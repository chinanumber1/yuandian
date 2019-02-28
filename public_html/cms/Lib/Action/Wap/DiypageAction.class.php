<?php
class DiypageAction extends BaseAction{
    public function page(){
    	$condition_page['page_id'] = $_GET['page_id'];
		$now_page = D('Merchant_store_diypage')->where($condition_page)->find();
		if(empty($now_page)){
			$this->error_tips('该页面不存在');
		}
		
		D('Merchant_store_diypage')->where($condition_page)->setInc('hits');
		
		$now_store = D('Merchant_store')->get_store_by_storeId($now_page['store_id']);
		$now_store['url'] = U('Mall/store',array('store_id'=>$now_store['store_id']));
		$now_store['card_url'] = U('My_card/merchant_card',array('mer_id'=>$now_store['mer_id']));
		// dump($now_store);
		C('now_store',$now_store);
		
		
		$field_list = D('Merchant_store_diypage_field')->getParseFields($now_page['store_id'],$now_page['page_id']);
		// dump($field_list);
		
		$this->assign('is_mobile',$this->is_mobile());
		
		$this->assign('now_page',$now_page);
		$this->assign('now_store',$now_store);
		$this->assign('field_list',$field_list);
		
		// M
		
		$this->display();
    }
	/**
	 * 判断是否手机访问
	 */
	public function is_mobile(){
		if(preg_match('/(iphone|ipad|ipod|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))){
			return true;
		}else{
			return false;
		}
	}
}
?>