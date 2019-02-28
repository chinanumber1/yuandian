<?php
class Shop_order_logModel extends Model
{

    public function add_log($param)
    {
        if (empty($param['order_id']))
            return false;
        if ($order = D('Shop_order')->field(true)->where(array('order_id' => $param['order_id']))->find()) {
            // 状态(0：用户下单，1：用户支付，2：店员接单，3：配送员接单，4：配送员取货，5：配送员送达，6：配送结束，7：店员验证消费，8：用户完成评论，9用户退款，10用户取消订单)
            $data = array('dateline' => time());
            $data['order_id'] = intval($param['order_id']);
            $data['status'] = isset($param['status']) ? intval($param['status']) : 0;
            $data['phone'] = isset($param['phone']) ? $param['phone'] : '';
            $data['name'] = isset($param['name']) ? $param['name'] : '';
            $data['note'] = isset($param['note']) ? $param['note'] : '';
            $data['from_type'] = isset($param['from_type']) ? intval($param['from_type']) : 0;
            if ($order['order_from'] == 6 && $data['status'] > 1) return false;
            if ($data['status'] == 9) {
                D('Deliver_supply')->where(array('order_id' => $data['order_id'], 'item' => 2))->save(array('status' => 0));
            }
            if ($order['cartid'] && ($orderTemp = D('Shop_order_temp')->field(true)->where(array('cartid' => $order['cartid']))->find())) {
                //0:拼单中，1:拼单已锁单
                switch (intval($data['status'])) {
                    case 1:
                        if ($orderTemp['status'] < 2) {//2支付成功等待接单
                            D('Shop_order_temp')->where(array('cartid' => $order['cartid']))->save(array('status' => 2));
                        }
                        break;
                    case 2:
                        if ($orderTemp['status'] < 3) {//3订单已接单在配送中
                            D('Shop_order_temp')->where(array('cartid' => $order['cartid']))->save(array('status' => 3));
                        }
                        break;
                    case 6:
                    case 7:
                        if ($orderTemp['status'] < 4) {//4订单完成
                            D('Shop_order_temp')->where(array('cartid' => $order['cartid']))->save(array('status' => 4));
                        }
                        break;
                    case 9:
                        if ($orderTemp['status'] < 5) {//5订单退款
                            D('Shop_order_temp')->where(array('cartid' => $order['cartid']))->save(array('status' => 5));
                        }
                        break;
                    case 10:
                        if ($orderTemp['status'] < 6) {//6订单取消
                            D('Shop_order_temp')->where(array('cartid' => $order['cartid']))->save(array('status' => 6));
                        }
                        break;
                }
                
            }
            $now_user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
            if ($now_user['openid'] && $data['status'] != 0 && $data['status'] != 1 && $order['order_from'] != 6) {
                $status = array(
                    '下单成功',
                    '支付成功',
                    '店员已接单',
                    '配送员已接单',
                    '配送员已取货',
                    '配送员配送中',
                    '配送结束',
                    '店员验证消费',
                    '完成评论',
                    '已完成退款',
                    '已取消订单',
                    '商家分配自提点',
                    '商家发货到自提点',
                    '自提点已接货',
                    '自提点已发货',
                    '您在自提完成取货',
                    30 => '店员修改价格',
                    31 => '由于配送员暂时无法配送，已将该订单重新分配到配送员抢单池，请耐心等待其他配送员抢单'
                );
                $href = C('config.site_url') . '/wap.php?c=Shop&a=status&order_id=' . $order['order_id'] . '&mer_id=' . $order['mer_id'] . '&store_id=' . $order['store_id'];
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $model->sendTempMsg('TM00017', array(
                    'href' => $href,
                    'wecha_id' => $now_user['openid'],
                    'first' => C('config.shop_alias_name') . '订单',
                    'OrderSn' => $order['real_orderid'],
                    'OrderStatus' => $status[$data['status']],
                    'remark' => date('Y-m-d H:i:s')
                ), $order['mer_id']);
            }
            if ($data['status'] != 0) {
                import('@.ORG.Apppush');
                $order['status'] = $data['status'];
                $apppush = new Apppush();
                $apppush->send($order, 'shop');
            }
            
            if (intval($data['status']) == 1 && intval($order['paid']) == 1) {
                D('Merchant_store_staff')->sendMsg($order['store_id'],$order);
            }
            
            if (intval($data['status']) == 2 && $order['platform'] == 0) {
                //店员接单的时候打印，只打印本平台的单子，饿了么，美团的单子不打印
                $printHaddle = new PrintHaddle();
                $printHaddle->printit($order['order_id'], 'shop_order', 5);
            }
            return $this->add($data);
        }
    }
}
?>