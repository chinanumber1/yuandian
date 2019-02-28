<?php
class Yunjubao{
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
		if(empty($this->pay_config['pay_yunjubao_key']) || empty($this->pay_config['pay_yunjubao_app']) || empty($this->pay_config['pay_yunjubao_operator_id'])){
			return array('error'=>1,'msg'=>'缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile){
			return $this->mobile_pay();
		}else{
			return $this->web_pay();
		}
	}
	public function web_pay(){
		
	}
	public function mobile_pay(){		
		$param['local_order_no'] = $this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : '');
		$param['operator_id'] = $this->pay_config['pay_yunjubao_operator_id'];
		$param['channel'] = '2';
		$param['amount'] = floatval($this->pay_money*100);
		$param['un_discount_amount'] = 0;
		$param['timestamp'] = $_SERVER['REQUEST_TIME'].mt_rand(100,999);
		$param['app'] = $this->pay_config['pay_yunjubao_app'];

		//计算KEY
		$signPars = "";
		ksort($param);
		foreach($param as $k => $v) {
			if("" !== $v && "sign" != $k) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->pay_config['pay_yunjubao_key'];
		$param['sign'] = md5($signPars);
		$param['command'] = 'open.pay.scan';
		$param['version'] = '1.1';
		
		import('ORG.Net.Http');
		$http = new Http();

		$return = Http::curlPost('http://openapi.caibaopay.com/gatewayOpen.htm',($param));
		if($return['result']['errorCode'] == '0' && $return['result']['success']){
			return array('error'=>0,'qrcode'=>$return['data']['qrCode']);
		}else{
			return array('error'=>1,'msg'=>$return['result']['errorMsg']);
		}
		
	}

	public function notice_url(){
		if(empty($this->pay_config['pay_ccb_merchantid']) || empty($this->pay_config['pay_ccb_posid']) || empty($this->pay_config['pay_ccb_branchid']) || empty($this->pay_config['pay_ccb_pub'])){
			return array('error'=>1,'msg'=>'建设银行缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if($this->is_mobile){
			return $this->mobile_notice();
		}else{
			return $this->web_notice();
		}
	}
	public function query_order(){
		
		if($_GET['SUCCESS'] != 'Y') {
			return array('error'=>1,'msg'=>'支付失败');
		}
		
		$order_id_arr = explode('_', $_GET['ORDERID']);
		
		$trade_no  = $_GET['ORDERID'];
		$pay_money = $_GET['PAYMENT'];
		
		$order_param['pay_type'] = 'ccb';
		$order_param['is_mobile'] = $this->is_mobile;
		$order_param['order_type'] = $order_id_arr[0];
		$order_param['order_id'] = $order_id_arr[1];
		$order_param['third_id'] = $trade_no;
		$order_param['pay_money'] = $pay_money;
		$order_param['return'] = 1;
		return array(
			'error' => 0,
			'order_param' => $order_param
		);
	}
	public function mobile_notice(){
		exit('success');
	}
	public function web_notice(){
		exit('success');
	}
	public function return_url(){
		if(empty($this->pay_config['pay_ccb_merchantid']) || empty($this->pay_config['pay_ccb_posid']) || empty($this->pay_config['pay_ccb_branchid']) || empty($this->pay_config['pay_ccb_pub'])){
			return array('error'=>1,'msg'=>'建设银行缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
	}
	public function refund(){
		$order_param['pay_type'] = 'ccb';
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
	}
}
?>