<?php
class Weixin{
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
		$this->is_mobile   = $is_mobile;
		$this->pay_config = $pay_config;
		$this->user_info  = $user_info;
	}
	public function pay($ticket=null, $deviceId=null){
		if(empty($this->pay_config['pay_weixin_appid']) || empty($this->pay_config['pay_weixin_mchid']) || empty($this->pay_config['pay_weixin_key']) || empty($this->pay_config['pay_weixin_appsecret'])){
			return array('error'=>1,'msg'=>'微信支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile == 2){
			return $this->app_pay($ticket, $deviceId);
		}elseif($this->is_mobile){
			return $this->mobile_pay();
		}else{
			return $this->web_pay();
		}
	}
	public function web_pay(){
		import('@.ORG.pay.Weixinnewpay.WxPayPubHelper');
		//使用jsapi接口
		$jsApi = new JsApi_pub($this->pay_config['pay_weixin_appid'],$this->pay_config['pay_weixin_mchid'],$this->pay_config['pay_weixin_key'],$this->pay_config['pay_weixin_appsecret']);
		//使用统一支付接口
		$unifiedOrder = new UnifiedOrder_pub($this->pay_config['pay_weixin_appid'],$this->pay_config['pay_weixin_mchid'],$this->pay_config['pay_weixin_key'],$this->pay_config['pay_weixin_appsecret']);
		$unifiedOrder->setParameter("body",($this->pay_config['is_own'] ? '' : '').'订单号：'.$this->order_info['order_type'].'_'.$this->order_info['order_id']);//商品描述
		//自定义订单号，此处仅作举例
		$unifiedOrder->setParameter("out_trade_no",$this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : ''));//商户订单号 
		$unifiedOrder->setParameter("total_fee",floatval($this->pay_money*100));//总金额
		if($this->pay_config['sub_mch_id']){
			$unifiedOrder->setParameter("sub_mch_id",$this->pay_config['sub_mch_id']);
			$unifiedOrder->setParameter("openid",$_SESSION['openid']);
		}else{
			//if($this->order_info['order_type']!='merrecharge') {
			//	$unifiedOrder->setParameter("openid", ($this->pay_config['is_own'] ? $_SESSION['open_authorize_openid'] : $_SESSION['openid']));//用户微信唯一标识
			//}
		}
		if($this->order_info['order_type']=='merrecharge'){
			$unifiedOrder->setParameter("notify_url",C('config.site_url').'/source/web_weixin_mer_notice.php');//通知地址
		}elseif($this->order_info['order_type']=='sqrecharge'){
			$unifiedOrder->setParameter("notify_url",C('config.site_url').'/source/web_weixin_sq_notice.php');//通知地址
		}elseif($this->order_info['order_type']=='strecharge'){
			$unifiedOrder->setParameter("notify_url",C('config.site_url').'/source/web_weixin_st_notice.php');//通知地址
		}else if($this->order_info['order_type']=='ticket' || $this->order_info['order_type']=='guide'){
			$unifiedOrder->setParameter("notify_url",C('config.site_url').'/source/web_weixin_jq_notice.php');//通知地址
		}else{
			$unifiedOrder->setParameter("notify_url",C('config.site_url').'/source/web_weixin_notice.php');//通知地址
		}
		$unifiedOrder->setParameter("trade_type","NATIVE");//交易类型
		$unifiedOrder->setParameter("attach",'weixin');//附加数据
		$prepay_result = $unifiedOrder->getPrepayId();
		if($prepay_result['return_code'] == 'FAIL'){
			return array('error'=>1,'msg'=>'没有获取微信支付的预支付ID，请重新发起支付！<br/><br/>微信支付错误返回：'.$prepay_result['return_msg']);
		}
		if($prepay_result['err_code']){
			return array('error'=>1,'msg'=>'没有获取微信支付的预支付ID，请重新发起支付！<br/><br/>微信支付错误返回：'.$prepay_result['err_code_des']);
		}
		//=========步骤3：得到微信的二维码============
		$jsApi->setPrepayId($prepay_result['prepay_id']);
		
		return array('error'=>0,'qrcode'=>$prepay_result['code_url']);
	}
	public function mobile_pay(){
		import('@.ORG.pay.Weixinnewpay.WxPayPubHelper');
		//使用jsapi接口
		$jsApi = new JsApi_pub($this->pay_config['pay_weixin_appid'],$this->pay_config['pay_weixin_mchid'],$this->pay_config['pay_weixin_key'],$this->pay_config['pay_weixin_appsecret']);
		//使用统一支付接口
		$unifiedOrder = new UnifiedOrder_pub($this->pay_config['pay_weixin_appid'],$this->pay_config['pay_weixin_mchid'],$this->pay_config['pay_weixin_key'],$this->pay_config['pay_weixin_appsecret']);	
		$unifiedOrder->setParameter("openid",($this->pay_config['is_own'] ? $_SESSION['open_authorize_openid'] : $_SESSION['openid']));//用户微信唯一标识
		$unifiedOrder->setParameter("body",($this->pay_config['is_own'] ? '' : '').$this->order_info['order_name'].($this->order_info['order_num'] > 0 ? '_'.$this->order_info['order_num'] : ''));//商品描述
		//服务商子商户支付
		if($this->pay_config['sub_mch_id']){
			$unifiedOrder->setParameter("sub_mch_id",$this->pay_config['sub_mch_id']);
			$unifiedOrder->setParameter("openid",$_SESSION['openid']);
		}else{

			$unifiedOrder->setParameter("openid",($this->pay_config['is_own'] ? $_SESSION['open_authorize_openid'] : $_SESSION['openid']));//用户微信唯一标识
		}
		//自定义订单号，此处仅作举例
		$unifiedOrder->setParameter("out_trade_no",$this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_'.$this->pay_config['is_own'] : ''));//商户订单号
		$unifiedOrder->setParameter("total_fee",floatval($this->pay_money*100));//总金额
		if($this->order_info['DEVICE_ID']=='wxcapp'){
			$unifiedOrder->setParameter("notify_url",C('config.site_url').'/source/wxcapp_weixin_notice.php');//通知地址
		}else if($this->order_info['DEVICE_ID']=='wxapp_paotui'){
			$unifiedOrder->setParameter("notify_url",C('config.site_url').'/source/wxapp_paotui_weixin_notice.php');//通知地址
		}else if($this->is_mobile==3){
			$unifiedOrder->setParameter("notify_url",C('config.site_url').'/source/wxapp_weixin_notice.php');//通知地址
		}else{
			$unifiedOrder->setParameter("notify_url",C('config.site_url').'/source/wap_weixin_notice.php');//通知地址
		}
		$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		if($this->pay_config['is_own'] && $this->order_info['mer_id']){
			$unifiedOrder->setParameter("attach",$this->order_info['mer_id']);//附加数据
		}else{
			$unifiedOrder->setParameter("attach",'weixin');//附加数据
		}
		$prepay_result = $unifiedOrder->getPrepayId();
		if($prepay_result['return_code'] == 'FAIL'){
			return array('error'=>1,'msg'=>'没有获取微信支付的预支付ID，请重新发起支付！微信支付错误返回：'.$prepay_result['return_msg']);
		}
		if($prepay_result['err_code']){
			return array('error'=>1,'msg'=>'没有获取微信支付的预支付ID，请重新发起支付！<br/><br/>微信支付错误返回：'.$prepay_result['err_code_des']);
		}
		//=========步骤3：使用jsapi调起支付============
		$jsApi->setPrepayId($prepay_result['prepay_id']);
		
		return array('error'=>0,'weixin_param'=>$jsApi->getParameters());
	}

	public function app_pay($ticket, $deviceId){
		import('@.ORG.pay.Weixinnewpay.WxPayPubHelper');
		//使用jsapi接口
		$jsApi = new JsApi_pub($this->pay_config['pay_weixin_appid'],$this->pay_config['pay_weixin_mchid'],$this->pay_config['pay_weixin_key'],$this->pay_config['pay_weixin_appsecret']);
		//使用统一支付接口
		$unifiedOrder = new UnifiedOrder_pub($this->pay_config['pay_weixin_appid'],$this->pay_config['pay_weixin_mchid'],$this->pay_config['pay_weixin_key'],$this->pay_config['pay_weixin_appsecret']);
		$unifiedOrder->setParameter("body",($this->pay_config['is_own'] ? '' : '').$this->order_info['order_name'].($this->order_info['order_num'] > 0 ? '_'.$this->order_info['order_num'] : ''));//商品描述
		//自定义订单号，此处仅作举例
		$unifiedOrder->setParameter("out_trade_no",$this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : ''));//商户订单号
		$unifiedOrder->setParameter("total_fee",floatval($this->pay_money*100));//总金额
		$unifiedOrder->setParameter("notify_url",C('config.site_url').'/source/wap_weixin_notice.php');//通知地址
		$unifiedOrder->setParameter("trade_type","NATIVE");//交易类型
		if($this->pay_config['is_own'] && $this->order_info['mer_id']){
			$unifiedOrder->setParameter("attach",$this->order_info['mer_id']);//附加数据
		}else{
			$unifiedOrder->setParameter("attach",'weixin');//附加数据
		}
		$prepay_result = $unifiedOrder->getPrepayId();
		if($prepay_result['return_code'] == 'FAIL'){
			return array('error'=>1,'msg'=>'没有获取微信支付的预支付ID，请重新发起支付！微信支付错误返回：'.$prepay_result['return_msg']);
		}
		if($prepay_result['err_code']){
			return array('error'=>1,'msg'=>'没有获取微信支付的预支付ID，请重新发起支付！<br/><br/>微信支付错误返回：'.$prepay_result['err_code_des']);
		}
		//=========步骤3：重新生成app签名============
		$sign = $jsApi->getPrepayAppSign($prepay_result);

		return array('error'=>0,'sign'=>$sign);
	}

	public function notice_url(){
		if(empty($this->pay_config['pay_weixin_appid']) || empty($this->pay_config['pay_weixin_mchid']) || empty($this->pay_config['pay_weixin_key']) || empty($this->pay_config['pay_weixin_appsecret'])){
			return array('error'=>1,'msg'=>'微信支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
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
		@libxml_disable_entity_loader(true);
		if(empty($this->pay_config['pay_weixin_appid']) || empty($this->pay_config['pay_weixin_mchid']) || empty($this->pay_config['pay_weixin_key']) || empty($this->pay_config['pay_weixin_appsecret'])){
			return array('error'=>1,'msg'=>'微信支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		
		import('@.ORG.pay.Weixinnewpay.WxPayPubHelper');
		//使用通用通知接口
		$notify = new Notify_pub($this->pay_config['pay_weixin_appid'],$this->pay_config['pay_weixin_mchid'],$this->pay_config['pay_weixin_key'],$this->pay_config['pay_weixin_appsecret']);
		//存储微信的回调
		$xml = file_get_contents("php://input");
		$notify->saveData($xml);
		D('Pay_async_record')->weixin_record($notify->data,$xml);
		//验证签名，并回应微信。
		if($notify->checkSign() == FALSE){
			$notify->setReturnParameter("return_code","FAIL");//返回状态码
			$notify->setReturnParameter("return_msg","签名失败");//返回信息
			return array('error'=>1,'msg'=>$notify->returnXml());
		}else{
			$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
			
			if($notify->data['return_code']=='SUCCESS' && $notify->data['result_code']=='SUCCESS'){
				$order_id_arr = explode('_',$notify->data['out_trade_no']);
				$order_param['pay_type'] = 'weixin';
				$order_param['is_mobile'] = $this->is_mobile;
				$order_param['order_type'] = $order_id_arr[0];
				$order_param['order_id'] = $order_id_arr[1];
				$order_param['is_own'] = intval($order_id_arr[2]);
				$order_param['third_id'] = $notify->data['transaction_id'];
				$order_param['pay_money'] = $notify->data['total_fee']/100;
				$order_param['sub_mch_id'] = $notify->data['sub_mch_id'];
				return array('error'=>0,'order_param'=>$order_param);
			}else{
				return array('error'=>1,'msg'=>'支付时发生错误！<br/>错误提示：'.$e->GetMessage().'<br/>错误代码：'.$e->Getcode());
			}
		}
	}
	/*查找微信订单信息*/
	public function query_order(){
		if(empty($this->pay_config['pay_weixin_appid']) || empty($this->pay_config['pay_weixin_mchid']) || empty($this->pay_config['pay_weixin_key']) || empty($this->pay_config['pay_weixin_appsecret'])){
			return array('error'=>1,'msg'=>'微信支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		import('@.ORG.pay.Weixinnewpay.WxPayPubHelper');
		//使用订单查询接口
		$orderQuery = new OrderQuery_pub($this->pay_config['pay_weixin_appid'],$this->pay_config['pay_weixin_mchid'],$this->pay_config['pay_weixin_key'],$this->pay_config['pay_weixin_appsecret']);
        //设置必填参数
		$orderQuery->setParameter('out_trade_no',$this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ?'_'.$this->pay_config['is_own'] : ''));//商户订单号
		//获取订单查询结果
		if($this->pay_config['sub_mch_id']){
			$orderQuery->setParameter("sub_mch_id",$this->pay_config['sub_mch_id']);
		}
		$orderQueryResult = $orderQuery->getResult();
		if($orderQueryResult['return_code'] == 'FAIL') {
			return array('error'=>1,'msg'=>'通信出错：'.$orderQueryResult['return_msg']);
		}else if($orderQueryResult['result_code'] == 'FAIL') {
			return array('error'=>1,'msg'=>'错误代码描述：'.$orderQueryResult['err_code_des']);
		}else if($orderQueryResult['trade_state'] != 'SUCCESS') {
			return array('error'=>1,'msg'=>'交易状态：'.$orderQueryResult['trade_state_desc']);
		}else{
			$order_param['pay_type'] = 'weixin';
			$order_param['is_mobile'] = $this->is_mobile;
			$order_param['order_type'] = $this->order_info['order_type'];
			$order_param['order_id'] = $this->order_info['order_id'];
			$order_param['is_own'] = intval($this->pay_config['is_own']);
			$order_param['third_id'] = $orderQueryResult['transaction_id'];
			$order_param['pay_money'] = $orderQueryResult['total_fee']/100;
			$order_param['sub_mch_id'] = $orderQueryResult['sub_mch_id'];
			return array('error'=>0,'order_param'=>$order_param);
		}
	}

        /* 退款 */
    public function refund()
    {
        if ($this->order_info['is_mobile_pay'] == 2) {
            $this->pay_config['pay_weixin_appid'] = C('config.pay_weixinapp_appid');
            $this->pay_config['pay_weixin_mchid'] = C('config.pay_weixinapp_mchid');
            $this->pay_config['pay_weixin_key'] = C('config.pay_weixinapp_key');
            $this->pay_config['pay_weixin_appsecret'] = C('config.pay_weixinapp_appsecret');
            C('config.pay_weixin_client_cert', C('config.pay_weixinapp_cert'));
            C('config.pay_weixin_client_key', C('config.pay_weixinapp_cert_key'));
        } elseif ($this->order_info['is_mobile_pay'] == 3) {
			if($this->order_info['business_type'] == 'service' &&  C('config.pay_wxapp_paotui_appid')){
				$this->pay_config['pay_weixin_appid'] = C('config.pay_wxapp_paotui_appid');
				$this->pay_config['pay_weixin_mchid'] = C('config.pay_wxapp_paotui_mchid');
				$this->pay_config['pay_weixin_key'] = C('config.pay_wxapp_paotui_key');
				$this->pay_config['pay_weixin_appsecret'] = C('config.pay_wxapp_paotui_appsecret');
				C('config.pay_weixin_client_cert',C('config.pay_wxapp_paotui_cert'));
				C('config.pay_weixin_client_key',C('config.pay_wxapp_paotui_cert_key'));
			} else if(C('config.pay_wxapp_appid')){
				$this->pay_config['pay_weixin_appid'] = C('config.pay_wxapp_appid');
				$this->pay_config['pay_weixin_mchid'] = C('config.pay_wxapp_mchid');
				$this->pay_config['pay_weixin_key'] = C('config.pay_wxapp_key');
				$this->pay_config['pay_weixin_appsecret'] = C('config.pay_wxapp_appsecret');
				C('config.pay_weixin_client_cert', C('config.pay_wxapp_cert'));
				C('config.pay_weixin_client_key', C('config.pay_wxapp_cert_key'));
			}
        }
		if($this->pay_config['is_own']){
			C('config.pay_weixin_client_cert',$this->pay_config['pay_weixin_client_cert']);
			C('config.pay_weixin_client_key',$this->pay_config['pay_weixin_client_key']);
		}
		if(empty($this->pay_config['pay_weixin_appid']) || empty($this->pay_config['pay_weixin_mchid']) || empty($this->pay_config['pay_weixin_key']) || empty($this->pay_config['pay_weixin_appsecret'])){
			return array('error'=>1,'msg'=>'微信支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		$weixin_cert =  C('config.pay_weixin_client_cert');
		$weixin_key =  C('config.pay_weixin_client_key');

		if(empty($weixin_cert) || empty($weixin_key)){
			return array('error'=>1,'msg'=>'管理员在后台支付配置中必须上传 微信支付证书和微信支付证书密钥！');
		}
		
		import('@.ORG.pay.Weixinnewpay.WxPayPubHelper');
		$refund = new Refund_pub($this->pay_config['pay_weixin_appid'],$this->pay_config['pay_weixin_mchid'],$this->pay_config['pay_weixin_key'],$this->pay_config['pay_weixin_appsecret']);
		// dump($this->order_info);exit;
		$refund->setParameter("out_trade_no",$this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->order_info['is_own'] ? '_'.$this->order_info['is_own'] : ''));//商户订单号
		$refund->setParameter("out_refund_no",$this->order_info['order_type'].'_'.$this->order_info['order_id'].'_'.$_SERVER['REQUEST_TIME']);//商户退款单号
		$refund->setParameter("total_fee",bcmul($this->order_info['payment_money'],100));//总金额
		$refund->setParameter("refund_fee",intval(bcmul($this->pay_money,100)));//退款金额
		$refund->setParameter("op_user_id",$this->pay_config['pay_weixin_mchid']);//操作员
		if(C('config.weixin_refund_use_balance')){
			$refund->setParameter("refund_account",'REFUND_SOURCE_RECHARGE_FUNDS');//操作员
		}
		if($this->pay_config['sub_mch_id']){
			$refund->setParameter("sub_mch_id",$this->pay_config['sub_mch_id']);
			$refund->setParameter("transaction_id",$this->order_info['third_id']);
			$refund->setParameter("out_trade_no",$this->order_info['order_type'].'_'.$this->order_info['order_id']. '_2' );//商户订单号
		}
		$refundResult = $refund->getResult();
		if(($refundResult['result_code'] == 'FAIL' && $refundResult['err_code'] != 'REFUND_FEE_INVALID')  || $refundResult['return_code'] == 'FAIL' || ($refundResult['return_code']=='SUCCESS' && $refundResult['result_code'] == 'FAIL') || !$refundResult ){
			$refund_param['err_msg'] = $refundResult['return_msg'];
			if($refundResult['err_code_des']){
				$refund_param['err_msg'] .= $refundResult['err_code_des'];
			}
			$refund_param['refund_time'] = time();
			return array('error'=>1,'type'=>'fail','msg'=>'退款申请失败！如果重试多次还是失败请联系系统管理员。'.($refund_param['err_msg']?'<br>原因：'.$refund_param['err_msg']:''),'refund_param'=>$refund_param);
		}else{
			$refund_param['refund_id'] = $refundResult['refund_id'];
			$refund_param['refund_time'] = time();
			return array('error'=>0,'type'=>'ok','msg'=>'退款申请成功！请注意查收“微信支付”给您发的退款通知。','refund_param'=>$refund_param);
		}
		
	}

	public function sendredpack(){

		if(empty($this->pay_config['pay_weixin_appid']) || empty($this->pay_config['pay_weixin_mchid']) || empty($this->pay_config['pay_weixin_key']) || empty($this->pay_config['pay_weixin_appsecret'])){
			return array('error'=>1,'msg'=>'微信支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		$weixin_cert =  C('config.pay_weixin_client_cert');
		$weixin_key =  C('config.pay_weixin_client_key');


		if(empty($weixin_cert) || empty($weixin_key)){
			return array('error'=>1,'msg'=>'管理员在后台支付配置中必须上传 微信支付证书和微信支付证书密钥！');
		}

		import('@.ORG.pay.Weixinnewpay.WxPayPubHelper');
		$refund = new RedPack($this->pay_config['pay_weixin_appid'],$this->pay_config['pay_weixin_mchid'],$this->pay_config['pay_weixin_key'],$this->pay_config['pay_weixin_appsecret']);
		// dump($this->order_info);exit;
		$refund->setParameter("mch_billno",'red'.time());//红包订单id
		$refund->setParameter("total_amount",$this->order_info['payment_money']*100);//总金额
		$refund->setParameter("total_num",1);//退款金额
		$refund->setParameter("send_name",C('config.site_name'));
		$refund->setParameter("re_openid",$this->user_info['openid']);
		$refund->setParameter("wishing",'恭喜您抽中'.$this->order_info['payment_money'].'元红包');

		$refund->setParameter("remark",$this->order_info['red_desc']);


		$refundResult = $refund->getResult();

		if(($refundResult['result_code'] == 'FAIL' && $refundResult['err_code'] != 'REFUND_FEE_INVALID')  || $refundResult['return_code'] == 'FAIL' || ($refundResult['return_code']=='SUCCESS' && $refundResult['result_code'] == 'FAIL') || !$refundResult ){
			$refund_param['err_msg'] = $refundResult['return_msg'];
			if($refundResult['err_code_des']){
				$refund_param['err_msg'] .= $refundResult['err_code_des'];
			}
			$refund_param['refund_time'] = time();
			return array('error'=>1,'type'=>'fail','msg'=>'红包发送失败！如果重试多次还是失败请联系系统管理员。','refund_param'=>$refund_param);
		}else{
			$refund_param['refund_id'] = $refundResult['refund_id'];
			$refund_param['refund_time'] = time();
			return array('error'=>0,'type'=>'ok','msg'=>'红包发送成功！','refund_param'=>$refund_param);
		}
	}
}
?>