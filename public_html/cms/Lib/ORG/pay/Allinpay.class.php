<?php
class Allinpay{
	protected $order_info;
	protected $pay_money;
	protected $pay_type;
	protected $is_mobile;
	protected $pay_config;
	protected $user_info;
	protected $payUrlDomain;
	protected $refundUrl;

	public function __construct($order_info,$pay_money,$pay_type,$pay_config,$user_info,$is_mobile=0){
		$this->order_info = $order_info;
		$this->pay_money  = $pay_money;
		$this->pay_type   = $pay_type;
		$this->is_mobile   = $is_mobile;
		$this->pay_config = $pay_config;
		$this->user_info  = $user_info;
		$this->payUrlDomain = ($_SERVER['HTTP_HOST'] != '192.168.0.254' && $_SERVER['HTTP_HOST'] != 'www.group.com' && $_SERVER['HTTP_HOST'] != 'hf.group.com' && $_SERVER['HTTP_HOST'] != 'waimaitest.weihubao.com') ? 'https://service.allinpay.com' : 'http://ceshi.allinpay.com';
		$this->refundUrl = ($_SERVER['HTTP_HOST'] != '192.168.0.254' && $_SERVER['HTTP_HOST'] != 'www.group.com' && $_SERVER['HTTP_HOST'] != 'hf.group.com' && $_SERVER['HTTP_HOST'] != 'waimaitest.weihubao.com') ? 'service.allinpay.com' : 'ceshi.allinpay.com';
	}
	public function pay(){
		if(empty($this->pay_config['pay_allinpay_merchantid']) || empty($this->pay_config['pay_allinpay_merchantkey'])){
			return array('error'=>1,'msg'=>'通联支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
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
		if($this->pay_config['pay_allinpay_merchantid_h5']){
			$this->pay_config['pay_allinpay_merchantid'] = $this->pay_config['pay_allinpay_merchantid_h5'];
			$this->pay_config['pay_allinpay_merchantkey'] = $this->pay_config['pay_allinpay_merchantkey_h5'];
			$this->payUrlDomain = 'https://cashier.allinpay.com';
		  
			$allinpay_user = D('Pay_allinpay_user')->where(array('uid'=>$_SESSION['user']['uid']))->find();
			if(!$allinpay_user){
				$reg_param['signType'] = '0';
				$reg_param['merchantId'] = $this->pay_config['pay_allinpay_merchantid'];
				$reg_param['partnerUserId'] = $_SESSION['user']['uid'];
				$bufSignSrc = '&signType='. $reg_param['signType'] . '&';
				$bufSignSrc.= 'merchantId='. $reg_param['merchantId'] . '&';
				$bufSignSrc.= 'partnerUserId='. $reg_param['partnerUserId'] . '&';
				$bufSignSrc.= 'key='. $this->pay_config['pay_allinpay_merchantkey'] . '&';
				$reg_param['signMsg'] = strtoupper(md5($bufSignSrc));
				import('ORG.Net.Http');
				$http = new Http();
				$return = Http::curlPost($this->payUrlDomain.'/usercenter/merchant/UserInfo/reg.do', http_build_query($reg_param));
				//echo $this->payUrlDomain.'/usercenter/merchant/UserInfo/reg.do<br/><br/>';
				//echo $bufSignSrc;
				//dump($reg_param);
				//dump($return);die;
				if($return['userId']){
					$allinpay_user = array('uid'=>$_SESSION['user']['uid'],'allinpay_uid'=>$return['userId']);
					if(!D('Pay_allinpay_user')->data($allinpay_user)->add()){
						return array('error'=>1,'msg'=>'通联支付注册用户失败，请重试');
					}
				}else{
					return array('error'=>1,'msg'=>'支付失败，通联返回:'.$return['describe']);
				}
			}
		}
		import('@.ORG.pay.Allinpay.allinpayCore');
		$allinpayClass = new allinpayCore();
		$allinpayClass->setParameter('payUrl',$this->payUrlDomain.'/mobilepayment/mobile/SaveMchtOrderServlet.action'); //提交地址
		if($this->pay_config['is_own'] && $this->order_info['mer_id']){
			$allinpayClass->setParameter('pickupUrl',C('config.site_url').'/wap.php?g=Index&c=Pay&a=return_url&pay_type=allinpay&is_mobile=1&own_mer_id='.$this->order_info['mer_id']); //跳转通知地址
		}elseif($this->order_info['scenic_id']){
			$allinpayClass->setParameter('pickupUrl',C('config.site_url').'/wap.php?g=Wap&c=Scenic_pay&a=return_url&pay_type=allinpay&is_mobile=1'); //跳转通知地址
		}else{
			$allinpayClass->setParameter('pickupUrl',C('config.site_url').'/wap.php?g=Index&c=Pay&a=return_url&pay_type=allinpay&is_mobile=1'); //跳转通知地址
		}
		$allinpayClass->setParameter('receiveUrl',C('config.site_url').'/wap.php?g=Index&c=Pay&a=notify_url&pay_type=allinpay&is_mobile=1'); //异步通知地址
		$allinpayClass->setParameter('merchantId',$this->pay_config['pay_allinpay_merchantid']); //商户号
		$allinpayClass->setParameter('orderNo',$this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_'.$this->pay_config['is_own'] : '')); //订单号
		$allinpayClass->setParameter('orderAmount',floatval($this->pay_money*100)); //订单金额(单位分)
		$allinpayClass->setParameter('orderDatetime',date('YmdHis',$_SERVER['REQUEST_TIME'])); //订单提交时间
		$allinpayClass->setParameter('productName',($this->order_info['order_name'] ? $this->order_info['order_name'] : $this->order_info['order_type'].'_'.$this->order_info['order_id'])); //商品名称
		$allinpayClass->setParameter('payType',0); //支付方式
		$allinpayClass->setParameter('key',$this->pay_config['pay_allinpay_merchantkey']); //支付方式
		if($this->pay_config['pay_allinpay_merchantid_h5']){
			$allinpayClass->setParameter('ext1','<USER>'.$allinpay_user['allinpay_uid'].'</USER>');
		}
		

		//开始跳转支付
		$form = $allinpayClass->sendRequestForm();

		return array('error'=>0,'form'=>$form);
	}
	public function web_pay(){
		import('@.ORG.pay.Allinpay.allinpayCore');
		$allinpayClass = new allinpayCore();
		$allinpayClass->setParameter('payUrl',$this->payUrlDomain.'/gateway/index.do'); //提交地址

		if($this->pay_config['is_own'] && $this->order_info['mer_id']){
			$allinpayClass->setParameter('pickupUrl',C('config.site_url').'/index.php?g=Index&c=Pay&a=return_url&pay_type=allinpay&own_mer_id='.$this->order_info['mer_id']); //跳转通知地址
		}elseif($this->order_info['scenic_id']){
			$allinpayClass->setParameter('pickupUrl',C('config.site_url').'/scenic.php?g=Scenic&c=Scenic_pay&a=return_url&pay_type=allinpay'); //跳转通知地址
		}elseif($this->order_info['order_type']=='merrecharge'){
			$allinpayClass->setParameter('pickupUrl',C('config.site_url').'/merchant.php?g=Merchant&c=Pay&a=return_url&pay_type=allinpay'); //跳转通知地址
		}elseif($this->order_info['order_type']=='strecharge'){
			$allinpayClass->setParameter('pickupUrl',C('config.site_url').'/merchant.php?g=Merchant&c=StorePay&a=return_url&pay_type=allinpay'); //跳转通知地址
		}elseif($this->order_info['order_type']=='sqrecharge'){
			$allinpayClass->setParameter('pickupUrl',C('config.site_url').'/shequ.php?g=House&c=Pay&a=return_url&pay_type=allinpay'); //跳转通知地址
		}else{
			$allinpayClass->setParameter('pickupUrl',C('config.site_url').'/index.php?g=Index&c=Pay&a=return_url&pay_type=allinpay'); //跳转通知地址
		}

		if($this->order_info['order_type']=='merrecharge'){
			$allinpayClass->setParameter('receiveUrl',C('config.site_url').'/Merchant.php?g=Merchant&c=Pay&a=notify_url&pay_type=allinpay'); //异步通知地址

		}elseif($this->order_info['order_type']=='strecharge') {
			$allinpayClass->setParameter('receiveUrl', C('config.site_url') . '/Merchant.php?g=Merchant&c=StorePay&a=notify_url&pay_type=allinpay'); //异步通知地址
		}elseif($this->order_info['order_type']=='sqrecharge') {
			$allinpayClass->setParameter('receiveUrl', C('config.site_url') . '/shequ.php?g=House&c=Pay&a=notify_url&pay_type=allinpay'); //异步通知地址
		}else{

			$allinpayClass->setParameter('receiveUrl',C('config.site_url').'/index.php?g=Index&c=Pay&a=notify_url&pay_type=allinpay'); //异步通知地址
		}
		$allinpayClass->setParameter('merchantId',$this->pay_config['pay_allinpay_merchantid']); //商户号
		$allinpayClass->setParameter('orderNo',$this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : '')); //订单号
		$allinpayClass->setParameter('orderAmount',floatval($this->pay_money*100)); //订单金额(单位分)
		$allinpayClass->setParameter('orderDatetime',date('YmdHis',$_SERVER['REQUEST_TIME'])); //订单提交时间
		$allinpayClass->setParameter('productName',($this->order_info['order_name'] ? $this->order_info['order_name'] : $this->order_info['order_type'].'_'.$this->order_info['order_id'])); //商品名称
		$allinpayClass->setParameter('payType',0); //支付方式
		$allinpayClass->setParameter('key',$this->pay_config['pay_allinpay_merchantkey']); //支付KEY

		//开始跳转支付
		$form = $allinpayClass->sendRequestForm();

		return array('error'=>0,'form'=>$form);
	}
	public function app_pay(){
		import('@.ORG.pay.Allinpay.allinpayCore');
		$allinpayClass = new allinpayCore();
		$allinpayClass->setParameter('payUrl',$this->payUrlDomain.'/mobilepayment/mobile/SaveMchtOrderServlet.action'); //提交地址
		if($this->pay_config['is_own'] && $this->order_info['mer_id']){
			$allinpayClass->setParameter('pickupUrl',C('config.site_url').'/api.php?g=Mobile&c=Pay&a=return_url&pay_type=allinpay&is_mobile=2&own_mer_id='.$this->order_info['mer_id']); //跳转通知地址
		}else{
			$allinpayClass->setParameter('pickupUrl',C('config.site_url').'/api.php?g=Mobile&c=Pay&a=return_url&pay_type=allinpay&is_mobile=2'); //跳转通知地址
		}
		$allinpayClass->setParameter('receiveUrl',C('config.site_url').'/api.php?g=Mobile&c=Pay&a=notify_url&pay_type=allinpay&is_mobile=2'); //异步通知地址
		$allinpayClass->setParameter('merchantId',$this->pay_config['pay_allinpay_merchantid']); //商户号
		$allinpayClass->setParameter('orderNo',$this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : '')); //订单号
		$allinpayClass->setParameter('orderAmount',floatval($this->pay_money*100)); //订单金额(单位分)
		$allinpayClass->setParameter('orderDatetime',date('YmdHis',$_SERVER['REQUEST_TIME'])); //订单提交时间
		$allinpayClass->setParameter('productName',($this->order_info['order_name'] ? $this->order_info['order_name'] : $this->order_info['order_type'].'_'.$this->order_info['order_id'])); //商品名称
		$allinpayClass->setParameter('payType',0); //支付方式
		$allinpayClass->setParameter('key',$this->pay_config['pay_allinpay_merchantkey']); //支付方式

		//开始跳转支付
		$form = $allinpayClass->sendRequestForm();

		return array('error'=>0,'form'=>$form);
	}

	public function notice_url(){
		if(empty($this->pay_config['pay_allinpay_merchantid']) || empty($this->pay_config['pay_allinpay_merchantkey'])){
			return array('error'=>1,'msg'=>'通联支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
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
		if(empty($this->pay_config['pay_allinpay_merchantid']) || empty($this->pay_config['pay_allinpay_merchantkey'])){
			return array('error'=>1,'msg'=>'通联支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile && $this->pay_config['pay_allinpay_merchantid_h5']){
			$this->pay_config['pay_allinpay_merchantid'] = $this->pay_config['pay_allinpay_merchantid_h5'];
			$this->pay_config['pay_allinpay_merchantkey'] = $this->pay_config['pay_allinpay_merchantkey_h5'];
		}
		$_POST['ext1'] = htmlspecialchars_decode($_POST['ext1']);
		//dump($_POST);die;
	
		import('@.ORG.pay.Allinpay.allinpayCore');
		$allinpayClass = new allinpayCore();
		$verify_result = $allinpayClass->verify_pay($this->pay_config['pay_allinpay_merchantkey']);

		if(empty($verify_result['error'])){
			$order_id_arr = explode('_',$verify_result['order_id']);

			$order_param['pay_type'] = 'allinpay';
			$order_param['is_mobile'] = $this->is_mobile;
			$order_param['order_type'] = $order_id_arr[0];
			$order_param['order_id'] = $order_id_arr[1];
			$order_param['is_own'] = intval($order_id_arr[2]);
			$order_param['third_id'] = $verify_result['paymentOrderId'];
			$order_param['pay_money'] = $verify_result['pay_money'];
			return array('error'=>0,'order_param'=>$order_param);
		}else{
			return array('error'=>1,'msg'=>$verify_result['msg']);
		}
	}
	public function refund(){
		if(empty($this->pay_config['pay_allinpay_merchantid']) || empty($this->pay_config['pay_allinpay_merchantkey'])){
			return array('error'=>1,'msg'=>'通联支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		import('@.ORG.pay.Allinpay.allinpayCore');
		$allinpayClass = new allinpayCore();
		$allinpayClass->setParameter('refundHost',$this->refundUrl); //提交域
		$allinpayClass->setParameter('key',$this->pay_config['pay_allinpay_merchantkey']); //支付KEY
		$allinpayClass->setParameter('merchantId',$this->pay_config['pay_allinpay_merchantid']); //商户号
		$allinpayClass->setParameter('orderNo',$this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->order_info['is_own'] ? '_1' : '')); //订单号
		$allinpayClass->setParameter('orderDatetime',date('YmdHis',$this->order_info['submit_order_time'])); //提交订单时间
		$allinpayClass->setParameter('refundAmount',$this->pay_money*100); //订单号


		$verify_result = $allinpayClass->refund($this->order_info,$this->pay_money,$this->pay_config);
		return $verify_result;
	}
}
?>