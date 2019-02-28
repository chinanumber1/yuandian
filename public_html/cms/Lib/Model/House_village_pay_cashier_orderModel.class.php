<?php
class House_village_pay_cashier_orderModel extends Model{
	/*得到已付款列表*/
	public function get_limit_list_page($column,$pageSize=20,$isSystem=false){
		if(!$column['village_id']){
			return null;
		}        

    	$condition_table  = array(C('DB_PREFIX').'house_village_pay_cashier_order'=>'o',C('DB_PREFIX').'house_village_user_bind'=>'b');
    	$condition_where = " `o`.`village_id` = `b`.`village_id`  AND `o`.`pigcms_id` = `b`.`pigcms_id`  AND `o`.`village_id` =".$column['village_id'];

    	if($column['pigcms_id']){
            $condition_where .= " AND `o`.`pigcms_id`=".intval($column['pigcms_id']);
        }

        // if($column['paid']){
        //     $condition_where .= " AND `o`.`paid`=".intval($column['paid']); 
        // }
        
        $condition_where .= " AND `o`.`paid`=1";

        if($column['name']){
            $condition_where .= " AND `b`.`name` like '%".$column['name']."%'";
        }

        if($column['pay_type'] == 1){//线上支付
            $condition_where .= " AND `o`.`pay_type`=0";
        }elseif($column['pay_type'] == 2){//线下 支付
            $condition_where .= " AND `o`.`pay_type`<>0";

        }

        if($column['phone']){
            $condition_where .= " AND `b`.`phone` like '%".$column['phone']."%'";
        }
        
        if($column['pay_time_str']){
    		$condition_where .= " AND ".$column['pay_time_str'];
    	}
    	
    	$condition_field = '`b`.`name` AS `username` ,b.*,o.*';
    	$order = ' `o`.`cashier_id` DESC';
    	if($isSystem){
    		import('@.ORG.system_page');
    	}else{
    		import('@.ORG.merchant_page');
    	}
    	$count_order = D('')->table($condition_table)->where($condition_where)->count();
    	$p = new Page($count_order,$pageSize,'page');
    	$order_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();


    	$total = D('')->field(' SUM(`o`.`money` ) AS totalMoney ')->table($condition_table)->where($condition_where)->find();
    	
    	$return['pagebar'] = $p->show();
    	$return['order_list'] = $order_list;
    	$return['totalMoney'] = $total;
    	return $return;
	}

    /*得到订单详情*/
    public function get_cashier_detail($column,$pageSize=20,$isSystem=false){
        if(!$column['village_id']||!$column['cashier_id']){
            return null;
        }        

        $condition_table  = array(C('DB_PREFIX').'house_village_pay_order'=>'o',C('DB_PREFIX').'house_village_user_bind'=>'b');
        $condition_where = " `o`.`village_id` = `b`.`village_id`  AND `o`.`bind_id` = `b`.`pigcms_id`  AND `o`.`village_id` =".$column['village_id'];

        if($column['pigcms_id']){
            $condition_where .= " AND `o`.`bind_id`=".intval($column['pigcms_id']);
        }

        if($column['cashier_id']){
            $condition_where .= " AND `o`.`cashier_id`=".intval($column['cashier_id']);
        }
        
        $condition_field = '`b`.`name` AS `username` ,b.*,o.*';
        $order = ' `o`.`order_id` DESC';
        if($isSystem){
            import('@.ORG.system_page');
        }else{
            import('@.ORG.merchant_page');
        }
        $count_order = D('')->table($condition_table)->where($condition_where)->count();
        $p = new Page($count_order,$pageSize,'page');
        $order_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();


        $total = D('')->field(' SUM(`o`.`money` ) AS totalMoney ')->table($condition_table)->where($condition_where)->find();
        
        $return['pagebar'] = $p->show();
        $return['order_list'] = $order_list;
        $return['totalMoney'] = $total;
        return $return;
    } 

    /*得到打印详情*/
    public function get_cashier_print_detail($column){
        if(!$column['village_id']||!$column['cashier_id']){
            return null;
        }    

        //查询订单
        $cashier_info = $this->get_one($column['cashier_id']);

        //小区信息
        $village_info = D('House_village')->where(array('village_id'=>$column['village_id']))->find();
        $cashier_info['village_name'] = $village_info['village_name'];

        //收款人
        if ($cashier_info['role_id']) {
            $role_info = D('House_admin')->where(array('id'=>$cashier_info['role_id']))->find();
            $cashier_info['payee'] = $role_info['realname'] ? $role_info['realname'] : $role_info['account'];
        }else{
            $cashier_info['payee'] =  $village_info['account'];
        }

        //业主信息
        $user_info = D('house_village_user_bind')->where(array('pigcms_id'=>$cashier_info['pigcms_id']))->find();
        //楼层信息
        $floor_where['floor_id'] = $user_info['floor_id'];
        $floor_where['status'] = 1;
        $database_house_village_floor = D('House_village_floor');
        $floor_info = $database_house_village_floor->house_village_floor_detail($floor_where);

        $floor_info = $floor_info['detail'];
        $cashier_info['username'] = $user_info['name'];
        $cashier_info['payer'] = $user_info['name'];
        $cashier_info['phone'] = $user_info['phone'];
        $cashier_info['usernum'] = $user_info['usernum'];
        // $cashier_info['room_num'] = $floor_info['floor_layer'] .'-'.$floor_info['floor_name'].'-' .$user_info['layer_num'].'-'.$user_info['room_addrss'];
        $cashier_info['room_num'] = $user_info['address'];
        $cashier_info['time'] = date('Y-m-d H:i:s' ,$cashier_info['time']);
        $cashier_info['pay_time'] = date('Y-m-d H:i:s' ,$cashier_info['pay_time']);
        $cashier_info['print_time'] = date('Y-m-d H:i:s' ,time());

        //收款方式
        $pay_type = D('house_village_pay_type')->where(array('id'=>$cashier_info['pay_type']))->find();
        $cashier_info['pay_type_name'] = $pay_type['name'];
        //查询详细订单
        $condition_table  = array(C('DB_PREFIX').'house_village_pay_order'=>'o',C('DB_PREFIX').'house_village_user_bind'=>'b');
        $condition_where = " `o`.`village_id` = `b`.`village_id`  AND `o`.`bind_id` = `b`.`pigcms_id`  AND `o`.`village_id` =".$column['village_id'];

        if($column['cashier_id']){
            $condition_where .= " AND `o`.`cashier_id`=".intval($column['cashier_id']);
        }
        
        $condition_field = '`b`.`name` AS `username` ,b.*,o.*';
        $order = ' `o`.`order_id` DESC';
       
        $p = new Page($count_order,$pageSize,'page');
        $order_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->select();
        if ($order_list) {
            foreach ($order_list as &$value) {
                //收款时间
                $value['pay_time'] = date('Y-m-d H:i:s' ,$value['pay_time']);
                //优惠
                if ($value["presented_property_month_num"]&&$value["diy_type"] == 0) {
                    $value['discount'] = $value["presented_property_month_num"].'个月';
                } elseif($value["diy_type"] == 1) {
                    $value['discount'] = $value["diy_content"];
                }else{
                    $value['discount'] = '';
                }
                //服务时间
                if ($value["order_type"] == 'custom_payment') {
                    $value['property_time_str'] = $value["property_time_str"];
                }
                //缴费周期
                $value['service_cycle'] = '';
                if ($value["property_month_num"]) { //物业服务周期
                    $value['service_cycle'] = $value["property_month_num"].'个月';
                }elseif($value["order_type"] == 'custom_payment'){ //自定义缴费周期
                    $value['service_cycle'] = $value["payment_paid_cycle"].'/周期';
                }

                $value['real_money'] = $value['money'];
                //楼层信息
                $floor_where['floor_id'] = $value['floor_id'];
                $floor_where['status'] = 1;
                // $database_house_village_floor = D('House_village_floor');
                // $floor_info = $database_house_village_floor->house_village_floor_detail($floor_where);
                // $floor_info = $floor_info['detail'];
                //房号
                // $value['room_num'] = $floor_info['floor_layer'] .'-'.$floor_info['floor_name'].'-' . $value['layer_num'].'-'.$value['room_addrss'];
                $value['room_num'] = $value['address'];
                //收费标准
                switch ($value["order_type"]) {
                    case 'property':
                        if(($floor_info['property_fee'] == '0.00') || (!isset($floor_info['property_fee']))){
                            // $value["price"] = $village_info['property_price'].'元/平方米/月';
                            $value["price"] = $village_info['property_price'];
                        }else{
                            $value["price"] = $floor_info['property_fee'];
                        }
                        // 开始时间，结束时间
                        $paylist = D('House_village_property_paylist')->where(array('order_id'=>$value['order_id']))->find();
                        $value["start_time"] = date('Y-m-d' ,$paylist['start_time']);
                        $value["end_time"] = date('Y-m-d' ,$paylist['end_time']);
                        break;
                    case 'water':
                        if(($floor_info['water_fee'] == '0.00') || (!isset($floor_info['water_fee']))){
                           // $value["price"] = $village_info['water_price'].'元/立方米';
                           $value["price"] = $village_info['water_price'];
                        }else{
                            $value["price"] = $floor_info['water_fee'];
                        }
                        $value["number"] = round($value["money"]/$value["price"],2);
                        break;
                    case 'electric':
                        if(($floor_info['electric_fee'] == '0.00') || (!isset($floor_info['electric_fee']))){
                           // $value["price"] = $village_info['electric_price'].'元/千瓦时(度)';
                           $value["price"] = $village_info['electric_price'];
                        }else{
                            $value["price"] = $floor_info['electric_fee'];
                        }
                        $value["number"] = round($value["money"]/$value["price"],2);
                        break;
                    case 'gas':
                        if(($floor_info['gas_fee'] == '0.00') || (!isset($floor_info['gas_fee']))){
                           // $value["price"] = $village_info['gas_price'].'元/立方米';
                           $value["price"] = $village_info['gas_price'];
                        }else{
                            $value["price"] = $floor_info['gas_fee'];
                        }
                        $value["number"] = round($value["money"]/$value["price"],2);
                        break;
                    case 'park':
                        if(($floor_info['park_fee'] == '0.00') || (!isset($floor_info['park_fee']))){
                           // $value["price"] = $village_info['park_price'].'元/月';
                           $value["price"] = $village_info['park_price'];
                        }else{
                            $value["price"] = $floor_info['park_fee'];
                        }
                        break;  
                    case 'custom_payment': // 自定义缴费 
                        $payment_standard_bind = D('House_village_payment_standard_bind')->where(array('bind_id'=>$value['payment_bind_id']))->find();
                        $payment_standard = D('House_village_payment_standard')->where(array('standard_id'=>$payment_standard_bind['standard_id']))->find();
                        // 周期类型
                        $cycle_type = '';
                        switch ($payment_standard['cycle_type']) {
                            case 'M':
                                $cycle_type = $payment_standard['pay_cycle'].'月/周期';
                                break;
                            case 'Y':
                                $cycle_type = $payment_standard['pay_cycle'].'年/周期';
                                break;
                            case 'D':
                                $cycle_type = $payment_standard['pay_cycle'].'天/周期';
                                break;
                        }
                        $value["service_cycle"] = $value['payment_paid_cycle'].'（'.$cycle_type.'）'; //支付周期
                        $value["price"] = $payment_standard['pay_money'];
                        $value["number"] = $payment_standard_bind['metering_mode_val']; //自定义数量
                        // 开始时间，结束时间
                        $paylist = D('House_village_property_paylist')->where(array('order_id'=>$value['order_id']))->find();
                        $value["start_time"] = date('Y-m-d' ,$paylist['start_time']);
                        $value["end_time"] = date('Y-m-d' ,$paylist['end_time']);
                        break;    
                    case 'custom': // 自定义缴费 
                        $value["price"] = $value["money"];
                        $value["number"] = 1;
                        break;
                }
            }
        }
        // $total = D('')->field(' SUM(`o`.`money` ) AS totalMoney ')->table($condition_table)->where($condition_where)->find();
        $cashier_info['totalMoney'] = '￥'.$cashier_info['money'].'（人民币大写：'.cny($cashier_info['money']).'）';
        $cashier_info['case'] = cny($cashier_info['money']);
        $cashier_info['order_list'] = $order_list;

        return $cashier_info;
    }

	public function get_one($cashier_id){
		if(!$cashier_id){
			return false;
		}
		return $this->where(array('cashier_id'=>$cashier_id))->find();
	}
    
    // 收银台收款
    public function cashier_pay($cashier_id ,$remarks = ''){
        if (!$cashier_id) {
            return array('error_code'=>1,'msg'=>'参数传递出错！');
        }

        $cashier_order = $this->get_one($cashier_id);
        if($cashier_order['paid'] > 0){
            return array('error_code'=>1,'msg'=>'该订单已经支付！');
        }

        $res = $this->where(array('cashier_id'=>$cashier_id))->data(array('paid'=>1,'pay_time'=>time()))->save();
        if (!$res) {
            return array('error_code'=>1,'msg'=>'支付失败！');
        }

        //查询订单
        $order_list = D('House_village_pay_order')->where(array('cashier_id'=>$cashier_id))->select();
        if (!$order_list) {
            return array('error_code'=>1,'msg'=>'没有子订单！');
        }
        // 跟新订单状态
        $data['paid'] = 1;
        $data['pay_time'] = time();
        $data['pay_type'] = ($cashier_order['pay_type']==0) ? 0 : 1;
        if ($remarks) {
            $data['remarks'] = $remarks;
        }
        foreach ($order_list as $key => $value) {
            D('House_village_pay_order')->where(array('order_id'=>$value['order_id']))->data($data)->save();
            $now_order = D('House_village_pay_order')->get_one($value['order_id']);

            // 欠费更新
            $bind_field = $now_order['order_type'].'_price';
            if(!empty($bind_field)){
                $now_user_info = D('House_village_user_bind')->get_one($now_order['village_id'],$now_order['bind_id'],'pigcms_id');

                $data_bind['pigcms_id'] = $now_user_info['pigcms_id'];

                if($now_user_info[$bind_field] - $now_order['money'] >= 0){
                    $data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'];
                }else{
                    $data_bind[$bind_field] = 0;
                }
                $data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'] >= 0 ? $now_user_info[$bind_field] - $now_order['money'] : 0;

                D('House_village_user_bind')->data($data_bind)->save();

            }

            $database_house_village_property_paylist = D('House_village_property_paylist');
            $paylist_data['bind_id'] = $now_order['bind_id'];
            $paylist_data['uid'] = $now_order['uid'];
            $paylist_data['village_id'] = $now_order['village_id'];
            $paylist_data['property_month_num'] = $now_order['property_month_num'] + 0;
            $paylist_data['presented_property_month_num'] = $now_order['presented_property_month_num'] + 0;
            $paylist_data['house_size'] = $now_order['house_size'];
            $paylist_data['property_fee'] = $now_order['property_fee'];
            $paylist_data['floor_type_name'] = $now_order['floor_type_name'];

            $now_user_info = D('House_village_user_bind')->get_one_by_bindId($now_order['bind_id']);
            $now_pay_info = $database_house_village_property_paylist->where(array('bind_id'=>$now_order['bind_id']))->order('add_time desc')->find();

            
            if($now_user_info['property_endtime']){
                $paylist_data['start_time'] = $now_user_info['property_endtime'];
                $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['property_endtime']);
            }else{
                if($now_user_info['add_time'] > 0){
                    $paylist_data['start_time'] = $now_user_info['add_time'] ;
                    $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['add_time']);
                }else{
                    $paylist_data['start_time'] = time();
                    $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", time());
                }

            }
            
            $paylist_data['end_time'] = strtotime(date('Y-m-d 23:59:59',$paylist_data['end_time']));

            $paylist_data['add_time'] = time();
            $paylist_data['order_id'] = $value['order_id'];

            //同步物业到期时间 
            $where['uid'] = $now_order['uid'];
            $where['village_id'] = $now_order['village_id'];
            $where['pigcms_id'] = $now_order['bind_id'];
            M('House_village_user_bind')->where($where)->save(array('property_endtime'=>$paylist_data['end_time']));

            $database_house_village_property_paylist->data($paylist_data)->add();


            //修改绑定缴费项目
            if($now_order['order_type'] == 'custom_payment'){
                M('House_village_payment_standard_bind')->where(array('bind_id'=>$now_order['payment_bind_id']))->setInc('paid_cycle',$now_order['payment_paid_cycle']);
            }

            // 小票打印start
            // $printHaddle = new PrintVillage();
            // $printHaddle->printit($order_id);
            // 小票打印end
        }
        return array('error_code'=>0,'msg'=>'提交成功！');
        
    }

    public function get_pay_order($order_id){
        $now_order = $this->get_one($order_id);
        $order_info = array(
                'pay_offline'           => false,         //线下支付
                'pay_merchant_balance'  => false,         //商家余额
                'pay_merchant_coupon'   => false,         //商家优惠券
                'pay_merchant_ownpay'   => false,         //商家自有支付
                'pay_system_balance'    => true,          //平台余额
                'pay_system_coupon'     => false,         //平台优惠券
                'pay_system_score'      => true,          //平台积分抵现
                'order_info'            => $now_order,    //平台积分抵现
        );
        if($now_order['order_type']=='property'){
            $order_info['pay_system_score']=true;
        }
        $now_village = D('House_village')->get_one($now_order['village_id']);
        if(C('config.open_sub_mchid') && $now_village['open_sub_mchid'] && $now_village['sub_mch_id']>0 ){
            $order_info['pay_merchant_ownpay'] = true;
        }

        if(C('config.open_sub_mchid') && $now_village['open_sub_mchid'] && $now_village['sub_mch_id']>0 && $now_village['sub_mch_discount']==0 ){
            $order_info['pay_system_score'] = false;
        }

        if(C('config.open_sub_mchid') && $now_village['open_sub_mchid'] && $now_village['sub_mch_id']>0 && $now_village['sub_mch_sys_pay']==0 ){
            $order_info['pay_system_balance'] = false;
        }
        return $order_info;
    }
	
    public function after_pay($order_id,$plat_order_info){
        $cashier_order = $this->get_one($order_id);
        $date_order['pay_time'] = $plat_order_info['pay_time'];
        $date_order['paid'] = 1;
        $this->where(array('cashier_id'=>$order_id))->save($date_order);

        $tmp_order = $cashier_order;
        $tmp_order['order_id'] = $cashier_order['cashier_id'];
        $tmp_order['is_own'] = $plat_order_info['is_own'];
        $tmp_order['payment_money'] = $plat_order_info['pay_money'];
        $tmp_order['score_deducte'] = $plat_order_info['system_score_money'];
        $tmp_order['balance_pay'] = $plat_order_info['system_balance'];   
        $tmp_order['order_type'] = 'village_pay_cashier';
        $tmp_order['desc'] = '社区收银台缴费';
        if($plat_order_info['is_own']==0)
            $plat_order_info['is_own']+=4;

        //社区对账
        $res = D('SystemBill')->bill_method($plat_order_info['is_own'],$tmp_order);
        if(!$res['error_code']){
            $this->where(array('cashier_id'=>$order_id))->setField('is_pay_bill',2);
        }

        //查询子订单
        $order_list = D('House_village_pay_order')->where(array('cashier_id'=>$order_id))->select();
        if (!$order_list) {
            return array('error_code'=>1,'msg'=>'没有子订单！');
        }
        // 跟新订单状态

        foreach ($order_list as $key => $now_order) {
            $date_order['pay_time'] = $plat_order_info['pay_time'];
            $date_order['paid'] = 1;
            $date_order['pay_type'] = ($cashier_order['pay_type']==0) ? 0 : 1;
            $date_order['score_used_count'] = $plat_order_info['system_score'];
            $date_order['score_deducte'] = $plat_order_info['system_score_money'];
            // if($now_order['score_can_get']>0){
            //     D('User')->add_score($plat_order_info['uid'],$now_order['score_can_get'],'交物业费获得积分');
            // }

            D('House_village_pay_order')->where(array('cashier_id'=>$order_id))->save($date_order);


            switch($now_order['order_type']){
                case 'property':
                    $now_village = D('House_village')->get_one($now_order['village_id']);
                    //增加积分
                    if($now_order['score_can_get']>0){
                        D('User')->add_score($plat_order_info['uid'],$now_order['score_can_get'],'交物业费获得积分');
                    }

                    $bind_field = 'property_price';
                    $now_user_info = D('House_village_user_bind')->get_one_by_bindId($now_order['bind_id']);
                    $House_village_property_paylist = D('House_village_property_paylist');
                    $paylist_data['bind_id'] = $now_order['bind_id'];
                    $paylist_data['uid'] = $now_order['uid'];
                    $paylist_data['village_id'] = $now_order['village_id'];
                    $paylist_data['property_month_num'] = $now_order['property_month_num'];
                    $paylist_data['presented_property_month_num'] = $now_order['presented_property_month_num'];
                    $paylist_data['house_size'] = $now_order['house_size'];
                    $paylist_data['property_fee'] = $now_order['property_fee'];
                    $paylist_data['floor_type_name'] = $now_order['floor_type_name'];

                    // $now_pay_info = $House_village_property_paylist->where(array('bind_id'=>$now_order['bind_id']))->order('add_time desc')->find();


                    if($now_user_info['property_endtime']){
                        $paylist_data['start_time'] = $now_user_info['property_endtime'];
                        $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['property_endtime']);
                    }else{
                        if($now_user_info['add_time'] > 0){
                            $paylist_data['start_time'] = $now_user_info['add_time'] ;
                            $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['add_time']);
                        }else{
                            $paylist_data['start_time'] = time();
                            $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", time());
                        }

                    }
                    
                    $paylist_data['add_time'] = time();
                    $paylist_data['order_id'] = $now_order['order_id'];

                    $paylist_data['end_time'] = strtotime(date('Y-m-d 23:59:59',$paylist_data['end_time']));
                    
                    $where['uid'] = $now_order['uid'];
                    $where['village_id'] = $now_order['village_id'];
                    $where['pigcms_id'] = $now_order['bind_id']; 
                    M('House_village_user_bind')->where($where)->save(array('property_endtime'=>$paylist_data['end_time']));
                    $House_village_property_paylist->data($paylist_data)->add();
                    $tmp_order['desc'] = '用户交物业费';
                    break;
                case 'water':
                    $bind_field = 'water_price';
                    $tmp_order['desc'] = '用户交水费';
                    break;
                case 'electric':
                    $bind_field = 'electric_price';
                    $tmp_order['desc'] = '用户交电费';
                    break;
                case 'gas':
                    $bind_field = 'gas_price';
                    $tmp_order['desc'] = '用户交燃气费';
                    break;
                case 'park':
                    $bind_field = 'park_price';
                    $tmp_order['desc'] = '用户交车位费';
                    break;
                default:
                    $bind_field = '';
                    $tmp_order['desc']= $plat_order_info['order_name'];
            }
            if(!empty($bind_field)){
                $now_user_info = D('House_village_user_bind')->get_one($now_order['village_id'],$now_order['bind_id'],'pigcms_id');
                $data_bind['pigcms_id'] = $now_user_info['pigcms_id'];
                if($now_user_info[$bind_field] - $now_order['money'] >= 0){
                    $data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'];
                }else{
                    $data_bind[$bind_field] = 0;
                }
                $data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'] >= 0 ? $now_user_info[$bind_field] - $now_order['money'] : 0;
                D('House_village_user_bind')->data($data_bind)->save();

            }

            //修改绑定缴费项目
            if($now_order['order_type'] == 'custom_payment'){
                M('House_village_payment_standard_bind')->where(array('bind_id'=>$now_order['payment_bind_id']))->setInc('paid_cycle',$now_order['payment_paid_cycle']);
            }
            
            // 对账
            M('House_village_pay_order')->where(array('order_id' => $now_order['order_id']))->setField('is_pay_bill', 2);

        }

        $now_user = D('User')->get_user($cashier_order['uid']);
        if(!empty($now_user['openid'])){
            $href = C('config.site_url').'/wap.php?g=Wap&c=House&a=village_my_paylists&village_id='.$cashier_order['village_id'];
            $model = new templateNews($this->config['wechat_appid'],$this->config['wechat_appsecret']);
            $model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' =>  ' 缴费成功提醒', 'keynote1' =>'社区收银台缴费', 'keynote2' =>'物业号 '. $now_user_info['usernum'], 'remark' => '缴费时间：'.date('Y年n月j日 H:i',$cashier_order['time']).'\n'.'缴费金额：￥'.$cashier_order['money']));
        }

        $village_info = D('House_village')->where(array('village_id'=>$cashier_order['village_id']))->find();
        $village_bind = D('House_village_user_bind')->where(array('pigcms_id'=>$cashier_order['pigcms_id']))->find();
        if($village_info['openid']){
            $href = "";
            $model = new templateNews($this->config['wechat_appid'],$this->config['wechat_appsecret']);
            $model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' =>  ' 业主 '.$village_bind['name'].'( '.$village_bind['address'].' )缴费成功提醒', 'keynote1' =>$village_info['property_name'], 'keynote2' =>$village_bind['name']."( ".$village_bind['phone']." )", 'remark' => '社区收银台缴费\n缴费时间：'.date('Y年n月j日 H:i',$cashier_order['time']).'\n'.'缴费金额：￥'.$cashier_order['money']));
        }

    }

    public function get_order_url($order_id,$is_mobile){
        $now_order = $this->get_one($order_id);
        M('House_village')->where(array('village_id'=>$now_order['village_id']))->setInc('payment_reminder');
        M('House_village_payment_standard_bind')->where(array('bind_id'=>$now_order['payment_bind_id']))->setInc('paid_cycle',$now_order['payment_paid_cycle']);
        
        // 小票打印start
        $printHaddle = new PrintVillage();
        $printHaddle->printit($order_id);
        // 小票打印end

        return '/wap.php?g=Wap&c=House&a=village_my&village_id='.$now_order['village_id'];
    }
}