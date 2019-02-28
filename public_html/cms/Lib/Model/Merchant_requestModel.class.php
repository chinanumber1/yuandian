<?php
/*
 * 粉丝行为分析入口
 
 * 用户访问统计入口
 
 */
class Merchant_requestModel extends Model{
	/*
	 * 为了追求处理速度，不做 商户是否有效、参数是否有效的判断,也不处理param值的判断处理。
	 *
	 * $param参数里填写 字段名、增量值  例如   array('img_num'=>1);
	 * 
	 */
	public function add_request($mer_id,$param=array()){
		if(empty($mer_id)) return false;
		if(empty($param)) return false;
		//查找此商户今天的值，没有则添加
		$condition_merchant_request['mer_id'] = $mer_id;
		$condition_merchant_request['year'] = date('Y',$_SERVER['REQUEST_TIME']);
		$condition_merchant_request['month'] = date('m',$_SERVER['REQUEST_TIME']);
		$condition_merchant_request['day'] = date('d',$_SERVER['REQUEST_TIME']);
		
		$merchant_request = $this->field(true)->where($condition_merchant_request)->find();
		if(empty($merchant_request)){
			$merchant_request['id'] = $this->data($condition_merchant_request)->add();
		}
		
		if(empty($merchant_request['id'])) return false;
		
		foreach($param as $key=>$value){
			$data_merchant_request[$key] = $merchant_request[$key]+$value;
		}
		$data_merchant_request['time'] = mktime(0,0,0,$condition_merchant_request['month'],$condition_merchant_request['day'],$condition_merchant_request['year']);
		$condition_save_merchant_request['id'] = $merchant_request['id'];
		$this->where($condition_save_merchant_request)->data($data_merchant_request)->save();
	}
}
?>