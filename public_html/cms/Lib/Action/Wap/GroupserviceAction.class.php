<?php

//团购 AJAX服务
class GroupserviceAction extends BaseAction{
    protected $send_time_type = array('分钟', '小时', '天', '周', '月');

    public function indexRecommendList()
    {
        $this->header_json();
        $page = $_GET['page'] ? $_GET['page'] : 0;
        $page_count = 10;
        
        $user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
        
        $content_type = $this->config['guess_content_type'];
        $limit = $this->config['guess_num'];
        $page_count = $limit < 10 ? $limit : 10;
        $page_all = 0;
        $page_max = floor($limit / $page_count) + ($limit % $page_count > 0 ? 1 : 0);
        
        if ($content_type == 'group') {
            $new_group_list = D('Group')->get_group_list('index_sort', $page . ',' . $page_count, true);
            $page_all = $page_max;
            // 判断是否微信浏览器，
            $group_list = array();
            foreach ($new_group_list as $storeGroupValue) {
                if ($new_group_list && $user_long_lat) {
                    $group_store_database = D('Group_store');
                    $rangeSort = array();
                    $tmpStoreList = $group_store_database->get_storelist_by_groupId($storeGroupValue['group_id']);
                    if ($tmpStoreList) {
                        foreach ($tmpStoreList as &$tmpStore) {
                            $tmpStore['Srange'] = getDistance($user_long_lat['lat'], $user_long_lat['long'], $tmpStore['lat'], $tmpStore['long']);
                            $tmpStore['range'] = getRange($tmpStore['Srange'], false);
                            $rangeSort[] = $tmpStore['Srange'];
                        }
                        array_multisort($rangeSort, SORT_ASC, $tmpStoreList);
                        $tmp['range'] = $tmpStoreList[0]['range'];
                        $tmp['Srange'] = $tmpStoreList[0]['Srange'];
                    }
                } else {
                    $tmp['range'] = '';
                }
                
                $tmp['group_id'] = $storeGroupValue['group_id'];
                $tmp['list_pic'] = $storeGroupValue['list_pic'];
                $tmp['group_name'] = $storeGroupValue['group_name'];
                $tmp['price'] = $storeGroupValue['price'];
                $tmp['old_price'] = $storeGroupValue['old_price'];
                $tmp['wx_cheap'] = $storeGroupValue['wx_cheap'];
                $tmp['pin_num'] = $storeGroupValue['pin_num'];
                $tmp['merchant_name'] = $storeGroupValue['merchant_name'];
                $tmp['s_name'] = $storeGroupValue['s_name'];
                $tmp['intro'] = $storeGroupValue['intro'];
                $tmp['tuan_type'] = $storeGroupValue['tuan_type'];
                $tmp['sale_txt'] = $storeGroupValue['sale_txt'];
                $tmp['url'] = $storeGroupValue['url'];
                $tmp['extra_pay_price'] = $storeGroupValue['extra_pay_price'];
                $group_list[] = $tmp;
            }
            $new_group_list = sortArrayAsc($group_list, 'Srange');
        } elseif ($content_type == 'shop') {
            $key = '';
            $order = 'juli';
            $deliver_type = 'all';
            $lat = isset($user_long_lat['lat']) ? $user_long_lat['lat'] : 0;
            $long = isset($user_long_lat['long']) ? $user_long_lat['long'] : 0;
            $cat_id = 0;
            $cat_fid = 0;
            $page = isset($_GET['page']) ? intval($_GET['page']) : 0;
            $where = array(
                'deliver_type' => $deliver_type,
                'order' => $order,
                'lat' => $lat,
                'long' => $long,
                'cat_id' => $cat_id,
                'cat_fid' => $cat_fid,
                'page' => $page
            );
            $key && $where['key'] = $key;
            
            $merchantStoreShopDB = D('Merchant_store_shop');
            $lists = $merchantStoreShopDB->get_list_by_option($where, 4);
            
            $return = array();
            $now_time = date('H:i:s');
            $n = 1;
            foreach ($lists['shop_list'] as $row) {
                if ($n > $page_count || ($page / 10) > $page_max || (($page / 10) == $page_max && $n > $limit % $page_count && $limit % $page_count != 0))
                    break;
                $n ++;
                $temp = array();
                $temp['store_id'] = $row['store_id'];
                $temp['name'] = $row['name'];
                $temp['store_theme'] = $row['store_theme'];
                $temp['isverify'] = $row['isverify'];
                $temp['juli_wx'] = $row['juli'];
                $temp['range'] = $row['range'];
                $temp['image'] = $this->config['site_url'] . '/index.php?c=Image&a=thumb&width=180&height=120&url=' . urlencode($row['image']);
                $temp['star'] = $row['score_mean'];
                $temp['month_sale_count'] = $row['sale_count'];
                $temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver']; // 是否支持配送
                $temp['delivery'] = $temp['delivery'] ? true : false;
                $temp['delivery_time'] = $row['send_time']; // 配送时长
                $temp['send_time_type'] = $row['send_time_type']; // 配送时长类型
                $temp['delivery_time_type'] = $this->send_time_type[$row['send_time_type']]; // 配送时长单位
                $temp['delivery_price'] = floatval($row['basic_price']); // 起送价
                $temp['delivery_money'] = floatval($row['delivery_fee']); // 配送费
                $temp['is_mult_class'] = $row['is_mult_class'];
                $temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false; // 是否是平台配送
                $temp['is_close'] = $row['state'] ? intval($row['is_close']) : 1;
                $temp['time'] = $merchantStoreShopDB->getBuniessName($row);
                
                $temp['coupon_list'] = array();
                if ($row['is_invoice']) {
                    $temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
                }
                if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
                    $temp['coupon_list']['discount'] = $row['store_discount'] / 10;
                }
                if ($row['isDiscountGoods']) {
                    $temp['coupon_list']['isDiscountGoods'] = 1;
                }
                if ($row['isdiscountsort']) {
                    $temp['coupon_list']['isdiscountsort'] = 1;
                }
                $system_delivery = array();
                foreach ($row['system_discount'] as $row_d) {
                    if ($row_d['type'] == 0) { // 新单
                        $temp['coupon_list']['system_newuser'][] = array(
                            'money' => floatval($row_d['full_money']),
                            'minus' => floatval($row_d['reduce_money'])
                        );
                    } elseif ($row_d['type'] == 1) { // 满减
                        $temp['coupon_list']['system_minus'][] = array(
                            'money' => floatval($row_d['full_money']),
                            'minus' => floatval($row_d['reduce_money'])
                        );
                    } elseif ($row_d['type'] == 2) { // 配送
                        if ($row_d['full_money'] > 0 && $row_d['reduce_money'] > 0) {
                            $system_delivery[] = array(
                                'money' => floatval($row_d['full_money']),
                                'minus' => floatval($row_d['reduce_money'])
                            );
                        }
                    }
                }
                foreach ($row['merchant_discount'] as $row_m) {
                    if ($row_m['type'] == 0) {
                        $temp['coupon_list']['newuser'][] = array(
                            'money' => floatval($row_m['full_money']),
                            'minus' => floatval($row_m['reduce_money'])
                        );
                    } elseif ($row_m['type'] == 1) {
                        $temp['coupon_list']['minus'][] = array(
                            'money' => floatval($row_m['full_money']),
                            'minus' => floatval($row_m['reduce_money'])
                        );
                    }
                }
                if ($row['deliver']) {
                    if ($temp['delivery_system']) {
                        $system_delivery && $temp['coupon_list']['delivery'] = $system_delivery;
                    } else {
                        if ($row['is_have_two_time']) {
                            if ($row['reach_delivery_fee_type2'] == 0) {
                                if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
                                    $temp['coupon_list']['delivery'][] = array(
                                        'money' => floatval($row['basic_price']),
                                        'minus' => floatval($row['delivery_fee2'])
                                    );
                                }
                            } elseif ($row['reach_delivery_fee_type2'] == 1) {
                                // $temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                            } elseif ($row['reach_delivery_fee_type2'] == 2) {
                                $row['delivery_fee2'] && $temp['coupon_list']['delivery'][] = array(
                                    'money' => floatval($row['no_delivery_fee_value2']),
                                    'minus' => floatval($row['delivery_fee2'])
                                );
                            }
                        } else {
                            if ($row['reach_delivery_fee_type'] == 0) {
                                if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
                                    $temp['coupon_list']['delivery'][] = array(
                                        'money' => floatval($row['basic_price']),
                                        'minus' => floatval($row['delivery_fee'])
                                    );
                                }
                            } elseif ($row['reach_delivery_fee_type'] == 1) {
                                // $temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                            } elseif ($row['reach_delivery_fee_type'] == 2) {
                                $row['delivery_fee'] && $temp['coupon_list']['delivery'][] = array(
                                    'money' => floatval($row['no_delivery_fee_value']),
                                    'minus' => floatval($row['delivery_fee'])
                                );
                            }
                        }
                    }
                }
                $temp['coupon_count'] = count($temp['coupon_list']);
                
                $return[] = $temp;
            }
            $new_group_list = $return;
        } elseif ($content_type == 'meal') {
            $this->header_json();
            $circle_id = 0;
            $area_id = 0;
            // 判断排序信息
            $sort_id = ! empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';
            $cat_url = isset($_GET['cat_url']) ? intval($_GET['cat_url']) : - 1;
            $params['area_id'] = $area_id;
            $params['circle_id'] = $circle_id;
            $params['sort'] = $sort_id;
            $params['lat'] = $user_long_lat['lat'];
            $params['long'] = $user_long_lat['long'];
            $params['cat_fid'] = 0;
            $params['cat_id'] = 0;
            $params['queue'] = - 1;
            $params['page'] = $page;
            
            $return = D('Merchant_store_foodshop')->wap_get_storeList_by_catid($params, 2);
            
            $page_all = $return['totalPage'] > $page_max ? $page_max : $return['totalPage'];
            $n = 1;
            if (! empty($return)) {
                foreach ($return['store_list'] as $v) {
                    if ($n > $page_count || $page > $page_max || ($page == $page_max && $n > $limit % $page_count && $limit % $page_count != 0))
                        break;
                    $n ++;
                    if ($v['discount_txt']['discount_type'] == 1) {
                        $v['discount_txt'] = $v['discount_txt']['discount_percent'] . '折';
                    } else if ($v['discount_txt']['discount_type'] == 2) {
                        $v['discount_txt'] = '每满' . $v['discount_txt']['condition_price'] . '减' . $v['discount_txt']['minus_price'] . '元';
                    } else {
                        $v['discount_txt'] = '';
                    }
                    
                    $tmp_store_list[] = $v;
                }
                if (empty($return)) {
                    foreach ($return['store_list'] as $v) {
                        if ($n > $page_count)
                            break;
                        $n ++;
                        $tmp_store_list[] = $v;
                    }
                    $return['store_list'] = $tmp_store_list;
                }
            }
            $new_group_list = $return;
        } elseif ($content_type == 'store') {
            $lat = isset($user_long_lat['lat']) ? $user_long_lat['lat'] : 0;
            $long = isset($user_long_lat['long']) ? $user_long_lat['long'] : 0;
            $return = D('Merchant')->wap_get_storeList_by_catid(0, 0, 0, 'juli', $lat, $long, 0, '');//guess_you_like($long, $lat, $limit);
            $n = 1;
            $newList = array();
            foreach ($return['store_list'] as $storeValue) {
                if ($n > $page_count || ($page / 10) > $page_max || (($page / 10) == $page_max && $n > $limit % $page_count && $limit % $page_count != 0)) {
                    break;
                }
                $n ++;
                if ($user_long_lat && $storeValue['juli']) {
                    $storeValue['range_txt'] = $this->wapFriendRange($storeValue['juli']);
                } else {
                    $storeValue['range_txt'] = '';
                }
                $storeValue['fans_count'] = M('Merchant_user_relation')->where(array('mer_id' => $storeValue['mer_id']))->count();
                if ($this->config['appoint_site_logo']) {
                    $storeValue['now_appoint'] = D('Appoint')->get_appointlist_by_StoreId($storeValue['store_id'], true, 2);
                }
                $storeValue['pay_in_store'] = C('config.pay_in_store');
                $storeValue['discount_txt'] = $storeValue['discount_txt'] ? unserialize($storeValue['discount_txt']) : array();
                $storeValue['store_pay'] = $this->config['site_url'] . str_replace('appapi.php', 'wap.php', U('My/pay', array('store_id' => $storeValue['store_id'])));
                $newList[] = $storeValue;
            }
            $return['store_list'] = $newList;
            $new_group_list = $return;
        }

        if (!empty($new_group_list)) {
            echo json_encode($new_group_list);
        } else {
            echo json_encode(array('nomore' => 1));
        }
    }
	//得到搜索的团购列表
	public function search(){
		$this->header_json();
		$group_return = D('Group')->get_group_list_by_keywords($_GET['w'],$_GET['sort'],true);
		echo json_encode($group_return);
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

	
    public function mall()
    {
        $flist = D('Goods_category')->field('id')->where(array('fid' => 0, 'is_hot' => 1))->order('sort DESC, id DESC')->select();
        $fids = array();
        foreach ($flist as $row) {
            $fids[] = $row['id'];
        }
        $data = array();
        if ($fids) {
            $list = D('Goods_category')->field(true)->where(array('fid' => array('in', $fids), 'is_hot' => 1))->order('sort DESC, id DESC')->select();
            foreach ($list as $val) {
                if (isset($data[$val['fid']])) {
                    $data[$val['fid']][] = $val;
                } else {
                    $data[$val['fid']] = array($val);
                }
            }
        }
        exit(json_encode(array('error' => 0, 'data' => $data)));
    }
    
    public function mallGoods()
    {
        $cateId = isset($_POST['cateId']) ? intval($_POST['cateId']) : 0;
        $flist = D('Goods_category')->field('fid')->where(array('id' => $cateId, 'is_hot' => 1))->find();
        $fid = isset($flist['fid']) ? intval($flist['fid']) : 0;
        
        $sql = "SELECT `g`.`goods_id`, `g`.`name`, `g`.`image`, `g`.`price`, `g`.`sell_count` ,`sh`.`score_mean` FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `s`.`status`=1 AND `s`.`have_shop`=1 AND `sh`.`store_theme`=1 AND `s`.`store_id`=`sh`.`store_id` INNER JOIN " . C('DB_PREFIX') . "shop_goods AS g ON `sh`.`store_id`=`g`.`store_id` AND `s`.`status`=1 WHERE g.cat_id={$cateId} LIMIT 20";
		
		$goodsList = D()->query($sql);
		
        // $goodsList = D('Shop_goods')->field(true)->where(array('status' => 1, 'cat_id' => $cateId))->limit(20)->select();
		
        $goods_image_class = new goods_image();
        $return = array();
        
        foreach ($goodsList as $goods) {
            if (empty($fid)) {
                $fid = $goods['cat_fid'];
            }
            $tmp_pic_arr = explode(';', $goods['image']);
            $image = '';
            foreach ($tmp_pic_arr as $v) {
                if (empty($image)) {
                    $image = $goods_image_class->get_image_by_path($v, 's');
                }
            }
            $goods['image'] = $image;
            $goods['score_mean'] = round($goods['score_mean']/5 * 100, 2) . '%';
            $goods['url'] = U('Mall/detail', array('goods_id' => $goods['goods_id']));
            $return[] = $goods;
        }
        
        exit(json_encode(array('error' => 0, 'data' => $return, 'fid' => $fid)));
    }
}

?>