<?php
class Store_orderModel extends Model
{
	
	public function get_order_by_id($uid, $order_id)
	{
		$condition = array();
		$condition['order_id'] = $order_id;
//		$uid && $condition['uid'] = $uid;
		return $this->field(true)->where($condition)->find();
	}

	public function get_pay_order($uid, $order_id, $is_web=false)
	{
		$now_order = $this->get_order_by_id($uid, $order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}
		if(!empty($now_order['paid'])){
			return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>str_replace('/source/','/',U('My/store_order_list',array('order_id'=>$now_order['order_id']))));
		}

		$merchant_store = M("Merchant_store")->where(array('store_id' => $now_order['store_id'], 'mer_id' => $now_order['mer_id']))->find();
		//$imgs = M('Merchant_store')->field('pic_info')->where(array('store_id'=>$now_order['store_id']))->find();
		$imgs =explode(';', $merchant_store['pic_info']);
		foreach($imgs as &$v){
			$v = preg_replace('/,/','/',$v);
		}

		$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'mer_id'			=>	$now_order['mer_id'],
					'order_type'		=>	'store',
					'order_name'		=>	$now_order['name'],
					'store_id'		=>	$now_order['store_id'],
					'no_discount_money'		=>	$now_order['no_discount_money'],
					'order_num'			=>	0,
					'order_price'		=>	floatval($now_order['price']),
					'order_total_money'	=>	floatval($now_order['price']),
					'extra_price'	=>	floatval($now_order['extra_price']),
					'img'				=> C('config.site_url').'/upload/store/'.$imgs[0],
			);
		return array('error' => 0,'order_info' => $order_info);
	}
	
	//手机端支付前订单处理
	public function wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user)
	{
		//去除微信优惠的金额
		$pay_money = $order_info['order_total_money'];

		if($merchant_balance['card_discount']){
			$pay_money = sprintf("%.2f",($pay_money-$order_info['no_discount_money'])*$merchant_balance['card_discount']/10)+$order_info['no_discount_money'];
			//$pay_money = floor($pay_money*$merchant_balance['card_discount']*10)/100;
		}
		if(C('config.open_extra_price')==1){
			$user_score_use_percent=(float)C('config.user_score_use_percent');
			$order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
			$pay_money +=$order_info['order_extra_price'];
		}
		$data_store_order['card_discount'] = $merchant_balance['card_discount'];


		//判断优惠券
		if ($now_coupon['card_price'] >0) {
			$data_store_order['card_id'] = $now_coupon['merc_id'];
			$data_store_order['card_price'] = round($now_coupon['card_price'] * 100)/100;
			if ($now_coupon['card_price'] >= $pay_money) {
				$order_result = $this->wap_pay_save_order($order_info, $data_store_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['card_price'];
		}

		if($now_coupon['coupon_price']>0){
			$data_store_order['coupon_id'] = $now_coupon['sysc_id'];
			$data_store_order['coupon_price'] = $now_coupon['coupon_price'];
			if ($now_coupon['coupon_price'] >= $pay_money) {
				$order_result = $this->wap_pay_save_order($order_info, $data_store_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['coupon_price'];
			$data_store_order['pay_money'] = $pay_money;
		}

		if(!empty($order_info['score_deducte'])&&$order_info['use_score']){
			$data_store_order['score_used_count']  = $order_info['score_used_count'];
			$data_store_order['score_deducte']     = (float)$order_info['score_deducte'];
			if($order_info['score_deducte'] >= $pay_money){
				//扣除积分
				$order_result = $this->wap_pay_save_order($order_info,$data_store_order);
				if($order_result['error_code']){
					return $order_result;
				}
				$order_info['pay_money'] = $pay_money;
				return $this->wap_after_pay_before($order_info);
			}


			$pay_money -= $order_info['score_deducte'];
			//$data_group_order['system_pay'] += $data_group_order['score_deducte'];
		}


		if(!empty($merchant_balance['card_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_money'] >= $pay_money){
				$data_store_order['merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_store_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_store_order['merchant_balance'] = $merchant_balance['card_money'];
			}
			$pay_money -= $merchant_balance['card_money'];
		}

		if(!empty($merchant_balance['card_give_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_give_money'] >= $pay_money){
				$data_store_order['card_give_money'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_store_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_store_order['card_give_money'] = $merchant_balance['card_give_money'];
			}
			$pay_money -= $merchant_balance['card_give_money'];
		}

		//判断帐户余额
		if (!empty($now_user['now_money'])&&$order_info['use_balance']) {
			if ($now_user['now_money'] >= $pay_money) {
				$data_store_order['balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info, $data_store_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			} else {
				$data_store_order['balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
		}
		//在线支付
		$order_result = $this->wap_pay_save_order($order_info, $data_store_order);
		if ($order_result['error_code']) {
			return $order_result;
		}
		return array('error_code' => false, 'pay_money' => $pay_money);
	}
	
	//手机端支付前保存各种支付信息
	public function wap_pay_save_order($order_info, $data_store_order)
	{
		$data_store_order['card_id'] 			= !empty($data_store_order['card_id']) ? $data_store_order['card_id'] : 0;
		$data_store_order['merchant_balance'] 	= !empty($data_store_order['merchant_balance']) ? $data_store_order['merchant_balance'] : 0;
		$data_store_order['card_give_money'] 	= !empty($data_store_order['card_give_money']) ? $data_store_order['card_give_money'] : 0;
		$data_store_order['card_discount'] 	= !empty($data_store_order['card_discount']) ? $data_store_order['card_discount'] : 0;
		$data_store_order['balance_pay']	 	= !empty($data_store_order['balance_pay']) ? $data_store_order['balance_pay'] : 0;
		$data_store_order['score_used_count']  	= !empty($data_store_order['score_used_count'])?$data_store_order['score_used_count']:0;
		$data_store_order['score_deducte']     	= !empty($data_store_order['score_deducte'])?(float)$data_store_order['score_deducte']:0;
		$data_store_order['score_discount_type']      = !empty($order_info['score_discount_type']) ? $order_info['score_discount_type'] : 0;
		$data_store_order['dateline'] = $_SERVER['REQUEST_TIME'];
		$data_store_order['card_price'] =  !empty($data_store_order['card_price']) ? $data_store_order['card_price'] : 0;
		$condition_store_order['order_id'] = $order_info['order_id'];
		$result = $this->where($condition_store_order)->data($data_store_order)->save();
		if ($result) {
			return array('error_code' => false, 'msg' => '保存订单成功！');
		} else {
			return array('error_code' => true, 'msg' => '保存订单失败！请重试或联系管理员。1');
		}
	}
	//如果无需调用在线支付，使用此方法即可。
	public function wap_after_pay_before($order_info)
	{
		$order_param = array(
				'order_id' => $order_info['order_id'],
				'pay_type' => '',
				'third_id' => '',
				'is_mobile' => $order_info['is_mobile'],
				'order_type' => 'store',
			);
			$result_after_pay = $this->after_pay($order_param);
			if ($result_after_pay['error']) {
				return array('error_code' => true,'msg'=>$result_after_pay['msg']);
			}
			return array('error_code'=>false,'msg'=>'支付成功！','url'=>str_replace('/source/','/',U('My/store_order_detail',array('order_id'=>$order_info['order_id']))));
	}
	
	//支付之后
	public function after_pay($order_param)
	{
		if($order_param['pay_type']!=''){
			$condition_order['orderid'] = $order_param['order_id'];
		}else{
			$condition_order['order_id'] = $order_param['order_id'];
		}
		$now_order = $this->field(true)->where($condition_order)->find();
		if (empty($now_order)) {
			return array('error' => 1, 'msg' => '当前订单不存在！');
		} elseif($now_order['paid'] == 1) {
			return array('error' => 1, 'msg' => '该订单已付款！', 'url' => U('My/store_order_list',array('order_id'=>$now_order['order_id'])));
		} else {
            if ($now_order['business_type'] == 'foodshop') {
                $now_food_order = D('Foodshop_order')->field('order_id, status, book_price')->where(array('order_id' => $now_order['business_id']))->find();
                if ($now_food_order && $now_food_order['status'] > 2) {
                    return array(
                        'error' => 1,
                        'msg' => '该订单已付款！',
                        'url' => U('My/store_order_list', array('order_id' => $now_order['order_id']))
                    );
                }
            }
		    
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if (empty($now_user)) {
				return array('error' => 1, 'msg' => '没有查找到此订单归属的用户，请联系管理员！');
			}
			
			//判断优惠券是否正确
//			if($now_order['card_id']){
//				$now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($now_order['card_id'],$now_order['uid']);
//				if(empty($now_coupon)){
//					return $this->wap_after_pay_error($now_order, $order_param, '您选择的优惠券不存在！');
//				}
//			}
			if($now_order['card_id']){
				$now_coupon = D('Card_new_coupon')->get_coupon_by_id($now_order['card_id']);
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}

			//判断平台优惠券
			if($now_order['coupon_id']){
				$now_coupon = D('System_coupon')->get_coupon_by_id($now_order['coupon_id']);
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}

			$score_used_count=$now_order['score_used_count'];
			if($now_user['score_count']<$score_used_count){
				return array('error'=>1,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
			if($score_used_count>0){
				$use_result = D('User')->user_score($now_order['uid'],$score_used_count,'购买 '.$now_order['order_name'].' 扣除'.C('config.score_name'));
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
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
					return $this->wap_after_pay_error($now_order, $order_param, '您的帐户余额不够此次支付！');
				}
			}
			
			//如果使用了商家优惠券
//			if($now_order['card_id']){
//				$use_result = D('Member_card_coupon')->user_card($now_order['card_id'], $now_order['mer_id'], $now_order['uid']);
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

			//如果使用了平台优惠券
			if($now_order['coupon_id']){

				$use_result = D('System_coupon')->user_coupon($now_order['coupon_id'],$now_order['order_id'],$order_param['order_type'],$now_order['mer_id'],$now_order['uid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}


			//如果使用会员卡余额
//			if ($merchant_balance) {
//				$use_result = D('Member_card')->use_card($now_order['uid'],$now_order['mer_id'],$merchant_balance,'购买 '.$now_order['order_name'].' 扣除会员卡余额');
//				if($use_result['error_code']){
//					return array('error'=>1,'msg'=>$use_result['msg']);
//				}
//			}

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
				$use_result = D('User')->user_money($now_order['uid'],$balance_pay,'购买 '.$now_order['order_name'].' 扣除余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//$condition_store_order['order_id'] = $order_param['order_id'];
			
			$data_store_order['pay_time'] = isset($order_param['pay_time']) && $order_param['pay_time'] ? strtotime($order_param['pay_time']) : $_SERVER['REQUEST_TIME'];
			$data_store_order['payment_money'] = floatval($order_param['pay_money']);
			$data_store_order['pay_type'] = $order_param['pay_type'];
			$data_store_order['third_id'] = $order_param['third_id'];
			//$data_group_order['is_mobile_pay'] = $order_param['is_mobile'];
			$data_store_order['is_own'] = isset($order_param['sub_mch_id'])?2:$order_param['is_own'];
			$data_store_order['paid'] = 1;
			if($this->where($condition_order)->data($data_store_order)->save()){
				//积分
				$update_order = $this->where($condition_order)->find();
				$store = D('Merchant_store')->field(true)->where(array('store_id' => $now_order['store_id']))->find();

				D('Scroll_msg')->add_msg('store',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.C('config.cash_alias_name').'成功');

				if(C('config.open_extra_price')==1){
					$order = $now_order;
					$order['order_type'] ='store';
					$score = D('Percent_rate')->get_extra_money($order);
					if($score>0){
						D('User')->add_score($order['uid'], floor($score),'在 ' . $store['name'] . ' 中使用'.C('config.cash_alias_name').'支付了' . floatval($now_order['balance_pay']+$now_order['merchant_balance']+$order_param['pay_money']) . '元 +'.$now_order['score_used_count'].C('config.extra_price_alias_name').' 获得'.C('config.extra_price_alias_name'));
						D('Scroll_msg')->add_msg('store',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.C('config.cash_alias_name').'成功获得'.C('config.score_name'));
						
					}
				}else{
					$score_get_times = C('config.score_get_times');
					//获得积分按抽成算
					if(C('config.add_score_by_percent')==0 && (C('config.open_score_discount')==0 || $update_order['score_discount_type']!=2) && ( $score_get_times==0 || empty($score_get_times))){
//						if(C('config.open_score_get_percent')==1){
//							$score_get = C('config.score_get_percent')/100;
//						}else{
//							$score_get = C('config.user_score_get');
//						}
						if(C('config.store_score_get_percent')==1){
							$score_get = C('config.user_store_score_get')/100;
						}else{
							$score_get = C('config.user_store_score_get');
						}
						if($data_store_order['is_own']&& C('config.user_own_pay_get_score')!=1){
							$data_store_order['payment_money']= 0;
						}
						// if(C('config.open_score_fenrun')){

							$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
							if($now_merchant['score_get_percent']>=0){
								$score_get = $now_merchant['score_get_percent']/100;
							}
						// }

						D('User')->add_score($now_order['uid'], round(($data_store_order['payment_money']+$update_order['balance_pay'] )* $score_get), '在' . $store['name'] . ' 中使用'.C('config.cash_alias_name').'支付了' . floatval($now_order['price']) . '元 获得'.C('config.score_name'));
						$this->where(array('order_id'=>$now_order['order_id']))->setField('score_give',round(($data_store_order['payment_money']+$update_order['balance_pay'] )* $score_get));
					}

					D('Userinfo')->add_score($now_order['uid'], $now_order['mer_id'], $now_order['price'], '在 ' . $store['name'] . ' 中使用'.C('config.cash_alias_name').'支付了' . floatval($now_order['price']) . '元 获得积分');
				}
				$spread_total_money = $balance_pay + $data_store_order['payment_money'];
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				//分佣
				if(!empty($now_user['openid'])&&C('config.open_user_spread') && (C('config.spread_money_limit')==0 || C('config.spread_money_limit')<=$spread_total_money)){
					//上级分享佣金
					if($now_order['from_plat']==1){
						$spread_rate = D('Percent_rate')->get_user_spread_rate($now_order['mer_id'],'store');
						$spread_type = 'store';
					}else{
						$spread_rate = D('Percent_rate')->get_user_spread_rate($now_order['mer_id'],'cash');
						$spread_type = 'cash';
					}

					$open_extra_price = C('config.open_extra_price');
					$spread_users[]=$now_user['uid'];
					if($now_user['wxapp_openid']!=''){
						$spread_where['_string'] = "openid = '{$now_user['openid']}' OR openid = '{$now_user['wxapp_openid']}' ";
					}else{
						$spread_where['_string'] = "openid = '{$now_user['openid']}'";
					}
					$now_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where)->find();
					if($data_store_order['is_own']  && C('config.own_pay_spread')==0){
						$data_store_order['payment_money']=0;
					}
					$href = C('config.site_url') . '/wap.php?g=Wap&c=My&a=spread_list&status=-1';
					if(!empty($now_user_spread)){
						if($now_user_spread['is_wxapp']){
							$spread_user = D('User')->get_user($now_user_spread['spread_openid'],'wxapp_openid');
						}else{
							$spread_user = D('User')->get_user($now_user_spread['spread_openid'],'openid');
						}
						//$user_spread_rate = $update_group['spread_rate'] > 0 ? $update_group['spread_rate'] : C('config.user_spread_rate');
						$user_spread_rate = $spread_rate['first_rate'];
						if($spread_user && $user_spread_rate&&!in_array($spread_user['uid'],$spread_users)){
							$spread_money = round(($balance_pay + $data_store_order['payment_money']) * $user_spread_rate / 100, 2);
							$spread_data = array(
									'uid'=>$spread_user['uid'],
									'spread_uid'=>0,
									'get_uid'=>$now_user['uid'],
									'money'=>$spread_money,
									'order_type'=>$spread_type,
									'order_id'=>$now_order['order_id'],
									'third_id'=>$now_order['store_id'],
									'add_time'=>$_SERVER['REQUEST_TIME']
							);
							if($spread_user['spread_change_uid']!=0){
								$spread_data['change_uid'] = 	$spread_user['spread_change_uid'];
							}
							D('User_spread_list')->data($spread_data)->add();
							$buy_user = D('User')->get_user($now_user_spread['openid'],'openid');
							if($spread_money>0){
								if($open_extra_price){
									$money_name = C('config.extra_price_alias_name');
								}else{
									$money_name = '佣金';
								}
								$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $spread_user['openid'], 'first' => $buy_user['nickname'] . '通过您的分享购买了'.C('config.cash_alias_name').'，验证消费后您将获得'.$money_name.'。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
							}
							$spread_users[]=$spread_user['uid'];
							// D('User')->add_money($spread_user['uid'],$spread_money,'推广用户 '.$now_user['nickname'].' 购买 ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
						}

						//第二级分享佣金
						$spread_where['_string'] = "openid = '{$spread_user['openid']}' OR openid = '{$spread_user['wxapp_openid']}' ";
						$second_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where )->find();

						if(!empty($second_user_spread)&&!$open_extra_price) {
							if($second_user_spread['is_wxapp']){
								$second_user = D('User')->get_user($second_user_spread['spread_openid'], 'wxapp_openid');
							}else{
								$second_user = D('User')->get_user($second_user_spread['spread_openid'], 'openid');
							}
//							//$sub_user_spread_rate = $update_group['sub_spread_rate'] > 0 ? $update_group['sub_spread_rate'] : C('config.user_first_spread_rate');
							$sub_user_spread_rate = $spread_rate['second_rate'];
							if ($second_user && $sub_user_spread_rate&&!in_array($second_user['uid'],$spread_users)) {
								$spread_money = round(($balance_pay + $data_store_order['payment_money']) * $sub_user_spread_rate / 100, 2);
								$spread_sec_data =array('uid' => $second_user['uid'], 'spread_uid' => $spread_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => $spread_type, 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
								if($second_user['spread_change_uid']!=0){
									$spread_sec_data['change_uid'] = 	$second_user['spread_change_uid'];
								}
								D('User_spread_list')->data($spread_sec_data)->add();
								$sec_user = D('User')->get_user($second_user_spread['openid'], 'openid');
								if($spread_money>0) {
									$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $second_user['openid'], 'first' => $sec_user['nickname'] .'的子用户'.$buy_user['nickname'] . '通过您的分享购买了'.C('config.cash_alias_name').'，验证消费后您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'),  $now_order['mer_id']);
								}
								$spread_users[]=$second_user['uid'];
								// D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
							}

							//顶级分享佣金
							$spread_where['_string'] = "openid = '{$second_user['openid']}' OR openid = '{$second_user['wxapp_openid']}' ";
							$first_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where(	$spread_where)->find();

							if (!empty($first_user_spread) && C('config.user_third_level_spread')&&!$open_extra_price) {
								if($first_user_spread['is_wxapp']){
									$first_spread_user = D('User')->get_user($first_user_spread['spread_openid'], 'wxapp_openid');
								}else{
									$first_spread_user = D('User')->get_user($first_user_spread['spread_openid'], 'openid');
								}
//								//$sub_user_spread_rate = $update_group['third_spread_rate'] > 0 ? $update_group['third_spread_rate'] : C('config.user_second_spread_rate');
								$sub_user_spread_rate = $spread_rate['third_rate'];
								if ($first_spread_user && $sub_user_spread_rate&&!in_array($first_spread_user['uid'],$spread_users)) {
									$spread_money = round(($balance_pay + $data_store_order['payment_money']) * $sub_user_spread_rate / 100, 2);
									$spread_thd_data=array('uid' => $first_spread_user['uid'], 'spread_uid' => $second_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => $spread_type, 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
									if($first_spread_user['spread_change_uid']!=0){
										$spread_thd_data['change_uid'] = 	$first_spread_user['spread_change_uid'];
									}
									D('User_spread_list')->data($spread_thd_data)->add();

									$fir_user = D('User')->get_user($first_user_spread['openid'], 'openid');
									if($spread_money>0) {
										$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $first_spread_user['openid'], 'first' =>$fir_user['nickname'].'的子用户的子用户'.$buy_user['nickname'] . '通过您的分享购买了'.C('config.cash_alias_name').'，验证消费后您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
									}
									// D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
								}
							}

						}
					}
				}
				$now_order = $update_order;
				$price_str =  floatval($now_order['balance_pay']+$now_order['payment_money']+$now_order['merchant_balance']+$now_order['card_give_money']) ;

				//增加粉丝 5 group 6 shop 7 meal 8 appoint 9 store
				D('Merchant')->saverelation($now_user['openid'], $now_order['mer_id'], 9);


				if($now_order['from_plat'] && $now_user['openid']){
					if($now_order['from_plat'] == 2){
						$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
						$href = C('config.site_url').'/wap.php?c=My&a=store_order_list';
						$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => '到店买单提醒', 'keyword1' => $now_order['name'], 'keyword2' => $now_order['order_id'], 'keyword3' => $price_str, 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '付款成功，感谢您的使用'), $now_order['mer_id']);
					}else{
						$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
						$href = C('config.site_url').'/wap.php?c=My&a=store_order_list';
						$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => C('config.cash_alias_name').'提醒', 'keyword1' => C('config.cash_alias_name'), 'keyword2' => $now_order['order_id'], 'keyword3' => $price_str, 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '付款成功，感谢您的使用'), $now_order['mer_id']);
					}
				}
				
				
				//支付成功增加商家余额

				$now_user = M('User')->where(array('uid'=>$now_order['uid']))->find();
				if($now_order['from_plat']==1){
					$now_order['order_type']='store';
					$now_order['desc']='用户'.C('config.cash_alias_name').'支付计入收入';
					//D('Merchant_money_list')->add_money($now_order['mer_id'],'用户优惠买单支付计入收入',$now_order);
					D('SystemBill')->bill_method($now_order['is_own'],$now_order);
					//商家推广分佣
					D('Merchant_spread')->add_spread_list($now_order,$now_user,$now_order['order_type'],$now_user['nickname'].'用户'.C('config.cash_alias_name').'获得佣金');
				}else{
					$now_order['order_type']='cash';
					$now_order['desc']='用户到店支付计入收入';
					//D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店支付计入收入',$now_order);
					D('SystemBill')->bill_method($now_order['is_own'],$now_order);
					//商家推广分佣
					D('Merchant_spread')->add_spread_list($now_order,$now_user,$now_order['order_type'],$now_user['nickname'].'用户到店支付获得佣金');
				}

				//微信派发优惠券 支付到平台 微信支付
				if($data_store_order['is_own']==0 && $data_store_order['pay_type']=='weixin' && $data_store_order['payment_money']>=C('config.weixin_send_money')){
					D('System_coupon')->weixin_send($data_store_order['payment_money'],$now_order['uid']);
				}

				//小票打印
// 				$msg = ArrayToStr::array_to_str($now_order['order_id'], 'shop_order');
// 				$op = new orderPrint(C('config.print_server_key'), C('config.print_server_topdomain'));
// 				$op->printit($now_order['mer_id'], $now_order['store_id'], $msg, 1);

// 				$str_format = ArrayToStr::print_format($now_order['order_id'], 'shop_order');
// 				foreach ($str_format as $print_id => $print_msg) {
// 					$print_id && $op->printit($now_order['mer_id'], $now_order['store_id'], $print_msg, 1, $print_id);
// 				}

				//短信提醒
				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['mer_id'], 'type' => 'store');
				$store = D('Merchant_store')->field(true)->where(array('store_id' => $now_order['store_id']))->find();
// 				if (C('config.sms_shop_success_order') == 1 || C('config.sms_shop_success_order') == 3) {

// 				$sms_data['uid'] = $now_order['uid'];
// 				$sms_data['mobile'] = $now_user['phone'];
// 				$sms_data['sendto'] = 'user';
// 				$sms_data['content'] = '您在' . date("Y-m-d H:i:s") . '时，通过优惠买单,给【' . $store['name'] . '】店,支付了' . floatval($now_order['price']) . '元';
// 				Sms::sendSms($sms_data);

// 				}
 				if (C('config.sms_store_success_order') == 2 ) {
					$sms_data['uid'] = 0;
					$store['phone'] = explode(' ',$store['phone']);
					$sms_data['mobile'] = $store['phone'][0];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客' . $now_user['username'] . '在' . date("Y-m-d H:i:s") . '时，通过'.C('config.cash_alias_name').'，支付了：' .$price_str. '元,单号：' . ($now_order['real_orderid']?$now_order['real_orderid']:$now_order['order_id']);
					Sms::sendSms($sms_data);
 				}

				
				if($now_order['business_type']){
					switch($now_order['business_type']){
						case 'foodshop':
// 							$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
							D('Foodshop_order')->after_pay($now_order['business_id']);
							M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['price']+$now_food_order['book_price'],'pay_type'=>'1'))->save();
							break;
					}
				}
				
				D('Merchant_store_staff')->sendMsgStoreOrder($now_order);

				$printHaddle = new PrintHaddle();
				$printHaddle->printit($now_order['order_id'], 'store_order', -1);

				if($order_param['is_mobile']){
					return array('error'=>0,'url'=>str_replace('/source/','/',U('My/store_order_detail',array('order_id'=>$now_order['order_id']))));
				}else{
					return array('error'=>0,'url'=>U('User/Index/store_order_list',array('order_id'=>$now_order['order_id'])));
				}
			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}
	private function voic_baidu(){
        static $return;

        if (empty($return)) {
            $voic_baidu = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=XU7nTWQzS9vn32bckmrhHbTu&client_secret=1375720f23eed9b7b787e728de5dd1c2";
            import('ORG.Net.Http');
            $return = Http::curlGet($voic_baidu);
        }
        return $return;
    }
	//支付时，金额不够，记录到帐号
	public function wap_after_pay_error($now_order,$order_param,$error_tips)
	{
		//记录充值的金额，因为 Pay/return_url 处没有返回order的具体信息，故在此调用。
		$user_result = D('User')->add_money($now_order['uid'],$order_param['pay_money'],'支付订单：'.$now_order['order_id'].'发生错误！原因是：'.$error_tips);
		if($order_param['pay_type']=='weixin'&&ACTION_NAME=='return_url'){
			exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
		}
		if($user_result['error_code']){
			return array('error'=>1,'msg'=>$user_result['msg']);
		}else{
			if($order_param['is_mobile']){
				$return_url = str_replace('/source/','/',U('My/store_order_list',array('order_id'=>$now_order['order_id'])));
			}else{
				$return_url = U('User/Index/store_order_list',array('order_id'=>$now_order['order_id']));
			}
			return array('error'=>1,'msg'=>$error_tips.'已将您充值的金额添加到您的余额内。','url'=>$return_url);
		}
	}

	/**
	 * 店铺当天订单数量
	 */

	public function get_storeorder_count_today($order_id){
		$today = strtotime(date('Ymd',time()));
		$where['store_id'] = $order_id;
		$where['pay_time'] = array('gt',$today);
		return $this->where($where)->count();
	}

	/**
	 * 扫码订单查询
	 */
	public function get_order_by_payid($uid,$payid){
		return $this->where(array('uid'=>$uid,'payid'=>$payid))->find();
	}

	/*
	 * 判断用户积分优惠券，并保存
	 * */
	public function check_coupon_score($now_user,$now_order)
	{
		$order_info = array(
				'order_id'			=>	$now_order['order_id'],
				'mer_id'			=>	$now_order['mer_id'],
				'order_type'		=>	'store',
				'order_name'		=>	$now_order['name'],
				'store_id'		=>	$now_order['store_id'],
				'no_discount_money'		=>	$now_order['no_discount_money'],
				'order_num'			=>	0,
				'order_price'		=>	floatval($now_order['price']),
				'order_total_money'	=>	floatval($now_order['price']),
				'uid'	=>	$now_user['uid'],
		);
		$order_type  = $order_info['order_type'];

		if ($order_info['mer_id']) {
			$now_merchant = D('Merchant')->get_info($order_info['mer_id']);
		}

		$now_user['now_money'] = 0;
		$platform = 'weixin';

		$order_info['total_money'] = $order_info['order_total_money'];
		$tmp_order = $order_info;
		if ($order_info['order_type'] == 'store') {
			$tmp_order['total_money'] -= $tmp_order['no_discount_money'];
		}

		$merchant_balance = 0 ;
		$card_info = D('Card_new')->get_card_by_uid_and_mer_id($now_user['uid'], $order_info['mer_id']);

		if(C('config.cash_nobind_phone_use_balance') || !empty($now_user['phone'])){


			if($card_info['status']) {
				if ((!isset($order_info['discount_status']) || $order_info['discount_status'])) {

						if (!empty($order_info['business_type'])) {
							$card_coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order, $order_type, $platform, $order_info['business_type']);
						} else {
							$card_coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order, $order_type, $platform);
						}

						$tmp_coupon = reset($card_coupon_list);

					if(!empty($tmp_coupon)){
						$mer_coupon['had_id'] = $tmp_coupon['id'];
						//$mer_coupon['coupon_id'] = $tmp_coupon['coupon_id'];
						$mer_coupon['order_money'] = $tmp_coupon['order_money'];
						$mer_coupon['discount'] = $tmp_coupon['discount'];
					}else{
						$mer_coupon =array();
					}
				}else{
					$mer_coupon = array();
				}
			}else{
				$mer_coupon = array();
			}

			$tmp_order['total_money'] -= empty($mer_coupon['discount']) ? 0 : $mer_coupon['discount'];

			//平台优惠券
			if ($tmp_order['total_money'] > $mer_coupon['discount']) {


					if (!empty($order_info['business_type'])) {
						$now_coupon = D('System_coupon')->get_noworder_coupon_list($tmp_order, $order_type, '', $now_user['uid'], $platform, $order_info['business_type']);
					} else {
						$now_coupon = D('System_coupon')->get_noworder_coupon_list($tmp_order, $order_type, '', $now_user['uid'], $platform);

					}
				$tmp_coupon = reset($now_coupon);

				if($tmp_coupon){
					$system_coupon['had_id'] = $tmp_coupon['id'];
					//	$system_coupon['coupon_id'] = $tmp_coupon['coupon_id'];
					$system_coupon['order_money'] = $tmp_coupon['order_money'];
					$system_coupon['discount'] = $tmp_coupon['discount'];
				}else{
					$system_coupon =array();
				}
			}else{
				$system_coupon = array();
			}

			if (isset($order_info['discount_status']) && !$order_info['discount_status']) {
				$card_info['discount'] = 10;
			}
			$tmp_order['total_money'] -=$system_coupon['discount'];

			if($tmp_order['total_money']<0){
				$tmp_order['total_money']= 0;
			}
		}
		if($order_info['order_type']=='store'){
			// $pay_infact+=$tmp_order['no_discount_money'];
			$tmp_order['total_money']+=$tmp_order['no_discount_money'];
		}

		$score_can_use_count=0;
		$score_deducte=0;

		$type_ =$order_type;
		$user_score_use_condition=C('config.user_score_use_condition');
		$user_score_max_use=D('Percent_rate')->get_max_core_use($order_info['mer_id'],$type_,$tmp_order['total_money']);

		//$user_score_max_use=$score_config['user_score_max_use'];
		if($order_type=='group'){
			$group_info = D('Group')->where(array('group_id'=>$order_info['group_id']))->find();
			if($group_info['score_use']){
				if($group_info['group_max_score_use']!=0){
					$user_score_max_use = $group_info['group_max_score_use'];
				}
			}else{
				$user_score_max_use = 0;
			}
		}
		$user_score_use_percent=C('config.user_score_use_percent');
		$score_max_deducte=bcdiv($user_score_max_use,$user_score_use_percent,2);

		if($user_score_use_percent>0&&$score_max_deducte>0&&$user_score_use_condition>0&&$now_user['score_count']>0){   //如果设置没有错误
			$total_ = $tmp_order['total_money'];
			if ($total_>=$user_score_use_condition) {             //判断是否够使用积分的条件，即这个订单的总额是否大于规定的金额
				if($total_>$score_max_deducte){                    //判断积分最大抵扣金额是否比这个订单的总额大
					$score_can_use_count = (int)($now_user['score_count']>$user_score_max_use?$user_score_max_use:$now_user['score_count']);//如果用户积分大于每个订单的积分可使用数 则按可使用数算
					$score_deducte =bcdiv($score_can_use_count,$user_score_use_percent,2);
					$score_deducte = $score_deducte>$total_?$total_:$score_deducte;
				}else{                                                                      //最大可抵扣的金额比总单金额大 只扣掉总单范围内的积分 扣除积分=总单*积分抵扣比例
					$score_can_use_count = ceil($total_*$user_score_use_percent)>(int)$now_user['score_count']?(int)$now_user['score_count']:ceil($total_*$user_score_use_percent);
					$score_deducte = bcdiv($score_can_use_count,$user_score_use_percent,2);
					$score_deducte = $score_deducte>$total_?$total_:$score_deducte;
				}
			}
		}

		//子商户设置不允许使用平台优惠抵扣
		if($now_user['forzen_score'] == 1 || ($this->config['cash_nobind_phone_use_balance']==0 && empty($now_user['phone']))){
			$score_can_use_count = 0;
			$score_deducte = 0;
		}
		$tmp_order['total_money']=$tmp_order['total_money']*100-$score_deducte*100;

		$arr['card_id'] = intval($mer_coupon['had_id']);
		$arr['card_price'] = floatval($mer_coupon['discount']);
		$arr['coupon_id'] = intval($system_coupon['had_id']);
		$arr['coupon_price'] = floatval($system_coupon['discount']);
		$arr['score_used_count'] = $score_can_use_count;
		$arr['score_deducte'] = $score_deducte;
		$arr['total_money'] = $tmp_order['total_money']/100;
		return $arr;
	}
}
?>