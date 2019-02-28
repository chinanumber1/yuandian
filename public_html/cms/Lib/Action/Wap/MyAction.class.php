<?php
class MyAction extends BaseAction{
	public $now_user;
	public function __construct(){
		parent::__construct();
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				$this->error_tips('请先进行登录！',U('Login/index',$location_param));
			}else{
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				redirect(U('Login/index',$location_param));
			}
		}
		
		if(C('config.mdd_api_url')){
			import('@.ORG.mdd_user');
			$mdd_user = new mdd_user();
			$mdd_result = $mdd_user->select($this->user_session['uid']);
		}

		$now_user = D('User')->get_user($this->user_session['uid']);
		if(empty($now_user)){
			session('user',null);
			$this->error_tips('未获取到您的帐号信息，请重新登录！',U('Login/index'));
		}
		if(!$now_user['status']){
			session('user',null);
			$this->error_tips('您的账号被禁止登录',U('Login/index'));
		}
		$now_user['now_money'] = floatval($now_user['now_money']);
		$now_user['now_money_two'] = number_format(floatval($now_user['now_money']),2);
		$level = M('User_level')->getField('level,lname');
		$now_user['lname'] = $level[$now_user['level']];
		if( $this->config['zbw_key']){
			$zbw_user = D('ZbwErp')->sync_data($this->user_session['uid']);
		}
		if($this->config['open_allinyun']==1){
			$allinyun = M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find();
			if($allinyun['bizUserId']){
				import('@.ORG.AccountDeposit.AccountDeposit');
				$deposit = new AccountDeposit('Allinyun');
				$allyun = $deposit->getDeposit();
				// $allinyun = M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find();
				$allyun->setUser($allinyun);
				$allinyun_user_info = $allyun->queryBalance();
				$now_user['now_money'] = $allinyun_user_info['signedResult']['allAmount']/100;

			}
		}
		$this->now_user = $now_user;
		$this->assign('now_user',$now_user);
		//$this->assign('level',$level);

		//二次验证时间
		if($times = D('Verify_limit')->field('end_time')->where(array('uid'=>$this->user_session['uid'],'times'=>array('lt',2)))->find()){
			$_SESSION['user']['verify_end_time']=$times['end_time'];
		}

	}

	//	新的个人中心页面
	public function index(){
		if($this->config['now_scenic'] == 2){
			redirect(U('Scenic_user/index'));
		}
		
		$uid	=	$this->user_session['uid'];

		//	商家优惠券
//		$mer_list = D('Member_card_coupon')->get_all_coupon($uid);
		$mer_list = D('Card_new_coupon')->get_user_all_coupon_list($uid,1);
		if($mer_list){
			$mer_number	=	count($mer_list);
		}else{
			$mer_number = 0;
		}
		$this->assign('mer_number',$mer_number);
		//	平台优惠券
		$coupon_list = D('System_coupon')->get_user_coupon_list($uid,$this->user_session['phone'],1);
		$coupon_number	=	count($coupon_list);
		$this->assign('coupon_number',$coupon_number);
		//	统计我参与的活动
		$sql	=	"SELECT COUNT(*) AS tp_count FROM `pigcms_extension_activity_record` `ear`,`pigcms_extension_activity_list` `eal`,`pigcms_merchant` `m` WHERE(`ear`.`activity_list_id` = `eal`.`pigcms_id` AND `eal`.`mer_id` = `m`.`mer_id`AND `ear`.`uid` = '$uid') GROUP BY `eal`.`pigcms_id` ";
		$mod = new Model();
		$result = $mod->query($sql);
		$activity_number	=	count($result);
		$this->assign('activity_number',$activity_number);
		//	统计我的会员卡
		//$card_number = D('Member_card_set')->get_all_card_count($uid);
		$sql = 'SELECT c.card_id,c.status,cl.status as clstatus,c.bg,c.diybg,m.name,c.discount,cl.id as cardid,cl.card_money,cl.card_money_give,m.pic_info,m.mer_id FROM '
				.C('DB_PREFIX').'card_userlist `cl`  left join '
				.C('DB_PREFIX').'card_new `c` on cl.mer_id = c.mer_id left join '
				.C('DB_PREFIX').'merchant m on c.mer_id  = m.mer_id WHERE ( cl.uid = '.$uid.' AND c.status=1 AND cl.status=1 AND m.status=1) ';
		$res =  M('')->query($sql);

		foreach ($res as $v) {
			$tmp[$v['card_id']]['id'] = $v['cardid'];
		}
		$card_number = count($tmp);
		$this->assign('card_number', $card_number);
		$share = new WechatShare($this->config,$_SESSION['openid']);
		$this->BioAuthticMethod = $share->getBioAuthticMethod($this->static_path);
		$this->assign('BioAuthticMethod', $this->BioAuthticMethod);

		//底部导航
		$home_menu_list = D('Home_menu')->getMenuList('plat_footer');
		if(empty($home_menu_list) && $this->config['single_system_type'] == 'shop'){
			$home_menu_list = array(
				array(
					'name'		=>	$this->config['shop_alias_name'],
					'url'		=> 	U('Shop/index'),
					'pic_path'	=> 	'shop/footer_store.png',
					'hover_pic_path'	=> 	'shop/footer_store_active.png',
				),
				array(
					'name'		=>	'订单',
					'url'		=> 	U('My/shop_order_list'),
					'pic_path'	=> 	'shop/footer_order.png',
					'hover_pic_path'	=> 	'shop/footer_order_active.png',
				),
				array(
					'name'		=>	'我的',
					'url'		=> 	U('My/index'),
					'pic_path'	=> 	'shop/footer_my.png',
					'hover_pic_path'	=> 	'shop/footer_my_active.png',
				),
			);
		}
		if($home_menu_list){
			$this->assign('home_menu_list',$home_menu_list);
		}

		$database_house_worker = D('House_worker');
		$house_worker_condition['status'] = 1;
		$house_worker_condition['openid'] = $this->user_session['openid'];
		if($now_house_worker = $database_house_worker->where($house_worker_condition)->find()){
			$this->assign('now_house_worker' , $now_house_worker);
		}
		if($this->config['show_scroll_msg']){
			$scroll_msg = D('Scroll_msg')->get_msg();
			$this->assign('scroll_msg',$scroll_msg);
		}

		$recently_sign = M('User_sign')->where(array('uid'=>$uid))->order('id DESC')->find();
		$today_sing = false;
		if($recently_sign && strtotime(date('Ymd',$_SERVER['REQUEST_TIME']))==strtotime(date('Ymd',$recently_sign['sign_time']))){
			$today_sing = true;
		}

		if($this->config['open_distributor']==1){
			$distributor['distributor'] = M('Distributor_agent')->where(array('uid'=>$uid,'type'=>1))->find();
			$distributor['agent'] = M('Distributor_agent')->where(array('uid'=>$uid,'type'=>2))->find();
			$this->assign('distributor',$distributor);
		}
		$this->assign('today_sing',$today_sing);
		$this->display();

	}
	//	老的个人中心页面
	public function index__old(){
		if($this->config['im_appid'] && $_SESSION['openid'] && $this->config['user_center_redirect_friend']){
			redirect(U('Api/go_im',array('hash'=>'myList','title'=>urlencode('会员中心'))));exit;
		}
		$this->display();
	}
	//	个人信息页面
	public function myinfo(){
		$find	=	M('User_authentication')->field('authentication_id')->where(array('uid'=>$this->user_session['uid']))->order('authentication_time DESC')->find();
		$this->assign('find',$find);
		$find_car	=	M('User_authentication_car')->field('car_id')->where(array('uid'=>$this->user_session['uid']))->order('add_time DESC')->find();
		$this->assign('find_car', $find_car);


		$merchant_url = $this->config['site_url'] . '/packapp/merchant';

		$this->assign('merchant_url', $merchant_url);
		$this->display();
	}
	//	我的钱包页面
	public function my_money(){
		if($_GET['source'] == 1){
			$_SESSION['source']	=	1;
		}else{
			$_SESSION['source']	=	2;
		}
		$this->display();
	}
	//	完善资料页面
	public function inputinfo(){
		$this->display();
	}
	//	交易记录
	public function transaction(){
		if($this->config['open_allinyun']==1){
			$allinyun = M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find();
			if($allinyun['bizUserId']){
				redirect(U('setAccountDeposit/money_list'));
			}
		}
		$this->display();
	}

	public function money_list(){
		$this->display();
	}

	public function redpack_list(){
		$res = M('User_redpack_history')->where(array('uid'=>$this->now_user['uid']))->sum('money');
		$this->assign('redpack_money',$res['money']);
		$this->display();
	}
	//	交易记录json
	public function transaction_json(){
		$page	=	$_POST['page'];
		$transaction	=	D('User_money_list')->get_list($this->now_user['uid'],$page,20);
		$transaction['count'] = count($transaction['money_list']);
		foreach($transaction['money_list'] as $k=>$v){
			$transaction['money_list'][$k]['time_s']	=	date('Y/m/d H:i',$v['time']);
			$transaction['money_list'][$k]['money']	=	floatval($transaction['money_list'][$k]['money']	);
		}
		echo json_encode($transaction);
	}

	//红包记录
	public function redpack_json(){
		$page	=	$_POST['page'];
		$transaction	=	D('User_money_list')->get_redpack_list($this->now_user['uid'],$page,20);
		$transaction['count'] = count($transaction['money_list']);
		foreach($transaction['money_list'] as $k=>$v){
			$transaction['money_list'][$k]['time_s']	=	date('Y/m/d H:i',$v['add_time']);
		}
		echo json_encode($transaction);
	}

	//	积分记录
	public function integral(){
		$this->display();
	}

	//新积分记录
	public function score_list(){
		$this->display();
	}
	//	积分记录json
	public function integral_json(){
		$page	=	$_POST['page'];
		$integral	=	D('User_score_list')->get_list($this->now_user['uid'],$page,20);
		$integral['count'] = count($integral['score_list']);
		foreach($integral['score_list'] as $k=>$v){
			$integral['score_list'][$k]['time_s']	=	date('Y/m/d H:i',$v['time']);
			$integral['score_list'][$k]['score']	=	floatval($integral['score_list'][$k]['score']	);
		}
		echo json_encode($integral);
	}
	//	关于我们
	public function about(){
		$this->display();
	}
	//	关于我们json
	public function about_json(){
		$activity_arr = array();
		$intro = D('Appintro');
		$count	=	$intro->count();
		if($count){
			$intro_info = $intro->select();
			foreach($intro_info as $v){
				$activity_arr[] = array(
						'title'=>$v['title'],
						'url'=>$this->config['site_url'] .'/wap.php?g=Wap&c=Appintro&a=intro&id='.$v['id']
				);
			}
		}else{
			$activity_arr  = array();
		}
		echo json_encode($activity_arr);
	}
	public function savemyinfo(){
		$_POST['truename']=trim($_POST['truename']);
		if(empty($_POST['truename'])) $this->dexit(array('error'=>1,'msg'=>'您的姓名必须要填写！'));
		if(empty($_POST['youaddress'])) $this->dexit(array('error'=>1,'msg'=>'地址必须要填写！'));
		if(M('User')->where(array('uid'=>$this->now_user['uid']))->data($_POST)->save()){
			$this->dexit(array('error'=>0,'msg'=>'保存成功！'));
		}
		$this->dexit(array('error'=>1,'msg'=>'保存失败！'));
	}
	public function username(){
		if($_POST['nickname']){
			if(empty($_POST['nickname'])){
				$this->assign('error','请输入新用户名！');
			}else if($_POST['nickname'] == $this->now_user['nickname']){
				$this->assign('error','您还没有修改用户名！');
			}else if($_POST['nickname'] == $this->config['site_name']){
				$this->assign('error','用户名不能和平台名称一样！');
			}else{
				$result = D('User')->save_user($this->now_user['uid'],'nickname',$_POST['nickname']);
				if($result['error']){
					$this->assign('error',$result['msg']);
				}else{
					redirect(U('My/myinfo',array('OkMsg'=>urlencode('昵称修改成功'))));
				}
			}
		}
		$this->display();
	}
	public function password(){
		if(IS_POST){
			if(!empty($this->now_user['pwd']) && md5($_POST['currentpassword']) != $this->now_user['pwd']){
				$this->assign('error','当前密码输入错误！');
			}else if($_POST['currentpassword'] == $_POST['password']){
				$this->assign('error','新密码不能和当前密码相同！');
			}else if($_POST['password2'] != $_POST['password']){
				$this->assign('error','两次新密码输入不一致！');
			}else{
				$result = D('User')->save_user($this->now_user['uid'],'pwd',md5($_POST['password']));
				if($result['error']){
					$this->assign('error',$result['msg']);
				}else{
					unset($_SESSION['veriry_token']);
					redirect(U('My/myinfo',array('OkMsg'=>urlencode('密码修改成功'))));
				}
			}
		}
		$this->display();
	}
	//发送验证码
	public function SmsCodeverify() {
		$user_modifypwdDb = M('User_modifypwd');
		if(isset($_POST['phone']) && !empty($_POST['phone'])){
			$chars = '0123456789';
			mt_srand((double)microtime() * 1000000 * getmypid());
			$vcode = "";

			while (strlen($vcode) < 6)
				$vcode .= substr($chars, (mt_rand() % strlen($chars)), 1);
			$content = '您的验证码是：'. $vcode . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
			Sms::sendSms(array('mer_id' => 0, 'store_id' => 0, 'content' => $content, 'mobile' => $_POST['phone'], 'uid' => $this->now_user['uid'], 'type' => 'bindphone'));
			$addtime = time();
			$expiry = $addtime + 20 * 60; /*             * **二十分钟有效期*** */
			$data = array('telphone' => $_POST['phone'], 'vfcode' => $vcode, 'expiry' => $expiry, 'addtime' => $addtime);
			$insert_id = $user_modifypwdDb->add($data);
			$this->ajaxReturn(array('error' => false));
			exit();

		}
	}

	public function bind_user(){

		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$referer = !empty($_GET['referer']) ? htmlspecialchars_decode($_GET['referer']) : U('My/index');
		if(preg_match('/\d+\|/',$_POST['phone'])){
			$tmp_phone = explode('|',$_POST['phone']);
			$_POST['phone'] = $tmp_phone[1];
			$_POST['phone_country_type'] = $tmp_phone[0];
		}

		if($this->config['open_allinyun']==1){
			redirect(U('setAccountDeposit/bindphone',array('referer'=>$referer)));
		}
		if(IS_POST){
			$database_user = D('User');
			if(empty($_POST['phone'])){
				$this->error('请输入手机号码！');
			}

			if(!empty($this->user_session['phone'])){
				$condition_user['phone'] = $_POST['phone'];
				if($database_user->field(true)->where($condition_user)->find()){
					$this->error('该手机号已注册！');
				}
			}

			//验证短信验证码

			if ($this->config['bind_phone_verify_sms']&&$this->config['sms_key']&&substr($_POST['phone'],0,10)!='1321234567') {
				$sms_verify_result = D('Smscodeverify')->verify($_POST['sms_code'], $_POST['phone']);
				if ($sms_verify_result['error_code']) {
					$this->error($sms_verify_result['msg']);
				} else {
					$modifypwd = $sms_verify_result['modifypwd'];
				}
			}

			//if (!empty($modifypwd)||$this->config['bind_phone_verify_sms']=='0'||empty($this->config['sms_key'])) {
			//$nowtime = time();
			//if ($modifypwd['expiry'] > $nowtime||$this->config['bind_phone_verify_sms']=='0'||empty($this->config['sms_key'])) {

			$condition_user['phone'] = $_POST['phone'];
			$res = $database_user->field('`uid`,`pwd`')->where($condition_user)->find();
			if($res && empty($this->now_user['phone']) && (!empty($this->now_user['openid']) || !empty($this->now_user['alipay_uid']))){
				if($_POST['bind_exist']){
					//$openid = $database_user->field('`openid`')->where(array('uid'=>$res['uid']))->find();
					if((!empty($res['openid']) && !empty($this->now_user['openid']) ) || (!empty($res['alipay_uid']) &&  !empty($this->now_user['alipay_uid']))){
						$this->error("该手机已被其他用户绑定，请登录该账号解除绑定！");
					}
					$login_result = D('User')->checkin($_POST['phone'],$_POST['password']);
					if($login_result['error_code']){
						$this->error($login_result['msg']);
					}else{
						$data_user['openid'] =$this->now_user['openid'];
						$this->now_user['union_id'] && $data_user['union_id'] =$this->now_user['union_id'];
						$this->now_user['wxapp_openid'] && $data_user['wxapp_openid'] =$this->now_user['wxapp_openid'];
						if($this->now_user['openid'] && $database_user->where(array('uid'=>$res['uid']))->save($data_user)){
							if($this->config['cash_bind_phone_merge_score']){
								D('User')->merge_user_from_new($this->now_user['uid'],$res['uid']);//合并积分
							}

							$data_use['openid'] = $this->now_user['openid'].'~no_use';
							$data_use['union_id'] = $this->now_user['union_id'].'~no_use';
							$data_use['wxapp_openid'] = $this->now_user['wxapp_openid'].'~no_use';
							$data_use['status'] = 4;
							$_POST['phone_country_type'] && $data_use['phone_country_type'] = $_POST['phone_country_type'];
							$database_user->where(array('uid'=>$this->now_user['uid']))->save($data_use);
							session_destroy();
							unset($_SESSION);
							$this->success("绑定成功，请重新登录",$_GET['referer']);
						}elseif($this->now_user['alipay_uid'] && $database_user->where(array('uid'=>$res['uid']))->setField('alipay_uid',$this->now_user['alipay_uid'])) {
							if($this->config['cash_bind_phone_merge_score']) {
								D('User')->merge_user_from_new($this->now_user['uid'], $res['uid']);
							}
							$data_use['alipay_uid'] = $this->now_user['alipay_uid'].'~no_use';
							$data_use['status'] = 4;
							$_POST['phone_country_type'] && $data_use['phone_country_type'] = $_POST['phone_country_type'];
							$database_user->where(array('uid'=>$this->now_user['uid']))->save($data_use);
							session_destroy();
							unset($_SESSION);
							$this->success("绑定成功，请重新登录",$_GET['referer']);
						}else {
							$this->error('绑定失败!');
						}
					}
				}else {
					$this->error('phone_exist');
				}
			}

			$condition_save_user['uid'] = $this->now_user['uid'];
			$data_save_user['phone'] = $_POST['phone'];
			if(!empty($_POST['password'])){
				if(!empty($this->now_user['phone'])){
					$condition_save_user['pwd'] = md5($_POST['password']);
				}else{
					$data_save_user['pwd'] = md5($_POST['password']);
				}
			}

			$_POST['phone_country_type'] && $data_save_user['phone_country_type'] = $_POST['phone_country_type'];
			if($database_user->where($condition_save_user)->data($data_save_user)->save()){
				$_SESSION['user']['phone'] = $_POST['phone'];
//				session_destroy();
//				unset($_SESSION);


				$database_house_village_user_bind = D('House_village_user_bind');
				$bind_where['uid'] = $this->now_user['uid'];
				$data = array('phone'=>$_POST['phone']);

				$database_house_village_user_bind->where($bind_where)->save($data);

				$this->success('手机号码绑定成功！');
			}else{
				$this->error('手机号码绑定失败！请重试。');
			}
			exit();
			//}
			//	}
		}
		$now_user = D('User')->where(array('uid'=>$this->user_session['uid']))->find();
		$this->assign('referer',$referer);
		$this->assign('now_user',$now_user);
		$this->display();

	}

	//验证原手机
	public function verify_original_phone(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		if(IS_POST) {
			$database_user = D('User');
			if (empty($_POST['phone'])) {
				$this->error_tips('请输入手机号码！');
			}

			if (empty($_POST['vcode'])) {
				$this->error_tips('请输入验证码！');
			}
			//print_r($_POST);
			//验证短信验证码
			if (substr($_POST['phone'], 0, 10) != '1321234567') {
				$sms_verify_result = D('Smscodeverify')->verify($_POST['vcode'], $_POST['phone']);
				if ($sms_verify_result['error_code']) {
					$this->error_tips($sms_verify_result['msg']);
				} else {
					$modifypwd = $sms_verify_result['modifypwd'];
				}
			}
			$_SESSION['veriry_token'] =1 ;
			$url = $_POST['go']=='password'?U('My/password'):U('My/bind_user',array('bind'=>1));
			$this->success_tips('手机号码验证成功！',$url);

		}else{
			$referer = !empty($_GET['referer']) ? $_GET['referer'] : $_SERVER['HTTP_REFERER'];
			$this->assign('referer',$referer);
			$this->display();
		}
	}
	/*优惠券操作*/
	public function card(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}

		$coupon_list = D('Member_card_coupon')->get_all_coupon($this->user_session['uid']);
		$this->assign('coupon_list',$coupon_list);

		$this->display();
	}

	/*选择优惠券*/
	public function select_card(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		//以下代码是为了得到商户的mer_id ，并且判断此订单是否存在！
		if($_GET['type'] == 'group'){
			$now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
			if($now_order['order_info']['group_share_num']>0||$now_order['order_info']['pin_num']>0)$group_pay_offline=false;
		}else if($_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad'){
			$now_order = D('Meal_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']), false, $_GET['type']);
			if ($now_order['order_info']['paid'] == 2) $this->assign('notCard',true);
			$_GET['type']  = 'meal';
		}else if($_GET['type'] == 'weidian'){
			$now_order = D('Weidian_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
			$this->assign('notCard',true);
		}else if($_GET['type'] == 'recharge'){
			$now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
			$this->assign('notCard',true);
		}else if($_GET['type'] == 'appoint'){
			$now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
		}else if($_GET['type'] == 'wxapp'){
			$_GET['notOffline'] = true;
			$now_order = D('Wxapp_order')->get_pay_order($_GET['uid'],intval($_GET['order_id']));
			$this->assign('notCard',true);
		}else if($_GET['type'] == 'store'){
			$_GET['notOffline'] = true;
			$now_order = D('Store_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
			//$this->assign('notCard',true);
		}else if($_GET['type'] == 'shop'||$_GET['type'] == 'mall'){
			$now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
		}else if($_GET['type'] == 'plat'){
			$now_order = D('Plat_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
		}else if($_GET['type'] == 'balance-appoint'){
			$now_order = D('Appoint_order')->get_pay_balace_order($this->user_session['uid'],intval($_GET['order_id']));
		}else{
			$this->error_tips('非法的订单');
		}
		$now_order = $now_order['order_info'];
		$now_order['total_money'] = $now_order['order_total_money'];

		if(empty($now_order)){
			$this->error_tips('当前订单不存在！');
		}

		if($_SESSION['discount']>0){
			$now_order['total_money']=$now_order['total_money']*$_SESSION['discount']/10;
			unset($_SESSION['discount']);
		}

		if($_SESSION['wx_cheap']>0){
			$now_order['total_money'] -=$_SESSION['wx_cheap'];
			unset($_SESSION['wx_cheap']);
		}
		$now_order['uid'] = $this->user_session['uid'];
		$this->assign('back_url',U('Pay/check',$_GET));
		if($this->is_app_browser){
			$platform = 'app';
		}else if($this->is_wexin_browser){
			$platform = 'weixin';
		}else{
			$platform = 'wap';
		}
		if($_GET['coupon_type']=='mer') {
			//$card_list = D('Member_card_coupon')->get_coupon($now_order['mer_id'], $this->user_session['uid']);
			if(!empty($now_order['business_type'])){
				$coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($now_order,$_GET['type'],$platform,$now_order['business_type']);
			}else{
				$coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($now_order, $_GET['type'],$platform);
			}
		}else if($_GET['coupon_type']=='system') {

			if($_SESSION['card_discount']>0){
				$now_order['total_money'] -= $_SESSION['card_discount'];
				unset($_SESSION['card_discount']);
			}
			if(!empty($now_order['business_type'])) {
				$coupon_list = D('System_coupon')->get_noworder_coupon_list($now_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform,$now_order['business_type']);
			}else{
				$coupon_list = D('System_coupon')->get_noworder_coupon_list($now_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform);
			}
		}
		if(!empty($coupon_list)){
			$param = $_GET;
			foreach($coupon_list as &$value){
				if($_GET['coupon_type']=='mer'){
					$param['merc_id'] =$value['id'];
					unset($param['unmer_coupon']);
				}else{
					unset($param['unsys_coupon']);
					$param['sysc_id'] =$value['id'];
				}
				$value['select_url'] = U('Pay/check',$param);
			}
			$this->assign('coupon_list',$coupon_list);
		}

		$param = $_GET;

		if($_GET['coupon_type']=='mer'){
			unset($param['merc_id']);
			$param['unmer_coupon']=1;
		}else{
			unset($param['sysc_id']);
			$param['unsys_coupon']=1;
		}


		$this->assign('unselect',U('Pay/check',$param));
		$this->display();
	}

        /* 地址操作 */
    public function adress()
    {
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！');
        }
        $adress_list = D('User_adress')->get_adress_list($this->user_session['uid']);
        
        if (empty($adress_list)) {
            redirect(U('My/edit_adress', $_GET));
        } else {
            
            $inList = array();
            $outList = array();
            $distance_array = array();
            
            if ($_GET['group_id']) {
                $select_url = 'Group/buy';
            } elseif ($_GET['store_id']) {
                if ($_GET['buy_type'] == 'waimai') {
                    $select_url = 'Takeout/sureOrder';
                } elseif ($_GET['buy_type'] == 'shop') {
                    $store = M('Merchant_store')->field('long,lat')->where(array('store_id' => intval($_GET['store_id'])))->find();
                    $shop = M('Merchant_store_shop')->field('delivery_range_type,delivery_radius,delivery_range_polygon, deliver_type')->where(array('store_id' => intval($_GET['store_id'])))->find();
                    $store = array_merge($store, $shop);
                    if ($store['delivery_range_polygon']) {
                        $store['delivery_range_polygon'] = substr($store['delivery_range_polygon'], 9, strlen($store['delivery_range_polygon']) - 11);
                        $lngLatData = explode(',', $store['delivery_range_polygon']);
                        array_pop($lngLatData);
                        $lngLats = array();
                        foreach ($lngLatData as $lnglat) {
                            $lng_lat = explode(' ', $lnglat);
                            $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
                        }
                        $store['delivery_range_polygon'] = $lngLats ? array($lngLats) : '';
                    }
                    foreach ($adress_list as $address) {
                        $address['distance'] = getDistance($address['latitude'], $address['longitude'], $store['lat'], $store['long']);
                        if ($store['delivery_range_type'] == 0) {
                            $distance = $address['distance'] / 1000;
                            if ($store['delivery_radius'] >= $distance || $store['deliver_type'] == 5) {
                                $distance_array[] = $address['distance'];
                                $inList[] = $address;
                            } else {
                                $outList[] = $address;
                            }
                        } else {
                            if ($store['delivery_range_polygon']) {
                                if (!isPtInPoly($address['longitude'], $address['latitude'], $store['delivery_range_polygon']) && $store['deliver_type'] != 5) {
                                    $outList[] = $address;
                                } else {
                                    $distance_array[] = $address['distance'];
                                    $inList[] = $address;
                                }
                            } else {
                                $outList[] = $address;
                            }
                        }
                    }
                    if ($distance_array && $inList) {
                        array_multisort($distance_array, SORT_ASC, $inList);
                    }
                    $adress_list = $inList;
                    
                    $select_url = 'Shop/confirm_order';
                } elseif ($_GET['buy_type'] == 'mall') {
                    $select_url = 'Mall/confirm_order';
                } else {
                    $select_url = 'Meal/cart';
                }
            } elseif (! empty($_GET['gift_id'])) {
                $select_url = 'Gift/order';
            } elseif (! empty($_GET['appoint_id'])) {
                $select_url = 'Appoint/order';
            } elseif (! empty($_GET['classify_userinput_id'])) {
                $select_url = 'Classify/order';
            } elseif (! empty($_GET['cid'])) {
                $select_url = 'Service/publish_detail';
            }
            if ($select_url) {
                $this->assign('back_url', U($select_url, $_GET));
            } else {
                $this->assign('back_url', U('My/myinfo'));
            }
            $param = $_GET;
            
            foreach ($adress_list as $key => $value) {
                $param['adress_id'] = $value['adress_id'];
                if (! empty($select_url)) {
                    $adress_list[$key]['select_url'] = U($select_url, $param);
                }
                $adress_list[$key]['edit_url'] = U('My/edit_adress', $param);
                $adress_list[$key]['del_url'] = U('My/del_adress', $param);
            }
            
            foreach ($outList as &$out) {
                $param['adress_id'] = $out['adress_id'];
                if (! empty($select_url)) {
                    $out['select_url'] = U($select_url, $param);
                }
                $out['edit_url'] = U('My/edit_adress', $param);
                $out['del_url'] = U('My/del_adress', $param);
            }
            $this->assign('adress_list', $adress_list);
            $this->assign('out_adress_list', $outList);

			if($select_url){
				$this->assign('back_url',U($select_url,$_GET));
			}else{
				$this->assign('back_url',U('My/myinfo'));
			}
			$param = $_GET;

			foreach($adress_list as $key=>$value){
				$param['adress_id'] = $value['adress_id'];
				if(!empty($select_url)){
					$adress_list[$key]['select_url'] = U($select_url,$param);
				}
				$adress_list[$key]['edit_url'] = U('My/edit_adress',$param);
				$adress_list[$key]['del_url'] = U('My/del_adress',$param);
			}

			$this->assign('adress_list',$adress_list);

			$database_area = D('Area');
			$now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
			$this->assign('now_city_area',$now_city_area);

			$province_list = $database_area->get_arealist_by_areaPid(0);
			$this->assign('province_list',$province_list);

			$city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
			$this->assign('city_list',$city_list);

			$area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
			$this->assign('area_list',$area_list);

			$id = $_GET['adress_id'];
			if(cookie('user_address') === '0' || cookie("user_address") == "") {
				$now_adress = D('User_adress')->get_adress($this->user_session['uid'], $id);
				if ($now_adress) {
					$this->assign('now_adress', $now_adress);

					$province_list = $database_area->get_arealist_by_areaPid(0);
					$this->assign('province_list',$province_list);

					$city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
					$this->assign('city_list', $city_list);

					$area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
					$this->assign('area_list', $area_list);
				} else {
					$now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
					$this->assign('now_city_area',$now_city_area);

					$province_list = $database_area->get_arealist_by_areaPid(0);
					$this->assign('province_list',$province_list);

					$city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
					$this->assign('city_list',$city_list);

					$area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
					$this->assign('area_list',$area_list);
				}
			} else {
				$cookie = json_decode($_COOKIE['user_address'], true);
				$now_adress = $cookie;
				$now_adress['default'] = $now_adress['defaul'];
				$now_adress['adress_id'] = $now_adress['id'];
				$this->assign('now_adress', $now_adress);
				$province_list = $database_area->get_arealist_by_areaPid(0);
				$this->assign('province_list',$province_list);

				$city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
				$this->assign('city_list', $city_list);

				$area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
				$this->assign('area_list', $area_list);
				$params = $_GET;
				unset($params['adress_id']);
				$this->assign('params',$params);
			}
			$this->display();
		}
	}

	public function pick_address(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$flag = $_GET['buy_type'] == 'shop' || $_GET['buy_type'] == 'mall' ? true : false;
		$adress_list = D('Pick_address')->get_pick_addr_by_merid($_GET['mer_id'], $flag, $_GET['store_id']);
		if(empty($adress_list)){
			$this->error_tips('地址信息错误请联系管理员！');
		}else{
			if($_GET['group_id']){
				$select_url = 'Group/buy';
			} elseif ($_GET['store_id']) {
				if ($_GET['buy_type'] == 'waimai') {
					$select_url = 'Takeout/sureOrder';
				} elseif ($_GET['buy_type'] == 'shop') {
					$select_url = 'Shop/confirm_order';
				} elseif ($_GET['buy_type'] == 'mall') {
					$select_url = 'Mall/confirm_order';
				} else {
					$select_url = 'Meal/cart';
				}
			}
			if($select_url){
				$this->assign('back_url',U($select_url,$_GET));
			}else{
				$this->assign('back_url',U('My/myinfo'));
			}

			$param = $_GET;

			foreach($adress_list as $key=>$value){
				$param['pick_addr_id'] = $value['pick_addr_id'];
				$adress_list[$key]['distance'] = $this->wapFriendRange($value['distance']);
				if(!empty($select_url)){
					$adress_list[$key]['select_url'] = U($select_url,$param);
				}
			}
			//dump($adress_list);
			$this->assign('pick_list',$adress_list);
			$this->display();
		}

	}
	/*添加编辑地址*/
	public function edit_adress(){
		if(IS_POST){
			if(empty($_POST['adress'])){
				$this->error('您的位置没有选择！请点击选择位置进行完善！');
			}
			if(D('User_adress')->post_form_save($this->user_session['uid']) !== false){
				cookie('user_address', 0);
				$param['group_id'] =$_POST['group_id'];
				$param['store_id'] =$_POST['store_id'];
				$param['gift_id'] =$_POST['gift_id'];
				$param['buy_type'] =$_POST['buy_type'];
				$param['classify_userinput_id'] =$_POST['classify_userinput_id'];
				$param['current_id'] =$_POST['current_id'];
				$this->success('保存成功！',U('adress',$param));
			}else{
				$this->error('地址保存失败！请重试');
			}
		}else{
			$database_area = D('Area');
			$id = $_GET['adress_id'];
			if(cookie('user_address') === '0' || cookie("user_address") == "") {
				$now_adress = D('User_adress')->get_adress($this->user_session['uid'], $id);
				if ($now_adress) {
					$this->assign('now_adress', $now_adress);

					$province_list = $database_area->get_arealist_by_areaPid(0);
					$this->assign('province_list',$province_list);

					$city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
					$this->assign('city_list', $city_list);

					$area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
					$this->assign('area_list', $area_list);
				} else {
					$now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
					$this->assign('now_city_area',$now_city_area);

					$province_list = $database_area->get_arealist_by_areaPid(0);
					$this->assign('province_list',$province_list);

					$city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
					$this->assign('city_list',$city_list);

					$area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
					$this->assign('area_list',$area_list);
				}
			} else {
				$cookie = json_decode($_COOKIE['user_address'], true);
				$now_adress = $cookie;
				$now_adress['default'] = $now_adress['defaul'];
				$now_adress['adress_id'] = $now_adress['id'];
				$this->assign('now_adress', $now_adress);
				$province_list = $database_area->get_arealist_by_areaPid(0);
				$this->assign('province_list',$province_list);

				$city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
				$this->assign('city_list', $city_list);

				$area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
				$this->assign('area_list', $area_list);
			}

			$params = $_GET;
			unset($params['adress_id']);
			$this->assign('params',$params);
		}

		$this->display();
	}

	/* 地图 */
	public function adres_map(){
		$params = $_GET;
		unset($params['adress_id']);
		$this->assign('params',$params);
		
		$database_area = D('Area');
		
		//得到当前城市的经纬度，用于地图初始化定位
		$now_city = $database_area->get_area_by_areaId($this->config['now_city']);
		if(!$now_city['area_lng']){
			$now_cityp['area_lng'] = '116.403847';
		}
		if(!$now_city['area_lat']){
			$now_cityp['area_lat'] = '39.915526';
		}
		$this->assign('now_city',$now_city);
		
		//得到所有城市并以城市首拼排序
		$all_city = S('all_city_address');
		if(empty($all_city)){
			$database_field = '`area_id`,`area_name`,`area_url`,`first_pinyin`,`is_hot`';
			$condition_all_city['area_type'] = 2;
			$all_city_old = $database_area->field($database_field)->where($condition_all_city)->order('`first_pinyin` ASC,`area_id` ASC')->select();
			foreach($all_city_old as $key=>$value){
				//首拼转成大写
				if(!empty($value['first_pinyin'])){
					$first_pinyin = strtoupper($value['first_pinyin']);
					$all_city[$first_pinyin][] = $value;
				}
			}
			S('all_city_address',$all_city);
		}
		$this->assign('all_city',$all_city);
		
		$this->display();
	}
	/*删除地址*/
	public function del_adress(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$result = D('User_adress')->delete_adress($this->user_session['uid'],$_GET['adress_id']);
		if($result){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}


	/*删除地址*/
	public function ajax_del_adress(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$result = D('User_adress')->delete_adress($this->user_session['uid'],$_GET['adress_id']);
		if($result){
			exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
		}else{
			exit(json_encode(array('status'=>1,'msg'=>'删除失败！')));
		}
	}

	public function select_area(){
		$area_list = D('Area')->get_arealist_by_areaPid($_POST['pid']);
		if(!empty($area_list)){
			$return['error'] = 0;
			$return['list'] = $area_list;
		}else{
			$return['error'] = 1;
		}
		echo json_encode($return);
	}
	/*全部团购*/
	public function group_order_list(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}

		$order_list = D('Group')->wap_get_order_list($this->user_session['uid'],intval($_GET['status']));
		$this->assign('order_list',$order_list);

		$this->display();
	}

	public function classify_order_list(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}

		$database_classify_userinput = D('Classify_userinput');
		$order_list = $database_classify_userinput->wap_get_order_list($this->user_session['uid'],intval($_GET['status']));
		$this->assign('order_list',$order_list);

		$this->display();
	}

	public function ajax_classify_order_list(){
		if(IS_AJAX){
			$database_classify_userinput = D('Classify_userinput');
			$order_list = $database_classify_userinput->wap_get_order_list($this->user_session['uid'],intval($_GET['status']));

			if(!empty($order_list)){
				exit(json_encode(array('status'=>1,'order_list'=>$order_list)));
			}else{
				exit(json_encode(array('status'=>0,'order_list'=>$order_list)));
			}
		}else{
			$this->error('访问页面不存在！~~');
		}
	}

	public function ajax_group_order_list(){
		if(IS_AJAX){
			$order_list = D('Group')->wap_get_order_list($this->user_session['uid'],intval($_GET['status']));
			if(!empty($order_list)){
				exit(json_encode(array('status'=>1,'order_list'=>$order_list)));
			}else{
				exit(json_encode(array('status'=>0,'order_list'=>$order_list)));
			}

		}else{
			$this->error('访问页面不存在！~~');
		}
	}

	/*全部预约*/
	public function appoint_order_list(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}

		$mer_id = $_GET['mer_id'] + 0;
		$status = $_GET['status'] ? $_GET['status'] + 0 : 0;

		$database_appoint = D('Appoint');

		if($status == 1){
			$where['service_status'] = 0;
		}elseif($status == 2){
			$where['service_status'] = 1;
		}

		if(!empty($mer_id)){
			$where['mer_id'] = $mer_id;
			$where['uid'] = $this->user_session['uid'];
			$order_list = $database_appoint->wap_order_list($where);
		}else{
			$order_list = $database_appoint->wap_get_order_list($this->user_session['uid'], $status);
		}

		$this->assign('order_list', $order_list);
		$this->display();
	}
	# 删除预约
	public function ajax_appoint_order_del(){
		$database_appoint_order = D('Appoint_order');
		$now_order = $database_appoint_order->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']));
		if(empty($now_order)){
			$this->error_tips('当前订单不存在！');
		}else if($now_order['paid']){
			$this->error_tips('当前订单已付款，不能删除。');
		}
		$condition_group_order['order_id'] = $now_order['order_id'];
		$data_group_order['is_del'] = 5;
		if($database_appoint_order->where($condition_group_order)->data($data_group_order)->save()){
			exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>'删除失败！')));
		}
	}

	public function ajax_appoint_order_list(){
		$status = $_GET['status'] ? $_GET['status'] + 0 : 0;
		$database_appoint = D('Appoint');

		$order_list = $database_appoint->wap_get_order_list($this->user_session['uid'], $status);

		if(!empty($order_list)){
			exit(json_encode(array('status' => 1 , 'order_list'=>$order_list)));
		}else{
			exit(json_encode(array('status'=> 0 , 'order_list'=>$order_list)));
		}
	}

	public function gift_order_list(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}

		$database_gift_order = D('Gift_order');
		$order_list = $database_gift_order->get_order_list($this->user_session['uid'],intval($_GET['status']),true,99999);

		$this->assign('order_list',$order_list);

		$this->display();
	}


	public function ajax_gift_order_list(){
		if(IS_AJAX){
			if(empty($this->user_session)){
				$this->error_tips('请先进行登录！');
			}

			$database_gift_order = D('Gift_order');
			$order_list = $database_gift_order->get_order_list($this->user_session['uid'],intval($_GET['status']),true,99999);

			foreach($order_list['order_list'] as &$order){
				$order['order_url'] = U('My/gift_order',array('order_id'=>$order['order_id']));
			}

			if($order_list['order_list']){
				exit(json_encode(array('status'=>1,'order_list'=>$order_list['order_list'])));
			}else{
				exit(json_encode(array('status'=>0,'order_list'=>$order_list['order_list'])));
			}
		}else{
			$this->error_tips('访问页面有误！');
		}
	}

	public function gift_order(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}

		$order_id = $_GET['order_id'] + 0;
		if(!$order_id){
			$this->error_tips('传递参数有误！');
		}

		$database_gift_order = D('Gift_order');
		$now_order = $database_gift_order->get_order_detail_by_id($this->user_session['uid'],$order_id);

		$now_order['express_name'] = D('Express')->get_express($now_order['express_type']);
		$this->assign('now_order',$now_order);
		$this->display();
	}

	/*团购收藏*/
	public function group_collect(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}

		$this->assign(D('Group')->wap_get_group_collect_list($this->user_session['uid']));

		$this->display();
	}

	public function ajax_group_collect(){
		if(empty($this->user_session)){
			$this->error('请先进行登录！');
		}

		$database_user_collect = D('User_collect');
		if($_POST['action'] == 'add'){
			$data_user_collect['type'] = $_POST['type'];
			$data_user_collect['id'] = $_POST['id'];
			$data_user_collect['uid'] = $this->user_session['uid'];
			if($database_user_collect->field('collect_id')->where($data_user_collect)->find()){
				$this->error('已收藏过');
			}
			if($database_user_collect->data($data_user_collect)->add()){
				if($_POST['type'] == 'group_detail'){
					$condition_group['group_id'] = $_POST['id'];
					D('Group')->where($condition_group)->setInc('collect_count');
				}
				$this->success('收藏成功');
			}else{
				$this->error('收藏失败！请重试');
			}
		}else if($_POST['action'] == 'del'){
			$condition_user_collect['type'] = $_POST['type'];
			$condition_user_collect['id'] = $_POST['id'];
			$condition_user_collect['uid'] = $this->user_session['uid'];
			if($database_user_collect->where($condition_user_collect)->delete()){
				if($_POST['type'] == 'group_detail'){
					$condition_group['group_id'] = $_POST['id'];
					D('Group')->where($condition_group)->setDec('collect_count');
				}
				$this->success('取消收藏成功');
			}else{
				$this->error('取消收藏失败！请重试');
			}
		}else{
			$this->error('您要做什么？');
		}

	}

	//预约收藏
	public function appoint_collect(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}

		$collection_list = D('')->table(array(C('DB_PREFIX').'appoint'=>'a', C('DB_PREFIX').'appoint_collection'=>'ac'))->where("ac.uid= '".$this->user_session['uid']."' AND ac.appoint_id = a.appoint_id")->select();

		foreach ($collection_list as $k => $v) {
	        $collection_list[$k]['pic'] = str_replace(',', '/', $v['pic']);
	    }

        $this->assign('collection_list',$collection_list);
		$this->display();
	}

	public function ajax_appoint_collect(){
		$listRows = 8;
		$firstRow = $_POST['page'] * $listRows;
		$collection_list = D('')->table(array(C('DB_PREFIX').'appoint'=>'a', C('DB_PREFIX').'appoint_collection'=>'ac'))->where("ac.uid= '".$this->user_session['uid']."' AND ac.appoint_id = a.appoint_id")->limit($firstRow,$listRows)->select();

		foreach ($collection_list as $k => $v) {
	        $collection_list[$k]['pic'] = str_replace(',', '/', $v['pic']);
	    }
		$html = '';
	    foreach ($collection_list as $key => $value) {
	    	$html.='<dd>
        				<a href="'.$value['url'].'" class="react">
							<div class="dealcard">
						        <div class="dealcard-img imgbox">
						        	<img src="'.$this->config['site_url'].'/upload/appoint/'.$value['pic'].'" style="width:100%;height:100%;">
						        </div>
							    <div class="dealcard-block-right">
										<div class="dealcard-brand single-line">'.$value['appoint_name'].'</div>
										<div class="title text-block">'.$value['appoint_name'].'</div>
							        <div class="price">
							            <strong>'.$value['appoint_price'].'121</strong>
							            <span class="strong-color">元</span>
							            <span class="line-right">已售'.$value['appoint_sum'].'</span>
							        </div>
							    </div>
							</div>
       					</a>
       				</dd>';
	    }

	    exit(json_encode(array('error'=>1,'msg'=>'加载成功','html'=>$html)));
	}

	/*预约详情*/
	public function appoint_order(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$now_order = D('Appoint_order')->get_order_detail_by_id($this->user_session['uid'],$_GET['order_id'],true);
		$now_appoint = D('Appoint')->get_appoint_by_appointId($now_order['appoint_id'], 'hits-setInc');
		if(empty($now_order)){
			$this->error_tips('当前订单不存在');
		}
		$now_order['order_type'] = 'appoint';
		$laste_order_info = D('Tmp_orderid')->get_laste_order_info($now_order['order_type'],$now_order['order_id']);
		if(!$now_order['paid'] && !empty($laste_order_info)) {
			if ($laste_order_info['pay_type']=='weixin') {
				$redirctUrl = C('config.site_url') . '/wap.php?g=Wap&c=Pay&a=weixin_back&order_type='.$now_order['order_type'].'&order_id=' . $laste_order_info['orderid'];
				file_get_contents($redirctUrl); 
				$now_order = D('Appoint_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);
			}
		}
		if(empty($now_order)){
			$this->error_tips('当前订单不存在');
		}

		$now_supply = D('Appoint_supply')->where(array('order_id'=>intval($_GET['order_id']),'status'=>3))->find();
		$this->assign('now_appoint',$now_appoint);
		$this->assign('now_supply',$now_supply);
		$this->assign('now_order',$now_order);
		$this->display();
	}
	/*团购详情*/
	public function group_order(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$otherrm = isset($_GET['otherrm']) ? intval($_GET['otherrm']) : 0;
		$otherrm && $_SESSION['otherwc'] = null;
		$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);

		$now_order['order_type'] = 'group';
		$laste_order_info=D('Tmp_orderid')->get_laste_order_info($now_order['order_type'],$now_order['order_id']);
		if(!$now_order['paid'] && !empty($laste_order_info)) {
			if ($laste_order_info['pay_type']=='weixin') {
				$redirctUrl = C('config.site_url') . '/wap.php?g=Wap&c=Pay&a=weixin_back&order_type='.$now_order['order_type'].'&order_id=' . $laste_order_info['orderid'];
				file_get_contents($redirctUrl);
				$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'], intval($_GET['order_id']), true);
			}
		}
		$now_group = D('Group')->where(array('group_id'=>$now_order['group_id']))->find();

		$database_merchant = D('Merchant');
		$now_merchant = $database_merchant->get_info($now_group['mer_id']);
		$now_group['merchant_name'] = $now_merchant['name'];

		if(empty($now_order)){
			$this->error_tips('当前订单不存在');
		}
		if(empty($now_order['paid'])){
			$now_order['status_txt'] = '未付款';
		}else if(empty($now_order['third_id']) && $now_order['pay_type'] == 'offline'){
			$now_order['status_txt'] = '线下未付款';
		}else if(empty($now_order['status'])){
			if($now_order['tuan_type'] != 2){
				$now_order['status_txt'] = '未消费';
			}else{
				$now_order['status_txt'] = '未发货';
			}
		}else if($now_order['status'] == '1'){
			$now_order['status_txt'] = '待评价';
		}else if($now_order['status'] == '2'){
			$now_order['status_txt'] = '已完成';
		}else if($now_order['status'] == '3'){
			$now_order['status_txt'] = '已退款';
			$now_order['group_pass_txt'] = '退款订单无法查看';
		}


		$uid = $this->user_session['uid'];
		$group_share_num = D('Group_share_relation')->get_share_num($uid,$now_order['order_id']);
		$is_shared = D('Group_share_relation')->check_share($uid,$now_order['order_id']);
		$pic = explode(';',$now_group['pic']);
		foreach($pic as &$v){
			$v = preg_replace('/,/','/',$v);
		}
		$now_group['pic'] = $pic;
		if($now_group['pin_num']>0 && $now_order['single_buy']==0 && $now_order['status']<3 && $now_order['paid']){
			$my_group_join = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
			if(empty($my_group_join)&&$now_order['paid']==1){
				$this->error_tips('当前订单出错');
			}
			$buyer = D('Group_start')->get_buyerer_by_order_id($now_order['order_id']);
			$robot_list = M('Robot_list')->where(array('mer_id'=>$now_order['mer_id']))->getField('id,robot_name,avatar');

			foreach ($buyer as &$v) {
				if($v['type']==1||$v['uid']!=$now_order['uid']){
					if(empty($v['pay_time'])){
						$v['pay_time'] = $group_start['last_time'];
					}
					$tmp_name = $v['nickname'];
					if($v['type']==1){
						$tmp_name = $robot_list[$v['uid']]['robot_name'];
						$v['avatar'] = $robot_list[$v['uid']]['avatar'];
					}
					if(preg_match('/\d{3}\*{4}\d{4}/',$v['nickname'],$m)){
						$v['nickname'] = '游客';
					}
					$strlen     = mb_strlen($tmp_name, 'utf-8');
					$firstStr     = mb_substr($tmp_name, 0, 1, 'utf-8');
					$lastStr     = mb_substr($tmp_name, -1, 1, 'utf-8');
					$v['nickname'] =  $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($tmp_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 1) ;
					//$v['avatar'] = $robot_list[$v['uid']]['avatar'];
				}
			}

			//$group_share_info = D('Group_start')->get_group_start_user_by_gid($my_group_join['id']);
			$end_time = $my_group_join['start_time'] + $now_group['pin_effective_time'] * 3600;
			$effective_time = $end_time - $_SERVER['REQUEST_TIME'];
			$efftime['h'] = floor($effective_time / 3600);
			$efftime['m'] = floor(($effective_time - $efftime['h'] * 3600) / 60);
			$efftime['s'] = $effective_time - $efftime['h'] * 3600 - $efftime['m'] * 60;

			if ($effective_time > 0) {
				$this->assign('effective_time', $efftime);
			}
			$end_time = $my_group_join['start_time']+$now_group['pin_effective_time']*3600;
			$effective_time= $end_time-$_SERVER['REQUEST_TIME'];
			if($effective_time<=0&&$my_group_join['status']==0){
				D('Group_start')->timeout($now_order['order_id']);
				$my_group_join['status'] = 2;
			}
			//$this->assign('effective_time',$effective_time);
			$this->assign('buyer',$buyer);
			$this->assign('my_group_join',$my_group_join);
		}else {
			if ($now_group['group_share_num'] == 0 && $now_group['open_now_num'] == 0 && $now_group['open_num'] == 0) {
				M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
				$now_order['is_share_group'] = 2;
			} else if ($now_group['group_share_num'] != 0 && $now_group['group_share_num'] <= $group_share_num) {
				M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
				$now_order['is_share_group'] = 2;
			} else if ($now_group['open_now_num'] <= $now_group['sale_count'] && $now_group['open_now_num'] != 0 && $now_group['group_share_num'] == 0) {
				M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
				$now_order['is_share_group'] = 2;
			} else if ($now_group['open_num'] <= $now_group['sale_count'] && $now_group['open_num'] != 0 && $now_group['open_now_num'] == 0 && $now_group['group_share_num'] == 0) {
				M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
				$now_order['is_share_group'] = 2;
			}
			if ($now_group['group_share_num'] > 0) {
				$share_user = D('Group_share_relation')->get_share_user($this->user_session['uid'], $now_order['order_id']);
				$this->assign('share_user', $share_user);
			}
		}
		//$now_order['coupon_price'] = D('Group_order')->get_coupon_info($now_order['order_id']);
		$has_pay = $now_order['wx_cheap']+$now_order['merchant_balance']+$now_order['balance_pay']+$now_order['score_deducte']+$now_order['coupon_price']+$now_order['payment_money'];

		if($now_order['pass_array']){
			$pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
			$consume_num = D('Group_pass_relation')->get_pass_num($now_order['order_id'],1);
			$unconsume_pass_num = $now_order['num']-$consume_num;
			$this->assign('pass_array',$pass_array);
		}
		if($now_order['status']==6){
			//$total_pay = $now_order['balance_pay']+$now_order['payment_money']+$now_order['merchant_balance'];
			if($now_order['num']!=$unconsume_pass_num){
				$refund_money = $now_order['refund_money'];
			}else{
				$refund_money = $now_order['merchant_balance']+$now_order['balance_pay']+$now_order['payment_money'];
			}
			$now_order['refund_total'] = $refund_money;
		}else{
			$now_order['refund_total'] = $now_order['balance_pay']+$now_order['payment_money']+$now_order['merchant_balance'];
		}

		if($now_order['trade_info']){
			$trade_info_arr = unserialize($now_order['trade_info']);
			if($trade_info_arr['type'] == 'hotel'){
				$trade_hotel_info = D('Trade_hotel_category')->format_order_trade_info($now_order['trade_info']);
				$has_refund = $trade_hotel_info['has_refund'];
				$trade_refund = true;
				if($has_refund==1){
					$trade_refund = false;
				}elseif($has_refund==2&&$now_order['add_time']+3600*$trade_hotel_info['refund_hour']>time()){
					$trade_refund = false;
				}
				$trade_hotel_info['refund'] = $trade_refund;
				$this->assign('trade_hotel_info',$trade_hotel_info);
			}
		}

		$lng_lat = D('User_long_lat')->getLocation($_SESSION['openid'], 0);
		$this->assign('lat',$lng_lat['lat']);
		$this->assign('lng',$lng_lat['long']);
		$this->assign('now_order',$now_order);
		$this->assign('now_group',$now_group);
		$this->assign('group_share_num',$group_share_num);
		$this->assign('is_shared',$is_shared);
		$this->assign('now_merchant',$now_merchant);
		if($now_group['pin_num']>0){
			$share = new WechatShare($this->config,$_SESSION['openid']);
			$this->sign_data = $share->get_wx_config();
			$this->assign('sign_data',$this->sign_data);
			$this->display('pin_group_order');
		}else{
			$this->display();
		}
	}
	/*团购详情*/
	public function meal_order_refund(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}

		$orderid = intval($_GET['order_id']);
		$store_id = intval($_GET['store_id']);
		$now_order = M("Meal_order")->where(array('order_id' => $orderid, 'mer_id' => $this->mer_id, 'store_id' => $store_id))->find();
		if (empty($now_order)) {
			$this->error_tips('当前订单不存在');
		}
		if ($now_order['is_confirm']) {
			$this->error_tips('当前订单店员正在处理中，不能退款或取消');
		}
		if(empty($now_order['paid'])){
			$this->error_tips('当前订单还未付款！');
		}
		if ($now_order['meal_type']) {
			if ($now_order['status'] > 0 && $now_order['status'] < 3) {
				$this->error_tips('订单必须是未消费状态才能取消！', U('Takeout/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			} elseif ($now_order['status'] > 2) {
				$this->redirect(U('Takeout/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			}
		} else {
			if ($now_order['status'] > 0 && $now_order['status'] < 3) {
				$this->error_tips('订单必须是未消费状态才能取消！', U('Food/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			} elseif ($now_order['status'] > 2) {
				$this->redirect(U('Food/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			}
		}

		$now_order['price'] = $now_order['pay_money'];
		$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'],$now_order['is_mobile_pay']);
		$this->assign('now_order',$now_order);
		$this->display();
	}
	//取消订单
	public function meal_order_check_refund(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$orderid = intval($_GET['orderid']);
		$store_id = intval($_GET['store_id']);
		$now_order = M("Meal_order")->where(array('order_id' => $orderid, 'mer_id' => $this->mer_id, 'store_id' => $store_id))->find();
		//dump($now_order);
		if(empty($now_order)){
			$this->error_tips('当前订单不存在');
		}
		if ($now_order['is_confirm']) {
			$this->error_tips('当前订单店员正在处理中，不能退款或取消');
		}
		if(empty($now_order['paid'])){
			$this->error_tips('当前订单还未付款！');
		}
		if ($now_order['meal_type']) {
			if ($now_order['status'] > 0 && $now_order['status'] < 3) {
				$this->error_tips('订单必须是未消费状态才能取消！', U('Takeout/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			} elseif ($now_order['status'] > 2) {
				$this->redirect(U('Takeout/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			}
		} else {
			if ($now_order['status'] > 0 && $now_order['status'] < 3) {
				$this->error_tips('订单必须是未消费状态才能取消！', U('Food/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			} elseif ($now_order['status'] > 2) {
				$this->redirect(U('Food/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			}
		}

// 		$now_order['price'] = $now_order['pay_money'];
// 		$data_meal_order['pay_money'] = 0;
// 		$data_meal_order['paid'] = 0;
		$my_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();
		//在线付款退款
		if($now_order['pay_type'] == 'offline'){
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize(array('refund_time'=>time()));
			$data_meal_order['status'] = 3;
			if(D('Meal_order')->data($data_meal_order)->save()){
			    //退款打印
			    $printHaddle = new PrintHaddle();
			    $printHaddle->printit($now_order['order_id'], 'meal_order', 3);

				$mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $store_id))->find();
				$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'food');
				if ($this->config['sms_cancel_order'] == 1 || $this->config['sms_cancel_order'] == 3) {
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_order['phone'] ? $now_order['phone'] : $my_user['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $orderid . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
					Sms::sendSms($sms_data);
				}
				if ($this->config['sms_cancel_order'] == 2 || $this->config['sms_cancel_order'] == 3) {
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $mer_store['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客' . $now_order['name'] . '的预定订单(订单号：' . $orderid . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
					Sms::sendSms($sms_data);
				}
				//如果使用了优惠券
				if($now_order['card_id']){
					$result = D('Member_card_coupon')->add_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				//如果使用了积分 2016-1-15
				if ($now_order['score_used_count']!=='0') {
					$order_info=unserialize($now_order['info']);
					$order_name=$order_info[0]['name']."*".$order_info[0]['num'];
					$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],'退款 '.$order_name.' '.$this->config['score_name'].'回滚');
					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				//平台余额退款
				if($now_order['balance_pay'] != '0.00'){
					$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$now_order['order_name'].' 增加余额');

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = '平台余额退款成功';
				}
				//商家会员卡余额退款
				if($now_order['merchant_balance'] != '0.00'){
					//$result = D('Member_card')->add_card($now_order['uid'],$now_order['mer_id'],$now_order['merchant_balance'],'退款 '.$now_order['order_name'].' 增加余额');
					$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,'退款 '.$now_order['order_name'].' 增加余额','退款 '.$now_order['order_name'].' 增加赠送余额');

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}
				if ($now_order['meal_type']) {
					$this->success_tips('您使用的是线下支付！订单状态已修改为已退款。',U('Takeout/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
				} else {
					$this->success_tips('您使用的是线下支付！订单状态已修改为已退款。',U('Food/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
				}
				exit;
			}else{
				$this->error_tips('取消订单失败！请重试。');
			}
		}
		if($now_order['payment_money'] != '0.00'){
			if($now_order['is_own']){
				$pay_method = array();
				$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$now_order['mer_id']))->find();
				foreach($merchant_ownpay as $ownKey=>$ownValue){
					$ownValueArr = unserialize($ownValue);
					if($ownValueArr['open']){
						$ownValueArr['is_own'] = true;
						$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
					}
				}
				$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
				if ($now_merchant['sub_mch_refund'] && $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
					$pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
					$pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
					$pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
					$pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
					$pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
					$pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_sp_client_cert'];
					$pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
					$pay_method['weixin']['config']['is_own'] = 2 ;
				}
			}else{
				$pay_method = D('Config')->get_pay_method(0,0,1);
			}

			if(empty($pay_method)){
				$this->error_tips('系统管理员没开启任一一种支付方式！');
			}
			if(empty($pay_method[$now_order['pay_type']])){
				$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
			}

			$pay_class_name = ucfirst($now_order['pay_type']);
			$import_result = import('@.ORG.pay.'.$pay_class_name);
			if(empty($import_result)){
				$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
			}

			if ($now_order['meal_type'] == 1) {
				$now_order['order_type'] = 'takeout';
			} elseif ($now_order['meal_type'] == 2) {
				$now_order['order_type'] = 'foodPad';
			} else {
				$now_order['order_type'] = 'food';
			}
			$order_id = $now_order['order_id'];
			$now_order['order_id'] = $now_order['orderid'];

			if($now_order['is_mobile_pay']==3){
				$pay_method[$now_order['pay_type']]['config'] =array(
						'pay_weixin_appid'=>$this->config['pay_wxapp_appid'],
						'pay_weixin_key'=>$this->config['pay_wxapp_key'],
						'pay_weixin_mchid'=>$this->config['pay_wxapp_mchid'],
						'pay_weixin_appsecret'=>$this->config['pay_wxapp_appsecret'],
				);
			}

			$pay_class = new $pay_class_name($now_order,$now_order['payment_money'],$now_order['pay_type'],$pay_method[$now_order['pay_type']]['config'],$this->user_session,1);
			$go_refund_param = $pay_class->refund();

			$now_order['order_id'] = $orderid;
			$data_meal_order['order_id'] = $orderid;
			$data_meal_order['refund_detail'] = serialize($go_refund_param['refund_param']);
			if(empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok'){
				$data_meal_order['status'] = 3;
			}

			D('Meal_order')->data($data_meal_order)->save();
			if($data_meal_order['status'] != 3){
				$this->error_tips($go_refund_param['msg']);
			}
		}
		//如果使用了优惠券
		if($now_order['card_id']){
			$result = D('Member_card_coupon')->add_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}


		//如果使用了积分 2016-1-15
		if ($now_order['score_used_count']!=='0') {
			$order_info=unserialize($now_order['info']);
			$order_name=$order_info[0]['name']."*".$order_info[0]['num'];
			$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],'退款 '.$order_name.' '.$this->config['score_name'].'回滚');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] .= $result['msg'];
		}

		//平台余额退款
		if($now_order['balance_pay'] != '0.00'){
			$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$now_order['order_name'].' 增加余额');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = '平台余额退款成功';
		}
		//商家会员卡余额退款
		if($now_order['merchant_balance'] != '0.00'){
			$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,'退款 '.$now_order['order_name'].' 增加余额','退款 '.$now_order['order_name'].' 增加赠送余额');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}
		if(empty($now_order['pay_type'])){
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			$go_refund_param['msg'] = '取消订单成功';
		}

		//退款时销量回滚
		if ($now_order['paid'] == 1 && date('m', $now_order['dateline']) == date('m')) {
			foreach (unserialize($now_order['info']) as $menu) {
				D('Meal')->where(array('meal_id' => $menu['id'], 'sell_count' => array('gt', $menu['num'])))->setDec('sell_count', $menu['num']);
			}
		}
		D("Merchant_store_meal")->where(array('store_id' => $now_order['store_id'], 'sale_count' => array('gt', 0)))->setDec('sale_count', 1);

		//退款打印
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($now_order['order_id'], 'meal_order', 3);

		$mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $store_id))->find();
		$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'food');
		if ($this->config['sms_cancel_order'] == 1 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['phone'] ? $now_order['phone'] : $my_user['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $orderid . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_cancel_order'] == 2 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $mer_store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客' . $now_order['name'] . '的预定订单(订单号：' . $orderid . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
			Sms::sendSms($sms_data);
		}

		if ($now_order['meal_type'] == 1) {
			$this->success_tips($go_refund_param['msg'], U('Takeout/order_detail',array('order_id'=>$orderid, 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
		} else {
			$this->success_tips($go_refund_param['msg'], U('Food/order_detail',array('order_id'=>$orderid, 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
		}
	}

	/*团购详情*/
	public function group_order_refund(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);
		$now_group = D('Group')->where(array('group_id'=>$now_order['group_id']))->find();

		if(empty($now_order)){
			$this->error_tips('当前订单不存在');
		}
		if(empty($now_order['paid'])){
			$this->error_tips('当前订单还未付款！');
		}
		if ($now_order['status'] > 0 && $now_order['status'] < 3) {
			$this->error_tips('订单必须是未消费状态才能取消！',U('My/group_order',array('order_id'=>$now_order['order_id'])));
		} elseif ($now_order['status'] > 2) {
			$this->redirect(U('My/group_order',array('order_id'=>$now_order['order_id'])));
		}

		$now_order['coupon_price'] = D('Group_order')->get_coupon_info($now_order['order_id']);
		$has_pay =$now_order['wx_cheap']+$now_order['merchant_balance']+$now_order['balance_pay']+$now_order['payment_money']+$now_order['score_deducte']+$now_order['coupon_price']+$now_order['card_give_money'];
		$tmp_price = ($has_pay-$now_order['wx_cheap'])/$now_order['num'];
		//未消费数
		if($now_order['pass_array']){
			$consume_num = D('Group_pass_relation')->get_pass_num($now_order['order_id'],1);
			$unconsume_pass_num = $now_order['num']-$consume_num;
		}elseif($now_order['tuan_type']==2){
			$unconsume_pass_num = $now_order['num'];
		}else{
			$unconsume_pass_num=1;
		}
		$this->assign('unconsume_pass_num',$unconsume_pass_num);

		//退款金额
		$res = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
		if($now_group['group_share_num']>0&&$now_group['pin_num']==0){
			if($now_order['num']!=$unconsume_pass_num){
				$refund_money = round($has_pay-$now_order['price']*$consume_num-$now_group['group_refund_fee']*$now_order['price']*$unconsume_pass_num/100>0? $has_pay-$now_order['price']*$consume_num-$now_group['group_refund_fee']*$now_order['price']*$unconsume_pass_num/100:0,2);
				$refund_fee = round($now_group['group_refund_fee']*$now_order['price']*$unconsume_pass_num/100,2);
			}else{
				$refund_money = $now_order['merchant_balance']+$now_order['balance_pay']+$now_order['payment_money']+$now_order['card_give_money'];
				$refund_fee=0;
			}
		}elseif($now_group['pin_num'] != 0&&!$now_order['single_buy'] && $res['status']!=2){

			if($res['status']){
				if($unconsume_pass_num == 1){
					$consume_num = 1;
				}
				if($consume_num == 0){
					$consume_num = $now_order['num'];
				}
				$refund_fee = round($now_order['price']*$now_group['group_refund_fee']/100*$consume_num,2);
				$refund_money =$has_pay-$refund_fee;

			}
		}elseif($now_order['num']!=$unconsume_pass_num){
			$refund_money = $unconsume_pass_num*$tmp_price;
		}else{
			$refund_money = $now_order['merchant_balance']+$now_order['balance_pay']+$now_order['payment_money']+$now_order['card_give_money'];
			$refund_fee=0;
		}
		$this->assign('refund_money',$refund_money);
		$this->assign('refund_fee',$refund_fee);
		$this->assign('now_order',$now_order);
		$this->display();
	}

	//取消订单
	public function group_order_check_refund(){


		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);
		if($this->config['open_allinyun']==1){
			import('@.ORG.AccountDeposit.AccountDeposit');
			$deposit = new AccountDeposit('Allinyun');
			$allyun = $deposit->getDeposit();

			$allinyun_user = D('Deposit')->allinyun_user($this->user_session['uid']);

			if($allinyun_user['bizUserId']!=''){
				$allyun->setUser($allinyun_user);
				$params['bizOrderNo'] = 'group_'.$now_order['orderid'];
				$params['oriBizOrderNo'] = $now_order['third_id'];
				$params['amount'] = intval($now_order['payment_money']*100);
				$res = $allyun->refundApply($params);
				if($res['status']=='OK'){

					$data_group_order['refund_detail'] = serialize($res);
					$data_group_order['status'] = 3;
					D('Group')->where(array('group_id' => $now_order['group_id']))->save($data_group_order);

					$this->success_tips('退款成功',U('My/group_order',array('order_id'=>$_GET['order_id'])));
				}else{
					$this->error_tips('退款失败,'.$res['message']);
				}
			}
		}
		$now_group = M('Group')->where(array('group_id'=>$now_order['group_id']))->find();
		if(empty($now_order)){
			$this->error_tips('当前订单不存在');
		}
		if(empty($now_order['paid'])){
			$this->error_tips('当前订单还未付款！');
		}
		if ($now_order['status'] > 0 && $now_order['status'] < 3) {
			$this->error_tips('订单必须是未消费状态才能取消！',U('My/group_order',array('order_id'=>$now_order['order_id'])));
		} elseif ($now_order['status'] > 2) {
			$this->redirect(U('My/group_order',array('order_id'=>$now_order['order_id'])));
		}

		if($now_order['is_share_group']==2){
			$need_refund_fee = true;
		}

		if($now_order['pass_array']){
			$consume_num = D('Group_pass_relation')->get_pass_num($now_order['order_id'],1);
			$unconsume_pass_num = $now_order['num']-$consume_num;
		}elseif($now_order['tuan_type']==2){
			$unconsume_pass_num = $now_order['num'];
		}else{
			$unconsume_pass_num = 1;
		}

		//线下付款退款
		if($now_order['pay_type'] == 'offline'){
			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize(array('refund_time'=>time()));
			$data_group_order['status'] = 3;
			if(D('Group_order')->data($data_group_order)->save()){
				//2015-12-24     线下退款时销量回滚
				$update_group = D('Group')->where(array('group_id' => $now_order['group_id']))->find();
				if ($update_group['type'] == 3) {
					$sale_count = $update_group['sale_count'] - $now_order['num'];
					$sale_count = $sale_count > 0 ? $sale_count : 0;
					$update_group_data = array('sale_count' => $sale_count);
					if ($update_group['count_num'] > 0 && $sale_count < $update_group['count_num']) {
						$update_group_data['type'] = 1;
					}
					D('Group')->where(array('group_id' => $now_order['group_id']))->save($update_group_data);
				} else {
					//退款时销量回滚
					D('Group')->where(array('group_id' => $now_order['group_id']))->setDec('sale_count', $now_order['num']);
				}


				//用户积分退款是回滚
				if ($now_order['score_used_count']>0&&$unconsume_pass_num==$now_order['num']) {

					$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],'退款 '.$now_order['order_name'].C('config.score_name').'回滚');
					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				//平台余额退款
				if($now_order['balance_pay'] != '0.00'){
					$result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$now_order['order_name'].' 增加余额');

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = '平台余额退款成功';
				}
				//商家会员卡余额退款
				if($now_order['merchant_balance'] != '0.00'){
//					if($now_order['num']>$unconsume_pass_num){
//						$now_order['merchant_balance'] =  ($has_pay-$now_order['balance_pay']-$consume_num*$now_order['price'])>0?($has_pay-$now_order['balance_pay']-$consume_num*$now_order['price']):0;
//					}
					$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,'退款 '.$now_order['order_name'].' 增加余额','退款 '.$now_order['order_name'].' 增加赠送余额');

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}
				$this->success_tips('您使用的是线下支付！订单状态已修改为已退款。',U('My/group_order',array('order_id'=>$now_order['order_id'])));
				exit;
			}else{
				$this->error_tips('取消订单失败！请重试。');
			}
		}
		$total_pay = $now_order['balance_pay']+$now_order['payment_money']+$now_order['merchant_balance']+$now_order['card_give_money'];
		$balance_percent  = round($now_order['balance_pay']/$total_pay,4);
		$payment_percent  = round($now_order['payment_money']/$total_pay,4);
		$merchant_percent = round($now_order['merchant_balance']/$total_pay,4);
		$card_give_percent = round($now_order['card_give_money']/$total_pay,4);

		//线上支付退款
		//商家会员卡余额退款
		$need_pay_price = $total_pay/$now_order['num'];
		$need_pay_tmp = $total_pay-$need_pay_price*$unconsume_pass_num;
		$need_refund_tmp = $total_pay-$need_pay_tmp;

		if( $now_group['pin_num']>0&&!$now_order['single_buy']) {
			$now_group_start = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
		}

		if($now_order['merchant_balance'] != '0.00'){
			if( $now_group['pin_num']>0&&!$now_order['single_buy'] && !empty($now_group_start) && $now_group_start['status']!=2) {
				if(($now_group_start['status']<2&&$now_group['group_refund_fee']!=100&&!$now_order['is_head']) || ($now_group_start['status'] ==1 && $now_group['group_refund_fee'] != 100 && $now_order['is_head'])){
					$now_order['merchant_balance'] = $now_order['merchant_balance']/$now_order['num'] * $unconsume_pass_num- round($now_order['total_money'] * $now_group['group_refund_fee'] / 100 * $merchant_percent, 2);
					$now_order['merchant_balance'] = $now_order['merchant_balance']>0?$now_order['merchant_balance']:0;
					$need_pay_tmp = $need_pay_tmp - $now_order['merchant_balance'];
				}else{
					$this->error_tips('您不能退款！');
				}
			}elseif($now_group['group_share_num']>0 ) {
				if ($now_order['num'] > $unconsume_pass_num) {
					$now_order['merchant_balance'] = round($now_order['merchant_balance']/$now_order['num'] * $unconsume_pass_num * (1 - $now_group['group_refund_fee'] / 100) , 2);
					$need_pay_tmp = $need_pay_tmp - $now_order['merchant_balance'];
				}
			}elseif($now_order['num']!=$unconsume_pass_num){
				if($now_order['merchant_balance']<=$need_pay_price*$unconsume_pass_num){
					$need_pay_tmp = $need_pay_price*$unconsume_pass_num-$now_order['merchant_balance'];
					$need_refund_tmp = 0;
				}else{
					$need_refund_tmp = $now_order['merchant_balance']-$need_pay_price*$unconsume_pass_num;
					$need_pay_tmp= 0;
				}
				$now_order['merchant_balance'] = $need_refund_tmp;
			}

			$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],0,0,'退款 '.$now_order['order_name'].' 增加余额','退款 '.$now_order['order_name'].' 增加赠送余额');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_group_order['status'] = 3;
			D('Group_order')->data($data_group_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		if($now_order['card_give_money'] != '0.00'){
			if( $now_group['pin_num']>0&&!$now_order['single_buy'] && !empty($now_group_start) && $now_group_start['status']!=2) {

				if(($now_group_start['status']<2&&$now_group['group_refund_fee']!=100&&!$now_order['is_head']) || ($now_group_start['status'] ==1 && $now_group['group_refund_fee'] != 100 && $now_order['is_head'])){
					$now_order['card_give_money']  = $now_order['card_give_money']/$now_order['num'] * $unconsume_pass_num - round($now_order['total_money'] * $now_group['group_refund_fee'] / 100 * $card_give_percent, 2);
					$now_order['card_give_money'] = $now_order['card_give_money']>0?$now_order['card_give_money']:0;
					$need_pay_tmp = $need_pay_tmp - $now_order['card_give_money'];
				}else{
					$this->error_tips('您不能退款！');
				}
			}elseif($now_group['group_share_num']>0 ) {
				if ($now_order['num'] > $unconsume_pass_num) {
					$now_order['card_give_money'] = round($now_order['merchant_balance']/$now_order['num'] * $unconsume_pass_num * (1 - $now_group['group_refund_fee'] / 100) , 2);
					$need_pay_tmp = $need_pay_tmp - $now_order['card_give_money'];
				}
			}elseif($now_order['num']!=$unconsume_pass_num){
				if($now_order['card_give_money']<=$need_pay_price*$unconsume_pass_num){
					$need_pay_tmp = $need_pay_price*$unconsume_pass_num-$now_order['card_give_money'];
					$need_refund_tmp = 0;
				}else{
					$need_refund_tmp = $now_order['card_give_money']-$need_pay_price*$unconsume_pass_num;
					$need_pay_tmp= 0;
				}
				$now_order['card_give_money'] = $need_refund_tmp;
			}

			$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],0,$now_order['card_give_money'],0,'退款 '.$now_order['order_name'].' 增加余额','退款 '.$now_order['order_name'].' 增加赠送余额');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_group_order['status'] = 3;
			D('Group_order')->data($data_group_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		//平台余额退款
		if($now_order['balance_pay'] != '0.00'){
			if( $now_group['pin_num']>0&&!$now_order['single_buy'] && !empty($now_group_start) && $now_group_start['status']!=2) {

				if(($now_group_start['status']<2&&$now_group['group_refund_fee']!=100&&!$now_order['is_head']) || ($now_group_start['status'] ==1 && $now_group['group_refund_fee'] != 100 && $now_order['is_head'])){
					$now_order['balance_pay'] =$now_order['balance_pay']/$now_order['num'] * $unconsume_pass_num- round($now_order['total_money'] * $now_group['group_refund_fee'] / 100 * $balance_percent, 2);
					$now_order['balance_pay'] = $now_order['balance_pay']>0?$now_order['balance_pay']:0;
					$need_pay_tmp = $need_pay_tmp - $now_order['balance_pay'];
				}else{
					$this->error_tips('您不能退款！');
				}
			}elseif($now_group['group_share_num']>0 ) {
				if ($now_order['num'] > $unconsume_pass_num) {
					$now_order['balance_pay'] = round($now_order['balance_pay']/$now_order['num'] * $unconsume_pass_num* (1 - $now_group['group_refund_fee'] / 100) , 2);
					$need_pay_tmp = $need_pay_tmp - $now_order['balance_pay'];
				}
			}elseif($now_order['num']!=$unconsume_pass_num){
				if($need_pay_tmp>0){
					if($now_order['balance_pay']<=$need_pay_tmp){
						$need_pay_tmp = $need_pay_tmp - $now_order['balance_pay'];
						$need_refund_tmp = 0;
					}else{
						$need_refund_tmp =$now_order['balance_pay']-$need_pay_tmp;
						$need_pay_tmp=0;
					}
				}else{
					$need_refund_tmp = $now_order['balance_pay'];
					$need_pay_tmp= 0;
				}
				$now_order['balance_pay'] = $need_refund_tmp;
			}
			if($now_order['balance_pay']>0){

				$result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$now_order['order_name'].' 增加余额');
				$param = array('refund_time' => time());
				if($result['error_code']){
					$param['err_msg'] = $result['msg'];
				} else {
					$param['refund_id'] = $now_order['order_id'];
				}
				$data_group_order['order_id'] = $now_order['order_id'];
				$data_group_order['refund_detail'] = serialize($param);
				$result['error_code'] || $data_group_order['status'] = 3;
				D('Group_order')->data($data_group_order)->save();
				if ($result['error_code']) {
					$this->error_tips($result['msg']);
				}
				$go_refund_param['msg'] = '平台余额退款成功';
			}
		}

		//线上支付
		if($now_order['payment_money'] != '0.00'){
			if($this->config['open_juhepay']==1 &&( $now_order['pay_type']=='weixin' || $now_order['pay_type']=='alipay')){
				import('@.ORG.LowFeePay');
				$lowfeepay = new LowFeePay('juhepay');
				$now_order['orderNo'] = 'group_'.$now_order['orderid'];
				if($now_order['mer_id']){
                    $mer_juhe = M('Merchant_juhe_config')->where(array('mer_id'=>$now_order['mer_id']))->find();
					if(!empty($mer_juhe)){ 
						$lowfeepay->userId =$mer_juhe['userid'];
						$now_order['is_own'] =1 ;
					    $now_order['orderNo'] =  $now_order['orderNo'].'_1';
					}
					
                }
				$go_refund_param= $lowfeepay->refund($now_order);

			}else {
				$payment_money_tmp = $now_order['payment_money'];
				if ($now_group['pin_num'] > 0 && !$now_order['single_buy'] && !empty($now_group_start) && $now_group_start['status'] != 2) {
					if (($now_group_start['status'] < 2 && $now_group['group_refund_fee'] != 100 && !$now_order['is_head']) || ($now_group_start['status'] ==1 && $now_group['group_refund_fee'] != 100 && $now_order['is_head'])) {
						$now_order['payment_money'] = $now_order['payment_money'] / $now_order['num'] * $unconsume_pass_num - round($now_order['total_money'] * $now_group['group_refund_fee'] / 100 * $payment_percent, 2);
						$now_order['payment_money'] = $now_order['payment_money'] > 0 ? $now_order['payment_money'] : 0;
						$need_refund_tmp = $now_order['payment_money'];
					} else {
						$this->error_tips('您不能退款');
					}
				} elseif ($now_group['group_share_num'] > 0) {
					if ($now_order['num'] > $unconsume_pass_num) {
						$now_order['payment_money'] = round($now_order['payment_money'] / $now_order['num'] * $unconsume_pass_num * (1 - $now_group['group_refund_fee'] / 100), 2);
					}
				} elseif ($now_order['num'] != $unconsume_pass_num) {
					if ($need_pay_tmp > 0) {
						if ($now_order['payment_money'] <= $need_pay_tmp) {
							$need_pay_tmp = $need_pay_tmp - $now_order['payment_money'];
							$need_refund_tmp = 0;
						} else {
							$need_refund_tmp = $now_order['payment_money'] - $need_pay_tmp;
							$need_pay_tmp = 0;
						}
					} else {
						$need_pay_tmp = 0;
						$need_refund_tmp = $now_order['payment_money'];
					}
				} else {
					$need_refund_tmp = $now_order['payment_money'];
				}
				if ($now_order['is_own']) {
					$pay_method = array();
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $now_order['mer_id']))->find();
					foreach ($merchant_ownpay as $ownKey => $ownValue) {
						$ownValueArr = unserialize($ownValue);
						if ($ownValueArr['open']) {
							$ownValueArr['is_own'] = true;
							$pay_method[$ownKey] = array('name' => $this->getPayName($ownKey), 'config' => $ownValueArr);
						}
					}
					$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
					if ($now_merchant['sub_mch_refund'] && $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
						$pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
						$pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
						$pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
						$pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
						$pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
						$pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_sp_client_cert'];
						$pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
						$pay_method['weixin']['config']['is_own'] = 2;
					}
				} else {
					$pay_method = D('Config')->get_pay_method(0, 0, 1);
				}

				if (empty($pay_method)) {
					$this->error_tips('系统管理员没开启任一一种支付方式！');
				}

				$pay_class_name = ucfirst($now_order['pay_type']);
				if ($pay_class_name == 'Alipay' && $now_order['is_mobile_pay'] == 2 && $this->config['new_pay_alipay_app_public_key'] != '' && $this->config['new_pay_alipay_app_appid'] != '' && $this->config['new_pay_alipay_app_private_key'] != '') {
					$pay_class_name = 'AlipayApp';
					$pay_method['alipay']['config']['new_pay_alipay_app_appid'] = $this->config['new_pay_alipay_app_appid'];
					$pay_method['alipay']['config']['new_pay_alipay_app_private_key'] = $this->config['new_pay_alipay_app_private_key'];
					$pay_method['alipay']['config']['new_pay_alipay_app_public_key'] = $this->config['new_pay_alipay_app_public_key'];
				}
				if (empty($pay_method[$now_order['pay_type']])) {
					$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
				}
				$import_result = import('@.ORG.pay.' . $pay_class_name);
				if (empty($import_result)) {
					$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
				}
				$now_order['order_type'] = 'group';
				if (!empty($now_order['orderid'])) {
					$now_order['order_id'] = $now_order['orderid'];
				}
				if ($now_order['is_mobile_pay'] == 3) {
					$pay_method[$now_order['pay_type']]['config'] = array(
							'pay_weixin_appid' => $this->config['pay_wxapp_appid'],
							'pay_weixin_key' => $this->config['pay_wxapp_key'],
							'pay_weixin_mchid' => $this->config['pay_wxapp_mchid'],
							'pay_weixin_appsecret' => $this->config['pay_wxapp_appsecret'],
					);
				}
				$now_order['payment_money'] = $payment_money_tmp;

				$pay_class = new $pay_class_name($now_order, $need_refund_tmp, $now_order['pay_type'], $pay_method[$now_order['pay_type']]['config'], $this->user_session, $now_order['is_mobile_pay']);
				$go_refund_param = $pay_class->refund();
			}
			$now_order['order_id'] = $_GET['order_id'];
			$data_group_order['order_id'] = $_GET['order_id'];
			$data_group_order['refund_detail'] = serialize($go_refund_param['refund_param']);
			if(empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok'){
				$data_group_order['status'] = 3;
			}else{
				$data_group_order['status'] = 0;
			}
			D('Group_order')->data($data_group_order)->save();
			if($data_group_order['status'] != 3){
				$this->error_tips($go_refund_param['msg']);
			}
		}

		//用户积分退款是回滚
		if ($now_order['score_used_count']>0&&$unconsume_pass_num==$now_order['num']) {
			$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],'退款 '.$now_order['order_name'].' '.$this->config['score_name'].'回滚');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_group_order['status'] = 3;
			D('Group_order')->data($data_group_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		//2015-12-9     退款时销量回滚  (多消费码且部分消费了)
		if($now_order['num']>$unconsume_pass_num) {
			$order_num = $now_order['num'];
			$now_order['num'] = $unconsume_pass_num;
			if ($now_group['pin_num'] > 0 && !$now_order['single_buy']) {
				$now_group_start = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
				if ($now_group_start&&$now_group_start['status'] == 1) {
					$refund_fee = round($now_group['group_refund_fee'] * $now_order['price'] * $unconsume_pass_num / 100, 2);
					$refund_money = round($total_pay + $now_order['score_deducte'] + $now_order['coupon_price'] - $now_order['price'] * $consume_num - $refund_fee, 2);
				}
			}elseif($now_group['group_share_num']>0){
				$refund_fee = round($now_group['group_refund_fee'] * $now_order['price'] * $unconsume_pass_num / 100, 2);
				$refund_money = round($total_pay + $now_order['score_deducte'] + $now_order['coupon_price'] - $now_order['price'] * $consume_num - $refund_fee, 2);
			}else{
				$refund_money =$need_refund_tmp;
				$refund_fee = 0;
			}
			$data_group_order['refund_money'] = $refund_money;
			$data_group_order['order_id'] = $now_order['order_id'];
			if($refund_fee>0){
				$data_group_order['refund_fee'] = $refund_fee;
			}
			if ($refund_money > 0) {
				$now_order['order_type'] = 'group';
				$now_order['refund'] = true;
				$now_order['refund_money'] = $refund_fee;
				//D('Merchant_money_list')->add_money($now_order['mer_id'], '团购退款手续费', $now_order);
				$now_order['desc']='团购退款手续费';
				D('SystemBill')->bill_method($now_order['is_own'],$now_order);
			}

			//退款 原来没有成团的拼团组人数减1
			if($now_group['pin_num']!=0){
				$now_group_start = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
				if($now_group_start['status']==0){
					D('Group_start')->buyer_refund_dec_by_orderid($now_order['order_id'],$now_group_start['id']);
				}
				if($order_num==$unconsume_pass_num){
					$data_group_order['status']=3;
				}
			}else{
				$data_group_order['status'] = 6;
			}
			D('Group_order')->data($data_group_order)->save();
		}else{
			$data_group_order['order_id'] = $_GET['order_id'];
			$data_group_order['refund_money'] = $total_pay;
			$data_group_order['refund_fee'] = 0;
			$data_group_order['status']=3;
			D('Group_order')->data($data_group_order)->save();
			$go_refund_param['msg'] = "退款成功！";
		}

		$update_group = D('Group')->where(array('group_id' => $now_order['group_id']))->find();

		if ($update_group['type'] == 3) {
			$sale_count = $update_group['sale_count'] - $now_order['num'];
			$sale_count = $sale_count > 0 ? $sale_count : 0;
			$update_group_data = array('sale_count' => $sale_count);
			if ($update_group['count_num'] > 0 && $sale_count < $update_group['count_num']) {
				$update_group_data['type'] = 1;
			}
			D('Group')->where(array('group_id' => $now_order['group_id']))->save($update_group_data);
		} else {
			//退款时销量回滚
			D('Group')->where(array('group_id' => $now_order['group_id'], 'sale_count' => array('egt', $now_order['num'])))->setDec('sale_count', $now_order['num']);
		}

		//酒店等其他业务增加库存
		if(!empty($now_order['trade_info'])){
			$trade_info = unserialize($now_order['trade_info']);
			
			switch($trade_info['type']){
				case 'hotel':
					$where['mer_id']=$now_group['mer_id'];
					$where['cat_id']=$trade_info['cat_id'];
					$where['_string']="stock_day >=".$trade_info['dep_time'] .' AND stock_day <'.$trade_info['end_time'];
					M('Trade_hotel_stock')->where($where)->setInc('stock',$trade_info['num']);
				
					break;
			}
		}

		//短信提醒
		$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => 0, 'type' => 'group');
		if ($this->config['sms_group_cancel_order'] == 1 || $this->config['sms_group_cancel_order'] == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您购买 '.$now_order['order_name'].'的订单(订单号：' . $now_order['order_id'] . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_group_cancel_order'] == 2 || $this->config['sms_group_cancel_order'] == 3) {
			$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $merchant['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客购买的' . $now_order['order_name'] . '的订单(订单号：' . $now_order['order_id'] . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
			Sms::sendSms($sms_data);
		}
		D('Group_order')->group_app_notice($now_order['order_id'],5);
		D('Group_pass_relation')->change_refund_status($now_order['order_id']);
		$this->success_tips($go_refund_param['msg'],U('My/group_order',array('order_id'=>$_GET['order_id'])));
	}

	/*删除团购订单*/
	public function group_order_del(){
		$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']));
		if(empty($now_order)){
			$this->error_tips('当前订单不存在！');
		}else if($now_order['paid']){
			$this->error_tips('当前订单已付款，不能删除。');
		}
		$condition_group_order['order_id'] = $now_order['order_id'];
		$data_group_order['status'] = 4;
		if(D('Group_order')->where($condition_group_order)->data($data_group_order)->save()){
			//退款时销量回滚
			$now_group = D('Group')->where(array('group_id' => $now_order['group_id']))->find();
			if($now_group['stock_reduce_method']){
				D('Group')->where(array('group_id' => $now_order['group_id'], 'sale_count' => array('egt', $now_order['num'])))->setDec('sale_count', $now_order['num']);
			}

			$this->success_tips('删除成功！',U('My/group_order_list'));
		}else{
			$this->error_tips('删除失败！请重试。');
		}
	}

	//plat_order_refund

	public function plat_order_refund(){
		$business_type = $_GET['business_type'];
		$order_id = $_GET['order_id'];
		$table = ucfirst($business_type).'_order';
		$model = D($table);
		$now_order = $model->get_order_by_orderid($order_id);
		$param['business_id'] = $_GET['order_id'];
		$param['business_type'] = $business_type;
		$plat_order = D('Plat_order')->get_order_by_business_id($param);

		if (empty($now_order)||empty($plat_order)) {
			$this->error_tips('当前订单不存在');
		}
		$can_refund = $model->can_refund_status($now_order);
		if ($plat_order['paid'] != 1 || !$can_refund) {
			$this->error_tips('当前订单店员正在处理中，不能退款或取消');
		}
		if (empty($plat_order['paid'])) {
			$this->error_tips('当前订单还未付款！');
		}
		$now_order['pay_order'] = $plat_order;

		$this->assign('now_order', $now_order);
		$this->display($business_type.'_order_refund');
	}

	public function plat_order_check_refund(){

		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$orderid = intval($_GET['order_id']);

		$now_order = M("Plat_order")->where(array('order_id' => $orderid))->find();


		$now_order['order_type'] = 'plat';
		$now_order['payment_money'] =$now_order['pay_money'];
		$table = ucfirst($now_order['business_type']).'_order';
		$model =D($table);
		$business_order = $model->get_order_by_orderid($now_order['business_id']);
		$now_order['mer_id'] = $business_order['mer_id'];
		if($this->config['open_allinyun']==1){
			import('@.ORG.AccountDeposit.AccountDeposit');
			$deposit = new AccountDeposit('Allinyun');
			$allyun = $deposit->getDeposit();

			$allinyun_user = D('Deposit')->allinyun_user($this->user_session['uid']);

			if($allinyun_user['bizUserId']!=''){
				$allyun->setUser($allinyun_user);
				$params['bizOrderNo'] = 'shop_'.$now_order['orderid'];
				$params['oriBizOrderNo'] = $now_order['third_id'];
				$params['amount'] = intval($now_order['payment_money']*100);
				$res = $allyun->refundApply($params);

				if($res['status']=='OK'){

					$refund_result = $model->afert_refund($business_order);
					if($now_order['pay_type']=='offline'){
						$this->success_tips('您使用的是线下支付！订单状态已修改为已退款。',$refund_result['url']);
					}else{
						$this->success_tips('退款成功',$refund_result['url']);
					}
				}else{
					$this->error_tips('退款失败,'.$res['message']);
				}
			}
		}

		if(empty($now_order)){
			$this->error_tips('当前订单不存在');
		}
		$can_refund = $model->can_refund_status($business_order);
		if ($now_order['paid'] != 1 || !$can_refund) {
			$this->error_tips('当前订单店员正在处理中，不能退款或取消');
		}

		if(empty($now_order['paid'])){
			$this->error_tips('当前订单还未付款！');
		}
//		dump($now_order);die;
		//$my_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();
		$refund_status =false;
		if($now_order['pay_money'] != '0.00'){
			if($this->config['open_juhepay']==1 &&( $now_order['pay_type']=='weixin' || $now_order['pay_type']=='alipay')){
				import('@.ORG.LowFeePay');
				$lowfeepay = new LowFeePay('juhepay');
				$now_order['orderNo'] = 'plat_'.$now_order['orderid'];
				if($now_order['mer_id']){
                    $mer_juhe = M('Merchant_juhe_config')->where(array('mer_id'=>$now_order['mer_id']))->find();
					if(!empty($mer_juhe)){ 
						$lowfeepay->userId =$mer_juhe['userid'];
						$now_order['is_own'] =1 ;
					    $now_order['orderNo'] =  $now_order['orderNo'].'_1';
					}
					
                }
				$go_refund_param= $lowfeepay->refund($now_order);

			}else {
				if ($now_order['is_own']) {
					$pay_method = array();
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $business_order['mer_id']))->find();
					foreach ($merchant_ownpay as $ownKey => $ownValue) {
						$ownValueArr = unserialize($ownValue);
						if ($ownValueArr['open']) {
							$ownValueArr['is_own'] = true;
							$pay_method[$ownKey] = array('name' => $this->getPayName($ownKey), 'config' => $ownValueArr);
						}
					}
					$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
					if ($now_merchant['sub_mch_refund'] && $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
						$pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
						$pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
						$pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
						$pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
						$pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
						$pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_sp_client_cert'];
						$pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
						$pay_method['weixin']['config']['is_own'] = 2;
					}
				} else {
					$pay_method = D('Config')->get_pay_method(0, 0, 1);
				}

				if (empty($pay_method)) {
					$this->error_tips('系统管理员没开启任一一种支付方式！');
				}


				$pay_class_name = ucfirst($now_order['pay_type']);
				if ($pay_class_name == 'Alipay' && $now_order['is_mobile_pay'] == 2 && $this->config['new_pay_alipay_app_public_key'] != '' && $this->config['new_pay_alipay_app_appid'] != '' && $this->config['new_pay_alipay_app_private_key'] != '') {
					$pay_class_name = 'AlipayApp';
					$pay_method['alipay']['config']['new_pay_alipay_app_appid'] = $this->config['new_pay_alipay_app_appid'];
					$pay_method['alipay']['config']['new_pay_alipay_app_private_key'] = $this->config['new_pay_alipay_app_private_key'];
					$pay_method['alipay']['config']['new_pay_alipay_app_public_key'] = $this->config['new_pay_alipay_app_public_key'];
				}
				if (empty($pay_method[$now_order['pay_type']])) {
					$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
				}
				$import_result = import('@.ORG.pay.' . $pay_class_name);
				if (empty($import_result)) {
					$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
				}

				$order_id = $now_order['order_id'];
				$now_order['order_id'] = $now_order['orderid'];
				if ($now_order['is_mobile_pay'] == 3) {
					$pay_method[$now_order['pay_type']]['config'] = array(
							'pay_weixin_appid' => $this->config['pay_wxapp_appid'],
							'pay_weixin_key' => $this->config['pay_wxapp_key'],
							'pay_weixin_mchid' => $this->config['pay_wxapp_mchid'],
							'pay_weixin_appsecret' => $this->config['pay_wxapp_appsecret'],
					);
					$is_mobile = 3;
				} else {
					$is_mobile = 1;
				}
				$now_order['payment_money'] = $now_order['pay_money'];
				$pay_class = new $pay_class_name($now_order, $now_order['pay_money'], $now_order['pay_type'], $pay_method[$now_order['pay_type']]['config'], $this->user_session, $is_mobile);
				$go_refund_param = $pay_class->refund();
			}
			$now_order['order_id'] = $orderid;
			$data_plat_order['order_id'] = $orderid;
			$data_plat_order['refund_detail'] = serialize($go_refund_param['refund_param']);
			if(empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok'){
				$refund_status = true;
			}else{
				$this->error_tips($go_refund_param['msg']);
			}
			D('Plat_order')->data($data_plat_order)->save();

		}elseif($now_order['pay_type'] == 'offline') {
			$data_plat_order['order_id'] = $now_order['order_id'];
			$data_plat_order['refund_detail'] = serialize(array('refund_time'=>time()));
			D('Plat_order')->data($data_plat_order)->save();
		}

		//如果使用了积分 2016-1-15
		if ($now_order['system_score']>0) {

			$order_name=$now_order['order_name'];
			$result = D('User')->add_score($now_order['uid'],$now_order['system_score'],'退款 '.$order_name.$this->config['score_name'].'回滚');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_plat_order['order_id'] = $now_order['order_id'];
			$data_plat_order['refund_detail'] = serialize($param);
			D('Plat_order')->data($data_plat_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}else{
				$refund_status = true;
			}
			$go_refund_param['msg'] .= $result['msg'];
		}

		//平台余额退款
		if($now_order['system_balance'] != '0.00'){
			$result = D('User')->add_money($now_order['uid'],$now_order['system_balance'],'退款 '.$now_order['order_name'].' 增加余额');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_plat_order['order_id'] = $now_order['order_id'];
			$data_plat_order['refund_detail'] = serialize($param);
			D('Plat_order')->data($data_plat_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}else{
				$refund_status = true;
			}
			$go_refund_param['msg'] = '平台余额退款成功';
		}

		//商家会员卡余额退款
		if($now_order['merchant_balance_pay'] != '0.00'||$now_order['merchant_balance_give']!='0.00'){
			$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance_pay'],$now_order['merchant_balance_give'],0,'退款 '.$now_order['order_name'].' 增加余额','退款 '.$now_order['order_name'].' 增加赠送余额');
			$param = array('refund_time' => time());
			if(!$result){
//				$param['err_msg'] = $result['msg'];
				$param['err_msg'] = '退款失败！';
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_plat_order['order_id'] = $now_order['order_id'];
			$data_plat_order['refund_detail'] = serialize($param);
			D('Plat_order')->data($data_plat_order)->save();
			if (!$result) {
//				$this->error_tips($result['msg']);
				$this->error_tips('退款失败！');
			}else{
				$refund_status = true;
			}
//			$go_refund_param['msg'] = $result['msg'];
			$go_refund_param['msg'] = '会员卡余额退款成功！';
		}


		//if($refund_status){
			//调用 打印 发短信 发模板消息 回滚库存 回调跳转地址
			$refund_result = $model->afert_refund($business_order);
			if(!$refund_status['error_code']){
				if($now_order['pay_type']=='offline'){
					$this->success_tips('您使用的是线下支付！订单状态已修改为已退款。',$refund_result['url']);
				}else{
					$this->success_tips($go_refund_param['msg'],$refund_result['url']);
				}
			}else{
				$this->error_tips($refund_result['msg']);
			}
		//}

	}

	public function ajax_group_order_del(){
		$database_group_order = D('Group_order');
		$now_order = $database_group_order->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']));
		if(empty($now_order)){
			exit(json_encode(array('status'=>0,'msg'=>'当前订单不存在！')));
		}

		$order_condition['order_id'] = $now_order['order_id'];
		$data['is_del'] = 1;
		if($database_group_order->where($order_condition)->data($data)->save()){
			exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>'删除失败！')));
		}
	}

	public function ajax_gift_order_del(){
		if(IS_AJAX){
			$order_id = $_GET['order_id'] + 0;
			$database_gift_order = D('Gift_order');
			$now_order = $database_gift_order->get_order_detail_by_id($this->user_session['uid'],$order_id);

			if(empty($now_order)){
				exit(json_encode(array('status'=>0,'msg'=>'当前订单不存在！')));
			}

			$order_condition['order_id'] = $now_order['order_id'];
			$data['is_del'] = 1;
			$data['del_time'] = time();
			if($database_gift_order->where($order_condition)->data($data)->save()){
				exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
			}else{
				exit(json_encode(array('status'=>0,'msg'=>'删除失败！')));
			}
		}else{
			$this->error_tips('访问页面有误！');
		}
	}


	public function ajax_meal_order_del(){
		$database_meal_order = D('Meal_order');
		$now_order = $database_meal_order->get_order_by_id($this->user_session['uid'],intval($_GET['order_id']));
		if(empty($now_order)){
			exit(json_encode(array('status'=>0,'msg'=>'当前订单不存在！')));
		}

		$order_condition['order_id'] = $now_order['order_id'];
		$data['is_del'] = 1;
		if($database_meal_order->where($order_condition)->data($data)->save()){
			exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>'删除失败！')));
		}
	}


	public function ajax_shop_order_del(){
		$database_shop_order = D('Shop_order');
		$now_order = $database_shop_order->get_order_by_id($this->user_session['uid'],intval($_GET['order_id']));

		if(empty($now_order)){
			exit(json_encode(array('status'=>0,'msg'=>'当前订单不存在！')));
		}

		$order_condition['order_id'] = $now_order['order_id'];
		$data['is_del'] = 1;
		if($database_shop_order->where($order_condition)->data($data)->save()){
			exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>'删除失败！')));
		}
	}


	/*删除预约订单*/
	public function appoint_order_del(){
		$database_appoint_order = D('Appoint_order');
		$now_order = $database_appoint_order->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']));
		if(empty($now_order)){
			$this->error_tips('当前订单不存在！');
		}else if($now_order['paid']){
			$this->error_tips('当前订单已付款，不能删除。');
		}
		$condition_group_order['order_id'] = $now_order['order_id'];
		$data_group_order['paid'] = 3;
		if($database_appoint_order->where($condition_group_order)->data($data_group_order)->save()){
			$this->success_tips('删除成功！',U('My/appoint_order_list'));
		}else{
			$this->error_tips('删除失败！请重试。');
		}
	}


	/*店铺收藏*/
	public function group_store_collect(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		// merchant_store_foodshop
		$collection_list = D('')->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_collection'=>'msc',C('DB_PREFIX').'merchant_store_foodshop'=>'msf'))->where("msc.uid= '".$this->user_session['uid']."' AND ms.store_id = msc.store_id AND msf.store_id = msc.store_id")->select();

		foreach ($collection_list as $k => $v) {
	        $collection_list[$k]['pic_info'] = str_replace(',', '/', $v['pic_info']);
	    }
	    // dump($collection_list);
        $this->assign('collection_list',$collection_list);
		$this->display();
	}

	public function ajax_store_collect(){
		$listRows = 8;
		$firstRow = $_POST['page'] * $listRows;
		$collection_list = D('')->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_collection'=>'msc'))->where("msc.uid= '".$this->user_session['uid']."' AND ms.store_id = msc.store_id")->limit($firstRow,$listRows)->select();

		foreach ($collection_list as $k => $v) {
	        $collection_list[$k]['pic_info'] = str_replace(',', '/', $v['pic_info']);
	    }
		$html='';
		foreach ($collection_list as $key => $value) {
			$html .= '<dd>
        				<a href="" class="react">
							<div class="dealcard poicard">
								<div class="dealcard-img imgbox">
									<img src="'.$this->config['site_url'].'/upload/store/'.$value['pic_info'].'" style="width:100%;height:100%;">
								</div>
							    <div class="dealcard-block-right">
									<h6 class="dealcard-brand single-line">
										<span class="poiname">'.$value['name'].'</span>
										<span class="dealtype-icon">团</span>
									</h6>
									<div class="info">
										<span class="stars">&nbsp;</span>
									</div>
							        <div class="pos">'.$value['area_name'].'</div>
							    </div>
							</div>
       					</a>
       				</dd>';
		}


		if(is_array($collection_list)){
			exit(json_encode(array('error'=>1,'id'=>$id,'html'=>$html)));
		}else{
			exit(json_encode(array('error'=>2)));
		}
	}

	//手艺人收藏
	public function worker_collect(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$this->assign(D('Merchant_workers')->wap_get_worker_collect_list($this->user_session['uid']));
		$this->display();
	}

	/*商家收藏***商家中心暂时没有手机版***/
	public function merchant_collect(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}

		$this->assign(D('Merchant')->get_collect_list($this->user_session['uid']));
		$this->display();
	}
	/*     * *图片上传** */

	public function ajaxImgUpload() {
		$mulu=isset($_GET['ml']) ? trim($_GET['ml']):'group';
		$mulu=!empty($mulu) ? $mulu : 'group';
		$filename = trim($_POST['filename']);
		$img = $_POST[$filename];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$imgdata = base64_decode($img);
		$img_order_id = sprintf("%09d",$this->user_session['uid']);
		$rand_num = mt_rand(10,99).'/'.substr($img_order_id,0,3).'/'.substr($img_order_id,3,3).'/'.substr($img_order_id,6,3);
		$getupload_dir = "/upload/reply/".$mulu."/" .$rand_num;

		$upload_dir = "." . $getupload_dir;
		if (!is_dir($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}
		$newfilename = $mulu.'_' . date('YmdHis') . '.jpg';
		$save = file_put_contents($upload_dir . '/' . $newfilename, $imgdata);
		$save = file_put_contents($upload_dir . '/m_' . $newfilename, $imgdata);
		$save = file_put_contents($upload_dir . '/s_' . $newfilename, $imgdata);
		if ($save) {
			$this->dexit(array('error' => 0, 'data' => array('code' => 1, 'siteurl'=>$this->config['site_url'],'imgurl' =>$getupload_dir . '/' . $newfilename, 'msg' => '')));
		} else {
			$this->dexit(array('error' => 1, 'data' => array('code' => 0, 'url' => '', 'msg' => '保存失败！')));
		}
	}
	/*团购评价*/
	public function group_feedback(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);
		$this->assign('now_order',$now_order);
		if(empty($now_order)){
			$this->error_tips('当前订单不存在！');
		}
		if(empty($now_order['paid'])){
			$this->error_tips('当前订单未付款！无法评论');
		}
		if(empty($now_order['status'])){
			$this->error_tips('当前订单未消费！无法评论');
		}
		if($now_order['status'] == 2){
			$this->error_tips('当前订单已评论');
		}
		if(IS_POST){
			$score = intval($_POST['score']);
			if($score > 5 || $score < 1){
				$this->error_tips('评分只能1到5分');
			}
			$inputimg=isset($_POST['inputimg']) ? $_POST['inputimg'] :'';
			$pic_ids=array();
			if(!empty($inputimg)){
				$database_reply_pic = D('Reply_pic');
				foreach($inputimg as $imgv){
					$imgv=str_replace('/upload/reply/group/','',$imgv);
					$imgtmp=explode('/',$imgv);
					$imgname=$imgtmp[count($imgtmp)-1];
					$reply_pic['name'] = $imgname;
					$reply_pic['pic'] = str_replace('/'.$imgname,'',$imgv).','.$imgname;
					$reply_pic['uid'] = $this->user_session['uid'];
					$reply_pic['order_type'] = '0';
					$reply_pic['order_id'] = intval($now_order['order_id']);
					$reply_pic['add_time'] = $_SERVER['REQUEST_TIME'];
					$pic_ids[] = $database_reply_pic->data($reply_pic)->add();
				}
			}
			$database_reply = D('Reply');
			$data_reply['parent_id'] = $now_order['group_id'];
			$data_reply['store_id'] = $now_order['store_id'];
			$data_reply['mer_id'] = $now_order['mer_id'];
			$data_reply['score'] = $score;
			$data_reply['order_type'] = '0';
			$data_reply['order_id'] = intval($now_order['order_id']);
			$data_reply['anonymous'] = intval($_POST['anonymous']);
			$data_reply['comment'] = $_POST['comment'];
			$data_reply['uid'] = $this->user_session['uid'];
			$data_reply['pic'] = !empty($pic_ids) ? implode(',',$pic_ids):'';
			$data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
			$data_reply['add_ip'] = get_client_ip(1);
			if($database_reply->data($data_reply)->add()){
				D('Group')->setInc_group_reply($now_order,$score);
				D('Group_order')->change_status($now_order['order_id'],2);
				$database_merchant_score = D('Merchant_score');
				$now_merchant_score = $database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['mer_id'],'type'=>'1'))->find();
				if(empty($now_merchant_score)){
					$data_merchant_score['parent_id'] = $now_order['mer_id'];
					$data_merchant_score['type'] = '1';
					$data_merchant_score['score_all'] = $score;
					$data_merchant_score['reply_count'] = 1;
					$database_merchant_score->data($data_merchant_score)->add();
				}else{
					$data_merchant_score['score_all'] = $now_merchant_score['score_all']+$score;
					$data_merchant_score['reply_count'] = $now_merchant_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_merchant_score['pigcms_id']))->data($data_merchant_score)->save();
				}
				$now_store_score=$database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['store_id'],'type'=>'2'))->find();
				if(empty($now_store_score)){
					$data_store_score['parent_id'] = $now_order['store_id'];
					$data_store_score['type'] = '2';
					$data_store_score['score_all'] = $score;
					$data_store_score['reply_count'] = 1;
					$database_merchant_score->data($data_store_score)->add();
				}else{
					$data_store_score['score_all'] = $now_store_score['score_all']+$score;
					$data_store_score['reply_count'] = $now_store_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_store_score['pigcms_id']))->data($data_store_score)->save();
				}
				if($this->config['feedback_score_add']>0){
				  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['meal_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);

					D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],'用户'.$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'对'.$this->config['meal_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
				}

				$this->success_tips('评论成功',U('My/group_order',array('order_id'=>$now_order['order_id'])));
			}else{
				$this->error_tips('评论失败');
			}
		}
		$this->display();
	}
	/*订餐OR外卖评价*/
	public function meal_feedback(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$now_order = D('Meal_order')->where(array('uid' => $this->user_session['uid'], 'order_id' => intval($_GET['order_id'])))->find();
		$this->assign('now_order',$now_order);
		if(empty($now_order)){
			$this->error_tips('当前订单不存在！');
		}
		if(empty($now_order['paid'])){
			$this->error_tips('当前订单未付款！无法评论');
		}
		if(empty($now_order['status'])){
			$this->error_tips('当前订单未消费！无法评论');
		}
		if($now_order['status'] == 2){
			$this->error_tips('当前订单已评论');
		}
		if(IS_POST){
			$score = intval($_POST['score']);
			if($score > 5 || $score < 1){
				$this->error_tips('评分只能1到5分');
			}
			$inputimg=isset($_POST['inputimg']) ? $_POST['inputimg'] :'';
			$pic_ids=array();
			if(!empty($inputimg)){
				$database_reply_pic = D('Reply_pic');
				foreach($inputimg as $imgv){
					$imgv=str_replace('/upload/reply/meal/','',$imgv);
					$imgtmp=explode('/',$imgv);
					$imgname=$imgtmp[count($imgtmp)-1];
					$reply_pic['name'] = $imgname;
					$reply_pic['pic'] = str_replace('/'.$imgname,'',$imgv).','.$imgname;
					$reply_pic['uid'] = $this->user_session['uid'];
					$reply_pic['order_type'] = '1';
					$reply_pic['order_id'] = intval($now_order['order_id']);
					$reply_pic['add_time'] = $_SERVER['REQUEST_TIME'];
					$pic_ids[] = $database_reply_pic->data($reply_pic)->add();
				}
			}
			$database_reply = D('Reply');
			$data_reply['parent_id'] = $now_order['store_id'];
			$data_reply['store_id'] = $now_order['store_id'];
			$data_reply['mer_id'] = $now_order['mer_id'];
			$data_reply['score'] = $score;
			$data_reply['order_type'] = '1';
			$data_reply['order_id'] = intval($now_order['order_id']);
			$data_reply['anonymous'] = intval($_POST['anonymous']);
			$data_reply['comment'] = $_POST['comment'];
			$data_reply['uid'] = $this->user_session['uid'];
			$data_reply['pic'] = !empty($pic_ids) ? implode(',',$pic_ids):'';
			$data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
			$data_reply['add_ip'] = get_client_ip(1);
			if ($database_reply->data($data_reply)->add()) {
				D('Merchant_store')->setInc_meal_reply($now_order['store_id'],$score);
				D('Meal_order')->change_status($now_order['order_id'],2);

				$database_merchant_score = D('Merchant_score');
				$now_merchant_score = $database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['mer_id'],'type'=>'1'))->find();
				if(empty($now_merchant_score)){
					$data_merchant_score['parent_id'] = $now_order['mer_id'];
					$data_merchant_score['type'] = '1';
					$data_merchant_score['score_all'] = $score;
					$data_merchant_score['reply_count'] = 1;
					$database_merchant_score->data($data_merchant_score)->add();
				}else{
					$data_merchant_score['score_all'] = $now_merchant_score['score_all']+$score;
					$data_merchant_score['reply_count'] = $now_merchant_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_merchant_score['pigcms_id']))->data($data_merchant_score)->save();
				}
				$now_store_score=$database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['store_id'],'type'=>'2'))->find();
				if(empty($now_store_score)){
					$data_store_score['parent_id'] = $now_order['store_id'];
					$data_store_score['type'] = '2';
					$data_store_score['score_all'] = $score;
					$data_store_score['reply_count'] = 1;
					$database_merchant_score->data($data_store_score)->add();
				}else{
					$data_store_score['score_all'] = $now_store_score['score_all']+$score;
					$data_store_score['reply_count'] = $now_store_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_store_score['pigcms_id']))->data($data_store_score)->save();
				}

				if ($now_order['meal_type'] == 1) {
					if($this->config['feedback_score_add']>0){
					  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['group_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
					  	D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],'用户'.$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'对'.$this->config['group_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
					}
					$this->success_tips('评论成功', U('Takeout/order_detail', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
				} else {
					if($this->config['feedback_score_add']>0){
					  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['group_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);

				  		D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],'用户'.$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'对'.$this->config['group_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
					}
					$this->success_tips('评论成功', U('Food/order_detail', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
				}
			} else{
				$this->error_tips('评论失败');
			}
		}
		$this->display();
	}


	/*预约评论*/
	public function appoint_feedback(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$order_id = $_GET['order_id'] + 0;
		$now_order = D('Appoint_order')->where(array('uid' => $this->user_session['uid'], 'order_id' => intval($_GET['order_id'])))->find();
		$this->assign('now_order',$now_order);
		if(empty($now_order)){
			$this->error_tips('当前订单不存在！');
		}

		if($now_order['type'] == 0){
			if(empty($now_order['service_status'])){
				$this->error_tips('当前订单未付款！无法评论');
			}
			if(empty($now_order['service_status'])){
				$this->error_tips('当前订单未消费！无法评论');
			}
		}


		$where['order_id'] = $order_id;
		$database_appoint_comment = D('Appoint_comment');
		$appoint_order_num = $database_appoint_comment->where($where)->count();
		if($appoint_order_num > 0){
			$this->error_tips('当前订单已评论');
		}

		if(IS_POST){
			$score = intval($_POST['score']);
			$profession_total_score = $_POST['profession_score'] + 0;
			$communicate_total_score = $_POST['communicate_score'] + 0;
			$speed_total_score = $_POST['speed_score'] + 0;


			if($score > 5 || $score < 1 || $profession_total_score > 5 || $profession_total_score < 1 || $communicate_total_score > 5 || $communicate_total_score < 1|| $speed_total_score > 5 || $speed_total_score < 1 ){
				$this->error_tips('评分只能1到5分');
			}
			$inputimg=isset($_POST['inputimg']) ? $_POST['inputimg'] :'';
			$pic_ids=array();
			if(!empty($inputimg)){
				$database_reply_pic = D('Reply_pic');
				foreach($inputimg as $imgv){
					$imgv=str_replace('/upload/reply/appoint/','',$imgv);
					$imgtmp=explode('/',$imgv);
					$imgname=$imgtmp[count($imgtmp)-1];
					$reply_pic['name'] = $imgname;
					$reply_pic['pic'] = str_replace('/'.$imgname,'',$imgv).','.$imgname;
					$reply_pic['uid'] = $this->user_session['uid'];
					$reply_pic['order_type'] = '2';
					$reply_pic['order_id'] = intval($now_order['order_id']);
					$reply_pic['add_time'] = $_SERVER['REQUEST_TIME'];
					$pic_ids[] = $database_reply_pic->data($reply_pic)->add();
				}
			}
			$database_reply = D('Reply');
			$data_reply['parent_id'] = $now_order['appoint_id'];
			$data_reply['store_id'] = $now_order['store_id'];
			$data_reply['mer_id'] = $now_order['mer_id'];
			$data_reply['score'] = $score;
			$data_reply['order_type'] = '2';
			$data_reply['order_id'] = intval($now_order['order_id']);
			$data_reply['anonymous'] = intval($_POST['anonymous']);
			$data_reply['comment'] = $_POST['comment'];
			$data_reply['uid'] = $this->user_session['uid'];
			$data_reply['pic'] = !empty($pic_ids) ? implode(',',$pic_ids):'';
			$data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
			$data_reply['add_ip'] = get_client_ip(1);
			if ($database_reply->data($data_reply)->add()) {
				D('Appoint')->setInc_appoint_reply($now_order, $score);
				D('Appoint_order')->change_status($now_order['order_id'], 2);

				$database_merchant_score = D('Merchant_score');
				$now_merchant_score = $database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['mer_id'],'type'=>'1'))->find();
				if(empty($now_merchant_score)){
					$data_merchant_score['parent_id'] = $now_order['mer_id'];
					$data_merchant_score['type'] = '1';
					$data_merchant_score['score_all'] = $score;
					$data_merchant_score['reply_count'] = 1;
					$database_merchant_score->data($data_merchant_score)->add();
				}else{
					$data_merchant_score['score_all'] = $now_merchant_score['score_all']+$score;
					$data_merchant_score['reply_count'] = $now_merchant_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_merchant_score['pigcms_id']))->data($data_merchant_score)->save();
				}
				$now_store_score=$database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['store_id'],'type'=>'2'))->find();
				if(empty($now_store_score)){
					$data_store_score['parent_id'] = $now_order['store_id'];
					$data_store_score['type'] = '2';
					$data_store_score['score_all'] = $score;
					$data_store_score['reply_count'] = 1;
					$database_merchant_score->data($data_store_score)->add();
				}else{
					$data_store_score['score_all'] = $now_store_score['score_all']+$score;
					$data_store_score['reply_count'] = $now_store_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_store_score['pigcms_id']))->data($data_store_score)->save();
				}

				//工作人员评分start
				$database_merchant_workers = D('Merchant_workers');
				$database_appoint_visit_order_info = D('Appoint_visit_order_info');
				$database_appoint_supply = D('Appoint_supply');
				$Map['appoint_order_id'] = $now_order['order_id'];
				$appoint_visit_order_info = $database_appoint_visit_order_info->where($Map)->find();

				if(!$appoint_visit_order_info){
					$Map['order_id'] = $now_order['order_id'];
					$appoint_visit_order_info = $database_appoint_supply->where($Map)->find();
				}

				if($appoint_visit_order_info){
					$_Map['merchant_worker_id'] = $appoint_visit_order_info['merchant_worker_id'] > 0 ? $appoint_visit_order_info['merchant_worker_id'] : $appoint_visit_order_info['worker_id'];
					$merchant_workers_info = $database_merchant_workers->appoint_worker_info($_Map);
					$profession_total_score = $merchant_workers_info['profession_total_score'];
					$communicate_total_score = $merchant_workers_info['communicate_total_score'];
					$speed_total_score = $merchant_workers_info['speed_total_score'];
					$profession_num = $merchant_workers_info['profession_num'];
					$communicate_num = $merchant_workers_info['communicate_num'];
					$speed_num = $merchant_workers_info['speed_num'];

					if($merchant_workers_info){
						$profession_total_score += $_POST['profession_score'] + 0;
						$communicate_total_score += $_POST['communicate_score'] + 0;
						$speed_total_score += $_POST['speed_score'] + 0;
						$profession_num++;
						$communicate_num++;
						$speed_num++;

						$merchant_workers_data['profession_total_score'] = $profession_total_score;
						$merchant_workers_data['communicate_total_score'] = $communicate_total_score;
						$merchant_workers_data['speed_total_score'] = $speed_total_score;
						$merchant_workers_data['profession_num'] = $profession_num;
						$merchant_workers_data['communicate_num'] = $communicate_num;
						$merchant_workers_data['speed_num'] = $speed_num;
						$merchant_workers_data['profession_avg_score'] = $profession_total_score/$profession_num;
						$merchant_workers_data['communicate_avg_score'] = $communicate_total_score/$communicate_num;
						$merchant_workers_data['speed_avg_score'] = $speed_total_score/$speed_num;
						$merchant_workers_data['all_avg_score'] = ($merchant_workers_data['profession_avg_score'] + $merchant_workers_data['communicate_avg_score'] + $merchant_workers_data['speed_avg_score']) / 3;
						$merchant_workers_data['mer_id'] =  $now_order['mer_id'];
						$result = $database_merchant_workers->where($_Map)->data($merchant_workers_data)->save();
						if(!$result){
							$this->error_tips('工作人员评分失败！');
						}

						$database_appoint_comment = D('Appoint_comment');
						$_data['uid'] = $this->user_session['uid'];
						$_data['merchant_worker_id'] =  $appoint_visit_order_info['merchant_worker_id'] > 0 ? $appoint_visit_order_info['merchant_worker_id'] : $appoint_visit_order_info['worker_id'];
						$_data['appoint_id'] = $now_order['appoint_id'];
						$_data['profession_score'] = $_POST['profession_score'];
						$_data['communicate_score'] = $_POST['communicate_score'];
						$_data['speed_score'] = $_POST['speed_score'];
						//$_data['score'] = $_POST['score'];
						if($inputimg){
							$_data['comment_img'] = serialize($inputimg);
						}
						$_data['content'] = $_POST['comment'];
						$_data['add_time'] = time();
						$_data['order_id'] = $now_order['order_id'];
						$_data['mer_id'] = $now_order['mer_id'];

						if($database_appoint_comment->data($_data)->add()){

							$worker_where['merchant_worker_id'] = $appoint_visit_order_info['merchant_worker_id'] > 0 ?$appoint_visit_order_info['merchant_worker_id'] : $appoint_visit_order_info['worker_id'];
							$database_merchant_workers->where($worker_where)->setInc('comment_num');
							$database_appoint = D('Appoint');
							$database_appoint->where(array('appoint_id'=>$now_order['appoint_id']))->setInc('comment_num');
						}
					}else{
						$database_appoint_comment = D('Appoint_comment');
						$_data['score'] = $_POST['score'];
						$_data['uid'] = $this->user_session['uid'];
						$_data['merchant_worker_id'] =  0;
						$_data['appoint_id'] = $now_order['appoint_id'];
						if($inputimg){
							$_data['comment_img'] = serialize($inputimg);
						}
						$_data['content'] = $_POST['comment'];
						$_data['add_time'] = time();
						$_data['order_id'] = $now_order['order_id'];
						$_data['mer_id'] = $now_order['mer_id'];
						$database_appoint_comment->data($_data)->add();

					}
				}
				//工作人员评分end
				if($this->config['feedback_score_add']>0){
				  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['appoint_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
			  		D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],'用户'.$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'对'.$this->config['appoint_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
				}
				$this->success_tips('评论成功', U('My/appoint_order', array('order_id' => $now_order['order_id'])));
			} else{
				$this->error_tips('评论失败');
			}
		}
		$this->display();
	}


	/*全部订餐订单列表*/
	public function meal_order_list(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
		$where = " uid={$this->user_session['uid']} AND status<=3";//array('uid' => $this->user_session['uid'], 'status' => array('lt', 3));
		if ($status == -1) {
			$where .= " AND paid=0";
			$where['paid'] = 0;
		} elseif ($status == 1) {
			$where .= " AND paid=1 AND status=0";
		} elseif ($status == 2) {
			$where .= " AND paid=1 AND status=1";
		}
// 		$status == -1 && $where['paid'] = 0;
// 		$status == 1 && $where['status'] = 0;
// 		$status == 2 && $where['status'] = 1;

 		$where .= " AND is_del = 0";
		$order_list = D("Meal_order")->field(true)->where($where)->order('order_id DESC')->select();
		//$temp = $store_ids = array();
		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}

		foreach($list as $key=>$val){
			$list[$key]['order_url'] = U('Meal/order_detail', array('mer_id' => $val['mer_id'], 'store_id' => $val['store_id'], 'order_id' => $val['order_id']));
		}

		$this->assign('order_list', $list);

		$this->display();
	}

	public function ajax_meal_order_list(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
		$where = " uid={$this->user_session['uid']} AND status<=3";//array('uid' => $this->user_session['uid'], 'status' => array('lt', 3));
		if ($status == -1) {
			$where .= " AND paid=0";
			$where['paid'] = 0;
		} elseif ($status == 1) {
			$where .= " AND paid=1 AND status=0";
		} elseif ($status == 2) {
			$where .= " AND paid=1 AND status=1";
		}
// 		$status == -1 && $where['paid'] = 0;
// 		$status == 1 && $where['status'] = 0;
// 		$status == 2 && $where['status'] = 1;
 		$where .= " AND is_del = 0";
		$order_list = D("Meal_order")->field(true)->where($where)->order('order_id DESC')->select();
		//$temp = $store_ids = array();
		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}


		foreach($list as $key=>$val){
			$list[$key]['order_url'] = U('meal/order_detail', array('mer_id' => $val['mer_id'], 'store_id' => $val['store_id'], 'order_id' => $val['order_id']));
		}

		if(!empty($list)){
			exit(json_encode(array('status'=>1,'order_list'=>$list)));
		}else{
			exit(json_encode(array('status'=>0,'order_list'=>$list)));
		}
	}

	/*全部订餐订单列表*/
	public function shop_order_list(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
		$where = "is_del=0 AND uid={$this->user_session['uid']}";//array('uid' => $this->user_session['uid'], 'status' => array('lt', 3));
		
		if(!empty($_GET['store_id'])){
			$where .= " AND store_id=".intval($_GET['store_id']);
		}
		
		if ($status == -1) {
			$where .= " AND paid=0";
		} elseif ($status == 1) {
			$where .= " AND paid=1 AND status<2";
		} elseif ($status == 2) {
			$where .= " AND paid=1 AND status=2";
		}
// 		$status == -1 && $where['paid'] = 0;
// 		$status == 1 && $where['status'] = 0;
// 		$status == 2 && $where['status'] = 1;


 		$where .= " AND is_del = 0";
		$order_list = D("Shop_order")->get_order_list($where, 'order_id DESC', 11);//field(true)->where($where)->order('order_id DESC')->select();
		$order_list = $order_list['order_list'];
// 		echo "<pre/>";
// 		print_r($order_list);die;
		//$temp = $store_ids = array();
		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}

		foreach($list as $key=>$val){
			$list[$key]['order_url'] = U('Shop/status', array('order_id' => $val['order_id']));
		}

		$this->assign('order_list', $list);

		$this->display();
	}



	public function ajax_shop_order_list(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
		$where = "is_del=0 AND uid={$this->user_session['uid']}";//array('uid' => $this->user_session['uid'], 'status' => array('lt', 3));
		if(!empty($_GET['store_id'])){
			$where .= " AND store_id=".intval($_GET['store_id']);
		}
		if ($status == -1) {
			$where .= " AND paid=0";
		} elseif ($status == 1) {
			$where .= " AND paid=1 AND status<2";
		} elseif ($status == 2) {
			$where .= " AND paid=1 AND status=2";
		}

		$order_list = D("Shop_order")->field(true)->where($where)->order('order_id DESC')->select();
		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}

		foreach($list as $key=>$val){
			$list[$key]['order_url'] = U('Shop/status', array('order_id' => $val['order_id']));
		}

		if(!empty($list)){
			exit(json_encode(array('status'=>1,'order_list'=>$list)));
		}else{
			exit(json_encode(array('status'=>0,'order_list'=>$list)));
		}
	}


	/*优惠券列表*/
	public function card_list(){
		// if(!$this->is_wexin_browser){
		// $this->error_tips('请使用微信浏览优惠券！');
		// }
		$use = empty($_GET['use']) ? '0' : '1';
		if($_GET['coupon_type']=='mer') {
			$coupon_list = D('Card_new_coupon')->get_user_all_coupon_list($this->user_session['uid']);
			$this->assign('cate_platform', D('Card_new_coupon')->cate_platform());
		}else{
			$coupon_list=array();

			$coupon_list = D('System_coupon')->get_user_coupon_list($this->user_session['uid'], $this->user_session['phone']);

			$this->assign('cate_platform', D('System_coupon')->cate_platform());
		}
		$tmp = array();
		foreach ($coupon_list as $key => $v) {
			if (!empty($tmp[$v['is_use']][$v['coupon_id']])) {
				$tmp[$v['is_use']][$v['coupon_id']]['get_num']++;
			} else {
				$tmp[$v['is_use']][$v['coupon_id']] = $v;
				$mer = M('Merchant')->where(array('mer_id'=>$v['mer_id']))->find();
				$tmp[$v['is_use']][$v['coupon_id']]['merchant']=$mer['name'];
				$tmp[$v['is_use']][$v['coupon_id']]['get_num'] = 1;
				switch($v['type']){
                    case 'all':
                        $url = $this->config['site_url'].'/wap.php';
                        break;
                    case 'group':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Group&a=index';
                        break;
                    case 'meal':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Meal_list&a=index';
                        break;
                    case 'appoint':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Appoint&a=index';
                        break;
                    case 'shop':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Shop&a=index';
                        break;
                }
				$tmp[$v['is_use']][$v['coupon_id']]['url'] = $url;
			}

		}
		$this->assign('coupon_list', $tmp);
		$this->display();
	}


	public function cards()
	{
//		$card_list = D('Member_card_set')->get_all_card($this->user_session['uid']);
//		$this->assign('card_list',$card_list);
		//新商家会员卡
		$uid = $this->user_session['uid'];

		$card_list = D('Card_new')->get_user_all_card($uid);
		$this->assign('card_list',$card_list);

		$this->display('card_new');
	}



	public function order_list()
	{
		$type = isset($_GET['type']) ? intval($_GET['type']) : 1 ;
		if ($type == 1) {
			$order_list = D('Group')->wap_get_order_list($this->user_session['uid']);
			$this->assign('order_list',$order_list);
		} else {
			$where = array('uid' => $this->user_session['uid'], 'status' => array('lt', 3));
			$order_list = D("Meal_order")->field(true)->where($where)->order('order_id DESC')->select();
			$temp = $store_ids = array();
			foreach ($order_list as $st) {
				$store_ids[] = $st['store_id'];
			}
			$m = array();
			if ($store_ids) {
				$store_image_class = new store_image();
				$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
				foreach ($merchant_list as $li) {
					$images = $store_image_class->get_allImage_by_path($li['pic_info']);
					$li['image'] = $images ? array_shift($images) : array();
					unset($li['status']);
					$m[$li['store_id']] = $li;
				}
			}
			$list = array();
			foreach ($order_list as $ol) {
				if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
					$list[] = array_merge($ol, $m[$ol['store_id']]);
				} else {
					$list[] = $ol;
				}
			}
			$this->assign('order_list', $list);
		}
		$this->assign('type', $type);
		$this->display();
	}
	public function join_activity(){
		$uid = $this->user_session['uid'];
		import('@.ORG.wap_group_page');
		$tp_count = D('')->table(array(C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'merchant'=>'m'))->where("`ear`.`activity_list_id`=`eal`.`pigcms_id` AND `eal`.`mer_id`=`m`.`mer_id` AND `ear`.`uid`='$uid'")->group('`eal`.`pigcms_id`')->count();
		$P = new Page($tp_count,20,'page');
		$order_list = D('')->field('`eal`.`name` AS `product_name`,`m`.`name` AS `merchant_name`,`eal`.*,`m`.*')->table(array(C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'merchant'=>'m'))->where("`ear`.`activity_list_id`=`eal`.`pigcms_id` AND `eal`.`mer_id`=`m`.`mer_id` AND `ear`.`uid`='$uid'")->group('`eal`.`pigcms_id`')->order('`eal`.`pigcms_id` DESC')->limit($P->firstRow.','.$P->listRows)->select();
		// dump($order_list);
		if($order_list){
			$extension_image_class = new extension_image();
			foreach($order_list as &$value){
				$value['list_pic'] = $extension_image_class->get_image_by_path(array_shift(explode(';',$value['pic'])),'s');
				$value['url'] = U('My/join_activity_detail',array('id'=>$value['pigcms_id']));
				$value['money'] = floatval($value['money']);
				$value['type_txt'] = $this->activity_type_txt($value['type']);
			}
		}
		$this->assign('order_list',$order_list);
		$this->assign('pagebar',$P->show());
		$this->display();
	}
	public function join_activity_detail(){
		$condition_extension_activity_list['pigcms_id'] = $_GET['id'];
		$now_activity = D('Extension_activity_list')->field(true)->where($condition_extension_activity_list)->find();
		if(empty($now_activity)){
			$this->error_tips('该活动不存在');
		}
		$now_activity['type_txt'] = $this->activity_type_txt($now_activity['type']);
		$extension_image_class = new extension_image();
		$now_activity['list_pic'] = $extension_image_class->get_image_by_path(array_shift(explode(';',$now_activity['pic'])),'s');
		$now_activity['url'] = U('Wapactivity/detail',array('id'=>$now_activity['pigcms_id']));
		$now_activity['money'] = floatval($now_activity['money']);

		//活动归属的商家信息
		$now_merchant = D('Merchant')->field(true)->where(array('mer_id'=>$now_activity['mer_id']))->find();

		$record_list = D('Extension_activity_record')->field(true)->where(array('activity_list_id'=>$now_activity['pigcms_id'],'uid'=>$this->user_session['uid']))->order('`pigcms_id` DESC')->select();
		if(empty($record_list)){
			$this->error_tips('您未参与该活动');
		}
		$record_id_arr = array();
		foreach($record_list as $value){
			$record_id_arr[] = $value['pigcms_id'];
		}
		if($now_activity['type'] == 1){
			$number_list = D('Extension_yiyuanduobao_record')->field('`number`')->where(array('record_id'=>array('in',$record_id_arr)))->select();
			// shuffle($number_list);
			$this->assign('number_list',$number_list);
		}else if($now_activity['type'] == 2){
			$number_list = D('Extension_coupon_record')->field('`number`,`check_time`')->where(array('record_id'=>array('in',$record_id_arr)))->select();
			$this->assign('number_list',$number_list);
		}
		$this->assign('now_merchant',$now_merchant);
		$this->assign('now_activity',$now_activity);
		$this->assign('number_list',$number_list);
		$this->display();
	}
	protected function activity_type_txt($type){
		switch($type){
			case '1':
				return '一元夺宝';
			case '2':
				return '优惠券';
			case '3':
				return '秒杀';
			case '4':
				return '红包';
			case '5':
				return '卡券';
		}
	}
	public function join_lottery()
	{
		$result = D('Lottery')->join_lottery($this->user_session['uid']);
		$this->assign($result);
		$this->display();
	}

	public function follow_merchant()
	{
		$mod = new Model();
		$this->user_session['openid'];
//		$sql = "SELECT b.* FROM  ". C('DB_PREFIX') . "merchant_user_relation AS a INNER JOIN  ". C('DB_PREFIX') . "merchant as b ON a.mer_id=b.mer_id WHERE a.openid='onfo6t5WPe6wJswql3ljRX9aeEUA'";
		$sql = "SELECT b.* FROM  ". C('DB_PREFIX') . "merchant_user_relation AS a INNER JOIN  ". C('DB_PREFIX') . "merchant as b ON a.mer_id=b.mer_id WHERE a.openid='{$this->user_session['openid']}'";
		$res = $mod->query($sql);
		$merchant_image_class = new merchant_image();
		foreach ($res as &$r) {
			$images = explode(";", $r['pic_info']);
			$images = explode(";", $images[0]);
			$r['fans_count'] = M('Merchant_user_relation')->where(array('mer_id'=>$r['mer_id']))->count();
			$r['img'] = $merchant_image_class->get_image_by_path($images[0]);
			$r['url'] = C('config.site_url').'/wap.php?c=Index&a=index&token=' . $r['mer_id'];
		}
		$this->assign('follow_list', $res);
		$this->display();
	}

	public function cancel_follow()
	{

		$mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
		if (D('Merchant_user_relation')->where(array('mer_id' => $mer_id, 'openid' => $_SESSION['user']['openid']))->delete()) {
			D('Merchant')->where(array('mer_id' => $mer_id, 'fans_count' => array('gt', 0)))->setDec('fans_count');
			$this->success('取消关注成功', U('My/follow_merchant'));
		} else {

			$this->error('取消关注失败，请稍后重试', U('My/follow_merchant'));
		}
	}

	public function recharge(){

		if( $this->config['open_user_recharge']==0){
			$this->error_tips('平台充值已关闭');
		}
		if($_POST['money']>0){
			if(IS_POST){
				$data_user_recharge_order['uid'] = $this->now_user['uid'];
				$money = floatval($_POST['money']);
				if(empty($money) || $money > 200000){
					$this->error_tips('请输入有效的金额！最高不能超过20万元。');
				}
				if($_POST['label']){
					$data_user_recharge_order['label'] = $_POST['label'];
				}
				$data_user_recharge_order['money'] = $money;
				// $data_user_recharge_order['order_name'] = '帐户余额在线充值';
				$data_user_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
				$data_user_recharge_order['is_mobile_pay'] = 1;

				if($order_id = D('User_recharge_order')->data($data_user_recharge_order)->add()){
					if($_GET['type']=='gift'){
						redirect(U('Pay/check',array('order_id'=>$order_id,'type'=>'gift')));
					}elseif($_GET['type']=='classify') {
						redirect(U('Pay/check',array('order_id'=>$order_id,'type'=>'classify')));
					}elseif($_POST['type']=='level') {
						redirect(U('Pay/check',array('order_id'=>$order_id,'type'=>'recharge')));
					}else{
						redirect(U('Pay/check',array('order_id'=>$order_id,'type'=>'recharge')));
					}

				}
			}
		}else{
			$this->display();
		}
	}

	public function withdraw(){
		if($this->config['company_pay_open']=='0') {
			$this->error_tips('平台没有开启提现功能！');
		}
		if($this->config['open_allinyun']==1){
			redirect(U('setAccountDeposit/withdraw'));
		}
		$user_info = M('User')->where(array('uid'=>$this->user_session['uid']))->find();
		if($user_info['score_recharge_money']>0 && $user_info['now_money']<$user_info['score_recharge_money']){
			$this->error_tips('用户积分兑换余额数据错误');
		}
		$can_withdraw_money = $user_info['now_money']>=$user_info['score_recharge_money']?floatval((int)(($user_info['now_money']-$user_info['score_recharge_money'])*100)/100):$user_info['now_money'];
		if($can_withdraw_money<0){
			$this->error_tips('可提现金额错误');
		}
		if ($user_info['frozen_time'] < $_SERVER['REQUEST_TIME'] && $_SERVER['REQUEST_TIME'] < $user_info['free_time']) {
			$user_info['can_withdraw_money'] = $can_withdraw_money-$user_info['frozen_money']>0? $can_withdraw_money-$user_info['frozen_money']:0;
		}else{
			$user_info['can_withdraw_money'] = $can_withdraw_money;
		}
		$this->assign('user_info',$user_info);
		if(empty($user_info['openid'])){
			if($_POST['pay_type']==2){
				$this->error('您没有绑定微信');
			}
			$this->error_tips('您没有绑定微信');
		}
		if(IS_POST){
			if($_POST['money']>0){
				$money = $_POST['money'];
				if($money<$this->config['company_least_money']){
					$this->error_tips('不能低于最低提款额 '.$this->config['company_least_money'].' 元!');
				}

				if($money>$can_withdraw_money){
					$this->error_tips('提款超出限额，申请失败！');
				}

				$data_companypay['pay_type'] = 'user';
				$data_companypay['pay_id'] = $user_info['uid'];
				$data_companypay['openid'] = $user_info['openid'];
				$data_companypay['nickname'] = $_POST['truename'];
				$data_companypay['phone'] = $user_info['phone'];
				$data_companypay['money'] = bcmul($money*((100-$this->config['company_pay_user_percent'])/100),100);
				$data_companypay['desc'] = "用户提现对{$money}元，用户ID: ".$user_info['uid'] ;
				if($this->config['company_pay_user_percent']>0){
					$data_companypay['desc'] .= '|手续费 '.$money*($this->config['company_pay_user_percent'])/100 .' 比例 '.$this->config['company_pay_user_percent'].'%';
				}
				$data_companypay['status'] = 0;
				$data_companypay['add_time'] = time();
				$use_result = D('User')->user_money($user_info['uid'],$money,'提款 '.$money.' 扣除余额'.$data_companypay['desc'],0,0,1);
				if($use_result['error_code']){
					$this->error_tips($use_result['msg']);
				}else{
					D('Companypay')->add($data_companypay);
					D('Scroll_msg')->add_msg('user_withdraw',$user_info['uid'],'用户'.$user_info['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '提现成功！');
					if($_POST['pay_type']==2){
						if($this->config['open_score_fenrun']){
							$url = U('My/money_list');
						}else{
							$url =U('My/transaction');
						}
						$this->success("申请成功，请等待审核！",$url);die;
					}
					$this->success_tips("申请成功，请等待审核！");die;
				}
			}else{
				$this->error_tips('数据不正确！');
			}
		}else{
			$where['pay_type']='user';
			$where['pay_id']=$user_info['uid'];
			$withdraw = M('Companypay');
			$count_withdraw = $withdraw->where($where)->count();
			import('@.ORG.system_page');
			$p = new Page($count_withdraw, 5);
			$withdraw_list = $withdraw->field('money,status,add_time,pay_time')->where($where)->order('pigcms_id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
			$pagebar = $p->show();
			$share = new WechatShare($this->config,$_SESSION['openid']);
			$this->BioAuthticMethod = $share->getBioAuthticMethod($this->static_path);
			$this->assign('BioAuthticMethod', $this->BioAuthticMethod);
			$this->assign('pagebar', $pagebar);
			$this->assign('draw_info',$withdraw_list);
			$this->display();
		}
	}

	//积分充值余额，这部分余额不能提现
	public function score_recharge(){
		if(!$this->config['score_recharge']||$this->config['open_extra_price']==1) {
			$this->error_tips('平台没有开启'.$this->config['score_name'].'兑换余额功能！');
		}
		$user_score_use_percent = C('config.user_score_recharge_percent');
		$score_count = D('User')->where(array('uid'=>$this->user_session['uid']))->getField('score_count');
		if($_POST['score']>0){
			$_POST['score'] = abs($_POST['score']);
			if(IS_POST && $score_count > 0){
				if($_POST['score'] <= $score_count){
					$score_count = $_POST['score'];
				}
				$score_deducte = bcdiv($score_count,$user_score_use_percent,2);
				if($res = D('User')->add_money($this->user_session['uid'],floatval($score_deducte),$this->config['score_name'].'兑换 '.$score_deducte.' 元到账户余额')){
					D('User')->use_score($this->user_session['uid'],$score_count,''.$this->config['score_name'].'兑换余额，减扣'.$this->config['score_name'].' '.$score_count.' 个');
					D('User')->add_score_recharge_money($this->user_session['uid'],$score_deducte,'保存'.$this->config['score_name'].'兑换记录 '.$score_deducte.' 元');
					D('Scroll_msg')->add_msg('score_recharge',$this->user_session['uid'],'用户'.$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'使用'.$this->config['score_name'].'兑换余额');

					$this->success_tips($this->config['score_name']."兑换余额成功！",U('score_recharge'));
				}else{
					$this->error_tips($res['msg']);
				}
			}else{
				$this->error_tips('非法请求');
			}
		}else{
			$score_count = D('User')->where(array('uid'=>$this->user_session['uid']))->getField('score_count');
			$score_deducte = bcdiv($score_count,$user_score_use_percent,2);
			$this->assign('score_count',$score_count);
			$this->assign('score_deducte',$score_deducte);
			$share = new WechatShare($this->config,$_SESSION['openid']);
			$this->BioAuthticMethod = $share->getBioAuthticMethod($this->static_path);
			$this->assign('BioAuthticMethod', $this->BioAuthticMethod);
			$this->display();
		}
	}

	public function lifeservice(){
		$order_list = D('Service_order')->field(true)->where(array('uid'=>$this->user_session['uid'],'status'=>array('neq','0')))->order('`order_id` DESC')->select();
		foreach($order_list as &$value){
			$value['type_txt'] = $this->lifeservice_type_txt($value['type']);
			$value['type_eng'] = $this->lifeservice_type_eng($value['type']);
			$value['infoArr'] = unserialize($value['info']);
			$value['order_url'] = U('My/lifeservice_detail',array('id'=>$value['order_id']));
		}
		$this->assign('order_list', $order_list);
		// dump($order_list);
		$this->display();
	}
	public function lifeservice_detail(){
		$now_order = D('Service_order')->field(true)->where(array('order_id'=>$_GET['id']))->find();
		$now_order['infoArr'] = unserialize($now_order['info']);
		$now_order['type_txt'] = $this->lifeservice_type_txt($now_order['type']);
		$now_order['type_eng'] = $this->lifeservice_type_eng($now_order['type']);
		$now_order['pay_money'] = floatval($now_order['pay_money']);
		$this->assign('now_order', $now_order);
		// dump($order_list);
		$this->display();
	}
	public function spread_list(){
		if(!isset($_GET['status'])){
			//待结算订单
			$spread_list = D('User_spread_list')->field(true)->where(array('status'=>'0',array('_string'=>'uid = '.$this->user_session['uid'].' OR change_uid='.$this->user_session['uid'])))->order('`pigcms_id` DESC')->select();
			if($spread_list){
				foreach($spread_list as $key=>$value){
					if($value['order_type'] == 'group'){
						$order_info = $spread_list[$key]['order_info'] = D('Group_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
						if($order_info['status'] == 0){
							unset($spread_list[$key]);
							continue;
						}
						$value['group_info'] = $spread_list[$key]['group_info'] = D('Group')->field('`group_id`,`name`')->where(array('group_id'=>$value['third_id']))->find();
					}else if($value['order_type']=='shop'){
						$order_info = $spread_list[$key]['order_info'] = D('Shop_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();

						if($order_info['status'] == 0){
							unset($spread_list[$key]);
							continue;
						}
						$value['shop_info'] = $spread_list[$key]['shop_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();

					}else if($value['order_type']=='store'|| $value['order_type']=='cash'){
						$order_info = $spread_list[$key]['order_info'] = D('Store_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
						if($order_info['paid'] == 0){
							unset($spread_list[$key]);
							continue;
						}
						$value['store_info'] = $spread_list[$key]['store_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();

					}else if($value['order_type']=='meal'){
						$order_info = $spread_list[$key]['order_info'] = D('Foodshop_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
						if($order_info['paid'] == 0){
							unset($spread_list[$key]);
							continue;
						}
						$value['meal_info'] = $spread_list[$key]['store_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();

					}else if($value['order_type']=='sub_card'){
						$order_info = $spread_list[$key]['order_info'] = D('Sub_card_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
						if($order_info['paid'] == 0){
							unset($spread_list[$key]);
							continue;
						}
						//$value['sub_card_info'] = $spread_list[$key]['store_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();

					}else if($value['order_type']=='yuedan'){
						$order_info = $spread_list[$key]['order_info'] = D('Yuedan_service_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					}
					if($value['spread_uid']){
						$value['spread_user'] = $spread_list[$key]['spread_user'] = D('User')->get_user($value['spread_uid']);
					}
					$value['get_user'] = $spread_list[$key]['get_user'] = D('User')->get_user($value['get_uid']);

					//组成描述语句
					if($value['spread_user']){
						$spread_list[$key]['desc']['txt'] = '子用户 '.$value['spread_user']['nickname'].' 推广用户 '.$value['get_user']['nickname'].' 购买';
					}else{
						$spread_list[$key]['desc']['txt'] = '推广用户 '.$value['get_user']['nickname'].' 购买';
					}

					if($value['change_uid']!=0){
						if($this->user_session['uid']!=$value['change_uid']){
							$change_user = D('User')->get_user($value['change_uid'],'uid');
							$spread_list[$key]['desc']['txt'] .= '（佣金已结算给'.$change_user['nickname'].')';
						}else{
							$spread_list[$key]['desc']['txt'] .= '（佣金由'.$value['get_user']['nickname'].'处结算过来)';
						}
					}

					if($value['order_type'] == 'group'){
						$spread_list[$key]['desc']['url'] =  U('Group/detail',array('group_id'=>$value['group_info']['group_id']));
						$spread_list[$key]['desc']['info'] = $order_info['total_money'].'元产品';
					}elseif($value['order_type']=='shop'){
						$spread_list[$key]['desc']['url'] =  U('Shop/detail',array('store_id'=>$value['shop_info']['store_id']));
						$spread_list[$key]['desc']['info'] = $order_info['total_price'].'元产品';
					}elseif($value['order_type']=='store'|| $value['order_type']=='cash'){
						$spread_list[$key]['desc']['url'] =  U('My/store_order_list');
						$spread_list[$key]['desc']['info'] = $order_info['total_price'].'元产品';
					}elseif($value['order_type']=='meal'){
						$spread_list[$key]['desc']['url'] =  U('My/foodshop_order_list');
						$spread_list[$key]['desc']['info'] = $order_info['total_price'].'元产品';
					}elseif($value['order_type']=='sub_card'){
						$spread_list[$key]['desc']['url'] =  U('My/sub_card_list');
						$spread_list[$key]['desc']['info'] = $order_info['money'].'元产品';
					}elseif($value['order_type']=='yuedan'){
						$spread_list[$key]['desc']['url'] =  U('Yuedan/my_index');
						$spread_list[$key]['desc']['info'] = $order_info['money'].'元产品';
					}
				}
			}
		}else{
			$condition_spread_list['_string'] ='uid = '.$this->user_session['uid'].' OR change_uid='.$this->user_session['uid'];
			//$condition_spread_list['uid'] = $this->user_session['uid'];
			if(in_array($_GET['status'],array(0,1,2))){
				$condition_spread_list['status'] = $_GET['status'];
			}
			$spread_list = D('User_spread_list')->field(true)->where($condition_spread_list)->order('`pigcms_id` DESC')->select();
			foreach($spread_list as $key=>$value){
				if($value['spread_uid']){
					$value['spread_user'] = $spread_list[$key]['spread_user'] = D('User')->get_user($value['spread_uid']);
				}
				$value['get_user'] = $spread_list[$key]['get_user'] = D('User')->get_user($value['get_uid']);

				if($value['order_type'] == 'group'){
					$value['group_info'] = $spread_list[$key]['group_info'] = D('Group')->field('`group_id`,`name`')->where(array('group_id'=>$value['third_id']))->find();
					//if($value['status'] == 0){
					$value['order_info'] = $spread_list[$key]['order_info'] = D('Group_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					//}
				}else if($value['order_type'] == 'shop'){
					$value['shop_info'] = $spread_list[$key]['shop_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();
					//if($value['status'] == 0){
					$value['order_info'] = $spread_list[$key]['order_info'] = D('Shop_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					//}
				}else if($value['order_type'] == 'store'|| $value['order_type']=='cash'){
					$value['store_info'] = $spread_list[$key]['store_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();
					//if($value['status'] == 0){
					$value['order_info'] = $spread_list[$key]['order_info'] = D('Store_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					//}
				}else if($value['order_type'] == 'meal'){
					$value['meal_info'] = $spread_list[$key]['store_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();
					//if($value['status'] == 0){
					$value['order_info'] = $spread_list[$key]['order_info'] = D('Foodshop_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					//}
				}else if($value['order_type'] == 'sub_card'){
					//$value['sub_card_info'] = $spread_list[$key]['store_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();
					//if($value['status'] == 0){
					$value['order_info'] = $spread_list[$key]['order_info'] = D('Sub_card_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					//}
				}else if($value['order_type'] == 'yuedan'){
					//$value['sub_card_info'] = $spread_list[$key]['store_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();
					//if($value['status'] == 0){
					$value['order_info'] = $spread_list[$key]['order_info'] = D('Yuedan_service_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					//}
				}

				//组成描述语句
				if($value['spread_user']){
					$spread_list[$key]['desc']['txt'] = '子用户 '.$value['spread_user']['nickname'].' 推广用户 '.$value['get_user']['nickname'].' 购买';
				}else{
					$spread_list[$key]['desc']['txt'] = '推广用户 '.$value['get_user']['nickname'].' 购买';
				}

				if($value['change_uid']!=0){
					if($this->user_session['uid']!=$value['change_uid']){
						$change_user = D('User')->get_user($value['change_uid'],'uid');
						$spread_list[$key]['desc']['txt'] = '（佣金已结算给'.$change_user['nickname'].')';
					}else{
						$spread_list[$key]['desc']['txt'] = '（佣金由'.$value['get_user']['nickname'].'处结算过来)';
					}
				}
				if($value['order_type'] == 'group'){
					$spread_list[$key]['desc']['url'] = U('Group/detail',array('group_id'=>$value['group_info']['group_id']));
					$spread_list[$key]['desc']['info'] = $value['order_info']['total_money'].'元产品';
				}else if($value['order_type'] == 'shop'){
					$spread_list[$key]['desc']['url'] = U('shop/detail',array('shop_id'=>$value['shop_info']['shop_id']));
					$spread_list[$key]['desc']['info'] = $value['order_info']['total_price'].'元产品';
				}else if($value['order_type'] == 'store'|| $value['order_type']=='cash'){
					$spread_list[$key]['desc']['info'] = $value['order_info']['total_price'].'元产品';
					$spread_list[$key]['desc']['url'] =  U('My/store_order_list');
					$alia_name = array('store'=>'优惠买单','cash'=>'到店付');
					$spread_list[$key]['desc']['info'] = $value['store_info']['name'] .$alia_name[$value['order_type']];
				}else if($value['order_type'] == 'meal'){
					$spread_list[$key]['desc']['info'] = $value['order_info']['total_price'].'元产品';
					$spread_list[$key]['desc']['url'] =  U('My/foodshop_order_list');
					$alia_name = array('store'=>C('config.meal_alias_name'));
					$spread_list[$key]['desc']['info'] = $value['meal_info']['name'] .$alia_name[$value['order_type']];
				}else if($value['order_type'] == 'sub_card'){
					$spread_list[$key]['desc']['info'] = $value['order_info']['money'].'元产品';
					$spread_list[$key]['desc']['url'] =  U('My/sub_card_list');
					$alia_name = array('sub_card'=>'免单套餐');
					$spread_list[$key]['desc']['info'] = $value['sub_card_info']['name'] .$alia_name[$value['order_type']];
				}else if($value['order_type'] == 'yuedan'){
					$spread_list[$key]['desc']['info'] = $value['order_info']['money'].'元产品';
					$spread_list[$key]['desc']['url'] =  U('Yuedan/my_index');
					$alia_name = array('sub_card'=>'约单');
					$spread_list[$key]['desc']['info'] = $value['yuedan']['name'] .$alia_name[$value['order_type']];
				}
			}
		}

		$this->assign('spread_list',$spread_list);
		$this->display();
	}
	public function spread_check(){
		if($this->config['open_extra_price']==1){
			$money_name = C('config.extra_price_alias_name');
		}else{
			$money_name = '佣金';
		}
		$where = array(
				'pigcms_id'=>$_GET['id'],
				'_string'=>'uid='.$this->user_session['uid'].' OR change_uid='.$this->user_session['uid']
		);
		if(C('config.open_distributor')>0){
			$res= D('Distributor_agent')->get_effective($this->user_session['uid'],1);
			if($res['error_code']){
				$this->error($res['msg']);
			}
		}
		$now_spread = D('User_spread_list')->where($where)->find();
		//dump($now_spread);
		if($now_spread && $now_spread['status'] == 0){
			if($now_spread['order_type'] == 'group'){
				$order_info = D('Group_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();
				if($order_info['status'] == '1' || $order_info['status'] == '2'){
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>1))->save()){
						//D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['group_alias_name'].'商品获得佣金');
						if($now_spread['change_uid']!=0){
							D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买'.$this->config['group_alias_name'].'商品获得'.$money_name.'('.$money_name.'过户)');

						}else{
							if($this->config['open_extra_price']==1){
								D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['group_alias_name'].'商品获得'.$money_name);
							}else{
								D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['group_alias_name'].'商品获得'.$money_name);

								D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
							}
						}
						$this->success('结算完成');
					}else{
						$this->error('操作失败');
					}
				}else if($order_info['status'] == '3' || $order_info['status'] == '6'){
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>2))->save()){
						$this->success('用户已退款');
					}else{
						$this->error('操作失败');
					}
				}
			}else if ($now_spread['order_type']=='shop'){
				$order_info = D('Shop_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();
				if($order_info['status'] == '1' || $order_info['status'] == '2' || $order_info['status'] == '3'){
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>1))->save()){
						//D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['shop_alias_name'].'商品获得佣金');

						if($now_spread['change_uid']!=0){
							D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买'.$this->config['shop_alias_name'].'商品获得'.$money_name.'('.$money_name.'过户)');
						}else{
							if($this->config['open_extra_price']==1){
								D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['shop_alias_name'].'商品获得'.$money_name);
							}else{
								D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['shop_alias_name'].'商品获得'.$money_name);
								D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
							}
						}
						$this->success('结算完成');
					}else{
						$this->error('操作失败');
					}
				}else if($order_info['status'] == '4'||$order_info['status'] == '5'){
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>2))->save()){
						$this->success('用户已退款');
					}else{
						$this->error('操作失败');
					}
				}
			}else if ($now_spread['order_type']=='store'||$now_spread['order_type']=='cash'){
				$order_info = D('Store_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();

					$alia_name = array('store'=>'优惠买单','cash'=>'到店付');
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>1))->save()){
						if($now_spread['change_uid']!=0){
							D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买'.$alia_name[$now_spread['order_type']].'商品获得'.$money_name.'('.$money_name.'过户)');
						}else{
							if($this->config['open_extra_price']==1){
								D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.$alia_name[$now_spread['order_type']].'商品获得'.$money_name);
							}else{

								D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$alia_name[$now_spread['order_type']].'商品获得'.$money_name);
								D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
							}
						}
						$this->success('结算完成');
					}else{
						$this->error('操作失败');
					}

			}else if ($now_spread['order_type']=='meal'){
				$order_info = D('Foodshop_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();

					$alia_name = array('store'=>C('config.meal_alias_name'));
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>1))->save()){
						//D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买餐饮商品获得佣金');
						if($now_spread['change_uid']!=0){
							D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买餐饮商品获得'.$money_name.'('.$money_name.'过户)');
						}else{
							if($this->config['open_extra_price']==1){
								D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['meal_alias_name'].'商品获得'.$money_name);
							}else{
								D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['meal_alias_name'].'商品获得'.$money_name);
								D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
							}
						}
						$this->success('结算完成');
					}else{
						$this->error('操作失败');
					}

			}else if ($now_spread['order_type']=='sub_card'){
				$order_info = D('Sub_card_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();

				$alia_name = array('sub_card'=>'次卡套餐');
				if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>1))->save()){
					//D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买餐饮商品获得佣金');
					if($now_spread['change_uid']!=0){
						D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买免单套餐获得'.$money_name.'('.$money_name.'过户)');
					}else{

						D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买免单套餐商品获得'.$money_name);
						D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');

					}
					$this->success('结算完成');
				}else{
					$this->error('操作失败');
				}

			}else if ($now_spread['order_type']=='yuedan'){
				$order_info = D('Yuedan_service_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();

				$alia_name = array('yuedan'=>'约单');
				if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>1))->save()){
					//D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买餐饮商品获得佣金');
					if($now_spread['change_uid']!=0){
						D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买约单获得'.$money_name.'('.$money_name.'过户)');
					}else{

						D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买约单获得'.$money_name);
						D('Scroll_msg')->add_msg('spread',$now_spread['uid'],'用户'.$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');

					}
					$this->success('结算完成');
				}else{
					$this->error('操作失败');
				}

			}

		}
	}

	public function spread_user_list(){
		if(!empty($_GET['uid'])){
			$user = M('User')->where(array('uid'=>$_GET['uid']))->find();
		}else{
			$user = $_SESSION['user'];
		}

		$res = D('User_spread')->get_spread_user($user['openid'],$user['uid']);
		$this->assign('res',$res);
		$this->assign('user',$user);
		$this->display();
	}

	//计算用户列表
	public function my_settlement_user(){

		$res = D('User_spread')->get_spread_change_user($this->user_session['uid']);
//		dump($res);
		$this->assign('res',$res);
		//$this->assign('user',$user);
		$this->display();
	}

	//解绑关系
	public function unbind_spread_change(){
		$uid=$_POST['uid'];
		if(M('User')->where(array('uid'=>$uid))->setField('spread_change_uid',0)){

			$this->AjaxReturn(array('error_code'=>0,'msg'=>'解绑成功'));
		}else{
			$this->AjaxReturn(array('error_code'=>1,'msg'=>'解绑失败'));
		}
		exit;
	}

	protected function lifeservice_type_txt($type){
		switch($type){
			case '1':
				$type_txt = '水费';
				break;
			case '2':
				$type_txt = '电费';
				break;
			case '3':
				$type_txt = '煤气费';
				break;
			default:
				$type_txt = '生活服务';
		}
		return $type_txt;
	}
	protected function lifeservice_type_eng($type){
		switch($type){
			case '1':
				$type_txt = 'water';
				break;
			case '2':
				$type_txt = 'electric';
				break;
			case '3':
				$type_txt = 'gas';
				break;
			default:
				$type_txt = 'life';
		}
		return $type_txt;
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
	/****等级升级****/
	public function levelUpdate(){
		if($this->config['level_onoff']==0){
			$this->error_tips('平台没有开启该功能');
		}
		$now_user = D('User')->get_user($this->user_session['uid']);
		$next = $now_user['level'];

		if($_GET['nextlevel']){
			$next = $_GET['nextlevel']-1;
		}
		$level_adver = D('Adver')->get_adver_by_key('levelad',5);
		$this->assign('level_adver',$level_adver);
		$nextlevel = M('User_level')->where(array('level'=>array('gt',$next)))->find();
		$this->assign('nextlevel',$nextlevel['level']);
		$this->display();
	}

	/*     * json 格式封装函数* */

	private function dexit($data = '') {
		if (is_array($data)) {
			echo json_encode($data);
		} else {
			echo $data;
		}
		exit();
	}

	public function pay(){
		if(IS_POST){
			if($order_id = $this->store_order()){
				redirect(U('Pay/check',array('type'=>'store','order_id'=>$order_id)));
			}else{
				$this->error_tips('创建订单失败');
			}
		}
		
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
		if(!$store_id && !$mer_id){
			$this->error_tips('非法访问');
		}
		if($store_id){
			$now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
			$now_store['discount_txt'] = unserialize($now_store['discount_txt']);
			$now_store['store_name']= $now_store['name'];
			$now_store['discount_type']= isset($now_store['discount_txt']['discount_type']) ? $now_store['discount_txt']['discount_type'] : 0;
			$now_store['discount_percent']= isset($now_store['discount_txt']['discount_percent']) ? $now_store['discount_txt']['discount_percent'] : 0;
			$now_store['condition_price']=isset($now_store['discount_txt']['condition_price']) ? $now_store['discount_txt']['condition_price'] : 0;
			$now_store['minus_price']= isset($now_store['discount_txt']['minus_price']) ? $now_store['discount_txt']['minus_price'] : 0;
			//扫到店付码成为推广用户
			if($_SESSION['openid'] && $_GET['spread']){
				D('Merchant_spread')->spread_add($now_store['mer_id'], $_SESSION['openid'],'scanpay',$store_id);
			}

			if($this->config['open_score_get_percent']){
				$now_store['score_precent'] = $this->config['score_get_percent'];
			}else{
				$now_store['score_precent'] = $this->config['user_score_get']*100;
			}
			
			
			if($this->config['store_ticket_have'] && $now_store['bind_store_trade'] == 'ticket'){
				$database_store_trade_ticket = D('Merchant_store_trade_ticket');
				$condition_store_trade_ticket = array('store_id'=>$now_store['store_id']);
				$now_store['store_trade_ticket'] = $database_store_trade_ticket->where($condition_store_trade_ticket)->find();
				
				$now_store['store_trade_ticket']['limit_num'] = $now_store['store_trade_ticket']['limit_num'] > 1 ? $now_store['store_trade_ticket']['limit_num'] : 1;
				if(empty($now_store['store_trade_ticket']['insure_name'])){
					$now_store['store_trade_ticket']['insure_name'] = '保险';
				}
				$display_tpl_name = 'pay_ticket';
			}
			
			$this->assign('store',$now_store);
		}else if($mer_id){
			$store_list = D('Merchant_store')->get_store_list_by_merId($mer_id);

			if(empty($store_list)){
				$this->error_tips('商家暂未创建店铺');
			}
			if(count($store_list) == 1){
				$_GET['store_id'] = $store_list[0]['store_id'];
				$_GET['mer_id'] = 0;
				$this->pay();
				die;
			}
			$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
			foreach ($store_list as &$vo) {
				if($user_long_lat){
					$vo['Srange'] = getDistance($user_long_lat['lat'], $user_long_lat['long'], $vo['lat'], $vo['long']);
					$vo['range'] = getRange($vo['Srange'], false);
				}
				$vo['discount_txt'] = unserialize($vo['discount_txt']);
				$vo['discount_type']= isset($vo['discount_txt']['discount_type']) ? $vo['discount_txt']['discount_type'] : 0;
				$vo['discount_percent']= isset($vo['discount_txt']['discount_percent']) ? $vo['discount_txt']['discount_percent'] : 0;
				$vo['condition_price']=isset($vo['discount_txt']['condition_price']) ? $vo['discount_txt']['condition_price'] : 0;
				$vo['minus_price']= isset($vo['discount_txt']['minus_price']) ? $vo['discount_txt']['minus_price'] : 0;
				$rangeSort[] = array('store_id'=>$vo['store_id'],'juli'=>$vo['Srange']);
			}
			$res = sortArrayAsc($store_list,'Srange');
			$this->assign('store_list',$res);
		}
		
		if($_GET['money']){
			$default_money = $_GET['money'];
		}else if($now_store['store_trade_ticket'] && $now_store['store_trade_ticket']['default_money']){
			$default_money = $now_store['store_trade_ticket']['default_money'];
		}

		$level_off=false;
		$user_= M('User')->where(array('uid'=>$this->user_session['uid']))->find();
		$this->user_session['level'] = $user_['level'];
		if(  !empty($this->user_level) && !empty($this->user_session) && isset($this->user_session['level'])){
			$leveloff=!empty($now_store['leveloff']) ? unserialize($now_store['leveloff']) :'';

			/****type:0无优惠 1百分比 2立减*******/
			if(!empty($leveloff) && isset($leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])){
				$level_off=$leveloff[$this->user_session['level']];
				if($level_off['type']==1){
					$level_off['offstr']='单价按原价'.$level_off['vv'].'%来结算';
				}elseif($level_off['type']==2){
					$level_off['offstr']='单价立减'.$level_off['vv'].'元';
				}

			}
			$this->assign('level_off',$level_off);

		}

		$this->assign('default_money',$default_money);

		$this->display($display_tpl_name);
	}

	
	
	public function ajax_get_store_list(){
		$mer_id = isset($_POST['mer_id']) ? intval($_POST['mer_id']) : 0;
		$store_list = D('Merchant_store')->get_store_list_by_merId($mer_id);
		$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		foreach ($store_list as &$vo) {
			//$vo['juli'] = ;
			$vo['Srange'] = getDistance($user_long_lat['lat'], $user_long_lat['long'], $vo['lat'], $vo['long']);
			$vo['range'] = getRange($vo['Srange'], false);
			$vo['discount_txt'] = unserialize($vo['discount_txt']);
			$vo['discount_type']= isset($vo['discount_txt']['discount_type']) ? $vo['discount_txt']['discount_type'] : 0;
			$vo['discount_percent']= isset($vo['discount_txt']['discount_percent']) ? $vo['discount_txt']['discount_percent'] : 0;
			$vo['condition_price']=isset($vo['discount_txt']['condition_price']) ? $vo['discount_txt']['condition_price'] : 0;
			$vo['minus_price']= isset($vo['discount_txt']['minus_price']) ? $vo['discount_txt']['minus_price'] : 0;
			$rangeSort[] = array('store_id'=>$vo['store_id'],'juli'=>$vo['Srange']);
		}
		$res = sortArrayAsc($store_list,'Srange');

		$this->ajaxReturn(array('store_list'=>$res));
	}

	public function store_order(){
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		
		$data = array('store_id' => $store_id);
		if($_POST['isTicket']){
			if(!$this->config['store_ticket_have']){
				$this->error_tips('平台未开启票务插件');
			}
			$now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
			if(empty($now_store)){
				$this->error_tips('店铺不存在');
			}
			if(!$now_store['bind_store_trade'] == 'ticket'){
				$this->error_tips('店铺未开启票务插件');
			}
			$database_store_trade_ticket = D('Merchant_store_trade_ticket');
			$condition_store_trade_ticket = array('store_id'=>$now_store['store_id']);
			$now_store['store_trade_ticket'] = $database_store_trade_ticket->where($condition_store_trade_ticket)->find();
			$now_store['store_trade_ticket']['limit_num'] = $now_store['store_trade_ticket']['limit_num'] > 1 ? $now_store['store_trade_ticket']['limit_num'] : 1;
			
			if($_POST['pay_num'] < $now_store['store_trade_ticket']['limit_num']){
				$this->error_tips('购票数量小于店铺设定的最低数');
			}
			if($this->config['store_ticket_have_insure'] && $now_store['store_trade_ticket']['have_insure'] && $now_store['store_trade_ticket']['insure_mustby'] && !$_POST['choose_cinsure']){
				$this->error_tips('店铺设定必须要购买保险');
			}
			$data['ticketPrice'] = $_POST['ticketPrice'];
			$data['ticketNum'] = $_POST['pay_num'];
			//计算保险
			$data['ticketInsure'] = 0;
			if($_POST['choose_cinsure']){
				if($now_store['store_trade_ticket']['insure_tikcet_3'] != 0 && $data['ticketPrice'] >= $now_store['store_trade_ticket']['insure_tikcet_3']){
					$data['ticketInsure'] = $now_store['store_trade_ticket']['insure_3'];
				}else if($now_store['store_trade_ticket']['insure_tikcet_2'] != 0 && $data['ticketPrice'] >= $now_store['store_trade_ticket']['insure_tikcet_2']){
					$data['ticketInsure'] = $now_store['store_trade_ticket']['insure_2'];
				}else if($data['ticketPrice'] >= $now_store['store_trade_ticket']['insure_tikcet_1']){
					$data['ticketInsure'] = $now_store['store_trade_ticket']['insure_1'];
				}
			}
			
			$total_money = ($data['ticketPrice']+$data['ticketInsure'])*$data['ticketNum'];
			$data['total_price'] = $total_money;
			$no_discount_money = 0;

		}else{
			//普通优惠买单
			$total_money  = $tmp_total_money= isset($_POST['total_money']) ? (intval($_POST['total_money'] * 100) / 100) : 0;
			$data['total_price'] = $total_money;
			$no_discount_money = isset($_POST['no_discount_money']) ? (intval($_POST['no_discount_money'] * 100) / 100) : 0;

			$now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
			if(empty($now_store)){
				$this->error_tips('店铺不存在');
			}
			if ($total_money <= 0)  $this->returnCode('10100302');;
			$minus_price_true = $price_true = 0;

			$level_off=false;
			$user_= M('User')->where(array('uid'=>$this->user_session['uid']))->find();
			$this->user_session['level'] = $user_['level'];
			if(  !empty($this->user_level) && !empty($this->user_session) && isset($this->user_session['level'])){
				$leveloff=!empty($now_store['leveloff']) ? unserialize($now_store['leveloff']) :'';
				/****type:0无优惠 1百分比 2立减*******/
				if(!empty($leveloff) && isset($leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])){
					$level_off=$leveloff[$this->user_session['level']];
					if($level_off['type']==1){
						$level_off['offstr']='单价按原价'.$level_off['vv'].'%来结算';
					}elseif($level_off['type']==2){
						$level_off['offstr']='单价立减'.$level_off['vv'].'元';
					}
				}
			}

			$discount_compare = false ;


			$now_store['discount_txt'] = unserialize($now_store['discount_txt']);

			if (isset($now_store['discount_txt']['discount_type'])) {
				if ($now_store['discount_txt']['discount_type'] == 1) {
					if (isset($now_store['discount_txt']['discount_percent']) && $now_store['discount_txt']['discount_percent'] > 0) {
						$price_true = ($total_money - $no_discount_money) * $now_store['discount_txt']['discount_percent'] / 10 + $no_discount_money;
						$minus_price_true += $total_money - $price_true;

					}
				} elseif ($now_store['discount_txt']['discount_type'] == 2) {
					if (isset($now_store['discount_txt']['condition_price']) && $now_store['discount_txt']['condition_price'] > 0 && isset($now_store['discount_txt']['minus_price']) && $now_store['discount_txt']['minus_price']) {
						$minus_price_true += floor(($total_money - $no_discount_money) / $now_store['discount_txt']['condition_price']) * $now_store['discount_txt']['minus_price'];
						$price_true = $total_money - $minus_price_true;

					}
				}
			}
			
			if($now_store['vip_discount_type']>0){
				$level_discount_type = $level_off['type'];
				$discount_v =  $level_off['vv'];
				$after_subtract = 0;
				if($now_store['vip_discount_type']==1){
					if($level_discount_type==1){
						$after_subtract = floatval(($price_true - $no_discount_money)*(1-($discount_v/100)));
					}else if($level_discount_type==2){
						$after_subtract = $discount_v;
					}
					$data['vip_discount_money'] = $after_subtract;

					$discount_compare = true;

				}else if($now_store['vip_discount_type']==2 ){
					if($level_discount_type==1){
						$minus_price_true = floatval(($price_true - $no_discount_money)*(1-($discount_v/100)));
					}else if($level_discount_type==2){
						$minus_price_true = $discount_v;
					}
					$data['vip_discount_money'] = $minus_price_true;


					$tmp_total_money = $price_true;
					$price_true = $price_true-$minus_price_true<0?0:$price_true-$minus_price_true;
					$total_money = $tmp_total_money;

				}

			}

		}

		if ($minus_price_true == 0 && $price_true == 0) {
			$minus_price_true = 0;
			$price_true = $total_money;
		}

		if($discount_compare){
			if( $after_subtract>$minus_price_true){
				$minus_price_true = $after_subtract;
				if($total_money-$minus_price_true<0){
					$minus_price_true = $total_money;
				}
				$price_true = $total_money-$minus_price_true;
			}else{
				$data['vip_discount_money'] = 0;
			}
		}

		$data['mer_id'] = $now_store['mer_id'];
		$data['uid'] = $this->user_session['uid'];
		$data['orderid'] = date("YmdHis") . mt_rand(10000000, 99999999);
		$data['name'] = '顾客现场自助支付-' . $now_store['name'];

		$data['discount_price'] = $minus_price_true;
		$data['price'] = $price_true;
		$data['no_discount_money'] = $no_discount_money;
		$data['dateline'] = time();
		$data['from_plat'] = 1;
		

		// die;
		$order_id = D("Store_order")->add($data);
		if ($order_id) {
			//$this->returnCode(0,array('order_type'=>'store','order_id'=>$order_id));
			//redirect(U('Pay/check',array('type'=>'store','order_id'=>$order_id)));
			return $order_id;
		} else {
			//$this->returnCode('20046014');
			return false;
		}
	}

	public function store_order_before(){
		$order_id  = $_GET['order_id'];
		$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();

		if(empty($now_order)){
			$this->error('当前订单不存在');
		}else if($now_order['paid'] == '1'){
			$this->error('该订单已经付款');
		}else if($now_order['uid'] != $this->user_session['uid']){
			if(M("Store_order")->where(array('order_id'=>$order_id))->data(array('uid'=>$this->user_session['uid']))->save()){
				redirect(U('Pay/check',array('type'=>'store','order_id'=>$order_id)));
			}else{
				$this->error('数据保存失败');
			}
		}else{
			redirect(U('Pay/check',array('type'=>'store','order_id'=>$order_id)));
		}
	}
	public function shop_order_before(){
		$order_id  = $_GET['order_id'];
		$now_order = M("Shop_order")->where(array('order_id'=>$order_id))->find();

		if(empty($now_order)){
			$this->error('当前订单不存在');
		}else if($now_order['paid'] == '1'){
			$this->error('该订单已经付款');
		}else if($now_order['uid'] != $this->user_session['uid']){
			if(M("Shop_order")->where(array('order_id'=>$order_id))->data(array('uid'=>$this->user_session['uid'], 'username' => $this->user_session['nickname'], 'userphone' => $this->user_session['phone']))->save()){
				redirect(U('Pay/check',array('type'=>'shop','order_id'=>$order_id)));
			}else{
				$this->error('数据保存失败');
			}
		}else{
			redirect(U('Pay/check',array('type'=>'shop','order_id'=>$order_id)));
		}
	}

	public function house_order_before(){
		$order_id  = $_GET['order_id'] + 0;
		if(!$order_id){
			$this->error('传递参数有误！');
		}

		$now_order = M("House_village_pay_order")->where(array('order_id'=>$order_id))->find();
		$recharge_where['label'] = 'wap_village_'.$order_id;
		$recharge_order_info = M('User_recharge_order')->where($recharge_where)->find();
		$recharge_order_id = $recharge_order_info['order_id'];
//		if($this->now_user['now_money']>$now_order['money']){
			redirect( U('House/pay_order',array('order_id'=>$order_id)));die;
//		}
		if(empty($now_order)){
			$this->error('当前订单不存在');
		}else if($now_order['paid'] == '1'){
			$this->error('该订单已经付款');
		}else if($now_order['uid'] != $this->user_session['uid']){

			if(M("House_village_pay_order")->where(array('order_id'=>$order_id))->data(array('uid'=>$this->user_session['uid']))->save()){
				if(!$recharge_order_info){
					$recharge_data['uid'] = $this->user_session['uid'];
					$recharge_data['add_time'] = time();
					$recharge_data['money'] = $now_order['money'];
					$recharge_data['is_mobile_pay'] = 1;
					$recharge_data['label'] = 'wap_village_'.$order_id;
					$recharge_order_id = M('User_recharge_order')->data($recharge_data)->add();
				}
				redirect(U('Pay/check',array('type'=>'recharge','order_id'=>$recharge_order_id)));
			}else{
				$this->error('数据保存失败');
			}
		}else{
			if(!$recharge_order_info){
				$recharge_data['uid'] = $this->user_session['uid'];
				$recharge_data['add_time'] = time();
				$recharge_data['money'] = $now_order['money'];
				$recharge_data['is_mobile_pay'] = 1;
				$recharge_data['label'] = 'wap_village_'.$order_id;
				$recharge_order_id = M('User_recharge_order')->data($recharge_data)->add();
			}
			redirect(U('Pay/check',array('type'=>'recharge','order_id'=>$recharge_order_id)));
		}
	}

	// 社区 收银台缴费
	public function house_cashier_order_before(){
		$order_id  = $_GET['order_id'] + 0;
		if(!$order_id){
			$this->error('传递参数有误！');
		}
		redirect( U('House/pay_order_cashier',array('order_id'=>$order_id)));die;
	
	}

	/*全部订餐订单列表*/
	public function store_order_list()
	{
		$where = "uid={$this->user_session['uid']} AND paid=1";
		$order_list = D("Store_order")->field(true)->where($where)->order('order_id DESC')->select();
		$temp = $store_ids = array();
		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
//			$ol['price'] = floatval($ol['balance_pay']+$ol['payment_money']+$ol['merchant_balance']+$ol['card_give_money']) ;
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}
		$this->assign('order_list', $list);
		$this->display();
	}


	public function cardcode(){
		if(!empty($this->now_user['cardid'])){
			$_SESSION['tmp_cardid'] = substr($this->now_user['cardid'],0,1).substr($this->now_user['cardid'],-1).substr(uniqid('', true), 17).substr(microtime(), 2, 6);
			//$tmp[1] = substr($_SESSION['tmp_cardid'],0,4);
			//$tmp[2] = substr($_SESSION['tmp_cardid'],4,4);
			//$tmp[3] = substr($_SESSION['tmp_cardid'],8,6);
			//$_SESSION['tmp_cardid'] = implode('-',$tmp);
			D('Physical_card')->where(array('cardid'=>$this->now_user['cardid']))->setField('t_id',$_SESSION['tmp_cardid']);
			$this->assign('cardid',$this->now_user['cardid']);
			$this->display();
		}else{
			$this->error_tips('您没有绑定实体卡！请联系商家！');
		}

	}

	public function cardbarcode(){
		import('@.ORG.barcode');
		$colorFront = new BCGColor(0, 0, 0);
		$colorBack = new BCGColor(255, 255, 255);

		$font = new BCGFontFile($_SERVER['DOCUMENT_ROOT'].'/cms/Lib/ORG/barcode/font/Arial.ttf', 18);
		// Barcode Part
		$code = new BCGcode128();
		$code->setScale(2);
		$code->setColor($colorFront, $colorBack);
		$code->setFont($font);
		if($_GET['type']=='pay'){
			$this->pay_qrcode();
			$code->parse($_SESSION['tmp_payid']);
		}else{
			$code->parse($_SESSION['tmp_cardid']);
		}

		// Drawing Part
		$drawing = new BCGDrawing('', $colorBack);
		$drawing->setBarcode($code);
		$drawing->draw();

		header('Content-Type: image/png');
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
	}

	public function cardqrcode(){
		import('@.ORG.phpqrcode');
		if($_GET['type']=='pay'){
			//$this->pay_qrcode();
			QRcode::png($_SESSION['tmp_payid'],false,2,8,2);
		}else{
			QRcode::png($_SESSION['tmp_cardid'],false,2,8,2);
		}
	}


	private function meal_after_refund($now_order)
	{
		$msg = '';
		//如果使用了优惠券
		if($now_order['card_id']){
			$result = D('Member_card_coupon')->add_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				return array('error' => 1, 'msg' => $result['msg']);
				$this->error_tips($result['msg']);
			}
			$msg = $result['msg'];
		}


		//如果使用了积分 2016-1-15
		if ($now_order['score_used_count']!=='0') {
			$order_info=unserialize($now_order['info']);
			$order_name=$order_info[0]['name']."*".$order_info[0]['num'];
			$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],'退款 '.$order_name.$this->config['score_name'].'回滚');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Group_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				return array('error' => 1, 'msg' => $result['msg']);
				$this->error_tips($result['msg']);
			}
			$msg .= $result['msg'];
		}

		//平台余额退款
		if($now_order['balance_pay'] != '0.00'){
			$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$now_order['order_name'].' 增加余额');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				return array('error' => 1, 'msg' => $result['msg']);
				$this->error_tips($result['msg']);
			}
			$msg .= '平台余额退款成功';
			// 			if($add_result['error_code']){
			// 				$this->error_tips($add_result['msg']);
			// 			}
			// 			$go_refund_param['msg'] = $add_result['msg'];

			// 			$data_meal_order['order_id'] = $now_order['order_id'];
			// 			$data_meal_order['refund_detail'] = serialize(array('refund_time'=>time()));
			// 			$data_meal_order['status'] = 3;
			// 			D('Meal_order')->data($data_meal_order)->save();
		}
		//商家会员卡余额退款
		if($now_order['merchant_balance'] != '0.00'){
			$result = D('Member_card')->add_card($now_order['uid'],$now_order['mer_id'],$now_order['merchant_balance'],'退款 '.$now_order['order_name'].' 增加余额');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				return array('error' => 1, 'msg' => $result['msg']);
				$this->error_tips($result['msg']);
			}
			$msg .= $result['msg'];
		}
		if(empty($now_order['pay_type'])){
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			$msg .= '取消订单成功';
		}

		//退款时销量回滚
		if ($now_order['paid'] == 1 && date('m', $now_order['dateline']) == date('m')) {
			foreach (unserialize($now_order['info']) as $menu) {
				D('Meal')->where(array('meal_id' => $menu['id'], 'sell_count' => array('gt', $menu['num'])))->setDec('sell_count', $menu['num']);
			}
		}
		D("Merchant_store_meal")->where(array('store_id' => $now_order['store_id'], 'sale_count' => array('gt', 0)))->setDec('sale_count', 1);

		//退款打印
		
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($now_order['order_id'], 'meal_order', 3);

		$mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $store_id))->find();
		$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'food');
		if ($this->config['sms_cancel_order'] == 1 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['phone'] ? $now_order['phone'] : $my_user['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $orderid . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_cancel_order'] == 2 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $mer_store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客' . $now_order['name'] . '的预定订单(订单号：' . $orderid . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
			Sms::sendSms($sms_data);
		}
		return array('error' => 0, 'msg' => $msg);
	}

	public function return_refund()
	{
		echo "<html><head><meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\"></head><body><h1>这是一个return_url页面</h1></body></html>";
		die;
		$pay_class_name = ucfirst($_GET['pay_type']);
		$import_result = import('@.ORG.pay.'.$pay_class_name);
		if(empty($import_result)){
			$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
		}
		$pay_class = new $pay_class_name('', '', $_GET['pay_type'], $pay_method[$_GET['pay_type']]['config'], '', 1);
		$go_refund_param = $pay_class->return_refund();
		if ($go_refund_param['error']) {
			$this->error_tips($go_refund_param['msg']);
		}
		$data = $go_refund_param['order_param'];
		if ($data['order_type'] == 'group') {

		} else {
			$now_order = M("Meal_order")->where(array('orderid' => $data['order_id']))->find();
		}
		$refund_param['refund_id'] = $data['sp_refund_no'];
		$refund_param['ret_code'] = $data['ret_code'];
		$refund_param['ret_detail'] = $data['ret_detail'];
		$refund_param['refund_time'] = $data['refund_time'];

		$data_meal_order['order_id'] = $now_order['order_id'];
		$data_meal_order['refund_detail'] = serialize($refund_param);
		if (empty($go_refund_param['error']) && $data['ret_code'] == 1) {
			echo "<html><head><meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\"></head><body><h1>这是一个return_url页面</h1></body></html>";
			$data_meal_order['status'] = 3;
		}
		D('Meal_order')->data($data_meal_order)->save();
		if($data_meal_order['status'] != 3){
			$this->error_tips($go_refund_param['msg']);
		}
		$result = $this->meal_after_refund($now_order);
		if ($result['error']) {
			$this->error_tips($result['msg']);
		}
		if ($now_order['meal_type'] == 1) {
			$this->success_tips($result['msg'], U('Takeout/order_detail', array('order_id' => $now_order['order_id'], 'store_id' => $now_order['store_id'], 'mer_id' => $now_order['mer_id'])));
		} else {
			$this->success_tips($result['msg'], U('Food/order_detail', array('order_id' => $now_order['order_id'], 'store_id' => $now_order['store_id'], 'mer_id' => $now_order['mer_id'])));
		}
	}

	//微信蓝牙
	public function wxblue(){
		C('open_authorize_wxpay',true);
		$share = new WechatShare($this->config,$_SESSION['openid']);
		$this->hideScript = $share->gethideOptionMenu(1);
		$this->assign('hideScript', $this->hideScript);
		$this->display();
	}

	//推广二维码
	public function my_spread_qrcode(){
		if(empty($this->now_user['openid'])){
			$this->error_tips("你没有绑定微信，无法生成推广二维码");
		}else{
			$this->assign('uid',$this->now_user['uid']);
			$spread = M('User_spread_qrcode');
			$spread_info = $spread->where(array('uid'=>$this->now_user['uid']))->find();
			$now_time = time();

			if(!empty($spread_info)){
				if($spread_info['qrcode_type']==0&&$now_time>strtotime(date('Y-m-d',$spread_info['last_time']))+86400){
					$this->get_spread_qrcode($spread_info['url'],$this->now_user['uid']);
					$spread_info = $spread->where(array('uid'=>$this->now_user['uid']))->find();
				}
				$effective_date = date('m月d号',$now_time+2592000);
				$spread= array("error_code"=>false,'id'=>$spread_info['qrcode_id'],'url'=>$spread_info['url'],'ticket'=>$spread_info['ticket'],'effective_date'=>$effective_date);
				$this->assign('spread_info',$spread);
			}
			$this->display();
		}
	}

	//@param 推广海报
	public function my_spread_hb(){
		$where['uid'] = $this->user_session['uid'];
		$spread_info = M('User_spread_qrcode')->where($where)->find();
		$res = D('Store_promote_setting')->where(array('status'=>1,'type'=>1))->select();
		$key = array_rand($res,1);
		$promote = $res[$key];
		$promote['qrcode'] = $spread_info['ticket'];
		$image_url = D('Store_promote_setting')->createImage($promote,  $spread_info['ticket'], $this->user_session, '');
		$this->assign('image',$image_url);
		$this->display();
	}

	public function my_spread(){
		$this->display();
	}
	public function get_spread_qrcode($spread_url='',$uid=0,$is_func=false){
		$_POST['url'] = empty($spread_url)?$_POST['url']:$spread_url;
		$_POST['uid'] = empty($uid)?$_POST['uid']:$this->now_user['uid'];
		$url = $_POST['url'];

		$url_info = parse_url($url);
		$n = preg_match('/(.*\.)?(\w+\.\w+)$/',$url_info['host'], $matches);

		if(!strpos($_POST['url'], $matches[2])&&empty($spread_url)){
			echo json_encode(array('error_code'=>1,'msg'=>'您输入的域名有误！'));exit;
		}
		$spread = M('User_spread_qrcode');
		$qrcode_id =900000000+$_POST['uid'];
		if($this->config['user_spread_qrcode_tmp']){
			$date['qrcode_type']=0;
			$res = D('Recognition')->get_tmp_qrcode($qrcode_id);
		}else{
			$date['qrcode_type']=1;
			$res = D('Recognition')->get_new_qrcode('spread',$qrcode_id);
			$res['ticket']=$res['qrcode'];
		}
		if($is_func){
			return $res['ticket'];
		}
		$date['qrcode_id']=$qrcode_id;
		if(strpos($url,'://')){
			$date['url']=$url;
		}else{
			$date['url']='http://'.$url;
		}
		if(!strpos($url,'openid')) {
			if (strpos($url, '?')) {
				$date['url'] .= "&openid=" . $this->now_user['openid'];
			} else {
				$date['url'] .= "?openid=" . $this->now_user['openid'];
			}
		}
		$date['url'] = html_entity_decode($date['url']);
		$date['ticket']=$res['ticket'];
		$date['last_time']=time();
		$where['uid']=$_POST['uid'];
		if($result=$spread->where($where)->find()){
			$spread->where($where)->save($date);
		}else{
			$date['create_time']=time();
			$date['uid']=$_POST['uid'];
			$id = $spread->data($date)->add();
		}
		if(!empty($result)){
			$id = $result['id'];
		}
		$res['qrcode_type']=$date['qrcode_type'];
		if(empty($spread_url)){
			echo json_encode(array('error_code'=>0,'msg'=>$res,'id'=>$id));exit;
		}
	}

	//佣金过户
	public function my_spread_change(){
		if(IS_POST){
			if($_POST['change_user']==$this->now_user['phone']){
				$this->AjaxReturn(array('error_code'=>1,'msg'=>'过户用户不能是自己'));
			}
			$bind_user = D('User')->get_user( $_POST['change_user'],'phone');
			if(empty($bind_user)){
				$this->AjaxReturn(array('error_code'=>1,'msg'=>'用户不存在'));
			}else{
				M('User')->where(array('uid'=>$this->now_user['uid']))->setField('spread_change_uid',$bind_user['uid']);
				$this->AjaxReturn(array('error_code'=>0,'msg'=>'绑定成功'));
			}
		}else{
			if($this->now_user['spread_change_uid']>0){
				$this->assign('change_user',D('User')->get_user($this->now_user['spread_change_uid']));
			}
			$this->display();
		}
	}

	//ajax 获取用户列表
	public function ajax_search_user(){
		$key = $_POST['key'];
		$value = $_POST['value'];
		$res = M('User')->field('nickname,phone')->where(array($key=>array('like','%'.$value.'%')))->select();
		if(empty($res)){
			$this->AjaxReturn(array('error_code'=>1,'msg'=>'没有相关用户'));
		}else{
			$this->AjaxReturn(array('error_code'=>0,'msg'=>$res));
		}
	}

	//	我的实名认证
	public function authentication(){
		$this->user_sessions();
		$uid	=	$this->user_session['uid'];
		$find	=	M('User_authentication')->field(true)->where(array('uid'=>$uid))->find();
		$this->assign('find',$find);
		$this->display();
	}
	//	我的实名认证提交
	public function authentication_json(){
		$where['uid']	=	$this->user_session['uid'];
		$find	=	M('User_authentication')->where($where)->find();
		$auth_data	=	array(
			'user_truename'			=>	$_POST['user_truename'],
			'user_id_number'		=>	$_POST['user_id_number'],
			'authentication_img'	=>	$_POST['authentication_img'],
			'authentication_back_img'=>	$_POST['authentication_back_img'],
			'hand_authentication'	=>	$_POST['hand_authentication'],
			'authentication_time'	=>	$_SERVER['REQUEST_TIME'],
			'examine_time'			=>	0,
			'authentication_status'	=>	0,
		);
		if($find){
			$user_authentication	=	M('User_authentication')->where($where)->data($auth_data)->save();
		}else{
			$auth_data['uid']	=	$this->user_session['uid'];
			$user_authentication	=	M('User_authentication')->data($auth_data)->add();
		}
		if(empty($user_authentication)){
			$this->returnCode('40000031');
		}else{
			$data['truename']	=	$_POST['user_truename'];
			$data['real_name']	=	2;
			$save	=	D('User')->scenic_save_user($where,$data);
			if($save){
				$_SESSION['user']['real_name']	=	2;
				$_SESSION['user']['truename']	=	$data['truename'];
			}
		}

		if(define(IS_HOUSE) == true){
			$url	=	U('village_my',array('village_id'=>$_POST['village_id']));
		}else{
			$url	=	$this->config['site_url'].U('myinfo');
		}
		$this->returnCode(0,$url);
	}
	//	我的实名认证展示
	public function authentication_index(){
		$this->user_sessions();
		$authentication	=	D('User_authentication')->field(true)->order('authentication_time DESC')->where(array('uid'=>$this->user_session['uid']))->find();
		if($authentication){
			$store_image_class = new scenic_image();
			$a_img = strstr($authentication['authentication_img'], ',',true);
			$b_img = strstr($authentication['authentication_back_img'], ',',true);
			if($a_img){
				$authentication['authentication_img'] = $store_image_class->get_image_by_path($authentication['authentication_img'],$this->config['site_url'],'aguide','1');
			}
			if($b_img){
				$authentication['authentication_back_img'] = $store_image_class->get_image_by_path($authentication['authentication_back_img'],$this->config['site_url'],'aguide','1');
			}
		}else{
			redirect(U('authentication'));
		}
		$this->assign('authentication',$authentication);
		$this->display();
	}
	/*     * *图片上传** */
	public function authenticationUpload() {
		$mulu=isset($_GET['ml']) ? trim($_GET['ml']):'group';
		$mulu=!empty($mulu) ? $mulu : 'group';
		$filename = trim($_POST['filename']);
		$img = $_POST[$filename];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$imgdata = base64_decode($img);
//		$img_order_id = sprintf("%09d",$this->user_session['uid']);
//		$rand_num = mt_rand(10,99).'/'.substr($img_order_id,0,3).'/'.substr($img_order_id,3,3).'/'.substr($img_order_id,6,3);
		$getupload_dir = "/upload/".$mulu."/" .$this->user_session['uid'];

		$upload_dir = "." . $getupload_dir;
		if (!is_dir($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}
		$newfilename = $mulu.'_' . date('YmdHis') . '.jpg';
		$save = file_put_contents($upload_dir . '/' . $newfilename, $imgdata);
		if ($save) {
			$this->dexit(array('error' => 0, 'data' => array('code' => 1, 'siteurl'=>$this->config['site_url'],'imgurl' =>$getupload_dir . '/' . $newfilename, 'msg' => '')));
		} else {
			$this->dexit(array('error' => 1, 'data' => array('code' => 0, 'url' => '', 'msg' => '保存失败！')));
		}
	}

	public function is_group_share(){
		if(!M('Group_order')->where(array('order_id'=>$_POST['order_id']))->save(array('is_share_group'=>1))){
			$this->error('更新组团购分享失败！');
		}else{
			$date['fid']=$_POST['order_id'];
			$date['uid']=$_POST['uid'];
			$date['order_id']=$_POST['order_id'];
			$result = M('Group_share_relation')->where($date)->find();
			if($result){
				$this->success('已经生成团购分组，不能再生成了！');
			}
			M('Group_share_relation')->add($date);
			$this->success('更新组团购分享成功！');
		}
	}

	public function ajax_group_share_num(){
		$uid = $_POST['uid'];
		$order_id = $_POST['order_id'];
		if(!$order_id||!uid){
			exit(json_encode(array('error_code'=>1,'msg'=>'传递参数有误！')));
		}
		$num = D('Group_share_relation')->get_share_num($uid,$order_id);

		exit(json_encode(array('error_code'=>0,'num'=>(int)$num)));

	}

	public function ajax_now_pin_num(){
		$uid = $_POST['uid'];
		$order_id = $_POST['order_id'];
		if(!$order_id||!uid){
			exit(json_encode(array('error_code'=>1,'msg'=>'传递参数有误！')));
		}
		$num = D('Group_start')->get_group_start_by_order_id($order_id);

		exit(json_encode(array('error_code'=>0,'num'=>$num)));

	}


	public  function  ajax_group_user(){
		$uid = $_POST['uid'];
		$order_id = $_POST['order_id'];
		$uids = explode(',',substr($_POST['uids'],0,-1));

		if(!$order_id||!uid){
			exit(json_encode(array('error_code'=>1,'msg'=>'传递参数有误！')));
		}
		if(empty($_POST['type'])){

			$res['user_arr'] = D('Group_share_relation')->get_share_user($uid,$order_id);
		}else{
			$res['user_arr'] =  D('Group_start')->get_buyerer_by_order_id($order_id);
		}
		$group_start =  D('Group_start')->get_group_start_by_order_id($order_id);
		foreach($res['user_arr'] as &$v){
			if(empty($v['pay_time'])){
				$v['pay_time'] = $group_start['last_time'];
			}
			$v['pay_time']  = date('Y-m-d H:i:s',$v['pay_time']);

			if(in_array($v['uid'],$uids)){
				$res['in'][] = $v['uid'];
			}
		}
		foreach($uids as $vv){
			if(!in_array($vv,$res['in'])){
				$res['not_in'][] = $vv;
			}
		}
		exit(json_encode(array('error_code'=>0,'res'=>$res)));
	}

	public function change_is_share(){
		if(!M('Group_order')->where(array('order_id'=>$_POST['order_id']))->save(array('is_share_group'=>2))){
			exit(json_encode(array('status'=>0,'msg'=>'取消失败！')));
		}
	}
	public function ajax_wap_user_del(){
		if(IS_AJAX){
			$order_id = $_POST['order_id'] + 0;
			if(empty($order_id)){
				exit(json_encode(array('msg' => '传递参数有误！','status' => 0)));
			}

			$database_appoint_order = D('Appoint_order');
			$where['order_id'] = $order_id;
			$data['is_del'] = 5;
			$data['del_time']= time();
			$result = $database_appoint_order->where($where)->data($data)->save();
			if(!empty($result)){
				exit(json_encode(array('status'=>1,'msg'=>'取消成功！')));
			}else{
				exit(json_encode(array('status'=>0,'msg'=>'取消失败！')));
			}
		}else{
			$this->error_tips('访问页面错误！~~~');
		}
	}



	public function ajax_wap_appoint_del(){
		if(IS_AJAX){
			$order_id = $_POST['order_id'] + 0;
			if(empty($order_id)){
				exit(json_encode(array('msg' => '传递参数有误！','status' => 0)));
			}

			if($this->config['appoint_rule']){
				$database_appoint_order = D('Appoint_order');
				$now_order = $database_appoint_order->get_order_by_id($this->user_session['uid'] , $order_id);
				$appoint_service_time = strtotime($now_order['appoint_date']  . ' ' .  $now_order['appoint_time']);
				$appoint_before_cancel_time = $this->config['appoint_before_cancel_time'] * 60;
				if($now_order['paid']==3){
					exit(json_encode(array('msg' => '已经退款了，不能再退了！','status' => 1)));
				}
					if( (time() > ($appoint_service_time - $appoint_before_cancel_time)) && ($now_order['payment_status'] > 0)){
						if($now_order['payment_status'] > 0){
						$where['order_id'] = $order_id;
						$fields['paid'] = 3;
						$fields['del_time'] = time();
						if($database_appoint_order->where($where)->data($fields)->save()){
							$now_order['order_type']='appoint';
							$now_appoint = M('Appoint')->field('appoint_name')->where(array('appoint_id'=>$now_order['appoint_id']))->find();

							if($now_order['product_payment_price']){
								$tmp_price = $now_order['product_payment_price'];
							}else{
								$tmp_price = $now_order['payment_price'];
							}


							$order_info['order_id'] = $now_order['order_id'];
							$order_info['store_id'] = $now_order['store_id'];
							$order_info['mer_id'] = $now_order['mer_id'];
							$order_info['order_type'] = 'appoint';
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

							//$result = D('Merchant_money_list')->add_money($now_order['mer_id'] , '用户预约 '.$now_appoint['appoint_name'].'*1 取消预约，定金' . $order_info['money'] . '记入收入',$order_info);
							$order_info['desc'] = '用户预约 '.$now_appoint['appoint_name'].'*1 取消预约，定金' . $order_info['money'] . '记入收入';
							 $order_info['score_discount_type']=$now_order['score_discount_type'];
							$result =D('SystemBill')->bill_method($order_info['is_own'],$order_info);
							if(!$result['error_code']){
								exit(json_encode(array('status' => 1,'msg' => '取消成功！')));
							}else{
								exit(json_encode(array('status' => 0,'msg' => '取消失败！')));
							}
						}
					}
				}else{
					$order_id = $_POST['order_id'] + 0;
					if(empty($order_id)){
						exit(json_encode(array('msg' => '传递参数有误！','status' => 0)));
					}

					$database_appoint_order = D('Appoint_order');
					$where['order_id'] = $order_id;
					$data['is_del'] = 5;
					$data['del_time']= time();
					$result = $database_appoint_order->where($where)->data($data)->save();
					if(!empty($result)){
						exit(json_encode(array('status'=>1,'msg'=>'取消成功！')));
					}else{
						exit(json_encode(array('status'=>0,'msg'=>'取消失败！')));
					}
				}
			}
		}else{
			$this->error_tips('访问页面错误！~~~');
		}
	}


	public function ajax_wap_appoint_pay_balance(){
		if(IS_POST){
			$order_id = $_POST['order_id'] + 0;
			if(empty($order_id)){
				exit(json_encode(array('msg' => '传递参数有误！','status' => 0)));
			}

			if($this->config['appoint_rule']){
				$database_appoint_order = D('Appoint_order');
				$now_order = $database_appoint_order->get_order_by_id($this->user_session['uid'],$order_id);

				if(empty($now_order)){
					exit(json_encode(array('msg' => '该订单不存在！','status' => 0)));
				}

				//$now_user = D('User')->get_user($now_order['uid']);
				$now_pay_money = $now_order['product_price'] - $now_order['product_payment_price'];

				if(!$now_pay_money){
					$now_pay_money = $now_order['appoint_price'] - $now_order['payment_money'];
				}

				if($now_pay_money <= 0){
					exit(json_encode(array('status' => 0,'msg' => '数据处理有误！')));
				}

				$href = U('Pay/check', array('order_id' => $order_id, 'type' => 'balance-appoint'));
				exit(json_encode(array('url' => $href,'status' => 1,'msg'=>'余额不足')));
			}
		}else{
			$this->error_tips('访问页面错误！~~~');
		}
	}

	public function shop_order_refund()
	{
		if (empty($this->user_session)) {
			$this->error_tips('请先进行登录！');
		}

		$order_id = intval($_GET['order_id']);
		$now_order = D("Shop_order")->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']));
		if (empty($now_order)) {
			$this->error_tips('当前订单不存在');
		}
		if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
			$this->error_tips('当前订单店员正在处理中，不能退款或取消');
		}
		if (empty($now_order['paid'])) {
			$this->error_tips('当前订单还未付款！');
		}

		if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
			$this->error_tips('订单必须是未消费状态才能取消！', U('Shop/status',array('order_id' => $now_order['order_id'])));
		} elseif ($now_order['status'] > 3 && !($now_order['paid'] == 1 && $now_order['status'] == 5)) {
			$this->redirect(U('Shop/status',array('order_id' => $now_order['order_id'])));
		}
		$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'], $now_order['is_mobile_pay']);
		$this->assign('now_order', $now_order);
		$this->display();
	}

	//取消订单
	public function shop_order_check_refund()
	{
		if (empty($this->user_session)) {
			$this->error_tips('请先进行登录！');
		}
		$order_id = intval($_GET['order_id']);
		$now_order = D("Shop_order")->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']));

		if($this->config['open_allinyun']==1){
			import('@.ORG.AccountDeposit.AccountDeposit');
			$deposit = new AccountDeposit('Allinyun');
			$allyun = $deposit->getDeposit();

			$allinyun_user = D('Deposit')->allinyun_user($this->user_session['uid']);

			if($allinyun_user['bizUserId']!=''){
				$allyun->setUser($allinyun_user);
				$params['bizOrderNo'] = 'shop_'.$now_order['orderid'];
				$params['oriBizOrderNo'] = $now_order['third_id'];
				$params['amount'] = intval($now_order['payment_money']*100);
				$res = $allyun->refundApply($params);

				if($res['status']=='OK'){
					$data_shop_order['order_id'] = $now_order['order_id'];
					$data_shop_order['status'] = 4;
					$data_shop_order['last_time'] = time();
					$data_shop_order['refund_detail'] = serialize($res);
					$return = $this->shop_refund_detail($now_order, $now_order['store_id']);
					if ($return['error_code']) {
						$this->error_tips($return['msg']);
					}
					D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));
					D('Shop_order')->data($data_shop_order)->save();
					$this->success_tips('退款成功',U('Shop/status',array('order_id' => $order_id, 'store_id' => $now_order['store_id'], 'mer_id' => $now_order['mer_id'])));
				}else{
					$this->error_tips('退款失败,'.$res['message']);
				}
			}
		}
		if(empty($now_order)){
			$this->error_tips('当前订单不存在');
		}
		$store_id = $now_order['store_id'];
		$this->mer_id = $now_order['mer_id'];
		if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
			$this->error_tips('当前订单店员正在处理中，不能退款或取消');
		}
		if (empty($now_order['paid'])) {
			$this->error_tips('当前订单还未付款！');
		}
		if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
			$this->error_tips('订单必须是未消费状态才能取消！', U('Shop/status',array('order_id' => $now_order['order_id'])));
		} elseif ($now_order['status'] > 3 && !($now_order['paid'] == 1 && $now_order['status'] == 5)) {
			$this->redirect(U('Shop/status',array('order_id' => $now_order['order_id'])));
		}
		$mer_store = D('Merchant_store')->where(array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id']))->find();
		$my_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();

		//线下支付退款
		$data_shop_order['cancel_type'] = 5;//取消类型（0:pc店员，1:wap店员，2:andriod店员,3:ios店员，4：打包app店员，5：用户，6：配送员, 7:超时取消）
		if ($now_order['pay_type'] == 'offline') {
			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize(array('refund_time' => time()));
			$data_shop_order['status'] = 4;
			if (D('Shop_order')->data($data_shop_order)->save()) {
				$return = $this->shop_refund_detail($now_order, $store_id);
				if ($return['error_code']) {
					$this->error_tips($result['msg']);
				} else {
					$this->success_tips('您使用的是线下支付！订单状态已修改为已退款。',U('Shop/status',array('order_id' => $now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
				}
			} else {
				$this->error_tips('取消订单失败！请重试。');
			}
		} else {
			if ($now_order['payment_money'] != '0.00') {
				if($this->config['open_juhepay']==1 &&( $now_order['pay_type']=='weixin' || $now_order['pay_type']=='alipay')){
					import('@.ORG.LowFeePay');
					$lowfeepay = new LowFeePay('juhepay');
					$now_order['orderNo'] = 'shop_'.$now_order['orderid'];
					if($now_order['mer_id']){
	                    $mer_juhe = M('Merchant_juhe_config')->where(array('mer_id'=>$now_order['mer_id']))->find();
						if(!empty($mer_juhe)){ 
							$lowfeepay->userId =$mer_juhe['userid'];
							$now_order['is_own'] =1 ;
						    $now_order['orderNo'] =  $now_order['orderNo'].'_1';
						}
						
	                }
					$go_refund_param= $lowfeepay->refund($now_order);

				}else {
					if ($now_order['is_own']) {
						$pay_method = array();
						$merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $now_order['mer_id']))->find();
						foreach ($merchant_ownpay as $ownKey => $ownValue) {
							$ownValueArr = unserialize($ownValue);
							if ($ownValueArr['open']) {
								$ownValueArr['is_own'] = true;
								$pay_method[$ownKey] = array('name' => $this->getPayName($ownKey), 'config' => $ownValueArr);
							}
						}
						$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
						if ($now_merchant['sub_mch_refund'] && $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
							$pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
							$pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
							$pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
							$pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
							$pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
							$pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_sp_client_cert'];
							$pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
							$pay_method['weixin']['config']['is_own'] = 2;
						}
					} else {
						$is_app = false;
						if (stripos($_SERVER['HTTP_USER_AGENT'], 'Android') || stripos($_SERVER['HTTP_USER_AGENT'], 'IOS')) $is_app = true;
						if ($now_order['pay_type'] == 'alipay') $is_app = true;
						$pay_method = D('Config')->get_pay_method(0, 0, 1, $is_app);
						if ($now_order['is_mobile_pay'] == 2) {
							$pay_method['weixin'] = $pay_method['weixinapp'];
						}
					}

					if (empty($pay_method)) {
						$this->error_tips('系统管理员没开启任一一种支付方式！');
					}

					$pay_class_name = ucfirst($now_order['pay_type']);
					if ($pay_class_name == 'Alipay' && $now_order['is_mobile_pay'] == 2 && $this->config['new_pay_alipay_app_public_key'] != '' && $this->config['new_pay_alipay_app_appid'] != '' && $this->config['new_pay_alipay_app_private_key'] != '') {
						$pay_class_name = 'AlipayApp';
						$pay_method['alipay']['config']['new_pay_alipay_app_appid'] = $this->config['new_pay_alipay_app_appid'];
						$pay_method['alipay']['config']['new_pay_alipay_app_private_key'] = $this->config['new_pay_alipay_app_private_key'];
						$pay_method['alipay']['config']['new_pay_alipay_app_public_key'] = $this->config['new_pay_alipay_app_public_key'];
					}
					if (empty($pay_method[$now_order['pay_type']])) {
						$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
					}

					$import_result = import('@.ORG.pay.' . $pay_class_name);
					if (empty($import_result)) {
						$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
					}
					D('Shop_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_refund' => 1));
					$now_order['order_type'] = 'shop';
					$now_order['order_id'] = $now_order['orderid'];
					if ($now_order['is_mobile_pay'] == 3) {
						$pay_method[$now_order['pay_type']]['config'] = array(
								'pay_weixin_appid' => $this->config['pay_wxapp_appid'],
								'pay_weixin_key' => $this->config['pay_wxapp_key'],
								'pay_weixin_mchid' => $this->config['pay_wxapp_mchid'],
								'pay_weixin_appsecret' => $this->config['pay_wxapp_appsecret'],
						);
						C('config.pay_weixin_client_cert', $this->config['pay_wxapp_cert']);
						C('config.pay_weixin_client_key', $this->config['pay_wxapp_cert_key']);
					}

					$pay_class = new $pay_class_name($now_order, $now_order['payment_money'], $now_order['pay_type'], $pay_method[$now_order['pay_type']]['config'], $this->user_session, 1);
					$go_refund_param = $pay_class->refund();
				}
				$now_order['order_id'] = $order_id;
				$data_shop_order['order_id'] = $order_id;
				$data_shop_order['refund_detail'] = serialize($go_refund_param['refund_param']);
				if (empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok') {
					$data_shop_order['status'] = 4;
				}
				$data_shop_order['last_time'] = time();
				D('Shop_order')->data($data_shop_order)->save();
				if($data_shop_order['status'] != 4){
					$this->error_tips($go_refund_param['msg']);
				}else{
					$go_refund_param['msg'] ="在线支付退款成功 ";
				}
			}


			$return = $this->shop_refund_detail($now_order, $store_id);
			if ($return['error_code']) {
				$this->error_tips($return['msg']);
			} else {
				$go_refund_param['msg'] .= $return['msg'];
			}

			if (empty($now_order['pay_type'])) {
				$data_shop_order['order_id'] = $now_order['order_id'];
				$data_shop_order['status'] = 4;
				$data_shop_order['last_time'] = time();
				D('Shop_order')->data($data_shop_order)->save();
				$go_refund_param['msg'] .= ' 取消订单成功';
			}
			if(empty($go_refund_param['msg'])){
				$go_refund_param['msg'] .= ' 取消订单成功';
			}
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));
			$this->success_tips($go_refund_param['msg'], U('Shop/status',array('order_id' => $order_id, 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
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


	/**
	 * 快店评论
	 */
	public function shop_feedback()
	{
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$now_order = D('Shop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => intval($_GET['order_id'])));


		if (empty($now_order)) {
			$this->error_tips('当前订单不存在！');
		}
		if (empty($now_order['paid'])) {
			$this->error_tips('当前订单未付款！无法评论');
		}
		if ($now_order['status'] < 2) {
			$this->error_tips('当前订单未消费！无法评论');
		}
		if ($now_order['status'] == 3) {
			$this->error_tips('当前订单已评论');
		}

		if (isset($now_order['info'])) {
			$list = array();
			$goods_ids = array();
			foreach ($now_order['info'] as $row) {
				if (!in_array($row['goods_id'], $goods_ids)) {
					$goods_ids[] = $row['goods_id'];
					$list[] = $row;
				}
			}
			$now_order['info'] = $list;
		}

		$this->assign('now_order', $now_order);


		$this->display();
	}

	public function add_comment()
	{
		if(empty($this->user_session)){
			exit(json_encode(array('status' => 0, 'msg' => '请先进行登录！')));
			$this->error_tips('请先进行登录！');
		}
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$goods_ids = isset($_POST['goods_ids']) ? $_POST['goods_ids'] : 0;
		$score = isset($_POST['whole']) ? $_POST['whole'] : 5;
		$comment = isset($_POST['textAre']) ? htmlspecialchars(trim($_POST['textAre'])) : '';
		
		$dscore = isset($_POST['score']) ? $_POST['score'] : 5;
		$dcomment = isset($_POST['comment']) ? htmlspecialchars(trim($_POST['comment'])) : '';
		

		$now_order = D('Shop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => $order_id));

		if (empty($comment)) {
		    exit(json_encode(array('status' => 0, 'msg' => '请填写您的宝贵意见！')));
		}
		if (empty($now_order)) {
			exit(json_encode(array('status' => 0, 'msg' => '当前订单不存在！')));
		}
		if (empty($now_order['paid'])) {
			exit(json_encode(array('status' => 0, 'msg' => '当前订单未付款！无法评论')));
		}
		if ($now_order['status'] < 2) {
			exit(json_encode(array('status' => 0, 'msg' => '当前订单未消费！无法评论')));
		}
		if ($now_order['status'] == 3) {
			exit(json_encode(array('status' => 0, 'msg' => '当前订单已评论')));
		}


		$goodsids = array();

		$goods = '';
		$pre = '';
		if (isset($now_order['info'])) {
			foreach ($now_order['info'] as $row) {
				if (!in_array($row['goods_id'], $goodsids)) {
					$goodsids[] = $row['goods_id'];
					if (in_array($row['goods_id'], $goods_ids)) {
						$goods .= $pre . $row['name'];
						$pre = '#@#';
					}
				}
			}
		}
		$database_reply = D('Reply');
		$data_reply['parent_id'] = $now_order['store_id'];
		$data_reply['store_id'] = $now_order['store_id'];
		$data_reply['mer_id'] = $now_order['mer_id'];
		$data_reply['score'] = $score;
		$data_reply['order_type'] = 3;
		$data_reply['order_id'] = intval($now_order['order_id']);
		$data_reply['anonymous'] = 1;
		$data_reply['comment'] = $comment;
		$data_reply['uid'] = $this->user_session['uid'];
		$data_reply['pic'] = '';
		$data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
		$data_reply['add_ip'] = get_client_ip(1);
		$data_reply['goods'] = $goods;
		$data_reply['deliver_score'] = $dscore;
// 		echo "<pre/>";
// 		print_r($data_reply);die;
		if ($database_reply->data($data_reply)->add()) {
		    D('Merchant_store')->setInc_shop_reply($now_order['store_id'], $score, $dscore);
			D('Shop_order')->change_status($now_order['order_id'], 3);
			D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 8));
			foreach ($goods_ids as $goods_id) {
				if (in_array($goods_id, $goodsids)) {
					D('Shop_goods')->where(array('goods_id' => $goods_id))->setInc('reply_count', 1);
					D('Shop_order_detail')->where(array('goods_id' => $goods_id, 'order_id' => $order_id))->save(array('is_goods' => 1));
				}
			}

			if ($supply = D('Deliver_supply')->field(true)->where(array('order_id' => $now_order['order_id'], 'item' => 2))->find()) {
			    D('Deliver_supply')->where(array('order_id' => $now_order['order_id'], 'item' => 2))->save(array('score' => $dscore, 'status' => 6, 'comment_time' => time(), 'comment' => $dcomment,'userId' => $this->user_session['uid']));
			    if ($user = D('Deliver_user')->field(true)->where(array('uid' => $supply['uid']))->find()) {
			        $userData = array();
			        $userData['reply_count'] = $user['reply_count'] + 1;
			        $userData['total_score'] = $user['total_score'] + $dscore;
			        $userData['average_score'] = round($userData['total_score'] / $userData['reply_count'], 2);
			        D('Deliver_user')->where(array('uid' => $supply['uid']))->save($userData);
			    }
			}
// 			$database_merchant_score = D('Merchant_score');
// 			$now_merchant_score = $database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['mer_id'],'type'=>'1'))->find();
// 			if(empty($now_merchant_score)){
// 				$data_merchant_score['parent_id'] = $now_order['mer_id'];
// 				$data_merchant_score['type'] = '1';
// 				$data_merchant_score['score_all'] = $score;
// 				$data_merchant_score['reply_count'] = 1;
// 				$database_merchant_score->data($data_merchant_score)->add();
// 			}else{
// 				$data_merchant_score['score_all'] = $now_merchant_score['score_all']+$score;
// 				$data_merchant_score['reply_count'] = $now_merchant_score['reply_count']+1;
// 				$database_merchant_score->where(array('pigcms_id'=>$now_merchant_score['pigcms_id']))->data($data_merchant_score)->save();
// 			}
// 			$now_store_score=$database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['store_id'],'type'=>'2'))->find();
// 			if(empty($now_store_score)){
// 				$data_store_score['parent_id'] = $now_order['store_id'];
// 				$data_store_score['type'] = '2';
// 				$data_store_score['score_all'] = $score;
// 				$data_store_score['reply_count'] = 1;
// 				$database_merchant_score->data($data_store_score)->add();
// 			}else{
// 				$data_store_score['score_all'] = $now_store_score['score_all']+$score;
// 				$data_store_score['reply_count'] = $now_store_score['reply_count']+1;
// 				$database_merchant_score->where(array('pigcms_id'=>$now_store_score['pigcms_id']))->data($data_store_score)->save();
// 			}
			if($this->config['feedback_score_add']>0){
			  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['shop_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
			  	D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],'用户'.$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'对'.$this->config['shop_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
			}
			exit(json_encode(array('status' => 1, 'msg' => '评论成功',  'url' => U('Shop/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])))));
			$this->success_tips('评论成功', U('Shop/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
		}
	}

	public function refund_back()
	{
		import('@.ORG.pay.Unionpay');

		$pay_class = new Unionpay('', '', 'unionpay', $pay_method['unionpay']['config'], '', 1);
		$get_pay_param = $pay_class->return_url();
		if ($get_pay_param['error']) {
			//TODO 退款失败的操作
		}
	}
	/*全部订餐订单列表*/
	public function foodshop_order_list()
	{
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;

		$where['uid'] = $this->user_session['uid'];
		$where['is_del'] = 0;
		if ($status != -1) {
			$where['status'] = $status;
		}


		$order_list = D("Foodshop_order")->get_order_list($where, 'order_id DESC', 0);
		$order_list = $order_list['order_list'];

		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}
		foreach ($list as $key => $val) {
			if ($val['status'] <= 1 || $val['status'] == 5) {
				$list[$key]['order_url'] = U('Foodshop/book_success', array('order_id' => $val['order_id']));
			} else {
				$list[$key]['order_url'] = U('Foodshop/order_detail', array('order_id' => $val['order_id']));
			}
		}
		$this->assign('order_list', $list);
		$this->display();
	}
	# 删除餐饮订单
	public function ajax_foodshop_order_del(){
		$database_shop_order = D('Foodshop_order');
		$now_order = $database_shop_order->get_order_by_id($this->user_session['uid'],intval($_GET['order_id']));

		if(empty($now_order)){
			exit(json_encode(array('status'=>0,'msg'=>'当前订单不存在！')));
		}

		$order_condition['order_id'] = $now_order['order_id'];
		$data['is_del'] = 1;
		if($database_shop_order->where($order_condition)->data($data)->save()){
			exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>'删除失败！')));
		}
	}
	public function ajax_foodshop_order_list()
	{
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;

		$where['uid'] = $this->user_session['uid'];
		$where['is_del'] = 0;
		if ($status != -1) {
			$where['status'] = $status;
		}


		$order_list = D("Foodshop_order")->get_order_list($where, 'order_id DESC', 0);
		$order_list = $order_list['order_list'];

		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}

		foreach($list as $key => $val){
			if ($val['status'] <= 1) {
				$list[$key]['order_url'] = U('Foodshop/book_success', array('order_id' => $val['order_id']));
			} else {
				$list[$key]['order_url'] = U('Foodshop/order_detail', array('order_id' => $val['order_id']));
			}
		}
		if (!empty($list)) {
			exit(json_encode(array('status' => 1, 'order_list' => $list)));
		} else {
			exit(json_encode(array('status' => 0, 'order_list' => $list)));
		}
	}
	/**
	 * 快店评论
	 */
	public function foodshop_feedback()
	{
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！');
		}
		$now_order = D('Foodshop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => intval($_GET['order_id'])));


		if (empty($now_order)) {
			$this->error_tips('当前订单不存在！');
		}

		if ($now_order['status'] < 3) {
			$this->error_tips('当前订单未消费！无法评论');
		}
		if ($now_order['status'] == 4) {
			$this->error_tips('当前订单已评论');
		}

		if (isset($now_order['info'])) {
			$list = array();
			$goods_ids = array();
			foreach ($now_order['info'] as $row) {
				if (!in_array($row['goods_id'], $goods_ids) && empty($row['is_must'])) {
					$goods_ids[] = $row['goods_id'];
					$list[] = $row;
				}
			}
			$now_order['info'] = $list;
		}

		$this->assign('now_order', $now_order);
		$this->display();
	}

	public function foodshop_comment()
	{
		if(empty($this->user_session)){
			exit(json_encode(array('status' => 0, 'msg' => '请先进行登录！')));
			$this->error_tips('请先进行登录！');
		}
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$goods_ids = isset($_POST['goods_ids']) ? $_POST['goods_ids'] : 0;
		$score = isset($_POST['whole']) ? $_POST['whole'] : 5;
		$comment = isset($_POST['textAre']) ? htmlspecialchars($_POST['textAre']) : 0;
		if (empty($comment)) {
			exit(json_encode(array('status' => 0, 'msg' => '评论内容不能为空！')));
		}
		$now_order = D('Foodshop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => $order_id));

		if (empty($now_order)) {
			exit(json_encode(array('status' => 0, 'msg' => '当前订单不存在！')));
		}

		if ($now_order['status'] < 3) {
			exit(json_encode(array('status' => 0, 'msg' => '当前订单未消费！无法评论')));
		}
		if ($now_order['status'] == 4) {
			exit(json_encode(array('status' => 0, 'msg' => '当前订单已评论')));
		}


		$goodsids = array();

		$goods = '';
		$pre = '';
		if (isset($now_order['info'])) {
			foreach ($now_order['info'] as $row) {
				if (!in_array($row['goods_id'], $goodsids)) {
					$goodsids[] = $row['goods_id'];
					if (in_array($row['goods_id'], $goods_ids)) {
						$goods .= $pre . $row['name'];
						$pre = '#@#';
					}
				}
			}
		}
		$database_reply = D('Reply');
		$data_reply['parent_id'] = $now_order['store_id'];
		$data_reply['store_id'] = $now_order['store_id'];
		$data_reply['mer_id'] = $now_order['mer_id'];
		$data_reply['score'] = $score;
		$data_reply['order_type'] = 4;//新的餐饮
		$data_reply['order_id'] = intval($now_order['order_id']);
		$data_reply['anonymous'] = 1;
		$data_reply['comment'] = $comment;
		$data_reply['uid'] = $this->user_session['uid'];
		$data_reply['pic'] = '';
		$data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
		$data_reply['add_ip'] = get_client_ip(1);
		$data_reply['goods'] = $goods;
		if ($database_reply->data($data_reply)->add()) {
			D('Merchant_store')->setInc_foodshop_reply($now_order['store_id'], $score);
			D('Foodshop_order')->change_status($now_order['order_id'], 4);
// 			D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 8));
			foreach ($goods_ids as $goods_id) {
				if (in_array($goods_id, $goodsids)) {
					D('Foodshop_goods')->where(array('goods_id' => $goods_id))->setInc('reply_count', 1);
					D('Foodshop_order_detail')->where(array('goods_id' => $goods_id, 'order_id' => $order_id))->save(array('is_goods' => 1));
				}
			}
			if($this->config['feedback_score_add']>0){
			  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['meal_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
			  	D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],'用户'.$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'对'.$this->config['meal_alias_name'].'评论获得'.$this->config['feedback_score_add'].'个'.$this->config['score_name']);
			}
			exit(json_encode(array('status' => 1, 'msg' => '评论成功',  'url' => U('Foodshop/order_detail', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])))));
		}
	}
	# 公共接口
	public function user_sessions(){
		if(empty($this->user_session)){
			$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
			redirect(U('Login/index',$location_param));
		}
	}
	public function user_sessions_json(){
		if(empty($this->user_session)){
			$this->returnCode('20020008');
		}
	}
	# 车主实名认证
	public function car_owner(){
		$find	=	M('User_authentication_car')->field(true)->where(array('uid'=>$this->user_session['uid']))->order('add_time DESC')->find();
		if($find){
			$image_class = new scenic_image();
			$find['authentication_img'] = $image_class->get_car_by_path($find['authentication_img'],$this->config['site_url'],'authentication_car','s');
			$find['authentication_back_img'] = $image_class->get_car_by_path($find['authentication_back_img'],$this->config['site_url'],'authentication_car','s');
			$find['drivers_license'] = $image_class->get_car_by_path($find['drivers_license'],$this->config['site_url'],'authentication_car','s');
			$find['driving_license'] = $image_class->get_car_by_path($find['driving_license'],$this->config['site_url'],'authentication_car','s');
		}
		$this->assign('find',$find);
		$this->display();
	}
	# 申请车主认证
	public function car_apply(){
		$plate_number	=	$this->plate_number();
		$find	=	M('User_authentication_car')->field(true)->where(array('uid'=>$this->user_session['uid']))->order('add_time DESC')->find();
		if($find){
			if($find['status'] == 2){
				$find	=	0;
			}
		}
		$this->assign('plate_number',$plate_number);
		$this->assign('find',$find);
		$this->display();
	}
	public function car_apply_json(){
		$_POST['uid']	=	$this->user_session['uid'];
		$_POST['add_time']	=	$_SERVER['REQUEST_TIME'];
		$add	=	M('User_authentication_car')->data($_POST)->add();
		if($add){
			$this->returnCode(0,U('car_apply'));
		}else{
			$this->returnCode('20046028');
		}
	}
	# 车牌
	public function plate_number(){
		$arr	=	array(
			array('id'=>1,'name'=>'北京','front'=>'京'),
			array('id'=>2,'name'=>'天津','front'=>'津'),
			array('id'=>3,'name'=>'上海','front'=>'沪'),
			array('id'=>4,'name'=>'重庆','front'=>'渝'),
			array('id'=>5,'name'=>'内蒙古自治区','front'=>'蒙'),
			array('id'=>6,'name'=>'维吾尔自治区','front'=>'新'),
			array('id'=>7,'name'=>'西藏自治区','front'=>'藏'),
			array('id'=>8,'name'=>'宁夏回族自治区','front'=>'宁'),
			array('id'=>9,'name'=>'广西壮族自治区','front'=>'桂'),
			array('id'=>10,'name'=>'香港特别行政区','front'=>'港'),
			array('id'=>11,'name'=>'澳门特别行政区','front'=>'澳'),
			array('id'=>12,'name'=>'黑龙江省','front'=>'黑'),
			array('id'=>13,'name'=>'吉林省','front'=>'吉'),
			array('id'=>14,'name'=>'辽宁省','front'=>'辽'),
			array('id'=>15,'name'=>'山西省','front'=>'晋'),
			array('id'=>16,'name'=>'河北省','front'=>'冀'),
			array('id'=>17,'name'=>'青海省','front'=>'青'),
			array('id'=>18,'name'=>'山东省','front'=>'鲁'),
			array('id'=>19,'name'=>'河南省','front'=>'豫'),
			array('id'=>20,'name'=>'江苏省','front'=>'苏'),
			array('id'=>21,'name'=>'安徽省','front'=>'皖'),
			array('id'=>22,'name'=>'浙江省','front'=>'浙'),
			array('id'=>23,'name'=>'福建省','front'=>'闽'),
			array('id'=>24,'name'=>'江西省','front'=>'赣'),
			array('id'=>25,'name'=>'湖南省','front'=>'湘'),
			array('id'=>26,'name'=>'湖北省','front'=>'鄂'),
			array('id'=>27,'name'=>'广东省','front'=>'粤'),
			array('id'=>28,'name'=>'海南省','front'=>'琼'),
			array('id'=>29,'name'=>'甘肃省','front'=>'甘'),
			array('id'=>30,'name'=>'陕西省','front'=>'陕'),
			array('id'=>31,'name'=>'贵州省','front'=>'贵'),
			array('id'=>32,'name'=>'云南省','front'=>'滇'),
			array('id'=>33,'name'=>'四川省','front'=>'川'),
		);
		return $arr;
	}
	/* 图片上传 */
    public function ajaxWebUpload(){
		if ($_FILES['file']['error'] != 4) {
        	$width = '900,450';
        	$height = '500,250';
			$param = array('size' => 2);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $width;
            $param['thumbMaxHeight'] = $height;
            $param['thumbRemoveOrigin'] = false;
			$image = D('Image')->handle($this->user_session['uid'], 'authentication_car', 1, $param);
			if ($image['error']) {
				exit(json_encode(array('error' => 1,'message' =>$image['msg'])));
			} else {
				exit(json_encode(array('error' => 0, 'url' => $image['url']['file'], 'title' => $image['title']['file'])));
			}
		}else{
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
    }

	public function group_recive_confirm()
	{
		$order_id = $_GET['order_id'];
		$condition_group_order['order_id'] = $order_id;
		$now_order = M('Group_order')->where($condition_group_order)->find();
		if($now_order['status']==1){
			$this->error_tips('该订单已经确认收货了',U('My/group_order',array('order_id'=>$order_id)));
		}
		if ($now_order['paid'] == 1 && $now_order['status'] == 0) {
			$data_group_order['status'] = 1; //原来是1
			$data_group_order['use_time'] = time();
		}
		if (M('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {
			$now_user = D('User')->get_user($now_order['uid'],'uid');
			$express_nmae = D('Express')->get_express($now_order['express_type']);
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$href = C('config.site_url').'/wap.php?c=My&a=group_order&order_id='.$now_order['order_id'];
			$model->sendTempMsg('TM00017', array('href' => $href,
					'wecha_id' => $now_user['openid'],
					'first' => $this->config['group_alias_name'].'快递收货通知',
					'OrderSn' => $now_order['real_orderid'],
					'OrderStatus' =>'您在'.date('Y-m-d H:i:s').'已确认收货',
					'remark' =>'快递号：'.$now_order['express_id'].'('.$express_nmae['name'].')'), $now_order['mer_id']);

			D('Group')->group_notice($now_order,1);
			$this->success_tips('订单状态修改成功',U('My/group_order',array('order_id'=>$order_id)));
		}else{
			$this->error_tips('订单状态修改失败',U('My/group_order',array('order_id'=>$order_id)));
		}

	}

	/*
	 * 到店付订单详情
	 * */
	public function store_order_detail(){
		if($_GET['wxapp_share_page']){

			$result = M('Store_order')->where(array('order_id' => $_GET['order_id'], 'uid' => $this->user_session['uid']))->setField('share_status', 1);
			if($this->config['share_coupon']){
				$result = D('System_coupon')->share_coupon(array('order_id'=>$_GET['order_id'],'uid'=>$this->user_session['uid'],'type'=>'store'));
			}
		}
		$order = D('Store_order')->get_order_by_id($this->user_session['uid'],$_GET['order_id']);
		if(empty($order)){
			$this->error_tips('订单不存在');
		}
		M('Store_order')->where(array('order_id'=>$order['order_id']))->setInc('show_lottery_first',1);
		$order['pay_type_str']  = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay'], $order['paid']);
		$store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($store['pic_info']);
		$store['image'] = isset($images[0]) ? $images[0] : '';
		$lottery  = M('Lottery_shop')->where(array('mer_id'=>$order['mer_id'],'status'=>1))->find();
		$lottery_info  = M('Lottery_shop_list')->where(array('uid'=>$this->user_session['uid'],'order_id'=>$_GET['order_id']))->find();

		C('config.share_rand_send_coupon')==1 && $coupon_where['rand_send'] = 1;

		$coupon_where['status']=1;
		$share_coupon = D('System_coupon')->get_coupon_list($coupon_where);

		$this->assign('share_coupon', $share_coupon);
		$this->assign('lottery', $lottery);
		$this->assign('lottery_info', $lottery_info);
		$this->assign('store',$store);
		$this->assign('order',$order);
		if($this->is_app_browser){
			$this->display('store_order_detail_app');
		}else{
			$this->display();
		}
	}


	/*
	 * 签到功能
	 * */
	public  function sign(){
		$return = D('User')->sign_in($this->user_session['uid']);
		if($return['error_code']==2){
			$this->error_tips($return['msg'],U('Wap/Login/index'));
		}else if($return['error_code']==1){
			$this->error_tips($return['msg'],U('Wap/Home/index'));
		}else{
			$this->success_tips($return['msg'],U('Wap/Home/index'));
		}
	}

	public function map(){
		$this->display();
	}


	/*
	 * 分润钱包
	 * */
	public function fenrun_money(){
		$this->display();
	}

	public function my_spread_code(){

		if(empty($this->now_user['spread_code'])){
			$spread_code = $this->make_password();
			M('User')->where(array('uid'=>$this->now_user['uid']))->setField('spread_code',$spread_code);
		}else{
			$spread_code = $this->now_user['spread_code'];
		}
		if($this->now_user['openid']){

			$spread_info = M('User_spread_qrcode')->where(array('uid'=>$this->now_user['uid']))->find();
			if($spread_info && $spread_info['qrcode_type']==0&&time()>strtotime(date('Y-m-d',$spread_info['last_time']))+86400){
				$this->get_spread_qrcode($spread_info['url'],$this->now_user['uid']);
				$spread_info = M('User_spread_qrcode')->where(array('uid'=>$this->now_user['uid']))->find();
			}else if(empty($spread_info)){
				$this->get_spread_qrcode($this->config['site_url'].'/wap.php',$this->now_user['uid']);
				$spread_info = M('User_spread_qrcode')->where(array('uid'=>$this->now_user['uid']))->find();

			}

			if($this->config['open_distributor']==1){
				$this->get_spread_qrcode($this->config['site_url'].'/packapp/merchant/reg.html?spread_code='.$spread_code,$this->now_user['uid']);
				$spread_info = M('User_spread_qrcode')->where(array('uid'=>$this->now_user['uid']))->find();
			}
			$this->assign('spread_qrcode',$spread_info['ticket']);
		}
		$this->assign('spread_code',$spread_code);
		$this->display();
	}

	public function fenrun_recharge_money(){

		$fenrun_money = $_POST['fenrn_money'];
		if($fenrun_money <=0 && $fenrun_money>$this->user_session['fenrun_money']){
			$this->error_tips('您输入的分润金额有误');
		}
		$result = D('User')->fenrun_recharge($fenrun_money);
		if($result['error_code']){
			$this->error_tips($result['msg']);
		}else{
			$this->success_tips($result['msg']);
		}
		$this->display();
	}

	//用户付款而二维码
	public function pay_qrcode(){
		//14位码
		$_SESSION['tmp_payid'] = substr($this->user_session['uid'],0,1).substr($this->user_session['uid'],-1).substr(uniqid('', true), 17).substr(microtime(), 2, 6);
		if(M('Tmp_payid')->where(array('payid'=>$_SESSION['tmp_payid'],'uid'=>$this->user_session['uid']))->find()){
			$this->pay_qrcode();
		}
		$date['uid'] = $this->user_session['uid'];
		$date['payid'] = $_SESSION['tmp_payid'];
		$date['add_time'] = $_SERVER['REQUEST_TIME'];
		M('Tmp_payid')->add($date);
		if(IS_POST){
			$this->success(1);exit;
		}

		return true;
	}

	public function scan_order(){
		$res = D('Store_order')->get_order_by_payid($this->user_session['uid'],$_SESSION['tmp_payid']);
		if(empty($res)){
			$this->error('');
		}
		$this->success('',U('Pay/check',array('type'=>'store','order_id'=>$res['order_id'])));
	}

	//抽奖概率
	public function lottery_shop(){
		$type = $_GET['type'];
		$order_id = $_GET['order_id'];
		$where['order_id']=$order_id;
		$where['uid']=$this->user_session['uid'];

		switch($type){
			case 'shop':
				$now_order = M('Shop_order')->where($where)->find();
				break;
			case 'store':
				$now_order = M('Store_order')->where($where)->find();
				break;
			default:
				$this->error('无效请求');
				break;
		}
		if($now_order) {
			if($now_order['share_status']==0){
				$this->error_tips('您还没有分享给朋友或朋友圈，请分享后再抽奖',U('Shop/status',array('order_id'=>$order_id)));
			}

			$mer_id = $now_order['mer_id'];
			$lottery   = M('Lottery_shop')->where(array('mer_id' => $mer_id, 'status' => 1))->find();
			$lottery['content'] = unserialize($lottery['content']);
			$lottery['sys_content'] = unserialize($lottery['sys_content']);
			$prize_arr = array(
					'0' => array('id' => 0,'image_url'=>$lottery['sys_content'][0]['image_url'],'is_win'=>1, 'title'=>$lottery['sys_content'][0]['title'],'v' => $lottery['sys_content'][0]['probability']),
					'1' => array('id' => 1,'image_url'=>$lottery['content'][0]['image_url'],'is_win'=>$lottery['content'][0]['is_win'], 'title'=>$lottery['content'][0]['title'],'v' => $lottery['content'][0]['probability']),
					'2' => array('id' => 2,'image_url'=>$lottery['content'][1]['image_url'],'is_win'=>$lottery['content'][1]['is_win'], 'title'=>$lottery['content'][1]['title'],'v' => $lottery['content'][1]['probability']),
					'3' => array('id' => 3,'image_url'=>$lottery['sys_content'][1]['image_url'],'is_win'=>1, 'title'=>$lottery['sys_content'][1]['title'],'v' => $lottery['sys_content'][1]['probability']),
					'4' => array('id' => 4,'image_url'=>$lottery['content'][2]['image_url'],'is_win'=>$lottery['content'][2]['is_win'], 'title'=>$lottery['content'][2]['title'],'v' => $lottery['content'][2]['probability']),
					'5' => array('id' => 5,'image_url'=>$lottery['sys_content'][2]['image_url'],'is_win'=>1, 'title'=>$lottery['sys_content'][2]['title'],'v' => $lottery['sys_content'][2]['probability']),
					'6' => array('id' => 6,'image_url'=>$lottery['content'][3]['image_url'],'is_win'=>$lottery['content'][3]['is_win'], 'title'=>$lottery['content'][3]['title'],'v' => $lottery['content'][3]['probability']),
					'7' => array('id' => 7,'image_url'=>$lottery['content'][4]['image_url'],'is_win'=>$lottery['content'][4]['is_win'], 'title'=>$lottery['content'][4]['title'],'v' => $lottery['content'][4]['probability']),
					'8' => array('id' => 8,'image_url'=>$lottery['sys_content'][3]['image_url'],'is_win'=>1, 'title'=>$lottery['sys_content'][3]['title'],'v' => $lottery['sys_content'][3]['probability']),
					'9' => array('id' => 9,'image_url'=>$lottery['content'][5]['image_url'],'is_win'=>$lottery['content'][5]['is_win'], 'title'=>$lottery['content'][5]['title'],'v' => $lottery['content'][5]['probability']),
			);

			foreach ($prize_arr as $key => $val) {
				$arr[$val['id']] = $val['v'];
			}

			$rid = $this->get_rand($arr); //根据概率获取奖项id

			if(!$res = M('Lottery_shop_list')->where(array('uid'=>$this->user_session['uid'],'order_id'=>$order_id))->find()){
				$data['lottery_id'] = $rid;
				$data['mer_id'] = $mer_id;
				$data['uid'] = $this->now_user['uid'];
				$data['order_id'] = $order_id;
				$data['add_time'] = $_SERVER['REQUEST_TIME'];
				$data['status'] = 0;
				$data['is_win'] = $prize_arr[$rid]['is_win'];
				M('Lottery_shop_list')->add($data);

			}else{

				if($res['status']==1){
					$this->error_tips('您已经抽过奖了，请在获奖记录里查看',U('lottery_shop_list'));
				}
				$rid = $res['lottery_id'];
			}
			$this->assign('rid',$rid);
			$lottery['lottery_rule'] = str_replace(PHP_EOL,'<br>',$lottery['lottery_rule']);
			$this->assign('lottery',$lottery);
			$this->assign('prize_arr',$prize_arr);
		}else{
			$this->error_tips('订单不存在');
		}
		$this->display();
	}


	//更新分享状态
	public  function ajax_share_friend(){
		$type = $_POST['order_type'];
		$order_id = $_POST['order_id'];
		$where['order_id']=$order_id;
		$where['uid']=$this->user_session['uid'];
		//$where['share_status']=0;
		switch($type){
			case 'shop':
				if($res = M('Shop_order')->where($where)->find()){
					$res['paid']==1 && $res['share_status'] == 0 && $result = M('Shop_order')->where($where)->setField('share_status', 1);
					$res['share_status'] = 1;
				}
				break;
			case 'store':
				if($res = M('Store_order')->where($where)->find()){
					$res['share_status']==0  && $result = M('Store_order')->where($where)->setField('share_status',1);
					$res['share_status'] = 1;
					$res['status'] =2;
				}
				break;
			default:
				$this->error('无效请求');
				break;
		}
		if(!$res['paid']){
			$this->error('订单未支付');
		}
		if($this->config['open_share_lottery']){
			$lottery   = M('Lottery_shop')->where(array('mer_id' => $res['mer_id'], 'status' => 1))->find();
			if(empty($lottery)){
				$this->error('无效请求');
			}
		}
		if($res['share_status']==1){
			if($this->config['open_share_lottery']==0){
				if($this->config['share_coupon']){
					$result = D('System_coupon')->share_coupon(array('order_id'=>$order_id,'uid'=>$this->user_session['uid'],'type'=>$type));
				}
				if($result['error_code']){
					$this->error($result['msg']);
				}else{
					$this->success('分享成功');die;
				}
			}else if($result){
				$this->success('分享成功');die;
			}

		}

		if($this->config['open_share_lottery']){
			if($res['status']==2){
				$res['share_status'] && $this->success('可以抽奖', U('My/lottery_shop', array('order_id' => $order_id,'type'=>$type)));
			}else{
				$res['share_status'] && $this->error('订单完成后才能抽奖');
			}
		}else if($this->config['share_coupon']){
			$res['status'] == 2 && $res['share_status'] && $this->success('获得分享优惠券', U('Share_lottery/my_get_coupon', array('order_id' => $order_id,'type'=>$type)));
		}
		$this->error('无效请求');
	}



	public function lottery_shop_list(){
		$this->display();
	}

	public function lottery_shop_list_json(){
		$page	=	$_POST['page'];
		$Lottery	=	D('Lottery_shop_list');

		$condition_user_score_list['uid'] = $this->user_session['uid'];
		$condition_user_score_list['award_time'] = array('gt',0);
		$_GET['page'] = isset($_POST['page'])?$_POST['page']:$_GET['page'];
		import('@.ORG.user_page');
		$count = $Lottery->where($condition_user_score_list)->count();
		$p = new Page($count,10);
		//$return['score_list'] = $Lottery->join(' as l left join '.C('DB_PREFIX').'lottery_shop ls ON l.mer_id = ls.mer_id')->where($condition_user_score_list)->order('`add_time` DESC')->limit($p->firstRow,$p->listRows)->select();
		$return['score_list'] = $Lottery->where($condition_user_score_list)->order('`add_time` DESC')->limit($p->firstRow,$p->listRows)->select();
		if($_GET['page'] >  $p->totalPage &&  $p->totalPage>0){
			$return['score_list'] = array();
		}
		$return['pagebar'] = $p->show();
		$return['count'] = count($return['score_list']);
		$sys_lottery = array(0,3,5,9);
		$mer_lottery = array(1,2,4,6,7,8,);

		foreach($return['score_list'] as &$v){
			$v['return'] = unserialize($v['return']);
			$v['time_s']	=	date('Y/m/d H:i',$v['award_time']);
		}
		echo json_encode($return);
	}
	//检查是否已经抽过奖了
	public function ajax_check_lottery(){
		if($lottery = M('Lottery_shop_list')->where(array('uid'=>$this->user_session['uid'],'order_id'=>$_POST['order_id']))->find()){
			$type = $_POST['type'];
			$where=array('uid'=>$this->user_session['uid'],'order_id'=>$_POST['order_id']);
			switch($_POST['type']){
				case 'shop':
					$now_order = M('Shop_order')->where($where)->find();
					break;
				case 'store':
					$now_order = M('Store_order')->where($where)->find();
					$type='cash';
					break;
			}
			if($lottery['status']){
				$this->error('您已经抽过奖了');
			}else{
				if($_POST['award']){
					$data['status']=1;
					$data['lottery_time']=time();

					if($lottery['is_win']&&$lottery['lottery_time']==0 && $lottery['award_time']==0){
						$lottery_info   = M('Lottery_shop')->where(array('mer_id' => $lottery['mer_id'], 'status' => 1))->find();
						$lottery_info['content'] = unserialize($lottery_info['content']);
						$lottery_info['sys_content'] = unserialize($lottery_info['sys_content']);
						$sys_lottery = array(0,3,5,8);
						$mer_lottery = array(1,2,4,6,7,9,);

						if( in_array($lottery['lottery_id'],$sys_lottery)){
							$key = array_keys($sys_lottery,$lottery['lottery_id']);
							$key =$key[0];

							if($lottery_info['sys_content'][$key]['type']==0 ){  //红包  平台发
								if($this->user_session['openid']==''){
									$error_msg = '用户未绑定微信，无法发送红包';
								}else {

									$order_info['payment_money'] = $pay_money = round(($pay_money = $now_order['balance_pay'] + $now_order['payment_money'] + $now_order['score_deducte'] + $now_order['merchant_balance']) * $this->config['redpack_percent'] / 100, 2);
									if($pay_money>200){
										$pay_money=200;
									}
									if($pay_money>=1) {
										$pay_method                  = D('Config')->get_pay_method($notOnline, $notOffline, true);
										$import_result               = import('@.ORG.pay.Weixin');
										$order_info['red_desc'] = '微信红包';
										$pay_class              = new Weixin($order_info, $pay_money, 'weixin', $pay_method['weixin']['config'], $this->user_session, 1);
										$return                 = $pay_class->sendredpack();
										$return['msg']  ="分享{$this->config[$type.'_alias_name']}订单后抽奖，抽奖抽中红包,".$return['msg'].'红包金额'.$pay_money.'元';
										D('Scroll_msg')->add_msg('fxcj',$this->user_session['uid'],'用户'.$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).  $return['msg']);

										$data['return']         = serialize($return);
										$data['award_time']     = time();
										M('Lottery_shop_list')->where(array('uid' => $this->user_session['uid'], 'order_id' => $_POST['order_id']))->save($data);
										if ($return['error']) {

										} else {
											$href  = $this->config['site_url'] . '/wap.php?c=My&a=lottery_shop_list';
											$model = new templateNews($this->config['wechat_appid'], $this->config['wechat_appsecret']);
											$model->sendTempMsg('TM00785', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '分享抽奖中奖信息', 'program' => '分享抽奖，抽中微信红包' . $pay_money . '元', 'result'=>$return['msg'],'remark' => '请及时查收！'), '');
											$this->success('红包发送成功，注意查收');
										}
									}else{
										$return['msg']  ="由于计算出您本次获得的红包金额小于1元，故无法发送红包，抱歉！";
										$data['return']         = serialize($return);
										$data['award_time']     = time();
										M('Lottery_shop_list')->where(array('uid' => $this->user_session['uid'], 'order_id' => $_POST['order_id']))->save($data);
										$this->error($return['msg'] );
									}
								}
							}elseif($lottery_info['sys_content'][$key]['type']==1){  //优惠券
								$coupon_id = $lottery_info['sys_content'][$key]['coupon_id'];
								$return = D('System_coupon')->send_coupon_by_id($coupon_id,$this->user_session['uid']);//不管超不超过限制都送但是不能超过总数，超过报错
								$return['msg'] = "分享{$this->config[$type.'_alias_name']}订单后抽奖,抽中系统优惠券，".$return['msg'];
								D('Scroll_msg')->add_msg('fxcj',$this->user_session['uid'],'用户'.$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).  $return['msg']);

								$data['return']  = serialize($return);
								$data['award_time']  = time();
								M('Lottery_shop_list')->where(array('uid'=>$this->user_session['uid'],'order_id'=>$_POST['order_id']))->save($data);
								if(!$return['error']) {

									$href  = $this->config['site_url'] . '/wap.php?c=My&a=lottery_shop_list';
									$model = new templateNews($this->config['wechat_appid'], $this->config['wechat_appsecret']);
									$model->sendTempMsg('TM00785', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '分享抽奖中奖信息', 'program' => "分享抽奖抽中优惠券【{$lottery_info['sys_content'][$key]['title']}】", 'result'=>$return['msg'], 'remark' => '请及时上线联系商家进行兑奖！'), $lottery_info['mer_id']);
									$this->success($return['msg']);
								}else{
									$this->error($return['msg']);
								}
							}
						}else{
							//商家设置选项
							$key = array_keys($mer_lottery,$lottery['lottery_id']);

							$key =$key[0];
							if($lottery_info['content'][$key]['type']==0){  //优惠券
								$coupon_id = $lottery_info['content'][$key]['coupon_id'];
								$return = D('Card_new_coupon')->send_coupon_by_id($coupon_id,$this->user_session['uid']);//不管超不超过限制都送但是不能超过总数，超过报错
								$return['msg'] = "分享{$this->config[$type.'_alias_name']}订单后抽奖,抽中商家优惠券，".$return['msg'];
								D('Scroll_msg')->add_msg('fxcj',$this->user_session['uid'],'用户'.$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).  $return['msg']);

								$data['return']  = serialize($return);
								$data['award_time']  = time();
								M('Lottery_shop_list')->where(array('uid'=>$this->user_session['uid'],'order_id'=>$_POST['order_id']))->save($data);
								if(!$return['error']) {

									$href  = $this->config['site_url'] . '/wap.php?c=My&a=lottery_shop_list';
									$model = new templateNews($this->config['wechat_appid'], $this->config['wechat_appsecret']);
									$model->sendTempMsg('TM00785', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '分享抽奖中奖信息', 'program' => "分享抽奖抽中优惠券【{$lottery_info['content'][$key]['title']}】", 'result'=>$return['msg'],'remark' => '请及时上线联系商家进行兑奖！'), $lottery_info['mer_id']);
									$this->success($return['msg']);
								}else{
									$this->error($return['msg']);
								}
							}else {
								$return['error'] = 0;
								if ($lottery['is_win']) {
									$return['msg']   = "分享{$this->config[$type.'_alias_name']}订单后抽奖，抽中商家奖【{$lottery_info['content'][$key]['title']}】";
									D('Scroll_msg')->add_msg('fxcj',$this->user_session['uid'],'用户'.$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).  $return['msg']);
								} else {
									$return['error'] = 1;
//									$return['msg'] = "分享{$this->config[$type.'_alias_name']}订单后抽奖，未中奖{$lottery_info['content'][$key]['title']}";
									$return['msg'] = "分享{$this->config[$type.'_alias_name']}订单后抽奖，未中奖";
								}
								$data['award_time'] = time();
								$data['return']     = serialize($return);

								M('Lottery_shop_list')->where(array('uid' => $this->user_session['uid'], 'order_id' => $_POST['order_id']))->save($data);
								if (!$return['error']) {
									$href  = $this->config['site_url'] . '/wap.php?c=My&a=lottery_shop_list';
									$model = new templateNews($this->config['wechat_appid'], $this->config['wechat_appsecret']);
									$model->sendTempMsg('TM00785', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '分享抽奖中奖信息', 'program'=>"分享{$this->config[$type.'_alias_name']}订单后抽奖",'result' => $return['msg'], 'remark' => '请及时上线联系商家进行兑奖！'), $lottery_info['mer_id']);
									$this->success($return['msg']);
								}else{
									$this->error($return['msg']);
								}
							}
						}
					}else{
						$lottery_info   = M('Lottery_shop')->where(array('mer_id' => $lottery['mer_id'], 'status' => 1))->find();
						$lottery_info['content']  = unserialize($lottery_info['content']);
						$key = $lottery['lottery_id'];
						$return['error']=1;
						$return['msg'] = "分享{$this->config[$type.'_alias_name']}订单后抽奖，未中奖{$lottery_info['content'][$key]['title']}";

						$data['return']     = serialize($return);
						M('Lottery_shop_list')->where(array('uid' => $this->user_session['uid'], 'order_id' => $_POST['order_id']))->save($data);
					}
				}else{
					$this->success('可以抽奖');
				}
			}
		}else{
			$this->error('订单不存在');
		}
	}

	//智百威同步
	public function zbw_sync(){
		$now_user = D('User')->get_user($this->user_session['uid']);
		if( $now_user['phone']){
			$zbw_card = D('ZbwErp')->GetVipInfoTel($now_user['phone']);
			if(empty($zbw_card)){
				$zbw_card = D('ZbwErp')->VipCreate($now_user);
			}else{
				$now_money = D('ZbwErp')->GetVipSpareCash($zbw_card['card_id']);
				$score = D('ZbwErp')->GetVipSpareCash($zbw_card['card_id']);
			}

		}
	}

	public function get_rand($proArr) {
		$result = '';

		//概率数组的总概率精度
		$proSum = array_sum($proArr);

		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset ($proArr);

		return $result;
	}

	public function make_password( $length = 8)
	{
		$str = substr(md5(time()), 0, 6);
		if(!M('User')->where(array('spread_code'=>$str))->find()){
			return $str;
		}else{
			return  $this->make_password();
		}
	}

	//跳转商家客服中转页，目的是为了网页授权获取用户 openid
	public function concact_kefu(){
		$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => ($this->now_user['openid'] ? $this->now_user['openid'] : $this->now_user['uid'])), $this->config['im_appkey']);
		$url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . ($this->now_user['openid'] ? $this->now_user['openid'] : $this->now_user['uid']) . '&key=' . $key . '#serviceList_' . $_GET['mer_id'];
		redirect($url);
	}

	/* 跳转到IM聊天系统 */
	public function go_im(){
		$openid = $this->now_user['openid'] ? $this->now_user['openid'] : $this->now_user['uid'];
		$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' =>$openid ), $this->config['im_appkey']);
		
		$hash = ltrim($_GET['hash'],'group_user');
		if($hash != $_GET['hash'] && preg_match('/^(\d+)$/',$hash)){
			$nowUser = D('User')->get_user($hash);
			if($nowUser && $nowUser['openid']){
				$_GET['hash'] = 'group_'.$nowUser['openid'];
			}
		}
		
		$url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $openid . '&key=' . $key .($_GET['title'] ? '&title='.urlencode($_GET['title']) : ''). ($_GET['hash'] ? '#'.$_GET['hash'] : '');
		redirect($url);
	}
}
?>