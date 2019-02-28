<?php
/*拼团自动退款

 * */
class plan_group_order_cancel extends plan_base{
	public function runTask(){
		$order_list  = D('Group_start')->group_refund();

		if(!empty($order_list)){
			foreach ($order_list as $v){
				$this->keepThread();
				$now_order = $v;
				if($now_order['payment_money'] >0){
					if($now_order['is_own']){
						$pay_method = array();
						$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$now_order['mer_id']))->find();
						foreach($merchant_ownpay as $ownKey=>$ownValue){
							$ownValueArr = unserialize($ownValue);
							if($ownValueArr['open']){
								$ownValueArr['is_own'] = true;
								$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
							}
						}
						$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
						if ($now_merchant['sub_mch_refund'] && C('config.open_sub_mchid') && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
							$pay_method['weixin']['config']['pay_weixin_appid'] = C('config.pay_weixin_appid');
							$pay_method['weixin']['config']['pay_weixin_appsecret'] = C('config.pay_weixin_appsecret');
							$pay_method['weixin']['config']['pay_weixin_mchid'] =C('config.pay_weixin_sp_mchid');
							$pay_method['weixin']['config']['pay_weixin_key'] = C('config.pay_weixin_sp_key');
							$pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
							$pay_method['weixin']['config']['pay_weixin_client_cert'] = C('config.pay_weixin_sp_client_cert');
							$pay_method['weixin']['config']['pay_weixin_client_key'] = C('config.pay_weixin_sp_client_key');
							$pay_method['weixin']['config']['is_own'] = 2 ;
						}
					}else{
					    $pay_method = D('Config')->get_pay_method(0, 0, $now_order['is_mobile_pay'] == 1 ? true : false, $now_order['is_mobile_pay'] == 2 ? true : false);
					}

					if(empty($pay_method)){
						$data_group_order['order_id'] = $now_order['order_id'];
						$data_group_order['status'] = 7;
						$data_group_order['refund_detail'] = '自动退款失败，原因：未找到支付方式';
						D('Group_order')->data($data_group_order)->save();
						continue;
					}
					if(empty($pay_method[$now_order['pay_type']])){
						$data_group_order['order_id'] = $now_order['order_id'];
						$data_group_order['status'] = 7;
						$data_group_order['refund_detail'] = '自动退款失败，原因：支付方式不存在';
						D('Group_order')->data($data_group_order)->save();
						continue;
					}

					$pay_class_name = ucfirst($now_order['pay_type']);
					$import_result = import('@.ORG.pay.'.$pay_class_name);
					if(empty($import_result)){
						$data_group_order['order_id'] = $now_order['order_id'];
						$data_group_order['status'] = 7;
						$data_group_order['refund_detail'] = '自动退款失败，原因：支付方式不存在';
						D('Group_order')->data($data_group_order)->save();
						continue;
					}
					$now_order['order_type'] = 'group';

					$tmp_order_id = $now_order['order_id'];
					if(!empty($now_order['orderid'])){
						$now_order['order_id']=$now_order['orderid'];
					}
					$pay_class = new $pay_class_name($now_order,$now_order['payment_money'],$now_order['pay_type'],$pay_method[$now_order['pay_type']]['config'],'',1);
					$go_refund_param = $pay_class->refund();
					$now_order['order_id'] = $tmp_order_id;
					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($go_refund_param['refund_param']);
					if(empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok'){
						$data_group_order['status'] = 3;
					}
					D('Group_order')->data($data_group_order)->save();

					if($data_group_order['status'] != 3||empty($data_group_order['status'])){
						$data_group_order['order_id'] = $now_order['order_id'];
						$data_group_order['status'] = 7;
						if($go_refund_param['order_param']){

							$data_group_order['refund_detail'] = '自动退款失败，原因：'.$go_refund_param['order_param']['error_msg'];
						}elseif($go_refund_param['refund_param']){
							$data_group_order['refund_detail'] = '自动退款失败，原因：'.$go_refund_param['refund_param']['err_msg'];
						}else{
							$data_group_order['refund_detail'] = serialize($go_refund_param);

						}

						D('Group_order')->data($data_group_order)->save();
						continue;
					}
				}

				//退积分
				if ($now_order['score_used_count']>0) {
					$order_info=unserialize($now_order['info']);
					$order_name=$order_info[0]['name']."*".$order_info[0]['num'];
					$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],'退款 '.$order_name.' '.C('config.score_name').'回滚');
					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Group_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$data_group_order['order_id'] = $now_order['order_id'];
						$data_group_order['status'] = 7;
						$data_group_order['refund_detail'] = '自动退款，退回'.C('config.score_name').'失败，原因：'.$result['msg'];
						D('Group_order')->data($data_group_order)->save();
					}
					$go_refund_param['msg'] .= $result['msg'];
				}

				//平台余额退款
				if($now_order['balance_pay']>0){
					$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$now_order['order_name'].' 增加余额');
					$param = array('refund_time' => time());
					if($add_result['error_code']){
						$param['err_msg'] = $add_result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$add_result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($add_result['error_code']) {
						$data_group_order['order_id'] = $now_order['order_id'];
						$data_group_order['status'] = 7;
						$data_group_order['refund_detail'] = '自动退款，退回余额失败，原因：'.$add_result['msg'];
						D('Group_order')->data($data_group_order)->save();
					}
					$go_refund_param['msg'] = '平台余额退款成功';
				}
				//商家会员卡余额退款

				if($now_order['card_give_money'] >0){
					$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],0,$now_order['card_give_money'],0,'退款 '.$now_order['order_name'].' 增加余额','退款 '.$now_order['order_name'].' 增加赠送余额');
					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($result['error_code']) {
						$data_group_order['order_id'] = $now_order['order_id'];
						$data_group_order['status'] = 7;
						$data_group_order['refund_detail'] = '自动退款，退回商家会员卡赠送余额失败，原因：'.$result['msg'];
						D('Group_order')->data($data_group_order)->save();
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				if($now_order['merchant_balance']>0){
					$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],0,0,'退款 '.$now_order['order_name'].' 增加余额','退款 '.$now_order['order_name'].' 增加赠送余额');
					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($result['error_code']) {
						$data_group_order['order_id'] = $now_order['order_id'];
						$data_group_order['status'] = 7;
						$data_group_order['refund_detail'] = '自动退款，退回商家会员卡余额失败，原因：'.$result['msg'];
						D('Group_order')->data($data_group_order)->save();
					}
					$go_refund_param['msg'] = $result['msg'];
				}

//				if($now_order['total_money']==0||$now_order['card_discount']==0){
				if(floatval($now_order['total_money'])==0){
					$data_group_order['order_id'] = $now_order['order_id'];
					$param = array('refund_time' => time());

					$param['refund_id'] = $now_order['order_id'];

					$data_group_order['refund_detail'] = serialize($param);
					$data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
				}

				//2015-12-9     退款时销量回滚
				$update_group = D('Group')->where(array('group_id' => $now_order['group_id']))->find();
				if ($update_group['type'] == 3) {
					$sale_count = $update_group['sale_count'] - $now_order['num'];
					$sale_count = $sale_count > 0 ? $sale_count : 0;
					$update_group_data = array('sale_count' => $sale_count);
					if ($update_group['count_num'] > 0 && $sale_count < $update_group['count_num']) {
						$update_group_data['type'] = 1;
					}
					D('Group')->where(array('group_id' => $now_order['group_id']))->save($update_group_data);
				} else {
					//退款时销量回滚
					D('Group')->where(array('group_id' => $now_order['group_id']))->setDec('sale_count', $now_order['num']);
				}

			}
		}
		return true;
	}

	protected function getPayName($label){
		$payName = array(
				'weixin' => '微信支付',
				'tenpay' => '财付通支付',
				'yeepay' => '银行卡支付(易宝支付)',
				'allinpay' => '银行卡支付(通联支付)',
				'chinabank' => '银行卡支付(网银在线)',
		);
		return $payName[$label];
	}
}
?>