<?php
/*
 * 订餐管理
 *
 * @  BuildTime  2014/11/18 11:21
 */

class ShopAction extends BaseAction
{
    public function index()
    {
    	$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$database_shop_category = D('Shop_category');
		$category = $database_shop_category->field(true)->where(array('cat_id' => $parentid))->find();
		$category_list = $database_shop_category->field(true)->where(array('cat_fid' => $parentid))->order('`cat_sort` DESC,`cat_id` ASC')->select();
		$this->assign('category', $category);
		$this->assign('category_list', $category_list);
		$this->assign('parentid', $parentid);
		$this->display();
    }

	public function cat_add()
	{
		$this->assign('bg_color','#F3F3F3');
		$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$this->assign('parentid', $parentid);
		$this->display();
	}
	public function cat_modify()
	{
		if(IS_POST){
			$database_shop_category = D('Shop_category');
			if ($database_shop_category->field('cat_id')->where(array('cat_url' => $_POST['cat_url']))->find()) {
			    $this->error('短标记(url):' . $_POST['cat_url'] . ',已存在');
			}
			if($database_shop_category->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function cat_edit()
	{
		$this->assign('bg_color','#F3F3F3');

		$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$database_shop_category = D('Shop_category');
		$condition_now_shop_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_shop_category->field(true)->where($condition_now_shop_category)->find();
		if (empty($now_category)) {
			$this->frame_error_tips('没有找到该分类信息！');
		}
		$this->assign('parentid', $parentid);
		$this->assign('now_category', $now_category);
		$this->display();
	}

	public function cat_amend()
	{
		if (IS_POST) {
			$database_shop_category = D('Shop_category');
			$where = array('cat_id' => $_POST['cat_id']);
			unset($_POST['cat_id']);
			if ($database_shop_category->field('cat_id')->where(array('cat_url' => $_POST['cat_url'], 'cat_id' => array('neq', $where['cat_id'])))->find()) {
			    $this->error('短标记(url):' . $_POST['cat_url'] . ',已存在');
			}
			if ($database_shop_category->where($where)->save($_POST)) {
				$this->success('编辑成功！');
			} else {
				$this->error('编辑失败！请重试~');
			}
		} else {
			$this->error('非法提交,请重新提交~');
		}
	}

	public function cat_del()
	{
		if (IS_POST) {
			$database_shop_category = D('Shop_category');
			$condition_now_shop_category['cat_id'] = intval($_POST['cat_id']);
			if ($obj = $database_shop_category->field(true)->where($condition_now_shop_category)->find()) {
				$t_list = $database_shop_category->field(true)->where(array('cat_fid' => $obj['cat_id']))->select();
				if ($t_list) {
					$this->error('该分类下有子分类，先清空子分类，再删除该分类');
				}
			}
			if ($database_shop_category->where($condition_now_shop_category)->delete()) {
				$database_shop_category_relation = D('Shop_category_relation');
				$condition_shop_category_relation['cat_id'] = intval($_POST['cat_id']);
				$database_shop_category_relation->where($condition_shop_category_relation)->delete();
				$this->success('删除成功！');
			} else {
				$this->error('删除失败！请重试~');
			}
		} else {
			$this->error('非法提交,请重新提交~');
		}
	}
	// 预约自定义表单所有字段展示
	public function cue_field(){
		$condition_now_appoint_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = M('Shop_category')->field(true)->where($condition_now_appoint_category)->find();

		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		if(!empty($now_category['cue_field'])){
			$now_category['cue_field'] = unserialize($now_category['cue_field']);
			foreach ($now_category['cue_field'] as $val){
				$sort[] = $val['sort'];
			}
			array_multisort($sort, SORT_DESC, $now_category['cue_field']);
		}
		$this->assign('now_category',$now_category);
		$this->display();
	}

	// 预约自定义表单添加字段
	public function cue_field_add(){
		$this->assign('bg_color','#F3F3F3');

		$this->display();
	}

	// 预约自定义表单添加字段 操作
	public function cue_field_modify(){
		if(IS_POST){
			$database_appoint_category = M('Shop_category');
			$condition_now_appoint_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_appoint_category->field(true)->where($condition_now_appoint_category)->find();

			if(!empty($now_category['cue_field'])){
				$cue_field = unserialize($now_category['cue_field']);
				foreach($cue_field as $key=>$value){
					if($value['name'] == $_POST['name']){
						$this->error('该填写项已经添加，请勿重复添加！');
					}
				}
			}else{
				$cue_field = array();
			}

			$post_data['name'] = $_POST['name'];
			$post_data['type'] = $_POST['type'];
			$post_data['sort'] = strval($_POST['sort']);
			$post_data['iswrite'] = $_POST['iswrite'];
			if(!empty($_POST['use_field'])){
				$post_data['use_field'] = explode(PHP_EOL, $_POST['use_field']);
			}

			array_push($cue_field,$post_data);
			$data_group_category['cue_field'] = serialize($cue_field);
			$data_group_category['cat_id'] = $now_category['cat_id'];
			if($database_appoint_category->data($data_group_category)->save()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}

	public function cue_field_del(){
		if(IS_POST){
			$database_group_category = M('Shop_category');
			$condition_now_group_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();

			if(!empty($now_category['cue_field'])){
				$cue_field = unserialize($now_category['cue_field']);
				$new_cue_field = array();

				foreach($cue_field as $key=>$value){
					if($value['name'] != $_POST['name']){
						array_push($new_cue_field,$value);
					}
				}
			}else{
				$this->error('此填写项不存在！');
			}
			$data_group_category['cue_field'] = serialize($new_cue_field);
			$data_group_category['cat_id'] = $now_category['cat_id'];
			if($database_group_category->data($data_group_category)->save()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function discount()
	{
	    $type = isset($_GET['searchstatus']) ? intval($_GET['searchstatus']) : -1;
	    $province_id = isset($_GET['province_idss']) ? intval($_GET['province_idss']) : 0;
	    $city_id = isset($_GET['city_idss']) ? intval($_GET['city_idss']) : 0;
	    $area_id = isset($_GET['area_id']) ? intval($_GET['area_id']) : 0;
	    $sort = isset($_GET['sort']) ? intval($_GET['sort']) : 0;
	    $_GET['searchstatus'] = $type;
        $begin_time =  isset($_GET['begin_time']) ? strtotime($_GET['begin_time']) : 0;
        $end_time =  isset($_GET['end_time']) ? strtotime($_GET['end_time'].' 23:59:59') : 0;
	    
	    
	    if ($this->system_session['area_id']) {
	        $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
	        if ($now_area['area_type'] == 3) {
	            $area_index = 'area_id';
	            $area_id = $this->system_session['area_id'];
	            $city_id = $now_area['area_pid'];
	            $temp_area = D('Area')->field(true)->where(array('area_id' => $city_id))->find();
	            $province_id = $temp_area['area_pid'];
	        } elseif($now_area['area_type'] == 2) {
	            $area_index = 'city_id';
	            $city_id = $this->system_session['area_id'];
	            $province_id = $now_area['area_pid'];
	        } elseif ($now_area['area_type'] == 1) {
	            $area_index = 'province_id';
	            $province_id = $this->system_session['area_id'];
	        }
	        $this->assign('admin_area', $now_area['area_type']);
	    }
	    
	    if ($province_id && $city_id && $area_id) {
	        $sql = "SELECT d.* FROM " . C('DB_PREFIX') . "shop_discount AS d LEFT JOIN " . C('DB_PREFIX') . "shop_discount_area AS a ON a.did=d.id WHERE `source`=0 AND (a.aid=" . $area_id . ' OR (d.is_area=0 AND d.use_area=0))';
	        if ($type != -1) {
	            $sql .= ' AND type=' . $type;
	        }
            $sql .= ' AND status!=4';

	        if($begin_time && $end_time){
	            $sql .= ' AND d.create_time BETWEEN '.$begin_time.' AND '.$end_time;
            }
	    } elseif ($province_id && $city_id) {
	        $ids = array($city_id);
	        $areas = D('Area')->field('area_id')->where(array('area_pid' => $city_id, 'is_open' => 1))->select();
	        foreach ($areas as $area) {
	            $ids[] = $area['area_id'];
	        }
	        $sql = "SELECT d.* FROM " . C('DB_PREFIX') . "shop_discount AS d LEFT JOIN " . C('DB_PREFIX') . "shop_discount_area AS a ON a.did=d.id WHERE `source`=0 AND (a.aid IN (" . implode(',', $ids) . ') OR (d.is_area=0 AND d.use_area=0))';
	        if ($type != -1) {
	            $sql .= ' AND type=' . $type;
	        }
	        $sql .= ' AND status!=4';

            if($begin_time && $end_time){
                $sql .= ' AND d.create_time BETWEEN '.$begin_time.' AND '.$end_time .' GROUP BY d.id';
            }
	    } elseif ($province_id) {
	        $ids = array($province_id);
	        $areas = D('Area')->field('area_id')->where(array('area_pid' => $province_id, 'is_open' => 1))->select();
	        $cityIds = array();
	        foreach ($areas as $area) {
	            $ids[] = $area['area_id'];
	            $cityIds[] = $area['area_id'];
	        }
	        if ($cityIds) {
	            $areas = D('Area')->field('area_id')->where(array('area_pid' => array('in', $cityIds), 'is_open' => 1))->select();
	            foreach ($areas as $area) {
	                $ids[] = $area['area_id'];
	            }
	        }
	        $sql = "SELECT d.* FROM " . C('DB_PREFIX') . "shop_discount AS d LEFT JOIN " . C('DB_PREFIX') . "shop_discount_area AS a ON a.did=d.id WHERE `source`=0 AND (a.aid IN (" . implode(',', $ids) . ') OR (d.is_area=0 AND d.use_area=0))';
	        if ($type != -1) {
	            $sql .= ' AND d.type=' . $type;
	        }
	        $sql .= ' AND status!=4';
            if($begin_time && $end_time){
                $sql .= ' AND d.create_time BETWEEN '.$begin_time.' AND '.$end_time.'  GROUP BY d.id';
            }
	    } else {
	        $sql = "SELECT * FROM " . C('DB_PREFIX') . "shop_discount as d WHERE `source`=0 AND status!=4";
            if($begin_time && $end_time){
                $sql .= ' AND create_time BETWEEN '.$begin_time.' AND '.$end_time;
            }
	    }
	    if ($sort) {
	        $sql .= ' ORDER BY d.id ASC';
	    } else {
	        $sql .= ' ORDER BY d.id DESC';
	    }
	    $discounts = D()->query($sql);
// 		$discounts = D('Shop_discount')->field(true)->where(array('source' => 0))->select();
		$this->assign('discount_list', $discounts);
		$this->display();
	}

	public function discount_add()
	{
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}
	public function discount_edit()
	{
		$this->assign('bg_color','#F3F3F3');
		$database_shop_discount = D('Shop_discount');
		$condition_now_shop_discount['id'] = intval($_GET['id']);
		$now_discount = $database_shop_discount->field(true)->where($condition_now_shop_discount)->find();
		if (empty($now_discount)) {
			$this->frame_error_tips('没有找到该分类信息！');
		}
		$this->assign('now_discount', $now_discount);
		$this->display();
	}

	public function discount_modify()
	{
		if (IS_POST) {
			$_POST['source'] = 0;
			if (floatval($_POST['plat_money']) || floatval($_POST['merchant_money'])) {
			    if ((floatval($_POST['plat_money']) + floatval($_POST['merchant_money'])) != floatval($_POST['reduce_money'])) {
			        $this->error('商家补贴的金额和平台补贴的金额的和必须等于优惠的金额');
			    }
			} else {
			    $_POST['plat_money'] = $_POST['reduce_money'];
			}
			if ($this->system_session['area_id']) {
			    $_POST['is_area'] = 1;
			}
			$_POST['create_time'] = time();
			if ($did = D('Shop_discount')->add($_POST)) {
			    if ($this->system_session['area_id']) {
			        $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
			        if ($now_area) {
			            D('Shop_discount_area')->add(array('did' => $did, 'level' => $now_area['area_type'], 'aid' => $this->system_session['area_id']));
			        }
			    }
			    
			    
				$this->success('添加成功！');
			} else {
				$this->error('添加失败！请重试~');
			}
		} else {
			$this->error('非法提交,请重新提交~');
		}
	}

	public function discount_amend()
	{
		if (IS_POST) {
			$database_shop_discount = D('Shop_discount');
			$where = array('id' => $_POST['id']);
			unset($_POST['id']);
			if (floatval($_POST['plat_money']) || floatval($_POST['merchant_money'])) {
			    if ((floatval($_POST['plat_money']) + floatval($_POST['merchant_money'])) != floatval($_POST['reduce_money'])) {
			        $this->error('商家补贴的金额和平台补贴的金额的和必须等于优惠的金额');
			    }
			} else {
			    $_POST['plat_money'] = $_POST['reduce_money'];
			}
			if ($database_shop_discount->where($where)->save($_POST)) {
				$this->success('编辑成功！');
			} else {
				$this->error('编辑失败！请重试~');
			}
		} else {
			$this->error('非法提交,请重新提交~');
		}
	}

    public function order()
    {
        $province_id = isset($_GET['province_idss']) ? intval($_GET['province_idss']) : 0;
        $city_id = isset($_GET['city_idss']) ? intval($_GET['city_idss']) : 0;
        $area_id = isset($_GET['area_id']) ? intval($_GET['area_id']) : 0;
        
        if ($this->system_session['area_id']) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if ($now_area['area_type'] == 3) {
                $area_index = 'area_id';
                $area_id = $this->system_session['area_id'];
                $city_id = $now_area['area_pid'];
                $temp_area = D('Area')->field(true)->where(array('area_id' => $city_id))->find();
                $province_id = $temp_area['area_pid'];
            } elseif($now_area['area_type'] == 2) {
                $area_index = 'city_id';
                $city_id = $this->system_session['area_id'];
                $province_id = $now_area['area_pid'];
            } elseif ($now_area['area_type'] == 1) {
                $area_index = 'province_id';
                $province_id = $this->system_session['area_id'];
            }
            $this->assign('admin_area', $now_area['area_type']);
        }
        
        $where_store = null;
        if (! empty($_GET['keyword']) && $_GET['searchtype'] == 's_name') {
            $where_store['name'] = array('like', '%' . $_GET['keyword'] . '%');
        }
        if ($province_id) {
            $where_store['province_id'] = $province_id;
        }
        if ($city_id) {
            $where_store['city_id'] = $city_id;
        }
        if ($area_id) {
            $where_store['area_id'] = $area_id;
        }
//         if ($this->system_session['area_id']) {
//             $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
//             if ($now_area['area_type'] == 3) {
//                 $area_index = 'area_id';
//             } elseif ($now_area['area_type'] == 2) {
//                 $area_index = 'city_id';
//             } elseif ($now_area['area_type'] == 1) {
//                 $area_index = 'province_id';
//             }
//             $where_store[$area_index] = $this->system_session['area_id'];
//         }
        
        $store_ids = array();
        $where = array('platform' => 0);
        
        $count_where = "`o`.`platform`=0 AND `o`.`paid`=1 AND `o`.`status`<>4 AND `o`.`status`<>5 AND (`o`.`pay_type`<>'offline' OR (`o`.`pay_type`='offline' AND `o`.`third_id`<>''))";
        if ($where_store) {
            $stores = D('Merchant_store')->field('store_id')->where($where_store)->select();
            foreach ($stores as $row) {
                $store_ids[] = $row['store_id'];
            }
            
            if ($store_ids) {
                $where['store_id'] = array('in', $store_ids);
                $count_where .= ' AND `o`.`store_id` IN (' . implode(',', $store_ids) . ')';
            } else {
                import('@.ORG.system_page');
                $p = new Page(0, 20);
                $this->assign('order_list', null);
                $this->assign('pagebar', $p->show());
                $this->display();
                exit();
            }
        }
        
        if (! empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'real_orderid') {
                $where['real_orderid'] = htmlspecialchars($_GET['keyword']);
                $count_where .= " AND `o`.`real_orderid`='" . htmlspecialchars($_GET['keyword']) . "'";
            } elseif ($_GET['searchtype'] == 'orderid') {
                $where['orderid'] = htmlspecialchars($_GET['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array(
                    'orderid' => $where['orderid']))->find();
                unset($where['orderid']);
                $where['order_id'] = $tmp_result['order_id'];
                $count_where .= " AND `o`.`order_id`=" . $tmp_result['order_id'];
            } elseif ($_GET['searchtype'] == 'name') {
                $where['username'] = htmlspecialchars($_GET['keyword']);
                $count_where .= " AND `o`.`username`='" . htmlspecialchars($_GET['keyword']) . "'";
            } elseif ($_GET['searchtype'] == 'phone') {
                $where['userphone'] = htmlspecialchars($_GET['keyword']);
                $count_where .= " AND `o`.`userphone`='" . htmlspecialchars($_GET['keyword']) . "'";
            } elseif ($_GET['searchtype'] == 'third_id') {
                $where['third_id'] = $_GET['keyword'];
                $count_where .= " AND `o`.`third_id`='" . htmlspecialchars($_GET['keyword']) . "'";
            }
        }
        $status = isset($_GET['status']) ? intval($_GET['status']) : - 1;
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        $pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
        if ($sort != 'DESC' && $sort != 'ASC')
            $sort = '';
        if ($type != 'price' && $type != 'pay_time')
            $type = '';
        $order_sort = '';
        if ($type && $sort) {
            $order_sort .= $type . ' ' . $sort . ',';
            $order_sort .= 'pay_time DESC';
        } else {
            $order_sort .= 'pay_time DESC';
        }
        if ($status == 100) {
            $where['paid'] = 0;
            $count_where .= " AND `o`.`paid`=0";
        } else if ($status != - 1) {
            if ($status == 2) {
                $where['status'] = array('in', array(2, 3));
                $count_where .= " AND `o`.`status` IN (2, 3)";
            } else {
                $where['status'] = $status;
                $count_where .= " AND `o`.`status`=" . $status;
            }
        }
        if ($pay_type && $pay_type != 'balance') {
            $where['pay_type'] = $pay_type;
            $count_where .= " AND `o`.`pay_type`='" . $pay_type . "'";
        } else if ($pay_type == 'balance') {
            $where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 )";
            
            $count_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )";
        }
        
        if (! empty($_GET['begin_time']) && ! empty($_GET['end_time'])) {
            if ($_GET['begin_time'] > $_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(
                strtotime($_GET['begin_time'] . " 00:00:00"),
                strtotime($_GET['end_time'] . " 23:59:59")
            );
            $where['_string'] .= ($where['_string'] ? ' AND ' : '') . " (create_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
            
            $count_where .= " AND (`o`.`create_time` BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
            // $condition_where['_string']=$time_condition;
        }
        
        $result = D("Shop_order")->get_order_list($where, $order_sort, 3, false);
        $list = isset($result['order_list']) ? $result['order_list'] : '';
        $store_ids = array();
        foreach ($list as $l) {
            $store_ids[] = $l['store_id'];
        }
        $temp = array();
        if ($store_ids) {
            $store_ids = implode(',', $store_ids);
            $sql = "SELECT `m`.`name` AS merchant_name, `s`.`name` AS store_name, `s`.`phone` AS store_phone, `s`.`store_id` FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON `s`.`mer_id`=`m`.`mer_id` WHERE `s`.`store_id` IN ($store_ids)";
            $mod = new Model();
            $res = $mod->query($sql);
            foreach ($res as $r) {
                $temp[$r['store_id']] = $r;
            }
        }
        foreach ($result['order_list'] as &$li) {
            $li['merchant_name'] = isset($temp[$li['store_id']]['merchant_name']) ? $temp[$li['store_id']]['merchant_name'] : '';
            $li['store_name'] = isset($temp[$li['store_id']]['store_name']) ? $temp[$li['store_id']]['store_name'] : '';
            $li['store_phone'] = isset($temp[$li['store_id']]['store_phone']) ? $temp[$li['store_id']]['store_phone'] : '';
        }
        $this->assign(array(
            'type' => $type,
            'sort' => $sort,
            'status' => $status,
            'pay_type' => $pay_type
        ));
        $this->assign('status_list', D('Shop_order')->status_list_admin);
        $this->assign($result);
        
        // 下单总金额, 验证消费总金额
        $field = 'sum(price) AS total_price, sum(price - card_price - merchant_balance - balance_pay - payment_money - score_deducte - coupon_price - card_give_money) AS offline_price, sum(card_price + merchant_balance + balance_pay + payment_money + score_deducte + coupon_price + card_give_money) AS online_price';
        
        // if($this->system_session['level']!=2){
        // $count_where = "paid=1 AND o.status<>4 AND o.status<>5 AND (pay_type<>'offline' OR (pay_type='offline' AND third_id<>''))";
        // $count_where = "paid=1 AND status<>4 AND status<>5 AND (pay_type<>'offline' OR (pay_type='offline' AND third_id<>''))";
        $table = array(
            C('DB_PREFIX') . 'shop_order' => 'o',
            C('DB_PREFIX') . 'merchant_store' => 's'
        );
        
        $count_where .= ' AND o.store_id = s.store_id';
        if ($this->system_session['area_id'] > 0) {
            $count_where .= ' AND s.' . $area_index . '=' . $this->system_session['area_id'];
        }
        $result_total = D('')->table($table)->field($field)->where($count_where)->select();
        
        $table[C('DB_PREFIX') . 'merchant_money_list'] = 'm';
        $count_where .= " AND o.real_orderid=m.order_id AND m.type='shop'";
        // echo $count_where;die;
        $money = D('')->table($table)->field($field)->where($count_where)->sum('m.money');
        // }else{
        // $count_where = "paid=1 AND status<>4 AND status<>5 AND (pay_type<>'offline' OR (pay_type='offline' AND third_id<>''))";
        // $result_total = D('Shop_order')->field($field)->where($count_where)->select();
        // }
        $result_total = isset($result_total[0]) ? $result_total[0] : '';
        $this->assign($result_total);
        $pay_method = D('Config')->get_pay_method('', '', 1);
        $this->assign('pay_method', $pay_method);
        $this->assign('money', $money);
        $this->display();
    }

	public function order_detail()
	{
		$this->assign('bg_color', '#F3F3F3');
		if(strlen($_GET['order_id'])>10){
			$res = M('Shop_order')->field('order_id')->where(array('real_orderid'=>$_GET['order_id']))->find();
			$_GET['order_id']=$res['order_id'];
		}

		$order = D('Shop_order')->get_order_detail(array('order_id' => intval($_GET['order_id'])));
// 		echo '<pre/>';
// 		print_r($order);die;
		if (empty($order)) {
			$this->frame_error_tips('没有找到该订单的信息！');
		}
		$supplys =  D('deliver_supply')->where(array('order_id'=>intval($_GET['order_id'])))->find();
        $supplys['confirm_time'] = $supplys['create_time'] && $supplys['order_time'] ? intval(($supplys['create_time'] - $supplys['order_time']) / 60) . '分钟' : '-';
        $supplys['grab_time'] = $supplys['start_time'] ? intval(($supplys['start_time'] - $supplys['create_time']) / 60) . '分钟' : '-';
        $supplys['deliver_use_time'] = $supplys['end_time'] ? intval(($supplys['end_time'] - $supplys['start_time']) / 60) . '分钟' : '-';
		$tempList = array();
		$times = D('Shop_order_log')->where(array('order_id'=>$_GET['order_id']))->select();
		$delivery_times = array();
		foreach($times as $time){
		    if($time['status']>3 && $time['status']<6){
                $delivery_times[] = $time;
            }
        }
        if(count($delivery_times)>0){
		    $this->assign('delivery_times',$delivery_times);
        }
		foreach($order['info'] as $v) {
		    $index = isset($v['packname']) && $v['packname'] ? $v['packname'] : 0;
		    if (isset($tempList[$index])) {
		        $tempList[$index]['list'][] = $v;
		    } else {
		        $tempList[$index] = array('name' => $v['packname'], 'list' => array($v));
		    }
		}
		if (count($tempList) == 1) {
		    $tempList[$index]['name'] = '';
		}
		$order['info'] = $tempList;
		$this->assign('store', D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find());
		$this->assign('order', $order);
		$this->assign('supplys',$supplys);

		//退款详情
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $refundList = D('Shop_order_refund')->field(true)->where(array('order_id' => $order_id, 'uid' => $order['uid']))->order('id DESC')->select();

        $sql = "SELECT g.image, d.goods_id, d.name, d.price, d.num, d.unit, d.spec, d.id, d.refund_id FROM " . C('DB_PREFIX') . "shop_goods AS g INNER JOIN " . C('DB_PREFIX') . "shop_refund_detail AS d ON g.goods_id=d.goods_id WHERE d.order_id={$order_id}";
        $list = D()->query($sql);
        $data = array();
        $goods_image_class = new goods_image();
        foreach ($list as $row) {
            $image = '';
            if(!empty($row['image'])){
                $tmp_pic_arr = explode(';', $row['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    if (empty($image)) {
                        $image = $goods_image_class->get_image_by_path($value, 's');
                        break;
                    }
                }
            }
            $row['image'] = $image;
            $data[$row['refund_id']][] = $row;
        }
        //状态(0：用户申请，1：商家同意退货，2：商家拒绝退货，3：用户重新申请，4：取消退货申请)
        $status = array('商家审核中', '已完成退货', '商家拒绝退货', '商家审核中', '取消退货申请');
        foreach ($refundList as &$refund) {
            $refund['image'] = $refund['image'] ? explode(',', $refund['image']) : '';
            $refund['goodsList'] = isset($data[$refund['id']]) ? $data[$refund['id']] : array();
            $refund['showStatus'] = $status[$refund['status']];
        }
        $this->assign('refund_list', $refundList);
		$this->display();
	}
    //修改配送状态页
	public function edit_supply_status(){
        $supply_id = $_GET['supply_id'];
        $this->assign('supply_id',$supply_id);
        $this->display();
    }

	public function shop()
	{
		$where = "s.status=1 AND s.have_shop=1 AND sh.deliver_type IN (0, 3)";//array('status' => 1);

		if(!empty($_GET['keyword'])){
			$where .= " AND s.name LIKE '%{$_GET['keyword']}%'";
		}
		if ($this->system_session['area_id']) {
			$now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
			if($now_area['area_type']==3){
				$area_index = 'area_id';
			}elseif($now_area['area_type']==2){
				$area_index = 'city_id';
			}elseif($now_area['area_type']==1){
				$area_index = 'province_id';
			}
			$where .= " AND s.{$area_index} = '{$this->system_session['area_id']}'";
		}
		$sql_count = "SELECT count(1) AS cnt FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `sh`.`store_id`=`s`.`store_id` WHERE {$where}";
		$mode = new model();
		$count = $mode->query($sql_count);
		$count = isset($count[0]['cnt']) ? $count[0]['cnt'] : 0;
		import('@.ORG.system_page');
		$p = new Page($count, 20);

		$sql = "SELECT `m`.`name` AS merchant_name, `s`.`name` AS store_name, `m`.`phone` AS merchant_phone, `s`.`phone` AS store_phone, `s`.`store_id`, `sh`.* FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `sh`.`store_id`=`s`.`store_id` WHERE {$where} ORDER BY `sh`.`delivery_radius` ASC LIMIT {$p->firstRow}, {$p->listRows}";
		$order_list = $mode->query($sql);

		$this->assign('order_list', $order_list);
		$this->assign('pagebar', $p->show());
		$this->display();
	}

	public function shop_edit()
	{
		$this->assign('bg_color','#F3F3F3');
		$database = D('Merchant_store_shop');
		$where['store_id'] = intval($_GET['store_id']);
		$now_shop = $database->field(true)->where($where)->find();

		if (empty($now_shop)) {
			$this->frame_error_tips('没有找到该店铺信息！');
		}
		if ($now_shop['delivery_range_polygon']) {
		    $now_shop['delivery_range_polygon'] = substr($now_shop['delivery_range_polygon'], 9, strlen($now_shop['delivery_range_polygon']) - 11);
		    $lngLatData = explode(',', $now_shop['delivery_range_polygon']);
		    array_pop($lngLatData);
		    $lngLats = array();
		    foreach ($lngLatData as $lnglat) {
		        $lng_lat = explode(' ', $lnglat);
		        $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
		    }
		    $now_shop['delivery_range_polygon'] = json_encode(array($lngLats));
		}
		
		$is_have_two_time = 0;
		if ($this->config['delivery_time2']) {
		    $delivery_times2 = explode('-', $this->config['delivery_time2']);
		    $start_time2 = $delivery_times2[0];
		    $stop_time2 = $delivery_times2[1];
		    if ($start_time2 != $stop_time2) {
		        $is_have_two_time = 1;
		    }
		}
		
		$is_have_three_time = 0;
		if ($this->config['delivery_time2']) {
		    $delivery_times3 = explode('-', $this->config['delivery_time3']);
		    $start_time3 = $delivery_times3[0];
		    $stop_time3 = $delivery_times3[1];
		    if ($start_time2 != $stop_time2) {
		        $is_have_three_time = 1;
		    }
		}
		
		$customs = D('Deliver_custom')->field(true)->select();
		foreach ($customs as &$value) {
		    $value['delivery_range_polygon'] = substr($value['delivery_range_polygon'], 9, strlen($value['delivery_range_polygon']) - 11);
		    $lngLatData = explode(',', $value['delivery_range_polygon']);
		    array_pop($lngLatData);
		    $lngLats = array();
		    foreach ($lngLatData as $lnglat) {
		        $lng_lat = explode(' ', $lnglat);
// 		        $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
		        $lngLats[] = $lng_lat[1] . '-' . $lng_lat[0];
		    }
		    $value['delivery_range_polygon'] = implode('|', $lngLats);
		}
		$this->assign('customs', $customs);
		
		$this->assign('is_have_two_time', $is_have_two_time);
		$this->assign('is_have_three_time', $is_have_three_time);
		$now_store = D('Merchant_store')->field(true)->where($where)->find();
		$now_shop = array_merge($now_shop, $now_store);
		$this->assign('now_shop', $now_shop);
		$this->display();
	}

	public function shop_amend()
	{
		if (IS_POST) {
			$where = array('store_id' => $_POST['id']);
			if ($shop = D('Merchant_store_shop')->field(true)->where($where)->find()) {
    			unset($_POST['id']);
    			if ($_POST['delivery_range_type'] == 1) {
    			    if ($_POST['custom_id']) {
    			        $custom = D('Deliver_custom')->field(true)->where(array('id' => intval($_POST['custom_id'])))->find();
    			        if (empty($custom)) {
    			            $this->error('请选择正确的服务范围');
    			        }
    			        $_POST['delivery_range_polygon'] = $custom['delivery_range_polygon'];
    			    } elseif ($_POST['delivery_range_polygon']) {
    			        $latLngArray = explode('|', $_POST['delivery_range_polygon']);
    			        if (count($latLngArray) < 3) {
    			            $this->error('请绘制一个合理的服务范围！');
    			        } else {
    		                $latLngData = array();
    		                foreach ($latLngArray as $row) {
    		                    $latLng = explode('-', $row);
    // 		                    $latLngData[] = array('lat' => $latLng[0], 'lng' => $latLng[1]);
    		                    $latLngData[] = $latLng[1] . ' ' . $latLng[0];//array('lat' => $latLng[0], 'lng' => $latLng[1]);
    		                }
    		                $latLngData[] = $latLngData[0];
    		                $_POST['delivery_range_polygon'] = 'POLYGON((' . implode(',', $latLngData) . '))';
    			        }
    			    } else {
    			        $this->error('请绘制您的服务范围！');
    			    }
    			    unset($_POST['delivery_radius']);
    			} else {
    			    unset($_POST['delivery_range_polygon']);
    			}
    			if ($_POST['s_send_time'] > 0) {
    			    $_POST['send_time'] = $_POST['s_send_time'];
    			} else {
    			    $_POST['send_time'] = $_POST['s_send_time'] = $this->config['deliver_send_time'];
    			}
    			$diffTime = 1;
    			if ($shop['send_time_type'] == 1) {
    			    $diffTime = 60;
    			} elseif ($shop['send_time_type'] == 2) {
    			    $diffTime = 1440;
    			} elseif ($shop['send_time_type'] == 3) {
    			    $diffTime = 1440 * 7;
    			} elseif ($shop['send_time_type'] == 4) {
    			    $diffTime = 1440 * 30;
    			}
    			$_POST['sort_time'] = $shop['work_time'] * $diffTime + $_POST['send_time'];
    			
    			if (D('Merchant_store_shop')->where($where)->save($_POST)) {
    				$this->success('编辑成功！');
    			} else {
    				$this->error('编辑失败！请重试~');
    			}
			} else {
			    $this->error('数据不正确');
			}
		} else {
			$this->error('非法提交,请重新提交~');
		}
	}

	public function refund_update(){
		$database_shop_order = D('Shop_order');
		$condition_shop_order['order_id'] = $_GET['order_id'];
		$order = $database_shop_order->field('`order_id`,`mer_id`,`paid`')->where($condition_shop_order)->find();
		if(empty($order)){
			$this->error('此订单不存在！');
		}
		$data['status'] = $order['paid'] == 1 ? 4 : 5;
		$data['last_time'] = time();
		if($database_shop_order->where($condition_shop_order)->save($data)){
		    D('Shop_order_log')->add_log(array('order_id' => $order['order_id'], 'status' => $order['paid'] == 1 ? 9 : 10, 'name' => $this->system_session['account'], 'note' => '系统管理原理员操作处理'));
			$this->success('订单状态已改为已退款！');
		}else{
			$this->error('订单状态改变失败！');
		}
	}

	public function export()
	{
		$param = $_POST;
		$param['type'] = 'shop';
		$param['rand_number'] = $_SERVER['REQUEST_TIME'];
		$param['system_session']['area_id'] = $this->system_session['area_id'] ;
		
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
		die;
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '订单信息';
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array( 'dir' => './runtime' );
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		// 设置当前的sheet

		$where_store = null;
		if(!empty($_GET['keyword']) && $_GET['searchtype'] == 's_name'){
			$where_store['name'] = array('like', '%'.$_GET['keyword'].'%');
		}

		if ($this->system_session['area_id']) {
			$now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
			if($now_area['area_type']==3){
				$area_index = 'area_id';
			}elseif($now_area['area_type']==2){
				$area_index = 'city_id';
			}elseif($now_area['area_type']==1){
				$area_index = 'province_id';
			}
			$where_store[$area_index] = $this->system_session['area_id'];
		}

		$store_ids = array();
		$where = array('platform' => 0);
		$condition_where = 'WHERE `o`.`platform`=0';
		if ($where_store) {
			$stores = D('Merchant_store')->field('store_id')->where($where_store)->select();
			foreach ($stores as $row) {
				$store_ids[] = $row['store_id'];
			}
			if ($store_ids) {
				$where['store_id'] = array('in', $store_ids);
				$condition_where .= ' AND o.store_id IN ('.implode(',',$store_ids).')';
			}
		}
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['real_orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .= ' AND o.real_orderid = "'. htmlspecialchars($_GET['keyword']).'"';
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['order_id'] = $tmp_result['order_id'];
				$condition_where .= ' AND o.order_id = '. $tmp_result['order_id'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['username'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=  ' AND o.username = "'.  htmlspecialchars($_GET['keyword']).'"';
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['userphone'] = htmlspecialchars($_GET['keyword']);
				$condition_where .= ' AND o.userphone = "'.  htmlspecialchars($_GET['keyword']).'"';
			}elseif ($_GET['searchtype'] == 'third_id') {
				$where['third_id'] =$_GET['keyword'];
				$condition_where .= ' AND o.third_id = "'.  $_GET['keyword'].'"';
			}

		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';

		if($status == 100){
			$where['paid'] = 0;
			$condition_where .= ' AND o.paid=0';
		}else if ($status != -1) {
			$where['status'] = $status;
			$condition_where .= ' AND o.status='.$status;
		}

		if($pay_type&&$pay_type!='balance'){
			$where['pay_type'] = $pay_type;
			$condition_where .= ' AND o.pay_type="'.$pay_type.'"';
		}else if($pay_type=='balance'){
			$where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 )";
			$condition_where .= ' AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )';
		}

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] =( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}

		$count = D('Shop_order')->where($where)->count();

		$length = ceil($count / 1000);
		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);
			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
			$objActSheet = $objExcel->getActiveSheet();
			$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(40);
			$objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);

			$objActSheet->setCellValue('A1', '订单编号');
			$objActSheet->setCellValue('B1', '商品名称');
			$objActSheet->setCellValue('C1', '商品进价');
			$objActSheet->setCellValue('D1', '单价');
			$objActSheet->setCellValue('E1', '单位');
			$objActSheet->setCellValue('F1', '规格/属性');
			$objActSheet->setCellValue('G1', '数量');
			$objActSheet->setCellValue('H1', '商家名称');
			$objActSheet->setCellValue('I1', '店铺名称');
			$objActSheet->setCellValue('J1', '客户姓名');
			$objActSheet->setCellValue('K1', '客户电话');
			$objActSheet->setCellValue('L1', '客户地址');
			$objActSheet->setCellValue('M1', '订单总价');
			$objActSheet->setCellValue('N1', '平台优惠');
			$objActSheet->setCellValue('O1', '商家优惠');
			$objActSheet->setCellValue('P1', '实付总价');
			$objActSheet->setCellValue('Q1', '在线支付金额');
			$objActSheet->setCellValue('R1', $this->config['deliver_name'] . '费');
			$objActSheet->setCellValue('S1', '商户配送费');
			$objActSheet->setCellValue('T1', '支付时间');
			$objActSheet->setCellValue('U1', '送达时间');
			$objActSheet->setCellValue('V1', '订单状态');
			$objActSheet->setCellValue('W1', '支付情况');

			$sql = "SELECT  o.*, m.name AS merchant_name,d.name as good_name,d.price as good_price ,d.spec,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id INNER JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";

			$result_list = D()->query($sql);

			$tmp_id = 0;
			if (!empty($result_list)) {
				$index = 1;
				foreach ($result_list as $value) {
					if($tmp_id == $value['real_orderid']){
						$objActSheet->setCellValueExplicit('A' . $index, '');
						$objActSheet->setCellValueExplicit('B' . $index, $value['good_name']);
						$objActSheet->setCellValueExplicit('C' . $index, $value['cost_price']);
						$objActSheet->setCellValueExplicit('D' . $index, $value['good_price']);
						$objActSheet->setCellValueExplicit('E' . $index, $value['unit']);
						$objActSheet->setCellValueExplicit('F' . $index, $value['spec']);
						$objActSheet->setCellValueExplicit('G' . $index, $value['good_num']);
						$objActSheet->setCellValueExplicit('H' . $index,'');
						$objActSheet->setCellValueExplicit('I' . $index,'');
						$objActSheet->setCellValueExplicit('J' . $index, '');
						$objActSheet->setCellValueExplicit('K' . $index, '');
						$objActSheet->setCellValueExplicit('L' . $index, '');
						$objActSheet->setCellValueExplicit('M' . $index, '');
						$objActSheet->setCellValueExplicit('N' . $index, '');
						$objActSheet->setCellValueExplicit('O' . $index, '');
						$objActSheet->setCellValueExplicit('P' . $index, '');
						$objActSheet->setCellValueExplicit('Q' . $index, '');
						$objActSheet->setCellValueExplicit('R' . $index, '');
						$objActSheet->setCellValueExplicit('S' . $index, '');
						$objActSheet->setCellValueExplicit('T' . $index, '');
						$objActSheet->setCellValueExplicit('U' . $index, '');
						$objActSheet->setCellValueExplicit('V' . $index, '');
						$objActSheet->setCellValueExplicit('W' . $index, '');
						$index++;
					}else{
						$index++;
						$objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
						$objActSheet->setCellValueExplicit('B' . $index, $value['good_name']);
						$objActSheet->setCellValueExplicit('C' . $index, $value['cost_price']);
						$objActSheet->setCellValueExplicit('D' . $index, $value['good_price']);
						$objActSheet->setCellValueExplicit('E' . $index, $value['unit']);
						$objActSheet->setCellValueExplicit('F' . $index, $value['spec']);
						$objActSheet->setCellValueExplicit('G' . $index, $value['good_num']);
						$objActSheet->setCellValueExplicit('H' . $index, $value['merchant_name']);
						$objActSheet->setCellValueExplicit('I' . $index, $value['store_name']);
						$objActSheet->setCellValueExplicit('J' . $index, $value['username']);
						$objActSheet->setCellValueExplicit('K' . $index, $value['userphone'] . ' ');
						$objActSheet->setCellValueExplicit('L' . $index, $value['address'] . ' ');
						$objActSheet->setCellValueExplicit('M' . $index, floatval($value['total_price']));
						$objActSheet->setCellValueExplicit('N' . $index, floatval($value['balance_reduce']));
						$objActSheet->setCellValueExplicit('O' . $index, floatval($value['merchant_reduce']));
						$objActSheet->setCellValueExplicit('P' . $index, floatval($value['price']));
						$objActSheet->setCellValueExplicit('Q' . $index, floatval($value['payment_money']));
						
						if($value['is_pick_in_store']  == 0){
							$objActSheet->setCellValueExplicit('R' . $index, floatval($value['freight_charge']));
						}else{
							$objActSheet->setCellValueExplicit('R' . $index, 0);
						}
						
						if($value['is_pick_in_store']  == 1){
							$objActSheet->setCellValueExplicit('S' . $index, floatval($value['freight_charge']));
						}else{
							$objActSheet->setCellValueExplicit('S' . $index, 0);
						}
						
						$objActSheet->setCellValueExplicit('T' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
						$objActSheet->setCellValueExplicit('U' . $index, $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '');
						$objActSheet->setCellValueExplicit('V' . $index, D('Shop_order')->status_list[$value['status']]);
						$objActSheet->setCellValueExplicit('W' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));
						$index++;
					}
					$tmp_id = $value['real_orderid'];

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
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}

	public function shop_list()
	{
		if($_GET['status'] == 0){
			$where = "s.status=1";
		}else{
			$where = "s.status=0";
		}
		$where .= " AND s.have_shop=1";//array('status' => 1);

		if(!empty($_GET['keyword'])){
			$where .= " AND s.name LIKE '%{$_GET['keyword']}%'";
		}
		if(!empty($_GET['preference_status'])){
			$where .= " AND sh.preference_status='1'";
		}
		if ($this->system_session['area_id']) {
			$now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
			if($now_area['area_type']==3){
				$area_index = 'area_id';
			}elseif($now_area['area_type']==2){
				$area_index = 'city_id';
			}elseif($now_area['area_type']==1){
				$area_index = 'province_id';
			}
			$where .= " AND s.{$area_index} = '{$this->system_session['area_id']}'";
		}
		$sql_count = "SELECT count(1) AS cnt FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `sh`.`store_id`=`s`.`store_id` WHERE {$where}";
		$mode = new model();
		$count = $mode->query($sql_count);
		$count = isset($count[0]['cnt']) ? $count[0]['cnt'] : 0;
		import('@.ORG.system_page');
		$p = new Page($count, 20);

		$sql = "SELECT `m`.`name` AS merchant_name, `s`.`name` AS store_name, `m`.`phone` AS merchant_phone, `s`.`phone` AS store_phone, `s`.`store_id`,`s`.`status`,`s`.`open_1`,`s`.`close_1`,`s`.`open_2`,`s`.`close_2`,`s`.`open_3`,`s`.`close_3`, `sh`.* FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `sh`.`store_id`=`s`.`store_id` WHERE {$where} ORDER BY `sh`.`sort` DESC, `sh`.`store_id` DESC LIMIT {$p->firstRow}, {$p->listRows}";
		$order_list = $mode->query($sql);
		
		$store['is_close'] = 1;
		$now_time = date('H:i:s');
		foreach($order_list as &$row){
			if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
				$row['time'] = '24小时营业';
				$row['is_close'] = 0;
			} else {
				$row['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
				if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
					$row['is_close'] = 0;
				}
					
				if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
					$row['time'] .= ',' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
					if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
						$row['is_close'] = 0;
					}
				}
				if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
					$row['time'] .= ',' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
					if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
						$row['is_close'] = 0;
					}
				}
			}
		}
		
		$this->assign('order_list', $order_list);
		$this->assign('pagebar', $p->show());
		$this->display();
	}
	
	public function edit_sort()
	{
	    $this->assign('bg_color','#F3F3F3');
	    $database = D('Merchant_store_shop');
	    $where['store_id'] = intval($_GET['store_id']);
	    $now_shop = $database->field(true)->where($where)->find();
	
	    if (empty($now_shop)) {
	        $this->frame_error_tips('没有找到该店铺信息！');
	    }
	    $now_store = D('Merchant_store')->field(true)->where($where)->find();
	    unset($now_store['sort']);
	    $now_shop = array_merge($now_shop, $now_store);
	    $this->assign('now_shop', $now_shop);
	    $this->display();
	}
	
	public function edit_amend()
	{
	    if (IS_POST) {
	        $store_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	        $sort = isset($_POST['sort']) ? intval($_POST['sort']) : 0;
	        $eleme_shopId= isset($_POST['eleme_shopId']) ? intval($_POST['eleme_shopId']) : 0;
			$preference_status = isset($_POST['preference_status']) ? intval($_POST['preference_status']) : 0;
			$preference_sort = isset($_POST['preference_sort']) ? intval($_POST['preference_sort']) : 0;
			$preference_reason = isset($_POST['preference_reason']) ? $_POST['preference_reason'] : '';
			$search_keywords = isset($_POST['search_keywords']) ? $_POST['search_keywords'] : '';
	        if ($eleme_shopId) {
	            $eleme_shop = M('Eleme_shop')->field(true)->where(array('shopId' => $eleme_shopId))->find();
	            if (empty($eleme_shop)) {
	                $this->error('饿了么店铺ID有误，或该店铺没有授权给平台的饿了么应用！');
	                exit;
	            }
	            if ($eleme_shop['store_id'] && $eleme_shop['store_id'] != $store_id) {
	                $this->error('该饿了么店铺已经授权给其他店铺管理了！');
	                exit;
	            }
	            $eleme_shop['store_id'] || M('Eleme_shop')->where(array('id' => $eleme_shop['id']))->save(array('store_id' => $store_id));
	            M('Merchant_store')->where(array('store_id' => $store_id))->save(array('eleme_shopId' => $eleme_shopId));
	        }
            if ($store_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find()) {
				//如果优选，必须商家上传正方形LOGO
				if($preference_status){
					$now_store = M('Merchant_store')->field('`mer_id`')->where(array('store_id' => $store_id))->find();
					$now_merchant = M('Merchant')->field(true)->where(array('mer_id' => $now_store['mer_id']))->find();
					if(empty($now_merchant['logo'])){
						$this->error('设置为优选店铺，请先在商家后台上传正方形LOGO');
						exit;
					}
				}
				
                $virtual_sale_count = isset($_POST['virtual_sale_count']) ? intval($_POST['virtual_sale_count']) : 0;//虚拟销量
                $sale_count = $store_shop['sale_count'] - $store_shop['virtual_sale_count'] + $virtual_sale_count;
                if (D('Merchant_store_shop')->where(array('store_id' => $store_id))->save(array('sort' => $sort, 'sale_count' => $sale_count, 'virtual_sale_count' => $virtual_sale_count,'preference_status'=>$preference_status,'preference_sort'=>$preference_sort,'preference_reason'=>$preference_reason,'search_keywords'=>$search_keywords, 'last_time' => time()))) {
                    $this->success('编辑成功！');
                } else {
                    $this->error('编辑失败！请重试~');
                }
            } else {
                $this->error('店铺信息错误！');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }
	
    
    public function sys_sort()
    {
        $systemGoodsSort = D('System_goods_sort');
        $count= $systemGoodsSort->where(array('status' => 1))->count();
        import('@.ORG.system_page');
        $p = new Page($count, 20);
        $category_list = $systemGoodsSort->field(true)->order('`sort` DESC, `sort_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('category_list', $category_list);
        $this->assign('pagebar', $p->show());
        $this->display();
    }
    
    public function sort_add()
    {
        $this->assign('bg_color','#F3F3F3');
        $this->display();
    }
    public function sort_modify()
    {
        if(IS_POST){
            $systemGoodsSort = D('System_goods_sort');
            if($systemGoodsSort->data($_POST)->add()){
                $this->success('添加成功！');
            }else{
                $this->error('添加失败！请重试~');
            }
        }else{
            $this->error('非法提交,请重新提交~');
        }
    }
    public function sort_edit()
    {
        $this->assign('bg_color','#F3F3F3');
        
        $systemGoodsSort = D('System_goods_sort');
        $where['sort_id'] = intval($_GET['sort_id']);
        $now_sort = $systemGoodsSort->field(true)->where($where)->find();
        if (empty($now_sort)) {
            $this->frame_error_tips('没有找到该分类信息！');
        }
        $this->assign('now_sort', $now_sort);
        $this->display();
    }
    
    public function sort_amend()
    {
        if (IS_POST) {
            $systemGoodsSort= D('System_goods_sort');
            $where = array('sort_id' => intval($_POST['sort_id']));
            unset($_POST['sort_id']);
            if ($systemGoodsSort->where($where)->save($_POST)) {
                $this->success('编辑成功！');
            } else {
                $this->error('编辑失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }
    
    public function sort_del()
    {
        if (IS_POST) {
            $systemGoodsSort= D('System_goods_sort');
            $where['sort_id'] = intval($_POST['sort_id']);
            if ($obj = $systemGoodsSort->field(true)->where($where)->find()) {
                $t_list = D('System_goods')->field(true)->where($where)->select();
                if ($t_list) {
                    $this->error('该分类下有商品，先清空商品后才可以删除！');
                }
            }
            if ($systemGoodsSort->where($where)->delete()) {
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }
    
    public function sys_goods()
    {
        $systemGoodsSort= D('System_goods_sort');
        $sort_list = $systemGoodsSort->field(true)->where(array('status' => 1))->order('`sort_id` DESC')->select();
        $list = array();
        foreach ($sort_list as $row) {
            $list[$row['sort_id']] = $row;
        }
        $this->assign('sort_list', $sort_list);
        $where = array('status' => 1);
        if(!empty($_GET['keyword'])){
            $where['name'] = array('like', '%' . htmlspecialchars(trim($_GET['keyword'])) . '%');
        }
        
        if (intval($_GET['sort_id'])) {
            $where['sort_id'] = intval($_GET['sort_id']);
        }
        
        $systemGoods = D('System_goods');
        $count= $systemGoods->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 20);
        $goods_list = $systemGoods->field(true)->where($where)->order('`goods_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $goods_image_class = new goods_image();
        foreach ($goods_list as &$goods) {
            $goods['sort_name'] = isset($list[$goods['sort_id']]['name']) ? $list[$goods['sort_id']]['name'] : '';
            if(!empty($goods['image'])){
                $tmp_pic_arr = explode(';', $goods['image']);
                $image = '';
                foreach ($tmp_pic_arr as $key => $value) {
                    if (empty($image)) $image = $goods_image_class->get_image_by_path($value, 's');
                }
                $goods['image'] = $image;
            }
        }
        $this->assign('goods_list', $goods_list);
        $this->assign('pagebar', $p->show());
        $this->display();
    }

    public function goods_add()
    {
        $this->assign('bg_color', '#F3F3F3');
        $systemGoodsSort= D('System_goods_sort');
        $sort_list = $systemGoodsSort->field(true)->where(array('status' => 1))->order('`sort_id` DESC')->select();
        $this->assign('sort_list', $sort_list);
        $this->display();
    }

    public function goods_modify()
    {
        $param = array('size' => $this->config['group_pic_size']);
        $param['thumb'] = true;
        $param['imageClassPath'] = 'ORG.Util.Image';
        $param['thumbPrefix'] = 'm_,s_';
        $param['thumbMaxWidth'] = '900,450';
        $param['thumbMaxHeight'] = '500,250';
        $param['thumbRemoveOrigin'] = false;
        
        $image = D('Image')->handle($this->system_session['id'], 'sysgoods', 0, $param, false);
        if (! $image['error']) {
            $_POST = array_merge($_POST, $image['title']);
        } else {
            $this->frame_submit_tips(0, $image['msg']);
        }
        $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
        $systemGoods = D('System_goods');
        $temp = $systemGoods->field(true)->where(array('number' => $_POST['number']))->find();
        if ($temp) {
            $this->frame_submit_tips(0, '商品的条形码已存在，请不要重复');
        }
        if ($id = $systemGoods->data($_POST)->add()) {
            D('Image')->update_table_id('/upload/sysgoods/' . $_POST['image'], $id, 'sysgoods');
            $this->frame_submit_tips(1, '添加成功！');
        } else {
            $this->frame_submit_tips(0, '添加失败！请重试~');
        }
    }

    public function goods_edit()
    {
        $this->assign('bg_color', '#F3F3F3');
        
        $systemGoods = D('System_goods');
        $condition['goods_id'] = intval($_GET['goods_id']);
        $now_goods = $systemGoods->field(true)->where($condition)->find();
        if (empty($now_goods)) {
            $this->frame_error_tips('该商品不存在！');
        }
        if(!empty($now_goods['image'])){
            $goods_image_class = new goods_image();
            $tmp_pic_arr = explode(';', $now_goods['image']);
            $image = '';
            foreach ($tmp_pic_arr as $key => $value) {
                if (empty($image)) $image = $goods_image_class->get_image_by_path($value, 's');
            }
            $now_goods['image'] = $image;
        }
        $this->assign('now_goods', $now_goods);
        
        $systemGoodsSort= D('System_goods_sort');
        $sort_list = $systemGoodsSort->field(true)->where(array('status' => 1))->order('`sort_id` DESC')->select();
        $this->assign('sort_list', $sort_list);
        $this->display();
    }

    public function goods_amend()
    {
        $systemGoods = D('System_goods');
        $condition['goods_id'] = intval($_POST['goods_id']);
        $now_goods = $systemGoods->field(true)->where($condition)->find();
        if (empty($now_goods)) {
            $this->frame_submit_tips(0, '数据有误');
        }
        $temp = $systemGoods->field(true)->where(array('number' => $_POST['number'], 'goods_id' => array('neq', intval($_POST['goods_id']))))->find();
        if ($temp) {
            $this->frame_submit_tips(0, '商品的条形码已存在，请不要重复');
        }
        if ($_FILES['image']['error'] != 4) {
            $param = array('size' => $this->config['group_pic_size']);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = '900,450';
            $param['thumbMaxHeight'] = '500,250';
            $param['thumbRemoveOrigin'] = false;
            $image = D('Image')->handle($this->system_session['id'], 'sysgoods', 0, $param, false);
            if (! $image['error']) {
                $_POST = array_merge($_POST, $image['title']);
            } else {
                $this->frame_submit_tips(0, $image['msg']);
            }
        }

        $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
        if ($systemGoods->data($_POST)->save()) {
            D('Image')->update_table_id('/upload/sysgoods/' . $_POST['image'], $_POST['goods_id'], 'sysgoods');
            if ($_POST['image']) {
//                 unlink('./upload/sysgoods/' . $now_goods['image']);
            }
            $this->frame_submit_tips(1, '编辑成功！');
        } else {
            $this->frame_submit_tips(0, '编辑失败！请重试~');
        }
    }

    public function goods_del()
    {
        $systemGoods = D('System_goods');
        $condition['goods_id'] = intval($_POST['goods_id']);
//         $now_goods = $systemGoods->field(true)->where($condition)->find();
        if ($systemGoods->where($condition)->delete()) {
//             unlink('./upload/sysgoods/' . $now_goods['image']);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！请重试~');
        }
    }
    
    public function goods_import()
    {
        if (IS_POST) {
            if ($_FILES['file']['error'] != 4) {
                set_time_limit(0);
                $upload_dir = './upload/excel/sysgoods/' . date('Ymd') . '/';
                if (! is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();
                $upload->maxSize = 10 * 1024 * 1024;
                $upload->allowExts = array('xls', 'xlsx');
                $upload->allowTypes = array(); // 允许上传的文件类型 留空不做检查
                $upload->savePath = $upload_dir;
                $upload->thumb = false;
                $upload->thumbType = 0;
                $upload->imageClassPath = '';
                $upload->thumbPrefix = '';
                $upload->saveRule = 'uniqid';
                if ($upload->upload()) {
                    $uploadList = $upload->getUploadFileInfo();
                    require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel/IOFactory.php';
                    $path = $uploadList['0']['savepath'] . $uploadList['0']['savename'];
                    $fileType = PHPExcel_IOFactory::identify($path); // 文件名自动判断文件类型
                    $objReader = PHPExcel_IOFactory::createReader($fileType);
                    $excelObj = $objReader->load($path);
                    $result = $excelObj->getActiveSheet()->toArray(null, true, true, true);
                    
                    $systemGoods = D('System_goods');
                    if (!empty($result) && is_array($result)) {
                        unset($result[1], $result[2]);
                        $last_user_id = 0;
                        $err_msg = '';
                        foreach ($result as $kk => $vv) {
                            if ($vv['A'] === null && 
                                $vv['B'] === null && 
                                $vv['C'] === null && 
                                $vv['D'] === null && 
                                $vv['E'] === null && 
                                $vv['F'] === null)
                                continue;
                            
//                             if (empty($vv['A'])) {
//                                 $err_msg = '请填写商品条形码！';
//                                 continue;
//                             }
                            if (empty($vv['B'])) {
                                $err_msg .= '第' . $kk . '行未填写商品名称！<br/>';
                                continue;
                            }
                            if (empty($vv['C'])) {
                                $err_msg .= '第' . $kk . '行未填写商品单位！<br/>';
                                continue;
                            }
                            if (empty($vv['D'])) {
                                $err_msg .= '第' . $kk . '行未填写零售价(元)！<br/>';
                                continue;
                            }
                            
                            if (empty($vv['F'])) {
                                $err_msg .= '第' . $kk . '行未填写所属分类ID！<br/>';
                                continue;
                            }
                            
                            $number = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
                            $name = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
                            $sort_id = intval(trim($vv['F']));

                            if ($number && ($systemGoods->where(array('number' => $number))->find())) {
                                $err_msg .= '第' . $kk . '行的商品条形码【' . $number. '】已存在！<br/>';
                                continue;
                            }
                            
                            if ($systemGoods->where(array('name' => $name))->find()) {
                                $err_msg .= '第' . $kk . '行的商品名【' . $name . '】已存在！<br/>';
                                continue;
                            }
                            
                            $tmpdata = array();
                            
                            $tmpdata['number'] = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
                            $tmpdata['name'] = $name;
                            $tmpdata['unit'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                            $tmpdata['price'] = htmlspecialchars(trim($vv['D']), ENT_QUOTES);
                            $tmpdata['cost_price'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
                            $tmpdata['sort_id'] = intval($sort_id);
//                             $tmpdata['image'] = htmlspecialchars(trim($vv['G']), ENT_QUOTES);
                            
                            $tmpdata['last_time'] = time();
                            
                            $thisGoodsId = $systemGoods->data($tmpdata)->add();
                            if (!$thisGoodsId) {
                                $err_msg .= '第' . $kk . '行的数据导入失败！<br/>';
                            }
                        }
                        if (empty($err_msg)) {
                            $this->frame_submit_tips(1, '导入成功');
                            exit();
                        } else {
                            $this->frame_submit_tips(0, '导入失败！原因：' . $err_msg);
                            exit();
                        }
                    }
                } else {
                    $this->frame_submit_tips(0, $upload->getErrorMsg());
                    exit();
                }
            }
            $this->frame_submit_tips(0, '文件上传失败');
            exit();
        } else {
            $this->display();
        }
    }
    
    public function unzip()
    {
        if (IS_POST) {
            if ($_FILES['file']['error'] != 4) {
                @set_time_limit(0);
                $upload_dir = './upload/sysgoods_temp/';
                if(!is_dir($upload_dir)){
                    mkdir($upload_dir, 0777, true);
                }
                
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();
                $upload->maxSize = 100*1024*1024;
                $upload->allowExts = array('zip');
                $upload->savePath = $upload_dir;
                $upload->saveRule = 'uniqid';
                if(!$upload->upload()){
                    $this->frame_submit_tips(0, '文件上传失败！错误提示：' . $upload->getErrorMsg());
                    exit;
                }
                $uploadList = $upload->getUploadFileInfo();
                $localZipName = $uploadList['0']['savepath'] . $uploadList['0']['savename'];
                
//                 $zipFileName = str_replace('.zip', '', $uploadList['0']['name']);
//                 $zipFileName = iconv('GB2312', 'UTF-8', $zipFileName);
                import('@.ORG.PclZip');
                $archive = new PclZip($localZipName);
                if($archive->extract(PCLZIP_OPT_PATH, $upload_dir, PCLZIP_OPT_REPLACE_NEWER) == 0){
                    $this->frame_submit_tips(0, '文件解压失败！错误提示：'.$archive->errorInfo(true));
                }
                unlink($localZipName);
//                 $upload_dir .= $zipFileName;
//                 $upload_dir = iconv("UTF-8", "gb2312", $upload_dir);
                $handler = opendir($upload_dir);
                $systemGoodsDB = M('System_goods');
                import('ORG.Util.Image');
                while (($filename = readdir($handler)) !== false) {
//                     $encode = mb_detect_encoding($filename);
//                     if ($encode != 'UTF-8') $filename = iconv($encode, "UTF-8", $filename);
                    if ($filename != '.' && $filename != '..') {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                        $original_filename = $upload_dir. '/' . $filename;
                        
                        if (!is_dir($original_filename)) {
                            $file = pathinfo($original_filename);
                            if (!in_array(strtolower($file['extension']),array('gif','jpg','jpeg','bmp','png'))) {
                                unlink($original_filename);
                                continue;
                            }
                            
                            $number = $file['filename'];
                            if ($goods = $systemGoodsDB->field(true)->where(array('number|goods_id' => $number))->find()) {
                                $img_mer_id = sprintf("%09d", $goods['goods_id']);
                                $rand_num = 'sysgoods_' . substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
                                $toPath = "./upload/sysgoods/{$rand_num}/";
                                if(!is_dir($toPath)){
                                    mkdir($toPath, 0777, true);
                                }
                                
                                $newName = substr(md5($goods['name']), 8, 16) . '.' . $file['extension'];
//                                 if ($encode != 'UTF-8') {
//                                     rename(iconv("UTF-8", $encode, $original_filename), $toPath . $newName);
//                                 } else {
                                    rename($original_filename, $toPath . $newName);
//                                 }
                                
                                
                                $systemGoodsDB->where(array('goods_id' => $goods['goods_id']))->save(array('image' => $rand_num . ',' . $newName));
                                
                                $image = getimagesize($toPath . $newName);
                                if (false !== $image) {
                                    $thumbWidth = explode(',', '900,450');
                                    $thumbHeight = explode(',', '500,250');
                                    $thumbPrefix = explode(',', 'm_,s_');
                                    $thumbSuffix = explode(',', '');
                                    $thumbFile = explode(',', '');
                                    $thumbPath = $toPath;
                                    $thumbExt = $file['extension']; // 自定义缩略图扩展名
                                    for ($i = 0, $len = count($thumbWidth); $i < $len; $i ++) {
                                        if (!empty($thumbFile[$i])) {
                                            $thumbname = $thumbFile[$i];
                                        } else {
                                            $prefix = isset($thumbPrefix[$i]) ? $thumbPrefix[$i] : $thumbPrefix[0];
                                            $suffix = isset($thumbSuffix[$i]) ? $thumbSuffix[$i] : $thumbSuffix[0];
                                            $thumbname = $prefix . basename($toPath . $newName, '.' . $file['extension']) . $suffix;
                                        }
                                        Image::thumb($toPath . $newName, $thumbPath . $thumbname . '.' . $thumbExt, '', $thumbWidth[$i], $thumbHeight[$i], true);
                                    }
                                }
                            }
//                             if ($encode != 'UTF-8') {
//                                 unlink(iconv("UTF-8", $encode, $original_filename));
//                             } else {
                                unlink($original_filename);
//                             }
                        } else {
//                             if ($encode != 'UTF-8') {
//                                 rmdir(iconv("UTF-8", $encode, $original_filename));
//                             } else {
                                rmdir($original_filename);
//                             }
                        }
                    }
                }
//                 rmdir(iconv("UTF-8", "gb2312", $upload_dir));
                @closedir($upload_dir);
                $this->frame_submit_tips(1, '导入成功');
                exit();
            }
            $this->frame_submit_tips(0, '文件上传失败');
            exit();
        } else {
            $this->display();
        }
    }
    
    
    public function area()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($discount = D('Shop_discount')->field(true)->where(array('id' => $id))->find()) {
            if ($discount['mer_id'] || $discount['store_id']) {
                $this->error_tips('此优惠不属于平台优惠');
            }
            
            if ($discount['use_area'] == 0) {
                $this->error_tips('此优惠无需指定区域');
            }
            if (IS_POST) {
                $areaArray = $_POST['areaIds'];
                $data = array();
                foreach ($areaArray as $row) {
                    $arr = explode('-', $row);
                    if (count($arr) == 1) {
                        $data[$arr[0]] = 1;
                    } elseif (count($arr) == 2) {
                        unset($data[$arr[0]]);
                        $data[$arr[1]] = 2;
                    } elseif (count($arr) == 3) {
                        unset($data[$arr[0]], $data[$arr[1]]);
                        $data[$arr[2]] = 3;
                    }
                }
                if (empty($data)) {
                    $this->error_tips('请选择区域');
                }
                
                $discountAreaDB = D('Shop_discount_area');
                //删除已经设置的区域
                $discountAreaDB->where(array('did' => $id))->delete();
                $provinceIds = array();
                $cityIds = array();
                $areaIds = array();
                foreach ($data as $aid => $level) {
                    if ($level == 1) {
                        $provinceIds[] = $aid;
                    } elseif ($level == 2) {
                        $cityIds[] = $aid;
                    } elseif ($level == 3) {
                        $areaIds[] = $aid;
                    }
                    $discountAreaDB->add(array('did' => $id, 'aid' => $aid, 'level' => $level));
                }
                //删除已经设置的商家，但是不在新设置的区域内
                $merchantWhere = 'd.did=' . $id . ' AND m.status=1';
                $tempWhere = '';
                if ($provinceIds) {
                    $tempWhere = 'province_id NOT IN (' . implode(',', $provinceIds) . ')';
                }
                if ($cityIds) {
                    $tempWhere = empty($tempWhere) ? 'city_id NOT IN (' . implode(',', $cityIds) . ')': $tempWhere . ' AND city_id IN (' . implode(',', $cityIds) . ')';
                }
                if ($areaIds) {
                    $tempWhere = empty($tempWhere) ? 'area_id NOT IN (' . implode(',', $areaIds) . ')' : $tempWhere . ' AND area_id IN (' . implode(',', $areaIds) . ')';
                }
                if (!empty($tempWhere)) {
                    $merchantWhere .= ' AND (' . $tempWhere . ')';
                }
                $sql = "SELECT d.mer_id, d.id FROM" . C('DB_PREFIX') . 'shop_discount_merchant AS d INNER JOIN ' . C('DB_PREFIX') . 'merchant AS m ON d.mer_id=m.mer_id WHERE ' . $merchantWhere;
                $deleteList = D()->query($sql);
                $discountMerchantDB = D('Shop_discount_merchant');
                foreach ($deleteList as $del) {
                    $discountMerchantDB->where(array('id' => $del['id'], 'mer_id' => $del['mer_id']))->delete();
                }
                $this->success('设置成功');
            } else {
                $where = array('area_type' => array('lt', 4), 'is_open' => 1);
                if ($this->system_session['area_id']) {
                    $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
                    if ($now_area['area_type'] == 3) {
                        $where['area_id'] = $this->system_session['area_id'];
                    }  else {
                        $where['area_pid'] = $this->system_session['area_id'];
                    }
                }
                $areaList = D('Area')->field('area_id, area_pid, area_name')->where($where)->select();
                $list = D('Shop_discount_area')->field('aid')->where(array('did' => $id))->select();
                $areaIds = array();
                foreach ($list as $row) {
                    $areaIds[] = $row['aid'];
                }
                
                $tmpMap = array();
                foreach ($areaList as $item) {
                    $item['select'] = 0;
                    if (in_array($item['area_id'], $areaIds)) {
                        $item['select'] = 1;
                    }
                    $tmpMap[$item['area_id']] = $item;
                }
                
                $list = array();
                foreach ($areaList as $item) {
                    if (isset($tmpMap[$item['area_pid']])) {
                        $tmpMap[$item['area_pid']]['son_list'][$item['area_id']] = &$tmpMap[$item['area_id']];
                    } else {
                        $list[$item['area_id']] = &$tmpMap[$item['area_id']];
                    }
                }
                $this->assign('area_list', $list);
                $this->display();
            }
        } else {
            $this->error_tips('此优惠不存在');
        }
    }
    
    public function merchant()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($discount = D('Shop_discount')->field(true)->where(array('id' => $id))->find()) {
            if ($discount['mer_id'] || $discount['store_id']) {
                $this->error_tips('此优惠不属于平台优惠');
            }
            if ($discount['use_type'] == 0) {
                $this->error_tips('此优惠无需指定商家');
            }
            $status = isset($_GET['status']) ? intval($_GET['status']) : 0;
            
            $merchantWhere = 'm.status=1';
            
            if (! empty($_GET['keyword'])) {
                if ($_GET['searchtype'] == 'mer_id') {
                    $merchantWhere .= ' AND m.mer_id=' . $_GET['keyword'];
                } elseif ($_GET['searchtype'] == 'account') {
                    $merchantWhere .= ' AND m.account LIKE \'%' . $_GET['keyword'] . '%\'';
                } elseif ($_GET['searchtype'] == 'name') {
                    $merchantWhere .= ' AND m.name LIKE \'%' . $_GET['keyword'] . '%\'';
                } elseif ($_GET['searchtype'] == 'phone') {
                    $merchantWhere .= ' AND m.phone LIKE \'%' . $_GET['keyword'] . '%\'';
                }
            }
            
            
            if ($discount['use_area']) {
                $areaList = D('Shop_discount_area')->field(true)->where(array('did' => $id))->select();
                if (empty($areaList)) {
                    $this->error_tips('请先指定使用的区域');
                }
                $provinceIds = array();
                $cityIds = array();
                $areaIds = array();
                foreach ($areaList as $area) {
                    if ($area['level'] == 1) {
                        $provinceIds[] = $area['aid'];
                    } elseif ($area['level'] == 2) {
                        $cityIds[] = $area['aid'];
                    } elseif ($area['level'] == 3) {
                        $areaIds[] = $area['aid'];
                    }
                }
                $tempWhere = '';
                if ($provinceIds) {
                    $tempWhere = 'province_id IN (' . implode(',', $provinceIds) . ')';
                }
                if ($cityIds) {
                    $tempWhere = empty($tempWhere) ? 'city_id IN (' . implode(',', $cityIds) . ')': $tempWhere . ' OR city_id IN (' . implode(',', $cityIds) . ')';
                }
                if ($areaIds) {
                    $tempWhere = empty($tempWhere) ? 'area_id IN (' . implode(',', $areaIds) . ')' : $tempWhere . ' OR area_id IN (' . implode(',', $areaIds) . ')';
                }
                if (!empty($tempWhere)) {
                    $merchantWhere .= ' AND (' . $tempWhere . ')';
                }
            } else {
                if ($this->system_session['area_id']) {
                    $area_index = D('Area')->getIndexByAreaID($this->system_session['area_id']);
                    $merchantWhere .= ' AND ' . $area_index . '=' . $this->system_session['area_id'];
                }
            }
            if ($status != 0) {
                $list = D('Shop_discount_merchant')->field('mer_id')->where(array('did' => $id))->select();
                $merIdArr = array();
                foreach ($list as $l) {
                    $merIdArr[] = $l['mer_id'];
                }
                if ($merIdArr) {
                    if ($status == 1) {
                        $merchantWhere .= ' AND m.mer_id IN (' . implode(',', $merIdArr) . ')';
                    } else {
                        $merchantWhere .= ' AND m.mer_id NOT IN (' . implode(',', $merIdArr) . ')';
                    }
                }
            }
            
            $databaseMerchant = D('Merchant');
            $sqlCount = "SELECT count(1) AS cnt FROM " . C('DB_PREFIX') . 'merchant AS m LEFT JOIN ' . C('DB_PREFIX') . 'shop_discount_merchant AS sm ON sm.did=' . $id . ' AND m.mer_id=sm.mer_id WHERE ' . $merchantWhere;
            $countList = $databaseMerchant->query($sqlCount);
            $count = isset($countList[0]['cnt']) ? intval($countList[0]['cnt']) : 0;
//             $count = $databaseMerchant->where($condition_merchant)->count();
            import('@.ORG.system_page');
            $p = new Page($count, 15);
            $sql = "SELECT m.*, sm.id AS did FROM " . C('DB_PREFIX') . 'merchant AS m LEFT JOIN ' . C('DB_PREFIX') . 'shop_discount_merchant AS sm ON sm.did=' . $id . ' AND m.mer_id=sm.mer_id WHERE ' . $merchantWhere . ' ORDER BY m.mer_id DESC LIMIT ' . $p->firstRow . ',' . $p->listRows;
            $merchantList = $databaseMerchant->query($sql);//field(true)->where($merchantWhere)->order('mer_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
            $this->assign('merchantList', $merchantList);
            $this->assign('pagebar', $p->show());
            $this->assign('did', $id);
            $this->assign('status', $status);
            $this->display();
        } else {
            $this->error_tips('此优惠不存在');
        }
    }
    
    public function change()
    {
        $did = isset($_POST['did']) ? intval($_POST['did']) : 0;
        $merIds = isset($_POST['merIds']) ? $_POST['merIds'] : '';
        $type = isset($_POST['type']) ? intval($_POST['type']) : 0;
        if (empty($merIds)) {
            $this->error('请选择商家');
        }
        
        if ($discount = D('Shop_discount')->field(true)->where(array('id' => $did))->find()) {
            if ($discount['mer_id'] || $discount['store_id']) {
                $this->error('此优惠不属于平台优惠');
            }
            if ($discount['use_type'] == 0) {
                $this->error('此优惠无需指定商家');
            }
        }
        
        $discountMerchantDB = D('Shop_discount_merchant');
        if ($type == 0) {
            $discountMerchantDB->where(array('did' => $did, 'mer_id' => array('in', $merIds)))->delete();
            $this->success('下线成功');
        } else {
            foreach ($merIds as $mer_id) {
                if (!($discountMerchantDB->where(array('did' => $did, 'mer_id' => $mer_id))->find())) {
                    $discountMerchantDB->add(array('did' => $did, 'mer_id' => $mer_id));
                }
            }
            $this->success('上线成功');
        }
        
    }
    
    
    public function merchant_reduce()
    {
        $keyword = isset($_GET['keyword']) ? htmlspecialchars(trim($_GET['keyword'])) : '';
        $area_id = isset($_GET['area_id']) ? intval($_GET['area_id']) : 0;
        $area = D('Area')->field(true)->where(array('area_id' => $area_id))->find();
        if (empty($area) || $area['area_type'] > 3) {
            $this->error('选择城市区域');
        }
        $condition = array('status' => 1);
        $where = 'm.status=1';
        if ($area['area_type'] == 1) {
            $where .= ' AND m.province_id=' . $area_id;
            $condition['province_id'] = $area_id;
        } elseif ($area['area_type'] == 2) {
            $where .= ' AND m.city_id=' . $area_id;
            $condition['city_id'] = $area_id;
        } elseif ($area['area_type'] == 2) {
            $where .= ' AND m.area_id=' . $area_id;
            $condition['area_id'] = $area_id;
        }
        
        if(!empty($keyword)) {
            if ($_GET['searchtype'] == 'mer_id') {
                $where .= ' AND m.mer_id=' . $keyword;
                $condition['mer_id'] = $keyword;
            } else if ($_GET['searchtype'] == 'account') {
                $where .= " AND m.account LIKE '%" . $keyword . "%'";
                $condition['account'] = array('like', $keyword);
            } elseif ($_GET['searchtype'] == 'name') {
                $where .= " AND m.name LIKE '%" . $keyword . "%'";
                $condition['name'] = array('like', $keyword);
            } elseif ($_GET['searchtype'] == 'phone') {
                $where .= " AND m.phone LIKE '%" . $keyword . "%'";
                $condition['phone'] = array('like', $keyword);
            }
        }
        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time'] . " 00:00:00"), strtotime($_GET['end_time'] . " 23:59:59"));
            $where .= " AND (o.pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $this->assign('begin_time', $_GET['begin_time']);
            $this->assign('end_time', $_GET['end_time']);
        }
        
        $sql = "SELECT count(DISTINCT(m.mer_id)) AS cnt, sum(platform_merchant) as merchant_reduce, sum(platform_plat) as balance_reduce FROM " . C('DB_PREFIX') . "merchant AS m LEFT JOIN " . C('DB_PREFIX') . "shop_order AS o ON o.mer_id=m.mer_id";
        $sql .= " AND o.paid=1 AND o.status<4 WHERE " . $where;
//         $sql .= ' GROUP BY m.mer_id';
        $countResult = D()->query($sql);
        
        $count = isset($countResult[0]['cnt']) ? $countResult[0]['cnt'] : 0;
        $merchant_reduce = isset($countResult[0]['merchant_reduce']) ? floatval($countResult[0]['merchant_reduce']) : 0;
        $balance_reduce = isset($countResult[0]['balance_reduce']) ? floatval($countResult[0]['balance_reduce']) : 0;
        import('@.ORG.system_page');
        $p = new Page($count, 20);
        
        $sql = "SELECT m.mer_id, m.name, m.phone, sum(platform_merchant) as merchant_reduce, sum(platform_plat) as balance_reduce FROM " . C('DB_PREFIX') . "merchant AS m LEFT JOIN " . C('DB_PREFIX') . "shop_order AS o ON o.mer_id=m.mer_id";
        $sql .= " AND o.paid=1 AND o.status<4 WHERE " . $where;
        $sql .= ' GROUP BY m.mer_id';
        $sql .= " LIMIT {$p->firstRow}, {$p->listRows}";
        $result = D()->query($sql);
        $this->assign('list', $result);
        $this->assign(array('merchant_reduce' => $merchant_reduce, 'balance_reduce' => $balance_reduce));
        $this->assign('pagebar', $p->show());
        $this->display();
    }
    
    public function reduce_list()
    {
        $area_id = isset($_REQUEST['area_id']) ? intval($_REQUEST['area_id']) : 0;
        $mer_id = I('mer_id');
        $merchant = D('Merchant')->field(true)->where(array('mer_id'=>$mer_id))->find();
//         echo '<Pre/>';
//         print_r($merchant);die;
        if (empty($merchant)) {
            $this->error_tips('请选择商家');
        }
        $this->assign('now_merchant', $merchant);
        
        $where = array('mer_id' => $mer_id, 'paid' => 1, 'status' => array('lt', 4));
        $real_orderid = isset($_REQUEST['order_id']) ? htmlspecialchars(trim($_REQUEST['order_id'])) : '';
        $condition = ' AND o.mer_id=' . $mer_id;
        if (!empty($real_orderid)) {
            $where['real_orderid'] = $real_orderid;
            $condition .= " AND o.real_orderid='" . $real_orderid . "'";
        }
        $this->assign('order_id', $real_orderid);
        

        if(isset($_REQUEST['begin_time'])&&isset($_REQUEST['end_time'])&&!empty($_REQUEST['begin_time'])&&!empty($_REQUEST['end_time'])){
            if ($_REQUEST['begin_time']>$_REQUEST['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_REQUEST['begin_time']." 00:00:00"),strtotime($_REQUEST['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $where['_string'] = $time_condition;
            $condition .= ' AND' . $time_condition;
            $this->assign('begin_time', $_REQUEST['begin_time']);
            $this->assign('end_time', $_REQUEST['end_time']);
        }
        $count = D('Shop_order')->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 20);
        
        $sql = "SELECT s.name, o.order_id, o.num, o.real_orderid, o.platform_merchant as merchant_reduce, o.platform_plat as balance_reduce, o.price, o.pay_time, o.discount_detail FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "shop_order AS o ON o.store_id=s.store_id";
        $sql .= " WHERE o.paid=1 AND o.status<4" . $condition;
        $sql .= " ORDER BY o.order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
        $list = D()->query($sql);
        foreach ($list as &$order) {
            if ($order['discount_detail'] && @unserialize($order['discount_detail'])) {
                $details = unserialize($order['discount_detail']);
                $discount_detail = '';
                $pre = '';
                foreach ($details as $dt) {
                    if ($dt['discount_type'] == 1) {
                        $discount_detail .= $pre . '平台新单';
                    } elseif ($dt['discount_type'] == 2) {
                        $discount_detail .= $pre . '平台满减';
                    } elseif ($dt['discount_type'] == 3) {
//                         $discount_detail .= $pre . '商家新单';
                    } elseif ($dt['discount_type'] == 4) {
//                         $discount_detail .= $pre . '商家满减';
                    }
                    $pre = ',';
                }
                $order['discount_detail'] = $discount_detail;
            } else {
                $order['discount_detail'] = '';
            }
        }
        $sql = "SELECT sum(o.platform_merchant) AS merchant_reduce, sum(o.platform_plat) AS balance_reduce FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "shop_order AS o ON o.store_id=s.store_id";
        $sql .= " WHERE o.paid=1 AND o.status<4" . $condition;
        $countResult = D()->query($sql);
        
        $merchant_reduce = isset($countResult[0]['merchant_reduce']) ? floatval($countResult[0]['merchant_reduce']) : 0;
        $balance_reduce = isset($countResult[0]['balance_reduce']) ? floatval($countResult[0]['balance_reduce']) : 0;
        
        
        $this->assign('mer_id', $mer_id);
        $this->assign('area_id', $area_id);
        $this->assign('list', $list);
        $this->assign(array('merchant_reduce' => $merchant_reduce, 'balance_reduce' => $balance_reduce));
        $this->assign('pagebar',$p->show());
        $this->display();
    }
    
    public function delete_discount(){
		$id = $_POST['id'];
		M('Shop_discount')->where(array('id'=>$id))->setField('status',4);
		$this->success('删除成功');
	}

    
    public function reduce_export()
    {
        set_time_limit(0);
        $area_id = isset($_GET['area_id']) ? intval($_GET['area_id']) : 0;
        $mer_id = I('mer_id');
        $merchant = D('Merchant')->field(true)->where(array('mer_id'=>$mer_id))->find();
        if (empty($merchant)) {
            $this->error_tips('请选择商家');
        }
        
        $where = array('mer_id' => $mer_id, 'paid' => 1, 'status' => array('lt', 4));
        $real_orderid = isset($_GET['order_id']) ? htmlspecialchars(trim($_GET['order_id'])) : '';
        $condition = ' AND o.mer_id=' . $mer_id;
        if (!empty($real_orderid)) {
            $where['real_orderid'] = $real_orderid;
            $condition .= " AND o.real_orderid='" . $real_orderid . "'";
        }
        $this->assign('order_id', $real_orderid);
        
        
        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $where['_string'] = $time_condition;
            $condition .= ' AND' . $time_condition;
        }
        
        $sql = "SELECT s.name, o.order_id, o.num, o.real_orderid, o.platform_merchant as merchant_reduce, o.platform_plat as balance_reduce, o.price, o.pay_time, o.discount_detail FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "shop_order AS o ON o.store_id=s.store_id";
        $sql .= " WHERE o.paid=1 AND o.status<4" . $condition;
        $sql .= " ORDER BY o.order_id DESC";
        $list = D()->query($sql);
        
        
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = $merchant['name'] . '补贴统计详情';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);
        
        $objExcel->setActiveSheetIndex(0);
        $objExcel->getActiveSheet()->setTitle($merchant['name'] . '补贴统计详情');
        $objActSheet = $objExcel->getActiveSheet();
        $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        
        $objActSheet->setCellValue('A1', '店铺名称');
        $objActSheet->setCellValue('B1', '订单号');
        $objActSheet->setCellValue('C1', '数量');
        $objActSheet->setCellValue('D1', '优惠类型');
        $objActSheet->setCellValue('E1', '订单总额');
        $objActSheet->setCellValue('F1', '平台补贴金额');
        $objActSheet->setCellValue('G1', '商家补贴金额');
        $objActSheet->setCellValue('H1', '支付时间');
        $index = 1;
        foreach ($list as $order) {
            $index++;
            if ($order['discount_detail'] && @unserialize($order['discount_detail'])) {
                $details = unserialize($order['discount_detail']);
                $discount_detail = '';
                $pre = '';
                foreach ($details as $dt) {
                    if ($dt['discount_type'] == 1) {
                        $discount_detail .= $pre . '平台新单';
                    } elseif ($dt['discount_type'] == 2) {
                        $discount_detail .= $pre . '平台满减';
                    } elseif ($dt['discount_type'] == 3) {
//                         $discount_detail .= $pre . '商家新单';
                    } elseif ($dt['discount_type'] == 4) {
//                         $discount_detail .= $pre . '商家满减';
                    }
                    $pre = ',';
                }
                $order['discount_detail'] = $discount_detail;
            } else {
                $order['discount_detail'] = '';
            }
            
            $objActSheet->setCellValueExplicit('A' . $index, $order['name']);
            $objActSheet->setCellValueExplicit('B' . $index, $order['real_orderid']);
            $objActSheet->setCellValueExplicit('C' . $index, $order['num']);
            $objActSheet->setCellValueExplicit('D' . $index, $order['discount_detail']);
            $objActSheet->setCellValueExplicit('E' . $index, strval(($order['price'])));
            $objActSheet->setCellValueExplicit('F' . $index, strval(($order['balance_reduce'])));
            $objActSheet->setCellValueExplicit('G' . $index, strval(($order['merchant_reduce'])));
            $objActSheet->setCellValueExplicit('H' . $index, date('Y-m-d H:i:s', $order['pay_time']));
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
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }
	
	
	//快店首页配置

	public function main_page(){

		$where['type'] = $_GET['type']?$_GET['type']:$this->config['shop_main_page_center_type'];
		$slider_list = M('Shop_main_page_pic_slider')->where($where)->order('sort DESC')->select();
		$this->assign('slider_list',$slider_list);
		$this->display();
	}

	//添加团购首页中部导航图文
	public function add_shop_center_type_img(){

		if(IS_POST){
			$where['type'] = $_POST['type'];
			$count = M('Shop_main_page_pic_slider')->where($where)->count();
			if($_FILES['pic']['error'] != 4){
				$image = D('Image')->handle($this->system_session['id'], 'slider');
				if (!$image['error']) {
					$_POST = array_merge($_POST, str_replace('/upload/slider/', '', $image['url']));
				} else {
					$this->frame_submit_tips(0, $image['message']);
				}
			}
			$data['name'] = $_POST['name'];
			$data['sub_name'] = $_POST['sub_name'];
			$data['url'] = $_POST['url'];
			$data['last_time'] = $_SERVER['REQUEST_TIME'];
			$_FILES['pic']['error'] != 4 && $data['pic']    = $_POST['pic'];
			$data['sort']    = $_POST['sort'];
			$data['type']    = $_POST['type'];

			if($_POST['id']){
				$result = M('Shop_main_page_pic_slider')->where(array('id'=>$_POST['id']))->save($data);

			}else{
				if($count==abs($_POST['type'])){
					$this->error_tips(abs($_POST['type']).'图模式只能添加'.abs($_POST['type']).'个');
				}
				$result = M('Shop_main_page_pic_slider')->add($data);

			}

			if($result){
				$this->frame_submit_tips(1,'保存成功！');
			}else{
				$this->frame_submit_tips(0,'保存失败！请重试~');
			}
		}else{
			if($_GET['id']){
				$where['id'] =  $_GET['id'];
				$slider = M('Shop_main_page_pic_slider')->where($where)->find();
				$this->assign('slider',$slider);
			}
			$this->display();
		}
	}

	public function image_show(){
		$this->display();
	}
}