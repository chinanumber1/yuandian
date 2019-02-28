<?php
class LoginAction extends BaseAction{
	public function index(){
		if(IS_POST){
//			$login_result = D('User')->checkin($_POST['phone'],$_POST['password']);
			$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
			$pwd = isset($_POST['password']) ? $_POST['password'] : '';
			$count = M('User')->where(array('phone'=>$_POST['phone']))->count();
			if($count==1){
				$now_user = D('User')->get_user($_POST['phone'],'phone');
			}

			if(($now_user['phone_country_type']>0||$count>1 )&& $this->config['international_phone'] ){
				$login_result = D('User')->checkin($phone, $pwd,false,false,$_POST['phone_country_type']);
			}else{
				$login_result = D('User')->checkin($phone, $pwd);
			}
			if($login_result['error_code']){
				$this->error($login_result['msg']);
			}else{
				$now_user = $login_result['user'];
				session('user',$now_user);
                session('openid', $now_user['openid']);
				setcookie('login_name',$now_user['phone'],$_SERVER['REQUEST_TIME']+10000000,'/');
				$this->success('登录成功！');
			}
		}else{
			if(!empty($this->user_session)){
				if(cookie('is_house')){
					redirect(C('INDEP_HOUSE_URL'));
				}else{
					redirect(U('My/index'));
				}
			}

			if($_GET['referer']){
				$referer = htmlspecialchars_decode($_GET['referer']);
			}else{
				$referer = $_SERVER['HTTP_REFERER'];
			}

			$this->assign('referer',$referer);

			
			if($_SERVER['HTTP_MDDAPP'] && $this->config['mdd_api_url']){
				if(strpos($referer,'http') !== 0){
					$referer = $this->config['site_url'].$referer;
				}
				$this->assign('referer',$referer);
				$this->display('mdd_login');
				die;
			}
			
			if($this->config['mdd_site_url']){
				if(strpos($referer,'http') !== 0){
					$referer = $this->config['site_url'].$referer;
				}
				redirect($this->config['mdd_site_url'].'/index.php/Login/webLogin?historyUrl='.urlencode($referer));
			}
			
			
			if($this->is_wexin_browser){
				redirect(U('Login/weixin',array('referer'=>urlencode($referer))));exit;
			}
			if($this->is_alipay_browser && $this->config['alipay_app_id']){
				redirect(U('Login/alipay',array('referer'=>urlencode($referer))));exit;
			}
			$this->display();
		}
	}
	public function reg(){
		if(IS_POST){
			if(preg_match('/\d+\|/',$_POST['phone'])){
				$tmp_phone = explode('|',$_POST['phone']);
				$_POST['phone'] = $tmp_phone[1];
				$_POST['phone_country_type'] = $tmp_phone[0];
			}
			$condition_user['phone'] = $data_user['phone'] = trim($_POST['phone']);
			if($this->config['international_phone']){
				$condition_user['phone_country_type'] = trim($_POST['phone_country_type']);
			}

			$database_user = D('User');
			if($database_user->field('`uid`')->where($condition_user)->find()){
				$this->error('手机号已存在');
			}

			if(empty($data_user['phone'])){
				$this->error('请输入手机号');
			}else if(empty($_POST['password'])){
				$this->error('请输入密码');
			}

			if(!$this->config['international_phone'] && !preg_match('/^[0-9]{11}$/',$data_user['phone'])){
				$this->error('请输入有效的手机号');
			}
			if ($this->config['reg_verify_sms']&&$this->config['sms_key']&&substr($_POST['phone'],0,10)!='1321234567') {
				if($this->config['sms_verify_fleshcode']){
					if (empty($_POST['verify']) || empty($_POST['type']) || md5($_POST['verify']) != $_SESSION['user_'.$_POST['type'].'_verify']) {
						$this->error('非法验证');
					}
				}
				$sms_verify_result = D('Smscodeverify')->verify($_POST['sms_code'], $_POST['phone']);
				if ($sms_verify_result['error_code']) {
					$this->error($sms_verify_result['msg']);
				}else{
					$modifypwd = $sms_verify_result['modifypwd'];
				}
			}
			$data_user['pwd'] = md5($_POST['password']);

			$data_user['nickname'] = substr($data_user['phone'],0,3).'****'.substr($data_user['phone'],7);

			$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);

			/****判断此用户是否在user_import表中***/
			$user_importDb=D('User_import');
			$user_import=$user_importDb->where(array('telphone'=>$condition_user['phone'],'isuse'=>'0'))->find();
			if(!empty($user_import)){
			   $data_user['truename']=$user_import['ppname'];
			   $data_user['qq']=$user_import['qq'];
			   $data_user['email']=$user_import['email'];
			   $data_user['level']=$user_import['level'];
			   $data_user['score_count']=$user_import['integral'];
			   $data_user['now_money']=$user_import['money'] ? $user_import['money'] : 0;
			   $data_user['importid']=$user_import['id'];
			   $data_user['youaddress'] = !empty($user_import['address']) ? str_replace('|', ' ', $user_import['address']) : '';
				if($this->config['reg_verify_sms']){
					$data_user['status'] = 1; //开启注册验证短信就不需要审核
				}else{
					$data_user['status'] = 2; /*             * *未审核*** */

				}
			   // $data_user['now_money'] = 0;
			}
			$data_user['source'] = 'wap';
			$data_user['score_clean_time'] = $_SERVER['REQUEST_TIME'];
			$_POST['phone_country_type'] && $data_user['phone_country_type'] = $_POST['phone_country_type'];
			if($uid = $database_user->data($data_user)->add()){
				if( $this->config['open_score_fenrun'] && $_POST['spread_code']){
					if($spread_user = M('User')->where(array('spread_code'=>$_POST['spread_code']))->find()){
						$now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();
						D('User_spread')->data(array('spread_openid'=>$spread_user['openid'],'spread_uid'=>$spread_user['uid'],'openid'=>'','uid'=>$uid))->add();
						if ($this->config['spread_user_give_type'] == 1 || $this->config['spread_user_give_type'] == 2) {
							$spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level['spread_user_give_moeny']:$this->config['spread_give_money'];
							//D('User')->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额','','',$uid);
							$Res = D('Fenrun')->add_recommend_award($spread_user['uid'],$uid,1,$spread_give_money,'推荐新用户注册平台奖励佣金');

						}

					}
				}
				$database_house_village_user_bind = D('House_village_user_bind');
				$database_house_village_user_vacancy = D('House_village_user_vacancy');
				$find_village = D('House_village_user_bind')->where(array('phone'=>trim($_POST['phone'])))->select();
				if($find_village){
					foreach($find_village as $k=>$v){
						$data_vacancy = array();
						$dataInfo['uid'] = $uid;
						$database_house_village_user_bind->where(array('pigcms_id'=>$v['pigcms_id']))->data($dataInfo)->save();
						if($v['type']==0 && $v['vacancy_id']>0){
							$database_house_village_user_vacancy->where(array('pigcms_id'=>$v['vacancy_id']))->data($dataInfo)->save();		
						}	
					}	
				}
				D('User')->register_give_money($uid);
				// if ($this->config['register_give_money_condition'] == 2 || $this->config['register_give_money_condition'] == 4) {
				// 	if($this->config['register_give_money_type']==1 ||$this->config['register_give_money_type']==2 ){
				// 		D('User')->add_money($uid, $this->config['register_give_money'], '新用户注册平台赠送余额');
				// 		D('Scroll_msg')->add_msg('reg',$uid,'用户'.$data_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'注册成功获得赠送余额'.$this->config['register_give_money'].'元');

				// 	}
				// 	if($this->config['register_give_money_type']==0 ||$this->config['register_give_money_type']==2 ){
				// 		D('User')->add_score($uid,$this->config['register_give_score'], '新用户注册平台赠送'.$this->config['score_name']);
				// 		D('Scroll_msg')->add_msg('reg',$uid,'用户'.$data_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'注册成功获得赠送'.$this->config['score_name'].$this->config['register_give_score'].'个');
				// 	}
					
				// }
				$session['uid'] = $uid;
				$session['phone'] = $data_user['phone'];
				$session['nickname'] = $data_user['nickname'];
				session('user',$session);

				setcookie('login_name',$session['phone'],$_SERVER['REQUEST_TIME']+1000000,'/');
				if(!empty($user_import)){
				   $user_importDb->where(array('id'=>$user_import['id']))->save(array('isuse'=>2));
				}
				$this->success('注册成功');
			}else{
				$this->error('注册失败！请重试。');
			}
		}else{
			if(!empty($this->user_session)){
				redirect(U('My/index'));
			}
			$this->display();
		}
	}
    /*     * *****手机短信验证***** */

	//忘记密码
	public function forgetpwd() {
		$accphone = isset($_GET['accphone']) ? trim($_GET['accphone']) : '';
		$this->assign('accphone', $accphone);
		if($this->config['sms_key']){
			$this->display();
		}else{
			$this->error_tips('平台未开启短信验证功能，请联系系统管理员！');
		}
	}

	public function pwdModify() {
		$pm = trim($_GET['pm']);
		if (!empty($pm)) {
			$pm = str_replace(' ', '+', $pm);
			$tmpstr = Encryptioncode($pm, 'DECODE');
			$modfyinfo = json_decode(base64_decode($tmpstr), TRUE);
			if (!empty($modfyinfo)) {
				$phone = $modfyinfo['phone'];
				$tmp = session($phone . 'Generate_Pwd_Modify');
				if ($tmp) {
					$modifypwd = M('User_modifypwd')->where(array('id' => $modfyinfo['vfycode_id'], 'telphone' => $phone))->find();
					$nowtime = time();
					if ($modifypwd['expiry'] < $nowtime) {
						$this->error('链接时间已经过期失效了', U('Index/Login/index'));
						exit();
					}
					$this->assign('pm', $pm);
					$this->display();
					exit();
				}
			}
		}
		redirect(U('Wap/Login/index'));
	}

	public function pwdModifying() {
		$pm = trim($_GET['pm']);
		$newpwd = trim($_POST['newpwd']);
		$new_pwd = trim($_POST['new_pwd']);
		if ($newpwd != $new_pwd) {
			exit(json_encode(array('error_code' => 1, 'msg' => '两次密码输入不一样！')));
		}
		if (!empty($pm)) {
			$pm = str_replace(' ', '+', $pm);
			$tmpstr = Encryptioncode($pm, 'DECODE');
			$modfyinfo = json_decode(base64_decode($tmpstr), TRUE);
			if (!empty($modfyinfo)) {
				$phone = $modfyinfo['phone'];
				$tmp = session($phone . 'Generate_Pwd_Modify');
				if ($tmp) {
					if (M('User')->where(array('uid' => $modfyinfo['uid'], 'phone' => $phone))->save(array('pwd' => md5($newpwd)))) {
						session($phone . 'Generate_Pwd_Modify', null);
						exit(json_encode(array('error_code' => 0, 'msg' => '密码修改成功！')));
					} else {
						exit(json_encode(array('error_code' => 2, 'msg' => '密码修改失败！')));
					}
				}
			}
		}
		//exit(json_encode(array('error_code' => 2, 'msg' => '参数出错！')));
	}

    public function SmsCodeverify() {
		$tphone = $_POST['phone'];
        $user_modifypwdDb = M('User_modifypwd');
        if (isset($_POST['vcode']) && !empty($_POST['vcode'])) {
            $vfycode = trim($_POST['vcode']);
            $modifypwd = $user_modifypwdDb->where(array('vfcode' => $vfycode, 'telphone' => $tphone))->find();
            if (!empty($modifypwd)) {
                $nowtime = time();
                if ($modifypwd['expiry'] > $nowtime) {
                    return true;
                }
            }
            $this->error('验证码失效了，务必在20分钟内完成验证');
            exit();
        } else {
            $vcode = createRandomStr(6, true, true);
            $content = '您的验证码是：'. $vcode . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
            Sms::sendSms(array('mer_id' => 0, 'store_id' => 0, 'content' => $content, 'mobile' => $tphone, 'uid' => 0, 'type' => 'regvfy'));
            $addtime = time();
            $expiry = $addtime + 20 * 60; /*             * **二十分钟有效期*** */
            $data = array('telphone' => $tphone, 'vfcode' => $vcode, 'expiry' => $expiry, 'addtime' => $addtime);
            $insert_id = $user_modifypwdDb->add($data);
            $this->error('vfcode');
            exit();
        }
    }
	public function logout(){
		session('user',null);
		session('openid',null);
		session('now_village_bind',null);
		redirect(U('Home/index'));
	}
	public function alipay(){
		$_SESSION['alipay']['referer'] = !empty($_GET['referer']) ? htmlspecialchars_decode($_GET['referer']) : U('Home/index');
		$customeUrl = $this->config['site_url'].'/wap.php?c=Login&a=alipay_back';
		$oauthUrl = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id='.$this->config['alipay_app_id'].'&scope=auth_userinfo&redirect_uri='.urlencode($customeUrl);
		redirect($oauthUrl);
	}
	public function alipay_back(){
		$referer = !empty($_SESSION['alipay']['referer']) ? $_SESSION['alipay']['referer'] : U('Home/index');
		
		$param = array();
		$param['app_id'] = $this->config['alipay_app_id'];
		$param['method'] = 'alipay.system.oauth.token';
		$param['charset'] = 'utf-8';
		$param['sign_type'] = $this->config['alipay_app_encrypt_type'] ? $this->config['alipay_app_encrypt_type'] : 'RSA';
		$param['timestamp'] = date('Y-m-d H:i:s');
		$param['version'] = '1.0';
		$param['grant_type'] = 'authorization_code';
		$param['code'] = $_GET['auth_code'];
		
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

		$priKey = $this->config['alipay_app_prikey'];
		$res = "-----BEGIN RSA PRIVATE KEY-----\n".wordwrap($priKey, 64, "\n", true)."\n-----END RSA PRIVATE KEY-----";

		if(!function_exists('openssl_sign')){
			$this->error_tips('系统不支持 openssl_sign');
		}
		
		if($param['sign_type'] == 'RSA2'){
			openssl_sign($stringToBeSigned, $sign, $res, OPENSSL_ALGO_SHA256);
		}else{
			openssl_sign($stringToBeSigned, $sign, $res);
		}
			
		if(empty($sign)){
			$this->error_tips('支付宝密钥错误');
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
		
		if($returnArr['alipay_system_oauth_token_response']){
			//如果存在即 自动登录
			$this->autologin('alipay_uid',$returnArr['alipay_system_oauth_token_response']['user_id'],$referer);
			

			$param = array();
			$param['app_id'] = $this->config['alipay_app_id'];
			$param['method'] = 'alipay.user.userinfo.share';
			$param['charset'] = 'utf-8';
			$param['sign_type'] = $this->config['alipay_app_encrypt_type'] ? $this->config['alipay_app_encrypt_type'] : 'RSA';
			$param['timestamp'] = date('Y-m-d H:i:s');
			$param['version'] = '1.0';
			$param['auth_token'] = $returnArr['alipay_system_oauth_token_response']['access_token'];
			
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

			$priKey = $this->config['alipay_app_prikey'];
			$res = "-----BEGIN RSA PRIVATE KEY-----\n".wordwrap($priKey, 64, "\n", true)."\n-----END RSA PRIVATE KEY-----";

			if(!function_exists('openssl_sign')){
				$this->error_tips('系统不支持 openssl_sign');
			}

			if($param['sign_type'] == 'RSA2'){
				openssl_sign($stringToBeSigned, $sign, $res, OPENSSL_ALGO_SHA256);
			}else{
				openssl_sign($stringToBeSigned, $sign, $res);
			}
			
			if(empty($sign)){
				$this->error_tips('支付宝密钥错误');
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
			// dump($param);
			// dump($returnArr);
			
			if($returnArr['error_response']){
				$this->error_tips('支付宝授权提示：'.$returnArr['error_response']['sub_msg']);
			}

			/*注册用户*/
			$jsonrt = $returnArr['alipay_user_userinfo_share_response'];
			// dump($jsonrt);die;
			$data_user = array(
				'alipay_uid'=> $jsonrt['alipay_user_id'],
				'nickname' 	=> $jsonrt['nick_name'] ? $jsonrt['nick_name'] : '支付宝用户',
				'sex' 		=> $jsonrt['gender'] == 'm' ? 1 : 2,
				'province' 	=> rtrim($jsonrt['province'],'省'),
				'city' 		=> rtrim($jsonrt['city'],'市'),
				'avatar' 	=> $jsonrt['avatar'] ? $jsonrt['avatar'] : '',
			);
			$_SESSION['alipay']['user'] = $data_user;
			$this->assign('referer',$referer);
			if($this->config['alipay_login_bind']){
				$this->display();
			}else{
				redirect(U('Login/alipay_nobind'));
			}
		}else if($returnArr['error_response']){
			$this->error_tips('支付宝授权提示：'.$returnArr['error_response']['sub_msg']);
		}
	}
	public function alipay_nobind(){
		if(empty($_SESSION['alipay']['user'])){
			$this->error('支付宝授权失效，请重新登录！');
		}
		$_SESSION['alipay']['user']['source'] = 'alipay_nobind_reg';
		$reg_result = D('User')->autoreg($_SESSION['alipay']['user']);
		if($reg_result['error_code']){
			$this->error_tips($reg_result['msg']);
		}else{
			$login_result = D('User')->autologin('alipay_uid',$_SESSION['alipay']['user']['alipay_uid']);
			if($login_result['error_code']){
				$this->error_tips($login_result['msg'],U('Login/index'));
			}else{
				$now_user = $login_result['user'];
				session('user',$now_user);
				$referer = !empty($_SESSION['alipay']['referer']) ? $_SESSION['alipay']['referer'] : U('Home/index');

				unset($_SESSION['alipay']);
				redirect($referer);
				exit;
			}
		}
	}
	public function weixin(){
		$_SESSION['weixin']['referer'] = !empty($_GET['referer']) ? htmlspecialchars_decode($_GET['referer']) : U('Home/index');
		$_SESSION['weixin']['state']   = md5(uniqid());

		$customeUrl = $this->config['site_url'].'/wap.php?c=Login&a=weixin_back';
		$oauthUrl='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->config['wechat_appid'].'&redirect_uri='.urlencode($customeUrl).'&response_type=code&scope=snsapi_userinfo&state='.$_SESSION['weixin']['state'].'#wechat_redirect';
		redirect($oauthUrl);
	}
	public function weixin_back(){
		$referer = !empty($_SESSION['weixin']['referer']) ? $_SESSION['weixin']['referer'] : U('Home/index');

		// if (isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == $_SESSION['weixin']['state'])){
		if (isset($_GET['code'])){
			unset($_SESSION['weixin']['state']);
			import('ORG.Net.Http');
			$http = new Http();
			$return = $http->curlGet('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->config['wechat_appid'].'&secret='.$this->config['wechat_appsecret'].'&code='.$_GET['code'].'&grant_type=authorization_code');
			$jsonrt = json_decode($return,true);
			if($jsonrt['errcode']){
				$error_msg_class = new GetErrorMsg();
				$this->error_tips('授权发生错误：'.$error_msg_class->wx_error_msg($jsonrt['errcode']),U('Login/index'));
			}

			$return = $http->curlGet('https://api.weixin.qq.com/sns/userinfo?access_token='.$jsonrt['access_token'].'&openid='.$jsonrt['openid'].'&lang=zh_CN');

			$jsonrt = json_decode($return,true);
			if ($jsonrt['errcode']) {
				$error_msg_class = new GetErrorMsg();
				$this->error_tips('授权发生错误：'.$error_msg_class->wx_error_msg($jsonrt['errcode']),U('Login/index'));
			}
			$is_follow = 0;
			$access_token_array = D('Access_token_expires')->get_access_token();
			if (!$access_token_array['errcode']) {
				$return = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_array['access_token'].'&openid='.$jsonrt['openid'].'&lang=zh_CN');
				$userifo = json_decode($return,true);
				$is_follow = $userifo['subscribe'];
			}

			if(!empty($this->user_session)){
				$data_user = array(
					'union_id' 	=> ($jsonrt['unionid'] ? $jsonrt['unionid'] : ''),
					'sex' 		=> $jsonrt['sex'],
					'nickname' 	=> $jsonrt['nickname'],
					'province' 	=> $jsonrt['province'],
					'city' 		=> $jsonrt['city'],
					'avatar' 	=> $jsonrt['headimgurl'],
					'last_weixin_time' 	=> $_SERVER['REQUEST_TIME'],
// 					'is_follow' 	=> $is_follow,
				);
				D('User')->where(array('uid'=>$this->user_session['uid']))->data($data_user)->save();
				redirect($referer);
			}else{
				/*优先使用 unionid 登录*/
				if(!empty($jsonrt['unionid'])){
					$this->autologin('union_id',$jsonrt['unionid'],$referer);
				}else{
					/*再次使用 openid 登录*/
					$this->autologin('openid',$jsonrt['openid'],$referer);
				}

				/*注册用户*/
				$data_user = array(
					'openid' 	=> $jsonrt['openid'],
					'union_id' 	=> ($jsonrt['unionid'] ? $jsonrt['unionid'] : ''),
					'nickname' 	=> $jsonrt['nickname'],
					'sex' 		=> $jsonrt['sex'],
					'province' 	=> $jsonrt['province'],
					'city' 		=> $jsonrt['city'],
					'avatar' 	=> $jsonrt['headimgurl'],
					'is_follow' 	=> $is_follow,
				);
				$_SESSION['weixin']['user'] = $data_user;
				$this->assign('referer',$referer);
				if($this->config['weixin_login_bind']){
					$this->display();
				}else{
					redirect(U('Login/weixin_nobind'));
				}
			}
		}else{
			$this->error_tips('访问异常！请重新登录。',U('Login/index',array('referer'=>urlencode($referer))));
		}
	}
	public function weixin_bind(){
		if(empty($_SESSION['weixin']['user'])){
			$this->error('微信授权失效，请重新登录！');
		}
		$login_result = D('User')->checkin($_POST['phone'],$_POST['password']);
		if($login_result['error_code']){
			$this->error($login_result['msg']);
		}else{
			$now_user = $login_result['user'];
			$condition_user['uid'] = $now_user['uid'];
			$data_user['openid'] = $_SESSION['weixin']['user']['openid'];
			$data_user['last_weixin_time'] = $_SERVER['REQUEST_TIME'];
			if($_SESSION['weixin']['user']['union_id']){
				$data_user['union_id'] 	= $_SESSION['weixin']['user']['union_id'];
			}
			if(empty($now_user['avatar'])){
				$data_user['avatar'] 	= $_SESSION['weixin']['user']['avatar'];
			}
			if(empty($now_user['sex'])){
				$data_user['sex']		= $_SESSION['weixin']['user']['sex'];
			}
			if(empty($now_user['province'])){
				$data_user['province'] 	= $_SESSION['weixin']['user']['province'];
			}
			if(empty($now_user['city'])){
				$data_user['city'] 		= $_SESSION['weixin']['user']['city'];
			}
			/****判断此用户是否在user_import表中***/
			$user_importDb=D('User_import');
			$user_import=$user_importDb->where(array('telphone'=>$condition_user['phone']))->find();
			if(!empty($user_import)){
			 if($user_import['isuse']==0){
			   $data_user['truename']=$user_import['ppname'];
			   $data_user['qq']=$user_import['qq'];
			   $data_user['email']=$user_import['email'];
			   $data_user['level']=$user_import['level'];
			   $data_user['score_count']=$user_import['integral'];
			   $data_user['now_money']=$user_import['money'];
			   $data_user['importid']=$user_import['id'];
 			   $data_user['youaddress'] = !empty($user_import['address']) ? str_replace('|', ' ', $user_import['address']) : '';
			 }
				if($this->config['reg_verify_sms']){
					$data_user['status'] = 1; //开启注册验证短信就不需要审核
				}else{
					$data_user['status'] = 2; /*             * *未审核*** */

				}
			   $mer_id=$user_import['mer_id'];
			   if($mer_id>0){
			      $merchant_user_relationDb=M('Merchant_user_relation');
				  $mwhere=array('openid'=>$data_user['openid'],'mer_id'=>$mer_id);
				  $mtmp=$merchant_user_relationDb->where($mwhere)->find();
				  if(empty($mtmp)){
					 $mwhere['dateline']=time();
					 $mwhere['from_merchant']=3;
				     $merchant_user_relationDb->add($mwhere);
				  }
			   }
			}
			if(D('User')->where($condition_user)->data($data_user)->save()){
				unset($_SESSION['weixin']);
				session('user',$now_user);
				setcookie('login_name',$now_user['phone'],$_SERVER['REQUEST_TIME']+10000000,'/');
				if(!empty($user_import)){
				   $user_importDb->where(array('id'=>$user_import['id']))->save(array('isuse'=>1));
				}
				$this->success('登录成功！');
			}else{
				$this->error('绑定失败！请重试。');
			}
		}
	}
	public function weixin_bind_reg(){
		if(IS_POST){
			if(preg_match('/\d+\|/',$_POST['phone'])){
				$tmp_phone = explode('|',$_POST['phone']);
				$_POST['phone'] = $tmp_phone[1];
				$_POST['phone_country_type'] = $tmp_phone[0];
			}
			if(empty($_SESSION['weixin']['user'])){
				$this->error('微信授权失效，请重新登录！');
			}

			$database_user = D('User');
			$condition_user['phone'] = $data_user['phone'] = trim($_POST['phone']);

			if(empty($data_user['phone'])){
				$this->error('请输入手机号');
			}else if(!$this->config['international_phone'] && !preg_match('/^[0-9]{11}$/',$data_user['phone'])){
				$this->error('请输入有效的手机号');
			}else if(empty($_POST['password'])){
				$this->error('请输入密码');
			}

			if($database_user->field('`uid`')->where($condition_user)->find()){
				$this->error('手机号已存在');
			}


			$where['openid'] = trim($_POST['openid']);
			if($database_user->field('`uid`')->where($where)->find()){
				$this->error('-1');
			}

			//技术测试号码
			if ($this->config['reg_verify_sms']&&$this->config['sms_key']&&substr($data_user['phone'],0,10)!='1321234567') {
				if($this->config['sms_verify_fleshcode']){
					if (md5($_POST['verify']) != $_SESSION['user_'.$_POST['type'].'_verify']) {
						$this->error('非法验证');
					}
				}
				$sms_verify_result = D('Smscodeverify')->verify($_POST['sms_code'], $_POST['phone']);
				if ($sms_verify_result['error_code']) {
					$this->error($sms_verify_result['msg']);
				}else{
					$modifypwd = $sms_verify_result['modifypwd'];
				}
			}

			$data_user['pwd'] = md5($_POST['password']);

			$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);

//			if($nickname == $this->config['site_name']){
//       			$data_user['nickname']  = '昵称';
//			}else{
//        		$data_user['nickname'] = $_SESSION['weixin']['user']['nickname'];
//			}
			$data_user['nickname'] = $_SESSION['weixin']['user']['nickname'];
			$data_user['openid'] = $_SESSION['weixin']['user']['openid'];
			if($_SESSION['weixin']['user']['union_id']){
				$data_user['union_id'] 	= $_SESSION['weixin']['user']['union_id'];
			}
			$data_user['avatar'] 	= $_SESSION['weixin']['user']['avatar'];
			$data_user['sex']		= $_SESSION['weixin']['user']['sex'];
			$data_user['province'] 	= $_SESSION['weixin']['user']['province'];
			$data_user['city'] 		= $_SESSION['weixin']['user']['city'];
			$data_user['last_weixin_time'] = $_SERVER['REQUEST_TIME'];

			$_POST['phone_country_type'] && $data_user['phone_country_type'] = $_POST['phone_country_type'];

			/****判断此用户是否在user_import表中***/
			$user_importDb=D('User_import');
			$user_import=$user_importDb->where(array('telphone'=>$condition_user['phone'],'isuse'=>'0'))->find();
			if(!empty($user_import)){
			   $data_user['truename']=$user_import['ppname'];
			   $data_user['qq']=$user_import['qq'];
			   $data_user['email']=$user_import['email'];
			   $data_user['level']=$user_import['level'];
			   $data_user['score_count']=$user_import['integral'];
			   $data_user['now_money']=$user_import['money'] ? $user_import['money'] : 0;;
			   $data_user['importid']=$user_import['id'];
			   $data_user['youaddress'] = !empty($user_import['address']) ? str_replace('|', ' ', $user_import['address']) : '';
				if($this->config['reg_verify_sms']){
					$data_user['status'] = 1; //开启注册验证短信就不需要审核
				}else{
					$data_user['status'] = 2; /*             * *未审核*** */

				}
			   $mer_id=$user_import['mer_id'];
			   if($mer_id>0){
			      $merchant_user_relationDb=M('Merchant_user_relation');
				  $mwhere=array('openid'=>$data_user['openid'],'mer_id'=>$mer_id);
				  $mtmp=$merchant_user_relationDb->where($mwhere)->find();
				  if(empty($mtmp)){
					 $mwhere['dateline']=time();
					 $mwhere['from_merchant']=3;
				     $merchant_user_relationDb->add($mwhere);
				  }
			   }
			}
			$data_user['source'] = 'weixin_bind_reg';
			$data_user['score_clean_time'] = $_SERVER['REQUEST_TIME'];
			if($uid = $database_user->data($data_user)->add()){
				if( $this->config['open_score_fenrun'] && $_POST['spread_code']){
					if($spread_user = M('User')->where(array('spread_code'=>$_POST['spread_code']))->find()){
						$now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();
						if(D('User_spread')->where(array('spread_openid'=>$spread_user['openid'],'openid'=>$data_user['openid']))->find()){
							D('User_spread')->where(array('spread_openid'=>$spread_user['openid'],'openid'=>$data_user['openid']))->setField('uid',$uid);
						}else{
							D('User_spread')->data(array('spread_openid'=>$spread_user['openid'],'spread_uid'=>$spread_user['uid'],'openid'=>'','uid'=>$uid))->add();
						}
						if ($this->config['spread_user_give_type'] == 1 || $this->config['spread_user_give_type'] == 2) {
							$spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level['spread_user_give_moeny']:$this->config['spread_give_money'];
							//D('User')->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额','','',$uid);
							$Res = D('Fenrun')->add_recommend_award($spread_user['uid'],$uid,1,$spread_give_money,'推荐新用户注册平台奖励佣金');

						}

					}
				}
				D('User')->register_give_money($uid);

				// if ($this->config['register_give_money_condition'] == 2 || $this->config['register_give_money_condition'] == 4) {
				// 	if($this->config['register_give_money_type']==1 ||$this->config['register_give_money_type']==2 ){
				// 		D('User')->add_money($uid, $this->config['register_give_money'], '新用户注册平台赠送余额');
				// 		D('Scroll_msg')->add_msg('reg',$uid,'用户'.$data_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'注册成功获得赠送余额'.$this->config['register_give_money'].'元');

				// 	}
				// 	if($this->config['register_give_money_type']==0 ||$this->config['register_give_money_type']==2 ){
				// 		D('User')->add_score($uid,$this->config['register_give_score'], '新用户注册平台赠送'.$this->config['score_name']);
				// 		D('Scroll_msg')->add_msg('reg',$uid,'用户'.$data_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'注册成功获得赠送'.$this->config['score_name'].$this->config['register_give_score'].'个');

				// 	}
				// }

				if($this->config['spread_user_give_type']!=3&&!empty($data_user['openid']) && !$this->config['open_score_fenrun']){
					$now_user_spread = D('User_spread')->field('`spread_openid`, `openid`')->where(array('openid'=>$data_user['openid']))->find();
					if($now_user_spread) {
						$spread_user = D('User')->get_user($now_user_spread['spread_openid'],'openid');
						$now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();

						if ($this->config['spread_user_give_type'] == 0 || $this->config['spread_user_give_type'] == 2) {
							$spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level:$this->config['spread_give_money'];

							D('User')->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额');

							D('Scroll_msg')->add_msg('spread_reg',$this->now_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠平台余额'.$spread_give_money.'元');
						}

						if ($this->config['spread_user_give_type'] == 1 || $this->config['spread_user_give_type'] == 2) {
							$spread_give_score = $now_level['spread_user_give_score']>0?$now_level:$this->config['spread_give_score'];
							D('User')->add_score($spread_user['uid'], $spread_give_score, '推荐新用户注册平台赠送' . $this->config['score_name']);
							D('Scroll_msg')->add_msg('spread_reg',$this->now_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠'.$this->config['score_name'].$spread_give_score.'个');
						}
					}

				}
				$session['uid'] = $uid;
				$session['phone'] = $data_user['phone'];
				$session['nickname'] = $data_user['nickname'];
				$session['sex'] = $data_user['sex'];
				session('user',$session);

				setcookie('login_name',$session['phone'],$_SERVER['REQUEST_TIME']+1000000,'/');
				if(!empty($user_import)){
				   $user_importDb->where(array('id'=>$user_import['id']))->save(array('isuse'=>1));
				}
				$this->success('注册成功');
			}else{
				$this->error('注册失败！请重试。');
			}
		}
	}
	public function weixin_nobind(){
		if(empty($_SESSION['weixin']['user'])){
			$this->error('微信授权失效，请重新登录！');
		}
		$_SESSION['weixin']['user']['source'] = 'weixin_nobind_reg';
		$reg_result = D('User')->autoreg($_SESSION['weixin']['user']);
		if($reg_result['error_code']){
			$this->error_tips($reg_result['msg'],U('Home/index'));
		}else{
			$login_result = D('User')->autologin('openid',$_SESSION['weixin']['user']['openid']);
			if($login_result['error_code']){
				$this->error_tips($login_result['msg'],U('Login/index'));
			}else{
				$now_user = $login_result['user'];
				session('user',$now_user);
				$referer = !empty($_SESSION['weixin']['referer']) ? $_SESSION['weixin']['referer'] : U('Home/index');

				unset($_SESSION['weixin']);
				redirect($referer);
				exit;
			}
		}
	}
	protected function autologin($field,$value,$referer){
		$result = D('User')->autologin($field,$value);
		if(empty($result['error_code'])){
			if($field == 'union_id' && empty($result['user']['openid']) && !empty($_SESSION['openid'])){
				$condition_user['union_id'] = $value;
				D('User')->where($condition_user)->data(array('openid'=>$_SESSION['openid']))->save();
			}
			$now_user = $result['user'];
			session('user',$now_user);
			redirect($referer);
			exit;
		}else if($result['error_code'] && $result['error_code'] != 1001){
			$this->error_tips($result['msg'],U('Login/index'));
		}
	}

    public function frame_login() {
        $pigcms_assign['referer'] = !empty($_GET['referer']) ? strip_tags($_GET['referer']) : (!empty($_SERVER['HTTP_REFERER']) ? strip_tags($_SERVER['HTTP_REFERER']) : U('Index/Index/index'));
        $pigcms_assign['url_referer'] = urlencode($pigcms_assign['referer']);
        $this->assign($pigcms_assign);

        $this->display();
    }

    public function login()
    {
    	$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    	$pwd = isset($_POST['pwd']) ? $_POST['pwd'] : '';
		$count = M('User')->where(array('phone'=>$_POST['phone']))->count();
		if($count==1){
			$now_user = D('User')->get_user($_POST['phone'],'phone');
		}

		if(($now_user['phone_country_type']>0||$count>1 )&& $this->config['international_phone'] ){
			$result = D('User')->checkin($phone, $pwd,false,false,$_POST['phone_country_type']);
		}else{
			echo 1;
			$result = D('User')->checkin($phone, $pwd);
		}
    	if (empty($result['error_code'])) {
    		session('user', $result['user']);
    		session('openid', $result['user']['openid']);
    	}
    	exit(json_encode($result));
    }

	public function see_login_qrcode(){
		$qrcode_return = D('Recognition')->get_login_qrcode();
		if($qrcode_return['error_code']){
			echo '<html><head></head><body>'.$qrcode_return['msg'].'<br/><br/><font color="red">请关闭此窗口再打开重试。</font></body></html>';
		}else{
			$this->assign($qrcode_return);
			$this->display();
		}
	}

    public function ajax_weixin_login() {
        for ($i = 0; $i < 6; $i++) {
            $database_login_qrcode = D('Login_qrcode');
            $condition_login_qrcode['id'] = $_GET['qrcode_id'];
            $now_qrcode = $database_login_qrcode->field('`uid`')->where($condition_login_qrcode)->find();
            if (!empty($now_qrcode['uid'])) {
                if ($now_qrcode['uid'] == -1) {
                    $data_login_qrcode['uid'] = 0;
                    $database_login_qrcode->where($condition_login_qrcode)->data($data_login_qrcode)->save();
                    exit('reg_user');
                }
                $database_login_qrcode->where($condition_login_qrcode)->delete();
                $result = D('User')->autologin('uid', $now_qrcode['uid']);
                if (empty($result['error_code'])) {
                    session('user', $result['user']);
                    exit('true');
                } else if ($result['error_code'] == 1001) {
                    exit('no_user');
                } else if ($result['error_code']) {
                    exit('false');
                }
            }
            if ($i == 5) {
                exit('false');
            }
            sleep(3);
        }
    }

	public function register_agreement(){
		$content = $this->config['register_agreement'];
		$this->assign('content',$content);
		$this->display();
	}
}

?>