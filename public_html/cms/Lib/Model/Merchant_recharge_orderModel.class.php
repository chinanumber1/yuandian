<?php
class Merchant_recharge_orderModel extends Model{
	public function get_pay_order($mer_id,$order_id,$is_web=false){
		$now_order = $this->get_order_by_id($mer_id,$order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}

		if($is_web){
			$order_info = array(
				'order_id'			=>	$now_order['order_id'],
				'order_type'		=> 'merrecharge',
				'recharge_money'	=>	floatval($now_order['money']),
				'order_name'		=>	'在线充值',
				'order_num'			=>	1,
				'num'   		=> 1,
				'price' 		=> floatval($now_order['money']),
				'money' 	=> floatval($now_order['money']),
				'order_total_money' 	=> floatval($now_order['money']),


			);
		}
		return array('error'=>0,'order_info'=>$order_info);
	}
	public function get_order_by_id($mer_id,$order_id){
		$condition_user_recharge_order['uid'] = $mer_id;
		$condition_user_recharge_order['order_id'] = $order_id;
		return $this->field(true)->where($condition_user_recharge_order)->find();
	}
	//电脑站支付前订单处理
	public function web_befor_pay($order_info,$now_merchant){

		$data_user_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user_recharge_order['submit_order_time'] = $_SERVER['REQUEST_TIME'];
		$condition_user_recharge_order['order_id'] = $order_info['order_id'];
		if(!$this->where($condition_user_recharge_order)->data($data_user_recharge_order)->save()){
			return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
		}
		return array('error_code'=>false,'pay_money'=>$order_info['order_total_money']);

	}


	public function after_pay($order_param){

		if($order_param['pay_type']!=''){
			$where['orderid'] = $order_param['order_id'];
		}else{
			$where['order_id'] = $order_param['order_id'];
		}

		$now_order = $this->field(true)->where($where)->find();

		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在');
		}else if($now_order['paid'] == 1){
			return array('error'=>1,'msg'=>'该订单已付款！','url'=>$this->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay'],$now_order));
		}else{
			//得到当前商家信息，不将session作为调用值，因为可能会失效或错误。
			$now_merchant = D('Merchant')->get_info($now_order['mer_id']);

			if(empty($now_merchant)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的商家，请联系管理员！');
			}

			$data_user_recharge_order = array();
			$data_user_recharge_order['pay_time'] = $_SERVER['REQUEST_TIME'];
			$data_user_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_user_recharge_order['payment_money'] = floatval($order_param['pay_money']);
			$data_user_recharge_order['pay_type'] = $order_param['pay_type'];
			$data_user_recharge_order['third_id'] = $order_param['third_id'];
			$data_user_recharge_order['paid'] = 1;
			if($this->where($where)->save($data_user_recharge_order)){

				//$return  = D('Merchant_money_list')->add_money($now_order['mer_id'],'商家在线充值',$order_param);
				$order_param['desc']='商家在线充值';
				$order_param['mer_id']=$now_order['mer_id'];
				D('SystemBill')->bill_method(0,$order_param);

				$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
				if($now_merchant['money']>0 && $now_merchant['status']==3){
					M('Merchant')->where(array('mer_id'=>$now_order['mer_id']))->setField('status',1);
				}

				D('Scroll_msg')->add_msg('mer_recharge',$now_merchant['uid'],'商家【'.$now_merchant['name'].'】于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'充值成功！');

				return array('error'=>0,'msg'=>'充值成功！','url'=>$this->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay'],$now_order));

			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}

	public function get_pay_after_url($label,$is_mobile = false,$now_order){
		if($label){
			$labelArr = explode('_',$label);

			if($labelArr[0] == 'wap'){
				switch($labelArr[1]){
				}
			}else{

				switch($labelArr[1]){
					case 'merauth':
						return U('Merchant/Merchant_money/pay_merchant_service',array('auth_id'=>$labelArr[2]));
						break;
					case 'marketorder':
						return U('Merchant/Market/pay_success',array('order_id'=>$labelArr[2]));
						break;
					case 'merauthgroup':
						return U('Merchant/Merchant_money/pay_merchant_service',array('menu_group'=>$labelArr[2]));
						break;
				}
			}
		}else{
			if($is_mobile){
				return U('Merchant/Merchant_money/index');
			}else{
				return U('Merchant/Merchant_money/index');
			}
		}
	}


}
?>