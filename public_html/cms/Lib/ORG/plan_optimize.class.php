<?php
class plan_optimize{
	public function runTask(){
		M()->query('OPTIMIZE TABLE `pigcms_process_plan`');
		M()->query('OPTIMIZE TABLE `pigcms_process_plan_msg`');
		return true;
	}
}
?>