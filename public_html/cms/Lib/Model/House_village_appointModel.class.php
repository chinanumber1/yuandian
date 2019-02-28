<?php
class House_village_appointModel extends Model{
	/*得到小区绑定的团购列表*/
	public function get_limit_list($village_id,$limit,$user_long_lat){
		
		$now_time = $_SERVER['REQUEST_TIME'];
		
		$appoint_list = D('')->field('`m`.`name` AS `merchant_name`,`a`.*,`m`.*')->table(array(C('DB_PREFIX').'appoint'=>'a',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_appoint'=>'hva'))->where("`a`.`mer_id`=`m`.`mer_id` AND `a`.`check_status`='1' AND `a`.`appoint_status`='0' AND `m`.`status`='1' AND `a`.`start_time`<'$now_time' AND `a`.`end_time`>'$now_time' AND `hva`.`appoint_id`=`a`.`appoint_id` AND `hva`.`village_id`='$village_id'")->order('`hva`.`sort` DESC,`hva`.`pigcms_id` ASC')->limit($limit)->select();
		if($appoint_list){
			$appoint_image_class = new appoint_image();
			foreach($appoint_list as $key=>$value){
				$tmp_pic_arr = explode(';',$value['pic']);
				$appoint_list[$key]['list_pic'] = $appoint_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$appoint_list[$key]['url'] = D('Appoint')->get_appoint_url($value['appoint_id'],true);
				$appoint_list[$key]['payment_money'] = floatval($value['payment_money']);
				$appoint_list[$key]['appoint_sum'] = intval($value['appoint_sum']);
			}
			if($user_long_lat){
				$appoint_store_database = D('Appoint_store');
				$rangeSort = array();
				foreach($appoint_list as &$storeAppointValue){
					$tmpStoreList = $appoint_store_database->get_storelist_by_appointId($storeAppointValue['appoint_id']);
					if($tmpStoreList){
						foreach($tmpStoreList as &$tmpStore){
							$tmpStore['Srange'] = getDistance($user_long_lat['lat'],$user_long_lat['long'],$tmpStore['lat'],$tmpStore['long']);
							$tmpStore['range'] = getRange($tmpStore['Srange'],false);
							$rangeSort[] = $tmpStore['Srange'];
						}
						array_multisort($rangeSort, SORT_ASC, $tmpStoreList);
						$storeAppointValue['store_list'] = $tmpStoreList;	
						$storeAppointValue['range'] = $tmpStoreList[0]['range'];
					}
				}
			}
			return $appoint_list;
		}else{
			return false;
		}
		
	}
	
	/*得到小区绑定的团购列表(有分页)*/
	public function get_limit_list_page($village_id,$pageSize=20,$user_long_lat = array()){
		$now_time = $_SERVER['REQUEST_TIME'];
		
		$condition_table = array(C('DB_PREFIX').'appoint'=>'a',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_appoint'=>'hva');
		$condition_field = "`m`.`name` AS `merchant_name`,`a`.*,`m`.*,`hva`.`pigcms_id`,`hva`.`village_id`,`hva`.`label`,`hva`.`index_show`,`hva`.`url`";
		$condition_where = "`a`.`mer_id`=`m`.`mer_id` AND `a`.`check_status`='1' AND `a`.`appoint_status`='0' AND `m`.`status`='1' AND `a`.`start_time`<'$now_time' AND `a`.`end_time`>'$now_time' AND `hva`.`appoint_id`=`a`.`appoint_id` AND `hva`.`village_id`='$village_id'";
		
		$count_appoint = D('')->table($condition_table)->where($condition_where)->count();
                //echo D('')->_sql();exit;
		
		import('@.ORG.merchant_page');
		$p = new Page($count_appoint,$pageSize,'page');
		$appoint_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order('`hva`.`sort` DESC,`hva`.`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		
		if($appoint_list){
			$appoint_image_class = new appoint_image();
			foreach($appoint_list as $key=>$value){
				$tmp_pic_arr = explode(';',$value['pic']);
				$appoint_list[$key]['list_pic'] = $appoint_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				//$appoint_list[$key]['url'] = D('Appoint')->get_appoint_url($value['appoint_id'],true);
				$appoint_list[$key]['payment_money'] = floatval($value['payment_money']);
				$appoint_list[$key]['appoint_sum'] = intval($value['appoint_sum']);
			}
			if($user_long_lat){
				$appoint_store_database = D('Appoint_store');
				foreach($appoint_list as &$storeAppointValue){
					$tmpStoreList = $appoint_store_database->get_storelist_by_appointId($storeAppointValue['appoint_id']);
					if($tmpStoreList){
						foreach($tmpStoreList as &$tmpStore){
							$tmpStore['Srange'] = getDistance($user_long_lat['lat'],$user_long_lat['long'],$tmpStore['lat'],$tmpStore['long']);
							$tmpStore['range'] = getRange($tmpStore['Srange'],false);
						}
						$storeAppointValue['store_list'] = $tmpStoreList;
						$storeAppointValue['range'] = $tmpStoreList[0]['range'];
					}
				}
			}
			
			$return = array();
			if($appoint_list){
				$return['totalPage'] = ceil($count_appoint/$pageSize);
				$return['appoint_count'] = count($appoint_list);
				$return['pagebar'] = $p->show();
				$return['appoint_list'] = $appoint_list;
			}
			
			return $return;
		}else{
			return false;
		}
		
	}
}

?>