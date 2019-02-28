<?php
/*
 * 微信图文的文章页
 *
 */
class SetAccountDepositAction extends BaseAction{
	private $allinyun;
	public function index(){
		if(empty($this->user_session['uid'])){
			$this->error_tips('您还没有登陆，请先登录',U('Login/index'));
			redirect(U('Login/index'));
		}
		$allinyun = M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find();
		if(empty($allinyun)){
			$this->createAllinyunAccount();
		}

		if($_GET['signResult']==1){
			$data['signStatus'] =1;
			M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->save($data);
		}
		$this->assign('deposit',$allinyun);

		$this->display();
	}

	public function createAllinyunAccount(){
		if(M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find()){
			$this->error('您已经创建了云商通账号，不能再次创建！');
		}
		import('@.ORG.AccountDeposit.AccountDeposit');
		$deposit = new AccountDeposit('Allinyun');
		$allyun = $deposit->getDeposit();
		$member = array(
				'bizUserId'=>C('config.allinyun_user_prefix').'_'.sprintf("%010d",$this->user_session['uid']),
				'memberType'=>3,
				'source'=>1,
		);
		
		
		$allyun->setUser($member);
		$allinyun_user = $allyun->getMemberInfo();
		if($allinyun_user['status']=='OK' && !M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find()){
			
			$data['uid'] = $this->user_session['uid'];
			$data['bizUserId'] =  $member['bizUserId'];
			$data['userId'] =  $allinyun_user['signedResult']['memberInfo']['userId'];
			$data['status'] = 1; //未审核
			M('User_allinyun_info')->add($data);
			$this->success_tips('创建云商通账号成功',U('index'));
		}else{
			
			
		
		
			$res = $allyun->createMember($member);

			if($res['status']=='error'){
				$this->error_tips($res['message']);
			}else{
				$data['uid'] = $this->user_session['uid'];
				$data['bizUserId'] =  $member['bizUserId'];
				$data['userId'] =  $res['userId'];
				$data['status'] = 1; //未审核
				M('User_allinyun_info')->add($data);

				$this->success_tips('创建云商通账号成功',U('bindphone'));
			}
		}
	}

	public function bindphone(){
		if(IS_POST){
			import('@.ORG.AccountDeposit.AccountDeposit');
			$deposit = new AccountDeposit('Allinyun');
			$allyun = $deposit->getDeposit();
			$allinyun = M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find();
			$allyun->setUser($allinyun);
			if(empty($_POST['phone'])){
				$this->error_tips('手机为空');
			}
			if(empty($_POST['code'])){
				$this->error_tips('验证码为空');
			}
			$res =  $allyun->bindPhone($_POST);
			if($res['status']=='error'){
				$this->error_tips($res['message']);
			}else{
				$data['bind_phone_status'] = 1;
				$data['phone'] = $_POST['phone'];
				M('User')->where(array('uid'=>$this->user_session['uid']))->setField('phone',$_POST['phone']);
				M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->save($data);
				
				$referer = !empty($_POST['referer']) ? htmlspecialchars_decode($_POST['referer']) : U('index');
				$this->success_tips('绑定成功！',$referer);
				// $this->success_tips('绑定成功！',U('verify_real_name'));
			}
		}else{
			$referer = !empty($_GET['referer']) ? htmlspecialchars_decode($_GET['referer']) : U('My/index');
			$this->assign('referer',$referer);
			$this->display();
		}
	}

	public function allyun(){
		import('@.ORG.AccountDeposit.AccountDeposit');
		$deposit = new AccountDeposit('Allinyun');
		$allyun = $deposit->getDeposit();
		$allinyun = M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find();
		$allyun->setUser($allinyun);
		$this->allinyun = $allinyun;
		
		return $allyun;
	}

	public function editphone(){
		if(IS_POST){
			$allyun = $this->allyun();
			$data['oldPhone'] = $this->allinyun['phone'];
			$data['newPhone'] = $_POST['phone'];
			$data['code'] = $_POST['code'];
			$res =  $allyun->changePhone($data);
			if($res['status']=='error'){
				$this->error_tips($res['message']);
			}else{
				$data['bind_phone_status'] = 1;
				$data['phone'] = $_POST['phone'];
				M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->save($data);
				$this->success_tips('重置成功！',U('index'));
			}
		}else{
			$allinyun = M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->find();
			$this->assign('deposit',$allinyun);
			$this->display();
		}

	}

	public function sendsms(){
		$allyun = $this->allyun();
		$res = $allyun->sendSMSCode($_POST['phone']);
		if($res['status']=='error'){
			$this->error($res['message']);
		}else{
			
			$this->success('发送成功');
		}
	}

	public function verify_real_name(){
		$allyun = $this->allyun();
			// $this->register_check($this->allinyun,'verify_real_name');
		if(IS_POST) {
			$res = $allyun->setRealName($_POST);
			if($res['status']=='error'){
				$this->error_tips('实名认证失败！'.$res['message']);
			}else{
				$data['identityNo'] =$_POST['identityNo'];
				$data['realName'] = $_POST['name'];
				M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->save($data);
				$this->success_tips('实名认证成功！',U('apply_bind_card'));
			}
		}else{
			$this->display();
		}
	}

	public function signConnect(){
		// if(IS_POST){
			$allyun = $this->allyun();
			// $this->register_check($this->allinyun,'signConnect');
			$url = $allyun->signContract();
			redirect($url);
		// }else{
			// $this->display();

		// }

	}
	
	public function sign_success(){
		$this->success_tips('签约成功');
		redirect(U('SetAccountDepositAction/index'));

	}
	
	public function register_check($allinyun){
		
		if($allinyun['phone']=='' ){
			$this->error_tips('您还未绑定手机,请先绑定手机号码',U('index'));
		}
		if($allinyun['realName']==''){
			$this->error_tips('您还未实名认证,请实名认证',U('verify_real_name'));
		}
		if($allinyun['signStatus']==0 ){
			$this->error_tips('您还进行会员电子签约',U('signConnect'));
		}
	}

	public function setPwd(){
		$allyun = $this->allyun();
		
		$allinyun = $this->allinyun;
		// $this->register_check($allinyun);
		$allinyun['source'] = 2;
		if($allinyun['setPwd']==1){
			
		$allinyun['action']  ='updatePayPwd';
		}else{
			
		$allinyun['action']  ='setPayPwd';
		}
		$url = $allyun->PayPWD($allinyun);
		// fdump($url,'url');die;
		redirect($url);
	}

	public function apply_bind_card(){
		
		$allyun = $this->allyun();
		
		$this->register_check($this->allinyun);
		if(IS_POST) {
			$data['cardNo'] = $_POST['cardNo'];
			$data['phone'] = $_POST['phone'];
			$data['name'] = $this->allinyun['realName'];
			$data['identityNo'] = $this->allinyun['identityNo'];
			$data['cardCheck'] =2;
			$data['unionCode'] =$_POST['unionCode'];
			if ($_POST['is_CreditCard']) {
				$data['validate'] = $_POST['validate'];
				$data['cvv2'] = $_POST['cvv2'];
			}
			if ($_POST['isSafeCard']) {
				$data['isSafeCard'] = $_POST['isSafeCard'];
			}
			// fdump($data);
			$res = $allyun->applayBindBankCard($data);

			if ($res['status'] == 'error') {
				$this->error('申请失败', $res['message']);
			} else {
				$data_companypay['from']     = 0;
				$data_companypay['type']     = 0;
				$data_companypay['pay_id']   = $this->user_session['uid'];
				$data_companypay['account_name'] = $this->allinyun['realName'];
				$data_companypay['account'] = $_POST['cardNo'];
				$data_companypay['is_default'] = 0;
				$data_companypay['add_time'] = $_SERVER['REQUEST_TIME'];
				$data_companypay['remark']    = $res['signedResult']['bankName'];
				$aid = M('User_withdraw_account_list')->add($data_companypay);
				$res['signedResult']['aid'] = $aid;
				$this->success('申请成功，短信验证码已发送', $res['signedResult']);
			}
		}else{
			$allinyun = M('User_allinyun_info')->where(array('uid' => $this->user_session['uid']))->find();
			$payMethod = $allyun->getPayMethod();
			$allinyun['identityNo'] = '*************'.substr($allinyun['identityNo'] ,-4);
			$this->assign('deposit',$allinyun);
			$this->assign('payMethod',$payMethod);
			$this->display();
		}
	}

	public function bind_card(){
		$allyun = $this->allyun();
		// $this->register_check($this->allinyun);
		$data['tranceNum'] = $_POST['tranceNum'];
		$_POST['transDate'] && $data['transDate'] = $_POST['transDate'];
		$data['phone'] = $_POST['phone'];
		$data['verificationCode'] = $_POST['verificationCode'];
		$res = $allyun->bindBankCard($data);
		if($res['status']=='error'){
			$this->error_tips('绑定失败'.','.$res['message']);
		}else{
			$data['bind_bank_list'] = $allinyun['bind_bank_list']!=''?','.$_POST['aid']:$_POST['aid'];
			M('User_allinyun_info')->where(array('uid'=>$this->user_session['uid']))->save($data);
			$this->success_tips('绑定成功',U('index'));
		}
	}

	public function add_bind_card(){
		$allinyun = M('User_allinyun_info')->where(array('uid' => $this->user_session['uid']))->find();
		$this->assign('deposit',$allinyun);
		$this->display();
	}


	public function money_list(){
		$this->display();
	}
	public function money_json(){
		$allyun = $this->allyun();
		$page	=	$_POST['page'];
		$result = $allyun->queryInExpDetail($_POST['page']?($_POST['page']-1)*10+1:1);
		
		$count = $result['signedResult']['totalNum'];
		$transaction['count'] = $count;
		$transaction['money_list'] = $result['signedResult']['inExpDetail'];

		echo json_encode($transaction);
	}

	public function integral(){
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

	public function withdraw_apply(){
		$allyun = $this->allyun();
		if(IS_POST) {
			if($_POST['money']<$this->config['company_least_money']){
				$this->error_tips('不能低于最低提款额 '.$this->config['company_least_money'].' 元!');
			}
			$bank = M('User_withdraw_account_list')->where(array('id'=>$_POST['bank_id']))->find();
			
			if($this->config['company_pay_user_percent']>0){
				$fee= intval($_POST['money']*($this->config['company_pay_user_percent'])/100*100);
			}
			
			if($fee>=$_POST['money']){
				$this->error_tips('申请失败,提现金额过小，不足以抵扣手续费');
			}
		
			$withdraw = array(
					'order_id'=>'withdraw_'.time(),
					'money'=>$_POST['money']*100,
					'bankCardNo'=>$allyun->rsaEncrypt($bank['account']),
			);
			
			$res = $allyun->withdrawApply($withdraw);

			if ($res['status'] == 'error') {
				$this->error('申请失败'.','.$res['message']);
			} else {
				$this->success('申请成功，短信验证码已发送', $res['signedResult']);
			}
		}else{
			$where=array('pay_id'=>$this->user_session['uid'],'from'=>0);
			$where['id'] = array('in',$this->allinyun['bind_bank_list']);
			$bank_list = M('User_withdraw_account_list')->where($where)->select();

			foreach ($bank_list as &$value) {
				$value['account'] =substr($value['account']  ,0,4).'***'.substr($value['account']  ,-4);
			}

			$this->assign('bank_list',$bank_list);
			$this->display();
		}
	}
	
	public function SmsPay(){
		if(IS_POST){
			$allyun = $this->allyun();
			$_POST['consumerIp'] = '36.7.111.127';
			
			$res = $allyun->confirmPayBackSms($_POST);
			
			if($res['status']=='OK'){
				$res['signedResult']['method'] = 'balance_pay';
                $res['signedResult']['amount'] = $_POST['payment_money'];
                $res['signedResult']['status'] = 'OK';
				redirect(C('config.site_url').'/source/allinyun_back.php?rps='.json_encode($res['signedResult']));
			}else{
				$this->error_tips('支付失败'.','.$res['message']);
			}
		}else{
			if(empty($_GET['order_id'])){
				$this->error_tips('订单不存在');
			}
			$order_id_info = explode('_',$_GET['order_id']);
			$order_id = $order_id_info[1];
			$_GET['type'] = $order_id_info[0];
			 $group_pay_offline = true;
			if($_GET['type'] == 'group'){
				$now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval(order_id));
				if($now_order['order_info']['group_share_num']>0||$now_order['order_info']['pin_num']>0)$group_pay_offline=false;
			}else if($_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad'){
				$now_order = D('Meal_order')->get_pay_order($this->user_session['uid'],intval(order_id), false, $_GET['type']);
				if ($now_order['order_info']['paid'] == 2) $this->assign('notCard',true);
				$_GET['type']  = 'meal';
			}else if($_GET['type'] == 'weidian'){
				$now_order = D('Weidian_order')->get_pay_order($this->user_session['uid'],intval(order_id));
				$this->assign('notCard',true);
			}else if($_GET['type'] == 'recharge'){
				$now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval(order_id));
				$this->assign('notCard',true);
			}else if($_GET['type'] == 'appoint'){
				$now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval(order_id));
			}else if($_GET['type'] == 'wxapp'){
				$_GET['notOffline'] = true;
				$now_order = D('Wxapp_order')->get_pay_order($_GET['uid'],intval(order_id));
				$this->assign('notCard',true);
			}else if($_GET['type'] == 'store'){
				$_GET['notOffline'] = true;
				$now_order = D('Store_order')->get_pay_order($this->user_session['uid'], intval(order_id));
				//$this->assign('notCard',true);
			}else if($_GET['type'] == 'shop' || $_GET['type'] == 'mall'){
				$now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval(order_id));
				if($this->config['open_extra_price']==0  && empty($this->user_session['phone']) && $now_order['order_info'] && $now_order['order_info']['order_from'] != 6){
					$this->error_tips('尊敬的用户，为了提供更好的服务，请您在消费之前先绑定手机号码！',U('My/bind_user',array('referer'=>urlencode(U('Pay/check',$_GET)))));
				}
			}else if($_GET['type'] == 'plat'){
				$now_order = D('Plat_order')->get_pay_order($this->user_session['uid'], intval(order_id));
				$now_order['order_info']['extra_price'] =  $now_order['order_info']['order_info']['extra_price'];
				if($now_order['order_info']['status']==1){
					$this->error_tips('订单已支付');
				}
			}else if($_GET['type'] == 'balance-appoint'){
				$now_order = D('Appoint_order')->get_pay_balace_order($this->user_session['uid'],intval(order_id));
			}else{
				$this->error_tips('非法的订单');
			}
			
			$this->display();
		}
	}
	

	public function withdraw(){
		$allyun = $this->allyun();
		if(IS_POST) {

			$confirm =array(
					'order_id'=>$_POST['order_id'],
					'orderNo'=>$_POST['orderNo'],
					'consumerIp'=>'36.7.111.127',
					'verificationCode'=>$_POST['verificationCode'],
			);
			$res = $allyun->confirmPayBackSms($confirm);

			if ($res['status'] == 'error') {
				$this->error_tips('提现失败', $res['message']);
			} else {
				$this->success_tips('提现成功',U('index'));
			}
		}else{
			$this->display();
		}
	}
	
	public function after_pay(){

		if($_GET['type']=='success'){
			$this->success_tips('支付成功',str_replace('&amp;','&',$_GET['redirctUrl']));
		}else{
			$this->error_tips('支付失败',str_replace('&amp;','&',$_GET['redirctUrl']));
		}

	}

}
?>