<?php
class Distributor_agent_orderModel extends Model{
	public function get_order($order_id){
		return $this->where(array('order_id'=>$order_id))->find();
	}
	public function get_pay_order($order_id){
		$now_order = $this->get_order($order_id);
		$order_info = array(
				'pay_offline' 			=> false,		  //线下支付
				'pay_merchant_balance' 	=> false,		  //商家余额
				'pay_merchant_coupon' 	=> false,		  //商家优惠券
				'pay_merchant_ownpay' 	=> false,		  //商家自有支付
				'pay_system_balance' 	=> true,		  //平台余额
				'pay_system_coupon' 	=> false,		  //平台优惠券
				'pay_system_score' 		=> false,		  //平台积分抵现
				'order_info' 			=> $now_order,	  //平台积分抵现
		);
		return $order_info;
	}
	public function get_order_url($order_id,$is_mobile){
		$now_order = $this->get_order($order_id);
		return C('config.site_url').'/wap.php?c=My&a=index';
	}
	public function after_pay($order_id,$plat_order_info){
		$now_order = $this->get_order($order_id);
		if($now_order['type']==2){
			$data['uid'] =  $now_order['uid'];
			$data['type'] =  $now_order['type'];
			$data['status'] = 1;
			$data['start_time'] =  $_SERVER['REQUEST_TIME'];
			$data['end_time'] =  $_SERVER['REQUEST_TIME']+86400*365*C('config.agent_effective_time');
			M('Distributor_agent')->add($data);
			$data_distributor['uid'] =  $now_order['uid'];
			$data_distributor['type'] = 1;
			$data_distributor['status'] = 1;
			$data_distributor['start_time'] =  $_SERVER['REQUEST_TIME'];
			$data_distributor['end_time'] =  $_SERVER['REQUEST_TIME']+86400*365*C('config.agent_effective_time');
			M('Distributor_agent')->add($data_distributor);
		}else if($now_order['type']==1){
			$data_distributor['uid'] =  $now_order['uid'];
			$data_distributor['type'] = 1;
			$data_distributor['status'] = 1;
			$data_distributor['start_time'] =  $_SERVER['REQUEST_TIME'];
			$data_distributor['end_time'] =  $_SERVER['REQUEST_TIME']+86400*365*C('config.agent_effective_time');
			M('Distributor_agent')->add($data_distributor);
		}
		if(C('config.distributor_level')>0){
			M('User')->where(array('uid'=>$now_order['uid']))->setField('level',C('config.distributor_level'));
		}

		D('Distributor_agent')->spread_money($now_order,$now_order['type']);


	}


}
?>