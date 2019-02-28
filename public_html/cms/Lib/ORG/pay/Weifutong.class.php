<?php
class Weifutong{
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
	public function pay(){
		if(empty($this->pay_config['pay_weifutong_mchid']) || empty($this->pay_config['pay_weifutong_key'])){
			return array('error'=>1,'msg'=>'威富通支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile == 0){
			$param['service'] = 'pay.weixin.native';
		}else{
			$param['service'] = 'pay.weixin.jspay';
		}
		$param['mch_id'] = $this->pay_config['pay_weifutong_mchid'];
		$param['out_trade_no'] = $this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : '');
		$param['body'] = ($this->pay_config['is_own'] ? '' : '').'订单号：'.$this->order_info['order_type'].'_'.$this->order_info['order_id'];
		
		
		if($this->pay_config['pay_weifutong_mchid'] != '7551000001'){
			$param['sub_openid'] = ($this->pay_config['is_own'] ? $_SESSION['open_authorize_openid'] : $_SESSION['openid']);
			if(empty($param['sub_openid'])){
				return array('error'=>1,'msg'=>'没有获取到用户相对微信的唯一标识');
			}
			$param['total_fee'] = floatval($this->pay_money*100);//正式金额
		}else{
			$param['total_fee'] = 1;//测试金额
		}
		
		if($this->pay_config['is_own'] && $this->order_info['mer_id']){
			$param['attach'] = $this->order_info['mer_id'];
		}else{
			$param['attach'] = 'weixin';
		}
		
		//$param['total_fee'] = 1;
		
		$param['mch_create_ip'] = get_client_ip();
		// $param['notify_url'] = C('config.site_url').'/source/wap_weifutong_notice.php?out_trade_no='.$param['out_trade_no'].'&urltype=back';
		// $param['callback_url'] = C('config.site_url').'/source/wap_weifutong_notice.php?out_trade_no='.$param['out_trade_no'].'&urltype=front';
		
		
		$param['notify_url'] = C('config.site_url').'/wap.php?c=Pay&a=return_url&pay_type=weifutong&is_mobile=1&out_trade_no='.$param['out_trade_no'].'&urltype=back';
		$param['callback_url'] = C('config.site_url').'/wap.php?c=Pay&a=return_url&pay_type=weifutong&is_mobile=1&out_trade_no='.$param['out_trade_no'].'&urltype=front';
		
		if($this->pay_config['is_own']){
			$param['notify_url'] .= '&own_mer_id='.$this->order_info['mer_id'];
			$param['callback_url'] .= '&own_mer_id='.$this->order_info['mer_id'];
		}
		
		
		$param['nonce_str'] = uniqid();
		// dump($param);die;
		//计算KEY
		$signPars = "";
		ksort($param);
		foreach($param as $k => $v) {
			if("" != $v && "sign" != $k) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->pay_config['pay_weifutong_key'];
		$param['sign'] = strtoupper(md5($signPars));
		
		//转换成xml
		$xml = '<xml>';
        forEach($param as $k=>$v){
            $xml.='<'.$k.'><![CDATA['.$v.']]></'.$k.'>';
        }
        $xml.='</xml>';
		
		//请求
		$gateUrl = 'https://pay.swiftpass.cn/pay/gateway';
		import('ORG.Net.Http');
		$http = new Http();
		$return = Http::curlPostXml($gateUrl, $xml);
		
		if($return['status'] == '410'){
			return array('error'=>1,'msg'=>'威富通支付返回了错误信息！<br/><br/>错误返回：'.$return['message']);
		}
		if($return['status'] == '400'){
			return array('error'=>1,'msg'=>'没有获取到威富通支付的动态口令，请检查配置项！<br/><br/>错误返回：'.$return['message']);
		}
		
		if($return['result_code'] !== '0'){
			return array('error'=>1,'msg'=>'没有获取到威富通支付的动态口令，请重新发起支付！<br/><br/>错误返回：'.$return['err_msg']);
		}
		
		if($this->is_mobile == 0){
			return array('error'=>0,'qrcode'=>$return['code_url']);
		}else{
			$payUrl = 'https://pay.swiftpass.cn/pay/jspay';
			return array('error'=>0,'url'=>$payUrl.'?token_id='.$return['token_id'].'&showwxtitle=1');
		}
		// dump($return);
		// dump($param);die;
	}
	public function notice_url(){
		if(empty($this->pay_config['pay_weifutong_mchid']) || empty($this->pay_config['pay_weifutong_key'])){
			return array('error'=>1,'msg'=>'威富通支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		exit('success');
	}
	public function return_url(){
		if(empty($this->pay_config['pay_weifutong_mchid']) || empty($this->pay_config['pay_weifutong_key'])){
			return array('error'=>1,'msg'=>'威富通支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		
		$is_notify = file_get_contents('php://input') ? true : false;
		
		$param['service'] = 'unified.trade.query';
		$param['mch_id'] = $this->pay_config['pay_weifutong_mchid'];
		$param['out_trade_no'] = $_GET['out_trade_no'];
		$param['nonce_str'] = uniqid();
		
		//计算KEY
		$signPars = "";
		ksort($param);
		foreach($param as $k => $v) {
			if("" != $v && "sign" != $k) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->pay_config['pay_weifutong_key'];
		$param['sign'] = strtoupper(md5($signPars));
		
		//转换成xml
		$xml = '<xml>';
        forEach($param as $k=>$v){
            $xml.='<'.$k.'><![CDATA['.$v.']]></'.$k.'>';
        }
        $xml.='</xml>';
		
		
		
		//请求
		$gateUrl = 'https://pay.swiftpass.cn/pay/gateway';
		import('ORG.Net.Http');
		$http = new Http();
		$return = Http::curlPostXml($gateUrl, $xml);
		
		if($return['result_code'] !== '0'){
			return array('error'=>1,'msg'=>'查询订单时发生错误！接口返回：'.$return['err_msg']);
		}
		if($return['trade_state'] != 'SUCCESS'){
			return array('error'=>1,'msg'=>'订单暂未支付');
		}
		
		$order_id_arr = explode('_',$_GET['out_trade_no']);
		$order_param['pay_type'] = 'weifutong';
		$order_param['is_mobile'] = $this->is_mobile;
		$order_param['order_type'] = $order_id_arr[0];
		$order_param['order_id'] = $order_id_arr[1];
		$order_param['third_id'] = $return['transaction_id'];
		$order_param['is_own'] = intval($order_id_arr[2]);
		$order_param['pay_money'] = $return['total_fee']/100;
		return array('error'=>0,'order_param'=>$order_param);
		
		// dump($return);
		// dump($param);die;
	}
	public function refund(){
		if(empty($this->pay_config['pay_weifutong_mchid']) || empty($this->pay_config['pay_weifutong_key'])){
			return array('error'=>1,'msg'=>'威富通支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}

		$param['service'] = 'unified.trade.refund';
		$param['mch_id'] = $this->pay_config['pay_weifutong_mchid'];
		$param['out_trade_no'] = $this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->order_info['is_own'] ? '_1' : '');
		$param['out_refund_no'] = $this->order_info['order_type'].'_'.$this->order_info['order_id'].'_'.mt_rand(0,9);
		$param['nonce_str'] = uniqid();
		
		$param['total_fee'] = floatval($this->order_info['payment_money']*100);
		$param['refund_fee'] = floatval($this->order_info['payment_money']*100);
		
		$param['op_user_id'] = $this->pay_config['pay_weifutong_mchid'];
		
		
		//计算KEY
		$signPars = "";
		ksort($param);
		foreach($param as $k => $v) {
			if("" != $v && "sign" != $k) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->pay_config['pay_weifutong_key'];
		$param['sign'] = strtoupper(md5($signPars));
		
		//转换成xml
		$xml = '<xml>';
        forEach($param as $k=>$v){
            $xml.='<'.$k.'><![CDATA['.$v.']]></'.$k.'>';
        }
        $xml.='</xml>';
		
		
		
		//请求
		$gateUrl = 'https://pay.swiftpass.cn/pay/gateway';
		import('ORG.Net.Http');
		$http = new Http();
		$return = Http::curlPostXml($gateUrl, $xml);
		// dump($return);die;
		if($return['result_code'] !== '0'){
			return array('error'=>1,'msg'=>'订单请求退款时发生错误！接口返回：'.$return['err_msg']);
		}

		$refund_param['refund_id'] = $return['refund_id'];
		$refund_param['refund_time'] = time();
		return array('error'=>0,'type'=>'ok','msg'=>'退款申请成功！正常情况下3个工作日款项会自动流入您支付时使用的银行卡内。','refund_param'=>$refund_param);
	}
}
?>