<?php
class SmscodeverifyModel extends Model{
	// 短信模版设置 还没有 
	public function get_sms_module(){
		
	}
	
	public function verify($sms_code,$phone){
		$user_modifypwdDb = M('User_modifypwd');
		$vfycode = trim($sms_code);
		$modifypwd = $user_modifypwdDb->where(array('vfcode' => $vfycode, 'telphone' => $phone))->find();
		if(empty($modifypwd)){
			return array('error_code'=>1,'msg'=>'验证码失败,请务必在20分钟内验证');
		}else{
			return array('error_code'=>0,'modifypwd'=>$modifypwd,'msg'=>'');
		}
		
	}
}