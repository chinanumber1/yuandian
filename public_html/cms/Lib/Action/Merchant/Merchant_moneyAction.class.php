<?php

    class Merchant_moneyAction extends BaseAction
    {
        public function index()
        {
            $mer_id     = intval($this->merchant_session['mer_id']);
            $store_list = D('Merchant_store')->where(array('mer_id' => $mer_id))->select();
            $period     = false;

            if (!empty($_GET['day'])) {
                $this->assign('day', $_GET['day']);
            }
            if (isset($_GET['begin_time']) && isset($_GET['end_time']) && !empty($_GET['begin_time']) && !empty($_GET['end_time'])) {
                if ($_GET['begin_time'] > $_GET['end_time']) {
                    $this->error("结束时间应大于开始时间");
                }
                $period = array(strtotime($_GET['begin_time'] . " 00:00:00"), strtotime($_GET['end_time'] . " 23:59:59"));
                if ($_GET['store_id']) {
                    $time_condition = " (l.use_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
                } else {
                    $time_condition = " (use_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
                }
                $condition_merchant_request['_string'] = $time_condition;
                $this->assign('begin_time', $_GET['begin_time']);
                $this->assign('end_time', $_GET['end_time']);
                $period = true;
            }
            if (isset($_GET['type']) && !empty($_GET['type'])) {
                $type = $_GET['type'];
                if ($type == 'activity') {
                    $condition_merchant_request['type'] = array('in', 'coupon,yydb');
                } elseif ($type == 'store') {
                    $condition_merchant_request['type'] = array('in', 'store,cash');
                } else {
                    $condition_merchant_request['type'] = $type;
                }
            } else {
                $type                               = 'group';
                $condition_merchant_request['type'] = $type;
            }

            if ($type == 'group') {
                foreach ($store_list as $key => $value) {
                    if (empty($value['have_group'])) {
                        unset($store_list[$key]);
                    }
                }
            } else if ($type == 'meal') {
                foreach ($store_list as $key => $value) {
                    if (empty($value['have_meal'])) {
                        unset($store_list[$key]);
                    }
                }
            } else if ($type == 'shop') {
                foreach ($store_list as $key => $value) {
                    if (empty($value['have_shop'])) {
                        unset($store_list[$key]);
                    }
                }
            }
            $this->assign('store_list', $store_list);

            if ($_GET['store_id'] != 0 && $type != 'wxapp' && $type != 'activity') {
                $store_id = $_GET['store_id'];
                foreach ($condition_merchant_request as $k => $v) {
                    if ($k != '_string') {
                        $condition_merchant_request['l.' . $k] = $v;
                        unset($condition_merchant_request[$k]);
                    }
                }
                $this->assign('store_id', $_GET['store_id']);
                $condition_merchant_request['o.store_id'] = $_GET['store_id'];
            }

            $today_zero_time = mktime(0, 0, 0, date('m', $_SERVER['REQUEST_TIME']), date('d', $_SERVER['REQUEST_TIME']), date('Y', $_SERVER['REQUEST_TIME']));

            if (empty($_GET['day'])) {
                $_GET['day'] = 2;
            }
            if ($_GET['day'] < 1) {
                $this->error('日期非法！');
            }

            if ($_GET['day'] == 1 && !$period) {
                if ($store_id) {
                    $condition_merchant_request['l.use_time'] = array(array('egt', $today_zero_time), array('elt', time()));
                } else {
                    $condition_merchant_request['use_time'] = array(array('egt', $today_zero_time), array('elt', time()));
                }
                if ($_GET['store_id']) {
                    if(in_array($type,array('group','shop','meal'))){
                        if($type=='meal'){
                            $request_list = M('Merchant_money_list')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join('as l left join ' . C('DB_PREFIX') .'foodshop_order o ON o.real_orderid = l.order_id ')->where($condition_merchant_request)->select();

                        }else{

                            $request_list = M('Merchant_money_list')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join('as l left join ' . C('DB_PREFIX') . $type . '_order o ON o.real_orderid = l.order_id ')->where($condition_merchant_request)->select();
                        }
                    }else{
                        $request_list = M('Merchant_money_list')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join('as l left join ' . C('DB_PREFIX') . $type . '_order o ON o.order_id = l.order_id ')->where($condition_merchant_request)->select();
                    }

                } else {
                    $condition_merchant_request['mer_id'] = $this->merchant_session['mer_id'];
                    $request_list                         = M('Merchant_money_list')->field(true)->where($condition_merchant_request)->select();
                }
            } else {
                if (!$period) {
                    if ($_GET['day'] == 2) {
                        //本月
                        $today_zero_time = mktime(0, 0, 0, date('m'), 1, date('Y'));
                        if ($store_id) {
                            $condition_merchant_request['l.use_time'] = array(array('egt', $today_zero_time), array('elt', $_SERVER['REQUEST_TIME']));
                        } else {
                            $condition_merchant_request['use_time'] = array(array('egt', $today_zero_time), array('elt', $_SERVER['REQUEST_TIME']));
                        }
                    } else {
                        if ($store_id) {
                            $condition_merchant_request['l.use_time'] = array(array('egt', $today_zero_time - (($_GET['day'] - 1) * 86400)), array('elt', $today_zero_time));
                        } else {
                            $condition_merchant_request['use_time'] = array(array('egt', $today_zero_time - (($_GET['day']) * 86400)), array('elt', time()));
                        }
                    }
                }

                if ($_GET['store_id']) {
                    if(in_array($type,array('group','shop','meal'))){
                        if($type=='meal'){
                            $request_list = M('Merchant_money_list')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join('as l left join ' . C('DB_PREFIX') . 'foodshop_order o ON o.real_orderid = l.order_id ')->where($condition_merchant_request)->select();

                        }else{

                            $request_list = M('Merchant_money_list')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join('as l left join ' . C('DB_PREFIX') . $type . '_order o ON o.real_orderid = l.order_id ')->where($condition_merchant_request)->select();
                        }
                    }else{
                        $request_list = M('Merchant_money_list')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join('as l left join ' . C('DB_PREFIX') . $type . '_order o ON o.order_id = l.order_id ')->where($condition_merchant_request)->select();
                    }

                } else {
                    $condition_merchant_request['mer_id'] = $this->merchant_session['mer_id'];
                    $request_list  = M('Merchant_money_list')->field(true)->where($condition_merchant_request)->select();
                }
            }

            $tmp_array = array();
            if (($_GET['day'] == 1 && !$period) || ($period && ($_GET['end_time'] == $_GET['begin_time']))) {
                foreach ($request_list as $value) {
                    if ($value['type'] == 'cash') {
                        $value['type'] = 'store';
                    }
                    $tmp_time = date('G', $value['use_time']);
                    if (empty($tmp_array[$tmp_time][$value['type']]['count'])) {
                        $tmp_array[$tmp_time][$value['type']]['count'] = 1;
                    } else {
                        $tmp_array[$tmp_time][$value['type']]['count']++;
                    }
                    if ($value['income'] == 1) {
                        $tmp_array[$tmp_time][$value['type']]['income'] += $value['money'];
                    } else {
                        $tmp_array[$tmp_time][$value['type']]['expend'] += $value['money'];
                    }
                }
            } else {
                foreach ($request_list as $value) {
                    if ($value['type'] == 'cash') {
                        $value['type'] = 'store';
                    }
                    if ($_GET['day'] == 2 && !$period) {
                        $tmp_time = date('j', $value['use_time']);
                    } else {
                        $tmp_time = date('ymd', $value['use_time']);
                    }
                    if (empty($tmp_array[$tmp_time][$value['type']]['count'])) {
                        $tmp_array[$tmp_time][$value['type']]['count'] = 1;
                    } else {
                        $tmp_array[$tmp_time][$value['type']]['count']++;
                    }
                    if ($value['income'] == 1) {
                        $tmp_array[$tmp_time][$value['type']]['income'] += $value['money'];
                    } else {
                        $tmp_array[$tmp_time][$value['type']]['expend'] += $value['money'];
                    }
                }
            }

            ksort($tmp_array);
            $alias_name = $this->get_alias_name();
            if (($_GET['day'] == 1 && !$period) || ($period && ($_GET['end_time'] == $_GET['begin_time']))) {
                $day = date('G', time());
                for ($i = 0; $i <= date('H', time()); $i++) {
                    $pigcms_list['xAxis_arr'][] = '"' . $i . '时"';
                    $time_arr[]                 = $i;
                }
            } else {
                if ($_GET['day'] == 2) {
                    $day = date('d', time());
                    for ($i = 1; $i <= $day; $i++) {
                        $pigcms_list['xAxis_arr'][] = '"' . $i . '日"';
                        $time_arr[]                 = $i;
                    }
                } else {
                    $day = $_GET['day'];
                    for ($i = $day - 1; $i >= 0; $i--) {
                        $pigcms_list['xAxis_arr'][] = '"' . date('j', $today_zero_time - $i * 86400) . '日"';
                        $time_arr[]                 = date('ymd', $today_zero_time - $i * 86400);
                    }
                }
            }

            if ($period) {
                unset($pigcms_list['xAxis_arr']);
                unset($time_arr);
                $start_day = strtotime($_GET['end_time']);

                $day = (strtotime($_GET['end_time']) - strtotime($_GET['begin_time'])) / 86400;
                if ($day == 0) {
                    for ($i = 0; $i < 24; $i++) {
                        $pigcms_list['xAxis_arr'][] = '"' . $i . '时"';
                        $time_arr[]                 = $i;
                    }
                } else {
                    for ($i = $day; $i >= 0; $i--) {
                        $pigcms_list['xAxis_arr'][] = '"' . date('d', $start_day - $i * 86400) . '日"';
                        $time_arr[]                 = date('ymd', $start_day - $i * 86400);
                    }
                }
            }
            $no_data_time = array();
            //根据时间组装数据
            foreach ($time_arr as $v) {
                if ($tmp_array[$v]) {
                    foreach ($alias_name as $name) {
                        $pigcms_list[$name . '_income'][] = '"' . floatval($tmp_array[$v][$name]['income']) . '"';
                        $pigcms_list[$name . '_income_all'] += floatval($tmp_array[$v][$name]['income']);
                        $pigcms_list[$name . '_order_count'][] = '"' . intval($tmp_array[$v][$name]['count']) . '"';
                        $pigcms_list[$name . '_order_count_all'] += intval($tmp_array[$v][$name]['count']);
                    }
                } else {
                    if (!in_array($v, $no_data_time)) {
                        foreach ($alias_name as $name) {
                            $pigcms_list[$name . '_income'][]      = '"0"';
                            $pigcms_list[$name . '_order_count'][] = '"0"';
                        }
                    }
                }
            }

            //基础统计
            $pigcms_list['xAxis_txt'] = implode(',', $pigcms_list['xAxis_arr']);

            foreach ($alias_name as $name) {
                $pigcms_list[$name]['income_txt']      = implode(',', $pigcms_list[$name . '_income']);
                $pigcms_list[$name]['order_count_txt'] = implode(',', $pigcms_list[$name . '_order_count']);
            }
            if (!$period && !$_GET['day'] != '') {
                $this->assign('day', $_GET['day']);
            }
            $mer_money = M('Merchant')->field('money')->where(array('mer_id' => $mer_id))->find();
            $this->assign('all_money', $mer_money['money']);
            $this->assign('pigcms_list', $pigcms_list);
            $this->assign('alias_name', $alias_name);
            $this->assign('mer_id', $mer_id);
            $this->assign('type', $type);
            krsort($tmp_array);
            $this->assign('request_list', $tmp_array);
            $this->display();
        }

        protected function get_alias_name()
        {
            return array('group', 'shop', 'meal', 'appoint', 'waimai', 'store', 'weidian', 'wxapp');
        }

        protected function get_alias_c_name()
        {
            $c_name = array(
                'all'          => '选择分类',
                'group'        => $this->config['group_alias_name'],
                'shop'         => $this->config['shop_alias_name'],
                'shop_offline' => $this->config['shop_alias_name'] . '线下零售',
                'meal'         => $this->config['meal_alias_name'],
                'appoint'      => $this->config['appoint_alias_name'],
                'waimai'       => '外卖',
                'store'        => '优惠买单',
                'cash'         => '到店支付',
                'weidian'      => '微店',
                'wxapp'        => '营销',
                'withdraw'     => '提现',
                'coupon'       => '优惠券',
                'withdraw'     => '提现',
                'activity'     => '平台活动',
                'spread'       => '商家推广',
                'sub_card'=>'免单套餐',
                'market'=>'批发',
            );
            if (!$this->config['store_open_waimai']) unset($c_name['waimai']);
            if (!$this->config['wxapp_url']) unset($c_name['wxapp']);
            if (!$this->config['appoint_page_row']) unset($c_name['appoint']);
            if (!$this->config['is_open_weidian']) unset($c_name['weidian']);
            if (!$this->config['pay_in_store']) unset($c_name['store']);
            if (!$this->config['pay_in_store'] && !$this->config['is_cashier']) unset($c_name['cash'], $c_name['shop_offline']);
            if (!$this->config['open_sub_card']) unset($c_name['sub_card']);

            return $c_name;
        }


        public function withdraw()
        {
            if ($this->config['company_pay_open'] == '0') {
                $this->error('平台没有开启提现功能！');
            }
            $mer_id       = intval($this->merchant_session['mer_id']);
            $now_merchant = M('Merchant')->where(array('mer_id' => $mer_id))->find();
            $this->assign('now_merchant', $now_merchant);
            if (M('Merchant_withdraw')->where(array('mer_id' => $mer_id, 'status' => array('in','0,4')))->find()) {
                $this->error('您有一笔提现在审核，请审核通过了再申请！');
            }


            if(isset($_POST['money']) && !$_POST['money']){
                $this->error('金额输入有误');
            }
            if ($_POST['money']) {
                if ($_POST['money'] > $now_merchant['money']) {
                    $this->error('提现金额超过了您的余额');
                }
                $money = floatval(($_POST['money'])) * 100;
                if ($_POST['money'] < $this->config['min_withdraw_money']) {
                    $this->error('不能低于最小商家提现金额 ' . $this->config['min_withdraw_money'] . ' 元!');
                }
                if ($_POST['money'] < $this->config['company_least_money']) {
                    $this->error('不能低于对账最小提款金额 ' . $this->config['company_least_money'] . ' 元!');
                }
                if (empty($_POST['name'])) {
                    $this->error('真实姓名不能为空');
                }

                if ($_POST['withdraw_type'] != 2) {
                    $money = $_POST['money'];
                    if (empty($_POST['name'])) {
                        $this->error('真实姓名不能为空');
                    }

                    if (!is_numeric($_POST['withdraw_type'])) {
                        $this->error('提现方式没有选择');
                    }
                    $data_companypay['type']     = 'mer';
                    $data_companypay['pay_type'] = $_POST['withdraw_type'];//0 银行卡，1 支付宝 2微信
                    $data_companypay['pay_id']   = $now_merchant['mer_id'];
                    $remark                      = '';
                    if ($_POST['withdraw_type'] == 0) {
                        $bank_id = $_POST['bank_id'];
                        $bank = M('User_withdraw_account_list')->where(array('id'=>$bank_id))->find();

                        $data_companypay['account'] = $bank['account'];
                        if (empty($bank['account']) || empty($bank['account_name']) || empty($bank['remark'])) {
                            $this->error('银行账号不全');
                        }
                        $remark = '开户名：' . $bank['account_name'] . ',开户行：' . $bank['remark'];
                    } else if ($_POST['withdraw_type'] == 1) {
                        if (empty($_POST['alipay_account'])) {
                            $this->error('支付宝账号不全');
                        }
                        $data_companypay['account'] = $_POST['alipay_account'];
                    }



                    $data_companypay['name']      = $now_merchant['name'];
                    $data_companypay['truename']  = $_POST['name'];
                    $data_companypay['remark']    = $remark;
                    $data_companypay['phone']     = $now_merchant['phone'];
                    if($this->config['merchant_withdraw_fee_type']==0){
                        $data_companypay['money']     = bcmul($money * ((100 - $this->config['company_pay_mer_percent']) / 100), 100);
                    }else if($this->config['merchant_withdraw_fee_type']==1){
                        $data_companypay['money']     = ($money-$this->config['company_pay_mer_money'])*100;
                    }

                    if($data_companypay['money']<=0){
                        $this->error('您的提现金额不足以抵扣提现手续费，不能提现');
                    }
                    $data_companypay['old_money'] = $money * 100;
                    $data_companypay['desc']      = "商户提现对账订单|商户ID " . $now_merchant['mer_id'] . " |转账 " . $money . " 元";
                    if ($this->config['company_pay_mer_percent'] > 0 && $this->config['merchant_withdraw_fee_type']==0) {
                        $system_take = ($data_companypay['old_money'] -  $data_companypay['money'])/100;
                        $data_companypay['desc'] .= '|手续费 ' .(($data_companypay['old_money'] -  $data_companypay['money'])/100) . ' 比例 ' . $this->config['company_pay_mer_percent'] . '%';
                    }else if($this->config['merchant_withdraw_fee_type']==1&&$this->config['company_pay_mer_money']>0){
                        $data_companypay['desc'] .= '|手续费 '.$this->config['company_pay_mer_money'].'元';
                         $system_take = $this->config['company_pay_mer_money'];

                    }
                    $data_companypay['status']   = 0;
                    $data_companypay['add_time'] = time();

                    //商家提现记录
                    $date_mer['mer_id']=$mer_id;
                    $date_mer['name']=$_POST['name'];
                    $date_mer['money']=   $data_companypay['money'] ;
                    $date_mer['old_money'] = $data_companypay['old_money'];
                    $date_mer['remark']= $data_companypay['desc'].'|'.$_POST['info'];
                    $date_mer['withdraw_time'] = time();
                    $date_mer['status'] = 4;
                    $res =M('Merchant_withdraw')->add($date_mer);
                    if(!$res){
                        $this->error("申请失败！");die;
                    }
                    $data_companypay['withdraw_id'] = $res;

                    $result = D('Merchant_money_list')->use_money($mer_id, $money, 'withdraw', $data_companypay['desc'].'|'.$_POST['info'],  $data_companypay['withdraw_id'], $this->config['company_pay_mer_percent'],$system_take);
                    $withdraw_id = M('Withdraw_list')->add($data_companypay);
                    D('Merchant_money_list')->withdraw_notice($res);

                    $this->success("申请成功，请等待审核！", U('Merchant_money/index'));
                    die;
                } else {
                    $res = D('Merchant_money_list')->withdraw($mer_id, $_POST['name'], $money, $_POST['info']);
                    if ($res['error_code']) {
                        $this->error($res['msg']);
                    } else {
                        D('Scroll_msg')->add_msg('mer_withdraw', $now_merchant['mer_id'], '商家' . $now_merchant['name'] . '于' . date('Y-m-d H:i', $_SERVER['REQUEST_TIME']) . '提现成功！');
                        $this->success("申请成功，请等待审核！", U('Merchant_money/index'));
                    }
                }

            } else {
                $where['from'] = 1;
                $where['type'] = 0;
                $where['pay_id'] = $mer_id;
                $bank_list = M('User_withdraw_account_list')->where($where)->order('is_default DESC,id DESC')->select();

                $this->assign('bank_list',$bank_list);
                $this->display();
            }
        }

        public function withdraw_list()
        {

            import('@.ORG.merchant_page');

            $mer_id = intval($this->merchant_session['mer_id']);
            if ($_GET['type'] == 1 || !isset($_GET['type'])) {
                $where['status'] = array('neq',4);
                $where['mer_id'] = $mer_id;
                $withdraw_to_other = M('Withdraw_list')->where(array('pay_id'=>$mer_id))->select();
                foreach ($withdraw_to_other as $value) {
                    $ids[] = $value['withdraw_id'];
                }
                if($ids){
                    $where['id'] = array('not in',$ids);
                }
                $count = M('Merchant_withdraw')->where($where)->count();
                $p = new Page($count, 20);
                $withdraw_list =M('Merchant_withdraw')->where($where)->order('withdraw_time DESC')->limit($p->firstRow,$p->listRows)->select();

            } elseif ($_GET['type'] == 2) {
                $count = M('Withdraw_list')->where(array('pay_id'=>$mer_id))->count();
                $p = new Page($count, 20);
                $withdraw_list = M('Withdraw_list')->field('id,status,pay_type,remark,desc,old_money as money,add_time as withdraw_time')->where(array('pay_id' => $mer_id))->order('withdraw_time DESC')->limit($p->firstRow,$p->listRows)->select();

            }
            $where['pay_type'] = 0;
            $where['pay_id'] = $this->merchant_session['mer_id'];;
            //$account_list = M('Withdraw_list')->field('account,remark')->where($where)->group('account')->select();
            $this->assign('withdraw_list', $withdraw_list);
            $pay_type = array(
                '0' => '银行',
                '1' => '支付宝',
            );
            $this->assign('pay_type', $pay_type);
            $this->assign('pagebar', $p->show());

            $this->display();
        }

        public function income_list()
        {
            if (!empty($_POST['order_id'])) {
                if (empty($_POST['order_type'])) {
                    $this->error("没有选分类");
                }
                if ($_POST['order_type'] == 'all') {
                    $this->error("该分类下不能填写订单id");
                } else if ($_POST['order_type'] == 'withdraw') {
                    $condition['id'] = $_POST['order_id'];
                } else {
                    $condition['order_id'] = $_POST['order_id'];
                }
            }
            $mer_id     = intval($this->merchant_session['mer_id']);
            $store_list = D('Merchant_store')->where(array('mer_id' => $mer_id))->select();
            $this->assign('store_list', $store_list);

            $merchant = M('Merchant')->field(true)->where(array('mer_id' => $mer_id))->find();
            if ($merchant['percent']) {
                $percent = $merchant['percent'];
            } elseif (C('config.platform_get_merchant_percent')) {
                $percent = C('config.platform_get_merchant_percent');
            }
            if (!empty($_POST['store_id'])) {
                $condition['store_id'] = $_POST['store_id'];
                $this->assign('store_id', $_POST['store_id']);
            }

            $this->assign('percent', $percent);
            $this->assign('order_id', $_POST['order_id']);
            $this->assign('order_type', $_POST['order_type']);

            if ($_POST['order_type'] == 'activity') {
                $condition['type'] = 'coupon or yydb';
            } elseif ($_POST['order_type'] != 'all' && !empty($_POST['order_type'])) {
                $condition['type'] = $_POST['order_type'];
            }
            if (isset($_POST['begin_time']) && isset($_POST['end_time']) && !empty($_POST['begin_time']) && !empty($_POST['end_time'])) {
                if ($_POST['begin_time'] > $_POST['end_time']) {
                    $this->error("结束时间应大于开始时间");
                }
                $period               = array(strtotime($_POST['begin_time'] . " 00:00:00"), strtotime($_POST['end_time'] . " 23:59:59"));
                $time_condition       = " (use_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
                $condition['_string'] = $time_condition;
                $this->assign('begin_time', $_POST['begin_time']);
                $this->assign('end_time', $_POST['end_time']);
            }

            if (!$_GET['page']) {
                $_SESSION['condition'] = $condition;
            }
            $res = D('Merchant_money_list')->get_income_list($mer_id, 0, $condition);
            $this->assign('mer_id', $mer_id);
            $this->assign('total', $res['total']);
            $this->assign('income_total', $res['income_total']);
            $this->assign('total_score', $res['total_score']);
            $this->assign('recharge_total', $res['recharge_total']);
            $this->assign('income_list', $res['income_list']);
            $this->assign('alias_name', $this->get_alias_c_name());
            $this->assign('pagebar', $res['pagebar']);
            $this->display();
        }

        public function withdraw_order_info(){
            $withdraw = M('Merchant_withdraw')->field('w.*,m.system_take')->join('as w LEFT JOIN '.C('DB_PREFIX').'merchant_money_list m ON m.order_id = w.id')->where(array('w.id'=>$_GET['id'],'m.type'=>'withdraw'))->find();
            $now_merchant = M('Merchant')->where(array('mer_id'=>$withdraw['mer_id']))->find();
            $this->assign('withdraw',$withdraw);
            $this->assign('now_merchant',$now_merchant);
            $this->display();
        }

        public function buy_system()
        {
            $this->error('该功能正在开发中');
        }

        //商家送出元宝记录 定制的
        public function score_log()
        {
            import('@.ORG.merchant_page');
            $where['mer_id'] = intval($this->merchant_session['mer_id']);
            if (isset($_POST['begin_time']) && isset($_POST['end_time']) && !empty($_POST['begin_time']) && !empty($_POST['end_time'])) {
                if ($_POST['begin_time'] > $_POST['end_time']) {
                    $this->error("结束时间应大于开始时间");
                }
                $period           = array(strtotime($_POST['begin_time'] . " 00:00:00"), strtotime($_POST['end_time'] . " 23:59:59"));
                $time_condition   = " (add_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
                $where['_string'] = $time_condition;
                $this->assign('begin_time', $_POST['begin_time']);
                $this->assign('end_time', $_POST['end_time']);
            }
            $count = M('Merchant_score_send_log')->where($where)->count();

            $p          = new Page($count, 20);
            $socre_list = M('Merchant_score_send_log')->where($where)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
            $pagebar    = $p->show();
            $this->assign('score_list', $socre_list);
            $this->assign('pagebar', $pagebar);
            $this->display();
        }

        /*
         *能还未拥有的权限列表
         * */
        public function buy_merchant_service()
        {
            $mer_menus = $this->merchant_session['menus'];
            if ($this->config['buy_merchant_auth'] == 0) {
                $this->error('管理员未开启该功能');
            }
            $menus = D('New_merchant_menu')->where(array('status' => 1,'show'=>1))->select();

            if (empty($mer_menus)||count($mer_menus)==count($menus)) {
                $this->error('您已经拥有所有的权限了！', U('Merchant_money/index'));
            }

			foreach($menus as &$value){
				$value['name'] = str_replace(array('团购','订餐','快店','预约'),array($this->config['group_alias_name'],$this->config['meal_alias_name'],$this->config['shop_alias_name'],$this->config['appoint_alias_name']),$value['name']);
			}
			
            $menus_group = D('Authority_group')->where(array('gid' => 1))->select();
            $list        = array();
            $list        = arrayPidProcess($menus);
            $this->assign('menus', $list);
            $this->assign('menus_group', $menus_group);
            $this->assign('mer_menus', $mer_menus);
            $this->display();
        }


        /*
         * 购买权限
         * */
        public function pay_merchant_service()
        {

            if ($this->config['buy_merchant_auth'] == 0) {
                $this->error('管理员未开启该功能');
            }
            if (empty($_GET['auth_id']) && empty($_GET['menu_group'])) {
                $this->error('非法访问！');
            }
            if ($_GET['auth_id']) {
                $auth_id  = $_GET['auth_id'];
                $now_auth = D('New_merchant_menu')->where(array('id' => $auth_id))->find();
            } else {
                $auth_id  = $_GET['menu_group'];
                $now_auth = D('Authority_group')->where(array('id' => $auth_id))->find();
            }
            $now_merchant = D('Merchant')->get_info($this->merchant_session['mer_id']);

            if ($now_auth['price'] > $now_merchant['money']) {
                if ($_GET['menu_group']) {
                    $this->error('您的商家余额不足，不能购买该权限，请充值', U('mer_recharge', array('money' => ($now_auth['price'] - $now_merchant['money']), 'auth_id' => $auth_id, 'type' => 1)));
                } else {
                    $this->error('您的商家余额不足，不能购买该权限，请充值', U('mer_recharge', array('money' => ($now_auth['price'] - $now_merchant['money']), 'auth_id' => $auth_id)));
                }
            } else {

                if ($_GET['menu_group']) {
                    $res = D('Merchant_auth')->add_auth($this->merchant_session['mer_id'], $auth_id, 1);
                } else {
                    $res = D('Merchant_auth')->add_auth($this->merchant_session['mer_id'], $auth_id);
                }
                if ($res['error_code']) {
                    $this->error($res['msg'], U('Merchant_money/buy_merchant_service'));
                } else {

                    D('Merchant_money_list')->use_money($this->merchant_session['mer_id'], $now_auth['price'], 'merauth', '购买商家权限【' . $now_auth['name'] . '】扣除商家余额', $auth_id);
                    $this->success($res['msg'], U('Merchant_money/buy_merchant_service'));
                }
            }
        }

        /*
         * 权限套餐详情
         * */

        public function service_detail(){
            $menu_group = $_GET['menu_group'];
            $menu_group_arr = M('Authority_group')->where(array('id'=>$menu_group))->find();

            $menu_group = explode(',',$menu_group_arr['menus']);
            $menus = D('New_merchant_menu')->field(true)->where(array('status' => 1, 'show' => 1))->order('`sort` DESC,`id` ASC')->select();
            $list = arrayPidProcess($menus);
            $this->assign('menus', $list);
            $this->assign('menu_group_arr', $menu_group_arr);
            $this->assign('menu_group', $menu_group);
            $this->display();
        }

        /*
         * 商家余额充值
         * */
        public function mer_recharge()
        {
            //商家信息

            $now_merchant = D('Merchant')->get_info($this->merchant_session['mer_id']);

            if (empty($_GET['money'])) {
                $condition['mer_id'] = $this->merchant_session['mer_id'];
                $condition['paid'] =1;
                $count = M('Merchant_recharge_order')->where($condition)->count();

                import("@.ORG.merchant_page");
                $p = new Page($count,15);
                $recharge_list = M('Merchant_recharge_order')->where($condition)->order('order_id DESC')->limit($p->firstRow,$p->listRows)->select();
                foreach ($recharge_list as &$item) {
                    $item['pay_type'] = D('Pay')->get_pay_name($item['pay_type'],0, $item['paid']);
                }

                $this->assign('recharge_list', $recharge_list);
                $this->assign('page', $p->show());
                $this->assign('now_merchant', $now_merchant);
                $this->display();
            } else {
                $money = floatval($_GET['money']);
                if (empty($money) || $money < 0 || !is_numeric($money)) {
                    $this->error('请输入正确的充值金额');
                }

                $data_mer_recharge_order['mer_id']    = $now_merchant['mer_id'];
                $data_mer_recharge_order['money']     = $money;
                $data_mer_recharge_order['add_time']  = $_SERVER['REQUEST_TIME'];
                $data_mer_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
                if ($_GET['auth_id'] ) {
                    if($_GET['type']){
                        $data_mer_recharge_order['label'] = 'web_merauthgroup_' . $_GET['auth_id'];

                    }else{

                        $data_mer_recharge_order['label'] = 'web_merauth_' . $_GET['auth_id'];
                    }
                }
                if ($order_id = M('Merchant_recharge_order')->data($data_mer_recharge_order)->add()) {
                    redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'merrecharge')));
                } else {
                    $this->error('订单创建失败，请重试。');
                }
            }

        }


        public function create_bank_account(){
            if(IS_POST){
                if(empty($_POST['account'])){
                    $this->error('银行卡号不能为空');
                }
                if(empty($_POST['account_name'])){
                    $this->error('持卡人姓名不能为空');
                }
                $data_companypay['from']     = 1;
                $data_companypay['type']     = 0;
                $data_companypay['pay_id']   = $this->merchant_session['mer_id'];
                $data_companypay['account_name'] = $_POST['account_name'];
                $data_companypay['account'] = $_POST['account'];
                $data_companypay['is_default'] = $_POST['is_default'];
                $data_companypay['add_time'] = $_SERVER['REQUEST_TIME'];
                $data_companypay['remark']    = $_POST['remark'];

                if($_POST['bank_id']){
                    $url  = U('bank_list');
                    $result = M('User_withdraw_account_list')->where(array('id'=>$_POST['bank_id']))->save($data_companypay);
                    if($result){
                        $aid = $_POST['bank_id'];
                    }else{
                        $aid = false;
                    }
                }else{
                   $aid = M('User_withdraw_account_list')->add($data_companypay);
                    $url  = U('withdraw');
                }

                if($aid){
                    if($data_companypay['is_default']){
                        $where['from'] = 1;
                        $where['type'] = 0;
                        $where['pay_id'] =  $this->merchant_session['mer_id'];
                        $where['id'] = array('neq',$aid);
                        M('User_withdraw_account_list')->where($where)->setField('is_default',0);

                    }
                    $this->success('保存成功',$url);
                }else{
                    $this->error('保存失败');
                }
            }else{
                if($_GET['bank_id']){
                    $where['id'] =  $_GET['bank_id'];
                    $bank = M('User_withdraw_account_list')->where($where)->find();
                    $this->assign('bank',$bank);
                }
                $this->display();
            }
        }

        public function bank_list(){
            $where['from'] = 1;
            $where['type'] = 0;
            $where['pay_id'] =  $this->merchant_session['mer_id'];
            $bank_list = M('User_withdraw_account_list')->where($where)->order('is_default DESC,id DESC')->select();
            $this->assign('bank_list',$bank_list);
            M('User_withdraw_account_list')->where($where)->select();
            $this->display();

        }

        public function  get_bank_name()
        {
            $card_number = $_POST['card_number'];
            require_once APP_PATH . 'Lib/ORG/BankList.class.php';
            if ($res = $this->bankInfo($card_number, $bankList)) {
                $this->success($res);
            } else {
                $this->error('没有查询到相关银行，请手动填写');
            }
        }

         public function delete_bank_account(){
             $where['id'] = $_GET['bank_id'];
             if($this->config['open_allinyun']==1){
                 $bank = M('User_withdraw_account_list')->where($where)->find();
                 import('@.ORG.AccountDeposit.AccountDeposit');
                 $deposit = new AccountDeposit('Allinyun');
                 $allyun = $deposit->getDeposit();
                 $allinyun = M('Merchant_allinyun_info')->where(array('mer_id'=>$this->merchant_session['mer_id']))->find();
                 $allyun->setUser($allinyun);
                 $param['cardNo'] = $bank['account'];
                 $res = $allyun->unbindBankCard($param);
                 if($res['status']=='error'){
                     $this->error($res['message']);
                 }

             }
             M('User_withdraw_account_list')->where($where)->delete();
             $this->success(
                 '删除成功'
             );
         }

        function bankInfo($card, $bankList)
        {
            $card_8 = substr($card, 0, 8);
            if (isset($bankList[$card_8])) {
                return $bankList[$card_8];
            }
            $card_6 = substr($card, 0, 6);
            if (isset($bankList[$card_6])) {
                return $bankList[$card_6];

            }
            $card_5 = substr($card, 0, 5);
            if (isset($bankList[$card_5])) {
                return $bankList[$card_5];

            }
            $card_4 = substr($card, 0, 4);
            if (isset($bankList[$card_4])) {
                return $bankList[$card_4];

            }

            return null;
        }

        public function discount_detail()
        {
            $where = 'l.mer_id=' . $this->merchant_session['mer_id'];
            $condition = 'mer_id=' . $this->merchant_session['mer_id'];
            if (isset($_POST['begin_time']) && isset($_POST['end_time']) && !empty($_POST['begin_time']) && !empty($_POST['end_time'])) {
                if ($_POST['begin_time'] > $_POST['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }
                $period = array(strtotime($_POST['begin_time'] . " 00:00:00"), strtotime($_POST['end_time'] . " 23:59:59"));
                $where .= " AND (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
                $condition .= " AND (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
                
                $this->assign('begin_time', $_POST['begin_time']);
                $this->assign('end_time', $_POST['end_time']);
            }
            if (isset($_POST['order_id']) && !empty($_POST['order_id'])) {
                $where .= " AND real_orderid='" . htmlspecialchars(trim($_POST['order_id'])) . "'";
                $condition .= " AND real_orderid='" . htmlspecialchars(trim($_POST['order_id'])) . "'";
            }
            $this->assign('order_id', $_POST['order_id']);
            $count = D('Merchant_discount_log')->where($condition)->count();
            import('@.ORG.merchant_page');
            $p = new Page($count, 15);
            $sql = "SELECT `s`.`name`, `l`.* FROM " . C('DB_PREFIX') . "merchant_store as s INNER JOIN " . C('DB_PREFIX'). "merchant_discount_log as l ON `s`.`store_id`=`l`.`store_id` ";
            $sql .= "WHERE $where ORDER BY `l`.`id` DESC LIMIT {$p->firstRow},{$p->listRows}";
            $order_list = D()->query($sql);
            
            $this->assign('order_list', $order_list);
            $this->assign('pagebar', $p->show());
            $merchant = D('Merchant')->field('discount_price, discount_num')->where(array('mer_id' => $this->merchant_session['mer_id']))->find();
            $this->assign('now_merchant', $merchant);
            $this->display();
        }
        
        public function exportDiscount()
        {
            set_time_limit(0);
            
            $where = 'l.mer_id=' . $this->merchant_session['mer_id'];
            $condition = 'mer_id=' . $this->merchant_session['mer_id'];
            if (isset($_REQUEST['begin_time']) && isset($_REQUEST['end_time']) && !empty($_REQUEST['begin_time']) && !empty($_REQUEST['end_time'])) {
                if ($_REQUEST['begin_time'] > $_REQUEST['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }
                $period = array(strtotime($_REQUEST['begin_time'] . " 00:00:00"), strtotime($_REQUEST['end_time'] . " 23:59:59"));
                $where .= " AND (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
                $condition .= " AND (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
                
            }
            if (isset($_REQUEST['order_id']) && !empty($_REQUEST['order_id'])) {
                $where .= " AND real_orderid='" . htmlspecialchars(trim($_REQUEST['order_id'])) . "'";
                $condition .= " AND real_orderid='" . htmlspecialchars(trim($_REQUEST['order_id'])) . "'";
            }
            
            require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
            $title = '商家统一折扣订单列表';
            $objExcel = new PHPExcel();
            $objProps = $objExcel->getProperties();
            // 设置文档基本属性
            $objProps->setCreator($title);
            $objProps->setTitle($title);
            $objProps->setSubject($title);
            $objProps->setDescription($title);
            
            $count = D('Merchant_discount_log')->where($condition)->count();
            
            $length = ceil($count / 1000);
            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);
                
                $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千配送订单');
                $objActSheet = $objExcel->getActiveSheet();
                
                $objActSheet->setCellValue('A1', '店铺名称');
                $objActSheet->setCellValue('B1', '订单号');
                $objActSheet->setCellValue('C1', '订单总额');
                $objActSheet->setCellValue('D1', '商家统一折扣比例');
                $objActSheet->setCellValue('E1', '商家统一折扣优惠总金额');
                $objActSheet->setCellValue('F1', '支付时间');
                
                $sql = "SELECT `s`.`name`, `l`.* FROM " . C('DB_PREFIX') . "merchant_store as s INNER JOIN " . C('DB_PREFIX'). "merchant_discount_log as l ON `s`.`store_id`=`l`.`store_id` ";
                $sql .= "WHERE $where ORDER BY `l`.`id` DESC LIMIT " . $i * 1000 . ', 1000';
                $order_list = D()->query($sql);
                if (!empty($order_list)) {
                    $index = 2;
                    foreach ($order_list as $value) {
                        $value['confirm_time'] = $value['create_time'] && $value['order_time'] ? intval(($value['create_time'] - $value['order_time']) / 60) . '分钟' : '-';
                        $value['grab_time'] = $value['start_time'] ? intval(($value['start_time'] - $value['create_time']) / 60) . '分钟' : '-';
                        $value['deliver_use_time'] = $value['end_time'] ? intval(($value['end_time'] - $value['start_time']) / 60) . '分钟' : '-';
                        
                        $objActSheet->setCellValueExplicit('A' . $index, $value['name']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['real_orderid'] . ' ');
                        $objActSheet->setCellValueExplicit('C' . $index, $value['price']);
                        $objActSheet->setCellValueExplicit('D' . $index, floatval($value['discount']/10) . '折');
                        $objActSheet->setCellValueExplicit('E' . $index, floatval($value['discount_money']) . ' ');
                        $objActSheet->setCellValueExplicit('F' . $index, date('Y-m-d H:i:s', $value['dateline']));
                        
                        $index++;
                    }
                }
                sleep(2);
            }
            //输出
            $objWriter = new PHPExcel_Writer_Excel5($objExcel);
            ob_end_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
            header("Content-Transfer-Encoding:binary");
            $objWriter->save('php://output');
            exit();
        }
    }