<?php
class Merchant_store_foodshopModel extends Model
{
	public function get_list_group_by_option()
	{
		$now_time = time();
		$sql = 'SELECT * FROM ' . C('DB_PREFIX') . 'merchant_store_foodshop AS f INNER JOIN ' . C('DB_PREFIX') . 'merchant_store AS s ON f.store_id=s.store_id WHERE s.have_meal=1 AND s.status=1';
		$temps = D()->query($sql);
		$result = array();
		foreach ($temps as $tmp) {
			$tmp['group_list'] = '';
			$tmp['group_count'] = 0;
			$tmp['discount_txt'] = unserialize($tmp['discount_txt']);
			$result[$tmp['store_id']] = $tmp;
		}
		$store_id_arr = array_keys($result);
		if ($store_id_arr) {
			$store_ids = implode(',', $store_id_arr);
			$sql = 'SELECT * FROM ' . C('DB_PREFIX') . 'group_store AS gs INNER JOIN ' . C('DB_PREFIX') . 'group AS g ON g.group_id=gs.group_id WHERE gs.store_id IN (' . $store_ids . ')';
			$groups = D()->query($sql);
			$group_image_class = new group_image();
			foreach ($groups as $row) {
				$tmp_pic_arr = explode(';', $row['pic']);
				$row['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
				$row['url'] = $this->get_group_url($row['group_id'], true);

				$row['price'] = floatval($row['price']);
				$row['old_price'] = floatval($row['old_price']);
				$row['wx_cheap'] = floatval($row['wx_cheap']);
				$row['is_start'] = 1;
				if ($now_time < $row['begin_time']) {
					$row['is_start'] = 0;
				}
				$row['begin_time'] = date("Y-m-d H:i:s", $row['begin_time']);
				if (isset($result[$row['store_id']])) {
					$result[$row['store_id']]['group_count']++;
					$result[$row['store_id']]['group_list'][] = $row;
				}
			}
		}
		return $result;
	}
	/*wap版得到指定分类ID或分类父ID下的分类，带有分页功能*/
// 	public function wap_get_storeList_by_catid($area_id = 0, $circle_id = 0, $order = '', $lat = 0, $long = 0, $cat_fid = 0, $cat_id = 0, $is_queue = -1)
	public function wap_get_storeList_by_catid($params,$is_wap=0)
	{
		$area_id = $params['area_id'];
		$circle_id = $params['circle_id'];
		$order = $params['sort'];
		$lat = $params['lat'];
		$long = $params['long'];
		$cat_fid = $params['cat_fid'];
		$cat_id = $params['cat_id'];
		$is_queue = $params['queue'];
		$keyword = $params['keyword'];
		$site_url  = C('config.site_url');

		$condition_field = '`f`.*, `s`.*, `m`.discount_percent, `m`.is_discount, `m`.other_discount, `m`.foodshop_tables_discount';

// 		$condition_where = 's.have_meal=1 AND s.status=1';
		$condition_where = "s.city_id='" . C('config.now_city') . "' AND s.have_meal=1 AND s.status=1";
		if ($is_queue == 1) {
			$condition_where .= ' AND f.is_queue=1';
		} elseif ($is_queue == 0) {
			$condition_where .= ' AND f.is_queue=0';
		}
		//区域
		if($area_id || $circle_id){
			if ($circle_id) {
				$condition_where .= " AND `s`.`circle_id`='$circle_id'";
			} else {
				$condition_where .= " AND `s`.`area_id`='$area_id'";
			}
		}


		if ($cat_fid || $cat_id) {
			if ($cat_fid && $cat_id) {
				$relation = D('Meal_store_category_relation')->where(array(array('cat_fid' => $cat_fid, 'cat_id' => $cat_id)))->select();
			} elseif ($cat_fid) {
				$relation = D('Meal_store_category_relation')->where(array(array('cat_fid' => $cat_fid)))->select();
			} else {
				$relation = D('Meal_store_category_relation')->where(array(array('cat_id' => $cat_id)))->select();
			}
			$store_ids = array();
			foreach ($relation as $r) {
				if (!in_array($r['store_id'], $store_ids)) {
					$store_ids[] = $r['store_id'];
				}
			}
			if ($store_ids) {
				$condition_where .= ' AND s.store_id IN (' . implode(',', $store_ids) . ')';
			} else {
				return array('store_list' => null, 'store_count' => 0, 'totalPage' => 0);
			}
		}
		if ($keyword) {
			$condition_where .= " AND `s`.`name` LIKE '%{$keyword}%'";
		}

		if ($lat && $long) {
			if ($order == 'juli') {
				$condition_field .= ", ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli,m.isverify";
			}
		} else {
			$order = $order == 'juli' ? '' : $order;
		}
            // 排序
        switch ($order) {
//             case 'rating':
//                 $order = '`f`.`sort` DESC, `f`.`score_mean` DESC, `f`.`sort` DESC';
//                 break;
//             case 'start':
//                 $order = '`f`.`sort` DESC, `f`.`create_time` DESC,`f`.`sort` DESC';
//                 break;
            case 'new':
                $order = '`f`.`create_time` DESC, `f`.`sort` DESC';
            case 'discount':
                $order = '`mm`.`is_discount` DESC, `mm`.discount_percent asc, `f`.`sort` DESC';
            case 'juli':
                $order = '`f`.`sort` DESC, `juli` asc, `f`.`sort` DESC';
                break;
            default:
                $order = '`f`.`sort` DESC, `f`.`create_time` DESC';
        }
        
        import('@.ORG.wap_group_page');
        
        $sql_count = 'SELECT count(1) as cnt  FROM ' . C('DB_PREFIX') . 'merchant_store_foodshop AS f INNER JOIN ' . C('DB_PREFIX') . 'merchant_store AS s ON f.store_id=s.store_id LEFT JOIN ' . C('DB_PREFIX') . 'merchant mm ON mm.mer_id = s.mer_id WHERE ' . $condition_where . ' AND mm.status = 1';
        
        $count_result = D()->query($sql_count);
        // echo D()->_sql();die;
        $count = isset($count_result[0]['cnt']) ? intval($count_result[0]['cnt']) : 0;
        
        $p = new Page($count, 10, 'page');
        if ($is_wap == 1) {
            $pagesize = 10;
            $star = ((isset($params['page']) ? intval($params['page']) : 0)) * $pagesize;
        } else if ($is_wap == 2)  {
			$pagesize = 10;
			$star =isset($params['page']) ? intval($params['page']) : 0;
		}else {
            $star = $p->firstRow;
            $pagesize = $p->listRows;
        }

		$sql = 'SELECT ' . $condition_field . ' FROM ' . C('DB_PREFIX') . 'merchant_store_foodshop AS f INNER JOIN ' . C('DB_PREFIX') . 'merchant_store AS s ON f.store_id=s.store_id LEFT JOIN ' . C('DB_PREFIX') . 'merchant AS m ON m.mer_id = s.mer_id WHERE ' . $condition_where . ' AND m.status=1 ORDER BY ' . $order . ' LIMIT ' . $star . ',' . $pagesize;
		$store_list = D()->query($sql);
		import('@.ORG.longlat');
		$longlat_class = new longlat();
		$store_image_class = new store_image();
        $nowTime = time() - 864000;
		foreach ($store_list as $tmp) {
		    $tmps['is_new'] = 0;
		    if ($tmp['create_time'] > $nowTime) {
                if (1 == C('config.is_open_merchant_foodshop_discount')) {
                    $tmps['is_new'] = 1;
                }
		    }
		    $tmps['sys_discount'] = 0;
		    if (1 == C('config.is_open_merchant_discount') && $tmp['is_discount'] == 1 && $tmp['discount_percent'] > 0) {
		        $tmps['sys_discount'] = floatval($tmp['discount_percent'] / 10);
		    }
            if (1 == C('config.is_open_merchant_foodshop_discount')) {
		        $time_start = strtotime(date('Ymd'));
		        $time_end = strtotime(date('Ymd 23:59:59'));
		        $where['create_time'] = array('between',array($time_start,$time_end));
                $where['status'] = array('in',array(1,2,3,4));
		        $where['mer_id'] = $tmp['mer_id'];
		        $order_index = D('foodshop_order')->where($where )->count();

                if($tmp['foodshop_tables_discount']!=''){
                    $tmp['foodshop_tables_discount'] = substr($tmp['foodshop_tables_discount'], 0, -1);
                    $discountArr= explode('|', $tmp['foodshop_tables_discount']);
                    $tables = [];
                    foreach($discountArr as $k => $v){
                        $v = explode(':',$v);
                        $tables[$k] = $v;
                    }
                    if(intval($order_index)>=count($tables)){
                        $mer_discount =  $tmp['other_discount'];
                    }else{
                        $mer_discount =  $tables[$order_index][1];
                    }
                }else{
                    $mer_discount =  $tmp['other_discount'];
                }
                $tmps['mer_discount'] = floatval($mer_discount / 10);
            }
		    $tmps['name'] = $tmp['name'];
		    $images = $store_image_class->get_allImage_by_path($tmp['pic_info']);
		    $tmps['image'] = $images ? array_shift($images) : '';
			$tmps['phone'] = $tmp['phone'];
			$tmps['adress'] = $tmp['adress'];
			$tmps['is_book'] = $tmp['is_book'];
			$tmps['is_queue'] = $tmp['is_queue'];
			$tmps['is_takeout'] = $tmp['is_takeout'];
			$tmps['long'] = $tmp['long'];
			$tmps['lat'] = $tmp['lat'];
			if ($tmp['juli']) {
				$tmps['range'] = getRange($tmp['juli']);
			} else {
				$location2 = $longlat_class->gpsToBaidu($tmp['lat'], $tmp['long']);//转换腾讯坐标到百度坐标
				$jl = getDistance($location2['lat'], $location2['lng'], $lat, $long);
				$tmps['range'] = getRange($jl);
			}


			$tmps['state'] = 0;//根据营业时间判断
			$time = time();
			$open_1 = strtotime(date('Y-m-d') . $tmp['open_1']);
			$close_1 = strtotime(date('Y-m-d') . $tmp['close_1']);
			$open_2 = strtotime(date('Y-m-d') . $tmp['open_2']);
			$close_2 = strtotime(date('Y-m-d') . $tmp['close_2']);
			$open_3 = strtotime(date('Y-m-d') . $tmp['open_3']);
			$close_3 = strtotime(date('Y-m-d') . $tmp['close_3']);
			if ($tmp['open_1'] == '00:00:00' && $tmp['close_1'] == '00:00:00') {
				$tmps['state'] = 1;
			} elseif (($open_1 < $time && $close_1 > $time) || ($open_2 < $time && $close_2 > $time) || ($open_3 < $time && $close_3 > $time)) {
				$tmps['state'] = 1;
			}

			$tmps['group_list'] = array();
			$tmps['group_count'] = 0;
			$tmps['pay_in_store'] = C('config.pay_in_store');
			$tmps['store_pay'] = $site_url.str_replace('appapi.php','wap.php',U('My/pay', array('store_id' => $tmp['store_id'])));
			$tmps['url'] = $site_url.str_replace('appapi.php','wap.php',U('Foodshop/shop', array('store_id' => $tmp['store_id'])));
			$tmps['discount_txt'] = $tmp['discount_txt'] ? unserialize($tmp['discount_txt']) : array();
			$tmps['score_mean'] = $tmp['score_mean']==0 ? 5.0 : $tmp['score_mean'];
			$tmps['store_id'] = $tmp['store_id'];
			$result[$tmp['store_id']] = $tmps;
		}

		$store_id_arr = array_keys($result);
		if ($store_id_arr) {
			$store_ids = implode(',', $store_id_arr);
			$now_time = time();
			$sql = 'SELECT g.name,g.pic,g.group_id,g.price,g.old_price,g.wx_cheap,g.pin_num,g.sale_count,g.virtual_num,g.begin_time,gs.store_id FROM ' . C('DB_PREFIX') . 'group_store AS gs INNER JOIN ' . C('DB_PREFIX') . 'group AS g ON g.group_id=gs.group_id WHERE gs.store_id IN (' . $store_ids . ') AND `g`.`status`=1 AND `g`.`type`=1 AND `g`.`end_time`>\'' . $now_time . '\' ORDER BY `g`.`sort` DESC,`g`.`group_id` DESC';
			$groups = D()->query($sql);
			$group_image_class = new group_image();
			foreach ($groups as $row) {
				$tmp_pic_arr = explode(';', $row['pic']);
				$row['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
				$row['url'] =$site_url.$this->get_group_url($row['group_id'], true);

				$row['price'] = floatval($row['price']);
				$row['old_price'] = floatval($row['old_price']);
				$row['wx_cheap'] = floatval($row['wx_cheap']);
				$row['is_start'] = 1;
				if ($now_time < $row['begin_time']) {
					$row['is_start'] = 0;
				}
				if($row['begin_time']+864000>time()&&$row['sale_count']==0){
					$row['sale_txt'] = '新品上架';
				}elseif($row['begin_time']+864000<time()&&$row['sale_count']==0){
					$row['sale_txt'] = '';
				}else{
					$row['sale_txt'] = '已售'.floatval($row['sale_count']+$row['virtual_num']);
				}
				$row['begin_time'] = date("Y-m-d H:i:s", $row['begin_time']);
				if (isset($result[$row['store_id']])) {
					if ($result[$row['store_id']]['group_count'] < 2) {
						$result[$row['store_id']]['group_list'][] = $row;
					}
					$result[$row['store_id']]['group_count']++;
				}
			}
		}
		$_store_list = array();
		foreach ($result as $row) {
			$_store_list[] = $row;
		}
		$return['totalPage'] = $p->totalPage;//ceil($count / 10);


		$return['store_list'] = $_store_list;
		$return['store_count'] = $count;
		return $return;

	}
	public function get_group_url($group_id, $is_wap)
	{
		if ($is_wap) {
			return str_replace('appapi.php', 'wap.php', U('Wap/Group/detail', array('group_id' => $group_id)));
		} else {
			return C('config.site_url') . '/group/' . $group_id . '.html';
		}
	}

	public function get_shop_detail($store_id)
	{
		$sql = 'SELECT * FROM ' . C('DB_PREFIX') . 'merchant_store_foodshop AS f INNER JOIN ' . C('DB_PREFIX') . 'merchant_store AS s ON f.store_id=s.store_id WHERE s.have_meal=1 AND s.status=1 AND s.store_id=' . $store_id;
		$temps = D()->query($sql);
// 		echo '<Pre/>';
// 		print_r($temps);
		$shop = isset($temps[0]) ? $temps[0] : '';
		if ($shop) {

			if ($shop['open_1'] == '00:00:00' && $shop['close_1'] == '00:00:00') {
				$shop['business_time'] = '24小时营业';
			} else {
				$shop['business_time'] = $shop['open_1'] . '~' . $shop['close_1'];
				if ($shop['open_2'] != '00:00:00' && $shop['close_2'] != '00:00:00') {
					$shop['business_time'] = ',' . $shop['open_2'] . '~' . $shop['close_2'];
				}
				if ($shop['open_3'] != '00:00:00' && $shop['close_3'] != '00:00:00') {
					$shop['business_time'] = ',' . $shop['open_3'] . '~' . $shop['close_3'];
				}
			}
			$shop['group_list'] = '';
			$shop['group_count'] = 0;
			$sql = 'SELECT * FROM ' . C('DB_PREFIX') . 'group_store AS gs INNER JOIN ' . C('DB_PREFIX') . 'group AS g ON g.group_id=gs.group_id WHERE gs.store_id=' . $store_id;
			$groups = D()->query($sql);
			$group_image_class = new group_image();
			foreach ($groups as $row) {
				$tmp_pic_arr = explode(';', $row['pic']);
				$row['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
				$row['url'] = $this->get_group_url($row['group_id'], true);

				$row['price'] = floatval($row['price']);
				$row['old_price'] = floatval($row['old_price']);
				$row['wx_cheap'] = floatval($row['wx_cheap']);
				$row['is_start'] = 1;
				if ($now_time < $row['begin_time']) {
					$row['is_start'] = 0;
				}
				$row['begin_time'] = date("Y-m-d H:i:s", $row['begin_time']);
				$shop['group_list'][] = $row;
				$shop['group_count']++;
			}
		}
		return $shop;
	}
	//判断当前订单是第几桌支付并享受几折优惠
    function getTableDiscount($mer_id,$order_id,$now_order=array()){
	    //如果开启
        if (1 == C('config.is_open_merchant_foodshop_discount')) {
            $orderinfo = D('foodshop_order')->where(array('order_id'=>$order_id))->find();
            $thatday = date('Ymd',$orderinfo['create_time']);
            $time_start = strtotime($thatday);
            $time_end = $orderinfo['create_time'];

            $where['create_time'] = array('between',array($time_start,$time_end));
            $where['status'] = array('in',array(1,2,3,4));
            $where['mer_id'] = $mer_id;
            $order_index = D('foodshop_order')->where($where)->count();
            //判断是否同一时间支付预定
            $orders_samecount = D('foodshop_order')->where(array('create_time'=>$orderinfo['create_time']))->count();
            if($orders_samecount>1) {
                $orders_same =  D('foodshop_order')->where(array('create_time'=>$orderinfo['create_time']))->select();
                foreach($orders_same as $order){
                    if(empty($order['pay_time'])){
                        if($order['order_id'] == $order_id){
                            continue;
                        }
                        $order_index = $order_index - 1;
                    }
                }
            }
            $tmp = D('Merchant')->where(array('mer_id'=>$mer_id))->find();
            if($tmp['foodshop_tables_discount']!=''){
                $tmp['foodshop_tables_discount'] = substr($tmp['foodshop_tables_discount'], 0, -1);
                $discountArr= explode('|', $tmp['foodshop_tables_discount']);
                $tables = [];
                foreach($discountArr as $k => $v){
                    $v = explode(':',$v);
                    $tables[$k] = $v;
                }
                if(intval($order_index)>count($tables)){
                    $mer_discount =  $tmp['other_discount'];
                    $mer_scale =  $tmp['other_scale'];
                }else{
                    if($orderinfo['status']==0){
                        $count_index = $order_index;
                    }else{
                        $count_index = $order_index - 1;
                    }
                    $mer_discount =  $tables[$count_index][1];
                    $mer_scale =   $tables[$count_index][2];
                }
            }else{
                $mer_discount =  $tmp['other_discount'];
                $mer_scale =  $tmp['other_scale'];
            }
            $tmps['mer_discount'] = floatval($mer_discount / 10);
            $tmps['mer_scale'] = floatval($mer_scale);
           if($now_order){
               $can_discount_table_money = D('Foodshop_order')->count_price($now_order, 1);
               $save = array('mer_table_discount'=>$tmps['mer_discount']*10,'mer_table_scale'=>$tmps['mer_scale'],'can_discount_table_money'=>$can_discount_table_money);
               $tmps['can_discount_table_money'] = $can_discount_table_money;
           }else{
               $save = array('mer_table_discount'=>$tmps['mer_discount']*10,'mer_table_scale'=>$tmps['mer_scale']);
           }

            D('foodshop_order')->where(array('order_id'=>$order_id))->save($save);
//            if($order_index == 0){
//                $order_index = 1;
//            }
            if($orderinfo['status']==0) {
                $tmps['tables'] = $order_index+1;
            }else{
                $tmps['tables'] = $order_index;
            }
            return $tmps;
        }else{
            return false;
        }
    }
}
?>