<?php
//配送员超时未接单电话通知
class plan_deliver_check_voice_notice extends plan_base
{

	public function runTask()
	{
		$config = D('Config')->field('value')->where(array('name' => 'deliver_look_out_time'))->find();
		$noticeTime = isset($config['value']) ? intval($config['value']) : 0;
		if ($noticeTime == 0) return true;
		$time = time();
		$where['o.get_type'] = array('gt',0);
		$where['o.status'] = 2;
		$where['o.deliver_check'] = 0;
		$where['o.check_time'] = array('lt',$time);
		$orderDB=M('Deliver_supply');
		$res = $orderDB->join('as o LEFT JOIN '.C('DB_PREFIX').'deliver_user u ON o.uid=u.uid')->field('o.*,u.phone as deliver_phone')->where($where)->select();
		fdump($res,'deliver_notice');
		fdump(M(),'deliver_notice',1);
 
		foreach ($res as $row) {
			$this->keepThread();
			if ($row['deliver_phone']) {
				$phoneArr = explode(' ', $row['deliver_phone']);
				foreach ($phoneArr as $phone) {
					$sms_data = array('mer_id' => $row['mer_id'], 'store_id' => $row['store_id'], 'type' => 'shop');
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $phone;
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '您好，'.C('config.shop_notice_plat_name').'提醒您，有新的' . C('config.shop_alias_name') . '订单需要处理！'.C('config.shop_notice_plat_name').'提醒您，有新的' . C('config.shop_alias_name') . '订单需要处理！'.C('config.shop_notice_plat_name').'提醒您，有新的' . C('config.shop_alias_name') . '订单需要处理！';
					$sms_data['is_voice'] = 1;
					Sms::sendSms($sms_data);
				}
				//deliver_check  2 已通知 1已查看
				$orderDB->where(array('supply_id' => $row['supply_id']))->save(array('deliver_check' => 2));
			}
			
		}
		return true;
	}
}
?>