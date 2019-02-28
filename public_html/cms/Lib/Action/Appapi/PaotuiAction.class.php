<?php
class PaotuiAction extends BaseAction{
    public function wxapp_login(){
        if($_POST['ticket']){
            if ($this->_uid){
                $now_user = $this->autologin('uid',$this->_uid);
                if(!empty($now_user)){
                    $return = array(
                        'ticket'    =>	$_POST['ticket'],
                        'user'      =>	$now_user,
                    );
					$plat_wxapp_ticket_info = ticket::create($now_user['uid'],'wxapp', true);
					$return['user']['plat_wxapp_ticket'] = $plat_wxapp_ticket_info['ticket'];
                    $this->returnCode(0, $return);
                }else{
                    $this->returnCode(0,array('emptyUser'=>true));
                }
            }else{
                $this->returnCode(0,array('emptyUser'=>true));
            }
        }

        $appid = $this->config['pay_wxapp_paotui_appid'];
        $appsecret = $this->config['pay_wxapp_paotui_appsecret'];


        import('ORG.Net.Http');
        $http = new Http();

        $return = Http::curlPost('https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code='.$_POST['code'].'&grant_type=authorization_code', array());

        import('@.ORG.aeswxapp.wxBizDataCrypt');

        $pc = new WXBizDataCrypt($appid, $return['session_key']);
        $errCode = $pc->decryptData($_POST['encryptedData'],$_POST['iv'],$data);
        $jsonrt = json_decode($data,true);
        /*优先使用 unionId 登录*/
        if(!empty($jsonrt['unionId'])){
            $now_user = $this->autologin('union_id',$jsonrt['unionId'],$jsonrt['openId']);
        }else{
            /*再次使用 openId 登录*/
            $now_user = $this->autologin('paotui_openid',$jsonrt['openId']);
        }
		$jsonrt['avatarUrl'] = str_replace('http://wx.qlogo.cn','https://thirdwx.qlogo.cn',$jsonrt['avatarUrl']);
        if(empty($now_user)){
            $data_user = array(
                'paotui_openid' 	=> $jsonrt['openId'],
                'union_id' 	=> ($jsonrt['unionId'] ? $jsonrt['unionId'] : ''),
                'nickname' 	=> $jsonrt['nickName'],
                'sex' 		=> $jsonrt['gender'],
                'province' 	=> $jsonrt['province'],
                'city' 		=> $jsonrt['city'],
                'avatar' 	=> $jsonrt['avatarUrl'],
                'is_follow' => 1,
                'source' 	=> 'wxapp_paotui'
            );
            $reg_result = D('User')->autoreg($data_user);
            if(!$reg_result['error_code']){
                $now_user = $this->autologin('paotui_openid',$jsonrt['openId']);
            }
        }else{
			if($now_user['avatar'] != $jsonrt['avatarUrl']){
				D('User')->save_user($now_user['uid'],'avatar',$jsonrt['avatarUrl']);
			}
		}

        if(!empty($now_user)){
            $ticket = ticket::create($now_user['uid'],'wxapp_paotui', true);
            $return = array(
                'ticket'=>	$ticket['ticket'],
                'user'	=>	$now_user,
            );
			
			$plat_wxapp_ticket_info = ticket::create($now_user['uid'],'wxapp', true);
			$return['user']['plat_wxapp_ticket'] = $plat_wxapp_ticket_info['ticket'];
			
            $this->returnCode(0,$return);
        }else{
            $this->returnCode(0,array('emptyUser'=>true));
        }
    }
    protected function autologin($field,$value,$openid = ''){
        $result = D('User')->autologin($field,$value);
        $now_user = array();
        if(empty($result['error_code'])){
            if($field == 'union_id' && empty($result['user']['paotui_openid'])){
                $condition_user['union_id'] = $value;
                D('User')->where($condition_user)->data(array('paotui_openid'=>$openid))->save();
                $result['user']['paotui_openid'] = $openid;
            }
            $result['user']['showPhone'] = substr_replace($result['user']['phone'], '****', 3, 4);
            $now_user = $result['user'];
        }
        return $now_user;
    }
	
	// 小程序配置接口
	public function config(){
		$return['config'] = array(
			'pay_wxapp_appid' => $this->config['pay_wxapp_appid'],
			'site_phone' 	  => $this->config['site_phone'],
		);
		$return['config']['moreLink'] = array(
			array(
				'title' => '更多功能',
				'desc'  => '本地团购外卖',
				'path'  => 'pages/index/index',
			),
			array(
				'title' => '本地商城',
				'desc'  => '精选本地好货',
				'path'  => 'pages/index/location?redirect=webview&webview_url='.urlencode($this->config['site_url'].'/wap.php?g=Wap&c=Mall&a=index').'&webview_title='.urlencode('本地商城'),
			),
			array(
				'title' => '小猪社群',
				'desc'  => '寻找本地交流群',
				'path'  => '',
				'wxapp_appid' => 'wx9dbabefa7aa20c6a'
			)
		);
		$return['catList'] = D('Service_category')->field('`cid`,`cat_name`,`accept_time`,`type`')->where(array('cat_status'=>1,'type'=>array('neq','1')))->order('cat_sort desc,cid asc')->select();
        if(!is_array($return['catList'])){
			$return['catList'] = array();
        }
		$this->returnCode('0',$return);
	}
	
	//根据用户上报的经纬度，得到能为其服务的配送员数量
	public function deliverAround(){
		$fromType = 0;
		if($_POST['type'] == 'gcj02'){
			$fromType = 3;
		}
		
		$longlatClass = new longlat();
		if($fromType){
			$longlat = $longlatClass->toBaidu($_POST['lat'],$_POST['lng'],$fromType);
			if(!$longlat){
				$this->returnCode(0,array('returnDeliver'=>array(),'userAdress'=>array()));
			}
			$_POST['lat'] = $longlat['lat'];
			$_POST['lng'] = $longlat['lng'];
		}
		
		
		//返回配送员数量
        $deliver = D('Deliver_user')->hasUser($_POST['lat'],$_POST['lng']);
		$returnDeliver = array();
		if($deliver){
			foreach($deliver as $deliverValue){
				$longlat = $longlatClass->baiduToGcj02($deliverValue['now_lat'],$deliverValue['now_lng']);
				$returnDeliver[] = array(
					'lng' => $longlat['lng'],
					'lat' => $longlat['lat'],
				);
			}
		}
		
		//若用户登录情况下返回离用户最近的收货地址
		if($this->_uid){
			$userAdress = D('User_adress')->getNearAdress($this->_uid,$_POST['lat'],$_POST['lng']);
			if($userAdress && $_POST['type'] == 'gcj02'){
				$userAdress['lng'] = $userAdress['longitude'];
				$userAdress['lat'] = $userAdress['latitude'];
				$longlat = $longlatClass->baiduToGcj02($userAdress['latitude'],$userAdress['longitude']);
				$userAdress['formartLng'] = $longlat['lng'];
				$userAdress['formartLat'] = $longlat['lat'];
			}
		}
		if(!$userAdress){
			$userAdress = array();
		}
		$return['returnDeliver']  = $returnDeliver;
		$return['userAdress']  = $userAdress;
        $this->returnCode(0,$return);
	}
	
	//根据用户上报的位置，得到省市区
	public function wxapp_get_location(){
		$fromType = 0;
		if($_POST['type'] == 'gcj02'){
			$fromType = 3;
		}
		
		$longlatClass = new longlat();
		if($fromType){
			$longlat = $longlatClass->toBaidu($_POST['lat'],$_POST['lng'],$fromType);
			if(!$longlat){
				$this->returnCode(0,array());
			}
			$address_lat = $longlat['lat'];
			$address_lng = $longlat['lng'];
		}else{
			$address_lat = $_POST['lat'];
			$address_lng = $_POST['lng'];
		}
		
		// 指定地点 获得省市区id
		$area_info = D('Area')->cityMatching($address_lat, $address_lng);
		
		if(empty($area_info['area_id']) || $area_info['is_open'] == 0){
			$this->returnCode('10001',array(),'当前城市暂未开通该服务');
		}
		
		if(empty($area_info['area_info']['area_id']) || $area_info['area_info']['area_is_open'] == 0){
			$this->returnCode('10001',array(),'当前区域暂未开通该服务');
		}
		
		$return = array(
			'province' 		=> $area_info['area_info']['province_id'],
			'province_txt'  => $area_info['area_info']['province_name'],
			
			'city'  		=> $area_info['area_info']['city_id'],
			'city_txt' 		=> $area_info['area_info']['city_name'],
			
			'area' 			=> $area_info['area_info']['area_id'],
			'area_txt' 		=> $area_info['area_info']['area_name'],
			
			'adress'		=> $_POST['adress'],
			
			'lng'			=> $address_lng,
			'lat'			=> $address_lat,
		);
		if($_POST['type'] == 'gcj02'){
			$return['formartLng'] = $_POST['lng'];
			$return['formartLat'] = $_POST['lat'];
		}
		$this->returnCode(0,$return);
	}
	
	//获取时间和运费配置项
	public function publish_detail(){
        $cid = $_POST['cid'];

        // 获取分类信息
        $database_Service_category = D('Service_category');
        $now_category = $database_Service_category->field(true)->where(array('cid'=>$cid))->find();
        if (empty($now_category)) {
            $this->returnCode('50000002');
        }

        // 判断是否有今天数据
        $weekarray=array("日","一","二","三","四","五","六"); //定义星期
        // 统一获取一小时后的时间 即为配送最早为一小时后
        $service_deliver_send_time = intval(C('config.service_deliver_send_time'));
        if ($service_deliver_send_time && $service_deliver_send_time > 0) {
            $date_time_H = date("H", strtotime("+".$service_deliver_send_time." minutes"));
            $date_time_M = date("i", strtotime("+".$service_deliver_send_time." minutes"));
        } else {
            $date_time_H = date("H", strtotime("+1 hours"));
            $date_time_M = date("i", strtotime("+1 hours"));
        }
        // 处理今天是否有数据
        $is_today = 0;
        $start_time1 = 0;
        $end_time1 = 0;
        $delivery_startTime_minute1 = 0;
        $delivery_endTime_minute1 = 0;
        $delivery_times = explode('-', $this->config['service_delivery_time']);
        if ($delivery_times[0] && $delivery_times[1]) {
            $delivery_startTime_minute_arr1 = explode(':', $delivery_times[0]);
            $delivery_endTime_minute_arr1 = explode(':', $delivery_times[1]);
            // 获取截止时间的分钟数，做最后取件时间判断
            $delivery_startTime_minute1 = intval($delivery_startTime_minute_arr1[1]);
            $delivery_endTime_minute1 = intval($delivery_endTime_minute_arr1[1]);
            // 做超过(包含)24点的时间范围处理
            $delivery_times1_start = intval($delivery_times[0]);
            $delivery_times1_end = intval($delivery_times[1]);

            // 处理起始时间
            if ($delivery_times1_start < $delivery_times1_end || ($delivery_times1_start == $delivery_times1_end && $delivery_startTime_minute1 < $delivery_endTime_minute1)) {
                $start_time1 = $delivery_times1_start;
                $end_time1 = $delivery_times1_end;
            } else {
                $start_time1 = $delivery_times1_start;
                $end_time1 = $delivery_times1_end + 24;
            }

            for ($d=0; $d <= intval($this->config['service_days_number']); $d++) {
                for ($i = $start_time1; $i <= $end_time1 ; $i++) {
                    if($d == 0){
                        if($i >= $date_time_H){
                            for ($s=0; $s <= 5; $s++) {
                                if ($i == $date_time_H && intval($s."0") < $date_time_M) {
                                    continue;
                                } else {
                                    if ($service_deliver_send_time && $service_deliver_send_time > 0) {
                                        $num = intval($s."0") - intval($date_time_M);
                                        if ($i == $date_time_H && $num < 10) {
                                            $minute_info = $date_time_M;
                                        } else {
                                            $minute_info = $s."0";
                                        }
                                    } else {
                                        $minute_info = $s."0";
                                    }
                                }
                                if ($i != $end_time1 || intval($s."0") < $delivery_endTime_minute1) {
                                    if ($i >= 24) {
                                        $hour = $i - 24;
                                        $day = $d + 1;
                                    } else {
                                        $hour = $i;
                                        $day = $d;
                                    }
                                    if ($day == 0) {
                                        $time[$day][$hour][$s]['week'] = "今天";
                                        $is_today = 1;
                                    } else if ($day == 1) {
                                        $time[$day][$hour][$s]['week'] = "明天";
                                    } else if ($day == 2) {
										$time[$day][$hour][$s]['week'] = "后天";
									}
                                    $time[$day][$hour][$s]['time'] = $hour . ":" . $minute_info;
                                    $time[$day][$hour][$s]['basic_distance'] = $this->config['service_basic_distance'];
                                    $time[$day][$hour][$s]['delivery_fee'] = $this->config['service_delivery_fee'];
                                    $time[$day][$hour][$s]['per_km_price'] = $this->config['service_per_km_price'];
                                    $time[$day][$hour][$s]['date'] = date("m-d", strtotime("+" . $day . " day"));
                                }
                            }
                        }
                    }else{
                        for ($s=0; $s <= 5; $s++) {
                            if (empty($time[$d][$i][$s]) && ($i != $end_time1 || intval($s."0") < $delivery_endTime_minute1)) {
                                if ($i >= 24) {
                                    $hour = $i - 24;
                                    $day = $d + 1;
                                } else {
                                    $hour = $i;
                                    $day = $d;
                                }
                                $time[$day][$hour][$s]['time'] = $hour . ":" . $s . "0";
                                $time[$day][$hour][$s]['basic_distance'] = $this->config['service_basic_distance'];
                                $time[$day][$hour][$s]['delivery_fee'] = $this->config['service_delivery_fee'];
                                $time[$day][$hour][$s]['per_km_price'] = $this->config['service_per_km_price'];
                                if ($day == 1) {
                                    $time[$day][$hour][$s]['week'] = "明天";
                                } else if ($day == 2) {
                                    $time[$day][$hour][$s]['week'] = "后天";
                                } else {
                                    $time[$day][$hour][$s]['week'] = "周" . $weekarray[date("w", strtotime("+" . $day . " day"))];
                                }
                                $time[$day][$hour][$s]['date'] = date("m-d", strtotime("+" . $day . " day"));
                            }
                        }
                    }
                }
            }
        }

        $start_time = 0;
        $end_time = 0;
        $delivery_startTime_minute2 = 0;
        $delivery_endTime_minute2 = 0;
        $delivery_times2 = explode('-', $this->config['service_delivery_time2']);
        if ($delivery_times2[0] && $delivery_times2[1]) {
            $delivery_startTime_minute_arr2 = explode(':', $delivery_times2[0]);
            $delivery_endTime_minute_arr2 = explode(':', $delivery_times2[1]);
            // 获取截止时间的分钟数，做最后取件时间判断
            $delivery_startTime_minute2 = intval($delivery_startTime_minute_arr2[1]);
            $delivery_endTime_minute2 = intval($delivery_endTime_minute_arr2[1]);
            // 做超过(包含)24点的时间范围处理
            $delivery_times2_start = intval($delivery_times2[0]);
            $delivery_times2_end = intval($delivery_times2[1]);
            // 处理起始时间 结束时间大于开始时间或者两者时间小时相同，但分钟不同
            if ($delivery_times2_start < $delivery_times2_end || ($delivery_times2_start == $delivery_times2_end && $delivery_startTime_minute2 < $delivery_endTime_minute2)) {
                $start_time = $delivery_times2_start;
                $end_time = $delivery_times2_end;
            } else {
                $start_time = $delivery_times2_start;
                $end_time = $delivery_times2_end + 24;
            }

            for ($d=0; $d <= intval($this->config['service_days_number']); $d++) {
                for ($i = $start_time; $i <= $end_time ; $i++) {
                    if($d==0){
                        if($i >= $date_time_H){
                            for ($s=0; $s <= 5; $s++) {
                                if ($i == $date_time_H && intval($s."0") < $date_time_M) {
                                    continue;
                                } else {
                                    if ($service_deliver_send_time && $service_deliver_send_time > 0) {
                                        $num = intval($s."0") - intval($date_time_M);
                                        if ($i == $date_time_H && $num < 10) {
                                            $minute_info = $date_time_M;
                                        } else {
                                            $minute_info = $s."0";
                                        }
                                    } else {
                                        $minute_info = $s."0";
                                    }
                                }
                                if (empty($time[$d][$i][$s]) && ($i != $end_time || intval($s."0") < $delivery_endTime_minute2)) {
                                    if ($i >= 24) {
                                        $hour = $i - 24;
                                        $day = $d + 1;
                                    } else {
                                        $hour = $i;
                                        $day = $d;
                                    }
                                    if ($day == 0) {
                                        $time[$day][$hour][$s]['week'] = "今天";
                                        $is_today = 1;
                                    } else if ($day == 1) {
                                        $time[$day][$hour][$s]['week'] = "明天";
                                    }
                                    $time[$day][$hour][$s]['time'] = $hour.":".$minute_info;
                                    $time[$day][$hour][$s]['basic_distance'] = $this->config['service_basic_distance2'];
                                    $time[$day][$hour][$s]['delivery_fee'] = $this->config['service_delivery_fee2'];
                                    $time[$day][$hour][$s]['per_km_price'] = $this->config['service_per_km_price2'];
                                    $time[$day][$hour][$s]['date'] = date("m-d",strtotime("+".$day." day"));
                                }

                            }
                        }
                    }else{
                        for ($s=0; $s <= 5; $s++) {
                            if (empty($time[$d][$i][$s]) && ($i != $end_time || intval($s."0") < $delivery_endTime_minute2)) {
                                if ($i >= 24) {
                                    $hour = $i - 24;
                                    $day = $d + 1;
                                } else {
                                    $hour = $i;
                                    $day = $d;
                                }
                                $time[$day][$hour][$s]['time'] = $hour . ":" . $s . "0";
                                $time[$day][$hour][$s]['basic_distance'] = $this->config['service_basic_distance2'];
                                $time[$day][$hour][$s]['delivery_fee'] = $this->config['service_delivery_fee2'];
                                $time[$day][$hour][$s]['per_km_price'] = $this->config['service_per_km_price2'];
                                if ($day == 1) {
                                    $time[$day][$hour][$s]['week'] = "明天";
                                } else if ($day == 2) {
                                    $time[$day][$hour][$s]['week'] = "后天";
                                } else {
                                    $time[$day][$hour][$s]['week'] = "周" . $weekarray[date("w", strtotime("+" . $day . " day"))];
                                }

                                $time[$day][$hour][$s]['date'] = date("m-d", strtotime("+" . $day . " day"));
                            }
                        }
                    }

                }
            }
        }

        foreach ($time as $key => $value) {
            ksort($value);
            foreach ($value as $kk => $vv) {
                foreach ($vv as $kkk => $vvv) {
                    $timeList[$key][] = $vvv;
                }
            }
        }


        // $weekarray=array("日","一","二","三","四","五","六");
        for ($d=0; $d <= intval($this->config['service_days_number']); $d++) {
			$day = date("m-d",strtotime("+".$d." day"));
			$daysList[$day]  =  array('show'=>$day,'day'=>$day);
            if($d == 0){
                if (1 == $is_today) {
                    $daysList[$day]['show'] = '今天';
                }
            }else if($d==1){
				$daysList[$day]['show'] = '明天';
            }else if($d==2){
				$daysList[$day]['show'] = '后天';
            }
        }

        if ($now_category['type'] == 2) {
            // 如果配置未全，仍然去之前的配置一中的配置
            if (empty($this->config['service_give_basic_distance']) || empty($this->config['service_give_delivery_fee']) || empty($this->config['service_give_per_km_price'])) {
                $config_time['service_basic_distance'] = $this->config['service_basic_distance'];
                $config_time['service_delivery_fee'] = $this->config['service_delivery_fee'];
                $config_time['service_per_km_price'] = $this->config['service_per_km_price'];
            } else {
                $config_time['service_basic_distance'] = $this->config['service_give_basic_distance'];
                $config_time['service_delivery_fee'] = $this->config['service_give_delivery_fee'];
                $config_time['service_per_km_price'] = $this->config['service_give_per_km_price'];
            }
        } else {
            $h =  intval(date("H"));
            $m =  intval(date("i"));
            $now_time = intval(strtotime('' . $h . ':' . $m));
            $start_time_info1 = intval(strtotime('' . $start_time1 . ':' . $delivery_startTime_minute1));
            if ($end_time1 > 24) {
                $end_time1_msg = $end_time1 - 24;
                $end_time_info1 = intval(strtotime('' . $end_time1_msg . ':' . $delivery_endTime_minute1 . ' + 1 day'));
            } else {
                $end_time_info1 = intval(strtotime('' . $end_time1 . ':' . $delivery_endTime_minute1));
            }
            $start_time_info2 = intval(strtotime('' . $start_time . ':' . $delivery_startTime_minute2));
            if ($end_time > 24) {
                $end_time_msg = $end_time - 24;
                $end_time_info2 = intval(strtotime('' . $end_time_msg . ':' . $delivery_endTime_minute2 . ' + 1 day'));
            } else {
                $end_time_info2 = intval(strtotime('' . $end_time . ':' . $delivery_endTime_minute2));
            }
            // 判断当前属于的时间段
            if ($now_time >= $start_time_info1 && $now_time <= $end_time_info1) {
                $config_time['service_basic_distance'] = $this->config['service_basic_distance'];
                $config_time['service_delivery_fee'] = $this->config['service_delivery_fee'];
                $config_time['service_per_km_price'] = $this->config['service_per_km_price'];
            } elseif ($now_time >= $start_time_info2 && $now_time <= $end_time_info2) {
                $config_time['service_basic_distance'] = $this->config['service_basic_distance2'];
                $config_time['service_delivery_fee'] = $this->config['service_delivery_fee2'];
                $config_time['service_per_km_price'] = $this->config['service_per_km_price2'];
            } else {
                $config_time['service_basic_distance'] = 0;
                $config_time['service_delivery_fee'] = 0;
                $config_time['service_per_km_price'] = 0;
            }
        }



        $datas['config_time'] = $config_time;
        
		//重新排序时间
        ksort($timeList);
		foreach($timeList as $value){
			$daysList[$value[0]['date']]['timeList'] = $value;
		}

        $datas['daysList'] = array_values($daysList);
		
        $config['service_basic_km'] = $this->config['service_basic_km'];
        $config['service_basic_km_time'] = $this->config['service_basic_km_time'];
        $config['service_basic_weight'] = $this->config['service_basic_weight'];
        $config['service_basic_weight_price'] = $this->config['service_basic_weight_price'];
        $config['service_bounds_weight'] = $this->config['service_bounds_weight'];
        $config['service_days_number'] = $this->config['service_days_number'];
        $config['count_freight_charge_method'] = $this->config['count_freight_charge_method'];
        $datas['config'] = $config;


        $this->returnCode('0',$datas);
	}
	
	// 计算两个收货地址的距离（骑行/直线）帮我送
    public function get_distance(){
		$returnArr = array();
		$returnArr['is_ride'] = false;
		
		// C('config.service_is_riding_distance','1');
		
        //计算配送距离
        $distance = 0;
        if (C('config.service_is_riding_distance') && C('config.baidu_map_ak')) {
            import('@.ORG.longlat');
            $longlat_class = new longlat();
            $distance = $longlat_class->getRidingDistance($_POST['start_lat'],$_POST['start_lng'],$_POST['end_lat'],$_POST['end_lng']);
            if ($distance == -1) {
                $this->returnCode('50000008');
            }
			if($distance){
				$returnArr['is_ride'] = true;
			}
        }
        $distance || $distance = getDistance($_POST['start_lat'],$_POST['start_lng'],$_POST['end_lat'],$_POST['end_lng']);
        $distance = $distance / 1000;
		$returnArr['distance'] = $distance;
        $this->returnCode('0',$returnArr);
    }


    // 计算两个收货地址的距离（骑行/直线）帮我送
    private function distance_calculation($data){
        //计算配送距离
        $distance = 0;
        if (C('config.service_is_riding_distance') && C('config.baidu_map_ak')) {
            import('@.ORG.longlat');
            $longlat_class = new longlat();
            $distance = $longlat_class->getRidingDistance($data['start_lat'],$data['start_lng'],$data['end_lat'],$data['end_lng']);
            if ($distance == -1) {
                $this->returnCode('50000008');
            }
        }
        $distance || $distance = getDistance($data['start_lat'],$data['start_lng'],$data['end_lat'],$data['end_lng']);
        $distance = $distance / 1000;
        return $distance;
    }

    // 服务快派 金钱计算方式
    private function publish_count_method($price) {
        // 计算完成后按照 配送配置-配送条件-配送计算方式
        switch (intval($this->config['count_freight_charge_method'])) {
            case 0:
                $price = round($price, 2);
                break;
            case 1:
                $price = ceil($price * 10) / 10;
                break;
            case 2:
                $price = floor($price * 10) / 10;
                break;
            case 3:
                $price = round($price, 1);
                break;
            case 4:
                $price = ceil($price);
                break;
            case 5:
                $price = floor($price);
                break;
            case 6:
                $price = round($price);
                break;
        }
        return $price;
    }

    // 帮我送订单信息
    public function publish_give_data(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $distance = array(
            'start_lat' => $_POST['start_adress_lat'],
            'start_lng' => $_POST['start_adress_lng'],
            'end_lat' =>   $_POST['end_adress_lat'],
            'end_lng' =>   $_POST['end_adress_lng'],
        );
        $distance_info = $this->distance_calculation($distance);
        $weight_price = 0;
        // 计算中重量费用
        if (!empty($_POST['weight'])) {
            // 初始重量
            $service_basic_weight = floatval($this->config['service_basic_weight']);
            // 初始重量费用
            $service_basic_weight_price = floatval($this->config['service_basic_weight_price']);
            // 超出初始重量每公斤费用
            $service_bounds_weight = floatval($this->config['service_bounds_weight']);
            $weight = floatval($_POST['weight']);
            if ($weight > $service_basic_weight) {
                $weight_price = ($weight - $service_basic_weight) * $service_bounds_weight + $service_basic_weight_price;
            } else {
                $weight_price = $service_basic_weight_price;
            }
            $weight_price = $this->publish_count_method($weight_price);
        } else {
            $this->returnCode('50000004');
        }
        // 计算服务费用
        $distance_price = 0;
        if (!empty($_POST['fetch_time']) && !empty($_POST['per_km_price']) && !empty($_POST['basic_distance'])) {
            $per_km_price = floatval($_POST['per_km_price']);
            $basic_distance = floatval($_POST['basic_distance']);
            if ($distance_info < $basic_distance) {
                $distance_price = 0;
            } else {
                $distance_price = ($distance_info - $basic_distance) * $per_km_price;
            }
            $distance_price = $this->publish_count_method($distance_price);
        } else {
            $this->returnCode('50000004');
        }
        // 计算总费用
        $basic_distance_price = floatval($_POST['basic_distance_price']);
        $tip_price = floatval($_POST['tip_price']);
        $total_price = $weight_price + $distance_price + $basic_distance_price + $tip_price;



        $data['address'] = $_POST['start_adress_detail'];
        $data['address_lng'] = $_POST['start_adress_lng'];
        $data['address_lat'] = $_POST['start_adress_lat'];
        $data['address_area_id'] = $_POST['start_adress_area_id'] ? $_POST['start_adress_area_id'] : 0;
        $data['province_id'] = $_POST['start_province_id'];
        $data['city_id'] = $_POST['start_city_id'];
        $data['area_id'] = $_POST['start_area_id'];

        // 开启了手动调度 不论是否存在平台配送员 均默认选择 平台配送
        if ($this->config['deliver_model'] == 1) {
            $data['deliver_type'] = 1;
        } else {
            // 开启抢单模式情况 如果存在平台配送员 选择平台配送 没有 选择商家配送
            //获取平台配送员信息
            $deliver = D('Deliver_user')->hasUser($data['address_lat'],$data['address_lng']);
            if($deliver){
                $data['deliver_type'] = 1;
            }else{
                $data['deliver_type'] = 2;
            }
        }


        $data['add_time'] = time();
        $data['cid'] = $_POST['cid'];
        $data['uid'] = $this->_uid;
        $data['catgory_type'] = 3;
        $data['status'] = 1;
        $data['destance_sum'] = $distance_info;
        $nowtime = date("mdHis");
        $data['order_sn'] = $nowtime.rand(10,99).sprintf("%08d",$this->_uid);
        $res = D('Service_user_publish')->data($data)->add();
        if($res){
            $giveData['publish_id'] =  $res;
            $giveData['cid'] = $_POST['cid'];
            $giveData['goods_catgory'] =  $_POST['goods_catgory'];
            $giveData['weight'] =  $_POST['weight'];
            $giveData['price'] =  $_POST['price'];
            $giveData['img'] =  $_POST['img'];

            // $giveData['start_adress_id'] =  $_POST['start_adress_id'];

            $giveData['start_adress_name'] =  $_POST['start_adress_detail'];
            $giveData['start_adress_lng'] =  $_POST['start_adress_lng'];
            $giveData['start_adress_lat'] =  $_POST['start_adress_lat'];
            $giveData['start_adress_nickname'] =  $_POST['start_adress_name'];
            $giveData['start_adress_phone'] =  $_POST['start_adress_phone'];


            // $giveData['end_adress_id'] =  $_POST['end_adress_id'];

            $giveData['end_adress_name'] =  $_POST['end_adress_detail'];
            $giveData['end_adress_lng'] =  $_POST['end_adress_lng'];
            $giveData['end_adress_lat'] =  $_POST['end_adress_lat'];
            $giveData['end_adress_nickname'] =  $_POST['end_adress_name'];
            $giveData['end_adress_phone'] =  $_POST['end_adress_phone'];

            $giveData['fetch_time'] =  $_POST['fetch_time'];

            $giveData['tip_price'] =  $_POST['tip_price'];
            $giveData['remarks'] = $this->filterEmoji2($_POST['remarks']);

            $giveData['basic_distance_price'] =  $basic_distance_price;
            $giveData['distance_price'] =  $distance_price;
            $giveData['weight_price'] =  $weight_price;

            $giveData['total_price'] =  $total_price;
            $giveData['addtime'] =  time();
            $giveData['uid'] =  $this->_uid;
            // D("Service_user_publish_give")->data($giveData)->add();

            $give_res = D("Service_user_publish_give")->data($giveData)->add();

            if(!$give_res){
                D('Service_user_publish')->where(array('publish_id'=>$res))->delete();
                $this->returnCode('1',array('error_code'=>true,'error_msg'=>'数据添加异常请重试！'));
            }

            $pay_order_param = array(
                'business_type' => 'service',
                'business_id' => $res,
                'order_name' => '帮我送订单',
                'uid' => $this->_uid,
                'total_money' => $total_price,
                'wx_cheap' => 0,
            );
            $plat_order_result = D('Plat_order')->add_order($pay_order_param);
            if ($plat_order_result['error_code']) {
                $this->returnCode('1',$plat_order_result);
            } else {
                $returnArr = array(
                    'msg' => '需求已发布,请去支付!',
                    'order_id' => $plat_order_result['order_id'],
                    'type' => 'plat'
                );
                $this->returnCode('0',$returnArr);
            }

        }else{
            $this->returnCode('50000006');
        }
    }


    // 帮我买
    public function publish_buy_data(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        // 指定地点 计算费用
        if (empty($this->config['service_give_basic_distance']) || empty($this->config['service_give_delivery_fee']) || empty($this->config['service_give_per_km_price'])) {
            $service_basic_distance = floatval($this->config['service_basic_distance']);
            $service_delivery_fee = floatval($this->config['service_delivery_fee']);
            $service_per_km_price = floatval($this->config['service_per_km_price']);
        } else {
            $service_basic_distance = floatval($this->config['service_give_basic_distance']);
            $service_delivery_fee = floatval($this->config['service_give_delivery_fee']);
            $service_per_km_price = floatval($this->config['service_give_per_km_price']);
        }

        // 获取提交的收获地址信息
        if($_POST['buy_type'] == 2){
            $distance = array(
                'start_lat' => $_POST['start_adress_lat'],
                'start_lng' => $_POST['start_adress_lng'],
                'end_lat' =>   $_POST['end_adress_lat'],
                'end_lng' =>   $_POST['end_adress_lng'],
            );
            $distance_info = $this->distance_calculation($distance);
            // 计算服务费用
            $distance_price = 0;
            if (!empty($service_basic_distance) && !empty($service_delivery_fee) && !empty($service_per_km_price)) {
                if ($distance_info < $service_basic_distance) {
                    $distance_price = 0;
                } else {
                    $distance_price = ($distance_info - $service_basic_distance) * $service_per_km_price;
                }
                $distance_price = $this->publish_count_method($distance_price);
            } else {
                $this->returnCode('50000004');
            }
            // 计算总费用
            $tip_price = floatval($_POST['tip_price']);
            $total_price = $distance_price + $service_delivery_fee + $tip_price;

            // 指定地点 获得省市区id
            $data['address'] = $_POST['start_adress_detail'];
            $data['address_lng'] = $_POST['start_adress_lng'];
            $data['address_lat'] = $_POST['start_adress_lat'];
            $data['address_area_id'] = $_POST['start_adress_area_id'] ? $_POST['start_adress_area_id'] : 0;
            $data['province_id'] = $_POST['start_province_id'];
            $data['city_id'] = $_POST['start_city_id'];
            $data['area_id'] = $_POST['start_area_id'];

        }else{
            $data['address'] = $_POST['end_adress_detail'];
            $data['address_lng'] = $_POST['end_adress_lng'];
            $data['address_lat'] = $_POST['end_adress_lat'];
            $data['address_area_id'] = $_POST['end_adress_area_id'] ? $_POST['end_adress_area_id'] : 0;
            $data['province_id'] = $_POST['end_province_id'];
            $data['city_id'] = $_POST['end_city_id'];
            $data['area_id'] = $_POST['end_area_id'];
            // 计算总费用
            $distance_price = 0;
            $tip_price = floatval($_POST['tip_price']);
            $total_price = $service_delivery_fee + $tip_price;
            $distance_info = 0;
        }


        // 开启了手动调度 不论是否存在平台配送员 均默认选择 平台配送
        if ($this->config['deliver_model'] == 1) {
            $data['deliver_type'] = 1;
        } else {
            // 开启抢单模式情况 如果存在平台配送员 选择平台配送 没有 选择商家配送
            //获取平台配送员信息
            $deliver = D('Deliver_user')->hasUser($data['address_lat'],$data['address_lng']);
            if($deliver){
                $data['deliver_type'] = 1;
            }else{
                $data['deliver_type'] = 2;
            }
        }

        $data['add_time'] = time();
        $data['cid'] = $_POST['cid'];
        $data['uid'] = $this->_uid;
        $data['status'] = 1;
        $data['catgory_type'] = 2;
        $data['destance_sum'] = $distance_info;
        $nowtime = date("mdHis");
        $data['order_sn'] = $nowtime.rand(10,99).sprintf("%08d",$this->_uid);
        $res = M('Service_user_publish')->data($data)->add();

        if($res){

            $buyData['publish_id'] =  $res;
            $buyData['goods_remarks'] = $this->filterEmoji2($_POST['goods_remarks']);
            $buyData['buy_type'] =  $_POST['buy_type'];
            $buyData['address'] =  $_POST['start_adress_detail'];
            $buyData['address_lng'] =  $_POST['start_adress_lng'];
            $buyData['address_lat'] =  $_POST['start_adress_lat'];

            // $buyData['adress_id'] =  $_POST['adress_id'];

            $buyData['end_adress_name'] =  $_POST['end_adress_detail'];
            $buyData['end_adress_lng'] =  $_POST['end_adress_lng'];
            $buyData['end_adress_lat'] =  $_POST['end_adress_lat'];
            $buyData['end_adress_nickname'] =  $_POST['end_adress_name'];
            $buyData['end_adress_phone'] =  $_POST['end_adress_phone'];



            $buyData['arrival_time'] =  $_POST['arrival_time'];
            $buyData['estimate_goods_price'] = $_POST['estimate_goods_price'];
            $buyData['basic_distance_price'] =  $service_delivery_fee;
            $buyData['distance_price'] =  $distance_price;
            $buyData['tip_price'] =  $tip_price;
            $buyData['total_price'] =  $total_price;
            $buyData['img'] =  $_POST['img'];
            $buy_res = D('Service_user_publish_buy')->data($buyData)->add();
            if(!$buy_res){
                D('Service_user_publish')->where(array('publish_id'=>$res))->delete();
                $this->returnCode('1',array('error_code'=>true,'error_msg'=>'数据添加异常请重试！'));
            }

            $pay_order_param = array(
                'business_type' => 'service',
                'business_id' => $res,
                'order_name' => '帮我买订单',
                'uid' => $this->_uid,
                'total_money' => $total_price,
                'wx_cheap' => 0,
            );
            $plat_order_result = D('Plat_order')->add_order($pay_order_param);
            if ($plat_order_result['error_code']) {
                $this->returnCode('1',$plat_order_result);
            } else {
                $returnArr = array(
                    'msg' => '需求已发布,请去支付!',
                    'order_id' => $plat_order_result['order_id'],
                    'type' => 'plat'
                );
                $this->returnCode('0',$returnArr);
            }
        }else{
            $this->returnCode('50000006');
        }
    }


    /**
     * 过滤emoji表情
     * @param string   $str 字符串
     * @return string
     */
    function filterEmoji2($str){
        if(empty($str)) return null;
        $str = preg_replace_callback(
            '/[\xf0-\xf7].{3}/',
            function($r){
                return '@E' . base64_encode($r[0]);
            },$str);
        $countt=substr_count($str,"@");
        for ($i=0; $i < $countt; $i++) {
            $c = stripos($str,"@");
            $str=substr($str,0,$c).substr($str,$c+10,strlen($str)-1);
        }
        $str = preg_replace_callback(
            '/@E(.{6}==)/',
            function($r){
                return base64_decode($r[1]);
            }, $str);
        return $str;
    }


}
?>