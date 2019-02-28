<?php
class PickAction extends BaseAction 
{
	protected $_pick_address;
	
	public function _initialize()
	{
		parent::_initialize();
		
		$this->_pick_address = session('pick');
		
		if (ACTION_NAME != 'login') {
			if (empty($this->_pick_address)) {
				redirect(U('Pick/login'));
				exit();
			} else {
				$this->_pick_address = D('Pick_address')->field(true)->where(array('id' => $this->_pick_address['id']))->find();
				if(empty($this->_pick_address)){
					$this->error('自提点不存在！');
				}
				$this->assign('pick_address', $this->_pick_address);
			}
		} elseif ($this->_pick_address && ACTION_NAME != 'logout') {
			redirect(U('Pick/index'));
		}
	}
	public function login()
	{
		if (IS_POST) {
			if(md5($_POST['verify']) != $_SESSION['merchant_pick_login_verify']){
				exit(json_encode(array('error'=>'1','msg'=>'验证码不正确！','dom_id'=>'verify')));
			}
			$pwd = htmlspecialchars($_POST['pwd']);
			$now_pick = D('Pick_address')->field(true)->where(array('pwd' => $pwd))->find();
			if (empty($now_pick)) {
				exit(json_encode(array('error'=>'2','msg'=>'不存在的登录秘钥！','dom_id'=>'account')));
			}
// 			$pwd = md5($_POST['pwd']);
// 			if($pwd != $now_staff['password']){
// 				exit(json_encode(array('error'=>'3','msg'=>'密码错误！','dom_id'=>'pwd')));
// 			}
			$data['id'] = $now_pick['id'];
			$data['last_time'] = $_SERVER['REQUEST_TIME'];
			if (D('Pick_address')->data($data)->save()) {
				session('pick', $now_pick);
				exit(json_encode(array('error'=>'0','msg'=>'登录成功,现在跳转~','dom_id'=>'pwd')));
			} else {
				exit(json_encode(array('error'=>'6','msg'=>'登录信息保存失败,请重试！','dom_id'=>'pwd')));
			}
		} else {
			$this->display();
		}
	}

	public function logout()
	{
		session('pick',null);
		redirect(U('Pick/login'));
	}
	
    public function index()
    {
        $pick_order = D('Pick_order')->field(true)->where(array('pick_id' => $this->_pick_address['id'], 'type' => 0))->select();
		$where= array('p.pick_id' => $this->_pick_address['id'], 'p.type' => 0);
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['s.real_orderid'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['s.orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['s.order_id'] = $tmp_result['order_id'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['s.username'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['s.userphone'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'third_id') {
				$where['s.third_id'] =$_GET['keyword'];
			}
		}

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] = "(o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}

        $count = D('Pick_order')->join('AS p INNER JOIN ' . C('DB_PREFIX').'shop_order AS s  ON p.order_id=s.order_id' )->where($where)->count();

        import('@.ORG.merchant_page');
        $p = new Page($count, 20);

//		$sql = "SELECT s.*, p.status as pstatus FROM " . C('DB_PREFIX') . "shop_order AS s INNER JOIN " . C('DB_PREFIX') . "pick_order AS p ON p.order_id=s.order_id WHERE p.type=0 AND p.pick_id={$this->_pick_address['id']} ORDER BY p.pid DESC LIMIT {$p->firstRow}, {$p->listRows}";
//		$mode = new Model();
//		$res = $mode->query($sql);
		$res = D('Pick_order')->field('s.*, p.status as pstatus')->join('AS p INNER JOIN ' . C('DB_PREFIX').'shop_order AS s  ON p.order_id=s.order_id' )->where($where)->limit($p->firstRow,$p->listRows)->select();
		foreach ($res as &$order) {
//			$order['offline_price'] = (floor($order['price'] * 100) - floor($order['card_price'] * 100) - floor($order['merchant_balance'] * 100) - floor($order['balance_pay'] * 100) - floor($order['payment_money'] * 100) - floor($order['score_deducte'] * 100) - floor($order['coupon_price'] * 100))/100;
			$order['offline_price'] = round($order['price'] - round($order['card_price'] + $order['merchant_balance'] + $order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);

			$order['pay_price'] = (floor($order['card_price'] * 100) + floor($order['merchant_balance'] * 100) + floor($order['balance_pay'] * 100) + floor($order['payment_money'] * 100) + floor($order['score_deducte'] * 100) + floor($order['coupon_price'] * 100))/100;
		}
        $this->assign('pagebar', $p->show());
        $this->assign('order_list', $res);
        $this->display();
    }
	
	public function order_detail()
	{
// 		echo "<pre/>";
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'mer_id' => $this->_pick_address['mer_id']));
// 		print_r($order);
// 		die;
		$store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
		$this->assign('store', $store);
		$this->assign('order', $order);
		$this->display();
	}
	
	//接货
	public function apick()
	{
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		if (!$this->check_order($order_id)) {
			exit(json_encode(array('status' => 0, 'info' => '不存在的订单！')));
		}
		$database = D('Shop_order');
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			exit(json_encode(array('status' => 0, 'info' => '订单不存在！')));
		}
		if ($order['status'] == 4 || $order['status'] == 5) exit(json_encode(array('status' => 0, 'info' => '订单已取消，不能接货！')));
		if ($order['paid'] == 0) exit(json_encode(array('status' => 0, 'info' => '订单未支付，不能接货！')));
		
		if (D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 9))) {//接货
			D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 2));
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 13, 'name' => $this->_pick_address['pick_addr'], 'phone' => $this->_pick_address['phone']));//接货
			exit(json_encode(array('status' => 1, 'info' => '已接货', 'type' => $order['is_pick_in_store'])));
		} else {
			exit(json_encode(array('status' => 0, 'info' => '接货失败，稍后重试！')));
		}
	}
	
	//发货
	public function spick()
	{
		$order_id = $condition['order_id'] = intval($_REQUEST['order_id']);
		if (!$this->check_order($order_id)) {
			$this->error('不存在的订单！');
		}

		$database = D('Shop_order');
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->error('订单不存在！');
		}
		if ($order['status'] == 4 || $order['status'] == 5) $this->error('订单已取消，不能发货！');
		if ($order['paid'] == 0) $this->error('订单未支付，不能发货！');
		if ($order['status'] != 9) $this->error('还未接货，不能发货！');
		$uid = isset($_POST['uid']) ? intval($_POST['uid']) : 0;
		
		if ($user = D('Deliver_user')->where(array('uid' => $uid))->find()) {
			if (empty($user['status'])) {
				$this->error("账号已禁止，不能分配");
			}
		}
		$data['status'] = 10;
		$data['order_status'] = 1;
		if($database->where($condition)->save($data)){
			D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 3));
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 14, 'name' => $this->_pick_address['pick_addr'], 'phone' => $this->_pick_address['phone']));
			if ($order['is_pick_in_store'] != 2) {
			    
			    $store = M('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
			    $store['adress'] = $this->_pick_address['pick_addr'];
			    $store['long'] = $this->_pick_address['long'];
			    $store['lat'] = $this->_pick_address['lat'];
			    
			    $result = D('Deliver_supply')->saveOrder($order_id, $store);
			    if ($result['error_code']) {
			        D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save(array('status' => 0, 'last_time' => time()));
			        $this->error($result['msg']);
			        exit;
			    }
			    
// 				$deliverCondition['store_id'] = $order['store_id'];
// 				$deliverCondition['mer_id'] = $order['mer_id'];
					
				// 商家是否接入配送
// 				if ($deliver = D('Deliver_store')->where($deliverCondition)->find()) {
// 					$old = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
// 					if (empty($old)) {
// // 						$deliverType = $order['is_pick_in_store'];
// 						// 订单的配送地址
// // 						$address_id = $order['address_id'];
// // 						$address_info = D('User_adress')->where(array('adress_id' => $address_id))->find();
// 						$supply['order_id'] = $order_id;
// 						$supply['paid'] = $order['paid'];
// 						$supply['pay_type'] = $order['pay_type'];
// 						$supply['money'] = $order['price'];
// 						$supply['deliver_cash'] = round($order['price'] - round($order['card_price'] + $order['merchant_balance'] + $order['card_give_money'] +$order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);
// 						$supply['store_id'] = $order['store_id'];
// 						$supply['store_name'] = $this->_pick_address['pick_addr'];
// 						$supply['mer_id'] = $order['mer_id'];
// 						$supply['from_site'] = $this->_pick_address['pick_addr'];
// 						$supply['from_lnt'] = $this->_pick_address['long'];
// 						$supply['from_lat'] = $this->_pick_address['lat'];
							
// // 						if ($address_info) {
// // 							$supply['aim_site'] =  $address_info['adress'].' '.$address_info['detail'];
// // 							$supply['aim_lnt'] = $address_info['longitude'];
// // 							$supply['aim_lat'] = $address_info['latitude'];
// // 							$supply['name']  = $address_info['name'];
// // 							$supply['phone'] = $address_info['phone'];
// // 						}

// 						//目的地
// 						$supply['aim_site'] =  $order['address'];
// 						$supply['aim_lnt'] = $order['lng'];
// 						$supply['aim_lat'] = $order['lat'];
// 						$supply['name']  = $order['username'];
// 						$supply['phone'] = $order['userphone'];

// 						$supply['status'] =  1;
// 						$supply['type'] = $order['is_pick_in_store'];
// 						$supply['item'] = 2;//0:老快店的外卖，1：外送系统，2：新快店
// 						$supply['create_time'] = $_SERVER['REQUEST_TIME'];
// 						//$supply['start_time'] = $_SERVER['REQUEST_TIME'];
// 						$supply['appoint_time'] = $order['expect_use_time'];
// 						$supply['note'] = $order['desc'];

// 						$supply['order_time'] = $order['pay_time'];
// 						$supply['freight_charge'] = $order['freight_charge'];
// 						$supply['distance'] = round(getDistance($order['lat'], $order['lng'], $this->_pick_address['lat'], $this->_pick_address['long'])/1000, 2);
						
						
// // 						$supply['status'] =  1;
// // 						$supply['type'] = $deliverType;
// // 						$supply['item'] = 2;//0:老快店的外卖，1：外送系统，2：新快店
// // 						$supply['create_time'] = $_SERVER['REQUEST_TIME'];
// // 						$supply['start_time'] = $_SERVER['REQUEST_TIME'];
// // 						$supply['appoint_time'] = $order['expect_use_time'];
// 						if ($supply_id = D('Deliver_supply')->add($supply)) {
// 							if ($user) {
// 								$columns['uid'] = $uid;
// 								$columns['status'] = 2;
// 								$result = D('Deliver_supply')->where(array("supply_id" => $supply_id, 'status' => 1))->data(array('uid' => $uid, 'status' => 2))->save();
// 								$deliver_info = serialize(array('uid' => $user['uid'], 'name' => $user['name'], 'phone' => $user['phone'], 'store_id' => $user['store_id']));
// 								$result = D("Shop_order")->where(array('order_id'=>$order_id))->data(array('order_status' => 2, 'deliver_info' => $deliver_info))->save();
// 								D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 3, 'name' => $user['name'], 'phone' => $user['phone']));
// 							}
// 						} else {
// 							$this->error('发货失败');
// 						}
// 					}
// 				}
			}
		} else {
			$this->error('发货失败');
		}
		$this->success('已发货', U('Pick/deliver_user', array('uid' => $uid, 'order_id' => $order_id)));
	}

	private function check_order($order_id)
	{
		$order = D('Pick_order')->where(array('order_id' => $order_id, 'pick_id' => $this->_pick_address['id'], 'type' => 0))->find();
		if (empty($order)) return false;
		return $order;
	}
	
	public function deliver_user()
	{
		$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = $this->check_order($order_id);
		if (!$order) $this->error('不存在的订单！');
		
		$database = D('Shop_order');
		$shop_order = $database->field(true)->where(array('order_id' => $order['order_id']))->find();
		if(empty($shop_order)){
			$this->error('订单不存在！');
		}
		if ($shop_order['is_pick_in_store'] == 1) {
			$users = D('Deliver_user')->where(array('store_id' => $shop_order['store_id'], 'status' => 1))->select();
			$user = D('User_adress')->where(array('adress_id' => $shop_order['address_id'], 'uid' => $shop_order['uid']))->find();
			foreach ($users as &$v) {
				$v['range'] = getRange(getDistance($v['lat'], $v['lng'], $user['latitude'], $user['longitude']));
			}
		}
		$this->assign('uid', $uid);
		$this->assign('order', $order);
		$this->assign('user_list', $users);
		$this->display();
	}
	
	//确认收货
	public function check()
	{
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		if (!$this->check_order($order_id)) {
			$this->error('不存在的订单！');
		}
		
		$database = D('Shop_order');
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->error('订单不存在！');
		}
		if ($order['status'] == 4 || $order['status'] == 5) $this->error('订单已取消，不能确认收货！');
		if ($order['paid'] == 0) $this->error('订单未支付，不能确认收货！');
		if ($order['status'] != 9) $this->error('还未接货，不能确认收货！');
		
		
		
		//配送状态更改成已完成，订单状态改成已消费
		$data = array('order_status' => 5, 'status' => 2);
		if ($order['paid'] == 0) {
			$data['paid'] = 1;
			if (empty($order['pay_type']) && empty($order['pay_time'])) $data['pay_type'] = 'offline';
		}
		if (empty($order['pay_time'])) $data['pay_time'] = time();
		if (empty($order['use_time'])) $data['use_time'] = time();
		if (empty($order['third_id'])) $data['third_id'] = $order['order_id'];
		if ($result = D("Shop_order")->where(array('order_id' => $order_id))->data($data)->save()) {
			D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 4));
			$this->shop_notice($order);
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 15, 'name' => $this->_pick_address['pick_addr'], 'phone' => $this->_pick_address['phone']));
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 6, 'name' => $this->_pick_address['pick_addr'], 'phone' => $this->_pick_address['phone']));
			$this->success('已完成');
		} else {
			$this->error("失败");
		}
		
	}


	private function shop_notice($order)
	{
		if ($store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find()) {
			//验证增加商家余额
			$order['order_type']='shop';
			//D('Merchant_money_list')->add_money($store['mer_id'],'用户购买快店订单记入收入',$order);
			$order['desc']='用户购买快店订单记入收入';
			D('SystemBill')->bill_method($order['is_own'],$order);

			//商家推广分佣
			$now_user = M('User')->where(array('uid' => $order['uid']))->find();
			D('Merchant_spread')->add_spread_list($order, $now_user, 'shop', $now_user['nickname'] . '用户购买快店商品获得佣金');

			//积分
			if($this->config['add_score_by_percent']==0 && ($this->config['open_score_discount']==0 || $order['score_discount_type']!=2)){
				 $now_merchant = D('Merchant')->get_info($store['mer_id']);
	            if($now_merchant['score_get_percent']>=0){
	                $this->config['score_get'] = $now_merchant['score_get_percent']/100;
	            }

	            if($order['is_own'] && C('config.user_own_pay_get_score')!=1){
					$order['payment_money']= 0;
				}	

				if($this->config['shop_goods_score_edit'] == 1){
					$score_get = D('Percent_rate')->shop_get_score($order);
				}else{
					
					$score_get  = round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']);
				}
				D('User')->add_score($order['uid'], $score_get, '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);
				D('Scroll_msg')->add_msg('shop',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). $store['name'] . ' 中消费获得'.$this->config['score_name']);

			}
			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name'].'');
		
			//短信
			$sms_data = array('mer_id' => $store['mer_id'], 'store_id' => $store['store_id'], 'type' => 'shop');
			if ($this->config['sms_finish_order'] == 1 || $this->config['sms_finish_order'] == 3) {
				if (empty($order['phone'])) {
					$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
					$order['phone'] = $user['phone'];
				}
				$sms_data['uid'] = $order['uid'];
				$sms_data['mobile'] = $order['userphone'];
				$sms_data['sendto'] = 'user';
				$sms_data['content'] = '您在 ' . $store['name'] . '店中下的订单(订单号：' . $order['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
				Sms::sendSms($sms_data);
			}
			if ($this->config['sms_finish_order'] == 2 || $this->config['sms_finish_order'] == 3) {
				$sms_data['uid'] = 0;
				$sms_data['mobile'] = $store['phone'];
				$sms_data['sendto'] = 'merchant';
				$sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['order_id'] . '),已经完成了消费！';
				Sms::sendSms($sms_data);
			}
		
			//小票打印 主打印
			$printHaddle = new PrintHaddle();
			$printHaddle->printit($order['order_id'], 'shop_order', 2);
			
// 			$msg = ArrayToStr::array_to_str($order['order_id'], 'shop_order');
// 			$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
// 			$op->printit($store['mer_id'], $order['store_id'], $msg, 2);
		
// 			//分单打印
// 			$str_format = ArrayToStr::print_format($order['order_id'], 'shop_order');
// 			foreach ($str_format as $print_id => $print_msg) {
// 				$print_id && $op->printit($store['mer_id'], $order['store_id'], $print_msg, 2, $print_id);
// 			}
		}
	}
}