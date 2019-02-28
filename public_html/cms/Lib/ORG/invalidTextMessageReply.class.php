<?php
class invalidTextMessageReply 
{
	private $_config = '';
	
	public $data = array();
	
	/**
	 * @param array $config
	 * config 数组包含 appid appsercet token encodingaeskey
	 * 即 $config = array('wechat_appid' => '', 'wechat_appsecret' => '', 'wechat_token' => '', 'wechat_encodingaeskey' => '');
	 * @param array $data
	 * data是微信发给服务 xml信息，进过解析的数组信息
	 */
	public function __construct($config, $data)
	{
		$this->_config = $config;
		
		$this->data = $data;
	}
	
	
	/**
	 * @return 
	 * 
	 * isuse = 0:使用系统的回复，您的回复无效，1：使用您的回复，系统回复处理无效
	 */
	public function index()
	{
		if(C('config.wechat_invalid_msg')){
			return array('data' => array(), 'isuse' => 0);
		}
		//将消息转发到多客服----------------------------------//
		$kf_account = 'no';//如果不指定特定的客服就是设置为空【$kf_account = ''】，如果过设置特定的客服，就将特定的客服 	完整客服账号 （如：test1@test）填写进去【$kf_account = 'test1@test'】就可以了
		return array('data' => array($kf_account, 'transfer_customer_service'), 'isuse' => 1);
		//将消息转发到多客服----------------------------------//
	}
}