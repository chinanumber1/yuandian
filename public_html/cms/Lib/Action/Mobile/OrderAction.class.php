<?php
/**
 * 订单列表App
 * @author yaolei
 */
class OrderAction extends BaseAction{
	protected $goodsModel;
	const  DISCOUNT_TYPE_PLATE_COUPON = 2; // 平台优惠券优惠id为2
	const  DISCOUNT_TYPE_STORE_COUPON = 4; // 店铺优惠券优惠id为4
	const  DISCOUNT_TYPE_COUPON_NEWUSER = 3; // 新用户立减优惠方式为3
	public function _initialize()
	{
		parent::_initialize();
		$this->goodsModel = D("Waimai_goods");
	}
	
	public function index(){
		if(empty($this->_uid)){
			$this->returnCode('10052001'); exit();
		}
		$database_order = D('Waimai_order');
    	$database_store = D('Merchant_store');
    	$database_merchant = D('Merchant');
    	$order_condition['uid'] = $this->_uid;
    	
    	import('@.ORG.waimai_page');
    	$count_order = $database_order->where($order_condition)->count();
    	$page = new Page($count_order, 5);
    	$order_info = $database_order->field(true)->where($order_condition)->order('`order_id` DESC')->limit($page->firstRow.','.$page->listRows)->select();
    	
    	foreach($order_info as $key => $val){
    		$storeId[$val['store_id']] = $val['store_id'];
    		$merId[$val['mer_id']] = $val['mer_id'];
    		$orderId[$val['order_id']] = $val['order_id'];
    	}
    	
    	$store_where['store_id'] = array('in', $storeId);
    	$store_info = $database_store->field(true)->where($store_where)->select();
    	
    	$merchant_where['mer_id'] = array('in', $merId);
    	$merchant_info = $database_merchant->field(true)->where($merchant_where)->select();
    	
    	$deliver_where['order_id'] = array('in', $orderId);
    	$deliverSupplyInfo = D("Deliver_supply")->field('`order_id`,`start_time`,`end_time`')->where($deliver_where)->select();
    	
		$orderObj = new Waimai_orderModel();
    	$order_list = $orderObj->formatArray($order_info, $store_info, $merchant_info, $deliverSupplyInfo);
    	
    	foreach($order_list as $key => $val){
    		if($val['goods_count'] >= 2){
    			$order_list[$key]['goods_list'] = array_slice($val['goods_list'],1,2);
    		}
    	}
		
    	$pageShow = array();
    	$pageShow['firstRow'] = $page->firstRow;
    	$pageShow['nowPage'] = $page->nowPage;
    	$pageShow['totalPage'] = $page->totalPage;
    	$pageShow['totalRows'] = $page->totalRows;
    	$pageShow['page_rows'] = $page->page_rows;
    	$pageShow['listRows'] = $page->listRows;
    	
		$order = array();
	
		foreach($order_list as $key => $val){
			$order[$key]['order_id'] = $val['order_id'];
			$order[$key]['store_id'] = $val['store_id'];
			$order[$key]['store_name'] = $val['store_name'];
			$order[$key]['create_time'] = $val['create_time'];
			$order[$key]['pic_info'] = $val['pic_info'];
			$order[$key]['discount_price'] = strval($val['discount_price']);
			$order[$key]['order_status'] = $val['order_status'];
		}
		
		$info = array();
		$info['pageShow'] = $pageShow;
		$info['order'] = $order;
		$this->returnCode(0, $info);
		exit();
	}
	
	public function detail(){
		if(empty($this->_uid)){
			$this->returnCode('10052001'); exit();
		}
		$database_order = D('Waimai_order');
		$database_store = D('Merchant_store');
		$database_merchant = D('Merchant');
		$order_condition['uid'] = $this->_uid;
		$order_condition['order_id'] = $_REQUEST['order_id'];
		
		$order_info = $database_order->field(true)->where($order_condition)->select();
		if(empty($order_info)){
			$this->returnCode('10100001');
		}
		
		foreach($order_info as $key => $val){
			$storeId[$val['store_id']] = $val['store_id'];
			$merId[$val['mer_id']] = $val['mer_id'];
			$orderId[$val['order_id']] = $val['order_id'];
		}
		
		$store_where['store_id'] = array('in', $storeId);
		$store_info = $database_store->field(true)->where($store_where)->select();
		
		
		$merchant_where['mer_id'] = array('in', $merId);
		$merchant_info = $database_merchant->field(true)->where($merchant_where)->select();
		 
		$deliver_where['order_id'] = array('in', $orderId);
		$deliverSupplyInfo = D("Deliver_supply")->field('`order_id`,`start_time`,`end_time`')->where($deliver_where)->select();
		 
		$orderObj = new Waimai_orderModel();
		$now_order = $orderObj->formatArray($order_info, $store_info, $merchant_info, $deliverSupplyInfo);
		// dump($now_order);exit;
		$now_order = $now_order[0];
		$times = time() - 7*24*60*60;
		if($this->config['waimai_coupon'] == 1 && $now_order['create_time'] >= $times && $now_order['pay_type'] != 'offline' && $now_order['paid'] == 1){
			$this->config['waimai_coupon'] == 1;
		} else {
			$this->config['waimai_coupon'] == 0;
		}
		
		//红包信息
		$where['id'] = $now_order['coupon_id'];
		$couponInfo = D("Waimai_user_coupon")->field(true)->where($where)->find();
		$where_coupon['coupon_id'] = $couponInfo['coupon_id'];
    	$coupon = D("Waimai_coupon")->field(true)->where($where_coupon)->find();
    	$coupon['money'] = $couponInfo['money']? $couponInfo['money']: 0;
		
		//优惠信息
		if($now_order['discount_detail']['discount_price']<=0){
      $now_order['discount_detail']=array();
		}
		$discountInfo = json_decode($now_order['discount_detail'], true);
		
		$orderList = array();
		$orderList['order_id'] = $now_order['order_id'];
		$orderList['uid'] = $now_order['uid'];
		$orderList['store_id'] = $now_order['store_id'];
		$orderList['order_number'] = $now_order['order_number'];
		$orderList['create_time'] = $now_order['create_time'];
		$orderList['pay_type'] = $now_order['pay_type']?$now_order['pay_type']:'余额支付';
		$orderList['nickname'] = $now_order['nickname'];
		$orderList['phone'] = $now_order['phone'];
		$orderList['sex'] = $now_order['sex'];
		$orderList['address'] = $now_order['address'];
		$orderList['discount_price'] = strval($now_order['discount_price']);
		$orderList['store_name'] = $now_order['store_name'];
		
		$tools_money = 0; //餐盒费
		$total = 0; //商品总价
		$goodsList = array();
		foreach($now_order['goods_list'] as $key => $val){
			$goodsList[$key]['goods_id'] = $val['goods_id'];
			$goodsList[$key]['name'] = $val['name'];
			$goodsList[$key]['num'] = $val['num'];
			$goodsList[$key]['price'] = $val['price'];
			$goodsList[$key]['tools_price'] = $val['tools_price'];
			$tools_money += $val['tools_price']*$val['num'];
			$total += $val['price']*$val['num'];
		}
		$send_money = 0;
		// 满多少免运费
		if( ($now_order['total_money']>0 && $total<$now_order['total_money']) || ($now_order['total_money'] == 0) ){
			 $send_money += $now_order['send_money'];
		}

		$couponList = array();
		$couponList['name'] = $couponInfo['name'];
		$couponList['money'] = $couponInfo['money'];
		$info = array();
		$info['order'] = $orderList;
		$info['goods'] = $goodsList;
		$info['coupon'] = $coupon;
		$info['discount'] = $discountInfo;
		$info['coupon_setting'] = $this->config['waimai_coupon'];
		$info['send_money'] = floatval($send_money);
		$info['tools_money'] = floatval($tools_money);
		file_put_contents('log.txt',var_export($info,true));
		$this->returnCode(0, $info);
		exit();
	}

	/**
	 * 创建订单
	 */
	public function create() {
		if (! $this->_uid) {
			$this->returnCode('10044010');
		}
		$now_user = M('User')->where(array('uid'=>$this->_uid))->find();
		$store_id = I('store_id', 0, 'intval');
		if (! $store_id) {
			$this->returnCode('10100002');
		}
		//购物车
		$carts = I('carts', false, 'htmlspecialchars_decode');
		$goods_params = json_decode(htmlspecialchars_decode($carts), true);
		if (! $goods_params) {
			$this->returnCode('10100003');
		}
		//优惠劵
		$coupon_id = I("coupon_id", 0, 'intval');

		//地址id
		$address_id = I('address_id', 0, 'intval');
		if (! $address_id) {
			$this->returnCode('10100004');
		}
		//备注
		$desc = I('desc', "");

		//支付方式
		$pay_method = I('pay_method');
		if (! $pay_method) {
			//$this->returnCode('10100005');
		}

		//获取用户地址
		$address = D("Waimai_user_address")->find($address_id);
		if (! $address) {
			$this->returnCode('10052002');
		}

		//验证支付方式
		$system_pay_method = D('Config')->get_pay_method();
		if(empty($system_pay_method)){
			$this->returnCode('10021001');
		}
		if(empty($system_pay_method[$pay_method])){
			//$this->returnCode('10021002');
		}

		//查找商铺
		$db = array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'waimai_store'=>'ws');
		$store = D()->table($db)->field(true)->where('ms.`have_waimai`=1 AND ms.`status`=1 AND ms.`store_id`='.$store_id.' AND ms.`store_id`=ws.`store_id`')->find();
		if (!$store) {
			$this->returnCode('10011002');
		}
		$mer_id = $store['mer_id'];

		//插入订单
		$cart = array();
		$order = array();
		//订单金额
		$money = 0;
		//餐具费
		$tools_money = 0;
		//优惠详情记录
		$discount_detail = array();
		//开启事务
		D()->startTrans();
		$template_msg = $pre = '';
		foreach ($goods_params as $val) {
			$goods = $this->goodsModel->getByIds($val['id'], $store_id, $mer_id);
			if (! $goods) {
				$this->returnCode('10100006');
			}
			$goods[0]['num'] = $val['num'];
			$cart[] = $goods[0];
			$money += $goods[0]['price'] * $val['num'];
			$tools_money += $goods[0]['tools_price'] * $val['num'];
			
			$template_msg .= $pre . $goods[0]['name'] . ':' . $goods[0]['price'] . '*' . $val['num'];
			$pre = '\n\t\t\t';
			
			//商品总销售记录数统计
			$result = $this->goodsModel->where("`goods_id`=".$val['id'])->setInc("sell_count", $val['num']);
			if (! $result) {
				D()->rollback();
				$this->returnCode('10100007');
			}
		}
		if ($money < $store['start_send_money']) {
			$this->returnCode('10100008');
		}

		//total_money为0，即为不参与满免配送费
		if ($store['total_money'] <= 0) {
			$send_money = $store['send_money'];
		} else {
			$send_money = $money > $store['total_money']? 0: $store['send_money'];
		}
		if (! $store['tools_money_have']) {
			$tools_money = 0;
		}

		//是否新用户
		$where = array();
		$where['uid'] = $this->_uid;
		//$where['pay_status'] = 1;
		//$where['order_status'] = 1;
		$have_order = D("Waimai_order")->where($where)->find();
		if (! $have_order) {
			$new_user = 1;
		} else {
			$new_user = 0;
		}

		//查找优惠
		$where = "`store_id`=$store_id AND `mer_id`=$mer_id AND `delete`=0";
		if ($new_user) {
			$where .= " AND ((`pay_method_term`='$pay_method' AND `money_term` <= $money) OR (`newuser_term`= 1 AND `money_term` <= $money) OR (`pay_method_term`='0' AND `money_term`=0 AND `type_id` in (2,4)))";
		} else {
			$where .= " AND ((`pay_method_term`='$pay_method' AND `money_term` <= $money) OR (`pay_method_term`='0' AND `money_term`=0 AND `type_id` in (2,4))) AND `newuser_term`=".$new_user;
		}
			$Waimai_discount = D('Waimai_discount')->get_discount_info($store_id);
			
    		//是否新用户
      $where = array();
      $where['uid'] = $this->_uid;
      //$where['pay_status'] = 1;
      //$where['order_status'] = 1;
      $have_order = D("Waimai_order")->where($where)->find();
      if (! $have_order) {
        $new_user = 1;
      } else {
        $new_user = 0;
      }
    	$tmp = 0;
    	$tmp2 = $money;
    	$discount_money = 0;
    	$discount_tmp = array();    	
    	if(!empty($Waimai_discount)){
        foreach($Waimai_discount as $v){
          if(!$new_user&&$v['type_id']==3){
            continue;
          }
          if($money>=$v['money_term']){
            $tmp = $money-$v['discount_money'];
          }else{
            $tmp = $money;
          }
          if($tmp<$tmp2){
            $tmp2 = $tmp;
            $discount_money = $v['discount_money'];
            $discount_tmp = $v;
          }
        }
    	}
    	
    	$discount_detail['desc']=$discount_tmp['desc'];
    	$discount_detail['discount_money']=$discount_money;
    	 
    	

		//使用优惠劵
		if ($coupon_id && ($is_plat_coupon || $is_store_coupon)) {
			$where = array();
			$where['id'] = $coupon_id;
			$where['uid'] = $this->_uid;
			$where['status'] = 0;
			$where['out_time'] = array('EGT', time());
			$where['order_money'] = array('ELT', $money);
			$coupon = D("waimai_user_coupon")->field(true)->where($where)->find();
			if (! $coupon) {
				$this->returnCode('10031001');
			}
			if (! (($is_plat_coupon && $coupon['type'] == 2) || ($is_store_coupon && $coupon['type'] == 1 && $coupon['store_id'] == $store_id))) {
				$this->returnCode('10031001');
			}
			//代价券置为已使用
			$coupon_update = D("waimai_user_coupon")->where($where)->data(array('status'=>1))->save();
			$discount_money += $coupon['money'];
		}

		//订单价格
		$total = $money + $send_money + $tools_money;
		//优惠后
		$after_discount_total = $money + $send_money + $tools_money - $discount_money;
		if ($after_discount_total < 0) {
			$after_discount_total = 0;
		}
		$columns = array();
		$columns['uid'] = $this->_uid;
		$columns['order_number'] = Waimai_orderModel::createOrderNum();
		$columns['mer_id'] = $mer_id;
		$columns['store_id'] = $store_id;
		$columns['order_status'] = 2;
		$columns['paid'] = 0;
		$columns['price'] = $total;
		$columns['address_id'] = $address_id;
		$columns['desc'] = $desc;
		$columns['discount_price'] = $after_discount_total>0? $after_discount_total: 0;
		$columns['pay_type'] = $pay_method;
		$columns['discount_detail'] = json_encode($discount_detail);
		$columns['coupon_id'] = $coupon_id;
		$columns['create_time'] = time();
		$columns['last_time'] = time();
		$columns['address'] = serialize($address);
		$columns['username'] = $address['name'];
		$columns['userphone'] = $address['phone'];
		if ($pay_method == 'offline') {
			$columns['code'] = mt_rand(1000, 9999);
			//线下支付
			//$columns['paid'] = 2;
		}
		
		if($order_id = D('Waimai_order')->add($columns)){
        $order_info['order_id'] = $order_id;
        $order_info['order_total_money'] = $columns['discount_price'];
        $save_result = D('Waimai_order')->mobile_befor_pay($order_info,0,$now_user);
      
        if($save_result['error_code']){
            D()->rollback();
            $this->returnCode('10100009');
        }
		}

		if (!$order_id) {
			D()->rollback();
			$this->returnCode('10100009');
		}

		//插入商品销售记录表
		foreach ($cart as $goods) {
			$params = array();
			$params['store_id'] = $store_id;
			$params['order_id'] = $order_id;
			$params['goods_id'] = $goods['goods_id'];
			$params['num'] = $goods['num'];
			$params['create_time'] = time();

			$result = D("Waimai_sell_log")->add($params);
			if (! $result) {
				D()->rollback();
				$this->returnCode('10100009');
			}
		}

		//添加订单日志
		$log = array();
		$log['status'] = 2;
		$log['order_id'] = $order_id;
		$log['store_id'] = $store_id;
		$log['uid'] = $this->_uid;
		$log['time'] = time();
		$log['group'] = 4;
		$result = D("Waimai_order_log")->add($log);
		if (!$result) {
			D()->rollback();
			$this->returnCode('10100010');
		}
	
		//线下支付，直接完成订单
	/*	if ($pay_method == 'offline') {
			//商家成交量自增
			$sale_count = D("Waimai_store")->where("`store_id`=".$store_id)->setInc('total_sale', 1);
			//推送到客户端
			$login_log = D("Waimai_app_login_log")->field(true)->where(array('uid'=>$this->_uid))->order("create_time DESC")->find();
			if ($login_log) {
				$audience = array('tag'=>array($login_log['device_id']));
				//拼接extra pigcms_tag:order打开订单页  store打开店铺页  index打开首页  normal普通通知
				$extra = array(
					'pigcms_tag' => 'order',
					'store_id' => 0,
					'order_id' => $order_id
				);
				$client = $login_log['client'];
				$title = "您在【".$store['name']."】店铺下的订单已成功，请耐心等待商家接单。";
				$msg = "您在【".$store['name']."】店铺下的订单已成功，请耐心等待商家接单。";
				import('@.ORG.Jpush');
				$jpush = new Jpush($this->config['push_jpush_appkey'], $this->config['push_jpush_secret']);
				$notification = $jpush->createBody($client, $title, $msg, $extra);

				$jpush->send("all", $audience, $notification);
			}
		}*/
		D()->commit();
		
		/***********************************▼▼▼外送下单增加小票打印、短信通知、模板消息通知▼▼▼**********************************************/
		$user_session = D('User')->get_user($this->_uid);
		$config = D('Config')->get_config();
		if ($user_session['openid']) {
			$href = $config['site_url'] . '/index.php?g=WaimaiWap&c=Order&a=detail&order_id=' . $order_id;
			$model = new templateNews($config['wechat_appid'], $config['wechat_appsecret']);
			$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $user_session['openid'], 'first' => '外卖下单成功', 'keyword3' => $order_id, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $template_msg, 'remark' => '您已成功下单，感谢您的使用！'));
		}
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($order_id, 'waimai_order', 0);
		
		$sms_data = array('mer_id' => $mer_id, 'store_id' => $store_id, 'type' => 'waimai');
		if ($config['sms_place_order'] == 1 || $config['sms_place_order'] == 3) {
			$sms_data['uid'] = $this->_uid;
			$sms_data['mobile'] = $address['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您' . date("Y-m-d H:i:s") . '在【' . $store['name'] . '】中预定了一份外卖，订单号：' . $order_id;
			Sms::sendSms($sms_data);
		}
		if ($config['sms_place_order'] == 2 || $config['sms_place_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客【' . $address['name'] . '】在' . date("Y-m-d H:i:s") . '时预定了一份外卖，订单号：' . $order_id . '请您注意查看并处理!';
			Sms::sendSms($sms_data);
		}
		/***********************************▲▲▲外送下单增加小票打印、短信通知、模板消息通知▲▲▲**********************************************/
		//支付订单
		$this->returnCode(null, array('order_id'=>$order_id));
	}
	
	public function status(){
		$database_order = D('Waimai_order');
		$database_store = D('Merchant_store');
		$database_merchant = D('Merchant');
		$order_condition['uid'] = $this->_uid;
		$order_condition['order_id'] = $_REQUEST['order_id'];
			
		$order_info = $database_order->field(true)->where($order_condition)->select();
		if(empty($order_info)){
			$this->returnCode('10100001');
		}
		
		foreach($order_info as $key => $val){
			$storeId[$val['store_id']] = $val['store_id'];
			$merId[$val['mer_id']] = $val['mer_id'];
			$orderId[$val['order_id']] = $val['order_id'];
		}
		
		$store_where['store_id'] = array('in', $storeId);
		$store_info = $database_store->field(true)->where($store_where)->select();
		
		$merchant_where['mer_id'] = array('in', $merId);
		$merchant_info = $database_merchant->field(true)->where($merchant_where)->select();
			
		$deliver_where['order_id'] = array('in', $orderId);
		$deliverSupplyInfo = D("Deliver_supply")->field('`order_id`,`start_time`,`end_time`')->where($deliver_where)->select();
			
		$orderObj = new Waimai_orderModel();
		$now_order = $orderObj->formatArray($order_info, $store_info, $merchant_info, $deliverSupplyInfo);
		$now_order = $now_order[0];
		
		$times = time() - 7*24*60*60;
		if($this->config['waimai_coupon'] == 1 && $now_order['create_time'] >= $times && $now_order['pay_type_type'] != 'offline' && $now_order['paid'] == 1){
			$this->config['waimai_coupon'] == 1;
		} else {
			$this->config['waimai_coupon'] == 0;
		}
		
		$where['order_id'] = $_REQUEST['order_id'];
		$statusInfo = D("Waimai_order_log")->field(true)->where($where)->select();
		if(empty($statusInfo)){
			$this->returnCode('10100001');
		}
		foreach($statusInfo as $key => $val){
			$deliver_where['order_id'] = $val['order_id'];
			$deliverSupplyInfo = D("Deliver_supply")->field(true)->where($deliver_where)->find();
			$supplyUserInfo = D("Deliver_user")->field(true)->where($deliverSupplyInfo['uid'])->find();
			$status[$key]['supply_phone'] = $supplyUserInfo['phone'];
			$status[$key]['supply_name'] = $supplyUserInfo['name'];
		}
		$orderNumber = D("Waimai_order")->field('`order_number`, `store_id`')->where($where)->find();
		$where['store_id'] = $orderNumber['store_id'];
		$storeName = D("Merchant_store")->field('`name`, `phone`')->where($where)->find();
		
		$info = array();
		$status = array();
		foreach($statusInfo as $key => $val){
			$status[$key]['id'] = $val['id'];
			$status[$key]['order_id'] = $val['order_id'];
			$status[$key]['status'] = $val['status'];
			$status[$key]['time'] = $val['time'];
		}
		
		$info['status'] = $status;
		$info['orderNumber'] = $orderNumber;
		$info['store_name'] = $storeName['name'];
		$info['store_phone'] = $storeName['phone'];
		$info['coupon_setting'] = $this->config['waimai_coupon'];
		
		$this->returnCode(0, $info);
		exit();
	}
	
	public function add_comment(){

		if(empty($this->_uid)){
			$this->returnCode('10052001'); exit();
		}
		$order_where['uid'] = $this->_uid;
		$order_where['order_id'] = $_REQUEST['order_id'];
		$comment_info = D("Waimai_comment")->field(true)->where($order_where)->find();
		if(!empty($comment_info)){
			$this->returnCode('10100100'); exit();
		}
		$where_deliver['order_id'] = $_REQUEST['order_id'];
		$info = D("Deliver_supply")->field('`order_id`,`start_time`,`end_time`,`store_id`')->where($where_deliver)->find();
	//	$info['store_id'] = 117;

		$storeObj = new Waimai_storeModel();
		$infoTime = $storeObj->gettime($info['start_time'], $info['end_time']);
		
		$params = array();
		$params['uid']			= $this->_uid;
		$params['store_id']		= $info['store_id'];
		$params['order_id']		= $_REQUEST['order_id'];
		$params['content']		= $_REQUEST['textarea'];
		$params['total_grade']	= $_REQUEST['grade_total'];		//整体评价
		$params['send_grade']	= $_REQUEST['grade_send'];      //配送评价
		$params['quality_grade']= $_REQUEST['grade_quality'];   //质量评价(菜品口味评价)
		$params['create_time']	= time();
		$params['send_time']	= $infoTime;
	
		//执行保存
		if(D("Waimai_comment")->data($params)->add()){
			$where['uid'] = $this->_uid;
			$where['order_id'] = $_REQUEST['order_id'];
			$status['comment_status'] = '2';
			D("waimai_order")->data($status)->where($where)->save();

				
			$where_store['store_id'] = $params['store_id'];
			$store_info = D("Waimai_store")->field(true)->where($where_store)->find();
			$store_params['grade_total'] = $store_info['grade_total'] + $params['total_grade'];
			$store_params['grade_send'] = $store_info['grade_send'] + $params['grade_send'];
			$store_params['grade_quality'] = $store_info['grade_quality'] + $params['grade_quality'];
			$store_params['comment_num'] = $store_info['comment_num'] + 1;
			$store_params['grade_average'] = ($store_info['grade_total'] + $params['grade_total'])/($store_info['comment_num'] + 1);
			$store_params['send_average'] = ($store_info['grade_send'] + $params['grade_send'])/($store_info['comment_num'] + 1);
			$store_params['quality_average'] = ($store_info['grade_quality'] + $params['grade_quality'])/($store_info['comment_num'] + 1);
			D("Waimai_store")->data($store_params)->where($where_store)->save();
			
			$this->returnCode(0); exit();
		} else {

			$this->returnCode('10100101'); exit();
		}
	}

	/**
     * 点赞
     */
    public function digg() {
    	if(empty($this->_uid)){
			$this->returnCode('10044010'); exit();
		}
        $order_id = I('order_id', 0, 'intval');
        if (!$order_id) {
            $this->returnCode('10100002');
        }
        $goods_id = I('goods_id', 0, 'intval');
        if (!$goods_id) {
            $this->returnCode('10100102');
        }

        $orderModel = D("Waimai_order");
        //查询订单是否存在
        $where = array();
        $where['uid'] = $this->_uid;
        $where['order_id'] = $order_id;
        $where['order_status'] = 1;
        $order = $orderModel->where($where)->find();
        if (!$order) {
            $this->returnCode('10100001');
        }

        //开启事务
        D()->startTrans();
        //查询是否已点赞
        $where = array();
        $where['is_digg'] = 0;
        $where['order_id'] = $order_id;
        $where['goods_id'] = $goods_id;
        $result = D("Waimai_sell_log")->where($where)->data(array('is_digg'=>1))->save();
        if (! $result) {
            D()->rollback();
            $this->returnCode(0);
        }

        //修改商品点赞数
        $result = $this->goodsModel->where(array('goods_id'=>$goods_id))->setInc("digg_count");
        if (!$result) {
            D()->rollback();
            $this->returnCode('10100103');
        }
        D()->commit();
        $this->returnCode(0);
    }
    
    public function get_deliver_place(){
    	$order_id = I("order_id", 0, 'intval');
    	if (! $order_id) {
    		$this->returnCode('10090001'); exit();
    	}
    	$supply = D("Deliver_supply")->where(array('order_id'=>$order_id))->find();
    	if (! $supply) {
    		$this->returnCode('10090002'); exit();
    	}
    	if (! $supply['uid']) {
    		$this->returnCode('10090003'); exit();
    	}
    	$start_time = $supply['start_time'];
    	if (!$start_time) {
    		$this->returnCode('10090004'); exit();
    	}
    	$end_time = $supply['end_time']? $supply['end_time']: time();
    	$where = array();
    	$where['uid'] = $supply['uid'];
    	$where['create_time'] = array(array('gt', $start_time), array('lt', $end_time));
    	$lines = D("Deliver_user_location_log")->field("`lng`,`lat`")->where($where)->order("`create_time` ASC")->select();
    	array_unshift($lines, array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']));
    	if($supply['status'] == 5){
    		array_push($lines, array('lng'=>$supply['aim_lnt'], 'lat'=>$supply['aim_lat']));
    	}
    	$user = D("Deliver_user")->field('`uid`, `name`, `phone`, `photo`')->where(array('uid'=>$supply['uid']))->find();
    	
    	$return = array();
    	$return['lines'] = $lines;
    	$return['site'] = array('store'=>array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']), 'user'=>array('lng'=>$supply['aim_lnt'], 'lat'=>$supply['aim_lat']));
    	$return['user'] = $user;
    	$this->returnCode(0, $return);
    	exit();
    }
    //获取支付方式
    public function order_pre_count() {
    	if (! $this->_uid) {
    		$this->returnCode('10044010');
    	}
    	$storeId = I("store_id", 0, "intval");
    	if (!$storeId) {
    		$this->returnCode('10011002');
    	}
    	$total = I("total", 0, "floatval");
    	if (!$total) {
    		$this->returnCode('10100003');
    	}
    	$pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
    	if (empty($pay_method)) {
    		$this->returnCode('10100301');
    	}

    	$pay_method_new = array();
    	foreach ($pay_method as $k=>$v){
    		$pay_method_new[$k]['name'] = $v['name'];
    		$pay_method_new[$k]['type'] = $k;
    		$pay_method_new[$k]['pic'] = $this->config["pay_".$k."_pic"];
    	}

    	// 本店铺是否支持代金券 所有的优惠方式
    	$Waimai_discount = D('Waimai_discount')->get_discount_info($storeId);
    		//是否新用户
      $where = array();
      $where['uid'] = $this->_uid;
      //$where['pay_status'] = 1;
      //$where['order_status'] = 1;
      $have_order = D("Waimai_order")->where($where)->find();
      if (! $have_order) {
        $new_user = 1;
      } else {
        $new_user = 0;
      }
    	$tmp = 0;
    	$tmp2 = $total;
    	$discount = 0;
    	if(!empty($Waimai_discount)){
        foreach($Waimai_discount as $v){
          if(!$new_user&&$v['type_id']==3){
            continue;
          }
          if($total>=$v['money_term']){
            $tmp = $total-$v['discount_money'];
          }else{
            $tmp = $total;
          }
          if($tmp<$tmp2){
            $tmp2 = $tmp;
            $discount = $v['discount_money'];
          }
        }
    	}
    	
    	$can_use_store_coupon = 0;
    	$can_use_plate_coupon = 0;
    	$newuser = array(
    		'insist' => 0,
    		'order_money' => 0,
    		'discount' => $discount
    	);
		if(count($Waimai_discount)>0){
			$orderUserCount = D('waimai_order')->where(array('uid'=>$this->_uid))->count();
			$new_user_discount_money = 0;
			foreach ($Waimai_discount as $discount){
				$discount['type_id'] = intval($discount['type_id']);
				//可以使用平台优惠劵
				if($discount['type_id'] == self::DISCOUNT_TYPE_PLATE_COUPON){
					$can_use_plate_coupon = 1;
					continue ;
				}
				//可以使用店铺优惠劵
				if ($discount['type_id'] == self::DISCOUNT_TYPE_STORE_COUPON) {
					$can_use_store_coupon = 1;
					continue;
				}
			}
		
			foreach ($Waimai_discount as $discount){
				$discount['type_id'] = intval($discount['type_id']);
				if($orderUserCount==0 && $discount['type_id'] == self::DISCOUNT_TYPE_COUPON_NEWUSER && $discount['money_term']<=$total){
					
					$new_user_discount_money = $discount['discount_money']>$new_user_discount_money?$discount['discount_money']:$new_user_discount_money;
					
					$newuser['insist'] = 1;
					$newuser['discount'] = $new_user_discount_money;
					$newuser['order_money'] = $discount['money_term'];
				}
				if($discount['pay_method_term'] !== '0' && $pay_method_new[$discount['pay_method_term']] && $discount['money_term']<=$total){
					$conditionDiscount['money_term'] = array('elt',$total);
					$conditionDiscount['delete'] = 0;
					$conditionDiscount['type_id'] = 101;
					$conditionDiscount['store_id'] = $storeId;
					$conditionDiscount['pay_method_term'] = $discount['pay_method_term'];
					$discountMoney = D("Waimai_store_discount")->field("MAX(`discount_money`) as `max_discount_money`, `type_id`, `discount_id`, `desc`")->where($conditionDiscount)->order("`discount_money` ASC")->find();
					
					if($discountMoney['max_discount_money']){
						$pay_method_new[$discount['pay_method_term']]['discount'] = $discountMoney['max_discount_money'];
					}else{
						$pay_method_new[$discount['pay_method_term']]['discount'] = $discount['discount_money'];
					}
				}
			}
		}
		
		foreach ($pay_method_new as $key=>$val) {
			if (! isset($val['discount'])) {
				$pay_method_new[$key]['discount'] = 0;
			}
		}
		//优惠方式
		$userCoupon = array();
		if ($can_use_plate_coupon && $can_use_store_coupon) {
			$userCoupon = D('Waimai_user_coupon')->get_user_coupon($this->_uid,$total,0,$storeId,"`money` DESC,`create_time` ASC");
		} elseif ($can_use_plate_coupon) {
			$userCoupon = D('Waimai_user_coupon')->get_user_coupon($this->_uid,$total,2,0,"`money` DESC,`create_time` ASC");
		} elseif ($can_use_store_coupon) {
			$userCoupon = D('Waimai_user_coupon')->get_user_coupon($this->_uid,$total,1,$storeId,"`money` DESC,`create_time` ASC");
		} else {
			$userCoupon = array();
		}
		foreach ($userCoupon as $key => $val) {
			$userCoupon[$key]['coupon_id'] = $val['id'];
		}
		$storeInfo = D('waimai_store')->where(array('store_id'=>$storeId))->find();
    	$send_money = 0;
		// 满多少免运费
		if( ($storeInfo['total_money']>0 && $total<$storeInfo['total_money']) || ($storeInfo['total_money'] == 0) ){
			 $send_money += $storeInfo['send_money'];
		}
		//余额
		
		$where['uid'] = $this->_uid;
		$now_money = D("User")->field('`now_money`')->where($where)->find();
		$return = array();
		$return['pay_method'] = array_values($pay_method_new);
		$return['new_user'] = $newuser;
		$return['user_Coupon'] = $userCoupon? $userCoupon: array();
		$return['send_money'] = $send_money;
		$return['now_money'] = $now_money['now_money'];
    	$this->returnCode(null, $return);
    }

   	//再来一份
   	public function againOrder(){
    	$where['order_id'] = I('order_id', 0, 'intval');
    	$where['uid'] = $this->_uid;
    	$sell_info = D("Waimai_sell_log")->field(true)->where($where)->select();
    	//dump($sell_info);exit;
    	$orderObj = new Waimai_orderModel();
    	$order_list = $orderObj->formatSellArray($sell_info);
		
		  $cart = array();
		  $total = 0;
    	foreach($order_list as $key=>$val){
    		$cart[$key]['num'] = $val['num'];
    		$cart[$key]['goods_id'] = $val['goods_id'];
    		$cart[$key]['name'] = $val['name'];
    		$cart[$key]['price'] = $val['price'];
    		$total +=$val['price']*$val['num'];
    		$cart[$key]['tools_price'] = $val['tools_price'];
    	}

    	$db = array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'waimai_store'=>'ws');

    	$store_id = $sell_info[0]['store_id'];
    	$store_info = D()->table($db)->field(true)->where("`ms`.`store_id`=$store_id AND `ms`.`store_id`=`ws`.`store_id`")->find();
    	
    	//$storeInfo = D('waimai_store')->where(array('store_id'=>$storeId))->find();
    	$send_money = 0;
		// 满多少免运费
		if( ($store_info['total_money']>0 && $total<$store_info['total_money']) || ($store_info['total_money'] == 0) ){
			 $send_money += $store_info['send_money'];
		}
    	$return = array();
    	$return['list'] = $cart;
    	$return['store_id'] = $store_id;
    	$return['send_money'] = $send_money;
    	$return['total_money'] = $store_info['total_money'];
    	$return['name'] = $store_info['name'];

    	$this->returnCode(null, $return);
    }
}