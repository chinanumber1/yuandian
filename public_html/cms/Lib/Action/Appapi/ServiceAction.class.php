<?php
/*
 *   Writers    guo
 */
class ServiceAction extends BaseAction{

    // 分类列表
    public function cat_list(){
        $catList = D('Service_category')->where(array('cat_status'=>1,'type'=>array('neq','1')))->order('cat_sort desc,cid asc')->select();
        if(is_array($catList)){
            $this->returnCode('0',$catList);
        }else{
            $this->returnCode('50000002');
        }
    }

    // 实名认证
    public function authentication(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        if($this->config['user_is_real_name'] == 1){
            $user_authentication = D("User_authentication")->where(array('uid'=>$this->_uid))->field('authentication_id,authentication_status,uid')->find();
            $url = "wap.php?g=Wap&c=My&a=authentication";
            if(empty($user_authentication)){
                exit(json_encode(array('errorCode'=>'50000001','errorMsg'=>'请先实名认证再进行发布','result'=>'/wap.php?g=Wap&c=My&a=authentication')));
            } elseif($user_authentication['authentication_status'] != 1){
                $this->redirect(U('My/authentication_index'));
            }
        }else{
            $this->returnCode('0');
        }
    }

    // 用户发布需求详情
    public function publish_detail(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $cid = I('cid');
        $lat = I('lat');
        $lng = I('lng');

        // 获取分类信息
        $database_Service_category = D('Service_category');
        $now_category = $database_Service_category->field(true)->where(array('cid'=>$cid))->find();
        if (empty($now_category)) {
            $this->returnCode('50000002');
        }

        //返回配送员数量
        $deliver = D('Deliver_user')->hasUser($lat,$lng);
        $datas['deliver_count'] = count($deliver);

        $adress_list = D('User_adress')->get_adress_list($this->_uid);
        foreach ($adress_list as $key => $value) {
            if($value['adress_id'] == $_GET['adress_id']){
                $addressInfo = $value;
            }elseif($value['default'] == 1){
                $addressInfo = $value;
            }
        }

        if(empty($adress_list)){
            $adress_list = array();
        }

        if(empty($addressInfo)){
            $addressInfo = (Object)array();
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
                                } else if ($i == $start_time1 && intval($s."0") < $delivery_startTime_minute1) {
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
                                    }
                                    $time[$day][$hour][$s]['time'] = $hour . ":" . $minute_info;
                                    $time[$day][$hour][$s]['basic_distance'] = $this->config['service_basic_distance'];
                                    $time[$day][$hour][$s]['delivery_fee'] = $this->config['service_delivery_fee'];
                                    $time[$day][$hour][$s]['per_km_price'] = $this->config['service_per_km_price'];
                                    $time[$day][$hour][$s]['date'] = date("Y-m-d", strtotime("+" . $day . " day"));
                                }
                            }
                        }
                    }else{
                        for ($s=0; $s <= 5; $s++) {
                            if ($i == $start_time1 && intval($s."0") < $delivery_startTime_minute1) {
                                continue;
                            }
                            if ($i != $end_time1 || intval($s."0") < $delivery_endTime_minute1) {
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
                                $time[$day][$hour][$s]['date'] = date("Y-m-d", strtotime("+" . $day . " day"));
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
                                } else if ($i == $start_time && intval($s."0") < $delivery_startTime_minute2) {
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
                                    $time[$day][$hour][$s]['date'] = date("Y-m-d",strtotime("+".$day." day"));
                                }

                            }
                        }
                    }else{
                        for ($s=0; $s <= 5; $s++) {
                            if ($i == $start_time && intval($s."0") < $delivery_startTime_minute2) {
                                continue;
                            }
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

                                $time[$day][$hour][$s]['date'] = date("Y-m-d", strtotime("+" . $day . " day"));
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
            if($d == 0){
                if (1 == $is_today) {
                    $daysList[] =  "今天";
                }
            }else if($d==1){
                $daysList[] =  "明天";
            }else if($d==2){
                $daysList[] =  "后天";
            }else{
                $daysList[] =  date("Y-m-d",strtotime("+".$d." day"));
            }

        }

        if ($now_category['type'] == 2) {
            // 如果配置全空且不为0，仍然取之前的配置一中的配置
            $service_give_basic_distance = $this->config['service_give_basic_distance'];
            $service_give_delivery_fee = $this->config['service_give_delivery_fee'];
            $service_give_per_km_price = $this->config['service_give_per_km_price'];

            $service_give_basic_distance_if =  (!$service_give_basic_distance && $service_give_basic_distance !== 0 && $service_give_basic_distance !== '0');
            $service_give_delivery_fee_if =  (!$service_give_delivery_fee && $service_give_delivery_fee !== 0 && $service_give_delivery_fee !== '0');
            $service_give_per_km_price_if =  (!$service_give_per_km_price && $service_give_per_km_price !== 0 && $service_give_per_km_price !== '0');
            if ($service_give_basic_distance_if && $service_give_delivery_fee_if && $service_give_per_km_price_if) {
                $config_time['service_basic_distance'] = $this->config['service_basic_distance'];
                $config_time['service_delivery_fee'] = $this->config['service_delivery_fee'];
                $config_time['service_per_km_price'] = $this->config['service_per_km_price'];
            } else {
                $config_time['service_basic_distance'] = $this->config['service_give_basic_distance'] ? $this->config['service_give_basic_distance'] : 0;
                $config_time['service_delivery_fee'] = $this->config['service_give_delivery_fee'] ? $this->config['service_give_delivery_fee'] : 0;
                $config_time['service_per_km_price'] = $this->config['service_give_per_km_price'] ? $this->config['service_give_per_km_price'] : 0;
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


        $datas['now_category'] = $now_category; //分类详情
        $datas['addressInfo'] = $addressInfo; //默认地址
        $datas['adress_list'] = array_values($adress_list); //地址列表
        $datas['config_time'] = $config_time;
        $datas['daysList'] = !empty($daysList) ? $daysList : array();
        $timeListBak = [];
        ksort($timeList);
        foreach ($timeList as $key => $value) {
            $timeListBak[] = $value;
        }
        $datas['timeList'] = $timeListBak;

        $config['service_basic_km'] = $this->config['service_basic_km'];
        $config['service_basic_km_time'] = $this->config['service_basic_km_time'];
        $config['service_basic_weight'] = $this->config['service_basic_weight'];
        $config['service_basic_weight_price'] = $this->config['service_basic_weight_price'];
        $config['service_bounds_weight'] = $this->config['service_bounds_weight'];
        $config['service_days_number'] = $this->config['service_days_number'];
        $datas['config'] = $config;


        $this->returnCode('0',$datas);
    }




    //获取附近的配送员数量
    public function deliver_count(){
        $lat = I('lat', false);
        $lng = I('lng', false);
        $deliver = D('Deliver_user')->hasUser($lat,$lng);
        if($deliver){
            $this->returnCode('0',count($deliver));
        }else{
            $this->returnCode('50000003');
        }
    }




    // 帮我买
    public function publish_buy_data(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $userInfo = D('User')->where(array('uid'=>$this->_uid))->field('now_money')->find();
        // if($userInfo['now_money']<$_POST['total_price']){
        //     $this->returnCode('50000005');
        // }

        // 获取提交的收获地址信息
        $adress_info = D("User_adress")->get_adress($this->_uid,$_POST['adress_id']);
        if($_POST['buy_type'] == 2){
            // 指定地点 获得省市区id
            $area_info = D('Area')->cityMatching($_POST['address_lat'], $_POST['address_lng']);

            $data['address'] = $_POST['address'];
            $data['address_lng'] = $_POST['address_lng'];
            $data['address_lat'] = $_POST['address_lat'];
            $data['address_area_id'] = $_POST['address_area_id'];
            $data['province_id'] = $area_info['area_info']['province_id'];
            $data['city_id'] = $area_info['area_info']['city_id'];
            $data['area_id'] = $area_info['area_info']['area_id'];
        }else{
            $data['address'] = $adress_info['adress'];
            $data['address_lng'] = $adress_info['longitude'];
            $data['address_lat'] = $adress_info['latitude'];
            $data['address_area_id'] = $adress_info['city'];
            $data['province_id'] = $adress_info['province'];
            $data['city_id'] = $adress_info['city'];
            $data['area_id'] = $adress_info['area'];
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
        $nowtime = date("mdHis");
        $data['order_sn'] = $nowtime.rand(10,99).sprintf("%08d",$this->_uid);
        $res = D('Service_user_publish')->data($data)->add();
        if($res){

            $buyData['publish_id'] =  $res;
            $buyData['goods_remarks'] = $this->filterEmoji2($_POST['goods_remarks']);
            $buyData['buy_type'] =  $_POST['buy_type'];
            $buyData['address'] =  $_POST['address'];
            $buyData['address_lng'] =  $_POST['address_lng'];
            $buyData['address_lat'] =  $_POST['address_lat'];

            $buyData['adress_id'] =  $_POST['adress_id'];

            $buyData['end_adress_name'] =  $adress_info['adress'].$adress_info['detail'];
            $buyData['end_adress_lng'] =  $adress_info['longitude'];
            $buyData['end_adress_lat'] =  $adress_info['latitude'];
            $buyData['end_adress_nickname'] =  $adress_info['name'];
            $buyData['end_adress_phone'] =  $adress_info['phone'];

            $buyData['arrival_time'] =  $_POST['arrival_time'];
            $buyData['estimate_goods_price'] = $this->filterEmoji2($_POST['estimate_goods_price']);
            $buyData['basic_distance_price'] =  $_POST['basic_distance_price'];
            $buyData['distance_price'] =  $_POST['distance_price'];
            $buyData['tip_price'] =  $_POST['tip_price'];
            $buyData['total_price'] =  $_POST['total_price'];
            $buyData['img'] =  $_POST['img'];
            $buy_res = D('Service_user_publish_buy')->data($buyData)->add();

            if(!$buy_res){
                D('Service_user_publish')->where(array('publish_id'=>$res))->delete();
                $this->returnCode('50000006');
            }

            $pay_order_param = array(
                'business_type' => 'service',
                'business_id' => $res,
                'order_name' => '帮我买订单',
                'uid' => $this->_uid,
                'total_money' => $_POST['total_price'],
                'wx_cheap' => 0,
            );
            $plat_order_result = D('Plat_order')->add_order($pay_order_param);
            if ($plat_order_result['error_code']) {
                $this->error_tips($plat_order_result['error_msg']);
                exit(json_encode(array('errorCode'=>50000008,'errorMsg'=>$plat_order_result['error_msg'])));
            } else {
                exit(json_encode(array('errorCode'=>0,'errorMsg'=>'需求已发布,请去支付!','order_id'=>$plat_order_result['order_id'],'type'=>'plat')));
            }

            // exit(json_encode(array('errorCode'=>0,'errorMsg'=>'需求提交成功','order_id'=>$res,'type'=>'service')));
        }else{
            $this->returnCode('50000006');
        }


    }


    // 帮我送
    public function publish_give_data(){
        if(!$this->_uid){
            $this->returnCode('20044013');
        }

        $userInfo = D('User')->where(array('uid'=>$this->_uid))->field('now_money')->find();
        // if($userInfo['now_money']<$_POST['total_price']){
        //     $this->returnCode('50000005');
        // }
        $start_adress_info = D("User_adress")->get_adress($this->_uid,$_POST['start_adress_id']);
        $end_adress_info = D("User_adress")->get_adress($this->_uid,$_POST['end_adress_id']);

        $data['address'] = $start_adress_info['adress'];
        $data['address_lng'] = $start_adress_info['longitude'];
        $data['address_lat'] = $start_adress_info['latitude'];
        $data['address_area_id'] = $start_adress_info['city'];
        $data['province_id'] = $start_adress_info['province'];
        $data['city_id'] = $start_adress_info['city'];
        $data['area_id'] = $start_adress_info['area'];


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

            $giveData['start_adress_id'] =  $_POST['start_adress_id'];

            $giveData['start_adress_name'] =  $start_adress_info['adress'].$start_adress_info['detail'];
            $giveData['start_adress_lng'] =  $start_adress_info['longitude'];
            $giveData['start_adress_lat'] =  $start_adress_info['latitude'];
            $giveData['start_adress_nickname'] =  $start_adress_info['name'];
            $giveData['start_adress_phone'] =  $start_adress_info['phone'];


            $giveData['end_adress_id'] =  $_POST['end_adress_id'];
            $giveData['end_adress_name'] =  $end_adress_info['adress'].$end_adress_info['detail'];
            $giveData['end_adress_lng'] =  $end_adress_info['longitude'];
            $giveData['end_adress_lat'] =  $end_adress_info['latitude'];
            $giveData['end_adress_nickname'] =  $end_adress_info['name'];
            $giveData['end_adress_phone'] =  $end_adress_info['phone'];

            $giveData['fetch_time'] =  $_POST['fetch_time'];
            $giveData['estimate_goods_price'] =  $_POST['estimate_goods_price'];

            $giveData['tip_price'] =  $_POST['tip_price'];
            $giveData['remarks'] = $this->filterEmoji2($_POST['remarks']);

            $giveData['basic_distance_price'] =  $_POST['basic_distance_price'];
            $giveData['distance_price'] =  $_POST['distance_price'];
            $giveData['weight_price'] =  $_POST['weight_price'];

            $giveData['total_price'] =  $_POST['total_price'];
            $giveData['addtime'] =  time();
            $giveData['uid'] =  $this->_uid;
            // D("Service_user_publish_give")->data($giveData)->add();

            $give_res = D("Service_user_publish_give")->data($giveData)->add();

            if(!$give_res){
                D('Service_user_publish')->where(array('publish_id'=>$res))->delete();
                exit(json_encode(array('error'=>2,'msg'=>'数据添加异常请重试！')));
            }

            $pay_order_param = array(
                'business_type' => 'service',
                'business_id' => $res,
                'order_name' => '帮我送订单',
                'uid' => $this->_uid,
                'total_money' => $_POST['total_price'],
                'wx_cheap' => 0,
            );
            $plat_order_result = D('Plat_order')->add_order($pay_order_param);
            if ($plat_order_result['error_code']) {
                $this->error_tips($plat_order_result['error_msg']);
                exit(json_encode(array('errorCode'=>50000008,'errorMsg'=>$plat_order_result['error_msg'])));
            } else {
                exit(json_encode(array('errorCode'=>0,'errorMsg'=>'需求已发布,请去支付!','order_id'=>$plat_order_result['order_id'],'type'=>'plat')));
            }

        }else{
            $this->returnCode('50000006');
        }
    }

    public function template_message(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }
        $publish_id = I('publish_id', false);

        $info = M('Service_user_publish')->where(array('publish_id'=>$publish_id))->find();
        if($info['deliver_type'] == 1){
            //推送配送订单，
            D('Deliver_supply')->saveOrder($publish_id, null,3,'service_user_publish');
        }else{
            //模板消息
            $where = "`cid`={$info['cid']} AND `area_id`={$info['address_area_id']} AND (ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$info['address_lat']}*PI()/180-`lat`*PI()/180)/2),2)+COS({$info['address_lat']}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$info['address_lng']}*PI()/180-`lng`*PI()/180)/2),2)))*1000) <= `radius`*1000 OR `radius` = 0) AND `uid` != {$this->_uid} ";
            $plist = D("Service_provider_category")->where($where)->field('uid,cat_name')->select();

            // 多维数组去重复
            foreach ($plist as $v){
                $temp[] = serialize($v);
            }
            $temp=array_unique($temp); //去掉重复的字符串,也就是重复的数组
            foreach ($temp as $k => $v){
                $temp[$k]=unserialize($v); //再将拆开的数组重新组装
            }
            $plist = $temp;

            if($plist){
                foreach ($plist as $key => $value) {
                    $user_info = D("User")->where(array('uid'=>$value['uid']))->field('uid,openid,nickname')->find();
                    if ($user_info['openid']) {
                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                        $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=detail_special&publish_id='.$publish_id;
//                        $model->sendTempMsg('OPENTM406638907',
//                            array('href' => $href,
//                                'wecha_id' => $user_info['openid'],
//                                'first' => $user_info['nickname'] . '您好！',
//                                'keyword1' => '用户发布了一个符合您的“'.$value['cat_name'].'”需求，配送费为'.$buyData['total_price'].'，请及时接单',
//                                'keyword2' => date('Y年m月d日 H:i:s'),
//                                'remark' => '请您及时处理！'));

                        $model->sendTempMsg('TM00017',
                            array('href' => $href,
                                'wecha_id' => $user_info['openid'],
                                'first' => '平台用户发布了一个符合您的“'.$value['cat_name'].'”需求，请及时接单！',
                                'OrderSn' => $info['order_sn'],
                                'OrderStatus' => '待处理',
                                'remark' => '请您及时处理！'));
                    }
                }
            }
        }

        // D('User')->where(array('uid'=>$this->user_session['uid']))->setDec('now_money',$_POST['total_price']);
        // D('User_money_list')->add_row($this->user_session['uid'],2,$_POST['total_price'],"支付帮我买服务费 ".$_POST['total_price']." 元",true,4,$res);

        D('Service_offer_record')->data(array('offer_id'=>0,'publish_id'=>$publish_id,'add_time'=>time(),'remarks'=>'订单支付成功'))->add();

        $now_user = D('User')->get_user($this->_uid);
        $publishInfo = D("Service_user_publish")->where(array('publish_id' => $publish_id))->field('publish_id,uid,cid,order_sn')->find();
        if ($now_user['openid']) {
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $publish_id;
//            $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'keyword1' => '您发布的需求已经完成支付，请等待接单。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时查看！'));
            // 帮买帮送修改  待办事项通知 模板 为 订单状态更新 模板
            $model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'OrderSn' => $publishInfo['order_sn'], 'OrderStatus' =>'您发布的需求已经完成支付，请查看。', 'remark' => date('Y.m.d H:i:s')));
        }
        // 进行极光App推送
        if ($now_user) {
            if (!$publishInfo['order_sn']) $publishInfo['order_sn'] = $publish_id;
            import('@.ORG.Apppush');
            $order['status'] = 2;
            $order['order_id'] = $publish_id;
            $order['order_sn'] = $publishInfo['order_sn'];
            $order['uid'] = $this->_uid;
            $order['catgory_type'] = $publishInfo['catgory_type'];
            $apppush = new Apppush();
            $apppush->send($order, 'publish');
        }
    }




    // 图片上传
    public function up_img(){
        if (!$this->_uid) {
            $this->returnCode('20044013');
        }

        if ($_FILES['imgFile']['error'] != 4) {
            $image = D('Image')->handle($this->_uid, 'yuedan', 0, array('size' => 4), false);
            if ($image['error']) {
                $this->returnCode('20140052');
            } else {
                exit(json_encode(array('errorCode' => 0,'errorMsg'=>'success', 'result' =>$this->config['site_url'].$image['url']['file'])));
            }
        } else {
            $this->returnCode('20140051');
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


    // 计算两个收货地址的距离（直线）帮我送
    public function ajax_distance(){
        //计算配送距离
        $distance = 0;
        if (C('config.service_is_riding_distance') && C('config.baidu_map_ak')) {
            import('@.ORG.longlat');
            $longlat_class = new longlat();
            $distance = $longlat_class->getRidingDistance($_POST['start_lat'],$_POST['start_lng'],$_POST['end_lat'],$_POST['end_lng']);
            if ($distance == -1) {
                $this->returnCode('50000008');
            }
        }
        $distance || $distance = getDistance($_POST['start_lat'],$_POST['start_lng'],$_POST['end_lat'],$_POST['end_lng']);
        $distance = $distance / 1000;

        exit(json_encode(array('destance_sum'=>$distance)));
    }
}
?>
