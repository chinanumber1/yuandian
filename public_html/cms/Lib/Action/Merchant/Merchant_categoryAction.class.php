<?php
class Merchant_categoryAction extends BaseAction{
	public function ajax_cat_fid(){
		$database_area = D('Merchant_category');
		$condition_area['cat_fid'] = 0;
		$condition_area['cat_status'] = 1;
		$province_list = $database_area->field(array('cat_id','cat_fid','cat_name'))->where($condition_area)->order('`cat_sort` DESC,`cat_id` ASC')->select();
		if(!empty($province_list)){
			$return['error'] = 0;
			$return['list'] = $province_list;
		}else{
			$return['error'] = 1;
			$return['info'] = '没有已开启的分类！';
		}
		exit(json_encode($return));
	}
	public function ajax_cat_id(){
		$database_area = D('Merchant_category');
		$condition_area['cat_fid'] = intval($_POST['cat_id']);
		$condition_area['cat_status'] = 1;
		$city_list = $database_area->field(array('cat_id','cat_name'))->where($condition_area)->order('`cat_sort` DESC,`cat_id` ASC')->select();
		if(!empty($city_list)){
			$return['error'] = 0;
			$return['list'] = $city_list;
		}else{
			$return['error'] = 1;
			$return['info'] = $_POST['cat_name'] .' 分类下没有已开启的子分类！';
		}
		exit(json_encode($return));
	}
}
?>
