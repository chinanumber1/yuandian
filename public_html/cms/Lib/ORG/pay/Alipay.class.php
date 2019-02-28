<?php
class Alipay{
	protected $order_info;
	protected $pay_money;
	protected $pay_type;
	protected $is_mobile;
	protected $pay_config;
	protected $user_info;
	protected $base_path;
	
	public function __construct($order_info=array(),$pay_money=0,$pay_type='alipay',$pay_config=array(),$user_info=array(),$is_mobile=0){
		
		$this->base_path = APP_PATH.'Lib/ORG/pay/AlipayWap/';
		
		$this->order_info = $order_info;
		$this->pay_money  = $pay_money;
		$this->pay_type   = $pay_type;
		$this->is_mobile  = $is_mobile;
		$this->pay_config = $pay_config;
		$this->user_info  = $user_info;
	}
	public function pay(){
		if(empty($this->pay_config['pay_alipay_name']) || empty($this->pay_config['pay_alipay_pid']) || empty($this->pay_config['pay_alipay_key'])){
			//return array('error'=>1,'msg'=>'支付宝支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile){
			return $this->mobile_pay();
		}else{
			return $this->web_pay();
		}
	}
	public function mobile_pay()
	{
		import("@.ORG.pay.AlipayWap.lib.alipay_submit");
		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		$alipay_config['seller_email']	= $this->pay_config['pay_alipay_name'];
		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
		$alipay_config['sign_type']    = 'MD5';
		$alipay_config['input_charset']= 'utf-8';
		$alipay_config['transport']    = 'http';
// 		$parameter = array(
// 			"service" => "create_direct_pay_by_user",
// 			"partner" => $this->pay_config['pay_alipay_pid'],
// 			"seller_email" => $this->pay_config['pay_alipay_name'],
// 			"payment_type"	=> '1',
// 			"notify_url"	=> C('config.site_url').'/source/wap_alipay_notice.php',
// 			"return_url"	=> C('config.site_url').'/source/wap_alipay_return.php',
// 			"out_trade_no"	=> $this->order_info['order_type'].'_'.$this->order_info['order_id'],
// 			"subject"	=> '订单编号：'.$this->order_info['order_id'],
// 			"total_fee"	=> $this->pay_money,
// 			"body"	=> '订单编号：'.$this->order_info['order_id'],
// 			"show_url"	=> C('config.site_url'),
// 			"anti_phishing_key"	=> '',
// 			"exter_invoke_ip"	=> '',
// 			"_input_charset"	=> 'utf-8'
// 		);
		
		/**************************调用授权接口alipay.wap.trade.create.direct获取授权码token**************************/
			
		//返回格式
		$format = "xml";

		//返回格式
		$v = "2.0";

		//请求号
		$req_id = date('Ymdhis');
		//**req_data详细信息**
		//服务器异步通知页面路径
		if($this->order_info['order_type']=='ticket'||$this->order_info['order_type']=='guide'){
			$notify_url = C('config.site_url').'/source/wap_alipay_notice_scenic.php';
		}else{
			$notify_url = C('config.site_url').'/source/wap_alipay_notice.php';
		}
		//$notify_url = C('config.site_url').'/source/wap_alipay_notice.php';
		//echo $notify_url;die;
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		//$notify_url = 'http://test1.pigcms.cn/alipay/notify_url.php';
		//页面跳转同步通知页面路径

		//?user_params='.$query_string_base
		if($this->order_info['order_type']=='ticket'||$this->order_info['order_type']=='guide'){
			$call_back_url = C('config.site_url').'/source/wap_alipay_return_scenic.php';
		}else{
			$call_back_url = C('config.site_url').'/source/wap_alipay_return.php';
		}
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		//$call_back_url = 'http://test1.pigcms.cn/alipay/call_back_url.php';
		//操作中断返回地址
		if($this->order_info['order_type']=='ticket'||$this->order_info['order_type']=='guide'){
			$merchant_url = C('config.site_url').'/source/wap_alipay_return_scenic.php';
		}else{
			$merchant_url = C('config.site_url').'/source/wap_alipay_return.php';
		}
		//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数
		//$merchant_url = 'http://test1.pigcms.cn/alipay/break.php';
		//商户订单号d
		$out_trade_no = $this->order_info['order_type'].'_'.$this->order_info['order_id'] . '_' . $this->pay_money;
		//商户网站订单系统中唯一订单号，必填

		//订单名称
		$subject = '订单编号：'.$this->order_info['order_id'];
		//必填

		//付款金额
		$total_fee = $this->pay_money;
		//必填

		//请求业务参数详细
		$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . trim($alipay_config['seller_email']) . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
		//必填

		/************************************************************/

		//构造要请求的参数数组，无需改动
		$para_token = array(
				"service" => "alipay.wap.trade.create.direct",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);



		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);

		//URLDECODE返回的信息
		$html_text = urldecode($html_text);

		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);

		//获取request_token
		$request_token = $para_html_text['request_token'];


		/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/

		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "alipay.wap.auth.authAndExecute",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
		
		return array('error' => 0, 'form' => $html_text);
	}
	public function web_pay(){
		import("@.ORG.pay.Alipay.alipay_submit");

		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		$alipay_config['seller_email']	= $this->pay_config['pay_alipay_name'];
		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
		$alipay_config['sign_type']    = 'MD5';
		$alipay_config['input_charset']= 'utf-8';
		$alipay_config['transport']    = 'http';
		$parameter = array(
			"service" => "create_direct_pay_by_user",
			"partner" => $this->pay_config['pay_alipay_pid'],
			"seller_email" => $this->pay_config['pay_alipay_name'],
			"payment_type"	=> '1',
			"notify_url"	=> C('config.site_url').'/source/web_alipay_notice.php',
			"return_url"	=> C('config.site_url').'/source/web_alipay_return.php',
			"out_trade_no"	=> $this->order_info['order_type'].'_'.$this->order_info['order_id'],
			"subject"	=> '订单编号：'.$this->order_info['order_id'],
			"total_fee"	=> $this->pay_money,
			"body"	=> '订单编号：'.$this->order_info['order_id'],
			"show_url"	=> C('config.site_url'),
			"anti_phishing_key"	=> '',
			"exter_invoke_ip"	=> '',
			"_input_charset"	=> 'utf-8'
		);

		if($this->order_info['order_type']=='ticket'||$this->order_info['order_type']=='guide'){
			$parameter['notify_url'] = C('config.site_url').'/source/web_alipay_notice_scenic.php';
		}
		if($this->order_info['order_type']=='ticket'||$this->order_info['order_type']=='guide'){
			$parameter['return_url'] = C('config.site_url').'/source/web_alipay_return_scenic.php';
		}
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$form = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		return array('error'=>0,'form'=>$form);
	}
	//异步通知
	public function notice_url(){
		if(empty($this->pay_config['pay_alipay_name']) || empty($this->pay_config['pay_alipay_pid']) || empty($this->pay_config['pay_alipay_key'])){
			return array('error'=>1,'msg'=>'支付宝支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile){
			return $this->mobile_notice();
		}elseif($this->is_mobile==2){
			return $this->app_notice();
		}else{
			return $this->web_notice();
		}
	}
	public function mobile_notice()
	{
		unset($_POST['pay_type'], $_GET['pay_type']);
		$_POST['notify_data'] = htmlspecialchars_decode($_POST['notify_data']);
		import("@.ORG.pay.AlipayWap.lib.alipay_notify");
		
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		
		//安全检验码，以数字和字母组成的32位字符
		//如果签名方式设置为“MD5”时，请设置该参数
		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
		
		//商户的私钥（后缀是.pen）文件相对路径
		//如果签名方式设置为“0001”时，请设置该参数
		$alipay_config['private_key_path']	= $this->base_path . 'key/rsa_private_key.pem';
		
		//支付宝公钥（后缀是.pen）文件相对路径
		//如果签名方式设置为“0001”时，请设置该参数
		$alipay_config['ali_public_key_path']= $this->base_path . 'key/alipay_public_key.pem';


		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		
		
		//签名方式 不需修改
		$alipay_config['sign_type']    = 'md5';
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= 'utf-8';
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = $this->base_path . 'cacert.pem';
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		
		
// 		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		$alipay_config['seller_email']	= $this->pay_config['pay_alipay_name'];
// 		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
// 		$alipay_config['sign_type']    = 'MD5';
// 		$alipay_config['input_charset']= 'utf-8';
// 		$alipay_config['transport']    = 'http';
		
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		
		if($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代
		
			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			
			//解析notify_data
			//注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
			$doc = new DOMDocument();	
			if (strtoupper($alipay_config['sign_type']) == 'MD5') {
				$doc->loadXML($_POST['notify_data']);
			}
			
			if ($alipay_config['sign_type'] == '0001') {
				$doc->loadXML($alipayNotify->decrypt($_POST['notify_data']));
			}
			
			if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
				//商户订单号
				$out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
				//支付宝交易号
				$trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
				//交易状态
				$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
				//$pay_time = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
				$pay_money = $doc->getElementsByTagName( "price" )->item(0)->nodeValue;
				$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;

				$order_id_arr = explode('_',$out_trade_no);
				$order_param['pay_type'] = 'alipay';
				$order_param['is_mobile'] = '1';
				$order_param['order_type'] = $order_id_arr[0];
				$order_param['order_id'] = $order_id_arr[1];
				$order_param['third_id'] = $trade_no;
				$order_param['pay_money'] = $pay_money;
				$order_param['return'] = 1;
				$order_param['trade_status'] = $trade_status;

				D('Pay_async_record')->alipay_record($order_param);

				return array('error'=>0,'order_param'=>$order_param);
				
				
				if($trade_status == 'TRADE_FINISHED') {
					//判断该笔订单是否在商户网站中已经做过处理
						//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
						//如果有做过处理，不执行商户的业务程序
							
					//注意：
					//该种交易状态只在两种情况下出现
					//1、开通了普通即时到账，买家付款成功后。
					//2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
			
					//调试用，写文本函数记录程序运行情况是否正常
					//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
					
					echo "success";		//请不要修改或删除
				} else if ($trade_status == 'TRADE_SUCCESS') {
					//判断该笔订单是否在商户网站中已经做过处理
						//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
						//如果有做过处理，不执行商户的业务程序
							
					//注意：
					//该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。
			
					//调试用，写文本函数记录程序运行情况是否正常
					//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
					
					echo "success";		//请不要修改或删除
				}
			}
		
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		} else {
			return array('error'=>1);
		    //验证失败
		    echo "fail";
		
		    //调试用，写文本函数记录程序运行情况是否正常
		    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
	}
	public function web_notice(){
		exit('success');
	}
	
	//跳转通知
	public function return_url(){
		if(empty($this->pay_config['pay_alipay_name']) || empty($this->pay_config['pay_alipay_pid']) || empty($this->pay_config['pay_alipay_key'])){
			return array('error'=>1,'msg'=>'支付宝支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile){
			return $this->mobile_return();
		}else{
			return $this->web_return();
		}
	}
	public function mobile_return(){
		unset($_GET['pay_type']);
		import("@.ORG.pay.AlipayWap.lib.alipay_notify");
		
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		
		//安全检验码，以数字和字母组成的32位字符
		//如果签名方式设置为“MD5”时，请设置该参数
		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
		
		//商户的私钥（后缀是.pen）文件相对路径
		//如果签名方式设置为“0001”时，请设置该参数
		$alipay_config['private_key_path']	= $this->base_path . 'key/rsa_private_key.pem';
		
		//支付宝公钥（后缀是.pen）文件相对路径
		//如果签名方式设置为“0001”时，请设置该参数
		$alipay_config['ali_public_key_path']= $this->base_path . 'key/alipay_public_key.pem';
		
		
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		
		
		//签名方式 不需修改
		$alipay_config['sign_type']    = 'md5';
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= 'utf-8';
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = $this->base_path . 'cacert.pem';
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		
		
// 		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		$alipay_config['seller_email']	= $this->pay_config['pay_alipay_name'];
// 		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
// 		$alipay_config['sign_type']    = 'MD5';
// 		$alipay_config['input_charset']= 'utf-8';
// 		$alipay_config['transport']    = 'http';
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
	
		if($verify_result){
			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];
			//支付宝交易号
			$trade_no = $_POST['trade_no'];
			//交易状态
			$trade_status = $_POST['trade_status'];
			//金额,以分为单位
			$total_fee = $_POST['total_fee'];
				
// 			if($_POST['trade_status'] == 'TRADE_SUCCESS'){
				$order_id_arr = explode('_',$out_trade_no);				
				$order_param['pay_type'] = 'alipay';
				$order_param['is_mobile'] = '0';
				$order_param['order_type'] = $order_id_arr[0];
				$order_param['order_id'] = $order_id_arr[1];
				$order_param['third_id'] = $trade_no;
				$order_param['pay_money'] = $total_fee;
				$order_param['return'] = 1;
				return array('error'=>0,'order_param'=>$order_param);
// 			}else {
// 				return array('error'=>1,'msg'=>'支付错误：付款失败！请联系管理员。');
// 			}
		}else {
			return array('error'=>1,'msg'=>'支付错误：认证签名失败！请联系管理员。');
		}
		
	}
	
	public function app_notice($config){
		import("@.ORG.pay.AlipayApp.lib.alipay_notify");
		import("@.ORG.pay.AlipayApp.lib.alipay_rsa");
		import("@.ORG.pay.AlipayApp.lib.alipay_core");
		
		 
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
		$alipay_config['partner']		= $config['pay_alipay_pid'];

		//商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
		$alipay_config['private_key']	= $config['private_key_path'];

		//支付宝的公钥，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
		$alipay_config['alipay_public_key']= 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB';

		//异步通知接口
		$alipay_config['service']= 'mobile.securitypay.pay';
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('RSA');

		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');

		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = APP_PATH.'Lib/ORG/pay/AlipayApp/cacert.pem';

		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		
		
		
		$alipayNotify = new AlipayNotify($alipay_config);
		if($alipayNotify->getResponse($_POST['notify_id']))//判断成功之后使用getResponse方法判断是否是支付宝发来的异步通知。
		{
			if($alipayNotify->getSignVeryfy($_POST, $_POST['sign'])) {//使用支付宝公钥验签
				
				//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
				//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
				//商户订单号
				$out_trade_no = $_POST['out_trade_no'];

				//支付宝交易号
				$trade_no = $_POST['trade_no'];

				//交易状态
				$trade_status = $_POST['trade_status'];

				
				return true;
			}
			else //验证签名失败
			{
				return false;
			}
		}
		else //验证是否来自支付宝的通知失败
		{
			echo false;
		}
			
	}
	
	public function app_return_url($config){
		import("@.ORG.pay.AlipayApp.lib.alipay_notify");
		import("@.ORG.pay.AlipayApp.lib.alipay_rsa");
		import("@.ORG.pay.AlipayApp.lib.alipay_core");
		
		 
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
		$alipay_config['partner']		= $config['partner'];

		//商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
		$alipay_config['private_key']	= $config['private_key_path'];

		//支付宝的公钥，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
		$alipay_config['alipay_public_key']= 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB';

		//异步通知接口
		$alipay_config['service']= 'mobile.securitypay.pay';
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('RSA');

		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');

		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = APP_PATH.'Lib/ORG/pay/AlipayApp/cacert.pem';
		

		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';

		$alipayNotify = new AlipayNotify($alipay_config);
		if(str_replace('"','',$_POST['success']))//判断success是否为true.
		{

			//验证参数是否匹配
		
			if (str_replace('"','',$_POST['partner'])==$alipay_config['partner']&&str_replace('"','',$_POST['service'])==$alipay_config['service']) {
				//获取要校验的签名结果
				//$_POST['sign'] = $_POST['sign'];
				$sign=str_replace('"','',$_POST['sign']);

				//除去数组中的空值和签名参数,且把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
				
				$data=createLinkstring(paraFilter($_POST));

				$isSgin=false;

				//获得验签结果
				$isSgin=rsaVerify($data,$alipay_config['alipay_public_key'],$sign);

				if ($isSgin) {
					return true;
					//此处可做商家业务逻辑，建议商家以异步通知为准。
				}else {
					return false;
				}
			}
		}else{
			return false;
		}
	}
	
	public function web_return(){
		unset($_GET['pay_type']);
		import("@.ORG.pay.Alipay.alipay_notify");
		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		$alipay_config['seller_email']	= $this->pay_config['pay_alipay_name'];
		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
		$alipay_config['sign_type']    = 'MD5';
		$alipay_config['input_charset']= 'utf-8';
		$alipay_config['transport']    = 'http';
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		
		if($verify_result){
			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];
			//支付宝交易号
			$trade_no = $_POST['trade_no'];
			//交易状态
			$trade_status = $_POST['trade_status'];
			//金额,以分为单位
			$total_fee = $_POST['total_fee'];
				
			if($_POST['trade_status'] == 'TRADE_SUCCESS'){
				$order_id_arr = explode('_',$out_trade_no);				
				$order_param['pay_type'] = 'alipay';
				$order_param['is_mobile'] = '0';
				$order_param['order_type'] = $order_id_arr[0];
				$order_param['order_id'] = $order_id_arr[1];
				$order_param['third_id'] = $trade_no;
				$order_param['pay_money'] = $total_fee;
				return array('error'=>0,'order_param'=>$order_param);
			}else {
				return array('error'=>1,'msg'=>'支付错误：付款失败！请联系管理员。');
			}
		}else {
			return array('error'=>1,'msg'=>'支付错误：认证签名失败！请联系管理员。');
		}
	}
	public function query_order(){
		if(empty($this->pay_config['pay_alipay_name']) || empty($this->pay_config['pay_alipay_pid']) || empty($this->pay_config['pay_alipay_key'])){
			return array('error'=>1,'msg'=>'支付宝支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile==1){
			return $this->mobile_query_order();
		}elseif($this->is_mobile==2){
			return $this->app_query_order();
		}else{
			return $this->web_query_order();
		}
	}
	public function mobile_query_order(){
		unset($_GET['pay_type']);
		import("@.ORG.pay.AlipayWap.lib.alipay_notify");
		
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		
		//安全检验码，以数字和字母组成的32位字符
		//如果签名方式设置为“MD5”时，请设置该参数
		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
		
		//商户的私钥（后缀是.pen）文件相对路径
		//如果签名方式设置为“0001”时，请设置该参数
		$alipay_config['private_key_path']	= $this->base_path . 'key/rsa_private_key.pem';
		
		//支付宝公钥（后缀是.pen）文件相对路径
		//如果签名方式设置为“0001”时，请设置该参数
		$alipay_config['ali_public_key_path']= $this->base_path . 'key/alipay_public_key.pem';
		
		
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		
		
		//签名方式 不需修改
		$alipay_config['sign_type']    = 'md5';
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= 'utf-8';
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = $this->base_path . 'cacert.pem';
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		
// 		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		$alipay_config['seller_email']	= $this->pay_config['pay_alipay_name'];
// 		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
// 		$alipay_config['sign_type']    = 'MD5';
// 		$alipay_config['input_charset']= 'utf-8';
// 		$alipay_config['transport']    = 'http';
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		
		if($verify_result){
			//商户订单号
			$out_trade_no = $_GET['out_trade_no'];
			//支付宝交易号
			$trade_no = $_GET['trade_no'];
			//交易状态
			$trade_status = $_GET['trade_status'];
			//金额,以分为单位
			$total_fee = $_GET['total_fee'];
				
// 			if($_GET['trade_status'] == 'TRADE_SUCCESS'){
				$order_id_arr = explode('_',$out_trade_no);				
				$order_param['pay_type'] = 'alipay';
				$order_param['is_mobile'] = '1';
				$order_param['order_type'] = $order_id_arr[0];
				$order_param['order_id'] = $order_id_arr[1];
				$order_param['third_id'] = $trade_no;
				$order_param['pay_money'] =$order_id_arr[2]?$order_id_arr[2]:$total_fee;
				$order_param['return'] = 1;
				return array('error'=>0,'order_param'=>$order_param);
// 			}else {
// 				return array('error'=>1,'msg'=>'支付错误：付款失败！请联系管理员。');
// 			}
		}else {
			return array('error'=>1,'msg'=>'支付错误：认证签名失败！请联系管理员。');
		}
		
		
		
		
		require_once($this->base_path."lib/alipay_notify.class.php");
		
		
		$_POST['notify_data'] = htmlspecialchars_decode($_POST['notify_data']);
		$from = htmlentities($_GET['from']);
		/*
		 unset($_GET['g']);
		 unset($_GET['m']);
		 unset($_GET['a']);
		 unset($_GET['token']);
		 unset($_GET['wecha_id']);
		 unset($_GET['from']);
		 unset($_GET['rget']);
		 unset($_GET['user_params']);
		*/
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($this->alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		if($verify_result) {//验证成功
		
			//请在这里加上商户的业务逻辑程序代
		
			//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
				
			//解析notify_data
			//注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
			$doc = new DOMDocument();
			if ($this->alipay_config['sign_type'] == 'MD5') {
				$doc->loadXML($_POST['notify_data']);
			}
				
			if ($this->alipay_config['sign_type'] == '0001') {
				$doc->loadXML($alipayNotify->decrypt($_POST['notify_data']));
			}
				
			if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
				//商户订单号
				$out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
				//支付宝交易号
				$trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
				//交易状态
				$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
		
				if($trade_status == 'TRADE_FINISHED') {
		
					echo "success";		//请不要修改或删除
				}
				else if ($trade_status == 'TRADE_SUCCESS') {
		
					$payHandel=new payHandle($this->token,$from,'alipay');
					$orderInfo=$payHandel->beforePay($out_trade_no);
					if (empty($orderInfo['paid'])) {
						$orderInfo = $payHandel->afterPay($out_trade_no,$trade_no);
						$url = C('site_url').'/index.php?g=Wap&m='.$from.'&a=payReturn&token='.$orderInfo['token'].'&wecha_id='.$orderInfo['wecha_id'].'&rget=1&orderid='.$out_trade_no;
						file_get_contents($url);
					}
					echo "success";		//请不要修改或删除
				}
			}
		
		}
		else {
			//验证失败
			echo "fail";
		
		}
		
		
		
	}
	
	public function app_query_order(){
		
		import("@.ORG.pay.Alipay.alipay_submit");
		$alipay_config['partner']		= $this->pay_config['pay_alipay_app_pid'];
		
		//安全检验码，以数字和字母组成的32位字符
		//如果签名方式设置为“MD5”时，请设置该参数
		$alipay_config['key']			= $this->pay_config['pay_alipay_app_key'];


		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');

		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');

		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = $this->base_path . 'cacert.pem';

		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';


        //商户订单号
        $out_trade_no = $_POST['trade_no'];
		

		/************************************************************/

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "single_trade_query",
				"partner" => trim($alipay_config['partner']),
				"trade_no"	=> $trade_no,
				"out_trade_no"	=> $out_trade_no,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($parameter);

		//解析XML
		//注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
		$doc = new DOMDocument();
		$doc->loadXML($html_text);


		//解析XML
		if( ! empty($doc->getElementsByTagName( "alipay" )->item(0)->nodeValue) ) {
			$alipay = $doc->getElementsByTagName( "alipay" )->item(0)->nodeValue;
			
			echo $alipay;
		}
	}
	
	public function web_query_order(){
		unset($_GET['pay_type']);
		import("@.ORG.pay.Alipay.alipay_notify");
		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		$alipay_config['seller_email']	= $this->pay_config['pay_alipay_name'];
		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
		$alipay_config['sign_type']    = 'MD5';
		$alipay_config['input_charset']= 'utf-8';
		$alipay_config['transport']    = 'http';
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		
		if($verify_result){
			//商户订单号
			$out_trade_no = $_GET['out_trade_no'];
			//支付宝交易号
			$trade_no = $_GET['trade_no'];
			//交易状态
			$trade_status = $_GET['trade_status'];
			//金额,以分为单位
			$total_fee = $_GET['total_fee'];
				
			if($_GET['trade_status'] == 'TRADE_SUCCESS'){
				$order_id_arr = explode('_',$out_trade_no);				
				$order_param['pay_type'] = 'alipay';
				$order_param['is_mobile'] = '0';
				$order_param['order_type'] = $order_id_arr[0];
				$order_param['order_id'] = $order_id_arr[1];
				$order_param['third_id'] = $trade_no;
				$order_param['pay_money'] = $total_fee;
				return array('error'=>0,'order_param'=>$order_param);
			}else {
				return array('error'=>1,'msg'=>'支付错误：付款失败！请联系管理员。');
			}
		}else {
			return array('error'=>1,'msg'=>'支付错误：认证签名失败！请联系管理员。');
		}
	}
	public function refund()
	{
		
		$order_param['pay_type'] = 'alipay';
		$order_param['return'] = 1;
		$order_param['is_mobile'] = $this->is_mobile;
		$order_param['order_type'] = $this->order_info['order_type'];
		$order_param['order_id'] = $this->order_info['order_id'];
		$order_param['is_own'] = intval($this->order_info['is_own']);
			
		$order_param['refund_id'] = '';
		$order_param['ret_code'] = 0;
		$order_param['ret_detail'] = '退款到用户的账号余额的金额';
		$order_param['refund_time'] = time();
		
		D('User')->add_money($this->order_info['uid'], $this->pay_money, '支付宝退款 '.$this->order_info['order_name'].' 增加余额');
		
		return array('error' => 0, 'type' => 'ok','msg' => '退款申请成功！', 'refund_param' => $order_param);
		
		
		$alipay_config['seller_email']	= $this->pay_config['pay_alipay_name'];
		
		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		
		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
		
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		
		
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('gbk');
		
		$alipay_config['cacert']    = $this->base_path . 'cacert.pem';
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		
		
		
		//---------------------------------
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		
		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
		
		
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		
		
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('gbk');
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = getcwd().'\\cacert.pem';
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		
		/**************************请求参数**************************/
		
		//服务器异步通知页面路径
		$notify_url = "http://商户网关地址/refund_fastpay_by_platform_pwd-PHP-GBK/notify_url.php";
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//卖家支付宝帐户
		$seller_email = $_POST['WIDseller_email'];
		//必填
		
		//退款当天日期
		$refund_date = $_POST['WIDrefund_date'];
		//必填，格式：年[4位]-月[2位]-日[2位] 小时[2位 24小时制]:分[2位]:秒[2位]，如：2007-10-01 13:13:13
		
		//批次号
		$batch_no = $_POST['WIDbatch_no'];
		//必填，格式：当天日期[8位]+序列号[3至24位]，如：201008010000001
		
		//退款笔数
		$batch_num = $_POST['WIDbatch_num'];
		//必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）
		
		//退款详细数据
		$detail_data = $_POST['WIDdetail_data'];
		//必填，具体格式请参见接口技术文档
		
		
		/************************************************************/
		
		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "refund_fastpay_by_platform_pwd",
				"partner" => trim($alipay_config['partner']),
				"notify_url"	=> $notify_url,
				"seller_email"	=> $seller_email,
				"refund_date"	=> $refund_date,
				"batch_no"	=> $batch_no,
				"batch_num"	=> $batch_num,
				"detail_data"	=> $detail_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		echo $html_text;
		
		
		
		
		
		return array('error'=>1,'msg'=>'支付宝退款暂未开通');
		$alipay_config['seller_email']	= $this->pay_config['pay_alipay_name'];
		
		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		
		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
		
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		
		
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');
		
		$alipay_config['cacert']    = $this->base_path . 'cacert.pem';
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		
		/**************************请求参数**************************/
		
		import("@.ORG.pay.Alipay.alipay_submit");
		
		//服务器异步通知页面路径
		$notify_url = C('config.site_url').'/source/wap_alipay_refund.php';//"http://商户网关地址/refund_fastpay_by_platform_pwd-PHP-UTF-8/notify_url.php";
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//卖家支付宝帐户
		$seller_email = $this->pay_config['pay_alipay_name'];
		//必填
		
		//退款当天日期
		$refund_date = date("Y-m-d H:i:s");//$_POST['WIDrefund_date'];
		//必填，格式：年[4位]-月[2位]-日[2位] 小时[2位 24小时制]:分[2位]:秒[2位]，如：2007-10-01 13:13:13
		
		//批次号
		$batch_no = date("Ymd") . $this->order_info['order_id'] . '_' . $this->order_info['order_type'];//$_POST['WIDbatch_no'];
		//必填，格式：当天日期[8位]+序列号[3至24位]，如：201008010000001
		
		//退款笔数
		$batch_num = 1;//$_POST['WIDbatch_num'];
		//必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）
		
		//退款详细数据
		$detail_data = '购买错了';//$_POST['WIDdetail_data'];
		//必填，具体格式请参见接口技术文档
		
		
		/************************************************************/
		
		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "refund_fastpay_by_platform_pwd",
				"partner" => trim($alipay_config['partner']),
				"notify_url"	=> $notify_url,
				"seller_email"	=> $seller_email,
				"refund_date"	=> $refund_date,
				"batch_no"	=> $batch_no,
				"batch_num"	=> $batch_num,
				"detail_data"	=> $detail_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		return $html_text;
		
	}
	
	
	public function refund_verify()
	{
		//return array('error'=>1,'msg'=>'支付宝退款暂未开通');
		$alipay_config['seller_email']	= $this->pay_config['pay_alipay_name'];
		
		$alipay_config['partner']		= $this->pay_config['pay_alipay_pid'];
		
		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']			= $this->pay_config['pay_alipay_key'];
		
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		
		
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');
		
		$alipay_config['cacert']    = $this->base_path . 'cacert.pem';
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';

		import("@.ORG.pay.Alipay.alipay_notify");
		
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		if ($verify_result) {
			//批次号
			$batch_no = $_POST['batch_no'];
			
			$order_id_arr = explode('_', substr($_POST['batch_no'], 8));

			$refund_param = array('order_id' => $order_id_arr[1], 'order_type' => $order_id_arr[0]);
			
			$refund_param['success_num'] = $_POST['success_num'];
			$refund_param['result_details'] = $_POST['result_details'];
			
			return array('error'=>0,'type'=>'ok','msg'=>'退款申请成功！请注意查收“微信支付”给您发的退款通知。','refund_param'=>$refund_param);
			
		} else {
			return array('error'=>1,'msg'=>'退款错误：认证签名失败！请联系管理员。');
		}
	}
}
?>