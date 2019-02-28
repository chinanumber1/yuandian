<?php
/*
 * 社区功能管理 2.0
 *
 */
class H_functionAction extends BaseAction{
	//	获取电话分类
    public function phone_category(){
		$this->is_existence();
		$village_id		=	I('village_id');
		$page	=	I('page',1);
		$page_coun	=	I('page_coun',10);
		$arr	=	array(
			'village_id'=>$village_id,
			'cat_status'=>array('neq','4'),
		);
		$phone_select	=	$this->phone_select($arr,$page,$page_coun,'House_village_phone_category','`cat_sort` DESC,`cat_id` ASC');
		if(empty($phone_select)){
			$this->returnCode('20090025');
		}
		$this->returnCode(0,$phone_select);
	}

	//	新增电话分类
	public	function	add_category(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(200, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$cat_name		=	I('cat_name');
		$village_id		=	I('village_id');
		if(empty($cat_name)){
			$this->returnCode('20090026');
		}
		$find = M('House_village_phone_category')->where(array('cat_name'=>$cat_name,'village_id'=>$village_id))->find();
		if($find){
			$this->returnCode('20090028');
		}
		$arr	=	array(
			'village_id'	=>	I('village_id'),
			'cat_name'		=>	$cat_name,
			'cat_sort'		=>	I('cat_sort',0),
			'cat_status'	=>	I('cat_status',1),
		);
		$add	=	M('House_village_phone_category')->add($arr);
		if(empty($add)){
			$this->returnCode('20090027');
		}else{
			$this->returnCode(0);
		}
	}

	//	修改电话分类
	public	function	edit_category(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(201, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$cat_id			=	I('cat_id');
		$village_id			=	I('village_id');
		if(empty($cat_id)){
			$this->returnCode('20090029');
		}
		$cat_name	=	I('cat_name');
		if(empty($cat_name)){
			$this->returnCode('20090026');
		}else{
			$find = M('House_village_phone_category')->where(array('cat_name'=>$cat_name,'cat_id'=>array('neq',$cat_id),'village_id'=>$village_id))->find();
		}
		if($find){
			$this->returnCode('20090028');
		}
		$arr	=	array(
			'cat_name'		=>	I('cat_name'),
			'cat_sort'		=>	I('cat_sort'),
			'cat_status'	=>	I('cat_status'),
		);
		foreach($arr as $k=>$v){
			if($v == null){
				unset($arr[$k]);
			}
		}
		$sSave	=	M('House_village_phone_category')->where(array('cat_id'=>$cat_id))->data($arr)->save();
		if($sSave){
			$this->returnCode(0);
		}else if($sSave === 0){
			$this->returnCode('20090007');
		}else{
			$this->returnCode('20090030');
		}
	}

	//	删除电话分类
	public	function	delete_category(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(202, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$cat_id			=	I('cat_id');
		if(empty($cat_id)){
			$this->returnCode('20090029');
		}
		$sDelete	=	M('House_village_phone_category')->where(array('cat_id'=>$cat_id))->delete();
		if($sDelete){
			$pDetele	=	M('House_village_phone')->where(array('cat_id'=>$cat_id))->delete();
		}else{
			$this->returnCode('20090031');
		}
		if($pDetele){
			$this->returnCode(0);
		}else if($pDetele === 0){
			$this->returnCode(0);
		}else{
			$this->returnCode('20090032');
		}
	}

	//	获取电话列表
	public	function	get_phone(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(203, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$cat_id		=	I('cat_id');
		$page	=	I('page',1);
		$page_coun	=	I('page_coun',10);
		if(empty($cat_id)){
			$this->returnCode('20090029');
		}
		$arr	=	array(
			'cat_id'=>$cat_id,
			'status'=>array('neq','4'),
		);
		$phone_select	=	$this->phone_select($arr,$page,$page_coun);
		if($phone_select){
			$this->returnCode(0,$phone_select);
		}else{
			$this->returnCode('20090033');
		}
	}

	//	新增电话
	public	function	add_phone(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(204, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$cat_id		=	I('cat_id');
		$village_id	=	I('village_id');
		if(empty($cat_id)){
			$this->returnCode('20090029');
		}
		$name	=	I('name');
		if(empty($name)){
			$this->returnCode('20090035');
		}else{
			$sFind	=	M('House_village_phone')->where(array('name'=>$name,'village_id'=>$village_id))->find();
		}
		if($sFind){
			$this->returnCode('20090036');
		}
		$phone	=	I('phone');
		if(empty($phone)){
			$this->returnCode('20090037');
		}
		$arr	=	array(
			'village_id'	=>	I('village_id'),
			'cat_id'	=>	$cat_id,
			'name'	=>	$name,
			'phone'	=>	$phone,
			'status'	=>	I('status',1),
			'sort'	=>	I('sort',0),
		);
		$add	=	M('House_village_phone')->add($arr);
		if(empty($add)){
			$this->returnCode('20090038');
		}else{
			$this->returnCode(0);
		}
	}

	//	修改电话
	public	function	edit_phone(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(205, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$pigcms_id	=	I('pigcms_id');
		$village_id	=	I('village_id');
		if(empty($pigcms_id)){
			$this->returnCode('20090039');
		}
		$name	=	I('name');
		if(empty($name)){
			$this->returnCode('20090035');
		}else{
			$sFind	=	M('House_village_phone')->where(array('name'=>$name,'pigcms_id'=>array('neq',$pigcms_id),'village_id'=>$village_id))->find();
		}
		if($sFind){
			$this->returnCode('20090036');
		}
		$phone	=	I('phone');
		if(empty($phone)){
			$this->returnCode('20090037');
		}
		$arr	=	array(
			'name'	=>	$name,
			'phone'	=>	$phone,
			'status'	=>	I('status',1),
			'sort'	=>	I('sort',0),
		);
		foreach($arr as $k=>$v){
			if($v == null){
				unset($arr[$k]);
			}
		}
		$sSave	=	M('House_village_phone')->where(array('pigcms_id'=>$pigcms_id))->data($arr)->save();
		if($sSave){
			$this->returnCode(0);
		}else if($sSave === 0){
			$this->returnCode('20090007');
		}else{
			$this->returnCode('20090040');
		}
	}

	//	删除电话
	public	function	delete_phone(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(206, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$pigcms_id	=	I('pigcms_id');
		if(empty($pigcms_id)){
			$this->returnCode('20090039');
		}
		$pDetele	=	M('House_village_phone')->where(array('pigcms_id'=>$pigcms_id))->delete();
		if($pDetele){
			$this->returnCode(0);
		}else{
			$this->returnCode('20090041');
		}
	}

	//	获取电话和分类列表
	private	function	phone_select($where=array(),$page=1,$page_coun=10,$tatle='House_village_phone',$order='`sort` DESC,`pigcms_id` ASC'){
		if(empty($where)){
			return false;
		}
		$count_arr = M($tatle)->where($where)->order($order)->count();
		$arr = M($tatle)->where($where)->order($order)->page($page,$page_coun)->select();
		if(empty($arr)){
			return false;
		}
		if($tatle	==	'House_village_phone_category'){
			foreach($arr as $k=>$v){
				$arr[$k]['count']	=	M('House_village_phone')->where(array('cat_id'=>$v['cat_id']))->count();
			}
		}
		$return['totalPage']	= ceil($count_arr/$page_coun);
		$return['page']			= intval($page);
		$return['list_count']	= count($arr);
		$return['list']	=	$arr;
		return $return;
	}

	//	快递列表
	public	function	express_service_list(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(207, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$database_house_village_express = D('House_village_express');
		$has_express_service = $this->getHasConfig($village_id, 'has_express_service');
		$page	=	I('page',1);
		$pageSize	=	I('page_coun',10);
		$status	=	I('status',0);
		if($status == 0){
			$where['status']	=	0;
		}else{
			$where['status']	=	array('egt',1);
		}
		if($has_express_service){
		    $where['village_id'] = $village_id;
		    $list = $this->express_service_page_list($where,$page,$pageSize);
		    foreach($list['list'] as $k=>$v){
				$list['list'][$k]['idd']	=	$v['id'];
		    }
		}else{
			$this->returnCode('20090042');
		}
		$this->returnCode(0,$list);
	}

	//	检测物业是否开启代收快递服务
	private function getHasConfig($village_id,$field){
		$database_house_village = D('House_village');
		$house_village_info = $database_house_village->get_one($village_id,$field);
		$config_info = $house_village_info[$field];
		return $config_info;
	}

	//	快递列表数据
	private function express_service_page_list($where=array() , $page=1,$pageSize = 10){
        if(!$where){
            return false;
        }

        $database_express = M('House_village_express');
        $count = $database_express->where($where)->count();
        $village_express_list = $database_express->where($where)->field(true)->order('id desc')->page($page,$pageSize)->select();
        $express_where['status'] = 1;
        $express_info = M('Express')->where($express_where)->getField('id,name');

        foreach($village_express_list as $k=>$v){
            if($v['express_type']==255){
               $village_express_list[$k]['express_name'] = '其他';
            }else{
               $village_express_list[$k]['express_name'] = $express_info[$v['express_type']];
            }
            $village_express_list[$k]['idd']	=	$v['id'];
            $village_express_list[$k]['add_time_s'] = date('Y-m-d H:i:s',$v['add_time']);
            $village_express_list[$k]['delivery_time_s'] = date('Y-m-d H:i:s',$v['delivery_time']);
        }
        $list['totalPage'] = ceil($count/$pageSize);
        $list['page'] = intval($page);
        $list['list_count'] = count($village_express_list);
        $list['list'] = isset($village_express_list)?$village_express_list:array();
        return $list;
    }

    //	得到快递公司列表
    public	function	express_list(){
    	$this->is_existence();
    	$database_express = D('Express');
		$express_list = $database_express->get_express_list();
		foreach($express_list as $k=>$v){
			$express_list[$k]['idd']	=	$v['id'];
		}
		if(empty($express_list)){
			$this->returnCode('20090049');
		}
		$this->returnCode(0,$express_list);
    }

    //	新增快递
    public function express_add(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(209, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$has_express_service = $this->getHasConfig($village_id, 'has_express_service');
		if($has_express_service){
			$arr['express_type']	=	I('express_type');
			if(empty($arr['express_type'])){
				$this->returnCode('20090046');
			}
			$arr['express_no']	=	I('express_no');
			if(empty($arr['express_no'])){
				$this->returnCode('20090047');
			}
			$arr['phone']	=	I('phone');
			if(empty($arr['phone'])){
				$this->returnCode('20090048');
			}
			$arr['memo']	=	I('memo');
			$arr['village_id']	=	$village_id;
			$arr['add_time']	=	time();
			$arr['fetch_code'] = rand(10000,99999);
			$database_house_village_express = D('House_village_express');
			$result = $database_house_village_express->village_express_add($arr);
			if(!$result){
				$this->returnCode('20090045');
			}else{
				if($result['status']){
					$this->returnCode(0);
				}else{
					$this->returnCode('20090043');
				}
			}
		}else{
			$this->returnCode('20090042');
		}
	}

	//	收取快递
	public function express_edit(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(210, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$has_express_service = $this->getHasConfig($village_id, 'has_express_service');
		if($has_express_service){
			$id = I('idd');
			if(empty($id)){
				$this->returnCode('20090050');
			}

			$where['id'] = $id;
			$data['status'] = 2;
			$data['delivery_time'] = time();
			$result = M('House_village_express')->where($where)->save($data);
			if(!$result){
				$this->returnCode('20090051');
			}else{
				$this->returnCode(0);
			}
		}else{
			$this->returnCode('20090042');
		}
	}

	//	删除快递
	public function express_del(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(211, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$has_express_service = $this->getHasConfig($village_id, 'has_express_service');
		if($has_express_service){
			$id = I('idd');
			if(empty($id)){
				$this->returnCode('20090050');
			}

			$where['id'] = $id;
			$result = M('House_village_express')->where($where)->delete();
			if(!$result){
				$this->returnCode('20090052');
			}else{
				$this->returnCode(0);
			}
		}else{
			$this->returnCode('20090042');
		}
	}

	//	快递搜索
	public function express_search(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(207, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		//	检测物业是否开通代收快递
		$has_express_service = $this->getHasConfig($village_id, 'has_express_service');
		if($has_express_service){
			$database_house_village_express = D('House_village_express');
			$keyword 	= 	I('keyword');
			$start_time = 	I('start_time');
			$end_time 	= 	I('end_time');
			$page		=	I('page',1);
			$pageSize	=	I('page_coun',10);
			$status		=	I('status',0);
			if($status == 0){
				$where['status']	=	0;
			}else{
				$where['status']	=	array('egt',1);
			}
			if($keyword){
				$where['phone|express_no'] = array('like','%'.$keyword.'%');
			}
			if($start_time && $end_time){
				$start_time = strtotime($start_time);
				$end_time = strtotime($end_time.'23:59:59');
				if ($status == 0) {
                    $where['add_time'] = array('between',array($start_time,$end_time));
                } else {
                    $where['delivery_time'] = array('between',array($start_time,$end_time));
                }
			}else if($start_time){
				$start_time = strtotime($start_time);
                if ($status == 0) {
                    $where['add_time'] = array('egt',$start_time);
                } else {
                    $where['delivery_time'] = array('egt',$start_time);
                }
			}else if($end_time){
				$end_time = strtotime($end_time.'23:59:59');
                if ($status == 0) {
                    $where['add_time'] = array('lt',$end_time);
                } else {
                    $where['delivery_time'] = array('lt',$end_time);
                }
			}
			$condition_where['_complex'] = $where;
			$result = $database_house_village_express->ajax_vllage_express_search($condition_where,$page,$pageSize);
			if($result['status']	==	0){
				$result['list']	=	array();
			}
			$this->returnCode(0,$result);
		}else{
			$this->returnCode('20090042');
		}
	}

	//	访客列表
	public function visitor_list(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(216, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id =  I('village_id');
		$page =  I('page',1);
		$pageSize =  I('pageSize',10);
		$has_visitor = $this->getHasConfig($village_id, 'has_visitor');
		if($has_visitor){
			$where['village_id'] = $village_id;
			$status = I('status',0);
			if($status == 0){
				$where['status'] = $status;
			}else{
				$where['status'] = array('egt',1);
			}
			$list = $this->house_village_visitor_page_list($where,$page,$pageSize);
			// if(!$list['list']){
			// 	$this->returnCode('20090066');
			// }else{
				$this->returnCode(0,$list);
			// }
		}else{
			$this->returnCode('20090065');
		}
	}

	//	访客列表
	public function house_village_visitor_page_list($where , $page = 1,$pageSize = 10){
        if(!$where){
            return false;
        }
        $count = M('House_village_visitor')->where($where)->count();
        $house_village_visitor_list = M('House_village_visitor')->where($where)->field(true)->order('id desc')->page($page,$pageSize)->select();
        foreach($house_village_visitor_list as $k=>$v){
			$house_village_visitor_list[$k]['idd'] = $v['id'];
			$house_village_visitor_list[$k]['add_time']	= date('Y-m-d H:i:s',$v['add_time']);
			$house_village_visitor_list[$k]['pass_time'] = $v['pass_time']?date('Y-m-d H:i:s',$v['pass_time']) : '';
        }
        $list['totalPage'] = ceil($count/$pageSize);
        $list['page'] = intval($page);
        $list['list_count'] = count($house_village_visitor_list);
        $list['list'] = isset($house_village_visitor_list)?$house_village_visitor_list:array();
        return $list;
    }

    //	新增访客
	public function visitor_add(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(243, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$has_visitor = $this->getHasConfig($village_id, 'has_visitor');

        $where['village_id'] = $village_id;
        $where['phone'] = I('owner_phone');
        $where['parent_id'] = 0;
        $where['type'] = array('in', array(0, 3));
		$owner = D('House_village_user_bind')->house_village_user_bind_detail($where);

		if($has_visitor ){
			$arr	=	array(
					'visitor_type'	=>	I('visitor_type'),
					'village_id'	=>	$village_id,
					'visitor_name'	=>	I('visitor_name'),
					'visitor_phone'	=>	I('visitor_phone'),
					'owner_phone'	=>	I('owner_phone'),
					'status'		=>	I('status',0),
					'memo'			=>	I('memo'),
					'owner_uid'			=>$owner['info']['uid'],
					'owner_name'			=>$owner['info']['name'],
					'owner_address'			=>	$owner['info']['address'],
					'add_time'			=>	time(),
			);
			if(empty($arr['visitor_phone'])){
				$this->returnCode('20090067');
			}
			if(empty($arr['owner_phone'])){
				$this->returnCode('20090068');
			}
			if($arr['visitor_type'] == null){
				$this->returnCode('20090070');
			}
			if($visitor_type == 255){
				if(empty($memo)){
					$this->returnCode('20090069');
				}
			}

			$database_house_village_visitor = D('House_village_visitor');
			$result =	$database_house_village_visitor->house_village_visitor_add($arr,1);
			if($result['status'] == 1){
				$this->returnCode(0);
			}else if($result['msg']== '访客手机号码不正确'){
				$this->returnCode('20090072');
			}else if($result['msg']== '业主手机号码不正确'){
				$this->returnCode('20090073');
			}else{
				$this->returnCode('20090071');
			}
		}else{
			$this->returnCode('20090065');
		}
	}

	//	删除访客
	public	function	visitor_del(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(218, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$has_visitor = $this->getHasConfig($village_id, 'has_visitor');
		if($has_visitor){
			$id	=	I('idd');
			$delete	=	M('House_village_visitor')->where(array('village_id'=>$village_id,'id'=>$id))->delete();
			if($delete){
				$this->returnCode(0);
			}else{
				$this->returnCode('20090075');
			}
		}else{
			$this->returnCode('20090065');
		}
	}

	//	搜索访客
	public	function	visitor_srach(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(216, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$status = I('status',0);
		$has_visitor = $this->getHasConfig($village_id, 'has_visitor');
		if($has_visitor){
			$keyword	=	I('keyword');
			$page		=	I('page',1);
			$page_coun	=	I('page_coun',10);
            $start_time = 	I('start_time');
            $end_time 	= 	I('end_time');
            $visitor_type = I('visitor_type');
			if($keyword){
                $where['visitor_name|visitor_phone|owner_name|owner_phone'] = array('like','%'.$keyword.'%');
            }
			if($start_time && $end_time){
			   $start_time = strtotime($start_time);
			   $end_time = strtotime($end_time.'23:59:59');
			   $where['add_time'] = array('between',array($start_time,$end_time));
			}else if($start_time){
			   $start_time = strtotime($start_time);
			   $where['add_time'] = array('egt',$start_time);
			}else if($end_time){
			   $end_time = strtotime($end_time.'23:59:59');
			   $where['add_time'] = array('lt',$end_time);
			}
			if($visitor_type){
			   $where['visitor_type'] = $visitor_type;
			}
			if($status == 0){
				$where['status'] = $status;
			}else{
				$where['status'] = array('egt',1);
			}
			$database_house_village_visitor = D('House_village_visitor');
			$result = $database_house_village_visitor->ajax_house_village_visitor_search($where,$page,$page_coun);
			if($result['status']){
				foreach($result['list']['list'] as $k=>$v){
					$result['list']['list'][$k]['idd']	=	$v['id'];
					$result['list']['list'][$k]['add_time']	= $v['add_time'] ? date('Y-m-d H:i:s',$v['add_time']) : '';
					$result['list']['list'][$k]['pass_time'] = $v['pass_time'] ? date('Y-m-d H:i:s',$v['pass_time']) : '';
				}
				$this->returnCode(0,$result['list']);
			}else{
				$this->returnCode('20090076');
			}
		}else{
			$this->returnCode('20090065');
		}
	}

	//	是否放行
	public	function	visitor_chk(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(217, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$id = I('idd');
		if(!$id){
            $this->returnCode('20090077');
        }
		$has_visitor = $this->getHasConfig($village_id, 'has_visitor');
		if($has_visitor){
	        $database_house_village_visitor = D('House_village_visitor');
	        $status = I('status',2);
	        $result = M('House_village_visitor')->where(array('id'=>$id))->data(array('status'=>$status,'pass_time'=>time()))->save();
	        if(empty($result)){
	            $this->returnCode('20090078');
	        }else{
	            $this->returnCode(0);
	        }
		}else{
			$this->returnCode('20090065');
		}
	}

	// 车位管理

	//	车库列表
	public function garage_list(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(51, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id =  I('village_id');
		$page =  I('page',1);
		$pageSize =  I('pageSize',10);

		if (I('garage_num')) {
			$where['garage_num'] =  array('like','%'.trim(I('garage_num')).'%');
		}
	
		$where['village_id'] = $village_id;
		$list = $this->house_village_parking_garage_page_list($where,$page,$pageSize);
		if(!$list['list']){
			$this->returnCode('20090124');
		}else{
			$this->returnCode(0,$list);
		}
		
	}

	//	车库列表
	public function house_village_parking_garage_page_list($where , $page = 1,$pageSize = 10){
        if(!$where){
            return false;
        }
        $count = M('House_village_parking_garage')->where($where)->count();
        $list = M('House_village_parking_garage')->where($where)->field(true)->order('garage_id desc')->page($page,$pageSize)->select();
        foreach($list as $k=>$v){
			$list[$k]['idd'] = $v['garage_id'];
        }
        $lists = array();
        $lists['totalPage'] = ceil($count/$pageSize);
        $lists['page'] = intval($page);
        $lists['list_count'] = count($list);
        $lists['list'] = isset($list)?$list:array();
        return $lists;
    }

    //	单个车库详情
	public function garage_detail(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(51, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$id = I('idd');
		if(empty($id)){
			$this->returnCode('20090106');
		}

        $where['garage_id']=$id;
        $where['village_id']=$village_id;
        $result = D('House_village_parking_garage')->get_garage_one('',$where);
		if(!$result){
			$this->returnCode('20090108');
		}else{
			$this->returnCode(0,$result);
		}
	}

    //	新增车库
	public function garage_add(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(52, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$data	=	array(
			'garage_num'	=>	I('garage_num'),
			'village_id'	=>	$village_id,
			'garage_position'	=>	I('garage_position'),
			'garage_remark'	=>	I('garage_remark'),
			'garage_addtime'			=>	time(),
		);
		if(empty($data['garage_num'])){ //车库编号
			$this->returnCode('20090104');
		}
		if(empty($data['garage_position'])){ //车库地址
			$this->returnCode('20090105');
		}
        $result = D('House_village_parking_garage')->add_parking_garage($data);
		if($result){
			$this->returnCode(0);
		}else{
			$this->returnCode('20090109');
		}
	}

	//	编辑车库
	public function garage_edit(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(53, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
	
		$id = I('idd');
		if(empty($id)){
			$this->returnCode('20090106');
		}

		$data = array(
			'garage_id'	=>	$id,
			'garage_num'	=>	I('garage_num'),
			'garage_position'	=>	I('garage_position'),
			'garage_remark'	=>	I('garage_remark'),
		);
		if(empty($data['garage_num'])){ //车库编号
			$this->returnCode('20090104');
		}
		if(empty($data['garage_position'])){ //车库地址
			$this->returnCode('20090105');
		}
		$result = D('House_village_parking_garage')->save_parking_garage($data);
		if($result===false){
			$this->returnCode('20090110');
		}else{
			$this->returnCode(0);
		}
	}  

	//	所有车库列表
	public function garage_all_list(){
		$this->is_existence();
		$village_id = I('village_id');
		
        $where['village_id'] = $village_id;
        $field ='*';
        $info_list = D('House_village_parking_garage')->get_garage_list($field,$where);
        $info_list = $info_list ? $info_list : array();
		// if($result){
			$this->returnCode(0,$info_list);
		// }else{
		// 	$this->returnCode('20090124');
		// }
	}

	//	删除车库
	public function garage_del(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(54, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
    	$id	=	I('idd');
        $where['garage_id'] = $id;
        $where['village_id'] = $village_id;
        //先查看是否绑定车位
		$position_list = D('House_village_parking_position')->get_parking_select($where);
		if ($position_list) {
			$this->returnCode('20090184');
		}

        $result = D('House_village_parking_garage')->del_prking_garage($where);
		if($result){
			$this->returnCode(0);
		}else{
			$this->returnCode('20090107');
		}
	}

	//	车位列表
	public function parking_list(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(45, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id =  I('village_id');
		$page =  I('page',1);
		$pageSize =  I('pageSize',10);
		if (I('position_num')) {
			$where['position_num'] = array('like','%'.trim(I('position_num')).'%');
		}
		
		$where['village_id'] = $village_id;
		$list = $this->house_village_parking_position_page_list($where,$page,$pageSize);
		if(!$list['list']){
			$this->returnCode('20090125');
		}else{
			$this->returnCode(0,$list);
		}
	}

	//	车位列表
	public function parking_all_list(){
		$this->is_existence();
    	//验证权限
   //  	if(!in_array(45, $this->power)){    		
			// $this->returnCode('20090103');
   //  	}

		$village_id =  I('village_id');
		$page =  I('page',1);

		$where['village_id'] = $village_id;
		$list = D('House_village_parking_position')->get_parking_select($where);
		if(!$list){
			$this->returnCode('20090125');
		}else{
			$this->returnCode(0,$list);
		}
	}

	//	车位列表
	public function house_village_parking_position_page_list($where , $page = 1,$pageSize = 10){
        if(!$where){
            return false;
        }
        $count = M('House_village_parking_position')->where($where)->count();
        $list = M('House_village_parking_position')->where($where)->field(true)->order('position_id desc')->page($page,$pageSize)->select();
        foreach($list as $k=>$v){

        	$garage_info = M('House_village_parking_garage')->where(array('garage_id'=>$v['garage_id']))->find();
			$list[$k]['idd'] = $v['position_id'];
			$list[$k]['garage_num'] = $garage_info['garage_num'];
        }
        $lists = array();
        $lists['totalPage'] = ceil($count/$pageSize);
        $lists['page'] = intval($page);
        $lists['list_count'] = count($list);
        $lists['list'] = isset($list)?$list:array();
        return $lists;
    }

    //	单个车位详情
	public function parking_detail(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(45, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$id = I('idd');
		if(empty($id)){
			$this->returnCode('20090111');
		}

        $where['position_id']=$id;
        $where['village_id']=$village_id;
        $result = D('House_village_parking_position')->get_parking_one($where);


		if(!$result){
			$this->returnCode('20090112');
		}else{
	        //车库
	        $where['garage_id']=$result['garage_id'];
	        $where['village_id']=$village_id;
	        $garage_info = D('House_village_parking_garage')->get_garage_one('',$where);
	        $result['garage_num'] = $garage_info['garage_num'];

	        //住户详情
	        $condition = '`bp`.`village_id` = `b`.`village_id` AND `bp`.`user_id` = `b`.`pigcms_id` AND bp.village_id ='.$where['village_id'].' AND bp.position_id='.$where['position_id'];
	        $condition_table  = array(C('DB_PREFIX').'house_village_bind_position'=>'bp',C('DB_PREFIX').'house_village_user_bind'=>'b');
	        $field = 'b.name,b.phone,b.address';
	        $order = ' `b`.`pigcms_id` DESC';
        	$user_list = D('')->field($field)->table($condition_table)->where($condition)->select();
        	$result['user_list'] = $user_list ? $user_list : array();
			$this->returnCode(0,$result);
		}
	}

    //	新增车位
	public function parking_add(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(46, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$data	=	array(
			// 'prefix'	=>	I('prefix'),
			'garage_id'	=>	I('garage_id'),
			'position_num'	=>	I('position_num'),
			// 'position_type'	=>	I('position_type'),
			'position_area'	=>	I('position_area'),
			// 'position_status'	=>	I('position_status'),
			'position_note'	=>	I('position_note'),
			'village_id'	=>	$village_id,
		);
		if(empty($data['garage_id'])){ //车库编号
			$this->returnCode('20090116');
		}
		if(empty($data['position_area'])){ //车位面积
			$this->returnCode('20090121');
		}
		if(empty($data['position_num'])){ //车位号
			$this->returnCode('20090118');
		}
		// if(empty($data['position_status'])){ //车位状态
		// 	$this->returnCode('20090119');
		// }
        $result = D('House_village_parking_position')->parking_position_add($data);

		if($result){
			$this->returnCode(0);
		}else{
			$this->returnCode('20090113');
		}
	}
	
	//	编辑车位
	public function parking_edit(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(47, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
	
		$id = I('idd');
		if(empty($id)){
			$this->returnCode('20090111');
		}

		$data	=	array(
			// 'prefix'	=>	I('prefix'),
			'position_id'	=>	$id,
			'garage_id'	=>	I('garage_id'),
			'position_num'	=>	I('position_num'),
			// 'position_type'	=>	I('position_type'),
			'position_area'	=>	I('position_area'),
			// 'position_status'	=>	I('position_status'),
			'position_note'	=>	I('position_note'),
			'village_id'	=>	$village_id,
		);
		if(empty($data['garage_id'])){ //车库编号
			$this->returnCode('20090116');
		}
		if(empty($data['position_num'])){ //车位号
			$this->returnCode('20090118');
		}
		// if(empty($data['position_status'])){ //车位状态
		// 	$this->returnCode('20090119');
		// }
		if(empty($data['position_area'])){ //车位面积
			$this->returnCode('20090121');
		}
        $result = D('House_village_parking_position')->parking_position_save($data);
		if($result===false){
			$this->returnCode('20090114');
		}else{
			$this->returnCode(0);
		}
	}

	//	删除车位
	public function parking_del(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(48, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$id = I('idd');
		if(empty($id)){
			$this->returnCode('20090111');
		}

        $res = D('House_village_parking_car')->get_parking_car_one(array('car_position_id'=>$id));//判断该车位是否存在绑定车辆
        if($res){
        	$this->returnCode('20090122');
        }

        $res = D('House_village_bind_position')->get_bind_position_one(array('position_id'=>$id));
        if($res){
        	$this->returnCode('20090123');
        }

        $where['position_id'] = $id;
        $where['village_id'] = $village_id;
        $result = D('House_village_parking_position')->del_prking_position($where);

		if($result){
			$this->returnCode(0);
		}else{
			$this->returnCode('20090115');
		}
	}

	//	车辆列表
	public function vehicle_list(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(55, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id =  I('village_id');
		$page =  I('page',1);
		$pageSize =  I('pageSize',10);
		$search = trim(I('search'));
		if ($search) {
			$num = preg_match("/[\x{4e00}-\x{9fa5}]/iu",$search,$arr); //匹配汉字
			$car_number_1 = preg_replace("/[\x{4e00}-\x{9fa5}]/iu","",$search); //这样是去掉汉字
			if ($num) { // 存在汉字 查询车牌号或姓名
				if ($car_number_1) {
					$where['_complex'] = array(
						'_complex' => array(
										'car_number' => array('like',strtoupper($car_number_1).'%'),
										'province' => $arr[0],
										'_logic' => 'and',
									),
						'car_user_name' => array('like',$search.'%'),
						'_logic' => 'or',
					);
				}else{
					$where['_complex'] = array(
						'province' => $arr[0],
						'car_user_name' => array('like',$search.'%'),
						'_logic' => 'or',
					);
				}
			}else{
				$num = preg_match("/[A-Za-z]/iu",$search,$arr); //匹配英文
				if ($num) { // 英文开头 查询车牌号或姓名
					$where['_complex'] = array(
						'car_number' => array('like',strtoupper($car_number_1).'%'),
						'car_user_name' => array('like',$car_number_1.'%'),
						'_logic' => 'or',
					);
				}else{
					// 数字 查询手机号
					$where['_complex'] = array(
						'car_user_phone' => array('like',$search.'%'),
						'car_number' => array('like',$search.'%'),
						'car_user_name' => array('like',$search.'%'),
						'_logic' => 'or',
					);
				}
			}
		}
		// var_dump($where);
		$where['village_id'] = $village_id;
		$list = $this->house_village_parking_car_page_list($where,$page,$pageSize);
		if(!$list['list']){
			$this->returnCode('20090126');
		}else{
			$this->returnCode(0,$list);
		}
	}

	//	车辆列表
	public function house_village_parking_car_page_list($where , $page = 1,$pageSize = 10){
        if(!$where){
            return false;
        }
        $count = M('House_village_parking_car')->where($where)->count();
        $list = M('House_village_parking_car')->where($where)->field(true)->order('car_id desc')->page($page,$pageSize)->select();
        foreach($list as $k=>$v){
	        $where_p['position_id']=$v['car_position_id'];
        	$position_info = D('House_village_parking_position')->get_one($where_p);
	        $list[$k]['position_num'] = $position_info['position_num'];
			$list[$k]['idd'] = $v['car_id'];
        }
        $lists = array();
        $lists['totalPage'] = ceil($count/$pageSize);
        $lists['page'] = intval($page);
        $lists['list_count'] = count($list);
        $lists['list'] = isset($list)?$list:array();
        return $lists;
    }

    //	单个车辆详情
	public function vehicle_detail(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(55, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$id = I('idd');
		if(empty($id)){
			$this->returnCode('20090127');
		}

        $where['car_id']=$id;
        $where['village_id']=$village_id;
        $result = D('House_village_parking_car')->get_parking_car_one($where);
		if(!$result){
			$this->returnCode('20090128');
		}else{
			//车位
	        $where_p['position_id']=$result['car_position_id'];
	        $where_p['village_id']=$village_id;
	        $garage_info = D('House_village_parking_position')->get_one($where_p);
	        $result['position_num'] = $garage_info['position_num'] ? $garage_info['position_num'] : '';

	        //住户详情
	        $condition = '`bc`.`village_id` = `b`.`village_id` AND `bc`.`user_id` = `b`.`pigcms_id` AND bc.village_id ='.$where['village_id'].' AND bc.car_id='.$where['car_id'];
	        $condition_table  = array(C('DB_PREFIX').'house_village_bind_car'=>'bc',C('DB_PREFIX').'house_village_user_bind'=>'b');
	        $field = 'b.name,b.phone,b.address';
	        $order = ' `b`.`pigcms_id` DESC';
        	$user_list = D('')->field($field)->table($condition_table)->where($condition)->select();
        	$result['user_list'] = $user_list ? $user_list : array();
			$this->returnCode(0,$result);
		}
	}

    //	新增车辆
	public function vehicle_add(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(56, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$data = array(
			'province'	=>	I('province') ? I('province') : '', // 省份
			'car_number'	=>	strtoupper(I('car_number')), //车牌号码
			'car_position_id'	=>	I('car_position_id') ? I('car_position_id') : 0, //车位号
			'car_user_name'	=>	I('car_user_name') ? I('car_user_name') : '', // 车主姓名
			'car_user_phone'	=>	I('car_user_phone') ? I('car_user_phone') : '', // 车主手机号
			'car_stop_num'	=>	I('car_stop_num') ? I('car_stop_num') : '', // 停车卡号
			'car_displacement'	=>	I('car_displacement') ? I('car_displacement') : '', // 排量
			'car_addtime'	=>	time(),
			'village_id'	=>	$village_id,
		);
		if(empty($data['car_number'])){ //车牌号码
			$this->returnCode('20090129');
		}

        if (empty($data['car_user_name'])) {
			$this->returnCode('20090181');
        }

        if (empty($data['car_user_phone'])) {
			$this->returnCode('20090182');
        }

        if(!preg_match('/^[0-9]{11}$/',$data['car_user_phone'])){
			$this->returnCode('20090183');
        }
        
		$res = D('House_village_parking_car')->get_parking_car_one(array('car_number'=>$data['car_number'],'village_id'=>$village_id));   
		if($res){ //车牌号已存在
			$this->returnCode('20090134');
        }
        $res = D('House_village_parking_car')->get_parking_car_one(array('car_stop_num'=>$data['car_stop_num'],'village_id'=>$village_id));  
		if($data['car_stop_num']&&$res){ //停车卡号已存在
			$this->returnCode('20090135');
        }

        $result = D('House_village_parking_car')->parking_car_add($data);
		if($result){
			$this->returnCode(0);
		}else{
			$this->returnCode('20090130');
		}
	}
	
	//	编辑车辆
	public function vehicle_edit(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(57, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
	
		$id = I('idd');
		if(empty($id)){
			$this->returnCode('20090127');
		}

		$data = array(
			'province'	=>	I('province') ? I('province') : '', // 省份
			'car_number'	=>	strtoupper(I('car_number')), //车牌号码
			'car_position_id'	=>	I('car_position_id'), //车位号
			'car_user_name'	=>	I('car_user_name'), // 车主姓名
			'car_user_phone'	=>	I('car_user_phone'), // 车主手机号
			'car_stop_num'	=>	I('car_stop_num'), // 停车卡号
			'car_displacement'	=>	I('car_displacement'), // 排量
			'car_id'	=>	$id, // 车辆ID
		);

		if(empty($data['car_number'])){ //车牌号码
			$this->returnCode('20090129');
		}

        if (empty($data['car_user_name'])) {
			$this->returnCode('20090181');
        }

        if (empty($data['car_user_phone'])) {
			$this->returnCode('20090182');
        } 

        if(!preg_match('/^[0-9]{11}$/',$data['car_user_phone'])){
			$this->returnCode('20090183');
        }
        
		$res = D('House_village_parking_car')->get_parking_car_one(array('car_number'=>$data['car_number'],'village_id'=>$village_id));   
		if($res&&$res['car_id']!=$id){ //车牌号已存在
			$this->returnCode('20090134');
        }

        if ($data['car_stop_num']) {
	        $res = D('House_village_parking_car')->get_parking_car_one(array('car_stop_num'=>$data['car_stop_num'],'village_id'=>$village_id));  
			if($res&&$res['car_id']!=$id){ //停车卡号已存在
				$this->returnCode('20090135');
	        }
        }

        $result = D('House_village_parking_car')->parking_car_save($data);
		if($result===false){
			$this->returnCode('20090131');
		}else{
			$this->returnCode(0);
		}
	}

	//	删除车辆
	public function vehicle_del(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(58, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$id = I('idd');
		if(empty($id)){
			$this->returnCode('20090127');
		}

		$res = D('House_village_bind_car')->get_bind_car_one(array('car_id'=>$id));
        if($res){
        	$this->returnCode('20090132');
        }
        $where['car_id'] = $id;
        $where['village_id'] = $village_id;
        $result = D('House_village_parking_car')->parking_car_del($where);

		if($result){
			$this->returnCode(0);
		}else{
			$this->returnCode('20090133');
		}
	}

	//	押金列表
	public function deposit_list(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(41, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id =  I('village_id');
		$page =  I('page',1);
		$pageSize =  I('pageSize',10);

	    $begin_time = I('begin_time');
        $end_time = I('end_time');
        $is_refund = I('is_refund',1);
        if ($is_refund==1) {
            $where['a.is_refund'] = 1;
        }else{
            $where['a.is_refund'] = array('neq',1);
        }

        if ($begin_time && !$end_time) {
            $where['a.pay_time'] = array('gt', strtotime($begin_time));
        }
        if ($end_time && !$begin_time) {
        	$where['a.pay_time'] = array('lt',strtotime($end_time.' 23:59:59'));
        }
        if ($end_time && $begin_time) {
        	$where['a.pay_time'] = array('between', array(strtotime($begin_time), strtotime($end_time.' 23:59:59')));
        }
        $where['c.village_id'] = $village_id;

		$list = $this->house_village_deposit_page_list($where,$page,$pageSize);
      	$now_village = D('House_village')->where(array('village_id'=>$village_id))->find();
		foreach($list['list'] as &$v){
			if ($v['role_id'] == 0) {
				$v['realname'] = $now_village['account'];
			}
        }
		// if(!$list['list']){
			// $this->returnCode('20090136');
		// }else{
			$this->returnCode(0,$list);
		// }
	}

	//	押金列表
	public function house_village_deposit_page_list($where , $page = 1,$pageSize = 10){
        if(!$where){
            return false;
        }

        $field='a.*,b.name,b.address,c.name as pay_name,d.realname';
        $join='join '.C('DB_PREFIX').'house_village_user_bind as b on a.pigcms_id=b.pigcms_id join '.C('DB_PREFIX').'house_village_pay_type as c on a.pay_type=c.id left join '.C('DB_PREFIX').'house_admin as d on a.role_id=d.id';
        $order='a.pay_time desc';

		$count = D('House_village_deposit')->alias('a')->join($join)->where($where)->count();
    
		$list = D('House_village_deposit')
                                ->alias('a')
                                ->field($field)
                                ->join($join)
                                ->where($where)
                                ->order($order)
                                ->page($page.','.$pageSize)
                                ->select();
        foreach($list as $k=>$v){
			$list[$k]['idd'] = $v['deposit_id'];
			$list[$k]['pay_time'] = date('Y-m-d H:i',$v['pay_time']);
			$list[$k]['refund_time'] = $list[$k]['refund_time'] ? date('Y-m-d H:i',$v['refund_time']) : '';
        }
        $lists = array();
        $lists['totalPage'] = ceil($count/$pageSize);
        $lists['page'] = intval($page);
        $lists['list_count'] = count($list);
        $lists['list'] = isset($list)?$list:array();
        return $lists;
    }

    //	单个押金详情
	public function deposit_detail(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(41, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$id = I('idd');
		if(empty($id)){
			$this->returnCode('20090137');
		}

        $where = array(
            'a.deposit_id' => $id,
            'a.village_id' => $village_id
        );
        //获取详情
        $field='a.*,b.name,b.address,c.name as pay_name';
        $join='join '.C('DB_PREFIX').'house_village_user_bind as b on a.pigcms_id=b.pigcms_id join '.C('DB_PREFIX').'house_village_pay_type as c on a.pay_type=c.id';
        $result = D('House_village_deposit')->get_deposit_one($field,$join,$where);
        $result['pay_time'] = date('Y-m-d H:i',$result['pay_time']);
        $result['refund_time'] = date('Y-m-d H:i',$result['refund_time']);
        if ($result['role_id'] == 0) {
        	$now_village = D('House_village')->where(array('village_id'=>$result['village_id']))->find();
        	$result['realname'] = $now_village['account'];
        }else{
        	$role_info = D('House_admin')->where(array('id'=>$result['role_id']))->find();
        	$result['realname'] = $role_info['realname'] ? $role_info['realname'] :$role_info['account'];

        }

		if(!$result){
			$this->returnCode('20090138');
		}else{
			$this->returnCode(0,$result);
		}
	}

    //	新增押金
	public function deposit_add(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(42, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');

		$data = array(
			'room_num'	=>	I('room_num') ? I('room_num') : '', // 房间编号
			'pay_type'	=>	I('pay_type'), //支付方式
			'pigcms_id'	=>	I('pigcms_id') ? I('pigcms_id') : 0, //绑定住户id
			'payment_money'	=>	I('payment_money') ? I('payment_money') : 0, // 应缴金额
			'actual_money'	=>	I('actual_money') ? I('actual_money') : 0, // 实缴金额
			'deposit_balance'	=>	I('actual_money') ? I('actual_money') : 0, // 押金余额
			'deposit_note'	=>	I('deposit_note') ? I('deposit_note') : '', // 押金备注
			'deposit_name'	=>	I('deposit_name') ? I('deposit_name') : '', // 押金项目
			'pay_time'	=>	time(), // 付款时间
			'role_id'	=>	$_SESSION['house']['role_id'], // 收款人id 角色ID
			'village_id'	=>	$village_id,
		);

		if(empty($data['room_num'])){ //房间编号
			$this->returnCode('20090140');
		}

		if(!intval($data['pay_type'])){ //支付方式
			$this->returnCode('20090141');
		}

		if(empty($data['deposit_name'])){ //押金项目
			$this->returnCode('20090142');
		}

		if(empty($data['payment_money'])){ //应缴金额
			$this->returnCode('20090143');
		}

		if(empty($data['actual_money'])){ //实缴金额
			$this->returnCode('20090144');
		}

        $result = D('House_village_deposit')->house_deposit_add($data);
		if($result){
			$this->returnCode(0);
		}else{
			$this->returnCode('20090168');
		}
	}
	
	//	押金退款
	public function deposit_refund(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(252, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
	
		$id = I('idd');
		if(empty($id)){
			$this->returnCode('20090111');
		}

		$data = array(
			'refund_money' => I('refund_money'),
			'refund_note' => I('refund_note'),
			'refund_time' => time()
		);
		$where = array(
			'deposit_id' => $id,
		);

		if($data['refund_money'] < 0){
			$this->returnCode('20090145');
        }
        
        $deposit_model = D('House_village_deposit');
        $res = $deposit_model->get_deposit_one('','',array('deposit_id'=>$id,'village_id'=>$village_id));
        if (!$res) {
			$this->returnCode('20090138');
        }

 		$data['deposit_balance'] = $res['actual_money'] - $res['refund_money'] - $data['refund_money'];
        if($data['deposit_balance'] < 0){  //退款金额大于押金余额
			$this->returnCode('20090146');
        }else{
            $data['is_refund'] = 2;
        }

        $data['refund_money'] = $data['refund_money'] + $res['refund_money'];
        $result = $deposit_model->where($where)->data($data)->save();
		if($result===false){
			$this->returnCode('20090147');
		}else{
			$this->returnCode(0);
		}
	}

	//	删除押金
	public function deposit_del(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(43, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$village_id = I('village_id');
		$id = I('idd');
		if(empty($id)){
			$this->returnCode('20090137');
		}
	
        $where['deposit_id'] = $id;
        $where['village_id'] = $village_id;
        $result = D('House_village_deposit')->deposit_delete($where);

		if($result){
			$this->returnCode(0);
		}else{
			$this->returnCode('20090148');
		}
	}

	//	线下支付方式
	public function pay_type_list(){
		$this->is_existence();
		$village_id =  I('village_id');
        //线下支付方式
        $pay_type_list = D('House_village_pay_type')->where(array('village_id'=>$village_id))->select();

		if(!$pay_type_list){
			$this->returnCode('20090152');
		}else{
			$this->returnCode(0,$pay_type_list);
		}
	}

	/**
     * 通过图片获取车牌号
     *
     */
	//  https://aip.baidubce.com/rest/2.0/ocr/v1/license_plate
	// AppID      11500187
	// API Key    hnhtjMX0a28bbwm3laC3u8YR
	// Secret Key  mtCDQQHex0GtXNCzWpF1f91OOG3bYXZf 
    public function PlateApi(){
		$this->is_existence();

		import('@.ORG.aip.AipOcr');
		if (C('config.plate_house_open') != 1) {
			$this->returnCode('20090176');
		}

		if (C('config.plate_house_way') == 1) { // 平台配置
			$AppID = C('config.plate_house_appid');
			$APIKey = C('config.plate_house_api_key');
			$SecretKey = C('config.plate_house_secret_key');

		}else{ // 小区配置
			if ($_SESSION['house']['plate_open'] != 1) {
				$this->returnCode('20090176');
			}
			$AppID = $_SESSION['house']['plate_appid'];
			$APIKey = $_SESSION['house']['plate_api_key'];
			$SecretKey = $_SESSION['house']['plate_secret_key'];
		}

		if (!$AppID || !$APIKey || !$SecretKey) {
			$this->returnCode('20090177');
		}

		// 初始化识别接口
		$AipOcr = new AipOcr($AppID ,$APIKey,$SecretKey);
		 // 投票相关图片
		// 图像数据，base64编码，要求base64编码后大小不超过4M，最短边至少15px，最长边最大4096px,支持jpg/png/bmp格式
        if(!empty($_FILES) && $_FILES['image']['error'] != 4){
            $image = D('Image')->handle($this->_uid, 'app/plate', 1, array('size' => 10), false);
            if (!$image['error']) {
                $url = $image['url'];
            } else {
                $this->returnCode(1,array(), $image['message']);
            }
        }
        
        $path = '.'.$image['url']['image'];
		$image = file_get_contents($path);
		// $image = $_POST['image'];
		// 车牌识别
		$res = $AipOcr->licensePlate($image);


        // $res['image'] = $path;
		// $this->returnCode(0,$res);

        if ($res['words_result']['number']) {
			$this->returnCode(0,$res['words_result']['number']);
        } else {
			$this->returnCode('20090167');
        }
    }


}