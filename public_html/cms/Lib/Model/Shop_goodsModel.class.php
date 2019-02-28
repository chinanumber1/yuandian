<?php
class Shop_goodsModel extends Model
{
	public $defaultSendTime=0; // getSelectTime方法里的 默认的期望送达时间。用于传递值使用
    public function saveSpec($goods, $store_id)
    {
        $delete_spec_ids = array();
        $delete_spec_val_ids = array();
        $delete_properties_ids = array();
        
        $spec_obj = M('Shop_goods_spec'); //规格表
        $spec_value_obj = M('Shop_goods_spec_value');//规格对应的属性值
        $properties_obj = M('Shop_goods_properties');//属性表
        $goods_id = $goods['goods_id'];
        if ($check_data = $this->field(true)->where(array('store_id' => $store_id, 'goods_id' => $goods_id))->find()) {
            //查找已有的属性和规格
            $old_spec = $spec_obj->field(true)->where(array('goods_id' => $goods_id, 'store_id' => $store_id))->select();
            foreach ($old_spec as $os) {
                $delete_spec_ids[] = $os['id'];
            }
            if ($delete_spec_ids) {
                $old_spec_val = $spec_value_obj->field(true)->where(array('sid' => array('in', $delete_spec_ids)))->select();
                foreach ($old_spec_val as $osv) {
                    $delete_spec_val_ids[] = $osv['id'];
                }
            }
            $old_properties = $properties_obj->field(true)->where(array('goods_id' => $goods_id))->select();
            foreach ($old_properties as $op) {
                $delete_properties_ids[] = $op['id'];
            }
            unset($old_spec, $old_spec_val, $old_properties);
        } else {
            return false;
        }
        
        //配置属性
        $properties = array();
        $spec = array();
        $list = array();
        
        $data_spec = array('store_id' => $store_id, 'goods_id' => $goods_id);
        foreach ($goods['spec_id'] as $k => $id) {//规格id集合
            $name = $data_spec['name'] = isset($goods['specs'][$k]) ? $goods['specs'][$k] : '';//规格名称
            $spec_val_id = isset($goods['spec_val_id'][$k]) ? $goods['spec_val_id'][$k] : array();//规格属性值的ID集合
            $spec_val = isset($goods['spec_val'][$k]) ? $goods['spec_val'][$k] : array();//规格属性值的名称集合
            
            $list[$k] = array();
            if ($spec_obj->field(true)->where(array('id' => $id, 'goods_id' => $goods_id))->find()) {
                $spec_obj->where(array('id' => $id))->save($data_spec);
            } else {
                $id = $spec_obj->add($data_spec);
            }
            if ($id) {//规格id
                $delete_spec_ids = array_diff($delete_spec_ids, array($id));
                
                $data_spec_val = array('sid' => $id);
                foreach ($spec_val_id as $i => $vid) {
                    $data_spec_val['name'] = $spec_val[$i];
                    if ($spec_value_obj->where(array('id' => $vid, 'sid' => $id))->find()) {
                        $spec_value_obj->where(array('id' => $vid))->save($data_spec_val);
                    } else {
                        $vid = $spec_value_obj->add($data_spec_val);
                    }
                    if ($vid) {
                        $delete_spec_val_ids = array_diff($delete_spec_val_ids, array($vid));
                        // 						$list[$k][$i] = array('spec_id' => $id, 'spec_name' => $name, 'spec_val_id' => $vid, 'spec_val_name' => $spec_val[$i]);
                        $list[$k][$i] = $vid;
                    }
                }
            }
        }
        $spec_value = array();
        $this->format_str($list, 0, array(), $spec_value);
        
        
        
        $properties = array();
        $is_properties = 0;
        foreach ($goods['properties_id'] as $pi => $pid) {//属性id集合
            $is_properties = 1;
            $name = isset($goods['properties'][$pi]) ? $goods['properties'][$pi] : '';//属性名称
            $num = isset($goods['properties_num'][$pi]) ? intval($goods['properties_num'][$pi]) : 1;//属性值可选的数量
            
            foreach ($goods['properties_val'][$pi] as $key => &$gpv) {
                if (isset($goods['properties_val_status_' . $pi . '_' . $key])) {
                    $gpv .= ':1';
                } else {
                    $gpv .= ':0';
                }
            }
            $val = isset($goods['properties_val'][$pi]) ? implode(',', $goods['properties_val'][$pi]) : '';//属性的属性值
            
            if ($properties_obj->field(true)->where(array('goods_id' => $goods_id, 'id' => $pid))->find()) {
                $properties_obj->where(array('goods_id' => $goods_id, 'id' => $pid))->save(array('name' => $name, 'val' => $val, 'num' => $num));
            } else {
                $pid = $properties_obj->add(array('goods_id' => $goods_id, 'name' => $name, 'val' => $val, 'num' => $num));
            }
            if ($pid) {
                $delete_properties_ids = array_diff($delete_properties_ids, array($pid));
                
                $properties[] = array('id' => $pid, 'name' => $name, 'val' => $val);
            }
        }
        
        $specs = '';
        $pre = '';
        foreach ($spec_value as $k => $v) {
            $old_price = isset($goods['old_prices'][$k]) ? $goods['old_prices'][$k] : 0;
            $cost_price = isset($goods['cost_prices'][$k]) ? $goods['cost_prices'][$k] : 0;
            $number = isset($goods['numbers'][$k]) ? $goods['numbers'][$k] : '';
            $price = isset($goods['prices'][$k]) ? $goods['prices'][$k] : 0;
            $seckill_price = isset($goods['seckill_prices'][$k]) ? floatval($goods['seckill_prices'][$k]) : 0;
            $stock_num = isset($goods['stock_nums'][$k]) ? intval($goods['stock_nums'][$k]) : 0;
            $max_num = isset($goods['max_nums'][$k]) ? intval($goods['max_nums'][$k]) : 0;
            $old_price = $old_price ? $old_price : $price;
            $specs .= $pre . $v . '|' . $old_price . ':' . $price . ':' . $seckill_price . ':' . $stock_num . ':' . $cost_price  .  ':' . $max_num  . '|';
            if ($properties) {
                // 				$specs .= '|';
                $ppre = '';
                foreach ($properties as $ti => $ps) {
                    $num = isset($goods['num' . $ti][$k]) && $goods['num' . $ti][$k] ? intval($goods['num' . $ti][$k]) : 1;
                    $specs .= $ppre . $ps['id'] . '=' . $num;
                    $ppre = ':';
                }
            }
            $number && $specs .= '|' . $number;
            $pre = '#';
        }
        
        //2016-11-08新增系统的商品分类属性值与商品直接的关系▽
        D('Goods_properties_relation')->where(array('gid' => $goods_id))->delete();
        if (isset($goods['goodsproperties'])) {
            foreach ($goods['goodsproperties'] as $pid) {
                D('Goods_properties_relation')->add(array('gid' => $goods_id, 'pid' => $pid));
            }
        }
        //2016-11-08新增系统的商品分类属性值与商品直接的关系△
        
        //74:77|1:1:1:1:0:1|6=1#74:78|1:1:1:1:0:1|6=1#74:79|1:1:1:1:0:1|6=1#75:77|1:1:1:1:0:1|6=1#75:78|1:1:1:1:0:1|6=1#75:79|1:1:1:1:0:1|6=1#76:77|1:1:1:1:0:1|6=1#76:78|1:1:1:1:0:1|6=1#76:79|1:1:1:1:0:1|6=1
        //规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price:max_num|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号 # 规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#...#规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号
        if ($this->where(array('goods_id' => $goods_id))->save(array('spec_value' => $specs, 'is_properties' => $is_properties, 'last_time' => time()))) {
            $delete_spec_ids && $spec_obj->where(array('id' => array('in', $delete_spec_ids)))->delete();
            $delete_spec_val_ids && $spec_value_obj->where(array('id' => array('in', $delete_spec_val_ids)))->delete();
            $delete_properties_ids && $properties_obj->where(array('id' => array('in', $delete_properties_ids)))->delete();
            //配置属性
            return $goods_id;
        } else {
            return false;
        }
    }
	public function save_post_form($goods, $store_id)
	{
		$goods_id = isset($goods['goods_id']) ? intval($goods['goods_id']) : 0;
		
		$data = array('name' => $goods['name']);
		$data['unit'] = $goods['unit'];
		$data['old_price'] = empty($goods['old_price']) ? $goods['price'] : $goods['old_price'];
		$data['cost_price'] = empty($goods['cost_price']) ? 0 : $goods['cost_price'];
		$data['price'] = $goods['price'];
		$data['extra_pay_price'] = floatval($goods['extra_pay_price']);
		$data['seckill_price'] = $goods['seckill_price'];
		$data['seckill_stock'] = $goods['seckill_stock'];
		$data['seckill_type'] = $goods['seckill_type'];
		$data['seckill_open_time'] = $goods['seckill_open_time'];
		$data['seckill_close_time'] = $goods['seckill_close_time'];
		$data['show_start_time'] = isset($goods['show_start_time']) ? $goods['show_start_time'] : '00:00:00';
		$data['show_end_time'] = isset($goods['show_end_time']) ? $goods['show_end_time'] : '00:00:00';
		$data['packing_charge'] = $goods['packing_charge'];
		$data['stock_num'] = $goods['stock_num'];
		$data['original_stock'] = $goods['stock_num'];
		$data['sort'] = $goods['sort'];
		$data['status'] = $goods['status'];
		$data['print_id'] = $goods['print_id'];
		$data['sort_id'] = $goods['sort_id'];
		$data['des'] = $goods['des'];
		$data['image'] = $goods['pic'];
		$data['number'] = $goods['number'];
		$data['store_id'] = $store_id;
		$data['score_max'] = isset($goods['score_max']) ? $goods['score_max'] : 0;
		$data['score_percent'] = isset($goods['score_percent']) ? $goods['score_percent'] : 0;

		$data['freight_template'] = intval($goods['freight_template']);
		$data['freight_type'] = intval($goods['freight_type']);
		$data['freight_value'] = floatval($goods['freight_value']);
		$data['max_num'] = intval($goods['max_num']);
		$data['min_num'] = intval($goods['min_num']);
		$data['limit_type'] = isset($goods['limit_type']) ? intval($goods['limit_type']) : 0;
		$data['is_discount'] = isset($goods['is_discount']) ? intval($goods['is_discount']) : 1;
		$data['is_use_coupon'] = isset($goods['is_use_coupon']) ? intval($goods['is_use_coupon']) : 1;
		
		$data['last_time'] = time();
		
		//2016-11-08新增系统的商品分类▽
		$data['cat_fid'] = intval($goods['cat_fid']);
		$data['cat_id'] = intval($goods['cat_id']);
		//2016-11-08新增系统的商品分类△
		
		$delete_spec_ids = array();
		$delete_spec_val_ids = array();
		$delete_properties_ids = array();
		
		$spec_obj = M('Shop_goods_spec'); //规格表
		$spec_value_obj = M('Shop_goods_spec_value');//规格对应的属性值
		$properties_obj = M('Shop_goods_properties');//属性表
		
		if ($check_data = $this->field(true)->where(array('store_id' => $store_id, 'goods_id' => $goods_id))->find()) {
			if ($this->where(array('store_id' => $store_id, 'goods_id' => $goods_id))->save($data)) {
				//查找已有的属性和规格
				$old_spec = $spec_obj->field(true)->where(array('goods_id' => $goods_id, 'store_id' => $store_id))->select();
				foreach ($old_spec as $os) {
					$delete_spec_ids[] = $os['id'];
				}
				if ($delete_spec_ids) {
					$old_spec_val = $spec_value_obj->field(true)->where(array('sid' => array('in', $delete_spec_ids)))->select();
					foreach ($old_spec_val as $osv) {
						$delete_spec_val_ids[] = $osv['id'];
					}
				}
				$old_properties = $properties_obj->field(true)->where(array('goods_id' => $goods_id))->select();
				foreach ($old_properties as $op) {
					$delete_properties_ids[] = $op['id'];
				}
				unset($old_spec, $old_spec_val, $old_properties);
			} else {
				return false;
			}
		} else {
			$goods_id = $this->add($data);
			if (empty($goods_id)) return false;
		}

		//配置属性
		$properties = array();
		$spec = array();
		$list = array();
		
		$data_spec = array('store_id' => $store_id, 'goods_id' => $goods_id);
		foreach ($goods['spec_id'] as $k => $id) {//规格id集合
			$name = $data_spec['name'] = isset($goods['specs'][$k]) ? $goods['specs'][$k] : '';//规格名称
			$spec_val_id = isset($goods['spec_val_id'][$k]) ? $goods['spec_val_id'][$k] : array();//规格属性值的ID集合
			$spec_val = isset($goods['spec_val'][$k]) ? $goods['spec_val'][$k] : array();//规格属性值的名称集合
			
			$list[$k] = array();
			if ($spec_obj->field(true)->where(array('id' => $id, 'goods_id' => $goods_id))->find()) {
				$spec_obj->where(array('id' => $id))->save($data_spec);
			} else {
				$id = $spec_obj->add($data_spec);
			}
			if ($id) {//规格id
				$delete_spec_ids = array_diff($delete_spec_ids, array($id));
				
				$data_spec_val = array('sid' => $id);
				foreach ($spec_val_id as $i => $vid) {
					$data_spec_val['name'] = $spec_val[$i];
					if ($spec_value_obj->where(array('id' => $vid, 'sid' => $id))->find()) {
						$spec_value_obj->where(array('id' => $vid))->save($data_spec_val);
					} else {
						$vid = $spec_value_obj->add($data_spec_val);
					}
					if ($vid) {
						$delete_spec_val_ids = array_diff($delete_spec_val_ids, array($vid));
// 						$list[$k][$i] = array('spec_id' => $id, 'spec_name' => $name, 'spec_val_id' => $vid, 'spec_val_name' => $spec_val[$i]);
						$list[$k][$i] = $vid;
					}
				}
			}
		}
		$spec_value = array();
		$this->format_str($list, 0, array(), $spec_value);
		


		$properties = array();
		$is_properties = 0;
		foreach ($goods['properties_id'] as $pi => $pid) {//属性id集合
			$is_properties = 1;
			$name = isset($goods['properties'][$pi]) ? $goods['properties'][$pi] : '';//属性名称
			$num = isset($goods['properties_num'][$pi]) ? intval($goods['properties_num'][$pi]) : 1;//属性值可选的数量
			
			foreach ($goods['properties_val'][$pi] as $key => &$gpv) {
			    if (isset($goods['properties_val_status_' . $pi . '_' . $key])) {
			        $gpv .= ':1';
			    } else {
			        $gpv .= ':0';
			    }
			}
			$val = isset($goods['properties_val'][$pi]) ? implode(',', $goods['properties_val'][$pi]) : '';//属性的属性值
			
			if ($properties_obj->field(true)->where(array('goods_id' => $goods_id, 'id' => $pid))->find()) {
				$properties_obj->where(array('goods_id' => $goods_id, 'id' => $pid))->save(array('name' => $name, 'val' => $val, 'num' => $num));
			} else {
				$pid = $properties_obj->add(array('goods_id' => $goods_id, 'name' => $name, 'val' => $val, 'num' => $num));
			}
			if ($pid) {
				$delete_properties_ids = array_diff($delete_properties_ids, array($pid));
				
				$properties[] = array('id' => $pid, 'name' => $name, 'val' => $val);
			}
		}		
		
		$specs = '';
		$pre = '';
		foreach ($spec_value as $k => $v) {
			$old_price = isset($goods['old_prices'][$k]) ? $goods['old_prices'][$k] : 0;
			$cost_price = isset($goods['cost_prices'][$k]) ? $goods['cost_prices'][$k] : 0;
			$number = isset($goods['numbers'][$k]) ? $goods['numbers'][$k] : '';
			$price = isset($goods['prices'][$k]) ? $goods['prices'][$k] : 0;
			$seckill_price = isset($goods['seckill_prices'][$k]) ? floatval($goods['seckill_prices'][$k]) : 0;
			$stock_num = isset($goods['stock_nums'][$k]) ? intval($goods['stock_nums'][$k]) : 0;
			$max_num = isset($goods['max_nums'][$k]) ? intval($goods['max_nums'][$k]) : 0;
			$old_price = $old_price ? $old_price : $price;
			$specs .= $pre . $v . '|' . $old_price . ':' . $price . ':' . $seckill_price . ':' . $stock_num . ':' . $cost_price  .  ':' . $max_num  . '|';
			if ($properties) {
// 				$specs .= '|';
				$ppre = '';
				foreach ($properties as $ti => $ps) {
					$num = isset($goods['num' . $ti][$k]) && $goods['num' . $ti][$k] ? intval($goods['num' . $ti][$k]) : 1;
					$specs .= $ppre . $ps['id'] . '=' . $num;
					$ppre = ':';
				}
			}
			$number && $specs .= '|' . $number;
			$pre = '#';
		}
		
		//2016-11-08新增系统的商品分类属性值与商品直接的关系▽
		D('Goods_properties_relation')->where(array('gid' => $goods_id))->delete();
		if (isset($goods['goodsproperties'])) {
			foreach ($goods['goodsproperties'] as $pid) {
				D('Goods_properties_relation')->add(array('gid' => $goods_id, 'pid' => $pid));
			}
		}
		//2016-11-08新增系统的商品分类属性值与商品直接的关系△
		
		//
		//规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price:max_num|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号 # 规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#...#规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号
		if ($this->where(array('goods_id' => $goods_id))->save(array('spec_value' => $specs, 'is_properties' => $is_properties, 'last_time' => $data['last_time'] + 1))) {
			$delete_spec_ids && $spec_obj->where(array('id' => array('in', $delete_spec_ids)))->delete();
			$delete_spec_val_ids && $spec_value_obj->where(array('id' => array('in', $delete_spec_val_ids)))->delete();
			$delete_properties_ids && $properties_obj->where(array('id' => array('in', $delete_properties_ids)))->delete();
			//配置属性
			return $goods_id;
		} else {
			return false;
		}
		
		
	}	
	
	private function format_str($a, $i, $str, &$return)
	{
		if ($i == 0) {
			$ii = $i + 1;
			foreach ($a[$i] as $val) {
				$t = $str ? $str . ':' : '';
				if ($ii == count($a)) {
					$return[] = $t . $val;
				} else {
					$this->format_str($a, $ii, $t . $val, $return);
				}
			}
		} else if ($i == count($a) - 1) {
			foreach ($a[$i] as $val) {
				$t = $str ? $str . ':' : '';
				$return[] = $t . $val;
			}
		} else {
			$ii = $i + 1;
			foreach ($a[$i] as $val) {
				$t = $str ? $str . ':' : '';
				$this->format_str($a, $ii, $t . $val, $return);
			}
		}
	}
	
	private function format_html($a, $i, $str, &$return)
	{
		if ($i == 0) {
			$ii = $i + 1;
			foreach ($a[$i] as $val) {
				$ta = $str;
				array_push($ta, $val);
				if ($ii == count($a)) {
					$return[] = $ta;
				} else {
					$this->format_html($a, $ii, $ta, $return);
				}
			}
		} else if ($i == count($a) - 1) {
			foreach ($a[$i] as $val) {
				$ta = $str;
				array_push($ta, $val);
				$return[] = $ta;
			}
		} else {
			$ii = $i + 1;
			foreach ($a[$i] as $val) {
				$ta = $str;
				array_push($ta, $val);
				$this->format_html($a, $ii, $ta, $return);
			}
		}
	}
	
	public function format_spec_value($str, $goods_id, $is_prorerties = 1, $minNum = 0)
	{
// 		if (empty($str)) return false;

		if ($is_prorerties || $str) {
			$properties_obj = M('Shop_goods_properties');
			$goods_properties_temp = $properties_obj->field(true)->where(array('goods_id' => $goods_id))->order('id asc')->select();
			$goods_properties_list = array();
			$goods_properties_status_list = array();
			foreach ($goods_properties_temp as $goods_p) {
// 				$goods_p['val'] = explode(',', $goods_p['val']);
				$vals = explode(',', $goods_p['val']);
				$value = array();
				$valueStatus = array();
				foreach ($vals as $val) {
				    $valArr = explode(':', $val);
				    if (!(isset($valArr[1]) && $valArr[1] == 0)) {
				        $value[] = $valArr[0];
				        $valueStatus[] = array($valArr[0], 1);
				    } else {
				        $valueStatus[] = array($valArr[0], 0);
				    }
				}
				$goods_p['val'] = $value;
				$goods_p['val_status'] = $valueStatus;
				$goods_properties_status_list[$goods_p['id']] = $goods_p;
				if ($value) {
				    $goods_properties_list[$goods_p['id']] = $goods_p;
				}
			}
			unset($goods_properties_temp);
		}
		
		if ($str) {
			$spec_obj = M('Shop_goods_spec'); //规格表
			$spec_value_obj = M('Shop_goods_spec_value');//规格对应的属性值
			$goods_spec_temp = $spec_obj->field(true)->where(array('goods_id' => $goods_id))->order('id ASC')->select();
			$goods_spec_list = array();
			$specids = array();
			foreach ($goods_spec_temp as $goods_t) {
				$specids[] = $goods_t['id'];
				$goods_spec_list[$goods_t['id']] = $goods_t;
			}
			unset($goods_spec_temp);
			$spec_valuse_list = array();
			if ($specids) {
				$spec_valuse_temp = $spec_value_obj->field(true)->where(array('sid' => array('in', $specids)))->order('id ASC')->select();
				foreach ($spec_valuse_temp as $v_temp) {
					$spec_valuse_list[$v_temp['id']] = $v_temp;
					$goods_spec_list[$v_temp['sid']]['list'][$v_temp['id']] = $v_temp;
				}
				unset($spec_valuse_temp, $specids);
			}
			
			$return = array();
			$json = array();
// 		if ($str) {
			$spec_array = explode('#', $str);
			$p_ids = array();
			$is_sort = true;
			$new_goods_spec_list = array();
			foreach ($spec_array as $row) {
				$row_array = explode('|', $row);
				$spec_ids = explode(':', $row_array[0]);
				$t_index = '';
				$t_pre = '';
				$spec_data = array();
				foreach ($spec_ids as $id) {
					$t_index .= $t_pre . 'id_' . $id;
					$t_pre = '_';
					$spec_data[] = array(
						'spec_val_id' => $id, 
						'spec_val_name' => isset($spec_valuse_list[$id]['name']) ? $spec_valuse_list[$id]['name'] : '',
						'spec_val_sid' => isset($spec_valuse_list[$id]['sid']) ? $spec_valuse_list[$id]['sid'] : '',
					);
					if ($is_sort && isset($spec_valuse_list[$id]['sid']) && isset($goods_spec_list[$spec_valuse_list[$id]['sid']])) {
						$new_goods_spec_list[] = $goods_spec_list[$spec_valuse_list[$id]['sid']];
					}
				}
				$is_sort = false;
				$index = implode('_', $spec_ids);
				
				$return[$index]['index'] = $t_index;
				$return[$index]['spec'] = $spec_data;
				
				$prices = explode(':', $row_array[1]);
				$return[$index]['old_price'] = floatval($prices[0]);
				$return[$index]['price'] = floatval($prices[1]);
				$return[$index]['seckill_price'] = floatval($prices[2]);
				$return[$index]['stock_num'] = $prices[3];
				$return[$index]['cost_price'] = isset($prices[4]) ? $prices[4] : 0;
				if ($minNum > 1) {
				    $return[$index]['max_num'] = 0;
				} else {
				    $return[$index]['max_num'] = isset($prices[5]) ? $prices[5] : 0;
				}
				
				
				$return[$index]['number'] = isset($row_array[3]) ? $row_array[3] : '';
				
				if (isset($row_array[2]) && $row_array[2] && strstr($row_array[2], '=')) {
					$p_data = array();
					$tdata = array();
					$properties = explode(':', $row_array[2]);
					foreach ($properties as $k => $pro) {
						$pro_array = explode('=', $pro);
						$p_data[] = array('id' => intval($pro_array[0]), 'num' => intval($pro_array[1]), 'name' => isset($goods_properties_status_list[$pro_array[0]]['name']) ? $goods_properties_status_list[$pro_array[0]]['name'] : '');
						$tdata['num' . $k . '[]'] = $pro_array[1];
					}
					$return[$index]['properties'] = $p_data;
					$json[$t_index] = $tdata;
				}
				if (empty($return[$index]['number']) && isset($row_array[2]) && $row_array[2] && !strstr($row_array[2], '=')) {
					$return[$index]['number'] = $row_array[2];
				}
				$json[$t_index]['old_prices[]'] = $prices[0];
				$json[$t_index]['prices[]'] = $prices[1];
				$json[$t_index]['seckill_prices[]'] = $prices[2];
				$json[$t_index]['stock_nums[]'] = $prices[3];
				$json[$t_index]['cost_prices[]'] = isset($prices[4]) ? floatval($prices[4]) : 0;
				$json[$t_index]['max_nums[]'] = isset($prices[5]) ? floatval($prices[5]) : 0;
				$json[$t_index]['numbers[]'] = isset($row_array[3]) ? $row_array[3] : '';
			}
		}
		
		$data = array();
		$new_goods_spec_list && $data['spec_list'] = $new_goods_spec_list;
		$goods_properties_list && $data['properties_list'] = $goods_properties_list;
		$goods_properties_status_list && $data['properties_status_list'] = $goods_properties_status_list;
		$return && $data['list'] = $return;
		$json && $data['json'] = $json;
		return $data = $data ? $data : null;
// 		return array('spec_list' => $goods_spec_list, 'properties_list' => $goods_properties_list, 'list' => $return, 'json' => $json);
	}

    public function get_list_by_storeid_diypage($store_id)
    {
        $where = array('store_id' => $store_id, 'status' => 1);
        if ($_POST['keyword']) {
            $where['name'] = array('like', '%' . $_POST['keyword'] . '%');
        }
        $count = $this->where($where)->count();
        import('@.ORG.diypage');
        $Page = new Page($count, 8);
        
        $good_list = $this->field(true)
            ->where($where)
            ->order('`last_time` DESC')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        
        $goods_image_class = new goods_image();
        $timeNow = time();
        $goodsList = array();
        foreach ($good_list as $value) {
            // 新增限时显示
            if (! ($value['show_start_time'] == $value['show_end_time'] || ($value['show_start_time'] == '00:00:00' && $value['show_end_time'] == '23:59:00'))) {
                $st = strtotime(date('Y-m-d') . ' ' . $value['show_start_time']);
                $et = strtotime(date('Y-m-d') . ' ' . $value['show_end_time']);
                if (! ($st <= $timeNow && $timeNow <= $et)) {
                    continue;
                }
            }
            $tmp_pic_arr = explode(';', $value['image']);
            foreach ($tmp_pic_arr as $k => $v) {
                $value['pic_arr'][$k]['title'] = $v;
                $value['pic_arr'][$k]['url'] = $goods_image_class->get_image_by_path($v);
            }
            $goodsList[] = $value;
        }
        return array(
            'good_list' => $goodsList,
            'page_bar' => $Page->show()
        );
    }
    
	public function getGoodsBySortIds($sortIds, $store_id, $isShow = false, $order = 'sort DESC, goods_id ASC',$is_shop=0)
    {
        if (count($sortIds) < 1) {
            return null;
        } elseif (count($sortIds) == 1) {
            $where = array('sort_id' => $sortIds[0], 'store_id' => $store_id);
        } else {
            $where = array('sort_id' => array('in', $sortIds), 'store_id' => $store_id);
        }
        
        $sort_list = D('Shop_goods_sort')->field(true)->where($where)->order('`sort` DESC,`sort_id` ASC')->select();

        if (!$isShow) {
            $where['status'] = 1;
        }
        if(C('config.is_show_jd_goods')==0){
            $where['jd_sku_id'] = array('eq',0);
        }elseif(C('config.is_show_shop_jd')==0 && $is_shop=1){
            $where['jd_sku_id'] = array('eq',0);
        }
        $g_list = $this->field(true)->where($where)->order($order)->select();
        $goods_image_class = new goods_image();
        $timeNow = time();
        $today = date('Ymd');
        $goodsList = array();
        foreach ($g_list as $row) {
            //新增限时显示
            if (empty($isShow) && !($row['show_start_time'] == $row['show_end_time'] || ($row['show_start_time'] == '00:00:00' && $row['show_end_time'] == '23:59:00'))) {
                $st = strtotime(date('Y-m-d') . ' ' . $row['show_start_time']);
                $et = strtotime(date('Y-m-d') . ' ' . $row['show_end_time']);
                if (!($st <= $timeNow && $timeNow <= $et)) {
                    continue;
                }
            }
            
			if ($row['seckill_type'] == 1) {
                $now_time = date('H:i');
                $open_time = date('H:i', $row['seckill_open_time']);
                $close_time = date('H:i', $row['seckill_close_time']);
                
                // 秒杀库存的计算
                if ($today == $row['sell_day']) {
                    $seckill_stock_num = $row['seckill_stock'] == - 1 ? - 1 : (intval($row['seckill_stock'] - $row['today_seckill_count']) > 0 ? intval($row['seckill_stock'] - $row['today_seckill_count']) : 0);
                } else {
                    $seckill_stock_num = $row['seckill_stock'];
                }
            } else {
                $now_time = time();
                $open_time = $row['seckill_open_time'];
                $close_time = $row['seckill_close_time'];
                $seckill_stock_num = $row['seckill_stock'] == - 1 ? - 1 : (intval($row['seckill_stock'] - $row['today_seckill_count']) > 0 ? intval($row['seckill_stock'] - $row['today_seckill_count']) : 0);
            }
			
            $row['is_seckill_price'] = false;
            $row['o_price'] = floatval($row['price']);
            if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0 && $seckill_stock_num != 0) {
                $row['price'] = floatval($row['seckill_price']);
                $row['is_seckill_price'] = true;
            } else {
                $row['price'] = floatval($row['price']);
            }

            $row['old_price'] = floatval($row['old_price']);
            $row['seckill_price'] = floatval($row['seckill_price']);
            $tmp_pic_arr = explode(';', $row['image']);
            foreach ($tmp_pic_arr as $key => $value) {
                if(false === strpos($value,',')){
                    $row['pic_arr'][$key]['title'] = $value;
                    $row['pic_arr'][$key]['url'] = 'http://img13.360buyimg.com/n0/'.$value;
                }else{
                    $row['pic_arr'][$key]['title'] = $value;
                    $row['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value);
                }

            }
			$row['image'] = $row['pic_arr'][0]['url']['s_image'];
            	
            $return = $this->format_spec_value($row['spec_value'], $row['goods_id'], $row['is_properties']);
            $row['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
            $row['properties_status_list'] = isset($return['properties_status_list']) ? $return['properties_status_list'] : '';
            $row['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
            $row['list'] = isset($return['list']) ? $return['list'] : '';
			
			$row['is_new'] = (($_SERVER['REQUEST_TIME'] - $row['last_time']) > 864000) ? 0 : 1;
			
			$row['seckill_stock'] = $seckill_stock_num;
			if($row['seckill_stock'] > 0 && $row['is_seckill_price']){
				$row['stock_num'] = $row['seckill_stock'];
			}
			if($row['seckill_stock'] == 0){
				$row['is_seckill_price'] = false;
			}
			
			if(!$row['spec_list']){
				if($row['min_num'] > 0 && $row['stock_num'] >= 0 && $row['min_num'] > $row['stock_num']){
					$row['stock_num'] = 0;
				}
				if($row['min_num'] > 0 && $row['seckill_stock'] >= 0 && $row['min_num'] > $row['seckill_stock']){
					$row['seckill_stock'] = 0;
				}
			}
			
            $goodsList[$row['sort_id']][] = $row;
            
        }
        
        $s_list = array();
        $today = date('w');
        foreach ($sort_list as $value) {
            if (!empty($value['is_weekshow']) && !$isShow) {
                $week_arr = explode(',', $value['week']);
                if (!in_array($today, $week_arr)) {
                    continue;
                }
            }
            if (isset($goodsList[$value['sort_id']])) {
                $value['goods_list'] = $goodsList[$value['sort_id']];
                $s_list[] = $value;
            }
        }
        
        return $s_list;
    }
    
	public function get_list_by_storeid($store_id, $sort = 0, $keyword = '',$is_shop=0)
	{
		$database_goods_sort = D('Shop_goods_sort');
		$condition_goods_sort['store_id'] = $store_id;
// 		$count_sort = $database_goods_sort->where($condition_goods_sort)->count();
		$sort_list = $database_goods_sort->field(true)->where($condition_goods_sort)->order('`sort` DESC,`sort_id` ASC')->select();
		$sort_image_class = new goods_sort_image();
		$s_list = array();
		$today = date('w');
		foreach ($sort_list as $value) {
			if (!empty($value['is_weekshow'])) {
				$week_arr = explode(',',$value['week']);
				if (!in_array($today, $week_arr)) {
					continue;
				}
				$week_str = '';
				foreach ($week_arr as $k=>$v){
					$week_str .= $this->get_week($v).' ';
				}
				$value['week_str'] = $week_str;
			}
			$value['see_image'] = $sort_image_class->get_image_by_path($value['image'],C('config.site_url'),'s');
			$s_list[$value['sort_id']] = $value;
		}

		$goods_image_class = new goods_image();
        if(C('config.is_show_jd_goods')==0){
            $condition =  array('store_id' => $store_id, 'status' => 1,'jd_sku_id'=>0);
        }else{
            $condition =  array('store_id' => $store_id, 'status' => 1);
            if(C('config.is_show_shop_jd')==0 && $is_shop==1){
                $condition =  array('store_id' => $store_id, 'status' => 1,'jd_sku_id'=>0);
            }
        }
		if ($sort == 1) {
			$g_list = $this->field(true)->where($condition)->order('sell_count DESC, goods_id ASC')->select();
		} elseif ($sort == 2) {
			$g_list = $this->field(true)->where($condition)->order('price DESC, goods_id ASC')->select();
		} elseif ($keyword != '') {
            if(C('config.is_show_jd_goods')==0){
                $g_list = $this->field(true)->where(array('store_id' => $store_id, 'name' => array('like', '%' . $keyword . '%'), 'status' => 1,'jd_sku_id'=>0))->order('sort DESC, goods_id ASC')->select();
            }elseif(C('config.is_show_shop_jd')==0 && $is_shop==1){
                $g_list = $this->field(true)->where(array('store_id' => $store_id, 'name' => array('like', '%' . $keyword . '%'), 'status' => 1,'jd_sku_id'=>0))->order('sort DESC, goods_id ASC')->select();
            }else{
                $g_list = $this->field(true)->where(array('store_id' => $store_id, 'name' => array('like', '%' . $keyword . '%'), 'status' => 1))->order('sort DESC, goods_id ASC')->select();
            }
		} else {
			$g_list = $this->field(true)->where($condition)->order('sort DESC, goods_id ASC')->select();
		}
		
		$today = date('Ymd');
		$storeShop = D('Merchant_store_shop')->where(array('store_id' => $store_id))->find();
		$stock_type = $storeShop['stock_type'];
		$sort_result = array();
		
		$timeNow = time();
		foreach ($g_list as $row) {
		    //新增限时显示
		    if (!($row['show_start_time'] == $row['show_end_time'] || ($row['show_start_time'] == '00:00:00' && $row['show_end_time'] == '23:59:00'))) {
		        $st = strtotime(date('Y-m-d') . ' ' . $row['show_start_time']);
		        $et = strtotime(date('Y-m-d') . ' ' . $row['show_end_time']);
		        if (!($st <= $timeNow && $timeNow <= $et)) {
		            continue;
		        }
		    }
		    
		    $row['sell_day'] = $stock_type ? $today : $row['sell_day'];
			if ($row['seckill_type'] == 1) {
				$now_time = date('H:i');
				$open_time = date('H:i', $row['seckill_open_time']);
				$close_time = date('H:i', $row['seckill_close_time']);
				//秒杀库存的计算
				if ($today == $row['sell_day']) {
				    $seckill_stock_num = $row['seckill_stock'] == -1 ? -1 : (intval($row['seckill_stock'] - $row['today_seckill_count']) > 0 ? intval($row['seckill_stock'] - $row['today_seckill_count']) : 0);
				} else {
				    $seckill_stock_num = $row['seckill_stock'];
				}
			} else {
				$now_time = time();
				$open_time = $row['seckill_open_time'];
				$close_time = $row['seckill_close_time'];
				$seckill_stock_num = $row['seckill_stock'] == -1 ? -1 : (intval($row['seckill_stock'] - $row['today_seckill_count']) > 0 ? intval($row['seckill_stock'] - $row['today_seckill_count']) : 0);
			}
			$row['is_seckill_price'] = false;
			$row['o_price'] = floatval($row['price']);
			
			if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0 && $seckill_stock_num != 0) {
				$row['price'] = floatval($row['seckill_price']);
				$row['is_seckill_price'] = true;
			} else {
				$row['price'] = floatval($row['price']);
			}

// 			$row['price'] = floatval($row['price']);
			$row['old_price'] = floatval($row['old_price']);
			$row['seckill_price'] = floatval($row['seckill_price']);
			$tmp_pic_arr = explode(';', $row['image']);
			foreach ($tmp_pic_arr as $key => $value) {
				$row['pic_arr'][$key]['title'] = $value;
                if(false === strpos($value,',')){
                    $row['pic_arr'][$key]['url'] = 'http://img13.360buyimg.com/n0/'.$value;
                }else{
                    $row['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value);
                }
			}
			$return = $this->format_spec_value($row['spec_value'], $row['goods_id'], $row['is_properties']);
// 			$row['json'] = isset($return['json']) ? json_encode($return['json']) : '';
			$row['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
			$row['properties_status_list'] = isset($return['properties_status_list']) ? $return['properties_status_list'] : '';
			$row['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
			$row['list'] = isset($return['list']) ? $return['list'] : '';
			
			if ($sort == 1 || $sort == 2 || $keyword != '') {//pc端的排序与搜索
				$sort_result[] = $row;
			} else {
				if (isset($s_list[$row['sort_id']])) {
					if (isset($s_list[$row['sort_id']]['goods_list'])) {
						$s_list[$row['sort_id']]['goods_list'][] = $row;
					} else {
						$s_list[$row['sort_id']]['goods_list'] = array($row);
					}
				}
			}
		}
		if ($sort == 1 || $sort == 2 || $keyword != '') {//pc端的排序与搜索
			$s_list = array(array('goods_list' => $sort_result, 'sort_id' => false));
		} else {
			foreach ($s_list as $k => $r) {
				if (!isset($r['goods_list'])) {
					unset($s_list[$k]);
				}
			}
		}
		return $s_list;
	}
	
	protected function get_week($num){
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
	
	public function get_goods_by_id($goods_id)
	{
		$now_goods = $this->field(true)->where(array('goods_id' => $goods_id))->find();
		if(empty($now_goods)){
			return false;
		}
		//如果设置了最小起购，限购就无效
		if ($now_goods['min_num'] > 1) {
		    $now_goods['max_num'] = 0;
		} else {
		    $now_goods['max_num'] = $now_goods['max_num'];
		}
		
		$shop = D('Merchant_store_shop')->where(array('store_id' => $now_goods['store_id']))->find();
		if (empty($shop)) return false; 
		$stock_type = $shop['stock_type'];
		if(!empty($now_goods['image'])){
			$goods_image_class = new goods_image();
			$tmp_pic_arr = explode(';', $now_goods['image']);
			foreach ($tmp_pic_arr as $key => $value) {
				$now_goods['pic_arr'][$key]['title'] = $value;
                if(false === strpos($value,',')){
                    $now_goods['pic_arr'][$key]['url'] = 'http://img13.360buyimg.com/n0/'.$value;
                }else{
                    $now_goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 'm');
                }
			}
			$now_goods['image'] = $now_goods['pic_arr'][0]['url'];
		}

		if ($now_goods['seckill_type'] == 1) {
			$now_time = date('H:i');
			$open_time = date('H:i', $now_goods['seckill_open_time']);
			$close_time = date('H:i', $now_goods['seckill_close_time']);
			
			//秒杀库存的计算
			$today = date('Ymd',time());
			if ($today == $now_goods['sell_day']) {
				$seckill_stock_num = $now_goods['seckill_stock'] == -1 ? -1 : (intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) > 0 ? intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) : 0);
			} else {
				$seckill_stock_num = $now_goods['seckill_stock'];
			}
		} else {
			$now_time = time();
			$open_time = $now_goods['seckill_open_time'];
			$close_time = $now_goods['seckill_close_time'];
			$seckill_stock_num = $now_goods['seckill_stock'] == -1 ? -1 : (intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) > 0 ? intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) : 0);
		}
		$now_goods['is_seckill_price'] = false;
		$now_goods['o_price'] = floatval($now_goods['price']);
		$now_goods['old_price'] = floatval($now_goods['price']);
		if ($open_time < $now_time && $now_time < $close_time && floatval($now_goods['seckill_price']) > 0 && $seckill_stock_num != 0) {
			$now_goods['price'] = floatval($now_goods['seckill_price']);
			$now_goods['is_seckill_price'] = true;
		} else {
			$now_goods['price'] = floatval($now_goods['price']);
		}
		$now_goods['seckill_price'] = floatval($now_goods['seckill_price']);
		$now_goods['packing_charge'] = floatval($now_goods['packing_charge']);
		
		
		$return = $this->format_spec_value($now_goods['spec_value'], $now_goods['goods_id'], $now_goods['is_properties'], $now_goods['min_num']);
		$now_goods['json'] = isset($return['json']) ? json_encode($return['json']) : '';
		$now_goods['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
		$now_goods['properties_status_list'] = isset($return['properties_status_list']) ? $return['properties_status_list'] : '';
		$now_goods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
		$now_goods['list'] = isset($return['list']) ? $return['list'] : '';
		
		$today = date('Ymd');
		
		$now_goods['sell_day'] = $stock_type ? $today : $now_goods['sell_day'];
		$now_goods['today_sell_spec'] = json_decode($now_goods['today_sell_spec'], true);
//		if($now_goods['spec_value']!=''){
//			$now_goods['extra_pay_price']=0;
//		}
		if($now_goods['extra_pay_price']>0){
			$now_goods['extra_pay_price_name']='元宝';
		}
		
		if ($now_goods['is_seckill_price']) {
			$now_goods['stock_num'] = $seckill_stock_num;
		} else {
// 			if ($now_goods['sell_day'] == $today) {
// 				$now_goods['stock_num'] = $now_goods['stock_num'] == -1 ? -1 : (intval($now_goods['stock_num'] - $now_goods['today_sell_count']) > 0 ? intval($now_goods['stock_num'] - $now_goods['today_sell_count']) : 0);
// 			}
			if ($now_goods['sell_day'] != $today) {
				$now_goods['stock_num'] = $now_goods['original_stock'];
			}
		}
		foreach ($now_goods['list'] as $key => &$row) {
			if ($now_goods['is_seckill_price']) {
				$row['stock_num'] = $seckill_stock_num;
			} else {
				$t_count = isset($now_goods['today_sell_spec'][$key]) ? intval($now_goods['today_sell_spec'][$key]) : 0;
				if ($now_goods['sell_day'] == $today) {
					$row['stock_num'] = $row['stock_num'] == -1 ? -1 : (intval($row['stock_num'] - $t_count) > 0 ? intval($row['stock_num'] - $t_count) : 0);
				}
			}
		}
		$template_id = intval($now_goods['freight_template']);
		if ($template_id) {
			if ($min = D('Express_template_value')->field(true)->where(array('tid' => $template_id, 'full_money' => array('gt', 0)))->find('freight')) {
				$min = 0;
			} else {
				$min = D('Express_template_value')->where(array('tid' => $template_id))->min('freight');
				$min = min($min, $now_goods['freight_value']);
			}
			$max = D('Express_template_value')->where(array('tid' => $template_id))->max('freight');
			$max = max($max, $now_goods['freight_value']);
			if ($min < $max) {
				$now_goods['deliver_fee'] = floatval($min) . '~' . floatval($max);
			} else {
				$now_goods['deliver_fee'] = floatval($min);
			}
		} else {
			$now_goods['deliver_fee'] = floatval($now_goods['freight_value']);
		}
		$nowtime = time();
		$now_goods['is_new'] = (($nowtime - $now_goods['last_time']) > 864000) ? 0 : 1;
		
		if(!$now_goods['spec_list']){
			if($now_goods['min_num'] > 0 && $now_goods['stock_num'] >= 0 && $now_goods['min_num'] > $now_goods['stock_num']){
				$now_goods['stock_num'] = 0;
			}
			if($now_goods['min_num'] > 0 && $now_goods['seckill_stock'] >= 0 && $now_goods['min_num'] > $now_goods['seckill_stock']){
				$now_goods['seckill_stock'] = 0;
			}
		}
		
		return $now_goods;
	}
	
    /**
     * 获取每个用户对某个商品的购买量
     * 
     */
    public function getBuyGoodsNum($uid, $goods_id)
    {
        $sql = "SELECT sum(d.num) as num FROM " . C('DB_PREFIX') . "shop_order as o INNER JOIN " . C('DB_PREFIX') . "shop_order_detail as d ON o.order_id=d.order_id AND o.uid={$uid} WHERE  (o.status <4 OR o.status>5 ) AND d.goods_id={$goods_id}";
        $res = $this->query($sql);
        $num = isset($res[0]['num']) ? intval($res[0]['num']) : 0;
        return $num;
    }
	/**
	 * 检查库存
	 * @param int $store_id
	 * @param int $goods_id
	 * @param int $num
	 * @param string $spec_ids = 'id_id'
	 * @return multitype:number string |multitype:number unknown |multitype:number string Ambigous <number, mixed>
	 */
	public function check_stock($goods_id, $num, $spec_ids = '', $stock_type = 0, $store_id = 0, $is_market = false, $uid = 0)
	{
		if ($store_id) {
			$now_goods = $this->field(true)->where(array('goods_id' => $goods_id, 'store_id' => $store_id))->find();
		} else {
			$now_goods = $this->field(true)->where(array('goods_id' => $goods_id))->find();
		}
		if (empty($now_goods)) return array('status' => 0, 'msg' => '商品不存在');
		if ($now_goods['min_num'] > 0 && $num < $now_goods['min_num']) {
		    return array('status' => 0, 'msg' => '商品【' . $now_goods['name'] . '】每单' . $now_goods['min_num'] . $now_goods['unit'] . '起购');
		}
		$image = '';
		if(!empty($now_goods['image'])){
			$goods_image_class = new goods_image();
			$tmp_pic_arr = explode(';', $now_goods['image']);
			foreach ($tmp_pic_arr as $key => $value) {
				if (empty($image)) {
				    if(false === strpos($value,',')){
                        $image = 'http://img13.360buyimg.com/n2/'.$value;
                        break;
                    }else{
                        $image = $goods_image_class->get_image_by_path($value, 's');
                        break;
                    }

				}
			}
		}
		$stock_num = 0;
		if ($now_goods['status'] != 1 && $is_market === false) return array('status' => 0, 'msg' => $now_goods['name'] . '商品已下架');
		$today = date('Ymd');
		//商品的库存类型（0：每日更新相同的库存，1:商品的总库存不会自动更新）
		$now_goods['sell_day'] = $stock_type ? $today : $now_goods['sell_day'];
		
		if ($now_goods['seckill_type'] == 1) {//秒杀类型（0：固定时间段，1：每天的时间段）
			$now_time = date('H:i');
			$open_time = date('H:i', $now_goods['seckill_open_time']);
			$close_time = date('H:i', $now_goods['seckill_close_time']);
			//秒杀库存的计算			
			if ($today == $now_goods['sell_day']) {
				$seckill_stock_num = $now_goods['seckill_stock'] == -1 ? -1 : (intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) > 0 ? intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) : 0);
			} else {
				$seckill_stock_num = $now_goods['seckill_stock'];
			}
			
		} else {
			$now_time = time();
			$open_time = $now_goods['seckill_open_time'];
			$close_time = $now_goods['seckill_close_time'];
			//秒杀库存的计算
			$seckill_stock_num = $now_goods['seckill_stock'] == -1 ? -1 : (intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) > 0 ? intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) : 0);
		}
		$maxNum = $now_goods['max_num'];
		$is_seckill_price = false;
		if ($open_time < $now_time && $now_time < $close_time && floatval($now_goods['seckill_price']) > 0 && $seckill_stock_num != 0) {
			$price = floatval($now_goods['seckill_price']);
			$is_seckill_price = true;
		} else {
			$price = floatval($now_goods['price']);
		}
		$old_price = floatval($now_goods['price']);
		$cost_price = floatval($now_goods['cost_price']);
// 		$price = $now_goods['price'];
		if ($spec_ids && $now_goods['spec_value']) {
			$return = $this->format_spec_value($now_goods['spec_value'], $now_goods['goods_id'], $now_goods['is_properties']);
			$list = isset($return['list']) ? $return['list'] : '';
			if (isset($list[$spec_ids])) {
				$today_sell_spec = json_decode($now_goods['today_sell_spec'], true);
				
				if ($now_goods['seckill_type'] == 1) {
					$now_time = date('H:i');
					$open_time = date('H:i', $now_goods['seckill_open_time']);
					$close_time = date('H:i', $now_goods['seckill_close_time']);
				} else {
					$now_time = time();
					$open_time = $now_goods['seckill_open_time'];
					$close_time = $now_goods['seckill_close_time'];
				}
				if ($open_time < $now_time && $now_time < $close_time && $list[$spec_ids]['seckill_price'] > 0 && $seckill_stock_num != 0) {
					$price = $list[$spec_ids]['seckill_price'];
					$is_seckill_price = true;
				} else {
					$price = $list[$spec_ids]['price'];
				}
				$old_price = floatval($list[$spec_ids]['price']);
				$cost_price = floatval($list[$spec_ids]['cost_price']);
				$number = $list[$spec_ids]['number'];
				$maxNum = $list[$spec_ids]['max_num'];
				if ($is_seckill_price) {
					$stock_num = $seckill_stock_num;
				} else {
					if ($today == $now_goods['sell_day']) {
						$sell_count = isset($today_sell_spec[$spec_ids]) ? intval($today_sell_spec[$spec_ids]) : 0;
						$stock_num = $list[$spec_ids]['stock_num'] == -1 ? -1 : (intval($list[$spec_ids]['stock_num'] - $sell_count) > 0 ? intval($list[$spec_ids]['stock_num'] - $sell_count) : 0);
					} else {
						$stock_num = $list[$spec_ids]['stock_num'];
					}
				}
			} else {
				return array('status' => 0, 'msg' => '您选择的规格可能被商家修改了');
			}
		} else {
			if ($is_seckill_price) {
				$stock_num = $seckill_stock_num;
			} else {
				if ($today == $now_goods['sell_day']) {
// 					$stock_num = $now_goods['stock_num'] == -1 ? -1 : (intval($now_goods['stock_num'] - $now_goods['today_sell_count']) > 0 ? intval($now_goods['stock_num'] - $now_goods['today_sell_count']) : 0);
					$stock_num = $now_goods['stock_num'];
				} else {
// 					$stock_num = $now_goods['stock_num'];
					$stock_num = $now_goods['original_stock'];
				}
			}
			$number = $now_goods['number'];
		}
		$maxNum = intval($maxNum);
		if ($now_goods['min_num'] > 1) {
		    $maxNum = 0;
		}
		if ($now_goods['limit_type'] == 1 && $maxNum > 0) {
		    $buyNum = $this->getBuyGoodsNum($uid, $goods_id);
		    if ($maxNum - $buyNum < 1) {
		        return array('status' => 0, 'msg' => '商品【' . $now_goods['name'] . '】每个用户限购' . $maxNum . $now_goods['unit'] . ',您已经购买了' . $maxNum . $now_goods['unit']);
		    } elseif ($maxNum - $buyNum < $num) {
		        if ($buyNum > 0) {
		            return array('status' => 0, 'msg' => '商品【' . $now_goods['name'] . '】您最多可购' . intval($maxNum - $buyNum) . $now_goods['unit'] . ',您已经购买了' . $buyNum . $now_goods['unit']);
		        } else {
		            return array('status' => 0, 'msg' => '商品【' . $now_goods['name'] . '】您最多可购' . intval($maxNum - $buyNum) . $now_goods['unit']);
		        }
		    }
		}
		if (empty($is_seckill_price) && $maxNum > 0 && $num > $maxNum) {
		    return array('status' => 0, 'msg' => '商品【' . $now_goods['name'] . '】每单限购' . $maxNum . $now_goods['unit']);
		}
		if ($stock_num == -1) {
		    return array('status' => 1, 'num' => $num, 'goods_id' => $now_goods['goods_id'], 'is_use_coupon' => $now_goods['is_use_coupon'], 'is_discount' => $now_goods['is_discount'], 'maxNum' => $maxNum, 'is_seckill_price' => $is_seckill_price, 'old_price' => $old_price, 'cost_price' => $cost_price, 'price' => $price, 'image' => $image, 'limit_type' => $now_goods['limit_type'], 'packing_charge' => $now_goods['packing_charge'], 'freight_type' => $now_goods['freight_type'], 'freight_value' => $now_goods['freight_value'], 'freight_template' => $now_goods['freight_template'], 'unit' => $now_goods['unit'], 'number' => $number, 'sort_id' => $now_goods['sort_id'], 'name' => $now_goods['name'],'jd_sku_id'=>$now_goods['jd_sku_id']);
		} elseif ($stock_num == 0) {
			return array('status' => 0, 'msg' => '商品库存不足');
		} elseif ($stock_num - $num >= 0) {
		    return array('status' => 1, 'num' => $num, 'goods_id' => $now_goods['goods_id'], 'maxNum' => $maxNum, 'is_use_coupon' => $now_goods['is_use_coupon'], 'is_discount' => $now_goods['is_discount'], 'is_seckill_price' => $is_seckill_price, 'old_price' => $old_price, 'cost_price' => $cost_price, 'price' => $price, 'image' => $image, 'limit_type' => $now_goods['limit_type'], 'packing_charge' => $now_goods['packing_charge'], 'freight_type' => $now_goods['freight_type'], 'freight_value' => $now_goods['freight_value'], 'freight_template' => $now_goods['freight_template'], 'unit' => $now_goods['unit'], 'number' => $number, 'sort_id' => $now_goods['sort_id'], 'name' => $now_goods['name'],'jd_sku_id'=>$now_goods['jd_sku_id']);
		} else {
		    return array('status' => 2, 'num' => $stock_num, 'goods_id' => $now_goods['goods_id'], 'maxNum' => $maxNum, 'is_use_coupon' => $now_goods['is_use_coupon'], 'is_discount' => $now_goods['is_discount'], 'is_seckill_price' => $is_seckill_price, 'old_price' => $old_price, 'cost_price' => $cost_price, 'msg' => '最多能购买' . $stock_num . $now_goods['unit'], 'limit_type' => $now_goods['limit_type'], 'number' => $number, 'price' => $price, 'image' => $image, 'packing_charge' => $now_goods['packing_charge'], 'freight_type' => $now_goods['freight_type'], 'freight_value' => $now_goods['freight_value'], 'freight_template' => $now_goods['freight_template'], 'unit' => $now_goods['unit'], 'sort_id' => $now_goods['sort_id'], 'name' => $now_goods['name'],'jd_sku_id'=>$now_goods['jd_sku_id']);
		}
	}
	
	/**
	 * 更新库存
	 * $id_index = 1_1
	 * $goods 是shop_goods_order_detail 的一条记录
	 * $type 操作类型 0：加销量，减库存，1：加库存，减销量
	 */
// 	public function update_stock($goods_id, $num, $id_index = '', $is_seckill = 0)
	public function update_stock($goods, $type = 0)
	{
		static $shops;
		$today = date('Ymd');
		$now_goods = $this->field(true)->where(array('goods_id' => $goods['goods_id']))->find();
		if (empty($now_goods)) return array('status' => 0, 'msg' => '商品不存在');
		
		if (isset($shops[$now_goods['store_id']])) {
			$shop = $shops[$now_goods['store_id']];
		} else {
			$shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $now_goods['store_id']))->find();
			$shops[$shop['store_id']] = $shop;
		}
		//$shop['stock_type']库存变更类型，0:每天更新成固定库存，1：不会自动更新库存
		$now_goods['sell_day'] = $shop['stock_type'] ? $today : $now_goods['sell_day'];
		
		if ($type == 0) {//加销量
			$num = $goods['num'];
			$total_num = $goods['num'];
			$seckill_num = $goods['num'];
		} else {//减销量
			$total_num = $goods['num'] * -1;
			if ($today == date('Ymd', $goods['create_time'])) {//下单是就是今天时候要实时回滚销量
				$num = $goods['num'] * -1;
				$seckill_num = $goods['num'] * -1;
			} else {//下单不是今天的情况
				if ($shop['stock_type'] == 0) {//每天固定库存的情况下，就无需回滚今天的销量
					$num = 0;
				} else {
					$num = $goods['num'] * -1;
				}
				if ($now_goods['seckill_type'] == 1) {//秒杀库存类型，1：每天固定库存，就无需回滚今天的销量
					$seckill_num = 0;
				} else {//0：固定库存
					$seckill_num = $goods['num'] * -1;
				}
			}
		}
		
		$today_sell_count = $now_goods['today_sell_count'];//今日销量
		$sell_count = $now_goods['sell_count'];//总销量
		$today_sell_spec = $now_goods['today_sell_spec'] ? json_decode($now_goods['today_sell_spec'], true) : '';//今日每种规格下的销量
		$today_seckill_count = $now_goods['today_seckill_count'];//今日秒杀的销量
		if ($today == $now_goods['sell_day']) {
		    $stock_num = $now_goods['stock_num'];//库存
		} else {
		    $stock_num = $now_goods['original_stock'];//库存
		}
		
		if ($goods['spec_id']) {//某种规格
			$id_index = $goods['spec_id'];
			isset($today_sell_spec[$id_index]) || $today_sell_spec[$id_index] = 0;
			if ($today == $now_goods['sell_day']) {
                $today_sell_spec[$id_index] = $today_sell_spec[$id_index] + $num;
				$today_sell_count += $num;
			} else {
				$today_sell_spec[$id_index] = $num;
				$today_sell_count = $num;
			}
			$sell_count += $total_num;
			$today_sell_spec[$id_index] = max(0, $today_sell_spec[$id_index]);
			
			if ($goods['is_seckill']) {
				if ($now_goods['seckill_type'] == 1) {
					if ($today == $now_goods['sell_day']) {
						$today_seckill_count += $seckill_num;
					} else {
						$today_seckill_count = $seckill_num;
					}
				} else {
					$today_seckill_count += $seckill_num;
				}
			} elseif ($today != $now_goods['sell_day']) {
				$today_seckill_count = 0;
			}
		} else {
			if ($today == $now_goods['sell_day']) {
				$today_sell_count += $num;
			} else {
				$today_sell_count = $num;
			}
			$sell_count += $total_num;
			$today_sell_count = max(0, $today_sell_count);
			
			if ($goods['is_seckill']) {
				if ($now_goods['seckill_type'] == 1) {
					if ($today == $now_goods['sell_day']) {
						$today_seckill_count += $seckill_num;
					} else {
						$today_seckill_count = $seckill_num;
					}
				} else {
					$today_seckill_count += $seckill_num;
				}
			} elseif ($today != $now_goods['sell_day']) {
				$today_seckill_count = 0;
			}
		}
		if ($stock_num > 0) {
		    if ($stock_num - $num >= 0) {
		        $stock_num -= $num;
		    } else {
		        $stock_num = 0;
		    }
		}
		$sell_count = max(0, $sell_count);
		$today_sell_count = max(0, $today_sell_count);
		$today_seckill_count = max(0, $today_seckill_count);
		$this->where(array('goods_id' => $goods['goods_id']))->save(array('stock_num' => $stock_num, 'sell_day' => $today, 'today_seckill_count' => $today_seckill_count, 'sell_count' => $sell_count, 'today_sell_count' => $today_sell_count, 'today_sell_spec' => $today_sell_spec ? json_encode($today_sell_spec) : ''));
	}
	
	
	public function get_list_by_option($where, $sort, $sort_type = 1)
	{
// 		if (empty($where['cat_fid'])) {
// // 			return array('goods_list' => null, 'total' => 0, 'next_page' => 0, 'total_page' => 0);
// 		}
		$order = 'ORDER BY ';
		if ($sort == 1) {
			$order .= '`g`.`sell_count` DESC, `g`.`price` ASC';
		} elseif ($sort == 2) {
			$order .= '`g`.`sell_count`';
		} elseif ($sort == 3) {
			$order .= '`g`.`price`';
		}
		if ($sort != 1) {
			if ($sort_type == 1) {
				$order .= ' DESC';
			} else {
				$order .= ' ASC';
			}
		}
		$condition = '`g`.`status`=1';
		if ($where['cat_fid']) {
			$condition .= " AND `g`.`cat_fid`='{$where['cat_fid']}'";
		}
		if ($where['cat_id']) {
			$condition .= " AND `g`.`cat_id`='{$where['cat_id']}'";
		}
		if ($where['store_id']) {
			$condition .= " AND `g`.`store_id`='{$where['store_id']}'";
		}
		
		if ($where['key']) {
			$condition .= " AND `g`.`name` LIKE '%{$where['key']}%'";
		}
		
		if ($where['goods_ids']) {
			$condition .= ' AND `g`.`goods_id` IN (' . implode(',', $where['goods_ids']) . ')';
		}
		
		if ($where['pids']) {
			$pids = implode(',', $where['pids']);
			$p_sql = "SELECT gid, pid FROM  ". C('DB_PREFIX') . "goods_properties_relation WHERE `pid` IN ({$pids})";
			$list = $this->query($p_sql);
			$pids_arr1 = $pids_arr2 = $pids_arr3 = $pids_arr4 = '';
			$goods_ids = array();
			switch (count($where['pids'])) {
				case 1:
					$pids_arr1 = explode(',', $where['pids'][0]);
					foreach ($list as $vo) {
						if (!in_array($vo['gid'], $goods_ids)) $goods_ids[] = $vo['gid'];
					}
					break;
				case 2:
					$pids_arr1 = explode(',', $where['pids'][0]);
					$pids_arr2 = explode(',', $where['pids'][1]);
					$goods_ids1 = $goods_ids2 = array();
					foreach ($list as $vo) {
						if (in_array($vo['pid'], $pids_arr1)) {
							$goods_ids1[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr2)) {
							$goods_ids2[] = $vo['gid'];
						}
					}
					$goods_ids = array_intersect($goods_ids1, $goods_ids2);
					break;
				case 3:
					$pids_arr1 = explode(',', $where['pids'][0]);
					$pids_arr2 = explode(',', $where['pids'][1]);
					$pids_arr3 = explode(',', $where['pids'][2]);
					$goods_ids1 = $goods_ids2 = $goods_ids3 = array();
					foreach ($list as $vo) {
						if (in_array($vo['pid'], $pids_arr1)) {
							$goods_ids1[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr2)) {
							$goods_ids2[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr3)) {
							$goods_ids3[] = $vo['gid'];
						}
					}
					$goods_ids = array_intersect($goods_ids1, $goods_ids2, $goods_ids3);
					break;
				case 4:
					$pids_arr1 = explode(',', $where['pids'][0]);
					$pids_arr2 = explode(',', $where['pids'][1]);
					$pids_arr3 = explode(',', $where['pids'][2]);
					$pids_arr4 = explode(',', $where['pids'][3]);
					$goods_ids1 = $goods_ids2 = $goods_ids3 = $goods_ids4 = array();
					foreach ($list as $vo) {
						if (in_array($vo['pid'], $pids_arr1)) {
							$goods_ids1[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr2)) {
							$goods_ids2[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr3)) {
							$goods_ids3[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr4)) {
							$goods_ids4[] = $vo['gid'];
						}
					}
					$goods_ids = array_intersect($goods_ids1, $goods_ids2, $goods_ids3, $goods_ids4);
					break;
			}
			if (empty($goods_ids)) {
				return array('goods_list' => null, 'total' => 0, 'next_page' => 0, 'total_page' => 0);
			} else {
				$condition .= ' AND `g`.`goods_id` IN (' . implode(',', $goods_ids) . ')';
			}
		}
		if(C('config.is_show_jd_goods')==0){
		    $condition .= ' AND `g`.`jd_sku_id` = 0 ';
        }
		$sql_count = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `s`.`status`=1 AND `s`.`have_shop`=1 AND `sh`.`store_theme`=1 AND `s`.`store_id`=`sh`.`store_id` INNER JOIN " . C('DB_PREFIX') . "shop_goods AS g ON `sh`.`store_id`=`g`.`store_id` AND `s`.`status`=1 WHERE {$condition}";
		$count = $this->query($sql_count);
		$total = isset($count[0]['count']) ? intval($count[0]['count']) : 0;
		
		$page = isset($where['page']) ? intval($where['page']) : 1;
		$pagesize = isset($where['page_size']) ? intval($where['page_size']) : 10;
		$totalPage = ceil($total / $pagesize);
		$star = $pagesize * ($page - 1);
		$return['next_page'] = $totalPage > $page ? intval($page + 1) : 0;
		$return['total_page'] = $totalPage;
		$return['total'] = $total;
		
		$sql = "SELECT `g`.`goods_id`,`g`.`sort_id`,`g`.`packing_charge`,`g`.`packing_charge`, `g`.`reply_count`,`g`.`max_num`, `g`.`min_num`, `g`.`name`, `g`.`show_start_time`, `g`.`show_end_time`, `g`.`seckill_type`, `g`.`seckill_open_time`, `g`.`last_time`, `g`.`seckill_close_time`, `g`.`price`, `g`.`old_price`, `g`.`seckill_price`, `g`.`unit`, `g`.`image`, `g`.`sell_count` ,`g`.`extra_pay_price`,`g`.`spec_value`,`g`.`jd_sku_id`,`g`.`stock_num`,`g`.`seckill_stock`,`g`.`is_properties`,`g`.`sell_day`,`g`.`today_seckill_count` FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `s`.`status`=1 AND `s`.`have_shop`=1 AND `s`.`store_id`=`sh`.`store_id` INNER JOIN " . C('DB_PREFIX') . "shop_goods AS g ON `sh`.`store_id`=`g`.`store_id` WHERE {$condition} {$order} LIMIT {$star}, {$pagesize}";
		$temp_list = $this->query($sql);
		$goods_image_class = new goods_image();
		$nowtime = time();
		$goods_list = array();
		$today = date('Ymd');
		foreach ($temp_list as $row) {
		    //新增限时显示
		    if (!($row['show_start_time'] == $row['show_end_time'] || ($row['show_start_time'] == '00:00:00' && $row['show_end_time'] == '23:59:00'))) {
		        $st = strtotime(date('Y-m-d') . ' ' . $row['show_start_time']);
		        $et = strtotime(date('Y-m-d') . ' ' . $row['show_end_time']);
		        if (!($st <= $nowtime && $nowtime<= $et)) {
		            continue;
		        }
		    }
			$row['price'] = floatval($row['price']);
			
//			if($row['spec_value']!=''){
//				$row['extra_pay_price']=0;
//			}

			if ($row['seckill_type'] == 1) {
                $now_time = date('H:i');
                $open_time = date('H:i', $row['seckill_open_time']);
                $close_time = date('H:i', $row['seckill_close_time']);
                
                // 秒杀库存的计算
                if ($today == $row['sell_day']) {
                    $seckill_stock_num = $row['seckill_stock'] == - 1 ? - 1 : (intval($row['seckill_stock'] - $row['today_seckill_count']) > 0 ? intval($row['seckill_stock'] - $row['today_seckill_count']) : 0);
                } else {
                    $seckill_stock_num = $row['seckill_stock'];
                }
            } else {
                $now_time = time();
                $open_time = $row['seckill_open_time'];
                $close_time = $row['seckill_close_time'];
                $seckill_stock_num = $row['seckill_stock'] == - 1 ? - 1 : (intval($row['seckill_stock'] - $row['today_seckill_count']) > 0 ? intval($row['seckill_stock'] - $row['today_seckill_count']) : 0);
            }
			
			$row['product_reply'] = $row['reply_count'];
			$row['stock'] = $row['stock_num'];
			$row['is_seckill_price'] = false;
			$row['o_price'] = floatval($row['price']);
			$row['old_price'] = floatval($row['price']);
			if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0 && $seckill_stock_num != 0){
				$row['price'] = floatval($row['seckill_price']);
				$row['is_seckill_price'] = true;
				$row['seckill_discount'] = round($row['price']/$row['o_price']*10,1);
			} else {
				$row['price'] = floatval($row['price']);
			}
			$row['is_new'] = (($nowtime - $row['last_time']) > 864000) ? 0 : 1;

			$row['url'] = U('Mall/detail', array('goods_id' => $row['goods_id']), true, false, true);
			$row['pcmallurl'] = '/mall/goods/' . $row['goods_id'];
			if ($row['min_num'] > 1) {
			    $row['max_num'] = 0;
			}
			$tmp_pic_arr = explode(';', $row['image']);
			foreach ($tmp_pic_arr as $key => $value) {
			    if(false === strpos($value,',')){
                    $temp_image = 'http://img13.360buyimg.com/n0/'.$value;
                }else{
                    $temp_image = $goods_image_class->get_image_by_path($value);
                }
				if ($temp_image) {
			        if(is_array($temp_image)){
                        $row['image'] = $temp_image['s_image'];
                        break;
                    }else{
                        $row['image'] = $temp_image;
                        break;
                    }
				}
			}
			
			$row['seckill_stock'] = $seckill_stock_num;
			if($row['seckill_stock'] > 0 && $row['is_seckill_price']){
				$row['stock_num'] = $row['seckill_stock'];
			}
			if($row['seckill_stock'] == 0){
				$row['is_seckill_price'] = false;
			}
			
			if(!$row['spec_list']){
				if($row['min_num'] > 0 && $row['stock_num'] >= 0 && $row['min_num'] > $row['stock_num']){
					$row['stock_num'] = 0;
					$row['stock'] = 0;
				}
				if($row['min_num'] > 0 && $row['seckill_stock'] >= 0 && $row['min_num'] > $row['seckill_stock']){
					$row['seckill_stock'] = 0;
				}
			}
			
			$goods_list[] = $row;
		}

		$return['goods_list'] = $goods_list;
		return $return;
	}
	
	public function get_list_by_condition($where, $sort = 1, $sort_type = 1)
	{

		$condition = array('status' => 1, 'store_id' => $where['store_id']);
		$where['sort_id'] && $condition['sort_id'] = $where['sort_id'];
		$where['name'] && $condition['name'] = $where['name'];
		$order = '';
		if ($sort == 1) {
			$order .= 'sell_count';
		} elseif ($sort == 2) {
			$order .= 'price';
		} elseif ($sort == 3) {
			$order .= 'reply_count';
		}
		if ($sort_type == 1) {
			$order .= ' DESC';
		} else {
			$order .= ' ASC';
		}
		if(C('config.is_show_jd_goods')==0){
            $condition['jd_sku_id'] = array('eq',0);
        }
		$count = $this->where($condition)->count();
		$page_size = 10;
		import('@.ORG.new_reply_ajax_page');
		$p = new Page($count, $page_size);
		
		
		$goods_image_class = new goods_image();
		$g_list = $this->field(true)->where($condition)->order($order . ', goods_id ASC')->limit($p->firstRow . ',' . $page_size)->select();
		/*elseif ($keyword != '') {
			$g_list = $this->field(true)->where(array('store_id' => $store_id, 'name' => array('like', '%' . $keyword . '%'), 'status' => 1))->order('sort DESC, goods_id ASC')->select();
		} else {
			$g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->order('sort DESC, goods_id ASC')->select();
		}*/
	
		$sort_result = array();
		$nowtime = time();
		$goods_list = array();
		foreach ($g_list as $row) {
		    //新增限时显示
		    if (!($row['show_start_time'] == $row['show_end_time'] || ($row['show_start_time'] == '00:00:00' && $row['show_end_time'] == '23:59:00'))) {
		        $st = strtotime(date('Y-m-d') . ' ' . $row['show_start_time']);
		        $et = strtotime(date('Y-m-d') . ' ' . $row['show_end_time']);
		        if (!($st <= $nowtime && $nowtime <= $et)) {
		            continue;
		        }
		    }
		    
			if ($row['seckill_type'] == 1) {
				$now_time = date('H:i');
				$open_time = date('H:i', $row['seckill_open_time']);
				$close_time = date('H:i', $row['seckill_close_time']);
			} else {
				$now_time = time();
				$open_time = $row['seckill_open_time'];
				$close_time = $row['seckill_close_time'];
			}
			$row['is_seckill_price'] = false;
			$row['o_price'] = floatval($row['price']);
			$row['old_price'] = floatval($row['price']);
			if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
				$row['price'] = floatval($row['seckill_price']);
				$row['is_seckill_price'] = true;
			} else {
				$row['price'] = floatval($row['price']);
			}
			$row['is_new'] = (($nowtime - $row['last_time']) > 864000) ? 0 : 1;
			$row['seckill_price'] = floatval($row['seckill_price']);
			$row['has_format'] = false;
			if ($row['spec_value'] || $row['is_properties']) {
				$row['has_format'] = true;
			}
			$row['has_spec']  = $row['spec_value'] ? true : false; //商品是否有规格

			$row['url'] = U('Mall/detail', array('goods_id' => $row['goods_id']), true, false, true);
			$tmp_pic_arr = explode(';', $row['image']);
			foreach ($tmp_pic_arr as $key => $value) {
			    if(false === strpos($value,',')){
                    $temp_image = 'http://img13.360buyimg.com/n0/'.$value;
                }else{
                    $temp_image = $goods_image_class->get_image_by_path($value);
                }
				if ($temp_image) {
                    if(is_array($temp_image)){
                        $row['image'] = $temp_image['image'];
                        break;
                    }else{
                        $row['image'] = $temp_image;
                        break;
                    }
				}
			}
			$goods_list[] = $row;
		}
		
		$return['count'] = $count;
		$return['goods_list']  = $goods_list;
		$return['now']  = $p->nowPage;
		$return['total']  = $p->totalPage;
		return $return;
	}


	/**
	 * 检查库存
	 * @param int $goods_id
	 * @param int $num
	 * @param string $spec_ids = 'id_id'
	 * @param string $stock_type //店铺更新库存的类型 0：每日更新相同的库存 1：库存不会每天自动更新
	 * @return multitype:number string |multitype:number unknown |multitype:number string Ambigous <number, mixed>
	 */
	public function check_stock_list($store_id = 2, $stock_type = 0, $num = 10)
	{
		$goods_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->select();
		
		$today = date('Ymd');
		
		$waring_list = array();
		foreach ($goods_list as $now_goods) {
			$now_goods['sell_day'] = $stock_type ? $today : $now_goods['sell_day'];
			if ($now_goods['spec_value']) {
				$return = $this->format_spec_value($now_goods['spec_value'], $now_goods['goods_id'], $now_goods['is_properties']);
				$list = isset($return['list']) ? $return['list'] : '';
				$today_sell_spec = json_decode($now_goods['today_sell_spec'], true);
				
				foreach ($list as $key => $value) {
					
					if ($today == $now_goods['sell_day']) {
						$sell_count = isset($today_sell_spec[$key]) ? intval($today_sell_spec[$key]) : 0;
						$stock_num = $value['stock_num'] == -1 ? -1 : (intval($value['stock_num'] - $sell_count) > 0 ? intval($value['stock_num'] - $sell_count) : 0);
					} else {
						$stock_num = $value['stock_num'];
					}
					$name = $pre = '';
					foreach ($value['spec'] as $spec_val) {
						$name .= $pre . $spec_val['spec_val_name'];
						$pre = ',';
					}
					if ($name) $name = ' (' . $name . ')';
					if ($stock_num != -1 && $stock_num < $num) {
						$waring_list[] = array('goods_id' => $now_goods['goods_id'], 'number' => $value['number'], 'price' => $value['price'], 'stock_num' => $stock_num, 'name' => $now_goods['name'] . $name);
					}
				}
				
	
			} else {
				
				if ($today == $now_goods['sell_day']) {
// 					$stock_num = $now_goods['stock_num'] == -1 ? -1 : (intval($now_goods['stock_num'] - $now_goods['today_sell_count']) > 0 ? intval($now_goods['stock_num'] - $now_goods['today_sell_count']) : 0);
					$stock_num = $now_goods['stock_num'];
				} else {
					$stock_num = $now_goods['original_stock'];
				}
				
				if ($stock_num != -1 && $stock_num < $num) {
					$waring_list[] = array('goods_id' => $now_goods['goods_id'], 'number' => $now_goods['number'], 'price' => $now_goods['price'], 'stock_num' => $stock_num, 'name' => $now_goods['name']);
				}
			}
		}

		return $waring_list;
	}
	
	
	public function get_list($store_id, $is_cache = true)
	{
		if ($is_cache) {
			$s_list = S('shop_goods_by_storeid_' . $store_id);
		} else {
			$s_list = null;
		}
		if ($s_list) return $s_list;
		
		$database_goods_sort = D('Shop_goods_sort');
		$condition_goods_sort['store_id'] = $store_id;
		$sort_list = $database_goods_sort->field(true)->where($condition_goods_sort)->order('`sort` DESC,`sort_id` ASC')->select();
		$s_list = array();
		$today = date('w');
		foreach ($sort_list as $value) {
			if (!empty($value['is_weekshow'])) {
				$week_arr = explode(',', $value['week']);
				if (!in_array($today, $week_arr)) {
					continue;
				}
				$week_str = '';
				foreach ($week_arr as $k => $v){
					$week_str .= $this->get_week($v) . ' ';
				}
				$value['week_str'] = $week_str;
			}
			$s_list[$value['sort_id']] = $value;
		}
		
// 		$g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->order('sort DESC, goods_id ASC')->select();
		$g_list = $this->field(true)->where(array('store_id' => $store_id))->order('`status` DESC,`sort` DESC, `goods_id` ASC')->select();
		
		$today = date('Ymd');
		$store_shop = D("Merchant_store_shop")->field('stock_type')->where(array('store_id' => $store_id))->find();
		$stock_type = intval($store_shop['stock_type']);
		$timeNow = time();
		foreach ($g_list as $row) {
		    //新增限时显示
		    if (!($row['show_start_time'] == $row['show_end_time'] || ($row['show_start_time'] == '00:00:00' && $row['show_end_time'] == '23:59:00'))) {
		        $st = strtotime(date('Y-m-d') . ' ' . $row['show_start_time']);
		        $et = strtotime(date('Y-m-d') . ' ' . $row['show_end_time']);
		        if (!($st <= $timeNow && $timeNow <= $et)) {
		            continue;
		        }
		    }
			$row['sell_day'] = $stock_type ? $today : $row['sell_day'];
			
			$temp_goods = array('name' => $row['name'], 'goods_id' => $row['goods_id'], 'goods_id_real' => $row['goods_id']);
			$temp_goods['seckill_type'] = $row['seckill_type'];
			
			if ($row['seckill_type'] == 1) {
				$now_time = date('H:i');
				$open_time = date('H:i', $row['seckill_open_time']);
				$close_time = date('H:i', $row['seckill_close_time']);
			} else {
				$now_time = time();
				$open_time = $row['seckill_open_time'];
				$close_time = $row['seckill_close_time'];
			}
			$temp_goods['open_time'] = $open_time;
			$temp_goods['close_time'] = $close_time;
			$temp_goods['price'] = floatval($row['price']);
			$temp_goods['seckill_price'] = floatval($row['seckill_price']);
			$temp_goods['number'] = $row['number'];
			$temp_goods['unit'] = $row['unit'];
			
			$temp_goods['sort_id'] = $row['sort_id'];
			if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
				$temp_goods['is_seckill_price'] = true;
			} else {
				$temp_goods['is_seckill_price'] = false;
			}
			if ($today == $row['sell_day']) {
// 				$stock_num = $row['stock_num'] == -1 ? -1 : (intval($row['stock_num'] - $row['today_sell_count']) > 0 ? intval($row['stock_num'] - $row['today_sell_count']) : 0);
				$stock_num = $row['stock_num'];
			} else {
				$stock_num = $row['original_stock'];
			}
			$temp_goods['stock_num'] = $stock_num;
			
			$return = $this->format_spec_value($row['spec_value'], $row['goods_id'], $row['is_properties']);
			if (isset($return['list'])) {
				$today_sell_spec = json_decode($row['today_sell_spec'], true);
				foreach ($return['list'] as $id_key => $spec_goods) {
					$temp_goods['goods_id'] = $row['goods_id'] . '_' . $id_key;
					$temp_goods['price'] = floatval($spec_goods['price']);
					$temp_goods['seckill_price'] = floatval($spec_goods['seckill_price']);
					$temp_goods['number'] = $spec_goods['number'];
					
					if ($today == $row['sell_day']) {
						$sell_count = isset($today_sell_spec[$id_key]) ? intval($today_sell_spec[$id_key]) : 0;
						$stock_num = $spec_goods['stock_num'] == -1 ? -1 : (intval($spec_goods['stock_num'] - $sell_count) > 0 ? intval($spec_goods['stock_num'] - $sell_count) : 0);
					} else {
						$stock_num = $spec_goods['stock_num'];
					}
					
					$temp_goods['stock_num'] = $stock_num;
					$t_names = array();
					$temp_goods['spec_arr'] = $spec_goods['spec'];
					foreach ($spec_goods['spec'] as $spec_row) {
						$t_names[] = $spec_row['spec_val_name'];
					}
					$temp_goods['name'] = $row['name'] . '(' . implode(',', $t_names) . ')';
					
					if (isset($s_list[$row['sort_id']])) {
						if (isset($s_list[$row['sort_id']]['goods_list'])) {
							$s_list[$row['sort_id']]['goods_list'][] = $temp_goods;
						} else {
							$s_list[$row['sort_id']]['goods_list'] = array($temp_goods);
						}
					}
				}
			} else {
				if (isset($s_list[$row['sort_id']])) {
					if (isset($s_list[$row['sort_id']]['goods_list'])) {
						$s_list[$row['sort_id']]['goods_list'][] = $temp_goods;
					} else {
						$s_list[$row['sort_id']]['goods_list'] = array($temp_goods);
					}
				}
			}
		}
		
		foreach ($s_list as $k => $r) {
			if (!isset($r['goods_list'])) {
				unset($s_list[$k]);
			}
		}
		$s_list = sortArray($s_list,'sort',SORT_DESC);
		S('shop_goods_by_storeid_' . $store_id, $s_list);
		return $s_list;
    }
    
    public function checkCartOld($store_id, $uid, $goodsData, $isCookie = 1, $address_id = 0, $is_market = false)
    {
        $store = D("Merchant_store")->field(true)->where(array('store_id' => $store_id))->find();
        if ($store['have_shop'] == 0 || $store['status'] != 1) {
            return array('error_code' => true, 'msg' => '商家已经关闭了该业务,不能下单了!');
        }
        if (C('config.store_shop_auth') == 1 && $store['auth'] < 3) {
            return array('error_code' => true, 'msg' => '您查看的' . C('config.shop_alias_name') . '没有通过资质审核！');
        }
        
        $store_image_class = new store_image();
        $images = $store_image_class->get_allImage_by_path($store['pic_info']);
        $store['images'] = $images ? array_shift($images) : '';
        
        $now_time = date('H:i:s');
        $is_open = 0;
        
        if (D('Merchant_store_shop')->checkTime($store)) {
            $is_open = 1;
        }
        if ($is_open == 0) {
            return array('error_code' => true, 'msg' => '店铺休息中');
        }
        
        $store_shop = D("Merchant_store_shop")->field(true)->where(array('store_id' => $store_id))->find();
        if (empty($store) || empty($store_shop)) {
            return array('error_code' => true, 'msg' => '店铺信息有误');
        }
        if ($store_shop['is_close'] == 1) {
            return array('error_code' => true, 'msg' => '店铺休息中');
        }
        $store = array_merge($store, $store_shop);
        $mer_id = $store['mer_id'];
        
        if ($store['deliver_type'] == 0 || $store['deliver_type'] == 3) {
            $store['extra_price'] = floatval($store['s_extra_price']) ? floatval($store['s_extra_price']) : floatval(C('config.extra_price'));
        } else {
            $store['extra_price'] = floatval($store['extra_price']);//配送附加费
        }
        $store['delivery_range_polygon'] = substr($store['delivery_range_polygon'], 9, strlen($store['delivery_range_polygon']) - 11);
        $lngLatData = explode(',', $store['delivery_range_polygon']);
        array_pop($lngLatData);
        $lngLats = array();
        foreach ($lngLatData as $lnglat) {
            $lng_lat = explode(' ', $lnglat);
            $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
        }
        $store['delivery_range_polygon'] = $lngLats ? array($lngLats) : '';
        //用户的VIP折扣率
        $vip_discount = 100;
        $is_discount = 0;
        //店铺设置的vip等级折扣率
        $storeShopLevel = !empty($store_shop['leveloff']) ? unserialize($store_shop['leveloff']) : '';
        $user = M('User')->field(true)->where(array('uid' => $uid))->find();
        if ($storeShopLevel && $user) {
            if ($user['level']) {
                //系统设置的用户等级
                $tmpArr = M('User_level')->field(true)->order('`id` ASC')->select();
                $levelArray = array();
                foreach ($tmpArr as $vv) {
                    $levelArray[$vv['level']] = $vv;
                }
                if (isset($storeShopLevel[$user['level']]) && isset($levelArray[$user['level']])) {
                    $levelOff = $storeShopLevel[$user['level']];
                    if ($levelOff['type'] == 1) {
                        $vip_discount = $levelOff['vv'];
                    }
                }
            }
        }
        
        
        $goods = array();
        $price = 0;//原始总价
        $total = 0;//商品总数
        $extra_price = 0;//额外价格的总价
        $packing_charge = 0;//打包费
        //店铺优惠条件
        $sorts_discout = D('Shop_goods_sort')->get_sorts_discount($store_id);
        $store_discount_money = 0;//店铺折扣后的总价
        $noDiscountGoods = '';
        $useGoods = array();
        $useCouponMeny = 0;
        if ($isCookie == 0) {
            foreach ($goodsData as $row) {
                $goods_id = $row['goods_id'];
                $num = $row['num'];
                if ($num < 1) continue;
                $t_return = $this->check_stock($goods_id, $num, $row['spec_id'], $store_shop['stock_type'], $store_id, false, $uid);
                if ($t_return['status'] == 0) {
                    continue;
                } elseif ($t_return['status'] == 2) {
                    continue;
                }
                $total += $num;
                
                if ($t_return['is_seckill_price']) {
                    $is_discount = 1;
                }
                
                $oldNum = 0;
                $discountNum = 0;
                if ($t_return['limit_type'] == 0 && $t_return['maxNum'] > 0) {
                    if (isset($useGoods[$t_return['goods_id']])) {
                        $useNum = $useGoods[$t_return['goods_id']];
                        $discountNum = max(0, $t_return['maxNum'] - $useNum);
                        $useGoods[$t_return['goods_id']] += $discountNum;
                    } else {
                        $discountNum = $t_return['maxNum'];
                        $useGoods[$t_return['goods_id']] = $t_return['maxNum'];
                    }
                    if ($num > $discountNum) {
                        $oldNum = $num - $discountNum;
                    }
                    if ($oldNum > 0) {
                        $price += floatval($t_return['old_price'] * $oldNum);
                    }
                    if ($discountNum > 0) {
                        $price += floatval($t_return['price'] * $discountNum);
                    }
                } else {
                    $price += floatval($t_return['price'] * $num);
                }
                
                $extra_price += $row['extra_price'] * $num;
                $packing_charge += $t_return['packing_charge'] * $num;
        
                //折扣($sorts_discout[$t_return['sort_id']]['discount_type'] == 1 ? '分类折扣' : '店铺折扣')
                $t_discount = isset($sorts_discout[$t_return['sort_id']]['discount']) && $sorts_discout[$t_return['sort_id']]['discount'] ? $sorts_discout[$t_return['sort_id']]['discount'] : 100;
        
                //该商品的折扣类型 0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                $discount_type = 0;
                //折扣率 0：无折扣
                $discount_rate = 0;
                if ($t_return['is_discount'] == 0) {
                    if ($t_discount < 100) {
                        $noDiscountGoods .= '【' . $t_return['name'] . '】';
                    }
                    $t_discount = 100;
                }
                if ($t_discount < 100) {
                    if ($sorts_discout[$t_return['sort_id']]['discount_type']) {//分类折扣
                        $discount_type = 2;
                    } else {
                        $discount_type = 1;
                    }
                    $discount_rate = $t_discount;
                    $is_discount = 1;
                }
                
                if ($oldNum > 0) {
                    $num = $oldNum;
                    $tempPrice = $t_return['old_price'];
                    $this_goods_total_price = $num * round($tempPrice * $t_discount * 0.01, 2);//本商品的折扣总价
                    $only_discount_price = round($tempPrice * $t_discount * 0.01, 2);
                    if ($sorts_discout['discount_type'] == 0) {//折上折
                        if ($vip_discount < 100) {
                            $is_discount = 1;
                            $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
                            $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
                        }
                        $this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);
                        $only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);
                    } else {//折扣最优
                        $t_vip_price = $num * round($tempPrice * $vip_discount * 0.01, 2);
                        if ($t_vip_price < $this_goods_total_price) {
                            $this_goods_total_price = $t_vip_price;
                            if ($vip_discount < 100) {
                                $is_discount = 1;
                                $discount_type = 3;
                                $discount_rate = $vip_discount;
                            }
                            $only_discount_price = round($tempPrice * $vip_discount * 0.01, 2);
                        }
                    }
                    $store_discount_money += $this_goods_total_price;
                    
                    $str = '';
                    $str_s && $str = implode(',', $str_s);
                    $str_p && $str = $str ? $str . ';' . implode(',', $str_p) : implode(',', $str_p);
                    $goods[] = array(
                        'name' => $row['productName'],
                        'packname' => $row['packname'],
                        'is_seckill_price' => $t_return['is_seckill_price'],//是否是秒杀价(0:否，1：是)
                        'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                        'discount_rate' => $discount_rate,//折扣率
                        'num' => $num,
                        'goods_id' => $goods_id,
                        'old_price' => floatval($t_return['old_price']),//商品原始价
                        'price' => floatval($tempPrice),//是秒杀的时候是秒杀价，不是的时候是原始价
                        'discount_price' => floatval($only_discount_price),//折扣价
                        'cost_price' => floatval($t_return['cost_price']),
                        'number' => $t_return['number'],
                        'image' => $t_return['image'],
                        'sort_id' => $t_return['sort_id'],
                        'packing_charge' => $t_return['packing_charge'],
                        'unit' => $t_return['unit'],
                        'str' => $str,
                        'spec_id' => $spec_str,
                        'extra_price' => $row['productExtraPrice']
                    );
                    $num = $discountNum;
                }
                if ($num > 0) {
                    $this_goods_total_price = $num * round($t_return['price'] * $t_discount * 0.01, 2);//本商品的店铺折扣后的总价
                    $only_discount_price = round($t_return['price'] * $t_discount * 0.01, 2);//本商品的店铺折扣的单价
                    if ($sorts_discout['discount_type'] == 0) {//折上折
                        if ($vip_discount < 100) {
                            $is_discount = 1;
                            $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
                            $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
                        }
                        $this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);//本商品的VIP折扣后的总价
                        $only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);//本商品的VIP折扣的单价
                    } else {//折扣最优
                        $t_vip_price = $num * round($t_return['price'] * $vip_discount * 0.01, 2);
                        if ($t_vip_price < $this_goods_total_price) {
                            $this_goods_total_price = $t_vip_price;
            
                            if ($vip_discount < 100) {
                                $is_discount = 1;
                                $discount_type = 3;
                                $discount_rate = $vip_discount;
                            }
                            $only_discount_price = round($t_return['price'] * $vip_discount * 0.01, 2);
                        }
                    }
            
                    $store_discount_money += $this_goods_total_price;//折扣后的商品总价（店铺，分类，VIP折扣都计算在内）
            
                    $goods[] = array(
                        'name' => $row['name'],
                        'is_seckill_price' => $t_return['is_seckill_price'],//是否是秒杀价(0:否，1：是)
                        'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                        'discount_rate' => $discount_rate,//折扣率
                        'num' => $num,
                        'goods_id' => $goods_id,
                        'old_price' => floatval($t_return['old_price']),//商品原始价
                        'price' => floatval($t_return['price']),//
                        'discount_price' => floatval($only_discount_price),//折扣价
                        'cost_price' => floatval($t_return['cost_price']),
                        'number' => $t_return['number'],
                        'image' => $t_return['image'],
                        'sort_id' => $t_return['sort_id'],
                        'packing_charge' => $t_return['packing_charge'],
                        'unit' => $t_return['unit'],
                        'str' => $row['spec'],
                        'spec_id' => $row['spec_id'],
                        'extra_price' => $row['extra_price']
                    );
                }
            }
        } elseif ($isCookie == 1) {
            
            if ($address_id) {
                $user_adress = D('User_adress')->get_one_adress($uid, $address_id);
                $express_freight = array();
                $delivery_list = D('Express_template')->get_deliver_list($store['mer_id'], $store['store_id']);
                $goods_id_array = array();
                $delivery_money_total = 0;
                $max_freight = 0;
                $template_total_price = 0;
            }
            
            //处理拼单可能出现每个袋中有相同的商品导致数量超出
            $totalData = array();
            foreach ($goodsData as $row) {
                $goods_id = $row['productId'];
                $num = $row['count'];
                if ($num < 1) continue;
                $spec_ids = array();
                foreach ($row['productParam'] as $r) {
                    if ($r['type'] == 'spec') {
                        $spec_ids[] = $r['id'];
                    }
                }
                $spec_str = $spec_ids ? implode('_', $spec_ids) : '';
                if (isset($totalData[$goods_id . '_' . $spec_str])) {
                    $totalData[$goods_id . '_' . $spec_str] += $num;
                } else {
                    $totalData[$goods_id . '_' . $spec_str] = $num;
                }
            }
            foreach ($goodsData as $row) {
                $goods_id = $row['productId'];
                $num = $row['count'];
                if ($num < 1) continue;
                $spec_ids = array();
                $str_s = array(); $str_p = array();
                foreach ($row['productParam'] as $r) {
                    if ($r['type'] == 'spec') {
                        $spec_ids[] = $r['id'];
                        $str_s[] = $r['name'];
                    } else {
                        foreach ($r['data'] as $d) {
                            $str_p[] = $d['name'];
                        }
                    }
                }
                $spec_str = $spec_ids ? implode('_', $spec_ids) : '';
                
                $tNum = isset($totalData[$goods_id . '_' . $spec_str]) ? intval($totalData[$goods_id . '_' . $spec_str]) : $num;
                $t_return = $this->check_stock($goods_id, $tNum, $spec_str, $store_shop['stock_type'], $store_id, false, $uid);
                
                if ($t_return['status'] == 0) {
                    return array('error_code' => true, 'msg' => $t_return['msg']);
                } elseif ($t_return['status'] == 2) {
                    return array('error_code' => true, 'msg' => $t_return['msg']);
                }
                $total += $num;
                if ($t_return['is_seckill_price']) {
                    $is_discount = 1;
                }
                
                $oldNum = 0;
                $discountNum = 0;
                if ($t_return['limit_type'] == 0 && $t_return['maxNum'] > 0) {
                    
                    if (isset($useGoods[$t_return['goods_id']])) {
                        $useNum = $useGoods[$t_return['goods_id']];
                        $discountNum = max(0, $t_return['maxNum'] - $useNum);
                        $useGoods[$t_return['goods_id']] += $discountNum;
                    } else {
                        $discountNum = $t_return['maxNum'];
                        $useGoods[$t_return['goods_id']] = $t_return['maxNum'];
                    }
                    
                    if ($num > $discountNum) {
                        $oldNum = $num - $discountNum;
                    }
                    
                    if ($oldNum > 0) {
                        $price += floatval($t_return['old_price'] * $oldNum);
                    }
                    if ($discountNum > 0) {
                        $price += floatval($t_return['price'] * $discountNum);
                    }
                } else {
                    $price += floatval($t_return['price'] * $num);
                }
                $extra_price += $row['productExtraPrice'] * $num;
                $packing_charge += $t_return['packing_charge'] * $num;
        
                if ($address_id) {
                    //-----计算运费--------  freight_type ==> 0:最大，1：单独
                    if ($t_return['freight_type'] == 0) {
                        $template_id = intval($t_return['freight_template']);
                        if ($user_adress) {
                            if (isset($delivery_list[$template_id][$user_adress['city']])) {
                                $express_freight_tmp = $delivery_list[$template_id][$user_adress['city']];
                            } elseif (isset($delivery_list[$template_id][$user_adress['province']])) {
                                $express_freight_tmp = $delivery_list[$template_id][$user_adress['province']];
                            } else {
                                $template_id = 0;
                                $express_freight_tmp = array('freight' => $t_return['freight_value'], 'full_money' => 0, 'tid' => 0);
                            }
                        } else {
                            $template_id = 0;
                            $express_freight_tmp = array('freight' => $t_return['freight_value'], 'full_money' => 0, 'tid' => 0);
                        }
                        if ($max_freight < $express_freight_tmp['freight']) {
                            $express_freight = $express_freight_tmp;
                            $max_freight = $express_freight_tmp['freight'];
                        }
                        $template_total_price += $t_return['price'] * $num;
                    } else {
                        if (!in_array($goods_id, $goods_id_array)) {
                            $template_id = intval($t_return['freight_template']);
                            if ($user_adress) {
                                if (isset($delivery_list[$template_id][$user_adress['city']])) {
                                    $delivery_money_total += $delivery_list[$template_id][$user_adress['city']]['freight'];
                                } elseif (isset($delivery_list[$template_id][$user_adress['province']])) {
                                    $delivery_money_total += $delivery_list[$template_id][$user_adress['province']]['freight'];
                                } else {
                                    $delivery_money_total += $t_return['freight_value'];
                                }
                            }
                            $goods_id_array[] = $goods_id;
                        }
                    }
                    //-----计算运费--------
                }
                
                $t_discount = isset($sorts_discout[$t_return['sort_id']]['discount']) && $sorts_discout[$t_return['sort_id']]['discount'] ? $sorts_discout[$t_return['sort_id']]['discount'] : 100;

                $discount_type = 0;
                $discount_rate = 0;
                if ($t_return['is_discount'] == 0) {
                    if ($t_discount < 100) {
                        $noDiscountGoods .= '【' . $t_return['name'] . '】';
                    }
                    $t_discount = 100;
                }
                if ($t_discount < 100) {
                    $is_discount = 1;
                    if ($sorts_discout[$t_return['sort_id']]['discount_type']) {//分类折扣
                        $discount_type = 2;
                    } else {
                        $discount_type = 1;
                    }
                    $discount_rate = $t_discount;
                }
                
                if ($oldNum > 0) {
                    $num = $oldNum;
                    $tempPrice = $t_return['old_price'];
                    $this_goods_total_price = $num * round($tempPrice * $t_discount * 0.01, 2);//本商品的折扣总价
                    $only_discount_price = round($tempPrice * $t_discount * 0.01, 2);
                    if ($sorts_discout['discount_type'] == 0) {//折上折
                        if ($vip_discount < 100) {
                            $is_discount = 1;
                            $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
                            $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
                        }
                        $this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);
                        $only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);
                    } else {//折扣最优
                        $t_vip_price = $num * round($tempPrice * $vip_discount * 0.01, 2);
                        if ($t_vip_price < $this_goods_total_price) {
                            $this_goods_total_price = $t_vip_price;
                            if ($vip_discount < 100) {
                                $is_discount = 1;
                                $discount_type = 3;
                                $discount_rate = $vip_discount;
                            }
                            $only_discount_price = round($tempPrice * $vip_discount * 0.01, 2);
                        }
                    }
                    $store_discount_money += $this_goods_total_price;
                    
                    $str = '';
                    $str_s && $str = implode(',', $str_s);
                    $str_p && $str = $str ? $str . ';' . implode(',', $str_p) : implode(',', $str_p);
                    $goods[] = array(
                        'name' => $row['productName'],
                        'packname' => $row['packname'],
                        'is_seckill_price' => $t_return['is_seckill_price'],//是否是秒杀价(0:否，1：是)
                        'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                        'discount_rate' => $discount_rate,//折扣率
                        'num' => $num,
                        'goods_id' => $goods_id,
                        'old_price' => floatval($t_return['old_price']),//商品原始价
                        'price' => floatval($tempPrice),//是秒杀的时候是秒杀价，不是的时候是原始价
                        'discount_price' => floatval($only_discount_price),//折扣价
                        'cost_price' => floatval($t_return['cost_price']),
                        'number' => $t_return['number'],
                        'image' => $t_return['image'],
                        'sort_id' => $t_return['sort_id'],
                        'packing_charge' => $t_return['packing_charge'],
                        'unit' => $t_return['unit'],
                        'str' => $str,
                        'spec_id' => $spec_str,
                        'extra_price' => $row['productExtraPrice']
                    );
                    $num = $discountNum;
                }
                if ($num > 0) {
                    $this_goods_total_price = $num * round($t_return['price'] * $t_discount * 0.01, 2);//本商品的折扣总价
                    $only_discount_price = round($t_return['price'] * $t_discount * 0.01, 2);
                    if ($sorts_discout['discount_type'] == 0) {//折上折
                        if ($vip_discount < 100) {
                            $is_discount = 1;
                            $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
                            $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
                        }
                        $this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);
                        $only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);
                    } else {//折扣最优
                        $t_vip_price = $num * round($t_return['price'] * $vip_discount * 0.01, 2);
                        if ($t_vip_price < $this_goods_total_price) {
                            $this_goods_total_price = $t_vip_price;
                            if ($vip_discount < 100) {
                                $is_discount = 1;
                                $discount_type = 3;
                                $discount_rate = $vip_discount;
                            }
                            $only_discount_price = round($t_return['price'] * $vip_discount * 0.01, 2);
                        }
                    }
                    $store_discount_money += $this_goods_total_price;
            
                    $str = '';
                    $str_s && $str = implode(',', $str_s);
                    $str_p && $str = $str ? $str . ';' . implode(',', $str_p) : implode(',', $str_p);
                    $goods[] = array(
                        'name' => $row['productName'],
                        'packname' => $row['packname'],
                        'is_seckill_price' => $t_return['is_seckill_price'],//是否是秒杀价(0:否，1：是)
                        'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                        'discount_rate' => $discount_rate,//折扣率
                        'num' => $num,
                        'goods_id' => $goods_id,
                        'old_price' => floatval($t_return['old_price']),//商品原始价
                        'price' => floatval($t_return['price']),//是秒杀的时候是秒杀价，不是的时候是原始价
                        'discount_price' => floatval($only_discount_price),//折扣价
                        'cost_price' => floatval($t_return['cost_price']),
                        'number' => $t_return['number'],
                        'image' => $t_return['image'],
                        'sort_id' => $t_return['sort_id'],
                        'packing_charge' => $t_return['packing_charge'],
                        'unit' => $t_return['unit'],
                        'str' => $str,
                        'spec_id' => $spec_str,
                        'extra_price' => $row['productExtraPrice']
                    );
                }
            }
        } elseif ($isCookie == 2) {
            foreach ($goodsData as $row) {
                $num = $row['num'];
                if ($num < 1) continue;
                $ids = explode('_', $row['goods_id']);
                $goods_id = array_shift($ids);
                $spec_str = $ids ? implode('_', $ids) : '';
                $t_return = $this->check_stock($goods_id, $num, $spec_str, $store_shop['stock_type'], $store_id, $is_market, $uid);
                //店铺关闭线下零售收取打包费时 将商品的打包费置 0
                if ($is_market && $store['is_open_retail_pack'] == 0) {
                    $t_return['packing_charge'] = 0;
                }
                if (!$is_market) {
                    if ($t_return['status'] == 0) {
                        return array('error_code' => true, 'msg' => $t_return['msg']);
                    } elseif ($t_return['status'] == 2) {
                        return array('error_code' => true, 'msg' => $t_return['msg']);
                    }
                }
                $total += $num;
                if ($t_return['is_seckill_price']) {
                    $is_discount = 1;
                }
                
                $oldNum = 0;
                $discountNum = 0;
                if ($t_return['limit_type'] == 0 && $t_return['maxNum'] > 0) {
                    if (isset($useGoods[$t_return['goods_id']])) {
                        $useNum = $useGoods[$t_return['goods_id']];
                        $discountNum = max(0, $t_return['maxNum'] - $useNum);
                        $useGoods[$t_return['goods_id']] += $discountNum;
                    } else {
                        $discountNum = $t_return['maxNum'];
                        $useGoods[$t_return['goods_id']] = $t_return['maxNum'];
                    }
                    if ($num > $discountNum) {
                        $oldNum = $num - $discountNum;
                    }
                    if ($oldNum > 0) {
                        $price += floatval($t_return['old_price'] * $oldNum);
                    }
                    if ($discountNum > 0) {
                        $price += floatval($t_return['price'] * $discountNum);
                    }
                } else {
                    $price += floatval($t_return['price'] * $num);
                }
                
                $extra_price += $row['productExtraPrice'] * $num;
                $packing_charge += $t_return['packing_charge'] * $num;
            
                $t_discount = isset($sorts_discout[$t_return['sort_id']]['discount']) && $sorts_discout[$t_return['sort_id']]['discount'] ? $sorts_discout[$t_return['sort_id']]['discount'] : 100;

                $discount_type = 0;
                $discount_rate = 0;
                if ($t_return['is_discount'] == 0) {
                    if ($t_discount < 100) {
                        $noDiscountGoods .= '【' . $t_return['name'] . '】';
                    }
                    $t_discount = 100;
                }
                if ($t_discount < 100) {
                    $is_discount = 1;
                    if ($sorts_discout[$t_return['sort_id']]['discount_type']) {//分类折扣
                        $discount_type = 2;
                    } else {
                        $discount_type = 1;
                    }
                    $discount_rate = $t_discount;
                }
        
                if ($oldNum > 0) {
                    $num = $oldNum;
                    $tempPrice = $t_return['old_price'];
                    $this_goods_total_price = $num * round($tempPrice * $t_discount * 0.01, 2);//本商品的折扣总价
                    $only_discount_price = round($tempPrice * $t_discount * 0.01, 2);
                    if ($sorts_discout['discount_type'] == 0) {//折上折
                        if ($vip_discount < 100) {
                            $is_discount = 1;
                            $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
                            $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
                        }
                        $this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);
                        $only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);
                    } else {//折扣最优
                        $t_vip_price = $num * round($tempPrice * $vip_discount * 0.01, 2);
                        if ($t_vip_price < $this_goods_total_price) {
                            $this_goods_total_price = $t_vip_price;
                            if ($vip_discount < 100) {
                                $is_discount = 1;
                                $discount_type = 3;
                                $discount_rate = $vip_discount;
                            }
                            $only_discount_price = round($tempPrice * $vip_discount * 0.01, 2);
                        }
                    }
                    $store_discount_money += $this_goods_total_price;
                    
                    $str = '';
                    $str_s && $str = implode(',', $str_s);
                    $str_p && $str = $str ? $str . ';' . implode(',', $str_p) : implode(',', $str_p);
                    $goods[] = array(
                        'name' => $row['productName'],
                        'packname' => $row['packname'],
                        'is_seckill_price' => $t_return['is_seckill_price'],//是否是秒杀价(0:否，1：是)
                        'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                        'discount_rate' => $discount_rate,//折扣率
                        'num' => $num,
                        'goods_id' => $goods_id,
                        'old_price' => floatval($t_return['old_price']),//商品原始价
                        'price' => floatval($tempPrice),//是秒杀的时候是秒杀价，不是的时候是原始价
                        'discount_price' => floatval($only_discount_price),//折扣价
                        'cost_price' => floatval($t_return['cost_price']),
                        'number' => $t_return['number'],
                        'image' => $t_return['image'],
                        'sort_id' => $t_return['sort_id'],
                        'packing_charge' => $t_return['packing_charge'],
                        'unit' => $t_return['unit'],
                        'str' => $str,
                        'spec_id' => $spec_str,
                        'extra_price' => $row['productExtraPrice']
                    );
                    $num = $discountNum;
                }
                if ($num > 0) {
                    $this_goods_total_price = $num * round($t_return['price'] * $t_discount * 0.01, 2);//本商品的折扣总价
                    $only_discount_price = round($t_return['price'] * $t_discount * 0.01, 2);
                    if ($sorts_discout['discount_type'] == 0) {//折上折
                        if ($vip_discount < 100) {
                            $is_discount = 1;
                            $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
                            $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
                        }
                        $this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);
                        $only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);
                    } else {//折扣最优
                        $t_vip_price = $num * round($t_return['price'] * $vip_discount * 0.01, 2);
                        if ($t_vip_price < $this_goods_total_price) {
                            $this_goods_total_price = $t_vip_price;
                            if ($vip_discount < 100) {
                                $is_discount = 1;
                                $discount_type = 3;
                                $discount_rate = $vip_discount;
                            }
                            $only_discount_price = round($t_return['price'] * $vip_discount * 0.01, 2);
                        }
                    }
                    $store_discount_money += $this_goods_total_price;
                    
                    $str = str_replace(array($t_return['name'], '(', ')'), '', $row['name']);
                    $goods[] = array(
                        'name' => $t_return['name'],
                        'is_seckill_price' => $t_return['is_seckill_price'],
                        'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                        'discount_rate' => $discount_rate,//折扣率
                        'num' => $num,
                        'goods_id' => $goods_id,
                        'old_price' => floatval($t_return['old_price']),//商品原始价
                        'price' => floatval($t_return['price']),
                        'discount_price' => floatval($only_discount_price),//折扣价
                        'cost_price' => floatval($t_return['cost_price']),
                        'number' => $t_return['number'],
                        'image' => $t_return['image'],
                        'sort_id' => $t_return['sort_id'],
                        'packing_charge' => $t_return['packing_charge'],
                        'unit' => $t_return['unit'],
                        'str' => $str,
                        'spec_id' => $spec_str,
                        'extra_price'=> 0
                    );
                }
            }
        }
        
        $minus_price = 0;
        //会员等级优惠  外卖费不参加优惠
        $vip_discount_money = round($store_discount_money, 2);
        
//         $discounts = D('Shop_discount')->get_discount_byids(array($store_id));
        $discounts = D('Shop_discount')->getDiscounts($store['mer_id'], $store_id);
        $discount_list = null;
        
        //优惠
        $sys_first_reduce = 0;//平台首单优惠
        $sto_first_reduce = 0;//店铺首单优惠
        $sys_full_reduce = 0;//平台满减
        $sto_full_reduce = 0;//店铺满减
        
        $platform_merchant = 0;//平台优惠中商家补贴的总和统计
        $platform_plat = 0;//平台优惠中平台补贴的总和统计
        
        $shopOrderDB = D("Shop_order");
        $noDiscountList = array();
        $sys_count = $shopOrderDB->where(array('uid' => $uid))->count();
        if (empty($sys_count) && $uid) {//平台首单优惠
            if ($d_tmp = $this->getReduce($discounts, 0, $vip_discount_money, 0, $is_discount)) {
                $dd_tmp['discount_type'] = 1;//平台首单
                $dd_tmp['money'] = $d_tmp['full_money'];
                $dd_tmp['minus'] = $d_tmp['reduce_money'];
                $dd_tmp['did'] = $d_tmp['id'];
                $dd_tmp['plat_money'] = $d_tmp['plat_money'];
                $dd_tmp['merchant_money'] = $d_tmp['merchant_money'];
                $discount_list['system_newuser'] = $dd_tmp;
                if ($d_tmp['plat_money'] > 0 || $d_tmp['merchant_money']) {
                    $sys_first_reduce += $d_tmp['plat_money'];
                    $platform_plat += $d_tmp['plat_money'];
                    $sto_first_reduce += $d_tmp['merchant_money'];
                    $platform_merchant += $d_tmp['merchant_money'];
                } else {
                    $sys_first_reduce += $d_tmp['reduce_money'];
                    $platform_plat += $d_tmp['reduce_money'];
                }
            }
            
            if ($d_tmp = $this->getNoShareReduce($discounts, 0, $vip_discount_money, 0, $is_discount)) {
                foreach ($d_tmp as $dt) {
                    if ($dt['is_share'] == 0) {
                        $noDiscountList[] = array('type' => 1, 'money' => $dt['full_money'], 'minus' => $dt['reduce_money']);
                    }
                }
            }
        }
        
        
        if ($uid && ($d_tmp = $this->getReduce($discounts, 1, $vip_discount_money, 0, $is_discount))) {
            $dd_tmp['discount_type'] = 2;//平台满减
            $dd_tmp['money'] = $d_tmp['full_money'];
            $dd_tmp['minus'] = $d_tmp['reduce_money'];
            $dd_tmp['did'] = $d_tmp['id'];
            $dd_tmp['plat_money'] = $d_tmp['plat_money'];
            $dd_tmp['merchant_money'] = $d_tmp['merchant_money'];
            $discount_list['system_minus'] = $dd_tmp;
            if ($d_tmp['plat_money'] > 0 || $d_tmp['merchant_money']) {
                $sys_full_reduce += $d_tmp['plat_money'];
                $platform_plat += $d_tmp['plat_money'];
                $sto_full_reduce += $d_tmp['merchant_money'];
                $platform_merchant += $d_tmp['merchant_money'];
            } else {
                $sys_full_reduce += $d_tmp['reduce_money'];
                $platform_plat += $d_tmp['reduce_money'];
            }
        }
        if ($d_tmp = $this->getNoShareReduce($discounts, 1, $vip_discount_money, 0, $is_discount)) {
            foreach ($d_tmp as $dt) {
                if ($dt['is_share'] == 0) {
                    $noDiscountList[] = array('type' => 2, 'money' => $dt['full_money'], 'minus' => $dt['reduce_money']);
                }
            }
        }
        
        $sto_count = $shopOrderDB->where(array('uid' => $uid, 'store_id' => $store_id))->count();
        
        if (empty($sto_count)) {
            if ($d_tmp = $this->getReduce($discounts, 0, $vip_discount_money, $store_id, $is_discount)) {
                $dd_tmp['discount_type'] = 3;//店铺首单
                $dd_tmp['money'] = $d_tmp['full_money'];
                $dd_tmp['minus'] = $d_tmp['reduce_money'];
                $dd_tmp['did'] = $d_tmp['id'];
                $dd_tmp['plat_money'] = $d_tmp['plat_money'];
                $dd_tmp['merchant_money'] = $d_tmp['merchant_money'];
                $discount_list['newuser'] = $dd_tmp;
                $sto_first_reduce += $d_tmp['reduce_money'];
            }
            if ($d_tmp = $this->getNoShareReduce($discounts, 0, $vip_discount_money, $store_id, $is_discount)) {
                foreach ($d_tmp as $dt) {
                    if ($dt['is_share'] == 0) {
                        $noDiscountList[] = array('type' => 3, 'money' => $dt['full_money'], 'minus' => $dt['reduce_money']);
                    }
                }
            }
        }
        if ($d_tmp = $this->getReduce($discounts, 1, $vip_discount_money, $store_id, $is_discount)) {
            $dd_tmp['discount_type'] = 4;//店铺满减
            $dd_tmp['money'] = $d_tmp['full_money'];
            $dd_tmp['minus'] = $d_tmp['reduce_money'];
            $dd_tmp['did'] = $d_tmp['id'];
            $dd_tmp['plat_money'] = $d_tmp['plat_money'];
            $dd_tmp['merchant_money'] = $d_tmp['merchant_money'];
            $discount_list['minus'] = $dd_tmp;
            $sto_full_reduce += $d_tmp['reduce_money'];
        }
        if ($d_tmp = $this->getNoShareReduce($discounts, 1, $vip_discount_money, $store_id, $is_discount)) {
            foreach ($d_tmp as $dt) {
                if ($dt['is_share'] == 0) {
                    $noDiscountList[] = array('type' => 4, 'money' => $dt['full_money'], 'minus' => $dt['reduce_money']);
                }
            }
        }
        
        //起步运费
        $delivery_fee = 0;
        //超出距离部分的单价
        $per_km_price = 0;
        //起步距离
        $basic_distance = 0;
        //减免配送费的金额
        $delivery_fee_reduce = 0;
        
        $plat_reduce_deliver_money = 0;
        $merchant_reduce_deliver_money = 0;
        
        //起步运费
        $delivery_fee2 = 0;
        //超出距离部分的单价
        $per_km_price2 = 0;
        //起步距离
        $basic_distance2 = 0;
        
        $deliverReturn = D('Deliver_set')->getDeliverInfo($store, $price);
        $delivery_fee = $deliverReturn['delivery_fee'];
        $per_km_price = $deliverReturn['per_km_price'];
        $basic_distance = $deliverReturn['basic_distance'];
        $delivery_fee2 = $deliverReturn['delivery_fee2'];
        $per_km_price2 = $deliverReturn['per_km_price2'];
        $basic_distance2 = $deliverReturn['basic_distance2'];
        $store['delivertime_start'] = $deliverReturn['delivertime_start'];
        $store['delivertime_stop'] = $deliverReturn['delivertime_stop'];
        $store['delivertime_start2'] = $deliverReturn['delivertime_start2'];
        $store['delivertime_stop2'] = $deliverReturn['delivertime_stop2'];
        
        if ($store_shop['deliver_type'] == 0 || $store_shop['deliver_type'] == 3) {//平台配送|平台或自提
            $store['basic_price'] = floatval($store_shop['s_basic_price']) ? floatval($store_shop['s_basic_price']) : (floatval(C('config.basic_price')) ? floatval(C('config.basic_price')) : floatval($store_shop['basic_price']));
            $store['extra_price'] = floatval($store_shop['s_extra_price']) ? floatval($store_shop['s_extra_price']) : floatval(C('config.extra_price'));
            //使用平台的优惠（配送费的减免）
            if ($d_tmp = $this->getReduce($discounts, 2, $price)) {
                $dd_tmp['discount_type'] = 5;//平台配送费满减
                $dd_tmp['money'] = $d_tmp['full_money'];
                $dd_tmp['minus'] = $d_tmp['reduce_money'];
                $dd_tmp['did'] = $d_tmp['id'];
                $dd_tmp['plat_money'] = $d_tmp['plat_money'];
                $dd_tmp['merchant_money'] = $d_tmp['merchant_money'];
                $discount_list['delivery'] = $dd_tmp;
                $delivery_fee_reduce = $d_tmp['reduce_money'];
                if ($d_tmp['plat_money'] > 0 || $d_tmp['merchant_money']) {
                    $plat_reduce_deliver_money += $d_tmp['plat_money'];
                    $platform_plat += $d_tmp['plat_money'];
                    $merchant_reduce_deliver_money += $d_tmp['merchant_money'];
                    $platform_merchant += $d_tmp['merchant_money'];
                } else {
                    $plat_reduce_deliver_money += $d_tmp['reduce_money'];
                    $platform_plat += $d_tmp['reduce_money'];
                }
            }
        }
        
        if (empty($goods)) {
            return array('error_code' => true, 'msg' => '购物车是空的');
        } else {
            $data = array('error_code' => false);
            $data['total'] = $total;
            $data['price'] = $price;//商品实际总价
            $data['extra_price'] = $extra_price;//商品实际总价
            $data['discount_price'] = $vip_discount_money;//折扣后的总价
            $data['goods'] = $goods;
            $data['store_id'] = $store_id;
            $data['mer_id'] = $mer_id;
            $data['store'] = $store;
            $data['discount_list'] = $discount_list;
    
            $data['delivery_type'] = $store_shop['deliver_type'];
    
            $data['sys_first_reduce'] = $sys_first_reduce;//平台新单优惠的金额
            $data['sys_full_reduce'] = $sys_full_reduce;//平台满减优惠的金额
            $data['sto_first_reduce'] = $sto_first_reduce;//店铺新单优惠的金额
            $data['sto_full_reduce'] = $sto_full_reduce;//店铺满减优惠的金额
            
            $data['platform_merchant'] = $platform_merchant;//平台优惠中商家补贴的总和统计
            $data['platform_plat'] = $platform_plat;//平台优惠中平台补贴的总和统计
    
            $data['store_discount_money'] = $store_discount_money;//店铺折扣后的总价
            $data['vip_discount_money'] = $vip_discount_money;//VIP折扣后的总价
            $data['packing_charge'] = $packing_charge;//总的打包费
    
            $data['delivery_fee'] = $delivery_fee;//起步配送费
            if ($address_id) {
                $full_money = floatval($express_freight['full_money']);
                if (!($full_money != 0 && $template_total_price >= $full_money)) {
                    $delivery_money_total += $express_freight['freight'];
                }
                $data['delivery_fee'] = $delivery_money_total;//起步配送费
            }
            
            $data['basic_distance'] = $basic_distance;//起步距离
            $data['per_km_price'] = $per_km_price;//超出起步距离部分的距离每公里的单价
            $data['delivery_fee_reduce'] = $delivery_fee_reduce;//配送费减免的金额
            
            //平台配送时配送费满减平台和商家各自承担的金额
            $data['plat_reduce_deliver_money'] = $plat_reduce_deliver_money; //平台减免的配送费
            $data['merchant_reduce_deliver_money'] = $merchant_reduce_deliver_money;//商家减免的配送金额
    
            $data['delivery_fee2'] = $delivery_fee2;//起步配送费
            $data['basic_distance2'] = $basic_distance2;//起步距离
            $data['per_km_price2'] = $per_km_price2;//超出起步距离部分的距离每公里的单价
            $data['userphone'] = isset($user['phone']) && $user['phone'] ? $user['phone'] : '';
            
            $data['noDiscountList'] = $noDiscountList;
            $data['noDiscountGoods'] = $noDiscountGoods;
            return $data;
        }
        
    }
    
    
    public function getReduce($discounts, $type, $price, $store_id = 0, $is_discount = 1)
    {
        $reduce_money = 0;
        $return = null;
        if (isset($discounts[$store_id])) {
            foreach ($discounts[$store_id] as $row) {
                if ($row['type'] == $type && ($row['is_share'] || $is_discount == 0)) {
                    if ($price >= $row['full_money']) {
                        if ($reduce_money < $row['reduce_money']) {
                            $reduce_money = $row['reduce_money'];
                            $return = $row;
                        }
                    }
                }
            }
        }
        return $return;
    }
    
    
    private function getNoShareReduce($discounts, $type, $price, $store_id = 0, $is_discount = 1)
    {
        $return = null;
        if (isset($discounts[$store_id])) {
            foreach ($discounts[$store_id] as $row) {
                if ($row['type'] == $type && $is_discount) {
                    if ($price >= $row['full_money']) {
                        $return[] = $row;
                    }
                }
            }
        }
        return $return;
    }
    
    
    
    /**
     * 经销市场的保存
     * @param array $goods
     * @param int $store_id
     * @return boolean|mixed|boolean|string|unknown
     */
    public function savePostForm($goods, $store_id)
    {
        $original_goods_id = isset($goods['goods_id']) ? intval($goods['goods_id']) : 0;
    
        $data = array('name' => $goods['name']);
        $data['unit'] = $goods['unit'];
        $data['old_price'] = empty($goods['old_price']) ? $goods['price'] : $goods['old_price'];
        $data['cost_price'] = empty($goods['cost_price']) ? 0 : $goods['cost_price'];
        $data['price'] = $goods['price'];
        $data['extra_pay_price'] = floatval($goods['extra_pay_price']);
        $data['seckill_price'] = $goods['seckill_price'];
        $data['seckill_stock'] = $goods['seckill_stock'];
        $data['seckill_type'] = $goods['seckill_type'];
        $data['seckill_open_time'] = $goods['seckill_open_time'];
        $data['seckill_close_time'] = $goods['seckill_close_time'];
        $data['packing_charge'] = $goods['packing_charge'];
        $data['stock_num'] = $goods['stock_num'];
        $data['sort'] = $goods['sort'];
        $data['status'] = $goods['status'];
        $data['print_id'] = $goods['print_id'];
        $data['sort_id'] = $goods['sort_id'];
        $data['des'] = $goods['des'];
        $data['image'] = $goods['pic'];
        $data['number'] = $goods['number'];
        $data['store_id'] = $store_id;
        $data['original_goods_id'] = $original_goods_id;
    
        $data['freight_template'] = intval($goods['freight_template']);
        $data['freight_type'] = intval($goods['freight_type']);
        $data['freight_value'] = floatval($goods['freight_value']);
    
        $data['last_time'] = time();
    
        //2016-11-08新增系统的商品分类▽
        $data['cat_fid'] = intval($goods['cat_fid']);
        $data['cat_id'] = intval($goods['cat_id']);
        //2016-11-08新增系统的商品分类△
    
        $delete_spec_ids = array();
        $delete_spec_val_ids = array();
        $delete_properties_ids = array();
    
        $spec_obj = M('Shop_goods_spec'); //规格表
        $spec_value_obj = M('Shop_goods_spec_value');//规格对应的属性值
        $properties_obj = M('Shop_goods_properties');//属性表
    
        $relation_map = '';
        if ($check_data = $this->field(true)->where(array('store_id' => $store_id, 'original_goods_id' => $original_goods_id))->find()) {
            $goods_id = $check_data['goods_id'];
            $relation_map = $check_data['relation_map'] ? json_decode($check_data['relation_map'], true) : '';
            if ($this->where(array('store_id' => $store_id, 'goods_id' => $goods_id))->save($data)) {
                //查找已有的属性和规格
//                 $old_spec = $spec_obj->field(true)->where(array('goods_id' => $goods_id, 'store_id' => $store_id))->select();
//                 foreach ($old_spec as $os) {
//                     $delete_spec_ids[] = $os['id'];
//                 }
//                 if ($delete_spec_ids) {
//                     $old_spec_val = $spec_value_obj->field(true)->where(array('sid' => array('in', $delete_spec_ids)))->select();
//                     foreach ($old_spec_val as $osv) {
//                         $delete_spec_val_ids[] = $osv['id'];
//                     }
//                 }
//                 $old_properties = $properties_obj->field(true)->where(array('goods_id' => $goods_id))->select();
//                 foreach ($old_properties as $op) {
//                     $delete_properties_ids[] = $op['id'];
//                 }
//                 unset($old_spec, $old_spec_val, $old_properties);
            } else {
                return false;
            }
        } else {
            $goods_id = $this->add($data);
//             echo $this->_sql();die;
            if (empty($goods_id)) return false;
        }
    
        //配置属性
        $properties = array();
        $spec = array();
        $list = array();

        $data_spec = array('store_id' => $store_id, 'goods_id' => $goods_id);
        foreach ($goods['spec_id'] as $k => $id) {//规格id集合
            $id = intval($id);
            $name = $data_spec['name'] = isset($goods['specs'][$k]) ? $goods['specs'][$k] : '';//规格名称
            $spec_val_id = isset($goods['spec_val_id'][$k]) ? $goods['spec_val_id'][$k] : array();//规格属性值的ID集合
            $spec_val = isset($goods['spec_val'][$k]) ? $goods['spec_val'][$k] : array();//规格属性值的名称集合

            $list[$k] = array();
            
            if (isset($relation_map['spec_id'][$id]) && $relation_map['spec_id'][$id]) {
                $newId = intval($relation_map['spec_id'][$id]);
                if ($spec_obj->field(true)->where(array('id' => $newId, 'goods_id' => $goods_id))->find()) {
                    $spec_obj->where(array('id' => $newId))->save($data_spec);
                } else {
                    $newId = $spec_obj->add($data_spec);
                    $relation_map['spec_id'][$id] = $newId;
                }
            } elseif ($id) {
                $newId = $spec_obj->add($data_spec);
                $relation_map['spec_id'][$id] = $newId;
            } else {
                $newId = $spec_obj->add($data_spec);
            }
            
            if ($newId) {//规格id
//                 $delete_spec_ids = array_diff($delete_spec_ids, array($id));
                $data_spec_val = array('sid' => $newId);
                foreach ($spec_val_id as $i => $vid) {
                    $vid = intval($vid);
                    $data_spec_val['name'] = $spec_val[$i];
                    if (isset($relation_map['spec_val_id'][$vid]) && $relation_map['spec_val_id'][$vid]) {
                        $newVID = intval($relation_map['spec_val_id'][$vid]);
                        if ($spec_value_obj->where(array('id' => $newVID, 'sid' => $newId))->find()) {
                            $spec_value_obj->where(array('id' => $newVID))->save($data_spec_val);
                        } else {
                            $newVID = $spec_value_obj->add($data_spec_val);
                            $relation_map['spec_val_id'][$vid] = $newVID;
                        }
                    } elseif ($vid) {
                        $newVID = $spec_value_obj->add($data_spec_val);
                        $relation_map['spec_val_id'][$vid] = $newVID;
                    } else {
                        $newVID = $spec_value_obj->add($data_spec_val);
                    }
                    
                    if ($newVID) {
//                      $delete_spec_val_ids = array_diff($delete_spec_val_ids, array($vid));
//                      $list[$k][$i] = array('spec_id' => $id, 'spec_name' => $name, 'spec_val_id' => $vid, 'spec_val_name' => $spec_val[$i]);
                        $list[$k][$i] = $newVID;
                    }
                }
            }
        }
        $spec_value = array();
        $this->format_str($list, 0, array(), $spec_value);
    
        $properties = array();
        $is_properties = 0;
        foreach ($goods['properties_id'] as $pi => $pid) {//属性id集合
            $pid = intval($pid);
            $is_properties = 1;
            $name = isset($goods['properties'][$pi]) ? $goods['properties'][$pi] : '';//属性名称
            $num = isset($goods['properties_num'][$pi]) ? intval($goods['properties_num'][$pi]) : 1;//属性值可选的数量
            $val = isset($goods['properties_val'][$pi]) ? implode(',', $goods['properties_val'][$pi]) : '';//属性的属性值
            
            if (isset($relation_map['properties_id'][$pid]) && $relation_map['properties_id'][$pid]) {
                $newPid = intval($relation_map['properties_id'][$pid]);
                if ($properties_obj->field(true)->where(array('goods_id' => $goods_id, 'id' => $newPid))->find()) {
                    $properties_obj->where(array('goods_id' => $goods_id, 'id' => $newPid))->save(array('name' => $name, 'val' => $val, 'num' => $num));
                } else {
                    $newPid = $properties_obj->add(array('goods_id' => $goods_id, 'name' => $name, 'val' => $val, 'num' => $num));
                    $relation_map['properties_id'][$pid] = $newPid;
                }
            } elseif ($pid) {
                $newPid = $properties_obj->add(array('goods_id' => $goods_id, 'name' => $name, 'val' => $val, 'num' => $num));
                $relation_map['properties_id'][$pid] = $newPid;
            } else {
                $newPid = $properties_obj->add(array('goods_id' => $goods_id, 'name' => $name, 'val' => $val, 'num' => $num));
            }
            
            if ($newPid) {
//                 $delete_properties_ids = array_diff($delete_properties_ids, array($newPid));
                $properties[] = array('id' => $newPid, 'name' => $name, 'val' => $val);
            }
        }
    
        $specs = '';
        $pre = '';
        foreach ($spec_value as $k => $v) {
            $old_price = isset($goods['old_prices'][$k]) ? $goods['old_prices'][$k] : 0;
            $cost_price = isset($goods['cost_prices'][$k]) ? $goods['cost_prices'][$k] : 0;
            $number = isset($goods['numbers'][$k]) ? $goods['numbers'][$k] : '';
            $price = isset($goods['prices'][$k]) ? $goods['prices'][$k] : 0;
            $seckill_price = isset($goods['seckill_prices'][$k]) ? floatval($goods['seckill_prices'][$k]) : 0;
            $stock_num = isset($goods['stock_nums'][$k]) ? intval($goods['stock_nums'][$k]) : 0;
            $old_price = $old_price ? $old_price : $price;
            $specs .= $pre . $v . '|' . $old_price . ':' . $price . ':' . $seckill_price . ':' . $stock_num . ':' . $cost_price  . '|';
            if ($properties) {
                // 				$specs .= '|';
                $ppre = '';
                foreach ($properties as $ti => $ps) {
                    $num = isset($goods['num' . $ti][$k]) && $goods['num' . $ti][$k] ? intval($goods['num' . $ti][$k]) : 1;
                    $specs .= $ppre . $ps['id'] . '=' . $num;
                    $ppre = ':';
                }
            }
            $number && $specs .= '|' . $number;
            $pre = '#';
        }
    
        //2016-11-08新增系统的商品分类属性值与商品直接的关系▽
        D('Goods_properties_relation')->where(array('gid' => $goods_id))->delete();
        if (isset($goods['goodsproperties'])) {
            foreach ($goods['goodsproperties'] as $pid) {
                D('Goods_properties_relation')->add(array('gid' => $goods_id, 'pid' => $pid));
            }
        }
        //2016-11-08新增系统的商品分类属性值与商品直接的关系△
    
        //
        //规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号 # 规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#...#规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号
        if ($this->where(array('goods_id' => $goods_id))->save(array('spec_value' => $specs, 'relation_map' => $relation_map ? json_encode($relation_map) : '', 'is_properties' => $is_properties, 'last_time' => $data['last_time'] + 1))) {
//             $delete_spec_ids && $spec_obj->where(array('id' => array('in', $delete_spec_ids)))->delete();
//             $delete_spec_val_ids && $spec_value_obj->where(array('id' => array('in', $delete_spec_val_ids)))->delete();
//             $delete_properties_ids && $properties_obj->where(array('id' => array('in', $delete_properties_ids)))->delete();
            //配置属性
            return $goods_id;
        } else {
            return false;
        }
    }
    
    
    
    public function checkBusinessTime($businessTime, $time, $startTime)
    {
        if (empty($businessTime)) return true;
        if ($time < $startTime) return false;
        foreach ($businessTime as $row) {
            if ($row['s'] <= $time && $time <= $row['e']) {
                return true;
            }
        }
        return false;
    }
    
    public function getSelectTime($return, $paramTime = null)
    {
        if ($paramTime === null) {
            $paramTime = time();
        }
        //预订的天数
        $advance_day = $return['store']['advance_day'];
        
        //配送时间段
        $start_time = $return['store']['delivertime_start'];
        $stop_time = $return['store']['delivertime_stop'];
        $start_time2 = $return['store']['delivertime_start2'];
        $stop_time2 = $return['store']['delivertime_stop2'];
        
        $start_time3 = $return['store']['delivertime_start3'];
        $stop_time3 = $return['store']['delivertime_stop3'];
        if ($start_time == $stop_time && $start_time == '00:00:00') {//时间段一，24小时
            $start_time = '00:00:00';
            $stop_time = '23:59:59';
        } elseif ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {//没有时间段二
            $stop_time2 = $start_time2 = 0;
        } elseif ($start_time3 == $stop_time3 && $start_time3 == '00:00:00') {//没有时间段三
            $stop_time3 = $start_time3 = 0;
        }

        //出单时长的单位
        $diffTime = 60;
        if ($return['store']['send_time_type'] == 1) {
            $diffTime = 3600;
        } elseif ($return['store']['send_time_type'] == 2) {
            $diffTime = 86400;
        } elseif ($return['store']['send_time_type'] == 3) {
            $diffTime = 86400 * 7;
        } elseif ($return['store']['send_time_type'] == 4) {
            $diffTime = 86400 * 30;
        }
        
        

        //营业时间
        $open_1 = $return['store']['open_1'];
        $close_1 = $return['store']['close_1'];
        $open_2 = $return['store']['open_2'];
        $close_2 = $return['store']['close_2'];
        $open_3 = $return['store']['open_3'];
        $close_3 = $return['store']['close_3'];
        
        $nowTime = $paramTime + $return['store']['work_time'] * $diffTime + $return['store']['send_time'] * 60;//默认的期望送达时间
		$this->defaultSendTime = $nowTime;
        
        if ('24小时营业' === D('Merchant_store_shop')->getBuniessName($return['store'])) {
            $open_1 = $close_1 = '00:00:00';
            $diffTime = 0;
        }
        
        $addTime = $return['store']['work_time'] * $diffTime + $return['store']['send_time'] * 60;
        
        //营业时间第一时间段的  开始时间等于结束时间 证明就是全天营业
        if ($open_1 == $close_1) {
            $open_1 = '00:00:00';
            $close_1 = '23:59:59';
            $open_2 = $close_2 = $open_3 = $close_3 = '00:00:00';
        }
        
        if ($nowTime >= strtotime(date('Y-m-d 23:59:59'))) {
            $open_2 = '00:00:00';
            $close_2 = '23:59:59';
        }

        $ot1 = strtotime(date('Y-m-d ' . $open_1));
        $ct1 = strtotime(date('Y-m-d ' . $close_1));
        $ot2 = strtotime(date('Y-m-d ' . $open_2));
        $ct2 = strtotime(date('Y-m-d ' . $close_2));
        $ot3 = strtotime(date('Y-m-d ' . $open_3));
        $ct3 = strtotime(date('Y-m-d ' . $close_3));

        $s1 = 0;
        $s2 = 0;
        $s3 = 0;
        $e1 = 0;
        $e2 = 0;
        $e3 = 0;

        $businessTime = array();
        if ($ot1 != $ct1) {
            $s1 = $ot1;
            $e1 = $ct1;
            if ($ot1 > $ct1) {
                $ct1 += 86400;
            }
            $businessTime[] = array(
                's' => $ot1 + $addTime,
                'e' => $ct1 + $addTime
            );
        }
        if ($ot2 != $ct2) {
            $s2 = $ot2;
            $e2 = $ct2;
            if ($ot2 > $ct2) {
                $ct2 += 86400;
            }
            $businessTime[] = array(
                's' => $ot2 + $addTime,
                'e' => $ct2 + $addTime
            );
        }
        if ($ot3 != $ct3) {
            $s3 = $ot3;
            $e3 = $ct3;
            if ($ot3 > $ct3) {
                $ct3 += 86400;
            }
            $businessTime[] = array(
                's' => $ot3 + $addTime,
                'e' => $ct3 + $addTime
            );
        }

        if ($e1 - $s2 > 86280 && $e1 - $s2 < 86400) {//时间段1和时间段2隔天相连
            $businessTime[] = array(
                's' => strtotime(date('Y-m-d ' . $open_2)) + 86400,
                'e' => strtotime(date('Y-m-d ' . $close_2)) + 86400 + $addTime
            );
        }
        if ($e2 - $s1 > 86280 && $e2 - $s1 < 86400) {//时间段2和时间段1隔天相连
            $businessTime[] = array(
                's' => strtotime(date('Y-m-d ' . $open_1)) + 86400,
                'e' => strtotime(date('Y-m-d ' . $close_1)) + 86400 + $addTime
            );
        }
        if ($e1 - $s3 > 86280 && $e1 - $s3 < 86400) {//时间段1和时间段3隔天相连
            $businessTime[] = array(
                's' => strtotime(date('Y-m-d ' . $open_3)) + 86400,
                'e' => strtotime(date('Y-m-d ' . $close_3)) + 86400 + $addTime
            );
        }
        if ($e3 - $s1 > 86280 && $e3 - $s1 < 86400) {//时间段3和时间段1隔天相连
            $businessTime[] = array(
                's' => strtotime(date('Y-m-d ' . $open_1)) + 86400,
                'e' => strtotime(date('Y-m-d ' . $close_1)) + 86400 + $addTime
            );
        }
        if ($e2 - $s3 > 86280 && $e2 - $s3 < 86400) {//时间段2和时间段3隔天相连
            $businessTime[] = array(
                's' => strtotime(date('Y-m-d ' . $open_3)) + 86400,
                'e' => strtotime(date('Y-m-d ' . $close_3)) + 86400 + $addTime
            );
        }
        if ($e3 - $s2 > 86280 && $e3 - $s2 < 86400) {//时间段3和时间段2隔天相连
            $businessTime[] = array(
                's' => strtotime(date('Y-m-d ' . $open_2)) + 86400,
                'e' => strtotime(date('Y-m-d ' . $close_2)) + 86400 + $addTime
            );
        }

        $startTime1 = strtotime(date('Y-m-d ' . $start_time));
        $stopTime1 = strtotime(date('Y-m-d ' . $stop_time));
        $deliverTimeList = array();
        if ($startTime1 > $stopTime1) {
            $deliverTimeList[] = array(
                's' => strtotime(date('Y-m-d 00:00:00')),
                'e' => $stopTime1,
                'select' => 1,
                'delivery_fee_old' => floatval(round($return['delivery_fee_old'], 2)),
                'delivery_fee' => floatval(round($return['delivery_fee'], 2))
            );
            $deliverTimeList[] = array(
                's' => $startTime1,
                'e' => $stopTime1 + 86400,
                'select' => 1,
                'delivery_fee_old' => floatval(round($return['delivery_fee_old'], 2)),
                'delivery_fee' => floatval(round($return['delivery_fee'], 2))
            );
        } else {
            $deliverTimeList[] = array(
                's' => $startTime1,
                'e' => $stopTime1,
                'select' => 1,
                'delivery_fee_old' => floatval(round($return['delivery_fee_old'], 2)),
                'delivery_fee' => floatval(round($return['delivery_fee'], 2))
            );
        }
        if ($start_time2 != '0' && $stop_time2 != '0') {
            $startTime2 = strtotime(date('Y-m-d ' . $start_time2));
            $stopTime2 = strtotime(date('Y-m-d ' . $stop_time2));
            if ($startTime2 > $stopTime2) {
                $deliverTimeList[] = array(
                    's' => strtotime(date('Y-m-d 00:00:00')),
                    'e' => $stopTime2,
                    'select' => 2,
                    'delivery_fee_old' => floatval(round($return['delivery_fee2_old'], 2)),
                    'delivery_fee' => floatval(round($return['delivery_fee2'], 2))
                );
                $deliverTimeList[] = array(
                    's' => $startTime2,
                    'e' => $stopTime2 + 86400,
                    'select' => 2,
                    'delivery_fee_old' => floatval(round($return['delivery_fee2_old'], 2)),
                    'delivery_fee' => floatval(round($return['delivery_fee2'], 2))
                );
            } else {
                $deliverTimeList[] = array(
                    's' => $startTime2,
                    'e' => $stopTime2,
                    'select' => 2,
                    'delivery_fee_old' => floatval(round($return['delivery_fee2_old'], 2)),
                    'delivery_fee' => floatval(round($return['delivery_fee2'], 2))
                );
            }
        }
        
        if ($start_time3 != '0' && $stop_time3 != '0') {
            $startTime3 = strtotime(date('Y-m-d ' . $start_time3));
            $stopTime3 = strtotime(date('Y-m-d ' . $stop_time3));
            if ($startTime3 > $stopTime3) {
                $deliverTimeList[] = array(
                    's' => strtotime(date('Y-m-d 00:00:00')),
                    'e' => $stopTime3,
                    'select' => 3,
                    'delivery_fee_old' => floatval(round($return['delivery_fee3_old'], 2)),
                    'delivery_fee' => floatval(round($return['delivery_fee3'], 2))
                );
                $deliverTimeList[] = array(
                    's' => $startTime3,
                    'e' => $stopTime3 + 86400,
                    'select' => 3,
                    'delivery_fee_old' => floatval(round($return['delivery_fee3_old'], 2)),
                    'delivery_fee' => floatval(round($return['delivery_fee3'], 2))
                );
            } else {
                $deliverTimeList[] = array(
                    's' => $startTime3,
                    'e' => $stopTime3,
                    'select' => 3,
                    'delivery_fee_old' => floatval(round($return['delivery_fee3_old'], 2)),
                    'delivery_fee' => floatval(round($return['delivery_fee3'], 2))
                );
            }
        }
        
        if ($stopTime1 - $startTime2 > 86330 && $stopTime1 - $startTime2 < 86400) {//时间段1和时间段2隔天相连
            $startTime2 += 86400;
            $stopTime2 += 86400;
            $deliverTimeList[] = array(
                's' => $startTime2,
                'e' => $stopTime2,
                'select' => 2,
                'delivery_fee_old' => floatval(round($return['delivery_fee2_old'], 2)),
                'delivery_fee' => floatval(round($return['delivery_fee2'], 2))
            );
        }
        if ($stopTime2 - $startTime1 > 86330 && $stopTime2 - $startTime1 < 86400) {//时间段2和时间段1隔天相连
            $startTime1 += 86400;
            $stopTime1 += 86400;
            $deliverTimeList[] = array(
                's' => $startTime1,
                'e' => $stopTime1,
                'select' => 1,
                'delivery_fee_old' => floatval(round($return['delivery_fee_old'], 2)),
                'delivery_fee' => floatval(round($return['delivery_fee'], 2))
            );
        }
        
        if ($stopTime1 - $startTime3 > 86330 && $stopTime1 - $startTime3 < 86400) {//时间段1和时间段3隔天相连
            $startTime3 += 86400;
            $stopTime3 += 86400;
            $deliverTimeList[] = array(
                's' => $startTime3,
                'e' => $stopTime3,
                'select' => 3,
                'delivery_fee_old' => floatval(round($return['delivery_fee3_old'], 2)),
                'delivery_fee' => floatval(round($return['delivery_fee3'], 2))
            );
        }
        if ($stopTime3 - $startTime1 > 86330 && $stopTime3 - $startTime1 < 86400) {//时间段3和时间段1隔天相连
            $startTime1 += 86400;
            $stopTime1 += 86400;
            $deliverTimeList[] = array(
                's' => $startTime1,
                'e' => $stopTime1,
                'select' => 1,
                'delivery_fee_old' => floatval(round($return['delivery_fee_old'], 2)),
                'delivery_fee' => floatval(round($return['delivery_fee'], 2))
            );
        }
        
        if ($stopTime2 - $startTime3 > 86330 && $stopTime2 - $startTime3 < 86400) {//时间段2和时间段3隔天相连
            $startTime3 += 86400;
            $stopTime3 += 86400;
            $deliverTimeList[] = array(
                's' => $startTime3,
                'e' => $stopTime3,
                'select' => 3,
                'delivery_fee_old' => floatval(round($return['delivery_fee3_old'], 2)),
                'delivery_fee' => floatval(round($return['delivery_fee3'], 2))
            );
        }
        if ($stopTime3 - $startTime2 > 86330 && $stopTime3 - $startTime2 < 86400) {//时间段3和时间段2隔天相连
            $startTime2 += 86400;
            $stopTime2 += 86400;
            $deliverTimeList[] = array(
                's' => $startTime2,
                'e' => $stopTime2,
                'select' => 2,
                'delivery_fee_old' => floatval(round($return['delivery_fee2_old'], 2)),
                'delivery_fee' => floatval(round($return['delivery_fee2'], 2))
            );
        }
        $newB = array(); // 营业时间
        $newD = array(); // 配送时间
        for ($day = 0; $day <= $advance_day; $day ++) {
            foreach ($businessTime as $btime) {
                $btime['s'] += $day * 86400;
                $btime['e'] += $day * 86400;
                $newB[] = $btime;
            }
            
            foreach ($deliverTimeList as $dtime) {
                $dtime['s'] += $day * 86400;
                $dtime['e'] += $day * 86400;
                $newD[] = $dtime;
            }
        }
        
        foreach ($deliverTimeList as $dtime) {
            $dtime['s'] += ($advance_day + 1) * 86400;
            $dtime['e'] += ($advance_day + 1) * 86400;
            $newD[] = $dtime;
        }
        $dateList = array();
        foreach ($newD as $row) {
            $stime = $row['s'];
            $etime = $row['e'];
            $_m_00 = strtotime(date('Y-m-d H:00', $stime));
            $_m_15 = strtotime(date('Y-m-d H:15', $stime));
            $_m_30 = strtotime(date('Y-m-d H:30', $stime));
            $_m_45 = strtotime(date('Y-m-d H:45', $stime));
            if ($_m_00 >= $stime) {
                $stime = $_m_00;
            } elseif ($_m_15 >= $stime) {
                $stime = $_m_15;
            } elseif ($_m_30 >= $stime) {
                $stime = $_m_30;
            } elseif ($_m_45 >= $stime) {
                $stime = $_m_45;
            } else {
                $stime = strtotime(date('Y-m-d H:00', $stime + 3600));
            }

            if ($row['s'] <= $nowTime && $nowTime < $row['e']) {
                if ($this->checkBusinessTime($newB, $nowTime, $nowTime)) {
                    $dateList[date('Y-m-d', $nowTime)][date('H:i', $nowTime)] = array(
                        'hour_minute' => date('H:i', $nowTime),
                        'time_select' => $row['select'],
                        'delivery_fee_old' => $row['delivery_fee_old'],
                        'delivery_fee' => $row['delivery_fee']
                    );
                }
            }
            for ($nowDate = $stime; $nowDate <= $etime;) {
                if ($this->checkBusinessTime($newB, $nowDate, $nowTime)) {
                    $dateList[date('Y-m-d', $nowDate)][date('H:i', $nowDate)] = array(
                        'hour_minute' => date('H:i', $nowDate),
                        'time_select' => $row['select'],
                        'delivery_fee_old' => $row['delivery_fee_old'],
                        'delivery_fee' => $row['delivery_fee']
                    );
                }
                $nowDate += 900;
            }
        }
        return $dateList;
    }
    
    
    
    public function getListByStoreId($store_id, $sort = 0, $keyword = '')
    {
        $sort_list = D('Shop_goods_sort')->lists($store_id);

        $now_shop = D('Merchant_store_shop')->field('stock_type')->where(array('store_id' => $store_id))->find();
        
        
        if ($sort == 1) {
            $g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->order('sell_count DESC, goods_id ASC')->select();
        } elseif ($sort == 2) {
            $g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->order('price DESC, goods_id ASC')->select();
        } elseif ($keyword != '') {
            $g_list = $this->field(true)->where(array('store_id' => $store_id, 'name' => array('like', '%' . $keyword . '%'), 'status' => 1))->order('sort DESC, goods_id ASC')->select();
        } else {
            $g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->order('sort DESC, goods_id ASC')->select();
        }
        
        $newList = array();
        $today = date('Ymd');
        $goods_image_class = new goods_image();
        $timeNow = time();
        foreach ($g_list as $r) {
            //新增限时显示
            if (!($r['show_start_time'] == $r['show_end_time'] || ($r['show_start_time'] == '00:00:00' && $r['show_end_time'] == '23:59:00'))) {
                $st = strtotime(date('Y-m-d') . ' ' . $r['show_start_time']);
                $et = strtotime(date('Y-m-d') . ' ' . $r['show_end_time']);
                if (!($st <= $timeNow && $timeNow <= $et)) {
                    continue;
                }
            }
            $glist = array();
            $glist['product_id'] = $r['goods_id'];
            $glist['product_name'] = $r['name'];
            $glist['product_price'] = strval(floatval($r['price']));
            $glist['is_seckill_price'] = strval(floatval($r['is_seckill_price']));
            $glist['o_price'] = strval(floatval($r['old_price']));
            $glist['number'] = $r['number'];
            $glist['packing_charge'] = strval(floatval($r['packing_charge']));
            $glist['unit'] = $r['unit'];
            //如果设置了最小起购，限购就无效
            if ($r['min_num'] > 1) {
                $glist['max_num'] = 0;
            } else {
                $glist['max_num'] = $r['max_num'];
            }
            $glist['min_num'] = $r['min_num'];
            $glist['limit_type'] = $r['limit_type'];
            
            $glist['product_image'] = '';
            $tmp_pic_arr = explode(';', $r['image']);
            foreach ($tmp_pic_arr as $key => $value) {
                if (empty($glist['product_image'])) {
                    $glist['product_image'] = $goods_image_class->get_image_by_path($value, 's');
                } else {
                    break;
                }
            }
            
            $glist['product_sale'] = $r['sell_count'];
            $glist['product_reply'] = $r['reply_count'];
            $glist['has_format'] = false;
            if ($r['spec_value'] || $r['is_properties']) {
                $glist['has_format'] = true;
            }
			$glist['has_spec']  = $r['spec_value'] ? true : false; //商品是否有规格
            
            if ($r['seckill_type'] == 1) {
                $now_time = date('H:i');
                $open_time = date('H:i', $r['seckill_open_time']);
                $close_time = date('H:i', $r['seckill_close_time']);
                
                // 秒杀库存的计算
                if ($today == $r['sell_day']) {
                    $seckill_stock_num = $r['seckill_stock'] == - 1 ? - 1 : (intval($r['seckill_stock'] - $r['today_seckill_count']) > 0 ? intval($r['seckill_stock'] - $r['today_seckill_count']) : 0);
                } else {
                    $seckill_stock_num = $r['seckill_stock'];
                }
            } else {
                $now_time = time();
                $open_time = $r['seckill_open_time'];
                $close_time = $r['seckill_close_time'];
                $seckill_stock_num = $r['seckill_stock'] == - 1 ? - 1 : (intval($r['seckill_stock'] - $r['today_seckill_count']) > 0 ? intval($r['seckill_stock'] - $r['today_seckill_count']) : 0);
            }
            
            $r['is_seckill_price'] = false;
            if ($open_time < $now_time && $now_time < $close_time && floatval($r['seckill_price']) > 0 && $seckill_stock_num != 0) {
                $glist['product_price'] = strval(floatval($r['seckill_price']));
                $r['is_seckill_price'] = true;
                $glist['o_price'] = strval(floatval($r['price']));
				$glist['seckill_discount'] = round($glist['product_price']/$glist['o_price']*10,1);
            }
            
            $r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
            if ($r['is_seckill_price']) {
                $glist['stock'] = $seckill_stock_num;
            } else {
                if ($today == $r['sell_day']) {
//                     $glist['stock'] = $r['stock_num'] == - 1 ? $r['stock_num'] : (intval($r['stock_num'] - $r['today_sell_count']) > 0 ? intval($r['stock_num'] - $r['today_sell_count']) : 0);
                    $glist['stock'] = $r['stock_num'];
                } else {
//                     $glist['stock'] = $r['stock_num'];
                    $glist['stock'] = $r['original_stock'];
                }
            }
            $glist['is_seckill_price'] = $r['is_seckill_price'];
            $glist['is_new'] = (($timeNow - $glist['last_time']) > 864000) ? 0 : 1;

            if(!$glist['spec_list']){
				if($glist['min_num'] > 0 && $glist['stock_num'] >= 0 && $glist['min_num'] > $glist['stock_num']){
					$glist['stock_num'] = 0;
				}
				if($glist['min_num'] > 0 && $glist['seckill_stock'] >= 0 && $glist['min_num'] > $glist['seckill_stock']){
					$glist['seckill_stock'] = 0;
				}
			}
			
            if (isset($newList[$r['sort_id']])) {
                $newList[$r['sort_id']][] = $glist;
            } else {
                $newList[$r['sort_id']] = array($glist);
            }
        }
        $result = array();
        $index = 0;
        foreach ($sort_list as $l) {
            $temp = array();
            $temp['cat_id'] = $l['sort_id'];
            $temp['cat_name'] = $l['sort_name'];
            $temp['level'] = $l['level'];
            $temp['index'] = $index;
            $temp['findex'] = 0;
            $temp['fid'] = 0;
            $temp['sort_discount'] = strval(round(intval($l['sort_discount']) * 0.1, 2));
            if (isset($newList[$l['sort_id']])) {
                $temp['product_list'] = $newList[$l['sort_id']];
                $temp['son_list'] = array();
            } elseif ($l['son_list']) {
                $temp['product_list'] = array();
                $son_list = $this->formatList($l['son_list'], $newList, $index, $l['sort_id']);
                if ($son_list) {
                    $temp['son_list'] = $son_list;
                } else {
                    $temp = null;
                }
            } else {
                $temp = null;
            }
            if (!empty($temp)) {
                $index ++;
                $result[] = $temp;
            }
        }
        return $result;
    }
    
    private function formatList($list, $newList, $findex, $fid)
    {
        if (empty($list)) {
            return $list;
        }
        $result = array();
        $index = 0;
        foreach ($list as $l) {
            $temp = array();
            $temp['cat_id'] = $l['sort_id'];
            $temp['level'] = $l['level'];
            $temp['index'] = $index;
            $temp['findex'] = $findex;
            $temp['fid'] = $fid;
            $temp['cat_name'] = $l['sort_name'];
            $temp['sort_discount'] = strval(round(intval($l['sort_discount']) * 0.1, 2));
            if (isset($newList[$l['sort_id']])) {
                $temp['product_list'] = $newList[$l['sort_id']];
                $temp['son_list'] = array();
            } elseif ($l['son_list']) {
                $temp['product_list'] = array();
                $son_list = $this->formatList($l['son_list'], $newList, $index, $l['sort_id']);
                if ($son_list) {
                    $temp['son_list'] = $son_list;
                } else {
                    $temp = null;
                }
            } else {
                $temp = null;
            }
            if (!empty($temp)) {
                $index ++;
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function get_qrcode($id)
    {
        $condition_good['goods_id'] = $id;
        $now_good = $this->field('`goods_id`')->where($condition_good)->find();
        if (empty($now_good)) {
            return false;
        }
        return $now_good;
    }
    
    /**
     * 判断店铺是否有秒杀商品
     * @param int $store_id
     * @return boolean
     */
    public function getDiscountGoods($store_id)
    {
        $nowTime = time();
        $where = 'store_id=' . $store_id . ' AND seckill_price>0 AND ((seckill_type=0 AND seckill_open_time<' . $nowTime . ' AND seckill_close_time>' . $nowTime . ') OR seckill_type=1)';
        $goodsList = $this->field(true)->where($where)->select();
        foreach ($goodsList as $goods) {
            if ($goods['seckill_type'] == 0) {
                return true;
            } else {
                $seckill_open_time = strtotime(date('Y-m-d')  . ' ' . date('H:i:s', $goods['seckill_open_time']));
                $seckill_close_time = strtotime(date('Y-m-d')  . ' ' . date('H:i:s', $goods['seckill_close_time']));
                if ($seckill_open_time < $nowTime && $nowTime < $seckill_close_time) {
                    return true;
                }
            }
        }
        return false;
    }
    
    public function findListByStoreid($store_id, $sort = 0, $keyword = '')
    {
        $database_goods_sort = D('Shop_goods_sort');
        $condition_goods_sort['store_id'] = $store_id;
        $sort_list = $database_goods_sort->field(true)->where($condition_goods_sort)->order('`sort` DESC,`sort_id` ASC')->select();
        $sort_image_class = new goods_sort_image();
        $goods_image_class = new goods_image();
        $s_list = array();
        $today = date('w');
        
        $today = date('Ymd');
        $storeShop = D('Merchant_store_shop')->where(array('store_id' => $store_id))->find();
        $stock_type = $storeShop['stock_type'];
        $sort_result = array();
        
        $timeNow = time();
        
        foreach ($sort_list as $value) {
            if (!empty($value['is_weekshow'])) {
                $week_arr = explode(',',$value['week']);
                if (!in_array($today, $week_arr)) {
                    continue;
                }
                $week_str = '';
                foreach ($week_arr as $k=>$v){
                    $week_str .= $this->get_week($v).' ';
                }
                $value['week_str'] = $week_str;
            }
            $value['see_image'] = $sort_image_class->get_image_by_path($value['image'],C('config.site_url'),'s');
            $s_list[$value['sort_id']] = $value;
        
        
            
            if ($sort == 1) {
                $g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1, 'sort_id' => $value['sort_id']))->order('sell_count DESC, goods_id ASC')->select();
            } elseif ($sort == 2) {
                $g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1, 'sort_id' => $value['sort_id']))->order('price DESC, goods_id ASC')->select();
            } elseif ($keyword != '') {
                $g_list = $this->field(true)->where(array('store_id' => $store_id, 'sort_id' => $value['sort_id'], 'name' => array('like', '%' . $keyword . '%'), 'status' => 1))->order('sort DESC, goods_id ASC')->select();
            } else {
                $g_list = $this->field(true)->where(array('store_id' => $store_id, 'sort_id' => $value['sort_id'], 'status' => 1))->order('sort DESC, goods_id ASC')->select();
            }
            

            foreach ($g_list as $row) {
                //新增限时显示
                if (!($row['show_start_time'] == $row['show_end_time'] || ($row['show_start_time'] == '00:00:00' && $row['show_end_time'] == '23:59:00'))) {
                    $st = strtotime(date('Y-m-d') . ' ' . $row['show_start_time']);
                    $et = strtotime(date('Y-m-d') . ' ' . $row['show_end_time']);
                    if (!($st <= $timeNow && $timeNow <= $et)) {
                        continue;
                    }
                }
                
                $row['sell_day'] = $stock_type ? $today : $row['sell_day'];
                if ($row['seckill_type'] == 1) {
                    $now_time = date('H:i');
                    $open_time = date('H:i', $row['seckill_open_time']);
                    $close_time = date('H:i', $row['seckill_close_time']);
                    //秒杀库存的计算
                    if ($today == $row['sell_day']) {
                        $seckill_stock_num = $row['seckill_stock'] == -1 ? -1 : (intval($row['seckill_stock'] - $row['today_seckill_count']) > 0 ? intval($row['seckill_stock'] - $row['today_seckill_count']) : 0);
                    } else {
                        $seckill_stock_num = $row['seckill_stock'];
                    }
                } else {
                    $now_time = time();
                    $open_time = $row['seckill_open_time'];
                    $close_time = $row['seckill_close_time'];
                    $seckill_stock_num = $row['seckill_stock'] == -1 ? -1 : (intval($row['seckill_stock'] - $row['today_seckill_count']) > 0 ? intval($row['seckill_stock'] - $row['today_seckill_count']) : 0);
                }
                $row['is_seckill_price'] = false;
                $row['o_price'] = floatval($row['price']);
                
                if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0 && $seckill_stock_num != 0) {
                    $row['price'] = floatval($row['seckill_price']);
                    $row['is_seckill_price'] = true;
                } else {
                    $row['price'] = floatval($row['price']);
                }
                
                $row['old_price'] = floatval($row['old_price']);
                $row['seckill_price'] = floatval($row['seckill_price']);
                $tmp_pic_arr = explode(';', $row['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $row['pic_arr'][$key]['title'] = $value;
                    $row['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value);
                }
                
                $return = $this->format_spec_value($row['spec_value'], $row['goods_id'], $row['is_properties']);
                $row['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
                $row['properties_status_list'] = isset($return['properties_status_list']) ? $return['properties_status_list'] : '';
                $row['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
                $row['list'] = isset($return['list']) ? $return['list'] : '';
                
                if ($sort == 1 || $sort == 2 || $keyword != '') {//pc端的排序与搜索
                    $sort_result[] = $row;
                } else {
                    if (isset($s_list[$row['sort_id']])) {
                        if (isset($s_list[$row['sort_id']]['goods_list'])) {
                            $s_list[$row['sort_id']]['goods_list'][] = $row;
                        } else {
                            $s_list[$row['sort_id']]['goods_list'] = array($row);
                        }
                    }
                }
            }
        
        }
        if ($sort == 1 || $sort == 2 || $keyword != '') {//pc端的排序与搜索
            $s_list = array(array('goods_list' => $sort_result, 'sort_id' => false));
        } else {
            foreach ($s_list as $k => $r) {
                if (!isset($r['goods_list'])) {
                    unset($s_list[$k]);
                }
            }
        }
        return $s_list;
    }
    
    
    
    
    
    
    
    public function checkCart($store_id, $uid, $goodsData, $isCookie = 1, $address_id = 0, $is_market = false)
    {
        $store = D("Merchant_store")->field(true)->where(array('store_id' => $store_id))->find();
        if ($store['have_shop'] == 0 || $store['status'] != 1) {
            return array('error_code' => true, 'msg' => '商家已经关闭了该业务,不能下单了!');
        }
        if (C('config.store_shop_auth') == 1 && $store['auth'] < 3) {
            return array('error_code' => true, 'msg' => '您查看的' . C('config.shop_alias_name') . '没有通过资质审核！');
        }
        
        $store_image_class = new store_image();
        $images = $store_image_class->get_allImage_by_path($store['pic_info']);
        $store['images'] = $images ? array_shift($images) : '';
        
        
        $store_shop = D("Merchant_store_shop")->field(true)->where(array('store_id' => $store_id))->find();
        if (empty($store) || empty($store_shop)) {
            return array('error_code' => true, 'msg' => '店铺信息有误');
        }
        
		
		//检测营业时间 开始
		$is_open = 0;
		 if (D('Merchant_store_shop')->checkTime($store) || $store_shop['advance_day'] > 0) {
            $is_open = 1;
        }
        if ($is_open == 0) {
            return array('error_code' => true, 'msg' => '本店休息中，暂时不接收新订单（营业时间: '.D('Merchant_store_shop')->getBuniessName($store).'）');
        }
        if ($store_shop['close_reason'] == '') {
            $close_reason = '店铺临时关闭';
        } else {
            $close_reason = '店铺临时关闭： ' . $store_shop['close_reason'];
        }
        if ($store_shop['is_close'] == 1) {
            return array('error_code' => true, 'msg' => $close_reason);
        }
		//检测营业时间 结束
		
		
        $store = array_merge($store, $store_shop);
        $mer_id = $store['mer_id'];
        
        if ($store['deliver_type'] == 0 || $store['deliver_type'] == 3) {
            $store['extra_price'] = floatval($store['s_extra_price']) ? floatval($store['s_extra_price']) : floatval(C('config.extra_price'));
        } else {
            $store['extra_price'] = floatval($store['extra_price']);//配送附加费
        }
        $store['delivery_range_polygon'] = substr($store['delivery_range_polygon'], 9, strlen($store['delivery_range_polygon']) - 11);
        $lngLatData = explode(',', $store['delivery_range_polygon']);
        array_pop($lngLatData);
        $lngLats = array();
        foreach ($lngLatData as $lnglat) {
            $lng_lat = explode(' ', $lnglat);
            $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
        }
        $store['delivery_range_polygon'] = $lngLats ? array($lngLats) : '';
        //用户的VIP折扣率
        $vip_discount = 100;
        $is_discount = 0;
        //店铺设置的vip等级折扣率
        $storeShopLevel = !empty($store_shop['leveloff']) ? unserialize($store_shop['leveloff']) : '';
        $user = M('User')->field(true)->where(array('uid' => $uid))->find();
        if ($storeShopLevel && $user) {
            if ($user['level']) {
                //系统设置的用户等级
                $tmpArr = M('User_level')->field(true)->order('`id` ASC')->select();
                $levelArray = array();
                foreach ($tmpArr as $vv) {
                    $levelArray[$vv['level']] = $vv;
                }
                if (isset($storeShopLevel[$user['level']]) && isset($levelArray[$user['level']])) {
                    $levelOff = $storeShopLevel[$user['level']];
                    if ($levelOff['type'] == 1) {
                        $vip_discount = $levelOff['vv'];
                    }
                }
            }
        }
        
        
        $goods = array();
        $price = 0;//原始总价
        $total = 0;//商品总数
        $extra_price = 0;//额外价格的总价
        $packing_charge = 0;//打包费
        //店铺优惠条件
        $sorts_discout = D('Shop_goods_sort')->get_sorts_discount($store_id);
        $store_discount_money = 0;//店铺折扣后的总价
        $noDiscountGoods = '';
        $useGoods = array();
        $useCouponMeny = 0;//统计可用商家优惠券的商品总价
        
        
        
        if ($address_id) {
            $user_adress = D('User_adress')->get_one_adress($uid, $address_id);
            $express_freight = array();
            $delivery_list = D('Express_template')->get_deliver_list($store['mer_id'], $store['store_id']);
            $goods_id_array = array();
            $delivery_money_total = 0;
            $max_freight = 0;
            $template_total_price = 0;
        }
        
        
            
            //处理拼单可能出现每个袋中有相同的商品导致数量超出
            $totalData = array();
            foreach ($goodsData as $row) {
                if ($isCookie == 0) {
                    $goods_id = $row['goods_id'];
                    $num = $row['num'];
                    
                    $spec_str = $row['spec_id'];
                    $str = $row['spec'];
                } elseif ($isCookie == 1) {
                    
                    $goods_id = $row['productId'];
                    $num = $row['count'];
                    
                    $spec_ids = array();
                    foreach ($row['productParam'] as $r) {
                        if ($r['type'] == 'spec') {
                            $spec_ids[] = $r['id'];
                        }
                    }
                    $spec_str = $spec_ids ? implode('_', $spec_ids) : '';
                } elseif ($isCookie == 2) {
                    $ids = explode('_', $row['goods_id']);
                    $goods_id = array_shift($ids);
                    $spec_str = $ids ? implode('_', $ids) : '';
                    $num = $row['num'];
                }
                
                if ($num < 1) continue;
                if (isset($totalData[$goods_id . '_' . $spec_str])) {
                    $totalData[$goods_id . '_' . $spec_str] += $num;
                } else {
                    $totalData[$goods_id . '_' . $spec_str] = $num;
                }
            }
            
            
            
            foreach ($goodsData as $row) {
                if ($isCookie == 0) {
                    $goods_id = $row['goods_id'];
                    $num = $row['num'];
                    $spec_str = $row['spec_id'];
                    $str = $row['spec'];
                } elseif ($isCookie == 1) {
                    $goods_id = $row['productId'];
                    $num = $row['count'];
                    $spec_ids = array();
                    $str_s = array(); 
                    $str_p = array();
                    foreach ($row['productParam'] as $r) {
                        if ($r['type'] == 'spec') {
                            $spec_ids[] = $r['id'];
                            $str_s[] = $r['name'];
                        } else {
                            foreach ($r['data'] as $d) {
                                $str_p[] = $d['name'];
                            }
                        }
                    }
                    $spec_str = $spec_ids ? implode('_', $spec_ids) : '';
                    
                    $str = '';
                    $str_s && $str = implode(',', $str_s);
                    $str_p && $str = $str ? $str . ';' . implode(',', $str_p) : implode(',', $str_p);
                } elseif ($isCookie == 2) {
                    $ids = explode('_', $row['goods_id']);
                    $goods_id = array_shift($ids);
                    $spec_str = $ids ? implode('_', $ids) : '';
					$num = $totalData[$goods_id . '_' . $spec_str];
                }
                if ($num < 1) continue;
                
                
                $tNum = isset($totalData[$goods_id . '_' . $spec_str]) ? intval($totalData[$goods_id . '_' . $spec_str]) : $num;
                
                $t_return = $this->check_stock($goods_id, $tNum, $spec_str, $store_shop['stock_type'], $store_id, $is_market, $uid);

                if ($isCookie == 2) {
                    $str = str_replace(array($t_return['name'], '(', ')'), '', $row['name']);
                }
                if ($is_market && $store['is_open_retail_pack'] == 0) {
                    $t_return['packing_charge'] = 0;
                }
                
                if ($t_return['status'] == 0) {
                    return array('error_code' => true, 'msg' => $t_return['msg']);
                } elseif ($t_return['status'] == 2) {
                    return array('error_code' => true, 'msg' => $t_return['msg']);
                }
                
                $total += $num;
                
                if ($t_return['is_seckill_price']) {
                    $is_discount = 1;
                }
                
                //处理每单中商品按优惠价计算的个数
                $oldNum = 0;
                $discountNum = 0;
                if ($t_return['limit_type'] == 0 && $t_return['maxNum'] > 0) {
					$tmpKey = $t_return['goods_id'].($spec_str ? '_'.$spec_str : '');
                    if (isset($useGoods[$tmpKey])) {
                        $useNum = $useGoods[$tmpKey];
                        $discountNum = max(0, $t_return['maxNum'] - $useNum);
                        if ($num < $discountNum) {
                            $discountNum = $num;
                        }
                        $useGoods[$tmpKey] += $discountNum;
                    } else {
                        if ($num > $t_return['maxNum']) {
                            $discountNum = $t_return['maxNum'];
                        } else {
                            $discountNum = $num;
                        }
                        $useGoods[$tmpKey] = $discountNum;
                    }
                    if ($num > $discountNum) {
                        $oldNum = $num - $discountNum;
                    }
                    if ($oldNum > 0) {
                        $price += floatval($t_return['old_price'] * $oldNum);
                    }
                    if ($discountNum > 0) {
                        $price += floatval($t_return['price'] * $discountNum);
                    }
                } else {
                    $price += floatval($t_return['price'] * $num);
                }
                
                $extra_price += $t_return['extra_price'] * $num;
                $packing_charge += $t_return['packing_charge'] * $num;
                
                if ($address_id) {
                    //-----计算运费--------  freight_type ==> 0:最大，1：单独
                    if ($t_return['freight_type'] == 0) {
                        $template_id = intval($t_return['freight_template']);
                        if ($user_adress) {
                            if (isset($delivery_list[$template_id][$user_adress['city']])) {
                                $express_freight_tmp = $delivery_list[$template_id][$user_adress['city']];
                            } elseif (isset($delivery_list[$template_id][$user_adress['province']])) {
                                $express_freight_tmp = $delivery_list[$template_id][$user_adress['province']];
                            } else {
                                $template_id = 0;
                                $express_freight_tmp = array('freight' => $t_return['freight_value'], 'full_money' => 0, 'tid' => 0);
                            }
                        } else {
                            $template_id = 0;
                            $express_freight_tmp = array('freight' => $t_return['freight_value'], 'full_money' => 0, 'tid' => 0);
                        }
                        if ($max_freight < $express_freight_tmp['freight']) {
                            $express_freight = $express_freight_tmp;
                            $max_freight = $express_freight_tmp['freight'];
                        }
                        $template_total_price += $t_return['price'] * $num;
                    } else {
                        if (!in_array($goods_id, $goods_id_array)) {
                            $template_id = intval($t_return['freight_template']);
                            if ($user_adress) {
                                if (isset($delivery_list[$template_id][$user_adress['city']])) {
                                    $delivery_money_total += $delivery_list[$template_id][$user_adress['city']]['freight'];
                                } elseif (isset($delivery_list[$template_id][$user_adress['province']])) {
                                    $delivery_money_total += $delivery_list[$template_id][$user_adress['province']]['freight'];
                                } else {
                                    $delivery_money_total += $t_return['freight_value'];
                                }
                            }
                            $goods_id_array[] = $goods_id;
                        }
                    }
                    //-----计算运费--------
                }
                
                
                //折扣($sorts_discout[$t_return['sort_id']]['discount_type'] == 1 ? '分类折扣' : '店铺折扣')
                $t_discount = isset($sorts_discout[$t_return['sort_id']]['discount']) && $sorts_discout[$t_return['sort_id']]['discount'] ? $sorts_discout[$t_return['sort_id']]['discount'] : 100;
                
                //该商品的折扣类型 0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                $discount_type = 0;
                //折扣率 0：无折扣
                $discount_rate = 0;
                if ($t_return['is_discount'] == 0) {
                    if ($t_discount < 100) {
                        $noDiscountGoods .= '【' . $t_return['name'] . '】';
                    }
                    $t_discount = 100;
                }
                if ($t_discount < 100) {
                    if ($sorts_discout[$t_return['sort_id']]['discount_type']) {//分类折扣
                        $discount_type = 2;
                    } else {
                        $discount_type = 1;
                    }
                    $discount_rate = $t_discount;
                    $is_discount = 1;
                }
                
                if ($oldNum > 0) {
                    $num = $oldNum;
                    $tempPrice = $t_return['old_price'];
                    $this_goods_total_price = $num * round($tempPrice * $t_discount * 0.01, 2);//本商品的折扣总价
                    $only_discount_price = round($tempPrice * $t_discount * 0.01, 2);
                    if ($sorts_discout['discount_type'] == 0) {//折上折
                        if ($vip_discount < 100) {
                            $is_discount = 1;
                            $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
                            $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
                        }
                        $this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);
                        $only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);
                    } else {//折扣最优
                        $t_vip_price = $num * round($tempPrice * $vip_discount * 0.01, 2);
                        if ($t_vip_price < $this_goods_total_price) {
                            $this_goods_total_price = $t_vip_price;
                            if ($vip_discount < 100) {
                                $is_discount = 1;
                                $discount_type = 3;
                                $discount_rate = $vip_discount;
                            }
                            $only_discount_price = round($tempPrice * $vip_discount * 0.01, 2);
                        }
                    }
                    $store_discount_money += $this_goods_total_price;
                    
                    if ($t_return['is_use_coupon']) {
                        $useCouponMeny += $this_goods_total_price;
                    }
                    $str = '';
                    $str_s && $str = implode(',', $str_s);
                    $str_p && $str = $str ? $str . ';' . implode(',', $str_p) : implode(',', $str_p);
                    $goods[] = array(
                        'name' => $row['productName']? $row['productName']: $row['name'],
                        'packname' => $row['packname'] ? $row['packname'] : '',
                        'is_seckill_price' => false,//是否是秒杀价(0:否，1：是)
                        'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                        'discount_rate' => $discount_rate,//折扣率
                        'num' => $num,
                        'goods_id' => $goods_id,
                        'old_price' => floatval($t_return['old_price']),//商品原始价
                        'price' => floatval($tempPrice),//是秒杀的时候是秒杀价，不是的时候是原始价
                        'discount_price' => floatval($only_discount_price),//折扣价
                        'cost_price' => floatval($t_return['cost_price']),
                        'number' => $t_return['number'],
                        'image' => $t_return['image'],
                        'sort_id' => $t_return['sort_id'],
                        'packing_charge' => $t_return['packing_charge'],
                        'unit' => $t_return['unit'],
                        'str' => $str,
                        'spec_id' => $spec_str,
                        'extra_price' => $t_return['extra_price'],
                        'is_use_coupon'=>$t_return['is_use_coupon'],
                        'jd_sku_id'=>$t_return['jd_sku_id']

                    );
                    $num = $discountNum;
                }
                if ($num > 0) {
                    $this_goods_total_price = $num * round($t_return['price'] * $t_discount * 0.01, 2);//本商品的店铺折扣后的总价
                    $only_discount_price = round($t_return['price'] * $t_discount * 0.01, 2);//本商品的店铺折扣的单价
                    if ($sorts_discout['discount_type'] == 0) {//折上折
                        if ($vip_discount < 100) {
                            $is_discount = 1;
                            $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
                            $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
                        }
                        $this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);//本商品的VIP折扣后的总价
                        $only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);//本商品的VIP折扣的单价
                    } else {//折扣最优
                        $t_vip_price = $num * round($t_return['price'] * $vip_discount * 0.01, 2);
                        if ($t_vip_price < $this_goods_total_price) {
                            $this_goods_total_price = $t_vip_price;
                            
                            if ($vip_discount < 100) {
                                $is_discount = 1;
                                $discount_type = 3;
                                $discount_rate = $vip_discount;
                            }
                            $only_discount_price = round($t_return['price'] * $vip_discount * 0.01, 2);
                        }
                    }
                    
                    $store_discount_money += $this_goods_total_price;//折扣后的商品总价（店铺，分类，VIP折扣都计算在内）
                    
                    if ($t_return['is_use_coupon']) {
                        $useCouponMeny += $this_goods_total_price;
                    }
                    $goods[] = array(
                        'name' => $row['productName']? $row['productName']: $row['name'],
						'packname' => $row['packname'] ? $row['packname'] : '',
                        'is_seckill_price' => $t_return['is_seckill_price'],//是否是秒杀价(0:否，1：是)
                        'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                        'discount_rate' => $discount_rate,//折扣率
                        'num' => $num,
                        'goods_id' => $goods_id,
                        'old_price' => floatval($t_return['old_price']),//商品原始价
                        'price' => floatval($t_return['price']),//
                        'discount_price' => floatval($only_discount_price),//折扣价
                        'cost_price' => floatval($t_return['cost_price']),
                        'number' => $t_return['number'],
                        'image' => $t_return['image'],
                        'sort_id' => $t_return['sort_id'],
                        'packing_charge' => $t_return['packing_charge'],
                        'unit' => $t_return['unit'],
                        'str' => $str,
                        'spec_id' => $spec_str,
                        'extra_price' => $t_return['extra_price'],
                        'is_use_coupon'=>$t_return['is_use_coupon'],
                        'jd_sku_id'=>$t_return['jd_sku_id']
                    );
                }
            }
        
        $minus_price = 0;
        //会员等级优惠  外卖费不参加优惠
        $vip_discount_money = round($store_discount_money, 2);
        
        //         $discounts = D('Shop_discount')->get_discount_byids(array($store_id));
        $discounts = D('Shop_discount')->getDiscounts($store['mer_id'], $store_id);
        $discount_list = null;
        
        //优惠
        $sys_first_reduce = 0;//平台首单优惠
        $sto_first_reduce = 0;//店铺首单优惠
        $sys_full_reduce = 0;//平台满减
        $sto_full_reduce = 0;//店铺满减
        
        $platform_merchant = 0;//平台优惠中商家补贴的总和统计
        $platform_plat = 0;//平台优惠中平台补贴的总和统计
        
        $shopOrderDB = D("Shop_order");
        $noDiscountList = array();
        $sys_count = $shopOrderDB->where(array('uid' => $uid))->count();
        if (empty($sys_count) && $uid) {//平台首单优惠
            if ($d_tmp = $this->getReduce($discounts, 0, $vip_discount_money, 0, $is_discount)) {
                $dd_tmp['discount_type'] = 1;//平台首单
                $dd_tmp['money'] = $d_tmp['full_money'];
                $dd_tmp['minus'] = $d_tmp['reduce_money'];
                $dd_tmp['did'] = $d_tmp['id'];
                $dd_tmp['plat_money'] = $d_tmp['plat_money'];
                $dd_tmp['merchant_money'] = $d_tmp['merchant_money'];
                $discount_list['system_newuser'] = $dd_tmp;
                if ($d_tmp['plat_money'] > 0 || $d_tmp['merchant_money']) {
                    $sys_first_reduce += $d_tmp['plat_money'];
                    $platform_plat += $d_tmp['plat_money'];
                    $sto_first_reduce += $d_tmp['merchant_money'];
                    $platform_merchant += $d_tmp['merchant_money'];
                } else {
                    $sys_first_reduce += $d_tmp['reduce_money'];
                    $platform_plat += $d_tmp['reduce_money'];
                }
            }
            
            if ($d_tmp = $this->getNoShareReduce($discounts, 0, $vip_discount_money, 0, $is_discount)) {
                foreach ($d_tmp as $dt) {
                    if ($dt['is_share'] == 0) {
                        $noDiscountList[] = array('type' => 1, 'money' => $dt['full_money'], 'minus' => $dt['reduce_money']);
                    }
                }
            }
        }
        
        if ($uid && ($d_tmp = $this->getReduce($discounts, 1, $vip_discount_money, 0, $is_discount))) {
            $dd_tmp['discount_type'] = 2;//平台满减
            $dd_tmp['money'] = $d_tmp['full_money'];
            $dd_tmp['minus'] = $d_tmp['reduce_money'];
            $dd_tmp['did'] = $d_tmp['id'];
            $dd_tmp['plat_money'] = $d_tmp['plat_money'];
            $dd_tmp['merchant_money'] = $d_tmp['merchant_money'];
            $discount_list['system_minus'] = $dd_tmp;
            if ($d_tmp['plat_money'] > 0 || $d_tmp['merchant_money']) {
                $sys_full_reduce += $d_tmp['plat_money'];
                $platform_plat += $d_tmp['plat_money'];
                $sto_full_reduce += $d_tmp['merchant_money'];
                $platform_merchant += $d_tmp['merchant_money'];
            } else {
                $sys_full_reduce += $d_tmp['reduce_money'];
                $platform_plat += $d_tmp['reduce_money'];
            }
        }
        if ($d_tmp = $this->getNoShareReduce($discounts, 1, $vip_discount_money, 0, $is_discount)) {
            foreach ($d_tmp as $dt) {
                if ($dt['is_share'] == 0) {
                    $noDiscountList[] = array('type' => 2, 'money' => $dt['full_money'], 'minus' => $dt['reduce_money']);
                }
            }
        }
        
        $sto_count = $shopOrderDB->where(array('uid' => $uid, 'store_id' => $store_id))->count();
        
        if (empty($sto_count)) {
            if ($d_tmp = $this->getReduce($discounts, 0, $vip_discount_money, $store_id, $is_discount)) {
                $dd_tmp['discount_type'] = 3;//店铺首单
                $dd_tmp['money'] = $d_tmp['full_money'];
                $dd_tmp['minus'] = $d_tmp['reduce_money'];
                $dd_tmp['did'] = $d_tmp['id'];
                $dd_tmp['plat_money'] = $d_tmp['plat_money'];
                $dd_tmp['merchant_money'] = $d_tmp['merchant_money'];
                $discount_list['newuser'] = $dd_tmp;
                $sto_first_reduce += $d_tmp['reduce_money'];
            }
            if ($d_tmp = $this->getNoShareReduce($discounts, 0, $vip_discount_money, $store_id, $is_discount)) {
                foreach ($d_tmp as $dt) {
                    if ($dt['is_share'] == 0) {
                        $noDiscountList[] = array('type' => 3, 'money' => $dt['full_money'], 'minus' => $dt['reduce_money']);
                    }
                }
            }
        }
        if ($d_tmp = $this->getReduce($discounts, 1, $vip_discount_money, $store_id, $is_discount)) {
            $dd_tmp['discount_type'] = 4;//店铺满减
            $dd_tmp['money'] = $d_tmp['full_money'];
            $dd_tmp['minus'] = $d_tmp['reduce_money'];
            $dd_tmp['did'] = $d_tmp['id'];
            $dd_tmp['plat_money'] = $d_tmp['plat_money'];
            $dd_tmp['merchant_money'] = $d_tmp['merchant_money'];
            $discount_list['minus'] = $dd_tmp;
            $sto_full_reduce += $d_tmp['reduce_money'];
        }
        if ($d_tmp = $this->getNoShareReduce($discounts, 1, $vip_discount_money, $store_id, $is_discount)) {
            foreach ($d_tmp as $dt) {
                if ($dt['is_share'] == 0) {
                    $noDiscountList[] = array('type' => 4, 'money' => $dt['full_money'], 'minus' => $dt['reduce_money']);
                }
            }
        }
        
        //起步运费
        $delivery_fee = 0;
        //超出距离部分的单价
        $per_km_price = 0;
        //起步距离
        $basic_distance = 0;
        //减免配送费的金额
        $delivery_fee_reduce = 0;
        
        $plat_reduce_deliver_money = 0;
        $merchant_reduce_deliver_money = 0;
        
        //起步运费
        $delivery_fee2 = 0;
        //超出距离部分的单价
        $per_km_price2 = 0;
        //起步距离
        $basic_distance2 = 0;
        
        $deliverReturn = D('Deliver_set')->getDeliverInfo($store, $price);
        $delivery_fee = $deliverReturn['delivery_fee'];
        $per_km_price = $deliverReturn['per_km_price'];
        $basic_distance = $deliverReturn['basic_distance'];
        $delivery_fee2 = $deliverReturn['delivery_fee2'];
        $per_km_price2 = $deliverReturn['per_km_price2'];
        $basic_distance2 = $deliverReturn['basic_distance2'];
        
        $delivery_fee3 = $deliverReturn['delivery_fee3'];
        $per_km_price3 = $deliverReturn['per_km_price3'];
        $basic_distance3 = $deliverReturn['basic_distance3'];
        
        $store['delivertime_start'] = $deliverReturn['delivertime_start'];
        $store['delivertime_stop'] = $deliverReturn['delivertime_stop'];
        $store['delivertime_start2'] = $deliverReturn['delivertime_start2'];
        $store['delivertime_stop2'] = $deliverReturn['delivertime_stop2'];
        
        $store['delivertime_start3'] = $deliverReturn['delivertime_start3'];
        $store['delivertime_stop3'] = $deliverReturn['delivertime_stop3'];
        
        if ($store_shop['deliver_type'] == 0 || $store_shop['deliver_type'] == 3) {//平台配送|平台或自提
            //时段起送价
            $start_time = $deliverReturn['delivertime_start'];
            $stop_time = $deliverReturn['delivertime_stop'];

            $start_time2 = $deliverReturn['delivertime_start2'];
            $stop_time2 = $deliverReturn['delivertime_stop2'];

            $start_time3 = $deliverReturn['delivertime_start3'];
            $stop_time3 = $deliverReturn['delivertime_stop3'];
            $time = time();
            $selectDeliverTime = 0;
            if (! ($start_time == $stop_time && $start_time == '00:00:00')) {
                $start_time = strtotime(date('Y-m-d') . ' ' . $start_time);
                $stop_time = strtotime(date('Y-m-d') . ' ' . $stop_time);
                if ($start_time > $stop_time) {
                    if (strtotime(date('Y-m-d')) <= $time && $time <= $stop_time) {
                        $selectDeliverTime = 1;
                    } else {
                        $stop_time2 += 86400;
                        if ($start_time <= $time && $time <= $stop_time) {
                            $selectDeliverTime = 1;
                        }
                    }
                } elseif ($start_time <= $time && $time <= $stop_time) {
                    $selectDeliverTime = 1;
                }
                if (! ($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
                    $start_time2 = strtotime(date('Y-m-d') . ' ' . $start_time2);
                    $stop_time2 = strtotime(date('Y-m-d') . ' ' . $stop_time2);

                    if ($start_time2 > $stop_time2) {
                        if (strtotime(date('Y-m-d')) <= $time && $time <= $stop_time2) {
                            $store['delivery_money'] = $delivery_fee2;
                            $selectDeliverTime = 2;
                        } else {
                            $stop_time2 += 86400;
                            if ($start_time2 <= $time && $time <= $stop_time2) {
                                $store['delivery_money'] = $delivery_fee2;
                                $selectDeliverTime = 2;
                            }
                        }
                    } elseif ($start_time2 <= $time && $time <= $stop_time2) {
                        $store['delivery_money'] = $delivery_fee2;
                        $selectDeliverTime = 2;
                    }
                }
                if (! ($start_time3 == $stop_time3 && $start_time3 == '00:00:00')) {
                    $start_time3 = strtotime(date('Y-m-d') . ' ' . $start_time3);
                    $stop_time3 = strtotime(date('Y-m-d') . ' ' . $stop_time3);

                    if ($start_time3 > $stop_time3) {
                        if (strtotime(date('Y-m-d')) <= $time && $time <= $stop_time3) {
                            $store['delivery_money'] = $delivery_fee3;
                            $selectDeliverTime = 3;
                        } else {
                            $stop_time3 += 86400;
                            if ($start_time3 <= $time && $time <= $stop_time3) {
                                $store['delivery_money'] = $delivery_fee3;
                                $selectDeliverTime = 3;
                            }
                        }
                    } elseif ($start_time3 <= $time && $time <= $stop_time3) {
                        $store['delivery_money'] = $delivery_fee3;
                        $selectDeliverTime = 3;
                    }
                }
            }else{
                $selectDeliverTime = 1;
            }
            $set = D('Deliver_set')->field(true)->where(array('area_id' => $store['area_id'], 'status' => 1))->find();
            if (empty($set)) {
                $set = D('Deliver_set')->field(true)->where(array('area_id' => $store['city_id'], 'status' => 1))->find();
                if (empty($set)) {
                    $set = D('Deliver_set')->field(true)->where(array('area_id' => $store['province_id'], 'status' => 1))->find();
                }
            }
            if($selectDeliverTime==1){
                if ($store['deliver_type'] == 0 || $store['deliver_type'] == 3) {
                    $store['basic_price'] = floatval($store['s_basic_price1']) && $store['s_is_open_own'] ? floatval($store['s_basic_price1']) : (floatval($store['s_basic_price']) ? floatval($store['s_basic_price']) :(floatval($set['basic_price1']) ? floatval($set['basic_price1']): (floatval(C('config.basic_price1')) ? floatval(C('config.basic_price1')):(floatval(C('config.basic_price')) ? floatval(C('config.basic_price')) : floatval($store['basic_price'])))));//起送价
                } else {
                    $store['basic_price'] = floatval($store['basic_price1'])?floatval($store['basic_price1']):floatval($store['basic_price']);//起送价
                }
            }elseif($selectDeliverTime==2){
                if ($store['deliver_type'] == 0 || $store['deliver_type'] == 3) {
                    $store['basic_price'] = floatval($store['s_basic_price2']) && $store['s_is_open_own'] ? floatval($store['s_basic_price2']) : (floatval($store['s_basic_price']) ? floatval($store['s_basic_price']) :(floatval($set['basic_price2']) ? floatval($set['basic_price2']): (floatval(C('config.basic_price2')) ? floatval(C('config.basic_price2')):(floatval(C('config.basic_price')) ? floatval(C('config.basic_price')) : floatval($store['basic_price'])))));//起送价
                } else {
                    $store['basic_price'] = floatval($store['basic_price2'])?floatval($store['basic_price2']):floatval($store['basic_price']);//起送价
                }

            }elseif($selectDeliverTime==3){
                if ($store['deliver_type'] == 0 || $store['deliver_type'] == 3) {
                    $store['basic_price'] = floatval($store['s_basic_price3']) && $store['s_is_open_own'] ? floatval($store['s_basic_price3']) : (floatval($store['s_basic_price']) ? floatval($store['s_basic_price']) : (floatval($set['basic_price3']) ? floatval($set['basic_price3']):(floatval(C('config.basic_price3')) ? floatval(C('config.basic_price3')):(floatval(C('config.basic_price')) ? floatval(C('config.basic_price')) : floatval($store['basic_price'])))));//起送价
                } else {
                    $store['basic_price'] = floatval($store['basic_price3'])?floatval($store['basic_price3']):floatval($store['basic_price']);//起送价
                }
            }else{
                if ($store['deliver_type'] == 0 || $store['deliver_type'] == 3) {
                    $store['basic_price'] = floatval($store['s_basic_price']) ? floatval($store['s_basic_price']) : (C('config.basic_price') ? floatval(C('config.basic_price')) : floatval($store['basic_price']));//起送价
                } else {
                    $store['basic_price'] = floatval($store['basic_price']);//起送价
                }
            }
            //$store['basic_price'] = floatval($store_shop['s_basic_price']) ? floatval($store_shop['s_basic_price']) : (floatval(C('config.basic_price')) ? floatval(C('config.basic_price')) : floatval($store_shop['basic_price']));
            $store['extra_price'] = floatval($store_shop['s_extra_price']) ? floatval($store_shop['s_extra_price']) : floatval(C('config.extra_price'));
            //使用平台的优惠（配送费的减免）
            if ($d_tmp = $this->getReduce($discounts, 2, $price)) {
                $dd_tmp['discount_type'] = 5;//平台配送费满减
                $dd_tmp['money'] = $d_tmp['full_money'];
                $dd_tmp['minus'] = $d_tmp['reduce_money'];
                $dd_tmp['did'] = $d_tmp['id'];
                $dd_tmp['plat_money'] = $d_tmp['plat_money'];
                $dd_tmp['merchant_money'] = $d_tmp['merchant_money'];
                $discount_list['delivery'] = $dd_tmp;
                $delivery_fee_reduce = $d_tmp['reduce_money'];
                if ($d_tmp['plat_money'] > 0 || $d_tmp['merchant_money']) {
                    $plat_reduce_deliver_money += $d_tmp['plat_money'];
                    $platform_plat += $d_tmp['plat_money'];
                    $merchant_reduce_deliver_money += $d_tmp['merchant_money'];
                    $platform_merchant += $d_tmp['merchant_money'];
                } else {
                    $plat_reduce_deliver_money += $d_tmp['reduce_money'];
                    $platform_plat += $d_tmp['reduce_money'];
                }
            }
        }
        
        if (empty($goods)) {
            return array('error_code' => true, 'msg' => '购物车是空的');
        } else {
            $data = array('error_code' => false);
            $data['total'] = $total;
            $data['price'] = $price;//商品实际总价
            $data['extra_price'] = $extra_price;//商品实际总价
            $data['discount_price'] = $vip_discount_money;//折扣后的总价
            $data['goods'] = $goods;
            $data['store_id'] = $store_id;
            $data['mer_id'] = $mer_id;
            $data['store'] = $store;
            $data['discount_list'] = $discount_list;
            
            $data['delivery_type'] = $store_shop['deliver_type'];
            
            $data['sys_first_reduce'] = $sys_first_reduce;//平台新单优惠的金额
            $data['sys_full_reduce'] = $sys_full_reduce;//平台满减优惠的金额
            $data['sto_first_reduce'] = $sto_first_reduce;//店铺新单优惠的金额
            $data['sto_full_reduce'] = $sto_full_reduce;//店铺满减优惠的金额
            
            $data['can_discount_money'] = $useCouponMeny;//可用商家优惠券的总价
            
            $data['platform_merchant'] = $platform_merchant;//平台优惠中商家补贴的总和统计
            $data['platform_plat'] = $platform_plat;//平台优惠中平台补贴的总和统计
            
            $data['store_discount_money'] = $store_discount_money;//店铺折扣后的总价
            $data['vip_discount_money'] = $vip_discount_money;//VIP折扣后的总价
            $data['packing_charge'] = $packing_charge;//总的打包费
            
            $data['delivery_fee'] = $delivery_fee;//起步配送费
            if ($address_id) {
                $full_money = floatval($express_freight['full_money']);
                if (!($full_money != 0 && $template_total_price >= $full_money)) {
                    $delivery_money_total += $express_freight['freight'];
                }
                $data['delivery_fee'] = $delivery_money_total;//起步配送费
            }
            
            $data['basic_distance'] = $basic_distance;//起步距离
            $data['per_km_price'] = $per_km_price;//超出起步距离部分的距离每公里的单价
            $data['delivery_fee_reduce'] = $delivery_fee_reduce;//配送费减免的金额
            
            //平台配送时配送费满减平台和商家各自承担的金额
            $data['plat_reduce_deliver_money'] = $plat_reduce_deliver_money; //平台减免的配送费
            $data['merchant_reduce_deliver_money'] = $merchant_reduce_deliver_money;//商家减免的配送金额
            
            $data['delivery_fee2'] = $delivery_fee2;//起步配送费
            $data['basic_distance2'] = $basic_distance2;//起步距离
            $data['per_km_price2'] = $per_km_price2;//超出起步距离部分的距离每公里的单价
            
            $data['delivery_fee3'] = $delivery_fee3;//起步配送费
            $data['basic_distance3'] = $basic_distance3;//起步距离
            $data['per_km_price3'] = $per_km_price3;//超出起步距离部分的距离每公里的单价
            $data['userphone'] = isset($user['phone']) && $user['phone'] ? $user['phone'] : '';
            
            $data['noDiscountList'] = $noDiscountList;
            $data['noDiscountGoods'] = $noDiscountGoods;
            return $data;
        }
        
    }
}
?>