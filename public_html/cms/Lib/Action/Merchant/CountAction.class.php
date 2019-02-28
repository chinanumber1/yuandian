<?php
class CountAction extends BaseAction{
	public function index(){
		$condition_merchant_request['mer_id'] = $this->merchant_session['mer_id'];
		
		$today_zero_time = mktime(0,0,0,date('m',$_SERVER['REQUEST_TIME']),date('d',$_SERVER['REQUEST_TIME']), date('Y',$_SERVER['REQUEST_TIME']));
		
		if(empty($_GET['day'])){
			$_GET['day'] = date('d',$_SERVER['REQUEST_TIME']);
		}
		
		if($_GET['day'] < 1){
			$this->error('日期非法！');
		}else if($_GET['day'] > 180){
			$this->error('最长只能查询180天！');
		}
		
		$condition_merchant_request['time'] = array(array('egt',$today_zero_time-(($_GET['day']-1)*86400)),array('elt',$today_zero_time));

		
		$request_list = M('Merchant_request')->field(true)->where($condition_merchant_request)->order('`time` ASC')->select();

		foreach($request_list as $value){
			$tmp_time = date('Ymd',$value['time']);
			$tmp_array[$tmp_time] = $value;
		}
		for($i=1;$i<=$_GET['day'];$i++){
			$tmp_time = date('Ymd',$today_zero_time-(($i-1)*86400));
			if(empty($tmp_array[$tmp_time])){
				$tmp_array[$tmp_time] = array('time'=>$today_zero_time-(($i-1)*86400));
			}
		}
		ksort($tmp_array);
		foreach($tmp_array as $key=>$value){
			//基础统计
			$pigcms_list['xAxis_arr'][]  = '"'.date('j',$value['time']).'日"';
			$pigcms_list['follow_arr'][] = '"'.intval($value['follow_num']).'"';
			$pigcms_list['img_arr'][]   = '"'.intval($value['img_num']).'"';
			$pigcms_list['website_hits_arr'][]   = '"'.intval($value['website_hits']).'"';
			//团购统计
			$pigcms_list['group_hits_arr'][] = '"'.intval($value['group_hits']).'"';
			$pigcms_list['group_buy_count_arr'][]   = '"'.intval($value['group_buy_count']).'"';
			$pigcms_list['group_buy_money_arr'][]   = '"'.floatval($value['group_buy_money']).'"';
			//订餐统计
			$pigcms_list['meal_hits_arr'][] = '"'.intval($value['meal_hits']).'"';
			$pigcms_list['meal_buy_count_arr'][]   = '"'.intval($value['meal_buy_count']).'"';
			$pigcms_list['meal_buy_money_arr'][]   = '"'.floatval($value['meal_buy_money']).'"';
			//预约统计
			$pigcms_list['appoint_hits_arr'][] = '"'.intval($value['appoint_hits']).'"';
			$pigcms_list['appoint_count_arr'][]   = '"'.intval($value['appoint_buy_count']).'"';
			$pigcms_list['appoint_money_arr'][]   = '"'.floatval($value['appoint_buy_money']).'"';
		}
		//基础统计
		$pigcms_list['xAxis_txt'] = implode(',',$pigcms_list['xAxis_arr']);
		$pigcms_list['follow_txt'] = implode(',',$pigcms_list['follow_arr']);
		$pigcms_list['img_txt'] = implode(',',$pigcms_list['img_arr']);
		$pigcms_list['website_hits_txt'] = implode(',',$pigcms_list['website_hits_arr']);
		//团购统计
		$pigcms_list['group_hits_txt'] = implode(',',$pigcms_list['group_hits_arr']);
		$pigcms_list['group_buy_count_txt'] = implode(',',$pigcms_list['group_buy_count_arr']);
		$pigcms_list['group_buy_money_txt'] = implode(',',$pigcms_list['group_buy_money_arr']);
		//订餐统计
		$pigcms_list['meal_hits_txt'] = implode(',',$pigcms_list['meal_hits_arr']);
		$pigcms_list['meal_buy_count_txt'] = implode(',',$pigcms_list['meal_buy_count_arr']);
		$pigcms_list['meal_buy_money_txt'] = implode(',',$pigcms_list['meal_buy_money_arr']);
		//预约统计
		$pigcms_list['appoint_hits_txt'] = implode(',',$pigcms_list['appoint_hits_arr']);
		$pigcms_list['appoint_buy_count_txt'] = implode(',',$pigcms_list['appoint_count_arr']);
		$pigcms_list['appoint_buy_money_txt'] = implode(',',$pigcms_list['appoint_money_arr']);
		$this->assign($pigcms_list);
		krsort($tmp_array);
		$this->assign('request_list',$tmp_array);
		
		$this->display();
	}
	
	public function order()
	{
		$this->assign(D("Meal_order")->get_order_by_mer_id($this->merchant_session['mer_id']));
		$this->display();
	}
	
	public function bill()
	{
		$mer_id = intval($this->merchant_session['mer_id']);
		$period = 0;
		$percent = '';
		$type_name=array('meal'=>'餐饮','group'=>'团购','weidian'=>'微店','appoint'=>'预约','wxapp'=>'营销','store'=>'收银','waimai'=>"外卖",'shop'=>"新快店");
		$time = '';
		if (!$_POST['begin_time']) {
			$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
		}else{
			$type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : 'meal';
		}
		switch($type){
			case 'meal':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2)  AND ( balance_pay<>'0.00') AND (payment_money<>'0.00' OR balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00')";
				break;
			case 'group':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2,6) AND (payment_money<>'0.00' OR balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00')";
				break;
			case 'weidian':
				$where = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
				break;
			case 'wxapp':
				$where = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
				break;
			case 'appoint':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1";
				break;
			case 'store':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND refund=0";
				break;
			case 'waimai':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 ";
				break;
			case 'shop':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status IN (2,3)";
				break;
		}

		$model=D($type.'_order');

		if($type=='waimai'){
			$un_bill_count = $model->where($where." AND is_pay_bill=0 ")->count();
		}else if($type=='appoint'){
			$un_bill_count = $model->join(' as o left join '.C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id")->where("o.mer_id=".$mer_id." AND o.paid=1 AND o.is_own=0 AND o.pay_type<>'offline' AND o.service_status=1 AND o.is_pay_bill=0 AND a.payment_status=1")->count();
		}else if($type=='store'){
			$un_bill_count = $model->where($where." AND is_pay_bill=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00') ")->count();
		}else{
			$un_bill_count = $model->where($where." AND is_pay_bill=0 ")->count();
		}
// 		echo $model->_sql() . '<br/>';
		$pay_time = $model->where($where." AND pay_time<>0 AND pay_time<>''")->field('pay_time')->order('pay_time ASC')->limit('0,1')->getField('pay_time');
		$start_year =empty($pay_time)?"":date('Y',$pay_time);
		if ($_GET['year']) {
			$time = array('year'=>$_GET['year'],'month'=>$_GET['month'],'period'=>$period);
			$time = serialize($time);
		}elseif(isset($_POST['begin_time'])&&isset($_POST['end_time'])){
			if ($_POST['begin_time']>$_POST['end_time']) {
				$this->error("结束时间应大于开始时间");
			}
			$period = $_POST['begin_time']==$_POST['end_time']?array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['begin_time']." 23:59:59")):array(strtotime($_POST['begin_time']),strtotime($_POST['end_time']));
			$time = array('year'=>$_GET['year'],'month'=>$_GET['month'],'period'=>$period);
			$time = serialize($time);
		}
		$selected_year = isset($_GET['year'])?$_GET['year']:0;
		$selected_month = isset($_GET['month'])?$_GET['month']:0;
		if (!$_POST['begin_time']) {
			$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
		}else{
			$type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : 'meal';
		}
                
		if ($this->merchant_session['percent']) {
			$percent = $this->merchant_session['percent'];
		} elseif ($this->config['platform_get_merchant_percent']) {
			$percent = $this->config['platform_get_merchant_percent'];
		}
		$this->assign('un_bill_count',$un_bill_count);
		$this->assign('start_year',$start_year);
		$this->assign('selected_year',$selected_year);
		$this->assign('selected_month',$selected_month);
		$this->assign('percent', $percent);
		$merchant = D('Merchant')->field(true)->where('mer_id=' . $mer_id)->find();
		$result = D("Order")->mer_bill($mer_id,0,$type,$time);
		//dump(D());
        $this->assign('type_name',$type_name[$type]);
		$this->assign($result);
		$this->assign('total_percent', $result['total'] * (100 - $percent) * 0.01);
		$this->assign('all_total_percent', ($result['alltotal'] + $result['alltotalfinsh']) * (100 - $percent) * 0.01);
		$this->assign('now_merchant', $merchant);
		$this->assign('mer_id', $mer_id);
		$this->assign('type', $type);
		$this->display();
	}

	//商家店铺对账
	public function storebill()
	{
		$mer_id = intval($this->merchant_session['mer_id']);
		$percent = '';
		$type_name=array('meal'=>'快店','group'=>'团购','weidian'=>'微店','appoint'=>'预约','store'=>'收银','waimai'=>"外卖");
		$time = '';
//		$where['pay_time']=array('neq',0);
//		$where['paid']=1;
//		$where['mer_id']=$_GET['mer_id'];
//		$where['payment_status']=1;
//		$where['service_status']=1;
//		$where['pay_type']=array('neq','offline');
//		if($_GET['type']=='appoint'){
//			$where['_string'] = " pay_money<>'0.00' OR balance_pay<>'0.00'";
//		}else{
//			$where['_string'] = " payment_money<>'0.00' OR balance_pay<>'0.00'";
//		}
		if (!$_POST['begin_time']) {
			$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
		}else{
			$type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : 'meal';
		}
		switch($type){
			case 'meal':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')";
				break;
			case 'group':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')";
				break;
			case 'weidian':
				$where = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
				break;
			case 'wxapp':
				$where = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
				break;
			case 'appoint':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1";
				break;
			case 'store':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND refund=0";
				break;
			case 'waimai':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 ";
				break;
		}
		if(!empty($_GET['store_id'])){
			$store_id = $_GET['store_id'];
			$where.=" AND store_id = ".$store_id;
		}else{
			$store_id='';
		}
		$pay_time=D($type.'_order')->where($where." AND pay_time<>0 AND pay_time<>''")->field('pay_time')->order('pay_time ASC')->limit('0,1')->getField('pay_time');



		//$s=D(ucwords($_GET['type']).'_order')->field('pay_time')->where($where)->limit(1)->find();
		//$start_year = $s['pay_time']!='0'&&!empty($s)?date('Y',$s['pay_time']):date('Y');
		$start_year =empty($pay_time)?"":date('Y',$pay_time);
		if ($_GET['year']) {
			$time = array('year'=>$_GET['year'],'month'=>$_GET['month'],'period'=>'');
			$time = serialize($time);
		}elseif(isset($_POST['begin_time'])&&isset($_POST['end_time'])){
			if ($_POST['begin_time']>$_POST['end_time']) {
				$this->error("结束时间应大于开始时间");
			}
			$period = $_POST['begin_time']==$_POST['end_time']?array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['begin_time']." 23:59:59")):array(strtotime($_POST['begin_time']),strtotime($_POST['end_time']));
			$time = array('year'=>$_GET['year'],'month'=>$_GET['month'],'period'=>$period);
			$time = serialize($time);
		}
		$selected_year = isset($_GET['year'])?$_GET['year']:0;
		$selected_month = isset($_GET['month'])?$_GET['month']:0;
		if (!$_POST['begin_time']) {
			$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
		}else{
			$type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : 'meal';
		}

		if ($this->merchant_session['percent']) {
			$percent = $this->merchant_session['percent'];
		} elseif ($this->config['platform_get_merchant_percent']) {
			$percent = $this->config['platform_get_merchant_percent'];
		}
		$this->assign('start_year',$start_year);
		$this->assign('selected_year',$selected_year);
		$this->assign('selected_month',$selected_month);
		$this->assign('percent', $percent);
		$merchant = D('Merchant')->field(true)->where(array('mer_id'=>$mer_id))->find();
		$result = D("Order")->mer_bill($mer_id,1,$type,$time,$store_id);

		$store_list = D('Merchant_store')->where(array('mer_id'=>$mer_id, 'status' => 1))->select();
		$this->assign('type_name',$type_name[$type]);
		$this->assign('store_list',$store_list);
		$this->assign($result);
		$this->assign('total_percent', $result['total'] * (100 - $percent) * 0.01);
		$this->assign('all_total_percent', ($result['alltotal'] + $result['alltotalfinsh']) * (100 - $percent) * 0.01);
		$this->assign('now_merchant', $merchant);
		$this->assign('mer_id', $mer_id);
		$this->assign('type', $type);
		$this->display();
	}
	
	public function clerk()
	{
		$Model = new Model();
		$sql = "SELECT s.name as store_name, s.*, m.* FROM ". C('DB_PREFIX') . "merchant_store AS s INNER JOIN ". C('DB_PREFIX') . "merchant_store_staff AS m ON s.store_id=m.store_id WHERE `s`.`mer_id`={$this->merchant_session['mer_id']}";
		$res = $Model->query($sql);
				
		$this->assign('staff_list', $res);
		$this->display();
	}
	
	public function weidian()
	{
		$mer_id = intval($this->merchant_session['mer_id']);
		$percent = '';
		if ($this->merchant_session['percent']) {
			$percent = $this->merchant_session['percent'];
		} elseif ($this->config['platform_get_merchant_percent']) {
			$percent = $this->config['platform_get_merchant_percent'];
		}
		$this->assign('percent', $percent);
		$merchant = D('Merchant')->field(true)->where('mer_id=' . $mer_id)->find();
		$result = D("Weidian_order")->get_order_by_mer_id($mer_id);
		$this->assign($result);
		$this->assign('total_percent', $result['total'] * $percent * 0.01);
		$this->assign('all_total_percent', ($result['alltotal']+$result['alltotalfinsh']) * $percent * 0.01);
		$this->assign('now_merchant', $merchant);
		$this->assign('mer_id', $mer_id);
		$this->display();
	}
	
	//微店中的店铺列表
	public function weidian_store()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;//
		$this->assign('store_id', $store_id);
		$data = array(
			'token'      => intval($this->merchant_session['mer_id']),
			'type'       => 'product,wei_page',
			'site_url'   => $this->config['site_url'],//'http://hf.pigcms.com'
		);
		$data['sign_key'] = $this->create_sign($data);
		$data['request_time'] = time();
		$resultArr = json_decode($this->curl_post('store.php', $data), true);
		
		$this->assign('store_list', $resultArr['stores']);
		$this->display();
		
		die;
		echo "<pre/>";
		print_r($data);
		print_r($resultArr);
	}
	
	//微店中的店铺下面的分销店铺列表
	public function child_stores()
	{
		$stid = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;//
		
		$post_data['token'] = intval($this->merchant_session['mer_id']);
		$post_data['site_url'] = $this->config['site_url'];//'http://hf.pigcms.com';
		$post_data['p'] = 1;
		$post_data['page_size'] = 20;
		$post_data['store_id'] = $stid;
		
		$post_data['sign_key'] = $this->create_sign($post_data);
		$post_data['request_time'] = time();
		$result = $this->curl_post('drp_stores.php', $post_data);
		$result = json_decode($result, true);
		if (empty($result['error_code'])) {
			foreach ($result['stores'] as &$res) {
				$res['date_added'] = date('Y-m-d H:i:s', $res['date_added']);
				$res['sales'] = $res['sales'] == null ? 0 : $res['sales'];
			}
		}
		exit(json_encode($result));
		
	}
	
	//店铺下的分销商
	public function weidian_bill()
	{
		$stid = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$fid = isset($_GET['fid']) ? intval($_GET['fid']) : 0;
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->assign('fid', $fid);
		$post_data['token'] = intval($this->merchant_session['mer_id']);
		$post_data['site_url'] = $this->config['site_url'];//'http://hf.pigcms.com';
		$post_data['p'] = $page;
		$post_data['page_size'] = 20;
		$post_data['store_id'] = $stid;
		
		$post_data['sign_key'] = $this->create_sign($post_data);
		$post_data['request_time'] = time();
		$result = $this->curl_post('drp_store.php', $post_data);
		
		$result = json_decode($result, true);
// 		echo "<pre/>";
// 		// 		print_r($post_data);
// 		print_r($result);
// 		die;
		$order_list = array();
		$order_ids = array();
		$store_id = $total_price = $total_profit = $finish_profit = 0;
		if (empty($result['error_code'])) {
			$store_id = $result['store']['store_id'];
			foreach ($result['orders'] as $row) {
				$temp = array();
				$temp['store_id'] = $row['store_id'];
				$temp['order_id'] = $row['order_id'];
				$temp['order_no'] = $row['order_no'];
				$temp['trade_no'] = $row['trade_no'];
				$temp['add_time'] = $row['add_time'];
				$temp['paid_time'] = $row['paid_time'];
				$temp['total'] = $row['total'];
				$temp['check_amount'] = $row['check_amount'];
				$temp['is_pay_bill'] = 0;
				$total_price += $row['total'];
				$total_profit += $row['check_amount'];
				$order_list[] = $temp;
				$order_ids[] = $row['order_id'];
			}
			if ($order_ids) {
				$bill_list = D('Weidian_distributor_bill')->field('order_id')->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id, 'order_id' => array('in', $order_ids)))->select();
				$finish_bill = array();
				foreach ($bill_list as $bill) {
					$finish_bill[$bill['order_id']] = $bill;
				}
				foreach ($order_list as &$order) {
					if (isset($finish_bill[$order['order_id']])) {
						$finish_profit += $order['check_amount'];
						$order['is_pay_bill'] = 1;
					}
				}
			}
		}
		import('@.ORG.merchant_page');
		$p = new Page($result['page_total'], 20);
		$this->assign('pagebar', $p->show());
		$this->assign('store_id', $store_id);
		$this->assign('total_price', $total_price);
		$this->assign('total_profit', $total_profit);
		$this->assign('finish_profit', $finish_profit);
		
		$this->assign('bill_list', $order_list);
		$this->display();
		die;
		echo "<pre/>";
// 		print_r($post_data);
		print_r($order_list);
		die;
	}
	
	//保存分销商的对账记录
	public function save_bill()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$strids = isset($_GET['strids']) ? htmlspecialchars($_GET['strids']) : '';
		if (empty($store_id)) exit(json_encode(array('error_code' => 1, 'error_msg' => '请选择分销商')));
		if ($strids) {
			$array = explode(',', $strids);
			foreach ($array as $order_id) {
				D('Weidian_distributor_bill')->add(array('store_id' => $store_id, 'order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id']));
			}
			
			$post_data['orders'] = $strids;
			$post_data['sync_balance'] = 1;
			$post_data['store_id'] = $store_id;
			$post_data['sign_key'] = $this->create_sign($post_data);
			$result = $this->curl_post('bill_check.php', $post_data);
			
			$result = json_decode($result, true);
			
		}
		exit(json_encode($result));
	}
	
	
	/**
	 * 店员账单
	 */
	public function staff_bill()
	{
		$mer_id = intval($this->merchant_session['mer_id']);
		$staffid = isset($_GET['staffid']) ? intval($_GET['staffid']) : 0;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : 'shop';
		$staffs = D('Merchant_store_staff')->where(array('token' => $mer_id))->select();
		$staff_name = '';
		foreach ($staffs as $row) {
			if ($row['id'] == $staffid) $staff_name = $row['name'];
		}
		$this->assign(D("Order")->get_offlineorder_by_mer_id($mer_id, $staff_name, $type));
		$this->assign('staffid', $staffid);
		$this->assign('staffs', $staffs);
		$this->assign('type', $type);
		$this->display();
		
	}
	
	public function change()
	{
		$mer_id = intval($this->merchant_session['mer_id']);
		$strids = isset($_GET['strids']) ? htmlspecialchars($_GET['strids']) : '';
		if ($strids) {
			$array = explode(',', $strids);
			$mealids = $groupids = array();
			foreach ($array as $val) {
				$t = explode('_', $val);
				if ($t[0] == 1) {
					$mealids[] = $t[1];
				} elseif ($t[0] == 2) {
					$groupids[] = $t[1];
				} elseif ($t[0] == 3) {
					$shopids[] = $t[1];
				}
			}
			$mealids && D('Store_order')->where(array('mer_id' => $mer_id,'business_type' => 'foodshop', 'business_id' => array('in', $mealids)))->save(array('is_pay_bill' => 1));
			$groupids && D('Group_order')->where(array('mer_id' => $mer_id, 'order_id' => array('in', $groupids)))->save(array('is_pay_bill' => 1));
			$shopids && D('Shop_order')->where(array('mer_id' => $mer_id, 'order_id' => array('in', $shopids)))->save(array('is_pay_bill' => 1));
		}
		exit(json_encode(array('error_code' => 0)));
	}
	
	public function store_order()
	{
		$mer_id = intval($this->merchant_session['mer_id']);
		$percent = '';
		if ($this->merchant_session['percent']) {
			$percent = $this->merchant_session['percent'];
		} elseif ($this->config['platform_get_merchant_percent']) {
			$percent = $this->config['platform_get_merchant_percent'];
		}
		$this->assign('percent', $percent);
		$merchant = D('Merchant')->field(true)->where('mer_id=' . $mer_id)->find();
		$result = D("Order")->get_order_by_mer_id($mer_id, 'store');
		$this->assign($result);
		$this->assign('total_percent', $result['total'] * (100 - $percent) * 0.01);
		$this->assign('all_total_percent', ($result['alltotal'] + $result['alltotalfinsh']) * (100 - $percent) * 0.01);
		$this->assign('now_merchant', $merchant);
		$this->assign('mer_id', $mer_id);
		$this->display();
	}
	
	
	private function create_sign($data)
	{
		$data['salt'] = C('config.weidian_sign');
		ksort($data);
		$sign_key = sha1(http_build_query($data));
		return $sign_key;
	}
	private function curl_post($url, $post)
	{
		$url = $this->config['weidian_url'].'/api/' . $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// post数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// post的变量
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$output = curl_exec($ch);
		curl_close($ch);
		//返回获得的数据
		return $output;
	}
}
?>