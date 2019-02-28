<?php
class ElemeAction extends BaseAction
{
    public function index()
    {
        $code = isset($_GET["code"]) ? $_GET["code"] : '';
        if (empty($code)) {
            $this->error('授权出错了', C('config.site_url'));
            exit;
        }
        
        import('@.ORG.Eleme');
        $eleme = new Eleme();
        $tokens = $eleme->getTokenByCode($code);
        if (isset($tokens['code']) && $tokens['code']) {
            $this->error($tokens['message'], C('config.site_url'));
            exit;
        }
        
        $eleme->setToken($tokens['access_token']);
        
        
        $shop = $eleme->getDataByApi("eleme.user.getUser");
        
        $elemeShopData = array('access_token' => $tokens['access_token']);
        $elemeShopData['refresh_token'] = $tokens['refresh_token'];
        $elemeShopData['expires_in'] = $tokens['expires_in'] + time();
        $elemeShopDB = D('Eleme_shop');
        if (empty($shop['error'])) {
            $data = $shop['result'];
            $elemeShopData['userId'] = $data['userId'];
            
            foreach ($data['authorizedShops'] as $val) {
                $elemeShopData['shopId'] = $val['id'];
                $elemeShopData['name'] = $val['name'];
                if ($tShop = $elemeShopDB->field(true)->where(array('userId' => $data['userId'], 'shopId' => $val['id']))->find()) {
                    $elemeShopDB->where(array('id' => $tShop['id']))->save($elemeShopData);
                } else {
                    $elemeShopDB->add($elemeShopData);
                }
            }
            $this->success('授权成功', C('config.site_url'));
        } else {
            $this->error($shop['error']['code'] . $shop['error']['message'], C('config.site_url'));
        }
        
    }
    
    
    public function sendMsg()
    {
        $content = file_get_contents("php://input");
        if ($content == null) {
            exit(json_encode(array('message' => 'ok')));
        }
        $message = json_decode($content, true, 512, JSON_BIGINT_AS_STRING);

        fdump_sql($message,'ele_message');
        $params = $message;
        $signature = $message["signature"];
        unset($params["signature"]);
        ksort($params);
        $string = "";
        foreach ($params as $key => $value) {
            $string .= $key . "=" . $value;
        }
        $splice = $string . C('config.eleme_app_secret');
        $md5 = strtoupper(md5($splice));
        
        if ($signature != $md5) {
            return false;
        }
        $oData = json_decode($message['message'], true, 512, JSON_BIGINT_AS_STRING);

        $eleme_shopId = $message['shopId'];
        $store = M('Merchant_store')->field(true)->where(array('eleme_shopId' => $eleme_shopId))->find();
        $store_shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store['store_id']))->find();
        $storeId = isset($store['store_id']) ? $store['store_id'] : 0;
        $merId = isset($store['mer_id']) ? $store['mer_id'] : 0;
        switch ($message["type"]) {
            case 10://订单生效
                if ($shopOrderData = M('Shop_order')->field(true)->where(array('platform_id' => $oData['orderId']))->find()) {
                    M('Shop_order')->where(array('order_id' => $shopOrderData['order_id']))->save(array('status' => 0, 'last_time' => time()));
                    exit(json_encode(array('message' => 'ok')));
                } else {
                    $order = array();
                    if (1 == C('config.is_deliver_eleme')) {
                        $eleme= D('Eleme_shop')->getElemeObj($storeId);
                        $result = $eleme->getDataByApi("eleme.packs.getEffectServicePackContract", array('shopId' => $eleme_shopId));
                        fdump_sql($result,'ele_message');
                        if (isset($result['result']['contractTypeName']) && (
                                ($result['result']['contractTypeName'] == '免费服务'
                                    || $result['result']['contractTypeName'] == 'e基础'
                                    || $result['result']['contractTypeName'] == 'E基础'
                                    || $result['result']['contractTypeName'] == '星火计划KA')
                                || ($store_shop['third_send_to_sys']==1 && strpos($result['result']['contractTypeName'],'蜂鸟')===false) )) {

                            $order['is_pick_in_store'] = 5;//饿了么外卖的商家自送转化成平台配送
                        }
                    }
                    $order['store_id'] = $storeId;
                    $order['mer_id'] = $merId;
                    $order['create_time'] = strtotime($oData['createdAt']);
                    $order['last_time'] = strtotime($oData['activeAt']);
                    $order['pay_time'] = strtotime($oData['activeAt']);
                    $order['expect_use_time'] = $oData['deliverTime'] ? strtotime($oData['deliverTime']) : 0;
                    $order['real_orderid'] = $oData['orderId'];
                    $order['platform_id'] = $oData['orderId'];
                    $order['platform'] = 1;
                    $order['order_from'] = 7;
                    $order['desc'] = $oData['description'] ? $oData['description'] : '';
                    $lngLat = explode(',', $oData['deliveryGeo']);
                    
                    $order['lat'] = $lngLat[1];
                    $order['lng'] = $lngLat[0];
                    $longlat = new longlat();
                    $latLngData = $longlat->gpsToBaidu($lngLat[1], $lngLat[0]);
                    if (isset($latLngData['lat']) && isset($latLngData['lng']) && $latLngData['lat'] > 0 && $latLngData['lng'] > 0) {
                        $order['lat'] = $latLngData['lat'];
                        $order['lng'] = $latLngData['lng'];
                    }
                    
                    
                    $order['invoice_head'] = $oData['invoice'] ? $oData['invoice'] : '';
                    
                    $order['username'] = $oData['consignee'];
                    $order['userphone'] = $oData['phoneList'][0];
                    $order['address'] = $oData['deliveryPoiAddress'];
                    $order['price'] = $oData['totalPrice'];
                    
                    $order['total_price'] = $oData['originalPrice'];
                    $order['freight_charge'] = $oData['deliverFee'];
                    $order['packing_charge'] = $oData['packageFee'];
                    
                    $order['fetch_number'] = $oData['daySn'];
                    $order['pay_type'] = empty($oData['onlinePaid']) ? 'offline' : '';
                    $order['paid'] = 1;
                    if ($data['onlinePaid']) {
                        $order['third_id'] = $oData['orderId'];
                        $order['payment_money'] = $oData['totalPrice'];
                    }
                    
                    $statusArr['pending'] = '未生效订单';
                    $statusArr['unprocessed'] = '未处理订单';
                    $statusArr['refunding'] = '退单处理中';
                    $statusArr['valid'] = '已处理的有效订单';
                    $statusArr['invalid'] = '无效订单';
                    $statusArr['settled'] = '已完成订单';
                    
                    if ($oData['status'] == 'pending') {
                        
                    } elseif ($oData['status'] == 'unprocessed') {
                        $order['status'] = 0;
                    } elseif ($oData['status'] == 'refunding') {
                        
                    } elseif ($oData['status'] == 'valid') {
                        $order['status'] = 1;
                    } elseif ($oData['status'] == 'invalid') {
                        
                    } elseif ($oData['status'] == 'settled') {
                        $order['status'] = 2;
                    }
                    if ($order_id = D('Shop_order')->add($order)) {
                        $shopOrderDetail = D('Shop_order_detail');
                        foreach ($oData['groups'] as $detail) {
                            foreach ($detail['items'] as $item) {
                                $orderDetail = array();
                                $orderDetail['packname'] = $detail['name'] ? $detail['name'] : '';
                                $orderDetail['store_id'] = $storeId;
                                $orderDetail['order_id'] = $order_id;
                                $orderDetail['name'] = $item['name'];
                                $orderDetail['price'] = floatval($item['price']);
                                $orderDetail['num'] = intval($item['quantity']);
                                $orderDetail['number'] = $item['barCode'] ? $item['barCode'] : '';
                                
                                foreach ($item['newSpecs'] as $v) {
                                    $spec[] = $v['value'];
                                }
                                foreach ($item['attributes'] as $v) {
                                    $spec[] = $v['value'];
                                }
                                
                                $orderDetail['spec'] = $spec ? implode(',', $spec) : '';
                                $shopOrderDetail->add($orderDetail);
                            }
                        }
                        D('Merchant_store_staff')->sendMsg($storeId);
                    }
                }
                exit(json_encode(array('message' => 'ok')));
                break;
            case 12://商户接单
                if ($shopOrderData = M('Shop_order')->field(true)->where(array('platform_id' => $oData['orderId']))->find()) {
                    M('Shop_order')->where(array('order_id' => $shopOrderData['order_id']))->save(array('status' => 1, 'last_time' => $oData['updateTime']));
                    $store = M('Merchant_store')->field(true)->where(array('store_id' => $shopOrderData['store_id']))->find();
//                     $shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $shopOrderData['store_id']))->find();
//                     $store = array_merge($store, $shop);
                    if ($shopOrderData['is_pick_in_store'] == 5) {
                        $result = D('Deliver_supply')->saveOrder($shopOrderData['order_id'], $store);
                    }
//                     if ($result['error_code']) {
//                         D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $order['store_id']))->save(array('status' => 0, 'last_time' => time()));
//                         $this->error($result['msg']);
//                         exit;
//                     }
                }
                exit(json_encode(array('message' => 'ok')));
                break;
            case 18://订单完结
                if ($shopOrderData = M('Shop_order')->field(true)->where(array('platform_id' => $oData['orderId']))->find()) {
                    M('Shop_order')->where(array('order_id' => $shopOrderData['order_id']))->save(array('status' => 2, 'last_time' => $oData['updateTime']));
                }
                exit(json_encode(array('message' => 'ok')));
                break;
            case 14://订单被取消
            case 15://订单置为无效
            case 17://订单强制无效
            case 23://商户同意取消单
            case 25://客服仲裁取消单申请有效
            case 33://商户同意退单
            case 35://客服仲裁退单有效
//                 case 57:
//                 case 58:
                if ($shopOrderData = M('Shop_order')->field(true)->where(array('platform_id' => $oData['orderId']))->find()) {
                    M('Shop_order')->where(array('order_id' => $shopOrderData['order_id']))->save(array('status' => 4, 'last_time' => $oData['updateTime']));
                    D('Deliver_supply')->where(array('order_id' => $shopOrderData['order_id'], 'item' => 2))->save(array('status' => 0));
                }
                exit(json_encode(array('message' => 'ok')));
                break;
        }
        exit(json_encode(array('message' => 'ok')));
    }
}