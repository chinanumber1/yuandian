<?php
/*
 * 商户和微信通信接口
 */
class HouseweixinAction extends CommonAction
{
	public function index()
	{
		$wechat = new Chat(array('wx_appid' => $this->config['wx_house_appid'], 'wx_token' => $this->config['wx_house_token'], 'wx_encodingaeskey' => $this->config['wx_house_encodingaeskey']));
		$data = $wechat->request();
		list($content, $type) = $this->reply($data);
		if ($content) {
			$wechat->response($content, $type);
		} else {
			exit();
		}
	}
	
	
	public function get_ticket()
	{
		//获取 component_verify_ticket 每十分钟微信服务本服务器请求一次
		$wechat = new Chat(array('wx_appid' => $this->config['wx_house_appid'], 'wx_token' => $this->config['wx_house_token'], 'wx_encodingaeskey' => $this->config['wx_house_encodingaeskey']));
		$data = $wechat->request();
		if(isset($data['InfoType'])) {
			if ($data['InfoType'] == 'component_verify_ticket') {
				if (isset($data['ComponentVerifyTicket']) && $data['ComponentVerifyTicket']) {
					if ($config = D('Config')->where("`name`='wx_house_componentverifyticket'")->find()) {
						D('Config')->where("`name`='wx_house_componentverifyticket'")->data(array('value' => $data['ComponentVerifyTicket']))->save();
					} else {
						D('Config')->data(array('name' => 'wx_house_componentverifyticket', 'value' => $data['ComponentVerifyTicket'], 'type' => 'type=text', 'gid' => 0, 'tab_id' => 0))->add();
					}
					S(C('now_city') . 'config', null);
					exit('success');
				}
			} elseif ($data['InfoType'] == 'unauthorized') {
				if (isset($data['AuthorizerAppid']) && $data['AuthorizerAppid']) {
					D('Weixin_bind')->where("`authorizer_appid`='{$data['AuthorizerAppid']}' AND type=1")->delete();
					exit('success');
				}
			}
		}
	}
	
	
	private function get_access_token($auth_code)
	{
		import('ORG.Net.Http');
		$url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
		$data = array('component_appid' => $this->config['wx_house_appid'], 'component_appsecret' => $this->config['wx_house_appsecret'], 'component_verify_ticket' => $this->config['wx_house_componentverifyticket']);
		$result = Http::curlPost($url, json_encode($data));
		if ($result['errcode']) {
			return false;
		}
		//获取 authorizer_appid
		$url = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=' . $result['component_access_token'];//
		$data = array('component_appid' => $this->config['wx_house_appid'], 'authorization_code' => $auth_code);
		$result1 = Http::curlPost($url, json_encode($data));

		if ($result1['errcode']) {
			return false;
		}
		return $result1['authorization_info']['authorizer_access_token'];
	}
	
	
    private function reply($data)
    {
    	$keyword = isset($data['Content']) ? $data['Content'] : (isset($data['EventKey']) ? $data['EventKey'] : '');
    	if($data['ToUserName'] == 'gh_3c884a361561'){
    		if ($data['MsgType'] == 'event') {
    			return array($data['Event'] . 'from_callback', 'text');
    		}
    		
    		if ($keyword == 'TESTCOMPONENT_MSG_TYPE_TEXT') {
    			return array('TESTCOMPONENT_MSG_TYPE_TEXT_callback', 'text');
    		}
    		if (strstr($keyword, 'QUERY_AUTH_CODE:')) {
    			$t = explode(':', $keyword);
    			$query_auth_code = $t[1];
    			$access_token = $this->get_access_token($query_auth_code);
    			$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
    			$str = '{"touser":"'. $data['FromUserName'] .'", "msgtype":"text", "text":{"content":"' . $query_auth_code . '_from_api"}}';
    			import('ORG.Net.Http');
    			Http::curlPost($url, $str);
    		}
    	}
    	
		$mer_id = 0;
		if ($bind = D('Weixin_bind')->where(array('user_name' => $data['ToUserName'], 'type' => 1))->find()) {
			$mer_id = $bind['mer_id'];
		} else {
			return false;
		}
		
    	if ($data['MsgType'] == 'event') {
    		$id = $data['EventKey'];
    		switch (strtoupper($data['Event'])) {
    			case 'SCAN':
// 		    		return $this->scan($id, $data['FromUserName'], $mer_id);
		    		break;
    			case 'CLICK':
		    		$return = $this->special_keyword($id, $data, $mer_id);
		    		return $return;
    				break;
    			case 'SUBSCRIBE':
					$this->route();
    				if (isset($data['Ticket'])) {
    					$id = substr($data['EventKey'], 8);
    					return $this->scan($id, $data['FromUserName'], $mer_id);
    				}
    				if ($mer_id) {
    					if ($other = D('House_reply_other')->where(array('type' => 0, 'mer_id' => $mer_id))->find()) {
    						if ($other['reply_type'] == 0) {
    							return array($other['content'], 'text');
    						} else {
    							return $this->txt_img($other['from_id'], $mer_id);
    						}
    					} else {
    						return array("感谢您的关注，我们将竭诚为您服务！", 'text');
    					}
    				}
    				return array("感谢您的关注，我们将竭诚为您服务！", 'text');
    				break;
    			case 'UNSUBSCRIBE':
					$this->route();
    				return array("BYE-BYE", 'text');
    				break;
    			case 'LOCATION':
    				break;
				default:
					//return array("亲，此号暂停测试，请搜索【pigcms】进行关注测试", 'text');
    		}
    	} elseif ($data['MsgType'] == 'text') {
    		$content = $data['Content'];
    		if (strtolower($content) == 'go') {
    			$t_data = $this->route();
// 				header("Content-type: text/xml");
// 				exit($t_data);
    		}
    		$return = $this->special_keyword($content, $data, $mer_id);
    		return $return;
    	} elseif ($data['MsgType'] == 'image' && $mer_id) {
			if ($other = D('House_reply_other')->where(array('type' => 2, 'mer_id' => $mer_id))->find()) {
				if ($other['reply_type'] == 0) {
					return array($other['content'], 'text');
				} else {
					return $this->txt_img($other['from_id'], $mer_id);
				}
			} else {
				return false;
			}
    	}
    	return false;
    }

    
    private function txt_img($pigcms_id, $mer_id)
    {
    	if ($data = D('House_source_material')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $mer_id))->find()) {
    		$it_ids = unserialize($data['it_ids']);
    		if ($data['type'] == 0) {
    			$id = isset($it_ids[0]) ? intval($it_ids[0]) : 0;
    			$image_text = D('House_image_text')->where(array('pigcms_id' => $id))->find();
    			$url = $this->config['site_url'] . '/wap.php?g=Wap&c=Article&a=hdetail&imid=' . $image_text['pigcms_id'];
    			$return[] = array($image_text['title'], $image_text['digest'], $this->config['site_url'] . $image_text['cover_pic'], $url);
    		} else {
    			$image_texts = D('House_image_text')->where(array('pigcms_id' => array('in', $it_ids)))->select();
    			foreach ($image_texts as $image) {
    				$url = $this->config['site_url'] . '/wap.php?g=Wap&c=Article&a=hdetail&imid=' . $image['pigcms_id'];
    				$return[] = array($image['title'], $image['digest'], $this->config['site_url'] . $image['cover_pic'], $url);
    			}
    		}
    		return array($return, 'news');
    	} else {
    		return array("没有找到相关的应答", 'text');
    	}
    }
    
    
    private function special_keyword($key, $data = array(), $mer_id = 0)
    {
    	$return = array();
		//关键字回复
		if ($mer_id) {
			if ($keyobj = D('House_keyword')->where(array('mer_id' => $mer_id, 'content' => $key))->find()) {
				if ($keyobj['table'] == 'House_text') {
					$text = D('House_text')->where(array('pigcms_id' => $keyobj['from_id']))->find();
					return array($text['content'], 'text');
				} else {
					return $this->txt_img($keyobj['from_id'], $mer_id);
				}
			} elseif ($other = D('House_reply_other')->where(array('type' => 1, 'mer_id' => $mer_id, 'is_open' => 1))->find()) {
				if ($other['reply_type'] == 0) {
					return array($other['content'], 'text');
				} else {
					return $this->txt_img($other['from_id'], $mer_id);
				}
			}
		}
		return array('亲，暂时没有找到与“' . $key . '”相关的内容！请更换内容。', 'text');

    }
    
    //连接路由操作
    private function route()
    {
		$nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
		$sTimeStamp = isset($_REQUEST['timestamp']) ? $_REQUEST['timestamp'] : time();
		$msg_signature = isset($_REQUEST['msg_signature']) ? $_REQUEST['msg_signature'] : '';
		$xml = $GLOBALS["HTTP_RAW_POST_DATA"];
		
		import("@.ORG.aes.WXBizMsgCrypt");
		$pc = new WXBizMsgCrypt($this->config['wx_house_token'], $this->config['wx_house_encodingaeskey'], $this->config['wx_house_appid']);
		$sMsg = "";
		$pc->decryptMsg($msg_signature, $sTimeStamp, $nonce, $xml, $sMsg);
		
		$data = $this->api_notice_increment('http://we-cdn.net', $sMsg);
		$data = str_replace('<?xml version="1.0"?>', '', $data);
		$encryptMsg = "";
		$pc->encryptMsg($data, $sTimeStamp, $nonce, $encryptMsg);
		return $encryptMsg;
    }
    
    
    private function api_notice_increment($url, $data)
    {
    	$ch = curl_init();
		$headers = array(
				"User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1",
				"Accept-Language: en-us,en;q=0.5",
				"Referer:http://mp.weixin.qq.com/",
 				'Content-type: text/xml'
		);
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
    	$tmpInfo = curl_exec($ch);
    	curl_close($ch);
    	if (curl_errno($ch)) {
    		return false;
    	} else {
    		return $tmpInfo;
    	}
    }
}