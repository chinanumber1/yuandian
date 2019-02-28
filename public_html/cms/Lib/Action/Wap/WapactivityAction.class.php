<?php
class WapactivityAction extends BaseAction{
	public function index(){
		$now_activity = D('Extension_activity')->where(array('begin_time'=>array('lt',$_SERVER['REQUEST_TIME']),'end_time'=>array('gt',$_SERVER['REQUEST_TIME'])))->order('`activity_id` ASC')->find();

		if(!empty($now_activity)){
			$activity_now = true;
		}else{
			$activity_now = false;
			$now_activity = D('Extension_activity')->where(array('begin_time'=>array('gt',$_SERVER['REQUEST_TIME'])))->order('`activity_id` ASC')->find();
			$this->assign('now_activity',$now_activity);
		}
		if(empty($now_activity)){
			$this->assign('jumpUrl',$this->config['site_url']);
			$this->error('暂时没有活动可供参与');
		}
		$now_activity['bg_pic'] = $this->config['site_url'].'/upload/extension/'.$now_activity['bg_pic'];
		$this->assign('now_activity',$now_activity);
		$this->assign('activity_now',$activity_now);
		
		if($now_activity['begin_time'] > $_SERVER['REQUEST_TIME']){
			$time_array['type'] = 1;
			list($time_array['d'],$time_array['h'],$time_array['m'],$time_array['s']) = explode(' ',date('j H i s',$now_activity['begin_time'] - $_SERVER['REQUEST_TIME']));
		}else{
			$time_array['type'] = 2;
			list($time_array['d'],$time_array['h'],$time_array['m'],$time_array['s']) = explode(' ',date('j H i s',$now_activity['end_time'] - $_SERVER['REQUEST_TIME']));
		}
		$this->assign('time_array',$time_array);
		
		$activity_type = $_GET['cat_url'] ? $_GET['cat_url'] : 'all';
		$activity_area = $_GET['area_url'] ? $_GET['area_url'] : 'all';
		
		$where_activity = '';
		if($activity_type != 'all'){
			$where_activity = ' AND `eal`.`type`='.$activity_type;
			$now_category['cat_url'] = $activity_type;
			$now_category['cat_name'] = $this->type_txt($activity_type);
			$this->assign('now_category',$now_category);
		}
		//活动商品列表
		$term_id = $now_activity['activity_id'];
		if($activity_area == 'all'){
			$now_activity = D('Extension_activity')->where(array('begin_time'=>array('lt',$_SERVER['REQUEST_TIME']),'end_time'=>array('gt',$_SERVER['REQUEST_TIME'])))->order('`activity_id` ASC')->select();
			foreach ($now_activity as $v) {
				$ac_id[] = $v['activity_id'];
			}
			$ac_id = implode(',',$ac_id);
			$tp_count = D('')->table(array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'merchant'=>'m'))->where("`eal`.`status`='1' AND `eal`.`activity_term` in($ac_id) AND `eal`.`mer_id`=`m`.`mer_id` AND `m`.`city_id`='{$this->config['now_city']}'".$where_activity)->count();
			if($tp_count){
				import('@.ORG.wap_group_page');
				$P = new Page($tp_count,20,'page');
				$activity_list = D('')->field('`eal`.`name` AS `product_name`,`m`.`name` AS `merchant_name`,`eal`.`money` as `eal_money`,`eal`.*,`m`.*')->table(array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'merchant'=>'m'))->where("`eal`.`status`='1' AND `eal`.`activity_term` in($ac_id)  AND `eal`.`mer_id`=`m`.`mer_id` AND `m`.`city_id`='{$this->config['now_city']}'".$where_activity)->order('`eal`.`is_finish` ASC,`eal`.`pigcms_id` DESC')->limit($P->firstRow.','.$P->listRows)->select();
			}
		}else{
			$now_area = D('Area')->get_area_by_areaUrl($activity_area,$activity_type,'activity');
			if(empty($now_area)){
				$this->assign('jumpUrl',U('Wapactivity/index'));
				$this->error('当前区域不存在');
			}
			$area_id = $now_area['area_id'];
			if ($now_area['area_type'] == 3) {
				$area_where = "`ms`.`area_id`='$area_id'";
			}else{
				$area_where = "`ms`.`circle_id`='$area_id'";
			}
			$tp_count = D('')->field('count(distinct(`eal`.`pigcms_id`)) AS `picms_count`')->table(array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'merchant_store'=>'ms'))->where("`eal`.`status`='1' AND `eal`.`activity_term`='$term_id' AND `eal`.`mer_id`=`m`.`mer_id` AND ".$area_where." AND `ms`.`mer_id`=`m`.`mer_id`".$where_activity)->find();
			if($tp_count){
				import('@.ORG.wap_group_page');
				$P = new Page($tp_count['picms_count'],20,'page');
				$activity_list = D('')->field('`eal`.`name` AS `product_name`,`m`.`name` AS `merchant_name`,`eal`.`money` as `eal_money`,`eal`.*,`m`.*')->table(array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'merchant_store'=>'ms'))->where("`eal`.`status`='1' AND `eal`.`activity_term`='$term_id' AND `eal`.`mer_id`=`m`.`mer_id` AND ".$area_where." AND `ms`.`mer_id`=`m`.`mer_id`".$where_activity)->group('`eal`.`pigcms_id`')->order('`eal`.`is_finish` ASC,`eal`.`pigcms_id` DESC')->limit($P->firstRow.','.$P->listRows)->select();
			}
		}
		if($activity_list){
			$extension_image_class = new extension_image();
			foreach($activity_list as &$value){
				$value['list_pic'] = $extension_image_class->get_image_by_path(array_shift(explode(';',$value['pic'])),'s');
				$value['url'] = U('Wapactivity/detail',array('id'=>$value['pigcms_id']));
				$value['money'] = floatval($value['eal_money']);
				$value['type_txt'] = $this->type_txt($value['type']);
			}
			$this->assign('activity_list',$activity_list);
			$this->assign('pagebar',$P->show());
		}
		// dump($activity_list);exit;
		//判断分类信息
		$cat_url = !empty($_GET['cat_url']) ? $_GET['cat_url'] : '';
		$this->assign('now_cat_url', $cat_url);
		
		//判断地区信息
		$area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';
		$this->assign('now_area_url',$area_url);
		
		$circle_id = 0;
		if(!empty($area_url)){
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->error('当前区域不存在！');
			}
			$this->assign('now_area', $tmp_area);
			
			if ($tmp_area['area_type'] == 3) {
				$now_area = $tmp_area;
			} else {
				$now_circle = $tmp_area;
				$this->assign('now_circle', $now_circle);
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {
					$this->error('当前区域不存在！');
				}
				$circle_url = $now_circle['area_url'];
				$circle_id = $now_circle['area_id'];
				$area_url = $now_area['area_url'];
			}
			$this->assign('top_area', $now_area);
			$area_id = $now_area['area_id'];
		}else{
			$area_id = 0;
		}
		
		//判断排序信息
		$sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';

		$long_lat = array('lat' => 0, 'long' => 0);
		$_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		if (empty($long_lat['long']) || empty($long_lat['lat'])) {
			$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
			$sort_array = array(
				array('sort_id'=>'defaults','sort_value'=>'默认排序'),
				array('sort_id'=>'time','sort_value'=>'最新发布'),
				array('sort_id'=>'solds','sort_value'=>'人气最高'),	
				array('sort_id'=>'part','sort_value'=>'参与人数'),	
			);
		} else {
			import('@.ORG.longlat');
			$longlat_class = new longlat();
			$location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
			$long_lat['lat'] = $location2['lat'];
			$long_lat['long'] = $location2['lng'];
			$sort_array = array(
				array('sort_id'=>'juli','sort_value'=>'离我最近'),
				array('sort_id'=>'time','sort_value'=>'最新发布'),
				array('sort_id'=>'solds','sort_value'=>'人气最高'),
				array('sort_id'=>'part','sort_value'=>'参与人数'),
			);
		}
		foreach($sort_array as &$value){
			if($sort_id == $value['sort_id']){
				$now_sort_array = $value;
				break;
			}
		}
		$this->assign('sort_array',$sort_array);
		$this->assign('now_sort_array',$now_sort_array);
		$all_area_list = D('Area')->get_all_area_list();
		$this->assign('all_area_list',$all_area_list);
		
		$all_category_list = $this->all_category_list();
		$this->assign('all_category_list',$all_category_list);
		//$long_lat['lat'] = 31.823263;
		//$long_lat['long'] = 117.235268;
		// $this->assign(D('Group')->wap_get_group_list_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id));
		
		/* 粉丝行为分析 */
		$this->behavior(array('model'=>'Activity_index'));
		
		$this->display();
	}
	protected function type_txt($type){
		switch($type){
			case '1':
				return '一元夺宝';
			case '2':
				return '优惠券';
			case '3':
				return '秒杀';
			case '4':
				return '红包';
			case '5':
				return '卡券';
		}
	}
	public function all_category_list(){
		return array(
			array('cat_name'=>'一元夺宝','cat_url'=>'1'),
			array('cat_name'=>'优惠券','cat_url'=>'2'),
			// array('cat_name'=>'秒杀','cat_url'=>'3'),
			// array('cat_name'=>'红包','cat_url'=>'4'),
			// array('cat_name'=>'卡券','cat_url'=>'5'), 
		);
	}
	public function detail(){
		$database_extension_activity_list = D('Extension_activity_list');
		$condition_extension_activity_list['pigcms_id'] = $_GET['id'];
		$now_activity = $database_extension_activity_list->field(true)->where($condition_extension_activity_list)->find();
		if(empty($now_activity)){
			$this->error_tips('该活动不存在',$this->config['site_url']);
		}
		$extension_image_class = new extension_image();
		$now_activity['all_pic'] = $extension_image_class->get_allImage_by_path($now_activity['pic']);
		$now_activity['info'] = str_replace('<img src="/upload/activity/content/','<img src="'.$this->config['site_url'].'/upload/activity/content/',$now_activity['info']);
		$activity_id = $now_activity['pigcms_id'];
		if($now_activity['part_count']){
			$tmp_part_list = D('')->field('`ear`.`pigcms_id`,`ear`.`time`,`ear`.`msec`,`ear`.`ip`,`ear`.`part_count`,`u`.`nickname`,`u`.`avatar`')->table(array(C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'user'=>'u'))->where("`ear`.`activity_list_id`='$activity_id' AND `ear`.`uid`=`u`.`uid`")->order('`ear`.`pigcms_id` DESC')->select();
			$part_list = $this->convertPartList($tmp_part_list);
			$this->assign('part_list',$part_list);
			// dump($part_list);
			
			if($this->user_session && D('Extension_activity_record')->where(array('activity_list_id'=>$activity_id,'uid'=>$this->user_session['uid']))->find()){
				$uid = $this->user_session['uid'];
				$tmp_user_part_list = D('')->field('`ear`.`pigcms_id`,`ear`.`time`,`ear`.`msec`,`ear`.`ip`,`ear`.`part_count`,`u`.`nickname`,`u`.`avatar`')->table(array(C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'user'=>'u'))->where("`ear`.`activity_list_id`='$activity_id' AND `ear`.`uid`='$uid' AND `ear`.`uid`=`u`.`uid`")->order('`ear`.`pigcms_id` DESC')->select();
				$user_part_list = $this->convertPartList($tmp_user_part_list);
				$this->assign('user_part_list',$user_part_list);
			}
		}
		$now_activity['money'] = floatval($now_activity['money']);
		$now_activity['lottery_number'] += 10000000;
		$this->assign('now_activity',$now_activity);
		// dump($now_activity);
		
		//找到当前商家
		$now_merchant = D('Merchant')->where(array('mer_id'=>$now_activity['mer_id']))->find();
		$this->assign('now_merchant',$now_merchant);
		
		//找到该商品所属的活动
		$parent_activity = D('Extension_activity')->field(true)->where(array('activity_id'=>$now_activity['activity_term']))->find();
		$this->assign('parent_activity',$parent_activity);
		
		$tpl_name = '';
		switch($now_activity['type']){
			case '1':
				//当前用户所有购买记录
				if($this->user_session && $now_activity['part_count']){
					$uid = $this->user_session['uid'];
					$lottery_user_list = D('')->field('`eyr`.`record_id`,`ear`.`time`,`ear`.`msec`,`eyr`.`number`')->table(array(C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_yiyuanduobao_record'=>'eyr'))->where("`ear`.`activity_list_id`='$activity_id' AND `ear`.`uid`='$uid' AND `eyr`.`record_id`=`ear`.`pigcms_id`")->order('`eyr`.`pigcms_id` DESC')->select();
					shuffle($lottery_user_list);
				}
				$this->assign('lottery_user_list',$lottery_user_list);

				if($now_activity['is_finish']){
					$activity_id = $now_activity['pigcms_id'];
					
					import('ORG.Net.IpLocation');
					$IpLocation = new IpLocation();
		
					//中奖数值
					$lottery_number_arr = array();
					for($i=0;$i<8;$i++){
						array_push($lottery_number_arr,substr($now_activity['lottery_number'],$i,1));
					}
					$this->assign('lottery_number_arr',$lottery_number_arr);
					//获奖人信息
					$lottery_user = D('User')->field('`uid`,`nickname`,`avatar`,`last_ip`')->where(array('uid'=>$now_activity['lottery_uid']))->find();
					$last_location = $IpLocation->getlocation(long2ip($lottery_user['last_ip']));
					$lottery_user['last_ip_txt'] = iconv('GBK','UTF-8',$last_location['country']);
					$this->assign('lottery_user',$lottery_user);
					//获奖人员所有购买记录
					$uid = $now_activity['lottery_uid'];
					$lottery_part_list = D('')->field('`eyr`.`record_id`,`ear`.`time`,`ear`.`msec`,`eyr`.`number`')->table(array(C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_yiyuanduobao_record'=>'eyr'))->where("`ear`.`activity_list_id`='$activity_id' AND `ear`.`uid`='$uid' AND `eyr`.`record_id`=`ear`.`pigcms_id`")->order('`eyr`.`pigcms_id` DESC')->select();
					shuffle($lottery_part_list);
					$lottery_part_listArr = array();
					foreach($lottery_part_list as &$value){
						$value['number']+= 10000000;
						if(empty($lottery_part_listArr[$value['record_id']])){
							$lottery_part_listArr[$value['record_id']] = array('time'=>$value['time'],'msec'=>$value['msec']);
						}
						$lottery_part_listArr[$value['record_id']]['list'][] = $value;
					}
					$this->assign('lottery_part_list',$lottery_part_list);
					$this->assign('lottery_part_listArr',$lottery_part_listArr);
				}
				$tpl_name = '1yuan';
				break;
			default:
				$tpl_name = 'coupon';
		}
		$this->display($tpl_name);
	}
	public function calc(){
		$database_extension_activity_list = D('Extension_activity_list');
		$condition_extension_activity_list['pigcms_id'] = $_GET['id'];
		$now_activity = $database_extension_activity_list->field(true)->where($condition_extension_activity_list)->find();
		if(empty($now_activity)){
			$this->error_tips('该活动不存在',$this->config['site_url']);
		}
		if(empty($now_activity['is_finish'])){
			$this->error_tips('该活动正在进行中',U('Wapactivity/detail',array('id'=>$now_activity['pigcms_id'])));
		}
		$this->assign('now_activity',$now_activity);
		
		//中奖的后50条购买记录
		$activity_id = $now_activity['pigcms_id'];
		$activity_record_list = D('')->field('`ear`.*,`u`.`uid`,`u`.`nickname`')->table(array(C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'user'=>'u'))->where("`ear`.`activity_list_id`='$activity_id' AND `ear`.`uid`=`u`.`uid`")->order('`ear`.`pigcms_id` DESC')->limit(50)->select();
		$allCount = 0;
		import('ORG.Net.IpLocation');
		$IpLocation = new IpLocation();
		foreach($activity_record_list as &$value){
			$tmp_time = date('His',$value['time']).$value['msec'];
			$allCount+=$tmp_time;
			$last_location = $IpLocation->getlocation(long2ip($value['ip']));
			$value['ip_txt'] = iconv('GBK','UTF-8',$last_location['country']);
		}
		$this->assign('activity_record_list',$activity_record_list);
		// dump($activity_record_list);
		$this->assign('allCount',$allCount);
					
		$this->display();
	}
	public function intro(){
		$database_extension_activity_list = D('Extension_activity_list');
		$condition_extension_activity_list['pigcms_id'] = $_GET['id'];
		$now_activity = $database_extension_activity_list->field(true)->where($condition_extension_activity_list)->find();
		if(empty($now_activity)){
			$this->error_tips('该活动不存在',$this->config['site_url']);
		}
		$now_activity['info'] = str_replace('<img src="/upload/activity/content/','<img src="'.$this->config['site_url'].'/upload/activity/content/',$now_activity['info']);
		$this->assign('now_activity',$now_activity);
		$this->display();
	}
	public function buy(){
		$database_extension_activity_list = D('Extension_activity_list');
		$condition_extension_activity_list['pigcms_id'] = $_GET['id'];
		$now_activity = $database_extension_activity_list->field(true)->where($condition_extension_activity_list)->find();
		if(empty($now_activity)){
			$this->error_tips('该活动不存在',$this->config['site_url']);
		}
		if($now_activity['is_finish']){
			$this->error_tips('该活动已结束');
		}
		$now_activity['info'] = str_replace('<img src="/upload/activity/content/','<img src="'.$this->config['site_url'].'/upload/activity/content/',$now_activity['info']);
		$this->assign('now_activity',$now_activity);
		// dump($now_activity);
		$this->display();
	}
	public function buy_coupon(){
		$database_extension_activity_list = D('Extension_activity_list');
		$condition_extension_activity_list['pigcms_id'] = $_GET['id'];
		$now_activity = $database_extension_activity_list->field(true)->where($condition_extension_activity_list)->find();
		if(empty($now_activity)){
			$this->error_tips('该活动不存在',$this->config['site_url']);
		}
		if($now_activity['is_finish']){
			$this->error_tips('该活动已结束');
		}
		$now_activity['money'] = floatval($now_activity['money']);
		$now_activity['info'] = str_replace('<img src="/upload/activity/content/','<img src="'.$this->config['site_url'].'/upload/activity/content/',$now_activity['info']);
		$this->assign('now_activity',$now_activity);
		// dump($now_activity);
		$this->display();
	}
	private function convertPartList($tmp_part_list){
		$part_list = array();
		import('ORG.Net.IpLocation');
		$IpLocation = new IpLocation();
		foreach($tmp_part_list as $value){
			$value['ip'] = long2ip($value['ip']);
			$last_location = $IpLocation->getlocation($value['ip']);
			$value['ip_txt'] = iconv('GBK','UTF-8',$last_location['country']);
			// $value['ip_txt'] = iconv('GBK','UTF-8',$last_location['area']);
			$arrKey = date('Y-m-d',$value['time']);
			if(empty($part_list[$arrKey])){
				$part_list[$arrKey] = array();
			}
			array_push($part_list[$arrKey],$value);
		}
		return $part_list;
	}
}