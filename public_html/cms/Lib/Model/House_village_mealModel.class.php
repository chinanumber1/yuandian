<?php
class House_village_mealModel extends Model{
	/*得到小区绑定的快店列表*/
	public function get_limit_list($village_id,$limit,$user_long_lat){
		$store_list = D('')->field('`ms`.*,`msm`.*')->table(array(C('DB_PREFIX').'merchant_store'=>'ms',C('DB_PREFIX').'house_village_meal'=>'hvm'))->where("`ms`.`have_meal`='1' AND `ms`.`status`='1' AND `ms`.`store_id`=`hvm`.`store_id` AND `hvm`.`village_id`='$village_id'")->order('`hvm`.`sort` DESC,`hvm`.`pigcms_id` ASC')->limit($limit)->select();
		if($store_list){
			$store_image_class = new store_image();
			
			foreach($store_list as $key=>$value){
				$images = $store_image_class->get_allImage_by_path($value['pic_info']);
				$store_list[$key]['list_pic'] = $images ? array_shift($images) : array();
				
				$store_list[$key]['mean_money'] = floatval($value['mean_money']);
				$store_list[$key]['wap_url'] = U('Food/shop',array('mer_id'=>$value['mer_id'],'store_id'=>$value['store_id']));
                                
                                if(defined('IS_INDEP_HOUSE')){
                                    $store_list[$key]['cat_url'] = str_replace('wap.php', C('INDEP_HOUSE_URL'), $value['cat_url']);
                                }
			}
			
			if($user_long_lat){
				$rangeSort = array();

				foreach($store_list as &$storeValue){
					$storeValue['Srange'] = getDistance($user_long_lat['lat'],$user_long_lat['long'],$storeValue['lat'],$storeValue['long']);
					$storeValue['range'] = getRange($storeValue['Srange'],false);
					$rangeSort[] = $storeValue['Srange'];
				}
				array_multisort($rangeSort, SORT_ASC, $store_list);
			}
			return $store_list;
		}else{
			return false;
		}
		
	}
	
	/*得到小区绑定的快店列表(有分页)*/
	public function get_limit_list_page($village_id,$pageSize=20,$user_long_lat = array()){
	
		$condition_table = array(C('DB_PREFIX').'merchant_store'=>'ms',C('DB_PREFIX').'house_village_meal'=>'hvm',C('DB_PREFIX').'merchant'=>'m');
		$condition_field = "`ms`.*,`m`.`name` AS `merchant_name`,`ms`.`name` AS `store_name`,`hvm`.`url` ";
		$condition_where = "`ms`.`have_meal`='1' AND `ms`.`status`='1' AND `ms`.`store_id`=`hvm`.`store_id` AND `hvm`.`village_id`='$village_id' AND `m`.`mer_id`=`ms`.`mer_id` ";
	
		$count_meal = D('')->table($condition_table)->where($condition_where)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count_meal,$pageSize,'page');
		$store_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order('`hvm`.`sort` DESC,`hvm`.`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
	
		if($store_list){
			$store_image_class = new store_image();
			
			foreach($store_list as $key=>$value){
				$images = $store_image_class->get_allImage_by_path($value['pic_info']);
				$store_list[$key]['list_pic'] = $images ? array_shift($images) : array();
				
				$store_list[$key]['mean_money'] = floatval($value['mean_money']);
				$store_list[$key]['wap_url'] = U('Food/shop',array('mer_id'=>$value['mer_id'],'store_id'=>$value['store_id']));
			}
			if($user_long_lat){
				foreach($store_list as &$storeValue){
					$storeValue['Srange'] = getDistance($user_long_lat['lat'],$user_long_lat['long'],$storeValue['lat'],$storeValue['long']);
					$storeValue['range'] = getRange($storeValue['Srange'],false);
				}
			}
			
			$return = array();
			if($store_list){
				$return['totalPage'] = ceil($count_meal/$pageSize);
				$return['meal_count'] = count($store_list);
				$return['pagebar'] = $p->show();
				$return['store_list'] = $store_list;
			}
			return $return;
		}else{
			return false;
		}
	
	}
}

?>