<?php

/*
 * 个人需求发布
 *
 */

class ServiceAction extends BaseAction {
    // 防止重复操作退款
    protected $user_order_refund;
	//首页
    public function index(){
        // 轮播
        $service_index_lunbo = D('Adver')->get_adver_by_key('service_index_lunbo',5);
        $this->assign('service_index_lunbo',$service_index_lunbo);

    	$catListBak = D('Service_category')->where(array('cat_status'=>1))->order('cat_sort desc,cid asc')->select();
    	$catList = array();
    	foreach ($catListBak as $key => $value) {
    		if($value['fcid'] == 0){
    			$catList [$value['cid']]= $value;
    		}
    	}
    	foreach ($catListBak as $k => $v) {
    		if($v['fcid'] != 0){
    			$catList [$v['fcid']]['catList'][$v['cid']]= $v;
    		}
    	}
		$this->assign('catList',$catList);

        //顶部广告
        $wap_index_top_adver = D('Adver')->get_adver_by_key('wap_index_top',5);
        $this->assign('wap_index_top_adver',$wap_index_top_adver);

        $this->display();
    }

    // 用户发布需求详情
    public function publish_detail(){

        //得到所有城市并以城市首拼排序
        $database_area = D('Area');
        $all_city = S('all_city_address');
        if(empty($all_city)){
            $database_field = '`area_id`,`area_name`,`area_url`,`first_pinyin`,`is_hot`';
            $condition_all_city['area_type'] = 2;
            $all_city_old = $database_area->field($database_field)->where($condition_all_city)->order('`first_pinyin` ASC,`area_id` ASC')->select();
            foreach($all_city_old as $key=>$value){
                //首拼转成大写
                if(!empty($value['first_pinyin'])){
                    $first_pinyin = strtoupper($value['first_pinyin']);
                    $all_city[$first_pinyin][] = $value;
                }
            }
            S('all_city_address',$all_city);
        }
        $this->assign('all_city',$all_city);
        // dump($all_city);

        if (empty($_GET['cid']) && !empty($_GET['type'])) {
            $this->redirect('Service/index');
        }

        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        if($this->config['user_is_real_name'] == 1){
            $user_authentication = D("User_authentication")->where(array('uid'=>$this->user_session['uid']))->field('authentication_id,authentication_status,uid')->find();
            if(empty($user_authentication)){
                $this->error_tips('请先实名认证再进行发布',U('My/authentication'));
            } elseif($user_authentication['authentication_status'] != 1){
                $this->redirect(U('My/authentication_index'));
            }
        }

        $database_Service_category = D('Service_category');
        $condition_now_Service_category['cid'] = intval($_GET['cid']);
        $now_category = $database_Service_category->field(true)->where($condition_now_Service_category)->find();
        if (empty($now_category)) {
            $this->error_tips('没有找到该分类信息！');
        }
        if (!empty($now_category['cat_field'])) {
            $now_category['cat_field'] = unserialize($now_category['cat_field']);
        }else if($now_category['type'] == 1){
            $this->error_tips('后台暂未完善此分类，无法发布。');
        }
        $cat_field_count = count($now_category['cat_field']);

        // dump($now_category['cat_field']);
        // dump($now_category);
        $this->assign('now_category', $now_category);
        $this->assign('cat_field_count', $cat_field_count);

        $adress_list = D('User_adress')->get_adress_list($this->user_session['uid']);
        foreach ($adress_list as $key => $value) {
            if($value['adress_id'] == $_GET['adress_id']){
                $addressInfo = $value;
            }elseif($value['default'] == 1){
                $addressInfo = $value;
            }
        }
        $this->assign('addressInfo', $addressInfo);
        $this->assign('adress_list', $adress_list);


        // 判断是否有今天数据
        $weekarray=array("日","一","二","三","四","五","六");

        // 统一默认获取一小时后的时间 即为配送最早为一小时后（如果有配置去配置时间）
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
        //客户需求为上线功能 可删除
// $delivery_times = explode('-', $this->config['service_delivery_time']);
// $delivery_times2 = explode('-', $this->config['service_delivery_time2']);
        // string(5) "08:20"
// $aa = explode(':', $delivery_times[0]);
// dump($aa);
// if((date("H")<=intval($delivery_times[0]) && date("H")>= intval($delivery_times[1])) || (date("H")<=intval($delivery_times2[0]) && date("H")>= intval($delivery_times2[1]))){
//     $this->error_tips('我们的配送员下班了',U('My/authentication'));
// }
        foreach ($time as $key => $value) {
            ksort($value);
            if(1 != $is_today) {
                $time_key =  $key - 1;
            } else {
                $time_key = $key;
            }
            foreach ($value as $kk => &$vv) {
                foreach ($vv as $kkk => $vvv) {
                    $timeList[$time_key][] = $vvv;
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
                // $daysList[] =  "周".$weekarray[date("w",strtotime("+".$d." day"))];
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
        $this->assign('config_time',$config_time);

        $this->assign('daysList',$daysList);
        $this->assign('timeList',$timeList);


        if($now_category['type'] == 2){
            if($_GET['adress_id']){
                $temp_buy_data = $_SESSION['temp_buy_data'];

                $temp_buy_data['img'] = array_filter(explode(';', $temp_buy_data['img']));
                $this->assign('temp_buy_data',$temp_buy_data);
            }else{
                $_SESSION['temp_buy_data'] = '';
            }
            // dump($temp_buy_data);
            $this->display('publish_detail_buy');
        }elseif ($now_category['type'] == 3) {
            // temp_give_data
            // $temp_give_data = $_SESSION['temp_give_data'];
            // $temp_give_data['img'] = array_filter(explode(';', $temp_give_data['img']));
            // $this->assign('temp_give_data',$temp_give_data);

            if($_GET['adress_id']){
                $temp_give_data = $_SESSION['temp_give_data'];
                $temp_give_data['img'] = array_filter(explode(';', $temp_give_data['img']));
                $this->assign('temp_give_data',$temp_give_data);
            }else{
                $_SESSION['temp_give_data'] = '';
            }
            $this->display('publish_detail_give');
        }else{
            $this->display();
        }
    }


    public function publish_buy_data(){

        $userInfo = D('User')->where(array('uid'=>$this->user_session['uid']))->field('now_money')->find();

        if($_POST['buy_type'] == 2){
            $data['address'] = $_POST['start_adress_detail'];
            $data['address_lng'] = $_POST['start_adress_lng'];
            $data['address_lat'] = $_POST['start_adress_lat'];
            $data['address_area_id'] = $_POST['start_adress_area_id'];
            $data['province_id'] = $_POST['start_province_id'];
            $data['city_id'] = $_POST['start_city_id'];
            $data['area_id'] = $_POST['start_area_id'];
			
			if(empty($_POST['destance_sum']) || $_POST['destance_sum'] == '0.00'){
				exit(json_encode(array('error'=>2,'msg'=>'距离计算失败，或没有配送距离，无法提交。请重试')));
			}
        }else{
            $data['address'] = $_POST['end_adress_detail'];
            $data['address_lng'] = $_POST['end_adress_lng'];
            $data['address_lat'] = $_POST['end_adress_lat'];
            $data['address_area_id'] = $_POST['end_adress_area_id'];
            $data['province_id'] = $_POST['end_province_id'];
            $data['city_id'] = $_POST['end_city_id'];
            $data['area_id'] = $_POST['end_area_id'];
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
        $data['uid'] = $this->user_session['uid'];
        $data['status'] = 1;
        $data['catgory_type'] = 2;
        $data['destance_sum'] = $_POST['destance_sum'];
        $nowtime = date("mdHis");
        // $data['order_sn'] = $nowtime.sprintf("%04d",$this->user_session['uid']);
        $data['order_sn'] = $nowtime.rand(10,99).sprintf("%08d",$this->user_session['uid']);
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
            $buyData['basic_distance_price'] =  $_POST['basic_distance_price'];
            $buyData['distance_price'] =  $_POST['distance_price'];
            $buyData['tip_price'] =  $_POST['tip_price'];
            $buyData['total_price'] =  $_POST['total_price'];
            $buyData['img'] =  $_POST['img'];
            $buy_res = D('Service_user_publish_buy')->data($buyData)->add();
            if(!$buy_res){
                D('Service_user_publish')->where(array('publish_id'=>$res))->delete();
                exit(json_encode(array('error'=>2,'msg'=>'数据添加异常请重试！')));
            }

            $pay_order_param = array(
                'business_type' => 'service',
                'business_id' => $res,
                'order_name' => '帮我买订单',
                'uid' => $this->user_session['uid'],
                'total_money' => $_POST['total_price'],
                'wx_cheap' => 0,
            );
            $plat_order_result = D('Plat_order')->add_order($pay_order_param);
            if ($plat_order_result['error_code']) {
                $this->error_tips($plat_order_result['error_msg']);
            } else {
//                redirect(U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')));
                exit(json_encode(array('error'=>1,'msg'=>'需求已发布,请去支付!','order_id'=>$res,'url'=>U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')))));
            }
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'发布失败请重试！')));
        }


    }

    public function temp_buy_data(){
        $_SESSION['temp_buy_data'] = $_POST;
        exit(json_encode(array('error'=>1,'msg'=>'存储成功')));
    }

    public function publish_give_data(){

        if(IS_POST){
            // $_POST['img'] = array_filter(explode(';', $_POST['img']));
            // $userInfo = D('User')->where(array('uid'=>$this->user_session['uid']))->field('now_money')->find();

            $data['address'] = $_POST['start_adress_detail'];
            $data['address_lng'] = $_POST['start_adress_lng'];
            $data['address_lat'] = $_POST['start_adress_lat'];
            $data['address_area_id'] = $_POST['start_adress_area_id'];
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
            $data['uid'] = $this->user_session['uid'];
            $data['catgory_type'] = 3;
            $data['status'] = 1;
            $data['destance_sum'] = $_POST['destance_sum'];
			
			if(empty($_POST['destance_sum']) || $_POST['destance_sum'] == '0.00'){
				exit(json_encode(array('error'=>2,'msg'=>'距离计算失败，或没有配送距离，无法提交。请重试')));
			}
			
            $nowtime = date("mdHis");
            // $data['order_sn'] = $nowtime.sprintf("%04d",$this->user_session['uid']);
            $data['order_sn'] = $nowtime.rand(10,99).sprintf("%08d",$this->user_session['uid']);
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
                $giveData['estimate_goods_price'] =  $_POST['estimate_goods_price'];

                $giveData['tip_price'] =  $_POST['tip_price'];
                $giveData['remarks'] = $this->filterEmoji2($_POST['remarks']);

                $giveData['basic_distance_price'] =  $_POST['basic_distance_price'];
                $giveData['distance_price'] =  $_POST['distance_price'];
                $giveData['weight_price'] =  $_POST['weight_price'];

                $giveData['total_price'] =  $_POST['total_price'];
                $giveData['addtime'] =  time();
                $giveData['uid'] =  $this->user_session['uid'];
                $give_res = D("Service_user_publish_give")->data($giveData)->add();

                if(!$give_res){
                    D('Service_user_publish')->where(array('publish_id'=>$res))->delete();
                    exit(json_encode(array('error'=>2,'msg'=>'数据添加异常请重试！')));
                }


                $pay_order_param = array(
                    'business_type' => 'service',
                    'business_id' => $res,
                    'order_name' => '帮我送订单',
                    'uid' => $this->user_session['uid'],
                    'total_money' => $_POST['total_price'],
                    'wx_cheap' => 0,
                );
                $plat_order_result = D('Plat_order')->add_order($pay_order_param);
                if ($plat_order_result['error_code']) {
                    $this->error_tips($plat_order_result['error_msg']);
                } else {
//                redirect(U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')));
                    exit(json_encode(array('error'=>1,'msg'=>'需求已发布,请去支付!','order_id'=>$res,'url'=>U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')))));
                }
                die;


                //扣款写记录
                // D('User')->where(array('uid'=>$this->user_session['uid']))->setDec('now_money',$_POST['total_price']);
                // D('User_money_list')->add_row($this->user_session['uid'],2,$_POST['total_price'],"支付帮我送服务费 ".$_POST['total_price']." 元",true,4,$res);
                // //写入订单变化记录
                // D('Service_offer_record')->data(array('offer_id'=>0,'publish_id'=>$res,'add_time'=>time(),'remarks'=>'订单支付成功'))->add();
                // if ($this->user_session['openid']) {
                //     $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                //     $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=need_list';
                //     $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => $this->user_session['nickname'] . '您好！', 'keyword1' => '您发布的'.$value['cat_name'].'需求已经完成支付，请等待接单。', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时查看！'));
                // }
                // if($deliver == true){
                //     //推送配送订单，
                //     D('Deliver_supply')->saveOrder($res, null,3,'service_user_publish');
                // }else{
                //     //模板消息
                //     $where = "`cid`={$data['cid']} AND `area_id`={$data['address_area_id']} AND (ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$data['address_lat']}*PI()/180-`lat`*PI()/180)/2),2)+COS({$data['address_lat']}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$data['address_lng']}*PI()/180-`lng`*PI()/180)/2),2)))*1000) <= `radius`*1000 OR `radius` = 0) AND `uid` != {$this->user_session['uid']}";
                //     $plist = D("Service_provider_category")->where($where)->field('uid,cat_name')->select();

                //     // 多维数组去重复
                //     foreach ($plist as $v){
                //         $temp[] = serialize($v);
                //     }
                //     $temp=array_unique($temp); //去掉重复的字符串,也就是重复的数组
                //     foreach ($temp as $k => $v){
                //         $temp[$k]=unserialize($v); //再将拆开的数组重新组装
                //     }
                //     $plist = $temp;

                //     if($plist){
                //         foreach ($plist as $key => $value) {
                //             $user_info = D("User")->where(array('uid'=>$value['uid']))->field('uid,openid,nickname')->find();
                //             if ($user_info['openid']) {
                //                 $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                //                 $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=detail_special&publish_id='.$res;
                //                 $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => $user_info['nickname'] . '您好！', 'keyword1' => '用户发布了一个符合您的 “'.$value['cat_name'].'” 需求，配送费为'.$buyData['total_price'].'，请及时接单', 'keyword2' => date('Y年m月d日 H:i:s'),  'remark' => '请您及时处理！'));
                //             }
                //         }
                //     }
                // }
                exit(json_encode(array('error'=>1,'msg'=>'需求已发布,请去支付！','order_id'=>$res)));
            }else{
                exit(json_encode(array('error'=>2,'msg'=>'发布失败请重试')));
            }
        }
    }

    public function temp_give_data(){
        $_SESSION['temp_give_data'] = $_POST;
        exit(json_encode(array('error'=>1,'msg'=>'存储成功')));
    }

    public  function publish_data(){
        if(IS_POST){
            $data = array();
            foreach ($_POST as $key => $value) {
                if (empty($value['type'])) continue;
                if(!$data['address']){
                    if($value['type'] == 6){
                        $data['address'] = $value['value']['address'];
                        $data['address_lng'] = $value['value']['address_lng'];
                        $data['address_lat'] = $value['value']['address_lat'];
                        $data['address_area_id'] = $value['value']['address_area_id'];
                        $data['address_area_name'] = $value['value']['address_area_name'];
                        // break;
                    }else if($value['type'] == 7){
                        $data['address'] = $value['value']['address_start'];
                        $data['address_lng'] = $value['value']['address_start_lng'];
                        $data['address_lat'] = $value['value']['address_start_lat'];
                        $data['address_area_id'] = $value['value']['address_start_area_id'];
                        $data['address_area_name'] = $value['value']['address_start_area_name'];
                    }
                }

                if($value['type'] == 1 || $value['type'] == 5){
                    $value['value'] = $this->filterEmoji2($value['value']);
                    $_POST[$key]['value'] = $value['value'];
                }

            }

            $data['add_time'] = time();
            $data['cid'] = $_POST['cid'];
            unset($_POST['cid']);
            $data['cat_field'] = serialize($_POST);
            $data['uid'] = $this->user_session['uid'];

            $res = D('Service_user_publish')->data($data)->add();
            if($res){
                //模板消息
                $where = "`cid`={$data['cid']} AND `area_id`={$data['address_area_id']} AND (ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$data['address_lat']}*PI()/180-`lat`*PI()/180)/2),2)+COS({$data['address_lat']}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$data['address_lng']}*PI()/180-`lng`*PI()/180)/2),2)))*1000) <= `radius`*1000 OR `radius` = 0) ";
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
                            $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=detail&publish_id='.$res;
//                            $model->sendTempMsg('OPENTM406638907',
//                                array('href' => $href,
//                                    'wecha_id' => $user_info['openid'],
//                                    'first' => $user_info['nickname'] . '您好！',
//                                    'keyword1' => '用户发布了一个符合您的 “'.$value['cat_name'].'” 需求，请及时报价',
//                                    'keyword2' => date('Y年m月d日 H:i:s'),
//                                    'remark' => '请您及时处理！'));

                            $model->sendTempMsg('TM00017',
                                array('href' => $href,
                                    'wecha_id' => $user_info['openid'],
                                    'first' => '平台用户发布了一个符合您的“'.$value['cat_name'].'”需求，请及时报价！',
                                    'OrderSn' => $res,
                                    'OrderStatus' => '待处理',
                                    'remark' => '请您及时处理！'));
                        }
                    }
                }

                $this->success_tips('发布成功！', U('Service/need_list'));
            }else{
                $this->error_tips('发布失败请重试');
            }
        }
    }


    function filterEmoji($str) {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);
        return $str;
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




    // 用户选择分类列表
    public function cat_list(){
    	$catListBak = D('Service_category')->where(array('cat_status'=>1))->order('cat_sort desc,cid asc')->select();
    	$catList = array();
    	foreach ($catListBak as $key => $value) {
    		if($value['fcid'] == 0){
    			$catList [$value['cid']]= $value;
    		}
    	}
    	foreach ($catListBak as $k => $v) {
    		if($v['fcid'] != 0){
    			$catList [$v['fcid']]['catList'][$v['cid']]= $v;
    		}
    	}
		// dump($catList);
		$this->assign('catList',$catList);
        $this->display();
    }

    // 商家选择分类
    public function search_cate(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        if($this->config['provider_is_real_name'] == 1){
            $service_authentication = D("Service_authentication")->where(array('uid'=>$this->user_session['uid'],'authentication_status'=>2))->field('authentication_id,authentication_status,uid')->find();
            if(empty($service_authentication)){
                $this->error_tips('请先通过认证再进行发布',U('Service/authentication'));
            }
        }


    	$catListBak = D('Service_category')->where(array('cat_status'=>1))->order('cat_sort desc,cid asc')->select();
    	$catList = array();
    	foreach ($catListBak as $key => $value) {
    		if($value['fcid'] == 0){
    			$catList [$value['cid']]= $value;
    		}
    	}
    	foreach ($catListBak as $k => $v) {
    		if($v['fcid'] != 0){
    			$catList [$v['fcid']]['catList'][$v['cid']]= $v;
    		}
    	}
		// dump($catList);
		$this->assign('catList',$catList);
    	$this->display();
    }
    //发布服务
    public function service_publish(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
    	if(IS_POST){
    		$_SESSION['service_publish'] = null;
    		$catList = D('Service_category')->field(true)->where(array('cid'=>array('in',implode(',',$_POST['selectCateArr']))))->select();
    		$this->assign('catList',$catList);
    		$_SESSION['service_publish']['catList'] = $catList;
    	}else if($_SESSION['service_publish']['catList']){
    		$_SESSION['service_publish']['sname'] = $_GET['sname'];
    		$_SESSION['service_publish']['lng'] = $_GET['lng'];
    		$_SESSION['service_publish']['lat'] = $_GET['lat'];
            $_SESSION['service_publish']['area_id'] = $_GET['area_id'];
            $_SESSION['service_publish']['area_name'] = $_GET['area_name'];
            $adressInfo = D('Service_provider_address')->where(array('lng'=>$_GET['lng'],'lat'=>$_GET['lat'],'uid'=>$this->user_session['uid']))->find();
            if($adressInfo){
                $_SESSION['service_publish']['paid'] = $adressInfo['paid'];
            }else{
                $_SESSION['service_publish']['paid'] = '';
            }

    	}else{
    		$_SESSION['service_publish'] = null;
    		$this->error_tips('传递参数有误！',U('Service/search_cate'));
    	}
    	// dump($_SESSION['service_publish']);
    	$this->assign('service_info',$_SESSION['service_publish']);
    	$this->display();
    }

    // 发布服务数据
    public function service_publish_data(){
    	if(empty($this->user_session['uid'])){
    		$this->error_tips('请先登录再进行操作',U('Service/service_publish'));
    	}
    	if(empty($_POST['sname']) && empty($_POST['sname']) && empty($_POST['sname'])){
    		$this->error_tips('服务地址有误请重试',U('Service/service_publish'));
    	}
    	if(!is_array($_POST['cat'])){
    		$this->error_tips('没有添加任何分类',U('Service/service_publish'));
    	}
    	$info = D('Service_provider')->where(array('uid'=>$this->user_session['uid']))->find();
    	if(is_array($info)){
    		$pid = $info['pid'];
    	}else{
    		$data['uid']=$this->user_session['uid'];
    		$data['phone']=$this->user_session['phone'];
    		$data['name']=$this->user_session['nickname'];
    		$data['avatar']=$this->user_session['avatar'];
    		$res = D('Service_provider')->data($data)->add();
    		if($res){
    			$pid = $res;
    		}else{
    			$this->error_tips('服务商添加异常',U('Service/service_publish'));
    		}
    	}


        if($_POST['paid']){
            $paid = $_POST['paid'];
        }else{
            $address_data['sname'] = $_POST['sname'];
            $address_data['lng'] = $_POST['lng'];
            $address_data['lat'] = $_POST['lat'];
            $address_data['area_id'] = $_POST['area_id'];
            $address_data['area_name'] = $_POST['area_name'];
            $address_data['add_time'] = time();
            $address_data['uid']=$this->user_session['uid'];
            $address_data['pid'] = $pid;
            $paid = D('Service_provider_address')->data($address_data)->add();
            if(empty($paid)){
                $this->error_tips('数据添加异常',U('Service/service_publish'));
            }
        }

    	foreach ($_POST['cat'] as $key => $value) {
            $provider_category_data = $value;
            $provider_category_data['add_time'] = time();
            $provider_category_data['sname'] = $_POST['sname'];
            $provider_category_data['lng'] = $_POST['lng'];
            $provider_category_data['lat'] = $_POST['lat'];
            $provider_category_data['area_id'] = $_POST['area_id'];
            $provider_category_data['area_name'] = $_POST['area_name'];
            $provider_category_data['pid'] = $pid;
            $provider_category_data['paid'] = $paid;
            $provider_category_data['uid']=$this->user_session['uid'];
            $cat_info = D('Service_provider_category')->where(array('uid'=>$this->user_session['uid'],'cid'=>$value['cid'],'paid'=>$paid))->find();
            if($cat_info){
                D('Service_provider_category')->where(array('uid'=>$this->user_session['uid'],'cid'=>$value['cid'],'paid'=>$paid))->data($provider_category_data)->save();
            }else{
                D('Service_provider_category')->data($provider_category_data)->add();
            }
    	}
    	$_SESSION['service_publish'] = null;
    	$this->success_tips('添加成功！',U('Service/provider_home'));
    }

    // 修改服务信息
    public function edit_service_publish(){
    	$addresInfo = D("Service_provider_address")->where(array('paid'=>$_GET['paid']))->find();
    	$catList = D('Service_provider_category')->where(array('paid'=>$addresInfo['paid']))->select();
    	if($_GET['sname']){
    		$addresInfo['sname'] = $_GET['sname'];
    		$addresInfo['lng'] = $_GET['lng'];
    		$addresInfo['lat'] = $_GET['lat'];
            $addresInfo['area_id'] = $_GET['area_id'];
            $addresInfo['area_name'] = $_GET['area_name'];
    	}
        // dump($addresInfo);
    	$this->assign('addresInfo',$addresInfo);
    	$this->assign('catList',$catList);
        // dump($catList);
    	$this->display();
    }

    public function edit_service_publish_data(){

        if(empty($this->user_session['uid'])){
            $this->error_tips('请先登录再进行操作',U('Service/service_publish'));
        }
        if(empty($_POST['sname']) && empty($_POST['sname']) && empty($_POST['sname'])){
            $this->error_tips('服务地址有误请重试',U('Service/service_publish'));
        }
        if(!is_array($_POST['cat'])){
            $this->error_tips('没有添加任何分类',U('Service/service_publish'));
        }

        $address_data['sname'] = $_POST['sname'];
        $address_data['lng'] = $_POST['lng'];
        $address_data['lat'] = $_POST['lat'];
        $address_data['area_id'] = $_POST['area_id'];
        $address_data['area_name'] = $_POST['area_name'];
        // $address_data['add_time'] = time();
        // $address_data['uid']=$this->user_session['uid'];
        // $address_data['pid'] = $pid;
        $paid = D('Service_provider_address')->where(array('paid'=>intval($_POST['paid']),'uid'=>$this->user_session['uid']))->data($address_data)->save();
        // if(empty($paid)){
        //     $this->error_tips('数据修改异常',U('Service/service_publish'));
        // }
        foreach ($_POST['cat'] as $key => $value) {
            $provider_category_data = $value;

            // $provider_category_data['add_time'] = time();
            $provider_category_data['sname'] = $_POST['sname'];
            $provider_category_data['lng'] = $_POST['lng'];
            $provider_category_data['lat'] = $_POST['lat'];
            $provider_category_data['area_id'] = $_POST['area_id'];
            $provider_category_data['area_name'] = $_POST['area_name'];
            // $provider_category_data['pid'] = $pid;
            // $provider_category_data['paid'] = $paid;
            // $provider_category_data['uid']=$this->user_session['uid'];

            D('Service_provider_category')->where(array('sp_cid'=>$value['sp_cid'],'uid'=>$this->user_session['uid'],'paid'=>intval($_POST['paid'])))->data($provider_category_data)->save();
        }
        $_SESSION['service_publish'] = null;
        $this->success_tips('修改成功！',U('Service/editdesc'));
    }

    public function service_cat_del(){
        $catInfo = D('Service_provider_category')->where(array('cid'=>$_POST['cid'],'paid'=>$_POST['paid'],'uid'=>$this->user_session['uid']))->find();
        if(D('Service_provider_category')->where(array('cid'=>$_POST['cid'],'uid'=>$this->user_session['uid']))->delete()){
            $paidList = D('Service_provider_category')->where(array('paid'=>$catInfo['paid'],'uid'=>$this->user_session['uid']))->find();
            if(empty($paidList)){
                D('Service_provider_address')->where(array('paid'=>$catInfo['paid'],'uid'=>$this->user_session['uid']))->delete();
            }
            exit(json_encode(array('error'=>1,'msg'=>'删除成功。')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'数据异常，删除失败。')));
        }
    }

    // 修改地址地图adres_map
    public function edit_adres_map(){
    	if(empty($_GET['paid'])){
    		$this->error_tips('数据异常请重试！');
    	}
    	$this->display();
    }

    // 商户主页
    public function provider_home(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
    	$providerInfo = D('Service_provider')->where(array('uid'=>$this->user_session['uid']))->find();
        if(!is_array($providerInfo)){
            // $this->error_tips('您暂时还不是服务商，即将至发布服务页面',U('Service/search_cate'));
            $this->redirect('Service/search_cate');
            die;
        }
        if (empty($providerInfo['avatar'])) {
            // 没有头像取默认头像
            $providerInfo['avatar'] = C('config.site_url') . '/static/default.png';
        }
    	$this->assign('providerInfo',$providerInfo);
    	$imgList = D('Service_provider_img')->where(array('pid'=>$providerInfo['pid']))->select();
    	$this->assign('imgList',$imgList);

    	$addresList = D("Service_provider_address")->where(array('pid'=>$providerInfo['pid']))->select();
    	foreach ($addresList as $key => $value) {
    		$addresList[$key]['catList'] = D('service_provider_category')->where(array('paid'=>$value['paid'],'pid'=>$providerInfo['pid']))->select();
    	}
    	$this->assign('addresList',$addresList);



        $offer_evaluate_list = D('')->table(array(C('DB_PREFIX').'service_offer_evaluate'=>'soe', C('DB_PREFIX').'user'=>'u'))->where("soe.p_uid= '".$this->user_session['uid']."' AND soe.uid = u.uid")->field('soe.*,u.nickname,u.avatar')->order('soe.add_time desc')->select();

        // $offer_evaluate_list = D('Service_offer_evaluate')->where(array('p_uid'=>$this->user_session['uid']))->order('add_time desc')->select();
        $this->assign('offer_evaluate_list',$offer_evaluate_list);
    	// dump($offer_evaluate_list);
    	$this->display();
    }

    public function provider_info(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
            die;
        }
        $providerInfo = D('Service_provider')->where(array('uid'=>$_GET['puid']))->find();
        if(!is_array($providerInfo)){
            $this->error_tips('数据异常请重试！',U('Service/search_cate'));
            die;
        }
        $this->assign('providerInfo',$providerInfo);
        $imgList = D('Service_provider_img')->where(array('pid'=>$providerInfo['pid']))->select();
        $this->assign('imgList',$imgList);

        $addresList = D("Service_provider_address")->where(array('pid'=>$providerInfo['pid']))->select();
        foreach ($addresList as $key => $value) {
            $addresList[$key]['catList'] = D('service_provider_category')->where(array('paid'=>$value['paid'],'pid'=>$providerInfo['pid']))->select();
        }
        $this->assign('addresList',$addresList);

        $offer_evaluate_list = D('')->table(array(C('DB_PREFIX').'service_offer_evaluate'=>'soe', C('DB_PREFIX').'user'=>'u'))->where("soe.p_uid= '".$_GET['puid']."' AND soe.uid = u.uid")->field('soe.*,u.nickname,u.avatar')->order('soe.add_time desc')->select();
        $this->assign('offer_evaluate_list',$offer_evaluate_list);
        $this->display();
    }

    // 商户相册
    public function editphoto(){
    	$providerInfo = D('Service_provider')->where(array('uid'=>$this->user_session['uid']))->find();
    	if(IS_POST){
    		D('Service_provider_img')->where(array('pid'=>$providerInfo['pid']))->delete();
    		foreach ($_POST['img'] as $key => $value) {
    			$data = array();
    			$data['img_url'] = $value;
    			$data['pid'] = $providerInfo['pid'];
    			D('Service_provider_img')->data($data)->add();
    		}
    		$this->success_tips('保存成功',U('Service/provider_home'));
    	}
    	$imgList = D('Service_provider_img')->where(array('pid'=>$providerInfo['pid']))->select();
    	$this->assign('imgList',$imgList);
    	$this->display();
    }

    // 修改头像名称
    public function editheader(){
    	if(IS_POST){
    		$data['name'] = $_POST['name'];
    		$data['avatar'] = $_POST['avatar'];
    		$data['phone'] = $_POST['phone'];
     		$res = D('Service_provider')->where(array('uid'=>$this->user_session['uid'],'pid'=>intval($_POST['pid'])))->data($data)->save();
     		$this->success_tips('保存成功',U('Service/provider_home'));
    	}else{
    		$providerInfo = D('Service_provider')->where(array('uid'=>$this->user_session['uid']))->find();
	    	$this->assign('providerInfo',$providerInfo);
	    	$this->display();
    	}

    }

    // 修改备注
    public function editdesc(){
    	if(IS_POST){
    		$data['describe'] = $_POST['describe'];
     		$res = D('Service_provider')->where(array('uid'=>$this->user_session['uid'],'pid'=>intval($_POST['pid'])))->data($data)->save();
     		$this->success_tips('保存成功',U('Service/provider_home'));
    	}else{
    		$providerInfo = D('Service_provider')->where(array('uid'=>$this->user_session['uid']))->find();
	    	$this->assign('providerInfo',$providerInfo);

	    	$addresList = D("Service_provider_address")->where(array('pid'=>$providerInfo['pid']))->select();
	    	foreach ($addresList as $key => $value) {
	    		$addresList[$key]['catList'] = D('Service_provider_category')->where(array('paid'=>$value['paid'],'pid'=>$providerInfo['pid']))->select();
	    	}
	    	$this->assign('addresList',$addresList);
	    	$this->display();
    	}
    }

    // 修改地址
    public function editaddress(){

    	if(IS_POST){
    		$data['sname'] = $_POST['sname'];
    		$data['lng'] = $_POST['lng'];
    		$data['lat'] = $_POST['lat'];
     		$res = D('Service_provider')->where(array('uid'=>$this->user_session['uid'],'pid'=>intval($_POST['pid'])))->data($data)->save();
     		// if($res){
     		// 	$this->success_tips('保存成功',U('Service/provider_home'));
     		// }else{
     		// 	$this->error_tips('保存失败请重试');
     		// }
            $this->success_tips('保存成功',U('Service/provider_home'));
    	}

    	$providerInfo = D('Service_provider')->where(array('uid'=>$this->user_session['uid']))->find();
    	if($_GET['sname']){
    		$providerInfo['sname'] = $_GET['sname'];
    		$providerInfo['lng'] = $_GET['lng'];
    		$providerInfo['lat'] = $_GET['lat'];
    	}
    	$this->assign('providerInfo',$providerInfo);
    	$this->display();
    }
    // 修改地址地图
    public function editaddressdata(){
    	$this->display();
    }


    /* 地图 */
	public function adres_map(){
		$this->display();
	}

	// 上传图片
	public function ajax_upload_file(){
        if(!$_FILES){
            exit(json_encode(array('error'=>1,'msg'=>'没有选择图片')));
        }
        if($_FILES['imgFile']['error'] == 4){
            exit(json_encode(array('error'=>1,'msg'=>'没有选择图片')));
        }
        if($_FILES['imgFile']['error'] == 3){
            exit(json_encode(array('error'=>1,'msg'=>'文件只有部分被上传')));
        }
        $upload_file = D('Image')->handle($this->user_session['uid'], 'service_provider', 0, array('size' => 20), false);
        if ($upload_file['error'] == 1){
            exit(json_encode(array('error'=>1,'msg'=>'上传失败，' .$upload_file['message']. '请重试！')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'上传成功','url'=>$upload_file['url']['imgFile'])));
        }

	}

    // 我的需求列表
    public function need_list(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }

        $publishList = D('')->table(array(C('DB_PREFIX').'service_user_publish'=>'sup', C('DB_PREFIX').'service_category'=>'sc'))->where("sup.uid= '".intval($this->user_session['uid'])."' AND sup.cid = sc.cid")->field('sup.*,sc.cat_name,sc.accept_time')->order('sup.add_time desc')->limit(6)->select();
        $publishCount = D('')->table(array(C('DB_PREFIX').'service_user_publish'=>'sup', C('DB_PREFIX').'service_category'=>'sc'))->where("sup.uid= '".intval($this->user_session['uid'])."' AND sup.cid = sc.cid")->count();

        foreach ($publishList as $key => $value) {
            if($value['catgory_type'] == 1){
                $time = intval($value['add_time'])+(intval($value['accept_time'])*60*60*24);
            }else{
                $time = intval($value['add_time'])+(intval($value['accept_time'])*60*60);
            }
            if(time() > $time){
                // D('Service_user_publish')->where(array('uid'=>$this->user_session['uid'],'publish_id'=>$vvalue['publish_id']))->data(array('status'=>3))->save();
                if($value['status'] < 2){
                    $publishList[$key]['status'] = 11;
                    D('Service_user_publish')->where(array('uid'=>$this->user_session['uid'],'publish_id'=>$value['publish_id']))->data(array('status'=>11))->save();
                }
            }
            if ($time) {
                $time = date("Y-m-d H:i", $time);
            }

            $publishList[$key]['endtime'] =$time;

            $publishList[$key]['cat_field'] = unserialize($value['cat_field']);
            $offer_list = '';
            $offer_list = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_provider'=>'u'))->where("so.publish_id= '".$value['publish_id']."' AND u.uid = so.p_uid")->field('so.offer_id,so.price,u.avatar')->select();
            $publishList[$key]['offer_count'] = count($offer_list);
            $publishList[$key]['offer_list'] = $offer_list;
        }

        $this->assign('publishList',$publishList);
        $this->assign('publishCount', $publishCount);
        $this->display();
    }


    // 我的需求列表
    public function need_list_ajax(){
        $listRows = 6;
        $firstRow = $_POST['page'] * $listRows;

        $publishList = D('')->table(array(C('DB_PREFIX').'service_user_publish'=>'sup', C('DB_PREFIX').'service_category'=>'sc'))->where("sup.uid= '".intval($this->user_session['uid'])."' AND sup.cid = sc.cid")->field('sup.*,sc.cat_name,sc.accept_time')->order('sup.add_time desc')->limit($firstRow,$listRows)->select();

        if(empty($publishList)){
            exit(json_encode(array('error'=>2,'msg'=>'暂无数据！')));
        }

        foreach ($publishList as $key => $value) {
            if($value['catgory_type'] == 1){
                $time = $value['add_time']+(intval($value['accept_time'])*60*60*24);
            }else{
                $time = $value['add_time']+(intval($value['accept_time'])*60*60);
            }

            $publishList[$key]['endtime'] =$time;
            if(time() > $time){
                if($value['status'] < 2){
                    $publishList[$key]['status'] = 11;
                    D('Service_user_publish')->where(array('uid'=>$this->user_session['uid'],'publish_id'=>$value['publish_id']))->data(array('status'=>11))->save();
                }
            }

            $publishList[$key]['cat_field'] = unserialize($value['cat_field']);
            $offer_list = '';
            $offer_list = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_provider'=>'u'))->where("so.publish_id= '".$value['publish_id']."' AND u.uid = so.p_uid")->field('so.offer_id,so.price,u.avatar')->select();
            $publishList[$key]['offer_count'] = count($offer_list);
            $publishList[$key]['offer_list'] = $offer_list;
        }


        foreach ($publishList as $key => $value) {

            if($value['catgory_type'] == 1){
                if($value['status'] == 1){
                    $status = '已发布';
                }elseif ($value['status'] == 2) {
                    $status = '已付款待服务';
                }elseif ($value['status'] == 3) {
                    $status = '已服务待确认';
                }elseif ($value['status'] == 4) {
                    $status = '订单完成';
                }elseif ($value['status'] == 5) {
                    $status = '申请退款';
                }elseif ($value['status'] == 6) {
                    $status = '退款成功';
                }elseif ($value['status'] == 7) {
                    $status = '评价成功';
                }elseif ($value['status'] == 8) {
                    $status = '等待取货';
                }elseif ($value['status'] == 9) {
                    $status = '配送中';
                }elseif ($value['status'] == 10) {
                    $status = '已暂停报价';
                }elseif ($value['status'] == 11) {
                    $status = '已经过期';
                }elseif ($value['status'] == 12) {
                    $status = '过期退款';
                }elseif ($value['status'] == 13) {
                    $status = '退款失败';
                }

                $imgHtml = '';
                $html = '';
                foreach ($value['offer_list'] as $offer_k => $offer_v) {
                    if($offer_k <5){
                        $imgHtml .= '<img style="border-radius:35px; width:35px; height:35px; border:#cdefeb 1px solid;" src="{pigcms{$offer_vo.avatar}" alt="">';
                    }else{
                        $dian = '...';
                    }
                }
                if ($value['endtime']) {
                    $endTime = date("Y-m-d H:i", $value['endtime']);
                }

                $html .= '<a href="'.U('Service/price_list',array('publish_id'=>$value['publish_id'])).'">
                                <li class="link-url" data-url="shop_detail.html?order_id=8867" data-webview="true">
                                    <div class="top clr">
                                        <if condition="'.$endTime.'">
                                            <div class="fl">截至日期：' . $endTime . '</div>
                                        </if>
                                    </div>
                                    <div class="con">
                                        <div class="con_top">
                                            <p>
                                                所属分类：
                                                <span class="red">'.$value['cat_name'].'</span>
                                            </p>
                                            <p>
                                                报价人数：'.$value['offer_count'].'个
                                            </p>
                                            <p>
                                                发布时间：'.date('Y-m-d H:i:s',$value['add_time']).'
                                            </p>
                                        </div>
                                        <div class="remark">
                                            <div>
                                               '.$imgHtml.$dian.'
                                            </div>
                                            <div class="overtime">
                                                '.$status.'
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                </a>';
            }elseif ($value['catgory_type'] == 2) {
                $status = '';
                if($value['status'] == 1){
                    $status = '去支付';
                }elseif ($value['status'] == 2) {
                    $status = '待接单';
                }elseif ($value['status'] == 3) {
                    $status = '已服务';
                }elseif ($value['status'] == 4) {
                    $status = '订单完成';
                }elseif ($value['status'] == 5) {
                    $status = '申请退款';
                }elseif ($value['status'] == 6) {
                    $status = '退款成功';
                }elseif ($value['status'] == 7) {
                    $status = '评价成功';
                }elseif ($value['status'] == 8) {
                    $status = '等待取货';
                }elseif ($value['status'] == 9) {
                    $status = '配送中';
                }elseif ($value['status'] == 11) {
                    $status = '已经过期';
                }elseif ($value['status'] == 12) {
                    $status = '过期退款';
                }elseif ($value['status'] == 13) {
                    $status = '退款失败';
                }

                foreach ($value['offer_list'] as $offer_k => $offer_v) {
                    if($offer_k <5){
                        $imgHtml .= '<img style="border-radius:35px; width:35px; height:35px; border:#cdefeb 1px solid;" src="{pigcms{$offer_vo.avatar}" alt="">';
                    }
                }
                $msg = $status;
                if ($value['status'] == 1) {
                    $msg = '<a style="border: 1px solid #06C1AE; padding: 3px 10px;border-radius: 3px;color: #06C1AE;" data-id="'.$value['publish_id'].'"  onclick="payMoney(this)" href="javascript:void(0);">去支付</a>';
                }
                $style = '';
                if ($value['status'] == 2) {
                    $msg .= '&nbsp;<a style="border: 1px solid #999c9e; padding: 3px 10px;border-radius: 3px;color: #999c9e;" data-id="'.$value['publish_id'].'"  onclick="order_refund(this)" href="javascript:void(0);">取消订单</a>';
                    $style = 'style="width: 125px;"';
                }

                $html .= '<a href="'.U('Service/price_list',array('publish_id'=>$value['publish_id'])).'">
                    <li class="link-url">
                        <div class="top clr">
                            <div class="fl">截至日期：'.date('Y-m-d H:i:s',$value['endtime']).'</div>
                        </div>
                        <div class="con">
                            <div class="con_top">
                                <p>
                                    所属分类：
                                    <span class="red">'.$value['cat_name'].'</span>
                                </p>
                                <p>
                                    发布时间：'.date('Y-m-d H:i:s',$value['add_time']).'
                                </p>
                            </div>
                            <div class="remark">
                                <div>
                                    '.$imgHtml.'
                                </div>
                                <div class="overtime" '. $style .'>
                                    '.$msg.'
                                </div>
                            </div>
                        </div>
                    </li>
                </a>';

            }elseif ($value['catgory_type'] == 3) {

                if($value['status'] == 1){
                    $status = '正常';
                }elseif ($value['status'] == 2) {
                    $status = '待接单';
                }elseif ($value['status'] == 3) {
                    $status = '已服务';
                }elseif ($value['status'] == 4) {
                    $status = '订单完成';
                }elseif ($value['status'] == 5) {
                    $status = '申请退款';
                }elseif ($value['status'] == 6) {
                    $status = '退款成功';
                }elseif ($value['status'] == 7) {
                    $status = '评价成功';
                }elseif ($value['status'] == 8) {
                    $status = '等待取货';
                }elseif ($value['status'] == 9) {
                    $status = '配送中';
                }elseif ($value['status'] == 11) {
                    $status = '已经过期';
                }elseif ($value['status'] == 12) {
                    $status = '过期退款';
                }elseif ($value['status'] == 13) {
                    $status = '退款失败';
                }

                foreach ($value['offer_list'] as $offer_k => $offer_v) {
                    if($offer_k <5){
                        $imgHtml .= '<img style="border-radius:35px; width:35px; height:35px; border:#cdefeb 1px solid;" src="{pigcms{$offer_vo.avatar}" alt="">';
                    }
                }

                $msg = $status;
                if ($value['status'] == 1) {
                    $msg = '<a style="border: 1px solid #06C1AE; padding: 3px 10px;border-radius: 3px;color: #06C1AE;" data-id="'.$value['publish_id'].'"  onclick="payMoney(this)" href="javascript:void(0);">去支付</a>';
                }
                $style = '';
                if ($value['status'] == 2) {
                    $msg .= '&nbsp;<a style="border: 1px solid #999c9e; padding: 3px 10px;border-radius: 3px;color: #999c9e;" data-id="'.$value['publish_id'].'"  onclick="order_refund(this)" href="javascript:void(0);">取消订单</a>';
                    $style = 'style="width: 125px;"';
                }

                $html .= '<a href="'.U('Service/price_list',array('publish_id'=>$value['publish_id'])).'">
                    <li class="link-url"  data-webview="true">
                        <div class="top clr">
                            <div class="fl">截至日期：'.date('Y-m-d H:i:s',$value['endtime']).'</div>
                        </div>
                        <div class="con">
                            <div class="con_top">
                                <p>
                                    所属分类：
                                    <span class="red">'.$value['cat_name'].'</span>
                                </p>
                                <p>
                                    发布时间：'.date('Y-m-d H:i:s',$value['add_time']).'
                                </p>
                            </div>
                            <div class="remark">
                                <div>
                                    '.$imgHtml.'
                                </div>
                                <div class="overtime" '.$style.'>
                                    '.$msg.'
                                </div>
                            </div>
                        </div>
                    </li>
                </a>';
            }
        }

        exit(json_encode(array('error'=>1,'msg'=>'返回成功','publishListHtml'=>$html)));
    }





    // 我的需求列表
    public function need_list_app(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
		
		$publishList_where = "`sup`.`uid`='".intval($this->user_session['uid'])."' AND `sup`.`cid`=`sc`.`cid`";
		
		if($_GET['from'] == 'paotui'){
			$publishList_where .= " AND (`sup`.`catgory_type`='2' OR`sup`.`catgory_type`='3')";
		}
		
        $publishList = D('')->table(array(C('DB_PREFIX').'service_user_publish'=>'sup', C('DB_PREFIX').'service_category'=>'sc'))->where($publishList_where)->field('sup.*,sc.cat_name,sc.accept_time')->order('sup.add_time desc')->select();
        $publishCount = D('')->table(array(C('DB_PREFIX').'service_user_publish'=>'sup', C('DB_PREFIX').'service_category'=>'sc'))->where($publishList_where)->count();

        foreach ($publishList as $key => $value) {
            if($value['catgory_type'] == 1){
                $time = $value['add_time']+(intval($value['accept_time'])*60*60*24);
            }else{
                $time = $value['add_time']+(intval($value['accept_time'])*60*60);
            }

            $publishList[$key]['endtime'] =$time;
            if(time() > $time){
                // D('Service_user_publish')->where(array('uid'=>$this->user_session['uid'],'publish_id'=>$vvalue['publish_id']))->data(array('status'=>3))->save();
                if($value['status'] < 2){
                    $publishList[$key]['status'] = 11;
                    D('Service_user_publish')->where(array('uid'=>$this->user_session['uid'],'publish_id'=>$value['publish_id']))->data(array('status'=>11))->save();
                }
            }

            $publishList[$key]['cat_field'] = unserialize($value['cat_field']);
            $offer_list = '';
            $offer_list = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_provider'=>'u'))->where("so.publish_id= '".$value['publish_id']."' AND u.uid = so.p_uid")->field('so.offer_id,so.price,u.avatar')->select();
            $publishList[$key]['offer_count'] = count($offer_list);
            $publishList[$key]['offer_list'] = $offer_list;
        }

        $this->assign('publishList',$publishList);
        $this->assign('publishCount',$publishCount);
        $this->display();
    }






    /**
     *
     * @param  $latitude    纬度
     * @param  $longitude    经度
     * @param  $raidus        半径范围(单位：米)
     * @return multitype:number
     */
    public function getAround($latitude,$longitude,$raidus){
        $PI = 3.14159265;
        $degree = (24901*1609)/360.0;
        $dpmLat = 1/$degree;
        $radiusLat = $dpmLat*$raidus;
        $minLat = $latitude - $radiusLat;
        $maxLat = $latitude + $radiusLat;
        $mpdLng = $degree*cos($latitude * ($PI/180));
        $dpmLng = 1 / $mpdLng;
        $radiusLng = $dpmLng*$raidus;
        $minLng = $longitude - $radiusLng;
        $maxLng = $longitude + $radiusLng;
        return array (minLat=>$minLat, maxLat=>$maxLat, minLng=>$minLng, maxLng=>$maxLng);
    }



    // 带报价需求
    public function trade(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        $cidlist = implode(',',$_SESSION['cid']);
        if($_GET['cid']){
            if($_GET['cid'] !='qb'){
                $catwhere['cid'] = $_GET['cid'];
                $catwhere['uid'] =$this->user_session['uid'];
            }else{
                $catwhere['uid'] =$this->user_session['uid'];
            }
        }else{
            if($cidlist){
                 $catwhere['cid'] = array('in',$cidlist);
                 $this->assign('cur','cur');
            }
            $catwhere['uid'] =$this->user_session['uid'];
        }

        $myOfferIdList = D('Service_offer')->where(array('p_uid'=>$this->user_session['uid']))->field('publish_id')->select();
        foreach ($myOfferIdList as $key => $value) {
            $offer_publish_id_list[] = $value['publish_id'];
        }

        $categoryList = D('Service_provider_category')->where($catwhere)->select();
        $publishList = array();
        foreach ($categoryList as $key => $value) {
            if($offer_publish_id_list){
                $where['publish_id'] = array('not in',implode(',',$offer_publish_id_list));
            }

            $where['deliver_type'] = 2;
            $where['cid'] = $value['cid'];
            $where['address_area_id'] = $value['area_id'];
            $where['catgory_type'] = 1;
            $where['status'] = 1;
            $where['uid'] = array('neq',$this->user_session['uid']);

            if($value['radius'] < 1 ){
                if($_GET['time']){
                    $time= $_GET['time']-1;
                    $where['add_time'] = array('gt',strtotime(date('Ymd'))-($time*60*60*24));
                }
                $list= D('Service_user_publish')->where($where)->order('add_time desc')->select();
            }else{
                if($_GET['time']){
                    $time= $_GET['time']-1;
                    $where['add_time'] = array('gt',strtotime(date('Ymd'))-($time*60*60*24));
                }
                $array = $this->getAround($value['lat'],$value['lng'],$value['radius']*1000);
                $where['address_lat']  = array(array('EGT',$array['minLat']),array('ELT',$array['maxLat']),'and');
                $where['address_lng'] = array(array('EGT',$array['minLng']),array('ELT',$array['maxLng']),'and');
                $list = D('Service_user_publish')->where($where)->order('add_time desc')->select();
            }

            foreach ($list as $kk => $vv) {
                $publishList[] = $vv;
            }
        }
        // dump($publishList);


        foreach ($publishList as $key => $value) {

            $publishList[$key]['cat_field'] = unserialize($value['cat_field']);
            $cat = '';
            $cat = D('Service_category')->where(array('cid'=>$value['cid']))->field('cat_name')->find();
            $publishList[$key]['cat_name'] = $cat['cat_name'];
            $userinfo = '';
            $userinfo = D('User')->where(array('uid'=>$value['uid']))->field('phone,nickname')->find();
            if($userinfo['phone']){
                $publishList[$key]['phone'] = substr_replace($userinfo['phone'],'*****',3,5);
            }
            $publishList[$key]['nickname'] = $userinfo['nickname'];

        }


        foreach ($publishList as $v){
            $v=serialize($v);
            $temp[]=$v;
        }
        $temp=array_unique($temp); //去掉重复的字符串,也就是重复的数组
        foreach ($temp as $k => $v){
            $temp[$k]=unserialize($v); //再将拆开的数组重新组装
        }
        $publishList = $temp;
        // dump($aaa);
        // dump($publishList);


        $sort = array(
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field'     => 'add_time',       //排序字段
        );
        $arrSort = array();
        foreach($publishList AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $publishList);
        }

        // dump($publishList);

        $this->assign('publishList',$publishList);
        $this->assign('publishCount',count($publishList));
        $this->display();
    }





    public function authentication(){
        // echo $this->config['authentication_price'];
        $authentication_info = D('Service_authentication')->where(array('uid'=>$this->user_session['uid']))->find();
        if($authentication_info){

            $authentication_info['authentication_field'] = unserialize($authentication_info['authentication_field']);
            $this->assign('authentication_info',$authentication_info);
            if($authentication_info['authentication_status'] == 3){
                $authentication_config_list = D('Service_authentication_config')->order('type asc')->select();
                foreach ($authentication_config_list as $key => $value) {
                    if($value['type'] == 1){
                        $authentication_wenben[] = $value;
                    }else{
                        $authentication_tupian[] = $value;
                    }
                }
                $this->assign('authentication_wenben',$authentication_wenben);
                $this->assign('authentication_tupian',$authentication_tupian);

                $this->display('authentication_edit');
            }else{
                $this->display('authentication_index');
            }
        }else{
            $authentication_config_list = D('Service_authentication_config')->order('type asc')->select();
            foreach ($authentication_config_list as $key => $value) {
                if($value['type'] == 1){
                    $authentication_wenben[] = $value;
                }else{
                    $authentication_tupian[] = $value;
                }
            }
            $this->assign('authentication_wenben',$authentication_wenben);
            $this->assign('authentication_tupian',$authentication_tupian);
            $this->display();
        }

    }

    public function authentication_data(){

        $data['uid'] = $this->user_session['uid'];
        $data['authentication_status'] = 1;
        $data['authentication_time'] = time();
        $authentication_id = $_POST['authentication_id'];
        unset($_POST['imgFile']);
        unset($_POST['authentication_id']);
        foreach ($_POST as $key => $value) {
            if(!$value['value']){
                $this->error_tips($value['title'].'不可以为空！');
            }
        }
        $data['authentication_field'] = serialize($_POST);

        if($authentication_id){
            $res = D('Service_authentication')->where(array('authentication_id'=>$authentication_id))->data($data)->save();
            if($res){
                //扣款写记录
                $this->success_tips('提交成功！', U('Service/authentication'));
            }else{
                $this->error_tips('提交失败请重试');
            }
        }else{
            $res = D('Service_authentication')->data($data)->add();
            if($res){
                $this->success_tips('提交成功！', U('Service/authentication'));
            }else{
                $this->error_tips('提交失败请重试');
            }
        }

    }




    // 自定义分类筛选
    public function trade_setting(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        if(IS_POST){
            $cidStr = $_POST['select_cate_ids'];
            $cid = explode(',',$cidStr);
            foreach( $cid as $k=>$v){
                if( !$v )
                    unset( $cid[$k] );
            }
            $_SESSION['cid'] = $cid;
            $this->redirect('Service/trade');
        }else{
            $cidlist = $_SESSION['cid'];
            $categoryList = D('Service_provider_category')->where(array('uid'=>$this->user_session['uid']))->field('cat_name,cid')->group('cid')->select();
            foreach ($categoryList as $key => $value) {
                foreach ($cidlist as $k => $v) {
                    if($v == $value['cid']){
                        $categoryList[$key]['status'] = 1;
                    }
                }
            }
            $this->assign('cidlist',implode(',',$cidlist));
            $this->assign('categoryList',$categoryList);
            $this->display();
        }
    }

    //特殊需求
    public function trade_special(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        $catwhere['catgory_type'] = array('neq',1);
        $catwhere['uid'] =$this->user_session['uid'];

        $myOfferIdList = D('Service_offer')->where(array('p_uid'=>$this->user_session['uid']))->field('publish_id')->select();
        foreach ($myOfferIdList as $key => $value) {
            $offer_publish_id_list[] = $value['publish_id'];
        }

        $categoryList = D('Service_provider_category')->where($catwhere)->select();
        // dump($categoryList);
        $publishList = array();
        foreach ($categoryList as $key => $value) {
            if($offer_publish_id_list){
                $where['publish_id'] = array('not in',implode(',',$offer_publish_id_list));
            }

            $where['deliver_type'] = 2;
            $where['status'] = 2;
            $where['cid'] = $value['cid'];
            $where['address_area_id'] = $value['area_id'];
            // $where['catgory_type'] = array('neq',1);
            $where['uid'] = array('neq',$this->user_session['uid']);

            if($value['radius'] < 1 ){
                $list= D('Service_user_publish')->where($where)->select();
            }else{
                $array = $this->getAround($value['lat'],$value['lng'],$value['radius']*1000);
                $where['address_lat']  = array(array('EGT',$array['minLat']),array('ELT',$array['maxLat']),'and');
                $where['address_lng'] = array(array('EGT',$array['minLng']),array('ELT',$array['maxLng']),'and');
                $list = D('Service_user_publish')->where($where)->select();
            }

            foreach ($list as $kk => $vv) {
                    $cat = D('Service_category')->where(array('cid'=>$value['cid']))->field('cat_name')->find();
                    $vv['cat_name'] = $cat['cat_name'];

                    $userinfo = '';
                    $userinfo = D('User')->where(array('uid'=>$vv['uid']))->field('phone,nickname')->find();
                    if($userinfo['phone']){
                        $vv['phone'] = substr_replace($userinfo['phone'],'*****',3,5);
                    }
                    $vv['nickname'] = $userinfo['nickname'];
                    if ($value['catgory_type'] == 2) {
                        $vv['cat_field'] =  D('Service_user_publish_buy')->where(array('publish_id'=>$vv['publish_id']))->find();
                    }elseif ($value['catgory_type'] == 3) {
                        $vv['cat_field'] =  D('Service_user_publish_give')->where(array('publish_id'=>$vv['publish_id']))->find();
                    }
                $publishList[] = $vv;
            }
        }


        // 多维数组去重复
        foreach ($publishList as $v){
            $v=serialize($v);
            $temp[]=$v;
        }

        $temp=array_unique($temp); //去掉重复的字符串,也就是重复的数组
        foreach ($temp as $k => $v){
            $temp[$k]=unserialize($v); //再将拆开的数组重新组装
        }
        $publishList = $temp;

        // 排序
        $sort = array(
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field'     => 'add_time',       //排序字段
        );
        $arrSort = array();
        foreach($publishList AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $publishList);
        }


        $this->assign('publishList',$publishList);
        $this->assign('publishCount',count($publishList));
        $this->display();
    }

    public function special_add_offer(){

        $publishInfo = D("Service_user_publish")->where(array('publish_id'=>$_POST['publish_id'],'status'=>2))->field('publish_id,uid,cid,order_sn')->find();
        if(empty($publishInfo)){
            exit(json_encode(array('error'=>4,'msg'=>'接单失败，请重试！')));
        }
        $offer_info = D("Service_offer")->where(array('publish_id'=>$_POST['publish_id']))->find();
        if($offer_info){
            exit(json_encode(array('error'=>3,'msg'=>'接单失败，此单已经被别的配送员接了！')));
        }

        $offer_data['publish_id'] = $publishInfo['publish_id'];
        $offer_data['price'] = 0;
        $offer_data['uid'] = $publishInfo['uid'];
        $offer_data['cid'] = $publishInfo['cid'];
        $offer_data['add_time'] = time();
        $offer_data['order_sn'] = date("ymdHis").rand(10,99).sprintf("%08d",$this->user_session['uid']);
        $offer_data['p_uid'] = $this->user_session['uid'];
        $offer_data['deliver_type'] = 2;
        $offer_data['status'] = 8;
        $offer_id = D("Service_offer")->data($offer_data)->add();

        if($offer_id){
            D("Service_user_publish")->where(array('publish_id'=>$publishInfo['publish_id']))->save(array('status' => 8));
            D('Service_offer_record')->data(array('offer_id'=>$offer_id,'publish_id'=>$publishInfo['publish_id'],'add_time'=>time(),'remarks'=>'配送员已接单'))->add();     // 添加推送状态消息
            $now_user = D('User')->get_user($publishInfo['uid']);
            if ($now_user['openid']) {
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $_POST['publish_id'];
                // 帮买帮送修改  待办事项通知 模板 为 订单状态更新 模板
                $model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'OrderSn' => $publishInfo['order_sn'], 'OrderStatus' =>'您发布的需求配送员已接单，请查看。', 'remark' => date('Y.m.d H:i:s')));
            }
            exit(json_encode(array('error'=>1,'msg'=>'接单成功，请尽快完成。',)));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'接单失败，请重试！')));
        }
    }

    public function offer_save_status(){
        $user_publish_where['publish_id']  = $_POST['publish_id'];
        D("Service_user_publish")->where($user_publish_where)->save(array('status'=>$_POST['status']));
        $offerInfo = D('Service_offer')->where(array('p_uid'=>$this->user_session['uid'],'publish_id'=>$_POST['publish_id']))->field('offer_id,p_uid')->find();
        $offer_where['p_uid'] = $this->user_session['uid'];
        $offer_where['publish_id']  = $_POST['publish_id'];
        // $offer_where['offer_id'] = $offer_id;
        $offer_where['deliver_type'] = 2;
        $res = D('Service_offer')->where($offer_where)->data(array('status'=>$_POST['status']))->save();

        if($_POST['status'] == 4){
            $remarks = '订单已完成';
        }else if($_POST['status'] == 9){
            $remarks = '配送员送货中';
        }else if($_POST['status'] == 8){
            $remarks = '配送员已接单';
        }

        if($res){
            $publishInfo = D('Service_user_publish')->where(array('publish_id'=>$_POST['publish_id']))->field('publish_id,cid,uid,catgory_type,order_sn')->find();
            if($_POST['status'] == 4){

                if($publishInfo['catgory_type'] == 2){
                    $publish_field_info = D('Service_user_publish_buy')->where(array('publish_id'=>$publishInfo['publish_id']))->find();
                }else if($publishInfo['catgory_type'] == 3){
                    $publish_field_info = D('Service_user_publish_give')->where(array('publish_id'=>$publishInfo['publish_id']))->find();
                }

                $categoryInfo = D('Service_category')->where(array('cid'=>$publishInfo['cid']))->field('cid,cut_proportion')->find();
                $cut_proportion = $categoryInfo['cut_proportion']/100;

                $poundage = round($publish_field_info['total_price']*$cut_proportion,2);
                $price = round($publish_field_info['total_price']-$poundage, 2);

                D('User')->where(array('uid'=>$offerInfo['p_uid']))->setInc('now_money',$price);
                D('User_money_list')->add_row($offerInfo['p_uid'],1,$price,"用户付款 ".$publish_field_info['total_price']." 元,包含小费".$publish_field_info['tip_price']."元,扣除手续费".round($poundage,2)."元");
                D('Service_provider')->where(array('uid'=>$offerInfo['p_uid']))->setInc('total_amount',$price);

            }
            D('Service_offer_record')->data(array('offer_id'=>$offerInfo['offer_id'],'publish_id'=>$_POST['publish_id'],'add_time'=>time(),'remarks'=>$remarks))->add();
            // 添加推送状态消息
            $now_user = D('User')->get_user($publishInfo['uid']);
            if ($now_user['openid']) {
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $_POST['publish_id'];
                // 帮买帮送修改  待办事项通知 模板 为 订单状态更新 模板
                $model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $now_user['nickname'] . '您好！', 'OrderSn' => $publishInfo['order_sn'], 'OrderStatus' =>'您发布的需求'.$remarks.'，请查看。', 'remark' => date('Y.m.d H:i:s')));
            }
            exit(json_encode(array('error'=>1,'msg'=>'状态已修改，请尽快完成。')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'变动失败，请刷新重试！多次重试无用请联系管理员。')));
        }
    }

    public function detail_special(){
        $publishInfo = D('Service_user_publish')->where(array('publish_id'=>$_GET['publish_id']))->find();
        $offer_info = D('Service_offer')->where(array('publish_id'=>$_GET['publish_id'],'deliver_type'=>2,'p_uid'=>$this->user_session['uid']))->find();

        if(empty($publishInfo)){
            $this->error_tips('需求异常请重试', U('Service/trade_special'));
        }

        if(empty($offer_info)){
            $offer_info_return = D('Service_offer')->where(array('publish_id'=>$_GET['publish_id'],'deliver_type'=>2))->find();
            if($offer_info_return){
                $this->error_tips('已有别的服务商接单。', U('Service/trade_special'));
            }
        }
        // dump($offer_info);
        $this->assign('offer_info',$offer_info);
        $catInfo = D('Service_category')->where(array('cid'=>$publishInfo['cid']))->field('cat_name')->find();
        $publishInfo['cat_name'] = $catInfo['cat_name'];

        if($publishInfo['catgory_type'] == 2){
            $cat_field_info = D('Service_user_publish_buy')->where(array('publish_id'=>$publishInfo['publish_id']))->find();
            $cat_field_info['img'] = array_filter(explode(';', $cat_field_info['img']));
        }else if($publishInfo['catgory_type'] == 3){
            $cat_field_info = D('Service_user_publish_give')->where(array('publish_id'=>$publishInfo['publish_id']))->find();
            $cat_field_info['img'] = array_filter(explode(';', $cat_field_info['img']));
        }
        $this->assign('cat_field_info',$cat_field_info);
        // dump($cat_field_info);
        $userinfo = D('User')->where(array('uid'=>$publishInfo['uid']))->field('phone,nickname')->find();
        if($userinfo['phone']){
            $publishInfo['phone'] = substr_replace($userinfo['phone'],'*****',3,5);
        }
        $publishInfo['nickname'] = $userinfo['nickname'];

        $offer_record_list = D('service_offer_record')->where(array('publish_id'=>$_GET['publish_id']))->order('rid desc')->select();
        $this->assign('offer_record_list',$offer_record_list);
        $this->assign('publishInfo',$publishInfo);
        $this->display();
    }

    // 需求详情 报价列表
    public function price_list(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        $publishInfo = D('Service_user_publish')->where(array('publish_id'=>$_GET['publish_id']))->find();
        $catInfo = D('Service_category')->where(array('cid'=>$publishInfo['cid']))->field('cat_name,accept_time')->find();
        $publishInfo['cat_name'] = $catInfo['cat_name'];


        if($publishInfo['catgory_type'] == 1){
            $publishInfo['cat_field'] = unserialize($publishInfo['cat_field']);
            foreach ($publishInfo['cat_field'] as $kk => $vv) {
                if($vv['type'] == 7){
                   $publishInfo['address_start'] = $vv['value']['address_start'];
                   $publishInfo['address_start_lng'] = $vv['value']['address_start_lng'];
                   $publishInfo['address_start_lat'] = $vv['value']['address_start_lat'];
                   $publishInfo['address_end'] = $vv['value']['address_end'];
                   $publishInfo['address_end_lng'] = $vv['value']['address_end_lng'];
                   $publishInfo['address_end_lat'] = $vv['value']['address_end_lat'];
                }
            }
            unset($publishInfo['cat_field']['cid']);

            $offer_list = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_provider'=>'u'))->where("so.publish_id= '".$publishInfo['publish_id']."' AND u.uid = so.p_uid")->field('so.offer_id,so.price,so.p_uid,so.status,u.avatar,u.phone,u.name')->select();
            foreach ($offer_list as $key => $value) {
                $offer_list[$key]['msg_list'] = D('Service_offer_message')->where(array('offer_id'=>$value['offer_id']))->field('message,type,add_time')->order('add_time asc')->select();
                $offer_list[$key]['msg_count'] = count($offer_list[$key]['msg_list']);
                if (empty($offer_list[$key]['avatar'])) {
                    // 没有头像取默认头像
                    $offer_list[$key]['avatar'] = C('config.site_url') . '/static/default.png';
                }
            }
            $this->assign('offer_list',$offer_list);
            $this->assign('offer_count',count($offer_list));
        }else if($publishInfo['catgory_type'] == 2){

            $cat_field_info = D('Service_user_publish_buy')->where(array('publish_id'=>$publishInfo['publish_id']))->find();
            $cat_field_info['img'] = array_filter(explode(';', $cat_field_info['img']));
            // $adress_info = D("User_adress")->get_adress($this->user_session['uid'],$cat_field_info['adress_id']);

            // $cat_field_info['arrival_time']= $publishInfo['add_time']+($cat_field_info['arrival_time']*60); //发布时间加上分钟
            // $cat_field_info['arrival_time']=$cat_field_info['arrival_time']/60; //小时送达

            // $cat_field_info['end_adress_name'] = $adress_info['adress'].$adress_info['detail'];

            if($publishInfo['deliver_type'] == 1){
                $offer_info = D('')->table(array(C('DB_PREFIX').'deliver_supply'=>'ds', C('DB_PREFIX').'deliver_user'=>'du'))->where("ds.order_id= '".$publishInfo['publish_id']."' AND du.uid = ds.uid AND ds.order_from in (4,5)")->field(true)->find();
                if (!empty($offer_info)) {
                    $offer_info['avatar'] = C('config.wechat_share_img') ? C('config.wechat_share_img') : C('config.site_logo');
                }
            }else{
                $offer_info = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_provider'=>'u'))->where("so.publish_id= '".$publishInfo['publish_id']."' AND u.uid = so.p_uid")->field('so.offer_id,so.price,so.p_uid,so.status,u.avatar,u.phone,u.name')->find();
            }

            // $offer_info = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_provider'=>'u'))->where("so.publish_id= '".$publishInfo['publish_id']."' AND u.uid = so.p_uid")->field('so.offer_id,so.price,so.p_uid,so.status,u.avatar,u.phone,u.name')->find();
            $this->assign('offer_info',$offer_info);

            $this->assign('cat_field_info',$cat_field_info);

            $offer_record_list = D('service_offer_record')->where(array('publish_id'=>$_GET['publish_id']))->order('rid desc')->select();
            $this->assign('offer_record_list',$offer_record_list);

        }else if($publishInfo['catgory_type'] == 3){
            $catInfo['accept_time']*60*60;

            $cat_field_info = D('Service_user_publish_give')->where(array('publish_id'=>$publishInfo['publish_id']))->find();
            $cat_field_info['img'] = array_filter(explode(';', $cat_field_info['img']));
//            $offer_info = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_provider'=>'u'))->where("so.publish_id= '".$publishInfo['publish_id']."' AND u.uid = so.p_uid")->field('so.offer_id,so.price,so.p_uid,so.status,u.avatar,u.phone,u.name')->find();
            if($publishInfo['deliver_type'] == 1){
                $offer_info = D('')->table(array(C('DB_PREFIX').'deliver_supply'=>'ds', C('DB_PREFIX').'deliver_user'=>'du'))->where("ds.order_id='".$publishInfo['publish_id']."' AND du.uid=ds.uid AND ds.order_from=3")->field(true)->find();
                if (!empty($offer_info)) {
                    $offer_info['avatar'] = C('config.wechat_share_img') ? C('config.wechat_share_img') : C('config.site_logo');
                }
            }else{
                $offer_info = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_provider'=>'u'))->where("so.publish_id= '".$publishInfo['publish_id']."' AND u.uid = so.p_uid")->field('so.offer_id,so.price,so.p_uid,so.status,u.avatar,u.phone,u.name')->find();
            }




            $this->assign('offer_info',$offer_info);

            // $start_adress_info = D("User_adress")->get_adress($this->user_session['uid'],$cat_field_info['start_adress_id']);
            // $cat_field_info['start_adress_name'] = $cat_field_info['start_adress_name'];

            // $end_adress_info = D("User_adress")->get_adress($this->user_session['uid'],$cat_field_info['end_adress_id']);
            // $cat_field_info['end_adress_name'] = $end_adress_info['end_adress_name'];

            $this->assign('cat_field_info',$cat_field_info);

            $offer_record_list = D('service_offer_record')->where(array('publish_id'=>$_GET['publish_id']))->order('rid desc')->select();
            $this->assign('offer_record_list',$offer_record_list);
        }

        $this->assign('publishInfo',$publishInfo);
        $this->display();
    }

    public function cancel_publish(){
        $res = D('service_user_publish')->where(array('publish_id'=>$_POST['publish_id'],'uid'=>$this->user_session['uid']))->save(array('status'=>10));
        if($res){
            exit(json_encode(array('error'=>1,'msg'=>'需求已取消，您接受不到新的报价了。')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'取消失败，请重试。')));
        }
    }

    // 需求详情 商户报价
    public function detail(){
        $publishInfo = D('Service_user_publish')->where(array('publish_id'=>$_GET['publish_id']))->find();

        $offerInfo = D('service_offer')->where(array('uid'=>$publishInfo['uid'],'p_uid'=>$this->user_session['uid'],'publish_id'=>$_GET['publish_id']))->find();
        if(is_array($offerInfo)){
            $this->error_tips('已报价请联系客户',U('Service/offer_detail',array('offer_id'=>$offerInfo['offer_id'])));
        }
        $publishInfo['cat_field'] = unserialize($publishInfo['cat_field']);
        foreach ($publishInfo['cat_field'] as $kk => $vv) {
            if($vv['type'] == 7){
               $publishInfo['address_start'] = $vv['value']['address_start'];
               $publishInfo['address_start_lng'] = $vv['value']['address_start_lng'];
               $publishInfo['address_start_lat'] = $vv['value']['address_start_lat'];
               $publishInfo['address_end'] = $vv['value']['address_end'];
               $publishInfo['address_end_lng'] = $vv['value']['address_end_lng'];
               $publishInfo['address_end_lat'] = $vv['value']['address_end_lat'];
            }
        }
        unset($publishInfo['cat_field']['cid']);
        $catInfo = D('Service_category')->where(array('cid'=>$publishInfo['cid']))->field('cat_name')->find();
        $publishInfo['cat_name'] = $catInfo['cat_name'];

        $userinfo = '';
        $userinfo = D('User')->where(array('uid'=>$publishInfo['uid']))->field('phone,nickname')->find();
        if($userinfo['phone']){
            $publishInfo['phone'] = substr_replace($userinfo['phone'],'*****',3,5);
        }
        $publishInfo['nickname'] = $userinfo['nickname'];

        $this->assign('publishInfo',$publishInfo);
        // dump($publishInfo);
        $this->display();
    }

    // 发送报价
    public function send_offer(){
        $publishInfo = D("Service_user_publish")->where(array('publish_id'=>$_POST['publish_id']))->field('publish_id,uid,cid')->find();
        $offer_info = D('Service_offer')->where(array('publish_id'=>$_POST['publish_id'],'p_uid'=>$this->user_session['uid']))->find();
        if($offer_info){
            exit(json_encode(array('error'=>3,'msg'=>'报价失败，您已经对此条需求报价了。')));
        }
        $offer_data['publish_id'] = $_POST['publish_id'];
        $offer_data['price'] = $_POST['price'];
        $offer_data['uid'] = $publishInfo['uid'];
        $offer_data['cid'] = $_POST['cid'];
        $offer_data['add_time'] = time();
        $offer_data['order_sn'] = date("ymdHis").rand(10,99).sprintf("%08d",$this->user_session['uid']);
        $offer_data['p_uid'] = $this->user_session['uid'];
        $res = D('Service_offer')->data($offer_data)->add();
        if($res){
            //抢单人数增加1位
            D("Service_user_publish")->where(array('publish_id'=>$_POST['publish_id']))->setInc('offer_sum');

            //模板消息
            $user_info = D("User")->where(array('uid'=>$publishInfo['uid']))->field('uid,openid,nickname')->find();
            if ($user_info['openid']) {
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id='.$_POST['publish_id'];
//                $model->sendTempMsg('OPENTM406638907',
//                    array('href' => $href,
//                        'wecha_id' => $user_info['openid'],
//                        'first' => $user_info['nickname'] . '您好！',
//                        'keyword1' => '已有服务商给您的需求发送了报价，请及时处理。',
//                        'keyword2' => date('Y年m月d日 H:i:s'),
//                        'remark' => '请您及时处理！'));

                $model->sendTempMsg('TM00017',
                    array('href' => $href,
                        'wecha_id' => $user_info['openid'],
                        'first' => $user_info['nickname'] . '已有服务商给您的需求发送了报价！',
                        'OrderSn' => $offer_data['order_sn'],
                        'OrderStatus' => '待处理',
                        'remark' => '请您及时处理！'));
            }

            exit(json_encode(array('error'=>1,'msg'=>'报价成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'报价失败，请重新尝试。')));
        }
    }

    //联系中的需求
    public function trade_contact(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        $publishList = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_user_publish'=>'sup',C('DB_PREFIX').'service_category'=>'sc',C('DB_PREFIX').'user'=>'u'))->where("so.p_uid= '".intval($this->user_session['uid'])."' AND sup.publish_id = so.publish_id  AND sc.cid = sup.cid AND u.uid = so.uid AND so.status != 4 AND so.status != 7")->field('so.*,sup.*,sc.cat_name,u.phone,u.nickname')->order('so.add_time desc')->select();

        // echo D('')->getlastsql();
        foreach ($publishList as $key => $value) {
            if($value['catgory_type'] == 1){
                $publishList[$key]['cat_field'] = unserialize($value['cat_field']);
            }elseif ($value['catgory_type'] == 2) {
                $publishList[$key]['cat_field'] =  D('Service_user_publish_buy')->where(array('publish_id'=>$value['publish_id']))->find();
            }elseif ($value['catgory_type'] == 3) {
                $publishList[$key]['cat_field'] =  D('Service_user_publish_give')->where(array('publish_id'=>$value['publish_id']))->find();
            }
            if($value['phone']){
                if($value['status']==1 || $value['status'] == 11){
                    $publishList[$key]['phone'] = substr_replace($value['phone'],'*****',3,5);
                }
            }
        }
        $this->assign('publishList',$publishList);
        // dump($publishList);
        $this->display();
    }
    // 已完成需求
    public function trade_over(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        $publishList = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_user_publish'=>'sup',C('DB_PREFIX').'service_category'=>'sc',C('DB_PREFIX').'user'=>'u'))->where("so.p_uid= '".intval($this->user_session['uid'])."' AND sup.publish_id = so.publish_id  AND sc.cid = sup.cid AND u.uid = so.uid AND so.status IN ('4','7')")->field('so.*,sup.*,sc.cat_name,u.phone,u.nickname')->order('so.add_time desc')->select();

        foreach ($publishList as $key => $value) {
            if($value['catgory_type'] == 1){
                $publishList[$key]['cat_field'] = unserialize($value['cat_field']);
            }elseif ($value['catgory_type'] == 2) {
                $publishList[$key]['cat_field'] =  D('Service_user_publish_buy')->where(array('publish_id'=>$value['publish_id']))->find();
            }elseif ($value['catgory_type'] == 3) {
                $publishList[$key]['cat_field'] =  D('Service_user_publish_give')->where(array('publish_id'=>$value['publish_id']))->find();
            }

        }
        $this->assign('publishList',$publishList);
        $this->display();
    }

    // 商户报价详情
    public function offer_detail(){
        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录！', U('Login/index'));
        }
        $publishInfo = D('')->table(array(C('DB_PREFIX').'service_offer'=>'so', C('DB_PREFIX').'service_user_publish'=>'sup',C('DB_PREFIX').'service_category'=>'sc',C('DB_PREFIX').'user'=>'u'))->where("so.offer_id = '".intval($_GET['offer_id'])."' AND so.p_uid= '".intval($this->user_session['uid'])."' AND sup.publish_id = so.publish_id AND sc.cid = sup.cid AND u.uid = so.uid")->field('sup.*,so.*,sc.cat_name,u.phone,u.nickname,u.avatar')->order('so.add_time desc')->find();

        $publishInfo['cat_field'] = unserialize($publishInfo['cat_field']);
        foreach ($publishInfo['cat_field'] as $kk => $vv) {
            if($vv['type'] == 7){
                $publishInfo['address_start'] = $vv['value']['address_start'];
                $publishInfo['address_start_lng'] = $vv['value']['address_start_lng'];
                $publishInfo['address_start_lat'] = $vv['value']['address_start_lat'];
                $publishInfo['address_end'] = $vv['value']['address_end'];
                $publishInfo['address_end_lng'] = $vv['value']['address_end_lng'];
                $publishInfo['address_end_lat'] = $vv['value']['address_end_lat'];
            }
        }
        unset($publishInfo['cat_field']['cid']);
        if($publishInfo['status'] == 7){
            $evaluate_info = D('service_offer_evaluate')->where(array('offer_id'=>$_GET['offer_id']))->find();
            $this->assign('evaluate_info',$evaluate_info);
        }
        if($publishInfo['phone']){
            if($publishInfo['status']==1 || $publishInfo['status'] == 11){
                $publishInfo['phone'] = substr_replace($publishInfo['phone'],'*****',3,5);
            }
        }

        $this->assign('publishInfo',$publishInfo);

        $offerMsgList = D('Service_offer_message')->where(array('offer_id'=>$_GET['offer_id']))->select();
        $this->assign('offerMsgList',$offerMsgList);
        $this->display();
    }

    // 用户支付
    public function offer_pay(){
        $offerInfo = D('Service_offer')->where(array('uid'=>$this->user_session['uid'],'offer_id'=>intval($_POST['offer_id'])))->find();
        // 如果已经付款提示已付款
        if (!empty($offerInfo) && $offerInfo['status'] == 2) {
            exit(json_encode(array('error'=>4,'msg'=>'已支付待服务')));
        }
        $userInfo = D('User')->where(array('uid'=>$this->user_session['uid']))->field('uid,now_money')->find();

        $cat_name_info = D('')->table(array(C('DB_PREFIX').'service_user_publish'=>'sup', C('DB_PREFIX').'service_category'=>'sc'))->where("sup.publish_id= '".$offerInfo['publish_id']."' AND sup.cid = sc.cid")->field('sc.cat_name')->select();

        if($offerInfo['price'] > $userInfo['now_money']){
            exit(json_encode(array('error'=>3,'msg'=>'当前余额不足请充值')));
        }

        $res = D('User')->where(array('uid'=>$this->user_session['uid']))->setDec('now_money',$offerInfo['price']);
        if($res){
            D('Service_offer')->where(array('offer_id'=>$_POST['offer_id']))->data(array('status'=>2))->save();
            D('Service_user_publish')->where(array('publish_id'=>$offerInfo['publish_id']))->data(array('status'=>2))->save();
            D('User_money_list')->add_row($offerInfo['uid'],2,$offerInfo['price'],"我购买".$cat_name_info['cat_name']."服务 ".$offerInfo['price']." 元");

            $providerUserInfo = D('User')->where(array('uid'=>$offerInfo['p_uid']))->find();
            // 订单状态提醒 用户付款
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $href = C('config.site_url').'/wap.php?g=Wap&c=Service&a=offer_detail&offer_id='.intval($_POST['offer_id']);
            $model->sendTempMsg('TM00017', array('href' => $href,
                    'wecha_id' => $providerUserInfo['openid'],
                    'first' => '用户付款通知',
                    'OrderSn' => $offerInfo['order_sn'],
                    'OrderStatus' =>$this->user_session['nickname'].'已付款',
                    'remark' =>'请尽快服务'));

            exit(json_encode(array('error'=>1,'msg'=>'支付成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'支付失败请重试')));
        }

    }

    //服务商确认服务
    public function confirm_service(){
        $offerInfo = D('Service_offer')->where(array('offer_id'=>intval($_POST['offer_id'])))->find();
        $res = D('Service_offer')->where(array('offer_id'=>$_POST['offer_id']))->data(array('status'=>3))->save();
        D('Service_user_publish')->where(array('publish_id'=>$offerInfo['publish_id']))->data(array('status'=>3))->save();
        if($res){

            $offerInfo = D('Service_offer')->where(array('offer_id'=>$_POST['offer_id']))->find();
            // 订单状态提醒 服务商确认服务
            $publishUserInfo = D('User')->where(array('uid'=>$offerInfo['uid']))->find();
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $href = C('config.site_url').'/wap.php?c=Service&a=price_list&publish_id='.$offerInfo['publish_id'];
            $model->sendTempMsg('TM00017', array('href' => $href,
                    'wecha_id' => $publishUserInfo['openid'],
                    'first' => '服务商服务通知',
                    'OrderSn' => $offerInfo['order_sn'],
                    'OrderStatus' =>$this->user_session['nickname'].'已服务',
                    'remark' =>'请尽快确认'));

            exit(json_encode(array('error'=>1,'msg'=>'服务已完成')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'完成服务失败请重试')));
        }
    }

    //用户确认服务
    public function user_confirm_service(){
        $offerInfo = D('Service_offer')->where(array('offer_id'=>$_POST['offer_id']))->find();
        $publishInfo = D('Service_user_publish')->where(array('publish_id'=>$offerInfo['publish_id']))->field('publish_id,cid,uid')->find();
        $categoryInfo = D('Service_category')->where(array('cid'=>$publishInfo['cid']))->field('cid,cat_name,cut_proportion,return_integral_proportion')->find();
        $cut_proportion = $categoryInfo['cut_proportion']/100;
        $poundage = $offerInfo['price']*$cut_proportion;
        $price = round($offerInfo['price']-$poundage, 2);
        $res = D('Service_offer')->where(array('offer_id'=>$_POST['offer_id']))->data(array('status'=>4))->save();
        D('Service_user_publish')->where(array('publish_id'=>$offerInfo['publish_id']))->data(array('status'=>4))->save();
        if($res){
            D('User')->where(array('uid'=>$offerInfo['p_uid']))->setInc('now_money',$price);
            D('User_money_list')->add_row($offerInfo['p_uid'],1,$price,"用户付款 ".$offerInfo['price']." 元,扣除手续费".round($poundage,2)."元");
            D('Service_provider')->where(array('uid'=>$offerInfo['p_uid']))->setInc('total_amount',$price);
            $providerUserInfo = D('User')->where(array('uid'=>$offerInfo['p_uid']))->find();
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $href = C('config.site_url').'/wap.php?g=Wap&c=Service&a=offer_detail&offer_id='.intval($_POST['offer_id']);
            $model->sendTempMsg('TM00017', array('href' => $href,
                    'wecha_id' => $providerUserInfo['openid'],
                    'first' => '用户确认服务通知',
                    'OrderSn' => $offerInfo['order_sn'],
                    'OrderStatus' =>$this->user_session['nickname'].'已经确认您完成了服务',
                    'remark' =>'查看详情'));
            exit(json_encode(array('error'=>1,'msg'=>'服务已完成')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'完成服务失败请重试')));
        }
    }

    // 用户申请退款
    public function offer_refund(){
//        $offerInfo = D('Service_offer')->where(array('offer_id'=>intval($_POST['offer_id'])))->find();
//        D('Service_user_publish')->where(array('publish_id'=>$offerInfo['publish_id']))->data(array('status'=>5))->save();
        $res = D('Service_offer')->where(array('offer_id'=>$_POST['offer_id']))->data(array('status'=>5,'reason'=>$_POST['reason']))->save();
        if($res){
            exit(json_encode(array('error'=>1,'msg'=>'用户申请退款成功！')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'用户申请退款失败请重试。')));
        }
    }

    // 服务商回复用户退款
    public function refund_reply(){
        $res = D('Service_offer')->where(array('offer_id'=>$_POST['offer_id']))->data(array('status'=>$_POST['status']))->save();
        $offerInfo = D('Service_offer')->where(array('offer_id'=>intval($_POST['offer_id'])))->find();
        D('Service_user_publish')->where(array('publish_id'=>$offerInfo['publish_id']))->data(array('status'=>$_POST['status']))->save();

        $offerInfo = D('Service_offer')->where(array('offer_id'=>$_POST['offer_id']))->find();

        if($res){
            if($_POST['status'] == 6){
                D('User')->where(array('uid'=>$offerInfo['uid']))->setInc('now_money',$offerInfo['price']);
                D('User_money_list')->add_row($offerInfo['uid'],1,$offerInfo['price'],"服务商退款 ".$offerInfo['price']." 元");

                $publishUserInfo = D('User')->where(array('uid'=>$offerInfo['uid']))->find();
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $href = C('config.site_url').'/wap.php?c=Service&a=price_list&publish_id='.$offer_id['publish_id'];
                $model->sendTempMsg('TM00017', array('href' => $href,
                        'wecha_id' => $publishUserInfo['openid'],
                        'first' => '服务商同意退款',
                        'OrderSn' => $offerInfo['order_sn'],
                        'OrderStatus' =>$this->user_session['nickname'].'同意退款',
                        'remark' =>'有问题请联系服务商'));
            }else{

                $publishUserInfo = D('User')->where(array('uid'=>$offerInfo['uid']))->find();
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $href = C('config.site_url').'/wap.php?c=Service&a=price_list&publish_id='.$offer_id['publish_id'];
                $model->sendTempMsg('TM00017', array('href' => $href,
                        'wecha_id' => $publishUserInfo['openid'],
                        'first' => '服务商拒绝退款',
                        'OrderSn' => $offerInfo['order_sn'],
                        'OrderStatus' =>$this->user_session['nickname'].'拒绝退款',
                        'remark' =>'有问题请联系服务商'));
            }
            exit(json_encode(array('error'=>1,'msg'=>'操作成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'操作失败请重试。')));
        }
    }

    // 发送留言
    public function send_msg(){
        $_POST['add_time'] = time();
        $res = D('Service_offer_message')->data($_POST)->add();
        if($res){
            if($_POST['type'] == 1){
                $href = C('config.site_url').'/wap.php?g=Wap&c=Service&a=offer_detail&offer_id='.$_POST['offer_id'];
                $userInfo = D('User')->where(array('uid'=>$_POST['p_uid']))->find();
            }else{
                $href = C('config.site_url').'/wap.php?g=Wap&c=Service&a=price_list&publish_id='.$_POST['publish_id'];
                $userInfo = D('User')->where(array('uid'=>$_POST['uid']))->find();
            }

            // 留言模板消息
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $model->sendTempMsg('OPENTM203574543', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，您有一条新的留言\n', 'keyword1' => $userInfo['nickname'], 'keyword2' => date('H时i分',$_SERVER['REQUEST_TIME']), 'keyword3' => '点击查看', 'remark' => '\n请点击查看详细信息！'));

            exit(json_encode(array('error'=>1,'msg'=>'留言发布成功！')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'留言发布失败！')));
        }
    }

    // 修改价格
    public function save_price(){
        $res = D('Service_offer')->where(array('p_uid'=>$this->user_session['uid'],'offer_id'=>intval($_POST['offer_id'])))->data(array('price'=>$_POST['price']))->save();
        if($res){
            $offer_info = D('Service_offer')->field('uid, publish_id')->where(array('p_uid'=>$this->user_session['uid'],'offer_id'=>intval($_POST['offer_id'])))->find();
            //模板消息
            $user_info = D("User")->where(array('uid'=>$offer_info['uid']))->field('uid,openid,nickname')->find();
            if ($user_info['openid']) {
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id='.$offer_info['publish_id'];
//                $model->sendTempMsg('OPENTM406638907',
//                    array('href' => $href,
//                        'wecha_id' => $user_info['openid'],
//                        'first' => $user_info['nickname'] . '您好！',
//                        'keyword1' => '已有服务商给您的需求重新发布新报价，请及时处理。',
//                        'keyword2' => date('Y年m月d日 H:i:s'),
//                        'remark' => '请您及时处理！'));

                $model->sendTempMsg('TM00017',
                    array('href' => $href,
                        'wecha_id' => $user_info['openid'],
                        'first' => $user_info['nickname'] . '已有服务商给您的需求重新发布新报价！',
                        'OrderSn' => $offer_info['order_sn'],
                        'OrderStatus' => '待处理',
                        'remark' => '请您及时处理！'));
            }
            exit(json_encode(array('error'=>1,'msg'=>'修改成功！')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'修改失败！')));
        }
    }


    //通过坐标获取城市  通过城市名字查询数据
    public function cityMatching(){
        $url = 'http://api.map.baidu.com/geocoder/v2/?output=json&ak=4c1bb2055e24296bbaef36574877b4e2&location='.$_GET['lat'].','.$_GET['lng'];
        import('ORG.Net.Http');
        $http = new Http();
        $result = $http->curlGet($url);
        $result = json_decode($result,true);
        $city_name = $result['result']['addressComponent']['city'];
        // 查询一下所在区
        $district_name = $result['result']['addressComponent']['district'];
        $long   =   strlen($city_name);
        if($long >= 7){
			$city_name    =   str_replace('市','',$city_name);
            $city_name    =   str_replace('地区','',$city_name);
            $city_name    =   str_replace('特别行政区','',$city_name);
            $city_name    =   str_replace('特別行政區','',$city_name);
            $city_name    =   str_replace('蒙古自治州','',$city_name);
            $city_name    =   str_replace('回族自治州','',$city_name);
            $city_name    =   str_replace('柯尔克孜自治州','',$city_name);
            $city_name    =   str_replace('哈萨克自治州','',$city_name);
            $city_name    =   str_replace('土家族苗族自治州','',$city_name);
            $city_name    =   str_replace('藏族羌族自治州','',$city_name);
            $city_name    =   str_replace('傣族自治州','',$city_name);
			$city_name    =   str_replace('布依族苗族自治州','',$city_name);
			$city_name    =   str_replace('苗族侗族自治州','',$city_name);
			$city_name    =   str_replace('壮族苗族自治州','',$city_name);
            $city_name    =   str_replace('黔东南苗族侗族自治州','',$city_name);
            $city_name    =   str_replace('澳门','澳門',$city_name);
			$city_name    =   str_replace('朝鲜族自治州','',$city_name);
			$city_name 	  =   str_replace('哈尼族彝族自治州','',$city_name);
			$city_name 	  =   str_replace('傣族景颇族自治州','',$city_name);
			$city_name 	  =   str_replace('藏族自治州','',$city_name);
			$city_name 	  =   str_replace('彝族自治州','',$city_name);
			$city_name 	  =   str_replace('白族自治州','',$city_name);
			$city_name 	  =   str_replace('傈僳族自治州','',$city_name);
        }
        $database_area = D('Area');
        $database_field = '`area_id`,`area_name`,`area_url`';
        $condition_all_city['area_name'] = $city_name;
        $condition_all_city['area_type'] = 2;
        $condition_all_city['is_open'] = 1;
        $oCity = $database_area->field($database_field)->where($condition_all_city)->find();
        $condition_all_district['area_name'] = $district_name;
        $condition_all_district['area_type'] = 3;
        $condition_all_district['is_open'] = 1;
        $oDistrict = $database_area->field($database_field)->where($condition_all_district)->find();
        if($oCity){
            exit(json_encode(array('error'=>1,'msg'=>'获取成功','area_id'=>$oCity['area_id'],'area_name'=>$oCity['area_name'], 'now_area_id' => $oDistrict['area_id'])));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>'当前城市未开启，请选择别的城市')));
        }

    }


    public function service_evaluate(){
        $offerInfo = D('Service_offer')->where(array('uid'=>$this->user_session['uid'],'offer_id'=>intval($_GET['offer_id'])))->find();
        if(!is_array($offerInfo)){

            $this->error_tips('数据异常请重试！');
        }
        $this->assign('offerInfo',$offerInfo);
        $this->display();
    }

    public function service_evaluate_data(){
        if(IS_POST){
            $data = $_POST;
            $data['add_time'] = time();
            $data['uid'] = $this->user_session['uid'];
            $res = D('Service_offer_evaluate')->data($data)->add();
            if($res){
                D('Service_offer')->where(array('offer_id'=>$_POST['offer_id']))->data(array('status'=>7))->save();
                exit(json_encode(array('error'=>1,'msg'=>'评论成功','url'=>U('Service/price_list',array('publish_id'=>$_POST['publish_id'])))));
            }else{
                exit(json_encode(array('error'=>2,'msg'=>'评论失败请重试！')));
            }
        }
    }


    function distance($lat1, $lng1, $lat2, $lng2, $miles = true) {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;
        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat/2)*sin($dlat/2)+cos($lat1)*cos($lat2)*sin($dlng/2)*sin($dlng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;
        return ($miles ? ($km * 0.621371192) : $km);
    }


    /**
    * 计算两组经纬度坐标 之间的距离
    * params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km);
    * return m or km
    */
    function GetDistance($lat1, $lng1, $lat2, $lng2, $len_type = 2, $decimal = 2){
        $EARTH_RADIUS = 6378.137; //地球半径
        $PI = 3.1415926;
        $radLat1 = $lat1 * $PI / 180.0;
        $radLat2 = $lat2 * $PI / 180.0;
        $a = $radLat1 - $radLat2;
        $b = ($lng1 * $PI / 180.0) - ($lng2 * $PI / 180.0);
        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $s = $s * $EARTH_RADIUS;
        $s = round($s * 1000);
        if ($len_type > 1){
            $s /= 1000;
        }
        return round($s,2);
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

    // 计算两个收货地址的距离（直线）帮我买
    public function ajax_buy_distance(){
        $destance_sum = 0;
        if (C('config.service_is_riding_distance') && C('config.baidu_map_ak')) {
            import('@.ORG.longlat');
            $longlat_class = new longlat();
            $destance_sum = $longlat_class->getRidingDistance($_POST['start_lat'],$_POST['start_lng'],$_POST['end_lat'],$_POST['end_lng']);
            if ($destance_sum == -1) {
                $this->returnCode('50000008');
            }
        }
        $destance_sum || $destance_sum = getDistance($_POST['start_lat'],$_POST['start_lng'],$_POST['end_lat'],$_POST['end_lng']);
        $destance_sum = $destance_sum / 1000;

        exit(json_encode(array('destance_sum'=>$destance_sum)));
    }


    // 查询服务快派订单
    public function service_plat_order() {
        $publish_id = $_POST['publish_id'] + 0;
        if (empty($publish_id)) {
            $this->returnCode('50000004');
        }
        $order_info = M('Plat_order')->field('order_id')->where(array('business_type' => 'service', 'business_id' => $publish_id))->find();
        if (empty($order_info)) {
            $this->returnCode('10100001');
        }
        exit(json_encode(array('order_id'=>$order_info['order_id'])));
    }


    // 服务快派 帮我买帮我送 在未接单之前 用户 可取消订单-然后原路自行退款
    public function service_order_refund() {
        if (empty($this->user_session)) {
            exit(json_encode(array('error'=>3,'msg'=>'请先进行登录！', 'url' => U('Login/index'))));
        }
        $uid = $this->user_session['uid'];
        $publish_id = $_POST['publish_id'] + 0;
        if (empty($publish_id)) {
            $this->returnCode('50000004');
        }
        $order_info = M('Plat_order')->field('order_id')->where(array('business_type' => 'service', 'business_id' => $publish_id))->find();
        if (empty($order_info)) {
            $this->returnCode('10100001');
        }
        if ($order_info && $order_info['is_refund'] == 1) {
            $this->returnCode('10100001');
            exit(json_encode(array('error'=>2,'msg'=>'订单已经退款！')));
        }
        $publish_info = D('Service_user_publish')->field('status, order_sn, catgory_type')->where(array('publish_id' => $publish_id))->find();
        if ($publish_info && $publish_info['catgory_type'] == 1) {
            exit(json_encode(array('error'=>1,'msg'=>'只有帮我买和帮我送订单才可取消！')));
        }
        if ($publish_info && $publish_info['status'] != 2) {
            $publish_msg = array(
                1 => '已发布待付款',
                3 => '已服务待确认',
                4 => '已经完成',
                5 => '申请退款中',
                6 => '已经退款成功',
                7 => '服务已完成且评价成功',
                8 => '等待取货',
                9 => '配送中',
                10 => '用户已取消',
                11 => '服务过期',
                12 => '过期退款',
                13 => '退款失败',
            );
            if ($publish_info['status'] == 13) {
                exit(json_encode(array('error'=>2,'msg'=>'当前订单'.$publish_msg[$publish_info['status']].', 可以联系管理员！')));
            }
            exit(json_encode(array('error'=>2,'msg'=>'当前订单'.$publish_msg[$publish_info['status']].',不可取消订单！')));
        }
        $refund = $uid . '_' . $publish_id;
        if ($this->user_order_refund[$refund]) {
            exit(json_encode(array('error'=>2,'msg'=>'当前正在取消订单，请勿重复点击！')));
        }
        $this->user_order_refund[$refund] = true;
        // 进行原路退款
        $refund  = D('Plat_order')->order_refund(array('business_type'=>'service','business_id'=>$publish_id));
        if(!$refund['error']) {
            D('Service_user_publish')->where(array('publish_id' => $publish_id))->save(array('status' => 10));
            D('Deliver_supply')->updateStatusToZero($publish_id);
            $this->user_order_refund[$refund] = false;
            // 推送模板消息
            $now_user = D('User')->field(true)->where(array('uid' => $uid))->find();
            if ($now_user['openid']) {
                $href = C('config.site_url') . '/wap.php?g=Wap&c=Service&a=price_list&publish_id=' . $publish_id;
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $model->sendTempMsg('TM00017', array(
                    'href' => $href,
                    'wecha_id' => $now_user['openid'],
                    'first' => '您的跑腿订单退款已完成',
                    'OrderSn' => $publish_info['order_sn'],
                    'OrderStatus' => '已完成退款',
                    'remark' => date('Y-m-d H:i:s')
                ));
            }
            exit(json_encode(array('error'=>1,'msg'=>'取消订单成功！')));
        } else {
            M('Service_user_publish')->where(array('publish_id' => $publish_id))->save(array('status' => 13));
            M('Deliver_supply')->where(array('item' => 3, 'order_id' => $publish_id))->save(array('status' => 7));
            $this->user_order_refund[$refund] = false;
            exit(json_encode(array('error'=>1,'msg'=>'取消订单失败！')));
        }
    }

}