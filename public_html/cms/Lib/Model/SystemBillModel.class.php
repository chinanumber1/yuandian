<?php
// 商家余额
class SystemBillModel extends Model{
   //调用不同的方法
    /*
     * @param type 对账类型  0 平台跟商家 1自有支付 2 平台很商家子商户 3 平台跟店铺子商户 4 平台跟社区 5 平台跟社区子商户
     * */
    public function bill_method($type,$order_info){
        switch($type){
            case 0:
            case 1:
            case 2:
                $res = D('Merchant_money_list')->add_money($order_info);
                break;
            case 3:
                $res =  D('Store_money_list')->add_money($order_info);
                break;
            case 4:
            case 5:
                $res =  D('Village_money_list')->add_money($order_info);
                break;
        }

        return $res;
    }
}