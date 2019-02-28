<?php
/*
 * 社区APP版	登录 2.0
 *
 */
class H_loginAction extends BaseAction{
	//	登录
    public function login(){
    	$ticket = I('ticket', false);
        $database_house = D('House_village'); 
        $database_admin = D('House_admin'); 

        //返回安卓版本信息 Android版本名称：village_android_version Android版本号：android_version_code Android版本描述：android_version_desc Android下载地址：android_download_url

        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info) {
                $uid = $info['uid']-10000000;
	            $type = substr($uid, 0,1);
	            $uid = substr($uid, 1);
	            if ($type==1) { // 主账号
                	$now_house = $database_house->field(true)->where(array('village_id'=>$uid))->find(); 

					//权限
                	$admin_menus = D('House_menu')->field(true)->where(array('status'=>1))->select();
	                $power = array();
	                foreach ($admin_menus as $value) {
	                    $power[] = $value['id'];
	                }
	            }elseif($type==2){ // 角色
                	$role = $database_admin->field(true)->where(array('id'=>$uid))->find();
                	$now_house = $database_house->field(true)->where(array('village_id'=>$role['village_id']))->find();
                	$now_house['account'] = $role['realname'] ? $role['realname'] : $role['account'];
                	//权限
                	$power = explode(',',$role['menus']);
	            }

	            //底部菜单
	            $footer = $this->getFooter($power);

                unset($now_house['pwd']);
                $arr = array(
                	'user'      => $now_house,
                	'footer'      => $footer,
                    'ticket'    => $ticket
                );
                $this->returnCode(0,$arr);
            }else{
				$this->returnCode('20000009');
            }
        }

		$condition_house['account'] = I('account');
		if($_POST['version_code']>=300){
			$village_id = $_POST['village_id'];

			if($village_id>0){
				$condition_house['village_id'] = $village_id;
			}
			$now_house = $database_house->field(true)->where($condition_house)->select();
			if ($now_house) { // 社区超级管理员登录
				if(count($now_house)==1){
					$now_house = $now_house[0];
				}else{
					$this->returnCode(0,array('village_list'=>$now_house));
				}
			}else{ // 角色登录
                $role = $database_admin->field(true)->where($condition_house)->find();

				if(empty($role)){
					$this->returnCode('20000005');
				}

				//权限
                $power = explode(',',$role['menus']);

	            //底部菜单
	            $footer = $this->getFooter($power);

				$pwd = md5(I('pwd'));
				if($pwd != $role['pwd']){
					$this->returnCode('20000006');
				}
				if($role['status'] == 2){
					$this->returnCode('20000007');
				}
                $now_house = $database_house->field(true)->where(array('village_id'=>$role['village_id']))->find();
				unset($now_house['pwd']);
				$role_id = '2'.$role['id']+10000000;
				$ticket = ticket::create($role_id, $this->DEVICE_ID, true);
            	$now_house['account'] = $role['realname'] ? $role['realname'] : $role['account'];
		        $arr	=	array(
					'user'	=>	$now_house,
                	'footer'      => $footer,
					'ticket'	=>	$ticket['ticket'],
		        );
				$data_house['id'] = $role['id'];
				$data_house['last_time'] = $_SERVER['REQUEST_TIME'];
				if($database_admin->data($data_house)->save()){
					// 更新设备号
		            $this->upDevice(2, $role['id']);

					$this->returnCode(0,$arr);
				}else{
					$this->returnCode('20000008');
				}
			}
		}else{
			$now_house = $database_house->field(true)->where($condition_house)->find();

			if (!$now_house) { // 角色登录
                $role = $database_admin->field(true)->where($condition_house)->find();
                // var_dump($database_admin->_sql());

				if(empty($role)){
					$this->returnCode('20000005');
				}

				//权限
                $power = explode(',',$role['menus']);
                
	            //底部菜单
	            $footer = $this->getFooter($power);

				$pwd = md5(I('pwd'));
				if($pwd != $role['pwd']){
					$this->returnCode('20000006');
				}
				if($role['status'] == 2){
					$this->returnCode('20000007');
				}
                $now_house = $database_house->field(true)->where(array('village_id'=>$role['village_id']))->find();
				unset($now_house['pwd']);
				$role_id = '2'.$role['id']+10000000;
				$ticket = ticket::create($role_id, $this->DEVICE_ID, true);
            	$now_house['account'] = $role['realname'] ? $role['realname'] : $role['account'];
		        $arr	=	array(
					'user'	=>	$now_house,
                	'footer'      => $footer,
					'ticket'	=>	$ticket['ticket'],
		        );
				$data_house['id'] = $role['id'];
				$data_house['last_time'] = $_SERVER['REQUEST_TIME'];
				if($database_admin->data($data_house)->save()){
					// 更新设备号
		            $this->upDevice(2, $role['id']);

					$this->returnCode(0,$arr);
				}else{
					$this->returnCode('20000008');
				}
			}
		}
		if(empty($now_house)){
			$this->returnCode('20000005');
		}

		//权限
		$admin_menus = D('House_menu')->field(true)->where(array('status'=>1))->select();
        $power = array();
        foreach ($admin_menus as $value) {
            $power[] = $value['id'];
        }

        //底部菜单
        $footer = $this->getFooter($power);

		$pwd = md5(I('pwd'));
		if($pwd != $now_house['pwd']){
			$this->returnCode('20000006');
		}
		if($now_house['status'] == 2){
			$this->returnCode('20000007');
		}
		unset($now_house['pwd']);
		$village_id	=	'1'.$now_house['village_id']+10000000;
		$ticket = ticket::create($village_id, $this->DEVICE_ID, true);
        $arr = array(
			'user'	=>	$now_house,
            'footer'      => $footer,
			'ticket'	=>	$ticket['ticket'],
        );
		$data_house['village_id'] = $now_house['village_id'];
		$data_house['last_time'] = $_SERVER['REQUEST_TIME'];
		if($database_house->data($data_house)->save()){
			// 更新设备号
            $this->upDevice(1, $now_house['village_id']);
            
			$this->returnCode(0,$arr);
		}else{
			$this->returnCode('20000008');
		}
	}

    // 获得首页底部菜单
	function getFooter($menu=array()){
		$arr = array(
			// 底部菜单
			'is_show' => false,
			'child' => array(
				'cashier' =>false, //收银台
				'door' =>false, //智慧门禁
			)
		);
		// 角色权限
		foreach ($menu as $value) {
			switch ($value) {
				case '66':
					$arr['is_show'] = true;
					$arr['child']['cashier'] = true;
					break;
				case '226':
					$arr['is_show'] = true;
					$arr['child']['door'] = true;
					break;
			}
		}
		return $arr;
	}


    //  更新user表里的Device-Id
    public function upDevice($type,$id){
        $userUpdata =   array(
            'device_id' =>  $this->DEVICE_ID,
        );
        if ($type==1) { // 总管理员
       	 	M('House_village')->where(array('village_id' => $id))->save($userUpdata);
        }else{
       	 	M('House_admin')->where(array('id' => $id))->save($userUpdata);
        }
    }
}