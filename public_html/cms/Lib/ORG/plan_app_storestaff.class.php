<?php
class plan_app_storestaff extends plan_base{
	public function runTask(){
		$where = "last_time<>0 AND last_time<".(time()-120) ." AND device_id<>''";
		$staffs = M('Merchant_store_staff')->field('id,client,device_id,last_time')->where($where)->select();
		$groups = array();
		foreach ($staffs as $staff){
			$this->keepThread();
			$client = $staff['client'];
			if ($client == '1') {
				$device_id = str_replace('-', '', $staff['device_id']);
				$audience = array('tag' => array($device_id));
			} else {
				$audience = array('tag' => array($staff['device_id']));
			}
			$extra = array('wake'=>1);
			
			$title = 'wake_storestaff';
			$msg = '正在运行';
			import('@.ORG.Jpush');
			$jpush = new Jpush(C('config.staff_jpush_appkey'), C('config.staff_jpush_secret'));
			$notification = $jpush->createBody($client, $title, $msg, $extra);
			$message = $jpush->createMsg($title, $msg, $extra);
			$msg = $jpush->send("all", $audience, $notification, $message);
			//if($msg['status']){
				M('Merchant_store_staff')->where(array('id'=>$staff['id']))->setField('last_time',0);
			//}
			
		}

		return true;
	}
}
?>