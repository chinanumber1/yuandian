<?php
class Tmp_orderidModel extends Model{
	public function get_laste_order_info($order_type,$order_id){
		$laste_info = $this->where(array('order_type'=>$order_type,'order_id'=>$order_id))->order('id DESC')->find();
		return $laste_info;
	}

	public function get_laste_mer_order_info($merid){
		$laste_info = $this->where(array('merid'=>$merid))->order('id DESC')->find();
		return $laste_info;
	}
}