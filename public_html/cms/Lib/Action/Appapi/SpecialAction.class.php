<?php
/*
 * 地图处理
 *
 */
class SpecialAction extends BaseAction{
	public function index(){
		if(empty($_POST['id'])){
			$this->returnCode('20150001');
		}
	}
}