<?php
class PayModel extends Model{
	public function __construct() {}
	/*根据 支付平台的英文 和 是否移动端支付 得到中文名称*/
	public function get_pay_name($pay_type,$is_mobile_pay=1, $paid = 1){
		switch($pay_type){
			case 'alipay':
				$pay_type_txt = '支付宝';
				break;
			case 'alipayh5':
				$pay_type_txt = '支付宝(H5)';
				break;
			case 'tenpay':
				$pay_type_txt = '财付通';
				break;
			case 'yeepay':
				$pay_type_txt = '易宝支付';
				break;
			case 'allinpay':
				$pay_type_txt = '通联支付';
				break;
			case 'chinabank':
				$pay_type_txt = '网银在线';
				break;
			case 'weixin':
				$pay_type_txt = '微信支付';
				break;
			case 'baidu':
				$pay_type_txt = '百度钱包';
				break;
			case 'unionpay':
				$pay_type_txt = '银联支付';
				break;
			case 'weifutong':
				$pay_type_txt = C('config.pay_weifutong_alias_name');
				break;
			case 'offline':
				$pay_type_txt = '货到付款';
				break;
			case 'ccb':
				$pay_type_txt = '建设银行';
				break;	
			default:
				if ($paid) {
					$pay_type_txt = '余额支付';
				} else {
					$pay_type_txt = '未支付';
					return '未支付';
				}
				
		}
		if($is_mobile_pay == 1 && $pay_type != 'alipayh5'){
			$pay_type_txt .= '(微信端)';
		} elseif ($is_mobile_pay == 2) {
			$pay_type_txt .= '(App)';
		}elseif ($is_mobile_pay == 3) {
			$pay_type_txt .= '(小程序)';
		}
		return $pay_type_txt;
	}
}

?>