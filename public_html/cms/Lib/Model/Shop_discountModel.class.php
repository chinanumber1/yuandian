<?php

class Shop_discountModel extends Model
{

    public function get_discount_byids($store_ids = array(), $is_all = true)
    {
        if ($is_all) {
            $sql = "SELECT `id`, `full_money`, `reduce_money`, `type`, `source`, `status`, `create_time`, `store_id`, `mer_id` FROM " . C('DB_PREFIX') . "shop_discount WHERE (`source`=0";
            if ($store_ids) {
                $str = implode(',', $store_ids);
                $sql .= " OR (`source`=1 AND `store_id` IN ({$str}))) AND `status`=1";
            } else {
                $sql .= " ) AND `status`=1";
            }
            $result = $this->query($sql);
        } elseif ($store_ids) {
            $result = $this->field(true)->where(array('store_id' => array('in', $store_ids, 'status' => 1)))->select();
        } else {
            return null;
        }
        $list = array();
        foreach ($result as $row) {
            if ($row['source'] == 0) {
                $list[0][] = $row;
            } elseif ($row['store_id'] && $row['source'] == 1) {
                $list[$row['store_id']][] = $row;
            }
        }
        return $list;
    }
    
    public function getDiscounts($merId, $store_id = 0)
    {
        //use_type 0:全部商家，1：指定商家
        //use_area 0:全区域，1：指定区域
        //status:0关闭，1：正常
        //source:0平台，1：店铺，2：商家
        $data = array();
        
        //店铺优惠
//         $result = $this->field(true)->where(array('store_id' => array('in', $store_ids, 'status' => 1)))->select();
//         foreach ($result as $row) {
//             $data[$row['store_id']][] = $row;
//         }
        //1:全区域，全商家
//         $where = array('source' => 0, 'use_area' => 0, 'use_type' => 0);
        
        $sql = "SELECT * FROM " . C('DB_PREFIX') . "shop_discount WHERE (`source`=0 AND `use_area`=0 AND `use_type`=0 AND `is_area`=0";
        if ($store_id) {
            $sql .= " OR (`source`=1 AND `store_id`={$store_id})) AND `status`=1";
        } else {
            $sql .= " ) AND `status`=1";
        }
        $result = $this->query($sql);
//         $result = $this->field(true)->where($where)->select();
        foreach ($result as $row) {
            $data[$row['store_id']][] = $row;
        }
        
        //2.全区域，指定商家
        //4.值定区域，指定商家
        $sql = "SELECT d.* FROM " . C('DB_PREFIX') . "shop_discount AS d INNER JOIN " . C('DB_PREFIX') . "shop_discount_merchant AS m ON d.id=m.did WHERE `status`=1 AND use_type=1 AND m.mer_id=" . $merId;
        $result = $this->query($sql);
        foreach ($result as $row) {
            $data[$row['store_id']][] = $row;
        }
        
        //3.指定区域，全部商家
        $merchant = D('Merchant')->field('province_id, city_id, area_id')->where(array('mer_id' => $merId))->find();
        $sql = "SELECT * FROM " . C('DB_PREFIX') . "shop_discount AS d INNER JOIN " . C('DB_PREFIX') . "shop_discount_area AS a ON a.did=d.id WHERE `status`=1 AND use_type=0 AND a.aid IN (" . $merchant['province_id'] . ',' . $merchant['city_id'] . ',' . $merchant['area_id'] . ')' ;
        $result = $this->query($sql);
        foreach ($result as $row) {
            $data[$row['store_id']][] = $row;
        }
        
        return $data;
    }
}
?>