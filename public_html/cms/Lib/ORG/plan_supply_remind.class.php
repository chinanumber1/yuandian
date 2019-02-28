<?php
/**
 * 新的配送订单提醒
 * @author pigcms_03
 *
 */
class plan_supply_remind extends plan_base
{
    public function runTask()
    {
        $time = time() - 60;
        $supply = M('Deliver_supply')->field(true)->where(array('status' => 1, 'create_time' => array('lt', $time)))->order('supply_id DESC')->find();
        $supply && D('Deliver_supply')->sendMsg($supply);
    }

}
?>