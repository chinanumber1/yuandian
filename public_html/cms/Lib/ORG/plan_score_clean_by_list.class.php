<?php
//超时自动取消订单
class plan_score_clean_by_list extends plan_base
{
	public function runTask()
	{
		$order_list = D('User_score_list')->clean_user_score();
		$this->keepThread();
		return true;
	}
}
?>