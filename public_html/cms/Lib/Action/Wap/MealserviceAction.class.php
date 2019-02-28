<?php

//快店 AJAX服务
class MealserviceAction extends BaseAction{
	//得到搜索的快店列表
	public function search(){
		$this->header_json();
		$return = D('Merchant_store')->get_list_by_search($_GET['w'], $_GET['sort'], true);
		echo json_encode($return);
	}
}

?>