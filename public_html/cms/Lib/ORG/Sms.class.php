<?php
final class Sms
{
	public $topdomain;
	
	public $key;
	
	public $smsapi_url;
	
	/**
	 * 
	 * 初始化接口类
	 * @param int $userid 用户id
	 * @param int $productid 产品id
	 * @param string $sms_key 密钥
	 */
	public function __construct()
	{
		
	}
	
	public function checkmobile($mobilephone)
	{
		$mobilephone = trim($mobilephone);
// 		if (preg_match("/^13[0-9]{1}[0-9]{8}$|15[01236789]{1}[0-9]{8}$|18[01236789]{1}[0-9]{8}$/", $mobilephone)) {
		if (preg_match("/^1[0-9]{10}$/", $mobilephone)) {
			return  $mobilephone;
		} else {
			return false;
		}
	}
	
	/**
	 * 
	 * 批量发送短信
	 * @param array $mobile 手机号码
	 * @param string $content 短信内容
	 * @param datetime $send_time 发送时间
	 * @param string $charset 短信字符类型 gbk / utf-8
	 * @param string $id_code 唯一值 、可用于验证码
	 * $data = array(mer_id, store_id, content, mobile, uid, type);
	 */
	public function sendSms($data = array(), $send_time = '', $charset = 'utf-8', $id_code = '')
	{
		if ($data) {
			$type = isset($data['type']) ? $data['type'] : 'meal';
			$sendto = isset($data['sendto']) ? $data['sendto'] : 'user';
			$mer_id = isset($data['mer_id']) ? intval($data['mer_id']) : 0;
			$store_id = isset($data['store_id']) ? intval($data['store_id']) : 0;
			$village_id = isset($data['village_id']) ? intval($data['village_id']) : 0;
			$uid = isset($data['uid']) ? intval($data['uid']) : 0;
			//if (empty($mer_id)) return 'mer_id is null';
			$content = isset($data['content']) ? Sms::_safe_replace($data['content']) : '';
			if (empty($content)) return 'send content is null';
			$mobile = isset($data['mobile']) ? $data['mobile'] : '';
			if (empty($mobile)) return 'phone is null';
			
			//语音通知
			$is_voice = isset($data['is_voice']) ? intval($data['is_voice']) : 0;
			
			//O2O多个号码以空格分开，取最后一个号码
			$mobileArr = array();
			$phone_array = explode(' ', $mobile);
			foreach ($phone_array as $phone) {
				if (Sms::checkmobile($phone)) {
					$mobileArr[] = $phone;
				}
			}
			if (count($mobileArr) > 1) {
				$mobile = array_pop($mobileArr);
			}

			$data = array(
				'o2o_type' => $type,
				'o2o_sendto' => $sendto,
				'o2o_mer_id' => $mer_id,
				'o2o_store_id' => $store_id,
				'o2o_village_id' => $village_id,
				'o2o_uid' => $uid,
				
				
				'topdomain' => C('config.sms_server_topdomain'),
				'key' => trim(C('config.sms_key')),
				'token' => $mer_id . 'o2opigcms',
				'content' => $content,
				'mobile' => trim($mobile),
				'sign' => trim(C('config.sms_sign')),
				'is_voice' => $is_voice,
			);
			if(C('config.open_qcloud_sms')){
				if($sendto=='user'){
					$user = M('User')->where(array('uid'=>$uid))->find();
					$data['nationCode'] = $user['phone_country_type']?$user['phone_country_type']:C('config.qcloud_sms_default_country');
				}else if($sendto=='merchant'){
					$mer = M('Merchant')->where(array('mer_id'=>$mer_id))->find();
					$data['nationCode'] = $mer['phone_country_type']?$mer['phone_country_type']:C('config.qcloud_sms_default_country');
				}
			}
			
			$msg_class = new plan_msg();
			$param = array(
				'type' => '1',
				'content' => $data,
			);
			$msg_class->addTask($param);
		}
	}
	public function sendSmsData($data){	
		if(C('config.open_qcloud_sms') && $data['nationCode']!='86'){
			include_once 'qcloud_sms/Sms.class.php';
//			$Content = $data['content'];
			$Content = '【'.trim(C('config.sms_sign')).'】'.$data['content'];
			$sms = new QcloudSms();
			$res = $sms->send(0,$data['nationCode'],$data['mobile'],$Content);

			echo $res;
		}else if(C('config.sms_guodu_operid') && empty($data['is_voice'])){
			$OperID = C('config.sms_guodu_operid');
			$OperPass = C('config.sms_guodu_operpass');
			$Content = $data['content'];
			$DesMobile = $data['mobile'];
			Sms::postGuoduSendMsg($data,$OperID, $OperPass, $SendTime, $ValidTime, $AppendID, $Content_Type, $Content, $DesMobile);
		}else{			
			$post = '';
			foreach ($data as $k => $v) {
				$post .= $k . '=' . $v .'&';
			}

			$smsapi_senturl = 'http://up.pigcms.cn/oa/admin.php?m=sms&c=sms&a=send&productid=3';
			$return = Sms::_post($smsapi_senturl, 0, $post);
			$arr = explode('#', $return);
			$send_time = time();
			
			//增加到本地数据库
			$row = array('mer_id' => $data['o2o_mer_id'], 'uid' => $data['o2o_uid'], 'store_id' => $data['o2o_store_id'], 'time' => $send_time, 'phone' => $data['mobile'], 'text' => $data['content'], 'status' => $arr[0], 'type' => $data['o2o_type'], 'sendto' => $data['o2o_sendto'], 'village_id' => $data['o2o_village_id']);
			D('Sms_record')->add($row);
		}
	}
	
	public function postGuoduSendMsg($data,$OperID, $OperPass, $SendTime, $ValidTime, $AppendID, $Content_Type, $Content, $DesMobile){
		$Content = '【'.trim(C('config.sms_sign')).'】'.$Content;
		$DesMobileSend = $DesMobile;
		$CommString = "OperID=" . $OperID . "&OperPass=" . $OperPass . "&SendTime=" . $SendTime . "&ValidTime=" . $ValidTime . "&AppendID=" . $AppendID . "&DesMobile=" . trim($DesMobileSend) . "&Content=" . urlencode(iconv("UTF-8", "GB2312//IGNORE",$Content)) . "&ContentType=" . $Content_Type;

		$TestUrl = "http://221.179.180.158:9007/QxtSms/QxtFirewall";
		// $TestUrl = "http://221.179.180.158:8000/HttpQuickProcess_utf-8/submitMessageAll";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $TestUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $CommString);
		$response = curl_exec($ch);
		
		$xml = simplexml_load_string($response);//转换post数据为simplexml对象
		foreach($xml->children() as $child){
			if($child->getName() == 'code'){
				$sms_status = $child;
			}
		}
		$sms_status = strval($sms_status);
		if($sms_status == '03' || $sms_status == '00'){
			$sms_status = '0';
		}
		
		$send_time = time();
		//增加到本地数据库
		$row = array('mer_id' => $data['o2o_mer_id'], 'uid' => $data['o2o_uid'], 'store_id' => $data['o2o_store_id'], 'time' => $send_time, 'phone' => $data['mobile'], 'text' => $data['content'], 'status' => $sms_status, 'type' => $data['o2o_type'], 'sendto' => $data['o2o_sendto'], 'village_id' => $data['o2o_village_id']);
		D('Sms_record')->add($row);
	}
		
	
	
	/**
	 *  post数据
	 *  @param string $url		post的url
	 *  @param int $limit		返回的数据的长度
	 *  @param string $post		post数据，字符串形式username='dalarge'&password='123456'
	 *  @param string $cookie	模拟 cookie，字符串形式username='dalarge'&password='123456'
	 *  @param string $ip		ip地址
	 *  @param int $timeout		连接超时时间
	 *  @param bool $block		是否为阻塞模式
	 *  @return string			返回字符串
	 */
	
	private function _post($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 15, $block = true)
	{
		$return = '';
		$url = str_replace('&amp;', '&', $url);
		$matches = parse_url($url);
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		$siteurl = Sms::_get_url();
		if ($post) {
			$out = "POST $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: ".$siteurl."\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n" ;
			$out .= 'Content-Length: '.strlen($post)."\r\n" ;
			$out .= "Connection: Close\r\n" ;
			$out .= "Cache-Control: no-cache\r\n" ;
			$out .= "Cookie: $cookie\r\n\r\n" ;
			$out .= $post ;
		} else {
			$out = "GET $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: ".$siteurl."\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		if(!$fp) return '';
		
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
	
		if($status['timed_out']) return '';	
		while (!feof($fp)) {
			if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n"))  break;				
		}
		
		$stop = false;
		while(!feof($fp) && !$stop) {
			$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
			$return .= $data;
			if($limit) {
				$limit -= strlen($data);
				$stop = $limit <= 0;
			}
		}
		@fclose($fp);

		//部分虚拟主机返回数值有误，暂不确定原因，过滤返回数据格式
		$return_arr = explode("\n", $return);
		if(isset($return_arr[1])) {
			$return = trim($return_arr[1]);
		}
		unset($return_arr);
		
		return $return;
	}

	/**
	 * 获取当前页面完整URL地址
	 */
	private function _get_url() {
		$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
		$php_self = $_SERVER['PHP_SELF'] ? Sms::_safe_replace($_SERVER['PHP_SELF']) : Sms::_safe_replace($_SERVER['SCRIPT_NAME']);
		$path_info = isset($_SERVER['PATH_INFO']) ? Sms::_safe_replace($_SERVER['PATH_INFO']) : '';
		$relate_url = isset($_SERVER['REQUEST_URI']) ? Sms::_safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.Sms::_safe_replace($_SERVER['QUERY_STRING']) : $path_info);
		return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
	}
	
	/**
	 * 安全过滤函数
	 *
	 * @param $string
	 * @return string
	 */
	private function _safe_replace($string) {
		$string = str_replace('%20','',$string);
		$string = str_replace('%27','',$string);
		$string = str_replace('%2527','',$string);
		$string = str_replace('*','',$string);
		$string = str_replace('"','&quot;',$string);
		$string = str_replace("'",'',$string);
		$string = str_replace('"','',$string);
		$string = str_replace(';','',$string);
		$string = str_replace('<','&lt;',$string);
		$string = str_replace('>','&gt;',$string);
		$string = str_replace("{",'',$string);
		$string = str_replace('}','',$string);
		$string = str_replace('\\','',$string);
		return $string;
	}
}
?>