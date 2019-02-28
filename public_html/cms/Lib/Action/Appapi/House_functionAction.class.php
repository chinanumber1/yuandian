<?php
/*
 * 社区功能管理
 *
 */
class House_functionAction extends BaseAction{
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
				$where['add_time'] = array('between',array($start_time,$end_time));
			}else if($start_time){
				$start_time = strtotime($start_time);
				$where['add_time'] = array('egt',$start_time);
			}else if($end_time){
				$end_time = strtotime($end_time.'23:59:59');
				$where['add_time'] = array('lt',$end_time);
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
	public	function	visitor_list(){
		$this->is_existence();
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
			if(!$list['list']){
				foreach($list['list'] as $k=>$v){
					$list['list'][$k]['idd']	=	$v['id'];
				}
				$this->returnCode('20090066');
			}else{
				$this->returnCode(0,$list);
			}
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
			$house_village_visitor_list[$k]['idd']	=	$v['id'];
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
		$village_id = I('village_id');
		$has_visitor = $this->getHasConfig($village_id, 'has_visitor');
		$owner = D('House_village_user_bind')->house_village_user_bind_detail(array('phone'=>$_POST['owner_phone']));


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
		$village_id = I('village_id');
		$id = I('idd');
		if(!$id){
            $this->returnCode('20090077');
        }
		$has_visitor = $this->getHasConfig($village_id, 'has_visitor');
		if($has_visitor){
	        $database_house_village_visitor = D('House_village_visitor');
	        $status = I('status',2);
	        $result = M('House_village_visitor')->where(array('id'=>$id))->data(array('status'=>$status))->save();
	        if(empty($result)){
	            $this->returnCode('20090078');
	        }else{
	            $this->returnCode(0);
	        }
		}else{
			$this->returnCode('20090065');
		}
	}
}