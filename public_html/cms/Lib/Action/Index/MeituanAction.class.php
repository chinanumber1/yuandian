<?php
class MeituanAction extends BaseAction
{
    public function index()
    {
        $data = $_REQUEST;
        $appAuthToken = isset($data['appAuthToken']) ? $data['appAuthToken'] : '';
        $storeId = isset($data['ePoiId']) ? intval($data['ePoiId']) : 0;
        if ($store = M('Merchant_store')->field(true)->where(array('store_id' => $storeId))->find()) {
            M('Merchant_store')->where(array('store_id' => $storeId))->save(array('meituan_token' => $appAuthToken));
        }
        echo json_encode(array('data' => 'success'));
    }
    
    public function cancelBind()
    {
        $data = $_REQUEST;
        $storeId = isset($data['ePoiId']) ? intval($data['ePoiId']) : 0;
        if ($store = M('Merchant_store')->field(true)->where(array('store_id' => $storeId))->find()) {
            M('Merchant_store')->where(array('store_id' => $storeId))->save(array('meituan_token' => ''));
        }
        echo json_encode(array('data' => 'success'));
    }
    
    public function order()
    {
        $content = $_REQUEST;
        if (empty($content)) {
            exit(json_encode(array('data' => 'OK')));
        }
        $storeId = $content['ePoiId'];
        $sign = $content['sign'];
        unset($content['sign']);
        $mySign = $this->generate_signature($content);
        if ($sign != $mySign) {
            exit(json_encode(array('data' => 'OK')));
        }
        if (empty($content['order'])) {
            exit(json_encode(array('data' => 'OK')));
        }
        $store = M('Merchant_store')->field(true)->where(array('store_id' => $storeId))->find();
        if (empty($store)) {
            exit(json_encode(array('data' => 'OK')));
        }
        $data = json_decode($content['order'], true);
        if (empty($data)) exit(json_encode(array('data' => 'OK')));
        
        $where = array('platform_id' => $data['orderId'], 'platform' => 2, 'store_id' => $storeId);
        if ($oldOrder = M('Shop_order')->field(true)->where($where)->find()) {
            $order = array();
            if ($data['status'] < 4) {
                $order['status'] = 0;
            } elseif ($data['status'] == 4 || $data['status'] == 4) {
                $order['status'] = 1;
            } elseif ($data['status'] == 8) {
                $order['third_id'] = $data['orderId'];
                $order['status'] = 2;
            } elseif ($data['status'] = 9) {
                $order['status'] = 5;
            }
            if ($order) {
                $order['last_time'] = $data['utime'];
                M('Shop_order')->where($where)->save($order);
            }
            exit(json_encode(array('data' => 'OK')));
        } else {
            $order = array();
            $order['store_id'] = $storeId;
            $order['mer_id'] = $store['mer_id'];
            $order['create_time'] = $data['ctime'];
            $order['last_time'] = $data['utime'];
            $order['pay_time'] = $data['utime'];
            $order['expect_use_time'] = $data['deliveryTime'];
            
            $order['real_orderid'] = $data['orderIdView'];
            $order['platform_id'] = $data['orderId'];
            $order['platform'] = 2;
            $order['order_from'] = 8;
            $order['store_id'] = $data['ePoiId'];
            $order['desc'] = $data['caution'];
            $order['lat'] = $data['latitude'];
            $order['lng'] = $data['longitude'];
            $order['invoice_head'] = $data['invoiceTitle'];
            
            $order['username'] = $data['recipientName'];
            $order['userphone'] = $data['recipientPhone'];
            $order['address'] = $data['recipientAddress'];
            $order['price'] = $data['total'];
            $order['total_price'] = $data['originalPrice'];
            $order['freight_charge'] = $data['shippingFee'];
            
            $order['fetch_number'] = $data['daySeq'];
            $order['pay_type'] = $data['payType'] == 1 ? 'offline' : '';
            $order['paid'] = 1;
            if ($data['payType'] == 2) {
                $order['third_id'] = $data['orderId'];
                $order['payment_money'] = $data['total'];
            }
            if ($data['logisticsCode'] == '0000' && 1 == C('config.is_deliver_meituan')) {//商家自送
                $order['is_pick_in_store'] = 5;//美团外卖的商家自送转化成平台配送
            }
            if ($data['status'] < 4) {
                $order['status'] = 0;
            } elseif ($data['status'] == 4 || $data['status'] == 4) {
                $order['status'] = 1;
            } elseif ($data['status'] == 8) {
                $order['status'] = 2;
            } elseif ($data['status'] = 9) {
                $order['status'] = 5;
            }
            
            if ($order_id = D('Shop_order')->add($order)) {
                $shopOrderDetail = D('Shop_order_detail');
                $data['detail'] = json_decode($data['detail'], true);
                foreach ($data['detail'] as $detail) {
                    $orderDetail = array();
                    $orderDetail['store_id'] = $content['ePoiId'];
                    $orderDetail['order_id'] = $order_id;
                    $orderDetail['name'] = $detail['food_name'];
                    $orderDetail['price'] = $detail['price'];
                    $orderDetail['num'] = $detail['quantity'];
                    $orderDetail['unit'] = $detail['unit'];
                    $orderDetail['discount_rate'] = floatval($detail['food_discount']) * 100;
                    $orderDetail['spec'] = $detail['food_property'];
                    $orderDetail['packing_charge'] = $detail['box_price'];
                    $orderDetail['packname'] = intval($detail['cart_id'] + 1) . '号口袋';
                    $shopOrderDetail->add($orderDetail);
                }
                D('Merchant_store_staff')->sendMsg($storeId);
                echo json_encode(array('data' => 'OK'));
            }
        }
    }
    
    public function orderCancel()
    {
        $content = $_REQUEST;
        
        $storeId = $content['ePoiId'];
        
        $sign = $content['sign'];
        unset($content['sign']);
        
        $mySign = $this->generate_signature($content);
        if ($sign != $mySign) {
            exit(json_encode(array('data' => 'OK')));
        }
        if (empty($content['orderCancel'])) {
            exit(json_encode(array('data' => 'OK')));
        }
        $store = M('Merchant_store')->field(true)->where(array('store_id' => $storeId))->find();
        if (empty($store)) {
            exit(json_encode(array('data' => 'OK')));
        }
        $data = json_decode($content['orderCancel'], true);
        if (empty($data)) exit(json_encode(array('data' => 'OK')));
        
        $where = array('platform_id' => $data['orderId'], 'platform' => 2, 'store_id' => $storeId);
        if ($oldOrder = M('Shop_order')->field(true)->where($where)->find()) {
            $savaData = array('status' => 5);
            $savaData['cancel_reason'] = $data['reason'];
            $reasonCode[1001] = '系统取消，超时未确认';
            $reasonCode[1002] = '系统取消，在线支付订单30分钟未支付';
            $reasonCode[1101] = '用户取消，在线支付中取消';
            $reasonCode[1102] = '用户取消，商家确认前取消';
            $reasonCode[1103] = '用户取消，用户退款取消';
            $reasonCode[1201] = '客服取消，用户下错单';
            $reasonCode[1202] = '客服取消，用户测试';
            $reasonCode[1203] = '客服取消，重复订单';
            $reasonCode[1204] = '客服取消，其他原因';
            $reasonCode[1301] = '其他原因';
            $reasonCode[2001] = '商家超时接单【商家取消时填写】';
            $reasonCode[2002] = '非顾客原因修改订单';
            $reasonCode[2003] = '非客户原因取消订单';
            $reasonCode[2004] = '配送延迟';
            $reasonCode[2005] = '售后投诉';
            $reasonCode[2006] = '用户要求取消';
            $reasonCode[2007] = '其他原因（未传code，默认为此）';
            $reasonCode[2008] = '店铺太忙';
            $reasonCode[2009] = '商品已售完';
            $reasonCode[2010] = '地址无法配送';
            $reasonCode[2011] = '店铺已打烊';
            $reasonCode[2012] = '联系不上用户';
            $reasonCode[2013] = '重复订单';
            $reasonCode[2014] = '配送员取餐慢';
            $reasonCode[2015] = '配送员送餐慢';
            $reasonCode[2016] = '配送员丢餐、少餐、餐洒';
            
            //$savaData['cancel_reason'] = $data['reason'] . ':' . $data['reasonCode'];
            M('Shop_order')->where($where)->save($savaData);
        }
        exit(json_encode(array('data' => 'OK')));
    }
    
    public function orderRefund()
    {
        $content = $_REQUEST;
        
        $storeId = $content['ePoiId'];
        
        $sign = $content['sign'];
        unset($content['sign']);
        
        $mySign = $this->generate_signature($content);
        
        if ($sign != $mySign) {
            exit(json_encode(array('data' => 'OK')));
        }
        if (empty($content['orderRefund'])) {
            exit(json_encode(array('data' => 'OK')));
        }
        $store = M('Merchant_store')->field(true)->where(array('store_id' => $storeId))->find();
        if (empty($store)) {
            exit(json_encode(array('data' => 'OK')));
        }
        $data = json_decode($content['orderRefund'], true);
        if (empty($data)) exit(json_encode(array('data' => 'OK')));
        
        $where = array('platform_id' => $data['orderId'], 'platform' => 2, 'store_id' => $storeId);
        if ($oldOrder = M('Shop_order')->field(true)->where($where)->find()) {
            
            $savaData = array('status' => 4);
            $savaData['cancel_reason'] = $data['reason'];
            
            $notifyType['apply'] = '发起退款';
            $notifyType['agree'] = '确认退款';
            $notifyType['reject'] = '驳回退款';
            $notifyType['cancelRefund'] = '用户取消退款申请';
            $notifyType['cancelRefundComplaint'] = '取消退款申诉';
            //$savaData['cancel_reason'] = $data['reason'] . ':' . $data['notifyType'];
            M('Shop_order')->where($where)->save($savaData);
        }
        exit(json_encode(array('data' => 'OK')));
    }
    
    public function orderRefund()
    {
        $content = $_REQUEST;
        
        $storeId = $content['ePoiId'];
        
        $sign = $content['sign'];
        unset($content['sign']);
        
        $mySign = $this->generate_signature($content);
        
        if ($sign != $mySign) {
            exit(json_encode(array('data' => 'OK')));
        }
        if (empty($content['orderRefund'])) {
            exit(json_encode(array('data' => 'OK')));
        }
        $store = M('Merchant_store')->field(true)->where(array('store_id' => $storeId))->find();
        if (empty($store)) {
            exit(json_encode(array('data' => 'OK')));
        }
        $data = json_decode($content['orderRefund'], true);
        if (empty($data)) exit(json_encode(array('data' => 'OK')));
        
        $where = array('platform_id' => $data['orderId'], 'platform' => 2, 'store_id' => $storeId);
        if ($oldOrder = M('Shop_order')->field(true)->where($where)->find()) {
            
            $savaData = array('status' => 4);
            $savaData['cancel_reason'] = $data['reason'];
            
            $notifyType['apply'] = '发起退款';
            $notifyType['agree'] = '确认退款';
            $notifyType['reject'] = '驳回退款';
            $notifyType['cancelRefund'] = '用户取消退款申请';
            $notifyType['cancelRefundComplaint'] = '取消退款申诉';
            //$savaData['cancel_reason'] = $data['reason'] . ':' . $data['notifyType'];
            M('Shop_order')->where($where)->save($savaData);
        }
        exit(json_encode(array('data' => 'OK')));
    }
    
    public function privacy()
    {
        $content = $_REQUEST;
        
        $sign = $content['sign'];
        unset($content['sign']);
        
        $mySign = $this->generate_signature($content);
        
        if ($sign == $mySign) {
            exit(json_encode(array('data' => 'OK')));
        }
        exit(json_encode(array('data' => 'NO')));
    }
    private function generate_signature($params)
    {
        $string = '';
        ksort($params);
        foreach ($params as $key => $value) {
            $value && $string .= $key . $value;
        }
        $string = C('config.meituan_sign_key') . $string;
        return strtolower(sha1($string));
    }
}