<?php
class TakeoutAction extends BaseAction
{
	public $store_id = 0;
	
	public $_store = null;
	
	public $session_index = '';
	public function __construct()
	{
		parent::__construct();
		$this->store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
		$this->assign('store_id', $this->store_id);
		
		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($this->mer_id,array('meal_hits'=>1));
		
		$merchant_store = M("Merchant_store")->where(array('store_id' => $this->store_id))->find();
		if ($merchant_store['office_time']) {
			$merchant_store['office_time'] = unserialize($merchant_store['office_time']);
		}
		$is_redirect_shop = 0;
		if ($merchant_store['have_shop']) {
			if ($shop = D('Merchant_store_shop')->field(true)->where(array('mer_id' => $merchant_store['mer_id'], 'store_id' => $merchant_store['store_id']))->find()) {
				$is_redirect_shop = $shop['close_old_store'];
			}
			if ($merchant_store['store_type'] == 3) $is_redirect_shop = 1;
		}
		
		if ($is_redirect_shop) {
			$this->redirect(U('Shop/index', array('shop-id' => $merchant_store['store_id'])));
		}
		
		
		$store_image_class = new store_image();
		$merchant_store['images'] = $store_image_class->get_allImage_by_path($merchant_store['pic_info']);
		$t = $merchant_store['images'];
		$merchant_store['image'] = array_shift($t);
		
		$merchant_store_meal = M("Merchant_store_meal")->where(array('store_id' => $this->store_id))->find();
		if ($merchant_store_meal) $merchant_store = array_merge($merchant_store, $merchant_store_meal);
		$this->leveloff=!empty($merchant_store_meal['leveloff']) ? unserialize($merchant_store_meal['leveloff']) :'';
		$this->_store = $merchant_store;
		if (empty($merchant_store['office_time'])) {
			if($merchant_store['open_1'] != '00:00:00' && $merchant_store['close_1'] != '00:00:00'){
				$merchant_store['office_time'][] = array('open' => $merchant_store['open_1'], 'close' => $merchant_store['close_1']);
			}
			if($merchant_store['open_2'] != '00:00:00' && $merchant_store['close_2'] != '00:00:00'){
				$merchant_store['office_time'][] = array('open' => $merchant_store['open_2'], 'close' => $merchant_store['close_2']);
			}
			if($merchant_store['open_3'] != '00:00:00' && $merchant_store['close_3'] != '00:00:00'){
				$merchant_store['office_time'][] = array('open' => $merchant_store['open_3'], 'close' => $merchant_store['close_3']);
			}
		}
		$this->assign('store', $merchant_store);
		$this->session_index = "session_takeout_menu_{$this->store_id}_{$this->mer_id}";
		if ($_SESSION['openid'] && $services = D('Customer_service')->where(array('mer_id' => $this->mer_id))->select()) {
			$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
			$kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $this->mer_id;
			$this->assign('kf_url', $kf_url);
		}
	}
	
	public function index()
	{
		$searhkey = isset($_GET['searhkey']) ? htmlspecialchars($_GET['searhkey']) : '';
		$this->assign('searhkey', $searhkey);
		$this->display();
	}

	public function store_list()
	{
		$searhkey = isset($_REQUEST['searhkey']) ? htmlspecialchars($_REQUEST['searhkey']) : '';
		$where = 's.have_meal=1 AND s.status=1 AND s.store_type<>1';
		$searhkey && $where .= " AND s.name like '%{$searhkey}%'";
		$model = new Model();
		$long_lat = array('lat' => 0, 'long' => 0);
		$_SESSION['openid'] && $long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		if (!empty($long_lat['long']) && !empty($long_lat['lat'])) {
			import('@.ORG.longlat');
			$longlat_class = new longlat();
			$location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
			$lat = $location2['lat'];
			$long = $location2['lng'];
			$sql = "SELECT s.*,ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli FROM ". C('DB_PREFIX') . "merchant_store AS s INNER JOIN ". C('DB_PREFIX') . "merchant_store_meal AS m ON s.store_id=m.store_id WHERE {$where} ORDER BY s.store_id DESC";
		}else{
			$sql = "SELECT s.* FROM ". C('DB_PREFIX') . "merchant_store AS s INNER JOIN ". C('DB_PREFIX') . "merchant_store_meal AS m ON s.store_id=m.store_id WHERE {$where} ORDER BY s.store_id DESC";
		}
		$stores = $model->query($sql);
		
		$store_image_class = new store_image();
    	$list = array();
    	foreach ($stores as $row) {
    		$row['position'] = array('lng' => $row['long'], 'lat' => $row['lat']);
    		$row['state'] = 0;//根据营业时间判断
    		if (empty($row['office_time'])) {
    			$now_time = date('H:i:s');
    			if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
    				$row['state'] = 1;
    			} else {
    				if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
    					$row['state'] = 1;
    				}
    				if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
    					$row['state'] = 1;
    				}
    				if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
    					$row['state'] = 1;
    				}
    			}
    		} else {
    			$now_time = time();
    			foreach (unserialize($row['office_time']) as $time) {
    				$open = strtotime(date("Y-m-d ") . $time['open'] . ':00');
    				$close = strtotime(date("Y-m-d ") . $time['close'] . ':00');
    				if ($open > $close) $close += 86400;
    				if ($open < $now_time && $now_time < $close) {
    					$row['state'] = 1;//根据营业时间判断
    					break;
    				}
    			}
    		}

    		$row['dist'] = 0;
			$row['distjuli'] = isset($row['juli']) ? intval($row['juli']) : 0;
    		$row['ctime'] = 0;
    		$row['tel'] = $row['phone'];
    		$row['address'] = $row['adress'];
    		$images = $store_image_class->get_allImage_by_path($row['pic_info']);
    		$row['img'] = array_shift($images);
    		
    		$row['url'] = U('Takeout/menu', array('mer_id' => $row['mer_id'], 'store_id' => $row['store_id']));//'wap.php?mod=takeout&action=menu&com_id=' . $row['com_id'] . '&id=' . $row['id'];
    		$list[] = $row;
    	}
    	exit(json_encode(array('result' => 1, 'message' => 'success', 'data' => $list)));
	}
	
	public function shop()
	{
		$now_city = D('Area')->get_area_by_areaId($this->config['now_city']);
		$this->assign('city_name',$now_city['area_name']);
		if($this->_store['reply_count']){
			$reply_list = D('Reply')->get_reply_list($this->_store['store_id'], 1, 0, 10);
			$this->assign('reply_list',$reply_list);
		}
		$this->display();
	}
	
	public function ajaxreply()
	{
		$page = isset($_GET['page']) ? intval($_GET['page']) : 2;
		$pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : 10;
		$start = ($page - 1) * $pagesize;
		$reply_list = D('Reply')->ajax_reply_list($this->_store['store_id'], 1, 0, $pagesize, $start);
		exit(json_encode(array('data' => $reply_list)));
	}
	
    /**
     * 点餐页
     */
    public function menu() 
    {
		if (empty($this->_store)) {
			$this->error_tips("不存在的商家店铺!");
		}
		if ($this->_store['status'] != 1 || empty($this->_store['have_meal'])) {
			$this->error('您查看的店铺已关闭！');
		}
		$flag = true;
		if ($this->_store['office_time']) {
			$now_time = time();
			foreach ($this->_store['office_time'] as $time) {
				$open = strtotime(date("Y-m-d ") . $time['open'] . ':00');
				$close = strtotime(date("Y-m-d ") . $time['close'] . ':00');
				if ($open < $now_time && $now_time < $close) {
					$flag = false;
					break;
				}
			}
			if ($flag) {
				$this->error_tips('抱歉！当前不在营业时间内！');
			}
		} else {
			if ($this->_store['open_1'] == '00:00:00' && $this->_store['close_1'] == '00:00:00') {
				$flag = false;
			} else {
				$now_time = date('H:i:s');
				if ($this->_store['open_1'] < $now_time && $now_time < $this->_store['close_1']) {
					$flag = false;
				}
				if ($this->_store['open_2'] < $now_time && $now_time < $this->_store['close_2']) {
					$flag = false;
				}
				if ($this->_store['open_3'] < $now_time && $now_time < $this->_store['close_3']) {
					$flag = false;
				}
			}
			if ($flag) {
				$this->error_tips('抱歉！当前不在营业时间内！');
			}
		}
		
		$sorts = M("Meal_sort")->where(array('store_id' => $this->store_id))->order('`sort` DESC, `sort_id` ASC')->select();
		$t_meals = $meals = $list = array();
		$ids = array();
		foreach ($sorts as $sort) {
			if ($sort['is_weekshow']) {
				$week = explode(",", $sort['week']);
				if (in_array(date("w"), $week)) {
					$list[$sort['sort_id']] = $sort;
					$ids[] = $sort['sort_id'];
				}
			} else {
				$list[$sort['sort_id']] = $sort;
				$ids[] = $sort['sort_id'];
			}
		}

		$disharr = unserialize($_SESSION[$this->session_index]);
		
		$nowMouth = date('Ym');
		
		$meal_image_class = new meal_image();
		$temp = M("Meal")->where(array('store_id' => $this->store_id, 'sort_id' => array('in', $ids), 'status' => 1))->order('sort DESC')->select();
		
		$MOOBJ = D('Meal_order');
		
		foreach ($temp as $m) {
			if (isset($disharr[$m['meal_id']])) {
				$m['num'] = $disharr[$m['meal_id']]['num'];
			} else {
				$m['num'] = 0;
			}
			
// 			if ($m['sell_mouth'] != $nowMouth) $m['sell_count'] = 0;//跨月销售额清零
			$check_stock = $MOOBJ->check_stock($m['meal_id']);
			$m['max'] = $check_stock['stock_num'];
			
			$m['image'] = $meal_image_class->get_image_by_path($m['image'],$this->config['site_url'],'s');
			if (isset($t_meals[$m['sort_id']]['list'])) {
				$t_meals[$m['sort_id']]['list'][] = $m;
			} else {
				$t_meals[$m['sort_id']]['list'] = array($m);
				$t_meals[$m['sort_id']]['sort_id'] = $m['sort_id'];
				$t_meals[$m['sort_id']]['sort_name'] = $list[$m['sort_id']]['sort_name'];
			}
		}
		foreach ($ids as $sort_id) {
			if (isset($t_meals[$sort_id])) {
				$meals[$sort_id] = $t_meals[$sort_id];
			} else {
 				unset($list[$sort_id]);
			}
		}
		
		if ($this->_store['delivery_fee_valid']) {//不足起送价同样还是送
// 			$this->_store['basic_price'] = 0;
			$this->assign('store', $this->_store);
			
		}
		/* 粉丝行为分析 */
		$this->behavior(array('mer_id' => $this->mer_id, 'biz_id' => $this->store_id,'keyword'=>strval($_GET['keywords'])));
		
		$this->assign('meals', $meals);
		$this->assign("sortlist", $list);
		$this->display();
    }

    /**
     * 订单信息确认
     */
    public function sureOrder() 
    {
    	if (empty($this->_store)) {
			$this->error_tips("不存在的商家店铺!", U('Takeout/index', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id)));
		}
		$MOOBJ = D('Meal_order');
        $dishtmp = $_POST['dish'];
        if (empty($dishtmp)) {
        	$disharr = unserialize($_SESSION[$this->session_index]);
        } else {
	        $disharr = array();
			foreach ($dishtmp as $id => $num) {
				$num = $num ? intval($num) : 0;
				if ($num > 0) {
					$check_stock = $MOOBJ->check_stock($id);
					if ($check_stock['stock_num'] > -1 && $check_stock['stock_num'] < $num) {
						$this->error_tips('您购买的' . $check_stock['name'] . '超出了库存量！', U('Takeout/menu', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id)));
					}
					$disharr[$id] = array('id' => $id, 'num' => $num);
				}
			}
        }
		if (empty($disharr)) {
			$this->error_tips('您尚未点菜！', U('Takeout/menu', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id)));
		}
		$_SESSION[$this->session_index] = serialize($disharr);
		$this->isLogin();
		
		$merchant_store_meal = M("Merchant_store_meal")->where(array('store_id' => $this->store_id, 'mer_id' => $this->mer_id))->find();
		$merchant_store = array_merge($this->_store, $merchant_store_meal);
		
		$flag = true;
		$time_list = array();
		if ($merchant_store['office_time']) {
			$now_time = time();
			
			foreach ($merchant_store['office_time'] as $time) {
				$open = strtotime(date("Y-m-d ") . $time['open'] . ':00');
				$close = strtotime(date("Y-m-d ") . $time['close'] . ':00');
				if ($open > $close) $close += 86400;
				if ($now_time < $open) {
					$t = $this->get_time_list($open, $close);
					$time_list = array_merge($time_list, $t);
				} elseif ($open <= $now_time && $now_time <= $close) {
					$flag = false;
					$t = $this->get_time_list($now_time, $close);
					$time_list = array_merge($time_list, $t);
				}
		
			}
			if ($flag) {
				$this->error_tips('抱歉！尚未不在营业时间内！', U('Takeout/menu', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id)));
			}
		} else {
			if ($merchant_store['open_1'] == '00:00:00' && $merchant_store['close_1'] == '00:00:00') {
				$flag = false;

				$open = strtotime(date("Y-m-d 00:00:01"));
				$close = strtotime(date("Y-m-d 23:59:59"));
				
				$time_list = $this->get_time_list($open, $close);
			} else {
				$now_time = date('H:i:s');
				$nowtime = time();
				if ($now_time < $merchant_store['open_1']) {
					$open = strtotime(date("Y-m-d ") . $merchant_store['open_1']);
					$close = strtotime(date("Y-m-d ") . $merchant_store['close_1']);
					$t = $this->get_time_list($open, $close);
					$time_list = array_merge($time_list, $t);
				} elseif ($merchant_store['open_1'] < $now_time && $now_time < $merchant_store['close_1']) {
					$close = strtotime(date("Y-m-d ") . $merchant_store['close_1']);
					$t = $this->get_time_list($nowtime, $close);
					$time_list = array_merge($time_list, $t);
					$flag = false;
				}
				if ($now_time < $merchant_store['open_2']) {
					$open = strtotime(date("Y-m-d ") . $merchant_store['open_2']);
					$close = strtotime(date("Y-m-d ") . $merchant_store['close_2']);
					$t = $this->get_time_list($open, $close);
					$time_list = array_merge($time_list, $t);
				} elseif ($merchant_store['open_2'] < $now_time && $now_time < $merchant_store['close_2']) {
					$close = strtotime(date("Y-m-d ") . $merchant_store['close_2']);
					$t = $this->get_time_list($nowtime, $close);
					$time_list = array_merge($time_list, $t);
					$flag = false;
				}
				if ($now_time < $merchant_store['open_3']) {
					$open = strtotime(date("Y-m-d ") . $merchant_store['open_3']);
					$close = strtotime(date("Y-m-d ") . $merchant_store['close_3']);
					$t = $this->get_time_list($open, $close);
					$time_list = array_merge($time_list, $t);
				} elseif ($merchant_store['open_3'] < $now_time && $now_time < $merchant_store['close_3']) {
					$close = strtotime(date("Y-m-d ") . $merchant_store['close_1']);
					$t = $this->get_time_list($nowtime, $close);
					$time_list = array_merge($time_list, $t);
					$flag = false;
				}
			}
			if ($flag) {
				$this->error_tips('抱歉！当前不在营业时间内！');
			}
		}
		$ids = array_keys($disharr);
		$meal_image_class = new meal_image();
		$temp = M("Meal")->where(array('store_id' => $this->store_id, 'meal_id' => array('in', $ids), 'status' => 1))->select();
		$t_menus = array();
		foreach ($temp as $tm) {
			$t_menus[$tm['meal_id']] = $tm;
		}
		
		$info = array();
		foreach ($ids as $id) {
			$m = $t_menus[$id];
			$m['image'] = $meal_image_class->get_image_by_path($t_menus[$id]['image'],$this->config['site_url'],'s');

			if (isset($disharr[$id])) {
				$m['num'] = $disharr[$id]['num'];
			} else {
				$m['num'] = 0;
			}
			$check_stock = $MOOBJ->check_stock($id);
			$m['max'] = $check_stock['stock_num'];
			
			$meals[] = $m;
		}
		
		
		
		
// 		foreach ($temp as $m) {
// 			if (isset($disharr[$m['meal_id']])) {
// 				$m['num'] = $disharr[$m['meal_id']]['num'];
// 			} else {
// 				$m['num'] = 0;
// 			}
// 			$m['image'] = $meal_image_class->get_image_by_path($m['image'],$this->config['site_url'],'s');
// 			$meals[] = $m;
// 		}
		/* 粉丝行为分析 */
		$this->behavior(array('mer_id' => $this->mer_id, 'biz_id' => $this->store_id,'keyword'=>strval($_GET['keywords'])));
		

		$now_group['user_adress'] = D('User_adress')->get_one_adress($this->user_session['uid'],intval($_GET['adress_id']));
			
		if ($this->_store['delivery_fee_valid']) {//不足起送价同样还是送
// 			$this->_store['basic_price'] = 0;
			$this->assign('store', $this->_store);
				
		}
		
		$this->assign('time_list', $time_list);
		$this->assign('now_group', $now_group);
		$this->assign('meals', $meals);
		$this->display();
    }
	
    private function get_time_list($startime, $endtime)
    {
    	if ($startime > $endtime) return null;
    	if (date('Y-m-d') == '1970-01-01') return null;
    	$s00 = strtotime(date('Y-m-d H:00:00', $startime));
    	$s15 = strtotime(date('Y-m-d H:15:00', $startime));
    	$s30 = strtotime(date('Y-m-d H:30:00', $startime));
    	$s45 = strtotime(date('Y-m-d H:45:00', $startime));
    	$s = 0;
    	if ($s00 >= $startime) {
    		$s = $s00;
    	} elseif ($s15 >= $startime) {
    		$s = $s15;
    	} elseif ($s30 >= $startime) {
    		$s = $s30;
    	} elseif ($s45 >= $startime) {
    		$s = $s45;
    	} else {
    		$s = $s00 + 3600;
    	}
    	for ($i = $s; $i <= $endtime;) {
    		$time_list[] = date('H:i', $i);
    		$i += 900;
    	}
    	return $time_list;
    }
    
	public function OrderPay()
	{
		$this->isLogin();
		if (IS_POST) {
			$phone = isset($_POST['ouserTel']) ? htmlspecialchars($_POST['ouserTel']) : '';
			$name = isset($_POST['ouserName']) ? htmlspecialchars($_POST['ouserName']) : '';
			$address = isset($_POST['ouserAddres']) ? htmlspecialchars($_POST['ouserAddres']) : '';
			$address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
			$arrive_time = isset($_POST['oarrivalTime']) ? htmlspecialchars($_POST['oarrivalTime']) : 0;
			$note = isset($_POST['omark']) ? htmlspecialchars($_POST['omark']) : '';
			if (empty($name)) $this->error_tips('联系人不能为空');
			if (empty($phone)) $this->error_tips('联系电话不能为空');
			$goodsData = isset($_POST['dish']) ? $_POST['dish'] : null;
			if (empty($goodsData)) $this->error_tips('您还没有点菜');
			if ($user_address = D('User_adress')->field(true)->where(array('adress_id' => $address_id, 'uid' => $this->user_session['uid']))->find()) {
				if ($user_address['longitude'] > 0 && $user_address['latitude'] > 0) {
					$distance = $this->getDistance($user_address['latitude'], $user_address['longitude'], $this->_store['lat'], $this->_store['long']);
					$delivery_radius = $this->_store['delivery_radius'] * 1000;
					if ($distance > $delivery_radius) {
						//$this->error_tips('您要送达的地址不在本店的配送范围内');
					}
				} else {
					//$this->error_tips('该地址需要您重新完善一下');
				}
			}
			if ($arrive_time) {
				$flag = true;
				if (empty($this->_store['office_time'])) {
					$now_time = date('H:i:s');
					if ($this->_store['open_1'] == '00:00:00' && $this->_store['close_1'] == '00:00:00') {
						$flag = false;
					} else {
						if ($this->_store['open_1'] < $now_time && $now_time < $this->_store['close_1']) {
							$flag = false;
						}
						if ($flag && $this->_store['open_2'] != '00:00:00' && $this->_store['close_2'] != '00:00:00') {
							if ($this->_store['open_2'] < $now_time && $now_time < $this->_store['close_2']) {
								$flag = false;
							}
						}
						if ($flag && $this->_store['open_3'] != '00:00:00' && $this->_store['close_3'] != '00:00:00') {
							if ($this->_store['open_3'] < $now_time && $now_time < $this->_store['close_3']) {
								$flag = false;
							}
						}
					}
				} else {
					$arrive_time = strtotime(date("Y-m-d " . $arrive_time . ":00"));
					foreach ($this->_store['office_time'] as $time) {
						$open = strtotime(date("Y-m-d ") . $time['open'] . ':00');
						$close = strtotime(date("Y-m-d ") . $time['close'] . ':00');
						if ($open > $close) $close += 86400;
						if ($open <= $arrive_time && $close <= $arrive_time) {
							$flag = false;
						}
					}
				}
				if ($flag) {
					$this->error_tips('抱歉！尚未不在营业时间内！');
				}
			}
			
			$meal = array_keys($goodsData);
			$total = $price = 0;
			$total_money = 0;
			$total_discount_money = 0;
			$vip_discount_money = 0;
			$store_discount_money = 0;
			
			if ($meal) {
				//店铺优惠条件
				$sorts_discout = D('Meal_sort')->get_sorts($this->store_id);
				
				$meals = M("Meal")->where(array('meal_id' => array('in', $meal), 'store_id' => $this->store_id))->select();
				$MOOBJ = D('Meal_order');
				$t_menus = array();
				foreach ($meals as $tm) {
					$t_menus[$tm['meal_id']] = $tm;
				}
				$info = array();
				foreach ($meal as $id) {
					if (0 == intval($goodsData[$id]['num'])) continue;
					
					$check_stock = $MOOBJ->check_stock($id);
					if ($check_stock['stock_num'] > -1 && $check_stock['stock_num'] < $goodsData[$id]['num']) {
						$this->error_tips('您购买的' . $check_stock['name'] . '超出了库存量！', U('Takeout/menu', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id)));
					}
					
					$info[] = array('id' => $id,'name' => $t_menus[$id]['name'], 'num' => $goodsData[$id]['num'], 'price' => $t_menus[$id]['price'], 'iscount' => 0);
					$total += $goodsData[$id]['num'];
					$total_money += $goodsData[$id]['num'] * $t_menus[$id]['price'];
					
					$t_discount = isset($sorts_discout[$t_menus[$id]['sort_id']]) && $sorts_discout[$t_menus[$id]['sort_id']] ? $sorts_discout[$t_menus[$id]['sort_id']] : 100;
					
					$store_discount_money += $goodsData[$id]['num'] * $t_menus[$id]['price'] * $t_discount / 100;
				}
				
// 				$info = array();
// 				foreach ($meals as $m) {
// 					$info[] = array('id' => $m['meal_id'],'name' => $m['name'], 'num' => $goodsData[$m['meal_id']]['num'], 'price' => $m['price']);
// 					$total += $goodsData[$m['meal_id']]['num'];
// 					$price += $goodsData[$m['meal_id']]['num'] * $m['price'];
// 				}
			}
			if (empty($this->_store['delivery_fee_valid']) && $total_money < $this->_store['basic_price']) {
				$this->error_tips('您的外卖总金额没有达到起步价');
			}
			
			$delivery_fee = 0;
			if ($this->_store['delivery_fee'] > 0) {//外卖费
				if ($this->_store['reach_delivery_fee_type'] == 1) {
					$delivery_fee = $this->_store['delivery_fee'];
				} else {
					if ($total_money < $this->_store['basic_price']) {//不足起送价
						if ($this->_store['delivery_fee_valid']) {
							$delivery_fee = $this->_store['delivery_fee'];
						}
					} else {
						if ($this->_store['reach_delivery_fee_type'] == 2 && $total_money < $this->_store['no_delivery_fee_value']) {
							$delivery_fee = $this->_store['delivery_fee'];
						}
					}
				}
			}
			
			//$total_money += $delivery_fee;
			
			$minus_price = 0;
			//会员等级优惠  外卖费不参加优惠
			$level_off = false;
			if (!empty($this->user_level) && !empty($this->leveloff) && !empty($this->user_session) && isset($this->user_session['level'])) {
				if (isset($this->leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])) {
					$level_off = $this->leveloff[$this->user_session['level']];
					if ($sorts_discout['discount_type'] == 0) {
						if ($level_off['type'] == 1) {
							$vip_discount_money = $store_discount_money *($level_off['vv'] / 100);
							$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
							$level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
						} elseif($level_off['type'] == 2) {
							$vip_discount_money = $store_discount_money - $level_off['vv'];
							$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
							$level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
						}
						
					} else {
						if ($level_off['type'] == 1) {
							$vip_discount_money = $total_money *($level_off['vv'] / 100);
							$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
							$level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
						} elseif($level_off['type'] == 2) {
							$vip_discount_money = $total_money - $level_off['vv'];
							$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
							$level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
						}
						$vip_discount_money = $vip_discount_money > $store_discount_money ? $store_discount_money : $vip_discount_money;
					}
				}
			}
			
			if ($level_off == false) $vip_discount_money = $store_discount_money;
			
			if ($store_meal = D('Merchant_store_meal')->where(array('store_id' => $this->store_id))->find()) {
				if (!empty($store_meal['minus_money']) && $vip_discount_money >= $store_meal['full_money']) {
					$vip_discount_money = $vip_discount_money - $store_meal['minus_money'];
				}
			}
			$total_discount_money = $vip_discount_money + $delivery_fee;
			$total_money += $delivery_fee;
			
			$data = array('arrive_time' => $arrive_time, 'mer_id' => $this->mer_id, 'store_id' => $this->store_id, 'name' => $name, 'phone' => $phone, 'address' => $address, 'note' => $note, 'info' => serialize($info), 'dateline' => time(), 'total' => $total, 'meal_type' => 1);
			$data['orderid'] = $this->mer_id . $this->store_id . date("YmdHis") . rand(1000000, 9999999);
			$data['uid'] = $this->user_session['uid'];
			$data['meal_type'] = 1;
			$data['address_id'] = $address_id;
			$data['order_status'] = 2;
			$data['price'] = $total_discount_money;//当前要支付的金额
			$data['delivery_fee'] = $delivery_fee;//外送费
			$data['total_price'] = $total_money;//订单总价
			$data['minus_price'] = $total_money - $total_discount_money;//优惠的金额
			
			!empty($level_off) && $data['leveloff']=serialize($level_off);
			
			$orderid = D("Meal_order")->add($data);
			if ($orderid) {
				if ($this->user_session['openid']) {
					$keyword2 = '';
					$pre = '';
					foreach (unserialize($data['info']) as $menu) {
						$keyword2 .= $pre . $menu['name'] . ':' . $menu['price'] . '*' . $menu['num'];
						$pre = '\n\t\t\t';
					}
					$href = C('config.site_url').'/wap.php?c=Takeout&a=order_detail&order_id='. $orderid . '&mer_id=' . $data['mer_id'] . '&store_id=' . $data['store_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $orderid, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $keyword2, 'remark' => '您的该次'.$this->config['meal_alias_name'].'下单成功，感谢您的使用！'));
				}
				
				$printHaddle = new PrintHaddle();
				$printHaddle->printit($orderid, 'meal_order', 0);
				
				$sms_data = array('mer_id' => $this->mer_id, 'store_id' => $this->store_id, 'type' => 'food');
				if ($this->config['sms_place_order'] == 1 || $this->config['sms_place_order'] == 3) {
					$sms_data['uid'] = $this->user_session['uid'];
					$sms_data['mobile'] = $data['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您' . date("Y-m-d H:i:s", $data['dateline']) . '在【' . $this->_store['name'] . '】中预定了一份外卖，订单号：' . $orderid;
					Sms::sendSms($sms_data);
				}
				if ($this->config['sms_place_order'] == 2 || $this->config['sms_place_order'] == 3) {
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $this->_store['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客【' . $data['name'] . '】在' . date("Y-m-d H:i:s", $data['dateline']) . '时预定了一份外卖，订单号：' . $orderid . '请您注意查看并处理!';
					Sms::sendSms($sms_data);
				}
				
				/* 粉丝行为分析 */
				$this->behavior(array('mer_id' => $this->mer_id, 'biz_id' => $orderid));
				
				$_SESSION[$this->session_index] = null;
				
				redirect(U('Pay/check',array('order_id' => $orderid, 'type'=>'takeout')));
			}
		} else {
			$this->error();
		}
	}
	
	public function order_list()
	{
		$this->isLogin();
		$weeks = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
		$this->isLogin();
		$sql = "SELECT s.name, o.order_id, o.store_id, o.price, o.total, o.paid, o.pay_type, o.third_id, o.dateline, o.status FROM " . C('DB_PREFIX') . "merchant_store as s INNER JOIN " . C('DB_PREFIX') . "meal_order as o ON o.store_id=s.store_id WHERE o.meal_type=1 AND o.uid={$this->user_session['uid']} ORDER BY o.order_id DESC";
		$mode = new Model();
		$list = $mode->query($sql);
		$order_list = array();
		foreach ($list as $l) {
			$l['date'] = date('Y-m-d', $l['dateline']) . ' ' . $weeks[date('w', $l['dateline'])] . ' ' . date('H:i', $l['dateline']);
			switch ($l['status']) {
				case 0:
					$l['css'] = 'inhand';
					$l['show_status'] = '处理中';
					break;
				case 1:
					$l['css'] = 'confirm';
					$l['show_status'] = '已使用';
					break;
				case 2:
					$l['css'] = 'complete';
					$l['show_status'] = '已评价';
					break;
				case 3:
				case 4:
					$l['css'] = 'cancle';
					$l['show_status'] = '已取消';
					break;
				default:
					$l['css'] = 'pending';
					$l['show_status'] = '待定';
			}
			$order_list[] = $l;
		}
		$this->assign('order_list', $order_list);
		$this->display();
	}
	
	public function order_detail()
	{
		$this->isLogin();
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$weeks = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
		$order = D('Meal_order')->field(true)->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find();
		$order['order_type']='takeout';
		$laste_order_info= D('Tmp_orderid')->get_laste_order_info($order['order_type'],$order['order_id']);
		if(!$order['paid'] && !empty($laste_order_info)) {
			if ($laste_order_info['pay_type']=='weixin') {
				$redirctUrl = C('config.site_url') . '/wap.php?g=Wap&c=Pay&a=weixin_back&order_type='.$order['order_type'].'&order_id=' . $laste_order_info['orderid'];
				file_get_contents($redirctUrl);
				$order = D('Meal_order')->field(true)->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find();
			}
		}
		if (empty($order)) $this->error_tips('不合法的订单查询请求');
		$order['info'] = unserialize($order['info']);
		$order['date'] = date('Y-m-d', $order['dateline']) . ' ' . $weeks[date('w', $order['dateline'])] . ' ' . date('H:i', $order['dateline']);
		if ($order['arrive_time']) {
			$order['arrive_time'] = date('H:i', $order['arrive_time']);
		} else {
			$order['arrive_time'] = '尽快送达';
		}
		
		switch ($order['status']) {
			case 0:
				$order['css'] = 'inhand';
				$order['show_status'] = '处理中';
				break;
			case 1:
				$order['css'] = 'confirm';
				$order['show_status'] = '已使用';
				break;
			case 2:
				$order['css'] = 'complete';
				$order['show_status'] = '已评价';
				break;
			case 3:
			case 4:
				$order['css'] = 'cancle';
				$order['show_status'] = '已取消';
				break;
			default:
				$order['css'] = 'pending';
				$order['show_status'] = '待定';
		}
// 		$order['price'] -= $order['delivery_fee'];
		$order['paytypestr'] = D('Pay')->get_pay_name($order['pay_type']);

		if(!empty($order['coupon_id'])) {
			$system_coupon = D('System_coupon')->get_coupon_info($order['coupon_id']);
			$this->assign('system_coupon',$system_coupon);
		}else if(!empty($order['card_id'])) {
			$card = D('Member_card_coupon')->get_coupon_info($order['card_id']);
			$this->assign('card', $card);
		}
		$this->assign('order', $order);
		$this->display();
	}
	
	
	private function isLogin()
	{
		if (empty($this->user_session)) {
			$this->error_tips('请先进行登录！',U('Login/index', array('referer' => urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']))));
		}
	}
	
	public function orderdel()
	{
		$this->isLogin();
		$id = isset($_GET['orderid']) ? intval($_GET['orderid']) : 0;
		if ($order = M('Meal_order')->where(array('order_id' => $id, 'uid' => $this->user_session['uid'], 'mer_id' => $this->mer_id, 'store_id' => $this->store_id))->find()) {

			if ($order['paid'] == 1 && date('m', $order['dateline']) == date('m')) {
				foreach (unserialize($order['info']) as $menu) {
					D('Meal')->where(array('meal_id' => $menu['id'], 'sell_count' => array('gt', $menu['num'])))->setDec('sell_count', $menu['num']);
				}
			}
			D("Merchant_store_meal")->where(array('store_id' => $order['store_id']))->setDec('sale_count', 1);
			/* 粉丝行为分析 */
			$this->behavior(array('mer_id' => $this->mer_id, 'biz_id' => $this->store_id));
			
			M('Meal_order')->where(array('order_id' => $id, 'uid' => $this->user_session['uid'], 'mer_id' => $this->mer_id, 'store_id' => $this->store_id))->save(array('status' => 4));
			$this->success_tips('订单取消成功', U('Takeout/order_list', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id, 'type' => 0)));
		} else {
			$this->error_tips('订单取消失败！');
		}
		
	}

    /*     * 计算两经纬度间的距离* */

    private function getDistance($lat_a, $lng_a, $lat_b, $lng_b) 
    {
        //R是地球半径（米）
        $R = 6377830;
        $pk = doubleval(180 / 3.1415926);

        $a1 = doubleval($lat_a / $pk);
        $a2 = doubleval($lng_a / $pk);
        $b1 = doubleval($lat_b / $pk);
        $b2 = doubleval($lng_b / $pk);

        $t1 = doubleval(cos($a1) * cos($a2) * cos($b1) * cos($b2));
        $t2 = doubleval(cos($a1) * sin($a2) * cos($b1) * sin($b2));
        $t3 = doubleval(sin($a1) * sin($b1));
        $tt = doubleval(acos($t1 + $t2 + $t3));

        return round($R * $tt);
    }
}
?>