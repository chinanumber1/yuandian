<?php
class Merchant_storeModel extends Model
{
    /**
     * 说明店铺的状态  status字段
     * 0:关闭,1：开启，2：审核中，4：删除
     */
    
	public function get_storelist_by_merId($mer_id){
		return $this->field(true)->where(array('mer_id'=>$mer_id, 'status' => 1))->select();
	}


	/*通过店铺ID得到当前店铺*/
	public function get_store_by_storeId($store_id){
		$now_store = D('')->table(array(C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'merchant_store'=>'mc',C('DB_PREFIX').'area'=>'a'))->where("`mc`.`store_id`='$store_id' AND `mc`.`area_id`=`a`.`area_id` AND `m`.`mer_id`=`mc`.mer_id")->find();
		if(!empty($now_store['pic_info'])){
			$store_image_class = new store_image();
			$now_store['all_pic'] = $store_image_class->get_allImage_by_path($now_store['pic_info']);
		}
		return $now_store;
	}
	/*通过店铺ID得到当前店铺*/
	public function get_store_list_by_merId($mer_id,$store_type=''){
		$where = '';
		if($store_type == 'shop'){
			$where = ' AND `mc`.`have_shop`=1';
		}
		$store_list = D('')->table(array(C('DB_PREFIX').'merchant_store'=>'mc',C('DB_PREFIX').'area'=>'a'))->field('mc.*,a.area_name')->where("`mc`.`mer_id`='$mer_id' AND `mc`.`area_id`=`a`.`area_id` AND `mc`.`status`=1".$where)->order('`sort` DESC')->select();
		foreach($store_list as $key=>$value){
			if(!empty($value['pic_info'])){
				$store_image_class = new store_image();
				$store_list[$key]['all_pic'] = $store_image_class->get_allImage_by_path($value['pic_info']);
			}
		}
		return $store_list;
	}


	/**
	 * 根据条件获取商家列表
	 * @param array $where
	 * @param number $limit
	 */
	public function get_list_by_option($area_id = 0, $circle_id = 0, $order = 'store_id', $lat = 0, $long = 0, $cat_id = 0, $cat_fid = 0, $store_type = 2)
	{
		$condition_where = "s.city_id='".C('config.now_city')."' AND s.have_meal=1 AND s.status=1 AND s.store_id=m.store_id";
		if (is_array($store_type)) {
			$condition_where .= " AND s.store_type IN (" . implode(',', $store_type) . ")";
		} else if($store_type){
			$condition_where .= " AND s.store_type={$store_type}";
		}

		$area_id && $condition_where .= " AND s.area_id={$area_id}";
		$circle_id && $condition_where .= " AND s.circle_id={$circle_id}";

		$juli = '';
		//排序
		switch($order){
			case 'price-asc':
				$order = '`m`.`mean_money` ASC,`s`.`store_id` DESC';
				break;
			case 'price-desc':
				$order = '`m`.`mean_money` DESC,`s`.`store_id` DESC';
				break;
			case 'hot':
				$order = '`m`.`sale_count` DESC,`s`.`store_id` DESC';
				break;
			case 'time':
				$order = '`s`.`last_time` DESC,`s`.`store_id` DESC';
				break;
			case 'store_id':
				$order = '`s`.`store_id` ASC';
				break;
			case 'juli':
			default:
				$juli = ", ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli";
				$order = 'juli asc';
				break;
		}


		import('@.ORG.wap_group_page');
		$mod = new Model();
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
				return array('meal_list' => null, 'pagebar' => null);
			}
		}

		$labels = D('Store_label')->field(true)->select();
		$label_list = array();
		foreach ($labels as $label) {
			$label_list[$label['id']] = $label;
		}

		$sql_count = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_foodshop as m ON m.store_id=s.store_id WHERE {$condition_where}";
		$count = $mod->query($sql_count);

		$total = isset($count[0]['count']) ? $count[0]['count'] : 0;
		$p = new Page($total, C('config.meal_page_row'), C('config.meal_page_val'));

		$sql = "SELECT s.*, m.* {$juli} FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_foodshop as m ON m.store_id=s.store_id WHERE {$condition_where} ORDER BY {$order} LIMIT {$p->firstRow}, {$p->listRows}";
		$res = $mod->query($sql);
		// dump($mod);
		$ids = array();
		foreach ($res as $r) {
			if (!in_array($r['circle_id'], $ids)) {
				$ids[] = $r['circle_id'];
			}
		}
		$temp = array();
		if ($ids) {
			$areas = M("Area")->where(array('area_id' => array('in', $ids)))->select();
			foreach ($areas as $a) {
				$temp[$a['area_id']] = $a;
			}
		}
		$now_time = time();
		$store_image_class = new store_image();
		foreach ($res as &$v) {
			$v['url'] = C('config.site_url').'/meal/'.$v['store_id'].'.html';
			$v['area_name'] = isset($temp[$v['circle_id']]) ? $temp[$v['circle_id']]['area_name'] : '';
			$images = $store_image_class->get_allImage_by_path($v['pic_info']);
			$v['image'] = $images ? array_shift($images) : array();
			$v['mean_money'] = floatval($v['mean_money']);
			$v['wap_url'] = U('Food/shop',array('mer_id'=>$v['mer_id'],'store_id'=>$v['store_id']));

			if($v['juli']){
				$v['range'] = getRange($v['juli']);
			}

			$v['state'] = 0;//根据营业时间判断
			$v['work_time'] = '';

			$temp_label = array();
			foreach (unserialize($v['store_labels']) as $labelid) {
				isset($label_list[$labelid]) && $temp_label[] = $label_list[$labelid];
			}
			$v['store_labels'] = $temp_label;
			if ($v['office_time']) {
				foreach (unserialize($v['office_time']) as $time) {
					$v['work_time'] .= '<span>' . $time['open'] . '-' . $time['close'] . '</span>';
					$open = strtotime(date("Y-m-d ") . $time['open'] . ':00');
					$close = strtotime(date("Y-m-d ") . $time['close'] . ':00');
					if ($open < $now_time && $now_time < $close) {
						$v['state'] = 1;//根据营业时间判断
					}
				}
			} else {
				if ($v['open_1'] == '00:00:00' && $v['close_1'] == '00:00:00') {
					$v['work_time'] .= '<span>24小时营业</span>';
					$v['state'] = 1;
				} else {
					$now_time = date('H:i:s');
					$v['work_time'] .= '<span>' . $v['open_1'] . '-' . $v['close_1'] . '</span>';
					if ($v['open_1'] < $now_time && $now_time < $v['close_1']) {
						$v['state'] = 1;
					}
					if ($v['open_2'] != '00:00:00' && $v['close_2'] != '00:00:00') {
						$v['work_time'] .= '<span>' . $v['open_2'] . '-' . $v['close_2'] . '</span>';
						if ($v['open_2'] < $now_time && $now_time < $v['close_2']) {
							$v['state'] = 1;
						}
					}
					if ($v['open_3'] != '00:00:00' && $v['close_3'] != '00:00:00') {
						$v['work_time'] .= '<span>' . $v['open_3'] . '-' . $v['close_3'] . '</span>';
						if ($v['open_3'] < $now_time && $now_time < $v['close_3']) {
							$v['state'] = 1;
						}
					}
				}
			}
		}
		$return['meal_list'] = $res;
		$return['totalPage'] = $p->totalPage;
		$return['pagebar'] = $p->show();
		$return['meal_count'] = $total;
		return $return;
	}


	public function get_list_by_option_pc($area_id = 0, $circle_id = 0, $order = 'store_id', $cat_id = 0, $cat_fid = 0, $store_type = 2)
	{
// 		$condition_table = array(C('DB_PREFIX') . 'merchant_store' => 's', C('DB_PREFIX') . 'merchant_store_meal' => 'm');

		$condition_where = "`me`.`city_id`='".C('config.now_city')."' AND s.have_meal=1 AND s.status=1 AND s.store_id=m.store_id AND me.status=1";
		if (is_array($store_type)) {
			$condition_where .= " AND s.store_type IN (" . implode(',', $store_type) . ")";
		} else {
			$condition_where .= "  AND s.store_type={$store_type}";
		}
// 		$condition_field = "s.*, m.*";
// // 		if ($cat_id) {
// 			$condition_table[C('DB_PREFIX') . 'meal_store_category_relation'] = 'r';
// 			$condition_where .= " AND s.store_id=r.store_id AND r.cat_id={$cat_id}";
// 			$condition_field .= ", r.*";
// 		}
		$area_id && $condition_where .= " AND s.area_id={$area_id}";
		$circle_id && $condition_where .= " AND s.circle_id={$circle_id}";
		//排序
		switch($order){
			case 'price-asc':
				$order = '`m`.`mean_money` ASC,`s`.`store_id` DESC';
				break;
			case 'price-desc':
				$order = '`m`.`mean_money` DESC,`s`.`store_id` DESC';
				break;
			case 'hot':
				$order = '`m`.`sale_count` DESC,`s`.`store_id` DESC';
				break;
			case 'time':
				$order = '`s`.`last_time` DESC,`s`.`store_id` DESC';
				break;
			case 'store_id':
			default:
				$order = '`s`.`store_id` ASC';
		}


		import('@.ORG.group_page');
		$mod = new Model();
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
				return array('group_list' => null, 'pagebar' => null);
			}
		}
		$sql_count = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant AS me INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON me.mer_id=s.mer_id INNER JOIN " . C('DB_PREFIX') . "merchant_store_meal as m ON m.store_id=s.store_id WHERE {$condition_where}";
		$count = $mod->query($sql_count);
		$total = isset($count[0]['count']) ? $count[0]['count'] : 0;
		$p = new Page($total, C('config.meal_page_row'), C('config.meal_page_val'));
		$sql = "SELECT me.fans_count, s.*, m.* FROM " . C('DB_PREFIX') . "merchant AS me INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON me.mer_id=s.mer_id INNER JOIN " . C('DB_PREFIX') . "merchant_store_meal as m ON m.store_id=s.store_id WHERE {$condition_where} ORDER BY {$order} LIMIT {$p->firstRow}, {$p->listRows}";
		$res = $mod->query($sql);
// 		$count = D('')->table($condition_table)->where($condition_where)->count();

// 		$res = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		$ids = array();
		foreach ($res as $r) {
			if (!in_array($r['circle_id'], $ids)) {
				$ids[] = $r['circle_id'];
			}
		}
		$temp = array();
		if ($ids) {
			$areas = M("Area")->where(array('area_id' => array('in', $ids)))->select();
			foreach ($areas as $a) {
				$temp[$a['area_id']] = $a;
			}
		}
		$now_time = time();
		$store_image_class = new store_image();
		$open_list = array();
		$close_list = array();
		foreach ($res as $v) {
			$v['url'] = C('config.site_url').'/meal/'.$v['store_id'].'.html';
			$v['area_name'] = isset($temp[$v['circle_id']]) ? $temp[$v['circle_id']]['area_name'] : '';
			$images = $store_image_class->get_allImage_by_path($v['pic_info']);
			$v['image'] = $images ? array_shift($images) : array();

			$v['state'] = 0;//根据营业时间判断
			$v['work_time'] = '';
			if ($v['office_time']) {
				$now_time = time();
				foreach (unserialize($v['office_time']) as $time) {
					$v['work_time'] .= '<span>' . $time['open'] . '-' . $time['close'] . '</span>';
					$open = strtotime(date("Y-m-d ") . $time['open'] . ':00');
					$close = strtotime(date("Y-m-d ") . $time['close'] . ':00');
					if ($open < $now_time && $now_time < $close) {
						$v['state'] = 1;//根据营业时间判断
					}
				}
			} else {
				if ($v['open_1'] == '00:00:00' && $v['close_1'] == '00:00:00') {
					$v['work_time'] .= '<span>24小时营业</span>';
					$v['state'] = 1;
				} else {
					$now_time = date('H:i:s');
					$v['work_time'] .= '<span>' . $v['open_1'] . '-' . $v['close_1'] . '</span>';
					if ($v['open_1'] < $now_time && $now_time < $v['close_1']) {
						$v['state'] = 1;
					}
					if ($v['open_2'] != '00:00:00' && $v['close_2'] != '00:00:00') {
						$v['work_time'] .= '<span>' . $v['open_2'] . '-' . $v['close_2'] . '</span>';
						if ($v['open_2'] < $now_time && $now_time < $v['close_2']) {
							$v['state'] = 1;
						}
					}
					if ($v['open_3'] != '00:00:00' && $v['close_3'] != '00:00:00') {
						$v['work_time'] .= '<span>' . $v['open_3'] . '-' . $v['close_3'] . '</span>';
						if ($v['open_3'] < $now_time && $now_time < $v['close_3']) {
							$v['state'] = 1;
						}
					}
				}
			}
			if ($v['state']) {
				$open_list[] = $v;
			} else {
				$close_list[] = $v;
			}
		}

		if ($close_list) {
			foreach ($close_list as $cl) {
				$open_list[] = $cl;
			}
		}
		return array('group_list' => $open_list, 'pagebar' => $p->show());
	}


	/**
	 * 根据关键字获取商家列表
	 * @param array $where
	 * @param number $limit
	 */
	public function get_list_by_search($w, $order = 'store_id', $is_wap = false)
	{
		$where['have_meal'] = 1;
		$where['status'] = 1;
// 		$area_id && $where['area_id'] = $area_id;
// 		$condition_table = array(C('DB_PREFIX') . 'merchant_store' => 's', C('DB_PREFIX') . 'merchant_store_meal' => 'm');
		$condition_where = "s.have_meal=1 AND s.status=1";
		$condition_where .= " AND s.name like '%{$w}%'";
		//排序
		switch($order){
			case 'price-asc':
				$order = '`m`.`mean_money` ASC,`s`.`store_id` DESC';
				break;
			case 'price-desc':
				$order = '`m`.`mean_money` DESC,`s`.`store_id` DESC';
				break;
			case 'hot':
				$order = '`m`.`sale_count` DESC,`s`.`store_id` DESC';
				break;
// 			case 'rating':
// 				$order = '`g`.`score_mean` DESC,`g`.`group_id` DESC';
// 				break;
			case 'time':
				$order = '`s`.`last_time` DESC,`s`.`store_id` DESC';
				break;
			default:
				$order = '`s`.`store_id` ASC';
		}

		if(empty($is_wap)){
			import('@.ORG.group_page');
		}else{
			import('@.ORG.wap_group_search_page');
		}

// 		$count = D('Merchant_store')->where($where)->count();
		$Model = new Model();
		$sql = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant AS me INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON me.mer_id=s.mer_id AND me.status=1 INNER JOIN ". C('DB_PREFIX') . "merchant_store_foodshop AS m ON s.store_id=m.store_id WHERE {$condition_where}";
		$count = $Model->query($sql);
		$count = $count[0]['count'];
		$p = new Page($count, C('config.group_page_row'), C('config.group_page_val'));
		$sql = "SELECT me.fans_count,me.isverify, s.*, m.* FROM " . C('DB_PREFIX') . "merchant AS me INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON me.mer_id=s.mer_id AND me.status=1 INNER JOIN ". C('DB_PREFIX') . "merchant_store_foodshop AS m ON s.store_id=m.store_id WHERE {$condition_where} ORDER BY {$order} LIMIT {$p->firstRow}, {$p->listRows}";
		$res = $Model->query($sql);


		$ids = array();
		foreach ($res as $r) {
			if (!in_array($r['circle_id'], $ids)) {
				$ids[] = $r['circle_id'];
			}
		}
		$temp = array();
		if ($ids) {
			$areas = M("Area")->where(array('area_id' => array('in', $ids)))->select();
			foreach ($areas as $a) {
				$temp[$a['area_id']] = $a;
			}
		}
		$now_time = time();
		$store_image_class = new store_image();
		foreach ($res as &$v) {
			$v['url'] = $is_wap ? U('Wap/Foodshop/shop', array('mer_id' => $v['mer_id'], 'store_id' => $v['store_id'], 'keywords' => urlencode($w))) : C('config.site_url').'/meal/'.$v['store_id'].'.html';
			$v['area_name'] = isset($temp[$v['circle_id']]) ? $temp[$v['circle_id']]['area_name'] : '';
			$images = $store_image_class->get_allImage_by_path($v['pic_info']);
			$v['image'] = $images ? array_shift($images) : array();
			$v['mean_money'] = floatval($v['mean_money']);
			$v['state'] = 0;//根据营业时间判断
			$v['work_time'] = '';
			$v['search_name'] = str_replace($w, '<font color="#06c1ae">' . $w. '</font>', $v['name']);
			if ($v['office_time']) {
				foreach (unserialize($v['office_time']) as $time) {
					$v['work_time'] .= '<span>' . $time['open'] . '-' . $time['close'] . '</span>';
					$open = strtotime(date("Y-m-d ") . $time['open'] . ':00');
					$close = strtotime(date("Y-m-d ") . $time['close'] . ':00');
					if ($open < $now_time && $now_time < $close) {
						$v['state'] = 1;//根据营业时间判断
					}
				}
			} else {
				if ($v['open_1'] == '00:00:00' && $v['close_1'] == '00:00:00') {
					$v['work_time'] .= '<span>24小时营业</span>';
					$v['state'] = 1;
				} else {
					$now_time = date('H:i:s');
					$v['work_time'] .= '<span>' . substr($v['open_1'], 0, -3) . '-' . substr($v['close_1'], 0, -3) . '</span>';
					if ($v['open_1'] < $now_time && $now_time < $v['close_1']) {
						$v['state'] = 1;
					}
					if ($v['open_2'] != '00:00:00' && $v['close_2'] != '00:00:00') {
						$v['work_time'] .= '<span>' . substr($v['open_2'], 0, -3) . '-' . substr($v['close_2'], 0, -3) . '</span>';
						if ($v['open_2'] < $now_time && $now_time < $v['close_2']) {
							$v['state'] = 1;
						}
					}
					if ($v['open_3'] != '00:00:00' && $v['close_3'] != '00:00:00') {
						$v['work_time'] .= '<span>' . substr($v['open_3'], 0, -3) . '-' . substr($v['close_3'], 0, -3) . '</span>';
						if ($v['open_3'] < $now_time && $now_time < $v['close_3']) {
							$v['state'] = 1;
						}
					}
				}
			}

		}
		return array('group_list' => $res, 'pagebar' => $p->show(), 'meal_count' => $count);
	}

	public function get_hot_list($limit = 6, $lat = 0, $long = 0)
	{
		$condition_where = "`me`.`city_id`='".C('config.now_city')."' AND s.have_shop=1 AND s.status=1 AND me.status=1";
		if (C('config.store_shop_auth') == 1) {
			$condition_where .= " AND s.auth>2";
		}
		$Model = new Model();
		if ($lat && $long) {
			$order = "ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(($lat * PI() / 180- `s`.`lat` * PI()/180)/2),2)+COS($lat *PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(($long *PI()/180- `s`.`long`*PI()/180)/2),2)))*1000) ASC, s.sort DESC";
		} else {
			$order = "s.sort DESC";
		}
		$sql = "SELECT s.*, m.*, mm.isverify,me.fans_count FROM ". C('DB_PREFIX') . "merchant as me INNER JOIN ". C('DB_PREFIX') . "merchant_store AS s ON me.mer_id=s.mer_id INNER JOIN ". C('DB_PREFIX') . "merchant_store_shop AS m ON s.store_id=m.store_id LEFT JOIN ".C('DB_PREFIX')."merchant as mm ON mm.mer_id = s.mer_id WHERE {$condition_where} ORDER BY {$order} LIMIT 0, {$limit}";
		$res = $Model->query($sql);

		$ids = array();
		foreach ($res as $r) {
			if (!in_array($r['circle_id'], $ids)) {
				$ids[] = $r['circle_id'];
			}
		}
		$temp = array();
		if ($ids) {
			$areas = M("Area")->where(array('area_id' => array('in', $ids)))->select();
			foreach ($areas as $a) {
				$temp[$a['area_id']] = $a;
			}
		}

		$now_time = time();
		$store_image_class = new store_image();
		foreach ($res as &$v) {
			$v['url'] = C('config.site_url').'/shop/'.$v['store_id'].'.html';
			$v['area_name'] = isset($temp[$v['circle_id']]) ? $temp[$v['circle_id']]['area_name'] : '';
			$images = $store_image_class->get_allImage_by_path($v['pic_info']);
			$v['image'] = $images ? array_shift($images) : array();
			$v['fans_count'] = M('Merchant_user_relation')->where(array('mer_id'=>$v['mer_id']))->count();
			$v['state'] = 0;//根据营业时间判断
			$v['work_time'] = '';
			if ($v['office_time']) {
				foreach (unserialize($v['office_time']) as $time) {
					$v['work_time'] .= '<span>' . $time['open'] . '-' . $time['close'] . '</span>';
					$open = strtotime(date("Y-m-d ") . $time['open'] . ':00');
					$close = strtotime(date("Y-m-d ") . $time['close'] . ':00');
					if ($open < $now_time && $now_time < $close) {
						$v['state'] = 1;//根据营业时间判断
					}
				}
			} else {
				if ($v['open_1'] == '00:00:00' && $v['close_1'] == '00:00:00') {
					$v['work_time'] .= '<span>24小时营业</span>';
					$v['state'] = 1;
				} else {
					$now_time = date('H:i:s');
					$v['work_time'] .= '<span>' . $v['open_1'] . '-' . $v['close_1'] . '</span>';
					if ($v['open_1'] < $now_time && $now_time < $v['close_1']) {
						$v['state'] = 1;
					}
					if ($v['open_2'] != '00:00:00' && $v['close_2'] != '00:00:00') {
						$v['work_time'] .= '<span>' . $v['open_2'] . '-' . $v['close_2'] . '</span>';
						if ($v['open_2'] < $now_time && $now_time < $v['close_2']) {
							$v['state'] = 1;
						}
					}
					if ($v['open_3'] != '00:00:00' && $v['close_3'] != '00:00:00') {
						$v['work_time'] .= '<span>' . $v['open_3'] . '-' . $v['close_3'] . '</span>';
						if ($v['open_3'] < $now_time && $now_time < $v['close_3']) {
							$v['state'] = 1;
						}
					}
				}
			}
		}
		if ($lat && $long) {
			foreach ($res as &$v){
				$v['range'] = getRange(getDistance($v['lat'],$v['long'],$lat,$long));
			}
		}

		return $res;
	}

	public function wap_get_store_collect_list($uid){
		$condition_where = "`s`.`circle_id`=`a`.`area_id` AND `s`.`store_id`=`c`.`id` AND `c`.`type`='group_shop' AND `c`.`uid`='$uid'";
		$condition_table = array(C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'area'=>'a',C('DB_PREFIX').'user_collect'=>'c');
		$condition_field  = '`s`.*,`a`.`area_name`';
		$order = '`c`.`collect_id` DESC';

		import('@.ORG.wap_collect_page');
		$count_store = D('')->table($condition_table)->where($condition_where)->count();
		$p = new Page($count_store,10,'page');
		$store_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

		if($store_list){
			$store_image_class = new store_image();
			foreach($store_list as &$v){
				$images = $store_image_class->get_allImage_by_path($v['pic_info']);
				$v['list_pic'] = $images ? array_shift($images) : array();
			}
		}

		$return['store_list'] = $store_list;

		$return['pagebar'] = $p->show();

		return $return;
	}

	/*增加一次订餐店铺评论数*/
	public function setInc_meal_reply($store_id, $score){
		$store_meal = D("Merchant_store_meal")->where(array('store_id' => $store_id))->find();
		if ($store_meal) {
			$data = array('reply_count' => $store_meal['reply_count'] + 1, 'score_all' => $store_meal['score_all'] + $score);
			$data['score_mean'] = $data['score_all'] / $data['reply_count'];
			if (D("Merchant_store_meal")->where(array('store_id' => $store_id))->data($data)->save()) {
				return true;
			} else {
				return false;
			}
		} else {
			$data = array('reply_count' => 1, 'score_all' => $score);
			$data['score_mean'] = $data['score_all'] / $data['reply_count'];
			$data['store_id'] = $store_id;
			if (D("Merchant_store_meal")->add($data)) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function setInc_shop_reply($store_id, $score, $dscore){
		$store_shop = D("Merchant_store_shop")->where(array('store_id' => $store_id))->find();
		if ($store_shop) {
		    $data = array('reply_count' => $store_shop['reply_count'] + 1, 'score_all' => $store_shop['score_all'] + $score, 'reply_deliver_count' => $store_shop['reply_deliver_count'] + 1);
			$data['score_mean'] = $data['score_all'] / $data['reply_count'];
			$data['reply_deliver_score'] = ($store_shop['reply_deliver_count'] * $store_shop['reply_deliver_score'] + $dscore) / $data['reply_deliver_count'];
			if (D("Merchant_store_shop")->where(array('store_id' => $store_id))->data($data)->save()) {
				return true;
			} else {
				return false;
			}
		} else {
			$data = array('reply_count' => 1, 'score_all' => $score, 'reply_deliver_count' => 1, 'reply_deliver_score' => $dscore);
			$data['score_mean'] = $data['score_all'] / $data['reply_count'];
			$data['store_id'] = $store_id;
			if (D("Merchant_store_shop")->add($data)) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function setInc_foodshop_reply($store_id, $score){
		$store_shop = D("Merchant_store_foodshop")->where(array('store_id' => $store_id))->find();
		if ($store_shop) {
			$data = array('reply_count' => $store_shop['reply_count'] + 1, 'score_all' => $store_shop['score_all'] + $score);
			$data['score_mean'] = $data['score_all'] / $data['reply_count'];
			if (D("Merchant_store_foodshop")->where(array('store_id' => $store_id))->data($data)->save()) {
				return true;
			} else {
				return false;
			}
		} else {
			$data = array('reply_count' => 1, 'score_all' => $score);
			$data['score_mean'] = $data['score_all'] / $data['reply_count'];
			$data['store_id'] = $store_id;
			if (D("Merchant_store_foodshop")->add($data)) {
				return true;
			} else {
				return false;
			}
		}
	}
	public function setDec_meal_reply($reply)
	{
		$store_meal = D("Merchant_store_meal")->where(array('store_id' => $reply['parent_id']))->find();
		if ($store_meal) {
			$data['reply_count'] = max(0, $store_meal['reply_count'] - 1);
			$data['score_all'] = max(0, $store_meal['score_all'] - $reply['score']);
			if ($data['reply_count'] == 0 || $data['score_all'] == 0) {
				$data['score_mean'] = 0;
			} else {
				$data['score_mean'] = $data['score_all'] / $data['reply_count'];
			}
			if (D("Merchant_store_meal")->where(array('store_id' => $reply['parent_id']))->data($data)->save()) return true;
		}
		return false;
	}

	public function setDec_shop_reply($reply)
	{
		$store_shop = D("Merchant_store_shop")->where(array('store_id' => $reply['parent_id']))->find();
		if ($store_shop) {
			$data['reply_count'] = max(0, $store_shop['reply_count'] - 1);
			$data['score_all'] = max(0, $store_shop['score_all'] - $reply['score']);
			if ($data['reply_count'] == 0 || $data['score_all'] == 0) {
				$data['score_mean'] = 0;
			} else {
				$data['score_mean'] = $data['score_all'] / $data['reply_count'];
			}
			if (D("Merchant_store_shop")->where(array('store_id' => $reply['parent_id']))->data($data)->save()) return true;
		}
		return false;
	}

	public function setDec_foodshop_reply($reply)
	{
		$store_shop = D("Merchant_store_foodshop")->where(array('store_id' => $reply['parent_id']))->find();
		if ($store_shop) {
			$data['reply_count'] = max(0, $store_shop['reply_count'] - 1);
			$data['score_all'] = max(0, $store_shop['score_all'] - $reply['score']);
			if ($data['reply_count'] == 0 || $data['score_all'] == 0) {
				$data['score_mean'] = 0;
			} else {
				$data['score_mean'] = $data['score_all'] / $data['reply_count'];
			}
			if (D("Merchant_store_foodshop")->where(array('store_id' => $reply['parent_id']))->data($data)->save()) return true;
		}
		return false;
	}



	/*收藏列表*/
	public function get_meal_collect_list($uid){
		$now_time = $_SERVER['REQUEST_TIME'];
		$condition_where = "`m`.`store_id`=`c`.`id` AND `c`.`type`='meal_detail' AND `c`.`uid`='{$uid}'";
		$condition_table = array(C('DB_PREFIX').'merchant_store'=>'m',C('DB_PREFIX').'user_collect'=>'c');
		$condition_field  = '`m`.*';

		$order = '`c`.`collect_id` DESC';

		import('@.ORG.collect_page');
		$count_meal = D('')->table($condition_table)->where($condition_where)->count();
		$p = new Page($count_group,10,'page');
		$meal_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

		$return['pagebar'] = $p->show();

		if($meal_list){
			$store_image_class = new store_image();
			foreach($meal_list as &$v){
				$images = $store_image_class->get_allImage_by_path($v['pic_info']);
				$v['list_pic'] = $images ? array_shift($images) : array();
				$v['url'] = C('config.site_url').'/meal/'.$v['store_id'].'.html';
			}
		}
		$return['meal_list'] = $meal_list;

		return $return;
	}

	/*wap收藏列表*/
	public function wap_get_meal_collect_list($uid){
		$now_time = $_SERVER['REQUEST_TIME'];
		$condition_where = "`m`.`store_id`=`c`.`id` AND `c`.`type`='meal_detail' AND `c`.`uid`='{$uid}'";
		$condition_table = array(C('DB_PREFIX').'merchant_store'=>'m',C('DB_PREFIX').'user_collect'=>'c');
		$condition_field  = '`m`.*';

		$order = '`c`.`collect_id` DESC';

		import('@.ORG.wap_collect_page');
		$count_group = D('')->table($condition_table)->where($condition_where)->count();
		$p = new Page($count_group,10,'page');
		$group_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

		$return['pagebar'] = $p->show();

		if($meal_list){
			$store_image_class = new store_image();
			foreach($meal_list as &$v){
				$images = $store_image_class->get_allImage_by_path($v['pic_info']);
				$v['list_pic'] = $images ? array_shift($images) : array();
			}
		}
		$return['meal_list'] = $meal_list;

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


	public function wap_get_store_list_by_catid($cat_id, $area_id, $order, $lat, $long, $cat_url){

		$stores = D('Store_category')->field('store_id')->where("cat_id='$cat_id'")->select();
		foreach ($stores as $s) $store_ids[] = $s['store_id'];
		$store_ids && $where['store_id'] = array('in', $store_ids);
		if ($cat_url == 'dianying' && empty($store_ids)) {
			return false;
		}
// 		$where['have_meal'] = 1;
		$where['status'] = 1;
		$area_id && $where['area_id'] = $area_id;

		$count = D('Merchant_store')->where($where)->count();

		//排序
		switch($order){
			case 'distance':
				$order = "ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(($lat * PI() / 180- `lat` * PI()/180)/2),2)+COS($lat *PI()/180)*COS(`lat`*PI()/180)*POW(SIN(($long *PI()/180- `long`*PI()/180)/2),2)))*1000) ASC";//'`g`.`price` ASC,`g`.`group_id` DESC';
				break;
// 			case 'priceDesc':
// 				$order = '`g`.`price` DESC,`g`.`group_id` DESC';
// 				break;
// 			case 'solds':
// 				$order = '`g`.`sale_count` DESC,`g`.`group_id` DESC';
// 				break;
// 			case 'rating':
// 				$order = '`g`.`score_mean` DESC,`g`.`group_id` DESC';
// 				break;
// 			case 'start':
// 				$order = '`g`.`last_time` DESC,`g`.`group_id` DESC';
// 				break;
			default:
				$order = '`store_id` DESC';
		}

		import('@.ORG.wap_group_page');
		$p = new Page($count,C('config.group_page_row'),C('config.group_page_val'));
		$list = D('Merchant_store')->field(true)->where($where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
// 		echo D('Merchant_store')->_sql();
		$return['pagebar'] = $p->show();

		if($list){
			$store_image_class = new store_image();
			foreach($list as &$v){
				$images = $store_image_class->get_allImage_by_path($v['pic_info']);
				$v['image'] = $images ? array_shift($images) : '';

				$v['juli'] = ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(($lat * PI() / 180- $v['lat'] * PI()/180)/2),2)+COS($lat *PI()/180)*COS($v['lat']*PI()/180)*POW(SIN(($long *PI()/180- $v['long']*PI()/180)/2),2)))*1000);
				$v['juli'] = $v['juli'] > 1000 ? number_format($v['juli']/1000, 1) . 'km' : ($v['juli'] < 100 ? '<100m' : $v['juli'] . 'm');
			}
		}
		$return['store_list'] = $list;
		return $return;
	}

	public function wap_get_store_list_waimai( $lat, $long){
		if (!$lat || !$long) {
			return false;
		}
		$where['status'] = 1;
		$where['have_waimai'] = 1;
		$count = D('Merchant_store')->where($where)->count();
		$order = " `sort` desc ,ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(($lat * PI() / 180- `lat` * PI()/180)/2),2)+COS($lat *PI()/180)*COS(`lat`*PI()/180)*POW(SIN(($long *PI()/180- `long`*PI()/180)/2),2)))*1000) ASC";
		$list = D('Merchant_store')->field('store_id,name')->where($where)->order($order)->page(1,5)->select();

		$return['store_list'] = $list;
		return $return;
	}

}
?>