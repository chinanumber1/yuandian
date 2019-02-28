<?php
class ShopAction extends BaseAction
{
    protected $send_time_type = array('分钟', '小时', '天', '周', '月');
	protected $leveloff = '';
	public function index(){
            
		if($_GET['shop-id']){
		    $shop = M('Merchant_store_shop')->field('is_mult_class')->where(array('store_id' => intval($_GET['shop-id'])))->find();
		    if ($shop['is_mult_class'] == 1) {
		        if($_GET['good-id']){
		            redirect(U('classic_shop').'&shop_id='.$_GET['shop-id'].'&good_id='.$_GET['good-id']);
		        }else{
		            redirect(U('classic_shop').'&shop_id='.$_GET['shop-id']);
		        }
		    } else {
		        if($_GET['good-id']){
		            redirect(U('index').'#shop-'.$_GET['shop-id'].'-'.$_GET['good-id']);
		        }else{
		            redirect(U('index').'#shop-'.$_GET['shop-id']);
		        }
		    }
		}
		if($_GET['cat']){
			redirect(U('index').'#cat-'.$_GET['cat']);
		}
		$nowIndex = isset($_GET['index']) ? htmlspecialchars(trim($_GET['index'])) : '';
		$cartid = isset($_GET['cartid']) ? htmlspecialchars(trim($_GET['cartid'])) : '';
		if( $this->user_session['uid'] && $this->config['open_rand_send']){
			$coupon_html = D('System_coupon')->rand_send_coupon_get(array('time'=>$_SERVER['REQUEST_TIME'],'uid'=>$this->user_session['uid']));
			$coupon_html && $this->assign('coupon_html',$coupon_html);
		}


		$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		$this->assign('user_long_lat',$user_long_lat);
		$this->assign('nowIndex', $nowIndex);
		$this->assign('cartid', $cartid);
		$this->getFooterMenu();
	    $this->display();
	}

	public function classic_index(){
		$this->getFooterMenu();
		$this->display();
	}
	public function classic_address(){
		$this->display();
	}
	public function classic_shopsearch(){
		$this->display();
	}
	public function classic_cat(){
		$this->display();
	}
	public function classic_shop(){
	    $nowIndex = isset($_GET['index']) ? htmlspecialchars(trim($_GET['index'])) : '';
      $cartid = isset($_GET['cartid']) ? htmlspecialchars(trim($_GET['cartid'])) : '';
      $wap_shop_top_adver = D('Adver')->get_adver_by_key('wap_shop_top',33);
      $this->assign('wap_shop_top_adver',$wap_shop_top_adver);
	    $this->assign('nowIndex', $nowIndex);
	    $this->assign('cartid', $cartid);
		$this->display('classic_shop_new');
	}
	public function classic_good(){
		$this->display();
	}
	public function classic_map(){
		$this->display();
	}
	public function getFooterMenu(){
		$home_menu_list = D('Home_menu')->getMenuList('shop_footer');
		if(empty($home_menu_list) && $this->config['single_system_type'] == 'shop'){
			$home_menu_list = array(
				array(
					'name'		=>	$this->config['shop_alias_name'],
					'url'		=> 	U('Shop/index'),
					'pic_path'	=> 	'shop/footer_store.png',
					'hover_pic_path'	=> 	'shop/footer_store_active.png',
				),
				array(
					'name'		=>	'订单',
					'url'		=> 	U('My/shop_order_list'),
					'pic_path'	=> 	'shop/footer_order.png',
					'hover_pic_path'	=> 	'shop/footer_order_active.png',
				),
				array(
					'name'		=>	'我的',
					'url'		=> 	U('My/index'),
					'pic_path'	=> 	'shop/footer_my.png',
					'hover_pic_path'	=> 	'shop/footer_my_active.png',
				),
			);
		}
		$this->assign('home_menu_list',$home_menu_list);
		
		return array();
	}
	public function ajax_index()
	{
		/*最多5个*/
		$return = array();
		$return['banner_list'] = D('Adver')->get_adver_by_key('wap_shop_index_top', 5);
		$return['slider_list'] = D('Slider')->get_slider_by_key('wap_shop_slider', 0);
		$return['adver_list'] = D('Adver')->get_adver_by_key('wap_shop_index_cente', 3);
		$return['category_list'] = D('Shop_category')->lists(true);
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
// 				array(
// 						'name' => '配送费最低',
// 						'sort_url' => 'delivery_fee'
// 				),
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
		echo json_encode($return);
	}
	public function ajax_category(){
		$return = array();
		$return['category_list'] = D('Shop_category')->lists(true);
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
// 				array(
// 						'name' => '配送费最低',
// 						'sort_url' => 'delivery_fee'
// 				),
				array(
						'name' => '评分最高',
						'sort_url' => 'score_mean'
				),
				array(
						'name' => '最新发布',
						'sort_url' => '	create_time'
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
		echo json_encode($return);
	}
	/*请求参数 cat_url sort_url type_url user_long user_lat page*/
	public function ajax_list()
	{
		$key = isset($_GET['key']) ? htmlspecialchars($_GET['key']) : '';
		$cat_url = isset($_GET['cat_url']) ? htmlspecialchars($_GET['cat_url']) : 'all';
		$order = isset($_GET['sort_url']) ? htmlspecialchars($_GET['sort_url']) : '';
		$deliver_type = isset($_GET['type_url']) ? htmlspecialchars($_GET['type_url']) : 'all';
		$lat = isset($_GET['user_lat']) ? htmlspecialchars($_GET['user_lat']) : 0;
		$long = isset($_GET['user_long']) ? htmlspecialchars($_GET['user_long']) : 0;
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $is_wap = $_GET['is_wap'] + 0;
        $village_id = $_GET['village_id'] + 0;
		$page = max(1, $page);
		$cat_id = 0;
		$cat_fid = 0;
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

		$where = array('deliver_type' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page);
		$key && $where['key'] = $key;

		if($is_wap > 0){
			$lists = D('Merchant_store_shop')->get_list_by_option($where,$is_wap);
		}else{
            // 如果存在社区id ,获取社区关联商户id, 加入查询条件中
            if ($village_id) {
                $where['village_id'] = $village_id;
                $lists = D('House_village_store')->get_list_by_option($where);
            } else {
                $lists = D('Merchant_store_shop')->get_list_by_option($where);
            }
		}
		$return = array();
		$now_time = date('H:i:s');

		foreach ($lists['shop_list'] as $row) {
			$temp = array();
			$temp['id'] = $row['store_id'];
			$temp['name'] = $row['name'];
			$temp['range'] = $row['range'];
			$temp['image'] = $row['image'];
			$temp['star'] = $row['score_mean'];
			$temp['month_sale_count'] = $row['sale_count'];
			$temp['is_new'] = ($row['create_time'] + 864000) < time() ? 0 : 1;
			$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
			$temp['delivery_time'] = $row['send_time'];//配送时长
			$temp['send_time_type'] = $row['send_time_type'];//配送时长单位类型
			$temp['delivery_time_type'] = $this->send_time_type[$row['send_time_type']];//配送时长单位
			$temp['delivery_price'] = floatval($row['basic_price']);//起送价
			$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
			$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
			$temp['is_close'] = $row['state'] ? intval($row['is_close']) : 1;
			$temp['isverify'] = $row['isverify'];
			$temp['is_mult_class'] = $row['is_mult_class'];
			$temp['merchant_coupon'] = $row['merchant_coupon'];
			$temp['deliver_type'] = $row['deliver_type'];

			$temp['coupon_list'] = array();
			if ($row['is_invoice']) {
				$temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
			}
			if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
				$temp['coupon_list']['discount'] = $row['store_discount']/10;
			}
// 			$temp['coupon_list']['isDiscountGoods'] = 0;
			if ($row['isDiscountGoods']) {
			    $temp['coupon_list']['isDiscountGoods'] = 1;
			}
			if ($row['isdiscountsort']) {
			    $temp['coupon_list']['isdiscountsort'] = 1;
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
			$return[] = $temp;
		}
		echo json_encode(array('store_list' => $return, 'has_more' => $lists['has_more'] ? true : false));
	}

	/*店铺详情页面*/
	public function ajax_shop() {
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;
		$where = array('store_id' => $store_id);
		$now_store = D('Merchant_store')->field(true)->where($where)->find();

		//资质认证
		if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
			echo json_encode(array());
			exit;
		}
		$now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
		$now_mer = M('Merchant')->field('isverify')->where(array('mer_id'=>$now_store['mer_id']))->find();
		if (empty($now_shop) || empty($now_store)) {
			echo json_encode(array());
			exit;
		}
		$auth_files = array();
		if (!empty($now_store['auth_files'])) {
			$auth_file_class = new auth_file();
			$tmp_pic_arr = explode(';', $now_store['auth_files']);
			foreach($tmp_pic_arr as $key => $value){
				$auth_files[] = $auth_file_class->get_image_by_path($value, 'm');//array('title' => $value, 'url' => $auth_file_class->get_image_by_path($value, 's'));
			}
		}
		$now_store['kf_url'] = '';
		if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $now_store['mer_id']))->select()) {
		    $key = $this->get_encrypt_key(array('app_id' => $this->config['im_appid'], 'openid' => $_SESSION['openid']), $this->config['im_appkey']);
		    $kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com') . '/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_store['mer_id'];
		    $now_store['kf_url'] = $kf_url;
		}
		$now_store['auth_files'] = $auth_files;
// 		$discounts = D('Shop_discount')->get_discount_byids(array($store_id));
		$discounts = D('Shop_discount')->getDiscounts($now_store['mer_id'], $store_id);
		$row = array_merge($now_store, $now_shop);

		$store = array();
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($row['pic_info']);

		$store['id'] = $row['store_id'];

		$store['kf_url'] = $row['kf_url'];
		$store['phone'] = $row['phone'];
		$store['long'] = $row['long'];
		$store['lat'] = $row['lat'];
		$store['store_theme'] = $row['store_theme'];
		$store['is_mult_class'] = $row['is_mult_class'];
		$store['adress'] = $row['adress'];
		$store['is_close'] = 1;
		$store['isverify'] = $now_mer['isverify'];
		$store['store_live_url'] = $now_shop['store_live_url'];

		if (D('Merchant_store_shop')->checkTime($row)) {
		    $store['is_close'] = 0;
		}
		$store['is_close'] = $store['is_close'] ? $store['is_close'] : $row['is_close'];
		$store['close_reason'] = $row['close_reason'];
		$store['time'] = D('Merchant_store_shop')->getBuniessName($row);

        if (M('Classify')->field(true)->where(array('token' => $row['mer_id']))->find()) {
            $store['home_url'] = U('Index/index', array('token' => $row['mer_id']));
        } else {
            $store['home_url'] = '';
        }
		$store['name'] = $row['name'];
		$store['store_notice'] = $row['store_notice'];
		$store['txt_info'] = $row['txt_info'];
		$store['image'] = isset($images[0]) ? $images[0] : '';
		$store['auth_files_str'] = implode(',', $auth_files);
		$store['auth_files'] = $auth_files;
		$store['images'] = $images;
		$store['images_str'] = implode(',', $images);
		$store['star'] = $row['score_mean'];
		$store['reply_count'] = $row['reply_count'];
		$store['reply_deliver_score'] = $row['reply_deliver_score'];
		$store['month_sale_count'] = $row['sale_count'];
		$store['is_new'] = ($row['create_time'] + 864000) < time() ? 0 : 1;
		$store['delivery'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送

// 		$store['delivery_time'] = $row['send_time'];
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

		$store['send_time_type'] = $row['send_time_type'];//时间单位类型
		$store['delivery_time_type'] = $this->send_time_type[$row['send_time_type']];//时间单位


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
                $store['delivery_price'] = floatval($row['s_basic_price1']) ? floatval($row['s_basic_price1']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) :(floatval($set['basic_price1']) ? floatval($set['basic_price1']): (floatval($this->config['basic_price1']) ? floatval($this->config['basic_price1']):(floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price1'])?floatval($row['basic_price1']):floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        }elseif($selectDeliverTime==2){
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                $store['delivery_price'] = floatval($row['s_basic_price2']) ? floatval($row['s_basic_price2']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) :(floatval($set['basic_price2']) ? floatval($set['basic_price2']): (floatval($this->config['basic_price2']) ? floatval($this->config['basic_price2']):(floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price2'])?floatval($row['basic_price2']):floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }

        }elseif($selectDeliverTime==3){
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                $store['delivery_price'] = floatval($row['s_basic_price3']) ? floatval($row['s_basic_price3']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($set['basic_price3']) ? floatval($set['basic_price3']):(floatval($this->config['basic_price3']) ? floatval($this->config['basic_price3']):(floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price3'])?floatval($row['basic_price3']):floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        }else{
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                $store['delivery_price'] = floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price']));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        }

//		if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
//		    $store['delivery_price'] = floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price']));//起送价
//		    $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);//起送价
//		} else {
//		    $store['extra_price'] = floatval($row['extra_price']);
//		    $store['delivery_price'] = floatval($row['basic_price']);//起送价
//		}

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
		$today = date('Ymd');

		$product_list = D('Shop_goods')->get_list_by_storeid($store_id,0,'',1);
		foreach ($product_list as $row) {
			$temp = array();
			$temp['cat_id'] = $row['sort_id'];
			$temp['cat_name'] = $row['sort_name'];
			$temp['sort_discount'] = $row['sort_discount']/10;
			foreach ($row['goods_list'] as $r) {
				$glist = array();
				$glist['product_id'] = $r['goods_id'];
				$glist['product_name'] = $r['name'];
				$glist['product_price'] = $r['price'];
				$glist['is_seckill_price'] = $r['is_seckill_price'];
				$glist['o_price'] = $r['o_price'];
				$glist['number'] = $r['number'];
				$glist['min_num'] = $r['min_num'];
				$this->user_session['uid'] && $glist['user_buy_num']  = D('Shop_goods')->getBuyGoodsNum($this->user_session['uid'],$r['goods_id']);
				//如果设置了最小起购，限购就无效
				if ($r['min_num'] > 1) {
				    $glist['max_num'] = 0;
				} else {
				    $glist['max_num'] = $r['max_num'];
				}
				$glist['limit_type'] = $r['limit_type'];
				$glist['packing_charge'] = floatval($r['packing_charge']);
				$glist['unit'] = $r['unit'];
				if(is_array($r['pic_arr'][0]['url'])){
				    $glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
                }else{
                    $glist['product_image'] =$r['pic_arr'][0]['url'];
                }

				$glist['is_new'] = ($r['last_time'] + 864000) < time() ? 0 : 1;
				$glist['product_sale'] = $r['sell_count'];
				$glist['product_reply'] = $r['reply_count'];
				$glist['spec_value'] = $r['spec_value'];
				$glist['is_properties'] = $r['is_properties'];
				$glist['has_format'] = false;
				if ($r['spec_value'] || $r['is_properties']) {
					$glist['has_format'] = true;
				}
				if($r['extra_pay_price']>0){
					$glist['extra_pay_price']=$r['extra_pay_price'];
					$glist['extra_pay_price_name']=$this->config['extra_price_alias_name'];
				}

				$r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
				if ($today == $r['sell_day']) {
//					$glist['stock'] = $r['stock_num'] == -1 ? $r['stock_num'] : (intval($r['stock_num'] - $r['today_sell_count']) > 0 ? intval($r['stock_num'] - $r['today_sell_count']) : 0);
                    $glist['stock'] = $r['stock_num'];
				} else {
					$glist['stock'] = $r['original_stock'];
				}
				$temp['product_list'][] = $glist;
			}
			$list[] = $temp;
		}
		echo json_encode(array('store' => $store, 'product_list' => $list));
    }

    public function ajaxShop()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;
        $where = array('store_id' => $store_id);
        $now_store = D('Merchant_store')->field(true)->where($where)->find();

        //资质认证
        if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
            echo json_encode(array());
            exit;
        }
        $now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
        $now_mer = M('Merchant')->field('isverify')->where(array('mer_id'=>$now_store['mer_id']))->find();
        if (empty($now_shop) || empty($now_store)) {
            echo json_encode(array());
            exit;
        }
        $auth_files = array();
        if (!empty($now_store['auth_files'])) {
            $auth_file_class = new auth_file();
            $tmp_pic_arr = explode(';', $now_store['auth_files']);
            foreach($tmp_pic_arr as $key => $value){
                $auth_files[] = $auth_file_class->get_image_by_path($value, 'm');//array('title' => $value, 'url' => $auth_file_class->get_image_by_path($value, 's'));
            }
        }
        $now_store['kf_url'] = '';
        if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $now_store['mer_id']))->select()) {
            $key = $this->get_encrypt_key(array('app_id' => $this->config['im_appid'], 'openid' => $_SESSION['openid']), $this->config['im_appkey']);
            $kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com') . '/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $now_store['mer_id'];
            $now_store['kf_url'] = $kf_url;
        }

        $now_store['auth_files'] = $auth_files;
//         $discounts = D('Shop_discount')->get_discount_byids(array($store_id));
        $discounts = D('Shop_discount')->getDiscounts($now_store['mer_id'], $store_id);
        $row = array_merge($now_store, $now_shop);
        $store = array();
        $store_image_class = new store_image();
        $images = $store_image_class->get_allImage_by_path($row['pic_info']);

        $store['id'] = $row['store_id'];
        $store['kf_url'] = $row['kf_url'];

        $store['phone'] = $row['phone'];
        $store['long'] = $row['long'];
        $store['lat'] = $row['lat'];
        $store['tmpl'] = $row['store_theme'] ? ($row['is_mult_class'] ? 0 : 1) : 0;
        $store['store_theme'] = $row['store_theme'];
        $store['adress'] = $row['adress'];
        $store['is_close'] = 1;
        $store['isverify'] = $now_mer['isverify'];
		$store['store_live_url'] = $now_shop['store_live_url'];
        $now_time = date('H:i:s');

        $store['time'] = D('Merchant_store_shop')->getBuniessName($row);
        if (D('Merchant_store_shop')->checkTime($row)) {
            $store['is_close'] = 0;
        }
        $store['is_close'] = $store['is_close'] ? $store['is_close'] : $row['is_close'];
        $store['close_reason'] = $row['close_reason'];

        $store['home_url'] = U('Index/index', array('token' => $row['mer_id']));
        $store['name'] = $row['name'];
        $store['store_notice'] = $row['store_notice'];
        $store['txt_info'] = $row['txt_info'];
        $store['image'] = isset($images[0]) ? $images[0] : '';
        $store['auth_files_str'] = implode(',', $auth_files);
        $store['auth_files'] = $auth_files;
        $store['images'] = $images;
        $store['images_str'] = implode(',', $images);
        $store['star'] = $row['score_mean'];
        $store['reply_count'] = $row['reply_count'];
        $store['reply_deliver_score'] = $row['reply_deliver_score'];
        $store['month_sale_count'] = $row['sale_count'];
        $store['is_new'] = ($row['create_time'] + 864000) < time() ? 0 : 1;
        $store['deliver_type'] = $row['deliver_type'];
        $store['delivery'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送
//         $store['delivery_time'] = $row['send_time'];//配送时长
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
        $store['send_time_type'] = $row['send_time_type'];//时间单位类型
        $store['delivery_time_type'] = $this->send_time_type[$row['send_time_type']];//时间单位
        $store['delivery_price'] = floatval($row['basic_price']);//起送价

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
                $store['delivery_price'] = floatval($row['s_basic_price1']) ? floatval($row['s_basic_price1']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) :(floatval($set['basic_price1']) ? floatval($set['basic_price1']): (floatval($this->config['basic_price1']) ? floatval($this->config['basic_price1']):(floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price1'])?floatval($row['basic_price1']):floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        }elseif($selectDeliverTime==2){
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                $store['delivery_price'] = floatval($row['s_basic_price2']) ? floatval($row['s_basic_price2']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) :(floatval($set['basic_price2']) ? floatval($set['basic_price2']): (floatval($this->config['basic_price2']) ? floatval($this->config['basic_price2']):(floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price2'])?floatval($row['basic_price2']):floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }

        }elseif($selectDeliverTime==3){
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                $store['delivery_price'] = floatval($row['s_basic_price3']) ? floatval($row['s_basic_price3']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($set['basic_price3']) ? floatval($set['basic_price3']):(floatval($this->config['basic_price3']) ? floatval($this->config['basic_price3']):(floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price3'])?floatval($row['basic_price3']):floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        }else{
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                $store['delivery_price'] = floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price']));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['delivery_price'] = floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        }

//        if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
//            $store['delivery_price'] = floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : ($this->config['basic_price'] ? floatval($this->config['basic_price']) : floatval($row['basic_price']));//起送价
//            $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
//        } else {
//            $store['delivery_price'] = floatval($row['basic_price']);//起送价
//            $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
//        }
        if($row['s_is_open_virtual']==1){
            $store['delivery_money'] = floatval($row['virtual_delivery_fee']);
        }else{
            $store['delivery_money'] = floatval($store['delivery_money']);
        }
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
                            'minus' => floatval($row['delivery_fee']),
							'delivery_free'=>true
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
        if ($store['tmpl']) {
            $today = date('Ymd');
            $product_list = D('Shop_goods')->get_list_by_storeid($store_id,0,'',1);
            foreach ($product_list as $row) {
                $temp = array();
                $temp['cat_id'] = $row['sort_id'];
                $temp['cat_name'] = $row['sort_name'];
                $temp['sort_discount'] = $row['sort_discount']/10;
                foreach ($row['goods_list'] as $r) {
                    $glist = array();
                    $glist['product_id'] = $r['goods_id'];
					$this->user_session['uid'] && $glist['user_buy_num']  = D('Shop_goods')->getBuyGoodsNum($this->user_session['uid'],$r['goods_id']);
                    $glist['product_name'] = $r['name'];
                    $glist['product_price'] = $r['price'];
                    $glist['is_seckill_price'] = $r['is_seckill_price'];
                    $glist['o_price'] = $r['o_price'];
                    $glist['number'] = $r['number'];
                    //如果设置了最小起购，限购就无效
                    if ($r['min_num'] > 1) {
                        $glist['max_num'] = 0;
                    } else {
                        $glist['max_num'] = $r['max_num'];
                    }
                    $glist['min_num'] = $r['min_num'];
                    $glist['limit_type'] = $r['limit_type'];
                    $glist['packing_charge'] = floatval($r['packing_charge']);
                    $glist['unit'] = $r['unit'];
                    if(is_array($r['pic_arr'][0]['url'])){
                        $glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
                    }else{
                        $glist['product_image'] =$r['pic_arr'][0]['url'];
                    }
                    $glist['is_new'] = ($r['last_time'] + 864000) < time() ? 0 : 1;
                    $glist['product_sale'] = $r['sell_count'];
                    $glist['product_reply'] = $r['reply_count'];
                    $glist['spec_value'] = $r['spec_value'];
                    $glist['is_properties'] = $r['is_properties'];
                    $glist['has_format'] = false;
                    if ($r['spec_value'] || $r['is_properties']) {
                        $glist['has_format'] = true;
                    }
                    if($r['extra_pay_price']>0){
                        $glist['extra_pay_price']=$r['extra_pay_price'];
                        $glist['extra_pay_price_name']=$this->config['extra_price_alias_name'];
                    }

                    if ($r['seckill_type'] == 1) {
                        $now_time = date('H:i');
                        $open_time = date('H:i', $r['seckill_open_time']);
                        $close_time = date('H:i', $r['seckill_close_time']);

                        //秒杀库存的计算
                        if ($today == $r['sell_day']) {
                            $seckill_stock_num = $r['seckill_stock'] == -1 ? -1 : (intval($r['seckill_stock'] - $r['today_seckill_count']) > 0 ? intval($r['seckill_stock'] - $r['today_seckill_count']) : 0);
                        } else {
                            $seckill_stock_num = $r['seckill_stock'];
                        }
                    } else {
                        $now_time = time();
                        $open_time = $r['seckill_open_time'];
                        $close_time = $r['seckill_close_time'];
                        $seckill_stock_num = $r['seckill_stock'] == -1 ? -1 : (intval($r['seckill_stock'] - $r['today_seckill_count']) > 0 ? intval($r['seckill_stock'] - $r['today_seckill_count']) : 0);
                    }

                    $r['is_seckill_price'] = false;
                    $r['o_price'] = floatval($r['price']);
                    if ($open_time < $now_time && $now_time < $close_time && floatval($r['seckill_price']) > 0 && $seckill_stock_num != 0) {
                        $r['price'] = floatval($r['seckill_price']);
                        $r['is_seckill_price'] = true;
                    } else {
                        $r['price'] = floatval($r['price']);
                    }


                    $r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
                    if ($r['is_seckill_price']) {
//                         $glist['stock'] = $seckill_stock_num;
                    } else {
                        if ($today == $r['sell_day']) {
//                             $glist['stock'] = $r['stock_num'] == -1 ? $r['stock_num'] : (intval($r['stock_num'] - $r['today_sell_count']) > 0 ? intval($r['stock_num'] - $r['today_sell_count']) : 0);
                            $glist['stock'] = $r['stock_num'];
                        } else {
                            $glist['stock'] = $r['original_stock'];
                        }
                    }
                    $temp['product_list'][] = $glist;
                }
                $list[] = $temp;
            }
            echo json_encode(array('store' => $store, 'product_list' => $list));
        } else {
            $sortList = D('Shop_goods_sort')->lists($store_id, true);
            $firstSort = reset($sortList);
            $sortId = isset($firstSort['sort_id']) ? $firstSort['sort_id'] : 0;
            echo json_encode(array('store' => $store, 'product_list' => $this->getGoodsBySortId($sortId, $store_id,1), 'sort_list' => $this->formatList($sortList)));
        }
    }
    //获取时段起送价
    public function ajax_basic_price()
    {
        $where = array('store_id' => $_GET['store_id']);
        $now_store = D('Merchant_store')->field(true)->where($where)->find();
        $now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
        $row = array_merge($now_store, $now_shop);
        $deliverReturn = D('Deliver_set')->getDeliverInfo($row);

        $start_time = $deliverReturn['delivertime_start'];
        $stop_time = $deliverReturn['delivertime_stop'];

        $start_time2 = $deliverReturn['delivertime_start2'];
        $stop_time2 = $deliverReturn['delivertime_stop2'];

        $start_time3 = $deliverReturn['delivertime_start3'];
        $stop_time3 = $deliverReturn['delivertime_stop3'];


        $time = time();
        $selectDeliverTime = 0;
        if (!($start_time == $stop_time && $start_time == '00:00:00')) {
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
            if (!($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
                $start_time2 = strtotime(date('Y-m-d') . ' ' . $start_time2);
                $stop_time2 = strtotime(date('Y-m-d') . ' ' . $stop_time2);
                if ($start_time2 > $stop_time2) {
                    if (strtotime(date('Y-m-d')) <= $time && $time <= $stop_time2) {
                        $selectDeliverTime = 2;
                    } else {
                        $stop_time2 += 86400;
                        if ($start_time2 <= $time && $time <= $stop_time2) {
                            $selectDeliverTime = 2;
                        }
                    }
                } elseif ($start_time2 <= $time && $time <= $stop_time2) {
                    $selectDeliverTime = 2;
                }
            }
            if (!($start_time3 == $stop_time3 && $start_time3 == '00:00:00')) {
                $start_time3 = strtotime(date('Y-m-d') . ' ' . $start_time3);
                $stop_time3 = strtotime(date('Y-m-d') . ' ' . $stop_time3);

                if ($start_time3 > $stop_time3) {
                    if (strtotime(date('Y-m-d')) <= $time && $time <= $stop_time3) {
                        $selectDeliverTime = 3;
                    } else {
                        $stop_time3 += 86400;
                        if ($start_time3 <= $time && $time <= $stop_time3) {
                            $selectDeliverTime = 3;
                        }
                    }
                } elseif ($start_time3 <= $time && $time <= $stop_time3) {
                    $selectDeliverTime = 3;
                }
            }
        }else{
            $selectDeliverTime = 1;
        }
        $store = [];
        $set = D('Deliver_set')->field(true)->where(array('area_id' => $row['area_id'], 'status' => 1))->find();
        if (empty($set)) {
            $set = D('Deliver_set')->field(true)->where(array('area_id' => $row['city_id'], 'status' => 1))->find();
            if (empty($set)) {
                $set = D('Deliver_set')->field(true)->where(array('area_id' => $row['province_id'], 'status' => 1))->find();
            }
        }
        if ($selectDeliverTime == 1) {
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                $store['basic_price'] = floatval($row['s_basic_price1']) ? floatval($row['s_basic_price1']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($set['basic_price1']) ? floatval($set['basic_price1']):(floatval($this->config['basic_price1']) ? floatval($this->config['basic_price1']) : (floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['basic_price'] = floatval($row['basic_price1']) ? floatval($row['basic_price1']) : floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        } elseif ($selectDeliverTime == 2) {
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                $store['basic_price'] = floatval($row['s_basic_price2']) ? floatval($row['s_basic_price2']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($set['basic_price2']) ? floatval($set['basic_price2']):(floatval($this->config['basic_price2']) ? floatval($this->config['basic_price2']) : (floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['basic_price'] = floatval($row['basic_price2']) ? floatval($row['basic_price2']) : floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }

        } elseif ($selectDeliverTime == 3) {
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                $store['basic_price'] = floatval($row['s_basic_price3']) ? floatval($row['s_basic_price3']) : (floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($set['basic_price3']) ? floatval($set['basic_price3']):(floatval($this->config['basic_price3']) ? floatval($this->config['basic_price3']) : (floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price'])))));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['basic_price'] = floatval($row['basic_price3']) ? floatval($row['basic_price3']) : floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        }else{
            if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
                $store['basic_price'] = floatval($row['s_basic_price']) ? floatval($row['s_basic_price']) : (floatval($this->config['basic_price']) ? floatval($this->config['basic_price']) : floatval($row['basic_price']));//起送价
                $store['extra_price'] = floatval($row['s_extra_price']) ? floatval($row['s_extra_price']) : floatval($this->config['extra_price']);
            } else {
                $store['basic_price'] = floatval($row['basic_price']);//起送价
                $store['extra_price'] = floatval($row['extra_price']);//不满起送价另付金额支持配送
            }
        }
        echo json_encode($store);
    }
    private function formatList($list)
    {
        if (empty($list)) {
            return $list;
        }
        $result = array();
        foreach ($list as $l) {
            $l['son_list'] = $this->formatList($l['son_list']);
            $result[] = $l;
        }
        return $result;
    }
    
    private function getGoodsBySortId($sortId, $store_id,$is_shop=0)
    {
        $now_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find();
        $sortIds = D('Shop_goods_sort')->getAllSonIds($sortId, $store_id);
        if($is_shop == 1){
            $product_list = D('Shop_goods')->getGoodsBySortIds($sortIds, $store_id,false,'sort DESC, goods_id ASC',1);
        }else{
            $product_list = D('Shop_goods')->getGoodsBySortIds($sortIds, $store_id);
        }
        $list = array();
        foreach ($product_list as $row) {
            $temp = array();
            $temp['cat_id'] = $row['sort_id'];
            $temp['cat_name'] = $row['sort_name'];
            $temp['sort_discount'] = $row['sort_discount']/10;
            foreach ($row['goods_list'] as $r) {
                $glist = array();
                $glist['product_id'] = $r['goods_id'];
				$this->user_session['uid'] && $glist['user_buy_num']  = D('Shop_goods')->getBuyGoodsNum($this->user_session['uid'],$r['goods_id']);

				$glist['product_name'] = $r['name'];
                $glist['product_price'] = $r['price'];
                $glist['is_seckill_price'] = $r['is_seckill_price'];
                $glist['o_price'] = $r['o_price'];
                $glist['number'] = $r['number'];
                //如果设置了最小起购，限购就无效
                if ($r['min_num'] > 1) {
                    $glist['max_num'] = 0;
                } else {
                    $glist['max_num'] = $r['max_num'];
                }
                $glist['min_num'] = $r['min_num'];
                $glist['limit_type'] = $r['limit_type'];
                $glist['packing_charge'] = floatval($r['packing_charge']);
                $glist['unit'] = $r['unit'];
                if(is_array($r['pic_arr'][0]['url'])){
                    $glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
                }else{
                    $glist['product_image'] =$r['pic_arr'][0]['url'];
                }
                $glist['is_new'] = ($r['last_time'] + 864000) < time() ? 0 : 1;
                $glist['product_sale'] = $r['sell_count'];
                $glist['product_reply'] = $r['reply_count'];
                $glist['spec_value'] = $r['spec_value'];
                $glist['is_properties'] = $r['is_properties'];
                $glist['has_format'] = false;
                if ($r['spec_value'] || $r['is_properties']) {
                    $glist['has_format'] = true;
                }
                if($r['extra_pay_price']>0){
                    $glist['extra_pay_price']=$r['extra_pay_price'];
                    $glist['extra_pay_price_name']=$this->config['extra_price_alias_name'];
                }

                if ($r['seckill_type'] == 1) {
                    $now_time = date('H:i');
                    $open_time = date('H:i', $r['seckill_open_time']);
                    $close_time = date('H:i', $r['seckill_close_time']);

                    //秒杀库存的计算
                    if ($today == $r['sell_day']) {
                        $seckill_stock_num = $r['seckill_stock'] == -1 ? -1 : (intval($r['seckill_stock'] - $r['today_seckill_count']) > 0 ? intval($r['seckill_stock'] - $r['today_seckill_count']) : 0);
                    } else {
                        $seckill_stock_num = $r['seckill_stock'];
                    }
                } else {
                    $now_time = time();
                    $open_time = $r['seckill_open_time'];
                    $close_time = $r['seckill_close_time'];
                    $seckill_stock_num = $r['seckill_stock'] == -1 ? -1 : (intval($r['seckill_stock'] - $r['today_seckill_count']) > 0 ? intval($r['seckill_stock'] - $r['today_seckill_count']) : 0);
                }

                $r['is_seckill_price'] = false;
                $r['o_price'] = floatval($r['price']);
                if ($open_time < $now_time && $now_time < $close_time && floatval($r['seckill_price']) > 0 && $seckill_stock_num != 0) {
                    $r['price'] = floatval($r['seckill_price']);
                    $r['is_seckill_price'] = true;
                } else {
                    $r['price'] = floatval($r['price']);
                }


                $r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
                if ($r['is_seckill_price']) {
                    $glist['stock'] = $seckill_stock_num;
                } else {
                    if ($today == $r['sell_day']) {
//                         $glist['stock'] = $r['stock_num'] == -1 ? $r['stock_num'] : (intval($r['stock_num'] - $r['today_sell_count']) > 0 ? intval($r['stock_num'] - $r['today_sell_count']) : 0);
                        $glist['stock'] = $r['stock_num'];
                    } else {
//                         $glist['stock'] = $r['stock_num'];
                        $glist['stock'] = $r['original_stock'];
                    }
                }
                $temp['product_list'][] = $glist;
            }
            $list[] = $temp;
        }
        return $list;
    }
    public function showGoodsBySortId()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;
        $where = array('store_id' => $store_id);
        $now_store = D('Merchant_store')->field(true)->where($where)->find();

        //资质认证
        if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
            echo json_encode(array());
            exit;
        }
        $now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
        $now_mer = M('Merchant')->field('isverify')->where(array('mer_id'=>$now_store['mer_id']))->find();
        if (empty($now_shop) || empty($now_store)) {
            echo json_encode(array());
            exit;
        }
        $sortId = isset($_GET['sort_id']) ? $_GET['sort_id'] : 0;
        echo json_encode(array('product_list' => $this->getGoodsBySortId($sortId, $store_id)));

    }
	public function scanGood()
	{
		$number = isset($_POST['good_id']) ? $_POST['good_id']: 0;
		$database_shop_goods = D('Shop_goods');
		$now_goods = $database_shop_goods->where(array('number'=>$number,'store_id'=>$_POST['store_id']))->find();

		if(empty($now_goods)){
			$this->error('商品不存在！');
		}

		echo json_encode(array('url'=>$this->config['site_url'].'/wap.php?c=Shop&a=detail&goods_id='.$now_goods['goods_id']));
	}

	public function ajax_goods()
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
		$now_goods['des_share'] = trim(strip_tags($now_goods['des']));
		echo json_encode($now_goods);
	}

	public function ajax_search_goods()
	{
		if(empty($_POST['key'])){
			$this->error('请输入商品名');
		}
		if(empty($_POST['store_id'])){
			$this->error('店铺不存在');
		}
		$where['name'] =array('like','%'.$_POST['key'].'%');
		$where['store_id'] = $_POST['store_id'];
		$database_shop_goods = D('Shop_goods');
		$now_goods = $database_shop_goods->get_list_by_condition($where);
		if(empty($now_goods)){
			$this->error('商品不存在！');
		}
		//$now_goods['des_share'] = trim(strip_tags($now_goods['des']));
		echo json_encode($now_goods);
	}

	/*我的收货地址*/
	public function ajax_address()
	{
		$return = array();
		if ($this->user_session['uid']) {
			$adress_list = D('User_adress')->get_adress_list($this->user_session['uid']);
			foreach ($adress_list as $row) {
				$return[] = array('id'=>$row['adress_id'],'street' => $row['adress'], 'house' => $row['detail'], 'name' => $row['name'], 'phone' => $row['phone'], 'long' => $row['longitude'], 'lat' => $row['latitude']);
			}
		}
		echo json_encode($return);
	}

	private function getCookieOldData($store_id,$cart_index='shop_cart_'){
		$productCart = json_decode(cookie($cart_index . $store_id), true);
		if (empty($productCart)) {
			$productCart = array();
			for ($i = 0; $i < 20; $i++) {
				$tmpCookie = cookie('shop_cart_' . $store_id . '_' . $i);

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
		}
		return $productCart;
	}

    private function getCookieData($store_id,$cart_index='shop_cart_')
    {
		$cart_cookie = cookie('cart_cookie');
		if(!$cart_cookie || !$store_id){
			return $this->getCookieOldData($store_id,$cart_index);
		}
		if($this->user_session['uid']){
			$where['uid'] = $this->user_session['uid'];
			$where['cart_cookie'] = $cart_cookie;
			$where['_logic'] = 'or';
			$condition['_complex'] = $where;
		}else{
			$condition['cart_cookie'] = $cart_cookie;
		}
		$condition['shop_id'] = $store_id;
		$cartList = M('Shop_cart')->where($condition)->order('`id` ASC')->select();
		if(!$cartList){
			$cartList = array();
			return $this->getCookieOldData($store_id,$cart_index);
		}else{
			foreach($cartList as &$cart_value){
				$cart_value['productParam'] = $cart_value['productParam'] ? unserialize($cart_value['productParam']) : array();
				$cart_value['count'] = intval($cart_value['count']);
				$cart_value['isSeckill'] = $cart_value['isSeckill'] == 'false' ? false : true;
			}
		}
		return $cartList;
    }

    public function confirm_order()
    {
        $this->isLogin();
        //delivery_type 0:平台配送，1：商家配送，2：自提，3:平台配送或自提，4：商家配送或自提
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $cartid = isset($_GET['cartid']) ? htmlspecialchars(trim($_GET['cartid'])) : '';
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $deliverExtraPrice = isset($_GET['deliverExtraPrice']) ? intval($_GET['deliverExtraPrice']) : 0;//配送附加费

        if ($cartid && ($orderTemp = D('Shop_order_temp')->field(true)->where(array('cartid' => $cartid, 'store_id' => $store_id))->find())) {
            $list = json_decode($orderTemp['info'], true);
            $data = array();
            foreach ($list as $rowset) {
                if (isset($rowset['data']) && $rowset['data']) {
                    $data = array_merge($data, $rowset['data']);
                }
            }
            $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $data);
            $this->assign('cartid', $cartid);
        } elseif ($order_id && ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid'])))) {
            $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $order['info'], 0);
            $this->assign('order_id', $order_id);
        } else {
            $cookieData = $this->getCookieData($store_id);
            if(empty($cookieData)) {
                redirect(U('Shop/index') . '#shop-' . $store_id);
                exit;
            }
            $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);
        }

        if ($return['error_code']) {
            $this->error_tips($return['msg']);
        }
        if ($_GET['village_id']) {
            $village_id = intval($_GET['village_id']);
        } elseif (cookie('visit_village_id')) {
            $village_id = cookie('visit_village_id');
        } else {
            $village_id = 0;
        }
        $this->assign('village_id', $village_id);

        //判断商家是否开启了自有支付
        $is_own = 0;
        $merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $return['mer_id']))->find();
        foreach ($merchant_ownpay as $ownKey => $ownValue) {
            $ownValueArr = unserialize($ownValue);
            if ($ownValueArr['open']) {
                //$is_own = 1;
            }
        }
        if ($is_own) {
            if ($return['delivery_type'] == 0) {
                $this->error_tips('商家配置的配送信息不正确');
            } elseif ($return['delivery_type'] == 3) {
                $return['delivery_type'] = 2;
            }
        }

        $return['this_discount_price'] = round($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce'], 2) + ($return['price'] - $return['vip_discount_money']);
        $basic_price = $return['price'];//商品实际总价
        if ($this->config['open_extra_price'] > 0) {
            $basic_price += $return['extra_price'];
        }
        $return['price'] = round($return['vip_discount_money'] - round($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce'], 2), 2);//实际要支付商品的总价格

        $return['price'] += $return['store']['pack_alias'];//商品的实际支付总加打包费



        $address_id = isset($_GET['adress_id']) ? intval($_GET['adress_id']) : cookie('userLocationId');

        $userLocationLong = cookie('userLocationLong');
        $userLocationLat = cookie('userLocationLat');
        if (empty($userLocationLong) || empty($userLocationLat)) {
            $userLocationLat = $return['store']['lat'];
            $userLocationLong = $return['store']['long'];
        }

        $user_address_temp = D('User_adress')->field(true)->where(array('uid' => $this->user_session['uid']))->order('`default` DESC,`adress_id` DESC')->select();
        $return['address_count'] = count($user_address_temp);
        $user_adress = array();
        $distance_array = array();
        $user_address_list = array();
        foreach ($user_address_temp as $address) {
            $address['distance'] = getDistance($address['latitude'], $address['longitude'], $userLocationLat, $userLocationLong);
            if ($return['store']['delivery_range_type'] == 0) {
                $distance = getDistance($address['latitude'], $address['longitude'], $return['store']['lat'], $return['store']['long']);
                $distance = $distance / 1000;
                if ($return['store']['delivery_radius'] < $distance && $return['delivery_type'] != 5) {
                    continue;
                }
            } else {
                if ($return['store']['delivery_range_polygon']) {
                    if (!isPtInPoly($address['longitude'], $address['latitude'], $return['store']['delivery_range_polygon']) && $return['delivery_type'] != 5) {
                        continue;
                    }
                } else {
                    continue;
                }
            }

            if ($address_id == $address['adress_id']) {
                $user_adress = $address;
                break;
            }

            if(empty($address_id) && $address['default']){
                $user_adress = $address;
                break;
            }
            $distance_array[] = $address['distance'];
            $user_address_list[] = $address;
        }

        if (empty($user_adress) && $distance_array && $user_address_list) {
            array_multisort($distance_array, SORT_ASC, $user_address_list);
            $user_adress = $user_address_list[0];
        }
        if ($user_adress) {
            $province = D('Area')->get_area_by_areaId($user_adress['province'],false);
            $user_adress['province_txt'] = $province['area_name'];

            $city = D('Area')->get_area_by_areaId($user_adress['city'],false);
            $user_adress['city_txt'] = $city['area_name'];

            $area = D('Area')->get_area_by_areaId($user_adress['area'],false);
            $user_adress['area_txt'] = $area['area_name'];
        }

        $extraPrice = $return['store']['extra_price'];
        if ($return['store']['basic_price'] <= floatval($basic_price + $return['packing_charge']) || ($deliverExtraPrice == 1 && $extraPrice > 0)) {
            $this->assign('user_adress', $user_adress);
        } else {
            if (in_array($return['delivery_type'], array(2, 3, 4))) {
                if (!($deliverExtraPrice == 1 && $extraPrice > 0)) {
                    $return['delivery_type'] = 2;
                }
            } elseif (!($deliverExtraPrice == 1 && $extraPrice > 0)) {
                $this->error_tips('没有达到起送价，不能使用配送');
            }
        }
        if ($return['delivery_type'] == 2 || $return['delivery_type'] == 5 || $return['store']['basic_price'] <= floatval($basic_price + $return['packing_charge'])) {
            $extraPrice = 0;
        }
        //计算配送费
        if ($user_adress) {
            $distance = 0;
            if (C('config.is_riding_distance')) {
                import('@.ORG.longlat');
                $longlat_class = new longlat();
                $distance = $longlat_class->getRidingDistance($user_adress['latitude'], $user_adress['longitude'], $return['store']['lat'], $return['store']['long']);
            }
            $distance || $distance = getDistance($user_adress['latitude'], $user_adress['longitude'], $return['store']['lat'], $return['store']['long']);

            $distance = $distance / 1000;
            $pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
            $return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
            $return['delivery_fee_old'] = $return['delivery_fee'];
            $return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
            $return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
            
            
            $pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
            $return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
            $return['delivery_fee2_old'] = $return['delivery_fee2'];
            $return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
            $return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;
            
            
            $pass_distance = $distance > $return['basic_distance3'] ? floatval($distance - $return['basic_distance3']) : 0;
            $return['delivery_fee3'] += round($pass_distance * $return['per_km_price3'], 2);
            $return['delivery_fee3_old'] = $return['delivery_fee3'];
            $return['delivery_fee3'] = $return['delivery_fee3'] - $return['delivery_fee_reduce'];
            $return['delivery_fee3'] = $return['delivery_fee3'] > 0 ? $return['delivery_fee3'] : 0;

            switch (intval($this->config['count_freight_charge_method'])) {
                case 0:
                    break;
                case 1:
                    $return['delivery_fee'] = ceil($return['delivery_fee'] * 10) / 10;
                    $return['delivery_fee2'] = ceil($return['delivery_fee2'] * 10) / 10;
                    $return['delivery_fee3'] = ceil($return['delivery_fee3'] * 10) / 10;
                    break;
                case 2:
                    $return['delivery_fee'] = floor($return['delivery_fee'] * 10) / 10;
                    $return['delivery_fee2'] = floor($return['delivery_fee2'] * 10) / 10;
                    $return['delivery_fee3'] = floor($return['delivery_fee3'] * 10) / 10;
                    break;
                case 3:
                    $return['delivery_fee'] = round($return['delivery_fee'] * 10) / 10;
                    $return['delivery_fee2'] = round($return['delivery_fee2'] * 10) / 10;
                    $return['delivery_fee3'] = round($return['delivery_fee3'] * 10) / 10;
                    break;
                case 4:
                    $return['delivery_fee'] = ceil($return['delivery_fee']);
                    $return['delivery_fee2'] = ceil($return['delivery_fee2']);
                    $return['delivery_fee3'] = ceil($return['delivery_fee3']);
                    break;
                case 5:
                    $return['delivery_fee'] = floor($return['delivery_fee']);
                    $return['delivery_fee2'] = floor($return['delivery_fee2']);
                    $return['delivery_fee3'] = floor($return['delivery_fee3']);
                    break;
                case 6:
                    $return['delivery_fee'] = round($return['delivery_fee']);
                    $return['delivery_fee2'] = round($return['delivery_fee2']);
                    $return['delivery_fee3'] = round($return['delivery_fee3']);
                    break;
            }
        }

        $date_list = D('Shop_goods')->getSelectTime($return);
        foreach ($date_list as $key => $rowset) {
            $temp = array();
            asort($rowset);
            foreach ($rowset as $rv) {
                $temp[] = $rv;
            }
            if ($key == date('Y-m-d')) {
                $d_list[] = array('ymd' => $key, 'show_date' => '今天', 'date_list' => $temp);
            } else if($key == date('Y-m-d',time()+86400)){
                $d_list[] = array('ymd' => $key, 'show_date' => '明天', 'date_list' => $temp);
            } else if($key == date('Y-m-d',time()+86400*2)){
                $d_list[] = array('ymd' => $key, 'show_date' => '后天', 'date_list' => $temp);
            } else {
                $d_list[] = array('ymd' => $key, 'show_date' => date('m月d日', strtotime($key)), 'date_list' => $temp);
            }
        }
        if (($return['delivery_type'] == 0 || $return['delivery_type'] == 1) && empty($d_list)) {
            $this->error_tips('抱歉,当前暂无可配送的预计送达时间');
        }
        $this->assign('dates', $d_list);

        $pick_addr_id = isset($_GET['pick_addr_id']) ? $_GET['pick_addr_id'] : '';
        $pick_list = D('Pick_address')->get_pick_addr_by_merid($return['mer_id'], true, $store_id);
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
        $goodsList = $return['goods'];
//         echo '<pre/>';
//         print_r($goodsList);die;
        $tempList = array();
        foreach ($goodsList as $goods) {
            $index = isset($goods['packname']) && $goods['packname'] ? $goods['packname'] : 0;
            if (isset($tempList[$index])) {
                $tempList[$index]['list'][] = $goods;
            } else {
                $tempList[$index] = array(
                    'name' => $goods['packname'],
                    'list' => array($goods)
                );
            }
        }
        if (count($tempList) == 1) {
            $tempList[$index]['name'] = '';
        }
        $return['goods'] = $tempList;
        $this->assign($return);
        $this->assign('pick_addr_id', $pick_addr_id);
        $this->assign('pick_address', $pick_address);

        $now_store_category_relation = M('Shop_category_relation')->where(array('store_id' => $return['store_id']))->find();
        $now_store_category = M('Shop_category')->where(array('cat_id' => $now_store_category_relation['cat_id']))->find();
        if ($now_store_category['cue_field']) {
            $this->assign('cue_field', unserialize($now_store_category['cue_field']));
        }
        $plat_discount = D('Merchant')->getDiscount($return['store']['mer_id']);
        $plat_discount = floatval($plat_discount);
        if ($plat_discount) {
            $plat_discount = floatval(round($plat_discount /10, 2));
        } else {
            $plat_discount = 10;
        }
        $this->assign('plat_discount', $plat_discount);
        $this->assign('extraPrice', $extraPrice);
        $this->assign('deliverExtraPrice', $deliverExtraPrice);
        $this->display();
    }


    public function save_order()
    {
        $this->isLogin();
		$now_merchant = M('Merchant')->where(array('mer_id'=>$_GET['mer_id']))->find();
		if($now_merchant['status']!=1){
			$this->error_tips('该商家已不能订餐');
		}
        //delivery_type 0:平台配送，1：商家配送，2：自提，3:平台配送或自提，4：商家配送或自提
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $cartid = isset($_POST['cartid']) ? htmlspecialchars(trim($_POST['cartid'])) : '';
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        //-------------------------------
        if ($cartid && ($orderTemp = D('Shop_order_temp')->field(true)->where(array('cartid' => $cartid, 'store_id' => $store_id))->find())) {
            $list= json_decode($orderTemp['info'], true);
            $data = array();
            foreach ($list as $rowset) {
                if (isset($rowset['data']) && $rowset['data']) {
                    $data = array_merge($data, $rowset['data']);
                }
            }
            $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $data);
        } elseif ($order_id && ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid'])))) {
            $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $order['info'], 0);
		} else if($_POST['scan_goods']==1){
			$cookieData = $this->getCookieOldData($store_id,'shop_cart_');
			if(empty($cookieData)) {
				redirect(U('Shop/index') . '#shop-' . $store_id);
				exit;
			}
			$return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);
		}  else {
            $cookieData = $this->getCookieData($store_id);
            if(empty($cookieData)) {
                redirect(U('Shop/index') . '#shop-' . $store_id);
                exit;
            }
            $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);
        }
        //---------------------------------
		if ($return['error_code']) $this->error_tips($return['msg']);
		if (IS_POST) {
			$village_id = isset($_REQUEST['village_id']) ? intval($_REQUEST['village_id']) : 0;
			$phone = isset($_POST['ouserTel']) ? htmlspecialchars($_POST['ouserTel']) : '';
			$name = isset($_POST['ouserName']) ? htmlspecialchars($_POST['ouserName']) : '';
			$address = isset($_POST['ouserAddres']) ? htmlspecialchars($_POST['ouserAddres']) : '';
			$pick_address = isset($_POST['pick_address']) ? htmlspecialchars($_POST['pick_address']) : '';
			$invoice_head = isset($_POST['invoice_head']) ? htmlspecialchars($_POST['invoice_head']) : '';
			$address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
			$pick_id = isset($_POST['pick_id']) ? htmlspecialchars($_POST['pick_id']) : 0;
			$pickType = substr($pick_id, 0, 1);
			$pick_id = substr($pick_id, 1);
			$deliver_type = isset($_POST['deliver_type']) ? intval($_POST['deliver_type']) : 0;//0:配送，1：自提
			$arrive_time = isset($_POST['oarrivalTime']) ? htmlspecialchars($_POST['oarrivalTime']) : 0;
			$arrive_date = isset($_POST['oarrivalDate']) ? htmlspecialchars($_POST['oarrivalDate']) : 0;
			$note = isset($_POST['omark']) ? htmlspecialchars($_POST['omark']) : '';

			$extraPrice = 0;
			if ($deliver_type == 0 && floatval($return['price'] + $return['packing_charge']) < $return['store']['basic_price']) {
			    $extraPrice = $return['store']['extra_price'];
			    if (empty($extraPrice)) {
    				if (in_array($return['store']['deliver_type'], array(2, 3, 4))) {
    					$deliver_type = 1;
    				} else {
    					$this->error_tips('订单没有达到起送价，不予配送');
    				}
			    }
			}
			if ($deliver_type != 1 && $deliver_type != 9) {//配送方式是：非自提和非快递配送
				if (empty($name)) $this->error_tips('联系人不能为空');
				if (empty($phone)) $this->error_tips('联系电话不能为空');
// 				if ($return['delivery_type'] == 1 || $return['delivery_type'] == 4) {
					if ($user_address = D('User_adress')->field(true)->where(array('adress_id' => $address_id, 'uid' => $this->user_session['uid']))->find()) {
						if ($user_address['longitude'] > 0 && $user_address['latitude'] > 0) {
						    if ($return['store']['delivery_range_type'] == 0) {
    							$distance = getDistance($user_address['latitude'], $user_address['longitude'], $return['store']['lat'], $return['store']['long']);
    							$delivery_radius = $return['store']['delivery_radius'] * 1000;
    							if ($distance > $delivery_radius && $return['delivery_type'] != 5) {
    								$this->error_tips('您到本店的距离是' . $distance . '米,超过了' . $delivery_radius . '米的配送范围');
    							}
						    } else {
						        if ($return['store']['delivery_range_polygon']) {
    						        if (!isPtInPoly($user_address['longitude'], $user_address['latitude'], $return['store']['delivery_range_polygon'])) {
    						            $this->error_tips('您的地址不在本店指定的配送区域');
    						        }
						        } else {
						            $this->error_tips('您的地址不在本店指定的配送区域');
						        }
						    }
						} else {
							$this->error_tips('您选择的地址没有完善，请先编辑地址，点击“点击选择位置”进行完善', U('My/adress',array('buy_type' => 'shop', 'store_id' => $return['store_id'], 'village_id' => $village_id, 'mer_id' => $return['mer_id'], 'current_id'=>$user_adress['adress_id'])));
						}
					} else {
						$this->error_tips('地址信息不存在');
					}
// 				}
			}

			$diffTime = 60;
			if ($return['store']['send_time_type'] == 1) {
			    $diffTime = 3600;
			} elseif ($return['store']['send_time_type'] == 2) {
			    $diffTime = 86400;
			} elseif ($return['store']['send_time_type'] == 3) {
			    $diffTime = 86400 * 7;
			} elseif ($return['store']['send_time_type'] == 4) {
			    $diffTime = 86400 * 30;
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
			if($deliver_type == 9){
				$order_data['packing_charge'] = 0;
				$return['packing_charge']=  0;
			}
			$order_data['merchant_reduce'] = $return['sto_first_reduce'] + $return['sto_full_reduce'];//店铺优惠
			$order_data['balance_reduce'] = $return['sys_first_reduce'] + $return['sys_full_reduce'];//平台优惠
			$orderid  = date('ymdhis').substr(microtime(),2,8-strlen($this->user_session['uid'])).$this->user_session['uid'];
			$order_data['real_orderid'] = $orderid;
			$order_data['no_bill_money'] = 0;//无需跟平台对账的金额

			if ($deliver_type == 1) {//自提
			    $extraPrice = 0;
			    if (empty($pick_id))$this->error_tips('请选择自提点');
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
			    $order_data['expect_use_time'] = time() + $return['store']['work_time'] * $diffTime;//客户期望使用时间
			} elseif ($deliver_type == 9) {//扫码
			    $extraPrice = 0;
			    $order_data['is_pick_in_store'] = 2;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
			    $delivery_fee = $order_data['freight_charge'] = 0;//运费
			    $order_data['username'] = isset($this->user_session['nickname']) && $this->user_session['nickname'] ? $this->user_session['nickname'] : '';
			    $order_data['userphone'] = isset($this->user_session['phone']) && $this->user_session['phone'] ? $this->user_session['phone'] : '';
			    $order_data['address'] = '';
			    $order_data['address_id'] = 0;
		        $order_data['pick_id'] = 0;
		        $order_data['order_from'] = 9;
			    $order_data['expect_use_time'] = time() + $return['store']['work_time'] * $diffTime;//客户期望使用时间
			} else {//配送
				$order_data['username'] = $name;
				$order_data['userphone'] = $phone;
				$order_data['address'] = $address;
				$order_data['address_id'] = $address_id;
				$order_data['lat'] = $user_address['latitude'];
				$order_data['lng'] = $user_address['longitude'];
				if ($arrive_date == 0) {
					$arrive_date = date('Y-m-d');
				}

				$start_time = $return['store']['delivertime_start'];
				$stop_time = $return['store']['delivertime_stop'];
				
				$start_time2 = $return['store']['delivertime_start2'];
				$stop_time2 = $return['store']['delivertime_stop2'];
				
				$start_time3 = $return['store']['delivertime_start3'];
				$stop_time3 = $return['store']['delivertime_stop3'];

				if ($start_time == $stop_time && $start_time == '00:00:00') {
					$stop_time = '23:59:59';
				}

				$arrive_time = strtotime($arrive_date . $arrive_time);
				if ($arrive_time == 0) {
				    if($return['delivery_type'] != 5) $this->error_tips('请选择配送时间');
				}

				$order_data['expect_use_time'] = $arrive_time ? $arrive_time : time() + $return['store']['send_time'] + $return['store']['work_time'] * $diffTime;//客户期望使用时间

				//计算配送费
    		    $distance = 0;
    		    if (C('config.is_riding_distance')) {
    		        import('@.ORG.longlat');
    		        $longlat_class = new longlat();
    		        $distance = $longlat_class->getRidingDistance($user_address['latitude'], $user_address['longitude'], $return['store']['lat'], $return['store']['long']);
    		    }
    		    $distance || $distance = getDistance($user_address['latitude'], $user_address['longitude'], $return['store']['lat'], $return['store']['long']);
    		    $order_data['distance'] = $distance;
// 				$distance = getDistance($user_address['latitude'], $user_address['longitude'], $return['store']['lat'], $return['store']['long']);
				$distance = $distance / 1000;
				if ($return['delivery_type'] == 5) {//快递配送
					$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
					$return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
					$return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
					$return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
					$delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];//运费
				} else {
					$expect_use_time_temp = strtotime(date('Y-m-d ') . date('H:i:s', $order_data['expect_use_time']));
					if ($this->checkTime($expect_use_time_temp, $start_time, $stop_time, 1)) {
					    //时间段一
					    $pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
					    $return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
					    $return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
					    $return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
					    $delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];//运费
					} elseif ($this->checkTime($expect_use_time_temp, $start_time2, $stop_time2, 2)) {
					    //时间段二
					    $pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
					    $return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
					    $return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
					    $return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;
					    $delivery_fee = $order_data['freight_charge'] = $return['delivery_fee2'];//运费
					} elseif ($this->checkTime($expect_use_time_temp, $start_time3, $stop_time3, 3)) {
					    //时间段二
					    $pass_distance = $distance > $return['basic_distance3'] ? floatval($distance - $return['basic_distance3']) : 0;
					    $return['delivery_fee3'] += round($pass_distance * $return['per_km_price3'], 2);
					    $return['delivery_fee3'] = $return['delivery_fee3'] - $return['delivery_fee_reduce'];
					    $return['delivery_fee3'] = $return['delivery_fee3'] > 0 ? $return['delivery_fee3'] : 0;
					    $delivery_fee = $order_data['freight_charge'] = $return['delivery_fee3'];//运费
					}else {
						$this->error_tips('您选择的时间不在配送时间段内！');
					}
				}

				switch (intval($this->config['count_freight_charge_method'])) {
				    case 0:
				        break;
				    case 1:
				        $delivery_fee = $order_data['freight_charge'] = ceil($order_data['freight_charge'] * 10) / 10;
				        break;
				    case 2:
				        $delivery_fee = $order_data['freight_charge'] = floor($order_data['freight_charge'] * 10) / 10;
				        break;
				    case 3:
				        $delivery_fee = $order_data['freight_charge'] = round($order_data['freight_charge'] * 10) / 10;
				        break;
				    case 4:
				        $delivery_fee = $order_data['freight_charge'] = ceil($order_data['freight_charge']);
				        break;
				    case 5:
				        $delivery_fee = $order_data['freight_charge'] = floor($order_data['freight_charge']);
				        break;
				    case 6:
				        $delivery_fee = $order_data['freight_charge'] = round($order_data['freight_charge']);
				        break;
				}

				if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {//平台配送
					$order_data['is_pick_in_store'] = 0;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
					$order_data['no_bill_money'] = $delivery_fee + $extraPrice + $return['merchant_reduce_deliver_money'];
				} elseif ($return['delivery_type'] == 1 || $return['delivery_type'] == 4)  {
					$order_data['is_pick_in_store'] = 1;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
				} else {
					$order_data['is_pick_in_store'] = 3;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
					$extraPrice = 0;
				}
			}

			$order_data['cartid'] = $cartid;//拼单号
			$order_data['other_money'] = $extraPrice;//加价送的金额
			$order_data['goods_price'] = $return['price'];//商品的价格
			$order_data['extra_price'] = $return['extra_price'];//另外要支付的金额
			$order_data['can_discount_money'] = $return['can_discount_money'] + $delivery_fee + $return['packing_charge'] + $extraPrice;//可用商家优惠券的总价
			$order_data['discount_price'] = $return['vip_discount_money'];//商品折扣后的总价
			$order_data['total_price'] = $return['price'] + $delivery_fee + $return['packing_charge'] + $extraPrice;//订单总价  商品价格+打包费+配送费
			$order_data['price'] = $order_data['discount_price'] + $delivery_fee + $return['packing_charge'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'] + $extraPrice;//实际要支付的价格
			
			if($order_data['price'] < 0){
				$order_data['price'] = 0;
			}

			$order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情

			$order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'
			$order_data['vip_discount_money'] = $return['vip_discount_money']?$return['vip_discount_money']:0;

			//自定义字段
			if($_POST['cue_field']){
				$order_data['cue_field'] = serialize($_POST['cue_field']);
			}
			if ($order_id = D('Shop_order')->saveOrder($order_data, $return, $this->user_session)) {
			    if ($order_data['status'] == 9) {
			        D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 13, 'name' => $return['store']['name'], 'phone' => $return['store']['phone']));//接货
			    }
                /* 粉丝行为分析 */
                $this->behavior(array('mer_id' => $return['mer_id'], 'biz_id' => $order_id));
				
				if(IS_AJAX){
					$this->success(array('order_id' => $order_id));
				}else{
					redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'shop')));
				}
			} else {
				$this->error_tips('订单保存失败');
			}
		} else {
			redirect(U('confirm_order',$_GET));
		}
	}

	public function checkTime($time, $start_time, $stop_time, $select)
	{
	    $stime = strtotime(date('Y-m-d ' . $start_time));
	    $etime = strtotime(date('Y-m-d ' . $stop_time));
	    $next_stime = 0;
	    $next_etime = 0;
	    if ($etime < $stime) {
	        $etime = strtotime(date('Y-m-d 23:59:59'));
	        $next_stime = strtotime(date('Y-m-d'));
	        $next_etime = strtotime(date('Y-m-d ' . $stop_time));
	    }

	    if ($stime <= $time && $time <= $etime) {
	        return $select;
	    }
	    if ($next_stime <= $time && $time <= $next_etime) {
	        return $select;
	    }
	    return 0;
	}


	public function order_detail_old()
	{
		$this->isLogin();
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']));
		$store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
		$shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
		cookie('shop_cart_' . $store['store_id'], null);
		for($i=0;$i<20;$i++){
			if(cookie('shop_cart_' . $store['store_id'].'_'.$i)){
				cookie('shop_cart_' . $store['store_id'].'_'.$i,null);
			}else{
				break;
			}
		}
		$pick_address = M('Pick_address')->where(array('id'=>$order['pick_id']))->find();
		$order['pick_lat'] = $pick_address['lat'];
		$order['pick_lng'] = $pick_address['long'];
		$lng_lat = D('User_long_lat')->getLocation($_SESSION['openid'], 0);
		$now_merchant = D('Merchant')->get_info($order['mer_id']);

		$this->assign('now_merchant',$now_merchant );

		$this->assign('lat',$lng_lat['lat']);
		$this->assign('lng',$lng_lat['long']);
		$this->assign('store', array_merge($store, $shop));
		$this->assign('order', $order);
		$this->display();
	}


	public function orderdel()
	{
		$this->isLogin();
		$id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if ($order = M('Shop_order')->where(array('order_id' => $id, 'uid' => $this->user_session['uid']))->find()) {
// 			if ($order['status'] != 0 ) $this->error_tips('商家已经处理了此订单，现在不能取消了！');
			if ($order['paid'] == 1 ) $this->error_tips('该订单已支付，您不能取消！');
// 			if ($order['paid'] == 1 && date('m', $order['dateline']) == date('m')) {
// 				foreach (unserialize($order['info']) as $menu) {
// 					D('Meal')->where(array('meal_id' => $menu['id'], 'sell_count' => array('gt', $menu['num'])))->setDec('sell_count', $menu['num']);
// 				}
// 			}
// 			D("Merchant_store_meal")->where(array('store_id' => $order['store_id']))->setDec('sale_count', 1);
			/* 粉丝行为分析 */
			$this->behavior(array('mer_id' => $order['mer_id'], 'biz_id' => $order['store_id']));

			M('Shop_order')->where(array('order_id' => $id, 'uid' => $this->user_session['uid']))->save(array('status' => 5, 'is_rollback' => 1));//取消未支付的订单
			D('Shop_order_log')->add_log(array('order_id' => $id, 'status' => 10));

			if (($order['paid'] == 1 || $order['reduce_stock_type'] == 1) && $order['is_rollback'] == 0) {
				$details = D('Shop_order_detail')->field(true)->where(array('order_id' => $order['order_id']))->select();
				$goods_db = D("Shop_goods");
				foreach ($details as $menu) {
					$goods_db->update_stock($menu, 1);//修改库存
				}
			}


			$this->success_tips('订单取消成功', U('Shop/status', array('mer_id' => $order['mer_id'], 'store_id' => $order['store_id'], 'order_id' => $order['order_id'],'app_no_header'=>'1')));
		} else {
			$this->error_tips('订单取消失败！');
		}

	}

	/**
	 * 订单状态列表
	 */
	public function order_detail()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))) {
			$storeName = D("Merchant_store")->field('`name`, `phone`')->where(array('store_id' => $order['store_id']))->find();
			$this->assign('storeName', $storeName);

			//删除购物车
			$condition_delete['shop_id'] = $order['store_id'];
			if($this->user_session['uid']){
				$condition_delete['uid'] = $this->user_session['uid'];
			}else{
				$condition_delete['cart_cookie'] = cookie('cart_cookie');
			}
			M('Shop_cart')->where($condition_delete)->delete();
			cookie('shop_cart_' . $order['store_id'], null);
			for($i=0;$i<20;$i++){
				if(cookie('shop_cart_' . $order['store_id'].'_'.$i)){
					cookie('shop_cart_' . $order['store_id'].'_'.$i,null);
				}else{
					break;
				}
			}

			$status = D('Shop_order_log')->field(true)->where(array('order_id' => $order_id))->order('id DESC')->select();
			$statusCount = D("Shop_order_log")->where(array('order_id' => $order_id))->count();
			$this->assign('statusCount', $statusCount);

			$isShowMap = 1;
			if ($order['order_status'] > 1 && $order['order_status'] < 5) {
			    $supply = D("Deliver_supply")->where(array('order_id' => $order_id))->find();
			    if (empty($supply)) {
			        $isShowMap = 0;
			    }
			}

			$this->assign('isShowMap', $isShowMap);
			$this->assign('status', $status);
			$this->assign('order_id', $order_id);
			$this->assign('order', $order);
			$this->display('status_new');
		} else {
			$this->error_tips('错误的订单信息！');
		}
	}

    public function line()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        if ($order = M('Shop_order')->field(true)->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find()) {
            if ($order['order_status'] > 1 && $order['order_status'] < 6) {
                $supply = D("Deliver_supply")->where(array('order_id' => $order_id))->find();
                if (empty($supply)) {
                    exit(json_encode(array('err_code' => true)));
                }
                $start_time = $supply['start_time'];
                $where = array();
                $where['uid'] = $supply['uid'];
                $where['create_time'] = array('gt', $start_time);
                $lines = D("Deliver_user_location_log")->where($where)->order("`create_time` ASC")->select();
                $points = array();
                $points['from_site'] = array('lng' => $supply['from_lnt'], 'lat' => $supply['from_lat']);
                $points['aim_site'] = array('lng' => $supply['aim_lnt'], 'lat' => $supply['aim_lat']);

//                 if ($supply['status'] > 3) {
//                     array_unshift($lines, $points['aim_site']);
//                 } else {
//                     array_unshift($lines, $points['from_site']);
//                 }
                if ($lines) {
                    $center = array_pop($lines);
                } else {
                    $center = array('lng' => $supply['from_lnt'], 'lat' => $supply['from_lat']);
                }

                exit(json_encode(array('err_code' => false, 'lines' => $lines, 'status' => $supply['status'], 'points' => $points, 'center' => $center)));
            }
        }
        exit(json_encode(array('err_code' => true)));
    }


	public function map()
	{
		$order_id = I("order_id", 0, 'intval');
		if (! $order_id) {
			$this->error_tips("OrderId不能为空");
		}
		$supply = D("Deliver_supply")->where(array('order_id'=>$order_id))->find();
		if (! $supply) {
			$this->error_tips("配送源不存在");
		}
		if (! $supply['uid']) {
			$this->error_tips("订单还没有分配配送员");
		}
		$start_time = $supply['start_time'];
		if (!$start_time) {
			$this->error_tips("配送员还没有开始配送");
		}
		$end_time = $supply['end_time']? $supply['end_time']: time();
		$where = array();
		$where['uid'] = $supply['uid'];
		$where['create_time'] = array(array('gt', $start_time), array('lt', $end_time));
		$lines = D("Deliver_user_location_log")->where($where)->order("`create_time` ASC")->select();

		$points = array();
		$points['from_site'] = array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']);
		$points['aim_site'] = array('lng'=>$supply['aim_lnt'], 'lat'=>$supply['aim_lat']);

		$this->assign('supply', $supply);
		$this->assign('lines', $lines);
		if ($lines) {
			$this->assign('center', array_pop($lines));
		} else {
			$this->assign('center', array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']));
		}
		$this->assign('point', $points);
		$this->assign('order_id', $order_id);

		$this->display('map');
	}

	public function orderstatus()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if ($order = M('Shop_order')->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find()) {
			exit(json_encode(array('error_code' => false, 'data' => $order)));
		} else {
			exit(json_encode(array('error_code' => true, 'msg' => '错误的订单信息！')));
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

	//extra_price 定制的页面
	public function merchant_shop(){

		if($this->config['open_extra_price']!=1){
			$this->error_tips('非法访问！');
		}
		$store_id= $_GET['store_id'];
		$from= !isset($_GET['from'])?0:$_GET['from'];//  '0代理团购，1代表订餐 2,预约',
		$now_store = D('Merchant_store')->get_store_by_storeId($_GET['store_id']);
		if(empty($now_store)){
			$this->error_tips('该店铺不存在！');
		}
		//得到当前店铺的评分
		$store_score = D('Merchant_score')->field('`score_all`,`reply_count`')->where(array('parent_id'=>$now_store['store_id'],'type'=>'2'))->find();
		$appoint_list = D('Appoint')->get_appointlist_by_StoreId($store_id);
		$activity_list = M('Wxapp_list')->where(array('mer_id'=>$now_store['mer_id']))->select();
		$reply_list = M('Reply')->field('r.*,u.nickname')->join('AS r left join '.C('DB_PREFIX').'user AS u ON r.uid = u.uid')->where(array('r.store_id'=>$store_id,'r.order_type'=>$from))->order('score DESC')->limit(2)->select();
		foreach ($reply_list as &$v) {
			$v['pic']=M('Reply_pic')->where(array('pigcms_id'=>array('in',$v['pic'])))->select();
		}
		$reply_count = M('Reply')->where(array('store_id'=>$store_id))->count();
		$this->assign('reply_count',$reply_count);
		$this->assign('reply_list',$reply_list);
		$this->assign('activity_list',$activity_list);
		$this->assign('appoint_list',$appoint_list);
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

		$now_store['reply_url']=U('Wap/Group/feedback',array('order_type'=>$from));
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
		$this->display();
	}



	/**
	 * 订单详情
	 */
    public function status()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if($_GET['wxapp_share_page']){
			$result = M('Shop_order')->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->setField('share_status', 1);
			if($this->config['share_coupon']){
				$result = D('System_coupon')->share_coupon(array('order_id'=>$order_id,'uid'=>$this->user_session['uid'],'type'=>'shop'));
			}
		}
        $order = D("Shop_order")->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']));
        if ($order) {
            $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
            $store_image_class = new store_image();
            $images = $store_image_class->get_allImage_by_path($store['pic_info']);
            $store['image'] = isset($images[0]) ? $images[0] : '';

            //京东校验订单是否分单
            if($order['jd_order_id']>0){
                import('ORG.Net.Http');
                $order_url = "https://bizapi.jd.com/api/order/selectJdOrder";
                $order_data['token'] = $this->config['jd_access_token'];
                $order_data['jdOrderId'] = $order['jd_order_id'];

                $order_return = Http::curlPost($order_url,$order_data);
                if($order_return['success']==false){
                    fdump_sql(json_encode($order_return),'jd_oder_info');
                }
                if($order_return['result']['pOrder']==0){
                    foreach($order_return['result']['sku'] as $sku){
                        M('Shop_order_detail')->where(array('order_id'=>$order['order_id'],'jd_sku_id'=>$sku['skuId']))->save(array('jd_order_id'=>$order_return['result']['jdOrderId']));
                    }
                    $jd_express[] = $order_return['result']['jdOrderId'];
                    $this->assign('jd_express',$jd_express);
                }elseif(is_array($order_return['result']['pOrder'])){
                    foreach($order_return['result']['cOrder'] as $corder){
                        foreach($corder['sku'] as $sku){
                            M('Shop_order_detail')->where(array('order_id'=>$order['order_id'],'jd_sku_id'=>$sku['skuId']))->save(array('jd_order_id'=>$corder['jdOrderId']));
                        }
                        $jd_express[] = $corder['jdOrderId'];
                    }
                    $this->assign('jd_express',$jd_express);
                }
            }

			//删除购物车
			$condition_delete['shop_id'] = $order['store_id'];
            if ($this->user_session['uid']) {
                $where['uid'] = $this->user_session['uid'];
                $where['cart_cookie'] = cookie('cart_cookie');
                $where['_logic'] = 'or';
                $condition_delete['_complex'] = $where;
            } else {
                $condition_delete['cart_cookie'] = cookie('cart_cookie');
            }
            M('Shop_cart')->where($condition_delete)->delete();


            if($order['pay_type'] == 'offline' && empty($order['third_id'])){
                $payment = rtrim(rtrim(number_format($order['price']-$order['card_price']-$order['merchant_balance']-$order['card_give_money']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-floatval($order['coupon_price']),2,'.',''),'0'),'.');
            }
            $discount_price = floatval(round($order['discount_price'] + $order['freight_charge'] + $order['packing_charge'] + $order['other_money'], 2));
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
                'order_from' => $order['order_from'],
                'deliver_log_list' => D('Shop_order_log')->where(array('order_id' => $order['order_id']))->order('id DESC')->find(),
                'deliver_info' => unserialize($order['deliver_info']),
                'express_name' => isset($order['express_name']) ? $order['express_name'] : '',
                'express_code' => isset($order['express_code']) ? $order['express_code'] : '',
                'express_number' => isset($order['express_number']) ? $order['express_number'] : '',
                'other_money' => floatval($order['other_money']),
				'share_status' => isset($order['share_status']) ? $order['share_status'] : 0,
				'show_lottery_first' => isset($order['show_lottery_first']) ? $order['show_lottery_first'] : 0,
                'jd_order_id' => $order['jd_order_id'] ? $order['jd_order_id'] : 0,
            );

               $order['status']!=0 && M('Shop_order')->where(array('order_id'=>$order['order_id']))->setInc('show_lottery_first',1);
            $tempList = array();
            foreach($order['info'] as $v) {
                $discount_price = floatval($v['discount_price']) > 0 ? floatval($v['discount_price']) : floatval($v['price']);
                $tGoods = array(
                    'name' => $v['name'],
                    'discount_type' => $v['discount_type'],
                    'price' => strval(floatval($v['price'])),
                    'discount_price' => strval($discount_price),
                    'spec' => empty($v['spec']) ? '' : $v['spec'],
                    'num' => $v['num'],
                    'total' => strval(floatval($v['price'] * $v['num'])),
                    'discount_total' => strval(floatval($discount_price * $v['num'])),
                );

                $index = isset($v['packname']) && $v['packname'] ? $v['packname'] : 0;
                if (isset($tempList[$index])) {
                    $tempList[$index]['list'][] = $tGoods;
                } else {
                    $tempList[$index] = array('name' => $v['packname'], 'list' => array($tGoods));
                }
            }
            if (count($tempList) == 1) {
                $tempList[$index]['name'] = '';
            }
            $arr['info'] = $tempList;
            $arr['discount_detail'] = $order['discount_detail'] ?: '';

            $this->assign($arr);
            $shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
            $this->assign('store', array_merge($store, $shop));
			//定制抽奖
			$lottery  = M('Lottery_shop')->where(array('mer_id'=>$order['mer_id'],'status'=>1))->find();
			$lottery_info  = M('Lottery_shop_list')->where(array('order_id'=>$order['order_id']))->find();
			$share_coupon_info  = M('Share_coupon_list')->where(array('order_id'=>$order['order_id']))->find();

			C('config.share_rand_send_coupon')==1 && $coupon_where['rand_send'] = 1;

			$coupon_where['status']=1;
			$share_coupon = D('System_coupon')->get_coupon_list($coupon_where);

			$this->assign('share_coupon', $share_coupon);
            $refund = D('Shop_order_refund')->field('id')->where(array('order_id' => $order['order_id']))->limit(1)->select();
            $isShowRefund = 0;
            if ($refund) {
                $isShowRefund = 1;
            }
            $this->assign('isShowRefund', $isShowRefund);
			$this->assign('lottery', $lottery);
			$this->assign('lottery_info', $lottery_info);
			$this->assign('share_coupon_info', $share_coupon_info);
			if($this->is_app_browser){

				$this->display('order_detail_app_new');
			}else{

				$this->display('order_detail_new');
			}
		} else {
            $this->error_tips('订单信息错误！');
        }
    }

    /**
     * 拼单页面列表
     */
    public function sync()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $cartid = isset($_GET['cartid']) ? htmlspecialchars(trim($_GET['cartid'])) : '';

        $return = $this->checkStore($store_id);
        if ($return['error']) {
            $this->error_tips($return['msg']);
        }
        $shop = $return['store'];

        if ($orderTemp = D('Shop_order_temp')->field(true)->where(array('cartid' => $cartid, 'store_id' => $store_id))->find()) {
            $this->assign('store', $shop);
            $this->assign('share_url', $this->config['site_url'] . U('Shop/spell', array('cartid' => $cartid, 'store_id' => $store_id)));
            $this->display();
        } else {
            $this->error_tips('拼单信息有误');
        }
    }

    /**
     * 生成拼单数据
     */
    public function addNew()
    {
        $cartid = isset($_POST['cartid']) ? htmlspecialchars(trim($_POST['cartid'])) : '';
        $avatar = isset($_POST['avatar']) ? htmlspecialchars(trim($_POST['avatar'])) : '';
        $name= isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
        $cartData= isset($_POST['cartData']) ? trim($_POST['cartData']) : '';
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $from = isset($_POST['from']) ? intval($_POST['from']) : 0;

        $return = $this->checkStore($store_id);
        if ($return['error']) {
            exit(json_encode($return));
        }
        $shop = $return['store'];

        $index = isset($_POST['index']) ? htmlspecialchars(trim($_POST['index'])) : '';
        $copyindex = isset($_POST['copyindex']) ? htmlspecialchars(trim($_POST['copyindex'])) : '';
        if ($copyindex) {
            $copyindex = is_numeric($copyindex)  ? 'index_' . $copyindex : $copyindex;
        }
        $where = array('cartid' => $cartid, 'store_id' => $store_id);
        $orderTemp = D('Shop_order_temp')->field(true)->where($where)->find();

        if (empty($index) && $orderTemp) {
            $index = $orderTemp['max_index'] + 1;
        } elseif (empty($index)) {
            $index = 1;
        }
        is_numeric($index) && $index = max(1, $index);

        if (empty($cartid)) {
            $cartid = md5($store_id . uniqid() . date('YmdHis'));
        }

        if (!is_array($cartData)) {
            $cartData = json_decode(htmlspecialchars_decode($cartData), true);
        }
        if ($cartData) {
            $resultGoods = D('Shop_order_temp')->getGoods($cartid, $store_id, $index);
            $shopGoodsDB = D('Shop_goods');
            foreach ($cartData as &$row) {
                $goods_id = $row['productId'];
                $num = $row['count'];
                if ($num < 1) continue;
                $spec_ids = array();
                $str_s = array(); $str_p = array();
                foreach ($row['productParam'] as $r) {
                    if ($r['type'] == 'spec') {
                        $spec_ids[] = $r['id'];
                        $str_s[] = $r['name'];
                    } else {
                        foreach ($r['data'] as $d) {
                            $str_p[] = $d['name'];
                        }
                    }
                }
                if (isset($resultGoods[$goods_id])) {
                    $num += $resultGoods[$goods_id]['count'];
                }
                $spec_str = $spec_ids ? implode('_', $spec_ids) : '';
                $t_return = $shopGoodsDB->check_stock($goods_id, $num, $spec_str, $shop['stock_type'], $store_id, false, $this->user_session['uid']);
                if ($t_return['status'] != 1) {
                    exit(json_encode(array('error' => true, 'msg' => $t_return['msg'])));
//                     $this->returnCode(1, null, $t_return['msg']);
                }
                $row['productPrice'] = floatval($t_return['price']);
                $row['productPackCharge'] = floatval($t_return['packing_charge']);
            }
        }
        
        
        $data = array('cartid' => $cartid, 'store_id' => $store_id);

        $info = array();
        $info['index'] = $index;
        $info['from'] = $from;
        $info['data'] = $cartData;

        $info['name'] = $name ?: $index . '号订购人';
        $info['avatar'] = $avatar;

        $arrIndex = is_numeric($index) ? 'index_' . $index : $index;
        if ($orderTemp) {
            $data = array();
            if (is_numeric($index) && intval($orderTemp['max_index']) < $index) {
                $data['max_index'] = $index;
            }
            $orderTemp['info'] = json_decode($orderTemp['info'], true);

            if (isset($orderTemp['info'][$arrIndex])) {
                if (empty($name) && $orderTemp['info'][$arrIndex]['name']) {
                    $info['name'] = $orderTemp['info'][$arrIndex]['name'];
                }
                if (empty($avatar) && $orderTemp['info'][$arrIndex]['avatar']) {
                    $info['avatar'] = $orderTemp['info'][$arrIndex]['avatar'];
                }
            }

            if ($copyindex && isset($orderTemp['info'][$copyindex]['data']) && $orderTemp['info'][$copyindex]['data']) {
                $info['data'] = $orderTemp['info'][$copyindex]['data'];
            }
            if ($info['data']) {
                foreach ($info['data'] as &$nowData) {
                    $nowData['packname'] = $info['name'];
                }
            }
            $orderTemp['info'][$arrIndex] = $info;
            $data['info'] = json_encode($orderTemp['info']);
            D('Shop_order_temp')->where($where)->save($data);
        } else {
            if ($info['data']) {
                foreach ($info['data'] as &$nowData) {
                    $nowData['packname'] = $info['name'];
                }
            }
            $data['info'] = json_encode(array($arrIndex => $info));
            $data['max_index'] = 1;
            D('Shop_order_temp')->add($data);
        }
        exit(json_encode(array('error' => false, 'name' => $info['name'], 'avatar' => $avatar, 'cartid' => $cartid, 'index' => $index, 'url' => U('Shop/index', array('index' => $index, 'cartid' => $cartid)) . "#shop-" .$store_id)));
    }

    /**
     * 锁定拼单去买单
     */
    public function saveCart()
    {
        $cartid = isset($_POST['cartid']) ? htmlspecialchars(trim($_POST['cartid'])) : '';
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
        $return = $this->checkStore($store_id);
        if ($return['error']) {
            exit(json_encode($return));
        }
        $shop = $return['store'];

        $where = array('cartid' => $cartid, 'store_id' => $store_id);
        if ($orderTemp = D('Shop_order_temp')->field(true)->where($where)->find()) {
            $orderTemp['info'] = json_decode($orderTemp['info'], true);
            unset($orderTemp['info'][$index]);
            D('Shop_order_temp')->where($where)->save(array('status' => $status));
            exit(json_encode(array('error' => false, 'msg' => 'ok')));
        }
        exit(json_encode(array('error' => true, 'msg' => '拼单信息有误')));
    }

    /**
     * 删除一个拼单人
     */
    public function delCart()
    {
        $cartid = isset($_POST['cartid']) ? htmlspecialchars(trim($_POST['cartid'])) : '';
        $index = isset($_POST['index']) ? trim($_POST['index']) : '';
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;

        $return = $this->checkStore($store_id);
        if ($return['error']) {
            exit(json_encode($return));
        }
        $shop = $return['store'];

        $arrIndex = is_numeric($index) ? 'index_' . $index : $index;
        $where = array('cartid' => $cartid, 'store_id' => $store_id);
        if ($orderTemp = D('Shop_order_temp')->field(true)->where($where)->find()) {
            $orderTemp['info'] = json_decode($orderTemp['info'], true);
            unset($orderTemp['info'][$arrIndex]);
            D('Shop_order_temp')->where($where)->save(array('info' => json_encode($orderTemp['info'])));
            exit(json_encode(array('error' => false, 'msg' => 'ok')));
        }
        exit(json_encode(array('error' => true, 'msg' => '拼单信息有误')));
    }

    public function spell()
    {
        if (isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == $_SESSION['weixin']['spell'])){
            unset($_SESSION['weixin']['spell']);
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
            $_SESSION['spell_user'] = array('openid' => $jsonrt['openid'], 'nickname' => $jsonrt['nickname'], 'headimgurl' => $jsonrt['headimgurl']);
        }
        $user = session('user');
        if (empty($user) && empty($_SESSION['spell_user']) && !empty($_SESSION['openid'])) {
            $_SESSION['weixin']['spell'] = md5(uniqid());
            $customeUrl = preg_replace('#&code=(\w+)#','',$this->config['site_url'].$_SERVER['REQUEST_URI']);
            $oauthUrl='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->config['wechat_appid'].'&redirect_uri='.urlencode($customeUrl).'&response_type=code&scope=snsapi_userinfo&state='.$_SESSION['weixin']['spell'].'#wechat_redirect';
            redirect($oauthUrl);
            exit;
        }
        $myInfo = array();
        if ($user) {
            $myInfo['nickname'] = $user['nickname'];
            $myInfo['openid'] = $user['openid'];
            $myInfo['avatar'] = $user['avatar'];
        } elseif ($_SESSION['spell_user']) {
            $myInfo['nickname'] = $_SESSION['spell_user']['nickname'];
            $myInfo['openid'] = $_SESSION['spell_user']['openid'];
            $myInfo['avatar'] = $_SESSION['spell_user']['headimgurl'];
        }

        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $cartid = isset($_GET['cartid']) ? htmlspecialchars(trim($_GET['cartid'])) : '';

        $shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find();
        if (empty($shop)) {
            $this->error('店铺信息不存在');
        }
        $where = array('cartid' => $cartid, 'store_id' => $store_id);

        if ($orderTemp = D('Shop_order_temp')->field(true)->where($where)->find()) {
            $this->assign('store', $shop);
            $this->assign($where);
            $this->assign($myInfo);
            $this->display();
        } else {
            $this->error('拼单信息有误');
        }
    }

    public function ajaxAll()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $cartid = isset($_GET['cartid']) ? htmlspecialchars(trim($_GET['cartid'])) : '';
        $myIndex = isset($_GET['myindex']) ? htmlspecialchars(trim($_GET['myindex'])) : '';
        $from = isset($_GET['from']) ? intval($_GET['from']) : 0;

        $return = $this->checkStore($store_id);
        if ($return['error']) {
            exit(json_encode($return));
        }
        $store = $return['store'];
        $where = array('cartid' => $cartid, 'store_id' => $store_id);
        $arrIndex = is_numeric($myIndex) ? 'index_' . $myIndex : $myIndex;
        if ($orderTemp = D('Shop_order_temp')->field(true)->where($where)->find()) {
            $list = json_decode($orderTemp['info'], true);
            $data = array();
            $myData = '';
            $myTotalPrice = 0;
            $myTotalPack = 0;
            $totalPrice = 0;
            $totalPack = 0;
            $myDiscountPrice = 0;
            $useGoods = array();
            foreach ($list as $this_index => &$rowset) {
                $frm = $rowset['from'] ? 'spell' : 'sync';
                if ($store['is_mult_class'] == 0) {
                    $rowset['add_cart_url'] = $this->config['site_url'] . U('Shop/index', array('index' => $rowset['index'], 'frm' => $frm, 'cartid' => $cartid)) . '#shop-' . $store_id;
                } else {
                    $rowset['add_cart_url'] = $this->config['site_url'] . U('Shop/classic_shop', array('index' => $rowset['index'], 'frm' => $frm, 'cartid' => $cartid, 'shop_id' => $store_id));
                }

                $rowset['status'] = $orderTemp['status'];
                $tempList = array();
                foreach ($rowset['data'] as $row) {
                    if ($row['limit_type'] == 0 && $row['maxNum'] > 0) {
                        if (isset($useGoods[$row['productId']])) {
                            $useNum = $useGoods[$row['productId']];
                            $discountNum = max(0, $row['maxNum'] - $useNum);
                            $useGoods[$row['productId']] += $discountNum;
                        } else {
                            $discountNum = $row['maxNum'];
                            $useGoods[$row['productId']] = $row['maxNum'];
                        }
                        $oldNum = 0;
                        if ($row['count'] > $discountNum) {
                            $oldNum = $row['count'] - $discountNum;
                        }
                        
                        $oldPrice = $row['oldPrice'];
                        $discountPrice = $row['productPrice'];

                        if ($row['productParam']) {
                            $row['productName'] .= '(';
                            $names = array();
                            foreach ($row['productParam'] as $val) {
                                if ($val['type'] == 'spec') {
                                    $names[] = $val['name'];
                                } elseif ($val['type'] == 'properties') {
                                    foreach ($val['data'] as $vo) {
                                        $names[] = $vo['name'];
                                    }
                                }
                            }
                            $row['productName'] .= implode(',', $names) . ')';
                        }
                        if ($oldNum > 0) {
                            $totalPrice += floatval($row['oldPrice'] * $oldNum);
                            $totalPack += floatval($row['productPackCharge'] * $oldNum);
                            
                            $row['productPrice'] = $row['oldPrice'];
                            $row['count'] = $oldNum;
                            $tempList[] = $row;
                        }
                        if ($discountNum > 0) {
                            $row['productPrice'] = $discountPrice;
                            $row['count'] = $discountNum;
                            
                            $totalPrice += floatval($row['productPrice'] * $row['count']);
                            $totalPack += floatval($row['productPackCharge'] * $row['count']);
                            $tempList[] = $row;
                        }
                    } else {
                        $totalPrice += floatval($row['productPrice'] * $row['count']);
                        $totalPack += floatval($row['productPackCharge'] * $row['count']);
                        if ($row['productParam']) {
                            $row['productName'] .= '(';
                            $names = array();
                            foreach ($row['productParam'] as $val) {
                                if ($val['type'] == 'spec') {
                                    $names[] = $val['name'];
                                } elseif ($val['type'] == 'properties') {
                                    foreach ($val['data'] as $vo) {
                                        $names[] = $vo['name'];
                                    }
                                }
                            }
                            $row['productName'] .= implode(',', $names) . ')';
                        }
                        $tempList[] = $row;
                    }
                }
                $rowset['data'] = $tempList;
                if ($from && $arrIndex == $this_index) {
                    $myData = $rowset;
                    foreach ($myData['data'] as $my) {
                        $myTotalPrice += floatval($my['productPrice'] * $my['count']);
                        $myTotalPack += floatval($my['productPackCharge'] * $my['count']);
                    }
                    unset($list[$this_index]);
                }

                if (isset($rowset['data']) && $rowset['data']) {
                    $data = array_merge($data, $rowset['data']);
                } elseif ($from) {
                    unset($list[$this_index]);
                } elseif ($from == 0 && $rowset['from'] == 1) {
                    unset($list[$this_index]);
                }
            }
//             $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $data);

//             $return['share_url'] = $this->config['site_url'] . U('Shop/spell', array('cartid' => $cartid, 'store_id' => $store_id));
            if ($orderTemp['status'] > 1) {
                $myDiscountPrice = floatval(round(floatval(($myTotalPack + $myTotalPrice) * $orderTemp['price']) / ($totalPack + $totalPrice), 2));
            }
            $isGo = true;
            $addPrice = 0;
            if ($totalPrice < $store['basic_price']) {
                $addPrice = floatval($store['basic_price'] - $totalPrice);
                $isGo = false;
            }
            exit(json_encode(array('error' => false, 'data' => array_reverse($list), 'isGo' => $isGo, 'addPrice' => $addPrice, 'totalPack' => $totalPack, 'totalPrice' => $totalPrice, 'myData' => $myData, 'myDiscountPrice' => $myDiscountPrice, 'myTotalPack' => $myTotalPack, 'myTotalPrice' => $myTotalPrice, 'store' => $store, 'status' => $orderTemp['status'], 'order' => $orderTemp)));
        } else {
            exit(json_encode(array('error' => true, 'msg' => '拼单信息有误')));
        }
    }

    private function checkStore($store_id)
    {
        $store = D("Merchant_store")->field(true)->where(array('store_id' => $store_id))->find();
        if ($store['have_shop'] == 0 || $store['status'] != 1) {
            return array('error' => true, 'msg' => '商家已经关闭了该业务,不能下单了!');
        }
        if (C('config.store_shop_auth') == 1 && $store['auth'] < 3) {
            return array('error' => true, 'msg' => '您查看的' . C('config.shop_alias_name') . '没有通过资质审核！');
        }

        $store_image_class = new store_image();
        $images = $store_image_class->get_allImage_by_path($store['pic_info']);
        $store['images'] = $images ? array_shift($images) : '';

        $now_time = date('H:i:s');
        $is_open = 0;
        if ($store['open_1'] == '00:00:00' && $store['close_1'] == '00:00:00') {
            $is_open = 1;
        } else {
            if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
                $is_open = 1;
            }
            if ($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00') {
                if ($store['open_2'] < $now_time && $now_time < $store['close_2']) {
                    $is_open = 1;
                }
            }
            if ($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00') {
                if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
                    $is_open = 1;
                }
            }
        }
        if ($is_open == 0) {
            return array('error' => true, 'msg' => '店铺休息中');
        }

        $store_shop = D("Merchant_store_shop")->field(true)->where(array('store_id' => $store_id))->find();
        if (empty($store) || empty($store_shop)) return array('error' => true, 'msg' => '店铺信息错误');
        $store = array_merge($store, $store_shop);

        return array('error' => false, 'store' => $store);
    }

	//商品详情
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
		$store['is_new'] = ($row['create_time'] + 864000) < time() ? 0 : 1;
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
		$store['mer_id'] = $now_store['mer_id'];
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

	public function scan_shop_cart(){
		if(!$this->is_wexin_browser){
			$this->error_tips('请在微信中打开');
		}
		$openid =$_SESSION['openid'];
		$now_user = M('User')->where(array('openid'=>$openid))->find();
		if(empty($now_user)){
			$_SESSION['weixin']['user']['source'] = 'scan_shop_buy';
			$now_user = D('User')->autoreg($_SESSION['weixin']['user']);
			$login_result = D('User')->autologin('openid',$_SESSION['weixin']['user']['openid']);
		}
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : $_SESSION['session_store_id'];
		$now_store = M('Merchant_store')->where(array('store_id'=>$store_id))->find();
		$card_info = D('Card_new')->get_card_by_uid_and_mer_id($now_user['uid'],$now_store['mer_id']);

		$this->assign('card_info',$card_info);
		$this->assign('now_store',$now_store);
		$this->assign('now_user',$now_user);
		$carts = array();
		for ($i = 0; $i < 40; $i++) {
			$tmpCookie = cookie('shop_cart_'.$store_id.'_' . $i);
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
			$g_list = D('Shop_goods')->field(true)->where(array('goods_id' => array('in', $goods_ids)))->select();
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
				$store_list[$vo['store_id']]['total_num'] += $vo['count'];
				$store_list[$vo['store_id']]['goods_list'][] = $goods_list[$vo['productId']];
			} else {
				$store_list[$vo['store_id']]['total_num'] += $vo['count'];
				$store_list[$vo['store_id']]['total_price'] = $total_price;
				$store_list[$vo['store_id']]['goods_list'] = array($goods_list[$vo['productId']]);
			}
		}

		$this->assign('product_list', $store_list[$store_id]);
		$this->assign('store_id', $store_id);
		$this->display();
	}

	public function scan_confirm_order(){
		$this->isLogin();
		//delivery_type 0:平台配送，1：商家配送，2：自提，3:平台配送或自提，4：商家配送或自提
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$cartid = isset($_GET['cartid']) ? htmlspecialchars(trim($_GET['cartid'])) : '';
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$cookieData = $this->getCookieOldData($store_id,'shop_cart_');
		if(empty($cookieData)) {
			redirect(U('Shop/index') . '#shop-' . $store_id);
			exit;
		}
		$return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);


		if ($return['error_code']) $this->error_tips($return['msg']);


		$is_own = 0;
		$merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $return['mer_id']))->find();
		foreach ($merchant_ownpay as $ownKey => $ownValue) {
			$ownValueArr = unserialize($ownValue);
			if ($ownValueArr['open']) {
				//$is_own = 1;
			}
		}
		if ($is_own) {
			if ($return['delivery_type'] == 0) {
				$this->error_tips('商家配置的配送信息不正确');
			} elseif ($return['delivery_type'] == 3) {
				$return['delivery_type'] = 2;
			}
		}

		$return['this_discount_price'] = round($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce'], 2) + ($return['price'] - $return['vip_discount_money']);
		$basic_price = $return['price'];//商品实际总价

		$return['price'] = round($return['vip_discount_money'] - round($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce'], 2), 2);//实际要支付商品的总价格

		$return['price'] += $return['store']['pack_alias'];//商品的实际支付总加打包费

		$goodsList = $return['goods'];
		$tempList = array();
		foreach ($goodsList as $goods) {
			$index = isset($goods['packname']) && $goods['packname'] ? $goods['packname'] : 0;
			if (isset($tempList[$index])) {
				$tempList[$index]['list'][] = $goods;
			} else {
				$tempList[$index] = array(
						'name' => $goods['packname'],
						'list' => array($goods)
				);
			}
		}
		if (count($tempList) == 1) {
			$tempList[$index]['name'] = '';
		}
		$return['goods'] = $tempList;
		$this->assign($return);

		$now_store_category_relation = M('Shop_category_relation')->where(array('store_id' => $return['store_id']))->find();
		$now_store_category = M('Shop_category')->where(array('cat_id' => $now_store_category_relation['cat_id']))->find();
		if ($now_store_category['cue_field']) {
			$this->assign('cue_field', unserialize($now_store_category['cue_field']));
		}

		$this->display();
	}

    public function refund()
    {
        if (empty($this->config['is_open_shop_refund'])) {
            $this->error_tips('平台未开启申请售后的权限！');
        }
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $order = D('Shop_order')->field(true)->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find();
        if (empty($order)) {
            $this->error_tips('订单信息有误！');
        }

        if ($order['status'] != 2 && $order['status'] != 3 ) {
            if ($refund = D('Shop_order_refund')->field('id')->where(array('order_id' => $order['order_id']))->limit(1)->select()) {
                redirect(U('Shop/refundStatus', array('order_id' => $order['order_id'])));
                exit;
            } else {
                $this->error_tips('当前状态不能申请售后');
            }
        }
        if ($order['use_time'] + 86400 * 3 > time()) {
            $sql = "SELECT g.image, d.goods_id, d.name, d.pay_price, d.discount_price, d.num, d.refundNum, d.unit, d.packing_charge, d.id ,d.jd_sku_id FROM " . C('DB_PREFIX') . "shop_goods AS g INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON g.goods_id=d.goods_id WHERE d.order_id={$order_id}";
            $list = D()->query($sql);

            $goods_image_class = new goods_image();
            $goodsList = array();

            foreach ($list as $row) {
                $row['pay_price'] = $row['pay_price'] ? $row['pay_price'] : $row['discount_price'];
                if ($row['refundNum'] >= $row['num']) {
                    unset($row);
                    continue;
                }
                $image = '';
                if(!empty($row['image'])){
                    $tmp_pic_arr = explode(';', $row['image']);
                    foreach ($tmp_pic_arr as $key => $value) {
                        if(false===strpos($value,',')){
                            $image = 'http://img13.360buyimg.com/n2/'.$value;
                        }else{
                            if (empty($image)) $image = $goods_image_class->get_image_by_path($value, 's');
                        }
                      break;
                    }
                }
                $row['image'] = $image;
                $goodsList[] = $row;
            }

            $this->assign('goodsList', $goodsList);
            $this->assign('expire_tips', '商品已经全部申请退货了');
            $this->assign('expire', empty($goodsList) ? 1 : 0);
        } else {
            $this->assign('expire_tips', '退款期限已超过3天，您只可查看退款进度');
            $this->assign('expire', 1);
        }
        $this->assign('order', $order);
        $this->display();
    }

    //请求京东退款参数
    public function jdRefund()
    {
        import('ORG.Net.Http');
        $order_id = $_POST['order_id'];
        $param['skuId'] = $_POST['skuId'];
//        $param['jdOrderId'] = M('Shop_order')->where(array('order_id'=>$order_id))->getField('jd_order_id');
        $param['jdOrderId'] = M('Shop_order_detail')->where(array('order_id'=>$order_id,'jd_sku_id'=>$_POST['skuId']))->getField('jd_order_id');
        $url_available = "https://bizapi.jd.com/api/afterSale/getAvailableNumberComp";
        $available_data['token'] = $this->config['jd_access_token'];
        $available_data['param'] = json_encode($param);
        $a_return = Http::curlPost($url_available,$available_data);
        if($a_return['success']==false){
            exit(json_encode(array('error' => 1, 'err_msg' => $a_return['resultMessage'])));
        }
        $url_expect = "https://bizapi.jd.com/api/afterSale/getCustomerExpectComp";
        $expect_return =  $e_return = Http::curlPost($url_expect,$available_data);
        $url_return = "https://bizapi.jd.com/api/afterSale/getWareReturnJdComp";
        $return_return =  $e_return = Http::curlPost($url_return,$available_data);
        $data['error'] = 0;
        $data['expect'] = $expect_return['result'];
        foreach($data['expect'] as $kk => $dd){
            if($dd['code']==20 ||$dd['code']==30){
                unset($data['expect'][$kk]);
            }
        }
        $data['return'] = $return_return['result'];
        echo json_encode($data);
    }

    public function saveRefund()
    {
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $order = D('Shop_order')->field(true)->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find();
        if (empty($order)) {
            exit(json_encode(array('errcode' => 1, 'msg' => '订单信息有误！')));
        }
        $goods = isset($_POST['goods']) ? $_POST['goods'] : '';
        $reason = isset($_POST['reason']) ? htmlspecialchars(trim($_POST['reason'])) : '';
        $images = isset($_POST['images']) ? htmlspecialchars(trim($_POST['images'])) : '';

        if (empty($goods)) {
            exit(json_encode(array('errcode' => 1, 'msg' => '请选择要退货的商品')));
        }
        if(isset($_POST['expect'])&&isset($_POST['back'])&&isset($_POST['isHasPackage'])&&isset($_POST['packageDesc'])){
            //京东保存服务单
            import('ORG.Net.Http');
            $url_afs = "https://bizapi.jd.com/api/afterSale/createAfsApply";
            $afs_datas['token'] = $this->config['jd_access_token'];
            $jd_order = M('Shop_order_detail')->where(array('order_id'=>$order_id,'jd_sku_id'=>$goods[0]['jd_sku_id']))->getField('jd_order_id');
            $afs_data['jdOrderId'] = $jd_order;
            $afs_data['customerExpect'] = $_POST['expect'];
            $afs_data['questionDesc'] = $reason;
            $afs_data['isNeedDetectionReport'] = false;
            if($images!=""){
                $imgs = explode(',',$images);
                foreach($imgs as $img){
                    $image_jd .= C('config.site_url') .$img.',';
                }
                $image_jd = substr($image_jd,0,-1);
            }else{
                $image_jd = "";
            }
            $afs_data['questionPic'] = $image_jd;
            if($_POST['isHasPackage']==1){
                $isHasPackage = true;
            }else{
                $isHasPackage = false;
            }
            $afs_data['isHasPackage'] = $isHasPackage;
            $afs_data['packageDesc'] = $_POST['packageDesc'];
            //客户信息实体
            $asCustomerDto['customerContactName'] = $order['username'];
            $asCustomerDto['customerTel'] = "11111111111";
            $asCustomerDto['customerMobilePhone'] = $order['userphone'];
            $asCustomerDto['customerEmail'] = $order['jd_email'];
            $asCustomerDto['customerPostcode'] = "000000";
            $afs_data['asCustomerDto'] = $asCustomerDto;
            //取件信息实体
            $AfterSalePickwareDto['pickwareType'] = $_POST['back'];
            $area_code = explode('_',$order['jd_area']);
            $AfterSalePickwareDto['pickwareProvince'] = $area_code[0];
            $AfterSalePickwareDto['pickwareCity'] = $area_code[1];
            $AfterSalePickwareDto['pickwareCounty'] = $area_code[2];
            $AfterSalePickwareDto['pickwareVillage'] = $area_code[3];
            $addressinfo = M('User_adress')->where(array('adress_id'=>$order['address_id']))->find();
            $AfterSalePickwareDto['pickwareAddress'] = $addressinfo['adress'].$addressinfo['detail'];
            $afs_data['asPickwareDto'] = $AfterSalePickwareDto;
            //返件信息实体
            $AfterSaleReturnwareDto['returnwareType'] = 10;
            $AfterSaleReturnwareDto['returnwareProvince'] = $area_code[0];
            $AfterSaleReturnwareDto['returnwareCity'] = $area_code[1];
            $AfterSaleReturnwareDto['returnwareCounty'] = $area_code[2];
            $AfterSaleReturnwareDto['returnwareVillage'] = $area_code[3];
            $AfterSaleReturnwareDto['returnwareAddress'] = $addressinfo['adress'].$addressinfo['detail'];
            $afs_data['asReturnwareDto'] = $AfterSaleReturnwareDto;
            //申请单明细
            if($goods[0]['jd_sku_id']==0){
                exit(json_encode(array('errcode' => 1, 'msg' => '商品异常！')));
            }
            $AfterSaleDetailDto['skuId'] = $goods[0]['jd_sku_id'];
            $AfterSaleDetailDto['skuNum'] = $goods[0]['num'];
            $afs_data['asDetailDto'] = $AfterSaleDetailDto;
            $afs_datas['param'] = json_encode($afs_data);
            $afs_return = Http::curlPost($url_afs,$afs_datas);
            if($afs_return['success']==false){
                fdump_sql(json_encode($afs_return),'jd_afs_apply');
                exit(json_encode(array('errcode' => 1, 'msg' => '京东服务单申请失败！')));
            }
        }
        $detailIds = array();
        $keyNums = array();
        foreach ($goods as $row) {
            $detailIds[] = $row['detail_id'];
            $keyNums[$row['detail_id']] = $row['num'];
        }

        $orderDetailDB = D('Shop_order_detail');
        $list = $orderDetailDB->field(true)->where(array('order_id' => $order_id, 'id' => array('in', $detailIds)))->select();

        $totalMoney = 0;

        $newData = array();
        $nowTime = time();
        foreach ($list as $val) {
            $totalMoney += $val['pay_price'] * $keyNums[$val['id']];
            $newData[] = array(
                'detail_id' => $val['id'],
                'order_id' => $order_id,
                'price' => $val['pay_price'],
                'name' => $val['name'],
                'goods_id' => $val['goods_id'],
                'unit' => $val['unit'],
                'spec' => $val['spec'],
                'spec_id' => $val['spec_id'],
                'num' => $keyNums[$val['id']],
                'number' => $val['number'],
                'packing_charge' => $val['packing_charge'],
                'create_time' => $nowTime,
                'jd_sku_id'=> $val['jd_sku_id']
            );
            $orderDetailDB->where(array('id' => $val['id']))->save(array('refundNum' => $keyNums[$val['id']]));
        }

        $refundData = array('order_id' => $order_id);
        $refundData['uid'] = $this->user_session['uid'];
        $refundData['price'] = $totalMoney;
        $refundData['image'] = $images;
        $refundData['reason'] = $reason;
        $refundData['applytime'] = $nowTime;
        $refundData['status'] = 0;
        $refundData['balance_pay'] = $order['balance_pay'];
        $refundData['payment_money'] = $order['payment_money'];
        $refundData['merchant_balance'] = $order['merchant_balance'];
        $refundData['score_deducte'] = $order['score_deducte'];
        $refundData['card_give_money'] = $order['card_give_money'];
        $refundData['score_used_count'] = $order['merchant_balance'];


        if ($refund_id = D('Shop_order_refund')->add($refundData)) {
            $shopRefundDetailDB = D('Shop_refund_detail');
            foreach ($newData as $tdata) {
                $tdata['refund_id'] = $refund_id;
                $shopRefundDetailDB->add($tdata);
            }
            D('Shop_order')->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->save(array('is_apply_refund' => 1));
            D('Shop_refund_log')->add(array('refund_id' => $refund_id, 'status' => 0, 'dateline' => $nowTime, 'note' => $reason));
            //微信通知店员
            $staffs = D('Merchant_store_staff')->field(true)->where(array('store_id' => $order['store_id'], 'is_notice' => 0, 'last_time'=>array('neq',0)))->select();
            $href = C('config.site_url') . '/packapp/storestaff/shop_detail.html?order_id='.$order_id;
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            foreach ($staffs as $staff) {
                if ($staff['client'] == 0 && $staff['openid']) {
                    $model->sendTempMsg('OPENTM405627933', array('href' => $href, 'wecha_id' => $staff['openid'], 'first' => $staff['name'] . '您好！您有新的退款订单待处理。', 'keyword1' => $order_id, 'keyword2' => $totalMoney.'元','keyword3'=>date('Y年m月d日 H:i'), 'remark' => '点击查看详情！'));
                }
            }
            exit(json_encode(array('errcode' => 0, 'msg' => '申请成功')));
        }
        exit(json_encode(array('errcode' => 1, 'msg' => '申请失败，稍后重试')));
    }
    public function ajax_upload_file()
    {
        if ($_FILES['imgFile']['error'] == 4) {
            exit(json_encode(array(
                'error' => 1,
                'msg' => '没有选择图片'
            )));
        }
        $upload_file = D('Image')->handle($this->user_session['uid'], 'shoporderrefund', 0, array('size' => 4), false);
        if ($upload_file['error']) {
            exit(json_encode(array(
                'error' => 1,
                'msg' => '上传失败，请重试！'
            )));
        } else {
            exit(json_encode(array(
                'error' => 2,
                'msg' => '上传成功',
                'url' => $upload_file['url']['imgFile']
            )));
        }
    }

    public function refundStatus()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 43;
        $refundList = D('Shop_order_refund')->field(true)->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->order('id DESC')->select();

        $sql = "SELECT g.image, d.goods_id, d.name, d.price, d.num, d.unit, d.spec, d.id, d.refund_id,d.jd_sku_id,d.jd_afs_id FROM " . C('DB_PREFIX') . "shop_goods AS g INNER JOIN " . C('DB_PREFIX') . "shop_refund_detail AS d ON g.goods_id=d.goods_id WHERE d.order_id={$order_id}";
        $list = D()->query($sql);
        $data = array();
        $goods_image_class = new goods_image();
       //获取京东服务单
        import('ORG.Net.Http');
        $service_url = "https://bizapi.jd.com/api/afterSale/getServiceListPage";
        $s_datas['token'] = $this->config['jd_access_token'];
        $s_data['pageSize'] = 10;
        $s_data['pageIndex'] = 1;
        foreach ($list as $row) {
            $image = '';
            if(!empty($row['image'])){
                $tmp_pic_arr = explode(';', $row['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    if(false===strpos($value,',')){
                        $image = 'http://img13.360buyimg.com/n2/'.$value;
                    }else{
                        if (empty($image)) $image = $goods_image_class->get_image_by_path($value, 's');
                    }
                    break;
                }
            }
            $jdOrder = M('Shop_order_detail')->where(array('order_id'=>$order_id,'goods_id'=>$row['goods_id']))->find();
            if($jdOrder['jd_sku_id']>0 && $jdOrder['jd_order_id']>0){
                $s_data['jdOrderId'] = $jdOrder['jd_order_id'];
                $s_datas['param'] = json_encode($s_data);
                $s_return = Http::curlPost($service_url,$s_datas);
                if($s_return['success']==false){
                    fdump_sql(json_encode($s_return),'jdServiceList');
                }

                $sd_url = "https://bizapi.jd.com/api/afterSale/getServiceDetailInfo";
                $serviceInfoList = $s_return['result']['serviceInfoList'];
                $sd_datas['token'] = $this->config['jd_access_token'];
                foreach($serviceInfoList as $serv){
                    //保存服务单号
                    if($serv['afsServiceStep']!=60){
                        M('Shop_order_detail')->where(array('order_id'=>$order_id,'jd_sku_id'=>$serv['wareId']))->save(array('jd_afs_id'=>$serv['afsServiceId']));
                    }
                    M('Shop_refund_detail')->where(array('order_id'=>$order_id,'jd_sku_id'=>$serv['wareId'],'jd_afs_id'=>0))->save(array('jd_afs_id'=>$serv['afsServiceId']));
                    $sd_data['afsServiceId'] = $serv['afsServiceId'];
                    $sd_data['appendInfoSteps'] = array(1,2,3,4,5);
                    $sd_datas['param'] = json_encode($sd_data);
                    $sd_return = Http::curlPost($sd_url,$sd_datas);
                    if($sd_return['success']==false){
                        fdump_sql(json_encode($sd_return),'jdServiceInfo');
                    }
                    if($row['jd_sku_id']==$serv['wareId'] && $row['jd_afs_id']==$sd_return['result']['afsServiceId']){
                        $row['approvedResultName'] = $sd_return['result']['afsServiceStepName'].' · '.$sd_return['result']['approvedResultName'];
                        $row['cancel'] = $serv['cancel'] ? '可以取消' : '不可取消';
                        if(in_array(2,$row['allowOperations'])){
                            $row['canExpress'] = 1;
                        }else{
                            $row['canExpress'] = 0;
                        }

                    }
                }
            }
            $row['image'] = $image;

            $data[$row['refund_id']][] = $row;
        }
        //状态(0：用户申请，1：商家同意退货，2：商家拒绝退货，3：用户重新申请，4：取消退货申请)
        $status = array('商家审核中', '已完成退货', '商家拒绝退货', '商家审核中', '取消退货申请');
        foreach ($refundList as &$refund) {
            $refund['image'] = $refund['image'] ? explode(',', $refund['image']) : '';
            $refund['goodsList'] = isset($data[$refund['id']]) ? $data[$refund['id']] : array();
            $refund['showStatus'] = $status[$refund['status']];
        }
        $this->assign('refund_list', $refundList);
        $this->display();
    }
    //京东填写发运信息
    public function refundExpress()
    {
       if(IS_POST){
           $afs_id = $_POST['afs_id'];
           $express_name = $_POST['express_name'];
           $express_num = $_POST['express_num'];
           $freightMoney = $_POST['freightMoney'];
           import('ORG.Net.Http');
           $express_url = "https://bizapi.jd.com/api/afterSale/updateSendSku";
           $express_datas['token'] = $this->config['jd_access_token'];
           $express_data['afsServiceId'] = $afs_id;
           $express_data['freightMoney'] = $freightMoney;
           $express_data['expressCompany'] = $express_name;
           $express_data['deliverDate'] = date('Y-m-d H:i:s',time());
           $express_data['expressCode'] = $express_num;
           $express_datas['param'] = json_encode($express_data);
           $express_return = Http::curlPost($express_url,$express_datas);
           if($express_return['success']==false){
               fdump_sql(json_encode($express_return),'jd_refund_express');
               exit(json_encode(array('errcode' => 1, 'msg' =>$express_return['resultMessage'])));
           }
           M('Shop_refund_detail')->where(array('jd_afs_id'=>$afs_id))->save(array('jd_express'=>$express_name,'jd_expNum'=>$express_num,'jd_freightMoney'=>$freightMoney));
           exit(json_encode(array('errcode' => 0, 'msg' => '提交成功')));
       }
       $afs = $_GET['afs_id'];
       $info = M('Shop_refund_detail')->where(array('jd_afs_id'=>$afs))->find();
       exit(json_encode(array('express_name'=>$info['jd_express'],'express_num'=>$info['jd_expNum'],'freightMoney'=>$info['jd_freightMoney'])));
    }
    public function refundLog()
    {
        $refund_id = isset($_GET['refund_id']) ? intval($_GET['refund_id']) : 0;
        $refund = D('Shop_order_refund')->field(true)->where(array('id' => $refund_id, 'uid' => $this->user_session['uid']))->find();
        if (empty($refund)) {
            $this->error_tips('退货信息不正确');
        }
        $logs = D('Shop_refund_log')->field(true)->where(array('refund_id' => $refund_id))->order('id DESC')->select();
        $this->assign('status', $logs);
        $this->display();
    }

    //取消退货申请
    public function cancelRefund()
    {
        $refund_id = isset($_POST['refund_id']) ? intval($_POST['refund_id']) : 0;
        $refund = D('Shop_order_refund')->field(true)->where(array('id' => $refund_id, 'uid' => $this->user_session['uid']))->find();
        if (empty($refund)) {
            exit(json_encode(array('errcode' => 1, 'msg' => '退货信息不正确')));
        }
        if ($refund['status'] > 0) {
            exit(json_encode(array('errcode' => 1, 'msg' => '当前状态不可取消退货申请了')));
        }
        //京东取消服务单
        import('ORG.Net.Http');
        $refundList = D('Shop_refund_detail')->field(true)->where(array('refund_id' => $refund_id))->select();
        foreach($refundList as $ref) {
            $jdOrder = M('Shop_order_detail')->where(array('order_id' => $ref['order_id'], 'goods_id' => $ref['goods_id']))->find();
        }
        if($jdOrder['jd_afs_id']>0){
            $cancel_url = "https://bizapi.jd.com/api/afterSale/auditCancel";
            $cancel_datas['token'] = $this->config['jd_access_token'];
            $cancel_data['serviceIdList'][] = $jdOrder['jd_afs_id'];
            $cancel_data['approveNotes'] = '取消服务单';
            $cancel_datas['param'] = json_encode($cancel_data);
            $c_return = Http::curlPost($cancel_url,$cancel_datas);
            if($c_return['success']==false){
                fdump_sql(json_encode($c_return),'jdCancelService');
                exit(json_encode(array('errcode' => 1, 'msg' => $c_return['resultMessage'])));
            }
        }

        if (D('Shop_order_refund')->where(array('id' => $refund_id, 'uid' => $this->user_session['uid']))->save(array('status' => 4))) {
            D('Shop_refund_log')->add(array('refund_id' => $refund_id, 'status' => 4, 'dateline' => time(), 'note' => ''));
            $refundDetailList = D('Shop_refund_detail')->field(true)->where(array('refund_id' => $refund_id))->select();
            $orderDetailDB = D('Shop_order_detail');
            foreach ($refundDetailList as $row) {
                $orderDetailDB->where(array('id' => $row['detail_id']))->save(array('refundNum' => 0));
            }
            D('Shop_order')->where(array('order_id' => $refund['order_id'], 'uid' => $this->user_session['uid']))->save(array('is_apply_refund' => 0));
            exit(json_encode(array('errcode' => 0, 'msg' => 'ok')));
        } else {
            exit(json_encode(array('errcode' => 1, 'msg' => '取消审核失败，稍后重试')));
        }
    }

    public function finishOrder()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $where = array('order_id' => $order_id, 'uid' => $this->user_session['uid']);
        $order = D('Shop_order')->field(true)->where($where)->find();
        if (empty($order)) {
            exit(json_encode(array('errcode' => 1, 'msg' => '订单信息不正确')));
        }
        if ($order['status'] == 2 || $order['status'] == 3) {
            exit(json_encode(array('errcode' => 1, 'msg' => '此单已确认，请不要重复确认')));
        }
        $data = array('status' => 2);
        $data['use_time'] = $_SERVER['REQUEST_TIME'];
        $data['last_time'] = $_SERVER['REQUEST_TIME'];
        if (D('Shop_order')->where($where)->save($data)) {
            D('Shop_order')->shop_notice($order);
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 6, 'name' => '', 'phone' => ''));
            exit(json_encode(array('errcode' => 0, 'msg' => 'ok', 'url' => U('Shop/status', array('order_id' => $order_id)))));
        } else {
            exit(json_encode(array('errcode' => 1, 'msg' => '订单状态修改失败，稍后重试')));
        }
    }
	public function ajax_cart_save(){
		if(!$_POST['cart_cookie']){
			$this->error('error');
		}
		$condition_delete['shop_id'] = $_POST['shop_id'];

		if($this->user_session['uid']){
			$where['uid'] = $this->user_session['uid'];
			$where['cart_cookie'] = $_POST['cart_cookie'];
			$where['_logic'] = 'or';
			$condition_delete['_complex'] = $where;
		}else{
			$condition_delete['cart_cookie'] = $_POST['cart_cookie'];
		}
		M('Shop_cart')->where($condition_delete)->delete();
		if($_POST['cookieProductCart']){
			foreach($_POST['cookieProductCart'] as $value){
				$data = $value;
				$data['productParam'] = $data['productParam'] ? serialize($data['productParam']) : array();
				$data['shop_id'] = $_POST['shop_id'];
				$data['cart_cookie'] = $_POST['cart_cookie'];
				$data['uid'] = intval($this->user_session['uid']);
				M('Shop_cart')->data($data)->add();
			}
			$this->success('ok');
		}else{
			$this->success('ok');
		}
	}
	public function ajax_cart(){
		if(!$_POST['cart_cookie'] || !$_POST['shop_id']){
			$this->error('error');
		}
		if($this->user_session['uid']){
			$where['uid'] = $this->user_session['uid'];
			$where['cart_cookie'] = $_POST['cart_cookie'];
			$where['_logic'] = 'or';
			$condition['_complex'] = $where;
		}else{
			$condition['cart_cookie'] = $_POST['cart_cookie'];
		}
		$condition['shop_id'] = $_POST['shop_id'];
		$cartList = M('Shop_cart')->where($condition)->order('`id` ASC')->select();
		if(!$cartList){
			$cartList = array();
		}else{
			foreach($cartList as &$cart_value){
				$cart_value['productParam'] = $cart_value['productParam'] ? unserialize($cart_value['productParam']) : array();
				$cart_value['count'] = intval($cart_value['count']);
				$cart_value['isSeckill'] = $cart_value['isSeckill'] == 'false' ? false : true;
			}
		}
		$this->success($cartList);
	}
}
?>