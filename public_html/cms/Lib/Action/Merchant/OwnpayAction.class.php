<?php
class OwnpayAction extends BaseAction{
	public function index(){

		$ownpay = D('Merchant_ownpay')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->find();
		if(empty($ownpay)){
			D('Merchant_ownpay')->data(array('mer_id' => $this->merchant_session['mer_id']))->add();
		}else{
			foreach($ownpay as $key=>$value){
				if($key != 'mer_id'){
					$ownpay[$key] = unserialize($value);
				}
			}
			$this->assign('ownpay',$ownpay);
		}
		$now_merchant = D('Merchant')->get_info($this->merchant_session['mer_id']);
		$this->assign('now_merchant',$now_merchant);
		//平台有没有开启商家绑定支付、该商家有没有配置支付
		//微信支付包含 平台有没有开启商家公众号绑定、该商家有没有配置绑定。
		$weixin_bind = D('Weixin_bind')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->find();
		if($weixin_bind){
			$this->assign('hasBind',true);
		}else{
			import('ORG.Net.Http');
// 			$result = $_SESSION['component_access_token'];
// 			if ($result && $result[0] > time()) {
// 				$result['component_access_token'] = $result[1];
// 			} else {
			$url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
			$data = array('component_appid' => $this->config['wx_appid'], 'component_appsecret' => $this->config['wx_appsecret'], 'component_verify_ticket' => $this->config['wx_componentverifyticket']);
			$result = Http::curlPost($url, json_encode($data));
			if (empty($result['errcode'])) {
				$_SESSION['component_access_token'] = array($result['expires_in'] + time(), $result['component_access_token']);
			} else {
				//exit(json_encode(array('err_code' => 1, 'err_msg' => '获取授权地址失败')));
			}
// 			}
			$url = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=' . $result['component_access_token'];//
			$data = array('component_appid' => $this->config['wx_appid']);
			$auth_code = Http::curlPost($url, json_encode($data));
			if (empty($auth_code['errcode'])) {
				$url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.$this->config['wx_appid'].'&pre_auth_code='.$auth_code['pre_auth_code'].'&redirect_uri=' . urlencode($this->config['site_url'] . '/merchant.php?g=Merchant&c=Weixin&a=auth_back');
				$this->assign('url', $url);
				//exit(json_encode(array('err_code' => 0, 'err_msg' => $url)));
			} else {
				$this->assign('url', '');
			}
		}

		$this->display();
	}
	public function save(){
		$_POST['weixin']['pay_weixin_client_cert']=$_POST['pay_weixin_client_cert'];
		$_POST['weixin']['pay_weixin_client_key']=$_POST['pay_weixin_client_key'];
		unset($_POST['pay_weixin_client_cert']);
		unset($_POST['pay_weixin_client_key']);
		$data_ownpay = array();
		foreach($_POST as $key=>$value){
			$data_ownpay[$key] = serialize($value);
			if(!empty($value['open'])){
				foreach($value as $vv){
					if(empty($vv)&&$vv!=0){
						$this->error('您开启了'.$this->getPayName($key).'，请完善'.$this->getPayName($key).'需要的信息');
					}
				}
			}
		}
		$where_ownpay = array('mer_id' => $this->merchant_session['mer_id']);
		if(D('Merchant_ownpay')->where($where_ownpay)->data($data_ownpay)->save()){
			$this->success('保存成功');
		}else{
			$this->error('保存失败，请重试');
		}
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
}


?>