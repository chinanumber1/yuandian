<?php
class FoodshopAction extends BaseAction
{

	protected $weeks = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
	/**
	 * 店铺列表
	 */
	public function index()
	{
		//导航条
		$adver = D('Adver')->get_adver_by_key('wap_foodshop_index_top', 5);
        $this->assign('wap_foodshop_index_top', $adver);
        $this->assign('wap_foodshop_slider', D('Slider')->get_slider_by_key('wap_foodshop_slider', 8));
// 		$this->assign('wap_foodshop_slider', D('Slider')->get_slider_by_key('wap_slider', 8));


		//判断地区信息
		$area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';
		$this->assign('now_area_url', $area_url);
		$circle_id = 0;
		$area_id = 0;
		if (!empty($area_url)) {
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
		}

		//判断排序信息

		$sort = !empty($_GET['sort']) ? htmlspecialchars(trim($_GET['sort'])) : 'juli';
		$queue = isset($_GET['queue']) ? intval($_GET['queue']) : -1;

		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'], 0);
		if (empty($long_lat)) {
			$sort = $sort == 'juli' ? 'defaults' : $sort;
			$sort_array = array(
					array('sort_id'=>'defaults','sort_value'=>'智能排序'),
					array('sort_id'=>'rating','sort_value'=>'评价最高'),
					array('sort_id'=>'new','sort_value'=>'新店'),
					array('sort_id'=>'discount','sort_value'=>'折扣最大'),
			);
		} else {
			$sort_array = array(
					array('sort_id'=>'juli', 'sort_value'=>'离我最近'),
					array('sort_id'=>'rating', 'sort_value'=>'评价最高'),
    			    array('sort_id'=>'defaults', 'sort_value'=>'智能排序'),
    			    array('sort_id'=>'new','sort_value'=>'新店'),
    			    array('sort_id'=>'discount','sort_value'=>'折扣最大'),
			);
			$this->assign('long_lat', $long_lat);
		}

        if (0 == $this->config['is_open_merchant_foodshop_discount']) {
            foreach($sort_array as $k => $v){
                if($v['sort_id']=='new' || $v['sort_id']=='discount'){
                    unset($sort_array[$k]);
                }
            }
        }

		foreach ($sort_array as $key => $value) {
			if ($sort == $value['sort_id']) {
				$now_sort_array = $value;
				break;
			}
		}

		if( $this->user_session['uid'] && $this->config['open_rand_send']){
			 $coupon_html = D('System_coupon')->rand_send_coupon_get(array('time'=>$_SERVER['REQUEST_TIME'],'uid'=>$this->user_session['uid']));
			$coupon_html && $this->assign('coupon_html',$coupon_html);
		}

		$queue_array = array('-1' => '不限', '0' => '无排号', '1' => '可排号');
		$other = $now_sort_array['sort_value'] . '/' . $queue_array[$queue];
		$this->assign('other', $other);
		$this->assign('sort_array', $sort_array);
		$this->assign('now_sort_array', $now_sort_array);

		$all_area_list = D('Area')->get_all_area_list();
		$this->assign('all_area_list', $all_area_list);

		$cat_url = isset($_GET['cat_url']) ? htmlspecialchars(trim(($_GET['cat_url']))) : 'all';
		$this->assign('now_cat_url', $cat_url);
		$this->assign('now_queue', $queue);
		$cat_id = 0;
		if($cat_url != 'all'){
			$now_category = D('Meal_store_category')->get_category_by_catUrl($cat_url);

			if (empty($now_category)) {
				$this->error_tips('此分类不存在！');
			}
			$this->assign('now_category',$now_category);

			if (!empty($now_category['cat_fid'])) {
				$f_category = D('Meal_store_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];

				$this->assign('top_category',$f_category);

				$cat_fid = $now_category['cat_fid'];
				$cat_id = $now_category['cat_id'];
			} else {
				$this->assign('top_category',$now_category);

				$cat_id = 0;
				$cat_fid = $now_category['cat_id'];
			}
		}
		$all_category_list = D('Meal_store_category')->get_all_category();
		$this->assign('all_category_list', $all_category_list);
		$this->display();
	}

	public function ajaxList()
	{
		$this->header_json();

		//判断地区信息
		$area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';

		$circle_id = 0;
		$area_id = 0;
		$cat_url = isset($_GET['cat_url']) ? htmlspecialchars(trim(($_GET['cat_url']))) : 'all';
		if (!empty($area_url)) {
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
		}



		$cat_id = 0;
		if($cat_url != 'all'){
			$now_category = D('Meal_store_category')->get_category_by_catUrl($cat_url);
			if (empty($now_category)) {
				$this->error_tips('此分类不存在！');
			}
			$this->assign('now_category',$now_category);

			if (!empty($now_category['cat_fid'])) {
				$f_category = D('Meal_store_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];

				$this->assign('top_category',$f_category);

				$cat_fid = $now_category['cat_fid'];
				$cat_id = $now_category['cat_id'];
			} else {
				$this->assign('top_category',$now_category);
				$cat_id = 0;
				$cat_fid = $now_category['cat_id'];
			}
		}

		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'], 0);

		//判断排序信息
		$sort = isset($_GET['sort']) ? htmlspecialchars(trim($_GET['sort'])) : '';
		$queue = isset($_GET['queue']) ? intval($_GET['queue']) : -1;
		$keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';

		$where = array('area_id' => $area_id, 'circle_id' => $circle_id, 'cat_fid' => $cat_fid, 'cat_id' => $cat_id, 'lat' => $long_lat['lat'], 'long' => $long_lat['long'], 'sort' => $sort, 'queue' => $queue, 'keyword' => $keyword);

		$return = D('Merchant_store_foodshop')->wap_get_storeList_by_catid($where);
// 		$return = D('Merchant_store_foodshop')->wap_get_storeList_by_catid($area_id, $circle_id, $sort_id, $long_lat['lat'], $long_lat['long'], $cat_url);
		echo json_encode($return);
	}

	/**
	 * 店铺详情
	 */
	public function shop()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;

		$store = M('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($store)) {
			$this->error_tips('不存在的店铺');
			exit;
		} else {
			$store['business_time'] = '';
			$store['is_close']      = 1;
			if ($store['open_1'] == '00:00:00' && $store['close_1'] == '00:00:00') {
				$store['is_close']      = 0;
				$store['business_time'] = '24小时营业';
			} else {
				$now_time = time();
				$store['business_time'] = substr($store['open_1'], 0, -3) . '~' . substr($store['close_1'], 0, -3);
				$open_1 = strtotime(date('Y-m-d') . $store['open_1']);
				$close_1 = strtotime(date('Y-m-d') . $store['close_1']);
				if ($open_1 >= $close_1) {
				    $close_1 += 86400;
				}
				if ($open_1 < $now_time && $now_time < $close_1) {
					$store['is_close'] = 0;
				}
				if ($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00') {
				    $store['business_time'] .= ';' . substr($store['open_2'], 0, -3) . '~' . substr($store['close_2'], 0, -3);
				    $open_2 = strtotime(date('Y-m-d') . $store['open_2']);
				    $close_2 = strtotime(date('Y-m-d') . $store['close_2']);
				    if ($open_2 >= $close_2) {
				        $close_2 += 86400;
				    }
				    if ($open_2 < $now_time && $now_time < $close_2) {
						$store['is_close'] = 0;
					}
				}
				if ($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00') {
				    $store['business_time'] .= ';' . substr($store['open_3'], 0, -3) . '~' . substr($store['close_3'], 0, -3);
				    $open_3 = strtotime(date('Y-m-d') . $store['open_3']);
				    $close_3 = strtotime(date('Y-m-d') . $store['close_3']);
				    if ($open_3 >= $close_3) {
				        $close_3 += 86400;
				    }
				    if ($open_3 < $now_time && $now_time < $close_3) {
						$store['is_close'] = 0;
					}
				}
			}
		}


		$store_image_class   = new store_image();
		$images              = $store_image_class->get_allImage_by_path($store['pic_info']);
		$store['image_list'] = $images;
		$store['image']      = $images ? array_shift($images) : array();

		$merchant = M('Merchant')->field(true)->where(array('mer_id' => $store['mer_id']))->find();
		if (empty($merchant)) {
			$this->error_tips('不存在的商家');
			exit;
		}
		$foodshop = M('Merchant_store_foodshop')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($foodshop)) {
			$this->error_tips('不存在的餐饮店铺');
			exit;
		}
		$foodshop['pic_count'] = 1;
		$foodshop['pic_str']   = '';
		if (!empty($foodshop['pic'])) {
			$goods_image_class     = new foodshopstore_image();
			$foodshop['pic']       = $goods_image_class->get_allImage_by_path($foodshop['pic'], 'm');
			$foodshop['pic_count'] = count($foodshop['pic']);
			$foodshop['pic_str']   = implode(',', $foodshop['pic']);
		}

		$foodshop = array_merge($store, $foodshop);

		$card_info   = D('Card_new')->get_card_by_mer_id($foodshop['mer_id']);
		$coupon_list = D('Card_new_coupon')->get_coupon_list_by_type_merid('meal', $foodshop['mer_id'], 0, 5, -1);

		$this->assign('card_info', $card_info);
		$this->assign('coupon_list', $coupon_list);

// 		echo '<pre/>';
// 		print_r($foodshop);die;
		$now_time          = time();
		$sql               = 'SELECT * FROM ' . C('DB_PREFIX') . 'group_store AS gs INNER JOIN ' . C('DB_PREFIX') . 'group AS g ON g.group_id=gs.group_id WHERE gs.store_id=' . $store_id . ' AND `g`.`status`=1  AND `g`.`type`=1 AND `g`.`end_time`>\'' . $now_time . '\' ORDER BY `g`.`sort` DESC,`g`.`group_id` DESC';
		$groups            = D()->query($sql);
		$group_image_class = new group_image();
		foreach ($groups as $row) {
			$tmp_pic_arr     = explode(';', $row['pic']);
			$row['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
			$row['url']      = U('Group/detail', array('pin_num' => $row['pin_num'], 'group_id' => $row['group_id']), true, false, true);

			$row['price']     = floatval($row['price']);
			$row['old_price'] = floatval($row['old_price']);
			$row['wx_cheap']  = floatval($row['wx_cheap']);
			$row['is_start']  = 1;
			$row['pin_num']   = $row['pin_num'];
			if ($now_time < $row['begin_time']) {
				$row['is_start'] = 0;
			}
			if ($row['begin_time'] + 864000 > time() && $row['sale_count'] == 0) {
				$row['sale_txt'] = '新品上架';
			} elseif ($row['begin_time'] + 864000 < time() && $row['sale_count'] == 0) {
				$row['sale_txt'] = '';
			} else {
				$row['sale_txt'] = '已售' . floatval($row['sale_count'] + $row['virtual_num']);
			}
			$row['begin_time']        = date("Y-m-d H:i:s", $row['begin_time']);
			$foodshop['group_list'][] = $row;
		}
		if ($foodshop['is_takeout'] && ($shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find())) {
			$foodshop['is_takeout'] = 1;
		} else {
			$foodshop['is_takeout'] = 0;
		}
		$this->assign('shop', $foodshop);

		//桌次优惠
        if (1 ==$this->config['is_open_merchant_foodshop_discount']) {
            $time_start = strtotime(date('Ymd'));
            $time_end = strtotime(date('Ymd 23:59:59'));
            $where['create_time'] = array('between',array($time_start,$time_end));
            $where['status'] = array('in',array(1,2,3,4));
            $where['mer_id'] = $merchant['mer_id'];
            $order_index = D('foodshop_order')->where($where )->count();

            if($merchant['foodshop_tables_discount']!=''){
                $merchant['foodshop_tables_discount'] = substr($merchant['foodshop_tables_discount'], 0, -1);
                $discountArr= explode('|', $merchant['foodshop_tables_discount']);
                $tables = [];
                foreach($discountArr as $k => $v){
                    $v = explode(':',$v);
                    $tables[$k] = $v;
                }
                if(intval($order_index)>=count($tables)){
                    $mer_discount =  $merchant['other_discount'];
                }else{
                    $mer_discount =  $tables[$order_index][1];
                }
            }else{
                $mer_discount =  $merchant['other_discount'];
            }
            $mer_discount = floatval($mer_discount / 10);
            if($mer_discount>0 && $mer_discount<100){
                $this->assign('mer_discount',$mer_discount);
			}
        }

		$goods_list = M('Foodshop_goods')->field(true)->where(array('store_id' => $store_id, 'status' => 1, 'is_hot' => 1))->select();

		$goods_image_class = new foodshop_goods_image();
		foreach ($goods_list as &$row) {
		    $tmp_pic_arr = explode(';', $row['image']);
		    foreach ($tmp_pic_arr as $key => $value) {
		        $row['pic_arr'][$key]['title'] = $value;
		        $row['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value);
		    }
		}

		$this->assign('goods_list', $goods_list);

		$reply_list = D('Reply')->get_reply_list($store_id, 4, 1, 3);

		$reply_count = D('Reply')->where(array('status' => array('lt', 2), 'parent_id' => $store_id, 'order_type' => 4))->count();
// 		echo '<pre/>';
// 		print_r($reply_list);die;
		$this->assign('reply_list', $reply_list);
		$this->assign('merchant', $merchant);
		$this->assign('reply_count', $reply_count);

		$collection_info = D('Merchant_store_collection')->where(array('store_id' => intval($_GET['store_id']), 'uid' => $this->user_session['uid']))->find();
		if ($collection_info) {
			$this->assign('collection_info', $collection_info);
		}

		//==================
		$tmp_wap_index_slider = M('Store_slider')->field('url,pic,name')->where(array('status' => 1, 'store_id' => $store_id))->order('sort DESC')->select();

		foreach($tmp_wap_index_slider as &$vs){
			$vs['pic'] = C('config.site_url').'/upload/slider/'.$vs['pic'];
		}
		if ($foodshop['is_book']) {
			$default_slider[] = array(
					'url'  => U('Foodshop/book_order', array('store_id' => $store_id)),
					'pic'  => $this->static_path . 'images/book.png',
					'name' => '订桌点餐'
			);
		}
		if ($foodshop['is_queue']) {

			$default_slider[] = array(
					'url'  => U('Foodshop/queue', array('store_id' => $store_id)),
					'pic'  => $this->static_path . 'images/xqym_05.png',
					'name' => '排号'
			);
		}
		if ($this->config['pay_in_store']) {

			$default_slider[] = array(
					'url'  => U('My/pay', array('store_id' => $store_id)),
					'pic'  => $this->static_path . 'images/xqym_07.png',
					'name' => $this->config['cash_alias_name']
			);
		}
		if ($foodshop['is_takeout']) {
			$default_slider[] = array(
					'url'  => U('Shop/index') . '#shop-' . $store_id,
					'pic'  => $this->static_path . 'images/xqym_09.png',
					'name' => $this->config['shop_alias_name']
			);
		}
		if ($card_info && $card_info['self_get'] == 1){
			$default_slider[] = array(
					'url'  => U('My_card/merchant_card', array('mer_id' => $store['mer_id'])),
					'pic'  => $this->static_path . 'images/merchant_card.png',
					'name' => '会员卡'
			);
		}


		$wap_index_slider = array();
		if($tmp_wap_index_slider){

			$tmp_wap_index_slider = array_merge($default_slider,$tmp_wap_index_slider);
		}else{
			$tmp_wap_index_slider = $default_slider;
		}

		//if(count($tmp_wap_index_slider) >= 10 &&$this->config['wap_slider_number'] == 10){
			$wap_slider_number = $this->config['wap_slider_number'];
		//}else{
		//	$wap_slider_number = 8;
		//}
		$slider_num =0;
		foreach($tmp_wap_index_slider as $key=>$value){
			$tmp_i = floor($key/$wap_slider_number);
			$wap_index_slider[$tmp_i][] = $value;
			$slider_num ++;
		}


		$this->assign('slider_num',$slider_num);
		$this->assign('isnew',D('User')->check_new($_SESSION['user']['uid'],'shop'));
		$this->assign('wap_index_slider',$wap_index_slider);
		$this->assign('wap_index_slider_number',$wap_slider_number);
		//==================

		$this->display();
	}

	public function store_collection(){
		$info = D('Merchant_store_collection')->where(array('store_id'=>intval($_POST['store_id']),'uid'=>$this->user_session['uid']))->find();
        if($info){
        	if(D('Merchant_store_collection')->where(array('store_id'=>intval($_POST['store_id']),'uid'=>$this->user_session['uid']))->delete()){
        		exit(json_encode(array('error'=>3,'msg'=>'取消收藏成功')));
        	}
        }
        $res = D('Merchant_store_collection')->data(array('store_id'=>$_POST['store_id'],'uid'=>$this->user_session['uid']))->add();
        if($res){
            exit(json_encode(array('error'=>1,'msg'=>'收藏成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'收藏失败请重试')));
        }
	}
	/**
	 * 预约下单
	 */
	public function book_order()
	{
		$this->isLogin();
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit;
		}
		$foodshop = $foodshop['data'];
		if ($foodshop['is_book'] == 0) {
			$this->error_tips('该店铺不支预订');
			exit;
		}


		//最少可约的人数
		$table_type_data = D('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id))->order('min_people asc')->select();
		if (empty($table_type_data)) {
			$this->error_tips('没有可预约的桌台');
			exit;
		}
		$table_type_data = $table_type_data[0];
		$book_num = isset($table_type_data['min_people']) ? $table_type_data['min_people'] : 2;
		$table_type = $table_type_data['id'];
		$now_time = time() + $foodshop['advance_time'] * 60;

		$order_list = M('Foodshop_order')->field(true)->where(array('table_type' => $table_type, 'book_time' => array('egt', $now_time), 'status' => array('in', array(1, 2))))->select();
		$order_table_list = array();
		foreach ($order_list as $order) {
			$order_table_list[date('YmdHi', $order['book_time'])][] = $order;
		}

		$order_date_count = array();
		if ($order_table_list) {
			foreach ($order_table_list as $index => $row) {
				if ($table_type_data['num'] <= count($row)) {
					$order_date_count[$index] = 1;
				} else {
					$order_date_count[$index] = 0;
				}
			}
		}

		$now_time = time() + $foodshop['advance_time'] * 60;
		$loop_time = $foodshop['book_time'] * 60;
		$start_time = $foodshop['book_start'];
		$stop_time = $foodshop['book_stop'];
		if ($start_time == '00:00:00' && $stop_time == '00:00:00') {
			$stop_time = '23:59:59';
		}
		$book_time = 0;
		for ($d = 0; $d <= $foodshop['book_day']; $d++) {
			$this_start_time = strtotime(date('Y-m-d ', strtotime("+{$d} day")) . $start_time);
			$this_stop_time = strtotime(date('Y-m-d ', strtotime("+{$d} day")) . $stop_time);
			if ($this_start_time < $this_stop_time) {
				for ($t = $this_start_time; $t <= $this_stop_time; $t += $loop_time) {
					if ($t < $now_time) {
					} else {
						if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
						} else {
							$book_time = date('Y-m-d H:i', $t);
							break;
						}
					}
				}
				if ($book_time) break;
			} else {
				$stop_time_t = strtotime(date('Y-m-d ', strtotime("+{$d} day")) . '23:59:59');
				for ($t = $this_start_time; $t <= $stop_time_t; $t += $loop_time) {
					if ($t < $now_time) {
					} else {
						if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
						} else {
							$book_time = date('Y-m-d H:i', $t);
							break;
						}
					}
				}
				if ($book_time) break;
				$d_t = $d + 1;
				if ($d_t < $foodshop['book_day']) {
					$start_time_t = strtotime(date('Y-m-d ', strtotime("+{$d_t} day")) . '00:00:00');
					$stop_time_t = strtotime(date('Y-m-d ', strtotime("+{$d_t} day")) . $stop_time);
					for ($t = $start_time_t; $t <= $stop_time_t; $t += $loop_time) {
						if ($t < $now_time) {
						} else {
							if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
							} else {
								$book_time = date('Y-m-d H:i', $t);
								break;
							}
						}
					}
					if ($book_time) break;
				}
				if ($book_time) break;
			}
		}
		$return = $this->format_data($foodshop, strtotime($book_time), $book_num, $table_type);
		if ($return['err_code']) {
			$this->error_tips($return['msg']);
			exit;
		}
// 		$table_type_data = M('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id))->select();
// 		$this->assign('table_list', $table_type_data);
		$this->assign($return);
		$this->assign(array('sex' => $this->user_session['sex'] == 1 ? 1 : 0, 'name' => $this->user_session['truename'] ? $this->user_session['truename'] : $this->user_session['nickname'], 'phone' => $this->user_session['phone']));
		$this->assign('store', $foodshop);
		$this->display();
	}

	private function now_store($store_id, $is_return = true)
	{
		$store = M('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($store) && $is_return) {
			return array('err_code' => true, 'msg' => '不存在的店铺');
		} elseif ($store['status'] != 1 && $is_return) {
			return array('err_code' => true, 'msg' => '店铺状态异常');
		}  elseif ($store['have_meal'] != 1 && $is_return) {
			return array('err_code' => true, 'msg' => '店铺不支持餐饮');
		} else {
			$store['business_time'] = '';
			if ($store['open_1'] == '00:00:00' && $store['close_1'] == '00:00:00') {
				$store['business_time'] = '24小时营业';
			} else {
			    $now_time = time();
			    $is_close = 1;
			    $open_1 = strtotime(date('Y-m-d') . $store['open_1']);
			    $close_1 = strtotime(date('Y-m-d') . $store['close_1']);
			    if ($open_1 >= $close_1) {
			        $close_1 += 86400;
			    }
			    if ($open_1 < $now_time && $now_time < $close_1) {
			        $is_close = 0;
			    }
			    if ($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00') {
			        $open_2 = strtotime(date('Y-m-d') . $store['open_2']);
			        $close_2 = strtotime(date('Y-m-d') . $store['close_2']);
			        if ($open_2 >= $close_2) {
			            $close_2 += 86400;
			        }
			        if ($open_2 < $now_time && $now_time < $close_2) {
			            $is_close = 0;
			        }
			    }
			    if ($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00') {
			        $store['business_time'] .= ';' . substr($store['open_3'], 0, -3) . '~' . substr($store['close_3'], 0, -3);
			        $open_3 = strtotime(date('Y-m-d') . $store['open_3']);
			        $close_3 = strtotime(date('Y-m-d') . $store['close_3']);
			        if ($open_3 >= $close_3) {
			            $close_3 += 86400;
			        }
			        if ($open_3 < $now_time && $now_time < $close_3) {
			            $is_close = 0;
			        }
			    }
			}
			if ($is_close && $is_return) {
				return array('err_code' => true, 'msg' => '店铺不在营业中');
			}
		}
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($store['pic_info']);
		$store['image_list'] = $images;
		$store['image'] = $images ? array_shift($images) : array();


		$merchant = M('Merchant')->field(true)->where(array('mer_id' => $store['mer_id']))->find();
		if (empty($merchant) && $is_return) {
			return array('err_code' => true, 'msg' => '不存在的商家');
		} elseif ($merchant['status'] != 1 && $is_return) {
			return array('err_code' => true, 'msg' => '商家状态异常');
		} else {

		}
		$foodshop = M('Merchant_store_foodshop')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($foodshop) && $is_return) {
			return array('err_code' => true, 'msg' => '不存在的餐饮店铺');
		}

		$background = '';
		if(!empty($foodshop['background'])){
		    $goods_image_class = new foodshopstore_image();
		    $tmp_pic_arr = explode(';', $foodshop['background']);
		    $background = $goods_image_class->get_image_by_path($tmp_pic_arr[0]);
		    $background = $background && $background['image'] ? $background['image'] : '';
		}
		$foodshop['background'] = $background ? $background : '';

		return array('err_code' => false, 'data' => array_merge($store, $foodshop));
	}

	public function book_save()
	{
		$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
		$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
		if (empty($name)) {
			exit(json_encode(array('err_code' => true, 'msg' => '您的姓名不能为空')));
		}
		if (empty($phone)) {
			exit(json_encode(array('err_code' => true, 'msg' => '您的电话不能为空')));
		}
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;

		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) exit(json_encode($foodshop));
		$foodshop = $foodshop['data'];

		$now_time = time() + $foodshop['advance_time'] * 60;
		$book_time = isset($_POST['book_time']) ? htmlspecialchars($_POST['book_time']) : 0;
		if (empty($book_time)) {
			exit(json_encode(array('err_code' => true, 'msg' => '预订时间不能为空')));
		}
		$book_time = strtotime($book_time);
		if ($now_time > $book_time) {
			exit(json_encode(array('err_code' => true, 'msg' => '至少提前' . $foodshop['advance_time'] . '分钟预定')));
		}
		$table_type = isset($_POST['table_type']) ? intval($_POST['table_type']) : 0;
		$table_type_data = M('Foodshop_table_type')->where(array('store_id' => $store_id, 'id' => $table_type))->find();
		if (empty($table_type_data)) {
			exit(json_encode(array('err_code' => true, 'msg' => '没有您选择的桌位')));
		}
		$book_num = isset($_POST['book_num']) ? intval($_POST['book_num']) : 2;
		if ($book_num < $table_type_data['min_people']) {
			exit(json_encode(array('err_code' => true, 'msg' => '请您选择更少人数的桌位')));
		}
		if ($table_type_data['is_add'] == 0 && $book_num > $table_type_data['max_people']) {
			exit(json_encode(array('err_code' => true, 'msg' => '请您选择更多人数的桌位')));
		}

		//
		$orders = M('Foodshop_order')->field(true)->where(array('store_id' => $store_id, 'book_time' => $book_time, 'table_type' => $table_type, 'status' => array('in', array(1, 2))))->select();
		if (count($orders) >= $table_type_data['num']) {
			exit(json_encode(array('err_code' => true, 'msg' => '该时段该桌台已订满')));
		}
		$tids = array();
		foreach ($orders as $order) {
			$tids[] = $order['table_id'];
		}
		$tables = M('Foodshop_table')->field(true)->where(array('store_id' => $store_id, 'tid' => $table_type))->select();
		$table_id = 0;
		foreach ($tables as $table) {
			if (!in_array($table['id'], $tids)) {
				$table_id = $table['id'];
				break;
			}
		}
		$sex = isset($_POST['sex']) ? intval($_POST['sex']) : 1;
		$note = isset($_POST['note']) ? htmlspecialchars($_POST['note']) : '';

		$data = array('mer_id' => $foodshop['mer_id'], 'uid' => $this->user_session['uid'], 'store_id' => $store_id, 'name' => $name, 'phone' => $phone, 'sex' => $sex, 'book_num' => $book_num, 'book_time' => $book_time, 'table_id' => $table_id, 'table_type' => $table_type, 'book_price' => $table_type_data['deposit'], 'note' => $note);
		$data['create_time'] = time();
		$data['price'] = $table_type_data['deposit'];
		$data['is_book_pay'] = 3; //支付页面获知是定金不能使用折扣
		$data['real_orderid'] = date('ymdhis').substr(microtime(),2,8-strlen($this->user_session['uid'])).$this->user_session['uid'];//real_orderid
		if ($order_id = D('Foodshop_order')->save_order($data)) {
			$pay_order_param = array(
					'business_type' => 'foodshop',
					'business_id' => $order_id,
					'order_name' => '餐饮订单',
					'uid' => $this->user_session['uid'],
					'store_id' => $store_id,
					'total_money' => $table_type_data['deposit'],
					'wx_cheap' => 0,
			);
			$result = D('Plat_order')->add_order($pay_order_param);
			if($result['error_code']){
				exit(json_encode(array('err_code' => true, 'msg' => '订座失败，稍后重试！')));
			}else{
				//exit(json_encode(array('err_code' => false, 'url' => U('Foodshop/book_success', array('order_id' => $order_id)))));
				exit(json_encode(array('err_code' => false, 'url' => U('Pay/check', array('order_id' => $result['order_id'], 'type' => 'plat')))));
				redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'foodshop')));
			}

		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '订座失败，稍后重试！')));
		}

	}

	public function book_success()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = M('Foodshop_order')->field(true)->where(array('uid' => $this->user_session['uid'], 'order_id' => $order_id))->find();
		if (empty($order)) {
			$this->error_tips('不合法的订单！');
			exit;
		}
		$foodshop = $this->now_store($order['store_id']);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit;
		}
		$foodshop = $foodshop['data'];

		$cart_detail = M('Foodshop_order_temp')->field('goods_id')->where(array('order_id' => $order_id))->limit(1)->find();
		$goods_detail = M('Foodshop_order_detail')->field('goods_id')->where(array('order_id' => $order_id))->limit(1)->find();
		$plat_order =D('Plat_order')->get_order_by_business_id(array('business_id'=>$order_id,'order_type'=>'foodshop'));
		$order['is_own'] = $plat_order['is_own'];
		$order['pay_type'] = $plat_order['pay_type'];
		if ($cart_detail || $goods_detail) {
			redirect(U('Foodshop/order_detail', array('order_id' => $order_id)));
			exit;
		}
		$table = M('Foodshop_table')->field(true)->where(array('store_id' => $foodshop['store_id'], 'id' => $order['table_id']))->find();
		$table_name = isset($table['name']) ? $table['name'] : '';
		$table_type = M('Foodshop_table_type')->field(true)->where(array('store_id' => $foodshop['store_id'], 'id' => $order['table_type']))->find();
		$order['table_type_name'] = isset($table_type['name']) ? $table_name . '(' . $table_type['min_people'] . '-' . $table_type['max_people'] . '人)' : $table_name;
		if ($order['book_time']) {
		    $order['book_time_show'] = date('m月d日 H:i', $order['book_time']);
		} else {
		    $order['book_time_show'] = '扫码点餐';
		}
		
		$this->assign('order', $order);
		$this->assign('store', $foodshop);
		$now_merchant = D('Merchant')->get_info($order['mer_id']);

		$this->assign('now_merchant',$now_merchant );
		$this->display();
	}

	public function get_data()
	{
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 2;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) exit(json_encode($foodshop));
		$foodshop = $foodshop['data'];

		$book_time = isset($_POST['book_time']) ? htmlspecialchars($_POST['book_time']) : 0;
		if (empty($book_time)) {
			exit(json_encode(array('err_code' => true, 'msg' => '预订时间不能为空')));
		}

		$book_time = strtotime($book_time);

		$book_num = isset($_POST['book_num']) ? intval($_POST['book_num']) : 2;
		$table_type = isset($_POST['table_type']) ? intval($_POST['table_type']) : 0;
		$return = $this->format_data($foodshop, $book_time, $book_num, $table_type);
		exit(json_encode($return));
	}



	/**
	 * @param array $foodshop	店铺的详情
	 * @param int $book_time	预定时间，时间戳格式
	 * @param int $book_num		预订人数
	 * @param int $table_type	桌台类型ID
	 * @return array
	 */
	private function format_data($foodshop, $book_time, $book_num, $table_type)
	{
		$store_id = $foodshop['store_id'];
		//根据预订人数查找对应的桌台
		$table_type_data = M('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id))->select();
		$type_list = array();
		$type_ids = array();
		foreach ($table_type_data as $type) {
			if ($type['min_people'] <= $book_num && ($type['max_people'] >= $book_num || $type['is_add'] == 1)) {
				$type_list[$type['id']] = $type;
				$type_ids[] = $type['id'];
			}
		}

		if ($type_ids) {
			if (!in_array($table_type, $type_ids)) {
				$table_type = $type_ids[0];
			}
		} else {
			return array('err_code' => true, 'msg' => '没有可供选择的桌台');
		}

		//检验已选的桌台类型的各个时间点的预订情况
		$order_list = M('Foodshop_order')->field(true)->where(array('table_type' => $table_type, 'book_time' => array('gt', time()), 'status' => array('in', array(1, 2))))->select();
		$order_table_list = array();
		foreach ($order_list as $order) {
			$order_table_list[date('YmdHi', $order['book_time'])][] = $order;
		}
// 		$order_table_list = isset($temp[$table_type]) ? $temp[$table_type] : '';
		$order_date_count = array();
		if ($order_table_list) {
			foreach ($order_table_list as $index => $row) {
				if ($type_list[$table_type]['num'] <= count($row)) {
					$order_date_count[$index] = 1;
				} else {
					$order_date_count[$index] = 0;
				}
			}
		}

		$weeks = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
		$time_list = array();
		$day_list = array();
		$now_time = time() + $foodshop['advance_time'] * 60;//开始预约时间
		$foodshop['book_time'] = $foodshop['book_time'] > 0 ? $foodshop['book_time'] : 60;
		$loop_time = $foodshop['book_time'] * 60;//预订时间间隔

		$start_time = $foodshop['book_start'];
		$stop_time = $foodshop['book_stop'];
		if ($start_time == '00:00:00' && $stop_time == '00:00:00') {
			$stop_time = '23:59:59';
		}

		$select_date_flag = false;
		for ($d = 0; $d <= $foodshop['book_day']; $d++) {
			$index = date('Ymd', strtotime("+{$d} day"));
			//日期的列表
			$day_list[$index] = array('date' => date('Y-m-d', strtotime("+{$d} day")), 'title' => $weeks[date('w', strtotime("+{$d} day"))], 'day' => '<i class="m">' . date('m', strtotime("+{$d} day")) . '</i>-<i class="d">' . date('d', strtotime("+{$d} day")) . '</i>');

			//每日可供预约的时间点
			$this_start_time = strtotime(date('YmdHi', strtotime(date('Y-m-d ', strtotime("+{$d} day")) . $start_time)));
			$this_stop_time = strtotime(date('YmdHi', strtotime(date('Y-m-d ', strtotime("+{$d} day")) . $stop_time)));
			$temp = null;
			if ($this_start_time < $this_stop_time) {

				$temp['date'] = date('Y-m-d', $this_start_time);
				for ($t = $this_start_time; $t <= $this_stop_time; $t += $loop_time) {
					$class = '';
					if ($t < $now_time) {
						$class = 'End';
						$t_a = array('class' => 'End', 'time' => date('H:i', $t));
					} else {
						if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
							$class = 'End';
							$t_a = array('class' => 'End', 'time' => date('H:i', $t));
						} else {
							$t_a = array('class' => '', 'time' => date('H:i', $t));
						}
					}
					if ($book_time == $t) {
						if ($class == 'End') {
							return array('err_code' => true, 'msg' => '您选择餐桌在该时间不能再预订了！');
							$book_time += $loop_time;
						} else {
							$select_date_flag = true;
							$t_a = array('class' => 'on', 'time' => date('H:i', $t));
						}
					}
					$temp['time_list'][] = $t_a;
				}
				$time_list[$index] = $temp;
			} else {
				$stop_time_t = strtotime(date('Y-m-d ', strtotime("+{$d} day")) . '23:59:59');
				$temp['date'] = date('Y-m-d', $this_start_time);
				for ($t = $this_start_time; $t <= $stop_time_t; $t += $loop_time) {
					$class = '';
					if ($t < $now_time) {
						$class = 'End';
						$t_a = array('class' => 'End', 'time' => date('H:i', $t));
					} else {
						if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
							$class = 'End';
							$t_a = array('class' => 'End', 'time' => date('H:i', $t));
						} else {
							$t_a = array('class' => '', 'time' => date('H:i', $t));
						}
					}
					if ($book_time == $t) {
						if ($class == 'End') {
							return array('err_code' => true, 'msg' => '您选择餐桌在该时间不能再预订了！');
							$book_time += $loop_time;
						} else {
							$select_date_flag = true;
							$t_a = array('class' => 'on', 'time' => date('H:i', $t));
						}
					}
					$temp['time_list'][] = $t_a;
				}
				$time_list[$index] = $temp;
				$d_t = $d + 1;
				if ($d_t < $foodshop['book_day']) {
					$start_time_t = strtotime(date('Y-m-d ', strtotime("+{$d_t} day")) . '00:00:00');
					$stop_time_t = strtotime(date('Y-m-d ', strtotime("+{$d_t} day")) . $stop_time);
					$temp['date'] = date('Y-m-d', $start_time_t);
					for ($t = $start_time_t; $t <= $stop_time_t; $t += $loop_time) {
						$class = '';
						if ($t < $now_time) {
							$class = 'End';
							$t_a = array('class' => 'End', 'time' => date('H:i', $t));
						} else {
							if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
								$class = 'End';
								$t_a = array('class' => 'End', 'time' => date('H:i', $t));
							} else {
								$t_a = array('class' => '', 'time' => date('H:i', $t));
							}
						}
						if ($book_time == $t) {
							if ($class == 'End') {
								return array('err_code' => true, 'msg' => '您选择餐桌在该时间不能再预订了！');
								$book_time += $loop_time;
							} else {
								$select_date_flag = true;
								$t_a = array('class' => 'on', 'time' => date('H:i', $t));
							}
						}
						$temp['time_list'][] = $t_a;
					}
					$time_list[date('Ymd', strtotime("+{$d_t} day"))] = $temp;
				}
			}
		}
		if ($select_date_flag) {
			$data = array('m' => date('m', $book_time), 'd' => date('d', $book_time), 'w' => $this->weeks[date('w', $book_time)], 'o' => date('H:i', $book_time), 'selectdate' => date('Y-m-d', $book_time));
			$data['err_code'] = false;
			$data['table_list'] = $type_list;
			$data['table_type'] = $table_type;
			$data['day_list'] = $day_list;
			$data['time_list'] = $time_list;
			$data['book_time'] = date('Y-m-d H:i', $book_time);
			$data['book_num'] = $book_num;
			$data['book_price'] = floatval($type_list[$table_type]['deposit']);
			return $data;//array('err_code' => false, 'table_list' => $type_list, 'table_type' => $table_type, 'day_list' => $day_list, 'time_list' => $time_list);
		} else {
			return array('err_code' => true, 'msg' => '没有可供预约的时间');
		}

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

	/**
	 * 取消预订
	 */
	public function cancel_book()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

		$cancel_reason = isset($_POST['cancel_reason']) ? htmlspecialchars($_POST['cancel_reason']) : '';
		if (empty($cancel_reason)) {
			exit(json_encode(array('err_code' => true, 'msg' => '取消理由不能为空！')));
		}
		if (empty($this->user_session)) {
			exit(json_encode(array('err_code' => true, 'msg' => '请先进行登录！')));
		}

		$now_order = D('Foodshop_order')->field(true)->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find();
		if ($now_order['status'] > 1) {
			exit(json_encode(array('err_code' => true, 'msg' => '您不能取消订单了')));
		}
		$foodshop = $this->now_store($now_order['store_id']);
		if ($foodshop['err_code']) {
// 			exit(json_encode($foodshop));
// 			$this->error_tips($foodshop['msg']);
// 			exit;
		}
		$foodshop = $foodshop['data'];

		if ($now_order['book_time'] - time() < $foodshop['cancel_time'] * 60) {
			exit(json_encode(array('err_code' => true, 'msg' => '当前时间已经超出可取消的时间，现在已经不能取消了！')));
		}

		if (D('Foodshop_order')->where(array('order_id' => $order_id))->save(array('cancel_time' => time(), 'cancel_reason' => $cancel_reason))) {
			exit(json_encode(array('err_code' => false, 'url' => U('My/plat_order_refund', array('order_id' => $now_order['order_id'], 'business_type' => 'foodshop')))));
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '取消时出现错误稍后重试！')));
		}

	}

	public function show_menu()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit;
		}
		$foodshop = $foodshop['data'];

		$lists = D('Foodshop_goods')->get_list_by_storeid($foodshop['store_id']);
		$goods_detail = array();
		foreach ($lists as $rowset) {
			foreach ($rowset['goods_list'] as $row) {
				if ($row['list']) {
					$goods_detail[$row['goods_id']] = array();
					foreach ($row['list'] as $index => $r) {
						$goods_detail[$row['goods_id']][$index] = array('price' => $r['price'], 'stock_num' => $r['stock_num']);
					}
				}
			}
		}
		$this->assign('goods_list', $lists);
		$this->assign('all_goods', json_encode($goods_detail));
		$this->assign('store', $foodshop);
		if ($foodshop['template']) {
		    $this->display('show_menupic');
		} else {
		    $this->display();
		}
	}

    public function menu()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $order_id = isset($_GET['order_id']) ? htmlspecialchars(trim($_GET['order_id'])) : 0;
        $foodshop = $this->now_store($store_id);
        if ($foodshop['err_code']) {
            $this->error_tips($foodshop['msg']);
            exit();
        }
        $foodshop = $foodshop['data'];

        $now_order = M('Foodshop_order')->where(array('real_orderid' => $order_id, 'store_id' => $store_id))->find();
        if (empty($now_order)) {
            $this->error_tips('订单信息不正确');
        }

        if ($now_order['status'] < 1) {
            $this->error_tips('未交预订金，不能点菜');
        }
        $isHot = 1;
        if ($foodshop['template']) {
            $isHot = 0;
        }
        $lists = D('Foodshop_goods')->get_list_by_storeid($foodshop['store_id'], $isHot);

        $goods_detail = array();
        foreach ($lists as $rowset) {
            foreach ($rowset['goods_list'] as $row) {
                if ($row['list']) {
                    $goods_detail[$row['goods_id']] = array();
                    foreach ($row['list'] as $index => $r) {
                        $propertiesArr = array();
                        if (isset($r['properties']) && $r['properties']) {
                            foreach ($r['properties'] as $vr) {
                                $propertiesArr[] = array('id' => $vr['id'], 'num' => $vr['num']);
                            }
                        }
                        $goods_detail[$row['goods_id']][$index] = array('price' => $r['price'], 'stock_num' => $r['stock_num'], 'properties' => $propertiesArr);
                    }
                }
            }
        }
        
//         echo '<pre/>';
//         print_r($goods_detail);die;
        // 记录是不是刷新detail页面
        $_SESSION['is_refresh_order_detail'] = 0;
        $productCart = json_decode(cookie('foodshop_cart_' . $store_id . '_order_' . $now_order['order_id']), true);
        if (empty($productCart)) {
            $goods_list = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $now_order['order_id'], 'store_id' => $store_id, 'uid' => $this->user_session['uid']))->order('id ASC')->select();
            $cookie_data = array();
            $tempPakeage = array();
            foreach ($goods_list as $go) {
                if (empty($go['package_id'])) {
                    $t_cookie = array(
                        'goods_id' => $go['goods_id'],
                        'type' => 'only',
                        'num' => $go['num'],
                        'name' => $go['name'],
                        'price' => floatval($go['price'])
                    );
                    $params = array();
                    if ($go['spec_id']) {
                        $params = D('Foodshop_goods')->format_spec_ids($go, $params);
                    }
                    if ($go['spec']) {
                        $params = D('Foodshop_goods')->format_properties_ids($go, $params);
                    }
                    $t_cookie['params'] = $params;
                    $cookie_data[] = $t_cookie;
                } else {
                    if ($go['ishide'] == 1) {
                        $tempPakeage[$go['id']] = array('goods_id' => $go['package_id'], 'name' => $go['name'], 'type' => 'group', 'num' => $go['num'], 'price' => floatval($go['price']), 'index' => $go['spec']);
                    } elseif (isset($tempPakeage[$go['fid']])) {
                        $tempPakeage[$go['fid']]['params'][] = array('goods_id' => $go['goods_id'], 'name' => $go['name'], 'price' => floatval($go['price']), 'unit' => $go['unit']);
                    } elseif (isset($tempPakeage[$go['spec']])) {
                        $tempPakeage[$go['spec']]['params'][] = array('goods_id' => $go['goods_id'], 'name' => $go['name'], 'price' => floatval($go['price']), 'unit' => $go['unit']);
                    } else {
                        $package = D('Foodshop_goods_package')->field(true)->where(array('id' => $go['package_id']))->find();
                        $t_cookie = array('goods_id' => $go['package_id'], 'name' => $package['name'], 'type' => 'group', 'num' => $go['num'], 'price' => floatval($package['price']), 'index' => $go['spec']);
                        $t_cookie['params'] = array('goods_id' => $go['goods_id'], 'name' => $go['name'], 'price' => floatval($go['price']), 'unit' => $go['unit']);
                        $tempPakeage[$go['spec']] = $t_cookie;
                    }
                }
            }
            foreach ($tempPakeage as $pak) {
                $cookie_data[] = $pak;
            }
            cookie('foodshop_cart_' . $store_id . '_order_' . $now_order['order_id'], json_encode($cookie_data));
        }
        if ($foodshop['template']) {
            $g_list = D('Foodshop_goods')->field(true)->where(array('store_id' => $store_id, 'status' => 1, 'is_hot' => 1))->order('show_type DESC, sort DESC, goods_id ASC')->select();
            $goods_image_class = new foodshop_goods_image();
            foreach ($g_list as &$row) {
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
                if (C('config.open_extra_price')) {
                    $row['extra_pay_price'] = floatval($row['extra_pay_price']);
                } else {
                    $row['extra_pay_price'] = 0;
                }
                $row['extra_price_name'] = C('config.extra_price_alias_name');

                if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
                    $row['price'] = floatval($row['seckill_price']);
                    $row['is_seckill_price'] = true;
                } else {
                    $row['price'] = floatval($row['price']);
                }
                $row['old_price'] = floatval($row['old_price']);
                $row['seckill_price'] = floatval($row['seckill_price']);
                $tmp_pic_arr = explode(';', $row['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $row['pic_arr'][$key]['title'] = $value;
                    $row['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value);
                }
            }
//             $bigHotList = array_slice($g_list, 0, 2);
//             $this->assign('bigHotList', $bigHotList);
            $this->assign('hot_list', $g_list);
            $this->assign('order', $now_order);
            $goods_package = D('Foodshop_goods_package')->where(array('store_id' => $store_id,'status' => 1))->order('`id` ASC')->select();
            $goods_image_class = new foodshop_goods_image();
            if ($goods_package) {
                foreach($goods_package as &$value){
                    $value['price'] = floatval($value['price']);
                    $value['goods_id'] = 0;
                    $value['unit'] = '份';
                    if (! empty($value['image'])) {
                        $value['image'] = $goods_image_class->get_image_by_path($value['image'], 's');
                    }
                }
                $lists[] = array('goods_list' => $goods_package, 'sort_name' => '团购套餐', 'sort_id' => -1, 'store_id' => $store_id);
            }
            $this->assign('goods_list', $lists);
            $this->assign('all_goods', json_encode($goods_detail));
            $this->assign('store', $foodshop);
            $this->display('menu_pic');
        } else {
            $this->assign('order', $now_order);
            $goods_package = D('Foodshop_goods_package')->where(array('store_id' => $store_id,'status' => 1))->order('`id` ASC')->select();
            if ($goods_package) {
                foreach($goods_package as &$value){
                    $value['price'] = floatval($value['price']);
                    $value['goods_id'] = 0;
                }
                $lists[] = array('goods_list' => $goods_package, 'sort_name' => '团购套餐', 'sort_id' => -1, 'store_id' => $store_id);
            }
            $this->assign('goods_list', $lists);
            $this->assign('all_goods', json_encode($goods_detail));
            $this->assign('store', $foodshop);
            $this->display();
        }
    }

    public function searchGoods()
    {
        $store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
        $order_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id'] : 0;

        $keyword = isset($_REQUEST['keyword']) ? htmlspecialchars(trim($_REQUEST['keyword'])) : '';
        $foodshop = $this->now_store($store_id);
        if ($foodshop['err_code']) {
            $this->error_tips($foodshop['msg']);
            exit();
        }
        $foodshop = $foodshop['data'];

		$where=array( 'store_id' => $store_id, 'uid' => $this->user_session['uid']);
		$where['_string'] = "real_orderid={$order_id} OR order_id={$order_id}";
		$now_order = M('Foodshop_order')->where($where)->find();
		if (empty($now_order)) {
            $this->error_tips('订单信息不正确');
        }

        if ($now_order['status'] < 1) {
            $this->error_tips('未交预订金，不能点菜');
        }
        $isHot = 1;
        if ($foodshop['template']) {
            $isHot = 0;
        }
        $lists = D('Foodshop_goods')->get_list_by_storeid($foodshop['store_id'], $isHot, $keyword);

        $goods_detail = array();
        foreach ($lists as $rowset) {
            foreach ($rowset['goods_list'] as $row) {
                if ($row['list']) {
                    $goods_detail[$row['goods_id']] = array();
                    foreach ($row['list'] as $index => $r) {
                        $goods_detail[$row['goods_id']][$index] = array('price' => $r['price'], 'stock_num' => $r['stock_num']);
                    }
                }
            }
        }

        // 记录是不是刷新detail页面
        $_SESSION['is_refresh_order_detail'] = 0;
        $productCart = json_decode(cookie('foodshop_cart_' . $store_id . '_order_' . $order_id), true);
        if (empty($productCart)) {
            $goods_list = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->select();
            $cookie_data = array();
            foreach ($goods_list as $go) {
                $t_cookie = array(
                    'goods_id' => $go['goods_id'],
                    'num' => $go['num'],
                    'name' => $go['name'],
                    'price' => floatval($go['price'])
                );
                $params = array();
                if ($go['spec_id']) {
                    $params = D('Foodshop_goods')->format_spec_ids($go, $params);
                }
                if ($go['spec']) {
                    $params = D('Foodshop_goods')->format_properties_ids($go, $params);
                }
                $t_cookie['params'] = $params;
                $cookie_data[] = $t_cookie;
            }
            cookie('foodshop_cart_' . $store_id . '_order_' . $order_id, json_encode($cookie_data));
        }
        $tWhere = array('store_id' => $store_id, 'status' => 1, 'is_hot' => 1);
        if ($keyword) {
            $tWhere['name'] = array('LIKE', '%' . $keyword . '%');
        }
        $g_list = D('Foodshop_goods')->field(true)->where($tWhere)->order('show_type DESC, sort DESC, goods_id ASC')->select();
        $goods_image_class = new foodshop_goods_image();
        foreach ($g_list as &$row) {
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
            if (C('config.open_extra_price')) {
                $row['extra_pay_price'] = floatval($row['extra_pay_price']);
            } else {
                $row['extra_pay_price'] = 0;
            }
            $row['extra_price_name'] = C('config.extra_price_alias_name');

            if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
                $row['price'] = floatval($row['seckill_price']);
                $row['is_seckill_price'] = true;
            } else {
                $row['price'] = floatval($row['price']);
            }
            $row['old_price'] = floatval($row['old_price']);
            $row['seckill_price'] = floatval($row['seckill_price']);
            $tmp_pic_arr = explode(';', $row['image']);
            foreach ($tmp_pic_arr as $key => $value) {
                $row['pic_arr'][$key]['title'] = $value;
                $row['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value);
            }
        }
        $this->assign('hot_list', $g_list);
        $this->assign('order', $now_order);
        $this->assign('goods_list', $lists);
        $this->assign('all_goods', json_encode($goods_detail));
        $this->assign('store', $foodshop);
        $this->assign('keyword', $keyword);
        $this->display();
    }

    public function show_detail()
    {
        $goods_id = isset($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
        $goods = D('Foodshop_goods')->field(true)->where(array('goods_id' => $goods_id))->find();
        if ($goods['seckill_type'] == 1) {
            $now_time = date('H:i');
            $open_time = date('H:i', $goods['seckill_open_time']);
            $close_time = date('H:i', $goods['seckill_close_time']);
        } else {
            $now_time = time();
            $open_time = $goods['seckill_open_time'];
            $close_time = $goods['seckill_close_time'];
        }
        $goods['is_seckill_price'] = false;
        $goods['o_price'] = floatval($goods['price']);
        if (C('config.open_extra_price')) {
            $goods['extra_pay_price'] = floatval($goods['extra_pay_price']);
        } else {
            $goods['extra_pay_price'] = 0;
        }
        $goods['extra_price_name'] = C('config.extra_price_alias_name');

        if ($open_time < $now_time && $now_time < $close_time && floatval($goods['seckill_price']) > 0) {
            $goods['price'] = floatval($goods['seckill_price']);
            $goods['is_seckill_price'] = true;
        } else {
            $goods['price'] = floatval($goods['price']);
        }
        $goods['old_price'] = floatval($goods['old_price']);
        $goods['seckill_price'] = floatval($goods['seckill_price']);
        $tmp_pic_arr = explode(';', $goods['image']);
        $goods_image_class = new foodshop_goods_image();
        foreach ($tmp_pic_arr as $key => $value) {
            $goods['pic_arr'][$key]['title'] = $value;
            $goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value);
        }

        $return = D('Foodshop_goods')->format_spec_value($goods);
        $goods['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
        $goods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
        if ($goods['properties_list'] || $goods['spec_list']) {
            $goods['is_properties'] = 1;
        }
        $this->assign('goods', $goods);
        $this->display();
    }

    public function goods_detail()
    {
        $order_id = isset($_GET['order_id']) ? htmlspecialchars(trim($_GET['order_id'])) : '';
        $goods_id = isset($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
        $now_order = M('Foodshop_order')->where(array('real_orderid' => $order_id))->find();
        if (empty($now_order)) {
            $this->error_tips('订单信息不正确');
        }
        $where = array('store_id' => $now_order['store_id'], 'status' => 1, 'goods_id' => $goods_id);
        $goods = D('Foodshop_goods')->field(true)->where($where)->find();
        $return = D('Foodshop_goods')->format_spec_value($goods);
        if ($goods['seckill_type'] == 1) {
            $now_time = date('H:i');
            $open_time = date('H:i', $goods['seckill_open_time']);
            $close_time = date('H:i', $goods['seckill_close_time']);
        } else {
            $now_time = time();
            $open_time = $goods['seckill_open_time'];
            $close_time = $goods['seckill_close_time'];
        }
        $goods['is_seckill_price'] = false;
        $goods['o_price'] = floatval($goods['price']);
        if (C('config.open_extra_price')) {
            $goods['extra_pay_price'] = floatval($goods['extra_pay_price']);
        } else {
            $goods['extra_pay_price'] = 0;
        }
        $goods['extra_price_name'] = C('config.extra_price_alias_name');

        if ($open_time < $now_time && $now_time < $close_time && floatval($goods['seckill_price']) > 0) {
            $goods['price'] = floatval($goods['seckill_price']);
            $goods['is_seckill_price'] = true;
        } else {
            $goods['price'] = floatval($goods['price']);
        }
        $goods['old_price'] = floatval($goods['old_price']);
        $goods['seckill_price'] = floatval($goods['seckill_price']);
        $tmp_pic_arr = explode(';', $goods['image']);
        $goods_image_class = new foodshop_goods_image();
        foreach ($tmp_pic_arr as $key => $value) {
            $goods['pic_arr'][$key]['title'] = $value;
            $goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value);
        }
        
        $today = date('Ymd');
        $goods['sell_day'] = $goods['update_stock_type'] ? $today : $goods['sell_day'];
        if ($today != $goods['sell_day'] && $goods['original_stock']) {
            $goods['stock_num'] = $goods['original_stock'];
        }
        $goods['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
        $goods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
        if ($goods['properties_list'] || $goods['spec_list']) {
            $goods['is_properties'] = 1;
        }
//         $goods['list'] = isset($return['list']) ? $return['list'] : '';
        $goods_detail = array();
        if (isset($return['list']) && $return['list']) {
            $goods_detail = array();
            foreach ($return['list'] as $index => $r) {
                $goods_detail[$index] = array('price' => $r['price'], 'stock_num' => $r['stock_num'], 'properties' => $r['properties']);
            }
        }
        $this->assign('all_goods', json_encode($goods_detail));
        $this->assign('goods', $goods);
        $this->assign('order', $now_order);
        $this->display();
    }

    public function ranking()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $foodshop = $this->now_store($store_id);
        if ($foodshop['err_code']) {
            $this->error_tips($foodshop['msg']);
            exit;
        }
        $foodshop = $foodshop['data'];
        $foodshop['ranking_num'] = $foodshop['ranking_num'] ? $foodshop['ranking_num'] : 10;
        $goods_image_class = new foodshop_goods_image();
        $where = array('store_id' => $store_id, 'status' => 1, 'is_must' => 0);
        $g_list = D('Foodshop_goods')->field('goods_id, name, image, sell_count, unit')->where($where)->order('sell_count DESC, sort DESC, goods_id ASC')->limit($foodshop['ranking_num'])->select();
        foreach ($g_list as &$row) {
            $tmp_pic_arr = explode(';', $row['image']);
            foreach ($tmp_pic_arr as $key => $value) {
                if (!isset($row['show_image'])) {
                    $image = $goods_image_class->get_image_by_path($value);
                    $row['show_image'] = $image['image'];
                }
            }
        }
        $this->assign('foodshop', $foodshop);
        $this->assign('goodsList', $g_list);
        $this->display();
    }


    public function myorder()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $foodshop = $this->now_store($store_id);
        if ($foodshop['err_code']) {
            $this->error_tips($foodshop['msg']);
            exit;
        }

        $sql = "SELECT g.goods_id, g.name, g.image, sum(od.num) AS num, g.unit, o.order_id,o.real_orderid FROM " . C('DB_PREFIX') . "foodshop_order AS o";
        $sql .= " INNER JOIN " . C('DB_PREFIX') . "foodshop_order_detail AS od ON o.order_id=od.order_id";
        $sql .= " INNER JOIN " . C('DB_PREFIX') . "foodshop_goods AS g ON g.goods_id=od.goods_id";
        $sql .= " WHERE g.store_id={$store_id} AND g.status=1 AND g.is_must=0 AND o.status>2 AND o.uid=" . $this->user_session['uid'] . " GROUP BY `g`.goods_id";
        $order = D()->query($sql);

        $goods_image_class = new foodshop_goods_image();
        foreach ($order as &$row) {
            $tmp_pic_arr = explode(';', $row['image']);
            foreach ($tmp_pic_arr as $key => $value) {
                if (!isset($row['show_image'])) {
                    $image = $goods_image_class->get_image_by_path($value);
                    $row['show_image'] = $image['image'];
                }
            }
        }
        $this->assign('foodshop', $foodshop['data']);
        $this->assign('orders', $order);
        $this->display();
    }



	/**
	 * 保存菜单
	 */
	public function order_detail()
	{
		$this->isLogin();
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$now_order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']), 3);
		if (empty($now_order)) {
			$this->error_tips('订单信息不正确');
		}
		redirect(U('Foodshop/sync', array('sync' => $now_order['real_orderid'])));
		exit;
		$store_id = $now_order['store_id'];
		$foodshop = $this->now_store($store_id, false);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit;
		}
		$foodshop = $foodshop['data'];
		//三个按钮的可用，默认不可能
		$is_pay = 0;
		$is_call_store = 0;
		$is_add_menu = 0;

		if ($now_order['status'] < 3) {
			$total = 0;
			$price = $now_order['price'] - $now_order['book_price'];//菜品的总价
			$total_price = $now_order['price'];
			$extra_price= 0;
			$productCart = json_decode(cookie('foodshop_cart_' . $store_id . '_order_' . $order_id), true);

			cookie('foodshop_cart_' . $store_id . '_order_' . $order_id, null);

			if ($now_order['running_state']) {//0：用户处理，1:用户不可处理;当是1的时候不能加减菜
				$productCart = null;
			}

//             echo '<pre/>';
//             print_r($productCart);
//             die;
			$new_goods_list = null;
			$new_package_list = null;
			if ($productCart) {
			    $packageTemp = array();
			    $productCart2 = array();
			    foreach ($productCart as $row) {
			        if ($row['type'] == 'only') {
			            $productCart2[] = $row;
			        } else {
			            $packageTemp[] = $row;
			        }
			    }
				$cart_data = D('Foodshop_goods')->format_cart($productCart2, $store_id, $order_id);
				if ($cart_data['err_code']) {
					$this->error_tips($cart_data['msg']);
				}
				$new_goods_list = $cart_data['data'];
			}


			$goods_list = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id, 'package_id' => 0))->select();
			$temp_list = array();
			foreach ($goods_list as $_row) {
				$_t_index = $_row['goods_id'];
				if (strlen($_row['spec']) > 0) {
					$_t_index = $_row['goods_id'] . '_' . md5($_row['spec']);
				}
				$temp_list[$_t_index] = $_row;
			}

			$goodsList = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id, 'package_id' => array('gt', 0)))->select();
			$tempList = array();
			foreach ($goodsList as $_row) {
			    $tempList[$_row['spec']][$_row['goods_id']] = $_row;
			}

			$tempDB = D('Foodshop_order_temp');
			$goodsDB = D('Foodshop_goods');
			foreach ($packageTemp as $pt) {
			    if (isset($tempList[$pt['index']])) {
			        $goods = array_shift($tempList[$pt['index']]);
			        if (floatval($pt['num']) != floatval($goods['num'])) {
			            $tempDB->where(array('spec' => $pt['index'], 'package_id' => $pt['goods_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save(array('num' => $pt['num']));
			        }
			    } else {
			        foreach ($pt['params'] as $gd) {
			            $gd['order_id'] = $order_id;
			            $gd['store_id'] = $store_id;
			            $gd['package_id'] = $pt['goods_id'];
			            $gd['spec'] = $pt['index'];
			            $gd['extra_price'] = 0;
			            $gd['num'] = $pt['num'];
			            $tempDB->add($gd);
			        }
			    }
			    unset($tempList[$pt['index']]);
			}
            $foodshopGoodsDB = D('Foodshop_goods');
			if ($new_goods_list) {
				foreach ($new_goods_list as $index => $new_row) {
					if (isset($temp_list[$index])) {
						if ($temp_list[$index]['num'] != $new_row['num']) {
							D('Foodshop_order_temp')->where(array('id' => $temp_list[$index]['id']))->save(array('num' => $new_row['num']));
							$new_row['num'] -= $temp_list[$index]['num'];
						}
						unset($temp_list[$index]);
					} else {
						$new_row['order_id'] = $order_id;
						$new_row['store_id'] = $store_id;
						$new_row['extra_price'] = empty($new_row['extra_price'])?0:$new_row['extra_price'];
						D('Foodshop_order_temp')->add($new_row);
					}
					//更新库存
					$foodshopGoodsDB->update_stock($new_row);
				}
			}
			//记录是不是刷新detail页面
			$is_refresh_order_detail = $_SESSION['is_refresh_order_detail'];
			$_SESSION['is_refresh_order_detail'] = 1;
			if ($temp_list && $now_order['running_state'] == 0 && empty($is_refresh_order_detail)) {
			    $del_ids = array();
			    foreach ($temp_list as $tmp) {
			        //更新库存
			        $foodshopGoodsDB->update_stock($tmp, 1);
			        $del_ids[] = $tmp['id'];
			    }
			    D('Foodshop_order_temp')->where(array('id' => array('in', $del_ids)))->delete();
			}

			if ($tempList && $now_order['running_state'] == 0 && empty($is_refresh_order_detail)) {
			    $del_ids = array();
			    foreach ($tempList as $index => $glist) {
			        foreach ($glist as $tmp) {
			            $del_ids[] = $tmp['id'];
			        }
			    }
			    D('Foodshop_order_temp')->where(array('id' => array('in', $del_ids)))->delete();
			}



			//预定信息
			$table = M('Foodshop_table_type')->field(true)->where(array('store_id' => $foodshop['store_id'], 'id' => $now_order['table_id']))->find();
			$table_name = isset($table['name']) ? $table['name'] : '';
			$table_type = M('Foodshop_table_type')->field(true)->where(array('store_id' => $foodshop['store_id'], 'id' => $now_order['table_type']))->find();
			$now_order['table_type_name'] = isset($table_type['name']) ? $table_name . '(' . $table_type['min_people'] . '-' . $table_type['max_people'] . '人)' : $table_name;
			$now_order['book_time_show'] = date('m月d日 H:i', $now_order['book_time']);

			$goods_detail_list = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->select();
			$new_goods_list = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->select();

			$package_list = array();
			$old_goods_list = array();
			foreach ($goods_detail_list as $new) {

				if ($new['package_id']) {
					if (isset($package_list[$new['package_id']])) {
						if (isset($package_list[$new['package_id']]['list'][$new['goods_id']])) {
							$package_list[$new['package_id']]['list'][$new['goods_id']]['num'] += $new['num'];
						} else {
							$package_list[$new['package_id']]['list'][$new['goods_id']] = $new;
						}

					} else {
						$package_list[$new['package_id']] = array('list' => array($new['goods_id'] => $new), 'name' => '', 'num' => 0, 'price' => 0);
					}
				} elseif ($new['is_must']) {
// 					$price += $new['price'] * $new['num'];
// 					$total += $new['num'];
				} else {
					$price += $new['price'] * $new['num'];
					$total += $new['num'];
					$extra_price +=$new['extra_price']*$new['num'];
					$old_goods_list[] = $new;
				}
			}
			$tList = $new_goods_list;
			$new_goods_list = array();
			foreach ($tList as $new) {
			    if ($new['package_id'] > 0) {
			        $tempIndex = $new['package_id'] . $new['spec'];
			        unset($new['spec']);
			        if (isset($package_list[$tempIndex])) {

			            if (isset($package_list[$tempIndex]['list'][$new['goods_id']])) {
			                $package_list[$tempIndex]['list'][$new['goods_id']]['num'] += $new['num'];
			            } else {
			                $package_list[$tempIndex]['list'][$new['goods_id']] = $new;
			            }
			        } else {
			            $package = D('Foodshop_goods_package')->field(true)->where(array('id' => $new['package_id']))->find();
			            $package_list[$tempIndex] = array('list' => array($new['goods_id'] => $new), 'name' => $package['name'], 'num' => $new['num'], 'price' => $package['price'], 'isNew' => 1);
			        }
			    } else {
    			    $price += $new['price'] * $new['num'];
    			    $total += $new['num'];
    			    $new_goods_list[] = $new;
			    }
			}
			if ($now_order['package_ids']) {
				$package_ids = json_decode($now_order['package_ids'], true);
// 				echo '<pre/>';
// 				print_r($package_list);die;
				$packages = D('Foodshop_goods_package')->field(true)->where(array('in' => array('id', $package_ids)))->select();
				foreach ($package_ids as $pid) {
					foreach ($packages as $p) {
						if ($pid == $p['id']) {
							$package_list[$pid]['num']++;
							$package_list[$pid]['price'] += $p['price'];
							$package_list[$pid]['name'] = $p['name'];
							$price += $p['price'];
							$total ++;
						}
					}
				}
			}



			$must_list = D('Foodshop_goods')->field(true)->where(array('store_id' => $now_order['store_id'], 'status' => 1, 'is_must' => 1))->select();
			$now_time = time();
			$save_data = array();
			foreach ($must_list as &$mgoods) {
				$mgoods['num'] = $now_order['book_num'];
				$price += $mgoods['price'] * $now_order['book_num'];
				$total += $now_order['book_num'];
			}

			if (in_array($now_order['status'], array(1, 2))) {
				if ($now_order['running_state'] == 0) {
					$is_add_menu = 1;
					if ($new_goods_list) {
						$is_call_store = 1;
					}
				}
			}
			if ($now_order['status'] == 0 || (($old_goods_list || $package_list) && empty($new_goods_list))) {
				$is_pay = 1;
			}
			$this->assign('goods_list', $new_goods_list);
		} else {
			$goods_detail_list = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->select();
			$price = $now_order['price'];

			$package_list = array();
			$must_list = array();
			$old_goods_list = array();
			foreach ($goods_detail_list as $new) {
				if ($new['package_id']) {
					if (isset($package_list[$new['package_id']])) {
						if (isset($package_list[$new['package_id']]['list'][$new['goods_id']])) {
							$package_list[$new['package_id']]['list'][$new['goods_id']]['num'] += $new['num'];
						} else {
							$package_list[$new['package_id']]['list'][$new['goods_id']] = $new;
						}
					} else {
						$package_list[$new['package_id']] = array('list' => array($new['goods_id'] => $new), 'name' => '', 'num' => 0, 'price' => 0);
					}
				} elseif ($new['is_must']) {
					$must_list[] = $new;
				} else {
					$old_goods_list[] = $new;
				}
			}
			if ($now_order['package_ids']) {
				$package_ids = json_decode($now_order['package_ids'], true);
				$packages = D('Foodshop_goods_package')->field(true)->where(array('in' => array('id', $package_ids)))->select();
				foreach ($package_ids as $pid) {
					foreach ($packages as $p) {
						if ($pid == $p['id']) {
							$package_list[$pid]['num']++;
							$package_list[$pid]['price'] += $p['price'];
							$package_list[$pid]['name'] = $p['name'];
						}
					}
				}
			}
		}

		$this->assign(array('is_pay' => $is_pay, 'is_add_menu' => $is_add_menu, 'is_call_store' => $is_call_store));
		$this->assign('old_goods_list', $old_goods_list);
		$this->assign('package_list', $package_list);
		$this->assign('must_list', $must_list);
		$this->assign('order', $now_order);
		$this->assign('store', $foodshop);
		$this->assign('price', $price);
		$this->assign('extra_price', $extra_price);
		$this->display();
	}


	public function queue()
	{
		$this->isLogin();
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit;
		}
		$foodshop = $foodshop['data'];

		if ($foodshop['is_queue'] == 0) {
			$this->error_tips('该店铺不支持排号');
		}
		if ($foodshop['queue_is_open'] == 0) {
			$notice = M('Foodshop_queue_notice')->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0))->find();
			$this->assign('notice', $notice);
		}

		$foodshop_queue_db = M('Foodshop_queue');
		$queue_data = array();
		if ($queue = $foodshop_queue_db->field(true)->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0))->find()) {

// 			$type_data = M('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id, 'id' => $queue['table_type']))->find();

			$count = $foodshop_queue_db->where(array('id' => array('lt', $queue['id']), 'table_type' => $queue['table_type'], 'status' => 0))->count();
			if (empty($count)) {
				$queue['wait'] = 0;
			} else {
				$queue['wait'] = $count;
			}
			$now_time = time();
			if ($queue['use_time'] > $now_time) {
				$queue['wait_time'] = '预计等待   <i>' . ceil(($queue['use_time'] - $now_time) / 60) . '</i>分钟';
			} else {
				$queue['wait_time'] = '请耐心等待店员叫号';
			}

			$queue['use_time'] = $now_time;
			if (empty($queue['wait'])) {
				$queue['wait_time'] = '请耐心等待店员叫号';
			}
			$queue['create_time'] = date('Y-m-d H:i', $queue['create_time']);
			$queue_data = $queue;
		}



		$queue_list = M('Foodshop_queue')->field(true)->where(array('status' => 0, 'store_id' => $store_id))->select();
		$temp = array();
		foreach ($queue_list as $queue) {
			if ($queue_data && $queue_data['table_type'] == $queue['table_type']) {
				if ($queue_data['id'] >= $queue['id']) {
					if (isset($temp[$queue['table_type']])) {
						$temp[$queue['table_type']] ++;
					} else {
						$temp[$queue['table_type']] = 1;
					}
				}
			} else {
				if (isset($temp[$queue['table_type']])) {
					$temp[$queue['table_type']] ++;
				} else {
					$temp[$queue['table_type']] = 1;
				}
			}
		}

		$table_type_data = D('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id))->select();
		foreach ($table_type_data as &$row) {
			$row['wait_time'] = $row['wait'] = 0;
			if (isset($temp[$row['id']]) && $foodshop['queue_is_open'] == 1) {
				$row['wait'] = $temp[$row['id']];
				$row['wait_time'] = ceil($temp[$row['id']] / $row['num']) * $row['use_time'];
			}
			if ($queue_data && $queue_data['table_type'] == $row['id']) {
				$queue_data['name'] = $row['name'];
			}
		}
		$this->assign('queue_list', $table_type_data);
		$this->assign('store', $foodshop);
		$this->assign('queue', $queue_data);
		$this->display();
	}


	public function queue_save()
	{
		if (empty($this->user_session)) {
			exit(json_encode(array('err_code' => true, 'msg' => '先登录，再取消')));
		}
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		$table_type = isset($_POST['table_type']) ? intval($_POST['table_type']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			exit(json_encode($foodshop));
		}
		$foodshop = $foodshop['data'];
		$table_type_data = M('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id, 'id' => $table_type))->find();
		if (empty($table_type_data)) {
			exit(json_encode(array('err_code' => true, 'msg' => '不存在的桌台类型')));
		}
		$foodshop_queue_db = M('Foodshop_queue');
		if ($queue = $foodshop_queue_db->field(true)->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0))->find()) {
			exit(json_encode(array('err_code' => true, 'msg' => '您已经取过号了，不要重新取号，如果重新取号，请先取消已经取的号')));
		}

		$fp = fopen('./runtime/' . md5(C('config.site_url') . $table_type) . '_lock.txt', "w+");
		flock($fp, LOCK_EX);
		if ($new_queue = $foodshop_queue_db->field(true)->where(array('store_id' => $store_id, 'table_type' => $table_type))->order('id DESC')->find()) {
			$number = str_replace($table_type_data['number_prefix'], '', $new_queue['number']);
		} else {
			$number = 0;
		}
		$number = intval($number) + 1;
		$new_number = $table_type_data['number_prefix'] . $number;
		$now_time = time();

		$num = isset($_POST['num']) ? intval($_POST['num']) : 1;
		$num = max($num, 1);

		$count = $foodshop_queue_db->where(array('store_id' => $store_id, 'table_type' => $table_type, 'status' => 0))->count();

		$use_time =  $now_time + ceil(($count + 1) / $table_type_data['num']) * $table_type_data['use_time'] * 60;

		$data = array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'table_type' => $table_type, 'number' => $new_number, 'create_time' => $now_time, 'use_time' => $use_time, 'num' => $num, 'status' => 0);
		$queue_id = $foodshop_queue_db->add($data);

		flock($fp, LOCK_UN);
		fclose($fp);

		if ($queue_id) {
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$href = C('config.site_url').'/wap.php?c=Foodshop&a=queue&store_id=' . $store_id;
			$model->sendTempMsg('OPENTM205984119', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '尊敬的用户您好，您的排号信息如下', 'keyword1' => $new_number, 'keyword2' => date('Y.m.d H:i'), 'keyword3' => $count + 1, 'remark' => '感谢您的支持！'), $foodshop['mer_id']);
			exit(json_encode(array('err_code' => false, 'number' => $new_number, 'time' => $table_type_data['use_time'])));
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '稍后重试')));
		}
	}
	public function queue_cancel()
	{
		if (empty($this->user_session)) {
			exit(json_encode(array('err_code' => true, 'msg' => '先登录，再取消')));
		}
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			exit(json_encode($foodshop));
		}
		if (!(M('Foodshop_queue')->field(true)->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0))->find())) {
			exit(json_encode(array('err_code' => true, 'msg' => '您已经没有在等待的号码了！')));
		}
		if (M('Foodshop_queue')->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid']))->save(array('status' => 1))) {
			exit(json_encode(array('err_code' => false, 'msg' => 'ok')));
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '取消失败，稍后重试')));
		}
	}

	public function notice_save()
	{
		if (empty($this->user_session)) {
			exit(json_encode(array('err_code' => true, 'msg' => '先登录，再取消')));
		}
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			exit(json_encode($foodshop));
		}
		if ($notice = M('Foodshop_queue_notice')->field(true)->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0))->find()) {
			exit(json_encode(array('err_code' => true, 'msg' => '已经设置了提醒')));
		} else {
			$data = array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0, 'openid' => $this->user_session['openid'], 'create_time' => time());
			if (M('Foodshop_queue_notice')->add($data)) {
				exit(json_encode(array('err_code' => false, 'msg' => 'ok')));
			} else {
				exit(json_encode(array('err_code' => true, 'msg' => '稍后重试')));
			}
		}
	}

    /**
     * 通知店员
     */
    public function call_store()
    {
        if (empty($this->user_session)) {
            exit(json_encode(array(
                'err_code' => true,
                'msg' => '先登录，再取消'
            )));
        }
        $order_id = isset($_GET['order_id']) ? htmlspecialchars($_GET['order_id']) : 0;
        $note = isset($_POST['note']) ? htmlspecialchars(trim($_POST['note'])) : '';
        if ($order = M('Foodshop_order')->field(true)->where(array('real_orderid' => $order_id))->find()) {
            if ($order['running_state']) {
                exit(json_encode(array(
                    'err_code' => true,
                    'msg' => '已通知店员了,不要重复操作'
                )));
            } else {
                $foodshop = M('Merchant_store_foodshop')->field(true)->where(array('store_id' => $order['store_id']))->find();
                if (empty($foodshop['is_auto_order'])) {
                    $productCart = array();
                    $goodsTempList = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order['order_id']))->select();
                    foreach ($goodsTempList as $goods_temp) {
//                         $t_cookie = array(
//                             'goods_id' => $goods_temp['goods_id'],
//                             'num' => $goods_temp['num'],
//                             'name' => $goods_temp['name'],
//                             'price' => floatval($goods_temp['price']),
//                             'extra_price' => $goods_temp['extra_price']
//                         );
                        $t_cookie = array(
                            'goods_id' => $goods_temp['goods_id'],
                            'tempId' => $goods_temp['id'],
                            'uid' => $goods_temp['uid'],
                            'fid' => $goods_temp['fid'],
                            'package_id' => $goods_temp['package_id'],
                            'num' => $goods_temp['num'],
                            'name' => $goods_temp['name'],
                            'price' => floatval($goods_temp['price']),
                            'extra_price' => $goods_temp['extra_price']
                        );
                        $temp_params = array();
                        if ($goods_temp['spec_id']) {
                            $temp_params = D('Foodshop_goods')->format_spec_ids($goods_temp, $temp_params);
                        }
                        if ($goods_temp['spec']) {
                            $temp_params = D('Foodshop_goods')->format_properties_ids($goods_temp, $temp_params);
                        }
                        $t_cookie['params'] = $temp_params;
                        $productCart[] = $t_cookie;
                    }

                    $cart_data = D('Foodshop_goods')->format_cart($productCart, $order['store_id'], $order['order_id']);
                    if ($cart_data['err_code']) {
                        exit(json_encode($cart_data));
                    }
                    
                    $new_goods_list = $cart_data['data'];
                    $total = $cart_data['total'];
                    $price = $cart_data['price'];
                    $now_time = time();

                    $goods_list = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order['order_id'], 'package_id' => 0))->select();
                    $detailList = array();
                    foreach ($goods_list as $_row) {
                        $_t_index = $_row['uid'] . '_' . $_row['goods_id'];
                        if (strlen($_row['spec']) > 0) {
                            $_t_index .= '_' . md5($_row['spec']);
                        }
                        $detailList[$_t_index] = $_row;
                    }
                    $newPackageIDs = array();
                    $detailDB = D('Foodshop_order_detail');
                    $foodshopGoodsDB = D('Foodshop_goods');
                    foreach ($new_goods_list as $index => $new_row) {
                        if ($new_row['package_id']) {//用户点的套餐
                            if (isset($newPackageIDs[$new_row['package_id']]) && $newPackageIDs[$new_row['package_id']]) {
                                $fid = $newPackageIDs[$new_row['package_id']];
                            } else {
                                if ($pack = D('Foodshop_goods_package')->field(true)->where(array('id' => $new_row['package_id']))->find()) {
                                    $tmp = array();
                                    $tmp['order_id'] = $order['order_id'];
                                    $tmp['store_id'] = $new_row['store_id'];
                                    $tmp['package_id'] = $new_row['package_id'];
                                    $tmp['goods_id'] = $new_row['package_id'];
                                    $tmp['ishide'] = 1;
                                    $tmp['is_discount'] = 1;
                                    $tmp['name'] = $pack['name'];
                                    $tmp['price'] = $pack['price'];
                                    $tmp['extra_price'] = 0;
                                    $tmp['uid'] = $new_row['uid'];
                                    $tmp['create_time'] = $now_time;
                                    $tmp['num'] = $new_row['num'];
                                    if ($fid = $detailDB->add($tmp)) {
                                        $newPackageIDs[$new_row['package_id']] = $fid;
                                    } else {
                                        exit(json_encode(array('err_code' => true, 'msg' => '用户点的套餐保存失败，稍后重试！')));
                                    }
                                } else {
                                    exit(json_encode(array('err_code' => true, 'msg' => '套餐信息不存在！')));
                                }
                            }
                            if ($new_row['fid']) {
                                $new_row['fid'] = $fid;
                                $new_row['create_time'] = $now_time;
                                $new_row['order_id'] = $order['order_id'];
                                $new_row['store_id'] = $new_row['store_id'];
                                $new_row['is_discount'] = 1;
                                $new_row['extra_price'] = empty($new_row['extra_price']) ? 0 : $new_row['extra_price'];
                                $detailDB->add($new_row);
                            }
                        } elseif (isset($detailList[$index])) {//这个用户已经点了这个菜品的时候 只是在已点菜品中增加数量
                            $detailDB->where(array('id' => $detailList[$index]['id']))->save(array('num' => $new_row['num'] + $detailList[$index]['num']));
                            unset($detailList[$new_row['uid']][$index]);
                        } else {//新增的菜品
                            $new_row['fid'] = 0;
                            $new_row['package_id'] = 0;
                            $new_row['create_time'] = $now_time;
                            $new_row['order_id'] = $order['order_id'];
                            $new_row['extra_price'] = empty($new_row['extra_price']) ? 0 : $new_row['extra_price'];
                            $detailDB->add($new_row);
                        }
                        
//                         if (isset($temp_list[$index])) {
//                             D('Foodshop_order_detail')->where(array('id' => $temp_list[$index]['id']))->save(array('num' => $new_row['num'] + $temp_list[$index]['num']));
//                             unset($temp_list[$index]);
//                         } else {
//                             $new_row['create_time'] = $now_time;
//                             $new_row['order_id'] = $order['order_id'];
//                             $new_row['store_id'] = $order['store_id'];
//                             $new_row['extra_price'] = empty($new_row['extra_price']) ? 0 : $new_row['extra_price'];
//                             D('Foodshop_order_detail')->add($new_row);
//                         }
                    }

                    D('Foodshop_order_temp')->where(array('order_id' => $order['order_id']))->delete();
                    if ($order['status'] < 2) {
                        $save_order_data['status'] = 2;
                    }
                    $save_order_data['running_state'] = 0;
                    $save_order_data['last_time'] = $_SERVER['REQUEST_TIME'];
                    $save_order_data['running_time'] = $_SERVER['REQUEST_TIME'];
                    $save_order_data['note'] = $note;
                    
                    /** 20180507 平台给商家限定订单数的折扣，下单时就增加折扣订单数目，没有做此单没有支付或者其他原因回滚折扣订单数 */
                    if (floatval($order['plat_discount']) <= 0) {
                        $save_order_data['plat_discount'] = 0;
                        $plat_discount = D('Merchant')->getDiscount($order['mer_id']);
                        $plat_discount = floatval($plat_discount);
                        if ($plat_discount) {
                            $save_order_data['plat_discount'] = $plat_discount;
//                             D('Merchant')->setDiscountNumInc($order['mer_id']);
                        }
                    }
                    
                    if (M('Foodshop_order')->where(array('order_id' => $order['order_id']))->save($save_order_data)) {
                        // 配置打印
                        D('Foodshop_order')->order_notice($order['order_id'], $new_goods_list);
                        $order['status'] = 2;
                        D('Merchant_store_staff')->sendMsgFoodShop($order);
                        cookie('foodshop_cart_' . $order['store_id'] . '_order_' . $order['order_id'], null);
                        exit(json_encode(array(
                            'err_code' => false,
                            'msg' => '通知上菜成功'
                        )));
                    } else {
                        exit(json_encode(array(
                            'err_code' => true,
                            'msg' => '通知上菜失败'
                        )));
                    }
                } else {
                    M('Foodshop_order')->where(array('order_id' => $order['order_id']))->save(array('running_state' => 1, 'note' => $note, 'running_time' => time()));
                    $order['status'] = 2;
                    D('Merchant_store_staff')->sendMsgFoodShop($order);
                    cookie('foodshop_cart_' . $order['store_id'] . '_order_' . $order['order_id'], null);
                    exit(json_encode(array(
                        'err_code' => false,
                        'msg' => '通知上菜成功'
                    )));
                }
            }
        } else {
            exit(json_encode(array(
                'err_code' => true,
                'msg' => '不存在的订单信息'
            )));
        }
    }

	/**
	 * 检测订单状态
	 */
	public function check_status()
	{
		if (empty($this->user_session)) {
			exit(json_encode(array('err_code' => true, 'msg' => '先登录，再取消')));
		}
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if ($order = M('Foodshop_order')->field(true)->where(array('order_id' => $order_id))->find()) {
			if ($order['status'] > 2) exit(json_encode(array('err_code' => false, 'msg' => 'no')));
			$old_goods = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id))->limit(1)->find();

			$new_goods = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id))->limit(1)->find();

			if ($old_goods && empty($new_goods)) {
				exit(json_encode(array('err_code' => false, 'msg' => 'ok')));
			}
			exit(json_encode(array('err_code' => true, 'msg' => 'no')));
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '不存在的订单信息')));
		}
	}


	public function pay()
	{
		if (empty($this->user_session)) {
			$this->error_tips('先登录');
			exit(json_encode(array('err_code' => true, 'msg' => '先登录，再取消')));
		}
		$order_id = isset($_GET['order_id']) ? htmlspecialchars(trim($_GET['order_id'])) : 0;
		if ($order = D('Foodshop_order')->get_order_detail(array('real_orderid' => $order_id))) {
		    if ($order['status'] > 2) {
		        $this->error_tips('订单已完成，不能再支付了');
		    } elseif ($order['status'] == 0) {
				$price = $order['price'];
			} else {
				$price = D('Foodshop_order')->count_price($order);
//				$order_data['can_discount_money'] = D('Foodshop_order')->count_price($order, 1);
				$order_data['can_discount_table_money'] = D('Foodshop_order')->count_price($order, 1);
			}
			//$order_data['price'] = $price;
			$order_data['extra_price'] = D('Foodshop_order')->count_extra_price($order);
			if ($order['uid'] != $this->user_session['uid']) {
			    $order_data['uid'] = $this->user_session['uid'];
			    $order_data['name'] = $this->user_session['nickname'];
			    $order_data['phone'] = $this->user_session['phone'];
			    if (isset($this->user_session['sex'])) {
			        $order_data['sex'] = $this->user_session['sex'];
			    }
			}
			
			M('Foodshop_order')->where(array('order_id'=>$order['order_id']))->save($order_data);

			if ($result = D('Plat_order')->field(true)->where(array('business_id' => $order['order_id'], 'business_type' => 'foodshop', 'paid' => 0))->find()) {
				if (floatval($result['total_money']) != floatval($price)) {
					if (D('Plat_order')->where(array('order_id' => $result['order_id']))->save(array('total_money' => $price))) {
						$result['error_code'] = false;
					} else {
						$result['error_code'] = true;
					}
				} else {
					$result['error_code'] = false;
				}
			} else {
				$pay_order_param = array(
						'business_type' => 'foodshop',
				        'business_id' => $order['order_id'],
						'order_name' => '餐饮订单',
						'uid' => $this->user_session['uid'],
						'total_money' => $price,
						'store_id' => $order['store_id'],
						'wx_cheap' => 0,
				);
				$result = D('Plat_order')->add_order($pay_order_param);
			}

			if ($result['error_code']) {
				$this->error_tips('支付失败稍后重试');
			} else {
				redirect(U('Pay/check', array('order_id' => $result['order_id'], 'type' => 'plat')));
			}
		} else {
			$this->error_tips('不存在的订单信息');
		}

	}

	public function reply()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;

		$store = M('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($store)) {
			$this->error_tips('不存在的店铺');
			exit;
		}
		$merchant = M('Merchant')->field(true)->where(array('mer_id' => $store['mer_id']))->find();
		if (empty($merchant)) {
			$this->error_tips('不存在的商家');
			exit;
		}
		$foodshop = M('Merchant_store_foodshop')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($foodshop)) {
			$this->error_tips('不存在的餐饮店铺');
			exit;
		}
		$reply_list = D('Reply')->get_page_reply_list($store_id, 4, '', '', 0);
		$foodshop = array_merge($foodshop, $store);
		$this->assign('foodshop', $foodshop);
		$this->display();
	}

	public function replyList()
	{
		$this->header_json();
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			exit(json_encode($foodshop));
		}
		$reply_list = D('Reply')->get_page_reply_list($store_id, 4, '', '', 0);
		$reply_list['err_code'] = false;
		exit(json_encode($reply_list));
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


	public function get_route()
	{
		$now_store = D('Merchant_store')->get_store_by_storeId($_GET['store_id']);
		if(empty($now_store)){
			$this->error_tips('该店铺不存在！');
		}
		$this->assign('now_store',$now_store);

		$this->display();
	}

	public function scan_qcode()
	{
		$this->isLogin();
		$table_id = isset($_GET['table_id']) ? intval($_GET['table_id']) : 0;
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit();
		}
		$foodshop = $foodshop['data'];
		//扫码桌台码成商家的粉丝
		$this->saverelation($this->user_session['openid'], $foodshop['mer_id']);
		if ($table = D('Foodshop_table')->field(true)->where(array('id' => $table_id, 'store_id' => $store_id))->find()) {
			if ($order = D('Foodshop_order')->field(true)->where(array('table_id' => $table_id, 'status' => 2))->find()) {
				if ($order['uid'] == $this->user_session['uid']) {
					redirect(U('Foodshop/order_detail', array('order_id' => $order['order_id'])));
				} else {
					$this->error_tips('此餐桌正在使用中！');
					exit;
				}
			} else {
				if ($order = D('Foodshop_order')->field(true)->where(array('table_id' => $table_id, 'status' => 1, 'uid' => $this->user_session['uid']))->order('book_time ASC')->find()) {
					redirect(U('Foodshop/order_detail', array('order_id' => $order['order_id'])));
					exit;
				}
				$table_type = D('Foodshop_table_type')->field(true)->where(array('id' => $table['tid']))->find();
				$book_num = isset($table_type['min_people']) ? $table_type['min_people'] : 1;
				$data = array('mer_id' => $foodshop['mer_id'], 'uid' => $this->user_session['uid'], 'store_id' => $store_id, 'name' => isset($this->user_session['nickname']) ? $this->user_session['nickname'] : '', 'phone' => isset($this->user_session['phone']) ? $this->user_session['phone'] : '', 'sex' => isset($this->user_session['sex']) ? intval($this->user_session['sex']) : 1, 'table_id' => $table_id, 'table_type' => $table['tid']);
				$data['create_time'] = time();
				$data['status'] = 1;
				$data['order_from'] = 1;
				$data['book_num'] = $book_num;
				$data['real_orderid'] = date('ymdhis') . substr(microtime(), 2, 8 - strlen($this->user_session['uid'])) . $this->user_session['uid'];//real_orderid
				if ($order_id = D('Foodshop_order')->save_order($data)) {
				    redirect(U('Foodshop/menu', array('order_id' => $data['real_orderid'], 'store_id' => $store_id)));
				} else {
					$this->error_tips('系统出错了，稍后重试！');
					exit;
				}
			}
			if ($table['status']) {

			}
		} else {
			$this->error_tips('不存在的餐台信息！');
		}
	}

    private function saverelation($openid, $mer_id)
    {
        $relation = D('Merchant_user_relation')->field(true)->where(array('openid' => $openid, 'mer_id' => $mer_id))->find();
        if (empty($relation)) {
            $relation = array(
                'openid' => $openid,
                'mer_id' => $mer_id,
                'dateline' => time(),
                'from_merchant' => 0
            );
            D('Merchant_user_relation')->add($relation);
            D('Merchant')->where(array('mer_id' => $mer_id))->setInc('fans_count', 1);
        }
    }

    public function search()
    {
        $this->display();
    }

    public function callServer()
    {
        if (empty($this->user_session)) {
            exit(json_encode(array(
                'err_code' => true,
                'msg' => '先登录，再取消'
            )));
        }
        $order_id = isset($_GET['order_id']) ? htmlspecialchars(trim($_GET['order_id'])) : 0;
        if ($order = M('Foodshop_order')->field(true)->where(array('real_orderid' => $order_id))->find()) {
            $order['status'] = 100;
            D('Merchant_store_staff')->sendMsgFoodShop($order);
            exit(json_encode(array(
                'err_code' => false,
                'msg' => '已通知到服务员，请稍等！'
            )));
        }

    }

    public function foodshop_getgroup_detail()
    {
        $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $data = D('Foodshop_goods_package')->get_detail_by_id(array('store_id' => $store_id, 'status' => 1, 'id' => $group_id), true);
        if ($data) {
            exit(json_encode(array('errcode' => 0, 'data' => $data)));
        } else {
            exit(json_encode(array('errcode' => 1, 'msg' => '不存在的团购套餐')));
        }
    }

    public function group_detail()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;
        $now_order = M('Foodshop_order')->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find();
        if (empty($now_order)) {
            $this->error_tips('订单信息不正确');
        }
        $data = D('Foodshop_goods_package')->get_detail_by_id(array('store_id' => $now_order['store_id'], 'status' => 1, 'id' => $group_id), true);
        if ($data['image']) {
            $goods_image_class = new foodshop_goods_image();
            $data['image'] = $goods_image_class->get_image_by_path($data['image'], 'm');
        }
//         echo '<pre/>';
//         print_r($data);die;
        $this->assign('goods', $data);
        $this->assign('order', $now_order);
        $this->display();
    }
    
    private function userInfo()
    {
        if (isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == $_SESSION['weixin']['foodshopuser'])){
            unset($_SESSION['weixin']['foodshopuser']);
            import('ORG.Net.Http');
            $http = new Http();
            $return = $http->curlGet('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->config['wechat_appid'].'&secret='.$this->config['wechat_appsecret'].'&code='.$_GET['code'].'&grant_type=authorization_code');
            $jsonrt = json_decode($return,true);
            if($jsonrt['errcode']){
                $error_msg_class = new GetErrorMsg();
                $this->error_tips('授权发生错误：'.$error_msg_class->wx_error_msg($jsonrt['errcode']),U('Login/index'));
            }
            
            $return = $http->curlGet('https://api.weixin.qq.com/sns/userinfo?access_token='.$jsonrt['access_token'].'&openid='.$jsonrt['openid'].'&lang=zh_CN');
            
            $jsonrt = json_decode($return,true);
            if ($jsonrt['errcode']) {
                $error_msg_class = new GetErrorMsg();
                $this->error_tips('授权发生错误：'.$error_msg_class->wx_error_msg($jsonrt['errcode']),U('Login/index'));
            }
            if ($user = D('User')->field(true)->where(array('openid' => $jsonrt['openid']))->find()) {
                $session['uid'] = $user['uid'];
                $session['phone'] = $user['phone'];
                $session['nickname'] = $user['nickname'];
                $session['sex'] = $user['sex'];
            } else {
                $session = array('openid' => $jsonrt['openid'], 'nickname' => $jsonrt['nickname'], 'avatar' => $jsonrt['headimgurl']);
                $result = D('User')->autoreg($session);
                if ($result['error_code']) {
                    $this->error_tips($result['msg']);
                } else {
                    $session['uid'] = $result['uid'];
                }
            }
            session('user',$session);
//             $_SESSION['foodshopuser'] = array('openid' => $jsonrt['openid'], 'nickname' => $jsonrt['nickname'], 'avatar' => $jsonrt['headimgurl']);
        }
        $user = session('user');
        if (empty($user) && empty($_SESSION['foodshopuser']) && !empty($_SESSION['openid'])) {
            $_SESSION['weixin']['foodshopuser'] = md5(uniqid());
            $customeUrl = preg_replace('#&code=(\w+)#','',$this->config['site_url'].$_SERVER['REQUEST_URI']);
            $oauthUrl='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->config['wechat_appid'].'&redirect_uri='.urlencode($customeUrl).'&response_type=code&scope=snsapi_userinfo&state='.$_SESSION['weixin']['foodshopuser'].'#wechat_redirect';
            redirect($oauthUrl);
            exit;
        }
    }
    
    public function sync()
    {
        $this->userInfo();
        
        $real_orderid = isset($_GET['sync']) ? htmlspecialchars(trim($_GET['sync'])) : '';
        $now_order = D('Foodshop_order')->get_order_detail(array('real_orderid' => $real_orderid), 3);
        if (empty($now_order)) {
            $this->error_tips('订单信息不正确');
        }
        
        $store_id = $now_order['store_id'];
        $order_id = $now_order['order_id'];
        $foodshop = $this->now_store($store_id, false);
        if ($foodshop['err_code']) {
            $this->error_tips($foodshop['msg']);
            exit;
        }
        $foodshop = $foodshop['data'];
        
        //三个按钮的可用，默认不可能
        $is_pay = 0;
        $is_call_store = 0;
        $is_add_menu = 0;
        
        $uids = array();
        $plat_discount = D('Merchant')->getDiscount($now_order['mer_id']);
        $plat_discount = floatval($plat_discount);
        if ($now_order['status'] < 3) {
            $total = 0;
            $price = $now_order['price'] - $now_order['book_price'];//菜品的总价
            $total_price = $now_order['price'];
            $extra_price = 0;
            
            $goods_detail_list = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->order('id ASC')->select();
            
            $temp_goods_list = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->order('id ASC')->select();
            
            $package_list = array();
            $old_goods_list = array();
            foreach ($goods_detail_list as $new) {
                if (!in_array($new['uid'], $uids)) {
                    $uids[] = $new['uid'];
                }
                if ($new['package_id']) {
                    if ($new['ishide']) {
                        if ($new['is_discount'] && $plat_discount) {
                            $price += $new['price'] * $new['num'] * $plat_discount * 0.01;
                        } else {
                            $price += $new['price'] * $new['num'];
                        }
                        
                        $total += $new['num'];
                        $new['isNew'] = 0;
                        $package_list[$new['uid']]['goodsList'][$new['id']] = $new;
                    } else {
                        $package_list[$new['uid']]['goodsList'][$new['fid']]['list'][$new['goods_id']] = $new;
                    }
                } elseif ($new['is_must']) {
                    //$price += $new['price'] * $new['num'];
                    //$total += $new['num'];
                } else {
                    if ($new['is_discount'] && $plat_discount) {
                        $price += $new['price'] * $new['num'] * $plat_discount * 0.01;
                    } else {
                        $price += $new['price'] * $new['num'];
                    }
                    $total += $new['num'];
                    $extra_price += $new['extra_price'] * $new['num'];
                    $new['isNew'] = 0;
                    $old_goods_list[$new['uid']]['goodsList'][] = $new;
                }
            }
            
            foreach ($temp_goods_list as $new) {
                if (!in_array($new['uid'], $uids)) {
                    $uids[] = $new['uid'];
                }
                if ($new['package_id'] > 0) {
                    if ($new['ishide']) {
                        if ($new['is_discount'] && $plat_discount) {
                            $price += $new['price'] * $new['num'] * $plat_discount * 0.01;
                        } else {
                            $price += $new['price'] * $new['num'];
                        }
                        $total += $new['num'];
                        $new['isNew'] = 1;
                        $package_list[$new['uid']]['goodsList'][$new['id']] = $new;
                    } else {
                        $package_list[$new['uid']]['goodsList'][$new['fid']]['list'][$new['goods_id']] = $new;
                    }
                } else {
                    if ($new['is_discount'] && $plat_discount) {
                        $price += $new['price'] * $new['num'] * $plat_discount * 0.01;
                    } else {
                        $price += $new['price'] * $new['num'];
                    }
                    $total += $new['num'];
                    $new['isNew'] = 1;
                    $old_goods_list[$new['uid']]['goodsList'][] = $new;//
                }
                $is_call_store = 1;
            }
            
            $must_list = D('Foodshop_goods')->field(true)->where(array('store_id' => $now_order['store_id'], 'status' => 1, 'is_must' => 1))->select();

            $now_time = time();
            $save_data = array();
            foreach ($must_list as &$mgoods) {
                $mgoods['num'] = $now_order['book_num'];
                
                if ($mgoods['is_discount'] && $plat_discount) {
                    $price += $mgoods['price'] * $now_order['num'] * $plat_discount * 0.01;
                } else {
                    $price += $mgoods['price'] * $now_order['num'];
                }
			 	$price += $mgoods['price'] * $now_order['book_num'];
                $total += $now_order['book_num'];
            }
            
            if (in_array($now_order['status'], array(1, 2)) && $now_order['running_state'] == 0) {
                $is_add_menu = 1;
            }
            
            if ($now_order['status'] == 0 || $is_call_store == 0) {
                $is_pay = 1;
            }
            
        } else {
            $goods_detail_list = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->order('id asc')->select();
            $price = $now_order['price'];
            $package_list = array();
            $must_list = array();
            $old_goods_list = array();
            foreach ($goods_detail_list as $new) {
                if (!in_array($new['uid'], $uids)) {
                    $uids[] = $new['uid'];
                }
                if ($new['package_id']) {
                    if ($new['ishide']) {
                        if ($new['is_discount'] && $plat_discount) {
                            $price += $new['price'] * $new['num'] * $plat_discount * 0.01;
                        } else {
                            $price += $new['price'] * $new['num'];
                        }
                        $total += $new['num'];
                        $new['isNew'] = 0;
                        $package_list[$new['uid']]['goodsList'][$new['id']] = $new;
                    } else {
                        $package_list[$new['uid']]['goodsList'][$new['fid']]['list'][$new['goods_id']] = $new;
                    }
                    
                } elseif ($new['is_must']) {
                    $must_list[] = $new;
                } else {
                    $old_goods_list[$new['uid']]['goodsList'][] = $new;
                }
            }
        }
        
        $this->assign(array('is_pay' => $is_pay, 'is_add_menu' => $is_add_menu, 'is_call_store' => $is_call_store));
        $userList = array();
        if ($uids) {
            $user_list = D('User')->field('uid, nickname, avatar')->where(array('uid' => array('in', $uids)))->select();
            foreach ($user_list as $user) {
                $userList[$user['uid']] = $user;
            }
        }
        if ($plat_discount) {
            $plat_discount = floatval(round($plat_discount / 10, 2));
        } else {
            $plat_discount = 10;
        }

		$now_coupon = D('System_coupon')->get_user_coupon_list($this->user_session['uid'],'',1);
		$now_coupon = sortArrayAsc($now_coupon,'discount_value');
		//商家桌次折扣
		if(1==$this->config['is_open_merchant_foodshop_discount']){
            $tablesDiscount = D('Merchant_store_foodshop')->getTableDiscount($now_order['mer_id'],$now_order['order_id']);
            if($tablesDiscount!=false && $tablesDiscount['mer_discount']!=0){
                $this->assign('tablesDiscount',$tablesDiscount);
            }
		}

		$this->assign('coupon_discount', $now_coupon[0]);
		$this->assign('plat_discount', $plat_discount);
        $this->assign('userList', $userList);
        $this->assign('old_goods_list', $old_goods_list);
        $this->assign('package_list', $package_list);
        $this->assign('must_list', $must_list);
        $this->assign('order', $now_order);
        $this->assign('store', $foodshop);
        $this->assign('userid', $this->user_session['uid']);
        $this->assign('price', $price);
        $this->assign('extra_price', $extra_price);
        $this->assign('share_url', U('Foodshop/sync', array('sync' => $real_orderid), true, false, true));
        $this->display();
    }
    
    
    public function getDataInfo()
    {
        $real_orderid = isset($_GET['real_orderid']) ? htmlspecialchars(trim($_GET['real_orderid'])) : '';
        $now_order = D('Foodshop_order')->get_order_detail(array('real_orderid' => $real_orderid), 3);
        if (empty($now_order)) {
            exit(json_encode(array('errcode' => 1, 'msg' => '订单信息不正确')));
        }
        
        $store_id = $now_order['store_id'];
        $order_id = $now_order['order_id'];
        $foodshop = $this->now_store($store_id, false);
        if ($foodshop['err_code']) {
            exit(json_encode(array('errcode' => 1, 'msg' => $foodshop['msg'])));
        }
        $foodshop = $foodshop['data'];
        
        //三个按钮的可用，默认不可能
        $is_pay = 0;
        $is_call_store = 0;
        $is_add_menu = 0;
        $is_call_server = 0;
        $is_comment = 0;
        $uids = array();
        
        if ($now_order['status'] < 3) {
            $is_call_server = 1;
            $total = 0;
            $price = $now_order['price'] - $now_order['book_price'];//菜品的总价
            $total_price = $now_order['price'];
            $extra_price = 0;
            
            $goods_detail_list = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->order('id ASC')->select();
            
            $temp_goods_list = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->order('id ASC')->select();
            
            $package_list = array();
            $old_goods_list = array();
            foreach ($goods_detail_list as $new) {
                if (!in_array($new['uid'], $uids)) {
                    $uids[] = $new['uid'];
                }
                $new['price'] = floatval($new['price']);
                $new['num'] = floatval($new['num']);
                if ($new['package_id']) {
                    if ($new['ishide']) {
                        $price += $new['price'] * $new['num'];
                        $total += $new['num'];
                        $new['isNew'] = 0;
                        $package_list['a_' . $new['uid']]['goodsList'][$new['id']] = $new;
                    } else {
                        $package_list['a_' . $new['uid']]['goodsList'][$new['fid']]['list'][$new['goods_id']] = $new;
                    }
                } elseif ($new['is_must']) {
                    //$price += $new['price'] * $new['num'];
                    //$total += $new['num'];
                } else {
                    $price += $new['price'] * $new['num'];
                    $total += $new['num'];
                    $extra_price += $new['extra_price'] * $new['num'];
                    $new['isNew'] = 0;
                    $old_goods_list['a_' . $new['uid']]['goodsList'][] = $new;
                }
            }
            
            foreach ($temp_goods_list as $new) {
                if (!in_array($new['uid'], $uids)) {
                    $uids[] = $new['uid'];
                }
                $new['price'] = floatval($new['price']);
                $new['num'] = floatval($new['num']);
                if ($new['package_id'] > 0) {
                    if ($new['ishide']) {
                        $price += $new['price'] * $new['num'];
                        $total += $new['num'];
                        $new['isNew'] = 1;
                        $package_list['a_' . $new['uid']]['goodsList'][$new['id']] = $new;
                    } else {
                        $package_list['a_' . $new['uid']]['goodsList'][$new['fid']]['list'][$new['goods_id']] = $new;
                    }
                } else {
                    $price += $new['price'] * $new['num'];
                    $total += $new['num'];
                    $new['isNew'] = 1;
                    $old_goods_list['a_' . $new['uid']]['goodsList'][] = $new;//
                }
                $is_call_store = 1;
            }
            
            $must_list = D('Foodshop_goods')->field(true)->where(array('store_id' => $now_order['store_id'], 'status' => 1, 'is_must' => 1))->select();
            $now_time = time();
            $save_data = array();
            foreach ($must_list as &$mgoods) {
                $mgoods['num'] = $now_order['book_num'];
                $price += $mgoods['price'] * $now_order['book_num'];
                $total += $now_order['book_num'];
            }
            
            if (in_array($now_order['status'], array(1, 2)) && $now_order['running_state'] == 0) {
                $is_add_menu = 1;
            }
            
            if ($now_order['status'] == 0 || $is_call_store == 0) {
                $is_pay = 1;
            }
            
        } else {
            $goods_detail_list = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->order('id asc')->select();
            $price = $now_order['price'];
            
            $package_list = array();
            $must_list = array();
            $old_goods_list = array();
            foreach ($goods_detail_list as $new) {
                if (!in_array($new['uid'], $uids)) {
                    $uids[] = $new['uid'];
                }
                $new['price'] = floatval($new['price']);
                $new['num'] = floatval($new['num']);
                if ($new['package_id']) {
                    if ($new['ishide']) {
                        $price += $new['price'] * $new['num'];
                        $total += $new['num'];
                        $new['isNew'] = 0;
                        $package_list['a_' . $new['uid']]['goodsList'][$new['id']] = $new;
                    } else {
                        $package_list['a_' . $new['uid']]['goodsList'][$new['fid']]['list'][$new['goods_id']] = $new;
                    }
                    
                } elseif ($new['is_must']) {
                    $must_list[] = $new;
                } else {
                    $old_goods_list['a_' . $new['uid']]['goodsList'][] = $new;
                }
            }
            if ($now_order['uid'] == $this->user_session['uid']) {
                $is_comment = 1;
            }
        }
        
        $userList = array();
        if ($uids) {
            $user_list = D('User')->field('uid, nickname, avatar')->where(array('uid' => array('in', $uids)))->select();
            foreach ($user_list as $user) {
                $userList[$user['uid']] = $user;
            }
        }
        $data = array('is_pay' => $is_pay, 'is_add_menu' => $is_add_menu, 'is_call_store' => $is_call_store, 'is_call_server' => $is_call_server, 'is_comment' => $is_comment);
        $data['orderUid'] = $now_order['uid'];
        $data['userList'] = $userList;
        $data['goodsList'] = $old_goods_list;
        $data['packageList'] = $package_list;
        $data['mustList'] = $must_list;
        
        exit(json_encode(array('errcode' => 0, 'data' => $data)));
    }
    
    
    
    
    public function saveGoods()
    {
        if (empty($this->user_session)) {
            exit(json_encode(array('err_code' => true, 'msg' => '先登录，再来点餐')));
        }
        
        $real_orderid = isset($_GET['orderid']) ? htmlspecialchars(trim($_GET['orderid'])) : '';
        $now_order = D('Foodshop_order')->get_order_detail(array('real_orderid' => $real_orderid), 3);
        if (empty($now_order)) {
            exit(json_encode(array('err_code' => true, 'msg' => '订单信息不正确')));
        }
        $store_id = $now_order['store_id'];
        $order_id = $now_order['order_id'];
        $foodshop = $this->now_store($store_id, false);
        if ($foodshop['err_code']) {
            exit(json_encode($foodshop));
        }
        
        $foodshop = $foodshop['data'];
        
        $tempDB = D('Foodshop_order_temp');
        $goodsDB = D('Foodshop_goods');
        if ($now_order['status'] < 3) {
            $productCart = json_decode(cookie('foodshop_cart_' . $store_id . '_order_' . $order_id), true);
            cookie('foodshop_cart_' . $store_id . '_order_' . $order_id, null);
            if ($now_order['running_state']) {//0：用户处理，1:用户不可处理;当是1的时候不能加减菜
                exit(json_encode(array('err_code' => true, 'msg' => '店员正在处理，您暂时无法操作')));
            }
            
            $new_goods_list = array();//提交的商品信息（除套餐中的商品）
            $packageTemp = array();//提交的套餐数据
            if ($productCart) {
                $productCart2 = array();
                foreach ($productCart as $row) {
                    if ($row['type'] == 'only') {
                        $row['fid'] = 0;
                        $row['package_id'] = 0;
                        $row['uid'] = $this->user_session['uid'];
                        $productCart2[] = $row;
                    } else {
                        $packageTemp[] = $row;
                    }
                }
                
                $cart_data = $goodsDB->format_cart($productCart2, $store_id, $order_id);
                if ($cart_data['err_code']) {
                    exit(json_encode($cart_data));
                }
                $new_goods_list = $cart_data['data'];
            }

            
            $goods_list = $tempDB->field(true)->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid'], 'store_id' => $store_id))->select();
            $temp_list = array();
            $packageList = array();
            foreach ($goods_list as $_row) {
                if ($_row['package_id']) {
                    if ($_row['ishide'] == 1) {
                        $packageList[$_row['package_id']] = $_row;
                    }
                } else {
                    $_t_index = $this->user_session['uid'] . '_' . $_row['goods_id'];
                    if (strlen($_row['spec']) > 0) {
                        $_t_index .= '_' . md5($_row['spec']);
                    }
                    $temp_list[$_t_index] = $_row;
                }
            }

            foreach ($packageTemp as $pt) {
                if (isset($packageList[$pt['goods_id']])) {
                    $package = $packageList[$pt['goods_id']];
                    if ($package['num'] != $pt['num']) {
                        $tempDB->where(array('id' => $package['id']))->save(array('num' => $pt['num']));
                    }
                    //找出这个套餐的所有已选的菜品
                    $packGoodsList = $tempDB->field(true)->where(array('fid' => $package['id']))->select();
                    $paramsGoods = array();
                    foreach ($packGoodsList as $pGoods) {
                        $paramsGoods[$pGoods['goods_id']] = $pGoods;
                    }
                    //本次提交的套餐的菜品信息
                    foreach ($pt['params'] as $gd) {
                        if (isset($paramsGoods[$gd['goods_id']])) {
                            if ($pt['num'] != $paramsGoods[$gd['goods_id']]['num']) {
                                $tempDB->where(array('id' => $paramsGoods[$gd['goods_id']]['id']))->save(array('num' => $pt['num']));
                            }
                            unset($paramsGoods[$gd['goods_id']]);
                        } else {
                            $gd['order_id'] = $order_id;
                            $gd['uid'] = $this->user_session['uid'];
                            $gd['store_id'] = $store_id;
                            $gd['package_id'] = $pt['goods_id'];
                            $gd['is_discount'] = 1;
                            //$gd['spec'] = $pt['index'];
                            $gd['extra_price'] = 0;
                            $gd['num'] = $pt['num'];
                            $gd['fid'] = $fid;
                         
                            $tempDB->add($gd);
                        }
                    }
                    //删除套餐中多余的菜品
                    foreach ($paramsGoods as $param) {
                        $tempDB->where(array('id' => $param['id']))->delete();
                    }
                    unset($packageList[$pt['goods_id']]);
                } else {
                    //增加套餐
                    $tmp = array();
                    $tmp['order_id'] = $order_id;
                    $tmp['uid'] = $this->user_session['uid'];
                    $tmp['store_id'] = $store_id;
                    $tmp['package_id'] = $pt['goods_id'];
                    $tmp['goods_id'] = $pt['goods_id'];
                    $tmp['ishide'] = 1;
                    $gd['is_discount'] = 1;
                    $tmp['name'] = $pt['name'];
                    $tmp['price'] = $pt['price'];
                    $tmp['extra_price'] = 0;
                    $tmp['num'] = $pt['num'];
                    $fid = $tempDB->add($tmp);
                    foreach ($pt['params'] as $gd) {
                        $gd['order_id'] = $order_id;
                        $gd['uid'] = $this->user_session['uid'];
                        $gd['store_id'] = $store_id;
                        $gd['package_id'] = $pt['goods_id'];
//                         $gd['spec'] = $pt['index'];
                        $gd['extra_price'] = 0;
                        $gd['is_discount'] = 1;
                        $gd['num'] = $pt['num'];
                        $gd['fid'] = $fid;
                        $tempDB->add($gd);
                    }
                }
            }

            //删除多余的套餐
            foreach ($packageList as $pack) {
                $tempDB->where(array('fid' => $pack['id']))->delete();
                $tempDB->where(array('id' => $pack['id']))->delete();
            }

            if ($new_goods_list) {
                foreach ($new_goods_list as $index => $new_row) {
                    if (isset($temp_list[$index])) {
                        if ($temp_list[$index]['num'] != $new_row['num']) {
                            D('Foodshop_order_temp')->where(array('id' => $temp_list[$index]['id']))->save(array('num' => $new_row['num']));
                            $new_row['num'] -= $temp_list[$index]['num'];
                            //更新库存
                            $goodsDB->update_stock($new_row);
                        }
                        unset($temp_list[$index]);
                    } else {
                        $new_row['order_id'] = $order_id;
                        $new_row['store_id'] = $store_id;
                        $new_row['uid'] = $this->user_session['uid'];
                        $new_row['extra_price'] = empty($new_row['extra_price'])?0:$new_row['extra_price'];
                        $tempDB->add($new_row);
                        //更新库存
                        $goodsDB->update_stock($new_row);
                    }
                }
            }

            if ($temp_list) {
                $del_ids = array();
                foreach ($temp_list as $tmp) {
                    //更新库存
                    $goodsDB->update_stock($tmp, 1);
                    $del_ids[] = $tmp['id'];
                }
                D('Foodshop_order_temp')->where(array('id' => array('in', $del_ids)))->delete();
            }
            exit(json_encode(array('err_code' => false, 'url' => U('Foodshop/sync', array('sync' => $now_order['real_orderid'])))));
        } else {
            exit(json_encode(array('err_code' => true, 'msg' => '此单已经支付买单了，不能再加菜了！')));
        }
    }
}
?>
