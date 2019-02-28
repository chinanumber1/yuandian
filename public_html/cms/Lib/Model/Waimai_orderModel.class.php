<?php
/**
 *	Waimai_orderModel
 *	@author yaolei
 */
class Waimai_orderModel extends Model{
	protected $tableName = 'waimai_order';
	
	protected $_scope = array(
		'normal' => array(
            'where'=>array('delete' => 0),
        ),
	);
	
	/**
	 * 获取订单
	 * @param integer $uid
	 * @param integer $order_id
	 * @param boolean $is_web
	 */
	public function get_order_by_id($uid, $order_id, $is_web = false)
	{
		$where = array();
		$where['order_id'] = $order_id;
		$where['uid'] = $uid;
		return $this->scope('normal')->field(true)->where($where)->find();
	}
	
	/**
	 * 获取支付订单
	 * @param integer $uid
	 * @param integer $order_id
	 * @param boolean $is_web
	 * @param string  $type
	 */
	public function get_pay_order($uid, $order_id, $is_web = true, $type = 'waimai')
	{
		$now_order = $this->get_order_by_id($uid, $order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}
		if(!empty($now_order['paid'])){
			if($is_web){
				return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>U('Waimai/Order/detail',array('order_id'=>$now_order['order_id'])));
			}else{
				return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>C('config.site_url').'index.php?g=Waimaiwap&c=Index&a=order&order_id='.$now_order['order_id']);
			}
		}
	
		if ($is_web) {
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'mer_id'			=>	$now_order['mer_id'],
					'address_id'	    =>	$now_order['address_id'],
					'store_id'			=>	$now_order['store_id'],
					'uid'				=>	$now_order['uid'],
					'order_name'		=>  $now_order['order_number'],
					'order_type'		=>	$type,
					'pay_type'      	=>	$now_order['pay_type'],
					'balance_pay'		=>	$now_order['balance_pay'],//平台余额支付的金额
					'merchant_balance'	=>	$now_order['merchant_balance'],//商家余额支付的金额
					'order_total_money'	=>	floatval($now_order['discount_price'] - $now_order['balance_pay'] - $now_order['merchant_balance']),
			);
		} else {
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'mer_id'			=>	$now_order['mer_id'],
					'is_mobile'			=>	1,
					'address_id'	    =>	$now_order['address_id'],
					'store_id'			=>	$now_order['store_id'],
					'order_name'		=>  $now_order['order_number'],
					'order_type'		=>	$type,
					'pay_type'      	=>	$now_order['pay_type'],
					'uid'				=>	$now_order['uid'],
					'balance_pay'		=>	$now_order['balance_pay'],//平台余额支付的金额
					'merchant_balance'	=>	$now_order['merchant_balance'],//商家余额支付的金额
					'order_total_money'	=>	floatval($now_order['discount_price'] - $now_order['balance_pay'] - $now_order['merchant_balance']),
			);
		}

		return array('error'=>0,'order_info'=>$order_info);
	}
	
	//电脑站支付前订单处理
	public function web_befor_pay($order_info,$now_user) {
		//判断是否需要在线支付
		if ($now_user['now_money'] < $order_info['order_total_money']) {
			$online_pay = true;
		}else{
			$online_pay = false;
		}
		//不使用在线支付，直接使用用户余额。
		if(! $online_pay) {
			$order_pay['balance_pay'] = $order_info['order_total_money'];
		} else {
			if (! empty($now_user['now_money'])) {
				$order_pay['balance_pay'] = $now_user['now_money'];
			}
		}
	
		//将已支付用户余额等信息记录到订单信息里
		if (! empty($order_pay['balance_pay'])) {
			$data_waimai_order['balance_pay'] = $order_pay['balance_pay'];
		}
		if (! empty($data_waimai_order)) {
			$data_waimai_order['merchant_balance'] 	= 0;
			$data_waimai_order['last_time'] = $_SERVER['REQUEST_TIME'];
			$condition_waimai_order['order_id'] = $order_info['order_id'];
			
			if (!$this->where($condition_waimai_order)->data($data_waimai_order)->save()) {
				return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
		}
	
		if ($online_pay) {
			return array('error_code'=>false, 'pay_money'=>$order_info['order_total_money'] - $now_user['now_money']);
		} else {
			$order_param = array(
					'order_id' => $order_info['order_id'],
					'pay_type' => '',
					'third_id' => '',
					'is_mobile' => 0,
			);
			$result_after_pay = $this->after_pay($order_param);
			if($result_after_pay['error']){
				return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
			}
			return array('error_code'=>false,'msg'=>'支付成功！','url'=>U('Waimai/Order/detail',array('order_id'=>$order_info['order_id'])));
		}
	}
	
	//手机端支付前订单处理
	public function wap_befor_pay($order_info,$merchant_balance,$now_user){
		$pay_money = $order_info['order_total_money'];
		//判断商家余额
		if(!empty($merchant_balance)){
			if($merchant_balance >= $pay_money){
				$data_group_order['merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_group_order['merchant_balance'] = $merchant_balance;
			}
			$pay_money -= $merchant_balance;
		}
	
		//判断帐户余额
		if(!empty($now_user['now_money'])){
			if($now_user['now_money'] >= $pay_money){
				$data_group_order['balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_group_order['balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
		}
		//在线支付
		$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
		if($order_result['error_code']){
			return $order_result;
		}

		return array('error_code'=>false,'pay_money'=>$pay_money);
	}

	//手机端支付前保存各种支付信息
	public function wap_pay_save_order($order_info,$data_group_order){
		$data_group_order['merchant_balance'] 	= !empty($data_group_order['merchant_balance']) ? $data_group_order['merchant_balance'] : 0;
		$data_group_order['balance_pay']	 	= !empty($data_group_order['balance_pay']) ? $data_group_order['balance_pay'] : 0;
		$data_group_order['last_time'] = $_SERVER['REQUEST_TIME'];
		$condition_group_order['order_id'] = $order_info['order_id'];
		$result = $this->where($condition_group_order)->data($data_group_order)->save();
		if(false === $result){
			return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
		}else{
			return array('error_code'=>false,'msg'=>'保存订单成功！');
		}
	}

	//如果无需调用在线支付，使用此方法即可。
	public function wap_after_pay_before($order_info){
		$order_param = array(
			'order_id' => $order_info['order_id'],
			'is_mobile' => $order_info['is_mobile'],
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

	//移动端支付前订单处理
	public function mobile_befor_pay($order_info,$merchant_balance,$now_user){
		$pay_money = $order_info['order_total_money'];
		//判断商家余额
		if(!empty($merchant_balance)){
			if($merchant_balance >= $pay_money){
				$data_group_order['merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->mobile_after_pay_before($order_info);
			}else{
				$data_group_order['merchant_balance'] = $merchant_balance;
			}
			$pay_money -= $merchant_balance;
		}

		//判断帐户余额
		if(!empty($now_user['now_money'])){
			if($now_user['now_money'] >= $pay_money){
				$data_group_order['balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->mobile_after_pay_before($order_info);
			}else{
				$data_group_order['balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
		}
		//在线支付
		$order_result = $this->mobile_pay_save_order($order_info,$data_group_order);
		if($order_result['error_code']){
			return $order_result;
		}

		return array('error_code'=>false,'pay_money'=>$pay_money);
	}

	public function mobile_pay_save_order($order_info,$data_group_order){
		$data_group_order['merchant_balance'] 	= !empty($data_group_order['merchant_balance']) ? $data_group_order['merchant_balance'] : 0;
		$data_group_order['balance_pay']	 	= !empty($data_group_order['balance_pay']) ? $data_group_order['balance_pay'] : 0;
		$data_group_order['last_time'] = $_SERVER['REQUEST_TIME'];
		$condition_group_order['order_id'] = $order_info['order_id'];
		$result = $this->where($condition_group_order)->data($data_group_order)->save();
		if(false === $result){
			return array('error_code'=>true, 'msg'=>'保存订单失败！');
		}else{
			return array('error_code'=>false, 'msg'=>'保存订单成功！');
		}
	}

	public function mobile_after_pay_before($order_info){
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
	//获取某个时间段的订单总数
	public function get_all_oreder_count($type = 'day'){
		$stime = $etime = 0;
		switch ($type) {
			case 'day' :
				$stime = strtotime(date("Y-m-d") . " 00:00:00");
				$etime = strtotime(date("Y-m-d") . " 23:59:59");
				break;
			case 'week' :
				$d = date("w");
				$stime = strtotime(date("Y-m-d") . " 00:00:00") - $d * 86400;
				$etime = strtotime(date("Y-m-d") . " 23:59:59") + (6 - $d) * 86400;
				break;
			case 'month' :
				$stime = mktime(0, 0, 0, date("m"), 1, date("Y"));
				$etime = mktime(0, 0, 0, date("m") + 1, 1, date("Y"));
				break;
			case 'year' :
				$stime = mktime(0, 0, 0, 1, 1, date("Y"));
				$etime = mktime(0, 0, 0, 1, 1, date("Y")+1);
				break;
			default :;
		}
		$total = $this->where("`paid`=1 AND `create_time`>'$stime' AND `create_time`<'$etime'")->count();
		return $total;
	}
	//支付之后
	public function after_pay($order_param){
// 		if($order_param['pay_type']!=''){
// 			$condition_group_order['orderid'] = $order_param['order_id'];
// 		}else{
// 			$condition_group_order['order_id'] = $order_param['order_id'];
// 		}
		$condition_group_order['order_id'] = $order_param['order_id'];
		$now_order = $this->field(true)->where($condition_group_order)->find();
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}else if($now_order['paid'] == 1){
			if($order_param['is_mobile']){
				return array('error'=>1,'msg'=>'该订单已付款！','url'=>C('config.site_url').'/index.php?g=Waimaiwap&c=Order&a=detail&order_id='.$now_order['order_id']);
			}else{
				return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('Waimai/Order/detail',array('order_id'=>$now_order['order_id'])));
			}
		}else{
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}
			
			//判断会员卡余额
			$merchant_balance = floatval($now_order['merchant_balance']);
			if($merchant_balance){
				$user_merchant_balance = D('Member_card')->get_balance($now_order['uid'],$now_order['mer_id']);
				if($user_merchant_balance < $merchant_balance){
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

			//如果用户使用了余额支付，则扣除相应的金额。
			if(!empty($balance_pay)){
				$use_result = D('User')->user_money($now_order['uid'],$balance_pay,'购买 '.$now_order['order_name'].' 扣除余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//推送到客户端
			$login_log = D("Waimai_app_login_log")->field(true)->where(array('uid'=>$now_order['uid']))->order("create_time DESC")->find();
			if ($login_log) {
				$audience = array('tag'=>array($login_log['device_id']));
				//拼接extra pigcms_tag:order打开订单页  store打开店铺页  index打开首页  normal普通通知
				$extra = array(
					'pigcms_tag' => 'order',
					'store_id' => 0,
					'order_id' => $now_order['order_id']
				);
				$db = array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'waimai_order'=>'wo');
				$store = D()->table($db)->field("`ms`.`name`")->where('`wo`.`order_id`='.$now_order['order_id'].' AND `ms`.`store_id`=`wo`.`store_id`')->find();
				$client = $login_log['client'];
				$title = "您在【".$store['name']."】店铺下的订单已成功，请耐心等待商家接单。";
				$msg = "您在【".$store['name']."】店铺下的订单已成功，请耐心等待商家接单。";
				import('@.ORG.Jpush');
				$jpush = new Jpush(C('config.push_jpush_appkey'), C('config.push_jpush_secret'));
				$notification = $jpush->createBody($client, $title, $msg, $extra);

				$jpush->send("all", $audience, $notification);
			}
			
			//商家成交量自增
			$sale_count = D("Waimai_store")->where("`store_id`=".$now_order['store_id'])->setInc('total_sale', 1);

			$data_group_order['pay_time'] = $_SERVER['REQUEST_TIME'];
			$data_group_order['online_pay'] = floatval($order_param['pay_money']);
			$data_group_order['code'] = mt_rand(1000, 9999);
			$data_group_order['third_id'] = $order_param['third_id'];
			$data_group_order['is_mobile_pay'] = $order_param['is_mobile'];
			$data_group_order['is_own'] = $order_param['is_own'];
			$data_group_order['submit_order_time'] = $_SERVER['REQUEST_TIME'];
			$data_group_order['paid'] = 1;
			if($this->where($condition_group_order)->data($data_group_order)->save()){
				$this->addGoodsNum($now_order['order_id']);
				//$condition_group['group_id'] = $now_order['group_id'];
				//D('Group')->where($condition_group)->setInc('sale_count',$now_order['num']);
				
				/* 粉丝行为分析 */
				//D('Merchant_request')->add_request($now_order['mer_id'],array('group_buy_count'=>$now_order['num'],'group_buy_money'=>$now_order['total_money']));

				if ($now_user['openid'] && $order_param['is_mobile']) {
					$href = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$sell_condition['store_id'] = $now_order['store_id'];
					$sell_condition['order_id'] = $now_order['order_id'];
					$sell_info = D('Waimai_sell_log')->field('`order_id`, `goods_id`, `num`, `create_time`')->where($sell_condition)->select();
					$sell_list = $this->formatSellArray($sell_info);
					$keyword1 = '';
					$pre = '';
					foreach ($sell_list as $menu) {
						$keyword1 .= $pre . $menu['name'] . ':' . $menu['price'] . '*' . $menu['num'];
						$pre = '\n\t\t\t';
					}
					$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $this->config['waimai_alias_name'].'提醒', 'keyword1' => $keyword1, 'keyword2' => $now_order['order_id'], 'keyword3' => $now_order['discount_price'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => $this->config['waimai_alias_name'].'成功，您的消费码：'.$data_group_order['code']));
				}

				$msg = ArrayToStr::array_to_str($now_order['order_id'], 'waimai_order');
				$op = new orderPrint(C('config.print_server_key'), C('config.print_server_topdomain'));
				$op->printit($now_order['mer_id'], $now_order['store_id'], $msg, 1);
				
				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => 0, 'type' => 'group');
				if (C('config.sms_success_order') == 1 || C('config.sms_success_order') == 3) {
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_order['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您购买 '.$now_order['order_number'].'的订单(订单号：' . $now_order['order_id'] . ')已经完成支付,您的消费码：' . $data_group_order['code'];
					Sms::sendSms($sms_data);
				}
				if (C('config.sms_success_order') == 2 || C('config.sms_success_order') == 3) {
					$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $merchant['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客购买的' . $now_order['order_number'] . '的订单(订单号：' . $now_order['order_id'] . '),在' . date('Y-m-d H:i:s') . '时已经完成了支付！';
					Sms::sendSms($sms_data);
				}
				if($order_param['is_mobile']){
					return array('error'=>0,'url'=>C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id']);
				}else{
					return array('error'=>0,'url'=>U('Waimai/Order/detail',array('order_id'=>$now_order['order_id'])));
				}
			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
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
			if($order_param['is_mobile']){
				$return_url = str_replace('/source/','/',U('Waimai/Order/detail',array('order_id'=>$now_order['order_id'])));
			}else{
				$return_url = U('Waimai/Order/detail',array('order_id'=>$now_order['order_id']));
			}
			return array('error'=>1,'msg'=>$error_tips.'已将您充值的金额添加到您的余额内。','url'=>$return_url);
		}
	}

	//支付时，金额不够，记录到帐号
	public function mobile_after_pay_error($now_order,$order_param,$error_tips){
		//记录充值的金额，因为 Pay/return_url 处没有返回order的具体信息，故在此调用。
		$user_result = D('User')->add_money($now_order['uid'],$order_param['pay_money'],'在线充值');
		if($user_result['error_code']){
			return array('error'=>1,'msg'=>$user_result['msg']);
		}else{
			if($order_param['is_mobile']){
				$return_url = str_replace('/source/','/',U('Waimai/Order/detail',array('order_id'=>$now_order['order_id'])));
			}else{
				$return_url = U('Waimai/Order/detail',array('order_id'=>$now_order['order_id']));
			}
			return array('error'=>1,'msg'=>$error_tips.'已将您充值的金额添加到您的余额内。','url'=>$return_url);
		}
	}

	//修改订单状态
	public function change_status($order_id, $status){
		$condition_group_order['order_id'] = $order_id;
		$data_group_order['order_status'] = $status;
		if($this->where($condition_group_order)->data($data_group_order)->save()){
			return true;
		}else{
			return false;
		}
	}
	
	/*格式化订单数据*/
	public function formatArray($order_info, $store_info, $merchant_info, $deliverSupplyInfo){
		foreach($order_info as $key => $val){
			$storeId[$val['store_id']] = $val['store_id'];
		}
		$where['store_id'] = array('in', $storeId);
		$waimaiStoreInfo = D("Waimai_store")->field(true)->where($where)->select();
		if(!empty($store_info)){
			$store_array = array();
			foreach($store_info as $key => $val){
				$store_array[$val['store_id']]['store_name'] = $val['name'];
				$store_array[$val['store_id']]['store_phone'] = $val['phone'];
				
				$waimai_image_class = new store_image();
				$tmp_pic_arr = explode(';', $val['pic_info']);
				foreach ($tmp_pic_arr as $key => $value) {
					$pic_list[$key]['url'] = $waimai_image_class->get_image_by_path($value, 's');
				}
				$store_array[$val['store_id']]['pic_info'] = $pic_list[0]['url'];
			}
		}
		
		if(!empty($merchant_info)){
			$merchant_array = array();
			foreach($merchant_info as $key => $val){
				$merchant_array[$val['mer_id']]['mer_name'] = $val['name'];
			}
		}
		if(!empty($waimaiStoreInfo)){
			$waimaiStore_array = array();
			foreach($waimaiStoreInfo as $key => $val){
				$waimaiStore_array[$val['store_id']]['send_money'] = $val['send_money'];
				$waimaiStore_array[$val['store_id']]['total_money'] = $val['total_money'];
			}
		}
		if(!empty($deliverSupplyInfo)){
			$deliverSupply_array = array();
			foreach($deliverSupplyInfo as $key=>$val){
				$deliverSupply_array[$val['order_id']]['start_time'] = $val['start_time'];
				$deliverSupply_array[$val['order_id']]['end_time'] = $val['end_time'];
				//$deliverSupply_array[$val['order_id']]['supply_phone'] = $val['phone'];
				//$deliverSupply_array[$val['order_id']]['supply_name'] = $val['name'];
			}
		}
		$pay_method = D('Config')->get_pay_method();
		if(empty($pay_method)){
			return array('error'=>1, 'msg'=>'管理员没有开启任何一种支付方式。');
		}
		$pay_method_new = array();
		foreach ($pay_method as $k=>$v){
			$pay_method_new[$k]['name'] = $v['name'];
			$pay_method_new[$k]['type'] = $k;
		}
		foreach($order_info as &$val){
			$list = unserialize($val['address']);
			$val['address'] = $list['address'].$list['detail'];
			$val['nickname'] = $list['name'];
			$val['sex'] = $list['sex'];
			$val['phone'] = $list['phone'];
			$val['store_name'] = $store_array[$val['store_id']]['store_name'];
			$val['store_phone'] = $store_array[$val['store_id']]['store_phone'];
			$val['pic_info'] = $store_array[$val['store_id']]['pic_info'];
			$sell_condition['store_id'] = $val['store_id'];
			$sell_condition['order_id'] = $val['order_id'];
			$sell_info = D('Waimai_sell_log')->field('`order_id`, `goods_id`, `num`, `create_time`')->where($sell_condition)->select();
			$val['goods_count'] = D('Waimai_sell_log')->where($sell_condition)->count();
			$sell_list = $this->formatSellArray($sell_info);
			$val['goods_list'] = $sell_list;
			$val['mer_name'] = $merchant_array[$val['mer_id']]['mer_name'];
			$val['time_date'] = date('m-d', $val['create_time']);
			$date = date('m-d H:i', $val['create_time']);
			$val['time_mine'] = substr($date, 6);
			if (isset($pay_method_new[$val['pay_type']])) {
				$val['pay_type_type'] = $val['pay_type'];
				$val['pay_type'] =  $pay_method_new[$val['pay_type']]['name'];
			} elseif ($val['balance_pay'] > 0) {
				$val['pay_type_type'] = 'balance';
				$val['pay_type'] =  '平台余额支付';
			} elseif ($val['merchant_balance'] > 0) {
				$val['pay_type_type'] = 'merchant';
				$val['pay_type'] =  '商家余额支付';
			}
			//餐盒费
			$boxArray = array();  
			foreach($sell_list as $key=>$value){
				$boxArray[$key]['tools_price'] = $value['tools_price']*$value['num'];
				$boxArray[$key]['goods_money'] = $value['price']*$value['num'];
			}
			foreach($boxArray as $v){
				$toolsPrice += $v['tools_price'];
				$goodsMoney += $v['goods_money'];
			}
			$val['tools_price'] = $toolsPrice;  //餐盒费
			$val['start_time'] = $deliverSupply_array[$val['order_id']]['start_time'];
			$val['end_time'] = $deliverSupply_array[$val['order_id']]['end_time'];
			//$val['supply_phone'] = $deliverSupply_array[$val['order_id']]['supply_phone'];  	//配送员电话
			//$val['supply_name'] = $deliverSupply_array[$val['order_id']]['supply_name'];		//配送员姓名
			$val['total_money'] = $waimaiStore_array[$val['store_id']]['total_money'];   //满多少免配送费
			$val['send_money'] = floatval($waimaiStore_array[$val['store_id']]['send_money']);	//配送费
			$val['goods_money'] = $goodsMoney;	//商品总价
			//$val['create_time'] = date('Y-m-d H:i:s', $val['create_time']);
			$val['discount_price'] = floatval($val['discount_price']);
		}
		return $order_info;
	}
	
	public function formatSellArray($sell_info){
		foreach($sell_info as $key => $val){
			$goodsId[$val['goods_id']] = $val['goods_id'];
		}
		$where['goods_id'] = array('in', $goodsId);
		$goods_info = D('Waimai_goods')->field('`goods_id`,`name`,`unit`,`old_price`,`price`,`image`,`desc`,`tools_price`')->where($where)->select();
		if(!empty($goods_info)){
			$goods_array = array();
			foreach($goods_info as $key => $val){
				$goods_array[$val['goods_id']]['name'] = trim($val['name']);
				$goods_array[$val['goods_id']]['unit'] = $val['unit'];
				$goods_array[$val['goods_id']]['old_price'] = $val['old_price'];
				$goods_array[$val['goods_id']]['price'] = $val['price'];
				$goods_array[$val['goods_id']]['desc'] = $val['desc'];
				$goods_array[$val['goods_id']]['image'] = $val['image'];
				$goods_array[$val['goods_id']]['tools_price'] = $val['tools_price'];
			}
		}
		foreach($sell_info as &$val){
			$val['name'] = $goods_array[$val['goods_id']]['name'];
			$val['unit'] = $goods_array[$val['goods_id']]['unit'];
			$val['old_price'] = $goods_array[$val['goods_id']]['old_price'];
			$val['price'] = $goods_array[$val['goods_id']]['price'];
			$val['image'] = $goods_array[$val['goods_id']]['image'];
			$val['desc'] = $goods_array[$val['goods_id']]['desc'];
			
			$waimai_image_class = new waimai_image();
			$tmp_pic_arr = explode(';', $val['image']);
			foreach ($tmp_pic_arr as $key => $value) {
				$pic_list[$key]['title'] = $value;
				$pic_list[$key]['url'] = $waimai_image_class->get_image_by_path($value, 's');
			}
			
			$val['image'] = $pic_list;
			$val['tools_price'] = $goods_array[$val['goods_id']]['tools_price'];
		}
		return $sell_info;
	}
	
	static function createOrderNum() {
		$prefix = "10";
		return $prefix.str_shuffle(date("YmdHms").mt_rand(10, 99).mt_rand(10, 99));
	}
	
	public function addGoodsNum($order_id)
	{
		$order = $this->field(true)->where(array('order_id' => $order_id))->find();
		if ($order && $order['is_sell'] == 0) {
			$goods = D('Waimai_sell_log')->where(array('order_id' => $order_id))->select();
			foreach ($goods as $good) {
				if ($g = D('Waimai_goods')->field(true)->where(array('goods_id' => $good['goods_id']))->find()) {
					if ($g['sell_day'] == date('Ymd')) {
						D('Waimai_goods')->where(array('goods_id' => $good['goods_id']))->save(array('today_sell_count' => $good['num'] + $g['today_sell_count']));
					} else {
						D('Waimai_goods')->where(array('goods_id' => $good['goods_id']))->save(array('today_sell_count' => $good['num'], 'sell_day' => date('Ymd')));
					}
				}
			}
			$this->field(true)->where(array('order_id' => $order_id))->save(array('is_sell' => 1));
		}
	}
	
	public function reduceGoodsNum($order_id)
	{
		$today = date('Ymd');
		$order = $this->field(true)->where(array('order_id' => $order_id))->find();
		if ($order && $order['is_sell'] == 1) {
			$goods = D('Waimai_sell_log')->where(array('order_id' => $order_id))->select();
			foreach ($goods as $good) {
				$sell_day = date('Ymd', $good['create_time']);
				if ($sell_day == $today) {
					D('Waimai_goods')->field(true)->where(array('goods_id' => $good['goods_id'], 'sell_day' => $today, 'today_sell_count' => array('egt', $good['num'])))->setDec('today_sell_count', $good['num']);
				}
			}
			$this->field(true)->where(array('order_id' => $order_id))->save(array('is_sell' => 0));
		}
	}
}
