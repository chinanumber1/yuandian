<?php
class OrderModel extends Model
{
	public function __construct() {}


	public function get_mer_bill($condition_merchant,$page_count=15){
		$database_merchant = M('Merchant');
		foreach($condition_merchant as $k=>$v){
			$condition_merchant['m.'.$k] = $v;
			unset($condition_merchant[$k]);
		}

		$count_merchant = $database_merchant->where($condition_merchant)->count();
		import('@.ORG.system_page');
		$p = new Page($count_merchant,$page_count);
		$merchant_list = $database_merchant->join('as m left join '.C('DB_PREFIX').'bill_time t ON m.mer_id = t.merid ')->field(true)->where($condition_merchant)->order('m.bill_time ASC ,m.mer_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
		foreach($merchant_list as $key=>$val){
			$val['meal_bill_info']&&$merchant_list[$key]['bill_info']['meal']=unserialize($val['meal_bill_info']);
			$val['group_bill_info']&&$merchant_list[$key]['bill_info']['group']=unserialize($val['group_bill_info']);
			$val['appoint_bill_info']&&$merchant_list[$key]['bill_info']['appoint']=unserialize($val['appoint_bill_info']);
			$val['store_bill_info']&&$merchant_list[$key]['bill_info']['store']=unserialize($val['store_bill_info']);
			$val['weidian_bill_info']&&$merchant_list[$key]['bill_info']['weidian']=unserialize($val['weidian_bill_info']);
			$val['waimai_bill_info']&&$merchant_list[$key]['bill_info']['waimai']=unserialize($val['waimai_bill_info']);
			$val['wxapp_bill_info']&&$merchant_list[$key]['bill_info']['wxapp']=unserialize($val['wxapp_bill_info']);
			$val['shop_bill_info']&&$merchant_list[$key]['bill_info']['shop']=unserialize($val['shop_bill_info']);
		}
//		dump($merchant_list);
		$pagebar = $p->show();
		return array('merchant_list'=>$merchant_list,'pagebar'=>$pagebar);
	}

	public function get_mer_billed($condition_merchant,$page_count=15){
		$database_merchant = M('Merchant m');
		foreach($condition_merchant as $k=>$v){
			$condition_merchant['m.'.$k] = $v;
			unset($condition_merchant[$k]);
		}
		$count_merchant = M('Bill_time')->count();
		import('@.ORG.system_page');
		$p = new Page($count_merchant,$page_count);
		$merchant_list = $database_merchant->join('RIGHT JOIN '.C('DB_PREFIX').'bill_time t ON m.mer_id = t.merid ')->field(true)->where($condition_merchant)->order('t.update_time DESC')->limit($p->firstRow.','.$p->listRows)->select();

		$pagebar = $p->show();
		foreach($merchant_list as $key=>$val){
			$val['meal_bill_info']    &&$merchant_list[$key]['bill_info']['meal']=unserialize($val['meal_bill_info']);
			$val['group_bill_info']   &&$merchant_list[$key]['bill_info']['group']=unserialize($val['group_bill_info']);
			$val['appoint_bill_info'] &&$merchant_list[$key]['bill_info']['appoint']=unserialize($val['appoint_bill_info']);
			$val['store_bill_info']   &&$merchant_list[$key]['bill_info']['store']=unserialize($val['store_bill_info']);
			$val['weidian_bill_info'] &&$merchant_list[$key]['bill_info']['weidian']=unserialize($val['weidian_bill_info']);
			$val['waimai_bill_info']  &&$merchant_list[$key]['bill_info']['waimai']=unserialize($val['waimai_bill_info']);
			$val['wxapp_bill_info']   &&$merchant_list[$key]['bill_info']['wxapp']=unserialize($val['wxapp_bill_info']);
			$val['shop_bill_info']    &&$merchant_list[$key]['bill_info']['shop']=unserialize($val['shop_bill_info']);
			$bill_info_id =array(
					$merchant_list[$key]['bill_info']['meal']['bill_id'],
					$merchant_list[$key]['bill_info']['group']['bill_id'],
					$merchant_list[$key]['bill_info']['appoint']['bill_id'],
					$merchant_list[$key]['bill_info']['store']['bill_id'],
					$merchant_list[$key]['bill_info']['weidian']['bill_id'],
					$merchant_list[$key]['bill_info']['waimai']['bill_id'],
					$merchant_list[$key]['bill_info']['wxapp']['bill_id'],
					$merchant_list[$key]['bill_info']['shop']['bill_id']
			);
			$tmp = M('Bill_info')->where(array('id'=>array('in',$bill_info_id)))->select();
			foreach($tmp as $k=>$v){
				$merchant_list[$key]['bill_info'][$v['name']]['money']=$v['money'];
			}
		}
		return array('merchant_list'=>$merchant_list,'pagebar'=>$pagebar);
	}

	public function get_order_by_mer_id($mer_id, $type = 'meal', $is_system = false,$time,$is_pay_bill)
	{
		if ($is_system) {
			import('@.ORG.system_page');
		} else {
			import('@.ORG.merchant_page');
		}
		$time_condition = '';
		if($time){
			$time = unserialize($time);
			if ($time['period']){
				if (is_array($time['period'])) {
					$time_condition = " AND (pay_time BETWEEN ".$time['period'][0].' AND '.$time['period'][1].")";
				}else{
					$time_condition = " AND pay_time=".$time['period'];
				}
			}  elseif ($time['month']) {
				$time_condition = ' AND year(FROM_UNIXTIME(pay_time))='.$time['year'].' AND month(FROM_UNIXTIME(pay_time))='.$time['month'];
			}elseif($time['year']){
				$time_condition = ' AND year(FROM_UNIXTIME(pay_time))='.$time['year'];
			}

		}

		if($is_pay_bill==1){
			$tmp_condition = " AND is_pay_bill in (1,2)";
		}else if($is_pay_bill==2){
			$tmp_condition = " AND is_pay_bill=0";
		}
		$time_condition .=$tmp_condition;
		$mode = new Model();
		switch ($type) {
			case 'meal':
				$db = D('Meal_order');
				if(empty($time_condition)){
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, 'pay_type' => array('NEQ', 'offline'), 'status' => array('in', '1, 2')))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, 'pay_type' => array('NEQ', 'offline'), 'status' => array('in', '1, 2'),'_string'=>substr($time_condition,5)))->count();
				}
				$p = new Page($count, 20);
				$sql = "SELECT order_id, info as order_name, uid, mer_id, store_id, phone, total, (balance_pay+payment_money) as price, price as order_price, dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price,score_deducte, card_id, merchant_balance, is_pay_bill,bill_time FROM ". C('DB_PREFIX') . "meal_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00') AND (payment_money<>'0.00' OR balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00') ".$time_condition;
				$sql .= " ORDER BY is_pay_bill, dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";

				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'group':
				$db = D('Group_order');
				if(empty($time_condition)){
					$count = $db->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1, 'is_own' => 0, 'status' => array('in', '1, 2, 6')))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1, 'is_own' => 0, 'status' => array('in', '1, 2, 6'),'_string'=>substr($time_condition,5)))->count();
				}
				$p = new Page($count, 20);
				$sql = "SELECT order_id, order_name, uid, mer_id, store_id, phone, num as total, (balance_pay+payment_money) as price, total_money as order_price,use_time as dateline, pay_time, paid, status, pay_type, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price, card_id, merchant_balance,score_deducte , refund_fee,refund_money ,is_pay_bill,real_orderid FROM ". C('DB_PREFIX') . "group_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2,6) AND (pay_type<>'offline' OR balance_pay<>'0.00') AND (payment_money<>'0.00' OR balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00') ".$time_condition;
				$sql .= " ORDER BY  is_pay_bill ASC ,use_time DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'weidian':
				$db = D('Weidian_order');
				$time = time() - 10 * 86400; //十天前的订单才能对账
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}' {$time_condition}")->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, order_name, uid, mer_id, store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "weidian_order WHERE mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline'".$time_condition;
				$sql .= " ORDER BY  is_pay_bill,order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}'")->group('is_pay_bill')->select();
				break;
			case 'wxapp':
				$db = D('Wxapp_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' {$time_condition}")->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, order_name, uid, mer_id, 0 as store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "wxapp_order WHERE mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'".$time_condition;
				$sql .= " ORDER BY  is_pay_bill,order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'")->group('is_pay_bill')->select();
				break;
			case 'appoint':
				$db = D('Appoint_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1 {$time_condition}")->count();
				$p = new Page($count, 20);
				$sql = "SELECT o.order_id, o.appoint_type as order_name, o.uid,o.mer_id, o.store_id, 1 as total, (pay_money+balance_pay) as price, o.payment_money as order_price, order_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, pay_money as payment_money, merchant_balance,coupon_price,score_deducte, is_pay_bill FROM ". C('DB_PREFIX') . "appoint_order o LEFT JOIN ". C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id  WHERE a.payment_status=1 AND  o.mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1".$time_condition;
				$sql .= " ORDER BY  is_pay_bill,order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);

				$total_list = $db->field('sum(balance_pay + pay_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1")->group('is_pay_bill')->select();
				break;
			case 'store':
				$db = D('Store_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 {$time_condition}")->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, name as order_name, 0 as uid, mer_id, store_id, 1 as total, (payment_money+balance_pay) as price, (payment_money+balance_pay) as order_price, dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "store_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00')".$time_condition;
				$sql .= " ORDER BY  is_pay_bill,order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'waimai':
				$db = D('Waimai_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 {$time_condition}")->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, uid, `desc` as order_name ,mer_id, store_id, 1 as total, (online_pay+balance_pay) as price, (online_pay+balance_pay) as order_price,create_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, online_pay as payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "waimai_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND (online_pay<>'0.00' OR balance_pay<>'0.00')".$time_condition;
				$sql .= " ORDER BY  is_pay_bill,order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + online_pay) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0  AND (payment_money<>'0.00' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'shop':
				$db = D('Shop_order');
				if(empty($time_condition)){
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, 'pay_type' => array('NEQ', 'offline'), 'status' => array('in', '2,3')))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, 'pay_type' => array('NEQ', 'offline'), 'status' => array('in', '2,3'),'_string'=>substr($time_condition,5)))->count();
				}
				$p = new Page($count, 20);
				$sql = "SELECT order_id, 3 as order_name, uid, mer_id, store_id, userphone as phone, num as total, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as price, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as order_price, create_time as dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price,card_price,score_deducte, card_id, merchant_balance, is_pay_bill,real_orderid FROM ". C('DB_PREFIX') . "shop_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (3,2) AND (pay_type<>'offline' OR balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')".$time_condition;
				$sql .= " ORDER BY is_pay_bill, dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";

				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay+payment_money+balance_reduce-no_bill_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (3,2) AND (pay_type<>'offline' OR balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')")->group('is_pay_bill')->select();
				break;
		}

		/** total: 本页的总额 ; finshtotal:本页已对账的总额; alltotal:未对账的总额; alltotalfinsh:全部已对账总额*/
		$total = $finshtotal = $alltotal = $alltotalfinsh =0;
		foreach ($total_list as $row) {
			$row['is_pay_bill'] && $alltotalfinsh += $row['price'];//已对账的总额
			$row['is_pay_bill'] || $alltotal += $row['price'];     //未对账的总额
		}
		//商家的门店信息
		$store_list = D('Merchant_store')->field('store_id, name')->where(array('mer_id' => $mer_id))->select();
		$store_idkey_list = array();
		foreach ($store_list as $store) {
			$store_idkey_list[$store['store_id']] = $store;
		}

		foreach ($order_list as &$order) {
			$order['store_name'] = isset($store_idkey_list[$order['store_id']]['name']) ? $store_idkey_list[$order['store_id']]['name'] : '';
			if ($type == 'meal') {
				$order['order_name'] = unserialize($order['order_name']);
			} elseif ($type == 'appoint') {
				$order['order_name'] = $order['order_name'] == 0 ? '到店' : '上门';
			}
			$order['pay_type_show'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay']);

			if($order['coupon_price']){
				$total += $order['price']+$order['coupon_price']+$order['score_deducte']; //本页的总额
			}else{
				$total += $order['price'];
			}
			//$order['system_pay']=$system_pay;
			$order['is_pay_bill'] && $finshtotal += $order['price'];	//本页已对账的总额
		}
		return array('order_list' => $order_list, 'pagebar' => $p->show(), 'total' => $total, 'finshtotal' => $finshtotal, 'alltotalfinsh' => $alltotalfinsh, 'alltotal' => $alltotal);

	}

	public function bill_order($mer_id, $type = 'meal', $is_system = false,$time,$order_id)
	{
		if ($is_system) {
			import('@.ORG.system_page');
		} else {
			import('@.ORG.merchant_page');
		}
		$time_condition = '';
		if($time){
			//$time = unserialize($time);
			if ($time['period']){
				if (is_array($time['period'])) {
					$time_condition = " AND (pay_time BETWEEN ".$time['period'][0].' AND '.$time['period'][1].")";
				}else{
					$time_condition = " AND pay_time=".$time['period'];
				}

			}

		}
		if($order_id){
			$time_condition .= ' AND order_id IN ('.$order_id.') ';
		}else{

			$time_condition .= " AND is_pay_bill=0";
		}

		$mode = new Model();
		switch ($type) {
			case 'meal':
				$db = D('Meal_order');
				if(empty($time)){
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0,'_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'status' => array('in', '1, 2'),'is_pay_bill'=>0))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0,'_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'status' => array('in', '1, 2'),'_string'=>substr($time_condition,5)))->count();
				}
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id,orderid, info as order_name, uid, mer_id, store_id, phone, total, (balance_pay+payment_money) as price, price as order_price, dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price,score_deducte, card_id, merchant_balance, is_pay_bill,bill_time FROM ". C('DB_PREFIX') . "meal_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (payment_money <> '0.00' OR balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00')".$time_condition;
				$sql .= " ORDER BY dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'group':
				$db = D('Group_order');
				if(empty($time_condition)){
					$count = $db->where(array('mer_id' => $mer_id, '_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'paid' => 1, 'is_own' => 0, 'status' => array('in', '1, 2, 6')))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id,'_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'paid' => 1, 'is_own' => 0, 'status' => array('in', '1, 2, 6'),'_string'=>substr($time_condition,5)))->count();
				}
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id, orderid,real_orderid,order_name, uid, mer_id, store_id, phone, num as total, (balance_pay+payment_money) as price, total_money as order_price, pay_time, paid, status, pay_type, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price, card_id, merchant_balance,score_deducte , refund_fee,refund_money ,is_pay_bill FROM ". C('DB_PREFIX') . "group_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2,6) AND ( payment_money <> '0.00' OR balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00')".$time_condition;
				$sql .= " ORDER BY use_time DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'weidian':
				$db = D('Weidian_order');
				$time = time() - 10 * 86400; //十天前的订单才能对账
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}' {$time_condition}")->count();
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id, orderid,order_name, uid, mer_id, store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "weidian_order WHERE mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline'".$time_condition;
				$sql .= " ORDER BY  order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}'")->group('is_pay_bill')->select();
				break;
			case 'wxapp':
				$db = D('Wxapp_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' {$time_condition}")->count();
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id,orderid,order_name, uid, mer_id, 0 as store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "wxapp_order WHERE mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'".$time_condition;
				$sql .= " ORDER BY order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'")->group('is_pay_bill')->select();
				break;
			case 'appoint':
				$db = D('Appoint_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1 {$time_condition}")->count();
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT o.order_id,o.orderid, o.appoint_type as order_name, o.uid,o.mer_id, o.store_id, 1 as total, (pay_money+balance_pay) as price, o.payment_money as order_price, order_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, pay_money as payment_money, merchant_balance,coupon_price,score_deducte, is_pay_bill FROM ". C('DB_PREFIX') . "appoint_order o LEFT JOIN ". C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id  WHERE a.payment_status=1 AND  o.mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline'  AND service_status=1".$time_condition;
				$sql .= " ORDER BY  order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);

				$total_list = $db->field('sum(balance_pay + pay_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1")->group('is_pay_bill')->select();
				break;
			case 'store':
				$db = D('Store_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 {$time_condition}")->count();
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id,orderid, name as order_name, 0 as uid, mer_id, store_id, 1 as total, (payment_money+balance_pay) as price, (payment_money+balance_pay) as order_price, dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "store_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00' )".$time_condition;
				$sql .= " ORDER BY order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'waimai':
				$db = D('Waimai_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 {$time_condition}")->count();
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id,orderid, uid, `desc` as order_name ,mer_id, store_id, 1 as total, (online_pay+balance_pay) as price, (online_pay+balance_pay) as order_price,create_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, online_pay as payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "waimai_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND (online_pay<>'0.00' OR balance_pay<>'0.00' )".$time_condition;
				$sql .= " ORDER BY order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + online_pay) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0  AND (payment_money<>'0.00' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'shop':
				$db = D('Shop_order');
				if(empty($time_condition)){
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, '_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'status' => array('in', '2,3')))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, '_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'status' => array('in', '2,3'),'_string'=>substr($time_condition,5)))->count();
				}
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id,orderid, real_orderid,3 as order_name, uid, mer_id, store_id, userphone as phone, num as total, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as price, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as order_price, create_time as dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price,card_price,score_deducte, card_id, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "shop_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (3,2) AND ( balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')".$time_condition;
				$sql .= " ORDER BY  dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";

				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay+payment_money+balance_reduce-no_bill_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (3,2) AND ( balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')")->group('is_pay_bill')->select();
				break;
		}

		/** total: 本页的总额 ; finshtotal:本页已对账的总额; alltotal:未对账的总额; alltotalfinsh:全部已对账总额*/
		$total = $finshtotal = $alltotal = $alltotalfinsh =0;
		foreach ($total_list as $row) {
			$row['is_pay_bill'] && $alltotalfinsh += $row['price'];//已对账的总额
			$row['is_pay_bill'] || $alltotal += $row['price'];     //未对账的总额
		}
		//商家的门店信息
		$store_list = D('Merchant_store')->field('store_id, name')->where(array('mer_id' => $mer_id))->select();
		$store_idkey_list = array();
		foreach ($store_list as $store) {
			$store_idkey_list[$store['store_id']] = $store;
		}

		foreach ($order_list as &$order) {
			$order['store_name'] = isset($store_idkey_list[$order['store_id']]['name']) ? $store_idkey_list[$order['store_id']]['name'] : '';
			if ($type == 'meal') {
				$order['order_name'] = unserialize($order['order_name']);
			} elseif ($type == 'appoint') {
				$order['order_name'] = $order['order_name'] == 0 ? '到店' : '上门';
			}
			$order['pay_type_show'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay']);
			if($type=='group'){
				$total+=$order['balance_pay']+$order['payment_money']+$order['score_deducte']+$order['coupon_price']-$order['refund_money'];
			}else if($type=='meal'||$type=='shop'||$type=='appoint'){
				$total+=$order['balance_pay']+$order['payment_money']+$order['score_deducte']+$order['coupon_price'];
			}else{
				$total+=$order['order_price'];
			}
			$order['is_pay_bill'] && $finshtotal += $order['price'];	//本页已对账的总额
		}
		return array('order_list' => $order_list, 'pagebar' => $p->show(), 'total' => $total, 'finshtotal' => $finshtotal, 'alltotalfinsh' => $alltotalfinsh, 'alltotal' => $alltotal);

	}
	public function mer_bill($mer_id, $is_system = false,$type,$time,$store_id)
	{
		if ($is_system) {
			import('@.ORG.system_page');
		} else {
			import('@.ORG.merchant_page');
		}
		$time_condition = '';
		if($time) {
			$time = unserialize($time);
			if ($time['period']) {
				if (is_array($time['period'])) {
					$time_condition = " AND (pay_time BETWEEN " . $time['period'][0] . ' AND ' . $time['period'][1] . ")";
				} else {
					$time_condition = " AND pay_time=" . $time['period'];
				}
			} elseif ($time['month']) {
				$time_condition = ' AND year(FROM_UNIXTIME(pay_time))=' . $time['year'] . ' AND month(FROM_UNIXTIME(pay_time))=' . $time['month'];
			} elseif ($time['year']) {
				$time_condition = ' AND year(FROM_UNIXTIME(pay_time))=' . $time['year'];
			}

		}
		if (!empty($store_id)) {
			$time_condition .= ' AND store_id=' . $store_id;
		}
		if ($type=='waimai') {
			if(empty($time_condition)){
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1))->count();
			}else{
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1,'_string'=>substr($time_condition,5)))->count();
			}
		} elseif($type == 'shop') {
			if(empty($time_condition0)){
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'is_own' => 0, 'paid' => 1, 'status' => array('in', '3,2')))->count();
			}else{
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'is_own' => 0, 'paid' => 1, 'status' => array('in', '3,2'),'_string'=>substr($time_condition,5)))->count();
			}
// 			echo D(ucwords($type).'_order')->_sql();die;
		}else{
			if(empty($time_condition0)){
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1, 'status' => array('in', '1, 2')))->count();
			}else{
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1, 'status' => array('in', '1, 2'),'_string'=>substr($time_condition,5)))->count();
			}
		}

		$p = new Page($count, 20);


		switch ($type) {
			case 'meal':
				$sql = "SELECT order_id,  1 as name,info as order_name, uid, mer_id, store_id, phone, total, (balance_pay+payment_money) as price, price as order_price, dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money, coupon_price,score_deducte,card_id, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "meal_order WHERE mer_id={$mer_id} AND paid=1 AND status in (1,2)  AND (payment_money<>'0.00' OR balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00')";
				break;
			case 'group':
				$sql = "SELECT order_id,real_orderid, order_name, uid, mer_id, store_id, phone, num as total, (balance_pay+payment_money) as price, total_money as order_price, use_time as dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money, coupon_price,score_deducte,card_id, merchant_balance,refund_fee,refund_money, is_pay_bill FROM ". C('DB_PREFIX') . "group_order WHERE mer_id={$mer_id} AND paid=1 AND status in (1,2,6) AND (payment_money<>'0.00' OR balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00')";
				break;
			case 'weidian':
				$time = time() - 10 * 86400;
				$sql = "SELECT order_id, order_name, uid, mer_id, store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "weidian_order WHERE mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline' ";
				break;
			case 'appoint':
				$sql = "SELECT o.order_id, o.appoint_type as order_name, o.uid, o.mer_id, o.store_id, 1 as total, (pay_money+balance_pay) as price, o.payment_money as order_price, order_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, pay_money as payment_money, merchant_balance ,coupon_price,card_pirce,score_deducte,is_pay_bill FROM ". C('DB_PREFIX') . "appoint_order o LEFT JOIN ". C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id  WHERE a.payment_status=1 AND o.mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1 AND (o.payment_money<>'0.00' OR balance_pay<>'0.00')";
				break;
			case 'wxapp':
				$sql = "SELECT order_id, order_name, uid, mer_id, 0 as store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "wxapp_order WHERE mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND (payment_money<>'0.00' OR balance_pay<>'0.00')";
				break;
			case 'store':
				$sql = "SELECT order_id, name as order_name, 0 as uid, mer_id, store_id, 1 as total, (payment_money+balance_pay) as price, (payment_money+balance_pay) as order_price, dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money,  merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "store_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00')";
				break;
			case 'waimai':
				$sql = "SELECT order_id, `desc` as order_name,  uid, mer_id, store_id, 1 as total, (online_pay+balance_pay) as price, (online_pay+balance_pay) as order_price, create_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, online_pay as payment_money,  merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "waimai_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND (online_pay<>'0.00' OR balance_pay<>'0.00')";
				break;
			case 'shop':
				$sql = "SELECT order_id, real_orderid, 3 as name, 3 as order_name,  uid, mer_id, store_id, num as total, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as price, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as order_price, create_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money,  merchant_balance, is_pay_bill, coupon_price, card_price, score_deducte FROM ". C('DB_PREFIX') . "shop_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status IN (2,3) AND (pay_type<>'offline' OR balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')";
				break;
			default:
				break;
		}

		$sql .=$time_condition. " ORDER BY is_pay_bill ASC, dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";
		$mode = new Model();
		$res = $mode->query($sql);
// 		echo $mode->_sql();
		$stores = D('Merchant_store')->field('store_id, name')->where(array('mer_id' => $mer_id))->select();
		$temp = array();
		foreach ($stores as $store) {
			$temp[$store['store_id']] = $store;
		}

		$total = $finshtotal = 0;
		foreach ($res as &$l) {
			$l['store_name'] = isset($temp[$l['store_id']]['name']) ? $temp[$l['store_id']]['name'] : '';
			$l['name'] == 1 && $l['order_name'] = unserialize($l['order_name']);
			$l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'], $l['is_mobile_pay']);
			$l['score_deducte'] = isset($l['score_deducte']) ? $l['score_deducte'] : 0;
			$l['coupon_price'] = isset($l['coupon_price']) ? $l['coupon_price'] : 0;
			$l['card_price'] = isset($l['card_price']) ? $l['card_price'] : 0;
			$total += $l['price'] + $l['score_deducte'] + $l['coupon_price'];
			$l['is_pay_bill'] && $finshtotal += $l['price'];	//本页已对账的总额
			$l['order_price'] = round($l['order_price'], 2);
		}
		$pagebar = $p->show();
		return array('order_list' => $res, 'pagebar' => $pagebar, 'total' => $total, 'finshtotal' => $finshtotal, 'alltotalfinsh' => $alltotalfinsh, 'alltotal' => $alltotal);

	}

	public function export_order_by_mid($mer_id, $type = 'meal', $is_pay_bill = 0,$order_id)
	{
		$condition='';
		$is_pay_bill=' is_pay_bill = '.$is_pay_bill;
		if($order_id){
			$condition .= ' AND order_id IN ('.$order_id.') ';
		}
		$mode = new Model();
		switch ($type) {
			case 'meal':
				$db = D('Meal_order');
				$sql = "SELECT order_id, orderid, info as order_name, uid, mer_id, store_id, phone, total as num, price as order_price, dateline, paid, status, pay_type, pay_time,score_deducte, balance_pay, payment_money, coupon_price FROM ". C('DB_PREFIX') . "meal_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND ( balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <> '0.00')".$condition;
				$sql .= " ORDER BY dateline DESC ";

				$order_list = $mode->query($sql);
				break;
			case 'group':
				$db = D('Group_order');


				$sql = "SELECT order_id, orderid,real_orderid,  order_name, uid, mer_id, store_id, phone, num , total_money as order_price,  pay_type, pay_time,  balance_pay, payment_money,  score_deducte,coupon_price,refund_money,refund_fee FROM ". C('DB_PREFIX') . "group_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2,6) AND (balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <> '0.00')".$condition;
				$sql .= " ORDER BY order_id DESC ";

				$order_list = $mode->query($sql);
				break;
			case 'weidian':
				$db = D('Weidian_order');
				$time = time() - 10 * 86400; //十天前的订单才能对账
				$sql = "SELECT order_id, orderid, order_name, uid, mer_id, store_id, order_num as num,  money as order_price, pay_type, pay_time, balance_pay, payment_money FROM ". C('DB_PREFIX') . "weidian_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline'".$condition;
				$sql .= " ORDER BY order_id DESC ";
				$order_list = $mode->query($sql);
				break;
			case 'wxapp':
				$db = D('Wxapp_order');
				$sql = "SELECT order_id,  orderid, order_name, uid, mer_id, 0 as store_id, order_num as num, money as order_price, pay_type, pay_time, balance_pay, payment_money FROM ". C('DB_PREFIX') . "wxapp_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'".$condition;
				$sql .= " ORDER BY order_id ";
				$order_list = $mode->query($sql);
				break;
			case 'appoint':
				$db = D('Appoint_order');
				$sql = "SELECT order_id,  orderid, appoint_id as order_name, uid, mer_id, store_id, 1 as num,  payment_money as order_price, pay_type, pay_time, balance_pay, pay_money as payment_money,  score_deducte,coupon_price FROM ". C('DB_PREFIX') . "appoint_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0  AND pay_type<>'offline' AND service_status=1".$condition;
				$sql .= " ORDER BY order_id DESC ";
				$order_list = $mode->query($sql);
				break;
			case 'store':
				$db = D('Store_order');
				$sql = "SELECT order_id, orderid, name as order_name, 0 as uid, mer_id, store_id, 1 as num, total_price as order_price, pay_type, pay_time, balance_pay, payment_money FROM ". C('DB_PREFIX') . "store_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0".$condition;
				$sql .= " ORDER BY order_id DESC";
				$order_list = $mode->query($sql);
				break;
			case 'waimai':
				$db = D('Waimai_order');
				$sql = "SELECT order_id, orderid, `desc` as order_name,  uid, mer_id, store_id, 1 as num, price as order_price, pay_type, pay_time, balance_pay, online_pay as payment_money FROM ". C('DB_PREFIX') . "waimai_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0 ".$condition;
				$sql .= " ORDER BY order_id DESC";
				$order_list = $mode->query($sql);
				break;
			case 'shop':
				$db = D('Shop_order');
				$sql = "SELECT order_id,orderid, real_orderid, 3 as order_name, uid, mer_id, store_id, userphone as phone, num ,  (balance_pay+payment_money+balance_reduce-no_bill_money) as order_price,  pay_type, pay_time, balance_pay, payment_money,coupon_price,score_deducte FROM ". C('DB_PREFIX') . "shop_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (3,2) AND ( balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')".$condition;
				$sql .= " ORDER BY order_id DESC";
				$order_list = $mode->query($sql);
				break;
		}


		//商家的门店信息
		$store_list = D('Merchant_store')->field('store_id, name')->where(array('mer_id' => $mer_id))->select();
		$store_idkey_list = array();
		foreach ($store_list as $store) {
			$store_idkey_list[$store['store_id']] = $store;
		}

		foreach ($order_list as &$order) {
			$order['store_name'] = isset($store_idkey_list[$order['store_id']]['name']) ? $store_idkey_list[$order['store_id']]['name'] : '';
			if ($type == 'meal') {
				$order['order_name'] = unserialize($order['order_name']);
			} elseif ($type == 'appoint') {
				$order['order_name'] = $order['order_name'] == 0 ? '到店' : '上门';
			}
			$order['pay_type'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay']);
		}
		return $order_list;
		//return array('order_list' => $order_list);

	}


	public function get_offlineorder_by_mer_id($mer_id, $staff_name = '', $type = 'shop')
	{
		import('@.ORG.merchant_page');

		$field = '';
		if ($type == 'shop') {
			$field = "3 as name, order_id, orderid, real_orderid, price, payment_money, balance_pay, merchant_balance, coupon_price, (card_give_money + card_price) as card_price, score_deducte, score_deducte, (price-payment_money-balance_pay-merchant_balance-coupon_price-card_price-card_give_money-score_deducte) as cash, create_time, pay_time, is_pay_bill";
			$table_name = 'Shop_order';
			$where = " mer_id={$mer_id} AND paid=1 AND status IN (3,2) AND pay_type='offline'";
		} elseif ($type == 'meal') {
			$field = "1 as name, business_id as order_id, orderid, orderid as real_orderid, price, payment_money, balance_pay, merchant_balance, coupon_price, card_price, score_deducte, (price-payment_money-balance_pay-merchant_balance-coupon_price-card_price-score_deducte) as cash, dateline as create_time, pay_time, is_pay_bill";
			$table_name = 'Store_order';
			$where = " mer_id={$mer_id} AND paid=1 AND business_type='foodshop' AND pay_type='offline'";
		} elseif ($type == 'group') {
			$field = "2 as name, order_id, orderid, real_orderid, price, payment_money, balance_pay, merchant_balance, coupon_price, card_price, score_deducte, score_deducte, (price-payment_money-balance_pay-merchant_balance-coupon_price-card_price-score_deducte) as cash, add_time as create_time, pay_time, is_pay_bill";
			$table_name = 'Group_order';
			$where = " mer_id={$mer_id} AND paid=1 AND status IN (1,2) AND pay_type='offline'";
		}
		if ($type == 'meal') {
			$staff_name && $where .= " AND staff_name='{$staff_name}'";
		} else {
			$staff_name && $where .= " AND last_staff='{$staff_name}'";
		}

		$count = D($table_name)->where($where)->count();
		$uncount = D($table_name)->where($where . ' AND is_pay_bill=0')->count();
		$p = new Page($count, 20);
		$list = D($table_name)->field($field)->where($where)->order('is_pay_bill ASC')->limit("{$p->firstRow}, {$p->listRows}")->select();
		return array('order_list' => $list, 'pagebar' => $pagebar, 'uncount' => $uncount);
	}

	/*
	 * plat_type 1 系统后台 2 商家 3 店员
	 * $param  惨数数组
	 * */
	public function order_export($param){
		import('ORG.Util.Dir');
		Dir::delDirnotself('./runtime');
		import('@.ORG.plan');
		$plan_class = new plan();
		switch ($param['type']) {
			case 'meal':
				$title = C('config.meal_alias_name').'账单';
				break;
			case 'group':
				$title = C('config.group_alias_name').'账单';
				break;
			case 'appoint':
				$title = C('config.appoint_alias_name').'账单';
				break;
			case 'shop':
				$title =  C('config.shop_alias_name').'账单';
				break;
			case 'income':
				$title = '收入明细';
				break;
			case 'user':
				$title = '用户信息';
				break;
			case 'express':
				$title = '配送信息';
				break;
			case 'trade':
				$title = '交易信息';
				break;
			case 'goods':
				$title = '菜品信息';
				break;
			case 'card_new':
				$title = '会员信息';
				break;
			case 'fans':
				$title = '粉丝信息';
				break;
			case 'merchant_list':
				$title = '商家列表';
				break;
			case 'percentage':
				$title = '抽成列表';
				break;
			case 'admin_recharge':
				$title = '管理员充值导出';
				break;
			case 'store':
				$title = '到店消费';
				break;


		}
		$file_name = date("Y-m-d", time()).'-'.$param['rand_number'] . '.xls';
		$date = array(
				'type'=>$param['type'],
				'file_name'=>$file_name,
				'title' =>$title,
				'status' => 0,
				'param'=>serialize($param),
				'dateline' => $param['rand_number']
		);
		if($export_id = M('Export_log')->add($date)){
			$param['export_id'] = $export_id;
			$params = array(
					'file'=>'order_export',
					'plan_time'=>time(),
					'param'=>$param,
			);
			$plan_class->addTask($params);
			return array('file_name'=>$file_name,'export_id'=>$export_id);

		}else{
			return false;
		}
	}
	public function order_export_runtask($param){

		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '';
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array( 'dir' => './runtime' );
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		$objExcel = new PHPExcel();

		$objProps = $objExcel->getProperties();
		switch ($param['type']) {
			case 'meal':
			case 'foodshop':
				$param['type'] = 'meal';
				$title = C('config.meal_alias_name').'账单';
				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);
				$where_store = array('status' => 1);
				if(!empty($param['keyword']) && $param['searchtype'] == 's_name'){
					$where_store['name'] = array('like', '%'.$param['keyword'].'%');
				}

				if ($param['system_session']['area_id']) {
					$now_area = D('Area')->field(true)->where(array('area_id' => $param['system_session']['area_id']))->find();
					if($now_area['area_type']==3){
						$area_index = 'area_id';
					}elseif($now_area['area_type']==2){
						$area_index = 'city_id';
					}elseif($now_area['area_type']==1){
						$area_index = 'province_id';
					}
					$where_store[$area_index] = $param['system_session']['area_id'];
					$stores = D('Merchant_store')->field('store_id')->where($where_store)->select();//
				} else {
					$stores = D('Merchant_store')->field('store_id')->where($where_store)->select();
				}

				$store_ids = array();
				foreach ($stores as $row) {
					$store_ids[] = $row['store_id'];
				}

				$condition_where = 'Where 1=1 ';
				if ($store_ids) {
					$where['store_id'] = array('in', $store_ids);
					$condition_where .=' AND o.store_id in('.implode(',',$store_ids).')';
				}

				if ($param['merchant_session']) {
					$condition_where = "WHERE o.mer_id = " . $param['merchant_session']['mer_id'];
				}
				if ($param['store_session']) {
					$condition_where = "WHERE o.store_id = " . $param['store_session']['store_id'];
				}

				if(!empty($param['keyword'])){
					if ($param['searchtype'] == 'real_orderid') {
						$where['real_orderid'] = htmlspecialchars($param['keyword']);
						$condition_where .=' AND o.real_orderid ='.$where['real_orderid'];
					} elseif ($param['searchtype'] == 'orderid') {
						$where['orderid'] = htmlspecialchars($param['keyword']);
						$condition_where .=' AND o.orderid ='.$where['orderid'];
					} elseif ($param['searchtype'] == 'name') {
						$where['name'] = htmlspecialchars($param['keyword']);
						$condition_where .=' AND o.name ='.$where['name'];
					} elseif ($param['searchtype'] == 'phone') {
						$where['phone'] = htmlspecialchars($param['keyword']);
						$condition_where .=' AND o.phone ='.$where['phone'];
					}
				}
				$status = isset($param['status']) ? intval($param['status']) : -1;
				$type = isset($param['type']) && $param['type'] ? $param['type'] : '';
				$sort = isset($param['sort']) && $param['sort'] ? $param['sort'] : '';
				if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
				if ($type != 'price' && $type != 'pay_time') $type = '';
				$order_sort = '';
				if ($type && $sort) {
					$order_sort .= $type . ' ' . $sort . ',';
					$order_sort .= 'order_id DESC';
				} else {
					$order_sort .= 'order_id DESC';
				}
				$pay_type = isset($param['pay_type']) && $param['pay_type'] ? $param['pay_type'] : '';
				if($pay_type&&$pay_type!='balance'){
					$condition_where .=' AND p.pay_type ="'.$pay_type.'"';
				}else if($pay_type=='balance'){
					$condition_where .= " AND (`p`.`system_balance`<>0 OR `p`.`merchant_balance_pay` <> 0 )";
				}


				if(!empty($param['begin_time'])&&!empty($param['end_time'])){
					if ($param['begin_time']>$param['end_time']) {
						return false;
					}
					$period = array(strtotime($param['begin_time']." 00:00:00"),strtotime($param['end_time']." 23:59:59"));
					$where['_string'] .=( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
					$condition_where .=" AND  (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
				}

				if ($status != -1) {
					$where['status'] = $status;
					$condition_where .= ' AND o.status ='.$status;
				}
				// 		echo '<pre/>';
				// 		print_r($where);die;
				//$count = D("Foodshop_order")->where($where)->count();
//				$sql_count = 'SELECT count(o.order_id) as count from pigcms_foodshop_order o LEFT JOIN (select a.* from pigcms_plat_order a,(select max(order_id) as order_id,business_id from pigcms_plat_order group by business_id) b where a.order_id=b.order_id and a.business_id=b.business_id) p on o.order_id  = p.business_id '.$condition_where;
				$sql_count = 'SELECT count(o.order_id) as count from '.C('DB_PREFIX').'foodshop_order o LEFT JOIN  '.C('DB_PREFIX').'plat_order  p on o.order_id  = p.business_id AND p.paid =1 LEFT JOIN '.C('DB_PREFIX').'foodshop_order_detail as fd ON fd.order_id = o.order_id '.$condition_where;
				$count  = M()->query($sql_count);
				$count = $count[0]['count'];
				set_time_limit(0);
				//require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

				$length = ceil($count / 1000);


				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);

					$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
					$objActSheet = $objExcel->getActiveSheet();
					$objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
					$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
					$objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
					$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
					$objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
					$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
					$objExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
					$objExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
					$objExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
					$objExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);

					$objActSheet->setCellValue('A1', '订单流水号');
					$objActSheet->setCellValue('B1', '商家名称');
					$objActSheet->setCellValue('C1', '店铺名称');
					$objActSheet->setCellValue('D1', '客户名称');
					$objActSheet->setCellValue('E1', '客户电话');
					$objActSheet->setCellValue('F1', '预定金');
					$objActSheet->setCellValue('G1', '预定时间');
					$objActSheet->setCellValue('H1', '桌台类型');
					$objActSheet->setCellValue('I1', '桌台名称');
					$objActSheet->setCellValue('J1', '商品名称');
					$objActSheet->setCellValue('K1', '规格/属性');
					$objActSheet->setCellValue('L1', '单价');
					$objActSheet->setCellValue('M1', '数量');
					$objActSheet->setCellValue('N1', '单位');
					$objActSheet->setCellValue('O1', '订单状态');
					$objActSheet->setCellValue('P1', '订单总价');
					$objActSheet->setCellValue('Q1', '余额支付');
					$objActSheet->setCellValue('R1', '平台在线支付');
					$objActSheet->setCellValue('S1', '商家余额支付');
					$objActSheet->setCellValue('T1', C('config.score_name'));
					$objActSheet->setCellValue('U1', '支付时间');
					$objActSheet->setCellValue('V1', '支付方式');
					$objActSheet->setCellValue('W1', '支付类型');
					$objActSheet->setCellValue('X1', '第三方流水号');


					//$objActSheet->setCellValue('R1', '支付情况');

					//$sql = 'SELECT o.*,p.pay_type as pay_method,p.system_balance,p.merchant_balance_pay,p.pay_money,p.system_score_money,p.pay_time ,p.paid from pigcms_foodshop_order o LEFT JOIN  pigcms_plat_order  p on o.order_id  = p.business_id '.$condition_where .'limit '.($i*1000).',1000';
					$sql = 'SELECT o.*,fd.name as goods_name,fd.num as goods_num ,fd.spec as goods_spec,fd.unit as goods_unit,fd.price as goods_price,p.pay_type as pay_method,p.system_balance,p.merchant_balance_pay,p.pay_money,p.system_score_money,p.pay_time ,p.paid,p.third_id from '.C('DB_PREFIX').'foodshop_order o LEFT JOIN  '.C('DB_PREFIX').'plat_order  p on o.order_id  = p.business_id AND p.business_type="foodshop" AND p.paid =1 LEFT JOIN '.C('DB_PREFIX').'foodshop_order_detail as fd ON fd.order_id = o.order_id '.$condition_where.' ORDER BY o.order_id DESC ' .'limit '.($i*1000).',1000';

					$list = M('')->query($sql);
					//appdump(M());
					$mer_ids = $store_ids = array();
					foreach ($list as $l) {
						$mer_ids[] = $l['mer_id'];
						$store_ids[] = $l['store_id'];
						$table_types[] = $l['table_type'];
						$tids[] = $l['table_id'];

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


					$store_temp = $mer_temp = array();
					if ($mer_ids) {
						$merchants = D("Merchant")->where(array('mer_id' => array('in', $mer_ids)))->select();
						foreach ($merchants as $m) {
							$mer_temp[$m['mer_id']] = $m;
						}
					}
					if ($store_ids) {
						$merchant_stores = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
						foreach ($merchant_stores as $ms) {
							$store_temp[$ms['store_id']] = $ms;
						}
					}
					foreach ($list as &$li) {
						$li['merchant_name'] = isset($mer_temp[$li['mer_id']]['name']) ? $mer_temp[$li['mer_id']]['name'] : '';
						$li['store_name'] = isset($store_temp[$li['store_id']]['name']) ? $store_temp[$li['store_id']]['name'] : '';

						$li['table_type_name'] = isset($type_list[$li['table_type']]) ? $type_list[$li['table_type']]['name'] . '(' . $type_list[$li['table_type']]['min_people'] . '-' . $type_list[$li['table_type']]['max_people'] . '人)' : '';
						$li['table_name'] = isset($table_list[$li['table_id']]) ? $table_list[$li['table_id']]['name'] : '';
						$li['show_status'] = D('Foodshop_order')->status_list[$li['status']];
					}

					$tmp_id = 0;
					$index_good =0;
					if (!empty($list)) {
						$index = 1;
						foreach ($list as $value) {
							if($tmp_id == $value['order_id']){
								$objActSheet->setCellValueExplicit('A' . $index, '');
								$objActSheet->setCellValueExplicit('B' . $index, '');
								$objActSheet->setCellValueExplicit('C' . $index, '');
								$objActSheet->setCellValueExplicit('D' . $index, '');
								$objActSheet->setCellValueExplicit('E' . $index, '');
								$objActSheet->setCellValueExplicit('F' . $index, '');
								$objActSheet->setCellValueExplicit('G' . $index, '');
								$objActSheet->setCellValueExplicit('H' . $index, '');
								$objActSheet->setCellValueExplicit('I' . $index, '');
								$objActSheet->setCellValueExplicit('J' . $index, $value['goods_name']);
								$objActSheet->setCellValueExplicit('K' . $index, $value['goods_spec']);
								$objActSheet->setCellValueExplicit('L' . $index, $value['goods_price']);
								$objActSheet->setCellValueExplicit('M' . $index, $value['goods_num']);
								$objActSheet->setCellValueExplicit('N' . $index, $value['goods_unit']);
								$objActSheet->setCellValueExplicit('O' . $index, '');
								$objActSheet->setCellValueExplicit('P' . $index, '');
								$objActSheet->setCellValueExplicit('Q' . $index, '');
								$objActSheet->setCellValueExplicit('R' . $index, '');
								$objActSheet->setCellValueExplicit('S' . $index, '');
								$objActSheet->setCellValueExplicit('T' . $index, '');
								$objActSheet->setCellValueExplicit('U' . $index, '');
								$objActSheet->setCellValueExplicit('V' . $index, '');
								$objActSheet->setCellValueExplicit('W' . $index, '');
								$objActSheet->setCellValueExplicit('X' . $index, '');
								$index++;
							}else{
								$index++;
								$objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
								$objActSheet->setCellValueExplicit('B' . $index, $value['merchant_name']);
								$objActSheet->setCellValueExplicit('C' . $index, $value['store_name']);
								$objActSheet->setCellValueExplicit('D' . $index, $value['name']);
								$objActSheet->setCellValueExplicit('E' . $index,$value['phone']);
								$objActSheet->setCellValueExplicit('F' . $index, $value['book_price']);
								$objActSheet->setCellValueExplicit('G' . $index, $value['book_time'] ? date('Y-m-d H:i:s', $value['book_time']) : '');
								$objActSheet->setCellValueExplicit('H' . $index,$value['table_type_name']);
								$objActSheet->setCellValueExplicit('I' . $index,$value['table_name']);
								$objActSheet->setCellValueExplicit('J' . $index,'');
								$objActSheet->setCellValueExplicit('K' . $index,'');
								$objActSheet->setCellValueExplicit('L' . $index,'');
								$objActSheet->setCellValueExplicit('M' . $index,'');
								$objActSheet->setCellValueExplicit('N' . $index,'');
								$objActSheet->setCellValueExplicit('O' . $index, $value['show_status']);
								$objActSheet->setCellValueExplicit('P' . $index, floatval($value['total_money']));
								$objActSheet->setCellValueExplicit('Q' . $index, floatval($value['system_balance']));
								$objActSheet->setCellValueExplicit('R' . $index, floatval($value['pay_money']));
								$objActSheet->setCellValueExplicit('S' . $index, floatval($value['merchant_balance_pay']));
								$objActSheet->setCellValueExplicit('T' . $index, floatval($value['system_score_money']));
								$objActSheet->setCellValueExplicit('U' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
								$objActSheet->setCellValueExplicit('V' . $index,D('Pay')->get_pay_name($value['pay_method'], $value['is_mobile_pay'], $value['paid']));
								$objActSheet->setCellValueExplicit('W' . $index,'支付定金');
								$objActSheet->setCellValueExplicit('X' . $index,$value['third_id']);
								$index++;
								$index_good = 0;

								//商品
								if($value['goods_name']){
									$objActSheet->setCellValueExplicit('A' . $index, '');
									$objActSheet->setCellValueExplicit('B' . $index, '');
									$objActSheet->setCellValueExplicit('C' . $index, '');
									$objActSheet->setCellValueExplicit('D' . $index, '');
									$objActSheet->setCellValueExplicit('E' . $index, '');
									$objActSheet->setCellValueExplicit('F' . $index, '');
									$objActSheet->setCellValueExplicit('G' . $index, '');
									$objActSheet->setCellValueExplicit('H' . $index, '');
									$objActSheet->setCellValueExplicit('I' . $index, '');
									$objActSheet->setCellValueExplicit('J' . $index, $value['goods_name']);
									$objActSheet->setCellValueExplicit('K' . $index, $value['goods_spec']);
									$objActSheet->setCellValueExplicit('L' . $index, $value['goods_price']);
									$objActSheet->setCellValueExplicit('M' . $index, $value['goods_num']);
									$objActSheet->setCellValueExplicit('N' . $index, $value['goods_unit']);
									$objActSheet->setCellValueExplicit('O' . $index, '');
									$objActSheet->setCellValueExplicit('P' . $index, '');
									$objActSheet->setCellValueExplicit('Q' . $index, '');
									$objActSheet->setCellValueExplicit('R' . $index, '');
									$objActSheet->setCellValueExplicit('S' . $index, '');
									$objActSheet->setCellValueExplicit('T' . $index, '');
									$objActSheet->setCellValueExplicit('U' . $index, '');
									$objActSheet->setCellValueExplicit('V' . $index, '');
									$objActSheet->setCellValueExplicit('W' . $index, '');
									$objActSheet->setCellValueExplicit('X' . $index, '');
									$index_good = 1;
									$index++;
								}
							}
							$tmp_id = $value['order_id'];

						}
					}
					sleep(2);
				}
				break;
			case 'group':
				$title = C('config.group_alias_name').'账单';

				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);

				if ($param['merchant_session']) {
					$condition_where = "WHERE o.mer_id = " . $param['merchant_session']['mer_id'];
				}else{
					$condition_where = "WHERE 1=1";
				}
				if ($param['store_session']) {
					$condition_where = "WHERE o.store_id = " . $param['store_session']['store_id'];
				}
				if(!empty($param['keyword'])){
					if ($param['searchtype'] == 'real_orderid') {
						$condition_where .= " AND `o`.`real_orderid`='" . htmlspecialchars($param['keyword'])."'";
					} elseif ($param['searchtype'] == 'orderid') {
						$where['orderid'] = htmlspecialchars($param['keyword']);
						$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$param['keyword']))->find();
						$condition_where .= " AND  `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
					} elseif ($param['searchtype'] == 'name') {
						$condition_where .= " AND  `u`.`nickname` like '%" . htmlspecialchars($param['keyword']) . "%'";
					} elseif ($param['searchtype'] == 'phone') {
						$condition_where .= " AND `u`.`phone`='" . htmlspecialchars($param['keyword']) . "'";
					} elseif ($param['searchtype'] == 's_name') {
						$condition_where .= " AND `g`.`s_name` like '%" . htmlspecialchars($param['keyword']) . "%'";
					}elseif ($param['searchtype'] == 'third_id') {
						$condition_where .= " AND `o`.`third_id` ='".$param['keyword']."'";
					}elseif ($param['searchtype'] == 'm_name') {
						$condition_where .= " AND `m`.`name` like '%" . htmlspecialchars($param['keyword']) . "%'";
					}
				}
				if ($param['system_session']['area_id']) {
					$now_area = D('Area')->field(true)->where(array('area_id' => $param['system_session']['area_id']))->find();
					if($now_area['area_type']==3){
						$area_index = 'area_id';
					}elseif($now_area['area_type']==2){
						$area_index = 'city_id';
					}elseif($now_area['area_type']==1){
						$area_index = 'province_id';
					}
					$condition_where .= " AND `m`.`{$area_index}`={$param['system_session']['area_id']}";
				}

				$status = isset($param['status']) ? intval($param['status']) : -1;
				$type = isset($param['type']) && $param['type'] ? $param['type'] : '';
				$sort = isset($param['sort']) && $param['sort'] ? $param['sort'] : '';
				$pay_type = isset($param['pay_type']) && $param['pay_type'] ? $param['pay_type'] : '';
				if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
				if ($type != 'price' && $type != 'pay_time') $type = '';
				$order_sort = '';
				if ($type && $sort) {
					$order_sort .= 'o.' . $type . ' ' . $sort . ',';
					$order_sort .= 'o.order_id DESC';
				} else {
					$order_sort .= 'o.order_id DESC';
				}

				if ($status != -1) {
					$condition_where .= " AND `o`.`status`={$status} AND `g`.`status` = 1 ";
				}
				if($pay_type){
					if($pay_type=='balance'){
						$condition_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )";
					}else{
						$condition_where .= " AND `o`.`pay_type`='{$pay_type}'";
					}
				}

				if(!empty($param['begin_time'])&&!empty($param['end_time'])){
					if ($param['begin_time']>$param['end_time']) {
						return false;
						//$this->error_tips("结束时间应大于开始时间");
					}
					$period = array(strtotime($param['begin_time']." 00:00:00"),strtotime($param['end_time']." 23:59:59"));
					$condition_where .= " AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
					//$condition_where['_string']=$time_condition;
				}
				
				if ($param['area_id'] && $param['area_id']!='undefined') {
					$condition_where .= " AND m.area_id={$param['area_id']}";
				} else if ($param['city_idss'] && $param['city_idss']!='undefined') {
					$condition_where .= " AND m.city_id={$param['city_idss']}";
				} else if ($param['province_idss'] && $param['province_idss']!='undefined') {
					$condition_where .= " AND m.province_id={$param['province_idss']}";
				}



				$sql = "SELECT count(order_id) as count FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC ";
				$count = D()->query($sql);

				$length = ceil($count[0]['count'] / 1000);
				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);

					$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
					$objActSheet = $objExcel->getActiveSheet();
					$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(40);
					$objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);

					$objActSheet->setCellValue('A1', '商家ID');
					$objActSheet->setCellValue('B1', '商家电话');
					$objActSheet->setCellValue('C1', '订单编号');
					$objActSheet->setCellValue('D1', '团购名称');
					$objActSheet->setCellValue('E1', '商家名称');
					$objActSheet->setCellValue('F1', '客户姓名');
					$objActSheet->setCellValue('G1', '客户电话');
					$objActSheet->setCellValue('H1', '订单总价');
					$objActSheet->setCellValue('I1', '平台余额');
					$objActSheet->setCellValue('J1', '商家余额');
					$objActSheet->setCellValue('K1', '在线支付金额');
					$objActSheet->setCellValue('L1', '平台'.C('config.score_name'));
					$objActSheet->setCellValue('M1', '平台优惠券');
					$objActSheet->setCellValue('N1', '商家优惠券');
					$objActSheet->setCellValue('O1', '商家折扣');
					$objActSheet->setCellValue('P1', '支付时间');
					$objActSheet->setCellValue('Q1', '地址');
					$objActSheet->setCellValue('R1', '订单状态');
					$objActSheet->setCellValue('S1', '支付情况');
					$objActSheet->setCellValue('T1', '第三方支付流水号');
					$sql = "SELECT o.*, m.name AS merchant_name,u.nickname as username,g.name,g.s_name,m.phone as mer_phone FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";
					$result_list = D()->query($sql);

					if (!empty($result_list)) {
						$index = 2;
						foreach ($result_list as $value) {
							$objActSheet->setCellValueExplicit('A' . $index,$value['mer_id']);
							$objActSheet->setCellValueExplicit('B' . $index,$value['mer_phone']);
							$objActSheet->setCellValueExplicit('C' . $index, $value['real_orderid']);
							$objActSheet->setCellValueExplicit('D' . $index, $value['s_name']);
							$objActSheet->setCellValueExplicit('E' . $index, $value['merchant_name']);
							$objActSheet->setCellValueExplicit('F' . $index, $value['username'] . ' ');
							$objActSheet->setCellValueExplicit('G' . $index, $value['phone'] . ' ');
							$objActSheet->setCellValueExplicit('H' . $index, floatval($value['total_money']));
							$objActSheet->setCellValueExplicit('I' . $index, floatval($value['balance_pay']));
							$objActSheet->setCellValueExplicit('J' . $index, floatval($value['merchant_balance']));
							$objActSheet->setCellValueExplicit('Q' . $index, floatval($value['payment_money']));
							$objActSheet->setCellValueExplicit('L' . $index, floatval($value['score_reducte']));
							$objActSheet->setCellValueExplicit('M' . $index, floatval($value['coupon_price']));
							$objActSheet->setCellValueExplicit('N' . $index, floatval($value['card_price']));
							$objActSheet->setCellValueExplicit('O' . $index, floatval($value['card_discount'])?floatval($value['card_discount']). '折':'');
							$objActSheet->setCellValueExplicit('P' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
							$objActSheet->setCellValueExplicit('Q' . $index, $value['adress'] . '');
							$objActSheet->setCellValueExplicit('R' . $index, $this->get_order_status($value));
							$objActSheet->setCellValueExplicit('S' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));
							$objActSheet->setCellValueExplicit('T' . $index,$value['third_id']);
							$index++;
						}
					}
					sleep(2);
				}
				break;
			case 'appoint':
				$title = C('config.appoint_alias_name').'账单';
				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);
				if($param['appoint_id']>0){
					$where['a.appoint_id'] = intval($param['appoint_id']);
				}
				$where['a.mer_id'] = array('neq',0);
				if(!empty($param['keyword'])){
					if ($param['searchtype'] == 'order_id') {
						$where['a.order_id'] = $param['keyword'];
					} elseif ($param['searchtype'] == 'orderid') {
						$where['orderid'] = htmlspecialchars($param['keyword']);
						$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
						unset($where['orderid']);
						$where['a.order_id'] = $tmp_result['order_id'];
					}elseif ($param['searchtype'] == 'name') {
						$where['u.username'] = htmlspecialchars($param['keyword']);
					} elseif ($param['searchtype'] == 'phone') {
						$where['u.phone'] = htmlspecialchars($param['keyword']);
					}elseif ($param['searchtype'] == 'third_id') {
						$where['a.third_id'] =$param['keyword'];
					}
				}
				if ($param['merchant_session']) {
					$where['a.mer_id'] = $param['merchant_session']['mer_id'];
				}
				if ($param['store_session']) {
					$where['a.store_id'] = $param['store_session']['store_id'];
					unset($where['a.appoint_id']);
					//$condition_where = "WHERE o.store_id = " . $param['store_session']['store_id'];
				}
				$pay_type = isset($param['pay_type']) && $param['pay_type'] ? $param['pay_type'] : '';
				if($pay_type){
					if($pay_type=='balance'){
						$where['_string'] = "(`a`.`balance_pay`<>0 OR `a`.`merchant_balance` <> 0 OR a.product_merchant_balance <> 0 OR a.product_balance_pay <> 0 )";
					}else{
						$where['a.pay_type'] = $pay_type;
					}
				}
				$database_appoint = D('Appoint');
				$database_order = D('Appoint_order');
				$now_appoint = $database_appoint->field(true)->where(array('appoint_id'=>$param['appoint_id']))->find();
				if(!empty($param['begin_time'])&&!empty($param['end_time'])){
					if ($param['begin_time']>$param['end_time']) {
						return false;
					}
					$period = array(strtotime($param['begin_time']." 00:00:00"),strtotime($param['end_time']." 23:59:59"));
					$where['_string'] .= (empty($where['_string'])?'':' AND ')." (a.order_time BETWEEN ".$period[0].' AND '.$period[1].")";
				}
				$order_count = $database_order->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
						->where($where)->count();
				$length = ceil($order_count / 1000);
				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);

					$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
					$objActSheet = $objExcel->getActiveSheet();

					$objActSheet->setCellValue('A1', '订单编号');
					$objActSheet->setCellValue('B1', '定金');
					$objActSheet->setCellValue('C1', '总价');
					$objActSheet->setCellValue('D1', '类型');
					$objActSheet->setCellValue('E1', '用户昵称');
					$objActSheet->setCellValue('F1', '手机号码');
					$objActSheet->setCellValue('G1', '订单状态');
					$objActSheet->setCellValue('H1', '服务状态');
					$objActSheet->setCellValue('I1', '平台余额支付');
					$objActSheet->setCellValue('J1', '商家会员卡支付');
					$objActSheet->setCellValue('K1', '在线支付金额');
					$objActSheet->setCellValue('L1', '下单时间');
					$objActSheet->setCellValue('M1', '支付时间');
					$objActSheet->setCellValue('N1', '支付方式');
					$order_list = $database_order->field('a.*,ap.appoint_name,a.appoint_type,a.mer_id,u.uid,u.nickname,a.order_time,a.pay_time,u.phone,s.name as store_name,s.adress as store_adress')
							->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
							->where($where)->limit(($i*1000).',1000')->order('`order_id` DESC')->select();
					$result_list = $order_list;

					if (!empty($result_list)) {
						$index = 2;
						foreach ($result_list as $value) {
							$objActSheet->setCellValueExplicit('A' . $index, $value['order_id']);
							if($value['product_id']>0){
								$objActSheet->setCellValueExplicit('B' . $index,floatval($value['product_payment_price']));
							}else{
								$objActSheet->setCellValueExplicit('B' . $index, floatval($value['payment_money']));
							}
							if($value['product_price']>0){
								$objActSheet->setCellValueExplicit('C' . $index,floatval($value['product_price']));
							}else{
								$objActSheet->setCellValueExplicit('C' . $index, floatval($value['appoint_price']));
							}
							if($value['type']==1){
								$objActSheet->setCellValueExplicit('D' . $index, '自营');
							}else{
								$objActSheet->setCellValueExplicit('D' . $index, '商家');
							}
							$objActSheet->setCellValueExplicit('E' . $index, $value['nickname'] . ' ');
							$objActSheet->setCellValueExplicit('F' . $index, $value['phone'] . ' ');
							if($value['paid']==0){
								$objActSheet->setCellValueExplicit('G' . $index, '未支付');
							}elseif($value['paid']==1){
								$objActSheet->setCellValueExplicit('G' . $index, '已支付');
							}elseif($value['paid']==2){
								$objActSheet->setCellValueExplicit('G' . $index, '已退款');
							}
							if($value['service_status']==1){
								$objActSheet->setCellValueExplicit('H' . $index, '未服务');
							}elseif($value['service_status']==2){
								$objActSheet->setCellValueExplicit('H' . $index, '已服务');
							}elseif($value['service_status']==3){
								$objActSheet->setCellValueExplicit('H' . $index, '已评价');
							}
							$objActSheet->setCellValueExplicit('I' . $index, floatval($value['balance_pay']));
							$objActSheet->setCellValueExplicit('J' . $index, floatval($value['merchant_balance']));
							$objActSheet->setCellValueExplicit('K' . $index, floatval($value['pay_money']));
							$objActSheet->setCellValueExplicit('L' . $index, $value['order_time'] ? date('Y-m-d H:i:s', $value['order_time']) : '');
							$objActSheet->setCellValueExplicit('M' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
							$objActSheet->setCellValueExplicit('N' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));


							$index++;
						}
					}
					sleep(2);
				}
				break;
			case 'shop':
				$title =  C('config.shop_alias_name').'账单';
				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);
				$where_store = null;
				if(!empty($param['keyword']) && $param['searchtype'] == 's_name'){
					$where_store['name'] = array('like', '%'.$param['keyword'].'%');
				}

				if ($param['system_session']['area_id']) {
					$now_area = D('Area')->field(true)->where(array('area_id' => $param['system_session']['area_id']))->find();
					if($now_area['area_type']==3){
						$area_index = 'area_id';
					}elseif($now_area['area_type']==2){
						$area_index = 'city_id';
					}elseif($now_area['area_type']==1){
						$area_index = 'province_id';
					}
					$where_store[$area_index] = $param['system_session']['area_id'];
				}
			
				if ($param['area_id'] && $param['area_id']!='undefined') {
					$where_store['area_id'] = $param['area_id'];
				} else if ($param['city_idss'] && $param['city_idss']!='undefined') {
					$where_store['city_id'] = $param['city_idss'];
				} else if ($param['province_idss'] && $param['province_idss']!='undefined') {
					$where_store['province_id'] = $param['province_idss'];
				}

				$store_ids = array();
				$where = array('platform' => 0);
				$condition_where = 'WHERE `o`.`platform`=0';
				if ($where_store) {
					$stores = D('Merchant_store')->field('store_id')->where($where_store)->select();
					foreach ($stores as $row) {
						$store_ids[] = $row['store_id'];
					}
					if ($store_ids) {
						$where['store_id'] = array('in', $store_ids);
						$condition_where .= ' AND o.store_id IN ('.implode(',',$store_ids).')';
					}
				}


				if(!empty($param['keyword'])){
					if ($param['searchtype'] == 'real_orderid') {
						$where['real_orderid'] = htmlspecialchars($param['keyword']);
						$condition_where .= ' AND o.real_orderid = "'. htmlspecialchars($param['keyword']).'"';
					} elseif ($param['searchtype'] == 'orderid') {
						$where['orderid'] = htmlspecialchars($param['keyword']);
						$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
						unset($where['orderid']);
						$where['order_id'] = $tmp_result['order_id'];
						$condition_where .= ' AND o.order_id = '. $tmp_result['order_id'];
					} elseif ($param['searchtype'] == 'name') {
						$where['username'] = htmlspecialchars($param['keyword']);
						$condition_where .=  ' AND o.username = "'.  htmlspecialchars($param['keyword']).'"';
					} elseif ($param['searchtype'] == 'phone') {
						$where['userphone'] = htmlspecialchars($param['keyword']);
						$condition_where .= ' AND o.userphone = "'.  htmlspecialchars($param['keyword']).'"';
					}elseif ($param['searchtype'] == 'third_id') {
						$where['third_id'] =$param['keyword'];
						$condition_where .= ' AND o.third_id = "'.  $param['keyword'].'"';
					}

				}
				if ($param['merchant_session']) {
					$where['mer_id'] =  $param['merchant_session']['mer_id'];
					$condition_where.=' AND o.mer_id = '.$param['store_session']['mer_id'];
				}
				if ($param['store_session']) {
					$where['store_id'] = $param['store_session']['store_id'];
					$condition_where.=' AND o.store_id = '.$param['store_session']['store_id'];
				}


				$status = isset($param['status']) ? intval($param['status']) : -1;
				if ($status != -1) {
					$where['status'] = $status;
					if ($status === 0) {
						$where['paid'] = 1;
						$condition_where .= ' AND o.paid = 1 ';
					}
				}

				$type = isset($param['type']) && $param['type'] ? $param['type'] : '';
				$sort = isset($param['sort']) && $param['sort'] ? $param['sort'] : '';
				$pay_type = isset($param['pay_type']) && $param['pay_type'] ? $param['pay_type'] : '';
				if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
				if ($type != 'price' && $type != 'pay_time') $type = '';

				if ($status == 11) {
					$where['status'] = 2;
					$where['is_apply_refund'] = 1;
					$condition_where .= ' AND o.status=2 AND o.is_apply_refund=1';
				} elseif($status == 100){
					$where['paid'] = 0;
					$condition_where .= ' AND o.paid=0';
				}else if ($status != -1) {
					$where['status'] = $status;
					$condition_where .= ' AND o.status='.$status;
				}

				if($pay_type&&$pay_type!='balance'){
					$where['pay_type'] = $pay_type;
					$condition_where .= ' AND o.pay_type="'.$pay_type.'"';
				}else if($pay_type=='balance'){
					$where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 )";
					$condition_where .= ' AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )';
				}

				if(!empty($param['begin_time'])&&!empty($param['end_time'])){
					if ($param['begin_time']>$param['end_time']) {
						return false;
					}
					$period = array(strtotime($param['begin_time']." 00:00:00"),strtotime($param['end_time']." 23:59:59"));
					$where['_string'] .=( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
					$condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
				}
				$sql_count  = "SELECT count(*) as count FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id INNER JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` ".$condition_where;
				$count  = M()->query($sql_count);
				$count = $count[0]['count'];
//				$count = D('Shop_order')->where($where)->count();

				$length = ceil($count / 1000);
				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);
					$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千条订单信息');
					$objActSheet = $objExcel->getActiveSheet();
					$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(40);
					$objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('X')->setWidth(20);

					$objActSheet->setCellValue('A1', '订单编号');
					$objActSheet->setCellValue('B1', '商品名称');
					$objActSheet->setCellValue('C1', '商品进价');
					$objActSheet->setCellValue('D1', '单价');
					$objActSheet->setCellValue('E1', '单位');
					$objActSheet->setCellValue('F1', '规格/属性');
					$objActSheet->setCellValue('G1', '数量');
					$objActSheet->setCellValue('H1', '商家名称');
					$objActSheet->setCellValue('I1', '店铺名称');
					$objActSheet->setCellValue('J1', '客户姓名');
					$objActSheet->setCellValue('K1', '客户电话');
					$objActSheet->setCellValue('L1', '客户地址');
					$objActSheet->setCellValue('M1', '订单总价');
					$objActSheet->setCellValue('N1', '平台优惠');
					$objActSheet->setCellValue('O1', '商家优惠');
					$objActSheet->setCellValue('P1', '实付总价');
					$objActSheet->setCellValue('Q1', '在线支付金额');
					$objActSheet->setCellValue('R1', '平台配送费');
					$objActSheet->setCellValue('S1', '商户配送费');
					$objActSheet->setCellValue('T1', '支付时间');
					$objActSheet->setCellValue('U1', '送达时间');
					$objActSheet->setCellValue('V1', '订单状态');
					$objActSheet->setCellValue('W1', '支付情况');
					$objActSheet->setCellValue('X1', '第三方流水号');

					$sql = "SELECT  o.*, m.name AS merchant_name,d.name as good_name,d.price as good_price ,d.spec,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id INNER JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";

					$result_list = D()->query($sql);

					$tmp_id = 0;
					if (!empty($result_list)) {
						$index = 1;
						foreach ($result_list as $value) {
							if($tmp_id == $value['real_orderid']){
								$objActSheet->setCellValueExplicit('A' . $index, '');
								$objActSheet->setCellValueExplicit('B' . $index, $value['good_name']);
								$objActSheet->setCellValueExplicit('C' . $index, $value['cost_price']);
								$objActSheet->setCellValueExplicit('D' . $index, $value['good_price']);
								$objActSheet->setCellValueExplicit('E' . $index, $value['unit']);
								$objActSheet->setCellValueExplicit('F' . $index, $value['spec']);
								$objActSheet->setCellValueExplicit('G' . $index, $value['good_num']);
								$objActSheet->setCellValueExplicit('H' . $index,'');
								$objActSheet->setCellValueExplicit('I' . $index,'');
								$objActSheet->setCellValueExplicit('J' . $index, '');
								$objActSheet->setCellValueExplicit('K' . $index, '');
								$objActSheet->setCellValueExplicit('L' . $index, '');
								$objActSheet->setCellValueExplicit('M' . $index, '');
								$objActSheet->setCellValueExplicit('N' . $index, '');
								$objActSheet->setCellValueExplicit('O' . $index, '');
								$objActSheet->setCellValueExplicit('P' . $index, '');
								$objActSheet->setCellValueExplicit('Q' . $index, '');
								$objActSheet->setCellValueExplicit('R' . $index, '');
								$objActSheet->setCellValueExplicit('S' . $index, '');
								$objActSheet->setCellValueExplicit('T' . $index, '');
								$objActSheet->setCellValueExplicit('U' . $index, '');
								$objActSheet->setCellValueExplicit('V' . $index, '');
								$objActSheet->setCellValueExplicit('W' . $index, '');
								$objActSheet->setCellValueExplicit('X' . $index, '');
								$index++;
							}else{
								$index++;
								$objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
								$objActSheet->setCellValueExplicit('B' . $index, $value['good_name']);
								$objActSheet->setCellValueExplicit('C' . $index, $value['cost_price']);
								$objActSheet->setCellValueExplicit('D' . $index, $value['good_price']);
								$objActSheet->setCellValueExplicit('E' . $index, $value['unit']);
								$objActSheet->setCellValueExplicit('F' . $index, $value['spec']);
								$objActSheet->setCellValueExplicit('G' . $index, $value['good_num']);
								$objActSheet->setCellValueExplicit('H' . $index, $value['merchant_name']);
								$objActSheet->setCellValueExplicit('I' . $index, $value['store_name']);
								$objActSheet->setCellValueExplicit('J' . $index, $value['username']);
								$objActSheet->setCellValueExplicit('K' . $index, $value['userphone'] . ' ');
								$objActSheet->setCellValueExplicit('L' . $index, $value['address'] . ' ');
								$objActSheet->setCellValueExplicit('M' . $index, floatval($value['total_price']));
								$objActSheet->setCellValueExplicit('N' . $index, floatval($value['balance_reduce']));
								$objActSheet->setCellValueExplicit('O' . $index, floatval($value['merchant_reduce']));
								$objActSheet->setCellValueExplicit('P' . $index, floatval($value['price']));
								$objActSheet->setCellValueExplicit('Q' . $index, floatval($value['payment_money']));


								if($value['is_pick_in_store']  == 0){
									$objActSheet->setCellValueExplicit('R' . $index, floatval($value['freight_charge']));
								}else{
									$objActSheet->setCellValueExplicit('R' . $index, 0);
								}

								if($value['is_pick_in_store']  == 1){
									$objActSheet->setCellValueExplicit('S' . $index, floatval($value['freight_charge']));
								}else{
									$objActSheet->setCellValueExplicit('S' . $index, 0);
								}

								$objActSheet->setCellValueExplicit('T' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
								$objActSheet->setCellValueExplicit('U' . $index, $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '');
								$objActSheet->setCellValueExplicit('V' . $index, D('Shop_order')->status_list[$value['status']]);
								$objActSheet->setCellValueExplicit('W' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));
								$objActSheet->setCellValueExplicit('X' . $index, $value['third_id']);
								$index++;
							}
							$tmp_id = $value['real_orderid'];

						}
					}
					sleep(2);
				}
				break;
			case 'income':
				$title = '收入明细';
				$type = $param['type'];
				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);
				$objExcel->getActiveSheet()->setTitle($type);
				$objActSheet = $objExcel->getActiveSheet();
				$cell_income   = array(
						'store_name' => '店铺名称',
						'type' => '类型',
						'order_id' => '订单编号',
						'num' => '数量',
						'total_money' => '订单总价',
						'goods_money' => '商品价格',
						'score_deducte' => '积分抵扣',
						'coupon_price' => '系统优惠券抵扣',
						'card_price' => '商家优惠券抵扣',
						'no_bill_money' => '不参与对账金额',
						'freight_charge'=>'运费',
						'merchant_reduce'=>'商家优惠',
						'balance_reduce'=>'平台优惠',
						'money' => '金额',
						'system_take' => '系统抽成',
						'score' => '送出' . C('config.score_name') ,
						'score_count' => C('config.score_name') . '使用数量',
						'use_time' => '记账时间',
						'desc' => '描述'
				);
				$cell_name = 'cell_'.$type;
				$cell_count = count($$cell_name);
				$cell_start = 1;
				for($f='A';$f<='Z';$f++,$cell_start++){
					if($cell_start>$cell_count){
						break;
					}
					$col_char[]=$f;
				}
				$col_k=0;
				$title_index=1;
				$i = 2;
				if($type=='income'){
					$title_index=3;
					$i = 4;
				}
				foreach($$cell_name as $key=>$v){
					$objActSheet->getColumnDimension($col_char[$col_k])->setWidth(20);
					$objActSheet->setCellValue($col_char[$col_k].$title_index, $v);
					$col_k++;
				}
				$mer_id = $param['mer_id'];
				$where['m.mer_id'] = $mer_id;
				if($param['order_type']&&$param['order_type']!='all'){
					$where['type']=$param['order_type'];
				}
				if($param['order_id']){
					$where['order_id']=$param['order_id'];
				}
				if($param['store_id']){
					$where['m.store_id']=$param['store_id'];
				}


				if(isset($param['begin_time'])&&isset($param['end_time'])&&!empty($param['begin_time'])&&!empty($param['end_time'])){
					if ($param['begin_time']>$param['end_time']) {
						$this->error("结束时间应大于开始时间");
					}
					$period = array(strtotime($param['begin_time']." 00:00:00"),strtotime($param['end_time']." 23:59:59"));

					$time_condition=" (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
					$where['_string']=$time_condition;
				}
				$result = M('Merchant_money_list')->join('as m left join '.C('DB_PREFIX').'merchant_store s ON m.store_id = s.store_id')->field('m.type,m.total_money,(m.system_take+pow(-1,m.income+1)*m.money) as goods_money,(m.total_money-m.system_take-pow(-1,m.income+1)*m.money) as no_bill_money,m.system_take,m.order_id,m.num,pow(-1,m.income+1)*m.money as money,m.use_time,m.desc,m.score,m.score_count,s.name as store_name')->where($where)->order('use_time DESC')->select();



				$total_where = array_merge($where,array('m.mer_id'=>$mer_id,'m.income'=>1));
				$total = M('Merchant_money_list')->join('as m left join '.C('DB_PREFIX') . 'merchant mm ON m.mer_id = mm.mer_id left join '.C('DB_PREFIX') . 'merchant_store s ON m.store_id = s.store_id ')->where($total_where)->order('use_time DESC')->sum('m.money');

				$income_total_where = array_merge($where,array('m.mer_id'=>$mer_id,'m.income'=>1,'m.type'=>array('neq','merrecharge')));
				$income_total = M('Merchant_money_list')->join('as m left join '.C('DB_PREFIX') . 'merchant mm ON mm.mer_id = m.mer_id left join '.C('DB_PREFIX') . 'merchant_store s ON m.store_id = s.store_id ')->where($income_total_where)->order('use_time DESC')->sum('m.money');

				if($type=='income'){
					$objActSheet->setCellValue('A1', '总计 ');
					$objActSheet->setCellValue('B1', $total);
					$objActSheet->setCellValue('C1', '消费收入');
					$objActSheet->setCellValue('D1', $income_total);
				}

				$alias_name = $this->get_alias_name();
				foreach ($result as $row) {
					$col_k=0;
					switch($row['type']){
						case 'group':
							$now_order = D('Group_order')->field('score_deducte,coupon_price,card_price')->where(array('real_orderid'=>$row['order_id']))->find();
							break;
						case 'shop':
							$now_order = D('Shop_order')->field('score_deducte,coupon_price,card_price,freight_charge,merchant_reduce,balance_reduce')->where(array('real_orderid'=>$row['order_id']))->find();
							break;
						case 'meal':
							$now_order = D('Plat_order')->join('as p LEFT JOIN '.C('DB_PREFIX').'foodshop_order as f ON f.order_id = p.order_id')->field('system_coupon_price as coupon_price,merchant_coupon_price as card_price,system_score_money as score_deducte')
									->where(array(
											'business_type'=>'foodshop',
											'f.real_orderid'=>$row['order_id'],
											'_string'=>'system_coupon_price<>0 OR merchant_coupon_price<>0 OR system_score_money <>0'
									))
									->find();
							if(empty($now_order)){
								$now_order['score_deducte']=  0;
								$now_order['coupon_price']=  0;
								$now_order['card_price']=  0;
							}
							break;
						case 'store':
							$now_order = D('Store_order')->field('score_deducte,coupon_price,card_price')->where(array('order_id'=>$row['order_id']))->find();
							break;
						case 'appoint':
							$now_order = D('Appoint_order')->field('score_deducte,coupon_price,card_price')->where(array('order_id'=>$row['order_id']))->find();
							break;

					}
					// dump($$cell_name);
					foreach($$cell_name as $k=>$vv){
						//dump($k);
						switch($k){
							case 'type':
								$objActSheet->setCellValue($col_char[$col_k] . $i, $alias_name[$row[$k]].' ');
								break;
							case 'order_id':
								$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
								break;
							case 'real_orderid':
								$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
								break;
							case 'orderid':
								$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
								break;
							case 'money':
								if($type=='withdraw'){
									$objActSheet->setCellValue($col_char[$col_k] . $i, floatval($row[$k]/100));
								}else{
									$objActSheet->setCellValue($col_char[$col_k] . $i, floatval($row[$k]));
								}
								break;
							case 'status':
								if($type=='withdraw'){
									$row[$k]==0 && $row[$k] = '审核中';
									$row[$k]==1 && $row[$k] = '已通过';
									$row[$k]==2 && $row[$k] = '被驳回';
									$row[$k]==3 && $row[$k] = '已提现';
									$row[$k]==4 && $row[$k] = '审核中';
								}
								$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
								break;
							case 'pay_time':
							case 'use_time':
							case 'withdraw_time':
								$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
								break;
							case 'desc':
							case 'remark':
							case 'name':
								//dump( $row[$k]);die;
								$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
								break;
							case 'score_deducte':
								$objActSheet->setCellValue($col_char[$col_k] . $i,$now_order['score_deducte'].' ');
								break;
							case 'coupon_price':
								$objActSheet->setCellValue($col_char[$col_k] . $i, $now_order['coupon_price'].' ');
								break;
							case 'card_price':
								$objActSheet->setCellValue($col_char[$col_k] . $i, $now_order['card_price'].' ');
								break;
							case 'freight_charge':
								$objActSheet->setCellValue($col_char[$col_k] . $i, $now_order['freight_charge'].' ');
								break;
							case 'merchant_reduce':
								$objActSheet->setCellValue($col_char[$col_k] . $i, $now_order['merchant_reduce'].' ');
								break;
							case 'balance_reduce':
								$objActSheet->setCellValue($col_char[$col_k] . $i, $now_order['balance_reduce'].' ');
								break;
							default:
								$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]);
								break;
						}
						$col_k++;
					}
					if($type!='income' && $type!='withdraw'){
						$objActSheet->setCellValue($col_char[$cell_count-1] . $i, $row['balance_pay']+$row['coupon_price']+$row['score_deducte']+$row['payment_money']);
					}
					$i++;
				}
				break;
			case 'user':
				$title = '用户信息';
				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);
				$database_user = D('User');

				if (!empty($param['keyword'])) {
					if ($param['searchtype'] == 'uid') {
						$condition_user['uid'] = $param['keyword'];
					} else if ($param['searchtype'] == 'nickname') {
						$condition_user['nickname'] = array('like', '%' . $param['keyword'] . '%');
					} else if ($param['searchtype'] == 'phone') {
						$condition_user['phone'] = array('like', '%' . $param['keyword'] . '%');
					}
				}

				$condition_user['openid'] = array('notlike','%no_use');
				$order_string = '`uid` DESC';

				$condition_user['status'] = array('neq',4);
				if ($param['status'] != '') {
					$condition_user['status']	=	$param['status'];
				}
				if($param['level']>0){
					$condition_user['level']	=	$param['level'];
				}

				if(!empty($param['begin_time'])&&!empty($param['end_time'])){
					if ($param['begin_time']>$param['end_time']) {
						return false;
					}
					$period = array(strtotime($param['begin_time']." 00:00:00"),strtotime($param['end_time']." 23:59:59"));
					$condition_user['_string'] =" (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
				}

				$count_user = $database_user->where($condition_user)->count();
				$length = ceil($count_user/2000);
				import('ORG.Net.IpLocation');
				$client =  array(0=>'WAP端',1=>'苹果',2=>'安卓',3=>'电脑',4=>'小程序',5=>'微信',6=>'支付宝','7'=>'微信扫描商家二维码');
				$IpLocation = new IpLocation();
				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);

					$objExcel->getActiveSheet()->setTitle('用户分页'.$i);
					$objActSheet = $objExcel->getActiveSheet();

					$objActSheet->setCellValue('A1', '用户ID');
					$objActSheet->setCellValue('B1', '昵称');
					$objActSheet->setCellValue('C1', '真实姓名');
					$objActSheet->setCellValue('D1', '手机号');
					$objActSheet->setCellValue('E1', '性别 (男； 女； 其他)');
					$objActSheet->setCellValue('F1', '省份');
					$objActSheet->setCellValue('G1', '城市');
					$objActSheet->setCellValue('H1', 'QQ');
					$objActSheet->setCellValue('I1', '注册时间');
					$objActSheet->setCellValue('J1', '注册IP');
					$objActSheet->setCellValue('K1', '最后登录时间');
					$objActSheet->setCellValue('L1', '最后登录地址');
					$objActSheet->setCellValue('M1', C('config.score_name'));
					$objActSheet->setCellValue('N1', '余额');
					$objActSheet->setCellValue('O1', '不可提现的余额');
					$objActSheet->setCellValue('P1', '是否手机认证');
					$objActSheet->setCellValue('Q1', '是否关注公众号');
					$objActSheet->setCellValue('R1', '账号是否正常');
					$objActSheet->setCellValue('S1', '用户来源');

					$user_list = $database_user->where($condition_user)->field(true)->limit($i * 2000 . ',2000')->select();
					if (!empty($user_list)) {

						$index = 2;
						foreach ($user_list as $value) {

							$objActSheet->setCellValueExplicit('A' . $index, $value['uid']);
							$objActSheet->setCellValueExplicit('B' . $index, $value['nickname']);
							$objActSheet->setCellValueExplicit('C' . $index, $value['truename']);
							$objActSheet->setCellValueExplicit('D' . $index, $value['phone'] . ' ');
							$sex = $value['sex'] == 0 ? '未知' : ($value['sex'] == 1 ? '男' : '女');
							$objActSheet->setCellValueExplicit('E' . $index, $sex);

							$objActSheet->setCellValueExplicit('F' . $index, $value['province']);
							$objActSheet->setCellValueExplicit('G' . $index, $value['city']);
							$objActSheet->setCellValueExplicit('H' . $index, $value['qq'] . ' ');
							$objActSheet->setCellValueExplicit('I' . $index, date('Y-m-d H:i:s', $value['add_time']));

							$last_location = $IpLocation->getlocation(long2ip($value['add_ip']));
							$add_ip = iconv('GBK', 'UTF-8', $last_location['country']);
							$objActSheet->setCellValueExplicit('J' . $index, $add_ip);

							$objActSheet->setCellValueExplicit('K' . $index, date('Y-m-d H:i:s', $value['last_time']));

							$last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
							$last_ip = iconv('GBK', 'UTF-8', $last_location['country']);
							$objActSheet->setCellValueExplicit('L' . $index, $last_ip);

							$objActSheet->setCellValueExplicit('M' . $index, $value['score_count'] . ' ');
							$objActSheet->setCellValueExplicit('N' . $index, $value['now_money'] . ' ');
							$objActSheet->setCellValueExplicit('O' . $index, $value['score_recharge_moeny'] . ' ');
							$is_check_phone = $value['is_check_phone'] == 0 ? '否' : '是';
							$objActSheet->setCellValueExplicit('P' . $index, $is_check_phone);
							$is_follow = $value['is_follow'] ? '是' : '否';
							$objActSheet->setCellValueExplicit('Q' . $index, $is_follow);
							$status = $value['status'] ? '正常' : '禁用';
							$objActSheet->setCellValueExplicit('R' . $index, $status);

							if(strpos($value['source'],'weixin')===0){
								$tmp_client = $client[5];
							}elseif(strpos($value['source'],'wxapp')===0){
								$tmp_client = $client[4];
							}elseif(strpos($value['source'],'wap')===0){
								$tmp_client = $client[0];
							}elseif(strpos($value['source'],'alipay')===0){
								$tmp_client = $client[6];
							}elseif($value['client'] == 2 || strpos($value['source'],'androidapp')===0){
								$tmp_client = $client[2];
							}elseif($value['client'] == 1 || strpos($value['source'],'iosapp')===0){
								$tmp_client = $client[1];
							}elseif($value['client'] == 1 || strpos($value['source'],'scan_mer_qrcode')===0){
								$tmp_client = $client[7];
							}else{
								$tmp_client = $client[3];
							}
							$objActSheet->setCellValueExplicit('S' . $index,$tmp_client);
							$index++;
						}
					}
					sleep(2);
				}
				break;
			case 'express':

				$title = '配送信息';

				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);

				if($param['order_id']){
					$where['order_id'] = $param['order_id'];
				}

				$where['mer_id']=$param['mer_id'];
				$where['type']=0;
				$where['item']=2;


				if(isset($param['begin_time'])&&isset($param['end_time'])&&!empty($param['begin_time'])&&!empty($param['end_time'])){
					if ($param['begin_time']>$param['end_time']) {
						return false;
					}
					$period = array(strtotime($param['begin_time']." 00:00:00"),strtotime($param['end_time']." 23:59:59"));
					$time_condition = " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
					$where['_string']=$time_condition;

				}

				$mer_id = $param['mer_id'];

				$count = M('Deliver_supply')->where( $where)->count();





				unset($where['mer_id']);
				unset($where['order_id']);
				$where['l.mer_id']=$mer_id;
				$param['order_id'] && $where['l.order_id']=$param['order_id'];
				$param['begin_time'] && $where['_string']= " (l.create_time BETWEEN ".$period[0].' AND '.$period[1].")";


				$length = ceil($count[0]['count'] / 1000);
				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);

					$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个配送信息');

					$objActSheet = $objExcel->getActiveSheet();
					$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);


					$objActSheet->setCellValue('A1', '店铺名称');
					$objActSheet->setCellValue('B1', '订单号');
					$objActSheet->setCellValue('C1', '数量');
					$objActSheet->setCellValue('D1', '订单总价');
					$objActSheet->setCellValue('E1', '配送费');
					$objActSheet->setCellValue('F1', '支付时间');
					$result_list = M('Deliver_supply')->join('as l left join '.C('DB_PREFIX') . 'merchant_store ms ON l.store_id = ms.store_id LEFT JOIN '.C('DB_PREFIX').'shop_order o ON o.order_id  = l.order_id ')->field('ms.name as store_name,l.supply_id,l.order_id,l.money,o.create_time,l.uid,l.freight_charge,o.real_orderid,o.pay_time,o.num')->where($where)->order('order_id DESC')->limit( $i * 1000,1000)->select();


					if (!empty($result_list)) {
						$index = 2;
						foreach ($result_list as $value) {
							$objActSheet->setCellValueExplicit('A' . $index, $value['store_name']);
							$objActSheet->setCellValueExplicit('B' . $index, $value['real_orderid']);
							$objActSheet->setCellValueExplicit('C' . $index, $value['num'] );
							$objActSheet->setCellValueExplicit('D' . $index, $value['money']);
							$objActSheet->setCellValueExplicit('E' . $index, floatval($value['freight_charge']));
							$objActSheet->setCellValueExplicit('F' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
							$index++;
						}
					}
					sleep(2);
				}
				break;
			case 'trade':
				$title = '交易数据';
				$type = $param['type'];
				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);
				$objExcel->getActiveSheet()->setTitle($type);
				$objExcel->getActiveSheet()->mergeCells('A1:V1');
				$objExcel->getActiveSheet()->mergeCells('A3:H3');
				$objActSheet = $objExcel->getActiveSheet();
				$cell_trade = array(
						'date_id' => '日期',
						'province' => '省份',
						'city' => '市',
						'area' => '区/县',
						'platform' => '收入来源',
						'type' => '业务',
						'merchant_name' => '商家',
						'store_name' => '店铺',
						'total_money' => '营业金额',
						'total_count' => '营业单数',
						'consume_money' => '实收金额',
						'consume_count'=>'实收单数',
						'refund_money'=>'退款金额',
						'refund_count'=>'退款单数',
						'alipay_money' => '支付宝交易金额',
						'alipay_count' => '支付宝交易单数',
						'weixin_money' => '微信交易金额' ,
						'weixin_count' => '微信交易单数',
						'balance_money' => '余额支付交易金额',
						'balance_count' => '余额支付交易单数',
						'score_money' => '积分抵扣金额',
						'score_count' => '积分抵扣单数',
				);

				$cell_name = 'cell_trade';
				$cell_count = count($$cell_name);
				$cell_start = 1;
				for($f='A';$f<='Z';$f++,$cell_start++){
					if($cell_start>$cell_count){
						break;
					}
					$col_char[]=$f;
				}
				$col_k=0;
				$title_index=1;
				$i = 2;
				if($type=='trade'){
					$title_index=2;
					$i = 3;
				}
				$objActSheet->setCellValue('A1', '说明：已停用的支付方式不计入统计，如需查看，请查看具体业务下的订单记录');
				$objActSheet->setCellValue('A3', '合计');
				//$objActSheet->setCellValue('G3', '合计');


				$param['type'] = $param['order_type'];
				$mer_list = $param['mer_list'];
				$store_list = $param['store_list'];
				$staff_list = $param['staff_list'];
				$type = $param['type'];
				$pay_type = $param['pay_type'];
				$start_time = strtotime($param['begin_time']);
				$end_time = strtotime($param['end_time'])+86400;
				if($param['selectTimeType']>0){
					$start_time = strtotime(date('Y-m-d',time()-($param['selectTimeType']-1)*86400));
					$end_time =time();
				}

				if ($param['area_id'] && $param['area_id']!='undefined') {
					$where_store['area_id'] = $param['area_id'];
				} else if ($param['city_idss'] && $param['city_idss']!='undefined') {
					$where_store['city_id'] = $param['city_idss'];
				} else if ($param['province_idss'] && $param['province_idss']!='undefined') {
					$where_store['province_id'] = $param['province_idss'];
				}
				if($where_store){
					$store_ids = M('Merchant_store')->where($where_store)->select();

					foreach ($store_ids as $st) {
						$store_list_id[] = $st['store_id'];
					}
					if($store_list_id){
						$where['store_id']= array('in',$store_list_id);
					}else{
						$where['store_id']= -2; // 区域下没有店铺信息 给一个不能查询的店铺
					}
				}

				if($type=='shop' && $param['platform']){
					$where['platform']  = $param['platform'];
				}

				if($end_time<$start_time){
					return array('error_code'=>1,'msg'=>'结束时间不能比开始时间小');
				}
				$param['mer_id'] && $where['mer_id'] = $param['mer_id'];

				if($type=='meal'){
					$Order = 'Foodshop_order';
				}else{
					$where['paid'] = 1;
					$Order = ucfirst($type).'_order';
				}

				if($mer_list && $mer_list!=-1){
					$where['mer_id']= array('in',$mer_list);
				}

				if($store_list && $store_list!=-1 ){
					$where['store_id']= array('in',$store_list);
				}

				if($staff_list && $staff_list!=-1 ){
					$where['staff_id']= array('in',$staff_list);
				}

				if($pay_type!='all'){
					if($pay_type=='balance'){
						$where['pay_type']= '';
					}elseif($pay_type=='alipay'){
						$where['pay_type']= array('like','alipay%');
					}else{
						$where['pay_type']= $param['pay_type'];
					}
				}

				if($start_time){
					$where['_string']="pay_time <= {$end_time} AND pay_time>={$start_time}  ";
				}

				//营业金额
				switch($type){
					case 'group':
						$where['status'] =array('elt',3);
						$sum_name = 'total_money';
						break;
					case 'shop':
						$where['status'] =array('elt',3);
						$sum_name = 'price';
						break;
					case 'meal':
						$where['status'] =array('in','3,4');
						$sum_name = 'total_price';
						break;
					case 'appoint':
						unset($where['paid']);
						$where['_string'] .= ' AND paid = 1 OR paid = 3';
						$sum_name = 'balance_pay+score_deducte+payment_money+merchant_balance+product_score_deducte+product_balance_pay+product_coupon_price+user_pay_money+product_merchant_balance';
						break;
					case 'store':
						$sum_name = 'price';

						break;
				}

				$arr['total_money_count'] = M($Order)->where($where)->count();
				$arr['total_money_sum'] = M($Order)->where($where)->sum($sum_name);
				$objActSheet->setCellValue('I3', floatval($arr['total_money_sum']));
				$objActSheet->setCellValue('J3', floatval($arr['total_money_count']));


				//$count = M($Order)->where($where)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->count();
				$subQuery = M($Order)->field('store_id')->where($where)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->buildSql();
				$count =  M()->table($subQuery.' a')->count();


				$lenght = $count/1000;
				$merchant_arr = array();
				$store_arr = array();
				$province = array();
				$city = array();
				$area = array();
				$ailas_name = $this->get_alias_name();
				$type_name = $ailas_name[$param['type']];
				$platform_arr =array(0=>'平台','1'=>'饿了吗','2'=>'美团');
				$platform_name = $platform_arr[$param['$platform']];
				for($i=0;$i<$lenght;$i++){

					$total_money_sum_by_store = M($Order)->field("FROM_UNIXTIME(pay_time, '%Y-%m-%d') as date_id,store_id,mer_id,sum({$sum_name}) as income,count(order_id) as counts")
							->where($where)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")
							->order("FROM_UNIXTIME(pay_time, '%Y-%m-%d') DESC,store_id DESC")
							->limit($i*1000,1000)->select();
					//实收金额
					$where_ = $where;
					unset($where_['status']);
					if($where_['_string']){
						$where_['_string'].=' AND ';
					}
					switch($type){
						case 'group':
							$where_['_string'] .= '   (status = 1 OR status = 2) ';
							break;
						case 'shop':
							$where_['_string'] .= '  (status = 2 OR status = 3) ';
							break;
						case 'meal':
							$where_['_string'] .= '  (status = 3 OR status = 4) ';
							break;
						case 'appoint':
							$where_['_string'] .= '  service_status>0 ';
							break;
						case 'store':
							break;
					}
					if($i==0){
						$arr['consume_money_count'] = M($Order)->where($where_)->count();
						$arr['consume_money_sum'] = M($Order)->where($where_)->sum($sum_name);
						$objActSheet->setCellValue('K3', floatval($arr['consume_money_sum']));
						$objActSheet->setCellValue('L3', floatval($arr['consume_money_count']));
					}

					$consume_money_count_store =M($Order)->where($where_)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->field("FROM_UNIXTIME(pay_time, '%Y-%m-%d') as date_id ,store_id,count(order_id) as consume_count,sum({$sum_name}) as consume_money")->select();

					foreach ($consume_money_count_store as $v) {
						$cosumen_count_tmp[$v['date_id']][$v['store_id']] = $v['consume_count'];
						$cosumen_money_tmp[$v['date_id']][$v['store_id']] = $v['consume_money'];
					}

					$where_['pay_type'] = 'alipay';
					if($i==0){
						$arr['alipay_money_count'] = M($Order)->where($where_)->count();
						$arr['alipay_money_sum'] = M($Order)->where($where_)->sum($sum_name);


						$objActSheet->setCellValue('O3', floatval($arr['alipay_money_sum']));
						$objActSheet->setCellValue('P3', floatval($arr['alipay_money_count']));
					}
					$alipay_money_count_store =M($Order)->where($where_)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->field("FROM_UNIXTIME(pay_time, '%Y-%m-%d') as date_id ,store_id,count(order_id) as consume_count,sum({$sum_name}) as consume_money")->select();
					foreach ($alipay_money_count_store as $v) {
						$alipay_count_tmp[$v['date_id']][$v['store_id']] = $v['consume_count'];
						$alipay_money_tmp[$v['date_id']][$v['store_id']] = $v['consume_money'];
					}

					$where_['pay_type'] = 'weixin';
					if($i==0) {
						$arr['weixin_money_count'] = M($Order)->where($where_)->count();
						$arr['weixin_money_sum'] = M($Order)->where($where_)->sum($sum_name);
						$objActSheet->setCellValue('Q3', floatval($arr['weixin_money_sum']));
						$objActSheet->setCellValue('R3', floatval($arr['weixin_money_count']));
					}
					$weixin_money_count_store =M($Order)->where($where_)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->field("FROM_UNIXTIME(pay_time, '%Y-%m-%d') as date_id ,store_id,count(order_id) as consume_count,sum({$sum_name}) as consume_money")->select();
					foreach ($weixin_money_count_store as $v) {
						$weixin_count_tmp[$v['date_id']][$v['store_id']] = $v['consume_count'];
						$weixin_money_tmp[$v['date_id']][$v['store_id']] = $v['consume_money'];
					}

					$where_['pay_type'] = '';
					if($i==0){
						$arr['balance_money_count'] = M($Order)->where($where_)->count();
						$arr['balance_money_sum'] = M($Order)->where($where_)->sum($sum_name);
						$objActSheet->setCellValue('S3', floatval($arr['balance_money_sum']));
						$objActSheet->setCellValue('T3', floatval($arr['balance_money_count']));
					}
					$balance_money_count_store =M($Order)->where($where_)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->field("FROM_UNIXTIME(pay_time, '%Y-%m-%d') as date_id ,store_id,count(order_id) as consume_count,sum({$sum_name}) as consume_money")->select();
					foreach ($balance_money_count_store as $v) {
						$balance_count_tmp[$v['date_id']][$v['store_id']] = $v['consume_count'];
						$balance_money_tmp[$v['date_id']][$v['store_id']] = $v['consume_money'];
					}

					$where_['score_deducte'] = array('gt',0);

					if($i==0) {
						$arr['score_money_count'] = M($Order)->where($where_)->count();
						$arr['score_money_sum'] = M($Order)->where($where_)->sum($sum_name);
						$objActSheet->setCellValue('U3', floatval($arr['score_money_sum']));
						$objActSheet->setCellValue('V3', floatval($arr['score_money_count']));
					}
					$balance_money_count_store =M($Order)->where($where_)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->field("FROM_UNIXTIME(pay_time, '%Y-%m-%d') as date_id ,store_id,count(order_id) as consume_count,sum(score_deducte) as consume_money")->select();
					foreach ($balance_money_count_store as $v) {
						$score_count_tmp[$v['date_id']][$v['store_id']] = $v['consume_count'];
						$score_money_tmp[$v['date_id']][$v['store_id']] = $v['consume_money'];
					}


					//退款金额
					switch($type){
						case 'group':
							$where['status'] =3;
							break;
						case 'shop':
							$where['status'] =4;
							break;
						case 'meal':
							break;
						case 'appoint':
							$where['status'] =2;
							$where['_string'] .= ' AND service_status>0 ';
							break;
						case 'store':
							$where['_string'] .= ' 1=0 ';
							break;
					}
					if($i==0) {
						$arr['refund_money_count'] = M($Order)->where($where)->count();
						$arr['refund_money_sum'] = M($Order)->where($where)->sum($sum_name);
						$objActSheet->setCellValue('M3', floatval($arr['refund_money_sum']));
						$objActSheet->setCellValue('N3', floatval($arr['refund_money_count']));

					}
					$refund_money_count_store =M($Order)->where($where)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->field("FROM_UNIXTIME(pay_time, '%Y-%m-%d') as date_id,store_id,count(order_id) as refund_count,sum({$sum_name}) as refund_money")->select();

					foreach ($refund_money_count_store as $v) {
						$refund_count_tmp[$v['date_id']][$v['store_id']] = $v['refund_count'];
						$refund_money_tmp[$v['date_id']][$v['store_id']] = $v['refund_money'];
					}

					foreach($$cell_name as $key=>$v){
						$objActSheet->getColumnDimension($col_char[$col_k])->setWidth(20);
						$objActSheet->setCellValue($col_char[$col_k].$title_index, $v);
						$col_k++;
					}



					$index= $i==0?4:2;
					foreach ($total_money_sum_by_store as &$v) {
//						$tmp['store_id'] = $v['store_id'];
//						$tmp['mer_id'] = $v['mer_id'];
						if(!$merchant_arr[$v['mer_id']]){
							$merchant_arr[$v['mer_id']] = $merchant = M('Merchant')->field('mer_id,name,province_id,city_id,area_id')->where(array('mer_id'=>$v['mer_id']))->find();
						}else{
							$merchant = $merchant_arr[$v['store_id']];
						}

						if(!$store_arr[$v['store_id']]){
							$store_arr[$v['store_id']]=$store = M('Merchant_store')->field('store_id,name,province_id,city_id,area_id')->where(array('store_id'=>$v['store_id']))->find();
						}else{
							$store = $store_arr[$v['store_id']];
						}

						if(!$province[$store['province_id']]){
							$province[$store['province_id']] = $province = M('Area')->where(array('area_id'=>$store['province_id']))->find();
						}else{
							$province = $province[$store['province_id']];
						}

						if(!$city[$store['city_id']]){
							$city[$store['city_id']] = $city = M('Area')->where(array('area_id'=>$store['city_id']))->find();
						}else{
							$city = $city[$store['city_id']];
						}

						if(!$area[$store['area_id']]){
							$area[$store['area_id']] = $area = M('Area')->where(array('area_id'=>$store['area_id']))->find();
						}else{
							$area = $area[$store['area_id']];
						}


						$v['merchant_name'] = $merchant['name'];
						$v['store_name'] = $store['name'];

						$v['province'] = $province['area_name'];
						$v['city'] = $city['area_name'];
						$v['area'] = $area['area_name'];


						$v['total_money'] = $v['income'];
						$v['total_count'] = $v['counts'];
						$v['date_id'] = $v['date_id'];
						$v['type'] = $type_name;
						$v['platform'] = $platform_name;

//
						$v['consume_money'] = floatval($cosumen_money_tmp[$v['date_id']][$v['store_id']]);
						$v['consume_count'] = floatval($cosumen_count_tmp[$v['date_id']][$v['store_id']]);

						$v['refund_money'] = floatval($refund_money_tmp[$v['date_id']][$v['store_id']]);
						$v['refund_count'] = floatval($refund_count_tmp[$v['date_id']][$v['store_id']]);

						$v['alipay_money'] = floatval($alipay_money_tmp[$v['date_id']][$v['store_id']]);
						$v['alipay_count'] = floatval($alipay_count_tmp[$v['date_id']][$v['store_id']]);

						$v['weixin_money'] = floatval($weixin_money_tmp[$v['date_id']][$v['store_id']]);
						$v['weixin_count'] = floatval($weixin_count_tmp[$v['date_id']][$v['store_id']]);

						$v['balance_money'] =floatval( $balance_money_tmp[$v['date_id']][$v['store_id']]);
						$v['balance_count'] = floatval($balance_count_tmp[$v['date_id']][$v['store_id']]);

						$v['score_money'] = floatval($score_money_tmp[$v['date_id']][$v['store_id']]);
						$v['score_count'] = floatval($score_count_tmp[$v['date_id']][$v['store_id']]);

						$col_k = 0;
						foreach($$cell_name as $k=>$vv){
							$objActSheet->setCellValueExplicit($col_char[$col_k].$index, $v[$k]);
							$col_k++;
						}
						$index++;
					}
				}
				break;
			case 'goods':

				$title = '菜品信息';
				$type = $param['type'];
				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);
				$objExcel->getActiveSheet()->setTitle($type);
				$objExcel->getActiveSheet()->mergeCells('A1:J1');

				$objActSheet = $objExcel->getActiveSheet();
				$cell_goods = array(
						'type' => '业务',
						'store_name' => '店铺',
						'rank' => '排名',
						'name' => '商品名称',
						'sort_name' => '商品类别',
						'price'=>'单价',
						'sale_count'=>'销售数量',
						'sale_money'=>'销售金额',
						'sale_percent' => '销售占比',
				);

				$cell_name = 'cell_goods';
				$cell_count = count($$cell_name);
				$cell_start = 1;
				for($f='A';$f<='Z';$f++,$cell_start++){
					if($cell_start>$cell_count){
						break;
					}
					$col_char[]=$f;
				}
				$col_k=0;
				$title_index=1;
				$i = 2;
				if($type=='goods'){
					$title_index=2;
					$i = 3;
				}
				$mer_list = $param['mer_list'];
				$store_list = $param['store_list'];
				$type = $param['order_type'];
				$start_time = strtotime($param['begin_time']);
				$end_time = strtotime($param['end_time'])+86400;
				$goods_name = $param['goods_name'];
				$type_name = $type=='meal'? C('config.meal_alias_name'):C('config.shop_alias_name');

				if($param['selectTimeType']>0){
					$start_time = strtotime(date('Y-m-d',time()-($param['selectTimeType']-1)*86400));
					$end_time =time();
					$objActSheet->setCellValue('A1', '日期：'.$param['selectTimeType'].'天内');
				}

				if ($param['area_id'] && $param['area_id']!='undefined') {
					$where_store['area_id'] = $param['area_id'];
				} else if ($param['city_idss'] && $param['city_idss']!='undefined') {
					$where_store['city_id'] = $param['city_idss'];
				} else if ($param['province_idss'] && $param['province_idss']!='undefined') {
					$where_store['province_id'] = $param['province_idss'];
				}
				if($where_store){
					$store_ids = M('Merchant_store')->where($where_store)->select();

					foreach ($store_ids as $st) {
						$store_list_id[] = $st['store_id'];
					}
					if($store_list_id){
						$where['store_id']= array('in',$store_list_id);
					}else{
						return array('error_code'=>1,'msg'=>'该地区没有数据');
					}
				}

				if($start_time){
					$objActSheet->setCellValue('A1',  '日期：'.$param['start_time'].'至'.$param['end_time']);
				}else{
					$objActSheet->setCellValue('A1',  '全部数据');
				}

				if($end_time<$start_time){
					return array('error_code'=>1,'msg'=>'结束时间不能比开始时间小');
				}
				$param['merchant_session']['mer_id'] && $where['mer_id'] = $param['merchant_session']['mer_id'];

				if($mer_list && $mer_list!=-1){
					$where['mer_id']= array('in',$mer_list);
				}

				if($store_list && $store_list!=-1 ){
					$where['store_id']= array('in',$store_list);
				}

				if($param['type_list'] && $param['type_list']!=-1){
					$where['sort_id']  =array('in',$param['type_list']);
				}

				if($goods_name){
					$where['name']=array('like',"%".$goods_name."%");
				}
				
				if($start_time){
					$where['_string']="o.create_time <= {$end_time} AND o.create_time>={$start_time}";
				}
				$listRows = 1000;
				if($type=='meal'){
					$all_sale_money = M('Foodshop_order_detail')->where($where)->sum('price');
					//$count = M('Foodshop_order_detail')->where($where)->group('goods_id')->count();
					$subQuery = M('Foodshop_order_detail')->field('goods_id')->group('goods_id')->where($where)->buildSql();
					$count =  M()->table($subQuery.' a')->count();
					if($param['sort'] && $count>$param['sort']){
						$count = $param['sort'];
					}
                } else {
                    $where_tmp = array();
                    foreach ($where as $key => &$v) {
                        if ($key != '_string') {
                            
                            if ($key != 'mer_id') {
                                $tmp['d.' . $key] = $v;
                            } else {
                                $tmp['o.' . $key] = $v;
                            }
                        } else {
                            $tmp['_string'] = $v;
                        }
                        $where_tmp[] = $tmp;
                        unset($where[$key]);
                    }
                    $where = $where_tmp;
                    $where['o.paid'] = 1;
                    $where['o.status'] = array('in', '0,1,2,3');
                    $all_sale_money = floatval(M('Shop_order_detail')->join('as d LEFT JOIN ' . C('DB_PREFIX') . 'shop_order AS o ON d.order_id = o.order_id ')->where($where)->sum('d.price'));
                    $all_sale_num = floatval(M('Shop_order_detail')->join('as d LEFT JOIN ' . C('DB_PREFIX') . 'shop_order AS o ON d.order_id = o.order_id ')->where($where)->sum('d.num'));
                    $subQuery = M('Shop_order_detail')->join('as d LEFT JOIN ' . C('DB_PREFIX') . 'shop_order AS o ON d.order_id = o.order_id ')->field('d.goods_id')->group('d.goods_id')->where($where)->buildSql();
                    
                    $count = M()->table($subQuery . ' a')->count();
                    if ($param['sort'] && $count > $param['sort']) {
                        $count = $param['sort'];
                    }
				}

				$lenght = $count/1000;
				$merchant_arr = array();
				$store_arr = array();
				$sort_arr = array();
				$rank_i = 0;
				for($i=0;$i<$lenght;$i++){
					foreach($$cell_name as $key=>$v){
						$objActSheet->getColumnDimension($col_char[$col_k])->setWidth(20);
						$objActSheet->setCellValue($col_char[$col_k].$title_index, $v);
						$col_k++;
					}

					if($type=='meal'){
						$res = M('Foodshop_order_detail')->where($where)->group('goods_id')->order('sale_money DESC')->field("goods_id,name,price,store_id,sort_id,sum(price) as sale_money ,sum(num) as sale_count,concat ( left (sum(price)/{$all_sale_money} *100,5),'%') as sale_percent")->limit($i*1000,$listRows)->select();

					}else{
					    $where['o.paid'] = 1;
					    $where['o.status'] = array('in','0,1,2,3');
					    $res = M('Shop_order_detail')->join('as d LEFT JOIN '.C('DB_PREFIX').'shop_order AS o ON d.order_id = o.order_id ')->where($where)->group('d.goods_id')->order('sale_money DESC')->field("d.goods_id,d.name,d.price,d.store_id,sum(d.price) as sale_money ,sum(d.num) as sale_count,concat ( left (sum(d.price)/{$all_sale_money} *100,5),'%') as sale_percent")->limit($i*1000,$listRows)->select();
// 						$res = M('Shop_order_detail')->where($where)->group('goods_id')->order('sale_money DESC')->field("goods_id,name,price,store_id,sort_id,sum(price) as sale_money ,sum(num) as sale_count,concat ( left (sum(price)/{$all_sale_money} *100,5),'%') as sale_percent")->limit($i*1000,$listRows)->select();
					}


					$index= $i==0?3:2;
					foreach ($res as $v) {
						$rank_i++;
						if(!$store_arr[$v['store_id']]){
							$store_arr[$v['store_id']]=$store = M('Merchant_store')->field('store_id,name,province_id,city_id,area_id')->where(array('store_id'=>$v['store_id']))->find();
						}else{
							$store = $store_arr[$v['store_id']];
						}

						$v['type'] = $type_name;
						$v['store_name'] = $store['name'];

						if($v['sort_id']>0){
							if(!$sort_arr[$v['sort_id']]){
								if($type=='meal'){
									$sort_arr[$v['sort_id']]=$sort_info = M('Foodshop_goods_sort')->where(array('sort_id'=>$v['sort_id']))->find();
								}else{
									$sort_arr[$v['sort_id']]=$sort_info = M('Shop_goods_sort')->where(array('sort_id'=>$v['sort_id']))->find();

								}
							}else{
								$sort_info = $sort_arr[$v['store_id']];
							}
							$v['sort_name'] = $sort_info['sort_name'];
						}
						$v['rank'] =$rank_i ;
						$col_k = 0;
						foreach($$cell_name as $k=>$vv){
							$objActSheet->setCellValueExplicit($col_char[$col_k].$index, $v[$k]);
							$col_k++;
						}
						$index++;
					}


				}




				break;
			case 'card_new':
				$title = '会员信息';
				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);
				$database_user = D('User');

				if (!empty($param['keyword'])) {
					if ($param['searchtype'] == 'phone') {
						$condition_user['u.phone'] = array('like', '%' . $param['keyword'] . '%');
					}
					if ($param['searchtype'] == 'card_id') {
						$condition_['c.id'] = array('like', '%' . $param['keyword'] . '%');
						$condition_['c.wx_card_code'] = array('like', '%' . $param['keyword'] . '%');
						$condition_['_logic'] = 'or';
						$condition_user['_complex'] = $condition_;
					}
					if ($param['searchtype'] == 'physical_id') {
						$condition_user['c.physical_id'] = array('like', '%' . $param['keyword'] . '%');
					}
					if ($param['searchtype'] == 'nickname') {
						$condition_user['u.nickname'] = array('like', '%' . $param['keyword'] . '%');
					}
				}

				$condition_user['c.mer_id'] = $param['merchant_session']['mer_id'];
				$count_user = M('Card_userlist')->join('as c left join '.C('DB_PREFIX').'user as u ON u.uid = c.uid')->where($condition_user)->count();

				$length = ceil($count_user/2000);
				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);

					$objExcel->getActiveSheet()->setTitle('会员分页'.$i);
					$objActSheet = $objExcel->getActiveSheet();

					$objActSheet->setCellValue('A1', '会员卡号');
					$objActSheet->setCellValue('B1', '微信卡号');
					$objActSheet->setCellValue('C1', '用户姓名');
					$objActSheet->setCellValue('D1', '用户生日');
					$objActSheet->setCellValue('E1', '用户手机');
					$objActSheet->setCellValue('F1', '会员卡余额');
					$objActSheet->setCellValue('G1', '会员卡积分');
					$objActSheet->setCellValue('H1', '实体卡号');
					$objActSheet->setCellValue('I1', '领卡时间');
					$objActSheet->setCellValue('J1', '会员卡状态');
					$user_list = M('Card_userlist')->field('c.*,u.*,c.add_time as card_add_time,c.status as card_status')->join('as c left join '.C('DB_PREFIX').'user as u ON u.uid = c.uid')->where($condition_user)->order('c.id DESC')->limit($i * 2000 . ',2000')->select();
					if (!empty($user_list)) {
						$index = 2;
						foreach ($user_list as $value) {
							$objActSheet->setCellValueExplicit('A' . $index, $value['id']);
							$objActSheet->setCellValueExplicit('B' . $index, $value['wx_card_code']);
							$objActSheet->setCellValueExplicit('C' . $index, $value['nickname']);
							$objActSheet->setCellValueExplicit('D' . $index, $value['birthday'] . ' ');
							$objActSheet->setCellValueExplicit('E' . $index, $value['phone']);
							$objActSheet->setCellValueExplicit('F' . $index, $value['card_money']+$value['card_money_give']);
							$objActSheet->setCellValueExplicit('G' . $index, $value['card_score']);
							$objActSheet->setCellValueExplicit('H' . $index, $value['physical_id'] . ' ');
							$objActSheet->setCellValueExplicit('I' . $index, $value['add_time']?date('Y-m-d H:i:s', $value['add_time']):'');
							$objActSheet->setCellValueExplicit('J' . $index, $value['status']==1?'正常':'禁止');
							$index++;
						}
					}
					sleep(2);
				}
				break;
			case 'fans':
				$title = '粉丝信息';
				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);

				$where = " `m`.`mer_id`={$param['merchant_session']['mer_id']}";

				$mode = new Model();
				$sql_count = "SELECT COUNT(1) as count FROM " . C('DB_PREFIX') . "merchant_user_relation AS m LEFT JOIN " . C('DB_PREFIX') . "user AS u ON `m`.`openid`=`u`.`openid` WHERE {$where}";

				$count_user = $mode->query($sql_count);


				$from_type=array(
						'0'=>'扫描商家产品二维码',
						'1'=>'扫描商家二维码',
						'2'=>'平台赠送',
						'3'=>'扫描产品推广二维码',
						'5'=>$this->config['group_alias_name'],
						'6'=>$this->config['shop_alias_name'],
						'7'=>$this->config['meal_alias_name'],
						'8'=>$this->config['appoint_alias_name'],
						'9'=>$this->config['cash_alias_name'],
				);

				$length = ceil($count_user[0]['count']/2000);

				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);

					$objExcel->getActiveSheet()->setTitle('用户分页'.$i);
					$objActSheet = $objExcel->getActiveSheet();

					$objActSheet->setCellValue('A1', '会员ID');
					$objActSheet->setCellValue('B1', '会员昵称');
					$objActSheet->setCellValue('C1', '手机号');
					$objActSheet->setCellValue('D1', '性别');
					$objActSheet->setCellValue('E1', '省(直辖市)');
					$objActSheet->setCellValue('F1', '城市');
					$objActSheet->setCellValue('G1', '关注时间');
					$objActSheet->setCellValue('H1', '最后登录');
					$objActSheet->setCellValue('I1', '获取渠道');
					$start = $i * 2000;
					$sql = "SELECT u.*, m.* FROM " . C('DB_PREFIX') . "merchant_user_relation AS m LEFT JOIN " . C('DB_PREFIX') . "user AS u ON `m`.`openid`=`u`.`openid` WHERE {$where} ORDER BY `m`.`dateline` DESC LIMIT  $start,2000 ";
					$user_list = $mode->query($sql);

					if (!empty($user_list)) {

						$index = 2;
						foreach ($user_list as $value) {

							$objActSheet->setCellValueExplicit('A' . $index, $value['uid']);
							$objActSheet->setCellValueExplicit('B' . $index, $value['nickname']);
							$sex = $value['sex'] == 0 ? '未知' : ($value['sex'] == 1 ? '男' : '女');
							$objActSheet->setCellValueExplicit('C' . $index,$value['phone']);
							$objActSheet->setCellValueExplicit('D' . $index,$sex);
							$objActSheet->setCellValueExplicit('E' . $index, $value['province'] . ' ');
							$objActSheet->setCellValueExplicit('F' . $index, $value['city']);
							$objActSheet->setCellValueExplicit('G' . $index,$value['dateline']?date('Y-m-d H:i:s', $value['dateline']):'');
							$objActSheet->setCellValueExplicit('H' . $index, $value['last_time']?date('Y-m-d H:i:s', $value['last_time']):'');
							$objActSheet->setCellValueExplicit('I' . $index, $from_type[$value['from_merchant']]);

							$index++;
						}
					}
					sleep(2);
				}
				break;

			case 'merchant_list':
				$param['type'] = 'merchant_list';
				$title = '商家列表';
				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);


				$database_merchant = D('Merchant');
				//搜索
				if(!empty($param['keyword'])){
					if($param['searchtype'] == 'mer_id'){
						$condition_merchant['mer_id'] = $param['keyword'];
					}else if($param['searchtype'] == 'account'){
						$condition_merchant['account'] = array('like','%'.$param['keyword'].'%');
					}else if($param['searchtype'] == 'name'){
						$condition_merchant['name'] = array('like','%'.$param['keyword'].'%');
					}else if($param['searchtype'] == 'phone'){
						$condition_merchant['phone'] = array('like','%'.$param['keyword'].'%');
					}else if($param['searchtype'] == 'store'){
						$where['name'] =  array('like','%'.htmlspecialchars($param['keyword']).'%');
						$store_list = M('Merchant_store')->field('mer_id')->where($where)->group('mer_id')->select();
						foreach ($store_list as $value) {
							$store_arr[] = $value['mer_id'];
						}
						$condition_merchant['mer_id'] = array('in',$store_arr);
					}
				}
				if ($param['system_session']['area_id']) {
					$now_area = D('Area')->field(true)->where(array('area_id' => $param['system_session']['area_id']))->find();
					if($now_area['area_type']==3){
						$area_index = 'area_id';
					}elseif($now_area['area_type']==2){
						$area_index = 'city_id';
					}elseif($now_area['area_type']==1){
						$area_index = 'province_id';
					}

					// $area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
					$condition_merchant[$area_index] = $param['system_session']['area_id'];
				}
				if($param['province_idss'] ){
					$condition_merchant['province_id'] =$param['province_idss'];
				}
				if($param['city_idss']){
					$condition_merchant['city_id'] =$where['city_id']= $param['city_idss'];
				}
				if($param['area_id']){
					$condition_merchant['area_id'] =$where['area_id']= $param['area_id'];
				}

				$searchstatus = intval($param['searchstatus']);
				switch($searchstatus){
					case 0:
						//3 是欠款状态 该状态下商户业务暂停，不能支付，但是可以管理，充值
						$condition_merchant['_string'] = 'status = 1 OR status = 3';
						break;
					case 1:
						$condition_merchant['status'] = 2;
						break;
					case 2:
						$condition_merchant['status'] = 0;
						break;
				}

				if (!isset($condition_merchant['status'])) {
					$condition_merchant['status'] = array('lt', 4);//4：假删除状态
				}
				switch($param['searchorder']){
					case 0:
						$order = 'mer_id DESC';
						break;
					case 1:
						$order = 'money DESC,mer_id DESC';
						break;

				}

				$sql_count = $database_merchant->where($condition_merchant)->count();
				$count = $sql_count;
				set_time_limit(0);
				$length = ceil($count / 1000);

				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);
					$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个商家信息');
					$objActSheet = $objExcel->getActiveSheet();
					$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

					$objActSheet->setCellValue('A1', '商家编号');
					$objActSheet->setCellValue('B1', '商家名称');
					$objActSheet->setCellValue('C1', '联系电话');
					$objActSheet->setCellValue('D1', '粉丝数');
					$objActSheet->setCellValue('E1', '账户余额');
					$objActSheet->setCellValue('F1', '店铺编号');
					$objActSheet->setCellValue('G1', '店铺名称');
					$objActSheet->setCellValue('H1', '店铺电话');
					$list = M('Merchant')->where($condition_merchant)->order($order)->limit($i*1000,1000)->select();

					if (!empty($list)) {
						$index =2;
						foreach ($list as $value) {
							$fans_count =  M('Merchant_user_relation')->where(array('mer_id'=>$value['mer_id']))->count();
							$objActSheet->setCellValueExplicit('A' . $index, $value['mer_id']);
							$objActSheet->setCellValueExplicit('B' . $index, $value['name']);
							$objActSheet->setCellValueExplicit('C' . $index,$value['phone']);
							$objActSheet->setCellValueExplicit('D' . $index,$fans_count);
							$objActSheet->setCellValueExplicit('E' . $index, $value['money']);
							$objActSheet->setCellValueExplicit('F' . $index, '');
							$objActSheet->setCellValueExplicit('G' . $index, '');
							$objActSheet->setCellValueExplicit('H' . $index, '');
							$store_list = M('Merchant_store')->where(array('mer_id'=>$value['mer_id']))->select();
							$index++;
							if($store_list){
								foreach ($store_list as $s) {
									$objActSheet->setCellValueExplicit('A' . $index, '');
									$objActSheet->setCellValueExplicit('B' . $index, '');
									$objActSheet->setCellValueExplicit('C' . $index,'');
									$objActSheet->setCellValueExplicit('D' . $index,'' );
									$objActSheet->setCellValueExplicit('E' . $index, '');
									$objActSheet->setCellValueExplicit('F' . $index, $s['store_id']);
									$objActSheet->setCellValueExplicit('G' . $index,$s['name']);
									$objActSheet->setCellValueExplicit('H' . $index, $s['phone']);
									$index++;
								}
							}
						}
					}
					sleep(2);
				}
				break;

			case 'percentage':

				$title = '抽成列表';

				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);
				$start_time = strtotime($param['start_time']);
				$end_time = strtotime($param['end_time'])+86400;
				if(!empty($param['keyword'])){
					if($param['searchtype'] == 'mer_id'){
						$condition_merchant['mer_id'] = $param['keyword'];
					}else if($param['searchtype'] == 'account'){
						$condition_merchant['account'] = array('like','%'.$param['keyword'].'%');
					}else if($param['searchtype'] == 'name'){
						$condition_merchant['name'] = array('like','%'.$param['keyword'].'%');
					}else if($param['searchtype'] == 'phone'){
						$condition_merchant['phone'] = array('like','%'.$param['keyword'].'%');
					}
				}
				if($param['area_id']){
					$condition_merchant['area_id'] = $param['area_id'];
					$where['area_id'] = $condition_merchant['area_id'];
				}
				if($param['city_id']){
					$condition_merchant['city_id'] = $param['city_id'];
					$where['city_id'] = $condition_merchant['city_id'];
				}

				if($param['province_id']){
					$condition_merchant['province_id'] = $param['province_id'];
					$where['province_id'] = $condition_merchant['province_id'];
				}
				$param['invit_code'] && $condition_merchant['invit_code'] = $param['invit_code'];

				if($end_time<$start_time){
					return array('error_code'=>1,'msg'=>'结束时间不能比开始时间小');
				}

				if(isset($param['begin_time'])&&isset($param['end_time'])&&!empty($param['begin_time'])&&!empty($param['end_time'])){
					if ($param['begin_time']>$param['end_time']) {
						return array('error_code'=>1,'msg'=>'结束时间不能比开始时间小');
					}
					$period = array(strtotime($param['begin_time']." 00:00:00"),strtotime($param['end_time']." 23:59:59"));
					$time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
					$condition_merchant['_string']=$time_condition;

				}

				$database_merchant = M('Merchant');
				$condition_merchant['area_id'] && $where['area_id'] = $condition_merchant['area_id'];
				$count_merchant = $database_merchant->where($where)->count();
				$time_condition = '';
				foreach($condition_merchant as $k=>$v){
					if($k!='_string'){
						$condition_merchant['m.'.$k] = $v;
					}else{
						$time_condition = 'WHERE '.$condition_merchant[$k];
					}
					unset($condition_merchant[$k]);
				}
				$extra_str ='';
				$extra_field ='';

				import('@.ORG.system_page');

				$length = ceil($count_merchant / 1000);
				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);
					$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个商家抽成信息');
					$objActSheet = $objExcel->getActiveSheet();
					$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);


					$objActSheet->setCellValue('A1', '编号');
					$objActSheet->setCellValue('B1', '商家名称');
					$objActSheet->setCellValue('C1', '联系电话');
					$objActSheet->setCellValue('D1', '当前商家余额');
					$objActSheet->setCellValue('E1', '送出积分');
					$objActSheet->setCellValue('F1', '抽成金额');
					$mer_percentage_list = $database_merchant->join('as m left join '.'(SELECT  mer_id,SUM(system_take) AS  money ,SUM(score) as all_score FROM '
							.C('DB_PREFIX').'merchant_money_list '.$time_condition.' GROUP BY mer_id) w ON m.mer_id = w.mer_id  '.$extra_str)
							->field('m.mer_id,m.money as now_money,m.name,m.phone,w.money,w.all_score'.$extra_field)
							->where($condition_merchant)
							->order('w.money DESC,w.all_score DESC')
							->limit( $i * 1000,1000)
							->select();

					if (!empty($mer_percentage_list)) {
						$index = 2;
						foreach ($mer_percentage_list as $value) {
							$objActSheet->setCellValueExplicit('A' . $index, $value['mer_id']);
							$objActSheet->setCellValueExplicit('B' . $index, $value['name']);
							$objActSheet->setCellValueExplicit('C' . $index, $value['phone'] );
							$objActSheet->setCellValueExplicit('D' . $index, $value['now_money']);
							$objActSheet->setCellValueExplicit('E' . $index, floatval($value['all_score']));
							$objActSheet->setCellValueExplicit('F' . $index, floatval($value['money']));
							$index++;
						}
					}
					sleep(2);
				}
				break;

			case 'admin_recharge':
				$title = '管理员充值';
				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);
				$model = M('User_money_list');
				$where['l.admin_id'] = array('neq', 0);
				if(!empty($param['admin_id'])) {
					if ($param['admin_id'] == '0') {
						$where['l.admin_id'] = array('neq', 0);
					} else{
						$where['l.admin_id'] = $param['admin_id'];
					}
				}
				if(!empty($param['begin_time'])&&!empty($param['end_time'])){
					if ($param['begin_time']>$param['end_time']) {
						$this->error_tips("结束时间应大于开始时间");
					}
					$period = array(strtotime($param['begin_time']." 00:00:00"),strtotime($param['end_time']." 23:59:59"));
					$where['_string'] =" (l.time BETWEEN ".$period[0].' AND '.$period[1].")";

				}
				$count = $model->join('as l left join '.C('DB_PREFIX').'admin a ON a.id = l.admin_id')->where($where)->count();

				$length = ceil($count/2000);

				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);

					$objExcel->getActiveSheet()->setTitle('用户分页'.$i);
					$objActSheet = $objExcel->getActiveSheet();

					$objActSheet->setCellValue('A1', '订单编号');
					$objActSheet->setCellValue('B1', '订单信息');
					$objActSheet->setCellValue('C1', '订单用户');
					$objActSheet->setCellValue('D1', '操作管理员');
					$objActSheet->setCellValue('E1', '时间');


					$user_list = $model->field('l.pigcms_id,l.money,l.desc,l.type,l.time,l.admin_id,l.uid,u.nickname,u.phone,a.realname,a.level')->join('as l left join '.C('DB_PREFIX').'user u ON l.uid = u.uid left join '.C('DB_PREFIX').'admin a ON a.id = l.admin_id')->where($where)->order('l.pigcms_id DESC')->limit($i * 2000 . ',2000')->select();
					if (!empty($user_list)) {

						$index = 2;
						foreach ($user_list as $value) {
							$objActSheet->setCellValueExplicit('A' . $index, $value['pigcms_id']);
							if($value['type']==1){
								$money_text = '充值';
							}else{
								$money_text = '减少';
							}
							$money_text.=$value['money'].'元';
							$objActSheet->setCellValueExplicit('B' . $index, $money_text);
							$objActSheet->setCellValueExplicit('C' . $index, $value['nickname']);
							$objActSheet->setCellValueExplicit('D' . $index, $value['realname'] . ' ');
							$objActSheet->setCellValueExplicit('E' . $index, date("Y-m-d", $value['time']));
							$index++;
						}
					}
					sleep(2);
				}
				break;

			case 'store':
				$title = C('config.store_alias_name').'账单';

				// 设置文档基本属性
				$objProps->setCreator($title);
				$objProps->setTitle($title);
				$objProps->setSubject($title);
				$objProps->setDescription($title);
				// 设置当前的sheet
				$objExcel->setActiveSheetIndex(0);

				if ($param['merchant_session']) {
					$condition_where = " o.mer_id = " . $param['merchant_session']['mer_id'];
				}else{
					$condition_where = " 1=1";
				}

				if ($param['store_session']) {
					$condition_where = " o.store_id = " . $param['store_session']['store_id'];
				}

				if(!empty($param['keyword'])){
					if ($param['searchtype'] == 'order_id') {
						$condition_where .= " AND  `o`.`order_id`='" . $param['order_id']."'";
					}else if ($param['searchtype'] == 'orderid') {
						$where['orderid'] = htmlspecialchars($param['keyword']);
						$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$param['keyword']))->find();
						$condition_where .= " AND  `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
					} elseif ($param['searchtype'] == 'name') {
						$condition_where .= " AND  `u`.`nickname` like '%" . htmlspecialchars($param['keyword']) . "%'";
					} elseif ($param['searchtype'] == 'phone') {
						$condition_where .= " AND `u`.`phone`='" . htmlspecialchars($param['keyword']) . "'";
					}elseif ($param['searchtype'] == 'third_id') {
						$condition_where .= " AND `o`.`third_id` ='".$param['keyword']."'";
					}
				}

				if ($param['system_session']['area_id']) {
					$now_area = D('Area')->field(true)->where(array('area_id' => $param['system_session']['area_id']))->find();
					if($now_area['area_type']==3){
						$area_index = 'area_id';
					}elseif($now_area['area_type']==2){
						$area_index = 'city_id';
					}elseif($now_area['area_type']==1){
						$area_index = 'province_id';
					}
					$condition_where .= " AND `m`.`{$area_index}`={$param['system_session']['area_id']}";
				}

				$pay_type = isset($param['pay_type']) && $param['pay_type'] ? $param['pay_type'] : '';

				if($pay_type){
					if($pay_type=='balance'){
						$condition_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )";
					}else{
						$condition_where .= " AND `o`.`pay_type`='{$pay_type}'";
					}
				}

				if(!empty($param['begin_time'])&&!empty($param['end_time'])){
					if ($param['begin_time']>$param['end_time']) {
						return false;
						//$this->error_tips("结束时间应大于开始时间");
					}
					$period = array(strtotime($param['begin_time']." 00:00:00"),strtotime($param['end_time']." 23:59:59"));
					$condition_where .= " AND (o.pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
					//$condition_where['_string']=$time_condition;
				}

				if ($param['area_id'] && $param['area_id']!='undefined') {
					$condition_where .= " AND m.area_id={$param['area_id']}";
				} else if ($param['city_idss'] && $param['city_idss']!='undefined') {
					$condition_where .= " AND m.city_id={$param['city_idss']}";
				} else if ($param['province_idss'] && $param['province_idss']!='undefined') {
					$condition_where .= " AND m.province_id={$param['province_idss']}";
				}


				$count =  M('Store_order')->join("AS o LEFT JOIN ".C('DB_PREFIX')."merchant AS m ON o.mer_id=m.mer_id LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS ms ON o.store_id=ms.store_id LEFT JOIN " . C('DB_PREFIX') . "user AS u ON o.uid=u.uid")
						->where($condition_where)->count();

				$length = ceil($count[0]['count'] / 1000);
				for ($i = 0; $i < $length; $i++) {
					$i && $objExcel->createSheet();
					$objExcel->setActiveSheetIndex($i);

					$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
					$objActSheet = $objExcel->getActiveSheet();
					$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(40);
					$objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
					$objExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);


					$objActSheet->setCellValue('A1', '商家ID');
					$objActSheet->setCellValue('B1', '商家名称');
					$objActSheet->setCellValue('C1', '店铺ID');
					$objActSheet->setCellValue('D1', '店铺名称');
					$objActSheet->setCellValue('E1', '订单编号');
					$objActSheet->setCellValue('F1', '客户姓名');
					$objActSheet->setCellValue('G1', '客户电话');
					$objActSheet->setCellValue('H1', '订单总价');
					$objActSheet->setCellValue('I1', '平台余额');
					$objActSheet->setCellValue('J1', '商家余额');
					$objActSheet->setCellValue('K1', '在线支付金额');
					$objActSheet->setCellValue('L1', '平台'.C('config.score_name'));
					$objActSheet->setCellValue('M1', '平台优惠券');
					$objActSheet->setCellValue('N1', '商家优惠券');
					$objActSheet->setCellValue('O1', '商家会员卡折扣');
					$objActSheet->setCellValue('P1', '不可优惠');
					$objActSheet->setCellValue('Q1', '支付时间');
					$objActSheet->setCellValue('R1', '支付情况');
					$objActSheet->setCellValue('S1', '第三方支付流水号');

					$sql = "SELECT o.*, u.nickname, u.phone, m.name AS merchant_name, ms.name AS store_name FROM " . C('DB_PREFIX') . "store_order AS o LEFT JOIN ".C('DB_PREFIX')."merchant AS m ON o.mer_id=m.mer_id LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS ms ON o.store_id=ms.store_id LEFT JOIN " . C('DB_PREFIX') . "user AS u ON o.uid=u.uid WHERE ".$condition_where." ORDER BY o.order_id DESC LIMIT ". $i * 1000 . ",1000";;

					$result_list = D()->query($sql);

					if (!empty($result_list)) {
						$index = 2;
						foreach ($result_list as $value) {
							$objActSheet->setCellValueExplicit('A' . $index,$value['mer_id']);
							$objActSheet->setCellValueExplicit('B' . $index,$value['merchant_name']);
							$objActSheet->setCellValueExplicit('C' . $index, $value['store_id']);
							$objActSheet->setCellValueExplicit('D' . $index, $value['store_name']);
							$objActSheet->setCellValueExplicit('E' . $index, $value['order_id']);
							$objActSheet->setCellValueExplicit('F' . $index, $value['username'] . ' ');
							$objActSheet->setCellValueExplicit('G' . $index, $value['phone'] . ' ');
							$objActSheet->setCellValueExplicit('H' . $index, floatval($value['total_money']));
							$objActSheet->setCellValueExplicit('I' . $index, floatval($value['balance_pay']));
							$objActSheet->setCellValueExplicit('J' . $index, floatval($value['merchant_balance']));
							$objActSheet->setCellValueExplicit('Q' . $index, floatval($value['payment_money']));
							$objActSheet->setCellValueExplicit('L' . $index, floatval($value['score_reducte']));
							$objActSheet->setCellValueExplicit('M' . $index, floatval($value['coupon_price']));
							$objActSheet->setCellValueExplicit('N' . $index, floatval($value['card_price']));
							$objActSheet->setCellValueExplicit('O' . $index, floatval($value['card_discount'])?floatval($value['card_discount']). '折':'');
							$objActSheet->setCellValueExplicit('P' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
							$objActSheet->setCellValueExplicit('Q' . $index, $value['adress'] . '');
							$objActSheet->setCellValueExplicit('R' . $index, $this->get_order_status($value));
							$objActSheet->setCellValueExplicit('S' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));

							$index++;
						}
					}
					sleep(2);
				}
				break;
		}


		ob_end_clean();
		//$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		$filename   = date("Y-m-d", time()).'-'.$param['rand_number'] . '.xls';
		$objWriter  = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');

		$objWriter->save('./runtime/'.iconv("utf-8", "gb2312", $filename));

		return true;
	}
	public function get_order_status($order){
		$status = '';
		if($order['paid']){
			if($order['pay_type']=='offline' && empty($order['third_id'])&& $order['status'] == 0){
				$status='线下支付，未付款';
			}elseif($order['status']==0){
				$status='已付款';
				if($order['tuan_type'] != 2){
					$status.='已付款';
				}else{
					if($order['is_pick_in_store']){
						$status.='未取货';
					}else{
						$status.='未发货';
					}
				}
			}elseif($order['status']==1){
				if($order['tuan_type'] != 2){
					$status='已消费';
				}else{
					if($order['is_pick_in_store']){
						$status='已取货';
					}else{
						$status='已发货';
					}
				}
				$status.='待评价';
			}elseif($order['status']==2){
				$status='已完成';
			}elseif($order['status']==3){
				$status='已退款';
			}elseif($order['status']==4){
				$status='已取消';
			}
		}else{
			if($status==4){
				$status='已取消';
			}else{
				$status='未付款';
			}
		}

		return $status;
	}

	protected  function get_alias_name(){
		$c_name = array(
				'all'=>'选择分类',
				'group'=>C('config.group_alias_name'),
				'shop'=>C('config.shop_alias_name'),
				'shop_offline'=>C('config.shop_alias_name').'线下零售',
				'meal'=>C('config.meal_alias_name'),
				'appoint'=>C('config.appoint_alias_name'),
				'waimai'=>'外卖',
				'store'=>'优惠买单',
				'cash'=>'到店支付',
				'weidian'=>'微店',
				'wxapp'=>'营销',
				'withdraw'=>'提现',
				'coupon'=>'优惠券',
				'withdraw'=>'提现',
				'activity'=>'平台活动',
				'spread'=>'商家推广',
				'market_order'=>'批发',
		);
		if(!C('config.store_open_waimai')) unset($c_name['waimai']);
		if(!C('config.wxapp_url')) unset($c_name['wxapp']);
		if(!C('config.appoint_page_row')) unset($c_name['appoint']);
		if(!C('config.is_open_weidian')) unset($c_name['weidian']);
		if(!C('config.is_cashier')) unset($c_name['store']);
		if(!C('config.pay_in_store') || !C('config.is_cashier') ) unset($c_name['cash'],$c_name['shop_offline']);
		return $c_name ;
	}


	/*
 	 *  1. 营业金额：即支付成功+退款失败的订单金额之和
	 *	2. 营业单数：即支付成功+退款失败的订单数之和
	 *	3. 实收金额：即已验证消费金额之和
	 *	4. 实收单数：即已验证消费订单数之和
	 *	5. 退款金额：退款成功的退款金额之和
	 *	6. 退款单数：退款成功的退款订单数之和
	 */

	public function sell_order_date($is_system = true,$param){
		$mer_list = $param['mer_list'];
		$store_list = $param['store_list'];
		$staff_list = $param['staff_list'];
		$type = $param['type'];
		$pay_type = $param['pay_type'];
//		$start_time = strtotime($param['begin_time']);
//		$end_time = strtotime($param['end_time'])+86400;
		if($param['selectTimeType']>0){
			$start_time = strtotime(date('Y-m-d',time()-($param['selectTimeType']-1)*86400));
			$end_time =time();
		}

		if ($param['area_id'] && $param['area_id']!='undefined') {
			$where_store['area_id'] = $param['area_id'];
		} else if ($param['city_idss'] && $param['city_idss']!='undefined') {
			$where_store['city_id'] = $param['city_idss'];
		} else if ($param['province_idss'] && $param['province_idss']!='undefined') {
			$where_store['province_id'] = $param['province_idss'];
		}
		if($where_store){
			$store_ids = M('Merchant_store')->where($where_store)->select();

			foreach ($store_ids as $st) {
				$store_list_id[] = $st['store_id'];
			}
			if($store_list_id){
				$where['store_id']= array('in',$store_list_id);
			}else{
				return array();
			}
		}

		if($param['begin_time'] && $param['end_time']){
			$start_time = strtotime($param['begin_time']);
			$end_time = strtotime($param['end_time'])+86400;
			unset($_GET['selectTimeType']);
		}
		if($type=='shop' && $param['platform']){
			$where['platform']  = $param['platform'];
		}

		if($end_time<$start_time){
			return array('error_code'=>1,'msg'=>'结束时间不能比开始时间小');
		}
		$param['mer_id'] && $where['mer_id'] = $param['mer_id'];

		if($type=='meal'){
			$Order = 'Foodshop_order';
			$where_meal = ' 1 = 1 ';
			$param['mer_id'] && $where_meal .= ' AND o.mer_id='.$param['mer_id'];
		}else{
			$where['paid'] = 1;
			$Order = ucfirst($type).'_order';
		}

		if($mer_list && $mer_list!=-1){
			$where['mer_id']= array('in',$mer_list);
			$where_meal .= " AND mer_id = in ({$mer_list}) ";
		}

		if($store_list && $store_list!=-1 ){
			$where['store_id']= array('in',$store_list);
			$where_meal .= " AND  store_id = in ({$mer_list}) ";
		}

		if($staff_list && $staff_list!=-1 ){
			$where['staff_id']= array('in',$staff_list);
			$where_meal .= " AND  staff_id = in ({$staff_list}) ";
		}

		if($pay_type!='all'){
			if($pay_type=='balance'){
				$where['pay_type']= '';
				$where_pay = " ='' " ;
			}elseif($pay_type=='alipay'){
				$where['pay_type']= array('like','alipay%');
				$where_pay = " like 'alipay%' " ;
			}else{
				$where['pay_type']= $param['pay_type'];
				$where_pay = " ='".$param['pay_type']."'" ;
			}
		}


		if($start_time){
			$where['_string']="pay_time <= {$end_time} AND pay_time>={$start_time}  ";
			$where_meal .= " AND pay_time <= {$end_time} AND pay_time>={$start_time} ";
		}



		//营业金额
		switch($type){
			case 'group':
				$where['status'] =array('elt',3);
				$sum_name = 'total_money';
				break;
			case 'shop':
				$where['status'] =array('elt',4);
				$sum_name = 'price';
				break;
			case 'meal':
				$where['status'] =array('in','3,4');
				$sum_name = 'price';
				break;
			case 'appoint':
				unset($where['paid']);
				$where['_string'] .= ' AND paid = 1 OR paid = 3';
				$sum_name = 'balance_pay+score_deducte+payment_money+merchant_balance+product_score_deducte+product_balance_pay+product_coupon_price+user_pay_money+product_merchant_balance';
				break;
			case 'store':
				$sum_name = 'price';

				break;
		}


		if($type=='meal'){
			if($param['mer_id']){
				$store_ids = M('Merchant_store')->where(array('mer_id'=>$param['mer_id']))->select();

				foreach ($store_ids as $st) {
					$store_list_id[] = $st['store_id'];
				}
				if($store_list_id){
					$where_plat['store_id']= implode(',',$store_list_id);
				}
			}

			$where_tm = $where;
			$where['pay_type'] && $where_tm['p.pay_type'] = $where['pay_type'];
			unset($where_tm['pay_type']);
			// $arr['total_money_count'] = M($Order)->join('AS o LEFT JOIN ( SELECT pay_type ,business_id,business_type,total_money FROM '.C('DB_PREFIX').'plat_order where business_type="foodshop"  AND store_id in ('.$where_plat['store_id'].') ) AS p on p.business_id=o.order_id ')->where($where_tm)->count();

			$counts  = M('')->query("SELECT count(t.order_id) as count_ FROM ( SELECT o.order_id,o.store_id, o.pay_time, o.status, o.total_price, o.price, o.book_price, ( CASE WHEN o.pay_type = 0 THEN ( SELECT pay_type FROM pigcms_plat_order p WHERE p.business_type = 'foodshop' AND p.business_id = o.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) WHEN o.pay_type = 1 THEN ( SELECT pay_type FROM pigcms_store_order s WHERE s.business_type = 'foodshop' AND s.business_id = s.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) END ) AS pay_types FROM pigcms_foodshop_order AS o where ".$where_meal." AND o.status IN (3, 4) ) t ".($where_pay?'WHERE t.pay_types '.$where_pay:''));
			$arr['total_money_count'] =$counts[0]['count_'];


			$price = M('')->query("SELECT sum(t.total_price) as price_ FROM ( SELECT o.order_id,o.store_id, o.pay_time, o.status, o.total_price, o.price, o.book_price, ( CASE WHEN o.pay_type = 0 THEN ( SELECT pay_type FROM pigcms_plat_order p WHERE p.business_type = 'foodshop' AND p.business_id = o.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) WHEN o.pay_type = 1 THEN ( SELECT pay_type FROM pigcms_store_order s WHERE s.business_type = 'foodshop' AND s.business_id = s.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) END ) AS pay_types FROM pigcms_foodshop_order AS o where ".$where_meal." AND o.status IN (3, 4) ) t ".($where_pay?'WHERE t.pay_types '.$where_pay:''));

			// $arr['total_money_sum'] = M($Order)->join('AS o LEFT JOIN ( SELECT pay_type ,business_id,business_type,total_money FROM '.C('DB_PREFIX').'plat_order where business_type="foodshop"  AND store_id in ('.$where_plat['store_id'].') ) AS p on p.business_id=o.order_id ')->where($where_tm)->sum($sum_name);

			$arr['total_money_sum'] = 	$price[0]['price_'];
			// $arr['total_money_count'] = M($Order)->where($where)->count();
			// $arr['total_money_sum'] = M($Order)->where($where)->sum($sum_name);

		}else{
			$arr['total_money_count'] = M($Order)->where($where)->count();
			$arr['total_money_sum'] = M($Order)->where($where)->sum($sum_name);
		}
		//$count = M($Order)->where($where)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->count();
		if($type=='meal'){
			$subQuery = M($Order)->field('store_id')->join('AS o LEFT JOIN ( SELECT pay_type ,business_id,business_type,total_money FROM '.C('DB_PREFIX').'plat_order where business_type="foodshop"  AND store_id in ('.$where_plat['store_id'].') ) AS p on p.business_id=o.order_id ')->where($where_tm )->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->buildSql();

			$counts = M('')->query("select count(m.order_id_) as count_ from (SELECT t.order_id as order_id_ FROM ( SELECT o.order_id,o.store_id, o.pay_time, o.status, o.total_price, o.price, o.book_price, ( CASE WHEN o.pay_type = 0 THEN ( SELECT pay_type FROM pigcms_plat_order p WHERE p.business_type = 'foodshop' AND p.business_id = o.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) WHEN o.pay_type = 1 THEN ( SELECT pay_type FROM pigcms_store_order s WHERE s.business_type = 'foodshop' AND s.business_id = s.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) END ) AS pay_types FROM pigcms_foodshop_order AS o where ".$where_meal." AND o.status IN (3, 4) ) t ".($where_pay?'WHERE t.pay_types '.$where_pay:'')." group by FROM_UNIXTIME(t.pay_time, '%Y-%m-%d'),t.store_id) as m");


			$count = $counts[0]['count_'];


		}else{
			$subQuery = M($Order)->field('store_id')->where($where)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->buildSql();
			$count =  M()->table($subQuery.' a')->count();
		}

		if($is_system){
			import('@.ORG.system_page');
		}else{
			import('@.ORG.merchant_page');
		}
		$p = new Page($count,10);
		if($type=='meal'){

			/* $total_money_sum_by_store = M($Order)->field("FROM_UNIXTIME(pay_time, '%Y-%m-%d') as date_id,store_id,mer_id,sum({$sum_name}) as income,count(order_id) as counts")->join('AS o LEFT JOIN ( SELECT pay_type ,business_id,business_type,total_money FROM '.C('DB_PREFIX').'plat_order where business_type="foodshop"  AND store_id in ('.$where_plat['store_id'].') ) AS p on p.business_id=o.order_id ')
				->where($where_tm)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")
				->order("FROM_UNIXTIME(pay_time, '%Y-%m-%d') DESC,store_id DESC")
				->limit($p->firstRow,$p->listRows)->select();   */


			$total_money_sum_by_store = M('')->query("select * from (SELECT t.store_id,t.mer_id,count(t.order_id) as counts ,sum(t.total_price) as income,FROM_UNIXTIME(t.pay_time, '%Y-%m-%d') as date_id FROM ( SELECT o.order_id,o.mer_id,o.store_id, o.pay_time, o.status, o.total_price, o.price, o.book_price, ( CASE WHEN o.pay_type = 0 THEN ( SELECT pay_type FROM pigcms_plat_order p WHERE p.business_type = 'foodshop' AND p.business_id = o.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) WHEN o.pay_type = 1 THEN ( SELECT pay_type FROM pigcms_store_order s WHERE s.business_type = 'foodshop' AND s.business_id = s.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) END ) AS pay_types FROM pigcms_foodshop_order AS o where ".$where_meal." AND o.status in (3,4)) t ".($where_pay?'WHERE t.pay_types '.$where_pay:'')." group by FROM_UNIXTIME(t.pay_time, '%Y-%m-%d'),t.store_id) as m order by date_id DESC  limit {$p->firstRow},{$p->listRows}");


		}else{

			$total_money_sum_by_store = M($Order)->field("FROM_UNIXTIME(pay_time, '%Y-%m-%d') as date_id,store_id,mer_id,sum({$sum_name}) as income,count(order_id) as counts")
					->where($where)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")
					->order("FROM_UNIXTIME(pay_time, '%Y-%m-%d') DESC,store_id DESC")
					->limit($p->firstRow,$p->listRows)->select();
		}



		$arr['pagebar'] = $p->show();
		//实收金额
		$where_ = $where;
		unset($where_['status']);
		empty($where_['_string']) && $where_['_string'] = ' 1=1 ';
		switch($type){
			case 'group':
				$where_['_string'] .= ' AND  (status = 1 OR status = 2) ';
				break;
			case 'shop':
				$where_['_string'] .= ' AND (status = 2 OR status = 3) ';
				break;
			case 'meal':
				$where_['_string'] .= ' AND (status = 3 OR status = 4) ';
				break;
			case 'appoint':
				$where_['_string'] .= ' AND service_status>0 ';
				break;
			case 'store':
				break;
		}

		if($type=='meal'){
			$where_t = $where_;
			isset($where['pay_type']) && $where_t['p.pay_type'] = $where['pay_type'];
			unset($where_t['pay_type']);

			$arr['consume_money_count'] =$arr['total_money_count'];


			$price = M('')->query("SELECT sum(t.price) as price_ FROM ( SELECT o.order_id,o.store_id, o.pay_time, o.status, o.total_price, o.price, o.book_price, ( CASE WHEN o.pay_type = 0 THEN ( SELECT pay_type FROM pigcms_plat_order p WHERE p.business_type = 'foodshop' AND p.business_id = o.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) WHEN o.pay_type = 1 THEN ( SELECT pay_type FROM pigcms_store_order s WHERE s.business_type = 'foodshop' AND s.business_id = s.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) END ) AS pay_types FROM pigcms_foodshop_order AS o where ".$where_meal." AND o.status IN (3, 4) ) t ".($where_pay?'WHERE t.pay_types '.$where_pay:''));

			// $arr['consume_money_sum'] =M($Order)->join('AS o LEFT JOIN ( SELECT pay_type ,business_id,business_type,total_money FROM '.C('DB_PREFIX').'plat_order where business_type="foodshop" AND store_id in ('.$where_plat['store_id'].') ) AS p on p.business_id=o.order_id ')->where($where_t)->sum($sum_name);

			$arr['consume_money_sum'] = $price[0]['price_'];


			// $consume_money_count_store =M($Order)->join('AS o LEFT JOIN ( SELECT pay_type ,business_id,business_type,total_money FROM '.C('DB_PREFIX').'plat_order where business_type="foodshop"  AND store_id in ('.$where_plat['store_id'].') ) AS p on p.business_id=o.order_id ')->where($where_t)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->field("FROM_UNIXTIME(o.pay_time, '%Y-%m-%d') as date_id,o.store_id,count(o.order_id) as consume_count,sum({$sum_name}) as consume_money")->select();


			$consume_money_count_store = M('')->query("select * from (SELECT t.store_id,t.mer_id,count(t.order_id) as consume_count ,sum(price) as consume_money,FROM_UNIXTIME(t.pay_time, '%Y-%m-%d') as date_id FROM ( SELECT o.order_id,o.mer_id,o.store_id, o.pay_time, o.status, o.total_price, o.price, o.book_price, ( CASE WHEN o.pay_type = 0 THEN ( SELECT pay_type FROM pigcms_plat_order p WHERE p.business_type = 'foodshop' AND p.business_id = o.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) WHEN o.pay_type = 1 THEN ( SELECT pay_type FROM pigcms_store_order s WHERE s.business_type = 'foodshop' AND s.business_id = s.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) END ) AS pay_types FROM pigcms_foodshop_order AS o where ".$where_meal." AND o.status in (3,4)) t ".($where_pay?'WHERE t.pay_types '.$where_pay:'')." group by FROM_UNIXTIME(t.pay_time, '%Y-%m-%d'),t.store_id) as m order by date_id DESC  limit {$p->firstRow},{$p->listRows}");


		}else{

			$arr['consume_money_count'] = M($Order)->where($where_)->count();

			$arr['consume_money_sum'] = M($Order)->where($where_)->sum($sum_name);
			$consume_money_count_store =M($Order)->where($where_)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->field("FROM_UNIXTIME(pay_time, '%Y-%m-%d') as date_id ,store_id,count(order_id) as consume_count,sum({$sum_name}) as consume_money")->select();
		}
		foreach ($consume_money_count_store as $v) {
			$cosumen_count_tmp[$v['date_id']][$v['store_id']] = $v['consume_count'];
			$cosumen_money_tmp[$v['date_id']][$v['store_id']] = $v['consume_money'];
		}

		//退款金额
		switch($type){
			case 'group':
				$where['status'] =3;
				break;
			case 'shop':
				$where['status'] =4;
				break;
			case 'meal':
				$where['status'] =5;
				break;
			case 'appoint':
				$where['status'] =2;
				$where['_string'] .= ' AND service_status>0 ';
				break;
			case 'store':
				$where['_string'] .= ' 1=0 ';
				break;
		}



		if($type=='meal'){

			// $where_t = $where ;
			// $where_t['pay_time']=array('gt',0);
			// unset($where_t['pay_type']);
			// $where['pay_type'] && $where_t['p.pay_type'] = $where['pay_type'];
			// $arr['refund_money_count'] =M($Order)->join('AS o LEFT JOIN ( SELECT pay_type ,business_id,business_type,total_money FROM '.C('DB_PREFIX').'plat_order where business_type="foodshop" AND refund_money>0  AND store_id in ('.$where_plat['store_id'].') ) AS p on p.business_id=o.order_id ')->where($where_t)->count();

			// $arr['refund_money_sum'] =M($Order)->join('AS o LEFT JOIN ( SELECT pay_type ,business_id,business_type,total_money FROM '.C('DB_PREFIX').'plat_order where business_type="foodshop" AND refund_money>0  AND store_id in ('.$where_plat['store_id'].') ) AS p on p.business_id=o.order_id ')->where($where_t)->sum($sum_name);


			$counts  = M('')->query("SELECT count(t.order_id) as count_ FROM ( SELECT o.order_id,o.store_id, o.pay_time, o.status, o.total_price, o.price, o.book_price, ( CASE WHEN o.pay_type = 0 THEN ( SELECT pay_type FROM pigcms_plat_order p WHERE p.business_type = 'foodshop' AND p.business_id = o.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) WHEN o.pay_type = 1 THEN ( SELECT pay_type FROM pigcms_store_order s WHERE s.business_type = 'foodshop' AND s.business_id = s.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) END ) AS pay_types FROM pigcms_foodshop_order AS o where ".$where_meal." AND o.status =5 AND o.pay_time>0) t ".($where_pay?'WHERE t.pay_types '.$where_pay:''));

			$arr['refund_money_count'] = $counts[0]['count_'];

			$price = M('')->query("SELECT sum(t.price) as price_ FROM ( SELECT o.order_id,o.store_id, o.pay_time, o.status, o.total_price, o.price, o.book_price, ( CASE WHEN o.pay_type = 0 THEN ( SELECT pay_type FROM pigcms_plat_order p WHERE p.business_type = 'foodshop' AND p.business_id = o.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) WHEN o.pay_type = 1 THEN ( SELECT pay_type FROM pigcms_store_order s WHERE s.business_type = 'foodshop' AND s.business_id = s.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) END ) AS pay_types FROM pigcms_foodshop_order AS o where ".$where_meal." AND o.status=5 AND o.pay_time>0) t ".($where_pay?'WHERE t.pay_types '.$where_pay:''));

			$arr['refund_money_sum'] = $price[0]['price_'];


			// $refund_money_count_store =M($Order)->join('AS o LEFT JOIN ( SELECT pay_type ,business_id,business_type,total_money FROM '.C('DB_PREFIX').'plat_order where business_type="foodshop" AND refund_money>0  AND store_id in ('.$where_plat['store_id'].') ) AS p on p.business_id=o.order_id ')->where($where_t)->group("FROM_UNIXTIME(o.pay_time, '%Y-%m-%d'),store_id")->field("FROM_UNIXTIME(o.pay_time, '%Y-%m-%d') as date_id,o.store_id,count(o.order_id) as refund_count,sum({$sum_name}) as refund_money")->select();
			// fdump($refund_money_count_store,'refund');



			$consume_money_count_store = M('')->query("select * from (SELECT t.store_id,t.mer_id,count(t.order_id) as refund_count ,sum(t.price) as refund_money,FROM_UNIXTIME(t.pay_time, '%Y-%m-%d') as date_id FROM ( SELECT o.order_id,o.mer_id,o.store_id, o.pay_time, o.status, o.total_price, o.price, o.book_price, ( CASE WHEN o.pay_type = 0 THEN ( SELECT pay_type FROM pigcms_plat_order p WHERE p.business_type = 'foodshop' AND p.business_id = o.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) WHEN o.pay_type = 1 THEN ( SELECT pay_type FROM pigcms_store_order s WHERE s.business_type = 'foodshop' AND s.business_id = s.order_id AND o.store_id in (".$where_plat['store_id'].") ORDER BY order_id DESC LIMIT 1 ) END ) AS pay_types FROM pigcms_foodshop_order AS o where ".$where_meal." AND o.status=5 AND o.pay_time>0) t ".($where_pay?'WHERE t.pay_types '.$where_pay:'')." group by FROM_UNIXTIME(t.pay_time, '%Y-%m-%d'),t.store_id) as m order by date_id DESC  limit {$p->firstRow},{$p->listRows}");

			fdump($tt ,'eeeee');
			fdump(M() ,'eeeee',1);


		}else{
			$arr['refund_money_count'] =M($Order)->where($where)->count();
			$arr['refund_money_sum'] =M($Order)->where($where)->sum($sum_name);
			$refund_money_count_store =M($Order)->where($where)->group("FROM_UNIXTIME(pay_time, '%Y-%m-%d'),store_id")->field("FROM_UNIXTIME(pay_time, '%Y-%m-%d') as date_id,store_id,count(order_id) as refund_count,sum({$sum_name}) as refund_money")->select();
		}

		foreach ($refund_money_count_store as $v) {
			$refund_count_tmp[$v['date_id']][$v['store_id']] = $v['refund_count'];
			$refund_money_tmp[$v['date_id']][$v['store_id']] = $v['refund_money'];
		}

		//线下支付
		$merchant_arr = array();
		$store_arr = array();
		foreach ($total_money_sum_by_store as $v) {

			$tmp['store_id'] = $v['store_id'];
			$tmp['mer_id'] = $v['mer_id'];
			if(empty($merchant_arr[$v['mer_id']])){
				$merchant_arr[$v['mer_id']] = $merchant = M('Merchant')->field('mer_id,name')->where(array('mer_id'=>$v['mer_id']))->find();
			}else{
				$merchant = $merchant_arr[$v['mer_id']];
			}

			if(empty($store_arr[$v['store_id']])){
				$store_arr[$v['store_id']]=$store = M('Merchant_store')->field('store_id,name')->where(array('store_id'=>$v['store_id']))->find();
			}else{
				$store = $store_arr[$v['store_id']];
			}


			$tmp['merchant_name'] = $merchant['name'];
			$tmp['store_name'] = $store['name'];
			$tmp['total_money'] = round($v['income'],2);
			$tmp['total_count'] = $v['counts'];
			$tmp['date_id'] = $v['date_id'];

			$tmp['consume_money'] = round($cosumen_money_tmp[$v['date_id']][$v['store_id']],2);
			$tmp['consume_count'] = $cosumen_count_tmp[$v['date_id']][$v['store_id']];

			$tmp['refund_money'] = round($refund_money_tmp[$v['date_id']][$v['store_id']],2);
			$tmp['refund_count'] = $refund_count_tmp[$v['date_id']][$v['store_id']];
			$arr['sale_date'][] = $tmp;
		}
		$arr['total_money_sum'] = round($arr['total_money_sum'],2);
		$arr['consume_money_sum'] = round($arr['consume_money_sum'],2);
		$arr['refund_money_sum'] = round($arr['refund_money_sum'],2);

		return $arr;
	}

	public function goods_date($is_system=true,$param){
		$mer_list = $param['mer_list'];
		$store_list = $param['store_list'];
		$type = $param['type'];
		//$pay_type = $param['pay_type'];
		$start_time = strtotime($param['begin_time']);
		$end_time = strtotime($param['end_time'])+86400;
		$goods_name = $param['goods_name'];

		if($param['selectTimeType']>0){
			$start_time = strtotime(date('Y-m-d',time()-($param['selectTimeType']-1)*86400));
			$end_time =time();;
		}

		if ($param['area_id'] && $param['area_id']!='undefined') {
			$where_store['area_id'] = $param['area_id'];
		} else if ($param['city_idss'] && $param['city_idss']!='undefined') {
			$where_store['city_id'] = $param['city_idss'];
		} else if ($param['province_idss'] && $param['province_idss']!='undefined') {
			$where_store['province_id'] = $param['province_idss'];
		}
		if($where_store){
			$store_ids = M('Merchant_store')->where($where_store)->select();

			foreach ($store_ids as $st) {
				$store_list_id[] = $st['store_id'];
			}
			if($store_list_id){
				$where['store_id']= array('in',$store_list_id);
			}else{
				return array();
			}
		}

		if($end_time<$start_time){
			return array('error_code'=>1,'msg'=>'结束时间不能比开始时间小');
		}
		$param['mer_id'] && $where['mer_id'] = $param['mer_id'];

		if($mer_list && $mer_list!=-1){
			$where['mer_id']= array('in',$mer_list);
		}

		if($store_list && $store_list!=-1 ){
			$where['store_id']= array('in',$store_list);
		}


		if($param['type_list'] && $param['type_list']!=-1){
			$where['sort_id']  =array('in',$param['type_list']);
		}

		if($goods_name){
			$where['name']=array('like',"%".$goods_name."%");
		}



		if($start_time){
			$where['_string']="o.create_time <= {$end_time} AND o.create_time>={$start_time}";
		}



		if($is_system){
			import('@.ORG.system_page');
		}else{
			import('@.ORG.merchant_page');
		}

		$type = $type=='foodshop'?'meal':$type;

		if($type=='meal'){

			$where_tmp = array();
			foreach ($where as $key => &$v) {

				if($key!='_string'){

					if($key!='mer_id'){
						$tmp['d.'.$key] = $v;
					}else{
						$tmp['o.'.$key] = $v;
					}
				}else{
					$tmp['_string'] = $v;
				}
				$where_tmp[] = $tmp;
				unset($where[$key]);
			}
			$where  =  $where_tmp;
			$where['o.status'] = array('in','1,2,3,4');
			$all_sale_money = floatval(M('Foodshop_order_detail')->join('as d LEFT JOIN '.C('DB_PREFIX').'foodshop_order AS o ON d.order_id = o.order_id ')->where($where)->sum('d.price'));
			$all_sale_num = floatval(M('Foodshop_order_detail')->join('as d LEFT JOIN '.C('DB_PREFIX').'foodshop_order AS o ON d.order_id = o.order_id ')->where($where)->sum('d.num'));

			//$count = M('Foodshop_order_detail')->where($where)->group('goods_id')->count();
			$subQuery = M('Foodshop_order_detail')->field('goods_id')->join('as d LEFT JOIN '.C('DB_PREFIX').'foodshop_order AS o ON d.order_id = o.order_id ')->group('d.goods_id')->where($where)->buildSql();
			$count =  M()->table($subQuery.' a')->count();

			if($param['sort'] && $count>$param['sort']){
				$count = $param['sort'];
			}

			$p = new Page($count, 15);
			if($param['sort']){
				if( $param['sort']>15 && intval($param['sort']/15)==$param['page']-1) {
					$p->listRows = $param['sort'] - (($param['page'] - 1) * 15);
				}else if($param['sort']<15){
					$p->listRows = $param['sort'];
				}
			}
			if($all_sale_money <=0){

				$arr['goods_date'] = M('Foodshop_order_detail')->join('as d LEFT JOIN '.C('DB_PREFIX').'foodshop_order AS o ON d.order_id = o.order_id ')->where($where)->group('d.goods_id,d.spec')->order('sale_money DESC')->field("d.goods_id,d.spec,d.name,d.price,d.store_id,sum(d.price) as sale_money ,sum(d.num) as sale_count,concat ( left (sum(d.price)/{$all_sale_num} *100,5),'%') as sale_percent")->limit($p->firstRow,$p->listRows)->select();
			}else{
				$arr['goods_date'] = M('Foodshop_order_detail')->join('as d LEFT JOIN '.C('DB_PREFIX').'foodshop_order AS o ON d.order_id = o.order_id ')->where($where)->group('d.goods_id,d.spec')->order('sale_money DESC')->field("d.goods_id,d.spec,d.name,d.price,d.store_id,sum(d.price) as sale_money ,sum(d.num) as sale_count,concat ( left (sum(d.price)/{$all_sale_money} *100,5),'%') as sale_percent")->limit($p->firstRow,$p->listRows)->select();
			}
			fdump(M(),'sss');
		}else{
			$where_tmp = array();
			foreach ($where as $key => &$v) {
				if($key!='_string'){

					if($key!='mer_id'){
						$tmp['d.'.$key] = $v;
					}else{
						$tmp['o.'.$key] = $v;
					}
				}else{
					$tmp['_string'] = $v;
				}
				$where_tmp[] = $tmp;
				unset($where[$key]);
			}

			$where  =  $where_tmp;
			$where['o.paid'] = 1;
			$where['o.status'] = array('in','0,1,2,3');
			$all_sale_money = floatval(M('Shop_order_detail')->join('as d LEFT JOIN '.C('DB_PREFIX').'shop_order AS o ON d.order_id = o.order_id ')->where($where)->sum('d.price'));
			$all_sale_num = floatval(M('Shop_order_detail')->join('as d LEFT JOIN '.C('DB_PREFIX').'shop_order AS o ON d.order_id = o.order_id ')->where($where)->sum('d.num'));
			//$count = M('Shop_order_detail')->where($where)->group('goods_id')->count();
			$subQuery = M('Shop_order_detail')->join('as d LEFT JOIN '.C('DB_PREFIX').'shop_order AS o ON d.order_id = o.order_id ')->field('d.goods_id')->group('d.goods_id')->where($where)->buildSql();

			$count =  M()->table($subQuery.' a')->count();
			if($param['sort'] && $count>$param['sort']){
				$count = $param['sort'];
			}

			$p = new Page($count, 15);
			if($param['sort']){
				if( $param['sort']>15 && intval($param['sort']/15)==$param['page']-1) {
					$p->listRows = $param['sort'] - (($param['page'] - 1) * 15);
				}else if($param['sort']<15){
					$p->listRows = $param['sort'];
				}
			}
			if($all_sale_money <=0){
				$arr['goods_date'] =M('Shop_order_detail')->join('as d LEFT JOIN '.C('DB_PREFIX').'shop_order AS o ON d.order_id = o.order_id ')->where($where)->group('d.goods_id,d.spec')->order('sale_money DESC')->field("d.goods_id,d.spec,d.name,d.price,d.store_id,sum(d.price) as sale_money ,sum(d.num) as sale_count,concat ( left (sum(d.price)/{$all_sale_num} *100,5),'%') as sale_percent")->limit($p->firstRow,$p->listRows)->select();
			}else{
				$arr['goods_date'] = M('Shop_order_detail')->join('as d LEFT JOIN '.C('DB_PREFIX').'shop_order AS o ON d.order_id = o.order_id ')->where($where)->group('d.goods_id,d.spec')->order('sale_money DESC')->field("d.goods_id,d.spec,d.name,d.price,d.store_id,sum(d.price) as sale_money ,sum(d.num) as sale_count,concat ( left (sum(d.price)/{$all_sale_money} *100,5),'%') as sale_percent")->limit($p->firstRow,$p->listRows)->select();
			}

		}

		$arr['pagebar'] = $p->show();

		return $arr;
	}
}
?>