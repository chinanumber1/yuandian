<?php
class House_village_phone_categoryModel extends Model{
	public function getCatList($village_id,$limit=0){
		return $this->field('`cat_id`,`cat_name`')->where(array('village_id'=>$village_id,'status'=>'1'))->order('`cat_sort` DESC')->limit($limit)->select();
	}
	//得到带分类的电话列表
	public function getAllCatPhoneList($village_id){
		$cat_list = $this->getCatList($village_id);
		$phone_list = M('House_village_phone')->field('`name`,`phone`,`cat_id`')->where(array('village_id'=>$village_id,'status'=>'1'))->order('`sort` DESC')->select();
		foreach($cat_list as $key=>$value){
			$cat_relation[$value['cat_id']] = $key;
		}
		foreach($phone_list as $key=>$value){
			$cat_list[$cat_relation[$value['cat_id']]]['phone_list'][] = $value;
		}
		foreach($cat_list as $key=>$value){
			if(empty($value['phone_list'])){
				unset($cat_list[$key]);
			}
		}
		return $cat_list;
	}
}