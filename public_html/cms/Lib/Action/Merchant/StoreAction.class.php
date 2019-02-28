<?php
/*
 * 店员中心
 */

class StoreAction extends BaseAction{
	
	protected $store;
	protected $now_merchant;
	public function _initialize(){
		parent::_initialize();
		if(ACTION_NAME != 'login' && ACTION_NAME != 'cashierBack'){
			if(empty($this->staff_session)){
				redirect(U('Store/login'));
				exit();
			}else{
				$staff_type=array(0=>'店小二',1=>'核销',2=>'店长');
				$this->staff_session['type_name'] = $staff_type[$this->staff_session['type']];
				$this->assign('staff_session',$this->staff_session);
				$database_merchant_store = D('Merchant_store');
				$condition_merchant_store['store_id'] = $this->staff_session['store_id'];
				$this->store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
				if(empty($this->store)){
					$this->error('店铺不存在！');
				}

				$this->assign('store',$this->store);
			}
		}

		// if($this->config['open_score_fenrun']){
			$now_merchant = D('Merchant')->get_info($this->store['mer_id']);
			if($now_merchant['score_get_percent']>=0){
				$this->config['score_get'] = $now_merchant['score_get_percent']/100;
			}
		// }
	}
	public function login(){
		if(IS_POST){
			if(md5($_POST['verify']) != $_SESSION['merchant_store_login_verify']){
				exit(json_encode(array('error'=>'1','msg'=>'验证码不正确！','dom_id'=>'verify')));
			}

			$database_store_staff = D('Merchant_store_staff');
			$condition_store_staff['username'] = $_POST['account'];
			$now_staff = $database_store_staff->field(true)->where($condition_store_staff)->find();

			if(empty($now_staff)){
				exit(json_encode(array('error'=>'2','msg'=>'帐号不存在！','dom_id'=>'account')));
			}
			$pwd = md5($_POST['pwd']);
			if($pwd != $now_staff['password']){
				exit(json_encode(array('error'=>'3','msg'=>'密码错误！','dom_id'=>'pwd')));
			}
			$data_store_staff['id'] = $now_staff['id'];
			$data_store_staff['last_time'] = $_SERVER['REQUEST_TIME'];
			if($database_store_staff->data($data_store_staff)->save()){
				session('staff',$now_staff);

				if ($now_staff['type'] == 2) {
    				$store = M('Merchant_store')->field(true)->where(array('store_id' => $now_staff['store_id']))->find();
    				$database_merchant = D('Merchant');
    				$condition_merchant['mer_id'] = $store['mer_id'];
    				$now_merchant = $database_merchant->field(true)->where($condition_merchant)->find();

    				if(!empty($now_merchant['last_ip'])){
    				    import('ORG.Net.IpLocation');
    				    $IpLocation = new IpLocation();
    				    $last_location = $IpLocation->getlocation(long2ip($now_merchant['last_ip']));
    				    $now_merchant['last']['country'] = iconv('GBK','UTF-8',$last_location['country']);
    				    $now_merchant['last']['area'] = iconv('GBK','UTF-8',$last_location['area']);
    				}
    				$now_merchant['store_id'] = $now_staff['store_id'];
    				session('merchant', $now_merchant);
				}
				exit(json_encode(array('error'=>'0','msg'=>'登录成功,现在跳转~','dom_id'=>'account')));
			}else{
				exit(json_encode(array('error'=>'6','msg'=>'登录信息保存失败,请重试！','dom_id'=>'account')));
			}
		}else{
			$this->display();
		}
	}
	public function index(){
		$this->display();
	}
	public function coupon_list(){
		$store_id = $this->store['store_id'];
		$condition_where = "`ear`.`uid`=`u`.`uid` AND `ear`.`activity_list_id`=`eal`.`pigcms_id` AND `ecr`.`record_id`=`ear`.`pigcms_id` AND `ecr`.`store_id`='$store_id'";

		$condition_table = array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_coupon_record'=>'ecr',C('DB_PREFIX').'user'=>'u');
		$order_list = D('')->field('`eal`.`name`,`ecr`.*,`ear`.`time`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order('`ecr`.`check_time` DESC')->select();
		$this->assign('order_list',$order_list);
		$this->display();
	}
	public function coupon_find(){
		if(IS_POST){
			$mer_id = $this->store['mer_id'];
			$condition_where = "`ear`.`uid`=`u`.`uid` AND `ear`.`activity_list_id`=`eal`.`pigcms_id` AND `ecr`.`record_id`=`ear`.`pigcms_id` AND `eal`.`mer_id`='$mer_id'";
			$find_value = $_POST['find_value'];
			$store_id = $this->store['store_id'];
			if($_POST['find_type'] == 1 && strlen($find_value) == 16){
				$condition_where .= " AND `ecr`.`number`='$find_value'";
			}else{
				$condition_where .= " AND `ecr`.`store_id`='$store_id'";
				if($_POST['find_type'] == 1){
					$condition_where .= " AND `ecr`.`number` like '$find_value%'";
				}else if($_POST['find_type'] == 2){
					$condition_where .= " AND `eal`.`pigcms_id` like '$find_value%'";
				}else if($_POST['find_type'] == 3){
					$condition_where .= " AND `u`.`uid`='$find_value'";
				}else if($_POST['find_type'] == 4){
					$condition_where .= " AND `u`.`nickname`='$find_value'";
				}else if($_POST['find_type'] == 5){
					$condition_where .= " AND `u`.`phone` like '$find_value%'";
				}
			}
			$condition_table = array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_coupon_record'=>'ecr',C('DB_PREFIX').'user'=>'u');
			$order_list = D('')->field('`eal`.`name`,`ecr`.*,`ear`.`time`,`u`.`uid`,`u`.`nickname`,`u`.`phone`,`ecr`.`check_time`')->where($condition_where)->table($condition_table)->order('`ecr`.`check_time` DESC')->select();
			if($order_list){
				foreach($order_list as $key=>$value){
					$order_list[$key]['time_txt'] = date('Y-m-d H:i:s',$value['time']);
					$order_list[$key]['check_time_txt'] = date('Y-m-d H:i:s',$value['check_time']);
				}
			}
			$return['list'] = $order_list;
			$return['row_count'] = count($order_list);
			echo json_encode($return);
		}else{
			$this->display();
		}
	}
	public function coupon_verify(){
		$mer_id = $this->store['mer_id'];
		$condition_table = array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_coupon_record'=>'ecr');
		$condition_where = "`ear`.`activity_list_id`=`eal`.`pigcms_id` AND `ecr`.`record_id`=`ear`.`pigcms_id` AND `eal`.`mer_id`='$mer_id' AND `ecr`.`pigcms_id`='{$_GET['id']}'";
		$now_order = D('')->field('`ecr`.`pigcms_id`,`eal`.`pigcms_id` as id,`eal`.`money`,`eal`.`name`')->where($condition_where)->table($condition_table)->find();
		if(!empty($now_order)){
			if(D('Extension_coupon_record')->where(array('pigcms_id'=>$now_order['pigcms_id']))->data(array('check_time'=>time(),'store_id'=>$this->store['store_id'],'last_staff'=>$this->staff_session['name']))->save()){
				//验证增加商家余额
				if($now_order['money']>0){
					$now_order['order_type'] ='coupon';
					$now_order['order_id'] =$now_order['pigcms_id'];
					$now_order['mer_id']  = $mer_id;
					//D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$now_order['name'].'记入收入',$now_order);
					$now_order['desc']='用户购买'.$now_order['name'].'记入收入';
					D('SystemBill')->bill_method(0,$now_order);
					//商家推广分佣
					$now_user = M('User')->where(array('uid'=>$now_order['uid']))->find();
					D('Merchant_spread')->add_spread_list($now_order,$now_user,$now_order['order_type'],$now_user['nickname'].'用户购买平台活动商品获得佣金');
				}
				$this->success('验证成功！');
			}else{
				$this->error('验证失败！请重试。');
			}
		}else{
			$this->error('当前订单不存在！');
		}
	}
	/* 团购相关 */
	protected function check_group(){
		if(empty($this->store['have_group'])){
			$this->error('您访问的店铺没有开通'.$this->config['group_alias_name'].'功能！');
		}
	}
	public function group_list(){
		$this->check_group();
		$store_id = $this->store['store_id'];

		$condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `o`.`store_id`='$store_id'";

		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$condition_where .= " AND `o`.`real_orderid`='" . htmlspecialchars($_GET['keyword'])."'";
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
				$condition_where .= " AND `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
			} elseif ($_GET['searchtype'] == 'name') {
				$condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			} elseif ($_GET['searchtype'] == 'phone') {
				$condition_where .= " AND `u`.`phone`='" . htmlspecialchars($_GET['keyword']) . "'";
			} elseif ($_GET['searchtype'] == 's_name') {
				$condition_where .= " AND `g`.`s_name` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}elseif ($_GET['searchtype'] == 'third_id') {
				$condition_where .= " AND `o`.`third_id` =".$_GET['keyword'];
			}
		}


		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;

		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';

		if ($status != -1) {
			$condition_where .= " AND `o`.`status`={$status}";
		}
		if($pay_type){
			if($pay_type=='balance'){
				$condition_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )";
			}else{
				$condition_where .= " AND `o`.`pay_type`='{$pay_type}'";
			}
		}

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}

			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$condition_where .= " AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
			//$condition_where['_string']=$time_condition;
		}

		$condition_table = array(C('DB_PREFIX').'group_order'=>'o',C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'user'=>'u');

		$order_count = D('')->where($condition_where)->table($condition_table)->count();
		import('@.ORG.merchant_page');
		$p = new Page($order_count, 15);

		$order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order('`o`.`add_time` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

		foreach ($order_list as &$item) {
			if($item['is_head'] || $item['pin_fid']>0){
				$group_start = D('Group_start')->get_group_start_by_order_id($item['order_id']);
				$item['group_start_status']  =$group_start['status'];
			}else{
				$item['group_start_status']  = 1;

			}
		}
		$this->assign('order_list',$order_list);
		$this->assign('pagebar',$p->show());
		$pay_method = D('Config')->get_pay_method('','',1);
		$this->assign('pay_method',$pay_method);
		$this->assign(array( 'status' => $status,'pay_type'=>$pay_type));
		$this->assign('status_list', D('Group_order')->status_list);
		$this->display();
	}
	public function group_find(){
		if(IS_POST){
			$mer_id = $this->store['mer_id'];
			$condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `o`.`mer_id`='$mer_id'";
			$find_value = $_POST['find_value'];
			$store_id = $this->store['store_id'];
			if($_POST['find_type'] == 1 && strlen($find_value) == 14){
				$res = D('Group_pass_relation')->get_orderid_by_pass($find_value);
				if(!empty($res)){
					$condition_where .= " AND `o`.`order_id`=".$res['order_id'];
				}else{
					$condition_where .= " AND `o`.`group_pass`='$find_value'";
				}
			}else{
				$condition_where .= " AND `o`.`store_id`='$store_id'";
				if($_POST['find_type'] == 1){
					$condition_where .= " AND `o`.`group_pass` like '$find_value%'";
				}else if($_POST['find_type'] == 2){
					$condition_where .= " AND `o`.`express_id` like '$find_value%'";
				}else if($_POST['find_type'] == 3){
					$condition_where .= " AND `o`.`real_orderid`='$find_value'";
				}else if($_POST['find_type'] == 4){
					$condition_where .= " AND `o`.`group_id`='$find_value'";
				}else if($_POST['find_type'] == 5){
					$condition_where .= " AND `o`.`uid`='$find_value'";
				}else if($_POST['find_type'] == 6){
					$condition_where .= " AND `u`.`nickname` like '$find_value%'";
				}else if($_POST['find_type'] == 7){
					$condition_where .= " AND `o`.`phone` like '$find_value%'";
				}
			}
			$condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'group_order'=>'o',C('DB_PREFIX').'user'=>'u');
			$order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order('`o`.`add_time` DESC')->select();
			if($order_list){
				foreach($order_list as $key=>$value){
//					$value['coupon_price'] = D('Group_order')->get_coupon_info($value['order_id']);
//					$order_list[$key]['un_pay'] =$value['total_money']-$value['wx_cheap']-$value['merchant_balance']-$value['balance_pay']-$value['score_deducte']-$value['coupon_price'];
//					$order_list[$key]['un_pay_num'] = $value['num']-round(($value['total_money']-$order_list[$key]['un_pay'])/$value['price']);
					//$order_list[$key]['un_pay_num'] = D('Group_pass_relation')->get_pass_num($value['order_id'],3);
					$order_list[$key]['add_time'] = date('Y-m-d H:i:s',$value['add_time']);
					$order_list[$key]['pay_time'] = date('Y-m-d H:i:s',$value['pay_time']);
					$order_list[$key]['use_time'] = !empty($value['use_time']) ? date('Y-m-d H:i:s',$value['use_time']):'';
					if($value['is_head'] || $value['pin_fid']>0){
						$group_start = D('Group_start')->get_group_start_by_order_id($value['order_id']);
						$order_list[$key]['group_start_status']  =$group_start['status'];
					}else{
						$order_list[$key]['group_start_status']  = 1;

					}
				}
			}

			$return['list'] = $order_list;
			$return['row_count'] = count($order_list);
			echo json_encode($return);
		}else{
			$this->check_group();
			$this->display();
		}
	}
	public function group_verify(){
		$this->check_group();
		$database_group_order = D('Group_order');
		$now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],false);
		if(empty($now_order['paid'])){
			$this->error('此订单尚未支付！');
		}
		if($now_order['status']!=0){
			$this->error('此订单尚不是未消费！');
		}
		if(empty($now_order)){
			$this->error('当前订单不存在！');
		}else if($now_order['paid'] && $now_order['status'] == 0){
			$condition_group_order['order_id'] = $now_order['order_id'];
			if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
				$data_group_order['third_id'] = $now_order['order_id'];
			}
			$data_group_order['status'] = '1';
			$data_group_order['store_id'] = $this->store['store_id'];
			$data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
			$data_group_order['last_staff'] = $this->staff_session['name'];
			if($database_group_order->where($condition_group_order)->data($data_group_order)->save()){
				D('Group_pass_relation')->change_refund_status($now_order['order_id'],1);
				//验证增加商家余额
				$now_order['order_type'] = 'group';
				$now_order['verify_all'] = 1;
				$now_order['store_id'] =$this->store['store_id'];

				//D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$now_order['name'].'记入收入',$now_order);
				$now_order['desc']='用户购买'.$now_order['name'].'记入收入';
				D('SystemBill')->bill_method($now_order['is_own'],$now_order);

				$this->group_notice($now_order,1);
				$this->success('验证消费成功！');

			}else{
				$this->error('验证失败！请重试。');
			}
		}else{
			$this->error('当前订单的状态并不是未消费。');
		}
	}

	public function group_pass_array(){
		$this->check_group();
		$database_group_order = D('Group_order');
		$now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],false);
		$now_order['coupon_price'] = D('Group_order')->get_coupon_info($now_order['order_id']);
		$un_pay =$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price'];
		$has_pay = $now_order['total_money']-$un_pay;
		$pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
		$un_consume_num  = D('Group_pass_relation')->get_pass_num($now_order['order_id'],0);
		foreach($pass_array as &$v){
			$v['need_pay'] = $has_pay>$now_order['price']?0:$now_order['price']-$has_pay;
			$has_pay=($has_pay-$now_order['price'])>0?$has_pay-$now_order['price']:0;
		}
		$this->assign('un_consume_num',$un_consume_num);
		$this->assign('pass_array',$pass_array);
		$this->assign('now_order',$now_order);
		$this->display();
	}

	public function group_array_verify(){
		$this->check_group();
		$database_group_order = D('Group_order');
		$verify_all = false;
		$now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'],$_POST['order_id'],false);
		if(empty($now_order['paid'])){
			$this->error('此订单尚未支付！');
		}
		if($now_order['status']!=0){
			$this->error('此订单尚不是未消费！');
		}
		if(empty($now_order)){
			$this->error('当前订单不存在！');
		}else{
			$where['order_id'] =$_POST['order_id'];
			$where['group_pass'] =$_POST['group_pass'];
			$group_pass_rela = D('Group_pass_relation');
			$res = $group_pass_rela->where($where)->find();
			if(!empty($res)){
				$date['status']=1;
				if($group_pass_rela->where($where)->data($date)->save()){
					$count = $group_pass_rela->get_pass_num($where['order_id']);
					$count += $group_pass_rela->get_pass_num($where['order_id'],3);

					if($count==0){
						if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
							$data_group_order['third_id'] = $now_order['order_id'];
						}
						$data_group_order['status'] = '1';
						$verify_all = true;
					}else{
						$now_order['res'] = $res;
					}
					$data_group_order['store_id'] = $this->store['store_id'];
					$now_order['store_id'] = $this->store['store_id'];
					$condition_group_order['order_id'] = $where['order_id'];
					$data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
					$data_group_order['last_staff'] = $this->staff_session['name'];
					if(D('Group_order')->where($condition_group_order)->data($data_group_order)->save()){
						//验证增加商家余额
						$now_order['order_type'] = 'group';
						$now_order['verify_all'] = 0;
						$now_order['mer_id'] = $this->store['mer_id'];
						//D('Merchant_money_list')->add_money($this->store['mer_id'],'验证团购订单'.$now_order['real_orderid'].'的消费码</br>'.$_POST['group_pass'].'记入收入',$now_order);
						$now_order['desc']='验证团购订单'.$now_order['real_orderid'].'的消费码</br>'.$_POST['group_pass'].'记入收入';
						D('SystemBill')->bill_method($now_order['is_own'],$now_order);

						$this->group_notice($now_order,$verify_all);
						$this->success('验证消费成功！');
					}else{
						$this->error('验证失败！请重试。');
					}
				}else{
					$this->error("验证消费成功！");
				}
			}else{
				exit('此消费码不存在！');
			}
		}
	}

	public function group_edit(){
		$this->check_group();
		$now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],false);

		if(empty($now_order)){
			exit('此订单不存在！');
		}
		if($now_order['tuan_type'] == 2 && $now_order['paid'] == 1){
			$express_list = D('Express')->get_express_list();
			$this->assign('express_list',$express_list);
		}
		if(!empty($now_order['pay_type'])){
			if($now_order['is_pick_in_store']){
				$now_order['paytypestr']="到店自提";
			}else{
				$now_order['paytypestr'] = D('Pay')->get_pay_name($now_order['pay_type']);
			}
			if(($now_order['pay_type']=='offline') && !empty($now_order['third_id']) && ($now_order['paid']==1)){
				$now_order['paytypestr'] .='<span style="color:green">&nbsp; 已支付</span>';
			}else if(($now_order['pay_type']!='offline') && ($now_order['paid']==1)){
				$now_order['paytypestr'] .='<span style="color:green">&nbsp; 已支付</span>';
			}else{
				$now_order['paytypestr'] .='<span style="color:red">&nbsp; 未支付</span>';
			}
		}else{
			$now_order['paytypestr'] = '未知';
		}
//		if(!empty($now_order['coupon_id'])) {
//			$system_coupon = D('System_coupon')->get_coupon_info($now_order['coupon_id']);
//			$now_order['coupon_price'] = $system_coupon['price'];
//			$this->assign('system_coupon',$system_coupon);
//		}else if(!empty($now_order['card_id'])) {
//			$card = D('Member_card_coupon')->get_coupon_info($now_order['card_id']);
//			$now_order['coupon_price'] = $card['price'];
//			$this->assign('card', $card);
//		}

		if($now_order['trade_info']){
			$trade_info_arr = unserialize($now_order['trade_info']);
			if($trade_info_arr['type'] == 'hotel'){
				$trade_hotel_info = D('Trade_hotel_category')->format_order_trade_info($now_order['trade_info']);
				$this->assign('trade_hotel_info',$trade_hotel_info);
			}
		}
		$pin_info = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
		$this->assign('pin_info', $pin_info);

		$pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
		$this->assign('pass_array',$pass_array);
		$this->assign('now_order',$now_order);
		$this->display();
	}

	public function group_express(){
		$this->check_group();
		$now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],false);
		if(empty($now_order)){
			$this->error('此订单不存在！');
		}
		if(empty($now_order['paid'])){
			$this->error('此订单尚未支付！');
		}

		if($now_order['status']!=0){
			$this->error('此订单尚不是未消费！');
		}

		$condition_group_order['order_id'] = $now_order['order_id'];
		$data_group_order['express_type'] = $_POST['express_type'];
		$data_group_order['express_id'] = $_POST['express_id'];
		$data_group_order['last_staff'] = $this->staff_session['name'];
		$data_group_order['last_time'] = $_SERVER['REQUEST_TIME'];
		if($now_order['paid'] == 1 && $now_order['status'] == 0){
			if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
				$data_group_order['third_id'] = $now_order['order_id'];
			}
			$data_group_order['status'] = 0; //原来是1
			$data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
			$data_group_order['store_id'] = $this->store['store_id'];
		}

		if(D('Group_order')->where($condition_group_order)->data($data_group_order)->save()){
			$now_user = D('User')->get_user($now_order['uid'],'uid');
			$express_name = D('Express')->get_express($_POST['express_type']);
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$href = C('config.site_url').'/wap.php?c=My&a=group_order&order_id='.$now_order['order_id'];
			$model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $this->config['group_alias_name'].'快递发货通知', 'OrderSn' => $now_order['real_orderid'], 'OrderStatus' =>$this->staff_session['name'].'已为您发货', 'remark' =>'快递号：'.$_POST['express_id'].'('.$express_name['name'].'),请尽快确认'), $this->store['mer_id']);

			D('Group_order')->group_app_notice($now_order['order_id'],6);
//			$this->group_notice($now_order,1);
			$this->success('修改成功！');
		}else{
			$this->error('修改失败！请重试。');
		}
	}

	public function group_pick(){
		$this->check_group();
		$now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],false);
		if(empty($now_order)){
			$this->error('此订单不存在！');
		}
		if(empty($now_order['paid'])){
			$this->error('此订单尚未支付！');
		}


		if($now_order['status']!=0){
			$this->error('此订单尚不是未消费！');
		}

		$condition_group_order['order_id'] = $now_order['order_id'];
		$date['status']=1;
		$date['paid'] = 1;
		$date['last_staff'] = $this->staff_session['name'];
		$date['use_time'] = $_SERVER['REQUEST_TIME'];
		if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
			$date['third_id'] = $now_order['order_id'];
		}
		if(D('Group_order')->where($condition_group_order)->data($date)->save()){

			//验证增加商家余额
			$now_order['order_type']='group';
			$now_order['verify_all']=1;
			$now_order['store_id'] =$this->store['store_id'];
			//D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$now_order['name'].'记入收入',$now_order);
			$now_order['desc']='用户购买'.$now_order['name'].'记入收入';
			D('SystemBill')->bill_method($now_order['is_own'],$now_order);

			$this->group_notice($now_order,1);
			$this->success('修改成功！');
		}else{
			$this->error('修改失败！请重试。');
		}
	}

	public function group_remark(){
		$this->check_group();
		$now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],true,false);
		if(empty($now_order)){
			$this->error('此订单不存在！');
		}
		if(empty($now_order['paid'])){
			$this->error('此订单尚未支付！');
		}
		$condition_group_order['order_id'] = $now_order['order_id'];
		$data_group_order['merchant_remark'] = $_POST['merchant_remark'];
		if(D('Group_order')->where($condition_group_order)->data($data_group_order)->save()){
			$this->success('修改成功！');
		}else{
			$this->error('修改失败！请重试。');
		}
	}


	/*检查是否开启订餐*/
	protected function check_meal(){
		if(empty($this->store['have_meal'])){
			$this->error('您访问的店铺没有开通'.$this->config['meal_alias_name'].'功能！');
		}
	}


	public function meal_list()
	{
		$this->check_meal();
		$store_id = intval($this->store['store_id']);
		$where = array();
		if (IS_POST) {
			$order_id = isset($_POST['order_id']) ? htmlspecialchars($_POST['order_id']) : '';
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
			$meal_pass = isset($_POST['meal_pass']) ? htmlspecialchars($_POST['meal_pass']) : '';
			$table_name = isset($_POST['table_name']) ? htmlspecialchars($_POST['table_name']) : '';
			$order_id && $where['order_id'] = array('like', "%$order_id%");
			$name && $where['name'] = array('like', "%$name%");
			$phone && $where['phone'] = array('like', "%$phone%");
			$meal_pass && $where['meal_pass'] = array('like', "%$meal_pass%");
			if ($table_name) {
				$tables = D('Merchant_store_table')->where(array('name' => array('like', "%$table_name%"), 'store_id' => $store_id))->select();
				$tableids = array();
				foreach ($tables as $table) {
					$tableids[] = $table['pigcms_id'];
				}
				$tableids && $where['tableid'] = array('in', $tableids);
			}
			$this->assign('meal_pass', $meal_pass);
			$this->assign('order_id', $order_id);
			$this->assign('name', $name);
			$this->assign('phone', $phone);
			$this->assign('table_name', $table_name);
		}

		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= $type . ' ' . $sort . ',';
			$order_sort .= 'order_id DESC';
		} else {
			$order_sort .= 'order_id DESC';
		}
		if ($status != -1) {
			$where['status'] = $status;
		}

		$this->assign(D("Meal_order")->get_order_list($this->store['mer_id'], $store_id, $where, $order_sort));
		$this->assign('now_store', $this->store);

		$this->assign('status_list', D('Meal_order')->status_list);
		$this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status));
		$this->display();
	}

	public function order_detial() {
		$now_order = D('Meal_order')->get_order_by_orderid( $_GET['order_id']);
		if (empty($now_order)) {
			exit('此订单不存在！');
		}
		$now_order['info']=unserialize($now_order['info']);
		if(!empty($now_order['coupon_id'])) {
			$system_coupon = D('System_coupon')->get_coupon_info($now_order['coupon_id']);
			$now_order['coupon_price'] = $system_coupon['price'];
			$this->assign('system_coupon',$system_coupon);
		}else if(!empty($now_order['card_id'])) {
			$card = D('Member_card_coupon')->get_coupon_info($now_order['card_id']);
			$now_order['coupon_price'] = $card['price'];
			$this->assign('card', $card);
		}
		if ($now_order['total_price'] > 0) {
			$now_order['offline_money'] = (floor($now_order['total_price'] * 100) - floor($now_order['minus_price'] * 100) - floor($now_order['balance_pay'] * 100) - floor($now_order['merchant_balance'] * 100) - floor($now_order['coupon_price'] * 100) - floor($now_order['card_price'] * 100) - floor($now_order['score_deducte'] * 100) - floor($now_order['payment_money'] * 100))/100;
		} else {
			$now_order['offline_money'] = (floor($now_order['price'] * 100) - floor($now_order['balance_pay'] * 100) - floor($now_order['merchant_balance'] * 100) - floor($now_order['coupon_price'] * 100) - floor($now_order['card_price'] * 100) - floor($now_order['score_deducte'] * 100) - floor($now_order['payment_money'] * 100))/100;
		}

		$mode = new Model();
		$sql = "SELECT u.name, u.phone FROM " . C('DB_PREFIX') . "deliver_supply AS s INNER JOIN " . C('DB_PREFIX') . "deliver_user AS u ON u.uid=s.uid WHERE s.order_id={$now_order['order_id']} AND s.item=0";
		$res = $mode->query($sql);
		$res = isset($res[0]) && $res[0] ? $res[0] : '';
		$now_order['deliver_user_info'] = $res;
		$this->assign('order',$now_order);
		$this->display();
	}

	public function meal_edit(){
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$store_id = intval($this->store['store_id']);
		if (IS_POST) {
			if (isset($_POST['status'])) {
				$status = intval($_POST['status']);
				if ($order = D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
					$data = array('store_uid' => $this->staff_session['id'], 'status' => $status);
					$data['last_staff'] = $this->staff_session['name'];
					if ($order['paid'] == 0) $this->error('当前订单的状态并不是未消费。');
					if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
						$order['paid'] = 0;
						$notOffline = 1;
						if ($this->config['pay_offline_open'] == 1) {
							$now_merchant = D('Merchant')->get_info($order['mer_id']);
							if ($now_merchant) {
								$notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
							}
						}
						if ($notOffline) {
							$this->error('当前订单的状态并不是未消费。');
							exit;
						}
					}

					if ($status && $order['paid'] == 0) {//将未支付的订单，由店员改成已消费，其订单状态则修改成线下已支付！
						$data['third_id'] = $order['order_id'];
						$order['pay_type'] = $data['pay_type'] = 'offline';
						$data['paid'] = 1;
						$price = $order['total_price'] > 0 ? $order['total_price'] : $order['price'];
						$data['pay_money'] = $price - $order['minus_price'];
						$order['pay_time'] = $_SERVER['REQUEST_TIME'];
					}
					$data['use_time'] = $_SERVER['REQUEST_TIME'];
					if (D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
						if ($status && $order['status'] == 0) {
							if ($supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 0))->find()) {
								if ($supply['status'] < 2) {
									D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 0))->delete();
								} else {
									D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 0))->save(array('status' => 5));
								}
							}
							$this->meal_notice($order);
						}

						$this->success("更新成功", U('Store/meal_list'));
					} else {
						$this->success("更新失败，稍后重试", U('Store/meal_list'));
					}
				} else {
					$this->error('不合法的请求');
				}
			} else {
				$this->redirect(U('Store/meal_list'));
			}
		} else {
			$order = D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find();
			$order['info'] = unserialize($order['info']);
			if ($order['store_uid']) {
				$staff = D("Merchant_store_staff")->where(array('id' => $order['store_uid']))->find();
				$order['store_uname'] = $staff['name'];
			}
			if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
				$order['paid'] = 0;
			}

			if (empty($order['tableid'])) {
				$order['tablename'] = '不限';
			} else {
				$table = D('Merchant_store_table')->where(array('pigcms_id' => $order['tableid'], 'store_id' => $store_id))->find();
				$order['tablename'] = isset($table['name']) ? $table['name'] : '不限';
			}
			if(!empty($order['coupon_id'])) {
				$system_coupon = D('System_coupon')->get_coupon_info($order['coupon_id']);
				$order['coupon_price'] = $system_coupon['price'];
				$this->assign('system_coupon',$system_coupon);
			}else if(!empty($now_order['card_id'])) {
				$card = D('Member_card_coupon')->get_coupon_info($order['card_id']);
				$order['coupon_price'] = $card['price'];
				$this->assign('card', $card);
			}
			$this->assign('order', $order);
			$this->display();
		}
	}

	/*预约订单列表*/
	public function appoint_list(){
		$store_id = $this->store['store_id'];

		$database_order = D('Appoint_order');
		//$database_user = D('User');
		$database_appoint = D('Appoint');
		//$database_store = D('Merchant_store');
		$where['a.store_id'] = $store_id;
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'order_id') {
				$where['a.order_id'] = $_GET['keyword'];
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['a.order_id'] = $tmp_result['order_id'];
			}elseif ($_GET['searchtype'] == 'name') {
				$where['u.username'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['u.phone'] = htmlspecialchars($_GET['keyword']);
			}elseif ($_GET['searchtype'] == 'third_id') {
				$where['a.third_id'] =$_GET['keyword'];
			}
		}
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type){
			if($pay_type=='balance'){
				$where['_string'] = "(`a`.`balance_pay`<>0 OR `a`.`merchant_balance` <> 0 OR a.product_merchant_balance <> 0 OR a.product_balance_pay <> 0 )";
			}else{
				$where['a.pay_type'] = $pay_type;
			}
		}

		$now_appoint = $database_appoint->field(true)->where(array('appoint_id'=>$_GET['appoint_id']))->find();
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] .= (empty($where['_string'])?'':' AND ')." (a.order_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}
		$order_count = $database_order->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
				->where($where)->count();

		import('@.ORG.merchant_page');
		$page = new Page($order_count, 20);
		$order_list = $database_order->field('a.*,ap.appoint_name,a.appoint_type,a.mer_id,u.uid,u.nickname,a.order_time,a.pay_time,u.phone,s.name as store_name,s.adress as store_adress')
				->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
				->where($where)->order('`order_id` DESC')->limit($page->firstRow.','.$page->listRows)->select();



//		$order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->select();
//
//		$uidArr = array();
//		foreach($order_info as $v){
//			array_push($uidArr,$v['uid']);
//		}
//
//		$uidArr = array_unique($uidArr);
//		$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();
//		$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
//		$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
//		$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
		$pagebar = $page->show();
		$pay_method = D('Config')->get_pay_method('','',1);
		$this->assign('pay_method',$pay_method);
		$this->assign('pay_type',$pay_type);
		$this->assign('pagebar', $pagebar);
		$this->assign('order_list', $order_list);
		$this->display();
	}


	public function allot_appoint_list(){
		$store_id = $this->store['store_id'];

		$database_order = D('Appoint_order');
		$database_user = D('User');
		$database_appoint = D('Appoint');
		$database_store = D('Merchant_store');
		//$database_merchant_workers = D('Merchant_workers');

		$where['store_id'] = $store_id;
		$where['type'] = array('in',array(1,2));
		//$where['is_del'] = 0;
		$order_info = $database_order->field(true)->where($where)->order('`merchant_allocation_time` desc ,`order_id` DESC')->select();
		$uidArr = array();
		foreach($order_info as $v){
			array_push($uidArr,$v['uid']);
		}

		$uidArr = array_unique($uidArr);
		$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();
		$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
		$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
		$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);

		$this->assign('order_list', $order_list);
		$this->display();
	}

	/*预约订单查找*/
	public function appoint_find(){
		if(IS_POST){
			$database_order = D('Appoint_order');
			$database_user = D('User');
			$database_appoint = D('Appoint');
			$database_store = D('Merchant_store');

			$appoint_where['a.mer_id'] = $this->store['mer_id'];
			if($_POST['find_type'] == 1 && strlen($_POST['find_value']) == 16){
				$appoint_where['a.appoint_pass'] = $_POST['find_value'];
			} else {
				if($_POST['find_type'] == 1){
					$appoint_where['a.appoint_pass'] = array('LIKE', '%'.$_POST['find_value'].'%');
				} else if($_POST['find_type'] == 2){
					$appoint_where['a.order_id'] = $_POST['find_value'];
				} else if($_POST['find_type'] == 3){
					$appoint_where['a.appoint_id'] = $_POST['find_value'];
				} else if($_POST['find_type'] == 4){
					$appoint_where['a.uid'] = $_POST['find_value'];
				} else if($_POST['find_type'] == 5){
					$appoint_where['u.nickname'] = array('LIKE', '%'.$_POST['find_value'].'%');
				} else if($_POST['find_type'] == 6){
					$appoint_where['u.phone'] = array('LIKE', '%'.$_POST['find_value'].'%');
				}
			}

			$order_info = $database_order->field('a.*,u.nickname,u.phone')->join('as a LEFT JOIN '.C('DB_PREFIX').'user u on u.uid = a.uid')->where($appoint_where)->order('`order_id` DESC')->select();
			//$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where($user_where)->select();
			$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
			$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
			$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
			if($order_list){
				foreach($order_list as $key=>$val){
					if($_POST['find_type'] == 5){
						if(!isset($val['nickname'])){
							unset($order_list[$key]);
							continue;
						}
					}else if($_POST['find_type'] == 6){
						if(!isset($val['phone'])){
							unset($order_list[$key]);
							continue;
						}
					}
					$order_list[$key]['pay_time'] = $order_list[$key]['pay_time']>0?date('Y-m-d H:i:s', $order_list[$key]['pay_time']):'';
					$order_list[$key]['order_time'] = date('Y-m-d H:i:s', $order_list[$key]['order_time']);
				}
			}

			$return['list'] = array_values($order_list);
			$return['row_count'] = count($order_list);
			echo json_encode($return);
		} else {
			$this->display();
		}
	}

	/*订单详情*/
	public function appoint_detail(){
		$where['order_id'] = $_GET['order_id'] + 0;

		$database_order = D('Appoint_order');
		$database_user = D('User');
		$database_appoint = D('Appoint');
		$database_store = D('Merchant_store');
		$database_appoint_visit_order_info = D('Appoint_visit_order_info');
		$database_merchant_workers = D('Merchant_workers');
		$database_appoint_product = D('Appoint_product');

		$order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->select();

		$uidArr = array();
		foreach($order_info as $v){
			array_push($uidArr,$v['uid']);
		}

		$uidArr = array_unique($uidArr);
		$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();

		$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
		$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
		$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
		$now_order = $order_list[0];
		$cue_info = unserialize($now_order['cue_field']);
		$cue_list = array();
		foreach($cue_info as $key=>$val){
			if(!empty($cue_info[$key]['value'])){
				$cue_list[$key]['name'] = $val['name'];
				$cue_list[$key]['value'] = $val['value'];
				$cue_list[$key]['type'] = $val['type'];
				if($cue_info[$key]['type'] == 2){
					$cue_list[$key]['long'] = $val['long'];
					$cue_list[$key]['lat'] = $val['lat'];
					$cue_list[$key]['address'] = $val['address'];
				}
			}
		}

		$product_detail = $database_appoint_product->get_product_info($now_order['product_id']);
		if($product_detail['status']){
			$now_order['product_detail'] = $product_detail['detail'];
		}

		//上门预约工作人员信息start
		$tmp_order_info=reset($order_info);

		$Map['appoint_order_id'] = $tmp_order_info['order_id'];
		$Map['uid'] = $tmp_order_info['uid'];
		$appoint_visit_order_info = $database_appoint_visit_order_info->where($Map)->find();
		$service_address=  unserialize($appoint_visit_order_info['service_address']);
		if($tmp_order_info['appoint_type'] == 1){
			$service_address_info = array();
			foreach($service_address as $key=>$val){
				if(!empty($service_address[$key]['value'])){
					$service_address_info[$key]['name'] = $val['name'];
					$service_address_info[$key]['value'] = $val['value'];
					$service_address_info[$key]['type'] = $val['type'];
					if($appoint_visit_order_info[$key]['type'] == 2){
						$service_address_info[$key]['long'] = $val['long'];
						$service_address_info[$key]['lat'] = $val['lat'];
						$service_address_info[$key]['address'] = $val['address'];
					}
				}
			}
		}

		if($service_address_info){
			$cue_list = $service_address_info;
		}


		$worker_where['merchant_worker_id'] = $appoint_visit_order_info['merchant_worker_id'];
		$worker_field=array('merchant_worker_id','name','mobile');
		$merchant_workers_info = $database_merchant_workers->appoint_worker_info($worker_where,$worker_field);
		$this->assign('merchant_workers_info',$merchant_workers_info);

		//上门预约工作人员信息end

		$this->assign('cue_list', $cue_list);
		$this->assign('now_order', $now_order);
		$this->display();
	}


	public function allot_appoint_detail(){
		$order_id = $_GET['order_id'] + 0;
		$where['order_id'] = $order_id;

		$database_order = D('Appoint_order');
		$database_user = D('User');
		$database_appoint = D('Appoint');
		$database_store = D('Merchant_store');
		$database_appoint_visit_order_info = D('Appoint_visit_order_info');
		$database_merchant_workers = D('Merchant_workers');
		$database_appoint_supply = D('Appoint_supply');

		/*$order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->select();
		$uidArr = array();
		foreach($order_info as $v){
			array_push($uidArr,$v['uid']);
		}

		$uidArr = array_unique($uidArr);
		$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();

		$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
		$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
		$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
		$now_order = $order_list[0];*/

		$now_order = $database_order->where($where)->find();
		$where_user['uid'] = $now_order['uid'];
		//$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where($where_user)->find();
		$where_appoint['appoint_id'] = $now_order['appoint_id'];
		$appoint_info = $database_appoint->field(true)->where($where_appoint)->find();
		$cue_info = unserialize($now_order['cue_field']);
		$cue_list = array();
		foreach($cue_info as $key=>$val){
			if(!empty($cue_info[$key]['value'])){
				$cue_list[$key]['name'] = $val['name'];
				$cue_list[$key]['value'] = $val['value'];
				$cue_list[$key]['type'] = $val['type'];
				if($cue_info[$key]['type'] == 2){
					$cue_list[$key]['long'] = $val['long'];
					$cue_list[$key]['lat'] = $val['lat'];
					$cue_list[$key]['address'] = $val['address'];
				}
			}
		}

		//上门预约工作人员信息start
		$tmp_order_info=reset($order_info);

		$Map['appoint_order_id'] = $tmp_order_info['order_id'];
		$Map['uid'] = $tmp_order_info['uid'];
		$appoint_visit_order_info = $database_appoint_visit_order_info->where($Map)->find();
		$service_address=  unserialize($appoint_visit_order_info['service_address']);
		if($tmp_order_info['appoint_type'] == 1){
			$service_address_info = array();
			foreach($service_address as $key=>$val){
				if(!empty($service_address[$key]['value'])){
					$service_address_info[$key]['name'] = $val['name'];
					$service_address_info[$key]['value'] = $val['value'];
					$service_address_info[$key]['type'] = $val['type'];
					if($appoint_visit_order_info[$key]['type'] == 2){
						$service_address_info[$key]['long'] = $val['long'];
						$service_address_info[$key]['lat'] = $val['lat'];
						$service_address_info[$key]['address'] = $val['address'];
					}
				}
			}
		}
		$cue_list = $service_address_info;

		$worker_where['merchant_worker_id'] = $appoint_visit_order_info['merchant_worker_id'];
		$worker_field=array('merchant_worker_id','name','mobile');
		$merchant_workers_info = $database_merchant_workers->appoint_worker_info($worker_where,$worker_field);
		$this->assign('merchant_workers_info',$merchant_workers_info);

		//上门预约工作人员信息end


		//技师列表start
		$where['merchant_store_id'] = $now_order['store_id'];
		$where['status'] = 1;
		$worker_list = $database_merchant_workers->where($where)->getField('merchant_worker_id,name');
		$this->assign('worker_list', $worker_list);
		//技师列表end

		$this->assign('cue_list', $cue_list);
		$this->assign('now_order', $now_order);

		$appoint_supply_where['order_id'] = $order_id;
		$appoint_supply_where['worker_id'] = 0;
		$appoint_supply_count = $database_appoint_supply->where($appoint_supply_where)->count();
		$this->assign('appoint_supply_count', $appoint_supply_count);
		$this->display();
	}


	public function ajax_worker_edit(){
		$merchant_worker_id = $_POST['merchant_worker_id'] + 0;
		$order_id = $_POST['order_id'] + 0;
		if(!$merchant_worker_id || !$order_id){
			exit(json_encode(array('status'=>0,'msg'=>'传递参数有误！')));
		}

		$database_appoint_visit_order_info = D('Appoint_visit_order_info');
		$database_appoint_order = D('Appoint_order');
		$Map['appoint_order_id'] = $order_id;
		$_data['merchant_worker_id'] = $merchant_worker_id ? $merchant_worker_id : 0;
		$where['order_id'] = $order_id;
		$database_appoint_order->where($where)->data($_data)->save();


		$insert_id = $database_appoint_visit_order_info->where($where)->data($_data)->save();
		if($insert_id){
			if(C('config.store_worker_sms_order')){
				$now_order = $database_appoint_order->where(array('order_id'=>$order_id))->find();
				$worker_where['appoint_order_id'] = $order_id;
				$now_worker = $database_appoint_visit_order_info->appoint_visit_order_detail($worker_where);
				$now_worker = $now_worker['detail'];
				$database_appoint = D('Appoint');
				$appoint_info = $database_appoint->get_appoint_by_appointId($now_order['appoint_id']);

				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'], 'type' => 'appoint');
				$sms_data['uid'] = 0;
				$sms_data['mobile'] = $now_worker['mobile'];
				$sms_data['sendto'] = 'user';
				$sms_data['content'] = '有份新的' . $appoint_info['appoint_name'] . '被预约，订单号：' . $now_order['order_id'] . '请您注意查看并处理!';
				Sms::sendSms($sms_data);
			}

			$database_appoint_supply = D('Appoint_supply');
			$now_supply_order = $database_appoint_supply->where(array('order_id'=>$now_order['order_id']))->find();
			$supply_data['appoint_id'] = $now_order['appoint_id'];
			$supply_data['mer_id'] = $now_order['mer_id'];
			$supply_data['store_id'] = $now_order['store_id'];
			$supply_data['create_time'] = time();
			$supply_data['status'] =  1;
			if($merchant_worker_id > 0){
				$supply_data['worker_id'] = $merchant_worker_id;
				$supply_data['get_type'] = 1;
				$supply_data['status'] =  2;
			}

			$supply_data['start_time'] = $_SERVER['REQUEST_TIME'];
			$supply_data['paid'] = $now_order['paid'];
			$supply_data['pay_type'] = $now_order['pay_type'];
			$supply_data['order_time'] = $now_order['order_time'];
			$supply_data['deliver_cash'] = floatval($now_order['product_price'] - $now_order['product_card_price'] - $now_order['product_merchant_balance'] - $now_order['product_balance_pay'] - $now_order['product_payment_money'] - $now_order['product_score_deducte'] - $now_order['product_coupon_price']);
			$supply_data['uid'] = $now_order['uid'];

			if(!$now_supply_order){
				$supply_data['order_id'] = $now_order['order_id'];
				$database_appoint_supply->data($supply_data)->add();
			}else{
				$supply_where['order_id'] = $now_order['order_id'];
				$database_appoint_supply->where($supply_where)->data($supply_data)->save();
			}

			exit(json_encode(array('status'=>1,'msg'=>'订单分配成功！')));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>'订单分配失败！')));
		}
	}

	/*验证预约服务*/
	public function appoint_verify(){
		$database_order = D('Appoint_order');
		$database_appoint_visit_order_info = D('Appoint_visit_order_info');
		$database_merchant_workers = D('Merchant_workers');
		$database_merchant_store = D('Merchant_store');


		$where['order_id'] = $_GET['order_id'];
		$now_order = $database_order->field(true)->where($where)->find();
		$now_store = $database_merchant_store->get_store_by_storeId($now_order['store_id']);
		if(empty($now_order)){
			$this->error('当前订单不存在！');
		} else {
			$condition_group['appoint_id'] = $now_order['appoint_id'];
			D('Appoint')->where($condition_group)->setInc('appoint_sum',1);
			$fields['store_id'] = $this->staff_session['store_id'];
			$fields['last_staff'] = $this->staff_session['name'];
			$fields['last_time'] = time();
			$fields['service_status'] = 1;
			$fields['paid'] = 1;
			if($database_order->where($where)->data($fields)->save()){
				$Map['appoint_order_id'] =  $_GET['order_id'] + 0;
				$appoint_visit_order_info = $database_appoint_visit_order_info->where($Map)->find();

				$worker_where['merchant_worker_id'] = $appoint_visit_order_info['merchant_worker_id'];
				$pay_money_count = $database_appoint_visit_order_info->order_appoint_price_sum($worker_where);
				$database_merchant_workers->where($worker_where)->where($worker_where)->setField('appoint_price',$pay_money_count);
				$database_merchant_workers->where($worker_where)->setInc('order_num');

				$sms_data = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'appoint');
				if ($this->config['sms_finish_order'] == 1 || $this->config['sms_finish_order'] == 3) {
					if (empty($now_order['phone'])) {
						$user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();
					}
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $user['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您在 ' . $this->store['name'] . '店中下的订单(订单号：' . $now_store['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
					Sms::sendSms($sms_data);
				}

				//验证增加商家余额
				$order_info['order_type']='appoint';
				$order_info['order_id'] = $now_order['order_id'];
				$order_info['mer_id'] = $now_order['mer_id'];
				$order_info['store_id'] = $now_order['store_id'];
				$order_info['balance_pay'] = $now_order['balance_pay'];
				$order_info['score_deducte'] = $now_order['score_deducte'];
				$order_info['payment_money'] = $now_order['pay_money'];
				$order_info['is_own'] = $now_order['is_own'];
				$order_info['merchant_balance'] = $now_order['merchant_balance'];
				$order_info['score_used_count'] = $now_order['score_used_count'];
				$order_info['money'] = $order_info['balance_pay'] + $order_info['score_deducte'] + $order_info['payment_money'] + $order_info['merchant_balance'];

				if($now_order['product_id'] > 0){
					$order_info['total_money'] = $now_order['product_price'];
				}else{
					$order_info['total_money'] = $now_order['appoint_price'];
				}
				$order_info['payment_money'] = $now_order['pay_money'] + $now_order['user_pay_money'];
				$order_info['balance_pay'] = $now_order['balance_pay'] + $now_order['product_balance_pay'];
				$order_info['merchant_balance'] = $now_order['merchant_balance'] + $now_order['product_merchant_balance'];
				$order_info['card_give_money'] = $now_order['card_give_money'] + $now_order['product_card_give_money'];
				$order_info['uid'] = $now_order['uid'];

				$appoint_name = M('Appoint')->field('appoint_name')->where(array('appoint_id'=>$order_info['appoint_id']))->find();
				//D('Merchant_money_list')->add_money($now_order['mer_id'],'用户预约'.$appoint_name['appoint_name'].'记入收入',$order_info);
				$order_info['desc']='用户预约'.$appoint_name['appoint_name'].'记入收入';
				 $order_info['score_discount_type']=$now_order['score_discount_type'];
				D('SystemBill')->bill_method($order_info['is_own'],$order_info);

				$now_user = D('User')->get_user($order_info['uid']);
				D('Merchant_spread')->add_spread_list($order_info,$now_user,'appoint',$now_user['nickname']."用户购买预约获得佣金");
				$where['status'] = array('neq',3);
				$database_appoint_supply = D('Appoint_supply');
				$now_supply = $database_appoint_supply->where($where)->find();
				if($now_supply){
					$supply_data['status'] = 3;
					$supply_data['end_time'] = time();
					$supply_data['check_source'] = 2;
					$supply_data['check_time'] = time();
					$database_appoint_supply->where($where)->data($supply_data)->save();
				}
				if(C('config.open_extra_price')==1){
					$score = D('Percent_rate')->get_extra_money($order_info);
					if($score>0){
						D('User')->add_score($order_info['uid'], floor($score), '用户预约'.$appoint_name['appoint_name'].' 获得'.C('config.extra_price_alias_name'));
					}

				}else if($this->config['add_score_by_percent']==0 && ($this->config['open_score_discount']==0 || $now_order['score_discount_type']!=2)){
					if($order_info['is_own']&& $this->config['user_own_pay_get_score']!=1){
						$order_info['payment_money'] = 0;
					}

					D('User')->add_score($now_order['uid'], round(($order_info['balance_pay']+$order_info['payment_money']) * $this->config['score_get'] ), '购买预约商品获得'.$this->config['score_name']);

				}

				$this->success('验证成功！');
			} else {
				$this->error('验证失败！请重试。');
			}
		}
	}

	/* 格式化订单数据  */
	protected function formatOrderArray($order_info, $user_info, $appoint_info, $store_info){
//		if(!empty($user_info)){
//			$user_array = array();
//			foreach($user_info as $val){
//				$user_array[$val['uid']]['phone'] = $val['phone'];
//				$user_array[$val['uid']]['nickname'] = $val['nickname'];
//			}
//		}
		if(!empty($appoint_info)){
			$appoint_array = array();
			foreach($appoint_info as $val){
				$appoint_array[$val['appoint_id']]['appoint_name'] = $val['appoint_name'];
				$appoint_array[$val['appoint_id']]['appoint_type'] = $val['appoint_type'];
				$appoint_array[$val['appoint_id']]['appoint_price'] = $val['appoint_price'];
			}
		}
		if(!empty($store_info)){
			$store_array = array();
			foreach($store_info as $val){
				$store_array[$val['store_id']]['store_name'] = $val['name'];
				$store_array[$val['store_id']]['store_adress'] = $val['adress'];
			}
		}
		if(!empty($order_info)){
			foreach($order_info as &$val){
//				$val['phone'] = $user_array[$val['uid']]['phone'];
//				$val['nickname'] = $user_array[$val['uid']]['nickname'];
				$val['appoint_name'] = $appoint_array[$val['appoint_id']]['appoint_name'];
				$val['appoint_type'] = $appoint_array[$val['appoint_id']]['appoint_type'];
				$val['appoint_price'] = $appoint_array[$val['appoint_id']]['appoint_price'];
				$val['store_name'] = $store_array[$val['store_id']]['store_name'];
				$val['store_adress'] = $store_array[$val['store_id']]['store_adress'];
			}
		}
		return $order_info;
	}

	public function logout()
    {
        session('staff_session', null);
        session('staff', null);
		if ($this->merchant_session['store_id']) {
            session('merchant', null);
        }
        redirect(U('Store/login'));
    }

	public function bill()
	{
		$mer_id = intval($this->store['mer_id']);
		$this->assign(D("Meal_order")->get_offlineorder_by_mer_id($mer_id, $this->staff_session['name']));
		$this->display();
	}
	private function meal_notice($order)
	{
		//验证增加商家余额
		$order['order_type']='meal';
		$info = unserialize($order['info']);
		$info_str = '';
		foreach($info as $v){
			$info_str.=$v['name'].':'.$v['price'].'*'.$v['num'].'</br>';
		}
		//D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$info_str.'记入收入',$order);
		$order['desc']='用户购买'.$info_str.'记入收入';
		D('SystemBill')->bill_method($order['is_own'],$order);
		//商家推广分佣
		$now_user = M('User')->where(array('uid' => $order['uid']))->find();
		D('Merchant_spread')->add_spread_list($order, $now_user, 'meal', $now_user['nickname'] . '用户购买餐饮商品获得佣金');

		//积分
		if(C('config.open_extra_price')==1){
			$score = D('Percent_rate')->get_extra_money($order);
			if($score>0){
				D('User')->add_score($order['uid'], floor($score),'在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.C('config.extra_price_alias_name'));
			}

		}else {
			if($this->config['add_score_by_percent']==0 && ($this->config['open_score_discount']==0 || $order['score_discount_type']!=2)){
				if($order['is_own'] && $this->config['user_own_pay_get_score']!=1 ){
					$order['payment_money'] = 0;
				}
				D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);

				D('Scroll_msg')->add_msg('meal',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). $this->store['name'] . ' 中消费获得'.$this->config['score_name']);
			}

			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name'].'');
		}
		//短信
		$sms_data = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'food');
		if ($this->config['sms_finish_order'] == 1 || $this->config['sms_finish_order'] == 3) {
			if (empty($order['phone'])) {
				$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
				$order['phone'] = $user['phone'];
			}
			$sms_data['uid'] = $order['uid'];
			$sms_data['mobile'] = $order['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在 ' . $this->store['name'] . '店中下的订单(订单号：' . $order['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_finish_order'] == 2 || $this->config['sms_finish_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $this->store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['order_id'] . '),已经完成了消费！';
			Sms::sendSms($sms_data);
		}

		//打印
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($order['order_id'], 'meal_order', 2);
	}

	private function group_notice($order,$verify_all)
	{
		//积分
		$now_user = M('User')->where(array('uid'=>$order['uid']))->find();
		if($verify_all){
			if(C('config.open_extra_price')==1){
				$order['order_type'] = 'group';
				$score = D('Percent_rate')->get_extra_money($order);
				if($score>0){
					D('User')->add_score($order['uid'], floor($score),'购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得'.C('config.extra_price_alias_name'));
				}

			}else {
				if($this->config['add_score_by_percent']==0 && ($this->config['open_score_discount']==0 || $order['score_discount_type']!=2)){
					if($order['is_own'] && $this->config['user_own_pay_get_score']!=1 ){
						$order['payment_money'] = 0;
					}
					D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得'.$this->config['score_name']);
					D('Scroll_msg')->add_msg('group',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '购买 ' . $order['order_name'] . '成功并消费获得'.$this->config['score_name']);
				}
				D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['total_money'], '购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得积分');
			}
			//商家推广分佣

			D('Merchant_spread')->add_spread_list($order,$now_user,'group',$now_user['nickname'].'购买'.C('config.group_alias_name').'获得佣金');

		}
		D('Group_order')->group_app_notice($order['order_id'],7);
		//短信
		$sms_data = array('mer_id' => $order['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'group');
		if($now_user['openid']){

			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$href = C('config.site_url').'/wap.php?c=My&a=group_order&order_id='.$order['order_id'];
			$model->sendTempMsg('TM00017', array('href' => $href,
					'wecha_id' => $now_user['openid'],
					'first' => $this->config['group_alias_name'].'团购提醒',
					'OrderSn' => $order['real_orderid'],
					'OrderStatus' =>"您购买的团购已于".date('Y-m-d H:i:s',time())."核销成功",
					'remark' =>'点击查看详情'),
					$this->store['mer_id']);
		}

		if ($this->config['sms_group_finish_order'] == 1 || $this->config['sms_group_finish_order'] == 3) {
			$sms_data['uid'] = $order['uid'];
			$sms_data['mobile'] = $order['phone'];
			$sms_data['sendto'] = 'user';
			if(empty($order['res'])){
				$sms_data['content'] = '您购买 '.$order['order_name'].'的订单(订单号：' . $order['real_orderid'] . ')已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
			}else{
				$sms_data['content'] = '您购买 '.$order['order_name'].'的订单(消费码：' . $order['res']['group_pass'] . ')已经完成了消费，如有任何疑意，请您及时联系我们！';
			}
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_group_finish_order'] == 2 || $this->config['sms_group_finish_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $this->store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客购买的' . $order['order_name'] . '的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费！';
			Sms::sendSms($sms_data);
		}

		//打印
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($order['order_id'], 'group_order', 2);
	}

	public function check_confirm()
	{
		$database = D('Meal_order');
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		$condition['store_id'] = $this->store['store_id'];
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->error('订单不存在！');
		}
		if ($order['status'] > 2) $this->error('订单已取消');
		if ($order['paid'] == 0) $this->error('未支付，不能接单。');
		if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
			$notOffline = 1;
			if ($this->config['pay_offline_open'] == 1) {
				$now_merchant = D('Merchant')->get_info($order['mer_id']);
				if ($now_merchant) {
					$notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
				}
			}
			if ($notOffline) {
				$this->error('未支付，不能接单。');
				exit;
			}
		}

		if ($order['meal_type'] == 1) {
			$deliverCondition['store_id'] = $this->store['store_id'];
			$deliverCondition['mer_id'] = $this->store['mer_id'];
			// 商家是否接入配送
// 			if ($deliver = D('Deliver_store')->where($deliverCondition)->find()) {
				$old = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 0))->find();
				if (empty($old)) {
					$deliverType = $deliver['type'];
					$address_id = $order['address_id'];
					$address_info = D('User_adress')->where(array('adress_id' => $address_id))->find();

					$supply['order_id'] = $order_id;
					$supply['paid'] = $order['paid'];
					$supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
					$supply['pay_type'] = $order['pay_type'];
					$supply['money'] = $order['price'];
					$supply['deliver_cash'] = floatval($order['price'] - $order['card_price']-$order['merchant_balance']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-$order['coupon_price']);
					$supply['deliver_cash'] = max(0, $supply['deliver_cash']);
					$supply['store_id'] = $this->store['store_id'];
					$supply['store_name'] = $this->store['name'];
					$supply['mer_id'] = $this->store['mer_id'];
					$supply['from_site'] = $this->store['adress'];
					$supply['from_lnt'] = $this->store['long'];
					$supply['from_lat'] = $this->store['lat'];

					if ($address_info) {
						$supply['aim_site'] =  $address_info['adress'].' '.$address_info['detail'];
						$supply['aim_lnt'] = $address_info['longitude'];
						$supply['aim_lat'] = $address_info['latitude'];
						$supply['name']  = $address_info['name'];
						$supply['phone'] = $address_info['phone'];
					}
					$supply['status'] =  1;
					$supply['type'] = $deliverType;
					$supply['item'] = 0;
					$supply['create_time'] = $_SERVER['REQUEST_TIME'];
					$supply['start_time'] = $_SERVER['REQUEST_TIME'];
					$supply['appoint_time'] = $_SERVER['REQUEST_TIME'];
					if ($addResult = D('Deliver_supply')->add($supply)) {
					} else {
						$this->error('接单失败');
					}
				}
// 			} else {
// 				$this->error('您还没有接入配送机制');
// 			}
		}
		$data['is_confirm'] = 1;
		$data['order_status'] = 3;
		$data['store_uid'] = $this->staff_session['id'];
		$data['last_staff'] = $this->staff_session['name'];
		if($database->where($condition)->save($data)){
			$this->success('已接单');
		} else {
			$this->error('接单失败');
		}

	}
	public function waimai(){
		$store_id = intval($this->store['store_id']);
		$mer_id = intval($this->store['mer_id']);

		$condition['store_id'] = $store_id;
		$condition['mer_id'] = $mer_id;

		if (IS_POST) {
			$order_id = isset($_POST['order_id']) ? htmlspecialchars($_POST['order_id']) : '';
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
			$meal_pass = isset($_POST['meal_pass']) ? htmlspecialchars($_POST['meal_pass']) : '';
			$order_id && $condition['order_id'] = array('like', "%$order_id%");
			$name && $condition['username'] = array('like', "%$name%");
			$phone && $condition['userphone'] = array('like', "%$phone%");
			$meal_pass && $condition['code'] = array('like', "%$meal_pass%");
			$this->assign('meal_pass', $meal_pass);
			$this->assign('order_id', $order_id);
			$this->assign('name', $name);
			$this->assign('phone', $phone);
		}

		$count = D('waimai_order')->where($condition)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count, 20);
		$list = D('waimai_order')->where($condition)->order("order_id DESC")->limit($p->firstRow . ',' . $p->listRows)->select();
		$pay_method = D('Config')->get_pay_method();

		if(count($list)){
			foreach ($list as $listInfo){
				$addressId[$listInfo['address_id']] = $listInfo['address_id'];
				$orderId[$listInfo['order_id']] = $listInfo['order_id'];
			}
			$allGoodsOrder = D('Waimai_goods')->get_all_order_goods($orderId);

			foreach ($list as $k=>$listInfo){
				if($listInfo['address']){
					$list[$k]['address_info'] = unserialize($listInfo['address']);
				}
				if($pay_method[$listInfo['pay_type']]){
					$list[$k]['pay_method'] = 	$pay_method[$listInfo['pay_type']]['name'];
				}
				if($allGoodsOrder[$listInfo['order_id']]){
					$list[$k]['order_info'] = $allGoodsOrder[$listInfo['order_id']];
				}
			}
		}
		$pagebar =  $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('order_list',$list);
		$this->assign('now_store', $this->store);
		$this->display();
	}

	public function waimai_add(){

		$store_id = intval($this->store['store_id']);
		$mer_id = intval($this->store['mer_id']);
		$status = intval($_POST['status']);
		$order_id = intval($_POST['order_id']);

		$storeInfo =  D('Waimai_store')->where(array('store_id'=>$store_id))->find();

		$condition['store_id'] = $store_id;
		$condition['mer_id'] = $mer_id;
		$deliverCondition = $condition;
		$condition['order_id'] = $order_id;

		$this->check_waimai();
		$order = D('Waimai_order')->where($condition)->find();
		if(!$order){
			$this->error('非法操作');
		}
		$order_status = 3;
		if($status == 2){
			$order_status = 7;
			// 取消订单 申请

		}
		$result = D('Waimai_order')->field('order_id,code,address_id')->where($condition)->save(array('order_status'=>$order_status));
		if(!$result){
			$this->error('操作失败');
		}
		// 如果商家已接单 并且已经配送
		if($status == 2 && $result){
			$this->success('取消订单成功');
		}
		// 商家是否接入配送
		$deliver = D('Deliver_store')->where($deliverCondition)->find();
// 		echo D('Deliver_store')->_sql();die;
		if(!$deliver){
			$this->success('已接单');
		}
		$deliverType = $deliver['type'];
		// 订单的配送地址
		$address_id = $order['address_id'];
		$address_info = D('Waimai_user_address')->where(array('address_id'=>$address_id))->find();
		$supply['order_id'] = $order_id;
		$supply['store_id'] = $store_id;
		$supply['mer_id'] = $mer_id;
		$supply['from_site'] = $this->store['adress'];
		$supply['from_lnt'] = $this->store['long'];
		$supply['from_lat'] = $this->store['lat'];
		$supply['aim_site'] =  $address_info['address'].' '.$address_info['detail'];
		$supply['aim_lnt'] = $address_info['longitude'];
		$supply['aim_lat'] = $address_info['latitude'];
		$supply['status'] =  1;
		$supply['type'] = $deliverType;
		$supply['item'] = 1;
		$supply['code'] = $order['code'];//'收货码',
		$supply['name']  = $address_info['name'];
		$supply['phone'] = $address_info['phone'];
		$supply['create_time'] = $_SERVER['REQUEST_TIME'];
		$supply['start_time'] = $_SERVER['REQUEST_TIME'];
		$supply['appoint_time'] = $_SERVER['REQUEST_TIME'];
		if($storeInfo && $storeInfo['close'] == '1'){
			$supply['appoint_time'] = strtotime(date('Y-m-d').''.$storeInfo['start_time_2']);
		}

		$addResult = D('Deliver_supply')->data($supply)->add();

		if(!$addResult){
			$this->error('接单失败');
		}

		//添加订单日志
		$log = array();
		$log['status'] = $order_status;
		$log['order_id'] = $order_id;
		$log['store_id'] = $store_id;
		$log['uid'] = $this->staff_session['id'];
		$log['time'] = time();
		$log['group'] = 2;
		$result = D("Waimai_order_log")->add($log);
		if (!$result) {
			//$this->error("添加订单日志失败");exit;
		}

		$this->success('已接单');
	}

	public function waimai_num(){
		$count = 0;
		$store_id = intval($this->store['store_id']);
		$mer_id = intval($this->store['mer_id']);
		$condition['store_id'] = $store_id;
		$condition['mer_id'] = $mer_id;
		$condition['order_status'] = 2;
		$count = D('waimai_order')->where($condition)->count();

		$this->success($count);
	}

	/*
	 * 外卖取消订单退款
	 */
	public function waimai_cancel() {
		$store_id = intval($this->store['store_id']);
		$mer_id = intval($this->store['mer_id']);
		$order_id = I('order_id', 0, 'intval');
		if (!$order_id) {
			$this->error("订单信息错误");exit;
		}
		//查找订单
		$where = array();
		$where['store_id'] = $store_id;
		$where['mer_id'] = $mer_id;
		$where['order_id'] = $order_id;
		$where['order_status'] = array('in',"2,3,4,5");
		$orderModel = D("Waimai_order");
		$order = $orderModel->field(true)->where($where)->find();
		if (!$order) {
			$this->error("订单不存在或已取消");exit;
		}

		D()->startTrans();
		//更新订单状态为取消状态
		$result = $orderModel->where($where)->data(array('order_status'=>7))->save();
		if (!$result) {
			D()->rollback();
			$this->error("订单状态修改失败");
		}

		//商家商品数回归
		$sell_log = D("Waimai_sell_log")->field(true)->where(array('order_id'=>$order_id, 'store_id'=>$store_id))->select();
		if (!$sell_log) {
			$this->error("销售记录为空");
		}
		foreach ($sell_log as $val) {
			$result = D("Waimai_goods")->where(array('goods_id'=>$val['goods_id']))->setDec("sell_count", $val['num']);
			if (!$result) {
				D()->rollback();
				$this->error("商品数量修改失败");exit;
			}
		}
		//订单到付
		if($now_order['pay_type'] == 'offline') {
			$update = array();
			$update['order_status'] = 7;
			$update['refund_detail'] = serialize(array('refund_time'=>time()));
			$result = $orderModel->where($where)->data($update)->save();
			if (!$result) {
				D()->rollback();
				$this->error("订单状态修改失败");
			}
			D()->commit();
			$this->success("取消成功");
		}

		$order_refund_params = array();
		//平台余额退款
		if ($order['balance_pay'] != '0.00') {
			$add_result = D('User')->add_money($order['uid'],$order['balance_pay'],'退款 '.$order['order_name'].' 增加余额');
			if (!$add_result) {
				D()->rollback();
				$this->error("平台余额退款失败");
			}

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $order['order_id'];
			}
			$param['balance_pay'] = $order['balance_pay'];
			$order_refund_params['balance_pay_refund'] = serialize($param);
		}

		//线上支付退款
		if ($order['online_pay'] != '0.00') {
			$pay_method = D('Config')->get_pay_method();
			if(empty($pay_method)){
				$this->error('系统管理员没开启任一一种支付方式！');
			}
			if(empty($pay_method[$order['pay_type']])){
				$this->error('您选择的支付方式不存在，请更新支付方式！');
			}

			$pay_class_name = ucfirst($order['pay_type']);
			$import_result = import('@.ORG.pay.'.$pay_class_name);
			if(empty($import_result)){
				$this->error('系统管理员暂未开启该支付方式，请更换其他的支付方式');
			}
			$order['order_type'] = 'waimai';
			$order['submit_order_time'] = $order['create_time'];
			$pay_class = new $pay_class_name($order, $order['online_pay'], $order['pay_type'], $pay_method[$order['pay_type']]['config'], $this->staff_session, 1);
			$go_refund_param = $pay_class->refund();
			$order_refund_params['online_pay_refund'] = serialize($go_refund_param['refund_param']);
			if ($go_refund_param['type'] != 'ok') {
				//退款失败
				D()->rollback();
				$this->error($go_refund_param['msg']);
			}
		}
		//先保证退款完整，再更新退款信息
		D()->commit();

		$update = array();
		$update['refund_detail'] = $order_refund_params;
		$result = D('Waimai_order')->where(array('order_id'=>$order_id))->data($update)->save();
		if(! $result){
			//退款成功，修改退款信息失败，记录日志
			error_log(date("Y-m-d H:i:s")."=>TYPE:Waimai OrderID:".$order_id." Refund:".$order['online_pay'].PHP_EOL, 3, RUNTIME_PATH."Logs/waimai_payement".date("Y-m-d").".log");
		}

		//添加订单日志
		$log = array();
		$log['status'] = 7;
		$log['order_id'] = $order_id;
		$log['store_id'] = $store_id;
		$log['uid'] = $this->staff_session['id'];
		$log['time'] = time();
		$log['group'] = 2;
		$result = D("Waimai_order_log")->add($log);
		if (!$result) {
			$this->error("添加订单日志失败");exit;
		}

		$this->success("订单取消成功");
	}

	/*检查是否开启订餐*/
	protected function check_waimai(){
		if(empty($this->store['have_waimai'])){
			$this->error('您访问的店铺没有开通'.$this->config['waimai_alias_name'].'功能！');
		}
	}

	/***收银台返回处理****/
	public function cashierBack()
	{
		$lgcode=isset($_GET['lgcode']) ? trim($_GET['lgcode']) :'';
		if($lgcode){
			$staff_session = session('staff');
			$database_store_staff = D('Merchant_store_staff');
			$condition_store_staff['username'] = $staff_session['account'];
			$now_staff = $database_store_staff->field(true)->where($condition_store_staff)->find();
			if(!empty($now_staff)){
				$tmplgcode = md5($now_staff['username']);
				if($lgcode == $tmplgcode){
					session('staff',$now_staff);
					Header('Location:/store.php?g=Merchant&c=Store&a=meal_list');
					exit();
				}
			}
		}
		session('merchant',null);
		$this->error('非法访问登录！');
	}

	public function cashier()
	{
		$siteurl = $this->config['site_url'];
		$siteurl = rtrim($siteurl,'/');

		if(empty($siteurl)){
			$siteurl=isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
			$siteurl = strtolower($siteurl);
			if(strpos($siteurl,"http:")===false && strpos($siteurl,"https:")===false) $siteurl='http://'.$siteurl;
			$siteurl = rtrim($siteurl,'/');
		}

		$postdata = array('account' => $this->staff_session['username'], 'mer_id' => $this->staff_session['token'], 'store_id' => $this->staff_session['store_id'], 'domain' => ltrim($siteurl, 'http://'));
		$postdata['sign'] = $this->getSign($postdata);
		$postdataStr = json_encode($postdata);
		$postdataStr = $this->Encryptioncode($postdataStr,'ENCODE');
		$postdataStr = base64_encode($postdataStr);

		header('Location: '. $siteurl .'/merchants.php?m=Index&c=auth&a=elogin&code=' . $postdataStr);
	}

	private function getSign($data) {
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$validate[$key] = $this->getSign($value);
			} else {
				$validate[$key] = $value;
			}
		}
		$validate['salt'] = 'pigcmso2oCashier';	//salt
		sort($validate, SORT_STRING);
		return sha1(implode($validate));
	}

	/**
	 * 加密和解密函数
	 *
	 * @access public
	 * @param  string  $string    需要加密或解密的字符串
	 * @param  string  $operation 默认是DECODE即解密 ENCODE是加密
	 * @param  string  $key       加密或解密的密钥 参数为空的情况下取全局配置encryption_key
	 * @param  integer $expiry    加密的有效期(秒)0是永久有效 注意这个参数不需要传时间戳
	 * @return string
	 */
	private function Encryptioncode($string, $operation = 'DECODE', $key = '', $expiry = 0)
	{
		$ckey_length = 4;
		$key = md5($key != '' ? $key : 'lhs_simple_encryption_code_87063');
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya . md5($keya . $keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for ($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for ($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for ($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if ($operation == 'DECODE') {
			if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc . str_replace('=', '', base64_encode($result));
		}
	}

	public function table()
	{
		$this->assign('now_store', $this->store);

		$database = D('Merchant_store_table');
		$where['store_id'] = $this->store['store_id'];
		$count = $database->where($where)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count, 20);
		$list = $database->field(true)->where($where)->order('`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('list', $list);
		$this->assign('pagebar',$p->show());
		$this->display();
	}

	public function table_order()
	{
		$tableid = intval($_GET['id']);
		$database = D('Merchant_store_table');
		$condition['pigcms_id'] = $tableid;
		$now_table = $database->field(true)->where($condition)->find();
		if(empty($now_table)){
			$this->error('桌台不存在！');
		}
		$this->assign('table', $now_table);
		$this->assign('now_store', $this->store);
		$order_list = D('Meal_order')->field(true)->where(array('tableid' => $tableid, 'mer_id' => $this->store['mer_id'], 'status' => 0, 'arrive_time' => array('gt', time() - 10800)))->order('arrive_time ASC')->select();
		$this->assign('order_list', $order_list);
		$this->display();
	}

	/* 分类状态 */
	public function table_status()
	{
		$database = D('Merchant_store_table');
		$condition['pigcms_id'] = intval($_POST['id']);
		$now_table = $database->field(true)->where($condition)->find();
		if(empty($now_table)){
			$this->error('桌台不存在！');
		}
		$data['status'] = $_POST['type'] == 'open' ? '1' : '0';
		if($database->where($condition)->data($data)->save()){
			exit('1');
		}else{
			exit;
		}
	}

	public function table_check()
	{
		$database = D('Meal_order');
		$order_id = $condition['order_id'] = intval($_POST['id']);
		$condition['store_id'] = $this->store['store_id'];
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->error('订单不存在！');
		}
		$data['is_confirm'] = $_POST['type'] == 'open' ? '1' : '0';
		if($database->where($condition)->data($data)->save()){
			exit('1');
		}else{
			exit;
		}
	}

	public function ajax_staff_del(){
		$order_id = $this->_post('order_id');
		if(!$order_id){
			exit(json_encode(array('msg'=>'传递参数有误！','status'=>0)));
		}

		$database_appoint_order = D('Appoint_order');
		$where['order_id'] = $order_id;
		$data['del_time'] = time();
		$data['is_del'] = 4;
		$data['del_staff_id'] = $_SESSION['staff']['id'];
		$result = $database_appoint_order->where($where)->data($data)->save();
		if($result){
			exit(json_encode(array('msg'=>'取消成功！','status'=>1)));
		}else{
			exit(json_encode(array('msg'=>'取消失败！','status'=>0)));
		}
	}

	public function shop_list()
	{
		$store_id = intval($this->store['store_id']);
		$where = array();
		if (IS_POST) {
			$real_orderid = isset($_POST['real_orderid']) ? htmlspecialchars($_POST['real_orderid']) : '';
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
			$meal_pass = isset($_POST['meal_pass']) ? htmlspecialchars($_POST['meal_pass']) : '';
			$orderid = isset($_POST['orderid']) ? htmlspecialchars($_POST['orderid']) : '';
			$real_orderid && $where['real_orderid'] = array('like', "%$real_orderid%");
			$name && $where['username'] = array('like', "%$name%");
			$phone && $where['userphone'] = array('like', "%$phone%");
			$orderid && $where['orderid'] = $orderid;

			$this->assign('meal_pass', $meal_pass);
			$this->assign('real_orderid', $real_orderid);
			$this->assign('name', $name);
			$this->assign('phone', $phone);
			$this->assign('orderid', $orderid);
		}
		$where['mer_id'] = $this->store['mer_id'];
		$where['store_id'] = $store_id;

		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= $type . ' ' . $sort . ',';
			$order_sort .= 'pay_time DESC';
		} else {
			if ($status != -1) {
				$order_sort .= 'pay_time DESC';
			} else {
				$order_sort .= 'order_id DESC';
			}

		}
		if ($status != -1) {
			$where['status'] = $status;
			if ($status === 0) {
			    $where['paid'] = 1;
			    $where['status'] = array('in', array(0, 9));
			}
			if ($status == 2) {
			    $where['status'] = array('in', array(2, 3));
			}
			if ($status == 6) {
			    $where['paid'] = 0;
			}
		}

		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['real_orderid'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['order_id'] = $tmp_result['order_id'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['username'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['userphone'] = htmlspecialchars($_GET['keyword']);
			}elseif ($_GET['searchtype'] == 'third_id') {
				$where['third_id'] =$_GET['keyword'];
			}
		}

		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';

		if ($status == 11) {
		    $where['status'] = 2;
		    $where['is_apply_refund'] = 1;
		} elseif($status == 100){
			$where['paid'] = 0;
		}else if ($status != -1) {
		    $where['status'] = $status;
		    if ($status === 0) {
		        $where['paid'] = 1;
		        $where['status'] = array('in', array(0, 9));
		    }
		}

		if($pay_type&&$pay_type!='balance'){
			$where['pay_type'] = $pay_type;
		}else if($pay_type=='balance'){
			$where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 ) AND `pay_type`=''";
		}
        $period = array();
        if (! empty($_GET['begin_time']) && ! empty($_GET['end_time'])) {
            if ($_GET['begin_time'] > $_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(
                strtotime($_GET['begin_time'] . " 00:00:00"),
                strtotime($_GET['end_time'] . " 23:59:59")
            );
            $where['_string'] .= ($where['_string'] ? ' AND ' : '') . " (create_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
        }
		$now_merchant = D('Merchant')->get_info($this->store['mer_id']);
		if(C('config.open_mer_owe_money')==0){
			$now_merchant['mch_owe_money'] = 0;
		}
		if($now_merchant['money']<$now_merchant['mch_owe_money']*(-1)){
			$where['_string'] .= ($where['_string'] ? ' AND ' : '') .' from_plat = 0 ';
		}

		$this->assign(D("Shop_order")->get_order_list($where, $order_sort, 2, false));
		$shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		$shop = array_merge($this->store, $shop);
		$this->assign('now_store', $shop);
		D('Shop_order')->status_list[11] = '申请退货';
		$this->assign('status_list', D('Shop_order')->status_list);
		//$this->assign($order_lsit);
		$this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status,'pay_type'=>$pay_type));

		$field = 'sum(price) AS total_price, sum(price+extra_price - card_price - merchant_balance - card_give_money - balance_pay - payment_money - score_deducte - coupon_price) AS offline_price, sum(card_price + merchant_balance + card_give_money + balance_pay + payment_money + score_deducte + coupon_price) AS online_price';
		$count_where = "store_id='{$this->store['store_id']}' AND paid=1 AND status<>4 AND status<>5 AND (pay_type<>'offline' OR (pay_type='offline' AND third_id<>''))";
		if ($period) {
		    $count_where .= " AND (create_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
		}
		$result_total = D('Shop_order')->field($field)->where($count_where)->select();
		$result_total = isset($result_total[0]) ? $result_total[0] : '';
		$this->assign($result_total);
		$this->assign('is_change', $this->staff_session['is_change']);
		$pay_method = D('Config')->get_pay_method('','',1);
		$this->assign('pay_method',$pay_method);
		$this->display();
	}
	public function ajax_shop_neworder(){
	    $result = M('Shop_order')->where(array('store_id' => $this->store['store_id'],'status'=>array('in', array(0, 9)),'pay_time'=>array('egt',$_POST['time'])))->find();
	    if($result){
	        $this->success(time());
	    }else{
	        $this->error(time());
	    }
	}

    public function ajax_shop_refundorder()
    {
        $sql = "SELECT count(1) as cnt FROM " . C('DB_PREFIX') . "shop_order as o INNER JOIN " . C('DB_PREFIX') . "shop_order_refund as r ON r.order_id=o.order_id AND o.store_id=" . $this->store['store_id'] . ' WHERE r.status=0 AND r.applytime>' . $_POST['time'];
        $count = M()->query($sql);
        $count = isset($count[0]['cnt']) ? intval($count[0]['cnt']) : 0;
        
        if ($count) {
            $this->success(time());
        } else {
            $this->error(time());
        }
    }

	public function check_shop()
	{
		$database = D('Shop_order');
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		$condition['store_id'] = $this->store['store_id'];
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->error('订单不存在！');
			exit;
		}
		if ($order['status'] > 0) {
			$this->error('该单已接，不要重复接单');
			exit;
		}
		if ($order['status'] == 4 || $order['status'] == 5) {
			$this->error('订单已取消，不能接单！');
			exit;
		}
		if ($order['is_refund']) {
			$this->error('用户正在退款中~！');
			exit;
		}
		if ($order['paid'] == 0) {
			$this->error('订单未支付，不能接单！');
			exit;
		}

		if ($order['platform'] == 1) {//eleme
		    $eleme = D('Eleme_shop')->getElemeObj($order['store_id']);
		    if ($eleme != false) {
		        $result = $eleme->getDataByApi("eleme.order.confirmOrderLite", array('orderId' => $order['platform_id']));
		        if ($result['error']) {
		            $this->error('饿了么返回的错误' . $result['error']['code'] . $result['error']['message']);
		            exit;
		        } else {
		            $data['status'] = 1;
		            $data['order_status'] = 1;
		            $condition['status'] = 0;
		            $data['last_staff'] = $this->staff_session['name'];
		            $data['last_time'] = time();
		            $database->where($condition)->save($data);
		            if ($order['is_pick_in_store'] == 5) {
		                $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
		                if ($result['error_code']) {
		                    D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $order['store_id']))->save(array('status' => 0, 'last_time' => time()));
		                    $this->error($result['msg']);
		                    exit;
		                }
		                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
		            }
		            $this->success('已接单');
		            exit;
		        }
		    } else {
		        $this->error('店铺对接饿了么有问题，请联系商家处理！');
		        exit;
		    }
		} elseif ($order['platform'] == 2) {//meituan
		    $store = M('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
		    if (empty($store) || empty($store['meituan_token'])) {
		        $this->error('店铺对接美团有问题，请联系商家处理！');
		        exit;
		    }
		    import('@.ORG.Meituan');
		    $meituan = new Meituan($store['meituan_token']);
		    $result = $meituan->getDataByApi('waimai/order/confirm', array('orderId' => $order['platform_id']));
		    if ($result['error']) {
		        $this->error('美团返回的错误：' . $result['msg']);
		        exit;
		    } else {
		        $data['status'] = 1;
		        $data['order_status'] = 1;
		        $condition['status'] = 0;
		        $data['last_staff'] = $this->staff_session['name'];
		        $data['last_time'] = time();
		        $database->where($condition)->save($data);
		        if ($order['is_pick_in_store'] == 5) {
		            $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
		            if ($result['error_code']) {
		                D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $order['store_id']))->save(array('status' => 0, 'last_time' => time()));
		                $this->error($result['msg']);
		                exit;
		            }
		            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
		        }
		        $this->success('已接单');
		        exit;
		    }
		} else {
    		$data['status'] = 1;
    		$data['order_status'] = 1;
    		$condition['status'] = 0;
    		$data['last_staff'] = $this->staff_session['name'];
    		$data['last_time'] = time();
    		if($database->where($condition)->save($data)){
    			if ($order['is_pick_in_store'] != 2 && $order['is_pick_in_store'] != 3) {
    			    $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
    			    if ($result['error_code']) {
    			        D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
    			        $this->error($result['msg']);
    			        exit;
    			    }
    			}
    			$phones = explode(' ', $this->store['phone']);
    			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
    			$this->success('已接单');
    		} else {
    			$this->error('接单失败');
    		}
		}
	}

	public function shop_order_detail()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
		$store = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
		$sure = false;
		if($order['is_pick_in_store'] == 3){
			$express_list = D('Express')->get_express_list();
			$this->assign('express_list',$express_list);
		} elseif ($order['is_pick_in_store'] == 0) {
			$supply = D('Deliver_supply')->field('uid')->where(array('order_id' => $order['order_id'], 'item' => 2))->find();
			if (isset($supply['uid']) && $supply['uid']) {
				$sure = true;
			}
		}
		$tempList = array();
		foreach($order['info'] as $v) {
		    $index = isset($v['packname']) && $v['packname'] ? $v['packname'] : 0;
		    if (isset($tempList[$index])) {
		        $tempList[$index]['list'][] = $v;
		    } else {
		        $tempList[$index] = array('name' => $v['packname'], 'list' => array($v));
		    }
		}
		if (count($tempList) == 1) {
		    $tempList[$index]['name'] = '';
		}
		$order['info'] = $tempList;
		
		
        $refundList = D('Shop_order_refund')->field(true)->where(array('order_id' => $order_id, 'status' => 0))->order('id DESC')->select();

        $sql = "SELECT g.image, d.goods_id, d.name, d.price, d.num, d.unit, d.spec, d.id, d.refund_id FROM " . C('DB_PREFIX') . "shop_goods AS g INNER JOIN " . C('DB_PREFIX') . "shop_refund_detail AS d ON g.goods_id=d.goods_id WHERE d.order_id={$order_id}";
        $list = D()->query($sql);
        $data = array();
        $goods_image_class = new goods_image();
        foreach ($list as $row) {
            $image = '';
            if(!empty($row['image'])){
                $tmp_pic_arr = explode(';', $row['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    if (empty($image)) {
                        $image = $goods_image_class->get_image_by_path($value, 's');
                        break;
                    }
                }
            }
            $row['image'] = $image;
            $data[$row['refund_id']][] = $row;
        }
        //状态(0：用户申请，1：商家同意退货，2：商家拒绝退货，3：用户重新申请，4：取消退货申请)
        $status = array('商家审核中', '已完成退货', '商家拒绝退货', '商家审核中', '取消退货申请');
        foreach ($refundList as &$refund) {
            $refund['image'] = explode(',', $refund['image']);
            $refund['goodsList'] = isset($data[$refund['id']]) ? $data[$refund['id']] : array();
            $refund['showStatus'] = $status[$refund['status']];
            //一个订单的退款有些，直接使用后循环查询
            $refund['logList'] = D('Shop_refund_log')->field(true)->where(array('refund_id' => $refund['id']))->order('id ASC')->select();
        }
        
        $rlist = D('Shop_order_refund')->field(true)->where(array('order_id' => $order_id, 'status' => 1))->order('id DESC')->select();
        $rids = array();
        foreach ($rlist as $rl) {
            $rids[] = $rl['id'];
        }
        $finishRefunds = array();
        if ($rids) {
            $finishRefunds = D('Shop_refund_detail')->field(true)->where(array('refund_id' => array('in', $rids)))->select();
        }
        
        $this->assign('refund_list', $refundList);
        $this->assign('finishRefunds', $finishRefunds);
		$this->assign('sure', $sure);
		$this->assign('store', $store);
		$this->assign('order', $order);
		$this->display();

	}


	public function shop_edit()
	{
		if (IS_POST) {
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
			$store_id = $this->store['store_id'];
			$status = intval($_POST['status']);
			if ($order = D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
				if ($status == 1 && $order['status'] > 0) {
					$this->error('该单已接，不要重复接单');
					exit;
				}
				if ($order['status'] == 4 || $order['status'] == 5) {
					$this->error('订单已取消，不能再做其他操作。');
					exit;
				}
				if ($order['is_refund']) {
					$this->error('用户正在退款中~！');
					exit;
				}
				if ($status == 4 || $status == 5) {
				    if ($order['paid']) {
				        $status = 4;
				    } else {
				        $status = 5;
				    }
				}
				$data = array('store_uid' => $this->staff_session['id']);
				$data['status'] = $status;
				$data['last_staff'] = $this->staff_session['name'];
				$condition = array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id);
				$database = D('Shop_order');
				if ($order['platform'] == 1) {//eleme
				    $eleme= D('Eleme_shop')->getElemeObj($order['store_id']);
				    if ($eleme != false) {
				        if ($status == 1) {
				            $result = $eleme->getDataByApi("eleme.order.confirmOrderLite", array('orderId' => $order['platform_id']));
				        } elseif ($status == 2 || $status == 3) {
				            $result = $eleme->getDataByApi("eleme.order.receivedOrderLite", array('orderId' => $order['platform_id']));
				        } elseif ($status == 4) {
				            $result = $eleme->getDataByApi("eleme.order.agreeRefundLite", array('orderId' => $order['platform_id']));
				        } elseif ($status == 5) {
				            $result = $eleme->getDataByApi("eleme.order.cancelOrderLite", array('orderId' => $order['platform_id'], 'type' => 'fakeOrder', 'remark' => '无法取得联系'));
				        }
						if ($result['error']){
							fdump_sql($result,'elem_storestaff_result_pc');
						}
						if ($result['error'] && $result['error']['message'] != '操作失败，订单已确认') {
				            $this->error('饿了么返回的错误' . $result['error']['code'] . $result['error']['message']);
				            exit;
				        } else {
				            $data['last_time'] = time();
				            $database->where($condition)->save($data);
				            if ($status == 1 && $order['is_pick_in_store'] == 5) {
				                $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
				                if ($result['error_code']) {
				                    D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $order['store_id']))->save(array('status' => 0, 'last_time' => time()));
				                    $this->error($result['msg']);
				                    exit;
				                }
				                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
				            } elseif ($status == 0 || $status == 5 || $status == 4) {
			                    D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 0));
				            }
				            
				            $this->success('操作成功');
				            exit;
				        }
				    } else {
				        $this->error('店铺对接饿了么有问题，请联系商家处理！');
				        exit;
				    }
				} elseif ($order['platform'] == 2) {//meituan
				    $store = M('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
				    if (empty($store) || empty($store['meituan_token'])) {
				        $this->error('店铺对接美团有问题，请联系商家处理！');
				        exit;
				    }
				    import('@.ORG.Meituan');
				    $meituan = new Meituan($store['meituan_token']);
				    if ($status == 1) {
				        $result = $meituan->getDataByApi('waimai/order/confirm', array('orderId' => $order['platform_id']));
				    } elseif ($status == 2 || $status == 3) {
				        $this->error('美团不支持确认消费');
				        exit;
				    } elseif ($status == 4) {
				        $result = $meituan->getDataByApi('waimai/order/agreeRefund', array('orderId' => $order['platform_id'], 'reason' => '同意退款'));
				    } elseif ($status == 5) {
				        $result = $meituan->getDataByApi('waimai/order/cancel', array('orderId' => $order['platform_id'], 'reasonCode' => 2001, 'reason' => '地址无法配送'));
				    }

				    if ($result['error']) {
				        $this->error('美团返回的错误：' . $result['msg']);
				        exit;
				    } else {
				        $data['last_time'] = time();
				        $database->where($condition)->save($data);
				        if ($status == 1 && $order['is_pick_in_store'] == 5) {
				            $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
				            if ($result['error_code']) {
				                D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $order['store_id']))->save(array('status' => 0, 'last_time' => time()));
				                $this->error($result['msg']);
				                exit;
				            }
				            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
				        } elseif ($status == 0 || $status == 5 || $status == 4) {
				            D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 0));
				        }
				        $this->success('操作成功');
				        exit;
				    }
				}
				
				if ($order['is_pick_in_store'] == 3) {
					$data['status'] = $status = 1;
					$express_id = isset($_POST['express_id']) ? intval($_POST['express_id']) : 0;
					$express_number = isset($_POST['express_number']) ? htmlspecialchars($_POST['express_number']) : 0;
					if ($status == 2 && (empty($express_id) || empty($express_number))) $this->error('快递公司和快递单号都不能为空。');
					if ($order['paid'] == 0 && $status != 5 && $status != 4) {
						$this->error('未付款的订单只能进行取消操作。');
						exit;
					}
					if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
						$order['paid'] = 0;
					}
					if ($order['paid'] == 0 && $status != 5 && $status != 4) {
						$notOffline = 1;
						if ($this->config['pay_offline_open'] == 1) {
							$now_merchant = D('Merchant')->get_info($order['mer_id']);
							if ($now_merchant) {
								$notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
							}
						}
						if ($notOffline) {
							$this->error('不支持线下支付。');
							exit;
						}
						$data['paid'] = 1;
						$data['third_id'] = $order['order_id'];
					}
					$data['last_staff'] = $this->staff_session['name'];
					$data['express_id'] = $express_id;
					$data['express_number'] = $express_number;
					$data['use_time'] = $_SERVER['REQUEST_TIME'];
					$data['last_time'] = $_SERVER['REQUEST_TIME'];
					if (D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
						$phones = explode(' ', $this->store['phone']);
						if ($status == 1) {
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
						} elseif ($status == 2 && $order['status'] != 2 && $order['status'] != 3) {
							$this->shop_notice($order);
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
						} elseif ($status == 4) {
						    D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9, 'name' => $this->staff_session['name'], 'phone' => $phones[0], 'note' => '店员把此单修改成已退款'));
						    $database->check_refund($order);
						} elseif ($status == 5) {
						    D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 10, 'name' => $this->staff_session['name'], 'phone' => $phones[0], 'note' => '店员把此单修改成已取消'));
						}
						$this->success("更新成功");
					} else {
						$this->error("更新失败，稍后重试");
					}
				} else {
				    if ($order['paid'] == 0 && $status != 5 && $status != 4) {
						$this->error('未付款的订单只能进行取消操作。');
						exit;
					}
					//0未确认，1已确认，2已消费，3已评价，4已退款，5已取消，
					if ($status == 3) {
						$this->error('您不能将该订单改成已评价状态。');
						exit;
					}
// 					if ($status == 5 && $order['paid'] == 1) {
// 						$this->error('当前订单已支付，您不能将改成取消状态。');
// 						exit;
// 					}
					$supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();

					if ($order['is_pick_in_store'] == 0 && $status == 2 && $supply && $supply['uid']) {//平台配送，当配送员接单后店员就不能把订单修改成已消费状态
						$this->error('您不能将该订单改成已消费状态。');
					}
					if ($status == 0 && $order['status'] == 1 && $order['is_pick_in_store'] < 2) {
						if ($supply && $supply['status'] > 1) {
							$this->error('当前订单已进入了配送状态，不能修改成未确认状态。');
							exit;
						}
					}
					
					if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
						$order['paid'] = 0;
					}
					if ($order['paid'] == 0 && $status != 5 && $status != 4) {
						$notOffline = 1;
						if ($this->config['pay_offline_open'] == 1) {
							$now_merchant = D('Merchant')->get_info($order['mer_id']);
							if ($now_merchant) {
								$notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
							}
						}
						if ($notOffline) {
							$this->error('不支持线下支付。');
							exit;
						}
					}

					if ($status == 2 && $order['paid'] == 0) {//将未支付的订单，由店员改成已消费，其订单状态则修改成线下已支付！
						$data['third_id'] = $order['order_id'];
						$order['pay_type'] = $data['pay_type'] = 'offline';
						$data['paid'] = 1;
						$order['pay_time'] = $_SERVER['REQUEST_TIME'];
					}
					
					if ($status == 2) {
						$data['order_status'] = 6;//配送完成
						$supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
						if ($supply) {
							if ($supply['status'] < 2) {
								D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->delete();
							} else {
								D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 5));
							}
						}
					} elseif ($status == 1) {
						$data['order_status'] = 1;
					}
					$data['use_time'] = $_SERVER['REQUEST_TIME'];
					$data['last_time'] = $_SERVER['REQUEST_TIME'];
					if (D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {

					    if (($status == 0 || $status == 4 || $status == 5) && $order['status'] == 1 && $order['is_pick_in_store'] < 2) {//is_pick_in_store : 配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
							D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 0));
						}
						$phones = explode(' ', $this->store['phone']);
						if ($status == 5 || $status == 4) {
						    if ($this->store['dada_shop_id']) {
						        $dada = new Dada();
						        $dadaData = array();
						        $dadaData['order_id'] = $order['real_orderid'];//
						        $dadaData['cancel_reason_id'] = 4;
						        $dadaData['cancel_reason'] = '顾客取消订单';
						        $result = $dada->formalCancel($dadaData);
						    }
						    D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 10, 'name' => $this->staff_session['name'], 'phone' => $phones[0], 'note' => '店员把此单修改成已取消'));
						    $database->check_refund($order);
							//销量回滚reduce_stock_type
							if (($order['paid'] == 1 || $order['reduce_stock_type'] == 1) && $order['is_rollback'] == 0) {
								$goods_obj = D('Shop_goods');
								$details = D('Shop_order_detail')->field(true)->where(array('order_id' => $order_id))->select();
								foreach ($details as $menu) {
									$goods_obj->update_stock($menu, 1);//修改库存
								}
								D('Shop_order')->where(array('order_id' => $order_id))->save(array('is_rollback' => 1));
							}

						}

						if ($status == 2 && $order['status'] != 2 && $order['status'] != 3) {//当订单由未消费修改成已消费时做的通知
						    D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 4));
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
							$this->shop_notice($order);
						}

						if ($status == 1 && $order['status'] == 0 && $order['is_pick_in_store'] != 2) {//接单
                		    $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
                		    if ($result['error_code']) {
                		        D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save(array('status' => 0, 'last_time' => time()));
                		        $this->error($result['msg']);
                		        exit;
                		    }
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));

						}
						$this->success("更新成功");
					} else {
						$this->error("更新失败，稍后重试");
					}
				}
			} else {
				$this->error('不合法的请求');
			}
		}
	}

	private function shop_notice($order, $is_staff = false)
	{
	    //验证增加商家余额
	    $order['order_type'] = 'shop';
		if ($is_staff) {
			if($order['card_id']){
				$use_result = D('Card_new_coupon')->user_coupon($order['card_id'], $order['order_id'], 'shop', $order['mer_id'], $order['uid']);
				if($use_result['error_code']){
					return array('error' => 1, 'msg' => $use_result['msg']);
				}
			}

			if(floatval($order['merchant_balance']) > 0){
				$use_result = D('Card_new')->use_money($order['uid'], $order['mer_id'], $order['merchant_balance'], '购买 '. $order['real_orderid'].' 扣除会员卡余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			if(floatval($order['card_give_money']) > 0){
				$use_result = D('Card_new')->use_give_money($order['uid'], $order['mer_id'], $order['card_give_money'], '购买 ' . $order['real_orderid'] . ' 扣除会员卡赠送余额');
				if ($use_result['error_code']) {
					return array('error' => 1, 'msg' => $use_result['msg']);
				}
			}

			$goods = D('Shop_order_detail')->field(true)->where(array('order_id' => $order['order_id']))->select();
			$goods_obj = D("Shop_goods");
			foreach ($goods as $gr) {
				$goods_obj->update_stock($gr);//修改库存
			}

			$order['order_type'] = 'shop_offline';
		}
		//D('Merchant_money_list')->add_money($this->store['mer_id'],'用户在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元记入收入',$order);
		$order['desc']='用户在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元记入收入';
		D('SystemBill')->bill_method($order['is_own'],$order);


		//商家推广分佣
		$now_user = M('User')->where(array('uid'=>$order['uid']))->find();
		D('Merchant_spread')->add_spread_list($order,$now_user,'shop',$now_user['nickname'].'用户购买快店商品获得佣金');

		//积分
		if(C('config.open_extra_price')==1){
			$score = D('Percent_rate')->get_extra_money($order);
			if($score>0){
				D('User')->add_score($order['uid'], floor($score),'在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.C('config.extra_price_alias_name'));
			}

		}else{
			if($this->config['add_score_by_percent']==0 && ($this->config['open_score_discount']==0 || $order['score_discount_type']!=2)){
				if($order['is_own'] && $this->config['user_own_pay_get_score']!=1 ){
					$order['payment_money'] = 0;
				}
				if($this->config['shop_goods_score_edit'] == 1){
					$score_get = D('Percent_rate')->shop_get_score($order);
				}else{
					$score_get  = round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']);
				}
				D('User')->add_score($order['uid'], $score_get, '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);
				D('Scroll_msg')->add_msg('shop',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). $this->store['name'] . ' 中消费获得'.$this->config['score_name']);
			}
			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name'].'');
		}
		//短信
		$sms_data = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'shop');
		if ($this->config['sms_shop_finish_order'] == 1 || $this->config['sms_shop_finish_order'] == 3) {
			if (empty($order['phone'])) {
				$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
				$order['phone'] = $user['phone'];
			}
			$sms_data['uid'] = $order['uid'];
			$sms_data['mobile'] = $order['userphone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在 ' . $this->store['name'] . '店中下的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_shop_finish_order'] == 2 || $this->config['sms_shop_finish_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $this->store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费！';
			Sms::sendSms($sms_data);
		}

		//打印
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($order['order_id'], 'shop_order', 2);

// 		$msg = ArrayToStr::array_to_str($order['order_id'], 'shop_order');
// 		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
// 		$op->printit($this->store['mer_id'], $this->store['store_id'], $msg, 2);

// 		$str_format = ArrayToStr::print_format($order['order_id'], 'shop_order');
// 		foreach ($str_format as $print_id => $print_msg) {
// 			$print_id && $op->printit($this->store['mer_id'], $this->store['store_id'], $print_msg, 2, $print_id);
// 		}

	}

	public function store_order()
	{
		$store_order = D('Store_order');
		import('@.ORG.merchant_page');
		$where = array('paid' => 1, 'store_id' => $this->store['store_id'], 'from_plat' => 1);

		$count = $store_order->where($where)->count();
		$p = new Page($count, 20);

		$sql = "SELECT s.*, u.nickname, u.phone FROM " . C('DB_PREFIX') . "store_order AS s INNER JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid WHERE s.paid=1 AND s.store_id={$this->store['store_id']} AND s.from_plat=1 ORDER BY s.order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
		$order_list = $store_order->query($sql);
		foreach ($order_list as &$l) {
			$l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'], $l['is_mobile_pay']);
			$l['pay_money'] = $l['balance_pay']+$l['payment_money']+$l['merchant_balance'];
		}
		$pagebar = $p->show();

		$this->assign(array('order_list' => $order_list, 'pagebar' => $pagebar));
		$this->display();
	}

	public function pick()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
		if(empty($order)){
			$this->error('订单不存在！');
			exit;
		}
		$pick_order = D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->find();
		if (IS_POST) {
			if ($order['status'] == 2 || $order['status'] == 3) {
				$this->error('订单已消费，不能分配！');
				exit;
			}
			if ($order['status'] == 4 || $order['status'] == 5) {
				$this->error('订单已取消，不能分配！');
				exit;
			}
			if ($order['is_refund']) {
				$this->error('用户正在退款中~！');
				exit;
			}
			if ($order['paid'] == 0) {
				$this->error('订单未支付，不能接单！');
				exit;
			}
			$pick_id = isset($_POST['pick_id']) ? htmlspecialchars($_POST['pick_id']) : '';
			$pick_address = null;
			if ($pick_id) {
				$type = substr($pick_id, 0, 1);
				$type = $type == 's' ? 1 : 0;//0:表示商家添加的自提点，1：默认的店铺
				$pick_id = substr($pick_id, 1);
				$pick_address = D('Pick_address')->field(true)->where(array('id' => $pick_id, 'mer_id' => $this->store['mer_id']))->find();

			}
			if (empty($pick_address)) {
				$this->error('没有分配自提点！', U('Store/pick', array('order_id' => $order_id)));
				exit;
			}
			if (empty($pick_order)) {
				D('Pick_order')->add(array('store_id' => $order['store_id'], 'order_id' => $order['order_id'], 'type' => $type, 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
				D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 7, 'order_status' => 1, 'pick_id' => $pick_id));
				$phones = explode(' ', $this->store['phone']);
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));//分配到自提点
				$this->success('分配成功！', U('Store/pick', array('order_id' => $order_id)));
				exit;
			} else {
				$this->error('不要重复分配！', U('Store/pick', array('order_id' => $order_id)));
				exit;
			}
		}

		$user = D('User_adress')->where(array('adress_id' => $order['address_id'], 'uid' => $order['uid']))->find();
		$pick_addr = D('Pick_address')->get_pick_addr_by_merid($this->store['mer_id'],true);
		if ($order['pick_id']) {
			$t_pick = array();
			foreach ($pick_addr as $v) {
				if ('p' . $order['pick_id'] == $v['pick_addr_id']) {
					$t_pick = $v;
				}
			}
			$pick_addr = array($t_pick);
		} else {
			foreach ($pick_addr as &$v) {
				$v['range'] = getRange(getDistance($v['lat'], $v['long'], $user['latitude'], $user['longitude']));
			}
		}
		$this->assign('order', $order);
		$pick_order['pick_id'] = isset($pick_order['pick_id']) && $pick_order['pick_id'] ? ($pick_order['type'] == 1 ? 's' . $pick_order['pick_id'] : 'p' . $pick_order['pick_id']) : '';

		$this->assign('pick_order', $pick_order);
		$this->assign('pick_addr', $pick_addr);
		$this->display();
	}

	public function deliver_goods()
	{
		$database = D('Shop_order');
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		$condition['store_id'] = $this->store['store_id'];
		$order = $database->field(true)->where($condition)->find();


		if(empty($order)){
			$this->error('订单不存在！');
			exit;
		}
		if ($order['status'] == 2 || $order['status'] == 3) {
			$this->error('订单已消费，不能发货！');
			exit;
		}
		if ($order['status'] == 4 || $order['status'] == 5) {
			$this->error('订单已取消，不能发货！');
			exit;
		}
		if ($order['is_refund']) {
			$this->error('用户正在退款中~！');
			exit;
		}
		if ($order['paid'] == 0) {
			$this->error('订单未支付，不能发货！');
			exit;
		}
		$data = array('status' => 8);
		$data['last_staff'] = $this->staff_session['name'];
		if (D('Shop_order')->where(array('order_id' => $order_id))->save($data)) {//发货
			D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 1));
			$phones = explode(' ', $this->store['phone']);
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 12, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));//发货
			$this->success('已发货');
		} else {
			$this->error('发货失败，稍后重试！');
		}
	}

    public function goods()
    {
        $begin_time = strtotime(date('Y-m-d', strtotime('-1 day')) . ' 00:00:01');
        $end_time = strtotime(date('Y-m-d', strtotime('-1 day')) . ' 23:59:59');
        if (IS_POST) {
            $begin_time = isset($_POST['begin_time']) ? strtotime($_POST['begin_time']) : $begin_time;
            $end_time = isset($_POST['end_time']) ? strtotime($_POST['end_time']) : $end_time;
        }
        $this->assign(array(
            'end_time' => date('Y-m-d H:i:s', $end_time),
            'begin_time' => date('Y-m-d H:i:s', $begin_time)
        ));
        $data = array(
            'store_id' => $this->store['store_id']
        );
        $data['begin_time'] = $begin_time;
        $data['end_time'] = $end_time;
        $this->assign('list', D('Shop_order')->order_count($data));
        $this->display();
    }

    public function goods_sale()
    {
        $begin_time = strtotime(date('Y-m-d', strtotime('-1 day')) . ' 00:00:01');
        $end_time = strtotime(date('Y-m-d', strtotime('-1 day')) . ' 23:59:59');
        if (IS_POST) {
            $begin_time = isset($_POST['begin_time']) ? strtotime($_POST['begin_time']) : $begin_time;
            $end_time = isset($_POST['end_time']) ? strtotime($_POST['end_time']) : $end_time;
            $sortId = isset($_POST['sort_id']) ? intval($_POST['sort_id']) : 0;
            $orderFrom = isset($_POST['orderFrom']) ? intval($_POST['orderFrom']) : 0;
        }

        $this->assign(array(
            'end_time' => date('Y-m-d H:i:s', $end_time),
            'begin_time' => date('Y-m-d H:i:s', $begin_time)
        ));
//         $data = array('store_id' => $this->store['store_id']);

//         $shopGoodsSortDB = D('Shop_goods_sort');
//         $sortList = $shopGoodsSortDB->field(true)->where(array('store_id' => $this->store['store_id'], 'fid' => 0))->select();
//         if ($sortId) {
//             $ids = $shopGoodsSortDB->getAllSonIds($sortId, $this->store['store_id']);
//             $ids && $data['sort_id'] = $ids;
//         }
//         if ($orderFrom) {
//             if ($orderFrom == 1) {
//                 $data['order_from'] = 1;
//             } elseif ($orderFrom == 3) {
//                 $data['order_from'] = 6;
//             } else {
//                 $data['order_from'] = 2;
//             }
//         }
//         $data['begin_time'] = $begin_time;
//         $data['end_time'] = $end_time;

        //------
        $store_id = $this->store['store_id'];

        $shopGoodsSortDB = D('Shop_goods_sort');
        $sortList = $shopGoodsSortDB->field(true)->where(array('store_id' => $store_id, 'fid' => 0))->select();

        $where = 'o.paid=1 AND (o.status=2 OR o.status=3) AND o.store_id=' . $store_id;
        if ($sort = D('Shop_goods_sort')->field(true)->where(array('sort_id' => $sortId, 'store_id' => $store_id))->find()) {
            if ($sort['fid'] == 0) {
                $ids = $shopGoodsSortDB->getAllSonIds($sortId, $store_id);
                $ids && $where .= ' AND d.sort_id IN (' . implode(',', $ids) . ')';
            }
        }
        if ($begin_time&& $end_time) {
            $where .= ' AND o.pay_time>' . $begin_time . ' AND o.pay_time<' . $end_time;
        }
        if ($orderFrom) {
            if ($orderFrom == 1) {
                $where .= ' AND o.order_from=1';
            } elseif ($orderFrom == 3) {
                $where .= ' AND o.order_from=6';
            } else {
                $where .= ' AND o.order_from<>1 AND o.order_from<>6';
            }
        }
        $sql = "SELECT count(1) AS cnt FROM " . C('DB_PREFIX') . 'shop_order AS o INNER JOIN ' . C('DB_PREFIX') . 'shop_order_detail AS d ON d.order_id=o.order_id WHERE ' . $where;
        $count = M()->query($sql);
        $count = isset($count[0]['cnt']) ? intval($count[0]['cnt']) : 0;
        import('@.ORG.merchant_page');
        $p = new Page($count, 20);
        $sql = "SELECT `d`.*, `o`.order_from FROM " . C('DB_PREFIX') . 'shop_order AS o INNER JOIN ' . C('DB_PREFIX') . 'shop_order_detail AS d ON d.order_id=o.order_id WHERE ' . $where . ' LIMIT ' . $p->firstRow . ',' . $p->listRows;
        $orders = M()->query($sql);

        $this->assign('sort_list', $sortList);
        $this->assign('sortId', $sortId);
        $this->assign('orderFrom', $orderFrom);
        $this->assign('list', $orders);
        $this->assign('pagebar', $p->show());
        $this->display();
    }


	/*
	 *
	 *
	 *	到店支付  begin
	 *
	 *
	 */
	public function store_arrival()
	{
		$store_order = D('Store_order');
		import('@.ORG.merchant_page');
		$where = array('paid' => 1, 'store_id' => $this->store['store_id'], 'from_plat' => array('in','2,3'));

		$count = $store_order->where($where)->count();
		$p = new Page($count, 20);

		$sql = "SELECT s.*, u.nickname, u.phone FROM " . C('DB_PREFIX') . "store_order AS s LEFT JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid WHERE s.paid=1 AND s.store_id={$this->store['store_id']} ORDER BY s.order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
		$order_list = $store_order->query($sql);

		$offline_pay_list = M('Store_pay')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();
		foreach($offline_pay_list as $key=>$value){
			$offline_pay_array[$value['id']] = $value['name'];
		}

		foreach ($order_list as &$l) {
			if($l['pay_type'] == 'offline'){
				$l['pay_type_show'] = '线下支付';
				if($l['offline_pay'] && $offline_pay_array[$l['offline_pay']]){
					$l['pay_type_show'].= ' ('.$offline_pay_array[$l['offline_pay']].')';
				}
			}else{
				$l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'], $l['is_mobile_pay']);
				if($l['from_plat']==3){
					$l['pay_type_show'].= ' (扫用户码)';
				}
			}
			$l['pay_money'] = $l['balance_pay']+$l['payment_money']+$l['merchant_balance'];
		}
		$pagebar = $p->show();

		$this->assign(array('order_list' => $order_list, 'pagebar' => $pagebar));
		$this->display();
	}
	public function store_arrival_add(){
		if(IS_POST){
			$data = array('store_id' => $this->store['store_id']);
			$data['mer_id'] = $this->store['mer_id'];
			$data['orderid'] = date("YmdHis") . mt_rand(10000000, 99999999);
			$data['name'] = $_POST['pay_title'] ? $_POST['pay_title'] : '顾客现场自助支付';
			$data['desc'] = $_POST['txt_info'];
			$data['total_price'] = $_POST['total_price'];
			$data['discount_price'] = $_POST['discount_money'];
			$data['price'] = $_POST['pay_money'];
			$data['dateline'] = time();
			$data['from_plat'] = 2;
			$data['staff_id'] = $this->staff_session['id'];
			$data['staff_name'] = $this->staff_session['name'];
			$data['extra_price'] = $_POST['extra_price'];
			$data['source'] = 'pc_staff_pay_auto';
            $data['discount_price'] = $_POST['discount_money'];
            $data['mer_table_discount'] = $_POST['mer_table_discount'] ? $_POST['mer_table_discount'] * 10 : 0;
            $data['mer_table_scale'] = $_POST['mer_table_scale'] ? $_POST['mer_table_scale'] : 0;

			if($_POST['from_scan']){
				$data['uid'] = intval($_POST['uid']);
				$data['payid'] = $_POST['payid'];
				//$this->success('SCAN_PAY_SUCCESS');
			}
			$now_user = D('User')->get_user($_POST['user_phone'],'phone');
			if(!empty($_POST['user_phone'])) {
				if (empty($now_user)) {
					if(!$this->config['international_phone'] && !preg_match('/^[0-9]{11}$/',$_POST['user_phone'])){
						$this->error('请输入有效的手机号，无法自动注册');
					}
					$data_user['phone'] = $_POST['user_phone'];
					D('User')->autoreg($data_user);
				} else {
					$data['uid'] = $now_user['uid'];
				}
			}
            if ($_POST['business_type']) {
                $data['business_type'] = $_POST['business_type'];
                $data['business_id'] = $_POST['business_id'];
                if ($data['business_type'] == 'foodshop') {
                    $now_food_order = D('Foodshop_order')->field('order_id, status, book_price')->where(array('order_id' => $data['business_id']))->find();
                    if ($now_food_order && $now_food_order['status'] > 2) {
                        $this->error('此单已完成，不能再支付');
                    }
                }
            }
            
            //保存优惠券
            $coupon_discount_id = isset($_POST['coupon_discount_id']) ? intval($_POST['coupon_discount_id']) : 0;
            $coupon_discount_money = isset($_POST['coupon_discount_money']) ? floatval($_POST['coupon_discount_money']) : 0;
            $is_use_discount = isset($_POST['is_use_discount']) ? intval($_POST['is_use_discount']) : 0;
            if ($is_use_discount && $coupon_discount_id) {
                M('Plat_order')->where(array('business_id' => $_POST['business_id'], 'business_type' => 'foodshop'))->save(array('system_coupon_id' => $coupon_discount_id, 'system_coupon_price' => $coupon_discount_money));
            }
            
			if (floatval($data['price']) == 0) {
			    $data['paid'] = '1';
			    $data['pay_time'] = $_SERVER['REQUEST_TIME'];
			    $data['payment_money'] = 0;
			}
			
			
			$order_id = M("Store_order")->add($data);
			if($order_id){
				if($_POST['from_scan']){
					$this->success('SCAN_PAY_SUCCESS');
				}
			    if(floatval($data['price']) == 0){
			        $now_order = M("Store_order")->where(array('order_id' => $order_id))->find();
			        if($now_order['business_type']){
			            switch($now_order['business_type']){
			                case 'foodshop':
// 			                    $now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
			                    D('Foodshop_order')->after_pay($now_order['business_id'], array('is_own'=>0), 1);
			                    M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1','payment_method'=>$now_order['pay_type'],'note' => $_POST['txt_info']))->save();
			                    break;
			            }
			        }

			        if (C('config.open_extra_price') == 1 && $now_order['uid'] > 0) {
			            $now_order['order_type'] = 'cash';
			            $score = D('Percent_rate')->get_extra_money($now_order);

			            if ($score > 0) {
			                M('Store_order')->where(array('order_id'=>$order_id))->setField('score_give',$score);
			                //到店付给用户积分时需要商家预支积分
			                M('Merchant')->where(array('mer_id'=>$this->store['mer_id']))->setInc('extra_price_pay_for_system',$score);
			                $send_score_data['mer_id'] = $this->store['mer_id'];
			                $send_score_data['score_count'] = $score;
			                $send_score_data['add_time'] = time();
			                M('Merchant_score_send_log')->add($send_score_data);

			                M('Merchant')->where(array('mer_id'=>$this->store['mer_id']))->setInc('extra_price_pay_for_system',$score);
			                D('User')->add_score($now_order['uid'], floor($score),$this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得'.C('config.extra_price_alias_name'));
			            }

			        }
			        $this->success('SUCCESS');
			    } else {
			    	$now_order = M("Store_order")->where(array('order_id' => $order_id))->find();
			        if($now_order['business_type']){
			            switch($now_order['business_type']){
			                case 'foodshop': //保存备注
			                    M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('note' => $_POST['txt_info']))->save();
			                    break;
			            }
			        }
			        $this->success($order_id);
			    }
			}else{
				$this->error('订单创建失败，请重试');
			}
		}else{
			$now_store['discount_txt'] = unserialize($this->store['discount_txt']);

			$this->assign('has_discount',$now_store['discount_txt'] ? true : false);
			$this->assign('discount_type', isset($now_store['discount_txt']['discount_type']) ? $now_store['discount_txt']['discount_type'] : 0);
			$this->assign('discount_percent', isset($now_store['discount_txt']['discount_percent']) ? $now_store['discount_txt']['discount_percent'] : 0);
			$this->assign('condition_price', isset($now_store['discount_txt']['condition_price']) ? $now_store['discount_txt']['condition_price'] : 0);
			$this->assign('minus_price', isset($now_store['discount_txt']['minus_price']) ? $now_store['discount_txt']['minus_price'] : 0);

			if($_GET['business_type']){
				switch($_GET['business_type']){
					case 'foodshop':
						$now_order = D('Foodshop_order')->get_order_detail(array('order_id'=>$_GET['business_id']));
						//定制桌次折扣优惠券
                        if(1 == $this->config['is_open_merchant_foodshop_discount']){
                            $tablesDiscount = D('Merchant_store_foodshop')->getTableDiscount($this->store['mer_id'],$_GET['business_id'],$now_order);
                            if($tablesDiscount!=false && $tablesDiscount['mer_discount']!=0){
                                $this->assign('tablesDiscount',$tablesDiscount);
                            }
                        }
						if ($now_order && $now_order['status'] > 2) {
						    $this->error('订单已完成，不能再支付了');
						}
						$pay_money = D('Foodshop_order')->count_price($now_order);

                        //注释原商家折扣功能---------start
						//$can_discount_money = D('Foodshop_order')->count_price($now_order, 1);
						//可以打折的金额
						//$now_order['total_money'] = $can_discount_money;
                        //注释原商家折扣功能---------end
						$now_coupon = D('System_coupon')->get_noworder_coupon_list($now_order, 'plat', '', $now_order['uid'], 'wap', 'foodshop');
						$coupon_discount = $now_coupon[0];

// 						$data_foodshop_order['system_coupon_id']=  $coupon_discount['id'];
// 						$data_foodshop_order['system_coupon_price'] = $coupon_discount['discount_money'];
// 						//保存优惠信息到订单
// 						if($coupon_discount['id'] ){
// 							M('Plat_order')->where(array('business_id'=>$_GET['business_id'],'business_type'=>'foodshop'))->save($data_foodshop_order);
// 							//$pay_money-=$coupon_discount['discount_money'];
// 						}


						$this->assign('coupon_discount',$coupon_discount);
						$pay_title = '餐饮订单：'.$now_order['real_orderid'];
						break;
				}
				$this->assign('pay_money',$pay_money);
				$this->assign('pay_title',$pay_title);
			}

			$this->display();
		}
	}
	public function store_arrival_order(){
		$order_id  = $_GET['order_id'];
		$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
		$this->assign('now_order',$now_order);

		$orderprinter = M("Orderprinter")->where(array('store_id'=>$this->store['store_id']))->order('`is_main` DESC')->find();
		$this->assign('orderprinter',$orderprinter);

		$offline_pay_list = M('Store_pay')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();
		$this->assign('offline_pay_list',$offline_pay_list);
		$now_merchant = D('Merchant')->get_info($this->store['mer_id']);
		$is_sub_alipay = $this->config['pay_alipay_sp_appid']!=''&& $this->config['pay_alipay_sp_prikey']!='' && $now_merchant['alipay_auth_code']!='';
		$this->assign('is_sub_alipay',$is_sub_alipay);
		$this->display();
	}
	public function store_arrival_print()
	{
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($_POST['order_id'], 'store_order', -1);
	}
	public function store_arrival_alipay_pay($order_id,$now_order){
		$now_merchant = D('Merchant')->get_info($this->store['mer_id']);
		if($now_merchant['open_sub_mchid']==1 && $this->config['pay_alipay_sp_appid']!=''&& $this->config['pay_alipay_sp_prikey']!='' && $now_merchant['alipay_auth_code']!=''){
			$this->store_alipay_face_to_face($order_id,$now_order,$now_merchant['alipay_auth_code']);
			die;
		}
		if(empty($this->config['arrival_alipay_open'])){
			$this->error('平台未开启支付宝收银');
		}
		$param['app_id'] = $this->config['arrival_alipay_app_id'];
		$param['method'] = 'alipay.trade.pay';
		$param['charset'] = 'utf-8';
		$param['sign_type'] = $this->config['alipay_arrival_encrypt_type'] ? $this->config['alipay_arrival_encrypt_type'] : 'RSA';
		$param['timestamp'] = date('Y-m-d H:i:s');
		$param['version'] = '1.0';
		$biz_content = array(
				'out_trade_no' => 'store_'.$now_order['order_id'],
				'scene' => 'bar_code',
				'auth_code' => $_POST['auth_code'],
				'total_amount' => $now_order['price'],
				'subject' => $now_order['name'],
		);
		$param['biz_content'] = json_encode($biz_content,JSON_UNESCAPED_UNICODE);

		ksort($param);
		$stringToBeSigned = "";
		$i = 0;
		foreach ($param as $k => $v) {
			if (!empty($v) && "@" != substr($v, 0, 1)) {
				if ($i == 0) {
					$stringToBeSigned .= "$k" . "=" . "$v";
				} else {
					$stringToBeSigned .= "&" . "$k" . "=" . "$v";
				}
				$i++;
			}
		}

		$priKey = $this->config['arrival_alipay_app_prikey'];;
		$res = "-----BEGIN RSA PRIVATE KEY-----\n".wordwrap($priKey, 64, "\n", true)."\n-----END RSA PRIVATE KEY-----";

		if(!function_exists('openssl_sign')){
			$this->error('服务器不支持SSL，请联系管理员安装该插件。');
		}

		if($param['sign_type'] == 'RSA2'){
			openssl_sign($stringToBeSigned, $sign, $res, OPENSSL_ALGO_SHA256);
		}else{
			openssl_sign($stringToBeSigned, $sign, $res);
		}
		
		if(empty($sign)){
			$this->error('支付宝收银商户密钥错误，请联系管理员解决。');
		}

		$sign = base64_encode($sign);

		$param['sign'] = $sign;
		$requestUrl = "https://openapi.alipay.com/gateway.do?";
		foreach ($param as $sysParamKey => $sysParamValue) {
			$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
		}
		$requestUrl = substr($requestUrl, 0, -1);
		// echo $requestUrl;die;
		import('ORG.Net.Http');
		$http = new Http();

		$return = Http::curlGet($requestUrl);
		$returnArr = json_decode($return,true);
		$data['from_plat'] = 3; //扫用户支付码
		if(!empty($returnArr['alipay_trade_pay_response']) && "10000" == $returnArr['alipay_trade_pay_response']['code']){
			$data['paid'] = '1';
			$data['pay_time'] = strtotime($returnArr['alipay_trade_pay_response']['gmt_payment']);
			$data['pay_type'] = 'alipay';
			$data['third_id'] = $returnArr['alipay_trade_pay_response']['trade_no'];
			$data['payment_money'] = $returnArr['alipay_trade_pay_response']['receipt_amount'];
			if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
				$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
				$now_order['order_type']='cash';
				//商家余额增加
				//D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店支付宝支付计入收入',$now_order);
				$now_order['desc']='用户到店支付宝支付计入收入';
				D('SystemBill')->bill_method($now_order['is_own'],$now_order);

				if($now_order['business_type']){
					switch($now_order['business_type']){
						case 'foodshop':
							$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
							D('Foodshop_order')->after_pay($now_order['business_id'], null, 1);
							M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1','payment_method'=>$data['pay_type']))->save();
							break;
					}
				}

				$this->success('支付成功！');
			}else{
				$this->error('支付失败！请联系管理员处理。');
			}
		}else if(!empty($returnArr['alipay_trade_pay_response']) && "10003" == $returnArr['alipay_trade_pay_response']['code']){	//需要用户处理，下次查询订单
			$this->error('需要用户确认支付，请在用户成功支付后再点击支付按钮。');
		}else{
			if($returnArr['alipay_trade_pay_response']['sub_code'] == 'ACQ.TRADE_HAS_SUCCESS' && $now_order['paid'] != 1){
				$data['paid'] = '1';
				$data['pay_time'] = strtotime($returnArr['alipay_trade_pay_response']['gmt_payment']);
				$data['pay_type'] = 'alipay';
				$data['third_id'] = $returnArr['alipay_trade_pay_response']['trade_no'];
				$data['payment_money'] = $returnArr['alipay_trade_pay_response']['receipt_amount'];
				if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
					$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
					$now_order['order_type']='cash';
					//商家余额增加
					//D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店支付宝支付计入收入',$now_order);
					$now_order['desc']='用户到店支付宝支付计入收入';
					D('SystemBill')->bill_method($now_order['is_own'],$now_order);

					if($now_order['business_type']){
						switch($now_order['business_type']){
							case 'foodshop':
								$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
								D('Foodshop_order')->after_pay($now_order['business_id'], null, 1);
								M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1','payment_method'=>$data['pay_type']))->save();
								break;
						}
					}

					$this->success('支付成功！');
				}else{
					$this->error('支付失败！请联系管理员处理。');
				}
			}else{
				$this->error('支付失败！支付宝返回：'.$returnArr['alipay_trade_pay_response']['sub_msg']);
			}
		}
	}
	public function store_arrival_offline_pay($order_id,$now_order){
		$data['paid'] = '1';
		$data['pay_time'] = $_SERVER['REQUEST_TIME'];
		$data['pay_type'] = 'offline';
		$data['offline_pay'] = $_POST['offline_pay'];
		if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
			$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
			if($now_order['business_type']){
				switch($now_order['business_type']){
					case 'foodshop':
						$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
						D('Foodshop_order')->after_pay($now_order['business_id'], null, 1);
						M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['price']+$now_food_order['book_price'],'pay_type'=>'1','payment_method'=>$data['pay_type']))->save();
						break;
				}
			}

			if(C('config.open_extra_price')==1&&$now_order['uid']>0){
				$now_order['order_type'] ='cash';
				$score = D('Percent_rate')->get_extra_money($now_order);

				if($score>0){
					M('Store_order')->where(array('order_id'=>$order_id))->setField('score_give',$score);
					//到店付给用户积分时需要商家预支积分
					M('Merchant')->where(array('mer_id'=>$this->store['mer_id']))->setInc('extra_price_pay_for_system',$score);
					$send_score_data['mer_id'] = $this->store['mer_id'];
					$send_score_data['score_count'] = $score;
					$send_score_data['add_time'] = time();
					M('Merchant_score_send_log')->add($send_score_data);

					M('Merchant')->where(array('mer_id'=>$this->store['mer_id']))->setInc('extra_price_pay_for_system',$score);
					D('User')->add_score($now_order['uid'], floor($score),$this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得'.C('config.extra_price_alias_name'));

				}

			}
			$this->success('支付成功！');
		}else{
			$this->error('支付失败！请联系管理员处理。');
		}

	}
	public function store_arrival_pay(){
		$order_id  = $_POST['order_id'];
		$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();

		if($_POST['offline_pay']){
			$this->store_arrival_offline_pay($order_id,$now_order);
			die;
		}

		if($this->config['open_juhepay']==1){
			import('@.ORG.LowFeePay');

			$order_info['pay_type'] = $_POST['auth_type']=='alipay'?'alipay':'scan_weixin';
			$order_info['order_id'] =   'store_'.$order_id;

			$order_info['order_total_money'] =  $now_order['price'];
			$order_info['order_name'] =  $now_order['name'];
			$pay_info['auth_code'] =  $_POST['auth_code'];
			$lowfeepay = new LowFeePay('juhepay');

			$mer_juhe = M('Merchant_juhe_config')->where(array('mer_id'=>$this->store['mer_id']))->find();
            if(!empty($mer_juhe)){
                $lowfeepay->userId =$mer_juhe['userid'];
                $order_info['is_own'] =1 ;
                $order_info['order_id'] =  $order_info['order_id'].'_1';
            }
			$go_pay_param= $lowfeepay->pay($order_info,$pay_info);

			if($go_pay_param['error']==1){
				$this->error($go_pay_param['msg']);
			}else{

				$data['paid'] = '1';
				$data['pay_time'] = time();

				$data['pay_type'] = $_POST['auth_type']=='alipay'?'alipay':'weixin';

				$data['third_id'] = $go_pay_param['thirdid'];
				if(!empty($mer_juhe)) {
					$data['is_own'] = 1;
				}

				$data['from_plat'] = 3; //扫用户支付码
				$data['payment_money'] = $now_order['price'];

				if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
					$now_order['order_type']='cash';
					$now_order['desc']='用户到店扫码支付计入收入';
					//商家余额增加

					D('SystemBill')->bill_method($now_order['is_own'],$now_order);
					$this->success($go_pay_param['msg']);

				}
			}
			die;
		}

		if($_POST['auth_type'] == 'alipay'){
			$this->store_arrival_alipay_pay($order_id,$now_order);
			die;
		}

		$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
		$sub_mch_pay = false;
		$is_own = 0;
		if($this->config['open_sub_mchid']){
			if($this->store['open_sub_mchid'] && $this->store['sub_mch_id']>0){
				$this->config['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
				$this->config['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
				$sub_mch_pay = true;
				$sub_mch_id = $this->store['sub_mch_id'];
				$is_own = 3 ;
			}else if ( $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
				$this->config['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
				$this->config['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
				$sub_mch_pay = true;
				$sub_mch_id = $now_merchant['sub_mch_id'];
				$is_own = 2 ;
			}
		}


		import('ORG.Net.Http');
		$http = new Http();

		//auth_code 获取openid
		$param = array();
		$param['appid'] = $this->config['pay_weixin_appid'];
		$param['mch_id'] = $this->config['pay_weixin_mchid'];
		$param['nonce_str'] = $this->createNoncestr();
		$param['auth_code'] = $_POST['auth_code'];
		$param['sign'] = $this->getWxSign($param);
		$return_openid_from_auth_code = Http::curlPostXml('https://api.mch.weixin.qq.com/tools/authcodetoopenid', $this->arrayToXml($param));
		if($return_openid_from_auth_code['return_code']=='SUCCESS' && $return_openid_from_auth_code['result_code'] =='SUCCESS'){
			$tmp_user_openid = $return_openid_from_auth_code['openid'];
		}
		$now_user = D('User')->get_user($tmp_user_openid,'openid');
		$now_order['order_type'] = 'store';
		if($now_user){
			if($this->config['san_pay_score_coupon']==1 && $now_merchant['san_pay_score_coupon']) {
				$check_result = D('Store_order')->check_coupon_score($now_user, $now_order);
				$now_order['card_id'] = $check_result['card_id'];
				$now_order['coupon_id'] = $check_result['coupon_id'];
				$now_order['score_used_count'] = $check_result['score_used_count'];
				$now_order['card_price'] = $check_result['card_price'];
				$now_order['coupon_price'] = $check_result['coupon_price'];
				$now_order['score_deducte'] = $check_result['score_deducte'];
				if ($now_order['card_id']) {
					$now_coupon = D('Card_new_coupon')->get_coupon_by_id($now_order['card_id']);
					if (empty($now_coupon)) {

						$this->error('支付异常：卡券不存在');
					}
				}

				//判断平台优惠券
				if ($now_order['coupon_id']) {
					$now_coupon = D('System_coupon')->get_coupon_by_id($now_order['coupon_id']);
					if (empty($now_coupon)) {
						$this->error('支付异常：系统卡券错误');
					}
				}

				$score_used_count = $now_order['score_used_count'];
				if ($now_user['score_count'] < $score_used_count) {
					$this->error('支付异常：积分异常');
				}
				$now_order['price'] = $check_result['total_money'];
			}
		}else{
			$access_token_array = D('Access_token_expires')->get_access_token();
			if (!$access_token_array['errcode']) {
				$result_user = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_array['access_token'].'&openid='.$tmp_user_openid.'&lang=zh_CN');
				$userinfo = json_decode($result_user,true);
				if(empty($userinfo['errcode'])) {
					//商家推广绑定关系
					D('Merchant_spread')->spread_add($now_order['mer_id'], $userinfo['openid'], 'storepay');
					$data_user = array(
						'openid' => $userinfo['openid'],
						'union_id' => ($userinfo['unionid'] ? $userinfo['unionid'] : ''),
						'nickname' => $userinfo['nickname'],
						'sex' => $userinfo['sex'],
						'province' => $userinfo['province'],
						'city' => $userinfo['city'],
						'avatar' => $userinfo['headimgurl'],
						'is_follow' => $userinfo['subscribe'],
						'source' => 'pc_staff_pay_auto2',
					);
					$reg_result = D('User')->autoreg($data_user);
					if ($reg_result['error_code']) {
						$now_user['uid'] = '0';
					} else {
						$now_user = D('User')->get_user($userinfo['openid'], 'openid');
					}
				}
			}else{
				$now_user['uid'] = '0';
			}
		}
		$success_pay_order = false;
		if($now_order['price']>0) {
			$session_key = 'store_order_userpaying_' . $order_id;
			if ($_SESSION[$session_key]) {
				$param = array();
				$param['appid'] = $this->config['pay_weixin_appid'];
				$param['mch_id'] = $this->config['pay_weixin_mchid'];
				$param['nonce_str'] = $this->createNoncestr();
				$param['out_trade_no'] = 'store_' . $now_order['order_id'];
				if ($sub_mch_pay) {
					$param['out_trade_no'] = 'store_' . $now_order['order_id'] . '_1';
					$param['sub_mch_id'] = $sub_mch_id;
				}
				$param['sign'] = $this->getWxSign($param);

				$return = Http::curlPostXml('https://api.mch.weixin.qq.com/pay/orderquery', $this->arrayToXml($param));
			} else {
				$param = array();
				$param['appid'] = $this->config['pay_weixin_appid'];
				$param['mch_id'] = $this->config['pay_weixin_mchid'];
				$param['nonce_str'] = $this->createNoncestr();
				$param['body'] = $now_order['name'];
				$param['out_trade_no'] = 'store_' . $now_order['order_id'];
				$param['total_fee'] = floatval($now_order['price'] * 100);
				$param['spbill_create_ip'] = get_client_ip();
				$param['auth_code'] = $_POST['auth_code'];
				if ($sub_mch_pay) {
					$param['out_trade_no'] = 'store_' . $now_order['order_id'] . '_1';
					$param['sub_mch_id'] = $sub_mch_id;
				}
				$param['sign'] = $this->getWxSign($param);
				$return = Http::curlPostXml('https://api.mch.weixin.qq.com/pay/micropay', $this->arrayToXml($param));
			}
			if ($return['return_code'] == 'FAIL') {
				$this->error('支付失败！微信返回：' . $return['return_msg']);
			}

			if ($return['err_code'] == 'USERPAYING') {
				$_SESSION[$session_key] = '1';
				$this->error('用户支付中，需要输入密码！请询问用户输入完成后，再点击“确认支付”');
			}
			if ($return['result_code'] == 'FAIL') {
				$this->error('支付失败！微信返回：' . $return['err_code_des']);
			}

			if ($return['trade_state'] == 'USERPAYING') {
				$this->error('用户支付中，需要输入密码！请询问用户输入完成后，再点击“确认支付”');
			}

			$data['pay_type'] = 'weixin';
			$success_pay_order = true;
		}else{
			$success_pay_order = true;
			$data['pay_type'] = '';
		}
		unset($_SESSION[$session_key]);
//		$now_user = D('User')->get_user($return['openid'],'openid');
//		if(empty($now_user)){
//			$access_token_array = D('Access_token_expires')->get_access_token();
//			if (!$access_token_array['errcode']) {
//				$result_user = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_array['access_token'].'&openid='.$return['openid'].'&lang=zh_CN');
//				$userinfo = json_decode($result_user,true);
//				if(empty($userinfo['errcode'])) {
//					//商家推广绑定关系
//					D('Merchant_spread')->spread_add($now_order['mer_id'], $userinfo['openid'], 'storepay');
//					$data_user = array(
//							'openid' => $userinfo['openid'],
//							'union_id' => ($userinfo['unionid'] ? $userinfo['unionid'] : ''),
//							'nickname' => $userinfo['nickname'],
//							'sex' => $userinfo['sex'],
//							'province' => $userinfo['province'],
//							'city' => $userinfo['city'],
//							'avatar' => $userinfo['headimgurl'],
//							'is_follow' => $userinfo['subscribe'],
//							'source' => 'pc_staff_pay_auto2',
//					);
//					$reg_result = D('User')->autoreg($data_user);
//					if ($reg_result['error_code']) {
//						$now_user['uid'] = '0';
//					} else {
//						$now_user = D('User')->get_user($userinfo['openid'], 'openid');
//					}
//				}
//			}else{
//				$now_user['uid'] = '0';
//			}
//		}

		if($success_pay_order) {
			if ($now_order['card_id']) {
				$use_result = D('Card_new_coupon')->user_coupon($now_order['card_id'], $now_order['order_id'], 'store', $now_order['mer_id'], $now_user['uid']);
				if ($use_result['error_code']) {
					$this->error('支付异常：卡券抵扣错误');
				} else {
					$data['card_id'] = $check_result['card_id'];
					$data['card_price'] = $check_result['card_price'];
				}
			}

			//如果使用了平台优惠券
			if ($now_order['coupon_id']) {
				$use_result = D('System_coupon')->user_coupon($now_order['coupon_id'], $now_order['order_id'], 'store', $now_order['mer_id'], $now_user['uid']);
				if ($use_result['error_code']) {
					$this->error('支付异常：系统卡券抵扣错误');
				} else {
					$data['coupon_id'] = $check_result['coupon_id'];
					$data['coupon_price'] = $check_result['coupon_price'];
				}
			}

			if ($score_used_count > 0) {
				$use_result = D('User')->user_score($now_user['uid'], $score_used_count, '购买 ' . $now_order['name'] . ' 扣除' . C('config.score_name'));
				if ($use_result['error_code']) {
					$this->error('支付异常：积分扣除异常');
				} else {
					$data['score_used_count'] = $check_result['score_used_count'];
					$data['score_deducte'] = $check_result['score_deducte'];
				}
			}
		}

		$data['uid'] = $now_user['uid'];
		$data['paid'] = '1';
		$data['pay_time'] = time();

		$data['third_id'] = $return['transaction_id'];
		$data['is_own'] = $is_own;
		$data['from_plat'] = 3; //扫用户支付码
		$data['payment_money'] = $return['total_fee']/100;
		$now_order['price'] = $data['payment_money'] + $data['score_deducte'] + $data['card_price'] + $data['coupon_price'];
		if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$href = C('config.site_url').'/wap.php?c=My&a=store_order_list';
			$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $this->store['name'], 'keyword1' => '店内收银支付提醒', 'keyword2' => $now_order['orderid'], 'keyword3' => $now_order['price'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '付款成功，感谢您的使用'), $this->store['mer_id']);
			if($now_user['uid']){
				$order = M("Store_order")->where(array('order_id'=>$order_id))->find();

				if(C('config.open_extra_price')==1){
					$order['order_type'] ='cash';
					$score = D('Percent_rate')->get_extra_money($order);
					if($score>0){
						M('Store_order')->where(array('order_id'=>$order_id))->setField('score_give',$score);
						//到店付给用户积分时需要商家预支积分
						D('User')->add_score($order['uid'], floor($score),$this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得'.C('config.extra_price_alias_name'));
					}

				}else{
					if($this->config['add_score_by_percent']==0){
						if($is_own && $this->config['user_own_pay_get_score']!=1){
							$order['payment_money'] = 0;
						}
						if(isset($this->config['store_score_get_percent'])){
							if($this->config['store_score_get_percent']==1){
								$this->config['score_get'] = $this->config['store_score_percent']/100;
							}else{
								$this->config['score_get'] =  $this->config['user_store_score_get'];
							}
						}
						M('Store_order')->where(array('order_id'=>$order_id))->setField('score_give',round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']));
						D('User')->add_score($now_user['uid'], round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '在' . $this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得'.$this->config['score_name']);
						D('Scroll_msg')->add_msg('store',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). $this->store['name'] . ' 中消费获得'.$this->config['score_name']);
					}
					D('Userinfo')->add_score($now_user['uid'], $now_order['mer_id'], $now_order['price'], '在 ' . $this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得'.$this->config['score_name'].'');
				}
			}
			$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
			$now_order['order_type']='cash';
			$now_order['desc']='用户到店微信支付计入收入';
			//商家余额增加
			//D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店微信支付计入收入',$now_order);
			D('SystemBill')->bill_method($now_order['is_own'],$now_order);


			D('Merchant_spread')->add_spread_list($now_order,$now_user,$now_order['order_type'],$now_user['nickname'].'用户到店支付获得佣金');

			if($now_order['business_type']){
				switch($now_order['business_type']){
					case 'foodshop':
						$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
						D('Foodshop_order')->after_pay($now_order['business_id'], null, 1);
						M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1','from_plat'=>3,'payment_type'=>$now_order['pay_type']))->save();
						break;
				}
			}

			$this->success('支付成功！');
		}else{
			$this->error('支付失败！请联系管理员处理。');
		}
	}


	public function store_alipay_face_to_face($order_id, $now_order,$auth_token){
		import('@.ORG.pay.Aop.AopClient');
		import('@.ORG.pay.Aop.SignData');
		import('@.ORG.pay.Aop.AlipayTradePayRequest');
		$aop = new AopClient ();
		$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
		$aop->appId = $this->config['pay_alipay_sp_appid'];
		$aop->rsaPrivateKey = $this->config['pay_alipay_sp_prikey'];
		$aop->alipayrsaPublicKey=$this->config['pay_alipay_sp_pubkey'];
		$aop->apiVersion = '1.0';
		$aop->signType = 'RSA2';
		$aop->postCharset='UTF-8';
		$aop->format='json';
		$request = new AlipayTradePayRequest();
		$biz_content = array(
				'out_trade_no' => 'store_'.$now_order['order_id'],
				'scene' => 'bar_code',
				'auth_code' => $_POST['auth_code'],
				'product_code' => 'FACE_TO_FACE_PAYMENT',
				'total_amount' => $now_order['price'],
				'subject' => $now_order['orderid'],
				'extend_params' => array(
					'sys_service_provider_id'=>$this->config['pay_alipay_pid'],
				),
		);

		$request->setBizContent(json_encode($biz_content,JSON_UNESCAPED_UNICODE));
		$result = $aop->execute ( $request,$_POST['auth_code'],$auth_token);

		$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
		$resultCode = $result->$responseNode->code;
		if(!empty($resultCode)&&$resultCode == 10000){
			$data['paid'] = '1';
			$data['pay_time'] = strtotime($result->$responseNode->gmt_payment);
			$data['pay_type'] = 'alipay';
			$data['third_id'] = $result->$responseNode->trade_no;
			$data['payment_money'] = $result->$responseNode->receipt_amount;
			if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
				$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
				$now_order['is_own']=2;
				$now_order['order_type']='cash';
				if($auth_token){
					$now_order['payment_money']=0;
				}
//				商家余额增加
//				D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店支付宝支付计入收入',$now_order);
				$now_order['desc']='用户到店支付宝支付计入收入(支付宝服务商子商户)';
				D('SystemBill')->bill_method($now_order['is_own'],$now_order);

				if($now_order['business_type']){
					switch($now_order['business_type']){
						case 'foodshop':
							$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
							D('Foodshop_order')->after_pay($now_order['business_id'], null, 1);
							M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1','payment_method'=>$data['pay_type']))->save();
							break;
					}
				}

				$this->success('支付成功！');
			}else{
				$this->error('支付失败！请联系管理员处理。支付宝返回信息：'.$result->$responseNode->sub_msg);
			}
		} elseif($resultCode == 10003) {
			$this->error("等待用户验证支付");
		}else{
			if($result->$responseNode->sub_code=='ACQ.TRADE_HAS_SUCCESS'){

				$data['paid'] = '1';
				$data['pay_time'] = strtotime($result->$responseNode->gmt_payment);
				$data['pay_type'] = 'alipay';
				$data['third_id'] = $result->$responseNode->trade_no;
				$data['payment_money'] = $result->$responseNode->receipt_amount;
				if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
					$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
					$now_order['is_own']=2;
					$now_order['order_type']='cash';
					//商家余额增加
					//D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店支付宝支付计入收入',$now_order);
					$now_order['desc']='用户到店支付宝支付计入收入';
					if($auth_token){
						$now_order['payment_money']=0;
					}
					D('SystemBill')->bill_method($now_order['is_own'],$now_order);

					if($now_order['business_type']){
						switch($now_order['business_type']){
							case 'foodshop':
								$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
								D('Foodshop_order')->after_pay($now_order['business_id'], null, 1);
								M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1','payment_method'=>$data['pay_type']))->save();
								break;
						}
					}

					$this->success('支付成功！');
				}else{
					$this->error('支付失败！请联系管理员处理。支付宝返回信息：'.$result->$responseNode->sub_msg);
				}
			}else{
				$this->error('支付失败！请联系管理员处理。支付宝返回信息：'.$result->$responseNode->sub_msg);
			}
		}
	}

	public function arrival_alipay_face_to_face($order_id, $now_order,$auth_token){
		import('@.ORG.pay.Aop.AopClient');
		import('@.ORG.pay.Aop.SignData');
		import('@.ORG.pay.Aop.AlipayTradePayRequest');
		$aop = new AopClient ();
		$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
		$aop->appId = $this->config['pay_alipay_sp_appid'];
		$aop->rsaPrivateKey = $this->config['pay_alipay_sp_prikey'];
		$aop->alipayrsaPublicKey=$this->config['pay_alipay_sp_pubkey'];
		$aop->apiVersion = '1.0';
		$aop->signType = 'RSA2';
		$aop->postCharset='UTF-8';
		$aop->format='json';
		$request = new AlipayTradePayRequest();
		$biz_content = array(
				'out_trade_no' => 'shop_'.$now_order['real_orderid'],
				'scene' => 'bar_code',
				'auth_code' => $_POST['auth_code'],
				'product_code' => 'FACE_TO_FACE_PAYMENT',
				'total_amount' => $now_order['price'],
				'subject' => $now_order['orderid'],
		);

		$now_merchant = D('Merchant')->get_info($this->store['mer_id']);
		$request->setBizContent(json_encode($biz_content,JSON_UNESCAPED_UNICODE));
		$result = $aop->execute ( $request,$_POST['auth_code'],$now_merchant['alipay_auth_code']);

		$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
		$resultCode = $result->$responseNode->code;
		if(!empty($resultCode)&&$resultCode == 10000){
			$data['paid'] = 1;
			$data['status'] = 2;
			$data['order_status'] = 5;
			$data['pay_time'] = strtotime($result->$responseNode->gmt_payment);
			$data['use_time'] = time();
			$data['pay_type'] = 'alipay';
			$data['third_id'] =  $result->$responseNode->trade_no;
			$data['payment_money'] =$result->$responseNode->receipt_amount;
			if (M('Shop_order')->where(array('order_id' => $order_id))->data($data)->save()) {
				$now_order = M('Shop_order')->where(array('order_id' => $order_id))->find();
				if($auth_token){
					$now_order['payment_money']=0;
				}
				$this->shop_notice($now_order, true);
				$this->success('支付成功！');
			} else {
				$this->error('支付失败！请联系管理员处理。');
			}
		} elseif($resultCode == 10003) {
			$this->error("等待用户验证支付");
		}else{
			if($result->$responseNode->sub_code=='ACQ.TRADE_HAS_SUCCESS'){

				$data['paid'] = 1;
				$data['status'] = 2;
				$data['order_status'] = 5;
				$data['pay_time'] = strtotime($result->$responseNode->gmt_payment);
				$data['use_time'] = time();
				$data['pay_type'] = 'alipay';
				$data['third_id'] =  $result->$responseNode->trade_no;
				$data['payment_money'] = $result->$responseNode->receipt_amount;
				if (M('Shop_order')->where(array('order_id' => $order_id))->data($data)->save()) {
					$now_order = M('Shop_order')->where(array('order_id' => $order_id))->find();
					if($auth_token){
						$now_order['payment_money']=0;
					}
					$this->shop_notice($now_order, true);
					$this->success('支付成功！');
				} else {
					$this->error('支付失败！请联系管理员处理。');
				}
			}else{
				$this->error('支付失败！请联系管理员处理。支付宝返回信息：'.$result->$responseNode->sub_msg);
			}
		}
	}

	public function store_arrival_check(){
		if($this->config['open_juhepay']==1){
			import('@.ORG.LowFeePay');
			$order['orderNo'] = 'store' . '_' . $_POST['order_id'];
			$lowfeepay = new LowFeePay('juhepay');
			$mer_juhe = M('Merchant_juhe_config')->where(array('mer_id'=>$this->store['mer_id']))->find();
			if(!empty($mer_juhe)){
				$lowfeepay->userId =$mer_juhe['userid'];
				$order_info['is_own'] =1 ;
				$order_info['order_id'] =  $order['orderNo'].'_1';
			}
			$go_pay_param = $lowfeepay->check($order);

			if($go_pay_param['error']==0){
				$date['pay_type'] = $go_pay_param['pay_type'];
				$date['payment_money'] = $go_pay_param['pay_money'];
				$date['third_id'] = $go_pay_param['third_id'];
				$date['is_own'] = $go_pay_param['is_own'];
				$date['paid'] = 1;
				$date['pay_time'] = $go_pay_param['pay_time'];

				if(M("Store_order")->where(array('order_id'=>$_POST['order_id']))->data($date)->save()){
					$now_order = M('Store_order')->where(array('order_id'=>$_POST['order_id']))->find();
					$now_order['order_type']='cash';
					$now_order['desc']='用户到店扫码支付计入收入';
					//商家余额增加

					D('SystemBill')->bill_method($now_order['is_own'],$now_order);
				}
			}
		}

		$now_order = M('Store_order')->where(array('order_id'=>$_POST['order_id']))->find();
		if($now_order['paid']){
			$this->success('支付成功！');
		}else{
			$this->error('还未支付');
		}
	}
	//作用：产生随机字符串，不长于32位
	public function createNoncestr( $length = 32 ){
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
		}
		return $str;
	}
	//微信支付密钥
	public function getWxSign($Obj)
	{
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = $this->formatBizQueryParaMap($Parameters, false);
		$String = $String."&key=".$this->config['pay_weixin_key'];
		$String = md5($String);
		$result_ = strtoupper($String);
		return $result_;
	}
	//格式化参数，签名过程需要使用
	function formatBizQueryParaMap($paraMap, $urlencode){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
			if($urlencode)
			{
				$v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar='';
		if (strlen($buff) > 0)
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	//数组转XML
	function arrayToXml($arr){
		$xml = "<xml>";
		foreach ($arr as $key=>$val){
			if (is_numeric($val)){
				$xml.="<".$key.">".$val."</".$key.">";
			}else{
				$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
			}
		}
		$xml.="</xml>";
		return $xml;
	}
	/*
	 *
	 *
	 *	到店支付  end
	 *
	 *
	 */
	public function ajax_foodshop_storestaff_order(){
		$where['store_id'] = $this->store['store_id'];
		$where['status'] = array('lt',3);
		$where['running_state'] = '1';
		$where['running_time'] = array('egt',$_POST['time']);
		$count = M("Foodshop_order")->where($where)->count();
		if($count > 0){
			$this->success('您有新订单');
		}else{
			$this->error($_SERVER['REQUEST_TIME']);
		}
	}
	public function foodshop()
	{
		$store_id = intval($this->store['store_id']);
		$where = array();
		if (IS_POST) {
			$real_orderid = isset($_POST['real_orderid']) ? htmlspecialchars($_POST['real_orderid']) : '';
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
			$real_orderid && $where['real_orderid'] = array('like', "%$real_orderid%");
			$name && $where['name'] = array('like', "%$name%");
			$phone && $where['phone'] = array('like', "%$phone%");
			$orderid && $where['orderid'] = $orderid;

			$this->assign('meal_pass', $meal_pass);
			$this->assign('real_orderid', $real_orderid);
			$this->assign('name', $name);
			$this->assign('phone', $phone);
			$this->assign('orderid', $orderid);
		}
		$where['store_id'] = $store_id;

		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= $type . ' ' . $sort . ',';
			$order_sort .= 'order_id DESC';
		} else {
			$order_sort .= 'order_id DESC';
		}
		$condition_where = 'WHERE o.mer_id = '.$this->store['mer_id'].' AND o.store_id = '.$this->store['store_id'];

		if ($status != -1 && $status <= 10) {
			$condition_where .= ' AND o.status ='.$status;
		}else if($status == 11){
			$condition_where .= ' AND o.status <3 AND o.running_state=1';
		}
        if (! empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'real_orderid') {
                $condition_where .= ' AND o.real_orderid =' . $_GET['keyword'];
            } elseif ($_GET['searchtype'] == 'orderid') {
                $condition_where .= ' AND o.orderid =' . $_GET['keyword'];
            } elseif ($_GET['searchtype'] == 'name') {
                $condition_where .= ' AND o.name ="' . $_GET['keyword'].'"';
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where .= ' AND o.phone =' . $_GET['keyword'];
            } elseif ($_GET['searchtype'] == 'third_id') {
                $condition_where .= ' AND p.third_id =' . $_GET['keyword'];
            }
        }

		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type&&$pay_type!='balance'){
			$condition_where .=' AND p.pay_type ="'.$pay_type.'"';
		}else if($pay_type=='balance'){
			$condition_where .= "  AND  (`p`.`system_balance`<>0 OR `p`.`merchant_balance_pay` <> 0 )";
		}


		$period = array();
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time'] . " 00:00:00"), strtotime($_GET['end_time'] . " 23:59:59"));
			$condition_where .=" AND  (o.create_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
		}
		$sql_cont = 'SELECT count(o.order_id) as count from pigcms_foodshop_order o LEFT JOIN (select a.* from pigcms_plat_order a,(select max(order_id) as order_id,business_id from pigcms_plat_order group by business_id) b where a.order_id=b.order_id and a.business_id=b.business_id) p on o.order_id  = p.business_id '.$condition_where.' ORDER BY o.order_id DESC';
		$count  = M()->query($sql_cont);
		$count = $count[0]['count'];

		import('@.ORG.merchant_page');
		$p = new Page($count, 20);
		//$list = D("Foodshop_order")->where($where)->order($order_sort)->limit($p->firstRow . ',' . $p->listRows)->select();
		$sql = 'SELECT o.*,p.pay_type as pay_method from pigcms_foodshop_order o LEFT JOIN (select a.* from pigcms_plat_order a,(select max(order_id) as order_id,business_id from pigcms_plat_order group by business_id) b where a.order_id=b.order_id and a.business_id=b.business_id) p on o.order_id  = p.business_id '.$condition_where.' ORDER BY o.order_id DESC'.' limit '.$p->firstRow.','.$p->listRows;
		$list = M('')->query($sql);

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
			if ($li['status'] < 1) {
			    $li['pay_type_show'] = '暂未支付';
			} else {
			    $li['pay_type_show'] = D('Pay')->get_pay_name($li['pay_method'], $li['is_mobile_pay']);
			}
			
			if ($li['from_plat'] == 3) {
				$li['pay_type_show'] .= ' (扫用户码)';
			}
		}
		$this->assign('order_list', $list);

		$pagebar = $p->show();
		$this->assign('status_list', D('Foodshop_order')->status_list);
		$this->assign('pagebar', $pagebar);

		//$this->assign(D("Foodshop_order")->get_order_list($where, $order_sort, 1));
		$shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		$shop = array_merge($this->store, $shop);
		$this->assign('now_store', $shop);

		$this->assign('status_list', D('Foodshop_order')->status_list);
		$this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status,'pay_type'=>$pay_type));
		$pay_method = D('Config')->get_pay_method('','',1);
		$this->assign('pay_method',$pay_method);
		$where = array('store_id' => $this->store['store_id'], 'paid' => 1);
		if ($period) {
		    $where['pay_time'] = array(array('gt', $period[0]), array('lt', $period[1]));
		}
		$where['business_type'] = 'foodshop';
		
		$data = array('total_price' => 0, 'online_price' => 0, 'offline_price' => 0);
		$field = 'sum(total_money - wx_cheap) AS total_price, sum(total_money - system_balance - system_coupon_price - system_score_money - merchant_balance_pay - merchant_balance_give - merchant_discount_money - merchant_coupon_price - pay_money) AS offline_price, sum(pay_money+system_balance + system_coupon_price + system_score_money + merchant_balance_pay + merchant_balance_give + merchant_discount_money + merchant_coupon_price) as online_price';
		$order = D('Plat_order')->field($field)->where($where)->find();
		$data['total_price'] += floatval($order['total_price']);
		$data['online_price'] += floatval($order['online_price']);
		$data['offline_price'] += floatval($order['offline_price']);
		$field = 'sum(price) AS total_price, sum(price - balance_pay - card_price - payment_money - merchant_balance - card_give_money - score_deducte) AS offline_price, sum(payment_money+balance_pay + card_price + merchant_balance + card_give_money + score_deducte) as online_price';
		$order = D('Store_order')->field($field)->where($where)->find();
		$data['total_price'] += floatval($order['total_price']);
		$data['online_price'] += floatval($order['online_price']);
		$data['offline_price'] += floatval($order['offline_price']);
		$this->assign($data);
		$this->display();
	}
	public function foodshop_order_before(){
		$table_type = M('Foodshop_table_type')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();

		if(empty($table_type)){
			$this->error('该店铺没有设置桌台分类');
		}

		$this->assign('table_type',$table_type);


		$table_list = M('Foodshop_table')->where(array('tid'=>$table_type[0]['id'],'status'=>'0'))->order('`id` ASC')->select();

		$this->assign('table_list',$table_list);

		$this->display();
	}
	public function get_table_list(){
		$table_list = M('Foodshop_table')->where(array('tid'=>$_GET['table_type'],'store_id'=>$this->store['store_id'],'status'=>'0'))->order('`id` ASC')->select();
		echo json_encode($table_list);
	}
	public function foodshop_add_order(){
		$data['real_orderid'] = date('ymdhis').substr(microtime(),2,8-strlen($this->store['store_id'])).$this->store['store_id'];
		$data['mer_id'] = $this->store['mer_id'];
		$data['store_id'] = $this->store['store_id'];
		$data['book_num'] = $_POST['book_num'];
		$data['table_type'] = $_POST['table_type'];
		$data['table_id'] = $_POST['table_id'];
		$data['create_time'] = $_SERVER['REQUEST_TIME'];
		$data['status'] = 1;
		$data['order_from'] = 2;
		if($order_id = D('Foodshop_order')->save_order($data)){
			M('Foodshop_table')->where(array('id'=>$data['table_id']))->data(array('status'=>'1'))->save();
			$this->success($order_id);
		}else{
			$this->error('创建订单失败，请重试');
		}
	}
	public function foodshop_order(){
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']), 3);

		$price = D('Foodshop_order')->count_price($order);
		if($this->config['open_extra_price']){
			$extra_price = D('Foodshop_order')->count_extra_price($order);
		}else{
			$extra_price = 0;
		}
		//备注
		$store_order = D('Store_order')->where(array('business_id'=>$order_id,'paid'=>1))->find();
		$store_order['desc'] && $order['note'] = $store_order['desc'];
		$this->assign('order', $order);
		$this->assign('price', $price);
		$this->assign('total_price', $price + $order['book_price']);
		$this->assign('extra_price', $extra_price);

		$goods_package = D('Foodshop_goods_package')->get_list_by_store_id($this->store['store_id']);
		$this->assign('goods_package', $goods_package);
		if ($order['status'] < 3) {
			$this->display();
		} else {
			$this->display('foodshop_order_detail');
		}
	}
	public function foodshop_getmenu(){
		$search	=	$_GET['search'];
		$tmpList = D('Foodshop_goods')->get_list_by_storeid($this->store['store_id']);

		foreach($tmpList as $value){
			$return['lists'][$value['sort_id']] = $value;
		}

		$return['package'] = D('Foodshop_goods_package')->get_list_by_store_id($this->store['store_id']);
		if(empty($return['package'])){
			$return['package'] = array();
		}

		$uids = array();
		$tmp_order = M('Foodshop_order_temp')->where(array('store_id'=>$this->store['store_id'],'order_id'=>$_GET['order_id'],'ishide' => 0))->order('`id` ASC')->select();
        if ($tmp_order) {
            foreach ($tmp_order as $tmp_value) {
                if (! in_array($tmp_value['uid'], $uids)) {
                    $uids[] = $tmp_value['uid'];
                }
                $tmp_value['price'] = floatval($tmp_value['price']);
                if ($this->config['open_extra_price'] == 0) {
                    $tmp_value['extra_pay_price'] = 0;
                }
                $return['tmp_order'][$tmp_value['uid']][] = $tmp_value;
            }
        } else {
            $return['tmp_order'] = array();
        }
        $return['order_detail'] = array();
        $order_details = M('Foodshop_order_detail')->where(array('store_id' => $this->store['store_id'], 'order_id' => $_GET['order_id'], 'ishide' => 0))->order('`id` ASC')->select();
        foreach ($order_details as $detail_value) {
            if (! in_array($detail_value['uid'], $uids)) {
                $uids[] = $detail_value['uid'];
            }
            $detail_value['price'] = floatval($detail_value['price']);
            $detail_value['num'] = floatval($detail_value['num']);
            
            $return['order_detail'][$detail_value['uid']][] = $detail_value;
        }
        $userTemp = D('User')->field('nickname, uid')->where(array('uid' => array('in', $uids)))->select();
        $userList = array();
        foreach ($userTemp as $user) {
            $userList[$user['uid']] = $user['nickname'];
        }
        $return['userList'] = $userList;
        echo json_encode($return);
	}
	public function foodshop_getgroup_detail(){
		echo json_encode(D('Foodshop_goods_package_detail')->get_detail_by_pid($_GET['group_id']));
	}
	public function foodshop_change_order(){
		$condition_where['store_id'] = $this->store['store_id'];
		$condition_where['order_id'] = $_GET['order_id'];
		$condition_where['id'] = $_POST['detail_id'];
		$detail = M('Foodshop_order_detail')->where($condition_where)->find();
		if($_POST['number'] > 0){
			if(M('Foodshop_order_detail')->where($condition_where)->data(array('num'=>$_POST['number']))->save()){
				$order = D('Foodshop_order')->get_order_detail(array('order_id' => intval($_GET['order_id']), 'store_id' => $this->store['store_id']), 2);
				$price = D('Foodshop_order')->count_price($order);
				if($this->config['open_extra_price']){
					$extra_price = D('Foodshop_order')->count_extra_price($order);
				}else{
					$extra_price = 0;
				}
				$detail['num'] = $_POST['number'] - $detail['num'];
				D('Foodshop_goods')->update_stock($detail);
				header('Content-Type:application/json; charset=utf-8');
				exit(json_encode(array('status' => 1, 'info' => '保存成功！', 'total_price' => floatval($price + $order['book_price']), 'book_price' => floatval($order['book_price']), 'unpaid_price' => floatval($price),'extra_price'=>$extra_price)));
				$this->success('保存成功！');
			}else{
				$this->error('保存失败！');
			}
		}else{
			if(M('Foodshop_order_detail')->where($condition_where)->delete()){
				$order = D('Foodshop_order')->get_order_detail(array('order_id' => intval($_GET['order_id']), 'store_id' => $this->store['store_id']), 2);
				$price = D('Foodshop_order')->count_price($order);
				if($this->config['open_extra_price']){
					$extra_price = D('Foodshop_order')->count_extra_price($order);
				}else{
					$extra_price = 0;
				}
				D('Foodshop_goods')->update_stock($detail, 1);
				header('Content-Type:application/json; charset=utf-8');
				exit(json_encode(array('status' => 1, 'info' => '保存成功！', 'total_price' => floatval($price + $order['book_price']), 'book_price' => floatval($order['book_price']), 'unpaid_price' => floatval($price),'extra_price'=>$extra_price)));
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
			}
		}
	}
	public function foodshop_save_order()
	{
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$package_id = isset($_POST['package_id']) ? intval($_POST['package_id']) : 0;
		$carts = isset($_POST['cart']) ? $_POST['cart'] : null;
		if (empty($carts)) {
			$this->error('没有点菜');
		}
		if ($order = M('Foodshop_order')->field(true)->where(array('store_id' => $this->store['store_id'], 'order_id' => $order_id))->find()) {
			$productCart = array();
			foreach ($carts as $row) {
				$params = array();
				$tmp_id = 0;
				if (isset($row['tmpOrderId']) && $row['tmpOrderId']) {
					if ($goods_temp = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'id' => $row['tmpOrderId']))->find()) {
					    $t_cookie = array('goods_id' => $goods_temp['goods_id'], 'tempId' => $goods_temp['id'], 'uid' => $goods_temp['uid'], 'fid' => $goods_temp['fid'], 'package_id' => $goods_temp['package_id'], 'num' => $row['count'], 'note' => $row['note'], 'name' => $goods_temp['name'], 'price' => floatval($goods_temp['price']),'extra_price'=>$goods_temp['extra_price']);
						$temp_params = array();
						if ($goods_temp['spec_id']) {
							$temp_params = D('Foodshop_goods')->format_spec_ids($goods_temp, $temp_params);
						}
						if ($goods_temp['spec']) {
							$temp_params = D('Foodshop_goods')->format_properties_ids($goods_temp, $temp_params);
						}
						$t_cookie['params'] = $temp_params;
						$productCart[] = $t_cookie;
						continue;
					}
				} elseif (isset($row['productParam'])) {
					foreach ($row['productParam'] as $r) {
						if ($r['type'] == 'spec') {
							$t_data = array('id' => $r['spec_id'], 'name' => '', 'type' => $r['type'], 'data' => array(array('id' => $r['id'], 'name' => $r['name'])));
						} else {
							$t_data = array('id' => 0, 'name' => '', 'type' => $r['type'], 'data' => '');
							$td = array();
							foreach ($r['data'] as $i => $d) {
								$t_data['id'] = $d['list_id'];
								$td[] = array('id' => $i, 'name' => $d['name']);
							}
							$t_data['data'] = $td;
						}
						$params[] = $t_data;
					}
				}
				$productCart[] = array('name' => $row['productName'], 'tempId' => 0, 'uid' => 0, 'goods_id' => $row['productId'], 'package_id' => 0, 'note' => $row['note'], 'num' => $row['count'], 'params' => $params,'extra_price'=>$row['extra_price']);
			}
			$cart_data = D('Foodshop_goods')->format_cart($productCart, $this->store['store_id'], $order_id, false);
			
			
			if ($cart_data['err_code']) {
				$this->error($cart_data['msg']);
			}
			$new_goods_list = $cart_data['data'];
			
			$total = $cart_data['total'];
			$price = $cart_data['price'];
			$now_time = time();
			if ($package_data = M('Foodshop_goods_package')->field(true)->where(array('id' => $package_id, 'store_id' => $this->store['store_id']))->find()) {
				$price = $package_data['price'];
				
				$hideData = array();
				$hideData['order_id'] = $order_id;
				$hideData['goods_id'] = $package_id;
				$hideData['name'] = $package_data['name'];
				$hideData['price'] = $package_data['price'];
				$hideData['unit'] = '份';
				$hideData['create_time'] = $now_time;
				$hideData['package_id'] = $package_id;
				$hideData['uid'] = 0;
				$hideData['ishide'] = 1;
				$hideData['is_discount'] = 1;
				$hideData['num'] = 1;
				$hideData['store_id'] = $this->store['store_id'];
				$hideData['extra_price'] = 0;
				if ($fid = D('Foodshop_order_detail')->add($hideData)) {
    				foreach ($new_goods_list as $index => $new_row) {
    					$new_row['order_id'] = $order_id;
    					$new_row['create_time'] = $now_time;
    					$new_row['package_id'] = $package_id;
    					$new_row['uid'] = 0;
    					$new_row['fid'] = $fid;
    					$new_row['store_id'] = $this->store['store_id'];
    					$new_row['extra_price'] = empty($new_row['extra_price'])?0:$new_row['extra_price'];
    					D('Foodshop_order_detail')->add($new_row);
    				}
//     				if ($order['package_ids']) {
//     					$package_ids = json_decode($order['package_ids'], true);
//     					$package_ids[] = $package_id;
//     				} else {
//     					$package_ids = array($package_id);
//     				}
				} else {
				    $this->error('套餐保存失败，稍后重试！');
				}
// 				$save_order_data = array('package_ids' => json_encode($package_ids));
// 				$save_order_data = array('price' => $price + $order['price'], 'package_ids' => json_encode($package_ids));
			} else {
			    $detail_list = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id, 'store_id' => $this->store['store_id'], 'package_id' => 0))->select();
				$detailList = array();
				foreach ($detail_list as $_row) {
				    $_t_index = $_row['uid'] . '_' . $_row['goods_id'];
					if (strlen($_row['spec']) > 0) {
						$_t_index .= '_' . md5($_row['spec']);
					}
					$detailList[$_t_index] = $_row;
				}

				$tempTableList = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'store_id' => $this->store['store_id'], 'package_id' => 0))->select();
				$temp_table_list = array();
				foreach ($tempTableList as $t_row) {
				    $t_t_index = $t_row['uid'] . '_' . $t_row['goods_id'];
				    if (strlen($t_row['spec']) > 0) {
				        $t_t_index .= '_' . md5($t_row['spec']);
				    }
				    $temp_table_list[$t_t_index] = $t_row;
				}
				$newPackageIDs = array();
				$detailDB = D('Foodshop_order_detail');
				$foodshopGoodsDB = D('Foodshop_goods');
				foreach ($new_goods_list as $index => $new_row) {
				    if ($new_row['package_id']) {//用户点的套餐
				        if (isset($newPackageIDs[$new_row['package_id']]) && $newPackageIDs[$new_row['package_id']]) {
				            $fid = $newPackageIDs[$new_row['package_id']];
				        } else {
				            if ($pack = D('Foodshop_goods_package')->field(true)->where(array('id' => $new_row['package_id']))->find()) {
    				            $tmp = array();
    				            $tmp['order_id'] = $order_id;
    				            $tmp['store_id'] = $this->store['store_id'];
    				            $tmp['package_id'] = $new_row['package_id'];
    				            $tmp['goods_id'] = $new_row['package_id'];
    				            $tmp['note'] = $new_row['note'];
    				            $tmp['ishide'] = 1;
    				            $tmp['is_discount'] = 1;
    				            $tmp['name'] = $pack['name'];
    				            $tmp['price'] = $pack['price'];
    				            $tmp['extra_price'] = 0;
    				            $tmp['uid'] = $new_row['uid'];
    				            $tmp['create_time'] = $now_time;
    				            $tmp['num'] = $new_row['num'];
    				            if ($fid = $detailDB->add($tmp)) {
    				                $newPackageIDs[$new_row['package_id']] = $fid;
    				            } else {
    				                $this->error('用户点的套餐保存失败，稍后重试！');
    				            }
				            } else {
				                $this->error('套餐信息不存在！');
				            }
				        }
				        if ($new_row['fid']) {
    				        $new_row['fid'] = $fid;
    				        $new_row['create_time'] = $now_time;
    				        $new_row['order_id'] = $order_id;
    				        $new_row['store_id'] = $this->store['store_id'];
    				        $new_row['extra_price'] = empty($new_row['extra_price']) ? 0 : $new_row['extra_price'];
    				        $detailDB->add($new_row);
				        }
				    } elseif (isset($detailList[$index])) {//这个用户已经点了这个菜品的时候 只是在已点菜品中增加数量
				        $detailDB->where(array('id' => $detailList[$index]['id']))->save(array('num' => $new_row['num'] + $detailList[$index]['num']));
				        unset($detailList[$new_row['uid']][$index]);
					} else {//新增的菜品
						$new_row['fid'] = 0;
						$new_row['package_id'] = 0;
						$new_row['create_time'] = $now_time;
						$new_row['order_id'] = $order_id;
						$new_row['store_id'] = $this->store['store_id'];
						$new_row['extra_price'] = empty($new_row['extra_price']) ? 0 : $new_row['extra_price'];
						$detailDB->add($new_row);
					}
					
					if (isset($temp_table_list[$index])) {
					    $diffNum = $new_row['num'] - $temp_table_list[$index]['num'];
					    if ($diffNum != 0) {
					        $temp_table_list[$index]['num'] = $diffNum;
					        $foodshopGoodsDB->update_stock($temp_table_list[$index]);
					    }
					    unset($temp_table_list[$index]);
					}
				}
				//将用户点的菜品，但是被店员给取消掉的回滚库存
				foreach ($temp_table_list as $i => $rowList) {
				    foreach ($rowList as $val) {
				        $foodshopGoodsDB->update_stock($val, 1);
				    }
				}
				//清空用户点餐的零时表
				D('Foodshop_order_temp')->where(array('order_id' => $order_id, 'store_id' => $this->store['store_id']))->delete();
			}
			if ($order['status'] < 2) {
				$save_order_data['status'] = 2;
			}
			$save_order_data['running_state'] = 0;
			$save_order_data['last_time'] = $_SERVER['REQUEST_TIME'];
			$save_order_data['running_time'] = $_SERVER['REQUEST_TIME'];

			if (M('Foodshop_order')->where(array('store_id' => $this->store['store_id'], 'order_id' => $order_id))->save($save_order_data)) {
				//配置打印
				D('Foodshop_order')->order_notice($order_id, $new_goods_list);
				$this->success('订单保存成功');
			} else {
				$this->error('订单保存失败，稍后重试！');
			}
		} else {
			$this->error('订单信息不存在！');
		}
	}

	public function foodshop_edit_order(){
		$now_order = M('Foodshop_order')->where(array('order_id'=>$_GET['order_id'],'store_id'=>$this->store['store_id']))->find();
		$this->assign('now_order',$now_order);

		$table_type = M('Foodshop_table_type')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();

		if(empty($table_type)){
			$this->error('该店铺没有设置桌台分类');
		}

		$this->assign('table_type',$table_type);


		$table_list = M('Foodshop_table')->where(array('tid'=>$now_order['table_type']))->order('`id` ASC')->select();

		$this->assign('table_list',$table_list);


		$this->display();
	}
	public function foodshop_edit_order_save(){
		$now_order = M('Foodshop_order')->where(array('order_id'=>$_POST['order_id'],'store_id'=>$this->store['store_id']))->find();

		$condition_order['order_id'] = $_POST['order_id'];
		$condition_order['store_id'] = $this->store['store_id'];
		$data['book_num'] = $_POST['book_num'];
		$data['table_type'] = $_POST['table_type'];
		$data['table_id'] = $_POST['table_id'];
		if(M('Foodshop_order')->where($condition_order)->data($data)->save()){
			M('Foodshop_table')->where(array('id'=>$now_order['table_id']))->data(array('status'=>'0'))->save();
			M('Foodshop_table')->where(array('id'=>$data['table_id']))->data(array('status'=>'1'))->save();
			$this->success('编辑成功');
		}else{
			$this->error('编辑订单失败，请重试');
		}
	}

	//只打印订单里面的商品，而且只用主打印机打印，一般用于用户结算前的打印。
	public function foodshop_print_order()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($order_id, 'foodshop_order', -1, 1);
		$this->success('打印成功');
	}

	public function tmp_table(){

		$table_type = M('Foodshop_table_type')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();
		if(empty($table_type)){
			$this->error('该店铺没有设置桌台分类');
		}

		$this->assign('table_type',$table_type);

		if($_GET['type_id']){
			$now_table = M('Foodshop_table_type')->where(array('store_id'=>$this->store['store_id'],'id'=>$_GET['type_id']))->find();
		}
		if(empty($now_table)){
			$now_table = $table_type[0];
		}
		$this->assign('now_table',$now_table);

		$table_list = M('Foodshop_table')->where(array('tid'=>$now_table['id']))->order('`id` ASC')->select();
		$this->assign('table_list',$table_list);

		$this->display();
	}

	public function book_list()
	{
		$table_id = isset($_GET['table_id']) && $_GET['table_id'] ? intval($_GET['table_id']) : 0;
		$where = array('store_id' => $this->store['store_id'], 'table_id' => $table_id, 'status' => array('in', array(1, 2)), 'book_time' => array('gt', time()));
		$list = D('Foodshop_order')->field(true)->where($where)->order('book_time ASC')->select();
		$this->assign('list', $list);
		$this->display();
	}

	public function tmp_table_lock(){
		if(M('Foodshop_table')->where(array('store_id'=>$this->store['store_id'],'id'=>$_POST['id']))->setField('status',$_POST['lock'])){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}

	/**
	 * 餐饮的排号管理
	 */
	public function queue()
	{
		$foodshop = M('Merchant_store_foodshop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		if ($foodshop['is_queue'] == 0) {
			$this->error('店铺没有排号功能');
		}

		$this->assign('queue_list', $this->queue_list(1));
		$store = array_merge($this->store, $foodshop);
		$this->assign('store', $store);
		$this->display();
	}

	public function change_queue()
	{
		$where = array('store_id' => $this->store['store_id']);
		$foodshop = M('Merchant_store_foodshop')->field(true)->where($where)->find();
		if (empty($foodshop)) {
			$this->error('店铺信息有问题');
		}
		if (M('Merchant_store_foodshop')->where($where)->save(array('queue_is_open' => 1 - intval($foodshop['queue_is_open']), 'queue_open_time' => time()))) {
			if ($foodshop['queue_is_open'] == 0) {
				//开启排号的时候清空以前的排号记录
				M('Foodshop_queue')->where($where)->delete();
				$msg = '点击关闭排号';
			} else {
				$msg = '点击开启排号';
			}
			$this->success($msg);
		} else {
			$this->error('状态修改失败');
		}
	}

	public function queue_list($param = 0)
	{
		$where = array('store_id' => $this->store['store_id']);
		$foodshop = M('Merchant_store_foodshop')->field(true)->where($where)->find();
		if ($foodshop['is_queue'] == 0) {
			if ($param) {
				$this->error('店铺没有排号功能');
				exit;
			} else {
				exit(json_encode(array('status' => 0)));
			}
		}
		if ($foodshop['queue_is_open'] == 0) {
			if ($param) {
// 				$this->error('店铺没有排号功能');
// 				exit;
				return false;
			} else {
				exit(json_encode(array('status' => 0)));
			}
		}

		//排队例表
		$queue_list = M('Foodshop_queue')->field(true)->where(array('store_id' => $this->store['store_id'], 'status' => 0))->order('id ASC')->select();
		$now_number_ids = null;
		$next_number_ids = null;
		$wait_number_list = array();
		foreach ($queue_list as $row) {
			if (!isset($now_number_ids[$row['table_type']])) {
				$now_number_ids[$row['table_type']] = $row['number'];
			} elseif (!isset($next_number_ids[$row['table_type']])) {
				$next_number_ids[$row['table_type']] = $row['number'];
			}
			if (isset($wait_number_list[$row['table_type']])) {
				$wait_number_list[$row['table_type']] ++;
			} else {
				$wait_number_list[$row['table_type']] = 1;
			}
		}
		$table_total = M('Foodshop_table')->field('count(tid) AS cnt, tid')->where(array('store_id' => $this->store['store_id'], 'status' => 0))->group('tid')->select();

		$temp = array();
		foreach ($table_total as $v) {
			$temp[$v['tid']] = $v['cnt'];
		}

		$table_type_list = M('Foodshop_table_type')->field(true)->where($where)->select();
		foreach ($table_type_list as &$t_row) {
			$t_row['now_number'] = '';
			$t_row['next_number'] = '';
			$t_row['wait'] = 0;
			$t_row['free'] = 0;
			if (isset($now_number_ids[$t_row['id']])) {
				$t_row['now_number'] = $now_number_ids[$t_row['id']];
			}
			if (isset($next_number_ids[$t_row['id']])) {
				$t_row['next_number'] = $next_number_ids[$t_row['id']];
			}
			if (isset($wait_number_list[$t_row['id']])) {
				$t_row['wait'] = $wait_number_list[$t_row['id']];
			}
			if (isset($temp[$t_row['id']])) {
				$t_row['free'] = $temp[$t_row['id']];
			}
		}
		if ($param) {
			return $table_type_list;
		} else {
			exit(json_encode(array('status' => 1, 'data' => $table_type_list)));
		}
	}

	public function queue_call()
	{
		$tid = isset($_POST['tid']) ? intval($_POST['tid']) : 0;
		$where = array('store_id' => $this->store['store_id'], 'table_type' => $tid, 'status' => 0);
		if ($queue = M('Foodshop_queue')->where($where)->order('id ASC')->limit(1)->find()) {
			//TODO 发送模板消息

			if ($user = D('User')->where(array('uid' => $queue['uid']))->find()) {
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				$href = C('config.site_url').'/wap.php?c=Foodshop&a=queue&store_id=' . $this->store['store_id'];
				$model->sendTempMsg('OPENTM205984119', array('href' => $href, 'wecha_id' => $user['openid'], 'first' => '尊敬的用户您好，已经排到您的号了', 'keyword1' => $queue['number'], 'keyword2' => date('Y.m.d H:i', $queue['create_time']), 'keyword3' => 1, 'remark' => '【' . $this->store['name'] . '】通知您进店用餐！'), $this->store['mer_id']);
			}


			$voic_baidu = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=XU7nTWQzS9vn32bckmrhHbTu&client_secret=1375720f23eed9b7b787e728de5dd1c2";
			import('ORG.Net.Http');
			$http = new Http();
			$return = Http::curlGet($voic_baidu);

			$voice_return = json_decode($return, true);
			$voice_access_token = $voice_return['access_token'];
			$msg = '请' . $queue['number'] . '号准备就餐';
			$voice_mp3 = 'http://tsn.baidu.com/text2audio?tex='.$msg.'&lan=zh&tok='.$voice_access_token.'&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';


			exit(json_encode(array('err_code' => false, 'msg' => '已叫号', 'mp3' => $voice_mp3)));
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '没有排号了')));
		}
	}

	public function queue_cancel()
	{
		$tid = isset($_POST['tid']) ? intval($_POST['tid']) : 0;
		$where = array('store_id' => $this->store['store_id'], 'status' => 0, 'table_type' => $tid);
		if ($queue = M('Foodshop_queue')->where($where)->order('id ASC')->limit(1)->find()) {
			$where['id'] = $queue['id'];
			if (M('Foodshop_queue')->where($where)->save(array('status' => 1))) {
				exit(json_encode(array('err_code' => false, 'msg' => 'ok')));
			} else {
				exit(json_encode(array('err_code' => true, 'msg' => '跳号失败，稍后重试')));
			}
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '不存在的信息')));
		}
	}

	public function queue_table()
	{
		$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;
		$queue = M('Foodshop_queue')->where(array('table_type' => $tid, 'store_id' => $this->store['store_id'], 'status' => 1))->find();
		if (empty($queue)) {
			$this->error('号码不存在', 'javascript:parent.location.reload();');
		}
		$now_table = M('Foodshop_table_type')->where(array('store_id' => $this->store['store_id'], 'id' => $tid))->find();
		if (empty($now_table)) {
			$this->error('该店铺没有设置桌台分类', 'javascript:parent.location.reload();');
		}
		$this->assign('now_table', $now_table);
		$table_list = M('Foodshop_table')->where(array('tid' => $now_table['id'], 'status' => 0))->order('`id` ASC')->select();
		if (empty($table_list)) {
			$this->error('暂无空闲桌台', 'javascript:parent.location.reload();');
		}
		$this->assign('table_list', $table_list);
		$this->assign('tid', $tid);
		$this->display();
	}

	public function queue_save()
	{
		$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;
		$queue = M('Foodshop_queue')->where(array('table_type' => $tid, 'store_id' => $this->store['store_id'], 'status' => 1))->find();
		if (empty($queue)) {
			$this->error('号码不存在');
		}
		if(M('Foodshop_table')->where(array('store_id'=>$this->store['store_id'],'id'=>$_POST['id']))->setField('status',$_POST['lock'])){
			$where = array('store_id' => $this->store['store_id'], 'id' => $queue['id'], 'table_type' => $tid);
			M('Foodshop_queue')->where($where)->save(array('status' => 2));
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}


	public function shop_export()
	{
		$param = $_POST;
		$param['type'] = 'shop';
		$param['rand_number'] = $_SERVER['REQUEST_TIME'];
		//$param['store_session'] = $this->store;
		 $param['store_session']['store_id'] = $this->store['store_id'];
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
		die;
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = $this->store['name'] . '订单信息';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		// 设置当前的sheet
		$condition_where = 'WHERE o.store_id = '.$this->store['store_id'];
		$where['store_id'] =$this->store['store_id'];


		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['real_orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .= ' AND o.real_orderid = "'. htmlspecialchars($_GET['keyword']).'"';
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['order_id'] = $tmp_result['order_id'];
				$condition_where .= ' AND o.order_id = '. $tmp_result['order_id'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['username'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=  ' AND o.username = "'.  htmlspecialchars($_GET['keyword']).'"';
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['userphone'] = htmlspecialchars($_GET['keyword']);
				$condition_where .= ' AND o.userphone = "'.  htmlspecialchars($_GET['keyword']).'"';
			}elseif ($_GET['searchtype'] == 'third_id') {
				$where['third_id'] =$_GET['keyword'];
			}

		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';

		if($status == 100){
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

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] =( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}

		$count = D('Shop_order')->where($where)->count();
		//$count = D('Shop_order')->where(array('store_id' => $this->store['store_id']))->count();

		$length = ceil($count / 1000);
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
			$objActSheet->setCellValue('R1', '支付时间');
			$objActSheet->setCellValue('S1', '送达时间');
			$objActSheet->setCellValue('T1', '订单状态');
			$objActSheet->setCellValue('U1', '支付情况');
			//$objActSheet->setCellValue('R1', 支付情况'支付情况');

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
						$objActSheet->setCellValueExplicit('R' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
						$objActSheet->setCellValueExplicit('S' . $index, $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '');
						$objActSheet->setCellValueExplicit('T' . $index, D('Shop_order')->status_list[$value['status']]);
						$objActSheet->setCellValueExplicit('U' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));
						$index++;
					}
					$tmp_id = $value['real_orderid'];

				}
			}
			sleep(2);
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}

	public function shop_change_price()
	{
		if (empty($this->staff_session['is_change'])) {
			$this->error('您没有修改价格的权限!');
		}
		if (IS_POST || IS_AJAX) {
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
			$where = array('order_id' => $order_id, 'store_id' => $this->store['store_id']);
			$order = D('Shop_order')->get_order_detail($where);
			if (empty($order)) {
				$this->error('不存在的订单信息!');
			}
			if (!($order['paid'] == 0 && $order['status'] == 0)) {
				$this->error('该订单已经不能修改支付价格了!');
			}
			$change_price = isset($_POST['change_price']) ? floatval($_POST['change_price']) : $order['price'];
			$change_reason = isset($_POST['change_reason']) ? htmlspecialchars($_POST['change_reason']) : '';
			if ($change_price == $order['price']) {
				$this->error('您没有修改价格!');
			}
			if ($change_price <= 0) {
				$this->error('您不能把价格改成小于等于0的数');
			}
			$data = array('price' => $change_price);
			$data['last_staff'] = $this->staff_session['name'];
			$data['last_time'] = $_SERVER['REQUEST_TIME'];
			if (floatval($order['change_price']) == 0) {
				$data['change_price'] = $order['price'];
			}
			$data['change_price_reason'] = $change_reason;

			if (D('Shop_order')->where($where)->save($data)) {
				$phones = explode(' ', $this->store['phone']);
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 30, 'name' => $this->staff_session['name'], 'phone' => $phones[0], 'note' => $change_price));
				$this->success('修改成功');
			} else {
				$this->error('修改出错，稍后重试！');
			}
		} else {
			$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
			$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
			$this->assign('order', $order);

			$this->display();
		}
	}


	
	public function mall_order_detail()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));

		$store_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
		
		//计算配送距离
		$distance = 0;
		if (C('config.is_riding_distance')) {
		    import('@.ORG.longlat');
		    $longlat_class = new longlat();
		    $distance = $longlat_class->getRidingDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long']);
		}
		$distance || $distance = getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long']);
		
		//获取配送的优惠金额信息
		$delivery_fee_reduce = 0;
		$discounts = D('Shop_discount')->getDiscounts($this->store['mer_id'], $this->store['store_id']);
		
		if ($d_tmp = D('Shop_goods')->getReduce($discounts, 2, $order['price'])) {
		    $delivery_fee_reduce = $d_tmp['reduce_money'];
		}
		$order['distance'] = $distance;
		
		//平台配送的相关设置信息的处理
		$distance = $distance / 1000;
		$deliverReturn = D('Deliver_set')->getDeliverInfo($store_shop, $order['price']);
		
		$pass_distance = $distance > $deliverReturn['basic_distance'] ? floatval($distance - $deliverReturn['basic_distance']) : 0;
		$deliverReturn['delivery_fee'] += round($pass_distance * $deliverReturn['per_km_price'], 2);
		$deliverReturn['delivery_fee'] = $deliverReturn['delivery_fee'] - $delivery_fee_reduce;
		$deliverReturn['delivery_fee'] = $deliverReturn['delivery_fee'] > 0 ? $deliverReturn['delivery_fee'] : 0;
		$delivery_fee = $deliverReturn['delivery_fee'];
		
		$delivery_fee2 = 0;
		$delivery_fee3 = 0;
		$timeList = array('time_select_1' => substr($deliverReturn['delivertime_start'], 0, -3) . '-' . substr($deliverReturn['delivertime_stop'], 0, -3));
		if (!($deliverReturn['delivertime_start2'] == $deliverReturn['delivertime_stop2'] && $deliverReturn['delivertime_start2'] == '00:00:00')) {
		    $pass_distance = $distance > $deliverReturn['basic_distance2'] ? floatval($distance - $deliverReturn['basic_distance2']) : 0;
		    $deliverReturn['delivery_fee2'] += round($pass_distance * $deliverReturn['per_km_price2'], 2);
		    $deliverReturn['delivery_fee2'] = $deliverReturn['delivery_fee2'] - $delivery_fee_reduce;
		    $deliverReturn['delivery_fee2'] = $deliverReturn['delivery_fee2'] > 0 ? $deliverReturn['delivery_fee2'] : 0;
		    $delivery_fee2 = $deliverReturn['delivery_fee2'];
		    $timeList['time_select_2'] = substr($deliverReturn['delivertime_start2'], 0, -3) . '-' . substr($deliverReturn['delivertime_stop2'], 0, -3);
		}
		if (!($deliverReturn['delivertime_start3'] == $deliverReturn['delivertime_stop3'] && $deliverReturn['delivertime_start3'] == '00:00:00')) {
		    $pass_distance = $distance > $deliverReturn['basic_distance3'] ? floatval($distance - $deliverReturn['basic_distance3']) : 0;
		    $deliverReturn['delivery_fee3'] += round($pass_distance * $deliverReturn['per_km_price3'], 2);
		    $deliverReturn['delivery_fee3'] = $deliverReturn['delivery_fee3'] - $delivery_fee_reduce;
		    $deliverReturn['delivery_fee3'] = $deliverReturn['delivery_fee3'] > 0 ? $deliverReturn['delivery_fee3'] : 0;
		    $delivery_fee3 = $deliverReturn['delivery_fee3'];
		    $timeList['time_select_3'] = substr($deliverReturn['delivertime_start3'], 0, -3) . '-' . substr($deliverReturn['delivertime_stop3'], 0, -3);
		}
		
		switch (intval(C('config.count_freight_charge_method'))) {
		    case 0:
		        break;
		    case 1:
		        $delivery_fee = ceil($delivery_fee * 10) / 10;
		        $delivery_fee2 = ceil($delivery_fee2 * 10) / 10;
		        $delivery_fee3 = ceil($delivery_fee3 * 10) / 10;
		        break;
		    case 2:
		        $delivery_fee = floor($delivery_fee * 10) / 10;
		        $delivery_fee2 = floor($delivery_fee2 * 10) / 10;
		        $delivery_fee3 = floor($delivery_fee3 * 10) / 10;
		        break;
		    case 3:
		        $delivery_fee = round($delivery_fee * 10) / 10;
		        $delivery_fee2 = round($delivery_fee2 * 10) / 10;
		        $delivery_fee3 = round($delivery_fee3 * 10) / 10;
		        break;
		    case 4:
		        $delivery_fee = ceil($delivery_fee);
		        $delivery_fee2 = ceil($delivery_fee2);
		        $delivery_fee3 = ceil($delivery_fee3);
		        break;
		    case 5:
		        $delivery_fee = floor($delivery_fee);
		        $delivery_fee2 = floor($delivery_fee2);
		        $delivery_fee3 = floor($delivery_fee3);
		        break;
		    case 6:
		        $delivery_fee = round($delivery_fee);
		        $delivery_fee2 = round($delivery_fee2);
		        $delivery_fee3 = round($delivery_fee3);
		        break;
		}
		
// 		if ($have_two_time) {
// 		    $this->assign(array('time_select_1' => substr($deliverReturn['delivertime_start'], 0, -3) . '-' . substr($deliverReturn['delivertime_stop'], 0, -3), 'time_select_2' => substr($deliverReturn['delivertime_start2'], 0, -3) . '-' . substr($deliverReturn['delivertime_stop2'], 0, -3)));
// 		} else {
// 		    $this->assign(array('time_select_1' => substr($deliverReturn['delivertime_start'], 0, -3) . '-' . substr($deliverReturn['delivertime_stop'], 0, -3)));
// 		}
		$this->assign($timeList);
		
		$diffTime = 60;
		if ($store_shop['send_time_type'] == 1) {
		    $diffTime = 3600;
		} elseif ($store_shop['send_time_type'] == 2) {
		    $diffTime = 86400;
		} elseif ($store_shop['send_time_type'] == 3) {
		    $diffTime = 86400 * 7;
		} elseif ($store_shop['send_time_type'] == 4) {
		    $diffTime = 86400 * 30;
		}
		$time = time() + $store_shop['work_time'] * $diffTime;

		$this->assign(array('delivery_fee' => $delivery_fee, 'delivery_fee2' => $delivery_fee2, 'delivery_fee3' => $delivery_fee3));
		$this->assign('arrive_datetime', date('Y-m-d H:i', $time));

		$this->assign('distance', round($distance, 2));
		$this->assign('store', $store_shop);
		$this->assign('order', $order);
		$this->display();

	}

	/**
	 * 商城订单更改配送方式  将快递 更改成 其他配送方式
	 */
	public function check_deliver()
	{
		$database = D('Shop_order');
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		$expect_use_time = isset($_POST['expect_use_time']) ? strtotime(htmlspecialchars($_POST['expect_use_time'])) : 0;
		$condition['store_id'] = $this->store['store_id'];
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->error('订单不存在！');
			exit;
		}

		if ($order['is_refund']) {
			$this->error('用户正在退款中~！');
			exit;
		}
		if ($order['paid'] == 0) {
			$this->error('订单未支付，不能接单！');
			exit;
		}
		if ($order['status'] > 0) {
			$this->error('该订单已处理，不能更改！');
			exit;
		}

		if ($order['is_pick_in_store'] != 3) {
			$this->error('不是快递配送，不能修改配送方式！');
			exit;
		}
		$d_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();

		if (in_array($d_shop['deliver_type'], array(2, 5))) {
			$this->error('店铺不支持快递以外的配送，不能修改配送方式！');
			exit;
		}
		$is_pick_in_store = $d_shop['deliver_type'] == 0 || $d_shop['deliver_type'] == 3 ? 0 : 1;

		
		$deliverReturn = D('Deliver_set')->getDeliverInfo($d_shop, $order['price'] - $order['freight_charge']);
		
		
		$start_time = $deliverReturn['delivertime_start'];
		$stop_time = $deliverReturn['delivertime_stop'];
		
		$start_time2 = $deliverReturn['delivertime_start2'];
		$stop_time2 = $deliverReturn['delivertime_stop2'];
		
		$start_time3 = $deliverReturn['delivertime_start3'];
		$stop_time3 = $deliverReturn['delivertime_stop3'];

		$distance = getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long']);
		$distance = $distance / 1000;
		
		$delivery_fee_reduce = 0;
		$discounts = D('Shop_discount')->getDiscounts($this->store['mer_id'], $this->store['store_id']);
		if ($d_tmp = D('Shop_goods')->getReduce($discounts, 2, $order['goods_price'])) {
		    $delivery_fee_reduce = $d_tmp['reduce_money'];
		}
		
		fdump($delivery_fee_reduce, 1, 1);
		$expect_use_time_temp = $expect_use_time ? $expect_use_time : (time() + $d_shop['send_time'] * 60);//默认的期望送达时间
		
		if (D('Deliver_set')->getDeliverTime($expect_use_time_temp, $start_time, $stop_time, 1)) {
		    //时间段一
		    $pass_distance = $distance > $deliverReturn['basic_distance'] ? floatval($distance - $deliverReturn['basic_distance']) : 0;
		    $deliverReturn['delivery_fee'] += round($pass_distance * $deliverReturn['per_km_price'], 2);
		    $deliverReturn['delivery_fee'] = $deliverReturn['delivery_fee'] - $delivery_fee_reduce;
		    $deliverReturn['delivery_fee'] = $deliverReturn['delivery_fee'] > 0 ? $deliverReturn['delivery_fee'] : 0;
		    $delivery_fee = $order_data['freight_charge'] = $deliverReturn['delivery_fee'];//运费
		} elseif (D('Deliver_set')->getDeliverTime($expect_use_time_temp, $start_time2, $stop_time2, 2)) {
		    //时间段二
		    $pass_distance = $distance > $deliverReturn['basic_distance2'] ? floatval($distance - $deliverReturn['basic_distance2']) : 0;
		    $deliverReturn['delivery_fee2'] += round($pass_distance * $deliverReturn['per_km_price2'], 2);
		    $deliverReturn['delivery_fee2'] = $deliverReturn['delivery_fee2'] - $delivery_fee_reduce;
		    $deliverReturn['delivery_fee2'] = $deliverReturn['delivery_fee2'] > 0 ? $deliverReturn['delivery_fee2'] : 0;
		    $delivery_fee = $order_data['freight_charge'] = $deliverReturn['delivery_fee2'];//运费
		} elseif (D('Deliver_set')->getDeliverTime($expect_use_time_temp, $start_time3, $stop_time3, 3)) {
		    //时间段三
		    $pass_distance = $distance > $deliverReturn['basic_distance3'] ? floatval($distance - $deliverReturn['basic_distance3']) : 0;
		    $deliverReturn['delivery_fee3'] += round($pass_distance * $deliverReturn['per_km_price3'], 2);
		    $deliverReturn['delivery_fee3'] = $deliverReturn['delivery_fee3'] - $delivery_fee_reduce;
		    $deliverReturn['delivery_fee3'] = $deliverReturn['delivery_fee3'] > 0 ? $deliverReturn['delivery_fee3'] : 0;
		    $delivery_fee = $order_data['freight_charge'] = $deliverReturn['delivery_fee3'];//运费
		} else {
		    $this->error_tips('您选择的时间不在配送时间段内！');
		}
		


		$data['status'] = 1;
		$condition['status'] = 0;
		$data['order_status'] = 1;
		$data['is_pick_in_store'] = $is_pick_in_store;
		$data['last_staff'] = $this->staff_session['name'];
		$data['last_time'] = time();
		$data['expect_use_time'] = $expect_use_time_temp;
		$data['last_staff'] = $this->staff_session['name'];
		if ($d_shop['deliver_type'] == 0 || $d_shop['deliver_type'] == 3) {
			$data['no_bill_money'] = $delivery_fee;
		}

		if ($database->where($condition)->save($data)) {
		    $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
		    if ($result['error_code']) {
		        D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
		        $this->error($result['msg']);
		        exit;
		    }

			$phones = explode(' ', $this->store['phone']);
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
			$this->success('已接单');
		} else {
			$this->error('接单失败');
		}
	}

	public function check_shop_goods_stock()
	{
		$now_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		if ($result = D('Shop_goods')->check_stock_list($this->store['store_id'], $now_shop['stock_type'])) {
			exit(json_encode(array('status' => 1)));
		} else {
			exit(json_encode(array('status' => 0)));
		}
	}

	public function shop_goods_stock()
	{
		$now_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		$this->assign('goods_list', D('Shop_goods')->check_stock_list($this->store['store_id'], $now_shop['stock_type']));
		$this->display();
	}
	
	public function goods_stock_export()
	{
	    set_time_limit(0);
	    
	    require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
	    $title = $this->store['name'] . '库存警报提醒';
	    $objExcel = new PHPExcel();
	    $objProps = $objExcel->getProperties();
	    // 设置文档基本属性
	    $objProps->setCreator($title);
	    $objProps->setTitle($title);
	    $objProps->setSubject($title);
	    $objProps->setDescription($title);
	    $objExcel->setActiveSheetIndex(0);
	    $objActSheet = $objExcel->getActiveSheet();
	    $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	    $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	    $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	    $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	    
	    $objActSheet->setCellValue('A1', '商品编号');
	    $objActSheet->setCellValue('B1', '商品名称');
	    $objActSheet->setCellValue('C1', '商品单价');
	    $objActSheet->setCellValue('D1', '剩余库存');
	    
	    $now_shop = D('Merchnat_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
	    $result_list = D('Shop_goods')->check_stock_list($this->store['store_id'], $now_shop['stock_type']);
	    if (!empty($result_list)) {
	        $index = 2;
	        foreach ($result_list as $value) {
	            $objActSheet->setCellValueExplicit('A' . $index, $value['number']);
	            $objActSheet->setCellValueExplicit('B' . $index, $value['name']);
	            $objActSheet->setCellValueExplicit('C' . $index, $value['price']);
	            $objActSheet->setCellValueExplicit('D' . $index, $value['stock_num']);
	            $index ++;
	        }
	    }
	    //输出
	    $objWriter = new PHPExcel_Writer_Excel5($objExcel);
	    ob_end_clean();
	    header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	    header("Content-Type:application/force-download");
	    header("Content-Type:application/vnd.ms-execl");
	    header("Content-Type:application/octet-stream");
	    header("Content-Type:application/download");
	    header('Content-Disposition:attachment;filename="' . $title . ':' . date("Y-m-d H:i:s") . '.xls"');
	    header("Content-Transfer-Encoding:binary");
	    $objWriter->save('php://output');
	    exit();
	}
	
	public function foodshop_goods_stock_export()
	{
	    set_time_limit(0);
	    
	    require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
	    $title = $this->store['name'] . '库存警报提醒';
	    $objExcel = new PHPExcel();
	    $objProps = $objExcel->getProperties();
	    // 设置文档基本属性
	    $objProps->setCreator($title);
	    $objProps->setTitle($title);
	    $objProps->setSubject($title);
	    $objProps->setDescription($title);
	    $objExcel->setActiveSheetIndex(0);
	    $objActSheet = $objExcel->getActiveSheet();
	    $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	    $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	    $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	    $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	    
	    $objActSheet->setCellValue('A1', '商品编号');
	    $objActSheet->setCellValue('B1', '商品名称');
	    $objActSheet->setCellValue('C1', '商品单价');
	    $objActSheet->setCellValue('D1', '剩余库存');
	    
	    $now_shop = D('Merchnat_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
	    $result_list= D('Foodshop_goods')->check_stock_list($this->store['store_id']);
	    //$result_list = D('Shop_goods')->check_stock_list($this->store['store_id'], $now_shop['stock_type']);
	    if (!empty($result_list)) {
	        $index = 2;
	        foreach ($result_list as $value) {
	            $objActSheet->setCellValueExplicit('A' . $index, $value['number']);
	            $objActSheet->setCellValueExplicit('B' . $index, $value['name']);
	            $objActSheet->setCellValueExplicit('C' . $index, $value['price']);
	            $objActSheet->setCellValueExplicit('D' . $index, $value['stock_num']);
	            $index ++;
	        }
	    }
	    //输出
	    $objWriter = new PHPExcel_Writer_Excel5($objExcel);
	    ob_end_clean();
	    header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	    header("Content-Type:application/force-download");
	    header("Content-Type:application/vnd.ms-execl");
	    header("Content-Type:application/octet-stream");
	    header("Content-Type:application/download");
	    header('Content-Disposition:attachment;filename="' . $title . ':' . date("Y-m-d H:i:s") . '.xls"');
	    header("Content-Transfer-Encoding:binary");
	    $objWriter->save('php://output');
	    exit();
	}


	public function report()
	{
		$this->assign('type', isset($_GET['type']) ? intval($_GET['type']) : 0);
		$this->display();
	}

	public function ajax_report()
	{
		$type = isset($_POST['type']) ? intval($_POST['type']) : 0;
		$day = isset($_POST['day']) ? intval($_POST['day']) : 0;
		$period = isset($_POST['period']) ? htmlspecialchars($_POST['period']) : '';
		$stime = $etime = 0;
		if ($day) {
		    $day --;
		    $stime = strtotime(date('Y-m-d', strtotime("-{$day} day")));
			$etime = time();
		}
		if ($period) {
			$time_array = explode('-', $period);
			$stime = strtotime($time_array[0]);
			$etime = strtotime($time_array[1]) + 86400;
		}
		$where = array('paid' => 1, 'store_id' => $this->store['store_id']);
		if ($stime && $etime) {
			$where['pay_time'] = array(array('gt', $stime), array('lt', $etime));
		}

		$store_pays = D('Store_pay')->field(true)->where(array('store_id' => $this->store['store_id']))->select();
		$offline_pay_type = array(0 => array('name' => '现金支付', 'id' => 0, 'store_id' => $this->store['store_id']));
		foreach ($store_pays as $pay) {
			$offline_pay_type[$pay['id']] = $pay;
		}
		if ($type == 0) {//餐饮
			$field = 'sum(total_money - wx_cheap) AS total_price, sum(total_money - system_balance - system_coupon_price - system_score_money - merchant_balance_pay - merchant_balance_give - merchant_discount_money - merchant_coupon_price - pay_money) AS offline_price, sum(pay_money) AS online_price, sum(system_balance + system_coupon_price + system_score_money + merchant_balance_pay + merchant_balance_give + merchant_discount_money + merchant_coupon_price) as balance, 0 as offline_pay';
			$where['business_type'] = 'foodshop';
			$order = D('Plat_order')->field($field)->where($where)->find();

			$field = 'sum(price) AS total_price, sum(price - balance_pay - card_price - payment_money - merchant_balance - card_give_money - score_deducte) AS offline_price, sum(payment_money) AS online_price, sum(balance_pay + card_price + merchant_balance + card_give_money + score_deducte) as balance, offline_pay';
			$where['business_type'] = 'foodshop';

			$order_list = D('Store_order')->field($field)->where($where)->group('offline_pay')->select();
			if (empty($order_list)) $order_list = array();
			if ($order) $order_list[] = $order;
		} elseif ($type == 1) {
// 		    $field = 'sum(price) AS total_price, sum(price - balance_pay - card_price - payment_money - merchant_balance - card_give_money - score_deducte-coupon_price) AS offline_price, sum(payment_money) AS online_price, sum(balance_pay + card_price + merchant_balance + card_give_money + score_deducte+coupon_price) as balance, offline_pay';
		    $field = 'price, card_discount, payment_money, (balance_pay + card_price + merchant_balance + card_give_money + score_deducte+coupon_price) as balance, offline_pay, pay_type';
		    $order_list = D('Store_order')->field($field)->where($where)->select();

		    $newList = array();
		    foreach ($order_list as $order) {
		        if (isset($newList[$order['offline_pay']]) && $newList[$order['offline_pay']]) {
		            $new = $newList[$order['offline_pay']];
		        } else {
		            $new = array('total_price' => 0, 'offline_price' => 0, 'online_price' => 0, 'offline_pay' => 0);
		        }

		        $new['total_price'] += $order['price'];
		        if ($order['pay_type'] == 'offline') {
		            if ($order['card_discount'] != 0) {
		                $new['offline_price'] += round($order['price'] * $order['card_discount'] * 0.1- $order['balance'] - $order['payment_money'], 2);
		            } else {
		                $new['offline_price'] += $order['price'] - $order['balance'] - $order['payment_money'];
		            }
		        }
		        $new['online_price'] += $order['payment_money'];
		        $new['offline_pay'] = $order['offline_pay'];
		        $newList[$order['offline_pay']] = $new;
		    }
		    $order_list = $newList;
		} else {
		    $where['order_from'] = 6;
		    $field = 'sum(price) AS total_price, sum(price - balance_pay - card_price - payment_money - merchant_balance - card_give_money - score_deducte) AS offline_price, sum(payment_money) AS online_price, sum(balance_pay + card_price + merchant_balance + card_give_money + score_deducte) as balance, offline_pay';
		    $order_list = D('Shop_order')->field($field)->where($where)->group('pay_type, offline_pay')->select();
		}
		$money_list = array();
		foreach ($order_list as $order) {

			if (isset($money_list['total_price'])) {
				$money_list['total_price']['money'] += floatval($order['total_price']);
			} else {
				$money_list['total_price'] = array('pay_type' => '支付总金额', 'money' => floatval($order['total_price']), 'report_export' => U('report_export', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => 'all')), 'report_detail' => U('report_detail', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => 'all')));
			}

			if (isset($money_list['online_price'])) {
				$money_list['online_price']['money'] += floatval($order['balance']) + floatval($order['online_price']);
			} else {
				$money_list['online_price'] = array('pay_type' => '在线支付', 'money' => floatval($order['balance']) + floatval($order['online_price']), 'report_export' => U('report_export', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => 'online')), 'report_detail' => U('report_detail', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => 'online')));
			}

			if (isset($money_list['offline_' + $order['offline_pay']])) {
				$money_list['offline_' . $order['offline_pay']]['money'] += floatval($order['offline_price']);
			} else {
			    if ($order['offline_pay'] == 0 && $order['offline_price'] > 0) {
			        $money_list['offline_' . $order['offline_pay']] = array('pay_type' => $offline_pay_type[$order['offline_pay']]['name'], 'money' => floatval($order['offline_price']), 'report_export' => U('report_export', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => $order['offline_pay'])), 'report_detail' => U('report_detail', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => $order['offline_pay'])));
			    } elseif ($order['offline_pay'] > 0) {
			        $money_list['offline_' . $order['offline_pay']] = array('pay_type' => $offline_pay_type[$order['offline_pay']]['name'], 'money' => floatval($order['offline_price']), 'report_export' => U('report_export', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => $order['offline_pay'])), 'report_detail' => U('report_detail', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => $order['offline_pay'])));
			    }
			}
		}
		exit(json_encode(array('error_code' => false, 'data' => $money_list, 'count' => count($money_list))));
	}

	public function report_detail()
	{
		$type = isset($_GET['type']) ? intval($_GET['type']) : 0;
		$stime = isset($_GET['stime']) ? intval($_GET['stime']) : 0;
		$etime = isset($_GET['etime']) ? intval($_GET['etime']) : 0;
		$pay_type = isset($_GET['pay_type']) ? htmlspecialchars($_GET['pay_type']) : 'all';

		$store_pays = D('Store_pay')->field(true)->where(array('store_id' => $this->store['store_id']))->select();
		$offline_pay_type = array(0 => array('name' => '现金支付', 'id' => 0, 'store_id' => $this->store['store_id']));
		foreach ($store_pays as $pay) {
			$offline_pay_type[$pay['id']] = $pay;
		}
		if ($pay_type == 'all') {
			$pay_type_title = '总的支付';
		} elseif ($pay_type == 'online') {
			$pay_type_title = '在线支付';
		} else {
			$pay_type_title = $offline_pay_type[$pay_type]['name'];
		}
		$mode = new Model();
		if ($type == 0) {
			$sql = "SELECT f.order_id, f.real_orderid, f.name as username, 0 as offline_pay, f.phone as userphone, (p.total_money - p.system_balance - p.system_coupon_price - p.system_score_money - p.merchant_balance_pay - p.merchant_balance_give - p.merchant_discount_money - p.merchant_coupon_price - p.pay_money-p.wx_cheap) AS offline_price, f.price, f.total_price, (p.pay_money+p.system_balance + p.system_coupon_price + p.system_score_money + p.merchant_balance_pay + p.merchant_balance_give + p.merchant_discount_money + p.merchant_coupon_price) as online_price, (f.total_price-f.price) as discount_price, p.pay_time, p.pay_type FROM " . C('DB_PREFIX') . "foodshop_order AS f INNER JOIN " . C('DB_PREFIX') . "plat_order AS p ON p.business_id=f.order_id WHERE p.paid=1 AND p.business_type='foodshop' AND p.pay_time>'{$stime}' AND p.pay_time<'{$etime}' AND p.store_id='{$this->store['store_id']}'";
			if ($pay_type != 'all' && $pay_type != 'online' && $pay_type == 0) {
				$sql .= " AND p.pay_type='offline'";
			} elseif ($pay_type == 'online') {
				$sql .= " AND (p.system_balance>0 OR p.system_coupon_price>0 OR p.system_score_money>0 OR p.merchant_balance_pay>0 OR p.merchant_balance_give>0 OR p.merchant_discount_money>0 OR p.merchant_coupon_price>0 OR p.pay_money>0)";
			} elseif ($pay_type > 0) {
			    $sql .= " AND false";
			}
			$temp_list = $mode->query($sql);
			foreach ($temp_list as $row) {
			    $row['discount_price'] = max(0, $row['discount_price']);
				if (isset($order_list[$row['order_id']])) {
					$order_list[$row['order_id']]['online_price'] += $row['online_price'];
					$order_list[$row['order_id']]['offline_price'] += $row['offline_price'];
// 					$order_list[$row['order_id']]['price'] += $row['price'];
// 					$order_list[$row['order_id']]['total_price'] += $row['total_price'];
// 					$order_list[$row['order_id']]['discount_price'] += $row['discount_price'];
				} else {
					$order_list[$row['order_id']] = $row;
				}
			}
			$sql = "SELECT f.order_id, f.real_orderid, f.name as username, s.offline_pay, f.phone as userphone, (s.price - s.balance_pay - s.card_price - s.payment_money - s.merchant_balance - s.card_give_money - s.score_deducte) AS offline_price, (s.balance_pay + s.card_price + s.payment_money + s.merchant_balance + s.card_give_money + s.score_deducte) AS online_price, f.total_price, f.price, (f.total_price-f.price) as discount_price, s.pay_time, s.pay_type FROM " . C('DB_PREFIX') . "foodshop_order AS f INNER JOIN " . C('DB_PREFIX') . "store_order AS s ON s.business_id=f.order_id WHERE s.paid=1 AND s.business_type='foodshop' AND s.pay_time>'{$stime}' AND s.pay_time<'{$etime}' AND s.store_id='{$this->store['store_id']}'";
			if ($pay_type != 'all' && $pay_type != 'online') {
				$sql .= " AND s.offline_pay='{$pay_type}'";
			} elseif ($pay_type == 'online') {
				$sql .= " AND (s.balance_pay>0 OR s.card_price OR s.payment_money>0 OR s.merchant_balance>0 OR s.card_give_money>0 OR s.score_deducte>0)";
			}
			$temp_list = $mode->query($sql);
			foreach ($temp_list as $row) {
			    $row['discount_price'] = max(0, $row['discount_price']);
				if (isset($order_list[$row['order_id']])) {
					$order_list[$row['order_id']]['online_price'] += $row['online_price'];
					$order_list[$row['order_id']]['offline_price'] += $row['offline_price'];
// 					$order_list[$row['order_id']]['price'] += $row['price'];
// 					$order_list[$row['order_id']]['total_price'] += $row['total_price'];
// 					$order_list[$row['order_id']]['discount_price'] += $row['discount_price'];
					$order_list[$row['order_id']]['offline_pay'] = $row['offline_pay'];
				} else {
					$order_list[$row['order_id']] = $row;
				}
			}
		} elseif ($type == 1) {
		    $where = "s.paid=1 AND s.store_id={$this->store['store_id']}";
		    if ($stime && $etime) {
		        $where .= " AND s.pay_time>'{$stime}' AND s.pay_time<'{$etime}'";
		    }
		    if ($pay_type != 'all' && $pay_type != 'online') {
		        $where .= " AND s.offline_pay='{$pay_type}'";
		    } elseif ($pay_type == 'online') {
		        $where .= " AND (s.balance_pay>0 OR s.card_price OR s.payment_money>0 OR s.merchant_balance>0 OR s.card_give_money>0 OR s.score_deducte>0)";
		    }
		    // (s.price - s.balance_pay - s.card_price - s.payment_money - s.merchant_balance - s.card_give_money - s.score_deducte - s.coupon_price) AS offline_price
		    $sql = "SELECT s.total_price, s.price, s.discount_price, s.card_discount, (s.balance_pay + s.card_price + s.payment_money + s.merchant_balance + s.card_give_money + s.score_deducte + s.coupon_price) AS online_price, u.nickname as username, u.phone as userphone, s.order_id as real_orderid, s.pay_time, s.pay_type, s.offline_pay FROM " . C('DB_PREFIX') . "store_order AS s LEFT JOIN " . C('DB_PREFIX') . "user as u ON s.uid=u.uid WHERE {$where}";
		    $order_list = $mode->query($sql);
		    foreach ($order_list as &$order) {
		        if ($order['card_discount'] != 0) {
		            $order['offline_price'] = $order['price'] * $order['card_discount'] * 0.1 - $order['online_price'];
		        } else {
		            $order['offline_price'] = $order['price'] - $order['online_price'];
		        }
		    }
		} else {
		    $where = "s.paid=1 AND s.store_id={$this->store['store_id']} AND s.order_from=6";
		    if ($stime && $etime) {
		        $where .= " AND s.pay_time>'{$stime}' AND s.pay_time<'{$etime}'";
		    }
		    if ($pay_type != 'all' && $pay_type != 'online') {
		        $where .= " AND s.pay_type='offline' AND s.offline_pay='{$pay_type}'";
		    } elseif ($pay_type == 'online') {
		        $where .= " AND (s.balance_pay>0 OR s.card_price OR s.payment_money>0 OR s.merchant_balance>0 OR s.card_give_money>0 OR s.score_deducte>0)";
		    }
		    $sql = "SELECT s.total_price, s.price, (s.total_price-s.price) as discount_price, (s.price - s.balance_pay - s.card_price - s.payment_money - s.merchant_balance - s.card_give_money - s.score_deducte) AS offline_price, (s.balance_pay + s.card_price + s.payment_money + s.merchant_balance + s.card_give_money + s.score_deducte) AS online_price, u.nickname as username, u.phone as userphone, s.order_id as real_orderid, s.pay_time, s.pay_type, s.offline_pay FROM " . C('DB_PREFIX') . "shop_order AS s LEFT JOIN " . C('DB_PREFIX') . "user as u ON s.uid=u.uid WHERE {$where}";
		    $order_list = $mode->query($sql);
		}
		$total_money = 0;
		foreach ($order_list as $index => &$order) {
			if ($pay_type == 'all') {
				$total_money += $order['price'];
			} elseif ($pay_type == 'online') {
				$total_money += $order['online_price'];
			} else {
				$total_money += $order['offline_price'];
			}
			if ($pay_type === 0) {
			    if (floatval($order['offline_price']) > 0) {
			        $order['pay_type'] = $offline_pay_type[$order['offline_pay']]['name'];
			    } else {
			        unset($order_list[$index]);
			    }
			} else {
			    if ($order['pay_type']) {
			        if ($order['pay_type'] == 'offline') {
			            if ($order['offline_pay']) {
			                $order['pay_type'] = $offline_pay_type[$order['offline_pay']]['name'];
			            } else {
			                $order['pay_type'] = '现金支付';
			            }
			        } else {
			            $order['pay_type'] = D('Pay')->get_pay_name($order['pay_type'], 0);
			        }
    			} elseif (floatval($order['offline_price']) > 0) {
    				$order['pay_type'] = $offline_pay_type[$order['offline_pay']]['name'];
    			} else {
    			    $order['pay_type'] = '余额支付';
    			}
			}
			$order['online_price'] = floatval($order['online_price']);
		}
		$this->assign(array('order_list' => $order_list, 'stime' => date('Y-m-d', $stime), 'etime' => date('Y-m-d', $etime), 'pay_type_title' => $pay_type_title, 'total_money' => $total_money, 'report_export' => U('report_export', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => $pay_type))));
		$this->display();
	}

	public function report_export()
	{
		set_time_limit(0);

		$type = isset($_GET['type']) ? intval($_GET['type']) : 0;
		$stime = isset($_GET['stime']) ? intval($_GET['stime']) : 0;
		$etime = isset($_GET['etime']) ? intval($_GET['etime']) : 0;
		$pay_type = isset($_GET['pay_type']) ? htmlspecialchars($_GET['pay_type']) : 'all';

		$store_pays = D('Store_pay')->field(true)->where(array('store_id' => $this->store['store_id']))->select();
		$offline_pay_type = array(0 => array('name' => '现金支付', 'id' => 0, 'store_id' => $this->store['store_id']));
		foreach ($store_pays as $pay) {
			$offline_pay_type[$pay['id']] = $pay;
		}
		if ($pay_type == 'all') {
			$pay_type_title = '总的支付';
		} elseif ($pay_type == 'online') {
			$pay_type_title = '在线支付';
		} else {
			$pay_type_title = $offline_pay_type[$pay_type]['name'];
		}
		$mode = new Model();
		if ($type == 0) {
		    $sql = "SELECT f.order_id, f.real_orderid, f.name as username, 0 as offline_pay, f.phone as userphone, (p.total_money - p.system_balance - p.system_coupon_price - p.system_score_money - p.merchant_balance_pay - p.merchant_balance_give - p.merchant_discount_money - p.merchant_coupon_price - p.pay_money) AS offline_price, p.total_money AS price, f.price AS total_price, (p.pay_money+p.system_balance + p.system_coupon_price + p.system_score_money + p.merchant_balance_pay + p.merchant_balance_give + p.merchant_discount_money + p.merchant_coupon_price) as online_price, (f.price-p.total_money) as discount_price, p.pay_time, p.pay_type FROM " . C('DB_PREFIX') . "foodshop_order AS f INNER JOIN " . C('DB_PREFIX') . "plat_order AS p ON p.business_id=f.order_id WHERE p.paid=1 AND p.business_type='foodshop' AND p.pay_time>'{$stime}' AND p.pay_time<'{$etime}' AND p.store_id='{$this->store['store_id']}'";
		    if ($pay_type != 'all' && $pay_type != 'online' && $pay_type == 0) {
		        $sql .= " AND p.pay_type='offline'";
		    } elseif ($pay_type == 'online') {
		        $sql .= " AND (p.system_balance>0 OR p.system_coupon_price>0 OR p.system_score_money>0 OR p.merchant_balance_pay>0 OR p.merchant_balance_give>0 OR p.merchant_discount_money>0 OR p.merchant_coupon_price>0 OR p.pay_money>0)";
		    } elseif ($pay_type > 0) {
		        $sql .= " AND false";
		    }
		    $temp_list = $mode->query($sql);
		    foreach ($temp_list as $row) {
		        $row['discount_price'] = max(0, $row['discount_price']);
		        if (isset($order_list[$row['order_id']])) {
		            $order_list[$row['order_id']]['online_price'] += $row['online_price'];
		            $order_list[$row['order_id']]['offline_price'] += $row['offline_price'];
		            $order_list[$row['order_id']]['price'] += $row['price'];
		            $order_list[$row['order_id']]['total_price'] += $row['total_price'];
		            $order_list[$row['order_id']]['discount_price'] += $row['discount_price'];
		        } else {
		            $order_list[$row['order_id']] = $row;
		        }
		    }
			$sql = "SELECT f.order_id, f.real_orderid, f.name as username, s.offline_pay, f.phone as userphone, (s.price - s.balance_pay - s.card_price - s.payment_money - s.merchant_balance - s.card_give_money - s.score_deducte) AS offline_price, (s.balance_pay + s.card_price + s.payment_money + s.merchant_balance + s.card_give_money + s.score_deducte) AS online_price, s.total_price, s.price, (s.total_price-s.price) as discount_price, s.pay_time, s.pay_type FROM " . C('DB_PREFIX') . "foodshop_order AS f INNER JOIN " . C('DB_PREFIX') . "store_order AS s ON s.business_id=f.order_id WHERE s.paid=1 AND s.business_type='foodshop' AND s.pay_time>'{$stime}' AND s.pay_time<'{$etime}' AND s.store_id='{$this->store['store_id']}'";
		    if ($pay_type != 'all' && $pay_type != 'online') {
		        $sql .= " AND s.offline_pay='{$pay_type}'";
		    } elseif ($pay_type == 'online') {
		        $sql .= " AND (s.balance_pay>0 OR s.card_price OR s.payment_money>0 OR s.merchant_balance>0 OR s.card_give_money>0 OR s.score_deducte>0)";
		    }
		    $temp_list = $mode->query($sql);
		    foreach ($temp_list as $row) {
		        $row['discount_price'] = max(0, $row['discount_price']);
		        if (isset($order_list[$row['order_id']])) {
		            $order_list[$row['order_id']]['online_price'] += $row['online_price'];
		            $order_list[$row['order_id']]['offline_price'] += $row['offline_price'];
		            $order_list[$row['order_id']]['price'] += $row['price'];
		            $order_list[$row['order_id']]['total_price'] += $row['total_price'];
		            $order_list[$row['order_id']]['discount_price'] += $row['discount_price'];
		            $order_list[$row['order_id']]['offline_pay'] = $row['offline_pay'];
		        } else {
		            $order_list[$row['order_id']] = $row;
		        }
		    }
		} elseif ($type == 1) {
		    $where = "s.paid=1 AND s.store_id={$this->store['store_id']}";
		    if ($stime && $etime) {
		        $where .= " AND s.pay_time>'{$stime}' AND s.pay_time<'{$etime}'";
		    }
		    if ($pay_type != 'all' && $pay_type != 'online') {
		        $where .= " AND s.offline_pay='{$pay_type}'";
		    } elseif ($pay_type == 'online') {
		        $where .= " AND (s.balance_pay>0 OR s.card_price OR s.payment_money>0 OR s.merchant_balance>0 OR s.card_give_money>0 OR s.score_deducte>0)";
		    }
		    $sql = "SELECT s.total_price, s.price, s.discount_price, (s.price - s.balance_pay - s.card_price - s.payment_money - s.merchant_balance - s.card_give_money - s.score_deducte) AS offline_price, (s.balance_pay + s.card_price + s.payment_money + s.merchant_balance + s.card_give_money + s.score_deducte) AS online_price, u.nickname as username, u.phone as userphone, s.order_id as real_orderid, s.pay_time, s.pay_type, s.offline_pay FROM " . C('DB_PREFIX') . "store_order AS s LEFT JOIN " . C('DB_PREFIX') . "user as u ON s.uid=u.uid WHERE {$where}";
		    $order_list = $mode->query($sql);
		} else {
		    $where = "s.paid=1 AND s.store_id={$this->store['store_id']} AND s.order_from=6";
		    if ($stime && $etime) {
		        $where .= " AND s.pay_time>'{$stime}' AND s.pay_time<'{$etime}'";
		    }
		    if ($pay_type != 'all' && $pay_type != 'online') {
		        $where .= " AND s.offline_pay='{$pay_type}'";
		    } elseif ($pay_type == 'online') {
		        $where .= " AND (s.balance_pay>0 OR s.card_price OR s.payment_money>0 OR s.merchant_balance>0 OR s.card_give_money>0 OR s.score_deducte>0)";
		    }
		    $sql = "SELECT s.total_price, s.price, (s.total_price-s.price) as discount_price, (s.price - s.balance_pay - s.card_price - s.payment_money - s.merchant_balance - s.card_give_money - s.score_deducte) AS offline_price, (s.balance_pay + s.card_price + s.payment_money + s.merchant_balance + s.card_give_money + s.score_deducte) AS online_price, u.nickname as username, u.phone as userphone, s.order_id as real_orderid, s.pay_time, s.pay_type, s.offline_pay FROM " . C('DB_PREFIX') . "shop_order AS s LEFT JOIN " . C('DB_PREFIX') . "user as u ON s.uid=u.uid WHERE {$where}";
		    $order_list = $mode->query($sql);
		}
		$total_money = 0;
		foreach ($order_list as &$order) {
			if ($pay_type == 'all') {
				$total_money += $order['price'];
			} elseif ($pay_type == 'online') {
				$total_money += $order['online_price'];
			} else {
				$total_money += $order['offline_price'];
			}
			if ($order['pay_type']) {
			    if ($order['pay_type'] == 'offline') {
			        if ($order['offline_pay']) {
			            $order['pay_type'] = $offline_pay_type[$order['offline_pay']]['name'];
			        } else {
			            $order['pay_type'] = '现金支付';
			        }
			    } else {
			        $order['pay_type'] = D('Pay')->get_pay_name($order['pay_type'], 0);
			    }
			} else {
				$order['pay_type'] = $offline_pay_type[$order['offline_pay']]['name'];
			}
			$order['online_price'] = floatval($order['online_price']);
		}

		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = $this->store['name'] . $pay_type_title . '统计报表';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();
		$objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

		$objActSheet->setCellValue('A1', '订单号');
		$objActSheet->setCellValue('B1', '用户姓名');
		$objActSheet->setCellValue('C1', '用户电话');
		$objActSheet->setCellValue('D1', '订单总价');
		$objActSheet->setCellValue('E1', '优惠金额');
		$objActSheet->setCellValue('F1', '实付金额');
		$objActSheet->setCellValue('G1', '在线支付金额');
		$objActSheet->setCellValue('H1', '线下支付金额');
		$objActSheet->setCellValue('I1', '支付时间');
		$objActSheet->setCellValue('J1', '支付类型');
		if (!empty($order_list)) {
			$index = 2;
			foreach ($order_list as $value) {
				$objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
				$objActSheet->setCellValueExplicit('B' . $index, $value['username']);
				$objActSheet->setCellValueExplicit('C' . $index, $value['userphone']);
				$objActSheet->setCellValueExplicit('D' . $index, $value['total_price']);
				$objActSheet->setCellValueExplicit('E' . $index, $value['discount_price']);
				$objActSheet->setCellValueExplicit('F' . $index, $value['price']);
				$objActSheet->setCellValueExplicit('G' . $index, $value['online_price']);
				$objActSheet->setCellValueExplicit('H' . $index, $value['offline_price']);
				$objActSheet->setCellValueExplicit('I' . $index, date('Y-m-d H:i:s', $value['pay_time']));
				$objActSheet->setCellValueExplicit('J' . $index, $value['pay_type']);
				$index ++;
			}
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="' . $title . ':' . date("Y-m-d H:i:s") . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}

	public function market()
	{
		$this->assign(array('name' => $this->staff_session['name'], 'date' => date('Y-m-d')));
		$offline_pay_list = M('Store_pay')->where(array('store_id' => $this->store['store_id']))->order('`id` ASC')->select();
		$this->assign('offline_pay_list', $offline_pay_list);
		$this->assign('store_id', $this->store['store_id']);
		$this->assign('is_change', $this->staff_session['is_change']);
		$this->display();
	}

	public function ajax_shop_goods()
	{
		$is_refresh = isset($_POST['refresh']) ? intval($_POST['refresh']) : 1;
		$product_list = D('Shop_goods')->get_list($this->store['store_id'], $is_refresh);
		if ($product_list) {
			exit(json_encode(array('err_code' => false, 'data' => $product_list)));
		} else {
			exit(json_encode(array('err_code' => true, 'data' => '暂无商品')));
		}
	}

	/**
	 * 获取会员卡信息
	 */
	public function ajax_card()
	{
		$key = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
		if(strlen($key) == 11 && $user = D('User')->field(true)->where(array('phone' => $key))->find()) {
			$card = D('Card_userlist')->field(true)->where(array('uid' => $user['uid'], 'mer_id' => $this->store['mer_id']))->find();
			exit(json_encode($this->format_ajax_card($card)));
		}else{

			$condition_user['mer_id'] = $this->store['mer_id'];
			$condition_user['_string'] = "id={$key} OR wx_card_code={$key} OR physical_id = {$key}";
			$card = D('Card_userlist')->field(true)->where($condition_user)->find();

			if(empty($card) &&($user = D('User')->field(true)->where(array('truename' =>  $key))->find() || $user = D('User')->field(true)->where(array('nickname' =>  array('like','%'.$key.'%')))->find()) ){
				$card = D('Card_userlist')->field(true)->where(array('uid' => $user['uid'], 'mer_id' => $this->store['mer_id']))->find();
			}
			exit(json_encode($this->format_ajax_card($card)));
		}
	}

	public function format_ajax_card($card){
		if(empty($card)){
			return array('err_code' => true, 'data' => '没有会员卡信息');
		}

		$card_source = M('Card_new')->where(array('mer_id' => $this->store['mer_id'], 'status' => 1))->find();
		if (empty($card_source)) {
			return array('err_code' => true, 'data' => '没有会员卡信息');
		}
		if ($user = D('User')->field(true)->where(array('uid' => $card['uid']))->find()) {
			$return = array('name' => $user['truename'] ? $user['truename'] : $user['nickname'], 'sex' => $user['sex'] == 1 ? '男' : '女', 'card_id' => $card['id'], 'phone' => $user['phone']);
			$return['card_money'] = $card['card_money'] + $card['card_money_give'];
			$return['card_score'] = $card['card_score'];
			$return['physical_id'] = $card['physical_id'];
			$return['uid'] = $user['uid'];
			$return['discount'] = $card_source['discount'];

			$return['card_new'] = D('Card_new')->get_use_coupon_by_params($user['uid'], $this->store['mer_id'], 'shop');
			return array('err_code' => false, 'data' => $return);
		} else {
			return array('err_code' => true, 'data' => '此卡找不到相应的用户信息');
		}
	}

	public function shop_order_save()
	{
		//order_from = 6;
		$data = isset($_POST['data']) ? ($_POST['data']) : '';
		$uid = isset($data['card_data']['uid']) && $data['card_data']['uid'] ? intval($data['card_data']['uid']) : 0;
		$store_id = $this->store['store_id'];
		$return = D('Shop_goods')->checkCart($store_id, $uid, $data['goods_data'], 2, 0, true);
		if ($return['error_code']) exit(json_encode($return));
		if (IS_POST) {

			if (!($user = D('User')->field(true)->where(array('uid' => $uid))->find())) {
				$uid = 0;
			}

			D('Shop_order')->where(array('staff_id' => $this->staff_session['id'], 'order_from' => 6, 'paid' => 0, 'is_del' => 0))->save(array('is_del' => 1));

			$now_time = time();
			$order_data = array();
			$order_data['mer_id'] = $return['mer_id'];
			$order_data['store_id'] = $return['store_id'];
			$order_data['uid'] = $uid;//TODO
			$order_data['staff_id'] = $this->staff_session['id'];
			$order_data['last_staff'] = $this->staff_session['name'];

			$order_data['desc'] = '';
			$order_data['create_time'] = $now_time;
			$order_data['last_time'] = $now_time;
			$order_data['invoice_head'] = '';
			$order_data['village_id'] = 0;

			$order_data['num'] = $return['total'];
			$order_data['packing_charge'] = $return['packing_charge'];//打包费
			$order_data['merchant_reduce'] = $return['sto_first_reduce'] + $return['sto_full_reduce'];//店铺优惠
			$order_data['balance_reduce'] = $return['sys_first_reduce'] + $return['sys_full_reduce'];//平台优惠
			$orderid  = date('ymdhis').substr(microtime(), 2, 8 - strlen($uid)). $uid;
			$order_data['real_orderid'] = $orderid;
			$order_data['no_bill_money'] = 0;//无需跟平台对账的金额

			$order_data['is_pick_in_store'] = 2;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
			$delivery_fee = $order_data['freight_charge'] = 0;//运费
			$order_data['username'] = isset($user['nickname']) && $user['nickname'] ? $user['nickname'] : '';
			$order_data['userphone'] = isset($user['phone']) && $user['phone'] ? $user['phone'] : '';
			$order_data['address'] = '';
			$order_data['address_id'] = 0;
			$order_data['pick_id'] = 0;
			$order_data['status'] = 0;
			$order_data['order_from'] = 6;
			$order_data['expect_use_time'] = 0;//客户期望使用时间


			$order_data['goods_price'] = $return['price'];//商品的价格
			$order_data['extra_price'] = $return['extra_price'];//另外要支付的金额
			$order_data['discount_price'] = $return['vip_discount_money'];//商品折扣后的总价
			$order_data['total_price'] = $return['price'] + $delivery_fee + $return['packing_charge'];//订单总价  商品价格+打包费+配送费
			$order_data['price'] = $order_data['discount_price'] + $delivery_fee + $return['packing_charge'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'];//实际要支付的价格
			$order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情

			$discountMsg = '';
			foreach($return['discount_list'] as $row) {
			    switch ($row['discount_type']) {
			        case 1:
			            $discountMsg .= '平台首单优惠,';
			            break;
			        case 2:
			            $discountMsg .= '平台满减优惠,';
			            break;
			        case 3:
			            $discountMsg .= '店铺首单优惠,';
			            break;
			        case 4:
			            $discountMsg .= '店铺满减优惠,';
			            break;
			    }
			}

			foreach ($return['goods'] as $goods) {
			    switch ($goods['discount_type']) {
			        case 1:
			            $discountMsg .= '店铺折扣优惠,';
			            break;
			        case 2:
			            $discountMsg .= '分类折扣优惠,';
			            break;
			    }
			}
			if($return['packing_charge']>0){
			    $discountMsg = trim($discountMsg,',');
			    if($discountMsg!=''){
                    $discountMsg .= '之后且含打包费的价格';
                }else{
                    $discountMsg .= '含打包费的价格';
                }
            }else{
                $discountMsg = trim($discountMsg,',');
                if($discountMsg!='') {
                    $discountMsg .= '之后的价格';
                }
            }
// 			if ($return['price'] - $return['store_discount_money'] > 0) {
// 				$order_data['discount_detail'] = '店铺折扣优惠：' . floatval($return['price'] - $return['store_discount_money']);
// 			}
// 			if ($return['store_discount_money'] - $return['vip_discount_money'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']) : 'VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']);
// 			}
// 			if ($return['sys_first_reduce']> 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台首单减：' . $return['sys_first_reduce'] : '平台首单减：' . $return['sys_first_reduce'];
// 			}
// 			if ($return['sys_full_reduce'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台满减：' . $return['sys_full_reduce'] : '平台满减：' . $return['sys_full_reduce'];
// 			}
// 			if ($return['sto_first_reduce']> 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺首单减：' . $return['sto_first_reduce'] : '店铺首单减：' . $return['sto_first_reduce'];
// 			}
// 			if ($return['sto_full_reduce'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺满减：' . $return['sto_full_reduce'] : '店铺满减：' . $return['sto_full_reduce'];
// 			}

			$order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'

			if ($order_id = D('Shop_order')->add($order_data)) {
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 0));
// 				if ($order_data['is_pick_in_store'] == 2 && $order_data['status'] == 7) {
// 					D('Pick_order')->add(array('store_id' => $order_data['store_id'], 'order_id' => $order_id, 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
// 					//D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//分配到自提点
// 				}
				$detail_obj = D('Shop_order_detail');
				$goods_obj = D("Shop_goods");
				foreach ($return['goods'] as $grow) {
					$detail_data = array('store_id' => $return['store_id'], 'order_id' => $order_id, 'number' => isset($grow['number']) && $grow['number'] ? $grow['number'] : '', 'cost_price' => $grow['cost_price'], 'unit' => $grow['unit'], 'goods_id' => $grow['goods_id'], 'name' => $grow['name'], 'price' => $grow['price'], 'num' => $grow['num'], 'spec' => $grow['str'], 'spec_id' => $grow['spec_id'], 'create_time' => time(),'extra_price'=>$grow['extra_price'] ? $grow['extra_price'] : 0);
					$detail_data['is_seckill'] = intval($grow['is_seckill_price']);
					$detail_data['discount_type'] = intval($grow['discount_type']);
					$detail_data['discount_rate'] = $grow['discount_rate'];
					$detail_data['sort_id'] = $grow['sort_id'];
					$detail_data['old_price'] = floatval($grow['old_price']);
					$detail_data['discount_price'] = floatval($grow['discount_price']);
					D('Shop_order_detail')->add($detail_data);
					$order_data['reduce_stock_type'] && $goods_obj->update_stock($grow);//修改库存
				}
				if ($user['openid']) {
					$keyword2 = '';
					$pre = '';
					foreach ($return['goods'] as $menu) {
						$keyword2 .= $pre . $menu['name'] . ':' . $menu['price'] . '*' . $menu['num'];
						$pre = '\n\t\t\t';
					}
					$href = C('config.site_url').'/wap.php?c=Shop&a=status&order_id='. $order_id . '&mer_id=' . $order_data['mer_id'] . '&store_id=' . $order_data['store_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $user['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $orderid, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $keyword2, 'remark' => '您的该次'.$this->config['shop_alias_name'].'下单成功，感谢您的使用！'), $this->store['mer_id']);
				}


				$printHaddle = new PrintHaddle();
				$printHaddle->printit($order_id, 'shop_order', 0);

// 				$msg = ArrayToStr::array_to_str($order_id, 'shop_order');
// 				$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
// 				$op->printit($return['mer_id'], $return['store_id'], $msg, 0);

// 				$str_format = ArrayToStr::print_format($order_id, 'shop_order');
// 				foreach ($str_format as $print_id => $print_msg) {
// 					$print_id && $op->printit($return['mer_id'], $return['store_id'], $print_msg, 0, $print_id);
// 				}


				$sms_data = array('mer_id' => $return['mer_id'], 'store_id' => $return['store_id'], 'type' => 'shop');
				if ($this->config['sms_shop_place_order'] == 1 || $this->config['sms_shop_place_order'] == 3) {
					$sms_data['uid'] = $user['uid'];
					$sms_data['mobile'] = $order_data['userphone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您' . date("H时i分") . '在【' . $return['store']['name'] . '】中下了一个订单，订单号：' . $orderid;
					Sms::sendSms($sms_data);
				}
				if ($this->config['sms_shop_place_order'] == 2 || $this->config['sms_shop_place_order'] == 3) {
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $return['store']['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客【' . $order_data['username'] . '】在' . date("Y-m-d H:i:s") . '时下了一个订单，订单号：' . $orderid . '请您注意查看并处理!';
					Sms::sendSms($sms_data);
				}

				if($this->config['cash_pay_qrcode']){
					$pay_qrcode_url = $this->config['site_url'] . '/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id=' . (3700000000 + $order_id);
				}else{
					$pay_qrcode_url = $this->config['site_url'] . '/index.php?c=Recognition&a=get_own_qrcode&qrCon=' . urlencode($this->config['site_url'].'/wap.php?c=My&a=shop_order_before&order_id='.$order_id);
				}

				exit(json_encode(array('error_code' => false, 'real_orderid' => $order_data['real_orderid'], 'order_id' => $order_id, 'price' => $order_data['price'], 'pay_qrcode_url' => $pay_qrcode_url, 'discount_msg' => trim($discountMsg, ','))));
			} else {
				exit(json_encode(array('error_code' => true, 'msg' => '订单保存失败')));
			}
		} else {
			exit(json_encode(array('error_code' => true, 'msg' => '不合法的提交')));
		}
	}


	private function get_reduce($discounts, $type, $price, $store_id = 0)
	{
		$reduce_money = 0;
		$return = null;
		if (isset($discounts[$store_id])) {
			foreach ($discounts[$store_id] as $row) {
				if ($row['type'] == $type) {
					if ($price >= $row['full_money']) {
						if ($reduce_money < $row['reduce_money']) {
							$reduce_money = $row['reduce_money'];
							$return = $row;
						}
					}
				}
			}
		}
		return $return;
	}

	public function shop_arrival_check()
	{
		$now_order = M('Shop_order')->where(array('order_id'=>$_POST['order_id']))->find();
		if ($now_order['paid']) {
			$this->success('支付成功！');
		} else {
			$this->error('还未支付');
		}
	}

	public function arrival_pay()
	{
		$table = isset($_POST['table']) ? htmlspecialchars($_POST['table']) : 'shop';
		$change_price_reason = isset($_POST['change_reason']) ? htmlspecialchars($_POST['change_reason']) : '';
// 		$discount = isset($_POST['discount']) ? intval($_POST['discount']) : 10;
		$coupon = isset($_POST['coupon']) ? intval($_POST['coupon']) : 0;
		$uid = isset($_POST['uid']) ? intval($_POST['uid']) : 0;
		$card_id = isset($_POST['card_id']) ? intval($_POST['card_id']) : 0;
		$card_money = isset($_POST['card_money']) ? floatval($_POST['card_money']) : 0;
		$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
		$table = strtolower($table);
		$table_name = ucfirst($table) . '_order';
		$order_id  = intval($_POST['order_id']);
		$now_order = M($table_name)->where(array('order_id' => $order_id))->find();

		if (empty($now_order)) $this->error('订单信息错误');

		//检查会员卡的信息已经余额
		$discount = 10;
		$data['merchant_balance'] = 0;//商家余额
		$data['card_give_money'] = 0;//会员卡赠送余额
		$data['card_id'] = 0;//优惠券ID
		$data['card_price'] = 0;//优惠券的金额
		$coupon_price = 0;
		if ($card = D('Card_userlist')->field(true)->where(array('id' => $card_id, 'uid' => $uid, 'mer_id' => $this->store['mer_id']))->find()) {
			$card_source = M('Card_new')->where(array('mer_id' => $this->store['mer_id'], 'status' => 1))->find();
			if (empty($card_source)) $this->error('会员卡不可用');
			$discount = floatval($card_source['discount']);
			$user_card_total_moeny = $card['card_money'] + $card['card_money_give'];
			if ($user_card_total_moeny < $card_money) {
				$this->error('会员卡余额不足');
			}
			if ($card_money <= $card['card_money']) {
				$data['merchant_balance'] = $card_money;
				$data['card_give_money'] = 0;
			} else {
				$data['merchant_balance'] = $card['card_money'];
				$data['card_give_money'] = $card_money - $card['card_money'];
			}

			$discount_price = floatval(round($now_order['price'] * $discount * 0.1, 2));
			$data['price'] = $discount_price;
			if ($user_coupon = M('Card_new_coupon_hadpull')->field(true)->where(array('id' => $coupon, 'is_use' => 0))->find()) {
				if ($new_coupon = M('card_new_coupon')->field(true)->where(array('coupon_id' => $user_coupon['coupon_id'], 'use_with_card' => 1))->find()) {
					$now_time = time();
					if (($new_coupon['cate_name'] == 'all' || $new_coupon['cate_name'] == 'shop') && $new_coupon['end_time'] > $now_time && $discount_price >= $new_coupon['order_money']) {
						$data['card_id'] = $coupon;
						$coupon_price = $data['card_price'] = $new_coupon['discount'];
					}
				}
			}
			$discount = floatval($card_source['discount']);
		} else {
			$card_money = 0;
			$discount = 10;
		}
		if ($this->staff_session['is_change']) {
			$true_price = floatval(round(floatval(round($now_order['price'] * $discount * 0.1, 2)) - $coupon_price, 2));//订单会员卡折扣和优惠券后的钱
			if ($true_price != $price) {
				$data['change_price'] = floatval(round($now_order['price'] * $discount * 0.1, 2));
				$data['price'] = $price;
				$data['change_price_reason'] = $change_price_reason;//修改价格的理由
			} else {
				$price = $true_price;
			}
			$now_order['price'] = floatval(round($price - $card_money, 2));
		} else {
			$now_order['price'] = floatval(round(floatval(round($now_order['price'] * $discount * 0.1, 2)) - $card_money - $coupon_price, 2));
		}
		$data['card_discount'] = $discount;
		$data['last_staff'] = $this->staff_session['name'];
		if ($now_order['price'] > 0) {
			$offline_pay = isset($_POST['offline_pay']) ? intval($_POST['offline_pay']) : -1;
			if($offline_pay >= 0){
				$this->arrival_offline_pay($order_id, $now_order, $table_name, $data);
				die;
			}
			if($_POST['auth_type'] == 'alipay'){
				$this->arrival_alipay_pay($order_id, $now_order, $table_name, $data);
				die;
			}

			$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
			$sub_mch_pay = false;
			$is_own = 0;

			if($this->config['open_sub_mchid']){
				if($this->store['open_sub_mchid'] && $this->store['sub_mch_id']>0){
					$this->config['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
					$this->config['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
					$sub_mch_pay = true;
					$sub_mch_id = $this->store['sub_mch_id'];
					$is_own = 3 ;
				}else if ( $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
					$this->config['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
					$this->config['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
					$sub_mch_pay = true;
					$sub_mch_id = $now_merchant['sub_mch_id'];
					$is_own = 2 ;
				}
			}


			import('ORG.Net.Http');
			$http = new Http();
			$session_key = $table . '_order_userpaying_'.$order_id;
			if($_SESSION[$session_key]){
				$param = array();
				$param['appid'] = $this->config['pay_weixin_appid'];
				$param['mch_id'] = $this->config['pay_weixin_mchid'];
				$param['nonce_str'] = $this->createNoncestr();
				$param['out_trade_no'] = $table . '_' . $now_order['order_id'];
				if($sub_mch_pay){
					$param['out_trade_no'] = 'store_'.$now_order['order_id'].'_1';
					$param['sub_mch_id'] = $sub_mch_id;
				}
				$param['sign'] = $this->getWxSign($param);
				$return = Http::curlPostXml('https://api.mch.weixin.qq.com/pay/orderquery', $this->arrayToXml($param));
			} else {
				$param = array();
				$param['appid'] = $this->config['pay_weixin_appid'];
				$param['mch_id'] = $this->config['pay_weixin_mchid'];
				$param['nonce_str'] = $this->createNoncestr();
				$param['body'] = $now_order['real_orderid'];
				$param['out_trade_no'] = $table . '_' . $now_order['order_id'];
				$param['total_fee'] = floatval($now_order['price']*100);
				$param['spbill_create_ip'] = get_client_ip();
				$param['auth_code'] = $_POST['auth_code'];
				if($sub_mch_pay){
					$param['out_trade_no'] = 'store_'.$now_order['order_id'].'_1';
					$param['sub_mch_id'] = $sub_mch_id;
				}
				$param['sign'] = $this->getWxSign($param);

				$return = Http::curlPostXml('https://api.mch.weixin.qq.com/pay/micropay', $this->arrayToXml($param));
			}
			if ($return['return_code'] == 'FAIL') {
			    $this->error('支付失败！微信返回：'. $return['return_msg']);
			}
			if ($return['result_code'] == 'FAIL') {
				if ($return['err_code'] == 'USERPAYING') {
					$_SESSION[$session_key] = '1';
					$this->error('用户支付中，需要输入密码！请询问用户输入完成后，再点击“确认支付”', '', array('errcode' => 'USERPAYING'));
				}
				$this->error('支付失败！微信返回：'.$return['err_code_des']);
			} elseif (isset($return['trade_state']) && $return['trade_state'] == 'USERPAYING') {
			    $this->error('用户支付中，需要输入密码！请询问用户输入完成后，再点击“确认支付”', '', array('errcode' => 'USERPAYING'));
			}

			$data['third_id'] = $return['transaction_id'];
			$data['payment_money'] = $return['total_fee']/100;
			$data['pay_type'] = 'weixin';

			unset($_SESSION[$session_key]);
			$now_user = D('User')->get_user($return['openid'],'openid');
			if(empty($now_user)){
				$access_token_array = D('Access_token_expires')->get_access_token();
				if (!$access_token_array['errcode']) {
					$result_user = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_array['access_token'].'&openid='.$return['openid'].'&lang=zh_CN');
					$userinfo = json_decode($result_user,true);
					if(empty($userinfo['errcode'])) {
						//商家推广绑定关系
						D('Merchant_spread')->spread_add($now_order['mer_id'], $userinfo['openid'], 'storepay');
						$data_user = array(
								'openid' => $userinfo['openid'],
								'union_id' => ($userinfo['unionid'] ? $userinfo['unionid'] : ''),
								'nickname' => $userinfo['nickname'],
								'sex' => $userinfo['sex'],
								'province' => $userinfo['province'],
								'city' => $userinfo['city'],
								'avatar' => $userinfo['headimgurl'],
								'is_follow' => $userinfo['subscribe'],
								'source' => 'pc_staff_pay_auto3',
						);
						$reg_result = D('User')->autoreg($data_user);
						if ($reg_result['error_code']) {
							$now_user['uid'] = '0';
						} else {
							$now_user = D('User')->get_user($userinfo['openid'], 'openid');
							$uid = $now_user['uid'];
						}
					}
				}
			} else {
				$uid = $now_user['uid'];
			}
		}


		$data['uid'] = $uid;
		$data['paid'] = 1;
		$data['status'] = 2;
		$data['order_status'] = 5;
		$data['from_plat'] = 3;
		$data['pay_time'] = time();
		$data['use_time'] = time();

		if(M($table_name)->where(array('order_id' => $order_id))->data($data)->save()){
			$now_order = M($table_name)->where(array('order_id'=>$order_id))->find();
			$this->shop_notice($now_order, true);
			$this->success('支付成功！');
		}else{
			$this->error('支付失败！请联系管理员处理。');
		}
	}
	public function arrival_offline_pay($order_id, $now_order, $table_name, $data = array())
	{
		$data['paid'] = 1;
		$data['status'] = 2;
		$data['order_status'] = 5;
		$data['pay_time'] = $_SERVER['REQUEST_TIME'];
		$data['use_time'] = time();
		$data['pay_type'] = 'offline';
		$data['offline_pay'] = $_POST['offline_pay'];
		$data['third_id'] = $order_id;
		if(M($table_name)->where(array('order_id' => $order_id))->data($data)->save()){
			$now_order = M($table_name)->where(array('order_id' => $order_id))->find();
			$this->shop_notice($now_order, true);
			$this->success('支付成功！');
		}else{
			$this->error('支付失败！请联系管理员处理。');
		}
	}

	public function alipay_auth_code(){
		import('@.ORG.pay.Aop.AopClient');
		import('@.ORG.pay.Aop.SignData');
		import('@.ORG.pay.Aop.AlipayTradePayRequest');
		import('@.ORG.pay.Aop.AlipayOpenAuthTokenAppRequest');
		$aop = new AopClient ();
		$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
		$aop->appId = $this->config['pay_alipay_sp_appid'];
		$aop->rsaPrivateKey = $this->config['pay_alipay_sp_prikey'];
		$aop->alipayrsaPublicKey=$this->config['pay_alipay_sp_pubkey'];
		$aop->apiVersion = '1.0';
		$aop->signType = 'RSA2';
		$aop->postCharset='utf-8';
		$aop->format='json';
		$request = new AlipayOpenAuthTokenAppRequest();
		$request->setBizContent;
		$now_merchant = D('Merchant')->get_info($this->store['mer_id']);

		$biz_content = array(
			'grant_type' => 'authorization_code',
			'code' => $now_merchant['alipay_auth_code'],
		);
		$request->setBizContent(json_encode($biz_content,JSON_UNESCAPED_UNICODE));
		$result = $aop->execute ( $request);
		return $result;
	}

	public function arrival_alipay_pay($order_id, $now_order, $table_name, $data = array())
	{
		$now_merchant = D('Merchant')->get_info($this->store['mer_id']);
		if($now_merchant['open_sub_mchid']==1 && $this->config['pay_alipay_sp_appid']!=''&& $this->config['pay_alipay_sp_prikey']!='' && $now_merchant['alipay_auth_code']!=''){
			$this->arrival_alipay_face_to_face($order_id,$now_order,$now_merchant['alipay_auth_code']);
			die;
		}
		if (empty($this->config['arrival_alipay_open'])) {
			$this->error('平台未开启支付宝收银');
		}
		$param['app_id'] = $this->config['arrival_alipay_app_id'];
		$param['method'] = 'alipay.trade.pay';
		$param['charset'] = 'utf-8';
		$param['sign_type'] = $this->config['alipay_arrival_encrypt_type'] ? $this->config['alipay_arrival_encrypt_type'] : 'RSA';
		$param['timestamp'] = date('Y-m-d H:i:s');
		$param['version'] = '1.0';
		$biz_content = array(
				'out_trade_no' => 'shop_'.$now_order['real_orderid'],
				'scene' => 'bar_code',
				'auth_code' => $_POST['auth_code'],
				'total_amount' => $now_order['price'],
				'subject' => $now_order['real_orderid'],
		);
		$param['biz_content'] = json_encode($biz_content,JSON_UNESCAPED_UNICODE);
		ksort($param);
		$stringToBeSigned = "";
		$i = 0;
		foreach ($param as $k => $v) {
			if (!empty($v) && "@" != substr($v, 0, 1)) {
				if ($i == 0) {
					$stringToBeSigned .= "$k" . "=" . "$v";
				} else {
					$stringToBeSigned .= "&" . "$k" . "=" . "$v";
				}
				$i++;
			}
		}

		$priKey = $this->config['arrival_alipay_app_prikey'];;
		$res = "-----BEGIN RSA PRIVATE KEY-----\n".wordwrap($priKey, 64, "\n", true)."\n-----END RSA PRIVATE KEY-----";

		if($param['sign_type'] == 'RSA2'){
			openssl_sign($stringToBeSigned, $sign, $res, OPENSSL_ALGO_SHA256);
		}else{
			openssl_sign($stringToBeSigned, $sign, $res);
		}
		
		if (empty($sign)) {
			$this->error('支付宝收银商户密钥错误，请联系管理员解决。');
		}

		$sign = base64_encode($sign);

		$param['sign'] = $sign;
		$requestUrl = "https://openapi.alipay.com/gateway.do?";
		foreach ($param as $sysParamKey => $sysParamValue) {
			$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
		}
		$requestUrl = substr($requestUrl, 0, -1);
		// echo $requestUrl;die;
		import('ORG.Net.Http');
		$http = new Http();

		$return = Http::curlGet($requestUrl);
		$returnArr = json_decode($return,true);
		if (!empty($returnArr['alipay_trade_pay_response']) && "10000" == $returnArr['alipay_trade_pay_response']['code']) {
			$data['paid'] = 1;
			$data['status'] = 2;
			$data['order_status'] = 5;
			$data['pay_time'] = strtotime($returnArr['alipay_trade_pay_response']['gmt_payment']);
			$data['use_time'] = time();
			$data['pay_type'] = 'alipay';
			$data['third_id'] = $returnArr['alipay_trade_pay_response']['trade_no'];
			$data['payment_money'] = $returnArr['alipay_trade_pay_response']['receipt_amount'];
			if (M($table_name)->where(array('order_id'=>$order_id))->data($data)->save()) {
				$now_order = M($table_name)->where(array('order_id' => $order_id))->find();
				$this->shop_notice($now_order, true);
				$this->success('支付成功！');
			} else {
				$this->error('支付失败！请联系管理员处理。');
			}
		} elseif (!empty($returnArr['alipay_trade_pay_response']) && "10003" == $returnArr['alipay_trade_pay_response']['code']) {	//需要用户处理，下次查询订单

		} else {
			if ($returnArr['alipay_trade_pay_response']['sub_code'] == 'ACQ.TRADE_HAS_SUCCESS' && $now_order['paid'] != 1) {
				$data['paid'] = 1;
				$data['status'] = 2;
				$data['order_status'] = 5;
				$data['pay_time'] = strtotime($returnArr['alipay_trade_pay_response']['gmt_payment']);
				$data['use_time'] = time();
				$data['pay_type'] = 'alipay';
				$data['third_id'] = $returnArr['alipay_trade_pay_response']['trade_no'];
				$data['payment_money'] = $returnArr['alipay_trade_pay_response']['receipt_amount'];
				if (M($table_name)->where(array('order_id' => $order_id))->data($data)->save()) {
					$now_order = M($table_name)->where(array('order_id' => $order_id))->find();
					$this->shop_notice($now_order, true);
					$this->success('支付成功！');
				} else {
					$this->error('支付失败！请联系管理员处理。');
				}
			} else {
				$this->error('支付失败！支付宝返回：'.$returnArr['alipay_trade_pay_response']['sub_msg']);
			}
		}
	}

	public function alipay_face_to_face($order_id, $now_order, $table_name, $data = array()){
		import('@.ORG.pay.Aop.AopClient');
		import('@.ORG.pay.Aop.SignData');
		import('@.ORG.pay.Aop.AlipayTradePayRequest');
		$aop = new AopClient ();
		$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
		$aop->appId = $this->config['pay_alipay_sp_appid'];
		$aop->rsaPrivateKey = $this->config['pay_alipay_sp_prikey'];
		$aop->alipayrsaPublicKey=$this->config['pay_alipay_sp_pubkey'];
		$aop->apiVersion = '1.0';
		$aop->signType = 'RSA2';
		$aop->postCharset='UTF-8';
		$aop->format='json';
		$request = new AlipayTradePayRequest();
		$biz_content = array(
				'out_trade_no' => 'store_'.$now_order['order_id'],
				'scene' => 'bar_code',
				'auth_code' => $_POST['auth_code'],
				'product_code' => 'FACE_TO_FACE_PAYMENT',
				'total_amount' => $now_order['price'],
				'subject' => $now_order['orderid'],
		);
		$now_merchant = D('Merchant')->get_info($this->store['mer_id']);
		$request->setBizContent(json_encode($biz_content,JSON_UNESCAPED_UNICODE));
		$result = $aop->execute ( $request,$_POST['auth_code'],$now_merchant['alipay_auth_code']);

		$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
		$resultCode = $result->$responseNode->code;
		if(!empty($resultCode)&&$resultCode == 10000){
			$this->success_tips('支付成功!');
		} elseif($resultCode == 10003) {
			$this->error_tips("等待用户验证支付");
		}else{
			if($result->$responseNode->sub_code=='ACQ.TRADE_HAS_SUCCESS'){
				$this->error_tips($result->$responseNode->sub_msg);
			}else{
				$this->error_tips($result->$responseNode->sub_msg);
			}
		}
	}


	public function appoint_export()
	{
		$param = $_POST;
		$param['type'] = 'appoint';
		$param['rand_number'] = $_SERVER['REQUEST_TIME'];
		 $param['store_session']['store_id'] = $this->store['store_id'];
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
		die;
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '预约订单信息';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		// 设置当前的sheet
		//$where['a.appoint_id'] = intval($_GET['appoint_id']);
		$store_id = $this->store['store_id'];
		$where['a.store_id'] = $store_id;
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'order_id') {
				$where['a.order_id'] = $_GET['keyword'];
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['a.order_id'] = $tmp_result['order_id'];
			}elseif ($_GET['searchtype'] == 'name') {
				$where['u.username'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['u.phone'] = htmlspecialchars($_GET['keyword']);
			}elseif ($_GET['searchtype'] == 'third_id') {
				$where['a.third_id'] =$_GET['keyword'];
			}
		}
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type){
			if($pay_type=='balance'){
				$where['_string'] = "(`a`.`balance_pay`<>0 OR `a`.`merchant_balance` <> 0 OR a.product_merchant_balance <> 0 OR a.product_balance_pay <> 0 )";
			}else{
				$where['a.pay_type'] = $pay_type;
			}
		}
		$database_appoint = D('Appoint');
		$database_order = D('Appoint_order');
		$now_appoint = $database_appoint->field(true)->where(array('appoint_id'=>$_GET['appoint_id']))->find();
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
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
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}


	public function group_export()
	{
		$param = $_POST;
		$param['type'] = 'group';
		$param['rand_number'] = $_SERVER['REQUEST_TIME'];
		 $param['store_session']['store_id'] = $this->store['store_id'];
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
		die;
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '团购订单信息';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		// 设置当前的sheet
		$store_id = $this->store['store_id'];

		$condition_where = " WHERE `o`.`store_id`='$store_id'";
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$condition_where .= "AND `o`.`real_orderid`='" . htmlspecialchars($_GET['keyword'])."'";
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
				$condition_where .= "AND `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
			} elseif ($_GET['searchtype'] == 'name') {
				$condition_where .= "AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			} elseif ($_GET['searchtype'] == 'phone') {
				$condition_where .= "AND `u`.`phone`='" . htmlspecialchars($_GET['keyword']) . "'";
			} elseif ($_GET['searchtype'] == 's_name') {
				$condition_where .= "AND `g`.`s_name` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}elseif ($_GET['searchtype'] == 'third_id') {
				$condition_where .= " AND `o`.`third_id` =".$_GET['keyword'];
			}
		}


		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
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
			$condition_where .= " AND `o`.`status`={$status}";
		}
		if($pay_type){
			if($pay_type=='balance'){
				$condition_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )";
			}else{
				$condition_where .= " AND `o`.`pay_type`='{$pay_type}'";
			}
		}

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$condition_where .= " AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
			//$condition_where['_string']=$time_condition;
		}


		$sql = "SELECT count(order_id) as count FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC ";
		$count = D()->query($sql);

		$length = ceil($count[0]['count'] / 1000);
		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);

			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
			$objActSheet = $objExcel->getActiveSheet();
			$objActSheet->getColumnDimension('A')->setAutoSize(true);
			$objActSheet->getColumnDimension('B')->setWidth(50);
			$objActSheet->getColumnDimension('C')->setWidth(20);
			$objActSheet->getColumnDimension('D')->setAutoSize(true);
			$objActSheet->getColumnDimension('E')->setAutoSize(true);
			$objActSheet->getColumnDimension('F')->setAutoSize(true);
			$objActSheet->getColumnDimension('G')->setAutoSize(true);
			$objActSheet->getColumnDimension('H')->setAutoSize(true);
			$objActSheet->getColumnDimension('I')->setAutoSize(true);
			$objActSheet->getColumnDimension('J')->setAutoSize(true);
			$objActSheet->getColumnDimension('K')->setAutoSize(true);
			$objActSheet->getColumnDimension('L')->setAutoSize(true);
			$objActSheet->getColumnDimension('M')->setAutoSize(true);
			$objActSheet->getColumnDimension('N')->setAutoSize(true);
			$objActSheet->getColumnDimension('O')->setWidth(20);
			$objActSheet->getColumnDimension('P')->setWidth(20);
			$objActSheet->getColumnDimension('Q')->setWidth(50);
			$objActSheet->setCellValue('A1', '订单编号');
			$objActSheet->setCellValue('B1', '团购名称');
			$objActSheet->setCellValue('C1', '商家名称');
			$objActSheet->setCellValue('D1', '客户姓名');
			$objActSheet->setCellValue('E1', '客户电话');
			$objActSheet->setCellValue('F1', '订单总价');
			$objActSheet->setCellValue('G1', '平台余额');
			$objActSheet->setCellValue('H1', '商家余额');
			$objActSheet->setCellValue('I1', '在线支付金额');
			$objActSheet->setCellValue('J1', '平台'.$this->config['score_name'].'');
			$objActSheet->setCellValue('K1', '平台优惠券');
			$objActSheet->setCellValue('L1', '商家优惠券');
			$objActSheet->setCellValue('M1', '商家折扣');
			$objActSheet->setCellValue('N1', '支付时间');
			$objActSheet->setCellValue('O1', '订单状态');
			$objActSheet->setCellValue('P1', '支付情况');
			$objActSheet->setCellValue('Q1', '收货地址');


			$sql = "SELECT o.*, m.name AS merchant_name,u.nickname as username ,g.name as group_name FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";
			$result_list = D()->query($sql);

			if (!empty($result_list)) {
				$index = 2;
				foreach ($result_list as $value) {
					$objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
					$objActSheet->setCellValueExplicit('B' . $index, $value['group_name']);
					$objActSheet->setCellValueExplicit('C' . $index, $value['merchant_name']);
					$objActSheet->setCellValueExplicit('D' . $index, $value['username'] . ' ');
					$objActSheet->setCellValueExplicit('E' . $index, $value['phone'] . ' ');
					$objActSheet->setCellValueExplicit('F' . $index, floatval($value['total_money']));
					$objActSheet->setCellValueExplicit('G' . $index, floatval($value['balance_pay']));
					$objActSheet->setCellValueExplicit('H' . $index, floatval($value['merchant_balance']));
					$objActSheet->setCellValueExplicit('I' . $index, floatval($value['payment_money']));
					$objActSheet->setCellValueExplicit('J' . $index, floatval($value['score_reducte']));
					$objActSheet->setCellValueExplicit('K' . $index, floatval($value['coupon_price']));
					$objActSheet->setCellValueExplicit('L' . $index, floatval($value['card_price']));
					$objActSheet->setCellValueExplicit('M' . $index, floatval($value['card_discount'])?floatval($value['card_discount']). '折':'');
					$objActSheet->setCellValueExplicit('N' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
					$objActSheet->setCellValueExplicit('O' . $index, $this->get_order_status($value));
					$objActSheet->setCellValueExplicit('P' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));
					$objActSheet->setCellValueExplicit('Q' . $index, $value['adress']);


					$index++;
				}
			}
			sleep(2);
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
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


	public function foodshop_export(){
		$param = $_POST;
		$param['type'] = 'meal';
		$param['rand_number'] = $_SERVER['REQUEST_TIME'];
		 $param['store_session']['store_id'] = $this->store['store_id'];
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
		die;
		$store_id = $this->store['store_id'];
		$condition_where = 'Where o.mer_id = '.$this->store['mer_id'].' AND o.store_id = '.$store_id;

		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['real_orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.real_orderid ='.$where['real_orderid'];
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND p.orderid ='.$where['orderid'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['name'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.name ='.$where['name'];
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['phone'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.phone ='.$where['phone'];
			} elseif ($_GET['searchtype'] == 'third_id') {
				$condition_where .=' AND p.third_id ='.$_GET['keyword'];
			}
		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= $type . ' ' . $sort . ',';
			$order_sort .= 'order_id DESC';
		} else {
			$order_sort .= 'order_id DESC';
		}
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type&&$pay_type!='balance'){
			$condition_where .=' AND p.pay_type ="'.$pay_type.'"';
		}else if($pay_type=='balance'){
			$condition_where .= " AND  (`p`.`system_balance`<>0 OR `p`.`merchant_balance_pay` <> 0 )";
		}


		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] .=( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition_where .=" AND  (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}

		if ($status != -1) {
			$where['status'] = $status;
			$condition_where .= ' AND o.status ='.$status;
		}

		$sql_cont = 'SELECT count(o.order_id) as count from pigcms_foodshop_order o LEFT JOIN (select a.* from pigcms_plat_order a,(select max(order_id) as order_id,business_id from pigcms_plat_order group by business_id) b where a.order_id=b.order_id and a.business_id=b.business_id) p on o.order_id  = p.business_id '.$condition_where;
		$count  = M()->query($sql_cont);
		$count = $count[0]['count'];
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '订单信息';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);
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
			$objActSheet->setCellValue('T1', $this->config['score_name']);
			$objActSheet->setCellValue('U1', '支付时间');
			$objActSheet->setCellValue('V1', '支付方式');
			$objActSheet->setCellValue('W1', '支付类型');


			//$objActSheet->setCellValue('R1', '支付情况');

			$sql = 'SELECT o.*,fd.name as goods_name,fd.num as goods_num ,fd.spec as goods_spec,fd.unit as goods_unit,fd.price as goods_price,p.pay_type as pay_method,p.system_balance,p.merchant_balance_pay,p.pay_money,p.system_score_money,p.pay_time ,p.paid from '.C('DB_PREFIX').'foodshop_order o LEFT JOIN  '.C('DB_PREFIX').'plat_order  p on o.order_id  = p.business_id AND p.paid =1 LEFT JOIN '.C('DB_PREFIX').'foodshop_order_detail as fd ON fd.order_id = o.order_id '.$condition_where.' ORDER BY o.order_id DESC ' .'limit '.($i*1000).',1000';

			$list = M('')->query($sql);

			//appdump(M());
			$mer_ids = $store_ids = $table_types= $tids =array();
			foreach ($list as $l) {
				!in_array($l['mer_id'],$mer_ids) && $mer_ids[] = $l['mer_id'];
				!in_array($l['store_id'], $store_ids) && $store_ids[] = $l['store_id'];
				!in_array($l['table_type'], $table_types)&& $table_types[] = $l['table_type'];
				!in_array($l['table_id'], $tids) && $tids[] = $l['table_id'];

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
						if($index_good==1){
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
						}
						if($index_good==0) {
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
							$objActSheet->setCellValueExplicit('O' . $index, $value['show_status']);
							$objActSheet->setCellValueExplicit('P' . $index, floatval($value['total_price']));
							$objActSheet->setCellValueExplicit('Q' . $index, floatval($value['system_balance']));
							$objActSheet->setCellValueExplicit('R' . $index, floatval($value['pay_money']));
							$objActSheet->setCellValueExplicit('S' . $index, floatval($value['merchant_balance_pay']));
							$objActSheet->setCellValueExplicit('T' . $index, floatval($value['system_score_money']));
							$objActSheet->setCellValueExplicit('U' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
							$objActSheet->setCellValueExplicit('V' . $index, D('Pay')->get_pay_name($value['pay_method'], $value['is_mobile_pay'],1));
							$objActSheet->setCellValueExplicit('W' . $index, '支付余额');
							$index_good = 1;
						}

						$index++;
					}else{
						$index++;
						$objActSheet->setCellValueExplicit('A' . $index, $value['order_id']);
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
						$objActSheet->setCellValueExplicit('P' . $index, floatval($value['total_price']));
						$objActSheet->setCellValueExplicit('Q' . $index, floatval($value['system_balance']));
						$objActSheet->setCellValueExplicit('R' . $index, floatval($value['pay_money']));
						$objActSheet->setCellValueExplicit('S' . $index, floatval($value['merchant_balance_pay']));
						$objActSheet->setCellValueExplicit('T' . $index, floatval($value['system_score_money']));
						$objActSheet->setCellValueExplicit('U' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
						$objActSheet->setCellValueExplicit('V' . $index,D('Pay')->get_pay_name($value['pay_method'], $value['is_mobile_pay'], 1));
						$objActSheet->setCellValueExplicit('W' . $index,'支付定金');
						$index++;
						$index_good = 0;
					}
					$tmp_id = $value['order_id'];

				}
			}
			sleep(2);
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}

	/**
	 * 实体卡管理
	 *
	 */
	public  function physical_card(){
		$card_list = D('Physical_card')->get_cardid_by_mer_id($this->store['mer_id']);
		$this->assign($card_list);
		$this->display();
	}

	public  function physical_card_add(){
		$mer_id = $this->store['mer_id'];
		if(IS_POST){
			$cardid = $_POST['cardid'];
			$card  = D('Physical_card');
			$result =$card->check_card($cardid,$_POST['phone'],$mer_id);
			if($result['error_code']){
				$this->error($result['msg']);
			}
			$card->bind_user($result['card_info'],$result['user'],$this->staff_session['name'].'用实体卡（卡号：'.$_POST['cardid'].'）为用户充值'.$result['card']['balance_money'].'元');
			$log['staff_id'] = $this->staff_session['id'];
			$log['mer_id'] =$this->store['mer_id'];
			$log['card_id'] = $_POST['cardid'];
			$log['des'] = '店员 '.$this->staff_session['name'].' 为用户（'.$result['user']['uid'].'）绑定实体卡（卡号：'.$_POST['cardid'].'）';
			$card->add_log($log);
			$this->success('绑定成功');
		}else{
			$this->display();
		}
	}

	public function physical_card_log(){
		$log = D('Physical_card')->card_log(0,$this->staff_session['id'],0);
		$this->assign($log);
		$this->display();
	}

	public function scan_payid_check(){
		$payid = $_POST['payid'];
		$res = M('Tmp_payid')->where(array('payid'=>$payid))->order('add_time DESC')->find();
		if(($res['add_time']+60)<time()){
			$this->error('付款码错误或超时，请重新输入!');
		}else{
			$this->success(array('uid'=>$res['uid'],'payid'=>$payid));
		}
	}

    // 团购新单统计数量
    public function group_count()
    {
        $time = I('time', time());
        $where = array(
            'mer_id' => $this->store['mer_id'],
            'store_id' => $this->store['store_id'],
            'pay_time' => array('gt', $time)
        );
        $count = M('Group_order')->where($where)->count();
        exit(json_encode(array('errcode' => 0, 'data' => array('count' => intval($count), 'time' => $time))));
    }

    // 餐饮新单统计数量
    public function foodshop_count()
    {
        $time = I('time', time());
        $where = array(
            'mer_id' => $this->store['mer_id'],
            'store_id' => $this->store['store_id'],
            'status' => 1,
            'create_time' => array('gt', $time)
        );
        $count = M('Foodshop_order')->where($where)->count();
        exit(json_encode(array('errcode' => 0, 'data' => array('count' => intval($count), 'time' => $time))));
    }

    // 快店新单统计数量
    public function shop_count()
    {
        $time = I('time', time());
        $where = array(
            'mer_id' => $this->store['mer_id'],
            'store_id' => $this->store['store_id'],
            'pay_time' => array('gt', $time)
        );
        $count = M('Shop_order')->where($where)->count();
        exit(json_encode(array('errcode' => 0, 'data' => array('count' => intval($count), 'time' => $time))));
    }

    // 预约新单统计数量
    public function appoint_count()
    {
        $time = I('time', time());
        $where = array(
            'store_id' => $this->store['store_id'],
            'paid' => 1,
            'order_time' => array('gt', $time)
        );
        $count = M('Appoint_order')->where($where)->count();
        exit(json_encode(array('errcode' => 0, 'data' => array('count' => intval($count), 'time' => $time))));
    }

    public function store_count()
    {
        $time = I('time', time());
        $where = array(
            'store_id' => $this->store['store_id'],
            'pay_time' => array('gt', $time),
            'from_plat' => 0
        );
        $count = M('Store_order')->where($where)->count();
        exit(json_encode(array('errcode' => 0, 'data' => array('count' => intval($count), 'time' => $time))));
    }

    public function cash_count()
    {
        $time = I('time', time());
        $where = array(
            'store_id' => $this->store['store_id'],
            'pay_time' => array('gt', $time),
            'from_plat' => 1
        );
        $count = M('Store_order')->where($where)->count();
        exit(json_encode(array('errcode' => 0, 'data' => array('count' => intval($count), 'time' => $time))));
    }

	//店铺充值列表
	public function money_list(){
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'order_id') {
				$condition_where['order_id'] = htmlspecialchars($_GET['keyword']);
			}
		}
		$condition_where['store_id']=$this->store['store_id'];
		$_GET['type']=='all' && $_GET['type']='';
		!empty($_GET['type']) && $condition_where['type'] =$_GET['type'];
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}

			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$condition_where['_string']= "use_time BETWEEN ".$period[0].' AND '.$period[1];

		}

		$count = M('Store_money_list')->where($condition_where)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count, 15);
		$order_list= M('Store_money_list')->where($condition_where)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();
		$this->assign('pagebar',$p->show());
		$this->assign('order_list',$order_list);
		$this->assign('alias_name',$this->get_alias_c_name());
		$this->display();

	}

	protected  function get_alias_c_name(){
		$c_name = array(
				'all'=>'所有分类',
				'group'=>$this->config['group_alias_name'],
				'shop'=>$this->config['shop_alias_name'],
				'shop_offline'=>$this->config['shop_alias_name'].'线下零售',
				'meal'=>$this->config['meal_alias_name'],
				'appoint'=>$this->config['appoint_alias_name'],
				'store'=>'优惠买单',
				'cash'=>'到店支付',
			//	'coupon'=>'优惠券',
				'strecharge'=>'店铺充值',

		);

		if(!$this->config['appoint_page_row']) unset($c_name['appoint']);
		if(!$this->config['is_cashier']) unset($c_name['store']);
		if(!$this->config['pay_in_store'] && !$this->config['is_cashier'] ) unset($c_name['cash'],$c_name['shop_offline']);
		return $c_name ;
	}
	public function recharge_list(){

		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'order_id') {
				$condition_where['order_id'] = htmlspecialchars($_GET['keyword']);
			}
		}
		!empty($_GET['pay_type']) && $condition_where['pay_type'] =$_GET['pay_type'];
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}

			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$condition_where['_string']= "pay_time BETWEEN ".$period[0].' AND '.$period[1];

		}
		$condition_where['store_id']=  $this->store['store_id'];

		$count = M('Store_recharge_order')->where($condition_where)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count, 15);
		$order_list= M('Store_recharge_order')->where($condition_where)->order('order_id DESC')->limit($p->firstRow,$p->listRows)->select();
		$this->assign('pagebar',$p->show());
		$this->assign('order_list',$order_list);
		$this->assign('pay_type',$this->getPayName());
		$this->display();
	}

	protected function getPayName(){
		$payName = array(
				'weixin' => '微信支付',
				'tenpay' => '财付通支付',
				'yeepay' => '银行卡支付(易宝支付)',
				'allinpay' => '银行卡支付(通联支付)',
				'chinabank' => '银行卡支付(网银在线)',
		);
		return $payName;
	}
	public function recharge(){
		if(IS_POST){
			$money = floatval($_POST['money']);
			if (empty($money)||$money <0 ||!is_numeric($money)) {
				$this->error('请输入正确的充值金额');
			}
			$data_store_recharge_order['store_id'] = $this->store['store_id'];
			$data_store_recharge_order['money'] = $money;
			$data_store_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
			$data_store_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];

			if ($order_id = M('Store_recharge_order')->data($data_store_recharge_order)->add()) {
				$this->success($order_id,U('StorePay/check', array('order_id' => $order_id, 'type' => 'strecharge')));
			} else {
				$this->error_tips('订单创建失败，请重试。');
			}
		}else{
			$this->display();
		}
	}

	//店铺余额订单导出
	public function store_money_export()
	{
		$store_id = $this->store['store_id'];
		//$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';

		$type = 'income';
		$title = '';

		switch ($type) {

			case 'income':
				$title = '收入明细';
				break;


		}
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);
		// 设置当前的sheet
		$objExcel->setActiveSheetIndex(0);


		$objExcel->getActiveSheet()->setTitle($type);
		$objActSheet = $objExcel->getActiveSheet();
		$cell_income   = array('type'=>'类型','order_id'=>'订单编号', 'num'=>'数量', 'money'=>'金额','score'=>'送出'.$this->config['score_name'],'score_count'=>$this->config['score_name'].'使用数量','use_time'=>'记账时间','desc'=>'描述');
		// 开始填充头部
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
		foreach($$cell_name as $key=>$v){

			$objActSheet->setCellValue($col_char[$col_k].'1', $v);
			$col_k++;
		}
		$i = 2;
		//if($type=='income'){
			$where['store_id']=$store_id;
			if($_GET['type']&&$_GET['type']!='all'){
				$where['type']=$_GET['type'];
			}
			if($_GET['order_id']){
				$where['order_id']=$_GET['order_id'];
			}
			if($_GET['store_id']){
				$where['store_id']=$_GET['store_id'];
			}


			if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
				if ($_GET['begin_time']>$_GET['end_time']) {
					$this->error("结束时间应大于开始时间");
				}
				$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));

				$time_condition=" (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
				$where['_string']=$time_condition;
			}
			$result = M('Store_money_list')->field('type,order_id,num,pow(-1,income+1)*money as money,use_time,desc,score,score_count')->where($where)->order('use_time DESC')->select();

		//}
		$alias_name = $this->get_alias_c_name();
		foreach ($result as $row) {
			$col_k=0;
			foreach($$cell_name as $k=>$vv){
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
					case 'pay_time':
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
						break;
					case 'use_time':
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
						break;
					case 'desc':
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
						break;
					default:
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]);
						break;
				}
				$col_k++;
			}
			if($type!='income'){
				$objActSheet->setCellValue($col_char[$cell_count-1] . $i, $row['balance_pay']+$row['coupon_price']+$row['score_deducte']+$row['payment_money']);
			}
			$i++;
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();

	}

	public function shop_order_print()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

        $now_order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
        if (empty($now_order)) {
            $this->error('订单信息错误');
        }
        $printHaddle = new PrintHaddle();
        $printHaddle->printit($now_order['order_id'], 'shop_order', -1);

//         $msg = ArrayToStr::array_to_str($now_order['order_id'], 'shop_order');
//         $op = new orderPrint(C('config.print_server_key'), C('config.print_server_topdomain'));
//         $op->printit($now_order['mer_id'], $now_order['store_id'], $msg, -1);

//         $str_format = ArrayToStr::print_format($now_order['order_id'], 'shop_order');
//         foreach ($str_format as $print_id => $print_msg) {
//             $print_id && $op->printit($now_order['mer_id'], $now_order['store_id'], $print_msg, -1, $print_id);
//         }

        $this->success('打印成功');
    }

	public function get_print_has(){
		$condition['mkey'] 	= $_POST['mkey'];
		$condition['store_id']	= $this->store['store_id'];
		if(M('Orderprinter')->where($condition)->find()){
			$this->success('打印机存在');
		}else{
			$this->error('打印机不存在');
		}
	}
	public function own_print_work(){
		$mkey = $_POST['mkey'];
		if(empty($mkey)){
			$this->error('请携带密钥值');
		}
		$op = new orderPrint(C('config.print_server_key'), C('config.print_server_topdomain'));
		$content = $op->get_own_printer($mkey);
		$this->success($content);
	}



	public function shop_order_report_form()
	{
	    $this->display();
	}

	public function ajaxShopOrder()
	{
	    $day = isset($_POST['day']) ? intval($_POST['day']) : 0;
	    $period = isset($_POST['period']) ? htmlspecialchars($_POST['period']) : '';
	    $stime = $etime = 0;
	    if ($day) {
	        $stime = strtotime("-{$day} day");
	        $etime = time();
	    }
	    if ($period) {
	        $time_array = explode('-', $period);
	        $stime = strtotime($time_array[0]);
	        $etime = strtotime($time_array[1]);
	    }
	    $where = 'store_id=' . $this->store['store_id'] . ' AND paid=1 AND status!=4 AND status!=5';
	    if ($stime && $etime) {
	        $where .= ' AND pay_time>' . $stime . ' AND pay_time<' . $etime;
	    }
	    $list = M('Shop_order')->field('sum(price) AS totalPrice, count(1) AS total, platform')->where($where)->group('platform')->select();

	    $count = array();
	    $price = array();
	    $platformNames = array(C('config.shop_alias_name'), '饿了么', '美团外卖');
	    $countColor = array('#01b484', '#2f4554', '#91c7ae');
	    $priceColor = array('#d48265', '#4d542f', '#337ab7');
	    foreach ($list as $row) {
	        $ctemp = array();
	        $ctemp['name'] = $platformNames[$row['platform']];
	        $ctemp['value'] = intval($row['total']);
	        $ctemp['itemStyle'] = array('normal' => array('color' => $countColor[$row['platform']]));
	        $count[] = $ctemp;
	        $ptemp = array();
	        $ptemp['name'] = $platformNames[$row['platform']];
	        $ptemp['value'] = floatval($row['totalPrice']);
	        $ptemp['itemStyle'] = array('normal' => array('color' => $priceColor[$row['platform']]));
	        $price[] = $ptemp;
	    }
	    exit(json_encode(array('error_code' => false, 'price' => $price, 'count' => $count, 'plat' => $platformNames)));
	}

    public function sort_export()
    {
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '商品销量统计';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $sortId = isset($_GET['sort_id']) ? intval($_GET['sort_id']) : 0;
        $orderFrom= isset($_GET['orderFrom']) ? intval($_GET['orderFrom']) : 0;

        $stime = isset($_GET['stime']) && $_GET['stime'] ? htmlspecialchars($_GET['stime']) : '';
        $etime = isset($_GET['etime']) && $_GET['etime'] ? htmlspecialchars($_GET['etime']) : '';

        $store_id = $this->store['store_id'];

        $shopGoodsSortDB = D('Shop_goods_sort');
        $sortList = $shopGoodsSortDB->field(true)->where(array('store_id' => $store_id, 'fid' => 0))->select();

        $where = 'o.paid=1 AND (o.status=2 OR o.status=3) AND o.store_id=' . $store_id;
        if ($sort = D('Shop_goods_sort')->field(true)->where(array('sort_id' => $sortId, 'store_id' => $store_id))->find()) {
            if ($sort['fid'] == 0) {
                $ids = $shopGoodsSortDB->getAllSonIds($sortId, $store_id);
                if ($ids) {
                    $where .= ' AND d.sort_id IN (' . implode(',', $ids) . ')';
                }
            }
        }
        if ($stime && $etime) {
            $where .= ' AND o.pay_time>' . strtotime($stime) . ' AND o.pay_time<' . strtotime($etime);
        }
        if ($orderFrom) {
            if ($orderFrom == 1) {
                $where .= ' AND o.order_from=1';
            } elseif ($orderFrom == 3) {
                $where .= ' AND o.order_from=6';
            } else {
                $where .= ' AND o.order_from<>1 AND o.order_from<>6';
            }
        }

        $sql = "SELECT `d`.* FROM " . C('DB_PREFIX') . 'shop_order AS o INNER JOIN ' . C('DB_PREFIX') . 'shop_order_detail AS d ON d.order_id=o.order_id WHERE ' . $where;
        $orders = M()->query($sql);
        $objExcel->setActiveSheetIndex(0);
        $objExcel->getActiveSheet()->setTitle('商品销量详情');
        $objActSheet = $objExcel->getActiveSheet();
        $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        $objActSheet->setCellValue('A1', '商品名称');
        $objActSheet->setCellValue('B1', '商品属性');
        $objActSheet->setCellValue('C1', '数量');
        $objActSheet->setCellValue('D1', '单价');
        $objActSheet->setCellValue('E1', '销售时间');
        $objActSheet->setCellValue('F1', '订单来源');
        $index = 1;
        foreach ($orders as $order) {
            $index ++;
            $objActSheet->setCellValueExplicit('A' . $index, $order['name']);
            $objActSheet->setCellValueExplicit('B' . $index, $order['spec']);
            $objActSheet->setCellValueExplicit('C' . $index, $order['num']);
            $objActSheet->setCellValueExplicit('D' . $index, $order['discount_price'] > 0 ? $order['discount_price'] : $order['price']);
            $objActSheet->setCellValueExplicit('E' . $index, date('Y-m-d H:i:s', $order['create_time']));
            if ($order['order_from'] == 1) {
                $objActSheet->setCellValueExplicit('F' . $index, '商城');
            } elseif ($order['order_from'] == 6) {
                $objActSheet->setCellValueExplicit('F' . $index, '线下零售');
            } else {
                $objActSheet->setCellValueExplicit('F' . $index, $this->config['shop_alias_name']);
            }

        }

        // 输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="' . $title . '_' . date("Y-m-d h:i:sa", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();

    }

	public function sub_card(){
		import('@.ORG.merchant_page');

		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'pass') {
				$where['pass'] = htmlspecialchars($_GET['keyword']);
			}else if ($_GET['searchtype'] == 'name') {
				$where['nickname'] = array('like','%'.htmlspecialchars($_GET['keyword']).'%');
			}else if ($_GET['searchtype'] == 'phone') {
				$where['phone'] = htmlspecialchars($_GET['keyword']);
			}
		}
		!empty($_GET['pay_type']) && $condition_where['pay_type'] =$_GET['pay_type'];
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}

			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string']= "use_time BETWEEN ".$period[0].' AND '.$period[1];

		}
		$where['status']=1;
		$where['store_id']=$this->store['store_id'];


		$count = M('Sub_card_user_pass')->where($where)->count();
		$p=new Page($count,15);
		$where['s.status']=1;
		unset($where['status']);
		$list = M('Sub_card_user_pass')->field('s.* ,sc.name,sc.desc,sc.price,sc.free_total_num,u.nickname,u.phone')->join('AS s LEFT JOIN '.C('DB_PREFIX').'sub_card sc ON sc.id = s.sub_card_id LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = case  when s.share_uid>0 then s.share_uid else s.uid end ')->where($where)->order('s.use_time DESC')->limit($p->firstRow,$p->listRows)->select();

		$this->assign('order_list',$list);
		$this->assign('pagebar',$p->show());
		$this->display();
	}

	//搜索免单
	public function sub_card_find(){
		if(IS_POST) {
			$find_value = $_POST['find_value'];
			if (strlen($find_value) == 14) {
				$res = D('Sub_card_order')->get_orderid_by_pass($find_value,$this->store['store_id']);

				if (!empty($res)) {
					$return['list'] = array($res['pass_info']);
					$return['sub_card'] = $res['sub_card'];
					$return['row_count'] = 1;

					echo json_encode($return);
				} else {
					$return['row_count'] = 0;

					echo json_encode($return);
				}
			}
		}else{
			$this->display();
		}
	}

	//免单套餐验证消费
	public function sub_card_verify(){
		$find_value = $_GET['pass'];
		$res = D('Sub_card_order')->get_orderid_by_pass($find_value,$this->store['store_id']);
		if($res['pass_info']['status']==1){
			$this->error('此消费码已消费，不能再验证消费！');
		} else {
			$condition['id'] = $res['pass_info']['id'];
			$date['status'] = '1';
			$date['use_time'] = $_SERVER['REQUEST_TIME'];
			$date['last_staff'] = $this->staff_session['name'];
			if(M('Sub_card_user_pass')->where($condition)->data($date)->save()){
				//验证增加商家余额
				$now_order['order_type'] = 'sub_card';
				$now_order['order_id'] = $res['pass_info']['id'];
				$now_order['mer_id'] =$this->store['mer_id'];
				$now_order['store_id'] =$this->store['store_id'];
				$now_order['percent'] =$res['sub_card']['percent'];
				$now_order['desc']='免单验证消费记入收入';
				$now_order['money'] = $res['sub_card']['price']/$res['sub_card']['free_total_num'];
				$now_order['total_money'] =$now_order['money'];
				D('SystemBill')->bill_method(0,$now_order);
				$this->success('验证消费成功！');
			}else{
				$this->error('验证失败！请重试。');
			}
		}
	}
	
	public function check_foodshop_goods_stock()
	{
	    if ($result = D('Foodshop_goods')->check_stock_list($this->store['store_id'])) {
	        exit(json_encode(array('status' => 1)));
	    } else {
	        exit(json_encode(array('status' => 0)));
	    }
	}
	
	public function foodshop_goods_stock()
	{
	    $this->assign('goods_list', D('Foodshop_goods')->check_stock_list($this->store['store_id']));
	    $this->display();
	}
	
    public function foodshop_change_order_note()
    {
        $condition_where['store_id'] = $this->store['store_id'];
        $condition_where['order_id'] = $_GET['order_id'];
        $condition_where['id'] = $_POST['detail_id'];
        $note = isset($_POST['note']) ? htmlspecialchars(trim($_POST['note'])) : '';
        
        if ($detail = M('Foodshop_order_detail')->where($condition_where)->find()) {
            if ($detail['note'] != $note) {
                M('Foodshop_order_detail')->where($condition_where)->save(array('note' => $note));
            }
            $this->success('保存成功！');
        } else {
            $this->error('保存失败！');
        }
    }
    
    public function replyRefund()
    {
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $refund_id = isset($_POST['refund_id']) ? intval($_POST['refund_id']) : 0;
        $type = isset($_POST['type']) ? htmlspecialchars(trim($_POST['type'])) : 'agree';
        $reply_content = isset($_POST['reply_content']) ? htmlspecialchars(trim($_POST['reply_content'])) : '';
        
        $order = D('Shop_order')->field(true)->where(array('order_id' => $order_id, 'store_id' => $this->store['store_id']))->find();
        if (empty($order)) {
            exit(json_encode(array('errcode' => 1, 'msg' => '订单信息错误')));
        }
        if (!in_array($type, array('agree', 'disagree'))) {
            exit(json_encode(array('errcode' => 1, 'msg' => '请选择正确的回复形式')));
        }
        
        $refund = D('Shop_order_refund')->field(true)->where(array('order_id' => $order_id, 'id' => $refund_id))->find();
        if (empty($refund)) {
            exit(json_encode(array('errcode' => 1, 'msg' => '订单的退货信息错误')));
        }
        if ($refund['status'] != 0) {
            exit(json_encode(array('errcode' => 1, 'msg' => '当前的退货信息无需修改')));
        }
        //状态(0：用户申请，1：商家同意退货，2：商家拒绝退货，3：用户重新申请，4：取消退货申请)
        if ($type == 'agree') {
            //先减余额支付，在进行线上支付的退款
            //已退款的金额的查询
            $refundMoney = D('Shop_order_refund')->field('sum(balance_pay) as balance_pay, sum(merchant_balance) as merchant_balance, sum(card_give_money) as card_give_money, sum(payment_money) as payment_money, sum(score_used_count) as score_used_count, sum(score_deducte) as score_deducte')->where(array('order_id' => $order_id))->find();
            //积分->商家余额->平台余额->在线支付的金额
            $nowPayMoney = floatval($refund['price']);//当前要退款的金额
			if($this->config['open_allinyun']==1){
				import('@.ORG.AccountDeposit.AccountDeposit');
				$deposit = new AccountDeposit('Allinyun');
				$allyun = $deposit->getDeposit();

				$allinyun_user = D('Deposit')->allinyun_user($this->user_session['uid']);

				if($allinyun_user['bizUserId']!=''){
					$allyun->setUser($allinyun_user);
					$params['bizOrderNo'] = 'shop_'.$order['orderid'];
					$params['oriBizOrderNo'] = $order['third_id'];
					$params['amount'] = intval($nowPayMoney*100);
					$res = $allyun->refundApply($params);

					if($res['status']=='OK'){
						$data_shop_order['order_id'] = $order['order_id'];
						$data_shop_order['status'] = 4;
						$data_shop_order['last_time'] = time();
						$data_shop_order['refund_detail'] = serialize($res);
						$return = $this->shop_refund_detail($order, $order['store_id']);
						if ($return['error_code']) {
							exit(json_encode(array('errcode' => 1, 'msg' =>$return['msg'])));
//							$this->error_tips($return['msg']);
						}
						D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));
						D('Shop_order')->data($data_shop_order)->save();
						exit(json_encode(array('errcode' => 0, 'msg' =>'退款成功')));
					}else{
						exit(json_encode(array('errcode' => 1, 'msg' =>$res['message'])));
					}
				}
			}
            $score_deducte = 0;
            $score_used_count = 0;
            if ($order['score_deducte'] > $refundMoney['score_deducte']) {
                if ($order['score_deducte'] - $refundMoney['score_deducte'] >= $nowPayMoney) {
                    $score_deducte = $nowPayMoney;
                    $nowPayMoney = 0;
                } else {
                    $score_deducte = $order['score_deducte'] - $refundMoney['score_deducte'];
                    $nowPayMoney = $nowPayMoney - $score_deducte;
                }
                if ($order['score_deducte'] > 0 && $order['score_used_count'] > 0) {
                    $score_used_count = intval($order['score_used_count']/$order['score_deducte'] * $score_deducte);
                }
            }
            
            $merchant_balance = 0;
            $card_give_money = 0;
            if ($nowPayMoney > 0) {
                if ($order['card_give_money'] > $refundMoney['card_give_money']) {
                    if ($order['card_give_money'] - $refundMoney['card_give_money'] >= $nowPayMoney) {
                        $card_give_money = $nowPayMoney;
                        $nowPayMoney = 0;
                    } else {
                        $card_give_money = $order['card_give_money'] - $refundMoney['card_give_money'];
                        $nowPayMoney = $nowPayMoney - $card_give_money;
                    }
                }
                if ($order['merchant_balance'] > $refundMoney['merchant_balance']) {
                    if ($order['merchant_balance'] - $refundMoney['merchant_balance'] >= $nowPayMoney) {
                        $merchant_balance = $nowPayMoney;
                        $nowPayMoney = 0;
                    } else {
                        $merchant_balance = $order['merchant_balance'] - $refundMoney['merchant_balance'];
                        $nowPayMoney = $nowPayMoney - $merchant_balance;
                    }
                }
            }
            $balance_pay = 0;
            if ($nowPayMoney > 0) {
                if ($order['balance_pay'] > $refundMoney['balance_pay']) {
                    if ($order['balance_pay'] - $refundMoney['balance_pay'] >= $nowPayMoney) {
                        $balance_pay = $nowPayMoney;
                        $nowPayMoney = 0;
                    } else {
                        $balance_pay = $order['balance_pay'] - $refundMoney['balance_pay'];
                        $nowPayMoney = $nowPayMoney - $balance_pay;
                    }
                }
            }
            $payment_money = 0;
            $third_refund_id = 0;
            if ($nowPayMoney > 0) {
                $payment_money = $nowPayMoney;
                if ($order['is_own']) {
                    $pay_method = array();
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $order['mer_id']))->find();
                    foreach ($merchant_ownpay as $ownKey => $ownValue) {
                        $ownValueArr = unserialize($ownValue);
                        if ($ownValueArr['open']) {
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name' => '', 'config' => $ownValueArr);
                        }
                    }
                } else {
                    $is_wap = $order['pay_type'] == 'alipayh5' ? 1 : 0;
                    $pay_method = D('Config')->get_pay_method(0, 0, $is_wap, 0);
                }
                
                if (empty($pay_method)) {
                    exit(json_encode(array('errcode' => 1, 'msg' => '支付方式不存在或已关闭')));
                    return false;
                }
                if (empty($pay_method[$order['pay_type']])) {
                    exit(json_encode(array('errcode' => 1, 'msg' => '支付方式不存在或已关闭')));
                }
                
                $pay_class_name = ucfirst($order['pay_type']);
                $import_result = import('@.ORG.pay.' . $pay_class_name);
                if (empty($import_result)) {
                    exit(json_encode(array('errcode' => 1, 'msg' => '支付方式不存在或已关闭')));
                }
                
                $order['order_type'] = 'shop';
                $order['order_id'] = $order['orderid'];
                if ($order['order_from'] == 4) {
                    $pay_method['weixin']['config'] = array(
                        'pay_weixin_appid' => C('config.pay_wxapp_appid'),
                        'pay_weixin_key' => C('config.pay_wxapp_key'),
                        'pay_weixin_mchid' => C('config.pay_wxapp_mchid'),
                        'pay_weixin_appsecret' => C('config.pay_wxapp_appsecret')
                    );
                    if ($order['is_own'] == 1) {
                        $wxapp_own_pay = M('Weixin_app_bind')->where(array('other_id' => $_POST['own_pay_mer_id'], 'bind_type' => 0))->find();
                        $pay_method['weixin']['config'] = array(
                            'pay_weixin_appid' => $wxapp_own_pay['appid'],
                            'pay_weixin_key' => $wxapp_own_pay['wxpay_key'],
                            'pay_weixin_mchid' => $wxapp_own_pay['wxpay_merid'],
                            'pay_weixin_appsecret' => $wxapp_own_pay['appsecret'],
                            'is_own' => 1
                        );
                    }
                }
				$now_user = D('User')->get_user($order['uid']);
				if ($pay_class_name == 'Alipay' && $order['is_mobile_pay'] == 2 && C('config.new_pay_alipay_app_public_key') != '' && C('config.new_pay_alipay_app_appid')!= '' && C('config.new_pay_alipay_app_private_key')!= '') {
					$pay_class_name = 'AlipayApp';
					$pay_method['alipay']['config']['new_pay_alipay_app_appid'] = C('config.new_pay_alipay_app_appid');
					$pay_method['alipay']['config']['new_pay_alipay_app_private_key'] = C('config.new_pay_alipay_app_private_key');
					$pay_method['alipay']['config']['new_pay_alipay_app_public_key'] = C('config.new_pay_alipay_app_public_key') ;
				}
				$import_result = import('@.ORG.pay.' . $pay_class_name);
				$pay_class = new $pay_class_name($order, $order['payment_money'], $order['pay_type'], $pay_method[$order['pay_type']]['config'], $now_user, 1);
				$go_refund_param = $pay_class->refund();
				
                if (!(empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok')) {
                    exit(json_encode(array('errcode' => 1, 'msg' => $go_refund_param['msg'])));
                } else {
                    $third_refund_id = $go_refund_param['refund_param']['refund_id'];
                }
            }
            
            $mer_store = D('Merchant_store')->where(array('mer_id' => $order['mer_id'], 'store_id' => $order['store_id']))->find();
            if ($score_used_count != 0) {
                $result = D('User')->add_score($order['uid'], $score_used_count, $this->config['shop_alias_name'] . '部分商品退货, ' . C('config.score_name').'回滚,订单编号' . $order['real_orderid']);
                $param = array('refund_time' => time());
                if ($result['error_code']) {
                    $score_used_count = 0;
                }
            }
            
            //平台余额退款
            if ($balance_pay > 0) {
                $result = D('User')->add_money($order['uid'], $balance_pay, $this->config['shop_alias_name'] . '部分商品退货,增加余额,订单编号' . $order['real_orderid']);
                if($result['error_code']){
                    $balance_pay = 0;
                }
            }
            //商家会员卡余额退款
            if ($merchant_balance > 0 || $card_give_money > 0) {
                $result = D('Card_new')->add_user_money($order['mer_id'], $order['uid'],  $merchant_balance, $card_give_money, 0, $this->config['shop_alias_name'] . '部分商品退货,增加余额,订单编号' . $order['real_orderid'], $this->config['shop_alias_name'] . '部分商品退货,增加赠送余额,订单编号' . $order['real_orderid']);
                if ($result['error_code']) {
                    $merchant_balance = 0;
                    $card_give_money = 0;
                }
            }
            $data = array();
            $data['reply_content'] = $reply_content;
            $data['reply_time'] = time();
            $data['status'] = 1;
            $data['score_deducte'] = $score_deducte;
            $data['score_used_count'] = $score_used_count;
            $data['balance_pay'] = $balance_pay;
            $data['merchant_balance'] = $merchant_balance;
            $data['card_give_money'] = $card_give_money;
            $data['payment_money'] = $payment_money;
            $data['third_refund_id'] = $third_refund_id;
            
            D('Shop_refund_log')->add(array('refund_id' => $refund_id, 'status' => 1, 'dateline' => time(), 'note' => $reply_content));
            D('Shop_order_refund')->where(array('order_id' => $order_id, 'id' => $refund_id))->save($data);
            
            $count = D('Shop_order_detail')->where(array('order_id' => $order_id, 'refundNum' => 0))->count();
            $rcount = D('Shop_order_refund')->where(array('order_id' => $order_id, 'status' => 0))->count();
            $saveData = array('is_apply_refund' => 0);
            if (empty($count) && empty($rcount)) {
                $saveData['status'] = 4;
                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));
            }
            if ($mdata = D('Merchant_money_list')->field('percent')->where(array('type' => 'shop', 'order_id' => $order['real_orderid']))->find()) {
                $totalMoney = floatval(round(floatval($refund['price']) * (100 - $mdata['percent']) * 0.01, 2));
                D('Merchant_money_list')->use_money($order['mer_id'], $totalMoney, 'shop', $this->config['shop_alias_name'] . '部分商品退货,减少余额,订单编号' . $order['real_orderid'], $order['real_orderid']);
            }
            
            D('Shop_order')->where(array('order_id' => $order_id))->save($saveData);
            exit(json_encode(array('errcode' => 0, 'msg' => 'ok')));
        } else {
            D('Shop_refund_log')->add(array('refund_id' => $refund_id, 'status' => 2, 'dateline' => time(), 'note' => $reply_content));
            $refundDetailList = D('Shop_refund_detail')->field(true)->where(array('refund_id' => $refund_id))->select();
            $orderDetailDB = D('Shop_order_detail');
            foreach ($refundDetailList as $row) {
                $orderDetailDB->where(array('id' => $row['detail_id']))->save(array('refundNum' => 0));
            }
            D('Shop_order_refund')->where(array('order_id' => $order_id, 'id' => $refund_id))->save(array('reply_time' => time(), 'reply_content' => $reply_content, 'status' => 2));
            D('Shop_order')->where(array('order_id' => $order_id))->save(array('is_apply_refund' => 0));
            exit(json_encode(array('errcode' => 0, 'msg' => 'ok')));
        }
    }

	private function shop_refund_detail($now_order, $store_id)
	{
		$order_id  = $now_order['order_id'];

		$mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $store_id))->find();


		$zbw_return = false;
		if($this->config['zbw_key']){

			$zbw_return = true;
			$now_user = D('User')->get_user($this->user_session['uid']);
			$result = D('ZbwErp')->VipRetSheet($now_user['zbw_cardid'],$now_order['balance_pay'],$now_order['score_used_count'],$now_order['real_orderid'], C('config.shop_alias_name') . '商品退款，增加余额，订单编号' . $now_order['real_orderid']);
			if($result['result']){
				$go_refund_param['msg'] .= '退款成功！';
			}else{
				$this->error_tips($result['err']);
			}
		}

		//如果使用了积分 2016-1-15
		if (!$zbw_return && $now_order['score_used_count'] != 0) {
			$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'], C('config.shop_alias_name') . '商品退款，' . C('config.score_name') . '回滚，订单编号' . $now_order['real_orderid']);
			$param = array('refund_time' => time());
			if ($result['error_code']) {
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			D('Shop_order')->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] .= ' '.$result['msg'];
		}

		//平台余额退款
		if (!$zbw_return && $now_order['balance_pay'] != '0.00') {

			$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'], C('config.shop_alias_name') . '商品退款，增加余额，订单编号' . $now_order['real_orderid']);

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			D('Shop_order')->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] .= ' 平台余额退款成功';
		}
		//商家会员卡余额退款
		if ($now_order['merchant_balance'] != '0.00'||$now_order['card_give_money']!='0.00') {
			//$result = D('Member_card')->add_card($now_order['uid'],$now_order['mer_id'],$now_order['merchant_balance'],'退款 ' . $mer_store['name'] . '(' . $order_id . ')  增加余额');
			$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,C('config.shop_alias_name') . '商品退款，增加余额，订单编号' . $now_order['real_orderid'], C('config.shop_alias_name') . '商品退款，增加赠送余额，订单编号' . $now_order['real_orderid']);

			$param = array('refund_time' => time());
			if ($result['error_code']) {
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			D('Shop_order')->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] .= $result['msg'];
		}

		//退款打印
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($now_order['order_id'], 'shop_order', 3);

// 		$msg = ArrayToStr::array_to_str($now_order['order_id'], 'shop_order');
// 		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
// 		$op->printit($this->mer_id, $store_id, $msg, 3);

// 		$str_format = ArrayToStr::print_format($now_order['order_id'], 'shop_order');
// 		foreach ($str_format as $print_id => $print_msg) {
// 			$print_id && $op->printit($this->mer_id, $store_id, $print_msg, 3, $print_id);
// 		}

		$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'shop');
		if ($this->config['sms_shop_cancel_order'] == 1 || $this->config['sms_shop_cancel_order'] == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['userphone'] ? $now_order['userphone'] : $my_user['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_shop_cancel_order'] == 2 || $this->config['sms_shop_cancel_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $mer_store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客' . $now_order['username'] . '的预定订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
			Sms::sendSms($sms_data);
		}

		//退款时销量回滚
		if (($now_order['paid'] == 1 || $now_order['reduce_stock_type'] == 1) && $now_order['is_rollback'] == 0) {
			$goods_obj = D("Shop_goods");
			foreach ($now_order['info'] as $menu) {
				$goods_obj->update_stock($menu, 1);//修改库存
			}
			D('Shop_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_rollback' => 1));
		}
		D("Merchant_store_shop")->where(array('store_id' => $now_order['store_id'], 'sale_count' => array('gt', 0)))->setDec('sale_count', 1);
		//退款时销量回滚

		$go_refund_param['error_code'] = false;
		return $go_refund_param;
	}
}