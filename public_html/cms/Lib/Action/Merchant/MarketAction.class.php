<?php
class MarketAction extends BaseAction
{
    public function __construct()
    {
        parent::__construct();
        if (empty($this->config['is_open_market'])) {
            $this->error('平台没有开启进销存功能');
            exit;
        }
    }
    const GOODS_SORT_LEVEL = 3;
    
    public function index()
    {
        $result = D('Market_goods')->getList(array('mer_id' => $this->merchant_session['mer_id']), 'my');
        $this->assign($result);
        $this->display();
    }
    public function market()
    {
        $cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
        $cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
        $province_id = isset($_GET['province_id']) ? intval($_GET['province_id']) : 0;
        $city_id = isset($_GET['city_id']) ? intval($_GET['city_id']) : 0;
        $area_id = isset($_GET['area_id']) ? intval($_GET['area_id']) : 0;
        $where = array('mer_id' => $this->merchant_session['mer_id'], 'status' => 1);
        $where['province_id'] = $province_id;
        $where['city_id'] = $city_id;
        $where['area_id'] = $area_id;
        if ($cat_fid && $cat_id) {
            $where['cat_fid'] = $cat_fid;
            $where['cat_id'] = $cat_id;
        }
        if($_GET['goods_name']){
            $where['goods_name'] = $_GET['goods_name'];
        }
        $result = D('Market_goods')->getList($where, 'market');
        $this->assign($result);
        $this->assign($where);
        $category_list = D('Goods_wholesale_category')->get_list();
        $this->assign('category_list', json_encode($category_list));
        $this->cartCount();
        $this->display();
    }
    
    public function sell_order()
    {
        $data = array('mer_id' => $this->merchant_session['mer_id']);
        if (isset($_GET['goods_id']) && $_GET['goods_id']) {
            $data['goods_id'] = intval($_GET['goods_id']);
        }
        $result = D('Market_order')->getList($data, 'sell');
        $this->assign($result);
        $this->display();
    }
    
    public function buy_order()
    {
        $result = D('Market_order')->getList(array('mer_id' => $this->merchant_session['mer_id']), 'buy');
        
        $goodsIDs = array();
        foreach ($result['orders'] as $row) {
            $goodsIDs[] = $row['goods_id'];
        }
        
        
        $sql = "SELECT `s`.`store_id` FROM ". C('DB_PREFIX') . "merchant_store AS s INNER JOIN  ". C('DB_PREFIX') . "merchant_store_shop AS ss ON `s`.`store_id`=`ss`.`store_id`";
        $sql .= " WHERE `s`.`mer_id`={$this->merchant_session['mer_id']} AND `s`.`status`='1' AND `s`.`have_shop`='1'";
        $sql .= " ORDER BY `s`.`sort` DESC,`s`.`store_id` ASC";
        $store_list = D()->query($sql);
        $storeIDs = array();
        foreach ($store_list as $store) {
            $storeIDs[] = $store['store_id'];
        }
        
        $goods_list = M('Shop_goods')->field(true)->where(array('original_goods_id' => array('in', $goodsIDs), 'store_id' => array('in', $storeIDs)))->select();
        $goodsList = array();
        foreach ($goods_list as $goods) {
            if (isset($goodsList[$goods['original_goods_id']])) {
                $goodsList[$goods['original_goods_id']][] = $goods['store_id'];
            } else {
                $goodsList[$goods['original_goods_id']] = array($goods['store_id']);
            }
        }
        
        foreach ($result['orders'] as &$new) {
            $tempStoreIds = isset($goodsList[$new['goods_id']]) ? $goodsList[$new['goods_id']] : array();
            $newStoreIds = array_diff($storeIDs, $tempStoreIds);
            $new['store_count'] = count($newStoreIds);
            if (count($newStoreIds) == 1) {
                $new['to_store_id'] = array_shift($newStoreIds);
            } else {
                $new['to_store_id'] = 0;
            }
        }
            
        $this->assign($result);
        $this->display();
    }
    
    public function buy()
    {
        $goodsId = intval($_GET['goods_id']);
        $order_id = intval($_GET['order_id']);
        $databaseMarketGoods = D('Market_goods');
        $where = array('goods_id' => $goodsId);
        $marketGoods = $databaseMarketGoods->field(true)->where($where)->find();
        if(empty($marketGoods)){
            $this->error('商品不存在！');
        }
        
        if(!empty($marketGoods['image'])){
            $goods_image_class = new goods_image();
            $tmp_pic_arr = explode(';', $marketGoods['image']);
            foreach ($tmp_pic_arr as $key => $value) {
                $marketGoods['pic_arr'][$key]['title'] = $value;
                $marketGoods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
            }
        }
        
        $return = $databaseMarketGoods->format_spec_value($marketGoods['spec_value'], $marketGoods['goods_id']);
        $marketGoods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
        $marketGoods['list'] = isset($return['list']) ? $return['list'] : '';
        $marketGoods['discount_info'] = json_decode($marketGoods['discount_info'], true);
        $marketGoods['username'] = $this->merchant_session['name'];
        $marketGoods['userphone'] = $this->merchant_session['phone'];
        $marketGoods['address'] = '';
        $marketGoods['buy_num'] = $marketGoods['min_num'];
        $totalMoney = 0;
        $totalCount = 0;
        
        if ($marketGoods['list']) {
            foreach ($marketGoods['list']as $vo) {
                $totalCount += $vo['min_num'];
                $totalMoney += $vo['min_num'] * $vo['price'];
            }
        } else {
            $totalMoney = $marketGoods['buy_num'] * $marketGoods['price'];
            $totalCount = $marketGoods['buy_num'];
        }
        
        if ($marketOrderOld = M('Market_order')->field(true)->where(array('order_id' => $order_id, 'goods_id' => $goodsId, 'mer_id' => $this->merchant_session['mer_id'], 'status' => 10))->find()) {
            $totalMoney = 0;
            $totalCount = 0;
            if ($marketOrderOld['spec_value'] && $marketGoods['list']) {
                $spec_array = explode('#', $marketOrderOld['spec_value']);
                foreach ($spec_array as $str) {
                    $row_array = explode('|', $str);
                    $i = str_replace(':', '_', $row_array[0]);
                    if (isset($marketGoods['list'][$i])) {
                        $tArray = explode(':', $row_array[1]);
                        $marketGoods['list'][$i]['buy_num'] = $tArray[3];
                        $totalCount += $tArray[3];
                        $totalMoney += $tArray[1] * $tArray[3];
                    }
                }
            } else {
                $totalCount = $marketOrderOld['num'];
                $totalMoney = $marketOrderOld['num'] * $marketOrderOld['price'];
            }
            
            $marketGoods['buy_num'] = $marketOrderOld['num'];
            $marketGoods['username'] = $marketOrderOld['username'];
            $marketGoods['userphone'] = $marketOrderOld['userphone'];
            $marketGoods['address'] = $marketOrderOld['address'];
        }
        
        $discount_info = null;
        foreach ($marketGoods['discount_info'] as $discount) {
            if ($discount['num'] <= $totalCount) {
                if ($discount_info) {
                    if ($discount_info['discount'] > $discount['discount']) {
                        $discount_info = $discount;
                    }
                } else {
                    $discount_info = $discount;
                }
            }
        }
        if ($discount_info) {
            $totalMoney= $totalMoney* $discount_info['discount'] * 0.1;
        }
        $marketGoods['totalMoney'] = $totalMoney;
        
        if (IS_POST) {
            $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
            $userphone = isset($_POST['userphone']) ? htmlspecialchars(trim($_POST['userphone'])) : '';
            $address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : '';
            $desc = isset($_POST['desc']) ? htmlspecialchars(trim($_POST['desc'])) : '';
            if (empty($username)) {
                $this->error('收货人不能为空');
                exit;
            }
            if (empty($userphone)) {
                $this->error('联系电话不能为空');
                exit;
            }
            if (empty($address)) {
                $this->error('收货地址不能为空');
                exit;
            }
            
            if ($marketGoods['spec_list']) {
                $buy_nums = $_POST['buy_nums'];
                
                $totalNum = 0;
                $totalPrice = 0;
                $spec_array = explode('#', $marketGoods['spec_value']);
                $target_spec_value_array = array();
                foreach ($spec_array as $str) {
                    $row_array = explode('|', $str);
                    $i = 'id_' . str_replace(':', '_id_', $row_array[0]);
                    $numArray = explode(':', $row_array[1]);
                    if ($buy_nums[$i] >= $numArray[2] && $buy_nums[$i] <= $numArray[3]) {
                        $totalNum += $buy_nums[$i];
                        $totalPrice += $buy_nums[$i] * $numArray[1];
                        //规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#
                        $numArray[3] = $buy_nums[$i];
                        $numArray[2] = 0;
                        $row_array[1] = implode(':', $numArray);
                        $target_spec_value_array[] = implode('|', $row_array);
                    }
                }
                
                if ($totalNum <= 0) {
                    $error_tips .= '您至少要批发一种规格且满足对应的批发条件<br/>';
                }
                
            } else {
                $totalNum = isset($_POST['buy_num']) ? intval($_POST['buy_num']) : $marketGoods['min_num'];
                if ($totalNum > $marketGoods['stock_num']) {
                    $error_tips .= '该商品库存不足<br/>';
                }
                if ($totalNum < $marketGoods['min_num']) {
                    $error_tips .= '一次至少要批发' . $marketGoods['min_num'] . $marketGoods['unit'] . '<br/>';
                }
                $totalPrice = $totalNum * $marketGoods['price'];
            }

            if (empty($error_tips)) {
                $discount_info = null;
                foreach ($marketGoods['discount_info'] as $discount) {
                    if ($discount['num'] <= $totalNum) {
                        if ($discount_info) {
                            if ($discount_info['discount'] > $discount['discount']) {
                                $discount_info = $discount;
                            }
                        } else {
                            $discount_info = $discount;
                        }
                    }
                }
                $money = $totalPrice;
                if ($discount_info) {
                    $money = $totalPrice * $discount_info['discount'] * 0.1;
                }
                
                
                $marketOrder = array('mer_id' => $this->merchant_session['mer_id'], 'last_time' => time());
                $marketOrder['money'] = $money;
                $marketOrder['total_price'] = $totalPrice;
                $marketOrder['num'] = $totalNum;
                $marketOrder['price'] = $marketGoods['price'];
                $marketOrder['sell_mer_id'] = $marketGoods['mer_id'];
                $marketOrder['sell_store_id'] = $marketGoods['store_id'];
                $marketOrder['status'] = 10;

                $marketOrder['goods_id'] = $marketGoods['goods_id'];
                $marketOrder['name'] = $marketGoods['name'];
                $marketOrder['number'] = $marketGoods['number'];
                $marketOrder['unit'] = $marketGoods['unit'];
                $marketOrder['image'] = $marketGoods['image'];
                $marketOrder['des'] = $marketGoods['des'];
                $marketOrder['is_properties'] = $marketGoods['is_properties'];
                $marketOrder['discount_info'] = $discount_info ? json_encode($discount_info) : '';
                
                $marketOrder['username'] = $username;
                $marketOrder['userphone'] = $userphone;
                $marketOrder['address'] = $address;
                $marketOrder['desc'] = $desc;
                $marketOrder['create_time'] = time();
                
                $marketOrder['spec_value'] = $target_spec_value_array ? implode('#', $target_spec_value_array) : '';
                
                if ($marketOrderOld) {
                    if ($order_id = M('Market_order')->where(array('order_id' => $order_id, 'goods_id' => $goodsId, 'mer_id' => $this->merchant_session['mer_id'], 'status' => 10))->save($marketOrder)) {
                        $order_id = $marketOrderOld['order_id'];
                        //删除已有的属性与规格的数据
                        $where['order_id'] = $order_id;
                        M('Market_order_properties')->where($where)->delete();
                        $spec_list = M('Market_order_spec')->field(true)->where($where)->select();
                        foreach ($spec_list as $spec) {
                            M('Market_order_spec_value')->where(array('sid' => $spec['id'], 'order_id' => $order_id))->delete();
                        }
                        M('Market_order_spec')->where($where)->delete();
                    }
                } else {
                    $order_id = M('Market_order')->add($marketOrder);
                }
        
                if ($order_id) {
                    if ($marketGoods['is_properties']) {
                        $properties = M('Market_goods_properties')->field(true)->where(array('goods_id' => $marketGoods['goods_id']))->select();
                        foreach ($properties as $pro_data) {
                            $pro_data['order_id'] = $order_id;
                            M('Market_order_properties')->add($pro_data);
                        }
                    }
        
                    if ($marketGoods['spec_value']) {
                        $spec_list = M('Market_goods_spec')->field(true)->where(array('goods_id' => $marketGoods['goods_id']))->select();
                        foreach ($spec_list as $spec) {
                            $spec['order_id'] = $order_id;
                            if (M('Market_order_spec')->add($spec)) {
                                $spec_value_list = M('Market_goods_spec_value')->field(true)->where(array('sid' => $spec['id']))->select();
                                foreach ($spec_value_list as $spec_value) {
                                    $spec_value['order_id'] = $order_id;
                                    M('Market_order_spec_value')->add($spec_value);
                                }
                            }
                        }
                    }
                    $this->success('下单成功！', U('pay', array('order_id' => $order_id)));
                } else {
                    $this->error('下单失败！请重试！');
                }
            } else {
                $this->error($error_tips);
            }
        } else {
            $this->assign('now_goods', $marketGoods);
            $this->cartCount();
            $this->display();
        }
    }
    
    public function pay()
    {
        $order_id = intval($_GET['order_id']);
        $databaseMarketOrder = D('Market_order');
        $merchant = M('Merchant')->where(array('mer_id' => $this->merchant_session['mer_id']))->find();
        if ($marketOrder = $databaseMarketOrder->field(true)->where(array('order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id'], 'status' => 10))->find()) {
            if(!empty($marketOrder['image'])){
                $goods_image_class = new goods_image();
                $tmp_pic_arr = explode(';', $marketOrder['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $marketOrder['pic_arr'][$key]['title'] = $value;
                    $marketOrder['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
                }
            }
            
            $return = $databaseMarketOrder->format_spec_value($marketOrder['spec_value'], $marketOrder['goods_id'], $order_id);
            $marketOrder['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
            $marketOrder['list'] = isset($return['list']) ? $return['list'] : '';
            $marketOrder['discount_info'] = json_decode($marketOrder['discount_info'], true);
            $this->assign('now_goods', $marketOrder);
            $this->assign('merchant', $merchant);
        } else {
            $this->error('订单信息错误');
            exit();
        }
        if (IS_POST) {
            if ($marketOrder['money'] > $merchant['money']) {
                $money = floatval($marketOrder['money'] - $merchant['money']);
                if (empty($money)||$money <0 ||!is_numeric($money)) {
                    $this->error('请输入正确的充值金额');
                }
                
                $data_mer_recharge_order['mer_id'] = $this->merchant_session['mer_id'];
                $data_mer_recharge_order['money'] = $money;
                $data_mer_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
                $data_mer_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
                $data_mer_recharge_order['label'] = 'web_marketorder_' . $order_id;
                if ($order_id = M('Merchant_recharge_order')->data($data_mer_recharge_order)->add()) {
                    redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'merrecharge')));
                } else {
                    $this->error_tips('订单创建失败，请重试。');
                }
            } else {
                redirect(U('pay_success', array('order_id' => $order_id)));
            }
        } else {
            $this->display();
        }
    }
    
    public function pay_success()
    {
        $order_id = intval($_GET['order_id']);
        $databaseMarketOrder = D('Market_order');
        if ($marketOrder = $databaseMarketOrder->field(true)->where(array('order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id'], 'status' => 10))->find()) {
            $return = $databaseMarketOrder->format_spec_value($marketOrder['spec_value'], $marketOrder['goods_id'], $order_id);
            $marketOrder['list'] = isset($return['list']) ? $return['list'] : '';
            
            
            $databaseMarketGoods = D('Market_goods');
            $where = array('goods_id' => $marketOrder['goods_id']);
            $marketGoods = $databaseMarketGoods->field(true)->where($where)->find();
            if(empty($marketGoods)){
                
                //批发市场是没有这条数据的时候删除没有支付的订单所有的数据
                $where['order_id'] = $order_id;
                M('Market_order_properties')->where($where)->delete();
                $spec_list = M('Market_order_spec')->field(true)->where($where)->select();
                foreach ($spec_list as $spec) {
                    M('Market_order_spec_value')->where(array('sid' => $spec['id'], 'order_id' => $order_id))->delete();
                }
                M('Market_order_spec')->where($where)->delete();
                
                $databaseMarketOrder->where(array('order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id'], 'status' => 10))->delete();
                
                $this->error('该商品在批发市场上已下架，不能购买了', U('market'));
                exit();
            }
            
            $marketGoodsChageData = array();
            if ($marketOrder['list']) {
                $return = $databaseMarketGoods->format_spec_value($marketGoods['spec_value'], $marketGoods['goods_id']);
                $marketGoods['list'] = isset($return['list']) ? $return['list'] : '';
                
                $spec_array = explode('#', $marketGoods['spec_value']);
                
                foreach ($marketOrder['list'] as $index => $row) {
                    $target_spec_value_array = array();
                    foreach ($spec_array as $str) {
                        $row_array = explode('|', $str);
                        $i = str_replace(':', '_', $row_array[0]);
                        if ($i == $index) {
                            $numArray = explode(':', $row_array[1]);
                            $numArray[3] = max(0, ($numArray[3] - $row['stock_num']));
                            $row_array[1] = implode(':', $numArray);
                        }
                        $target_spec_value_array[] = implode('|', $row_array);
                    }
                    $spec_array = $target_spec_value_array;
                    
                    
                    if (isset($marketGoods['list'][$index])) {
                        if ($row['stock_num'] > $marketGoods['list'][$index]['stock_num']) {
                            $this->error('购买的商品库存不足', U('buy', array('order_id' => $marketOrder['order_id'], 'goods_id' => $marketOrder['goods_id'])));
                            exit();
                        } elseif ($row['stock_num'] < $marketGoods['list'][$index]['min_num']) {
                            $this->error('购买的商品的数量不足最小批发量', U('buy', array('order_id' => $marketOrder['order_id'], 'goods_id' => $marketOrder['goods_id'])));
                            exit();
                        }
                    } else {
                        $this->error('购买的商品已经没有这种规格了', U('buy', array('order_id' => $marketOrder['order_id'], 'goods_id' => $marketOrder['goods_id'])));
                        exit();
                    }
                }
                $marketGoodsChageData['spec_value'] = implode('#', $spec_array);
            } elseif ($marketOrder['num'] > $marketGoods['stock_num']) {
                $this->error('购买的商品库存不足', U('buy', array('order_id' => $marketOrder['order_id'], 'goods_id' => $marketOrder['goods_id'])));
                exit();
            } elseif ($marketOrder['num'] < $marketGoods['min_num']) {
                $this->error('购买的商品的数量不足最小批发量', U('buy', array('order_id' => $marketOrder['order_id'], 'goods_id' => $marketOrder['goods_id'])));
                exit();
            }
            
            $marketGoodsChageData['stock_num'] = max(0, ($marketGoods['stock_num'] - $marketOrder['num']));
            $marketGoodsChageData['sell_count'] = $marketGoods['sell_count'] + $marketOrder['num'];
            
            //自己减少余额
            $merchant = M('Merchant')->where(array('mer_id' => $this->merchant_session['mer_id']))->find();
            if ($merchant['money'] >= $marketOrder['money']) {
                $returnData = D('Merchant_money_list')->use_money($this->merchant_session['mer_id'],$marketOrder['money'],'market','购买批发市场的商品【'.$marketOrder['name'].'】扣除商家余额',$marketOrder['order_id']);
            } else {
                $this->error('您的余额不足，请充值！', U('pay', array('order_id' => $marketOrder['order_id'], 'goods_id' => $marketOrder['goods_id'])));
                exit();
            }
            
            
            if ($returnData['error_code']) {
                $this->error('支付失败！请重试！', U('pay', array('order_id' => $marketOrder['order_id'], 'goods_id' => $marketOrder['goods_id'])));
                exit();
            }
            
            //修改订单状态
            $databaseMarketOrder->where(array('order_id' => $order_id))->save(array('pay_time' => time(), 'status' => 1));
            
            //更改批发市场中商品的库存
            $databaseMarketGoods->where(array('goods_id' => $marketOrder['goods_id']))->save($marketGoodsChageData);
            
            $sms_data = array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => 0, 'type' => 'market');
            
            $sellmerchant = M('Merchant')->where(array('mer_id' => $marketOrder['sell_mer_id']))->find();
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $sellmerchant['phone'];
            $sms_data['sendto'] = 'merchant';
            $sms_data['content'] = '商家' . $merchant['name'] . '批发了' . $marketOrder['name'] . '共' . $marketOrder['num'] . $marketOrder['unit'] . '订单号：' . $marketOrder['order_id'] . '请您注意查看并处理!';
            Sms::sendSms($sms_data);
            
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $merchant['phone'];;
            $sms_data['sendto'] = 'merchant';
            $sms_data['content'] = '您在' . $sellmerchant['name'] . '批发了' . $marketOrder['name'] . '共' . $marketOrder['num'] . $marketOrder['unit'] . '订单号：' . $marketOrder['order_id'] . '已经完成支付。欢迎下次光临!';
            Sms::sendSms($sms_data);
            
            $this->success('支付成功', U('buy_order'));
        } else {
            $this->error('订单信息错误', U('buy', array('order_id' => $marketOrder['order_id'], 'goods_id' => $marketOrder['goods_id'])));
            exit();
        }
    }
    
    public function order_detail()
    {
        $order_id = intval($_GET['order_id']);
        $type = $_GET['type'];
        if (!in_array($type, array('buy', 'sell'))) {
            $this->error('信息来源不正确');
        }

        
        $databaseMarketOrder = D('Market_order');
        $where = array('order_id' => $order_id);
        $orderList = array();
        if ($type == 'buy') {
            $fid = isset($_GET['fid']) ? intval($_GET['fid']) : 0;
            if ($totalData = D('Market_total')->where(array('id' => $fid, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
                $databaseMarketGoods = D('Market_order');
                $orderList = $databaseMarketOrder->where(array('mer_id' => $this->merchant_session['mer_id'], 'fid' => $fid))->select();
            } else {
                $where['mer_id'] = $this->merchant_session['mer_id'];
            }
        } else {
            $where['sell_mer_id'] = $this->merchant_session['mer_id'];
        }
        $userData = array();
        if ($orderList) {
            foreach ($orderList as &$marketOrder) {
                if(!empty($marketOrder['image'])){
                    $goods_image_class = new goods_image();
                    $tmp_pic_arr = explode(';', $marketOrder['image']);
                    foreach ($tmp_pic_arr as $key => $value) {
                        $marketOrder['pic_arr'][$key]['title'] = $value;
                        $marketOrder['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
                    }
                }
                
                $return = $databaseMarketOrder->format_spec_value($marketOrder['spec_value'], $marketOrder['goods_id'], $marketOrder['order_id']);
                $marketOrder['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
                $marketOrder['list'] = isset($return['list']) ? $return['list'] : '';
                $marketOrder['discount_info'] = json_decode($marketOrder['discount_info'], true);
                $marketOrder['express_name'] = D('Express')->get_express($marketOrder['express_id']);
                $marketOrder['express_name'] = isset($marketOrder['express_name']['name']) ? $marketOrder['express_name']['name'] : '';
                
                if ($merchant = M('Merchant')->field('name, phone')->where(array('mer_id' => $marketOrder['sell_mer_id']))->find()) {
                    $marketOrder['merchant_name'] = $merchant['name'];
                    $marketOrder['merchant_phone'] = $merchant['phone'];
                    
                }
                if ($store = M('Merchant_store')->field('name, phone')->where(array('store_id' => $marketOrder['sell_store_id']))->find()) {
                    $marketOrder['store_name'] = $store['name'];
                    $marketOrder['store_phone'] = $store['phone'];
                }
                if (empty($userData)) {
                    $userData['username'] = $marketOrder['username'];
                    $userData['userphone'] = $marketOrder['userphone'];
                    $userData['address'] = $marketOrder['address'];
                    $userData['desc'] = $marketOrder['desc'];
                    $userData['express_name'] = $marketOrder['express_name'];
                    $userData['express_number'] = $marketOrder['express_number'];
                    $userData['sell_note'] = $marketOrder['sell_note'];
                    $userData['create_time'] = $marketOrder['create_time'];
                    $userData['pay_time'] = $marketOrder['pay_time'];
                    $userData['send_time'] = $marketOrder['send_time'];
                    $userData['pull_time'] = $marketOrder['pull_time'];
                }
            }
            $this->assign('userData', $userData);
            $this->assign('orderList', $orderList);
            $this->display();
        } elseif ($marketOrder = $databaseMarketOrder->field(true)->where($where)->find()) {
            if(!empty($marketOrder['image'])){
                $goods_image_class = new goods_image();
                $tmp_pic_arr = explode(';', $marketOrder['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $marketOrder['pic_arr'][$key]['title'] = $value;
                    $marketOrder['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
                }
            }
            
            $return = $databaseMarketOrder->format_spec_value($marketOrder['spec_value'], $marketOrder['goods_id'], $order_id);
            $marketOrder['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
            $marketOrder['list'] = isset($return['list']) ? $return['list'] : '';
            $marketOrder['discount_info'] = json_decode($marketOrder['discount_info'], true);
            $marketOrder['express_name'] = D('Express')->get_express($marketOrder['express_id']);
            $marketOrder['express_name'] = isset($marketOrder['express_name']['name']) ? $marketOrder['express_name']['name'] : '';
            
            if ($type == 'buy') {
                if ($merchant = M('Merchant')->field('name, phone')->where(array('mer_id' => $marketOrder['sell_mer_id']))->find()) {
                    $marketOrder['merchant_name'] = $merchant['name'];
                    $marketOrder['merchant_phone'] = $merchant['phone'];
                    
                }
                if ($store = M('Merchant_store')->field('name, phone')->where(array('store_id' => $marketOrder['sell_store_id']))->find()) {
                    $marketOrder['store_name'] = $store['name'];
                    $marketOrder['store_phone'] = $store['phone'];
                }
            } else {
                if ($merchant = M('Merchant')->field('name, phone')->where(array('mer_id' => $marketOrder['mer_id']))->find()) {
                    $marketOrder['merchant_name'] = $merchant['name'];
                    $marketOrder['merchant_phone'] = $merchant['phone'];
                    
                }
            }
            
            $userData['username'] = $marketOrder['username'];
            $userData['userphone'] = $marketOrder['userphone'];
            $userData['address'] = $marketOrder['address'];
            $userData['desc'] = $marketOrder['desc'];
            $userData['express_name'] = $marketOrder['express_name'];
            $userData['express_number'] = $marketOrder['express_number'];
            $userData['sell_note'] = $marketOrder['sell_note'];
            $userData['create_time'] = $marketOrder['create_time'];
            $userData['pay_time'] = $marketOrder['pay_time'];
            $userData['send_time'] = $marketOrder['send_time'];
            $userData['pull_time'] = $marketOrder['pull_time'];
            $this->assign('userData', $userData);
            $this->assign('orderList', array($marketOrder));
            $this->display();
        } else {
            $this->error('错误的订单信息');
        }
    }
    
    //发货
    public function push()
    {
        $order_id = intval($_GET['order_id']);
        $databaseMarketOrder = D('Market_order');
        if ($marketOrder = $databaseMarketOrder->field(true)->where(array('order_id' => $order_id, 'sell_mer_id' => $this->merchant_session['mer_id']))->find()) {
            if ($marketOrder['status'] != 2 && $marketOrder['status'] != 1) {
                $this->error('此单不能发货');
                exit;
            }
            if (IS_POST) {
                $express_id = isset($_POST['express_id']) ? intval($_POST['express_id']) : 0;
                $express_number = isset($_POST['express_number']) ? htmlspecialchars(trim($_POST['express_number'])) : 0;
                $sell_note = isset($_POST['sell_note']) ? htmlspecialchars(trim($_POST['sell_note'])) : '';
                $express = D('Express')->get_express($express_id);
                if (empty($express)) {
                    $this->error('请选择正确的快递公司');
                    exit;
                }
                if (empty($express_number)) {
                    $this->error('请选择填写快递单号');
                    exit;
                }
                $data['express_id'] = $express_id;
                $data['express_number'] = $express_number;
                $data['sell_note'] = $sell_note;
                $data['send_time'] = time();
                $data['status'] = 2;
                if ($databaseMarketOrder->where(array('order_id' => $order_id, 'sell_mer_id' => $this->merchant_session['mer_id']))->save($data)) {
                    
                    $buymerchant = M('Merchant')->where(array('mer_id' => $marketOrder['mer_id']))->find();
                    $sms_data['uid'] = 0;
                    $sms_data['mobile'] = $buymerchant['phone'];
                    $sms_data['sendto'] = 'merchant';
                    $sms_data['content'] = '您在' . $this->merchant_session['name'] . '批发了' . $marketOrder['name'] . '共' . $marketOrder['num'] . $marketOrder['unit'] . '订单号：' . $marketOrder['order_id'] . '对方已经发货。请您注意查看并处理!';
                    Sms::sendSms($sms_data);
                    
                    $this->success('发货成功');
                } else {
                    $this->error('发货失败');
                }
            } else {
                if(!empty($marketOrder['image'])){
                    $goods_image_class = new goods_image();
                    $tmp_pic_arr = explode(';', $marketOrder['image']);
                    foreach ($tmp_pic_arr as $key => $value) {
                        $marketOrder['pic_arr'][$key]['title'] = $value;
                        $marketOrder['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
                    }
                }
                $return = $databaseMarketOrder->format_spec_value($marketOrder['spec_value'], $marketOrder['goods_id'], $order_id);
                $marketOrder['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
                $marketOrder['list'] = isset($return['list']) ? $return['list'] : '';
                $marketOrder['discount_info'] = json_decode($marketOrder['discount_info'], true);
                $this->assign('now_goods', $marketOrder);
                $this->assign('express_list', D('Express')->get_express_list());
                $this->display();
            }
        } else {
            $this->error('错误的订单信息');
        }
    }
    
    //收货
    public function pull()
    {
        $order_id = intval($_POST['order_id']);
        $databaseMarketOrder = D('Market_order');
        $where = array('order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id']);
        if ($marketOrder = $databaseMarketOrder->field(true)->where($where)->find()) {
            if ($marketOrder['status'] != 2) {
                exit(json_encode(array('error_code' => true, 'msg' => '此单暂时不能收货')));
            } else {
                if ($databaseMarketOrder->where($where)->save(array('status' => 3, 'last_time' => time(), 'pull_time' => time()))) {
                    //对方增加余额
                    $data = array();
                    $data['order_type'] = 'market';
                    $data['desc'] = '批发市场的商品【'.$marketOrder['name'].'】卖出时增加商家余额';
                    $data['mer_id'] = $marketOrder['sell_mer_id'];
                    $data['money'] = $marketOrder['money'];
                    $data['order_id'] = $marketOrder['order_id'];
                    $data['total_money'] = $marketOrder['money'];
                    // D('Merchant_money_list')->add_money($data);
                    D('SystemBill')->bill_method(0,$data);
                    
                    
                    $sellmerchant = M('Merchant')->where(array('mer_id' => $marketOrder['sell_mer_id']))->find();
                    $sms_data['uid'] = 0;
                    $sms_data['mobile'] = $sellmerchant['phone'];
                    $sms_data['sendto'] = 'merchant';
                    $sms_data['content'] = '您发给' . $this->merchant_session['name'] . '的' . $marketOrder['name'] . '共' . $marketOrder['num'] . $marketOrder['unit'] . '对方已经收货，订单号：' . $marketOrder['order_id'];
                    Sms::sendSms($sms_data);
                    
                    
                    exit(json_encode(array('error_code' => false, 'msg' => 'ok')));
                } else {
                    exit(json_encode(array('error_code' => true, 'msg' => '收货失败，稍后重试')));
                }
            }
        } else {
            exit(json_encode(array('error_code' => true, 'msg' => '订单信息错误')));
        }
    }
    
    public function add_to_store()
    {

        $order_id = intval($_GET['order_id']);
        $store_id = intval($_GET['store_id']);
		$now_store = $this->check_store($store_id);
        $databaseMarketOrder = D('Market_order');
        $where = array('order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id'], 'status' => 3);
        if ($marketOrder = $databaseMarketOrder->field(true)->where($where)->find()) {
            if ($tGoods = M('Shop_goods')->field(true)->where(array('original_goods_id' => $marketOrder['goods_id'], 'store_id' => $store_id))->find()) {
                $this->error('该商品已上架，请不要重复上架');
                exit;
            }
            if(!empty($marketOrder['image'])){
                $goods_image_class = new goods_image();
                $tmp_pic_arr = explode(';', $marketOrder['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $marketOrder['pic_arr'][$key]['title'] = $value;
                    $marketOrder['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
                }
            }
            $marketOrder['cost_price'] = $marketOrder['price'];
            $marketOrder['stock_num'] = $marketOrder['num'];
            
            $return = $databaseMarketOrder->format_spec_value($marketOrder['spec_value'], $marketOrder['goods_id'], $order_id);
            $marketOrder['json'] = isset($return['json']) ? json_encode($return['json']) : '';
            $marketOrder['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
            $marketOrder['list'] = isset($return['list']) ? $return['list'] : '';
            if (IS_POST) {
                if (empty($_POST['name'])) {
                    $error_tips .= '商品名称必填！'.'<br/>';
                }
                if (empty($_POST['unit'])) {
                    $error_tips .= '商品单位必填！'.'<br/>';
                }
                if (empty($_POST['price'])&&!$this->config['open_extra_price']) {
                    $error_tips .= '商品价格必填！'.'<br/>';
                }
                if (empty($_POST['pic'])) {
                    $error_tips .= '请至少上传一张照片！'.'<br/>';
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
                	
                $sort_id = 0;
                for ($i = 1; $i <= self::GOODS_SORT_LEVEL; $i++) {
                    if (isset($_POST['sort_id_' . $i]) && intval($_POST['sort_id_' . $i])) {
                        $sort_id = intval($_POST['sort_id_' . $i]);
                        unset($_POST['sort_id_' . $i]);
                    }
                }
                if (empty($sort_id)) {
                    $error_tips .= '请选择分类'.'<br/>';
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
                	
                $_POST['seckill_open_time'] = strtotime($_POST['seckill_open_time'] . ":00");
                $_POST['seckill_close_time'] = strtotime($_POST['seckill_close_time'] . ":00");
            
                if (empty($error_tips)) {
                    $_POST['goods_id'] = $marketOrder['goods_id'];
                    $_POST['sort_id'] = $sort_id;
                    $_POST['store_id'] = $now_store['store_id'];
                    $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
//                     echo '<pre/>';
//                     print_r($_POST);die;
                    if ($goods_id = D('Shop_goods')->savePostForm($_POST, $now_store['store_id'])) {
                        D('Image')->update_table_id($_POST['image'], $goods_id, 'goods');
                        $this->success('保存成功！', U('Shop/goods_list', array('sort_id' => $sort_id)));
                        die;
                        $ok_tips = '保存成功！';
                    } else {
                        $this->error('保存失败！请重试！');
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
            
            $this->assign('now_goods', $marketOrder);
//             $this->assign('now_sort', $now_sort);
            $this->assign('now_store', $now_store);
            $this->assign('express_template', D('Express_template')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->select());
            
            $this->display();
        } else {
            $this->error('订单信息有误，暂不能发布到店铺');
        }
    }
    
    public function select_store()
    {
        $order_id = intval($_GET['order_id']);

        $databaseMarketOrder = D('Market_order');
        $where = array('order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id'], 'status' => 3);
        if ($marketOrder = $databaseMarketOrder->field(true)->where($where)->find()) {
            $sql = "SELECT s.store_id, s.name FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON sh.store_id=s.store_id WHERE s.have_shop=1 AND s.status=1 AND s.mer_id={$this->merchant_session['mer_id']}";
            $res = D()->query($sql);
            $storeIds = array();
            $storeList = array();
            foreach ($res as $r) {
                $storeIds[] = $r['store_id'];
                $storeList[$r['store_id']] = $r;
            }
            
            $goods_list = M('Shop_goods')->field(true)->where(array('original_goods_id' => $marketOrder['goods_id'], 'store_id' => array('in', $storeIds)))->select();
            $goodsList = array();
            foreach ($goods_list as $goods) {
                unset($storeList[$goods['store_id']]);
            }
            $this->assign('stores', $storeList);
            $this->display();
        } else {
            $this->error('订单信息有误，暂不能发布到店铺');
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
        $list = array();
        foreach ($formart_data as $fi => $string) {
            $array = explode('_', $string);
            $array = array_chunk($array, 2);
            $index = $pre = '';
            $tdata = array();
            foreach ($array as $irow) {
                $k = $irow[0];
                $ki = $irow[1];
                $r = $data['spec_val_id'][$irow[0]][$irow[1]];
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
            $json[$index]['numbers[]'] = $data['numbers'][$fi];
        }
        return array('spec_list' => $spec_list, 'properties_list' => $properties_list, 'list' => $list, 'json' => $json);
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
    
    public function getTotalPrice()
    {
        
        $goodsId = intval($_GET['goods_id']);
        $order_id = intval($_GET['order_id']);
        $databaseMarketGoods = D('Market_goods');
        $where = array('goods_id' => $goodsId);
        $marketGoods = $databaseMarketGoods->field(true)->where($where)->find();
        if(empty($marketGoods)){
            exit(json_encode(array('error' => true, 'msg' => '商品不存在！')));
        }
        
        $return = $databaseMarketGoods->format_spec_value($marketGoods['spec_value'], $marketGoods['goods_id']);
        $marketGoods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
        $marketGoods['list'] = isset($return['list']) ? $return['list'] : '';
        $marketGoods['discount_info'] = json_decode($marketGoods['discount_info'], true);
        $marketGoods['username'] = $this->merchant_session['name'];
        $marketGoods['userphone'] = $this->merchant_session['phone'];
        $marketGoods['address'] = '';
        $marketGoods['buy_num'] = $marketGoods['min_num'];
        
        if (IS_POST) {
            if ($marketGoods['spec_list']) {
                $buy_nums = $_POST['buy_nums'];
                
                $totalNum = 0;
                $totalPrice = 0;
                $spec_array = explode('#', $marketGoods['spec_value']);
                $target_spec_value_array = array();
                foreach ($spec_array as $str) {
                    $row_array = explode('|', $str);
                    $i = 'id_' . str_replace(':', '_id_', $row_array[0]);
                    $numArray = explode(':', $row_array[1]);
                    if ($buy_nums[$i] >= $numArray[2] && $buy_nums[$i] <= $numArray[3]) {
                        $totalNum += $buy_nums[$i];
                        $totalPrice += $buy_nums[$i] * $numArray[1];
                    }
                }
                if ($totalNum <= 0) {
                    $totalPrice = 0;
                }
            } else {
                $totalNum = isset($_POST['buy_num']) ? intval($_POST['buy_num']) : $marketGoods['min_num'];
                if ($totalNum > $marketGoods['stock_num'] || $totalNum < $marketGoods['min_num']) {
                    $totalPrice = 0;
                } else {
                    $totalPrice = $totalNum * $marketGoods['price'];
                }
            }
            
            $discount_info = null;
            foreach ($marketGoods['discount_info'] as $discount) {
                if ($discount['num'] <= $totalNum) {
                    if ($discount_info) {
                        if ($discount_info['discount'] > $discount['discount']) {
                            $discount_info = $discount;
                        }
                    } else {
                        $discount_info = $discount;
                    }
                }
            }
            $money = $totalPrice;
            if ($discount_info) {
                $money = $totalPrice * $discount_info['discount'] * 0.1;
            }
            
            exit(json_encode(array('error' => false, 'totalMoney' => $money)));
        }
    }
    
    public function changeStatus()
    {
        $goodsId = intval($_POST['id']);
        $where = array('goods_id' => $goodsId, 'mer_id' => $this->merchant_session['mer_id']);
        $status = $_POST['type'] == 'open' ? '1' : '3';
        if (M('Market_goods')->where($where)->save(array('status' => $status))) {
            exit('1');
        } else {
            exit;
        }
    }
    
    public function cancel()
    {
        $order_id = intval($_POST['order_id']);
        $databaseMarketOrder = D('Market_order');
        if ($marketOrder = $databaseMarketOrder->field(true)->where(array('order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
            $return = $databaseMarketOrder->format_spec_value($marketOrder['spec_value'], $marketOrder['goods_id'], $order_id);
            $marketOrder['list'] = isset($return['list']) ? $return['list'] : '';
            
            
            if ($marketOrder['status'] != 10 && $marketOrder['status'] != 1) {
                $this->error('商品只有在未发货之前能删除订单');
                exit();
            }
            
            //已支付的订单要回滚库存和回退余额
            if ($marketOrder['status'] == 1) {
                $databaseMarketGoods = D('Market_goods');
                $where = array('goods_id' => $marketOrder['goods_id']);
                $marketGoods = $databaseMarketGoods->field(true)->where($where)->find();
                if(empty($marketGoods)){
                    $this->error('商品信息错误', U('market'));
                    exit();
                }
                
                $marketGoodsChageData = array();
                if ($marketOrder['list']) {
                    $return = $databaseMarketGoods->format_spec_value($marketGoods['spec_value'], $marketGoods['goods_id']);
                    $marketGoods['list'] = isset($return['list']) ? $return['list'] : '';
                    $spec_array = explode('#', $marketGoods['spec_value']);
                    foreach ($marketOrder['list'] as $index => $row) {
                        $target_spec_value_array = array();
                        foreach ($spec_array as $str) {
                            $row_array = explode('|', $str);
                            $i = str_replace(':', '_', $row_array[0]);
                            if ($i == $index) {
                                $numArray = explode(':', $row_array[1]);
                                $numArray[3] = max(0, ($numArray[3] + $row['stock_num']));
                                $row_array[1] = implode(':', $numArray);
                            }
                            $target_spec_value_array[] = implode('|', $row_array);
                        }
                        $spec_array = $target_spec_value_array;
                    }
                    $marketGoodsChageData['spec_value'] = implode('#', $spec_array);
                }
                
                $marketGoodsChageData['stock_num'] = $marketGoods['stock_num'] + $marketOrder['num'];
                $marketGoodsChageData['sell_count'] = $marketGoods['sell_count'] - $marketOrder['num'];
                
                
                $data = array();
                $data['order_type'] = 'marketcancel';
                $data['desc'] = '取消在批发市场已购的商品【'.$marketOrder['name'].'】时返还的金额';
                $data['mer_id'] = $this->merchant_session['mer_id'];
                $data['money'] = $marketOrder['money'];
                $data['order_id'] = $marketOrder['order_id'];
                $data['total_money'] = $marketOrder['money'];
                D('SystemBill')->bill_method(0,$data);
                
            }
            
            D('Market_order_properties')->where(array('order_id' => $order_id))->delete();
            D('Market_order_spec')->where(array('order_id' => $order_id))->delete();
            D('Market_order_spec_value')->where(array('order_id' => $order_id))->delete();
            
            //更新父订单的总价
            if ($marketOrder['fid'] && $marketOrder['status'] == 10) {
                if ($totalData = D('Market_total')->field(true)->where(array('id' => $marketOrder['fid']))->find()) {
                    D('Market_total')->where(array('id' => $marketOrder['fid']))->save(array('money' => $totalData['money'] - $marketOrder['money']));
                }
            }
            $databaseMarketOrder->field(true)->where(array('order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id']))->delete();
            
            //更改批发市场中商品的库存
            D('Market_goods')->where(array('goods_id' => $marketOrder['goods_id']))->save($marketGoodsChageData);
            
            $this->success('删除成功');
        } else {
            $this->error('订单信息错误');
            exit();
        }
    }
    
    public function addCart()
    {
        if (IS_POST) {
            $goodsId = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
            $databaseMarketGoods = D('Market_goods');
            $where = array('goods_id' => $goodsId);
            $marketGoods = $databaseMarketGoods->field(true)->where($where)->find();
            if(empty($marketGoods)){
                $this->error('商品不存在！');
            }
            if ($marketGoods['status'] != 1) {
                $this->error('商品已下架！');
            }
            $return = $databaseMarketGoods->format_spec_value($marketGoods['spec_value'], $marketGoods['goods_id']);
            $marketGoods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
            $marketGoods['list'] = isset($return['list']) ? $return['list'] : '';
            $marketGoods['discount_info'] = json_decode($marketGoods['discount_info'], true);
            
            $totalNum = 0;
            $totalPrice = 0;
            $specArr = array();
            if ($marketCartOld = M('Market_cart')->field(true)->where(array('goods_id' => $goodsId, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
                if ($marketCartOld['spec_value'] && $marketGoods['list']) {
                    $spec_array = explode('#', $marketCartOld['spec_value']);
                    foreach ($spec_array as $str) {
                        $row_array = explode('|', $str);
                        $i = str_replace(':', '_', $row_array[0]);
//                         if (isset($marketGoods['list'][$i])) {
                        $tArray = explode(':', $row_array[1]);
                        $specArr[$i] = explode(':', $row_array[1]);
//                             $marketGoods['list'][$i]['buy_num'] = $tArray[3];
//                             $totalCount += $tArray[3];
//                             $totalMoney += $tArray[1] * $tArray[3];
//                         }
                    }
                } else {
                    $totalCount = $marketCartOld['num'];
//                     $totalMoney = $marketCartOld['num'] * $marketCartOld['price'];
                }
            }
            if ($marketGoods['spec_list']) {
                $buy_nums = $_POST['buy_nums'];
                $spec_array = explode('#', $marketGoods['spec_value']);
                $target_spec_value_array = array();
                foreach ($spec_array as $str) {
                    $row_array = explode('|', $str);
                    $i = 'id_' . str_replace(':', '_id_', $row_array[0]);
                    $ii = str_replace(':', '_', $row_array[0]);
                    if (isset($specArr[$ii]) && $specArr[$ii]) {
                        $specArr[$ii][3] += $buy_nums[$i];
                        $totalNum += $specArr[$ii][3];
                        $totalPrice += $specArr[$ii][3] * $specArr[$ii][1];
                        $row_array[1] = implode(':', $specArr[$ii]);
                        $target_spec_value_array[] = implode('|', $row_array);
                    } else {
                        $numArray = explode(':', $row_array[1]);
                        if ($buy_nums[$i] >= $numArray[2] && $buy_nums[$i] <= $numArray[3]) {
                            $totalNum += $buy_nums[$i];
                            $totalPrice += $buy_nums[$i] * $numArray[1];
                            //[new] => 规格值ID:规格值ID:...:规格值ID|old_price:price:min_num:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#
                            //规格值ID:规格值ID:...:规格值ID|old_price:price:min_num:stock_num(购入的数量):cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#
                            $numArray[3] = $buy_nums[$i];
                            $row_array[1] = implode(':', $numArray);
                            $target_spec_value_array[] = implode('|', $row_array);
                        }
                    }
                }
                
                if ($totalNum <= 0) {
                    $error_tips .= '您至少要批发一种规格且满足对应的批发条件<br/>';
                }
                
            } else {
                if ($totalNum) {
                    $totalNum += isset($_POST['buy_num']) ? intval($_POST['buy_num']) : 0;
                } else {
                    $totalNum = isset($_POST['buy_num']) ? intval($_POST['buy_num']) : $marketGoods['min_num'];
                }
                
                if ($totalNum > $marketGoods['stock_num']) {
                    $error_tips .= '该商品库存不足<br/>';
                }
                if ($totalNum < $marketGoods['min_num']) {
                    $error_tips .= '一次至少要批发' . $marketGoods['min_num'] . $marketGoods['unit'] . '<br/>';
                }
                $totalPrice = $totalNum * $marketGoods['price'];
            }
            
            if (empty($error_tips)) {
                $discount_info = null;
                foreach ($marketGoods['discount_info'] as $discount) {
                    if ($discount['num'] <= $totalNum) {
                        if ($discount_info) {
                            if ($discount_info['discount'] > $discount['discount']) {
                                $discount_info = $discount;
                            }
                        } else {
                            $discount_info = $discount;
                        }
                    }
                }
                $money = $totalPrice;
                if ($discount_info) {
                    $money = $totalPrice * $discount_info['discount'] * 0.1;
                }
                
                
                $marketCart = array('mer_id' => $this->merchant_session['mer_id'], 'last_time' => time());
                $marketCart['money'] = $money;
                $marketCart['total_price'] = $totalPrice;
                $marketCart['num'] = $totalNum;
                $marketCart['price'] = $marketGoods['price'];
                $marketCart['sell_mer_id'] = $marketGoods['mer_id'];
                $marketCart['sell_store_id'] = $marketGoods['store_id'];
//                 $marketOrder['status'] = 10;
                
                $marketCart['goods_id'] = $marketGoods['goods_id'];
                $marketCart['name'] = $marketGoods['name'];
                $marketCart['number'] = $marketGoods['number'];
                $marketCart['unit'] = $marketGoods['unit'];
                $marketCart['image'] = $marketGoods['image'];
                $marketCart['des'] = $marketGoods['des'];
                $marketCart['is_properties'] = $marketGoods['is_properties'];
                $marketCart['discount_info'] = $discount_info ? json_encode($discount_info) : '';
                
//                 $marketOrder['username'] = $username;
//                 $marketOrder['userphone'] = $userphone;
//                 $marketOrder['address'] = $address;
//                 $marketOrder['desc'] = $desc;
//                 $marketOrder['create_time'] = time();
                
                $marketCart['spec_value'] = $target_spec_value_array ? implode('#', $target_spec_value_array) : '';
                
                if ($marketCartOld) {
                    if ($cartid = M('Market_cart')->where(array('goods_id' => $goodsId, 'mer_id' => $this->merchant_session['mer_id']))->save($marketCart)) {
//                         $cartid = $marketOrderOld['cartid'];
//                         //删除已有的属性与规格的数据
//                         $where['order_id'] = $order_id;
//                         M('Market_order_properties')->where($where)->delete();
//                         $spec_list = M('Market_order_spec')->field(true)->where($where)->select();
//                         foreach ($spec_list as $spec) {
//                             M('Market_order_spec_value')->where(array('sid' => $spec['id'], 'order_id' => $order_id))->delete();
//                         }
//                         M('Market_order_spec')->where($where)->delete();
                        $this->success('加入购物车成功！', U('cart'));
                        exit;
                    } else {
                        $this->error('加入购物车失败！请重试！');
                    }
                } else {
                    $cartid = M('Market_cart')->add($marketCart);
                }
                
                if ($cartid) {
//                     if ($marketGoods['is_properties']) {
//                         $properties = M('Market_goods_properties')->field(true)->where(array('goods_id' => $marketGoods['goods_id']))->select();
//                         foreach ($properties as $pro_data) {
//                             $pro_data['cartid'] = $cartid;
//                             M('Market_cart_properties')->add($pro_data);
//                         }
//                     }
                    
//                     if ($marketGoods['spec_value']) {
//                         $spec_list = M('Market_goods_spec')->field(true)->where(array('goods_id' => $marketGoods['goods_id']))->select();
//                         foreach ($spec_list as $spec) {
//                             $spec['cartid'] = $cartid;
//                             if (M('Market_cart_spec')->add($spec)) {
//                                 $spec_value_list = M('Market_goods_spec_value')->field(true)->where(array('sid' => $spec['id']))->select();
//                                 foreach ($spec_value_list as $spec_value) {
//                                     $spec_value['cartid'] = $cartid;
//                                     M('Market_cart_spec_value')->add($spec_value);
//                                 }
//                             }
//                         }
//                     }
                    $this->success('加入购物车成功！', U('cart'));
                } else {
                    $this->error('加入购物车失败！请重试！');
                }
            } else {
                $this->error($error_tips);
            }
        }
    }
    
    private function cartCount()
    {
        $count = D('Market_cart')->where(array('mer_id' => $this->merchant_session['mer_id']))->count();
        $this->assign('count', intval($count));
    }
    
    public function cart()
    {
        $fid = isset($_GET['fid']) ? intval($_GET['fid']) : 0;
        if ($totalData = D('Market_total')->where(array('id' => $fid, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
            if ($totalData['status'] == 1) {
                $this->error('此单已支付');
            }
            $databaseMarketGoods = D('Market_order');
            $cartList = $databaseMarketGoods->where(array('mer_id' => $this->merchant_session['mer_id'], 'fid' => $fid))->select();
            
            $goodsDB = D('Market_goods');
            foreach ($cartList as $cart) {
                $gds = $goodsDB->field('status')->where(array('goods_id' => $cart['goods_id']))->find();
                if (empty($gds) || $gds['status'] != 1) {
                    $this->error('商品【' . $cart['name'] . '】已下架，此单已失效，请重新下单订购其他商品');
                }
            }
            $this->assign(array('totalMoney' => $totalData['money'], 'fid' => $fid, 'totalCount' => count($cartList)));
            $this->assign(array('username' => $cartList[0]['username'], 'userphone' => $cartList[0]['userphone'], 'address' => $cartList[0]['address'], 'desc' => $cartList[0]['desc']));
        } else {
            $databaseMarketGoods = D('Market_cart');
            $cartList = D()->query('select c.*, m.name as merchantName from ' . C('DB_PREFIX') . 'market_cart as c inner join ' . C('DB_PREFIX') . 'merchant as m on c.sell_mer_id=m.mer_id where c.mer_id=' . $this->merchant_session['mer_id']);
            $this->assign(array('username' => $this->merchant_session['name'], 'userphone' => $this->merchant_session['phone'], 'address' => '', 'desc' => ''));
        }
        $goods_image_class = new goods_image();
        $databaseMarketGoods = D('Market_cart');
        $goodsDB = D('Market_goods');
        $list = array();
        foreach ($cartList as $cart) {
            $gds = $goodsDB->field('status')->where(array('goods_id' => $cart['goods_id']))->find();
            if (empty($gds) || $gds['status'] != 1) {
                continue;
            }
            if(!empty($cart['image'])){
                $image = '';
                $tmp_pic_arr = explode(';', $cart['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    if (empty($image)) {
                        $image = $goods_image_class->get_image_by_path($value, 's');
                    }
                }
                $cart['image'] = $image;
            }
            $return = $databaseMarketGoods->format_spec_value($cart['spec_value'], $cart['goods_id'], $cart['cartid']);
            $cart['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
            $cart['list'] = isset($return['list']) ? $return['list'] : '';
            $cart['discount_info'] = json_decode($cart['discount_info'], true);
            if (isset($list[$cart['sell_mer_id']])) {
                $list[$cart['sell_mer_id']]['cartlist'][]= $cart;
            } else {
                $list[$cart['sell_mer_id']] = array('name' => $cart['merchantName'], 'cartlist' => array($cart));
            }
            
        }
        $this->assign('cartList', $list);
        $this->display();
    }
    
    public function goPay()
    {
        $fid = isset($_POST['fid']) ? intval($_POST['fid']) : 0;
        $totalMoney = 0;
        $resultList = array();
        $databaseMarketTotal = D('Market_total');
        $databaseMarketGoods = D('Market_goods');
        $databaseMarketOrder = D('Market_order');
        $databaseMarketCart = D('Market_cart');
        if ($totalData = $databaseMarketTotal->where(array('id' => $fid, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
            if ($totalData['status'] == 1) {
                $this->error('此单已支付');
            }
            $resultList = $databaseMarketOrder->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'fid' => $fid))->select();
            foreach ($resultList as $marketOrder) {
                
                $order_id = $marketOrder['order_id'];
                $return = $databaseMarketOrder->format_spec_value($marketOrder['spec_value'], $marketOrder['goods_id'], $order_id);
                $marketOrder['list'] = isset($return['list']) ? $return['list'] : '';
                
                $where = array('goods_id' => $marketOrder['goods_id']);
                $marketGoods = $databaseMarketGoods->field(true)->where($where)->find();
                if(empty($marketGoods) || $marketGoods['status'] != 1){
                    //批发市场是没有这条数据的时候删除没有支付的订单所有的数据
                    $where['order_id'] = $order_id;
                    M('Market_order_properties')->where($where)->delete();
                    $spec_list = M('Market_order_spec')->field(true)->where($where)->select();
                    foreach ($spec_list as $spec) {
                        M('Market_order_spec_value')->where(array('sid' => $spec['id'], 'order_id' => $order_id))->delete();
                    }
                    M('Market_order_spec')->where($where)->delete();
                    
                    $databaseMarketOrder->where(array('order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id']))->delete();
                    
                    //修改订单的总价
                    $databaseMarketTotal->where(array('id' => $fid, 'mer_id' => $this->merchant_session['mer_id']))->save(array('money' => $totalData['money'] - $marketOrder['money']));
                    $this->error('商品【' . $marketGoods['name'] . '】在批发市场上已下架，不能购买了', U('market'));
                    exit();
                }
                
                /**$marketGoodsChageData = array();
                if ($marketOrder['list']) {
                    $return = $databaseMarketGoods->format_spec_value($marketGoods['spec_value'], $marketGoods['goods_id']);
                    $marketGoods['list'] = isset($return['list']) ? $return['list'] : '';
                    
                    $spec_array = explode('#', $marketGoods['spec_value']);
                    
                    foreach ($marketOrder['list'] as $index => $row) {
                        $target_spec_value_array = array();
                        foreach ($spec_array as $str) {
                            $row_array = explode('|', $str);
                            $i = str_replace(':', '_', $row_array[0]);
                            if ($i == $index) {
                                $numArray = explode(':', $row_array[1]);
                                $numArray[3] = max(0, ($numArray[3] - $row['stock_num']));
                                $row_array[1] = implode(':', $numArray);
                            }
                            $target_spec_value_array[] = implode('|', $row_array);
                        }
                        $spec_array = $target_spec_value_array;
                        
                        if (isset($marketGoods['list'][$index])) {
                            if ($row['stock_num'] > $marketGoods['list'][$index]['stock_num']) {
                                $this->error('购买商品【' . $marketGoods['name'] . '】的库存不足', U('cart'));
                                exit();
                            } elseif ($row['stock_num'] < $marketGoods['list'][$index]['min_num']) {
                                $this->error('购买的商品【' . $marketGoods['name'] . '】的数量不足最小批发量', U('cart'));
                                exit();
                            }
                        } else {
                            $this->error('购买商品【' . $marketGoods['name'] . '】的已经没有这种规格了', U('cart'));
                            exit();
                        }
                    }
                    $marketGoodsChageData['spec_value'] = implode('#', $spec_array);
                } elseif ($marketOrder['num'] > $marketGoods['stock_num']) {
                    $this->error('购买商品【' . $marketGoods['name'] . '】的库存不足', U('cart'));
                    exit();
                } elseif ($marketOrder['num'] < $marketGoods['min_num']) {
                    $this->error('购买商品【' . $marketGoods['name'] . '】的数量不足最小批发量', U('cart'));
                    exit();
                }*/
            }
            $totalMoney = $totalData['money'];
        } else {
            $cartIds = isset($_POST['cartids']) ? $_POST['cartids'] : null;
            if (empty($cartIds)) {
                $this->error('请选择商品');
            }
            $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
            $userphone = isset($_POST['userphone']) ? htmlspecialchars(trim($_POST['userphone'])) : '';
            $address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : '';
            $desc = isset($_POST['desc']) ? htmlspecialchars(trim($_POST['desc'])) : '';
            if (empty($username)) {
                $this->error('收货人不能为空');
                exit;
            }
            if (empty($userphone)) {
                $this->error('联系电话不能为空');
                exit;
            }
            if (empty($address)) {
                $this->error('收货地址不能为空');
                exit;
            }
            $list = $databaseMarketCart->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'cartid' => array('in', $cartIds)))->select();
            if ($list) {
                $orderList = array();
                foreach ($list as $marketOrder) {
                    $cartid = $marketOrder['cartid'];
                    $return = $databaseMarketCart->format_spec_value($marketOrder['spec_value'], $marketOrder['goods_id'], $cartid);
                    $marketOrder['list'] = isset($return['list']) ? $return['list'] : '';
                    
                    $where = array('goods_id' => $marketOrder['goods_id']);
                    $marketGoods = $databaseMarketGoods->field(true)->where($where)->find();
                    if(empty($marketGoods) || $marketGoods['status'] != 1){
                        //$databaseMarketCart->where(array('mer_id' => $this->merchant_session['mer_id'], 'cartid' => $cartid))->delete();
                        $this->error('商品【' . $marketGoods['name'] . '】在批发市场上已下架，不能购买了', U('cart'));
                        exit();
                    }
                    if ($marketOrder['list']) {
                        $return = $databaseMarketGoods->format_spec_value($marketGoods['spec_value'], $marketGoods['goods_id']);
                        $marketGoods['list'] = isset($return['list']) ? $return['list'] : '';
                        
                        foreach ($marketOrder['list'] as $index => $row) {
                            if (isset($marketGoods['list'][$index])) {
                                if ($row['stock_num'] > $marketGoods['list'][$index]['stock_num']) {
                                    $this->error('购买商品【' . $marketGoods['name'] . '】的库存不足', U('cart'));
                                    exit();
                                } elseif ($row['stock_num'] < $marketGoods['list'][$index]['min_num']) {
                                    $this->error('购买的商品【' . $marketGoods['name'] . '】的数量不足最小批发量', U('cart'));
                                    exit();
                                }
                            } else {
                                $this->error('购买商品【' . $marketGoods['name'] . '】的已经没有这种规格了', U('cart'));
                                exit();
                            }
                        }
                        $marketGoodsChageData['spec_value'] = implode('#', $spec_array);
                    } elseif ($marketOrder['num'] > $marketGoods['stock_num']) {
                        $this->error('购买商品【' . $marketGoods['name'] . '】的库存不足', U('cart'));
                        exit();
                    } elseif ($marketOrder['num'] < $marketGoods['min_num']) {
                        $this->error('购买商品【' . $marketGoods['name'] . '】的数量不足最小批发量', U('cart'));
                        exit();
                    }
                    
                    $totalMoney += $marketOrder['money'];
                    $marketOrder['last_time'] = time();
                    $marketOrder['status'] = 10;
                    $marketOrder['username'] = $username;
                    $marketOrder['userphone'] = $userphone;
                    $marketOrder['address'] = $address;
                    $marketOrder['desc'] = $desc;
                    $marketOrder['create_time'] = time();
                    $orderList[] = $marketOrder;
                    
                }
                
                if ($orderList) {
                    //先生成父订单
                    $fid = $databaseMarketTotal->add(array('mer_id' => $this->merchant_session['mer_id'], 'status' => 0, 'money' => $totalMoney));
                    if (empty($fid)) {
                        $this->error('结算失败，稍后重试');
                    }
                    foreach ($orderList as $marketOrder) {
                        $cartid = $marketOrder['cartid'];
                        unset($marketOrder['cartid']);
                        $marketOrder['fid'] = $fid;
                        if ($order_id = $databaseMarketOrder->add($marketOrder)) {
                            $marketOrder['order_id'] = $order_id;
                            $resultList[] = $marketOrder;
                            if ($marketOrder['is_properties']) {
                                $properties = M('Market_goods_properties')->field(true)->where(array('goods_id' => $marketOrder['goods_id']))->select();
                                foreach ($properties as $pro_data) {
                                    $pro_data['order_id'] = $order_id;
                                    M('Market_order_properties')->add($pro_data);
                                }
                            }
                            
                            if ($marketOrder['spec_value']) {
                                $spec_list = M('Market_goods_spec')->field(true)->where(array('goods_id' => $marketOrder['goods_id']))->select();
                                foreach ($spec_list as $spec) {
                                    $spec['order_id'] = $order_id;
                                    if (M('Market_order_spec')->add($spec)) {
                                        $spec_value_list = M('Market_goods_spec_value')->field(true)->where(array('sid' => $spec['id']))->select();
                                        foreach ($spec_value_list as $spec_value) {
                                            $spec_value['order_id'] = $order_id;
                                            M('Market_order_spec_value')->add($spec_value);
                                        }
                                    }
                                }
                            }
                            $databaseMarketCart->where(array('cartid' => $cartid))->delete();
                        } else {
                            $this->error('下单失败！请重试！');
                        }
                    }
                }
            } else {
                $this->error('结算失败，稍后重试');
            }
        }
        $merchant = D('Merchant')->field('money, name, phone')->where(array('mer_id' => $this->merchant_session['mer_id']))->find();
        if ($totalMoney > $merchant['money']) {
            $money = floatval($totalMoney - $merchant['money']);
            if (empty($money)||$money <0 ||!is_numeric($money)) {
                $this->error('请输入正确的充值金额');
            }
            
            $data_mer_recharge_order['mer_id'] = $this->merchant_session['mer_id'];
            $data_mer_recharge_order['money'] = $money;
            $data_mer_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
            $data_mer_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
            $data_mer_recharge_order['label'] = 'web_marketorder_' . $fid;
            if ($order_id = M('Merchant_recharge_order')->data($data_mer_recharge_order)->add()) {
                redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'merrecharge')));
            } else {
                $this->error_tips('订单创建失败，请重试。');
            }
        } else {
            $returnData = D('Merchant_money_list')->use_money($this->merchant_session['mer_id'], $totalMoney, 'marketmulti', '购买批发市场的商品扣除商家余额', $fid);
            if ($returnData['error_code']) {
                $this->error('支付失败！请重试！', U('buy_order'));
                exit();
            }
            foreach ($resultList as $marketOrder) {
                $order_id = $marketOrder['order_id'];
                $return = $databaseMarketOrder->format_spec_value($marketOrder['spec_value'], $marketOrder['goods_id'], $order_id);
                $marketOrder['list'] = isset($return['list']) ? $return['list'] : '';
                
                $where = array('goods_id' => $marketOrder['goods_id']);
                $marketGoods = $databaseMarketGoods->field(true)->where($where)->find();

                
                $marketGoodsChageData = array();
                if ($marketOrder['list']) {
                    $return = $databaseMarketGoods->format_spec_value($marketGoods['spec_value'], $marketGoods['goods_id']);
                    $marketGoods['list'] = isset($return['list']) ? $return['list'] : '';
                    $spec_array = explode('#', $marketGoods['spec_value']);
                    foreach ($marketOrder['list'] as $index => $row) {
                        $target_spec_value_array = array();
                        foreach ($spec_array as $str) {
                            $row_array = explode('|', $str);
                            $i = str_replace(':', '_', $row_array[0]);
                            if ($i == $index) {
                                $numArray = explode(':', $row_array[1]);
                                $numArray[3] = max(0, ($numArray[3] - $row['stock_num']));
                                $row_array[1] = implode(':', $numArray);
                            }
                            $target_spec_value_array[] = implode('|', $row_array);
                        }
                        $spec_array = $target_spec_value_array;
                    }
                    $marketGoodsChageData['spec_value'] = implode('#', $spec_array);
                }
                
                $marketGoodsChageData['stock_num'] = max(0, ($marketGoods['stock_num'] - $marketOrder['num']));
                $marketGoodsChageData['sell_count'] = $marketGoods['sell_count'] + $marketOrder['num'];
                
                //修改订单状态
                $databaseMarketOrder->where(array('order_id' => $order_id))->save(array('pay_time' => time(), 'status' => 1));
                
                //更改批发市场中商品的库存
                $databaseMarketGoods->where(array('goods_id' => $marketOrder['goods_id']))->save($marketGoodsChageData);
                
                $sms_data = array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => 0, 'type' => 'market');
                
                $sellmerchant = M('Merchant')->field('name, phone')->where(array('mer_id' => $marketOrder['sell_mer_id']))->find();
                $sms_data['uid'] = 0;
                $sms_data['mobile'] = $sellmerchant['phone'];
                $sms_data['sendto'] = 'merchant';
                $sms_data['content'] = '商家' . $merchant['name'] . '批发了' . $marketOrder['name'] . '共' . $marketOrder['num'] . $marketOrder['unit'] . '订单号：' . $marketOrder['order_id'] . '请您注意查看并处理!';
                Sms::sendSms($sms_data);
                
                $sms_data['uid'] = 0;
                $sms_data['mobile'] = $merchant['phone'];;
                $sms_data['sendto'] = 'merchant';
                $sms_data['content'] = '您在' . $sellmerchant['name'] . '批发了' . $marketOrder['name'] . '共' . $marketOrder['num'] . $marketOrder['unit'] . '订单号：' . $marketOrder['order_id'] . '已经完成支付。欢迎下次光临!';
                Sms::sendSms($sms_data);
            }
            $databaseMarketTotal->where(array('id' => $fid))->save(array('pay_time' => time(), 'status' => 1));
            $this->success('支付成功', U('buy_order'));
        }
        
    }
    
    public function delCart()
    {
        $cartid = isset($_POST['cartid']) ? intval($_POST['cartid']) : 0;
        if ($cartid == -1) {
            D('Market_cart')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->delete();
            exit(json_encode(array('errCode' => 0, 'msg' => 'ok')));
        } else {
            if ($cart = D('Market_cart')->field(true)->where(array('cartid' => $cartid, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
                D('Market_cart')->field(true)->where(array('cartid' => $cartid, 'mer_id' => $this->merchant_session['mer_id']))->delete();
                exit(json_encode(array('errCode' => 0, 'msg' => 'ok')));
            } else {
                exit(json_encode(array('errCode' => 1, 'msg' => '参数错误')));
            }
        }
    }
}