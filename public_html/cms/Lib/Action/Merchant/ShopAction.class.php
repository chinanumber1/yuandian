<?php

class ShopAction extends BaseAction
{
    
    const GOODS_SORT_LEVEL = 3;
	/* 店铺管理 */
	public function index()
	{
		$mer_id = $this->merchant_session['mer_id'];
		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['mer_id'] = $mer_id;
		$condition_merchant_store['have_shop'] = '1';
		$condition_merchant_store['status'] = '1';
		if ($this->merchant_session['store_id']) {
            $count_store = 1;
		} else {
            $count_store = $database_merchant_store->where($condition_merchant_store)->count();
		}
		$db_arr = array(C('DB_PREFIX') . 'area' => 'a', C('DB_PREFIX') . 'merchant_store' => 's');
		import('@.ORG.merchant_page');
		$p = new Page($count_store, 30);

		$sql = "SELECT `s`.`store_id`, `s`.`mer_id`, `s`.`name`, `s`.`adress`, `s`.`phone`, `s`.`sort`, `s`.`meituan_token`, `s`.`eleme_shopId`, `ss`.`store_theme`, `ss`.`store_id` AS sid, `ss`.`zbw_sBranchNo` FROM ". C('DB_PREFIX') . "merchant_store AS s LEFT JOIN  ". C('DB_PREFIX') . "merchant_store_shop AS ss ON `s`.`store_id`=`ss`.`store_id`";
// 		$sql .= " LEFT JOIN ". C('DB_PREFIX') . "merchant_store_zbw AS zbw ON `ss`.`store_id`=`zbw`.storeId";
		$sql .= " WHERE `s`.`mer_id`={$mer_id} AND `s`.`status`='1' AND `s`.`have_shop`='1'";
		if ($this->merchant_session['store_id']) {
		    $sql .= " AND `s`.`store_id`={$this->merchant_session['store_id']}";
		}
		$sql .= " ORDER BY `s`.`sort` DESC,`s`.`store_id` ASC";
		$sql .= " LIMIT {$p->firstRow}, {$p->listRows}";
		$store_list = D()->query($sql);
		import('@.ORG.Meituan');
		$eleme = new Meituan();
		foreach ($store_list as &$store) {
		    $store['meituan_url'] = $eleme->getAuthUrl($store['store_id'], $store['name']);
		    $store['meituan_cancel_url'] = $eleme->cancelBind($store['meituan_token']);
		}
// 		echo D()->_sql();
// 		$store_list = D()->table($db_arr)->field(true)->where("`s`.`mer_id`='$mer_id' AND `s`.`status`='1' AND `s`.`have_shop`='1' AND `s`.`area_id`=`a`.`area_id`")->order('`sort` DESC,`store_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('store_list', $store_list);

		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);

		$this->display();
	}

	/* 店铺信息修改 */
	public function shop_edit()
	{
	    if ($this->merchant_session['store_id'] && $this->merchant_session['store_id'] != $_GET['store_id']) {
	        $this->error('您没有这个权限');
	    }
		if (!empty($_SESSION['system'])) {
			$this->assign('login_system',true);
		}
		
		$now_store = $this->check_store($_GET['store_id']);
		$store_id = $now_store['store_id'];
		if(IS_POST){
		    if ($_POST['delivery_range_type'] == 1 && ($_POST['deliver_type'] == 1 || $_POST['deliver_type'] == 4)) {
		        if ($_POST['delivery_range_polygon']) {
		            $latLngArray = explode('|', $_POST['delivery_range_polygon']);
		            if (count($latLngArray) < 3) {
		                $this->error('请绘制一个合理的服务范围！');
		            } else {
		                $latLngData = array();
		                foreach ($latLngArray as $row) {
		                    $latLng = explode(',', $row);
// 		                    $latLngData[] = array('lat' => $latLng[0], 'lng' => $latLng[1]);
		                    $latLngData[] = $latLng[1] . ' ' . $latLng[0];//array('lat' => $latLng[0], 'lng' => $latLng[1]);
		                }
		                $latLngData[] = $latLngData[0];
		                $_POST['delivery_range_polygon'] = 'POLYGON((' . implode(',', $latLngData) . '))';
		                $_POST['custom_id'] = 0;
		            }
		        } else {
		            $this->error('请绘制您的服务范围！');
		        }
		        unset($_POST['delivery_radius']);
		    } else {
		        unset($_POST['delivery_range_polygon']);
		    }
			$_POST['store_id'] = $now_store['store_id'];
			if(substr($_POST['store_notice'], -1) == ' '){
				$_POST['store_notice'] = trim($_POST['store_notice']);
			}else{
				$_POST['store_notice'] = trim($_POST['store_notice']);
			}
			if(empty($_POST['store_category'])){
				$this->error('请至少选一个分类！');
			}
			$cat_ids = array();
			foreach ($_POST['store_category'] as $cat_a) {
				$a = explode('-', $cat_a);
				$cat_ids[] = array('cat_fid' => $a[0], 'cat_id' => $a[1]);
			}
			
			$leveloff = isset($_POST['leveloff']) ? $_POST['leveloff'] :false;
			unset($_POST['leveloff']);
			
			$newleveloff = array();
			if (!empty($leveloff)) {
				foreach ($leveloff as $kk => $vv) {
					$vv['type'] = intval($vv['type']);
					$vv['vv'] = intval($vv['vv']);
					if (($vv['type'] > 0) && ($vv['vv'] > 0)) {
						$vv['level'] = $kk;
						$newleveloff[$kk] = $vv;
					}
				}
			}

			$_POST['store_discount'] = intval(floatval($_POST['store_discount']) * 10);
			$_POST['store_discount'] = ($_POST['store_discount'] > 100 || $_POST['store_discount'] < 0) ? 0 : $_POST['store_discount'];
			
			$_POST['discount_type'] = isset($_POST['discount_type']) ? intval($_POST['discount_type']) : 0;
			$_POST['reduce_stock_type'] = isset($_POST['reduce_stock_type']) ? intval($_POST['reduce_stock_type']) : 0;
			$_POST['rollback_time'] = isset($_POST['rollback_time']) ? intval($_POST['rollback_time']) : 20;
			$_POST['rollback_time'] = max(10, $_POST['rollback_time']);
			$_POST['work_time'] = isset($_POST['work_time']) ? intval($_POST['work_time']) : 20;
			
// 			$_POST['virtual_sale_count'] = isset($_POST['virtual_sale_count']) ? intval($_POST['virtual_sale_count']) : 0;//虚拟销量

			$_POST['leveloff'] = !empty($newleveloff) ? serialize($newleveloff) : '';
			if($leveloff === false) unset($_POST['leveloff']);
			$database_merchant_store_shop = D('Merchant_store_shop');
			$deliver_type = isset($_POST['deliver_type']) ? intval($_POST['deliver_type']) : 0;
			//unset($_POST['deliver_type']);

			$store_shop = $database_merchant_store_shop->field(true)->where(array('store_id' => $store_id))->find();
			if ($store_shop) {
				if (in_array($store_shop['deliver_type'], array(0, 3)) && in_array($deliver_type, array(0, 3))) {//平台=>平台 配送距离不修改
					unset($_POST['delivery_radius']);
					$_POST['s_send_time'] = $store_shop['s_send_time'] ? $store_shop['s_send_time'] : $this->config['deliver_send_time'];
					$_POST['send_time'] = $_POST['s_send_time'];
				}
				if (in_array($store_shop['deliver_type'], array(1, 2, 4)) && in_array($deliver_type, array(0, 3))) {//商家=>平台 配送距离设置为0
					$_POST['delivery_radius'] = 0;
				}
                if (empty($store_shop['create_time'])) {
                    $_POST['create_time'] = time();
                } else {
                    $_POST['last_time'] = time();
                }
                
                $diffTime = 1;
                if ($_POST['send_time_type'] == 1) {
                    $diffTime = 60;
                } elseif ($_POST['send_time_type'] == 2) {
                    $diffTime = 1440;
                } elseif ($_POST['send_time_type'] == 3) {
                    $diffTime = 1440 * 7;
                } elseif ($_POST['send_time_type'] == 4) {
                    $diffTime = 1440 * 30;
                }
                //sort_time显示给用户看的，以及用于排序
                $_POST['sort_time'] = $_POST['work_time'] * $diffTime + $_POST['send_time'];
                
				//$_POST['sale_count'] = $store_shop['sale_count'] - $store_shop['virtual_sale_count'] + $_POST['virtual_sale_count'];
				$operat_shop = $database_merchant_store_shop->data($_POST)->save();
			} else {
				if ($deliver_type == 0 || $deliver_type == 3) {
					$_POST['delivery_radius'] = 0;
					$_POST['send_time'] = $_POST['s_send_time'] = $this->config['deliver_send_time'];
				} else {
				    $_POST['s_send_time'] = $this->config['deliver_send_time'];
				}
				$_POST['create_time'] = time();
// 				$_POST['sale_count'] = $_POST['virtual_sale_count'];
				
				
				$diffTime = 1;
				if ($_POST['send_time_type'] == 1) {
				    $diffTime = 60;
				} elseif ($_POST['send_time_type'] == 2) {
				    $diffTime = 1440;
				} elseif ($_POST['send_time_type'] == 3) {
				    $diffTime = 1440 * 7;
				} elseif ($_POST['send_time_type'] == 4) {
				    $diffTime = 1440 * 30;
				}
				$_POST['sort_time'] = $_POST['work_time'] * $diffTime + $_POST['send_time'];
				
				$operat_shop = $database_merchant_store_shop->add($_POST);
			}

			//处理配送
			$deliver_store = D('Deliver_store')->field(true)->where(array('store_id' => $now_store['store_id']))->find();
			if ($deliver_type != 2) {
				$t_type = ($deliver_type == 0 || $deliver_type == 3) ? 0 : 1;
				$deliver['store_id'] = $now_store['store_id'];
				$deliver['mer_id'] = $now_store['mer_id'];
				$deliver['site'] = $now_store['adress'];
				$deliver['type'] = $t_type;
				$deliver['range'] = $_POST['delivery_radius'];
				if ($deliver_store) {
					D('Deliver_store')->where(array('pigcms_id' => $deliver_store['pigcms_id']))->save($deliver);
				} else {
					D('Deliver_store')->data($deliver)->add();
				}
			} elseif ($deliver_type == 2 && $deliver_store) {
				D('Deliver_store')->field(true)->where(array('store_id' => $now_store['store_id']))->save(array('type' => 2));
			}

			$database_shop_category_relation = D('Shop_category_relation');
			$condition_shop_category_relation['store_id'] = $now_store['store_id'];
			$database_shop_category_relation->where($condition_shop_category_relation)->delete();
			foreach($cat_ids as $key => $value){
				$data_shop_category_relation[$key]['cat_id'] = $value['cat_id'];
				$data_shop_category_relation[$key]['cat_fid'] = $value['cat_fid'];
				$data_shop_category_relation[$key]['store_id'] = $now_store['store_id'];
			}
			$database_shop_category_relation->addAll($data_shop_category_relation);
			$this->success('编辑成功！');
		} else {
			$database_merchant_store_shop = D('Merchant_store_shop');
			$condition_merchant_store_shop['store_id'] = $now_store['store_id'];
			$store_shop = $database_merchant_store_shop->field(true)->where($condition_merchant_store_shop)->find();
			if ($store_shop && $store_shop['delivery_range_polygon']) {
			    $store_shop['delivery_range_polygon'] = substr($store_shop['delivery_range_polygon'], 9, strlen($store_shop['delivery_range_polygon']) - 11);
			    $lngLatData = explode(',', $store_shop['delivery_range_polygon']);
			    array_pop($lngLatData);
			    $lngLats = array();
			    foreach ($lngLatData as $lnglat) {
			        $lng_lat = explode(' ', $lnglat);
			        $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
			    }
			    $store_shop['delivery_range_polygon'] = json_encode(array($lngLats));
			}

			//所有分类
			$database_shop_category = D('Shop_category');
			$category_list = $database_shop_category->lists();
			$this->assign('category_list', $category_list);

			//此店铺有的分类
			$database_shop_category_relation = D('Shop_category_relation');
			$condition_shop_category_relation['store_id'] = $now_store['store_id'];
			$relation_list = $database_shop_category_relation->field(true)->where($condition_shop_category_relation)->select();
			$relation_array = array();
			foreach ($relation_list as $key => $value) {
				array_push($relation_array, $value['cat_id']);
			}
			 
			$store_shop['store_discount'] *= 0.1;
			$this->assign('relation_array', $relation_array);
			
			$leveloff = !empty($store_shop['leveloff']) ? unserialize($store_shop['leveloff']) :false;
			$tmparr = M('User_level')->order('id ASC')->select();
			$levelarr = array();
			if ($tmparr && $this->config['level_onoff']) {
				foreach ($tmparr as $vv) {
					if (!empty($leveloff) && isset($leveloff[$vv['level']])) {
						$vv['vv'] = $leveloff[$vv['level']]['vv'];
						$vv['type'] = $leveloff[$vv['level']]['type'];
					} else {
						$vv['vv'] = '';
						$vv['type'] = '';
					}
					$levelarr[$vv['level']] = $vv;
				}
			}
			unset($tmparr);
			$this->assign('levelarr', $levelarr);

			if ($this->config['is_open_system_deliver']) {
                $deliver_types = array(
                    array(
                        'id' => 0,
                        'name' => $this->config['deliver_name']
                    ),
                    array(
                        'id' => 1,
                        'name' => '商家配送'
                    ),
                    array(
                        'id' => 2,
                        'name' => '客户自提'
                    ),
                    array(
                        'id' => 3,
                        'name' => $this->config['deliver_name'] . '或自提'
                    ),
                    array(
                        'id' => 4,
                        'name' => '商家配送或自提'
                    ),
                    array(
                        'id' => 5,
                        'name' => '快递配送'
                    )
                );
            } else {
                $deliver_types = array(
                    array(
                        'id' => 1,
                        'name' => '商家配送'
                    ),
                    array(
                        'id' => 2,
                        'name' => '客户自提'
                    ),
                    array(
                        'id' => 4,
                        'name' => '商家配送或自提'
                    ),
                    array(
                        'id' => 5,
                        'name' => '快递配送'
                    )
                );
                $store_shop['deliver_type'] = ($store_shop['deliver_type'] == 0 || $store_shop['deliver_type'] == 3) ? 1 : $store_shop['deliver_type'];
            }
            $this->assign('deliver_types', $deliver_types);
            $this->assign('store_shop', $store_shop);
            $this->assign('now_store', $now_store);
			
			$condition['store_id']	= $now_store['store_id'];
			$subject_info = M('Merchant_store_shop_data')->where($condition)->find();
			$this->assign('subject_info',$subject_info);
			
			$this->display();
		}
	}

	/**
	 * 商品分类
	 */
    public function goods_sort()
    {
        $fid = isset($_GET['fid']) ? intval($_GET['fid']) : 0;
        $now_store = $this->check_store(intval($_GET['store_id']));
        $shopGoodsSortDB = D('Shop_goods_sort');
        $sortList = array();
        if ($sort = $shopGoodsSortDB->field(true)->where(array('store_id' => $now_store['store_id'], 'sort_id' => $fid))->find()) {
            $ids = $shopGoodsSortDB->getIds($fid, $now_store['store_id']);
            if (count($ids) > 1) {
                $sortList = $shopGoodsSortDB->field(true)->where(array('store_id' => $now_store['store_id'], 'sort_id' => array('in', $ids)))->order('sort_id ASC')->select();
            } else {
                $sortList = array($sort);
            }
        } else {
            $fid = 0;
        }
        $this->assign('now_store', $now_store);
        $this->assign('fid', $fid);
        $this->assign('sortList', $sortList);
        
        $where = array('store_id' => $now_store['store_id']);
        $where['fid'] = $fid;
        
        $sort_list = $shopGoodsSortDB->field(true)->where($where)->order('`sort` DESC,`sort_id` ASC')->select();
        foreach ($sort_list as &$value) {
            if ($now_store['is_mult_class'] == 0 && $value['operation_type'] == 2) {
                $value['operation_type'] = 0;
            }
            if ($value['week'] != null) {
                $week_arr = explode(',', $value['week']);
                $week_str = '';
                foreach ($week_arr as $k => $v) {
                    $week_str .= $this->get_week($v) . ' ';
                }
                $value['week_str'] = $week_str;
            }
        }
        $this->assign('sort_list', $sort_list);
        $this->display();
	}

	protected function get_week($num)
	{
		switch($num){
			case 1:
				return '星期一';
			case 2:
				return '星期二';
			case 3:
				return '星期三';
			case 4:
				return '星期四';
			case 5:
				return '星期五';
			case 6:
				return '星期六';
			case 0:
				return '星期日';
			default:
				return '';
		}
	}

	/**
	 * 添加商品分类
	 */
	public function sort_add()
	{
	    $fid = isset($_REQUEST['fid']) ? intval($_REQUEST['fid']) : 0;
	    $now_store = $this->check_store(intval($_GET['store_id']));
	    
	    if ($sort = M('Shop_goods_sort')->field(true)->where(array('sort_id' => $fid, 'store_id' => $now_store['store_id']))->find()) {
	        if ($now_store['is_mult_class'] == 0) {
	            $this->error($sort['sort_name'] . '店铺暂未开启多级分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
	            exit;
	        }
	        if ($sort['level'] == self::GOODS_SORT_LEVEL) {
	            $this->error($sort['sort_name'] . '分类下不能再增加子分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
	            exit;
	        }
	    } else {
	        $fid = 0;
	        $sort = null;
	    }
	    
		if (IS_POST) {
			if (empty($_POST['sort_name'])) {
				$error_tips = '分类名称必填！'.'<br/>';
			} else {
				$database_goods_sort = D('Shop_goods_sort');
				$data_goods_sort['store_id'] = $now_store['store_id'];
				$data_goods_sort['sort_name'] = htmlspecialchars(trim($_POST['sort_name']));
				$data_goods_sort['sort'] = intval($_POST['sort']);
				$data_goods_sort['sort_discount'] = intval(floatval($_POST['sort_discount']) * 10);
				$data_goods_sort['sort_discount'] = ($data_goods_sort['sort_discount'] > 100 || $data_goods_sort['sort_discount'] < 0) ? 0 : $data_goods_sort['sort_discount']; 
				$data_goods_sort['is_weekshow'] = intval($_POST['is_weekshow']);
				$data_goods_sort['print_id'] = intval($_POST['print_id']);
				$data_goods_sort['fid'] = $fid;
				$data_goods_sort['level'] = 1;
				if ($sort) {
				    $data_goods_sort['level'] = $sort['level'] + 1;
    				if (M('Shop_goods')->field(true)->where(array('sort_id' => $fid, 'store_id' => $now_store['store_id']))->find()) {
    				    $this->error($sort['sort_name'] . '分类下有归属商品了，不能给它增加子分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
    				    exit;
    				}
				}
				
				if ($data_goods_sort['level'] < self::GOODS_SORT_LEVEL) {
				    $data_goods_sort['operation_type'] = 2;
				} else {
				    $data_goods_sort['operation_type'] = 0;
				}
				if ($_POST['week']) {
					$data_goods_sort['week'] = strval(implode(',', $_POST['week']));
				}
				if ($_FILES['image']['error'] != 4) {
					$param = array('size' => $this->config['meal_pic_size']);
					$param['thumb'] = true;
					$param['imageClassPath'] = 'ORG.Util.Image';
					$param['thumbPrefix'] = 'm_,s_';
					$param['thumbMaxWidth'] = $this->config['meal_pic_width'];
					$param['thumbMaxHeight'] = $this->config['meal_pic_height'];
					$param['thumbRemoveOrigin'] = false;

					$image = D('Image')->handle($this->merchant_session['mer_id'], 'goods_sort', 1, $param);

					if ($image['error']) {
						$error_tips .= $image['msg'] . '<br/>';
					} else {
						$_POST = array_merge($_POST, $image['title']);
					}
				}
				if(!empty($_POST['image_select'])){
					$img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
					$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

					$tmp_img = explode(',',$_POST['image_select']);
					$_POST['image'] = $rand_num.','.$tmp_img[1];
				}
				$data_goods_sort['image'] = $_POST['image'] ?: '';
				if ($database_goods_sort->data($data_goods_sort)->add()) {
				    if ($sort && $sort['operation_type'] == 2) {
				        $database_goods_sort->where(array('sort_id' => $sort['sort_id']))->save(array('operation_type' => 1));
				    }
					$this->success('添加成功！！', U('Shop/goods_sort',array('store_id' => $now_store['store_id'], 'fid' => $fid)));
					die;
				} else {
				    echo $database_goods_sort->_sql();
					$this->error('添加失败！！请重试。', U('Shop/goods_sort',array('store_id' => $now_store['store_id'], 'fid' => $fid)));
					die;
				}
			}
		}
		
		$print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
		foreach ($print_list as &$l) {
		    if ($l['is_main']) {
		        $l['name'] .= '(主打印机)';
		    } else {
		        $l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
		    }
		}
		$this->assign('sort', $sort);
		$this->assign('print_list', $print_list);
		$this->assign('now_store', $now_store);
		$this->assign('fid', $fid);
		$this->display();
	}


	/**
	 * 修改商品分类
	 */
	public function sort_edit()
	{
		$now_sort = $this->check_sort(intval($_GET['sort_id']));
		$now_store = $this->check_store($now_sort['store_id']);
	    $fid = isset($_REQUEST['fid']) ? intval($_REQUEST['fid']) : 0;
	    if ($sort = M('Shop_goods_sort')->field(true)->where(array('sort_id' => $fid))->find()) {
// 	        if ($now_store['is_mult_class'] == 0) {
// 	            $this->error($sort['sort_name'] . '店铺暂未开启多级分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
// 	            exit;
// 	        }
	        if ($sort['level'] == self::GOODS_SORT_LEVEL) {
	            $this->error($sort['sort_name'] . '分类下不能再增加子分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
	            exit;
	        }
	    } else {
	        $fid = 0;
	        $sort = null;
	    }
		if (IS_POST) {
			if (empty($_POST['sort_name'])) {
				$error_tips = '分类名称必填！'.'<br/>';
			} else {
				$database_goods_sort = D('Shop_goods_sort');
				$data_goods_sort['sort_id'] = $now_sort['sort_id'];
				$data_goods_sort['sort_name'] = htmlspecialchars(trim($_POST['sort_name']));
				$data_goods_sort['sort'] = intval($_POST['sort']);
				$data_goods_sort['sort_discount'] = intval(floatval($_POST['sort_discount']) * 10);
				$data_goods_sort['sort_discount'] = ($data_goods_sort['sort_discount'] > 100 || $data_goods_sort['sort_discount'] < 0) ? 0 : $data_goods_sort['sort_discount']; 
				$data_goods_sort['is_weekshow'] = intval($_POST['is_weekshow']);
				$data_goods_sort['week'] = implode(',',$_POST['week']);
				
				$data_goods_sort['print_id'] = intval($_POST['print_id']);
				$data_goods_sort['fid'] = $fid;
				
				//$data_goods_sort['level'] = 1;
				if ($sort) {
// 				    $data_goods_sort['level'] = $sort['level'] + 1;
    				if (M('Shop_goods')->field(true)->where(array('sort_id' => $fid, 'store_id' => $now_store['store_id']))->find()) {
    				    $this->error($sort['sort_name'] . '分类下有归属商品了，不能给它增加子分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
    				    exit;
    				}
				}
				
// 				if ($data_goods_sort['level'] < self::GOODS_SORT_LEVEL) {
// 				    $data_goods_sort['operation_type'] = 2;
// 				} else {
// 				    $data_goods_sort['operation_type'] = 0;
// 				}
				
				if($_FILES['image']['error'] != 4){
					$param = array('size' => $this->config['meal_pic_size']);
					$param['thumb'] = true;
					$param['imageClassPath'] = 'ORG.Util.Image';
					$param['thumbPrefix'] = 'm_,s_';
					$param['thumbMaxWidth'] = $this->config['meal_pic_width'];
					$param['thumbMaxHeight'] = $this->config['meal_pic_height'];
					$param['thumbRemoveOrigin'] = false;

					$image = D('Image')->handle($this->merchant_session['mer_id'], 'goods_sort', 1, $param);

					if ($image['error']) {
						$error_tips .= $image['msg'] . '<br/>';
					} else {
						$_POST = array_merge($_POST, $image['title']);
					}
				}
				if(!empty($_POST['image_select'])){
					$img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
					$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

					$tmp_img = explode(',',$_POST['image_select']);
					$_POST['image']=$rand_num.','.$tmp_img[1];
				}
				if ($_POST['image']) {
					$data_goods_sort['image'] = $_POST['image'];
				}
				if ($database_goods_sort->data($data_goods_sort)->save()) {
				    if ($sort && $sort['operation_type'] == 2) {
				        $database_goods_sort->where(array('sort_id' => $sort['sort_id']))->save(array('operation_type' => 1));
				    }
					$this->success('保存成功！！', U('Shop/goods_sort',array('store_id' => $now_store['store_id'], 'fid' => $fid)));
					die;
				} else {
					$this->error('保存失败！！您是不是没做过修改？请重试。', U('Shop/goods_sort',array('store_id' => $now_store['store_id'], 'fid' => $fid)));
					die;
				}
			}
			$_POST['sort_id'] = $now_sort['sort_id'];
			$this->assign('now_sort', $_POST);
			$this->assign('ok_tips', $ok_tips);
			$this->assign('error_tips', $error_tips);
		}
		$now_sort['sort_discount'] *= 0.1;
		$this->assign('fid', $fid);
		$this->assign('now_sort', $now_sort);
		$this->assign('now_store', $now_store);
		$print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
		foreach ($print_list as &$l) {
		    if ($l['is_main']) {
		        $l['name'] .= '(主打印机)';
		    } else {
		        $l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
		    }
		}
		$this->assign('sort', $sort);
		$this->assign('print_list', $print_list);
		$this->display();
	}

	/* 分类状态 */
	public function sort_status()
	{
		$now_sort = $this->check_sort($_POST['id']);
		$now_store = $this->check_store($now_sort['store_id']);
		$database_goods_sort = D('Shop_goods_sort');
		$condition_goods_sort['sort_id'] = $now_sort['sort_id'];
		$data_goods_sort['is_weekshow'] = $_POST['type'] == 'open' ? '1' : '0';
		if ($database_goods_sort->where($condition_goods_sort)->data($data_goods_sort)->save()) {
			exit('1');
		} else {
			exit;
		}
	}

	/* 删除分类 */
	public function sort_del()
	{
		$now_sort = $this->check_sort($_GET['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);

		$count = D('Shop_goods')->where(array('sort_id' => $now_sort['sort_id'], 'store_id' => $now_sort['store_id']))->count();
		if ($count) $this->error('该分类下有商品，先删除商品后再来删除该分类');
		
		$sortCount = D('Shop_goods_sort')->where(array('fid' => $now_sort['sort_id'], 'store_id' => $now_sort['store_id']))->count();
		if ($sortCount) $this->error('该分类下有子分类，先删除子分类后再来删除该分类');
		
		$database_goods_sort = D('Shop_goods_sort');
		$condition_goods_sort['sort_id'] = $now_sort['sort_id'];
		if ($database_goods_sort->where($condition_goods_sort)->delete()) {
			$this->success('删除成功！');
		} else {
			$this->error('删除失败！');
		}
	}
	/* 分类页商品筛选 */
	public function sort_goods_search(){
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store', $now_store);

		$database_goods = D('Shop_goods');
		$condition_goods['store_id'] = $now_store['store_id'];
		if($_GET['keyword']){
			$condition_goods['name'] = array('like','%'.$_GET['keyword'].'%');
		}else{
			$this->error('请输入搜索关键词');
		}
		
		$count_goods = $database_goods->where($condition_goods)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count_goods, 20);
		$goods_list = $database_goods->field(true)->where($condition_goods)->order('`sort` DESC, `goods_id` ASC')->limit($p->firstRow . ',' . $p->listRows)->select();

		$plist = array();
		$prints = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
		foreach ($prints as $l) {
			if ($l['is_main']) {
				$l['name'] .= '(主打印机)';
			} else {
				$l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
			}
			$plist[$l['pigcms_id']] = $l;
		}
		$today = date('Ymd');
		foreach ($goods_list as &$rl) {
			$rl['print_name'] = isset($plist[$rl['print_id']]['name']) ? $plist[$rl['print_id']]['name'] : '';
			if ($rl['sell_day'] != $today) {
			    $rl['today_sell_count'] = 0;
			}
			if ($rl['stock_num'] == -1) {
			    $rl['stock_num_t'] = '无限';
			} else {
			    if ($now_store['stock_type'] == 1) {
			        $rl['stock_num_t'] = max(0, $rl['stock_num'] - $rl['sell_count']);
			    } else {
			        $rl['stock_num_t'] = max(0, $rl['stock_num'] - $rl['today_sell_count']);
			    }
			}
		}

		$this->assign('goods_list', $goods_list);
		$this->assign('pagebar', $p->show());

		$this->display();
	}
	
	/* 菜品管理 */
	public function goods_list()
	{
		$now_sort = $this->check_sort($_GET['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);
		$this->assign('now_sort', $now_sort);
		$this->assign('now_store', $now_store);

		$database_goods = D('Shop_goods');
		$condition_goods['sort_id'] = $now_sort['sort_id'];
		
		if($_GET['keyword']){
			$condition_goods['name'] = array('like','%'.$_GET['keyword'].'%');
		}
		
		$count_goods = $database_goods->where($condition_goods)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count_goods, 20);
		$goods_list = $database_goods->field(true)->where($condition_goods)->order('`sort` DESC, `goods_id` ASC')->limit($p->firstRow . ',' . $p->listRows)->select();

		$plist = array();
		$prints = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
		foreach ($prints as $l) {
			if ($l['is_main']) {
				$l['name'] .= '(主打印机)';
			} else {
				$l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
			}
			$plist[$l['pigcms_id']] = $l;
		}
		$today = date('Ymd');
		foreach ($goods_list as &$rl) {
			$rl['print_name'] = isset($plist[$rl['print_id']]['name']) ? $plist[$rl['print_id']]['name'] : '';
			if ($rl['sell_day'] != $today) {
			    $rl['today_sell_count'] = 0;
			}
			if ($rl['stock_num'] == -1) {
			    $rl['stock_num_t'] = '无限';
			} else {
			    $rl['stock_num_t'] = $rl['stock_num'];
// 			    if ($now_store['stock_type'] == 1) {
// 			        $rl['stock_num_t'] = max(0, $rl['stock_num'] - $rl['sell_count']);
// 			    } else {
// 			        $rl['stock_num_t'] = max(0, $rl['stock_num'] - $rl['today_sell_count']);
// 			    }
			}
		}
		
		$this->assign('goods_list', $goods_list);
		$this->assign('pagebar', $p->show());

		$this->display();
	}
	/* 添加店铺 */
	public function goods_add()
	{
		$now_sort = $this->check_sort(intval($_GET['sort_id']));
		$now_store = $this->check_store($now_sort['store_id']);
        if (IS_POST) {
//  			echo "<pre/>";
//  			print_r($_POST);
//  			die;
            if (!empty($_POST['sysname']) && (empty($_POST['name']) || empty($_POST['unit']) || empty($_POST['price']) || $_POST['pic'])) {
                $number = htmlspecialchars(trim($_POST['sysname']));
                $systemGoods = D('System_goods');
                $condition = array();
     
                if ($number) {
                    $condition['number'] = $number;
                }
                if (empty($condition)) {
                    $this->error('请输入查询条件');
                }
                $now_goods = $systemGoods->field(true)->where($condition)->find();
                if (empty($now_goods)) {
                    $this->error('商品不存在');
                }
                $_POST['name'] = $_POST['name'] ? $_POST['name'] : $now_goods['name'];
                $_POST['unit'] = $_POST['unit'] ? $_POST['unit'] : $now_goods['unit'];
                $_POST['price'] = isset($_POST['price']) ? $_POST['price'] : $now_goods['price'];
                $pics = array();
                if(!empty($now_goods['image'])){
                    $goods_image_class = new goods_image();
                    $tmp_pic_arr = explode(';', $now_goods['image']);
                    foreach ($tmp_pic_arr as $key => $value) {
                        $pics[] = $value;
                    }
                }
            }
            $_POST['pic'] = $_POST['pic'] ? $_POST['pic'] : $pics;
			if (empty($_POST['name'])) {
				$error_tips .= '商品名称必填！'.'<br/>';
			}
			if (empty($_POST['unit'])) {
				$error_tips .= '商品单位必填！'.'<br/>';
			}
			if ($_POST['price'] === '' && !$this->config['open_extra_price']) {
				$error_tips .= '商品价格可以设置为0，但是必填！'.'<br/>';
			}		
			if ($_POST['price'] < 0 && !$this->config['open_extra_price']) {
				$error_tips .= '商品价格必须大于或等于0！'.'<br/>';
			}
			if (empty($_POST['pic'])) {
				$error_tips .= '请至少上传一张照片！'.'<br/>';
			}
			
			if($_POST['stock_num'] >= 0 && (($_POST['seckill_stock'] > 0 && $_POST['seckill_stock'] > $_POST['stock_num']) || $_POST['seckill_stock'] == -1)){
				$error_tips .= '限时价库存不能大于商品库存！如果不做限时活动，请将限时价库存设置为0'.'<br/>';
			}

			$_POST['des'] = fulltext_filter($_POST['des']);

			$img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
			$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
			foreach($_POST['pic'] as $kp => $vp){
				$tmp_vp = explode(',', $vp);
				if (!strstr($tmp_vp[0], '/upload/')) $rand_num = $tmp_vp[0];
				$_POST['pic'][$kp] = $rand_num . ',' . $tmp_vp[1];
			}
			$_POST['pic'] = implode(';', $_POST['pic']);
			$_POST['print_id'] = isset($_POST['print_id']) ? intval($_POST['print_id']) : 0;


			if ($_POST['specs']) {
				foreach ($_POST['specs'] as $val) {
					if (empty($val)) {
						$error_tips .= '请给规格取名，若不需要的请删除后重新生成'.'<br/>';
					}
				}
			}

			if ($_POST['spec_val']) {
				foreach ($_POST['spec_val'] as $rowset) {
					foreach ($rowset as $val) {
						if (empty($val)) {
							$error_tips .= '请给规格的属性值取名，若不需要的请删除后重新生成'.'<br/>';
						}
					}
				}
			}


			if ($_POST['properties']) {
				foreach ($_POST['properties'] as $val) {
					if (empty($val)) {
						$error_tips .= '请给属性取名，若不需要的请删除后重新生成'.'<br/>';
					}
				}
			}

			if ($_POST['properties_val']) {
				foreach ($_POST['properties_val'] as $rowset) {
					foreach ($rowset as $val) {
						if (empty($val)) {
							$error_tips .= '请给属性的属性值取名，若不需要的请删除后重新生成'.'<br/>';
						}
					}
				}
			}

			if (isset($_POST['prices']) && $_POST['prices']) {
				foreach ($_POST['prices'] as $rowset) {
					foreach ($rowset as $val) {
						if (empty($val)) {
							$error_tips .= '所有的现价必须要填写'.'<br/>';
						}
					}
				}
			}
			
			$sort_id = $now_sort['sort_id'];
			for ($i = 1; $i <= self::GOODS_SORT_LEVEL; $i++) {
			    if (isset($_POST['sort_id_' . $i]) && intval($_POST['sort_id_' . $i])) {
			        $sort_id = intval($_POST['sort_id_' . $i]);
			        unset($_POST['sort_id_' . $i]);
			    }
			}
			$shopGoodsSortDB = M('Shop_goods_sort');
			if ($sort = $shopGoodsSortDB->field(true)->where(array('sort_id' => $sort_id, 'store_id' => $now_store['store_id']))->find()) {
			    if ($fsort = $shopGoodsSortDB->field(true)->where(array('fid' => $sort_id, 'store_id' => $now_store['store_id']))->find()) {
			        $error_tips .= '该分类有子分类，不能直接添加商品'.'<br/>';
			    } elseif ($sort['operation_type'] != 0) {
			        $shopGoodsSortDB->where(array('sort_id' => $sort_id, 'store_id' => $now_store['store_id']))->save(array('operation_type' => 0));
			    }
			} else {
			    $error_tips .= '商品分类不存在'.'<br/>';
			}
			if ($_POST['seckill_type']) {
			    $_POST['seckill_open_time'] = strtotime(date('Y-m-d ') . $_POST['seckill_open_time'] . ":00");
			    $_POST['seckill_close_time'] = strtotime(date('Y-m-d ') . $_POST['seckill_close_time'] . ":00");
			} else {
			    $_POST['seckill_open_time'] = strtotime($_POST['seckill_open_datetime'] . ":00");
			    $_POST['seckill_close_time'] = strtotime($_POST['seckill_close_datetime'] . ":00");
			}
			
			
			
			if (empty($error_tips)) {
				$_POST['sort_id'] = $sort_id;
				$_POST['store_id'] = $now_store['store_id'];
				$_POST['last_time'] = $_SERVER['REQUEST_TIME'];

				if ($goods_id = D('Shop_goods')->save_post_form($_POST, $now_store['store_id'])) {
					D('Image')->update_table_id($_POST['image'], $goods_id, 'goods');
					$this->success('添加成功！', U('Shop/goods_list', array('sort_id' => $now_sort['sort_id'])));
					die;
					$ok_tips = '添加成功！';
				} else {
					$this->error('添加失败！请重试！', U('Shop/goods_list', array('sort_id' => $now_sort['sort_id'])));
					die;
					$error_tips = '添加失败！请重试。';
				}
			} else {
				$return = $this->format_data($_POST);
				$_POST['json'] = isset($return['json']) ? json_encode($return['json']) : '';
				$_POST['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
				$_POST['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
				$_POST['list'] = isset($return['list']) ? $return['list'] : '';

				$this->assign('now_goods', $_POST);
			}

			$this->assign('ok_tips', $ok_tips);
			$this->assign('error_tips', $error_tips);
		} else {
			$this->assign('now_goods', array('seckill_open_time' => strtotime(date('Y-m-d') . ' 08:00:00'), 'seckill_close_time' => strtotime(date('Y-m-d') . ' 10:00:00')));
		}
		$print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
		foreach ($print_list as &$l) {
			if ($l['is_main']) {
				$l['name'] .= '(主打印机)';
			} else {
				$l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
			}
		}
		$this->assign('print_list', $print_list);
		$category_list = D('Goods_category')->get_list();
		$this->assign('category_list', json_encode($category_list));
		
		$sort_list = D('Shop_goods_sort')->lists($now_store['store_id'], false);
// 		echo '<pre/>';
// 		print_r($sort_list);die;
		$this->assign('sort_list', json_encode($sort_list));
		$ids = D('Shop_goods_sort')->getIds($now_sort['sort_id'], $now_store['store_id']);
		$this->assign('select_ids', json_encode($ids));
		
		$this->assign('now_sort', $now_sort);
		$this->assign('now_store', $now_store);
		
		$this->assign('express_template', D('Express_template')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->select());
		$this->display();
	}

	public function ajax_goods_properties()
	{
		$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
		$goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
		$properties = D('Goods_properties')->field(true)->where(array('status' => 1, 'cat_id' => $cat_id))->select();
		if ($properties) {
			$value_ids = array();
			$relations = D('Goods_properties_relation')->field(true)->where(array('gid' => $goods_id))->select();
			foreach ($relations as $r) {
				$value_ids[] = $r['pid'];
			}
			$pids = array();
			$list = array();
			foreach ($properties as $row) {
				$pids[] = $row['id'];
				$row['value_list'] = null;
				$list[$row['id']] = $row;
			}
			$value_list = D('Goods_properties_value')->field(true)->where(array('pid' => array('in', $pids)))->select();
			foreach ($value_list as $v) {
				if (isset($list[$v['pid']])) {
					if (in_array($v['id'], $value_ids)) {
						$v['checked'] = 1;
					} else {
						$v['checked'] = 0;
					}
					$list[$v['pid']]['value_list'][] = $v;
				}
			}
			$data = array();
			foreach ($list as $row) {
				if (isset($row['value_list']) && $row['value_list']) {
					$data[] = $row;
				}
			}
			exit(json_encode(array('error_code' => false, 'data' => $data)));
		} else {
			exit(json_encode(array('error_code' => true, 'msg' => '没有数据')));
		}
	}

	public function ajax_upload_pic()
	{
		if ($_FILES['file']['error'] != 4) {
			$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
			$shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $store_id))->find();
			$store_theme = isset($shop['store_theme']) ? intval($shop['store_theme']) : 0;
			if ($store_theme) {
				$width = '900,450';
				$height = '900,450';
			} else {
				$width = '900,450';
				$height = '500,250';
			}
			$param = array('size' => $this->config['group_pic_size']);
			$param['thumb'] = true;
			$param['imageClassPath'] = 'ORG.Util.Image';
			$param['thumbPrefix'] = 'm_,s_';
			$param['thumbMaxWidth'] = $width;
			$param['thumbMaxHeight'] = $height;
			$param['thumbRemoveOrigin'] = false;
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'goods', 1, $param);
			if ($image['error']) {
				exit(json_encode(array('error' => 1,'message' =>$image['message'])));
			} else {
				$title = $image['title']['imgFile']?$image['title']['imgFile']:$image['title']['file'];
				$goods_image_class = new goods_image();
				$url = $goods_image_class->get_image_by_path($title, 'm');
				exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
			}
		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}

	/* 编辑商品 */
	public function goods_edit()
	{
		$now_goods = $this->check_goods($_GET['goods_id']);
		$now_sort = $this->check_sort($now_goods['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);
		if (IS_POST) {
			if (empty($_POST['name'])) {
				$error_tips .= '商品名称必填！'.'<br/>';
			}
			if (empty($_POST['unit'])) {
				$error_tips .= '商品单位必填！'.'<br/>';
			}
			if ($_POST['price'] === '' && !$this->config['open_extra_price']) {
				$error_tips .= '商品价格可以设置为0，但是必填！'.'<br/>';
			}
			if ($_POST['price'] < 0 && !$this->config['open_extra_price']) {
				$error_tips .= '商品价格必须大于或等于0！'.'<br/>';
			}
			if (empty($_POST['pic'])) {
				$error_tips .= '请至少上传一张照片！'.'<br/>';
			}
			
			if($_POST['stock_num'] >= 0 && (($_POST['seckill_stock'] > 0 && $_POST['seckill_stock'] > $_POST['stock_num']) || $_POST['seckill_stock'] == -1)){
				$error_tips .= '限时价库存不能大于商品库存！如果不做限时活动，请将限时价库存设置为0'.'<br/>';
			}

			$_POST['des'] = fulltext_filter($_POST['des']);

			$img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
			$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
			foreach($_POST['pic'] as $kp => $vp){
				$tmp_vp = explode(',', $vp);
				if (!strstr($tmp_vp[0], '/upload/')) $rand_num = $tmp_vp[0];
				$_POST['pic'][$kp] = $rand_num . ',' . $tmp_vp[1];
			}
			$_POST['pic'] = implode(';', $_POST['pic']);
			$_POST['print_id'] = isset($_POST['print_id']) ? intval($_POST['print_id']) : 0;

			if ($_POST['specs']) {
				foreach ($_POST['specs'] as $val) {
					if (empty($val)) {
						$error_tips .= '请给规格取名，若不需要的请删除后重新生成'.'<br/>';
					}
				}
			}

			if ($_POST['spec_val']) {
				foreach ($_POST['spec_val'] as $rowset) {
					foreach ($rowset as $val) {
						if (empty($val)) {
							$error_tips .= '请给规格的属性值取名，若不需要的请删除后重新生成'.'<br/>';
						}
					}
				}
			}

			if ($_POST['properties']) {
				foreach ($_POST['properties'] as $val) {
					if (empty($val)) {
						$error_tips .= '请给属性取名，若不需要的请删除后重新生成'.'<br/>';
					}
				}
			}

			if ($_POST['properties_val']) {
				foreach ($_POST['properties_val'] as $rowset) {
					foreach ($rowset as $val) {
						if (empty($val)) {
							$error_tips .= '请给属性的属性值取名，若不需要的请删除后重新生成'.'<br/>';
						}
					}
				}
			}
			if ($_POST['score_percent']) {
				if(strpos($_POST['score_percent'],'%')){
					$tmp_percent = str_replace('%','',$_POST['score_percent']);
					if(floatval($tmp_percent)>100 ||floatval($tmp_percent)<0 ){
						$error_tips .= '消费1元获得积分数百分比应在0-100%之间'.'<br/>';
					}
				}
			}
			if ($_POST['score_max']<0) {

				$error_tips .= '积分最大使用数应是大于等于0的整数'.'<br/>';

			}
			$score_max_deducte = bcdiv($_POST['score_max'], $this->config['user_score_use_percent'], 2);
			if ($score_max_deducte>$_POST['price']) {
				$error_tips .= '积分最大使用数抵扣金额超过了商品价格'.'<br/>';
			}
			
			$sort_id = $now_sort['sort_id'];
			for ($i = 1; $i <= self::GOODS_SORT_LEVEL; $i++) {
			    if (isset($_POST['sort_id_' . $i]) && intval($_POST['sort_id_' . $i])) {
			        $sort_id = intval($_POST['sort_id_' . $i]);
			        unset($_POST['sort_id_' . $i]);
			    }
			}
			$shopGoodsSortDB = M('Shop_goods_sort');
			if ($sort = $shopGoodsSortDB->field(true)->where(array('sort_id' => $sort_id, 'store_id' => $now_store['store_id']))->find()) {
			    if ($fsort = $shopGoodsSortDB->field(true)->where(array('fid' => $sort_id, 'store_id' => $now_store['store_id']))->find()) {
			        $error_tips .= '该分类有子分类，不能直接添加商品'.'<br/>';
			    } elseif ($sort['operation_type'] != 0) {
			        $shopGoodsSortDB->where(array('sort_id' => $sort_id, 'store_id' => $now_store['store_id']))->save(array('operation_type' => 0));
			    }
			} else {
			    $error_tips .= '商品分类不存在'.'<br/>';
			}
			
			if ($_POST['seckill_type']) {
			    $_POST['seckill_open_time'] = strtotime(date('Y-m-d ') . $_POST['seckill_open_time'] . ":00");
			    $_POST['seckill_close_time'] = strtotime(date('Y-m-d ') . $_POST['seckill_close_time'] . ":00");
			} else {
			    $_POST['seckill_open_time'] = strtotime($_POST['seckill_open_datetime'] . ":00");
			    $_POST['seckill_close_time'] = strtotime($_POST['seckill_close_datetime'] . ":00");
			}

			if (empty($error_tips)) {
				$_POST['goods_id'] = $now_goods['goods_id'];
				$_POST['sort_id'] = $sort_id;
				$_POST['store_id'] = $now_store['store_id'];
				$_POST['last_time'] = $_SERVER['REQUEST_TIME'];

				if ($goods_id = D('Shop_goods')->save_post_form($_POST, $now_store['store_id'])) {
					D('Image')->update_table_id($_POST['image'], $goods_id, 'goods');
					$this->success('保存成功！', U('Shop/goods_list', array('sort_id' => $now_sort['sort_id'],'page' => $_POST['page'])));
					die;
					$ok_tips = '保存成功！';
				} else {
					$this->error('保存失败！请重试！', U('Shop/goods_list', array('sort_id' => $now_sort['sort_id'],'page' => $_POST['page'])));
					die;
					$error_tips = '保存失败！请重试。';
				}
			} else {
				$return = $this->format_data($_POST);
				$_POST['json'] = isset($return['json']) ? json_encode($return['json']) : '';
				$_POST['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
				$_POST['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
				$_POST['list'] = isset($return['list']) ? $return['list'] : '';
				$this->assign('now_goods', $_POST);
			}

			$this->assign('ok_tips', $ok_tips);
			$this->assign('error_tips', $error_tips);
		}

		$print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
		foreach ($print_list as &$l) {
			if ($l['is_main']) {
				$l['name'] .= '(主打印机)';
			} else {
				$l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
			}
		}
		$this->assign('print_list', $print_list);
		$category_list = D('Goods_category')->get_list();
		$this->assign('category_list', json_encode($category_list));
		
		$sort_list = D('Shop_goods_sort')->lists($now_store['store_id'], false);
		$this->assign('sort_list', json_encode($sort_list));
		$ids = D('Shop_goods_sort')->getIds($now_sort['sort_id'], $now_store['store_id']);
		$this->assign('select_ids', json_encode($ids));
		$this->assign('now_goods', $now_goods);
		$this->assign('now_sort', $now_sort);
		$this->assign('now_store', $now_store);
		$this->assign('express_template', D('Express_template')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->select());
		$this->display();
	}


	/* 商品删除 */
	public function goods_del()
	{
		$now_goods = $this->check_goods($_GET['goods_id']);
		$now_sort = $this->check_sort($now_goods['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);

		$database_goods = D('Shop_goods');
		$condition_goods['goods_id'] = $now_goods['goods_id'];
		if ($database_goods->where($condition_goods)->delete()) {
			$spec_obj = M('Shop_goods_spec'); //规格表
			$old_spec = $spec_obj->field(true)->where(array('goods_id' => $now_goods['goods_id'], 'store_id' => $now_sort['store_id']))->select();
			foreach ($old_spec as $os) {
				$delete_spec_ids[] = $os['id'];
			}
			$spec_obj->where(array('goods_id' => $now_goods['goods_id'], 'store_id' => $now_sort['store_id']))->delete();
			if ($delete_spec_ids) {
				$old_spec_val = M('Shop_goods_spec_value')->where(array('sid' => array('in', $delete_spec_ids)))->delete();
			}
			M('Shop_goods_properties')->where(array('goods_id' => $now_goods['goods_id']))->delete();
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！请检查后重试。');
		}
	}
	/* 商品状态 */
	public function goods_status()
	{
		$now_goods = $this->check_goods($_POST['id']);
		$now_sort = $this->check_sort($now_goods['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);

		$database_goods = D('Shop_goods');
		$condition_goods['goods_id'] = $now_goods['goods_id'];
		$data_goods['status'] = $_POST['type'] == 'open' ? '1' : '0';
		if($database_goods->where($condition_goods)->data($data_goods)->save()){
			exit('1');
		}else{
			exit;
		}
	}

	/* 检测店铺存在，并检测是不是归属于商家 */
	protected function check_store($store_id)
	{
	    if ($this->merchant_session['store_id'] && $this->merchant_session['store_id'] != $store_id) {
	        $this->error('您没有这个权限');
	    }
	    
		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['store_id'] = $store_id;
		$condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
		$now_store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
		if (empty($now_store)) {
			$this->error('店铺不存在！');
		} else {
			//return $now_store;
			if ($now_shop = D('Merchant_store_shop')->field(true)->where($condition_merchant_store)->find()) {
				if (!empty($now_shop['background'])) {
					$image_tmp = explode(',', $now_shop['background']);
					$now_shop['background_image'] = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
				}
				return array_merge($now_store, $now_shop);
			}
			return $now_store;
			$now_shop = D('Merchant_store_shop')->field(true)->where($condition_merchant_store)->find();
			return array_merge($now_store, $now_shop);
		}
	}
	/* 检测分类存在 */
	protected function check_sort($sort_id)
	{
		$database_goods_sort = D('Shop_goods_sort');
		$condition_goods_sort['sort_id'] = $sort_id;
		$now_sort = $database_goods_sort->field(true)->where($condition_goods_sort)->find();
		if (empty($now_sort)) {
			$this->error('分类不存在！');
		}
		if(!empty($now_sort['image'])){
			$sort_image_class = new goods_sort_image();
			$now_sort['see_image'] = $sort_image_class->get_image_by_path($now_sort['image'],$this->config['site_url'],'s');
		}
		if ($now_sort['week'] != null) {
			$now_sort['week'] = explode(',', $now_sort['week']);
		}
		return $now_sort;
	}
	/* 检测商品存在 */
	protected function check_goods($goods_id)
	{
		$database_shop_goods = D('Shop_goods');
		$condition_goods['goods_id'] = $goods_id;
		$now_goods = $database_shop_goods->field(true)->where($condition_goods)->find();
		if(empty($now_goods)){
			$this->error('商品不存在！');
		}
		if(!empty($now_goods['image'])){
			$goods_image_class = new goods_image();
			$tmp_pic_arr = explode(';', $now_goods['image']);
			foreach ($tmp_pic_arr as $key => $value) {
				$now_goods['pic_arr'][$key]['title'] = $value;
				$now_goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
			}
		}

		$return = $database_shop_goods->format_spec_value($now_goods['spec_value'], $now_goods['goods_id']);
		$now_goods['json'] = isset($return['json']) ? json_encode($return['json']) : '';
		$now_goods['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
		$now_goods['properties_status_list'] = isset($return['properties_status_list']) ? $return['properties_status_list'] : '';
		$now_goods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
		$now_goods['list'] = isset($return['list']) ? $return['list'] : '';

		return $now_goods;
	}

	private function format_spec($a, $i, $str, &$return)
	{
		if ($i == 0) {
			$ii = $i + 1;
			foreach ($a[$i] as $val) {
				$t = $str ? $str . '_' : '';
				if ($ii == count($a)) {
					$return[] = $t . $val;
				} else {
					$this->format_spec($a, $ii, $t . $val, $return);
				}
			}
		} else if ($i == count($a) - 1) {
			foreach ($a[$i] as $val) {
				$t = $str ? $str . '_' : '';
				$return[] = $t . $val;
			}
		} else {
			$ii = $i + 1;
			foreach ($a[$i] as $val) {
				$t = $str ? $str . '_' : '';
				$this->format_spec($a, $ii, $t . $val, $return);
			}
		}
	}

	public function format_data($data)
	{
		$spec_list = array();
		foreach ($data['spec_id'] as $i => $id) {
			$id = intval($id);
			$t_i = $id ? $id : 'i_' . $i;
			$spec_list[$t_i] = array('id' => $id, 'name' => $data['specs'][$i]);

			foreach ($data['spec_val_id'][$i] as $ii => $vid) {
				$vid = intval($vid);
				$v_i = $vid ? $vid : 'v_' . $ii;
				$spec_list[$t_i]['list'][$v_i] = array('id' => $vid,  'name' => $data['spec_val'][$i][$ii]);
			}
		}

		$properties_list = array();
		foreach ($data['properties_id'] as $pi => $pid) {
			$pid = intval($pid);
			$p_i = $pid ? $pid : 'p_' . $pi;
			$properties_list[$p_i] = array('id' => $pid, 'name' => $data['properties'][$pi], 'val' => $data['properties_val'][$pi]);
		}


		$for_data = array();
		foreach ($data['spec_val_id'] as $di => $dr) {
			foreach ($dr as $d => $id_t) {
				$for_data[$di][$d] = $di . '_' . $d;
			}
		}

		$formart_data = array();
		$this->format_spec($for_data, 0, '', $formart_data);

// 		echo "<Pre/>";
// 		print_r($formart_data);
// 		die;

		$list = array();
		foreach ($formart_data as $fi => $string) {
			$array = explode('_', $string);
			$array = array_chunk($array, 2);
// 		foreach ($data['spec_val_id'] as $k => $rowset) {
			$index = $pre = '';
			$tdata = array();
			foreach ($array as $irow) {
				$k = $irow[0];
				$ki = $irow[1];
				$r = $data['spec_val_id'][$irow[0]][$irow[1]];
// 			foreach ($rowset as $ki => $r) {
				if ($r) {
					$index .= $pre . 'id_' . $r;
				} else {
					$index .= $pre . 'index_' . $ki;
				}
				$pre = '_';
				$tdata[] = array('spec_val_id' => $r, 'spec_val_name' => $data['spec_val'][$k][$ki]);
			}
			$list[$index]['index'] = $index;
			$list[$index]['spec'] = $tdata;
			$list[$index]['old_price'] = $data['old_prices'][$fi];
			$list[$index]['price'] = $data['prices'][$fi];
			$list[$index]['seckill_price'] = $data['seckill_prices'][$fi];
			$list[$index]['stock_num'] = $data['stock_nums'][$fi];
			$list[$index]['max_num'] = $data['max_nums'][$fi];
			$list[$index]['cost_price'] = $data['cost_prices'][$fi];
			$list[$index]['number'] = $data['numbers'][$fi];
			$pt_data = array();
			foreach ($data['properties'] as $pin => $pr) {
				$pt_data[] = array('id' => $data['properties_id'][$pin], 'num' => $data['num' . $fi][$pin], 'name' => $pr);
				$ptdata['num' . $pin . '[]'] = $data['num' . $fi][$pin];
			}
			$list[$index]['properties'] = $pt_data;

			$json[$index] = $ptdata;
			$json[$index]['old_prices[]'] = $data['old_prices'][$fi];
			$json[$index]['prices[]'] = $data['prices'][$fi];
			$json[$index]['seckill_prices[]'] = $data['seckill_prices'][$fi];
			$json[$index]['stock_nums[]'] = $data['stock_nums'][$fi];
			$json[$index]['max_nums[]'] = $data['max_nums'][$fi];
			$json[$index]['cost_prices[]'] = $data['cost_prices'][$fi];
			$json[$index]['numbers[]'] = $data['numbers'][$fi];
		}
// 		echo "<Pre/>";
// 		print_r(array('spec_list' => $spec_list, 'properties_list' => $properties_list, 'list' => $list, 'json' => $json));
// 		die;
		return array('spec_list' => $spec_list, 'properties_list' => $properties_list, 'list' => $list, 'json' => $json);

	}

	public function discount()
	{
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('store', $now_store);
		$discount = D('Shop_discount')->field(true)->where(array('store_id' => $now_store['store_id']))->select();
		$this->assign('discount_list', $discount);
		$this->display();
	}

	public function discount_add()
	{
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store',$now_store);

		if (IS_POST) {
			$database_discount = D('Shop_discount');
			$data_discount['store_id'] = $now_store['store_id'];
			$data_discount['mer_id'] = $now_store['mer_id'];
			$data_discount['full_money'] = $_POST['full_money'];
			$data_discount['reduce_money'] = $_POST['reduce_money'];
			$data_discount['type'] = intval($_POST['type']);
			$data_discount['status'] = intval($_POST['status']);
			$data_discount['is_share'] = intval($_POST['is_share']);
			$data_discount['source'] = 1;
			if ($database_discount->data($data_discount)->add()) {
				$this->success('添加成功！！', U('Shop/discount',array('store_id' => $now_store['store_id'])));
				die;
			}else{
				$this->error('添加失败！！请重试。', U('Shop/discount',array('store_id' => $now_store['store_id'])));
				die;
			}
			if(!empty($error_tips)){
				$this->assign('now_discount', $_POST);
			}
			$this->assign('ok_tips', $ok_tips);
			$this->assign('error_tips', $error_tips);
		}
		$this->display();
	}

	public function discount_edit()
	{
		$now_store = $this->check_store($_GET['store_id']);
		if (!($discount = D('Shop_discount')->field(true)->where(array('id' => intval($_GET['id']), 'store_id' => $now_store['store_id']))->find())) {
			$this->error('不存在的优惠，请查证后修改。', U('Shop/discount',array('store_id' => $now_store['store_id'])));
		}
		$this->assign('now_store',$now_store);

		if (IS_POST) {
			$database_discount = D('Shop_discount');
			$data_discount['id'] = $discount['id'];
			$data_discount['store_id'] = $now_store['store_id'];
			$data_discount['mer_id'] = $now_store['mer_id'];
			$data_discount['full_money'] = $_POST['full_money'];
			$data_discount['reduce_money'] = $_POST['reduce_money'];
			$data_discount['type'] = intval($_POST['type']);
			$data_discount['status'] = intval($_POST['status']);
			$data_discount['is_share'] = intval($_POST['is_share']);
			$data_discount['source'] = 1;
			if ($database_discount->data($data_discount)->save()) {
				$this->success('修改成功！！', U('Shop/discount',array('store_id' => $now_store['store_id'])));
				die;
			}else{
				$this->error('修改失败！！请重试。', U('Shop/discount',array('store_id' => $now_store['store_id'])));
				die;
			}
			if(!empty($error_tips)){
				$this->assign('now_discount', $_POST);
			}
			$this->assign('ok_tips', $ok_tips);
			$this->assign('error_tips', $error_tips);
		} else {
			$this->assign('now_discount', $discount);
		}
		$this->display();
	}

	public function order()
	{
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store', $now_store);
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= $type . ' ' . $sort . ',';
			$order_sort .= 'pay_time DESC';
		} else {
			$order_sort .= 'pay_time DESC';
		}

		$where = array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']);
// 		if ($status != -1) {
// 		    if ($status == 2) {
// 		        $where['status'] = array('in', '2, 3');
// 		    } elseif ($status == 6) {
// 		        $where['paid'] = 0;
// 		    } else {
// 		        $where['status'] = $status;
// 		    }
// 		}

		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['real_orderid'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['order_id'] = $tmp_result['order_id'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['username'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['userphone'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'third_id') {
				$where['third_id'] =$_GET['keyword'];
			}
		}

		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';

		if($status == 100){
			$where['paid'] = 0;
		}else if ($status != -1) {
		    if ($status == 2) {
		        $where['status'] = array('in', '2, 3');
		    } elseif ($status == 6) {
		        $where['paid'] = 0;
		    } else {
		        $where['status'] = $status;
		    }
		}
		if($pay_type&&$pay_type!='balance'){
			$where['pay_type'] = $pay_type;
		}else if($pay_type=='balance'){
			$where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 OR `card_give_money` <> 0)";
		}

		$period = array();
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] .=( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
			//$condition_where['_string']=$time_condition;
		}

		$order_lsit = D('Shop_order')->get_order_list($where, $order_sort, 2, false);
		$this->assign('status_list', D('Shop_order')->status_list);
		$this->assign($order_lsit);
		$this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status,'pay_type'=>$pay_type));

		$field = 'sum(price) AS total_price, sum(price - card_price - merchant_balance - balance_pay - payment_money - score_deducte - coupon_price) AS offline_price, sum(card_price + merchant_balance + balance_pay + payment_money + score_deducte + coupon_price) AS online_price';
		$count_where = "store_id='{$now_store['store_id']}' AND paid=1 AND is_del=0 AND status<>4 AND status<>5 AND (pay_type<>'offline' OR (pay_type='offline' AND third_id<>''))";
		if ($period) {
		    $count_where .= " AND (use_time BETWEEN " . $period[0] .' AND ' . $period[1] . ")";
		}
		$result_total = D('Shop_order')->field($field)->where($count_where)->select();
		$result_total = isset($result_total[0]) ? $result_total[0] : '';
		$this->assign($result_total);
		$pay_method = D('Config')->get_pay_method('','',1);
		$this->assign('pay_method',$pay_method);

		$this->display();
	}

	public function order_detail()
	{
		if(strlen($_GET['order_id'])>=20){
			$now_shop_order = D('Shop_order')->where(array('real_orderid'=>$_GET['order_id']))->find();
			$_GET['order_id'] = $now_shop_order['order_id'];
		}
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id']));
		$order['user_adress'] = D('User_adress')->get_one_adress($order['uid'],intval($order['address_id']));
		if ($this->merchant_session['store_id'] && $this->merchant_session['store_id'] != $order['store_id']) {
		    $this->error('您没有这个权限');
		}
		$tempList = array();
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
		
		$store = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
		$this->assign('store', $store);
		$this->assign('order', $order);
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

	public function ajax_del_pic() {
// 		$group_image_class = new goods_image();
// 		$group_image_class->del_image_by_path($_POST['path']);
	}

	public function clone_goods()
	{

	    if ($this->merchant_session['store_id']) {
	        $this->error('您没有这个权限');
	    }
		$source_store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		$store_ids = isset($_POST['store_ids']) ? $_POST['store_ids'] : 0;
		if (empty($store_ids)) {
		    $this->error('请选择要同步的店铺');
		}
		if ($store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $source_store_id, 'have_shop' => 1))->find()) {
			if ($shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $source_store_id))->find()) {

			} else {
				$this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
			}
		} else {
			$this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
		}
		foreach ($store_ids as $store_id) {
			if ($target_store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id, 'have_shop' => 1))->find()) {
				if (!$target_shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find()) {
					continue;
				}
			} else {
				continue;
			}

			$goods_sorts = M('Shop_goods_sort')->field(true)->where(array('store_id' => $source_store_id))->order('level ASC')->select();
			foreach ($goods_sorts as $sv) {
			    $oldIds[$sv['sort_id']] = $sv['fid'];//id ==> fid
			}
			$listOldFidToNewFid = array();
			foreach ($goods_sorts as $sort) {
				$source_sort_id = $sort['sort_id'];
				if ($target_sort = M('Shop_goods_sort')->field(true)->where(array('store_id' => $store_id, 'sort_name' => $sort['sort_name']))->find()) {
					$target_sort_id = $target_sort['sort_id'];
				} else {
					$sort['store_id'] = $store_id;
					unset($sort['sort_id']);
					$sort['fid'] = 0;
					if (isset($oldIds[$source_sort_id]) && $oldIds[$source_sort_id]) {// oldFId = $oldIds[$source_sort_id];
					    $sort['fid'] = isset($listOldFidToNewFid[$oldIds[$source_sort_id]]) ? $listOldFidToNewFid[$oldIds[$source_sort_id]] : 0;
					}
					$target_sort_id = M('Shop_goods_sort')->add($sort);
				}
				$listOldFidToNewFid[$source_sort_id] = $target_sort_id;//oldID ==> newID
				
				$goods_list = M('Shop_goods')->field(true)->where(array('store_id' => $source_store_id, 'sort_id' => $source_sort_id))->select();
				foreach ($goods_list as $goods) {
					if ($tmp_goods = M('Shop_goods')->field(true)->where(array('name' => $goods['name'], 'store_id' => $store_id))->find()) {
						continue;
					} else {
						$source_goods_id = $goods['goods_id'];
						unset($goods['goods_id']);
						$goods['store_id'] = $store_id;
						$goods['sort_id'] = $target_sort_id;
						$goods['print_id'] = 0;
						$target_goods_id = M('Shop_goods')->add($goods);
						$pro_map = $spec_map = $spec_value_map = array();
						if ($goods['is_properties']) {
							$properties = M('Shop_goods_properties')->field(true)->where(array('goods_id' => $source_goods_id))->select();
							foreach ($properties as $pro_data) {
								$source_pro_id = $pro_data['id'];
								unset($pro_data['id']);
								$pro_data['goods_id'] = $target_goods_id;
								$pro_map[$source_pro_id] = M('Shop_goods_properties')->add($pro_data);
							}
						}
						if ($goods['spec_value']) {
							$spec_list = M('Shop_goods_spec')->field(true)->where(array('goods_id' => $source_goods_id, 'store_id' => $source_store_id))->select();
							foreach ($spec_list as $spec) {
								$source_spec_id = $spec['id'];
								unset($spec['id']);

								$spec['store_id'] = $store_id;
								$spec['goods_id'] = $target_goods_id;
								
								if ($new_spec_id = M('Shop_goods_spec')->add($spec)) {

									$spec_value_list = M('Shop_goods_spec_value')->field(true)->where(array('sid' => $source_spec_id))->select();
									foreach ($spec_value_list as $spec_value) {
										$source_spec_value_id = $spec_value['id'];
										unset($spec_value['id']);
										$spec_value['sid'] = $new_spec_id;
										$spec_value_map[$source_spec_value_id] = M('Shop_goods_spec_value')->add($spec_value);
									}
								}
							}

							//规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#
							$spec_array = explode('#', $goods['spec_value']);
							$target_spec_value_array = array();
							foreach ($spec_array as $str) {
								$row_array = explode('|', $str);
								$spec_val_ids = explode(':', $row_array[0]);
								$new_ids = array();
								foreach ($spec_val_ids as $tid) {
									$new_ids[] = $spec_value_map[$tid];
								}
								$row_array[0] = implode(':', $new_ids);
								if (count($row_array) > 2 && $row_array[2] && strstr($row_array[2], '=')) {
									$pro_str_ids = explode(':', $row_array[2]);
									$new_pro_ids = array();
									foreach ($pro_str_ids as $pstr) {
										$v_k_a = explode('=', $pstr);
										$new_pro_ids[] = $pro_map[$v_k_a[0]] . '=' . $v_k_a[1];
									}
									$row_array[2] = implode(':', $new_pro_ids);
								}
								$target_spec_value_array[] = implode('|', $row_array);
							}
							M('Shop_goods')->where(array('goods_id' => $target_goods_id))->save(array('spec_value' => implode('#', $target_spec_value_array)));
						}
					}
				}
			}
			$this->success('克隆完成');
		}
	}
	
	public function store()
	{
	    if ($this->merchant_session['store_id']) {
	        $this->error('您没有这个权限');
	    }
	    $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
	    if ($store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id, 'have_shop' => 1))->find()) {
	        if ($shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find()) {
	            
	        } else {
	            $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
	        }
	    } else {
	        $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
	    }
	    
	    $sql = "SELECT s.store_id, s.name FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON sh.store_id=s.store_id WHERE s.have_shop=1 AND s.status=1 AND s.store_id<>{$store_id} AND s.mer_id={$this->merchant_session['mer_id']}";
	    $res = D()->query($sql);
	    $this->assign('stores', $res);
	    $this->assign('store_id', $store_id);
	    $this->display();
	}
	
	public function foodshop()
	{
	    if ($this->merchant_session['store_id']) {
	        $this->error('您没有这个权限');
	    }
	    $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
	    if ($store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id, 'have_shop' => 1))->find()) {
	        if ($shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find()) {
	            
	        } else {
	            $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
	        }
	    } else {
	        $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
	    }
	    
	    $sql = "SELECT s.store_id, s.name FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_foodshop AS sh ON sh.store_id=s.store_id WHERE s.have_meal=1 AND s.status=1 AND s.mer_id={$this->merchant_session['mer_id']}";
	    $res = D()->query($sql);
	    $this->assign('stores', $res);
	    $this->assign('store_id', $store_id);
	    $this->display();
	}


	public function export()
	{
		$param = $_POST;
		$param['type'] = 'shop';
		$param['rand_number'] = $_SERVER['REQUEST_TIME'];
		$param['store_session']['store_id'] = $_POST['store_id'];
		 //$param['store_session']['store_id'] = $store_session['store_id'];
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
		die;
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '订单信息';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		// 设置当前的sheet

		$where = array();
		$condition_where = 'WHERE o.store_id = '.$_GET['store_id'];
		$where['store_id'] =$_GET['store_id'];


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
			} elseif ($_GET['searchtype'] == 'third_id') {
				$where['third_id'] =$_GET['keyword'];
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
			$objActSheet->setCellValue('R1', '支付时间');
			$objActSheet->setCellValue('S1', '送达时间');
			$objActSheet->setCellValue('T1', '订单状态');
			$objActSheet->setCellValue('U1', '支付情况');
			//$objActSheet->setCellValue('R1', 支付情况'支付情况');

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
						$objActSheet->setCellValueExplicit('R' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
						$objActSheet->setCellValueExplicit('S' . $index, $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '');
						$objActSheet->setCellValueExplicit('T' . $index, D('Shop_order')->status_list[$value['status']]);
						$objActSheet->setCellValueExplicit('U' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));
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

	public function ajax_upload_shoppic()
	{
		if ($_FILES['file']['error'] != 4) {
			$param = array('size' => $this->config['group_pic_size']);
			$param['thumb'] = true;
			$param['imageClassPath'] = 'ORG.Util.Image';
// 			$param['thumbPrefix'] = 'm_,s_';
			$param['thumbMaxWidth'] = 640;
			$param['thumbMaxHeight'] = 420;
			$param['thumbRemoveOrigin'] = false;
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'background', 1, $param);
			if ($image['error']) {
				exit(json_encode(array('error' => 1,'message' =>$image['msg'])));
			} else {
				$title = $image['title']['file'];
				$image_tmp = explode(',', $title);
				$url = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
				exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
			}
		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}

	public function ajax_del_shoppic()
	{
		if (!empty($_POST['path'])) {
			$image_tmp = explode(',', $_POST['path']);
			unlink('./upload/background/' . $image_tmp[0] . '/' . $image_tmp['1']);
			unlink('./upload/background/' . $image_tmp[0] . '/m_' . $image_tmp['1']);
			unlink('./upload/background/' . $image_tmp[0] . '/s_' . $image_tmp['1']);
			return true;
		} else {
			return false;
		}
	}
	
	public function change_mall()
	{
		$now_store = $this->check_store(intval($_POST['id']));
		$store_theme = $_POST['type'] == 'open' ? '1' : '0';
		if (D('Merchant_store_shop')->where(array('store_id' => $now_store['store_id'], 'mer_id' => $this->merchant_session['mer_id']))->save(array('store_theme' => $store_theme))) {
			exit('1');
		} else {
			exit;
		}
	}
	
    
    public function sort_order()
    {
        $sortId = isset($_GET['sort_id']) ? intval($_GET['sort_id']) : 0;
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $now_store = $this->check_store($store_id);
        
        $stime = isset($_GET['stime']) && $_GET['stime'] ? htmlspecialchars($_GET['stime']) : '';
        $etime = isset($_GET['etime']) && $_GET['etime'] ? htmlspecialchars($_GET['etime']) : '';
        $shopGoodsSortDB = D('Shop_goods_sort');
        $sortList = $shopGoodsSortDB->field(true)->where(array('store_id' => $store_id, 'fid' => 0))->select();
        
        if ($sort = D('Shop_goods_sort')->field(true)->where(array('sort_id' => $sortId, 'store_id' => $store_id))->find()) {
            if ($sort['fid']) {
                $this->error('暂时不支持子分类的查询');
            } else {
                $ids = $shopGoodsSortDB->getAllSonIds($sortId, $store_id);
                if ($ids) {
                    $where = ' AND d.sort_id IN (' . implode(',', $ids) . ')';
                    if ($stime && $etime) {
                        $where .= ' AND d.create_time>' . strtotime($stime) . ' AND d.create_time<' . strtotime($etime);
                    }
                    $sql = "SELECT count(1) as cnt, sum(d.num) as totalNum, sum(d.price) as totalPrice, sum(d.cost_price) as costPrice FROM " . C('DB_PREFIX') . 'shop_order AS o INNER JOIN ' . C('DB_PREFIX') . 'shop_order_detail AS d ON d.order_id=o.order_id WHERE o.paid=1 AND (o.pay_type<>"offline" OR (o.pay_type="offline" AND o.third_id>0)) AND o.status<>4 AND o.status<>5' . $where;
                    $totalList = M()->query($sql);
                    $count = isset($totalList[0]['cnt']) ? $totalList[0]['cnt'] : 0;
                    $totalPrice = isset($totalList[0]['totalPrice']) ? $totalList[0]['totalPrice'] : 0;
                    $totalNum = isset($totalList[0]['totalNum']) ? $totalList[0]['totalNum'] : 0;
                    $costPrice = isset($totalList[0]['costPrice']) ? $totalList[0]['costPrice'] : 0;
                    import('@.ORG.merchant_page');
                    $p = new Page($count, 20);
                    $sql = "SELECT `d`.* FROM " . C('DB_PREFIX') . 'shop_order AS o INNER JOIN ' . C('DB_PREFIX') . 'shop_order_detail AS d ON d.order_id=o.order_id WHERE o.paid=1 AND (o.pay_type<>"offline" OR (o.pay_type="offline" AND o.third_id>0)) AND o.status<>4 AND o.status<>5' . $where . ' LIMIT ' . $p->firstRow . ',' . $p->listRows;
                    $orders = M()->query($sql);
//                     $orders = D('Shop_order_detail')->field(true)->where($where)->select();
                }
                $this->assign(array('total_num' => $totalNum, 'total_price' => $totalPrice, 'cost_price' => $costPrice));
                $this->assign('order_list', $orders);
                $this->assign('sortId', $sortId);
                $this->assign('sort_list', $sortList);
                $this->assign('pagebar', $p->show());
                $this->display();
            }
        } else {
            $this->error('分类信息有误');
        }
    }
    
    public function sort_export()
    {
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '商品分类销量统计';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        
        $sortId = isset($_GET['sort_id']) ? intval($_GET['sort_id']) : 0;
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $now_store = $this->check_store($store_id);
        
        $stime = isset($_GET['stime']) && $_GET['stime'] ? htmlspecialchars($_GET['stime']) : '';
        $etime = isset($_GET['etime']) && $_GET['etime'] ? htmlspecialchars($_GET['etime']) : '';
        $shopGoodsSortDB = D('Shop_goods_sort');
        $sortList = $shopGoodsSortDB->field(true)->where(array('store_id' => $store_id, 'fid' => 0))->select();
        
        if ($sort = D('Shop_goods_sort')->field(true)->where(array('sort_id' => $sortId, 'store_id' => $store_id))->find()) {
            if ($sort['fid']) {
                return ;
            } else {
                $ids = $shopGoodsSortDB->getAllSonIds($sortId, $store_id);
                if ($ids) {
                    $where = ' AND d.sort_id IN (' . implode(',', $ids) . ')';
                    if ($stime && $etime) {
                        $where .= ' AND d.create_time>' . strtotime($stime) . ' AND d.create_time<' . strtotime($etime);
                    }
        
                    $sql = "SELECT `d`.* FROM " . C('DB_PREFIX') . 'shop_order AS o INNER JOIN ' . C('DB_PREFIX') . 'shop_order_detail AS d ON d.order_id=o.order_id WHERE o.paid=1 AND (o.pay_type<>"offline" OR (o.pay_type="offline" AND o.third_id>0)) AND o.status<>4 AND o.status<>5' . $where;
                    $orders = M()->query($sql);

                    $objExcel->setActiveSheetIndex(0);
                    $objExcel->getActiveSheet()->setTitle('商品销量详情');
                    $objActSheet = $objExcel->getActiveSheet();
                    $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                    $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                    $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                    $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                    $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                    
                    $objActSheet->setCellValue('A1', '商品名称');
                    $objActSheet->setCellValue('B1', '商品属性');
                    $objActSheet->setCellValue('C1', '数量');
                    $objActSheet->setCellValue('D1', '单价');
                    $objActSheet->setCellValue('E1', '销售时间');
                    $index = 1;
                    foreach ($orders as $order) {
                        $index++;
                        $objActSheet->setCellValueExplicit('A' . $index, $order['name']);
                        $objActSheet->setCellValueExplicit('B' . $index, $order['spec']);
                        $objActSheet->setCellValueExplicit('C' . $index, $order['num']);
                        $objActSheet->setCellValueExplicit('D' . $index, $order['discount_price'] > 0 ? $order['discount_price'] : $order['price']);
                        $objActSheet->setCellValueExplicit('E' . $index, date('Y-m-d H:i:s', $order['create_time']));
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
            }
        } else {
            return false;
        }
    }
    
    /**
     * 将商品发布到批发市场
     */
    public function goods_push()
    {
        $goodsId = intval($_GET['goods_id']);
        $database_shop_goods = D('Shop_goods');
        $where = array('goods_id' => $goodsId);
        $now_goods = $database_shop_goods->field(true)->where($where)->find();
        if(empty($now_goods)){
            $this->error('商品不存在！');
        }
        if ($now_goods['original_goods_id']) {
            $this->error('该商品不能发布！');
        }
        $now_store = $this->check_store($now_goods['store_id']);
        
        if(!empty($now_goods['image'])){
            $goods_image_class = new goods_image();
            $tmp_pic_arr = explode(';', $now_goods['image']);
            foreach ($tmp_pic_arr as $key => $value) {
                $now_goods['pic_arr'][$key]['title'] = $value;
                $now_goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
            }
        }
        
        $return = $database_shop_goods->format_spec_value($now_goods['spec_value'], $now_goods['goods_id']);
//         $now_goods['json'] = isset($return['json']) ? json_encode($return['json']) : '';
//         $now_goods['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
        $now_goods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
        $now_goods['list'] = isset($return['list']) ? $return['list'] : '';
        $now_goods['discount_info'] = '';
        $now_goods['status'] = 1;
//         echo '<pre/>';
//         print_r($now_goods);die;
        if ($marketGoodsOld = M('Market_goods')->where($where)->find()) {
            if ($marketGoodsOld['spec_value'] && $now_goods['list']) {
                $spec_array = explode('#', $marketGoodsOld['spec_value']);
                foreach ($spec_array as $str) {
                    $row_array = explode('|', $str);
                    $i = str_replace(':', '_', $row_array[0]);
//                     echo $i . '<br/>';
                    if (isset($now_goods['list'][$i])) {
                        $tArray = explode(':', $row_array[1]);
                        $now_goods['list'][$i]['wholesale_prices'] = $tArray[1];
                        $now_goods['list'][$i]['min_nums'] = $tArray[2];
                        $now_goods['list'][$i]['stocks'] = $tArray[3];
                    }
                }
            }
            
            $now_goods['wholesale_price'] = $marketGoodsOld['price'];
            $now_goods['min_num'] = $marketGoodsOld['min_num'];
            $now_goods['stock'] = $marketGoodsOld['stock_num'];
            $now_goods['discount_info'] = json_decode($marketGoodsOld['discount_info'], true);
            $now_goods['status'] = $marketGoodsOld['status'];
            $now_goods['cat_fid'] = $marketGoodsOld['cat_fid'];
            $now_goods['cat_id'] = $marketGoodsOld['cat_id'];
        }
        
        
        if (IS_POST) {
//             echo '<pre/>';
//             print_r($_POST);die;
            if ($now_goods['spec_list']) {
                $wholesale_prices = $_POST['wholesale_prices'];
                $min_nums = $_POST['min_nums'];
                $stocks = $_POST['stocks'];
                foreach ($wholesale_prices as $i => $val) {
                    if (!((empty($wholesale_prices[$i]) && empty($stocks[$i]) && empty($min_nums[$i])) || ($wholesale_prices[$i] && $stocks[$i] && $min_nums[$i]))) {
                        $error_tips .= '请正确设置每一种规格对应的批发参数<br/>';
                    }
                }
            } else {
                $wholesale_price = isset($_POST['wholesale_price']) ? floatval($_POST['wholesale_price']) : 0;
                if ($wholesale_price <= 0) {
                    $error_tips .= '请设置批发价且大于0<br/>';
                }
                $min_num = isset($_POST['min_num']) ? intval($_POST['min_num']) : 0;
                if ($min_num <= 0) {
                    $error_tips .= '请设置最低批发数且大于0<br/>';
                }
                $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
                if ($stock <= 0) {
                    $error_tips .= '请设置库存且大于0<br/>';
                }
            }
            $nums = $_POST['nums'];
            $discounts = $_POST['discounts'];
            $status = $this->config['market_goods_is_examine'] ? 0 : 1;
            
            if (empty($error_tips)) {
                $discount_info = array();
                for ($i = 0; $i < count($nums); $i ++) {
                    if ($nums[$i] && $discounts[$i]) {
                        $discount_info[] = array('num' => $nums[$i], 'discount' => $discounts[$i]);
                    }
                }
                $marketGoods = array('mer_id' => $this->merchant_session['mer_id'], 'last_time' => time());
                $marketGoods['store_id'] = $now_goods['store_id'];
                $marketGoods['goods_id'] = $now_goods['goods_id'];
                $marketGoods['name'] = $now_goods['name'];
                $marketGoods['number'] = $now_goods['number'];
                $marketGoods['unit'] = $now_goods['unit'];
                $marketGoods['image'] = $now_goods['image'];
                $marketGoods['des'] = $now_goods['des'];
                $marketGoods['is_properties'] = $now_goods['is_properties'];
                $marketGoods['status'] = $status;
                $marketGoods['discount_info'] = $discount_info ? json_encode($discount_info) : '';
                $marketGoods['cat_fid'] = isset($_POST['cat_fid']) ? intval($_POST['cat_fid']) : 0;
                $marketGoods['cat_id'] = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
                $marketGoods['province_id'] = isset($now_store['province_id']) ? intval($now_store['province_id']) : 0;
                $marketGoods['city_id'] = isset($now_store['city_id']) ? intval($now_store['city_id']) : 0;
                $marketGoods['area_id'] = isset($now_store['area_id']) ? intval($now_store['area_id']) : 0;
                
                if (empty($marketGoods['cat_fid']) || empty($marketGoods['cat_id'])) {
                    $this->error('请选择商品分类！');
                }
                if (empty($now_goods['spec_list'])) {
                    $marketGoods['min_num'] = $min_num;
                    $marketGoods['stock_num'] = $stock;
                    $marketGoods['price'] = $wholesale_price;
                }
                if ($marketGoodsOld) {
                    if ($mid = M('Market_goods')->where(array('mid' => $marketGoodsOld['mid']))->save($marketGoods)) {
                        $mid = $marketGoodsOld['mid'];
                        //删除已有的属性与规格的数据
                        M('Market_goods_properties')->where($where)->delete();
                        $spec_list = M('Market_goods_spec')->field(true)->where($where)->select();
                        foreach ($spec_list as $spec) {
                            M('Market_goods_spec_value')->where(array('sid' => $spec['id']))->delete();
                        }
                        M('Market_goods_spec')->where($where)->delete();
                    }
                } else {
                    $mid = M('Market_goods')->add($marketGoods);
                }
                
                if ($mid) {
                    if ($now_goods['is_properties']) {
                        $properties = M('Shop_goods_properties')->field(true)->where(array('goods_id' => $now_goods['goods_id']))->select();
                        foreach ($properties as $pro_data) {
                            M('Market_goods_properties')->add($pro_data);
                        }
                    }
                    
                    if ($now_goods['spec_value']) {
                        $spec_list = M('Shop_goods_spec')->field(true)->where(array('goods_id' => $now_goods['goods_id']))->select();
                        foreach ($spec_list as $spec) {
                            if (M('Market_goods_spec')->add($spec)) {
                                $spec_value_list = M('Shop_goods_spec_value')->field(true)->where(array('sid' => $spec['id']))->select();
                                foreach ($spec_value_list as $spec_value) {
                                    M('Market_goods_spec_value')->add($spec_value);
                                }
                            }
                        }
                        //[old] => 规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号 # 规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#...#规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号
                        //[new] => 规格值ID:规格值ID:...:规格值ID|old_price:price:min_num:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#
                        $spec_array = explode('#', $now_goods['spec_value']);
                        $target_spec_value_array = array();
                        $stock_num = 0;
                        $wholesale_price = 0;
                        $min_num = 0;
                        foreach ($spec_array as $str) {
                            $row_array = explode('|', $str);
                            $i = 'id_' . str_replace(':', '_id_', $row_array[0]);
                            $row_array[1] = $wholesale_prices[$i] . ':' . $wholesale_prices[$i] . ':' . $min_nums[$i] . ':' . $stocks[$i] . ':' . $wholesale_prices[$i];
                            
                            $marketGoods['min_num'] = $min_num;
                            if ($wholesale_price > 0) {
                                $wholesale_price = min($wholesale_price, $wholesale_prices[$i]);
                            } else {
                                $wholesale_price = $wholesale_prices[$i];
                            }
                            if ($min_num > 0) {
                                $min_num = min($min_num, $min_nums[$i]);
                            } else {
                                $min_num = $min_nums[$i];
                            }
                            
                            $stock_num += $stocks[$i];
                            
                            $target_spec_value_array[] = implode('|', $row_array);
                        }
                        $data = array('spec_value' => implode('#', $target_spec_value_array));
                        $data['min_num'] = $min_num;
                        $data['stock_num'] = $stock_num;
                        $data['price'] = $wholesale_price;
                        
                        M('Market_goods')->where(array('mid' => $mid))->save($data);
                    }
                    $this->success('发布成功！', U('Market/index'));
                } else {
                    $this->error('发布失败！请重试！');
                }
            } else {
                $this->error($error_tips);
            }
        } else {
            $category_list = D('Goods_wholesale_category')->get_list();
            $this->assign('category_list', json_encode($category_list));
            $this->assign('now_goods', $now_goods);
            $this->assign('now_store', $now_store);
            $this->display();
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
        $p = new Page($count, 5);
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
        $this->assign('page', $p->show());
        $this->display();
    }
    
    public function ajax_goods()
    {
        $goods_id = isset($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
        $number = isset($_GET['number']) ? htmlspecialchars(trim($_GET['number'])) : '';
        $systemGoods = D('System_goods');
        $condition = array();
        if ($goods_id) {
            $condition['goods_id'] = $goods_id;
        }
        if ($number) {
            $condition['number'] = $number;
        }
        if (empty($condition)) {
            exit(json_encode(array('error' => true, 'msg' => '请输入查询条件')));
        }
        $now_goods = $systemGoods->field(true)->where($condition)->find();
        if (empty($now_goods)) {
            exit(json_encode(array('error' => true, 'msg' => '商品不存在')));
        }
        if(!empty($now_goods['image'])){
            $goods_image_class = new goods_image();
            $tmp_pic_arr = explode(';', $now_goods['image']);
            $image = '';
            $title = '';
            foreach ($tmp_pic_arr as $key => $value) {
                if (empty($image)) {
                    $title = $value;
                    $image = $goods_image_class->get_image_by_path($value, 's');
                }
            }
            $now_goods['image'] = $image;
            $now_goods['imageTitle'] = $title;
        }
        exit(json_encode(array('error' => false, 'data' => $now_goods)));
    }
    
    
    public function goodsToFood()
    {
        
        if ($this->merchant_session['store_id']) {
            $this->error('您没有这个权限');
        }
        $source_store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $store_ids = isset($_POST['store_ids']) ? $_POST['store_ids'] : 0;
        
        if (empty($store_ids)) {
            $this->error('请选择要同步的店铺');
        }
        if ($store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $source_store_id, 'have_shop' => 1))->find()) {
            if (!($shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $source_store_id))->find())) {
                $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
            }
        } else {
            $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
        }
        
        $goods_image_class = new goods_image();
        
        foreach ($store_ids as $store_id) {
            if ($target_store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id, 'have_shop' => 1))->find()) {
                if (!($target_shop = M('Merchant_store_foodshop')->field(true)->where(array('store_id' => $store_id))->find())) {
                    continue;
                }
            } else {
                continue;
            }
            
            $goods_sorts = M('Shop_goods_sort')->field(true)->where(array('store_id' => $source_store_id))->select();
            foreach ($goods_sorts as $sv) {
                $oldIds[$sv['sort_id']] = $sv['fid'];//id ==> fid
            }
            $listOldFidToNewFid = array();
            foreach ($goods_sorts as $sort) {
                $source_sort_id = $sort['sort_id'];
                if (!($goods_list = M('Shop_goods')->field(true)->where(array('store_id' => $source_store_id, 'sort_id' => $source_sort_id))->select())) {
                    continue;
                }
                //目标分类
                if ($target_sort = M('Foodshop_goods_sort')->field(true)->where(array('store_id' => $store_id, 'sort_name' => $sort['sort_name']))->find()) {
                    $target_sort_id = $target_sort['sort_id'];
                } else {
                    $sort['store_id'] = $store_id;
                    unset($sort['sort_id']);

                    $target_sort_id = M('Foodshop_goods_sort')->add($sort);
                }
                $listOldFidToNewFid[$source_sort_id] = $target_sort_id;//oldID ==> newID
                
                foreach ($goods_list as $goods) {
                    if(!empty($goods['image'])){
                        $tmp_pic_arr = explode(';', $goods['image']);
                        foreach ($tmp_pic_arr as $key => $value) {
                            $image_tmp = explode(',', $value);
                            if (strstr($image_tmp[0], 'sysgoods_')) {
                                continue;
                            }
                            $images = $goods_image_class->get_image_by_path($value);
                            foreach ($images as $img) {
                                $img = str_replace(C('config.site_url'), '', $img);
                                $newFile = './upload/foodshop_goods/' . str_replace('/upload/goods/', '', $img);
                                dmkdir($newFile, 0777, false);
                                $t = copy('.' . $img, $newFile);
                            }
                        }
                    }
                    if ($tmp_goods = M('Foodshop_goods')->field(true)->where(array('name' => $goods['name'], 'store_id' => $store_id))->find()) {
                        continue;
                    } else {
                        $source_goods_id = $goods['goods_id'];
                        unset($goods['goods_id']);
                        $goods['store_id'] = $store_id;
                        $goods['sort_id'] = $target_sort_id;
                        $goods['print_id'] = 0;
                        $target_goods_id = M('Foodshop_goods')->add($goods);
                        $pro_map = $spec_map = $spec_value_map = array();
                        if ($goods['is_properties']) {
                            $properties = M('Shop_goods_properties')->field(true)->where(array('goods_id' => $source_goods_id))->select();
                            foreach ($properties as $pro_data) {
                                $source_pro_id = $pro_data['id'];
                                unset($pro_data['id']);
                                $pro_data['goods_id'] = $target_goods_id;
                                $pro_map[$source_pro_id] = M('Foodshop_goods_properties')->add($pro_data);
                            }
                        }
                        if ($goods['spec_value']) {
                            $spec_list = M('Shop_goods_spec')->field(true)->where(array('goods_id' => $source_goods_id, 'store_id' => $source_store_id))->select();
                            foreach ($spec_list as $spec) {
                                $source_spec_id = $spec['id'];
                                unset($spec['id']);
                                
                                $spec['store_id'] = $store_id;
                                $spec['goods_id'] = $target_goods_id;
                                
                                if ($new_spec_id = M('Foodshop_goods_spec')->add($spec)) {
                                    
                                    $spec_value_list = M('Shop_goods_spec_value')->field(true)->where(array('sid' => $source_spec_id))->select();
                                    foreach ($spec_value_list as $spec_value) {
                                        $source_spec_value_id = $spec_value['id'];
                                        unset($spec_value['id']);
                                        $spec_value['sid'] = $new_spec_id;
                                        $spec_value_map[$source_spec_value_id] = M('Foodshop_goods_spec_value')->add($spec_value);
                                    }
                                }
                            }
                            
                            //规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#
                            $spec_array = explode('#', $goods['spec_value']);
                            $target_spec_value_array = array();
                            foreach ($spec_array as $str) {
                                $row_array = explode('|', $str);
                                $spec_val_ids = explode(':', $row_array[0]);
                                $new_ids = array();
                                foreach ($spec_val_ids as $tid) {
                                    $new_ids[] = $spec_value_map[$tid];
                                }
                                $row_array[0] = implode(':', $new_ids);
                                if (count($row_array) > 2 && $row_array[2] && strstr($row_array[2], '=')) {
                                    $pro_str_ids = explode(':', $row_array[2]);
                                    $new_pro_ids = array();
                                    foreach ($pro_str_ids as $pstr) {
                                        $v_k_a = explode('=', $pstr);
                                        $new_pro_ids[] = $pro_map[$v_k_a[0]] . '=' . $v_k_a[1];
                                    }
                                    $row_array[2] = implode(':', $new_pro_ids);
                                }
                                $target_spec_value_array[] = implode('|', $row_array);
                            }
                            M('Foodshop_goods')->where(array('goods_id' => $target_goods_id))->save(array('spec_value' => implode('#', $target_spec_value_array)));
                        }
                    }
                }
            }
            $this->success('克隆完成');
        }
    }

    public function see_goods_qrcode()
    {
        $this->display();
    }

    public function cardqrcode()
    {
        import('@.ORG.phpqrcode');
        $href = $this->config['site_url'] . '/wap.php?c=Shop&a=detail&goods_id=' . $_GET['code'];
        QRcode::png($href, false, 2, 10, 2);
    }

    public function zbw()
    {
        $store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
        
        if ($store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id, 'have_shop' => 1))->find()) {
            if ($shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find()) {
            } else {
                $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name']);
            }
        } else {
            $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name']);
        }
        if (IS_POST) {
            $zbw_sBranchNo = isset($_POST['zbw_sBranchNo']) ? htmlspecialchars(trim($_POST['zbw_sBranchNo'])) : '';
            if (empty($zbw_sBranchNo)) {
                $this->error('机构编码不能为空');
            }
            if (D('Merchant_store_shop')->where(array('store_id' => $store_id))->save(array('zbw_sBranchNo' => $zbw_sBranchNo))) {
                $this->success('操作成功');
            } else {
                $this->error('操作失败，稍后重试');
            }
        } else {
            $this->assign('store_id', $store_id);
            $this->display();
        }
        
    }
    
    public function syncZbw()
    {
        $storeId = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $zbw = $this->getZbwObj();
        if (is_array($zbw)) {
            exit(json_encode($zbw));
        }
        $zbwInfo = D('Merchant_store_shop')->field('zbw_sBranchNo')->where(array('store_id' => $storeId))->find();
        if (empty($zbwInfo) || empty($zbwInfo['zbw_sBranchNo'])) {
            exit(json_encode(array('sCode' => 1, 'sError' => '请先配置对接的信息')));
        }
//         if ($zbwInfo) {
//             $zbw = new ZhiBaiWei();
//             if ($zbwInfo['expiresTime'] < time()) {
//                 $tokenResult = $zbw->getToken($zbwInfo['sAppKey'], $zbwInfo['sAppCode']);
//                 if ($tokenResult['sCode'] == 0) {
//                     D('Merchant_store_zbw')->where(array('storeId' => $storeId))->save(array('sToken' => $tokenResult['sToken'], 'expiresTime' => time() + 600));
//                     $zbw->setToken($tokenResult['sToken']);
//                 } else {
//                     exit(json_encode($tokenResult));
//                     exit(json_encode(array('error' => true, 'msg' => '获取token失败')));
//                 }
//             } else {
//                 $zbw->setToken($zbwInfo['sToken']);
//             }
            
            //分类同步处理
            $shopGoodsSortDB = D('Shop_goods_sort');
            $page = 1;
            while ($page) {
                $clsList = $zbw->getItemCls($page);
                if ($clsList['sCode'] == 0) {
                    if (!empty($clsList['ItemClss'])) {
                        foreach ($clsList['ItemClss'] as $item) {
                            if ($tempSort = $shopGoodsSortDB->field(true)->where(array('clsNo' => $item['clsNo'], 'store_id' => $storeId))->find()) {
                                continue;
                            }
                            $fclsNo = substr($item['clsNo'], 0, -2);
                            $fid = 0;
                            if (!empty($fclsNo)) {
                                if ($thisSort = $shopGoodsSortDB->field(true)->where(array('clsNo' => $fclsNo, 'store_id' => $storeId))->find()) {
                                    $level = $thisSort['level'] + 1;
                                    $fid = $thisSort['sort_id'];
                                    if ($thisSort['operation_type'] != 1) {
                                        $shopGoodsSortDB->where(array('clsNo' => $fclsNo, 'store_id' => $storeId))->save(array('operation_type' => 1));
                                    }
                                } else {
                                    $level = 1;
                                }
                            } else {
                                $level = 1;
                            }
                            
                            $data = array('store_id' => $storeId, 'status' => 1);
                            $data['sort_name'] = $item['clsName'];
                            $data['clsNo'] = $item['clsNo'];
                            $data['level'] = $level;
                            $data['fid'] = $fid;
                            if ($level == 3) {
                                $data['operation_type'] = 0;
                            } else {
                                $data['operation_type'] = 2;
                            }
                            $shopGoodsSortDB->add($data);
                        }
                        $page ++;
                    } else {
                        $page = 0;
                    }
                } else {
                    $page = 0;
                    exit(json_encode($clsList));
                }
            }
            exit(json_encode(array('sCode' => 0, 'sError' => 'ok')));
//         } else {
//             exit(json_encode(array('sCode' => 1, 'sError' => '请先配置对接的信息')));
//         }
    }
    
    
    private function getZbwObj()
    {
        $list = D('Config')->field('name, value')->where(array('name' => array('in', array('zbw_sAppKey', 'zbw_sAppCode', 'zbw_sToken', 'zbw_expiresTime'))))->select();
        $config = array();
        foreach ($list as $row) {
            $config[$row['name']] = $row['value'];
        }
        
        $zbw = new ZhiBaiWei();
        if ($config['zbw_expiresTime'] < time()) {
            $tokenResult = $zbw->getToken($config['zbw_sAppKey'], $config['zbw_sAppCode']);
            if ($tokenResult['sCode'] == 0) {
                D('Config')->where(array('name' => 'zbw_sToken'))->save(array('value' => $tokenResult['sToken']));
                D('Config')->where(array('name' => 'zbw_expiresTime'))->save(array('value' => time() + 600));
                
                $zbw->setToken($tokenResult['sToken']);
            } else {
                return $tokenResult;
            }
        } else {
            $zbw->setToken($config['zbw_sToken']);
        }
        return $zbw;
    }
    public function syncZbwGoods()
    {
        $storeId = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        
        $zbw = $this->getZbwObj();
        if (is_array($zbw)) {
            exit(json_encode($zbw));
        }
        $zbwInfo = D('Merchant_store_shop')->field('zbw_sBranchNo')->where(array('store_id' => $storeId))->find();
        if (empty($zbwInfo) || empty($zbwInfo['zbw_sBranchNo'])) {
            exit(json_encode(array('sCode' => 1, 'sError' => '请先配置对接的信息')));
        }
//         if ($zbwInfo) {
//             $zbw = new ZhiBaiWei();
//             if ($zbwInfo['expiresTime'] < time()) {
//                 $tokenResult = $zbw->getToken($zbwInfo['sAppKey'], $zbwInfo['sAppCode']);
//                 if ($tokenResult['sCode'] == 0) {
//                     D('Merchant_store_zbw')->where(array('storeId' => $storeId))->save(array('sToken' => $tokenResult['sToken'], 'expiresTime' => time() + 600));
//                     $zbw->setToken($tokenResult['sToken']);
//                 } else {
//                     exit(json_encode($tokenResult));
//                     exit(json_encode(array('error' => true, 'msg' => '获取token失败')));
//                 }
//             } else {
//                 $zbw->setToken($zbwInfo['sToken']);
//             }
            
            //商品同步处理
            $shopGoodsSortDB = D('Shop_goods_sort');
            $shopGoodsDB = D('Shop_goods');
            $page = isset($_POST['page']) ? intval($_POST['page']) : 0;
            $last_time = time();
            $goodsList = $zbw->getGoods($page);
            if ($goodsList['sCode'] == 0) {
                if (!empty($goodsList['goodsIds'])) {
                    $goodsIdLIst = array();
                    foreach ($goodsList['goodsIds'] as $item) {
                        $goodsIdLIst[] = $item['goodsId'];
                        if ($tempGoods = $shopGoodsDB->field(true)->where(array('zbwId' => $item['goodsId'], 'store_id' => $storeId))->find()) {
                            continue;
                        }
                        $sort_id = 0;
                        if ($thisSort = $shopGoodsSortDB->field(true)->where(array('clsNo' => $item['clsNo'], 'store_id' => $storeId))->find()) {
                            $sort_id = $thisSort['sort_id'];
                            if ($thisSort['operation_type'] != 0) {
                                $shopGoodsSortDB->where(array('clsNo' => $item['clsNo'], 'store_id' => $storeId))->save(array('operation_type' => 0));
                            }
                        }
                        
                        $data = array('store_id' => $storeId);
                        $data['name'] = $item['name'];
                        $data['number'] = $item['barcode'];
                        $data['zbwId'] = $item['goodsId'];
                        $data['unit'] = $item['unit'];
                        $data['price'] = $item['salePrice'];
                        $data['sort_id'] = $sort_id;
                        $data['last_time'] = $last_time;
                        $shopGoodsDB->add($data);
                    }
                    
                    $stockList = $zbw->goodsStock($goodsIdLIst, $zbwInfo['zbw_sBranchNo']);
                    if ($stockList['sCode'] == 0 && !empty($stockList['stocks'])) {
                        foreach ($stockList['stocks'] as $stock) {
                            $shopGoodsDB->where(array('zbwId' => $stock['goodsId'], 'store_id' => $storeId))->save(array('stock_num' => $stock['stockCnt']));
                        }
                    }
                    $page ++;
                    exit(json_encode(array('sCode' => 0, 'sError' => 'ok', 'page' => $page)));
                }
            }
            exit(json_encode(array('sCode' => 0, 'sError' => 'ok', 'page' => 0)));
//         } else {
//             exit(json_encode(array('sCode' => 1, 'sError' => '请先配置对接的信息')));
//         }
    }
    
    public function test()
    {
        $zbw = $this->getZbwObj();
        $stockList = $zbw->getItemImage('PK02534');
        echo '<pre/>';
        print_r($stockList);die;
    }
	
	public function shop_fitment_color(){
		if(IS_POST){
			$now_store = $this->check_store($_POST['store_id']);

			$condition['store_id']		= $_POST['store_id'];
			$data['shop_fitment_color'] = $_POST['shop_fitment_color'];
			$data['last_time']			= time();
			if(M('Merchant_store_shop')->where($condition)->data($data)->save()){
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
		}else{
			$now_store = $this->check_store($_GET['store_id']);
			$this->assign('now_store',$now_store);
		}

		$this->display();
	}
	public function shop_fitment_showcase(){
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store',$now_store);

		$condition['store_id']	= $_GET['store_id'];
		$subject_info = M('Merchant_store_shop_data')->where($condition)->find();
		$this->assign('subject_info',$subject_info);

		$goodIdArr = explode(',',$subject_info['showcase_good_id']);
		$goodArr = M('Shop_goods')->field('`goods_id`,`name`')->where(array('goods_id'=>array('in',$goodIdArr)))->select();
		$this->assign('goodArr',$goodArr);

		$this->display();
	}
	public function shop_fitment_showcase_save(){
		$now_store = $this->check_store($_POST['store_id']);

		$condition['store_id']		= $_POST['store_id'];
		$data['shop_showcase_show'] = $_POST['showcase_show'];
		$data['last_time']			= time();
		if(M('Merchant_store_shop')->where($condition)->data($data)->save()){
			$condition_data['store_id'] = $_POST['store_id'];
			$data_data['showcase_name']	= $_POST['showcase_name'];
			$data_data['showcase_good_id']	= implode(',',$_POST['good_id']);
			if(!$data_data['showcase_good_id']){
				$data_data['showcase_good_id'] = array();
			}
			$data_data['last_time']			= time();
			if(M('Merchant_store_shop_data')->where($condition)->find()){
				if(M('Merchant_store_shop_data')->where($condition_data)->data($data_data)->save()){
					$this->success('保存成功！');
				}else{
					$this->error('保存失败！');
				}
			}else{
				$data_data['store_id'] = $_POST['store_id'];
				if(M('Merchant_store_shop_data')->data($data_data)->add()){
					$this->success('保存成功。');
				}else{
					$this->error('保存失败。');
				}
			}
		}else{
			$this->error('保存失败!.');
		}
	}
	public function shop_fitment_subject(){
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store',$now_store);

		$condition['store_id']	= $_GET['store_id'];
		$subject_info = M('Merchant_store_shop_data')->where($condition)->find();
		$this->assign('subject_info',$subject_info);

		$goodIdArr = explode(',',$subject_info['subject_good_id']);
		$goodArr = M('Shop_goods')->field('`goods_id`,`name`')->where(array('goods_id'=>array('in',$goodIdArr)))->select();
		$this->assign('goodArr',$goodArr);

		$this->display();
	}
	public function shop_fitment_history(){
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store',$now_store);

		if(IS_POST){
			$condition['store_id'] = $now_store['store_id'];
			$data['shop_brand_weipage'] = $_POST['shop_brand_weipage'];
			$data['last_time'] = time();
			if(M('Merchant_store_shop')->where($condition)->data($data)->save()){
				$this->success('保存成功！');
			}else{
				$this->error('保存失败！');
			}
		}else{
			$diypage_list = M('Merchant_store_diypage')->field('`page_id`,`page_name`')->where(array('mer_id'=>$this->mer_id,'is_remove'=>'0'))->select();
			$this->assign('diypage_list',$diypage_list);

			$this->display();
		}
	}
	public function shop_fitment_subject_save(){
		$now_store = $this->check_store($_POST['store_id']);

		$condition['store_id']		= $_POST['store_id'];
		$data['shop_subject_show'] = $_POST['subject_show'];
		$data['last_time']			= time();
		if(M('Merchant_store_shop')->where($condition)->data($data)->save()){
			$condition_data['store_id'] = $_POST['store_id'];
			$data_data['subject_name']	= $_POST['subject_name'];
			$data_data['subject_pic']	= $_POST['subject_pic'];
			$data_data['subject_good_id']	= implode(',',$_POST['good_id']);
			if(!$data_data['subject_good_id']){
				$data_data['subject_good_id'] = array();
			}
			$data_data['last_time']			= time();
			if(M('Merchant_store_shop_data')->where($condition)->find()){
				if(M('Merchant_store_shop_data')->where($condition_data)->data($data_data)->save()){
					$this->success('保存成功');
				}else{
					$this->error('保存失败！');
				}
			}else{
				$data_data['store_id'] = $_POST['store_id'];
				if(M('Merchant_store_shop_data')->data($data_data)->add()){
					$this->success('保存成功');
				}else{
					$this->error('保存失败。');
				}
			}
		}else{
			$this->error('保存失败！.');
		}
	}
	public function shop_fitment_build_subject_pic(){
		$this->display();
	}
	public function shop_fitment_build_subject_pic_func(){
		if(empty($_POST)){
			$_POST = array (
			  'img' => './static/images/shop_fitment/showcase_img1.png',
			  'big_left' => '50',
			  'big_top' => '35',
			  'big_font' => '40',
			  'small_left' => '50',
			  'small_top' => '116',
			  'small_font' => '24',
			  'good_left' => '426',
			  'good_top' => '22',
			  'good_width' => '232',
			  'good_height' => '132',
			  'big_color' => '#432D0A',
			  'small_color' => '#68502E',
			  'type' => '1',
			  'good_img_width' => '510',
			  'good_img_height' => '383',
			  'good_img_src' => 'http://www.group.com/upload/goods/000/000/001/m_5b9ca7bb56170374.jpg',
			  'big_name' => '优选食材精品推荐',
			  'small_name' => '满60减20 满20减10',
			  'big_color_arr' =>
			  array (
				0 => '67',
				1 => '45',
				2 => '10',
			  ),
			  'small_color_arr' =>
			  array (
				0 => '104',
				1 => '80',
				2 => '46',
			  ),
			  'store_id' => '356',
			);
		}
		$now_store = $this->check_store($_POST['store_id']);
		$bgImgSrc = $_POST['img'];
		$bgImgSrc = str_replace($this->config['site_url'],'',$bgImgSrc);
		$bgImgSrc = ltrim($bgImgSrc,'/');
		$bgImgSrc = ltrim($bgImgSrc,'./');

		$goodImgSrc = $_POST['good_img_src'];
		$goodImgSrc = str_replace($this->config['site_url'],'',$goodImgSrc);
		$goodImgSrc = ltrim($goodImgSrc,'/');
		$goodImgSrc = ltrim($goodImgSrc,'./');

		//画布
		$font = './static/fonts/apple_lihei.otf';
		$fontBold = './static/fonts/apple_lihei_bold.otf';

		$img = imagecreatetruecolor(686,176);
		$white  =  imagecolorallocate ( $img ,  255 ,  255 ,  255 );
		imagefill ( $img ,  0 ,   0 ,  $white );

		//背景图片绘制
			$src_im = imagecreatefrompng($bgImgSrc);
			imagecopy($img,$src_im,0,0,0,0,686,176);

		//写主标题
		$fontColor = imagecolorallocate ($img, $_POST['big_color_arr'][0], $_POST['big_color_arr'][1], $_POST['big_color_arr'][2]);
		$fontSize = intval($_POST['big_font']*0.7);
		imagettftext($img, $fontSize, 0, $_POST['big_left'], $_POST['big_top']+35, $fontColor, $fontBold, $_POST['big_name']);
		// imagettftext($img, $fontSize, 0, $_POST['big_left']+1, $_POST['big_top']+36, $fontColor, $font, $_POST['big_name']);

		//写副标题
		$fontColor = imagecolorallocate ($img, $_POST['small_color_arr'][0], $_POST['small_color_arr'][1], $_POST['small_color_arr'][2]);
		$fontSize = intval($_POST['small_font']*0.7);
		$fontTop = $_POST['small_top']+22;
		if($_POST['type'] == '2'){
			$fontTop+= 2;
		}
		imagettftext($img, $fontSize, 0, $_POST['small_left'], $fontTop , $fontColor, $font, $_POST['small_name']);

		//商品图片绘制
			$info   = getimagesize($goodImgSrc);
			$fun    = 'imagecreatefrom'.image_type_to_extension($info[2], false);
			$src_im =  call_user_func_array($fun,array($goodImgSrc));


			//创建新图像
				$newimg = imagecreatetruecolor($_POST['good_width'],$_POST['good_height']);
				// 调整默认颜色
				$color = imagecolorallocate($newimg, 255, 255, 255);
				imagefill($newimg, 0, 0, $color);
				//裁剪
				//等比例缩小高度居中
				$goodImageTop = intval($info[1] - ($info[0]*$_POST['good_height']/$_POST['good_width']));
				// echo $goodImageTop;die;
				imagecopyresampled($newimg, $src_im, 0, 0, 0, $goodImageTop/2, $_POST['good_width'],$_POST['good_height'],$info[0],$info[1]-$goodImageTop);
				imagedestroy($src_im); //销毁原图
				imagecopy($img,$newimg,$_POST['good_left'],$_POST['good_top'],0,0,$_POST['good_width'],$_POST['good_height']);

		// header('Content-type: image/png');
		// imagepng($img);
		// die;
		$img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
		$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

		$fileName = 'upload/store/'.$rand_num;
		if(!file_exists($fileName)){
			mkdir($fileName,0777,true);
		}
		$fileName.= '/'.$_POST['store_id'].'_subject.png';
		if(imagepng($img,$fileName)){
			$this->success($fileName);
		}else{
			$this->error('生成失败，请重试');
		}
	}
	public function shop_fitment_category_hot(){
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store',$now_store);

		$condition['store_id']	= $_GET['store_id'];
		$subject_info = M('Merchant_store_shop_data')->where($condition)->find();
		$this->assign('subject_info',$subject_info);

		$goodIdArr = explode(',',$subject_info['cat_hot_good_id']);
		$goodArr = M('Shop_goods')->field('`goods_id`,`name`')->where(array('goods_id'=>array('in',$goodIdArr)))->select();
		$this->assign('goodArr',$goodArr);

		$this->display();
	}
	public function shop_fitment_category_hot_save(){
		$now_store = $this->check_store($_POST['store_id']);

		$condition_data['store_id'] = $_POST['store_id'];
		$data_data['cat_hot_name']	= $_POST['cat_hot_name'];
		$data_data['cat_hot_desc']	= $_POST['cat_hot_desc'];
		$data_data['cat_hot_sort']	= $_POST['cat_hot_sort'];
		$data_data['cat_hot_status']	= $_POST['cat_hot_status'];
		$data_data['cat_hot_good_id']	= implode(',',$_POST['good_id']);
		if(!$data_data['cat_hot_good_id']){
			$data_data['cat_hot_good_id'] = '';
		}
		$data_data['last_time']			= time();
		if(M('Merchant_store_shop_data')->where($condition)->find()){
			if(M('Merchant_store_shop_data')->where($condition_data)->data($data_data)->save()){
				$this->success('保存成功！');
			}else{
				$this->error('保存失败！');
			}
		}else{
			$data_data['store_id'] = $_POST['store_id'];
			if(M('Merchant_store_shop_data')->data($data_data)->add()){
				$this->success('保存成功。');
			}else{
				$this->error('保存失败。');
			}
		}
	}
	public function shop_fitment_category_discount(){
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store',$now_store);

		$condition['store_id']	= $_GET['store_id'];
		$subject_info = M('Merchant_store_shop_data')->where($condition)->find();
		$this->assign('subject_info',$subject_info);

		$goodIdArr = explode(',',$subject_info['cat_discount_good_id']);
		$goodArr = M('Shop_goods')->field('`goods_id`,`name`')->where(array('goods_id'=>array('in',$goodIdArr)))->select();
		$this->assign('goodArr',$goodArr);

		$this->display();
	}
	public function shop_fitment_category_discount_save(){
		$now_store = $this->check_store($_POST['store_id']);

		$condition_data['store_id'] = $_POST['store_id'];
		$data_data['cat_discount_name']	= $_POST['cat_discount_name'];
		$data_data['cat_discount_desc']	= $_POST['cat_discount_desc'];
		$data_data['cat_discount_sort']	= $_POST['cat_discount_sort'];
		$data_data['cat_discount_status']	= $_POST['cat_discount_status'];
		$data_data['cat_discount_good_id']	= implode(',',$_POST['good_id']);
		if(!$data_data['cat_discount_good_id']){
			$data_data['cat_discount_good_id'] = '';
		}
		$data_data['last_time']			= time();
		if(M('Merchant_store_shop_data')->where($condition)->find()){
			if(M('Merchant_store_shop_data')->where($condition_data)->data($data_data)->save()){
				$this->success('保存成功！');
			}else{
				$this->error('保存失败！');
			}
		}else{
			$data_data['store_id'] = $_POST['store_id'];
			if(M('Merchant_store_shop_data')->data($data_data)->add()){
				$this->success('保存成功。');
			}else{
				$this->error('保存失败。');
			}
		}
	}
}