<?php
/*
 * 社区2.0业主
 *
 */
class H_userAction extends BaseAction{
	//	获取业主的全部列表
    public function index(){
    	$this->is_existence();
    	//验证权限
    	if(!in_array(91, $this->power)){    		
			$this->returnCode('20090103');
    	}

    	$village_id	=	I('village_id');
		$page	=	I('page',1);
		$page_coun	=	I('page_coun',10);
		$condition_where['village_id'] = $village_id;
		$condition_where['parent_id'] = 0;
		$condition_where['type'] = array('neq',4);

		$search	=	I('search');
		if($search){
			$where['name'] = array('like',array('%'.$search.'%','%.com'));
			$where['phone'] = array('like',array('%'.$search.'%','%.com'));
			$where['address'] = array('like',array('%'.$search.'%','%.com'));
			$where['_logic'] = 'OR';
			$condition_where['_complex'] = $where;
		}

		$arr = $this->get_limit_list_page($condition_where,$page,$page_coun);
		if(empty($arr)){
			$arr = (object)array();
		}
		$this->returnCode(0,$arr);
	}
	//	获取单个业主信息
    public function owner_one(){
    	$this->is_existence();
    	//验证权限
    	if(!in_array(91, $this->power) && !in_array(67, $this->power) ){    		
			$this->returnCode('20090103');
    	}

    	$pigcms_id	=	I('pigcms_id');
    	$village_id	=	I('village_id');
    	if(empty($pigcms_id)){
			$this->returnCode('20090009');
		}

		$info = M('House_village_user_bind')->field(array('parent_id'),true)->where(array('pigcms_id'=>$pigcms_id))->find();
		if(empty($info)){
			$this->returnCode('20090010');
		}

		$database_house_village_floor = D('House_village_floor');
        if($info['floor_id']){
            $floor_info = $database_house_village_floor->where(array('floor_id'=>$info['floor_id']))->find();
            $info['floor_info'] = $floor_info;
        }


        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $vacancy_where['status'] = array('in' , '1,2,3');
        $vacancy_where['village_id'] = $village_id;
        $vacancy_where['usernum'] = $info['usernum'];
        $result = $database_house_village_user_vacancy->where($vacancy_where)->find();
        $info['room_info'] = $result;

        //车位信息
        $position_list = D('House_village_bind_position')->get_user_position_bind_list(array('pigcms_id'=>$pigcms_id));
        //车辆信息
        $car_list = D('House_village_bind_car')->get_user_car_bind_list(array('pigcms_id'=>$pigcms_id));
        $info['position_list'] = $position_list['list'];
        $info['car_list'] = $car_list;

         // 自定义缴费项
        $payment_list = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb', C('DB_PREFIX').'house_village_payment_standard'=>'ps', C('DB_PREFIX').'house_village_payment'=>'p'))->where("psb.pigcms_id= '".$pigcms_id."' AND p.payment_id = psb.payment_id AND ps.standard_id = psb.standard_id")->select();
        $payment_list = $payment_list ? $payment_list : array();

        // 自定义缴费项（车位）
        $position_payment_list = D('House_village_bind_position')->get_user_position_payment_list(array('pigcms_id'=>$pigcms_id));
        $payment_list = array_merge($payment_list, $position_payment_list);
    	$cycle_type = array(
                    'Y'=>'年',
                    'M'=>'月',
                    'D'=>'日',
                );
         foreach ($payment_list as $kk => $vv) {
            $payment_list[$kk]['start_time'] = date('Y-m-d',$vv['start_time']);
            $payment_list[$kk]['end_time'] = date('Y-m-d',$vv['end_time']);
            $payment_list[$kk]['cycle_type'] = $cycle_type[$vv['cycle_type']];
            if ($vv['garage_num']) {
                $payment_list[$kk]['payment_name'] = $vv['payment_name'].'('.$vv['garage_num'].'-'.$vv['position_num'].')';
            }
        }

        $info['payment_list'] = $payment_list;
		$this->returnCode(0,$info);
	}
	//	修改业主信息
	public function eidt(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(93, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$pigcms_id	=	I('pigcms_id');
		if(empty($pigcms_id)){
			$this->returnCode('20090009');
		}
		$village_id = I('village_id');

		$arr = array(
			'name'			=>	I('name'), //姓名
			'phone'			=>	I('phone'),//手机号
			// 'water_price'	=>	I('water_price',0),//水费总欠费
			// 'electric_price'=>	I('electric_price',0),//电费总欠费
			// 'gas_price'		=>	I('gas_price',0),//燃气费总欠费
			// 'park_price'	=>	I('park_price',0),//停车费总欠费
			// 'property_price'=>	I('property_price',0),//物业费总欠费
			// 'park_flag'		=>	I('park_flag',0), //是否有停车位 1有 0没有
		);

		if(empty($arr['name'])){
			$this->returnCode('20090059');
		}
		if(empty($arr['phone'])){
			$this->returnCode('20090060');
		}


		// 用户id
		$now_user = D('User')->get_user(I('phone'), 'phone');
        if ($now_user) {
            $arr['uid'] = $now_user['uid'];
        }else{
			$arr['uid'] = 0;
		}

		// foreach($arr as $k=>$v){
		// 	if($v == null){
		// 		unset($arr[$k]);
		// 	}
  		//   	}

    	$bind_info = M('House_village_user_bind')->field(true)->where(array('pigcms_id'=>$pigcms_id,'village_id'=>$village_id))->find();
    	if($bind_info){
			$aSave = M('House_village_user_bind')->where(array('pigcms_id'=>$pigcms_id,'village_id'=>$village_id))->data($arr)->save();
    	}else{
			$this->returnCode('20090010');
    	}
    	if($aSave){
    		//修改房间表 信息
			$condition_vacancy['usernum']    = $bind_info['usernum'];
			$condition_vacancy['village_id'] = $village_id;
			
			$data_vacancy['status'] = 3;
			$data_vacancy['uid'] = $arr['uid'];
			$data_vacancy['name'] = $arr['name'];
			$data_vacancy['phone'] = $arr['phone'];
			$data_vacancy['type'] = 0;
			D('House_village_user_vacancy')->where($condition_vacancy)->data($data_vacancy)->save();
			$this->returnCode(0);
    	}else if($aSave === 0){
			$this->returnCode('20090007');
    	}else{
			$this->returnCode('20090011');
    	}
	}
	//	搜索业主
	public function	owner_search(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(91, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$search	=	I('search');
		if(empty($search)){
			$this->returnCode('20090012');
		}
		$village_id	=	I('village_id');
		$page	=	I('page',1);
		$page_coun	=	I('page_coun',10);
		$where['name'] = array('like',array('%'.$search.'%','%.com'));
		$where['phone'] = array('like',array('%'.$search.'%','%.com'));
		$where['address'] = array('like',array('%'.$search.'%','%.com'));
		$where['_logic'] = 'OR';
		$condition_where['village_id'] = array('eq',$village_id);
		$condition_where['_complex'] = $where;
		$condition_where['type'] = array('neq',4);
		$condition_where['parent_id'] = 0;
		$arr = $this->get_limit_list_page($condition_where,$page,$page_coun);
		if(empty($arr)){
			$this->returnCode('20090013');
		}
		$this->returnCode(0,$arr);
	}
	//	得到小区下所有的业主列表
	public function get_limit_list_page($condition_where,$page=1,$page_coun=10){
		$return = array();
		$count_user = D('House_village_user_bind')->where($condition_where)->count();
		$user_list = D('House_village_user_bind')->field(array('parent_id'),true)->where($condition_where)->order('`pigcms_id` DESC')->page($page,$page_coun)->select();
		if($user_list){
			$return['totalPage']	= ceil($count_user/$page_coun);
			$return['page']			= intval($page);
			$return['user_count']	= count($user_list);
			$return['user_list']	= $user_list;
		}else{
			return false;
		}
		return $return;
	}
	//	新增业主
	public	function	add(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(92, $this->power)){    		
			$this->returnCode('20090103');
    	}
    	$village_id = I('village_id');

		$arr = array(
			'village_id'	=>	I('village_id'),// 小区id
			// 'usernum'		=>	I('usernum'),//物业编号
			'name'			=>	I('name'), //姓名
			'phone'			=>	I('phone'),//手机号
			'housesize'		=>	I('housesize'),
			// 'water_price'	=>	I('water_price',0),
			// 'electric_price'=>	I('electric_price',0),
			// 'gas_price'		=>	I('gas_price',0),
			// 'park_price'	=>	I('park_price',0),
			// 'property_price'=>	I('property_price',0),
			// 'park_flag'		=>	I('park_flag',0),
			'floor_id'		=>	I('floor_id'), //单元
			'layer_num'		=>	I('layer_num'), //层号
			'room_addrss'		=>	I('room_addrss'),//房号
			'add_time'		=>	time(),
		);
		// if(empty($arr['usernum'])){
		// 	$this->returnCode('20090057');
		// }
		if(empty($arr['name'])){
			$this->returnCode('20090059');
		}
		if(empty($arr['phone'])){
			$this->returnCode('20090060');
		}
		if(empty($arr['floor_id'])){
			$this->returnCode('20090155');
		}
		if(empty($arr['layer_num'])){
			$this->returnCode('20090156');
		}
		if(empty($arr['room_addrss'])){
			$this->returnCode('20090157');
		}
		if(empty($arr['housesize'])){
			$this->returnCode('20090061');
		}

		// 查找单元信息
        $where['floor_id'] = I('floor_id') + 0;
        $where['status'] = 1;
        $where['village_id'] = $village_id;
        $house_village_floor_info = D('House_village_floor')->where($where)->find();
        if (!$house_village_floor_info) {
            $this->returnCode('20090154');
        }

		// 查找房间信息
        $vacancy_where['status'] = 1;
        $vacancy_where['pigcms_id'] = I('layer_room') + 0;
        $vacancy_info = D('House_village_user_vacancy')->where($vacancy_where)->find();
        if (!isset($vacancy_info)) {
            $this->returnCode('20090158');
        }

        $arr['vacancy_id'] = $vacancy_info['pigcms_id']; // 房间id
        $arr['usernum'] = $vacancy_info['usernum']; // 物业编号
		$arr['address'] = $house_village_floor_info['floor_name'] . $house_village_floor_info['floor_layer'] . $arr['layer_num'] . $arr['room_addrss']; //地址

		// 用户id
		$now_user = D('User')->get_user(I('phone'), 'phone');
        if ($now_user) {
            $arr['uid'] = $now_user['uid'];
        }else{
			$arr['uid'] = 0;
		}

		// 查询是否有已绑定住业主
		$aFind = M('House_village_user_bind')->field(array('pigcms_id'))->where(array('village_id'=>$arr['village_id'],'usernum'=>$arr['usernum']))->find();
		if($aFind){
			$this->returnCode('20090058');
		}

		$add = M('House_village_user_bind')->data($arr)->add();
		if($add){
			//更改房间房主信息  uid,name,phone,type=0,status=3,housesize,park_flag,add_time,				
			$data_info['uid'] = $arr['uid'];
			$data_info['name'] = $arr['name'];
			$data_info['phone'] = $arr['phone'];
			$data_info['type'] = 0;
			$data_info['status'] = 3;
			$data_info['housesize'] = $arr['housesize'];
			
			$where_info['pigcms_id'] = I('layer_room');
			$where_info['village_id'] = $village_id;

			D('House_village_user_vacancy')->data($data_info)->where($where_info)->save();
			$this->returnCode(0);
		}else{
			$this->returnCode('20090063');
		}
	}

	// 删除业主
    public function del(){
		$this->is_existence();
      //验证权限
    	if(!in_array(94, $this->power)){    		
			$this->returnCode('20090103');
    	}
        
		$pigcms_id = I('pigcms_id');
		if(empty($pigcms_id)){
			$this->returnCode('20090009');
		}

        $database_house_village_user_bind = D('House_village_user_bind');
        $bind_condition['pigcms_id'] = $pigcms_id;
        $bind_condition['parent_id'] = 0;
        $now_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
        if(!$now_bind_info){
			$this->returnCode('20090010');
        }

        $insert_id = $database_house_village_user_bind->where($bind_condition)->delete();
        // 查询一下是否存在其他状态的业主信息，全部删除
        $where = array('village_id' => $now_bind_info['village_id'], 'room_addrss' => $now_bind_info['room_addrss'],'layer_num' => $now_bind_info['layer_num'],'floor_id' => $now_bind_info['floor_id'], 'type' => array('in', '0, 3'));
        $other = $database_house_village_user_bind->where($where)->field('pigcms_id')->select();
        if (!empty($other)) {
            foreach ($other as $val) {
                $database_house_village_user_bind->where(array('pigcms_id' => $val['pigcms_id']))->delete();
            }
        }
        if($insert_id){
			$family_condition['village_id'] = $now_bind_info['village_id'];
			$family_condition['parent_id'] = $now_bind_info['pigcms_id'];
			$database_house_village_user_bind->where($family_condition)->delete();
			
			//清空房间
			$database_house_village_user_vacancy = D('House_village_user_vacancy');
			$data['uid'] = 0;
			$data['status'] = 1;
			$data['name'] = "";
			$data['phone'] = "";
			$data['type'] = 0;
			$data['park_flag'] = 0;
            $database_house_village_user_vacancy->where(array('phone'=>$now_bind_info['phone'],'pigcms_id'=>$now_bind_info['vacancy_id'],'village_id'=>$now_bind_info['village_id']))->data($data)->save();
			$this->returnCode(0);
        }else{
            $this->returnCode('20090149');
        }
    }

    // 单元信息
    public function floor_info(){
		$this->is_existence();
    	$database_house_village_floor = D('House_village_floor');
        $condition['village_id'] = I('village_id');
        $condition['status'] = 1;
        $floor_list = $database_house_village_floor->house_village_floor_page_list($condition , true ,'floor_id desc' , 99999);
        if ($floor_list['list']['list']) {
        	$this->returnCode(0,$floor_list['list']['list']);
        }else{
        	$this->returnCode('20090150');
        }
        
    }

    // 房间信息
    public function layer_info(){
		$this->is_existence();
    	$database_house_village_floor = D('House_village_floor');
        $condition['village_id'] = I('village_id');
        $condition['status'] = 1;

        $floor_id = I('floor_id');
        $database_house_village_user_vacancy = D('House_village_user_vacancy');

        $where['floor_id'] = $floor_id;
        $where['status'] = 1;
        $result = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($where ,true ,'pigcms_id desc' , 999999999);
        if ($result['result']['list']) {
        	$this->returnCode(0,$result['result']['list']);
        }else{
        	$this->returnCode('20090151');
        }
        
    }

    //获取业主信息
    public function user_list(){
        // $find_type = I('find_type');
        $find_value = I('find_value');
        $village_id = I('village_id');

        if ($find_value) {
            // if ($find_type == 1) {
                // $where['name'] = array('like', $find_value . '%');
            // } else if ($find_type == 2) {
                // $where['phone'] = array('like', $find_value . '%');
            // } else if ($find_type == 3) {
                // $where['usernum'] = array('like', $find_value . '%');
            // }
            $where['other'] = '(`b`.`name` like "%'.$find_value.'%" OR `b`.`phone` like "%'.$find_value.'%" OR `b`.`usernum` like "%'.$find_value.'%")';
        }
       
        // if($_POST['room_addrss']){
        //     $where['room_addrss'] = array('like', '%' . $_POST['room_addrss'] . '%');
        // }
        // if($_POST['pigcms_id']){
        //     $where['pigcms_id'] = array('like', '%' . $_POST['pigcms_id'] . '%');
        // }
        if(I('type')){
            $type='true';
        }
                    
        // if (empty($where)) {
        //     $user_list = D('House_village_user_bind')->get_limit_room_list_page($village_id);
        // } else {
            $user_list = D('House_village_user_bind')->get_limit_room_list_page($village_id, 99999, $where,$type);
        // }

        $user_list = $user_list['user_list'];

         $cycle_type = array(
                'Y'=>'年',
                'M'=>'月',
                'D'=>'日',
            );

        foreach ($user_list as $key => $value) {
            // $payment_list = array();
            // $payment_list = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',
            // C('DB_PREFIX').'house_village_payment_standard'=>'ps',
            // C('DB_PREFIX').'house_village_payment'=>'p'))
            // ->where("psb.pigcms_id= '".$value['pigcms_id']."' AND p.payment_id = psb.payment_id AND ps.standard_id = psb.standard_id")->select();

            // foreach ($payment_list as $kk => $vv) {
            //     $payment_list[$kk]['start_time'] = date('Y-m-d',$vv['start_time']);
            //     $payment_list[$kk]['end_time'] = date('Y-m-d',$vv['end_time']);
            //     $payment_list[$kk]['cycle_type'] = $cycle_type[$vv['cycle_type']];
            // }
            
            // $user_list[$key]['payment_list'] = $payment_list;

        } 
        if ($user_list) {
        	$this->returnCode(0,$user_list);
        }else{
        	$this->returnCode('20090169');
        }
    }
}