<?php
/*
 * 首页
 *
 */
class IndexAction extends BaseAction {
    public function index(){
		//顶部广告
		$index_top_adver = D('Adver')->get_adver_by_key('index_top');
		$this->assign('index_top_adver',$index_top_adver);

		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12);
    	$this->assign('search_hot_list',$search_hot_list);

		//右侧广告
		$index_right_adver = D('Adver')->get_adver_by_key('index_right',3);
		$this->assign('index_right_adver',$index_right_adver);

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);

		//热门二级分类
		$hot_group_category = D('Group_category')->get_hot_category();
		$this->assign('hot_group_category',$hot_group_category);

		//所有区域
		$all_area_list = D('Area')->get_area_list();
		$this->assign('all_area_list',$all_area_list);

		//热门商圈
		$hot_circle_list = D('Area')->get_hot_circle_list();
		$this->assign('hot_circle_list',$hot_circle_list);

		//最新团购
		$new_group_list = D('Group')->get_group_list('new',12);
		$this->assign('new_group_list',$new_group_list);

		//手动首页排序团购
		$index_sort_group_list = D('Group')->get_group_list('index_sort',12);
		$this->assign('index_sort_group_list',$index_sort_group_list);

		//首页大分类下的团购列表
		$index_group_list = D('Group')->get_category_arr_group_list($all_category_list,12);
		
		$this->assign('index_group_list',$index_group_list);

		//活动列表
		if($this->config['activity_open']){
			$now_activity = D('Extension_activity')->where(array('begin_time'=>array('lt',$_SERVER['REQUEST_TIME']),'end_time'=>array('gt',$_SERVER['REQUEST_TIME'])))->order('`activity_id` ASC')->find();
			if($now_activity){
				// list($time_array['j'],$time_array['h'],$time_array['m'],$time_array['s']) = explode(' ',date('j H i s',$now_activity['end_time'] - $_SERVER['REQUEST_TIME']));
				$time = $now_activity['end_time'] - $_SERVER['REQUEST_TIME'];
				$time_array['j'] = floor($time/86400);
				$time_array['h'] = floor($time%86400/3600);
				$time_array['m'] = floor($time%86400%3600/60);
				$time_array['s'] = floor($time%86400%60);
				// $activity_list = D('Extension_activity_list')->field('`pigcms_id`,`name`,`title`,`index_pic`,`part_count`')->where(array('activity_term'=>$now_activity['activity_id'],'status'=>'1','is_finish'=>'0','index_sort'=>array('neq','0')))->order('`index_sort` DESC,`pigcms_id` DESC')->limit(6)->select();
				$activity_list = D('')->field('`eac`.`pigcms_id`,`eac`.`name`,`eac`.`title`,`eac`.`index_pic`,`eac`.`part_count`')->table(array(C('DB_PREFIX').'extension_activity_list'=>'eac',C('DB_PREFIX').'merchant'=>'m'))->where("`eac`.activity_term='{$now_activity['activity_id']}' AND `eac`.`status`='1' AND `eac`.`is_finish`='0' AND `eac`.`index_sort`>0 AND `eac`.`mer_id`=`m`.`mer_id` AND `m`.`city_id`='{$this->config['now_city']}'")->order('`eac`.`index_sort` DESC,`eac`.`pigcms_id` DESC')->limit(6)->select();
				if(empty($activity_list)){
					unset($now_activity);
				}
				$this->assign('now_activity',$now_activity);
				$this->assign('time_array',$time_array);

				// $activity_list = D('Extension_activity_list')->field('`pigcms_id`,`name`,`title`,`index_pic`,`part_count`')->where(array('activity_term'=>$now_activity['activity_id'],'status'=>'1','index_sort'=>array('neq','0')))->order('`index_sort` DESC,`pigcms_id` DESC')->limit(6)->select();

				$extension_image_class = new extension_image();
				foreach($activity_list as &$value){
					$value['index_pic'] = $this->config['site_url'].'/upload/activity/index_pic/'.$value['index_pic'];
					$value['url'] = $this->config['site_url'].'/activity/'.$value['pigcms_id'].'.html';
				}
				$this->assign('activity_list',$activity_list);
				$this->assign('activity_url',$this->config['site_url'].'/activity/');
			}

			//本站信息
			$index_site_info = S('index_site_info');
			if(empty($index_site_info)){
				$today_zero_time = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME']) . ' 00:00:00');
				$index_site_info = array();
				$index_site_info['user_count'] = D('User')->where(array('add_time'=>array('gt',$today_zero_time)))->count('uid');
				$index_site_info['merchant_count'] = D('Merchant')->where(array('reg_time'=>array('gt',$today_zero_time)))->count('mer_id');
				$index_site_info['merchant_store_count'] = D('Merchant_store')->where(array('last_time'=>array('gt',$today_zero_time)))->count('store_id');
				$index_site_info['group_count'] = D('Group')->where(array('last_time'=>array('gt',$today_zero_time)))->count('group_id');
				$index_site_info['meal_store_count'] = D('Merchant_store_meal')->where(array('last_time'=>array('gt',$today_zero_time)))->count('store_id');
				// dump($index_site_info);
			}
			$this->assign('index_site_info',$index_site_info);
		}
		//友情链接
		$flink_list = D('Flink')->get_flink_list();
		$this->assign('flink_list',$flink_list);

		$this->display();
    }

	public function group_index_sort(){
		$group_id = $_POST['id'];
		$database_index_group_hits = D('Index_group_hits_'.substr(dechex($group_id),-1));
		$data_index_group_hits['group_id'] = $group_id;
		$data_index_group_hits['ip']		= get_client_ip();
		if(!$database_index_group_hits->field('`group_id`')->where($data_index_group_hits)->find()){
			$condition_group['group_id'] = $group_id;
			if(M('Group')->where($condition_group)->setDec('index_sort')){
				$data_index_group_hits['time'] = $_SERVER['REQUEST_TIME'];
				$database_index_group_hits->data($data_index_group_hits)->add();
			}
		}
	}
	
	public function plat_wxapp(){
		$arr['appid'] = $this->config['pay_wxapp_appid'];
		$arr['paotui_appid'] = $this->config['pay_wxapp_paotui_appid'];
		
		$now_city_id = M('Config')->where(array('name'=>'now_city'))->find();
		$city_info = D('Area')->get_area_by_areaId($now_city_id['value']);
		$arr['city'] = $city_info['area_name'];
		
		$arr['wxappName'] = $this->config['site_name'];
		
		$arr['shopName'] = $this->config['shop_alias_name'];
		
		if(empty($this->config['baidu_map_ak'])){
			$arr['baidu_map'] = '未填写';
		}else{
			$url = 'http://api.map.baidu.com/geoconv/v1/?coords=116.397470,39.908823&from=3&to=5&ak=' . $this->config['baidu_map_ak'] . '&output=json';
			import('ORG.Net.Http');
			$http = new Http();
			$result = $http->curlGet($url);
			if ($result) {
				$result = json_decode($result, true);
				if ($result['status'] == 0) {
					$arr['baidu_map'] = 'ok';
				}else{
					$arr['baidu_map'] = $result['message'];
				}
			}else{
				$arr['baidu_map'] = '百度地图请求异常';
			}
		}

		// if (!empty($this->config['wechat_appid']) && !empty($this->config['wechat_appsecret'])) {
			// $im = new im();
			// $im->create();
		// }
		
		echo json_encode($arr);
	}
}