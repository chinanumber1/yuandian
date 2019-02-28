<?php
class WorkerserviceAction extends BaseAction{
    //得到搜索的团购列表
	public function search(){
		$this->header_json();	
		$group_return = D('Merchant_workers')->get_worker_list_by_keywords($_GET['w'],$_GET['sort'],true);
		echo json_encode($group_return);
	}
}
?>