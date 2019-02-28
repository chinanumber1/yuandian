<?php
class Merchant_store_staffModel extends Model
{
	public function sendMsgGroupOrder($now_order){
		$staffs = D('Merchant_store_staff')->field(true)->where(array('store_id' => $now_order['store_id'], 'is_notice' => 0, 'last_time'=>array('neq',0)))->select();
		$jpush = new Jpush();
		$href = C('config.site_url') . '/packapp/storestaff/index.html?gopage=group_list';
		$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
		foreach ($staffs as $staff) {
			if($staff['client'] == 0 && $staff['openid']){
//                $model->sendTempMsg('OPENTM406638907',
//                    array('href' => $href,
//                        'wecha_id' => $staff['openid'],
//                        'first' => $staff['name'] . '您好！',
//                        'keyword1' => C('config.group_alias_name') . '新订单提醒',
//                        'keyword2' => date('Y年m月d日 H:i'),
//                        'remark' => '请您及时处理！'),
//                    $now_order['mer_id']);
			    // 团购新订单-提醒店员
				$model->sendTempMsg('TM00017',
                    array('href' => $href,
                        'wecha_id' => $staff['openid'],
                        'first' => $staff['name'] . '您好！' . C('config.group_alias_name') . '新订单提醒！',
                        'OrderSn' => $now_order['real_orderid'],
                        'OrderStatus' => '待接单',
                        'remark' => '请您及时处理！'),
                    $now_order['mer_id']);
			}else if($staff['client'] > 0){
				if (! C('config.staff_jpush_appkey')) continue;
				if ($staff['device_id']) {
					$client = $staff['client'];
					$device_id = str_replace('-', '', $staff['device_id']);
					$audience = array('tag' => array($device_id));
					
					$notification = $message = '';
					
					$title = '订单提醒';
					$msg = C('config.group_alias_name') . '新订单提醒，请及时查看！';
					
					$voice_return = json_decode($this->voic_baidu(), true);
					$voice_access_token = $voice_return['access_token'];
					$voice_mp3 = 'http://tsn.baidu.com/text2audio?tex=' . $msg . '&lan=zh&tok=' . $voice_access_token . '&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';
				
					$url = C('config.site_url') . '/packapp/storestaff/index.html?gopage=group_list';
					$js_url = C('config.site_url') . '/packapp/storestaff/group_list.html';
					
					$url = str_replace('http://','https://',$url);
					$js_url = str_replace('http://','https://',$js_url);
					
					$voice_second = 6;
					
					$extra = array(
						'pigcms_tag' => 'group_order',
                        'tag_desc' => $now_order['real_orderid'],
						'voice_mp3' => $voice_mp3,
						'voice_second' => $voice_second, 
						'url' => $url,
						'js_url' => $js_url,
						'mp3_label' => 'new_group_order'.substr(md5($this->config['site_url']),0,16)
					);
					
					$notification = $jpush->createBody($client, $title, $msg, $extra, 'group_sound.caf');
					$message = $jpush->createMsg($title, $msg, $extra);
					
					$columns = array();
					$columns['platform'] = $client == 1 ? array('ios') : array('android');
					$columns['audience'] = $audience;
					$columns['notification'] = $notification;
					$columns['message'] = $message;
					$columns['from'] = 'storestaff';
					$plan_msg = new plan_msg();
					$plan_msg->addTask(array('type' => '4', 'content' => array($columns)));
				}
			}
		}
	}
	public function sendMsgStoreOrder($now_order){
		$staffs = D('Merchant_store_staff')->field(true)->where(array('store_id' => $now_order['store_id'], 'is_notice' => 0, 'last_time'=>array('neq',0)))->select();
		$jpush = new Jpush();
		if($now_order['from_plat'] == 2 || $now_order['from_plat'] == 3){
			$href = C('config.site_url') . '/packapp/storestaff/index.html?gopage=cashier';
		}else{
			$href = C('config.site_url') . '/packapp/storestaff/index.html?gopage=store';
		}
		$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
		foreach ($staffs as $staff) {
			if($now_order['from_plat'] == 2 || $now_order['from_plat'] == 3){
				$msg = '店员收银收款成功'.floatval($now_order['price']).'元';
			}else{
				$msg = C('config.cash_alias_name') . '收款成功'.floatval($now_order['price']).'元';
			}
			if($now_order['ticketNum']){
				$msg.= '，含'.$now_order['ticketNum'].'张票';
				if(floatval($now_order['ticketInsure'])){
					$msg.= '，含保险';
				}else{
					if(C('config.store_ticket_have')){
						$database_store_trade_ticket = D('Merchant_store_trade_ticket');
						$condition_store_trade_ticket = array('store_id'=>$now_order['store_id']);
						$store_trade_ticket = $database_store_trade_ticket->where($condition_store_trade_ticket)->find();
						if($store_trade_ticket['have_insure']){
							$msg.= '，不含保险';
						}
					}				
				}
				
			}
			
			if($staff['client'] == 0 && $staff['openid']){
//				$model->sendTempMsg('OPENTM406638907',
//                    array('href' => $href,
//                        'wecha_id' => $staff['openid'],
//                        'first' => $staff['name'] . '您好！',
//                        'keyword1' => $msg,
//                        'keyword2' => date('Y年m月d日 H:i'),
//                        'remark' => '请您及时处理！'),$now_order['mer_id']);
                // 模板-提醒店员
                $model->sendTempMsg('TM00017',
                    array('href' => $href,
                        'wecha_id' => $staff['openid'],
                        'first' => $staff['name'] . '您好！' . $msg,
                        'OrderSn' => $now_order['orderid'],
                        'OrderStatus' => '待处理',
                        'remark' => '请您及时处理！'),
                    $now_order['mer_id']);
			}else if($staff['client'] > 0){
				if (! C('config.staff_jpush_appkey')) continue;
				if ($staff['device_id']) {
					$client = $staff['client'];
					$device_id = str_replace('-', '', $staff['device_id']);
					$audience = array('tag' => array($device_id));
					
					$notification = $message = '';
					
					$title = '订单提醒';

					$voice_return = json_decode($this->voic_baidu(), true);
					$voice_access_token = $voice_return['access_token'];
					$voice_mp3 = 'http://tsn.baidu.com/text2audio?tex=' . $msg . '&lan=zh&tok=' . $voice_access_token . '&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';
					
					if($now_order['from_plat'] == 2 || $now_order['from_plat'] == 3){
						$url = C('config.site_url') . '/packapp/storestaff/index.html?gopage=cashier';
						$js_url = C('config.site_url') . '/packapp/storestaff/cashier.html';
					}else{
						$url = C('config.site_url') . '/packapp/storestaff/index.html?gopage=store';
						$js_url = C('config.site_url') . '/packapp/storestaff/store.html';
					}
					
					$url = str_replace('http://','https://',$url);
					$js_url = str_replace('http://','https://',$js_url);
					
					$voice_second = 6;
					
					$extra = array(
						'pigcms_tag' => 'store_order_'.$now_order['from_plat'],
                        'tag_desc' => $now_order['real_orderid'],
						'voice_mp3' => $voice_mp3,
						'voice_second' => $voice_second, 
						'url' => $url,
						'js_url' => $js_url
					);
					
					$notification = $jpush->createBody($client, $title, $msg, $extra, 'store_pay.caf');
					$message = $jpush->createMsg($title, $msg, $extra);
					
					$columns = array();
					$columns['platform'] = $client == 1 ? array('ios') : array('android');
					$columns['audience'] = $audience;
					$columns['notification'] = $notification;
					$columns['message'] = $message;
					$columns['from'] = 'storestaff';
					$plan_msg = new plan_msg();
					$plan_msg->addTask(array('type' => '4', 'content' => array($columns)));
				}
			}
		}
	}

	public function sendMsgAppointOrder($now_order){
		$staffs = D('Merchant_store_staff')->field(true)->where(array('store_id' => $now_order['store_id'], 'is_notice' => 0, 'last_time'=>array('neq',0)))->select();
		$jpush = new Jpush();
		$href = C('config.site_url') . '/packapp/storestaff/index.html?gopage=appoint';
		$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
		foreach ($staffs as $staff) {
			if($staff['client'] == 0 && $staff['openid']){
//				$model->sendTempMsg('OPENTM406638907',
//                    array('href' => $href,
//                        'wecha_id' => $staff['openid'],
//                        'first' => $staff['name'] . '您好！',
//                        'keyword1' => C('config.appoint_alias_name') . '新订单提醒' ,
//                        'keyword2' => date('Y年m月d日 H:i'),
//                        'remark' => '请您及时处理！'),
//                    $now_order['mer_id']);
                $model->sendTempMsg('TM00017',
                    array('href' => $href,
                        'wecha_id' => $staff['openid'],
                        'first' => $staff['name'] . '您好！' . C('config.appoint_alias_name') . '新订单提醒！',
                        'OrderSn' => $now_order['orderid'],
                        'OrderStatus' => '待处理',
                        'remark' => '请您及时处理！'),
                    $now_order['mer_id']);
			}else if($staff['client'] > 0){
				if (! C('config.staff_jpush_appkey')) continue;
				if ($staff['device_id']) {
					$client = $staff['client'];
					$device_id = str_replace('-', '', $staff['device_id']);
					$audience = array('tag' => array($device_id));

					$notification = $message = '';

					$title = C('config.appoint_alias_name') . '新订单提醒';
					$msg = C('config.appoint_alias_name') . '新订单提醒，请及时查看！';

					$voice_return = json_decode($this->voic_baidu(), true);
					$voice_access_token = $voice_return['access_token'];
					$voice_mp3 = 'http://tsn.baidu.com/text2audio?tex=' . $msg . '&lan=zh&tok=' . $voice_access_token . '&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';

					$url = C('config.site_url') . '/packapp/storestaff/index.html?gopage=appoint';
					$js_url = C('config.site_url') . '/packapp/storestaff/appoint.html';
					
					$url = str_replace('http://','https://',$url);
					$js_url = str_replace('http://','https://',$js_url);
					
					$voice_second = 6;

					$extra = array(
						'pigcms_tag' => 'appoint_order',
						'tag_desc' => $now_order['order_id'],
						'voice_mp3' => $voice_mp3,
						'voice_second' => $voice_second,
						'url' => $url,
						'js_url' => $js_url,
						'mp3_label' => 'new_appoint_order'.substr(md5($this->config['site_url']),0,16)
					);

					$notification = $jpush->createBody($client, $title, $msg, $extra,  'sound.caf');
					$message = $jpush->createMsg($title, $msg, $extra);

					$columns = array();
					$columns['platform'] = $client == 1 ? array('ios') : array('android');
					$columns['audience'] = $audience;
					$columns['notification'] = $notification;
					$columns['message'] = $message;
					$columns['from'] = 'storestaff';
					$plan_msg = new plan_msg();
					$plan_msg->addTask(array('type' => '4', 'content' => array($columns)));
				}
			}
		}
	}

    public function sendMsg($storeId,$order=array())
    {
        $staffs = $this->field(true)->where(array('store_id' => $storeId, 'is_notice' => 0, 'last_time'=>array('neq',0)))->select();
        $jpush = new Jpush();
        $href = C('config.site_url') . '/packapp/storestaff/index.html';
        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
        if ($order['real_orderid']) {
            $real_orderid = $order['real_orderid'];
        } else {
            $real_orderid = '';
        }
        foreach ($staffs as $staff) {
            if ($staff['client'] == 0 && $staff['openid']) {
//                $model->sendTempMsg('OPENTM406638907',
//                    array('href' => $href, 'wecha_id' => $staff['openid'],
//                        'first' => $staff['name'] . '您好！',
//                        'keyword1' => C('config.shop_alias_name') . '新订单提醒',
//                        'keyword2' => date('Y年m月d日 H:i'),
//                        'remark' => '请您及时处理！'),
//                    $order['mer_id']);
                $model->sendTempMsg('TM00017',
                    array('href' => $href,
                        'wecha_id' => $staff['openid'],
                        'first' => $staff['name'] . '您好！' . C('config.shop_alias_name') . '新订单提醒！',
                        'OrderSn' => $real_orderid,
                        'OrderStatus' => '待处理',
                        'remark' => '请您及时处理！'),
                    $order['mer_id']);
            } elseif($staff['client'] > 0){
                if (! C('config.staff_jpush_appkey')) continue;
                if ($staff['device_id']) {
                    $client = $staff['client'];
                    if ($client == 1) {
                        $device_id = str_replace('-', '', $staff['device_id']);
                        $audience = array('tag' => array($device_id));
                    } else {
                        $audience = array('tag' => array($staff['device_id']));
                    }
                    $notification = $message = '';
                    
                    $title = C('config.shop_alias_name') . '新订单提醒';
                    $msg = C('config.shop_alias_name') . '新订单提醒，请及时查看！';
                    
                    $voice_return = json_decode($this->voic_baidu(), true);
                    $voice_access_token = $voice_return['access_token'];
                    $voice_mp3 = 'http://tsn.baidu.com/text2audio?tex=' . $msg . '&lan=zh&tok=' . $voice_access_token . '&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';
                    
                    $url = C('config.site_url') . '/packapp/storestaff/index.html?gopage=shop';
                    $js_url = C('config.site_url') . '/packapp/storestaff/shop.html';
					
					$url = str_replace('http://','https://',$url);
					$js_url = str_replace('http://','https://',$js_url);
					
                    $voice_second = 6;
                    
                    $extra = array(
                        'pigcms_tag' => 'shop_order',
                        'tag_desc' => $order['real_orderid'],
                        'voice_mp3' => $voice_mp3,
                        'voice_second' => $voice_second,
                        'url' => $url,
                        'js_url' => $js_url,
						'mp3_label' => 'new_shop_order'.substr(md5($msg),8,16).substr(md5($this->config['site_url']),8,16)
                    );
                    
                    $notification = $jpush->createBody($client, $title, $msg, $extra, 'group_sound.caf');
                    $message = $jpush->createMsg($title, $msg, $extra);
                    
                    $columns = array();
                    $columns['platform'] = $client == 1 ? array('ios') : array('android');
                    $columns['audience'] = $audience;
                    $columns['notification'] = $notification;
                    $columns['message'] = $message;
                    $columns['from'] = 'storestaff';
                    $plan_msg = new plan_msg();
                    $plan_msg->addTask(array('type' => '4', 'content' => array($columns)));
                }
            }
        }
    }
    
    
    public function sendMsgFoodShop($order)
    {
        $staffs = array();
        if ($order['table_id']) {
            $table = D('Foodshop_table')->field(true)->where(array('id' => $order['table_id']))->find();
            if ($table && $table['staff_id']) {
                $staffs = $this->field(true)->where(array('id' => $table['staff_id']))->select();
            }
        }
        if (empty($staffs)) {
            $staffs = $this->field(true)->where(array('store_id' => $order['store_id'], 'is_notice' => 0, 'last_time'=>array('neq',0)))->select();
        }
        
        $jpush = new Jpush();
        if ($order['status'] == 100) {
            $href = C('config.site_url') . '/packapp/storestaff/foodshop_order_list.html?status=1';
            $title = '客人呼叫服务';
            $msg = '【' . $table['name']. '】的客人呼叫服务，请您及时提供服务';
			$mp3_label = 'new_foodshop_order_hujiaofuwu_'.substr(md5($table['name']),0,16).substr(md5(C('config.site_url')),0,16);
        } elseif ($order['status'] == 1) {
            $href = C('config.site_url') . '/packapp/storestaff/foodshop_order_list.html?status=1';
            $title = C('config.meal_alias_name') . '新订单提醒';
            $msg = C('config.meal_alias_name') . '新订单提醒，请及时查看！';
            $mp3_label = 'new_foodshop_order_neworder_'.substr(md5(C('config.site_url')),0,16);
        } elseif ($order['status'] == 3) {
            $href = C('config.site_url') . '/packapp/storestaff/foodshop_order_list.html?status=3';
            $title = C('config.meal_alias_name') .'订单支付成功';
            $msg = C('config.meal_alias_name') . '订单支付成功，请及处理！';
            $mp3_label = 'new_foodshop_order_neworder_'.substr(md5(C('config.site_url')),0,16);
        }else {
            $href = C('config.site_url') . '/packapp/storestaff/foodshop_order_list.html?status=4';
            $title = C('config.meal_alias_name') . '上菜提醒';
            $msg = C('config.meal_alias_name') . '上菜提醒，请及时确认菜品！';
			$mp3_label = 'new_foodshop_order_shangcaitixing_'.substr(md5(C('config.site_url')),0,16);
        }
        
        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
        foreach ($staffs as $staff) {
            if ($staff['client'] == 0 && $staff['openid']) {
//                $model->sendTempMsg('OPENTM406638907',
//                    array('href' => $href,
//                        'wecha_id' => $staff['openid'],
//                        'first' => $staff['name'] . '您好！',
//                        'keyword1' => $title,
//                        'keyword2' => date('Y年m月d日 H:i'),
//                        'remark' => $msg),
//                    $order['mer_id']);
                $model->sendTempMsg('TM00017',
                    array('href' => $href,
                        'wecha_id' => $staff['openid'],
                        'first' => $staff['name'] . '您好！' . $title,
                        'OrderSn' => $order['real_orderid'],
                        'OrderStatus' => '待处理',
                        'remark' => '请您及时处理！'),
                    $order['mer_id']);
            } elseif($staff['client'] > 0){
                if (! C('config.staff_jpush_appkey')) continue;
                if ($staff['device_id']) {
                    $client = $staff['client'];
                    if ($client == 1) {
                        $device_id = str_replace('-', '', $staff['device_id']);
                        $audience = array('tag' => array($device_id));
                    } else {
                        $audience = array('tag' => array($staff['device_id']));
                    }
                    $notification = $message = '';
                    
                    
                    
                    $voice_return = json_decode($this->voic_baidu(), true);
                    $voice_access_token = $voice_return['access_token'];
                    $voice_mp3 = 'http://tsn.baidu.com/text2audio?tex=' . $msg . '&lan=zh&tok=' . $voice_access_token . '&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';
                    
                    $url = C('config.site_url') . '/packapp/storestaff/index.html?gopage=foodshop';
                    $js_url = $href;
                    
					$url = str_replace('http://','https://',$url);
					$js_url = str_replace('http://','https://',$js_url);
					
                    $voice_second = 6;
                    
                    $extra = array(
                        'pigcms_tag' => 'foodshop_order',
                        'tag_desc' => $order['real_orderid'],
                        'voice_mp3' => $voice_mp3,
                        'voice_second' => $voice_second,
                        'url' => $url,
                        'js_url' => $js_url,
						'mp3_label' => $mp3_label ? $mp3_label : 'new_foodshop_order_new'.substr(md5(C('config.site_url')),0,16)
                    );
                    
                    $notification = $jpush->createBody($client, $title, $msg, $extra, 'sound.caf');
                    $message = $jpush->createMsg($title, $msg, $extra);
                    
                    $columns = array();
                    $columns['platform'] = $client == 1 ? array('ios') : array('android');
                    $columns['audience'] = $audience;
                    $columns['notification'] = $notification;
                    $columns['message'] = $message;
                    $columns['from'] = 'storestaff';
                    $plan_msg = new plan_msg();
                    $plan_msg->addTask(array('type' => '4', 'content' => array($columns)));
                }
            }
        }
    }
	
	private function voic_baidu()
    {
        static $return;

        if (empty($return)) {
            $voic_baidu = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=XU7nTWQzS9vn32bckmrhHbTu&client_secret=1375720f23eed9b7b787e728de5dd1c2";
            import('ORG.Net.Http');
            $return = Http::curlGet($voic_baidu);
        }
        return $return;
    }
}
?>