<?php
class Merchant_store_shopModel extends Model
{

    /**
     * 根据条件获取商家列表
     * 
     * @param array $where            
     * @param number $limit            
     */
    public function get_list_by_option($where = array(), $is_wap = 1, $is_search = 0)
    {
        $deliver_type = isset($where['deliver_type']) ? $where['deliver_type'] : 'all';
        $order_str = isset($where['order']) ? $where['order'] : '';
        $lat = isset($where['lat']) ? $where['lat'] : 0;
        $long = isset($where['long']) ? $where['long'] : 0;
        $cat_id = isset($where['cat_id']) ? $where['cat_id'] : 0;
        $cat_fid = isset($where['cat_fid']) ? $where['cat_fid'] : 0;
		$limit	 = isset($where['limit']) ? $where['limit'] : 0;
        
        // $condition_where = "s.city_id='".C('config.now_city')."' AND s.have_meal=1 AND s.status=1 AND s.store_id=m.store_id";
        $condition_where = "s.status=1 AND s.store_id=m.store_id AND s.have_shop=1 AND ((m.is_close_shop=0 AND m.store_theme=1) OR m.store_theme=0)";
        if (C('config.store_shop_auth') == 1) {
            $condition_where .= " AND s.auth>2";
        }
		if ($where['store_ids']) {
			if(is_array($where['store_ids'])){
				$where['store_ids'] = implode(',',$where['store_ids']);
			}
            $condition_where .= " AND s.store_id IN (".$where['store_ids'].")";
        }
		if ($where['preference_status']) {
            $condition_where .= " AND m.preference_status=1";
        }
        if (isset($where['deliver_type_pc'])) {
            if ($where['deliver_type_pc'] == 0) {
                $condition_where .= " AND ((`m`.`delivery_range_type`=0 AND `m`.`deliver_type` IN (0, 1, 3, 4)  AND ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < `m`.`delivery_radius`*1000)";
                $condition_where .= " OR (`m`.`delivery_range_type`=1 AND MBRContains(PolygonFromText(`m`.`delivery_range_polygon`),PolygonFromText('Point({$long} {$lat})'))>0))";
            } elseif ($where['deliver_type_pc'] == 1) {
                $condition_where .= " AND ((`m`.`delivery_range_type`=0 AND `m`.`deliver_type` IN (0, 3)  AND ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < `m`.`delivery_radius`*1000)";
                $condition_where .= " OR (`m`.`delivery_range_type`=1 AND MBRContains(PolygonFromText(`m`.`delivery_range_polygon`),PolygonFromText('Point({$long} {$lat})'))>0))";
            } elseif ($where['deliver_type_pc'] == 2) {
                $condition_where .= " AND `m`.`deliver_type` IN (2, 3, 4)";
            } elseif ($where['deliver_type_pc'] == 5) {
                $condition_where .= " AND `m`.`deliver_type`=5";
            } else {
                $condition_where .= " AND (`m`.`deliver_type` IN (2, 3, 4, 5) OR (`m`.`delivery_range_type`=0 AND `m`.`deliver_type` IN (0, 1) AND ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < `m`.`delivery_radius`*1000)";
                $condition_where .= " OR (`m`.`delivery_range_type`=1 AND MBRContains(PolygonFromText(`m`.`delivery_range_polygon`),PolygonFromText('Point({$long} {$lat})'))>0))";
            }
        } else {
            if ($deliver_type == 'delivery') {
                // $condition_where .= " AND `m`.`deliver_type`<>2";
                $condition_where .= " AND ((`m`.`delivery_range_type`=0 AND `m`.`deliver_type`<>2 AND ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < `m`.`delivery_radius`*1000)";
                $condition_where .= " OR (`m`.`delivery_range_type`=1 AND MBRContains(PolygonFromText(`m`.`delivery_range_polygon`),PolygonFromText('Point({$long} {$lat})'))>0))";
            } elseif ($deliver_type == 'pick') {
                $condition_where .= " AND `m`.`deliver_type` IN (2, 3, 4)";
            } elseif ($deliver_type == 'offline') {
                $condition_where .= " AND `m`.`open_offline`='1'";
            } else {
                $condition_where .= " AND (`m`.`deliver_type` IN (2, 3, 4, 5) OR (`m`.`delivery_range_type`=0 AND `m`.`deliver_type` IN (0, 1) AND ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < `m`.`delivery_radius`*1000)";
                $condition_where .= " OR (`m`.`delivery_range_type`=1 AND MBRContains(PolygonFromText(`m`.`delivery_range_polygon`),PolygonFromText('Point({$long} {$lat})'))>0))";
            }
        }
        
        if (isset($where['key']) && $where['key']) {
			if ($is_search) {
				if($where['search_type'] == 'barcode'){
					$condition_where .= " AND (`g`.`number`='{$where['key']}')";
				}else{
					$condition_where .= " AND (`s`.`name` LIKE '%{$where['key']}%' OR `g`.`name` LIKE '%{$where['key']}%')";
				}
			} else {
				$condition_where .= " AND `s`.`name` LIKE '%{$where['key']}%'";
			}
        }
        
        if ($cat_id) {
            $category = M('Shop_category')->where(array('cat_id' => $cat_id))->find();
        } elseif ($cat_fid) {
            $category = M('Shop_category')->where(array('cat_id' => $cat_fid))->find();
        }
        $show_method = isset($category) && $category ? $category['show_method'] : (isset($where['show_method']) ? $where['show_method'] : 2);
        
        $condition_field = 's.*, m.*, mm.isverify,mm.logo as `merchant_logo`';
        $order = '';
        $time = date('H:i:s');
        if ($show_method == 2) { // 靠后显示
            $time = date("H:i:s");
            $condition_field .= ",(CASE
	        WHEN (`s`.`open_1`='00:00:00' and `s`.`open_2`='00:00:00' and `s`.`open_3`='00:00:00' and `s`.`close_1`='00:00:00' and `s`.`close_2`='00:00:00' and `s`.`close_3`='00:00:00') then 2
	        WHEN (`m`.`is_reserve` = 1) then 1
	        WHEN ((`s`.`open_1`<'$time' and `s`.`close_1`>'$time') OR (`s`.`open_2`<'$time' and `s`.`close_2`>'$time') OR (`s`.`open_3`<'$time' and `s`.`close_3`>'$time')) then 2
	        ELSE 0
	        END) as `t_sort`";
            $order .= '`t_sort` DESC, ';
            $order .= '`m`.`is_close` ASC, ';
        } elseif ($show_method == 0) { // 不显示
            $condition_where .= " AND (";
            $condition_where .= "(`s`.`open_1`='00:00:00' AND `s`.`close_1`='00:00:00')";
            $condition_where .= " OR ((`s`.`open_1`<'$time' AND `s`.`close_1`>'$time')";
            $condition_where .= " OR (`s`.`open_2`<'$time' AND `s`.`close_2`>'$time')";
            $condition_where .= " OR (`s`.`open_3`<'$time' AND `s`.`close_3`>'$time')))";
            $condition_where .= " AND `m`.`is_close`= 0";
        }
        if ($order_str == 'basic_price') {
            if (C('config.basic_price')) {
                $condition_field .= ",(CASE
			WHEN ((`m`.`deliver_type`=0 OR `m`.`deliver_type`=3) AND `m`.`s_basic_price` > 0) then `m`.`s_basic_price`
			WHEN ((`m`.`deliver_type`=0 OR `m`.`deliver_type`=3) AND `m`.`s_basic_price`=0) then " . C('config.basic_price') . "
			ELSE `m`.`basic_price`
			END) as `t_basic_price`";
            } else {
                $condition_field .= ",(CASE
			WHEN ((`m`.`deliver_type`=0 OR `m`.`deliver_type`=3) AND `m`.`s_basic_price` > 0) then `m`.`s_basic_price`
			WHEN ((`m`.`deliver_type`=0 OR `m`.`deliver_type`=3) AND `m`.`s_basic_price`=0) then `m`.`basic_price`
			ELSE `m`.`basic_price`
			END) as `t_basic_price`";
            }
			//此处商家配送，多时间段不同起送价时会有排序问题。筛选是按照 basic_price标准起送价，但是后续在展示时使用了时间段的起送价。
        }
        
        // 排序
        switch ($order_str) {
            case 'score_mean':
                $order .= '`m`.`score_mean` DESC,`m`.`sort` DESC';
                break;
            case 'create_time':
                $order .= '`m`.`create_time` DESC,`m`.`sort` DESC';
                break;
            case 'sale_count':
                $order .= '`m`.`sale_count` DESC,`m`.`sort` DESC';
                break;
            case 'send_time':
                $order .= '`m`.`sort_time` ASC,`m`.`sort` DESC';
                break;
            case 'basic_price':
                $order .= 't_basic_price ASC,`m`.`sort` DESC';
                break;
            case 'delivery_fee':
                $order .= '`m`.`delivery_fee` ASC,`m`.`sort` DESC';
                break;
            case 'store_id':
                $order .= '`s`.`store_id` ASC, `m`.`sort` DESC';
                break;
            case 'juli': // 智能排序
                $condition_field .= ", ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli";
                $order .= '`m`.`sort` DESC, juli ASC';
                break;
			case 'only_juli': // 智能排序
                $condition_field .= ", ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli";
                $order .= 'juli ASC';
                break;
			case 'preference_sort':
                $order .= '`m`.`preference_sort` DESC,`m`.`sort` DESC';
                break;
            default:
                $condition_field .= ", ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli";
                $order .= '`m`.`sort` DESC, juli ASC';
                break;
        }
        
        //根据分类查找对应的店铺ID
        if ($cat_fid || $cat_id) {
            if ($cat_fid && $cat_id) {
                $relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid, 'cat_id' => $cat_id))->select();
            } elseif ($cat_fid) {
                $relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid))->select();
            } else {
                $relation = D('Shop_category_relation')->where(array('cat_id' => $cat_id))->select();
            }
            $store_ids = array();
            foreach ($relation as $r) {
                if (! in_array($r['store_id'], $store_ids)) {
                    $store_ids[] = $r['store_id'];
                }
            }
            if ($store_ids) {
                $condition_where .= ' AND s.store_id IN (' . implode(',', $store_ids) . ')';
            } else {
                return array(
                    'shop_list' => null,
                    'pagebar' => null,
                    'total' => 0,
                    'next_page' => 0
                );
            }
        }
        
		//没有告知取多少条，则统计。否则不统计，影响效率
		if(empty($limit)){
			$sql_count = "SELECT count(distinct(`s`.store_id)) as count FROM " . C('DB_PREFIX') . "merchant_store AS s";
			$sql_count .= " INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id";
			$sql_count .= " LEFT JOIN " . C('DB_PREFIX') . "merchant AS mm ON s.mer_id=mm.mer_id";
			if ($is_search) {
				$sql_count .= " LEFT JOIN " . C('DB_PREFIX') . "shop_goods AS g ON g.store_id=m.store_id";
				$sql_count .= " WHERE {$condition_where} AND mm.status = 1";
			} else {
				$sql_count .= " WHERE {$condition_where} AND mm.status = 1";
			}
			$count = $this->query($sql_count);
			$total = isset($count[0]['count']) ? $count[0]['count'] : 0;
			if ($is_wap == 1) {
				$page = isset($where['page']) ? intval($where['page']) : 1;
				$pagesize = 20;
				$totalPage = ceil($total / $pagesize);
				$pagetotal = $totalPage;
				$star = $pagesize * ($page - 1);
				$return['has_more'] = $totalPage > $page ? true : false;
			} elseif ($is_wap == 2) {
				$page = isset($where['page']) ? intval($where['page']) : 1;
				$pagesize = 10;
				$totalPage = ceil($total / $pagesize);
				
				$star = $pagesize * ($page - 1);
				$return['next_page'] = $totalPage > $page ? intval($page + 1) : 0;
			} elseif ($is_wap == 3) {
				$page = isset($where['page']) ? intval($where['page']) : 1;
				$pagesize = 10;
				$pagetotal = ceil($total / $pagesize);
				$star = $pagesize * ($page - 1);
				$return['has_more'] = $pagetotal > $page ? true : false;
			}  elseif($is_wap == 4){
				$page = isset($where['page']) ? intval($where['page']) : 1;
				$pagesize = 10;
				$pagetotal = ceil($total / $pagesize);
				$star =$page;
				$return['has_more'] = $pagetotal > $page ? true : false;
			} else if ($is_wap == 5) {
				$page = isset($where['page']) ? intval($where['page']) : 1;
				$pagesize = 10;
				$totalPage = ceil($total / $pagesize);
				$pagetotal = $totalPage;
				$star = $pagesize * ($page - 1);
				$return['has_more'] = $totalPage > $page ? true : false;
			}else {
				import('@.ORG.group_page');
				$p = new Page($total, C('config.meal_page_row'), C('config.meal_page_val'));
				$star = $p->firstRow;
				$pagesize = $p->listRows;
			}
        }else{
			$star = 0;
			$pagesize = $limit;
		}
		
        if ($is_search) {
            $sql = "SELECT {$condition_field} FROM " . C('DB_PREFIX') . "merchant_store AS s";
            $sql .= " INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS m ON m.store_id=s.store_id";
            $sql .= " LEFT JOIN " . C('DB_PREFIX') . "merchant AS mm ON mm.mer_id = s.mer_id";
            $sql .= " LEFT JOIN " . C('DB_PREFIX') . "shop_goods AS g ON g.store_id=m.store_id";
            $sql .= " WHERE {$condition_where} AND mm.status = 1 GROUP BY `m`.store_id";
            $sql .= " ORDER BY {$order}";
            $sql .= " LIMIT {$star}, {$pagesize}";
            
            $result = $this->query($sql);
            $res = array();
            $store_ids = array();
            foreach ($result as $r) {
                if (! in_array($r['store_id'], $store_ids)) {
                    $store_ids[] = $r['store_id'];
                }
                $res[$r['store_id']] = $r;
            }
            if (empty($store_ids)) {
                return array(
                    'shop_list' => null,
                    'pagebar' => null,
                    'total' => 0,
                    'next_page' => 0
                );
            }
			$goods_image_class = new goods_image();
			
            $good_where = array('status' => 1, 'store_id' => array('in', $store_ids), 'name' => array('like', '%' . $where['key'] . '%'));
			if($where['search_type'] == 'barcode'){
				unset($good_where['name']);
				$good_where['number'] = $where['key'];
			}
            $goods_list = D('Shop_goods')->field('name, goods_id, sell_count, price, sort_id, unit, store_id,image')->where($good_where)->select();
            
			$sort_goods_list = array();
            $sort_ids = array();
            foreach ($goods_list as $goods) {
                if (isset($sort_goods_list[$goods['sort_id']])) {
                    $sort_goods_list[$goods['sort_id']][] = $goods;
                } else {
                    $sort_goods_list[$goods['sort_id']] = array($goods);
                }
                if (!in_array($goods['sort_id'], $sort_ids)) {
                    $sort_ids[] = $goods['sort_id'];
                }
            }
            $sort_list = D('Shop_goods_sort')->field(true)->where(array('sort_id' => array('in', $sort_ids)))->select();
            $today = date('w');
            $goods_list = array();
            foreach ($sort_list as $st) {
                if (!empty($st['is_weekshow'])) {
                    $week_arr = explode(',', $st['week']);
                    if (!in_array($today, $week_arr)) {
                        continue;
                    }
                }
                $goods_list = array_merge($goods_list, $sort_goods_list[$st['sort_id']]);
            }
            foreach ($goods_list as $goods) {
                $goods['price'] = floatval($goods['price']);
                $goods['search_name'] = str_replace($where['key'], '<font color="#06c1ae">' . $where['key'] . '</font>', $goods['name']);
				
				$images = $goods_image_class->get_allImage_by_path($goods['image']);
				$goods['image'] = $images ? array_shift($images) : '';
				$goods['image'] = $goods['image']['s_image'];
				
				$tmpGoodNameStr = str_replace($where['key'], '&^&' . $where['key'] . '&^&', $goods['name']);
				$tmpGoodNameArr = explode('&^&',$tmpGoodNameStr);
				$goods['search_name_arr'] = array();
				foreach($tmpGoodNameArr as $tmpValue){
					if(!empty($tmpValue)){
						$goods['search_name_arr'][] = array(
							'value'   => $tmpValue,
							'is_high' => $tmpValue == $where['key'] ? true : false
						);
					}
				}
				
                if (isset($res[$goods['store_id']]['goods_list'])) {
                    $res[$goods['store_id']]['goods_list'][] = $goods;
                } else {
                    $res[$goods['store_id']]['goods_list'] = array($goods);
                }
            }
        } else {
            $sql = "SELECT {$condition_field} FROM " . C('DB_PREFIX') . "merchant_store AS s";
            $sql .= " INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS m ON m.store_id=s.store_id";
            $sql .= " LEFT JOIN " . C('DB_PREFIX') . "merchant AS mm ON mm.mer_id = s.mer_id";
            $sql .= " WHERE {$condition_where} AND mm.status = 1";
            $sql .= " ORDER BY {$order}";
            $sql .= " LIMIT {$star}, {$pagesize}";
			fdump($sql,'sql');
            $res = $this->query($sql);
        }
        $ids = array();
        $store_ids = array();
        $latLng = array();
        $list = array();
        
        foreach ($res as $r) {
            if (! in_array($r['circle_id'], $ids)) {
                $ids[] = $r['circle_id'];
            }
            if (! in_array($r['store_id'], $store_ids)) {
                $store_ids[] = $r['store_id'];
            }
            $latLng[] = $r['lat'] . ',' . $r['long'];
        }
        
//         $discounts = D('Shop_discount')->get_discount_byids($store_ids);
        $temp = array();
        if ($ids) {
            $areas = M("Area")->where(array('area_id' => array('in', $ids)))->select();
            foreach ($areas as $a) {
                $temp[$a['area_id']] = $a;
            }
        }
        
        $newLatLong = array();
        if (C('config.is_riding_distance')) {
            $url = 'http://api.map.baidu.com/routematrix/v2/riding?destinations=' . implode('|', $latLng) . '&origins=' . $lat . ',' . $long . '&ak=' . C('config.baidu_map_ak') . '&output=json';
            import('ORG.Net.Http');
            $http = new Http();
            $result = $http->curlGet($url);
            if ($result) {
                $result = json_decode($result, true);
                if ($result['status'] == 0) {
                    $newLatLong = $result['result'];
                }
            }
        }
        
        import('@.ORG.longlat');
        $longlat_class = new longlat();
        $store_image_class = new store_image();
        
        $shopDiscountDataBase = D('Shop_discount');
        $cardNewCouponDataBase = D('Card_new_coupon');
        $deliverSetDB = D('Deliver_set');
        $shopGoodsDB = D('Shop_goods');
        $shopGoodsSortDB = D('Shop_goods_sort');
        foreach ($res as &$v) {
            $v['isDiscountGoods'] = $shopGoodsDB->getDiscountGoods($v['store_id']);
            $v['isdiscountsort'] = $shopGoodsSortDB->checkShopDiscount($v['store_id']);
            $v['url'] = C('config.site_url') . '/shop/' . $v['store_id'] . '.html';
            $v['area_name'] = isset($temp[$v['circle_id']]) ? $temp[$v['circle_id']]['area_name'] : '';
            $images = $store_image_class->get_allImage_by_path($v['pic_info']);
            $v['image'] = $images ? array_shift($images) : '';
			
			if($where['key']){
				$tmpStoreNameStr = str_replace($where['key'], '&^&' . $where['key'] . '&^&', $v['name']);
				$tmpStoreNameArr = explode('&^&',$tmpStoreNameStr);
				$v['name_arr'] = array();
				foreach($tmpStoreNameArr as $tmpValue){
					if(!empty($tmpValue)){
						$v['name_arr'][] = array(
							'value'   => $tmpValue,
							'is_high' => $tmpValue == $where['key'] ? true : false
						);
					}
				}
			}
			
			if($v['merchant_logo']){
				$v['merchant_logo'] = C('config.site_url') . $v['merchant_logo'];
			}
			
            $v['mean_money'] = floatval($v['mean_money']);
            $v['wap_url'] = U('Shop/shop', array('mer_id' => $v['mer_id'], 'store_id' => $v['store_id']));
            $v['deliver'] = $v['deliver_type'] == 2 ? false : true;
            
            if ($v['send_time_type'] == 0) {
                $v['send_time'] = $v['sort_time'];
            } elseif ($v['send_time_type'] == 1) {
                $v['send_time'] = floatval(round($v['sort_time'] / 60, 2));
            } elseif ($v['send_time_type'] == 2) {
                $v['send_time'] = floatval(round($v['sort_time'] / 1440, 2));
            } elseif ($v['send_time_type'] == 3) {
                $v['send_time'] = floatval(round($v['sort_time'] / 10080, 2));
            } elseif ($v['send_time_type'] == 4) {
                $v['send_time'] = floatval(round($v['sort_time'] / 43200, 2));
            }
            
            
            if (empty($newLatLong)) {
                if ($v['juli']) {
                    $v['range'] = getRange($v['juli']);
                    $jl = $v['juli'];
                } else {
                    $location2 = $longlat_class->gpsToBaidu($v['lat'], $v['long']); // 转换腾讯坐标到百度坐标
                    $jl = getDistance($location2['lat'], $location2['lng'], $lat, $long);
                    $v['range'] = getRange($jl);
                }
            } else {
                $tLatLng = array_shift($newLatLong);
                $v['range'] = $tLatLng['distance']['text'];
                $jl = $tLatLng['distance']['value'];
            }
            if (in_array($v['deliver_type'], array(3, 4)) && $jl > $v['delivery_radius'] * 1000) {
                $v['deliver'] = 0;
            }
            
            $v['state'] = 0; // 根据营业时间判断
            if ($this->checkTime($v)) {
                $v['state'] = 1;
            }
            $time = time();
            
            $discounts = $shopDiscountDataBase->getDiscounts($v['mer_id'], $v['store_id']);
            $v['merchant_coupon'] = $cardNewCouponDataBase->is_shop_coupon($v['store_id'], $v['mer_id']);
            $v['system_discount'] = isset($discounts[0]) ? $discounts[0] : null;
            $v['merchant_discount'] = isset($discounts[$v['store_id']]) ? $discounts[$v['store_id']] : null;
            
            
            $is_have_two_time = 0; // 是否是第二时段的配送显示
            $deliverReturn = $deliverSetDB->getDeliverInfo($v);
            
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
            
            $v['delivery_fee'] = $delivery_fee;

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
                            $v['delivery_fee'] = $delivery_fee2;
                            $selectDeliverTime = 2;
                            $is_have_two_time = 1;
                        } else {
                            $stop_time2 += 86400;
                            if ($start_time2 <= $time && $time <= $stop_time2) {
                                $v['delivery_fee'] = $delivery_fee2;
                                $selectDeliverTime = 2;
                                $is_have_two_time = 1;
                            }
                        }
                    } elseif ($start_time2 <= $time && $time <= $stop_time2) {
                        $v['delivery_fee'] = $delivery_fee2;
                        $selectDeliverTime = 2;
                        $is_have_two_time = 1;
                    }
                }
                
                if (! ($start_time3 == $stop_time3 && $start_time2 == '00:00:00')) {
                    $start_time3 = strtotime(date('Y-m-d') . ' ' . $start_time3);
                    $stop_time3 = strtotime(date('Y-m-d') . ' ' . $stop_time3);
                    if ($start_time3 > $stop_time3) {
                        if (strtotime(date('Y-m-d')) <= $time && $time <= $stop_time3) {
                            $v['delivery_fee'] = $delivery_fee3;
                            $selectDeliverTime = 3;
                            $is_have_two_time = 1;
                        } else {
                            $stop_time3 += 86400;
                            if ($start_time3 <= $time && $time <= $stop_time3) {
                                $v['delivery_fee'] = $delivery_fee3;
                                $selectDeliverTime = 3;
                                $is_have_two_time = 1;
                            }
                        }
                    } elseif ($start_time3 <= $time && $time <= $stop_time3) {
                        $v['delivery_fee'] = $delivery_fee3;
                        $selectDeliverTime = 3;
                        $is_have_two_time = 1;
                    }
                }
            }else{
                $selectDeliverTime = 1;
            }
            $set = D('Deliver_set')->field(true)->where(array('area_id' => $v['area_id'], 'status' => 1))->find();
            if (empty($set)) {
                $set = D('Deliver_set')->field(true)->where(array('area_id' => $v['city_id'], 'status' => 1))->find();
                if (empty($set)) {
                    $set = D('Deliver_set')->field(true)->where(array('area_id' => $v['province_id'], 'status' => 1))->find();
                }
            }
            if($selectDeliverTime==1){
                if ($v['deliver_type'] == 0 || $v['deliver_type'] == 3) {
                    $v['basic_price'] = floatval($v['s_basic_price1']) ? floatval($v['s_basic_price1']) : (floatval($v['s_basic_price']) ? floatval($v['s_basic_price']) : (floatval($set['basic_price1']) ? floatval($set['basic_price1']) : (floatval(C('config.basic_price1')) ? floatval(C('config.basic_price1')):(C('config.basic_price') ? floatval(C('config.basic_price')) : floatval($v['basic_price'])))));//起送价
                    $v['extra_price'] = floatval($v['s_extra_price']) ? floatval($v['s_extra_price']) : floatval(C('config.extra_price'));
                } else {
                    $v['basic_price'] = floatval($v['basic_price1'])?floatval($v['basic_price1']):floatval($v['basic_price']);//起送价
                    $v['extra_price'] = floatval($v['extra_price']);//不满起送价另付金额支持配送
                }
            }elseif($selectDeliverTime==2){
                if ($v['deliver_type'] == 0 || $v['deliver_type'] == 3) {
                    $v['basic_price'] = floatval($v['s_basic_price2']) ? floatval($v['s_basic_price2']) : (floatval($v['s_basic_price']) ? floatval($v['s_basic_price']) : (floatval($set['basic_price2']) ? floatval($set['basic_price2']) : (floatval(C('config.basic_price2')) ? floatval(C('config.basic_price2')):(C('config.basic_price') ? floatval(C('config.basic_price')) : floatval($v['basic_price'])))));//起送价
                    $v['extra_price'] = floatval($v['s_extra_price']) ? floatval($v['s_extra_price']) : floatval(C('config.extra_price'));
                } else {
                    $v['basic_price'] = floatval($v['basic_price2'])?floatval($v['basic_price2']):floatval($v['basic_price']);//起送价
                    $v['extra_price'] = floatval($v['extra_price']);//不满起送价另付金额支持配送
                }

            }elseif($selectDeliverTime==3){
                if ($v['deliver_type'] == 0 || $v['deliver_type'] == 3) {
                    $v['basic_price'] = floatval($v['s_basic_price3']) && $v['s_is_open_own'] ? floatval($v['s_basic_price3']) : (floatval($v['s_basic_price']) ? floatval($v['s_basic_price']) : (floatval($set['basic_price3']) ? floatval($set['basic_price3']) : (floatval(C('config.basic_price3')) ? floatval(C('config.basic_price3')):(C('config.basic_price') ? floatval(C('config.basic_price')) : floatval($v['basic_price'])))));//起送价
                    $v['extra_price'] = floatval($v['s_extra_price']) ? floatval($v['s_extra_price']) : floatval(C('config.extra_price'));
                } else {
                    $v['basic_price'] = floatval($v['basic_price3'])?floatval($v['basic_price3']):floatval($v['basic_price']);//起送价
                    $v['extra_price'] = floatval($v['extra_price']);//不满起送价另付金额支持配送
                }
            }else{
                if ($v['deliver_type'] == 0 || $v['deliver_type'] == 3) {
                    $v['basic_price'] = floatval($v['s_basic_price']) ? floatval($v['s_basic_price']) : (C('config.basic_price') ? floatval(C('config.basic_price')) : floatval($v['basic_price']));
                    $v['extra_price'] = floatval($v['s_extra_price']) ? floatval($v['s_extra_price']) : floatval(C('config.extra_price'));
                } else {
                    $v['basic_price'] = floatval($v['basic_price']);//起送价
                    $v['extra_price'] = floatval($v['extra_price']);//不满起送价另付金额支持配送
                }
            }
            
//            if ($v['deliver_type'] == 0 || $v['deliver_type'] == 3) {
//                $v['basic_price'] = floatval($v['s_basic_price']) ? floatval($v['s_basic_price']) : (C('config.basic_price') ? floatval(C('config.basic_price')) : floatval($v['basic_price']));
//            }
            
            $v['is_have_two_time'] = $is_have_two_time;
            if($v['s_is_open_virtual']==1){
                $v['delivery_fee'] = $v['virtual_delivery_fee'];
            }
        }
        $return['shop_list'] = $res;
        $return['total'] = $total;
        $return['pagetotal'] = $pagetotal;
        if (! $is_wap) {
            $return['totalPage'] = $p->totalPage;
            $return['pagebar'] = $p->show();
        }
        return $return;
    }

	/**
	 * 根据条件获取商家列表
	 * @param array $where
	 * @param number $limit
	 */
	public function get_list_by_ids($ids, $params = array(),$limit)
	{
		$lat = isset($params['lat']) ? $params['lat'] : 0;
		$long = isset($params['long']) ? $params['long'] : 0;

		if (empty($ids)) return null;

        $time = date("H:i:s");
        $condition_field = 's.*,m.*';
        $order = '';
        $time = date("H:i:s");
        $condition_field .= ",(CASE
	        WHEN (`s`.`open_1`='00:00:00' and `s`.`open_2`='00:00:00' and `s`.`open_3`='00:00:00' and `s`.`close_1`='00:00:00' and `s`.`close_2`='00:00:00' and `s`.`close_3`='00:00:00') then 2
	        WHEN (`m`.`is_reserve` = 1) then 1
	        WHEN ((`s`.`open_1`<'$time' and `s`.`close_1`>'$time') OR (`s`.`open_2`<'$time' and `s`.`close_2`>'$time') OR (`s`.`open_3`<'$time' and `s`.`close_3`>'$time')) then 2
	        ELSE 0
	        END) as `t_sort`";
        $order .= '`t_sort` DESC, ';
        $order .= '`m`.`is_close` ASC ';

		$sql = "SELECT {$condition_field} FROM "  . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id WHERE m.store_id IN ({$ids})"."ORDER BY".$order."limit {$limit}";
		$res = $this->query($sql);
// 		echo $mod->_sql();die;
		// dump($mod);
		$ids = array();
		$store_ids = array();
		foreach ($res as $r) {
			if (!in_array($r['circle_id'], $ids)) {
				$ids[] = $r['circle_id'];
			}
			if (!in_array($r['store_id'], $store_ids)) {
				$store_ids[] = $r['store_id'];
			}
		}
		
		$temp = array();
		if ($ids) {
			$areas = M("Area")->where(array('area_id' => array('in', $ids)))->select();
			foreach ($areas as $a) {
				$temp[$a['area_id']] = $a;
			}
		}

		import('@.ORG.longlat');
		$longlat_class = new longlat();
		$store_image_class = new store_image();
		$list = array();
		
		$shopDiscountDataBase = D('Shop_discount');
		$deliverSetDB = D('Deliver_set');
		foreach ($res as $v) {
			$v['url'] = C('config.site_url') . '/shop/' . $v['store_id'] . '.html';
			$v['area_name'] = isset($temp[$v['circle_id']]) ? $temp[$v['circle_id']]['area_name'] : '';
			$images = $store_image_class->get_allImage_by_path($v['pic_info']);
			$v['image'] = $images ? array_shift($images) : array();
			$v['mean_money'] = floatval($v['mean_money']);
			$v['wap_url'] = U('Shop/shop', array('mer_id' => $v['mer_id'], 'store_id' => $v['store_id']));
			$v['deliver'] = $v['deliver_type'] == 2 ? false : true;
			if ($v['send_time_type'] == 0) {
			    $v['send_time'] = $v['sort_time'];
			} elseif ($v['send_time_type'] == 1) {
			    $v['send_time'] = floatval(round($v['sort_time'] / 60, 2));
			} elseif ($v['send_time_type'] == 2) {
			    $v['send_time'] = floatval(round($v['sort_time'] / 1440, 2));
			} elseif ($v['send_time_type'] == 3) {
			    $v['send_time'] = floatval(round($v['sort_time'] / 10080, 2));
			} elseif ($v['send_time_type'] == 4) {
			    $v['send_time'] = floatval(round($v['sort_time'] / 43200, 2));
			}
			$location2 = $longlat_class->gpsToBaidu($v['lat'], $v['long']);//转换腾讯坐标到百度坐标
			$jl = getDistance($location2['lat'], $location2['lng'], $lat, $long);
			$v['range'] = getRange($jl);

			if (in_array($v['deliver_type'], array(3, 4)) && $jl > $v['delivery_radius'] * 1000) {
				$v['deliver'] = 0;
			}

			$v['state'] = 0;//根据营业时间判断
			$time = time();
			if ($this->checkTime($v)) {
			    $v['state'] = 1;
			}

			$discounts = $shopDiscountDataBase->getDiscounts($v['mer_id'], $v['store_id']);
			
			$v['system_discount'] = isset($discounts[0]) ? $discounts[0] : null;
			$v['merchant_discount'] = isset($discounts[$v['store_id']]) ? $discounts[$v['store_id']] : null;

			$is_have_two_time = 0; // 是否是第二时段的配送显示
			$deliverReturn = $deliverSetDB->getDeliverInfo($v);
			
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
			
			$v['delivery_fee'] = $delivery_fee;
			
			if (! ($start_time == $stop_time && $start_time == '00:00:00')) {
			    $start_time = strtotime(date('Y-m-d') . ' ' . $start_time);
			    $stop_time = strtotime(date('Y-m-d') . ' ' . $stop_time);
			    if ($start_time > $stop_time) {
			        $stop_time += 86400;
			    }
			    if (! ($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
			        $start_time2 = strtotime(date('Y-m-d') . ' ' . $start_time2);
			        $stop_time2 = strtotime(date('Y-m-d') . ' ' . $stop_time2);
			        if ($start_time2 > $stop_time2) {
			            if (strtotime(date('Y-m-d')) <= $time && $time <= $stop_time2) {
			                $v['delivery_fee'] = $delivery_fee2;
			                $is_have_two_time = 1;
			            } else {
			                $stop_time2 += 86400;
			                if ($start_time2 <= $time && $time <= $stop_time2) {
			                    $v['delivery_fee'] = $delivery_fee2;
			                    $is_have_two_time = 1;
			                }
			            }
			        } elseif ($start_time2 <= $time && $time <= $stop_time2) {
			            $v['delivery_fee'] = $delivery_fee2;
			            $is_have_two_time = 1;
			        }
			    }
			    
			    if (! ($start_time3 == $stop_time3 && $start_time2 == '00:00:00')) {
			        $start_time3 = strtotime(date('Y-m-d') . ' ' . $start_time3);
			        $stop_time3 = strtotime(date('Y-m-d') . ' ' . $stop_time3);
			        if ($start_time3 > $stop_time3) {
			            if (strtotime(date('Y-m-d')) <= $time && $time <= $stop_time3) {
			                $v['delivery_fee'] = $delivery_fee3;
			                $is_have_two_time = 1;
			            } else {
			                $stop_time3 += 86400;
			                if ($start_time3 <= $time && $time <= $stop_time3) {
			                    $v['delivery_fee'] = $delivery_fee3;
			                    $is_have_two_time = 1;
			                }
			            }
			        } elseif ($start_time3 <= $time && $time <= $stop_time3) {
			            $v['delivery_fee'] = $delivery_fee3;
			            $is_have_two_time = 1;
			        }
			    }
			}
			
			if ($v['deliver_type'] == 0 || $v['deliver_type'] == 3) {
			    $v['basic_price'] = floatval($v['s_basic_price']) ? floatval($v['s_basic_price']) : (C('config.basic_price') ? floatval(C('config.basic_price')) : floatval($v['basic_price']));
			}

			$v['is_have_two_time'] = $is_have_two_time;
            if($v['s_is_open_virtual']==1){
                $v['delivery_fee'] = $v['virtual_delivery_fee'];
            }
			$list[$v['store_id']] = $v;
		}
		$return['shop_list'] = $list;
		return $return;
	}



	public function get_qrcode($id){
		$condition_store['store_id'] = $id;
		$now_store = $this->field('`store_id`,`qrcode_id`')->where($condition_store)->find();
		if(empty($now_store)){
			return false;
		}
		return $now_store;
	}
	public function save_qrcode($id,$qrcode_id){
		$condition_store['store_id'] = $id;
		$data_store['qrcode_id'] = $qrcode_id;
		if($this->where($condition_store)->data($data_store)->save()){
			return(array('error_code'=>false));
		}else{
			return(array('error_code'=>true,'msg'=>'保存二维码至'.C('config.group_alias_name').'失败！请重试。'));
		}
	}
	public function del_qrcode($id){
		$condition_store['store_id'] = $id;
		$data_store['qrcode_id'] = '';
		if($this->where($condition_store)->data($data_store)->save()){
			return(array('error_code'=>false));
		}else{
			return(array('error_code'=>true,'msg'=>'保存二维码至'.C('config.group_alias_name').'失败！请重试。'));
		}
	}

	public function get_store_by_search($where)
	{
		$cat_id = isset($where['cat_id']) ? $where['cat_id'] : 0;
		$cat_fid = isset($where['cat_fid']) ? $where['cat_fid'] : 0;


// 		$condition_where = "s.city_id='" . C('config.now_city') . "' AND s.status=1 AND s.store_id=m.store_id AND s.have_shop=1";
		$condition_where = "s.status=1 AND s.store_id=m.store_id AND s.have_shop=1 AND m.store_theme=1 AND ((m.is_close_shop=0 AND m.store_theme=1) OR m.store_theme=0)";
		if (C('config.store_shop_auth') == 1) {
			$condition_where .= " AND s.auth>2";
		}
		if (isset($where['key']) && $where['key']) {
			$condition_where .= " AND `s`.`name` LIKE '%{$where['key']}%'";
		}

		if ($cat_fid || $cat_id) {
			if ($cat_fid && $cat_id) {
				$relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid, 'cat_id' => $cat_id))->select();
			} elseif ($cat_fid) {
				$relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid))->select();
			} else {
				$relation = D('Shop_category_relation')->where(array('cat_id' => $cat_id))->select();
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
				return array('shop_list' => null, 'pagebar' => null, 'total' => 0, 'next_page' => 0);
			}
		}


		$sql_count = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id WHERE {$condition_where}";
		$count = $this->query($sql_count);
		$total = isset($count[0]['count']) ? $count[0]['count'] : 0;

		$page = isset($where['page']) ? intval($where['page']) : 1;

		$pagesize = 10;
		$totalPage = ceil($total / $pagesize);
		$star = $pagesize * ($page - 1);
		$return['next_page'] = $totalPage > $page ? intval($page + 1) : 0;
		$return['total_page'] = $totalPage;
		$return['total'] = $total;


		$sql = "SELECT `s`.`name`, `s`.`pic_info`, `s`.`store_id`, `m`.`score_mean` FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id WHERE {$condition_where} LIMIT {$star}, {$pagesize}";
		$res = $this->query($sql);

		$store_ids = array();
		$store_list = array();
		$store_image_class = new store_image();
		foreach ($res as $v) {
			$v['url'] = U('Mall/store', array('store_id' => $v['store_id']));//C('config.site_url') . '/shop/' . $v['store_id'] . '.html';
			$images = $store_image_class->get_allImage_by_path($v['pic_info']);
			$v['image'] = $images ? array_shift($images) : array();
			$v['goods_count'] = 0;
			$store_ids[] = $v['store_id'];
			$store_list[$v['store_id']] = $v;
		}

		$goods_list = D('Shop_goods')->field('store_id, count(1) as cnt')->where(array('store_id' => array('in', $store_ids), 'status' => 1))->group('store_id')->select();
		foreach ($goods_list as $g) {
			if (isset($store_list[$g['store_id']])) {
				$store_list[$g['store_id']]['goods_count'] = $g['cnt'];
			}
		}
		$return['store_list'] = array_values($store_list);
		return $return;
	}
	
	
	
    /**
     * 根据条件获取快店店铺列表
     * @param array $where array(
     *  'order' => 排序，可选值（只能填写一个）['store_id'(默认)，'score_mean'(好评)，'permoney'(人均消费)]
     *  'cat_id' => 分类ID
     *  'cat_fid' => 父分类ID
     *  'area_id' => 区域ID
     *  'circle_id' => 商圈ID
     *  'key' => 搜索的关键词
     * );
     * @param number $isverify (-1 ：不筛选，0：非签约商家，1：签约商家)
     */
    public function getStores($where = array(), $isverify = -1)
    {
        $order_str = isset($where['order']) ? $where['order'] : 'juli';
        $cat_id = isset($where['cat_id']) ? $where['cat_id'] : 0;
        $cat_fid = isset($where['cat_fid']) ? $where['cat_fid'] : 0;
        $area_id = isset($where['area_id']) ? $where['area_id'] : 0;
        $circle_id = isset($where['circle_id']) ? $where['circle_id'] : 0;
    
        
        $condition_where = "s.city_id='" . C('config.now_city') . "' AND s.status=1 AND s.store_id=m.store_id AND s.have_shop=1";
        if (C('config.store_shop_auth') == 1) {
            $condition_where .= " AND s.auth>2";
        }
    
        if (isset($where['key']) && $where['key']) {
            $condition_where .= " AND `s`.`name` LIKE '%{$where['key']}%'";
        }
    
        $area_id && $condition_where .= " AND `s`.`area_id`={$area_id}";
        $circle_id && $condition_where .= " AND `s`.`circle_id`={$circle_id}";
        
        if ($cat_id) {
            $category = M('Shop_category')->where(array('cat_id' => $cat_id))->find();
        } elseif ($cat_fid) {
            $category = M('Shop_category')->where(array('cat_id' => $cat_fid))->find();
        }
        $show_method = isset($category) && $category ? $category['show_method'] : 2;
    
    
        $condition_field = 's.*, m.*,mm.isverify';
        
        //排序
        $order = '';
        switch($order_str){
            case 'score_mean':
                $order .= '`m`.`score_mean` DESC,`s`.`store_id` DESC';
                break;
            case 'permoney':
                $order .= '`m`.`mean_money` ASC,`s`.`store_id` DESC';
                break;
            case 'store_id':
                $order .= '`s`.`store_id` ASC';
                break;
            default:
                $order .= '`s`.`store_id` ASC';
                break;
        }

        $mod = new Model();
        if ($cat_fid || $cat_id) {
            if ($cat_fid && $cat_id) {
                $relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid, 'cat_id' => $cat_id))->select();
            } elseif ($cat_fid) {
                $relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid))->select();
            } else {
                $relation = D('Shop_category_relation')->where(array('cat_id' => $cat_id))->select();
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
                return array('shop_list' => null, 'pagebar' => null, 'total' => 0, 'next_page' => 0);
            }
        }

        if ($isverify != -1) {
            $condition_where .= ' AND mm.isverify=' . $isverify;
            $sql_count = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant as mm ON mm.mer_id=s.mer_id WHERE {$condition_where}";
        } else {
            $sql_count = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id WHERE {$condition_where}";
        }
        $count = $this->query($sql_count);
        
        $total = isset($count[0]['count']) ? $count[0]['count'] : 0;
        $nowPage = isset($where['p']) ? max($where['p'], 1) : 1;
        $pagesize = isset($where['pagesize']) ? intval($where['pagesize']) : 10;
        $pagesize = $pagesize > 0 && $pagesize < 100 ? $pagesize : 10;
        $star = ($nowPage - 1) * $pagesize;
        
        $sql = "SELECT {$condition_field} FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant as mm ON mm.mer_id=s.mer_id WHERE {$condition_where} ORDER BY {$order} LIMIT {$star}, {$pagesize}";
        $res = $this->query($sql);
        $store_ids = array();
        foreach ($res as $r) {
            if (!in_array($r['store_id'], $store_ids)) {
                $store_ids[] = $r['store_id'];
            }
        }

        $items = D('Shop_category')->field(true)->order('cat_id DESC')->select();
        $tmpMap = array();
        foreach ($items as $item) {
            $tmpMap[$item['cat_id']] = $item;
        }

        $store_image_class = new store_image();
        foreach ($res as &$v) {
            $v['url'] = C('config.site_url') . '/shop/' . $v['store_id'] . '.html';
            $images = $store_image_class->get_allImage_by_path($v['pic_info']);
            $v['image'] = $images ? array_shift($images) : array();
            $v['mean_money'] = floatval($v['mean_money']);
            $catNamef = isset($tmpMap[$v['cat_fid']]) ? $tmpMap[$v['cat_fid']]['cat_name'] : '';
            $catName = isset($tmpMap[$v['cat_id']]) ? $tmpMap[$v['cat_id']]['cat_name'] : '';
            $v['cat_name'] = $catNamef ? $catNamef . ($catName ? '-' . $catName : '') : $catName;
        }
        
        $return['shop_list'] = $res;
        $return['total'] = $total;
        $return['total_page'] = ceil($total / $pagesize);
        return $return;
    }
    
    
    public function getBuniessName($v)
    {
        if ($v['open_1'] == '00:00:00' && ($v['close_1'] == '00:00:00' || $v['close_1'] == '23:59:00')) {
            return $buniessName = '24小时营业';
        } else {
            $ot1 = strtotime(date('Y-m-d ' . $v['open_1']));
            $ct1 = strtotime(date('Y-m-d ' . $v['close_1']));
            $ot2 = strtotime(date('Y-m-d ' . $v['open_2']));
            $ct2 = strtotime(date('Y-m-d ' . $v['close_2']));
            $ot3 = strtotime(date('Y-m-d ' . $v['open_3']));
            $ct3 = strtotime(date('Y-m-d ' . $v['close_3']));
            $s1 = 0;
            $s2 = 0;
            $s3 = 0;
            $e1 = 0;
            $e2 = 0;
            $e3 = 0;
            if ($ot1 != $ct1) {
                $s1 = $ot1;
                $e1 = $ct1;
            } else {
                return $buniessName = '24小时营业';
            }
            if ($ot2 != $ct2) {
                $s2 = $ot2;
                $e2 = $ct2;
            }
            if ($ot3 != $ct3) {
                $s3 = $ot3;
                $e3 = $ct3;
            }
            $buniessName = array();
            if ($s1 > 0 && $e1 > 0) {
                if ($s1 > $e1) {
                    $e1 += 86400;
                }
                if ($s2 > 0 && $e2 > 0) {
                    if ($s2 > $e2) {
                        $e2 += 86400;
                    }
                    
                    if ($s2 < $s1 && $s1 <= $e2 && $e2 < $e1) {//时间段2的开始时间
                        $s1 = $s2;
                    } elseif ($s1 < $s2 && $e2 < $e1) {
                        
                    } elseif ($s1 < $s2 && $s2 <= $e1 && $e1 < $e2) {
                        $e1 = $e2;
                    } elseif ($s2 < $s1 && $e1 < $e2) {
                        $s1 = $s2;
                        $e1 = $e2;
                    } elseif ($s2 + 86400 <= $e1 && $e1 < $e2 + 86400) {
                        $e1 = $e2 + 86400;
                    } elseif ($e2 - 86400 > 0 && $e2 - 86400 >= $s1 && ($s2 - 86400 > 0 && $s2 - 86400 < $s1 || $s2 - 86400 < 0 && $s2 < $s1)) {
                        if ($s2 - 86400 > 0) {
                            $s1 = $s2 - 86400;
                        } else {
                            $s1 = $s2;
                        }
                    } else {
                        if ($s3 > 0 && $e3 > 0) {
                            if ($s3 > $e3) {
                                $e3 += 86400;
                            }
                            if ($s2 < $s3 && $s3 <= $e2 && $e2 < $e3) {//时间段2的开始时间
                                $s3 = $s2;
                            } elseif ($s3 < $s2 && $e2 < $e3) {
                                
                            } elseif ($s3 < $s2 && $s2 <= $e3 && $e3 < $e2) {
                                $e3 = $e2;
                            } elseif ($s2 < $s3 && $e3 < $e2) {
                                $s3 = $s2;
                                $e3 = $e2;
                            } elseif ($s2 + 86400 <= $s3 && $e3 < $e2 + 86400) {
                                $e3 = $e2 + 86400;
                            } elseif ($e2 - 86400 > 0 && $e2 - 86400 >= $s3 && ($s2 - 86400 > 0 && $s2 - 86400 < $s3 || $s2 - 86400 < 0 && $s2 < $s3)) {
                                if ($s2 - 86400 > 0) {
                                    $s3 = $s2 - 86400;
                                } else {
                                    $s3 = $s2;
                                }
                            } else {
                                if ($e2 - $s2 >= 86400) {
                                    return $buniessName = '24小时营业';
                                }
                                $sh = strtotime(date('Y-m-d ') . date('H:i:00', $s2));
                                $eh = strtotime(date('Y-m-d ') . date('H:i:00', $e2));
                                if ($sh > $eh) {
                                    $buniessName[] = date('H:i', $s2) . '~次日' . date('H:i', $e2);
                                } else {
                                    $buniessName[] = date('H:i', $s2) . '~' . date('H:i', $e2);
                                }
                            }
                        } else {
                            if ($e2 - $s2 >= 86400) {
                                return $buniessName = '24小时营业';
                            }
                            $sh = strtotime(date('Y-m-d ') . date('H:i:00', $s2));
                            $eh = strtotime(date('Y-m-d ') . date('H:i:00', $e2));
                            if ($sh > $eh) {
                                $buniessName[] = date('H:i', $s2) . '~次日' . date('H:i', $e2);
                            } else {
                                $buniessName[] = date('H:i', $s2) . '~' . date('H:i', $e2);
                            }
                        }
                    }
                    
                }
                
                if ($s3 > 0 && $e3 > 0) {
                    $e3 = $e3;
                    if ($s3 > $e3) {
                        $e3 = $e3 + 86400;
                    }
                    
                    if ($s3 < $s1 && $s1 <= $e3 && $e3 < $e1) {//时间段2的开始时间
                        $s1 = $s3;
                    } elseif ($s1 < $s3 && $e3 < $e1) {
                        
                    } elseif ($s1 < $s3 && $s3 <= $e1 && $e1 < $e3) {
                        $e1 = $e3;
                        $e1 = $e3;
                    } elseif ($s3 < $s1 && $e1 < $e3) {
                        $s1 = $s3;
                        $e1 = $e3;
                    }  elseif ($s3 + 86400 <= $s1 && $e1 < $e3 + 86400) {
                        $s1 = $s3;
                        $e1 = $e3 + 86400;
                    } elseif ($e3 - 86400 > 0 && $e3 - 86400 >= $s1 && ($s3 - 86400 > 0 && $s3 - 86400 < $s1 || $s3 - 86400 < 0 && $s3 < $s1)) {
                        if ($s2 - 86400 > 0) {
                            $s1 = $s3 - 86400;
                        } else {
                            $s1 = $s3;
                        }
                    } else {
                        if ($e3 - $s3 >= 86400) {
                            return $buniessName = '24小时营业';
                        }
                        $sh = strtotime(date('Y-m-d ') . date('H:i:00', $s3));
                        $eh = strtotime(date('Y-m-d ') . date('H:i:00', $e3));
                        if ($sh > $eh) {
                            $buniessName[] = date('H:i', $s3) . '~次日' . date('H:i', $e3);
                        } else {
                            $buniessName[] = date('H:i', $s3) . '~' . date('H:i', $e3);
                        }
                    }
                }
                
                if ($e1 - $s1 >= 86400) {
                    return $buniessName = '24小时营业';
                }
                $sh = strtotime(date('Y-m-d ') . date('H:i:00', $s1));
                $eh = strtotime(date('Y-m-d ') . date('H:i:00', $e1));
                if ($sh > $eh) {
                    $buniessName[] = date('H:i', $s1) . '~次日' . date('H:i', $e1);
                } else {
                    $buniessName[] = date('H:i', $s1) . '~' . date('H:i', $e1);
                }
            }
            sort($buniessName);
            return implode(',', $buniessName);
        }
    }
    public function checkTime($v)
    {
        $nowTime = time();
        
        if ($v['open_1'] == '00:00:00' && ($v['close_1'] == '00:00:00' || $v['close_1'] == '23:59:00')) {
            return true;
        } else {
            $ot1 = strtotime(date('Y-m-d ' . $v['open_1']));
            $ct1 = strtotime(date('Y-m-d ' . $v['close_1']));
            $ot2 = strtotime(date('Y-m-d ' . $v['open_2']));
            $ct2 = strtotime(date('Y-m-d ' . $v['close_2']));
            $ot3 = strtotime(date('Y-m-d ' . $v['open_3']));
            $ct3 = strtotime(date('Y-m-d ' . $v['close_3']));
            $s1 = 0;
            $s2 = 0;
            $s3 = 0;
            $e1 = 0;
            $e2 = 0;
            $e3 = 0;
            if ($ot1 != $ct1) {
                $s1 = $ot1;
                $e1 = $ct1;
                if ($ot1 > $ct1) {
                    $ct1 += 86400;
                }
                if ($ot1 <= $nowTime && $nowTime <= $ct1) {
                    return true;
                }
            } else {
                return true;
            }
            if ($ot2 != $ct2) {
                $s2 = $ot2;
                $e2 = $ct2;
                if ($ot2 > $ct2) {
                    $ct2 += 86400;
                }
                if ($ot2 <= $nowTime && $nowTime <= $ct2) {
                    return true;
                }
            }
            if ($ot3 != $ct3) {
                $s3 = $ot3;
                $e3 = $ct3;
                if ($ot3 > $ct3) {
                    $ct3 += 86400;
                }
                if ($ot3 <= $nowTime && $nowTime <= $ct3) {
                    return true;
                }
            }
            
            if ($e1 - $s2 > 86330 && $e1 - $s2 < 86400) {//时间段1和时间段2隔天相连
                $s = $s1;
                $e = $e2 + 86400;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            if ($s2 > 0 && ($s2 - $e1 < 70) && ($s2 - $e1 >= 0)) {//时间段1和时间段2相连
                $s = $s1;
                $e = $e2;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            
            if ($e2 - $s1 > 86330 && $e2 - $s1 < 86400) {//时间段2和时间段1隔天相连
                $s = $s2;
                $e = $e1 + 86400;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            if ($e2 > 0 && ($s1 - $e2 < 70) && ($s1 - $e2 >= 0)) {//时间段2和时间段1相连
                $s = $s2;
                $e = $e1;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            
            if ($e1 - $s3 > 86330 && $e1 - $s3 < 86400) {//时间段1和时间段3隔天相连
                $s = $s1;
                $e = $e3 + 86400;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            
            if ($s3 > 0 && ($s3 - $e1 < 70) && ($s3 - $e1 >= 0)) {//时间段1和时间段3相连
                $s = $s1;
                $e = $e3;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            if ($e3 - $s1 > 86330 && $e3 - $s1 < 86400) {//时间段3和时间段1隔天相连
                $s = $s3;
                $e = $e1 + 86400;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            
            if ($e3 > 0 && ($s1 - $e3 < 70) && ($s1 - $e3 >= 0)) {//时间段3和时间段1相连
                $s = $s3;
                $e = $e1;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            
            if ($e2 - $s3 > 86330 && $e2 - $s3 < 86400) {//时间段2和时间段3隔天相连
                $s = $s2;
                $e = $e3 + 86400;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            if ($e2 > 0 && $s3 > 0 && ($s3 - $e2 < 70) && ($s3 - $e2 >= 0)) {//时间段2和时间段3相连
                $s = $s2;
                $e = $e3;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            
            if ($e3 - $s2 > 86330 && $e3 - $s2 < 86400) {//时间段3和时间段2隔天相连
                $s = $s3;
                $e = $e2 + 86400;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            if ($e3 > 0 && $s2 > 0 && ($s2 - $e3 < 70) && ($s2 - $e3 >= 0)) {//时间段3和时间段2相连
                $s = $s3;
                $e = $e2;
                if ($s <= $nowTime && $nowTime <= $e) {
                    return true;
                }
            }
            return false;
        }
    }
}
?>