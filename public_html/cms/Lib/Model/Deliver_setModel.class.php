<?php
class Deliver_setModel extends Model
{
    public function getDeliverInfo($store, $price = 0)
    {
        $delivery_fee = 0;
        $per_km_price = 0;
        $basic_distance = 0;
        
        $delivery_fee2 = 0;
        $per_km_price2 = 0;
        $basic_distance2 = 0;
        
        $delivery_fee3 = 0;
        $per_km_price3 = 0;
        $basic_distance3 = 0;
        
        if ($store['deliver_type'] == 0 || $store['deliver_type'] == 3) {
            if ($store['s_is_open_own']) {//开启了店铺的独立配送费的设置
                //配送时段一的配置
                if ($store['s_free_type'] == 0) {//免配送费
                    
                } elseif ($store['s_free_type'] == 1) {//不免
                    $delivery_fee = $store['s_delivery_fee'];
                    $per_km_price = $store['s_per_km_price'];
                    $basic_distance = $store['s_basic_distance'];
                } elseif ($store['s_free_type'] == 2) {//满免
                    if ($price < $store['s_full_money']) {
                        $delivery_fee = $store['s_delivery_fee'];
                        $per_km_price = $store['s_per_km_price'];
                        $basic_distance = $store['s_basic_distance'];
                    }
                }
                //配送时段二的配送
                if ($store['s_free_type2'] == 0) {//免配送费
                    
                } elseif ($store['s_free_type2'] == 1) {//不免
                    $delivery_fee2 = $store['s_delivery_fee2'];
                    $per_km_price2 = $store['s_per_km_price2'];
                    $basic_distance2 = $store['s_basic_distance2'];
                } elseif ($store['s_free_type2'] == 2) {//满免
                    if ($price < $store['s_full_money2']) {
                        $delivery_fee2 = $store['s_delivery_fee2'];
                        $per_km_price2 = $store['s_per_km_price2'];
                        $basic_distance2 = $store['s_basic_distance2'];
                    }
                }
                //配送时段三的配送
				if($store['s_free_type3'] == 4) {//跳过配置
                    $delivery_fee3 = C('config.delivery_fee3');
                    $per_km_price3 = C('config.per_km_price3');
                    $basic_distance3 = C('config.basic_distance3');
                } elseif ($store['s_free_type3'] == 0) {//免配送费
                    
                } elseif ($store['s_free_type3'] == 1) {//不免
                    $delivery_fee3 = $store['s_delivery_fee3'];
                    $per_km_price3 = $store['s_per_km_price3'];
                    $basic_distance3 = $store['s_basic_distance3'];
                } elseif ($store['s_free_type3'] == 2) {//满免
                    if ($price < $store['s_full_money3']) {
                        $delivery_fee3 = $store['s_delivery_fee3'];
                        $per_km_price3 = $store['s_per_km_price3'];
                        $basic_distance3 = $store['s_basic_distance3'];
                    }
                }
                $set = $this->field(true)->where(array('area_id' => $store['area_id'], 'status' => 1))->find();
                if (empty($set)) {
                    $set = $this->field(true)->where(array('area_id' => $store['city_id'], 'status' => 1))->find();
                    if (empty($set)) {
                        $set = $this->field(true)->where(array('area_id' => $store['province_id'], 'status' => 1))->find();
                    }
                }
                if ($set) {
                    $delivertime_start = $set['delivertime_start'];
                    $delivertime_stop = $set['delivertime_stop'];
                    $delivertime_start2 = $set['delivertime_start2'];
                    $delivertime_stop2 = $set['delivertime_stop2'];
                    $delivertime_start3 = $set['delivertime_start3'];
                    $delivertime_stop3 = $set['delivertime_stop3'];
                }else{
                    $delivery_times = explode('-', C('config.delivery_time'));
                    $delivertime_start = $delivery_times[0] . ':00';
                    $delivertime_stop = $delivery_times[1] . ':00';
                    $delivery_times2 = explode('-', C('config.delivery_time2'));
                    $delivertime_start2 = $delivery_times2[0] . ':00';
                    $delivertime_stop2 = $delivery_times2[1] . ':00';

                    $delivery_times3 = explode('-', C('config.delivery_time3'));
                    $delivertime_start3 = $delivery_times3[0] . ':00';
                    $delivertime_stop3 = $delivery_times3[1] . ':00';
                }

            } else {
                $set = $this->field(true)->where(array('area_id' => $store['area_id'], 'status' => 1))->find();
                if (empty($set)) {
                    $set = $this->field(true)->where(array('area_id' => $store['city_id'], 'status' => 1))->find();
                    if (empty($set)) {
                        $set = $this->field(true)->where(array('area_id' => $store['province_id'], 'status' => 1))->find();
                    }
                }
                if ($set) {
                    if ($set['freetype'] == 0) {//免配送费
                        
                    } elseif ($set['freetype'] == 1) {//不免
                        $delivery_fee = $set['base_fee'];
                        $per_km_price = $set['per_km_price'];
                        $basic_distance = $set['base_distance'];
                    } elseif ($set['freetype'] == 2) {//满免
                        if ($price < $set['full_money']) {
                            $delivery_fee = $set['base_fee'];
                            $per_km_price = $set['per_km_price'];
                            $basic_distance = $set['base_distance'];
                        }
                    }
                    //配送时段二的配送
                    if ($set['freetype2'] == 0) {//免配送费
                        
                    } elseif ($set['freetype2'] == 1) {//不免
                        $delivery_fee2 = $set['base_fee2'];
                        $per_km_price2 = $set['per_km_price2'];
                        $basic_distance2 = $set['base_distance2'];
                    } elseif ($set['freetype2'] == 2) {//满免
                        if ($price < $set['full_money2']) {
                            $delivery_fee2 = $set['base_fee2'];
                            $per_km_price2 = $set['per_km_price2'];
                            $basic_distance2 = $set['base_distance2'];
                        }
                    }
                    if ($set['freetype3'] == 0) {//免配送费
                        
                    } elseif ($set['freetype3'] == 1) {//不免
                        $delivery_fee3 = $set['base_fee3'];
                        $per_km_price3 = $set['per_km_price3'];
                        $basic_distance3 = $set['base_distance3'];
                    } elseif ($set['freetype3'] == 2) {//满免
                        if ($price < $set['full_money3']) {
                            $delivery_fee3 = $set['base_fee3'];
                            $per_km_price3 = $set['per_km_price3'];
                            $basic_distance3 = $set['base_distance3'];
                        }
                    }
                    
                    $delivertime_start = $set['delivertime_start'];
                    $delivertime_stop = $set['delivertime_stop'];
                    $delivertime_start2 = $set['delivertime_start2'];
                    $delivertime_stop2 = $set['delivertime_stop2'];
                    $delivertime_start3 = $set['delivertime_start3'];
                    $delivertime_stop3 = $set['delivertime_stop3'];
                    
                    
                } else {
                    $delivery_fee = C('config.delivery_fee');
                    $per_km_price = C('config.per_km_price');
                    $basic_distance = C('config.basic_distance');
                    
                    $delivery_fee2 = C('config.delivery_fee2');
                    $per_km_price2 = C('config.per_km_price2');
                    $basic_distance2 = C('config.basic_distance2');
                    
                    $delivery_fee3 = C('config.delivery_fee3');
                    $per_km_price3 = C('config.per_km_price3');
                    $basic_distance3 = C('config.basic_distance3');
                    
                    
                    $delivery_times = explode('-', C('config.delivery_time'));
                    $delivertime_start = $delivery_times[0] . ':00';
                    $delivertime_stop = $delivery_times[1] . ':00';
                    $delivery_times2 = explode('-', C('config.delivery_time2'));
                    $delivertime_start2 = $delivery_times2[0] . ':00';
                    $delivertime_stop2 = $delivery_times2[1] . ':00';
                    $delivery_times3 = explode('-', C('config.delivery_time3'));
                    $delivertime_start3 = $delivery_times3[0] . ':00';
                    $delivertime_stop3 = $delivery_times3[1] . ':00';
                }
            }
        } else {//商家配送|商家或自提|快递配送
            if ($store['reach_delivery_fee_type'] == 0) {
                
            } elseif ($store['reach_delivery_fee_type'] == 1) {
                $delivery_fee = $store['delivery_fee'];
                $per_km_price = $store['per_km_price'];
                $basic_distance = $store['basic_distance'];
                
                $delivery_fee2 = $store['delivery_fee2'];
                $per_km_price2 = $store['per_km_price2'];
                $basic_distance2 = $store['basic_distance2'];
            } elseif ($store['reach_delivery_fee_type'] == 2)  {
                if ($price < $store['no_delivery_fee_value']) {
                    $delivery_fee = $store['delivery_fee'];
                    $per_km_price = $store['per_km_price'];
                    $basic_distance = $store['basic_distance'];
                    
                    $delivery_fee2 = $store['delivery_fee2'];
                    $per_km_price2 = $store['per_km_price2'];
                    $basic_distance2 = $store['basic_distance2'];
                }
            }
            if ($store['reach_delivery_fee_type2'] == 0) {
                
            } elseif ($store['reach_delivery_fee_type2'] == 1) {
                $delivery_fee2 = $store['delivery_fee2'];
                $per_km_price2 = $store['per_km_price2'];
                $basic_distance2 = $store['basic_distance2'];
            } elseif ($store['reach_delivery_fee_type2'] == 2)  {
                if ($price < $store['no_delivery_fee_value2']) {
                    $delivery_fee2 = $store['delivery_fee2'];
                    $per_km_price2 = $store['per_km_price2'];
                    $basic_distance2 = $store['basic_distance2'];
                }
            }
            if ($store['reach_delivery_fee_type3'] == 0) {
                
            } elseif ($store['reach_delivery_fee_type3'] == 1) {
                $delivery_fee3 = $store['delivery_fee3'];
                $per_km_price3 = $store['per_km_price3'];
                $basic_distance3 = $store['basic_distance3'];
            } elseif ($store['reach_delivery_fee_type3'] == 2)  {
                if ($price < $store['no_delivery_fee_value3']) {
                    $delivery_fee3 = $store['delivery_fee3'];
                    $per_km_price3 = $store['per_km_price3'];
                    $basic_distance3 = $store['basic_distance3'];
                }
            }
            
            $delivertime_start = $store['delivertime_start'];
            $delivertime_stop = $store['delivertime_stop'];
            $delivertime_start2 = $store['delivertime_start2'];
            $delivertime_stop2 = $store['delivertime_stop2'];
            $delivertime_start3 = $store['delivertime_start3'];
            $delivertime_stop3 = $store['delivertime_stop3'];
        }
        
        return array('delivery_fee' => $delivery_fee,
                    'basic_distance' => $basic_distance,
                    'per_km_price' => $per_km_price,
                    'delivertime_start' => $delivertime_start,
                    'delivertime_stop' => $delivertime_stop,
                    'delivery_fee2' => $delivery_fee2,
                    'basic_distance2' => $basic_distance2,
                    'per_km_price2' => $per_km_price2,
                    'delivertime_start2' => $delivertime_start2,
                    'delivertime_stop2' => $delivertime_stop2,
                    'delivery_fee3' => $delivery_fee3,
                    'basic_distance3' => $basic_distance3,
                    'per_km_price3' => $per_km_price3,
                    'delivertime_start3' => $delivertime_start3,
                    'delivertime_stop3' => $delivertime_stop3,
        );
    }
    
    
    public function getDeliverTime($time, $start_time, $stop_time, $select)
    {
        $stime = strtotime(date('Y-m-d ' . $start_time));
        $etime = strtotime(date('Y-m-d ' . $stop_time));
        $next_stime = 0;
        $next_etime = 0;
        if ($etime < $stime) {
            $etime = strtotime(date('Y-m-d 23:59:59'));
            $next_stime = strtotime(date('Y-m-d'));
            $next_etime = strtotime(date('Y-m-d ' . $stop_time));
        }
        
        if ($stime <= $time && $time <= $etime) {
            return $select;
        }
        if ($next_stime <= $time && $time <= $next_etime) {
            return $select;
        }
        return 0;
    }
}