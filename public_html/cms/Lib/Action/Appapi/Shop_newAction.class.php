<?php
/*
 * 新版快店接口
 *
 */
class Shop_newAction extends BaseAction{
	
	protected $send_time_type = array('分钟', '小时', '天', '周', '月');
		
	public function lbs_location(){
		if(empty($_POST['lng']) || empty($_POST['lat'])){
			$this->returnCode(1001,array(),'请携带用户经纬度');
		}
		$lng = $_POST['lng'];
		$lat = $_POST['lat'];
		$return = array();
		
		$lbs_type = 0;
		if($_POST['lbs_type'] == 'gcj02'){
			$lbs_type = 3;
		}else if($_POST['lbs_type'] == 'gps'){
			$lbs_type = 1;
		}
		if($lbs_type){
			import('@.ORG.longlat');
			$longlat_class = new longlat();
			$location2 = $longlat_class->toBaidu($_POST['lat'],$_POST['lng'],$lbs_type);
			$lat = $location2['lat'];
			$lng = $location2['lng'];
		}
		
		//获取当前省市区
		$now_city = D('Area')->cityMatching($lat,$lng,true);
		//如果当前城市没开，使用默认城市
		if(empty($now_city['is_open'])){
			$tmp_now_city = M('Area')->where(array('area_id'=>C('config.now_city')))->find();
			$area_lng = floatval($tmp_now_city['area_lng']);
			if(empty($area_lng)){
				$url = 'http://api.map.baidu.com/geocoder/v2/?address='.$tmp_now_city['area_name'].'&output=json&ak='.C('config.baidu_map_ak');
				import('ORG.Net.Http');
				$http = new Http();
				$result = $http->curlGet($url);
				$result = json_decode($result, true);
				$lng = $result['result']['location']['lng'];
				$lat = $result['result']['location']['lat'];
				M('Area')->where(array('area_id'=>C('config.now_city')))->data(array('area_lng'=>$lng,'area_lat'=>$lat))->save();
			}else{
				$lng = $tmp_now_city['area_lng'];
				$lat = $tmp_now_city['area_lat'];
			}
			$now_city = D('Area')->cityMatching($lat,$lng,true);
			$now_city['address_name'] = $tmp_now_city['area_name'];
			$now_city['address_addr'] = $tmp_now_city['area_name'];
		}
		C('config.now_city',$now_city['area_id']);
		$return = array(
			'lng'			=>$lng,
			'lat'			=>$lat,
			'province_id'	=>$now_city['area_info']['province_id'],
			'province_name'	=>$now_city['area_info']['province_name'],
			'city_id'		=>$now_city['area_id'],
			'city_name'		=>$now_city['area_name'],
			'area_id'		=>$now_city['area_info']['area_id'],
			'area_name'		=>$now_city['area_info']['area_name'],
			'address_name'	=>$now_city['address_name'],
			'address_addr'	=>$now_city['address_addr'],
			'pois'			=>$now_city['pois'],
		);
		
		$this->returnCode(0,$return);
	}
	
	public function index(){
		if(empty($_POST['lng']) || empty($_POST['lat'])){
			$this->returnCode(1001,array(),'请携带用户经纬度');
		}
		$lng = $_POST['lng'];
		$lat = $_POST['lat'];
		$return = array();
		
		$lbs_type = 0;
		if($_POST['lbs_type'] == 'gcj02'){
			$lbs_type = 3;
		}else if($_POST['lbs_type'] == 'gps'){
			$lbs_type = 1;
		}
		if($lbs_type){
			import('@.ORG.longlat');
			$longlat_class = new longlat();
			$location2 = $longlat_class->toBaidu($_POST['lat'],$_POST['lng'],$lbs_type);
			$lat = $location2['lat'];
			$lng = $location2['lng'];
		}
		
		
		//获取当前省市区
		$now_city = D('Area')->cityMatching($lat,$lng);
		//如果当前城市没开，使用默认城市
		if(empty($now_city['is_open'])){
			$tmp_now_city = M('Area')->where(array('area_id'=>C('config.now_city')))->find();
			$area_lng = floatval($tmp_now_city['area_lng']);
			if(empty($area_lng)){
				$url = 'http://api.map.baidu.com/geocoder/v2/?address='.$tmp_now_city['area_name'].'&output=json&ak='.C('config.baidu_map_ak');
				import('ORG.Net.Http');
				$http = new Http();
				$result = $http->curlGet($url);
				$result = json_decode($result, true);
				$lng = $result['result']['location']['lng'];
				$lat = $result['result']['location']['lat'];
				M('Area')->where(array('area_id'=>C('config.now_city')))->data(array('area_lng'=>$lng,'area_lat'=>$lat))->save();
			}else{
				$lng = $tmp_now_city['area_lng'];
				$lat = $tmp_now_city['area_lat'];
			}
			$now_city = D('Area')->cityMatching($lat,$lng);
			$now_city['address_name'] = $tmp_now_city['area_name'];
			$now_city['address_addr'] = $tmp_now_city['area_name'];
		}
		C('config.now_city',$now_city['area_id']);
		$return['city_info'] = array(
			'lng'			=>$lng,
			'lat'			=>$lat,
			'province_id'	=>$now_city['area_info']['province_id'],
			'province_name'	=>$now_city['area_info']['province_name'],
			'city_id'		=>$now_city['area_id'],
			'city_name'		=>$now_city['area_name'],
			'area_id'		=>$now_city['area_info']['area_id'],
			'area_name'		=>$now_city['area_info']['area_name'],
			'address_name'	=>$now_city['address_name'],
			'address_addr'	=>$now_city['address_addr'],
		);
		
		//轮播图
		$return['banner_list'] = D('Adver')->get_adver_by_key('wap_shop_index_top', 5, true);
		
		//导航栏  分十个或八个的场景
		$tmp_wap_index_slider = D('Slider')->get_slider_by_key('wap_shop_slider', 0,true);
		$return['slider_list'] = array();
		if(count($tmp_wap_index_slider) >= 10 &&$this->config['wap_slider_number'] == 10){
			$wap_slider_number = $this->config['wap_slider_number'];
		}else{
			$wap_slider_number = 8;
		}
		foreach($tmp_wap_index_slider as $key=>$value){
			$tmp_i = floor($key/$wap_slider_number);
			$return['slider_list'][$tmp_i][] = $value;
		}
		$return['slider_list_length'] = $wap_slider_number;
		
		
		//中间橱窗广告
		$showcase_adver_arr = D('Adver')->get_adver_by_key('wap_shop_index_showcase_adver', 1, true);
		if(empty($showcase_adver_arr)){
			$return['showcase_adver'] = array();
		}else{
			$return['showcase_adver'] = $showcase_adver_arr[0];
		}
		
		//中间3、4、5图广告
		$show_ad = $this->config['shop_main_page_show_ad'];
		$center_type = $this->config['shop_main_page_center_type'];
		$return['center_slider_type'] = $center_type;
		if($show_ad){
			$where['type'] =$center_type;
			$slider_list = M('Shop_main_page_pic_slider')->field('`name`,`url`,`pic`')->where($where)->order('sort DESC')->limit($center_type)->select();
			foreach ($slider_list as &$v) {
				$v['url'] = htmlspecialchars_decode($v['url']);
				$v['pic'] = $this->config['site_url'].'/upload/slider/'.$v['pic'];
			}
			$return['center_slider'] = $slider_list;
		}else{
			$return['center_slider'] = array();
		}
		
		//首页中间轮播宽图
		$return['center_adver_list'] = D('Adver')->get_adver_by_key('wap_shop_index_center_slider_adver', 3, true);
		
		//为您优选
		$where = array('preference_status'=>'1','order' => 'preference_sort', 'lat' => $lat, 'long' => $lng,  'page' => '1', 'limit' => 4,'show_method'=>0);
		$lists = D('Merchant_store_shop')->get_list_by_option($where);
		$return['preference_shop_list'] = array();
		if($lists){
			foreach($lists['shop_list'] as $value){
				$tmp_shop = array(
					'store_id' 					=> $value['store_id'],
					'store_name' 				=> $value['name'],
					'merchant_logo' 			=> $value['merchant_logo'],
					'preference_reason' 		=> $value['preference_reason'],
					'store_image' 				=> $value['image'],
					'shop_fitment_color'		=> $value['shop_fitment_color'] ? $value['shop_fitment_color'] : '113533', //背景色
					'shop_fitment_front_color' 	=> 'ffffff', //标题色	
				);
				
				$return['preference_shop_list'][] = $tmp_shop;
			}
		}
		
		//分类、排序、筛选
		$_GET['is_return'] = 1;
		$categoryType = $this->getCategoryType();
		
		$return = array_merge($return,$categoryType);
		
		//天气
		$im = new im();
		$return['now_weather'] = $im->get_now_weather($return['city_info']['city_name']);
		
		$this->returnCode(0,$return);
	}
	
	//分类、排序、筛选
	public function getCategoryType(){
		$return['category_list'] = D('Shop_category')->lists(true);
		if(empty($return['category_list'])){
			$return['category_list'] = array();
		}else{
			foreach($return['category_list'] as &$cat_value){
				unset($cat_value['cat_id']);
				unset($cat_value['cat_fid']);
				unset($cat_value['cat_sort']);
				unset($cat_value['cat_status']);
				unset($cat_value['show_method']);
				unset($cat_value['cue_field']);
				if($cat_value['son_list']){
					foreach($cat_value['son_list'] as &$cat_v){
						unset($cat_v['cat_id']);
						unset($cat_v['cat_fid']);
						unset($cat_v['cat_sort']);
						unset($cat_v['cat_status']);
						unset($cat_v['show_method']);
						unset($cat_v['cue_field']);
					}
				}
			}
		}
		$return['sort_list'] = array(
			array(
					'name' => '智能排序',
					'sort_url' => 'juli'
			),
			array(
					'name' => '销售数量最高',
					'sort_url'=>'sale_count'
			),
			array(
					'name' => '配送时间最短',
					'sort_url'=>'send_time'
			),
			array(
					'name' => '起送价最低',
					'sort_url' => 'basic_price'
			),
			array(
					'name' => '评分最高',
					'sort_url' => 'score_mean'
			),
			array(
					'name' => '最新发布',
					'sort_url' => 'create_time'
			)
		);
		$return['type_list'] = array(
			array(
				'name' => '全部',
				'type_url' => 'all'
			),
			array(
				'name' => '配送',
				'type_url' => 'delivery'
			),
			array(
				'name' => '自提',
				'type_url' => 'pick'
			)
		);
		if($_GET['is_return']){
			return $return;
		}else{
			$this->returnCode(0,$return);
		}
	}
	
	/*获取店铺列表*/
	public function ajax_list(){
		$key = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
		$cat_url = isset($_POST['cat_url']) ? htmlspecialchars($_POST['cat_url']) : 'all';
		$order = isset($_POST['sort_url']) ? htmlspecialchars($_POST['sort_url']) : 'juli';
		$deliver_type = isset($_POST['type_url']) ? htmlspecialchars($_POST['type_url']) : 'all';
		$lat = isset($_POST['lat']) ? htmlspecialchars($_POST['lat']) : 0;
		$long = isset($_POST['lng']) ? htmlspecialchars($_POST['lng']) : 0;
		$is_search = $_POST['is_search'] ? 1 : 0;
		$search_type = isset($_POST['search_type']) ? htmlspecialchars($_POST['search_type']) : '';
		$store_ids = isset($_POST['store_ids']) ? $_POST['store_ids'] : array();
		cookie('userLocationLong', $long);
		cookie('userLocationLat', $lat);
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$page = max(1, $page);
		$cat_id = 0;
		$cat_fid = 0;
        $village_id = $_POST['village_id'] + 0;
		if($_POST['village_id']){
			$now_village =  D('House_village')->get_one($_POST['village_id']);
			$lat = $now_village['lat'];
			$long = $now_village['long'];
		}
		if ($cat_url != 'all') {
			$now_category = D('Shop_category')->get_category_by_catUrl($cat_url);
			if ($now_category) {
				if ($now_category['cat_fid']) {
					$cat_id = $now_category['cat_id'];
					$cat_fid = $now_category['cat_fid'];
				} else {
					$cat_id = 0;
					$cat_fid = $now_category['cat_id'];
				}
			}
		}

		$where = array('deliver_type' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page, 'search_type'=>$search_type);
		$key && $where['key'] = $key;
		$store_ids && $where['store_ids'] = $store_ids;

		$merchantStoreShopDB = D('Merchant_store_shop');
        // 如果存在社区id ,获取社区关联商户id, 加入查询条件中
        if ($village_id) {
            $where['village_id'] = $village_id;
            $lists = D('House_village_store')->get_list_by_option($where);
        } else {
            $lists = $merchantStoreShopDB->get_list_by_option($where,1,$is_search);
        }
		$return = array();
		$now_time = date('H:i:s');
		$hot_keywords = is_array($_POST['hot_keywords']) ? $_POST['hot_keywords'] : array();
		$hot_keywords_time_unix = $_POST['hot_keywords_time'] ? $_POST['hot_keywords_time'] : time();
		$hot_keywords_time_hour = date('G',$hot_keywords_time_unix);
		$now_time_hour = date('G');
		if(($hot_keywords_time_hour >= 4 && $hot_keywords_time_hour < 10) && ($now_time_hour >= 10 || $now_time_hour < 4)){	//早饭
			$hot_keywords = array();
		}else if(($hot_keywords_time_hour >= 10 && $hot_keywords_time_hour < 14) && ($now_time_hour >= 14 || $now_time_hour < 10)){	//午饭
			$hot_keywords = array();
		}else if(($hot_keywords_time_hour >= 14 && $hot_keywords_time_hour < 17) && ($now_time_hour >= 17 || $now_time_hour < 14)){	//下午茶
			$hot_keywords = array();
		}else if(($hot_keywords_time_hour >= 17 && $hot_keywords_time_hour < 4) && ($now_time_hour >= 4 && $now_time_hour < 17)){	//晚饭+夜宵
			$hot_keywords = array();
		}
		
		foreach ($lists['shop_list'] as $row) {
			$temp = array();
			$temp['store_id'] = $row['store_id'];
			$temp['name'] = $row['name'];
			$temp['store_theme'] = $row['store_theme'];
			$temp['isverify'] = $row['isverify'];
			$temp['extra_price'] = $row['extra_price'];
			$temp['juli_wx'] = $row['juli'];
			$temp['range'] = $row['range'];
			$temp['image'] = $row['image'];
			if($row['logo']){
				$temp['image'] = $this->config['site_url'].$row['logo'];
			}
			$temp['image'] = $this->config['site_url'] . '/index.php?c=Image&a=thumb&width=180&height=120&url=' . urlencode($temp['image']);
			
			$temp['star'] = $row['score_mean'];
			if($temp['star'] == '0.0'){
				$temp['star'] = '3.5';
			}
			$temp['month_sale_count'] = $row['sale_count'];
			$temp['shop_fitment_color'] = $row['shop_fitment_color'] ? $row['shop_fitment_color'] : '113533';			//背景色
			$temp['shop_fitment_front_color'] = 'ffffff';		//标题色
			$temp['is_new'] = ($row['create_time'] + 864000) < time() ? 0 : 1;
			$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
			$temp['delivery'] = $temp['delivery'] ? true : false;
			$temp['delivery_time'] = $row['send_time'];//配送时长
			$temp['send_time_type'] = $row['send_time_type'];
			$temp['delivery_time_type'] = $this->send_time_type[$row['send_time_type']];
			$temp['delivery_price'] = floatval($row['basic_price']);//起送价
			$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
			$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
			$temp['is_close'] = 1;
			$temp['deliver_type'] = $row['deliver_type'];
			if ($merchantStoreShopDB->checkTime($row)) {
			    $temp['is_close'] = 0;
			}
			$temp['is_close'] = $temp['is_close'] ? $temp['is_close'] : $row['is_close'];
			$temp['time'] = $merchantStoreShopDB->getBuniessName($row);
			
			if($is_search){
				$temp['goods_list'] = $row['goods_list'] ? $row['goods_list'] : array();
			}
			
			if($where['key']){
				$temp['name_arr'] = $row['name_arr'];
			}

			$temp['coupon_list'] = array();
			if ($row['is_invoice']) {
				$temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
			}
// 			$temp['coupon_list']['isdiscountgoods'] = 0;
			if ($row['isDiscountGoods']) {
			    $temp['coupon_list']['isdiscountgoods'] = 1;
			}
			if ($row['isdiscountsort']) {//店铺的商品分类折扣
			    $temp['coupon_list']['isdiscountsort'] = 1;
			}
			if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
				$temp['coupon_list']['discount'] = $row['store_discount']/10;
			}
			$system_delivery = array();
			foreach ($row['system_discount'] as $row_d) {
				if ($row_d['type'] == 0) {//新单
					$temp['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 1) {//满减
					$temp['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 2) {//配送
					if ($row_d['full_money'] > 0 && $row_d['reduce_money'] > 0) {
						$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
					}
				}
			}
			foreach ($row['merchant_discount'] as $row_m) {
				if ($row_m['type'] == 0) {
					$temp['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				} elseif ($row_m['type'] == 1) {
					$temp['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				}
			}
			if ($row['deliver']) {
				if ($temp['delivery_system']) {
					$system_delivery && $temp['coupon_list']['delivery'] = $system_delivery;
				} else {
					if ($row['is_have_two_time']) {
						if ($row['reach_delivery_fee_type2'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
							}
						} elseif ($row['reach_delivery_fee_type2'] == 1) {
							//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
						} elseif ($row['reach_delivery_fee_type2'] == 2) {
							$row['delivery_fee2'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
						}
					} else {
						if ($row['reach_delivery_fee_type'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
							}
						} elseif ($row['reach_delivery_fee_type'] == 1) {
							//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
						} elseif ($row['reach_delivery_fee_type'] == 2) {
							$row['delivery_fee'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
						}
					}
				}
			}
			$temp['coupon_count'] = count($temp['coupon_list']);
			$temp['coupon_list'] = $this->simpleParseCoupon($temp['coupon_list'],'array');
			$temp['deliver_name'] = isset($this->config['deliver_name']) && !empty($this->config['deliver_name']) ? $this->config['deliver_name'] : '平台配送';
			$return[] = $temp;
			
			//热门关键词
			$tmp_key = explode(' ',$row['search_keywords']);
			if(!empty($tmp_key)){
				foreach($tmp_key as $key=>$value){
					if(empty($value)){
						unset($tmp_key[$key]);
					}
				}
			}
			if(!empty($tmp_key)){
				$hot_keywords = array_unique(array_merge($hot_keywords,$tmp_key));
			}
		}
		$array = array('store_list' => $return, 'hot_keywords' => $hot_keywords, 'hot_keywords_time' => time() , 'has_more' => $lists['has_more'] ? true : false);
		$this->returnCode(0,$array);
    }
	public function simpleParseCoupon($obj){
		$returnObj = array();
        foreach ($obj as $key => $value) {
            if ($key == 'invoice') {
				$returnObj[] = array(
					'type' => 'invoice',
					'text' => '票',
				);
            } elseif ($key == 'discount') {
				$returnObj[] = array(
					'type' => 'discount',
					'text' => '全场'. $obj[$key] . '折',
				);
            } elseif ($key == 'isdiscountgoods') {
				$returnObj[] = array(
					'type' => 'isdiscountgoods',
					'text' => '限时优惠',
				);
            } elseif ($key == 'isdiscountsort') {
				$returnObj[] = array(
					'type' => 'isdiscountsort',
					'text' => '折扣优惠',
				);
            } else {
                foreach ($obj[$key] as $k => $v) {
                    if($key == 'delivery'){
                        $returnObj[] = array(
							'type' => 'delivery',
							'text' => '满'.$obj[$key][$k]['money'].'配送减'.$obj[$key][$k]['minus'],
						);
                    } else if($key == 'system_newuser'){
                        $returnObj[] = array(
							'type' => 'system_newuser',
							'text' => '平台首单'.$obj[$key][$k]['money'].'减'.$obj[$key][$k]['minus'],
						);
                    } else if($key == 'newuser'){
                        $returnObj[] = array(
							'type' => 'newuser',
							'text' => '店铺首单'.$obj[$key][$k]['money'].'减'.$obj[$key][$k]['minus'],
						);
                    } else {
						 $returnObj[] = array(
							'type' => 'minus',
							'text' => $obj[$key][$k]['money'].'减'.$obj[$key][$k]['minus'],
						);
                    }
                }
            }
        }
		return $returnObj;
	}
	/* 解析优惠券 */
	public function parseCoupon($obj, $type)
    {
        $returnObj = array();
        foreach ($obj as $key => $value) {
            if ($key == 'invoice') {
                $returnObj[$key] = '满' . $obj[$key] . '元支持开发票，请在下单时填写发票抬头';
            } elseif ($key == 'discount') {
                $returnObj[$key] = '店内全场' . $obj[$key] . '折';
            } elseif ($key == 'isdiscountgoods') {
                $returnObj[$key] = '店内有部分商品限时优惠';
            } elseif ($key == 'isdiscountsort') {
                $returnObj[$key] = '部分商品分类参与折扣优惠';
            } else {
                $returnObj[$key] = [];
                foreach ($obj[$key] as $k => $v) {
                    if ($key == 'delivery') {
                        $returnObj[$key][] = '商品满' . $obj[$key][$k]['money'] . ',配送费减' . $obj[$key][$k]['minus'] . '';
                    } else {
                        $returnObj[$key][] = '满' . $obj[$key][$k]['money'] . '减' . $obj[$key][$k]['minus'] . '';
                    }
                }
            }
        }
        
        $textObj = array();
        foreach ($returnObj as $key => $value) {
            if ($key == 'invoice' || $key == 'discount' || $key == 'isdiscountgoods' || $key == 'isdiscountsort') {
                $textObj[$key] = $value;
            } else {
                switch ($key) {
                    case 'system_newuser':
                        $textObj[$key] = '平台首单' . implode(',', $value);
                        break;
                    case 'system_minus':
                        $textObj[$key] = '平台优惠' . implode(',', $value);
                        break;
                    case 'newuser':
                        $textObj[$key] = '店铺首单' . implode(',', $value);
                        break;
                    case 'minus':
                        $textObj[$key] = '店铺优惠' . implode(',', $value);
                        break;
                    case 'system_minus':
                        $textObj[$key] = '平台优惠' . implode(',', $value);
                        break;
                    case 'delivery':
                        $textObj[$key] = implode(',', $value);
                        break;
                }
            }
        }
        if ($type == 'text') {
            $tmpObj = array();
            foreach ($textObj as $key => $value) {
                $tmpObj[] = $value;
            }
            return implode(';', $tmpObj);
        } else {
            $returnObj = array();
            foreach ($textObj as $key => $value) {
                $returnObj[] = array(
                    'type' => $key,
                    'value' => $value
                );
            }
            return $returnObj;
        }
    }
	public function barcodeGetName(){
		if(empty($_POST['barcode'])){
			$this->returnCodeError('请携带条形码值');
		}
		$barcode = trim($_POST['barcode']);
		$where = array(
			'number' => $barcode
		);
        $nowGood = M('System_goods')->field('`name`')->where($where)->find();
		if(!$nowGood){
			$nowGood['name'] = $barcode;
		}
		$this->returnCodeOk($nowGood);
	}
	public function ajax_shop(){
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		if (empty($store_id)){
		    $this->returnCodeError('请携带 store_id 参数');
		}
		$where = array('store_id' => $store_id);
		$now_store = D('Merchant_store')->field(true)->where($where)->find();	
		$now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
		$now_mer = M('Merchant')->field('isverify')->where(array('mer_id'=>$now_store['mer_id']))->find();
		if (empty($now_shop) || empty($now_store)) {
		    $this->returnCodeError('该' . $this->config['shop_alias_name'] . '暂未完善信息，无法使用');
		}
		
		// 1/5的概率同步一下评价数字，防止几率性的不一致
		if(mt_rand(1,5) == 5){
			$reply_count = M('Reply')->where("`order_type`='3' AND `parent_id`='".$now_store['store_id']."' AND `status`<2")->count();
			if($now_shop['reply_count'] != $reply_count){
				D('Merchant_store_shop')->data(array('reply_count'=>$reply_count))->where($where)->save();
				$now_shop['reply_count'] = $reply_count;
			}
		}
		
		$auth_files = array();
		if ($now_store['auth'] > 2) {
			if (!empty($now_store['auth_files'])) {
				$auth_file_class = new auth_file();
				$tmp_pic_arr = explode(';', $now_store['auth_files']);
				foreach($tmp_pic_arr as $key => $value){
					$auth_file_temp = $auth_file_class->get_image_by_path($value);//array('title' => $value, 'url' => $auth_file_class->get_image_by_path($value, 's'));
					$auth_files[] = $auth_file_temp['image'];
				}
			}
		}
		$now_store['auth_files'] = $auth_files;
		
// 		$discounts = D('Shop_discount')->get_discount_byids(array($store_id));
		$discounts = D('Shop_discount')->getDiscounts($now_store['mer_id'], $store_id);
		$row = array_merge($now_store, $now_shop);
		
		$store = array();
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($row['pic_info']);
	
		$store['id'] = $row['store_id'];
		$store['store_id'] = $row['store_id'];
		
		$store['phone'] = trim($row['phone']);
		$store['long'] = $row['long'];
		$store['lng'] = $row['long'];
		$store['lat'] = $row['lat'];
		$store['isverify'] = $now_mer['isverify'];
		$store['store_theme'] = $row['store_theme'];
		$store['adress'] = $row['adress'];
		$store['close_reason'] = $row['close_reason'];
        $store['openclassfyindex'] = $row['is_mult_class'];
        $store['deliver_type'] = $row['deliver_type'];
		
		//背景色
		$store['shop_fitment_color'] = $row['shop_fitment_color'] ? $row['shop_fitment_color'] : '113533';			
		//标题色
		$store['shop_fitment_front_color'] = 'ffffff';
		
		$store['image'] = isset($images[0]) ? $images[0] : '';
		if($row['logo']){
			$store['image'] = $this->config['site_url'].$row['logo'];
		}
		
		$store['images'] = $images ? $images : array();
		
		$store['share_title'] = $row['name'];
		$store['share_content'] = $row['store_notice'];
		$store['share_image'] = !empty($images) ? array_shift($images) : '';
		
		if ($row['store_theme'] && empty($row['is_mult_class'])) {
		    $store['share_url'] = $this->config['site_url'] . 'wap.php?c=Shop&a=classic_shop&shop_id=' . $row['store_id'];
		} else {
		    $store['share_url'] = $this->config['site_url'] . '/wap.php?g=Wap&c=Shop&a=index#shop-' . $row['store_id'];
		}
		
		//返回商家是否支持客服
		if($this->DEVICE_ID == 'wxapp' && $services = D('Customer_service')->where(array('mer_id' => $row['mer_id']))->select()) {
            $store['kefu_url'] = $this->config['site_url'].'/wap.php?c=My&a=concact_kefu&mer_id='.$row['mer_id'];
        }else{
			$store['kefu_url'] = '';
		}
		
		
		$store['time'] = D('Merchant_store_shop')->getBuniessName($row);
		
		if($row['is_close']){
			$store['is_close'] = 1;
		}else{
			$store['is_booking'] = false;
			if (!D('Merchant_store_shop')->checkTime($row)) {
				$store['advance_day'] = $row['advance_day'];
				if($row['advance_day'] > 0){
					$store['is_close'] = 0;
					$store['is_booking'] = true;
				}else{
					$store['is_close'] = 1;
					$store['close_reason'] = '营业时间: '.$store['time'];
				}
			}
		}
		
		if (M('Classify')->field(true)->where(array('token' => $row['mer_id']))->find()) {
            $store['home_url'] = $this->config['site_url'].'/wap.php?g=Wap&c=Index&a=index&token='.$row['mer_id'];
        } else {
            $store['home_url'] = '';
        }
		
		$store['reply_deliver_count'] = $row['reply_deliver_count'];
		$store['reply_deliver_score'] = $row['reply_deliver_score'];
		$store['reply_count'] = $row['reply_count'];
		$store['store_live_url'] = $row['store_live_url'];
		$store['name'] = $row['name'];
		$store['store_notice'] = $row['store_notice'];
		$store['txt_info'] = $row['txt_info'];
		$store['auth_files'] = $auth_files;
		$store['star'] = $row['score_mean'];
		if($store['star'] == '0.0'){
			$store['star'] = '3.5';
		}
		$store['month_sale_count'] = $row['sale_count'];
		$store['is_new'] = ($row['create_time'] + 864000) < time() ? 0 : 1;
		$store['delivery'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送
// 		$store['delivery_time'] = $row['send_time'];

		$store['goods_count'] = S('store_shop_goods_count'.$store['store_id']);
		if(!$store['store_shop_goods_count']){
			$store['goods_count'] = M('Shop_goods')->where(array('store_id'=>$store['store_id'],'status'=>1))->count();
			S('store_shop_goods_count'.$store['store_id'],$store['goods_count'],86400);
		}
		
		//店铺小程序二维码
		$store['wxapp_qrcode'] = $this->config['site_url'].'/index.php?c=Recognition_wxapp&a=create_unlimit_qrcode&page=pages/shop_new/shopDetail/shopDetail&scene='.urlencode('store_id='.$store['store_id']);
		
		
		//配送时长
		if ($row['send_time_type'] == 0) {
		    $store['delivery_time'] = $row['sort_time'];
		} elseif ($row['send_time_type'] == 1) {
		    $store['delivery_time'] = floatval(round($row['sort_time'] / 60, 2));
		} elseif ($row['send_time_type'] == 2) {
		    $store['delivery_time'] = floatval(round($row['sort_time'] / 1440, 2));
		} elseif ($row['send_time_type'] == 3) {
		    $store['delivery_time'] = floatval(round($row['sort_time'] / 10080, 2));
		} elseif ($row['send_time_type'] == 4) {
		    $store['delivery_time'] = floatval(round($row['sort_time'] / 43200, 2));
		}
		
		$store['send_time_type'] = $row['send_time_type'];
		$store['delivery_time_type'] = $this->send_time_type[$row['send_time_type']];
		$store['delivery_price'] = floatval($row['basic_price']);//起送价
		
		//门店专题、爆款橱窗等信息
		$store['shop_fitment_color'] = $row['shop_fitment_color'] ? $row['shop_fitment_color'] : '113533';
		$store['shop_subject_show'] = $row['shop_subject_show'];
		$store['shop_showcase_show'] = $row['shop_showcase_show'];
		$store['shop_brand_weipage'] = $row['shop_brand_weipage'];
		if($row['shop_brand_weipage']){
			$store['shop_brand_weipage_url'] = $this->config['site_url'].'/wap.php?c=Diypage&a=page&page_id='.$store['shop_brand_weipage'];
		}else{
			$store['shop_brand_weipage_url'] = '';
		}
		
		//配送时间
		
		$deliverReturn = D('Deliver_set')->getDeliverInfo($row);
		$delivery_fee = $deliverReturn['delivery_fee'];
		$per_km_price = $deliverReturn['per_km_price'];
		$basic_distance = $deliverReturn['basic_distance'];
		
		$delivery_fee2 = $deliverReturn['delivery_fee2'];
		$per_km_price2 = $deliverReturn['per_km_price2'];
		$basic_distance2 = $deliverReturn['basic_distance2'];
		
		$delivery_fee3 = $deliverReturn['delivery_fee3'];
		$per_km_price3 = $deliverReturn['per_km_price3'];
		$basic_distance3 = $deliverReturn['basic_distance3'];
		
		$start_time = $deliverReturn['delivertime_start'];
		$stop_time = $deliverReturn['delivertime_stop'];
		
		$start_time2 = $deliverReturn['delivertime_start2'];
		$stop_time2 = $deliverReturn['delivertime_stop2'];
		
		$start_time3 = $deliverReturn['delivertime_start3'];
		$stop_time3 = $deliverReturn['delivertime_stop3'];
		
		$store['delivery_money'] = $delivery_fee;
		
		$time = time();
        $selectDeliverTime = 0;
        if (! ($start_time == $stop_time && $start_time == '00:00:00')) {
            $start_time = strtotime(date('Y-m-d') . ' ' . $start_time);
            $stop_time = strtotime(date('Y-m-d') . ' ' . $stop_time);
            if ($start_time > $stop_time) {
                if (strtotime(date('Y-m-d')) <= $time && $time <= $stop_time) {
                    $selectDeliverTime = 1;
                } else {
                    $stop_time2 += 86400;
                    if ($start_time <= $time && $time <= $stop_time) {
                        $selectDeliverTime = 1;
                    }
                }
            } elseif ($start_time <= $time && $time <= $stop_time) {
                $selectDeliverTime = 1;
            }
            if (! ($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
                $start_time2 = strtotime(date('Y-m-d') . ' ' . $start_time2);
                $stop_time2 = strtotime(date('Y-m-d') . ' ' . $stop_time2);

                if ($start_time2 > $stop_time2) {
                    if (strtotime(date('Y-m-d')) <= $time && $time <= $stop_time2) {
                        $store['delivery_money'] = $delivery_fee2;
                        $selectDeliverTime = 2;
                    } else {
                        $stop_time2 += 86400;
                        if ($start_time2 <= $time && $time <= $stop_time2) {
                            $store['delivery_money'] = $delivery_fee2;
                            $selectDeliverTime = 2;
                        }
                    }
                } elseif ($start_time2 <= $time && $time <= $stop_time2) {
                    $store['delivery_money'] = $delivery_fee2;
                    $selectDeliverTime = 2;
                }
            }
            if (! ($start_time3 == $stop_time3 && $start_time3 == '00:00:00')) {
                $start_time3 = strtotime(date('Y-m-d') . ' ' . $start_time3);
                $stop_time3 = strtotime(date('Y-m-d') . ' ' . $stop_time3);

                if ($start_time3 > $stop_time3) {
                    if (strtotime(date('Y-m-d')) <= $time && $time <= $stop_time3) {
                        $store['delivery_money'] = $delivery_fee3;
                        $selectDeliverTime = 3;
                    } else {
                        $stop_time3 += 86400;
                        if ($start_time3 <= $time && $time <= $stop_time3) {
                            $store['delivery_money'] = $delivery_fee3;
                            $selectDeliverTime = 3;
                        }
                    }
                } elseif ($start_time3 <= $time && $time <= $stop_time3) {
                    $store['delivery_money'] = $delivery_fee3;
                    $selectDeliverTime = 3;
                }
            }
        }else{
            $selectDeliverTime = 1;
        }


        $set = D('Deliver_set')->field(true)->where(array('area_id' => $row['area_id'], 'status' => 1))->find();
        if (empty($set)) {
            $set = D('Deliver_set')->field(true)->where(array('area_id' => $row['city_id'], 'status' => 1))->find();
            if (empty($set)) {
                $set = D('Deliver_set')->field(true)->where(array('area_id' => $row['province_id'], 'status' => 1))->find();
            }
        }
        if($selectDeliverTime==1){
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                if($row['s_is_open_own']==1){
                    $store['delivery_price'] = floatval($row['s_basic_price1']) ? floatval($row['s_basic_price1']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) :(floatval($set['basic_price1']) ? floatval($set['basic_price1']): (floatval($this->config['basic_price1']) ? floatval($this->config['basic_price1']):($this->config['basic_price'] ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                }else{
                    $store['delivery_price'] = floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) :(floatval($set['basic_price1']) ? floatval($set['basic_price1']): (floatval($this->config['basic_price1']) ? floatval($this->config['basic_price1']):($this->config['basic_price'] ? floatval($this->config['basic_price']) : floatval($row['basic_price']))));//起送价
                }
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price1'])?floatval($row['basic_price1']):floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        }elseif($selectDeliverTime==2){
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                if($row['s_is_open_own']==1) {
                    $store['delivery_price'] = floatval($row['s_basic_price2']) ? floatval($row['s_basic_price2']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($set['basic_price2']) ? floatval($set['basic_price2']) : (floatval($this->config['basic_price2']) ? floatval($this->config['basic_price2']) : ($this->config['basic_price'] ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                }else{
                    $store['delivery_price'] = floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($set['basic_price2']) ? floatval($set['basic_price2']) : (floatval($this->config['basic_price2']) ? floatval($this->config['basic_price2']) : ($this->config['basic_price'] ? floatval($this->config['basic_price']) : floatval($row['basic_price']))));//起送价
                }
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price2'])?floatval($row['basic_price2']):floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }

        }elseif($selectDeliverTime==3){
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                if($row['s_is_open_own']==1) {
                    $store['delivery_price'] = floatval($row['s_basic_price3']) ? floatval($row['s_basic_price3']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($set['basic_price3']) ? floatval($set['basic_price3']) : (floatval($this->config['basic_price3']) ? floatval($this->config['basic_price3']) : ($this->config['basic_price'] ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                }else{
                    $store['delivery_price'] = floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($set['basic_price3']) ? floatval($set['basic_price3']) : (floatval($this->config['basic_price3']) ? floatval($this->config['basic_price3']) : ($this->config['basic_price'] ? floatval($this->config['basic_price']) : floatval($row['basic_price']))));//起送价
                }
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price3'])?floatval($row['basic_price3']):floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        }else{
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                $store['delivery_price'] = floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : ($this->config['basic_price'] ? floatval($this->config['basic_price']) : floatval($row['basic_price']));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        }

        if($row['s_is_open_virtual']==1){
            $store['delivery_money'] = floatval($row['virtual_delivery_fee']);
        }else{
            $store['delivery_money'] = floatval($store['delivery_money']);
        }
// 		$store['delivery_money'] = $row['deliver_type'] == 0 ? C('config.delivery_fee') : $row['delivery_fee'];//配送费
// 		$store['delivery_money'] = floatval($store['delivery_money']);//配送费
		$store['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
		if (in_array($row['deliver_type'], array(2, 3, 4))) {
			$store['pick'] = 1;//是否支持自提
		} else {
			$store['pick'] = 0;//是否支持自提
		}
		$store['pack_alias'] = $row['pack_alias'];//打包费别名
		$store['freight_alias'] = $row['freight_alias'];//运费别名
		$store['coupon_list'] = array();
		if ($row['is_invoice']) {
			$store['coupon_list']['invoice'] = floatval($row['invoice_price']);
		}
		if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
			$store['coupon_list']['discount'] = floatval(round($row['store_discount']/10, 2));
		}
		$system_delivery = array();
		if (isset($discounts[0]) && $discounts[0]) {
			foreach ($discounts[0] as $row_d) {
				if ($row_d['type'] == 0) {//新单
					$store['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 1) {//满减
					$store['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 2) {//配送
					$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				}
			}
		}
		if (isset($discounts[$store_id]) && $discounts[$store_id]) {
			foreach ($discounts[$store_id] as $row_m) {
				if ($row_m['type'] == 0) {
					$store['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				} elseif ($row_m['type'] == 1) {
					$store['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				}
			}
		}
        if ($store['delivery']) {
            if ($store['delivery_system']) {
                $system_delivery && $store['coupon_list']['delivery'] = $system_delivery;
            } else {
                if ($selectDeliverTime == 1) {
                    if ($row['reach_delivery_fee_type'] == 0) {
                        if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
                            $store['coupon_list']['delivery'][] = array(
                                'money' => floatval($row['basic_price']),
                                'minus' => floatval($row['delivery_fee'])
                            );
                        }
                    } elseif ($row['reach_delivery_fee_type'] == 1) {
                        // $store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                    } else {
                        $row['delivery_fee'] && $store['coupon_list']['delivery'][] = array(
                            'money' => floatval($row['no_delivery_fee_value']),
                            'minus' => floatval($row['delivery_fee'])
                        );
                    }
                } elseif ($selectDeliverTime == 2) {
                    if ($row['reach_delivery_fee_type2'] == 0) {
                        if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
                            $store['coupon_list']['delivery'][] = array(
                                'money' => floatval($row['basic_price']),
                                'minus' => floatval($row['delivery_fee2'])
                            );
                        }
                    } elseif ($row['reach_delivery_fee_type2'] == 1) {
                        // $store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                    } else {
                        $row['delivery_fee2'] && $store['coupon_list']['delivery'][] = array(
                            'money' => floatval($row['no_delivery_fee_value2']),
                            'minus' => floatval($row['delivery_fee2'])
                        );
                    }
                } elseif ($selectDeliverTime == 3) {
                    if ($row['reach_delivery_fee_type3'] == 0) {
                        if ($row['basic_price'] > 0 && $row['delivery_fee3'] > 0) {
                            $store['coupon_list']['delivery'][] = array(
                                'money' => floatval($row['basic_price']),
                                'minus' => floatval($row['delivery_fee3'])
                            );
                        }
                    } elseif ($row['reach_delivery_fee_type3'] == 1) {
                        // $store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                    } else {
                        $row['delivery_fee3'] && $store['coupon_list']['delivery'][] = array(
                            'money' => floatval($row['no_delivery_fee_value3']),
                            'minus' => floatval($row['delivery_fee3'])
                        );
                    }
                }
            }
        }
		$store['coupon_list'] = $this->parseCoupon($store['coupon_list'],'array');
		
		
		//店铺优惠券
		$now_mer = M('Merchant_store')->where(array('store_id'=>$store['store_id']))->find();
		$where = array('mer_id'=>$now_mer['mer_id'],'status'=>array('gt',0),'end_time'=>array('gt',time()),'mer_id'=>$now_mer['mer_id'],'send_type'=>array('neq',1));
		$where['_string'] = "cate_name='shop' OR cate_name='all'";
		$coupont_list =  M('Card_new_coupon')->where($where)
						->order('status ASC,allow_new DESC,discount DESC')
						->field('coupon_id,name,img,had_pull,num,limit,des,cate_name,cate_id,discount,order_money,start_time,end_time,status,allow_new,store_id')->select();
						
		$category = array(
				'all' => '通用券',
				'group' => C('config.group_alias_name'),
				'meal' => C('config.meal_alias_name'),
				'appoint' => C('config.appoint_alias_name'),
				'shop' => C('config.shop_alias_name'),
				'store' => '优惠买单',
		);
		$new_coupon = array();
		$old_num=  0;

		foreach ($coupont_list as $k=>&$v) {
			$is_new_coupon = false;

			$store_ids = explode(',',$v['store_id']);
	
			if(!empty($store_ids) && !in_array($store['store_id'],$store_ids)){
				continue;
			}
			
			if(strpos($v['img'],'http')===false){
				$v['img']=  $this->config['site_url'].$v['img'];
			}
			$v['effective_days'] ='有效期'.intval(($v['end_time']-$v['start_time'])/86400).'天';
			$v['start_time'] = date('Y.m.d',$v['start_time']);
			$v['end_time'] = date('Y.m.d',$v['end_time']);

			$v['platform'] = unserialize($v['platform']);
			if(!empty($v['platform'])&& !in_array('app',$v['platform'])){
				continue;
			}

			if(!empty($this->_uid)){
				$count = M('Card_new_coupon_hadpull')->where(array('coupon_id'=>$v['coupon_id'],'uid'=>$this->_uid))->count();

				if($count>0) {
					$v['limit'] = $count;
					$old_num++;
				}else if($v['status']==1 && $count==0){
					$is_new_coupon =  true;
				}
				if($count==0 && $v['status']!=1){
					continue;
				}


				if($count == 0 && $v['allow_new'] && !D('User')->check_new($this->_uid,'shop')){
					continue;
				}
			}

			$v['discount'] = floatval($v['discount']);
			$v['order_money'] = floatval($v['order_money']);

			$v['cate_id'] = unserialize($v['cate_id']);
			$v['cate_name']  = $category[$v['cate_name']];
			if(!empty($v['cate_id'])){
				$v['type_name']=$v['cate_id']['cat_name'];
			}
			unset($coupont_list[$k]['cate_id']);
			if($is_new_coupon){
				$new_coupon[] =  $v;
			}
			$tmp[]  =$v;
			
		}
		if(empty($tmp)){
			$tmp = array();
		}else{
			foreach($tmp as &$tmp_value){
				$tmp_value = array(
					'coupon_id' 	=> $tmp_value['coupon_id'],
					'order_money' 	=> getFormatNumber($tmp_value['order_money']),
					'discount' 		=> getFormatNumber($tmp_value['discount']),
					'end_time' 		=> $tmp_value['end_time'],
				);
			}
		}
		$store['ticket_list'] = $tmp;
		if(!empty($new_coupon)){
			$store['ticket_list'] = $new_coupon;
			$store['ticket_get_status'] = 0;
		}else{
			if($old_num==count($tmp)){	
				$store['ticket_get_status'] = 1;
			}else{
				$store['ticket_get_status'] = 0;
			}	
		}
		
		//三级分类返回分类目录结构，一级分类直接返回分类+商品
		if($store['openclassfyindex']){
			$store['category_list'] = $this->format_category_value(D('Shop_goods_sort')->lists($store['store_id']));
		}else{
			$store['product_list'] = D('Shop_goods')->getListByStoreId($store['store_id']);
			foreach($store['product_list'] as $value){
				if($value['son_list']){
					unset($store['product_list']);
					$store['openclassfyindex'] = 1;
					$store['category_list'] = $this->format_category_value(D('Shop_goods_sort')->lists($store['store_id']));
					break;
				}
			}
		}
/* 		if($store['category_list']){
			foreach($store['category_list'] as $key=>$value){
				$store['category_list'][$key] = array(
					'sort_id' 	=>  $value['sort_id'],
					'sort_name' =>  $value['sort_name'],
				);
			}
		} */
		
		
		//门店专题、爆款橱窗、品牌故事、特色分类
		$condition['store_id']	= $store['store_id'];
		$subject_info = M('Merchant_store_shop_data')->where($condition)->find();
		$store['shop_subject_pic'] = '';
		$store['shop_showcase'] = array('good_count'=>0);
		if($subject_info){
			//门店专题是否展示
			if($store['shop_subject_show'] && $subject_info['subject_pic']){
				$store['shop_subject_pic'] = $this->config['site_url'].'/'.$subject_info['subject_pic'];
			}
			//爆款橱窗是否展示
			if($store['shop_showcase_show']){
				$store['shop_showcase']['name']	= $subject_info['showcase_name'];
				$where_goods = array(
					'store_id'	=> $store['store_id'],
					'goods_ids'	=> explode(',',$subject_info['showcase_good_id']),
				);
				$good_list = D('Shop_goods')->get_list_by_option($where_goods,1);
				if(empty($good_list['goods_list'])){
					$good_list['goods_list'] = array();
				}else{
					$tempGoodList = array();
					foreach($good_list['goods_list'] as $tmp_good_value){
						$tempGoodList[$tmp_good_value['goods_id']] = $tmp_good_value;
					}
					$good_list['goods_list'] = array();
					foreach($where_goods['goods_ids'] as $goods_id){
						if($tempGoodList[$goods_id]){
							$good_list['goods_list'][] = $tempGoodList[$goods_id];
						}
					}
					
					foreach($good_list['goods_list'] as &$good_value){
						$good_value['product_sale'] = $good_value['sell_count'];
						$good_value['product_id']	= $good_value['goods_id'];
						$good_value['product_name']	= $good_value['name'];
						$good_value['product_image']= $good_value['image'];
						$good_value['product_price']= $good_value['price'];
						$good_value['has_format']   = ($good_value['spec_value'] || $good_value['is_properties']) ? true : false;
						$good_value['has_spec']   = $good_value['spec_value'] ? true : false;
					}
				}
				
				
				$store['shop_showcase']['good_list'] = $good_list['goods_list'];
				$store['shop_showcase']['good_count'] = count($good_list['goods_list']);
				if($store['shop_showcase']['good_count'] == 0){
					unset($store['shop_showcase']);
				}
			}
			//特殊分类-热销
			if($subject_info['cat_hot_status']){
				$store['cat_hot']['name']	= $subject_info['cat_hot_name'];
				$store['cat_hot']['desc']	= $subject_info['cat_hot_desc'];
				$where_goods = array(
					'store_id'	=> $store['store_id'],
					'goods_ids'	=> explode(',',$subject_info['cat_hot_good_id']),
				);
				$sort = 1;
				$sort_type = 1;
				switch($subject_info['cat_hot_sort']){
					case 1:		//价格从高到低排序
						$sort = 3;
						$sort_type = 1;
						break;
					case 2:		//价格从低到高排序
						$sort = 3;
						$sort_type = 0;
						break;
					case 3:		//销量从高到低排序
						$sort = 2;
						$sort_type = 1;
						break;
					case 4:		//销量从低到高排序
						$sort = 2;
						$sort_type = 0;
						break;
				}
				$good_list = D('Shop_goods')->get_list_by_option($where_goods,$sort,$sort_type);
				if(empty($good_list['goods_list'])){
					$good_list['goods_list'] = array();
				}else{
					foreach($good_list['goods_list'] as &$good_value){
						$good_value['product_sale'] = $good_value['sell_count'];
						$good_value['product_id']	= $good_value['goods_id'];
						$good_value['product_name']	= $good_value['name'];
						$good_value['product_image']= $good_value['image'];
						$good_value['product_price']= $good_value['price'];
						$good_value['has_format']   = ($good_value['spec_value'] || $good_value['is_properties']) ? true : false;
						$good_value['has_spec']   = $good_value['spec_value'] ? true : false;
					}
				}
				$store['cat_hot']['good_list'] = $good_list['goods_list'];
				$store['cat_hot']['good_count'] = count($good_list['goods_list']);
				if($store['cat_hot']['good_count'] == 0){
					unset($store['cat_hot']);
				}
			}
			//特殊分类-优惠
			if($subject_info['cat_discount_status']){
				$store['cat_discount']['name']	= $subject_info['cat_discount_name'];
				$store['cat_discount']['desc']	= $subject_info['cat_discount_desc'];
				$where_goods = array(
					'store_id'	=> $store['store_id'],
					'goods_ids'	=> explode(',',$subject_info['cat_discount_good_id']),
				);
				$sort = 1;
				$sort_type = 1;
				switch($subject_info['cat_discount_sort']){
					case 1:		//价格从高到低排序
						$sort = 3;
						$sort_type = 1;
						break;
					case 2:		//价格从低到高排序
						$sort = 3;
						$sort_type = 0;
						break;
					case 3:		//销量从高到低排序
						$sort = 2;
						$sort_type = 1;
						break;
					case 4:		//销量从低到高排序
						$sort = 2;
						$sort_type = 0;
						break;
				}
				$good_list = D('Shop_goods')->get_list_by_option($where_goods,$sort,$sort_type);
				if(empty($good_list['goods_list'])){
					$good_list['goods_list'] = array();
				}else{
					foreach($good_list['goods_list'] as &$good_value){
						$good_value['product_sale'] = $good_value['sell_count'];
						$good_value['product_id']	= $good_value['goods_id'];
						$good_value['product_name']	= $good_value['name'];
						$good_value['product_image']= $good_value['image'];
						$good_value['product_price']= $good_value['price'];
						$good_value['has_format']   = ($good_value['spec_value'] || $good_value['is_properties']) ? true : false;
						$good_value['has_spec']   = $good_value['spec_value'] ? true : false;
					}
				}
				$store['cat_discount']['good_list'] = $good_list['goods_list'];
				$store['cat_discount']['good_count'] = count($good_list['goods_list']);
				if($store['cat_discount']['good_count'] == 0){
					unset($store['cat_discount']);
				}
			}
		}
		
		$this->returnCodeOk($store);
	}
	//门店专题页面
	public function shop_subject(){
		$store_id = intval($_POST['store_id']);
		if(empty($store_id)){
			$this->returnCodeError('请携带 store_id 参数');
		}
		$condition['store_id']	= $store_id;
		$subject_info = M('Merchant_store_shop_data')->where($condition)->find();
		$returnArr['subject_name'] = $subject_info['subject_name'];
		$returnArr['subject_pic'] = $this->config['site_url'].'/'.$subject_info['subject_pic'];
		$where_goods = array(
			'store_id'	=> $store_id,
			'goods_ids'	=> explode(',',$subject_info['subject_good_id']),
			'page_size'	=> 20,
		);
		$good_list = D('Shop_goods')->get_list_by_option($where_goods,1);
		if(empty($good_list['goods_list'])){
			$good_list['goods_list'] = array();
		}else{
			$tempGoodList = array();
			foreach($good_list['goods_list'] as $tmp_good_value){
				$tempGoodList[$tmp_good_value['goods_id']] = $tmp_good_value;
			}
			$good_list['goods_list'] = array();
			foreach($where_goods['goods_ids'] as $goods_id){
				if($tempGoodList[$goods_id]){
					$good_list['goods_list'][] = $tempGoodList[$goods_id];
				}
			}
		}
		$returnArr['good_list'] = $good_list['goods_list'];
		$returnArr['good_count'] = count($good_list['goods_list']);
		
		foreach($returnArr['good_list'] as &$good_value){
			$good_value['product_sale'] = $good_value['sell_count'];
			$good_value['product_id']	= $good_value['goods_id'];
			$good_value['product_name']	= $good_value['name'];
			$good_value['product_image']= $good_value['image'];
			$good_value['product_price']= $good_value['price'];
			$good_value['has_format']   = ($good_value['spec_value'] || $good_value['is_properties']) ? true : false;
			$good_value['has_spec']   = $good_value['spec_value'] ? true : false;
		}
		
		$this->returnCodeOk($returnArr);
	}
	
	public function format_category_value($category_list){
		if($category_list){
			foreach($category_list as $key=>$value){
				if($value['son_list']){
					$category_list[$key]['son_list'] = $value['son_list'] = array_values($value['son_list']);
					
					foreach($value['son_list'] as $k=>$v){
						if($v['son_list']){
							$category_list[$key]['son_list'][$k]['son_list'] = array_values($v['son_list']);
						}
					}
				}
			}
			$category_list = array_values($category_list);
		}else{
			$category_list = array();
		}
		return $category_list;
	}
	/*
	 *
	 * 根据商品分类ID获取商品列表
	 *
	 */
	public function getGoodsBySortId(){
		$sort_id = $_POST['sort_id'];
		if(empty($sort_id)){
			$this->returnCodeError('请携带分类ID');
		}
		$store_id = $_POST['store_id'];
		if(empty($store_id)){
			$this->returnCodeError('请携带店铺ID');
		}
		$nowSort = D('Shop_goods')->getGoodsBySortIds(array($sort_id),$store_id);
		$productList = $nowSort[0]['goods_list'];
		foreach($productList as $key=>$value){
			$productList[$key] = array(
				'product_id' 			=> $value['goods_id'],			//商品ID
				'goods_id' 			=> $value['goods_id'],			//商品ID
				'name' 				=> $value['name'],				//商品名称
				'product_name' 		=> $value['name'],  //商品名称
				'unit' 				=> $value['unit'],				//商品单位
				'o_price' 			=> $value['o_price'],			//商品价格
				'price' 			=> $value['price'],				//商品价格
				'product_price' 	=> $value['price'],				//商品价格
				'is_seckill_price' 	=> $value['is_seckill_price'],	//是否在秒杀
				'seckill_discount' 	=> $value['o_price'] ? round($value['price']/$value['o_price']*10,1) : 10,//秒杀折扣
				'seckill_stock' 	=> $value['seckill_stock'],		//秒杀库存，-1代表无限
				'image' 			=> $value['image'],				//商品图片
				'product_image' 	=> $value['image'],				//商品图片
				'sell_count' 		=> $value['sell_count'],		//商品已售数量
				'stock_num' 		=> $value['stock_num'],			//商品库存
				'stock' 		=> $value['stock_num'],	//商品库存
				'today_sell_count' 	=> $value['today_sell_count'],	//商品今日销量
				'reply_count' 		=> $value['reply_count'],		//商品评论数量
				'packing_charge' 	=> $value['packing_charge'],	//商品打包费
				'number' 			=> $value['number'],			//商品条码数值
				'min_num' 			=> $value['min_num'],			//商品最少购买数量
				'max_num' 			=> $value['max_num'],				//商品最多购买数量
				'has_format' 		=> ($value['spec_value'] || $value['is_properties']) ? true : false,//商品是否有规格属性
				'has_spec'   		=> $value['spec_value'] ? true : false, //商品是否有规格
				'is_new' 			=> $value['is_new'],			//是否新品
				'product_sale' 		=> $value['sell_count'],		//商品销量
				'product_reply' 	=> $value['reply_count'],		//好评数
			);
		}
		
		$this->returnCodeOk($productList);
	}
	
	public function ajax_goods(){
		$goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 1;
		$now_goods = D('Shop_goods')->get_goods_by_id($goods_id);
		foreach($now_goods['spec_list'] as &$value){
			$value['list'] = array_values($value['list']);
		}
		
		if($now_goods['spec_list']){
			foreach($now_goods['spec_list'] as &$value){
				$value['id_'] = $value['id'];
				unset($value['id']);
				foreach($value['list'] as &$v){
					$v['id_'] = $v['id'];
					unset($v['id']);
				}
			}
			$now_goods['spec_list'] = array_values($now_goods['spec_list']);
		}else{
			$now_goods['spec_list'] = array();
		}
		
		if($now_goods['properties_list']){
			foreach($now_goods['properties_list'] as &$value){
				$value['id_'] = $value['id'];
				unset($value['id']);
			}
			$now_goods['properties_list'] = array_values($now_goods['properties_list']);
		}else{
			$now_goods['properties_list'] = array();
		}
		
		if($now_goods['list']){
			foreach($now_goods['list'] as &$value){
				if ($now_goods['is_seckill_price']) {
					$value['price'] = strval(floatval($value['seckill_price']));
				} else {
				    $value['price'] = strval(floatval($value['price']));
				}
				if($value['properties']){
					foreach($value['properties'] as &$v){
						$v['id_'] = $v['id'];
						unset($v['id']);
					}
				}else{
					$value['properties'] = array();
				}
			}
		}else{
			$now_goods['list'] = $this->getObj();
		}
		$now_goods['content'] = '';
		if ($now_goods['des']) {
			if($_POST['Device-Id'] != 'wxapp'){
				$src = '<img src="'.C('config.site_url').'/';
				$now_goods['des'] = str_replace('<img src="/', $src, $now_goods['des']);
				$now_goods['des'] = str_replace('<img alt="" src="/', $src, $now_goods['des']);
			}else{
				$src = '<img style="max-width:100%;" src="'.C('config.site_url').'/';
				$now_goods['des'] = str_replace('<img src="/', $src, $now_goods['des']);
				$now_goods['des'] = str_replace('<img src="'.$this->config['site_url'].'/', $src, $now_goods['des']);
				$now_goods['des'] = str_replace('<img src="'.str_replace('https://','http://',$this->config['site_url']).'/', $src, $now_goods['des']);
			}
		}
		
		$now_goods['share_title'] = $now_goods['name'];
		$now_goods['share_content'] = $now_goods['name'];
		$now_goods['share_image'] = $now_goods['pic_arr'][0]['url'];
		$store = D('Merchant_store_shop')->field('store_id, store_theme, is_mult_class')->where(array('store_id' => $now_goods['store_id']))->find();
		if ($store['store_theme'] && empty($store['is_mult_class'])) {
		    $now_goods['share_url'] = $this->config['site_url'] . 'wap.php?c=Shop&a=classic_shop&shop_id=' . $store['store_id'] . '&goods_id=' . $now_goods['goods_id'];
		} else {
		    $now_goods['share_url'] = $this->config['site_url'] . '/wap.php?g=Wap&c=Shop&a=index#shop-' . $store['store_id'] . '-' . $now_goods['goods_id'];
		}
		
		$store['shop_fitment_color'] = $store['shop_fitment_color'] ? $store['shop_fitment_color'] : '113533';			//背景色
		$store['shop_fitment_front_color'] = 'ffffff';		//标题色

		//商品小程序二维码
		$now_goods['wxapp_qrcode'] = $this->config['site_url'].'/index.php?c=Recognition_wxapp&a=create_unlimit_qrcode&page=pages/shop_new/goodsDetail/goodsDetail&scene='.urlencode('product_id='.$now_goods['goods_id'].'&store_id='.$now_goods['store_id']);
		
		if($now_goods['seckill_stock'] && $now_goods['stock_num']){
			$now_goods['seckill_stock'] = $now_goods['stock_num'];
		}
		
		unset($now_goods['old_price']);
		unset($now_goods['seckill_open_time']);
		unset($now_goods['seckill_close_time']);
		unset($now_goods['last_time']);
		unset($now_goods['sort']);
		unset($now_goods['status']);
		unset($now_goods['print_id']);
		unset($now_goods['sell_day']);
		unset($now_goods['spec_value']);
		unset($now_goods['is_properties']);
		unset($now_goods['today_sell_spec']);
		unset($now_goods['today_seckill_count']);
		unset($now_goods['cat_fid']);
		unset($now_goods['cat_id']);
		unset($now_goods['freight_type']);
		unset($now_goods['freight_value']);
		unset($now_goods['freight_template']);
		unset($now_goods['extra_pay_price']);
		unset($now_goods['cost_price']);
		unset($now_goods['original_goods_id']);
		unset($now_goods['relation_map']);
		unset($now_goods['original_stock']);
		unset($now_goods['score_percent']);
		unset($now_goods['score_max']);
		unset($now_goods['zbwId']);
		unset($now_goods['show_start_time']);
		unset($now_goods['show_end_time']);
		unset($now_goods['limit_type']);
		unset($now_goods['json']);
		unset($now_goods['val_status']);
		unset($now_goods['properties_status_list']);
		unset($now_goods['deliver_fee']);
		$now_goods['seckill_discount'] = $now_goods['o_price'] ? round($now_goods['price']/$now_goods['o_price']*10,1) : 10;
		$this->returnCodeOk($now_goods);
	}
	public function near_offline_shop(){
		if(empty($_POST['lng']) || empty($_POST['lat'])){
			$this->returnCode(1001,array(),'请携带用户经纬度');
		}
		
		$lat = isset($_POST['lat']) ? htmlspecialchars($_POST['lat']) : 0;
		$long = isset($_POST['lng']) ? htmlspecialchars($_POST['lng']) : 0;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$page = max(1, $page);
		$key = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
		
		$where = array('deliver_type' => 'offline', 'order' => 'only_juli', 'lat' => $lat, 'long' => $long,'page' => $page,'key'=>$key,'show_method'=>0);
		$merchantStoreShopDB = D('Merchant_store_shop');
 
        $lists = $merchantStoreShopDB->get_list_by_option($where,1);
		
		if($_POST['only_one']){
			if($lists['shop_list']){
				$only_one_shop = $lists['shop_list'][0];
				$only_one_shop = array(
					'store_id' 	=> $only_one_shop['store_id'],
					'name' 		=> $only_one_shop['name'],
					'adress' 	=> $only_one_shop['adress'],
					'range' 	=> $only_one_shop['range'],
					'image' 	=> $only_one_shop['image'],
				);
			}else{
				$only_one_shop = (object)array();
			}
			$this->returnCodeOk($only_one_shop);
		}else{
			if($lists['shop_list']){
				foreach($lists['shop_list'] as &$shop_value){
					$shop_value = array(
						'store_id' 	=> $shop_value['store_id'],
						'name' 		=> $shop_value['name'],
						'adress' 	=> $shop_value['adress'],
						'range' 	=> $shop_value['range'],
						'image' 	=> $shop_value['image'],
					);
				}
			}
			
			$this->returnCodeOk($lists);
		}
	}
	public function shop_search(){
		$store_id = intval($_POST['store_id']);
		if(empty($store_id)){
			$this->returnCodeError('请携带 store_id 参数');
		}
		$page = intval($_POST['page']);
		if(empty($page)){
			$page = 1;
		}
		
		$key = safeValue($_POST['key']);
		$where_goods = array(
			'store_id'	=> $store_id,
			'key'		=> $key,
			'page'		=> $page,
		);
		$good_list = D('Shop_goods')->get_list_by_option($where_goods,1);
		
		foreach($good_list['goods_list'] as &$good_value){
			$good_value['product_sale'] = $good_value['sell_count'];
			$good_value['product_id']	= $good_value['goods_id'];
			$good_value['product_name']	= $good_value['name'];
			$good_value['product_image']= $good_value['image'];
			$good_value['product_price']= $good_value['price'];
			$good_value['stock']= $good_value['stock_num'];
			$good_value['has_format']   = ($good_value['spec_value'] || $good_value['is_properties']) ? true : false;
			$good_value['has_spec']   = $good_value['spec_value'] ? true : false;
		}
		
		$this->returnCodeOk($good_list);
	}
	public function shop_barcode_search(){
		$store_id = intval($_POST['store_id']);
		$barcode = safeValue($_POST['barcode']);
		if(empty($store_id)){
			$this->returnCodeError('请携带 store_id 参数');
		}
		if(empty($barcode)){
			$this->returnCodeError('条形码为空');
		}
		
		$is_refresh = isset($_POST['refresh']) ? intval($_POST['refresh']) : 1;
		$product_list = D('Shop_goods')->get_list($store_id, $is_refresh);
		
		foreach($product_list as $key=>$value){
			foreach($value['goods_list'] as $k=>$v){
				if($v['number'] == $barcode){
					if($v['spec_arr']){
						foreach($v['spec_arr'] as $kk=>$vv){
							if($vv['spec_val_id']){
								unset($v['spec_arr'][$kk]);
								$v['spec_arr'][$kk] = array(
									'type' => 'spec',
									'name' => $vv['spec_val_name'],
									'spec_id' => $vv['spec_val_sid'],
									'id' => $vv['spec_val_id'],
								);
							}
						}
					}
					$this->returnCodeOk($v);
				}
			}
		}
		
		$this->returnCodeOk(array());
	}
	public function merchant_card(){
		$store_id = intval($_POST['store_id']);
		if(empty($store_id)){
			$this->returnCodeError('请携带 store_id 参数');
		}
		
		$condition_merchant_store['store_id'] = $_POST['store_id'];
        $now_store = M('Merchant_store')->field(true)->where($condition_merchant_store)->find();
		if(empty($now_store)){
			$this->returnCodeError('店铺不存在');
		}
		
		//查找会员卡
		$merchant_card = M('Card_new')->where(array('mer_id'=>$now_store['mer_id']))->find();
		
		$returnArr = array(
			'has_card' => false
		);
		if(empty($merchant_card) || empty($merchant_card['status'])){
			$this->returnCodeOk($returnArr);
		}
		$returnArr['has_card'] = true;
		$returnArr['card_url'] = $this->config['site_url'].'/wap.php?c=My_card&a=merchant_card&mer_id='.$now_store['mer_id'];
		
		if($this->_uid){
			$card = D('Card_userlist')->field(true)->where(array('uid' => $this->_uid, 'mer_id' => $now_store['mer_id']))->find();
			if(!empty($card)){
				$user = D('User')->field(true)->where(array('uid' => $card['uid']))->find();
				$user_card = array('name' => $user['truename'] ? $user['truename'] : $user['nickname'], 'sex' => $user['sex'] == 1 ? '男' : '女', 'card_id' => $card['id'], 'phone' => $user['phone']);
				$user_card['card_money'] = $card['card_money'] + $card['card_money_give'];
				$user_card['card_score'] = $card['card_score'];
				$user_card['physical_id'] = $card['physical_id'];
				$user_card['uid'] = $user['uid'];
				$user_card['discount'] = empty($merchant_card['discount']) ? 10 : $merchant_card['discount'];

				$user_card['card_new'] = D('Card_new')->get_use_coupon_by_params($user['uid'], $now_store['mer_id'], 'shop');
				$returnArr['card_info'] = $user_card;
			}
		}
		$this->returnCode(0,$returnArr);
	}
}
?>