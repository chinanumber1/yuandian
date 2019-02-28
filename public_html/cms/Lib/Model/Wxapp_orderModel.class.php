<?php
class Wxapp_orderModel extends Model{
	public function get_pay_order($uid,$order_id=0){
		if(empty($order_id)){
			$wxapp_order_id = $_GET['orderid'] ? $_GET['orderid'] : $_GET['single_orderid'];
			$data_wxapp_order['from'] = $_GET['from'] ? $_GET['from'] : '';
			$data_wxapp_order['mer_id'] = $_GET['mer_id'] ? $_GET['mer_id'] : '';
			$data_wxapp_order['wxapp_order_id'] = $wxapp_order_id;
			$data_wxapp_order['order_name'] = $_GET['orderName'] ? $_GET['orderName'] : $this->getFromName($data_wxapp_order['from'],$wxapp_order_id);
			$data_wxapp_order['uid'] = $uid;
			$data_wxapp_order['money'] = $_GET['price'] ? $_GET['price'] : '';
			$data_wxapp_order['order_num'] = $_GET['pro_num'] ? $_GET['pro_num'] : 1;
			$data_wxapp_order['add_time'] = $_SERVER['REQUEST_TIME'];
			// dump($wxapp_order_id);
			// dump($data_wxapp_order);
			if(empty($wxapp_order_id) || empty($data_wxapp_order['from']) || empty($data_wxapp_order['mer_id']) || empty($data_wxapp_order['order_name']) || empty($data_wxapp_order['money']) || empty($data_wxapp_order['order_num'])) return(array('error'=>1,'msg'=>'访问参数错误，请重试'));
			$condition_weidian_order['wxapp_order_id'] = $wxapp_order_id;
			$now_order = $this->field('`order_id`,`uid`,`wxapp_order_id`,`money`,`paid`,`third_id`,`pay_type`')->where($condition_weidian_order)->find();
			if(empty($now_order)){
				$order_id = $this->data($data_wxapp_order)->add();
				if(empty($order_id)){
					return(array('error'=>1,'msg'=>'订单处理失败，请重试！'));
				}
			}else if(!empty($now_order['paid'])){
				return(array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>$this->get_wxapp_url(array('order_id'=>$now_order['order_id']))));
			}else{
				$order_id = $now_order['order_id'];
				$data_wxapp_order['order_id'] = $order_id;
				$this->data($data_wxapp_order)->save();
			}
			
			$order_info = array(
				'order_id'			=>	$order_id,
				'mer_id'			=>	$data_wxapp_order['mer_id'],
				'order_type'		=>	'wxapp',
				'order_name'		=>	$data_wxapp_order['order_name'],
				'order_num'			=>	$data_wxapp_order['order_num'],
				'order_total_money'	=>	floatval($data_wxapp_order['money']),
				'coupon_url_param'  =>   array('type'=>'wxapp','order_id'=>$order_id),
			);		
		}else{
			$now_order = $this->get_order_by_id($order_id);
			if(empty($now_order)){
				return array('error'=>1,'msg'=>'当前订单不存在！');
			}else if(!empty($now_order['paid'])){
				return(array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>$this->get_wxapp_url(array('order_id'=>$now_order['order_id']))));
			}
			$order_info = array(
				'order_id'			=>	$now_order['order_id'],
				'mer_id'			=>	$now_order['mer_id'],
				'order_type'		=>	'wxapp',
				'order_name'		=>	$now_order['order_name'],
				'order_num'			=>	$now_order['order_num'],
				'order_total_money'	=>	floatval($now_order['money']),
			);	
		}
		return array('error'=>0,'order_info'=>$order_info);
	}
	public function getFromName($from,$orderid){
		switch($from){
			case 'Unitary':
				return '一元夺宝';
			default:
				return $orderid;
		}
	}
	public function get_order_by_id($order_id){
		$condition_wxapp_order['order_id'] = $order_id;
		return $this->field(true)->where($condition_wxapp_order)->find();
	}
	//手机端支付前订单处理
	public function wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user){

		//去除微信优惠的金额
		$pay_money = $order_info['order_total_money'];
		$pay_money = sprintf("%.2f",$pay_money*$merchant_balance['card_discount']/10);

		//判断优惠券
//		if(!empty($now_coupon['price'])){
//			$data_wxapp_order['card_id'] = $now_coupon['record_id'];
//			$data_wxapp_order['card_price'] = $now_coupon['price'];
//			if($now_coupon['price'] >= $pay_money){
//				$order_result = $this->wap_pay_save_order($order_info,$data_wxapp_order);
//				if($order_result['error_code']){
//					return $order_result;
//				}
//				return $this->wap_after_pay_before($order_info);
//			}
//			$pay_money -= $now_coupon['price'];
//		}

		if ($now_coupon['card_price'] >0) {
			$data_wxapp_order['card_id'] = $now_coupon['merc_id'];
			$data_wxapp_order['card_price'] = round($now_coupon['card_price'] * 100)/100;
			if ($now_coupon['card_price'] >= $pay_money) {
				$order_result = $this->wap_pay_save_order($order_info, $data_wxapp_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['card_price'];
		}
		
		//判断商家余额
		if(!empty($merchant_balance['card_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_money'] >= $pay_money){
				$data_wxapp_order['merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_wxapp_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_wxapp_order['merchant_balance'] = $merchant_balance['card_money'];
			}
			$pay_money -= $merchant_balance['card_money'];
		}

		if(!empty($merchant_balance['card_give_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_give_money'] >= $pay_money){
				$data_wxapp_order['card_give_money'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_wxapp_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_wxapp_order['card_give_money'] = $merchant_balance['card_give_money'];
			}
			$pay_money -= $merchant_balance['card_give_money'];
		}
		
		//判断帐户余额
		if(!empty($now_user['now_money'])&&$order_info['use_balance']){
			if($now_user['now_money'] >= $pay_money){
				$data_wxapp_order['balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_wxapp_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_wxapp_order['balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
		}
		//在线支付
		$order_result = $this->wap_pay_save_order($order_info,$data_wxapp_order);
		if($order_result['error_code']){
			return $order_result;
		}
		return array('error_code'=>false,'pay_money'=>$pay_money);
	}
	//手机端支付前保存各种支付信息
	public function wap_pay_save_order($order_info,$data_wxapp_order){
		$data_wxapp_order['card_id'] 			= !empty($data_wxapp_order['card_id']) ? $data_wxapp_order['card_id'] : 0;
		$data_wxapp_order['merchant_balance'] 	= !empty($data_wxapp_order['merchant_balance']) ? $data_wxapp_order['merchant_balance'] : 0;
		$data_wxapp_order['card_give_money'] 	= !empty($data_wxapp_order['card_give_money']) ? $data_wxapp_order['card_give_money'] : 0;
		$data_wxapp_order['card_discount'] 	= !empty($data_wxapp_order['card_discount']) ? $data_wxapp_order['card_discount'] : 0;
		$data_wxapp_order['balance_pay']	 	= !empty($data_wxapp_order['balance_pay']) ? $data_wxapp_order['balance_pay'] : 0;
		$data_wxapp_order['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_wxapp_order['card_price']	 	= !empty($data_wxapp_order['card_price']) ? $data_wxapp_order['card_price'] : 0;
		$condition_wxapp_order['order_id'] = $order_info['order_id'];
		if($this->where($condition_wxapp_order)->data($data_wxapp_order)->save()){
			return array('error_code'=>false,'msg'=>'保存订单成功！');
		}else{
			return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
		}
	}
	//如果无需调用在线支付，使用此方法即可。
	public function wap_after_pay_before($order_info){
		$order_param = array(
			'order_id' => $order_info['order_id'],
			'pay_type' => '',
			'third_id' => '',
		);
		$result_after_pay = $this->after_pay($order_param);
		if($result_after_pay['error']){
			return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
		}else{
			return array('error_code'=>false,'msg'=>'支付成功','url'=>$result_after_pay['url']);
		}
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
			return array('error'=>1,'msg'=>'该订单已付款','url'=>$this->get_wxapp_url(array('order_id'=>$now_order['order_id'],'status'=>2,'msg'=>'该订单已付款')));
		}else{
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}
				
			//判断优惠券是否正确
//			if($now_order['card_id']){
//				$now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($now_order['card_id'],$now_order['uid']);
//				if(empty($now_coupon)){
//					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
//				}
//			}
			if($now_order['card_id']){
				$now_coupon = D('Card_new_coupon')->get_coupon_by_id($now_order['card_id']);
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}
				
			//判断会员卡余额
			$merchant_balance = floatval($now_order['merchant_balance']);
			if($merchant_balance){
				$user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$now_order['mer_id']);
				if($user_merchant_balance['card_money'] < $merchant_balance){
					return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
				}
			}

			$card_give_money = floatval($now_order['card_give_money']);
			if($card_give_money){
				$user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$now_order['mer_id']);
				if($user_merchant_balance['card_money_give'] < $card_give_money){
					return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
				}
			}
			//判断帐户余额
			$balance_pay = floatval($now_order['balance_pay']);
			if($balance_pay){
				if($now_user['now_money'] < $balance_pay){
					return $this->wap_after_pay_error($now_order,$order_param,'您的帐户余额不够此次支付！');
				}
			}
				
			//如果使用了商家优惠券
//			if($now_order['card_id']){
//				$use_result = D('Member_card_coupon')->user_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);
//				if($use_result['error_code']){
//					return array('error'=>1,'msg'=>$use_result['msg']);
//				}
//			}
			if($now_order['card_id']){
				$use_result = D('Card_new_coupon')->user_coupon($now_order['card_id'],$now_order['order_id'],$order_param['order_type'],$now_order['mer_id'],$now_order['uid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}
				
			//如果使用会员卡余额
			if($merchant_balance){
				$use_result = D('Card_new')->use_money($now_order['uid'],$now_order['mer_id'],$merchant_balance,'购买 '.$now_order['order_name'].' 扣除会员卡余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			if($card_give_money){
				$use_result = D('Card_new')->use_give_money($now_order['uid'],$now_order['mer_id'],$card_give_money,'购买 '.$now_order['order_name'].' 扣除会员卡赠送余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}
			//如果用户使用了余额支付，则扣除相应的金额。
			if(!empty($balance_pay)){
				$use_result = D('User')->user_money($now_order['uid'],$balance_pay,'购买 '.$now_order['order_id'].' 扣除余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}
				
			$data_weidian_order = array();
			$data_weidian_order['pay_time'] = isset($order_param['pay_time']) && $order_param['pay_time'] ? strtotime($order_param['pay_time']) : $_SERVER['REQUEST_TIME'];
			$data_weidian_order['payment_money'] = floatval($order_param['pay_money']);
			$data_weidian_order['pay_type'] = $order_param['pay_type'];
			$data_weidian_order['third_id'] = $order_param['third_id'];
			$data_weidian_order['is_own'] = isset($order_param['sub_mch_id'])?2:$order_param['is_own'];
			$data_weidian_order['paid'] = 1;
			if($this->where($where)->save($data_weidian_order)){

				if(C('config.add_score_by_percent')==0){	
					if(C('config.open_score_get_percent')==1){
						$score_get = C('config.score_get_percent')/100;
					}else{
						
						$score_get = C('config.user_score_get');
					}
					$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
					if($now_merchant['score_get_percent']>=0){
						$score_get = $now_merchant['score_get_percent']/100;
					}
					D('User')->add_score($now_order['uid'],round($now_order['money']*$score_get),'购买 '.$now_order['order_id'].' 消费'.floatval($now_order['money']).'元 获得'.C('config.score_name'));
					D('Scroll_msg')->add_msg('buy',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买微信营销成功获得'.C('config.score_name'));
				}
				D('Scroll_msg')->add_msg('wxapp',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买微信营销成功');
				
				$params = array(
					'from' => $now_order['from'],
					'transactionid' => $order_param['third_id'],
					'token' => $this->getToken($now_order['mer_id']),
					'orderid' => $now_order['wxapp_order_id'],
					'payType' => $order_param['pay_type'],
				);
				$params['sign'] 	= $this->getSign($params);
				import('ORG.Net.Http');
				$http = new Http();
				$http->curlGet(C('config.wxapp_url').'/index.php?g=Home&m=Auth&a=order&'.http_build_query($params));
				//支付成功增加商家余额
				$now_order = $this->field(true)->where($where)->find();
				$now_order['order_type']='wxapp';
				//D('Merchant_money_list')->add_money($now_order['mer_id'],'微信营销支付计入收入',$now_order);
				$now_order['desc']='微信营销支付计入收入';
				D('SystemBill')->bill_method($now_order['is_own'],$now_order);

				return array('error'=>0,'url'=>$this->get_wxapp_url(array('order_id'=>$now_order['order_id'])));
			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}
	
	/*模拟公众号token*/
	public function getToken($id){
		return substr(md5(C('config.site_url').$id.'o2o'),8,16);
	}
	/*Pigcms规定的密钥*/
	private function getSign($data){
		foreach ($data as $key => $value) {
			$validate[$key] = is_array($value) ? $this->getSign($value) : $value;
		}
		$validate['salt'] = C('config.wxapp_encrypt') ? C('config.wxapp_encrypt') : 'pigcms';
		sort($validate, SORT_STRING);
		return sha1(implode($validate));
	}
	
	//支付完成，跳回到微店系统
	public function get_wxapp_url($data){
		return C('config.site_url').'/wap.php?c=Wxapp&a=pay_back&order_id='.$data['order_id'];
	}
	
	//支付时，金额不够，记录到帐号
	public function wap_after_pay_error($now_order,$order_param,$error_tips){
		//记录充值的金额，因为 Pay/return_url 处没有返回order的具体信息，故在此调用。
		$user_result = D('User')->add_money($now_order['uid'],$order_param['pay_money'],'支付订单：'.$now_order['order_id'].'发生错误！原因是：'.$error_tips);
		if($order_param['pay_type']=='weixin'&&ACTION_NAME=='return_url'){
			exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
		}
		if($user_result['error_code']){
			return array('error'=>1,'msg'=>$user_result['msg']);
		}else{
			return array('error'=>1,'msg'=>$error_tips.'已将您充值的金额添加到您的余额内。');
		}
	}
	
	public function get_order_by_mer_id($mer_id, $is_system = false)
	{
		if ($is_system) {
			import('@.ORG.system_page');
		} else {
			import('@.ORG.merchant_page');
		}
		
		$time = time() - 10 * 86400;
		
		$count = $this->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}'")->count();
		$p = new Page($count, 20);
		$mode = new Model();
		$sql = "SELECT sum(payment_money+balance_pay) as price, is_pay_bill FROM ". C('DB_PREFIX') . "weidian_order WHERE mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}' GROUP BY is_pay_bill";
		$res = $mode->query($sql);
		$alltotal = $alltotalfinsh = 0;
		foreach ($res as $r) {
			$r['is_pay_bill'] && $alltotalfinsh += $r['price'];
			$r['is_pay_bill'] || $alltotal += $r['price'];
		}
		
		$sql = "SELECT order_id, order_name, uid, mer_id, store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "weidian_order WHERE mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline'";
		
		$sql .= " ORDER BY order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
		
		$res = $mode->query($sql);

		$total = $finshtotal = 0;
		foreach ($res as &$l) {
			$l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'], false);
			$total += $l['price'];
			$l['is_pay_bill'] && $finshtotal += $l['price'];
		}
		$pagebar = $p->show();
		return array('order_list' => $res, 'pagebar' => $pagebar, 'total' => $total, 'finshtotal' => $finshtotal, 'alltotalfinsh' => $alltotalfinsh, 'alltotal' => $alltotal);
	}

	static function createOrderNum() {
		$prefix = "10";
		return $prefix.str_shuffle(date("YmdHms").mt_rand(10, 99).mt_rand(10, 99));
	}
}
?>