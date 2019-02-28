<?php
class PayAction extends BaseAction{
	public function check(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！',U('Login/index'));
		}
		if(!in_array($_GET['type'],array('group','meal','takeout','food','recharge','appoint', 'shop','gift'))){
			$this->error_tips('订单来源无法识别，请重试。');
		}
		$group_pay_offline = true;
		if($_GET['type'] == 'group'){
			$now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']),true);
			if($now_order['order_info']['group_share_num']>0||$now_order['order_info']['pin_num']>0)$group_pay_offline=false;
		}else if($_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food'){
			$now_order = D('Meal_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']),true, $_GET['type']);
			$_GET['type']  = 'meal';
		}else if($_GET['type'] == 'recharge'){
			$now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']),true);
		}else if($_GET['type'] == 'appoint'){
			$now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']),true);
		} elseif ($_GET['type'] == 'shop') {
			$now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']),true);
		}elseif($_GET['type'] == 'gift'){
			$now_order = D('Gift_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']),true);
		}else{
			$this->error_tips('非法的订单');
		}
		if($now_order['error'] == 1){
			if($now_order['url']){
				$this->error_tips($now_order['msg'],$now_order['url']);
			}else{
				$this->error_tips($now_order['msg']);
			}
		}

		//判断线下支付
		$pay_offline = D('Percent_rate')->pay_offline($now_order['mer_id'],$_GET['type']);
		$this->assign('pay_offline',$pay_offline&&$group_pay_offline);

		$order_info = $now_order['order_info'];
		if($this->config['open_extra_price']==1){
			$user_score_use_percent=(float)$this->config['user_score_use_percent'];
			$order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
		}else{
			$order_info['order_extra_price'] = 0;
		}
		$this->assign('order_info',$order_info);
		$leveloff=isset($order_info['leveloff']) && !empty($order_info['leveloff']) ? unserialize($order_info['leveloff']) : false;

		$this->assign('leveloff',$leveloff);
		$now_user = D('User')->get_user($this->user_session['uid']);

		if(empty($now_user)){
			$this->error_tips('未获取到您的帐号信息，请重试！');
		}
		$now_user['now_money'] = floatval($now_user['now_money']);
		$this->assign('now_user',$now_user);
		
		if($_GET['type'] != 'recharge'){
			$pay_money = $order_info['order_total_money'] - $now_user['now_money'];
		}else{
			$pay_money = $order_info['order_total_money'];
		}

		//使用积分
		$score_can_use_count = 0;
		$score_deducte = 0;
		$user_score_use_percent = (float)$this->config['user_score_use_percent'];
		//定制元宝判断条件
		//if($this->config['open_extra_pirce']!=0&&$order_info['order_extra_price']!=0) {
			if ($_GET['type'] == 'group' || $_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad' || $_GET['type'] == 'appoint' || $_GET['type'] == 'shop' || $_GET['type'] == 'gift') {
				$type_ = $_GET['type'];
//			if($order_info['business_type']=='foodshop'){
//				$type_ = 'meal';
//			}
				//$score_config = D('Config')->field('name,value')->where('tab_id="user_score"')->getField('name,value');
				$user_score_use_condition = $this->config['user_score_use_condition'];
				$user_score_max_use = D('Percent_rate')->get_max_core_use($order_info['mer_id'], $type_);;
				//$user_score_max_use=$score_config['user_score_max_use'];

				if ($_GET['type'] == 'group') {
					$group_info = D('Group')->where(array('group_id' => $order_info['group_id']))->find();
					if ($group_info['score_use']) {
						if ($group_info['group_max_score_use'] != 0) {
							$user_score_max_use = $group_info['group_max_score_use'];
						}
					} else {
						$user_score_max_use = 0;
					}
				}

				$score_max_deducte = bcdiv($user_score_max_use, $user_score_use_percent, 2);
				if ($user_score_use_percent > 0 && $score_max_deducte > 0 && $user_score_use_condition > 0 && $now_user['score_count'] > 0) {   //如果设置没有错误
					$total_ = $order_info['order_total_money'];

					if ($total_ >= $user_score_use_condition) {             //判断是否够使用积分的条件，即这个订单的总额是否大于规定的金额
						if ($total_ > $score_max_deducte) {                    //判断积分最大抵扣金额是否比这个订单的总额大
							$score_can_use_count = (int)($now_user['score_count'] > $user_score_max_use ? $user_score_max_use : $now_user['score_count']);//如果用户积分大于每个订单的积分可使用数 则按可使用数算
							//$score_deducte = sprintf("%.2f",substr(sprintf("%.3f", (float)$score_can_use_count/$user_score_use_percent), 0, -2));
							$score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
							$score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
						} else {
							$score_can_use_count = $total_ * $user_score_use_percent > $now_user['score_count'] ? $now_user['score_count'] : $total_ * $user_score_use_percent;
							$score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
							$score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
						}
					}
				}
				if ($_GET['type'] == 'gift') {
					$score_can_use_count = $order_info['total_integral'];
				}

			}

		//积分冻结
		if($now_user['forzen_score'] == 1){
			$score_can_use_count = 0;
			$score_deducte = 0;
		}
		//}
		$this->assign('score_can_use_count', $score_can_use_count);
		$this->assign('user_score_use_percent', $user_score_use_percent);
		$this->assign('score_deducte', $score_deducte);
		$this->assign('score_count', $now_user['score_count']);

		$this->assign('pay_money',$pay_money);
		//调出支付方式
		$notOnline = intval($_GET['notOnline']);
		if($_GET['type'] != 'recharge' && $_GET['type'] != 'appoint'){
			$notOffline = intval($_GET['notOffline']);
		}else{
			$notOffline = 1;
		}
		
		$now_merchant = D('Merchant')->get_info($order_info['mer_id']);
		if ($now_merchant) {
			$notOffline = ($pay_offline && $now_merchant['is_offline'] == 1) ? 0 : 1;
		}
		$this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
		switch($this->config['merchant_ownpay']){
			case '0':
				$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				break;
			case '1':
				$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				break;
			case '2':
				$pay_method = array();
				$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$now_merchant['mer_id']))->find();
				foreach($merchant_ownpay as $ownKey=>$ownValue){
					$ownValueArr = unserialize($ownValue);
					if($ownValueArr['open']){
						$ownValueArr['is_own'] = true;
						$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
					}
				}
				unset($pay_method['weixin']);
				if(empty($pay_method)){
					$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				}
				break;			
		}

		unset($pay_method['weixinapp']);
		if($this->config['open_allinyun']==1){
			$can_pay_allinyun = true;
			$allinyun = M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find();
			if($order_info['mer_id'] && $_GET['type']!='recharge'){
				$allinyun_mer =  D('Deposit')->get_merchant_info($order_info['mer_id'],1);
				if( $allinyun_mer['status']==1){
					$can_pay_allinyun = true;
				}else{
					$can_pay_allinyun = false;
				}
			}


			if($allinyun['bizUserId'] && $can_pay_allinyun){
				import('@.ORG.AccountDeposit.AccountDeposit');
				$deposit = new AccountDeposit('Allinyun');
				$allyun = $deposit->getDeposit();
				$payinfo = $allyun->getPayMethod();
				$bankCode = $payinfo['bank_code'];
				$this->assign('bankCode',$bankCode);
				$pay_method['allinyun_gateway']['name'] ='云商通网关';
				$pay_method['allinyun_alipay']['name'] ='云商通支付宝';
				$this->assign('balance_disable',1);
				// $_GET['type']!='recharge' && $pay_method['allinyun_balance']['name'] ='云商通余额';
			}
		}

		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		$this->assign('pay_method',$pay_method);
		
		$this->display();
	}
	protected function getPayName($label){
		$payName = array(
			'weixin' => '微信支付',
			'tenpay' => '财付通支付',
			'yeepay' => '银行卡支付(易宝支付)',
			'allinpay' => '银行卡支付(通联支付)',
			'chinabank' => '银行卡支付(网银在线)',
		);
		return $payName[$label];
	}
	public function go_pay(){

		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！',U('Login/index'));
		}
		if(!in_array($_POST['order_type'],array('group','meal','takeout','food','recharge','appoint','waimai','waimai-recharge', 'shop','gift'))){
			$this->error_tips('订单来源无法识别，请重试。');
		}
		if($_POST['order_type'] == 'group'){
			$now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
		}else if($_POST['order_type'] == 'meal' || $_POST['order_type'] == 'takeout' || $_POST['order_type'] == 'food'){
			$now_order = D('Meal_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true, $_POST['order_type']);
		}else if($_POST['order_type'] == 'recharge'){
			$now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
		}else if($_POST['order_type'] == 'appoint'){
			$now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
		}else if($_POST['order_type'] == 'waimai'){
			$now_order = D('Waimai_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
			if ($now_order['order_info']['pay_type'] !== $_POST['pay_type']) {
				$this->error_tips('非法的订单');
			}
		}else if($_POST['order_type'] == 'waimai-recharge'){
			$now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
		} elseif ($_POST['order_type'] == 'shop') {
			$now_order = D('Shop_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
		} elseif ($_POST['order_type'] == 'gift') {
			$now_order = D('Gift_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
		}else{
			$this->error_tips('非法的订单');
		}
		if($now_order['error'] == 1){
			if($now_order['url']){
				$this->error_tips($now_order['msg'],$now_order['url']);
			}else{
				$this->error_tips($now_order['msg']);
			}
		}
		$order_info = $now_order['order_info'];

		//用户信息
		$now_user = D('User')->get_user($this->user_session['uid']);

		if(empty($now_user)){
			$this->error_tips('未获取到您的帐号信息，请重试！');
		}
                
                //判断积分是否够用 防止支付同时积分被改动
		
		if ($_POST['use_score']) {
			if($now_user['score_count']<$_POST['score_used_count']){
				$this->error_tips('账户'.$this->config['score_name'].'不够，请重试！');
			}
			if($this->config['open_extra_price']){

				if(isset($order_info['extra_price']) && $order_info['extra_price']>0 &&$order_info['extra_price']<$_POST['score_deducte']){
					$this->error_tips($this->config['score_name'].'抵扣金额错误！');
				}
			}else{
				if($order_info['order_total_money']<$_POST['score_deducte']){
					$this->error_tips($this->config['score_name'].'抵扣金额错误！');
				}
			}

			$order_info['score_used_count']=$_POST['score_used_count'];
			$order_info['score_deducte']=$_POST['score_deducte'];
		}else{
			$order_info['score_used_count']=0;
			$order_info['score_deducte']=0;
		}
		if($now_user['forzen_score'] == 1){
			$order_info['score_used_count']=0;
			$order_info['score_deducte']=0;
		}

		if($_POST['use_balance']==0||!isset($_POST['use_balance'])){
			$order_info['use_balance'] = 0;
		}else{
			$order_info['use_balance'] = 1;
		}
		//dump($order_info);die;
		
		//如果用户存在余额或使用了优惠券，则保存至订单信息。如果金额满足订单总价，则实时扣除并返回支付成功！若不够则不实时扣除，防止用户在付款过程中取消支付
		if($order_info['order_type'] == 'group'){
			$save_result = D('Group_order')->web_befor_pay($order_info,$now_user);
		}else if($order_info['order_type'] == 'meal' || $order_info['order_type'] == 'takeout' || $order_info['order_type'] == 'food'){
			$save_result = D('Meal_order')->web_befor_pay($order_info,$now_user);
		}else if($order_info['order_type'] == 'recharge'){
			$save_result = D('User_recharge_order')->web_befor_pay($order_info,$now_user);
		}else if($order_info['order_type'] == 'appoint'){
			$save_result = D('Appoint_order')->web_befor_pay($order_info,$now_user);
		}else if($order_info['order_type'] == 'waimai'){
			$save_result = D('Waimai_order')->web_befor_pay($order_info,$now_user);
		}else if($order_info['order_type'] == 'waimai-recharge'){
			$save_result = D('User_recharge_order')->web_befor_pay($order_info,$now_user);
		} elseif($order_info['order_type'] == 'shop') {
			$save_result = D('Shop_order')->web_befor_pay($order_info,$now_user);
		}elseif($order_info['order_type'] == 'gift'){
			$save_result = D('Gift_order')->web_befor_pay($order_info,$now_user);
		}
		
		if($save_result['error_code']){
			$this->error($save_result['msg']);exit();
		}else if($save_result['url']){
			$this->assign('jumpUrl',$save_result['url']);
			$this->success($save_result['msg']);exit();
		}
		//需要支付的钱
		$pay_money = $save_result['pay_money'];
		
		$this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
		if(in_array($order_info['order_type'],array('group','meal','takeout','food','appoint','waimai','shop')) && $order_info['mer_id']){
			$mer_id = $order_info['mer_id'];
		}else{
			$this->config['merchant_ownpay'] = 0;
		}
		switch($this->config['merchant_ownpay']){
			case '0':
				$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				break;
			case '1':
				$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
				foreach($merchant_ownpay as $ownKey=>$ownValue){
					$ownValueArr = unserialize($ownValue);
					if($ownValueArr['open'] && $ownKey != 'weixin'){	//PC端不使用商家的微信支付
						$ownValueArr['is_own'] = true;
						$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
					}
				}
				break;
			case '2':
				$pay_method = array();
				$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
				foreach($merchant_ownpay as $ownKey=>$ownValue){
					$ownValueArr = unserialize($ownValue);
					if($ownValueArr['open']){
						$ownValueArr['is_own'] = true;
						$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
					}
				}
				unset($pay_method['weixin']);
				if(empty($pay_method)){
					$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				}
				break;			
		}


		//用pay_id代替参数order_id传进在线支付
		$order_id = $order_info['order_id'];
		if ($_POST['order_type'] == 'takeout' || $_POST['order_type'] == 'food' || $_POST['order_type'] == 'foodPad') {
			$order_table = 'Meal_order';
		}else if($_POST['order_type']=='recharge'){
			$order_table = 'User_recharge_order';
		}else{
			$order_table = ucfirst($_POST['order_type']).'_order';
		}
		//if($_POST['pay_type']!='offline'){
		$nowtime = date("ymdHis");
		$orderid = $nowtime.rand(10,99).sprintf("%08d",$this->user_session['uid']);
		$data_tmp['pay_type'] = $_POST['pay_type'] ? $_POST['pay_type']: '';
		$data_tmp['order_type'] = $_POST['order_type'];
		$data_tmp['order_id'] = $order_id;
		$data_tmp['orderid'] = $orderid;
		$data_tmp['addtime'] = $nowtime;
		if(!D('Tmp_orderid')->add($data_tmp)){
			$this->error_tips('更新失败，请联系管理员');
		}
		$save_pay_id = D($order_table)->where(array("order_id"=>$order_id))->setField('orderid',$orderid);
		if(!$save_pay_id){
			$this->error_tips('更新失败，请联系管理员');
		}else{
			$order_info['order_id']=$orderid;
		}
		//}

		if(strpos($_POST['pay_type'],'allinyun')!==false || ( ($_POST['pay_type']=='weixin' || $_POST['pay_type']=='alipay' ) && $this->config['open_allinyun']==1)){
			import('@.ORG.AccountDeposit.AccountDeposit');
			$deposit = new AccountDeposit('Allinyun');
			$allyun = $deposit->getDeposit();

			$allinyun_user = D('Deposit')->allinyun_user($this->user_session['uid']);

			if($allinyun_user){
				$allyun->setUser($allinyun_user);
				$pay_type = explode('_',$_POST['pay_type']);
				$order_info['payerId'] = $allinyun_user['bizUserId'];
				$order_info['openid'] = $this->user_session['openid'] ;
				if($_POST['pay_type']=='weixin'){
					$order_info['pay_type'] = 'scan_weixin';
				}else if($_POST['pay_type']=='allinyun_alipay'){
					$order_info['pay_type'] = 'scan_alipay';
				}else{

					$order_info['pay_type'] = $pay_type[1];
					$_POST['bank_code'] && $order_info['bank_code'] = $_POST['bank_code'];
				}
				$order_info['source'] = 1;
				$order_info['platform'] = 'pc';
				if($order_info['mer_id']){
					$allinyun_mer =  D('Deposit')->get_merchant_info($order_info['mer_id'],1);
					$order_info['recieverId'] =  $allinyun_mer['bizUserId'];
				}

				$allyun->Pay($_POST['order_type'],$orderid,$pay_money,$order_info);die;
			}

		}
		if($this->config['open_juhepay']==1){

			$order_info['openid'] = $this->user_session['openid'];
			if($_POST['pay_type']=='weixin'){
				$order_info['pay_type'] ='weixin_scan';
			}else{
				$order_info['pay_type'] ='alipay_scan';
			}

			$order_info['order_total_money'] =  round($pay_money,2);
			$order_info['order_id'] =   $_POST['order_type'].'_'.$order_info['order_id'];
			import('@.ORG.LowFeePay');
			$lowfeepay = new LowFeePay('juhepay');
			if($mer_id){
				$mer_juhe = M('Merchant_juhe_config')->where(array('mer_id'=>$mer_id))->find();
				if(!empty($mer_juhe)){
					$lowfeepay->userId =$mer_juhe['userid'];
					$order_info['is_own'] =1 ;
					$order_info['order_id'] =  $order_info['order_id'].'_1';
				}
			}
			$returnArr= $lowfeepay->pay($order_info);
			$returnArr['confirm_pay_url'] = U('LowFeePayNotice/order_back_notice',array('order_type'=>$order_info['order_type']))."&source=0&order_id={$orderid}&uid={$this->user_session['uid']}";
			$returnArr['orderid'] =$orderid;
			$this->header_json();
			echo json_encode($returnArr);die;
		}else{
			if(empty($pay_method)){
				$this->error_tips('系统管理员没开启任一一种支付方式！');
			}
			if(empty($pay_method[$_POST['pay_type']]) && strpos($_POST['pay_type'],'allinyun')===false ){
				$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
			}
			if($order_info['order_type'] == 'recharge' && $_POST['pay_type'] == 'offline'){
				$this->error_tips('在线充值只能使用在线支付');
			}
			$pay_class_name = ucfirst($_POST['pay_type']);
			$import_result = import('@.ORG.pay.'.$pay_class_name);
			if(empty($import_result) && strpos($_POST['pay_type'],'allinyun')===false){
				$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
			}
			$pay_class = new $pay_class_name($order_info,$pay_money,$_POST['pay_type'],$pay_method[$_POST['pay_type']]['config'],$this->user_session,0);
			$go_pay_param = $pay_class->pay();
		}


		if(empty($go_pay_param['error'])){
			if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
				$returnArr = array(
					'status'=>1,
					'info'=>$go_pay_param['qrcode'],
					'orderid'=>$orderid,
				);
				$this->header_json();
				echo json_encode($returnArr);
				exit;
			}else if(!empty($go_pay_param['url'])){
				$this->assign('url',$go_pay_param['url']);
			}else if(!empty($go_pay_param['form'])){
				$this->assign('form',$go_pay_param['form']);
			}else{
				$this->error_tips('调用支付发生错误，请重试。');
			}
		}else{
			$this->error_tips($go_pay_param['msg']);
		}
		
		$this->display();
	}
	
	//异步通知
	public function notify_url(){
		$pay_method = D('Config')->get_pay_method();
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		if(empty($pay_method[$_GET['pay_type']])){
			$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
		}
		
		$pay_class_name = ucfirst($_GET['pay_type']);
		$import_result = import('@.ORG.pay.'.$pay_class_name);
		if(empty($import_result)){
			$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
		}
		
		$pay_class = new $pay_class_name('','',$_GET['pay_type'],$pay_method[$_GET['pay_type']]['config'],$this->user_session,0);
		$notify_return = $pay_class->notice_url();
		
		if(empty($notify_return['error'])){

		}else{
			$this->error_tips($notify_return['msg']);
		}
	}
	
	//跳转通知
	public function return_url(){
		$pay_type = isset($_GET['paytype']) ? $_GET['paytype'] : $_GET['pay_type'];
		$this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
		switch($this->config['merchant_ownpay']){
			case '0':
				$pay_method = D('Config')->get_pay_method();
				break;
			case '1':
				$pay_method = D('Config')->get_pay_method();
				if($_GET['own_mer_id']){
					$mer_id = $_GET['own_mer_id'];
				}else if($_SESSION['own_mer_id']){
					$mer_id = $_SESSION['own_mer_id'];
					unset($_SESSION['own_mer_id']);
				}
				if($mer_id){
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
					foreach($merchant_ownpay as $ownKey=>$ownValue){
						$ownValueArr = unserialize($ownValue);
						if($ownValueArr['open']){
							$ownValueArr['is_own'] = true;
							$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
						}
					}
				}				
				break;
			case '2':
				$pay_method = array();
				if($_GET['own_mer_id']){
					$mer_id = $_GET['own_mer_id'];
				}else if($_SESSION['own_mer_id']){
					$mer_id = $_SESSION['own_mer_id'];
					unset($_SESSION['own_mer_id']);
				}
				if($mer_id){
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
					foreach($merchant_ownpay as $ownKey=>$ownValue){
						$ownValueArr = unserialize($ownValue);
						if($ownValueArr['open']){
							$ownValueArr['is_own'] = true;
							$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
						}
					}
					unset($pay_method['weixin']);
					if(empty($pay_method)){
						$pay_method = D('Config')->get_pay_method();
					}
				}
				break;			
		}
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		if(empty($pay_method[$pay_type])){
			$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
		}

		$pay_class_name = ucfirst($pay_type);
		$import_result = import('@.ORG.pay.'.$pay_class_name);
		if(empty($import_result)){
			$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
		}
	
		$pay_class = new $pay_class_name('','',$pay_type,$pay_method[$pay_type]['config'],$this->user_session,0);
		$get_pay_param = $pay_class->return_url();
		$get_pay_param['order_param']['return']=1;

		$offline = $pay_type!='offline'?false:true;
		if(empty($get_pay_param['error'])){
			if($get_pay_param['order_param']['order_type'] == 'group'){
				$now_order = $this->get_orderid('Group_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Group_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'meal' || $get_pay_param['order_param']['order_type'] == 'takeout' || $get_pay_param['order_param']['order_type'] == 'food'){
				$now_order = $this->get_orderid('Meal_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Meal_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'recharge'){
				$now_order = $this->get_orderid('User_recharge_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'appoint'){
				$now_order = $this->get_orderid('Appoint_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'waimai'){
				$now_order = $this->get_orderid('Waimai_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Waimai_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'waimai-recharge'){
				$now_order = $this->get_orderid('User_recharge_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'shop'){
				$now_order = $this->get_orderid('Shop_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Shop_order')->after_pay($get_pay_param['order_param']);			
			}else{
				$this->error_tips('订单类型非法！请重新下单。');
			}
			// if($get_pay_param['order_param']['pay_type'] == 'yeepay' && $pay_info['url']){
				// $pay_info['url'] = str_replace('/source/web_yeepay.php','/index.php',$pay_info['url']);
			// }
			if($pay_info['url']){
				$pay_info['url'] = preg_replace('#/source/(\w+).php#','/index.php',$pay_info['url']);
			}
			$urltype = isset($_GET['urltype']) ? $_GET['urltype'] : '';
			if(empty($pay_info['error'])){
				if($get_pay_param['order_param']['pay_type'] == 'weixin'){
					exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
				} elseif ($get_pay_param['order_param']['pay_type'] == 'baidu') {//百度的异步通知返回
                	exit("<html><head><meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\"></head><body><h1>这是一个return_url页面</h1></body></html>");
                } elseif ('unionpay' == $get_pay_param['order_param']['pay_type'] && $urltype == 'back') {
                	exit("验签成功");
                }
				if(!empty($pay_info['url'])){
					$this->assign('jumpUrl',$pay_info['url']);
					$this->success('订单付款成功！现在跳转.');
					exit();
				}
			}
			if(empty($pay_info['url'])){
				$this->error_tips($pay_info['msg']);
			}else{
                if ($pay_info['error'] && $urltype == 'front') {
                	$this->redirect($pay_info['url']);
                	exit;
                }
				$this->error_tips($pay_info['msg'],$pay_info['url']);
			}
		}else{
			$this->error_tips($get_pay_param['msg']);
		}
	}

	public function get_orderid($table,$orderid,$offline=0){
		$order =  D($table);
		$tmp_orderid = D('Tmp_orderid');
		if($offline){
			$now_order = $order->where(array('orderid'=>$orderid))->find();
		}else{
			$now_order = $order->where(array('orderid'=>$orderid))->find();
			if(empty($now_order)){
				$res = $tmp_orderid->where(array('orderid'=>$orderid))->find();
				$now_order = $order->where(array('order_id'=>$res['order_id']))->find();
				$order->where(array('order_id'=>$res['order_id']))->setField('orderid',$orderid);
				$now_order['orderid']=$orderid;
			}
		}
		if(empty($now_order)){
			$this->error_tips('该订单不存在');
		}else{
			//$tmp_orderid->where(array('order_id'=>$now_order['order_id']))->delete();
		}
		return $now_order;
	}


	//微信同步回调页面
	public function weixin_back(){
		switch($_GET['order_type']){
			case 'group':
				//$now_order = D('Group_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('Group_order',$_GET['order_id']);
				break;
			case 'meal':
			case 'takeout':
			case 'food':
				//$now_order = D('Meal_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('Meal_order',$_GET['order_id']);
				break;
			case 'recharge':
				//$now_order = D('User_recharge_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('User_recharge_order',$_GET['order_id']);
				break;
			case 'appoint':
				//$now_order = D('Appoint_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('Appoint_order',$_GET['order_id']);
				break;
			case 'waimai':
				//$now_order = D('Waimai_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('Waimai_order',$_GET['order_id']);
				break;
			case 'shop':
				//$now_order = D('Waimai_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('Shop_order',$_GET['order_id']);
				break;
			default:
				$this->error_tips('非法的订单');
		}

		if(empty($now_order)){
			$this->error_tips('该订单不存在');
		}
		$now_order['order_type'] = $_GET['order_type'];
		if($now_order['paid']){
			switch($_GET['order_type']){
				case 'group':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=group_order_view&order_id='.$now_order['order_id'];
					break;
				case 'meal':
				case 'takeout':
				case 'food':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=meal_order_view&order_id='.$now_order['order_id'];
					break;
				case 'recharge':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Credit&a=index';
					break;
				case 'appoint':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=appoint_order_view&order_id='.$now_order['order_id'];
					break;
				case 'waimai':
					$redirctUrl = C('config.site_url').'/index.php?g=Waimai&c=Order&a=detail&order_id='.$now_order['order_id'];
					break;
				case 'shop':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=shop_order_view&order_id='.$now_order['order_id'];
					break;
			}
			redirect($redirctUrl);exit;
		}else if($_GET['pay_type'] == 'weifutong'){
			$this->error_tips('您还未支付');
		}
		$tmpid = $now_order['order_id'];
		$now_order['order_id']  =   $now_order['orderid'];
		$import_result = import('@.ORG.pay.Weixin');
		$pay_method = D('Config')->get_pay_method();
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		$pay_class = new Weixin($now_order,0,'weixin',$pay_method['weixin']['config'],$this->user_session,1);
		$go_query_param = $pay_class->query_order();
		if($go_query_param['error'] === 0){
			switch($_GET['order_type']){
				case 'group':
					D('Group_order')->after_pay($go_query_param['order_param']);
					break;
				case 'meal':
				case 'takeout':
				case 'food':
					D('Meal_order')->after_pay($go_query_param['order_param']);
					break;
				case 'recharge':
					D('User_recharge_order')->after_pay($go_query_param['order_param']);
					break;
				case 'appoint':
					D('Appoint_order')->after_pay($go_query_param['order_param']);
					break;
				case 'waimai':
					D('Waimai_order')->after_pay($go_query_param['order_param']);
					break;
				case 'shop':
					D('Shop_order')->after_pay($go_query_param['order_param']);
					break;
			}
		}
		$now_order['order_id']  =   $tmpid;
		switch($_GET['order_type']){
			case 'group':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=group_order_view&order_id='.$now_order['order_id'];
				break;
			case 'meal':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=meal_order_view&order_id='.$now_order['order_id'];
				break;
			case 'recharge':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Credit&a=index';
				break;
			case 'appoint':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=appoint_order_view&order_id='.$now_order['order_id'];
				break;
			case 'waimai':
				$redirctUrl = C('config.site_url').'/index.php?g=Waimai&c=Order&a=detail&order_id='.$now_order['order_id'];
				break;
			case 'shop':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=shop_order_view&order_id='.$now_order['order_id'];
				break;
		}
		redirect($redirctUrl);
	}
	//支付宝支付同步回调
	public function alipay_return(){
		$order_id_arr = explode('_',$_GET['out_trade_no']);				
		$order_type = $order_id_arr[0];
		$order_id = $order_id_arr[1];
		switch($order_type){
			case 'group':
				$now_order = D('Group_order')->where(array('orderid'=>$order_id))->find();
				break;
			case 'meal':
			case 'takeout':
			case 'food':
				$now_order = D('Meal_order')->where(array('orderid'=>$order_id))->find();
				break;
			case 'recharge':
				$now_order = D('User_recharge_order')->where(array('orderid'=>$order_id))->find();
				break;
			case 'appoint':
				$now_order = D('Appoint_order')->where(array('orderid'=>$order_id))->find();
				break;
			case 'waimai':
				$now_order = D('Waimai_order')->where(array('orderid'=>$order_id))->find();
				break;
			case 'shop':
				$now_order = D('Shop_order')->where(array('orderid'=>$order_id))->find();
				break;
			case 'ticket':
				$now_order = D('Scenic_order')->where(array('orderid'=>$order_id))->find();
				break;
			default:
				$this->error('非法的订单');
		}
		if($now_order['paid'] && ($order_type == 'ticket' && $now_order['paid'] == 2)){
			switch($order_type){
				case 'group':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=group_order_view&order_id='.$now_order['order_id'];
					break;
				case 'meal':
				case 'takeout':
				case 'food':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=meal_order_view&order_id='.$now_order['order_id'];
					break;
				case 'recharge':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Credit&a=index';
					break;
				case 'appoint':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=appoint_order_view&order_id='.$now_order['order_id'];
					break;
				case 'waimai':
					$redirctUrl = C('config.site_url').'/index.php?g=Waimai&c=Order&a=detail&order_id='.$now_order['order_id'];
					break;
				case 'shop':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=shop_order_view&order_id='.$now_order['order_id'];
					break;
				case 'ticket':
					$redirctUrl = C('config.site_url').'/scenic.php?g=Scenic&c=Scenic_user&a=ticket_order_details&order_id='.$now_order['order_id'];
					break;
			}
			redirect($redirctUrl);exit;
		}
		$pay_method = D('Config')->get_pay_method();
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		$import_result = import('@.ORG.pay.Alipay');
		$pay_class = new Alipay('','',$order_type,$pay_method['alipay']['config'],$this->user_session,0);
		$go_query_param = $pay_class->query_order();
		if($go_query_param['error'] === 0){
			switch($order_type){
				case 'group':
					D('Group_order')->after_pay($go_query_param['order_param']);
					break;
				case 'meal':
				case 'takeout':
				case 'food':
					D('Meal_order')->after_pay($go_query_param['order_param']);
					break;
				case 'recharge':
					D('User_recharge_order')->after_pay($go_query_param['order_param']);
					break;
				case 'appoint':
					D('Appoint_order')->after_pay($go_query_param['order_param']);
					break;
				case 'waimai':
					D('Waimai_order')->after_pay($go_query_param['order_param']);
					break;
				case 'shop':
					D('Shop_order')->after_pay($go_query_param['order_param']);
					break;
				case 'ticket':
					D('Scenic_order')->after_pay($go_query_param['order_param']);
					break;
			}
		}
		switch($order_type){
			case 'group':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=group_order_view&order_id='.$now_order['order_id'];
				break;
			case 'meal':
			case 'takeout':
			case 'food':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=meal_order_view&order_id='.$now_order['order_id'];
				break;
			case 'recharge':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Credit&a=index&order_id='.$now_order['order_id'];
				break;
			case 'appoint':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=appoint_order_view&order_id='.$now_order['order_id'];
				break;
			case 'waimai':
				$redirctUrl = C('config.site_url').'/index.php?g=Waimai&c=Order&a=detail&order_id='.$now_order['order_id'];
				break;
			case 'shop':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=shop_order_view&order_id='.$now_order['order_id'];
				break;
			case 'ticket':
				$redirctUrl = C('config.site_url').'/scenic.php?g=Scenic&c=Scenic_user&a=ticket_order_details&order_id='.$now_order['order_id'];
				break;
		}
		redirect($redirctUrl);
	}
	
	//百度支付同步通知
	public function baidu_back()
	{
		$pay_type = 'baidu';
		$this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
		switch($this->config['merchant_ownpay']){
			case '0':
				$pay_method = D('Config')->get_pay_method();
				break;
			case '1':
				$pay_method = D('Config')->get_pay_method();
				if($_GET['own_mer_id']){
					$mer_id = $_GET['own_mer_id'];
				}else if($_SESSION['own_mer_id']){
					$mer_id = $_SESSION['own_mer_id'];
					unset($_SESSION['own_mer_id']);
				}
				if($mer_id){
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
					foreach($merchant_ownpay as $ownKey=>$ownValue){
						$ownValueArr = unserialize($ownValue);
						if($ownValueArr['open']){
							$ownValueArr['is_own'] = true;
							$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
						}
					}
				}				
				break;
			case '2':
				$pay_method = array();
				if($_GET['own_mer_id']){
					$mer_id = $_GET['own_mer_id'];
				}else if($_SESSION['own_mer_id']){
					$mer_id = $_SESSION['own_mer_id'];
					unset($_SESSION['own_mer_id']);
				}
				if($mer_id){
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
					foreach($merchant_ownpay as $ownKey=>$ownValue){
						$ownValueArr = unserialize($ownValue);
						if($ownValueArr['open']){
							$ownValueArr['is_own'] = true;
							$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
						}
					}
					unset($pay_method['weixin']);
					if(empty($pay_method)){
						$pay_method = D('Config')->get_pay_method();
					}
				}
				break;			
		}
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		if(empty($pay_method[$pay_type])){
			$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
		}

		$pay_class_name = ucfirst($pay_type);
		$import_result = import('@.ORG.pay.'.$pay_class_name);
		if(empty($import_result)){
			$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
		}
	
		$pay_class = new $pay_class_name('','',$pay_type,$pay_method[$pay_type]['config'],$this->user_session,0);
		$get_pay_param = $pay_class->return_url();
		if($pay_type!='offline'){
			$get_pay_param['order_param']['return']=1;
		}
		if(empty($get_pay_param['error'])){
			
			switch($get_pay_param['order_param']['order_type']){
				case 'group':
					$now_order = D('Group_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				case 'meal':
				case 'takeout':
				case 'food':
					$now_order = D('Meal_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				case 'recharge':
					$now_order = D('User_recharge_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				case 'appoint':
					$now_order = D('Appoint_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				case 'waimai':
					$now_order = D('Waimai_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				case 'shop':
					$now_order = D('Shop_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				default:
					$this->error_tips('非法的订单');
			}
			
			if(empty($now_order)){
				$this->error_tips('该订单不存在');
			}
			$now_order['order_type'] = $get_pay_param['order_param']['order_type'];
			if($now_order['paid']){
				switch($get_pay_param['order_param']['order_type']){
					case 'group':
						$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=group_order_view&order_id='.$now_order['order_id'];
						break;
					case 'meal':
					case 'takeout':
					case 'food':
						$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=meal_order_view&order_id='.$now_order['order_id'];
						break;
					case 'recharge':
						$redirctUrl = C('config.site_url').'/index.php?g=User&c=Credit&a=index';
						break;
					case 'appoint':
						$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=appoint_order_view&order_id='.$now_order['order_id'];
						break;
					case 'waimai':
						$redirctUrl = C('config.site_url').'/index.php?g=Waimai&c=Order&a=detail&order_id='.$now_order['order_id'];
						break;
					case 'shop':
						$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=shop_order_view&order_id='.$now_order['order_id'];
						break;
				}
				redirect($redirctUrl);exit;
			}
			
			if($get_pay_param['order_param']['order_type'] == 'group'){
				$pay_info = D('Group_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'meal' || $get_pay_param['order_param']['order_type'] == 'takeout' || $get_pay_param['order_param']['order_type'] == 'food'){
				$pay_info = D('Meal_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'recharge'){
				$pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'appoint'){
				$pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'waimai'){
				$pay_info = D('Waimai_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'waimai-recharge'){
				$pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);			
			} elseif ($get_pay_param['order_param']['order_type'] == 'shop') {
				$pay_info = D('Shop_order')->after_pay($get_pay_param['order_param']);			
			}else{
				$this->error_tips('订单类型非法！请重新下单。');
			}
			
			if($pay_info['url']){
				$pay_info['url'] = preg_replace('#/source/(\w+).php#','/index.php',$pay_info['url']);
			} 
			if(empty($pay_info['error'])){
				if(!empty($pay_info['url'])){
					$this->assign('jumpUrl',$pay_info['url']);
					$this->success('订单付款成功！现在跳转.');
					exit();
				}
			}
			if(empty($pay_info['url'])){
				$this->error_tips($pay_info['msg']);
			}else{
				$this->error_tips($pay_info['msg'],$pay_info['url']);
			}
		}else{
			$this->error_tips($get_pay_param['msg']);
		}
	}
}
?>