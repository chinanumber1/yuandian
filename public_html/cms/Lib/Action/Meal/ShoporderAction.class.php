<?php
/*
 * 订餐
 *
 */
class ShoporderAction extends BaseAction
{
	
	public function index()
	{
		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
		//delivery_type 0:平台配送，1：商家配送，2：自提，3:平台配送或自提，4：商家配送或自提
    	$store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
    	$cookieData = isset($_POST['foodshop_cart']) && $_POST['foodshop_cart'] ? htmlspecialchars_decode($_POST['foodshop_cart']) : $_SESSION['foodshop_cart'];
    	$_SESSION['foodshop_cart'] = $cookieData;
    	$cookieData = json_decode($cookieData, true);
    	if (empty($cookieData)) $this->error('您的购物车是空的');
    	$return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);

		if ($return['error_code']) $this->error($return['msg']);
	
		$village_id = isset($_GET['village_id']) ? intval($_GET['village_id']) : 0;
		$this->assign('village_id', $village_id);
		$is_own = 0;
		$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id' => $return['mer_id']))->find();
		foreach ($merchant_ownpay as $ownKey => $ownValue) {
			$ownValueArr = unserialize($ownValue);
			if($ownValueArr['open']){
// 				$is_own = 1;
			}
		}
		if ($is_own) {
			if ($return['delivery_type'] == 0) {
				$this->error('商家配置的配送信息不正确');
			} elseif ($return['delivery_type'] == 3) {
				$return['delivery_type'] = 2;
			}
		}
	
		$return['basic_price'] = $basic_price = $return['price'];
		$return['price'] = round($return['vip_discount_money'] - round($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce'], 2), 2);//实际要支付的价格
	
	
		$advance_day = $return['store']['advance_day'];
		$advance_day = empty($advance_day) ? 1 : $advance_day;
		$date['min_date'] = date('Y-m-d H:i:s', time() + $return['store']['send_time']);
		$date['max_date'] = date('Y-m-d H:i:s', strtotime("+{$advance_day} day") + $return['store']['send_time']);
		if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {
			$delivery_times = explode('-', $this->config['delivery_time']);
			$start_time = $delivery_times[0] . ':00';
			$stop_time = $delivery_times[1] . ':00';
				
			$delivery_times2 = explode('-', $this->config['delivery_time2']);
			$start_time2 = $delivery_times2[0] . ':00';
			$stop_time2 = $delivery_times2[1] . ':00';
				
		} else {
			$start_time = $return['store']['delivertime_start'];
			$stop_time = $return['store']['delivertime_stop'];
				
			$start_time2 = $return['store']['delivertime_start2'];
			$stop_time2 = $return['store']['delivertime_stop2'];
		}
	
		$have_two_time = 1;//是否两个时段 0：没有，1有
	
		$is_cross_day_1 = 0;//第一时间段是否跨天 0：不跨天，1：跨天
		$is_cross_day_2 = 0;//第二时间段是否跨天 0：不跨天，1：跨天
	
		$time = time() + $return['store']['send_time'] * 60;//默认的期望送达时间
	
		$format_second_time = 1;//是否要格式化时间段二
	
		$now_time_value = 1;//当前所处的时间段
		if ($start_time == $stop_time && $start_time == '00:00:00') {//时间段一，24小时
			$start_time = strtotime(date('Y-m-d ') . '00:00');
			$stop_time = strtotime(date('Y-m-d ') . '23:59');
			$have_two_time = 0;
		} else {
			$start_time = strtotime(date('Y-m-d ') . $start_time);
			$stop_time = strtotime(date('Y-m-d ') . $stop_time);
			if ($stop_time < $start_time) {
				$stop_time = $stop_time + 86400;
				$is_cross_day_1 = 1;
			}
				
			if ($time < $start_time) {
				$time = $start_time;
			} elseif ($start_time <= $time && $time <= $stop_time) {
	
			} else {
				$format_second_time = 0;
				if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {//没有时间段二
					$have_two_time = 0;
					$time = $start_time + 86400;
					$start_time2 = strtotime(date('Y-m-d ') . '00:00');
					$stop_time2 = strtotime(date('Y-m-d ') . '23:59');
				} else {
					$start_time2 = strtotime(date('Y-m-d ') . $start_time2);
					$stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
					if ($stop_time2 < $start_time2) {
						$stop_time2 = $stop_time2 + 86400;
						$is_cross_day_2 = 1;
					}
	
					if ($time < $start_time2) {
						$time = $start_time2;
						$now_time_value = 2;
					} elseif ($start_time2 <= $time && $time <= $stop_time2) {
						$now_time_value = 2;
					} else {
						$time = $start_time + 86400;
					}
				}
			}
		}
		if ($format_second_time) {//是否要格式化时间段二
			if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {
				$have_two_time = 0;
				$start_time2 = strtotime(date('Y-m-d ') . '00:00');
				$stop_time2 = strtotime(date('Y-m-d ') . '23:59');
			} else {
				$start_time2 = strtotime(date('Y-m-d ') . $start_time2);
				$stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
				if ($stop_time2 < $start_time2) {
					$stop_time2 = $stop_time2 + 86400;
					$is_cross_day_2 = 1;
				}
			}
		}
	
		if ($have_two_time) {
			$this->assign(array('time_select_1' => date('H:i', $start_time) . '-' . date('H:i', $stop_time), 'time_select_2' => date('H:i', $start_time2) . '-' . date('H:i', $stop_time2)));
		}
		$this->assign('have_two_time', $have_two_time);
		$this->assign('arrive_date', date('Y-m-d', $time));
		$this->assign('arrive_time', date('H:i', $time));
		$this->assign('now_date', date('Y-m-d H:i', $time));
		$this->assign('now_time_value', $now_time_value);
	
	
	
		$date['minYear'] = date('Y', $time);
		$date['minMouth'] = date('n', $time) - 1;
		$date['minDay'] = date('j', $time);
	
	
	
		$date['minHour_today'] = date('G', $time);
		$date['minMinute_today'] = date('i', $time);
	
		$date['minHour_tomorrow'] = date('G', $start_time);
		$date['minMinute_tomorrow'] = date('i', $start_time);
	
		if ($time < $start_time2) {
			$date['minHour_today2'] = date('G', $start_time2);
			$date['minMinute_today2'] = date('i', $start_time2);
		} else {
			$date['minHour_today2'] = date('G', $time);
			$date['minMinute_today2'] = date('i', $time);
		}
		$date['minHour_tomorrow2'] = date('G', $start_time2);
		$date['minMinute_tomorrow2'] = date('i', $start_time2);
	
	
		$date['maxYear_today'] = date('Y', $stop_time);
		$date['maxMouth_today'] = date('n', $stop_time) - 1;
		$date['maxDay_today'] = date('j', $stop_time);
	
		$date['maxYear_today2'] = date('Y', $stop_time2);
		$date['maxMouth_today2'] = date('n', $stop_time2) - 1;
		$date['maxDay_today2'] = date('j', $stop_time2);
	
	
		$time = strtotime("+{$advance_day} day") + $return['store']['send_time'] * 60;
		$date['maxYear'] = date('Y', $time);
		$date['maxMouth'] = date('n', $time) - 1;
		$date['maxDay'] = date('j', $time);
	
	
	
	
		$date['maxHour'] = date('G', $stop_time);
		$date['maxMinute'] = date('i', $stop_time);
	
		$date['maxHour2'] = date('G', $stop_time2);
		$date['maxMinute2'] = date('i', $stop_time2);
	
		$date['today'] = date('Y-m-d');
	
		$date['is_cross_day_1'] = $is_cross_day_1;
		$date['is_cross_day_2'] = $is_cross_day_2;
		// 		echo "<Pre/>";
		// 		print_r($date);die;
		$this->assign($date);
	
		if ($return['store']['basic_price'] <= $basic_price) {
			$address_id = isset($_GET['adress_id']) ? intval($_GET['adress_id']) : cookie('userLocationId');
			$user_adress = D('User_adress')->get_one_adress($this->user_session['uid'], intval($address_id));
			$this->assign('user_adress', $user_adress);
		} else {
			if (in_array($return['delivery_type'], array(2, 3, 4))) {
				$return['delivery_type'] = 2;
			} else {
				$this->error_tips('没有达到起送价，不予以配送');
			}
		}
	
	
		//计算配送费
		if ($user_adress) {
		    if (C('config.is_riding_distance')) {
		        import('@.ORG.longlat');
		        $longlat_class = new longlat();
		        $distance = $longlat_class->getRidingDistance($user_adress['latitude'], $user_adress['longitude'], $return['store']['lat'], $return['store']['long']);
		    }
		    $distance || $distance = getDistance($user_adress['latitude'], $user_adress['longitude'], $return['store']['lat'], $return['store']['long']);
			$distance = $distance / 1000;
			$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
			$return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
			$return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
			$return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
				
			$pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
			$return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
			$return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
			$return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;
		}
		
		$pick_addr_id = isset($_GET['pick_addr_id']) ? $_GET['pick_addr_id'] : '';
		
		$pick_list = D('Pick_address')->get_pick_addr_by_merid($return['mer_id'], true, $store_id);
		if (empty($pick_list)) {
// 		    $lng_lat = D('User_long_lat')->getLocation($_SESSION['openid'], 0);
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
// 		        'distance' => $distance
		    );
		}
		
// 		$pick_list = D('Pick_address')->get_pick_addr_by_merid($return['mer_id'], true);
		if ($pick_addr_id) {
			foreach ($pick_list as $k => $v) {
				if ($v['pick_addr_id'] == $pick_addr_id) {
					$pick_address = $v;
					break;
				}
			}
		} else {
			$pick_address = $pick_list[0];
			$pick_addr_id = $pick_address['pick_addr_id'];
		}
// 		echo '<pre/>';
// 		print_r($return);die;
		$this->assign($return);
		$this->assign('pick_addr_id', $pick_addr_id);
		$this->assign('pick_address', $pick_address);
	
		$now_store_category_relation = M('Shop_category_relation')->where(array('store_id'=>$return['store_id']))->find();
		$now_store_category = M('Shop_category')->where(array('cat_id'=>$now_store_category_relation['cat_id']))->find();
		if($now_store_category['cue_field']){
			$this->assign('cue_field',unserialize($now_store_category['cue_field']));
		}
	
		$this->display();
	
	
	}
	
	
	private function get_reduce($discounts, $type, $price, $store_id = 0)
	{
		$reduce_money = 0;
		if (isset($discounts[$store_id])) {
			foreach ($discounts[$store_id] as $row) {
				if ($row['type'] == $type) {
					if ($price >= $row['full_money']) {
						$reduce_money = max($reduce_money, $row['reduce_money']);
					}
				}
			}
		}
		return $reduce_money;
	}
	
	public function saveorder()
	{
    	//判断登录
    	if(empty($this->user_session)){
    		$this->assign('jumpUrl',U('Index/Login/index'));
    		exit(json_encode(array('error_code' => true, 'msg' => '请先登录！')));
    	}
    	//---------------edit 2017-3-7---------------
    	$store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
    	$cookieData = isset($_POST['foodshop_cart']) && $_POST['foodshop_cart'] ? htmlspecialchars_decode($_POST['foodshop_cart']) : $_SESSION['foodshop_cart'];
    	$_SESSION['foodshop_cart'] = $cookieData;
    	$cookieData = json_decode($cookieData, true);
    	if (empty($cookieData)) exit(json_encode(array('error_code' => true, 'msg' => '您的购物车是空的')));
    	$return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);
        //----------------edit line---------------------------
        //---------------edit 2017-3-7---------------
    	if ($return['error_code']) exit(json_encode($return));
    	
    	if (IS_POST) {
    		$pick_address = isset($_POST['pick_address']) ? htmlspecialchars($_POST['pick_address']) : '';
    		$address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
    		$pick_id = isset($_POST['pick_id']) ? htmlspecialchars($_POST['pick_id']) : '';
    		$pick_id = substr($pick_id, 1);
    		$deliver_type = isset($_POST['deliver_type']) ? intval($_POST['deliver_type']) : 0;
    		$arrive_time = isset($_POST['oarrivalTime']) ? htmlspecialchars($_POST['oarrivalTime']) : 0;
    		$note = isset($_POST['desc']) ? htmlspecialchars($_POST['desc']) : '';
    		$invoice_head = isset($_POST['invoice_head']) ? htmlspecialchars($_POST['invoice_head']) : '';
    		if ($deliver_type != 1) {
    			if ($user_address = D('User_adress')->field(true)->where(array('adress_id' => $address_id, 'uid' => $this->user_session['uid']))->find()) {
    				if ($user_address['longitude'] > 0 && $user_address['latitude'] > 0) {
					    if ($return['store']['delivery_range_type'] == 0) {
							$distance = getDistance($user_address['latitude'], $user_address['longitude'], $return['store']['lat'], $return['store']['long']);
							$delivery_radius = $return['store']['delivery_radius'] * 1000;
							if ($distance > $delivery_radius && $return['delivery_type'] != 5) {
								exit(json_encode(array('error_code' => true, 'msg' => '您到本店的距离是' . $distance . '米,超过了' . $delivery_radius . '米的配送范围')));
							}
					    } else {
					        if ($return['store']['delivery_range_polygon']) {
						        if (!isPtInPoly($user_address['longitude'], $user_address['latitude'], $return['store']['delivery_range_polygon'])) {
						            exit(json_encode(array('error_code' => true, 'msg' => '您的地址不在本店指定的配送区域')));
						        }
					        } else {
					            exit(json_encode(array('error_code' => true, 'msg' => '您的地址不在本店指定的配送区域')));
					        }
					    }
					}
    			} else {
    				exit(json_encode(array('error_code' => true, 'msg' => '不存在的地址')));
    			}
    		}
    		$now_time = time();
			$orderid = date('ymdhis').substr(microtime(),2,8-strlen($this->user_session['uid'])).$this->user_session['uid'];
			$order_data = array();
			$order_data['real_orderid'] = $orderid;
			$order_data['mer_id'] = $return['mer_id'];
    		$order_data['store_id'] = $return['store_id'];
    		$order_data['uid'] = $this->user_session['uid'];
    	
    		$order_data['desc'] = $note;
    		$order_data['create_time'] = $now_time;
    		$order_data['last_time'] = $now_time;
    		$order_data['invoice_head'] = $invoice_head;

// 			$order_data['is_mobile_pay'] = 1;
    		$order_data['num'] = $return['total'];
			$order_data['packing_charge'] = $return['packing_charge'];//打包费
    		$order_data['merchant_reduce'] = $return['sto_first_reduce'] + $return['sto_full_reduce'];//店铺优惠
    		$order_data['balance_reduce'] = $return['sys_first_reduce'] + $return['sys_full_reduce'];//平台优惠
// 			$orderid  = date('ymdhis').substr(microtime(),2,8-strlen($this->user_session['uid'])).$this->user_session['uid'];
// 			$order_data['real_orderid'] = $orderid;
    		$order_data['no_bill_money'] = 0;//无需跟平台对账的金额
    		if ($deliver_type == 1) {//自提
				$order_data['is_pick_in_store'] = 2;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
    			$delivery_fee = $order_data['freight_charge'] = 0;//运费
				$order_data['username'] = isset($this->user_session['nickname']) && $this->user_session['nickname'] ? $this->user_session['nickname'] : '';
				$order_data['userphone'] = isset($this->user_session['phone']) && $this->user_session['phone'] ? $this->user_session['phone'] : '';
    			$order_data['address'] = $pick_address;
    			$order_data['address_id'] = 0;
				$order_data['pick_id'] = $pick_id;
				$order_data['status'] = 7;
				$order_data['expect_use_time'] = time() + $return['store']['send_time'] * 60;//客户期望使用时间
    		} else {//配送
    			$order_data['username'] = $user_address['name'];
    			$order_data['userphone'] = $user_address['phone'];
    			$order_data['address'] = $user_address['adress'] . $user_address['detail'];
    			$order_data['address_id'] = $address_id;
				$order_data['lat'] = $user_address['latitude'];
				$order_data['lng'] = $user_address['longitude'];
// 				if ($arrive_date == 0) {
// 					$arrive_date = date('Y-m-d');
// 				}
				if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {
					$delivery_times = explode('-', $this->config['delivery_time']);
					$start_time = $delivery_times[0] . ':00';
					$stop_time = $delivery_times[1] . ':00';
				
					$delivery_times2 = explode('-', $this->config['delivery_time2']);
					$start_time2 = $delivery_times2[0] . ':00';
					$stop_time2 = $delivery_times2[1] . ':00';
				} else {
					$start_time = $return['store']['delivertime_start'];
					$stop_time = $return['store']['delivertime_stop'];
				
					$start_time2 = $return['store']['delivertime_start2'];
					$stop_time2 = $return['store']['delivertime_stop2'];
				}
				
				if ($start_time == $stop_time && $start_time == '00:00:00') {
					$stop_time = '23:59:59';
				}
				$if_start_time = strtotime(date('Y-m-d ') . $start_time);
				$if_stop_time = strtotime(date('Y-m-d ') . $stop_time);
				
				if ($if_start_time > $if_stop_time) {
					$if_stop_time = $if_stop_time + 86400;
				}
				
				$if_start_time2 = strtotime(date('Y-m-d ') . $start_time2);
				$if_stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
				if ($if_start_time2 > $if_stop_time2) {
					$if_stop_time2 = $if_stop_time2 + 86400;
				}
				
				if ($arrive_time == 0) {
// 					if ($arrive_date != date('Y-m-d')) {
// 						$arrive_time = strtotime($arrive_date . $start_time);
// 					} else {
						$arrive_time = time() + $return['store']['send_time'] * 60;
						if ($start_time == $stop_time && $start_time == '00:00:00') {
								
						} else {
							$_start_time = strtotime(date('Y-m-d ') . $start_time);
							$_stop_time = strtotime(date('Y-m-d ') . $stop_time);
							if ($_start_time > $_stop_time) {
								$_stop_time = $_stop_time + 86400;
							}
							if ($arrive_time < $_start_time) {
								$arrive_time = $_start_time;
							} elseif ($_start_time <= $arrive_time && $arrive_time <= $_stop_time) {
						
							} else {
								$_start_time2 = strtotime(date('Y-m-d ') . $start_time2);
								$_stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
								if ($_start_time2 > $_stop_time2) {
									$_stop_time2 = $_stop_time2 + 86400;
								}
								if ($arrive_time < $_start_time2) {
									$arrive_time = $_start_time2;
								} elseif ($_start_time2 <= $arrive_time && $arrive_time <= $_stop_time2) {
								
								} else {
									$arrive_time = $_start_time + 86400;
								}
							}
						}
// 					}
				} else {
					$arrive_time = strtotime($arrive_time);
					if ($start_time == $stop_time && $start_time == '00:00:00') {
							
					} else {
    				    $advance_day = $return['store']['advance_day'];
    					$advance_day = empty($advance_day) ? 1 : $advance_day;
    					$isTrueTime = false;
    					for ($day = 0; $day <= $advance_day; $day ++) {
    					    $_start_time = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' ' . $start_time);
    					    $_stop_time = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' ' . $stop_time);
    					    if ($etime < $stime) $etime = $etime + 86400;
    					    
    					    if ($_start_time <= $arrive_time && $arrive_time <= $_stop_time) {
    					        $isTrueTime = true;
    					        break;
    					    } elseif ($start_time2 != 0 && $stop_time2 != 0) {
    					        $_start_time = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' ' . $start_time2);
    					        $_stop_time = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' ' . $stop_time2);
    					        if ($_start_time <= $arrive_time && $arrive_time <= $_stop_time) {
    					            $isTrueTime = true;
    					            break;
    					        }
    					    }
    					}
    					if (!$isTrueTime)exit(json_encode(array('error_code' => true, 'msg' => '您选择的时间不在配送时间段内')));
					}
				}
				
				$order_data['expect_use_time'] = $arrive_time ? $arrive_time : time() + $return['store']['send_time'] * 60;//客户期望使用时间
				//计算配送费
				if (C('config.is_riding_distance')) {
				    import('@.ORG.longlat');
				    $longlat_class = new longlat();
				    $distance = $longlat_class->getRidingDistance($user_address['latitude'], $user_address['longitude'], $return['store']['lat'], $return['store']['long']);
				    $distance || $distance = getDistance($user_address['latitude'], $user_address['longitude'], $return['store']['lat'], $return['store']['long']);
				}
				
				$distance = $distance / 1000;
				if ($return['delivery_type'] == 5) {//快递配送
					$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
					$return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
					$return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
					$return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
					$delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];//运费
				} else {
					$expect_use_time_temp = strtotime(date('Y-m-d ') . date('H:i:s', $order_data['expect_use_time']));
					if ($if_start_time <= $expect_use_time_temp && $expect_use_time_temp <= $if_stop_time) {//时间段一
						$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
						$return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
						$return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
						$return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
						$delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];//运费
					} elseif ($if_start_time2 <= $expect_use_time_temp && $expect_use_time_temp <= $if_stop_time2) {//时间段二
						$pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
						$return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
						$return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
						$return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;
						$delivery_fee = $order_data['freight_charge'] = $return['delivery_fee2'];//运费
					} else {
						exit(json_encode(array('error_code' => true, 'msg' => '您选择的时间不在配送时间段内！')));
					}
				}
				if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {//平台配送
					$order_data['is_pick_in_store'] = 0;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
					$order_data['no_bill_money'] = $delivery_fee + $return['merchant_reduce_deliver_money'];
				} elseif ($return['delivery_type'] == 1 || $return['delivery_type'] == 4)  {
					$order_data['is_pick_in_store'] = 1;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
				} else {
					$order_data['is_pick_in_store'] = 3;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
    			}
    		}

    		$order_data['order_from'] = 5;//订单来源:0：wap快店，1：wap商城，2：Android，3：ios,4:小程序,5：pc快店
    		$order_data['goods_price'] = $return['price'];//商品的价格
    		$order_data['extra_price'] = $return['extra_price'];//另外要支付的金额
    		$order_data['discount_price'] = $return['vip_discount_money'];//商品折扣后的总价
			$order_data['total_price'] = $return['price'] + $delivery_fee + $return['packing_charge'];//订单总价  商品价格+打包费+配送费
			$order_data['price'] = $order_data['discount_price'] + $delivery_fee + $return['packing_charge'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'];//实际要支付的价格
    		$order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情


			$order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'
    			
			if ($order_id = D('Shop_order')->saveOrder($order_data, $return, $this->user_session)) {
			    cookie('shop_cart_' . $return['store_id'], null);
    			exit(json_encode(array('error_code' => 0, 'msg' => '', 'data' => U('Index/Pay/check', array('order_id' => $order_id,'type'=>'shop')))));
			} else {
			    exit(json_encode(array('error_code' => 0, 'msg' => '订单保存失败')));
			}
			
    		if ($order_id = D('Shop_order')->add($order_data)) {
    			
    			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 0));
				if ($order_data['is_pick_in_store'] == 2 && $order_data['status'] == 7) {
					D('Pick_order')->add(array('store_id' => $order_data['store_id'], 'order_id' => $order_id, 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
					//D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//分配到自提点
				}
				$_SESSION['foodshop_cart'] = null;
    			$detail_obj = D('Shop_order_detail');
				$goods_obj = D("Shop_goods");
				foreach ($return['goods'] as $grow) {
					$detail_data = array('store_id' => $return['store_id'], 'order_id' => $order_id, 'number' => isset($grow['number']) && $grow['number'] ? $grow['number'] : '', 'cost_price' => $grow['cost_price'], 'unit' => $grow['unit'], 'goods_id' => $grow['goods_id'], 'name' => $grow['name'], 'price' => $grow['price'], 'num' => $grow['num'], 'spec' => $grow['str'], 'spec_id' => $grow['spec_id'], 'create_time' => time(),'extra_price'=>$grow['extra_price']);
					$detail_data['is_seckill'] = intval($grow['is_seckill_price']);
					$detail_data['discount_type'] = intval($grow['discount_type']);
					$detail_data['discount_rate'] = $grow['discount_rate'];
					$detail_data['sort_id'] = $grow['sort_id'];
					$detail_data['old_price'] = floatval($grow['old_price']);
					$detail_data['discount_price'] = floatval($grow['discount_price']);
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
    			
//     			$msg = ArrayToStr::array_to_str($order_id, 'shop_order');
//     			$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
//     			$op->printit($return['mer_id'], $return['store_id'], $msg, 0);
    	
// 				$str_format = ArrayToStr::print_format($order_id, 'shop_order');
//     			foreach ($str_format as $print_id => $print_msg) {
//     				$print_id && $op->printit($return['mer_id'], $return['store_id'], $print_msg, 0, $print_id);
//     			}
    	
    	
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
    	
    	
    			cookie('foodshop_cart_' . $return['store_id'], null);
    			exit(json_encode(array('error_code' => 0, 'msg' => '', 'data' => U('Index/Pay/check', array('order_id' => $order_id,'type'=>'shop')))));
    		} else {
    			exit(json_encode(array('error_code' => 0, 'msg' => '订单保存失败')));
    		}
    	} else {
    		exit(json_encode(array('error_code' => 0, 'msg' => '不合法的提交')));
    	}
	}
	
	public function ajax_prices()
	{
		$address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
		$type = isset($_POST['type']) ? intval($_POST['type']) : 0;
		
    	$store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
    	$cookieData = isset($_POST['foodshop_cart']) && $_POST['foodshop_cart'] ? htmlspecialchars_decode($_POST['foodshop_cart']) : $_SESSION['foodshop_cart'];
    	$_SESSION['foodshop_cart'] = $cookieData;
    	$cookieData = json_decode($cookieData, true);
    	if (empty($cookieData)) exit(json_encode(array('error_code' => true, 'msg' => '您的购物车是空的！')));
    	$return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);
		if ($return['error_code']) exit(json_encode($return));
		$arrive_time = isset($_POST['oarrivalTime']) ? htmlspecialchars($_POST['oarrivalTime']) : 0;
		if (empty($this->user_session)) {
			exit(json_encode(array('error_code' => true, 'msg' => '请先登录！')));
		}

		if ($type == 1) {
			$price = $return['vip_discount_money'] + $return['packing_charge'] - round(($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce']), 2);//实际要支付的价格
			$data = array('error_code' => false, 'price' => $price, 'delivery_fee' => 0, 'delivery_fee1' => 0, 'delivery_fee2' => 0);
			exit(json_encode($data));
		}
		
		$user_adress = D('User_adress')->get_one_adress($this->user_session['uid'], intval($address_id));
		if (empty($user_adress)) exit(json_encode(array('error_code' => true, 'msg' => '地址不存在')));
		
		if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {
			$delivery_times = explode('-', $this->config['delivery_time']);
			$start_time = $delivery_times[0] . ':00';
			$stop_time = $delivery_times[1] . ':00';
		
			$delivery_times2 = explode('-', $this->config['delivery_time2']);
			$start_time2 = $delivery_times2[0] . ':00';
			$stop_time2 = $delivery_times2[1] . ':00';
		} else {
			$start_time = $return['store']['delivertime_start'];
			$stop_time = $return['store']['delivertime_stop'];
		
			$start_time2 = $return['store']['delivertime_start2'];
			$stop_time2 = $return['store']['delivertime_stop2'];
		}
		
		if ($start_time == $stop_time && $start_time == '00:00:00') {
			$stop_time = '23:59:59';
		}
		$if_start_time = strtotime(date('Y-m-d ') . $start_time);
		$if_stop_time = strtotime(date('Y-m-d ') . $stop_time);
		
		if ($if_start_time > $if_stop_time) {
			$if_stop_time = $if_stop_time + 86400;
		}
		
		$if_start_time2 = strtotime(date('Y-m-d ') . $start_time2);
		$if_stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
		if ($if_start_time2 > $if_stop_time2) {
			$if_stop_time2 = $if_stop_time2 + 86400;
		}
		$arrive_time = $arrive_time ? strtotime($arrive_time) : 0;
		$arrive_time = $arrive_time ? $arrive_time : time() + $return['store']['send_time'] * 60;//客户期望使用时间
		$expect_use_time_temp = strtotime(date('Y-m-d ') . date('H:i:s', $arrive_time));
		

		//计算配送费
		$distance = getDistance($user_adress['latitude'], $user_adress['longitude'], $return['store']['lat'], $return['store']['long']);
		$distance = $distance / 1000;
// 		echo $return['delivery_fee_reduce'];die;
		$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
		$return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
		$return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
		$return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
		
		$pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
		$return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
		$return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
		$return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;
				
		if ($if_start_time <= $expect_use_time_temp && $expect_use_time_temp <= $if_stop_time) {//时间段一
			$delivery_fee  = $return['delivery_fee'];//运费
		} elseif ($if_start_time2 <= $expect_use_time_temp && $expect_use_time_temp <= $if_stop_time2) {//时间段二
			$delivery_fee = $return['delivery_fee2'];//运费
		} else {
			exit(json_encode(array('error_code' => true, 'msg' => '您选择的时间不在配送时间段内！')));
		}

		$price = round(($return['vip_discount_money'] + $return['packing_charge'] + $delivery_fee), 2) - round(($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce']), 2);//实际要支付的价格
		

		
		if($this->config['open_extra_price']){
			$extra_price = $return['extra_price'];
		}else{
			$extra_price = 0;
		}
		
		$data = array('error_code' => false, 'price' => $price, 'delivery_fee' => $delivery_fee, 'delivery_fee1' => $return['delivery_fee'], 'delivery_fee2' => $return['delivery_fee2'],'extra_price'=>$extra_price);
		exit(json_encode($data));
	}

    public function mall()
    {
        // 所有分类 包含2级分类
        $all_category_list = D('Group_category')->get_category();
        $this->assign('all_category_list', $all_category_list);
        $store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
        $cookieData = $_SESSION['pc_mall_cart'];

        $cookieData = json_decode($cookieData, true);
        if (empty($cookieData)) {
            $this->error('您的购物车是空的');
        }
        
        $user_adress = D('User_adress')->get_one_adress($this->user_session['uid']);
        $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData, 1, $user_adress['adress_id']);
        if ($return['error_code']) {
            $this->error($return['msg']);
        }
        
        $return['basic_price'] = $basic_price = $return['price'];
        $return['price'] = round($return['vip_discount_money'] - round($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce'], 2), 2); // 实际要支付的价格
        $this->assign($return);
        $now_store_category_relation = M('Shop_category_relation')->where(array('store_id' => $return['store_id']))->find();
        $now_store_category = M('Shop_category')->where(array('cat_id' => $now_store_category_relation['cat_id']))->find();
        if ($now_store_category['cue_field']) {
            $this->assign('cue_field', unserialize($now_store_category['cue_field']));
        }
        $this->display();
    }
    
    
    public function ajaxMall()
    {
        $address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
        
        $user_adress = D('User_adress')->get_one_adress($this->user_session['uid'], intval($address_id));
        if (empty($user_adress)) exit(json_encode(array('error_code' => true, 'msg' => '地址不存在')));
        
        $store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
        $cookieData = $_SESSION['pc_mall_cart'];
        $cookieData = json_decode($cookieData, true);
        if (empty($cookieData)) exit(json_encode(array('error_code' => true, 'msg' => '您的购物车是空的！')));
        $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData, 1, $address_id);
        if ($return['error_code']) exit(json_encode($return));
        $arrive_time = isset($_POST['oarrivalTime']) ? htmlspecialchars($_POST['oarrivalTime']) : 0;
        if (empty($this->user_session)) {
            exit(json_encode(array('error_code' => true, 'msg' => '请先登录！')));
        }
        $price = round(($return['vip_discount_money'] + $return['packing_charge'] + $delivery_fee), 2) - round(($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce']), 2);//实际要支付的价格
        
        if($this->config['open_extra_price']){
            $extra_price = $return['extra_price'];
        }else{
            $extra_price = 0;
        }
        
        $data = array('error_code' => false, 'price' => $price, 'delivery_fee' => $delivery_fee,'extra_price'=>$extra_price);
        exit(json_encode($data));
    }
    
    public function saveMall()
    {
        //判断登录
        if(empty($this->user_session)){
            exit(json_encode(array('error_code' => true, 'msg' => '请先登录！')));
        }
        
        $address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
        $user_address = D('User_adress')->get_one_adress($this->user_session['uid'], intval($address_id));
        if (empty($user_address)) {
            exit(json_encode(array('error_code' => true, 'msg' => '地址不存在')));
        }
        $store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
        $cookieData = $_SESSION['pc_mall_cart'];
        $cookieData = json_decode($cookieData, true);
        if (empty($cookieData)) {
            exit(json_encode(array('error_code' => true, 'msg' => '您的购物车是空的！')));
        }
        $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData, 1, $address_id);
        if ($return['error_code']) {
            exit(json_encode(array('error_code' => true, 'msg' => $return['msg'])));
        }
        if (IS_POST) {
//             $village_id = isset($_REQUEST['village_id']) ? intval($_REQUEST['village_id']) : 0;
            $phone = isset($_POST['ouserTel']) ? htmlspecialchars($_POST['ouserTel']) : '';
            $name = isset($_POST['ouserName']) ? htmlspecialchars($_POST['ouserName']) : '';
            $address = isset($_POST['ouserAddres']) ? htmlspecialchars($_POST['ouserAddres']) : '';
            $pick_address = isset($_POST['pick_address']) ? htmlspecialchars($_POST['pick_address']) : '';
            $invoice_head = isset($_POST['invoice_head']) ? htmlspecialchars($_POST['invoice_head']) : '';
//             $pick_id = isset($_POST['pick_id']) ? htmlspecialchars($_POST['pick_id']) : 0;
//             $pick_id = substr($pick_id, 1);
//             $deliver_type = isset($_POST['deliver_type']) ? intval($_POST['deliver_type']) : 0;
//             $arrive_time = isset($_POST['oarrivalTime']) ? htmlspecialchars($_POST['oarrivalTime']) : 0;
//             $arrive_date = isset($_POST['oarrivalDate']) ? htmlspecialchars($_POST['oarrivalDate']) : 0;
            $note = isset($_POST['omark']) ? htmlspecialchars($_POST['omark']) : '';
            
            $now_time = time();
            $order_data = array();
            $order_data['mer_id'] = $return['mer_id'];
            $order_data['store_id'] = $return['store_id'];
            $order_data['uid'] = $this->user_session['uid'];
            
            $order_data['desc'] = $note;
            $order_data['create_time'] = $now_time;
            $order_data['last_time'] = $now_time;
            $order_data['invoice_head'] = $invoice_head;
//             $order_data['village_id'] = $village_id;
            
            $order_data['num'] = $return['total'];
            $order_data['packing_charge'] = $return['packing_charge'];//打包费
            $order_data['merchant_reduce'] = $return['sto_first_reduce'] + $return['sto_full_reduce'];//店铺优惠
            $order_data['balance_reduce'] = $return['sys_first_reduce'] + $return['sys_full_reduce'];//平台优惠
            $orderid  = date('ymdhis').substr(microtime(),2,8-strlen($this->user_session['uid'])).$this->user_session['uid'];
            $order_data['real_orderid'] = $orderid;
            $order_data['no_bill_money'] = 0;//无需跟平台对账的金额
            
            $order_data['username'] = $name;
            $order_data['userphone'] = $phone;
            $order_data['address'] = $address;
            $order_data['address_id'] = $address_id;
            $order_data['lat'] = $user_address['latitude'];
            $order_data['lng'] = $user_address['longitude'];
            $delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];//运费
            $order_data['is_pick_in_store'] = 3;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
            
            
            $order_data['order_from'] = 7;//pc商城
            $order_data['goods_price'] = $return['price'];//商品的价格
            $order_data['extra_price'] = $return['extra_price'];//另外要支付的金额
            $order_data['discount_price'] = $return['vip_discount_money'];//商品折扣后的总价
            $order_data['total_price'] = $return['price'] + $delivery_fee + $return['packing_charge'];//订单总价  商品价格+打包费+配送费
            $order_data['price'] = $order_data['discount_price'] + $delivery_fee + $return['packing_charge'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'];//实际要支付的价格
            $order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情
            
            $order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'
            
            
            //自定义字段
            if($_POST['cue_field']){
                $order_data['cue_field'] = serialize($_POST['cue_field']);
            }
            
            if ($order_id = D('Shop_order')->add($order_data)) {
                $_SESSION['pc_mall_cart'] = null;
                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 0));
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
                exit(json_encode(array('error_code' => 0, 'msg' => '', 'data' => U('Index/Pay/check', array('order_id' => $order_id, 'type' => 'shop')))));
                redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'mall')));
            } else {
                exit(json_encode(array('error_code' => true, 'msg' => '订单保存失败')));
            }
        } else {
            
            exit(json_encode(array('error_code' => true, 'msg' => '不合法的提交')));
        }
        
    }
}