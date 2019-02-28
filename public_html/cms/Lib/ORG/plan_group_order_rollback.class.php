<?php
class plan_group_order_rollback extends plan_base{
	public function runTask(){
		//20分钟前的订单回滚
		$nowtime = time() - 1200;
		$orders = M('Group_order')->field('group_id, order_id, num')->where("`stock_reduce_method`=1 AND `paid`=0 AND `status`=0 AND `add_time`<'{$nowtime}'")->order('order_id ASC')->limit('0, 30')->select();
		$groups = array();
		foreach ($orders as $order){
			$this->keepThread();
			if (isset($groups[$order['group_id']])) {
				$groups[$order['group_id']] += $order['num'];
			} else {
				$groups[$order['group_id']] = $order['num'];
			}
			M('Group_order')->where(array('order_id' => $order['order_id']))->save(array('status' => 5));
		}
		foreach ($groups as $group_id => $num) {
			$this->keepThread();
			M('Group')->where(array('group_id' => $group_id, 'sale_count' => array('egt', $num)))->setDec('sale_count', $num);
		}
		return true;
	}
}
?>