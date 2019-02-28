<?php
//超时未接单电话通知
class plan_shop_order_voice_notice extends plan_base
{
	
	public function runTask()
	{
		$config = D('Config')->field('value')->where(array('name' => 'shop_order_voice_notice'))->find();
		$noticeTime = isset($config['value']) ? intval($config['value']) : 0;
		if ($noticeTime == 0) return true;
		$noticeTime = $noticeTime * 60;
		
		$time = time();
		$sql = "SELECT o.mer_id, o.store_id, o.order_id,s.voice_phone FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS s ON s.store_id=o.store_id";
		$sql .= " WHERE o.paid=1 AND o.status=0 AND o.voice_notice=0 AND o.pay_time>1510535971 AND {$time}-o.pay_time>{$noticeTime}";
		$res = D()->query($sql);
		$orderDB = D('Shop_order');
		foreach ($res as $row) {
			$this->keepThread();
			if ($row['voice_phone']) {
			    $phoneArr = explode(' ', $row['voice_phone']);
			    foreach ($phoneArr as $phone) {
    			    $sms_data = array('mer_id' => $row['mer_id'], 'store_id' => $row['store_id'], 'type' => 'shop');
    			    $sms_data['uid'] = 0;
    			    $sms_data['mobile'] = $phone;
    			    $sms_data['sendto'] = 'merchant';
    			    $sms_data['content'] = '您好，'.C('config.shop_notice_plat_name').'提醒您，有新的' . C('config.shop_alias_name') . '订单需要处理！'.C('config.shop_notice_plat_name').'提醒您，有新的' . C('config.shop_alias_name') . '订单需要处理！'.C('config.shop_notice_plat_name').'提醒您，有新的' . C('config.shop_alias_name') . '订单需要处理！';
    			    $sms_data['is_voice'] = 1;
    			    Sms::sendSms($sms_data);
			    }
			}
			$orderDB->where(array('order_id' => $row['order_id']))->save(array('voice_notice' => 1));
		}
		return true;
	}
}
?>