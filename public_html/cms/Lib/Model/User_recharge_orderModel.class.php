<?php
class User_recharge_orderModel extends Model{
	public function get_pay_order($uid,$order_id,$is_web=false){
		$now_order = $this->get_order_by_id($uid,$order_id);
		//dump($now_order);exit;
		if($now_order['money']<=0){
			return array('error'=>1,'msg'=>'充值订单金额异常！');
		}
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}
		if(!empty($now_order['paid'])){
			return array('error'=>1,'msg'=>'您已经充值！','url'=>'/wap.php?c=My&a=transaction');
		}

		if($is_web){
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'order_type'		=>	($_GET['type'] == 'waimai-recharge' || $_POST['order_type'] == 'waimai-recharge') ? 'waimai-recharge' : 'recharge',
					'order_total_money'	=>	floatval($now_order['money']),
					'order_name'		=>	'在线充值',
					'order_num'			=>	1,
					'order_content'    =>  array(
							0=>array(
									'name'  		=> '在线充值',
									'num'   		=> 1,
									'price' 		=> floatval($now_order['money']),
									'money' 	=> floatval($now_order['money']),
							)
					)
			);
		}else{
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'order_type'		=>	($_GET['type'] == 'waimai-recharge' || $_POST['order_type'] == 'waimai-recharge') ? 'waimai-recharge' : 'recharge',
					'order_name'		=>	'在线充值',
					'order_num'			=>	1,
					'order_price'		=>	floatval($now_order['money']),
					'order_total_money'	=>	floatval($now_order['money']),
			);
		}
		return array('error'=>0,'order_info'=>$order_info);
	}
	public function get_order_by_id($uid,$order_id){
		$condition_user_recharge_order['uid'] = $uid;
		$condition_user_recharge_order['order_id'] = $order_id;
		return $this->field(true)->where($condition_user_recharge_order)->find();
	}
	//电脑站支付前订单处理
	public function web_befor_pay($order_info,$now_user){

		$data_user_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user_recharge_order['submit_order_time'] = $_SERVER['REQUEST_TIME'];
		$condition_user_recharge_order['order_id'] = $order_info['order_id'];
		if(!$this->where($condition_user_recharge_order)->data($data_user_recharge_order)->save()){
			return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
		}
		return array('error_code'=>false,'pay_money'=>$order_info['order_total_money']);

	}
	//手机端支付前订单处理
	public function wap_befor_pay($order_info,$now_user){

		//去除微信优惠的金额
		$pay_money = $order_info['order_total_money'];

		//判断优惠券
//		if(!empty($now_coupon['price'])){
//			$data_weidian_order['card_id'] = $now_coupon['record_id'];
//			if($now_coupon['price'] >= $pay_money){
//				$order_result = $this->wap_pay_save_order($order_info,$data_weidian_order);
//				if($order_result['error_code']){
//					return $order_result;
//				}
//				return $this->wap_after_pay_before($order_info);
//			}
//			$pay_money -= $now_coupon['price'];
//		}

		//判断商家余额
//		if(!empty($merchant_balance)){
//			if($merchant_balance >= $pay_money){
//				$data_weidian_order['merchant_balance'] = $pay_money;
//				$order_result = $this->wap_pay_save_order($order_info,$data_weidian_order);
//				if($order_result['error_code']){
//					return $order_result;
//				}
//				return $this->wap_after_pay_before($order_info);
//			}else{
//				$data_weidian_order['merchant_balance'] = $merchant_balance;
//			}
//			$pay_money -= $merchant_balance;
//		}

		//判断帐户余额
//		if(!empty($now_user['now_money'])){
//			if($now_user['now_money'] >= $pay_money){
//				$data_weidian_order['balance_pay'] = $pay_money;
//				$order_result = $this->wap_pay_save_order($order_info,$data_weidian_order);
//				if($order_result['error_code']){
//					return $order_result;
//				}
//				return $this->wap_after_pay_before($order_info);
//			}else{
//				$data_weidian_order['balance_pay'] = $now_user['now_money'];
//			}
//			$pay_money -= $now_user['now_money'];
//		}
		//在线支付
		$order_result = $this->wap_pay_save_order($order_info);
		if($order_result['error_code']){
			return $order_result;
		}
		return array('error_code'=>false,'pay_money'=>$pay_money);
	}
	//手机端支付前保存各种支付信息
	public function wap_pay_save_order($order_info){
		$data_weidian_order['last_time'] = $_SERVER['REQUEST_TIME'];
		$condition_weidian_order['order_id'] = $order_info['order_id'];
		if($this->where($condition_weidian_order)->data($data_weidian_order)->save()){
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
		if(floatval($order_param['pay_money'])!=floatval($now_order['money'])){
			return array('error'=>1,'msg'=>'充值订单有误');
		}
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在');
		}else if($now_order['paid'] == 1){
			if($order_param['order_type'] == 'waimai-recharge'){
				if($order_param['is_mobile_pay']){
					return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('Waimai/User/index'));
				}else{
					return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('Waimai/Asset/balance'));
				}
			}else{
				return array('error'=>1,'msg'=>'该订单已付款！','url'=>$this->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay'],$now_order));
			}
		}else{
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}

			$data_user_recharge_order = array();
			if(isset($order_param['is_mobile'])){
				$data_user_recharge_order['is_mobile_pay'] = $order_param['is_mobile'];
			}
			$data_user_recharge_order['pay_time'] = $_SERVER['REQUEST_TIME'];
			$data_user_recharge_order['payment_money'] = floatval($order_param['pay_money']);
			$data_user_recharge_order['pay_type'] = $order_param['pay_type'];
			$data_user_recharge_order['third_id'] = $order_param['third_id'];
			$data_user_recharge_order['paid'] = 1;

			if($this->where($where)->save($data_user_recharge_order)){
				$now_order = $this->field(true)->where($where)->find();

				D('User')->add_money($now_order['uid'],$order_param['pay_money'],'在线充值');
				D('Scroll_msg')->add_msg('user_recharge',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'充值成功！');
				if(empty($now_order['label'])){

					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('TM00356',
							array('href' => C('config.site_url').'/wap.php?c=My&a=transaction',
									'wecha_id' => $now_user['openid'],
									'first' => $now_user['nickname'].'您好！',
									'work' => '您已成功在线充值'.$order_param['pay_money'].'元，请及时查看',
									'remark' => date('Y年m月d日 H:i:s')
							)
					);

					if (C('config.sms_place_order') == 1 || C('config.sms_place_order') == 3) {
						$sms_data['uid'] = $now_user['uid'];
						$sms_data['mobile'] = $now_user['phone'];
						$sms_data['sendto'] = 'user';
						$sms_data['content'] = "您于".date('Y-m-d H:i:s')."在线充值余额{$order_param['pay_money']}元成功！请及时查看";
						Sms::sendSms($sms_data);
					}
				}
				if($order_param['order_type'] == 'waimai-recharge'){
					return array('error'=>0,'msg'=>'充值成功！','url'=>U('Waimai/User/index'));
				} else {
					return array('error'=>0,'msg'=>'充值成功！','url'=>$this->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay'],$now_order));
				}


			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}

	public function get_pay_after_url($label,$is_mobile = false,$now_order=array()){
		if($label){
			$labelArr = explode('_',$label);
			if($labelArr[0] == 'wap'){
				switch($labelArr[1]){
					case 'village':
						//直接验证订单
						$order_id = $labelArr[2];
						$now_order = D('House_village_pay_order')->field(true)->where(array('order_id'=>$order_id))->find();
						if($now_order['paid']){
							return U('House/pay_order',array('order_id'=>$order_id));
						}
						$use_result = D('User')->user_money($now_order['uid'],$now_order['money'],$now_order['order_name'].' 扣除余额');
						if(empty($use_result['error_code'])){
							$data_order['order_id'] = $order_id;
							$data_order['pay_time'] = $_SERVER['REQUEST_TIME'];
							$data_order['paid'] = 1;
							// $data_order['pay_type'] = $now_order['pay_type'];
							// $data_order['payment_money'] = $now_order['payment_money'];
							D('House_village_pay_order')->data($data_order)->save();
							$now_user_info = D('House_village_user_bind')->get_one($now_order['village_id'],$now_order['bind_id'],'pigcms_id');
							if($now_order['order_type'] != 'custom'){
								switch($now_order['order_type']){
									case 'property':
										$database_house_village_property_paylist = D('House_village_property_paylist');
										$paylist_data['bind_id'] = $now_user_info['pigcms_id'];
										$paylist_data['uid'] = $now_user_info['uid'];
										$paylist_data['village_id'] = $now_user_info['village_id'];
										$paylist_data['property_month_num'] = $now_order['property_month_num'];
										$paylist_data['presented_property_month_num'] = $now_order['presented_property_month_num'];
										$paylist_data['house_size'] = $now_order['house_size'];
										$paylist_data['property_fee'] = $now_order['property_fee'];
										$paylist_data['floor_type_name'] = $now_order['floor_type_name'];

										$now_pay_info = $database_house_village_property_paylist->where(array('bind_id'=>$now_user_info['pigcms_id']))->order('add_time desc')->find();
										if(!empty($now_pay_info)){
											$paylist_data['start_time'] = $now_pay_info['end_time'] ;
											$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_pay_info['end_time']);
										}else{
											if($now_user_info['add_time'] > 0){
												$paylist_data['start_time'] = $now_user_info['add_time'];
												$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['add_time']);
											}else{
												$paylist_data['start_time'] = time();
												$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", time());
											}

										}
										$paylist_data['add_time'] = time();
										$paylist_data['order_id'] = $order_id;
										$database_house_village_property_paylist->data($paylist_data)->add();


										$bind_field = 'property_price';
										$data_bind['property_month_num'] =  $now_user_info['property_month_num'] + $now_order['property_month_num'];
										$data_bind['presented_property_month_num'] = $now_user_info['presented_property_month_num'] + $now_order['presented_property_month_num'];

										break;
									case 'water':
										$bind_field = 'water_price';
										break;
									case 'electric':
										$bind_field = 'electric_price';
										break;
									case 'gas':
										$bind_field = 'gas_price';
										break;
									case 'park':
										$bind_field = 'park_price';
										break;
									default:
										$bind_field = '';
								}

								if(!empty($bind_field)){
									$data_bind['pigcms_id'] = $now_user_info['pigcms_id'];
									if($now_user_info[$bind_field] - $now_order['money'] >= 0){
										$data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'];
									}else{
										$data_bind[$bind_field] = 0;
									}
									$data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'] >= 0 ? $now_user_info[$bind_field] - $now_order['money'] : 0;
									D('House_village_user_bind')->data($data_bind)->save();
								}
							}else{
								$data_order['pay_money']  = $now_order['payment_money'];
								$data_order['system_score']  =0;
								$data_order['system_score_money']  =0;
								$data_order['system_balance']  = 0;
								$data_order['is_own']  = 0;
								D('House_village_pay_order')->after_pay($order_id,$data_order);
							}
							$database_user = D('User');
							$now_user = $database_user->get_user($now_order['uid']);
							if(!empty($now_user['openid'])){
								$href = C('config.site_url').'/wap.php?g=Wap&c=House&a=village_my_paylists&village_id='.$now_order['village_id'];
								$model = new templateNews($this->config['wechat_appid'],$this->config['wechat_appsecret']);
								$model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' =>  ' 缴费成功提醒', 'keynote1' =>$now_order['order_name'], 'keynote2' =>'物业号 '. $now_user_info['usernum'], 'remark' => '缴费时间：'.date('Y年n月j日 H:i',$now_order['time']).'\n'.'缴费金额：￥'.$now_order['money']));
							}
						}


						return U('House/pay_order',array('order_id'=>$order_id));
					case 'activity':
						$activity_id = $labelArr[2];
						$quantity = $labelArr[3];
						return "/index.php?c=Activity&a=submit&r=1&id=".$activity_id."&q=".$quantity;
					case 'gift':
						$gift_id = $labelArr[2];
						$quantity = $labelArr[3];
						return "/wap.php?c=Gift&a=submit&r=1&order_id=".$gift_id."&q=".$quantity;
					case 'classify':
						$order_id = $labelArr[2];
						$quantity = $labelArr[3];
						return "/wap.php?c=Classify&a=submit&r=1&order_id=".$order_id."&q=".$quantity;
					case 'appoint':
						$order_id = $labelArr[2];
						return "/wap.php?c=My&a=submit&r=1&order_id=" . $order_id . '&is_initiative=1&user_recharge_order_id='.$now_order['order_id'];
					case 'crowdsourcing':
						$wap = $labelArr[2];
						$order_id = $labelArr[3];
						$status = $labelArr[4];
						if($wap == 1){
							return "/wap_house.php?c=Crowdsourcing&a=grab_single&package_id=".$order_id."&status=" . $status;
						}else{
							return "/wap.php?c=Crowdsourcing&a=grab_single&package_id=".$order_id."&status=" . $status;
						}
					case 'ride':
						$wap = $labelArr[2];
						$order_id = $labelArr[3];
						$status = $labelArr[4];
						if($wap == 1){
							return "/wap_house.php?c=Ride&a=ride_place_order&ride_id=".$order_id."&status=" . $status;
						}else{
							return "/wap.php?c=Ride&a=ride_place_order&ride_id=".$order_id."&status=" . $status;
						}
					case 'servicebuy':
						$cid = $labelArr[2];
						return "/wap.php?c=Service&a=publish_detail&cid=".$cid;
					case 'servicegive':
						$cid = $labelArr[2];
						return "/wap.php?c=Service&a=publish_detail&cid=".$cid;
					case 'service':
						$publish_id = $labelArr[2];
						return "/wap.php?c=Service&a=price_list&publish_id=".$publish_id;
					case 'nextorder':
						$rid = $labelArr[2];
						return "/wap.php?c=Yuedan&a=next_order&rid=".$rid;
					case 'level':
						return "/wap.php?c=My&a=levelUpdate";
					case 'express':
						$village_express_order_id = $labelArr[2];
						$data_village_express_order['paid']=1;
						$data_village_express_order['pay_time']=$_SERVER['REQUEST_TIME'];
						return "/wap.php?g=Wap&c=Library&a=express_submit&order_id=".$village_express_order_id;

				}
			}
			if($labelArr[0] == 'weixin'){
				switch($labelArr[1]){
					case 'activity': // 群活动
						//报名id
						$join_id = $labelArr[2];

						// 查询报名信息
						$join_info = D('Community_activity_join')->field(true)->where(array('join_id'=>$join_id))->find();
						if (!$join_info) {
							return '/Appapi.php?g=Appapi&c=Comm_Apply&a=join_order_detail&order_id='.$order_id;
						}
						if($join_info['status'] == 1){
							// 已付款
							return '/Appapi.php?g=Appapi&c=Comm_Apply&a=join_order_detail&order_id='.$order_id;
						}
                        //获取用户信息
                        $user_info = D('User')->get_user($join_info['uid']);
                        if ($user_info['now_money']  < $join_info['money']) {
                        	// 余额不足
							return '/Appapi.php?g=Appapi&c=Comm_Apply&a=join_order_detail&order_id='.$order_id;
                        }
						 // 查询群活动
		                $activity_info = D('Community_activity')->field(true)->where(array('activity_id'=>$join_info['activity_id']))->find();

						$use_result = D('User')->user_money($join_info['uid'],$join_info['money'],'参加群活动【'.$activity_info['title'].'】报名费用，减少余额');
						if(empty($use_result['error_code'])){
							//生成订单
							$order_data = array(
								'community_id' => $join_info['community_id'],
								'activity_id' => $join_info['activity_id'],
								'uid' => $join_info['uid'],
								'money' => $join_info['money'],
								'orderid' => $now_order['orderid'],
								'status' => 1, // 已付款
								'pay_type' => 1,//付款方式1-微信，2-支付宝，3-余额
								'type' => 1, // 订单类型:1-活动，2-加群
								'add_time' => time(),
							);
							$order_id = D('Community_order')->data($order_data)->add();
							if ($order_id) {
								$res = D('Community_order')->after_pay($order_id,$labelArr[1],$labelArr[2]);
							}
						}
						return '/Appapi.php?g=Appapi&c=Comm_Apply&a=join_order_detail&order_id='.$order_id;
					case 'joingroup': // 加群
						// 加群id
						$add_id = $labelArr[2];
						// 查询报名信息
						$join_info = D('Community_join')->field(true)->where(array('add_id'=>$add_id))->find();
						if (!$join_info) {
							return '/Appapi.php?g=Appapi&c=Comm_Group&a=group_info&community_id='.$join_info['community_id'];
						}
						if($join_info['add_status'] == 3){
							// 已付款
							return '/Appapi.php?g=Appapi&c=Comm_Group&a=group_info&community_id='.$join_info['community_id'];
						}
                        //获取用户信息
                        $user_info = D('User')->get_user($join_info['add_uid']);
                        if ($user_info['now_money']  < $join_info['charge_money']) {
                        	// 余额不足
							return '/Appapi.php?g=Appapi&c=Comm_Group&a=group_info&community_id='.$join_info['community_id'];
                        }
						
		                // 查询群信息
		                $community_info = D('Community_info')->field(true)->where(array('community_id'=>$join_info['community_id']))->find();

						$use_result = D('User')->user_money($join_info['add_uid'],$join_info['charge_money'],'加入群【'.$community_info['community_name'].'】费用，减少余额');
						if(empty($use_result['error_code'])){
							//生成订单
							$order_data = array(
								'community_id' => $join_info['community_id'],
								'uid' => $join_info['add_uid'],
								'money' => $join_info['charge_money'],
								'orderid' => $now_order['orderid'],
								'status' => 1, // 已付款
								'pay_type' => 1,//付款方式1-微信，2-支付宝，3-余额
								'type' => 2, // 订单类型:1-活动，2-加群
								'add_time' => time(),
							);
							if ($order_id = D('Community_order')->data($order_data)->add()) {				
								$res = D('Community_order')->after_pay($order_id,$labelArr[1],$labelArr[2]);
							}
						}
						return '/Appapi.php?g=Appapi&c=Comm_Group&a=group_info&community_id='.$join_info['community_id'];
				}
			}
		}else{
			if($is_mobile){

				return '/wap.php?c=My&a=transaction';
			}else{

				return '/index.php?g=User&c=Credit&a=index';
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
			return array('error'=>1,'msg'=>$error_tips.'已将您充值的金额添加到您的余额内。');
		}
	}
}
?>