<?php
/*
 * 社区2.0 收银台
 *
 */
class H_cashierAction extends BaseAction{
    public $pay_list_type = array(
            'property'=>'物业费',
            'water'=>'水费',
            'electric'=>'电费',
            'gas'=>'燃气费',
            'park'=>'停车费',
            'custom'=>'临时缴费',
            'custom_payment'=>'自定义缴费',
    );
	
	//	收银台 个人账单
	public function personal_order(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(66, $this->power)){    		
			$this->returnCode('20090103');
    	}
  		$database_house_village_pay_order = D('House_village_pay_order');

        $bind_id = I('pigcms_id');
		$village_id =  I('village_id');

	    $where['village_id'] = $village_id;
        $where['order_type'] = "'custom','custom_payment','property'";
        $where['paid'] = 0; //未付款
        $where['cashier_id'] = 0;
        $where['bind_id'] = $bind_id;
        $pay_list_order = D('House_village_pay_order')->get_limit_list_page($where,9999);

        $totalmoney = 0;
        $order_list = array();
        if($pay_list_order){
            foreach ($pay_list_order['order_list'] as $k => $v){
                $totalmoney += $v['money'];                              //本页的总额
                $order_list[$k]['order_id'] = $v['order_id'];
                $order_list[$k]['type'] = $v['order_type'];
                $order_list[$k]['name'] = $v['order_name'];
                $order_list[$k]['money'] = $v['money'];
                $order_list[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
            }
        }
        // $pay_list_order['total'] = $totalmoney;
        $list = array();
        $list['pay_list_order'] = $order_list;

        //小区信息
        $now_village = D('House_village')->get_one($village_id);
        //当前业主信息
        $database_house_village_user_bind = D('House_village_user_bind');
        $now_user_info = $database_house_village_user_bind->get_one_by_bindId($bind_id);

        $floor_info = D('House_village_floor')->where(array('floor_id'=>$now_user_info['floor_id']))->find();


        $now_user_info['address'] = $floor_info['floor_name'].'-'.$floor_info['floor_layer'] . '-' .$now_user_info['layer_num'] . '-' .$now_user_info['room_addrss'];


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
                    // 'url' => $now_village['water_price'] ? U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'water')) : U('Lifeservice/query',array('type'=>'water')),
                    'money'=>floatval($now_user_info['water_price']),
            );
        }
        // 电费
        if($now_village['electric_price']&&$now_user_info['electric_price']){
            $totalmoney += floatval($now_user_info['electric_price']);
            $pay_list[] = array(
                    'type' => 'electric',
                    'name' => $this->pay_list_type['electric'],
                    // 'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'electric')),
                    'money'=>floatval($now_user_info['electric_price']),
            );
        }
        // 燃气费
        if($now_village['gas_price']&&$now_user_info['gas_price']){
            $totalmoney += floatval($now_user_info['gas_price']);
            $pay_list[] = array(
                    'type' => 'gas',
                    'name' => $this->pay_list_type['gas'],
                    // 'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'gas')),
                    'money'=>floatval($now_user_info['gas_price']),
            );
        }
        // 停车费
        if($now_village['park_price']&&$now_user_info['park_price']){
            $totalmoney += floatval($now_user_info['park_price']);
            $pay_list[] = array(
                    'type' => 'park',
                    'name' => $this->pay_list_type['park'],
                    // 'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'park')),
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
        $list['pay_list'] = $pay_list;
        $list['user_info'] = $now_user_info;
        $list['totalmoney'] = $totalmoney;

		// if(!$totalmoney){
		// 	$this->returnCode('20090153');
		// }else{
			$this->returnCode(0,$list);
		// }
	} 

    //收银台 未缴账单单个费用详情
    public function personal_order_detail(){
        $this->is_existence();
        //验证权限
        if(!in_array(66, $this->power)){            
            $this->returnCode('20090103');
        }

        $village_id =  I('village_id');
        $order_id =  I('order_id');

        if (!$order_id) {
            $this->returnCode('20090159');
        }

        $pay_order = D('House_village_pay_order')->get_one($order_id);
        if (!$pay_order) {
            $this->returnCode('20090160');
        }

        $pay_order['time'] = date('Y-m-d H:i:s',$pay_order['time']);
      
        //当前业主信息
        $database_house_village_user_bind = D('House_village_user_bind');
        $now_user_info = $database_house_village_user_bind->get_one_by_bindId($pay_order['bind_id']);

        $pay_order['user_info'] = $now_user_info;
        $this->returnCode(0,$pay_order);
        
    }

    // 添加缴费订单
    public function owner_order_add(){
        $this->is_existence();
        //收银台-查看 权限
        if (!in_array(67,  $this->power)) {
            $this->returnCode('20090103');
        }

        $village_id =  I('village_id');
        $type =  I('type'); // 缴费类型
        $usernum =  I('usernum'); // 物业编号
        $remarks =  I('remarks'); // 备注

        $bind_where['usernum'] = $usernum;
        $database_house_village_user_bind = D('House_village_user_bind');
        $now_bind_info         = $database_house_village_user_bind->where($bind_where)->find();
        $now_village = D('House_village')->get_one($now_bind_info['village_id']);

        if (!$now_bind_info) {
            $this->returnCode('20090010');
        }
        if($type=='property') {
            $property_id =  I('property_id'); // 物业缴费周期

            $database_house_village_property  = D('House_village_property');
            $database_house_village_floor     = D('House_village_floor');

            //单元信息
            $now_floor_info = $database_house_village_floor->get_floor_info($now_bind_info['floor_id']);

            //物业缴费周期
            $property_where['id'] = $property_id + 0;
            $now_property_info    = $database_house_village_property->house_village_property_detail($property_where);
            $now_property_info    = $now_property_info['detail'];

            if (!$now_property_info) {//物业缴费周期不存在
                $this->returnCode('20090180');
            }

            $data['order_name']         = '缴纳物业费';
            $data['order_type']         = 'property';
            $data['village_id']         = $village_id;
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
            $data['remarks'] = $remarks;
            $order_id = D("House_village_pay_order")->add($data);
            if ($order_id) {
                $this->returnCode(0);
            } else {
                $this->returnCode('20090166');
            }
        }else{

            switch($type){
                case 'water':
                    if(empty($now_village['water_price'])) $this->returnCode('20090172');
                    $pay_money = $now_bind_info['water_price'];
                    $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_water` AS `use`,`water_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                    foreach($order_list as $key=>$value){
                        $order_list[$key]['desc'] = '用水 '.floatval($value['use']).' 立方米，总费用 '.floatval($value['price']).' 元';
                    }
                    $data_order['order_name'] = '水费';
                    break;
                case 'electric':
                    if(empty($now_village['electric_price'])) $this->returnCode('20090173'); //当前小区不支持缴纳电费
                    $pay_money = $now_bind_info['electric_price'];
                    $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_electric` AS `use`,`electric_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                    foreach($order_list as $key=>$value){
                        $order_list[$key]['desc'] = '用电 '.floatval($value['use']).' 千瓦时(度)，总费用 '.floatval($value['price']).' 元';
                    }
                    $data_order['order_name'] = '电费';
                    break;
                case 'gas':
                    if(empty($now_village['gas_price'])) $this->returnCode('20090174');//当前小区不支持缴纳燃气费
                    $pay_money = $now_bind_info['gas_price'];
                    $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_gas` AS `use`,`gas_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                    foreach($order_list as $key=>$value){
                        $order_list[$key]['desc'] = '使用燃气 '.floatval($value['use']).' 立方米，总费用 '.floatval($value['price']).' 元';
                    }
                    $data_order['order_name'] = '燃气费';
                    break;
                case 'park':
                    if(empty($now_village['park_price'])) $this->returnCode('20090175');//当前小区不支持缴纳停车费
                    $pay_money = $now_bind_info['park_price'];
                    $order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`park_price` AS `price`')->where(array('usernum'=>$now_bind_info['usernum']))->order('`pigcms_id` DESC')->select();
                    foreach($order_list as $key=>$value){
                        $order_list[$key]['desc'] = '停车费 '.floatval($value['price']).' 元';
                    }
                    $data_order['order_name'] = '停车费';
                    break;
                case 'custom_payment':
                    $pay_money =  I('payment_price'); // 自定义缴费项金额
                    $order_name =  I('payment_name'); // 自定义缴费项名称
                    $payment_paid_cycle =  I('payment_paid_cycle'); // 自定义缴费项周期数
                    $payment_bind_id =  I('payment_bind_id'); //  自定义缴费项id

                    $data_order['order_name'] = $order_name;
                    $data_order['payment_paid_cycle'] = $payment_paid_cycle;
                    $data_order['payment_bind_id'] = $payment_bind_id;
                    break;
                case 'custom':
                    $pay_money =  I('custom_price'); // 自定义缴费金额
                    $custom_remark =  I('custom_remark'); // 自定义缴费名称
                    $data_order['order_name'] = '自定义缴费【'.$custom_remark.'】';
                    break;
            }
     
            $data_order['money'] = $pay_money ;
            $data_order['uid'] = $now_bind_info['uid'];
            $data_order['bind_id'] = $now_bind_info['pigcms_id'];
            $data_order['village_id'] = $now_village['village_id'];
            $data_order['time'] = $_SERVER['REQUEST_TIME'];
            $data_order['paid'] = 0;
            $data_order['order_type'] = $type;
            $data_order['remarks'] = $remarks;

            if($order_id = D('House_village_pay_order')->data($data_order)->add()){
                $this->returnCode(0);
            } else {
                $this->returnCode('20090166');
            }
        }
    }

    //缴费优惠列表
    public function proerty_list(){
        $this->is_existence();

        $village_id =  I('village_id');
        $where['village_id'] = $village_id;
        $where['status'] = 1;


        $database_house_village_property = D('House_village_property');
        $list = $database_house_village_property->house_village_proerty_page_list($where , true , 'property_month_num desc' , 99999);
        if ($list['list']['list']) {
            $this->returnCode(0,$list['list']['list']);
        }else{
            $this->returnCode('20090178');
        }
    }


	//个人历史账单
    public function personal_history_order(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(66, $this->power)){    		
			$this->returnCode('20090103');
    	}

        $pigcms_id = I('pigcms_id');
		$village_id =  I('village_id');

        // 历史缴费 已缴
        $where = array(
            'pigcms_id' => $pigcms_id,
            'village_id' => $village_id,
            'paid' => 1,
        );

		$page =  I('page',1);
		$pageSize =  I('pageSize',10);

        //线下支付方式
        $pay_type_list = D('House_village_pay_type')->where(array('village_id'=>$village_id))->select();

        $paid_list = $this->House_village_pay_cashier_order_page_list($where,$page,$pageSize);
        $total = 0;
        if($paid_list){
            foreach ($paid_list['list'] as &$v){
                $total += $v['money'];                              //本页的总额
                if ($pay_type_list) {
                    foreach ($pay_type_list as $key => $value) {
                        if ($v['pay_type'] == $value['id']) {
                            $v['pay_type_name'] = $value['name'];
                        }
                    }
                }
                $v['pay_time'] = date('Y-m-d H:i:s',$v['pay_time']);
                
            }
        }
        $paid_list['totalmoney'] = $total;

        //当前业主信息
        $database_house_village_user_bind = D('House_village_user_bind');
        $now_user_info = $database_house_village_user_bind->get_one_by_bindId($pigcms_id);

        $list['user_info'] = $now_user_info;
        $list['paid_list'] = $paid_list;
		$this->returnCode(0,$list);
    }

	//	账单列表
	public function House_village_pay_cashier_order_page_list($where , $page = 1,$pageSize = 10){
        if(!$where){
            return false;
        }

        $condition = 'o.pigcms_id=b.pigcms_id AND b.village_id='.$where['village_id'];
        if ($where['pigcms_id']) {
            $condition .= ' AND o.pigcms_id='.$where['pigcms_id'];
        }
        if ($where['paid']) {
            $condition .= ' AND o.paid='.$where['paid'];
        }

        if ($where['search']) {
            $condition .= ' AND (b.name like "%'.$where['search'].'%" OR b.phone like "%'.$where['search'].'%")';
        }
         
        $condition_table  = array(C('DB_PREFIX').'house_village_pay_cashier_order'=>'o',C('DB_PREFIX').'house_village_user_bind'=>'b');
        $field = 'b.name,b.address,b.phone,o.*';
        $order = ' `o`.`cashier_id` DESC';

        $count = D('')->table($condition_table)->where($condition)->count();
        $list = D('')->table($condition_table)->field($field)->where($condition)->order($order)->page($page,$pageSize)->select();

        $lists = array();
        $lists['totalPage'] = ceil($count/$pageSize);
        $lists['page'] = intval($page);
        $lists['list_count'] = count($list);
        $lists['list'] = $list ? $list : array();
        return $lists;
    }

    //已缴账单
    public function paid_list(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(83, $this->power)){    		
			$this->returnCode('20090103');
    	}

        $village_id =  I('village_id');
		$search =  trim(I('search'));

        // 历史缴费 已缴
        $where = array(
            'village_id' => $village_id,
            'paid' => 1,
        );

        if ($search) {
            $where['search'] = $search;
        }

		$page =  I('page',1);
		$pageSize =  I('pageSize',10);

        //线下支付方式
        $pay_type_list = D('House_village_pay_type')->where(array('village_id'=>$village_id))->select();

        $paid_list = $this->House_village_pay_cashier_order_page_list($where,$page,$pageSize);
        $total = 0;
        if($paid_list){
            foreach ($paid_list['list'] as &$v){
                $total += $v['money'];                              //本页的总额
                if ($pay_type_list) {
                    foreach ($pay_type_list as $key => $value) {
                        if ($v['pay_type'] == $value['id']) {
                            $v['pay_type_name'] = $value['name'];
                        }
                    }
                }
                $v['pay_time'] = date('Y-m-d H:i:s',$v['pay_time']);
                
            }
        }
        $paid_list['totalmoney'] = $total;

        // $paid_list = $paid_list['list'] ? $paid_list : array();
		$this->returnCode(0,$paid_list);
    }

    //	账单详情
	public function cashier_order_detail(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(66, $this->power) && !in_array(83, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$id = I('cashier_id');
		if(empty($id)){
			$this->returnCode('20090159');
		}

        //查询总订单
        $cashier_order = D('House_village_pay_cashier_order')->get_one($id);
        if (!$cashier_order) {
			$this->returnCode('20090160');
        }

        //当前业主信息
        $now_bind_info = D('House_village_user_bind')->get_one_by_bindId($cashier_order['pigcms_id']);

        $order_list = D('House_village_pay_order')->where(array('cashier_id' => $id))->select();
        $totalmoney = 0;
        foreach ($order_list as $key => $value) {
            $totalmoney += $value['money'];
        }
		$cashier_order['order_list'] = $order_list;
        $cashier_order['user_info'] = $now_bind_info;
        $cashier_order['real_money'] = $totalmoney;
        $cashier_order['money'] = $cashier_order['money'];
		if(!$cashier_order){
			$this->returnCode('20090138');
		}else{
			$this->returnCode(0,$cashier_order);
		}
	}

    //	未缴账单 暂时中支持水电燃气停车费
	public function unpaid_list(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(85, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$page =  I('page',1);
		$pageSize =  I('pageSize',10);
       
        // 历史缴费
        $where = array(
            'village_id' => $village_id,
        );

        $search =   trim(I('search'));
        if ($search) {
            $where['search'] = $search;
        }
  

        $list = D('House_village_user_bind')->get_cashier_unpaid_list($where,$page,$pageSize);
        foreach ($list['list'] as &$value) {
            $value['water_price'] = strval($value['water_price']);
            $value['electric_price'] = strval($value['electric_price']);
            $value['gas_price'] = strval($value['gas_price']);
            $value['park_price'] = strval($value['park_price']);
            $value['property_price'] = strval($value['property_price']);
            $value['cunstom_money'] = strval($value['cunstom_money']);
            $value['total'] = strval($value['total']);
        }
    
        $total = D('House_village_user_bind')->get_cashier_unpaid_list($where,0,0);


        $list['total_money'] = strval($total['total_money']);
        $list['water_money'] = strval($total['water_money']);
        $list['electric_money'] = strval($total['electric_money']);
        $list['gas_money'] = strval($total['gas_money']);
        $list['park_money'] = strval($total['park_money']);
        $list['cunstom_money'] = strval($total['cunstom_money']);
        $list['property_money'] = strval($total['property_money']);
		if($list['list']){
			$this->returnCode(0,$list);
		}else{
			$this->returnCode('20090161');
		}
	}

	//	账单列表
	public function House_village_user_bind_page_list($where , $page = 1,$pageSize = 10){

        $condition_where = '(water_price>0 OR electric_price>0 OR gas_price>0 OR park_price>0) AND village_id='.$where['village_id'];
        $order = '`pigcms_id` DESC';
 
        $count = D('House_village_user_bind')->where($condition_where)->count();
        $list = D('House_village_user_bind')->where($condition_where)->field(true)->order($order)->page($page,$pageSize)->select();

        $lists = array();
        $lists['totalPage'] = ceil($count/$pageSize);
        $lists['page'] = intval($page);
        $lists['list_count'] = count($list);
        $lists['list'] = isset($list)?$list:array();
        return $lists;
    }

	//	个人订单列表 删除订单
	public function order_del(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(69, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$id = I('order_id');
		if(empty($id)){
			$this->returnCode('20090159');
		}
	
        $database_house_village_pay_order = D('House_village_pay_order');
        $pay_order = $database_house_village_pay_order->get_one($id);

        if (!$pay_order) $this->returnCode('20090160');
        
        if ($pay_orde['paid'] > 0 ) {
        	$this->returnCode('20090162');
        } 
        
        $result = $database_house_village_pay_order->where(array('order_id'=>$id))->delete();
		if($result){
			$this->returnCode(0);
		}else{
			$this->returnCode('20090163');
		}
	}
	
	//	收款
	public function do_cashier(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(68, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
	
		$database_house_village_pay_order = D('House_village_pay_order');
        $pigcms_id = I('pigcms_id');
        $ids = I('orderids');
        $pay_type = I('pay_type',0); //支付方式
        $remarks = I('remarks');

        if (!$pigcms_id) {
			$this->returnCode('20090009');
        }

        if (!$ids) {
			$this->returnCode('20090165');
        }

        // 小区信息
        $now_village = D('House_village')->get_one($village_id);
        
        //当前业主信息
        $database_house_village_user_bind = D('House_village_user_bind');
        $now_bind_info = $database_house_village_user_bind->get_one_by_bindId($pigcms_id);
        if (!$now_bind_info) {
			$this->returnCode('20090010');
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
						$this->returnCode('20090171');
                    }
                    $order_info = $database_house_village_pay_order->get_one($aOrder[1]);
                    if ($order_info) {
	                    $totalmoney += $order_info['money'];
	                    $aOrderId[] = $aOrder[1];
                    }
                    break;
                
                case 'water':
                    if(empty($now_village['water_price'])) 
						$this->returnCode('20090172');
                    
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
                        'remarks' => $remarks,
                    );
                    break;
                case 'electric': 
                    if(empty($now_village['electric_price'])) 
						$this->returnCode('20090173');

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
                        'remarks' => $remarks,
                    );
                    break;
                case 'gas':
                    if(empty($now_village['gas_price'])) 
						$this->returnCode('20090174');
                    
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
                        'remarks' => $remarks,
                    );
                    break;
                case 'park': 
                    if(empty($now_village['park_price'])) 
						$this->returnCode('20090175');
                    
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
                        'remarks' => $remarks,
                    );
                    break;
            }
        }
        //生成总订单
        $database_house_village_pay_cashier_order = D('House_village_pay_cashier_order');
        $data = array(
            'pay_type' => $pay_type,
            // 'money' => $totalmoney,
            'money' => I('real_money'),
            'uid' => $now_bind_info['uid'],
            'pigcms_id' => $now_bind_info['pigcms_id'],
            'village_id' => $village_id,
            'time' => $_SERVER['REQUEST_TIME'],
            'paid' => 0,
            'remarks' => $remarks,
            'role_id' => $this->role_id,
        );
        $cashier_id = $database_house_village_pay_cashier_order->data($data)->add();
        if (!$cashier_id) {
			$this->returnCode('20090170');
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
        if ($_POST['is_online'] == 1 && I('real_money') > 0) {
            $qrcode_id = $cashier_id + 4200000000;
            $src = C('config.site_url')."/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id=".$qrcode_id;

            $this->returnCode(0, array('cashier_id'=>$cashier_id,'src'=>$src));
        }else{
            // 支付
            $result = $database_house_village_pay_cashier_order->cashier_pay($cashier_id);
			if($result['error_code']==1){
				$this->returnCode(1,array(),$result['msg']);
			}else{
				$this->returnCode(0, array('cashier_id'=>$cashier_id));
			}
        }

	}
}