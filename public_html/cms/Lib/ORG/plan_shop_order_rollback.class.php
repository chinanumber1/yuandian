<?php
class plan_shop_order_rollback extends plan_base
{
	public function runTask()
	{
		$time = time();
		$sql = "SELECT o.*,s.rollback_time FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS s ON s.store_id=o.store_id WHERE o.paid=0 AND o.reduce_stock_type=1 AND o.is_rollback=0 AND (s.rollback_time>0 AND {$time}-o.create_time>s.rollback_time*60)";
		$res = D()->query($sql);
		
		$goods_db = D("Shop_goods");
		$order_detail_db = D('Shop_order_detail');
		$order_db = D('Shop_order');
		$order_log_db = D('Shop_order_log');
		foreach ($res as $row) {
			$this->keepThread();
			$order_db->where(array('order_id' => $row['order_id']))->save(array('status' => 5, 'is_rollback' => 1));
			$order_log_db->add_log(array('order_id' => $row['order_id'], 'status' => 10, 'name' => '超时未付款系统自动取消', 'phone' => '', 'note' => '超时未付款，系统自动取消 (' . $row['rollback_time'] . '分钟)'));
			if (($row['paid'] == 1 || $row['reduce_stock_type'] == 1) && $row['is_rollback'] == 0) {
				$details = $order_detail_db->field(true)->where(array('order_id' => $row['order_id']))->select();
				foreach ($details as $menu) {
					$goods_db->update_stock($menu, 1);//修改库存
				}
			}
		}
		return true;
	}
}
?>