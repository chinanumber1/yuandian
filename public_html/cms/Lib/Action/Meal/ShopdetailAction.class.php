<?php
/*
 * 快店详情
 *
 */
class ShopdetailAction extends BaseAction{
    public function index(){
    	//导航条
    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
    	$this->assign('web_index_slider',$web_index_slider);
    	
		$store_id = intval($_GET['store_id']);
		//店铺信息
		$now_store = D('Merchant_store')->where(array('store_id' => $store_id))->find();
		if (empty($now_store)) {
			$this->error('您查看的'.$this->config['shop_alias_name'].'不存在！');
		}
		if ($now_store['status'] != 1) {
			$this->error('您查看的'.$this->config['shop_alias_name'].'已关闭！');
		}
		//资质认证
		if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
			$this->error('您查看的'.$this->config['shop_alias_name'].'没有通过资质审核！');
		}
		
		/*多城市判断跳转*/
		if($this->config['many_city'] && $this->config['many_city_main_domain'].'.'.$this->config['many_city_top_domain'] == $_SERVER['HTTP_HOST'] && $this->config['now_site_url']){
			$now_merchant = D('Merchant')->get_info($now_store['mer_id']);		
			$now_city = D('Area')->field('`area_id`,`area_name`,`area_url`')->where(array('area_id'=>$now_merchant['city_id']))->find();
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.'http://'.$now_city['area_url'].'.'.$this->config['many_city_top_domain'].$_SERVER['REQUEST_URI']);
			exit();
		}
		
		$now_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($now_shop) || empty($now_store)) {
			$this->error('您查看的'.$this->config['shop_alias_name'].'不存在！');
		}
// 		$discounts = D('Shop_discount')->get_discount_byids(array($store_id));
		$discounts = D('Shop_discount')->getDiscounts($now_store['mer_id'], $store_id);
		$row = array_merge($now_store, $now_shop);
		
		$store = array();
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($row['pic_info']);
	
		$store['id'] = $row['store_id'];
		$store['mer_id'] = $now_store['mer_id'];
		
		$store['phone'] = $row['phone'];
		$store['long'] = $row['long'];
		$store['lat'] = $row['lat'];
		$store['adress'] = $row['adress'];
		$store['is_close'] = 1;
		$now_time = date('H:i:s');
		if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
			$store['time'] = '24小时营业';
			$store['is_close'] = 0;
		} else {
			$store['time'] = $row['open_1'] . '~' . $row['close_1'];
			if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
				$store['is_close'] = 0;
			}
			if ($row['open_2'] != '00:00:00' && $row['close_2'] != '00:00:00') {
				$store['time'] .= ',' . $row['open_2'] . '~' . $row['close_2'];
				if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
					$store['is_close'] = 0;
				}
			}
			if ($row['open_3'] != '00:00:00' && $row['close_3'] != '00:00:00') {
				$store['time'] .= ',' . $row['open_3'] . '~' . $row['close_3'];
				if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
					$store['is_close'] = 0;
				}
			}
		}
		
		$store['name'] = $row['name'];
		$store['store_notice'] = $row['store_notice'];
		$store['image'] = isset($images[0]) ? $images[0] : ''; 
		$store['star'] = $row['score_mean'];
		$store['month_sale_count'] = $row['sale_count'];
		$store['delivery'] = $row['deliver_type'] == 2 ? false : true;;//是否支持配送
		$store['delivery_time'] = $row['send_time'];//配送时长
		$store['delivery_price'] = floatval($row['basic_price']);//起送价
		$store['delivery_money'] = $row['deliver_type'] == 0 ? C('config.delivery_fee') : $row['delivery_fee'];//配送费
		$store['delivery_money'] = floatval($store['delivery_money']);//配送费
		$store['delivery_system'] = empty($row['delivery_type']) ? true : false;//是否是平台配送
		$store['coupon_list'] = array();
		if ($row['is_invoice']) {
			$store['coupon_list']['invoice'] = floatval($row['invoice_price']);
		}
		if ($row['store_discount']) {
			$store['coupon_list']['discount'] = $row['store_discount'];
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
		if ($store['deliver']) {
			if ($store['delivery_system'] && $system_delivery) {
				$store['coupon_list']['delivery'] = $system_delivery;
			} else {
				if ($row['reach_delivery_fee_type'] == 0) {
					$store['coupon_list']['delivery'] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
				} elseif ($row['reach_delivery_fee_type'] == 1) {
					//$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
				} else {
					$store['coupon_list']['delivery'] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
				}
			}
		}
		$tmpCouponList = array();
		foreach($store['coupon_list'] as $key=>$value){
			if(is_array($value)){
				foreach($value as $v){
					$tmpCouponList[$key][] = '满'.$v['money'].'元减'.$v['minus'].'元';
				}
			}else if($key == 'invoice'){
				$tmpCouponList[$key] = '满'.$value.'元支持开发票，请在下单时填写发票抬头';
			}else if($key == 'discount'){
				$tmpCouponList[$key] = '店内全场'.$value.'折';
			}
		}
		$store['coupon_list_txt'] = array();
		foreach($tmpCouponList as $key=>$value){
			if(is_array($value)){
				switch($key){
					case 'system_newuser':
						$store['coupon_list_txt'][$key] = '平台首单'.implode(',',$value);
						break;
					case 'system_minus':
						$store['coupon_list_txt'][$key] = '平台优惠'.implode(',',$value);
						break;
					case 'newuser':
						$store['coupon_list_txt'][$key] = '店铺首单'.implode(',',$value);
						break;
					case 'minus':
						$store['coupon_list_txt'][$key] = '店铺优惠'.implode(',',$value);
						break;
					case 'system_minus':
						$store['coupon_list_txt'][$key] = '平台优惠'.implode(',',$value);
						break;
					case 'delivery':
						$store['coupon_list_txt'][$key] = '配送费'.implode(',',$value);
						break;
				}
			}else if($key == 'invoice' || $key == 'discount'){
				$store['coupon_list_txt'][$key] = $value;
			}
		}
		$today = date('Ymd');
		
		$product_list = D('Shop_goods')->get_list_by_storeid($store_id);
		foreach ($product_list as $row) {
			$temp = array();
			$temp['cat_id'] = $row['sort_id'];
			$temp['cat_name'] = $row['sort_name'];
			foreach ($row['goods_list'] as $r) {
				$glist = array();
				$glist['product_id'] = $r['goods_id'];
				$glist['product_name'] = $r['name'];
				$glist['product_price'] = $r['price'];
				if (isset($r['pic_arr'][0])) {
					$glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
				}
				$glist['product_sale'] = $r['sell_count'];
				$glist['product_reply'] = $r['reply_count'];
				$glist['has_format'] = false;
				if ($r['spec_value'] || $r['is_properties']) {
					$glist['has_format'] = true;
					$glist['properties_list'] = $r['properties_list'];
					$glist['spec_list'] = $r['spec_list'];
					$glist['list'] = $r['list'];
				}
				
				$r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
				if ($today == $r['sell_day']) {
					$glist['stock'] = $r['stock_num'] == -1 ? $r['stock_num'] : intval($r['stock_num'] - $r['today_sell_count']);
				} else {
					$glist['stock'] = $r['stock_num'];
				}
				if($glist['list']){
					$tmpListArr = array();
					foreach($glist['list'] as $listKey=>$listValue){
						$tmpList = array();
						$tmpList[] = $glist['product_id'].'_'.$listKey;
						$tmpList[] = $listValue['price'];
						$tmpList[] = $listValue['stock_num'];
						if($listValue['properties']){
							$tmpPList = array();
							foreach($listValue['properties'] as $listPKey=>$listPValue){
								$tmpPList[] = $listPValue['id'].':'.$listPValue['num'];
							}
							$tmpList[] = implode(',',$tmpPList);
						}else{
							$tmpList[] = '';
						}
						$tmpListArr[] = implode('|',$tmpList);
					}
					$glist['list_txt'] = implode(';',$tmpListArr);
				}
				$temp['product_list'][] = $glist;
			}
			$list[] = $temp;
		}
// dump($store);
		// dump($list[0]['product_list'][1]);
		$this->assign('store',$store);
		$this->assign('product_list',$list);
		$this->display();
    }
    
	public function group_noexit_tips()
	{
// 		$this->error('您查看的餐厅不存在！');
	}
	
	public function addcart()
	{
		$shop_cart = isset($_POST['shop_cart']) ? htmlspecialchars($_POST['shop_cart']) : '';
		$temp = explode(":", $shop_cart);
		$store_id = $temp[0];
		$menus = explode("|", $temp[1]);
		$ids = $list = array();
		$food_count = 0;
		$MOOBJ = D('Meal_order');
		foreach ($menus as $m){
			$t = explode(",", $m);
			$ids[] = $t[0];
			$list[$t[0]] = $t[1];
			
			$check_stock = $MOOBJ->check_stock($t[0]);
			if ($check_stock['stock_num'] > -1 && $check_stock['stock_num'] < $t[1]) {
				exit(json_encode(array('error_code' => 1, 'msg' => '您购买的' . $check_stock['name'] . '超出了库存量！')));
				break;
			}
			
			$food_count += $t[1];
		}
		$meals = D("Meal")->field(true)->where(array('store_id' => $store_id, 'meal_id' => array('in', $ids)))->select();
		$total = 0;
		$food_list = array();
		foreach ($meals as $meal) {
			$tt = array();
			$tt['food_id'] = $meal['meal_id'];
			$tt['food_name'] = $meal['name'];
			$tt['unit'] = $meal['unit'];
			$tt['count'] = 1;
			$tt['box_num'] = 1;
			$tt['box_price'] = 0;
			$tt['single_price'] = $meal['price'];
			$tt['price'] = $meal['price'];
			$tt['food_score'] = 0;
			$tt['foodComment'] = '';
			$tt['is_online_special_meal'] = '';
			$tt['original_price'] = $meal['price'];
			$total += $meal['price'] * $list[$meal['meal_id']];
			$food_list[] = $tt;
		}
		
		echo json_encode(array('data' => array('foodlist' => $food_list, 'total' => $total, 'food_count' => $food_count, 'origin_total' => $total, 'isSatisfyMinPrice' => 1, 'act_info' => array('has_full_discount' => 0, 'has_meals_donation' => 0)), 'msg' => '成功', 'code' => 0));
	}
}