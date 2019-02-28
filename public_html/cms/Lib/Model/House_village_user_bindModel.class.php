<?php

class House_village_user_bindModel extends Model
{
    /*通过手机号自动绑定业主*/
    public function bind($uid, $phone)
    {
		if(empty($uid) || empty($phone)){
			return false;
		}
        $this->data(array('uid' => $uid))->where(array('phone' => $phone))->save();
    }

    public function get_user_bind_list($uid, $village_id)
    {
	    $bind_list = $this->field(true)->where(array('uid' => $uid, 'village_id' => $village_id,'status'=>1))->order('`pigcms_id` DESC')->select();
	    //$bind_list = $this->field(true)->where(array('uid' => $uid, 'village_id' => $village_id, 'parent_id' => 0,'status'=>1))->order('`pigcms_id` DESC')->select();
        return $bind_list;
    }


    public function get_family_user_bind_list($uid, $village_id)
    {
        $bind_list = $this->where(array('uid' => $uid, 'village_id' => $village_id , 'status'=>1, 'parent_id' => array('neq',0)))->order('`pigcms_id` DESC')->select();

        foreach($bind_list as &$val){
            $val['address'] = $this->where(array('pigcms_id'=>$val['parent_id']))->getField('address');
        }

        return $bind_list;
    }

    /*得到小区下所有的业主列表*/
    public function get_limit_list_page($village_id, $pageSize = 20, $condition_where = array(),$type='')
    {
        if (!$village_id) {
            return null;
        }
        $return = array();
        $condition_where['village_id'] = $village_id;
        if(!$type){
            // $condition_where['status'] = 1;
            $condition_where['parent_id'] = 0;
            $condition_where['type'] = array('neq',4);
        }else{
            // $condition_where['status'] = 1;
            $condition_where['type'] = array('in',array(0,1,2));
        }
        $count_user = $this->where($condition_where)->count();
        import('@.ORG.merchant_page');
        $p = new Page($count_user, $pageSize, 'page');
        $user_list = $this->field(true)->where($condition_where)->order('`pigcms_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        $database_house_village_pay_order = D('House_village_pay_order');
        foreach($user_list as $Key=>$user){
            if($user['floor_id']){
                $floor_type = D('House_village_floor')->where(array('floor_id'=>$user['floor_id']))->getField('floor_type');
                $user_list[$Key]['floor_type_name'] = D('House_village_floor_type')->where(array('id'=>$floor_type))->getField('name');
            }

            $property_month_time_arr = $database_house_village_pay_order->where(array('village_id'=>$village_id,'paid'=>1,'uid'=>$user['uid']))->field('(property_month_num+presented_property_month_num) as property_month_time')->select();
            $user_list[$Key]['openid'] = D('User')->where(array('uid'=>$user['uid']))->getField('openid');
            $property_month_time = 0;
            foreach($property_month_time_arr as $row){
                $property_month_time += $row['property_month_time'];
            }
            $user_list[$Key]['property_month_time'] = $user['add_time'] + $property_month_time * 30 * 24 * 3600;

            $condition['parent_id'] = $user['pigcms_id'];
            $bind_list = $this->where($condition)->select();
            if($bind_list){
                $user_list[$Key]['bind_list'] = $bind_list;
            }
        }


        if ($user_list) {
            $return['totalPage'] = ceil($count_user / $pageSize);
            $return['user_count'] = count($user_list);
            $return['pagebar'] = $p->show();
            $return['user_list'] = $user_list;
        }

        return $return;
    }

     /*得到小区下所有的业主列表*/
    public function get_limit_room_list_page($village_id, $pageSize = 20, $condition_where = array(),$type='')
    {
        if (!$village_id) {
            return null;
        }
        $return = array();
        $condition = '`f`.`village_id` = `b`.`village_id`  AND `f`.`floor_id` = `b`.`floor_id` AND b.village_id ='.$village_id;
        $condition_table  = array(C('DB_PREFIX').'house_village_floor'=>'f',C('DB_PREFIX').'house_village_user_bind'=>'b');

        // $condition_where['village_id'] = $village_id;
        if(!$type){//业主列表
            // $condition .= ' AND b.parent_id =0 AND b.type <> 4 AND b.status =1 ';
            $condition .= ' AND b.parent_id =0 AND b.type <> 4';
        }else{//所有住户列表
            // $condition .= ' AND b.status =1 AND b.type in (0,1,2)';
            $condition .= ' AND b.type in (0,1,2)';
        }
        if ($condition_where['other']) {
            $condition .= ' AND '.$condition_where['other'];
        }

        $field = 'b.*,f.floor_name,f.floor_layer';
        $order = ' `b`.`pigcms_id` DESC';

        $count_user = D('')->table($condition_table)->where($condition)->count();
        $p = new Page($count_user,$pageSize,'page');
        $user_list = D('')->field($field)->table($condition_table)->where($condition)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

        foreach($user_list as &$user){
            $user['address'] = $user['floor_name'].'-'.$user['floor_layer'] . '-' .$user['layer_num'] . '-' .$user['room_addrss'];
        }

        if ($user_list) {
            $return['totalPage'] = ceil($count_user / $pageSize);
            $return['user_count'] = count($user_list);
            $return['pagebar'] = $p->show();
            $return['user_list'] = $user_list;
        }

        return $return;
    }

    /*得到单个业主信息*/
    public function get_one($village_id, $value, $field = 'uid', $bind_uid = 0)
    {
        $condition_user['village_id'] = $village_id;
        $condition_user[$field] = $value;
        $now_user = $this->field(true)->where($condition_user)->find();
        // dump($this);
        if (!empty($now_user)) {
            $now_user['water_price'] = floatval($now_user['water_price']);
            $now_user['electric_price'] = floatval($now_user['electric_price']);
            $now_user['gas_price'] = floatval($now_user['gas_price']);
            $now_user['park_price'] = floatval($now_user['park_price']);
            $now_user['property_price'] = floatval($now_user['property_price']);
            if ($bind_uid) {
                $this->where(array('pigcms_id' => $now_user['pigcms_id']))->data(array('uid' => $bind_uid))->save();
            }
        }
        return $now_user;
    }

    /*得到单个业主信息*/
    public function get_one_by_bindId($pigcms_id)
    {
        $condition_user['pigcms_id'] = $pigcms_id;
        $now_user = $this->field(true)->where($condition_user)->find();
        if (!empty($now_user)) {
            $now_user['water_price'] = floatval($now_user['water_price']);
			$now_user['pigcms_id_find'] = $now_user['pigcms_id'];
            $now_user['electric_price'] = floatval($now_user['electric_price']);
            $now_user['gas_price'] = floatval($now_user['gas_price']);
            $now_user['park_price'] = floatval($now_user['park_price']);
            $now_user['property_price'] = floatval($now_user['property_price']);
            /*if ($bind_uid) {
                $this->where(array('pigcms_id' => $now_user['pigcms_id']))->data(array('uid' => $bind_uid))->save();
            }*/

            if($now_user['parent_id']){
                $address = $this->where(array('pigcms_id'=>$now_user['parent_id']))->getField('address');
                $now_user['address'] = $address;
            }

        }
        return $now_user;
    }

    /*得到小区下所有的业主列表(绑定微信的)*/
    public function get_limit_list_open($village_id, $pageSize = 5)
    {
        if (!$village_id) {
            return null;
        }

        $return = array();

        $condition_table = array(C('DB_PREFIX') . 'house_village_user_bind' => 'b', C('DB_PREFIX') . 'user' => 'u');
        $condition_where = " b.uid = u.uid AND u.openid !='' AND b.uid>0 AND b.village_id=" . $village_id;
        $condition_field = ' distinct(u.openid), b.uid,u.openid,u.nickname';
        // if($bigId !== 0){
        // $condition_where .= " AND b.pigcms_id<=".$bigId." AND b.pigcms_id>=".$smallId;
        // }
        $count_user = D('')->table($condition_table)->where($condition_where)->count('distinct(u.openid)');

        import('@.ORG.merchant_page');
        $p = new Page($count_user, $pageSize, 'page');
        $user_list = D('')->table($condition_table)->field($condition_field)->where($condition_where)->order('`b`.`pigcms_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        if ($user_list) {
            $return['totalPage'] = ceil($count_user / $pageSize);
            $return['user_count'] = count($user_list);
            $return['pagebar'] = $p->show();
            $return['user_list'] = $user_list;
        }

        return $return;
    }

    /*得到小区下所有欠费业主列表(绑定微信的)*/
    public function get_pay_list_open($village_id, $pageSize = 20, $bigId = 0, $smallId = 0)
    {
        if (!$village_id) {
            return null;
        }

        $return = array();

        $condition_table = array(C('DB_PREFIX') . 'house_village_user_paylist' => 'b', C('DB_PREFIX') . 'user' => 'u');
        $condition_where = " b.uid = u.uid 	AND  u.openid !='' AND b.uid>0 AND b.village_id=" . $village_id;
        $condition_field = ' distinct(u.openid), b.uid,u.openid,b.address ';
        if ($bigId !== 0) {
            $condition_where .= " AND b.pigcms_id<=" . $bigId . " AND b.pigcms_id>=" . $smallId;
        }
        $count_user = D('')->table($condition_table)->where($condition_where)->count('distinct(u.openid)');

        import('@.ORG.merchant_page');
        $p = new Page($count_user, $pageSize, 'page');
        $user_list = D('')->table($condition_table)->field($condition_field)->where($condition_where)->order('`b`.`pigcms_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        if ($user_list) {
            $return['totalPage'] = ceil($count_user / $pageSize);
            $return['user_count'] = count($user_list);
            $return['pagebar'] = $p->show();
            $return['user_list'] = $user_list;
        }

        return $return;
    }

    /*绑定家属*/
    public function house_village_my_bind_family_add($data)
    {
        if (!$data) {
            return array('status' => 0, 'msg' => '传递参数有误！');
        }

        $where['uid'] = $data['uid'];
        $where['phone'] = $data['phone'];
        $where['parent_id'] = $data['parent_id'];
        $count = $this->where($where)->count();
        if ($count > 0) {
            return array('status' => 0, 'msg' => '该手机号已经绑定');
        }

        $insert_id = $this->data($data)->add();
        if ($insert_id) {
            $sms_data['uid'] = $data['uid'];
            $sms_data['mobile'] = $data['phone'];
            $sms_data['sendto'] = 'user';
            $sms_data['type'] = 'bind_family';
            $sms_data['content'] = '手机号：' . $_SESSION['user']['phone'] . '已成功将您绑定为其家属！';
            //Sms::sendSms($sms_data);

            return array('status' => 1, 'msg' => '绑定成功');
        } else {
            return array('status' => 0, 'msg' => '绑定失败');
        }
    }

    /*家属列表*/
    public function house_village_my_bind_family_list($where)
    {
        if (!$where) {
            return false;
        }

        $field = array('pigcms_id', 'name', 'phone');
        $list = $this->where($where)->field($field)->select();
        if ($list) {
            return $list;
        } else {
            return false;
        }
    }


    //获取被绑定家属信息
    public function get_village_family_list($where)
    {
        if (!$where) {
            return false;
        }

        $info = $this->where($where)->select();
        print_r($info);
        exit;
    }


    //获取单个业主信息
    public function house_village_user_bind_detail($where, $field = true)
    {
        if (!$where) {
            return false;
        }

        $info = $this->field($field)->where($where)->find();

        if (!$info) {
            return array('status' => 0, 'info' => '没有查到相关业主');
        } else {
            return array('status' => 1, 'info' =>$info);
        }
    }
	
	//查询用户申请小区房间亲属/租客信息 - wangdong
	public function get_my_room_not_master($uid , $type , $room_str=""){
		
		$where = "`uid`=".$uid;
		if(!empty($room_str)) $where .= " AND vacancy_id not in ($room_str)"; //$condition['vacancy_id'] = array('not in' , $room_str);
		$where .= " AND ((type in ($type)) or (type=3 AND status in (0,2)))";
		
		$lists = $this->field(true)->where($where)->order('village_id DESC,floor_id ASC,parent_id ASC')->select();
		
		return $lists;
	}
	
	//业主下面的亲戚/租客列表 - wangdong
	public function get_my_room_user($pigcms_id){
	
		$condition['vacancy_id'] = $pigcms_id;
		$condition['status'] = array('in' , "1,2");
		$condition['type'] = array('in' , '1,2');
		$lists = $this->field(true)->where($condition)->select();
		return $lists;
		
	}
	
	//小程序判断用户是否绑定当前小区 - wandong
	public function wxapp_getSelect($where){
		
		$list = $this->field(true)->where($where)->select();
		return $list;
			
	}

    //获取所有家属绑定信息
    public function get_all_child_list($where, $fields = true ,$order = 'pigcms_id desc' , $pageSize = 20){
        if(!$where){
            return false;
        }
        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');

        $child_list = $this->where($where)->field($fields)->order($order)->limit($p->firstRow.','.$p->listRows)->select();      
        foreach($child_list as $Key=>$row){
            $row['add_time'] = date('Y-m-d H:i:s',$row['add_time']);
            //查询业主信息
            $parent_info = $this->where(array('pigcms_id'=>$row['parent_id']))->field('name,phone')->order($order)->find(); 
            if ($parent_info) {
                $row['parent_name'] = $parent_info && $parent_info['name'] ? $parent_info['name'] : '';
                $row['parent_phone'] = $parent_info && $parent_info['phone'] ? $parent_info['phone'] : '';
            } 
            $child_list[$Key] = $row;
        }
        $list['pageCount'] = ceil($count / $pageSize);
        $list['list'] = $child_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'result'=>$list);
        }else{
            return array('status'=>0,'result'=>$list);
        }
    }
	
    /**
     * [get_user_bind_info 获取绑定车位业主的信息]
     * @return [type] [description]
     */
	public function get_user_bind_info($field='',$join,$where){
        $data_list = D('House_village_user_bind')->alias('a')->field($field)->join($join)->where($where)->find();
        
        if($data_list){
            return $data_list;
        }else{
            return false;
        }
    }
	
    /**
     * [get_user_bind_select 查询绑定的业主信息]
     * @return [type] [description]
     */
    public function get_user_bind_select(){
        $result = D('House_village_user_bind')->field($field)->select();
        if($result !== false){
            return $result;
        }else{
            return false;
        }
    }
	
    /**
     * [user_bind_save 修改绑定信息]
     * @return [type] [description]
     */
    public function user_bind_save($arr){
        $result = D('House_village_user_bind')->save($arr);
        if($result !== false){
            return $result;
        }else{
            return false;
        }
    }

    /**
     * [get_user_bind_count 获取数量]
     * @param  [type] $where [description]
     * @param  string $field [description]
     * @return [type]        [description]
     */
    public function get_user_bind_count($where,$field=''){
        $count = D('House_village_user_bind')->field($field)->where($where)->count();
        return $count;
    }

    /**
     * [get_user_bind_sum 获取总和]
     * @param  string $field [description]
     * @return [type]        [description]
     */
    public function get_user_bind_sum($field='',$where=array()){
        $pay_count = $this->where($where)->sum($field);
        $pay_count = $pay_count ? $pay_count : 0;
        return $pay_count;
    }

    /*得到未缴列表*/
    public function get_cashier_unpaid_list($column,$page=0,$pageSize=20){ 
        if(!$column['village_id']){
            return null;
        }
        // 水电燃气停车费 物业费
        $condition = '(water_price>0 OR electric_price>0 OR gas_price>0 OR park_price>0 OR ((property_endtime <> "" OR property_endtime <> null) AND property_endtime<"'.time().'")) AND parent_id=0 AND village_id='.$column['village_id'];
        if ($column['pigcms_id']) {
            $condition .= ' AND pigcms_id in('.$column['pigcms_id'].')';
        }  

        if ($column['name']) {
            $condition .= ' AND name ="'.$column['name'].'"';
        }   

        if ($column['phone']) {
            $condition .= ' AND phone ="'.$column['phone'].'"';
        }       

        if ($column['search']) {
            $condition .= ' AND (name like "%'.$column['search'].'%" OR phone like "%'.$column['search'].'%")';
        }

        $list = $this->field('pigcms_id')->where($condition)->select();
        $ids = array();
        foreach ($list as $key => $value) {
            $ids[] = $value['pigcms_id'];
        }
        // $ids = array_values($list);
        // var_dump($ids);

        // 自定义缴费
        $condition = '`psb`.`pigcms_id` = `b`.`pigcms_id`  AND `psb`.`standard_id` = `ps`.`standard_id` AND `psb`.`cycle_sum` > `psb`.`paid_cycle` AND `psb`.`start_time` <"'.time().'" AND b.village_id ='.$column['village_id'];
        $condition_table  = array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',C('DB_PREFIX').'house_village_user_bind'=>'b',C('DB_PREFIX').'house_village_payment_standard'=>'ps');
        $field = '`psb`.*,`ps`.`cycle_type`,`ps`.`pay_cycle`,`b`.`pigcms_id`';

        if ($column['pigcms_id']) {
            $condition .= ' AND `b`.`pigcms_id` in('.$column['pigcms_id'].')';
        }
        if ($column['name']) {
            $condition .= ' AND `b`.name ="'.$column['name'].'"';
        }   

        if ($column['phone']) {
            $condition .= ' AND `b`.phone ="'.$column['phone'].'"';
        }

        if ($column['search']) {
            $condition .= ' AND (b.name like "%'.$column['search'].'%" OR b.phone like "%'.$column['search'].'%")';
        }

        $list1 = D('')->field($field)->table($condition_table)->where($condition)->select();
        // fdump(D('')->getLastSql(),'htm',true);
        
        $list1 = $list1 ? $list1 : array();

        // 车位自定义缴费
        $condition = '`bp`.`user_id` = `b`.`pigcms_id`  AND `bp`.`position_id` = `psb`.`position_id`  AND `psb`.`standard_id` = `ps`.`standard_id` AND `psb`.`cycle_sum` > `psb`.`paid_cycle` AND `psb`.`start_time` <"'.time().'" AND b.village_id ='.$column['village_id'];
        $condition_table  = array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',C('DB_PREFIX').'house_village_user_bind'=>'b',C('DB_PREFIX').'house_village_payment_standard'=>'ps',C('DB_PREFIX').'house_village_bind_position'=>'bp');
        $field = '`psb`.*,`ps`.`cycle_type`,`ps`.`pay_cycle`,`b`.`pigcms_id`';

        if ($column['pigcms_id']) {
            $condition .= ' AND `b`.`pigcms_id` in('.$column['pigcms_id'].')';
        }
        if ($column['name']) {
            $condition .= ' AND `b`.name ="'.$column['name'].'"';
        }   

        if ($column['phone']) {
            $condition .= ' AND `b`.phone ="'.$column['phone'].'"';
        }
        if ($column['search']) {
            $condition .= ' AND (b.name like "%'.$column['search'].'%" OR b.phone like "%'.$column['search'].'%")';
        }

        $list2 = D('')->field($field)->table($condition_table)->where($condition)->select();
        $list2 = $list2 ? $list2 : array();

        $custom_list = array_merge($list1,$list2);

        $custom_ids = array();
        if ($custom_list) {
            foreach ($custom_list as $key => $value) {
                switch ($value['cycle_type']) {
                    case 'Y': // 年
                        // 计算到期时间 = 开始时间 + 已缴时间（收费周期*已缴周期）
                        $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400;
                        break;
                    case 'M': //月
                        $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400*30;
                        break;
                    case 'D': // 日
                        $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400*365;
                        break;
                }
                if ($end_time<time()) {
                    $custom_ids[] = $value['pigcms_id'];;
                }
            }
        }

        //欠费业主id集
        $ids = array_merge($ids,$custom_ids);
        $condition = 'f.floor_id=b.floor_id AND b.pigcms_id in('.implode(',',$ids).') AND b.village_id='.$column['village_id'];

        if($column['is_bind_weixin'] == 1){
            $condition .= ' AND u.openid <> "" AND u.openid is not null';
        }elseif ($column['is_bind_weixin'] == 2) {
            $condition .= ' AND (u.openid = "" OR u.openid is null)';
        }
         
        $condition_table  = array(C('DB_PREFIX').'house_village_floor'=>'f',C('DB_PREFIX').'house_village_user_bind'=>'b');
        $field = 'b.*,f.floor_name,f.floor_layer,f.property_fee,u.openid';
        $order = ' `b`.`pigcms_id` DESC';

        $left_join = ' left join '.C('DB_PREFIX').'user as u on u.uid=b.uid';

        if (!$pageSize) {
            $list = D('')->field($field)->table($condition_table)->join($left_join)->where($condition)->order($order)->select();
        }else{
            $count_user = D('')->table($condition_table)->where($condition)->count();
            if ($page) {
                $list = D('')->field($field)->table($condition_table)->join($left_join)->where($condition)->order($order)->page($page,$pageSize)->select();
            }else{
                $p = new Page($count_user,$pageSize,'page');
                $list = D('')->field($field)->table($condition_table)->join($left_join)->where($condition)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
            }
        }
        $total_money = 0;
        $water_money = 0;
        $electric_money = 0;
        $gas_money = 0;
        $park_money = 0;
        $property_money = 0;
        $cunstom_money = 0;
        if($list){
            $now_village = D('House_village')->get_one($column['village_id']);
            foreach ($list as &$v){
                $water_money += $v['water_price'];
                $electric_money += $v['electric_price'];
                $gas_money += $v['gas_price'];
                $park_money += $v['park_price'];
                //物业欠费
                $v['property_price'] = 0;
                if ($v['property_endtime'] && $v['property_endtime'] < strtotime(date("Y-m-d"))) {
                    $num = $this->getTimeNum($v['property_endtime'],strtotime(date("Y-m-d")),'M');
                    // var_dump($now_village['property_price']);
                    if (($v['property_fee'] != '0.00') && isset($v['property_fee'])) {
                        $v['property_price'] = $v['property_fee'] * $v['housesize'] * $num;
                    } else {
                        $v['property_price'] = $now_village['property_price'] * $v['housesize'] * $num;
                    }
                    $property_money += $v['property_price'];
                }
                //自定义项欠费
                $payment_list = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',
                C('DB_PREFIX').'house_village_payment_standard'=>'ps',
                C('DB_PREFIX').'house_village_payment'=>'p'))
                ->where("psb.pigcms_id= '".$v['pigcms_id']."' AND p.payment_id = psb.payment_id AND `psb`.`cycle_sum` > `psb`.`paid_cycle` AND ps.standard_id = psb.standard_id AND `psb`.`start_time` <".time())->select();
                $payment_list = $payment_list ? $payment_list : array();

                 // 车位缴费
                $position_payment_list = D('House_village_bind_position')->get_user_position_payment_list(array('pigcms_id'=>$v['pigcms_id']));
                $payment_list = array_merge($payment_list, $position_payment_list);
                $v['cunstom_money'] = 0;
                if ($payment_list) {
                    foreach ($payment_list as $key => $value) {
                        switch ($value['cycle_type']) {
                            case 'Y':
                                $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400;            
                                break;
                            case 'M': 
                                $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400*30;
                                break;
                            case 'D':
                                $end_time = $value['start_time'] + $value['pay_cycle']*$value['paid_cycle']*86400*365;
                                break;
                        }
                        if ($end_time<time()) {
                            $num = $this->getTimeNum($end_time,time(),$value['cycle_type']);
                            $num = ceil($num/$value['pay_cycle']);
                            if ($value['pay_type']==1) {
                                $v['cunstom_money'] += $value['pay_money'] * $num;
                            }else{
                                $v['cunstom_money'] += $value['pay_money'] * $num * $value['metering_mode_val'];
                            }
                        }
                    }
                    $cunstom_money += $v['cunstom_money'];
                }
                
                $v['total'] = floatval($v['water_price'])+floatval($v['electric_price'])+floatval($v['gas_price'])+floatval($v['park_price']) + $v['property_price'] + $v['cunstom_money'];
                $total_money += $v['total'];
            }
        }
        $return['pagebar'] = $pageSize&&$p ? $p->show() : '';
        $return['list'] = $list;
        $return['total_money'] = $total_money;
        $return['water_money'] = $water_money;
        $return['electric_money'] = $electric_money;
        $return['gas_money'] = $gas_money;
        $return['park_money'] = $park_money;
        $return['cunstom_money'] = $cunstom_money;
        $return['property_money'] = $property_money;
        $return['totalPage'] = ceil($count_user/$pageSize);
        $return['page'] = intval($page);;
        $return['list_count'] = count($list);;
        return $return;
    }

    //获得相差时间 $date2>$date1
    function getTimeNum($date1,$date2,$type){
        // var_dump(date('Y-m-d',$date1),date('Y-m-d',$date2));
        switch ($type) {
            case 'Y': //相差年份
                $date_1['y'] = date('Y',$date1);
                $date_2['y'] = date('Y',$date2);
                $num = $date_2['y']-$date_1['y'];
                if (strtotime(date('m-d',$date2))-strtotime(date('m-d',$date1))>=0) {
                    $num += 1;
                }
                # code...
                break;
            case 'M': //相差月份
                list($date_1['y'],$date_1['m'],$date_1['d']) = explode("-",date('Y-m-d',$date1));
                list($date_2['y'],$date_2['m'],$date_2['d']) = explode("-",date('Y-m-d',$date2));
                $num = ($date_2['y']-$date_1['y'])*12 +$date_2['m']-$date_1['m'];
                if ($date_2['d']- $date_1['d'] >= 0) { //多相差1天则加一个月
                    $num += 1;
                }
                break;
            case 'D': //相差天数
                $num = abs( ceil(($date2-$date1)/86400));
                break;
        }
        return $num;    
    }

    //发送缴费通知
    function send_weixin($href,$openid,$param){
        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
        $model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $openid, 'first' => ' 尊敬的业主，您有新的账单！', 'keynote2' => $param['address'], 'keynote1' => $param['property_name'], 'remark' => '您的待缴总额为：[￥'.$param['total'].']，点击缴费！'));
    }

    //发送缴费通知
    function send_weixin_pay($village_id,$user_list,$send_user_type=0){

        $now_village = D('House_village')->where(array('village_id'=>$village_id))->find();

        $send_user_type = $send_user_type ? $send_user_type : $now_village['send_user_type']; // 发送类型

        $href = C('config.site_url') . '/wap.php?g=Wap&c=House&a=village_my_pay&village_id='.$village_id;
        foreach ($user_list as $key => $value) {            
            $param = array(
                'address' => $value['address'],
                'total' => $value['total'],
                'property_name' => $now_village['property_name'],
            );
            
            switch ($send_user_type) {
                case '1': // 只发送业主
                    if ($value['openid']) {
                        $this->send_weixin($href,$value['openid'],$param);
                    }
                    break;
                case '2': // 业主和家属
                    if ($value['openid']) {
                        $this->send_weixin($href,$value['openid'],$param);
                    }
                    // 查询家属
                    $condition = '`u`.`uid` = `b`.`uid` AND `b`.`status`=1 AND `b`.`type` in(1,2) AND b.vacancy_id ='.$value['vacancy_id'];
                    $condition_table  = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'house_village_user_bind'=>'b');
                    $field = '`u`.`openid`';
                    $bind_list = D()->field($field)->table($condition_table)->where($condition)->select();
                    if ($bind_list) {
                        foreach ($bind_list as $bind) {
                            if(!empty($bind['openid'])){
                                $this->send_weixin($href,$bind['openid'],$param);
                            }
                        }
                    }
                    break;
                case '3':// 只发送家属
                    $condition = '`u`.`uid` = `b`.`uid` AND `b`.`status`=1 AND `b`.`type` in(1,2) AND b.vacancy_id ='.$value['vacancy_id'];
                    $condition_table  = array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'house_village_user_bind'=>'b');
                    $field = '`u`.`openid`';
                    $bind_list = D()->field($field)->table($condition_table)->where($condition)->select();
                    if ($bind_list) {
                        foreach ($bind_list as $bind) {
                            if(!empty($bind['openid'])){
                                $this->send_weixin($href,$bind['openid'],$param);
                            }
                        }
                    }
                    break;
            }
        }
    }

}
