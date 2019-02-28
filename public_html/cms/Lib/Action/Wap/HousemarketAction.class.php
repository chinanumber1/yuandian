<?php

//社区超市

class HousemarketAction extends BaseAction{
	public function index(){
		$now_village = $this->get_village($_GET['village_id']);
		if(empty($now_village['shop_id'])){
			if(!$this->config['house_market_shopId']){
				$this->error_tips('平台未开通'.$this->config['house_market_name']);
			}
			$this->assign('house_market_shopId',$this->config['house_market_shopId']);
		}else{
			$this->assign('house_market_shopId',$now_village['shop_id']);
		}
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