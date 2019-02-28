<?php
class SearchAction extends BaseAction{
	public function index(){
		//热门搜索词
		$type = $_GET['type'] == 'meal' ? 1 : 0;
    	$search_hot_list = D('Search_hot')->get_list(18,$type,true);
    	$this->assign('search_hot_list',$search_hot_list);
		$_GET['type'] = empty($_GET['type'])?$this->config['search_first_type']:$_GET['type'];
    	$this->assign('type',$_GET['type']);

		$this->display();
	}
	public function group(){
		$keywords = htmlspecialchars($_REQUEST['w']);
		$this->assign('keywords',$keywords);
		
		$group_category = D('Group_category')->field('`cat_url`')->where(array('cat_name'=>$keywords,'cat_status'=>'1'))->find();
		if($group_category['cat_url']){
			redirect(U('Group/index',array('cat_url'=>$group_category['cat_url'],'w'=> urlencode($keywords))));exit;
		}
		
		$sort = empty($_GET['sort']) ? 'default' : $_GET['sort'];
		
		$this->assign('now_sort',$sort);
		
		//得到搜索的团购列表
		$group_return = D('Group')->get_group_list_by_keywords($keywords,$sort,true);
		$this->assign($group_return);
		// dump($group_return);exit;

		
		$this->display();
	}
	
	//技师搜索
	public function worker(){
		$keywords = htmlspecialchars($_REQUEST['w']);
		
		$this->assign('keywords',$keywords);
	
		$sort = empty($_GET['sort']) ? 'default' : $_GET['sort'];
		
		$this->assign('now_sort',$sort);
		
		//得到搜索的店铺列表
		$return = D('Merchant_workers')->get_list_by_search($keywords, $sort, true);

		$this->assign($return);
				
		$this->display();
	}
	
	public function meal()
	{
		$keywords = htmlspecialchars($_REQUEST['w']);
		$this->assign('keywords',$keywords);

		$sort = empty($_GET['sort']) ? 'default' : $_GET['sort'];

		$this->assign('now_sort',$sort);

		//得到搜索的店铺列表
		$return = D('Merchant_store')->get_list_by_search($keywords, $sort, true);
		foreach($return['group_list'] as &$value){
			$value['search_name'] = str_replace($keywords, '<font color="#06c1ae">' . $keywords . '</font>', $value['name']);
		}
		$this->assign($return);

		$this->display();
	}

	public function shop()
	{
		$keywords = htmlspecialchars($_REQUEST['w']);
		$this->assign('keywords',$keywords);

		$sort = empty($_GET['sort']) ? 'default' : $_GET['sort'];

		$this->assign('now_sort',$sort);
		$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		$where = array('lat' => $user_long_lat['lat'], 'long' => $user_long_lat['long']);

		$this->assign('user_long',$user_long_lat);

		//得到搜索的店铺列表
		$lists = D('Merchant_store_shop')->get_list_by_option($where, 1, 1);
		$return = array();
		$now_time = date('H:i:s');
		foreach ($lists['shop_list'] as $row) {
			$temp = array();
			$temp['store_id'] = $row['store_id'];
			$temp['name'] = $row['name'];
			$temp['search_name'] = str_replace($key, '<font color="#06c1ae">' . $key . '</font>', $row['name']);
			$temp['store_theme'] = $row['store_theme'];
			$temp['isverify'] = $row['isverify'];
			$temp['juli_wx'] = $row['juli'];
			$temp['range'] = $row['range'];
			$temp['image'] = $this->config['site_url'].'/index.php?c=Image&a=thumb&width=180&height=120&url='.urlencode($row['image']);
			$temp['star'] = $row['score_mean'];
			$temp['month_sale_count'] = $row['sale_count'];
			$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
			$temp['delivery'] = $temp['delivery'] ? true : false;
			$temp['delivery_time'] = $row['send_time'];//配送时长
			$temp['delivery_price'] = floatval($row['basic_price']);//起送价
			$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
			$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
			$temp['is_close'] = 1;
			$temp['goods_list'] = isset($row['goods_list']) && $row['goods_list'] ? $row['goods_list'] : array();

			if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
				$temp['time'] = '24小时营业';
				$temp['is_close'] = 0;
			} else {
				$temp['time'] = $row['open_1'] . '~' . $row['close_1'];
				if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
					$temp['is_close'] = 0;
				}
				if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
					$temp['time'] .= ',' . $row['open_2'] . '~' . $row['close_2'];
					if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
						$temp['is_close'] = 0;
					}
				}
				if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
					$temp['time'] .= ',' . $row['open_3'] . '~' . $row['close_3'];
					if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
						$temp['is_close'] = 0;
					}
				}
			}

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
			$temp['coupon_list'] = $this->parseCoupon($temp['coupon_list'],'array');
			$return[] = $temp;
		}
		$this->assign('shop_list',$return);

		$this->display();
	}
	
	public function appoint()
	{
		$keywords = htmlspecialchars($_REQUEST['w']);
		
		$this->assign('keywords',$keywords);
	
		$sort = empty($_GET['sort']) ? 'default' : $_GET['sort'];
		
		$this->assign('now_sort',$sort);
		
		//得到搜索的店铺列表
		$return = D('Appoint')->get_list_by_search($keywords, $sort, true);

		$this->assign($return);
				
		$this->display();
	}

	public function parseCoupon($obj,$type){
		$returnObj = array();
		foreach($obj as $key=>$value){
			if($key=='invoice'){
				$returnObj[$key] = '满'.$obj[$key].'元支持开发票，请在下单时填写发票抬头';
			}else if($key=='discount'){
				$returnObj[$key] = '店内全场'.$obj[$key].'折';
			}else{
				$returnObj[$key] = [];
				foreach($obj[$key] as $k=>$v){
					if ($key == 'delivery')  {
						$returnObj[$key][] = '商品满'.$obj[$key][$k]['money'].'元,配送费减'.$obj[$key][$k]['minus'].'元';
					} else {
						$returnObj[$key][] = '满'.$obj[$key][$k]['money'].'元减'.$obj[$key][$k]['minus'].'元';
					}
				}
			}
		}

		$textObj = array();
		foreach($returnObj as $key=>$value){
			if($key=='invoice' || $key=='discount'){
				$textObj[$key] = $value;
			}else{
				switch($key){
					case 'system_newuser':
						$textObj[$key] = '平台首单'.implode(',',$value);
						break;
					case 'system_minus':
						$textObj[$key] = '平台优惠'.implode(',',$value);
						break;
					case 'newuser':
						$textObj[$key] = '店铺首单'.implode(',',$value);
						break;
					case 'minus':
						$textObj[$key] = '店铺优惠'.implode(',',$value);
						break;
					case 'system_minus':
						$textObj[$key] = '平台优惠'.implode(',',$value);
						break;
					case 'delivery':
						$textObj[$key] = implode(',',$value);
						break;
				}
			}
		}
		if($type == 'text'){
			$tmpObj = array();
			foreach($textObj as $key=>$value){
				$tmpObj[] = $value;
			}
			return implode(';',$tmpObj);
		}else{
			$returnObj = array();
			foreach($textObj as $key=>$value){
				$returnObj[] = array(
						'type'=>$key,
						'value'=>$value
				);
			}
			return $returnObj;
		}
	}
}
?>