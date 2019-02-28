<?php
class Alipay_authAction extends BaseAction{
	public function index(){
		echo 'success';
		import('@.ORG.pay.Aop.AopClient');
		import('@.ORG.pay.Aop.SignData');
		import('@.ORG.pay.Aop.AlipayTradePayRequest');
		import('@.ORG.pay.Aop.AlipayOpenAuthTokenAppRequest');
		$aop = new AopClient ();
		$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
		$aop->appId = 'your app_id';
		$aop->rsaPrivateKey = '请填写开发者私钥去头去尾去回车，一行字符串';
		$aop->alipayrsaPublicKey='请填写支付宝公钥，一行字符串';
		$aop->apiVersion = '1.0';
		$aop->signType = 'RSA2';
		$aop->postCharset='GBK';
		$aop->format='json';
		$request = new AlipayOpenAuthTokenAppRequest();
		$request->setBizContent("{" .
				"\"grant_type\":\"authorization_code\"," .
				"\"code\":\"1cc19911172e4f8aaa509c8fb5d12F56\"," .
				"\"refresh_token\":\"201509BBdcba1e3347de4e75ba3fed2c9abebE36\"" .
				"  }");
		$result = $aop->execute ( $request);

		$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
		$resultCode = $result->$responseNode->code;
		if(!empty($resultCode)&&$resultCode == 10000){
			echo "成功";
		} else {
			echo "失败";
		}

	}



}


?>
