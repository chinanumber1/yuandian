<?php
class Store_recharge_orderModel extends Model{
	public function get_pay_order($store_id,$order_id,$is_web=false){
		$now_order = $this->get_order_by_id($store_id,$order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}

		if($is_web){
			$order_info = array(
				'order_id'			=>	$now_order['order_id'],
				'order_type'		=> 'strecharge',
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
	public function get_order_by_id($store_id,$order_id){
		$condition_user_recharge_order['uid'] = $store_id;
		$condition_user_recharge_order['order_id'] = $order_id;
		return $this->field(true)->where($condition_user_recharge_order)->find();
	}
	//电脑站支付前订单处理
	public function web_befor_pay($order_info,$now_store){

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
			//得到当前店铺信息，不将session作为调用值，因为可能会失效或错误。
			$now_store = D('Merchant_store')->get_store_by_storeId($now_order['store_id']);

			if(empty($now_store)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的店铺，请联系管理员！');
			}

			$data_user_recharge_order = array();
			$data_user_recharge_order['pay_time'] = $_SERVER['REQUEST_TIME'];
			$data_user_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_user_recharge_order['payment_money'] = floatval($order_param['pay_money']);
			$data_user_recharge_order['pay_type'] = $order_param['pay_type'];
			$data_user_recharge_order['third_id'] = $order_param['third_id'];
			$data_user_recharge_order['paid'] = 1;
			if($this->where($where)->save($data_user_recharge_order)){
				$order_param['desc'] = '店铺在线充值';
				$order_param['store_id'] = $now_order['store_id'];
				$return  = D('Store_money_list')->add_money($order_param);
				$now_store = D('Merchant_store')->get_store_by_storeId($now_order['store_id']);
				if($now_store['money']>0 && $now_store['status']==3){
					M('Store')->where(array('store_id'=>$now_order['store_id']))->setField('status',1);
				}

				D('Scroll_msg')->add_msg('store_recharge',$now_store['uid'],'店铺【'.$now_store['name'].'】于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'充值成功！');

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
						return U('Merhcant/Store/pay_store_service',array('auth_id'=>$labelArr[2]));
						break;
				}
			}
		}else{
			if($is_mobile){
				return U('Merhcant/Store/recharge_list');
			}else{
				return U('Merhcant/Store/recharge_list');
			}
		}
	}


}
?>