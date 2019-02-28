<?php
/*
    小区收费管理
 */
class CashierAction extends BaseAction{
    protected $village_id;

    public function _initialize(){
        parent::_initialize();
        $this->village_id = $this->house_session['village_id'];
        $this->role_id = $this->house_session['role_id']; //角色id
    }
    public $pay_list_type = array(
            'property'=>'物业费',
            'water'=>'水费',
            'electric'=>'电费',
            'gas'=>'燃气费',
            'park'=>'停车费',
            'custom'=>'其他缴费',
            'custom_payment'=>'自定义缴费',
    );
   

    /*-------------------------------------------------------收银台 start 20180615----------------------------------------------------------*/
    //水电燃气停车费 按照业主信息中来，物业费自定义费用按照订单来
    public function cashier(){
        //收银台-查看 权限
        if (!in_array(66, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_property = D('House_village_property');
        $where['village_id'] = $_SESSION['house']['village_id'];
        $where['status'] = 1;
        
        $list = $database_house_village_property->house_village_proerty_page_list($where , true , 'property_month_num desc' , 99999);

        if(!$list){
            $this->error_tips('数据处理有误！');
        }else{
            if($list['status']){
                $this->assign('list' , $list['list']);
            }else{
                $this->error_tips('请先添加缴费优惠。',U('Unit/preferential_add'));
            }
        }
        $this->display();
    }

    //已缴订单
    public function cashier_paid_list(){
        //已缴 权限
        if (!in_array(83, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'name') {
                $condition['name'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'phone') {
                $condition['phone'] = $_GET['keyword'] ;
            }
        }

        if($_GET['searchstatus']){
            $condition['pay_type'] = $_GET['searchstatus'];
        }

        if ($_GET['begin_time']&&!$_GET['end_time']) {
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time > ".$period[0].")";
            $condition['pay_time_str']=$time_condition;
        }

        if (!$_GET['begin_time']&&$_GET['end_time']) {
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time < ".$period[1].")";
            $condition['pay_time_str']=$time_condition;
        }

        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['pay_time_str']=$time_condition;
        }

        $database_house_village_pay_order = D('House_village_pay_order');
        $village_id = $this->village_id;

        //线下支付方式
        $pay_type_list = D('House_village_pay_type')->where(array('village_id'=>$village_id))->select();
        $this->assign('pay_type_list',$pay_type_list);

        if($village_id){
            // 历史缴费
            $condition['village_id'] = $village_id;

            $paid_list = D('House_village_pay_cashier_order')->get_limit_list_page($condition,20);
            $total = 0;
            if($paid_list){
                foreach ($paid_list['order_list'] as &$v){
                    $total += $v['money'];                              //本页的总额
                    if ($pay_type_list) {
                        foreach ($pay_type_list as $key => $value) {
                            if ($v['pay_type'] == $value['id']) {
                                $v['pay_type_name'] = $value['name'];
                            }
                        }
                    }
                   $v['pay_type_name'] = $v['pay_type_name'] ? $v['pay_type_name'] : '在线支付';
                    
                }
            }
            $paid_list['total'] = $total;
            $this->assign('paid_list',$paid_list);
        }
        //打印模板
        $database_house_village_print_template = D('House_village_print_template');
        $print_template = $database_house_village_print_template->where(array('village_id'=>$village_id))->select();
        // var_dump($print_template);
        $this->assign('print_template',$print_template);
        $this->assign('village_id',$village_id);
        $this->display();
    }
    
    //未缴 获得详情
    public function ajax_cashier_unpaid_detail(){
        //未缴 权限
        // if (!in_array(85, $this->house_session['menus'])) {
        //     $this->error('对不起，您没有权限执行此操作');
        // }

        $database_house_village_pay_order = D('House_village_pay_order');
        $village_id = $this->village_id;
        $pigcms_id = $_POST['pigcms_id']+0;


        if($village_id){
            $user_info = D('House_village_user_bind')->get_one_by_bindId($pigcms_id);
            $user_info['water_price'] = floatval($user_info['water_price']);
            $user_info['electric_price'] = floatval($user_info['electric_price']);
            $user_info['gas_price'] = floatval($user_info['gas_price']);
            $user_info['park_price'] = floatval($user_info['park_price']);

            //根据物业到期时间，计算物业费
            $user_info['property_price'] = 0;
            if ($user_info['property_endtime'] && $user_info['property_endtime'] < strtotime(date("Y-m-d"))) {

                $now_village = D('House_village')->get_one($column['village_id']);
                $now_floor_info = D('House_village_floor')->get_floor_info($user_info['floor_id']);

                $num =  D('House_village_user_bind')->getTimeNum($user_info['property_endtime'],strtotime(date("Y-m-d")),'M');
                if (($now_floor_info['property_fee'] != '0.00') && isset($now_floor_info['property_fee'])) {
                    $user_info['property_price'] = $now_floor_info['property_fee'] * $user_info['housesize'] * $num;
                } else {
                    $user_info['property_price'] = $now_village['property_price'] * $user_info['housesize'] * $num;
                }
            }

            //自定义项欠费
            $user_info['cunstom_money'] = 0;
            $payment_list = array();
            $payment_list = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',
            C('DB_PREFIX').'house_village_payment_standard'=>'ps',
            C('DB_PREFIX').'house_village_payment'=>'p'))
            ->where("psb.pigcms_id= '".$user_info['pigcms_id']."' AND p.payment_id = psb.payment_id AND `psb`.`cycle_sum` > `psb`.`paid_cycle` AND ps.standard_id = psb.standard_id AND `psb`.`start_time` <".time())->select();
            $payment_list = $payment_list ? $payment_list : array();

             // 车位缴费
            $position_payment_list = D('House_village_bind_position')->get_user_position_payment_list(array('pigcms_id'=>$user_info['pigcms_id']));
            $payment_list = array_merge($payment_list, $position_payment_list);
            // var_dump($payment_list);
            if ($payment_list) {
                foreach ($payment_list as $key => $value) {
                    switch ($value['cycle_type']) {
                        case 'Y':
                            $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400;
                            if ($end_time<time()) {
                                $num = D('House_village_user_bind')->getTimeNum($end_time,strtotime(date("Y-m-d")),'Y');
                                $num = ceil($num/$value['pay_cycle']);
                                if ($value['pay_type']==1) {
                                    $user_info['cunstom_money'] += $value['pay_money'] * $num;
                                }else{
                                    $user_info['cunstom_money'] += $value['pay_money'] * $num * $value['metering_mode_val'];
                                }
                            }
                            break;
                        case 'M': 
                            // 月份，日期
                            // 到期时间
                            $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400*30;
                            if ($end_time<time()) {
                                $num = D('House_village_user_bind')->getTimeNum($end_time,strtotime(date("Y-m-d")),'M');
                                $num = ceil($num/$value['pay_cycle']);
                                if ($value['pay_type']==1) {
                                    $user_info['cunstom_money'] += $value['pay_money'] * $num;
                                }else{
                                    $user_info['cunstom_money'] += $value['pay_money'] * $num * $value['metering_mode_val'];
                                }
                            }
                            break;
                        case 'D':
                            $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400*365;
                            if ($end_time<time()) {
                                $num = D('House_village_user_bind')->getTimeNum($end_time,time(),'Y');
                                $num = ceil($num/$value['pay_cycle']);
                                if ($value['pay_type']==1) {
                                    $user_info['cunstom_money'] += $value['pay_money'] * $num;
                                }else{
                                    $user_info['cunstom_money'] += $value['pay_money'] * $num * $value['metering_mode_val'];
                                }
                            }
                            break;
                    }
                }
            }

            $user_info['total'] = floatval($user_info['water_price'])+floatval($user_info['electric_price'])+floatval($user_info['gas_price'])+floatval($user_info['park_price']) + $user_info['property_price'] + $user_info['cunstom_money'];
           exit(json_encode(array('status'=>0,'data'=>$user_info)));
        }
    }

    // 添加缴费订单
    public function owner_order_add(){
        //收银台-查看 权限
        if (!in_array(67, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){

            $type = $_POST['type'];
            $bind_where['usernum'] = $_POST['usernum'];
            $database_house_village_user_bind = D('House_village_user_bind');
            $now_bind_info         = $database_house_village_user_bind->where($bind_where)->find();
            $now_village = D('House_village')->get_one($now_bind_info['village_id']);

            if (!$now_bind_info) {
                $this->error('该物业编号不存在！');
            }

            if($type=='property') {
                $database_house_village_property  = D('House_village_property');
                $database_house_village_floor     = D('House_village_floor');



                $now_floor_info = $database_house_village_floor->get_floor_info($now_bind_info['floor_id']);

                $property_where['id'] = $_POST['property_id'] + 0;
                $now_property_info    = $database_house_village_property->house_village_property_detail($property_where);
                $now_property_info    = $now_property_info['detail'];

                if (!$now_property_info) {
                    $this->error('物业缴费周期不存在！');
                }

                $data['order_name']         = '缴纳物业费';
                $data['order_type']         = 'property';
                $data['village_id']         = $this->house_session['village_id'];
                $data['time']               = time();
                $data['property_month_num'] = $now_property_info['property_month_num'];
                $data['floor_type_name']    = $now_floor_info['name'] ? $now_floor_info['name'] : '';
                $data['house_size']         = $now_bind_info['housesize'];
                $data['bind_id']            = $now_bind_info['pigcms_id'];
                $data['uid']                = $now_bind_info['uid'];
                $data['diy_type']           = $now_property_info['diy_type'];
                if ($now_property_info['diy_type'] > 0) {
                    $data['diy_content'] = $now_property_info['diy_content'];
                } else {
                    $data['presented_property_month_num'] = $now_property_info['presented_property_month_num'] ? $now_property_info['presented_property_month_num'] : 0;
                }

                if (($now_floor_info['property_fee'] != '0.00') && isset($now_floor_info['property_fee'])) {
                    $data['money']        = $now_floor_info['property_fee'] * $now_bind_info['housesize'] * $now_property_info['property_month_num'];
                    $data['property_fee'] = $now_floor_info['property_fee'];
                } else {
                    $data['money']        = $now_village['property_price'] * $now_bind_info['housesize'] * $now_property_info['property_month_num'];
                    $data['property_fee'] = $now_village['property_price'];
                }
                $data['remarks'] = $_POST['remarks'];
                $order_id = D("House_village_pay_order")->add($data);
                if ($order_id) {
                    $this->success('添加成功');
                } else {
                    $this->error('订单创建失败，请重试');
                }
            }else{

                switch($type){
                    case 'water':
                        if(empty($now_village['water_price'])) $this->error('当前小区不支持缴纳水费');
                        $pay_money = $now_bind_info['water_price'];
                        $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_water` AS `use`,`water_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                        foreach($order_list as $key=>$value){
                            $order_list[$key]['desc'] = '用水 '.floatval($value['use']).' 立方米，总费用 '.floatval($value['price']).' 元';
                        }
                        $data_order['order_name'] = '水费';
                        break;
                    case 'electric':
                        if(empty($now_village['electric_price'])) $this->error('当前小区不支持缴纳电费');
                        $pay_money = $now_bind_info['electric_price'];
                        $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_electric` AS `use`,`electric_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                        foreach($order_list as $key=>$value){
                            $order_list[$key]['desc'] = '用电 '.floatval($value['use']).' 千瓦时(度)，总费用 '.floatval($value['price']).' 元';
                        }
                        $data_order['order_name'] = '电费';
                        break;
                    case 'gas':
                        if(empty($now_village['gas_price'])) $this->error('当前小区不支持缴纳燃气费');
                        $pay_money = $now_bind_info['gas_price'];
                        $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_gas` AS `use`,`gas_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                        foreach($order_list as $key=>$value){
                            $order_list[$key]['desc'] = '使用燃气 '.floatval($value['use']).' 立方米，总费用 '.floatval($value['price']).' 元';
                        }
                        $data_order['order_name'] = '燃气费';
                        break;
                    case 'park':
                        if(empty($now_village['park_price'])) $this->error('当前小区不支持缴纳停车费');
                        $pay_money = $now_bind_info['park_price'];
                        $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`park_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                        foreach($order_list as $key=>$value){
                            $order_list[$key]['desc'] = '停车费 '.floatval($value['price']).' 元';
                        }
                        $data_order['order_name'] = '停车费';
                        break;
                    case 'custom_payment':
                        $pay_money = $_POST['payment_price'];
                        $data_order['order_name'] = $_POST['payment_name'];
                        $data_order['payment_paid_cycle'] = $_POST['payment_paid_cycle'];
                        $data_order['payment_bind_id'] = $_POST['payment_bind_id'];
                        break;
                    case 'custom':
                        $pay_money = $_POST['custom_price'];
                        $data_order['order_name'] = '自定义缴费【'.$_POST['custom_remark'].'】';
                        break;
                }
         
                $data_order['money'] = $pay_money ;
                $data_order['uid'] = $now_bind_info['uid'];
                $data_order['bind_id'] = $now_bind_info['pigcms_id'];
                $data_order['village_id'] = $now_village['village_id'];
                $data_order['time'] = $_SERVER['REQUEST_TIME'];
                $data_order['paid'] = 0;
                $data_order['order_type'] = $type;
                $data_order['remarks'] = $_POST['remarks'];

                if($order_id = D('House_village_pay_order')->data($data_order)->add()){
                    $this->success('添加成功');
                }else{
                    $this->error('下单失败，请重试');
                }
            }
        }else{
            $database_house_village_property = D('House_village_property');
            $where['village_id'] = $_SESSION['house']['village_id'];
            $where['status'] = 1;

            //收银台过来
            if (!$_GET['pigcms_id']) {
                $this->error('数据处理有误！');
            }
            
            $user_info = D('House_village_user_bind')->get_one_by_bindId($_GET['pigcms_id']);
            $this->assign('user_info' , $user_info);
            $this->assign('pigcms_id' , $_GET['pigcms_id']);
            
            $list = $database_house_village_property->house_village_proerty_page_list($where , true , 'property_month_num desc' , 99999);

            if(!$list){
                $this->error('数据处理有误！');
            }else{
                if($list['status']){
                    $this->assign('list' , $list['list']);
                }else{
                    $this->error('请先添加缴费优惠。',U('Unit/preferential_add'));
                }
            }
            $this->display();
        }
    }

    public function ajax_user_list(){
        if(IS_JAX){
            $find_type = $_POST['find_type'];
            $find_value = $_POST['find_value'];

            if ($find_value) {
                if ($find_type == 1) {
                    $where['name'] = array('like', '%' . $find_value . '%');
                } else if ($find_type == 2) {
                    $where['phone'] = array('like', '%' . $find_value . '%');
                } else if ($find_type == 3) {
                    $where['usernum'] = array('like', '%' . $find_value . '%');
                }
            }
            if($_POST['usernum']){
                $where['usernum'] = $_POST['usernum'];
            }
            if($_POST['room_addrss']){
                $where['room_addrss'] = array('like', '%' . $_POST['room_addrss'] . '%');
            }
            if($_POST['pigcms_id']){
                $where['pigcms_id'] = array('like', '%' . $_POST['pigcms_id'] . '%');
            }
            if($_POST['type']){
                $type='true';
            }
                        
            $village_id = $this->village_id;
            if (empty($where)) {
                $user_list = D('House_village_user_bind')->get_limit_list_page($village_id);
            } else {
                $user_list = D('House_village_user_bind')->get_limit_list_page($village_id, 99999, $where,$type);
            }

            $user_list = $user_list['user_list'];

             $cycle_type = array(
                    'Y'=>'年',
                    'M'=>'月',
                    'D'=>'日',
                );

            foreach ($user_list as $key => $value) {
                $payment_list = array();
                $payment_list = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',
                C('DB_PREFIX').'house_village_payment_standard'=>'ps',
                C('DB_PREFIX').'house_village_payment'=>'p'))
                ->where("psb.pigcms_id= '".$value['pigcms_id']."' AND p.payment_id = psb.payment_id AND ps.standard_id = psb.standard_id")->select();
                $payment_list = $payment_list ? $payment_list : array();

                // 车位缴费
                $position_payment_list = D('House_village_bind_position')->get_user_position_payment_list(array('pigcms_id'=>$value['pigcms_id']));
                $payment_list = array_merge($payment_list, $position_payment_list);

                foreach ($payment_list as $kk => $vv) {
                    $payment_list[$kk]['start_time'] = date('Y-m-d',$vv['start_time']);
                    $payment_list[$kk]['end_time'] = date('Y-m-d',$vv['end_time']);
                    $payment_list[$kk]['cycle_type'] = $cycle_type[$vv['cycle_type']];
                    if ($vv['garage_num']) {
                        $payment_list[$kk]['payment_name'] = $vv['payment_name'].'('.$vv['garage_num'].'-'.$vv['position_num'].')';
                    }
                }
                
                $user_list[$key]['payment_list'] = $payment_list;

            }

            if($user_list){
                exit(json_encode(array('status'=>1,'user_list'=>$user_list)));
            }else{
                exit(json_encode(array('status'=>0,'user_list'=>$user_list)));
            }

        }else{
            $this->error_tips('访问页面有误！');
        }
    }

    // 删除订单
    public function del_pay_order(){
        //收银台-删除 权限
        if (!in_array(69, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        if(IS_JAX){
            $order_id = intval($_POST['order_id']);
            $database_house_village_pay_order = D('House_village_pay_order');
            $pay_order = $database_house_village_pay_order->get_one($order_id);
            if (!$pay_order) exit(json_encode(array('status'=>1,'msg'=>'该订单信息不存在')));
            
            if ($pay_orde['paid'] > 0 ) {
                exit(json_encode(array('status'=>1,'msg'=>'该订单已付款，不能删除')));
            } 
            
            $res = $database_house_village_pay_order->where(array('order_id'=>$order_id))->delete();
            if($res){
                exit(json_encode(array('status'=>0,'msg'=>'删除成功')));
            }else{
                exit(json_encode(array('status'=>1,'msg'=>'删除失败')));
            }
        }else{
            $this->error_tips('访问页面有误！');
        }
    }

    //个人账单
    //水电燃气停车费 按照业主信息中来，物业费自定义费用按照订单来 可以删除
    public function personal_order_list(){
        //收银台-查看 权限
        if (!in_array(66, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_pay_order = D('House_village_pay_order');
        $where['bind_id'] = $_GET['bind_id'];

        $village_id = $this->village_id;

        //线下支付方式
        $pay_type_list = D('House_village_pay_type')->where(array('village_id'=>$village_id))->select();

        if($village_id){
            $where['village_id'] = $village_id;
            $where['order_type'] = "'custom','custom_payment','property'";
            $where['paid'] = 0; //未付款
            $where['cashier_id'] = 0;
            $pay_list_order = D('House_village_pay_order')->get_limit_list_page($where,20);

            $totalmoney = 0;
            if($pay_list_order){
                foreach ($pay_list_order['order_list'] as $v){
                    $totalmoney += $v['money'];                              //本页的总额
                }
            }
            $pay_list_order['total'] = $totalmoney;
           
            $this->assign('pay_list_order',$pay_list_order);

            //小区信息
            $now_village = D('House_village')->get_one($village_id);
            //当前业主信息
            $database_house_village_user_bind = D('House_village_user_bind');
            $now_user_info = $database_house_village_user_bind->get_one_by_bindId($_GET['bind_id']);
            $this->assign('now_user_info',$now_user_info);
            //缴费
            $pay_list = array();
            $now_village['property_price'] = getFormatNumber($now_village['property_price']);
            $now_village['water_price'] = getFormatNumber($now_village['water_price']);
            $now_village['electric_price'] = getFormatNumber($now_village['electric_price']);
            $now_village['gas_price'] = getFormatNumber($now_village['gas_price']);
            $now_village['park_price'] = getFormatNumber($now_village['park_price']);
            //物业费
            // $pay_list[] = array(
            //         'type' => 'property',
            //         'name' => $this->pay_list_type['property'],
            //         'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'property')),
            //         'money'=>floatval($now_user_info['property_price']),
            // );
            // 水费
            if($now_village['water_price']&&$now_user_info['water_price']){
                $totalmoney += floatval($now_user_info['water_price']);
                $pay_list[] = array(
                        'type' => 'water',
                        'name' => $this->pay_list_type['water'],
                        'url' => $now_village['water_price'] ? U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'water')) : U('Lifeservice/query',array('type'=>'water')),
                        'money'=>floatval($now_user_info['water_price']),
                );
            }
            // 电费
            if($now_village['electric_price']&&$now_user_info['electric_price']){
                $totalmoney += floatval($now_user_info['electric_price']);
                $pay_list[] = array(
                        'type' => 'electric',
                        'name' => $this->pay_list_type['electric'],
                        'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'electric')),
                        'money'=>floatval($now_user_info['electric_price']),
                );
            }
            // 燃气费
            if($now_village['gas_price']&&$now_user_info['gas_price']){
                $totalmoney += floatval($now_user_info['gas_price']);
                $pay_list[] = array(
                        'type' => 'gas',
                        'name' => $this->pay_list_type['gas'],
                        'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'gas')),
                        'money'=>floatval($now_user_info['gas_price']),
                );
            }
            // 停车费
            if($now_village['park_price']&&$now_user_info['park_price']){
                $totalmoney += floatval($now_user_info['park_price']);
                $pay_list[] = array(
                        'type' => 'park',
                        'name' => $this->pay_list_type['park'],
                        'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'park')),
                        'money'=>floatval($now_user_info['park_price']),
                );
            }
            // // 自定义缴费
            // if($now_village['has_custom_pay']){
            //     $pay_list[] = array(
            //             'type' => 'custom',
            //             'name' => $this->pay_list_type['custom'],
            //             'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'custom')),
            //             'money'=> -1,
            //     );
            // }

            // $payment_list = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',
            //         C('DB_PREFIX').'house_village_payment_standard'=>'ps',
            //         C('DB_PREFIX').'house_village_payment'=>'p'))
            //         ->where("psb.pigcms_id= '".$this->village_bind['pigcms_id']."' AND p.payment_id = psb.payment_id AND ps.standard_id = psb.standard_id")->select();

            // $this->assign('payment_list',$payment_list);
            $this->assign('pay_list',$pay_list);
            $this->assign('totalmoney',$totalmoney);


        }
        $this->assign('user_info',$now_user_info);
        $this->assign('pay_type_list',$pay_type_list);
        $this->assign('village_id',$village_id);
        $this->assign('pigcms_id',$_GET['bind_id']);

        $this->display();

    }

    //收银台历史账单
    public function history_cashier_order(){
        //收银台-查看 权限
        if (!in_array(66, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_pay_order = D('House_village_pay_order');
        $where['bind_id'] = $_GET['bind_id'];

        $village_id = $this->village_id; 

        //线下支付方式
        $pay_type_list = D('House_village_pay_type')->where(array('village_id'=>$village_id))->select();
        $this->assign('pay_type_list',$pay_type_list);

        if($village_id){
            // 历史缴费
            $where = array(
                'pigcms_id' => $_GET['bind_id'],
                'village_id' => $village_id,
                'paid' => 1,
            );

            $paid_list = D('House_village_pay_cashier_order')->get_limit_list_page($where,20);
            $total = 0;
            if($paid_list){
                foreach ($paid_list['order_list'] as &$v){
                    $total += $v['money'];                              //本页的总额
                    if ($pay_type_list) {
                        foreach ($pay_type_list as $key => $value) {
                            if ($v['pay_type'] == $value['id']) {
                                $v['pay_type_name'] = $value['name'];
                            }
                        }
                    }
                    
                }
            }
            $paid_list['total'] = $total;
            $this->assign('paid_list',$paid_list);
        }

        //当前业主信息
        $database_house_village_user_bind = D('House_village_user_bind');
        $now_user_info = $database_house_village_user_bind->get_one_by_bindId($_GET['bind_id']);

        //打印模板
        $database_house_village_print_template = D('House_village_print_template');
        $print_template = $database_house_village_print_template->where(array('village_id'=>$village_id))->select();
        // var_dump($print_template);
        $this->assign('user_info',$now_user_info);
        $this->assign('print_template',$print_template);
        $this->assign('village_id',$village_id);
        $this->assign('pigcms_id',$_GET['bind_id']);
        $this->display();

    }


    //收银台 确认收款
    //水电燃气停车费 按照业主信息中来，物业费自定义费用按照订单来 可以删除
    public function ajax_cashier_pay(){
        //收银台-收款 权限
        if (!in_array(68, $this->house_session['menus'])) {
             exit(json_encode(array('status'=>1,'msg'=>'对不起，您没有权限执行此操作')));
        }
        $database_house_village_pay_order = D('House_village_pay_order');
        $pigcms_id = $_GET['pigcms_id'];
        $ids = $_POST['orderids'];
        $pay_type = $_POST['pay_type']; //支付方式
        if (!$pigcms_id || !$ids) {
            exit(json_encode(array('status'=>1,'msg'=>'数据处理有误！')));
        }

        $village_id = $this->village_id;
        $now_village = D('House_village')->get_one($village_id);
        
        if (!$now_village) {
            exit(json_encode(array('status'=>1,'msg'=>'当前小区不存在！')));
        }


        //当前业主信息
        $database_house_village_user_bind = D('House_village_user_bind');
        $now_bind_info = $database_house_village_user_bind->get_one_by_bindId($pigcms_id);
        if (!$now_bind_info) {
            exit(json_encode(array('status'=>1,'msg'=>'当前业主不存在！')));
        }

        $database_house_village_pay_order = D('House_village_pay_order');

        $aId = explode(',',trim($ids,',')); //缴费项
        $aOrderId = array(); // 所有订单id
        $aDataOrder = array(); //需要添加的订单数据
        $totalmoney = 0; //总金额

        foreach ($aId as $key => $order) {
            $aOrder = explode('|', $order);
            switch ($aOrder[0]) {
                case 'custom_payment':  // 自定义缴费标准
                case 'property': // 物业费
                case 'custom': // 自定义缴费
                    // 已有订单
                    if(!$aOrder[1]){
                        exit(json_encode(array('status'=>1,'msg'=>'传递参数有误！')));
                    }
                    $order_info = $database_house_village_pay_order->get_one($aOrder[1]);
                    if ($order_info) {
                        $totalmoney += $order_info['money'];
                        $aOrderId[] = $aOrder[1];
                    }
                    break;
                
                case 'water':
                    if(empty($now_village['water_price'])) 
                        exit(json_encode(array('status'=>1,'msg'=>'当前小区不支持缴纳水费')));
                    
                    $totalmoney += $now_bind_info['water_price'];
                    $aDataOrder[] = array(
                        'money' => $now_bind_info['water_price'],
                        'uid' => $now_bind_info['uid'],
                        'bind_id' => $now_bind_info['pigcms_id'],
                        'village_id' => $village_id,
                        'time' => $_SERVER['REQUEST_TIME'],
                        'paid' => 0,
                        'order_type' => $aOrder[0],
                        'order_name' => $this->pay_list_type['water'],
                        'remarks' => $_POST['remarks'],
                    );
                    break;
                case 'electric': 
                    if(empty($now_village['electric_price'])) 
                        exit(json_encode(array('status'=>1,'msg'=>'当前小区不支持缴纳电费')));

                    $totalmoney += $now_bind_info['electric_price'];
                    $aDataOrder[] = array(
                        'money' => $now_bind_info['electric_price'],
                        'uid' => $now_bind_info['uid'],
                        'bind_id' => $now_bind_info['pigcms_id'],
                        'village_id' => $village_id,
                        'time' => $_SERVER['REQUEST_TIME'],
                        'paid' => 0,
                        'order_type' => $aOrder[0],
                        'order_name' => $this->pay_list_type['electric'],
                        'remarks' => $_POST['remarks'],
                    );
                    break;
                case 'gas':
                    if(empty($now_village['gas_price'])) 
                        exit(json_encode(array('status'=>1,'msg'=>'当前小区不支持缴纳燃气费')));
                    
                    $totalmoney += $now_bind_info['gas_price'];
                    $aDataOrder[] = array(
                        'money' => $now_bind_info['gas_price'],
                        'uid' => $now_bind_info['uid'],
                        'bind_id' => $now_bind_info['pigcms_id'],
                        'village_id' => $village_id,
                        'time' => $_SERVER['REQUEST_TIME'],
                        'paid' => 0,
                        'order_type' => $aOrder[0],
                        'order_name' => $this->pay_list_type['gas'],
                        'remarks' => $_POST['remarks'],
                    );
                    break;
                case 'park': 
                    if(empty($now_village['park_price'])) 
                        exit(json_encode(array('status'=>1,'msg'=>'当前小区不支持缴停车费')));
                    
                    $totalmoney += $now_bind_info['park_price'];
                    $aDataOrder[] = array(
                        'money' => $now_bind_info['park_price'],
                        'uid' => $now_bind_info['uid'],
                        'bind_id' => $now_bind_info['pigcms_id'],
                        'village_id' => $village_id,
                        'time' => $_SERVER['REQUEST_TIME'],
                        'paid' => 0,
                        'order_type' => $aOrder[0],
                        'order_name' => $this->pay_list_type['park'],
                        'remarks' => $_POST['remarks'],
                    );
                    break;
            }
        }
        //生成总订单
        $database_house_village_pay_cashier_order = D('House_village_pay_cashier_order');
        $data = array(
            'pay_type' => $_POST['pay_type'] ? $_POST['pay_type'] : 0,
            // 'money' => $totalmoney,
            'money' => $_POST['real_money'],
            'uid' => $now_bind_info['uid'],
            'pigcms_id' => $now_bind_info['pigcms_id'],
            'village_id' => $village_id,
            'time' => $_SERVER['REQUEST_TIME'],
            'paid' => 0,
            'remarks' => $_POST['remarks'],
            'role_id' => $this->role_id,
        );
        $cashier_id = $database_house_village_pay_cashier_order->data($data)->add();
        if (!$cashier_id) {
            exit(json_encode(array('status'=>1,'msg'=>'收款失败，请重试！')));
        }
        // 更新物业自定义缴费
        if ($aOrderId) {
            D('House_village_pay_order')->where(array('order_id'=>array('in' , $aOrderId)))->data(array('cashier_id'=>$cashier_id))->save();
        }

        // 生成水电燃气停车订单
        if ($aDataOrder) {
            foreach ($aDataOrder as $data_order) {
                $data_order['cashier_id'] = $cashier_id;
                $order_id = D('House_village_pay_order')->data($data_order)->add();
                $aOrderId[] = $order_id;
            }
        }

        //扫码支付
        if ($_POST['is_online'] == 1 && intval($_POST['real_money'])!=0) {
            exit(json_encode(array('status'=>0,'data'=>array('cashier_id'=>$cashier_id))));
        }else{
            // 支付
            $result = $database_house_village_pay_cashier_order->cashier_pay($cashier_id);
            if($result['error_code']==1){
                exit(json_encode(array('status'=>1,'msg'=>$result['msg'])));
            }else{
                exit(json_encode(array('status'=>0,'msg'=>'收款成功！')));
            }
        }
    }


    //收银台 查看订单详情
    public function cashier_detail(){
        //收银台-查看 权限
        if (!in_array(66, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_pay_order = D('House_village_pay_order');
        $cashier_id = $_GET['cashier_id'];
       
        if (!$cashier_id) {
            exit(json_encode(array('status'=>1,'msg'=>'参数传递有误！')));
        }

        $village_id = $this->village_id;
        $now_village = D('House_village')->get_one($village_id);

        //查询总订单
        $database_house_village_pay_cashier_order = D('House_village_pay_cashier_order');
        $cashier_order = $database_house_village_pay_cashier_order->get_one($cashier_id);

        //当前业主信息
        $database_house_village_user_bind = D('House_village_user_bind');
        $now_bind_info = $database_house_village_user_bind->get_one_by_bindId($cashier_order['pigcms_id']);
        // if (!$now_bind_info) {
        //     exit(json_encode(array('status'=>1,'msg'=>'当前业主不存在！')));
        // }

        $order_list = $database_house_village_pay_order->where(array('cashier_id' => $cashier_id))->select();
        $totalmoney = $database_house_village_pay_order->field(' SUM(`money` ) AS totalMoney ')->where(array('cashier_id' => $cashier_id))->find();
        $totalmoney = $totalmoney['totalMoney'];
        $this->assign('order_list',$order_list);
        $this->assign('now_bind_info',$now_bind_info);
        $this->assign('cashier_order',$cashier_order);
        $this->assign('totalmoney',$totalmoney);
        $this->display();
    }

    //收银台 打印模板配置
    public function print_template_list(){
        //打印模板设置-查看 权限
        if (!in_array(86, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_pay_order = D('House_village_pay_order');
        $village_id = $this->village_id;
        $now_village = D('House_village')->get_one($village_id);

        $where = array();
        $where['village_id'] = $village_id;

        $database_house_village_print_template = D('House_village_print_template');
        $list = $database_house_village_print_template->get_limit_list_page($where);

        $this->assign('list',$list);
        $this->display();
    }

    //打印模板配置 预览
    public function print_template_detail(){
        //打印模板设置-查看 权限
        if (!in_array(86, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $template_id = $_GET['template_id'] + 0;
        $database_house_village_print_template = D('House_village_print_template');

        if (!$template_id) {
            $this->error('参数不对');
        }
        $village_id = $this->village_id;
        $print_template = $database_house_village_print_template->get_one($template_id);
        $this->assign('print_template',$print_template);
        $this->assign('template_id',$template_id);
        $this->display();
    }


    //收银台 打印模板配置
    public function print_template_add(){
        //打印模板设置-添加 查看 编辑 权限
        if (!in_array(87, $this->house_session['menus']) && !in_array(86, $this->house_session['menus']) && !in_array(88, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $template_id = $_GET['template_id'] + 0;
        $database_house_village_print_template = D('House_village_print_template');

        $village_id = $this->village_id;
        if ($template_id) {
            $print_template = $database_house_village_print_template->get_one($template_id);
            $this->assign('print_template',$print_template);
            $this->assign('template_id',$template_id);
        }
        
        $this->assign('print_template',$print_template);
        $this->assign('template_id',$template_id);
        $this->display();
    }    

    //收银台 删除打印模板
    public function print_template_del(){
        //打印模板设置-删除 权限
        if (!in_array(89, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $template_id = $_GET['template_id'] + 0;
        $database_house_village_print_template = D('House_village_print_template');
        if ($template_id) {
            $result = $database_house_village_print_template->where(array('template_id'=>$template_id))->delete();
            if($result){
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }

    }
    //收银台 保存打印模板
    public function print_template_save(){
        $template_id = $_GET['template_id'] + 0;
        
        $database_house_village_print_template = D('House_village_print_template');
        $village_id = $this->village_id;

        $data = array();
        $data['village_id'] = $village_id;
        $data['title'] = $_POST['title'];
        $data['desc'] = $_POST['desc'];
        if ($template_id) { // 编辑
            //打印模板设置-编辑 权限
            if (!in_array(88, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $res = $database_house_village_print_template->where(array('template_id'=>$template_id))->data($data)->save();
            if($res !== false ){
                exit(json_encode(array('status'=>0,'data'=>array('template_id'=>$template_id))));
            }else{
                exit(json_encode(array('status'=>1,'msg'=>'保存失败，请重试！')));
            }
        } else {
            //打印模板设置-添加 权限
            if (!in_array(87, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $data['time'] = $_SERVER['REQUEST_TIME'];
            $template_id = $database_house_village_print_template->data($data)->add();
            if($template_id){
                exit(json_encode(array('status'=>0,'data'=>array('template_id'=>$template_id))));
            }else{
                exit(json_encode(array('status'=>1,'msg'=>'保存失败，请重试！')));
            }
        }
    }
    // 打印模板自定义配置
    public function print_template_custom(){
        //打印模板设置-添加 编辑 权限
        if (!in_array(87, $this->house_session['menus']) && !in_array(88, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $template_id = $_GET['template_id'] + 0;
        
        $database_house_village_print_template = D('House_village_print_template');
        $village_id = $this->village_id;

        //模板信息
        $print_template = $database_house_village_print_template->get_one($template_id);
        $lastIndex = 1;
        if ($print_template['custom']) {
            foreach ($print_template['custom'] as $value) {
                $lastIndex = $value['id'];
            }
        }

        //获得自定义项
        $print_custom = D('House_village_print_custom_configure')->where(array('is_hidden'=>0))->order('sort desc,configure_id asc')->select();
        // var_dump($print_template);
        $this->assign('lastIndex',$lastIndex);
        $this->assign('template_id',$template_id);
        $this->assign('print_template',$print_template);
        $this->assign('print_custom',$print_custom);
        $this->display();
    }

    // 保存模板配置项
    public function save_custom(){
        //打印模板设置-添加 编辑 权限
        if (!in_array(87, $this->house_session['menus']) && !in_array(88, $this->house_session['menus'])) {
            exit(json_encode(array('status'=>0,'msg'=>'对不起，您没有权限执行此操作')));
        }
        $ids = $_POST['ids'];
        $template_id = $_GET['template_id'] + 0;
        $database_house_village_print_custom = D('House_village_print_custom');
        if (is_array($ids)&&$ids) {
            //删除之前的
            $database_house_village_print_custom->where(array('template_id'=>$template_id,'village_id'=>$this->village_id))->delete();
            foreach ($ids as $key => $value) {
                $data = array(
                    'template_id' => $template_id,
                    'village_id' => $this->village_id,
                    'configure_id' => intval($value),
                    'time' => $_SERVER['REQUEST_TIME'],
                );
                $insert = $database_house_village_print_custom->data($data)->add();
            }
        }
        exit(json_encode(array('status'=>0)));
    }

    // 打印
    public function print_start(){
        //收银台-打印 权限
        if (!in_array(70, $this->house_session['menus']) && !in_array(84, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $template_id = $_GET['template_id'] + 0;
        $cashier_id = $_GET['cashier_id'] + 0;
        
        $village_id = $this->village_id;

        $where = array(
            'cashier_id' => $cashier_id,
            'village_id' => $village_id,
        );
        //获取订单详情
        $cashier_info = D('House_village_pay_cashier_order')->get_cashier_print_detail($where);
        //获取模板详情
        $database_house_village_print_template = D('House_village_print_template');
        $print_template = $database_house_village_print_template->get_one($template_id);
        // cashier_info
        $cashier_info['desc'] = $print_template['desc'];
        $this->assign('cashier_info',$cashier_info);
        $this->assign('print_template',$print_template);

        // exit(json_encode($this->fetch()));
        // return $this->fetch();
        $this->display();
    }

    /*-------------------------------------------------------收银台 end-------------------------------------------------------------*/

    //缴费提醒
    public function payment_reminder(){
        $sum = M('House_village')->where(array('village_id'=>$this->village_id))->getField('payment_reminder');
        if($sum > 0){
            M('House_village')->where(array('village_id'=>$this->village_id))->setField('payment_reminder',0);
            exit(json_encode(array('error'=>1,'msg'=>'您有 ('.$sum.') 条缴费提醒！')));
        }else{
            exit(json_encode(array('error'=>0,'msg'=>'暂无缴费信息')));
        }
    }


    //缴费项列表
    public function payment_item(){
        //收费设置-查看 权限
        if (!in_array(71, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $payment_list = D('House_village_payment')->where(array('village_id'=>$this->village_id))->select();
        foreach ($payment_list as $key => $value) {
            $payment_list[$key]['standard_sum'] = D('House_village_payment_standard')->where(array('payment_id'=>$value['payment_id']))->count();
        }
        $this->assign('payment_list',$payment_list);
        $this->display();
    }

    //添加缴费项
    public function payment_item_add(){
        //收费设置-收费项添加 权限
        if (!in_array(72, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        if(IS_POST){
            $res = D('House_village_payment')->data(array('payment_name'=>$_POST['payment_name'],'village_id'=>$this->village_id))->add();
            if($res){
                $this->success('添加成功，正在跳转',U('payment_item'));
                die;
            }else{
                $this->error('添加失败');
                die;
            }
        }
        $this->display();
    }

    //编辑缴费项
    public function payment_item_edit(){
        //收费设置-收费项添查看 权限
        if (!in_array(71, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        if(IS_POST){
            //收费设置-收费项编辑 权限
            if (!in_array(73, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            
            $res = D('House_village_payment')->where(array('payment_id'=>$_POST['payment_id'],'village_id'=>$this->village_id))->data(array('payment_name'=>$_POST['payment_name']))->save();
            if($res){
                $this->success('修改成功，正在跳转',U('payment_item')); die;
            }else{
                $this->error('修改失败'); die;
            }
        }
        $info = D('House_village_payment')->where(array('payment_id'=>$_GET['payment_id'],'village_id'=>$this->village_id))->find();
        if(!is_array($info)){
            $this->error('数据异常');die;
        }
        $this->assign('info',$info);
        $this->display();
    }

    //删除收费项
    public function payment_item_del(){
        //收费设置-收费项删除 权限
        if (!in_array(74, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(D('House_village_payment')->where(array('payment_id'=>$_GET['payment_id'],'village_id'=>$this->village_id))->delete()){
            D('House_village_payment_standard')->where(array('payment_id'=>$_GET['payment_id'],'village_id'=>$this->village_id))->delete();
            $this->success('删除成功',U('payment_item'));die;
        }else{
            $this->error('删除失败');die;
        }
    }


    //缴费标准 列表
    public function payment_standard(){
        //收费设置-收费标准 查看 权限
        if (!in_array(75, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $cycle_type = array(
                'Y'=>'年',
                'M'=>'月',
                'D'=>'日',
            );
        $standard_list = D('House_village_payment_standard')->where(array('village_id'=>$this->village_id,'payment_id'=>$_GET['payment_id']))->order('standard_id desc')->select();
        $this->assign('standard_list',$standard_list);
        $this->assign('cycle_type',$cycle_type);
        $this->display();
    }

    //添加标准
    public function payment_standard_add(){
        //收费设置-收费标准 添加 权限
        if (!in_array(76, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        if(IS_POST){
            $data = $_POST;
            if($_POST['metering_mode_type'] == 1){
                $data['metering_mode'] = '房间面积'; 
            }
            if($_POST['metering_mode_type'] == 3){
                $data['metering_mode'] = '车位面积'; 
            }
            if ($_POST['pay_type'] == 1) {
                $data['metering_mode'] = '固定费用'; 
                $data['metering_mode_type'] = 0;
            }
            $data['village_id'] = $this->village_id;
            $res = D('House_village_payment_standard')->data($data)->add();
            if($res){
                $this->success('添加成功',U('payment_standard',array('payment_id'=>$_POST['payment_id'])));die;
            }else{
                $this->error('添加失败');die;
            }
        }
        $this->display();
    }

    //编辑收费标准
    public function payment_standard_edit(){
        //收费设置-收费标准 查看 权限
        if (!in_array(75, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        if(IS_POST){
            //收费设置-收费标准 编辑 权限
            if (!in_array(77, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            
            $data = $_POST;
            if($_POST['metering_mode_type'] == 1){
                $data['metering_mode'] = '房间面积'; 
            }
            if($_POST['metering_mode_type'] == 3){
                $data['metering_mode'] = '车位面积'; 
            }
            if ($_POST['pay_type'] == 1) {
                $data['metering_mode'] = '固定费用'; 
                $data['metering_mode_type'] = 0;
            }
            $res = D('House_village_payment_standard')->where(array('payment_id'=>$_POST['payment_id'],'village_id'=>$this->village_id,'standard_id'=>$_POST['standard_id']))->data($data)->save();
            if($res){
                $this->success('修改成功',U('payment_standard',array('payment_id'=>$_POST['payment_id'])));die;
            }else{
                $this->error('修改失败');die;
            }
        }
        $info = D('House_village_payment_standard')->where(array('standard_id'=>$_GET['standard_id'],'village_id'=>$this->village_id,'payment_id'=>$_GET['payment_id']))->find();
        $this->assign('info',$info);
        $this->display();
    }
    
    //删除收费标准
    public function payment_standard_del(){
        //收费设置-收费标准 删除 权限
        if (!in_array(78, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        if(D('House_village_payment_standard')->where(array('payment_id'=>$_GET['payment_id'],'village_id'=>$this->village_id,'standard_id'=>$_GET['standard_id']))->delete()){
            $this->success('删除成功',U('payment_standard',array('payment_id'=>$_GET['payment_id'])));die;
        }else{
            $this->error('删除失败');die;
        }
    }

    //线下支付方式列表
    public function pay_type_list(){
        //收费设置-线下支付方式 查看 权限
        if (!in_array(79, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $paytype_list = D('House_village_pay_type')->where(array('village_id'=>$this->village_id))->select();
        foreach ($paytype_list as $key => $value) {
            $paytype_list[$key]['standard_sum'] = D('House_village_payment_standard')->where(array('payment_id'=>$value['payment_id']))->count();
        }
        $this->assign('paytype_list',$paytype_list);
        $this->display();
    }

    //添加线下支付方式
    public function pay_type_add(){

        if ($_GET['id']) {
            //收费设置-线下支付方式 查看 权限
            if (!in_array(79, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            $pay_type = D('House_village_pay_type')->where(array('id'=>$_GET['id'],'village_id'=>$this->village_id))->find();
        }
        if(IS_POST){
            if ($_GET['id']) {
                //收费设置-线下支付方式 编辑 权限
                if (!in_array(81, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }

                if (!trim($_POST['name'])) {
                    $this->error('名称不能为空');
                }

                $res = D('House_village_pay_type')->where(array('id'=>$_GET['id'],'village_id'=>$this->village_id))->data(array('name'=>trim($_POST['name'])))->save();
            }else{
                //收费设置-线下支付方式 添加 权限
                if (!in_array(80, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }

                if (!trim($_POST['name'])) {
                    $this->error('名称不能为空');
                }
                
                $res = D('House_village_pay_type')->data(array('name'=>$_POST['name'],'village_id'=>$this->village_id))->add();
            }
            if($res!==false){
                $this->success('添加成功，正在跳转',U('pay_type_list'));
                die;
            }else{
                $this->error('添加失败');
                die;
            }
        }
        $this->assign('pay_type',$pay_type);
        $this->display();
    }

    //删除线下支付方式
    public function pay_type_del(){
        //收费设置-线下支付方式 删除 权限
        if (!in_array(82, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(D('House_village_pay_type')->where(array('id'=>$_GET['id'],'village_id'=>$this->village_id))->delete()){
            $this->success('删除成功',U('pay_type_list'));die;
        }else{
            $this->error('删除失败');die;
        }
    }

    // 上传图片
    public function ajax_upload_pic(){
        if ($_FILES['imgFile']['error'] != 4) {
            $upload_dir = './upload/house/village/'.date('Ymd').'/'.date('H').'/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();
            $upload->maxSize = $this->config['group_pic_size'] * 1024 * 1024;
            $upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
            $upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif','application/octet-stream');
            $upload->savePath = $upload_dir;
            $upload->saveRule = 'uniqid';
            if ($upload->upload()) {
                $uploadList = $upload->getUploadFileInfo();
                $title = $uploadList[0]['savename'];
                $url = $upload_dir.$title;
                exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
            } else {
                exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
            }
        } else {
            exit(json_encode(array('error' => 1, 'message' => '没有选择图片')));
        }
    }
}
?>

