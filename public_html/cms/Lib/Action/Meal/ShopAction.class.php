<?php
/*
 * 周边团购
 *
 */
class ShopAction extends BaseAction{
	public function index(){
		$userLocationLat = $_COOKIE['userLocationLat'];
		$userLocationLong = $_COOKIE['userLocationLong'];
		$userLocationName = $_COOKIE['userLocationName'];
		if(empty($userLocationLat) || empty($userLocationLong) || empty($userLocationName)){
			redirect('/shop/around');
		}
		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12,0);
    	$this->assign('search_hot_list',$search_hot_list);
		
		$cat_url = !empty($_GET['cat_url']) ? $_GET['cat_url'] : 'all';
		$type_url = !empty($_GET['type_url']) ? $_GET['type_url'] : 'all';
		$order = isset($_GET['order']) && $_GET['order'] ? htmlspecialchars($_GET['order']) : '';

		$category_list = D('Shop_category')->lists(true);
		$cat_option_list[] = array('txt_desc'=>'分类','row_type'=>'category','category_list'=>$category_list);
		$type_list = array(
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
		$cat_option_list[] = array('txt_desc'=>'类别','row_type'=>'type','type_list'=>$type_list);
		
		$cat_option_html = $this->get_cat_option_html($cat_option_list,$cat_url,$type_url,$order);
		$this->assign('cat_option_html',$cat_option_html);
		
		$cat_sort_url = $this->get_cat_sort_url($cat_url,$type_url);
		$this->assign($cat_sort_url);
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$page = max(1, $page);
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

		$where = array('deliver_type' => $type_url, 'order' => $order, 'lat' => $userLocationLat, 'long' => $userLocationLong, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page);
		
		$lists = D('Merchant_store_shop')->get_list_by_option($where, 0);
		// dump(D('Merchant_store_shop'));exit;
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
			$temp['delivery'] = $row['deliver'];//是否支持配送
			$temp['delivery_time'] = $row['send_time'];//配送时长
			$temp['delivery_price'] = floatval($row['basic_price']);//起送价
			$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
			$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
			$temp['is_close'] = 1;
		
			if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
				$temp['time'] = '24小时营业';
				$temp['is_close'] = 0;
			} else {
				$temp['time'] = $row['open_1'] . '~' . $row['close_1'];
				if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
					$temp['is_close'] = 0;
				}
				if ($row['open_2'] != '00:00:00' && $row['close_2'] != '00:00:00') {
					$temp['time'] .= ',' . $row['open_2'] . '~' . $row['close_2'];
					if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
						$temp['is_close'] = 0;
					}
				}
				if ($row['open_3'] != '00:00:00' && $row['close_3'] != '00:00:00') {
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
			if ($row['store_discount']) {
				$temp['coupon_list']['discount'] = $row['store_discount'];
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
					$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
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
				if ($temp['delivery_system'] && $system_delivery) {
					$temp['coupon_list']['delivery'] = $system_delivery;
				} else {
					if ($row['reach_delivery_fee_type'] == 0) {
						$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
					} elseif ($row['reach_delivery_fee_type'] == 1) {
						//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
					} else {
						$temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
					}
				}
			}
			$temp['coupon_count'] = count($temp['coupon_list']);
			$return[] = $temp;
		}
		$this->assign('shop_list',$return);
		$this->assign('pagebar',$lists['pagebar']);
		// dump($return);

		$this->display();
	}
    public function around(){
    	//导航条
    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
    	$this->assign('web_index_slider',$web_index_slider);

		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12,0);
    	$this->assign('search_hot_list',$search_hot_list);

		$this->display();
    }
	protected function get_cat_option_html($cat_option_list,$cat_url,$type_url,$order){
		$cat_option_html = '';
		foreach($cat_option_list as $key=>$value){
			$cat_option_html .= '<div class="filter-label-list filter-section category-filter-wrapper log-mod-viewed '.($key==0 ? 'first-filter' :'').($value['row_type']=='custom_1' ? 'filter-sect--multi' : '').'">';
			$cat_option_html .= '<div class="label has-icon">'.$value['txt_desc'].'：</div>';
			$cat_option_html .= '<ul class="filter-sect-list">';
			
			if($value['row_type'] == 'category'){
				foreach($value['category_list'] as $k=>$v){
					$cat_option_html .= '<li class="item'.($cat_url==$v['cat_url'] ? ' current' : '').'"><a '.($v['is_hot'] ? 'class="briber"' : '').' href="'.$this->get_cat_option_url($v['cat_url'],$type_url,$order).'">'.$v['cat_name'].'</a></li>';
				}
			}else if($value['row_type'] == 'type'){
				foreach($value['type_list'] as $k=>$v){
					$cat_option_html .= '<li '.($type_url==$v['type_url'] ? 'class="current"' : '').'><a href="'.$this->get_cat_option_url($cat_url,$v['type_url'],$order).'">'.$v['name'].'</a></li>';
				}
			}
			$cat_option_html .= '</ul>';
			$cat_option_html .= '</div>';
		}
		return $cat_option_html;
	}
	protected function get_cat_option_url($cat_url,$type_url,$order){
		if($order){
			return C('config.site_url').'/shop/'.$cat_url.'/'.$type_url.'/'.$order;
		}else{
			return C('config.site_url').'/shop/'.$cat_url.'/'.$type_url;
		}
	}
	protected function get_cat_sort_url($cat_url,$type_url){
		$return['default_sort_url'] = C('config.site_url').'/shop/'.$cat_url.'/'.$type_url;
		$return['hot_sort_url'] = C('config.site_url').'/shop/'.$cat_url.'/'.$type_url.'/hot';
		$return['basic_price_url'] = C('config.site_url').'/shop/'.$cat_url.'/'.$type_url.'/basic_price';
		$return['delivery_fee_url'] = C('config.site_url').'/shop/'.$cat_url.'/'.$type_url.'/delivery_fee';
		$return['rating_sort_url'] = C('config.site_url').'/shop/'.$cat_url.'/'.$type_url.'/score_mean';
		$return['time_sort_url'] = C('config.site_url').'/shop/'.$cat_url.'/'.$type_url.'/create_time';
		return $return;
	}
}