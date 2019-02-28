<?php
class House_village_groupModel extends Model{
	/*得到小区绑定的团购列表*/
	public function get_limit_list($village_id,$limit='',$user_long_lat=''){
		
		$now_time = $_SERVER['REQUEST_TIME'];
		
		$group_list = D('')->field('`g`.`name` AS `group_name`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*')->table(array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_group'=>'hvg'))->where("`g`.`mer_id`=`m`.`mer_id` AND `g`.`status`='1' AND `m`.`status`='1' AND `g`.`type`='1' AND `g`.`begin_time`<'$now_time' AND `g`.`end_time`>'$now_time' AND `hvg`.`group_id`=`g`.`group_id` AND `hvg`.`village_id`='$village_id'")->order('`hvg`.`sort` DESC,`hvg`.`pigcms_id` DESC')->limit($limit)->select();
		if($group_list){
			$group_image_class = new group_image();
			
			foreach($group_list as $key=>$value){
				unset($group_list[$key]['content'],$group_list[$key]['txt_info'],$group_list[$key]['cue']);
				$tmp_pic_arr = explode(';',$value['pic']);
				$group_list[$key]['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$group_list[$key]['url'] = D('Group')->get_group_url($value['group_id'],true);
				$group_list[$key]['price'] = floatval($value['price']);
				$group_list[$key]['old_price'] = floatval($value['old_price']);
				$group_list[$key]['wx_cheap'] = floatval($value['wx_cheap']);
                                
                                if(defined('IS_INDEP_HOUSE')){
                                    $group_list[$key]['cat_url'] = str_replace('wap.php', C('INDEP_HOUSE_URL'), $value['cat_url']);
                                }
			}
			
			if($user_long_lat){
				$group_store_database = D('Group_store');
				$rangeSort = array();
				foreach($group_list as &$storeGroupValue){
					$tmpStoreList = $group_store_database->get_storelist_by_groupId($storeGroupValue['group_id']);
					if($tmpStoreList){
						foreach($tmpStoreList as &$tmpStore){
							$tmpStore['Srange'] = getDistance($user_long_lat['lat'],$user_long_lat['long'],$tmpStore['lat'],$tmpStore['long']);
							$tmpStore['range'] = getRange($tmpStore['Srange'],false);
							$rangeSort[] = $tmpStore['Srange'];
						}
						array_multisort($rangeSort, SORT_ASC, $tmpStoreList);
						$storeGroupValue['store_list'] = $tmpStoreList;	
						$storeGroupValue['range'] = $tmpStoreList[0]['range'];
					}
				}
			}
			return $group_list;
		}else{
			return false;
		}
		
	}
	
	/*得到小区绑定的团购列表(有分页)*/
	public function get_limit_list_page($village_id,$pageSize=20,$user_long_lat = array(),$is_wap = false){

		$now_time = $_SERVER['REQUEST_TIME'];
		$condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_group'=>'hvg');
		$condition_field = "`g`.`name` AS `group_name`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*,`hvg`.`sort`,`hvg`.`url`";
		$condition_where = "`g`.`mer_id`=`m`.`mer_id` AND `g`.`status`='1' AND `m`.`status`='1' AND `g`.`type`='1' AND `g`.`begin_time`<'$now_time' AND `g`.`end_time`>'$now_time' AND `hvg`.`group_id`=`g`.`group_id` AND `hvg`.`village_id`='$village_id'";
		
		$count_group = D('')->table($condition_table)->where($condition_where)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count_group,$pageSize,'page');
		$group_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order('`hvg`.`sort` DESC,`hvg`.`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();

		if($group_list){
			$group_image_class = new group_image();
				
			foreach($group_list as $key=>$value){
				unset($group_list[$key]['content'],$group_list[$key]['txt_info'],$group_list[$key]['cue']);
				$tmp_pic_arr = explode(';',$value['pic']);
				$group_list[$key]['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
				$group_list[$key]['url'] = D('Group')->get_group_url($value['group_id'],$is_wap);
				$group_list[$key]['price'] = floatval($value['price']);
				$group_list[$key]['old_price'] = floatval($value['old_price']);
				$group_list[$key]['wx_cheap'] = floatval($value['wx_cheap']);
			}
			
			if($user_long_lat){
				$group_store_database = D('Group_store');
				foreach($group_list as &$storeGroupValue){
					$tmpStoreList = $group_store_database->get_storelist_by_groupId($storeGroupValue['group_id']);
					if($tmpStoreList){
						foreach($tmpStoreList as &$tmpStore){
							$tmpStore['Srange'] = getDistance($user_long_lat['lat'],$user_long_lat['long'],$tmpStore['lat'],$tmpStore['long']);
							$tmpStore['range'] = getRange($tmpStore['Srange'],false);
						}
						$storeGroupValue['store_list'] = $tmpStoreList;	
						$storeGroupValue['range'] = $tmpStoreList[0]['range'];
					}
				}
			}
			$return = array();
			if($group_list){
				$return['totalPage'] = ceil($count_group/$pageSize);
				$return['group_count'] = count($group_list);
				$return['pagebar'] = $p->show();
				$return['group_list'] = $group_list;
			}

			return $return;
		}else{
			return false;
		}
	
	}

	public function get_order_list($type,$village_id='',$time_condition='',$is_system = 0){

		$house_table  = $type;
		if($village_id>0){
			$where['village_id'] = $village_id;

			$condition['village_id']= $village_id;
		}else{
			$where='1=1';
			$condition['village_id']= array('neq','');
		}
		if($is_system){
			import('@.ORG.system_page');
		}else{
			import('@.ORG.merchant_page');
		}
		//$p = new Page($count_group,$pageSize,'page');
		switch($type){
			case 'group':
				$order_table = 'Group_order';
				$ids = M('House_village_group')->where($where)->getField('group_id,pigcms_id');
				foreach ($ids as $k=>$v) {
					$tmp[] = $k;
				}
				$ids = implode(',', $tmp);
				$condition['o.group_id']= array('in', $ids);
				$condition['o.paid']= 1;
				$condition['o.status']= array('in','1,2');
				$condition['_string'] = '(balance_pay+payment_money+merchant_balance) >0';
				if($time_condition!=''){
					$condition['_string'] .= ' AND '.$time_condition;
				}
				$field ='o.real_orderid as order_id,o.order_name as des,o.phone,o.total_money ,o.pay_time,(balance_pay+payment_money+merchant_balance) as pay_in_fact,o.pay_time,o.status,m.name as mer_name';
				$count  = D($order_table)->join('as o left join '.C('DB_PREFIX').'merchant as m ON o.mer_id = m.mer_id ')->where($condition)->count();
				$p = new Page($count,20);
				$order_list = D($order_table)->field($field)->join('as o left join '.C('DB_PREFIX').'merchant as m ON o.mer_id = m.mer_id ')->where($condition)->order('o.pay_time DESC')->limit($p->firstRow,$p->listRows)->select();

				break;
			case 'shop':
				$order_table = 'Shop_order';
				$condition['village_id'] = $village_id;
				// $condition['pay_type'] = array('neq','offline');
				// $condition['o.paid']= 1;
				$condition['o.status']= array('in','1,2,3');
				// $condition['_string'] = '(balance_pay+payment_money+merchant_balance) >0';
				if($time_condition!=''){
					// $condition['_string'] .= ' AND '.$time_condition;
				}
				$field ='o.real_orderid as order_id,`s`.`name` AS `des`,o.userphone as phone,o.pay_time,o.total_price as total_money ,(balance_pay+payment_money+merchant_balance+score_deducte+card_price+coupon_price+score_deducte-no_bill_money) as pay_in_fact,`s`.*,`m`.isverify,`hvs`.*,o.pay_time,o.status,m.name as mer_name';
				
				$count  =D('')->table(array(C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_store'=>'hvs',C('DB_PREFIX').'shop_order'=>'o'))->where("`s`.`mer_id`=`m`.`mer_id` AND `hvs`.`store_id`=`s`.`store_id` AND `o`.`village_id`='$village_id' AND o.store_id=s.store_id AND o.village_id=hvs.village_id AND o.status in (1,2,3)")->order('`hvs`.`sort` DESC,`hvs`.`pigcms_id` DESC')->count();
				$p = new Page($count,20);
				// $order_list = D($order_table)->field($field)->join('as o left join '.C('DB_PREFIX').'merchant as m ON o.mer_id = m.mer_id left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = o.store_id left join '.C('DB_PREFIX').'house_village_store hvs ON hvs.village_id = o.village_id')->where($condition)->order('o.pay_time DESC')->select();

				$order_list = D('')->field($field)->table(array(C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_store'=>'hvs',C('DB_PREFIX').'shop_order'=>'o'))->where("`s`.`mer_id`=`m`.`mer_id` AND `hvs`.`store_id`=`s`.`store_id` AND `o`.`village_id`='$village_id' AND o.store_id=s.store_id AND o.village_id=hvs.village_id AND o.status in (1,2,3)")->order('`hvs`.`sort` DESC,`hvs`.`pigcms_id` DESC')->select();


				break;
			case 'meal':
				$order_table = 'Foodshop_order';
				$house_table  = 'meal';
				$ids = M('House_village_meal')->field('store_id')->where($where)->select();
				foreach ($ids as $v) {
					$tmp[] = $v[ 'store_id'];
				}
				$ids = implode(',', $tmp);
				$ids && $condition = array( 'o.store_id' => array('in', $ids));
				$condition['village_id'] = $village_id;
				//$condition['o.paid']= 1;
				$condition['o.status']= array('in','1,3,4');
				$condition['_string'] = '(p.system_balance+p.pay_money+p.merchant_balance_pay) >0';
				if($time_condition!=''){
					$condition['_string'] .= ' AND '.$time_condition;
				}
				$field ='o.real_orderid as order_id,s.name as des,o.phone,o.pay_time,o.total_price as total_money ,(p.system_balance+p.pay_money+p.merchant_balance_pay) as pay_in_fact,o.pay_time,o.status,m.name as mer_name';
				$count  = D($order_table)->join('as o left join '.C('DB_PREFIX').'merchant as m ON o.mer_id = m.mer_id left join '.C('DB_PREFIX').'plat_order p ON p.business_id = o.order_id')->where($condition)->count();
				$p = new Page($count,20);
				$order_list = D($order_table)->field($field)->join('as o left join '.C('DB_PREFIX').'merchant as m ON o.mer_id = m.mer_id left join '.C('DB_PREFIX').'plat_order p ON p.business_id = o.order_id left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = o.store_id')->where($condition)->order('o.pay_time DESC')->limit($p->firstRow,$p->listRows)->select();
				$tmp_order_id = 0;
				$tmp_key = array();
				foreach ($order_list as $k=>$v	) {
					if($tmp_order_id==$v['order_id']&&!empty($tmp_key)){
						$order_list[$tmp_key[$v['order_id']]]['pay_in_fact']+=$v['pay_in_fact'];
						unset($order_list[$k]);
						continue;
					}
					$tmp_key[$v['order_id']] = $k;
					$tmp_order_id = $v['order_id'];
				}
				//dump($order_list);
				break;
			case 'appoint':
				$order_table = 'Appoint_order';
				$ids = M('House_village_appoint' )->field('appoint_id')->where($where)->getField('appoint_id,pigcms_id');
				foreach ($ids as $k=>$v) {
					$tmp[] = $k;
				}
				$ids = implode(',', $tmp);
				$condition['o.appoint_id']= array('in', $ids);
				$condition['o.paid']= 1;
				$condition['o.service_status']= array('gt',0);
				//$condition = array('o.service_status'=>array('gt',0),'o.appoint_id' => array('in', $ids));
				$condition['_string'] = '((o.payment_money+product_balance_pay+o.user_pay_money+o.product_payment_price) >0 OR o.payment_money>0)';
				if($time_condition!=''){
					$condition['_string'] .= ' AND '.$time_condition;
				}
				///dump($condition);die;
				$field ='o.order_id,a.appoint_name as des,u.phone,(o.payment_money+o.product_price) as total_money ,o.product_id,o.payment_money,o.payment_status,(o.payment_money+product_balance_pay+o.user_pay_money+o.product_payment_price) as pay_in_fact,o.pay_time,o.service_status as status,m.name as mer_name,o.pay_time';
				$count  =D($order_table)->join('as o left join '.C('DB_PREFIX').'merchant as m ON o.mer_id = m.mer_id ')->where($condition)->count();
				$p = new Page($count,20);
				$order_list = D($order_table)->field($field)->join('as o left join '.C('DB_PREFIX').'merchant as m ON o.mer_id = m.mer_id left join '.C('DB_PREFIX').'user u ON u.uid = o.uid left join '.C('DB_PREFIX').'appoint a ON o.appoint_id = a.appoint_id')->where($condition)->limit($p->firstRow,$p->listRows)->order('o.pay_time DESC')->select();
				break;

		}
		return array('order_list'=>$order_list,'page'=>$p->show());
	}
}

?>