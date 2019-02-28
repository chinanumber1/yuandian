<?php
class Unionpay
{
	protected $order_info;
	protected $pay_money;
	protected $is_mobile;
	protected $pay_config;
	protected $user_info;
	protected $payUrlDomain;
	protected $refundUrl;
	protected $orderNo;
	
	protected $cert_path = '';
	
	protected $middle_cert_file = '';
	protected $root_cert_file = '';
	protected $enc_cert_file = '';
	
	protected $siteUrl = 'https://gateway.95516.com';
	
	protected $order_types = array('group' => 'G', 'meal' => 'M', 'takeout' => 'T', 'food' => 'F', 'foodPad' => 'O', 'shop' => 'H', 'weidian' => 'W', 'recharge' => 'R', 'appoint' => 'A', 'waimai' => 'I', 'wxapp' => 'X', 'store' => 'S');
	public function __construct($order_info,$pay_money,$pay_type = '', $pay_config,$user_info,$is_mobile=0)
	{
		$this->order_info = $order_info;
		$this->pay_money  = $pay_money;
		$this->is_mobile   = $is_mobile;
		$this->pay_config = $pay_config;
		$this->user_info  = $user_info;
		
		switch($order_info['order_type'])
		{
			case 'group':
				$this->orderNo = 'G';
				break;
			case 'meal':
				$this->orderNo = 'M';
				break;
			case 'takeout':
				$this->orderNo = 'T';
				break;
			case 'food':
				$this->orderNo = 'F';
				break;
			case 'foodPad':
				$this->orderNo = 'O';
				break;
			case 'weidian':
				$this->orderNo = 'W';
				break;
			case 'recharge':
				$this->orderNo = 'R';
				break;
			case 'appoint':
				$this->orderNo = 'A';
				break;
			case 'waimai':
				$this->orderNo = 'I';
				break;
			case 'wxapp':
				$this->orderNo = 'X';
				break;
			case 'store':
				$this->orderNo = 'S';
				break;
			case 'shop':
				$this->orderNo = 'H';
				break;
			default:
		}
		$this->orderNo .= $this->pay_config['is_own'] ? '1' : '0';
		$this->orderNo .= $this->order_info['order_id'];
		$this->pay_config['pay_unionpay_merchantid'] = '827611073790001';
		$this->pay_config['pay_unionpay_merchantkey'] = 123456;
		$this->pay_config['union_sign_cert_path'] = dirname(__FILE__) . '/UnionPay/certs/acp_youfu_sign.pfx';
		$this->cert_path = dirname(__FILE__) . '/UnionPay/certs';
		
		$this->middle_cert_file = dirname(__FILE__) . '/UnionPay/certs/acp_prod_middle.cer';
		$this->root_cert_file= dirname(__FILE__) . '/UnionPay/certs/acp_prod_root.cer';
		$this->enc_cert_file= dirname(__FILE__) . '/UnionPay/certs/acp_prod_enc.cer';
	}
	
	public function pay()
	{
		if(empty($this->pay_config['pay_unionpay_merchantid']) || empty($this->pay_config['pay_unionpay_merchantkey'])){
			return array('error'=>1,'msg'=>'银联支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile == 2){
			return $this->app_pay();
		}elseif($this->is_mobile){
			return $this->mobile_pay();
		}else{
			return $this->web_pay();
		}
	}
	
	public function mobile_pay()
	{
		$url = $this->siteUrl . '/gateway/api/frontTransReq.do';
		$txnTime = date('YmdHis');
		$params = array(
				//以下信息非特殊情况不需要改动
				'version' => '5.1.0',                 //版本号
				'encoding' => 'utf-8',				  //编码方式
				'txnType' => '01',				      //交易类型
				'txnSubType' => '01',				  //交易子类
				'bizType' => '000201',				  //业务类型
				'frontUrl' =>  C('config.site_url').'/wap.php?c=Pay&a=return_url&pay_type=unionpay&is_mobile=1&urltype=front',  //前台通知地址
				'backUrl' => C('config.site_url').'/wap.php?c=Pay&a=return_url&pay_type=unionpay&is_mobile=1&urltype=back',	  //后台通知地址
				'signMethod' => '01',	              //签名方法
				'channelType' => '08',	              //渠道类型，07-PC，08-手机
				'accessType' => '0',		          //接入类型
				'currencyCode' => '156',	          //交易币种，境内商户固定156
		
				//TODO 以下信息需要填写
				'merId' => $this->pay_config['pay_unionpay_merchantid'],		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
				'orderId' => $this->orderNo,	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
				'txnTime' => $txnTime,	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
				'txnAmt' => floatval($this->pay_money * 100),	//交易金额，单位分，此处默认取demo演示页面传递的参数
				// 		'reqReserved' =>'透传信息',        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
		
				//TODO 其他特殊用法请查看 special_use_purchase.php
		);
		import('@.ORG.pay.UnionPay.acp_service');
		AcpService::sign($params, $this->pay_config['union_sign_cert_path'], $this->pay_config['pay_unionpay_merchantkey']);
		$html_form = AcpService::createAutoFormHtml($params, $url);
		return array('error'=>0,'form'=>$html_form);
	}
	
	public function web_pay()
	{
	    $url = $this->siteUrl . '/gateway/api/frontTransReq.do';
		$txnTime = date('YmdHis');
		import('@.ORG.pay.UnionPay.acp_service');
		$params = array(
				//以下信息非特殊情况不需要改动
				'version' => '5.1.0',                 //版本号
				'encoding' => 'utf-8',				  //编码方式
				'txnType' => '01',				      //交易类型
				'txnSubType' => '01',				  //交易子类
				'bizType' => '000201',				  //业务类型
				'frontUrl' =>  C('config.site_url') . '/index.php?g=Index&c=Pay&a=return_url&pay_type=unionpay&urltype=front',  //前台通知地址
				'backUrl' => C('config.site_url') . '/index.php?g=Index&c=Pay&a=return_url&pay_type=unionpay&urltype=back',	  //后台通知地址
				'signMethod' => '01',	              //签名方法
				'channelType' => '08',	              //渠道类型，07-PC，08-手机
				'accessType' => '0',		          //接入类型
				'currencyCode' => '156',	          //交易币种，境内商户固定156
		
				//TODO 以下信息需要填写
				'merId' => $this->pay_config['pay_unionpay_merchantid'],		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
				'orderId' => $this->orderNo,	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
				'txnTime' => $txnTime,	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
				'txnAmt' => floatval($this->pay_money * 100),	//交易金额，单位分，此处默认取demo演示页面传递的参数
				// 		'reqReserved' =>'透传信息',        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
		
				//TODO 其他特殊用法请查看 special_use_purchase.php
		);
		AcpService::sign($params, $this->pay_config['union_sign_cert_path'], $this->pay_config['pay_unionpay_merchantkey']);
		$html_form = AcpService::createAutoFormHtml($params, $url);
		return array('error' => 0,'form' => $html_form);
	}
	
	public function app_pay()
	{}

	public function notice_url()
	{
		if (empty($this->pay_config['pay_unionpay_merchantid']) || empty($this->pay_config['pay_unionpay_merchantkey'])) {
			return array('error'=>1,'msg'=>'银联支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile){
			return $this->mobile_notice();
		}else{
			return $this->web_notice();
		}
	}
	public function mobile_notice()
	{
		exit('success');
	}
	public function web_notice()
	{
		exit('success');
	}
	
	
	public function return_url()
	{
		if (isset ($_POST ['signature'])) {
			import('@.ORG.pay.UnionPay.acp_service');
			if (AcpService::validate($_POST, $this->middle_cert_file, $this->root_cert_file, $this->cert_path)) {
				$respCode = $_POST['respCode']; //判断respCode=00或A6即可认为交易成功
				if ($respCode == '00' || $respCode == 'A6') {
					$types = array_flip($this->order_types);
					
					$third_id = $_POST['queryId'];
					$order_id = $_POST['orderId'];
					$t = substr($order_id, 0, 1);
					$order_type = $types[$t];
					$is_own = substr($order_id, 1, 1);
					$order_id = substr($order_id, 2);
					$pay_money = floatval($_POST['txnAmt']/100);
					
					$order_param['pay_time'] = $_POST['txnTime'];
					$order_param['pay_type'] = 'unionpay';
					$order_param['is_mobile'] = $this->is_mobile;
					$order_param['order_type'] = $order_type;
					$order_param['order_id'] = $order_id;
					$order_param['is_own'] = intval($is_own);
					$order_param['third_id'] = $third_id;
					$order_param['pay_money'] = $pay_money;
					return array('error' => 0,'order_param' => $order_param);
				} else {
					return array('error' => 1, 'msg' => $_POST['respMsg']);
				}
			} else {
				return array('error' => 1, 'msg' => '验签失败');
			}
		} else {
			return array('error' => 1, 'msg' => '签名为空');
		}
	}
	
	
	public function refund()
	{
	    $url = $this->siteUrl . '/gateway/api/backTransReq.do';
		
		if (empty($this->pay_config['pay_unionpay_merchantid']) || empty($this->pay_config['pay_unionpay_merchantkey'])) {
			return array('error'=>1,'msg'=>'银联支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}

		$txnTime = date('YmdHis', $this->order_info['pay_time']);
		if (date('Ymd') == date('Ymd', $this->order_info['pay_time'])) {
			$params = array(
					//以下信息非特殊情况不需要改动
					'version' => '5.1.0',		      //版本号
					'encoding' => 'utf-8',		      //编码方式
					'signMethod' => '01',		      //签名方法
					'txnType' => '31',		          //交易类型
					'txnSubType' => '00',		      //交易子类
					'bizType' => '000201',		      //业务类型
					'accessType' => '0',		      //接入类型
					'channelType' => '07',		      //渠道类型
					'backUrl' => C('config.site_url').'/wap.php?g=Wap&c=My&a=refund_back', //后台通知地址
			
					//TODO 以下信息需要填写
					'orderId' => $this->orderNo . 'T',	    //商户订单号，8-32位数字字母，不能含“-”或“_”，可以自行定制规则，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
					'merId' => $this->pay_config['pay_unionpay_merchantid'],			//商户代码，请改成自己的测试商户号，此处默认取demo演示页面传递的参数
					'origQryId' => $this->order_info['third_id'], //原消费的queryId，可以从查询接口或者通知接口中获取，此处默认取demo演示页面传递的参数
					'txnTime' => $txnTime,	    //订单发送时间，格式为YYYYMMDDhhmmss，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
					'txnAmt' => floatval($this->pay_money * 100),       //交易金额，消费撤销时需和原消费一致，此处默认取demo演示页面传递的参数
					// 		'reqReserved' =>'透传信息',            //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
			);
		} else {
			//退款次日
			$params = array(
					//以下信息非特殊情况不需要改动
					'version' => '5.1.0',		      //版本号
					'encoding' => 'utf-8',		      //编码方式
					'signMethod' => '01',		      //签名方法
					'txnType' => '04',		          //交易类型
					'txnSubType' => '00',		      //交易子类
					'bizType' => '000201',		      //业务类型
					'accessType' => '0',		      //接入类型
					'channelType' => '07',		      //渠道类型
					'backUrl' => C('config.site_url').'/wap.php?g=Wap&c=My&a=refund_back', //后台通知地址
			
					//TODO 以下信息需要填写
					'orderId' => $this->orderNo . 'T',	    //商户订单号，8-32位数字字母，不能含“-”或“_”，可以自行定制规则，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
					'merId' => $this->pay_config['pay_unionpay_merchantid'],	        //商户代码，请改成自己的测试商户号，此处默认取demo演示页面传递的参数
					'origQryId' => $this->order_info['third_id'], //原消费的queryId，可以从查询接口或者通知接口中获取，此处默认取demo演示页面传递的参数
					'txnTime' => $txnTime,	    //订单发送时间，格式为YYYYMMDDhhmmss，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
					'txnAmt' => floatval($this->pay_money * 100),       //交易金额，退货总金额需要小于等于原消费
					// 		'reqReserved' =>'透传信息',            //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
			);
		}
		import('@.ORG.pay.UnionPay.acp_service');
		AcpService::sign($params, $this->pay_config['union_sign_cert_path'], $this->pay_config['pay_unionpay_merchantkey'] ); // 签名
		
		$result_arr = AcpService::post($params, $url);


		if (count($result_arr) <= 0) { //没收到200应答的情况
			$refund_param['err_msg'] = 'POST请求失败';
			$refund_param['refund_time'] = time();
			$refund_param['refund_id'] = isset($result_arr["queryId"]) ? $result_arr["queryId"] : '';
			return array('error' => 1,'type' => 'fail','msg' => '退款申请失败！如果重试多次还是失败请联系系统管理员。', 'refund_param' => $refund_param);
		}
		
		if (!AcpService::validate ($result_arr, $this->middle_cert_file, $this->root_cert_file, $this->cert_path)) {
			$refund_param['err_msg'] = '应答报文验签失败';
			$refund_param['refund_time'] = time();
			$refund_param['refund_id'] = isset($result_arr["queryId"]) ? $result_arr["queryId"] : '';
			return array('error' => 1,'type' => 'fail','msg' => '退款申请失败！如果重试多次还是失败请联系系统管理员。', 'refund_param' => $refund_param);
		}
		
		if ($result_arr["respCode"] == "00") {
			//交易已受理，等待接收后台通知更新订单状态，如果通知长时间未收到也可发起交易状态查询
			$refund_param['err_msg'] = '受理成功';
			$refund_param['refund_id'] = $result_arr["queryId"];
			$refund_param['refund_time'] = time();
			return array('error' => 0, 'type' => 'ok', 'msg' => '退款申请成功！请注意查收“微信支付”给您发的退款通知。', 'refund_param' => $refund_param);
		} elseif ($result_arr["respCode"] == "03" || $result_arr["respCode"] == "04" || $result_arr["respCode"] == "05" ){
			$refund_param['err_msg'] = '处理超时，请稍后查询';
			$refund_param['refund_time'] = time();
			$refund_param['refund_id'] = isset($result_arr["queryId"]) ? $result_arr["queryId"] : '';
			return array('error' => 1,'type' => 'fail','msg' => '退款申请失败！如果重试多次还是失败请联系系统管理员。', 'refund_param' => $refund_param);
		} else {
			$refund_param['err_msg'] = $result_arr["respMsg"];
			$refund_param['refund_time'] = time();
			$refund_param['refund_id'] = isset($result_arr["queryId"]) ? $result_arr["queryId"] : '';
			return array('error' => 1,'type' => 'fail','msg' => '退款申请失败！如果重试多次还是失败请联系系统管理员。', 'refund_param' => $refund_param);
		}
	}
	
	/*查找微信订单信息*/
	public function query_order()
	{
	    $url = $this->siteUrl . '/gateway/api/queryTrans.do';
		
		if (empty($this->pay_config['pay_unionpay_merchantid']) || empty($this->pay_config['pay_unionpay_merchantkey'])) {
			return array('error'=>1,'msg'=>'银联支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		$params = array(
				//以下信息非特殊情况不需要改动
				'version' => '5.0.0',		  //版本号
				'encoding' => 'utf-8',		  //编码方式
				'signMethod' => '01',		  //签名方法
				'txnType' => '00',		      //交易类型
				'txnSubType' => '00',		  //交易子类
				'bizType' => '000000',		  //业务类型
				'accessType' => '0',		  //接入类型
				'channelType' => '07',		  //渠道类型
		
				//TODO 以下信息需要填写
				'orderId' => $this->orderNo,	//请修改被查询的交易的订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数
				'merId' => $this->pay_config['pay_unionpay_merchantid'],	    //商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
				'txnTime' => $this->order_info['pay_time'],	//请修改被查询的交易的订单发送时间，格式为YYYYMMDDhhmmss，此处默认取demo演示页面传递的参数
		);
		
		import('@.ORG.pay.UnionPay.acp_service');
		AcpService::sign($params, $this->pay_config['union_sign_cert_path'], $this->pay_config['pay_unionpay_merchantkey']); // 签名
		
		$result_arr = AcpService::post ($params, $url);
		if (count($result_arr) <= 0) { //没收到200应答的情况
			return array('error' => 1, 'msg' => '通信出错');
		}
		
		if (!AcpService::validate($result_arr, $this->middle_cert_file, $this->root_cert_file, $this->cert_path)) {
			return array('error' => 1, 'msg' => '应答报文验签失败');
		}
		
		if ($result_arr["respCode"] == "00") {
			if ($result_arr["origRespCode"] == "00") {
				$pay_money = floatval($result_arr['txnAmt']/100);
				$order_param['pay_type'] = 'unionpay';
				$order_param['is_mobile'] = $this->is_mobile;
				$order_param['order_type'] = $this->order_info['order_type'];
				$order_param['order_id'] = $this->order_info['order_id'];
				$order_param['is_own'] = intval($this->pay_config['is_own']);
				$order_param['third_id'] = $result_arr['queryId'];
				$order_param['pay_money'] = $pay_money;
				return array('error'=>0,'order_param'=>$order_param);
			
			} elseif ($result_arr["origRespCode"] == "03" || $result_arr["origRespCode"] == "04" || $result_arr["origRespCode"] == "05") {
				return array('error' => 1, 'msg' => "交易处理中，请稍微查询。");
			} else {
				return array('error' => 1, 'msg' => "交易失败：" . $result_arr["origRespMsg"] . "");
			}
		} elseif ($result_arr["respCode"] == "03" || $result_arr["respCode"] == "04" || $result_arr["respCode"] == "05" ){
			return array('error' => 1, 'msg' => '处理超时，请稍微查询');
		} else {
			return array('error' => 1, 'msg' => "失败：" . $result_arr["respMsg"] . "");
		}
	}
}
?>