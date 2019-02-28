<?php
class plan_clear_adminlog extends plan_base{
	public function runTask(){
		$clear_time = time() - 86400*30;
		M('Admin_log')->where(array('add_time'=>array('lt',$clear_time)))->delete();
		return true;
	}
}
?>