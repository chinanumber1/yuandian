<?php

//社区O2O的电话

class HousephoneAction extends BaseAction{
	public function index(){
		$now_village = $this->get_village($_GET['village_id']);
		$this->assign('now_village',$now_village);
		$phone_list = D('House_village_phone_category')->getAllCatPhoneList($now_village['village_id']);
		$this->assign('phone_list',$phone_list);
		// dump($now_village);
		$this->display();
	}
	protected function get_village($village_id){
		$now_village = D('House_village')->get_one($village_id);
		if(empty($now_village)){
			$this->error_tips('当前访问的小区不存在或未开放');
		}
		$this->assign('now_village',$now_village);
		return $now_village;
	}
}

?>