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
	
	public function config(){
		redirect(U('Config/index',array('galias'=>'deliver','header'=>'Deliver/header')));
	}
	
	/**
	 * 配送员列表
	 */
	public function user() {
	    //搜索
	    if (!empty($_GET['keyword'])) {
	        if ($_GET['searchtype'] == 'uid') {
	            $condition_user['uid'] = $_GET['keyword'];
	        } else if ($_GET['searchtype'] == 'nickname') {
	            $condition_user['name'] = array('like', '%' . $_GET['keyword'] . '%');
	        } else if ($_GET['searchtype'] == 'phone') {
	            $condition_user['phone'] = array('like', '%' . $_GET['keyword'] . '%');
	        }
	    }
	    $condition_user['group'] = 1;

		$menus = $this->system_session['menus'];

		$this->assign('menus',$menus);

	    if ($this->system_session['area_id']) {
			$area_index = D('Area')->getIndexByAreaID($this->system_session['area_id']);
			$condition_user[$area_index] = $this->system_session['area_id'];
		}
	    
	    $count_user = $this->deliver_user->where($condition_user)->count();
	    import('@.ORG.system_page');
	    $p = new Page($count_user, 20);
	    $user_list = $this->deliver_user->field(true)->where($condition_user)->order('`status` DESC, `sort` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

	    $m = date('Ym'.'01');
	    $m_start = strtotime($m);
        $next = strtotime("next month");
        $m_end = strtotime(date('Ym'.'01',$next))-1;
        $where['end_time'] = array('between',array($m_start,$m_end));
        $where['status'] = array('gt',4);
	    foreach($user_list as &$user){
	        $where['uid'] = $user['uid'];
	        $month_count = M('Deliver_supply')->where($where)->count();
            $user['month_count'] = $month_count;
        }
	    $this->assign('user_list', $user_list);
	    $pagebar = $p->show();
	    $this->assign('pagebar', $pagebar);
	    $this->display();
	}

    /**
     * 删除配送员
     */
    public function del()
    {
        if (IS_POST) {
            $condition_user['group'] = 1;
            $condition_user['uid'] = intval($_POST['uid']);
            if ($this->system_session['area_id']) {
                $area_index = D('Area')->getIndexByAreaID($this->system_session['area_id']);
                $condition_user[$area_index] = $this->system_session['area_id'];
            }
            
            if ($this->deliver_user->where($condition_user)->delete()) {
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }
    
    /**
     * 配送员添加
     */
    public function user_add() {
    	if($_POST){
    		$column['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    		$column['phone'] = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
    		$column['pwd'] = isset($_POST['pwd']) ? htmlspecialchars($_POST['pwd']) : '';
    		$column['store_id'] = 0;
    		$column['city_id'] = $_POST['city_id'];
    		$column['province_id'] = $_POST['province_id'];
    		$column['circle_id'] = $_POST['circle_id'];
    		$column['area_id'] = $_POST['area_id'];
    		$column['site'] = $_POST['adress'];
    		$long_lat = explode(',',$_POST['long_lat']);
    		$column['lng'] = $long_lat[0];
    		$column['lat'] = $long_lat[1];
    		$column['create_time'] = $_SERVER['REQUEST_TIME'];
    		$column['status'] = intval($_POST['status']);
    		$column['last_time'] = $_SERVER['REQUEST_TIME'];
    		$column['group'] = 1;
    		$column['range'] = intval($_POST['range']);
    		$column['sort'] = intval($_POST['sort']);
    		$column['max_num'] = intval($_POST['max_num']);
    		$column['is_cancel_order'] = intval($_POST['is_cancel_order']);
    		$column['delivery_range_type'] = intval($_POST['delivery_range_type']);
    		if (empty($column['name'])) {
    			$this->error('姓名不能为空');
    		}
    		if (empty($column['phone'])) {
    			$this->error('联系电话不能为空');
    		}
    		if ($column['delivery_range_type'] == 1) {
    		    if ($_POST['delivery_range_polygon']) {
    		        $latLngArray = explode('|', $_POST['delivery_range_polygon']);
    		        if (count($latLngArray) < 3) {
    		            $this->error('请绘制一个合理的服务范围！');
    		        } else {
    		            $latLngData = array();
    		            foreach ($latLngArray as $row) {
    		                $latLng = explode('-', $row);
    		                $latLngData[] = $latLng[1] . ' ' . $latLng[0];
    		            }
    		            $latLngData[] = $latLngData[0];
    		            $column['delivery_range_polygon'] = 'POLYGON((' . implode(',', $latLngData) . '))';
    		        }
    		    } else {
    		        $this->error('请绘制您的服务范围！');
    		    }
    		}
    		if (empty($column['pwd'])) {
    			$this->error('密码不能为空');
    		}
    		if ($column['max_num'] < 0) {
    		    $this->error('配送员手中待配送完成的订单数小于多少单后才可继续抢单的值应该填写大于等于0的整数');
    		}
    		$column['pwd'] = md5($column['pwd']);
    		if ($this->deliver_user->field(true)->where(array('phone' => $column['phone']))->find()) {
    			$this->error('该手机号已经是配送员账号了，不能重复申请');
    		}
    		
    		$id = $this->deliver_user->data($column)->add();
    		if(!$id){
    			$this->error('保存失败，请重试');
    		}
    		$this->success('保存成功');
    	}
    	
    	$this->display();
    }
    
    /**
     * 配送员修改
     */
    public function user_edit() {
    	if($_POST){
    		$uid = intval($_POST['uid']);
    		$column['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    		$column['phone'] = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
    		$column['pwd'] = isset($_POST['pwd']) ? htmlspecialchars($_POST['pwd']) : '';
    		if($column['pwd']){
    			$column['pwd'] = md5($column['pwd']);
    		} else {
    			unset($column['pwd']);
    		}
    		$column['city_id'] = $_POST['city_id'];
    		$column['province_id'] = $_POST['province_id'];
    		$column['circle_id'] = $_POST['circle_id'];
    		$column['area_id'] = $_POST['area_id'];
    		$column['site'] = $_POST['adress'];
    		$long_lat = explode(',',$_POST['long_lat']);
    		$column['lng'] = $long_lat[0];
    		$column['lat'] = $long_lat[1];
    		$column['status'] = intval($_POST['status']);
    		$column['last_time'] = $_SERVER['REQUEST_TIME'];
    		$column['range'] = intval($_POST['range']);
    		$column['sort'] = intval($_POST['sort']);
    		$column['max_num'] = intval($_POST['max_num']);
    		$column['is_cancel_order'] = intval($_POST['is_cancel_order']);
    		$column['delivery_range_type'] = intval($_POST['delivery_range_type']);
    		$column['phone_country_type'] = intval($_POST['phone_country_type']);
    		if (empty($column['name'])) {
    			$this->error('姓名不能为空');
    		}
    		if (empty($column['phone'])) {
    			$this->error('联系电话不能为空');
    		}
            if ($column['delivery_range_type'] == 1) {
                if ($_POST['delivery_range_polygon']) {
                    $latLngArray = explode('|', $_POST['delivery_range_polygon']);
                    if (count($latLngArray) < 3) {
                        $this->error('请绘制一个合理的服务范围！');
                    } else {
                        $latLngData = array();
                        foreach ($latLngArray as $row) {
                            $latLng = explode('-', $row);
                            $latLngData[] = $latLng[1] . ' ' . $latLng[0];
                        }
                        $latLngData[] = $latLngData[0];
                        $column['delivery_range_polygon'] = 'POLYGON((' . implode(',', $latLngData) . '))';
                    }
                } else {
                    $this->error('请绘制您的服务范围！');
                }
            }
    		if ($column['max_num'] < 0) {
    		    $this->error('配送员手中待配送完成的订单数小于多少单后才可继续抢单的值应该填写大于等于0的整数');
    		}
    		$user = $this->deliver_user->field(true)->where(array('phone' => $column['phone']))->find();
    		if ($user && $user['uid'] != $uid) {
    			$this->error('该手机号已经是配送员账号了，不能重复申请');
    		}
    		
    		if($this->deliver_user->where(array('uid'=>$uid))->data($column)->save()){
    			$this->success('修改成功！');
    		}else{
    			$this->error('修改失败！请检查内容是否有过修改（必须修改）后重试~');
    		}
    	}else{
    		$uid = $_GET['uid'];
    		if(!$uid){
    			$this->error('非法操作');
    		}
    		$deliver = $this->deliver_user->where(array('uid'=>$uid))->find();
    		
    		if ($deliver['delivery_range_polygon']) {
    		    $deliver['delivery_range_polygon'] = substr($deliver['delivery_range_polygon'], 9, strlen($deliver['delivery_range_polygon']) - 11);
    		    $lngLatData = explode(',', $deliver['delivery_range_polygon']);
                array_pop($lngLatData);
                $lngLats = array();
                foreach ($lngLatData as $lnglat) {
                    $lng_lat = explode(' ', $lnglat);
                    $lngLats[] = $lng_lat[1] . '-' . $lng_lat[0];
                }
                $deliver['delivery_range_polygon'] = implode('|', $lngLats);
            }
    		if(!$deliver){
    			$this->error('非法操作');
    		}
    		$this->assign('now_user',$deliver);
    	}
    	$this->display();
    }

	//配送列表
	public function deliver_List()
	{
		$selectStoreId = I("selectStoreId", 0, 'intval');
		$selectUserId = I("selectUserId", 0, 'intval');
		$phone = I("phone", 0);
		$orderNum = I("orderNum", 0);
		


		
		

		//获取商家的所有配送员
		$delivers = D("Deliver_user")->field(true)->where(array('mer_id'=>$mer_id))->order('status DESC')->select();
		foreach ($delivers as $key => $val) {
			if ($val['status'] == 0) {
				$delivers[$key]['name'] = $val['name'] . " (已禁用)";
			}
		}
		$db_arr = array(C('DB_PREFIX').'deliver_supply'=>'s',C('DB_PREFIX').'deliver_user'=>'u',C('DB_PREFIX').'merchant_store'=>'m');//,C('DB_PREFIX').'waimai_order'=>'o'
		$fields = "s.order_id, s.item, s.name as username, s.phone as userphone, m.name as storename, s.money, u.name, u.phone, s.start_time, s.end_time, s.aim_site, s.pay_type, s.paid, s.status, u.group";
		$where = 'm.store_id=s.store_id AND s.uid=u.uid';
		if ($phone) {
			$where .= " AND s.phone=".$phone;
		}
        if ($selectStoreId) {
            $where .= " AND s.store_id=".$selectStoreId;
        }
        if ($selectUserId) {
            $where .= "  AND s.uid=".$selectUserId;
        }
        
        import('@.ORG.system_page');
        $count_order = D()->table($db_arr)->where($where)->count();
        $p = new Page($count_order, 20);
        $supply_info = D()->table($db_arr)->field($fields)->where($where)->order('s.`supply_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
        foreach ($supply_info as $key => $value) {
            $supply_info[$key]['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
            if ($value['start_time']) {
                $supply_info[$key]['start_time'] = date("Y-m-d H:i:s", $value['start_time']);
            } else {
                $supply_info[$key]['start_time'] = '-';
            }
            if ($value['end_time']) {
                $supply_info[$key]['end_time'] = date("Y-m-d H:i:s", $value['end_time']);
            } else {
                $supply_info[$key]['end_time'] = '-';
            }
            $supply_info[$key]['paid'] = $value['paid'] == 1? "已支付": "未支付";
            $supply_info[$key]['group'] = $value['group'] == 1? $this->config['deliver_name'] . "员": "店铺配送员";
            $supply_info[$key]['pay_type'] = $value['pay_type'] == "offline"? "线下支付": "线上支付";
            //订单状态（0：订单失效，1:订单完成，2：商家未确认，3：商家已确认，4已取餐，5：正在配送，6：退单,7商家取消订单,8配送员已接单）
            //配送状态(0失败 1等待接单 2接单 3取货 4开始配送 5完成）
            switch ($value['status']) {
                case 1:
                    $supply_info[$key]['order_status'] = "等待接单";
                    break;
                case 2:
                    $supply_info[$key]['order_status'] = "已接单";
                    break;
                case 3:
                    $supply_info[$key]['order_status'] = "已取货";
                    break;
                case 4:
                    $supply_info[$key]['order_status'] = "开始配送";
                    break;
                case 5:
                    $supply_info[$key]['order_status'] = "已完成";
                    break;
                case 6:
                    $supply_info[$key]['order_status'] = "已评价";
                    break;
                default:
                    $supply_info[$key]['order_status'] = "订单失效";
                    break;
            }
        }
        $pagebar = $p->show();
        $this->assign('selectStoreId', $selectStoreId);
        $this->assign('phone', $phone);
        $this->assign('orderNum', $orderNum);
        $this->assign('selectUserId', $selectUserId);
        $this->assign('stores', $stores);
        $this->assign('delivers', $delivers);
        $this->assign('pagebar', $pagebar);
        $this->assign('supply_info', $supply_info);

        $this->display();
	}

    public function deliverList()
    {
        $selectStoreId = I("selectStoreId", 0, "intval");
        $selectUserId = I("selectUserId", 0, "intval");
        $phone = I("phone", 0);
        $orderNum = I("orderNum", 0);
        
        $status = I('status', -1, 'intval');
        $order_from = isset($_GET['order_from']) ? intval($_GET['order_from']) : - 1;
        $day = I('day', 0, 'intval');
        $period = I('period', '', 'htmlspecialchars');
        
        $timetype = I('timetype', 0, 'intval');
        $time = I('time', 0, 'intval');
        $stime = $etime = 0;
        if ($day) {
            if ($day == 1) {
                $stime = strtotime(date('Y-m-d'));
                $etime = time();
            } else {
                $stime = strtotime("-{$day} day");
                $etime = time();
            }
        }
        if ($period) {
            $time_array = explode('-', $period);
            $stime = strtotime($time_array[0]);
            $etime = strtotime($time_array[1]) + 86400;
        }

        $sql = "SELECT s.`supply_id`,case  when s.`server_type` <> 0 then sp.`order_sn` else so.real_orderid end as order_sn, s.order_id, s.order_time, s.create_time, s.order_from, s.item, s.name as username, s.phone as userphone, m.name as storename, s.money, u.name, u.phone, s.start_time, s.end_time, s.aim_site, s.pay_type, s.paid, s.status, s.deliver_cash, s.distance, s.from_lat, s.aim_lat, s.from_lnt, s.aim_lnt, s.get_type, s.server_type FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid LEFT JOIN " . C('DB_PREFIX'). "service_user_publish AS sp ON (sp.publish_id=s.order_id AND s.`server_type` <> 0) LEFT JOIN " . C('DB_PREFIX'). "user AS us ON (sp.uid=us.uid AND s.`server_type` <> 0) LEFT JOIN " . C('DB_PREFIX'). "shop_order AS so ON (so.order_id=s.order_id AND s.`server_type` = 0)";
        $sql_count = "SELECT count(1) AS count,sum(s.freight_charge) AS total_freight,sum(s.money) AS total_money,sum(s.tip_price) AS tip_price ,case  when s.`server_type` <> 0 then sp.`order_sn` else so.real_orderid end as order_sn FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid  LEFT JOIN " . C('DB_PREFIX'). "service_user_publish AS sp ON (sp.publish_id=s.order_id AND s.`server_type` <> 0) LEFT JOIN " . C('DB_PREFIX'). "user AS us ON (sp.uid=us.uid AND s.`server_type` <> 0) LEFT JOIN " . C('DB_PREFIX'). "shop_order AS so ON (so.order_id=s.order_id AND s.`server_type` = 0)";

        $sql .= ' WHERE s.type=0';
        $sql_count .= ' WHERE s.type=0';
        if ($phone) {
            $sql .= " AND (s.phone=" . $phone . ' OR us.phone=' . $phone . ')';
            $sql_count .= " AND (s.phone=" . $phone . ' OR us.phone=' . $phone . ')';
        }

        if ($time > 0) {
            $sqltime = $time * 60;
            if ($timetype == 0) {
                $sql .= " AND s.create_time>s.order_time AND s.create_time-s.order_time>" . $sqltime;
                $sql_count .= " AND s.create_time>s.order_time AND s.create_time-s.order_time>" . $sqltime;
            } elseif ($timetype == 1) {
                $sql .= " AND s.start_time>s.create_time AND s.start_time-s.create_time>" . $sqltime;
                $sql_count .= " AND s.start_time>s.create_time AND s.start_time-s.create_time>" . $sqltime;
            } elseif ($timetype == 2) {
                $sql .= " AND s.end_time>s.start_time AND s.end_time-s.start_time>" . $sqltime;
                $sql_count .= " AND s.end_time>s.start_time AND s.end_time-s.start_time>" . $sqltime;
            }
        }
        if ($stime && $etime) {
            $sql .= " AND s.create_time>'{$stime}' AND s.create_time<'{$etime}'";
            $sql_count .= " AND s.create_time>'{$stime}' AND s.create_time<'{$etime}'";
        }
        if ($status != -1) {
            $sql .= " AND s.status=" . $status;
            $sql_count .= " AND s.status=" . $status;
        }
        
        if ($order_from > - 1) {
//            if ($order_from == 0) {
//                $sql .= " AND s.order_from<3";
//                $sql_count .= " AND s.order_from<3";
//            } elseif ($order_from == 4) {
//                $sql .= " AND (s.order_from=4 OR s.order_from=5)";
//                $sql_count .= " AND (s.order_from=4 OR s.order_from=5)";
//            } else {
                $sql .= " AND s.order_from=" . $order_from;
                $sql_count .= " AND s.order_from=" . $order_from;
//            }
        }
        
        if ($selectStoreId) {
            $sql .= " AND s.store_id=" . $selectStoreId;
            $sql_count .= " AND s.store_id=" . $selectStoreId;
        }
        
        if ($selectUserId) {
            $sql .= " AND s.uid=" . $selectUserId;
            $sql_count .= " AND s.uid=" . $selectUserId;
        }
        if ($this->system_session['area_id']) {
            $area_index = D('Area')->getIndexByAreaID($this->system_session['area_id']);
            $sql .= ' AND ((s.' . $area_index . '>0 AND s.' . $area_index . '=' . $this->system_session['area_id'] . ') OR m.' . $area_index . '=' . $this->system_session['area_id'] . ')';
            $sql_count .= ' AND ((s.' . $area_index . '>0 AND s.' . $area_index . '=' . $this->system_session['area_id'] . ') OR m.' . $area_index . '=' . $this->system_session['area_id'] . ')';
        }
        
        import('@.ORG.system_page');
        $res_count = D()->query($sql_count);
        $count_order = isset($res_count[0]['count']) ? $res_count[0]['count'] : 0;
        
        $p = new Page($count_order, 20);
        $sql .= ' ORDER BY s.`supply_id` DESC LIMIT ' . $p->firstRow . ',' . $p->listRows;
        $supply_info = D()->query($sql);
        foreach ($supply_info as &$value) {
//            $config_status = M('Shop_order_log')->where(array('order_id'=>$value['order_id'],'status'=>2))->find();
//            if($config_status['dateline']){
//                $value['create_time']=$config_status['dateline'];
//            }
            $value['confirm_time'] = $value['create_time'] && $value['order_time'] ? intval(($value['create_time'] - $value['order_time']) / 60) . '分钟' : '-';
            $value['grab_time'] = $value['start_time'] ? intval(($value['start_time'] - $value['create_time']) / 60) . '分钟' : '-';
            $value['deliver_use_time'] = $value['end_time'] ? intval(($value['end_time'] - $value['start_time']) / 60) . '分钟' : '-';
            $value['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
            $value['start_time'] = $value['start_time'] ? date("Y-m-d H:i:s", $value['start_time']) : '-';
            $value['end_time'] = $value['end_time'] ? date("Y-m-d H:i:s", $value['end_time']) : '-';
            
            $value['paid'] = $value['paid'] == 1 ? "已支付" : "未支付";
            $value['pay_type'] = $value['pay_type'] == "offline" ? "线下支付" : "线上支付";
            $value['distance'] = $value['distance'] ? $value['distance'] . 'km' : getRange(getDistance($value['from_lat'], $value['from_lnt'], $value['aim_lat'], $value['aim_lnt']));
            // 订单状态（0：订单失效，1:订单完成，2：商家未确认，3：商家已确认，4已取餐，5：正在配送，6：退单,7商家取消订单,8配送员已接单）
            // 配送状态(0失败 1等待接单 2接单 3取货 4开始配送 5完成）
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
                    $value['order_status'] = "已完成";
                    break;
                case 6:
                    $value['order_status'] = "已评价";
                    break;
                case 7:
                    $value['order_status'] = "退款失败";
                    break;
                default:
                    $value['order_status'] = "订单失效";
                    break;
            }
        }
        
        $pagebar = $p->show();
        $this->assign(array(
            'status' => $status,
            'order_from' => $order_from,
            'day' => $day,
            'period' => $period,
            'phone' => $phone,
            'time' => $time,
            'timetype' => $timetype
        ));
        $this->assign('selectStoreId', $selectStoreId);
        $this->assign('orderNum', $orderNum);
        $this->assign('selectUserId', $selectUserId);
        $this->assign('stores', $stores);
        $this->assign('delivers', $delivers);
        $this->assign('pagebar', $pagebar);
        $this->assign('supply_info', $supply_info);
        $this->assign('res_count', $res_count);
        $this->display();
    }
    
    // 配送列表 操作
    public function appoint_deliver()
    {
        $supply_id = isset($_REQUEST['supply_id']) ? intval($_REQUEST['supply_id']) : 0;
    	$supply = D('Deliver_supply')->field(true)->where(array('supply_id' => $supply_id, 'type' => 0))->find();
    	if (empty($supply)) $this->frame_error_tips('不存在的数据');
    	if (IS_POST) {
    		if ($supply['status'] > 4) $this->error('配送已完成，不能重新指派了');
    		$uid = isset($_POST['uid']) ? intval($_POST['uid']) : 0;
			
			if($uid == $supply['uid']) $this->error('该订单已经属于该配送员');
			
    		$user = D('Deliver_user')->field(true)->where(array('uid' => $uid, 'group' => 1, 'status' => 1))->find();
    		if (empty($user)) $this->error('配送员不存在');
    		$status = $supply['status'] == 1 ? 2 : $supply['status'];
    		$save_data = array('uid' => $uid, 'status' => $status);
			if ($status == 2) {
				$save_data['start_time'] = time();
			}
			$from_type = 1;//0:默认用户操作，1：系统指派，2：系统更换，3：计划任务指定
    		if ($supply['uid']) {
    		    $from_type = 2;
    			$save_data['get_type'] = 2;
    			$save_data['change_log'] = $supply['change_log'] ? $supply['change_log'] . ',' . $supply['uid'] : $supply['uid'];

    		} else {
    			$save_data['get_type'] = 1;
            }
            $save_data['deliver_check']=0;
            $save_data['check_time'] = time()+$this->config['deliver_look_out_time']*60;

            $result = D('Deliver_supply')->where(array('supply_id' => $supply_id))->save($save_data);
    		if ($status == 2) {
    			if ($supply['item'] == 0) {
    				$result = D("Meal_order")->where(array('order_id' => $supply['order_id']))->data(array('order_status' => 8))->save();
    			} elseif ($supply['item'] == 2) {
    				$deliver_info = serialize(array('uid' => $user['uid'], 'name' => $user['name'], 'phone' => $user['phone'], 'store_id' => $user['store_id']));
    				$result = D("Shop_order")->where(array('order_id' => $supply['order_id']))->data(array('order_status' => 2, 'deliver_info' => $deliver_info))->save();
    				D('Shop_order_log')->add_log(array('order_id' => $supply['order_id'], 'from_type' => $from_type, 'status' => 3, 'name' => $user['name'], 'phone' => $user['phone']));
    			} elseif ($supply['item'] == 3) {
    			    $offer_id = D('Service_offer')->add_offer($supply['order_id'], $user['uid']);
    			    $data = array('offer_id' => $offer_id);
    			    if ($supply['server_type'] != 1) {
    			        $data['appoint_time'] = time() + $supply['server_time'] * 60;
    			    }
    			    D('Deliver_supply')->where(array('supply_id' => $supply_id))->save($data);
    			}
    		} else {
    		    D('Shop_order_log')->add_log(array('order_id' => $supply['order_id'], 'from_type' => $from_type, 'status' => $status + 1, 'name' => $user['name'], 'phone' => $user['phone']));
    		}
    		D('Deliver_supply')->sendNotice($user, $supply);

    		$this->success('指派成功');
    	} else {
            if ($supply['item'] != 3) {
                $store = D('Merchant_store')->field(true)->where(array('store_id' => $supply['store_id']))->find();
                if (empty($store)) $this->frame_error_tips('店铺不存在');
            }
			$users = D('Deliver_user')->hasUser($supply['from_lat'], $supply['from_lnt']);
			if (empty($users)) $this->frame_error_tips('当前暂无工作中或正常范围内的配送员指派');
			
			$uids = '';
			$pre = '';
			$data = array();
			foreach ($users as $user) {
				$user['range'] = getRange(getDistance($supply['from_lat'], $supply['from_lnt'], $user['lat'], $user['lng']));
				$user['now_range'] = getRange(getDistance($supply['from_lat'], $supply['from_lnt'], $user['lat'], $user['lng']));
				$data[$user['uid']] = $user;
				$uids .= $pre . $user['uid'];
				$pre = ',';
			}
			$sql = "SELECT a.pigcms_id, a.uid, a.lat, a.lng FROM " . C('DB_PREFIX') . "deliver_user_location_log AS a INNER JOIN (SELECT uid, MAX(pigcms_id) AS pigcms_id FROM " . C('DB_PREFIX') . "deliver_user_location_log GROUP BY uid) AS b ON a.uid = b.uid AND a.pigcms_id = b.pigcms_id WHERE a.uid IN ({$uids})";
			$now_users = D()->query($sql);
			foreach ($now_users as $v) {
				if (isset($data[$v['uid']])) {
					$data[$v['uid']]['now_range'] = getRange(getDistance($supply['from_lat'], $supply['from_lnt'], $v['lat'], $v['lng']));
				}
			}
			$this->assign('supply', $supply);
			$this->assign('users', $data);
			$this->display();
    	}
    }
    public function count_log()
    {
    	$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
    	$condition_user = array('mer_id' => 0, 'uid' => $uid);
        $user = $this->deliver_user->field(true)->where($condition_user)->find();
        if (empty($user)) $this->error('不存在的配送员');
        $deliver_count_obj = D('Deliver_count');
        $count = $deliver_count_obj->field(true)->where(array('uid' => $uid))->count();
        import('@.ORG.system_page');
        $p = new Page($count, 15);
        $count_list = $deliver_count_obj->field(true)->where(array('uid' => $uid))->order('`today` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
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
    	$order_from = isset($_GET['order_from']) ? intval($_GET['order_from']) : -1;
		$begin_time = isset($_GET['begin_time']) ? htmlspecialchars($_GET['begin_time']) : '';
		$end_time = isset($_GET['end_time']) ? htmlspecialchars($_GET['end_time']) : '';
    	$condition_user = array('mer_id' => 0, 'uid' => $uid);
        $user = $this->deliver_user->field(true)->where($condition_user)->find();
        if (empty($user)) $this->error('不存在的配送员');
        $sql = "SELECT s.`supply_id`, s.order_id, s.item, s.name as username, s.phone as userphone,s.freight_charge, m.name as storename, s.money, s.start_time, s.end_time, s.aim_site, s.pay_type, s.paid, s.status, s.deliver_cash, s.tip_price, s.server_type FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id";
        $sql_count = "SELECT count(1) AS count,sum(s.freight_charge) AS total_freight,sum(s.distance) as total_distance,sum(s.money) AS total_money,sum(s.tip_price) AS tip_price FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id";
        
        $sql .= ' WHERE s.type=0 AND s.status>0 AND s.uid=' . $uid;
        $sql_count .= ' WHERE s.type=0 AND s.status>0 AND s.uid=' . $uid;
        
		if ($begin_time && $end_time) {
			$sql .= ' AND s.end_time>' . strtotime($begin_time) . ' AND s.end_time<' . strtotime($end_time);
			$sql_count .= ' AND s.end_time>' . strtotime($begin_time) . ' AND s.end_time<' . strtotime($end_time);
		}
        if ($order_from > - 1) {
            if ($order_from == 0) {
                $sql .= " AND s.order_from<3";
                $sql_count .= " AND s.order_from<3";
            } elseif ($order_from == 4) {
                $sql .= " AND (s.order_from=4 OR s.order_from=5)";
                $sql_count .= " AND (s.order_from=4 OR s.order_from=5)";
            } else {
                $sql .= " AND s.order_from=" . $order_from;
                $sql_count .= " AND s.order_from=" . $order_from;
            }
        }
        import('@.ORG.system_page');
        
        $res_count = D()->query($sql_count);
        $count_order = isset($res_count[0]['count']) ? $res_count[0]['count'] : 0;

        
        $p = new Page($count_order, 20);
        $sql .= ' ORDER BY s.`supply_id` DESC LIMIT ' . $p->firstRow . ',' . $p->listRows;
        $supply_info = D()->query($sql);

        foreach ($supply_info as &$value) {
            $value['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
			$value['deliver_use_time'] = $value['end_time'] ? intval(($value['end_time']-$value['start_time'])/60).'分钟' : '-';
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
                    $value['order_status'] = "接单";
                    break;
                case 3:
                    $value['order_status'] = "取货";
                    break;
                case 4:
                    $value['order_status'] = "开始配送";
                    break;
                case 5:
                    $value['order_status'] = "完成";
                    break;
                case 6:
                    $value['order_status'] = "已评价";
                    break;
                default:
                    $value['order_status'] = "订单失效";
                    break;
            }
        }

        $this->assign('supply_info', $supply_info);
        $this->assign('pagebar', $p->show());
        $this->assign('user', $user);
        $this->assign('res_count', $res_count);
        $this->assign(array('begin_time' => $begin_time, 'end_time' => $end_time, 'order_from' => $order_from));
        $this->display();
    }
    
    
    public function change()
    {
    	$supply_id = isset($_GET['supply_id']) ? intval($_GET['supply_id']) : 0;
    	$supply = D('Deliver_supply')->field(true)->where(array('supply_id' => $supply_id, 'type' => 0))->find();
    	if (empty($supply)) exit(json_encode(array('error_code' => true, 'msg' => '不存在的数据')));
    	if ($supply['status'] == 5 || $supply['status'] == 0) exit(json_encode(array('error_code' => false, 'msg' => '修改成功')));
    	if ($supply['status'] == 1) exit(json_encode(array('error_code' => true, 'msg' => '配送员还未接单，不能修改成已完成')));
    	
    	$columns = array();
    	$columns['status'] = 5;
    	$columns['end_time'] = time();
    	
    	$database_deliver_user = D('Deliver_user');
    	$date = 0;
    	if ($now_deliver_user = $database_deliver_user->field(true)->where(array('uid' => $supply['uid']))->find()) {
    		$today = date('Ymd');
    		$num = 0;
    		if ($now_deliver_user['today'] != $today) {
    			$date = $now_deliver_user['today'];
    			$num = $now_deliver_user['today_num'];
    			$deliver_user_data['today'] = $today;
    			$deliver_user_data['today_num'] = 1;
    			$deliver_user_data['num'] = $now_deliver_user['num'] + 1;
    		} else {
    			$deliver_user_data['today_num'] = $now_deliver_user['today_num'] + 1;
    			$deliver_user_data['num'] = $now_deliver_user['num'] + 1;
    		}
    		$database_deliver_user->where(array('uid' => $supply['uid']))->save($deliver_user_data);
    	}
    	
    	if (D('Deliver_supply')->where(array("supply_id" => $supply_id))->save($columns)) {
	  		if ($supply['item'] == 0) {
	    		if ($order = D("Meal_order")->field(true)->where(array('order_id' => $supply['order_id']))->find()) {
	    			$data = array('order_status' => 1, 'status' => 1);//配送状态更改成已完成，订单状态改成已消费
	    			if ($order['paid'] == 0) {
	    				$data['paid'] = 1;
	    				if (empty($data['pay_type']) && empty($data['pay_time'])) $data['pay_type'] = 'offline';
	    			}
	    			if (empty($order['pay_time'])) $data['pay_time'] = time();
	    			if (empty($order['use_time'])) $data['use_time'] = time();
	    			if (empty($order['third_id'])) $data['third_id'] = $order['order_id'];
	    			if ($result = D("Meal_order")->where(array('order_id' => $supply['order_id']))->data($data)->save()) {
	    				$this->meal_notice($order);
	    			} else {
	    				exit(json_encode(array('error_code' => true, 'msg' => "更新订单信息错误")));
	    			}
	    		} else {
	    			exit(json_encode(array('error_code' => true, 'msg' => "订单信息错误")));
	    		}
	    	} elseif ($supply['item'] == 2) {//快店的配送
	    		if ($order = D("Shop_order")->field(true)->where(array('order_id' => $supply['order_id']))->find()) {
	    			$data = array('order_status' => 5, 'status' => 2);
	    			if ($order['is_pick_in_store'] == 0) {//平台配送
	    				if ($order['paid'] == 0 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {
	    					$data['paid'] = $order['paid'] == 0 ? 1 : $order['paid'];
	    					$data['pay_type'] = '';
	    					$data['balance_pay'] = $supply['deliver_cash'];
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
	    			if ($result = D("Shop_order")->where(array('order_id' => $order['order_id']))->data($data)->save()) {
	    				if ($order['is_pick_in_store'] == 0) {//平台配送
	    					if ($order['paid'] == 0 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {
	    						D('User_money_list')->add_row($order['uid'], 1, $supply['deliver_cash'], '用户充值用于购买快店产品');
	    						D('User_money_list')->add_row($order['uid'], 2, $supply['deliver_cash'], '用户购买快店产品');
	    					}
	    				}
	    				D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 4));
	    				D('Shop_order')->shop_notice($order);
	    				D('Shop_order_log')->add_log(array('order_id' => $order['order_id'], 'status' => 6, 'name' => '系统管理员：' . $this->system_session['realname'], 'phone' => $this->system_session['phone']));
	    			} else {
	    				exit(json_encode(array('error_code' => true, 'msg' => "更新订单信息错误")));
	    			}
	    		} else {
	    			exit(json_encode(array('error_code' => true, 'msg' => "订单信息错误")));
	    		}
	    	} elseif ($supply['item'] == 3) {
                D('Service_offer')->offer_save_status($supply['order_id'], $supply['uid'], $supply['offer_id'], 4);
            }
	    	//统计每日配送订单量
	    	
	    	$total = D('Deliver_supply')->where(array('uid' => $supply['uid'], 'status' => array('gt', 4)))->count();
	    	D('Deliver_user')->where(array('uid' => $supply['uid']))->save(array('num' => $total));
	    	$todayNum = D('Deliver_supply')->where(array('uid' => $supply['uid'], 'status' => array('gt', 4), 'end_time' => array(array('gt', strtotime(date('Y-m-d') . ' 00:00:00')), array('lt', time() + 2))))->count();
	    	$date = date('Ymd');
	    	if ($deliver_count = D('Deliver_count')->field(true)->where(array('uid' => $supply['uid'], 'today' => $date))->find()) {
	    	    D('Deliver_count')->where(array('uid' => $supply['uid'], 'today' => $date))->save(array('num' => $todayNum));
	    	} else {
	    	    D('Deliver_count')->add(array('uid' => $supply['uid'], 'today' => $date, 'num' => 1));
	    	}
	    	exit(json_encode(array('error_code' => false, 'msg' => "更新状态成功")));
    	} else {
    		exit(json_encode(array('error_code' => true, 'msg' => "更新状态失败")));
    	}
    }

    public function change_status()
    {
        $supply_id = isset($_GET['supply_id']) ? intval($_GET['supply_id']) : 0;
        $status = isset($_GET['status']) ? intval($_GET['status']) : 0;
        $supply = D('Deliver_supply')->field(true)->where(array('supply_id' => $supply_id, 'type' => 0))->find();
        if (empty($supply)) exit(json_encode(array('error_code' => true, 'msg' => '不存在的数据')));
        if ($supply['status'] == 5 || $supply['status'] == 0) exit(json_encode(array('error_code' => false, 'msg' => '修改成功')));
        if ($supply['status'] == 1) exit(json_encode(array('error_code' => true, 'msg' => '配送员还未接单，不能修改成已完成')));

        $columns = array();
        $columns['status'] = $status;
        $columns['end_time'] = time();
        //完成订单
        if ($status == 5) {
            $database_deliver_user = D('Deliver_user');
            $date = 0;
            if ($now_deliver_user = $database_deliver_user->field(true)->where(array('uid' => $supply['uid']))->find()) {
                $today = date('Ymd');
                $num = 0;
                if ($now_deliver_user['today'] != $today) {
                    $date = $now_deliver_user['today'];
                    $num = $now_deliver_user['today_num'];
                    $deliver_user_data['today'] = $today;
                    $deliver_user_data['today_num'] = 1;
                    $deliver_user_data['num'] = $now_deliver_user['num'] + 1;
                } else {
                    $deliver_user_data['today_num'] = $now_deliver_user['today_num'] + 1;
                    $deliver_user_data['num'] = $now_deliver_user['num'] + 1;
                }
                $database_deliver_user->where(array('uid' => $supply['uid']))->save($deliver_user_data);
            }

            if (D('Deliver_supply')->where(array("supply_id" => $supply_id))->save($columns)) {
                if ($supply['item'] == 0) {
                    if ($order = D("Meal_order")->field(true)->where(array('order_id' => $supply['order_id']))->find()) {
                        $data = array('order_status' => 1, 'status' => 1);//配送状态更改成已完成，订单状态改成已消费
                        if ($order['paid'] == 0) {
                            $data['paid'] = 1;
                            if (empty($data['pay_type']) && empty($data['pay_time'])) $data['pay_type'] = 'offline';
                        }
                        if (empty($order['pay_time'])) $data['pay_time'] = time();
                        if (empty($order['use_time'])) $data['use_time'] = time();
                        if (empty($order['third_id'])) $data['third_id'] = $order['order_id'];
                        if ($result = D("Meal_order")->where(array('order_id' => $supply['order_id']))->data($data)->save()) {
                            $this->meal_notice($order);
                        } else {
                            exit(json_encode(array('error_code' => true, 'msg' => "更新订单信息错误")));
                        }
                    } else {
                        exit(json_encode(array('error_code' => true, 'msg' => "订单信息错误")));
                    }
                } elseif ($supply['item'] == 2) {//快店的配送
                    if ($order = D("Shop_order")->field(true)->where(array('order_id' => $supply['order_id']))->find()) {
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
                        if ($result = D("Shop_order")->where(array('order_id' => $order['order_id']))->data($data)->save()) {
                            if ($order['is_pick_in_store'] == 0) {//平台配送
                                if ($order['paid'] == 0 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {
                                    D('User_money_list')->add_row($order['uid'], 1, $supply['deliver_cash'], '用户充值用于购买快店产品');
                                    D('User_money_list')->add_row($order['uid'], 2, $supply['deliver_cash'], '用户购买快店产品');
                                }
                            }
                            D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 4));
                            D('Shop_order')->shop_notice($order);
                            D('Shop_order_log')->add_log(array('order_id' => $order['order_id'], 'status' => 6, 'name' => '系统管理员：' . $this->system_session['realname'], 'phone' => $this->system_session['phone']));
                        } else {
                            exit(json_encode(array('error_code' => true, 'msg' => "更新订单信息错误")));
                        }
                    } else {
                        exit(json_encode(array('error_code' => true, 'msg' => "订单信息错误")));
                    }
                } elseif ($supply['item'] == 3) {
                    D('Service_offer')->offer_save_status($supply['order_id'], $supply['uid'], $supply['offer_id'], 4);
                }
                //统计每日配送订单量

                $total = D('Deliver_supply')->where(array('uid' => $supply['uid'], 'status' => array('gt', 4)))->count();
                D('Deliver_user')->where(array('uid' => $supply['uid']))->save(array('num' => $total));
                $todayNum = D('Deliver_supply')->where(array('uid' => $supply['uid'], 'status' => array('gt', 4), 'end_time' => array(array('gt', strtotime(date('Y-m-d') . ' 00:00:00')), array('lt', time() + 2))))->count();
                $date = date('Ymd');
                if ($deliver_count = D('Deliver_count')->field(true)->where(array('uid' => $supply['uid'], 'today' => $date))->find()) {
                    D('Deliver_count')->where(array('uid' => $supply['uid'], 'today' => $date))->save(array('num' => $todayNum));
                } else {
                    D('Deliver_count')->add(array('uid' => $supply['uid'], 'today' => $date, 'num' => 1));
                }
                exit(json_encode(array('error_code' => false, 'msg' => "更新状态成功")));
            } else {
                exit(json_encode(array('error_code' => true, 'msg' => "更新状态失败")));
            }
        } elseif ($status == 0) {
            //失败订单
            if (D('Deliver_supply')->where(array("supply_id" => $supply_id))->save($columns)) {
                if ($supply['item'] == 0) {
                    if ($order = D("Meal_order")->field(true)->where(array('order_id' => $supply['order_id']))->find()) {
                        $data = array('order_status' => 0, 'status' => 4);//配送状态更改，订单状态更改

                        if ($result = D("Meal_order")->where(array('order_id' => $supply['order_id']))->data($data)->save()) {

                        } else {
                            exit(json_encode(array('error_code' => true, 'msg' => "更新订单信息错误")));
                        }
                    } else {
                        exit(json_encode(array('error_code' => true, 'msg' => "订单信息错误")));
                    }
                } elseif ($supply['item'] == 2) {//快店的配送
                    if ($order = D("Shop_order")->field(true)->where(array('order_id' => $supply['order_id']))->find()) {
                        $data = array('order_status' => 6, 'status' => 5);

                        if ($result = D("Shop_order")->where(array('order_id' => $order['order_id']))->data($data)->save()) {
                            $data_log['dateline'] = time();
                            $data_log['status'] = 34;
                            $data_log['order_id'] = $order['order_id'];
                            $data_log['from_type'] = 1;
                            $data_log['note'] = '系统将配送状态修改为配送失败';
                            D("Shop_order_log")->add($data_log);
                            D('Shop_order')->check_refund($order);
                            // 推送模板消息
                            $now_order = D('Shop_order')->where(array( 'order_id' => $supply['order_id']))->find();
                            $now_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();
                            if ($now_user['openid']) {
                                $href = C('config.site_url') . '/wap.php?g=Wap&c=Shop&a=status&order_id=' . $supply['order_id'];
                                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                                $model->sendTempMsg('TM00017', array(
                                    'href' => $href,
                                    'wecha_id' => $now_user['openid'],
                                    'first' => '您的'.C('config.shop_alias_name').'订单已取消，系统已自动退款！',
                                    'OrderSn' => $now_order['real_orderid'],
                                    'OrderStatus' => '已完成退款',
                                    'remark' => date('Y-m-d H:i:s')
                                ));
                            }
                            $deliver_user = D('Deliver_user')->field(true)->where(array('uid' => $supply['uid']))->find();
                            if($deliver_user['openid']){
                                $href = '';
                                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                                $model->sendTempMsg('TM00017', array(
                                    'href' => $href,
                                    'wecha_id' => $deliver_user['openid'],
                                    'first' => '用户取消'.C('config.shop_alias_name').'订单！',
                                    'OrderSn' => $now_order['real_orderid'],
                                    'OrderStatus' => '已取消',
                                    'remark' => date('Y-m-d H:i:s')
                                ));
                            }
                        } else {
                            exit(json_encode(array('error_code' => true, 'msg' => "更新订单信息错误")));
                        }
                    } else {
                        exit(json_encode(array('error_code' => true, 'msg' => "订单信息错误")));
                    }
                } elseif ($supply['item'] == 3) {
                    // 进行原路退款
                    $refund = D('Plat_order')->order_refund(array('business_type' => 'service', 'business_id' => $supply['order_id']));
                    if (!$refund['error']) {
                        D('Service_user_publish')->where(array('publish_id' => $supply['order_id']))->save(array('status' => 6));
                        D('Service_offer')->where(array('publish_id' => $supply['order_id']))->save(array('status' => 6));
                        D('Deliver_supply')->updateStatusToZero($supply['order_id']);
                        $now_offer = D('Service_offer')->where(array('publish_id'=>$supply['order_id']))->find();
                        D("Service_offer_record")->add(array('offer_id'=>$now_offer['offer_id'],'publish_id'=>$supply['order_id'],'add_time'=>time(),'remarks'=>'退款成功'));
                        // 推送模板消息
                        $now_order = D('Plat_order')->where(array('business_type' => 'service', 'business_id' => $supply['order_id']))->find();
                        $now_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();
                        $order_sn = D('Service_user_publish')->where(array('publish_id'=>$supply['order_id']))->getField('order_sn');
                        if ($now_user['openid']) {
                            $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $supply['order_id'];
                            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));

                            $model->sendTempMsg('TM00017', array(
                                'href' => $href,
                                'wecha_id' => $now_user['openid'],
                                'first' => '您的跑腿订单已取消，系统已自动退款！',
                                'OrderSn' => $order_sn,
                                'OrderStatus' => '已完成退款',
                                'remark' => date('Y-m-d H:i:s')
                            ));
                        }
                        $deliver_user = D('Deliver_user')->field(true)->where(array('uid' => $supply['uid']))->find();
                        if($deliver_user['openid']){
                            $href = '';
                            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                            $model->sendTempMsg('TM00017', array(
                                'href' => $href,
                                'wecha_id' => $deliver_user['openid'],
                                'first' => '用户取消跑腿订单！',
                                'OrderSn' => $order_sn,
                                'OrderStatus' => '已取消',
                                'remark' => date('Y-m-d H:i:s')
                            ));
                        }
                    }
                    exit(json_encode(array('error_code' => false, 'msg' => "更新状态成功")));
                } else {
                    exit(json_encode(array('error_code' => true, 'msg' => "更新状态失败")));
                }
            } elseif ($status == 4) {
                //配送中
                if (D('Deliver_supply')->where(array("supply_id" => $supply_id))->save($columns)) {
                    if ($supply['item'] == 0) {
                        if ($order = D("Meal_order")->field(true)->where(array('order_id' => $supply['order_id']))->find()) {
                            $data = array('order_status' => 5);//配送状态更改，订单状态更改

                            if ($result = D("Meal_order")->where(array('order_id' => $supply['order_id']))->data($data)->save()) {

                            } else {
                                exit(json_encode(array('error_code' => true, 'msg' => "更新订单信息错误")));
                            }
                        } else {
                            exit(json_encode(array('error_code' => true, 'msg' => "订单信息错误")));
                        }
                    } elseif ($supply['item'] == 2) {//快店的配送
                        if ($order = D("Shop_order")->field(true)->where(array('order_id' => $supply['order_id']))->find()) {
                            $data = array('order_status' => 3);

                            if ($result = D("Shop_order")->where(array('order_id' => $order['order_id']))->data($data)->save()) {

                            } else {
                                exit(json_encode(array('error_code' => true, 'msg' => "更新订单信息错误")));
                            }
                        } else {
                            exit(json_encode(array('error_code' => true, 'msg' => "订单信息错误")));
                        }
                    } elseif ($supply['item'] == 3) {
                        $data = array('status' => 9);
                        D('Service_offer')->where(array('offer_id' => $supply['order_id']))->data($data)->save();
                    }
                    exit(json_encode(array('error_code' => false, 'msg' => "更新状态成功")));
                } else {
                    exit(json_encode(array('error_code' => true, 'msg' => "更新状态失败")));
                }

            } else {
                exit(json_encode(array('error_code' => true, 'msg' => "状态输入有误")));
            }
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
                
                 $now_merchant = D('Merchant')->get_info($store['mer_id']);
                if($now_merchant['score_get_percent']>=0){
                    $this->config['score_get'] = $now_merchant['score_get_percent']/100;
                }

    			D('User')->add_score($order['uid'], floor(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);
    			D('Scroll_msg')->add_msg('meal',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'在'. $store['name'] . ' 中消费获得'.$this->config['score_name']);
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
			
    	}
    }

    
	public function export() 
	{
		set_time_limit(0);	
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '配送列表';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);
		// 设置当前的sheet
		$phone = I("phone", 0);
		$status = I('status', 0, 'intval');
		$day = I('day', 0, 'intval');
		$period = I('period', '', 'htmlspecialchars');
		$order_from = isset($_GET['order_from']) ? intval($_GET['order_from']) : -1;
		
		$timetype = I('timetype', 0, 'intval');
		$time = I('time', 0, 'intval');
		$stime = $etime = 0;
		if ($day) {
			$stime = strtotime("-{$day} day");
			$etime = time();
		}
		if ($period) {
			$time_array = explode('-', $period);
			$stime = strtotime($time_array[0]);
			$etime = strtotime($time_array[1]) + 86400;
		}

        $sql = "SELECT count(1) AS cnt ,case  when s.`server_type` <> 0 then sp.`order_sn` else so.real_orderid end as order_sn FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid LEFT JOIN " . C('DB_PREFIX'). "service_user_publish AS sp ON (sp.publish_id=s.order_id AND s.`server_type` <> 0) LEFT JOIN " . C('DB_PREFIX'). "user AS us ON (sp.uid=us.uid AND s.`server_type` <> 0) LEFT JOIN " . C('DB_PREFIX'). "shop_order AS so ON (so.order_id=s.order_id AND s.`server_type` = 0)";

        $sql .= ' WHERE s.type=0';
        if ($phone) {
            $sql .= " AND (s.phone=" . $phone . ' OR us.phone=' . $phone . ')';
        }
		if ($time > 0) {
		    $sqltime = $time * 60;
		    if ($timetype == 0) {
		        $sql .= " AND s.create_time>s.order_time AND s.create_time-s.order_time>" . $sqltime;
		    } elseif ($timetype == 1) {
		        $sql .= " AND s.start_time>s.create_time AND s.start_time-s.create_time>" . $sqltime;
		    } elseif ($timetype == 2) {
		        $sql .= " AND s.end_time>s.start_time AND s.end_time-s.start_time>" . $sqltime;
		    }
		}
		if ($stime && $etime) {
		    $sql .= " AND s.create_time>'{$stime}' AND s.create_time<'{$etime}'";
		}
		if ($status != -1) {
		    $sql .= " AND s.status='{$status}'";
		}
		
		if ($order_from > - 1) {
		    if ($order_from == 0) {
		        $sql .= " AND s.order_from<3";
		    } elseif ($order_from == 4) {
		        $sql .= " AND (s.order_from=4 OR s.order_from=5)";
		    } else {
		        $sql .= " AND s.order_from=" . $order_from;
		    }
		}
		
		if ($this->system_session['area_id']) {
		    $area_index = D('Area')->getIndexByAreaID($this->system_session['area_id']);
		    $sql .= ' AND m.' . $area_index . '=' . $this->system_session['area_id'];
		}
		
		$count = D()->query($sql);
		$count = isset($count[0]['cnt']) ? intval($count[0]['cnt']) : 0;
		
		$length = ceil($count / 1000);
        $m_shop_order_detail = M('Shop_order_detail');
        $m_shop_order = M('Shop_order');
        $m_service_user_publish_buy = M('Service_user_publish_buy');
        $m_service_user_publish_give = M('Service_user_publish_give');
        $m_service_user_publish = M('Service_user_publish');
        $m_area = M('Area');
		for ($i = 0; $i < $length; $i++) { 
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);
	
			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千配送订单');
			$objActSheet = $objExcel->getActiveSheet();

            $objActSheet->setCellValue('A1', '订单编号');
            $objActSheet->setCellValue('B1', '配送ID');
            $objActSheet->setCellValue('C1', '订单来源');
            $objActSheet->setCellValue('D1', '商品名称');
            $objActSheet->setCellValue('E1', '商品价格');
            $objActSheet->setCellValue('F1', '商品数量');
            $objActSheet->setCellValue('G1', '店铺名称');
            $objActSheet->setCellValue('H1', '店铺商圈');
			$objActSheet->setCellValue('I1', '客户名称');
            $objActSheet->setCellValue('J1', '用户ID');
            $objActSheet->setCellValue('K1', '客户手机');
			$objActSheet->setCellValue('L1', '客户地址');
            $objActSheet->setCellValue('M1', '支付状态');
            $objActSheet->setCellValue('N1', '支付方式');
            $objActSheet->setCellValue('O1', '支付来源');
			$objActSheet->setCellValue('P1', '订单价格');
			$objActSheet->setCellValue('Q1', '应收现金');
			$objActSheet->setCellValue('R1', '配送员昵称');
			$objActSheet->setCellValue('S1', '配送员手机号');
			$objActSheet->setCellValue('T1', '开始时间');
			$objActSheet->setCellValue('U1', '送达时间');
			$objActSheet->setCellValue('V1', '配送费');
			$objActSheet->setCellValue('W1', '小费');
			$objActSheet->setCellValue('X1', '店员接单时长');
			$objActSheet->setCellValue('Y1', '配送员接单时长');
			$objActSheet->setCellValue('Z1', '配送时长');
            $objActSheet->setCellValue('AA1', '配送员取货时间');
            $objActSheet->setCellValue('AB1', '配送员开始配送时间');
            $objActSheet->setCellValue('AC1', '配送状态');
            $objActSheet->setCellValue('AD1', '接单类型');
            $objActSheet->setCellValue('AE1', '订单备注');

			$sql = "SELECT m.`circle_id`, s.`supply_id`, s.`order_from`, case  when s.`server_type` <> 0 then sp.`order_sn` else so.real_orderid end as order_sn, s.order_id, s.item, s.name as username, s.phone as userphone, m.name as storename, s.money, u.name, u.phone, s.create_time, s.order_time, s.start_time, s.end_time, s.aim_site, s.pay_type, s.paid, s.status, s.deliver_cash, s.freight_charge, s.tip_price FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid LEFT JOIN " . C('DB_PREFIX'). "service_user_publish AS sp ON (sp.publish_id=s.order_id AND s.`server_type` <> 0) LEFT JOIN " . C('DB_PREFIX'). "user AS us ON (sp.uid=us.uid AND s.`server_type` <> 0) LEFT JOIN " . C('DB_PREFIX'). "shop_order AS so ON (so.order_id=s.order_id AND s.`server_type` = 0)";


            $sql .= ' WHERE s.type=0';
            if ($phone) {
                $sql .= " AND (s.phone=" . $phone . ' OR us.phone=' . $phone . ')';
            }
			if ($time > 0) {
			    $sqltime = $time * 60;
			    if ($timetype == 0) {
			        $sql .= " AND s.create_time>s.order_time AND s.create_time-s.order_time>" . $sqltime;
			    } elseif ($timetype == 1) {
			        $sql .= " AND s.start_time>s.create_time AND s.start_time-s.create_time>" . $sqltime;
			    } elseif ($timetype == 2) {
			        $sql .= " AND s.end_time>s.start_time AND s.end_time-s.start_time>" . $sqltime;
			    }
			}
			
			if ($stime && $etime) {
				$sql .= " AND s.create_time>'{$stime}' AND s.create_time<'{$etime}'";
			}
			if ($status != -1) {
				$sql .= " AND s.status='{$status}'";
			}
			if ($order_from > - 1) {
			    if ($order_from == 0) {
			        $sql .= " AND s.order_from<3";
			    } elseif ($order_from == 4) {
			        $sql .= " AND (s.order_from=4 OR s.order_from=5)";
			    } else {
			        $sql .= " AND s.order_from=" . $order_from;
			    }
			}
			if ($this->system_session['area_id']) {
			    $area_index = D('Area')->getIndexByAreaID($this->system_session['area_id']);
			    $sql .= ' AND m.' . $area_index . '=' . $this->system_session['area_id'];
			}
			
			$sql .= ' ORDER BY s.`supply_id` DESC LIMIT ' . $i * 1000 . ', 1000';
			$supply_list = D()->query($sql);
			
			if (!empty($supply_list)) {
				import('ORG.Net.IpLocation');
				$IpLocation = new IpLocation();
				$index = 2;
				foreach ($supply_list as $value) {
				    $value['confirm_time'] = $value['create_time'] && $value['order_time'] ? intval(($value['create_time'] - $value['order_time']) / 60) . '分钟' : '-';
				    $value['grab_time'] = $value['start_time'] ? intval(($value['start_time'] - $value['create_time']) / 60) . '分钟' : '-';
				    $value['deliver_use_time'] = $value['end_time'] ? intval(($value['end_time'] - $value['start_time']) / 60) . '分钟' : '-';
				    $where['order_id'] = $value['order_id'];
				    $where['status'] = array('in','4,5');
				    $times = D('Shop_order_log')->where($where)->select();

				    // 查询一下关联 快店的商品信息
                    $circle_info = '';
                    $order_from = '-'; // 订单来源 0：wap快店，1：wap商城，2：Android，3：ios,4:小程序,5：pc快店,6:线下零售,7.饿了么，8.美团
                    if ($value['item'] != 3 && $value['order_from'] != 3 && $value['order_from'] != 4 && $value['order_from'] != 5) {
                        $shop_detail = $m_shop_order_detail->where(array('order_id' => $value['order_id']))->field('name, num, price')->select();
                        $info = $m_shop_order->where(array('order_id' => $value['order_id']))->field('uid, desc, order_from')->find();
                        $userId = $info['uid'];
                        $remark = $info['desc'];
                        if ($info['order_from'] == 0) {
                            $order_from = 'wap快店';
                        } elseif($info['order_from'] == 1) {
                            $order_from = 'wap商城';
                        } elseif($info['order_from'] == 2) {
                            $order_from = 'Android';
                        } elseif($info['order_from'] == 3) {
                            $order_from = 'ios';
                        } elseif($info['order_from'] == 4) {
                            $order_from = '小程序';
                        } elseif($info['order_from'] == 5) {
                            $order_from = 'pc快店';
                        } elseif($info['order_from'] == 6) {
                            $order_from = '线下零售';
                        } elseif($info['order_from'] == 7) {
                            $order_from = '饿了么';
                        } elseif($info['order_from'] == 8) {
                            $order_from = '美团';
                        }
                        if ($value['circle_id']) {
                            // 获取对应商圈信息
                            $condition_area = array(
                                'area_id' => $value['circle_id']
                            );
                            $circle_arr = $m_area->where($condition_area)->field('area_pid, area_name, area_ip_desc')->find();
                            if (!$circle_arr['area_ip_desc']) {
                                if ($circle_arr['area_pid']) {
                                    $condition_pid_area = array(
                                        'area_id' => $circle_arr['area_pid']
                                    );
                                    $circle_msg = $m_area->where($condition_pid_area)->getField('area_name');
                                    $circle_info = $circle_msg . $circle_arr['area_name'];
                                } else {
                                    $circle_info = $circle_arr['area_name'];
                                }
                            } else {
                                $circle_info = $circle_arr['area_ip_desc'];
                            }
                        } else {
                            $circle_info = '';
                        }
                    } else {
                        // 帮我送
                        $userId = $m_service_user_publish->where(array('publish_id' => $value['order_id']))->getField('uid');
                        if ($value['order_from'] == 3) {
                            $remark = $m_service_user_publish_give->where(array('publish_id' => $value['order_id']))->getField('remarks');
                        } else {
                            // 帮我买
                            $remark = $m_service_user_publish_buy->where(array('publish_id' => $value['order_id']))->getField('goods_remarks');
                        }
                        $shop_detail = array();
                    }

                    if ($shop_detail) {
                        $shop_first = false;
                        foreach ($shop_detail as $val) {
                            if (!$shop_first) {
                                $objActSheet->setCellValueExplicit('A' . $index, $value['order_sn']);
                                $objActSheet->setCellValueExplicit('B' . $index, $value['supply_id']);
                                if ($value['item'] == 0) {
                                    $objActSheet->setCellValueExplicit('C' . $index, $this->config['meal_alias_name']);
                                } elseif ($value['item'] == 1) {
                                    $objActSheet->setCellValueExplicit('C' . $index, $this->config['waimai_alias_name']);
                                } else {
                                    if ($value['order_from'] == 0) {
                                        $objActSheet->setCellValueExplicit('C' . $index, $this->config['shop_alias_name']);
                                    } elseif ($value['order_from'] == 1) {
                                        $objActSheet->setCellValueExplicit('C' . $index, '饿了么');
                                    } elseif ($value['order_from'] == 2) {
                                        $objActSheet->setCellValueExplicit('C' . $index, '美团');
                                    } elseif ($value['order_from'] == 3) {
                                        $objActSheet->setCellValueExplicit('C' . $index, '帮我送');
                                    } elseif ($value['order_from'] == 4 || $value['order_from'] == 5) {
                                        $objActSheet->setCellValueExplicit('C' . $index, '帮我买');
                                    }
                                }
                                $objActSheet->setCellValueExplicit('D' . $index, $val['name']);
                                $objActSheet->setCellValueExplicit('E' . $index, $val['price']);
                                $objActSheet->setCellValueExplicit('F' . $index, $val['num']);


                                $objActSheet->setCellValueExplicit('G' . $index, $value['storename']);
                                $objActSheet->setCellValueExplicit('H' . $index, $circle_info);
                                $objActSheet->setCellValueExplicit('I' . $index, $value['username']);
                                $objActSheet->setCellValueExplicit('J' . $index, $userId);
                                $objActSheet->setCellValueExplicit('K' . $index, $value['userphone'] . ' ');
                                $objActSheet->setCellValueExplicit('L' . $index, $value['aim_site']);
                                if ($value['paid'] == 1) {
                                    $objActSheet->setCellValueExplicit('M' . $index, '已支付');
                                } else {
                                    $objActSheet->setCellValueExplicit('M' . $index, '未支付');
                                }

                                if ($value['pay_type'] == 'alipay') {
                                    $objActSheet->setCellValueExplicit('N' . $index, '支付宝');
                                } elseif ($value['pay_type'] == 'alipayh5') {
                                    $objActSheet->setCellValueExplicit('N' . $index, '支付宝(H5)');
                                } elseif ($value['pay_type'] == 'tenpay') {
                                    $objActSheet->setCellValueExplicit('N' . $index, '财付通');
                                } elseif ($value['pay_type'] == 'yeepay') {
                                    $objActSheet->setCellValueExplicit('N' . $index, '易宝支付');
                                } elseif ($value['pay_type'] == 'allinpay') {
                                    $objActSheet->setCellValueExplicit('N' . $index, '通联支付');
                                } elseif ($value['pay_type'] == 'chinabank') {
                                    $objActSheet->setCellValueExplicit('N' . $index, '网银在线');
                                } elseif ($value['pay_type'] == 'weixin') {
                                    $objActSheet->setCellValueExplicit('N' . $index, '微信支付');
                                } elseif ($value['pay_type'] == 'baidu') {
                                    $objActSheet->setCellValueExplicit('N' . $index, '百度钱包');
                                } elseif ($value['pay_type'] == 'unionpay') {
                                    $objActSheet->setCellValueExplicit('N' . $index, '银联支付');
                                } elseif ($value['pay_type'] == 'weifutong') {
                                    $objActSheet->setCellValueExplicit('N' . $index, $this->config['pay_weifutong_alias_name']);
                                } elseif ($value['pay_type'] == 'offline') {
                                    $objActSheet->setCellValueExplicit('N' . $index, '货到付款');
                                } elseif ($value['pay_type'] == 'ccb') {
                                    $objActSheet->setCellValueExplicit('N' . $index, '建设银行');
                                } else {
                                    $objActSheet->setCellValueExplicit('N' . $index, '余额支付');
                                }
                                $objActSheet->setCellValueExplicit('O' . $index, $order_from);

                                $objActSheet->setCellValueExplicit('P' . $index, floatval($value['money']));
                                $objActSheet->setCellValueExplicit('Q' . $index, floatval($value['deliver_cash']));


                                $objActSheet->setCellValueExplicit('R' . $index, $value['name']);
                                $objActSheet->setCellValueExplicit('S' . $index, $value['phone'] . ' ');
                                $objActSheet->setCellValueExplicit('T' . $index, $value['start_time'] ? date('Y-m-d H:i:s', $value['start_time']) : '还未开始');
                                $objActSheet->setCellValueExplicit('U' . $index, $value['end_time'] ? date('Y-m-d H:i:s', $value['end_time']) : '还未完成');

                                $objActSheet->setCellValueExplicit('V' . $index, floatval($value['freight_charge']));
                                $objActSheet->setCellValueExplicit('W' . $index, floatval($value['tip_price']));

                                $objActSheet->setCellValueExplicit('X' . $index, $value['confirm_time']);
                                $objActSheet->setCellValueExplicit('Y' . $index, $value['grab_time']);
                                $objActSheet->setCellValueExplicit('Z' . $index, $value['deliver_use_time']);
                                $objActSheet->setCellValueExplicit('AA' . $index, $times[0]['dateline']? date('Y-m-d H:i:s',$times[0]['dateline']) : '还未开始');
                                $objActSheet->setCellValueExplicit('AB' . $index, $times[1]['dateline']? date('Y-m-d H:i:s',$times[1]['dateline']) : '还未开始');
                                switch ($value['status']) {
                                    case 1:
                                        $value['order_status'] = "等待接单";
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
                                        $value['order_status'] = "已完成";
                                        break;
                                    case 6:
                                        $value['order_status'] = "已评价";
                                        break;
                                    case 7:
                                        $value['order_status'] = "退款退款失败";
                                        break;
                                    default:
                                        $value['order_status'] = "订单失效";
                                        break;
                                }
                                $objActSheet->setCellValueExplicit('AC' . $index, $value['order_status'] );
                                if($value['status']<2){
                                    $value['get_type'] = '--';
                                }elseif ($value['get_type']==0){
                                    $value['get_type'] = '抢单';
                                }else{
                                    $value['get_type'] = '系统派单';
                                }
                                $objActSheet->setCellValueExplicit('AD' . $index,  $value['get_type'] );
                                $objActSheet->setCellValueExplicit('AE' . $index,  $remark );
                                $shop_first = true;
                                $index++;
                            }else {
                                $objActSheet->setCellValueExplicit('D' . $index, $val['name']);
                                $objActSheet->setCellValueExplicit('E' . $index, $val['price']);
                                $objActSheet->setCellValueExplicit('F' . $index, $val['num']);
                                $index++;
                            }
                        }
                    } else {
                        $objActSheet->setCellValueExplicit('A' . $index, $value['order_sn']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['supply_id']);
                        if ($value['item'] == 0) {
                            $objActSheet->setCellValueExplicit('C' . $index, $this->config['meal_alias_name']);
                        } elseif ($value['item'] == 1) {
                            $objActSheet->setCellValueExplicit('C' . $index, $this->config['waimai_alias_name']);
                        } else {
                            if ($value['order_from'] == 0) {
                                $objActSheet->setCellValueExplicit('C' . $index, $this->config['shop_alias_name']);
                            } elseif ($value['order_from'] == 1) {
                                $objActSheet->setCellValueExplicit('C' . $index, '饿了么');
                            } elseif ($value['order_from'] == 2) {
                                $objActSheet->setCellValueExplicit('C' . $index, '美团');
                            } elseif ($value['order_from'] == 3) {
                                $objActSheet->setCellValueExplicit('C' . $index, '帮我送');
                            } elseif ($value['order_from'] == 4 || $value['order_from'] == 5) {
                                $objActSheet->setCellValueExplicit('C' . $index, '帮我买');
                            }
                        }
                        $objActSheet->setCellValueExplicit('D' . $index, '-');
                        $objActSheet->setCellValueExplicit('E' . $index, '-');
                        $objActSheet->setCellValueExplicit('F' . $index, '-');


                        $objActSheet->setCellValueExplicit('G' . $index, $value['storename']);
                        $objActSheet->setCellValueExplicit('H' . $index, $circle_info);
                        $objActSheet->setCellValueExplicit('I' . $index, $value['username']);
                        $objActSheet->setCellValueExplicit('J' . $index, $userId);
                        $objActSheet->setCellValueExplicit('K' . $index, $value['userphone'] . ' ');
                        $objActSheet->setCellValueExplicit('L' . $index, $value['aim_site']);
                        if ($value['paid'] == 1) {
                            $objActSheet->setCellValueExplicit('M' . $index, '已支付');
                        } else {
                            $objActSheet->setCellValueExplicit('M' . $index, '未支付');
                        }

                        if ($value['pay_type'] == 'alipay') {
                            $objActSheet->setCellValueExplicit('N' . $index, '支付宝');
                        } elseif ($value['pay_type'] == 'alipayh5') {
                            $objActSheet->setCellValueExplicit('N' . $index, '支付宝(H5)');
                        } elseif ($value['pay_type'] == 'tenpay') {
                            $objActSheet->setCellValueExplicit('N' . $index, '财付通');
                        } elseif ($value['pay_type'] == 'yeepay') {
                            $objActSheet->setCellValueExplicit('N' . $index, '易宝支付');
                        } elseif ($value['pay_type'] == 'allinpay') {
                            $objActSheet->setCellValueExplicit('N' . $index, '通联支付');
                        } elseif ($value['pay_type'] == 'chinabank') {
                            $objActSheet->setCellValueExplicit('N' . $index, '网银在线');
                        } elseif ($value['pay_type'] == 'weixin') {
                            $objActSheet->setCellValueExplicit('N' . $index, '微信支付');
                        } elseif ($value['pay_type'] == 'baidu') {
                            $objActSheet->setCellValueExplicit('N' . $index, '百度钱包');
                        } elseif ($value['pay_type'] == 'unionpay') {
                            $objActSheet->setCellValueExplicit('N' . $index, '银联支付');
                        } elseif ($value['pay_type'] == 'weifutong') {
                            $objActSheet->setCellValueExplicit('N' . $index, $this->config['pay_weifutong_alias_name']);
                        } elseif ($value['pay_type'] == 'offline') {
                            $objActSheet->setCellValueExplicit('N' . $index, '货到付款');
                        } elseif ($value['pay_type'] == 'ccb') {
                            $objActSheet->setCellValueExplicit('N' . $index, '建设银行');
                        } else {
                            $objActSheet->setCellValueExplicit('N' . $index, '余额支付');
                        }
                        $objActSheet->setCellValueExplicit('O' . $index, $order_from);

                        $objActSheet->setCellValueExplicit('P' . $index, floatval($value['money']));
                        $objActSheet->setCellValueExplicit('Q' . $index, floatval($value['deliver_cash']));


                        $objActSheet->setCellValueExplicit('R' . $index, $value['name']);
                        $objActSheet->setCellValueExplicit('S' . $index, $value['phone'] . ' ');
                        $objActSheet->setCellValueExplicit('T' . $index, $value['start_time'] ? date('Y-m-d H:i:s', $value['start_time']) : '还未开始');
                        $objActSheet->setCellValueExplicit('U' . $index, $value['end_time'] ? date('Y-m-d H:i:s', $value['end_time']) : '还未完成');

                        $objActSheet->setCellValueExplicit('V' . $index, floatval($value['freight_charge']));
                        $objActSheet->setCellValueExplicit('W' . $index, floatval($value['tip_price']));

                        $objActSheet->setCellValueExplicit('X' . $index, $value['confirm_time']);
                        $objActSheet->setCellValueExplicit('Y' . $index, $value['grab_time']);
                        $objActSheet->setCellValueExplicit('Z' . $index, $value['deliver_use_time']);
                        $objActSheet->setCellValueExplicit('AA' . $index, $times[0]['dateline']? date('Y-m-d H:i:s',$times[0]['dateline']) : '还未开始');
                        $objActSheet->setCellValueExplicit('AB' . $index, $times[1]['dateline']? date('Y-m-d H:i:s',$times[1]['dateline']) : '还未开始');
                        switch ($value['status']) {
                            case 1:
                                $value['order_status'] = "等待接单";
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
                                $value['order_status'] = "已完成";
                                break;
                            case 6:
                                $value['order_status'] = "已评价";
                                break;
                            case 7:
                                $value['order_status'] = "退款退款失败";
                                break;
                            default:
                                $value['order_status'] = "订单失效";
                                break;
                        }
                        $objActSheet->setCellValueExplicit('AC' . $index, $value['order_status'] );
                        if($value['status']<2){
                            $value['get_type'] = '--';
                        }elseif ($value['get_type']==0){
                            $value['get_type'] = '抢单';
                        }else{
                            $value['get_type'] = '系统派单';
                        }
                        $objActSheet->setCellValueExplicit('AD' . $index,  $value['get_type'] );
                        $objActSheet->setCellValueExplicit('AE' . $index,  $remark );

                        $index++;
                    }
				}
			}
			sleep(2);
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
		
	}
	
	public function export_user()
	{
		set_time_limit(0);
		
		$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
		$begin_time = isset($_GET['begin_time']) ? htmlspecialchars($_GET['begin_time']) : '';
		$end_time = isset($_GET['end_time']) ? htmlspecialchars($_GET['end_time']) : '';
		$condition_user = array('mer_id' => 0, 'uid' => $uid);
		$user = $this->deliver_user->field(true)->where($condition_user)->find();
		if (empty($user)) $this->error('不存在的配送员');
		
		
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		
		if ($begin_time && $end_time) {
			$title = '【' . $user['name'] . '】在' . $begin_time . '至' . $end_time . '时间段的配送记录列表';
		} else {
			$title = '【' . $user['name'] . '】的配送记录列表';
		}
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);
		
		// 设置当前的sheet
		$sql_count = "SELECT count(1) AS count FROM " . C('DB_PREFIX') . "merchant_store AS m INNER JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id";
		$sql_count .= ' WHERE s.type=0 AND s.uid=' . $uid;
		if ($begin_time && $end_time) {
			$sql_count .= ' AND s.start_time>' . strtotime($begin_time) . ' AND s.start_time<' . strtotime($end_time);
		}
		
		$res_count = D()->query($sql_count);
		$count_order = isset($res_count[0]['count']) ? $res_count[0]['count'] : 0;
		
		$length = ceil($count_order / 1000);
		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);
		
			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千配送订单');
			$objActSheet = $objExcel->getActiveSheet();
				
			$objActSheet->setCellValue('A1', '配送ID');
			$objActSheet->setCellValue('B1', '订单来源');
			$objActSheet->setCellValue('C1', '店铺名称');
			$objActSheet->setCellValue('D1', '客户名称');
			$objActSheet->setCellValue('E1', '客户手机');
			$objActSheet->setCellValue('F1', '客户地址');
			$objActSheet->setCellValue('G1', '支付状态');
			$objActSheet->setCellValue('H1', '订单价格');
			$objActSheet->setCellValue('I1', '小费');
			$objActSheet->setCellValue('J1', '配送状态');
			$objActSheet->setCellValue('K1', '开始时间');
			$objActSheet->setCellValue('L1', '送达时间');
			$objActSheet->setCellValue('M1', '配送费');
			$objActSheet->setCellValue('N1', '应收现金');
			
			
			$sql = "SELECT s.`supply_id`, s.order_id, s.item, s.name as username, s.phone as userphone, m.name as storename, s.money, s.start_time, s.end_time, s.aim_site, s.pay_type, s.paid, s.status, s.deliver_cash, s.freight_charge, s.tip_price FROM " . C('DB_PREFIX') . "merchant_store AS m INNER JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id";
			$sql .= ' WHERE s.type=0 AND s.uid=' . $uid;
			if ($begin_time && $end_time) {
				$sql .= ' AND s.start_time>' . strtotime($begin_time) . ' AND s.start_time<' . strtotime($end_time);
			}
			
			$sql .= ' ORDER BY s.`supply_id` DESC LIMIT ' . $i * 1000 . ', 1000';
			
			$supply_list = D()->query($sql);
				
			if (!empty($supply_list)) {
				import('ORG.Net.IpLocation');
				$IpLocation = new IpLocation();
				$index = 2;
				foreach ($supply_list as $value) {
						
					$objActSheet->setCellValueExplicit('A' . $index, $value['supply_id']);
					if ($value['item'] == 0) {
						$objActSheet->setCellValueExplicit('B' . $index, $this->config['meal_alias_name']);
					} elseif ($value['item'] == 1) {
						$objActSheet->setCellValueExplicit('B' . $index, $this->config['waimai_alias_name']);
					} elseif ($value['item'] == 2) {
						$objActSheet->setCellValueExplicit('B' . $index, $this->config['shop_alias_name']);
					}
					$objActSheet->setCellValueExplicit('C' . $index, $value['storename']);
					$objActSheet->setCellValueExplicit('D' . $index, $value['username']);
					$objActSheet->setCellValueExplicit('E' . $index, $value['userphone'] . ' ');
					$objActSheet->setCellValueExplicit('F' . $index, $value['aim_site']);
					if ($value['paid'] == 1) {
						$objActSheet->setCellValueExplicit('G' . $index, '已支付');
					} else {
						$objActSheet->setCellValueExplicit('G' . $index, '未支付');
					}
						
					$objActSheet->setCellValueExplicit('H' . $index, floatval($value['money']));
					$objActSheet->setCellValueExplicit('N' . $index, floatval($value['deliver_cash']));
					switch ($value['status']) {
						case 1:
							$value['order_status'] = '<font color="red">等待接单</font>';
							break;
						case 2:
							$value['order_status'] = "接单";
							break;
						case 3:
							$value['order_status'] = "取货";
							break;
						case 4:
							$value['order_status'] = "开始配送";
							break;
						case 5:
							$value['order_status'] = "完成";
							break;
						case 6:
						    $value['order_status'] = "已评价";
						    break;
						default:
							$value['order_status'] = "订单失效";
							break;
					}	
					$objActSheet->setCellValueExplicit('J' . $index, $value['order_status']);
					$objActSheet->setCellValueExplicit('K' . $index, $value['start_time'] ? date('Y-m-d H:i:s', $value['start_time']) : '--');
					$objActSheet->setCellValueExplicit('L' . $index, $value['end_time'] ? date('Y-m-d H:i:s', $value['end_time']) : '--');
					$objActSheet->setCellValueExplicit('M' . $index, $value['freight_charge']);
					$objActSheet->setCellValueExplicit('I' . $index, $value['tip_price']);
						
					$index++;
				}
			}
			sleep(2);
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="' . $title . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}
    
    public function desk()
    {
        $type = isset($_GET['type']) ? intval($_GET['type']) : 0;
        $area_id = isset($_GET['area_id']) ? intval($_GET['area_id']) : 0;
        $this->assign('type', $type);
        $data = array('is_province' => 1, 'is_city' => 1, 'is_area' => 1);
        $area_type = 0;
        $area_name = '';
        if ($this->system_session['area_id']) {
            $area_index = D('Area')->getIndexByAreaID($this->system_session['area_id']);
            if ($area_index == 'province_id') {
                $data['province_id'] = $this->system_session['area_id'];
                
                $areas = D('Area')->field('area_id, area_pid')->where(array('area_pid' => $this->system_session['area_id'], 'is_open' => 1))->order('area_id ASC')->limit(1)->select();
                $data['city_id'] = $areas[0]['area_id'];
                $areas = D('Area')->field('area_id, area_pid')->where(array('area_pid' => $data['city_id'], 'is_open' => 1))->order('area_id ASC')->limit(1)->select();
                $data['area_id'] = $areas[0]['area_id'];
                
                $data['is_province'] = 0;
            } elseif ($area_index == 'city_id') {
                $area = D('Area')->field('area_id, area_pid')->where(array('area_id' => $this->system_session['area_id']))->find();
                $data['province_id'] = $area['area_pid'];
                $data['city_id'] = $this->system_session['area_id'];
                $areas = D('Area')->field('area_id, area_pid')->where(array('area_pid' => $data['city_id'], 'is_open' => 1))->order('area_id ASC')->limit(1)->select();
                $data['area_id'] = $areas[0]['area_id'];
                $data['is_province'] = 0;
                $data['is_city'] = 0;
            } else {
                $data['area_id'] = $this->system_session['area_id'];
                $area = D('Area')->field('area_name, area_id, area_pid')->where(array('area_id' => $this->system_session['area_id']))->find();
                $area_type = 3;
                $area_name = $area['area_name'];
                $data['city_id'] = $area['area_pid'];
                $area = D('Area')->field('area_id, area_pid')->where(array('area_id' => $data['city_id']))->find();
                $data['province_id'] = $area['area_pid'];
                $data['is_province'] = 0;
                $data['is_city'] = 0;
                $data['is_area'] = 0;
            }
        } else {
            $area = D('Area')->field('area_id, area_pid')->where(array('area_id' => $this->config['now_city']))->find();
            $data['province_id'] = $area['area_pid'];
            $data['city_id'] = $area['area_id'];
            $areas = D('Area')->field('area_id, area_pid')->where(array('area_pid' => $this->config['now_city'], 'is_open' => 1))->order('area_id ASC')->limit(1)->select();
            $data['area_id'] = $areas[0]['area_id'];
        }
        if ($area_id) {
            $area = D('Area')->field('area_id, area_pid')->where(array('area_id' => $area_id))->find();
            $data['area_id'] = $area_id;
            $data['city_id'] = $area['area_pid'];
            $area = D('Area')->field('area_id, area_pid')->where(array('area_id' => $data['city_id']))->find();
            $data['province_id'] = $area['area_pid'];
        }
        $this->assign(array('area_type' => $area_type, 'area_name' => $area_name));
        $data['lat'] = 0;
        $data['lng'] = 0;
        
        $condition_user = array('status' => 1, 'group' => 1);
        $condition_user['province_id'] = $data['province_id'];
        $condition_user['city_id'] = $data['city_id'];
        $condition_user['area_id'] = $data['area_id'];
        $userList = $this->deliver_user->field('uid, now_lng, now_lat, name, phone')->where($condition_user)->limit(1)->select();
        if ($userList) {
            $data['lat'] = $userList[0]['now_lat'];
            $data['lng'] = $userList[0]['now_lng'];
        }
        
        $this->assign($data);
        $this->display();
    }

    public function getData()
    {
        $province_id = I('province_id', 0);
        $city_id = I('city_id', 0);
        $area_id = I('area_id', 0);
        
        $where = array('type' => 0);
        $where['province_id'] = $province_id;
        $where['city_id'] = $city_id;
        $where['area_id'] = $area_id;
        $condition_user = array('status' => 1, 'group' => 1);
        $condition_user['province_id'] = $province_id;
        $condition_user['city_id'] = $city_id;
        $condition_user['area_id'] = $area_id;
        $condition_user['is_notice'] = 0;
        $condition_user['last_time'] = array('neq','0');
        
        $userList = $this->deliver_user->field('uid, lng, lat, now_lng, now_lat, name, phone')->where($condition_user)->select();
        
        $countList = D('Deliver_supply')->field('count(1) as cnt, status, uid')->where($where)->group('uid, status')->select();
        $unGetCount = 0;
        $sendCount = 0;
        $newList = array();
        foreach ($countList as $count) {
            switch ($count['status']) {
                case 0:
                    break;
                case 1:
                    $unGetCount += $count['cnt'];
                    break;
                case 2:
                    $newList[$count['uid']]['fetch'] += $count['cnt'];
                    $sendCount += $count['cnt'];
                    break;
                case 3:
                case 4:
                    $newList[$count['uid']]['send'] += $count['cnt'];
                    $sendCount += $count['cnt'];
                    break;
                case 5:
                    break;
            }
        }
        $where['status'] = array('in', array(5, 6));
        $where['end_time'] = array(array('gt', strtotime(date('Y-m-d'))),array('lt', time()));
        $finishList = D('Deliver_supply')->field('count(1) as cnt, uid')->where($where)->group('uid')->select();
        foreach ($finishList as $finish) {
            $newList[$finish['uid']]['finish'] = $finish['cnt'];
        }
        foreach ($userList as &$user) {
			if($user['now_lng'] == '0.000000'){
				$user['now_lng'] = $user['lng'];
				$user['now_lat'] = $user['lat'];
			}
			unset($user['lng'],$user['lat']);
			
            $user['fetch'] = 0;
            $user['send'] = 0;
            $user['finish'] = 0;
            if (isset($newList[$user['uid']]['fetch'])) {
                $user['fetch'] = $newList[$user['uid']]['fetch'];
            }
            if (isset($newList[$user['uid']]['send'])) {
                $user['send'] = $newList[$user['uid']]['send'];
            }
            if (isset($newList[$user['uid']]['finish'])) {
                $user['finish'] = $newList[$user['uid']]['finish'];
            }
        }
        exit(json_encode(array('errcode' => false, 'data' => $userList, 'userCount' => count($userList), 'unGetCount' => $unGetCount, 'sendCount' => $sendCount)));
    }
    
    
    //加载带指派的订单
    public function initOrders()
    {
        if ($this->config['deliver_model'] == 0) {
            exit(json_encode(array('errcode' => true, 'msg' => '配送员抢单模式中，地图调度暂不可用')));
        }
        $type = isset($_GET['type']) ? intval($_GET['type']) : 0;
        $province_id = I('province_id', 0);
        $city_id = I('city_id', 0);
        $area_id = I('area_id', 0);
        
        $where = array('status' => 1, 'type' => 0);
        $where['province_id'] = $province_id;
        $where['city_id'] = $city_id;
        $where['area_id'] = $area_id;
        
        $time = time() + 3600 * $this->config['expected_duration_date'];
        
        $orderList = D('Deliver_supply')->field(true)->where($where)->order('supply_id ASC')->select();
        $nowTime = time();
        $nowList = array();
        $otherList = array();
        foreach ($orderList as $order) {
            if ($order['order_from'] > 2) {
                $order['store_name'] = $order['from_site'];
            }
            $order['order_out_time'] = date('H:i', $order['order_out_time']);
            $order['desk_time'] = floor(($nowTime - $order['create_time'])/60);
            if ($order['appoint_time'] > $time) {
                $otherList[] = $order;
            } else {
                $nowList[] = $order;
            }
        }
        if ($type == 0) {
            exit(json_encode(array('errcode' => false, 'data' => $nowList, 'nowCount' => count($nowList), 'otherCount' => count($otherList))));
        } else {
            exit(json_encode(array('errcode' => false, 'data' => $otherList, 'nowCount' => count($nowList), 'otherCount' => count($otherList))));
        }
    }
    
    //加载每个配送员待处理的订单
    public function initUserOrders()
    {
        $province_id = I('province_id', 0);
        $city_id = I('city_id', 0);
        $area_id = I('area_id', 0);
        $where = array('status' => array('in', array(2, 3, 4)));
        $where['province_id'] = $province_id;
        $where['city_id'] = $city_id;
        $where['area_id'] = $area_id;
        
        $orderList = D('Deliver_supply')->field(true)->where($where)->order('supply_id ASC')->select();
        
        $newList = [];
        foreach ($orderList as $order) {
            $newList[$order['uid']][] = $order;
        }
        exit(json_encode(array('errcode' => false, 'data' => $newList)));
    }
    // 调度控制台 操作
    public function appointDeliver()
    {
        $supply_ids = isset($_POST['supply_ids']) ? $_POST['supply_ids'] : 0;
        $uid = isset($_POST['uid']) ? intval($_POST['uid']) : 0;
        $user = D('Deliver_user')->field(true)->where(array('uid' => $uid, 'group' => 1, 'status' => 1))->find();
        if (empty($user)) {
            exit(json_encode(array('errcode' => true, 'msg' => '配送员不存在')));
        }
        $supplyArr = explode(',', $supply_ids);
        $supplys = D('Deliver_supply')->field(true)->where(array('supply_id' => array('in', $supplyArr), 'type' => 0))->select();
        if (empty($supplys)) {
            exit(json_encode(array('errcode' => true, 'msg' => '不存在的数据')));
        }
        $nowTime = time();
        foreach ($supplys as $sup) {
            if ($sup['status'] == 1) {
                $save_data = array('uid' => $uid, 'status' => 2, 'start_time' => $nowTime, 'get_type' => 1);
                $result = D('Deliver_supply')->where(array('supply_id' => $sup['supply_id']))->save($save_data);
                if ($sup['item'] == 0) {
                    $result = D("Meal_order")->where(array('order_id' => $sup['order_id']))->data(array('order_status' => 8))->save();
                } elseif ($sup['item'] == 2) {
                    $deliver_info = serialize(array('uid' => $user['uid'], 'name' => $user['name'], 'phone' => $user['phone'], 'store_id' => $user['store_id']));
                    $result = D("Shop_order")->where(array('order_id' => $sup['order_id']))->data(array('order_status' => 2, 'deliver_info' => $deliver_info))->save();
                    D('Shop_order_log')->add_log(array('order_id' => $sup['order_id'], 'from_type' => 1, 'status' => 3, 'name' => $user['name'], 'phone' => $user['phone']));
                } elseif ($sup['item'] == 3) {
                    $offer_id = D('Service_offer')->add_offer($sup['order_id'], $uid);
                    $data = array('offer_id' => $offer_id);
                    if ($sup['server_type'] != 1) {
                        $data['appoint_time'] = time() + $sup['server_time'] * 60;
                    }
                    D('Deliver_supply')->where(array('supply_id' => $sup['supply_id']))->save($data);
                }
                D('Deliver_supply')->sendNotice($user, $sup);
            }
        }
        exit(json_encode(array('errcode' => false, 'msg' => 'ok')));
    }
    
    public function changeModel()
    {
        $model = isset($_GET['model']) ? intval($_GET['model']) : 0;
        D('Config')->where("`name`='deliver_model'")->data(array('value' => $model))->save();
        S(C('now_city') . 'config', null);
        exit(json_encode(array('errcode' => false, 'msg' => 'ok')));
    }
    
    public function order()
    {
        $area_id = I('area_id', 0);
        
        if ($area_id) {
            $area = D('Area')->field('area_id, area_pid')->where(array('area_id' => $area_id))->find();
            $data['area_id'] = $area_id;
            $city_id = $area['area_pid'];
            $area = D('Area')->field('area_id, area_pid')->where(array('area_id' => $city_id))->find();
            $province_id = $area['area_pid'];
        }
        
        
        $sql = "SELECT s.`supply_id`, s.order_id, s.item, s.name as username, s.phone as userphone, m.name as storename, s.money, u.name, u.phone, s.start_time, s.end_time, s.aim_site, s.pay_type, s.paid, s.status, s.deliver_cash, s.distance, s.from_lat, s.aim_lat, s.from_lnt, s.aim_lnt, s.get_type, s.server_type,s.order_from FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid";
        $sql_count = "SELECT count(1) AS count,sum(s.freight_charge) AS total_freight,sum(s.money) AS total_money,sum(s.tip_price) AS tip_price FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid";
        
        $sql .= ' WHERE s.type=0 AND s.status> 1 AND s.status<5 AND s.province_id=' . $province_id . ' AND s.city_id=' . $city_id . ' AND s.area_id=' . $area_id;
        $sql_count .= ' WHERE s.type=0 AND s.status> 1 AND s.status<5 AND s.province_id=' . $province_id . ' AND s.city_id=' . $city_id . ' AND s.area_id=' . $area_id;

        import('@.ORG.system_page');
        $res_count = D()->query($sql_count);
        $count_order = isset($res_count[0]['count']) ? $res_count[0]['count'] : 0;
        
        $p = new Page($count_order, 20);
        $sql .= ' ORDER BY s.`supply_id` DESC LIMIT ' . $p->firstRow . ',' . $p->listRows;
        $supply_info = D()->query($sql);
        
        foreach ($supply_info as &$value) {
            $value['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
            $value['deliver_use_time'] = $value['end_time'] ? intval(($value['end_time']-$value['start_time'])/60).'分钟' : '-';
            $value['start_time'] = $value['start_time'] ? date("Y-m-d H:i:s", $value['start_time']) : '-';
            $value['end_time'] = $value['end_time'] ? date("Y-m-d H:i:s", $value['end_time']) : '-';
            
            $value['paid'] = $value['paid'] == 1 ? "已支付" : "未支付";
            $value['pay_type'] = $value['pay_type'] == "offline" ? "线下支付" : "线上支付";
            $value['distance'] = $value['distance'] ? $value['distance'] . 'km' : getRange(getDistance($value['from_lat'], $value['from_lnt'], $value['aim_lat'], $value['aim_lnt']));
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
                    $value['order_status'] = "已完成";
                    break;
                case 6:
                    $value['order_status'] = "已评价";
                    break;
                default:
                    $value['order_status'] = "订单失效";
                    break;
            }
        }
        
        $pagebar = $p->show();
        $this->assign(array('status' => $status, 'day' => $day, 'period' => $period, 'phone' => $phone));
        $this->assign('selectStoreId', $selectStoreId);
        $this->assign('orderNum', $orderNum);
        $this->assign('selectUserId', $selectUserId);
        $this->assign('stores', $stores);
        $this->assign('delivers', $delivers);
        $this->assign('pagebar', $pagebar);
        $this->assign('supply_info', $supply_info);
        $this->assign('res_count', $res_count);
        $this->display();
    }
    
    public function reply()
    {
        $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
        $where = ' WHERE s.type=0 AND s.status>4';
        if (! empty($keyword)) {
            if ($_GET['nameType'] == 0) {
                $where .= " AND u.name LIKE '%" . $keyword . "%'";
            } elseif ($_GET['nameType'] == 1) {
                $where .= " AND s.name LIKE '%" . $keyword . "%'";
            }
        }
        if ($this->system_session['area_id']) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if ($now_area['area_type'] == 3) {
                $where .= ' AND u.area_id=' . $this->system_session['area_id'];
            } elseif ($now_area['area_type'] == 2) {
                $where .= ' AND u.city_id=' . $this->system_session['area_id'];
            } elseif ($now_area['area_type'] == 1) {
                $where .= ' AND u.province_id=' . $this->system_session['area_id'];
            }
            $this->assign('admin_area', $now_area['area_type']);
        }
        if ($_GET['province_idss'] && $this->config['many_city']) {
            $where .= ' AND u.province_id=' . $_GET['province_idss'];
        }
        if ($_GET['city_idss']) {
            $where .= ' AND u.city_id=' . $_GET['city_idss'];
        }
        if ($_GET['area_id']) {
            $where .= ' AND u.area_id=' . $_GET['area_id'];
        }
        $select = isset($_GET['select']) ? $_GET['select'] : '';
        $stime = $etime = 0;
        $day = 0;
        $period = 0;
        if (is_numeric($select)) {
            $day = $select;
            $stime = strtotime("-{$day} day");
            $etime = time();
        } else {
            $period = $select;
            $time_array = explode('-', $select);
            $stime = strtotime($time_array[0]);
            $etime = strtotime($time_array[1]) + 86400;
        }
        if ($stime && $etime) {
            $where .= " AND s.create_time>'{$stime}' AND s.create_time<'{$etime}'";
        }
        
        
        $sql = "SELECT s.`supply_id`, s.order_id, s.item, s.name as username, s.phone as userphone, m.name as storename, s.score, u.name, u.phone, s.comment, s.comment_time, s.from_site";
        $sql .= " FROM " . C('DB_PREFIX') . "merchant_store AS m ";
        $sql .= "RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id ";
        $sql .= "LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid" . $where;
        
        $sql_count = "SELECT count(1) AS count FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid" . $where;
        
        
        $sql_tongji = "SELECT count(1) AS count, score FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid" . $where . ' group by s.score';
        

        import('@.ORG.system_page');
        $res_count = D()->query($sql_count);
        $count_order = isset($res_count[0]['count']) ? $res_count[0]['count'] : 0;
        
        $p = new Page($count_order, 20);
        $sql .= ' ORDER BY s.`supply_id` DESC LIMIT ' . $p->firstRow . ',' . $p->listRows;
        $supply_info = D()->query($sql);
        foreach ($supply_info as &$value) {
            switch ($value['score']) {
                case 0:
                    $value['score'] = '未评论';
                    break;
                case 1:
                case 2:
                    $value['score'] .= '星（差评）';
                    break;
                case 3:
                    $value['score'] .= '星（中评）';
                    break;
                case 4:
                case 5:
                    $value['score'] .= '星（好评）';
                    break;
            }
        }
        
        $pagebar = $p->show();
        $this->assign(array('day' => $day, 'period' => $period, 'keyword' => $keyword));
        
        $tempList = D()->query($sql_tongji);
        
        $counts = array('commentScore0' => 0, 'commentScore1' => 0, 'commentScore2' => 0, 'commentScore3' => 0, 'count' => $count_order);
        $totalScore = 0;
        $total = 0;
        foreach ($tempList as $temp) {
            $totalScore += $temp['score'] * $temp['count'];
            if ($temp['score'] > 0) {
                $total += $temp['count'];
            }
            switch ($temp['score']) {
                case 0:
                    $counts['commentScore0'] += $temp['count'];
                    break;
                case 1:
                case 2:
                    $counts['commentScore1'] += $temp['count'];
                    break;
                case 3:
                    $counts['commentScore2'] += $temp['count'];
                    break;
                case 4:
                case 5:
                    $counts['commentScore3'] += $temp['count'];
                    break;
            }
        }
        $counts['meanScore'] = round($totalScore / $total, 2);
        
        $counts['goodPrecent'] = round($counts['commentScore3'] / $total * 100, 2) . '%';
        
        $this->assign('pagebar', $pagebar);
        $this->assign('supply_info', $supply_info);
        $this->assign($counts);
        $this->display();
    }
    
    
    
    public function exportComment()
    {
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '配送员评论列表';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);
        
        $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
        $where = ' WHERE s.type=0 AND s.status>4';
        if (! empty($keyword)) {
            if ($_GET['nameType'] == 0) {
                $where .= " AND u.name LIKE '%" . $keyword . "%'";
            } elseif ($_GET['nameType'] == 1) {
                $where .= " AND s.name LIKE '%" . $keyword . "%'";
            }
        }
        if ($this->system_session['area_id']) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if ($now_area['area_type'] == 3) {
                $where .= ' AND u.area_id=' . $this->system_session['area_id'];
            } elseif ($now_area['area_type'] == 2) {
                $where .= ' AND u.city_id=' . $this->system_session['area_id'];
            } elseif ($now_area['area_type'] == 1) {
                $where .= ' AND u.province_id=' . $this->system_session['area_id'];
            }
            $this->assign('admin_area', $now_area['area_type']);
        }
        if ($_GET['province_idss'] && $this->config['many_city']) {
            $where .= ' AND u.province_id=' . $_GET['province_idss'];
        }
        if ($_GET['city_idss']) {
            $where .= ' AND u.city_id=' . $_GET['city_idss'];
        }
        if ($_GET['area_id']) {
            $where .= ' AND u.area_id=' . $_GET['area_id'];
        }
        
        $day = I('day', 0, 'intval');
        $period = I('period', '', 'htmlspecialchars');
        $stime = $etime = 0;
        if ($day) {
            $stime = strtotime("-{$day} day");
            $etime = time();
        }
        if ($period) {
            $time_array = explode('-', $period);
            $stime = strtotime($time_array[0]);
            $etime = strtotime($time_array[1]) + 86400;
        }
        if ($stime && $etime) {
            $where .= " AND s.create_time>'{$stime}' AND s.create_time<'{$etime}'";
        }
        
        
        $sql_count = "SELECT count(1) AS count FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid" . $where;
        $res_count = D()->query($sql_count);
        $count = isset($res_count[0]['count']) ? $res_count[0]['count'] : 0;
        
        $length = ceil($count / 1000);
        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);
            
            $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千配送订单');
            $objActSheet = $objExcel->getActiveSheet();
            
            $objActSheet->setCellValue('A1', '配送ID');
            $objActSheet->setCellValue('B1', '店铺名称');
            $objActSheet->setCellValue('C1', '配送员昵称');
            $objActSheet->setCellValue('D1', '配送员手机号');
            $objActSheet->setCellValue('E1', '评价用户昵称');
            $objActSheet->setCellValue('F1', '评价用户手机号');
            $objActSheet->setCellValue('G1', '评论时间');
            $objActSheet->setCellValue('H1', '评分');
            $objActSheet->setCellValue('I1', '评论内容');
            
            $sql = "SELECT s.`supply_id`, s.order_id, s.item, s.name as username, s.phone as userphone, m.name as storename, s.score, u.name, u.phone, s.comment, s.comment_time, s.from_site";
            $sql .= " FROM " . C('DB_PREFIX') . "merchant_store AS m ";
            $sql .= "RIGHT JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id ";
            $sql .= "LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid" . $where;
            $sql .= ' ORDER BY s.`supply_id` DESC LIMIT ' . $i * 1000 . ', 1000';
            $supply_list = D()->query($sql);

            if (!empty($supply_list)) {
                import('ORG.Net.IpLocation');
                $IpLocation = new IpLocation();
                $index = 2;
                foreach ($supply_list as $value) {
                    $objActSheet->setCellValueExplicit('A' . $index, $value['supply_id']);
                    if ($value['item'] == 3) {
                        $objActSheet->setCellValueExplicit('B' . $index, $value['from_site']);
                    } else {
                        $objActSheet->setCellValueExplicit('B' . $index, $value['storename']);
                    }
                    $objActSheet->setCellValueExplicit('C' . $index, $value['name']);
                    $objActSheet->setCellValueExplicit('D' . $index, $value['phone']);
                    $objActSheet->setCellValueExplicit('E' . $index, $value['username'] . ' ');
                    $objActSheet->setCellValueExplicit('F' . $index, $value['userphone']);
                    if ($value['comment_time'] > 0) {
                        $objActSheet->setCellValueExplicit('G' . $index, date('Y-m-d H:i:s', $value['comment_time']));
                    } else {
                        $objActSheet->setCellValueExplicit('G' . $index, '--');
                    }
                    switch ($value['score']) {
                        case 0:
                            $value['score'] = '未评论';
                            break;
                        case 1:
                        case 2:
                            $value['score'] .= '星（差评）';
                            break;
                        case 3:
                            $value['score'] .= '星（中评）';
                            break;
                        case 4:
                        case 5:
                            $value['score'] .= '星（好评）';
                            break;
                    }
                    $objActSheet->setCellValueExplicit('H' . $index, $value['score']);
                    $objActSheet->setCellValueExplicit('I' . $index, $value['comment']);
                    $index++;
                }
            }
            sleep(2);
        }
        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
        
    }
    
    /**
     * 自定义配送区域列表
     */
    public function area()
    {
        $customs = D('Deliver_custom')->field(true)->select();
        foreach ($customs as &$value) {
            $value['delivery_range_polygon'] = substr($value['delivery_range_polygon'], 9, strlen($value['delivery_range_polygon']) - 11);
            $lngLatData = explode(',', $value['delivery_range_polygon']);
            array_pop($lngLatData);
            $lngLats = array();
            foreach ($lngLatData as $lnglat) {
                $lng_lat = explode(' ', $lnglat);
                $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
            }
            $value['delivery_range_polygon'] = json_encode(array($lngLats));
        }
        $this->assign('customs', $customs);
        $this->display();
    }
    
    /**
     * 操作处理自定义配送区域
     */
    public function custom()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($custom = D('Deliver_custom')->field(true)->where(array('id' => $id))->find()) {
            if ($custom['delivery_range_polygon']) {
                $custom['delivery_range_polygon'] = substr($custom['delivery_range_polygon'], 9, strlen($custom['delivery_range_polygon']) - 11);
                $lngLatData = explode(',', $custom['delivery_range_polygon']);
                array_pop($lngLatData);
                $lngLats = array();
                foreach ($lngLatData as $lnglat) {
                    $lng_lat = explode(' ', $lnglat);
                    $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
                }
                $custom['delivery_range_polygon'] = json_encode(array($lngLats));
            }
            if ($custom['lng'] && $custom['lat']) {
                $custom['lng_lat'] = $custom['lng'] . ',' . $custom['lat'];
            }
        }
        $this->assign('custom', $custom);
        $this->display();
    }
    
    /**
     * 保存配送自定义区域
     */
    public function customSave()
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
        $delivery_range_polygon = isset($_POST['delivery_range_polygon']) ? htmlspecialchars(trim($_POST['delivery_range_polygon'])) : '';
        $lng_lat = isset($_POST['lng_lat']) ? htmlspecialchars(trim($_POST['lng_lat'])) : '';
        
        if (empty($name)) {
            $this->error('配送区域名称不能为空');
        }
        if (empty($delivery_range_polygon)) {
            $this->error('请绘制配送区域');
        }
        if (empty($lng_lat)) {
            $this->error('请绘制配送区域');
        }
        $data = array();
        $data['name'] = $name;
        
        $latLngArray = explode('|', $delivery_range_polygon);
        if (count($latLngArray) < 3) {
            $this->error('请绘制一个合理的服务范围！');
        } else {
            $latLngData = array();
            foreach ($latLngArray as $row) {
                $latLng = explode(',', $row);
                $latLngData[] = $latLng[1] . ' ' . $latLng[0];
            }
            $latLngData[] = $latLngData[0];
            $data['delivery_range_polygon'] = 'POLYGON((' . implode(',', $latLngData) . '))';
        }
        $lngLat = explode(',', $lng_lat);
        $data['lng'] = $lngLat[0];
        $data['lat'] = $lngLat[1];
        $data['last_time'] = time();
        if ($custom = D('Deliver_custom')->field(true)->where(array('id' => $id))->find()) {
            if (D('Deliver_custom')->where(array('id' => $id))->save($data)) {
                D('Merchant_store_shop')->where(array('custom_id' => $id))->save(array('delivery_range_polygon' => $data['delivery_range_polygon']));
                $this->success('修改配送区域成功');
            } else {
                $this->error('修改配送区域失败');
            }
        } else {
            if (D('Deliver_custom')->add($data)) {
                $this->success('添加配送区域成功');
            } else {
                $this->error('添加配送区域失败');
            }
        }
    }
    
    /**
     * 删除自定义配送区域
     */
    public function customDel()
    {
        $id = intval($_POST['id']);
        if (D('Deliver_custom')->where(array('id' => $id))->delete()) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败！请重试~');
        }
    }
    
    public function cancel()
    {
        $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
        $where = ' WHERE s.type=0 AND s.status>4';
        if (! empty($keyword)) {
            if ($_GET['nameType'] == 0) {
                $where .= " AND u.name LIKE '%" . $keyword . "%'";
            }
        }
        if ($this->system_session['area_id']) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if ($now_area['area_type'] == 3) {
                $where .= ' AND u.area_id=' . $this->system_session['area_id'];
            } elseif ($now_area['area_type'] == 2) {
                $where .= ' AND u.city_id=' . $this->system_session['area_id'];
            } elseif ($now_area['area_type'] == 1) {
                $where .= ' AND u.province_id=' . $this->system_session['area_id'];
            }
            $this->assign('admin_area', $now_area['area_type']);
        }
        if ($_GET['province_idss'] && $this->config['many_city']) {
            $where .= ' AND u.province_id=' . $_GET['province_idss'];
        }
        if ($_GET['city_idss']) {
            $where .= ' AND u.city_id=' . $_GET['city_idss'];
        }
        if ($_GET['area_id']) {
            $where .= ' AND u.area_id=' . $_GET['area_id'];
        }
        $select = isset($_GET['select']) ? $_GET['select'] : '';
        $stime = $etime = 0;
        $day = 0;
        $period = 0;
        if (is_numeric($select)) {
            $day = $select;
            $stime = strtotime("-{$day} day");
            $etime = time();
        } else {
            $period = $select;
            $time_array = explode('-', $select);
            $stime = strtotime($time_array[0]);
            $etime = strtotime($time_array[1]) + 86400;
        }
        if ($stime && $etime) {
            $where .= " AND s.dateline>'{$stime}' AND s.dateline<'{$etime}'";
        }
        
        $sql = "SELECT s.`supply_id`, m.name as storename, s.item, u.name, u.phone, s.order_id, s.dateline";
        $sql .= " FROM " . C('DB_PREFIX') . "merchant_store AS m ";
        $sql .= "RIGHT JOIN " . C('DB_PREFIX') . "deliver_cancel_log AS s ON m.store_id=s.store_id ";
        $sql .= "LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid" . $where;
        
        $sql_count = "SELECT count(1) AS count FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_cancel_log AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid" . $where;
        
        $sql_tongji = "SELECT count(1) AS count, score FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_cancel_log AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid" . $where . ' group by s.score';
        
        import('@.ORG.system_page');
        $res_count = D()->query($sql_count);
        $count_order = isset($res_count[0]['count']) ? $res_count[0]['count'] : 0;
        
        $p = new Page($count_order, 20);
        $sql .= ' ORDER BY s.`id` DESC LIMIT ' . $p->firstRow . ',' . $p->listRows;
        $supply_info = D()->query($sql);
        
        $pagebar = $p->show();
        $this->assign(array('day' => $day, 'period' => $period, 'keyword' => $keyword));
        
        
        $this->assign('pagebar', $pagebar);
        $this->assign('supply_info', $supply_info);
        $this->assign($counts);
        
        $this->display();
    }
    
    
    public function exportCancel()
    {
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '配送员扔回订单列表';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);
        
        $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
        $where = ' WHERE s.type=0 AND s.status>4';
        if (! empty($keyword)) {
            if ($_GET['nameType'] == 0) {
                $where .= " AND u.name LIKE '%" . $keyword . "%'";
            }
        }
        if ($this->system_session['area_id']) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if ($now_area['area_type'] == 3) {
                $where .= ' AND u.area_id=' . $this->system_session['area_id'];
            } elseif ($now_area['area_type'] == 2) {
                $where .= ' AND u.city_id=' . $this->system_session['area_id'];
            } elseif ($now_area['area_type'] == 1) {
                $where .= ' AND u.province_id=' . $this->system_session['area_id'];
            }
            $this->assign('admin_area', $now_area['area_type']);
        }
        if ($_GET['province_idss'] && $this->config['many_city']) {
            $where .= ' AND u.province_id=' . $_GET['province_idss'];
        }
        if ($_GET['city_idss']) {
            $where .= ' AND u.city_id=' . $_GET['city_idss'];
        }
        if ($_GET['area_id']) {
            $where .= ' AND u.area_id=' . $_GET['area_id'];
        }
        
        $day = I('day', 0, 'intval');
        $period = I('period', '', 'htmlspecialchars');
        $stime = $etime = 0;
        if ($day) {
            $stime = strtotime("-{$day} day");
            $etime = time();
        }
        if ($period) {
            $time_array = explode('-', $period);
            $stime = strtotime($time_array[0]);
            $etime = strtotime($time_array[1]) + 86400;
        }
        if ($stime && $etime) {
            $where .= " AND s.dateline>'{$stime}' AND s.dateline<'{$etime}'";
        }
        
        
        $sql_count = "SELECT count(1) AS count FROM " . C('DB_PREFIX') . "merchant_store AS m RIGHT JOIN " . C('DB_PREFIX') . "deliver_cancel_log AS s ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid" . $where;
        $res_count = D()->query($sql_count);
        $count = isset($res_count[0]['count']) ? $res_count[0]['count'] : 0;
        
        $length = ceil($count / 1000);
        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);
            
            $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千配送订单');
            $objActSheet = $objExcel->getActiveSheet();
            
            $objActSheet->setCellValue('A1', '配送ID');
            $objActSheet->setCellValue('B1', '店铺名称');
            $objActSheet->setCellValue('C1', '配送员昵称');
            $objActSheet->setCellValue('D1', '配送员手机号');
            $objActSheet->setCellValue('E1', '扔回时间');
            
            $sql = "SELECT s.`supply_id`, s.order_id, s.item, m.name as storename, s.score, u.name, u.phone, s.comment, s.dateline, s.from_site";
            $sql .= " FROM " . C('DB_PREFIX') . "merchant_store AS m ";
            $sql .= "RIGHT JOIN " . C('DB_PREFIX') . "deliver_cancel_log AS s ON m.store_id=s.store_id ";
            $sql .= "LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid" . $where;
            $sql .= ' ORDER BY s.`id` DESC LIMIT ' . $i * 1000 . ', 1000';
            $supply_list = D()->query($sql);
            
            if (!empty($supply_list)) {
                $index = 2;
                foreach ($supply_list as $value) {
                    $objActSheet->setCellValueExplicit('A' . $index, $value['supply_id']);
                    $objActSheet->setCellValueExplicit('B' . $index, $value['storename']);
                    $objActSheet->setCellValueExplicit('C' . $index, $value['name']);
                    $objActSheet->setCellValueExplicit('D' . $index, $value['phone']);
                    if ($value['dateline'] > 0) {
                        $objActSheet->setCellValueExplicit('E' . $index, date('Y-m-d H:i:s', $value['dateline']));
                    } else {
                        $objActSheet->setCellValueExplicit('E' . $index, '--');
                    }
                    $index++;
                }
            }
            sleep(2);
        }
        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }
}