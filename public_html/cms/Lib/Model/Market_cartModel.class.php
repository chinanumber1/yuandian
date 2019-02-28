<?php
class Market_cartModel extends Model
{

    public function format_spec_value($str, $goods_id, $cartid)
    {
        if ($str) {
            $spec_obj = M('Market_goods_spec'); //规格表
            $spec_value_obj = M('Market_goods_spec_value');//规格对应的属性值
            $goods_spec_temp = $spec_obj->field(true)->where(array('goods_id' => $goods_id, 'cartid' => $cartid))->order('id ASC')->select();
            $goods_spec_list = array();
            $specids = array();
            foreach ($goods_spec_temp as $goods_t) {
                $specids[] = $goods_t['id'];
                $goods_spec_list[$goods_t['id']] = $goods_t;
            }
            unset($goods_spec_temp);
            $spec_valuse_list = array();
            if ($specids) {
                $spec_valuse_temp = $spec_value_obj->field(true)->where(array('sid' => array('in', $specids), 'cartid' => $cartid))->order('id ASC')->select();
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
                $return[$index]['min_num'] = floatval($prices[2]);
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
}