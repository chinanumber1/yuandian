<?php
/**
 * 平台给商家的折扣记录
 * @author pigcms_03
 *
 */
class Merchant_discount_logModel extends Model
{
    public function saveLog($order, $order_from = 0)
    {
        $data = array();
        $data['order_from'] = $order_from;
        $data['dateline'] = time();
        $data['mer_id'] = $order['mer_id'];
        $data['store_id'] = $order['store_id'];
        $data['order_id'] = $order['order_id'];
        $data['real_orderid'] = $order['real_orderid'];
        $data['discount'] = $order['plat_discount'];
        $data['discount_money'] = $order['plat_discount_price'];
        $data['price'] = $order['price'];
        if ($this->add($data)) {
            D('Merchant')->setDiscountNumInc($order['mer_id'], $order['plat_discount_price']);
            return true;
        }
        return false;
    }
}