<?php

class JuHePay{
	private $private_key;
	private $userId;
	private $mercId;
	private $order_info;
	private $pay_url;
	private $back_url;
	private $sign_key;
	private $wxappuid;
	private $pay_type =array(10=>'weixin',11=>'weixin',12=>'weixin',15=>'weixin',20=>'alipay',21=>'alipay');// 10-微信被扫 11-微信主扫 12-微信公众号支付 20-支付宝被扫 21-支付宝主扫
	private $pay_type_other =array(10=>'scan_weixin',11=>'weixin_scan',12=>'weixin',15=>'wx_app',20=>'alipay',21=>'alipay_scan');// 10-微信被扫 11-微信主扫 12-微信公众号支付 20-支付宝被扫 21-支付宝主扫
	public function __construct(){
		$this->pay_url =C('config.juhe_pay_url');
		$this->back_url = C('config.site_url').'/source/juhepay_notice.php';
		$this->sign_key =C('config.juhe_signkey');
		$this->userId =C('config.juhe_uid');
		$this->wxappuid =C('config.juhe_wxappuid');
		$this->mercId = C('config.juhe_merid');
		$this->private_key = C('config.juhe_private_key');
		$this->appId = C('config.wechat_appid');
		$this->wx_appId = C('config.pay_wxapp_appid');
	}
	
	
	public function __set($property, $value) {
		$this->$property = $value; 
	}
	
	public function notice($sign_data){
		$sign_data=  json_decode($sign_data,true);
		$md5_info = $sign_data['md5Info'];
		unset($sign_data['md5Info']);
		ksort($sign_data);
		$r = http_build_query($sign_data).$this->sign_key;
		fdump($sign_data,'sign_data'); 
		if(strtoupper(md5($r))==$md5_info){

			$arr['sign_result'] = true;
			$order_id = explode('_',$sign_data['orderNo']);

			$arr['order_param'] = array(
					'third_id'=>$sign_data['thridOrderNo'],
					'pay_money'=>$sign_data['orderAmount'],
					'order_id'=>$order_id[1],
					'order_type'=>$order_id[0],
					'pay_type'=>$this->pay_type[$sign_data['payType']],
					'error'=>$sign_data['orderStatus']==1?0:1,
					'is_mobile'=>$sign_data['payType']=='12'?1:0,
					'is_own'=>$order_id[2]?1:0,
			);
		}else{
			$arr['sign_result'] = false;
			$arr['order_param'] = array();
		}

		return $arr;
	}


	public function refund($order){
		$request = $this->_encode_refund_request($order['orderNo']);
		$request = json_encode($request);
		$header = $this->set_header($request);
		$res = httpRequest($this->pay_url,'POST',$request,$header);
		$res = json_decode($res[1],true);
		if($res['head']['resCode']=='000000'){
			$go_refund_param['type']='ok';
			$go_refund_param['error'] = 0;
		}else{
			$go_refund_param['error'] = 1;
		}
		$go_refund_param['refund_param']  = serialize($res);
		return $go_refund_param;
	}

	public function check($order){
		$request = $this->_encode_check_request($order['orderNo']);
		$request = json_encode($request);
		$header = $this->set_header($request);
		$res = httpRequest($this->pay_url,'POST',$request,$header);
	
		$res = json_decode($res[1],true);
		$order_id = explode('_',$res['orderNo']);
		
		$go_pay_param = array(
				'third_id'=>$res['thridOrderNo'],
				'pay_money'=>$res['orderAmount'],
				'order_id'=>$order_id[1],
				'order_type'=>$order_id[0],
				'pay_type'=>$this->pay_type[$res['payType']],
				'error'=>( $res['refundstatus']==0&&$res['orderStatus']==1 )?0:1,
				'is_mobile'=>$res['payType']=='12'?1:0,
				'errorMsg'=>'',
				'pay_time'=>strtotime($res['head']['tranDate'].$res['head']['tranTime']),
				'is_own'=>($order_id[2]?1:0),
		);
		if($res['refundstatus']==1){
			$go_pay_param['errorMsg'] = '订单已退款';
		}
		if($res['orderStatus']==0){
			$go_pay_param['errorMsg'] = '订单未支付';
		}
		return $go_pay_param;
	}


	public function pay($order,$payinfo=array()){
		
		$this->order_info  = $order;
		$appid = $this->appId;
		if($order['is_wxapp']==1){
			$appid = $this->wx_appId;
			$this->userId = $this->wxappuid;
		}
		
		$request = $this->_encode_payment_request($order['order_id'],$order['order_total_money'],$order['openid'],$appid,$payinfo['auth_code']);

		$request = json_encode($request);
		$header = $this->set_header($request);
		$res = httpRequest($this->pay_url ,'POST',$request,$header);
		$pay_key  = array_search($this->order_info['pay_type'],$this->pay_type_other);
		$res = json_decode($res[1],true);

		switch($pay_key){
			//主扫微信
			case 10:
				if(!$res){
					$returnArr = array(
							'error'=>1,
							'msg'=>'用户支付中，需要输入密码！请询问用户输入完成后，再点击“确认支付”',
					);
				}else if($res['head']['resCode']=='900002'){
					$returnArr = array(
							'error'=>1,

							'msg'=>$res['head']['resMessage'],
					);
				}else if($res['head']['resCode']=='000000'){
					$returnArr = array(
							'error'=>0,
							'msg'=>'交易成功',
							'thirdid'=>$res['orderNo'],
					);
				}

				break;
			case 11:
				$returnArr = array(
						'status'=>1,
						'info'=>$res['qrCodeStr'],
						'orderid'=>$order['order_id'],
				);
				break;
			//微信公众号
			case 12:
			case 15:
				$jsApiObj["appId"] = $res['appId'];
				$jsApiObj["timeStamp"] = $res['timeStamp'];
				$jsApiObj["nonceStr"] = $res['nonceStr'];
				$jsApiObj["package"] = $res['payPackage'];
				$jsApiObj["signType"] = $res['signType'];
				$jsApiObj["paySign"] = $res['paySign'];
				$returnArr['weixin_param'] = json_encode($jsApiObj);
				$returnArr['error'] = 0;
				$order_id = explode('_',$order['order_id']);
				
				$returnArr['redirctUrl'] = C('config.site_url').'/index.php?c=LowFeePayNotice&a=order_back_notice&order_type='.$order_id[0].'&order_id='.$order_id[1].'&source=1'.($order_id[2]?'&is_own=1':'');
				break;
			//主扫支付宝
			case 20:
				if(!$res){
					$returnArr = array(
							'error'=>1,
							'msg'=>'用户支付中，需要输入密码！请询问用户输入完成后，再点击“确认支付”',
					);
				}else if($res['head']['resCode']=='900002'){
					$returnArr = array(
							'error'=>1,
							'msg'=>$res['head']['resMessage'],
					);
				}else if($res['head']['resCode']=='000000'){
					$returnArr = array(
							'error'=>0,
							'msg'=>'交易成功',
							'thirdid'=>$res['orderNo'],
					);
				}
				break;
			case 21:
				$returnArr = array(
						'status'=>1,
						'info'=>$res['qrCodeStr'],
						'orderid'=>$order['order_id'],
				);

				break;
		}

		return $returnArr;
	}

	private function _sign_request($request) {
		$privatekey = openssl_get_privatekey($this->private_key);
		$request_sha1 = sha1($request);
		$request_sha1 = str_pad($request_sha1, 128, "\0", STR_PAD_LEFT);
		$r = openssl_private_encrypt($request_sha1, $sign, $privatekey, OPENSSL_NO_PADDING);
		return bin2hex($sign);
	}

	function _encode_payment_request($order_id, $amount, $openid='',$appId='',$authCode='') {
		// if($this->order_info['pay_type_platform']=='wxapp'){
		// $this->userId = $this->wxappuid;
		// }
		
		if($payinfo){
			$this->userId = $payinfo['userid'];
			
		}
		$payment_request = array(
				'data' => array(
						"authCode" => ($authCode?$authCode:''),
						"head" => array(
								"apiVersion" => "1.0",
								"channelDate" => date("Ymd"),
								"channelNo" => "99",
								"channelSerial" => $order_id,
								"channelTime" => date('His'),
								"channelVersion" => "1.0",
								"deviceFingerPrint" => "",
								"loginToken" => "",
								"tranCode" => "PP1017", // 交易码
								"userId" => $this->userId , // 在第三方商户名
						),
						"backNoticeUrl" => $this->back_url,
						"openId" => $openid,
						"orderAmount" => strval($amount), // 金额跟平台订单数据一致
						"payType" => strval(array_search($this->order_info['pay_type'],$this->pay_type_other)), // 微信H5
						"remark" => $this->order_info['order_name'],
						"wechatAppId" =>$appId ,
						"appId" => $appId ,
						"spbillCreateIp"=> "61.148.243.120",
						"wechatSceneInfo"=> "qwer",
						"wechatMercID"=> "81163511"
				),
		);

		return $payment_request;
	}

	function _encode_refund_request($order_id) {
		$refund_request = array(
				'data' => array(
						"head" => array(
								"apiVersion" => "1.0",
								"channelDate" => date("Ymd"),
								"channelNo" => "99",
								"channelSerial" => $order_id,
								"channelTime" => date('His'),
								"channelVersion" => "1.0",
								"deviceFingerPrint" => "",
								"loginToken" => "",
								"tranCode" => "PP1032", // 交易码
								"userId" => $this->userId, // 在第三方商户名
						),
						"orderNo" => $order_id,
				),
		);
		return $refund_request;
	}

	function _encode_check_request($order_id) {
		$refund_request = array(
				'data' => array(
						"head" => array(
								"apiVersion" => "1.0",
								"channelDate" => date("Ymd"),
								"channelNo" => "99",
								"channelSerial" => $order_id,
								"channelTime" => date('His'),
								"channelVersion" => "1.0",
								"deviceFingerPrint" => "",
								"loginToken" => "",
								"tranCode" => "PP1024", // 交易码
								"userId" => $this->userId, // 在第三方商户名
						),
						"orderNo" => $order_id,
				),
		);
		return $refund_request;
	}
	function set_header($request) {
		$header = array(
				"Content-type: application/json;charset='utf-8'",
				'Content-Encoding: UTF-8',
				'mercId: '.$this->mercId,
				'sign: '.$this->_sign_request($request),
		);
		return $header;
	}



}


