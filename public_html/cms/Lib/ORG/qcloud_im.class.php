<?php
class qcloud_im{
	//SDK API类
    public $api;
	//APPID
	public $sdkappid;
	//管理员账号
	public $identifier;
	//密钥路径
	public $private_key_path;
	//加密工具路径
	public $signature;
	//usersig
	public $usersig;
	
	//架构函数
	public function __construct($sdkappid,$identifier='admin'){
		//设置 SDK 需要的参数
		$this->sdkappid   = $sdkappid;
		$this->identifier = $identifier;
		$this->private_key_path = dirname(__FILE__).'/../../..'.C('config.cloud_communication_private_key');
		if(strstr(PHP_OS, 'WIN')){
			$this->signature = dirname(__FILE__).'/qcloud_im/signature/windows-signature64.exe';
		}else{
			$this->signature = dirname(__FILE__).'/qcloud_im/signature/linux-signature64';
		}
		
		//引入SDK并调用好类放入 api属性，方便业务层调用SDK。
		require_once  dirname(__FILE__) . '/qcloud_im/TimRestApi.php';
		
		$this->api = createRestAPI();
		$this->api->init($this->sdkappid, $this->identifier);
		
		// 生成签名，有效期一天
		// 对于FastCGI，可以一直复用同一个签名，但是必须在签名过期之前重新生成签名
		
		$this->usersig = S('qcloud_im_usersig_'.$this->identifier);
		if($this->usersig){
			$this->api->set_user_sig($this->usersig);
		}else{
			$ret = $this->api->generate_user_sig($this->identifier, '86400', $this->private_key_path, $this->signature);
			if ($ret == null){
				// 签名生成失败
				return -10;
			}
			S('qcloud_im_usersig_'.$this->identifier,$ret[0],57600);		// 2/3天
			$this->usersig = $ret[0];
		}
	}
}
?>