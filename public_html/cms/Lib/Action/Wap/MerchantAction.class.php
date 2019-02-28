<?php
class MerchantAction extends BaseAction{
	public function around(){
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		$this->assign('long_lat',$long_lat);
		$this->display();
	}
	public function ajaxAround(){
		$this->header_json();
		$list = D('Merchant')->get_merchants_by_long_lat($_POST['lat'], $_POST['lng'],2000);
		echo json_encode($list);
	}
	//	店铺列表
	public function store_list(){
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

		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);	//获取用户的经度和纬度
		if(empty($long_lat)){
			$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
			$sort_array = array(
				array('sort_id'=>'defaults','sort_value'=>'默认排序'),
				array('sort_id'=>'rating','sort_value'=>'评价最高'),
				array('sort_id'=>'start','sort_value'=>'最新发布'),
				array('sort_id'=>'solds','sort_value'=>'人气最高'),
			);
		} else {
			$sort_array = array(
				array('sort_id'=>'juli','sort_value'=>'离我最近'),
				array('sort_id'=>'rating','sort_value'=>'评价最高'),
				array('sort_id'=>'start','sort_value'=>'最新发布'),
				array('sort_id'=>'solds','sort_value'=>'人气最高'),
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
		$all_category_list = D('Merchant_category')->get_all_category();
		$this->assign('all_category_list',$all_category_list);

		//根据分类信息获取分类
		if(!empty($cat_url)){
			$now_category = D('Merchant_category')->get_category_by_catUrl($cat_url);
			if(empty($now_category)){
				$this->error_tips('此分类不存在！');
			}
			$this->assign('now_category',$now_category);

			if(!empty($now_category['cat_fid'])){
				$f_category = D('Merchant_category')->get_category_by_id($now_category['cat_fid']);
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
		//$this->assign(D('Merchant')->wap_get_Merchant_list_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id,$_GET['w']));

		/* 粉丝行为分析 */
		$this->behavior(array('model'=>'Merchant_store_list'));

		$this->display();
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
			$tmp_area_id	=	$tmp_area['area_id'];
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
		$sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		if(empty($long_lat)){
			$sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
		}
		if($_GET['page'] == 1){
			if($long_lat){
				$condition_field = "`img`,`area_id`,`introduce`,`market_name`,`market_id`, ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$long_lat['lat']}*PI()/180-`lat`*PI()/180)/2),2)+COS({$long_lat['lat']}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$long_lat['long']}*PI()/180-`long`*PI()/180)/2),2)))*1000) AS juli";
			}else{
				$condition_field = "`img`,`area_id`,`introduce`,`market_name`,`market_id`";
			}
			if($tmp_area_id){
				$area_market	=	M('Area_market')->field($condition_field)->order('market_sort desc')->where(array('area_id'=>$tmp_area_id,'is_open'=>1))->limit(3)->select();
			}else{
//				$area_market	=	M('Area_market')->field($condition_field)->order('market_sort desc')->where(array('city_id'=>C('config.now_city'),'is_hot'=>1,'is_open'=>1))->limit(3)->select();
			}
			foreach($area_market as $k=>$v){
				$area_market[$k]['img']		=	$this->config['site_url'].$v['img'];
				$area_market[$k]['count']	=	M('merchant_store')->where(array('market_id'=>$v['market_id'],'status'=>1))->count();
				if($v['juli']){
					$area_market[$k]['range_txt']	=	$this->wapFriendRange($v['juli']);
				}else{
					$area_market[$k]['range_txt']	=	'';
				}
				$area_market[$k]['url']	=	U('store_market_list',array('market_id'=>$v['market_id']));
			}
		}
		//所有分类 包含2级分类
		$all_category_list = D('Merchant_category')->get_all_category();

		//根据分类信息获取分类
		if(!empty($cat_url)){
			$now_category = D('Merchant_category')->get_category_by_catUrl($cat_url);
			if(empty($now_category)){
				$this->error_tips('此分类不存在！');
			}

			if(!empty($now_category['cat_fid'])){
				$f_category = D('Merchant_category')->get_category_by_id($now_category['cat_fid']);
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

		$return = S('wap_store_'.($get_grouplist_catfid ? $get_grouplist_catfid : $get_grouplist_catid).'_'.$area_id.'_'.$sort_id.'_'.$_GET['page']);
		if(empty($return)){
			$return = D('Merchant')->wap_get_storeList_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id,$_GET['w']);
			S('wap_store_group_'.($get_grouplist_catfid ? $get_grouplist_catfid : $get_grouplist_catid).'_'.$area_id.'_'.$sort_id.'_'.$_GET['page'],$return,360);
		}

		foreach($return['store_list'] as &$storeValue){
			//得到友好的距离
			if($long_lat&&($sort_id=='defaults'||$sort_id=='juli')){
				$storeValue['range_txt'] = $this->wapFriendRange($storeValue['juli']);
			}else{
				$storeValue['range_txt'] =	'';
			}
			$storeValue['fans_count'] = M('Merchant_user_relation')->where(array('mer_id'=>$storeValue['mer_id']))->count();
			//	预约
			if($this->config['appoint_site_logo']){
				$storeValue['now_appoint']	=	D('Appoint')->get_appointlist_by_StoreId($storeValue['store_id'],true,2);
			}
		}
		$return['style'] = 'group';
		$return['market_list'] = isset($area_market)?$area_market:array();
		$return['img']	=	array(
			'group_alias_name'	=>	substr($this->config['group_alias_name'],0,3),
			'waimai_alias_name'	=>	substr($this->config['waimai_alias_name'],0,3),
			'meal_alias_name'	=>	substr($this->config['meal_alias_name'],0,3),
		);
		echo json_encode($return);
		/* 粉丝行为分析 */
		$this->behavior(array('model'=>'Group_index'));
	}
	//	店铺详情
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
		//	快店
		$now_store['wap_url'] = U('Food/shop',array('mer_id'=>$now_store['mer_id'],'store_id'=>$now_store['store_id']));
		$this->assign('now_store',$now_store);
		$store_group_list = D('Group')->get_store_group_list($now_store['store_id'],0,true);
		$this->assign('store_group_list',$store_group_list);

		//	预约
		if($this->config['appoint_page_row']){
			$now_appoint	=	D('Appoint')->get_appointlist_by_StoreId($now_store['store_id'],true);
			$this->assign('now_appoint',$now_appoint);
		}
		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($now_store['mer_id'],array('group_hits'=>1));
		/*人气自增*/
		M('Merchant_store')->where(array('store_id'=>$now_store['store_id']))->setInc('hits');
		/* 粉丝行为分析 */
		$this->behavior(array('mer_id'=>$now_store['mer_id'],'store_id'=>$now_store['store_id']));

		if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $now_store['mer_id']))->select()) {
			$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
			$kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_store['mer_id'];
			$this->assign('kf_url', $kf_url);
		}
		$this->assign('is_wap', M('Classify')->field(true)->where(array('token' => $now_store['mer_id']))->find());




		$this->display();
	}
	//	商场店铺列表
	public function store_market_list(){
		$market_id	=	$_GET['market_id'];
		$this->assign('market_id', $market_id);
		/* 粉丝行为分析 */
		$this->behavior(array('model'=>'Merchant_store_market_list'));
		$this->display();
	}
	//	获取商场店铺列表接口
	public function store_market_list_ajax(){
		$this->header_json();
		$market_id	=	$_GET['market_id'];
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		$return = D('Merchant')->wap_get_storeList($market_id, $long_lat['lat'], $long_lat['long']);
		foreach($return['store_list'] as &$storeValue){
			//得到友好的距离
			if($long_lat){
				$storeValue['range_txt'] =	$this->wapFriendRange($storeValue['juli']);
			}else{
				$storeValue['range_txt'] =	'';
			}
		}
		$return['style'] = 'group';
		$return['img']	=	array(
			'group_alias_name'	=>	substr($this->config['group_alias_name'],0,3),
			'waimai_alias_name'	=>	substr($this->config['waimai_alias_name'],0,3),
			'meal_alias_name'	=>	substr($this->config['meal_alias_name'],0,3),
		);
		echo json_encode($return);
	}
}
?>