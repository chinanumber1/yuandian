<?php
class plan_worker_msg extends plan_base{
    public function runTask(){
        $condition_table = array(C('DB_PREFIX') . 'appoint'=>'a' , C('DB_PREFIX') . 'appoint_order'=>'o', C('DB_PREFIX') .'appoint_visit_order_info'=>'i', C('DB_PREFIX') .'merchant_workers'=>'w');
        $condition_where = '`o`.`order_id` = `i`.`appoint_order_id` AND `a`.`appoint_id` = `o`.`appoint_id` AND `o`.`service_status`=0 AND ((`o`.`payment_status`=1 AND `o`.`paid`=1) OR (`o`.`paid`=0 AND `o`.`payment_status`=0)) AND '.time().' > (UNIX_TIMESTAMP(CONCAT_WS(" ",appoint_date,appoint_time) )- '. C("config.worker_before_time") * 60 .')  AND (`i`.`merchant_worker_id` = `w`.`merchant_worker_id`) AND (`o`.`is_sms_worker`=0)';
        $condition_fields = array('`o`.`uid`,`o`.`order_id`,`w`.`mobile`,`a`.`appoint_name`,`o`.`appoint_time`,`o`.`appoint_date`,`o`.`mer_id`');

        $orders = M('')->table($condition_table)->where($condition_where)->limit(1)->order('`o`.`order_id` desc')->field($condition_fields)->select();
        $sms_data = array('type' => 'appoint');

        foreach ($orders as $order){
            $this->keepThread();
            $sms_data['mer_id'] = $order['mer_id'];
            $sms_data['store_id'] = $order['store_id'];
            $sms_data['uid'] = $order['uid'];
            $sms_data['mobile'] = $order['mobile'];
            $sms_data['sendto'] = 'user';
            $sms_data['content'] = '技师服务前提醒：您将在' . ceil((strtotime($order['appoint_date'] . ' ' . $order['appoint_time'] )- time())/60) .'分钟后为客户进行预约服务，请准备！如有疑问，可致电：'.$order['mobile'];
            Sms::sendSms($sms_data);

            D('Appoint_order')->where(array('order_id'=>$order['order_id']))->setField('is_sms_worker',1);
        }
        return true;
    }
}
?>