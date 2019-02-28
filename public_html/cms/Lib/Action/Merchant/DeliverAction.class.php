<?php

/*
 * 用户中心
 *
 * @  Writers    yanleilei
 * @  BuildTime  2015/8/18 18:25
 * 
 */

class DeliverAction extends BaseAction {
	protected $deliver_user, $deliver_store, $deliver_location, $deliver_supply;
	
	protected function _initialize() {
		parent::_initialize();
		$this->deliver_user = D("Deliver_user");
		$this->deliver_store = D("Deliver_store");
		$this->deliver_location = D("Deliver_location");
		$this->deliver_supply = D("Deliver_supply");
	}
	/**
	 * 配送员列表
	 */
    public function user() {
        $condition_user['mer_id'] = $this->merchant_session['mer_id'];
        $condition_user['group'] = 2;
        if ($this->merchant_session['store_id']) {
            $condition_user['store_id'] = $this->merchant_session['store_id'];
        }
        $count_user = $this->deliver_user->where($condition_user)->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 15);
        $user_list = $this->deliver_user->field(true)->where($condition_user)->order('`uid` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        
        $storeInfoNew = array();
        if(count($user_list)>0){
        	foreach ($user_list as $uinfo){
        		$store_id[$uinfo['store_id']] = $uinfo['store_id'];
        	}
        	$store_ids = join(',', $store_id);
        	$storeInfos = D('merchant_store')->field('name,store_id')->where(array('store_id'=>array('in',$store_ids)))->select();
        	foreach ($storeInfos as $storeOne){
        		$storeInfoNew[$storeOne['store_id']] = $storeOne;
        	}
        }
 
        $this->assign('storeInfoNew',$storeInfoNew);
        $this->assign('user_list', $user_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }
    
    /**
     * 配送员添加
     */
    public function user_add() {
    	$mer_id = $this->merchant_session['mer_id'];
    	if($_POST){
    		$store_id = intval($_POST['store_id']);
    		if ($this->merchant_session['store_id']) {
    		    $store_id = $this->merchant_session['store_id'];
    		}
    		$store = D('Merchant_store')->where(array('mer_id' => $mer_id, 'store_id' => $store_id))->find();
    		if (empty($store))$this->error('店铺不存在');
    		$column['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    		$column['phone'] = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    		$column['pwd'] = isset($_POST['pwd']) ? htmlspecialchars($_POST['pwd']) : '';
    		$column['mer_id'] = $mer_id;
    		$column['store_id'] = $store_id;
    		$column['city_id'] = $store['city_id'];
    		$column['province_id'] = $store['province_id'];
    		$column['circle_id'] = $store['circle_id'];
    		$column['area_id'] = $store['area_id'];
    		$column['site'] = $store['adress'];
    		$column['lng'] = $store['long'];
    		$column['lat'] = $store['lat'];
    		$column['create_time'] = $_SERVER['REQUEST_TIME'];
    		$column['status'] = intval($_POST['status']);
    		$column['last_time'] = $_SERVER['REQUEST_TIME'];
    		$column['group'] = 2;
    		$column['range'] = intval($_POST['range']);
            $_POST['phone_country_type'] && $column['phone_country_type'] = intval($_POST['phone_country_type']);
    		if (empty($column['name'])) {
    			$this->error('姓名不能为空');
    		}
    		if (empty($column['phone'])) {
    			$this->error('联系电话不能为空');
    		}
    		if (empty($column['pwd'])) {
    			$this->error('密码不能为空');
    		}
    		$column['pwd'] = md5($column['pwd']);
    		if (D('Deliver_user')->field(true)->where(array('phone' => $column['phone']))->find()) {
    			$this->error('该手机号已经是配送员账号了，不能重复申请');
    		}
    		$id = D('Deliver_user')->data($column)->add();
    		
    		if(!$id){
    			$this->error('保存失败，请重试');
    		}
    		$this->success('保存成功');
    	}else{
    		// 该商家下的所有外卖店铺
    		$merstore['mer_id'] = $mer_id;
    		$merstore['have_shop'] = 1;
    		if ($this->merchant_session['store_id']) {
    		    $merstore['store_id'] = $this->merchant_session['store_id'];
    		}
    		$waimai_store = D('merchant_store')->where($merstore)->order('sort DESC')->select();
    		$this->assign('waimai_store',$waimai_store);
    	}
    	
    	$this->display();
    }
    
    /**
     * 配送员修改
     */
    public function user_edit() {
    	$mer_id = $this->merchant_session['mer_id'];
    	if($_POST){
    		$uid = intval($_POST['uid']);
    		$store_id = intval($_POST['store_id']);
    		if ($this->merchant_session['store_id']) {
    		    $store_id = $this->merchant_session['store_id'];
    		}
    		$store = D('Merchant_store')->where(array('mer_id' => $mer_id, 'store_id' => $store_id))->find();
    		if (empty($store))$this->error('店铺不存在');
    		$column['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    		$column['phone'] = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    		$column['pwd'] = isset($_POST['pwd']) ? htmlspecialchars($_POST['pwd']) : '';
    		$column['store_id'] = $_POST['store_id'];
    		if($column['pwd']){
    			$column['pwd'] = md5($column['pwd']);
    		} else {
    			unset($column['pwd']);
    		}
    		$column['city_id'] = $store['city_id'];
    		$column['province_id'] = $store['province_id'];
    		$column['circle_id'] = $store['circle_id'];
    		$column['area_id'] = $store['area_id'];
    		$column['site'] = $store['adress'];
    		$column['lng'] = $store['long'];
    		$column['lat'] = $store['lat'];
    		$column['status'] = intval($_POST['status']);
    		$column['range'] = intval($_POST['range']);
    		$column['last_time'] = $_SERVER['REQUEST_TIME'];
            $_POST['phone_country_type'] && $column['phone_country_type'] = intval($_POST['phone_country_type']);
    		if (empty($column['name'])) {
    			$this->error('姓名不能为空');
    		}
    		if (empty($column['phone'])) {
    			$this->error('联系电话不能为空');
    		}
    		$user = D('Deliver_user')->field(true)->where(array('phone' => $column['phone']))->find();
    		if ($user && $user['uid'] != $uid) {
    			$this->error('该手机号已经是配送员账号了，不能重复申请');
    		}
    		
    		if(D('deliver_user')->where(array('uid'=>$uid,'mer_id'=>$mer_id))->data($column)->save()){
    			$this->success('修改成功！');
    		}else{
    			$this->error('修改失败！请检查内容是否有过修改（必须修改）后重试~');
    		}
    	}else{
    		$uid = $_GET['uid'];
    		if(!$uid){
    			$this->error('非法操作');
    		}
    		$deliver = D('deliver_user')->where(array('uid'=>$uid,'mer_id'=>$mer_id))->find();
    		if(!$deliver){
    			$this->error('非法操作');
    		}
    		$this->assign('now_user',$deliver);
    		
    		$merstore['mer_id'] = $mer_id;
    		$merstore['have_shop'] = 1;
    		if ($this->merchant_session['store_id']) {
    		    $merstore['store_id'] = $this->merchant_session['store_id'];
    		}
    		$waimai_store = D('merchant_store')->where($merstore)->order('sort DESC')->select();
    		$this->assign('waimai_store',$waimai_store);
    	}
    	$this->display();
    }
    
    public function user_del(){
    	$uid = $_GET['uid'];
    	if(!$uid){
    		$this->error('非法操作');
    	}
    	$mer_id = $this->merchant_session['mer_id'];
    	$condition_user['mer_id'] = $mer_id;
    	$condition_user['uid'] = $uid;
		if ($this->merchant_session['store_id']) {
		    $condition_user['store_id'] = $this->merchant_session['store_id'];
		}
    	$count_user = $this->deliver_user->where($condition_user)->find();
    	if(!$count_user){
    		$this->error('非法操作');
    	}
    	
    	$result = $this->deliver_user->where($condition_user)->save(array('status'=>0));
    	if(!$result){
    		$this->error('删除失败，请稍后重试');
    	}
    	$this->success('操作成功');
    }

    //配送列表
    public  function deliverList()
    {
        $selectStoreId = I("selectStoreId", 0, "intval");
        $selectUserId = I("selectUserId", 0, "intval");
        $phone = I("phone", 0);
        $orderNum = I("orderNum", 0);

        $mer_id = $this->merchant_session['mer_id'];
        //获取商家店铺ID
        $where = "mer_id={$mer_id} AND status=1 AND (have_meal=1 OR have_shop=1)";
        if ($this->merchant_session['store_id']) {
            $where .= " AND store_id=" . $this->merchant_session['store_id'];
            $selectStoreId = $this->merchant_session['store_id'];
        }
        $stores = D("Merchant_store")->field(true)->where($where)->select();
        $storeIds = array();
        foreach ($stores as $val) {
            $storeIds[] = $val['store_id'];
        }

        if ($selectStoreId && !in_array($selectStoreId, $storeIds)) {
            $this->error("参数出错");
        }
        //获取商家的所有配送员
        $userWhere = array('mer_id' => $mer_id);
        if ($this->merchant_session['store_id']) {
            $userWhere['store_id'] = $this->merchant_session['store_id'];
        }
        $delivers = D("Deliver_user")->field(true)->where($userWhere)->order('status DESC')->select();
        foreach ($delivers as $key => $val) {
            if ($val['status'] == 0) {
                $delivers[$key]['name'] = $val['name']." (已禁用)";
            }
        }

        $sql = "SELECT s.`supply_id`, s.order_id, s.item, s.name as username, s.phone as userphone, m.name as storename, s.money, u.name, u.phone, s.start_time, s.create_time, s.end_time, s.aim_site, s.pay_type, s.paid, s.status, s.deliver_cash FROM " . C('DB_PREFIX') . "merchant_store AS m INNER JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid";
        $sql_count = "SELECT count(1) AS count FROM " . C('DB_PREFIX') . "merchant_store AS m INNER JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid";
        
        $sql .= ' WHERE s.type=1 AND s.mer_id=' . $this->merchant_session['mer_id'];
        $sql_count .= ' WHERE s.type=1 AND s.mer_id=' . $this->merchant_session['mer_id'];
        if ($phone) {
            $sql .= " AND s.phone=".$phone;
            $sql_count .= " AND s.phone=".$phone;
        }


        if ($selectStoreId) {
            $sql .= " AND s.store_id=".$selectStoreId;
            $sql_count .= " AND s.store_id=".$selectStoreId;
        }
        
        if ($selectUserId) {
            $sql .= "  AND s.uid=".$selectUserId;
            $sql_count .= "  AND s.uid=".$selectUserId;
        }
        
        $res_count = D()->query($sql_count);
        $count_order = isset($res_count[0]['count']) ? $res_count[0]['count'] : 0;

        import('@.ORG.merchant_page');
        $p = new Page($count_order, 20);
        $sql .= ' ORDER BY s.`supply_id` DESC LIMIT ' . $p->firstRow . ',' . $p->listRows;
        $supply_info = D()->query($sql);

        foreach ($supply_info as &$value) {
            $value['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
			$value['start_time'] = $value['start_time'] ? date("Y-m-d H:i:s", $value['start_time']) : '-';
			$value['end_time'] = $value['end_time'] ? date("Y-m-d H:i:s", $value['end_time']) : '-';
            $value['paid'] = $value['paid'] == 1 ? "已支付" : "未支付";
            $value['pay_type'] = $value['pay_type'] == "offline" ? "线下支付" : "线上支付";
            //订单状态（0：订单失效，1:订单完成，2：商家未确认，3：商家已确认，4已取餐，5：正在配送，6：退单,7商家取消订单,8配送员已接单）
            //配送状态(0失败 1等待接单 2接单 3取货 4开始配送 5完成）
            switch ($value['status']) {
                case 1:
                    $value['order_status'] = '<font color="red">等待接单</font>';
                    break;
                case 2:
                    $value['order_status'] = "已接单";
                    break;
                case 3:
                    $value['order_status'] = "已取货";
                    break;
                case 4:
                    $value['order_status'] = "开始配送";
                    break;
                case 5:
                    $value['order_status'] = "配送完成";
                    break;
                case 6:
                    $value['order_status'] = "已评价";
                    break;
//                 case 6:
//                     $value['order_status'] = "已退单";
//                     break;
//                 case 7:
//                     $value['order_status'] = "已取消";
//                     break;
//                 case 68:
//                     $value['order_status'] = "已接单";
                default:
                    $value['order_status'] = "订单失效";
                    break;
            }
        }

        $this->assign('selectStoreId', $selectStoreId);
        $this->assign('phone', $phone);
        $this->assign('orderNum', $orderNum);
        $this->assign('selectUserId', $selectUserId);
        $this->assign('stores', $stores);
        $this->assign('delivers', $delivers);
        $this->assign('pagebar', $p->show());
        $this->assign('supply_info', $supply_info);

        $this->display();
    }
    
    public function count_log()
    {
    	$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
    	$condition_user = array('mer_id' => $this->merchant_session['mer_id'], 'uid' => $uid);
        $user = $this->deliver_user->field(true)->where($condition_user)->find();
        if (empty($user)) $this->error('不存在的配送员');
        $deliver_count_obj = D('Deliver_count');
        $count = $deliver_count_obj->field(true)->where(array('uid' => $uid))->order('`id` DESC')->count();
        import('@.ORG.merchant_page');
        $p = new Page($count, 15);
        $count_list = $deliver_count_obj->field(true)->where(array('uid' => $uid))->order('`id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        foreach ($count_list as &$row) {
        	$row['today'] = date('Y-m-d', strtotime($row['today'] . '000000'));
        }
        $this->assign('count_list', $count_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('user', $user);
        $this->display();
    }
    
    public function log_list()
    {
    	$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
		$begin_time = isset($_POST['begin_time']) ? htmlspecialchars($_POST['begin_time']) : '';
		$end_time = isset($_POST['end_time']) ? htmlspecialchars($_POST['end_time']) : '';
		
    	$condition_user = array('mer_id' => $this->merchant_session['mer_id'], 'uid' => $uid);
        $user = $this->deliver_user->field(true)->where($condition_user)->find();
        if (empty($user)) $this->error('不存在的配送员');
        
        
        $sql = "SELECT s.`supply_id`, s.order_id, s.item, s.name as username, s.phone as userphone, m.name as storename, s.money, s.create_time, s.start_time, s.end_time, s.aim_site, s.pay_type, s.paid, s.status, s.deliver_cash FROM " . C('DB_PREFIX') . "merchant_store AS m INNER JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id";
        $sql_count = "SELECT count(1) AS count FROM " . C('DB_PREFIX') . "merchant_store AS m INNER JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id";
        
        $sql .= ' WHERE s.type=1 AND s.mer_id=' . $this->merchant_session['mer_id'] . ' AND s.uid=' . $uid;
        $sql_count .= ' WHERE s.type=1 AND s.mer_id=' . $this->merchant_session['mer_id'] . ' AND s.uid=' . $uid;
        
		if ($begin_time && $end_time) {
			$sql .= ' AND s.start_time>' . strtotime($begin_time) . ' AND s.start_time<' . strtotime($end_time);
			$sql_count .= ' AND s.start_time>' . strtotime($begin_time) . ' AND s.start_time<' . strtotime($end_time);
		}
		
        
        
        $res_count = D()->query($sql_count);
        $count_order = isset($res_count[0]['count']) ? $res_count[0]['count'] : 0;

        
        import('@.ORG.merchant_page');
        $p = new Page($count_order, 20);
        $sql .= ' ORDER BY s.`supply_id` DESC LIMIT ' . $p->firstRow . ',' . $p->listRows;
        $supply_info = D()->query($sql);

        foreach ($supply_info as &$value) {
            $value['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
			$value['start_time'] = $value['start_time'] ? date("Y-m-d H:i:s", $value['start_time']) : '-';
			$value['end_time'] = $value['end_time'] ? date("Y-m-d H:i:s", $value['end_time']) : '-';
            $value['paid'] = $value['paid'] == 1 ? "已支付" : "未支付";
            $value['pay_type'] = $value['pay_type'] == "offline" ? "线下支付" : "线上支付";
            //订单状态（0：订单失效，1:订单完成，2：商家未确认，3：商家已确认，4已取餐，5：正在配送，6：退单,7商家取消订单,8配送员已接单）
            //配送状态(0失败 1等待接单 2接单 3取货 4开始配送 5完成）
            switch ($value['status']) {
                case 1:
                    $value['order_status'] = '<font color="red">等待接单</font>';
                    break;
                case 2:
                    $value['order_status'] = "已接单";
                    break;
                case 3:
                    $value['order_status'] = "已取货";
                    break;
                case 4:
                    $value['order_status'] = "开始配送";
                    break;
                case 5:
                    $value['order_status'] = "配送完成";
                    break;
                default:
                    $value['order_status'] = "订单失效";
                    break;
            }
        }

        $this->assign('supply_info', $supply_info);
        $this->assign('pagebar', $p->show());
        $this->assign('user', $user);
		$this->assign(array('begin_time' => $begin_time, 'end_time' => $end_time));
        $this->display();
    }
}