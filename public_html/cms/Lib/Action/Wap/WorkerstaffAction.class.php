<?php
class WorkerstaffAction extends BaseAction{
    public function _initialize(){
        parent::_initialize();

        $this->worker_session = session('worker_session');
        $this->store_session = session('store_session');
        $this->worker_session = !empty($this->worker_session) ? unserialize($this->worker_session): false;
        $this->store_session = !empty($this->store_session) ? unserialize($this->store_session): false;
        if (ACTION_NAME != 'login' && ACTION_NAME != 'logout') {
            if (empty($this->worker_session) && $this->is_wexin_browser && !empty($_SESSION['openid'])) {
                if ($worker_info = D('Merchant_workers')->where(array('openid' => trim($_SESSION['openid'])))->find()) {
                    session('worker_session', serialize($worker_info));
                    $this->worker_session = $worker_info;

                    $store = D('Merchant_store')->where(array('store_id' => $worker_info['merchant_store_id']))->find();
                    $store_image_class = new store_image();
                    $images = $store_image_class->get_allImage_by_path($store['pic_info']);
                    $store['image'] = $images ? array_shift($images) : '';
                    session('store_session', $store);
                    $this->store_session = $store;
                }
            }
            $this->worker_session = unserialize($_SESSION['worker_session']);
            if (empty($this->worker_session)) {
                redirect(U('login', array('referer' => urlencode('http://' . $_SERVER['HTTP_HOST'] . (!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'])))));
            } else {
                if ($worker_info = D('Merchant_workers')->where(array('merchant_worker_id' => $this->worker_session['merchant_worker_id']))->find()) {
                    if (empty($worker_info['status'])) {
                        session('worker_session', null);
                        $this->error_tips("您的账号已禁止");
                        exit;
                    }
                }
            }
        }
    }

    public function login(){
        if (IS_POST) {
            $worker_condition['username'] = trim($_POST['username']);
            $database_merchant_workers = D('Merchant_workers');
            $now_worker = $database_merchant_workers->appoint_worker_info($worker_condition);
            if (empty($now_worker)) {
                exit(json_encode(array('error' => 2, 'msg' => '帐号不存在！', 'dom_id' => 'account')));
            }
            if (empty($now_worker['status'])) {
                exit(json_encode(array('error' => 2, 'msg' => '此账号已冻结！', 'dom_id' => 'account')));
            }
            $pwd = md5(trim($_POST['pwd']));
            if ($pwd != $now_worker['password']) {
                exit(json_encode(array('error' => 3, 'msg' => '密码错误！', 'dom_id' => 'pwd')));
            }
            $data_worker['last_time'] = $_SERVER['REQUEST_TIME'];
            if ($database_merchant_workers->where(array('uid'=>$now_worker['merchant_worker_id']))->data($data_worker)->save()) {
                session('worker_session', serialize($now_worker));

                $store = D('Merchant_store')->where(array('store_id' => $now_worker['merchant_store_id']))->find();
                $store_image_class = new store_image();
                $images = $store_image_class->get_allImage_by_path($store['pic_info']);
                $store['image'] = $images ? array_shift($images) : '';
                session('store_session' , serialize($store));

                $is_bind = $now_worker['openid'] ? 1 : 0;
                exit(json_encode(array('error' => 0, 'msg' => '登录成功,现在跳转~', 'dom_id' => 'account', 'is_bind' => $is_bind)));
            } else {
                exit(json_encode(array('error' => 6, 'msg' => '登录信息保存失败,请重试！', 'dom_id' => 'account')));
            }
        } else {
            if ($this->is_wexin_browser && !empty($_SESSION['openid'])) {
                $this->assign('openid', $_SESSION['openid']);
            }
            $referer = isset($_GET['referer']) ? htmlspecialchars_decode(urldecode($_GET['referer']),ENT_QUOTES) : '';
            $this->assign('refererUrl', $referer);
            $this->display();
        }
    }

    public function index(){
        if (!$this->worker_session['merchant_store_id']) {
            $this->error_tips("登陆信息有误！");
        }

        $store = D('Merchant_store')->where(array('store_id' => $this->worker_session['merchant_store_id']))->find();
        $store_image_class = new store_image();
        $images = $store_image_class->get_allImage_by_path($store['pic_info']);
        $store['image'] = $images ? array_shift($images) : '';
        $this->assign('store', $store);

        $office_time = unserialize($this->worker_session['office_time']);
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
        if(isset($office_time['open']) && isset($office_time['close'])){
            $office_time = array($office_time);
        }

        $beforeTime = $this->worker_session['before_time'] > 0 ? ($this->worker_session['before_time']) * 3600 : 0;
        $gap = $this->worker_session['time_gap'] * 60 > 0 ? $this->worker_session['time_gap'] * 60 : 1800;
        $tempTime = array();
        foreach ($office_time as $key => $value) {
            $startTime = strtotime(date('Y-m-d') . ' ' . $value['open']);
            $endTime = strtotime(date('Y-m-d') . ' ' . $value['close']);
            for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
                $tempKey = date('H:i', $time) . '-' . date('H:i', $time + $gap);
                $tempTime[$tempKey]['time'] = $tempKey;
                $tempTime[$tempKey]['start'] = date('H:i', $time);
                $tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
                $tempTime[$tempKey]['order'] = 'no';
                if ((date('H:i') < date('H:i', $time - $beforeTime))) {
                    $tempTime[$tempKey]['order'] = 'yes';
                }
            }
        }
       

        $startTimeAppoint = strtotime('now');
        $endTimeAppoint = strtotime('+3 day');

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
        foreach ($timeOrder as $i => $tem) {
            foreach ($tem as $key => $temval)
                if (strtotime($i . ' ' . $temval['end']) < strtotime('now') + $beforeTime && ($temval['order'] == 'yes')) {
                    $timeOrder[$i][$key]['order'] = 'no';
                } elseif (strtotime($i . ' ' . $temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i . ' ' . $temval['start']) > strtotime('now') + $beforeTime && ($temval['order'] == 'no')) {
                    $timeOrder[$i][$key]['order'] = 'yes';
                }
        }

        $appoint_num = D('Appoint_order')->get_worker_appoint_num(false, $this->worker_session['merchant_worker_id']);

        if (count($appoint_num) > 0) {
            foreach ($appoint_num as $val) {
                $key = date('Y-m-d', strtotime($val['appoint_date']));
//                if ($timeOrder[$key][$val['appoint_time']]['order'] != 'no') {
//                    //if (isset($timeOrder[$key]) && ($merchant_workers_info['appoint_people'] == $val['appointNum'])) {
//                    if (isset($timeOrder[$key]) && (1 == $val['appointNum'])) {
//                        $timeOrder[$key][$val['appoint_time']]['order'] = 'all';
//                    }
//                }
                if(isset($timeOrder[$key])) {
                    foreach ($timeOrder[$key] as $tk => &$t) {
                        if ($t['order'] != 'no' && $t['start'] == $val['appoint_time']) {
                            $t['order'] = 'all';
                        }
                    }
                }
            }
        }
        $this->assign('timeOrder',$timeOrder);

        $database_appoint_supply = D('Appoint_supply');
        $supply_where['status'] = 1;
        $supply_where['store_id'] = $this->store['store_id'];
        $supply_where['worker_id'] = 0;
        $gray_count = $database_appoint_supply->where($supply_where)->count();

        $supply_where['status'] = 2;
        $supply_where['worker_id'] = $this->worker_session['merchant_worker_id'];
        $deliver_count = $database_appoint_supply->where($supply_where)->count();

        $supply_where['status'] = 3;
        $finish_count = $database_appoint_supply->where($supply_where)->count();

        $this->assign(array('gray_count' => $gray_count, 'deliver_count' => $deliver_count, 'finish_count' => $finish_count));
        $this->display();
    }

    public function tongji(){
        $begin_time = isset($_GET['begin_time']) && $_GET['begin_time'] ? $_GET['begin_time'] : '';
        $end_time = isset($_GET['end_time']) && $_GET['end_time'] ? $_GET['end_time'] : '';
        $where = array('worker_id' => $this->worker_session['merchant_worker_id']);
        if ($begin_time && $end_time) {
            $where['start_time'] = array(array('gt', strtotime($begin_time)), array('lt', strtotime($end_time . '23:59:59')));
        }

        $database_appoint_supply = D('Appoint_supply');
        $result = $database_appoint_supply->field('sum(deliver_cash) as offline_money, sum(money-deliver_cash) as online_money, sum(freight_charge) as freight_charge')->where($where)->find();
        $where['worker_id'] = $this->worker_session['merchant_worker_id'];
        $count_list = $database_appoint_supply->field('count(*) as cnt, get_type')->where($where)->group('get_type')->select();

        $where['status'] = 3;
        $count = $database_appoint_supply->where($where)->count();
        $where['uid'] = array('gt' , 0);

        $custom_count = $database_appoint_supply->where($where)->count('DISTINCT(`uid`)');

        foreach ($count_list as $row) {
            if ($row['get_type'] == 0) {
                $result['self_count'] = $row['cnt'];
            } elseif ($row['get_type'] == 1) {
                $result['system_count'] = $row['cnt'];
            } elseif ($row['get_type'] == 2) {
                $result['change_count'] = $row['cnt'];
            }
        }
        $result['total'] = $count;
        $result['custom_count'] = $custom_count;

        $result['begin_time'] = $begin_time;
        $result['end_time'] = $end_time;
        $this->assign($result);
        $this->display();
    }

    public function freeLogin()
    {
        if(IS_POST && $this->is_wexin_browser && !empty($_SESSION['openid']) && is_array($this->worker_session)){
            if ($old_user = D('Merchant_workers')->where(array('openid' => trim($_SESSION['openid'])))->find()) {
                exit(json_encode(array('error' => 1, 'msg' => '该微信号已被绑定了' . $old_user['phone'] . '账号，不能重复绑定')));
            } else {
                if (D('Merchant_workers')->where(array('merchant_worker_id'=>$this->worker_session['merchant_worker_id']))->save(array('openid' => trim($_SESSION['openid']), 'last_time' => time()))) {
                    exit(json_encode(array('error' => 0)));
                } else {
                    exit(json_encode(array('error' => 1, 'msg' => '绑定失败，请下次登录再试')));
                }
            }
        }
        exit(json_encode(array('error' => 1, 'msg' => '绑定失败，请下次登录再试')));
    }

    public function info(){
        $database_appoint_supply = D('Appoint_supply');
        $database_appoint_comment = D('Appoint_comment');
        $where = array('worker_id' => $this->worker_session['merchant_worker_id']);
        $total_list = $database_appoint_supply->field('count(*) as cnt')->where($where)->find();
        $where['status'] = 3;
        $count = $database_appoint_supply->where($where)->count();

        $worker_comment_where['status'] = 1;
        $worker_comment_where['merchant_worker_id'] = $this->worker_session['merchant_worker_id'];
        $comment_total = $database_appoint_comment->where($worker_comment_where)->count();

        $this->assign(array('total' => isset($total_list['cnt']) ? intval($total_list['cnt']) : 0, 'finish_total' => $count,'comment_total'=>$comment_total));
        $this->display();
    }

    public function grab(){
        if (IS_POST){
            $database_merchant_workers = D('Merchant_workers');
            if ($user = $database_merchant_workers->where(array('merchant_worker_id' => $this->worker_session['merchant_worker_id']))->find()) {
                if (empty($user['status'])) {
                    $this->error("您的账号已禁止，不能抢单");
                }
            }else{
                $this->error('技师不存在！');
            }
            $supply_id = intval(I("supply_id"));
            if (!$supply_id) {
                $this->error("参数错误");
            }

            $database_appoint_supply = D('Appoint_supply');
            $appoint_where['supply_id'] = $supply_id;
            $now_supply = $database_appoint_supply->where($appoint_where)->find();
            if (empty($now_supply)) {
                $this->error("信息不存在！");
            }

            if ($now_supply['status'] != 1) {
                $this->error("已被抢单，不能再抢了");
                exit;
            }

            $columns = array('worker_id' => $this->worker_session['merchant_worker_id'], 'status' => 2);

            $result = $database_appoint_supply->where(array("supply_id" => $supply_id, 'status' => 1))->save($columns);
            if (false === $result) {
                $this->error("抢单失败");
                exit;
            }

            $this->success("抢单成功");
        }

        if (IS_AJAX) {
            $condition_table = array(C('DB_PREFIX') . 'appoint_supply' => '`as`' , C('DB_PREFIX') .'appoint_order'=>'`o`', C('DB_PREFIX') . 'appoint'=>'`a`' , C('DB_PREFIX') .'merchant_store'=>'`s`',C('DB_PREFIX').'user'=>'`u`');
            $condition_where = ' (`asu`.`worker_id` = 0) AND `asu`.`store_id`='.$this->store_session['store_id'].' AND asu.status=1';
            $order_fields = array('`o`.`order_name`','`o`.`appoint_date`','`o`.`appoint_time`','`o`.`product_id`','`o`.`product_name`','`o`.`product_price`','`o`.`appoint_price`');
            $supply_fields = array('`asu`.*');
            $appoint_fields = array('`a`.`appoint_name`');
            $store_fields = array('`s`.`name` as `store_name`');
            $user_fields = array('`u`.`nickname`','`u`.`phone`');
            $condition_fields = array_merge($order_fields , $supply_fields , $appoint_fields , $store_fields , $user_fields);
            $list = M('Appoint_supply')->field($condition_fields)->join('as asu left join '.C('DB_PREFIX') .'appoint_order o ON o.order_id = asu.order_id left join '.C('DB_PREFIX').'appoint a ON a.appoint_id = o.appoint_id left join '.C('DB_PREFIX') .'merchant_store s on s.store_id = asu.store_id left join  '.C('DB_PREFIX').'user u on u.uid = o.uid')->where($condition_where)->select();
			
            foreach($list as $Key => $val){
                if($val['order_time'] > 0){
                    $list[$Key]['order_time'] = date('Y-m-d H:i:s',$val['order_time']);
                }else{
                    unset($list[$Key]['order_time']);
                }

                if(empty($val['order_name'])){
                    $list[$Key]['order_name'] = $val['appoint_name'];
                }

                switch ($val['pay_type']) {
                    case 'offline':
                        $list[$Key]['pay_method'] = '线下支付';
                        break;
                    default:
                        if ($val['paid']) {
                            $list[$Key]['pay_method'] = '在线支付';
                        } else {
                            $list[$Key]['pay_method'] = '未支付';
                        }
                        break;
                }
            }

            if (empty($list)) {
                exit(json_encode(array('err_code' => true)));
            }else{
                exit(json_encode(array('err_code' => false, 'list' => $list)));
            }
        }

        $this->display();
    }

    public function pick(){
        if(IS_POST){
            $supply_id = intval(I("supply_id"));
            if (!$supply_id) {
                $this->error("传递参数有误！");
            }
            $database_appoint_supply = D('Appoint_supply');
            $now_supply = $database_appoint_supply->where(array('supply_id' => $supply_id, 'worker_id' => $this->worker_session['merchant_worker_id']))->find();

            if (empty($now_supply)) {
                $this->error("信息错误");
            }

            if ($now_supply['status'] != 2) {
                $this->error("此单暂时不能进行已完成操作");
            }

            $columns = array();
            $columns['worker_id'] = $this->worker_session['merchant_worker_id'];
            $columns['status'] = 3;
            $columns['end_time'] = time();
            $result = $database_appoint_supply->where(array("supply_id" => $supply_id, 'status' => 2))->data($columns)->save();
			
            if (!$result) {
                $this->error("更新状态失败");
            }else{
                $database_appoint_order = D('Appoint_order');
                $now_order = $database_appoint_order->where(array('order_id'=>$now_supply['order_id']))->find();
                if(($now_order['payment_money'] >= $now_order['appoint_price']) && !$now_order['product_id']){
                    $order_param['order_id'] = $now_supply['order_id'];
                    $order_param['is_mobile'] = 1;
                    $order_param['complete_source'] = 1;
                    $result = $database_appoint_order->balance_after_pay($order_param);
				
				
                    if(!$result['error']){
                        $this->success("更新状态成功");
                    }else{
                        $this->error("更新状态失败");
                    }
                }else{
                    $data_order['complete_source'] = 2;
                    $data_order['is_mobile'] = 1;
                    //$data_order['service_status'] = 1;
                    $result = $database_appoint_order->where(array('order_id'=>$now_supply['order_id']))->data($data_order)->save();
					
                    if($result){
                        $this->success("更新状态成功");
                    }else{
                        $this->error("更新状态失败");
                    }
                }
            }
        }else{
            $condition_table = array(C('DB_PREFIX') . 'appoint_supply' => '`as`' , C('DB_PREFIX') .'appoint_order'=>'`o`', C('DB_PREFIX') . 'appoint'=>'`a`' , C('DB_PREFIX') .'merchant_store'=>'s', C('DB_PREFIX') . 'user'=>'`u`');
            $condition_where = '(`asu`.`worker_id` = '  . $this->worker_session['merchant_worker_id'] . ') AND (`asu`.`status` = 2) ';
            $order_fields = array('`o`.`order_name`','`o`.`appoint_date`','`o`.`appoint_time`','`o`.`cue_field`','`o`.`type`','`o`.`appoint_type`','`o`.`product_id`','`o`.`product_name`','`o`.`product_price`','`o`.`appoint_price`');
            $supply_fields = array('`asu`.*');
            $appoint_fields = array('`a`.`appoint_name`');
            $store_fields = array('`s`.`name` as `store_name`');
            $user_fields = array('`u`.`nickname`,`u`.`phone`');
            $condition_fields = array_merge($order_fields , $supply_fields , $appoint_fields , $store_fields ,$user_fields);
            $list = M('Appoint_supply')->field($condition_fields)->join('as asu left join '.C('DB_PREFIX') .'appoint_order o ON o.order_id = asu.order_id left join '.C('DB_PREFIX').'appoint a ON a.appoint_id = o.appoint_id left join '.C('DB_PREFIX') .'merchant_store s on s.store_id = asu.store_id left join  '.C('DB_PREFIX').'user u on u.uid = o.uid')->where($condition_where)->order('`asu`.`create_time` desc')->select();
		
            if (false === $list) {
                $this->error_tips("系统错误");
            }

            foreach($list as $Key => $supply){
                $list[$Key]['order_time'] = date('Y-m-d H:i:s',$supply['order_time']);
                $cue_field = unserialize($supply['cue_field']);
                $tmp_fields = array();
                foreach($cue_field as $field){
                    $tmp_fields[] = $field['name'] .' : '.$field['value'];
                }
                $list[$Key]['note'] = $tmp_fields;
            }
            $this->assign('list', $list);
            $this->display();
        }
    }

    //位置导航
    public function map(){
        $supply_id = I("supply_id", 0, 'intval');
        if (! $supply_id) {
            $this->error("SupplyId不能为空");
        }

        $condition_table = array(C('DB_PREFIX') . 'appoint_supply' => '`as`' , C('DB_PREFIX') .'appoint_order'=>'`o`' , C('DB_PREFIX') .'merchant_store'=>'s');
        $condition_where = '(`as`.`order_id` = `o`.`order_id`) AND (`as`.`worker_id` = '  . $this->worker_session['merchant_worker_id'] . ') AND (`as`.`supply_id`='.$supply_id.') AND (`s`.`store_id`=`as`.`store_id`)';
        $order_fields = array('`o`.`cue_field`');
        $supply_fields = array('`as`.*');
        $store_fields = array('`s`.`name` as `store_name`','`s`.`long` as `store_long`','`s`.`lat` as `store_lat`');
        $condition_fields = array_merge($order_fields , $supply_fields , $store_fields);
        $supply = M('')->where($condition_where)->table($condition_table)->field($condition_fields)->order('`as`.`create_time` desc')->find();
        $supply['cue_field'] = unserialize($supply['cue_field']);

        if (!$supply) {
            $this->error("配送源不存在");
        }
        $this->assign('supply', $supply);
        $this->display();
    }

    public function finish(){
        $this->display();
    }

    public function ajaxFinish(){
        $page = isset($_GET['page']) && $_GET['page'] ? intval($_GET['page']) : 1;
        $page = max(1, $page);

        $condition_table = array(C('DB_PREFIX') . 'appoint_supply' => '`as`' , C('DB_PREFIX') .'appoint_order'=>'`o`', C('DB_PREFIX') . 'appoint'=>'`a`' , C('DB_PREFIX') .'merchant_store'=>'s',C('DB_PREFIX').'user'=>'`u`');
        $condition_where = ' (`asu`.`worker_id` = '  . $this->worker_session['merchant_worker_id'] . ') AND (`asu`.`status` = 3) AND ( asu.is_hide = 0 )';
        $order_fields = array('`o`.`order_name`','`o`.`appoint_date`','`o`.`appoint_time`','`o`.`cue_field`','`o`.`product_id`','`o`.`product_name`','`o`.`product_price`','`o`.`appoint_price`');
        $supply_fields = array('`asu`.*');
        $appoint_fields = array('`a`.`appoint_name`');
        $store_fields = array('`s`.`name` as `store_name`');
        $user_fields = array('`u`.`nickname`,`u`.`phone`');
        $condition_fields = array_merge($order_fields , $supply_fields , $appoint_fields , $store_fields , $user_fields);

        $count = M('Appoint_supply')->join('as asu left join '.C('DB_PREFIX') .'appoint_order o ON o.order_id = asu.order_id left join '.C('DB_PREFIX').'appoint a ON a.appoint_id = o.appoint_id left join '.C('DB_PREFIX') .'merchant_store s on s.store_id = asu.store_id left join  '.C('DB_PREFIX').'user u on u.uid = o.uid')->where($condition_where)->count();
        $page_size = 10;
        $start = $page_size * ($page - 1);
        $list = M('Appoint_supply')->join('as asu left join '.C('DB_PREFIX') .'appoint_order o ON o.order_id = asu.order_id left join '.C('DB_PREFIX').'appoint a ON a.appoint_id = o.appoint_id left join '.C('DB_PREFIX') .'merchant_store s on s.store_id = asu.store_id left join  '.C('DB_PREFIX').'user u on u.uid = o.uid')->where($condition_where)->field($condition_fields)->limit($start . ',' . $page_size)->select();


        foreach($list as $Key => $supply){
            $list[$Key]['order_time'] = date('Y-m-d H:i:s',$supply['order_time']);
            $cue_field = unserialize($supply['cue_field']);
            $tmp_fields = array();
            foreach($cue_field as $field){
                $tmp_fields[] = $field['name'] .' : '.$field['value'];
            }

            $list[$Key]['note'] = $tmp_fields;
        }

        exit(json_encode(array('list' => $list, 'err_code' => false , 'total' => ceil($count/$page_size) , 'count'=>count($list))));
    }
    public function del(){
        $worker_id = $this->worker_session['merchant_worker_id'];
        $supply_id = intval(I("supply_id"));

        $database_appoint_supply = D('Appoint_supply');
        if ($supply = $database_appoint_supply->where(array('worker_id' => $worker_id, 'supply_id' => $supply_id, 'status' => 3))->find()) {
            $database_appoint_supply->where(array('worker_id' => $worker_id, 'supply_id' => $supply_id, 'status' => 3))->save(array('is_hide' => 1));
            $this->success('ok');
        } else {
            $this->error("配送信息错误");
        }
    }

    public function logout(){
        $_SESSION['worker_session'] = null;
        $_SESSION['store_session'] = null;
        redirect(U('login'));
    }

    function setting(){
        $this->display();
    }


    public function setting_worker_time(){
        $database_merchant_workers = D('Merchant_workers');
        $worker_where['merchant_worker_id'] = $this->worker_session['merchant_worker_id'];
        $worker_where['status'] = 1;
        if(IS_POST){
            $office_start_time = $_POST['office_start_time'];
            $office_stop_time = $_POST['office_stop_time'];
            $time_gap = $_POST['time_gap'] + 0;

            if ($office_start_time != '00:00' || $office_stop_time != '00:00') {
                $office_time = array('open' => $office_start_time, 'close' => $office_stop_time);
            } else {
                $office_time = array('open' => '00:00', 'close' => '00:00');
            }

            //时间间隔
            if($time_gap % 10 != 0){
                $this->error_tips('间隔时间必须是10的倍数');
            }
            $data['time_gap'] = $time_gap;

            if ($office_time) {
                $data['office_time'] = serialize($office_time);
            }
            $insert_id = $database_merchant_workers->where($worker_where)->data($data)->save();
            if($insert_id){
                $this->success_tips('修改成功！');
            }else{
                $this->error_tips('修改失败！');
            }
        }else{
            $now_worker = $database_merchant_workers->where($worker_where)->find();
            $now_worker['office_time'] = unserialize($now_worker['office_time']);
            $this->assign('now_worker' , $now_worker);
            $this->display();
        }
    }


    public function comment_list(){
        if(!$this->worker_session){
            redirect(U('login', array('referer' => urlencode('http://' . $_SERVER['HTTP_HOST'] . (!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'])))));
        }

        $database_appoint_comment = D('Appoint_comment');
        $comment_where['status'] = 1;
        $comment_where['merchant_worker_id'] = $this->worker_session['merchant_worker_id'];
        $comment_list = $database_appoint_comment->appoint_comment_list($comment_where , true , 'id desc' , 999);

        $this->assign('comment_list' , $comment_list['result']);
        $this->display();
    }

    //	交易记录
    public function money_record(){
        $this->display();
    }

    //	交易记录json
    public function transaction_json(){
        $page	=	$_POST['page'];
        $transaction	=	$this->get_list($this->worker_session['merchant_worker_id'],$page,20);
        fdump($transaction, 'post_info', true);
        $transaction['count'] = count($transaction['money_list']);
        foreach($transaction['money_list'] as $k=>$v){
            $transaction['money_list'][$k]['time_s']	=	date('Y/m/d H:i',$v['time']);
            $transaction['money_list'][$k]['money']	=	floatval($transaction['money_list'][$k]['money']	);
        }
        echo json_encode($transaction);
    }


    /*获取列表*/
    public function get_list($merchant_worker_id,$page=0,$page_count=0){
        $condition_user_money_list['merchant_worker_id'] = $merchant_worker_id;
        $condition_user_money_list['_string'] =" `desc` not like '%兑换余额%' AND `desc` not like '%兑换记录%'  AND `desc` not like '%不可提现%' ";
        import('@.ORG.user_page');
        $merchant_workers_money =  M('Merchant_workers_money');
        $count = $merchant_workers_money->where($condition_user_money_list)->count();
        $p = new Page($count,10);
        if($page){
            $return['money_list'] = $merchant_workers_money->field(true)->where($condition_user_money_list)->order('`time` DESC')->page($page.','.$page_count)->select();
        }else{
            $return['money_list'] = $merchant_workers_money->field(true)->where($condition_user_money_list)->order('`time` DESC')->limit($p->firstRow.',10')->select();
        }
        $return['pagebar'] = $p->show();
        return $return;
    }
}
?>