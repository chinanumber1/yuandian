<?php
class Huaweipush{
    public $AppID, $AppSecret;
    public function __construct(){
        $this->AppID = '100080543';
        $this->AppSecret = 'cfb3d6bcd826688893e0cf51003b562c';
    }
    public function get_token(){
		$access_token = S('huawei_push_token');
		
		if(empty($access_token)){
			$param['grant_type'] = 'client_credentials';
			$param['client_id'] = $this->AppID;
			$param['client_secret'] = $this->AppSecret;
			
			import('ORG.Net.Http');
			$http = new Http();

			$return = Http::curlPostOwn('https://login.vmall.com/oauth2/token', http_build_query($param));
			
			$return = json_decode($return,true);
			$access_token = $return['access_token'];
			
			S('huawei_push_token',$access_token,$return['expires_in']-3600);
		}
		
		return $access_token;
	}
	public function push($device_id,$title,$content){
		$param['access_token'] = $this->get_token();
		$param['nsp_ts'] = time();
		$param['nsp_svc'] = 'openpush.message.api.send';
		
		$device_id_arr = array($device_id);
		
		$param['device_token_list'] = json_encode($device_id_arr);
		
		
		$title = '订单提醒';
		$msg = C('config.group_alias_name') . '新订单提醒，请及时查看！';
		
		$voice_return = json_decode($this->voic_baidu(), true);
		$voice_access_token = $voice_return['access_token'];
		$voice_mp3 = 'http://tsn.baidu.com/text2audio?tex=' . $msg . '&lan=zh&tok=' . $voice_access_token . '&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';
	
		$url = C('config.site_url') . '/packapp/storestaff/index.html?gopage=group_list';
		$js_url = C('config.site_url') . '/packapp/storestaff/group_list.html';
	
		$voice_second = 6;
		
		$extra = array(
			array('pigcms_tag' => 'group_order'),
			array('voice_mp3' => $voice_mp3),
			array('voice_second' => $voice_second), 
			array('url' => $url),
			array('js_url' => $js_url),
			array('mp3_label' => 'new_group_order'),
		);
		
		$content = array(
			'content' => $content,
			'pigcms_tag' => 'group_order',
			'voice_mp3' => $voice_mp3,
			'voice_second' => $voice_second,
			'url' => $url,
			'js_url' => $js_url,
			'mp3_label' => 'new_group_order',
		);
		
		$payload = array(
			'hps' => array(
				'msg' => array(
					'type' => 1,
					'body' => array(
						'content' => $content,
						'title' => $title,
					),
					// 'action' => array(
						// 'type' => 1,
						// 'param' => array(
							// 'intent' => '#Intent;compo=com.rvr/.Activity;S.W=U;end',
						// ),
					// ),
				),
			),
			'ext' => array(
				'biTag' => 'Trump',
				'customize' => $extra
			),
		);
		$param['payload'] = json_encode($payload);
		
		// echo $param['payload'];
		// die;
		
		// dump($param);
		
		import('ORG.Net.Http');
		$http = new Http();
		
		$url_param = array('ver'=>1,"appId"=>$this->AppID);
		$return = Http::curlPostOwn('https://api.push.hicloud.com/pushsend.do?nsp_ctx='.urlencode(json_encode($url_param)), http_build_query($param));
		dump($return);
	}
	private function voic_baidu()
    {
        static $return;

        if (empty($return)) {
            $voic_baidu = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=XU7nTWQzS9vn32bckmrhHbTu&client_secret=1375720f23eed9b7b787e728de5dd1c2";
            import('ORG.Net.Http');
            $return = Http::curlGet($voic_baidu);
        }
        return $return;
    }
}