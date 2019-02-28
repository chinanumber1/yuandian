<?php
class Deliver_supplyModel extends Model
{
    
    /**
     * @param number $order_id
     * @param array $store merchant_store 表中的数据
     * @param number $item
     * @param string $tableName
     * @return boolean|number[]|string[]|number[]|unknown[]
     */
    public function saveOrder($order_id, $store, $item = 2, $tableName = 'shop_order')
    {
        if (strtolower($tableName) == 'shop_order') {
            $shopOrderDB = M('Shop_order');
            $order = $shopOrderDB->field(true)->where(array('order_id' => $order_id))->find();
            $shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
            if (empty($shop)) return array('error_code' => 1, 'msg' => '订单所对应的店铺不存在');
            $store = array_merge($store, $shop);
        } elseif (strtolower($tableName) == 'service_user_publish') {
            if ($publish = M('Service_user_publish')->field(true)->where(array('publish_id' => $order_id))->find()) {
                if ($publish['catgory_type'] == 2) {
                    $order = M('Service_user_publish_buy')->field(true)->where(array('publish_id' => $order_id))->find();
                    $order['start_adress_name'] = $order['address'];
                    $order['start_adress_lng'] = $order['address_lng'];
                    $order['start_adress_lat'] = $order['address_lat'];
                    $order['weight_price'] = 0;
                } elseif ($publish['catgory_type'] == 3) {
                    $order = M('Service_user_publish_give')->field(true)->where(array('publish_id' => $order_id))->find();
                }
                $order['province_id'] = $publish['province_id'];
                $order['city_id'] = $publish['city_id'];
                $order['area_id'] = $publish['area_id'];
            } else {
                return false;
            }
        }
        $dada_shop_id = 0;
        $city_code = 0;
        if ($order['is_pick_in_store'] == 0 && C('config.dada_is_open') && strtolower($tableName) != 'service_user_publish') {
            $dada = new Dada();
            $longlat_class = new longlat();
            
            $areas = M('Area')->field(true)->where(array('area_id' => array('in', array($store['city_id'], $store['area_id']))))->select();
            foreach ($areas as $a) {
                if ($a['area_id'] == $store['city_id']) {
                    $city_name = $a['area_name'];
                } elseif ($a['area_id'] == $store['area_id']) {
                    $area_name = $a['area_name'];
                }
            }
            if (empty($store['dada_shop_id'])) {
                
                $city_list = S('dada_city_list');
                if (empty($city_list)) {
                    $result = $dada->cityList();
                    if ($result['status'] == 'success') {
                        foreach ($result['result'] as $c) {
                            $city_list[$c['cityName']] = $c['cityCode'];
                        }
                    } else {
                        return array('error_code' => 1, 'msg' => '达达返回的错误：' . $result['msg']);
                    }
                    S('dada_city_list', $city_list, 86400);
                }
                if (isset($city_list[$city_name])) {
                    $city_code = $city_list[$city_name];
                    
                    $shop['shop_name'] = $store['name'];//                必填            门店名称
                    $shop['shop_type'] = 1;//                  必填            业务类型(餐饮-1,商超-9,水果生鲜-13,蛋糕-21,酒品-24,鲜花-3,其他-5)
                    $shop['city_name'] = $city_name;//                  必填             城市名称(如,上海)
                    $shop['area_name'] = $area_name;//                 必填             区域名称(如,浦东新区)
                    $shop['shop_address'] = $store['adress'];//             必填             门店地址
                    $location2 = $longlat_class->baiduToGcj02($store['lat'], $store['long']);
                    $shop['shop_lng'] = $location2['lng'];//                    必填             门店经度
                    $shop['shop_lat'] = $location2['lat'];//                     必填             门店纬度
                    $shop['contact_name'] = $store['name'];//            必填              联系人姓名
                    $shop['phone'] = $store['phone'];//                        必填              联系人电话
                    
                    $result = $dada->addShop($shop);
                    if ($result['status'] == 'success') {
                        $dada_shop_id = $result['result']['successList'][0]['originShopId'];
                        M('Merchant_store')->where(array('store_id' => $store['store_id']))->save(array('dada_shop_id' => $dada_shop_id, 'dada_city_code' => $city_code));
                    } else {
                        return array('error_code' => 1, 'msg' => '达达返回的错误：' . $result['msg']);
                    }
                } else {
//                     return array('error_code' => 1, 'msg' => '达达暂没有开启【' . $city_name . '】城市的配送');
                }
            } else {
                $dada_shop_id = $store['dada_shop_id'];
                $city_code = $store['dada_city_code'];
            }
        }
        
        if ($dada_shop_id) {
            $data = array();
            $data['shop_id'] = $dada_shop_id;//达达的门店号
            $data['order_id'] = $order['real_orderid'];//
            $data['city_code'] = $city_code;
            $data['order_price'] = $order['price'];
            $data['is_prepay'] = 0;
            $data['expected_fetch_time'] = time() + 1300;//期望取货时间
            $data['expected_finish_time'] = time() + 1600;//期望送达时间
            $data['user_name'] = $order['username'];//收货人姓名
            $data['user_address'] = $order['address'];//收货人地址
            $data['user_phone'] = $order['userphone'];//收货人手机号（手机号和座机号必填一项）
            $data['user_tel'] = $order['userphone'];//收货人座机号（手机号和座机号必填一项）

            $location2 = $longlat_class->baiduToGcj02($order['lat'], $order['lng']);
            $data['user_lat'] = $location2['lat'];//收货人地址维度（高德坐标系）
            $data['user_lng'] = $location2['lng'];//收货人地址经度（高德坐标系）
            
            $result = $dada->addOrder($data);
            if ($result['status'] == 'success') {
                return array('error_code' => 0, 'msg' => '接单成功！');
            } else {
                return array('error_code' => 1, 'msg' => $result['msg']);
            }
        } else {
            $old = $this->field(true)->where(array('order_id' => $order_id, 'item' => $item))->find();
            if (empty($old)) {
                if (strtolower($tableName) == 'shop_order') {
//                     $store = M('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
//                     $shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
//                     if (empty($store)) return array('error_code' => 1, 'msg' => '订单所对应的店铺不存在');
//                     $storeDetail = array_merge($store, $shop);
                    $order_from = 0;
                    $delivery_fee = $order['freight_charge'];//原始配送费
                    if ($order['is_pick_in_store'] == 5) {//重新计算饿了么和美团的配送费
                        //计算配送距离
                        $distance = 0;
                        if (C('config.is_riding_distance')) {
                            import('@.ORG.longlat');
                            $longlat_class = new longlat();
                            $distance = $longlat_class->getRidingDistance($order['lat'], $order['lng'], $store['lat'], $store['long']);
                        }
                        $distance || $distance = getDistance($order['lat'], $order['lng'], $store['lat'], $store['long']);

                        //获取配送的优惠金额信息
                        $delivery_fee_reduce = 0;
                        $discounts = D('Shop_discount')->getDiscounts($store['mer_id'], $store['store_id']);
                        if ($d_tmp = D('Shop_goods')->getReduce($discounts, 2, $order['goods_price'])) {
                            $delivery_fee_reduce = $d_tmp['reduce_money'];
                        }
                        $order['distance'] = $distance;
                        $expect_use_time_temp = $order['create_time'];
                        
                        //平台配送的相关设置信息的处理
                        $distance = $distance / 1000;
                        $deliverReturn = D('Deliver_set')->getDeliverInfo($store, $order['price']);
                        if ($this->checkTime($expect_use_time_temp, $deliverReturn['delivertime_start'], $deliverReturn['delivertime_stop'], 1)) { //时间段一
                            $pass_distance = $distance > $deliverReturn['basic_distance'] ? floatval($distance - $deliverReturn['basic_distance']) : 0;
                            $deliverReturn['delivery_fee'] += round($pass_distance * $deliverReturn['per_km_price'], 2);
                            $deliverReturn['delivery_fee'] = $deliverReturn['delivery_fee'] - $delivery_fee_reduce;
                            $deliverReturn['delivery_fee'] = $deliverReturn['delivery_fee'] > 0 ? $deliverReturn['delivery_fee'] : 0;
                            $delivery_fee = $deliverReturn['delivery_fee'];
                        } elseif ($this->checkTime($expect_use_time_temp, $deliverReturn['delivertime_start2'], $deliverReturn['delivertime_stop2'], 2)) { //时间段二
                            $pass_distance = $distance > $deliverReturn['basic_distance2'] ? floatval($distance - $deliverReturn['basic_distance2']) : 0;
                            $deliverReturn['delivery_fee2'] += round($pass_distance * $deliverReturn['per_km_price2'], 2);
                            $deliverReturn['delivery_fee2'] = $deliverReturn['delivery_fee2'] - $delivery_fee_reduce;
                            $deliverReturn['delivery_fee2'] = $deliverReturn['delivery_fee2'] > 0 ? $deliverReturn['delivery_fee2'] : 0;
                            $delivery_fee = $deliverReturn['delivery_fee2'];
                        } elseif ($this->checkTime($expect_use_time_temp, $deliverReturn['delivertime_start3'], $deliverReturn['delivertime_stop3'], 3)) { //时间段二
                            $pass_distance = $distance > $deliverReturn['basic_distance3'] ? floatval($distance - $deliverReturn['basic_distance3']) : 0;
                            $deliverReturn['delivery_fee3'] += round($pass_distance * $deliverReturn['per_km_price3'], 2);
                            $deliverReturn['delivery_fee3'] = $deliverReturn['delivery_fee3'] - $delivery_fee_reduce;
                            $deliverReturn['delivery_fee3'] = $deliverReturn['delivery_fee3'] > 0 ? $deliverReturn['delivery_fee3'] : 0;
                            $delivery_fee = $deliverReturn['delivery_fee3'];
                        }
                       
                        switch (intval(C('config.count_freight_charge_method'))) {
                            case 0:
                                break;
                            case 1:
                                $delivery_fee = ceil($delivery_fee * 10) / 10;
                                break;
                            case 2:
                                $delivery_fee = floor($delivery_fee * 10) / 10;
                                break;
                            case 3:
                                $delivery_fee = round($delivery_fee * 10) / 10;
                                break;
                            case 4:
                                $delivery_fee = ceil($delivery_fee);
                                break;
                            case 5:
                                $delivery_fee = floor($delivery_fee);
                                break;
                            case 6:
                                $delivery_fee = round($delivery_fee);
                                break;
                        }
                        //修改原始订单表中的配送费
                        if ($delivery_fee != $order['freight_charge']) {
                            D('Shop_order')->where(array('order_id' => $order['order_id']))->save(array('freight_charge' => $delivery_fee, 'distance' => $order['distance']));
                        }
                    }
                    $order_from = $order['platform'];
                    $supply = array();
                    if (empty($order['third_id']) && $order['pay_type'] == 'offline') $order['paid'] = 0;
                    $supply['order_from'] = $order_from;//订单来源(0:快店，1:饿了么，2:美团，3：帮送，4：帮买(指定地址)),5：帮买（未指定地址）
                    $supply['order_id'] = $order_id;
                    $supply['paid'] = $order['paid'];
                    $supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
                    $supply['pay_type'] = $order['pay_type'];
                    $supply['money'] = $order['price'];
                    $supply['deliver_cash'] = round($order['price']+$order['extra_price'] - round($order['card_price'] + $order['merchant_balance'] + $order['card_give_money'] +$order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);
                    $supply['deliver_cash'] = max(0, $supply['deliver_cash']);
                    $supply['store_id'] = $store['store_id'];
                    $supply['store_name'] = $store['name'];
                    $supply['mer_id'] = $store['mer_id'];
                    $supply['from_site'] = $store['adress'];
                    $supply['from_lnt'] = $store['long'];
                    $supply['from_lat'] = $store['lat'];
                    
                    $supply['province_id'] = $store['province_id'];
                    $supply['city_id'] = $store['city_id'];
                    $supply['area_id'] = $store['area_id'];
    
                    //目的地
                    $supply['aim_site'] =  $order['address'];
                    $supply['aim_lnt'] = $order['lng'];
                    $supply['aim_lat'] = $order['lat'];
                    $supply['name']  = $order['username'];
                    $supply['phone'] = $order['userphone'];
                    $supply['fetch_number'] = isset($order['fetch_number']) ? $order['fetch_number'] : '';
    
                    $supply['status'] =  1;
                    $supply['type'] = $order['is_pick_in_store'] != 5 ? $order['is_pick_in_store'] : 0;
                    $supply['item'] = $item;//0:老快店的外卖，1：外送系统，2：新快店, 3:对接的
                    $supply['create_time'] = $_SERVER['REQUEST_TIME'];
                    $supply['order_out_time'] = $_SERVER['REQUEST_TIME'] + $shop['work_time'] * 60;//预计出单时间
                    //$supply['start_time'] = $_SERVER['REQUEST_TIME'];
                    $supply['appoint_time'] = $order['expect_use_time'];
                    $supply['note'] = $order['desc'];
                
                    $supply['order_time'] = $order['pay_time'];//订单支付时间
                    $supply['freight_charge'] = $delivery_fee;//配送费
                    
                    if ($order['distance']) {
                        $supply['distance'] = round($order['distance']/1000, 2);//配送距离
                    } else {
                        $supply['distance'] = round(getDistance($order['lat'], $order['lng'], $store['lat'], $store['long'])/1000, 2);//配送距离
                    }
                } else {
                    
                    $supply['order_id'] = $order_id;
                    $supply['paid'] = 1;
                    $supply['real_orderid'] = '';
                    $supply['pay_type'] = '';
                    $supply['money'] = $order['total_price'];
                    $supply['deliver_cash'] = 0;
                    $supply['store_id'] = 0;
                    $supply['store_name'] = '';
                    $supply['mer_id'] = 0;
                    $supply['from_site'] = $order['start_adress_name'];
                    $supply['from_lnt'] = $order['start_adress_lng'];
                    $supply['from_lat'] = $order['start_adress_lat'];
                    
                    $supply['province_id'] = isset($order['province_id']) ? $order['province_id'] : 0;
                    $supply['city_id'] = isset($order['city_id']) ? $order['city_id'] : 0;
                    $supply['area_id'] = isset($order['area_id']) ? $order['area_id'] : 0;
                    
                    //目的地
                    $supply['aim_site'] =  $order['end_adress_name'];
                    $supply['aim_lnt'] = $order['end_adress_lng'];
                    $supply['aim_lat'] = $order['end_adress_lat'];
                    $supply['name']  = $order['end_adress_nickname'];
                    $supply['phone'] = $order['end_adress_phone'];
                    $supply['fetch_number'] = '';
                    
                    $supply['status'] =  1;
                    $supply['type'] = 0;
                    $supply['item'] = 3;//0:老快店的外卖，1：外送系统，2：新快店, 3:对接的
                    $supply['create_time'] = $_SERVER['REQUEST_TIME'];
                    //$supply['start_time'] = $_SERVER['REQUEST_TIME'];
                    $supply['appoint_time'] = 0;
                    
                    $supply['note'] = isset($order['remarks']) && $order['remarks'] ? $order['remarks'] : '';
                    $supply['order_time'] = $publish['add_time'];//订单支付时间
                    $supply['freight_charge'] = floatval($order['weight_price'] + $order['distance_price'] + $order['basic_distance_price']);//配送费
                    
                    $supply['distance'] = 0;
                    if ($order['start_adress_lat'] && $order['start_adress_lng']) {
                        $supply['distance'] = round(getDistance($order['start_adress_lat'], $order['start_adress_lng'], $order['end_adress_lat'], $order['end_adress_lng'])/1000, 2);//配送距离
                    }
                    $supply['image'] = isset($order['img']) && !empty($order['img']) ? $order['img'] : '';
                    $supply['tip_price'] = $order['tip_price'];//小费
                    if ($publish['catgory_type'] == 2) {//帮我买
                        $supply['goods_name'] = isset($order['goods_remarks']) && $order['goods_remarks']? $order['goods_remarks'] : '';//商品类型或名称
                        $supply['goods_price'] = isset($order['estimate_goods_price']) ? floatval($order['estimate_goods_price']) : 0;//商品的预估费用
                        $supply['server_time'] = $order['arrival_time'];//送达时间
                        $supply['server_type'] = $order['buy_type'] == 1 ? 3 : 2;//服务类型(1:帮我送，2：帮我买（指定地址），3：帮我买（未指定地址）；
                        $supply['order_from'] = $order['buy_type'] == 1 ? 5 : 4;//订单来源(0:快店，1:饿了么，2:美团，3：帮送，4：帮买(指定地址)),5：帮买（未指定地址）
                        if ($supply['server_type'] == 3) {//帮我买，未指定地址时就已目的地地址为起始地址
                            $supply['from_site'] = $supply['aim_site'];
                            $supply['from_lnt'] = $supply['aim_lnt'];
                            $supply['from_lat'] = $supply['aim_lat'];
                        }
                    } elseif ($publish['catgory_type'] == 3) {//帮我送
                        $supply['order_from'] = 3;//订单来源(0:快店，1:饿了么，2:美团，3：帮送，4：帮买(指定地址)),5：帮买（未指定地址）
                        $supply['goods_name'] = isset($order['goods_catgory']) ? $order['goods_catgory'] : '';//商品类型或名称
                        $supply['goods_price'] = isset($order['price']) ? $order['price'] : 0;//商品的预估费用
                        $supply['goods_weight'] = $order['weight'];//商品重量
                        $supply['server_time'] = $order['fetch_time'] ? $order['fetch_time'] : '立刻取货';//取货时间
                        $supply['server_type'] = 1;//服务类型(1:帮我送，2：帮我买（指定地址），3：帮我买（未指定地址）；
                    }
                }
                
                if ($old = $this->field(true)->where(array('order_id' => $order_id, 'item' => $item))->find()) {
                    return array('error_code' => 0, 'msg' => '接单成功！');
                }
                if ($supply_id = $this->add($supply)) {
                    $supply['supply_id'] = $supply_id;
                    //推送消息提示
                    $this->sendMsg($supply);
                    return array('error_code' => 0, 'msg' => '接单成功！');
                } else {
                    return array('error_code' => 1, 'msg' => '保存订单失败');
                }
            }
            return array('error_code' => 0, 'msg' => '接单成功！');
        }
        
    }
    
    public function sendMsg($supply, $excludeUid = 0)
    {
		if(C('config.deliver_model')){
			return false;
		}
        $group = $supply['type'] + 1;
        $where = $group == 1 ? '`group`=1' : '`group`=2 AND `store_id`=' . $supply['store_id'];
        if ($group == 1) {
            $where .= " AND `is_notice`=0 AND `status`=1 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$supply['from_lat']}*PI()/180-`lat`*PI()/180)/2),2)+COS({$supply['from_lat']}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$supply['from_lnt']}*PI()/180-`lng`*PI()/180)/2),2))))<`range`";
        } else {
            $where .= " AND `is_notice`=0 AND `status`=1";
        }
        
        $users = D('Deliver_user')->field(true)->where($where)->select();
        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
        $href = C('config.site_url') . '/packapp/deliver/index.html?gopage=grab';
        foreach ($users as $user) {
            if ($excludeUid && $excludeUid == $user['uid']) {
                continue;
            }
            if ($user['client'] == 0) {
                if ($user['openid']) {
//                    $model->sendTempMsg('OPENTM406638907',
//                        array('href' => $href,
//                            'wecha_id' => $user['openid'],
//                            'first' => $user['name'] . '您好！',
//                            'keyword1' => '您有新的待抢配送订单',
//                            'keyword2' => date('Y年m月d日 H:i'),
//                            'remark' => '请您及时处理！'));

                    $model->sendTempMsg('TM00017',
                        array('href' => $href,
                            'wecha_id' => $user['openid'],
                            'first' => $user['name'] . '您好！您有新的待抢配送订单!',
                            'OrderSn' => $supply['order_id'],
                            'OrderStatus' => '待处理',
                            'remark' => '请您及时处理！'));
                }
            } else {
                $this->deliver($user);
            }
        }
    }
    
    
    private function deliver($user)
    {
        $client = intval($user['client']);	//1 IOS 2安卓
        $device_id = str_replace('-', '', $user['device_id']);
        $audience = array('tag' => array($device_id));
    
        $title = '订单提醒';
        $msg = '您有新的待处理订单';
    
        $voice_return = json_decode($this->voic_baidu(), true);
        $voice_access_token = $voice_return['access_token'];
        $voice_mp3 = 'http://tsn.baidu.com/text2audio?tex=' . $msg . '&lan=zh&tok=' . $voice_access_token . '&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';
    
        $url = C('config.site_url') . '/packapp/deliver/index.html?gopage=grab';
        $js_url = C('config.site_url') . '/packapp/deliver/grab.html';
		
		$url = str_replace('http://','https://',$url);
		$js_url = str_replace('http://','https://',$js_url);
					
        $voice_second = 6;
    
        $extra = array(
			'voice_mp3' => $voice_mp3, 
			'voice_second' => $voice_second, 
			'url' => $url,
			'js_url' => $js_url,
			'mp3_label' => 'new_deliver_order'
		);
        
        import('@.ORG.Jpush');
        $jpush = new Jpush(C('config.deliver_jpush_appkey'), C('config.deliver_jpush_secret'));
        $notification = $jpush->createBody($client, $title, $msg, $extra, 'group_sound.caf');
        $message = $jpush->createMsg($title, $msg, $extra);
        
        $columns = array();
        $columns['platform'] = $client == 1 ? array('ios') : array('android');
        $columns['audience'] = $audience;
        $columns['notification'] = $notification;
        $columns['message'] = $message;
        $columns['from'] = 'delivery';
        $plan_msg = new plan_msg();
        $plan_msg->addTask(array('type' => '4', 'content' => array($columns)));
    }
    private function voic_baidu()
    {
        static $return;

        if (empty($return)) {
            $voic_baidu = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=XU7nTWQzS9vn32bckmrhHbTu&client_secret=1375720f23eed9b7b787e728de5dd1c2";
            import('ORG.Net.Http');
            $return = Http::curlGet($voic_baidu);
        }
        return $return;
    }
    
    public function sendNotice($user, $supply)
    {
        if($user['client'] == 0){
            if($user['openid']){
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $href = C('config.site_url') . '/packapp/deliver/index.html?gopage=pick';
                $model->sendTempMsg('OPENTM405486394', array('href' => $href, 'wecha_id' => $user['openid'], 'first' => $user['name'] . '您好！', 'keyword1' => '系统分配一个配送订单给您，请及时处理。', 'keyword2' => date('Y年m月d日 H:s'), 'keyword3' => '订单号：' . $supply['real_orderid'], 'remark' => '请您及时处理！'));
            }
        }else if($user['client'] > 0){
            $client = intval($user['client']);	//1 IOS 2安卓
            $device_id = str_replace('-', '', $user['device_id']);
            $audience = array('tag' => array($device_id));
            
            $title = '订单提醒';
            // $msg = $this->config['site_name'].'：收款成功'.mt_rand(0,100).'.0'.mt_rand(1,9).'元';
            $msg = '系统分配了新订单，等待您的处理';
            
            $voice_return = json_decode($this->voic_baidu(), true);
            $voice_access_token = $voice_return['access_token'];
            $voice_mp3 = 'http://tsn.baidu.com/text2audio?tex=' . $msg . '&lan=zh&tok=' . $voice_access_token . '&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';
            
            $url = C('config.site_url') . '/packapp/deliver/index.html?gopage=pick';
            $js_url = C('config.site_url') . '/packapp/deliver/pick.html';
            
			$url = str_replace('http://','https://',$url);
			$js_url = str_replace('http://','https://',$js_url);
			
            $voice_second = 6;
            
            $extra = array(
				'voice_mp3' => $voice_mp3, 
				'voice_second' => $voice_second, 
				'url' => $url,
				'js_url' => $js_url,
				'mp3_label' => 'new_system_deliver_order'
			);
            
            import('@.ORG.Jpush');
            $jpush = new Jpush(C('config.deliver_jpush_appkey'), C('config.deliver_jpush_secret'));
            $notification = $jpush->createBody($client, $title, $msg, $extra, 'group_sound.caf');
            $message = $jpush->createMsg($title, $msg, $extra);
            
            $columns = array();
            $columns['platform'] = $client == 1 ? array('ios') : array('android');
            $columns['audience'] = $audience;
            $columns['notification'] = $notification;
            $columns['message'] = $message;
            $columns['from'] = 'delivery';
            $plan_msg = new plan_msg();
            $plan_msg->addTask(array('type' => '4', 'content' => array($columns)));
        }
    }
    
    public function updateStatusToZero($id)
    {
        $this->where(array('item' => 3, 'order_id' => $id))->save(array('status' => 0));
    }

    private function checkTime($time, $start_time, $stop_time, $select)
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
    
//     private function getReduce($discounts, $type, $price, $store_id = 0, $is_discount = 1)
//     {
//         $reduce_money = 0;
//         $return = null;
//         if (isset($discounts[$store_id])) {
//             foreach ($discounts[$store_id] as $row) {
//                 if ($row['type'] == $type && ($row['is_share'] || $is_discount == 0)) {
//                     if ($price >= $row['full_money']) {
//                         if ($reduce_money < $row['reduce_money']) {
//                             $reduce_money = $row['reduce_money'];
//                             $return = $row;
//                         }
//                     }
//                 }
//             }
//         }
//         return $return;
//     }
}