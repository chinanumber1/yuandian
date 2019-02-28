<?php
class SpecialAction extends BaseAction{
    protected $send_time_type = array('分钟', '小时', '天', '周', '月');
	public function index(){
		
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				$this->error_tips('请先进行登录！',U('Login/index',$location_param));
			}
		}

		if($_GET['lat']&&$_GET['long']){
			$long_lat['lat'] = $_GET['lat'];
			$long_lat['long'] = $_GET['long'];
		}else{
			$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		}
		if(!empty($long_lat)){
			import('@.ORG.longlat');
			$longlat_class = new longlat();
			$long_lat = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);
			$long_lat['long'] = $long_lat['lng'];
			$this->assign('long_lat',$long_lat);
		}
		
		$database_special = D('Special');
		$condition_special['pigcms_id'] = $_GET['id'];
        $now_special = $database_special->where($condition_special)->find();
		
		$coupon_id_arr = array();
		if($now_special['coupon']){
			$now_special['coupon'] = unserialize($now_special['coupon']);
			foreach($now_special['coupon'] as $value){
				$coupon_id_arr[] = $value['id'];
			}
		}
		$now_special['coupon_id'] = implode(',',$coupon_id_arr);
		
		$now_special['product_list'] = unserialize($now_special['product_list']);
		$product_list = array();
		foreach($now_special['product_list'] as $key=>$value){
			$tmp_arr = array();
			foreach($value['product'] as $v){
				$tmp_arr[$key][] = $v['id'];
			}
			$product_list[$key] = implode(',',$tmp_arr[$key]);
		}
		// dump($product_list);
		$now_special['product_id_arr'] = $product_list;
		
		
		// dump($now_special);
		$this->assign('now_special',$now_special);
		
		$database_special->where($condition_special)->setInc('hits');
		
		$this->display();
	}
	public function ajax_get_coupon_by_ids(){
		$ids = $_POST['ids'];
		$ids_arr = explode(',',$ids);
		$res = D('System_coupon')->get_coupon_list_by_ids($ids);
		$time = time();
		foreach($ids_arr as $v){
			$can = true;
			if($res[$v]['end_time']<$time||$res[$v]['start_time']>$time||$res[$v]['status']!=1||$res[$v]['had_pull']==$res[$v]['num']){
				$can = false;
			}
			$arr[]=array(
				'id'=>$res[$v]['coupon_id'],
				'name'=>$res[$v]['name'],
				'order_money'=>floatval($res[$v]['order_money']),
				'discount'=>floatval($res[$v]['discount']),
				'can'=>$can,
			);
		}
		$this->success($arr);
	}
	public function ajax_get_shop_by_ids(){
		//和快店列表返回一致$_POST['ids']
		$ids = $_POST['ids'];
		
		$where = array('lat' => $_POST['user_lat'], 'long' => $_POST['user_long']);
        $page = $_GET['page'] ? $_GET['page'] : 0;
        $page_count = 10;
		$lists = D('Merchant_store_shop')->get_list_by_ids($ids, $where,$page.','.$page_count);

		// dump(D('Merchant_store_shop'));
		$return = array();
		$now_time = date('H:i:s');
		$id_array = explode(',', $ids);
		foreach ($id_array as $store_id) {
			$row = isset($lists['shop_list'][$store_id]) ? $lists['shop_list'][$store_id] : '';
			if (empty($row)) continue;
			$temp = array();
			$temp['id'] = $row['store_id'];
			$temp['name'] = $row['name'];
			$temp['range'] = $row['range'];
			$temp['image'] = $row['image'];
			$temp['star'] = $row['score_mean'];
			$temp['month_sale_count'] = $row['sale_count'];
			$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
			$temp['delivery_time'] = $row['send_time'];//配送时长
			$temp['send_time_type'] = $row['send_time_type'];//配送时长类型
			$temp['delivery_time_type'] = $this->send_time_type[$row['send_time_type']];//配送时长单位
			$temp['delivery_price'] = floatval($row['basic_price']);//起送价
			$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
			$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
			
			$temp['is_close'] = $row['state'] ? intval($row['is_close']) : 1;
// 			$temp['is_close'] = 1;
		
// 			if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
// 				$temp['time'] = '24小时营业';
// 				$temp['is_close'] = 0;
// 			} else {
// 				$temp['time'] = $row['open_1'] . '~' . $row['close_1'];
// 				if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
// 					$temp['is_close'] = 0;
// 				}
// 				if ($row['open_2'] != '00:00:00' && $row['close_2'] != '00:00:00') {
// 					$temp['time'] .= ',' . $row['open_2'] . '~' . $row['close_2'];
// 					if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
// 						$temp['is_close'] = 0;
// 					}
// 				}
// 				if ($row['open_3'] != '00:00:00' && $row['close_3'] != '00:00:00') {
// 					$temp['time'] .= ',' . $row['open_3'] . '~' . $row['close_3'];
// 					if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
// 						$temp['is_close'] = 0;
// 					}
// 				}
// 			}
				
			$temp['coupon_list'] = array();
			if ($row['is_invoice']) {
				$temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
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
					if ($row['reach_delivery_fee_type'] == 0) {
						if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
							$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
						}
					} elseif ($row['reach_delivery_fee_type'] == 1) {
						//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
					} elseif ($row['reach_delivery_fee_type'] == 2) {
						$temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
					}
				}
			}

			$temp['coupon_count'] = count($temp['coupon_list']);
			if($temp['is_close']==1){
			    $append[] = $temp;
            }else{
                $return[] = $temp;
            }
		}
		if($append){
		   $return =  array_merge($return,$append);
		}
		if(empty($return)){
            $return = array();
        }

        $this->success($return);
	}
}
	
?>