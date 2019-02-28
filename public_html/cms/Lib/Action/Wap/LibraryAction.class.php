<?php

class LibraryAction extends BaseAction
{

    //取快递页面 自己取 帮别人取
    public function receive()
    {
        $this->display();
    }

    //输入取货吗页面
    public function fetch_code()
    {
        $database_express = D('Express');
        $express_list = $database_express->get_express_list();
        $this->assign('express_list', $express_list);
        $this->display();
    }

    public function collection_list()
    {
        if (!$this->user_session) {
            $this->error_tips('请先进行登录', U('Login/index'));
        }

        if (!$_POST['fetch_code']) {
            $this->error_tips('请输入取件码！');
        }
        $database_house_village_express = D('House_village_express');
        $database_express = D('Express');
        $info = $database_house_village_express->where(array('fetch_code' => $_POST['fetch_code'], 'status' => 0))->find();
        if (is_array($info)) {
            $info['express_name'] = $database_express->where(array('id' => $info['express_type']))->getField('name');
            $list = $database_house_village_express->where(array('village_id' => $info['village_id'], 'phone' => $info['phone'], 'status' => 0, 'id' => array('neq', $info['id'])))->select();

        } else {
            $this->error_tips('没有查询到您要取的物品！', U('fetch_code'));
        }

        foreach ($list as $key => $value) {
            $list[$key]['express_name'] = $database_express->where(array('id' => $value['express_type']))->getField('name');
        }
        // dump($info);
        $this->assign('info', $info);
        $this->assign('list', $list);

        // $list = array();
        // foreach ($_POST['fetch_code'] as $key => $value) {
        //      $info = $database_house_village_express->where(array('fetch_code'=>$value))->find();
        //      $info['express_name'] = $database_express->where(array('id'=>$info['express_type']))->getField('name');
        //      $list[] = $info;
        // }
        // $this->assign('list',$list);
        $this->display();
    }

    //确认收货
    public function confirm_receipt()
    {
        $database_house_village_express = D('House_village_express');
        $res = $database_house_village_express->where(array('id' => $_POST['id']))->data(array('status' => 1, 'delivery_time' => time(), 'take_uid' => $this->user_session['uid'], 'take_nickname' => $this->user_session['nickname']))->save();
        if ($res) {
            exit(json_encode(array('error' => 1, 'msg' => '修改成功！')));
        } else {
            exit(json_encode(array('error' => 2, 'msg' => '修改失败，请重试！')));
        }
    }

    //确认收货--全部取货
    public function confirm_receipt_all()
    {
        $database_house_village_express = D('House_village_express');
        $res = $database_house_village_express->where(array('village_id' => $_POST['village_id'], 'phone' => $_POST['phone']))->data(array('status' => 1, 'delivery_time' => time(), 'take_uid' => $this->user_session['uid'], 'take_nickname' => $this->user_session['nickname']))->save();
        if ($res) {
            exit(json_encode(array('error' => 1, 'msg' => '修改成功！')));
        } else {
            exit(json_encode(array('error' => 2, 'msg' => '修改失败，请重试！')));
        }
    }


    public function express_service_list()
    {
        if (!$this->user_session) {
            $this->error_tips('请先进行登录', U('Login/index'));
        }

        $village_id = $_GET['village_id'] + 0;

        $this->get_village($village_id);
        $database_house_village_express = D('House_village_express');

        $has_express_service = $this->getHasConfig($village_id, 'has_express_service');
        if ($has_express_service) {
            $where['village_id'] = $village_id;
            $where['uid'] = $this->user_session['uid'];
            $list = $database_house_village_express->express_service_list($where);
            $express_config = M('House_village_express_config')->where(array('village_id' => $village_id))->find();
            $express_config['status'] = $express_config['status'];
            $this->assign('list', $list['list']);
            $this->assign('express_config', $express_config);
            $this->display();
        } else {
            $this->error_tips('小区未开通相关服务！');
        }
    }

    public function ajax_express_appoint()
    {
        if (IS_AJAX) {

            if (empty($this->user_session)) {
                exit(json_encode(array('status' => 0, 'info' => '未获取到您的帐号信息，请重试')));
            }


            $now_user = D('User')->get_user($this->user_session['uid']);
            if (empty($now_user)) {
                exit(json_encode(array('status' => 0, 'info' => '未获取到您的帐号信息，请重试')));
            }

            $use_money = $_POST['express_collection_price'] + 0;


            $database_house_village_express_order = D('House_village_express_order');

            $order_where['express_id'] = $_POST['express_id'] + 0;
            $order_where['paid'] = 0;

            $now_order = $database_house_village_express_order->house_village_express_order_detail($order_where);
            $now_order = $now_order['detail'];
            $express_config = M('House_village_express_config')->where(array('village_id' => $_POST['village_id']))->find();
            $time = strtotime($_POST['send_time']);
            $start_time = strtotime(date('G:i:s', $express_config['start_time']));
            $end_time = strtotime(date('G:i:s', $express_config['end_time']));

            // 配送时间段
            $e_time = strtotime(date('G:i:s', strtotime($_POST['send_time'])));

            if (empty($_POST['send_time'])) {
                exit(json_encode(array('status' => 0, 'info' => '预约时间不能为空！')));
            }

            // if ($time < $start_time) {
            //     exit(json_encode(array('status' => 0, 'info' => '预约时间不能比当前时间小')));
            // }

            // if ($time > $end_time) {
            //     exit(json_encode(array('status' => 0, 'info' => '该时间段物业不送货上门！')));
            // }
            //20180706
            if ($time < time()) {
                exit(json_encode(array('status' => 0, 'info' => '预约时间不能比当前时间小')));
            }
            if ($e_time<$start_time || $e_time>$end_time) {
                exit(json_encode(array('status' => 0, 'info' => '该时间段物业不送货上门！')));
            }

            if ($now_order) {
                $now_user = D('User')->get_user($this->user_session['uid']);
                if (empty($now_user)) {
                    $this->error_tips('未获取到您的帐号信息，请重试');
                }

                $use_money = $now_order['express_collection_price'];

                $pay_order_param = array(
                    'business_type' => 'house_village_express',
                    'business_id' => $now_order['order_id'],
                    'order_name' => '快递代送',
                    'uid' => $now_user['uid'],
                    'total_money' => $use_money,
                    'wx_cheap' => 0,
                );

                $plat_order_result = D('Plat_order')->add_order($pay_order_param);

                if ($plat_order_result['error_code']) {
                    $this->error($plat_order_result['error_msg']);
                } else {
                    $this->success('', U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')));

                    // redirect(U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')));
                }
                exit;
                if ($use_money != 0) {
                    if ($now_user['now_money'] < $use_money) {
                        exit(json_encode(array('order_id' => $now_order['order_id'], 'status' => -4, 'info' => '您的帐户余额为 <span>' . $now_user['now_money'] . '</span> 元，请先充值帐户余额', 'recharge' => $use_money - $now_user['now_money'])));
                    }
                    $save_result = D('User')->user_money($now_user['uid'], $use_money, '快递送达。');
                    if ($save_result['error_code']) {
                        exit(json_encode(array('status' => 0, 'info' => $save_result['error_code'])));
                    }

                    $data['paid'] = 1;
                    $data['pay_time'] = time();

                    $edit_where['order_id'] = $now_order['order_id'];
                    $result = $database_house_village_express_order->house_village_express_order_edit($edit_where, $data);

                    if (!$result) {
                        exit(json_encode(array('status' => 0, 'info' => '数据处理有误！')));
                    } else {
                        if ($result['status']) {
                            exit(json_encode(array('status' => 1, 'info' => '付费成功！')));
                        } else {
                            exit(json_encode(array('status' => 0, 'info' => '付费失败！')));
                        }
                    }
                } else {
                    $data['paid'] = 1;
                    $data['pay_time'] = time();

                    $edit_where['order_id'] = $now_order['order_id'];
                    $result = $database_house_village_express_order->house_village_express_order_edit($edit_where, $data);
                    exit(json_encode(array('status' => 1, 'info' => '提交成功！')));
                }
            }

            $result = $database_house_village_express_order->house_village_express_order_add($_POST);

            if ($result['status']==0) {
                exit(json_encode(array('status' => 0, 'info' => '数据处理有误！')));
            } else {
                $now_user = D('User')->get_user($this->user_session['uid']);
                if (empty($now_user)) {
                    $this->error_tips('未获取到您的帐号信息，请重试');
                }
                $order_where['express_id'] = $result['order_id'] + 0;
                $order_where['paid'] = 0;
                $now_order = $database_house_village_express_order->house_village_express_order_detail($order_where);
                $now_order = $now_order['detail'];
                $use_money = $now_order['express_collection_price'];

                $pay_order_param = array(
                    'business_type' => 'house_village_express',
                    'business_id' => $now_order['order_id'],
                    'order_name' => '快递代送',
                    'uid' => $now_user['uid'],
                    'total_money' => $use_money,
                    'wx_cheap' => 0,
                );

                $plat_order_result = D('Plat_order')->add_order($pay_order_param);
                if ($plat_order_result['error_code']) {
                    $this->error($plat_order_result['error_msg']);
                } else {
                    $this->success('', U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')));

                    // redirect(U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')));
                }

                exit;
                if ($result['status']) {
                    if ($use_money != 0) {
                        if ($now_user['now_money'] < $use_money) {
                            exit(json_encode(array('order_id' => $database_house_village_express_order->getLastInsID(), 'status' => -4, 'info' => '您的帐户余额为 <span>' . $now_user['now_money'] . '</span> 元，请先充值帐户余额', 'recharge' => $use_money - $now_user['now_money'])));
                        }
                        $save_result = D('User')->user_money($now_user['uid'], $use_money, '快递送达。');

                        if ($save_result['error_code']) {
                            exit(json_encode(array('status' => 0, 'info' => $save_result['error_code'])));
                        }

                        $data['paid'] = 1;
                        $data['pay_time'] = time();

                        $edit_where['order_id'] = $result['order_id'];
                        $result = $database_house_village_express_order->house_village_express_order_edit($edit_where, $data);

                        if (!$result) {
                            exit(json_encode(array('status' => 0, 'info' => '数据处理有误！')));
                        } else {
                            if ($result['status']) {
                                $this->send_to_village($_POST['village_id'], $_POST['express_id']);
                                exit(json_encode(array('status' => 1, 'info' => '付费成功！')));
                            } else {
                                exit(json_encode(array('status' => 1, 'info' => '付费失败！')));
                            }
                        }
                        exit(json_encode(array('status' => 1, 'info' => $result['msg'])));
                    } else {
                        $data['paid'] = 1;
                        $data['pay_time'] = time();

                        $edit_where['order_id'] = $result['order_id'];
                        $result = $database_house_village_express_order->house_village_express_order_edit($edit_where, $data);
                        $this->send_to_village($_POST['village_id'], $_POST['express_id']);
                        exit(json_encode(array('status' => 1, 'info' => '提交成功！')));
                    }

                } else {
                    exit(json_encode(array('status' => 0, 'info' => $result['msg'])));
                }
            }
        } else {
            $this->error_tips('访问页面有误！');
        }
    }

    public function send_to_village($village_id, $id)
    {
        $express_config = M('House_village_express_config')->where(array('village_id' => $village_id))->find();
        $order_info = M('House_village_express')->field('e.id,o.send_time,u.name,ex.name as express_name , e.express_no,v.village_name')->join('as e LEFT JOIN ' . C('DB_PREFIX') . 'house_village_express_order  o ON e.id = o.express_id left join ' . C('DB_PREFIX') . 'express ex ON e.express_type = ex.id left join ' . C('DB_PREFIX') . 'house_village v ON e.village_id = v.village_id left join  ' . C('DB_PREFIX') . 'house_village_user_bind u ON e.uid = u.uid')->where(array('e.village_id' => $_POST['village_id'], 'e.id' => $id))->find();
        if ($this->config['village_sms'] && $order_info['send_time'] > 0) {
            $sms_data = array('mer_id' => $express_config['village_id'], 'store_id' => 0, 'type' => 'village_express');
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $express_config['notice_phone'];
            $sms_data['sendto'] = 'village';
            $sms_data['content'] = '【提醒】您好，' . $order_info['village_name'] . $order_info['name'] . '业主，已预约快递代送服务，代送时间:' . date('Y-m-d H:i', $order_info['send_time']) . '，' . $order_info['express_name'] . '，单号：' . $order_info['express_no'] . '。[' . $this->config['site_name'] . ']';
            Sms::sendSms($sms_data);
        }
    }

    public function express_submit()
    {
        $order_id = $_GET['order_id'];
        $database_house_village_express_order = D('House_village_express_order');
        $where['order_id'] = $order_id;
        $order_info = $database_house_village_express_order->house_village_express_order_detail($where);
        $order_info = $order_info['detail'];

        if ($order_info['paid'] > 0) {
            $this->error_tips('该订单已支付。');
        }

        if (empty($this->user_session)) {
            $this->error_tips('请先进行登录', U('Login/index'));
        }


        if ($now_user['now_money'] < $use_money) {
            $this->error_tips('您的帐户余额为 <span>' . $now_user['now_money'] . '</span> 元，请先充值帐户余额');
        }
        $save_result = D('User')->user_money($now_user['uid'], $use_money, '快递送达。');
        if ($save_result['error_code']) {
            $this->error_tips($save_result['error_code']);
        }

        $data['paid'] = 1;
        $data['pay_time'] = time();

        $edit_where['order_id'] = $order_id;
        $result = $database_house_village_express_order->house_village_express_order_edit($edit_where, $data);
        if (!$result) {
            $this->error_tips('数据处理有误！');
        } else {
            if ($result['status']) {
                $village_user['uid'] = $now_user['uid'];
                $village_user['village_id'] = $order_info['village_id'];

                $bind_user = D('House_village_user_bind')->house_village_user_bind_detail($village_user);

                $data_village_order['order_name'] = '快递代送';
                $data_village_order['order_type'] = 'express';
                $data_village_order['uid'] = $now_user['uid'];
                $data_village_order['village_id'] = $order_info['village_id'];
                $data_village_order['money'] = $order_info['express_collection_price'];
                $data_village_order['time'] = $data['pay_time'];
                $data_village_order['pay_time'] = $data['pay_time'];
                $data_village_order['paid'] = 1;
                $data_village_order['third_id'] = $order_id;
                $data_village_order['check_time'] = $data['pay_time'];
                $data_village_order['bind_id'] = $bind_user['info']['pigcms_id'];
                $data_village_order['pay_type'] = 2; //在线支付
                M('House_village_pay_order')->add($data_village_order);
                $this->success_tips('付费成功！', U('Library/express_service_list', array('village_id' => $village_user['village_id'])));
            } else {
                $this->error_tips('付费失败！');
            }
        }


    }

    public function express_appoint()
    {
        $village_id = $_GET['village_id'] + 0;
        $this->get_village($village_id);

        $datatabase_house_village_express_order = D('House_village_express_order');

        $where['express_id'] = $_GET['id'] + 0;
        $now_order = $datatabase_house_village_express_order->house_village_express_order_detail($where);

        $now_order = $now_order['detail'];

        $now_express = M('House_village_express')->where(array('id' => $_GET['id']))->find();
        if ($now_order) {
            if ($now_order['paid'] > 0) {
                $this->error_tips('订单已支付。请您耐心等待。');
            }
        }
        $express_config = M('House_village_express_config')->where(array('village_id' => $village_id))->find();
        $this->assign('express_config', $express_config);
        $this->assign('now_express', $now_express);
        $this->display();
    }

    public function submit()
    {
        if (empty($this->user_session)) {
            exit(json_encode(array('status' => 0, 'info' => '未获取到您的帐号信息，请重试')));
        }
        $now_user = D('User')->get_user($this->user_session['uid']);
        if (empty($now_user)) {
            exit(json_encode(array('status' => 0, 'info' => '未获取到您的帐号信息，请重试')));
        }

        $use_money = $_POST['express_collection_price'] + 0;

        if ($use_money != 0) {
            if ($now_user['now_money'] < $use_money) {
                exit(json_encode(array('status' => -4, 'info' => '您的帐户余额为 <span>' . $now_user['now_money'] . '</span> 元，请先充值帐户余额', 'recharge' => $use_money - $now_user['now_money'])));
            }
            $save_result = D('User')->user_money($now_user['uid'], $use_money, '快递代送');
            if ($save_result['error_code']) {
                exit(json_encode(array('status' => 0, 'info' => $save_result['error_code'])));
            }
        }
    }

    public function visitor_list()
    {
        if (!$this->user_session) {
            $this->error_tips('请先进行登录', U('Login/index'));
        }


        $database_house_village_visitor = D('House_village_visitor');

        $village_id = $_GET['village_id'] + 0;
        $this->get_village($village_id);
        $has_visitor = $this->getHasConfig($village_id, 'has_visitor');
        if ($has_visitor) {
            $where['village_id'] = $village_id;
            $where['owner_uid'] = $this->user_session['uid'];
            $list = $database_house_village_visitor->house_village_visitor_list($where);
            if (!$list) {
                $this->error('数据处理有误！');
            } else {
                $this->assign('list', $list['list']);
                $this->assign('visitor_type', $database_house_village_visitor->visitor_type);
            }
            $this->display();
        } else {
            $this->error_tips('小区未开通相关服务！');
        }

    }

    // 访客开门
    public function visitor_list_open()
    {
        if (empty($this->user_session)) {
            if (IS_POST) {
                $this->check_ajax_error_tips('请先进行登录', U('Login/index', array('referer' => urlencode(U('House/village_my', array('village_id' => $_GET['village_id']))))));
            } else {
                $this->error_tips('请先进行登录', U('Login/index', array('referer' => urlencode(U('House/village_my', array('village_id' => $_GET['village_id']))))));
            }
        }
        $uid = $this->user_session['uid'];
        $village_id = $_GET['village_id'] + 0;
        if (empty($village_id)) {
            $this->error_tips('未获取到您的社区信息，请重试', U('Login/index', array('referer' => urlencode(U('House/village_my', array('village_id' => $_GET['village_id']))))));
        }
        $where['uid'] = $uid;
        $where['village_id'] = $village_id;
        $aUserSelect = M('House_village_user_bind')->distinct(true)->field(array('village_id', 'floor_id', 'property_price', 'property_endtime', 'phone', 'type'))->where($where)->select();


        $aDoor = array();

        if (!empty($aUserSelect)) {
            foreach ($aUserSelect as $k => $v) {
                if ($v['type'] == 4) continue;
                $condition_door['door_status'] = 1;
                $condition_door['village_id'] = $v['village_id'];
                $aDoorList = M('House_village_door')->distinct(true)->field(true)->where($condition_door)->select();

                // 是否允许开门 false 允许
                $ban_open_status = !$v['open_door'];

                // 允许开门情况下继续判断
                if ($ban_open_status) {
                    $now_house = M('House_village')->where(array('village_id' => $v['village_id']))->find();
                    if (!$v['property_endtime']) {
                        $ban_open_status = false;
                    } else {
                        $owe_property_days = (strtotime(date('y-m-d', time())) - strtotime(date('y-m-d', $v['property_endtime']))) / 86400;
                        $ban_open_status = false;
                        if (!$now_house['owe_property_open_door'] && (time() > $v['property_endtime'])) {
                            $ban_open_status = true;
                        } else if ($now_house['owe_property_open_door_day'] > 0 && $owe_property_days > 0 && $owe_property_days > $now_house['owe_property_open_door_day']) {
                            $ban_open_status = true;
                        }
                    }
                }


                foreach ($aDoorList as $kk => &$vv) {
                    $info = array();
                    unset($vv['door_psword']);
                    unset($vv['door_device_id']);
                    $userWhere = array(
                        'user_id' => $uid,
                        'door_fid' => $vv['door_id'],
                    );
                    $aDoorFind = M('House_village_door_user')->field(true)->where($userWhere)->find();
                    if (!$ban_open_status && empty($aDoorFind)) {
                        $aDoorList[$kk]['open_status'] = 1;
                        $aDoorList[$kk]['open_status_txt'] = '可以打开';
                        $info[] = $vv;
                    } else if (!$ban_open_status && $aDoorFind['status'] == 1) {
                        if ($aDoorFind == 0 || time() > $aDoorFind['end_time']) {
                            $aDoorList[$kk]['open_status'] = 1;    //允许使用
                            $aDoorList[$kk]['open_status_txt'] = '可以打开';
                            $info[] = $vv;
                        }
                    } else {
                        $aDoorList[$kk]['open_status'] = 0;    //允许使用
                        $aDoorList[$kk]['open_status_txt'] = '不能打开';
                        $info[] = $vv;
                    }
                    $aSelect[] = $info;
                }
            }
        }

        if ($aSelect) {
            foreach ($aSelect as $k => $v) {
                if (empty($v)) {
                    continue;
                }
                foreach ($v as $kk => $vv) {
                    if ($vv['floor_id'] != "-1") {
                        $aFloor = M('House_village_floor')->field(array('floor_name', 'floor_layer'))->where(array('floor_id' => $vv['floor_id']))->find();
                        $vv['floor_name'] = strval($aFloor['floor_name']);
                        $vv['floor_layer'] = strval($aFloor['floor_layer']);
                    } else {
                        $vv['floor_name'] = '小区';
                        $vv['floor_layer'] = '大门';
                    }

                    $aDoor[] = isset($vv) ? $vv : array();
                }
            }
        }

        $village_floor_list = [
            'list' => $aDoor
        ];
        $this->assign('village_floor_list', $village_floor_list);
        $this->display();
    }


    // 访客开门二维码
    public function visitor_list_open_time()
    {
        $this->display();
    }

    // 社区添加门禁分享信息
    public function house_village_door_share_add()
    {
        if (empty($this->user_session)) {
            if (IS_POST) {
                $this->check_ajax_error_tips('请先进行登录', U('Login/index', array('referer' => urlencode(U('House/village_my', array('village_id' => $_GET['village_id']))))));
            } else {
                $this->error_tips('请先进行登录', U('Login/index', array('referer' => urlencode(U('House/village_my', array('village_id' => $_GET['village_id']))))));
            }
        }
        // 获取社区id
        $village_id = $_POST['village_id'] + 0;
        if (empty($village_id)) {
            $this->returnCode('30000001');
        }
        // 选中单元信息处理                                                    share_status
        $data = array();
        $data['share_info'] = serialize($_POST['share_info']);
        $data['village_id'] = $village_id;
        $data['share_uid'] = $this->user_session['uid'];
        $data['open_uid'] = 0;
        $data['share_time'] = time();
        // 有时间类型的取时间类型，没有默认为0 即分钟 分享时间计时方式（0: 分钟，1:小时，2:天）
        $share_time_type = !empty($_POST['share_time_type']) ? $_POST['share_time_type'] : 0;
        $data['share_time_type'] = $share_time_type;
        $share_time_length = !empty($_POST['share_time_length']) ? $_POST['share_time_length'] : 0;
        $data['share_time_length'] = $share_time_length;
        // 通过时间类型，计算截止时间
        if (!empty($_POST['share_time_type'])) {
            if (0 == $share_time_type) {
                $data['share_end_time'] = strtotime("+" . $share_time_length . " minute");
            } elseif (1 == $share_time_type) {
                $data['share_end_time'] = strtotime("+" . $share_time_length . " hour");
            } elseif (2 == $share_time_type) {
                $data['share_end_time'] = strtotime("+" . $share_time_length . " day");
            }
        } else {
            $data['share_end_time'] = strtotime("+" . $share_time_length . " minute");
        }
        $data['share_use'] = 0;
        $data['share_status'] = 0;
        $addInfo = M('House_village_door_share')->add($data);
        if (!$addInfo) {
            $this->error_tips('分享失败');
        } else {
            $return = array();
            $return['share_url'] = C('config.site_url') . '/wap.php?g=Wap&c=Library&a=visitor_list_open_time&door_share_id=' . $addInfo;
            $return['error_code'] = 0;
            exit(json_encode($return));
        }
    }


    public function express_edit()
    {
        $database_house_village_express = D('House_village_express');
        if (IS_POST) {
            $id = $_POST['id'] + 0;
            $status = $_POST['status'] + 0;
            if (!$id || !$status) {
                $this->error('传递参数有误！');
            }

            $where['id'] = $order_where['express_id'] = $id;
            $data['status'] = $status;

            $database_house_village_express_order = D('House_village_express_order');
            $database_house_village_express_order->house_village_express_order_edit($order_where, $data);
            $result = $database_house_village_express->house_village_express_edit($where, $data);
            if (!$result) {
                exit(json_encode(array('status' => 0, 'msg' => '数据处理有误！')));
            } else {
                if ($status == 1) {
                    $express_config = M('House_village_express_config')->where(array('village_id' => $_POST['village_id']))->find();
                    $order_info = M('House_village_express')->field('e.id,o.send_time,u.name,ex.name as express_name , e.express_no,v.village_name')->join('as e LEFT JOIN ' . C('DB_PREFIX') . 'house_village_express_order  o ON e.id = o.express_id left join ' . C('DB_PREFIX') . 'express ex ON e.express_type = ex.id left join ' . C('DB_PREFIX') . 'house_village v ON e.village_id = v.village_id left join  ' . C('DB_PREFIX') . 'house_village_user_bind u ON e.uid = u.uid')->where(array('e.village_id' => $_POST['village_id'], 'e.id' => $id))->find();
                    if ($this->config['village_sms'] && $order_info['send_time'] > 0) {
                        $sms_data = array('mer_id' => $express_config['village_id'], 'store_id' => 0, 'type' => 'village_express');
                        $sms_data['uid'] = 0;
                        $sms_data['mobile'] = $express_config['notice_phone'];
                        $sms_data['sendto'] = 'village';
                        $sms_data['content'] = '【提醒】您好，' . $order_info['village_name'] . $order_info['name'] . '业主，已确认收货，收货时间:' . date('Y-m-d H:i', $order_info['send_time']) . '，' . $order_info['express_name'] . '，单号：' . $order_info['express_no'] . '。[' . $this->config['site_name'] . ']';
                        Sms::sendSms($sms_data);
                    }
                }
                exit(json_encode(array('status' => $result['status'], 'msg' => $result['msg'])));
            }
        }
    }


    public function chk_visitor_info()
    {
        $id = $_POST['id'] + 0;
        $status = $_POST['status'] + 0;
        if (!$id) {
            $this->error('传递参数有误！');
        }
        $database_house_village_visitor = D('House_village_visitor');
        $where['id'] = $id;
        $data['status'] = $status;
        $result = $database_house_village_visitor->house_village_visitor_edit($where, $data);
        if (!$result) {
            exit(json_encode(array('status' => 0, 'msg' => '数据处理有误！')));
        } else {
            exit(json_encode(array('status' => $result['status'], 'msg' => $result['msg'])));
        }
    }


    private function getHasConfig($village_id, $field)
    {
        $database_house_village = D('House_village');
        $house_village_info = $database_house_village->get_one($village_id, $field);
        $config_info = $house_village_info[$field];
        return $config_info;
    }

    private function get_village($village_id)
    {
        $now_village = D('House_village')->get_one($village_id);
        if (empty($now_village)) {
            $this->error_tips('当前访问的小区不存在或未开放');
        }
        $this->assign('now_village', $now_village);
        return $now_village;
    }


    public function express_send_list()
    {
        if (!$this->user_session) {
            $this->error_tips('请先进行登录', U('Login/index'));
        }

        $village_id = $_GET['village_id'] + 0;

        $this->get_village($village_id);
        $database_house_village_express_send = D('House_village_express_send');

        $express_tmp = M('Express')->where(array('status' => 1))->select();

        foreach ($express_tmp as $key => $value) {
            $express_list[$value['code']] = $value['name'];
        }
        $this->assign('express_list', $express_list);
        // dump($express_bak);

        $has_express_send = $this->getHasConfig($village_id, 'has_express_send');
        if ($has_express_send) {
            $where['village_id'] = $village_id;
            $where['uid'] = $this->user_session['uid'];

            $send_list = M('House_village_express_send')->where($where)->select();
            // echo M('House_village_express_send')->getlastsql();
            $this->assign('send_list', $send_list);

            $this->display();
        } else {
            $this->error_tips('小区未开通相关服务！');
        }

    }

    public function express_send_add()
    {
        if (IS_POST) {
            $userInfo = D('User')->where(array('uid' => $this->user_session['uid']))->field('now_money')->find();
            if ($userInfo['now_money'] < $_POST['send_price']) {
                exit(json_encode(array('error' => 3, 'msg' => '您的余额不足请前去充值')));
            }
            $data = $_POST;
            $data['uid'] = $this->user_session['uid'];
            $data['add_time'] = time();
            $res = D("House_village_express_send")->data($data)->add();
            if ($res) {

                D('User')->where(array('uid' => $this->user_session['uid']))->setDec('now_money', $_POST['send_price']);
                D('User_money_list')->add_row($this->user_session['uid'], 2, $_POST['send_price'], "支付待寄快递服务费 " . $_POST['send_price'] . " 元", true, 5, $res);

                $order_param['village_id'] = $_POST['village_id'];
                $order_param['desc'] ='快递代发费用';
                $order_param['balance_pay'] = $_POST['send_price'];
                $order_param['payment_money'] = 0;
                $order_param['is_own'] = 4;
                $order_param['order_type'] = 'express';
                $order_param['order_id'] = 0;
                fdump($order_param,'ss');
                $return  = D('SystemBill')->bill_method(4,$order_param);


                $_SESSION['express'] = '';
                exit(json_encode(array('error' => 1, 'msg' => '添加成功！')));
            } else {
                exit(json_encode(array('error' => 2, 'msg' => '添加失败，请重试！')));
            }
        } else {

            $goods_type = array('1' => '文件', '2' => '数码产品', '3' => '生活用品', '4' => '服饰', '5' => '食品', '6' => '其他');
            $this->assign('goods_type', $goods_type);

            $this->assign('send_adress', $_SESSION['express']['express_adress_send']);
            $this->assign('collect_adress', $_SESSION['express']['express_adress_collect']);
            $this->assign('send_info', $_SESSION['express']['express_send_info']);
            $village_id = $_GET['village_id'] + 0;
            $express_send_price = $this->getHasConfig($village_id, 'express_send_price');
            $this->assign('express_send_price', $express_send_price);
            $express_list = M('Express')->where(array('status' => 1))->select();
            $this->assign('express_list', $express_list);
            $this->display();
        }
    }

    public function express_send_adress()
    {
        if ($_GET['type'] == 1) {
            $this->assign('adress_info', $_SESSION['express']['express_adress_send']);
        } else {
            $this->assign('adress_info', $_SESSION['express']['express_adress_collect']);
        }
        $this->display();
    }

    public function send_adress_ajax()
    {
        if ($_POST['type'] == 1) {
            $_SESSION['express']['express_adress_send'] = $_POST;
        } elseif ($_POST['type'] == 2) {
            $_SESSION['express']['express_adress_collect'] = $_POST;
        } else {
            $_SESSION['express']['express_send_info'] = $_POST;
        }
        exit(json_encode(array('error' => 1, 'msg' => '保存成功')));
    }
}

?>