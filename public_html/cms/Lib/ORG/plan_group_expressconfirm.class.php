<?php
//超时自动取消订单
class plan_group_expressconfirm extends plan_base
{
	
	public function runTask()
	{
		$order_list = D('Group')->wait_for_confirm_express_list();
		$this->keepThread();
		return true;
	}
}
?>