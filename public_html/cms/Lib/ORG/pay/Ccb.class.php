<?php
class Ccb{
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
		if(empty($this->pay_config['pay_ccb_merchantid']) || empty($this->pay_config['pay_ccb_posid']) || empty($this->pay_config['pay_ccb_branchid']) || empty($this->pay_config['pay_ccb_pub'])){
			return array('error'=>1,'msg'=>'建设银行缺少配置信息！请联系管理员处理或选择其他支付方式。');
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
		
		$MERCHANTID	 = $this->pay_config['pay_ccb_merchantid'];
		$POSID		 = $this->pay_config['pay_ccb_posid'];
		$BRANCHID	 = $this->pay_config['pay_ccb_branchid'];
		$PUB32TR2	 = substr($this->pay_config['pay_ccb_pub'],-30);
		$ORDERID	 = $this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : '');
		$PAYMENT	 = $this->pay_money;
		$CURCODE	 = '01';
		$TXCODE	 	 = '520100';
		$REMARK1	 = '';
		$REMARK2	 = '';
		$bankURL	 = 'https://ibsbjstar.ccb.com.cn/CCBIS/ccbMain';
		$PROINFO     = $this->order_info['order_name'] ? $this->order_info['order_name'] : $this->order_info['order_type'].'_'.$this->order_info['order_id'];
		$PROINFO     = $this->order_info['order_type'].'_'.$this->order_info['order_id'];
		$PROINFO     = $this->order_info['order_type'];
		$REFERER	 = '3133333.com';
		
		$tmp 		 = 'MERCHANTID='.$MERCHANTID.'&POSID='.$POSID.'&BRANCHID='.$BRANCHID.'&ORDERID='.$ORDERID.'&PAYMENT='.$PAYMENT.'&CURCODE='.$CURCODE.'&TXCODE='.$TXCODE.'&REMARK1='.$REMARK1.'&REMARK2='.$REMARK2;
		
		$temp_New	 = $tmp.'&TYPE=1&PUB='.$PUB32TR2.'&GATEWAY=W1Z1&CLIENTIP='.get_client_ip().'11&REGINFO=user&PROINFO='.$PROINFO.'&REFERER='.$REFERER;
		
		$temp_New1	 = $tmp.'&TYPE=1&GATEWAY=W1Z1&CLIENTIP='.get_client_ip().'11&REGINFO=user&PROINFO='.$PROINFO.'&REFERER='.$REFERER;
		
		$strMD5 = md5($temp_New);
		

		$URL3 = $bankURL.'?'.$temp_New1.'&MAC='.$strMD5;
		
		// dump($_SERVER);
		
		// echo $temp_New.'<br/><br/><br/>';
		// echo $URL3;die;
		
		redirect($URL3);
		
		// return array('error'=>0,'form'=>$form);
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