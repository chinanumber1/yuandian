<?php
class Foodshop_orderModel extends Model
{
	public $status_list = array('-1' => '全部', '0' => '订单生成', 1 => '预定金已支付', 2 => '店员确认', 3 => '买单完成', 4 => '已评价', 5 => '已取消', 11 => '待店员操作');
	
	/**获取订单分类**/
	public function get_order_cate($order_id)
	{
		$store_id = $this->field('store_id')->where(array('order_id'=>$order_id))->find();
		$cat_id = M('Foodshop_category_relation')->field('cat_fid')->where(array('store_id'=>$store_id['store_id']))->find();
		$meal_cate = M('Foodshop_category')->field('cat_id,cat_name')->where(array('cat_id'=>$cat_id['cat_fid']))->find();
		return $meal_cate;
	}

	public function can_refund_status($now_order){
		if($now_order['status']!=1){
			return false;
		}else{
			return true;
		}
	}



	public function get_order_by_id($uid, $order_id, $orderid = 0)
	{
		$where = array();
		$order_id && $where['order_id'] = $order_id;
		$orderid && $where['orderid'] = $orderid;
		$where['uid'] = $uid;
		return $this->field(true)->where($where)->find();
	}

	public function get_order_by_orderid($order_id)
	{
		$where = array();


		$order_id && $where['order_id'] = $order_id;
		if (strlen($order_id) > 10){
			$where['real_orderid'] = $order_id;
			unset($where['order_id']);
		}
		return $this->get_order_detail($where, 3);
		return $this->field(true)->where($where)->find();
	}

	public function get_order($order_id){
		return $this->where(array('order_id'=>$order_id))->find();
	}

    public function get_pay_order($order_id)
    {
        $now_order = $this->get_order($order_id);
        return array(
            'pay_offline' => false,//D('Percent_rate')->pay_offline($now_order['mer_id'],'meal'), // 线下支付 D('Percent_rate')->pay_offline($now_order['mer_id'],'meal')
            'pay_merchant_balance' => true, // 商家余额
            'pay_merchant_coupon' => true, // 商家优惠券
            'pay_merchant_ownpay' => true, // 商家自有支付
            'pay_system_balance' => true, // 平台余额
            'pay_system_coupon' => true, // 平台优惠券
            'pay_system_score' => true, // 平台积分抵现
            'discount_status' => $now_order['is_book_pay'] == 3 ? false : true, // 平台积分抵现
            'order_info' => $now_order,
            'status' => $now_order['status']
        );
    }
	public function get_order_url($order_id, $is_mobile)
	{
		$now_order = $this->get_order_detail(array('order_id' => $order_id));
		if ($now_order) {
			if ($now_order['status'] == 1) {
				return C('config.site_url').'/wap.php?c=Foodshop&a=book_success&order_id=' . $order_id;
			} else {
				return C('config.site_url').'/wap.php?c=Foodshop&a=order_detail&order_id=' . $order_id;
			}
		}
		
	}
	public function after_pay($order_id, $plat_order_info = array(), $pay_type = 0)
	{
		$now_order = $this->get_order_detail(array('order_id' => $order_id), 3);
		if ($now_order['status'] > 2) {
		    return ;
		}
		$now_user = D('User')->get_user($now_order['uid']);
		$save_data = '';

		if ($now_order['status'] == 0) {
		    $now_order['status'] = 1;
		    D('Merchant_store_staff')->sendMsgFoodShop($now_order);
			$save_data = array('book_pay_time' => time(), 'price' => $now_order['book_price'], 'status' => 1, 'is_book_pay' => 1);
		} else {
			$price = $this->count_price($now_order);

			$payMoney = 0 ;
			$book_order = M('Plat_order')->where(array('business_id' => $order_id, 'business_type' => 'foodshop', 'paid' => 1))->select();
			
			$order_info['balance_pay'] = 0;
			$order_info['payment_money'] = 0;
			$order_info['score_deducte'] = 0;
			$order_info['score_used_count'] = 0;
			$order_info['coupon_price'] = 0;
			$order_info['merchant_balance'] = 0;
			$order_info['card_give_money'] = 0;

			foreach ($book_order as $book) {
			    $payMoney += floatval($book['total_money']);

				if($book['is_own']>0){
					$book['pay_money'] = 0;
				}

			    $order_info['balance_pay'] += $book['system_balance'];
			    $order_info['payment_money'] += $book['pay_money'];
			    $order_info['score_deducte'] += $book['system_score_money'];
			    $order_info['score_used_count'] += $book['system_score'];
			    $order_info['coupon_price'] += $book['system_coupon_price'];
			    $order_info['merchant_balance'] += $book['merchant_balance_pay'];
			    $order_info['card_give_money'] += $book['merchant_balance_give'];
			}
			$store_order = M('Store_order')->field('price')->where(array('business_id' => $order_id, 'business_type' => 'foodshop', 'paid' => 1))->select();

			foreach ($store_order as $storder) {
			    $payMoney += floatval($storder['price']);
			}

			//商家桌次折扣
            if(C('config.is_open_merchant_foodshop_discount')==1){
                $where = array('real_orderid'=>$now_order['real_orderid']);
                $order = D('Foodshop_order')->where($where)->find();
                $discount = $order['mer_table_discount'];
                $can_discount_table_money = $order['can_discount_table_money'];
                if($discount!=0 && $discount!=100){
                    $reduceMoney = round($can_discount_table_money - $can_discount_table_money * ($discount / 100),2);
                    $payMoney -= $reduceMoney;
				}
            }

			$save_data = array('price' => $payMoney, 'status' => 3, 'pay_type' => $pay_type);
			$save_data['total_price'] = $price + $now_order['price'];
			if (empty($now_order['phone']) && $now_order['uid']) {
			    $user = D('User')->field('phone')->where(array('uid' => $now_order['uid']))->find();
			    if ($user) {
			        $save_data['phone'] = $user['phone'];
			    }
			}
			$save_data['pay_time'] = $_SERVER['REQUEST_TIME'];
			$order_info['order_id'] = $now_order['real_orderid'];
			$order_info['store_id'] = $now_order['store_id'];
			$order_info['order_type'] = 'meal';
			$order_info['total_price'] = $payMoney;//$save_data['total_price'];
			$order_info['total'] = 1 ;
			$order_info['order_from'] = $now_order['order_from'];
			$order_info['is_own'] = $plat_order_info['is_own'];
			$order_info['mer_id'] = $now_order['mer_id'];
			$order_info['uid'] = $now_order['uid'];
			$order_info['desc']='购买餐饮商品';
			 $order_info['score_discount_type']=$now_order['score_discount_type'];

			$res = D('SystemBill')->bill_method($order_info['is_own'],$order_info);


			//微信派发优惠券 支付到平台 微信支付
			if($plat_order_info['is_own']==0 && $plat_order_info['pay_type']=='weixin' && $order_info['payment_money']>=C('config.weixin_send_money')){
				D('System_coupon')->weixin_send($order_info['payment_money'],$order_info['uid']);
			}

			// 加积分

			if(C('config.open_extra_price')==1){
				$order_info['extra_price'] = $now_order['extra_price'] ;
				$score = D('Percent_rate')->get_extra_money($order_info);
					
				if($score>0){
					D('User')->add_score($now_order['uid'], $score,'购买'.C('config.meal_alias_name').'商品获得'.C('config.score_name'));

				}
			}else if(C('config.add_score_by_percent')==0 && (C('config.open_score_discount')==0 || $plat_order_info['score_discount_type']!=2)){
				if(C('config.open_score_get_percent')==1){
					$score_get = C('config.score_get_percent')/100;
				}else{
					
					$score_get = C('config.user_score_get');
				}
				// if(C('config.open_score_fenrun')){
					if($order_info['is_own'] && C('config.user_own_pay_get_score')!=1){
						$order_info['payment_money'] = 0;
					}
					$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
					if($now_merchant['score_get_percent']>=0){
						$score_get = $now_merchant['score_get_percent']/100;
					}
				// }

				D('User')->add_score($now_order['uid'], floor(($order_info['balance_pay']+$order_info['payment_money']) * $score_get), '购买餐饮商品获得'.C('config.score_name'));
				D('Scroll_msg')->add_msg('foodshop',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.C('config.meal_alias_name').'成功获得'.C('config.score_name'));
			}
			// 加积分
		}
        if ($now_order['plat_discount'] > 0) {
            $now_order['plat_discount'] = 100;
            $oldprice = $this->count_price($now_order);
            $diffPrice = floatval($oldprice - $price);
            $save_data['plat_discount_price'] = $diffPrice;
        }
			$save_data['payment_method'] = $plat_order_info['pay_type'] ? $plat_order_info['pay_type'] :'';
        if ($this->where(array('order_id' => $order_id))->save($save_data)) {
            if ($now_order['plat_discount'] > 0) {
                $logData = $now_order;
                $logData['plat_discount_price'] = $diffPrice;
                D('Merchant_discount_log')->saveLog($logData, 1);
            }
// 		    $goods = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id))->select();
// 		    $foodShopObj = D("Foodshop_goods");
// 		    foreach ($goods as $gr) {
// 		        $foodShopObj->update_stock($gr['goods_id'], $gr['num'], $gr['spec_id']);
// 		    }
			D('Scroll_msg')->add_msg('foodshop',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.C('config.meal_alias_name').'成功');

			if(C('config.open_extra_price')==1&&$now_order['status'] != 0){
				$total_money_tmp=($order_info['balance_pay']+$order_info['merchant_balance']+$order_info['pay_money']).'元+'.$order_info['score_used_count'].C('config.score_name');

			}else{
				$total_money_tmp =floatval($save_data['price']) . '元';
			}
			if ($now_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find()) {
				if ($now_user['openid']) {
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					if ($now_order['status'] == 0) {
						$href = C('config.site_url').'/wap.php?c=Foodshop&a=book_success&order_id='. $order_id;
						$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => C('config.meal_alias_name').'提醒', 'keyword2' => $now_order['real_orderid'], 'keyword1' => $now_order['table_name'], 'keyword3' => $total_money_tmp, 'keyword4' => date('Y.m.d H:i'), 'remark' => '预订成功,期待您的到来!'), $now_order['mer_id']);
					} else {
						$href = C('config.site_url').'/wap.php?c=Foodshop&a=order_detail&order_id='. $order_id;
						$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => C('config.meal_alias_name').'提醒', 'keyword2' => $now_order['real_orderid'], 'keyword1' => $now_order['info'][0]['name'] . '...等菜品', 'keyword3' => $total_money_tmp, 'keyword4' => date('Y.m.d H:i'), 'remark' => '买单成功,欢迎下次光临!'), $now_order['mer_id']);
					}
				}
				$spread_total_money = $plat_order_info['system_balance'] +$plat_order_info['pay_money'];
				if(!empty($now_user['openid'])&&C('config.open_user_spread') && (C('config.spread_money_limit')==0 || C('config.spread_money_limit')<=$spread_total_money)){
					//上级分享佣金
					$spread_rate = D('Percent_rate')->get_user_spread_rate($now_order['mer_id'],'meal');
					$spread_users[]=$now_user['uid'];
					if($now_user['wxapp_openid']!=''){
						$spread_where['_string'] = "openid = '{$now_user['openid']}' OR openid = '{$now_user['wxapp_openid']}' ";
					}else{
						$spread_where['_string'] = "openid = '{$now_user['openid']}'";
					}
					$now_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where)->find();

					$href = C('config.site_url') . '/wap.php?g=Wap&c=My&a=spread_list&status=-1';
					$open_extra_price = C('config.open_extra_price');
					if($plat_order_info['is_own']  && C('config.own_pay_spread')==0){
						$data_group_order['payment_money']=0;
					}
					if(!empty($now_user_spread)){
						if($now_user_spread['is_wxapp']){
							$spread_user = D('User')->get_user($now_user_spread['spread_openid'],'wxapp_openid');
						}else{
							$spread_user = D('User')->get_user($now_user_spread['spread_openid'],'openid');
						}
						//$user_spread_rate = $update_group['spread_rate'] > 0 ? $update_group['spread_rate'] : C('config.user_spread_rate');
						$user_spread_rate = $spread_rate['first_rate'];
						if($spread_user && $user_spread_rate&&!in_array($spread_user['uid'],$spread_users)){
							$spread_money = round(($plat_order_info['system_balance'] +$plat_order_info['pay_money']) * $user_spread_rate / 100, 2);
							$spread_data = array('uid'=>$spread_user['uid'],'spread_uid'=>0,'get_uid'=>$now_user['uid'],'money'=>$spread_money,'order_type'=>'meal','order_id'=>$now_order['order_id'],'third_id'=>$now_order['store_id'],'add_time'=>$_SERVER['REQUEST_TIME']);
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
								$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $spread_user['openid'], 'first' => $buy_user['nickname'] . '通过您的分享消费了餐饮，验证消费后您将获得'.$money_name.'。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
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
//							$sub_user_spread_rate = $update_group['sub_spread_rate'] > 0 ? $update_group['sub_spread_rate'] : C('config.user_first_spread_rate');
							$sub_user_spread_rate = $spread_rate['second_rate'];
							if ($second_user && $sub_user_spread_rate&&!in_array($second_user['uid'],$spread_users)) {
								$spread_money = round(($plat_order_info['system_balance'] +$plat_order_info['pay_money']) * $sub_user_spread_rate / 100, 2);

								$spread_data=array('uid' => $second_user['uid'], 'spread_uid' => $spread_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => 'meal', 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
								if($spread_user['spread_change_uid']!=0){
									$spread_data['change_uid'] = 	$second_user['spread_change_uid'];
								}

								D('User_spread_list')->data($spread_data)->add();
								$sec_user = D('User')->get_user($second_user_spread['openid'], 'openid');
								if($spread_money>0) {
									$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $second_user['openid'], 'first' => $sec_user['nickname'] .'的子用户'.$buy_user['nickname'] . '通过您的分享消费了餐饮，验证消费后您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
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
//								$sub_user_spread_rate = $update_group['third_spread_rate'] > 0 ? $update_group['third_spread_rate'] : C('config.user_second_spread_rate');
								$sub_user_spread_rate = $spread_rate['third_rate'];
								if ($first_spread_user && $sub_user_spread_rate&&!in_array($first_spread_user['uid'],$spread_users)) {
									$spread_money = round(($plat_order_info['system_balance'] +$plat_order_info['pay_money']) * $sub_user_spread_rate / 100, 2);
									$spread_data = array('uid' => $first_spread_user['uid'], 'spread_uid' => $second_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => 'meal', 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
									if($spread_user['spread_change_uid']!=0){
										$spread_data['change_uid'] = 	$first_spread_user['spread_change_uid'];
									}

									D('User_spread_list')->data($spread_data)->add();

									$fir_user = D('User')->get_user($first_user_spread['openid'], 'openid');
									if($spread_money>0) {
										$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $first_spread_user['openid'], 'first' =>$fir_user['nickname'].'的子用户的子用户'.$buy_user['nickname'] . '通过您的分享消费了餐饮，验证消费后您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
									}
									// D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
								}
							}

						}
					}
				}
			}

			//增加粉丝 5 group 6 shop 7 meal 8 appoint 9 store
			D('Merchant')->saverelation($now_user['openid'], $now_order['mer_id'], 7);
				
			//小票打印
			$printHaddle = new PrintHaddle();
			if ($save_data['status'] == 3) {
			    $printHaddle->printit($now_order['order_id'], 'foodshop_order', 1);
			} else {
			    $printHaddle->printit($now_order['order_id'], 'foodshop_order', 0);
			}
			
			
//     		$store = D('Merchant_store')->field(true)->where(array('store_id' => $now_order['store_id']))->find();
//     		$msg = ArrayToStr::array_to_str($now_order['order_id'], 'foodshop_order');
//     		$op = new orderPrint(C('config.print_server_key'), C('config.print_server_topdomain'));
//     		$op->printit($store['mer_id'], $store['store_id'], $msg, 1);
    		
//     		$str_format = ArrayToStr::print_format($now_order['order_id'], 'foodshop_order');
//             foreach ($str_format as $print_id => $print_msg) {
//                 if ($print_id) {
//                     foreach ($print_msg as $msg) {
//                         $op->printit($store['mer_id'], $store['store_id'], $msg, 1, $print_id);
//                     }
//                 }
//             }
			if ($save_data['status'] == 3) {//通知店员
			    $now_order['status'] = 3;
                D('Merchant_store_staff')->sendMsgFoodShop($now_order);
			}
			//短信提醒
			$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['mer_id'], 'type' => 'food');
			if (C('config.sms_success_order') == 1 || C('config.sms_success_order') == 3) {
				if (empty($now_order['phone'])) {
					$now_order['phone'] = isset($now_user['phone']) && $now_user['phone'] ? $now_user['phone'] : '';
				}
				$sms_data['uid'] = $now_order['uid'];
				$sms_data['mobile'] = $now_order['phone'];
				$sms_data['sendto'] = 'user';
				
				if ($now_order['status'] == 1) {
					$sms_data['content'] = '您在' . date('Y-m-d H:i:s') . '时，预订了' . $store['name'] . '的' . $now_order['table_type_name'] . $now_order['book_num'] . '人，已成功生成订单，订单号：' . $now_order['real_orderid'];
				} else {
					$sms_data['content'] = '您预订的' . $store['name'] . $now_order['table_type_name'] . $now_order['book_num'] . '人的订单(订单号：' . $now_order['real_orderid'] . ')已经完成支付。欢迎下次光临！';
				}
				$sms_data['mobile'] && Sms::sendSms($sms_data);
			}
			
			if (C('config.sms_success_order') == 2 || C('config.sms_success_order') == 3) {
				$sms_data['uid'] = 0;
				$sms_data['mobile'] = $store['phone'];
				$sms_data['sendto'] = 'merchant';

				if ($now_order['status'] == 1) {
					$sms_data['content'] = '顾客在' . date('Y-m-d H:i:s') . '时，预订了' . $store['name'] . '的' . $now_order['table_type_name'] . $now_order['book_num'] . '人，已成功生成订单，订单号：' . $now_order['real_orderid'];
				} else {
					$sms_data['content'] = '顾客预订的' . $store['name'] . '的' . $now_order['table_type_name'] . $now_order['book_num'] . '人的订单(订单号：' . $now_order['real_orderid'] . '),在' . date('Y-m-d H:i:s') . '时已经完成了支付！';
				}
				$sms_data['mobile'] && Sms::sendSms($sms_data);
			}
		}
		//D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['money'],$now_order['give_money'],$now_order['give_score'],'在线充值'.intval($now_order['money']).'元','在线充值'.intval($now_order['money']).'元赠送');
	}

	//退款后发送 短信、模板消息、减库存 等等
	public function afert_refund($now_order){
		//发模板消息.
		if($this->where(array('order_id'=>$now_order['order_id']))->setField('status',5)) {
			$order_id = $now_order['order_id'];
			$href = C('config.site_url') . '/wap.php?c=Foodshop&a=book_success&order_id=' . $order_id;
			if ($now_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find()) {
				if ($now_user['openid']) {
					$now_store = M('Merchant_store')->where(array('store_id'=>$now_order['store_id']))->find();
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));

					$model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_store['name'].C('config.meal_alias_name') . '订金取消提醒', 'OrderSn' => $now_order['real_orderid'], 'OrderStatus' =>'订单已于 '.date('Y.m.d H:i:s').' 取消成功', 'remark' => '感谢您的使用欢迎您继续光临本店'), $now_order['mer_id']);

				}
				//小票打印
				$printHaddle = new PrintHaddle();
				$printHaddle->printit($now_order['order_id'], 'foodshop_order', 3);
				
// 				$store = D('Merchant_store')->field(true)->where(array('store_id' => $now_order['store_id']))->find();
// 				$msg = ArrayToStr::array_to_str($now_order['order_id'], 'foodshop_order');
// 				$op = new orderPrint(C('config.print_server_key'), C('config.print_server_topdomain'));
// 				$op->printit($store['mer_id'], $store['store_id'], $msg, 1);
				
// 				$str_format = ArrayToStr::print_format($now_order['order_id'], 'foodshop_order');
//                 foreach ($str_format as $print_id => $print_msg) {
//                     if ($print_id) {
//                         foreach ($print_msg as $msg) {
//                             $op->printit($store['mer_id'], $store['store_id'], $msg, 1, $print_id);
//                         }
//                     }
//                 }
				
				//短信提醒
				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['mer_id'], 'type' => 'food');
				if (C('config.sms_cancel_order') == 1 || C('config.sms_cancel_order') == 3) {
					if (empty($now_order['phone'])) {
						$now_order['phone'] = isset($now_user['phone']) && $now_user['phone'] ? $now_user['phone'] : '';
					}
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_order['phone'];
					$sms_data['sendto'] = 'user';
					
					$sms_data['content'] = '您预订的' . $store['name'] . $now_order['table_type_name'] . $now_order['book_num'] . '人的订单(订单号：' . $now_order['real_orderid'] . '),在' . date('Y-m-d H:i:s') . '时取消成功！';
					Sms::sendSms($sms_data);
				}
				
				if (C('config.sms_cancel_order') == 2 || C('config.sms_cancel_order') == 3) {
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $store['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客预订的' . $store['name'] . '的' . $now_order['table_type_name'] . $now_order['book_num'] . '人的订单(订单号：' . $now_order['real_orderid'] . '),在' . date('Y-m-d H:i:s') . '时取消订单！';
					Sms::sendSms($sms_data);
				}
			}
			return array('error_code'=>true,'msg'=>'订单取消成功！','url'=>$href);
		}else{
			return array('error_code'=>true,'msg'=>'订单状态更新失败！');
		}
	}

	public function count_price($order, $returnType = 0)
	{
		$price = 0;
		$totalDiscount = 0;
		$mer_id = $order['mer_id'];
		$plat_discount = $order['plat_discount'];
		$mer_table_discount = $order['mer_table_discount'];
		if ($plat_discount <= 0 || $plat_discount >= 100) {
		    $plat_discount = 100;
		}
		//套餐价格
		if ($order['package_ids']) {
			$package_ids = json_decode($order['package_ids'], true);
			$packages = D('Foodshop_goods_package')->field(true)->where(array('in' => array('id', $package_ids)))->select();
			foreach ($package_ids as $pid) {
				foreach ($packages as $p) {
					if ($pid == $p['id']) {
					    $price += $p['price'] * $plat_discount * 0.01;
					    $totalDiscount += $p['price'] * $plat_discount * 0.01;
					}
				}
			}
		}
		//菜品总价
		$must_goods = array();
		foreach ($order['info'] as $info) {
		    if (empty($info['package_id']) && empty($info['is_must'])) {
//			    if ($info['is_discount']) {
//                    $price += $info['price'] * $info['num'] * $plat_discount * 0.01;
//                    $totalDiscount += $info['price'] * $info['num'] * $plat_discount * 0.01;
//                }else
				if($info['is_table_discount']){
                    $price += $info['price'] * $info['num'];
                    $totalDiscount += $info['price'] * $info['num'];
				}else{
			        $price += $info['price'] * $info['num'];
			    }
			} elseif ($info['ishide']) {//计算修改后的套餐存储方式
//			    if ($info['is_discount']) {
//			        $price += $info['price'] * $info['num'] * $plat_discount * 0.01;
//			        $totalDiscount += $info['price'] * $info['num'] * $plat_discount * 0.01;
//			    }else
				if($info['is_table_discount']){
                    $price += $info['price'] * $info['num'];
                    $totalDiscount += $info['price'] * $info['num'];
                } else {
			        $price += $info['price'] * $info['num'];
			    }
			}
			if ($info['is_must']) {
				$must_goods[$info['goods_id']] = array('id' => $info['id'], 'num' => $info['num']);
			}
		}

		//必点菜的总价统计
		
		$goods_list = D('Foodshop_goods')->field(true)->where(array('store_id' => $order['store_id'], 'status' => 1, 'is_must' => 1))->select();
		$now_time = time();
		$save_data = array();
		foreach ($goods_list as $goods) {
//		    if ($goods['is_discount']) {
//		        $price += $goods['price'] * $order['book_num'] * $plat_discount * 0.01;
//		        $totalDiscount += $goods['price'] * $order['book_num'] * $plat_discount * 0.01;
//		    }else
			if($goods['is_table_discount']){
                $price += $goods['price'] * $order['book_num'];
                $totalDiscount += $goods['price'] * $order['book_num'];
            } else {
		        $price += $goods['price'] * $order['book_num'];
		    }
// 			$price += $goods['price'] * $order['book_num'];
			if (isset($must_goods[$goods['goods_id']]['id']) && $must_goods[$goods['goods_id']]['id']) {
				if ($order['book_num'] > 0) {
					if ($must_goods[$goods['goods_id']]['num'] != $order['book_num']) {
						D('Foodshop_order_detail')->where(array('id' => $must_goods[$goods['goods_id']]['id']))->save(array('num' => $order['book_num']));
					}
					unset($must_goods[$goods['goods_id']]);
				}
// 				if ($order['book_num'] > 0) unset($must_goods[$goods['goods_id']]);
			} else {
// 				D('Foodshop_order_detail')->add(array('goods_id' => $goods['goods_id'], 
// 						'num' => $order['book_num'], 
// 						'price' => $goods['price'], 
// 						'order_id' => $order['order_id'], 
// 						'store_id' => $order['store_id'], 
// 						'name' => $goods['name'],
// 						'unit' => $goods['unit'],
// 						'number' => $goods['number'],
// 						'is_must' => 1,
// 						'create_time' => $now_time
// 				));
				$order['book_num'] && $save_data[] = array('goods_id' => $goods['goods_id'], 
						'num' => $order['book_num'], 
						'price' => $goods['price'], 
						'order_id' => $order['order_id'], 
						'store_id' => $order['store_id'], 
						'name' => $goods['name'],
						'unit' => $goods['unit'],
						'number' => $goods['number'],
						'is_must' => 1,
						'create_time' => $now_time
				);
			}
		}
		if ($must_goods) {
			$ids = array();
			foreach ($must_goods as $id => $v) {
				$ids[] = $v['id'];
			}

			if ($ids) {
				D('Foodshop_order_detail')->where(array('id' => array('in', $ids)))->delete();
			}
		}
		if(!$returnType){
            if ($save_data) {//将必点菜插入订单详情中去
                D('Foodshop_order_detail')->addAll($save_data);
            }
		}
		//减去已支付的预订金
		if ($order['status']) {
			$price -= $order['book_price'];
		}
		$price = max(0, $price);
		if ($returnType) {
		    return $totalDiscount;
		} else {
		    return $price;
		}
	}


	public function count_extra_price($order)
	{
		$price = 0;
		$order_detail = M('Foodshop_order_detail')->where(array('order_id'=>$order['order_id']))->select();
		foreach ($order_detail as $v) {
			if($v['extra_price']>0){
				$price+=$v['extra_price']*$v['num'];
			}
		}
		return $price;
	}
	
	/**
	 * @param array $where
	 * @param string $order
	 * @param int $from 0:用户的个人中心订单列表，1:商家中心的订单列表 2:打包app店员中心
	 * @return array
	 */
	public function get_order_list($where = array(), $order = 'order_id DESC', $from = 0)
	{
		$count = $this->where($where)->count();
		if ($from == 0) {
// 			import('@.ORG.wap_group_page');
// 			$p = new Page($count, 20, 'p');
// 			$list = $this->where($where)->order($order)->limit($p->firstRow . ',' . $p->listRows)->select();
			$list = $this->where($where)->order($order)->select();
			return array('order_list' => $list);
		} elseif ($from == 1) {
			import('@.ORG.merchant_page');
			$p = new Page($count, 20);
			$list = $this->where($where)->order($order)->limit($p->firstRow . ',' . $p->listRows)->select();
		} elseif ($from == 2) {
			import('@.ORG.merchant_page');
			$_GET['page'] = isset($_POST['page']) ? intval($_POST['page']) : 1;
			$p = new Page($count, 10);
			$list = $this->where($where)->order($order)->limit($p->firstRow . ',' . $p->listRows)->select();
			$order_list = array();
			foreach ($list as $r) {
				$thisorder = $this->get_order_detail(null, 2, $r);
				$price = $this->count_price($thisorder);
				$new_order = $this->get_order_detail(null, 3, $r);
				$new_order['totalPrice'] = floatval($price + $thisorder['book_price']);
				$order_list[] = $new_order;
				
			}
			return array('order_list' => $order_list, 'count' => $count, 'totalPage' => $p->totalPage);
		}
		$table_types = array();
		$tids = array();
		foreach ($list as $row) {
			if (!in_array($row['table_id'], $tids)) {
				$tids[] = $row['table_id'];
			}
			if (!in_array($row['table_type'], $table_types)) {
				$table_types[] = $row['table_type'];
			}
		}
		$type_list = array();
		if ($table_types) {
			$temp_type_list = M('Foodshop_table_type')->field(true)->where(array('id' => array('in', $table_types)))->select();
			foreach ($temp_type_list as $tmp) {
				$type_list[$tmp['id']] = $tmp;
			}
		}
		$table_list = array();
		if ($tids) {
			$temp_table_list = M('Foodshop_table')->field(true)->where(array('id' => array('in', $tids)))->select();
			foreach ($temp_table_list as $temp) {
				$table_list[$temp['id']] = $temp;
			}
		}
		foreach ($list as &$val) {
			$val['table_type_name'] = isset($type_list[$val['table_type']]) ? $type_list[$val['table_type']]['name'] . '(' . $type_list[$val['table_type']]['min_people'] . '-' . $type_list[$val['table_type']]['max_people'] . '人)' : '';
			$val['table_name'] = isset($table_list[$val['table_id']]) ? $table_list[$val['table_id']]['name'] : '';
			$val['show_status'] = $this->status_list[$val['status']];
		}
		return array('order_list' => $list, 'pagebar' => $p->show());
	}
	
	
	

	/**
	 * @param array $where
	 * @param int $show_type 0:只查找订单，1：查找包括桌台信息，2：包括菜品信息，3：包括支付信息
	 * @return boolean|mixed
	 */
	public function get_order_detail($where, $show_type = 2, $order = array())
	{
		if (!empty($where)) {
			$order = $this->field(true)->where($where)->find();
		}
		if (empty($order)) return false;
		
		$order['date'] = date('Y-m-d H:i:s', $order['create_time']);
		$order['show_status'] = $this->status_list[$order['status']];
		$order['status_str'] = $this->status_list[$order['status']];
		$order['note'] = $order['note'] ? $order['note'] : '无';
		if ($show_type == 0) return $order;
		
		$order['table_name'] = '';
		$order['table_type_name'] = '';
		if ($order['table_id']) {
			$table = M('Foodshop_table')->field(true)->where(array('id' => $order['table_id']))->find();
			$order['table_name'] = $table ? $table['name'] : '';
		}
		if ($order['table_type']) {
			$type = M('Foodshop_table_type')->field(true)->where(array('id' => $order['table_type']))->find();
			$order['table_type_name'] = $type ? $type['name'] : '';
			$order['min_max'] = $type ? '(' . $type['min_people'] . '-' . $type['max_people'] . '人)' : '';
		}
		$order['book_time_show'] = $order['book_time'] ? date('m月d日 H:i', $order['book_time']) : '--';
		$order['book_time_packapp'] = $order['book_time'] ? date('Y-m-d H:i', $order['book_time']) : '--';
		if ($show_type == 1) return $order;
		$order['info'] = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order['order_id']))->select();
		foreach ($order['info'] as &$r) {
			$r['num'] = floatval($r['num']);
			$r['price'] = floatval($r['price']);
		}
		$order['info_temp'] = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order['order_id']))->select();
		foreach ($order['info_temp'] as &$r) {
			$r['num'] = floatval($r['num']);
			$r['price'] = floatval($r['price']);
		}
		if ($show_type == 2) return $order;

		$result = D('Plat_order')->field(true)->where(array('business_id' => $order['order_id'], 'business_type' => 'foodshop', 'paid' => 1))->order('order_id ASC')->select();
		
		
		$order['merchant_reduce'] = 0;//店铺优惠
		$order['balance_reduce'] = 0;//平台优惠
		$order['score_used_count'] = 0;//使用积分数
		$order['score_deducte'] = 0;//积分抵用的金额
		$order['merchant_balance'] = 0;//商家余额
		$order['balance_pay'] = 0;//平台余额
		$order['card_id'] = 0;//会员卡
		$order['card_price'] = 0;//店铺优惠券金额
		$order['coupon_id'] = 0;//平台优惠券
		$order['coupon_price'] = 0;//平台优惠券金额
		$order['merchant_balance_give'] = 0;//商家赠送余额
		$order['pay_money'] = 0;//在线支付的金额

		foreach ($result as $vo) {
		    $order['merchant_reduce'] += $vo['merchant_discount_money'];//店铺优惠
		    $order['balance_reduce'] = 0;//平台优惠
		    $order['score_used_count'] += $vo['system_score'];//使用积分数
		    $order['score_deducte'] += $vo['system_score_money'];//积分抵用的金额
		    $order['merchant_balance'] += $vo['merchant_balance_pay'];//商家余额
		    $order['merchant_balance_give'] += $vo['merchant_balance_give'];//商家余额
		    $order['balance_pay'] += $vo['system_balance'];//平台余额
		    $order['card_id'] || $order['card_id'] = $vo['merchant_coupon_id'];//会员卡
		    $order['card_price'] += $vo['merchant_coupon_price'];//店铺优惠券金额
		    $order['coupon_id'] || $order['coupon_id'] = $vo['system_coupon_id'];//平台优惠券
		    $order['coupon_price'] += $vo['system_coupon_price'];//平台优惠券金额
		    $order['pay_money'] += $vo['pay_money'];
		}
		if (count($result) == 1) {
			if ($order['status'] < 3) {
				$order['book_pay_type'] = D('Pay')->get_pay_name($result[0]['pay_type'], $result[0]['is_mobile_pay'], $result[0]['paid']);
				$order['book_pay_time'] = $result[0]['pay_time'];
			} elseif ($order['pay_type'] == 1) {//店员买单
				if ($store_order = D('Store_order')->field(true)->where(array('business_type' => 'foodshop', 'business_id' => $order['order_id'], 'paid' => 1))->find()) {
					if ($store_order['pay_type'] == 'offline') {
						if ($store_order['offline_pay'] && ($store_pay = D('Store_pay')->field('name')->where(array('id' => $store_order['offline_pay']))->find())) {
							$order['pay_type'] = $store_pay['name'];
						} else {
							$order['pay_type'] = '现下支付';
						}
					} else {
						$order['pay_type'] = D('Pay')->get_pay_name($store_order['pay_type'], 0, 1);
					}
					$order['pay_time'] = $store_order['pay_time'];
					$order['book_pay_type'] = D('Pay')->get_pay_name($result[0]['pay_type'], $result[0]['is_mobile_pay'], $result[0]['paid']);
					$order['book_pay_time'] = $result[0]['pay_time'];
				} else {
					$order['pay_type'] = D('Pay')->get_pay_name($result[0]['pay_type'], $result[0]['is_mobile_pay'], $result[0]['paid']);
					$order['pay_time'] = $result[0]['pay_time'];
				}
			}else {
				$order['pay_type'] = D('Pay')->get_pay_name($result[0]['pay_type'], $result[0]['is_mobile_pay'], $result[0]['paid']);
				$order['pay_time'] = $result[0]['pay_time'];
			}
		} elseif (count($result) > 1) {
			$order['book_pay_type'] = D('Pay')->get_pay_name($result[0]['pay_type'], $result[0]['is_mobile_pay'], $result[0]['paid']);
			$order['book_pay_time'] = $result[0]['pay_time'];
			$order['pay_type'] = D('Pay')->get_pay_name($result[1]['pay_type'], $result[1]['is_mobile_pay'], $result[1]['paid']);
			$order['pay_time'] = $result[1]['pay_time'];
			
// 			$order['balance_pay'] = $result[1]['system_balance'];
// 			$order['coupon_price'] = $result[1]['system_coupon_price'];
			
// 			$order['score_used_count'] = $result[1]['system_score'];
// 			$order['score_deducte'] = $result[1]['system_score_money'];
			
// 			$order['merchant_balance'] = $result[1]['merchant_balance_pay'] + $result[1]['merchant_balance_give'];
// 			$order['card_price'] = $result[1]['merchant_coupon_price'];
// 			$order['merchant_reduce'] = $result[1]['merchant_discount_money'];
// 			$order['pay_money'] = $result[1]['pay_money'];
		} elseif ($order['pay_type'] == 1) {//店员买单
			if ($store_order = D('Store_order')->field(true)->where(array('business_type' => 'foodshop', 'business_id' => $order['order_id'], 'paid' => 1))->find()) {
				if ($store_order['pay_type'] == 'offline') {
					if ($store_order['offline_pay'] && ($store_pay = D('Store_pay')->field('name')->where(array('id' => $store_order['offline_pay']))->find())) {
						$order['pay_type'] = $store_pay['name'];
					} else {
						$order['pay_type'] = '现下支付';
					}
				} else {
					$order['pay_type'] = D('Pay')->get_pay_name($store_order['pay_type'], 0, 1);
				}
				$order['pay_time'] = $store_order['pay_time'];
			}
		}
		$order['register_phone'] = '';
		if ($user = D('User')->field(true)->where(array('uid' => $order['uid']))->find()) {
			$order['register_phone'] = $user['phone'];
		}
		return $order;
	}

	public function get_rate_order_list($uid,$is_rate=false,$is_wap=false)
	{
		$condition_where = "`o`.`uid`='$uid' AND `o`.`store_id`=`s`.`store_id`";
		if($is_rate){
			$condition_where .= " AND `o`.`paid`='1'";
			$condition_where .= " AND `o`.`status`='3'";
			$condition_where .= " AND `r`.`order_type`='3' AND `r`.`order_id`=`o`.`order_id`";
			$condition_table = array(C('DB_PREFIX').'merchant_store' => 's', C('DB_PREFIX').'shop_order' => 'o', C('DB_PREFIX').'reply' => 'r');
			$condition_field = '`o`.*,`s`.`name`,`s`.`pic_info`,`r`.*';
			$condition_order = '`r`.`pigcms_id` DESC';
		}else{
			$condition_where .= " AND `o`.`paid`='1'";
			$condition_where .= " AND `o`.`status`<3";
			$condition_table = array(C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'shop_order'=>'o');
			$condition_field = '`o`.*,`s`.`name`,`s`.`pic_info`';
			$condition_order = '`o`.`create_time` DESC';
		}

		$order_list = $this->field($condition_field)->where($condition_where)->table($condition_table)->order($condition_order)->select();
		$store_image_class = new store_image();
		foreach ($order_list as &$v) {
			$v['info'] = D("Shop_order_detail")->field(true)->where(array('order_id' => $v['order_id']))->select();
			$images = $store_image_class->get_allImage_by_path($v['pic_info']);
			$v['image'] = $images ? array_shift($images) : array();
			$v['url'] = C('config.site_url') . '/shop/' . $v['store_id'] . '.html';
			$v['comment'] = stripslashes($v['comment']);
			if($v['pic']){
				$tmp_array = explode(',',$v['pic']);
				$v['pic_count'] = count($tmp_array);
			}
		}
		return $order_list;
	}
	
	public function order_count($data = array())
	{
		$where = '1';
		if (isset($data['store_id']) && $data['store_id']) {
			$where .= " AND o.store_id={$data['store_id']}";
		}
		if (isset($data['begin_time']) && $data['begin_time'] && isset($data['end_time']) && $data['end_time']) {
			$where .= " AND o.pay_time>'{$data['begin_time']}' AND o.pay_time<'{$data['end_time']}'";
		}
		$sql = "SELECT d.*, sum(d.num) as total FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON d.order_id=o.order_id WHERE {$where} AND o.paid=1 AND (o.status<2 OR o.status>5) GROUP BY d.goods_id, d.spec_id";
		$res = $this->query($sql);
		$list = array();
		foreach ($res as $r) {
			if (isset($list[$r['goods_id']])) {
				$list[$r['goods_id']]['count'] += $r['total'];
				$list[$r['goods_id']]['row'] += 1;
				$list[$r['goods_id']]['list'][] = $r;
			} else {
				$list[$r['goods_id']] = array('count' => $r['total'], 'name' => $r['name'], 'row' => 1, 'list' => array($r));
			}
		}
		return $list;
	}
	
// 	public function cancel()
// 	{
// 		$cancel_time = 60 * C('config.shop_order_cancel_time');
// 		$time = time();
// 		$sql = "SELECT o.* FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS s ON s.store_id=o.store_id WHERE o.paid=1 AND o.status=0 AND ((o.cancel_time>0 AND {$time}-o.create_time>o.cancel_time*60) OR (o.cancel_time=0 AND {$time}-o.create_time>{$cancel_time}))";
// 		$res = $this->query($sql);
// 		foreach ($res as $row) {
// 			$this->where(array('order_id' => $row['order_id']))->save(array('status' => 5));
// 			D('Shop_order_log')->add_log(array('order_id' => $row['order_id'], 'status' => 10, 'name' => '店员接单超时系统自动取消', 'phone' => ''));
// 		}
// 	}
	
	public function save_order($data)
	{
		if($_SESSION['now_village_bind']['village_id']>0&&M('House_village_meal')->where(array('village_id'=>$_SESSION['now_village_bind']['village_id'],'store_id'=>$data['store_id']))->find()){
			$data['village_id'] = $_SESSION['now_village_bind']['village_id'];
		}
		/** 20180507 平台给商家限定订单数的折扣，下单时就增加折扣订单数目，没有做此单没有支付或者其他原因回滚折扣订单数 */
		$data['plat_discount'] = 0;
		$plat_discount = D('Merchant')->getDiscount($data['mer_id']);
		$plat_discount = floatval($plat_discount);
		if ($plat_discount) {
		    $data['plat_discount'] = $plat_discount;
		}
		return $this->add($data);
	}


	//修改订单状态
	public function change_status($order_id, $status)
	{
		$where['order_id'] = $order_id;
		$data['status'] = $status;
		if ($this->where($where)->data($data)->save()) {
			return true;
		} else {
			return false;
		}
	}
	public function order_notice($order_id, $menu = array())
	{
	    $printHaddle = new PrintHaddle();
	    if ($menu) {
	        $printHaddle->foodshop_menu($order_id, $menu, 0);
	    } else {
	        $printHaddle->printit($order_id, 'foodshop_order', 0);
	    }
	}
	
	//到店付回调餐饮订单状态
	public function store_order_after_call($store_order){
		$now_food_order = $this->where(array('order_id'=>$store_order['business_id']))->find();
		$this->after_pay($store_order['business_id'], null, 1);
		$this->where(array('order_id'=>$store_order['business_id']))->data(array('price'=>$store_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1'))->save();
	}
}
?>
