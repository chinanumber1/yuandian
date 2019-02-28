<?php
/*
 * 渠道二维码
 *
 */
class Recognition_wxappAction extends BaseAction{
	public function create_page_qrcode(){
		$qr_path = htmlspecialchars_decode($_GET['page']);
		$access_token_array = D('Access_token_wxapp_expires')->get_access_token();
		if ($access_token_array['errcode']) {
			exit('获取access_token发生错误：错误代码' . $access_token_array['errcode'] .',微信返回错误信息：' . $access_token_array['errmsg']);
		}
		
		$qrcode_url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$access_token_array['access_token'];
		$post_data = array(
			'path'=>$qr_path,
			'width'=>425
		);
		$img_content = $this->curlPost($qrcode_url,json_encode($post_data));
		// echo $qr_path;
		// dump($img_content);die;
		header('Content-type: image/jpg');
		echo $img_content;
	}
	public function create_unlimit_qrcode(){
		$page = htmlspecialchars_decode($_GET['page']);
		$scene = htmlspecialchars_decode($_GET['scene']);
		$access_token_array = D('Access_token_wxapp_expires')->get_access_token();
		if ($access_token_array['errcode']) {
			exit('获取access_token发生错误：错误代码' . $access_token_array['errcode'] .',微信返回错误信息：' . $access_token_array['errmsg']);
		}
		
		$qrcode_url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$access_token_array['access_token'];
		$post_data = array(
			'scene'	=>	$scene,
			'page'	=>	$page,
			'width'	=>	280
		);
		$img_content = $this->curlPost($qrcode_url,json_encode($post_data));
		// echo $qr_path;
		// dump($img_content);die;
		header('Content-type: image/jpg');
		echo $img_content;
	}
	public function create_url_qrcode(){
		$qr_path = '/pages/index/index?redirect=webview&webview_url='.urlencode(htmlspecialchars_decode($_GET['webview_url']));
		$shortTitle = '&webview_title=';
		$title = '&webview_title='.urlencode($_GET['webview_title']);
		if(strlen($qr_path) + strlen($title) < 128){
			$qr_path.= $title;
		}else if(strlen($qr_path) + strlen($shortTitle) < 128){
			$qr_path.= $shortTitle;
		}
		$access_token_array = D('Access_token_wxapp_expires')->get_access_token();
		if ($access_token_array['errcode']) {
			exit('获取access_token发生错误：错误代码' . $access_token_array['errcode'] .',微信返回错误信息：' . $access_token_array['errmsg']);
		}
		
		$qrcode_url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$access_token_array['access_token'];
		$post_data = array(
			'path'=>$qr_path,
			'width'=>425
		);
		$img_content = $this->curlPost($qrcode_url,json_encode($post_data));
		// echo $qr_path;
		// dump($img_content);die;
		header('Content-type: image/jpg');
		echo $img_content;
	}
	public function see_qrcode($type,$id){
		//判断ID是否正确，如果正确且以前生成过二维码则得到ID
		$data['third_type'] = $type;
		$data['third_id'] = $id;
		
		
		$now_qrcode = M('Recognition_wxapp')->where($data)->find();
		
		if(!empty($now_qrcode)){
			if($_GET['img']){
				echo '<html><head><style>*{margin:0;padding:0;}</style></head><body><img src="'.$now_qrcode['ticket'].'"/></body></html>';
			}else{
				redirect($now_qrcode['ticket']);
			}
			die;
		}

        if($type == 'merchantstore'){
            $qr_path = 'pages/store/detail?store_id='.$id;
        }elseif($type == 'doorshare'){
		    // 访客分享开门
            $qr_path = '/pages/index/index?redirect=house_door&door_share_id='.$id;
        }else{
			exit('您查看的内容非法！无法查看二维码！');
		}
		
		$access_token_array = D('Access_token_wxapp_expires')->get_access_token();
		if ($access_token_array['errcode']) {
			exit('获取access_token发生错误：错误代码' . $access_token_array['errcode'] .',微信返回错误信息：' . $access_token_array['errmsg']);
		}
		
		$qrcode_url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$access_token_array['access_token'];
		$post_data = array(
			'path'=>$qr_path,
			'width'=>425
		);
		$img_content = $this->curlPost($qrcode_url,json_encode($post_data));
		header('Content-type: image/jpg');
		echo $img_content;
	}
	static public function curlPost($url,$data,$timeout=15){
		$ch = curl_init();
		$headers[] = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch ,CURLOPT_TIMEOUT ,$timeout);
		$result = curl_exec($ch);
		
		//关闭curl
		curl_close($ch);
		return $result;	
	}
}