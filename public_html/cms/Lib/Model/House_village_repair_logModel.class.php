<?php
class House_village_repair_logModel extends Model
{
	
	/**
	 * status
	 * 0:业主发起
	 * 1:物业接受分配给工作人员
	 * 2:工作人员接受
	 * 3:工作人员处理
	 * 4:用户评价
	 */
	
	public function add_log($param)
	{
		if (empty($param['repair_id'])) return false;
		if ($order = D('House_village_repair_list')->field(true)->where(array('pigcms_id' => $param['repair_id']))->find()) {
			$data = array('dateline' => time());
			$data['repair_id'] = intval($param['repair_id']);
			$data['status'] = isset($param['status']) ? intval($param['status']) : 0;
			$data['worker_phone'] = isset($param['phone']) ? $param['phone'] : '';
			$data['worker_name'] = isset($param['name']) ? $param['name'] : '';

			if ($data['status'] == 0 || $data['status'] == 4) {
				$this->send_work($order, $data['status']);
				$this->send_system($order, $data['status']);
			} elseif ($data['status'] == 1) {
				$this->send_work($order, $data['status']);
				$this->send_user($order, $data['status']);
			} else {
				$this->send_user($order, $data['status']);
				$this->send_system($order, $data['status']);
			}
			return $this->add($data);
		}
		
	}
	
	private function send_work($order, $status)
	{
		if ($village = D('House_village')->field(true)->where(array('village_id' => $order['village_id']))->find()) {
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			
			if ($order['type'] == 1) {
				$first = '物业报修工单';
				$type = 1;
			} elseif ($order['type'] == 2) {
				$first = '水电煤工单';
				$type = 0;
			} else {
				$first = '投诉建议工单';
				$type = 0;
			}

			if ($status == 0) {
				if ($village['handle_type']) {
					$href = C('config.site_url').'/wap.php?c=Worker&a=detail&wxscan=1&pigcms_id=' . $order['pigcms_id'];
					$workers = D('House_worker')->field(true)->where(array('status' => 1, 'village_id' => $order['village_id'], 'type' => $type))->select();
					foreach ($workers as $worker) {
						$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $worker['openid'], 'first' => '物业工单', 'work' => '您有一个' . $first . '需处理', 'remark' => date('Y-m-d H:i:s')));
					}
				}
			} else {
				$href = C('config.site_url').'/wap.php?c=Worker&a=detail&wxscan=1&pigcms_id=' . $order['pigcms_id'];
				$worker = D('House_worker')->field(true)->where(array('status' => 1, 'village_id' => $order['village_id'], 'wid' => $order['wid']))->find();
				$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $worker['openid'], 'first' => '物业工单', 'work' => '您有一个' . $first . '需处理', 'remark' => date('Y-m-d H:i:s')));
			}
		}
	}
	
	private function send_system($order, $status)
	{
		if ($village = D('House_village')->field(true)->where(array('village_id' => $order['village_id']))->find()) {
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));

			if ($order['type'] == 1) {
				$first = '物业报修工单';
				$type = 1;
			} elseif ($order['type'] == 2) {
				$first = '水电煤工单';
				$type = 0;
			} else {
				$first = '投诉建议工单';
				$type = 0;
			}
			if ($status == 0) {
				if ($village['handle_type'] == 0) {
					$href = C('config.site_url').'/wap.php?c=Customer&a=detail&pigcms_id=' . $order['pigcms_id'];
					$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $village['openid'], 'first' => '物业工单', 'work' => '您有一个' . $first . '需处理', 'remark' => date('Y-m-d H:i:s')));
				}
			} else {
				$status_all = array('下单成功', '物业已受理', '处理人员已受理', '处理人员完成', '业主已评价');
				$href = C('config.site_url').'/wap.php?c=Customer&a=detail&pigcms_id=' . $order['pigcms_id'];
				$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $village['openid'], 'first' => '物业工单', 'work' => $first . '工单现在的状态已经被' . $status_all[$status], 'remark' => date('Y-m-d H:i:s')));
			}
		}
	}
	private function send_user($order, $status)
	{
		
		$now_user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
		if ($now_user['openid']) {
			$status_all = array('下单成功', '物业已受理', '处理人员已受理', '处理完成', '已评价');
			if ($order['type'] == 1) {
				$first = '报修';
				$href = C('config.site_url').'/wap.php?c=House&a=village_my_repair_detail&village_id='. $order['village_id'] . '&id=' . $order['pigcms_id'];
			} elseif ($order['type'] == 2) {
				$first = '水电煤';
				$href = C('config.site_url').'/wap.php?c=House&a=village_my_utilitieslists&village_id='. $order['village_id'] . '&id=' . $order['pigcms_id'];
			} else {
				$first = '投诉建议';
				$href = C('config.site_url').'/wap.php?c=House&a=village_my_suggest_detail&village_id='. $order['village_id'] . '&id=' . $order['pigcms_id'];
			}
			
			$worker = D('House_worker')->field(true)->where(array('status' => 1, 'village_id' => $order['village_id'], 'wid' => $order['wid']))->find();
			if ($status == 1) {
				$remark = '已经分配给:' . $worker['name'] . ',' . $worker['phone'] . '请您耐心等待！';
			} elseif ($status == 2) {
				$remark = '工作人员:' . $worker['name'] . ',' . $worker['phone'] . '已经接单，'. $order['msg'] .'请您耐心等待！';
			} elseif ($status == 3) {
				$remark = '工作人员:' . $worker['name'] . ',' . $worker['phone'] . '已处理，期待您对本次任务的评价！';
			}
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $first . '订单', 'OrderSn' => $order['pigcms_id'], 'OrderStatus' => $status_all[$status], 'remark' => $remark));
		}
	}
}