<?php

class AlipayApp
{
    protected $order_info;

    protected $pay_money;

    protected $pay_type;

    protected $is_mobile;

    protected $user_info;

    protected $config;

    public function __construct($order_info=array(),$pay_money=0,$pay_type='alipay',$pay_config=array(),$user_info=array(),$is_mobile=0)
    {
        $this->order_info = $order_info;
        
        $this->pay_money = $pay_money;
        
        $this->pay_type = $pay_type;
        
        $this->is_mobile = $is_mobile;
        
        $this->user_info = $user_info;
        
        $this->config = array(
            // 应用ID,您的APPID。
            'app_id' => $pay_config['new_pay_alipay_app_appid'],
            // 商户私钥，您的原始格式RSA私钥
            'alipay_private_key' => $pay_config['new_pay_alipay_app_private_key'],
            // 异步通知地址
            'notify_url' => C('config.site_url') . '/source/appapi_alipay_notice.php',
            // 同步跳转
            'return_url' => C('config.site_url') . '/source/appapi_alipay_return.php',
            // 编码格式
            'charset' => "UTF-8",
            // 签名方式
            'sign_type' => 'RSA2',
            // 支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
            // 支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => $pay_config['new_pay_alipay_app_public_key']
        );
    }

    public function pay()
    {
        if ($this->is_mobile) {
            return $this->mobile_pay();
        } else if($this->is_mobile==2) {
            return $this->app_pay();
        } else {
            return $this->web_pay();
        }
    }

    public function mobile_pay()
    {

    }

    // 异步通知
    public function notice_url()
    {
        if ($this->is_mobile) {
            return $this->mobile_notice();
        } elseif ($this->is_mobile == 2) {
            return $this->app_notice();
        } else {
            return $this->web_notice();
        }
    }



    public function refund()
    {
        import('@.ORG.pay.Aop.AopClient');
        import('@.ORG.pay.Aop.SignData');
        import('@.ORG.pay.Aop.AlipayTradePayRequest');
        import('@.ORG.pay.Aop.AlipayTradeRefundRequest');
	
        $aop = new AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $this->config['app_id'];
        $aop->rsaPrivateKey = $this->config['alipay_private_key'];
        $aop->alipayrsaPublicKey=$this->config['alipay_public_key'];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset="UTF-8";
        $aop->format='json';
        $request = new AlipayTradeRefundRequest ();
        $out_trade_no = $this->order_info['order_type'].'_'. $this->order_info['orderid'];
		$trade_no = $this->order_info['third_id'];
        $request->setBizContent("{" .
            "\"out_trade_no\":\"{$out_trade_no}\"," .
            "\"trade_no\":\"{$trade_no}\"," .
            "\"refund_amount\":{$this->pay_money}," .
            "\"refund_reason\":\"正常退款\"" .
            "  }");

        $result = $aop->execute ( $request);


        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            return array('error'=>0,'type'=>'ok','msg'=>'退款申请成功！请注意查收“支付宝”给您发的退款通知。','refund_param'=> $result->$responseNode->msg);
        } else {
            return array('error'=>1,'type'=>'fail','msg'=>'退款申请失败！如果重试多次还是失败请联系系统管理员。','refund_param'=> $result->$responseNode->msg);
        }
    }

}
?>