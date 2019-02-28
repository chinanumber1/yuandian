<?php
class Market_goodsModel extends Model
{
    public function getList($data, $type)
    {
        
        $where = array();
        $sqlWhere = ' WHERE 1 ';
        if ($type == 'my') {
            import('@.ORG.merchant_page');
            if (!isset($data['mer_id']) || empty($data['mer_id'])) {
                return false;
            }
            $where['mer_id'] = $data['mer_id'];
            $sqlWhere .= ' AND g.mer_id=' . $data['mer_id'];
        } elseif ($type == 'market') {
            import('@.ORG.merchant_page');
            if (!isset($data['mer_id']) || empty($data['mer_id'])) {
                return false;
            }
            $where['mer_id'] = array('neq', $data['mer_id']);
            $sqlWhere .= ' AND g.mer_id<>' . $data['mer_id'];
            
            if (isset($data['cat_fid']) && $data['cat_fid'] && isset($data['cat_id']) && $data['cat_id']) {
                $where['cat_fid'] = $data['cat_fid'];
                $where['cat_id'] = $data['cat_id'];
                $sqlWhere .= ' AND g.cat_fid=' . $data['cat_fid'] . ' AND g.cat_id=' . $data['cat_id'];
            }
            if(isset($data['goods_name']) && $data['goods_name']){
                $sqlWhere .= " AND g.name LIKE '%{$data['goods_name']}%'";
            }
        } else {
            import('@.ORG.system_page');
            if (isset($data['key']) && $data['key']) {
                $where['name'] = array('like', '%' . $data['key'] . '%');
                $sqlWhere = " AND g.name LIKE '%{$data['key']}%'";
            }
        }
        if (isset($data['status']) && $data['status'] != -1) {
            $where['status'] = $data['status'];
            $sqlWhere .= " AND g.status=" . $data['status'];
        }
        
        if (isset($data['province_id']) && $data['province_id']) {
            $where['province_id'] = $data['province_id'];
            $sqlWhere .= " AND g.province_id=" . $data['province_id'];
        }
        
        if (isset($data['city_id']) && $data['city_id']) {
            $where['city_id'] = $data['city_id'];
            $sqlWhere .= " AND g.city_id=" . $data['city_id'];
        }
        
        if (isset($data['area_id']) && $data['area_id']) {
            $where['area_id'] = $data['area_id'];
            $sqlWhere .= " AND g.area_id=" . $data['area_id'];
        }
        
        
        $count = $this->where($where)->count();
        $p = new Page($count, 20);
        
        $sql = 'SELECT g.*, m.name AS merchant_name, m.phone AS merchant_phone, s.name AS store_name, s.phone AS store_phone'; 
        $sql .= ' FROM ' . C('DB_PREFIX') . 'market_goods AS g INNER JOIN ' . C('DB_PREFIX') . 'merchant_store AS s ON g.store_id=s.store_id INNER JOIN ' . C('DB_PREFIX') . 'merchant AS m ON s.mer_id=m.mer_id AND m.status=1';
        $sql .= $sqlWhere;
        $sql .= ' ORDER BY g.last_time DESC';
        $sql .= ' LIMIT ' . $p->firstRow . ',' . $p->listRows;
        $orders = $this->query($sql);
        $goods_image_class = new goods_image();
        $catids = array();
        foreach ($orders as &$order) {
            if (!in_array($order['cat_id'], $catids)) {
                $catids[] = $order['cat_id'];
            }
            if (!in_array($order['cat_fid'], $catids)) {
                $catids[] = $order['cat_fid'];
            }
            if(!empty($order['image'])){
                $tmp_pic_arr = explode(';', $order['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $order['pic'] || $order['pic'] = $goods_image_class->get_image_by_path($value, 's');
                }
            }
            $order['discount_info_txt'] = '暂无优惠';
            if ($order['discount_info']) {
                $order['discount_info_txt'] = '';
               $order['discount_info'] = json_decode($order['discount_info'], true);
               foreach ($order['discount_info'] as $row) {
                   $order['discount_info_txt'] .= '批发满:' . $row['num'] . $order['unit'] . ',享受：' . $row['discount'] . '折优惠<br/>';
               }
            }
        }
        if ($catids) {
            $items = D('Goods_wholesale_category')->field('id, name')->where(array('id' => array('in', $catids)))->order('`sort` DESC, `id` ASC')->select();
            $catList = array();
            foreach ($items as $item) {
                $catList[$item['id']] = $item['name'];
            }
            
            foreach ($orders as &$row) {
                $row['cat_fname'] = isset($catList[$row['cat_fid']]) ? $catList[$row['cat_fid']] : '';
                $row['cat_name'] = isset($catList[$row['cat_id']]) ? $catList[$row['cat_id']] : '';
            }
        }
        
        return array('orders' => $orders, 'totalPage' => $p->totalPage, 'pagebar' => $p->show());
    }
    
    
    public function getAll($param)
    {
        ;
    }
    public function format_spec_value($str, $goods_id, $is_prorerties = 1)
    {
//         if ($is_prorerties || $str) {
//             $properties_obj = M('Market_goods_properties');
//             $goods_properties_temp = $properties_obj->field(true)->where(array('goods_id' => $goods_id))->order('id asc')->select();
//             $goods_properties_list = array();
//             foreach ($goods_properties_temp as $goods_p) {
//                 $goods_p['val'] = explode(',', $goods_p['val']);
//                 $goods_properties_list[$goods_p['id']] = $goods_p;
//             }
//             unset($goods_properties_temp);
//         }
    
        if ($str) {
            $spec_obj = M('Market_goods_spec'); //规格表
            $spec_value_obj = M('Market_goods_spec_value');//规格对应的属性值
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
                    $spec_data[] = array('spec_val_id' => $id, 'spec_val_name' => isset($spec_valuse_list[$id]['name']) ? $spec_valuse_list[$id]['name'] : '');
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
                $return[$index]['min_num'] = intval($prices[2]);
                $return[$index]['buy_num'] = intval($prices[2]);
                $return[$index]['stock_num'] = $prices[3];
                $return[$index]['cost_price'] = isset($prices[4]) ? $prices[4] : 0;
    
                $return[$index]['number'] = isset($row_array[3]) ? $row_array[3] : '';
    
                if (isset($row_array[2]) && $row_array[2] && strstr($row_array[2], '=')) {
                    $p_data = array();
                    $tdata = array();
                    $properties = explode(':', $row_array[2]);
                    foreach ($properties as $k => $pro) {
                        $pro_array = explode('=', $pro);
                        $p_data[] = array('id' => intval($pro_array[0]), 'num' => intval($pro_array[1]), 'name' => isset($goods_properties_list[$pro_array[0]]['name']) ? $goods_properties_list[$pro_array[0]]['name'] : '');
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
                $json[$t_index]['min_num[]'] = $prices[2];
                $json[$t_index]['stock_nums[]'] = $prices[3];
                $json[$t_index]['numbers[]'] = isset($row_array[3]) ? $row_array[3] : '';
            }
        }
    
        $data = array();
        $new_goods_spec_list && $data['spec_list'] = $new_goods_spec_list;
//         $goods_properties_list && $data['properties_list'] = $goods_properties_list;
        $return && $data['list'] = $return;
        $json && $data['json'] = $json;
        return $data = $data ? $data : null;
        // 		return array('spec_list' => $goods_spec_list, 'properties_list' => $goods_properties_list, 'list' => $return, 'json' => $json);
    }
}