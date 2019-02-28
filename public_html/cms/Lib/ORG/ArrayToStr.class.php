<?php

class ArrayToStr
{

    public static function print_format($order_id, $table = 'meal_order', $printType = 0, $menus = null)
    {
        $str_format = array();
        if ($table == 'foodshop_order') {
            $order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id), 3);
        } elseif ($table == 'shop_order') {
            $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id));
        } else {
            $order = D(ucfirst($table))->field(true)->where(array('order_id' => $order_id))->find();
        }
        
        if (is_array($order)) {
            if ($table == 'meal_order') {
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
                            $mid_pid[$m['meal_id']] = $m['print_id'];
                        }
                        $str_format = array();
                        foreach ($list as $l) {
                            if (isset($str_format[$mid_pid[$l['id']]])) {
                                $str_format[$mid_pid[$l['id']]] .= chr(10) . $l['name'] . ": ￥" . $l['price'] . " * " . $l['num'] . "({$l['unit']})";
                                $l['omark'] && $str_format[$mid_pid[$l['id']]] .= chr(10) . "菜品备注: " . $l['omark'];
                            } else {
                                $str_format[$mid_pid[$l['id']]] = "订单号：" . $order['order_id'];
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
                                $str_format[$mid_pid[$l['id']]] .= chr(10) . "订单类型：" . $meal_type;
                                $table_name && $str_format[$mid_pid[$l['id']]] .= chr(10) . "就餐桌位：" . $table_name;
                                $str_format[$mid_pid[$l['id']]] .= chr(10) . "************************";
                                $str_format[$mid_pid[$l['id']]] .= chr(10) . $l['name'] . ": ￥" . $l['price'] . " * " . $l['num'] . "({$l['unit']})";
                                $l['omark'] && $str_format[$mid_pid[$l['id']]] .= chr(10) . "菜品备注: " . $l['omark'];
                            }
                        }
                    }
                }
            } elseif ($table == 'shop_order') {
                if ($order['info']) {
                    $list = $order['info'];
                    if ($printType == 1) {
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
                                    foreach ($sortGoods[$sort['sort_id']] as $sGoods) {
                                        if (isset($str_format[$sort['print_id']])) {
                                            if ($sGoods['number']) {
                                                $len = self::dstrlen($sGoods['number']);
                                                $len = $len % 32;
                                                if ($len > 24) {
                                                    $space = 25;
                                                } else {
                                                    $space = 25 - $len;
                                                }
                                                $str_format[$sort['print_id']] .= chr(10) . self::cutstr($sGoods['number'], 24) . self::format($sGoods['num'], $space);
                                            } else {
                                                $sGoods['spec'] && $sGoods['name'] .= '(' . $sGoods['spec'] . ')';
                                                
                                                $len = self::dstrlen($sGoods['name']);
                                                $len = $len % 32;
                                                if ($len > 24) {
                                                    $space = 25;
                                                } else {
                                                    $space = 25 - $len;
                                                }
                                                $str_format[$sort['print_id']] .= chr(10) . self::cutstr($sGoods['name'], 24) . self::format($sGoods['num'], $space);
                                            }
                                        } else {
                                            $str_format[$sort['print_id']] = chr(10) . '商品分类：' . $sort['sort_name'];
                                            $str_format[$sort['print_id']] .= chr(10) . '店铺名称：' . $store['name'];
                                            $str_format[$sort['print_id']] .= chr(10) . '店铺电话：' . $store['phone'];
                                            $str_format[$sort['print_id']] .= chr(10) . '店铺地址：' . $store['adress'];
                                            $str_format[$sort['print_id']] .= chr(10) . '打印时间：' . date('Y-m-d H:i:s');
                                            $str_format[$sort['print_id']] .= chr(10) . '订单编号:' . $order['real_orderid'];
                                            $str_format[$sort['print_id']] .= chr(10) . '客户姓名：' . $order['username'];
                                            $str_format[$sort['print_id']] .= chr(10) . '客户手机：' . $order['userphone'];
                                            if ($order['desc']) {
                                                $str_format[$sort['print_id']] .= chr(10) . '客户留言:' . $order['desc'];
                                            }
                                            if ($order['order_from'] != 6) {
                                                if ($order['is_pick_in_store'] == 2) {
                                                    $str_format[$sort['print_id']] .= chr(10) . '自提地址：' . $order['address'];
                                                } else {
                                                    $str_format[$sort['print_id']] .= chr(10) . '客户地址：' . $order['address'];
                                                }
                                                
                                                $str_format[$sort['print_id']] .= chr(10) . '配送方式：' . $order['deliver_str'];
                                                $str_format[$sort['print_id']] .= chr(10) . '配送状态：' . $order['deliver_status_str'];
                                            }
                                            $str_format[$sort['print_id']] .= chr(10) . '下单时间：' . date('Y-m-d H:i:s', $order['create_time']);
                                            if ($order['pay_time']) {
                                                $str_format[$sort['print_id']] .= chr(10) . '支付时间：' . date('Y-m-d H:i:s', $order['pay_time']);
                                            }
                                            if ($order['expect_use_time']) {
                                                $str_format[$sort['print_id']] .= chr(10) . '期望送达时间：' . date('Y-m-d H:i:s', $order['expect_use_time']);
                                            }
                                            $str_format[$sort['print_id']] .= chr(10) . '支付状态：' . $order['pay_status_print'];
                                            
                                            $str_format[$sort['print_id']] .= chr(10) . '********************************';
                                            $str_format[$sort['print_id']] .= chr(10) . '    品名                数量';
                                            
                                            if ($sGoods['number']) {
                                                $len = self::dstrlen($sGoods['number']);
                                                $len = $len % 32;
                                                if ($len > 24) {
                                                    $space = 25;
                                                } else {
                                                    $space = 25 - $len;
                                                }
                                                $str_format[$sort['print_id']] .= chr(10) . self::cutstr($sGoods['number'], 24) . self::format($sGoods['num'], $space);
                                            } else {
                                                $sGoods['spec'] && $sGoods['name'] .= '(' . $sGoods['spec'] . ')';
                                                
                                                $len = self::dstrlen($sGoods['name']);
                                                $len = $len % 32;
                                                if ($len > 24) {
                                                    $space = 25;
                                                } else {
                                                    $space = 25 - $len;
                                                }
                                                $str_format[$sort['print_id']] .= chr(10) . self::cutstr($sGoods['name'], 24) . self::format($sGoods['num'], $space);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $goods_ids = array();
                        foreach ($list as $row) {
                            if (! in_array($row['goods_id'], $goods_ids)) {
                                $goods_ids[] = $row['goods_id'];
                            }
                        }
                        if ($goods_ids) {
                            $goods = D('Shop_goods')->field(true)->where(array('goods_id' => array('in', $goods_ids)))->select();
                            $mid_pid = array();
                            foreach ($goods as $m) {
                                $mid_pid[$m['goods_id']] = $m['print_id'];
                            }
                            $str_format = array();
                            foreach ($list as $l) {
                                if (isset($str_format[$mid_pid[$l['goods_id']]])) {
                                    if ($l['number']) {
                                        $len = self::dstrlen($l['number']);
                                        $len = $len % 32;
                                        if ($len > 24) {
                                            $space = 25;
                                        } else {
                                            $space = 25 - $len;
                                        }
                                        $str_format[$mid_pid[$l['goods_id']]] .= chr(10) . self::cutstr($l['number'], 24) . self::format($l['num'], $space);
                                    } else {
                                        $l['spec'] && $l['name'] .= '(' . $l['spec'] . ')';
                                        
                                        $len = self::dstrlen($l['name']);
                                        $len = $len % 32;
                                        if ($len > 24) {
                                            $space = 25;
                                        } else {
                                            $space = 25 - $len;
                                        }
                                        $str_format[$mid_pid[$l['goods_id']]] .= chr(10) . self::cutstr($l['name'], 24) . self::format($l['num'], $space);
                                    }
                                } else {
                                    $str_format[$mid_pid[$l['goods_id']]] = chr(10) . "订单编号：" . $order['real_orderid'];
                                    $str_format[$mid_pid[$l['goods_id']]] .= chr(10) . '********************************';
                                    $str_format[$mid_pid[$l['goods_id']]] .= chr(10) . '    品名                数量';
                                    if ($l['number']) {
                                        $len = self::dstrlen($l['number']);
                                        $len = $len % 32;
                                        if ($len > 24) {
                                            $space = 25;
                                        } else {
                                            $space = 25 - $len;
                                        }
                                        $str_format[$mid_pid[$l['goods_id']]] .= chr(10) . self::cutstr($l['number'], 24) . self::format($l['num'], $space);
                                    } else {
                                        $l['spec'] && $l['name'] .= '(' . $l['spec'] . ')';
                                        
                                        $len = self::dstrlen($l['name']);
                                        $len = $len % 32;
                                        if ($len > 24) {
                                            $space = 25;
                                        } else {
                                            $space = 25 - $len;
                                        }
                                        $str_format[$mid_pid[$l['goods_id']]] .= chr(10) . self::cutstr($l['name'], 24) . self::format($l['num'], $space);
                                    }
                                }
                            }
                        }
                    }
                }
            } elseif ($table == 'foodshop_order') {
                $str_format = array();
                if ($menus) {
                    $goods_ids = array();
                    foreach ($menus as $row) {
                        $goods_ids[] = $row['goods_id'];
                    }
                    $print_ids = array();
                    if ($goods_ids) {
                        $list = D('Foodshop_goods')->field(true)->where(array('goods_id' => array('in', $goods_ids)))->select();
                        foreach ($list as $lr) {
                            $print_ids[$lr['goods_id']] = $lr['print_id'];
                        }
                    }
                    $print_all_str = '';
                    foreach ($menus as $l) {
                        $thisRowData = '';
                        $thisRowData .= chr(10) . "订单编号：" . $order['real_orderid'];
                        $thisRowData .= chr(10) . "桌台编号：" . $order['table_name'];
                        $thisRowData .= chr(10) . "下单时间：" . date('Y-m-d H:i', $order['create_time']);
                        $thisRowData .= chr(10) . '********************************';
                        $thisRowData .= chr(10) . '    品名                数量';
                        $l['spec'] && $l['name'] .= '(' . $l['spec'] . ')';
                        
                        $len = self::dstrlen($l['name']);
                        $len = $len % 32;
                        if ($len > 24) {
                            $space = 25;
                        } else {
                            $space = 25 - $len;
                        }
                        $thisRowData .= chr(10) . self::cutstr($l['name'], 24) . self::format($l['num'], $space);
                        $str_format[$print_ids[$l['goods_id']]][] = $thisRowData;
                        
                        if (empty($print_all_str)) {
                            $print_all_str .= chr(10) . "订单编号：" . $order['real_orderid'];
                            $print_all_str .= chr(10) . "桌台编号：" . $order['table_name'];
                            $print_all_str .= chr(10) . "下单时间：" . date('Y-m-d H:i', $order['create_time']);
                            $print_all_str .= chr(10) . '********************************';
                            $print_all_str .= chr(10) . '    品名                数量';
                        }
                        $len = self::dstrlen($l['name']);
                        $len = $len % 32;
                        if ($len > 24) {
                            $space = 25;
                        } else {
                            $space = 25 - $len;
                        }
                        $print_all_str .= chr(10) . self::cutstr($l['name'], 24) . self::format($l['num'], $space);
                    }
                    $str_format['all'] = $print_all_str;
                } elseif ($order['info']) {
                    $list = $order['info'];
                    $goods_ids = array();
                    foreach ($list as $row) {
                        if (! in_array($row['goods_id'], $goods_ids)) {
                            $goods_ids[] = $row['goods_id'];
                        }
                    }
                    if ($goods_ids) {
                        $goods = D('Foodshop_goods')->field(true)->where(array('goods_id' => array('in', $goods_ids)))->select();
                        $mid_pid = array();
                        foreach ($goods as $m) {
                            $mid_pid[$m['goods_id']] = $m['print_id'];
                        }
                        foreach ($list as $l) {
                            $thisRowData = '';
                            $thisRowData .= chr(10) . "订单编号：" . $order['real_orderid'];
                            $thisRowData .= chr(10) . "桌台编号：" . $order['table_name'];
                            $thisRowData .= chr(10) . '********************************';
                            $thisRowData .= chr(10) . '    品名                数量';
                            $l['spec'] && $l['name'] .= '(' . $l['spec'] . ')';
                            
                            $len = self::dstrlen($l['name']);
                            $len = $len % 32;
                            if ($len > 24) {
                                $space = 25;
                            } else {
                                $space = 25 - $len;
                            }
                            $thisRowData .= chr(10) . self::cutstr($l['name'], 24) . self::format($l['num'], $space);
                            $str_format[$mid_pid[$l['goods_id']]][] = $thisRowData;
                        }
                    }
                }
            }
        }
        return $str_format;
    }

    public static function array_to_str($order_id, $table = 'meal_order')
    {
        if ($table == 'foodshop_order') {
            $order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id), 3);
        } elseif ($table == 'shop_order') {
            $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id));
        } else {
            $order = D(ucfirst($table))->field(true)->where(array('order_id' => $order_id))->find();
        }
        if (is_array($order)) {
            $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
            $payarr = array(
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
            if ($table == 'meal_order') {
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
                        $meals = D('Meal')->field(true)
                            ->where(array(
                            'meal_id' => array(
                                'in',
                                $meal_ids
                            )
                        ))
                            ->select();
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
                $pay_type = isset($payarr[$order['pay_type']]) ? $payarr[$order['pay_type']] : '';
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
                return $print_format;
            } elseif ($table == 'group_order') {
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
                
                $pay_type = isset($payarr[$order['pay_type']]) ? $payarr[$order['pay_type']] : '';
                if (empty($pay_type) && $order['paid']) {
                    $pay_type = '余额支付';
                }
                $print_format = preg_replace('/\{pay_status\}/', $pay_status, $print_format);
                $print_format = preg_replace('/\{pay_type\}/', $pay_type, $print_format);
                $print_format = preg_replace('/\{print_time\}/', date('Y-m-d H:i:s'), $print_format);
                return $print_format;
            } elseif ($table == 'waimai_order') {
                $store_where['store_id'] = array('in', $order['store_id']);
                $store_info = D('Merchant_store')->field(true)->where($store_where)->select();
                $deliverStore = D("Deliver_store")->field(true)->where($store_where)->find();
                
                $merchant_where['mer_id'] = array('in', $order['mer_id']);
                $merchant_info = D('Merchant')->field(true)->where($merchant_where)->select();
                
                $orderId[$order_id] = $order_id;
                $deliver_where['order_id'] = array('in', $orderId);
                $deliverSupplyInfo = D("Deliver_supply")->field('`order_id`,`start_time`,`end_time`')
                    ->where($deliver_where)
                    ->select();
                
                $orderObj = new Waimai_orderModel();
                $now_order = $orderObj->formatArray(array($order), $store_info, $merchant_info, $deliverSupplyInfo);
                $now_order = $now_order[0];
                $now_order['deliver_type'] = $deliverStore['type'];
                
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
                $format_str .= chr(10) . "************************";
                foreach ($now_order['goods_list'] as $val) {
                    $format_str .= chr(10) . $val['name'] . ": ￥" . $val['price'] . " * " . $val['num'];
                }
                $format_str .= chr(10) . "************************";
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
                return $format_str;
            } elseif ($table == 'appoint_order') {
                $order_info = D(ucfirst($table))->get_order_detail_by_id($order['uid'], $order['order_id']);
                
                $user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
                $store_info = D('Merchant_store')->get_store_by_storeId($order_info['store_id']);
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
                $format_str .= chr(10) . '************************';
                if ($order_info['appoint_date'] && $order_info['appoint_date']) {
                    $format_str .= chr(10) . '预约时间：' . $order_info['appoint_date'] . ' ' . $order_info['appoint_date'];
                }
                $format_str .= chr(10) . '预约店铺：' . $order_info['appoint_name'];
                $format_str .= chr(10) . '预约类型：' . ($order_info['appoint_type'] == 0 ? '到店' : '上门');
                
                if ($order_info['worker_detail']) {
                    $worker_detail = $order_info['worker_detail'];
                    $format_str .= chr(10) . '工作人员：' . $worker_detail['name'];
                }
                $format_str .= chr(10) . '************************';
                
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
                $format_str .= chr(10) . '※※※※※※※※※※※※※※※※';
                $format_str .= chr(10) . '店铺名称：' . $store_info['name'];
                $format_str .= chr(10) . '店铺电话：' . $store_info['phone'];
                $format_str .= chr(10) . '店铺地址：' . $store_info['area_ip_desc'] . chr(32) . $store_info['adress'];
                $format_str .= chr(10) . '打印时间：' . date('Y-m-d H:i:s');
                $format_str .= chr(10) . '谢谢惠顾，欢迎再次光临！';
                return $format_str;
            } elseif ($table == 'shop_order') {
                $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
                $store_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
                $freight_alias = $store_shop['freight_alias'] ? $store_shop['freight_alias'] : '配送费用';
                $pack_alias = $store_shop['pack_alias'] ? $store_shop['pack_alias'] : '打包费用';
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
                    $format_str .= chr(10) . '********************************';
                    if ($list['name']) {
                        $format_str .= chr(10) . $list['name'];
                    }
                    $format_str .= chr(10) . '    品名         数量      单价';
                    foreach ($list['list'] as $val) {
                        if ($val['spec']) {
                            $val['name'] .= '(' . $val['spec'] . ')';
                        }
                        if ($val['number']) {
                            $format_str .= chr(10) . $val['name'];
                            $len = self::dstrlen($val['number']);
                            $len = $len % 32;
                            if ($len > 16) {
                                $space = 17;
                            } else {
                                $space = 17 - $len;
                            }
                            $format_str .= chr(10) . self::cutstr($val['number']) . self::format($val['num'], $space) . self::format('￥' . floatval($val['price']) . '/' . $val['unit'], 6);
                        } else {
                            $len = self::dstrlen($val['name']);
                            $len = $len % 32;
                            if ($len > 16) {
                                $space = 17;
                            } else {
                                $space = 17 - $len;
                            }
                            $format_str .= chr(10) . self::cutstr($val['name']) . self::format($val['num'], $space) . self::format('￥' . floatval($val['price']) . '/' . $val['unit'], 6);
                        }
                    }
                    $format_str .= chr(10) . '********************************';
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
                $format_str .= chr(10) . '********************************';
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
                $format_str .= chr(10) . '※※※※※※※※※※※※※※※※';
                $format_str .= chr(10) . '店铺名称：' . $store['name'];
                $format_str .= chr(10) . '店铺电话：' . $store['phone'];
                $format_str .= chr(10) . '店铺地址：' . $store['adress'];
                $format_str .= chr(10) . '打印时间：' . date('Y-m-d H:i:s');
                $format_str .= chr(10) . '谢谢惠顾，欢迎再次光临！';
                
                
                /***************************newo2o.cn*******************************************/
//                 $format_str .= chr(10) . chr(10) . chr(10) . chr(10);
//                 $format_str .= chr(10) . "订单编号：" . $order['real_orderid'];
//                 $format_str .= chr(10) . '********************************';
//                 $format_str .= chr(10) . '    品名                数量';
//                 foreach ($order['info'] as $val) {
//                     if ($val['number']) {
//                         $len = self::dstrlen($val['number']);
//                         $len = $len % 32;
//                         if ($len > 24) {
//                             $space = 25;
//                         } else {
//                             $space = 25 - $len;
//                         }
//                         $format_str .= chr(10) . self::cutstr($val['number'], 24) . self::format($val['num'], $space);
//                     } else {
//                         $val['spec'] && $val['name'] .= '(' . $val['spec'] . ')';
//                         $len = self::dstrlen($val['name']);
//                         $len = $len % 32;
//                         if ($len > 24) {
//                             $space = 25;
//                         } else {
//                             $space = 25 - $len;
//                         }
//                         $format_str .= chr(10) . self::cutstr($val['name'], 24) . self::format($val['num'], $space);
//                     }
//                 }
                /***************************newo2o.cn*******************************************/
                
                return $format_str;
            } elseif ($table == 'store_order') {
                $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
                $format_str = '';
                $format_str .= chr(10) . '订单编号:' . $order['order_id'];
                $format_str .= chr(10) . '流水号:' . $order['orderid'];
                $format_str .= chr(10) . '订单总价:' . $order['total_price'];
                $format_str .= chr(10) . '优惠金额:' . $order['discount_price'];
                $format_str .= chr(10) . '实付金额:' . $order['price'];
                $format_str .= chr(10) . '※※※※※※※※※※※※※※※※';
                $format_str .= chr(10) . '店铺名称：' . $store['name'];
                $format_str .= chr(10) . '店铺电话：' . $store['phone'];
                $format_str .= chr(10) . '店铺地址：' . $store['adress'];
                $format_str .= chr(10) . '打印时间：' . date('Y-m-d H:i:s');
                return $format_str;
            } elseif ($table == 'foodshop_order') {
                $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
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
                
                $format_str .= chr(10) . '********************************';
                $format_str .= chr(10) . '    品名         数量      单价';
                foreach ($order['info'] as $val) {
                    if ($val['spec']) {
                        $val['name'] .= '(' . $val['spec'] . ')';
                    }
                    
                    $len = self::dstrlen($val['name']);
                    $len = $len % 32;
                    if ($len > 16) {
                        $space = 17;
                    } else {
                        $space = 17 - $len;
                    }
                    
                    $format_str .= chr(10) . self::cutstr($val['name']) . self::format($val['num'], $space) . self::format('￥' . floatval($val['price']) . '/' . $val['unit'], 6);
                }
                $format_str .= chr(10) . '********************************';
                
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
                    $format_str .= chr(10) . '实付金额：' . floatval($order['price']);
                }
                
                $format_str .= chr(10) . '※※※※※※※※※※※※※※※※';
                $format_str .= chr(10) . '店铺名称：' . $store['name'];
                $format_str .= chr(10) . '店铺电话：' . $store['phone'];
                $format_str .= chr(10) . '店铺地址：' . $store['adress'];
                $format_str .= chr(10) . '打印时间：' . date('Y-m-d H:i:s');
                $format_str .= chr(10) . '谢谢惠顾，欢迎再次光临！';
                return $format_str;
            }
        }
    }
    public static function printOnlyOne($order_id, $table = 'shop_order')
    {
        if ($table == 'foodshop_order') {
            $order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id), 3);
        } elseif ($table == 'shop_order') {
            $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id));
        } else {
            return false;
            $order = D(ucfirst($table))->field(true)->where(array('order_id' => $order_id))->find();
        }
        if (is_array($order)) {
            if ($table == 'shop_order') {
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
                    
                    $format_str .= chr(10) . '********************************';
                    if ($val['packname']) {
                        $format_str .= chr(10) . $val['packname'];
                    }
                    $format_str .= chr(10) . '    品名       数量';
                    if ($val['number']) {
                        $format_str .= chr(10) . self::cutstr($val['number']) . self::format($val['num'], 12);
                    } else {
                        $val['spec'] && $val['name'] .= '(' . $val['spec'] . ')';
                        $format_str .= chr(10) . self::cutstr($val['name']) . self::format($val['num'], 12);
                    }
                    $format_str .= chr(10) . '********************************';
                    
                    
                    if ($order['desc']) {
                        $format_str .= chr(10) . '客户留言:' . $order['desc'];
                    }
                    $format_str .= chr(10) . chr(10) . chr(10) . chr(10);
                }
                return $format_str;
            } elseif ($table == 'foodshop_order') {
                $format_str = '';
                foreach ($order['info'] as $val) {
                    $format_str .= chr(10) . '订单编号:' . $order['real_orderid'];
                    $format_str .= chr(10) . '桌台编号：' . $order['table_name'];
                    if ($order['book_time_show']) {
                        $format_str .= chr(10) . '预定时间：' . $order['book_time_show'];
                    }
                    $format_str .= chr(10) . '********************************';
                    $format_str .= chr(10) . '    品名                数量';
                    $val['spec'] && $val['name'] .= '(' . $val['spec'] . ')';
                    $format_str .= chr(10) . self::cutstr($val['name']) . self::format($val['num'], 12);
                    $format_str .= chr(10) . '********************************';
                    if ($order['note']) {
                        $format_str .= chr(10) . '客户留言:' . $order['note'];
                    }
                    $format_str .= chr(10) . chr(10) . chr(10) . chr(10);
                }
                return $format_str;
            }
        }
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
}
