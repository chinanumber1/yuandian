<?php
class plan_shop_order_finish extends plan_base
{
	public function runTask()
	{
	    $config = D('Config')->field('value')->where(array('name' => 'shop_order_finish'))->find();
	    $finishTime = isset($config['value']) ? intval($config['value']) : 0;//60 * C('config.shop_order_cancel_time');
	    if ($finishTime == 0) return true;
	    $finishTime = $finishTime * 86400;
	    $nowTime = time();
	    $time = $nowTime - $finishTime;
	    $res = D('Shop_order')->field(true)->where(array('is_pick_in_store' => 3, 'status' => 1, 'last_time' => array('lt', $time)))->order('last_time ASC')->limit(10)->select();
	    $order_db = D('Shop_order');
		$order_log_db = D('Shop_order_log');
		foreach ($res as $row) {
			$this->keepThread();
			$order_db->where(array('order_id' => $row['order_id']))->save(array('status' => 2, 'use_time' => $nowTime, 'last_time' => $nowTime));
			$order_db->shop_notice($row);
			$order_log_db->add_log(array('order_id' => $row['order_id'], 'status' => 7, 'name' => '超时未确认收货系统自动确认收货并消费', 'phone' => '', 'note' => '超时未确认收货，系统自动收货 (' . $config['value']. '天)'));
		}
		return true;
	}
}
?>