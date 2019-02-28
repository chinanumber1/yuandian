<?php
class House_village_storeModel extends Model{
	/*得到小区绑定的团购列表*/
	public function get_limit_list($village_id,$limit='',$user_long_lat=''){
		
		$now_time = $_SERVER['REQUEST_TIME'];
		
		$store_list = D('')->field('`s`.`name` AS `store_name`,`m`.`name` AS `merchant_name`,`s`.*,`m`.*,`hvs`.*')->table(array(C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_store'=>'hvs'))->where("`s`.`mer_id`=`m`.`mer_id` AND `hvs`.`store_id`=`s`.`store_id` AND `hvs`.`village_id`='$village_id'")->order('`hvs`.`sort` DESC,`hvs`.`pigcms_id` DESC')->limit($limit)->select();
		if($store_list){
			return $store_list;
		}else{
			return false;
		}
		
	}


	public function get_list_by_option($where = array(),$limit=0){
		$lat = isset($where['lat']) ? $where['lat'] : 0;
        $long = isset($where['long']) ? $where['long'] : 0;
        $village_id = isset($where['village_id']) ? $where['village_id'] : 0;

		$now_time = $_SERVER['REQUEST_TIME'];
		
		$res = D('')->field('`s`.`name` AS `store_name`,`s`.*,`m`.isverify,`hvs`.*,ss.*')->table(array(C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_store'=>'hvs',C('DB_PREFIX').'merchant_store_shop'=>'ss'))->where("`s`.`mer_id`=`m`.`mer_id` AND `hvs`.`store_id`=`s`.`store_id` AND `hvs`.`village_id`='$village_id' AND ss.store_id=s.store_id")->order('`hvs`.`sort` DESC,`hvs`.`pigcms_id` DESC')->limit($limit)->select();

		$ids = array();
        $store_ids = array();
        $latLng = array();
        $list = array();
        
        $goods_image_class = new goods_image();
        foreach ($res as $r) {
            if (! in_array($r['circle_id'], $ids)) {
                $ids[] = $r['circle_id'];
            }
            if (! in_array($r['store_id'], $store_ids)) {
                $store_ids[] = $r['store_id'];
            }
            $latLng[] = $r['lat'] . ',' . $r['long'];
        }
        
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
        foreach ($res as &$v) {
            $v['url'] = C('config.site_url') . '/shop/' . $v['store_id'] . '.html';
            $v['area_name'] = isset($temp[$v['circle_id']]) ? $temp[$v['circle_id']]['area_name'] : '';
            $images = $store_image_class->get_allImage_by_path($v['pic_info']);
            $v['image'] = $images ? array_shift($images) : '';
            $v['mean_money'] = floatval($v['mean_money']);
            $v['wap_url'] = U('Shop/shop', array('mer_id' => $v['mer_id'], 'store_id' => $v['store_id']));
            $v['deliver'] = $v['deliver_type'] == 2 ? false : true;
            
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
            $start_time = $deliverReturn['delivertime_start'];
            $stop_time = $deliverReturn['delivertime_stop'];
            $start_time2 = $deliverReturn['delivertime_start2'];
            $stop_time2 = $deliverReturn['delivertime_stop2'];
            
            if (! ($start_time == $stop_time && $start_time == '00:00:00')) {
                $start_time = strtotime(date('Y-m-d') . ' ' . $start_time);
                $stop_time = strtotime(date('Y-m-d') . ' ' . $stop_time);
                if ($start_time > $stop_time) $stop_time += 86400;
                if (! ($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
                    $start_time2 = strtotime(date('Y-m-d') . ' ' . $start_time2);
                    $stop_time2 = strtotime(date('Y-m-d') . ' ' . $stop_time2);
                    if ($start_time2 > $stop_time2) $stop_time2 += 86400;
                    $is_have_two_time = 1;
                }
            }
            
            if ($is_have_two_time) {
                if ($time <= $stop_time || $time > $stop_time2) {
                    $is_have_two_time = 0;
                }
            }
            $v['delivery_fee'] = $is_have_two_time ? $delivery_fee2 : $delivery_fee;
            
            if ($v['deliver_type'] == 0 || $v['deliver_type'] == 3) {
                $v['basic_price'] = floatval($v['s_basic_price']) ? floatval($v['s_basic_price']) : (C('config.basic_price') ? floatval(C('config.basic_price')) : floatval($v['basic_price']));
            }
            
            $v['is_have_two_time'] = $is_have_two_time;
        }
        $return['shop_list'] = $res;
        $return['total'] = $total;
        $return['pagetotal'] = $pagetotal;
      
     return $return;


		// if($store_list){
		// 	return $store_list;
		// }else{
		// 	return false;
		// }
		
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