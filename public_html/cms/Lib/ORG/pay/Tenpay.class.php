<?php
class Tenpay{
	protected $order_info;
	protected $pay_money;
	protected $pay_type;
	protected $is_mobile;
	protected $pay_config;
	protected $user_info;
	
	public function __construct($order_info,$pay_money,$pay_type,$pay_config,$user_info,$is_mobile=0){
		$this->order_info = $order_info;
		$this->pay_money  = $pay_money;
		$this->pay_type   = $pay_type;
		$this->is_mobile  = $is_mobile;
		$this->pay_config = $pay_config;
		$this->user_info  = $user_info;
	}
	public function pay(){
		if(empty($this->pay_config['pay_tenpay_partnerid']) || empty($this->pay_config['pay_tenpay_partnerkey'])){
			return array('error'=>1,'msg'=>'财付通支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile == 2){
			return $this->app_pay();
		}elseif($this->is_mobile){
			return $this->mobile_pay();
		}else{
			return $this->web_pay();
		}
	}
	public function mobile_pay(){
		import("@.ORG.pay.Tenpay.RequestHandler");
		import("@.ORG.pay.Tenpay.client.ClientResponseHandler");
		import("@.ORG.pay.Tenpay.client.TenpayHttpClient");

		/* 创建支付请求对象 */
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($this->pay_config['pay_tenpay_partnerkey']);
		$reqHandler->setGateUrl("http://wap.tenpay.com/cgi-bin/wappayv2.0/wappay_init.cgi");
		$httpClient = new TenpayHttpClient();
		
		
		//应答对象
		$resHandler = new ClientResponseHandler();
		
		//----------------------------------------
		//设置支付参数
		//----------------------------------------
		if($this->pay_config['is_own'] && $this->order_info['mer_id']){
			$callback_url = C('config.site_url').'/wap.php?c=Pay&a=return_url&pay_type=tenpay&own_mer_id='.$this->order_info['mer_id'];
		}else{
			$callback_url = C('config.site_url').'/wap.php?c=Pay&a=return_url&pay_type=tenpay';
		}
		$notify_url = C('config.site_url').'/wap.php?c=Pay&a=notify_url&pay_type=tenpay';
		$desc = ($this->pay_config['is_own'] ? '' : '').$this->order_info['order_name'].'_'.$this->order_info['order_num'];
		
		
		$reqHandler->setParameter("total_fee",floatval($this->pay_money*100));  //总金额
		//用户ip
		$reqHandler->setParameter("spbill_create_ip", get_client_ip());//客户端IP
		$reqHandler->setParameter("ver", "2.0");	//版本类型
		$reqHandler->setParameter("bank_type", "0"); //银行类型，财付通填写0
		$reqHandler->setParameter("callback_url",$callback_url);	//交易完成后跳转的URL
		$reqHandler->setParameter("bargainor_id", $this->pay_config['pay_tenpay_partnerid']); //商户号
		$reqHandler->setParameter("sp_billno", $this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : '')); //商户订单号
		$reqHandler->setParameter("notify_url",$notify_url);//接收财付通通知的URL，需绝对路径
		$reqHandler->setParameter("desc",$desc);
		$reqHandler->setParameter("attach",'1');

		$httpClient->setReqContent($reqHandler->getRequestURL());

		//后台调用
		if($httpClient->call()){
			$resHandler->setContent($httpClient->getResContent());
			if($resHandler->parameters['err_info']){
				return array('error'=>1,'msg'=>'财付通异常返回：<b>'.$resHandler->parameters['err_info'].'</b>');
			}
			//获得的token_id，用于支付请求
			$token_id = $resHandler->getParameter('token_id');
			$reqHandler->setParameter("token_id", $token_id);
			
			$reqUrl = "http://wap.tenpay.com/cgi-bin/wappayv2.0/wappay_gate.cgi?token_id=".$token_id;
			
			return array('error'=>0,'url'=>$reqUrl);
		}else{
			return array('error'=>1,'msg'=>'财付通信息校验失败，请重试。');
		}
	}
	public function web_pay(){
		import("@.ORG.pay.TenpayComputer.RequestHandler");
		
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($this->pay_config['pay_tenpay_partnerkey']);
		$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");
		
		//----------------------------------------
		//设置支付参数
		//----------------------------------------
		if($this->pay_config['is_own'] && $this->order_info['mer_id']){
			$return_url = C('config.site_url').'/index.php?c=Pay&a=return_url&pay_type=tenpay&own_mer_id='.$this->order_info['mer_id'];
		}else{
			$return_url = C('config.site_url').'/index.php?c=Pay&a=return_url&pay_type=tenpay';
		}
		$notify_url = C('config.site_url').'/index.php?c=Pay&a=notify_url&pay_type=tenpay';
		$body = ($this->pay_config['is_own'] ? '' : '').'订单编号：'.$this->order_info['order_id'];
		
		
		$reqHandler->setParameter("partner", $this->pay_config['pay_tenpay_partnerid']);	//商户号
		$reqHandler->setParameter("out_trade_no", $this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : ''));
		$reqHandler->setParameter("total_fee", floatval($this->pay_money*100));  //总金额
		$reqHandler->setParameter("return_url", $return_url);
		$reqHandler->setParameter("notify_url", $notify_url);
		$reqHandler->setParameter("body", $body);
		$reqHandler->setParameter("bank_type", "DEFAULT");  	  //银行类型，默认为财付通
		//用户ip
		$reqHandler->setParameter("spbill_create_ip", get_client_ip());//客户端IP
		$reqHandler->setParameter("fee_type", "1");               //币种
		$reqHandler->setParameter("subject",'订单编号：'.$this->order_info['order_id']);          //商品名称，（中介交易时必填）
		
		//系统可选参数
		$reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
		$reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
		$reqHandler->setParameter("input_charset", "utf-8");   	  //字符集
		$reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号

		
		//请求的URL
		$reqUrl = $reqHandler->getRequestURL();
		
		//获取debug信息,建议把请求和debug信息写入日志，方便定位问题
		$debugInfo = $reqHandler->getDebugInfo();
		
		return array('error'=>0,'url'=>$reqUrl);
	}

	public function app_pay(){
		import("@.ORG.pay.Tenpay.RequestHandler");
		import("@.ORG.pay.Tenpay.client.ClientResponseHandler");
		import("@.ORG.pay.Tenpay.client.TenpayHttpClient");

		/* 创建支付请求对象 */
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($this->pay_config['pay_tenpay_partnerkey']);
		$reqHandler->setGateUrl("http://wap.tenpay.com/cgi-bin/wappayv2.0/wappay_init.cgi");
		$httpClient = new TenpayHttpClient();
		
		
		//应答对象
		$resHandler = new ClientResponseHandler();
		
		//----------------------------------------
		//设置支付参数
		//----------------------------------------
		if($this->pay_config['is_own'] && $this->order_info['mer_id']){
			$callback_url = C('config.site_url').'/api.php?c=Pay&a=return_url&pay_type=tenpay&own_mer_id='.$this->order_info['mer_id'];
		}else{
			$callback_url = C('config.site_url').'/api.php?c=Pay&a=return_url&pay_type=tenpay';
		}
		$notify_url = C('config.site_url').'/api.php?c=Pay&a=notify_url&pay_type=tenpay&ticket='.$ticket."&Device-Id=".$deviceId;
		if ($this->is_mobile == 2) {
			$callback_url = C('config.site_url').'/api.php?g=Mobile&c=Pay&a=return_url&pay_type=tenpay&is_mobile=2&Device-Id=200';
			$notify_url = C('config.site_url').'/api.php?g=Mobile&c=Pay&a=notify_url&pay_type=tenpay&is_mobile=2&Device-Id=200';
		}
		$desc = $this->order_info['order_name'].'_'.$this->order_info['order_num'];
		
		
		$reqHandler->setParameter("total_fee",floatval($this->pay_money*100));  //总金额
		//用户ip
		$reqHandler->setParameter("spbill_create_ip", get_client_ip());//客户端IP
		$reqHandler->setParameter("ver", "2.0");	//版本类型
		$reqHandler->setParameter("bank_type", "0"); //银行类型，财付通填写0
		$reqHandler->setParameter("callback_url",$callback_url);	//交易完成后跳转的URL
		$reqHandler->setParameter("bargainor_id", $this->pay_config['pay_tenpay_partnerid']); //商户号
		$reqHandler->setParameter("sp_billno", $this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : '')); //商户订单号
		$reqHandler->setParameter("notify_url",$notify_url);//接收财付通通知的URL，需绝对路径
		$reqHandler->setParameter("desc",$desc);
		$reqHandler->setParameter("attach",'1');

		$httpClient->setReqContent($reqHandler->getRequestURL());

		//后台调用
		if($httpClient->call()){
			$resHandler->setContent($httpClient->getResContent());
			if($resHandler->parameters['err_info']){
				return array('error'=>1,'msg'=>'财付通异常返回：<b>'.$resHandler->parameters['err_info'].'</b>');
			}
			//获得的token_id，用于支付请求
			$token_id = $resHandler->getParameter('token_id');
			$reqHandler->setParameter("token_id", $token_id);
			
			$reqUrl = "http://wap.tenpay.com/cgi-bin/wappayv2.0/wappay_gate.cgi?token_id=".$token_id;
			
			return array('error'=>0,'url'=>$reqUrl);
		}else{
			return array('error'=>1,'msg'=>'财付通信息校验失败，请重试。');
		}
	}
	
	public function notice_url(){
		if(empty($this->pay_config['pay_tenpay_partnerid']) || empty($this->pay_config['pay_tenpay_partnerkey'])){
			return array('error'=>1,'msg'=>'财付通支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile){
			return $this->mobile_notice();
		}else{
			return $this->web_notice();
		}
	}
	public function mobile_notice(){
		exit('success');
	}
	public function web_notice(){
		exit('success');
	}
	public function return_url(){
		if(empty($this->pay_config['pay_tenpay_partnerid']) || empty($this->pay_config['pay_tenpay_partnerkey'])){
			return array('error'=>1,'msg'=>'财付通支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile){
			return $this->mobile_return();
		}else{
			return $this->web_return();
		}
	}
	public function mobile_return(){
		import("@.ORG.pay.Tenpay.ResponseHandler");
		import("@.ORG.pay.Tenpay.WapResponseHandler");
		$resHandler = new WapResponseHandler();
		$resHandler->setKey($this->pay_config['pay_tenpay_partnerkey']);
		//判断签名
		if($resHandler->isTenpaySign()) {
			//商户订单号
			$bargainor_id = $resHandler->getParameter("bargainor_id");
			$sp_billno = $resHandler->getParameter("sp_billno");
			//财付通交易单号
			$transaction_id = $resHandler->getParameter("transaction_id");
			//金额,以分为单位
			$total_fee = $resHandler->getParameter("total_fee");
			//支付结果
			$pay_result = $resHandler->getParameter("pay_result");
			if( "0" == $pay_result  ) {	
				$order_id_arr = explode('_',$sp_billno);
				
				$order_param['pay_type'] = 'tenpay';
				$order_param['is_mobile'] = '1';
				$order_param['order_type'] = $order_id_arr[0];
				$order_param['order_id'] = $order_id_arr[1];
				$order_param['is_own'] = intval($order_id_arr[2]);
				$order_param['third_id'] = $transaction_id;
				$order_param['pay_money'] = $total_fee/100;
				return array('error'=>0,'order_param'=>$order_param);
			} else {
				return array('error'=>1,'msg'=>'支付错误：付款失败！请联系管理员。');
			}
		} else {
			return array('error'=>1,'msg'=>'支付错误：认证签名失败！请联系管理员。');
		}
	}
	public function web_return(){
		unset($_GET['pay_type']);
		import("@.ORG.pay.TenpayComputer.ResponseHandler");
		$resHandler = new ResponseHandler();
		$resHandler->setKey($this->pay_config['pay_tenpay_partnerkey']);
		
		if($resHandler->isTenpaySign()){
			$notify_id = $resHandler->getParameter("notify_id");
			//商户订单号
			$out_trade_no = $resHandler->getParameter("out_trade_no");
			//财付通订单号
			$transaction_id = $resHandler->getParameter("transaction_id");
			//金额,以分为单位
			$total_fee = $resHandler->getParameter("total_fee");
			//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
			$discount = $resHandler->getParameter("discount");
			//支付结果
			$trade_state = $resHandler->getParameter("trade_state");
			//交易模式,1即时到账
			$trade_mode = $resHandler->getParameter("trade_mode");
				
			if("0" == $trade_state) {
				$order_id_arr = explode('_',$out_trade_no);
				
				$order_param['pay_type'] = 'tenpay';
				$order_param['is_mobile'] = '0';
				$order_param['order_type'] = $order_id_arr[0];
				$order_param['order_id'] = $order_id_arr[1];
				$order_param['is_own'] = intval($order_id_arr[2]);
				$order_param['third_id'] = $transaction_id;
				$order_param['pay_money'] = $total_fee/100;
				return array('error'=>0,'order_param'=>$order_param);
			}else {
				return array('error'=>1,'msg'=>'支付错误：付款失败！请联系管理员。');
			}
		}else {
			return array('error'=>1,'msg'=>'支付错误：认证签名失败！请联系管理员。');
		}
	}
	public function refund(){
		if(empty($this->pay_config['pay_tenpay_partnerid']) || empty($this->pay_config['pay_tenpay_partnerkey'])){
			return array('error'=>1,'msg'=>'财付通支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		// if($this->is_mobile){
			return $this->mobile_refund();
		// }else{
			// return $this->web_refund();
		// }
	}
	public function web_refund(){
		
	}
	public function mobile_refund(){
		import("@.ORG.pay.Tenpay.RequestHandler");
		import("@.ORG.pay.Tenpay.client.ClientResponseHandler");
		import("@.ORG.pay.Tenpay.client.TenpayHttpClient");
		
		/* 创建支付请求对象 */
		$reqHandler = new RequestHandler();
		//通信对象
		$httpClient = new TenpayHttpClient();
		//应答对象
		$resHandler = new ClientResponseHandler();

		/* 创建支付请求对象 */
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($this->pay_config['pay_tenpay_partnerkey']);
		$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/normalrefundquery.xml");

		$httpClient = new TenpayHttpClient();
		//应答对象
		$resHandler = new ClientResponseHandler();
		//设置支付参数 
		$reqHandler->setParameter("partner",$this->pay_config['pay_tenpay_partnerid']);  //商户号
		$reqHandler->setParameter("out_trade_no",$this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->order_info['is_own'] ? '_1' : ''));  //订单号
		$reqHandler->setParameter("input_charset", "utf-8");   	  //字符集
		
		//设置请求
		$httpClient->setTimeOut(30);	
		$httpClient->setMethod("POST");
		$httpClient->setReqContent($reqHandler->getRequestURL());
		
		//后台调用
		if($httpClient->call()){
			$resHandler->setContent($httpClient->getResContent());
			$resHandler->setKey($this->pay_config['pay_tenpay_partnerkey']);
			if($resHandler->isTenpaySign() && $resHandler->getParameter("retcode") == 0 ){
				$refund_param['refund_id'] = $resHandler->getParameter('out_refund_no_0');
				$refund_param['refund_time'] = $refundResult['timestamp'];
				return array('error'=>0,'type'=>'ok','msg'=>'退款申请成功！5到10个工作日款项会自动流入您支付时使用的银行卡内。','refund_param'=>$refund_param);
			}else{
				$refund_param['err_msg'] = '验证签名失败 或 业务错误信息:retcode= '.$resHandler->getParameter('retcode').',retmsg= '.$resHandler->getParameter('retmsg');
				$refund_param['refund_time'] = time();
				return array('error'=>1,'type'=>'fail','msg'=>'退款申请失败！如果重试多次还是失败请联系系统管理员。','refund_param'=>$refund_param);
			}
		} else {
			//有可能因为网络原因，请求已经处理，但未收到应答。
			$refund_param['err_msg'] = 'call err:'.$httpClient->getResponseCode().','.$httpClient->getErrInfo();
			$refund_param['refund_time'] = time();
			return array('error'=>1,'type'=>'fail','msg'=>'退款申请失败！如果重试多次还是失败请联系系统管理员。','refund_param'=>$refund_param);
		}
	}
}
?>