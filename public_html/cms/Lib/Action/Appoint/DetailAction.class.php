<?php

/*
 * 预约内页
 *
 */

class DetailAction extends BaseAction
{
    public function index()
    {
        $database_appoint = D('Appoint');
        $database_appoint_order = D('Appoint_order');
        $database_lottery = D('Lottery');
        $database_appoint_category = D('Appoint_category');
        $database_user_collect = D('User_collect');
        $database_merchant_workers = D('Merchant_workers');
        $database_merchant_workers_appoint = D('Merchant_workers_appoint');
        $appoint_id = $_GET['appoint_id'] + 0;

        $now_appoint = $database_appoint->get_appoint_by_appointId($appoint_id, 'hits-setInc');

        if (empty($now_appoint)) {
            $this->group_noexit_tips('预约不存在。');
        }

        //商家的活动
        $lotterys = $database_lottery->field(true)->where(array('token' => $now_appoint['mer_id'], 'statdate' => array('lt', time()), 'enddate' => array('gt', time())))->select();
        foreach ($lotterys as $lottery) {
            $index_right_adver[] = array('name' => $lottery['title'], 'pic' => $lottery['starpicurl'], 'url' => 'javascript:void(0);', 'id' => $lottery['id']);
        }
        $this->assign('index_right_adver', $index_right_adver);

        if (!empty($now_appoint['pic_info'])) {
            $merchant_image_class = new merchant_image();
            $now_appoint['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_appoint['pic_info']);
        }

        if (!empty($this->user_session)) {
            $condition_user_collect['type'] = 'group_detail';
            $condition_user_collect['id'] = $now_appoint['group_id'];
            $condition_user_collect['uid'] = $this->user_session['uid'];
            if ($database_user_collect->where($condition_user_collect)->find()) {
                $now_appoint['is_collect'] = true;
            }
        }

        $this->assign('now_group', $now_appoint);
        if (!empty($now_appoint['cat_fid'])) {
            $f_category = $database_appoint_category->get_category_by_id($now_appoint['cat_fid']);
        }

        $s_category = $database_appoint_category->get_category_by_id($now_appoint['cat_id']);
        if (empty($s_category)) {
            $this->group_noexit_tips('预约上级分类不存在。');
        }


        // 产品列表
        $appointProduct = D('Appoint_product')->get_productlist_by_appointId($appoint_id);
        $this->assign('appoint_product', $appointProduct);
        // 预约开始时间 结束时间
        $office_time = unserialize($now_appoint['office_time']);

        // 如果设置的营业时间为0点到0点则默认是24小时营业
        if ((count($office_time) < 1)|| (($office_time['open'] == '00:00') && ($office_time['close']=='00:00'))) {
            $office_time['open'] = '00:00';
            $office_time['close'] = '24:00';
        } else {
            foreach ($office_time as $i => $time) {
                if ($time['open'] == '00:00' && $time['close'] == '00:00') {
                    unset($office_time[$i]);
                }
            }
        }

        // 发起预约时候的起始时间 还有提前多长时间可预约
        $beforeTime = $now_appoint['before_time'] > 0 ? ($now_appoint['before_time']) * 3600 : 0;
        $gap = $now_appoint['time_gap'] * 60 > 0 ? $now_appoint['time_gap'] * 60 : 1800;

        /*foreach ($office_time as $i => $time) {
            $startTime = strtotime(date('Y-m-d') . ' ' . $time['open']);
            $endTime = strtotime(date('Y-m-d') . ' ' . $time['close']);
            for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
                $tempKey = date('H:i', $time);
                $tempTime[$tempKey]['time'] = $tempKey;
                $tempTime[$tempKey]['start'] = date('H:i', $time);
                $tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
                $tempTime[$tempKey]['order'] = 'no';
                if ((date('H:i') <= date('H:i', $time - $beforeTime))) {
                    $tempTime[$tempKey]['order'] = 'yes';
                    $tempTime[$tempKey]['remain_num'] = $now_appoint['appoint_people'];
                }
            }
        }*/

        $startTime = strtotime(date('Y-m-d') . ' ' . $office_time['open']);
        $endTime = strtotime(date('Y-m-d') . ' ' . $office_time['close']);
        for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
            $tempKey = date('H:i', $time);
            $tempTime[$tempKey]['time'] = $tempKey;
            $tempTime[$tempKey]['start'] = date('H:i', $time);
            $tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
            $tempTime[$tempKey]['order'] = 'no';
            if ((date('H:i') <= date('H:i', $time - $beforeTime))) {
                $tempTime[$tempKey]['order'] = 'yes';
                //$tempTime[$tempKey]['remain_num'] = $now_appoint['appoint_people'];
                $tempTime[$tempKey]['remain_num'] = 1;
            }
        }



        $startTimeAppoint = $now_appoint['start_time'] > strtotime('now') ? $now_appoint['start_time'] : strtotime('now');
        //$endTimeAppoint = $now_appoint['end_time'] > strtotime('+3 day') ? strtotime('+3 day') : $now_appoint['end_time'];
        $tmp_gap  =$now_appoint['time_gap'];
        if($tmp_gap > 0){
            $endTimeAppoint = $now_appoint['end_time'] > strtotime('+3 day') ? strtotime('+3 day') : $now_appoint['end_time'];
        }else{
            $endTimeAppoint = $now_appoint['end_time'] > strtotime('+30 day') ? strtotime('+30 day') : $now_appoint['end_time'];
        }


        /*$dateArray[date('Y-m-d', $startTimeAppoint)] = date('Y-m-d', $startTimeAppoint);
        $dateArray[date('Y-m-d', $endTimeAppoint)] = date('Y-m-d', $endTimeAppoint);
        for ($date = $startTimeAppoint; $date < $endTimeAppoint; $date = $date + 86400) {
            $dateArray[date('Y-m-d', $date)] = date('Y-m-d', $date);
        }
        ksort($dateArray);
        foreach ($dateArray as $i => $date) {
            $timeOrder[$date] = $tempTime;
        }

        ksort($timeOrder);
        foreach ($timeOrder as $i => $item) {
            foreach ($item as $key => $temval)
                if (strtotime($i . ' ' . $temval['end']) < strtotime('now') + $beforeTime && ($temval['order'] == 'yes')) {
                    $timeOrder[$i][$key]['order'] = 'no';
                } elseif (strtotime($i . ' ' . $temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i . ' ' . $temval['start']) > strtotime('now') + $beforeTime && ($temval['order'] == 'no')) {
                    $timeOrder[$i][$key]['order'] = 'yes';
                    //$timeOrder[$i][$key]['remain_num'] = $now_appoint['appoint_people'];
                    $timeOrder[$i][$key]['remain_num'] = 1;
                }
        }

        // 查询可预约时间点
        $appoint_num = D('Appoint_order')->get_appoint_remain_num($now_appoint['appoint_id']);
        if (count($appoint_num) > 0) {
            foreach ($appoint_num as $val) {
                $key = date('Y-m-d', strtotime($val['appoint_date']));
                if ($timeOrder[$key][$val['appoint_time']]['order'] != 'no') {
                    //if (isset($timeOrder[$key]) && ($now_appoint['appoint_people'] == $val['appointNum'])) {
                    if (isset($timeOrder[$key]) && (1 == $val['appointNum'])) {
                        $timeOrder[$key][$val['appoint_time']]['order'] = 'all';
                        $timeOrder[$key][$val['appoint_time']]['remain_num'] = 0;
                    } else {
                        //$timeOrder[$key][$val['appoint_time']]['remain_num'] = $now_appoint['appoint_people'] - $val['appointNum'];
                        $timeOrder[$key][$val['appoint_time']]['remain_num'] = 0;
                    }
                }
            }
        }

        $this->assign('timeOrder', $timeOrder);*/

        $dateArray[date('Y-m-d', $startTimeAppoint)] = date('Y-m-d', $startTimeAppoint);
        $dateArray[date('Y-m-d', $endTimeAppoint)] = date('Y-m-d', $endTimeAppoint);
        for ($date = $startTimeAppoint; $date < $endTimeAppoint; $date = $date + 86400) {
            $dateArray[date('Y-m-d', $date)] = date('Y-m-d', $date);
        }
        ksort($dateArray);


        if($tmp_gap > 0){
            foreach ($dateArray as $i => $date) {
                $timeOrder[$date] = $tempTime;
            }
            ksort($timeOrder);


            foreach ($timeOrder as $i => $tem) {
                foreach ($tem as $key => $temval)
                    if (strtotime($i . ' ' . $temval['end']) < strtotime('now') + $beforeTime && ($temval['order'] == 'yes')) {
                        $timeOrder[$i][$key]['order'] = 'no';
                    } elseif (strtotime($i . ' ' . $temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i . ' ' . $temval['start']) > strtotime('now') + $beforeTime && ($temval['order'] == 'no')) {
                        $timeOrder[$i][$key]['order'] = 'yes';
                    }
            }

            // 查询可预约时间点
            if ($now_appoint['is_store']) {
                $appoint_num = $database_appoint_order->get_worker_appoint_num($now_appoint['appoint_id'], $merchant_workers_id);
            } else {
                $appoint_num = $database_appoint_order->get_appoint_num($now_appoint['appoint_id']);
            }

            if (count($appoint_num) > 0) {
                foreach ($appoint_num as $val) {
                    $key = date('Y-m-d', strtotime($val['appoint_date']));
                    if ($timeOrder[$key][$val['appoint_time']]['order'] != 'no') {
                        if (isset($timeOrder[$key]) && (1 == $val['appointNum'])) {
                            $timeOrder[$key][$val['appoint_time']]['order'] = 'all';
                        }
                    }
                }
            }

            $this->assign('timeOrder', $timeOrder);
        }else{
            $this->assign('timeOrder', $dateArray);
        }

        // 自定义表单项
        $category = D('Appoint_category')->get_category_by_id($now_appoint['cat_id']);
        if (empty($category['cue_field'])) {
            $category = D('Appoint_category')->get_category_by_id($category['cat_fid']);
        }
        if ($category) {
            $cuefield = unserialize($category['cue_field']);
            foreach ($cuefield as $val) {
                $sort[] = $val['sort'];
            }
            array_multisort($sort, SORT_DESC, $cuefield);
        }

        $this->assign('formData', $cuefield);

        $this->assign('f_category', $f_category);
        $this->assign('s_category', $s_category);


        $where['appoint_id'] = $appoint_id;
        $merchant_workers_appoint_list = $database_merchant_workers_appoint->where($where)->getField('id,merchant_worker_id');
        if ($merchant_workers_appoint_list) {
            $Map['merchant_worker_id'] = array('in', $merchant_workers_appoint_list);
            $worker_list = $database_merchant_workers->where($Map)->select();
            $this->assign('worker_list', $worker_list);
        }

        //评价列表start
        $database_reply = D('Reply');
        $reply_list = $database_reply->get_page_reply_list($appoint_id , 2);
        $this->assign('reply_list', $reply_list);
        //评价列表end
        $this->display();
    }

    public function ajax_get_list()
    {
        $reply_return = D('Reply')->get_page_reply_list($_GET['parent_id'], $_GET['order_type'], $_POST['tab'], $_POST['order'], $_GET['store_count']);
        if ($reply_return['count']) {
            echo json_encode($reply_return);
        } else {
            echo '0';
        }
    }

    public function order()
    {
        if (IS_POST) {
            $store_id = $_POST['store_id'] + 0;
            $product_id = $_POST['product_id'] + 0;
            $appoint_time = explode(' ', $_POST['appoint_time']);
            $appoint_id = $_POST['appoint_id'] + 0;
            $merchant_worker_id = $_POST['merchant_worker_id'] + 0;
            $custom_field = serialize($_POST['custom_field']);
            $is_store = $_POST['is_store'] + 0;

            $database_appoint = D('Appoint');
            $database_appoint_order = D('Appoint_order');
            $now_appoint = $database_appoint->get_appoint_by_appointId($appoint_id);

            $now_appoint['store_id'] = $store_id ? $store_id : 0;
            $now_appoint['product_id'] = $product_id ? $product_id : 0;
            $now_appoint['appoint_date'] = reset($appoint_time);
            $now_appoint['appoint_time'] = end($appoint_time);
            $now_appoint['cue_field'] = $custom_field;
            $now_appoint['is_store'] = $is_store ? $is_store : 0;

            $result = $database_appoint_order->save_post_form($now_appoint, $this->user_session['uid'], 0, $merchant_worker_id);
            if ($result['error'] == 1) {
                $this->error($result['msg'],$result['url']);
            }

            // 如果需要定金
            if (intval($now_appoint['payment_status']) == 1) {
                $href = U('Index/Pay/check', array('order_id' => $result['order_id'], 'type' => 'appoint'));
            } else {
//                $resultOrder = $database_appoint_order->no_pay_after($result['order_id'], $now_appoint);
//                if ($resultOrder['error'] == 1) {
//                    $this->error($resultOrder['msg']);
//                }
                $href = U('User/Index/appoint_order_view', array('order_id' => $result['order_id']));
            }
            exit(json_encode(array('status' => 1, 'url' => $href)));
        } else {
            exit(json_encode(array('status' => 0, 'info' => '访问页面有误~~~')));
        }
    }

    /*public function ajaxAppointTime()
    {
        $appoint_id = $this->_post('appoint_id');
        $now_appoint = D('Appoint')->get_appoint_by_appointId($appoint_id, 'hits-setInc');
        $office_time = unserialize($now_appoint['office_time']);

        // 如果设置的营业时间为0点到0点则默认是24小时营业
        if (count($office_time) < 1) {
            $office_time[0]['open'] = '00:00';
            $office_time[0]['close'] = '24:00';
        } else {
            foreach ($office_time as $i => $time) {
                if ($time['open'] == '00:00' && $time['close'] == '00:00') {
                    unset($office_time[$i]);
                }
            }
        }
        // 发起预约时候的起始时间 还有提前多长时间可预约
        $beforeTime = $now_appoint['before_time'] > 0 ? ($now_appoint['before_time']) * 3600 : 0;
        $gap = $now_appoint['time_gap'] * 60 > 0 ? $now_appoint['time_gap'] * 60 : 1800;

        foreach ($office_time as $i => $time) {
            $startTime = strtotime(date('Y-m-d') . ' ' . $time['open']);
            $endTime = strtotime(date('Y-m-d') . ' ' . $time['close']);
            for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
                $tempKey = date('H:i', $time) . '-' . date('H:i', $time + $gap);
                $tempTime[$tempKey]['time'] = $tempKey;
                $tempTime[$tempKey]['start'] = date('H:i', $time);
                $tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
                $tempTime[$tempKey]['order'] = 'no';
//				if(strtotime($i.' '.$temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i.' '.$temval['start']) > strtotime('now')+ $beforeTime && ($temval['order'] == 'no')){
//					$tempTime[$tempKey]['order'] = 'yes';
//				}
                if ((date('H:i') < date('H:i', $time - $beforeTime))) {
                    $tempTime[$tempKey]['order'] = 'yes';
                    $tempTime[$tempKey]['remain_num'] = $now_appoint['appoint_people'];
                }

            }
        }

        $startTimeAppoint = $now_appoint['start_time'] > strtotime('now') ? $now_appoint['start_time'] : strtotime('now');
        $endTimeAppoint = $now_appoint['end_time'] > strtotime('+3 day') ? strtotime('+3 day') : $now_appoint['end_time'];

        $dateArray[date('Y-m-d', $startTimeAppoint)] = date('Y-m-d', $startTimeAppoint);
        $dateArray[date('Y-m-d', $endTimeAppoint)] = date('Y-m-d', $endTimeAppoint);
        for ($date = $startTimeAppoint; $date < $endTimeAppoint; $date = $date + 86400) {
            $dateArray[date('Y-m-d', $date)] = date('Y-m-d', $date);
        }
        ksort($dateArray);
        ksort($dateArray);
        foreach ($dateArray as $i => $date) {
            $timeOrder[$date] = $tempTime;
        }
        ksort($timeOrder);
//		foreach($timeOrder as $i=>$tem){
//			foreach ($tem as $key=>$temval)
//				if(strtotime($i.' '.$temval['end'])<strtotime('now')+$beforeTime && ($temval['order'] == 'yes')){
//					$timeOrder[$i][$key]['order'] = 'no';
//			    }elseif(strtotime($i.' '.$temval['end'])>strtotime('now')+$beforeTime && ($temval['order'] == 'no')){
//					$timeOrder[$i][$key]['order'] = 'yes';
//			    }
//		}

        foreach ($timeOrder as $i => $tem) {
            foreach ($tem as $key => $temval)
                if (strtotime($i . ' ' . $temval['end']) < strtotime('now') + $beforeTime && ($temval['order'] == 'yes')) {
                    $timeOrder[$i][$key]['order'] = 'no';
                } elseif (strtotime($i . ' ' . $temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i . ' ' . $temval['start']) > strtotime('now') + $beforeTime && ($temval['order'] == 'no')) {
                    $timeOrder[$i][$key]['order'] = 'yes';
                    $timeOrder[$i][$key]['remain_num'] = $now_appoint['appoint_people'];
                }
        }

        // 查询可预约时间点
        $appoint_num = D('Appoint_order')->get_appoint_remain_num($now_appoint['appoint_id']);
        if (count($appoint_num) > 0) {
            foreach ($appoint_num as $val) {
                $key = date('Y-m-d', strtotime($val['appoint_date']));
                if ($timeOrder[$key][$val['appoint_time']]['order'] != 'no') {
                    if (isset($timeOrder[$key]) && ($now_appoint['appoint_people'] == $val['appointNum'])) {
                        $timeOrder[$key][$val['appoint_time']]['order'] = 'all';
                        $timeOrder[$key][$val['appoint_time']]['remain_num'] = 0;
                    } else {
                        $timeOrder[$key][$val['appoint_time']]['remain_num'] = $now_appoint['appoint_people'] - $val['appointNum'];
                    }
                }
            }
        }
        exit(json_encode(array('status' => 1, 'timeOrder' => $timeOrder)));
    }*/


    public function ajaxAppointTime()
    {
        if(IS_POST){
            $appoint_id = $_POST['appoint_id'] + 0;
            $now_appoint = D('Appoint')->get_appoint_by_appointId($appoint_id, 'hits-setInc');
            $office_time = unserialize($now_appoint['office_time']);

            // 如果设置的营业时间为0点到0点则默认是24小时营业
            if (count($office_time) < 1) {
                $office_time[0]['open'] = '00:00';
                $office_time[0]['close'] = '24:00';
            } else if(!$office_time['open']){
                foreach ($office_time as $i => $time) {
                    if ($time['open'] == '00:00' && $time['close'] == '00:00') {
                        unset($office_time[$i]);
                    }
                }
            }else if($office_time['open'] == '00:00' && $office_time['close'] == '00:00'){
                $office_time['open'] = '00:00';
                $office_time['close'] = '24:00';
            }
            // 发起预约时候的起始时间 还有提前多长时间可预约
            $beforeTime = $now_appoint['before_time'] > 0 ? ($now_appoint['before_time']) * 3600 : 0;
            $gap = $now_appoint['time_gap'] * 60 > 0 ? $now_appoint['time_gap'] * 60 : 1800;
            $startTime = strtotime(date('Y-m-d') . ' ' . $office_time['open']);
            $endTime = strtotime(date('Y-m-d') . ' ' . $office_time['close']);
            for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
                $tempKey = date('H:i', $time) . '-' . date('H:i', $time + $gap);
                $tempTime[$tempKey]['time'] = $tempKey;
                $tempTime[$tempKey]['start'] = date('H:i', $time);
                $tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
                $tempTime[$tempKey]['order'] = 'no';
                if ((date('H:i') < date('H:i', $time - $beforeTime))) {
                    $tempTime[$tempKey]['order'] = 'yes';
                    //$tempTime[$tempKey]['remain_num'] = $now_appoint['appoint_people'];
                    $tempTime[$tempKey]['remain_num'] = 1;
                }

            }

            $startTimeAppoint = $now_appoint['start_time'] > strtotime('now') ? $now_appoint['start_time'] : strtotime('now');
            $endTimeAppoint = $now_appoint['end_time'] > strtotime('+3 day') ? strtotime('+3 day') : $now_appoint['end_time'];

            $dateArray[date('Y-m-d', $startTimeAppoint)] = date('Y-m-d', $startTimeAppoint);
            $dateArray[date('Y-m-d', $endTimeAppoint)] = date('Y-m-d', $endTimeAppoint);
            for ($date = $startTimeAppoint; $date < $endTimeAppoint; $date = $date + 86400) {
                $dateArray[date('Y-m-d', $date)] = date('Y-m-d', $date);
            }
            ksort($dateArray);
            ksort($dateArray);
            foreach ($dateArray as $i => $date) {
                $timeOrder[$date] = $tempTime;
            }
            ksort($timeOrder);
            foreach ($timeOrder as $i => $tem) {
                foreach ($tem as $key => $temval)
                    if (strtotime($i . ' ' . $temval['end']) < strtotime('now') + $beforeTime && ($temval['order'] == 'yes')) {
                        $timeOrder[$i][$key]['order'] = 'no';
                    } elseif (strtotime($i . ' ' . $temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i . ' ' . $temval['start']) > strtotime('now') + $beforeTime && ($temval['order'] == 'no')) {
                        $timeOrder[$i][$key]['order'] = 'yes';
                        //$timeOrder[$i][$key]['remain_num'] = $now_appoint['appoint_people'];
                        $timeOrder[$i][$key]['remain_num'] = 0;
                    }
            }

            // 查询可预约时间点
            $appoint_num = D('Appoint_order')->get_appoint_remain_num($now_appoint['appoint_id']);
            if (count($appoint_num) > 0) {
                foreach ($appoint_num as $val) {
                    $key = date('Y-m-d', strtotime($val['appoint_date']));
                    if ($timeOrder[$key][$val['appoint_time']]['order'] != 'no') {
                        //if (isset($timeOrder[$key]) && ($now_appoint['appoint_people'] == $val['appointNum'])) {
                        if (isset($timeOrder[$key]) && (1 == $val['appointNum'])) {
                            $timeOrder[$key][$val['appoint_time']]['order'] = 'all';
                            $timeOrder[$key][$val['appoint_time']]['remain_num'] = 0;
                        } else {
                            //timeOrder[$key][$val['appoint_time']]['remain_num'] = $now_appoint['appoint_people'] - $val['appointNum'];
                            $timeOrder[$key][$val['appoint_time']]['remain_num'] = 0;
                        }
                    }
                }
            }
            exit(json_encode(array('status' => 1, 'timeOrder' => $timeOrder)));
        }else{
            $this->error('访问页面有误！~~~');
        }

    }



    public function ajaxWorkerTime()
    {
        if(IS_POST){
            $database_merchant_workers = D('Merchant_workers');
            $worker_id = $_POST['worker_id'] + 0;
            if (!$worker_id) {
                exit(json_encode(array('status' => 0)));
            }

            // 预约开始时间 结束时间
            $merchant_workers_info = $database_merchant_workers->where(array('merchant_worker_id' => $worker_id))->find();
            $office_time = unserialize($merchant_workers_info['office_time']);

            // 如果设置的营业时间为0点到0点则默认是24小时营业
            if (count($office_time) < 1) {
                $office_time[0]['open'] = '00:00';
                $office_time[0]['close'] = '24:00';
            } else {
                foreach ($office_time as $i => $time) {
                    if ($time['open'] == '00:00' && $time['close'] == '00:00') {
                        unset($office_time[$i]);
                    }
                }
            }

            // 发起预约时候的起始时间 还有提前多长时间可预约
            $beforeTime = $merchant_workers_info['before_time'] > 0 ? ($merchant_workers_info['before_time']) * 3600 : 0;
            $gap = $merchant_workers_info['time_gap'] * 60 > 0 ? $merchant_workers_info['time_gap'] * 60 : 1800;

            /*foreach ($office_time as $i => $time) {
                $startTime = strtotime(date('Y-m-d') . ' ' . $time['open']);
                $endTime = strtotime(date('Y-m-d') . ' ' . $time['close']);
                for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
                    $tempKey = date('H:i', $time) . '-' . date('H:i', $time + $gap);
                    $tempTime[$tempKey]['time'] = $tempKey;
                    $tempTime[$tempKey]['start'] = date('H:i', $time);
                    $tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
                    $tempTime[$tempKey]['order'] = 'no';

                    if ((date('H:i') < date('H:i', $time - $beforeTime))) {
                        $tempTime[$tempKey]['order'] = 'yes';
                        $tempTime[$tempKey]['remain_num'] = $merchant_workers_info['appoint_people'];
                    }
                }
            }*/


            $startTime = strtotime(date('Y-m-d') . ' ' . reset($office_time));
            $endTime = strtotime(date('Y-m-d') . ' ' . end($office_time));
            for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
                $tempKey = date('H:i', $time) . '-' . date('H:i', $time + $gap);
                $tempTime[$tempKey]['time'] = $tempKey;
                $tempTime[$tempKey]['start'] = date('H:i', $time);
                $tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
                $tempTime[$tempKey]['order'] = 'no';

                if ((date('H:i') < date('H:i', $time - $beforeTime))) {
                    $tempTime[$tempKey]['order'] = 'yes';
                    //$tempTime[$tempKey]['remain_num'] = $merchant_workers_info['appoint_people'];
                    $tempTime[$tempKey]['remain_num'] = 0;
                }
            }

            $appoint_id = $_POST['appoint_id'] + 0;
            $now_appoint = D('Appoint')->get_appoint_by_appointId($appoint_id, 'hits-setInc');
            $startTimeAppoint = $now_appoint['start_time'] > strtotime('now') ? $now_appoint['start_time'] : strtotime('now');
            $endTimeAppoint = $now_appoint['end_time'] > strtotime('+3 day') ? strtotime('+3 day') : $now_appoint['end_time'];
            $dateArray[date('Y-m-d', $startTimeAppoint)] = date('Y-m-d', $startTimeAppoint);
            $dateArray[date('Y-m-d', $endTimeAppoint)] = date('Y-m-d', $endTimeAppoint);
            for ($date = $startTimeAppoint; $date < $endTimeAppoint; $date = $date + 86400) {
                $dateArray[date('Y-m-d', $date)] = date('Y-m-d', $date);
            }
            ksort($dateArray);
            foreach ($dateArray as $i => $date) {
                $timeOrder[$date] = $tempTime;
            }
            ksort($timeOrder);
            foreach ($timeOrder as $i => $item) {
                foreach ($item as $key => $temval)
                    if (strtotime($i . ' ' . $temval['end']) < strtotime('now') + $beforeTime && ($temval['order'] == 'yes')) {
                        $timeOrder[$i][$key]['order'] = 'no';
                    } elseif (strtotime($i . ' ' . $temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i . ' ' . $temval['start']) > strtotime('now') + $beforeTime && ($temval['order'] == 'no')) {
                        $timeOrder[$i][$key]['order'] = 'yes';
                        //$timeOrder[$i][$key]['remain_num'] = $merchant_workers_info['appoint_people'];
                        $timeOrder[$i][$key]['remain_num'] = 1;
                    }
            }

            print_r($timeOrder);exit;
            // 查询可预约时间点
            $appoint_num = D('Appoint_order')->get_worker_appoint_num($now_appoint['appoint_id'], $worker_id);
            if (count($appoint_num) > 0) {
                foreach ($appoint_num as $val) {
                    $key = date('Y-m-d', strtotime($val['appoint_date']));
                    if ($timeOrder[$key][$val['appoint_time']]['order'] != 'no') {
                        //if (isset($timeOrder[$key]) && ($merchant_workers_info['appoint_people'] == $val['appointNum'])) {
                        if (isset($timeOrder[$key]) && (1 == $val['appointNum'])) {
                            $timeOrder[$key][$val['appoint_time']]['order'] = 'all';
                            $timeOrder[$key][$val['appoint_time']]['remain_num'] = 0;
                        } else {
                            //$timeOrder[$key][$val['appoint_time']]['remain_num'] = $merchant_workers_info['appoint_people'] - $val['appointNum'];
                            $timeOrder[$key][$val['appoint_time']]['remain_num'] = 0;
                        }
                    }
                }
            }
            exit(json_encode(array('timeOrder' => $timeOrder, 'status' => 1)));
        }else{
            $this->error('访问页面有误！~~~');
        }

    }


    public function ajaxWorker()
    {
        $database_merchant_workers = D('Merchant_workers');
        $database_merchant_workers_appoint = D('Merchant_workers_appoint');

        $merchant_store_id = $_POST['merchant_store_id'] + 0;
        $appoint_id = $_POST['appoint_id'] + 0;
        //$merchant_workers_id=$_POST['merchant_workers_id'];

        $where['merchant_store_id'] = $merchant_store_id;
        $where['appoint_id'] = $appoint_id;
        $merchant_workers_appoint_list = $database_merchant_workers_appoint->where($where)->getField('id,merchant_worker_id');

        if ($merchant_workers_appoint_list) {
            $Map['merchant_worker_id'] = array('in', $merchant_workers_appoint_list);
            $worker_list = $database_merchant_workers->where($Map)->select();

            exit(json_encode(array('status' => 1, 'worker_list' => $worker_list)));
        } else {
            exit(json_encode(array('status' => 0)));
        }

    }

    public function group_noexit_tips($fix)
    {
        $this->assign('jumpUrl', $this->config['site_url']);
        $this->error('您不能查看该商品！' . $fix);
    }
}