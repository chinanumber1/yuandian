<?php
class SmssendAction extends BaseAction{
	public function sms_send() {
		return false;
		$user_modifypwdDb = M('User_modifypwd');
		 if(isset($_POST['phone']) && !empty($_POST['phone'])){
			 $result = D('User')->check_phone($_POST['phone']);

			 if (empty($_POST['verify']) || empty($_POST['type']) ||md5($_POST['verify']) != $_SESSION['user_'.$_POST['type'].'_verify']) {
				 $this->error('非法验证');
			 }
			 if(!empty($result)&&$_POST['reg']){
				 $this->ajaxReturn($result);
			 }
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
}