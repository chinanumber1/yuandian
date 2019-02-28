<?php
/*
 * 商户登录
 *
 */

class LoginAction extends BaseAction
{

    public function index()
    {
        if (trim($_SERVER['SCRIPT_NAME'], '/') == 'store.php') {
            header('Location: ' . U('Store/login'));
            exit();
        }
        $this->display();
    }

	public function sendsms(){


		if(empty($_POST['phone'])){
			$this->error('请输入手机号');
		}
		$now_merchant = M('Merchant')->where(array('phone'=>$_POST['phone']))->find();
		if(!empty($now_merchant)){
			$this->error('该手机号已注册商家');
		}


		//防止1分钟内多次发送短信
		$laste_sms = M('Merchant_sms_record')->where(array('phone'=>$_POST['phone'],'type'=>1))->order('pigcms_id DESC')->find();
		if(time()-$laste_sms['send_time']<60){
			$this->error('一分钟内不能多次发送短信');
		}
		$phone =$_POST['phone'];
		$code = mt_rand(1000, 9999);


		$text = '您的验证码是：' . $code . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';


		$columns = array();
		$columns['phone'] = $phone;

		$columns['extra'] = $code;
		$columns['type'] = 1;

		$columns['mer_id'] = 0;
		$columns['status'] = 0;
		$columns['send_time'] = time();
		$columns['expire_time'] = $columns['send_time'] + 72000;
		$result = M("Merchant_sms_record")->add($columns);
		if (!$result){
			$this->error('短信数据写入失败');
		}
		$sms_data = array('mer_id' => 0, 'store_id' => 0, 'content' => $text, 'mobile' => $phone, 'uid' => 0, 'type' => 'merchant_pwd');
		$_POST['phone_country_type'] && $sms_data['nationCode']  = $_POST['phone_country_type'];
		$return = Sms::sendSms($sms_data);
		if ($result != 0) {
			$this->error(self::ConverSmsCode($return));
		}
		$this->error(0, $return);
	}


	public function check(){
		if($this->isAjax()){
			if(md5($_POST['verify']) != $_SESSION['merchant_login_verify']){
				exit(json_encode(array('error'=>'1','msg'=>'验证码不正确！','dom_id'=>'verify')));
			}
			
			$database_merchant = D('Merchant');
			$condition_merchant['account'] = trim($_POST['account']);
			$now_merchant = $database_merchant->field(true)->where($condition_merchant)->find();
			if(empty($now_merchant)){
				exit(json_encode(array('error'=>'2','msg'=>'用户名不存在！','dom_id'=>'account')));
			}
			$pwd = md5($_POST['pwd']);
			if($pwd != $now_merchant['pwd']){
				exit(json_encode(array('error'=>'3','msg'=>'密码错误！','dom_id'=>'pwd')));
			}
			if(!empty($now_merchant['merchant_end_time']) && $now_merchant['merchant_end_time'] < $_SERVER['REQUEST_TIME']){
				$data_merchant['mer_id'] = $now_merchant['mer_id'];
				$data_merchant['status'] = '0';
				$database_merchant->data($data_merchant)->save();
				exit(json_encode(array('error'=>'7','msg'=>'您的帐号已经过期！请联系工作人员获得详细帮助。','dom_id'=>'account')));
			}
			if($now_merchant['status'] == 0){
				exit(json_encode(array('error'=>'4','msg'=>'您被禁止登录！请联系工作人员获得详细帮助。','dom_id'=>'account')));
			}else if($now_merchant['status'] == 2){
				exit(json_encode(array('error'=>'5','msg'=>'您的帐号正在审核中，请耐心等待或联系工作人员审核。','dom_id'=>'account')));
			}else if($now_merchant['status'] == 4){
				exit(json_encode(array('error'=>'6','msg'=>'您的帐号已经被永久删除，无法使用。','dom_id'=>'account')));
			}
			
			$data_merchant['mer_id'] = $now_merchant['mer_id'];
			$data_merchant['last_ip'] = get_client_ip(1);
			$data_merchant['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_merchant['login_count'] = $now_merchant['login_count']+1;
			if($database_merchant->data($data_merchant)->save()){
				$now_merchant['login_count'] += 1;
				
				if(!empty($now_merchant['last_ip'])){
					import('ORG.Net.IpLocation');
					$IpLocation = new IpLocation();
					$last_location = $IpLocation->getlocation(long2ip($now_merchant['last_ip']));
					$now_merchant['last']['country'] = mb_convert_encoding($last_location['country'],'UTF-8','GBK');
					$now_merchant['last']['area'] = mb_convert_encoding($last_location['area'],'UTF-8','GBK');
				}
				session('merchant',$now_merchant);

				if($now_merchant['status']==3){
					$remark = '您的账户处于欠费状态，您的商家业务已经被关闭，请及时充值，充值后将恢复业务';
				}else{
					$remark ='登录成功,现在跳转~';
				}
				exit(json_encode(array('error'=>'0','msg'=>$remark,'dom_id'=>'account')));
			}else{
				exit(json_encode(array('error'=>'6','msg'=>'登录信息保存失败,请重试！','dom_id'=>'account')));
			}
		}else{
			exit('deney Access !');
		}
	}
	public function reg_check(){
		if($this->isAjax()){
			if(md5($_POST['verify']) != $_SESSION['merchant_reg_verify']){
				exit(json_encode(array('error'=>'1','msg'=>'验证码不正确！','dom_id'=>'verify')));
			}

			if ($this->config['reg_verify_sms']&&$this->config['sms_key']&&$this->config['open_merchant_reg_sms']) {
				$laste_sms = M('Merchant_sms_record')->where(array('mer_id' => $this->mer_id))->order('pigcms_id DESC')->find();
				if (time() - $laste_sms['send_time'] < 60) {
					exit(json_encode(array('error'=>'1','msg'=>'短信验证码不正确','dom_id'=>'verify')));
				}
			}

			$_POST['account'] = trim($_POST['account']);
			//帐号
			$database_merchant = D('Merchant');
			$condition_merchant_account['account'] = trim($_POST['account']);
			$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant_account)->find();
			if(!empty($now_merchant)){
				exit(json_encode(array('error'=>'2','msg'=>'帐号已经存在！','dom_id'=>'account')));
			}
			//手机作为帐号
			$database_merchant = D('Merchant');
			$condition_merchant_account_phone['phone'] = trim($_POST['account']);
			$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant_account_phone)->find();
			if(!empty($now_merchant)){
				exit(json_encode(array('error'=>'2','msg'=>'该账号作为手机号已经存在，不允许重复','dom_id'=>'account')));
			}
			
			
			//名称
			$condition_merchant_name['name'] = $_POST['name'];
			$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant_name)->find();
			if(!empty($now_merchant)){
				exit(json_encode(array('error'=>'3','msg'=>'商家名称已经存在！','dom_id'=>'email')));
			}
			
			//邮箱
			if(!empty($_POST['email'])){
				$condition_merchant_email['email'] = $_POST['email'];
				$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant_email)->find();
				if(!empty($now_merchant)){
					exit(json_encode(array('error'=>'4','msg'=>'邮箱已经存在！','dom_id'=>'email')));
				}
			}

			//邀请码
			if($this->config['open_admin_code']==1){
				if(!M('Admin')->where(array('invit_code'=>$_POST['invit_code']))->find()){
					exit(json_encode(array('error'=>'5','msg'=>'邀请码不存在！','dom_id'=>'invit_code')));
				}
			}


			//手机号
			$condition_merchant_phone['phone'] = $_POST['phone'];
			$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant_phone)->find();
			if(!empty($now_merchant)){
				exit(json_encode(array('error'=>'5','msg'=>'手机号已经存在！','dom_id'=>'phone')));
			}
			
			//账号作为手机号
			$condition_merchant_phone_account['phone'] = $_POST['phone'];
			$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant_phone_account)->find();
			if(!empty($now_merchant)){
				exit(json_encode(array('error'=>'5','msg'=>'该手机号作为账号已经存在，不允许重复','dom_id'=>'phone')));
			}
			
//			$config = D('Config')->get_config();
//			$this->assign('config',$config);
			
			$_POST['mer_id'] = null;
			if($this->config['merchant_verify']){
				$_POST['status'] = 2;
			}else{

				$_POST['status'] = 1;
			}

			
			$_POST['pwd'] = md5($_POST['pwd']);
			$_POST['reg_ip'] = get_client_ip(1);
			$_POST['reg_time'] = $_SERVER['REQUEST_TIME'];
			$_POST['login_count'] = 0;
			$_POST['reg_from'] = 0;

			if($insert_id=$database_merchant->data($_POST)->add()){
				M('Merchant_score')->add(array('parent_id'=>$insert_id,'type'=>1));
				D('Scroll_msg')->add_msg('mer_reg',$insert_id,'商家'.$_POST['name'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '注册成功！');
				if (0 == $this->config['merchant_verify'] && $this->config['open_distributor']==1 && $_POST['spread_code']) {
					D('Distributor_agent')->agent_spread_log($insert_id);
				}

				if($this->config['merchant_verify']){
					exit(json_encode(array('error'=>'0','msg'=>'注册成功,请耐心等待审核或联系工作人员审核。~','dom_id'=>'account')));
				}else{
					exit(json_encode(array('error'=>'0','msg'=>'注册成功,请登录~','dom_id'=>'account')));
				}

				D('Merchant')->reg_notice($insert_id);
			}else{
				exit(json_encode(array('error'=>'6','msg'=>'注册失败,请重试！','dom_id'=>'account')));
			}
		}else{
			exit('deney Access !');
		}
	}
	public function logout(){
		session('merchant',null);
		header('Location: '.U('Login/index'));
	}
	public function verify(){
		$verify_type = $_GET['type'];
		if(empty($verify_type)){exit;}
		import('ORG.Util.Image');
		Image::buildImageVerify(4,1,'jpeg',53,26,'merchant_'.$verify_type.'_verify');
	}

	//错误代码
	static private function ConverSmsCode($smscode) {
		$errCode = array(
				'2'    => '20060001',
				'400'  => '20060002',
				'401'  => '20060003',
				'402'  => '20060004',
				'403'  => '20060005',
				'4030' => '20060006',
				'404'  => '20060007',
				'405'  => '20060008',
				'4050' => '20060009',
				'4051' => '20060010',
				'4052' => '20060011',
				'406'  => '20060012',
				'407'  => '20060013',
				'4070' => '20060014',
				'4071' => '20060015',
				'4072' => '20060016',
				'4073' => '20060017',
				'408'  => '20060018',
				'4085' => '20060019',
				'4084' => '20060020',
		);
		return $errCode[$smscode];
	}
	
	
}