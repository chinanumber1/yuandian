<?php
/*
 * 测试APP推送
 *
 */

class TestapppushAction extends BaseAction{
	public function deliver(){
		$client = intval($_GET['client']);	//1 IOS 2安卓 
		if($client == 2){
			$device_id = '865790020627642';
			$device_id = '863444034785648'; 
		}else{
			$device_id = '929B9A1ADF0A4C1D882AEEF69091E0FD';
		}
		$device_id = str_replace('-', '', $device_id);
		$audience = array('tag' => array($device_id));
		
		$title = '订单提醒';
		$msg = $this->config['site_name'].'：收款成功'.mt_rand(0,100).'.0'.mt_rand(1,9).'元';
		$msg = '您有新的待处理订单';
		
		$voice_return = json_decode($this->voic_baidu(),true);
		$voice_access_token = $voice_return['access_token'];
		$voice_mp3 = 'http://tsn.baidu.com/text2audio?tex='.$msg.'&lan=zh&tok='.$voice_access_token.'&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';
		
		$url = 'http://hf.pigcms.com/packapp/deliver/index.html?gopage=grab';
		$js_url = 'http://hf.pigcms.com/packapp/deliver/grab.html';
		echo $voice_mp3;
		
		$voice_second = 1;
		
		$extra = array('voice_mp3'=>$voice_mp3,'voice_second'=>$voice_second,'url'=>$url,'js_url'=>$js_url);
		dump($extra);
		import('@.ORG.Jpush');
		$jpush = new Jpush(C('config.deliver_jpush_appkey'), C('config.deliver_jpush_secret'));
		$notification = $jpush->createBody($client, $title, $msg, $extra);
		$message = $jpush->createMsg($title, $msg, $extra);
		$msg = $jpush->send("all", $audience, $notification, $message);
	}
	public function voic_baidu(){
		$voic_baidu = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=XU7nTWQzS9vn32bckmrhHbTu&client_secret=1375720f23eed9b7b787e728de5dd1c2";
		import('ORG.Net.Http');
		$http	=	new Http();
		$url	=	Http::curlGet($voic_baidu);
		return $url;
    }
}