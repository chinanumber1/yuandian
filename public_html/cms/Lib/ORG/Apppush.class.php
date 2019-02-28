<?php
class Apppush
{
	public function send($order, $order_type)
	{
		$login_log = D("Appapi_app_login_log")->field(true)->where(array('uid' => $order['uid']))->order("create_time DESC")->find();

		if (empty($login_log)) return false;
		$client = $login_log['client'];
		if ($client == 1) {
			$device_id = str_replace('-', '', $login_log['device_id']);
			$audience = array('tag' => array($device_id));
		} else {
			$audience = array('tag' => array($login_log['device_id']));
		}
		$notification = $message = '';
		$jpush = new Jpush();
		switch ($order_type) {
			case 'shop':
				$status = array('下单成功', '支付成功', '店员已接单', '配送员已接单', '配送员已取货' , '配送员配送中', '配送结束', '店员验证消费', '完成评论', '已完成退款', '已取消订单', '商家分配自提点', '商家发货到自提点', '自提点已接货', '自提点已发货', '您在自提完成取货', 30 => '店员修改价格');

				$extra = array('pigcms_tag' => 'shop_order', 'tag_desc' => $order['real_orderid'], 'url' => C('config.site_url') . '/wap.php?c=Shop&a=status&order_id=' . $order['order_id']);
				$title = C('config.shop_alias_name') . '订单状态变更提醒';
				$msg = C('config.shop_alias_name') . '订单状态修改成【' . $status[$order['status']] . '】，更多信息请查看详情！';
				$notification = $jpush->createBody(3, $title, $msg, $extra);
				$message = $jpush->createMsg($title, $msg, $extra);
				break;
            case 'group':
                $status = array(0=>'下单成功',1=> '支付成功',  2=>'完成评论',3=>'拼团成功', 4=>'已完成退款', 5=>'已取消订单',6=>'店员已发货' , 7=>'店员验证消费',8=>'开团成功',9=>'参团成功');
                $extra = array('pigcms_tag' => 'group_order', 'tag_desc' => $order['real_orderid'], 'url' => C('config.site_url') . '/wap.php?c=My&a=group_order&order_id=' . $order['order_id']);
                $title = C('config.group_alias_name') . '订单状态变更提醒';
                $msg = C('config.group_alias_name') . '订单状态修改成【' . $status[$order['status']] . '】，更多信息请查看详情！';
                $notification = $jpush->createBody(3, $title, $msg, $extra);
                $message = $jpush->createMsg($title, $msg, $extra);
                break;
            case 'publish':
                $status = array(2=>'订单支付成功',4=> '订单已完成',  8=>'配送员已接单', 9=>'配送员送货中');
                $catgory_type = array(2=>'帮我买', 3=>'帮我送');
                $extra = array('pigcms_tag' => 'publish_order', 'tag_desc' => $order['order_sn'], 'url' => C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $order['order_id']);
                $title = $catgory_type[$order['catgory_type']] . '订单状态变更提醒';
                $msg = $catgory_type[$order['catgory_type']] . '订单状态修改成【' . $status[$order['status']] . '】，更多信息请查看详情！';
                $notification = $jpush->createBody(3, $title, $msg, $extra);
                $message = $jpush->createMsg($title, $msg, $extra);
                break;
		}

		$columns = array();
		$columns['platform'] = $client == 1 ? array('ios') : array('android');
		$columns['audience'] = $audience;
		$columns['notification'] = $notification;
		$columns['message'] = $message;
		$plan_msg = new plan_msg();
		$plan_msg->addTask(array('type' => '4', 'content' => array($columns)));
		return ;
	}
}