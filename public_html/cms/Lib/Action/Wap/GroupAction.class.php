<?php
class GroupAction extends BaseAction{
	public function index(){

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
		if($this->config['open_group_default_sort']){
			$sort_id = 'defaults';
		}
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		if(empty($long_lat) ){
			$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
			$sort_array = array(
				array('sort_id'=>'defaults','sort_value'=>'智能排序'),
				array('sort_id'=>'juli','sort_value'=>'离我最近'),
				array('sort_id'=>'rating','sort_value'=>'评价最高'),
				array('sort_id'=>'start','sort_value'=>'最新发布'),
				array('sort_id'=>'solds','sort_value'=>'人气最高'),
				array('sort_id'=>'price','sort_value'=>'价格最低'),
				array('sort_id'=>'priceDesc','sort_value'=>'价格最高'),
			);
		} else {
			$sort_array = array(
				array('sort_id'=>'defaults','sort_value'=>'智能排序'),
				array('sort_id'=>'juli','sort_value'=>'离我最近'),
				array('sort_id'=>'rating','sort_value'=>'评价最高'),
				array('sort_id'=>'start','sort_value'=>'最新发布'),
				array('sort_id'=>'solds','sort_value'=>'人气最高'),
				array('sort_id'=>'price','sort_value'=>'价格最低'),
				array('sort_id'=>'priceDesc','sort_value'=>'价格最高'),
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
		$all_category_list = D('Group_category')->get_all_category();
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

// 			if(!empty($category_cat_field)){
// 				$cat_field = unserialize($category_cat_field);
// 				foreach($cat_field as $key=>$value){
					//包含区域
// 					if($value['use_field'] && $value['use_field'] == 'area'){
// 						$all_area_list = D('Area')->get_area_list();
// 						$this->assign('all_area_list',$all_area_list);
// 					}
// 				}
// 			}
		}else{
			//所有区域
// 			$all_area_list = D('Area')->get_all_area_list();
// 			$this->assign('all_area_list',$all_area_list);
		}
		$all_area_list = D('Area')->get_all_area_list();
		$this->assign('all_area_list',$all_area_list);
		//$long_lat['lat'] = 31.823263;
		//$long_lat['long'] = 117.235268;
		$this->assign(D('Group')->wap_get_group_list_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id));

		/* 粉丝行为分析 */
		$this->behavior(array('model'=>'Group_index'));
		if( $this->user_session['uid'] && $this->config['open_rand_send']){
			$coupon_html = D('System_coupon')->rand_send_coupon_get(array('time'=>$_SERVER['REQUEST_TIME'],'uid'=>$this->user_session['uid']));
			$coupon_html && $this->assign('coupon_html',$coupon_html);
		}

		$this->display();
	}

	//团购首页
	public function main_page(){
		$show_ad = $this->config['group_main_page_show_ad'];
		$center_type = $this->config['group_main_page_center_type'];
		//$show_type = $this->config['group_main_page_show_type'];

		$group_adver = D('Adver')->get_adver_by_key('group_index',5);
		$this->assign('group_adver',$group_adver);
		if($show_ad){
			$where['type'] =$center_type;
			$slider_list = M('Group_main_page_pic_slider')->where($where)->order('sort DESC')->select();
			$this->assign('slider_list',$slider_list);
		}

		$tmp_wap_index_slider =D('Slider')->get_slider_by_key('wap_group_slider', 0);

		$wap_index_slider = array();
		foreach ($tmp_wap_index_slider as $key => $value) {
			$tmp_i = floor($key / 8);
			$wap_index_slider[$tmp_i][] = $value;
		}


		$this->assign('wap_group_slider',$wap_index_slider);

		$this->display();
	}


	public function ajax_hotel_around(){
		$now_category = D('Group_category')->get_category_by_catUrl('jiudian');

		$long_lat['lat']= $_POST['lat'];
		$long_lat['long'] = $_POST['lng'];
		$sort_id = 'juli';
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
		$group_list = D('Group')->wap_get_group_list_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id,2000);
		$tmp =array();
		foreach ($group_list['group_list'] as $v) {
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

		$circle_id = 0;
		if(!empty($area_url)){
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->error('当前区域不存在！');
			}

			if ($tmp_area['area_type'] == 3) {
				$now_area = $tmp_area;
			} else {
				$now_circle = $tmp_area;
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {
					$this->error('当前区域不存在！');
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

		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
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

		}
		if($sort_id == 'juli'){
			$return = D('Group')->wap_get_storeList_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
			foreach($return['store_list'] as &$storeValue){
				$storeValue['range_txt'] = $this->wapFriendRange($storeValue['juli']);
				$group_list = S('wap_store_group_'.($get_grouplist_catfid ? $get_grouplist_catfid : $get_grouplist_catid).'_'.$storeValue['store_id']);
				if(empty($group_list)){
					$group_list = D('Group')->get_single_store_group_list($storeValue['store_id'],0,true);
					
					foreach($group_list as $key=>$value){
						if(($get_grouplist_catid && $value['cat_id'] != $get_grouplist_catid) || ($get_grouplist_catfid && $value['cat_fid'] != $get_grouplist_catfid)){
							unset($group_list[$key]);
						}
					}
					S('wap_store_group_'.($get_grouplist_catfid ? $get_grouplist_catfid : $get_grouplist_catid).'_'.$storeValue['store_id'],$group_list,360);
				}else{
					foreach($group_list as &$groupValue){
						if($groupValue['end_time'] < $_SERVER['REQUEST_TIME']){
							unset($groupValue);
						}
					}
				}
				$storeValue['group_list'] = array_values($group_list);
				$storeValue['group_count'] = count($group_list);
				if(empty($storeValue['group_count'])){
					unset($storeValue);
				}
			}
			$return['style'] = 'store';
		}else{
			$return = D('Group')->wap_get_group_list_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
			$return['style'] = 'group';
		}
		echo json_encode($return);
		/* 粉丝行为分析 */
		$this->behavior(array('model'=>'Group_index'));
	}
	public function detail(){
		$now_group = D('Group')->get_group_by_groupId($_GET['group_id'],'hits-setInc');
		if(empty($now_group)){
			$this->error_tips('当前'.$this->config['group_alias_name'].'不存在！');
		}
		if($now_group['end_time']<$_SERVER['REQUEST_TIME']){
			$this->error_tips('当前'.$this->config['group_alias_name'].'已结束！');
		}
		if($now_group['cue']){
			$now_group['cue_arr'] = unserialize($now_group['cue']);
		}
		if(!empty($now_group['pic_info'])){
			$merchant_image_class = new merchant_image();
			$now_group['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_group['pic_info']);
		}
		//判断是否微信浏览器，
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);

		if($long_lat){
			$rangeSort = array();
			foreach($now_group['store_list'] as &$storeValue){
				$storeValue['Srange'] = getDistance($long_lat['lat'],$long_lat['long'],$storeValue['lat'],$storeValue['long']);
				$storeValue['range'] = getRange($storeValue['Srange'],false);
				$rangeSort[] = $storeValue['Srange'];
			}
			array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
			$this->assign('long_lat',$long_lat);
		}

		if($now_group['packageid']>0&&$now_group['pin_num']==0){

			$packages=M('Group_packages')->where(array('id' => $now_group['packageid'], 'mer_id' => $now_group['mer_id']))->find();
			if(!empty($packages['groupidtext'])){
				$mpackages = unserialize($packages['groupidtext']);
				$packagesgroupid = $this->check_group_status(array_keys($mpackages));
				if(is_array($packagesgroupid)){
					foreach($packagesgroupid as $gvv){
						$tmp_mpackages[$gvv['group_id']]=$mpackages[$gvv['group_id']];
					}
					$mpackages=$tmp_mpackages;
					unset($tmp_mpackages);
				}
			}else{
				$mpackages = false;
			}
			$this->assign('mpackages',$mpackages);
		}
		$is_collect = false;
		if(!empty($this->user_session)){
			$database_user_collect = D('User_collect');
			$condition_user_collect['type'] = 'group_detail';
			$condition_user_collect['id'] = $now_group['group_id'];
			$condition_user_collect['uid'] = $this->user_session['uid'];
			if($database_user_collect->where($condition_user_collect)->find()){
				$now_group['is_collect'] = true;
			}

			//判断积分抵现
			$user_coupon_use = D('User')->check_score_can_use($this->user_session['uid'],$now_group['price'],'group',$now_group['group_id'],$now_group['mer_id']);
			$this->assign('user_coupon_use',$user_coupon_use);

			$data_user_collect['type'] ='group_detail';
			$data_user_collect['id'] = $now_group['group_id'];
			$data_user_collect['uid'] = $this->user_session['uid'];
			if($database_user_collect->field('collect_id')->where($data_user_collect)->find()){
				$is_collect = true;
			}
		}
		$this->assign('is_collect',$is_collect);
		$now_group['wx_cheap']  =   floatval($now_group['wx_cheap']);

		//拼团团购详情页面 参团价格分割
		if($now_group['pin_num']>0) {
			$now_group['old_price_e'] = explode('.', sprintf('%.2f', $now_group['old_price']));
			$now_group['price_e'] = explode('.', sprintf('%.2f', $now_group['price']));
			$group_start = D('Group_start');
//			$start_num = $group_start->where(array('group_id' => $now_group['group_id'], 'status' => 0))->count();

			$now_start = $group_start->where(array('id'=>$_GET['gid']))->find();
			if (!$now_start['status']&&isset($_GET['gid']) && !empty($_GET['gid'])) {
				$in_group = false;
				$start_user_arr = $group_start->get_buyerer_by_order_id('',$_GET['gid']);

				foreach($start_user_arr as $st){
					if($st['uid']==$_SESSION['user']['uid']){
						$in_group = true;
					}
				}
				if(!$in_group){
					$group_share_info = $group_start->get_group_start_user_by_gid($_GET['gid']);
					$end_time = $group_share_info['start_time'] + $now_group['pin_effective_time'] * 3600;
					$effective_time = $end_time - $_SERVER['REQUEST_TIME'];
					if ($effective_time > 0) {
						$group_share_info['end_time'] = $end_time;
						$this->assign('group_share_info', $group_share_info);
					} else {
						$group_start->update_start_group($_GET['gid'], 2); //2 团购小组超时
					}
				}
			}else{

				$can_join = D('Group_start')->check_join_pin($now_group['group_id'],$this->user_session['uid'],$now_group['pin_effective_time']);

				$this->assign('can_join',$can_join);
			}


		}
		foreach( $now_group['all_pic'] as $v_img){
			$now_group['img_arr'][] = $v_img['m_image'];
		}
		
		$src	=	'<img src="'.C('config.site_url').'/';
		$now_group['content'] = str_replace('<img src="/',$src,$now_group['content']);
		$now_group['intro'] = str_replace(array("\r\n", "\r", "\n"), "", $now_group['intro']);
		$this->assign('now_group',$now_group);

		//组团购
		if(!empty($_GET['fid'])&&$now_group['group_share_num']>0){
			$_SESSION['fid']=$_GET['fid'];
		}else{
			unset($_SESSION['fid']);
		}
		//新拼团
		unset($_SESSION['gid']);
		if(!empty($_GET['gid'])){
			$this_share_group = M('Group_start')->where(array('id'=>$_GET['gid']))->find();
			if(!$now_start['status']&&$now_group['group_id']==$this_share_group['group_id']){
				$_SESSION['gid']=$_GET['gid'];
			}
		}

		$time = $now_group['begin_time'] - $_SERVER['REQUEST_TIME'];
		$time_array['d'] = floor($time/86400);
		$time_array['h'] = floor($time%86400/3600);
		$time_array['m'] = floor($time%86400%3600/60);
		$time_array['s'] = floor($time%86400%60);
		$this->assign('time_array',$time_array);

		if($now_group['reply_count']){
			$reply_list = D('Reply')->get_reply_list($now_group['group_id'],0,count($now_group['store_list']),3);
			$this->assign('reply_list',$reply_list);
		}


		$merchant_group_list = D('Group')->get_grouplist_by_MerchantId($now_group['mer_id'],3,true,$now_group['group_id']);
		$this->assign('merchant_group_list',$merchant_group_list);

		//分类下其他团购
		$category_group_list = D('Group')->get_grouplist_by_catId($now_group['cat_id'],$now_group['cat_fid'],3,true);
		foreach($category_group_list as $key=>$value){
			if($value['group_id'] == $now_group['group_id']){
				unset($category_group_list[$key]);
			}
		}
		$this->assign('category_group_list',$category_group_list);

		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($now_group['mer_id'],array('group_hits'=>1));

		/* 粉丝行为分析 */
		$this->behavior(array('mer_id'=>$now_group['mer_id'],'biz_id'=>$now_group['group_id'],'keyword'=>strval($_GET['keywords'])));

		if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $now_group['mer_id']))->select()) {
			$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
			$kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_group['mer_id'];
			$this->assign('kf_url', $kf_url);
		}

		if($now_group['trade_type'] == 'hotel'){
			if($_COOKIE['dep_date'] && strtotime(date('Y-m-d'))<strtotime($_COOKIE['end_date'])){
				$trade_hotel['time_dep_time'] = $_COOKIE['dep_date'];
				$trade_hotel['time_end_time'] = $_COOKIE['end_date'];
				$trade_hotel['show_dep_time'] = substr($_COOKIE['dep_date'],5);
				$trade_hotel['show_end_time'] =  substr($_COOKIE['end_date'],5);
				$trade_hotel['show_end_time'] =  substr($_COOKIE['end_date'],5);
				$trade_hotel['dep_time'] =  $_COOKIE['dep_time'];
				$trade_hotel['end_time'] =  $_COOKIE['end_time'];
				$trade_hotel['days'] =  ($trade_hotel['end_time']- $trade_hotel['dep_time']);
			}else{
				$trade_hotel['time_dep_time'] = date('Y-m-d');
				$trade_hotel['show_dep_time'] = date('m-d');
				$trade_hotel['dep_time'] = date('Ymd');
				$trade_hotel['time_end_time'] = date('Y-m-d',time()+86400);
				$trade_hotel['show_end_time'] = date('m-d',time()+86400);
				$trade_hotel['end_time'] = date('Ymd',time()+86400);
				$trade_hotel['days'] =  ($trade_hotel['end_time']- $trade_hotel['dep_time']);
			}
			$hotel_list_tmp = D('Trade_hotel_category')->get_all_list($now_group['mer_id'],true,$trade_hotel['dep_time'],$trade_hotel['end_time']);

			$hotel_cat_id = explode(',',$now_group['trade_info']);
//			foreach ($hotel_cat_id as $vc) {
//				$hotel_list[$vc] = $hotel_list_tmp[$vc];
//			}
			foreach($hotel_list_tmp as $key=>$v){
				if(in_array($key,$hotel_cat_id)){
					$hotel_list[] = $v;
				}
			}
			$this->assign('trade_hotel',$trade_hotel);
			$this->assign('hotel_list',$hotel_list);
			$finalprice = round($now_group['price'],2);
			$this->assign('finalprice',$finalprice);
//			$this->display('buy_trade_hotel');die;
		}
		
		if($this->config['coupon_spread_open']){
			$merchant_card_spread_info = D('Card_new')->get_card_by_mer_id_and_uid($now_group['mer_id'],$this->user_session['uid']);
			$this->assign('merchant_card_spread_info',$merchant_card_spread_info);
		}
		
		if($now_group['trade_type']=='hotel'){
			$this->display('hotel_detail');
		}elseif($now_group['pin_num']>0){
			$this->display('pin_detail');
		}else{
			$this->display();
		}
	}

	private function check_group_status($groupids=array()){
		if(!empty($groupids)){
			$tmpids=M('Group')->where('group_id in('.implode(',',$groupids).') and status="1" AND pin_num=0  AND end_time>'.$_SERVER['REQUEST_TIME'])->field('group_id')->select();
			return $tmpids;
		}
		return false;
	}


	public function set(){
		$now_group = D('Group')->get_group_by_groupId($_GET['group_id'],'hits-setInc');
		if(empty($now_group)){
			$this->error_tips('当前'.$this->config['group_alias_name'].'不存在！');
		}
		if($now_group['cue']){
			$now_group['cue_arr'] = unserialize($now_group['cue']);
		}
		if(!empty($now_group['pic_info'])){
			$merchant_image_class = new merchant_image();
			$now_group['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_group['pic_info']);
		}

		if(!empty($this->user_session)){
			$database_user_collect = D('User_collect');
			$condition_user_collect['type'] = 'group_detail';
			$condition_user_collect['id'] = $now_group['group_id'];
			$condition_user_collect['uid'] = $this->user_session['uid'];
			if($database_user_collect->where($condition_user_collect)->find()){
				$now_group['is_collect'] = true;
			}
		}

		$this->assign('now_group',$now_group);

		if($now_group['reply_count']){
			$reply_list = D('Reply')->get_reply_list($now_group['group_id'],0,count($now_group['store_list']),3);
			$this->assign('reply_list',$reply_list);
		}

		$merchant_group_list = D('Group')->get_grouplist_by_MerchantId($now_group['mer_id'],3,true,$now_group['group_id']);
		$this->assign('merchant_group_list',$merchant_group_list);

		//分类下其他团购
		$category_group_list = D('Group')->get_grouplist_by_catId($now_group['cat_id'],$now_group['cat_fid'],3,true);
		foreach($category_group_list as $key=>$value){
			if($value['group_id'] == $now_group['group_id']){
				unset($category_group_list[$key]);
			}
		}
		$this->assign('category_group_list',$category_group_list);

		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($now_group['mer_id'],array('group_hits'=>1));

		/* 粉丝行为分析 */
		$this->behavior(array('mer_id'=>$now_group['mer_id'],'biz_id'=>$now_group['group_id'],'keyword'=>strval($_GET['keywords'])));

		$this->display();
	}
	public function feedback(){
		$now_group = D('Group')->get_group_by_groupId($_GET['group_id']);
		if(empty($now_group)&&!isset($_GET['order_type'])){
			$this->error_tips('当前'.$this->config['group_alias_name'].'不存在！');
		}
		$this->assign('now_group',$now_group);

		$_POST['page'] = $_GET['page'];
		if(isset($_GET['order_type'])){
			$reply_return = D('Reply')->get_page_reply_list('',$_GET['order_type'],'','time',count($now_group['store_list']));
		}else{
			$reply_return = D('Reply')->get_page_reply_list($now_group['group_id'],0,'','time',count($now_group['store_list']));
		}

		$reply_return['pagebar'] = '';
		if($$reply_return['total'] > 1){
			if($reply_return['now'] == 1){
				$reply_return['pagebar'] .= '<a class="btn btn-weak btn-disabled">上一页</a>';
			}else{
				$reply_return['pagebar'] .= '<a class="btn btn-weak" href="'.(U('Group/feedback',array('group_id'=>$now_group['group_id'],'page'=>$reply_return['now']-1))).'">上一页</a>';
			}
			$reply_return['pagebar'] .= '<span class="pager-current">'.($reply_return['now']).'</span>';
			if($reply_return['now'] == $reply_return['total']){
				$reply_return['pagebar'] .= '<a class="btn btn-weak btn-disabled">下一页</a>';
			}else{
				$reply_return['pagebar'] .= '<a class="btn btn-weak" href="'.(U('Group/feedback',array('group_id'=>$now_group['group_id'],'page'=>$reply_return['now']+1))).'">下一页</a>';
			}
		}
		$this->assign($reply_return);

		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($now_group['mer_id'],array('group_hits'=>1));

		/* 粉丝行为分析 */
		$this->behavior(array('mer_id'=>$now_group['mer_id'],'biz_id'=>$now_group['group_id']));

		$this->display();
	}
	public function ajaxFeedback(){
		$this->header_json();
		$now_group = D('Group')->get_group_by_groupId($_GET['group_id']);
		if(empty($now_group)&&!isset($_GET['order_type'])){
			exit(json_encode(array('status'=>0,'info'=>'当前'.$this->config['group_alias_name'].'不存在！')));
		}
		if(!isset($_GET['order_type'])){
			$reply_return = D('Reply')->get_page_reply_list($now_group['group_id'],0,'','time',count($now_group['store_list']));
		}else{
			$reply_return = D('Reply')->get_page_reply_list('',$_GET['order_type'],'','time',count($now_group['store_list']));

		}
		$reply_return['status'] = 1;
		exit(json_encode($reply_return));
	}
	public function branch(){
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
				$rangeSort = array();
				foreach($now_group['store_list'] as &$storeValue){
					$storeValue['Srange'] = getDistance($location2['lat'],$location2['lng'],$storeValue['lat'],$storeValue['long']);
					$storeValue['range'] = getRange($storeValue['Srange'],false);
					$rangeSort[] = $storeValue['Srange'];
				}
				array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
			}
		}
		$this->assign('now_group',$now_group);

		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($now_group['mer_id'],array('group_hits'=>1));

		/* 粉丝行为分析 */
		$this->behavior(array('mer_id'=>$now_group['mer_id'],'biz_id'=>$now_group['group_id']));

		$this->display();
	}
	public function map(){
		$now_group = D('Group')->get_group_by_groupId($_GET['group_id']);

		if(empty($now_group)){
			$this->error_tips('当前'.$this->config['group_alias_name'].'不存在！');
		}
        if(!C('config.google_map_ak')){
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
        }

		$this->assign('now_group',$now_group);

		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($now_group['mer_id'],array('group_hits'=>1));

		/* 粉丝行为分析 */
		$this->behavior(array('mer_id'=>$now_group['mer_id'],'biz_id'=>$now_group['group_id']));

		$this->display();
	}
	public function buy(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->error_tips('请先进行登录！',U('Login/index'));
			}else{
				redirect(U('Login/index'));
			}
		}
		$now_user = D('User')->get_user($this->user_session['uid']);
		if(empty($this->user_session['phone']) && !empty($now_user['phone'])){
			$_SESSION['user']['phone'] = $this->user_session['phone'] = $now_user['phone'];
		}
		$this->assign('now_user',$now_user);

		$now_group = D('Group')->get_group_by_groupId($_GET['group_id']);
		if(empty($now_group)){
			$this->error_tips('当前'.$this->config['group_alias_name'].'不存在！');
		}

		if($now_group['begin_time'] > $_SERVER['REQUEST_TIME']){
			$this->error_tips('此单'.$this->config['group_alias_name'].'还未开始！');
		}
		if($now_group['end_time']<$_SERVER['REQUEST_TIME']){
			$this->error_tips('此单'.$this->config['group_alias_name'].'已结束！');
		}
		//用户等级 优惠
		$level_off=false;
		$finalprice=0;
		$user_= M('User')->where(array('uid'=>$this->user_session['uid']))->find();
		$this->user_session['level'] = $user_['level'];
		if($_GET['type']==1 || $_POST['group_type']==1){
			$now_group['price'] = $now_group['old_price'];
			$now_group['extra_pay_price']=$now_group['extra_pay_old_price'];
		}elseif($_GET['type']==3 || $_POST['group_type']==3){
			$now_group['price'] = intval(round($now_group['price']*$now_group['start_discount']))/100; //团长按团长折扣计算
		}else{
			$now_group['price'] = $now_group['price'];
		}
		$level_discount = 0;
		if( $now_group['trade_type'] != 'hotel' && !empty($this->user_level) && !empty($this->user_session) && isset($this->user_session['level'])){
			$leveloff=!empty($now_group['leveloff']) ? unserialize($now_group['leveloff']) :'';

			/****type:0无优惠 1百分比 2立减*******/
			if(!empty($leveloff) && isset($leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])){
				$level_off=$leveloff[$this->user_session['level']];
				if($level_off['type']==1){
				  	//$finalprice=$now_group['price']*($level_off['vv']/100);
					$level_discount = $now_group['price']*(1-$level_off['vv']/100);
				  	$finalprice=$finalprice>0 ? $finalprice : 0;
				  	$level_off['offstr']='单价按原价'.$level_off['vv'].'%来结算';

				}elseif($level_off['type']==2){
					//$finalprice=$now_group['price']-$level_off['vv'];
				 	$level_discount =$level_off['vv'];
				  	$finalprice=$finalprice>0 ? $finalprice : 0;
				  	$level_off['offstr']='单价立减'.$level_off['vv'].'元';

				}

			}

		}
		$after_discount_finalprice = -1;
		// 折扣优惠方式  0 折上折 1 折扣最优
		if($now_group['discount']>0){
			$tmp_finalprice = round($now_group['price']*$now_group['discount']/10,2);
			$group_discount = $now_group['price']-$tmp_finalprice;
		}


//		!$finalprice && $finalprice = $now_group['price'];
		if($now_group['vip_discount_type']==1){
			$finalprice = $now_group['price']*$now_group['discount']/10;
			if($group_discount>$level_discount){
				$after_discount_finalprice = $tmp_finalprice;
				$finalprice = 0;
				$level_discount = 0;

			}else{
				$after_discount_finalprice = $now_group['price']-$level_discount;
				$group_discount = 0;
			}
		}else if($now_group['vip_discount_type']==2){

			if($level_off['type']==1){
				$level_discount = round($tmp_finalprice*(1-$level_off['vv']/100),2);
			}elseif($level_off['type']==2){
				$level_discount =$level_off['vv'];
			}
			$after_discount_finalprice = $tmp_finalprice-$level_discount;
		}else{
			$after_discount_finalprice = $now_group['price']-$level_discount;
		}




		is_array($level_off) && $level_off['price']=round($finalprice,2);
		$now_start = D('Group_start')->where(array('id'=>$_GET['gid']))->find();
		if(!empty($_GET['gid'])){
			$this_share_group = M('Group_start')->where(array('id'=>$_GET['gid']))->find();
			if(!$now_start['status']&&$now_group['group_id']==$this_share_group['group_id']){
				$_SESSION['gid']=$_GET['gid'];
			}
		}
		unset($leveloff);



		if(IS_POST){
			$after_discount_finalprice>=0 && $finalprice = $after_discount_finalprice ;
			$level_discount>0 && $now_group['vip_discount_money'] = $level_discount;
			$finalprice > 0 && $now_group['price']=round($finalprice,2);
			$level_discount > 0 && $now_group['level_price']=$after_discount_finalprice;
			$result = D('Group_order')->save_post_form($now_group,$this->user_session['uid'],0);

			if($result['error'] == 1){
				$this->error_tips($result['msg']);
			}
			redirect(U('Pay/check',array('order_id'=>$result['order_id'],'type'=>'group')));
		}else{
			if($now_group['tuan_type'] == 2){
				$now_group['user_adress'] = D('User_adress')->get_one_adress($this->user_session['uid'],intval($_GET['adress_id']));

				/*运费计算*/
				if($now_group['user_adress']){
					$express_fee = D('Group')->get_express_fee($now_group['group_id'],$now_group['price'],$now_group['user_adress']);
					$now_group['express_fee'] =$express_fee['freight'];
					$now_group['express_template'] = $express_fee;
					//$now_group['price'] += $now_group['express_fee'];
				}

			}
			$now_group['wx_cheap'] = floatval($now_group['wx_cheap']);
			$pick_list = D('Pick_address')->get_pick_addr_by_merid($now_group['mer_id']);
			if(!empty($_GET['pick_addr_id'])){
				foreach($pick_list as $k=>$v){
					if($v['pick_addr_id']==$_GET['pick_addr_id']){
						$pick_address = $v;
						break;
					}
				}
			}else{
				$pick_address =$pick_list[0];
			}
			$this->assign('pick_address',$pick_address);
			$this->assign('leveloff',$level_off);
			$this->assign('finalprice',$finalprice);

			if($now_group['tuan_type']!=2){
				$now_group['express_fee']=0;
			}
			
			//每ID每天限购
			if($now_group['once_max_day']){
				$now_user_today_count = D('Group_order')->get_once_max_day($now_group['group_id'],$this->user_session['uid']);
				$today_can_buy = $now_group['once_max_day'] - $now_user_today_count;
				
				if($today_can_buy <= 0){
					$this->error_tips('该商品限制单人每天只能购买' . $now_group['once_max_day'] . '份，您当天购买的数量已达上限，不能再购买!');
				}
				
				if(!$now_group['once_max'] && $today_can_buy || $now_group['once_max'] < $today_can_buy){
					$now_group['once_max'] = $today_can_buy;
				}
			}

			$this->assign('now_group',$now_group);
			$this->assign('after_discount_finalprice',$after_discount_finalprice);
			$this->assign('group_discount',$group_discount);
			$this->assign('level_discount',$level_discount);
			
			if($this->user_session['phone']){
				$this->assign('pigcms_phone',substr($this->user_session['phone'],0,3).'****'.substr($this->user_session['phone'],7));
			}else{
				$this->assign('pigcms_phone','您需要绑定手机号码');
			}
			/* 粉丝行为分析 */
			D('Merchant_request')->add_request($now_group['mer_id'],array('group_hits'=>1));

			/* 粉丝行为分析 */
			$this->behavior(array('mer_id'=>$now_group['mer_id'],'biz_id'=>$now_group['group_id']));


						
			if($now_group['trade_type'] == 'hotel'){
				$trade_hotel['time_dep_time'] = date('Y-m-d');
				$trade_hotel['show_dep_time'] = date('m-d');
				$trade_hotel['dep_time'] = date('Ymd');
				$trade_hotel['time_end_time'] = date('Y-m-d',time()+86400);
				$trade_hotel['show_end_time'] = date('m-d',time()+86400);
				$trade_hotel['end_time'] = date('Ymd',time()+86400);
				$hotel_list_tmp = D('Trade_hotel_category')->get_all_list($now_group['mer_id'],true,$trade_hotel['dep_time'],$trade_hotel['end_time']);

				$hotel_cat_id = explode(',',$now_group['trade_info']);
				foreach ($hotel_cat_id as $vc) {
					$hotel_list[$vc] = $hotel_list_tmp[$vc];
				}
				$this->assign('trade_hotel',$trade_hotel);
				$this->assign('hotel_list',$hotel_list);
				$this->display('buy_trade_hotel');
			}else{
				$this->display();
			}
		}
	}
	public function ajax_get_trade_hotel_stock(){
		$now_group = D('Group')->get_group_by_groupId($_POST['group_id']);

		$hotel_list_tmp = D('Trade_hotel_category')->get_all_list($now_group['mer_id'],true,$_POST['dep_time'],$_POST['end_time']);
		$hotel_cat_id = explode(',',$now_group['trade_info']);
//		foreach ($hotel_cat_id as $vc) {
//			$hotel_list[$vc] = $hotel_list_tmp[$vc];
//		}
		foreach($hotel_list_tmp as $key=>$v){
			if(in_array($key,$hotel_cat_id)){
				$hotel_list[] = $v;
			}
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
	public function addressinfo(){
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		$this->assign('long_lat',$long_lat);

		$now_store = D('Merchant_store')->get_store_by_storeId($_GET['store_id']);
		if(empty($now_store)){
			$this->error_tips('该店铺不存在！');
		}
		$this->assign('now_store',$now_store);

		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($now_store['mer_id'],array('group_hits'=>1));

		/* 粉丝行为分析 */
		$this->behavior(array('mer_id'=>$now_store['mer_id'],'store_id'=>$now_store['store_id']));

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