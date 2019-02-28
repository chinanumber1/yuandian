<?php
class Mobile_recharge_orderModel extends Model{
	public function get_pay_order($order_id){
		$now_order = $this->get_order_by_id($order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}


			$order_info = array(
				'order_id'			=>	$now_order['order_id'],
				'order_type'		=> 'mobile_recharge',
				'money'	=>	floatval($now_order['money']),
				'order_name'		=>	'话费充值',
				'order_num'			=>	1,
				'num'   		=> 1,
				'price' 		=> floatval($now_order['money']),
				'money' 	=> floatval($now_order['money']),
				'order_total_money' 	=> floatval($now_order['money']),
				'pay_system_score'=>true,
				'pay_system_balance'=>true,

			);

		return array('error'=>0,'order_info'=>$order_info);
	}
	public function get_order_by_id($order_id){
		$condition_user_recharge_order['order_id'] = $order_id;
		return $this->field(true)->where($condition_user_recharge_order)->find();
	}
	//电脑站支付前订单处理
	public function web_befor_pay($order_info,$now_village){

		$data_user_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user_recharge_order['submit_order_time'] = $_SERVER['REQUEST_TIME'];
		$condition_user_recharge_order['order_id'] = $order_info['order_id'];
		if(!$this->where($condition_user_recharge_order)->data($data_user_recharge_order)->save()){
			return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
		}
		return array('error_code'=>false,'pay_money'=>$order_info['order_total_money']);

	}


	public function after_pay($order_id,$plat_order_info){
		$where['order_id'] = $order_id;


		$now_order = $this->field(true)->where($where)->find();

		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在');
		}else if($now_order['paid'] == 1){
			return array('error'=>1,'msg'=>'该订单已付款！','url'=>$this->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay'],$now_order));
		}else{
			//得到当前社区信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);

			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找用户，请联系管理员！');
			}

			$data_user_recharge_order = array();
			$data_user_recharge_order['pay_time'] = $_SERVER['REQUEST_TIME'];
			$data_user_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_user_recharge_order['pay_type'] = $plat_order_info['pay_type'];
			$data_user_recharge_order['third_id'] = $plat_order_info['third_id'];
			$data_user_recharge_order['paid'] = 1;
			$data['status'] = 1;
			if($this->where($where)->save($data_user_recharge_order)){
				import('@.ORG.mobile_recharge');
				$appkey = C('config.mobile_recharge_APIKey');
				$openid = C('config.mobile_recharge_openid');
				$recharge = new mobile_recharge($appkey,$openid);
				$res = $recharge->telcz($now_order['phone'],strval(floatval($now_order['old_money'])),$now_order['orderid']);

				$data['label'] = serialize($res['result']);
				$data['status'] = 2;
				$this->where($where)->save($data);
				if($res['error_code']){
					$i=0;
					do {
						$return  = D('Plat_order')->order_refund(array('business_type'=>'mobile_recharge','business_id'=>$now_order['order_id']));
						$i++;
					} while ($return['error']==1&&$i<=10);

					if(!$return['error']){
						$return['msg'] =$res['reason']. ',充值失败，已退款';
						$data['status'] = 5;
						$data['label'] = serialize($return);
					}else{
						$return['msg'] = '充值失败，未退款';
						$data['status'] = 6;
						$data['label'] .= serialize($return);
					}
					$this->where($where)->save($data);
					return array('error'=>1,'msg'=>$res['reason']);
				}else{
					if(C('config.mobile_score_get_percent')==0){
						$score_get = C('config.user_mobile_score_get');
					}else if(C('config.mobile_score_get_percent')==1){
						$score_get = C('config.mobile_score_percent')/100;
					}
					D('User')->add_score($now_user['uid'],round($now_order['money']*$score_get),'用户充值话费赠送积分');
					D('Scroll_msg')->add_msg('mobile_recharge',$now_user['uid'],'用户【'.$now_user['nickname'].'】于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'给【'. substr_replace($now_order['phone'],'****',3,4).'】充值成功！');
					return array('error'=>0,'msg'=>$res['reason'],'url'=>$this->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay'],$now_order));
				}


			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}


	public function get_order_url($order_id, $is_mobile)
	{
		$now_order = $this->get_order_by_id($order_id);
		if ($now_order) {
			return C('config.site_url').'/wap.php?c=Third_recharge&a=mobile_recharge_back&order_id='.$order_id;
		}else{
			return array('error'=>1,'msg'=>'未知订单！');
		}

	}


	public function get_pay_after_url($label,$is_mobile = false,$now_order){
		if($label){
			$labelArr = explode('_',$label);

			if($labelArr[0] == 'wap'){
				switch($labelArr[1]){
				}
			}else{


			}
		}else{
			if($is_mobile){
				return U('My/mobile_recharge_list');
			}
		}
	}


}
?>