<?php
class SOAClient {
	private static $METHOD_POST = "POST";
	private static $BUFFER_SIZE = 1024;
	
	public static $SSO_SERVICE = "SSOService";
	public static $STATUS_OK   = "OK";
	public static $STATUS_ERR  = "error";
	public static $ERR_MESSAGE = "message";
	public static $ERR_CODE    = "errorCode";
	public static $RETURN_VALUE= "returnValue";
	
	private $serverAddress = "";
	private $serverUrl = "";
	private $sessionId;
	private $ssoid = "ime_public_ssoid";
	private $_signKey = "";
	private $_sysid = "";
	private $version = "1.0";
	private $_signMethod = "MD5";
	private $privateKey;
	private $publicKey;
	private $_publicKey;
	private $proxy = null;
	private $timeStr = null;

	private $pwd = null;
	private $alias = null;
	private $privatePath = null;
	private $publicPath = null;

	public function getServerAddress() {
		return $this->serverAddress;
	}
	public function setServerAddress($serverAddress) {
		$this->serverAddress = $serverAddress;
		$this->serverUrl = $serverAddress ;
	}
	public function getSignMethod() {
		return _signMethod;
	}
	public function setSignMethod($signMethod) {
		$this->_signMethod = $signMethod;
	}

	public function setAlias($alias)
	{
		$this->alias = $alias;
	}

	public function setPwd($pwd)
	{
		$this->pwd = $pwd;
	}

	public function setPrivatePath($privatePath)
	{
		$this->privatePath = $privatePath;
	}

	private function getPrivateKey()
	{
		$this->privateKey = RSAUtil::loadPrivateKey($this->alias,$this->privatePath, $this->pwd);
		return $this->privateKey;
	}

	private function getPublicKey()
	{
		$this->publicKey = RSAUtil::loadPublicKey($this->alias,$this->publicPath, $this->pwd);
		return $this->publicKey;
	}

	public function setPublicPath($publicPath)
	{
		$this->publicPath = $publicPath;
	}

	//验签
	function loadPublicKey($alias, $path, $pwd) {
		echo $path.'---'.$pwd;
		$priKey = file_get_contents($path);
		$res = openssl_get_privatekey($priKey);
		print_r($res);
		
		($res) or die('您使用的私钥格式错误，请检查私钥配置');
		
		openssl_sign("errorCode=SOA.NoSuchMethod&errorMessage=找不到相应的服务:aaa.createMember", $sign, $res);
		
		openssl_free_key($res);
		
		$sign=base64_encode($sign);
		
		echo '<br>'.$sign.'<br>';

		//调用openssl内置方法验签，返回bool值
		return $res;
	}
	public function setSignKey($privateKey){
		$this->privateKey = $privateKey;
	}
	/*
	public function setPublicKey(String publicKey) {
		this._publicKey = publicKey;
		try {
			this.publicKey = RSAUtil.getPublicKey(publicKey);
		} catch (Exception e) {
		}
	}
	*/
	public function setPublicKey($publicKey) {
		$this->publicKey = $publicKey;
	}
	public function getSysId() {
		return $this->_sysid;
	}
	public function setSysId($sysid) {
		$this->_sysid = $sysid;
	}
	public function getVersion() {
		return $this->version;
	}
	public function setVersion($version) {
		$this->version = $version;
	}
	/*
	public function setProxy(Proxy proxy){
		this.proxy = proxy;
	}
	*/
	public function getTimeStr() {
		return $this->timeStr;
	}
	public function setTimeStr($timeStr) {
		$this->timeStr = $timeStr;
	}
	
	
	
	
	
	
	
	/**
	 * 向IME服务器发送请求
	 * @param service 服务对象名称
	 * @param method 服务方法名称
	 * @param param 服务参数
	 * @return 返回结果，status存放调用结果(OK:成功; error:失败), returnValue存放返回结果
	 * @throws Exception
	 */
	public function request($service, $method, $param) {
		$req = $this->praseParam($service, $method, $param);
	
		$result = $this->request2($req);
		
		return $this->checkResult($result);
	}

	public function verifySign($signedValue,$sign){
		$this->getPublicKey();

		if($sign != null){
			$seccess = RSAUtil::verify($this->publicKey, $signedValue, $sign);
			if(!$seccess)
				throw new Exception("签名验证错误");
		}
		return $seccess;
	}


	public function praseParam($service, $method, $param)
	{
		$request["service"] = $service;
		$request["method"] = $method;
		$request["param"] = $param;
		$strRequest = json_encode($request);
		$strRequest = str_replace("\r\n", "", $strRequest);
		
		$req['req'] = $strRequest;
		$req['ssoid'] = $this->ssoid;
		$this->getPrivateKey();
		if (("" != $this->_signKey || $this->privateKey != null) && "" != $this->_sysid) {
			$timestamp = date("Y-m-d H:i:s", time());
			$sign = $this->sign($this->_sysid, $strRequest, $timestamp);
			$req['sysid'] = $this->_sysid;
			$req['timestamp'] = $timestamp;
			$req['sign'] = $sign;
			$req['v'] = $this->version;
		}
		fdump($req,'dd');
		return $req;
	}
	
	private function request2($args){
		global $log;
		$ch = curl_init () ;
		curl_setopt($ch, CURLOPT_URL, $this->serverUrl);

		$sb = '';
		$reqbody = array();
		foreach($args as $entry_key => $entry_value){
			$sb .= $entry_key.'='.urlencode($entry_value).'&';
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sb);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-length', count($reqbody)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);

		curl_close($ch);
		return $result;
	}
	
	/**
	 * 检查服务调用结果是否正确，如果服务调用失败，则抛出失败异常
	 * @param result 服务调用的返回结果对象
	 * @throws Exception
	 */
	private function checkResult($result){
		global $log;
		$arr = json_decode($result, true);
		$sign = $arr['sign'];
		$signedValue = $arr['signedValue'];

		$this->getPublicKey();
		if($sign != null){
 			$seccess = RSAUtil::verify($this->publicKey, $signedValue, $sign);
			if(!$seccess)
				throw new Exception("签名验证错误");
		}
	
		$arr['signedResult'] = json_decode($arr['signedValue'],true);
		return $arr;
	}
	
	private function sign($sysid, $req, $timestamp) {
		$this->getPrivateKey();
		if ("SHA1WithRSA" == $this->_signMethod){
			return RSAUtil::sign($this->privateKey, $sysid.$req.$timestamp);
		}
	}
}
?>