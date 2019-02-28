<?php
class RechargeAction extends BaseAction
{
	public function index()
	{
		$ticket = I('ticket', false);
		if ($ticket) {
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			$this->user_session['uid'] = $info['uid'];
		}

		if(empty($this->user_session)){
			$this->returnCode('20044010');
		}


		if($_POST['money']>0){
				$data_user_recharge_order['uid'] = $this->user_session['uid'];
				$money = floatval($_POST['money']);
				if(empty($money) || $money > 10000){
					$this->returnCode('10052015');
				}
				if($_POST['label']){
					$data_user_recharge_order['label'] = $_POST['label'];
				}
				$data_user_recharge_order['money'] = $money;
				// $data_user_recharge_order['order_name'] = '帐户余额在线充值';
				$data_user_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
				$data_user_recharge_order['is_mobile_pay'] = 1;
			//print_r($data_user_recharge_order);
				if($order_id = D('User_recharge_order')->data($data_user_recharge_order)->add()){
					if($_POST['type']=='gift'){
						$this->returnCode(0,array('order_id'=>$order_id,'type'=>'gift'));
					}elseif($_GET['type']=='classify') {
						$this->returnCode(0,array('order_id'=>$order_id,'type'=>'classify'));
					}else{
						$this->returnCode(0,array('order_id'=>$order_id,'type'=>'recharge'));
					}
				}
		}else{
			$this->returnCode('10070002');
		}

	}
}
?>