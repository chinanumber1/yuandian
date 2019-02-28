<?php
class Group_orderModel extends Model{
	public $status_list = array('-1' => '全部', 0 => '未消费', 1 => '已消费', 2 => '已完成', 3 => '已退款', 4 => '已取消', 6 => '部分退款',8=>'未支付');

	/**获取订单分类**/
	public function get_order_cate($order_id){
		//order表 到 group表 到 category表
		$group_id = $this->field('group_id')->where(array('order_id'=>$order_id))->find();
		$cat_id = D('Group')->field('cat_fid')->where(array('group_id'=>$group_id['group_id']))->find();
		$group_cate = D('Group_category')->field('cat_id,cat_name')->where(array('cat_id'=>$cat_id['cat_fid']))->find();
		return $group_cate;
	}
	public function get_coupon_info($order_id){
		$now_order = $this->where(array('order'=>$order_id))->find();
		if(!empty($now_order['card_id'])){
			$now_card = D('Member_card_coupon')->get_coupon_info($now_order['card_id']);
			$now_order['coupon_type'] = 'mer';
			$now_order['coupon_price'] = $now_card['price'];
		}
		if(!empty($now_order['coupon_id'])){
			$now_coupon = D('System_coupon')->get_coupon_info($now_order['coupon_id']);
			$now_order['coupon_type'] = 'system';
			$now_order['coupon_price'] = $now_coupon['price'];
		}
		return $now_order['coupon_price'];
	}


	public function save_post_form($group,$uid,$order_id=0){
		$data_group_order['group_id'] = $group['group_id'];
		$data_group_order['mer_id'] = $group['mer_id'];
		$data_group_order['num'] = intval($_POST['quantity']);
		if(cookie('visit_village_id')!=''&&M('House_village_group')->where(array('village_id'=>cookie('visit_village_id'),'group_id'=>$group['group_id']))->find()){
			$data_group_order['village_id'] = $_SESSION['now_village_bind']['village_id'];
		}
		if(empty($data_group_order['num'])){
			return array('error'=>1,'msg'=>'请输入正确的购买数量！');
		}else if($data_group_order['num'] < $group['once_min']){
			return array('error'=>1,'msg'=>'您最少需要购买'.$group['once_min'].'单！');
		}else if($group['once_max'] != 0 && $data_group_order['num'] > $group['once_max']){
			return array('error'=>1,'msg'=>'您最多只能购买'.$group['once_min'].'单！');
		}
		//2015-11-19
		$check_result = $this->check_buy_num($uid, $group['group_id'], $data_group_order['num']);
		if ($check_result['errcode']) {
			return array('error'=>1,'msg'=>$check_result['msg']);
		}

		$data_group_order['order_name'] = $group['s_name'].'*'.$data_group_order['num'];
		//1 为单独购买 3 为发起团购 2 为参团
		if($_POST['group_type']==1){
			$data_group_order['price'] = $group['level_price']>0 ? $group['level_price']: $group['old_price'];
			$data_group_order['single_buy']=1;

		}elseif($_POST['group_type']==3){
			$data_group_order['price'] = $group['price']; //团长按团长折扣计算

			$start_res = D('Group_start')->start_group($uid,$group);
			$data_group_order['is_head'] = $start_res['msg'];//是否是团长
			if($start_res['error_code']){
				return array('error'=>1,'msg'=>$start_res['msg']);
			}
		}else{
			$data_group_order['price'] = $group['price'];
		}

		if($group['extra_pay_price']>0){
			$data_group_order['extra_price'] =$_POST['group_type']==1?$group['extra_pay_old_price']*$data_group_order['num'] :$group['extra_pay_price']*$data_group_order['num'];
		}

		if($group['trade_type'] == 'hotel'){
			$hotel_list = D('Trade_hotel_category')->get_cat_price($group['mer_id'],$_POST['cat-id'],$_POST['dep-time'],$_POST['end-time']);
			if($hotel_list['err_code']){
				return array('error'=>1,'msg'=>$hotel_list['err_msg']);
			}
			if($hotel_list['stock'] < $data_group_order['num']){
				return array('error'=>1,'msg'=>'房间数没达到您选择的房间数，请重新选择');
			}

			$trade_info_arr = array(
				'type'=>'hotel',
				'dep_time'=>$_POST['dep-time'],
				'end_time'=>$_POST['end-time'],
				'cat_id'=>$_POST['cat-id'],
				'num'=>$data_group_order['num']
			);
			$data_group_order['price'] = 0;
			foreach($hotel_list['stock_list'] as $value){
				if($_POST['quantity']>=$hotel_list['discount_room']&&$hotel_list['discount_room']>0){
					$trade_info_arr['price_list'][$value['day']] = $value['discount_price'];
				}else{
					$trade_info_arr['price_list'][$value['day']] = $value['price'];
				}
				$data_group_order['price'] += $trade_info_arr['price_list'][$value['day']];
			}

			$data_group_order['trade_info'] = serialize($trade_info_arr);
			$data_group_order['store_id'] = $group['store_list'][0]['store_id'];
		}


		$data_group_order['group_type'] = $_POST['group_type'];
		$group['vip_discount_money'] && $data_group_order['vip_discount_money'] = $group['vip_discount_money'] * $data_group_order['num'];
		$data_group_order['total_money'] = $data_group_order['price'] * $data_group_order['num'];
		$data_group_order['tuan_type'] = $group['tuan_type'];
		$data_group_order['add_time'] = $_SERVER['REQUEST_TIME'];
		$orderid = date('ymdhis').substr(microtime(),2,8-strlen($uid)).$uid;//real_orderid
		$data_group_order['real_orderid'] = $orderid;
		//实物

		if($group['tuan_type']==2  && $group['trade_type']!='hotel'){
			if(!empty($_POST['pick_in_store'])){
				if($_POST['pick_addr_id']){
					$pick_list = D('Pick_address')->get_pick_addr_by_merid($group['mer_id']);

					if(!empty($_POST['pick_in_store'])){
						foreach($pick_list as $k=>$v){
							if($v['pick_addr_id']==$_POST['pick_addr_id']){
								$pick_address = $v;
								break;
							}
						}
					}
					$data_group_order['is_pick_in_store'] = 1;
					$data_group_order['phone'] = $pick_address['phone'];
					$data_group_order['adress'] = $pick_address['area_info']['province'].$pick_address['area_info']['city']
							.$pick_address['area_info']['area'].$pick_address['name'];

				}else if(!empty($_POST['pick_address'])){
					$data_group_order['is_pick_in_store'] = 1;
					$data_group_order['adress'] = $_POST['pick_address'];
					$data_group_order['pick_lng'] = $_POST['pick_lng'] ? $_POST['pick_lng'] : 0;
					$data_group_order['pick_lat'] = $_POST['pick_lat'] ? $_POST['pick_lat'] : 0;
					$user = D('User')->get_user($uid);
					$data_group_order['phone'] = $user['phone'];

				}else{
					return array('error'=>1,'msg'=>'自取地址为空不能使用，请选择自取地址！');
				}
			}else{
				$now_adress = D('User_adress')->get_one_adress($uid,$_POST['adress_id']);
				if(empty($now_adress)){
					return array('error'=>1,'msg'=>'请先添加收货地址！');
				}
				$data_group_order['contact_name'] = $now_adress['name'];
				$data_group_order['phone'] = $now_adress['phone'];
				$data_group_order['zipcode'] = $now_adress['zipcode'];
				$data_group_order['adress'] = $now_adress['province_txt'].' '.$now_adress['city_txt'].' '.$now_adress['area_txt'].' '.$now_adress['adress'].' '.$now_adress['detail'];
				$_POST['delivery_type'] && $data_group_order['delivery_type'] = $_POST['delivery_type'];
				$_POST['delivery_comment'] && $data_group_order['delivery_comment'] = $_POST['delivery_comment'];
			}
		}else{
			$now_user = D('User')->get_user($uid);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'未获取到您的帐号信息，请重试！');
			}
			$data_group_order['phone'] = $now_user['phone'];
			if($_POST['delivery_comment']){
				$data_group_order['delivery_comment'] = $_POST['delivery_comment'];
			}
		}

		if($group['tuan_type'] == 2){
			$group['user_adress'] = D('User_adress')->get_one_adress($uid,intval($_POST['adress_id']));

			/*运费计算*/
			if($group['user_adress'] && $_POST['pick_in_store']==0){
				$express_fee = D('Group')->get_express_fee($group['group_id'],$data_group_order['total_money'],$group['user_adress']);
				$data_group_order['express_fee'] =$express_fee['freight'];
				$group['express_template'] = $express_fee;
				$data_group_order['total_money'] += $data_group_order['express_fee'];
			}

		}

		if($order_id){
			$condition_group_order['order_id'] = $order_id;
			$condition_group_order['uid'] = $uid;
			$save_result = $this->where($condition_group_order)->data($data_group_order)->save();
			if($save_result){
				return array('error'=>0,'msg'=>'订单修改成功！','order_id'=>$order_id);
			}else{
				return array('error'=>1,'msg'=>'订单修改失败！请重试','order_id'=>$order_id);
			}
		}else{
			$data_group_order['uid'] = $uid;
			$Group_store=D('Group_store')->where(array('group_id'=>$group['group_id']))->select();
			if(!empty($Group_store) && (count($Group_store)==1) && C('config.group_to_store')==1 || $group['tuan_type']==2){
				/****当此团购为实物且只指定一个店铺时，将店铺id直接带入保存到订单里*************/
				$data_group_order['store_id']=$Group_store['0']['store_id'];
			}
			$data_group_order['stock_reduce_method'] = $group['stock_reduce_method'];

			if(!empty($_SESSION['gid'])){
				$data_group_order['pin_fid'] = $_SESSION['gid'];
			}

			if(!empty($_SESSION['fid'])){
				$fid_group_id = M('Group_order')->where(array('order_id'=>$_SESSION['fid']))->find();
				if($group['group_id']!=$fid_group_id['group_id']){
					unset($_SESSION['fid']);
				}
			}


			$order_id = $this->data($data_group_order)->add();


			if(!empty($_SESSION['fid'])||$group['group_share_num']>0){
				//$res = M('Group_share_relation')->where(array('fid'=>$_SESSION['fid'],'uid'=>$uid))->find();
//				if(empty($res)){

				$date['fid'] = empty($_SESSION['fid'])?$order_id:$_SESSION['fid'];
				$date['uid'] = $uid;
				$date['order_id'] = $order_id;
				unset($_SESSION['fid']);
				if(!M('Group_share_relation')->data($date)->add()){
					return array('error'=>1,'msg'=>'参加组团购失败！','order_id'=>$order_id);
				}
			}
			if($order_id){
				//下单成功减库存
				if ($group['stock_reduce_method']) {
					$sale_count = $data_group_order['num'] + $group['sale_count'];
					$update_group_data = array('sale_count' => $sale_count);
// 					if ($group['count_num'] > 0 && $sale_count >= $group['count_num']) {//更改结束状态
// 						$update_group_data['type'] = 3;
// 					}
					D('Group')->where(array('group_id' => $group['group_id'], 'mer_id' => $group['mer_id']))->save($update_group_data);
				}

				if ($_SESSION['user']['openid']) {
					$href = C('config.site_url').'/wap.php?c=My&a=group_order&order_id='.$order_id;
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $_SESSION['user']['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $orderid, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $data_group_order['order_name'], 'remark' => '您的该次'.C('config.group_alias_name').'已生成，点击查看订单详情！'), $data_group_order['mer_id']);
				}
				$sms_data = array('mer_id' => $group['mer_id'], 'store_id' => 0, 'type' => 'group');
				if (C('config.sms_group_place_order') == 1 || C('config.sms_group_place_order') == 3) {
					$sms_data['uid'] = $uid;
					$sms_data['mobile'] = $data_group_order['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您在' . date("Y-m-d H:i:s") . '时，购买了' . $group['s_name'] . '，已成功生产订单，订单号：' . $orderid;
					Sms::sendSms($sms_data);
				}
				if (C('config.sms_group_place_order') == 2 || C('config.sms_group_place_order') == 3) {
					$merchant = D('Merchant')->where(array('mer_id' => $group['mer_id']))->find();
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $merchant['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '有份新的' . $group['s_name'] . '被购买，订单号：' . $orderid . '请您注意查看并处理!';
					Sms::sendSms($sms_data);
				}
				if($_POST['group_type']==3){
					$notice_status = 8;
				}else if($_POST['group_type']==2){
					$notice_status = 9;
				}else{
					$notice_status = 0;
				}
				$this->group_app_notice($order_id,$notice_status);
				return array('error'=>0,'msg'=>'订单产生成功！','order_id'=>$order_id);
			}else{
				return array('error'=>1,'msg'=>'订单产生失败！请重试');
			}
		}
	}
	public function get_order_detail_by_id_and_merId($mer_id,$order_id,$is_wap=false){
		$condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'group_order'=>'o',C('DB_PREFIX').'user'=>'u');
		$condition_where = "(`o`.`order_id`='$order_id' OR `o`.`real_orderid`='$order_id') AND `o`.`group_id`=`g`.`group_id` AND `g`.`mer_id`='$mer_id' AND `o`.`uid`=`u`.`uid`";
		$now_order = $this->field('`o`.*,`g`.`s_name`,`g`.`pic`,`g`.`end_time`,`u`.`nickname`,`u`.`phone` `user_phone`')->where($condition_where)->table($condition_table)->find();
		if(!empty($now_order)){
			$group_image_class = new group_image();
			$tmp_pic_arr = explode(';',$now_order['pic']);
			$now_order['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
			$now_order['url'] = D('Group')->get_group_url($now_order['group_id'],$is_wap);

			$now_order['price'] = floatval($now_order['price']);
			$now_order['total_money'] = floatval($now_order['total_money']);
			$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'],$now_order['is_mobile_pay']);
			if($now_order['group_pass']){
				$now_order['group_pass_txt'] = preg_replace('#(\d{4})#','$1 ',$now_order['group_pass']);
			}
		}
		return $now_order;
	}
	public function get_order_detail_by_id($uid,$order_id,$is_wap=false,$check_user=true){
		$condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'group_order'=>'o');
		if($check_user){
			$condition_where = "`o`.`uid`='$uid' AND ";
		}else{
			$condition_where = '';
		}
		$condition_where .= "`o`.`order_id`='$order_id' AND `o`.`group_id`=`g`.`group_id`";
		$now_order = $this->field('`o`.*,`g`.`s_name`,`g`.`pic`,`g`.`end_time`,`g`.`reply_count`,`g`.`score_all`,`g`.`score_mean`')->where($condition_where)->table($condition_table)->find();

		if(!empty($now_order)){
			$group_image_class = new group_image();
			$tmp_pic_arr = explode(';',$now_order['pic']);
			$now_order['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
			$now_order['url'] = D('Group')->get_group_url($now_order['group_id'],$is_wap);
			$now_order['order_url'] = $this->get_order_url($now_order['group_id'],$is_wap);

			$now_order['price'] = floatval($now_order['price']);
			$now_order['total_money'] = floatval($now_order['total_money']);
			$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'],$now_order['is_mobile_pay']);

			if($now_order['pay_type_txt']!='' && $now_order['is_own']>0){
				$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
				if($now_order['is_own']==2){
					$now_order['pay_type_txt'] .= '(平台子商家：'.$now_merchant['name'].')';
				}else{
					$now_order['pay_type_txt'] .= '(商家：'.$now_merchant['name'].')';
				}
			}
			if($now_order['group_pass']){
				$now_order['group_pass_txt'] = preg_replace('#(\d{4})#','$1 ',$now_order['group_pass']);
			}
			if($now_order['express_type']){
				$now_order['express_info'] = D('Express')->get_express($now_order['express_type']);
			}
		}

		return $now_order;
	}
	public function get_order_by_id($uid,$order_id){
		$condition_group_order['order_id'] = $order_id;
		$condition_group_order['uid'] = $uid;
		$condition_group_order['status'] = array('lt', 3);
		return $this->field(true)->where($condition_group_order)->find();
	}
	public function get_pay_order($uid,$order_id,$is_web=false){
		$now_order = $this->get_order_by_id($uid,$order_id);
		$now_order_img = $this->get_order_detail_by_id($uid,$order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单已失效或不存在！');
		}
		if(!empty($now_order['paid'])){
			unset($_SESSION['group_order']);
			if($is_web){
				return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>U('User/Index/group_order_view',array('order_id'=>$now_order['order_id'])));
			}else{
				return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>str_replace('/source/','/',U('My/group_order',array('order_id'=>$now_order['order_id']))));
			}
		}
		if (empty($now_order['stock_reduce_method'])) {//支付成功后减库存支付的时候验证库存量
    		$check_result = $this->check_buy_num($uid, $now_order['group_id'], $now_order['num']);
    		if ($check_result['errcode']) {
    			return array('error' => 1,'msg'=> $check_result['msg']);
    		}
		}
		$now_group = D('Group')->get_group_by_groupId($now_order['group_id']);
		if(empty($now_group)){
			return array('error'=>1,'msg'=>'当前'.C('config.group_alias_name').'不存在或已过期！');
		}
		//2015-11-19 检查数量 TODO
		if($is_web){
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'mer_id'			=>	$now_order['mer_id'],
					'store_id'			=>	$now_order['store_id'],
					'group_id'			=>	$now_order['group_id'],
					'order_type'		=>	'group',
					'group_share_num' =>  $now_group['group_share_num'],
					'order_name'		=>	$now_group['s_name'],
					'order_num'			=>	$now_order['num'],
					'pin_num'			=>	$now_group['pin_num'],
					'is_head'			=>	$now_order['is_head'],
					'order_total_money'	=>	floatval($now_order['total_money']),
					'extra_price' =>$now_order['extra_price'],
					'extra_pay_price' =>$now_group['extra_pay_price'],
					'order_content'    =>  array(
							0=>array(
									'name'  		=> $now_group['merchant_name'].'：'.$now_group['group_name'],
									'num'   		=> $now_order['num'],
									'price' 		=> floatval($now_order['price']),
									'money' 	=> floatval($now_order['num']*$now_order['price']),
									'extra_price' =>$now_group['extra_pay_price'],
							)
					)
			);
		}else{
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'group_id'			=>	$now_order['group_id'],
					'mer_id'			=>	$now_order['mer_id'],
					'store_id'			=>	$now_order['store_id'],
					'order_type'		=>	'group',
					'group_share_num' =>$now_group['group_share_num'],
				//'order_txt_type'		=>	$now_group['s_name'],
					'order_name'		=>	$now_group['s_name'],
					'order_num'			=>	$now_order['num'],
					'pin_num'			=>	$now_group['pin_num'],
					'is_head'			=>	$now_order['is_head'],
					'order_price'		=>	floatval($now_order['price']),
					'order_total_money'	=>	floatval($now_order['total_money']),
					'img'               => $now_order_img['list_pic'],
					'extra_price' =>$now_order['extra_price'],
					'extra_pay_price' =>$now_group['extra_pay_price'],
					'order_txt_type' =>$now_order['num'].' x '.$now_group['price'].' 元',
			);
			if($now_group['trade_type'] == 'hotel' && $now_order['trade_info']){
				$trade_info_arr = unserialize($now_order['trade_info']);
				$tmp_dep_time = date('m月d日',strtotime($trade_info_arr['dep_time']));
				$tmp_end_time = date('m月d日',strtotime($trade_info_arr['end_time']));
				$book_day = ((strtotime($trade_info_arr['end_time'])-strtotime($trade_info_arr['dep_time']))/86400);
				$order_info['extra_pay_price'] = $now_group['extra_pay_price']*$book_day ;
				$order_info['order_name'] = $tmp_dep_time.'-'.$tmp_end_time.' 共'.$book_day.'晚';
			}
		}
		//实物
		if($now_order['tuan_type'] == 2){
			$order_info['adress'] = $now_order['contact_name'].'，'.$now_order['adress'].'，'.$now_order['zipcode'].'，'.$now_order['phone'];
			switch($now_order['delivery_type']){
				case '1':
					$order_info['delivery_type'] = '工作日、双休日与假日均可送货';
					break;
				case '2':
					$order_info['delivery_type'] = '只工作日送货';
					break;
				case '3':
					$order_info['delivery_type'] = '只双休日、假日送货';
					break;
				default:
					$order_info['delivery_type'] = '白天没人，其它时间送货';
					break;
			}
			$order_info['delivery_comment'] = $now_order['delivery_comment'];
		}
		return array('error'=>0,'order_info'=>$order_info);
	}

	//电脑站支付前订单处理
	public function web_befor_pay($order_info,$now_user){
		//判断是否需要在线支付
		if(!$order_info['use_balance']){
			$now_user['now_money']=0;
		}
		if(($now_user['now_money']+$order_info['score_deducte'] )< $order_info['order_total_money']){
			$online_pay = true;
		}else{
			$online_pay = false;
		}
		//不使用在线支付，直接使用用户余额。
		if(empty($online_pay)){
			// $money_pay_result = D('User')->user_money($now_user['uid'],$order_info['order_total_money'],'购买 '.$order_info['order_name'].'*'.$order_info['order_num']);
			// if($money_pay_result['error_code']){
			// return $money_pay_result;
			// }
			$order_pay['balance_pay'] = $order_info['order_total_money']-$order_info['score_deducte'];
		}else{
			if(!empty($now_user['now_money'])){
				$order_pay['balance_pay'] = $now_user['now_money'];
			}
		}

		//将已支付用户余额等信息记录到订单信息里
		if(!empty($order_pay['balance_pay'])){
			$data_group_order['balance_pay'] = $order_pay['balance_pay'];
		}
		//扣除积分并保存订单
		if(!empty($order_info['score_deducte'])){
			$data_group_order['score_used_count']	= $order_info['score_used_count'];
			$data_group_order['score_deducte']      = (float)$order_info['score_deducte'];
		}

		if(!empty($data_group_order)){
			$data_group_order['wx_cheap'] 			= 0;
			$data_group_order['card_id'] 			= 0;
			$data_group_order['merchant_balance'] 	= 0;
			$data_group_order['last_time'] = $_SERVER['REQUEST_TIME'];
			$condition_group_order['order_id'] = $order_info['order_id'];
			if(!$this->where($condition_group_order)->data($data_group_order)->save()){

				return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
		}
		if($online_pay){
			return array('error_code'=>false,'pay_money'=>$order_info['order_total_money'] - $now_user['now_money']-(float)$order_info['score_deducte']);
		}else{
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
			return array('error_code'=>false,'msg'=>'支付成功！','url'=>U('User/Index/group_order_view',array('order_id'=>$order_info['order_id'])));
		}
	}
	//手机端支付前订单处理
	public function wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user,$wx_cheap){
		//去除微信优惠的金额
		$pay_money  = $order_info['order_total_money'];

		if($merchant_balance['card_discount']>0){
			$pay_money = sprintf("%.2f",$pay_money*$merchant_balance['card_discount']/10);
			//$pay_money = floor($pay_money*$merchant_balance['card_discount']*10)/100;
		}
		$pay_money = $pay_money - $wx_cheap;
		if(C('config.open_extra_price')==1){
			$user_score_use_percent=(float)C('config.user_score_use_percent');
			$order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
			$pay_money +=$order_info['order_extra_price'];
		}
		//dump($order_info);die;
		//去掉折扣

		if($pay_money<=0){
			$order_result = $this->wap_pay_save_order($order_info);
			if ($order_result['error_code']) {
				return $order_result;
			}
			return $this->wap_after_pay_before($order_info);
		}
		$data_group_order['wx_cheap'] = $wx_cheap;
		$data_group_order['card_discount'] = $merchant_balance['card_discount'];
		//判断优惠券

		if($now_coupon['card_price']>0) {
			$data_group_order['card_id'] = $now_coupon['merc_id'];
			$data_group_order['card_price'] = $now_coupon['card_price'];
			if ($now_coupon['card_price'] >= $pay_money) {
				$order_result = $this->wap_pay_save_order($order_info, $data_group_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['card_price'];
			$data_group_order['pay_money'] = $pay_money;
		}

		//系统优惠券
		if($now_coupon['coupon_price']>0){
			$data_group_order['coupon_id'] = $now_coupon['sysc_id'];
			$data_group_order['coupon_price'] = $now_coupon['coupon_price'];
			if ($now_coupon['coupon_price'] >= $pay_money) {
				$order_result = $this->wap_pay_save_order($order_info, $data_group_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['coupon_price'];
			$data_group_order['pay_money'] = $pay_money;
		}
		// 使用积分
		if(!empty($order_info['score_deducte'])&&$order_info['use_score']){
			$data_group_order['score_used_count']  = $order_info['score_used_count'];
			$data_group_order['score_deducte']     = (float)$order_info['score_deducte'];
			if($order_info['score_deducte'] >= $pay_money){
				//扣除积分
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				$order_info['pay_money'] = $pay_money;
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $order_info['score_deducte'];
			//$data_group_order['system_pay'] += $data_group_order['score_deducte'];
		}

		//判断商家余额
		if(!empty($merchant_balance['card_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_money'] >= $pay_money){
				$data_group_order['merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_group_order['merchant_balance'] = $merchant_balance['card_money'];
			}
			$pay_money -= $merchant_balance['card_money'];
		}

		if(!empty($merchant_balance['card_give_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_give_money'] >= $pay_money){
				$data_group_order['card_give_money'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_group_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_group_order['card_give_money'] = $merchant_balance['card_give_money'];
			}
			$pay_money -= $merchant_balance['card_give_money'];
		}


		//判断帐户余额
		if(!empty($now_user['now_money'])&&$order_info['use_balance']){
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
	public function wap_pay_save_order($order_info,$data_group_order=array()){
		$data_group_order['wx_cheap'] 			= !empty($data_group_order['wx_cheap']) ? $data_group_order['wx_cheap'] : 0;
		$data_group_order['coupon_id'] 			= !empty($data_group_order['coupon_id']) ? $data_group_order['coupon_id'] : 0;
		$data_group_order['coupon_price'] 		= !empty($data_group_order['coupon_price']) ? $data_group_order['coupon_price'] : 0;
		$data_group_order['card_id'] 			= !empty($data_group_order['card_id']) ? $data_group_order['card_id'] : 0;
		$data_group_order['card_price'] 		= !empty($data_group_order['card_price']) ? $data_group_order['card_price'] : 0;
		$data_group_order['merchant_balance'] 	= !empty($data_group_order['merchant_balance']) ? $data_group_order['merchant_balance'] : 0;
		$data_group_order['card_give_money'] 	= !empty($data_group_order['card_give_money']) ? $data_group_order['card_give_money'] : 0;
		$data_group_order['card_discount'] 	= !empty($data_group_order['card_discount']) ? $data_group_order['card_discount'] : 0;
		$data_group_order['balance_pay']	 	= !empty($data_group_order['balance_pay']) ? $data_group_order['balance_pay'] : 0;
		$data_group_order['score_used_count']   = !empty($data_group_order['score_used_count']) ? $data_group_order['score_used_count'] : 0;
		$data_group_order['score_deducte']      = !empty($data_group_order['score_deducte']) ? $data_group_order['score_deducte'] : 0;
		$data_group_order['score_discount_type']      = !empty($order_info['score_discount_type']) ? $order_info['score_discount_type'] : 0;
		$data_group_order['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_group_order['submit_order_time'] = $_SERVER['REQUEST_TIME'];
		$condition_group_order['order_id'] = $order_info['order_id'];
		if($this->where($condition_group_order)->data($data_group_order)->save()){
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
				'is_mobile' => $order_info['is_mobile'],
		);
		$result_after_pay = $this->after_pay($order_param);
		if($result_after_pay['error']){
			return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
		}
		return array('error_code'=>false,'msg'=>'支付成功！','url'=>str_replace('/source/','/',U('My/group_order',array('order_id'=>$order_info['order_id']))));
	}
	//支付前订单处理
	public function befor_pay($order_info,$now_coupon,$now_user){
		//判断是否需要在线支付
		if($now_coupon['price']+$now_user['now_money'] < $order_info['order_total_money']){
			$online_pay = true;
		}else{
			$online_pay = false;
		}
		//不使用在线支付，直接使用会员卡和用户余额。
		if(empty($online_pay)){
			if(!empty($now_coupon)){
				$coupon_pay_result = D('Member_card_coupon')->user_card($now_coupon['record_id'],$order_info['mer_id'],$now_user['uid']);
				if($coupon_pay_result['error_code']){
					return $coupon_pay_result;
				}
				$order_pay['car_id'] = $now_coupon['record_id'];
			}
			if(!empty($now_user['now_money']) && $now_coupon['price'] < $order_info['order_total_money']){
				$money_pay_result = D('User')->user_money($now_user['uid'],$order_info['order_total_money']-$now_coupon['price']);
				if($money_pay_result['error_code']){
					return $money_pay_result;
				}
				$order_pay['balance_pay'] = $now_user['now_money'];
			}
		}else{
			//校验会员卡
			if(!empty($now_coupon)){
				$coupon_pay_result = D('Member_card_coupon')->check_card($now_coupon['record_id'],$order_info['mer_id'],$now_user['uid']);
				if($coupon_pay_result['error_code']){
					return $coupon_pay_result;
				}
				$order_pay['car_id'] = $now_coupon['record_id'];
			}
			if(!empty($now_user['now_money'])){
				$order_pay['balance_pay'] = $now_user['now_money'];
			}
		}

		//将会员卡ID，已支付用户余额等信息记录到订单信息里
		if(!empty($order_pay['car_id'])){
			$data_group_order['card_id'] = $order_pay['record_id'];
		}
		if(!empty($order_pay['balance_pay'])){
			$data_group_order['balance_pay'] = $order_pay['record_id'];
		}
		if(!empty($data_group_order)){
			$data_group_order['last_time'] = $_SERVER['REQUEST_TIME'];
			$condition_group_order['order_id'] = $now_order['order_id'];
			if(!$this->where($condition_group_order)->data($data_group_order)->save()){
				return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
		}

		if($online_pay){
			return array('error_code'=>false,'pay_money'=>$order_info['order_total_money'] - $now_coupon['price'] - $now_user['now_money']);
		}else{
			$order_param = array(
					'order_id' => $order_info['order_id'],
					'pay_type' => '',
					'third_id' => '',
					'is_mobile' => 0,
					'pay_money' => 0,
					'order_type' => 'group',
			);
			$result_after_pay = $this->after_pay($order_param);
			if($result_after_pay['error']){
				return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
			}
			return array('error_code'=>false,'url'=>U('My/group_order',array('order_id'=>$order_info['order_id'])));
		}
	}


	//支付之后
	public function after_pay($order_param){
		unset($_SESSION['group_order']);
		if($order_param['pay_type']!=''){
			$condition_group_order['orderid'] = $order_param['order_id'];
		}else{
			$condition_group_order['order_id'] = $order_param['order_id'];
		}
		$now_order = $this->field(true)->where($condition_group_order)->find();

		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}else if($now_order['paid'] == 1){
			if($order_param['is_mobile']){
				return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('My/group_order',array('order_id'=>$now_order['order_id'])));
			}else{
				return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('User/Index/group_order_view',array('order_id'=>$now_order['order_id'])));
			}
		}else{
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}
			if($now_order['status']==3){
				return array('error'=>1,'msg'=>'订单支付失败');
			}
            if (empty($now_order['stock_reduce_method'])) {//支付成功后减库存支付的时候验证库存量
                $check_result = $this->check_buy_num($now_order['uid'], $now_order['group_id'], $now_order['num']);
                if ($check_result['errcode']) {
                    return $this->wap_after_pay_error($now_order, $order_param, $check_result['msg']);
                }
            }
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


			//如果用户使用了积分抵扣，则扣除相应的积分
			//判断积分数量是否正确
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
				$use_result = D('User')->user_money($now_order['uid'],$balance_pay,'购买 '.$now_order['order_name'].' 扣除余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			if($now_order['tuan_type'] < 2){
				$group_pass_array = array(
						date('y',$_SERVER['REQUEST_TIME']),
						date('m',$_SERVER['REQUEST_TIME']),
						date('d',$_SERVER['REQUEST_TIME']),
						date('H',$_SERVER['REQUEST_TIME']),
						date('i',$_SERVER['REQUEST_TIME']),
						date('s',$_SERVER['REQUEST_TIME']),
						mt_rand(10,99),
				);
				shuffle($group_pass_array);

				$data_group_order['group_pass'] = implode('',$group_pass_array);
				$fisrt_group_pass =  $data_group_order['group_pass'];
				$group_pass_arr[$data_group_order['group_pass']] =0;
				$date_group_pass_array[]=array('order_id'=>$now_order['order_id'],'group_pass'=>$data_group_order['group_pass'],'status'=>0);
				if($now_order['num']>1){
					for($i=0;$i<$now_order['num']-1;$i++){
						$group_pass_array = array(
								date('y',$_SERVER['REQUEST_TIME']),
								date('m',$_SERVER['REQUEST_TIME']),
								date('d',$_SERVER['REQUEST_TIME']),
								date('H',$_SERVER['REQUEST_TIME']),
								date('i',$_SERVER['REQUEST_TIME']),
								date('s',$_SERVER['REQUEST_TIME']),
								mt_rand(10,99),
						);
						shuffle($group_pass_array);
						$tmp = implode('',$group_pass_array);
						$date_group_pass_array[]=array('order_id'=>$now_order['order_id'],'group_pass'=>$tmp,'status'=>0);
					}
					//$data_group_order['group_pass_array'] = serialize($group_pass_arr);
					if(!M('Group_pass_relation')->addAll($date_group_pass_array)){
						return array('error'=>1,'msg'=>'保存消费码失败！');
					}
					$data_group_order['pass_array'] = 1;
				}
			}

			//file_put_contents('log.txt',var_export($fisrt_group_pass,true));
			$data_group_order['pay_time'] = isset($order_param['pay_time']) && $order_param['pay_time'] ? strtotime($order_param['pay_time']) : $_SERVER['REQUEST_TIME'];
			$data_group_order['payment_money'] = floatval($order_param['pay_money']);
			$data_group_order['pay_type'] = $order_param['pay_type'];
			$data_group_order['third_id'] = $order_param['third_id'];
			$data_group_order['is_mobile_pay'] = $order_param['is_mobile'];
			$data_group_order['is_own'] = isset($order_param['sub_mch_id'])?2:$order_param['is_own'];
			$data_group_order['paid'] = 1;
			$data_group_order['status'] = 0;

			if($this->where($condition_group_order)->data($data_group_order)->save()){
				$condition_group['group_id'] = $now_order['group_id'];

				D('Scroll_msg')->add_msg('group',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.$now_order['order_name'].'成功');

				//2015-11-19     更改结束状态
				//D('Group')->where($condition_group)->setInc('sale_count',$now_order['num']);
				if (empty($now_order['stock_reduce_method'])) {
					$update_group = D('Group')->where($condition_group)->find();
					$sale_count = $now_order['num'] + $update_group['sale_count'];
					$update_group_data = array('sale_count' => $sale_count);
					if ($update_group['count_num'] > 0 && $sale_count >= $update_group['count_num']) {//更改结束状态
						$update_group_data['type'] = 3;
					}
					D('Group')->where($condition_group)->save($update_group_data);
				}
				//D('User')->add_score($now_order['uid'],floor($now_order['total_money']*C('config.user_score_get')),'购买 '.$now_order['order_name'].' 消费'.floatval($now_order['total_money']).'元 获得积分');

				/* 粉丝行为分析 */
				D('Merchant_request')->add_request($now_order['mer_id'],array('group_buy_count'=>$now_order['num'],'group_buy_money'=>$now_order['total_money']));

				$this->group_app_notice($now_order['order_id'],1);


				$spread_total_money = $balance_pay + $data_group_order['payment_money'];

				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				/* 计算用户分享获得佣金 */
				if(!empty($now_user['openid'])&&C('config.open_user_spread') && (C('config.spread_money_limit')==0 || C('config.spread_money_limit')<=$spread_total_money)){
					//上级分享佣金
					$spread_rate = D('Percent_rate')->get_user_spread_rate($now_order['mer_id'],'group',$now_order['group_id']);
					$spread_users[]=$now_user['uid'];
					if($now_user['wxapp_openid']!=''){
						$spread_where['_string'] = "openid = '{$now_user['openid']}' OR openid = '{$now_user['wxapp_openid']}' ";
					}else{
						$spread_where['_string'] = "openid = '{$now_user['openid']}'";
					}
					$now_user_spread = D('User_spread')->field('`spread_openid`, `openid`,`is_wxapp`')->where($spread_where)->find();
					$href = C('config.site_url') . '/wap.php?g=Wap&c=My&a=spread_list&status=-1';
					$open_extra_price = C('config.open_extra_price');
					if($data_group_order['is_own']  && C('config.own_pay_spread')==0){
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
							$spread_money = round(($balance_pay + $data_group_order['payment_money']) * $user_spread_rate / 100, 2);

							$spread_data = array('uid'=>$spread_user['uid'],'spread_uid'=>0,'get_uid'=>$now_user['uid'],'money'=>$spread_money,'order_type'=>'group','order_id'=>$now_order['order_id'],'third_id'=>$now_order['group_id'],'add_time'=>$_SERVER['REQUEST_TIME']);
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
								$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $spread_user['openid'], 'first' => $buy_user['nickname'] . '通过您的分享购买了【' . $now_order['order_name'] . '】，验证消费后您将获得'.$money_name.'。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
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
								$spread_money = round(($balance_pay + $data_group_order['payment_money']) * $sub_user_spread_rate / 100, 2);

								$spread_data =array('uid' => $second_user['uid'], 'spread_uid' => $spread_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => 'group', 'order_id' => $now_order['order_id'], 'third_id' => $now_order['group_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
								if($spread_user['spread_change_uid']!=0){
									$spread_data['change_uid'] = 	$second_user['spread_change_uid'];
								}
								D('User_spread_list')->data($spread_data)->add();
								$sec_user = D('User')->get_user($second_user_spread['openid'], 'openid');
								if($spread_money>0) {
									$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $second_user['openid'], 'first' => $sec_user['nickname'] .'的子用户'.$buy_user['nickname'] . '通过您的分享购买了【' . $now_order['order_name'] . '】，验证消费后您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
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
									$spread_money = round(($balance_pay + $data_group_order['payment_money']) * $sub_user_spread_rate / 100, 2);

									$spread_data =array('uid' => $first_spread_user['uid'], 'spread_uid' => $second_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => 'group', 'order_id' => $now_order['order_id'], 'third_id' => $now_order['group_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
									if($spread_user['spread_change_uid']!=0){
										$spread_data['change_uid'] = 	$first_spread_user['spread_change_uid'];
									}

									D('User_spread_list')->data($spread_data)->add();

									$fir_user = D('User')->get_user($first_user_spread['openid'], 'openid');
									if($spread_money>0) {
										$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $first_spread_user['openid'], 'first' =>$fir_user['nickname'].'的子用户的子用户'.$buy_user['nickname'] . '通过您的分享购买了【' . $now_order['order_name'] . '】，验证消费后您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'), $now_order['mer_id']);
									}
									// D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
								}
							}

						}
					}
				}

				$now_group = D('Group')->where(array('group_id'=>$now_order['group_id']))->find();
				$group_share_num = D('Group_share_relation')->get_share_num($now_order['uid'],$now_order['order_id']);

				//参团 单独购买不产生团购小组
				if($now_group['pin_num']>0 && !$now_order['single_buy']){
					if($now_order['is_head']){
						D('Group_start')->add_buyer_list($now_order['is_head'],$now_order['uid'],$now_order['order_id']);
						$date_start['num']=1;
						$date_start['status']=0;
						if($now_group['pin_num']==$date_start['num']){
							$date_start['status']=1;
						}
						M('Group_start')->where(array('id'=>$now_order['is_head']))->save($date_start);

					}elseif(!empty($now_order['pin_fid'])){
						D('Group_start')->add_group($now_order['group_id'],$now_order['uid'],$now_group,$now_order['order_id'],$now_order['pin_fid']);
					}else{
						D('Group_start')->add_group($now_order['group_id'],$now_order['uid'],$now_group,$now_order['order_id']);
					}
					$this->group_app_notice($now_order['order_id'],3);
				}

				//微信派发优惠券 支付到平台 微信支付
				if($data_group_order['is_own']==0 &&  $data_group_order['pay_type']=='weixin' && $data_group_order['payment_money']>=C('config.weixin_send_money')){
					D('System_coupon')->weixin_send( $data_group_order['payment_money'],$now_order['uid']);
				}

				//D('Action_relation')->add_user_action($order_param['order_id'],'group');

				if(C('config.open_extra_price')==1&&$now_order['extra_price']>0){
					$now_order['total_money']=($now_order['balance_pay']+$now_order['merchant_balance']+$order_param['pay_money']).'+'.$now_order['score_used_count'].C('config.score_name');
				}

				//增加粉丝 5 group 6 shop 7 meal 8 appoint 9 store
				D('Merchant')->saverelation($now_user['openid'], $now_order['mer_id'], 5);

				if ($now_user['openid'] && $order_param['is_mobile']) {
					$href = C('config.site_url').'/wap.php?c=My&a=group_order&order_id='.$now_order['order_id'];
					if($now_order['tuan_type'] < 2&&$now_group['pin_num']==0){
						if($now_group['open_num']==0&&$now_group['open_now_num']==0&&$now_group['group_share_num']==0){
							$remark = C('config.group_alias_name').'成功，您的消费码：'.$data_group_order['group_pass'];
						}else{
							if($now_group['group_share_num']!=0&&$now_group['group_share_num']>$group_share_num){
								$remark = C('config.group_alias_name').'成功，还差'.($now_group['group_share_num']-$group_share_num).'份才能成团，快分享给好友吧，成团后消费码将在订单详情中显示';
							}else if($now_group['open_now_num']>$now_group['sale_count']&&$now_group['open_now_num']!=0&&$now_group['group_share_num']==0){
								$remark = C('config.group_alias_name').'成功，还差'.($now_group['open_now_num']-$now_group['sale_count']).'份才能成团，快分享给好友吧，成团后消费码将在订单详情中显示';
							}else if($now_group['open_num']>$now_group['sale_count']&&$now_group['open_num']!=0&&$now_group['open_now_num']==0&&$now_group['group_share_num']==0){
								$remark = C('config.group_alias_name').'成功，还差'.($now_group['open_num']-$now_group['sale_count']).'份才能成团，快分享给好友吧，成团后消费码将在订单详情中显示';
							}else{
								$remark = C('config.group_alias_name').'成功，您的消费码：'.$fisrt_group_pass;
							}
						}
						$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => C('config.group_alias_name').'提醒', 'keyword1' => $now_order['order_name'], 'keyword2' => $now_order['real_orderid'], 'keyword3' => $now_order['total_money'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => $remark), $now_order['mer_id']);
					} else {
						$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => C('config.group_alias_name').'提醒', 'keyword1' => $now_order['order_name'], 'keyword2' => $now_order['real_orderid'], 'keyword3' => $now_order['total_money'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => C('config.group_alias_name').'成功，感谢您的使用'), $now_order['mer_id']);
					}
				}

				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => 0, 'type' => 'group');
				if (C('config.sms_group_success_order') == 1 || C('config.sms_group_success_order') == 3) {
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_order['phone'];
					$sms_data['sendto'] = 'user';

					if ($data_group_order['group_pass']&&$now_group['pin_num']==0) {
						if($now_group['open_num']==0&&$now_group['open_now_num']==0&&$now_group['group_share_num']==0&&$now_group['pin_num']==0){
							$remark = '您的消费码：'.$data_group_order['group_pass'];
						}else{
							if($now_group['group_share_num']!=0&&$now_group['group_share_num']>$group_share_num){
								$remark = '还差'.($now_group['group_share_num']-$group_share_num).'份才能成团,快分享给好友吧,成团后消费码将在订单详情中显示';
							}else if($now_group['open_now_num']>$now_group['sale_count']&&$now_group['open_now_num']!=0&&$now_group['group_share_num']==0){
								$remark = '还差'.($now_group['open_now_num']-$now_group['sale_count']).'份才能成团,快分享给好友吧,成团后消费码将在订单详情中显示';
							}else if($now_group['open_num']>$now_group['sale_count']&&$now_group['open_num']!=0&&$now_group['open_now_num']==0&&$now_group['group_share_num']==0){
								$remark = '还差'.($now_group['open_num']-$now_group['get_order_detail_by_id_and_merId']).'份才能成团,快分享给好友吧,成团后消费码将在订单详情中显示';
							}else{
								$remark = C('config.group_alias_name').'成功，您的消费码：'.$data_group_order['group_pass'];
							}
						}
						$sms_data['content'] = '您购买 '.$now_order['order_name'].'的订单(订单号：' . $now_order['real_orderid'] . ')已经完成支付,' . $remark;
					} else {
						$sms_data['content'] = '您购买 '.$now_order['order_name'].'的订单(订单号：' . $now_order['real_orderid'] . ')已经完成支付!';
					}

					Sms::sendSms($sms_data);
				}
				if (C('config.sms_group_success_order') == 2 || C('config.sms_group_success_order') == 3) {
					$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $merchant['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客购买的' . $now_order['order_name'] . '的订单(订单号：' . $now_order['real_orderid'] . '),在' . date('Y-m-d H:i:s') . '时已经完成了支付！';
					Sms::sendSms($sms_data);
				}
				// dump($now_order);
				if($now_order['trade_info']){
					$trade_info_arr = unserialize($now_order['trade_info']);
					// dump($trade_info_arr);
					if($now_order['stock_reduce_method'] == 0){
						D('Trade_hotel_category')->change_cat_stock($now_order['mer_id'],$trade_info_arr['cat_id'],$trade_info_arr['dep_time'],$trade_info_arr['end_time'],$trade_info_arr['num']);
					}
				}

				//店员APP推送
				if($now_order['store_id']){
					D('Merchant_store_staff')->sendMsgGroupOrder($now_order);
				}

				$orderprint = D('Orderprinter')->field(true)->where(array('store_id' => $now_order['store_id']))->find();

				if(false!==strpos($orderprint['paid'],'1')){
					$printHaddle = new PrintHaddle();
					$printHaddle->printit($now_order['order_id'], 'group_order', 2);
				}

				if($order_param['is_mobile']){
					return array('error'=>0,'url'=>str_replace('/source/','/',U('My/group_order',array('order_id'=>$now_order['order_id']))));
				}else{
					return array('error'=>0,'url'=>U('User/Index/group_order_view',array('order_id'=>$now_order['order_id'])));
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
		$this->where(array('order_id'=>$now_order['order_id']))->setField('status',3);
		if($order_param['pay_type']=='weixin'&&ACTION_NAME=='return_url'){
			exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
		}
		if($user_result['error_code']){
			return array('error'=>1,'msg'=>$user_result['msg']);
		}else{
			if($order_param['is_mobile']){
				$return_url = str_replace('/source/','/',U('My/group_order',array('order_id'=>$now_order['order_id'])));
			}else{
				$return_url = U('User/Index/group_order_view',array('order_id'=>$now_order['order_id']));
			}
			return array('error'=>1,'msg'=>$error_tips.'，已将您充值的金额添加到您的余额内。','url'=>$return_url);
		}
	}
	//修改订单状态
	public function change_status($order_id,$status){
		$condition_group_order['order_id'] = $order_id;
		$data_group_order['status'] = $status;
		if($this->where($condition_group_order)->data($data_group_order)->save()){
			return true;
		}else{
			return false;
		}
	}
	/*获得订单链接*/
	public function get_order_url($order_id,$is_wap=false){
		if($is_wap){
			return U('My/group_order',array('order_id'=>$order_id));
		}else{
			return U('User/Index/group_order_view',array('order_id'=>$order_id));
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
		$total = $this->where("`paid`=1 AND `add_time`>'$stime' AND `add_time`<'$etime'")->count();
		return $total;
	}


	/**
	 * @param int $uid
	 * @param int $group_id
	 * @param int $num
	 * @return array
	 */
	public function check_buy_num($uid, $group_id, $num)
	{
		$count = $this->where("group_id='{$group_id}' AND uid='{$uid}' AND paid=1 AND status<3")->sum('num');
		$group = D('Group')->get_group_by_groupId($group_id);

		$max_num = $group['once_max'];//一次购买最多的数量

		$min_num = $group['once_min'];//一次最少数量

		//商品有库存限制的时候
		if ($group['count_num'] > 0) {
			$k_num = $group['count_num'] - $group['sale_count']; //实际库存

			if ($k_num < $min_num) {//库存等于销售量的时候不能买
				return array('errcode' => 1, 'msg' => '该商品已售空');
			} else {
				if ($max_num > 0) {
					$my_num = $max_num - $count;//我的剩余得到份数
					$my_num = $my_num > $k_num ? $k_num : $my_num;
					$my_num = $my_num > $max_num ? $max_num : $my_num;

					if ($my_num < $num) {
						if ($my_num > 0) {
							return array('errcode' => 1, 'msg' => '您最多能购买' . $my_num . '份');
						} else {
							return array('errcode' => 1, 'msg' => '该商品限制单人只能购买' . $max_num . '份，您购买的数量已达上限，不能再购买!');
						}
					} elseif ($num < $min_num) {
						return array('errcode' => 1, 'msg' => '您一次最少要购买' . $min_num . '份');
					}
				} else {
					if ($num > $k_num) {
						return array('errcode' => 1, 'msg' => '您最多能购买' . $k_num . '份');
					}
				}
			}
		} else {
			if ($max_num > 0) {
				$my_num = $max_num - $count;//我的剩余得到份数
				$my_num = $my_num > $max_num ? $max_num : $my_num;
				if ($my_num < $num) {
					if ($my_num > 0) {
						return array('errcode' => 1, 'msg' => '您最多能购买' . $my_num . '份');
					} else {
						return array('errcode' => 1, 'msg' => '此商品每个用户只能购买' . $max_num . '份');
					}
				} elseif ($num < $min_num) {
					return array('errcode' => 1, 'msg' => '您一次最少要购买' . $min_num . '份');
				}
			}
		}

		if ($num < $min_num) {
			return array('errcode' => 1, 'msg' => '您一次最少要购买' . $min_num . '份');
		}
		
		//每ID每天限购
		if($group['once_max_day']){
			$now_user_today_count = $this->get_once_max_day($group_id,$uid);
			$today_can_buy = $group['once_max_day'] - $now_user_today_count;
			if($today_can_buy <= 0 || $today_can_buy < $num){
				return array('errcode' => 1, 'msg' => '该商品限制单人每天只能购买' . $group['once_max_day'] . '份，您当天购买的数量已达上限，不能再购买!');
			}
		}
		
		return array('errcode' => 0);
	}
	/*
    * @param  string  $client       推送平台设置  1苹果 2安卓 3苹果、安卓
    * @param  array   $audience     推送设备指定  all全部用户 "android", "ios", "winphone"。
    * @param  array   $notification 通知内容体   “通知”对象，是一条推送的实体内容对象之一（另一个是“消息”），是会作为“通知”推送到客户端的。
    * @param  array   $message      消息内容体   应用内消息。或者称作：自定义消息，透传消息。
    * @return boolean $audience     推送的用户   all全部用户
    */
	public  function    AuroraMass($client=3, $title, $msg, $extra=array(),$audience='all'){
		switch($client){
			case 1:
				$platform   =   array('ios');break;
			case 2:
				$platform   =   array('android');break;
			default:
				$platform   =   'all';
		}
		import('@.ORG.Jpush');
		$jpush = new Jpush(C('config.weixin_push_jpush_appkey'), C('config.weixin_push_jpush_secret'));
		$notification = $jpush->createBody($client, $title, $msg, $extra);
		$message      = $jpush->createMsg($title, $msg, $extra);
		return($jpush->send($platform, $audience, $notification,$message));
	}

	/**
	 * 回滚团购的销量
	 */
	public function stock_rollback()
	{
		//20分钟前的订单回滚
		$nowtime = time() - 1200;
		$orders = $this->field('group_id, order_id, num')->where("`stock_reduce_method`=1 AND `paid`=0 AND `status`=0 AND `add_time`<'{$nowtime}'")->order('order_id ASC')->limit('0, 30')->select();
		$groups = array();
		foreach ($orders as $order) {
			if (isset($groups[$order['group_id']])) {
				$groups[$order['group_id']] += $order['num'];
			} else {
				$groups[$order['group_id']] = $order['num'];
			}
			$this->where(array('order_id' => $order['order_id']))->save(array('status' => 5));
//     		D('Group')->where(array('group_id' => $order['group_id']))->setDec('sale_count', $order['num']);
		}
		foreach ($groups as $group_id => $num) {
			D('Group')->where(array('group_id' => $group_id, 'sale_count' => array('egt', $num)))->setDec('sale_count', $num);
		}
		return;
	}
	//app 极光推送 支付成功
	public function  group_app_notice($order_id ,$status=0){
		import('@.ORG.Apppush');
		$now_order = M('Group_order')->where(array('order_id'=>$order_id))->find();
		$now_order['status'] = $status;
		$apppush = new Apppush();
		$apppush->send($now_order, 'group');
	}
	//得到一个用户今天买了多少份同一个团购
	public function get_once_max_day($group_id,$uid){
		$condition_once_max_day['group_id'] = $group_id;
		$condition_once_max_day['uid'] = $uid;
		$condition_once_max_day['paid'] = '1';
		$condition_once_max_day['status'] = array('lt',3);
		$today_zero = strtotime(date('Y-m-d', time()));
		$condition_once_max_day['pay_time'] = array('between',array($today_zero,$today_zero+86400));
		$once_max_day = M('Group_order')->where($condition_once_max_day)->sum('num');
		return $once_max_day;
	}
}
?>
