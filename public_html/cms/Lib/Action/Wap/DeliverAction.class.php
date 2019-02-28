<?php
/**
 * 配送员登录
 * @author yanleilei
 */
class DeliverAction extends BaseAction 
{
	protected $deliver_session;
	protected $item = array(0 => "老的餐饮外送", 1 => "外卖", 2 => "新快店");
	protected $deliver_supply;
	
	public function __construct()
	{
	    parent::__construct();
	    redirect($this->config['site_url'].'/packapp/deliver/index.html');
	    exit;
		$this->deliver_session = session('deliver_session');
		$this->deliver_session = !empty($this->deliver_session)? unserialize($this->deliver_session): false;
		if (ACTION_NAME != 'logout') {
			if (empty($this->deliver_session) && $this->is_wexin_browser && !empty($_SESSION['openid'])) {
				if ($user = D('Deliver_user')->field(true)->where(array('openid' => trim($_SESSION['openid'])))->find()) {
					session('deliver_session', serialize($user));
					$this->deliver_session = $user;
				}
			}
			
			if (empty($this->deliver_session)) {
				if (ACTION_NAME != 'login') {
					redirect(U('Deliver/login', array('referer' => urlencode('http://' . $_SERVER['HTTP_HOST'] . (!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'])))));
					exit();
				}
			} else {
				if ($user = D('Deliver_user')->field(true)->where(array('uid' => $this->deliver_session['uid']))->find()) {
					if (empty($user['status'])) {
						session('deliver_session', null);
						$this->error_tips("您的账号已禁止");
						exit;
					}
				}
// 				if (ACTION_NAME == 'login') {
// 					redirect(U('Deliver/grab'));
// 				}
				$this->assign('deliver_session', $this->deliver_session);
			}
		}
		$this->assign('merchantstatic_path', $this->config['site_url'] . '/tpl/Merchant/static/');
		$this->deliver_supply = D("Deliver_supply");
		
		//查看骑士是否需要上传位置
		$where = array();
		$where['status'] = array('in', '3, 4');
		// $where['item'] = 1;
		$where['uid'] = $this->deliver_session['uid'];
		$have_send = $this->deliver_supply->field("`supply_id`")->where($where)->find();
		if ($have_send) {
			$this->assign("have_send", true);
		} else {
			$this->assign("have_send", false);
		}
// 		if (ACTION_NAME == 'index') {
// 			redirect(U('grab'));
// 		}

		if($this->config['open_score_get_percent']==1){
			$this->config['score_get'] = $this->config['score_get_percent']/100;
		}else{
			$this->config['score_get'] =  $this->config['user_score_get'];
		}
		$store = D('Merchant_store')->field(true)->where(array('store_id' => $this->deliver_session['store_id']))->find();
		$now_merchant = D('Merchant')->get_info($store['mer_id']);
        if($now_merchant['score_get_percent']>=0){
            $this->config['score_get'] = $now_merchant['score_get_percent']/100;
        }
	}
	
	/**
	 * 登录
	 */
	public function login()
	{
		if (IS_POST) {
			$condition_deliver_user['phone'] = trim($_POST['phone']);
			$database_deliver_user = D('Deliver_user');
			$now_user = $database_deliver_user->field(true)->where($condition_deliver_user)->find();
			if (empty($now_user)) {
				exit(json_encode(array('error' => 2, 'msg' => '帐号不存在！', 'dom_id' => 'account')));
			}
			if (empty($now_user['status'])) {
				exit(json_encode(array('error' => 2, 'msg' => '此账号已冻结！', 'dom_id' => 'account')));
			}
			$pwd = md5(trim($_POST['pwd']));
			if ($pwd != $now_user['pwd']) {
				exit(json_encode(array('error' => 3, 'msg' => '密码错误！', 'dom_id' => 'pwd')));
			}
			$data_deliver_user['last_time'] = $_SERVER['REQUEST_TIME'];
			if ($database_deliver_user->where(array('uid'=>$now_user['uid']))->data($data_deliver_user)->save()) {
				session('deliver_session', serialize($now_user));
				$is_bind = $now_user['openid'] ? 1 : 0;
				exit(json_encode(array('error' => 0, 'msg' => '登录成功,现在跳转~', 'dom_id' => 'account', 'is_bind' => $is_bind)));
			} else {
				exit(json_encode(array('error' => 6, 'msg' => '登录信息保存失败,请重试！', 'dom_id' => 'account')));
			}
		} else {
			if ($this->is_wexin_browser && !empty($_SESSION['openid'])) {
				$this->assign('openid', $_SESSION['openid']);
			}
			$referer = isset($_GET['referer']) ? htmlspecialchars_decode(urldecode($_GET['referer']),ENT_QUOTES) : '';
			$this->assign('refererUrl', $referer);
			$this->display();
		}
	}
	
	
	public function logout()
	{
		$_SESSION['deliver_session'] = null;
		redirect(U('Deliver/login'));
	}
	
	/**
	 * 绑定微信，下次免登录
	 */
	public function freeLogin()
	{
		if(IS_POST && $this->is_wexin_browser && !empty($_SESSION['openid']) && is_array($this->deliver_session)){
			if ($old_user = D('Deliver_user')->where(array('openid' => trim($_SESSION['openid'])))->find()) {
				exit(json_encode(array('error' => 1, 'msg' => '该微信号已被绑定了' . $old_user['phone'] . '账号，不能重复绑定')));
			} else {
				if (D('Deliver_user')->where(array('uid'=>$this->deliver_session['uid']))->save(array('openid' => trim($_SESSION['openid']), 'last_time' => time()))) {
					exit(json_encode(array('error' => 0)));
				} else {
					exit(json_encode(array('error' => 1, 'msg' => '绑定失败，请下次登录再试')));
				}
			}
		}
		exit(json_encode(array('error' => 1, 'msg' => '绑定失败，请下次登录再试')));
	}
	
	
	public function index() 
	{
		if ($this->deliver_session['store_id']) {
			$store = D('Merchant_store')->field(true)->where(array('store_id' => $this->deliver_session['store_id']))->find();
			$store_image_class = new store_image();
			$images = $store_image_class->get_allImage_by_path($store['pic_info']);
			$store['image'] = $images ? array_shift($images) : '';
			$this->assign('store', $store);
		}
		

		$my_distance = $this->deliver_session['range'] * 1000;
		$time = time();
		if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
		    $where = "`create_time`<$time AND `status`=1";
			$where = "`type`= 1 AND `store_id`=" . $this->deliver_session['store_id'] . " AND " . $where;
		} else {
		    $where = "`create_time`<$time AND `status`=1 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$this->deliver_session['lat']}*PI()/180-`from_lat`*PI()/180)/2),2)+COS({$this->deliver_session['lat']}*PI()/180)*COS(`from_lat`*PI()/180)*POW(SIN(({$this->deliver_session['lng']}*PI()/180-`from_lnt`*PI()/180)/2),2)))*1000) <= $my_distance ";
			$where = "`type`= 0 AND " . $where;
		}
		$gray_count = D("Deliver_supply")->where($where)->count();
		
		$deliver_count = D('Deliver_supply')->where(array('uid' => $this->deliver_session['uid'], 'status' => array(array('gt', 1), array('lt', 5))))->count();
		$finish_count = D('Deliver_supply')->where(array('uid' => $this->deliver_session['uid'], 'status' => 5))->count();
		
		$this->assign(array('gray_count' => $gray_count, 'deliver_count' => $deliver_count, 'finish_count' => $finish_count));
		$this->display();
	}
	
	public function index_count()
	{
		$my_distance = $this->deliver_session['range'] * 1000;
		$time = time();
		
		if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
		    $where = "`create_time`<$time AND `status`=1";
			$where = "`type`= 1 AND `store_id`=" . $this->deliver_session['store_id'] . " AND " . $where;
		} else {
		    $where = "`create_time`<$time AND `status`=1 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$this->deliver_session['lat']}*PI()/180-`from_lat`*PI()/180)/2),2)+COS({$this->deliver_session['lat']}*PI()/180)*COS(`from_lat`*PI()/180)*POW(SIN(({$this->deliver_session['lng']}*PI()/180-`from_lnt`*PI()/180)/2),2)))*1000) <= $my_distance ";
			$where = "`type`= 0 AND " . $where;
		}
		$gray_count = D("Deliver_supply")->where($where)->count();
		
		$deliver_count = D('Deliver_supply')->where(array('uid' => $this->deliver_session['uid'], 'status' => array(array('gt', 0), array('lt', 5))))->count();
		$finish_count = D('Deliver_supply')->where(array('uid' => $this->deliver_session['uid'], 'status' => 5))->count();
		exit(json_encode(array('err_code' => false, 'gray_count' => $gray_count, 'deliver_count' => $deliver_count, 'finish_count' => $finish_count)));
	}
	
	private function rollback($supply_id, $status)
	{
		$data = array();
		switch ($status) {
			case 1:
				$data = array('uid' => 0, 'status' => 1);
				break;
			case 2:
				$data = array('status' => 2, 'start_time' => 0);
				break;
			case 3:
				$data = array('status' => 3);
				break;
			case 4:
				$data = array('status' => 4, 'end_time' => 0);
				break;
		}
		$this->deliver_supply->where(array("supply_id" => $supply_id))->save($data);
	}
	//抢
	public function grab()
	{
		if (IS_POST) {
			if ($user = D('Deliver_user')->where(array('uid' => $this->deliver_session['uid']))->find()) {
				if (empty($user['status'])) {
					$this->error("您的账号已禁止，不能抢单");
					exit;
				}
			}
			$supply_id = intval(I("supply_id"));
			if (!$supply_id) {
				$this->error("参数错误");
				exit;
			}
			
			if ($user['max_num'] > 0) {
			    $count = $this->deliver_supply->where('uid=' . $user['uid'] . ' AND status>0 AND status<5')->count();
			    if ($count >= $user['max_num']) {
			        $this->returnCode(1, null, '您当前已有' . $count . '单没有完成配送，请配送完成再来抢单！');
			    }
			}
			$supply = $this->deliver_supply->field(true)->where(array('supply_id' => $supply_id))->find();
			if (empty($supply)) {
				$this->error("配送信息错误");
				exit;
			}
			
			if ($supply['status'] != 1) {
				$this->error("已被抢单，不能再抢了");
				exit;
			}
			
			$uid = $this->deliver_session['uid'];
			$columns = array('uid' => $this->deliver_session['uid'], 'status' => 2);
			$columns['start_time'] = time();
			
			if ($supply['server_type'] == 2 || $supply['server_type'] == 3) {
			    $columns['appoint_time'] = time() + 60 * $supply['server_time'];
			}
			
			$result = $this->deliver_supply->where(array("supply_id" => $supply_id, 'status' => 1))->save($columns);
			if (false === $result) {
				$this->error("抢单失败");
				exit;
			}

			$order_id = $supply['order_id'];
			
			if ($supply['item'] == 1) {
				$order = D("Waimai_order")->find($order_id);
				if ($order['order_status'] != 3) {
					$this->rollback($supply_id, 1);
					$this->error("订单信息错误");
					exit;
				}
				//更新订单状态
				$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>8))->save();
				if (!$result) {
					$this->rollback($supply_id, 1);
					$this->error("更新订单信息错误");
					exit;
				}
				//添加订单日志
				$log = array();
				$log['status'] = 8;
				$log['order_id'] = $order_id;
				$log['store_id'] = $order['store_id'];
				$log['uid'] = $uid;
				$log['time'] = time();
				$log['group'] = 4;
				$result = D("Waimai_order_log")->add($log);
				if (!$result) {
					$this->rollback($supply_id, 1);
					$this->error("添加订单日志失败");
					exit;
				}
// 				D()->commit();
			} elseif ($supply['item'] == 0) {
// 				D()->commit();
				//更新订单状态
				$order = D("Meal_order")->where(array('order_id' => $order_id))->find();
				if ($order['order_status'] != 3) {
					$this->rollback($supply_id, 1);
					$this->error("订单信息错误");
					exit;
				}
				//更新订单状态
				$result = D("Meal_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>8))->save();
				if (!$result) {
					$this->rollback($supply_id, 1);
					$this->error("更新订单信息错误");
					exit;
				}
			} elseif ($supply['item'] == 2) {
// 				D()->commit();
				//更新订单状态
				$order = D("Shop_order")->where(array('order_id' => $order_id))->find();
				if ($order['order_status'] != 1) {
					$this->rollback($supply_id, 1);
					$this->error("订单信息错误");
					exit;
				}
				//更新订单状态
				$deliver_info = serialize(array('uid' => $this->deliver_session['uid'], 'name' => $this->deliver_session['name'], 'phone' => $this->deliver_session['phone'], 'store_id' => $this->deliver_session['store_id']));
				$result = D("Shop_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>2, 'deliver_info' => $deliver_info))->save();
				if (!$result) {
					$this->rollback(1);
					$this->error("更新订单信息错误");
					exit;
				}
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 3, 'name' => $this->deliver_session['name'], 'phone' => $this->deliver_session['phone']));
			} elseif ($supply['item'] == 3) {
			    $offer_id = D('Service_offer')->add_offer($order_id, $this->deliver_session['uid']);
			    $data = array('offer_id' => $offer_id);
			    if ($supply['server_type'] != 1) {
			        $data['appoint_time'] = time() + $supply['server_time'] * 60;
			    }
			    $this->deliver_supply->where(array('supply_id' => $supply_id))->save($data);
			}
			$this->success("抢单成功");exit;
		}
		
		if (IS_AJAX) {
		    if ($this->config['deliver_model']) {
		        exit(json_encode(array('err_code' => false, 'list' => '')));
		    }
			$lat = isset($_GET['lat']) && $_GET['lat'] ? $_GET['lat'] : $this->deliver_session['lat'];
			$lng = isset($_GET['lng']) && $_GET['lng'] ? $_GET['lng'] : $this->deliver_session['lng'];

			$my_lnt = $this->deliver_session['lng'];
			$my_lat = $this->deliver_session['lat'];
			$my_distance = $this->deliver_session['range'] * 1000;
			
			
			if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
			    $where = "`status`=1";
				$where = "`type`= 1 AND `store_id`=" . $this->deliver_session['store_id'] . " AND " . $where;
			} else {
			    $where = "`status`=1 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$my_lat}*PI()/180-`from_lat`*PI()/180)/2),2)+COS({$my_lat}*PI()/180)*COS(`from_lat`*PI()/180)*POW(SIN(({$my_lnt}*PI()/180-`from_lnt`*PI()/180)/2),2)))*1000) <= $my_distance ";
				$where = "`type`= 0 AND " . $where;
			}
			
			$list = D('Deliver_supply')->field(true)->where($where)->order("`create_time` DESC")->select();
			
			if (empty($list)) {
				exit(json_encode(array('err_code' => true)));
			}
			
			foreach ($list as &$val) {
				switch ($val['pay_type']) {
					case 'offline':
						$val['pay_method'] = 0;
						break;
					default:
						if ($val['paid']) {
							$val['pay_method'] = 1;
						} else {
							$val['pay_method'] = 0;
						}
						break;
				}
				$val['deliver_cash'] = floatval($val['deliver_cash']);
				$val['distance'] = floatval($val['distance']);
				$val['freight_charge'] = floatval($val['freight_charge']);
				$val['create_time'] = date('Y-m-d H:i', $val['create_time']);
				$val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
				$val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
				$val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
				$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
				$val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
				$val['image'] = $val['image'] ? explode(';', trim($val['image'], ';')) : array();
			}
			exit(json_encode(array('err_code' => false, 'list' => $list)));
		}
		
		$this->display();
	}
	
	//取
	public function pick() 
	{
		$uid = $this->deliver_session['uid'];
		if (IS_POST) {
			$supply_id = intval(I("supply_id"));
			if (!$supply_id) {
				$this->error("参数错误");
				exit;
			}
			
			$supply = $this->deliver_supply->field(true)->where(array('supply_id' => $supply_id, 'uid' => $this->deliver_session['uid']))->find();
			if (empty($supply)) {
				$this->error("配送信息错误");
				exit;
			}
			if ($supply['status'] != 2) {
				$this->error("此单暂时不能进行取货操作");
				exit;
			}
			
			$order_id = $supply['order_id'];
			
			$columns = array();
			$columns['uid'] = $uid;
			$columns['status'] = 3;
			$result = $this->deliver_supply->where(array("supply_id"=>$supply_id, 'status'=>2))->data($columns)->save();
			if (false === $result) {
				$this->error("更新状态失败");
				exit;
			}
			if ($supply['item'] == 1) {
				//获取订单信息
				$order = D("Waimai_order")->find($order_id);
				if (!$order) {
					$this->rollback($supply_id, 2);
					$this->error("订单信息错误");
					exit;
				}
				
				//更新订单状态
				$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>4))->save();
				if (!$result) {
					$this->rollback($supply_id, 2);
					$this->error("更新订单信息错误");
					exit;
				}
				//添加订单日志
				$log = array();
				$log['status'] = 4;
				$log['order_id'] = $order_id;
				$log['store_id'] = $order['store_id'];
				$log['uid'] = $uid;
				$log['time'] = time();
				$log['group'] = 4;
				$result = D("Waimai_order_log")->add($log);
				if (!$result) {
					$this->rollback($supply_id, 2);
					$this->error("添加订单日志失败");
					exit;
				}
			} elseif ($supply['item'] == 0) {
				//更新订单状态
				$result = D("Meal_order")->where(array('order_id' => $order_id))->data(array('order_status' => 4))->save();
				if (!$result) {
					$this->rollback($supply_id, 2);
					$this->error("更新订单信息错误");
					exit;
				}
			} elseif ($supply['item'] == 2) {
				//更新订单状态
				$result = D("Shop_order")->where(array('order_id' => $order_id))->data(array('order_status' => 3))->save();
				if (!$result) {
					$this->rollback($supply_id, 2);
					$this->error("更新订单信息错误");
					exit;
				}
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 4, 'name' => $this->deliver_session['name'], 'phone' => $this->deliver_session['phone']));
			} elseif ($supply['item'] == 3) {
			    D('Service_offer')->offer_save_status($order_id, $this->deliver_session['uid'], $supply['offer_id'], 9);
			}
			$this->success("更新状态成功");
			exit;
		}
		$where = array();
		$where['status'] = 2;
		$where['uid'] = $uid;
		if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
			$where['store_id'] = $this->deliver_session['store_id'];
		}
		$list = $this->deliver_supply->field(true)->where($where)->order("`create_time` DESC")->select();
		if (false === $list) {
			$this->error("系统错误");
			exit;
		}
		
		foreach ($list as &$val) {
			switch ($val['pay_type']) {
				case 'offline':
					$val['pay_method'] = 0;
					break;
				default:
					if ($val['paid']) {
						$val['pay_method'] = 1;
					} else {
						$val['pay_method'] = 0;
					}
					break;
			}
			$val['deliver_cash'] = floatval($val['deliver_cash']);
			$val['distance'] = floatval($val['distance']);
			$val['freight_charge'] = floatval($val['freight_charge']);
			$val['create_time'] = date('Y-m-d H:i', $val['create_time']);
			$val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
			$val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
			$val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
// 			$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
			$val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
			$val['image'] = $val['image'] ? explode(';', trim($val['image'], ';')) : array();
			if ($val['change_log']) {
				$changes = explode(',', $val['change_log']);
				$uid = array_pop($changes);
				$val['change_name'] = $this->getDeliverUser($uid);
			}
		}
		$this->assign('list', $list);
		$this->display();
	}
	
	private function getDeliverUser($uid)
	{
		$user = D('Deliver_user')->field(true)->where(array('uid' => $uid))->find();
		return isset($user['name']) ? $user['name'] : '';
	}
	//送
	public function send() 
	{
		$uid = $this->deliver_session['uid'];
		if (IS_POST) {
			$supply_id = intval(I("supply_id"));
			if (!$supply_id) {
				$this->error("参数错误");
				exit;
			}
			
			$supply = $this->deliver_supply->field(true)->where(array('supply_id' => $supply_id, 'uid' => $this->deliver_session['uid']))->find();
			if (empty($supply)) {
				$this->error("配送信息错误");
				exit;
			}
			if ($supply['status'] != 3) {
				$this->error("此单暂时不能进行配送操作");
				exit;
			}
			
			$order_id = $supply['order_id'];
			
// 			D()->startTrans();
			$columns = array();
			$columns['uid'] = $uid;
			$columns['status'] = 4;
			//$columns['end_time'] = time();
			$result = $this->deliver_supply->where(array("supply_id"=>$supply_id, 'status'=>3))->data($columns)->save();
			if (false === $result) {
				$this->error("更新状态失败");exit;
			}
			if ($supply['item'] == 1) {
				//获取订单信息
				$order = D("Waimai_order")->find($order_id);
				if (!$order) {
					$this->rollback($supply_id, 3);
					$this->error("订单信息错误");
					exit;
				}
				
				//更新订单状态
				$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>5))->save();
				if (!$result) {
					$this->rollback($supply_id, 3);
					$this->error("更新订单信息错误");
					exit;
				}
				//添加订单日志
				$log = array();
				$log['status'] = 5;
				$log['order_id'] = $order_id;
				$log['store_id'] = $order['store_id'];
				$log['uid'] = $uid;
				$log['time'] = time();
				$log['group'] = 4;
				$result = D("Waimai_order_log")->add($log);
				if (!$result) {
					$this->rollback($supply_id, 3);
					$this->error("添加订单日志失败");
					exit;
				}
			} elseif ($supply['item'] == 0) {
				//更新订单状态
				$result = D("Meal_order")->where(array('order_id' => $order_id))->data(array('order_status' => 5))->save();
				if (!$result) {
					$this->rollback($supply_id, 3);
					$this->error("更新订单信息错误");
					exit;
				}
			} elseif ($supply['item'] == 2) {
// 				D()->commit();
				//更新订单状态
				$result = D("Shop_order")->where(array('order_id' => $order_id))->data(array('order_status' => 4))->save();
				if (!$result) {
					$this->rollback($supply_id, 3);
					$this->error("更新订单信息错误");
					exit;
				}
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 5, 'name' => $this->deliver_session['name'], 'phone' => $this->deliver_session['phone']));
			}
			$this->success("更新状态成功");
			exit;
		}
		$where = array();
		$where['status'] = 3;
		// $where['item'] = 1;
		$where['uid'] = $uid;
		if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
			$where['store_id'] = $this->deliver_session['store_id'];
		}
		$list = $this->deliver_supply->field(true)->where($where)->order("`create_time` DESC")->select();
		if (false === $list) {
			$this->error("系统错误");exit;
		}
		
		foreach ($list as &$val) {
			switch ($val['pay_type']) {
				case 'offline':
					$val['pay_method'] = 0;
					break;
				default:
					if ($val['paid']) {
						$val['pay_method'] = 1;
					} else {
						$val['pay_method'] = 0;
					}
					break;
			}
			$val['deliver_cash'] = floatval($val['deliver_cash']);
			$val['distance'] = floatval($val['distance']);
			$val['freight_charge'] = floatval($val['freight_charge']);
			$val['create_time'] = date('Y-m-d H:i', $val['create_time']);
			$val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
			$val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
			$val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
// 			$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
			$val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
			$val['image'] = $val['image'] ? explode(';', trim($val['image'], ';')) : array();
			if ($val['change_log']) {
				$changes = explode(',', $val['change_log']);
				$uid = array_pop($changes);
				$val['change_name'] = $this->getDeliverUser($uid);
			}
		}
		$this->assign('list', $list);
		$this->display();
	}
	
	//我的
	public function my()
	{
		$uid = $this->deliver_session['uid'];
		if (IS_POST) {
			$supply_id = intval(I("supply_id"));
			if (! $supply_id) {
				$this->error("参数错误");exit;
			}
			
			$supply = $this->deliver_supply->field(true)->where(array('supply_id' => $supply_id, 'uid' => $this->deliver_session['uid']))->find();
			if (empty($supply)) {
				$this->error("配送信息错误");
				exit;
			}
			if ($supply['status'] != 4) {
				$this->error("此单暂时不能进行配送完成操作");
				exit;
			}
			
			$order_id = $supply['order_id'];
			
// 			D()->startTrans();
			$columns = array();
			$columns['uid'] = $uid;
			$columns['status'] = 5;
			$columns['paid'] = 1;
			$columns['end_time'] = time();

			if ($supply['type'] == 0 && $supply['pay_type'] == 'offline') {
				$columns['pay_type'] = '';
			}
				
			$result = $this->deliver_supply->where(array("supply_id"=>$supply_id, 'status'=>4))->data($columns)->save();
			if (false === $result) {
				$this->error("更新状态失败");exit;
			}
			
			if ($supply['item'] == 1) {
			
				//获取订单信息
				$order = D("Waimai_order")->find($order_id);
				if (!$order) {
					$this->rollback($supply_id, 4);
					$this->error("订单信息错误");
				}
				
				//更新订单状态
				$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>1))->save();
				if (!$result) {
					$this->rollback($supply_id, 4);
// 					D()->rollback();
					$this->error("更新订单信息错误");
					exit;
				}
				//添加订单日志
				$log = array();
				$log['status'] = 1;
				$log['order_id'] = $order_id;
				$log['store_id'] = $order['store_id'];
				$log['uid'] = $uid;
				$log['time'] = time();
				$log['group'] = 4;
				$result = D("Waimai_order_log")->add($log);
				if (!$result) {
					$this->rollback($supply_id, 4);
					$this->error("添加订单日志失败");
					exit;
// 					D()->rollback();
				}
// 				D()->commit();
			} elseif ($supply['item'] == 0) {
// 				D()->commit();
				if ($order = D("Meal_order")->field(true)->where(array('order_id' => $order_id))->find()) {
					$data = array('order_status' => 1, 'status' => 1);//配送状态更改成已完成，订单状态改成已消费
					if ($order['paid'] == 0) {
						$data['paid'] = 1;
						if (empty($data['pay_type']) && empty($data['pay_time'])) $data['pay_type'] = 'offline';
					}
					if (empty($order['pay_time'])) $data['pay_time'] = time();
					if (empty($order['use_time'])) $data['use_time'] = time();
					if (empty($order['third_id'])) $data['third_id'] = $order['order_id'];
					if ($result = D("Meal_order")->where(array('order_id' => $order_id))->data($data)->save()) {
						$this->meal_notice($order);
					} else {
						$this->rollback($supply_id, 4);
						$this->error("更新订单信息错误");
						exit;
					}
				} else {
					$this->rollback($supply_id, 4);
					$this->error("订单信息错误");
					exit;
				}
			} elseif ($supply['item'] == 2) {//快店的配送
// 				D()->commit();
				if ($order = D("Shop_order")->field(true)->where(array('order_id' => $order_id))->find()) {
					//配送状态更改成已完成，订单状态改成已消费
					$data = array('order_status' => 5, 'status' => 2);
					
					
					if ($order['is_pick_in_store'] == 0) {//平台配送
						if ($order['paid'] == 0 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {
							$data['paid'] = $order['paid'] == 0 ? 1 : $order['paid'];
							$data['pay_type'] = '';
							$data['balance_pay'] = $order['balance_pay'] + $supply['deliver_cash'];
							$order['balance_pay'] = $order['balance_pay'] + $supply['deliver_cash'];
						}
					} else {
						if ($order['paid'] == 0) {
							$data['paid'] = 1;
							if (empty($order['pay_type']) && empty($order['pay_time'])) $data['pay_type'] = 'offline';
						}
					}
					if (empty($order['pay_time'])) $data['pay_time'] = time();
					if (empty($order['use_time'])) $data['use_time'] = time();
					if (empty($order['third_id'])) $data['third_id'] = $order['order_id'];
					if ($result = D("Shop_order")->where(array('order_id' => $order_id, 'status' => array('neq', 2)))->data($data)->save()) {
						if ($order['is_pick_in_store'] == 0) {//平台配送
							if ($order['paid'] == 0 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {
								D('User_money_list')->add_row($order['uid'], 1, $supply['deliver_cash'], '配送员模拟手动充值');
								D('User_money_list')->add_row($order['uid'], 2, $supply['deliver_cash'], '用户购买快店产品');
							}
						}
						
						D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 4));
						$this->shop_notice($order);
						D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 6, 'name' => $this->deliver_session['name'], 'phone' => $this->deliver_session['phone']));
					} else {
						$this->rollback($supply_id, 4);
						$this->error("更新订单信息错误");
						exit;
					}
					if ($order['is_pick_in_store'] == 5) {
					    if ($order['platform'] == 1) {
					       $returnData = D('Merchant_money_list')->use_money($order['mer_id'], $order['freight_charge'], 'deliver', '平台配送商家由饿了么平台的订单的配送费', $order['order_id']);
					    } elseif ($order['platform'] == 2) {
					       $returnData = D('Merchant_money_list')->use_money($order['mer_id'], $order['freight_charge'], 'deliver', '平台配送商家由美团外卖平台的订单的配送费', $order['order_id']);
					    }
					}
				} else {
					$this->rollback($supply_id, 4);
					$this->error("订单信息错误");
					exit;
				}
			} elseif ($supply['item'] == 3) {
			    D('Service_offer')->offer_save_status($order_id, $this->deliver_session['uid'], $supply['offer_id'], 4);
			}
			$total = D('Deliver_supply')->where(array('uid' => $uid, 'status' => array('gt', 4)))->count();
			D('Deliver_user')->where(array('uid' => $uid))->save(array('num' => $total));
			
			$todayNum = D('Deliver_supply')->where(array('uid' => $uid, 'status' => array('gt', 4), 'end_time' => array(array('gt', strtotime(date('Y-m-d') . ' 00:00:00')), array('lt', time() + 2))))->count();
			$date = date('Ymd');
			if ($deliver_count = D('Deliver_count')->field(true)->where(array('uid' => $uid, 'today' => $date))->find()) {
			    D('Deliver_count')->where(array('uid' => $uid, 'today' => $date))->save(array('num' => $todayNum));
			} else {
			    D('Deliver_count')->add(array('uid' => $uid, 'today' => $date, 'num' => $todayNum));
			}
// 			D('Deliver_user')->where(array('uid' => $this->deliver_session['uid']))->setInc('num');
// 			//统计每日配送订单量
// 			$date = date('Ymd');
// 			if ($deliver_count = D('Deliver_count')->field(true)->where(array('uid' => $this->deliver_session['uid'], 'today' => $date))->find()) {
// 				D('Deliver_count')->where(array('uid' => $this->deliver_session['uid'], 'today' => $date))->setInc('num');
// 			} else {
// 				D('Deliver_count')->add(array('uid' => $this->deliver_session['uid'], 'today' => $date, 'num' => 1));
// 			}
			$this->success("更新状态成功");
			exit;
		}
		$where = array();
		$where['status'] = 4;
		// $where['item'] = 1;
		$where['uid'] = $uid;
		$where['is_hide'] = 0;
		if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
			$where['store_id'] = $this->deliver_session['store_id'];
		}
		$list = $this->deliver_supply->field(true)->where($where)->order("`create_time` DESC")->select();
		if (false === $list) {
			$this->error("系统错误");exit;
		}
		
		foreach ($list as &$val) {
			switch ($val['pay_type']) {
				case 'offline':
					$val['pay_method'] = 0;
					break;
				default:
					if ($val['paid']) {
						$val['pay_method'] = 1;
					} else {
						$val['pay_method'] = 0;
					}
					break;
			}
			$val['deliver_cash'] = floatval($val['deliver_cash']);
			$val['distance'] = floatval($val['distance']);
			$val['freight_charge'] = floatval($val['freight_charge']);
			$val['create_time'] = date('Y-m-d H:i', $val['create_time']);
			$val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
			$val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
			$val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
// 			$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
			$val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
			$val['image'] = $val['image'] ? explode(';', trim($val['image'], ';')) : array();
			if ($val['change_log']) {
				$changes = explode(',', $val['change_log']);
				$uid = array_pop($changes);
				$val['change_name'] = $this->getDeliverUser($uid);
			}
		}
		$this->assign('list', $list);
		$this->display();
	}


	private function shop_notice($order)
	{
		if ($store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find()) {
			//增加商家余额
			$order['order_type']='shop';
			//D('Merchant_money_list')->add_money($store['mer_id'],'用户购买快店订单记入收入',$order);
			$order['desc']='用户购买快店订单记入收入';
			D('SystemBill')->bill_method($order['is_own'],$order);

			//商家推广分佣
	        $now_user = M('User')->where(array('uid' => $order['uid']))->find();
	        D('Merchant_spread')->add_spread_list($order, $now_user, 'shop', $now_user['nickname'] . '用户购买快店商品获得佣金');

			//积分
			if($this->config['add_score_by_percent']==0 && ($this->config['open_score_discount']==0 || $order['score_discount_type']!=2)){
				
				D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay'])* $this->config['score_get']), '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);

				D('Scroll_msg')->add_msg('shop',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '在'.$store['name'] . ' 中消费获得'.$this->config['score_name']);
			}
			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
		
			//短信
			$sms_data = array('mer_id' => $store['mer_id'], 'store_id' => $store['store_id'], 'type' => 'shop');
			if ($this->config['sms_shop_finish_order'] == 1 || $this->config['sms_shop_finish_order'] == 3) {
				if (empty($order['phone'])) {
					$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
					$order['phone'] = $user['phone'];
				}
				$sms_data['uid'] = $order['uid'];
				$sms_data['mobile'] = $order['userphone'];
				$sms_data['sendto'] = 'user';
				$sms_data['content'] = '您在 ' . $store['name'] . '店中下的订单(订单号：' . $order['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
				Sms::sendSms($sms_data);
			}
			if ($this->config['sms_shop_finish_order'] == 2 || $this->config['sms_shop_finish_order'] == 3) {
				$sms_data['uid'] = 0;
				$sms_data['mobile'] = $store['phone'];
				$sms_data['sendto'] = 'merchant';
				$sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['order_id'] . '),已经完成了消费！';
				Sms::sendSms($sms_data);
			}
		
			//小票打印 主打印
			$printHaddle = new PrintHaddle();
			$printHaddle->printit($order['order_id'], 'shop_order', 2);
		}
	}

    private function meal_notice($order)
    {
    	if ($store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find()) {
			//增加商户余额
			$order['order_type']='meal';
			$info = unserialize($order['info']);
			$info_str = '';
			foreach($info as $v){
				$info_str.=$v['name'].':'.$v['price'].'*'.$v['num'].'</br>';
			}
			//D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$info_str.'记入收入',$order);
			$order['desc']='用户购买'.$info_str.'记入收入';
			D('SystemBill')->bill_method($order['is_own'],$order);


			//商家推广分佣
			$now_user = M('User')->where(array('uid' => $order['uid']))->find();
			D('Merchant_spread')->add_spread_list($order, $now_user, 'meal', $now_user['nickname'] . '用户购买餐饮商品获得佣金');

	    	//积分
	    	 if($this->config['add_score_by_percent']==0 && ($this->config['open_score_discount']==0 || $order['score_discount_type']!=2)){
	    	 	

				D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);

				D('Scroll_msg')->add_msg('meal',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '在'.$store['name'] . ' 中消费获得'.$this->config['score_name']);
			}
			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
			
			//短信
			$sms_data = array('mer_id' => $store['mer_id'], 'store_id' => $store['store_id'], 'type' => 'food');
			if ($this->config['sms_finish_order'] == 1 || $this->config['sms_finish_order'] == 3) {
				if (empty($order['phone'])) {
					$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
					$order['phone'] = $user['phone'];
				}
				$sms_data['uid'] = $order['uid'];
				$sms_data['mobile'] = $order['phone'];
				$sms_data['sendto'] = 'user';
				$sms_data['content'] = '您在 ' . $store['name'] . '店中下的订单(订单号：' . $order['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
				Sms::sendSms($sms_data);
			}
			if ($this->config['sms_finish_order'] == 2 || $this->config['sms_finish_order'] == 3) {
				$sms_data['uid'] = 0;
				$sms_data['mobile'] = $store['phone'];
				$sms_data['sendto'] = 'merchant';
				$sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['order_id'] . '),已经完成了消费！';
				Sms::sendSms($sms_data);
			}
			
			//小票打印 主打印
			$printHaddle = new PrintHaddle();
			$printHaddle->printit($order['order_id'], 'meal_order', 2);
    	}
    }
	
	public function detail()
	{
		$supply_id = intval(I("supply_id"));
		$where = array('supply_id' => $supply_id);
		$supply = D("Deliver_supply")->where($where)->find();
		if (empty($supply)) {
			$this->error_tips("配送源不存在");
			exit;
		}

		if ($supply['uid'] && $supply['uid'] != $this->deliver_session['uid']) {
			$this->error_tips("该订单不是您配送，您无权查看");
			exit;
		}

		$supply['deliver_cash'] = floatval($supply['deliver_cash']);
		$supply['distance'] = floatval($supply['distance']);
		$supply['freight_charge'] = floatval($supply['freight_charge']);
		$supply['create_time'] = date('Y-m-d H:i', $supply['create_time']);
		$supply['appoint_time'] = date('Y-m-d H:i', $supply['appoint_time']);
		$supply['order_time'] = $supply['order_time'] ? date('Y-m-d H:i', $supply['order_time']) : '--';
		$supply['end_time'] = $supply['end_time'] ? date('Y-m-d H:i', $supply['end_time']) : '未送达';
		$supply['real_orderid'] = $supply['real_orderid'] ? $supply['real_orderid'] : $supply['order_id'];
// 		$supply['store_distance'] = getRange(getDistance($supply['from_lat'], $supply['from_lnt'], $lat, $lng));
		$supply['map_url'] = U('Deliver/map', array('supply_id' => $supply['supply_id']));
		$supply['image'] = $supply['image'] ? explode(';', trim($supply['image'], ';')) : array();
		if ($supply['change_log']) {
			$changes = explode(',', $supply['change_log']);
			$uid = array_pop($changes);
			$supply['change_name'] = $this->getDeliverUser($uid);
		}
		
		
		$this->assign('supply', $supply);
		
		if ($supply['item'] == 1) {//外送系统的外送
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Waimai_order")->where($where)->find();
			if (empty($order)) {
				$this->error_tips("订单信息有误");
				exit;
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];
			$this->assign('order', $order);
			//商品信息
			$goods = D()->field(true)->table(array(C('DB_PREFIX').'waimai_sell_log'=>'sl', C('DB_PREFIX').'waimai_goods'=>'wg'))->where("sl.order_id=".$supply['order_id']." AND sl.goods_id=wg.goods_id")->select();
			$this->assign('goods', $goods);
			
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'waimai_store'=>'ws'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ws.store_id")->find();
			if (! $store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			$this->assign('store', $store);
			
			//红包信息
			$where = array();
			$where['id'] = $order['coupon_id'];
			$couponInfo = D("Waimai_user_coupon")->field(true)->where($where)->find();
			$this->assign('couponInfo', $couponInfo);
			//优惠信息
			$discountInfo = json_decode($order['discount_detail'], true);
			$this->assign('discountInfo', $discountInfo);
			
		} elseif ($supply['item'] == 0) {//老的餐饮外送
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Meal_order")->where($where)->find();
			if (empty($order)) {
				$this->error_tips("订单信息有误");
				exit;
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];
			$order['discount_price'] = $order['price'];
			$this->assign('order', $order);
			$goods = unserialize($order['info']);
			foreach ($goods as &$g) {
				$g['tools_money'] = 0;
			}
			$this->assign('goods', $goods);
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_meal'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();
			if (!$store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			$this->assign('store', $store);
		} elseif ($supply['item'] == 2) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Shop_order")->where($where)->find();
			
			if (empty($order)) {
				$this->error_tips("订单信息有误");
				exit;
			}
			$order['pay_type'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay'], $order['paid']);
			$order['discount_price'] = $order['price'];
			$order['cue_field'] = $order['cue_field'] ? unserialize($order['cue_field']) : '';
			$this->assign('order', $order);
			$goods = D('Shop_order_detail')->field(true)->where(array('order_id' => $supply['order_id']))->select();

			$tempList = array();
			foreach($goods as $v) {
			    if ($v['spec']) {
			        $v['name'] = $v['name'] . '(' . $v['spec'] . ')';
			    }
			    $v['tools_money'] = 0;
			    
			    $index = isset($v['packname']) && $v['packname'] ? $v['packname'] : 0;
			    if (isset($tempList[$index])) {
			        $tempList[$index]['list'][] = $v;
			    } else {
			        $tempList[$index] = array('name' => $v['packname'], 'list' => array($v));
			    }
			}
			
			$this->assign('goods', $tempList);
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_shop'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();
			if (!$store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			$this->assign('store', $store);
		} elseif ($supply['item'] == 3) {
		    if ($supply['server_type'] == 1) {
		        $order = D('Service_user_publish_give')->field(true)->where(array('publish_id' => $supply['order_id']))->find();
		    } else {
		        $order = D('Service_user_publish_buy')->field(true)->where(array('publish_id' => $supply['order_id']))->find();
		    }
		    $this->assign('order', $order);
		}
		$this->display();
	}
	
	public function detail_bak()
	{
		$uid = $this->deliver_session['uid'];
		$supply_id = intval(I("supply_id"));
		if (! $supply_id) {
			$this->error_tips("参数错误");
		}
		$where = array();
		$where['uid'] = $uid;
		$where['supply_id'] = $supply_id;
		// $where['item'] = 1;
		$supply = D("Deliver_supply")->where($where)->find();
		if (! $supply) {
			$this->error_tips("配送源不存在");
		}
		$this->assign('supply', $supply);
		
		if ($supply['item'] == 1) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Waimai_order")->where($where)->find();
			if (! $order) {
				$this->error_tips("订单信息有误");
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];
			$this->assign('order', $order);
			//商品信息
			$goods = D()->field(true)->table(array(C('DB_PREFIX').'waimai_sell_log'=>'sl', C('DB_PREFIX').'waimai_goods'=>'wg'))->where("sl.order_id=".$supply['order_id']." AND sl.goods_id=wg.goods_id")->select();
			$this->assign('goods', $goods);
			
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'waimai_store'=>'ws'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ws.store_id")->find();
			if (! $store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			$this->assign('store', $store);
			
			//红包信息
			$where = array();
			$where['id'] = $order['coupon_id'];
			$couponInfo = D("Waimai_user_coupon")->field(true)->where($where)->find();
			$this->assign('couponInfo', $couponInfo);
			//优惠信息
			$discountInfo = json_decode($order['discount_detail'], true);
			$this->assign('discountInfo', $discountInfo);
			
		} elseif ($supply['item'] == 0) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Meal_order")->where($where)->find();
			if (!$order) {
				$this->error_tips("订单信息有误");
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];
			$order['discount_price'] = $order['price'];
			$this->assign('order', $order);
			$goods = unserialize($order['info']);
			foreach ($goods as &$g) {
				$g['tools_money'] = 0;
			}
			$this->assign('goods', $goods);
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_meal'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();
			if (!$store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			$this->assign('store', $store);
		} elseif ($supply['item'] == 2) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Shop_order")->where($where)->find();
			if (! $order) {
				$this->error_tips("订单信息有误");
			}
			$order['pay_type'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay'], $order['paid']);
			$order['discount_price'] = $order['price'];
			$order['cue_field'] = $order['cue_field'] ? unserialize($order['cue_field']) : '';
			$this->assign('order', $order);
			$goods = D('Shop_order_detail')->field(true)->where(array('order_id' => $supply['order_id']))->select();
			foreach ($goods as &$g) {
				if ($g['spec']) {
					$g['name'] = $g['name'] . '(' . $g['spec'] . ')';
				}
				$g['tools_money'] = 0;
			}
			$this->assign('goods', $goods);
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_shop'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();
			if (!$store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			$this->assign('store', $store);
		}
		$this->display();
	}
	
	//上传位置
	public  function location() 
	{
		$lng = I("lng");
		if (!$lng) {
			$this->error("获取坐标失败");
		}
		$lat = I("lat");
		if (!$lat) {
			$this->error("获取坐标失败");
		}
		$uid = $this->deliver_session['uid'];
		
		$columns = array();
		$columns['uid'] = $uid;
		$columns['lng'] = $lng;
		$columns['lat'] = $lat;
		$columns['create_time'] = time();
		
		$result = D("Deliver_user_location_log")->add($columns);
		D('Deliver_user')->where(array('uid' => $uid))->save(array('now_lng' => $lng, 'now_lat' => $lat));
		if (!$result) {
			$this->error("位置查找失败");
		}
		$this->success("位置上传成功");
	}
	
	//位置导航
	public function map()
	{
		$supply_id = I("supply_id", 0, 'intval');
		if (! $supply_id) {
			$this->error("SupplyId不能为空");
		}
		$supply = D("Deliver_supply")->where(array('supply_id' => $supply_id))->find();
		if (! $supply) {
			$this->error("配送源不存在");
		}
		$this->assign('supply', $supply);
		$this->display();
	}
	
	public function del()
	{
		$uid = $this->deliver_session['uid'];
		$supply_id = intval(I("supply_id"));
		if ($supply = $this->deliver_supply->field(true)->where(array('uid' => $uid, 'supply_id' => $supply_id, 'status' => 5))->find()) {
			$this->deliver_supply->where(array('uid' => $uid, 'supply_id' => $supply_id, 'status' => 5))->save(array('is_hide' => 1));
			$this->success('ok');
		} else {
			$this->error("配送信息错误");
		}
	}
	
	public function info()
	{
		if ($this->deliver_session['store_id']) {
			$store = D('Merchant_store')->field(true)->where(array('store_id' => $this->deliver_session['store_id']))->find();
			$store_image_class = new store_image();
			$images = $store_image_class->get_allImage_by_path($store['pic_info']);
			$store['image'] = $images ? array_shift($images) : '';
			$this->assign('store', $store);
		}
		$total_list = D('Deliver_supply')->field('count(1) as cnt, sum(distance) as distance')->where(array('uid' => $this->deliver_session['uid'], 'status' => 5))->find();
		$grap_count = D('Deliver_supply')->where(array('uid' => $this->deliver_session['uid'], 'get_type' => 0))->count();
		$where['status'] = 5;
		$this->assign(array('distance' => isset($total_list['distance']) ? floatval($total_list['distance']) : 0, 'finish_total' => isset($total_list['cnt']) ? intval($total_list['cnt']) : 0, 'total' => $grap_count));
		$this->display();
	}
	
	public function tongji()
	{
		$begin_time = isset($_GET['begin_time']) && $_GET['begin_time'] ? $_GET['begin_time'] : '';
		$end_time = isset($_GET['end_time']) && $_GET['end_time'] ? $_GET['end_time'] : '';
		$where = array('uid' => $this->deliver_session['uid'], 'status' => 5);
		if ($begin_time && $end_time) {
			$where['end_time'] = array(array('gt', strtotime($begin_time)), array('lt', strtotime($end_time . '23:59:59')));
		}
		
		$result = D('Deliver_supply')->field('sum(deliver_cash) as offline_money, sum(money-deliver_cash) as online_money, sum(freight_charge) as freight_charge')->where($where)->find();
		
		$count_list = D('Deliver_supply')->field('count(1) as cnt, get_type')->where($where)->group('get_type')->select();
		
		foreach ($count_list as $row) {
			if ($row['get_type'] == 0) {
				$result['self_count'] = $row['cnt'];
			} elseif ($row['get_type'] == 1) {
				$result['system_count'] = $row['cnt'];
			} elseif ($row['get_type'] == 2) {
				$result['change_count'] = $row['cnt'];
			}
		}
		$result['begin_time'] = $begin_time;
		$result['end_time'] = $end_time;
		$this->assign($result);
		$this->display();
	}
	
	public function finish()
	{
		$this->display();
	}
	
	public function ajaxFinish()
	{
		$where = array();
		$page = isset($_GET['page']) && $_GET['page'] ? intval($_GET['page']) : 1;
		$page = max(1, $page);
		$where['status'] = 5;
		$where['is_hide'] = 0;
		$where['uid'] = $this->deliver_session['uid'];
		if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
			$where['store_id'] = $this->deliver_session['store_id'];
		}
		$count = $this->deliver_supply->where($where)->count();
		
		$page_size = 10;
		$start = $page_size * ($page - 1);
		
		$list = $this->deliver_supply->field(true)->where($where)->order("`create_time` DESC")->limit($start . ',' . $page_size)->select();
		foreach ($list as &$val) {
			switch ($val['pay_type']) {
				case 'offline':
					$val['pay_method'] = 0;
					break;
				default:
					if ($val['paid']) {
						$val['pay_method'] = 1;
					} else {
						$val['pay_method'] = 0;
					}
					break;
			}
			$val['deliver_cash'] = floatval($val['deliver_cash']);
			$val['distance'] = floatval($val['distance']);
			$val['freight_charge'] = floatval($val['freight_charge']);
			$val['create_time'] = date('Y-m-d H:i', $val['create_time']);
			$val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
			$val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
			$val['end_time'] = $val['end_time'] ? date('Y-m-d H:i', $val['end_time']) : '未送达';
			$val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
// 			$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
			$val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
			$val['image'] = $val['image'] ? explode(';', trim($val['image'], ';')) : array();
			if ($val['change_log']) {
				$changes = explode(',', $val['change_log']);
				$uid = array_pop($changes);
				$val['change_name'] = $this->getDeliverUser($uid);
			}
		}
		exit(json_encode(array('total' => ceil($count/$page_size), 'list' => $list, 'count' => $count, 'err_code' => false)));
	}
}