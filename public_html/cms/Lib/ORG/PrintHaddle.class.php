<?php

class PrintHaddle
{

    public $orderPrint = null;
    
    public $payarr = array(
                'alipay' => '支付宝',
                'weixin' => '微信支付',
                'tenpay' => '财付通[wap手机]',
                'tenpaycomputer' => '财付通[即时到帐]',
                'yeepay' => '易宝支付',
                'allinpay' => '通联支付',
                'daofu' => '货到付款',
                'dianfu' => '到店付款',
                'chinabank' => '网银在线',
                'offline' => '线下支付'
            );
    public function __construct()
    {
        $this->orderPrint = new orderPrint(C('config.print_server_key'), C('config.print_server_topdomain'));
    }
    
    public function printit($order_id, $table = 'shop_order', $status = 0, $isPrint = 0)
    {
        return $this->$table($order_id, $status, $isPrint);
    }
    private function shop_order_old($order_id, $status = 0)
    {
        $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id));
        if (empty($order)) return false;
        
        $prints = $this->getPrints($order['store_id']);
        if (empty($prints)) return false;
        
        $this->shop_order_every($order, $prints, $status);
        
        $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
        $store_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
        $freight_alias = $store_shop['freight_alias'] ? $store_shop['freight_alias'] : '配送费用';
        $pack_alias = $store_shop['pack_alias'] ? $store_shop['pack_alias'] : '打包费用';
        
        foreach ($prints as $usePrinter) {
            if ($usePrinter['is_main'] == 0) {
                continue;
            }
            
            if ($status != -1) {
                $statusArr = explode(',', $usePrinter['paid']);
                if (!in_array($status, $statusArr)) {
                    continue;
                }
            }
            
            $formatData = $this->getThreeData($usePrinter);
            $width = $formatData['width'];
            $firstSpace = $formatData['one'];
            $secondSpace = $formatData['two'];
            $thirdSpace = $formatData['three'];
            $spaceWidth= $formatData['spaceWidth'];
            
            $format_str = '';
            if (isset($order['fetch_number']) && $order['fetch_number']) {
                $format_str .= chr(10) . '取单编号:' . $order['fetch_number'];
            }
            $format_str .= chr(10) . '订单编号:' . $order['real_orderid'];
            
            $tempList = array();
            foreach ($order['info'] as $val) {
                $index = isset($val['packname']) && $val['packname'] ? $val['packname'] : 0;
                if (isset($tempList[$index])) {
                    $tempList[$index]['list'][] = $val;
                } else {
                    $tempList[$index] = array('name' => $val['packname'], 'list' => array($val));
                }
            }
            
            foreach ($tempList as $list) {
                $format_str .= chr(10) . str_repeat('*', $width * 2);
                if ($list['name']) {
                    $format_str .= chr(10) . $list['name'];
                }
                $format_str .= chr(10) . str_repeat(' ', $firstSpace) . '品名' . str_repeat(' ', $secondSpace) . '数量' . str_repeat(' ', $thirdSpace) . '单价';//16
                
                foreach ($list['list'] as $val) {
                    if ($val['spec']) {
                        $val['name'] .= '(' . $val['spec'] . ')';
                    }
                    if ($val['number']) {
                        $format_str .= chr(10) . $val['name'];
                        $len = self::dstrlen($val['number']);
                        $len = $len % ($width * 2);
                        if ($len > $spaceWidth) {
                            $space = $spaceWidth + 1;
                        } else {
                            $space = $spaceWidth + 1 - $len;
                        }
                        $format_str .= chr(10) . self::cutstr($val['number'], $spaceWidth, $width * 2) . self::format($val['num'], $space) . self::format('￥' . floatval($val['price']) . '/' . $val['unit'], $thirdSpace);
                    } else {
                        $len = self::dstrlen($val['name']);
                        $len = $len % ($width * 2);
                        if ($len > $spaceWidth) {
                            $space = $spaceWidth + 1;
                        } else {
                            $space = $spaceWidth + 1 - $len;
                        }
                        $format_str .= chr(10) . self::cutstr($val['name'], $spaceWidth, $width * 2) . self::format($val['num'], $space) . self::format('￥' . floatval($val['price']) . '/' . $val['unit'], $thirdSpace);
                    }
                }
                $format_str .= chr(10) . str_repeat('*', $width * 2);
            }
            
            $format_str .= chr(10) . '客户姓名：' . $order['username'];
            $format_str .= chr(10) . '客户手机：' . $order['userphone'];
            if ($order['desc']) {
                $format_str .= chr(10) . '客户留言:' . $order['desc'];
            }
            if ($order['order_from'] != 6) {
                if ($order['is_pick_in_store'] == 2) {
                    $format_str .= chr(10) . '自提地址：' . $order['address'];
                } else {
                    $format_str .= chr(10) . '客户地址：' . $order['address'];
                }
                $format_str .= chr(10) . '配送方式：' . $order['deliver_str'];
                $format_str .= chr(10) . '配送状态：' . $order['deliver_status_str'];
            }
            
            $format_str .= chr(10) . '下单时间：' . date('Y-m-d H:i', $order['create_time']);
            if ($order['pay_time']) {
                $format_str .= chr(10) . '支付时间：' . date('Y-m-d H:i', $order['pay_time']);
            }
            if ($order['expect_use_time']) {
                $format_str .= chr(10) . '送达时间：' . date('Y-m-d H:i', $order['expect_use_time']);
            }
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            $format_str .= chr(10) . '商品总价：￥' . floatval($order['goods_price']);
            $format_str .= chr(10) . $freight_alias . '：￥' . floatval($order['freight_charge']);
            $format_str .= chr(10) . $pack_alias . '：￥' . floatval($order['packing_charge']);
            $format_str .= chr(10) . '订单总价：￥' . floatval($order['total_price']);
            if ($order['merchant_reduce'] > 0) {
                $format_str .= chr(10) . '店铺优惠：￥' . floatval($order['merchant_reduce']);
            }
            if ($order['balance_reduce'] > 0) {
                $format_str .= chr(10) . '平台优惠：￥' . floatval($order['balance_reduce']);
            }
            $format_str .= chr(10) . '实付金额：￥' . floatval($order['price']);
            if ($order['score_used_count'] > 0) {
                $format_str .= chr(10) . '使用' . C('config.score_name') . '：' . $order['score_used_count'];
                $format_str .= chr(10) . '' . C('config.score_name') . '抵现：￥' . floatval($order['score_deducte']);
            }
            if ($order['merchant_balance'] > 0) {
                $format_str .= chr(10) . '商家余额：￥' . floatval($order['merchant_balance']);
            }
            if ($order['balance_pay'] > 0) {
                $format_str .= chr(10) . '平台余额：￥' . floatval($order['balance_pay']);
            }
            if ($order['payment_money'] > 0) {
                $format_str .= chr(10) . '在线支付：￥' . floatval($order['payment_money']);
            }
            if ($order['card_id'] > 0) {
                $format_str .= chr(10) . '店铺优惠券金额：￥' . floatval($order['card_price']);
            }
            if ($order['coupon_id'] > 0) {
                $format_str .= chr(10) . '平台优惠券金额：￥' . floatval($order['coupon_price']);
            }
            
            if ($order['pay_type'] == 'offline' && empty($order['third_id'])) {
                $format_str .= chr(10) . '线下需支付：￥' . round($order['price'] - round($order['card_price'] + $order['merchant_balance'] + $order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);
            }
            $format_str .= chr(10) . '支付状态：' . $order['pay_status_print'];
            $format_str .= chr(10) . '支付方式：' . $order['pay_type_str'];
            $format_str .= chr(10) . '订单状态：' . $order['status_str'];
            if ($order['cue_field']) {
                foreach ($order['cue_field'] as $cue) {
                    $format_str .= chr(10) . $cue['title'] . ':' . $cue['txt'];
                }
            }
            $format_str .= chr(10) . str_repeat('※', $width);
            $format_str .= chr(10) . '店铺名称：' . $store['name'];
            $format_str .= chr(10) . '店铺电话：' . $store['phone'];
            $format_str .= chr(10) . '店铺地址：' . $store['adress'];
            $format_str .= chr(10) . '打印时间：' . date('Y-m-d H:i:s');
            $format_str .= chr(10) . '谢谢惠顾，欢迎再次光临！';
            $this->orderPrint->newPrint($usePrinter, $format_str);
        }
        return false;
    }
    
    
    private function foodshop_order($order_id, $status = 0, $isPrint = 0)
    {
        $order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id), 3);
        
        if (empty($order)) return false;
        
        $prints = $this->getPrints($order['store_id']);
        if (empty($prints)) return false;
        
        $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
        if ($isPrint == 0) {
            $this->foodshop_order_every($order, $prints, $status, 1);
        }
        
        foreach ($prints as $usePrinter) {
            if ($usePrinter['is_main'] == 0) {
                continue;
            }
            if ($status != -1) {
                $statusArr = explode(',', $usePrinter['paid']);
                if (!in_array($status, $statusArr)) {
                    continue;
                }
            }
            $formatData = $this->getThreeData($usePrinter);
            $width = $formatData['width'];
            $firstSpace = $formatData['one'];
            $secondSpace = $formatData['two'];
            $thirdSpace = $formatData['three'];
            $spaceWidth = $formatData['spaceWidth'];
            
            $format_str = '';
            $format_str .= chr(10) . '订单编号:' . $order['real_orderid'];
            $format_str .= chr(10) . '客户姓名：' . $order['name'];
            $format_str .= chr(10) . '客户手机：' . $order['phone'];
            
            if ($order['note']) {
                $format_str .= chr(10) . '客户留言:' . $order['note'];
            }
            $format_str .= chr(10) . '桌台类型：' . $order['table_type_name'];
            $format_str .= chr(10) . '桌台名称：' . $order['table_name'];
            $format_str .= chr(10) . '预定金额：' . floatval($order['book_price']);
            
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            $format_str .= chr(10) . str_repeat(' ', $firstSpace) . '品名' . str_repeat(' ', $secondSpace) . '数量' . str_repeat(' ', $thirdSpace) . '单价';;
            foreach ($order['info'] as $val) {
                if ($val['spec']) {
                    $val['name'] .= '(' . $val['spec'] . ')';
                }
                if ($val['note']) {
                    $val['name'] .= '(备注：' . $val['note'] . ')';
                }
                $len = self::dstrlen($val['name']);
                $len = $len % ($width * 2);
                if ($len > $spaceWidth) {
                    $space = $spaceWidth + 1;
                } else {
                    $space = $spaceWidth + 1 - $len;
                }
                $format_str .= chr(10) . self::cutstr($val['name'], $spaceWidth, $width * 2) . self::format($val['num'], $space) . self::format('￥' . floatval($val['price']) . '/' . $val['unit'], $thirdSpace);
            }
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            
            if ($order['book_time_show']) {
                $format_str .= chr(10) . '预定时间：' . $order['book_time_show'];
            }
            
            if (isset($order['book_pay_type'])) {
                $format_str .= chr(10) . '预定支付方式：' . $order['book_pay_type'];
            }
            if (isset($order['book_pay_time']) && $order['book_pay_time']) {
                $format_str .= chr(10) . '预定支付时间：' . date('Y-m-d H:i', $order['book_pay_time']);
            }
            
            if (isset($order['pay_type'])) {
                $format_str .= chr(10) . '买单支付方式：' . $order['pay_type'];
            }
            if (isset($order['pay_time']) && $order['pay_time']) {
                $format_str .= chr(10) . '买单支付时间：' . date('Y-m-d H:i', $order['pay_time']);
            }
            
            if ($order['status'] > 1) {
                $format_str .= chr(10) . '订单总价：' . floatval($order['total_price']);
                if($order['order_from']==2||$order['from_plat']==2){
                    $store_order = M('Store_order')->where(array('business_type'=>'foodshop','business_id'=>$order['order_id'],'paid'=>1))->find();
                    $format_str .= chr(10) . '优惠金额：' . floatval($store_order['discount_price']);
                    $format_str .= chr(10) . '实付金额：' . floatval($store_order['price']);
                }else{
                    $format_str .= chr(10) . '实付金额：' . floatval($order['price']);
                }
            }
            
            $format_str .= chr(10) . str_repeat('※', $width);
            $format_str .= chr(10) . '店铺名称：' . $store['name'];
            $format_str .= chr(10) . '店铺电话：' . $store['phone'];
            $format_str .= chr(10) . '店铺地址：' . $store['adress'];
            $format_str .= chr(10) . '打印时间：' . date('Y-m-d H:i:s');
            $format_str .= chr(10) . '谢谢惠顾，欢迎再次光临！';
            $this->orderPrint->newPrint($usePrinter, $format_str);
        }
    }

    private function meal_order($order_id, $status = 0)
    {
        $order = D('Meal_order')->field(true)->where(array('order_id' => $order_id))->find();
        if (empty($order)) return false;
        
        $prints = $this->getPrints($order['store_id']);
        if (empty($prints)) return false;
        $this->meal_order_every($order, $prints);
        $print_format = C('config.print_format');
        
        $print_format = preg_replace('/\{user_name\}/', $order['name'], $print_format);
        $print_format = preg_replace('/\{user_phone\}/', $order['phone'], $print_format);
        $print_format = preg_replace('/\{user_address\}/', $order['address'], $print_format);
        $print_format = preg_replace('/\{user_message\}/', $order['note'], $print_format);
        $print_format = preg_replace('/\{user_num\}/', $order['num'], $print_format);
        $print_format = preg_replace('/\{buy_time\}/', date("Y-m-d H:i:s", $order['dateline']), $print_format);
        $print_format = preg_replace('/\{pay_time\}/', date("Y-m-d H:i:s", $order['pay_time']), $print_format);
        $print_format = preg_replace('/\{use_time\}/', date("Y-m-d H:i:s", $order['use_time']), $print_format);
        if ($order['arrive_time']) {
            $print_format = preg_replace('/\{arrive_time\}/', date("Y-m-d H:i:s", $order['arrive_time']), $print_format);
        } else {
            $print_format = preg_replace('/\{arrive_time\}/', '尽快送达', $print_format);
        }
        
        $goods_list = '';
        if ($order['info']) {
            $list = unserialize($order['info']);
            $meal_ids = array();
            foreach ($list as $row) {
                if (! in_array($row['id'], $meal_ids)) {
                    $meal_ids[] = $row['id'];
                }
            }
            if ($meal_ids) {
                $meals = D('Meal')->field(true)->where(array('meal_id' => array('in', $meal_ids)))->select();
                $mid_pid = array();
                foreach ($meals as $m) {
                    $mid_pid[$m['meal_id']] = $m;
                }
                foreach ($list as $k => $row) {
                    $goods_list .= chr(10) . $row['name'] . ": ￥" . $row['price'] . " * " . $row['num'] . "({$mid_pid[$row['id']]['unit']})";
                    $row['omark'] && $goods_list .= chr(10) . "菜品备注: " . $row['omark'];
                }
            }
        }
        $print_format = preg_replace('/\{goods_list\}/', $goods_list, $print_format);
        $print_format = preg_replace('/\{goods_count\}/', $order['total'], $print_format);
        $print_format = preg_replace('/\{goods_price\}/', $order['total_price'], $print_format);
        $print_format = preg_replace('/\{minus_price\}/', $order['minus_price'], $print_format);
        $print_format = preg_replace('/\{true_price\}/', $order['total_price'] - $order['minus_price'], $print_format);
        $print_format = preg_replace('/\{orderid\}/', $order['order_id'], $print_format);
        if (empty($order['paid'])) {
            $pay_status = '未支付';
        } else {
            if (empty($order['status'])) {
                $pay_status = '未消费';
            } elseif ($order['status'] == 1) {
                $pay_status = '已消费';
            } elseif ($order['status'] == 2) {
                $pay_status = '已完成';
            } elseif ($order['status'] == 3) {
                $pay_status = '已退款';
            }
        }
        $print_format = preg_replace('/\{pay_status\}/', $pay_status, $print_format);
        $pay_type = isset($this->payarr[$order['pay_type']]) ? $this->payarr[$order['pay_type']] : '';
        if (empty($pay_type)) {
            if ($order['price'] == $order['balance_pay']) {
                $pay_type = '平台余额支付';
            } elseif ($order['price'] == $order['merchant_balance']) {
                $pay_type = '商家会员卡余额支付';
            }
        }
        $print_format = preg_replace('/\{pay_type\}/', $pay_type, $print_format);
        $table_name = '未选择';
        if ($order['meal_type'] == 1) {
            $meal_type = '外送';
        } elseif ($order['meal_type'] == 0) {
            $meal_type = '预定';
            if ($table = D('Merchant_store_table')->field(true)->where(array('pigcms_id' => $order['tableid']))->find()) {
                $table_name = $table['name'];
            }
        } elseif ($order['meal_type'] == 2) {
            $meal_type = '现场pad点餐';
            if ($table = D('Merchant_store_table')->field(true)->where(array('pigcms_id' => $order['tableid']))->find()) {
                $table_name = $table['name'];
            }
        } elseif ($order['meal_type'] == 3) {
            $meal_type = '在线购买';
            if ($table = D('Merchant_store_table')->field(true)->where(array('pigcms_id' => $order['tableid']))->find()) {
                $table_name = $table['name'];
            }
        }
        $print_format = preg_replace('/\{delivery_fee\}/', $order['delivery_fee'], $print_format);
        $print_format = preg_replace('/\{meal_type\}/', $meal_type, $print_format);
        $print_format = preg_replace('/\{table_name\}/', $table_name, $print_format);
        
        $print_format = preg_replace('/\{store_name\}/', $store['name'], $print_format);
        $print_format = preg_replace('/\{store_phone\}/', $store['phone'], $print_format);
        $print_format = preg_replace('/\{store_address\}/', $store['adress'], $print_format);
        
        $print_format = preg_replace('/\{print_time\}/', date('Y-m-d H:i:s'), $print_format);
        
        foreach ($prints as $usePrinter) {
            if ($usePrinter['is_main'] == 0) {
                continue;
            }
            if ($status != -1) {
                $statusArr = explode(',', $usePrinter['paid']);
                if (!in_array($status, $statusArr)) {
                    continue;
                }
            }
            $this->orderPrint->newPrint($usePrinter, $print_format);
        }
        return false;
    }
    
    private function group_order($order_id, $status = 0)
    {
        $order = D('Group_order')->field(true)->where(array('order_id' => $order_id))->find();
        if (empty($order)) return false;
        
        $prints = $this->getPrints($order['store_id']);
        if (empty($prints)) return false;
        
        $print_format = C('config.group_print_format');
        $user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
        $nickname = isset($user['nickname']) ? $user['nickname'] : '';
        
        $print_format = preg_replace('/\{user_name\}/', $nickname, $print_format);
        $print_format = preg_replace('/\{user_phone\}/', $order['phone'], $print_format);
        $print_format = preg_replace('/\{user_address\}/', $order['adress'], $print_format);
        $print_format = preg_replace('/\{orderid\}/', $order['real_orderid'], $print_format);
        
        $print_format = preg_replace('/\{goods_name\}/', $order['order_name'], $print_format);
        $print_format = preg_replace('/\{goods_count\}/', $order['num'], $print_format);
        $print_format = preg_replace('/\{goods_price\}/', $order['total_money'], $print_format);
        $print_format = preg_replace('/\{minus_price\}/', $order['wx_cheap'], $print_format);
        $print_format = preg_replace('/\{true_price\}/', $order['total_money'] - $order['wx_cheap'], $print_format);
        
        $print_format = preg_replace('/\{buy_time\}/', date("Y-m-d H:i:s", $order['add_time']), $print_format);
        $print_format = preg_replace('/\{pay_time\}/', date("Y-m-d H:i:s", $order['pay_time']), $print_format);
        $print_format = preg_replace('/\{use_time\}/', date("Y-m-d H:i:s", $order['use_time']), $print_format);
        
        $print_format = preg_replace('/\{store_name\}/', $store['name'], $print_format);
        $print_format = preg_replace('/\{store_phone\}/', $store['phone'], $print_format);
        $print_format = preg_replace('/\{store_address\}/', $store['adress'], $print_format);
        
        if (empty($order['paid'])) {
            $pay_status = '未支付';
        } else {
            if (empty($order['status'])) {
                $pay_status = '未消费';
            } elseif ($order['status'] == 1) {
                $pay_status = '已消费';
            } elseif ($order['status'] == 2) {
                $pay_status = '已完成';
            } elseif ($order['status'] == 3) {
                $pay_status = '已退款';
            }
        }
        
        $pay_type = isset($this->payarr[$order['pay_type']]) ? $this->payarr[$order['pay_type']] : '';
        if (empty($pay_type) && $order['paid']) {
            $pay_type = '余额支付';
        }
        $print_format = preg_replace('/\{pay_status\}/', $pay_status, $print_format);
        $print_format = preg_replace('/\{pay_type\}/', $pay_type, $print_format);
        $print_format = preg_replace('/\{print_time\}/', date('Y-m-d H:i:s'), $print_format);
        
        
        foreach ($prints as $usePrinter) {
            if ($usePrinter['is_main'] == 0) {
                continue;
            }
            if ($status != -1) {
                $statusArr = explode(',', $usePrinter['paid']);
                if (!in_array($status, $statusArr)) {
                    continue;
                }
            }
            $this->orderPrint->newPrint($usePrinter, $print_format);
        }
        return false;
    }
    
    private function waimai_order($order_id, $status = 0)
    {
        $order = D('Group_order')->field(true)->where(array('order_id' => $order_id))->find();
        if (empty($order)) return false;
        
        $prints = $this->getPrints($order['store_id']);
        if (empty($prints)) return false;
        
        $store_where['store_id'] = array('in', $order['store_id']);
        $store_info = D('Merchant_store')->field(true)->where($store_where)->select();
        $deliverStore = D("Deliver_store")->field(true)->where($store_where)->find();
        
        $merchant_where['mer_id'] = array('in', $order['mer_id']);
        $merchant_info = D('Merchant')->field(true)->where($merchant_where)->select();
        
        $orderId[$order_id] = $order_id;
        $deliver_where['order_id'] = array('in', $orderId);
        $deliverSupplyInfo = D("Deliver_supply")->field('`order_id`,`start_time`,`end_time`')->where($deliver_where)->select();
        
        $orderObj = new Waimai_orderModel();
        $now_order = $orderObj->formatArray(array($order), $store_info, $merchant_info, $deliverSupplyInfo);
        $now_order = $now_order[0];
        $now_order['deliver_type'] = $deliverStore['type'];
        
        
        foreach ($prints as $usePrinter) {
            if ($usePrinter['is_main'] == 0) {
                continue;
            }
            if ($status != -1) {
                $statusArr = explode(',', $usePrinter['paid']);
                if (!in_array($status, $statusArr)) {
                    continue;
                }
            }
            $formatData = $this->getThreeData($usePrinter);
            $width = $formatData['width'];
            $firstSpace = $formatData['one'];
            $secondSpace = $formatData['two'];
            $thirdSpace = $formatData['three'];
            
            $format_str = '联系人：' . $now_order['nickname'];
            $format_str .= chr(10) . "联系电话：" . $now_order['phone'];
            $format_str .= chr(10) . "送货地址：" . $now_order['address'];
            $format_str .= chr(10) . "支付方式：" . $now_order['pay_type'];
            if ($now_order['paid'] == 1) {
                $format_str .= chr(10) . "支付状态：已支付";
            } else {
                $format_str .= chr(10) . "支付状态：已支付";
            }
            
            $format_str .= chr(10) . "备注：" . $now_order['desc'];
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            foreach ($now_order['goods_list'] as $val) {
                $format_str .= chr(10) . $val['name'] . ": ￥" . $val['price'] . " * " . $val['num'];
            }
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            if ($now_order['tools_price'] > 0) {
                $format_str .= chr(10) . "打包费: ￥" . $now_order['tools_price'];
            }
            
            if ($now_order['send_money'] > 0 && ($now_order['goods_money'] < $now_order['total_money'] || $now_order['total_money'] == 0)) {
                $format_str .= chr(10) . "配送费: ￥" . $now_order['send_money'];
            }
            $format_str .= chr(10) . "总价: ￥" . $now_order['price'];
            $discountInfo = json_decode($now_order['discount_detail'], true);
            if (! empty($discountInfo)) {
                foreach ($discountInfo as $val) {
                    $format_str .= chr(10) . "优惠活动：" . $val['desc'] . "-￥" . $val['discount_money'];
                }
            }
            
            $where['id'] = $now_order['coupon_id'];
            $couponInfo = D("Waimai_user_coupon")->field(true)->where($where)->find();
            $where_coupon['coupon_id'] = $couponInfo['coupon_id'];
            $coupon = D("Waimai_coupon")->field(true)->where($where_coupon)->find();
            $coupon['money'] = $couponInfo['money'];
            $couponInfo = $coupon;
            if (! empty($couponInfo['money'])) {
                $format_str .= chr(10) . "红包：" . $couponInfo['name'] . "-￥" . $couponInfo['money'];
            }
            $format_str .= chr(10) . "实际支付：￥" . $now_order['discount_price'];
            $this->orderPrint->newPrint($usePrinter, $format_str);
        }
        return false;
    }
    
    private function appoint_order($order_id, $status = 0)
    {
        $order = D('Appoint_order')->field(true)->where(array('order_id' => $order_id))->find();
        if (empty($order)) return false;
        
        $prints = $this->getPrints($order['store_id']);
        if (empty($prints)) return false;
        
        $order_info = D('Appoint_order')->get_order_detail_by_id($order['uid'], $order['order_id']);
        
        $user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
        $store_info = D('Merchant_store')->get_store_by_storeId($order_info['store_id']);
        
        foreach ($prints as $usePrinter) {
            if ($usePrinter['is_main'] == 0) {
                continue;
            }
            if ($status != -1) {
                $statusArr = explode(',', $usePrinter['paid']);
                if (!in_array($status, $statusArr)) {
                    continue;
                }
            }
            
            $formatData = $this->getThreeData($usePrinter);
            $width = $formatData['width'];
            $firstSpace = $formatData['one'];
            $secondSpace = $formatData['two'];
            $thirdSpace = $formatData['three'];
            
            $format_str = '';
            if ($order_info['paid'] == 0) {
                $format_str .= chr(10) . '未支付订单';
            } else if ($order_info['paid'] == 1) {
                $format_str .= chr(10) . '已支付订单';
            } elseif ($order_info['paid'] == 2) {
                $format_str .= chr(10) . '已退款订单';
            }
            
            $format_str .= chr(10) . '客户姓名：' . $user['nickname'];
            $format_str .= chr(10) . '客户电话：' . $user['phone'];
            
            if ($order['cue_field']) {
                $cue_field = unserialize($order['cue_field']);
                foreach ($cue_field as $v) {
                    if (isset($v['address'])) {
                        // $format_str .= chr(10) . '客户地址：' .$v['value'];
                        $format_str .= chr(10) . '客户地址：' . $v['address'];
                    }
                }
            }
            
            if ($order_info['content']) {
                $format_str .= chr(10) . '客户留言：' . $order_info['content'];
            }
            $format_str .= chr(10) . '下单时间：' . date('Y-m-d H:i:s', $order['order_time']);
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            if ($order_info['appoint_date'] && $order_info['appoint_date']) {
                $format_str .= chr(10) . '预约时间：' . $order_info['appoint_date'] . ' ' . $order_info['appoint_date'];
            }
            $format_str .= chr(10) . '预约店铺：' . $order_info['appoint_name'];
            $format_str .= chr(10) . '预约类型：' . ($order_info['appoint_type'] == 0 ? '到店' : '上门');
            
            if ($order_info['worker_detail']) {
                $worker_detail = $order_info['worker_detail'];
                $format_str .= chr(10) . '工作人员：' . $worker_detail['name'];
            }
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            
            $format_str .= chr(10) . '订单号：' . $order_info['order_id'];
            $format_str .= chr(10) . '定金：￥' . ($order_info['product_payment_price']>0?$order_info['product_payment_price']:$order_info['payment_money']);
            
            $database_appoint_product = D('Appoint_product');
            $product_info = $database_appoint_product->get_productlist_by_appointId($order_info['appoint_id']);
            $product_info = reset($product_info);
            if ( $order_info['product_detail']) {
                $format_str .= chr(10) . '总价：￥' . $order_info['product_detail']['price'];
            } else {
                $format_str .= chr(10) . '总价：￥' . $order_info['appoint_price'];
            }
            
            $format_str .= chr(10) . '订单状态：' . ($order_info['paid'] == 0 ? '未支付' : ($order_info['paid'] == 1 ? '已支付' : '已退款'));
            $format_str .= chr(10) . '服务状态：' . ($order_info['service_status'] == 0 ? '未服务' : '已服务');
            
            if (! $order_info['pay_type']) {
                $format_str .= chr(10) . '支付方式：到店支付';
            } else {
                $format_str .= chr(10) . '支付方式：' . $order_info['pay_type_txt'];
            }
            $format_str .= chr(10) . str_repeat('※', $width);
            $format_str .= chr(10) . '店铺名称：' . $store_info['name'];
            $format_str .= chr(10) . '店铺电话：' . $store_info['phone'];
            $format_str .= chr(10) . '店铺地址：' . $store_info['area_ip_desc'] . chr(32) . $store_info['adress'];
            $format_str .= chr(10) . '打印时间：' . date('Y-m-d H:i:s');
            $format_str .= chr(10) . '谢谢惠顾，欢迎再次光临！';
            $this->orderPrint->newPrint($usePrinter, $format_str);
        }
        return false;
    }
    
    private function store_order($order_id, $status = 0)
    {
        $order = D('Store_order')->field(true)->where(array('order_id' => $order_id))->find();
        if (empty($order)) return false;

        $prints = $this->getPrints($order['store_id']);
        if (empty($prints)) return false;
        
        $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
        
        foreach ($prints as $usePrinter) {
            if ($usePrinter['is_main'] == 0) {
                continue;
            }
            if ($status != -1) {
                $statusArr = explode(',', $usePrinter['paid']);
                if (!in_array($status, $statusArr)) {
                    continue;
                }
            }
            
            $formatData = $this->getThreeData($usePrinter);
            $width = $formatData['width'];
            $firstSpace = $formatData['one'];
            $secondSpace = $formatData['two'];
            $thirdSpace = $formatData['three'];
            
            $format_str = '';
            $format_str .= chr(10) . '订单编号:' . $order['order_id'];
            $format_str .= chr(10) . '流水号:' . $order['orderid'];
            $format_str .= chr(10) . '订单总价:' . $order['total_price'];
            $format_str .= chr(10) . '优惠金额:' . $order['discount_price'];
            $format_str .= chr(10) . '实付金额:' . $order['price'];
            $format_str .= chr(10) . str_repeat('※', $width);
            $format_str .= chr(10) . '店铺名称：' . $store['name'];
            $format_str .= chr(10) . '店铺电话：' . $store['phone'];
            $format_str .= chr(10) . '店铺地址：' . $store['adress'];
            $format_str .= chr(10) . '打印时间：' . date('Y-m-d H:i:s');
            $this->orderPrint->newPrint($usePrinter, $format_str);
        }
        return $format_str;
        
    }
    
    /**
     * 快店的分单打印
     * @param array $order 订单详情
     * @param array $prints 打印机数组
     * @param int $status  当前状态
     * @param number $printType 打印类型， 0：按菜品归属打印机进行分组打印，1：按商品分类所属打印机进行分组打印
     */
    public function shop_order_every($order, $prints, $status, $printType = 0)
    {
        if ($order['info']) {
            $list = $order['info'];
            if ($printType == 1) {//按照分类打印
                $shopGoodsSortDB = D('Shop_goods_sort');
                $sortGoods = array();
                $fids = array();
                foreach ($list as $row) {
                    $sortIds = $shopGoodsSortDB->getIds($row['sort_id'], $row['store_id']);
                    if ($sortIds) {
                        $fid = array_shift($sortIds);
                        $fids[] = $fid;
                        $sortGoods[$fid][] = $row;
                    }
                }
                if ($fids) {
                    $sortList = $shopGoodsSortDB->field(true)->where(array('sort_id' => array('in', $fids)))->select();
                    $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
                    
                    foreach ($sortList as $sort) {
                        if ($sort['print_id'] && isset($sortGoods[$sort['sort_id']])) {
                            $mainPrint = isset($prints[$sort['print_id']]) ? $prints[$sort['print_id']] : null;
                            if (empty($mainPrint)) {
                                continue;
                            }
                            if ($status != -1) {
                                $statusArr = explode(',', $mainPrint['paid']);
                                if (!in_array($status, $statusArr)) {
                                    continue;
                                }
                            }
                            $formatData = $this->getTwoData($mainPrint);
                            $width = $formatData['width'];
                            $firstSpace = $formatData['one'];
                            $secondSpace = $formatData['two'];
                            
                            $format_str = chr(10) . '商品分类：' . $sort['sort_name'];
                            $format_str .= chr(10) . '店铺名称：' . $store['name'];
                            $format_str .= chr(10) . '店铺电话：' . $store['phone'];
                            $format_str .= chr(10) . '店铺地址：' . $store['adress'];
                            $format_str .= chr(10) . '打印时间：' . date('Y-m-d H:i:s');
                            $format_str .= chr(10) . '订单编号:' . $order['real_orderid'];
                            $format_str .= chr(10) . '客户姓名：' . $order['username'];
                            $format_str .= chr(10) . '客户手机：' . $order['userphone'];
                            if ($order['desc']) {
                                $format_str .= chr(10) . '客户留言:' . $order['desc'];
                            }
                            if ($order['order_from'] != 6) {
                                if ($order['is_pick_in_store'] == 2) {
                                    $format_str .= chr(10) . '自提地址：' . $order['address'];
                                } else {
                                    $format_str .= chr(10) . '客户地址：' . $order['address'];
                                }
                                
                                $format_str .= chr(10) . '配送方式：' . $order['deliver_str'];
                                $format_str .= chr(10) . '配送状态：' . $order['deliver_status_str'];
                            }
                            $format_str .= chr(10) . '下单时间：' . date('Y-m-d H:i:s', $order['create_time']);
                            if ($order['pay_time']) {
                                $format_str .= chr(10) . '支付时间：' . date('Y-m-d H:i:s', $order['pay_time']);
                            }
                            if ($order['expect_use_time']) {
                                $format_str .= chr(10) . '期望送达时间：' . date('Y-m-d H:i:s', $order['expect_use_time']);
                            }
                            $format_str .= chr(10) . '支付状态：' . $order['pay_status_print'];
                            
                            $format_str .= chr(10) . str_repeat('*', $width * 2);
                            $format_str .= chr(10) . str_repeat(' ', $firstSpace) . '品名' . str_repeat(' ', $secondSpace) . '数量';//16
                            
                            foreach ($sortGoods[$sort['sort_id']] as $sGoods) {
                                if ($sGoods['number']) {
                                    $len = self::dstrlen($sGoods['number']);
                                    $len = $len % ($width * 2);
                                    if ($len > ($width * 2 - 8)) {
                                        $space = ($width * 2 - 7);
                                    } else {
                                        $space = ($width * 2 - 7)- $len;
                                    }
                                    $format_str .= chr(10) . self::cutstr($sGoods['number'], ($width * 2 - 8), $width * 2) . self::format($sGoods['num'], $space);
                                } else {
                                    $sGoods['spec'] && $sGoods['name'] .= '(' . $sGoods['spec'] . ')';
                                    
                                    $len = self::dstrlen($sGoods['name']);
                                    $len = $len % ($width * 2);
                                    if ($len > ($width * 2 - 8)) {
                                        $space = ($width * 2 - 7);
                                    } else {
                                        $space = ($width * 2 - 7)- $len;
                                    }
                                    $format_str .= chr(10) . self::cutstr($sGoods['name'], ($width * 2 - 8), $width * 2) . self::format($sGoods['num'], $space);
                                }
                            }
                            $this->orderPrint->newPrint($mainPrint, $format_str);
                        }
                    }
                }
            } else {
                $goods_ids = array();
                $newList = array();
                foreach ($list as $row) {
                    if (! in_array($row['goods_id'], $goods_ids)) {
                        $goods_ids[] = $row['goods_id'];
                    }
                    $newList[$row['goods_id']][] = $row;
                }
                if ($goods_ids) {
                    $goods = D('Shop_goods')->field(true)->where(array('goods_id' => array('in', $goods_ids)))->select();
                    $printGoodsArr = array();
                    foreach ($goods as $m) {
                        if (isset($printGoodsArr[$m['print_id']])) {
                            $printGoodsArr[$m['print_id']] = array_merge($printGoodsArr[$m['print_id']], $newList[$m['goods_id']]);
                        } else {
                            $printGoodsArr[$m['print_id']] = $newList[$m['goods_id']];
                        }
                    }
                    
                    foreach ($printGoodsArr as $pintid => $goodsList) {
                        $mainPrint = isset($prints[$pintid]) ? $prints[$pintid] : null;
                        if (empty($mainPrint)) {
                            continue;
                        }
                        if ($status != -1) {
                            $statusArr = explode(',', $mainPrint['paid']);
                            if (!in_array($status, $statusArr)) {
                                continue;
                            }
                        }
                        
                        $formatData = $this->getTwoData($mainPrint);
                        $width = $formatData['width'];
                        $firstSpace = $formatData['one'];
                        $secondSpace = $formatData['two'];
                        
                        $format_str = chr(10) . "订单编号：" . $order['real_orderid'];
                        $format_str .= chr(10) . str_repeat('*', $width * 2);
                        $format_str .= chr(10) . str_repeat(' ', $firstSpace) . '品名' . str_repeat(' ', $secondSpace) . '数量';//16
                        
                        foreach ($goodsList as $l) {
                            if ($l['number']) {
                                $len = self::dstrlen($l['number']);
                                $len = $len % ($width * 2);
                                if ($len > ($width * 2 - 8)) {
                                    $space = ($width * 2 - 7);
                                } else {
                                    $space = ($width * 2 - 7)- $len;
                                }
                                $format_str .= chr(10) . self::cutstr($l['number'], ($width * 2 - 8), $width * 2) . self::format($l['num'], $space);
                            } else {
                                $l['spec'] && $l['name'] .= '(' . $l['spec'] . ')';
                                $len = self::dstrlen($l['name']);
                                $len = $len % ($width * 2);
                                if ($len > ($width * 2 - 8)) {
                                    $space = ($width * 2 - 7);
                                } else {
                                    $space = ($width * 2 - 7)- $len;
                                }
                                
                                $format_str .= chr(10) . self::cutstr($l['name'], ($width * 2 - 8), $width * 2) . self::format($l['num'], $space);
                            }
                        }
                        $this->orderPrint->newPrint($mainPrint, $format_str);
                    }
                }
            }
        }
        
    }
	
	
	/*
	 *  一菜一单   printType 设置为1，打印机打印全部归属的菜品设置为0
	 */
    public function foodshop_menu($order_id, $menus, $status, $printType = 1)
    {
        $order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id), 3);
        
        if (empty($order)) return false;
        
        $prints = $this->getPrints($order['store_id']);
        if (empty($prints)) return false;
        
        $goods_ids = array();
        foreach ($menus as $row) {
            $goods_ids[] = $row['goods_id'];
            $newList[$row['goods_id']] = $row;
        }
        $print_ids = array();
        if ($goods_ids) {
            $goods = D('Foodshop_goods')->field(true)->where(array('goods_id' => array('in', $goods_ids)))->select();
            $printGoodsArr = array();
            foreach ($goods as $m) {
                if (isset($printGoodsArr[$m['print_id']])) {
                    $printGoodsArr[$m['print_id']][] = $newList[$m['goods_id']];
                } else {
                    $printGoodsArr[$m['print_id']] = array($newList[$m['goods_id']]);
                }
            }
            //分单打印
            foreach ($printGoodsArr as $pintid => $goodsList) {
                $mainPrint = isset($prints[$pintid]) ? $prints[$pintid] : null;
                if (empty($mainPrint)) {
                    continue;
                }
                
                if ($status != -1) {
                    $statusArr = explode(',', $mainPrint['paid']);
                    if (!in_array($status, $statusArr)) {
                        continue;
                    }
                }
                
                $formatData = $this->getTwoData($mainPrint);
                $width = $formatData['width'];
                $firstSpace = $formatData['one'];
                $secondSpace = $formatData['two'];
                if ($printType == 0) {
                    $format_str = chr(10) . "订单编号：" . $order['real_orderid'];
                    $format_str .= chr(10) . "桌台编号：" . $order['table_name'];
                    $format_str .= chr(10) . "打印时间：" . date('Y-m-d H:i');
                    $format_str .= chr(10) . str_repeat('*', $width * 2);
                    $format_str .= chr(10) . str_repeat(' ', $firstSpace) . '品名' . str_repeat(' ', $secondSpace) . '数量';//16
                }
                foreach ($goodsList as $l) {
                    if ($printType == 1) {
                        $format_str = chr(10) . "订单编号：" . $order['real_orderid'];
                        $format_str .= chr(10) . "桌台编号：" . $order['table_name'];
                        $format_str .= chr(10) . "打印时间：" . date('Y-m-d H:i');
                        $format_str .= chr(10) . str_repeat('*', $width * 2);
                        $format_str .= chr(10) . str_repeat(' ', $firstSpace) . '品名' . str_repeat(' ', $secondSpace) . '数量';//16
                    }
                    $l['spec'] && $l['name'] .= '(' . $l['spec'] . ')';
                    if ($l['note']) {
                        $l['name'] .= '(备注：' . $l['note'] . ')';
                    }
                    $len = self::dstrlen($l['name']);
                    $len = $len % ($width * 2);
                    if ($len > ($width * 2 - 8)) {
                        $space = ($width * 2 - 7);
                    } else {
                        $space = ($width * 2 - 7)- $len;
                    }
                    $format_str .= chr(10) . self::cutstr($l['name'], ($width * 2 - 8), $width * 2) . self::format($l['num'], $space);
                    
                    if ($printType == 1) {
                        $format_str .= chr(10) . chr(10) . chr(10) . chr(10);
                        $this->orderPrint->newPrint($mainPrint, $format_str);
                    }
                }
                if ($printType == 0) {
                    $this->orderPrint->newPrint($mainPrint, $format_str);
                }
            }
            
            //打印全部(主打印机)
            foreach ($prints as $mainPrint) {
                $statusArr = explode(',', $mainPrint['paid']);
                if ($mainPrint['is_main'] == 0 && !in_array(4, $statusArr)) {
                    continue;
                }
                $formatData = $this->getTwoData($mainPrint);
                $width = $formatData['width'];
                $firstSpace = $formatData['one'];
                $secondSpace = $formatData['two'];
                $print_all_str = '';
                $print_all_str .= chr(10) . "订单编号：" . $order['real_orderid'];
                $print_all_str .= chr(10) . "桌台编号：" . $order['table_name'];
                $print_all_str .= chr(10) . "打印时间：" . date('Y-m-d H:i');
                
                $print_all_str .= chr(10) . str_repeat('*', $width * 2);
                $print_all_str .= chr(10) . str_repeat(' ', $firstSpace) . '品名' . str_repeat(' ', $secondSpace) . '数量';//16
                foreach ($menus as $l) {
                    $l['spec'] && $l['name'] .= '(' . $l['spec'] . ')';
                    if ($l['note']) {
                        $l['name'] .= '(备注：' . $l['note'] . ')';
                    }
                    $len = self::dstrlen($l['name']);
                    $len = $len % ($width * 2);
                    if ($len > ($width * 2 - 8)) {
                        $space = ($width * 2 - 7);
                    } else {
                        $space = ($width * 2 - 7)- $len;
                    }
                    $print_all_str .= chr(10) . self::cutstr($l['name'], ($width * 2 - 8), $width * 2) . self::format($l['num'], $space);
                }
                if ($print_all_str) {
                    $this->orderPrint->newPrint($mainPrint, $print_all_str);
                }
            }
        }
    }
    
    public function foodshop_order_every($order, $prints, $status, $printType = 1)
    {
        if ($order['info']) {
            $list = $order['info'];
            $goods_ids = array();
            $newList = array();
            foreach ($list as $row) {
                $goods_ids[] = $row['id'];
                $goods_gids[] = $row['goods_id'];
                $newList[$row['id']] = $row;
            }
            if ($goods_ids) {
                $goods = D('Foodshop_goods')->field(true)->where(array('goods_id' => array('in', $goods_gids)))->select();
                foreach($newList as  &$nlist){
                    foreach($goods as $good){
                        if($nlist['goods_id']==$good['goods_id']){
                            $nlist['print_id'] = $good['print_id'];
                        }
                    }
                }

                $printGoodsArr = array();
                foreach ($newList as $m) {
                    if (isset($printGoodsArr[$m['print_id']])) {
                        $printGoodsArr[$m['print_id']][] = $newList[$m['id']];
                    } else {
                        $printGoodsArr[$m['print_id']] = array($newList[$m['id']]);
                    }
                }

                
                foreach ($printGoodsArr as $pintid => $goodsList) {
                    $mainPrint = isset($prints[$pintid]) ? $prints[$pintid] : null;
                    if (empty($mainPrint)) {
                        continue;
                    }
                    
                    if ($status != -1) {
                        $statusArr = explode(',', $mainPrint['paid']);
                        if (!in_array($status, $statusArr)) {
                            continue;
                        }
                    }
                    
                    $formatData = $this->getTwoData($mainPrint);
                    $width = $formatData['width'];
                    $firstSpace = $formatData['one'];
                    $secondSpace = $formatData['two'];
                    
                    if ($printType == 0) {
                        $format_str = chr(10) . "订单编号：" . $order['real_orderid'];
                        $format_str .= chr(10) . "桌台编号：" . $order['table_name'];
                        $format_str .= chr(10) . str_repeat('*', $width * 2);
                        $format_str .= chr(10) . str_repeat(' ', $firstSpace) . '品名' . str_repeat(' ', $secondSpace) . '数量';
                    }
                    foreach ($goodsList as $l) {
                        if ($printType == 1) {
                            $format_str = chr(10) . "订单编号：" . $order['real_orderid'];
                            $format_str .= chr(10) . "桌台编号：" . $order['table_name'];
                            $format_str .= chr(10) . str_repeat('*', $width * 2);
                            $format_str .= chr(10) . str_repeat(' ', $firstSpace) . '品名' . str_repeat(' ', $secondSpace) . '数量';
                        }
                        
                        $l['spec'] && $l['name'] .= '(' . $l['spec'] . ')';
                        if ($l['note']) {
                            $l['name'] .= '(备注：' . $l['note'] . ')';
                        }
                        $len = self::dstrlen($l['name']);
                        $len = $len % ($width * 2);
                        if ($len > ($width * 2 - 8)) {
                            $space = ($width * 2 - 7);
                        } else {
                            $space = ($width * 2 - 7)- $len;
                        }
                        $format_str .= chr(10) . self::cutstr($l['name'], ($width * 2 - 8), $width * 2) . self::format($l['num'], $space);
                        if ($printType == 1) {
                            $format_str .= chr(10) . chr(10) . chr(10) . chr(10);
                            $this->orderPrint->newPrint($mainPrint, $format_str);
                        }
                    }
                    if ($printType == 0) {
                        $this->orderPrint->newPrint($mainPrint, $format_str);
                    }
                }
            }
        }
    }
    
    public function meal_order_every($order, $prints)
    {
        if ($order['info']) {
            $list = unserialize($order['info']);
            $meal_ids = array();
            $newList = array();
            foreach ($list as $row) {
                if (! in_array($row['id'], $meal_ids)) {
                    $meal_ids[] = $row['id'];
                }
                $newList[$row['id']] = $row;
            }
            if ($meal_ids) {
                $meals = D('Meal')->field(true)->where(array('meal_id' => array('in', $meal_ids)))->select();
                $mid_pid = array();
                foreach ($meals as $m) {
                    $mid_pid[$m['meal_id']] = $m['print_id'];
                }
                
                $printGoodsArr = array();
                foreach ($meals as $m) {
                    if (isset($printGoodsArr[$m['print_id']])) {
                        $printGoodsArr[$m['print_id']][] = $newList[$m['meal_id']];
                    } else {
                        $printGoodsArr[$m['print_id']] = array($newList[$m['meal_id']]);
                    }
                }
                
                foreach ($printGoodsArr as $pintid => $goodsList) {
                    $mainPrint = isset($prints[$pintid]) ? $prints[$pintid] : null;
                    if (empty($mainPrint)) {
                        continue;
                    }
                    $format_str = "订单号：" . $order['order_id'];
                    $table_name = '';
                    if ($order['meal_type'] == 1) {
                        $meal_type = '外送';
                    } elseif ($order['meal_type'] == 0) {
                        $meal_type = '预定';
                        if ($table = D('Merchant_store_table')->field(true)->where(array('pigcms_id' => $order['tableid']))->find()) {
                            $table_name = $table['name'];
                        }
                    } elseif ($order['meal_type'] == 2) {
                        $meal_type = '现场pad点餐';
                        if ($table = D('Merchant_store_table')->field(true)->where(array('pigcms_id' => $order['tableid']))->find()) {
                            $table_name = $table['name'];
                        }
                    } elseif ($order['meal_type'] == 3) {
                        $meal_type = '在线购买';
                        if ($table = D('Merchant_store_table')->field(true)->where(array('pigcms_id' => $order['tableid']))->find()) {
                            $table_name = $table['name'];
                        }
                    }
                    $format_str .= chr(10) . "订单类型：" . $meal_type;
                    $table_name && $format_str .= chr(10) . "就餐桌位：" . $table_name;
                    $format_str .= chr(10) . str_repeat('*', $width * 2);
                    
                    foreach ($goodsList as $l) {
                        $format_str .= chr(10) . $l['name'] . ": ￥" . $l['price'] . " * " . $l['num'] . "({$l['unit']})";
                        $l['omark'] && $format_str .= chr(10) . "菜品备注: " . $l['omark'];
                    }
                    
                    $this->orderPrint->newPrint($mainPrint, $format_str);
                }
            }
        }
    }
    
    /**
     * 一菜一单【快店和餐饮】   主打印机打印，采用一条数据中间空行来分单
     */
    private function shop_order_only_one($order, $mainPrint)
    {
        $formatData = $this->getTwoData($mainPrint);
        $width = $formatData['width'];
        $firstSpace = $formatData['one'];
        $secondSpace = $formatData['two'];
        $format_str = '';
        foreach ($order['info'] as $val) {
            $format_str .= chr(10) . '订单编号:' . $order['real_orderid'];
            $format_str .= chr(10) . '下单时间：' . date('Y-m-d H:i', $order['create_time']);
            if ($order['pay_time']) {
                $format_str .= chr(10) . '支付时间：' . date('Y-m-d H:i', $order['pay_time']);
            }
            if ($order['expect_use_time']) {
                $format_str .= chr(10) . '送达时间：' . date('Y-m-d H:i', $order['expect_use_time']);
            }
            
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            if ($val['packname']) {
                $format_str .= chr(10) . $val['packname'];
            }
            $format_str .= chr(10) . str_repeat(' ', $firstSpace) . '品名' . str_repeat(' ', $secondSpace) . '数量';//16
            if ($val['number']) {
                $len = self::dstrlen($val['number']);
                $len = $len % ($width * 2);
                if ($len > ($width * 2 - 8)) {
                    $space = ($width * 2 - 7);
                } else {
                    $space = ($width * 2 - 7) - $len;
                }
                $format_str .= chr(10) . self::cutstr($val['number'], ($width * 2 - 8), $width * 2) . self::format($val['num'], $space);
            } else {
                $val['spec'] && $val['name'] .= '(' . $val['spec'] . ')';
                $len = self::dstrlen($val['name']);
                $len = $len % ($width * 2);
                if ($len > ($width * 2 - 8)) {
                    $space = ($width * 2 - 7);
                } else {
                    $space = ($width * 2 - 7) - $len;
                }
                
                $format_str .= chr(10) . self::cutstr($val['name'], ($width * 2 - 8), $width * 2) . self::format($val['num'], $space);
            }
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            
            
            if ($order['desc']) {
                $format_str .= chr(10) . '客户留言:' . $order['desc'];
            }
            $format_str .= chr(10) . chr(10) . chr(10) . chr(10);
        }
        return $this->orderPrint->newPrint($mainPrint, $format_str);
    }
    
    /**
     * 一菜一单【快店和餐饮】   主打印机打印，采用一条数据中间空行来分单
     */
    private function foodshop_order_only_one($order, $mainPrint)
    {
        $formatData = $this->getTwoData($mainPrint);
        $width = $formatData['width'];
        $firstSpace = $formatData['one'];
        $secondSpace = $formatData['two'];
        
        $format_str = '';
        foreach ($order['info'] as $val) {
            $format_str .= chr(10) . '订单编号:' . $order['real_orderid'];
            $format_str .= chr(10) . '桌台编号：' . $order['table_name'];
            if ($order['book_time_show']) {
                $format_str .= chr(10) . '预定时间：' . $order['book_time_show'];
            }
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            $format_str .= chr(10) . str_repeat(' ', $firstSpace) . '品名' . str_repeat(' ', $secondSpace) . '数量';
            $val['spec'] && $val['name'] .= '(' . $val['spec'] . ')';
            if ($val['note']) {
                $val['name'] .= '(备注：' . $val['note'] . ')';
            }
            $len = self::dstrlen($val['name']);
            $len = $len % ($width * 2);
            if ($len > ($width * 2 - 8)) {
                $space = ($width * 2 - 7);
            } else {
                $space = ($width * 2 - 7) - $len;
            }
            
            $format_str .= chr(10) . self::cutstr($val['name'], ($width * 2 - 8), $width * 2) . self::format($val['num'], $space);
            
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            if ($order['note']) {
                $format_str .= chr(10) . '客户留言:' . $order['note'];
            }
            $format_str .= chr(10) . chr(10) . chr(10) . chr(10);
        }
        return $this->orderPrint->newPrint($mainPrint, $format_str);
    }
    
    private function getThreeData($usePrinter)
    {
        if ($usePrinter['is_big'] == 0 && $usePrinter['paper'] == 0) {
            $width = 16;
            $firstSpace = 4;
            $secondSpace = 9;
            $thirdSpace = 6;
            $spaceWidth = 16;//数量前的所占的字符数
        } elseif ($usePrinter['is_big'] == 1 && $usePrinter['paper'] == 0) {
            $width = 12;
            $firstSpace = 3;
            $secondSpace = 4;
            $thirdSpace = 3;
            $spaceWidth = 10;
        } elseif ($usePrinter['is_big'] == 2 && $usePrinter['paper'] == 0) {
            $width = 8;
            $firstSpace = 1;
            $secondSpace = 2;
            $thirdSpace = 1;
            $spaceWidth = 8;
        } elseif ($usePrinter['is_big'] == 0 && $usePrinter['paper'] == 1) {
            $width = 24;
            $firstSpace = 7;
            $secondSpace = 14;
            $thirdSpace = 12;
            $spaceWidth = 24;
        } elseif ($usePrinter['is_big'] == 1 && $usePrinter['paper'] == 1) {
            $width = 18;
            $firstSpace = 4;
            $secondSpace = 11;
            $thirdSpace = 8;
            $spaceWidth = 18;
        } elseif ($usePrinter['is_big'] == 2 && $usePrinter['paper'] == 1) {
            $width = 12;
            $firstSpace = 3;
            $secondSpace = 4;
            $thirdSpace = 3;
            $spaceWidth = 10;
        }
        return array('width' => $width, 'one' => $firstSpace, 'two' => $secondSpace, 'three' => $thirdSpace, 'spaceWidth' => $spaceWidth);
    }
    
    private function getTwoData($mainPrint)
    {
        if ($mainPrint['is_big'] == 0 && $mainPrint['paper'] == 0) {
            $width = 16;
            $firstSpace = 4;
            $secondSpace = 16;
        } elseif ($mainPrint['is_big'] == 1 && $mainPrint['paper'] == 0) {
            $width = 12;
            $firstSpace = 4;
            $secondSpace = 8;
        } elseif ($mainPrint['is_big'] == 2 && $mainPrint['paper'] == 0) {
            $width = 8;
            $firstSpace = 2;
            $secondSpace = 2;
        } elseif ($mainPrint['is_big'] == 0 && $mainPrint['paper'] == 1) {
            $width = 24;
            $firstSpace = 4;
            $secondSpace = 32;
        } elseif ($mainPrint['is_big'] == 1 && $mainPrint['paper'] == 1) {
            $width = 18;
            $firstSpace = 4;
            $secondSpace = 20;
        } elseif ($mainPrint['is_big'] == 2 && $mainPrint['paper'] == 1) {
            $width = 12;
            $firstSpace = 4;
            $secondSpace = 8;
        }
        return array('width' => $width, 'one' => $firstSpace, 'two' => $secondSpace);
    }

    public static function dstrlen($string)
    {
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n ++;
                $noc ++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n ++;
            }
        }
        return $noc;
    }

    public static function cutstr($string, $length = 16, $mod = 32)
    {
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n ++;
                $noc ++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n ++;
            }
        }
        $noc = $noc % $mod;
        if ($noc > $length) {
            return $string . chr(10);
        } else {
            return $string;
        }
    }

    public static function format($string, $space)
    {
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n ++;
                $noc ++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n ++;
            }
        }
        if ($noc < 4) {
            $space += 1;
        } elseif ($noc > 4) {
            $space = $space - $noc + 4;
        }
        for ($i = 0; $i < $space; $i ++) {
            $string = chr(32) . $string;
        }
        if ($noc < 4) {
            for ($k = 0; $k < (3 - $noc); $k ++) {
                $string = $string . chr(32);
            }
        }
        return $string;
    }
    
    /**
     * 获取店铺的打印机列表 (如果店铺没有主打印机的话，将最先添加的打印机当做主打印机)
     * @param int $store_id
     * @return number|unknown[]
     */
    private function getPrints($store_id)
    {
        $prints = D('Orderprinter')->where(array('store_id' => $store_id))->order('is_main DESC, pigcms_id ASC')->select();
        $result = array();
        $isMain = 0;
        $firstId = 0;
        foreach ($prints as $print) {
            if ($print['is_main']) {
                $isMain = $print['pigcms_id'];
            } elseif (empty($firstId)) {
                $firstId = $print['pigcms_id'];
            }
            $result[$print['pigcms_id']] = $print;
        }
        if ($isMain == 0 && $firstId != 0) {
            $result[$firstId]['is_main'] = 1;
        }
        return $result;
    }
    
    
    
    private function shop_order($order_id, $status = 0)
    {
        $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id));
        if (empty($order)) return false;
        
        $prints = $this->getPrints($order['store_id']);
        if (empty($prints)) return false;
        
        $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
        $store_shop = D('Merchant_store_shop')->field('freight_alias, pack_alias')->where(array('store_id' => $order['store_id']))->find();
        $freight_alias = $store_shop['freight_alias'] ? $store_shop['freight_alias'] : '配送费用';
        $pack_alias = $store_shop['pack_alias'] ? $store_shop['pack_alias'] : '打包费用';
        //调用分单打印的数据处理
        $this->shopOrderMenu($order, $prints, $status);

        foreach ($prints as $usePrinter) {
            if ($usePrinter['is_main'] == 0) {
                continue;
            }
            
            if ($status != -1) {
                $statusArr = explode(',', $usePrinter['paid']);
                if (!in_array($status, $statusArr)) {
                    continue;
                }
            }
            
            $formatData = $this->getThreeData($usePrinter);
            $width = $formatData['width'];
            $firstSpace = $formatData['one'];
            $secondSpace = $formatData['two'];
            $thirdSpace = $formatData['three'];
            $spaceWidth= $formatData['spaceWidth'];
            
            
            if ($order['paid'] == 1 && $order['order_from'] != 6 && $order['is_pick_in_store'] < 2) {
                $title = '#' . $order['fetch_number']. C('config.shop_notice_plat_name');
            } else {
                $title = C('config.shop_notice_plat_name');
            }
            $titleNum = self::dstrlen($title);
            if ($usePrinter['is_big'] == 0) {
                $s = $width - $titleNum;
                $e = $width * 2 - $titleNum * 2 - $s;
            } elseif ($usePrinter['is_big'] == 1) {
                $s = floor(($width * 2 - $titleNum * 1.5) / 2);
                $e = floor($width * 2 - $titleNum * 1.5 - $s);
            } else {
                $s = floor(($width * 2 - $titleNum) / 2);
                $e = $width * 2 - $titleNum - $s;
            }
            $format_str = str_repeat('*', $s) . '<FH2><FW2>' . $title . '</FW2></FH2>'. str_repeat('*', $e);
            
            $format_str .= chr(10) . '店铺名称:' . $store['name'];
            $format_str .= chr(10) . '订单编号:' . $order['real_orderid'];
            $format_str .= chr(10) . '下单时间:' . date('m-d H:i', $order['create_time']);
            if ($order['order_from'] == 1) {//商城增加配送方式
                if ($order['is_pick_in_store'] == 2) {
                    $format_str .= chr(10) . '配送方式:自提（商城）';
                    $format_str .= chr(10) . '自提地址：' . $order['address'];
                } elseif ($order['is_pick_in_store'] == 3) {
                    $format_str .= chr(10) . '配送方式:快递配送（商城）';
                }
            } elseif ($order['is_pick_in_store'] == 2) {
                $format_str .= chr(10) . '配送方式:自提';
                $format_str .= chr(10) . '自提地址：' . $order['address'];
            }
            if ($order['desc']) {
                $format_str .= chr(10) . str_repeat('*', $width * 2);
                $format_str .= chr(10) . '<FH2><FW2>客户留言:' . $order['desc'] . '</FW2></FH2>';
            }
            
            
            $tempList = array();
            foreach ($order['info'] as $val) {
                $index = isset($val['packname']) && $val['packname'] ? $val['packname'] : 0;
                if (isset($tempList[$index])) {
                    $tempList[$index]['list'][] = $val;
                } else {
                    $tempList[$index] = array('name' => $val['packname'], 'list' => array($val));
                }
            }
            ksort($tempList);
            if ($order['order_from'] == 1 || $order['order_from'] == 6) {//商城或线下零售
                $format_str .= chr(10) . str_repeat('*', $width * 2);
            }
            foreach ($tempList as $list) {
                if ($order['order_from'] != 1 && $order['order_from'] != 6) {
                    if (empty($list['name'])) {
                        $list['name'] = '1号订购人';
                    }
                    $titleNum = self::dstrlen($list['name']);
                    $s = floor(($width * 2 - $titleNum) / 2);
                    $e = $width * 2 - $titleNum - $s;
                    $format_str .= chr(10) . str_repeat('*', $s). $list['name'] . str_repeat('*', $e);
                }
                
                foreach ($list['list'] as $val) {
                    if ($val['spec']) {
                        $val['name'] .= '(' . $val['spec'] . ')';
                    }
                    if ($val['number']) {
                        $format_str .= chr(10) . $val['name'];
                        $len = self::dstrlen($val['number']);
                        $len = $len % ($width * 2);
                        if ($len > $spaceWidth) {
                            $space = $spaceWidth + 1;
                        } else {
                            $space = $spaceWidth + 1 - $len;
                        }
                        $format_str .= chr(10) . self::cutstr($val['number'], $spaceWidth, $width * 2) . self::format('×' . $val['num'], $space) . self::format(floatval($val['price']), $thirdSpace);
                    } else {
                        $len = self::dstrlen($val['name']);
                        $len = $len % ($width * 2);
                        if ($len > $spaceWidth) {
                            $space = $spaceWidth + 1;
                        } else {
                            $space = $spaceWidth + 1 - $len;
                        }
                        $format_str .= chr(10) . self::cutstr($val['name'], $spaceWidth, $width * 2) . self::format('×' . $val['num'], $space) . self::format(floatval($val['price']), $thirdSpace);
                    }
                }
            }
            $format_str .= chr(10) . str_repeat('*', $width * 2);
            $format_str .= chr(10) . '商品总价：￥' . floatval($order['goods_price']);
            $format_str .= chr(10) . $freight_alias . '：￥' . floatval($order['freight_charge']);
            $format_str .= chr(10) . $pack_alias . '：￥' . floatval($order['packing_charge']);
            if ($order['paid']) {
                
                if ($order['pay_type'] == 'offline' && empty($order['third_id'])) {
                    $format_str .= chr(10) . '线下需支付：￥' . round($order['price'] - round($order['card_price'] + $order['merchant_balance'] + $order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'] + $order['card_give_money'], 2), 2);
                } elseif ($order['pay_type'] == 'offline' && !empty($order['third_id'])) {
                    $format_str .= chr(10) . '实付：￥' . round($order['price'] - round($order['card_price'] + $order['merchant_balance'] + $order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'] + $order['card_give_money'], 2), 2) . '(现金支付)';
                } else {
                    $format_str .= chr(10) . '实付：￥' . round($order['merchant_balance'] + $order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'] + $order['card_give_money'], 2) . '(在线支付)';
                }
            }
            if ($order['username'] || $order['userphone']) {
                $format_str .= chr(10) . str_repeat('※', $width);
                if ($order['username']) {
                    $format_str .= chr(10) . '客户姓名：' . $order['username'];
                }
                if ($order['userphone']) {
                    $format_str .= chr(10) . '客户手机：' . $order['userphone'];
                }
                if ($order['order_from'] != 6) {
                    if ($order['is_pick_in_store'] == 2) {
    //                     $format_str .= chr(10) . '自提地址：' . $order['address'];
                    } else {
                        $format_str .= chr(10) . '客户地址：' . $order['address'];
                    }
                }
            }
            
            $this->orderPrint->newPrint($usePrinter, $format_str);
        }
        return false;
    }
    
    
    /**
     * 快店的分单打印
     * @param array $order 订单详情
     * @param array $prints 打印机数组
     * @param int $status  当前状态
     * @param number $printType 打印类型， 0：按菜品归属打印机进行分组打印，1：按商品分类所属打印机进行分组打印
     */
    public function shopOrderMenu($order, $prints, $status, $printType = 0)
    {
        if ($order['info']) {
            $list = $order['info'];
            $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
            if ($printType == 1) {//按照分类打印
                $shopGoodsSortDB = D('Shop_goods_sort');
                $sortGoods = array();
                $fids = array();
                foreach ($list as $row) {
                    $sortIds = $shopGoodsSortDB->getIds($row['sort_id'], $row['store_id']);
                    if ($sortIds) {
                        $fid = array_shift($sortIds);
                        $fids[] = $fid;
                        $sortGoods[$fid][] = $row;
                    }
                }
                if ($fids) {
                    $sortList = $shopGoodsSortDB->field(true)->where(array('sort_id' => array('in', $fids)))->select();
                    foreach ($sortList as $sort) {
                        if ($sort['print_id'] && isset($sortGoods[$sort['sort_id']])) {
                            $mainPrint = isset($prints[$sort['print_id']]) ? $prints[$sort['print_id']] : null;
                            if (empty($mainPrint)) {
                                continue;
                            }
                            if ($status != -1) {
                                $statusArr = explode(',', $mainPrint['paid']);
                                if (!in_array($status, $statusArr)) {
                                    continue;
                                }
                            }
                            $formatData = $this->getTwoData($mainPrint);
                            $width = $formatData['width'];
                            $firstSpace = $formatData['one'];
                            $secondSpace = $formatData['two'];
                            
                            if ($order['order_from'] != 6 && $order['is_pick_in_store'] < 2) {
                                $title = '#' . $order['fetch_number']. C('config.shop_notice_plat_name');
                            } else {
                                $title = C('config.shop_notice_plat_name');
                            }
                            
                            $titleNum = self::dstrlen($title);
                            if ($mainPrint['is_big'] == 0) {
                                $s = $width - $titleNum;
                                $e = $width * 2 - $titleNum * 2 - $s;
                            } elseif ($mainPrint['is_big'] == 1) {
                                $s = floor(($width * 2 - $titleNum * 1.5) / 2);
                                $e = floor($width * 2 - $titleNum * 1.5 - $s);
                            } else {
                                $s = floor(($width * 2 - $titleNum) / 2);
                                $e = $width * 2 - $titleNum - $s;
                            }
                            $format_str = str_repeat('*', $s) . '<FH2><FW2>' . $title . '</FW2></FH2>'. str_repeat('*', $e);
                            
                            $format_str .= chr(10) . '店铺名称:' . $store['name'];
                            $format_str .= chr(10) . '商品分类：' . $sort['sort_name'];
                            $format_str .= chr(10) . '订单编号:' . $order['real_orderid'];
                            $format_str .= chr(10) . '下单时间:' . date('m-d H:i', $order['create_time']);

                            if ($order['desc']) {
                                $format_str .= chr(10) . str_repeat('*', $width * 2);
                                $format_str .= chr(10) . '<FH2><FW2>客户留言:' . $order['desc'] . '</FW2></FH2>';
                            }
                            
                            $tempList = array();
                            foreach ($sortGoods[$sort['sort_id']] as $val) {
                                $index = isset($val['packname']) && $val['packname'] ? $val['packname'] : 0;
                                if (isset($tempList[$index])) {
                                    $tempList[$index]['list'][] = $val;
                                } else {
                                    $tempList[$index] = array('name' => $val['packname'], 'list' => array($val));
                                }
                            }
                            ksort($tempList);
                            if ($order['order_from'] == 1 || $order['order_from'] == 6) {//商城或线下零售
                                $format_str .= chr(10) . str_repeat('*', $width * 2);
                            }
                            $total = 0;
                            foreach ($tempList as $list) {
                                if ($order['order_from'] != 1 && $order['order_from'] != 6) {
                                    if (empty($list['name'])) {
                                        $list['name'] = '1号订购人';
                                    }
                                    $titleNum = self::dstrlen($list['name']);
                                    $s = floor(($width * 2 - $titleNum) / 2);
                                    $e = $width * 2 - $titleNum - $s;
                                    $format_str .= chr(10) . str_repeat('*', $s). $list['name'] . str_repeat('*', $e);
                                }
                                
                                
                                foreach ($list['list'] as $sGoods) {
                                    if ($sGoods['spec']) {
                                        $sGoods['name'] .= '(' . $sGoods['spec'] . ')';
                                    }
                                    if ($sGoods['number']) {
                                        $format_str .= chr(10) . $sGoods['name'];
                                        $goodsRowNum = self::dstrlen($sGoods['number'] . '×' . $sGoods['num']);
                                        $goodsSpaceNum = $width * 2 - $goodsRowNum;
                                        $format_str .= chr(10) . $sGoods['number'] . str_repeat(' ', $goodsSpaceNum) . '×' . $sGoods['num'];
                                    } else {
                                        $goodsRowNum = self::dstrlen($sGoods['name'] . '×' . $sGoods['num']);
                                        $goodsSpaceNum = $width * 2 - $goodsRowNum;
                                        $format_str .= chr(10) . $sGoods['name'] . str_repeat(' ', $goodsSpaceNum) . '×' . $sGoods['num'];
                                    }
                                    $total += $sGoods['num'];
                                }
                            }
                            $format_str .= chr(10) . str_repeat('*', $width * 2);
                            
                            $footerNum = self::dstrlen('共计' .  $total . '件商品');
                            $spaceNum = $width * 2 - $footerNum;
                            $format_str .= chr(10) . '共计' . str_repeat(' ', $spaceNum) . $total . '件商品';
                            
                            $this->orderPrint->newPrint($mainPrint, $format_str);
                        }
                    }
                }
            } else {
                $goods_ids = array();
                $newList = array();
                foreach ($list as $row) {
                    if (! in_array($row['goods_id'], $goods_ids)) {
                        $goods_ids[] = $row['goods_id'];
                    }
                    $newList[$row['goods_id']][] = $row;
                }
                if ($goods_ids) {
                    $goods = D('Shop_goods')->field(true)->where(array('goods_id' => array('in', $goods_ids)))->select();
                    $printGoodsArr = array();
                    foreach ($goods as $m) {
                        if (isset($printGoodsArr[$m['print_id']])) {
                            $printGoodsArr[$m['print_id']] = array_merge($printGoodsArr[$m['print_id']], $newList[$m['goods_id']]);
                        } else {
                            $printGoodsArr[$m['print_id']] = $newList[$m['goods_id']];
                        }
                    }
                    
                    foreach ($printGoodsArr as $pintid => $goodsList) {
                        $mainPrint = isset($prints[$pintid]) ? $prints[$pintid] : null;
                        if (empty($mainPrint)) {
                            continue;
                        }
                        if ($status != -1) {
                            $statusArr = explode(',', $mainPrint['paid']);
                            if (!in_array($status, $statusArr)) {
                                continue;
                            }
                        }
                        
                        $formatData = $this->getTwoData($mainPrint);
                        $width = $formatData['width'];
                        $firstSpace = $formatData['one'];
                        $secondSpace = $formatData['two'];
                        
                        if ($order['order_from'] != 6 && $order['is_pick_in_store'] < 2) {
                            $title = '#' . $order['fetch_number']. C('config.shop_notice_plat_name');
                        } else {
                            $title = C('config.shop_notice_plat_name');
                        }
                        
                        $titleNum = self::dstrlen($title);
                        if ($mainPrint['is_big'] == 0) {
                            $s = $width - $titleNum;
                            $e = $width * 2 - $titleNum * 2 - $s;
                        } elseif ($mainPrint['is_big'] == 1) {
                            $s = floor(($width * 2 - $titleNum * 1.5) / 2);
                            $e = floor($width * 2 - $titleNum * 1.5 - $s);
                        } else {
                            $s = floor(($width * 2 - $titleNum) / 2);
                            $e = $width * 2 - $titleNum - $s;
                        }
                        $format_str = str_repeat('*', $s) . '<FH2><FW2>' . $title . '</FW2></FH2>'. str_repeat('*', $e);
                        $format_str .= chr(10) . '店铺名称:' . $store['name'];
                        $format_str .= chr(10) . '订单编号:' . $order['real_orderid'];
                        $format_str .= chr(10) . '下单时间:' . date('m-d H:i', $order['create_time']);
                        
                        if ($order['desc']) {
                            $format_str .= chr(10) . str_repeat('*', $width * 2);
                            $format_str .= chr(10) . '<FH2><FW2>客户留言:' . $order['desc'] . '</FW2></FH2>';
                        }
                        
                        
                        $tempList = array();
                        foreach ($goodsList as $val) {
                            $index = isset($val['packname']) && $val['packname'] ? $val['packname'] : 0;
                            if (isset($tempList[$index])) {
                                $tempList[$index]['list'][] = $val;
                            } else {
                                $tempList[$index] = array('name' => $val['packname'], 'list' => array($val));
                            }
                        }
                        ksort($tempList);
                        if ($order['order_from'] == 1 || $order['order_from'] == 6) {//商城或线下零售
                            $format_str .= chr(10) . str_repeat('*', $width * 2);
                        }
                        $total = 0;
                        foreach ($tempList as $list) {
                            if ($order['order_from'] != 1 && $order['order_from'] != 6) {
                                if (empty($list['name'])) {
                                    $list['name'] = '1号订购人';
                                }
                                $titleNum = self::dstrlen($list['name']);
                                $s = floor(($width * 2 - $titleNum) / 2);
                                $e = $width * 2 - $titleNum - $s;
                                $format_str .= chr(10) . str_repeat('*', $s). $list['name'] . str_repeat('*', $e);
                            }
                            foreach ($list['list'] as $sGoods) {
                                if ($sGoods['spec']) {
                                    $sGoods['name'] .= '(' . $sGoods['spec'] . ')';
                                }
                                if ($sGoods['number']) {
                                    $format_str .= chr(10) . $sGoods['name'];
                                    $goodsRowNum = self::dstrlen($sGoods['number'] . '×' . $sGoods['num']);
                                    $goodsSpaceNum = $width * 2 - $goodsRowNum;
                                    $format_str .= chr(10) . $sGoods['number'] . str_repeat(' ', $goodsSpaceNum) . '×' . $sGoods['num'];
                                } else {
                                    $goodsRowNum = self::dstrlen($sGoods['name'] . '×' . $sGoods['num']);
                                    $goodsSpaceNum = $width * 2 - $goodsRowNum;
                                    $format_str .= chr(10) . $sGoods['name'] . str_repeat(' ', $goodsSpaceNum) . '×' . $sGoods['num'];
                                }
                                $total += $sGoods['num'];
                            }
                        }
                        $format_str .= chr(10) . str_repeat('*', $width * 2);
                        $footerNum = self::dstrlen('共计' .  $total . '件商品');
                        $spaceNum = $width * 2 - $footerNum;
                        $format_str .= chr(10) . '共计' . str_repeat(' ', $spaceNum) . $total . '件商品';
                        
                        $this->orderPrint->newPrint($mainPrint, $format_str);
                    }
                }
            }
        }
        
    }
}