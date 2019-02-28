<?php
class MallAction extends BaseAction
{
	protected $leveloff = '';
	public function index()
	{
	    $_SESSION['session_store_id'] = 0;
		$category_list = D('Goods_category')->get_list();
		$this->assign('cat_fid', isset($category_list[0]['id']) ? $category_list[0]['id'] : 0);
		if( $this->user_session['uid'] && $this->config['open_rand_send']){
			$coupon_html = D('System_coupon')->rand_send_coupon_get(array('time'=>$_SERVER['REQUEST_TIME'],'uid'=>$this->user_session['uid']));
			$coupon_html && $this->assign('coupon_html',$coupon_html);
		}
		$this->display();
	}
	
	public function ajax_index()
	{
		$return = array();
		$return['banner_list'] = D('Adver')->get_adver_by_key('wap_mall_index_top', 5);
		$return['slider_list'] = D('Slider')->get_slider_by_key('wap_mall_slider', 0);
		$return['category_list'] = D('Goods_category')->get_list();
		
		
		$where = array();
		$where['cat_fid'] = $return['category_list'][0]['id'];
		$where['page'] = 1;
		
		
		$return['goods_list'] = D('Shop_goods')->get_list_by_option($where, 1, 1);
		
		$_SESSION['session_store_id'] = 0;
		echo json_encode($return);
	}
	
	public function category()
	{
		$category_list = D('Goods_category')->get_list();
		$this->assign('category_list', $category_list);
		$this->display();
	}
	
	public function goods_list()
	{
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
		$properties = D('Goods_properties')->field(true)->where(array('cat_id' => $cat_id, 'status' => 1))->select();
		$pids = array();
		$names = array();
		foreach ($properties as $pro) {
			$pids[] = $pro['id'];
			$names[] = $pro['name'];
		}
		$names = implode(',', $names);
		$value_list = array();
		if ($pids) {
			$temp_list = D('Goods_properties_value')->field(true)->where(array('pid' => array('in', $pids)))->select();
			foreach ($temp_list as $val) {
				$value_list[$val['pid']][] = $val;
			}
		}
		foreach ($properties as &$vo) {
			if (isset($value_list[$vo['id']])) {
				$vo['value_list'] = $value_list[$vo['id']];
			} else {
				$vo['value_list'] = null;
			}
		}
		$title = '';
		if ($category = D('Goods_category')->field(true)->where(array('id' => $cat_fid))->find()) {
			$title = $category['name'];
		}
		if ($category = D('Goods_category')->field(true)->where(array('id' => $cat_id))->find()) {
			$title = $title ? $title . '-' . $category['name'] : $category['name'];
		}
		$this->assign(array('cat_fid' => $cat_fid, 'cat_id' => $cat_id));
		$this->assign('properties', $properties);
		$this->assign('title', $title);
		$this->assign('names', $names);
		$this->display();
	}
	
	public function ajax_list()
	{
		$where = array();
		$where['cat_fid'] = isset($_POST['cat_fid']) ? intval($_POST['cat_fid']) : 0;
		$where['cat_id'] = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
		$where['store_id'] = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		$where['page'] = isset($_POST['page']) ? intval($_POST['page']) : 0;
		
		$pids1 = isset($_POST['pids1']) ? trim(htmlspecialchars($_POST['pids1'])) : '';
		$pids2 = isset($_POST['pids2']) ? trim(htmlspecialchars($_POST['pids2'])) : '';
		$pids3 = isset($_POST['pids3']) ? trim(htmlspecialchars($_POST['pids3'])) : '';
		$pids4 = isset($_POST['pids4']) ? trim(htmlspecialchars($_POST['pids4'])) : '';
		$where['key'] = isset($_POST['key']) ? trim(htmlspecialchars($_POST['key'])) : '';
		$search_type = isset($_POST['search_type']) ? intval($_POST['search_type']) : 0;
		$sort = isset($_POST['sort']) ? intval($_POST['sort']) : 1;//排序字段(1:goods_id, 2:sell_count, 3:price)
		if (!in_array($sort, array(1, 2, 3))) $sort = 1;
		$sort_type = isset($_POST['sort_type']) ? intval($_POST['sort_type']) : 1;//排序方式(1:DESC, 2:ASC)
		if ($sort_type != 1 && $sort_type != 2) $sort_type = 1;
		
		$where['pids'] = null;
		if ($pids1) $where['pids'][] = $pids1;
		if ($pids2) $where['pids'][] = $pids2;
		if ($pids3) $where['pids'][] = $pids3;
		if ($pids4) $where['pids'][] = $pids4;
		
		if ($search_type == 0) {
			$return = D('Shop_goods')->get_list_by_option($where, $sort, $sort_type);
		} else {
			$return = D('Merchant_store_shop')->get_store_by_search($where);
		}
		exit(json_encode($return));
	}
	
	public function detail()
	{
		$goods_id = intval($_GET['goods_id']);
		if(empty($goods_id)){
			$this->error_tips('商品不存在！');
		}
		$database_shop_goods = D('Shop_goods');
		$now_goods = $database_shop_goods->get_goods_by_id($goods_id);
		if(empty($now_goods)){
			$this->error_tips('商品不存在！');
		}
		
		//如果设置了最小起购，限购就无效
		if ($now_goods['min_num'] > 1) {
		    $now_goods['max_num'] = 0;
		}
		
		$store_id = $now_goods['store_id'];
		
		$where = array('store_id' => $store_id);
		$now_store = D('Merchant_store')->field(true)->where($where)->find();
		//资质认证
		// if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
			// $this->error_tips('店铺没有通过资质认证');
		// }
		$now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
		if (empty($now_shop) || empty($now_store)) {
			$this->error_tips('该店铺没有完善店铺信息');
		}
		$city_name = $province_name = '';
		$areas = D('Area')->field(true)->where(array('area_id' => array('in', array($now_store['province_id'], $now_store['city_id']))))->select();
		foreach ($areas as $area) {
			if ($area['area_pid']) {
				$city_name = $area['area_name'];
			} else {
				$province_name = $area['area_name'];
			}
		}
// 		$discounts = D('Shop_discount')->get_discount_byids(array($store_id));
		$discounts = D('Shop_discount')->getDiscounts($now_store['mer_id'], $store_id);
		$row = array_merge($now_store, $now_shop);
		
		$store = array();
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($row['pic_info']);
		
		$store['store_id'] = $row['store_id'];
		$store['phone'] = $row['phone'];
		$store['long'] = $row['long'];
		$store['lat'] = $row['lat'];
		$store['store_theme'] = $row['store_theme'];
		$store['adress'] = $row['adress'];
		$store['now_city_name'] = $province_name . ' ' . $city_name;
		$store['is_close'] = 1;
		$now_time = date('H:i:s');
		if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
			$store['time'] = '24小时营业';
			$store['is_close'] = 0;
		} else {
			$store['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
			if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
				$store['is_close'] = 0;
			}
			if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
				$store['time'] .= ',' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
				if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
					$store['is_close'] = 0;
				}
			}
			if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
				$store['time'] .= ',' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
				if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
					$store['is_close'] = 0;
				}
			}
		}
		
		$store['name'] = $row['name'];
		$store['store_notice'] = $row['store_notice'];
		$store['txt_info'] = $row['txt_info'];
		$store['image'] = isset($images[0]) ? $images[0] : '';
		$store['star'] = $row['score_mean'];
		$store['month_sale_count'] = $row['sale_count'];
		$store['delivery'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送
		$store['delivery_time'] = $row['send_time'];//配送时长
		$store['delivery_price'] = floatval($row['basic_price']);//起送价
		

		$store['pack_alias'] = $row['pack_alias'];//打包费别名
		$store['freight_alias'] = $row['freight_alias'];//运费别名
		$store['coupon_list'] = array();
// 		if ($row['is_invoice']) {
// 			$store['coupon_list']['invoice'] = floatval($row['invoice_price']);
// 		}
		if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
			$store['coupon_list']['discount'] = $row['store_discount']/10;
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
				if ($is_have_two_time) {
					if ($row['reach_delivery_fee_type2'] == 0) {
						if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
							$store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
						}
					} elseif ($row['reach_delivery_fee_type'] == 1) {
						//$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
					} else {
						$row['delivery_fee2'] && $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
					}
				} else {
					if ($row['reach_delivery_fee_type'] == 0) {
						if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
							$store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
						}
					} elseif ($row['reach_delivery_fee_type'] == 1) {
						//$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
					} else {
						$row['delivery_fee'] && $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
					}
				}
			}
		}
		$this->assign('goods_count', D('Shop_goods')->where(array('store_id' => $store_id, 'status' => 1))->count());
		$store['phone'] = explode(' ', $store['phone']);
		$this->assign('store', $store);
		$this->assign('goods_detail', $now_goods['list'] ? json_encode($now_goods['list']) : '');
		$this->assign('now_goods', $now_goods);
		if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $now_store['mer_id']))->select()) {
			$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
			$kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_store['mer_id'];
			$this->assign('kf_url', $kf_url);
		}
		$this->display();
	}
	
	public function store()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$sort_id = isset($_GET['sort_id']) ? intval($_GET['sort_id']) : 0;
		$where = array('store_id' => $store_id);
		$now_store = D('Merchant_store')->field(true)->where($where)->find();
		//资质认证
		if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
			$this->error_tips('您查看的'.$this->config['shop_alias_name'].'没有通过资质审核！');
			exit;
		}
		$now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
		if (empty($now_shop) || empty($now_store)) {
			$this->error_tips('店铺信息错误');
		}
		$otherwc = isset($_GET['otherwc']) ? intval($_GET['otherwc']) : 0;
		if($otherwc){
		    $_SESSION['session_store_id'] = $store_id;
		}
		/* 自定义首页开始 */
		if(empty($_GET['show_own'])){
			$database_diypage = M('Merchant_store_diypage');
			$condition_diypage = array('store_id'=>$now_store['store_id'],'is_home'=>'1','is_remove'=>'0');
			$home_diypage = $database_diypage->field('page_id')->where($condition_diypage)->find();
			if($home_diypage){
				redirect(U('Diypage/page',array('page_id'=>$home_diypage['page_id'])));
			}
		}
		/* 自定义首页结束 */
		
		
		if (!empty($now_shop['background'])) {
			$image_tmp = explode(',', $now_shop['background']);
			$now_shop['background'] = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
		}
		
// 		$discounts = D('Shop_discount')->get_discount_byids(array($store_id));
		$discounts = D('Shop_discount')->getDiscounts($now_store['mer_id'], $store_id);
		$row = array_merge($now_store, $now_shop);
		
		$store = array();
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($row['pic_info']);
	
		$store['id'] = $row['store_id'];
		
		$store['phone'] = $row['phone'];
		$store['background'] = $row['background'];
		$store['long'] = $row['long'];
		$store['lat'] = $row['lat'];
		$store['store_theme'] = $row['store_theme'];
		$store['adress'] = $row['adress'];
		$store['is_close'] = 1;
		$now_time = date('H:i:s');
		if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
			$store['time'] = '24小时营业';
			$store['is_close'] = 0;
		} else {
			$store['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
			if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
				$store['is_close'] = 0;
			}
			if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
				$store['time'] .= ',' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
				if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
					$store['is_close'] = 0;
				}
			}
			if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
				$store['time'] .= ',' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
				if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
					$store['is_close'] = 0;
				}
			}
		}
		
		$store['name'] = $row['name'];
		$store['store_notice'] = $row['store_notice'];
		$store['txt_info'] = $row['txt_info'];
		$store['image'] = isset($images[0]) ? $images[0] : ''; 
		$store['star'] = $row['score_mean'];
		$store['month_sale_count'] = $row['sale_count'];
		$store['delivery'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送
		$store['delivery_time'] = $row['send_time'];//配送时长
		$store['delivery_price'] = floatval($row['basic_price']);//起送价
		
		$is_have_two_time = 0;//是否是第二时段的配送显示
		
		if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
			if ($this->config['delivery_time']) {
				$delivery_times = explode('-', $this->config['delivery_time']);
				$start_time = $delivery_times[0] . ':00';
				$stop_time = $delivery_times[1] . ':00';
				if (!($start_time == $stop_time && $start_time == '00:00:00')) {
					if ($this->config['delivery_time2']) {
						$delivery_times2 = explode('-', $this->config['delivery_time2']);
						$start_time2 = $delivery_times2[0] . ':00';
						$stop_time2 = $delivery_times2[1] . ':00';
						if (!($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
							$is_have_two_time = 1;
						}
					}
				}
			}
			
			if ($is_have_two_time) {
				if ($now_time <= $stop_time || $now_time > $stop_time2) {
					$is_have_two_time = 0;
				} 
			}
			
			if ($row['s_is_open_own']) {
				if ($is_have_two_time) {
					$store['delivery_money'] = $row['s_free_type2'] == 0 ? 0 : $row['s_delivery_fee2'];
				} else {
					$store['delivery_money'] = $row['s_free_type'] == 0 ? 0 : $row['s_delivery_fee'];
				}
			} else {
				$store['delivery_money'] = $is_have_two_time ? C('config.delivery_fee2') : C('config.delivery_fee');
			}
		} else {
			if (!($row['delivertime_start'] == $row['delivertime_stop'] && $row['delivertime_start'] == '00:00:00')) {
				if (!($row['delivertime_start2'] == $row['delivertime_stop2'] && $row['delivertime_start2'] == '00:00:00')) {
					$is_have_two_time = 1;
				}
			}
			
			if ($is_have_two_time) {
				if ($now_time <= $row['delivertime_stop'] || $now_time > $row['delivertime_stop2']) {
					$is_have_two_time = 0;
				} 
			}
			
			$store['delivery_money'] = $is_have_two_time ? $row['delivery_fee2'] : $row['delivery_fee'];
		}
		$store['delivery_money'] = floatval($store['delivery_money']);
		
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
			$store['coupon_list']['discount'] = $row['store_discount']/10;
		}
		$system_delivery = array();
		
		$sys_newuser = '';
		$sys_minus  = '';
		$sys_delivery = '';
		if (isset($discounts[0]) && $discounts[0]) {
			foreach ($discounts[0] as $row_d) {
				if ($row_d['type'] == 0) {//新单
					$store['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
					$sys_newuser .= '满' . floatval($row_d['full_money']) . '元减' . floatval($row_d['reduce_money']) . '元,';
				} elseif ($row_d['type'] == 1) {//满减
					$store['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
					$sys_minus  .= '满' . floatval($row_d['full_money']) . '元减' . floatval($row_d['reduce_money']) . '元,';
				} elseif ($row_d['type'] == 2) {//配送
					$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
					$sys_delivery .= '满' . floatval($row_d['full_money']) . '元减' . floatval($row_d['reduce_money']) . '元,';
				}
			}
		}
		
		$mer_newuser = '';
		$mer_minus  = '';
		$mer_delivery = '';
		if (isset($discounts[$store_id]) && $discounts[$store_id]) {
			foreach ($discounts[$store_id] as $row_m) {
				if ($row_m['type'] == 0) {
					$store['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
					$mer_newuser .= '满' . floatval($row_m['full_money']) . '元减' . floatval($row_m['reduce_money']) . '元,';
				} elseif ($row_m['type'] == 1) {
					$store['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
					$mer_minus .= '满' . floatval($row_m['full_money']) . '元减' . floatval($row_m['reduce_money']) . '元,';
				}
			}
		}
		if ($store['delivery']) {
			if ($store['delivery_system']) {
				$system_delivery && $store['coupon_list']['delivery'] = $system_delivery;
			} else {
				$sys_delivery = '';
				if ($is_have_two_time) {
					if ($row['reach_delivery_fee_type2'] == 0) {
						if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
							$store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
							$mer_delivery .= '满' . floatval($row['basic_price']) . '元减' . floatval($row['delivery_fee2']) . '元,';
						}
					} elseif ($row['reach_delivery_fee_type'] == 1) {
						//$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
					} elseif ($row['delivery_fee2']) {
						 $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
						 $mer_delivery .= '满' . floatval($row['no_delivery_fee_value2']) . '元减' . floatval($row['delivery_fee2']) . '元,';
					}
				} else {
					if ($row['reach_delivery_fee_type'] == 0) {
						if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
							$store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
							$mer_delivery .= '满' . floatval($row['basic_price']) . '元减' . floatval($row['delivery_fee']) . '元,';
						}
					} elseif ($row['reach_delivery_fee_type'] == 1) {
						//$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
					} elseif ($row['delivery_fee']) {
						 $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
						 $mer_delivery .= '满' . floatval($row['no_delivery_fee_value']) . '元减' . floatval($row['delivery_fee']) . '元,';
					}
				}
			}
		}
		$store['txt_discount'] = array('sys_newuser' => rtrim($sys_newuser, ','), 'sys_minus' => rtrim($sys_minus, ','), 'sys_delivery' => rtrim($sys_delivery, ','), 'mer_newuser' => rtrim($mer_newuser, ','), 'mer_minus' => rtrim($mer_minus, ','), 'mer_delivery' => rtrim($mer_delivery, ','));
		
        $nowSort = array('sort_id' => 0, 'sort_name' => '分类');
        $s_list = D('Shop_goods_sort')->getAllChilds($store_id);
        foreach ($s_list as $value) {
            if ($value['sort_id'] == $sort_id) {
                $nowSort = array('sort_id' => $sort_id, 'sort_name' => $value['sort_name']);
            }
        }

        $this->assign('now_sort', $nowSort);
        $this->assign('sort_list', $s_list);
        $this->assign(array('store' => $store, 'goods_count' => D('Shop_goods')->where(array('store_id' => $store_id, 'status' => 1))->count()));
        $this->display();
	}
	
	
	public function ajax_goods()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$sort_id = isset($_POST['sort_id']) ? intval($_POST['sort_id']) : 0;
		$sort = isset($_POST['sort']) ? intval($_POST['sort']) : 1;
		$sort_type = isset($_POST['sort_type']) ? intval($_POST['sort_type']) : 1;
		
		$return = D('Shop_goods')->get_list_by_condition(array('store_id' => $store_id, 'sort_id' => $sort_id), $sort, $sort_type);
		exit(json_encode($return));
		if (empty($goods)) {
			exit(json_encode(array('goods_list' => null, 'count' => 0)));
		} else {
			exit(json_encode(array('goods_list' => $goods, 'count' => count($goods))));
		}
	}
	
	
	public function cart()
	{
// 		$carts = $_COOKIE['mall_goods_cart'];
// 		$carts = json_decode($carts, true);
		
		$carts = array();
		for ($i = 0; $i < 40; $i++) {
		    $tmpCookie = cookie('mall_goods_cart_' . $i);
		    if (!empty($tmpCookie)) {
		        $tmpArr = json_decode($tmpCookie, true);
		        if (empty($tmpArr)) {
		            $tmpArr = array();
		        }
		        $carts = array_merge($carts, $tmpArr);
		    } else {
		        break;
		    }
		}
		
		$goods_store = array();
		$goods_ids = array();
		$cart_list = array();
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : $_SESSION['session_store_id'];
		foreach ($carts as $cart) {
			$goods_store[$cart['productId']] = $cart['store_id'];
			$goods_ids[] = $cart['productId'];
		}
		if ($goods_ids) {
			$store_list = array();
			$s_list = D('Merchant_store')->field('name, store_id')->where(array('store_id' => array('in', $goods_store)))->select();
			foreach ($s_list as $s) {
			    if ($store_id && $store_id != $s['store_id']) continue;
				$store_list[$s['store_id']] = $s;
			}
			$goods_list = array();
			$g_list = D('Shop_goods')->field(true)->where(array('goods_id' => array('in', $goods_ids), 'status' => 1))->select();
			$goods_image_class = new goods_image();
			foreach ($g_list as $row) {
			    if ($store_id && $store_id != $row['store_id']) continue;
//				if($row['spec_value']!=''){
//					$row['extra_pay_price']=0;
//				}
				if ($row['seckill_type'] == 1) {
					$now_time = date('H:i');
					$open_time = date('H:i', $row['seckill_open_time']);
					$close_time = date('H:i', $row['seckill_close_time']);
				} else {
					$now_time = time();
					$open_time = $row['seckill_open_time'];
					$close_time = $row['seckill_close_time'];
				}
				$row['is_seckill_price'] = false;
				$row['o_price'] = floatval($row['price']);
				if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
					$row['price'] = floatval($row['seckill_price']);
					$row['is_seckill_price'] = true;
				} else {
					$row['price'] = floatval($row['price']);
				}
			
				$row['old_price'] = floatval($row['old_price']);
				$row['seckill_price'] = floatval($row['seckill_price']);
			
				$row['url'] = U('Mall/detail', array('goods_id' => $row['goods_id']), true, false, true);
				$tmp_pic_arr = explode(';', $row['image']);
				foreach ($tmp_pic_arr as $key => $value) {
					$temp_image = $goods_image_class->get_image_by_path($value);
					if ($temp_image) {
						$row['image'] = $temp_image['image'];
						break;
					}
				}
				$goods_list[$row['goods_id']] = $row;
			}
		}
		
		foreach ($carts as $vo) {
		    if ($store_id && $store_id != $vo['store_id']) continue;
			$index_key = 's_' . $vo['store_id'] . '_g_' . $vo['productId'];
			$name = $pre = '';
			if ($vo['productParam']) {
				foreach ($vo['productParam'] as $param) {
					if ($param['type'] == 'spec') {
						$index_key .= '_s_' . $param['id'];
						$name .= $pre . $param['name'];
						$pre = ','; 
					} else {
						foreach ($param['data'] as $d) {
							$index_key .= '_v_' . $d['id'];
							$name .= $pre . $d['name'];
							$pre = ','; 
						}
					}
				}
			}
			
			$goods_list[$vo['productId']]['index_key'] = $index_key;
// 			$goods_list[$vo['productId']]['price'] = $vo['productPrice'];
			$goods_list[$vo['productId']]['num'] = $vo['count'];
			if ($name) {
				$goods_list[$vo['productId']]['name'] = $vo['productName'] . '(' . $name . ')';
			}
			$goods_list[$vo['productId']]['price'] = $vo['productPrice'];
			$total_price = $vo['count'] * $goods_list[$vo['productId']]['price'];
			if (isset($store_list[$vo['store_id']]['goods_list'])) {
				$store_list[$vo['store_id']]['total_price'] += $total_price;
				$store_list[$vo['store_id']]['goods_list'][] = $goods_list[$vo['productId']];
			} else {
				$store_list[$vo['store_id']]['total_price'] = $total_price;
				$store_list[$vo['store_id']]['goods_list'] = array($goods_list[$vo['productId']]);
			}
		}

		$this->assign('product_list', $store_list);
		$this->assign('store_id', $store_id);
		$this->display();
	}
	
	public function reply()
	{
		$goods_id = isset($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
		$store_shop = array();
		if ($goods = D('Shop_goods')->field(true)->where(array('goods_id' => $goods_id))->find()) {
			$store = D('Merchant_store')->field(true)->where(array('store_id' => $goods['store_id']))->find();
			$store_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $goods['store_id']))->find();
			$store = array_merge($store, $store_shop);
			$this->assign('store', $store);
		} else {
			$this->error_tips('商品不存在！');
		}
		$this->display();
	}
	
	public function search()
	{
		$keyword = isset($_GET['key']) ? trim(htmlspecialchars($_GET['key'])) : '';
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$this->assign('store_id', $store_id);
		$this->assign('keyword', $keyword);
		$this->display();
	}
	
	
	public function addressinfo()
	{
		$now_store = D('Merchant_store')->get_store_by_storeId($_GET['store_id']);
		if(empty($now_store)){
			$this->error_tips('该店铺不存在！');
		}
		$this->assign('now_store',$now_store);

		$this->display();
	}
	
	public function confirm_order()
	{
		//delivery_type 0:平台配送，1：商家配送，2：自提，3:平台配送或自提，4：商家配送或自提,5:邮递，6：邮递或自提

		$address_id = isset($_GET['adress_id']) ? intval($_GET['adress_id']) : cookie('userLocationId');
		$user_adress = D('User_adress')->get_one_adress($this->user_session['uid'], intval($address_id));
		if(isset($_GET['params'])&&$_GET['params']=='onlybuy'){
            $return = $this->check_cart($user_adress['adress_id'],'onlybuy');
            $this->assign('onlybuy', $_GET['params']);
        }else{
            $return = $this->check_cart($user_adress['adress_id']);
        }

		if ($return['error_code']) $this->error_tips($return['msg']);
		
		$village_id = isset($_GET['village_id']) ? intval($_GET['village_id']) : 0;
		$this->assign('village_id', $village_id);
		$this->assign('user_adress', $user_adress);
		
		$pick_addr_id = isset($_GET['pick_addr_id']) ? $_GET['pick_addr_id'] : '';
		$pick_list = D('Pick_address')->get_pick_addr_by_merid($return['mer_id'], true);
		if (empty($pick_list)) {
		    $lng_lat = D('User_long_lat')->getLocation($_SESSION['openid'], 0);
		    $nowStore = $return['store'];
		    $distance = getDistance($lng_lat['lat'], $lng_lat['long'], $nowStore['lat'], $nowStore['long']);
		    $areaList = D('Area')->where(array('area_id' => array('in', array($nowStore['province_id'], $nowStore['city_id'], $nowStore['area_id']))))->getField('area_id,area_name');
		    $pick_list[] = array(
		        'name' => $nowStore['adress'] . ' ' . $nowStore['name'],
		        'area_info' => array(
		            'province' => $areaList[$nowStore['province_id']],
		            'city' => $areaList[$nowStore['city_id']],
		            'area' => $areaList[$nowStore['area_id']]
		        ),
		        'pick_addr_id' => 's' . $nowStore['store_id'],
		        'phone' => $nowStore['phone'],
		        'long' => $nowStore['long'],
		        'lat' => $nowStore['lat'],
		        'addr_type' => 1,
		        'distance' => $distance
		    );
		}
		if ($pick_addr_id) {
		    foreach ($pick_list as $k => $v) {
		        if ($v['pick_addr_id'] == $pick_addr_id) {
		            $pick_address = $v;
		            break;
		        }
		    }
		} else {
		    $pick_address = $pick_list[0];
		}
		$pick_address['distance'] = $this->wapFriendRange($pick_address['distance']);
		
// 		if ($pick_addr_id) {
// 			foreach ($pick_list as $k => $v) {
// 				if ($v['pick_addr_id'] == $pick_addr_id) {
// 					$pick_address = $v;
// 					break;
// 				}
// 			}
// 		} else {
// 			$pick_address = $pick_list[0];
// 		}

		$this->assign($return);
		$this->assign('pick_addr_id', $pick_addr_id);
		$this->assign('pick_address', $pick_address);
		
		$now_store_category_relation = M('Shop_category_relation')->where(array('store_id'=>$return['store_id']))->find();
		$now_store_category = M('Shop_category')->where(array('cat_id'=>$now_store_category_relation['cat_id']))->find();
		if($now_store_category['cue_field']){
			$this->assign('cue_field',unserialize($now_store_category['cue_field']));
		}
        // 添加查询 商城店铺 配送类型
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        if ($store_id) {
            $merchant_store_shop_info = M('Merchant_store_shop')->field('delivery_type')->where(array('store_id' => $store_id))->find();
            if ($merchant_store_shop_info) {
                $this->assign('delivery_type', $merchant_store_shop_info['delivery_type']);
            }
        }
		
		$this->display();
	}
	
	
	public function save_order()
	{
	    $address_id = isset($_POST['adress_id']) ? intval($_POST['adress_id']) : 0;
	    if(isset($_REQUEST['onlybuy'])&&$_REQUEST['onlybuy']=='onlybuy'){
            $return = $this->check_cart($address_id,$_REQUEST['onlybuy']);
        }else{
            $return = $this->check_cart($address_id);
        }
		if ($return['error_code']) $this->error_tips($return['msg']);
		if (IS_POST) {
			$village_id = isset($_REQUEST['village_id']) ? intval($_REQUEST['village_id']) : 0;
			$phone = isset($_POST['ouserTel']) ? htmlspecialchars($_POST['ouserTel']) : '';
			$name = isset($_POST['ouserName']) ? htmlspecialchars($_POST['ouserName']) : '';
			$address = isset($_POST['ouserAddres']) ? htmlspecialchars($_POST['ouserAddres']) : '';
			$pick_address = isset($_POST['pick_address']) ? htmlspecialchars($_POST['pick_address']) : '';
			$invoice_head = isset($_POST['invoice_head']) ? htmlspecialchars($_POST['invoice_head']) : '';
			$pick_id = isset($_POST['pick_id']) ? htmlspecialchars($_POST['pick_id']) : 0;
			$pickType = substr($pick_id, 0, 1);
			$pick_id = substr($pick_id, 1);
			$deliver_type = isset($_POST['deliver_type']) ? intval($_POST['deliver_type']) : 0;
			$arrive_time = isset($_POST['oarrivalTime']) ? htmlspecialchars($_POST['oarrivalTime']) : 0;
			$arrive_date = isset($_POST['oarrivalDate']) ? htmlspecialchars($_POST['oarrivalDate']) : 0;
			$note = isset($_POST['omark']) ? htmlspecialchars($_POST['omark']) : '';

			if ($deliver_type != 1) {//配送方式是：非自提和非快递配送
				if (empty($name)) $this->error_tips('联系人不能为空');
				if (empty($phone)) $this->error_tips('联系电话不能为空');
				if (!($user_address = D('User_adress')->field(true)->where(array('adress_id' => $address_id, 'uid' => $this->user_session['uid']))->find())) {
					$this->error_tips('地址信息不存在');
				}
			}
			$now_time = time();
			$order_data = array();
			$order_data['mer_id'] = $return['mer_id'];
			$order_data['store_id'] = $return['store_id'];
			$order_data['uid'] = $this->user_session['uid'];

			$order_data['desc'] = $note;
			$order_data['create_time'] = $now_time;
			$order_data['last_time'] = $now_time;
			$order_data['invoice_head'] = $invoice_head;
			$order_data['village_id'] = $village_id;

			$order_data['num'] = $return['total'];
			$order_data['packing_charge'] = $return['packing_charge'];//打包费
			$order_data['merchant_reduce'] = $return['sto_first_reduce'] + $return['sto_full_reduce'];//店铺优惠
			$order_data['balance_reduce'] = $return['sys_first_reduce'] + $return['sys_full_reduce'];//平台优惠
			$orderid  = date('ymdhis').substr(microtime(),2,8-strlen($this->user_session['uid'])).$this->user_session['uid'];
			$order_data['real_orderid'] = $orderid;
			$order_data['no_bill_money'] = 0;//无需跟平台对账的金额
			
			if ($deliver_type == 1) {//自提
				$order_data['is_pick_in_store'] = 2;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
				$delivery_fee = $order_data['freight_charge'] = 0;//运费
				$order_data['username'] = isset($this->user_session['nickname']) && $this->user_session['nickname'] ? $this->user_session['nickname'] : '';
				$order_data['userphone'] = isset($this->user_session['phone']) && $this->user_session['phone'] ? $this->user_session['phone'] : '';
				$order_data['address'] = $pick_address;
				$order_data['address_id'] = 0;
				if ($pickType == 's') {
				    $order_data['pick_id'] = 0;
				    $order_data['status'] = 9;
				} else {
				    $order_data['pick_id'] = $pick_id;
				    $order_data['status'] = 7;
				}
// 				$order_data['pick_id'] = $pick_id;
// 				$order_data['status'] = 7;
// 				$order_data['expect_use_time'] = time() + $return['store']['send_time'] * 60;//客户期望使用时间
			} else {//配送
				$order_data['username'] = $name;
				$order_data['userphone'] = $phone;
				$order_data['address'] = $address;
				$order_data['address_id'] = $address_id;
				$order_data['lat'] = $user_address['latitude'];
				$order_data['lng'] = $user_address['longitude'];
// 				$order_data['expect_use_time'] = time() + 86400 * 7;//客户期望使用时间
				$delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];//运费
				$order_data['is_pick_in_store'] = 3;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
			}
			
			
			$order_data['order_from'] = 1;//商品的价格
			$order_data['goods_price'] = $return['price'];//商品的价格
			$order_data['extra_price'] = $return['extra_price'];//另外要支付的金额
			$order_data['discount_price'] = $return['vip_discount_money'];//商品折扣后的总价
			$order_data['total_price'] = $return['price'] + $delivery_fee + $return['packing_charge'];//订单总价  商品价格+打包费+配送费
			$order_data['price'] = $order_data['discount_price'] + $delivery_fee + $return['packing_charge'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'];//实际要支付的价格
			$order_data['discount_detail'] = '';//优惠详情
			$order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情
			$order_data['can_discount_money'] = $return['can_discount_money'] ;//使用商家优惠券的金额

			$order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'
			
			//自定义字段
			if($_POST['cue_field']){
				$order_data['cue_field'] = serialize($_POST['cue_field']);
			}
			
			if ($order_id = D('Shop_order')->add($order_data)) {
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 0));
				if ($order_data['is_pick_in_store'] == 2 && $order_data['status'] == 7) {
					D('Pick_order')->add(array('store_id' => $order_data['store_id'], 'order_id' => $order_id, 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
					D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//分配到自提点
				}
				$detail_obj = D('Shop_order_detail');
				$goods_obj = D("Shop_goods");
				foreach ($return['goods'] as $grow) {
					$detail_data = array('store_id' => $return['store_id'], 'order_id' => $order_id, 'number' => isset($grow['number']) && $grow['number'] ? $grow['number'] : '', 'cost_price' => $grow['cost_price'], 'unit' => $grow['unit'], 'goods_id' => $grow['goods_id'], 'name' => $grow['name'], 'price' => $grow['price'], 'num' => $grow['num'], 'spec' => $grow['str'], 'spec_id' => $grow['spec_id'], 'create_time' => time());
					$detail_data['is_seckill'] = intval($grow['is_seckill_price']);
					$detail_data['discount_type'] = intval($grow['discount_type']);
					$detail_data['discount_rate'] = $grow['discount_rate'];
					$detail_data['sort_id'] = $grow['sort_id'];
					$detail_data['old_price'] = floatval($grow['old_price']);
					$detail_data['discount_price'] = floatval($grow['discount_price']);
					$detail_data['pay_price'] = floatval($grow['discount_price']);
					D('Shop_order_detail')->add($detail_data);
					$order_data['reduce_stock_type'] && $goods_obj->update_stock($grow);//修改库存
				}
				if ($this->user_session['openid']) {
					$keyword2 = '';
					$pre = '';
					foreach ($return['goods'] as $menu) {
						$keyword2 .= $pre . $menu['name'] . ':' . $menu['price'] . '*' . $menu['num'];
						$pre = '\n\t\t\t';
					}
					$href = C('config.site_url').'/wap.php?c=Shop&a=status&order_id='. $order_id . '&mer_id=' . $order_data['mer_id'] . '&store_id=' . $order_data['store_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $orderid, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $keyword2, 'remark' => '您的该次'.$this->config['shop_alias_name'].'下单成功，感谢您的使用！'), $order_data['mer_id']);
				}

				$printHaddle = new PrintHaddle();
				$printHaddle->printit($order_id, 'shop_order', 0);
				
// 				$msg = ArrayToStr::array_to_str($order_id, 'shop_order');
// 				$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
// 				$op->printit($return['mer_id'], $return['store_id'], $msg, 0);

// 				$str_format = ArrayToStr::print_format($order_id, 'shop_order');
// 				foreach ($str_format as $print_id => $print_msg) {
// 					$print_id && $op->printit($return['mer_id'], $return['store_id'], $print_msg, 0, $print_id);
// 				}
				
				
				$sms_data = array('mer_id' => $return['mer_id'], 'store_id' => $return['store_id'], 'type' => 'shop');
				if ($this->config['sms_shop_place_order'] == 1 || $this->config['sms_shop_place_order'] == 3) {
					$sms_data['uid'] = $this->user_session['uid'];
					$sms_data['mobile'] = $order_data['userphone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您' . date("Y-m-d H:i:s") . '在【' . $return['store']['name'] . '】中下了一个订单，订单号：' . $orderid;
					Sms::sendSms($sms_data);
				}
				if ($this->config['sms_shop_place_order'] == 2 || $this->config['sms_shop_place_order'] == 3) {
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $return['store']['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客【' . $order_data['username'] . '】在' . date("Y-m-d H:i:s") . '时下了一个订单，订单号：' . $orderid . '请您注意查看并处理!';
					Sms::sendSms($sms_data);
				}
				
				redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'mall')));
			} else {
				$this->error_tips('订单保存失败');
			}
		} else {
			$this->error_tips('不合法的提交');
		}
		
	}
	
	private function check_cart($address_id,$onlybuy='cart')
	{
		$this->isLogin();
		$store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
		if($onlybuy=='onlybuy'){
            $productCart = $this->getCookieData($onlybuy);
        }else{
            $productCart = $this->getCookieData('cart');//json_decode(cookie('buy_mall_goods_cart'), true);
        }
		return D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $productCart, 1, $address_id);
	}
	
	private function getCookieData($onlybuy)
	{
        $productCart = array();
        for ($i = 0; $i < 40; $i++) {
            if($onlybuy=='onlybuy'){
                $tmpCookie = cookie('only_buy_cart_' . $i);
            }else{
                $tmpCookie = cookie('buy_mall_goods_cart_' . $i);
            }
            if (!empty($tmpCookie)) {
                $tmpArr = json_decode($tmpCookie, true);
                if (empty($tmpArr)) {
                    $tmpArr = array();
                }
                $productCart = array_merge($productCart, $tmpArr);
            } else {
                break;
            }
        }
	    return $productCart;
	}
	
	
	private function isLogin()
	{
		if (empty($this->user_session)) {
			if($this->is_app_browser){
				$this->error_tips('请先进行登录！',U('Login/index'));
			}else{
				redirect(U('Login/index',array('referer'=>urlencode($_SERVER["REQUEST_URI"]))));
			}
		}
	}
	
	private function get_reduce($discounts, $type, $price, $store_id = 0)
	{
		$reduce_money = 0;
		$return = null;
		if (isset($discounts[$store_id])) {
			foreach ($discounts[$store_id] as $row) {
				if ($row['type'] == $type) {
					if ($price >= $row['full_money']) {
						if ($reduce_money < $row['reduce_money']) {
							$reduce_money = $row['reduce_money'];
							$return = $row;
						}
					}
				}
			}
		}
		return $return;
	}
	
	public function status_old()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))) {
			$storeName = D("Merchant_store")->field('`name`, `phone`')->where(array('store_id' => $order['store_id']))->find();
			$this->assign('storeName', $storeName);
			cookie('shop_cart_' . $order['store_id'], null);
			$this->clear_cookie();
				
			$status = D('Shop_order_log')->field(true)->where(array('order_id' => $order_id))->order('id DESC')->select();
			$statusCount = D("Shop_order_log")->where(array('order_id' => $order_id))->count();
			$this->assign('statusCount', $statusCount);
			$this->assign('status', $status);
			$this->assign('order_id', $order_id);
			$this->assign('order', $order);
			$this->display();
		} else {
			$this->error_tips('错误的订单信息！');
		}
	}
	
	public function order_detail_old()
	{
		$this->isLogin();
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']));
		$store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
		$shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
		$this->clear_cookie();
		$this->assign('store', array_merge($store, $shop));
		$this->assign('order', $order);
		$this->display();
	}
	
	private function clear_cookie()
	{
		$goodsCart = array();
		$type = cookie('mall_buy_type');
		if($type=='add'){
            $buy_mall_goods_cart = $this->getCookieData('cart');//json_decode(cookie('buy_mall_goods_cart'), true);
            foreach ($buy_mall_goods_cart as $row) {
                $goodsCartKey = 's_' . $row['store_id'] . '_g_' . $row['productId'];
                if ($row['productParam']) {
                    foreach ($row['productParam'] as $param) {
                        if ($param['type'] == 'spec') {
                            $goodsCartKey .= '_s_' . $param['id'];
                        } else {
                            if ($param['data']) {
                                foreach ($param['data'] as $da) {
                                    $goodsCartKey .= '_v_' . $da['id'];
                                }
                            }
                        }
                    }
                }
                $goodsCart[$goodsCartKey] = $row;
            }
            for ($i = 0; $i < 40; $i++) {
                $tmpCookie = cookie('buy_mall_goods_cart_' . $i);
                if (!empty($tmpCookie)) {
                    cookie('buy_mall_goods_cart_' . $i, null);
                } else {
                    break;
                }
            }
            cookie('buy_mall_goods_cart', null);
            if ($goodsCart) {
                $cart_goods = array();
                $productCart_t = array();
                for ($i = 0; $i < 40; $i++) {
                    $tmpCookie = cookie('mall_goods_cart_' . $i);
                    if (!empty($tmpCookie)) {
                        $tmpArr = json_decode($tmpCookie, true);
                        if (empty($tmpArr)) {
                            $tmpArr = array();
                        }
                        $productCart_t = array_merge($productCart_t, $tmpArr);
                    } else {
                        break;
                    }
                }
                //$productCart_t = json_decode(cookie('mall_goods_cart'), true);
                foreach ($productCart_t as $row) {
                    $this_index = 's_' . $row['store_id'] . '_g_' . $row['productId'];
                    if ($row['productParam']) {
                        foreach ($row['productParam'] as $param) {
                            if ($param['type'] == 'spec') {
                                $this_index .= '_s_' . $param['id'];
                            } else {
                                if ($param['data']) {
                                    foreach ($param['data'] as $da) {
                                        $this_index .= '_v_' . $da['id'];
                                    }
                                }
                            }
                        }
                    }
                    if (!isset($goodsCart[$this_index])) {
                        $cart_goods[] = $row;
                    }
                }
                if ($cart_goods) {
                    $cart_goods = array_chunk($cart_goods, 5);
                    $t = 0;
                    foreach ($cart_goods as $i => $cart) {
                        cookie('mall_goods_cart_' . $i, json_encode($cart));
                        $t = $i;
                    }
                    $t ++;
                    for ($i = $t; $i < 40; $i++) {
                        cookie('mall_goods_cart_' . $i, null);
                    }
                } else {
                    for ($i = 0; $i < 40; $i++) {
                        cookie('mall_goods_cart_' . $i, null);
                    }
                }
            }
            cookie('mall_buy_cart',null);
        }else{
            cookie('mall_buy_cart',null);
        }

	}
	
	public function order_detail()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))) {
			$storeName = D("Merchant_store")->field('`name`, `phone`')->where(array('store_id' => $order['store_id']))->find();
			$this->assign('storeName', $storeName);
			cookie('shop_cart_' . $order['store_id'], null);
			$this->clear_cookie();
				
			$status = D('Shop_order_log')->field(true)->where(array('order_id' => $order_id))->order('id DESC')->select();
			$statusCount = D("Shop_order_log")->where(array('order_id' => $order_id))->count();
			$this->assign('statusCount', $statusCount);
			$this->assign('status', $status);
			$this->assign('order_id', $order_id);
			$this->assign('order', $order);
			$this->display('status_new');
		} else {
			$this->error_tips('错误的订单信息！');
		}
	}
    


	/**
	 * 订单详情
	 */
    public function status()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $order = D("Shop_order")->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']));
        if ($order) {
            $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
            $store_image_class = new store_image();
            $images = $store_image_class->get_allImage_by_path($store['pic_info']);
            $store['image'] = isset($images[0]) ? $images[0] : '';
            
            cookie('shop_cart_' . $order['store_id'], null);
            $this->clear_cookie();
            
            if($order['pay_type'] == 'offline' && empty($order['third_id'])){
                $payment = rtrim(rtrim(number_format($order['price']-$order['card_price']-$order['merchant_balance']-$order['card_give_money']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-floatval($order['coupon_price']),2,'.',''),'0'),'.');
            }
            $discount_price = floatval(round($order['discount_price'] + $order['freight_charge'] + $order['packing_charge'], 2));
            $order['card_discount'] = $order['card_discount'] == 0 ? 10 : $order['card_discount'];
            $arr['order_details'] = array(
                'orderid' => $order['orderid'],
                'order_id' => $order['order_id'],
                'real_orderid' => $order['real_orderid'],
                'username' => $order['username'],
                'userphone' => $order['userphone'],
                'create_time' => date('Y-m-d H:i:s',$order['create_time']),
                'pay_time' => date('Y-m-d H:i:s',$order['pay_time']),
                'expect_use_time' => $order['expect_use_time'] != 0 ? date('Y-m-d H:i',$order['expect_use_time']) : '尽快',
                'is_pick_in_store' => $order['is_pick_in_store'],//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
                'address' => $order['address'],
                'deliver_str' => $order['deliver_str'],
                'deliver_status_str' => $order['deliver_status_str'],
                'note' => isset($order['desc']) ? $order['desc'] : '',
                'invoice_head' => $order['invoice_head'],//发票抬头
                'pay_status' => $order['pay_status_print'],
                'pay_type_str' => $order['pay_type_str'],
                'status_str' => $order['status_str'],
                'score_used_count' => $order['score_used_count'],//抵用的积分
                'score_deducte' => strval(floatval($order['score_deducte'])),//积分兑现的金额
                'card_give_money' => strval(floatval($order['card_give_money'])),//会员卡赠送余额
                'merchant_balance' => strval(floatval($order['merchant_balance'])),//商家余额
                'balance_pay' => strval(floatval($order['balance_pay'])),//平台余额
                'payment_money' => strval(floatval($order['payment_money'])),//在线支付的金额
                'change_price' => strval(floatval($order['change_price'])),//店员修改前的原始价格（如果是0表示没有修改过，可不显示）
                'change_price_reason' => $order['change_price_reason'],//店员修改价格的理由
                'card_id' => $order['card_id'],
                'card_price' => strval(floatval($order['card_price'])),//商家优惠券的金额
                'coupon_price' => strval(floatval($order['coupon_price'])),//平台优惠券的金额
                'payment' => isset($payment) ? $payment : 0,
                'use_time' => $order['use_time'] != 0 ? date('Y-m-d H:i:s',$order['use_time']) : '0',
                'last_staff' => $order['last_staff'],
                'status' => $order['status'],
                'paid' => $order['paid'],
                'register_phone' => $order['register_phone'],//注册时的用户手机号
                'lat' => $order['lat'],
                'lng' => $order['lng'],
                'cue_field' => $order['cue_field'],//商家自定义字段值（如果没有的话是空 即：''）
                'card_discount' => $order['card_discount'],//会员卡折扣
                'goods_price' => strval(floatval($order['goods_price'])),//商品的总价
                'freight_charge' => strval(floatval($order['freight_charge'])),//配送费
                'packing_charge' => strval(floatval($order['packing_charge'])),//打包费
                'total_price' => strval(floatval($order['total_price'])),//订单总价
                'merchant_reduce' => strval(floatval($order['merchant_reduce'])),//商家优惠的金额
                'balance_reduce' => strval(floatval($order['balance_reduce'])),//平台优惠的金额
                'price' => strval(floatval($order['price'])),//实际支付金额
                'distance' => round(getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long'])/1000, 2),//距离
                'discount_price' => strval($discount_price),//折扣后的总价  = floatval(round($order['discount_price'] + $order['freight_charge'] + $order['packing_charge'], 2));
                'minus_price' => strval(floatval(round($order['merchant_reduce'] + $order['balance_reduce'], 2))),//平台和商家的优惠金额
                'go_pay_price' => strval(floatval(round($discount_price - $order['merchant_reduce'] - $order['balance_reduce'], 2))),//应付的金额
                'minus_card_discount' => strval(floatval(round(($discount_price - $order['merchant_reduce'] - $order['balance_reduce'] - $order['freight_charge']) * (1 - $order['card_discount'] * 0.1), 2))),//折扣与优惠的优惠金额
                'order_from_txt' => $this->order_froms[$order['order_from']],
                'deliver_log_list' => D('Shop_order_log')->where(array('order_id' => $order['order_id']))->order('id DESC')->find(),
                'deliver_info' => unserialize($order['deliver_info']),
                'express_name' => isset($order['express_name']) ? $order['express_name'] : '',
                'express_code' => isset($order['express_code']) ? $order['express_code'] : '',
                'express_number' => isset($order['express_number']) ? $order['express_number'] : '',
            );
            foreach($order['info'] as $v) {
                $discount_price = floatval($v['discount_price']) > 0 ? floatval($v['discount_price']) : floatval($v['price']);
                $arr['info'][] = array(
                    'name' => $v['name'],
                    'discount_type' => $v['discount_type'],
                    'price' => strval(floatval($v['price'])),
                    'discount_price' => strval($discount_price),
                    'spec' => empty($v['spec']) ? '' : $v['spec'],
                    'num' => $v['num'],
                    'total' => strval(floatval($v['price'] * $v['num'])),
                    'discount_total' => strval(floatval($discount_price * $v['num'])),
                );
            }
            $arr['discount_detail'] = $order['discount_detail'] ?: '';
//             echo '<pre/>';
//             print_r($arr);die;
            $this->assign($arr);
            $shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
            $this->assign('store', array_merge($store, $shop));
            $this->display('order_detail_new');
        } else {
            $this->error_tips('订单信息错误！');
        }
    }
}
?>