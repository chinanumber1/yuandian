<?php
class Group_storeModel extends Model{
	/*得到指定团购ID下的店铺列表 包含区域信息*/
	public function get_storelist_by_groupId($group_id){
		$store_list = S('wap_group_store_'.$group_id);
		if(empty($store_list)){
			$store_list = D('')->table(array(C('DB_PREFIX').'group_store'=>'gc',C('DB_PREFIX').'merchant_store'=>'mc',C('DB_PREFIX').'area'=>'a'))->where("`gc`.`group_id`='$group_id' AND`gc`.`store_id`=`mc`.`store_id` AND `mc`.`city_id`='".C('config.now_city')."' AND `gc`.`area_id`=`a`.`area_id`")->order('`mc`.`store_id` ASC')->select();
			S('wap_group_store_'.$group_id,$store_list,360);
		}
		return $store_list;
	}
}

?>