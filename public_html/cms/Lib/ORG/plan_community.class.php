<?php
	/*
	 * 社群，群頭像生成
	 * */
class plan_community extends plan_base{
	public function runTask($param){

		$res = D('Community_info')->comm_runtask($param);

		return true;
	}
}
?>