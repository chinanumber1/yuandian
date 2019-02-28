<?php
class Card_new_recharge_orderModel extends Model{
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
				'pay_system_balance' 	=> false,		  //平台余额
				'pay_system_coupon' 	=> false,		  //平台优惠券
				'pay_system_score' 		=> false,		  //平台积分抵现
				'order_info' 			=> $now_order,	  //平台积分抵现
		);
		$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
		if(C('config.open_sub_mchid') && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id']>0 ){
			$order_info['pay_merchant_ownpay'] = true;
		}
		return $order_info;
	}
	public function get_order_url($order_id,$is_mobile){
		$now_order = $this->get_order($order_id);

		return './wap.php?c=My_card&a=merchant_card&mer_id='.$now_order['mer_id'];
	}
	public function after_pay($order_id,$plat_order_info){
		$now_order = $this->get_order($order_id);

		if(isset($plat_order_info['sub_mch_id']) && $plat_order_info['sub_mch_id'] >0){
			D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],0,$now_order['give_money']+$now_order['money'],$now_order['give_score'],'','在线充值(子商户充值)'.floatval($now_order['give_money']+$now_order['money']).'元');
			if(C('config.user_own_pay_get_score')!=1){	
				if(C('config.open_score_get_percent')==1){
					$score_get = C('config.score_get_percent')/100;
				}else{
					$score_get =   C('config.user_score_get');
				}
				D('User')->add_score($now_order['uid'], round(($now_order['money'] ) *$score_get), '会员卡在线充值获得'.C('config.score_name'));
			}
		}else{
			D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['money'],$now_order['give_money'],$now_order['give_score'],'在线充值'.floatval($now_order['money']).'元','在线充值'.floatval($now_order['money']).'元赠送');
			if(C('config.open_score_get_percent')==1){
				$score_get = C('config.score_get_percent')/100;
			}else{
				$score_get =   C('config.user_score_get');
			}
			D('User')->add_score($now_order['uid'], round(($now_order['money'] ) *$score_get), '会员卡在线充值获得'.C('config.score_name'));
		}

	}
}
?>