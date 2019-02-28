<?php
class Market_orderModel extends Model
{
    public function getList($data, $type)
    {
        
        if ($type == 'sell') {
            import('@.ORG.merchant_page');
            $where = array('sell_mer_id' => $data['mer_id']);
            $sqlWhere = ' WHERE `o`.`sell_mer_id`=' . $data['mer_id'];
        } elseif ($type == 'buy') {
            import('@.ORG.merchant_page');
            $where = array('mer_id' => $data['mer_id']);
            $sqlWhere = ' WHERE `o`.`mer_id`=' . $data['mer_id'];
        } else {
            $where = 1;
            $sqlWhere = ' WHERE 1';
            import('@.ORG.system_page');
            if (isset($data['key']) && isset($data['key'])) {
                
            }
        }
        if (isset($data['goods_id']) && $data['goods_id']) {
            $where['goods_id'] = $data['goods_id'];
            $sqlWhere = ' WHERE o.goods_id=' . $data['goods_id'];
        }
        
        $count = $this->where($where)->count();
        $p = new Page($count, 20);
        
        if ($type == 'buy') {
            $sql = 'SELECT o.*, m.name AS merchant_name, m.phone AS merchant_phone, s.name AS store_name, s.phone AS store_phone';
            $sql .= ' FROM ' . C('DB_PREFIX') . 'merchant AS m INNER JOIN ' . C('DB_PREFIX') . 'market_order AS o ON o.sell_mer_id=m.mer_id INNER JOIN ' . C('DB_PREFIX') . 'merchant_store AS s ON s.store_id=o.sell_store_id';
        } elseif ($type == 'sell') {
            $sql = 'SELECT o.*, m.name AS merchant_name, m.phone AS merchant_phone';
            $sql .= ' FROM ' . C('DB_PREFIX') . 'market_order AS o INNER JOIN ' . C('DB_PREFIX') . 'merchant AS m ON o.mer_id=m.mer_id';
        } else {
            $sql = 'SELECT o.*, m.name AS merchant_name, m.phone AS merchant_phone, s.name AS store_name, s.phone AS store_phone, b.name AS buy_merchant_name, b.phone AS buy_merchant_phone';
            $sql .= ' FROM ';
            $sql .= C('DB_PREFIX') . 'merchant AS m';
            $sql .= ' INNER JOIN ' . C('DB_PREFIX') . 'merchant_store AS s ON s.mer_id=m.mer_id';
            $sql .= ' INNER JOIN ' . C('DB_PREFIX') . 'market_order AS o ON o.sell_store_id=s.store_id';
            $sql .= ' INNER JOIN ' . C('DB_PREFIX') . 'merchant AS b ON o.mer_id=b.mer_id';
        }
        $sql .= $sqlWhere;
        $sql .= ' ORDER BY o.pay_time DESC';
        $sql .= ' LIMIT ' . $p->firstRow . ',' . $p->listRows;
        $orders = $this->query($sql);
//         echo $this->_sql();
//         $orders = $this->field(true)->where($where)->order('pay_time DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $goods_image_class = new goods_image();
        foreach ($orders as &$order) {
            if(!empty($order['image'])){
                $tmp_pic_arr = explode(';', $order['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $order['pic'] || $order['pic'] = $goods_image_class->get_image_by_path($value, 's');
                }
            }
            $order['discount_info_txt'] = '无优惠';
            if ($order['discount_info']) {
               $order['discount_info'] = json_decode($order['discount_info'], true);
               $order['discount_info_txt'] = '批发满:' . $order['discount_info']['num'] . $order['unit'] . ',享受：' . $order['discount_info']['discount'] . '折优惠<br/>';
            }
            $order['status_txt'] = '';
            switch ($order['status']) {
                case 1:
                    $order['status_txt'] = '<span style="color:green">已支付</span>';
                    break;
                case 2:
                    $order['status_txt'] = '<span style="color:green">已发货</span>';
                    break;
                case 3:
                    $order['status_txt'] = '<span style="color:green">已收货</span>';
                    break;
                case 10:
                    $order['status_txt'] = '<span style="color:red">未支付</span>';
                    break;
            }
            
//             $return = $this->format_spec_value($order['spec_value'], $order['goods_id'], $order['order_id']);
//             $list = isset($return['list']) ? $return['list'] : '';
//             $spec_list = isset($return['spec_list']) ? $return['spec_list'] : '';
//             $order['good_detail'] = '';
//             if ($spec_list) {
//                 $order['good_detail'] .= '<div>';
//                 $order['good_detail'] .= '<span>商品条形码</span>';
//                 foreach ($spec_list as $gs) {
//                     $order['good_detail'] .= '<span>' . $gs['name'] . '</span>';
//                 }
//                 $order['good_detail'] .= '<span>批发价</span>';
//                 $order['good_detail'] .= '<span>本次批发数</span>';
//                 $order['good_detail'] .= '<span>总价</span>';
//                 $order['good_detail'] .= '</div>';
//                 foreach ($list as $gl) {
//                     $order['good_detail'] .= '<div>';
//                     $order['good_detail'] .= '<span>' . $gl['number'] . '</span>';
//                     foreach ($gl['spec'] as $g) {
//                         $order['good_detail'] .= '<span>' . $g['spec_val_name'] . '</span>';
//                     }
//                     $order['good_detail'] .= '<span>' . $gl['price'] . '</span>';
//                     $order['good_detail'] .= '<span>' . $gl['stock_num'] . '</span>';
//                     $order['good_detail'] .= '<span>' . floatval($gl['stock_num'] * $gl['price']) . '</span>';
//                     $order['good_detail'] .= '</div>';
//                 }
//             }
        }
        return array('orders' => $orders, 'totalPage' => $p->totalPage, 'pagebar' => $p->show());
    }
    
    public function format_spec_value($str, $goods_id, $order_id)
    {
        if ($str) {
            $spec_obj = M('Market_order_spec'); //规格表
            $spec_value_obj = M('Market_order_spec_value');//规格对应的属性值
            $goods_spec_temp = $spec_obj->field(true)->where(array('goods_id' => $goods_id, 'order_id' => $order_id))->order('id ASC')->select();
            $goods_spec_list = array();
            $specids = array();
            foreach ($goods_spec_temp as $goods_t) {
                $specids[] = $goods_t['id'];
                $goods_spec_list[$goods_t['id']] = $goods_t;
            }
            unset($goods_spec_temp);
            $spec_valuse_list = array();
            if ($specids) {
                $spec_valuse_temp = $spec_value_obj->field(true)->where(array('sid' => array('in', $specids), 'order_id' => $order_id))->order('id ASC')->select();
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
                $return[$index]['seckill_price'] = floatval($prices[2]);
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
        $return && $data['list'] = $return;
        $json && $data['json'] = $json;
        return $data = $data ? $data : null;
    }

    public function get_pay_order($mer_id, $order_id, $is_web = false)
    {
        $now_order = $this->field(true)->where(array('mer_id' => $mer_id, 'order_id' => $order_id))->find();
        if (empty($now_order)) {
            return array(
                'error' => 1,
                'msg' => '当前订单不存在！'
            );
        }
        
        if ($is_web) {
            $order_info = array(
                'order_id' => $now_order['order_id'],
                'order_type' => 'market',
                'recharge_money' => floatval($now_order['money']),
                'order_name' => $now_order['name'],
                'order_num' => $now_order['num'],
                'num' => $now_order['num'],
                'price' => floatval($now_order['money']),
                'money' => floatval($now_order['money']),
                'order_total_money' => floatval($now_order['money'])
            );
        }
        return array(
            'error' => 0,
            'order_info' => $order_info
        );
    }

    public function web_befor_pay($order_info, $now_merchant)
    {
//         $data_user_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
//         $data_user_recharge_order['submit_order_time'] = $_SERVER['REQUEST_TIME'];
//         $condition_user_recharge_order['order_id'] = $order_info['order_id'];
//         if (! $this->where($condition_user_recharge_order)->data($data_user_recharge_order)->save()) {
//             return array(
//                 'error_code' => true,
//                 'msg' => '保存订单失败！请重试或联系管理员。'
//             );
//         }
        return array(
            'error_code' => false,
            'pay_money' => $order_info['order_total_money']
        );
    }

    public function after_pay($order_param)
    {
        if ($order_param['pay_type'] != '') {
            $where['id'] = $order_param['order_id'];
        } else {
            $where['id'] = $order_param['order_id'];
        }
        
        $now_order = D('Market_total')->field(true)->where($where)->find();
        
        if (empty($now_order)) {
            return array(
                'error' => 1,
                'msg' => '当前订单不存在'
            );
        } elseif ($now_order['status'] != 0) {
            return array(
                'error' => 1,
                'msg' => '该订单已付款！',
            );
        } else {
            // 得到当前商家信息，不将session作为调用值，因为可能会失效或错误。
            $now_merchant = D('Merchant')->get_info($now_order['mer_id']);
            
            if (empty($now_merchant)) {
                return array(
                    'error' => 1,
                    'msg' => '没有查找到此订单归属的商家，请联系管理员！'
                );
            }
            
            $data_user_recharge_order = array();
            $data_user_recharge_order['pay_time'] = $_SERVER['REQUEST_TIME'];
            $data_user_recharge_order['payment_money'] = floatval($order_param['pay_money']);
            $data_user_recharge_order['pay_type'] = $order_param['pay_type'];
            $data_user_recharge_order['third_id'] = $order_param['third_id'];
            $data_user_recharge_order['status'] = 1;
            if ($this->where($where)->save($data_user_recharge_order)) {
                $order_param['desc']  = '批发在线充值' ;
                $order_param['mer_id']  = $now_order['mer_id'] ;
                $order_param['order_type']  = 'market' ;

                D('SystemBill')->bill_method(0,$order_param);

                // $return = D('Merchant_money_list')->add_money($order_param);
                $now_merchant = D('Merchant')->get_info($now_order['mer_id']);
                if ($now_merchant['money'] > 0 && $now_merchant['status'] == 3) {
                    M('Merchant')->where(array(
                        'mer_id' => $now_order['mer_id']
                    ))->setField('status', 1);
                }
                unset($data_user_recharge_order['payment_money']);
                $data_user_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
                $data_user_recharge_order['paid'] = 1;
                $this->where(array('fid' => $now_order['id']))->save($data_user_recharge_order);
                
                D('Scroll_msg')->add_msg('mer_recharge', $now_merchant['uid'], '商家【' . $now_merchant['name'] . '】于' . date('Y-m-d H:i', $_SERVER['REQUEST_TIME']) . '充值成功！');
                
                return array(
                    'error' => 0,
                    'msg' => '充值成功！',
//                     'url' => $this->get_pay_after_url($now_order['label'], $now_order['is_mobile_pay'], $now_order)
                );
            } else {
                return array(
                    'error' => 1,
                    'msg' => '修改订单状态失败，请联系系统管理员！'
                );
            }
        }
    }
}