<?php
class FoodAction extends BaseAction
{
	//	商铺ID
	public $store_id = 0;
	//	商户ID
	public $mer_id = 0;
	//	商铺所有字段
	public $_store = null;
	public $session_index = '';
	public $session_table_index = '';
	public $order_index = '';
	//	等级优惠相关设置
	public $leveloff = '';
	
	
	public function __construct()
	{
		parent::__construct();
		$this->store_id = I('store_id',0);
		//店铺详情
		$merchant_store = M("Merchant_store")->where(array('store_id' => $this->store_id))->find();
		$this->mer_id	=	$merchant_store['mer_id'];
		if ($merchant_store['office_time']) {
			$merchant_store['office_time'] = unserialize($merchant_store['office_time']);
		} else {
			$merchant_store['office_time'][] = array('open' => $merchant_store['open_1'], 'close' => $merchant_store['close_1']);
			$merchant_store['office_time'][] = array('open' => $merchant_store['open_2'], 'close' => $merchant_store['close_2']);
			$merchant_store['office_time'][] = array('open' => $merchant_store['open_3'], 'close' => $merchant_store['close_3']);
		}
		
		$store_image_class = new store_image();
		$merchant_store['images'] = $store_image_class->get_allImage_by_path($merchant_store['pic_info']);
		$t = $merchant_store['images'];
		$merchant_store['image'] = array_shift($t);
		$merchant_store_meal = M("Merchant_store_meal")->where(array('store_id' => $this->store_id))->find();
		if ($merchant_store_meal) $merchant_store = array_merge($merchant_store, $merchant_store_meal);
		//	等级优惠相关设置	不需要，做完以后删除
		$this->leveloff=!empty($merchant_store_meal['leveloff']) ? unserialize($merchant_store_meal['leveloff']) :'';
		//	商铺所有字段
		$this->_store = $merchant_store;
		$this->session_index = "session_foods{$this->store_id}_{$this->mer_id}";
		$this->session_table_index = "session_table_{$this->store_id}_{$this->mer_id}";
		$this->order_index = "order_id_{$this->store_id}_{$this->mer_id}";
	}

	public function menu()
	{
		empty($this->_store) && $this->error("不存在的商家店铺!");
		$orid = isset($_GET['orid']) ? intval($_GET['orid']) : 0;

		//	查询选择的桌位
		$tableid	=	I('tableid');
		$tableid = isset($tableid) ? intval($tableid) : 0;
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
					$list[] = $sort;
					$ids[] = $sort['sort_id'];
				}
			} else {
				$list[] = $sort;
				$ids[] = $sort['sort_id'];
			}
		}

		$nowMouth = date('Ym');

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
// 			if ($m['sell_mouth'] != $nowMouth) $m['sell_count'] = 0;//跨月销售额清零
			$m['image'] = $meal_image_class->get_image_by_path($m['image'], $this->config['site_url'], 's');
			if (isset($t_meals[$m['sort_id']]['list'])) {
				$t_meals[$m['sort_id']]['list'][] = $m;
			} else {
				$t_meals[$m['sort_id']]['list'] = array($m);
				$t_meals[$m['sort_id']]['sort_id'] = $m['sort_id'];
			}
		}

		foreach ($ids as $sort_id) {
			isset($t_meals[$sort_id]) && $meals[$sort_id] = $t_meals[$sort_id];
		}
		$this->assign('meals', $meals);
		$this->assign("sortlist", $list);
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
		foreach ($foods as $kk => $vv) {
			$count = $vv['count'] ? intval($vv['count']) : 0;
			if ($count > 0) {
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
			foreach ($dish as $val) {
				$val['image'] = $meal_image_class->get_image_by_path($val['image'],$this->config['site_url'],'s');
				$disharr[$val['meal_id']] = array_merge($disharr[$val['meal_id']], $val);
			}
		}

		$allmark = $_SESSION["allmark" . $this->store_id . $this->mer_id];
		$this->assign('allmark', $allmark);
		$this->assign('ordishs', $disharr);
	}


	/**
	 * 填写客户的个人信息
	 */
	public function sureorder()
	{
        $disharr = $_POST['dish'];
        $allmark = htmlspecialchars(trim($_POST['allmark']), ENT_QUOTES);
        $_SESSION["allmark" . $this->store_id . $this->mer_id] = $allmark;
        $orid = $this->_get('orid') ? intval($this->_get('orid', "trim")) : 0;
        if ($this->_store) {
            $tmparr = array();
            foreach ($disharr as $dk => $dv) {
                if (!empty($dv)) {
                    $tmpnum = intval($dv['num']);
                    if ($tmpnum > 0) {
                        $tmparr[$dk] = array();
                        $tmparr[$dk]['id'] = $dk;
                        $tmparr[$dk]['num'] = $tmpnum;
                        $tmparr[$dk]['omark'] = htmlspecialchars(trim($dv['omark']), ENT_QUOTES);
                    }
                }
            }
            if ($tmparr) {
                $_SESSION[$this->session_index] = serialize($tmparr);
            }
			$totalmoney = trim($_POST['totalmoney']);
			$totalnum = trim($_POST['totalnum']);
			$level_off=false;
			$finaltotalprice=0;
			if(!empty($this->user_level) && !empty($this->leveloff) && !empty($this->user_session) && isset($this->user_session['level'])){
			     /****type:0无优惠 1百分比 2立减*******/
				if(isset($this->leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])){
					$level_off=$this->leveloff[$this->user_session['level']];
					if($level_off['type']==1){
					  $finaltotalprice=$totalmoney *($level_off['vv']/100);
					  $finaltotalprice=$finaltotalprice>0 ? $finaltotalprice : 0;
					  $level_off['offstr']='按此次总价'.$level_off['vv'].'%来结算';
					}elseif($level_off['type']==2){
					  $finaltotalprice=$totalmoney-$level_off['vv'];
					  $finaltotalprice=$finaltotalprice>0 ? $finaltotalprice : 0;
					  $level_off['offstr']='此次总价立减'.$level_off['vv'].'元';
					}
				}
			}
			$this->assign('totalmoney', $totalmoney);
			$this->assign('totalnum', $totalnum);
			$this->assign('finaltotalprice', round($finaltotalprice,2));
			$this->assign('level_off', $level_off);

			$tables = D('Merchant_store_table')->where(array('store_id' => $this->store_id))->select();
			$this->assign('tables', $tables);
			if (empty($tables)) {
				$this->assign('seattype', 0);
			} else {
				$this->assign('seattype', $tables[0]['pigcms_id']);
			}
            $user_info = D('User_adress')->get_one_adress($this->user_session['uid'], intval($_GET['adress_id']));
            $this->assign('date', date('Y-m-d'));
            $this->assign('time', date('H:i', time() + 1200));
            $this->assign('user_info', $user_info);
        } else {
            $jumpurl = U('Food/index', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id));
            $this->error('订单信息中店面信息出错', $jumpurl);
        }
	}

	/**
	 * 保存订单
	 */
	public function saveorder()
	{
		if(empty($this->user_session)){
			exit(json_encode(array('status' => 1, 'message' => '请先进行登录！', 'url' => U('Login/index'))));
		}

		$orid = isset($_REQUEST['orid']) ? intval($_REQUEST['orid']) : 0;

		$isdeposit = isset($_POST['isdeposit']) ? intval($_POST['isdeposit']) : 0;/***isdeposit 1 支付预定经***/
		$is_reserve = isset($_POST['is_reserve']) ? intval($_POST['is_reserve']) : 0;/***is_reserve 1 预约***/


		$total = $price = $tmpprice=0;
		$disharr = unserialize($_SESSION[$this->session_index]);
		$idarr = array_keys($disharr);
		$store_meal = D('Merchant_store_meal')->where(array('store_id' => $this->store_id))->find();
		if ($old_order = $this->check_order($orid)) {//加菜
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
					if (isset($oldmenus[$val['meal_id']])) {
						for ($i = 0; $i < count($info); $i++) {
							if ($info[$i]['id'] == $val['meal_id']) {
								$isadd = $info[$i]['num'] == $num ? 0 : 1;
								$info[$i] = array('id' => $val['meal_id'], 'name' => $val['name'], 'price' => $val['price'], 'num' => $num, 'omark' => $omark, 'isadd' => $isadd, 'iscount' => 0);
							}
						}
					} else {
						$info[] = array('id' => $val['meal_id'], 'name' => $val['name'], 'price' => $val['price'], 'num' => $num, 'omark' => $omark, 'isadd' => 1, 'iscount' => 0);
					}

					$total += $num;
					$tmpprice += $val['price'] * $num;
				}

				//会员等级优惠
				$level_off = false;
				$finaltotalprice = 0;
				if(!empty($this->user_level) && !empty($this->leveloff) && !empty($this->user_session) && isset($this->user_session['level'])){
					 /****type:0无优惠 1百分比 2立减*******/
					if(isset($this->leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])){
						$level_off = $this->leveloff[$this->user_session['level']];
						if($level_off['type'] == 1){
						  $finaltotalprice = $tmpprice *($level_off['vv'] / 100);
						  $finaltotalprice = $finaltotalprice > 0 ? $finaltotalprice : 0;
						  $level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
						} elseif($level_off['type'] == 2) {
						  $finaltotalprice = $tmpprice - $level_off['vv'];
						  $finaltotalprice = $finaltotalprice > 0 ? $finaltotalprice : 0;
						  $level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
						}
					}
				}

				if (!empty($old_order['leveloff'])) {
					$leveloff = unserialize($old_order['leveloff']);
					if ($old_order['paid'] == 1) {
						$price = $finaltotalprice > 0 ? $leveloff['totalprice']+$finaltotalprice : $leveloff['totalprice']+$tmpprice;
					} else {
						$price = $finaltotalprice > 0 ? $finaltotalprice : $tmpprice;
					}
					$price = round($price, 2);
					is_array($level_off) && $level_off['totalprice']=$price;
				}else{
					foreach ($info as $v) {
						$price += $v['price'] * $v['num'];
					}
					$price = max($price, $old_order['price']);
				}

				$total_price = $price;
				$minus_price = 0;
				if ($store_meal && !empty($store_meal['minus_money']) && $price > $store_meal['full_money']) {
					$price = $price - $store_meal['minus_money'];
					$minus_price = $store_meal['minus_money'];
				}

				$data = array('price' => $price, 'dateline' => time());
				if ($old_order['paid'] == 1) {
					$data['total'] = $total + $old_order['total'];
				} else {
					$data['total'] = $total;
				}
				$data['orderid'] = date("YmdHis") . sprintf("%08d", $this->user_session['uid']);
				$data['info'] = $info ? serialize($info) : '';

				$data['total_price'] = $total_price;
				$data['minus_price'] = $minus_price;

				$data['paid'] = $old_order['paid'] == 1 ? 2 : 0;

				!empty($level_off) && $data['leveloff']=serialize($level_off);

				if ($return = D("Meal_order")->where(array('order_id' => $orid, 'uid' => $this->user_session['uid']))->save($data)) {
					$_SESSION[$this->session_index]  = null;
					$_SESSION["allmark" . $this->store_id . $this->mer_id] = null;
					redirect(U('Pay/check', array('order_id' => $orid, 'type'=>'food')));
				} else {
					exit(json_encode(array('status' => 1, 'message' => '服务器繁忙，稍后重试！')));
					$this->error('服务器繁忙，稍后重试');
				}
			} else {
				$jumpurl = U('Food/menu', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id, 'orid' => $orid));
				//exit(json_encode(array('status' => 1, 'message' => '还没有加菜呢', 'url' => $jumpurl)));
				$this->error('还没有加菜呢', $jumpurl);
			}
		} else {//点菜的新单信息
			$phone = isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : '';
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
			$note = isset($_POST['mark']) ? htmlspecialchars($_POST['mark']) : '';
			$date = isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '';
			$time = isset($_POST['time']) ? htmlspecialchars($_POST['time']) : '';
			$sex = isset($_POST['sex']) ? intval($_POST['sex']) : 1;
			$num = isset($_POST['num']) ? intval($_POST['num']) : 2;
			$tableid = isset($_POST['seattype']) ? intval($_POST['seattype']) : 0;
			//if (empty($date)) exit(json_encode(array('status' => 1, 'message' => '使用日期不能为空')));
			//if (empty($time)) exit(json_encode(array('status' => 1, 'message' => '使用时间不能为空')));
			//if (empty($name)) exit(json_encode(array('status' => 1, 'message' => '您的姓名不能为空')));
			//if (empty($phone)) exit(json_encode(array('status' => 1, 'message' => '您的电话不能为空')));
			$arrive_time = strtotime($date . ' ' . $time . ":00");
			$info = array();
			if ($idarr) {//点餐
				$dish = M("Meal")->where(array('store_id' => $this->store_id, 'meal_id' => array('in', $idarr), 'status' => 1))->select();
				foreach ($dish as $val) {
					$num = isset($disharr[$val['meal_id']]['num']) ? intval($disharr[$val['meal_id']]['num']) : 0;
					$omark = isset($disharr[$val['meal_id']]['omark']) ? htmlspecialchars($disharr[$val['meal_id']]['omark']) : '';
					$info[] = array('id' => $val['meal_id'], 'name' => $val['name'], 'price' => $val['price'], 'num' => $num, 'omark' => $omark, 'isadd' => 0, 'iscount' => 0);
					$total += $num;
					$price += $val['price'] * $num;
				}
				//会员等级优惠
				$level_off=false;
				$finaltotalprice=0;
				if(!empty($this->user_level) && !empty($this->leveloff) && !empty($this->user_session) && isset($this->user_session['level'])){
					 /****type:0无优惠 1百分比 2立减*******/
					if(isset($this->leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])){
						$level_off=$this->leveloff[$this->user_session['level']];
						if($level_off['type']==1){
						  $finaltotalprice=$price *($level_off['vv']/100);
						  $finaltotalprice=$finaltotalprice>0 ? $finaltotalprice : 0;
						  $level_off['offstr']='按此次总价'.$level_off['vv'].'%来结算';
						}elseif($level_off['type']==2){
						  $finaltotalprice=$price-$level_off['vv'];
						  $finaltotalprice=$finaltotalprice>0 ? $finaltotalprice : 0;
						  $level_off['offstr']='此次总价立减'.$level_off['vv'].'元';
						}
					}
				}
			  $price=$finaltotalprice > 0 ? round($finaltotalprice,2) : $price;
			  $level_off && is_array($level_off) && $level_off['totalprice']=$price;
			} else {//预定
				if (empty($is_reserve)) {
					$jumpurl = U('Food/menu', array('mer_id' => $this->mer_id, 'store_id' => $this->store_id));
					exit(json_encode(array('status' => 1, 'message' => '您还没有买或订单已生成', 'url' => $jumpurl)));
					$this->error('您还没有买或订单已生成', $jumpurl);
				}
				$price = $this->_store['deposit'];
			}

			$total_price = $price;
			$minus_price = 0;

			if ($store_meal && !empty($store_meal['minus_money']) && $price > $store_meal['full_money']) {
				$price = $price - $store_meal['minus_money'];
				$minus_price = $store_meal['minus_money'];
			}


			$price = $isdeposit ? $this->_store['deposit'] : $price;

			if(isset($level_off) && $isdeposit && (isset($level_off['totalprice']) && $level_off['totalprice'] < $price)){
			    $level_off['totalprice'] = $price;
			}


			$data = array('mer_id' => $this->mer_id, 'tableid' => $tableid, 'store_id' => $this->store_id, 'name' => $name, 'phone' => $phone, 'address' => $address, 'note' => $note, 'dateline' => time(), 'total' => $total, 'price' => $price, 'arrive_time' => $arrive_time);
			$data['orderid'] = date("YmdHis") . sprintf("%08d", $this->user_session['uid']);
			$data['uid'] = $this->user_session['uid'];
			$data['sex'] = $sex;
			$data['num'] = $num;
			$data['info'] = $info ? serialize($info) : '';

			$data['total_price'] = $total_price;
			$data['minus_price'] = $minus_price;

			isset($level_off) && !empty($level_off) && $data['leveloff']=serialize($level_off);
			$orderid = D("Meal_order")->add($data);
			if ($orderid) {
				$_SESSION[$this->session_index] = null;
				$_SESSION["allmark" . $this->store_id . $this->mer_id] = null;
				if ($this->user_session['openid']) {
					$keyword2 = '';
					$pre = '';
					foreach (unserialize($data['info']) as $menu) {
						$keyword2 .= $pre . $menu['name'] . ':' . $menu['price'] . '*' . $menu['num'];
						$pre = '\n\t\t\t';
					}
					$href = C('config.site_url').'/wap.php?c=Food&a=order_detail&order_id='. $orderid . '&mer_id=' . $data['mer_id'] . '&store_id=' . $data['store_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $orderid, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $keyword2, 'remark' => '您的该次'.$this->config['meal_alias_name'].'下单成功，感谢您的使用！'), $this->mer_id);
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
				/* 粉丝行为分析 */
				$this->behavior(array('mer_id' => $this->mer_id, 'biz_id' => $orderid));
				if ($data['total_price'] - $data['minus_price'] <= 0) {
					D("Meal_order")->where(array('order_id' => $orderid))->save(array('paid' => 1));
					exit(json_encode(array('status' => 0, 'url' => U('Food/order_detail', array('order_id' => $orderid, 'mer_id'=> $this->mer_id, 'store_id' => $this->store_id)))));
				}
				exit(json_encode(array('status' => 0, 'url' => U('Pay/check', array('order_id' => $orderid, 'type'=>'food', 'isdeposit' => $isdeposit)))));
			} else {
				exit(json_encode(array('status' => 1, 'message' => '服务器繁忙，稍后重试')));
			}
		}
	}

	/**
	 * 订单列表
	 */
	public function order_list()
	{
		$meal_type = isset($_GET['meal_type']) ? intval($_GET['meal_type']) : 0;
		$this->assign('meal_type', $meal_type);
        $sql = "SELECT `s`.`name` as s_name, `o`.* FROM " . C('DB_PREFIX') . 'merchant_store AS s INNER JOIN ' . C('DB_PREFIX') . "meal_order as o ON s.store_id=o.store_id WHERE o.store_id={$this->store_id} AND o.mer_id={$this->mer_id} AND o.uid={$this->user_session['uid']}  AND o.meal_type={$meal_type} AND o.status<3 ORDER BY o.order_id DESC LIMIT 0, 30";
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
	}

	public function orderdel()
	{
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
		//菜品分类
		$sorts = M("Meal_sort")->where(array('store_id' => $this->store_id))->order('sort DESC, sort_id DESC')->select();
		if(empty($sorts)){
			$this->returnCode('20080002');
		}
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
		if(empty($meals)){
			$this->returnCode('20080003');
		}
		foreach ($meals as $meal) {
			if (isset($disharr[$m['meal_id']])) {
				$meal['num'] = $disharr[$meal['meal_id']]['num'];
			} else {
				$meal['num'] = 0;
			}
			$meal['image'] = $meal_image_class->get_image_by_path($meal['image'], $this->config['site_url'], 's');
			if (isset($sort_meal_list[$meal['sort_id']]['list'])) {
				$sort_meal_list[$meal['sort_id']]['list'][] = $meal;
			} else {
				$sort_meal_list[$meal['sort_id']]['sort_id'] = $meal['sort_id'];
				$sort_meal_list[$meal['sort_id']]['sort_name'] = isset($sort_list[$meal['sort_id']]['sort_name']) ? $sort_list[$meal['sort_id']]['sort_name'] : '';
				$sort_meal_list[$meal['sort_id']]['list'] = array($meal);
			}
		}
		$temp_array = $sort_meal_list;
		$sort_meal_list = array();
		foreach ($sortids as $sort_id) {
			if (isset($temp_array[$sort_id])) {
				$sort_meal_list[$sort_id] = $temp_array[$sort_id];
			} else {
				unset($sort_list[$sort_id]);
			}
		}
		sort($sort_meal_list);
		$tables = D('Merchant_store_table')->where(array('store_id' => $this->store_id))->select();
		$arr['tables']			=	isset($tables)?$tables:array();
		$arr['sort_meal_list']	=	isset($sort_meal_list)?$sort_meal_list:array();
		$this->returnCode(0,$arr);
	}
	//	订单保存
	public function save_pad_order()
	{
    	$tableid = I('tableid');
    	$num 	= 	intval(I('num',0));
    	$shop_cart	=isset($_POST['shop_cart'])?$_POST['shop_cart']:0;
    	$store_id	=	I('store_id');
		$shop_cart	=	str_replace('&','"',$shop_cart);
		$shop_cart	=	str_replace('amp;','',$shop_cart);
		$shop_cart	=	str_replace('quot;','',$shop_cart);
		if(empty($shop_cart)){
			$this->returnCode('20080011');
		}
		$l	=	substr($shop_cart,0,1);
		if($l	==	"'"){
			$shop_cart	=	ltrim($shop_cart,"'");
		}
		$r	=	substr($shop_cart,-1,1);
		if($r	==	"'"){
			$shop_cart	=	rtrim($shop_cart, "'");
		}
		$temp	=	json_decode($shop_cart,true);
    	if ($store_id != $this->_store['store_id']) {
    		$this->returnCode('20046001');
    	}
    	$ids = $list = array();
    	foreach ($temp as $m){
    		$ids[] = $m['meal_id'];
    		$list[$m['meal_id']] = $m;
    	}
    	$meals = D("Meal")->field(true)->where(array('store_id' => $store_id, 'meal_id' => array('in', $ids)))->select();
		if(empty($meals)){
			$this->returnCode('20080011');
		}

		$total = 0;
		$price = 0;
    	foreach ($meals as $meal) {
    		$info[] = array('id' => $meal['meal_id'],'name' => $meal['name'], 'num' => $list[$meal['meal_id']]['meal_num'], 'price' => $meal['price']);
			$total += $list[$meal['meal_id']]['meal_num'];
			$price += $list[$meal['meal_id']]['meal_num'] * $meal['price'];
    	}
		$data = array('mer_id' => $this->_store['mer_id'], 'store_id' => $store_id, 'name' => '', 'phone' => '', 'address' => '', 'note' => '', 'info' => serialize($info), 'dateline' => time(), 'total' => $total, 'price' => $price);
		$data['total_price'] = $price;
		$data['minus_price'] = 0;
		$data['num'] = $num;
		$data['tableid'] = $tableid;
		$data['meal_type'] = 2;//现场点餐
		$data['arrive_time'] = time();
		$data['orderid'] = $this->_store['mer_id'] . $store_id . date("YmdHis") . rand(1000000, 9999999);
		$data['uid'] = 0;
		$orderid = D("Meal_order")->add($data);
		if ($orderid) {
		    $printHaddle = new PrintHaddle();
		    $printHaddle->printit($orderid, 'meal_order', 0);
			$this->returnCode(0,array('msg'=>"您的订单已生成，一共{$price}元"));
		} else {
			$this->returnCode('20080010');
		}
	}
}
?>