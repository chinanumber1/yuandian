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
		$hotel_adver = D('Adver')->get_adver_by_key('hotel_cat',5);
		$arr['hotel_adver']=$hotel_adver;
		$this->returnCode(0,$arr);
	}

	//酒店搜索
	public function hotel_search(){

		//$_COOKIE['search_hotel_history']= '七天~如家~长江饭店~老乡鸡~南七~中科大~大蜀山~万达茂~滨湖会展';
		//$search_hotel_history = $_POST['search_hotel_history'];
//		if($search_hotel_history){
//			$search_hotel_history = explode('~',trim($search_hotel_history,'~'));
//		}
		$hot_circle = D('Area')->get_area_list_hot(16);
		$circle_category = D('Circle_category')->field('id,name')->limit(16)->select();
		$arr['hot_circle']=$hot_circle;
		$arr['circle_category']=$circle_category;
		//$arr['search_hotel_history']=$search_hotel_history?array_reverse($search_hotel_history):'';
		$this->returnCode(0,$arr);
	}

	public  function ajax_search_hotel(){
		$query = $_POST['query'];
		if($query==''){
			$this->returnCode('20090012');
		}
		$cookies = explode('~',$_POST['search_hotel_history']);
		if(!in_array($query,$cookies)){
			cookie('search_hotel_history', $_POST['search_hotel_history'].'~'.$query);
		}

		//按酒店名字搜索
		$where['is_hotel'] = '1';
		$where['cat_status'] = '1';
		$hotel_category = D('Group_category')->field('cat_id')->where($where)->select();
		foreach ($hotel_category as $item) {
			$hotel_cate_fid[] = $item['cat_id'];
		}
//		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		$long_lat['lat'] = $_POST['lat'];
		$long_lat['long'] = $_POST['long'];
		$search_group_name_and_merchant_name = D('Group')->get_group_list_by_keywords($query,'default',true,$hotel_cate_fid,$long_lat['lat'],$long_lat['long']);
		$result['name']['hotel_count'] =  $search_group_name_and_merchant_name['group_count'];

		//按商圈名字搜索
		$area_result = D('Area')->get_circle_by_keyword($query);
		$result['area'] = $area_result?$area_result:array();
		$result['hotel_address'] =$search_group_name_and_merchant_name['group_list']?$search_group_name_and_merchant_name['group_list']:array();

		$this->returnCode(0,$result);
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
			$this->returnCode('50000002');
		}
		//$long_lat = D('User_long_lat')->getLocation('oa0OWwERofGeKzCzqjbn10g_3LTE');
		$city_name = M('Area')->where(array('area_id'=>$this->config['now_city']))->find();

	}

	//酒店列表
	public function hotel_list(){
		//判断地区信息
		$area_url = !empty($_POST['area_url']) ? $_POST['area_url'] : '';
		$arr['now_area_url']=$area_url;
		$cat_url = $_POST['cat_url'];
		$circle_id = 0;
		if(!empty($area_url)){
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){

				$this->returnCode('20045008');
			}
			$arr['now_area']= $tmp_area;

			if ($tmp_area['area_type'] == 3) {
				$now_area = $tmp_area;
			} else {
				$now_circle = $tmp_area;
				$arr['now_circle']=$now_circle;
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {

					$this->returnCode('20045008');
				}
				$circle_url = $now_circle['area_url'];
				$circle_id = $now_circle['area_id'];
				$area_url = $now_area['area_url'];
			}
			$arr['top_area']=$now_area;
			$area_id = $now_area['area_id'];
		}else{
			$area_id = 0;
		}

		//判断排序信息
		$sort_id = !empty($_POST['sort_id']) ? $_POST['sort_id'] : 'juli';
		if($this->config['open_group_default_sort']){
			$sort_id = 'defaults';
		}
		$long_lat['lat'] = $_POST['lat'];
		$long_lat['long'] = $_POST['long'];
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
			//$this->assign('long_lat',$long_lat);
		}
		foreach($sort_array as $key=>$value){
			if($sort_id == $value['sort_id']){
				$now_sort_array = $value;
				break;
			}
		}

		$arr['sort_array']=$sort_array;

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_hotel_category();
		foreach ($all_category_list as $vv) {
			$tmp_category_list = [];
			foreach ($vv['category_list'] as $vt) {
				$tmp_category_list[] = $vt;
			}
			$vv['category_list']  = $tmp_category_list;
			$tmp_all_category_list[] = $vv;
		}

		$arr['all_category_list']=$tmp_all_category_list;

		//根据分类信息获取分类
		if(!empty($cat_url)){
			$now_category = D('Group_category')->get_category_by_catUrl($cat_url);
			if(empty($now_category)){
				$this->returnCode('50000002');
			}
			$arr['now_category'] = $now_category;

			if(!empty($now_category['cat_fid'])){
				$f_category = D('Group_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];
				$category_cat_field = $f_category['cat_field'];

				$top_category = $f_category;
				$arr['top_category']=$f_category;

				$get_grouplist_catfid = 0;
				$get_grouplist_catid = $now_category['cat_id'];
			}else{
				$all_category_url = $now_category['cat_url'];
				$category_cat_field = $now_category['cat_field'];
				$top_category = $now_category;
				$arr['top_category']=$now_category;

				$get_grouplist_catfid = $now_category['cat_id'];
				$get_grouplist_catid = 0;
			}
		}
		$all_area_list = D('Area')->get_all_area_list();
		foreach ($all_area_list as $item) {
			$tmp_all_area_list[] = $item;
		}
		$arr['all_area_list'] = $tmp_all_area_list;
		$this->returnCode(0,$arr);
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
		$this->returnCode(0,array('hotel_list'=>$tmp));
	}



	public function ajaxList(){
		//$this->header_json();
		//判断分类信息

		$cat_url = !empty($_POST['cat_url']) ? $_POST['cat_url'] : '';

		//判断地区信息
		$area_url = !empty($_POST['area_url']) ? $_POST['area_url'] : '';
		$search_txt  = !empty($_POST['search_txt']) ? $_POST['search_txt'] : '';
		//if($search_txt){
		if($search_txt && $_POST['type']=='area'){

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
		}elseif($_POST['type']=='name'){
			$keyword = $search_txt;
		}

		//$circle_id = 0;
		if(!empty($area_url)){
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->returnCode('20045008');
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
		$sort_id = !empty($_POST['sort_id']) ? $_POST['sort_id'] : 'defaults';
		$long_lat['lat']= $_POST['lat'];
		$long_lat['long'] = $_POST['lng'];

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_all_category();

		//根据分类信息获取分类
		if(!empty($cat_url)){
			$now_category = D('Group_category')->get_category_by_catUrl($cat_url);
			if(empty($now_category)){
				$this->returnCode('50000002');
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
		$_GET['page']  =$_POST['page'];
		$returns = D('Group')->wap_get_hotel($keyword,$get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
		//$return = D('Group')->wap_get_hotel('','',$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
		$arr = array();
		foreach ($returns['group_list'] as $h) {
			$tmp['group_id']  = $h['group_id'];
			$tmp['mer_id']  = $h['mer_id'];
			$tmp['store_id']  = $h['store_id'];
			$tmp['hotel_cat_id']  = $h['trade_info'];
			$tmp['url']  = $this->config['site_url'].$h['url'].'&group_type=hotel&dep_date='.$_POST['dep_date'].'&end_date='.$_POST['dep_date'].'&dep_time='.$_POST['dep_time'].'&end_time='.$_POST['end_time'];
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
		$return['hotel_list'] = $arr;

		$return['hotel_count'] = $returns['group_count'];
		$return['totalPage'] = $returns['totalPage'];

		$this->returnCode(0,$return);

	}

	public function ajax_get_trade_hotel_stock(){
		$now_group = D('Group')->get_group_by_groupId($_POST['group_id']);

		$hotel_list_tmp = D('Trade_hotel_category')->get_all_list($now_group['mer_id'],true,$_POST['dep_time'],$_POST['end_time']);
		$hotel_cat_id = explode(',',$now_group['trade_info']);
		foreach ($hotel_cat_id as $vc) {
			$hotel_list[$vc] = $hotel_list_tmp[$vc];
		}

		$this->returnCode(0,array('hotel_list'=>$hotel_list));
	}
	public function ajax_get_trade_hotel_price(){
		$now_group = D('Group')->get_group_by_groupId($_POST['group_id']);
		$hotel_list = D('Trade_hotel_category')->get_cat_price($now_group['mer_id'],$_POST['cat_id'],$_POST['dep_time'],$_POST['end_time']);
		$this->returnCode(0,array('hotel_list'=>$hotel_list));
	}




}

?>