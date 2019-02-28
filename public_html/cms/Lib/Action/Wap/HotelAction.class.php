<?php
class HotelAction extends BaseAction{
	public function _initialize()
	{
		parent::_initialize();
		if(!$this->config['many_city']){
			$now_city = D('Area')->where(array('area_id'=>$this->config['now_city']))->find();
			$this->config['now_select_city'] ['area_id']  = $this->config['now_city'];
			$this->config['now_select_city'] ['area_name']  = $now_city['area_name'];
			$this->config['now_select_city'] ['area_url']  =$now_city['area_url'];
			C('config.now_select_city',$this->config['now_select_city']);
			$this->assign('config',$this->config);
		}
	}

	public function index(){


		//判断分类信息
		$hotel_adver = D('Adver')->get_adver_by_key('hotel_cat',5);
		//dump($wap_index_top_adver);
		$this->assign('hotel_adver',$hotel_adver);
		/* 粉丝行为分析 */
		$this->behavior(array('model'=>'Group_index'));
		//dump($this->config['now_select_city']);die;
		$this->display();
	}

	//酒店搜索
	public function hotel_search(){

		//$_COOKIE['search_hotel_history']= '七天~如家~长江饭店~老乡鸡~南七~中科大~大蜀山~万达茂~滨湖会展';
		$search_hotel_history = $_COOKIE['search_hotel_history'];
		if($search_hotel_history){
			$search_hotel_history = explode('~',trim($search_hotel_history,'~'));
		}
		$hot_circle = D('Area')->get_area_list_hot(16);
		$circle_category = D('Circle_category')->limit(16)->select();
		$this->assign('hot_circle',$hot_circle);
		$this->assign('circle_category',$circle_category);
		$this->assign('search_hotel_history',array_reverse($search_hotel_history));
		$this->display();
	}

	public  function ajax_search_hotel(){
		$query = $_GET['query'];
		if($query==''){
			$this->error('请输入搜索内容');
		}
		$cookies = explode('~',$_COOKIE['search_hotel_history']);
		if(!in_array($query,$cookies)){
			cookie('search_hotel_history', $_COOKIE['search_hotel_history'].'~'.$query);
		}

		//按酒店名字搜索
		$where['is_hotel'] = '1';
		$where['cat_status'] = '1';
		$hotel_category = D('Group_category')->field('cat_id')->where($where)->select();
		foreach ($hotel_category as $item) {
			$hotel_cate_fid[] = $item['cat_id'];
		}
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		$search_group_name_and_merchant_name = D('Group')->get_group_list_by_keywords($query,'default',true,$hotel_cate_fid,$long_lat['lat'],$long_lat['long']);
		$result['name']['hotel_count'] =  $search_group_name_and_merchant_name['group_count'];

		//按商圈名字搜索
		$result['area'] = D('Area')->get_circle_by_keyword($query);
		$result['hotel_address'] =$search_group_name_and_merchant_name['group_list'];

		$this->success($result);
		//按酒店名字搜索
	}

	//清空搜索历史
	public function del_search_history(){
		cookie('search_hotel_history',null);
		$this->success('ok');
	}

	public function hotel_around(){
		$now_category = D('Group_category')->get_category_by_catUrl('jiudian');
		if(empty($now_category)){
			$this->error_tips('此分类不存在！');
		}
		//$long_lat = D('User_long_lat')->getLocation('oa0OWwERofGeKzCzqjbn10g_3LTE');
		$city_name = M('Area')->where(array('area_id'=>$this->config['now_city']))->find();
		$this->assign('city_name',$city_name['area_name']);
		$this->display();
	}

	//酒店列表
	public function hotel_list(){
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
		if($this->config['open_group_default_sort']){
			$sort_id = 'defaults';
		}
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		if(empty($long_lat)){
			$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
			$sort_array = array(
					array('sort_id'=>'defaults','sort_value'=>'智能排序'),

					array('sort_id'=>'rating','sort_value'=>'好评优先'),


					array('sort_id'=>'price','sort_value'=>'低价优先'),
					array('sort_id'=>'priceDesc','sort_value'=>'高价优先'),
			);
		} else {
			$sort_array = array(
					array('sort_id'=>'defaults','sort_value'=>'智能排序'),

					array('sort_id'=>'rating','sort_value'=>'好评优先'),


					array('sort_id'=>'price','sort_value'=>'低价优先'),
					array('sort_id'=>'priceDesc','sort_value'=>'高价优先'),
			);
			$this->assign('long_lat',$long_lat);
		}
		foreach($sort_array as $key=>$value){
			if($sort_id == $value['sort_id']){
				$now_sort_array = $value;
				break;
			}
		}

		$this->assign('sort_array',$sort_array);
		$this->assign('now_sort_array',$now_sort_array);


		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_hotel_category();

		$this->assign('all_category_list',$all_category_list);

		//根据分类信息获取分类
		if(!empty($cat_url)){
			$now_category = D('Group_category')->get_category_by_catUrl($cat_url);
			if(empty($now_category)){
				$this->error_tips('此分类不存在！');
			}
			$this->assign('now_category',$now_category);

			if(!empty($now_category['cat_fid'])){
				$f_category = D('Group_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];
				$category_cat_field = $f_category['cat_field'];

				$top_category = $f_category;
				$this->assign('top_category',$f_category);

				$get_grouplist_catfid = 0;
				$get_grouplist_catid = $now_category['cat_id'];
			}else{
				$all_category_url = $now_category['cat_url'];
				$category_cat_field = $now_category['cat_field'];
				$top_category = $now_category;
				$this->assign('top_category',$now_category);

				$get_grouplist_catfid = $now_category['cat_id'];
				$get_grouplist_catid = 0;
			}
		}
		$all_area_list = D('Area')->get_all_area_list();
		$this->assign('all_area_list',$all_area_list);
		$this->display();
	}


	public function ajax_hotel_around(){
		//$now_category = D('Group_category')->get_category_by_catUrl('jiudian');
		$long_lat['lat']= $_POST['lat'];
		$long_lat['long'] = $_POST['lng'];
		$sort_id = 'juli';


		$where['is_hotel'] = '1';
		$where['cat_status'] = '1';
		$hotel_category = M('Group_category')->field('cat_id')->where($where)->select();
		foreach ($hotel_category as $item) {
			$hotel_cate_fid[] = $item['cat_id'];
		}
		$get_grouplist_catfid = $hotel_cate_fid;
		$get_grouplist_catid = 0;
		$return = D('Group')->wap_get_hotel('',$get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long']);

//		$group_list = D('Group')->wap_get_group_list_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id,3000);
		$tmp =array();
		foreach ($return['group_list'] as $v) {
			$arr['group_id'] = $v['group_id'];
			$arr['long'] = $v['long'];
			$arr['lat'] = $v['lat'];
			$arr['group_name'] = $v['group_name'];
			$arr['reply_count'] = $v['reply_count'];
			$arr['score_mean'] = $v['score_mean']==0?5:$v['score_mean'];
			$arr['score_all'] = $v['score_all'];
			$arr['sname'] = $v['s_name'];
			$arr['sphone'] = $v['phone'];
			$arr['adress'] = $v['adress'];
			$arr['price'] = $v['price'];
			$arr['juli'] = $v['juli'];
			$arr['url'] = $v['url'];
			$arr['list_pic'] = $v['list_pic'];
			$arr['juli'] = 	$this->wapFriendRange($v['juli']);
			$hotel = M('Trade_hotel_category')->where(array('cat_fid'=>$v['trade_info']))->select();
			$arr['is_refund']  = 0;
			$arr['discount']  = 0;
			$arr['circle_name']  = $v['circle_name'];

			foreach ($hotel as $vv) {
				if($vv['has_refund']!=1){
					$arr['is_refund']  = 1;
				}
				if($vv['discount_room']>0){
					$arr['discount']  = 1;
				}
				if($arr['is_refund'] && $arr['discount']){
					break;
				}
			}
			$tmp[] = $arr;
		}
		echo json_encode($tmp);
	}



	public function ajaxList(){
		$this->header_json();
		//判断分类信息

		$cat_url = !empty($_GET['cat_url']) ? $_GET['cat_url'] : '';

		//判断地区信息
		$area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';
		$search_txt  = !empty($_GET['search_txt']) ? $_GET['search_txt'] : '';
		//if($search_txt){
		if($search_txt && $_GET['type']=='area'){

			if(	$hot_circle = M('Area')->where(array('area_name'=>array('like','%'.$search_txt.'%')))->find()){
				$circle_id  = $hot_circle['area_id'];
			}

			if($circle_cat = M('Circle_category')->where(array('name'=>array('like','%'.$search_txt.'%')))->find()){
				$circle = M('Area')->where(array('circle_category_id'=>$circle_cat['id'],'is_open'=>1))->select();
				foreach ( $circle as $vc) {
					$circle_id[]=$vc['area_id'];
				}
			}
			$keyword = '';
		}elseif($_GET['type']=='name'){
			$keyword = $search_txt;
		}

		//$circle_id = 0;
		if(!empty($area_url)){
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->error('当前区域不存在！');
			}

			if ($tmp_area['area_type'] == 3||$tmp_area['area_type'] == 2) {
				$now_area = $tmp_area;
			} else if($tmp_area['area_type'] == 4){
				$now_circle = $tmp_area;
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {
					$this->returnCode('20045008');
				}
				$circle_url = $now_circle['area_url'];
				$circle_id = $now_circle['area_id'];
				$area_url = $now_area['area_url'];
			}
			$area_id = $now_area['area_id'];
		}else{
			$area_id = 0;
		}

		//判断排序信息
		$sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'defaults';
		if(!$_GET['long']){
			$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		}
		if(empty($long_lat)){
			$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
			$sort_array = array(
				array('sort_id'=>'defaults','sort_value'=>'智能排序'),
				array('sort_id'=>'rating','sort_value'=>'评价最高'),
				array('sort_id'=>'start','sort_value'=>'最新发布'),
				array('sort_id'=>'solds','sort_value'=>'人气最高'),
				array('sort_id'=>'price','sort_value'=>'价格最低'),
				array('sort_id'=>'priceDesc','sort_value'=>'价格最高'),
			);
		}else{
			$sort_array = array(
				array('sort_id'=>'defaults','sort_value'=>'智能排序'),
				array('sort_id'=>'juli','sort_value'=>'离我最近'),
				array('sort_id'=>'rating','sort_value'=>'评价最高'),
				array('sort_id'=>'start','sort_value'=>'最新发布'),
				array('sort_id'=>'solds','sort_value'=>'人气最高'),
				array('sort_id'=>'price','sort_value'=>'价格最低'),
				array('sort_id'=>'priceDesc','sort_value'=>'价格最高'),
			);
		}
		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_all_category();

		//根据分类信息获取分类
		if(!empty($cat_url)){
			$now_category = D('Group_category')->get_category_by_catUrl($cat_url);
			if(empty($now_category)){
				$this->error_tips('此分类不存在！');
			}

			if(!empty($now_category['cat_fid'])){
				$f_category = D('Group_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];
				$category_cat_field = $f_category['cat_field'];
				$top_category = $f_category;
				$get_grouplist_catfid = 0;
				$get_grouplist_catid = $now_category['cat_id'];
			}else{
				$all_category_url = $now_category['cat_url'];
				$category_cat_field = $now_category['cat_field'];
				$top_category = $now_category;
				$get_grouplist_catfid = $now_category['cat_id'];
				$get_grouplist_catid = 0;
			}
		}else{
			$where['is_hotel'] = '1';
			$where['cat_status'] = '1';
			$hotel_category = M('Group_category')->field('cat_id')->where($where)->select();
			foreach ($hotel_category as $item) {
				$hotel_cate_fid[] = $item['cat_id'];
			}
			$get_grouplist_catfid = $hotel_cate_fid;
			$get_grouplist_catid = 0;
		}

		$return = D('Group')->wap_get_hotel($keyword,$get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
		//$return = D('Group')->wap_get_hotel('','',$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
		$arr = array();
		foreach ($return['group_list'] as $h) {
			$tmp['group_id']  = $h['group_id'];
			$tmp['mer_id']  = $h['mer_id'];
			$tmp['store_id']  = $h['store_id'];
			$tmp['hotel_cat_id']  = $h['trade_info'];
			$tmp['url']  = $h['url'].'&group_type=hotel&dep_date='.$_COOKIE['dep_date'].'&end_date='.$_COOKIE['dep_date'].'&dep_time='.$_COOKIE['dep_time'].'&end_time='.$_COOKIE['end_time'];
			$tmp['list_pic']  = $h['list_pic'];
			$tmp['group_name']  = $h['group_name'];
			$tmp['s_name']  = $h['s_name'];
			$tmp['juli_txt']  = $h['juli_txt'];
			$tmp['score_mean'] = $h['score_mean']==0?5:$h['score_mean'];

			$tmp['reply_count']  = $h['reply_count'];
			$tmp['price']  = $h['price'];
			$tmp['circle_name']  = $h['circle_name'];
			$hotel = M('Trade_hotel_category')->where(array('cat_fid'=>$h['trade_info']))->select();
			$tmp['is_refund'] = 0 ;
			$tmp['discount'] = 0 ;
			foreach ($hotel as $vv) {
				if($vv['has_refund']!=1){
					$tmp['is_refund']  = 1;
				}
				if($vv['discount_room']>0){
					$tmp['discount']  = 1;
				}
				if($tmp['is_refund'] && $tmp['discount']){
					break;
				}
			}
			$arr[] = $tmp;
		}
		$return['group_list'] = $arr;
		$return['style'] = 'group';

		echo json_encode($return);

	}



	public function map(){
		$now_group = D('Group')->get_group_by_groupId($_GET['group_id']);

		if(empty($now_group)){
			$this->error_tips('当前'.$this->config['group_alias_name'].'不存在！');
		}

		//判断是否微信浏览器，
		if($_SESSION['openid']){
			$long_lat = D('User_long_lat')->field('long,lat')->where(array('open_id' => $_SESSION['openid']))->find();
			if($long_lat){
				import('@.ORG.longlat');
				$longlat_class = new longlat();
				$location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
				foreach($now_group['store_list'] as &$storeValue){
					$storeValue['range'] = getRange(getDistance($location2['lat'],$location2['lng'],$storeValue['lat'],$storeValue['long']),false);
				}
			}
		}

		$this->assign('now_group',$now_group);

		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($now_group['mer_id'],array('group_hits'=>1));

		/* 粉丝行为分析 */
		$this->behavior(array('mer_id'=>$now_group['mer_id'],'biz_id'=>$now_group['group_id']));

		$this->display();
	}

	public function ajax_get_trade_hotel_stock(){
		$now_group = D('Group')->get_group_by_groupId($_POST['group_id']);

		$hotel_list_tmp = D('Trade_hotel_category')->get_all_list($now_group['mer_id'],true,$_POST['dep_time'],$_POST['end_time']);
		$hotel_cat_id = explode(',',$now_group['trade_info']);
		foreach ($hotel_cat_id as $vc) {
			$hotel_list[$vc] = $hotel_list_tmp[$vc];
		}

		echo json_encode($hotel_list);exit;
	}
	public function ajax_get_trade_hotel_price(){
		$now_group = D('Group')->get_group_by_groupId($_POST['group_id']);
		$hotel_list = D('Trade_hotel_category')->get_cat_price($now_group['mer_id'],$_POST['cat_id'],$_POST['dep_time'],$_POST['end_time']);
		echo json_encode($hotel_list);exit;
	}
	public function shop(){
		$now_store = D('Merchant_store')->get_store_by_storeId($_GET['store_id']);
		if(empty($now_store)){
			$this->error_tips('该店铺不存在！');
		}
		//得到当前店铺的评分
		$store_score = D('Merchant_score')->field('`score_all`,`reply_count`')->where(array('parent_id'=>$now_store['store_id'],'type'=>'2'))->find();
		$this->assign('store_score',$store_score);

		if(!empty($this->user_session)){
			$database_user_collect = D('User_collect');
			$condition_user_collect['type'] = 'group_shop';
			$condition_user_collect['id'] = $now_store['store_id'];
			$condition_user_collect['uid'] = $this->user_session['uid'];
			if($database_user_collect->where($condition_user_collect)->find()){
				$now_store['is_collect'] = true;
			}
		}

		$this->assign('now_store',$now_store);

		$store_group_list = D('Group')->get_store_group_list($now_store['store_id'],0,true);
		$this->assign('store_group_list',$store_group_list);

		//为粉丝推荐
		$index_sort_group_list = D('Group')->get_group_list('index_sort',10,true);
		//判断是否微信浏览器，
		if($_SESSION['openid'] && $index_sort_group_list){
			$long_lat = D('User_long_lat')->field('long,lat')->where(array('open_id' => $_SESSION['openid']))->find();
			if($long_lat){
				import('@.ORG.longlat');
				$longlat_class = new longlat();
				$location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
				$group_store_database = D('Group_store');
				foreach($index_sort_group_list as &$storeGroupValue){
					$tmpStoreList = $group_store_database->get_storelist_by_groupId($storeGroupValue['group_id']);
					if($tmpStoreList){
						foreach($tmpStoreList as &$tmpStore){
							$tmpStore['Srange'] = getDistance($location2['lat'],$location2['lng'],$tmpStore['lat'],$tmpStore['long']);
							$tmpStore['range'] = getRange($tmpStore['Srange'],false);
							$rangeSort[] = $tmpStore['Srange'];
						}
						array_multisort($rangeSort, SORT_ASC, $tmpStoreList);
						$storeGroupValue['store_list'] = $tmpStoreList;
						$storeGroupValue['range'] = $tmpStoreList[0]['range'];
					}
				}
			}
		}
		$this->assign('index_sort_group_list',$index_sort_group_list);


		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($now_store['mer_id'],array('group_hits'=>1));

		/* 粉丝行为分析 */
		$this->behavior(array('mer_id'=>$now_store['mer_id'],'store_id'=>$now_store['store_id']));

		if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $now_store['mer_id']))->select()) {
			$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
			$kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_store['mer_id'];
			$this->assign('kf_url', $kf_url);
		}

		$this->display();
	}

	public function get_route(){
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		$this->assign('long_lat',$long_lat);

		$now_store = D('Merchant_store')->get_store_by_storeId($_GET['store_id']);
		if(empty($now_store)){
			$this->error_tips('该店铺不存在！');
		}
		$this->assign('now_store',$now_store);

		$this->assign('no_gotop',true);

		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($now_store['mer_id'],array('group_hits'=>1));

		/* 粉丝行为分析 */
		$this->behavior(array('mer_id'=>$now_store['mer_id'],'store_id'=>$now_store['store_id']));

		$this->display();
	}

	public function around(){

		$long_lat['lat'] = $_COOKIE['around_lat'];
		$long_lat['long'] = $_COOKIE['around_long'];

		if(empty($long_lat['lat']) || empty($long_lat['long'])){
			$_SESSION['openid'] && $long_lat = D('User_long_lat')->field('long,lat')->where(array('open_id' => $_SESSION['openid']))->find();
		}
// 		$x = $_COOKIE['around_lat'];
// 		$y = $_COOKIE['around_long'];
// 		$adress = $_COOKIE['around_adress'];

		if(empty($long_lat['lat']) || empty($long_lat['long'])){
			redirect(U('Group/around_adress'));
		}

// 		if ($_SESSION['openid'] && ($long_lat = D('User_long_lat')->field('long,lat')->where(array('open_id' => $_SESSION['openid']))->find())) {
// 			$long_lat['lat'] = 31.841217;
// 			$long_lat['long'] = 117.207008;
			$this->assign('lat_long', $long_lat['lat'] . ',' . $long_lat['long']);
			$around_range = $this->config['group_around_range'];
			$stores = D("Merchant_store")->field("`store_id`,ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$long_lat['lat']}*PI()/180-`lat`*PI()/180)/2),2)+COS({$long_lat['lat']}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$long_lat['long']}*PI()/180-`long`*PI()/180)/2),2)))*1000) AS juli")->where("`have_group`='1' AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$long_lat['lat']}*PI()/180-`lat`*PI()/180)/2),2)+COS({$long_lat['lat']}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$long_lat['long']}*PI()/180-`long`*PI()/180)/2),2)))*1000) < '$around_range'")->select();
			$store_ids = array();
			foreach ($stores as $store){
				$store_ids[] = $store['store_id'];
				$store_around_range[$store['store_id']] = $store['juli'];
			}
			$groupids = array();
			if ($store_ids) {
				$gslist = D('Group_store')->field('`group_id`,`store_id`')->where(array('store_id' => array('in', $store_ids)))->select();
				foreach ($gslist as $gs){
					$groupids[] = $gs['group_id'];
					$group_around_range[$gs['group_id']] = $store_around_range[$gs['store_id']];
				}
			}
			$this->assign('group_around_range',$group_around_range);
// 			$this->assign('adress', $adress);

			//得到附近的团购列表
			$groupids && $group_return = D('Group')->get_group_list_by_group_ids($groupids,'', true);
			$this->assign($group_return);
// 		}

		$this->display();
	}
	public function around_adress(){
		if(!empty($_GET['long']) && !empty($_GET['lat'])){
			$map_center = 'new BMap.Point('.$_GET['long'].','.$_GET['lat'].')';
		}else{
			import('ORG.Net.IpLocation');
			$IpLocation = new IpLocation();
			$last_location = $IpLocation->getlocation();
			$map_center = '"'.iconv('GBK','UTF-8',$last_location['country']).'"';
		}
		$this->assign('map_center',$map_center);
		$this->display();
	}
	public function navigation(){

		//导购热门广告
		$wap_center_adver = D('Adver')->get_adver_by_key('wap_group_navigation',4);
		$this->assign('wap_center_adver',$wap_center_adver);
		// dump($wap_center_adver);
		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_all_category();
		$this->assign('all_category_list',$all_category_list);
		$this->assign('no_gotop',true);
		// dump($all_category_list);
		$this->display();
	}
}

?>