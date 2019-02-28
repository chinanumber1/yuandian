<?php
//超时强制接单
class plan_supply_order extends plan_base
{
	
	public function runTask()
	{
		$now_time = time();
		$configs = D('Config')->field('name, value')->where(array('name' => array('in', array('deliver_timeout', 'deliver_timeout2', 'delivery_time', 'delivery_time2'))))->select();
		$config = array();
		foreach ($configs as $val) {
		    $config[$val['name']] = $val['value'];
		}
		$deliver_timeout = 60 * intval($config['deliver_timeout']);//C('config.deliver_timeout');
		$deliver_timeout2 = 60 * intval($config['deliver_timeout2']);
		if (empty($deliver_timeout)) return true;
		
		$dateList = array();
		$dateList2 = array();
		if ($deliver_time = $config['delivery_time']) {
			$delivery_times = explode('-', $deliver_time);
			$start_time = $delivery_times[0] . ':00';
			$stop_time = $delivery_times[1] . ':00';
			$start_time = strtotime(date('Y-m-d' . ' ' . $start_time));
			$stop_time = strtotime(date('Y-m-d' . ' ' . $stop_time));
			if ($start_time > $stop_time) {
			    $dateList[] = array('s' => strtotime(date('Y-m-d')), 'e' => $stop_time);
			    $stop_time += 86400;
			}
			$dateList[] = array('s' => $start_time, 'e' => $stop_time);
		} else {
			$start_time = 0;
			$stop_time = 0;
			$deliver_timeout2  = 0;
		}
		
		if ($delivery_time2 = $config['delivery_time2']) {
			$delivery_times2 = explode('-', $delivery_time2);
			$start_time2 = $delivery_times2[0] . ':00';
			$stop_time2 = $delivery_times2[1] . ':00';
			$start_time2 = strtotime(date('Y-m-d' . ' ' . $start_time2));
			$stop_time2 = strtotime(date('Y-m-d' . ' ' . $stop_time2));
			if ($start_time2 > $stop_time2) {
			    $dateList2[] = array('s' => strtotime(date('Y-m-d')), 'e' => $stop_time2);
			    $stop_time2 += 86400;
			}
			$dateList2[] = array('s' => $start_time2, 'e' => $stop_time2);
		} else {
			$start_time2 = 0;
			$stop_time2 = 0;
			$deliver_timeout2 = 0;
		}
			

		$deliverSupplyDB = D('Deliver_supply');
		

		$deliver_timeout1 = $deliver_timeout2 ? min($deliver_timeout, $deliver_timeout2) : $deliver_timeout;
		$time = $now_time - $deliver_timeout1;
// 		$last_time = $now_time - $cancel_time - 5 * 60;//超时五分钟内的订单
		$list = $deliverSupplyDB->field(true)->where(array('type' => 0, 'status' => 1, 'create_time' => array('lt', $time)))->select();
// 		$list = D('Deliver_supply')->field(true)->where(array('type' => 0, 'item' => 2, 'status' => 1, 'create_time' => array(array('lt', $time), array('gt', $last_time))))->select();
		$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
		$href = C('config.site_url').'/wap.php?c=Deliver&a=pick';
		foreach ($list as $row) {
			$this->keepThread();
			if ($row['appoint_time']) {
			    $appoint_time = strtotime(date('Y-m-d') . ' ' . date('H:i:s', $row['appoint_time']));
			    $isContinue = false;
			    if ($dateList) {
			        foreach ($dateList as $dd) {
			            if ($dd['s'] <= $appoint_time && $appoint_time <= $dd['e']) {
			                if (($now_time - $row['create_time']) < $deliver_timeout) {
			                    $isContinue = true;
			                }
			            }
			        }
			    }
			    
			    if ($dateList2) {
			        foreach ($dateList2 as $dd2) {
			            if ($dd2['s'] <= $appoint_time && $appoint_time <= $dd2['e']) {
			                if (($now_time - $row['create_time']) < $deliver_timeout2) {
			                    $isContinue = true;
			                }
			            }
			        }
			    }
			    if ($isContinue) continue;
			}
			
			
			$users = D('Deliver_user')->hasUser($row['from_lat'], $row['from_lnt']);
// 			$user = $this->distance($row);
			if (empty($users)) continue;
			$minRange = 0;
			$user = null;
			foreach ($users as $val) {
			    $range = getDistance($row['from_lat'], $row['from_lnt'], $val['lat'], $val['lng']);
			    if (($range > 0 && $range < $minRange) || $minRange == 0) {
			        $user = $val;
			        $minRange = $range;
			    }
			}
			if (empty($user)) continue;
			$result = $deliverSupplyDB->where(array('supply_id' => $row['supply_id']))->save(array('uid' => $user['uid'], 'status' => 2, 'get_type' => 1,'start_time'=>time()));
			if ($result) {
// 				if ($user['openid']) {
// 					$model->sendTempMsg('OPENTM405486394', array('href' => $href, 'wecha_id' => $user['openid'], 'first' => $user['name'] . '您好！', 'keyword1' => '系统分配一个配送订单给您，请注意及时查收。', 'keyword2' => date('Y年m月d日 H:s'), 'keyword3' => '订单号：' . $row['real_orderid'], 'remark' => '请您及时处理！'));
// 				}
				$deliverSupplyDB->sendNotice($user, $row);
				if ($row['item'] == 2) {
    				$deliver_info = serialize(array('uid' => $user['uid'], 'name' => $user['name'], 'phone' => $user['phone'], 'store_id' => $user['store_id']));
    				D('Shop_order')->where(array('order_id' => $row['order_id']))->data(array('order_status' => 2, 'deliver_info' => $deliver_info))->save();
    				D('Shop_order_log')->add_log(array('order_id' => $row['order_id'], 'from_type' => 3, 'status' => 3, 'name' => $user['name'], 'phone' => $user['phone']));
				} elseif ($row['item'] == 3) {
				    $offer_id = D('Service_offer')->add_offer($row['order_id'], $user['uid']);
				    $data = array('offer_id' => $offer_id);
				    if ($row['server_type'] != 1) {
				        $data['appoint_time'] = time() + $row['server_time'] * 60;
				    }
				    $deliverSupplyDB->where(array('supply_id' => $row['supply_id']))->save($data);
				}
			}
		}
		return true;
	}
	
	private function distance($supply)
	{
		if ($store = D('Merchant_store')->field(true)->where(array('store_id' => $supply['store_id']))->find()) {
			$users = D('Deliver_user')->field(true)->where(array('circle_id' => $store['circle_id'], 'group' => 1, 'status' => 1))->select();
			$users || $users = D('Deliver_user')->field(true)->where(array('city_id' => $store['city_id'], 'group' => 1, 'status' => 1))->select();
			$users || $users = D('Deliver_user')->field(true)->where(array('province_id' => $store['province_id'], 'group' => 1, 'status' => 1))->select();
			if (empty($users)) return null;
			
// 			$uids = '';
// 			$pre = '';
			$distance = 0;
			$return_user = null;
			foreach ($users as $user) {
				$range = getDistance($supply['aim_lat'], $supply['aim_lnt'], $user['lat'], $user['lng']);
				if ($return_user == null) {
					$distance = $range;
					$return_user = $user;
				} elseif ($distance > $range) {
					$distance = $range;
					$return_user = $user;
				}
// 				$uids .= $pre . $user['uid'];
// 				$pre = ',';
			}
			return $return_user;
			$sql = "SELECT a.pigcms_id, a.uid, a.lat, a.lng FROM " . C('DB_PREFIX') . "deliver_user_location_log AS a INNER JOIN (SELECT uid, MAX(pigcms_id) AS pigcms_id FROM " . C('DB_PREFIX') . "deliver_user_location_log GROUP BY uid) AS b ON a.uid = b.uid AND a.pigcms_id = b.pigcms_id WHERE a.uid IN ({$uids})";
			$now_users = D()->query($sql);
			foreach ($now_users as $v) {
				$range = getDistance($supply['aim_lat'], $supply['aim_lnt'], $v['lat'], $v['lng']);
				if ($uid == 0) {
					$distance = $range;
					$uid = $v['uid'];
				} elseif ($distance > $range) {
					$distance = $range;
					$uid = $v['uid'];
				}
			}
			if ($uid) {
				$return_user = D('Deliver_user')->field(true)->where(array('uid' => $uid))->find();
			}
			return $return_user;
		}
		return null;
	}
}
?>