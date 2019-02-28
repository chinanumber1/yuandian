<?php
class Group_pass_relationModel extends Model{
	public function get_pass_array($order_id){
		$pass_array = $this->field(true)->where(array('order_id'=>$order_id))->select();
		return $pass_array;
	}

	public function get_pass_num($order_id,$status=0){
		$count = $this->where(array('order_id'=>$order_id,'status'=>$status))->count();
		return $count;
	}

	public function get_orderid_by_pass($group_pass){
		$order_id = $this->field('order_id')->where(array('group_pass'=>$group_pass))->find();
		return $order_id;
	}

	public function change_refund_status($order_id,$status=2){
		$this->where(array('order_id'=>$order_id,'status'=>array(in,'0,3')))->setField('status',$status);
	}
}
?>