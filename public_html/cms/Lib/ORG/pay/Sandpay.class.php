<?php
class Sandpay{
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
		if($this->is_mobile){
			return $this->mobile_pay();
		}else{
			return $this->web_pay();
		}
	}
	public function mobile_pay(){
		dump($this->order_info);
		dump($this->pay_money);
		dump($this->pay_type);
		$data['uid'] = $this->user_info['uid'];
		$data['order_id'] = $this->order_info['order_id'];
		$data['order_type'] = $this->order_info['order_type'];
		$data['order_name'] = $this->order_info['order_name'];
		$data['order_price'] = $this->pay_money;
		$data['create_time'] = time();
		
		$nowtime = date("ymdHis");
		$data['sandpay_rand_id'] = $nowtime.rand(10,99).sprintf("%08d",$this->user_info['uid']);
		
		if($sandpay_order_id = M('Sandpay_order')->data($data)->add()){
			redirect(U('Sandpay/index',array('order_id'=>$data['sandpay_rand_id'])));
		}else{
			$this->error_tips('银行卡支付生成订单失败');
		}
		
	}
	public function web_pay(){
		$param = base64_encode($this->order_info['order_type'].'_'.$this->order_info['order_id']);
		if($this->pay_config['is_own'] && $this->order_info['mer_id']){
			$_SESSION['own_mer_id'] = $this->order_info['mer_id'];
		}
		header('Location:' . C('config.site_url').'/index.php?c=Pay&a=return_url&pay_type=offline&param=' . $param);
		exit();
	}
	
	public function notice_url(){
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
		$condition_order['sandpay_rand_id'] = $_GET['sandpay_order_id'];
		$now_order = M('Sandpay_order')->where($condition_order)->find();
		if(empty($now_order)){
			$this->error_tips('该订单不存在');
		}
		if(empty($now_order['is_paid'])){
			$this->error_tips('该订单暂未付款');
		}
		
		$order_param['pay_type'] = 'sandpay';
		$order_param['is_mobile'] = $this->is_mobile;
		$order_param['order_type'] = $now_order['order_type'];
		$order_param['order_id'] = $now_order['order_id'];
		$order_param['third_id'] = $now_order['third_id'];
		$order_param['pay_money'] = $now_order['paid_money'];
		
		return array('error' => 0,'order_param' => $order_param);
	}
}
?>