<?php
class DeliverAction extends BaseAction {

	protected $item = array(
			"0" => "快店的外送",
			"1" => "外卖",
	);
	protected $deliver_supply;

	public function get_device_token(){
		$where['device_id'] =  $_POST['device_id'];
		$device_token =  $_POST['device_token'];
		M('Deliver_user')->where($where)->setField('device_token',$device_token);
	}

	public function config(){
		$config['can_register'] = true;
		$config['site_phone'] = $this->config['site_phone'];
		$config['deliver_see_detail'] = $this->config['deliver_see_detail'];
        $config['deliver_see_freight_charge'] = $this->config['deliver_see_freight_charge'];
		$config['is_packapp'] = true;
		if($this->config['map_config']=='google'){
            $config['google_map_ak'] = $this->config['google_map_ak'];
        }else{
            $config['google_map_ak'] = '-1';
        }
		$appConfig  =   D('Appapi_app_config')->field(true)->select();

		foreach($appConfig as $k=>$v){

			$appConfig[$v['var']]   =   nl2br($v['value']);

		}

		$config['deliver_android_version'] = $appConfig['deliver_android_version'];
		$config['deliver_android_vcode'] = $appConfig['deliver_android_vcode'];
		$config['deliver_android_url'] = $appConfig['deliver_android_url'];
		$config['deliver_android_vdes'] = $appConfig['deliver_android_vdes'];
		$this->returnCode('0',$config);
	}
	
	public function login(){
		$ticket = I('ticket', false);
		$client = I('client', 0);

		if ($ticket) {
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			if ($info) {
				$uid = $info['uid'];
				$now_user = D('Deliver_user')->field('uid,name,pwd,openid,phone,is_notice,status')->where(array('uid'=>$uid))->find();
				unset($now_user['pwd']);
				
				if($now_user){
					$data_deliver_user['last_time'] = $_SERVER['REQUEST_TIME'];
					$data_deliver_user['client'] = $client;
					$data_deliver_user['device_id'] = $this->DEVICE_ID;

					D('Deliver_user')->where(array('uid'=>$now_user['uid']))->data($data_deliver_user)->save();
				}
				
				$return = array(
						'ticket'    =>	$ticket,
						'user'      =>	$now_user,
				);
				$this->returnCode(0,$return);
			}else{
				$this->returnCode('20000009');
			}
		}
		$phone  = I('phone',false);
		$now_user = D('Deliver_user')->field('uid,name,pwd,openid,is_notice,phone,status')->where(array('phone'=>$phone))->find();
		if (empty($now_user)) {
			$this->returnCode('20020001');
		}
		if (empty($now_user['status'])) {
			$this->returnCode('20020003');
		}
		$pwd = md5(trim($_POST['pwd']));
		if ($pwd != $now_user['pwd']) {
			$this->returnCode('20020002');
		}
		$ticket = ticket::create($now_user['uid'], $this->DEVICE_ID, true);
		preg_match('/versionCode=(\d+)/',$_SERVER['HTTP_USER_AGENT'],$versionCode);
		if($versionCode[1]){
			$data_deliver_user['app_version'] = intval($versionCode[1]);
		}
		$data_deliver_user['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_deliver_user['device_id'] = $this->DEVICE_ID;
		$data_deliver_user['client'] = $client;
		unset($now_user['pwd']);
		if (D('Deliver_user')->where(array('uid'=>$now_user['uid']))->data($data_deliver_user)->save()) {
			$arr = array(
					'ticket'=>$ticket['ticket'],
					'user'=>$now_user,

			);
			$this->returnCode(0,$arr);
		} else {
			$this->returnCode('20020006');
		}
	}

	//配送列表
	public function deliver_list(){
		$ticket = I('ticket', false);
		if ($ticket) {
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			$this->user_session['uid'] = $info['uid'];
		}
		if(empty($this->user_session)){
			$this->returnCode('20020008');
		}else{
			$now_user = D('Deliver_user')->field(true)->where(array('uid'=>$this->user_session['uid']))->find();
			if(empty($now_user)){
				$this->returnCode('20020001');
			}
		}
		$exits_list = I('exits_list');
		if(!empty($device_deliver_list)){
			$device_deliver_list = explode(',',$device_deliver_list);

		}
		$my_lnt = I("lng");
		//$my_lnt = 117.228278;
		if (!$my_lnt) {
			$this->returnCode('20020009');
		}
		$my_lat = I("lat");
		//$my_lat = 31.822762;
//		$date['lat']=$my_lat;
//		$date['lng']=$my_lnt;
//		if(!D('Deliver_user')->where(array('uid'=>$this->user_session['uid']))->save($date)){
//			$this->returnCode('20020020');
//		}
		if (!$my_lat) {
			$this->returnCode('20020009');
		}
		$my_distance = $now_user['range'] * 1000 * 1000;
		$time = time();
		$db_array = array(
				C('DB_PREFIX')."deliver_supply"=>"d",
				C('DB_PREFIX')."waimai_order"=>"w"
		);
		$nember_6  = 6378.138*2;
		$lat_pi = $my_lat*pi()/180;
		$lnt_pi = $my_lnt*pi()/180;
//		$where = "`appoint_time`<$time AND `status`=1 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$my_lat}*PI()/180-`from_lat`*PI()/180)/2),2)+COS({$my_lat}*PI()/180)*COS(`from_lat`*PI()/180)*POW(SIN(({$my_lnt}*PI()/180-`from_lnt`*PI()/180)/2),2)))*1000) < $my_distance ";
//		$fields = "*,ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$my_lat}*PI()/180-`from_lat`*PI()/180)/2),2)+COS({$my_lat}*PI()/180)*COS(`from_lat`*PI()/180)*POW(SIN(({$my_lnt}*PI()/180-`from_lnt`*PI()/180)/2),2)))*1000) AS distance";
		$fields = "*,ROUND({$nember_6}* ASIN(SQRT(POW(SIN(({$lat_pi}-`from_lat`*PI()/180)/2),2)+COS({$lat_pi})*COS(`from_lat`*PI()/180)*POW(SIN(({$lnt_pi}-`from_lnt`*PI()/180)/2),2)))*1000) AS distance";
		if ($now_user['store_id']) {
		    $where = "`appoint_time`<$time AND `status`=1";
			$where = "`type`= 1 AND `store_id`=" . $now_user['store_id'] . " AND " . $where;
		} else {
		    $where = "`appoint_time`<$time AND `status`=1 AND ROUND({$nember_6} * ASIN(SQRT(POW(SIN(({$lat_pi}-`from_lat`*PI()/180)/2),2)+COS({$lat_pi})*COS(`from_lat`*PI()/180)*POW(SIN(({$lnt_pi}-`from_lnt`*PI()/180)/2),2)))*1000) < $my_distance ";
			$where = "`type`= 0 AND " . $where;
		}

		$list = D("Deliver_supply")->field($fields)->where($where)->order("`create_time` DESC")->select();

		foreach ($list as $key=>$val) {
			switch ($val['pay_type']) {
				case 'offline':
					$list[$key]['pay_method'] = '线下支付'; break;
				default:
					if ($val['paid']) {
						$list[$key]['pay_method'] = '在线支付'; break;
					} else {
						$list[$key]['pay_method'] = '未支付'; break;
					}
			}
			if(empty($val['supply_id'])){
				continue;
			}
			$tmp['supply_id'] = $val['supply_id'];
			$tmp['from_site'] = empty($val['from_site'])?'':$val['from_site'];
			$tmp['aim_site'] = empty($val['aim_site'])?'':$val['aim_site'];
			$tmp['name'] = empty($val['name'])?'':$val['name'];
			$tmp['status'] = empty($val['status'])?'':$val['status'];
			$tmp['store_name'] = empty($val['store_name'])?'':$val['store_name'];
			$tmp['phone'] = empty($val['phone'])?'':$val['phone'];
			$tmp['pay_method'] = $list[$key]['pay_method'];
			$supply_id[] = $tmp['supply_id'];
			$arr[]=$tmp;
		}

		if(!empty($exits_list)){
			$device_deliver_list = explode(',',$exits_list);

			foreach($arr as $k=>$v ){
				if(in_array($v['supply_id'],$device_deliver_list)){
					unset($arr[$k]);
				}
			}
			foreach($device_deliver_list as $key=>$vd){
				if(!in_array($vd,$supply_id)){
					$has_pick_id [] = $vd;
				}
			}
			if(empty($has_pick_id)){
				$return['old'] = "";
			}else{

				$return['old'] = implode(',',$has_pick_id);
			}
			$return['is_new'] = true;
		}else{
			$return['old'] = "";
			$return['is_new'] = false;
		}


		if (false === $list) {
			$this->returnCode('20020010');
		}
		if(empty($arr)){
			$arr = array();
		}
		if ($this->config['deliver_model'] == 1 && $now_user['group'] == 1) $arr = array();
		$return['deliver_list'] = $arr;
		//error_log(date("Y-m-d H:i:s=>").json_encode($list).PHP_EOL, 3, "/work/log/debug.log");
		$this->returnCode(0,$return);
	}


	public function grab(){

		$ticket = I('ticket', false);
		if ($ticket) {
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			$uid = $info['uid'];
		}
		if(empty($uid)){
			$this->returnCode('20020008');
		}else{
			$now_user = D('Deliver_user')->field(true)->where(array('uid'=>$uid))->find();
			if(empty($now_user)){
				$this->returnCode('20020001');
			}
		}
		$deliver = D('Deliver_supply');
		if ($now_user['max_num'] > 0) {
		    $count = $deliver->where('uid=' . $now_user['uid'] . ' AND status>0 AND status<5')->count();
		    if ($count >= $now_user['max_num']) {
		        $this->returnCode(1, null, '您当前已有' . $count . '单没有完成配送，请配送完成再来抢单！');
		    }
		}
		
		$supply_id = intval(I("supply_id"));
		if (! $supply_id) {
			$this->returnCode('20020011');
		}
		$columns = array();
		$columns['uid'] = $uid ;
		$columns['status'] = 2;
		$columns['start_time'] = time();

		D()->startTrans();
		$result = $deliver->lock(true)->where(array("supply_id"=>$supply_id))->find();

		if($result['status'] != 1){
			$this->returnCode('20020012');
		}
		
		$result = $deliver->where(array("supply_id"=>$supply_id, 'status'=>1))->data($columns)->save();
		if (false === $result) {
			D()->rollback();
			$this->returnCode('20020012');
		}
		//获取order_id
		$supply = $deliver->find($supply_id);
		if (!$supply['order_id']) {
			D()->rollback();
			$this->returnCode('20020013');
		}
		//获取订单信息
		$order_id = $supply['order_id'];

		if ($supply['item'] == 1) {
			$order = D("Waimai_order")->find($order_id);
			if ($order['order_status'] != 3) {
				D()->rollback();
				$this->returnCode('20020014');
			}
			//更新订单状态
			$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>8))->save();
			if (!$result) {
				D()->rollback();
				$this->returnCode('20020015');
			}
			//添加订单日志
			$log = array();
			$log['status'] = 8;
			$log['order_id'] = $order_id;
			$log['store_id'] = $order['store_id'];
			$log['uid'] = $uid;
			$log['time'] = time();
			$log['group'] = 4;
			$result = D("Waimai_order_log")->add($log);
			if (!$result) {
				$this->returnCode('20020016');
				D()->rollback();
			}
		} elseif ($supply['item'] == 0) {
			//更新订单状态
			$order = D("Meal_order")->where(array('order_id' => $order_id))->find();

			if ($order['order_status'] != 3) {
				D()->rollback();
				$this->returnCode('20020014');
			}
			//更新订单状态
			$result = D("Meal_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>8))->save();
			if (!$result) {
				D()->rollback();
				$this->returnCode('20020015');
			}
		}elseif ($supply['item'] == 2) {
			D()->commit();
			//更新订单状态
			$order = D("Shop_order")->where(array('order_id' => $order_id))->find();
			if ($order['order_status'] != 1) {
				$this->returnCode('20020014');
			}
			//更新订单状态
			$deliver_info = serialize(array('uid' => $now_user['uid'], 'name' => $now_user['name'], 'phone' => $now_user['phone'], 'store_id' => $now_user['store_id']));
			$result = D("Shop_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>2, 'deliver_info' => $deliver_info))->save();
			if (!$result) {
				$this->returnCode('20020015');
			}
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 3, 'name' => $now_user['name'], 'phone' => $now_user['phone']));
		} elseif ($supply['item'] == 3) {
		    $offer_id = D('Service_offer')->add_offer($order_id, $uid);
		    $saveData = array('offer_id' => $offer_id);
		    if ($supply['server_type'] == 2 || $supply['server_type'] == 3) {
		        $saveData['appoint_time'] = time() + 60 * $supply['server_time'];
		    }
		    
		    $deliver->where(array('supply_id' => $supply_id))->save($saveData);
		}
		D()->commit();
		$this->returnCode(0);//抢单成功
	}

	public function detial(){
		$ticket = I('ticket', false);
		if ($ticket) {
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			$this->user_session['uid'] = $info['uid'];
		}

		if(empty($this->user_session)){
			$this->returnCode('20020008');
		}else{
			$now_user = D('Deliver_user')->field(true)->where(array('uid'=>$this->user_session['uid']))->find();
			if(empty($now_user)){
				$this->returnCode('20020001');
			}
		}
		$deliver =  D('Deliver_supply');
		$supply_id = intval(I("supply_id"));
		if (! $supply_id) {
			$this->returnCode('20020011');
		}
		$uid =  $this->user_session['uid'];

		$where = array();
		if(empty($uid)){
			$where['uid'] = $uid;
		}
		$where['supply_id'] = $supply_id;
// 		$where['item'] = 1;
		$supply = D("Deliver_supply")->where($where)->find();


		if (! $supply) {
			$this->returnCode('20020018');
		}
		$arr['supply_id']=$supply['supply_id'];
		$arr['from_site']=$supply['from_site'];
		$arr['status']=$supply['status'];
		$arr['aim_site']=$supply['aim_site'];
		$arr['name']=$supply['name'];
		$arr['phone']=$supply['phone'];
		$arr['from_lat']=empty($supply['from_lat'])?0:$supply['from_lat'];
		$arr['from_lnt']=empty($supply['from_lnt'])?0:$supply['from_lnt'];
		$arr['aim_lat']=empty($supply['aim_lat'])?0:$supply['aim_lat'];
		$arr['aim_lnt']=empty($supply['aim_lnt'])?0:$supply['aim_lnt'];
		if ($supply['item'] == 1) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Waimai_order")->where($where)->find();
			if (! $order) {
				$this->returnCode('20020014');
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];

			$arr['order_discount_price'] =$order['discount_price'];
			$arr['order_code']=$order['code'];
			$arr['order_pay_type']=$order['pay_type'];

			//商品信息
			$goods = D()->field(true)->table(array(C('DB_PREFIX').'waimai_sell_log'=>'sl', C('DB_PREFIX').'waimai_goods'=>'wg'))->where("sl.order_id=".$supply['order_id']." AND sl.goods_id=wg.goods_id")->select();

			$arr['goods']=$goods;

			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'waimai_store'=>'ws'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ws.store_id")->find();
			if (! $store) {
				$this->returnCode('20020019');
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}

			$arr['store_send_money']=empty($store['send_money'])?0:$store['send_money'];
			$arr['store_name']=$store['name'];
			$arr['store_phone']=$store['phone'];
			$this->returnCode(0,$arr);

			//红包信息
//			$where = array();
//			$where['id'] = $order['coupon_id'];
//			$couponInfo = D("Waimai_user_coupon")->field(true)->where($where)->find();
//			$this->assign('couponInfo', $couponInfo);
//			//优惠信息
//			$discountInfo = json_decode($order['discount_detail'], true);
//			$this->assign('discountInfo', $discountInfo);

		} elseif ($supply['item'] == 0) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Meal_order")->where($where)->find();

			if (! $order) {
				$this->returnCode('20020014');
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];
			$order['total_price'] = $order['total_price'];

			$arr['total_price'] =$order['price']-$order['system_pay'];
			$arr['order_code']=(string)$arr['total_price'];
			if(empty($order['pay_type'])){
				if($order['balance_pay']>0) {
					$arr['order_pay_type'] = '余额支付';
				}else{
					$arr['order_pay_type'] = '未支付';
				}
			}else{
				$arr['order_pay_type']=$order['pay_type'];
			}

			$goods = unserialize($order['info']);

//			foreach ($goods as &$g) {
//				//$g['tools_money'] = 0;
//			}
			$arr['goods']=$goods;

			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_meal'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();
			if (!$store) {
				$this->returnCode('20020019');
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			if($store['tools_money_have']){
				$arr['store']['tools_money']=$store['tools_money'];
			}

			$arr['store_send_money']=0;
			$arr['tools_money']=0;
			$arr['store_name']=$store['name'];
			$arr['store_phone']=$store['phone'];
			$this->returnCode(0,$arr);
		} elseif ($supply['item'] == 2) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Shop_order")->where($where)->find();
			if (! $order) {
				$this->error_tips("订单信息有误");
			}
			$order['pay_type'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay'], $order['paid']);

			$order['discount_price'] = $order['price'];
			$arr['total_price'] =$order['total_price'];
			$arr['order_code']=(string)$arr['total_price'];
			$goods = D('Shop_order_detail')->field(true)->where(array('order_id' => $supply['order_id']))->select();
			foreach ($goods as &$g) {
				if ($g['spec']) {
					$g['name'] = $g['name'] . '(' . $g['spec'] . ')';
				}
				$g['tools_money'] = 0;
			}
			if(empty($order['pay_type'])){
				if($order['balance_pay']>0) {
					$arr['order_pay_type'] = '余额支付';
				}else{
					$arr['order_pay_type'] = '未支付';
				}
			}else{
				$arr['order_pay_type']=$order['pay_type'];
			}

			$arr['goods'] ['id']= $goods[0]['id'];
			$arr['goods'] ['name']= $goods[0]['name'];
			$arr['goods'] ['price']= $goods[0]['price'];
			$arr['goods'] ['num']= $goods[0]['num'];
			$arr['goods'] ['iscount']= 1;
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_shop'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();

			if (!$store) {
				$this->error_tips("店铺信息有误");
			}

			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			$arr['store_send_money']=0;
			$arr['tools_money']=$store['tools_money'] ;
			$arr['store_name']=$store['name'];
			$arr['store_phone']=$store['phone'];
			$this->returnCode(0,$arr);
		}
	}

	public function map() {
		$ticket = I('ticket', false);
		if ($ticket) {
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			$this->user_session['uid'] = $info['uid'];
		}
		if(empty($this->user_session)){
			$this->returnCode('20020008');
		}else{
			$now_user = D('Deliver_user')->field(true)->where(array('uid'=>$this->user_session['uid']))->find();
			if(empty($now_user)){
				$this->returnCode('20020001');
			}
		}
		$supply_id = I("supply_id", 0, 'intval');
		if (! $supply_id) {
			$this->returnCode('20020011');
		}
		$supply = D("Deliver_supply")->where(array('supply_id'=>$supply_id))->find();
		if (! $supply) {
			$this->returnCode('20020018');
		}
		$this->returnCode(0,$supply);
	}

	public function pick_list(){
		$ticket = I('ticket', false);
		if ($ticket) {
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			$this->user_session['uid'] = $info['uid'];
		}
		if(empty($this->user_session)){
			$this->returnCode('20020008');
		}else{
			$now_user = D('Deliver_user')->field(true)->where(array('uid'=>$this->user_session['uid']))->find();
			if(empty($now_user)){
				$this->returnCode('20020001');
			}
		}

		$where = array();
		$where['status'] = 2;
//     	$where['item'] = 1;
		$where['uid'] = $this->user_session['uid'];

		$list = D('Deliver_supply')->field(true)->where($where)->order("`start_time` DESC")->select();
		foreach ($list as $key=>$val) {
			switch ($val['pay_type']) {
				case 'offline':
					$list[$key]['pay_method'] = '线下支付'; break;
				default:
					if ($val['paid']) {
						$list[$key]['pay_method'] = '在线支付'; break;
					} else {
						$list[$key]['pay_method'] = '未支付'; break;
					}
			}
			if(empty($val['supply_id'])){
				continue;
			}
			$tmp['supply_id'] = $val['supply_id'];
			$tmp['from_site'] = empty($val['from_site'])?'':$val['from_site'];
			$tmp['aim_site'] = empty($val['aim_site'])?'':$val['aim_site'];
			$tmp['name'] = empty($val['name'])?'':$val['name'];
			$tmp['status'] = empty($val['status'])?'':$val['status'];
			$tmp['store_name'] = empty($val['store_name'])?'':$val['store_name'];
			$tmp['phone'] = empty($val['phone'])?'':$val['phone'];
			$tmp['pay_method'] = $list[$key]['pay_method'];
			$supply_id[] = $tmp['supply_id'];
			$arr[]=$tmp;
		}
		if (false === $list) {
			$this->returnCode('20020010');
		}
		$arr = empty($arr)?array():$arr;
		$this->returnCode(0,$arr);
	}


	public function pick(){

		$ticket = I('ticket', false);
		if ($ticket) {
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			$uid = $info['uid'];
		}
		if(empty($uid)){
			$this->returnCode('20020008');
		}else{
			$now_user = D('Deliver_user')->field(true)->where(array('uid'=>$uid))->find();
			if(empty($now_user)){
				$this->returnCode('20020001');
			}
		}

		$supply_id = intval(I("supply_id"));
		if (! $supply_id) {
			$this->returnCode('20020011');
		}
		//获取order_id
		$supply = D('Deliver_supply')->find($supply_id);
		if (!$supply['order_id']) {
			$this->returnCode('20020013');
		}
		if ($supply['status'] != 2) {
		    $this->returnCode(1, null, "当前状态不能修改成取货状态！");
		}
		$order_id = $supply['order_id'];

		D()->startTrans();
		$columns = array();
		$columns['uid'] = $uid;
		$columns['status'] = 3;
		$result = D('Deliver_supply')->where(array("supply_id"=>$supply_id, 'status'=>2))->data($columns)->save();
		if (false === $result) {
			$this->returnCode('20020022');
		}
		if ($supply['item'] == 1) {
			//获取订单信息
			$order = D("Waimai_order")->find($order_id);
			if (!$order) {
				$this->returnCode('20020014');
			}

			//更新订单状态
			$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>4))->save();
			if (!$result) {
				D()->rollback();
				$this->returnCode('20020015');
			}
			//添加订单日志
			$log = array();
			$log['status'] = 4;
			$log['order_id'] = $order_id;
			$log['store_id'] = $order['store_id'];
			$log['uid'] = $uid;
			$log['time'] = time();
			$log['group'] = 4;
			$result = D("Waimai_order_log")->add($log);
			if (!$result) {
				$this->returnCode('20020016');
				D()->rollback();
			}
		} elseif ($supply['item'] == 0) {
			//更新订单状态
			$result = D("Meal_order")->where(array('order_id' => $order_id))->data(array('order_status' => 4))->save();
			if (!$result) {
				D()->rollback();
				$this->returnCode('20020015');
			}
		}elseif ($supply['item'] == 2) {
			//更新订单状态
			$result = D("Shop_order")->where(array('order_id' => $order_id))->data(array('order_status' => 3))->save();
			if (!$result) {
				$this->returnCode('20020015');
			}
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 4, 'name' => $now_user['name'], 'phone' => $now_user['phone']));
		} elseif ($supply['item'] == 3) {
		    D('Service_offer')->offer_save_status($order_id, $uid, $supply['offer_id'], 9);
		}
		D()->commit();
		$this->returnCode(0);
	}

	public function send_list(){
		$ticket = I('ticket', false);
		if ($ticket) {
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			$this->user_session['uid'] = $info['uid'];
		}
		if(empty($this->user_session)){
			$this->returnCode('20020008');
		}else{
			$now_user = D('Deliver_user')->field(true)->where(array('uid'=>$this->user_session['uid']))->find();
			if(empty($now_user)){
				$this->returnCode('20020001');
			}
		}

		$where = array();
		$where['status'] = 3;
//     	$where['item'] = 1;
		$where['uid'] = $this->user_session['uid'];

		$list =  D('Deliver_supply')->field(true)->where($where)->order("`end_time` DESC")->select();
		if (false === $list) {
			$this->returnCode('20020010');
		}
		foreach ($list as $key=>$val) {
			switch ($val['pay_type']) {
				case 'offline':
					$list[$key]['pay_method'] = '线下支付'; break;
				default:
					if ($val['paid']) {
						$list[$key]['pay_method'] = '在线支付'; break;
					} else {
						$list[$key]['pay_method'] = '未支付'; break;
					}
			}
			if(empty($val['supply_id'])){
				continue;
			}
			$tmp['supply_id'] = $val['supply_id'];
			$tmp['from_site'] = empty($val['from_site'])?'':$val['from_site'];
			$tmp['aim_site'] = empty($val['aim_site'])?'':$val['aim_site'];
			$tmp['name'] = empty($val['name'])?'':$val['name'];
			$tmp['status'] = empty($val['status'])?'':$val['status'];
			$tmp['store_name'] = empty($val['store_name'])?'':$val['store_name'];
			$tmp['phone'] = empty($val['phone'])?'':$val['phone'];
			$tmp['pay_method'] = $list[$key]['pay_method'];
			$supply_id[] = $tmp['supply_id'];
			$arr[]=$tmp;
		}
		$arr = empty($arr)?array():$arr;
		$this->returnCode(0,$arr);
	}

	public function send(){

		$ticket = I('ticket', false);
		if ($ticket) {
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			$uid = $info['uid'];
		}
		if(empty($uid)){
			$this->returnCode('20020008');
		}else{
			$now_user = D('Deliver_user')->field(true)->where(array('uid'=>$uid))->find();
			if(empty($now_user)){
				$this->returnCode('20020001');
			}
		}

		$supply_id = intval(I("supply_id"));
		if (! $supply_id) {
			$this->returnCode('20020011');
		}
		//获取order_id
		$supply = D('Deliver_supply')->find($supply_id);
		if (!$supply['order_id']) {
			$this->returnCode('20020013');
		}
		if ($supply['status'] != 3) {
		    $this->returnCode(1, null, "当前状态不能修改成送达状态！");
		}
		$order_id = $supply['order_id'];

		D()->startTrans();
		$columns = array();
		$columns['status'] = 4;
		$columns['end_time'] = time();
		$result = D('Deliver_supply')->where(array("supply_id"=>$supply_id, 'status'=>3))->data($columns)->save();
		if (false === $result) {
			$this->returnCode('20020022');
		}
		if ($supply['item'] == 1) {
			//获取订单信息
			$order = D("Waimai_order")->find($order_id);
			if (!$order) {
				$this->returnCode('20020014');
			}

			//更新订单状态
			$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>5))->save();
			if (!$result) {
				D()->rollback();
				$this->returnCode('20020015');
			}
			//添加订单日志
			$log = array();
			$log['status'] = 5;
			$log['order_id'] = $order_id;
			$log['store_id'] = $order['store_id'];
			$log['uid'] = $uid;
			$log['time'] = time();
			$log['group'] = 4;
			$result = D("Waimai_order_log")->add($log);
			if (!$result) {
				$this->returnCode('20020016');
				D()->rollback();
			}
		} elseif ($supply['item'] == 0) {
			//更新订单状态
			$result = D("Meal_order")->where(array('order_id' => $order_id))->data(array('order_status' => 5))->save();
			if (!$result) {
				D()->rollback();
				$this->returnCode('20020015');
			}
		}elseif ($supply['item'] == 2) {

			//更新订单状态
			$result = D("Shop_order")->where(array('order_id' => $order_id))->data(array('order_status' => 4))->save();
			if (!$result) {
				$this->returnCode('20020015');
			}
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 5, 'name' => $now_user['name'], 'phone' => $now_user['phone']));

		}


		D()->commit();
		$this->returnCode(0);
}

public function my(){
	$ticket = I('ticket', false);
	if ($ticket) {
		$info = ticket::get($ticket, $this->DEVICE_ID, true);
		$this->user_session['uid'] = $info['uid'];
	}
	if(empty($this->user_session)){
		$this->returnCode('20020008');
	}else{
		$now_user = D('Deliver_user')->field(true)->where(array('uid'=>$this->user_session['uid']))->find();
		if(empty($now_user)){
			$this->returnCode('20020001');
		}
	}

	$where = array();
	$where['status'] = array('in', array(4, 5, 6));
//     	$where['item'] = 1;
	$where['uid'] = $this->user_session['uid'];
	$_GET['page']= I('page');
	import('@.ORG.common_page');
	$count  = D('Deliver_supply')->field(true)->where($where)->count();
	$p = new Page($count,10);
	$list = D('Deliver_supply')->field(true)->where($where)->order("`status` ASC,`end_time` DESC")->limit($p->firstRow,$p->listRows)->select();

	if (false === $list) {
		$this->returnCode('20020010');
	}
	foreach ($list as $key=>$val) {
		switch ($val['pay_type']) {
			case 'offline':
				$list[$key]['pay_method'] = '线下支付'; break;
			default:
				if ($val['paid']) {
					$list[$key]['pay_method'] = '在线支付'; break;
				} else {
					$list[$key]['pay_method'] = '未支付'; break;
				}
		}
		if(empty($val['supply_id'])){
			continue;
		}
		$tmp['supply_id'] = $val['supply_id'];
		$tmp['from_site'] = empty($val['from_site'])?'':$val['from_site'];
		$tmp['aim_site'] = empty($val['aim_site'])?'':$val['aim_site'];
		$tmp['name'] = empty($val['name'])?'':$val['name'];
		$tmp['status'] = empty($val['status'])?'':$val['status'];
		$tmp['store_name'] = empty($val['store_name'])?'':$val['store_name'];
		$tmp['phone'] = empty($val['phone'])?'':$val['phone'];
		$tmp['pay_method'] = $list[$key]['pay_method'];
		$supply_id[] = $tmp['supply_id'];
		$arr[]=$tmp;
	}
	$arr = empty($arr)?array():$arr;
	$return['count'] = empty($p->totalPage)?0:$p->totalPage;
	$return['lists'] = $arr;
	$this->returnCode(0,$return);
}

public function complete(){

	$ticket = I('ticket', false);
	if ($ticket) {
		$info = ticket::get($ticket, $this->DEVICE_ID, true);
		$uid = $info['uid'];
	}
	if(empty($uid)){
		$this->returnCode('20020008');
	}else{
		$now_user = D('Deliver_user')->field(true)->where(array('uid'=>$uid))->find();
		if(empty($now_user)){
			$this->returnCode('20020001');
		}
	}

	$supply_id = intval(I("supply_id"));
	if (! $supply_id) {
		$this->returnCode('20020011');
	}
	//获取order_id
	$supply = D('Deliver_supply')->find($supply_id);
	if (!$supply['order_id']) {
		$this->error("配送信息错误");
	}
	if ($supply['status'] != 4) {
	    $this->returnCode(1, null, "当前状态不能修改成完成状态！");
	}
	$order_id = $supply['order_id'];
// 	D()->startTrans();
	$columns = array();
	$columns['uid'] = $uid;
	$columns['status'] = 5;
	$columns['end_time'] = time();
	$result = D('Deliver_supply')->where(array("supply_id"=>$supply_id, 'status'=>4))->data($columns)->save();
	if (false === $result) {
		$this->returnCode('20020022');
	}
	
	
	//统计每日配送订单量
	$total = D('Deliver_supply')->where(array('uid' => $uid, 'status' => array('gt', 4)))->count();
	D('Deliver_user')->where(array('uid' => $uid))->save(array('num' => $total));
	
	$todayNum = D('Deliver_supply')->where(array('uid' => $uid, 'status' => array('gt', 4), 'end_time' => array(array('gt', strtotime(date('Y-m-d') . ' 00:00:00')), array('lt', time() + 2))))->count();
	$date = date('Ymd');
	if ($deliver_count = D('Deliver_count')->field(true)->where(array('uid' => $uid, 'today' => $date))->find()) {
	    D('Deliver_count')->where(array('uid' => $uid, 'today' => $date))->save(array('num' => $todayNum));
	} else {
	    D('Deliver_count')->add(array('uid' => $uid, 'today' => $date, 'num' => $todayNum));
	}
	
	
	if ($supply['item'] == 1) {
		//获取订单信息
		$order = D("Waimai_order")->find($order_id);
		if (!$order) {
			$this->returnCode('20020014');
		}

		//更新订单状态
		$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>1))->save();
		if (!$result) {
// 			D()->rollback();
			$this->returnCode('20020015');
		}
		//添加订单日志
		$log = array();
		$log['status'] = 1;
		$log['order_id'] = $order_id;
		$log['store_id'] = $order['store_id'];
		$log['uid'] = $uid;
		$log['time'] = time();
		$log['group'] = 4;
		$result = D("Waimai_order_log")->add($log);
		if (!$result) {
			$this->returnCode('20020016');
// 			D()->rollback();
		}
	} elseif ($supply['item'] == 0) {
		//更新订单状态//获取订单信息
		$order = D("Meal_order")->field(true)->where(array('order_id' => $order_id))->find();
		if (!$order) {
			$this->returnCode('20020014');
		}
		$data = array('order_status' => 1, 'status' => 1);
		if ($order['paid'] == 0) {
			$data['paid'] = 1;
			if (empty($data['pay_type']) && empty($data['pay_time'])) $data['pay_type'] = 'offline';
			if (empty($data['pay_time'])) $data['pay_time'] = time();
		}
		//验证商家余额增加

		$order['order_type']='meal';
		$info = unserialize($order['info']);
		$info_str = '';
		foreach($info as $v){
			$info_str.=$v['name'].':'.$v['price'].'*'.$v['num'].'</br>';
		}
		//D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$info_str.'记入收入',$order);
		$order['desc']='用户购买'.$info_str.'记入收入';
		D('SystemBill')->bill_method($order['is_own'],$order);

		$result = D("Meal_order")->where(array('order_id' => $order_id))->data($data)->save();
		if (!$result) {
// 			D()->rollback();
			$this->returnCode('20020015');
		}
	}elseif ($supply['item'] == 2) {//快店的配送
// 		D()->commit();
		if ($order = D("Shop_order")->field(true)->where(array('order_id' => $order_id))->find()) {
			//配送状态更改成已完成，订单状态改成已消费
		    if ($order['status'] != 1) {
		        $this->returnCode(1, null, '该订单的当前状态无法修改成配送完成状态');
			}
			$data = array('order_status' => 5, 'status' => 2);
			if ($order['is_pick_in_store'] == 0) {//平台配送
				if ($order['paid'] == 0 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {
					$data['paid'] = $order['paid'] == 0 ? 1 : $order['paid'];
					$data['pay_type'] = '';
					$data['balance_pay'] = $order['balance_pay'] + $supply['deliver_cash'];
					$order['balance_pay'] = $order['balance_pay'] + $supply['deliver_cash'];
				}
			} else {
				if ($order['paid'] == 0) {
					$data['paid'] = 1;
					if (empty($order['pay_type']) && empty($order['pay_time'])) $data['pay_type'] = 'offline';
				}
			}
			if (empty($order['pay_time'])) $data['pay_time'] = time();
			if (empty($order['use_time'])) $data['use_time'] = time();
			if (empty($order['third_id'])) $data['third_id'] = $order['order_id'];
			if ($result = D("Shop_order")->where(array('order_id' => $order_id, 'status' => array('neq', 2)))->data($data)->save()) {
				if ($order['is_pick_in_store'] == 0) {//平台配送
					if ($order['paid'] == 0 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {
						D('User_money_list')->add_row($order['uid'], 1, $supply['deliver_cash'], '配送员模拟手动充值');
						D('User_money_list')->add_row($order['uid'], 2, $supply['deliver_cash'], '用户购买快店产品');
					}
				}
				D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 4));
				D('Shop_order')->shop_notice($order);
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 6, 'name' => $now_user['name'], 'phone' => $now_user['phone']));
				if ($order['is_pick_in_store'] == 5) {
				    if ($order['platform'] == 1) {
				        $returnData = D('Merchant_money_list')->use_money($order['mer_id'], $order['freight_charge'], 'deliver', '平台配送商家由饿了么平台的订单的配送费', $order['order_id']);
				    } elseif ($order['platform'] == 2) {
				        $returnData = D('Merchant_money_list')->use_money($order['mer_id'], $order['freight_charge'], 'deliver', '平台配送商家由美团外卖平台的订单的配送费', $order['order_id']);
				    }
				}
			} else {
				$this->returnCode('20020015');
			}
		} else {
			$this->returnCode('20020014');
		}
	} elseif ($supply['item'] == 3) {
	    D('Service_offer')->offer_save_status($order_id, $uid, $supply['offer_id'], 4);
	}
// 	D()->commit();
	$this->returnCode(0);
}



    public function reg()
    {
        $data['name'] = $_POST['name'];
        if (empty($data['name'])) {
            $this->returnCode('20030001');
        }
        $data['phone'] = $_POST['phone'];
        if (empty($data['phone'])) {
            $this->returnCode('20030002');
        }
        if (M('Deliver_user')->where(array(
            'phone' => $data['phone']
        ))->find()) {
            $this->returnCode('20030005');
        }
        
        $data['pwd'] = $_POST['pwd'];
        if (strlen($data['pwd']) < 6) {
            $this->returnCode('20030003');
        }
        $data['pwd'] = md5($data['pwd']);
        $data['create_time'] = time();
        $data['last_time'] = time();
        $data['status'] = '0';
        $data['group'] = '1';
        if (M('Deliver_user')->data($data)->add()) {
            $this->returnCode('0');
        } else {
            $this->returnCode('20030004');
        }
    }

    protected function get_deliver_info()
    {
        $now_deliver = M('Deliver_user')->where(array('uid' => $this->_uid, 'status' => '1'))->find();
        if (empty($now_deliver)) {
            $this->returnCode('20030102');
        }
        return $now_deliver;
    }
	public function new_index(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		$now_deliver = $this->get_deliver_info();
		$return_deliver = array();
		if(empty($_POST['poll'])){
			$return_deliver['map_lng'] = $now_deliver['lng'];
			$return_deliver['map_lat'] = $now_deliver['lat'];
			
			$return_deliver['name'] = $now_deliver['name'];
			if ($now_deliver['store_id']) {
				$store = D('Merchant_store')->field(true)->where(array('store_id' => $now_deliver['store_id']))->find();
				$store_image_class = new store_image();
				$images = $store_image_class->get_allImage_by_path($store['pic_info']);

				$return_deliver['store_image'] = $images ? array_shift($images) : '';
				$return_deliver['is_system'] = false;
				$return_deliver['store_name'] = $store['name'];
				
			}else{
				$return_deliver['system_image'] = $this->config['wechat_share_img'] ? $this->config['wechat_share_img'] : $this->config['site_logo'];
				$return_deliver['is_system'] = true;
			}
		}


		
		$my_distance = $now_deliver['range'] * 1000;
		$time = time();
		if ($now_deliver['group'] == 2 && $now_deliver['store_id']) {
		    $where = "`create_time`<$time AND `status`=1";
			$where = "`type`= 1 AND `store_id`=" . $now_deliver['store_id'] . " AND " . $where;
		} else {
		    $where = "`create_time`<$time AND `status`=1 ";
		    if ($now_deliver['delivery_range_type'] == 0) {
		        $where .= " AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$now_deliver['lat']}*PI()/180-`from_lat`*PI()/180)/2),2)+COS({$now_deliver['lat']}*PI()/180)*COS(`from_lat`*PI()/180)*POW(SIN(({$now_deliver['lng']}*PI()/180-`from_lnt`*PI()/180)/2),2)))*1000) < $my_distance ";
		    } else {
		        $where .= " AND MBRContains(PolygonFromText('{$now_deliver['delivery_range_polygon']}'),PolygonFromText(CONCAT('Point(',from_lnt,' ',from_lat,')')))>0";
		    }
		    $where = "`type`= 0 AND " . $where;
		}
		$gray_count = D("Deliver_supply")->where($where)->count();
		
		if(empty($_POST['onlyGrab'])){
			$deliver_count = D('Deliver_supply')->where(array('uid' => $now_deliver['uid'], 'status' => array(array('gt', 0), array('lt', 5))))->count();
			$finish_count = D('Deliver_supply')->where(array('uid' => $now_deliver['uid'], 'status' => array('gt', 4)))->count();
			$arr = array('deliver_info'=>$return_deliver,'gray_count'=>$gray_count,'deliver_count'=>$deliver_count,'finish_count'=>$finish_count);
			$config = M('Appapi_app_config')->select();
			foreach($config as $v){
				if($v['var']=='deliver_android_version'){
					$arr['deliver_android_version'] = $v['value'];
				}elseif($v['var']=='deliver_android_url'){
					$arr['deliver_android_url'] = $v['value'];
				}elseif($v['var']=='deliver_android_vcode'){
					$arr['deliver_android_vcode'] = $v['value'];
				}elseif($v['var']=='deliver_android_vdes'){
					$arr['deliver_android_vdes'] = $v['value'];
				}

			}
		}else{
			$arr['gray_count'] = $gray_count;
		}
		if ($this->config['deliver_model'] == 1 && $now_deliver['group'] == 1) $arr['gray_count'] = 0;
		$this->returnCode('0',$arr);
	}

    public function new_tongji()
    {
        if (empty($this->_uid)) {
            $this->returnCode('20030101');
        }
        $now_deliver = $this->get_deliver_info();
        
        $begin_time = isset($_POST['begin_time']) && $_POST['begin_time'] ? $_POST['begin_time'] : date('Y-m-d');
        $end_time = isset($_POST['end_time']) && $_POST['end_time'] ? $_POST['end_time'] : date('Y-m-d');
        
        $order_from = isset($_POST['order_from']) && $_POST['order_from'] ? intval($_POST['order_from']) : 0;
        $where = array('uid' => $now_deliver['uid'], 'status' => array('gt', 0));
        
        if ($begin_time > $end_time) {
            $this->returnCode('1', array(), '开始时间应该比结束时间早');
        }
        if ($order_from == 0) {
            $where['order_from'] = array('in', array(0, 1, 2));
        } elseif ($order_from == 3) {
            $where['order_from'] = $order_from;
        } else {
            $where['order_from'] = array('in', array(4, 5));
        }
        if ($begin_time && $end_time) {
            $where['start_time'] = array(array('gt', strtotime($begin_time)), array('lt', strtotime($end_time . '23:59:59')));
        }
        $where['status'] = array('neq',0);
        $result = D('Deliver_supply')->field('sum(deliver_cash) as offline_money, sum(money-deliver_cash) as online_money, sum(freight_charge) as freight_charge, sum(tip_price) as tip_price')->where($where)->find();
        
        $count_list = D('Deliver_supply')->field('count(1) as cnt, get_type')->where($where)->group('get_type')->select();
        
        foreach ($count_list as $row) {
            if ($row['get_type'] == 0) {
                $result['self_count'] = $row['cnt'];
            } elseif ($row['get_type'] == 1) {
                $result['system_count'] = $row['cnt'];
            } elseif ($row['get_type'] == 2) {
                $result['change_count'] = $row['cnt'];
            }
        }
        $result['offline_money'] = $result['offline_money'] ? $result['offline_money'] : 0;
        $result['online_money'] = $result['online_money'] ? $result['online_money'] : 0;
        $result['freight_charge'] = $result['freight_charge'] ? $result['freight_charge'] : 0;
        
        $result['self_count'] = $result['self_count'] ? $result['self_count'] : 0;
        $result['system_count'] = $result['system_count'] ? $result['system_count'] : 0;
        $result['change_count'] = $result['change_count'] ? $result['change_count'] : 0;
        $result['tip_price'] = $result['tip_price'] ? $result['tip_price'] : 0;
        $result['appDate'] = $begin_time;
        $result['appDate1'] = $end_time;
        $this->returnCode('0', $result);
    }
	public function new_info(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		$now_deliver = $this->get_deliver_info();
		
		$total_list = D('Deliver_supply')->field('count(1) as cnt, sum(distance) as distance')->where(array('uid' => $now_deliver['uid'], 'status' => array('gt', 4)))->find();
		$grap_count = D('Deliver_supply')->where(array('uid' => $now_deliver['uid'], 'get_type' => 0))->count();
		
		
		$result['finish_total'] = isset($total_list['cnt']) ? intval($total_list['cnt']) : 0;
		$result['distance'] = isset($total_list['distance']) ? number_format($total_list['distance'],2) : 0;
		$result['total'] = $grap_count;
		$result['score'] = $now_deliver['average_score'];
		$result['score_width'] = 20 * $now_deliver['average_score'];
		$this->returnCode('0',$result);
	}

    /**
     * 记录配送员的坐标
     */
    public function location()
    {
        if (empty($this->_uid)) {
            $this->returnCode('20030101');
        }
        $now_deliver = $this->get_deliver_info();
        $lng = floatval($_POST['lng']);
        if (! $lng) {
            $this->returnCode('20020009');
        }
        $lat = floatval($_POST['lat']);
        if (! $lat) {
            $this->returnCode('20020009');
        }
        $columns = array();
        $columns['uid'] = $now_deliver['uid'];
        $columns['lng'] = $lng;
        $columns['lat'] = $lat;
        $columns['create_time'] = time();
        
        $result = D("Deliver_user_location_log")->add($columns);
        D('Deliver_user')->where(array('uid' => $now_deliver['uid']))->save(array('now_lng' => $lng, 'now_lat' => $lat));
        if (! $result) {
            $this->returnCode('20020021');
        }
        $this->returnCode(0);
    }
	
	public function new_deliver_list(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		
		$now_deliver = $this->get_deliver_info();
		
		$lat = isset($_POST['lat']) && $_POST['lat'] ? $_POST['lat'] : $now_deliver['lat'];
		$lng = isset($_POST['lng']) && $_POST['lng'] ? $_POST['lng'] : $now_deliver['lng'];
			
	
		$my_lnt = $now_deliver['lng'];
		$my_lat = $now_deliver['lat'];
		$my_distance = $now_deliver['range'] * 1000;

		if ($now_deliver['group'] == 2 && $now_deliver['store_id']) {
		    $where = "`status`=1";
			$where = "`type`= 1 AND `store_id`=" . $now_deliver['store_id'] . " AND " . $where;
		} else {
		    $where = "`status`=1 ";
		    if ($now_deliver['delivery_range_type'] == 0) {
		        $where .= " AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$my_lat}*PI()/180-`from_lat`*PI()/180)/2),2)+COS({$my_lat}*PI()/180)*COS(`from_lat`*PI()/180)*POW(SIN(({$my_lnt}*PI()/180-`from_lnt`*PI()/180)/2),2)))*1000) < $my_distance ";
		    } else {
		        $where .= " AND MBRContains(PolygonFromText('{$now_deliver['delivery_range_polygon']}'),PolygonFromText(CONCAT('Point(',from_lnt,' ',from_lat,')')))>0";
		    }
		    $where = "`type`= 0 AND " . $where;
		}
		
		$list = D('Deliver_supply')->field(true)->where($where)->order("`supply_id` ASC")->select();
		$id_list = [];
        foreach($list as &$value){
            $value['phone'] = str_replace(' ','', $value['phone']);
			if($value['deliver_check']==0){

				$id_list[] = $value['supply_id'];
			}
		}
		if(!empty($id_list)){

			$data['deliver_check'] = 1;
			$data['check_time'] = $_SERVER['REQUEST_TIME'];
			M('Deliver_supply')->where(array('supply_id'=>array('in',$id_list)))->save($data);
		}

		if (empty($list)) {
			$list = array();
		}
		if ($this->config['deliver_model'] == 1 && $now_deliver['group'] == 1) $list = array();
		
		$this->returnCode('0',$this->foramt_arr($list,$lng,$lat));
	}
	
	public function pick_count(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		
		$now_deliver = $this->get_deliver_info();
		$where = array();
		$where['uid'] = $now_deliver['uid'];
		if ($now_deliver['group'] == 2 && $now_deliver['store_id']) {
			$where['store_id'] = $now_deliver['store_id'];
		}
		//待取货
		$where['status'] = 2;
		$return['pick_count'] = D('Deliver_supply')->where($where)->count();
		//待配送
		$where['status'] = 3;
		$return['send_count'] = D('Deliver_supply')->where($where)->count();
		//配送中
		$where['status'] = 4;
		$return['my_count'] = D('Deliver_supply')->where($where)->count();
		$this->returnCode('0',$return);
	}
	public function new_pick_list(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		
		$now_deliver = $this->get_deliver_info();
		$where = array();
		$where['status'] = 2;
		$where['uid'] = $now_deliver['uid'];
		if ($now_deliver['group'] == 2 && $now_deliver['store_id']) {
			$where['store_id'] = $now_deliver['store_id'];
		}
		$list = D('Deliver_supply')->field(true)->where($where)->order("`create_time` DESC")->select();

		$id_list = [];
		foreach($list as &$value){
            $value['phone'] = str_replace(' ','', $value['phone']);
            if($this->config['deliver_see_freight_charge']==0){
                $value['freight_charge'] = '-';
            }
			if($value['deliver_check']==0){

				$id_list[] = $value['supply_id'];
			}
        }

		if(!empty($id_list)){

			$data['deliver_check'] = 1;
			$data['check_time'] = $_SERVER['REQUEST_TIME'];
			M('Deliver_supply')->where(array('supply_id'=>array('in',$id_list)))->save($data);
		}

		if (empty($list)) {
			$list = array();
		}
		$this->returnCode('0',$this->foramt_arr($list));
	}
	public function new_send_list(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		
		$now_deliver = $this->get_deliver_info();
		$where = array();
		$where['status'] = 3;
		$where['uid'] = $now_deliver['uid'];
		if ($now_deliver['group'] == 2 && $now_deliver['store_id']) {
			$where['store_id'] = $now_deliver['store_id'];
		}
		$list = D('Deliver_supply')->field(true)->where($where)->order("`create_time` DESC")->select();
        foreach($list as &$value){
            $value['phone'] = str_replace(' ','', $value['phone']);
        }
		if (empty($list)) {
			$list = array();
		}

		$this->returnCode('0',$this->foramt_arr($list));
	}
	public function new_my_list(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		
		$now_deliver = $this->get_deliver_info();
		$where = array();
		$where['status'] = 4;
		$where['uid'] = $now_deliver['uid'];
		if ($now_deliver['group'] == 2 && $now_deliver['store_id']) {
			$where['store_id'] = $now_deliver['store_id'];
		}
		$list = D('Deliver_supply')->field(true)->where($where)->order("`create_time` DESC")->select();
        foreach($list as &$value){
            $value['phone'] = str_replace(' ','', $value['phone']);
        }
		if (empty($list)) {
			$list = array();
		}
		
		$this->returnCode('0',$this->foramt_arr($list));
	}
	public function new_finish(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		
		$now_deliver = $this->get_deliver_info();
		
		$where = array();
		$page = isset($_POST['page']) && $_POST['page'] ? intval($_POST['page']) : 1;
		$page = max(1, $page);
		$where['status'] = array('gt', 4);
		$where['is_hide'] = 0;
		$where['uid'] = $now_deliver['uid'];
		if ($now_deliver['group'] == 2 && $now_deliver['store_id']) {
			$where['store_id'] = $now_deliver['store_id'];
		}
		
		$count = D('Deliver_supply')->where($where)->count();
		
		$page_size = 5;
		$start = $page_size * ($page - 1);
		
		$list = D('Deliver_supply')->field(true)->where($where)->order("`create_time` DESC")->limit($start . ',' . $page_size)->select();
        foreach($list as &$value){
            $value['phone'] = str_replace(' ','', $value['phone']);
        }
		if (empty($list)) {
			$list = array();
		}

		$this->returnCode('0',array('list'=>$this->foramt_arr($list),'total_page' => ceil($count/$page_size)));
	}
	public function new_del(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		$now_deliver = $this->get_deliver_info();
		
		$supply_id_arr = explode(',',$_POST['supply_ids']);
		foreach($supply_id_arr as &$value){
			if(empty($value)){
				unset($value);
			}
		}
		D('Deliver_supply')->where(array('uid' => $now_deliver['uid'], 'supply_id' =>array('in',$supply_id_arr), 'status' => array('gt', 4)))->save(array('is_hide' => 1));
		$this->returnCode('0');
	}
	public function new_detail(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		
		$now_deliver = $this->get_deliver_info();
		
		$supply_id = intval($_POST['supply_id']);
		$where = array('supply_id' => $supply_id);
		$supply = D("Deliver_supply")->where($where)->find();
		if (empty($supply)){
			$this->returnCode('20030201');
		}
		if ($supply['uid'] && $supply['uid'] != $now_deliver['uid']) {
			$this->returnCode('20030202');
		}
		$supply['phone'] = str_replace(' ','', $supply['phone']);
		$supply['deliver_cash'] = floatval($supply['deliver_cash']);
		$supply['distance'] = floatval($supply['distance']);
		if($this->config['deliver_see_freight_charge']==0 && $supply['status']<3)
		{
            $supply['freight_charge'] = '-';
        }else{
            $supply['freight_charge'] = floatval($supply['freight_charge']);
        }
        $supply['create_time'] = date('Y-m-d H:i', $supply['create_time']);
        if ($supply['item'] == 3) {
            if ($supply['status'] > 1) {
                $supply['appoint_time'] = date('Y-m-d H:i', $supply['appoint_time']);
            } else {
                $supply['appoint_time'] = "预计{$supply['server_time']}分钟内送达";
            }
        } else {
            $supply['appoint_time'] = date('Y-m-d H:i', $supply['appoint_time']);
        }
		
		$supply['order_time'] = $supply['order_time'] ? date('Y-m-d H:i', $supply['order_time']) : '--';
		$supply['end_time'] = $supply['end_time'] ? date('Y-m-d H:i', $supply['end_time']) : '未送达';
		$supply['real_orderid'] = $supply['real_orderid'] ? $supply['real_orderid'] : $supply['order_id'];
		$supply['image'] = $supply['image'] ? explode(';', trim($supply['image'], ';')) : array();
		if ($supply['change_log']) {
			$changes = explode(',', $supply['change_log']);
			$uid = array_pop($changes);
			$supply['change_name'] = $this->getDeliverUser($uid);
		}
		$return_arr['supply'] = $supply;
		
		$goods = array();
		if ($supply['item'] == 1) {//外送系统的外送
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Waimai_order")->where($where)->find();
			if (empty($order)) {
				$this->returnCode('20030203');
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];

			//商品信息
			$goods = D()->field(true)->table(array(C('DB_PREFIX').'waimai_sell_log'=>'sl', C('DB_PREFIX').'waimai_goods'=>'wg'))->where("sl.order_id=".$supply['order_id']." AND sl.goods_id=wg.goods_id")->select();
			
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'waimai_store'=>'ws'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ws.store_id")->find();
			if (! $store) {
				$this->returnCode('20030204');
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			
			//红包信息
			$where = array();
			$where['id'] = $order['coupon_id'];
			$couponInfo = D("Waimai_user_coupon")->field(true)->where($where)->find();

			//优惠信息
			$discountInfo = json_decode($order['discount_detail'], true);
			
		} elseif ($supply['item'] == 0) {//老的餐饮外送
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Meal_order")->where($where)->find();
			if (empty($order)) {
				$this->returnCode('20030203');
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];
			$order['discount_price'] = $order['price'];

			$goods = unserialize($order['info']);
			foreach ($goods as &$g) {
				$g['tools_money'] = 0;
			}

			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_meal'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();
			if (!$store) {
				$this->returnCode('20030204');
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
		} elseif ($supply['item'] == 2) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Shop_order")->where($where)->find();
			
			if (empty($order)) {
				$this->returnCode('20030203');
			}
			$order['pay_type'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay'], $order['paid']);
			$order['discount_price'] = $order['price'];
			$order['cue_field'] = $order['cue_field'] ? unserialize($order['cue_field']) : array();
			$this->assign('order', $order);
			$goods = D('Shop_order_detail')->field(true)->where(array('order_id' => $supply['order_id']))->select();
			foreach ($goods as &$g) {
				if ($g['spec']) {
					$g['name'] = $g['name'] . '(' . $g['spec'] . ')';
				}
				$g['tools_money'] = 0;
			}
			
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_shop'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();
			if (!$store) {
				$this->returnCode('20030204');
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
		} elseif ($supply['item'] == 3) {
		    if ($supply['server_type'] == 1) {
		        $order = D('Service_user_publish_give')->field(true)->where(array('publish_id' => $supply['order_id']))->find();
		    } else {
		        $order = D('Service_user_publish_buy')->field(true)->where(array('publish_id' => $supply['order_id']))->find();
		    }
		    $order['num'] = 1;
		    $order['freight_charge'] = $supply['freight_charge'];
		}
		
		if($order['create_time']){
			$order['create_time'] = date('Y-m-d H:i', $order['create_time']);
		}
		$return_arr['order'] = $order;
		$return_arr['goods'] = $goods;
		
		$store['phone'] = trim($store['phone']);
		
		$return_arr['store'] = $store;
		$return_arr['is_cancel_order'] = $now_deliver['is_cancel_order'];
		$this->returnCode('0',$return_arr);
	}
	public function foramt_arr($list,$lng=0,$lat=0){
		foreach ($list as &$val){
			switch ($val['pay_type']) {
				case 'offline':
					$val['pay_method'] = 0;
					break;
				default:
					if ($val['paid']) {
						$val['pay_method'] = 1;
					} else {
						$val['pay_method'] = 0;
					}
					break;
			}
			
			$val['pay_method'] = strval($val['pay_method']);
			$val['deliver_cash'] = strval(floatval($val['deliver_cash']));
			$val['distance'] = strval(floatval($val['distance']));
			if($val['freight_charge']!='-'){
                $val['freight_charge'] = strval(floatval($val['freight_charge']));
            }
			$val['appoint_time'] = $val['appoint_time'] ? date('Y-m-d H:i', $val['appoint_time']) : '尽快送达';
			$val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
			$val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
			$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
			$val['image'] = $val['image'] ? explode(';', trim($val['image'], ';')) : array();
			if ($val['change_log']){
				$changes = explode(',', $val['change_log']);
				$uid = array_pop($changes);
				$val['change_name'] = $this->getDeliverUser($uid);
			}
			unset($val['mer_id']);
			unset($val['store_id']);
			unset($val['order_id']);
			unset($val['type']);
			unset($val['create_time']);
			unset($val['start_time']);
			unset($val['end_time']);
// 			unset($val['item']);
			unset($val['code']);
			unset($val['score']);
			// unset($val['status']);
			unset($val['money']);
			unset($val['uid']);
			unset($val['pay_type']);
			unset($val['paid']);
			unset($val['is_hide']);
			// unset($val['note']);
			unset($val['change_log']);		
		}
		return $list;
	}
	private function getDeliverUser($uid){
		$user = D('Deliver_user')->field(true)->where(array('uid' => $uid))->find();
		return isset($user['name']) ? $user['name'] : '';
	}
	public function save_openid(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		D('Deliver_user')->data(array('openid'=>$_POST['openid']))->where(array('uid' => $this->_uid))->save();
		$this->returnCode('0');
	}
	public function save_notice(){
		if(empty($this->_uid)){
			$this->returnCode('20030101');
		}
		D('Deliver_user')->data(array('is_notice'=>$_POST['is_notice']))->where(array('uid' => $this->_uid))->save();
		$this->returnCode('0');
	}

    public function reply()
    {
        if (empty($this->_uid)) {
            $this->returnCode('20030101');
        }
        $now_deliver = $this->get_deliver_info();
        $list = D('Deliver_supply')->field('count(1) AS count, score')->where(array('uid' => $this->_uid, 'score' => array('gt', 0)))->group('score')->select();
        $return = array();
        $return['score'] = $now_deliver['average_score'];
        $return['scoreWidth'] = $now_deliver['average_score'] * 20;
        $return['good'] = 0;
        $return['bad'] = 0;
        $return['middle'] = 0;
        $return['total'] = 0;
        foreach ($list as $row) {
            if ($row['score'] < 3) {
                $return['bad'] += $row['count'];
            } elseif ($row['score'] == 3) {
                $return['middle'] += $row['count'];
            } else {
                $return['good'] += $row['count'];
            }
            $return['total'] += $row['count'];
        }
        $this->returnCode(0, $return);
    }

    public function replyList()
    {
        if (empty($this->_uid)) {
            $this->returnCode('20030101');
        }
        $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
        
        $page = isset($_POST['page']) && $_POST['page'] ? intval($_POST['page']) : 1;
        $page = max(1, $page);
        
        $page_size = 5;
        $start = $page_size * ($page - 1);
        
        $where = 'uid=' . $this->_uid;
        $whereList = 's.uid=' . $this->_uid;
        if ($status == 0) {
            
        } elseif ($status == 1) {
            $where .= ' AND score>3';
            $whereList .= ' AND s.score>3';
        } elseif ($status == 2) {
            $where .= ' AND score=3';
            $whereList .= ' AND s.score=3';
        } else {
            $where .= ' AND score<3';
            $whereList .= ' AND s.score<3';
        }
        $count = D('Deliver_supply')->where($where)->count();
        
        $sql = "SELECT s.name, u.avatar, s.score, s.comment, s.comment_time, s.from_site";
        $sql .= " FROM " . C('DB_PREFIX') . "deliver_supply AS s ";
        $sql .= "INNER JOIN " . C('DB_PREFIX') . "user AS u ON s.userId=u.uid WHERE " . $whereList;
        $sql .= ' ORDER BY `s`.`comment_time` DESC LIMIT ' . $start . ',' . $page_size;
        
        $list = D()->query($sql);
        if (empty($list)) {
            $list = array();
        } else {
            foreach ($list as &$row) {
				$row['score'] = intval($row['score']);
                $row['comment_time'] = date('Y-m-d H:i', $row['comment_time']);
            }
        }
        $this->returnCode(0, array('list' => $list, 'total_page' => ceil($count/$page_size)));
    }
    
    public function cancelOrder()
    {
        $ticket = I('ticket', false);
        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            $uid = $info['uid'];
        }
        if (empty($uid)) {
            $this->returnCode('20020008');
        } else {
            $now_user = D('Deliver_user')->field(true)->where(array('uid' => $uid))->find();
            if (empty($now_user)) {
                $this->returnCode('20020001');
            }
        }
        if ($now_user['is_cancel_order'] == 0) {
            $this->returnCode(1, null, '您没有扔回的权限');
        }
        $supply_id = intval(I('supply_id'));
        if (! $supply_id) {
            $this->returnCode('20020011');
        }
        $supplyDB = D('Deliver_supply');
        $supply = $supplyDB->where(array("supply_id" => $supply_id, 'status' => 2, 'uid' => $uid))->find();
        if (empty($supply)) {
            $this->returnCode(1, null, '订单信息错误');
        }
        
        if ($supplyDB->where(array("supply_id" => $supply_id))->save(array('uid' => 0, 'status' => 1, 'start_time' => 0, 'offer_id' => 0))) {
            $order_id = $supply['order_id'];
            if ($supply['item'] == 2) {
                $result = D('Shop_order')->where(array('order_id'=>$order_id))->data(array('order_status' => 1, 'deliver_info' => ''))->save();
                if (!$result) {
                    $this->returnCode('20020015');
                }
                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 31, 'name' => $now_user['name'], 'phone' => $now_user['phone'], 'note' => '配送员【' .$now_user['name']. '】放弃配送，等待下个配送员接单配送'));
            } elseif ($supply['item'] == 3) {
                D('Service_offer')->cancel_order($supply['order_id']);
            }
            $cancelData = array();
            $cancelData['uid'] = $now_user['uid'];
            $cancelData['username'] = $now_user['name'];
            $cancelData['userphone'] = $now_user['phone'];
            $cancelData['supply_id'] = $supply['supply_id'];
            $cancelData['store_id'] = $supply['store_id'];
            $cancelData['order_id'] = $supply['order_id'];
            $cancelData['item'] = $supply['item'];
            $cancelData['dateline'] = time();
            D('Deliver_cancel_log')->add($cancelData);
            $supply['uid'] = 0;
            $supply['status'] = 1;
            $supply['start_time'] = 0;
            $supplyDB->sendMsg($supply, $uid);
            $this->returnCode(0);
        } else {
            $this->returnCode(1, null, '放弃失败，稍后重试');
        }
    }
}