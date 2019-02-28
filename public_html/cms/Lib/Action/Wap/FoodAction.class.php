<?php
class FoodAction extends BaseAction
{
	public $store_id = 0;
	
	public $_store = null;
	
	public $session_index = '';
	
	public $session_table_index = '';
	
	public $order_index = '';

	public $leveloff = '';
	
	public $page_lenght = 15;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;

		if (!isset($this->config['no_foodshop'])) {
			redirect(U('Foodshop/shop', array('store_id' => $this->store_id)));
			exit;
		}
		$this->assign('store_id', $this->store_id);
		
		/* 粉丝行为分析 */
		D('Merchant_request')->add_request($this->mer_id, array('meal_hits' => 1));
		
		//店铺详情
		$merchant_store = M("Merchant_store")->where(array('store_id' => $this->store_id))->find();
		
		if ($merchant_store['office_time']) {
			$merchant_store['office_time'] = unserialize($merchant_store['office_time']);
		} else {

			$merchant_store['office_time'][] = array('open' => $merchant_store['open_1'], 'close' => $merchant_store['close_1']);
			if ($merchant_store['open_2'] != '00:00:00' && $merchant_store['close_2'] != '00:00:00') {
				$merchant_store['office_time'][] = array('open' => $merchant_store['open_2'], 'close' => $merchant_store['close_2']);
			}
			if ($merchant_store['open_3'] != '00:00:00' && $merchant_store['close_3'] != '00:00:00') {
				$merchant_store['office_time'][] = array('open' => $merchant_store['open_3'], 'close' => $merchant_store['close_3']);
			}
		}
		$store_image_class = new store_image();
		$merchant_store['images'] = $store_image_class->get_allImage_by_path($merchant_store['pic_info']);
		$t = $merchant_store['images'];
		$merchant_store['image'] = array_shift($t);
		
		$merchant_store_meal = M("Merchant_store_meal")->where(array('store_id' => $this->store_id))->find();
		if ($merchant_store_meal) $merchant_store = array_merge($merchant_store, $merchant_store_meal);
		$this->leveloff=!empty($merchant_store_meal['leveloff']) ? unserialize($merchant_store_meal['leveloff']) :'';
		$this->_store = $merchant_store;
		$this->assign('store', $this->_store);
		
		
		$this->session_index = "session_foods{$this->store_id}_{$this->mer_id}";
		$this->session_table_index = "session_table_{$this->store_id}_{$this->mer_id}";
		$this->order_index = "order_id_{$this->store_id}_{$this->mer_id}";
		if ($_SESSION['openid'] && ($services = D('Customer_service')->where(array('mer_id' => $this->mer_id))->select())) {
			$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
			$kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $this->mer_id;
			$this->assign('kf_url', $kf_url);
		}
	}
	
	public function index()
	{
		$name = isset($_GET['searhkey']) ? htmlspecialchars($_GET['searhkey']) : '';
		$where = array('mer_id' => $this->mer_id, 'have_meal' => 1, 'status' => 1);
		if ($name) $where['name'] = array('like', '%'.$name.'%');
		$stores = D('Merchant_store')->field(true)->where($where)->select();
		$store_image_class = new store_image();
		$list = array();
		foreach ($stores as $row) {
			$temp = array();
			$temp['position'] = array('lng' => $row['long'], 'lat' => $row['lat']);
			$temp['name'] = $row['name'];
			$temp['btndisabled'] = 0;
			$temp['isShow'] = 0;
			$temp['seeURL'] = '';
			$temp['showList'] = array();
			$temp['btnText'] = '我买';
			$temp['btnUrl'] = U('Food/shop', array('mer_id' => $row['mer_id'], 'store_id' => $row['store_id']));
			$temp['address'] = $row['adress'];
			$images = $store_image_class->get_allImage_by_path($row['pic_info']);
			$temp['imgurl'] = array_shift($images);
		
			$temp['storeDetailsURL'] = U('Food/shop', array('mer_id' => $row['mer_id'], 'store_id' => $row['store_id']));//'wap.php?mod=takeout&action=menu&com_id=' . $row['com_id'] . '&id=' . $row['id'];
			$list[] = $temp;
		}
		$this->assign('list', json_encode($list));
		$this->assign('total', count($list));
		
		$this->display();
	}
	
	
	public function shop()
	{
		empty($this->_store) && $this->error_tips("不存在的商家店铺!");
		if (empty($this->_store['have_meal'])) {
			$this->error_tips("商家店铺已关闭!");
		}
		$is_redirect_shop = 0;
		if ($this->_store['have_shop']) {
			if ($shop = D('Merchant_store_shop')->field(true)->where(array('mer_id' => $this->mer_id, 'store_id' => $this->_store['store_id']))->find()) {
				$is_redirect_shop = $shop['close_old_store'];
			}
			if ($this->_store['store_type'] == 3) $is_redirect_shop = 1;
		}
		
		if ($this->_store['store_type'] == 2) {
			if ($is_redirect_shop) {
				$this->redirect(U('Shop/index', array('shop-id' => $this->_store['store_id'])));
			} else {
				$this->redirect(U('Takeout/menu', array('mer_id' => $this->mer_id, 'store_id' => $this->_store['store_id'])));
			}
		}
		$this->assign('is_redirect_shop', $is_redirect_shop);
		$this->behavior(array('mer_id' => $this->mer_id, 'biz_id' => $this->store_id));
		$this->display();
	}
	
	
	public function shop_detail()
	{
		empty($this->_store) && $this->error("不存在的商家店铺!");
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
		
		$html = '';
		foreach ($reply_list as $vo) {
			$html .= '<dd class="dd-padding">';
			$html .= '<div class="feedbackCard">';
			$html .= '<div class="userInfo">';
			$html .= '<weak class="username">' . $vo['nickname'] . '</weak>';
			$html .= '</div>';
			$html .= '<div class="score">';
			$html .= '<span class="stars">';
			for($i = 0; $i < 6; $i++) {
				if ($vo['score'] > $i) {
					$html .= '<i class="text-icon icon-star"></i>';
				} else {
					$html .= '<i class="text-icon icon-star-gray"></i>';
				}
			}
			$html .= '</span>';
			$html .= '<weak class="time">' . $vo['add_time'] . '</weak>';
			$html .= '</div>';
			$html .= '<div class="comment">';
			$html .= '<p>' . $vo['comment'] . '</p>';
			$html .= '</div>';
			if ($vo['pics']) {
				$html .= '<div class="pics view_album" data-pics="';
				$i = 1;
				foreach ($vo['pics'] as $vvoo) {
					$html .= $vvoo['m_image'];
					if (count($vo['pics']) > $i) {
						$html .= ',';
					}
					$i ++;
				}
				$html .= '">';
				foreach ($vo['pics'] as $voo) {
					$html .= '<span class="pic-container imgbox" style="background:none;"><img src="' . $voo['s_image'] . '" style="width:100%;"/></span>&nbsp;';
				}
				$html .= '</volist>';
				$html .= '</div>';
			}
			$html .= '</div>';
			$html .= '</dd>';
		}
	}
	
	//菜单列表
	public function menu()
	{
		empty($this->_store) && $this->error("不存在的商家店铺!");
		$orid = isset($_GET['orid']) ? intval($_GET['orid']) : 0;

		$tableid = isset($_GET['tableid']) ? intval($_GET['tableid']) : 0;//指定桌台号
		if ($now_table = D('Merchant_store_table')->field(true)->where(array('pigcms_id' => $tableid, 'store_id' => $this->_store['store_id']))->find()) {
			$_SESSION[$this->session_table_index] = $tableid;
		}

		//客户的购物车记录
		$disharr = unserialize($_SESSION[$this->session_index]);
		
		if ($order = $this->check_order($orid)) {
			if ($order['paid'] != 1) {
				$info = unserialize($order['info']);
				foreach ($info as $om) {
					if (isset($disharr[$om['id']])) {
						$disharr[$om['id']]['num'] += $om['num'];
					} else {
						$disharr[$om['id']]['num'] = $om['num'];
					}
				}
			}
			$this->assign('orid', $orid);
		} else {
			$this->assign('orid', 0);
		}

		/**************客户收藏的菜品*****************/
		$like = D('Meal_like')->field('meal_ids')->where(array('uid' => $this->user_session['uid'], 'store_id' => $this->store_id, 'mer_id' => $this->mer_id))->find();
		$meal_ids = array();
		$like && $meal_ids = unserialize($like['meal_ids']);
		/**************客户收藏的菜品*****************/
		
		//菜品分类
		$sorts = M("Meal_sort")->where(array('store_id' => $this->store_id))->order('sort DESC, sort_id DESC')->select();
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
		
		$nowDay = date('Ymd');
		$MOOBJ = D('Meal_order');
		$meal_image_class = new meal_image();
		$temp = M("Meal")->where(array('store_id' => $this->store_id, 'sort_id' => array('in', $ids), 'status' => 1))->order('sort DESC')->select();
		foreach ($temp as $m) {
			if (isset($disharr[$m['meal_id']])) {
				$m['num'] = $disharr[$m['meal_id']]['num'];
			} else {
				$m['num'] = 0;
			}
			if (in_array($m['meal_id'], $meal_ids)) {
				$m['like'] = 1;
			} else {
				$m['like'] = 0;
			}
			if ($m['sell_mouth'] != $nowDay) {
				//$m['sell_count'] = 0;
			}
			
			/***库存的处理***/
			$m['max'] = -1;
			$check_stock = $MOOBJ->check_stock($m['meal_id']);
			$m['max'] = $check_stock['stock_num'];
			
			$m['image'] = $meal_image_class->get_image_by_path($m['image'], $this->config['site_url'], 's');
			if (isset($t_meals[$m['sort_id']]['list'])) {
				$t_meals[$m['sort_id']]['list'][] = $m;
			} else {
				$t_meals[$m['sort_id']]['list'] = array($m);
				$t_meals[$m['sort_id']]['sort_id'] = $m['sort_id'];
			}
		}
		
		foreach ($ids as $sort_id) {
			if (isset($t_meals[$sort_id])) {
				$meals[$sort_id] = $t_meals[$sort_id];
			} else {
				unset($list[$sort_id]);
			}
		}
		
		/* 粉丝行为分析 */
		$this->behavior(array('mer_id' => $this->mer_id, 'biz_id' => $this->store_id,'keyword'=>strval($_GET['keywords'])));
		
		$this->assign('meals', $meals);
		$this->assign("sortlist", $list);
		$this->display();
	}
	
	/**
	 * 保存到购物车
	 */
	public function processOrder() 
	{
		empty($this->_store) && $this->error("不存在的商家店铺!");
		
		$foods = $_POST['cart'];
		$disharr = array();
		$sure_dish = unserialize($_SESSION[$this->session_index]);
		$MOOBJ = D('Meal_order');
		foreach ($foods as $kk => $vv) {
			$count = $vv['count'] ? intval($vv['count']) : 0;
			if ($count > 0) {
				$check_stock = $MOOBJ->check_stock($vv['id']);
				if ($check_stock['stock_num'] > -1 && $check_stock['stock_num'] < $count) {
					exit(json_encode(array('error' => 1, 'msg' => '您购买的' . $check_stock['name'] . '超出了库存量！')));
				}
				$disharr[$vv['id']] = array('id' => $vv['id'], 'num' => $count, 'omark' => '');
				if (isset($sure_dish[$vv['id']]['omark']) && $sure_dish[$vv['id']]['omark']) {
					$disharr[$vv['id']]['omark'] = $sure_dish[$vv['id']]['omark'];
				}
			}
		}
		empty($disharr) && exit(json_encode(array('error' => 1, 'msg' => '您尚未点菜！')));
		$_SESSION[$this->session_index] = serialize($disharr);
		exit(json_encode(array('error' => 0, 'msg' => 'ok')));
	}
	
	private function check_order($order_id)
	{
		if ($order = D('Meal_order')->where(array('uid' => $this->user_session['uid'], 'store_id' => $this->store_id, 'order_id' => $order_id))->find()) {
			return $order;
		} else {
			return false;
		}
	}
	/**
	 * 确认购物车 
	 */
	public function cart()
	{
		$this->isLogin();
		$isclean = $this->_get('isclean', 'trim');
		$orid = $this->_get('orid', 'intval');
		
		if ($this->check_order($orid)) {
			$this->assign('action_url', U('Food/saveorder', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id, 'orid' => $orid)));
			$this->assign('orid', $orid);
		} else {
			$this->assign('action_url', U('Food/sureorder', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id)));
			$this->assign('orid', 0);
		}
		$level_off=false;
		if(!empty($this->user_level) && !empty($this->leveloff) && !empty($this->user_session) && isset($this->user_session['level'])){
		     /****type:0无优惠 1百分比 2立减*******/
			if(isset($this->leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])){
				$level_off=$this->leveloff[$this->user_session['level']];
				if($level_off['type']==1){
				  $level_off['offstr']='按此次总价'.$level_off['vv'].'%来结算';
				}elseif($level_off['type']==2){
				  $level_off['offstr']='此次总价立减'.$level_off['vv'].'元';
				}
			}
		}
		$this->assign('level_off', $level_off);		
		if ($isclean == 1) {
			$_SESSION[$this->session_index] = '';
			$disharr = '';
		} else {
			$disharr = unserialize($_SESSION[$this->session_index]);
		}
		if (!empty($disharr)) {
			$idarr = array_keys($disharr);
			$meal_image_class = new meal_image();
			$dish = M("Meal")->where(array('store_id' => $this->store_id, 'meal_id' => array('in', $idarr), 'status' => 1))->select();
			$MOOBJ = D('Meal_order');
			foreach ($dish as $val) {
				$val['image'] = $meal_image_class->get_image_by_path($val['image'],$this->config['site_url'],'s');
				
				//库存的处理
				$check_stock = $MOOBJ->check_stock($val['meal_id']);
				$val['max'] = $check_stock['stock_num'];
				
				$disharr[$val['meal_id']] = array_merge($disharr[$val['meal_id']], $val);
			}
		}
		
		$allmark = $_SESSION["allmark" . $this->store_id . $this->mer_id];
		$this->assign('allmark', $allmark);
		$this->assign('ordishs', $disharr);
		$this->display();
	}
	
	
	/**
	 * 填写客户的个人信息
	 */
	public function sureorder()
	{
		$this->isLogin();
		$is_reserve = isset($_GET['is_reserve']) ? intval($_GET['is_reserve']) : 0 ;
		$this->assign('is_reserve', $is_reserve);
		
		$tableid = $_SESSION[$this->session_table_index];
		$this->assign('tableid', intval($tableid));
		
        $disharr = $_POST['dish'];
        $allmark = htmlspecialchars(trim($_POST['allmark']), ENT_QUOTES);
        $_SESSION["allmark" . $this->store_id . $this->mer_id] = $allmark;
        $orid = $this->_get('orid') ? intval($this->_get('orid', "trim")) : 0;
        if ($this->_store) {
            $tmparr = array();
            $MOOBJ = D('Meal_order');
            $totalnum = 0;//菜品总数
            $total_money = 0;//菜品总价
            $store_discount_money = 0;//店铺折扣后的菜品总价
            $total_discount_money = 0;//总的优惠后价格
            $sorts_discout = D('Meal_sort')->get_sorts($this->store_id);
            $idarr = array_keys($disharr);
            $dish = M("Meal")->where(array('store_id' => $this->store_id, 'meal_id' => array('in', $idarr), 'status' => 1))->select();
            foreach ($dish as $val) {
            	$num = isset($disharr[$val['meal_id']]['num']) ? intval($disharr[$val['meal_id']]['num']) : 0;
            	if ($num == 0) continue;	
            	$check_stock = $MOOBJ->check_stock($val['meal_id']);
            	if ($check_stock['stock_num'] > -1 && $check_stock['stock_num'] < $num) {
            		$this->error('您购买的' . $check_stock['name'] . '超出了库存量！');
            	}
            	$tmparr[$val['meal_id']] = array('id' => $val['meal_id'], 'num' => $num, 'omark' => htmlspecialchars(trim($dv['omark']), ENT_QUOTES));
            	$total_money += $val['price'] * $num;
            	$t_discount = isset($sorts_discout[$val['sort_id']]) && $sorts_discout[$val['sort_id']] ? $sorts_discout[$val['sort_id']] : 100;
            	$store_discount_money += $val['price'] * $num * $t_discount / 100;
            	$totalnum += $num;
            }
            
            $tmparr && $_SESSION[$this->session_index] = serialize($tmparr);

            
            $level_off = false;
            $vip_discount_money = null;
			if (!empty($this->user_level) && !empty($this->leveloff) && !empty($this->user_session) && isset($this->user_session['level'])) {
			     /****type:0无优惠 1百分比 2立减*******/
				if (isset($this->leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])) {
					$level_off = $this->leveloff[$this->user_session['level']];
					if ($sorts_discout['discount_type'] == 0) {//折中折
						if ($level_off['type'] == 1) {
							$total_discount_money = $store_discount_money *($level_off['vv'] / 100);
							$total_discount_money = $total_discount_money > 0 ? $total_discount_money : 0;
// 							$level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
						} elseif ($level_off['type'] == 2) {
							$vip_discount_money = $store_discount_money - $level_off['vv'];
							$total_discount_money = $total_discount_money > 0 ? $total_discount_money : 0;
// 							$level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
						}
					} else {//计算vip的优惠
						if ($level_off['type'] == 1) {
							$vip_discount_money = $total_money *($level_off['vv'] / 100);
							$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 							$level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
						} elseif ($level_off['type'] == 2) {
							$vip_discount_money = $total_money - $level_off['vv'];
							$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 							$level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
						}
					}
					
					if ($sorts_discout['discount_type'] == 1) {
						if ($vip_discount_money < $store_discount_money) {
							$sorts_discout = null;
							$total_discount_money = $vip_discount_money;
						} else {
							$total_discount_money = $store_discount_money;
							$level_off = null;
						}
					}
				}
			}
			if ($vip_discount_money === null) $total_discount_money = $store_discount_money;
			if ($is_reserve) {
				$this->assign('reserve_money', round($this->_store['deposit'], 2));
			}
			$this->assign('level_off', $level_off);
			$this->assign('sorts_discout', $sorts_discout);
			
			$this->assign('total_money', $total_money);
			$this->assign('totalnum', $totalnum);
			$this->assign('vip_discount_money', round($vip_discount_money, 2));
			$this->assign('store_discount_money', round($store_discount_money, 2));
			$this->assign('total_discount_money', round($total_discount_money, 2));
			/*****************************************/
			$arrive_time = time();
			$star_time = $arrive_time - 7200;
			$end_time = $arrive_time + 7200;
			$tables = D('Merchant_store_table')->field(true)->where(array('store_id' => $this->store_id))->select();
			$orders = D('Meal_order')->field('tableid')->where("`store_id`={$this->store_id} AND `paid`=1 AND `is_confirm`=1 AND `status`=0 AND `arrive_time`>'{$star_time}' AND `arrive_time`<'{$end_time}' AND tableid>0")->select();
			$tids = array();
			foreach ($orders as $row) {
				$tids[$row['tableid']] = $row;
			}
			$table_list = array();
			foreach ($tables as $table) {
				if (!isset($tids[$table['pigcms_id']])) {
					$table_list[] = $table;
				}
			}
			$this->assign('tables', $table_list);
			/*****************************************/
			if (empty($tables)) {
				$this->assign('seattype', 0);
			} else {
				$this->assign('seattype', $tables[0]['pigcms_id']);
			}
            $user_info = D('User_adress')->get_one_adress($this->user_session['uid'], intval($_GET['adress_id']));
            $this->assign('date', date('Y-m-d'));
            $this->assign('time', date('H:i', time() + 1200));
            $this->assign('user_info', $user_info);
            $this->display();
        } else {
            $jumpurl = U('Food/index', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id));
            $this->error('订单信息中店面信息出错', $jumpurl);
        }
	}
	
	/**
	 * 保存订单
	 * total_price 订单实际的总价
	 * minus_price 订单优惠的金额
	 * price该次支付的金额
	 * pay_money已经支付的金额
	 * price-pay_money是该次实际要支付的金额
	 */
	public function saveorder()
	{
		if(empty($this->user_session)){
			exit(json_encode(array('status' => 0, 'info' => '请先进行登录！', 'url' => U('Login/index'))));
		}
		
		$orid = isset($_REQUEST['orid']) ? intval($_REQUEST['orid']) : 0;
		
		$isdeposit = isset($_POST['isdeposit']) ? intval($_POST['isdeposit']) : 0;/***isdeposit 1 支付预定经***/
		$is_reserve = isset($_POST['is_reserve']) ? intval($_POST['is_reserve']) : 0;/***is_reserve 1 预约***/
		

		$total = $price = $tmpprice=0;
		$store_meal = D('Merchant_store_meal')->where(array('store_id' => $this->store_id))->find();
		$MOOBJ = D('Meal_order');
		//店铺优惠条件
		$sorts_discout = D('Meal_sort')->get_sorts($this->store_id);
		
		$total_money = 0;
		$total_discount_money = 0;
		$vip_discount_money = 0;
		$store_discount_money = 0;
		
		if ($old_order = $this->check_order($orid)) {//加菜
			$disharr = $_POST['dish'];
			$idarr = array_keys($disharr);
			$info = $old_order['info'] ? unserialize($old_order['info']) : array();
			$isadd = empty($info) ? 0 : 1;
			$oldmenus = array();
			if ($old_order['paid'] != 1) {
				foreach ($info as $om) {
					$oldmenus[$om['id']] = $om['num'];
				}
			}
			if ($idarr) {
				$dish = M("Meal")->where(array('store_id' => $this->store_id, 'meal_id' => array('in', $idarr), 'status' => 1))->select();
				foreach ($dish as $val) {
					$num = isset($disharr[$val['meal_id']]['num']) ? intval($disharr[$val['meal_id']]['num']) : 0;
					$omark = isset($disharr[$val['meal_id']]['omark']) ? htmlspecialchars($disharr[$val['meal_id']]['omark']) : '';
					$check_num = 0;
					if (isset($oldmenus[$val['meal_id']])) {
						for ($i = 0; $i < count($info); $i++) {
							if ($info[$i]['id'] == $val['meal_id']) {
								$isadd = $info[$i]['num'] == $num ? 0 : 1;
								$info[$i] = array('id' => $val['meal_id'], 'name' => $val['name'], 'price' => $val['price'], 'num' => $num, 'omark' => $omark, 'isadd' => $isadd, 'iscount' => 0);
								$check_num = $num - $info[$i]['num'];
							}
						}
					} else {
						$info[] = array('id' => $val['meal_id'], 'name' => $val['name'], 'price' => $val['price'], 'num' => $num, 'omark' => $omark, 'isadd' => 1, 'iscount' => 0);
						$check_num = $num;
					}
					
					$check_stock = $MOOBJ->check_stock($val['meal_id']);
					if ($check_stock['stock_num'] > -1 && $check_stock['stock_num'] < $check_num) {
						$this->error('您购买的' . $check_stock['name'] . '超出了库存量！');
					}
					
					$total += $num;
					$total_money += $val['price'] * $num;
					
	            	$t_discount = isset($sorts_discout[$val['sort_id']]) && $sorts_discout[$val['sort_id']] ? $sorts_discout[$val['sort_id']] : 100;
	            	$store_discount_money += $val['price'] * $num * $t_discount / 100;
            	
				}

				//会员等级优惠
				$level_off = false;
				if (!empty($this->user_level) && !empty($this->leveloff) && !empty($this->user_session) && isset($this->user_session['level'])) {
					 /****type:0无优惠 1百分比 2立减*******/
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
							if ($vip_discount_money > $store_discount_money) {
								$vip_discount_money = $store_discount_money;
							}
						}
					}
				}
				$total_discount_money = $level_off ? $vip_discount_money : $store_discount_money;

// 				if (!empty($old_order['leveloff'])) {
// 					$leveloff = unserialize($old_order['leveloff']);
					if ($old_order['paid'] == 1) {
						$price = $finaltotalprice > 0 ? $leveloff['totalprice']+$finaltotalprice : $leveloff['totalprice']+$tmpprice;
						$total_money = $total_money + $old_order['total_price'];
						$total_discount_money += ($old_order['total_price'] - $old_order['minus_price']);
					}
// 					$price = round($price, 2);
// 					is_array($level_off) && $level_off['totalprice']=$price;
// 				}else{
// 					foreach ($info as $v) {
// 						$price += $v['price'] * $v['num'];
// 					}
// 					$price = max($price, $old_order['price']);
// 				}
				
				if ($store_meal && !empty($store_meal['minus_money']) && $total_discount_money > $store_meal['full_money']) {
					$total_discount_money = $total_discount_money - $store_meal['minus_money'];
				}
				
				$data = array('price' => $total_discount_money, 'dateline' => time());
				if ($old_order['paid'] == 1) {
					$data['total'] = $total + $old_order['total'];
				} else {
					$data['total'] = $total;
				}
				$data['orderid'] = date("YmdHis") . sprintf("%08d", $this->user_session['uid']);
				$data['info'] = $info ? serialize($info) : '';
				
				$data['total_price'] = $total_money;
				$data['minus_price'] = $total_money - $total_discount_money;
				
				$data['paid'] = $old_order['paid'] == 1 ? 2 : 0;
				
				!empty($level_off) && $data['leveloff'] = serialize($level_off);
				
				if ($return = D("Meal_order")->where(array('order_id' => $orid, 'uid' => $this->user_session['uid']))->save($data)) {
					$_SESSION[$this->session_index]  = null;
					$_SESSION["allmark" . $this->store_id . $this->mer_id] = null;
					$this->notice($orid);
					redirect(U('Pay/check', array('order_id' => $orid, 'type'=>'food')));
				} else {
					exit(json_encode(array('status' => 0, 'info' => '服务器繁忙，稍后重试！')));
					$this->error('服务器繁忙，稍后重试');
				}
			} else {
				$jumpurl = U('Food/menu', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id, 'orid' => $orid));
				$this->error('还没有加菜呢', $jumpurl);
			}
		} else {//点菜的新单信息
			$disharr = unserialize($_SESSION[$this->session_index]);
			$idarr = array_keys($disharr);
			$phone = isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : '';
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
			$note = isset($_POST['mark']) ? htmlspecialchars($_POST['mark']) : '';
			$date = isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '';
			$time = isset($_POST['time']) ? htmlspecialchars($_POST['time']) : '';
			$sex = isset($_POST['sex']) ? intval($_POST['sex']) : 1;
			$people_num = isset($_POST['num']) ? intval($_POST['num']) : 2;
			$tableid = isset($_POST['seattype']) ? intval($_POST['seattype']) : 0;
			//if (empty($date)) exit(json_encode(array('status' => 1, 'info' => '使用日期不能为空')));
			//if (empty($time)) exit(json_encode(array('status' => 1, 'info' => '使用时间不能为空')));
			//if (empty($name)) exit(json_encode(array('status' => 1, 'info' => '您的姓名不能为空')));
			//if (empty($phone)) exit(json_encode(array('status' => 1, 'info' => '您的电话不能为空')));
			$arrive_time = strtotime($date . ' ' . $time . ":00");
			$now_time = time() + 1200;
			if ($is_reserve) {
				if ($now_time > $arrive_time) $this->error('至少提前二十分钟预约');
				if (empty($tableid))$this->error('请您选择要预定的餐桌');
				$star_time = $arrive_time - 7200;
				$end_time = $arrive_time + 7200;
				$count = D('Meal_order')->where("`tableid`={$tableid} AND `store_id`={$store_id} AND `paid`=1 AND `status`=0 AND `is_confirm`=1 AND `arrive_time`>'{$star_time}' AND `arrive_time`<'{$end_time}'")->count();
				if ($count > 0) $this->error('您选择预定的餐桌已被预定，请重新选择');
				$idarr = null;
			}
			$info = array();
			if ($idarr) {//点餐
				$dish = M("Meal")->where(array('store_id' => $this->store_id, 'meal_id' => array('in', $idarr), 'status' => 1))->select();
				foreach ($dish as $val) {
					$num = isset($disharr[$val['meal_id']]['num']) ? intval($disharr[$val['meal_id']]['num']) : 0;
					
					$check_stock = $MOOBJ->check_stock($val['meal_id']);
					if ($check_stock['stock_num'] > -1 && $check_stock['stock_num'] < $num) {
						$this->error('您购买的' . $check_stock['name'] . '超出了库存量！');
					}
					
					$omark = isset($disharr[$val['meal_id']]['omark']) ? htmlspecialchars($disharr[$val['meal_id']]['omark']) : '';
					$info[] = array('id' => $val['meal_id'], 'name' => $val['name'], 'price' => $val['price'], 'num' => $num, 'omark' => $omark, 'isadd' => 0, 'iscount' => 0);
					$total += $num;
					$total_money += $val['price'] * $num;
					
	            	$t_discount = isset($sorts_discout[$val['sort_id']]) && $sorts_discout[$val['sort_id']] ? $sorts_discout[$val['sort_id']] : 100;
	            	$store_discount_money += $val['price'] * $num * $t_discount / 100;
				}
				//会员等级优惠
				$level_off = false;
				if (!empty($this->user_level) && !empty($this->leveloff) && !empty($this->user_session) && isset($this->user_session['level'])) {
					 /****type:0无优惠 1百分比 2立减*******/
					if (isset($this->leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])) {
						$level_off = $this->leveloff[$this->user_session['level']];
						if ($sorts_discout['discount_type'] == 0) {//折上折
							if ($level_off['type'] == 1) {
								$vip_discount_money = $store_discount_money * ($level_off['vv'] / 100);
								$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 								$level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
							} elseif ($level_off['type'] == 2) {
								$vip_discount_money = $store_discount_money - $level_off['vv'];
								$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 								$level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
							}
						} else {
							if ($level_off['type'] == 1) {
								$vip_discount_money = $total_money * ($level_off['vv'] / 100);
								$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 								$level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
							} elseif ($level_off['type'] == 2) {
								$vip_discount_money = $total_money - $level_off['vv'];
								$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 								$level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
							}
							$vip_discount_money = $vip_discount_money > $store_discount_money ? $store_discount_money : $vip_discount_money;
						}
					}
				}
				$total_discount_money = $level_off ? $vip_discount_money : $store_discount_money;
				$minus_price = 0;
				if ($store_meal && !empty($store_meal['minus_money']) && $total_discount_money >= $store_meal['full_money']) {
					$total_discount_money = $total_discount_money - $store_meal['minus_money'];
				}
				$price = $total_discount_money;
				if(isset($level_off) && $isdeposit && (isset($level_off['totalprice']) && $level_off['totalprice'] < $total_discount_money)){
					$level_off['totalprice'] = $total_discount_money;
				}
				$meal_type = 3;
			} else {//预定
				if (empty($is_reserve)) {
					$jumpurl = U('Food/menu', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id));
					exit(json_encode(array('status' => 0, 'info' => '您还没有买或订单已生成', 'url' => $jumpurl)));
					$this->error('您还没有买或订单已生成', $jumpurl);
				}
				$total_discount_money = $price = $total_money = $this->_store['deposit'];
				$meal_type = 0;
			}


			$data = array();
			$data['mer_id'] = $this->mer_id;
			$data['tableid'] = $tableid;
			$data['meal_type'] = $meal_type;
			$data['store_id'] = $this->store_id;
			$data['name'] = $name;
			$data['phone'] = $phone;
			$data['address'] = $address;
			$data['dateline'] = time();
			$data['note'] = $note;
			$data['total'] = $total;
			$data['arrive_time'] = $arrive_time ? $arrive_time : time() + 600;
			$data['price'] = $price;
			$data['orderid'] = date("YmdHis") . sprintf("%08d", $this->user_session['uid']);
			$data['uid'] = $this->user_session['uid'];
			$data['sex'] = $sex;
			$data['num'] = $people_num;
			$data['info'] = $info ? serialize($info) : '';
			
			$data['is_confirm'] = $is_reserve;//预定餐台的默认是店员已确认
			$data['total_price'] = $total_money;
			$data['minus_price'] = $total_money - $total_discount_money;
			
			isset($level_off) && !empty($level_off) && $data['leveloff'] = serialize($level_off);
			$orderid = D("Meal_order")->add($data);
			if ($orderid) {
				$_SESSION[$this->session_index] = null;
				$_SESSION["allmark" . $this->store_id . $this->mer_id] = null;

				$this->notice($orderid);
				/* 粉丝行为分析 */
				$this->behavior(array('mer_id' => $this->mer_id, 'biz_id' => $orderid));
				if ($data['total_price'] - $data['minus_price'] <= 0) {
					D("Meal_order")->where(array('order_id' => $orderid))->save(array('paid' => 1));
					exit(json_encode(array('status' => 1, 'url' => U('Food/order_detail', array('order_id' => $orderid, 'mer_id'=> $this->mer_id, 'store_id' => $this->store_id)))));
				}
				exit(json_encode(array('status' => 1, 'url' => U('Pay/check', array('order_id' => $orderid, 'type'=>'food', 'isdeposit' => $is_reserve)))));
			} else {
				exit(json_encode(array('status' => 0, 'info' => '服务器繁忙，稍后重试')));
			}
		}
	}
	
	/**
	 * 订单列表
	 */
	public function order_list()
	{
		$this->isLogin();
		$meal_type = isset($_GET['meal_type']) ? intval($_GET['meal_type']) : 0;
		$this->assign('meal_type', $meal_type);
		if ($meal_type == 1) {
			$sql = "SELECT `s`.`name` as s_name, `o`.* FROM " . C('DB_PREFIX') . 'merchant_store AS s INNER JOIN ' . C('DB_PREFIX') . "meal_order as o ON s.store_id=o.store_id WHERE o.store_id={$this->store_id} AND o.mer_id={$this->mer_id} AND o.uid={$this->user_session['uid']}  AND o.meal_type={$meal_type} AND o.status<3 ORDER BY o.order_id DESC LIMIT 0, 30";
		} else {
			$sql = "SELECT `s`.`name` as s_name, `o`.* FROM " . C('DB_PREFIX') . 'merchant_store AS s INNER JOIN ' . C('DB_PREFIX') . "meal_order as o ON s.store_id=o.store_id WHERE o.store_id={$this->store_id} AND o.mer_id={$this->mer_id} AND o.uid={$this->user_session['uid']}  AND o.meal_type<>1 AND o.status<3 ORDER BY o.order_id DESC LIMIT 0, 30";
		}
        
        $mode = new Model();
        $meal_list = $mode->query($sql);
        $list = array();
        $weekarr = array('0' => '周日', '1' => '周一', '2' => '周二', '3' => '周三', '4' => '周四', '5' => '周五', '6' => '周六');
        $nowtime = time();
        foreach ($meal_list as $tmp) {
        	//if ($tmp['pay_type'] == 'offline' && empty($tmp['thrid_id'])) $tmp['paid'] = 0;
        	$tmp['topay'] = false;
        	if ($tmp['status'] == 4) {
				$tmp['css'] = ' faild hasicon';
				$tmp['show_status'] = '<label>×</label>已取消';
			} elseif ($tmp['status'] == 3) {
				$tmp['css'] = ' faild hasicon';
				$tmp['show_status'] = '<label>×</label>已退款';
			} elseif ($tmp['paid'] == 1) {
        		if ($tmp['status']) {
        			$tmp['css'] = ' faild hasicon';
        			$tmp['show_status'] = '<label>√</label>已完成';
        		} else {
        			$tmp['css'] = ' processing';
        			$tmp['show_status'] = '已付款';
        		}
        	} elseif ($tmp['paid'] == 2) {
				$tmp['topay'] = true;
				$tmp['css'] = 'processing';
				$tmp['show_status'] = '处理中';
			} else {//预定时间加3个小时 if (intval($tmp['arrive_time']) + 10800 > time())
				$tmp['topay'] = true;
				$tmp['css'] = 'processing';
				$tmp['show_status'] = '处理中';
			}
        	
            $tmp['otimestr'] = date('Y-m-d', $tmp['dateline']) . " " . $weekarr[date('w', $tmp['dateline'])] . " " . date('H:i', $tmp['dateline']);
            $list[] = $tmp;
        }
        $this->assign('orderList', $list);
        $this->display();
	}
	
	/**
	 * 订单详情
	 */
	public function order_detail()
	{
		$this->isLogin();
		
		$orderid = intval($_GET['order_id']);
// 		$otherrm = isset($_GET['otherrm']) ? intval($_GET['otherrm']) : 0;
// 		$otherrm && $_SESSION['otherwc'] = null;
		
		$order = M("Meal_order")->where(array('order_id' => $orderid, 'uid' => $this->user_session['uid'], 'store_id' => $this->store_id))->find();
		$order['order_type'] = 'food';
		$laste_order_info = D('Tmp_orderid')->get_laste_order_info($order['order_type'],$order['order_id']);
		if(!$order['paid'] && !empty($laste_order_info)) {
			if ($laste_order_info['pay_type']=='weixin') {
				$redirctUrl = C('config.site_url') . '/wap.php?g=Wap&c=Pay&a=weixin_back&order_type='.$order['order_type'].'&order_id=' . $laste_order_info['orderid'];
				file_get_contents($redirctUrl);
				$order = M("Meal_order")->where(array('order_id' => $orderid, 'uid' => $this->user_session['uid'], 'store_id' => $this->store_id))->find();
			}
		}

		if (empty($order)) {
			$this->error('错误的订单信息', U('Meal/index'));
		}
		$meallist = unserialize($order['info']);
		//if ($order['pay_type'] == 'offline' && empty($order['third_id'])) $order['paid'] = 0;
		$order['topay'] = false;
		$order['jiaxcai'] = false;
		$order['cancel'] = 0;
		// if ($order['paid'] == 1 && !($order['pay_type'] == 'offline' && empty($order['third_id']))) {
		if ($order['status'] == 4) {
			$order['cancel'] = 2;//删除
			$order['css'] = ' faild hasicon';
			$order['show_status'] = '<label>×</label>已取消';
		} elseif ($order['status'] == 3) {
			$order['cancel'] = 1;//退款
			$order['css'] = ' faild hasicon';
			$order['show_status'] = '<label>×</label>已退款';
		} elseif ($order['paid'] == 1) {
			if ($order['status']) {
				$order['css'] = ' faild hasicon';
				$order['show_status'] = '<label>√</label>已完成';
			} else {
				$order['css'] = ' processing';
				$order['show_status'] = '已付款';
				$order['jiaxcai'] = true;
				$order['cancel'] = 1;//退款
			}
		} else {
// 			if (intval($order['arrive_time']) + 10800 > time()) {//预定时间加3个小时
				$order['topay'] = true;
				if ($order['paid'] == 2) {
					$order['cancel'] = 1;//退款
				} else {
					$order['cancel'] = 2;//删除
					$order['jiaxcai'] = true;
				}
				$order['css'] = 'processing';
				$order['show_status'] = '处理中';
// 			} else {
// 				if ($order['paid'] == 2) {
// 					//$order['cancel'] = 1;//退款
// 			 	} else {
// 					//$order['cancel'] = 2;//删除
// 				}
// 				$order['css'] = ' faild hasicon';
// 				$order['show_status'] = '<label>×</label>已过期';
// 			}
		}
// 		if ($order['paid'] == 1 && !($order['pay_type'] == 'offline' && empty($order['third_id']))) $order['jiaxcai'] = false;
		$order['paytypestr'] = D('Pay')->get_pay_name($order['pay_type']);
		if ($order['paid'] == 0) {
			$order['paytypestr'] = $order['paidstr'] = '未支付';
		} elseif ($order['paid'] == 1) {
			if ($order['pay_type'] == 'offline' && empty($order['third_id'])) {
				$order['paidstr'] = '还未付款';
			} else {
				$order['paidstr'] = '全额付款';
			}
		} else {
			$order['paidstr'] = '已付订金';
		}
		
		if ($order['status'] > 2) {
			$order['topay'] = false;
			$order['jiaxcai'] = false;
			$order['cancel'] = 0;
		}
		if (empty($order['tableid'])) {
			$order['tablename'] = '不限';
		} else {
			$table = D('Merchant_store_table')->where(array('pigcms_id' => $order['tableid'], 'store_id' => $this->store_id))->find();
			$order['tablename'] = isset($table['name']) ? $table['name'] : '不限';
		}
		if(!empty($order['leveloff'])) $order['leveloff']=unserialize($order['leveloff']);
		/* 粉丝行为分析 */

		if(!empty($order['coupon_id'])) {
			$system_coupon = D('System_coupon')->get_coupon_info($order['coupon_id']);
			$this->assign('system_coupon',$system_coupon);
		}else if(!empty($order['card_id'])) {
			$card = D('Member_card_coupon')->get_coupon_info($order['card_id']);
			$this->assign('card', $card);
		}

		$this->behavior(array('mer_id' => $this->mer_id, 'biz_id' => $this->store_id));
		$this->assign('meallist', $meallist);
		$this->assign('order', $order);
		$this->display();
	}
	
	/**
	 * 客户收藏
	 */
	public function dolike()
	{
		if(empty($this->user_session)) exit('no');
		$meal_id = isset($_POST['meal_id']) ? intval($_POST['meal_id']) : 0;
		$islove = isset($_POST['islove']) ? intval($_POST['islove']) : 0;
		$like = D('Meal_like')->where(array('uid' => $this->user_session['uid'], 'mer_id' => $this->mer_id, 'store_id' => $this->store_id))->find();
		if ($like) {
			$meal_ids = unserialize($like['meal_ids']);
			if ($islove) {
				in_array($meal_id, $meal_ids) || $meal_ids[$meal_id] = $meal_id;
			} else {
				unset($meal_ids[$meal_id]);
			}
			D('Meal_like')->where(array('uid' => $this->user_session['uid'], 'mer_id' => $this->mer_id, 'store_id' => $this->store_id))->save(array('meal_ids' => serialize($meal_ids)));
		} elseif ($islove) {
			$meal_ids = array();
			$meal_ids[$meal_id] = $meal_id;
			D('Meal_like')->add(array('uid' => $this->user_session['uid'], 'mer_id' => $this->mer_id, 'store_id' => $this->store_id, 'meal_ids' => serialize($meal_ids)));
		}
	}
	
	private function isLogin()
	{
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！',U('Login/index', array('referer' => urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']))));
		}
	}
	
	public function orderdel()
	{
		$this->isLogin();
		$id = isset($_GET['orderid']) ? intval($_GET['orderid']) : 0;
		if ($order = M('Meal_order')->where(array('order_id' => $id, 'uid' => $this->user_session['uid'], 'mer_id' => $this->mer_id, 'store_id' => $this->store_id))->find()) {
			if ($order['paid'] == 1) $this->error('请您去退款，以免您的财产损失！');
			if ($order['paid'] == 1 && date('m', $order['dateline']) == date('m')) {
				foreach (unserialize($order['info']) as $menu) {
					D('Meal')->where(array('meal_id' => $menu['id'], 'sell_count' => array('gt', $menu['num'])))->setDec('sell_count', $menu['num']);
				}
			}
			D("Merchant_store_meal")->where(array('store_id' => $order['store_id']))->setDec('sale_count', 1);
			/* 粉丝行为分析 */
			$this->behavior(array('mer_id' => $this->mer_id, 'biz_id' => $this->store_id));
			
			M('Meal_order')->where(array('order_id' => $id, 'uid' => $this->user_session['uid'], 'mer_id' => $this->mer_id, 'store_id' => $this->store_id))->save(array('status' => 4));
			$this->success('订单取消成功', U('My/order_list', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id, 'type' => 0)));
		} else {
			$this->error('订单取消失败！');
		}
		
	}
	
	
	
	
	/**
	 * iPad点餐模板
	 */
	public function pad()
	{
		empty($this->_store) && $this->error("不存在的商家店铺!");
		if ($this->_store['store_type'] == 2) $this->error("您的店铺没有点餐的权限");
// 		$orid = isset($_GET['orid']) ? intval($_GET['orid']) : 0;

		//客户的购物车记录
// 		$disharr = unserialize($_SESSION[$this->session_index]);
		
		/**********已买的订单加菜处理
		if ($order = $this->check_order($orid)) {
			if ($order['paid'] != 1) {
				$info = unserialize($order['info']);
				foreach ($info as $om) {
					if (isset($disharr[$om['id']])) {
						$disharr[$om['id']]['num'] += $om['num'];
					} else {
						$disharr[$om['id']]['num'] = $om['num'];
					}
				}
			}
			$this->assign('orid', $orid);
		} else {
			$this->assign('orid', 0);
		}
		*********************************************/
		/**************客户收藏的菜品*****************/
// 		$like = D('Meal_like')->field('meal_ids')->where(array('uid' => $this->user_session['uid'], 'store_id' => $this->store_id, 'mer_id' => $this->mer_id))->find();
// 		$meal_ids = array();
// 		$like && $meal_ids = unserialize($like['meal_ids']);
		/**************客户收藏的菜品*****************/
		
		//菜品分类
		$sorts = M("Meal_sort")->where(array('store_id' => $this->store_id))->order('sort DESC, sort_id DESC')->select();
		
		$sort_list = array();
		$sortids = array();
		foreach ($sorts as $sort) {
			if ($sort['is_weekshow']) {
				$week = explode(",", $sort['week']);
				if (in_array(date("w"), $week)) {
					$sort_list[$sort['sort_id']] = $sort;
					$sortids[] = $sort['sort_id'];
				}
			} else {
				$sort_list[$sort['sort_id']] = $sort;
				$sortids[] = $sort['sort_id'];
			}
		}
		
		$nowMouth = date('Ym');
		$sort_meal_list = array();
		$meal_image_class = new meal_image();
		$meals = M("Meal")->where(array('store_id' => $this->store_id, 'sort_id' => array('in', $sortids), 'status' => 1))->order('sort DESC')->select();
		foreach ($meals as $meal) {
			if (isset($disharr[$m['meal_id']])) {
				$meal['num'] = $disharr[$meal['meal_id']]['num'];
			} else {
				$meal['num'] = 0;
			}
// 			if (in_array($m['meal_id'], $meal_ids)) {
// 				$m['like'] = 1;
// 			} else {
// 				$m['like'] = 0;
// 			}
// 			if ($meal['sell_mouth'] != $nowMouth) $meal['sell_count'] = 0;//跨月销售额清零
			$meal['image'] = $meal_image_class->get_image_by_path($meal['image'], $this->config['site_url'], 's');
			if (isset($sort_meal_list[$meal['sort_id']]['list'])) {
				$sort_meal_list[$meal['sort_id']]['list'][] = $meal;
			} else {
				$sort_meal_list[$meal['sort_id']]['list'] = array($meal);
				$sort_meal_list[$meal['sort_id']]['sort_id'] = $meal['sort_id'];
				$sort_meal_list[$meal['sort_id']]['sort_name'] = isset($sort_list[$meal['sort_id']]['sort_name']) ? $sort_list[$meal['sort_id']]['sort_name'] : '';
			}
		}
// 		echo "<pre/>";
// 		print_r($sort_meal_list);die;
		
		$temp_array = $sort_meal_list;
		$sort_meal_list = array();
		foreach ($sortids as $sort_id) {
			if (isset($temp_array[$sort_id])) {
				$sort_meal_list[$sort_id] = $temp_array[$sort_id];
			} else {
				unset($sort_list[$sort_id]);
			}
// 			isset($temp_array[$sort_id]) && $sort_meal_list[$sort_id] = $temp_array[$sort_id];
		}
		/* 粉丝行为分析 */
		$this->behavior(array('mer_id' => $this->mer_id, 'biz_id' => $this->store_id,'keyword'=>strval($_GET['keywords'])));
		
		$merchant = D('Merchant')->get_info($this->mer_id);
		$notOffline = 0;
		if ($merchant) {
			$notOffline = $merchant['is_close_offline'] == 0 && $merchant['is_offline'] == 1 ? 0 : 1;
		}
		$this->assign('notOffline', $notOffline);
		$tables = D('Merchant_store_table')->where(array('store_id' => $this->store_id))->select();
		$this->assign('tables', $tables);
		
		$this->assign('meals', $sort_meal_list);
		$this->assign("sortlist", $sort_list);
		$this->display();
	}
	
	public function save_pad_order()
	{
    	$tableid = isset($_POST['tableid']) ? intval($_POST['tableid']) : 0;
    	$pay_type = isset($_POST['pay_type']) ? intval($_POST['pay_type']) : 1;
    	$people_num = isset($_POST['num']) ? intval($_POST['num']) : 0;
    	$shop_cart = isset($_POST['shop_cart']) ? htmlspecialchars($_POST['shop_cart']) : '';

		if(empty($shop_cart)){
			exit(json_encode(array('error_code' => 1, 'msg' => '购物车没有商品，请重新下单')));
		}
    	$temp = explode(":", $shop_cart);
    	$store_id = $temp[0];
    	if ($store_id != $this->_store['store_id']) {
    		exit(json_encode(array('error_code' => 1, 'msg' => '店铺不存在')));
    	}
    	$menus = explode("|", $temp[1]);
    	$ids = $list = array();
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
    	}
    	
    	$ids && $meals = D("Meal")->field(true)->where(array('store_id' => $store_id, 'meal_id' => array('in', $ids)))->select();
		if(empty($meals)){
			exit(json_encode(array('error_code' => 1, 'msg' => '购物车没有商品，请重新下单')));
		}
		
		//店铺优惠条件
		$sorts_discout = D('Meal_sort')->get_sorts($store_id);
		
		$total_money = 0;
		$total_discount_money = 0;
		$vip_discount_money = 0;
		$store_discount_money = 0;
		
		
		$total = 0;
		$price = 0;
    	foreach ($meals as $meal) {
    		$info[] = array('id' => $meal['meal_id'], 'name' => $meal['name'], 'num' => $list[$meal['meal_id']], 'price' => $meal['price']);
			$total += $list[$meal['meal_id']];
			$total_money += $list[$meal['meal_id']] * $meal['price'];
					
			$t_discount = isset($sorts_discout[$meal['sort_id']]) && $sorts_discout[$meal['sort_id']] ? $sorts_discout[$meal['sort_id']] : 100;
			$store_discount_money += $meal['price'] * $list[$meal['meal_id']] * $t_discount / 100;
			
    	}

         //用户等级 优惠
		$finaltotalprice = 0;
		$store_meal = D('Merchant_store_meal')->where(array('store_id' => $store_id))->find();
		if ($this->user_session) {
			$level_off = false;
			if (!empty($this->user_level) && !empty($store_meal) && !empty($store_meal['leveloff'])) {
				$leveloff = unserialize($store_meal['leveloff']);	
				if (!empty($this->user_session) && isset($this->user_session['level'])) {
					/****type:0无优惠 1百分比 2立减*******/
					if (!empty($leveloff) && is_array($leveloff) && isset($this->user_level[$this->user_session['level']]) && isset($leveloff[$this->user_session['level']])) {
						$level_off = $leveloff[$this->user_session['level']];
						if ($sorts_discout['discount_type'] == 0) {
							if ($level_off['type'] == 1) {
								$vip_discount_money = $store_discount_money *($level_off['vv']/100);
								$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 								$level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
							} elseif($level_off['type'] == 2) {
								$vip_discount_money = $store_discount_money - $level_off['vv'];
								$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 								$level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
							}
						} else {
							if ($level_off['type'] == 1) {
								$vip_discount_money = $total_money *($level_off['vv']/100);
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
				unset($leveloff);
			}
		}
		$total_discount_money = $level_off ? $vip_discount_money : $store_discount_money;
		
// 		$price = $finaltotalprice > 0 ? round($finaltotalprice,2) : $price;
// 		$total_price = $price;
// 		$minus_price = 0;
		if ($store_meal && !empty($store_meal['minus_money']) && $total_discount_money >= $store_meal['full_money']) {
			$total_discount_money = $total_discount_money - $store_meal['minus_money'];
// 			$minus_price = $store_meal['minus_money'];
		}
		
		is_array($level_off) && $level_off['totalprice'] = $total_discount_money;
		
		$data = array('mer_id' => $this->_store['mer_id'], 'store_id' => $store_id, 'name' => '', 'phone' => '', 'address' => '', 'note' => '', 'info' => serialize($info), 'dateline' => time(), 'total' => $total, 'price' => $total_discount_money);
		$data['total_price'] = $total_money;
		$data['minus_price'] = $total_money - $total_discount_money;
		
		$data['num'] = $people_num;
		$data['tableid'] = $tableid;
		$data['meal_type'] = 2;//现场点餐
		$data['arrive_time'] = time();
		
		$data['orderid'] = $this->_store['mer_id'] . $store_id . date("YmdHis") . rand(1000000, 9999999);
		$data['uid'] = $this->user_session['uid'];
		$level_off && is_array($level_off) && $data['leveloff'] = serialize($level_off);
		$orderid = D("Meal_order")->add($data);
		if ($orderid) {
		    $printHaddle = new PrintHaddle();
		    $printHaddle->printit($orderid, 'meal_order', 0);
		    
			$sms_data = array('mer_id' => $store['mer_id'], 'store_id' => $store['store_id'], 'type' => 'food');
			if ($this->config['sms_place_order'] == 1 || $this->config['sms_place_order'] == 3) {
				$sms_data['uid'] = $this->user_session['uid'];
				$sms_data['mobile'] = $data['phone'] ? $data['phone'] : $this->user_session['phone'];
				$sms_data['sendto'] = 'user';
				$sms_data['content'] = '您在' . $store['name'] . '中下单成功，订单号：' . $orderid;
				Sms::sendSms($sms_data);
			}
			if ($this->config['sms_place_order'] == 2 || $this->config['sms_place_order'] == 3) {
				$sms_data['uid'] = 0;
				$sms_data['mobile'] = $store['phone'];
				$sms_data['sendto'] = 'merchant';
				$sms_data['content'] = '客户' . $data['name'] . '刚刚下了一个订单，订单号：' . $orderid . '，请您注意查看并处理';
				Sms::sendSms($sms_data);
			}
			exit(json_encode(array('error_code' => 0, 'msg' => '', 'url' => U('Pay/check',array('order_id' => $orderid, 'type'=>'foodPad', 'online' => $pay_type)))));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => D("Meal_order")->getError())));
		}
	}
	
	public function get_table()
	{
		empty($this->_store) && exit(json_encode(array('errcode' => 1, 'msg' => '不存在的店铺')));
		$store_id = $this->_store['store_id'];
		$date = isset($_POST['date']) ? $_POST['date'] : '';
		$time = isset($_POST['time']) ? $_POST['time'] : '';
		$arrive_time = strtotime($date . ' ' . $time . ':00');
		if ($arrive_time < time()) {
			$arrive_time = time();
// 			exit(json_encode(array('errcode' => 1, 'msg' => '预订时间不能小于当前时间')));
		}
		$star_time = $arrive_time - 7200;
		$end_time = $arrive_time + 7200;
		$tables = D('Merchant_store_table')->field(true)->where(array('store_id' => $store_id))->select();
		
		$orders = D('Meal_order')->field('tableid')->where("`store_id`={$store_id} AND `paid`=1 AND `status`=0 AND `is_confirm`=1 AND `arrive_time`>'{$star_time}' AND `arrive_time`<'{$end_time}' AND tableid>0")->select();
		$tids = array();
		foreach ($orders as $row) {
			$tids[$row['tableid']] = $row;
		}
		
		$table_list = array();
		foreach ($tables as $table) {
			if (!isset($tids[$table['pigcms_id']])) {
				$table_list[] = $table;
			}
		}
		exit(json_encode(array('errcode' => 0, 'data' => $table_list)));
	}
	
	public function logout()
	{
		session('user', null);
		exit();
	}
	
	
	private function notice($orderid, $is_add = 0)
	{
		$data = D('Meal_order')->field(true)->where(array('order_id' => $orderid))->find();
		if (empty($data)) return false;
		if ($this->user_session['openid']) {
			$keyword2 = '';
			$pre = '';
			foreach (unserialize($data['info']) as $menu) {
				$keyword2 .= $pre . $menu['name'] . ':' . $menu['price'] . '*' . $menu['num'];
				$pre = '\n\t\t\t';
			}
			$href = C('config.site_url').'/wap.php?c=Food&a=order_detail&order_id='. $orderid . '&mer_id=' . $data['mer_id'] . '&store_id=' . $data['store_id'];
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $orderid, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $keyword2, 'remark' => '您的该次'.$this->config['meal_alias_name'].'下单成功，感谢您的使用！'));
		}
		
		$printHaddle = new PrintHaddle();
		$printHaddle->printit($orderid, 'meal_order', 0);
		
		$sms_data = array('mer_id' => $this->mer_id, 'store_id' => $this->store_id, 'type' => 'food');
		if ($this->config['sms_place_order'] == 1 || $this->config['sms_place_order'] == 3) {
			$sms_data['uid'] = $this->user_session['uid'];
			$sms_data['mobile'] = $data['phone'] ? $data['phone'] : $this->user_session['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在' . $this->_store['name'] . '中预定的用餐的订单生产成功，订单号：' . $orderid;
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_place_order'] == 2 || $this->config['sms_place_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $this->_store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客' . $data['name'] . '刚刚下了一个订单，订单号：' . $orderid . '请您注意查看并处理';
			Sms::sendSms($sms_data);
		}
	}
}
?>