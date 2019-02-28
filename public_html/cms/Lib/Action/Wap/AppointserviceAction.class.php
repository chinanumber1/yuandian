<?php

//预约 AJAX服务
class AppointserviceAction extends BaseAction{
	//得到搜索的快店列表
	public function search(){
		$this->header_json();
		$return = D('Appoint')->get_list_by_search($_GET['w'], $_GET['sort'], true);
	
		echo json_encode($return);
	}
}

?>