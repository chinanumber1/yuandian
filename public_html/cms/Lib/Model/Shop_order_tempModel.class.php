<?php

class Shop_order_tempModel extends Model
{
    public function getGoods($cartid, $store_id, $index)
    {
        $arrIndex = is_numeric($index) ? 'index_' . $index : $index;
        $where = array('cartid' => $cartid, 'store_id' => $store_id);
        $orderTemp = $this->field('info')->where($where)->find();
        $return = array();
        if ($orderTemp) {
            $goods = json_decode($orderTemp['info'], true);
            foreach ($goods as $key => $val) {
                if ($arrIndex != $key) {
                    $list = $val['data'];
                    foreach ($list as $row) {
                        if (isset($return[$row['productId']])) {
                            $return[$row['productId']]['count'] += $row['count'];
                        } else {
                            $return[$row['productId']] = $row;
                        }
                    }
                }
            }
        }
        return $return;
    }
}
?>