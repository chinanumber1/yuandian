<?php
class DoorAction extends BaseAction{
	//	门禁设备列表
    public function door_list(){
        //门禁设置-查看 权限
        if (!in_array(226, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

    	$where['village_id'] = $this->house_session['village_id'];
    	$page		=	I('page',1);
    	$page_coun	=	I('page_coun',50);
    	$aDoor	=	D('House_village_door')->get_door($where,$page,$page_coun,'html');
    	$this->assign('door_list',$aDoor['door_list']);
    	$this->assign('pagebar',$aDoor['pagebar']);
		
		$condition_count_today['village_id'] = $this->house_session['village_id'];
		$condition_count_today['add_time'] = array('egt',strtotime(date('Y-m-d 00:00:00')));
		$today_count = M('House_village_open_door')->where($condition_count_today)->count();
		$this->assign('today_count',$today_count);
		
		$condition_count_yesterday['village_id'] = $this->house_session['village_id'];
		$condition_count_yesterday['add_time'] = array('between',strtotime(date("Y-m-d 00:00:00",strtotime("-1 day"))).','.strtotime(date('Y-m-d 00:00:00')));
		$yesterday_count = M('House_village_open_door')->where($condition_count_yesterday)->count();
		$this->assign('yesterday_count',$yesterday_count);
		
		$this->display();
	}
	//	新增设备
	//public	function	door_add(){
//		if($_POST){
//			$_POST['village_id']	= $this->house_session['village_id'];
//			$add	=	D('House_village_door')->add_door($_POST);
//			if($add){
//				$this->success('新增设备成功！',U('Door/door_list'));exit;
//			}else{
//				$this->error('新增设备失败！');exit;
//			}
//		}else{
//			//	获取小区的楼层列表
//			$arr['village_id']	=	$this->house_session['village_id'];
//    		$arr['status']		=	1;
//    		$aFloor	=	M('House_village_floor')->field(array('floor_name','floor_layer'))->where($arr)->select();
//    		$this->assign('aFloor',$aFloor);
//		}
//		$this->display();
//	}
	//	修改设备
	public function door_eidt(){
		$where['door_id']		=	I('door_id');
		if(empty($where['door_id'])){
			$where['door_id']	=	$_GET['door_id'];
		}
		if(empty($where)){
			$this->error('设备未找到');exit;
		}
		if($_POST){
	        //门禁设置-修改 权限
	        if (!in_array(227, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');exit;
	        }

			$data['floor_id']	=	$_POST['floor_id'];
			$data['door_name']	=	$_POST['door_name'];
			$data['door_status']=	$_POST['door_status'];
			$data['all_status']	=	$_POST['all_status'];
			if($_POST['location']){
				$locationArr = explode(',',trim($_POST['location']));
				$data['lng'] = $locationArr[0];
				$data['lat'] = $locationArr[1];
			}
			$aSave	=	D('House_village_door')->save_door($where,$data);
			if($aSave == 99){
				$this->success('修改设备成功！',U('door_list'));exit;
			}else{
				$this->error('修改设备失败！');exit;
			}
		}else{
	        //门禁设置-查看 权限
	        if (!in_array(226, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');exit;
	        }

			$aDoor	=	D('House_village_door')->get_one_door($where);
			$this->assign('aDoor',$aDoor);
			//	获取小区的楼层列表
			$arr['village_id']	=	$this->house_session['village_id'];
    		$arr['status']		=	1;
    		$aFloor	=	M('House_village_floor')->field(array('floor_id','floor_name','floor_layer'))->where($arr)->select();
    		$this->assign('aFloor',$aFloor);
    		$this->display();
		}
	}
	//	查看设备的用户
	public function door_user(){
        //查看用户 权限
        if (!in_array(228, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

		$where['door_fid']		=	I('door_id');
		$page		=	I('page',1);
		$page_coun		=	I('page_coun',10);
		if(empty($where)){
			$this->error('设备未找到');exit;
		}
		$aDoor	=	D('House_village_door')->get_door_user($where,$page,$page_coun,'html');
		$this->assign('aDoor',$aDoor['list']);
		$this->assign('pagebar',$aDoor['pagebar']);
		$this->assign('door_id',$where['door_fid']);
		$this->display();
	}
	//	查看设备的用户
	public function door_user_add(){
        //用户-添加 权限
        if (!in_array(229, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

		//	查询楼的门禁ID
		$where['door_id']		=	I('door_id');
		$floor_id	=	M('House_village_door')->field(array('floor_id'))->where($where)->find();
		//	查询属于这个门禁的用户
		$where_user	=	array(
			'village_id'	=>	$this->house_session['village_id'],
			'floor_id'	=>	$floor_id['floor_id'],
		);
		$aUser	=	M('House_village_user_bind')->field(array('pigcms_id','name','address','uid'))->where($where_user)->select();
		//	循环这些用户进行匹配
		foreach($aUser as $k=>$v){
			$aDoorUser	=	M('House_village_door_user')->field(array('pigcms_id'))->where(array('user_id'=>$v['pigcms_id'],'door_fid'=>$where['door_id']))->find();
			if($aDoorUser){
				unset($aUser[$k]);
				continue;
			}else{
				$avatar	=	M('User')->field(array('avatar'))->where(array('uid'=>$v['uid']))->find();
				if($avatar){
					$aUser[$k]['avatar']	=	$avatar['avatar'];
				}else{
					$aUser[$k]['avatar']	=	$avatar['avatar'];
				}
			}
		}
		$this->assign('aUser',$aUser);
		$this->assign('door_id',$where['door_id']);
		$this->display();
	}
	//	门禁设备新增用户
	public function door_add_user(){
        //用户-添加 权限
        if (!in_array(229, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

		$pigId	=	$_POST['pigcms'];
		$arr['door_fid']	=	$_POST['door_id'];
		if(empty($pigId)){
			$this->error('请选择用户');exit;
		}
		if(empty($arr)){
			$this->error('门禁ID为空');exit;
		}
		$aId	=	explode(',',$pigId);
		foreach($aId as $k=>$v){
			$arr['user_id']	=	$v;
			$aFind	=	M('House_village_door_user')->field(array('pigcms_id'))->where($arr)->find();
			if($aFind){
				continue;
			}
			$arr['status']	=	1;
			$arr['start_time']	=	time();
			$add	=	M('House_village_door_user')->data($arr)->add();
			if(empty($add)){
				$this->error('添加用户失败');exit;
			}
		}
		$this->success('新增设备成功！',U('door_user',array('door_id'=>$arr['door_fid'])));exit;
	}
	//	门禁设备新增用户
	public function door_del_user(){
        //用户-删除 权限
        if (!in_array(231, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');exit;
        }

		$where['pigcms_id']	=	$_GET['pigcms_id'];
		if(empty($where)){
			$this->error('请选择要删除的用户');exit;
		}
		$aDelete	=	M('House_village_door_user')->where($where)->delete();
		if(empty($aDelete)){
			$this->error('删除用户失败');exit;
		}else{
			$this->success('删除用户成功');exit;
		}
	}
	//	门禁设备修改用户
	public function door_eidt_user(){
		$door_id		=	$_GET['door_id'];
		$where['pigcms_id']	=	$_GET['pigcms_id'];
		if($_POST){
	        //用户-编辑 权限
	        if (!in_array(230, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');exit;
	        }

			$data['start_time']	=	strtotime($_POST['start_time']);
			$data['end_time']	=	strtotime($_POST['end_time'])+86399;
			$data['status']	=	$_POST['status'];
			$aSave	=	M('House_village_door_user')->where($where)->data($data)->save();
			if($aSave){
				$this->success('修改用户成功',U('door_user',array('door_id'=>$door_id)));exit;
			}else{
				$this->error('修改用户失败');exit;
			}
		}else{
	        //用户-查看 权限
	        if (!in_array(228, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');exit;
	        }

			if(empty($where)){
				$this->error('请选择要修改的用户');exit;
			}
			$aFind	=	M('House_village_door_user')->field(true)->where($where)->find();
			if(empty($aFind)){
				$this->error('未找到这个用户');exit;
			}
			$this->assign('aFind',$aFind);
			$this->assign('door_id',$door_id);
			$this->display();
		}
	}
	
	//门禁记录
	public function door_open_list(){
		$nowDoor = M('House_village_door')->where(array('door_id'=>$_GET['door_id'],'village_id'=>$this->house_session['village_id']))->find();
		// dump($nowDoor);
		if(empty($nowDoor)){
			$this->error('门禁设备不存在');
		}
		if($nowDoor['floor_id'] != '-1'){
			$nowDoor['floor_info'] = M('House_village_floor')->where(array('floor_id'=>$nowDoor['floor_id']))->find();
		}
		$this->assign('nowDoor',$nowDoor);
		
		//查询下一个门禁
		$nextDoor = M('House_village_door')->where(array('door_id'=>array('gt',$nowDoor['door_id']),'village_id'=>$this->house_session['village_id']))->order('`door_id` ASC')->find();
		$this->assign('nextDoor',$nextDoor);
		
		//门禁记录列表
		$count = M('House_village_open_door')->where(array('door_device_id'=>$nowDoor['door_device_id']))->count();
		
		import('@.ORG.merchant_page');
		$p = new Page($count,'50','page');
		// dump($p);
		
		$condition_table = array(C('DB_PREFIX') . 'house_village_open_door' => 'd', C('DB_PREFIX') . 'user' => 'u');
		
		$condition_where = "`d`.`door_device_id`='".$nowDoor['door_device_id']."' AND `d`.`uid`=`u`.`uid`";
		
		$door_list = M()->field('`d`.*,`u`.`phone`')->table($condition_table)->where($condition_where)->order('`d`.`add_time` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		// dump($door_list);
		// echo M()->getLastSql();
		$this->assign('door_list',$door_list);
		$this->assign('pagebar',$p->show());
		
		$this->display();
	}
}