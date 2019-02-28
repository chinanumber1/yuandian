<?php
class Wxapp_jqAction extends BaseAction{
	public function login(){
		// $this->returnCode(0,array('emptyUser'=>true));
        if($_POST['ticket']){
            $info = ticket::get($_POST['ticket'],'wxapp', true);
            if ($info) {
				// $now_user = D('User')->field(true)->where(array('uid'=>$info['uid']))->find();
				// $now_user['a'] = 1;
				$now_user = $this->autologin('uid',$info['uid']);
				$return = array(
                    'ticket'    =>	$_POST['ticket'],
                    'user'      =>	$now_user,
                );
                $this->returnCode(0, $return);
            }else{
				$this->returnCode(0,array('emptyUser'=>true));
			}
        }

		$appid = $this->config['pay_jq_wxapp_appid'];
		$appsecret = $this->config['pay_jq_wxapp_appsecret'];

		import('ORG.Net.Http');
		$http = new Http();

		$return = Http::curlPost('https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code='.$_POST['code'].'&grant_type=authorization_code', array());

		import('@.ORG.aeswxapp.wxBizDataCrypt');

		$pc = new WXBizDataCrypt($appid, $return['session_key']);
		$errCode = $pc->decryptData($_POST['encryptedData'],$_POST['iv'],$data);
		$jsonrt = json_decode($data,true);
		/*优先使用 unionId 登录*/
		if(!empty($jsonrt['unionId'])){
			$now_user = $this->autologin('union_id',$jsonrt['unionId'],$jsonrt['openId']);
		}else{
			/*再次使用 openId 登录*/
			$now_user = $this->autologin('wxapp_jq_openid',$jsonrt['openId']);
		}
		if(empty($now_user)){
			$data_user = array(
				'wxapp_jq_openid' 	=> $jsonrt['openId'],
				'union_id' 	=> ($jsonrt['unionId'] ? $jsonrt['unionId'] : ''),
				'nickname' 	=> $jsonrt['nickName'],
				'sex' 		=> $jsonrt['gender'],
				'province' 	=> $jsonrt['province'],
				'city' 		=> $jsonrt['city'],
				'avatar' 	=> str_replace('http://wx.qlogo.cn','https://thirdwx.qlogo.cn',$jsonrt['avatarUrl']),
				'is_follow' => 1,
				'source' 	=> 'wxapp'
			);
			$reg_result = D('User')->autoreg($data_user);
			if(!$reg_result['error_code']){
				$now_user = $this->autologin('wxapp_jq_openid',$jsonrt['openId']);
			}
		}

		if(!empty($now_user)){
			$ticket = ticket::create($now_user['uid'],'wxapp', true);
			$return = array(
				'ticket'=>	$ticket['ticket'],
				'user'	=>	$now_user,
			);
			$this->returnCode(0,$return);
		}else{
			$this->returnCode(0,array('emptyUser'=>true));
		}
    }
	protected function autologin($field,$value,$openid = ''){
		$result = D('User')->autologin($field,$value);
		if(empty($result['error_code'])){
			if($field == 'union_id' && empty($result['user']['wxapp_jq_openid'])){
				$condition_user['union_id'] = $value;
				D('User')->where($condition_user)->data(array('wxapp_jq_openid'=>$openid))->save();
				$result['user']['wxapp_jq_openid'] = $openid;
			}
			$now_user = $result['user'];
			$now_user['can_withdraw_money'] = floatval($now_user['now_money'])-floatval($now_user['score_recharge_money']);
		}
		return $now_user;
	}
}
?>