<?php
class Baidu
{
	protected $order_info;
	protected $pay_money;
	protected $pay_type;
	protected $is_mobile;
	protected $pay_config;
	protected $user_info;
	
	public function __construct($order_info, $pay_money, $pay_type, $pay_config, $user_info, $is_mobile=0)
	{
		$this->order_info = $order_info;
		$this->pay_money  = $pay_money;
		$this->pay_type   = $pay_type;
		$this->is_mobile   = $is_mobile;
		$this->pay_config = $pay_config;
		$this->user_info  = $user_info;
	}
	public function pay()
	{
		if (empty($this->pay_config['pay_baidu_sp_no']) || empty($this->pay_config['pay_baidu_key'])) {
			return array('error' => 1, 'msg' => '百度钱包支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		if ($this->is_mobile) {
			return $this->mobile_pay();
		} else {
			return $this->web_pay();
		}
	}
	public function web_pay()
	{
		import('@.ORG.pay.BaiduPay.bdpay_sdk');
		
		$bdpay_sdk = new bdpay_sdk($this->pay_config['pay_baidu_sp_no'], $this->pay_config['pay_baidu_key']);
		
		// 用于测试的商户请求支付接口的表单参数，具体的表单参数各项的定义和取值参见接口文档
		$params = array (
				'service_code' => 1,
				'sp_no' => $this->pay_config['pay_baidu_sp_no'],
				'order_create_time' => date("YmdHis"),
				'order_no' => D('Baidu_order')->get_bf_order($this->order_info['order_id'], $this->order_info['order_type'], $this->pay_config['is_own']),
				'goods_name' => iconv("UTF-8", "GBK", urldecode($this->order_info['order_name'])),
				'goods_desc' => iconv("UTF-8", "GBK", urldecode(($this->pay_config['is_own'] ? '' : '').$this->order_info['order_name'].'_'.$this->order_info['order_num'])),
				'goods_url' => C('config.site_url'),
// 				'unit_amount' => $unit_amount,//商品的单价
// 				'unit_count' => $unit_count,//商品数量
// 				'transport_amount' => 0,//运费
				'total_amount' => floatval($this->pay_money * 100),
				'currency' => 1,
// 				'buyer_sp_username' => $buyer_sp_username,//买家在商户网站的用户名
				'return_url' => C('config.site_url').'/source/web_baidu_return.php',
				'page_url' => C('config.site_url').'/source/web_baidu_notify.php',
				'pay_type' => 1,
// 				'bank_no' => $bank_no,
				'expire_time' => date('YmdHis', strtotime('+2 day')),
				'input_charset' => 1,
				'version' => 2,
				'sign_method' => 1,
				'extra' => $this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : '')
		);
		
		$order_url = $bdpay_sdk->create_baifubao_pay_order_url($params, "https://www.baifubao.com/api/0/pay/0/direct/0");
		
		if (false === $order_url) {
			return array('error' => 1,'msg' => '创建百度钱包支付接口的URL失败');
		} else {
			return array('error' => 0, 'url' => $order_url);
		}
	}
	
	public function mobile_pay()
	{
		import('@.ORG.pay.BaiduPay.bdpay_sdk');
		
		$bdpay_sdk = new bdpay_sdk($this->pay_config['pay_baidu_sp_no'], $this->pay_config['pay_baidu_key']);
		
		// 用于测试的商户请求支付接口的表单参数，具体的表单参数各项的定义和取值参见接口文档
		$params = array (
				'service_code' => 1,
				'sp_no' => $this->pay_config['pay_baidu_sp_no'],
				'order_create_time' => date("YmdHis"),
				'order_no' => D('Baidu_order')->get_bf_order($this->order_info['order_id'], $this->order_info['order_type'], $this->pay_config['is_own']),
				'goods_name' => iconv("UTF-8", "GBK", urldecode($this->order_info['order_name'])),
				'goods_desc' => iconv("UTF-8", "GBK", urldecode(($this->pay_config['is_own'] ? '' : '').$this->order_info['order_name'].'_'.$this->order_info['order_num'])),
				'goods_url' => C('config.site_url'),
// 				'unit_amount' => $unit_amount,//商品的单价
// 				'unit_count' => $unit_count,//商品数量
// 				'transport_amount' => 0,//运费
				'total_amount' => floatval($this->pay_money * 100),
				'currency' => 1,
// 				'buyer_sp_username' => $buyer_sp_username,//买家在商户网站的用户名
				'return_url' => C('config.site_url').'/source/wap_baidu_return.php',
				'page_url' => C('config.site_url').'/source/wap_baidu_notify.php',
				'pay_type' => 1,
// 				'bank_no' => $bank_no,
				'expire_time' => date('YmdHis', strtotime('+2 day')),
				'input_charset' => 1,
				'version' => 2,
				'sign_method' => 1,
				'extra' => $this->order_info['order_type'].'_'.$this->order_info['order_id'].($this->pay_config['is_own'] ? '_1' : '')
		);
		
		$order_url = $bdpay_sdk->create_baifubao_pay_order_url($params, "https://www.baifubao.com/api/0/pay/0/wapdirect/0");
		
		if (false === $order_url) {
			return array('error' => 1,'msg' => '创建百度钱包支付接口的URL失败');
		} else {
			return array('error' => 0, 'url' => $order_url);
		}
	}


	public function notice_url()
	{
		import('@.ORG.pay.BaiduPay.bdpay_sdk');
		
		$bdpay_sdk = new bdpay_sdk($this->pay_config['pay_baidu_sp_no'], $this->pay_config['pay_baidu_key']);
		
		unset($_GET['g'], $_GET['c'], $_GET['a'], $_GET['paytype']);
		$order_param = $bdpay_sdk->check_bfb_pay_result_notify();
		if ($order_param['error']) {
			return $order_param;
		} else {
			$data = $order_param['params'];
			$order = D('Baidu_order')->get_source_order($data['order_no']);
			if (empty($order)) {
				return array('error' => 1, 'msg' => '订单出错误了');
			}
			$order_param['pay_type'] = 'baidu';
			$order_param['return'] = 1;
			$order_param['is_mobile'] = $this->is_mobile;
			$order_param['order_type'] = $order['type'];
			$order_param['order_id'] = $order['order_id'];
			$order_param['is_own'] = intval($order['is_own']);
			$order_param['third_id'] = $data['bfb_order_no'];
			$order_param['pay_money'] = $data['total_amount'] / 100;
			return array('error' => 0, 'order_param' => $order_param);
		}
	}
	
	public function return_url()
	{
		import('@.ORG.pay.BaiduPay.bdpay_sdk');
		
		$bdpay_sdk = new bdpay_sdk($this->pay_config['pay_baidu_sp_no'], $this->pay_config['pay_baidu_key']);
		unset($_GET['g'], $_GET['c'], $_GET['a'], $_GET['paytype']);
		$order_param = $bdpay_sdk->check_bfb_pay_result_notify();
		if ($order_param['error']) {
			return $order_param;
		} else {
			$data = $order_param['params'];
			$order = D('Baidu_order')->get_source_order($data['order_no']);
			if (empty($order)) {
				return array('error' => 1, 'msg' => '订单出错误了');
			}
			$order_param['pay_type'] = 'baidu';
			$order_param['return'] = 1;
			$order_param['is_mobile'] = $this->is_mobile;
			$order_param['order_type'] = $order['type'];
			$order_param['order_id'] = $order['order_id'];
			$order_param['is_own'] = intval($order['is_own']);
			$order_param['third_id'] = $data['bfb_order_no'];
			$order_param['pay_money'] = $data['total_amount'] / 100;
			return array('error' => 0, 'order_param' => $order_param);
			
		}
	}
	
	/*查找百度支付订单信息*/
	public function query_order()
	{}
	
	/*退款*/
	public function refund()
	{
		if(empty($this->pay_config['pay_baidu_sp_no']) || empty($this->pay_config['pay_baidu_key'])){
			return array('error' => 1,'msg' => '微信支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
		}
		import('@.ORG.pay.BaiduPay.bdpay_sdk');
		
		$bdpay_sdk = new bdpay_sdk($this->pay_config['pay_baidu_sp_no'], $this->pay_config['pay_baidu_key']);
		$params = array (
				'service_code' => 2,
				'input_charset' => 1,
				'sign_method' => 1,
				'output_type' => 1,
				'output_charset' => 1,
				'return_url' => C('config.site_url').'/source/wap_baidu_refund.php',
				'return_method' => 1,
				'version' =>  2,
				'sp_no' => $this->pay_config['pay_baidu_sp_no'],
				'order_no' => D('Baidu_order')->get_bf_order($this->order_info['order_id'], $this->order_info['order_type'], $this->pay_config['is_own']),
				'cashback_amount' => $this->pay_money * 100,
				'cashback_time' => date("YmdHis"),
				'currency' => 1,
				'sp_refund_no' => date("YmdHis"). sprintf ('%06d', rand(0, 999999))
		);
		
		$refund_url = $bdpay_sdk->create_baifubao_Refund_url($params, "https://www.baifubao.com/api/0/refund");
		
		if(false === $refund_url){
			return array('error' => 1,'msg' => '创建百度钱包支付接口的URL失败');
		} else {
			$xml = $bdpay_sdk->request($refund_url);

			$data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
			if ($data['ret_code'] == 1) {
				$order = D('Baidu_order')->get_source_order($data['order_no']);
				if (empty($order)) {
					return array('error' => 1, 'msg' => '订单出错误了');
				}
				$order_param['pay_type'] = 'baidu';
				$order_param['return'] = 1;
				$order_param['is_mobile'] = $this->is_mobile;
				$order_param['order_type'] = $order['type'];
				$order_param['order_id'] = $order['order_id'];
				$order_param['is_own'] = intval($order['is_own']);
					
				$order_param['refund_id'] = $data['sp_refund_no'];
				$order_param['ret_code'] = $data['ret_code'];
				$order_param['ret_detail'] = $data['ret_detail'];
				$order_param['refund_time'] = time();
				return array('error' => 0, 'type' => 'ok','msg' => '退款申请成功！', 'refund_param' => $order_param);
			}
			return array('error' => 1, 'msg' => '百度钱包支付退款失败', 'ret_code' => $data['ret_code']);
		}
	}
	
	public function return_refund()
	{
		import('@.ORG.pay.BaiduPay.bdpay_sdk');
		
		$bdpay_sdk = new bdpay_sdk($this->pay_config['pay_baidu_sp_no'], $this->pay_config['pay_baidu_key']);
		unset($_GET['g'], $_GET['c'], $_GET['a'], $_GET['paytype']);
		$order_param = $bdpay_sdk->check_bfb_refund_result_notify();
		if ($order_param['error']) {
			return $order_param;
		} else {
			$data = $order_param['params'];
			$order = D('Baidu_order')->get_source_order($data['order_no']);
			if (empty($order)) {
				return array('error' => 1, 'msg' => '订单出错误了');
			}
			
			$order_param['pay_type'] = 'baidu';
			$order_param['return'] = 1;
			$order_param['is_mobile'] = $this->is_mobile;
			$order_param['order_type'] = $order['type'];
			$order_param['order_id'] = $order['order_id'];
			$order_param['is_own'] = intval($order['is_own']);
			
			$order_param['refund_id'] = $data['sp_refund_no'];
			$order_param['ret_code'] = $data['ret_code'];
			$order_param['ret_detail'] = $data['ret_detail'];
			$order_param['refund_time'] = time();
			return array('error' => 0, 'order_param' => $order_param);
		}
	}
}
?>