<?php

class House_village_pay_typeModel extends Model
{
	/**
	 * [get_payment_select 获取多个信息]
	 * @return [type] [description]
	 */
	public function get_pay_type_select($where){
		$info_list = D('House_village_pay_type')->where($where)->select();
		if($info_list !== false){
			return $info_list;
		}else{
			return false;
		}
	}
}

?>