<?php
/*
 * 社区门禁功能 2.0
 *
 */
class H_doorAction extends BaseAction{
	//	获取门禁列表
    public function get_door(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(226, $this->power)){    		
			$this->returnCode('20090103');
    	}
    	
		$village_id		=	I('village_id');
		$page	=	I('page',1);
		$floor_id	=	I('floor_id');
		$door_device_id	=	I('door_device_id');
		$door_name	=	I('door_name');
		$page_coun	=	I('page_coun',10);
		$arr	=	array(
			'village_id'=>$village_id,
		);
		if($floor_id){
			$arr['floor_id']	=	$floor_id;
		}
		if($door_device_id){
			$arr['door_device_id']	=	$door_device_id;
		}
		if($door_name){
			$arr['door_name']	=	array('like','%{$door_name}%');
		}
		$aDoor	=	D('House_village_door')->get_door($arr,$page,$page_coun);
		if($aDoor==2){
			$this->returnCode('20090082');
		}else if($aDoor==3){
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$aDoor);
	}
	//	新增门禁
    public function add_door(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(259, $this->power)){    		
			$this->returnCode('20090103');
    	}

		$arr	=	array(
			'village_id'	=>	I('village_id'),
			'door_device_id'=>	I('door_device_id'),
			'door_name'		=>	I('door_name'),
			'door_psword'	=>	I('door_psword'),
			'door_status'	=>	I('door_status',1),
			'all_status'	=>	I('all_status',1),
			'floor_id'		=>	I('floor_id'),
			'lng'		    =>	I('lng'),
			'lat'		    =>	I('lat'),
			'add_time'		=>	time(),
		);
		if(empty($arr['door_device_id'])){
			$this->returnCode('20090094');
		}else if(empty($arr['door_name'])){
			$this->returnCode('20090083');
		}else if(empty($arr['door_psword'])){
			$this->returnCode('20090084');
		}else if(empty($arr['floor_id'])){
			$this->returnCode('20090096');
		}
		$oneDoor	=	D('House_village_door')->get_one_door(array('door_device_id'=>$arr['door_device_id']));
		if($oneDoor != 2){
			$village = D('House_village')->where(array('village_id'=>$oneDoor['village_id']))->find();
			// $this->returnCode('20090097');
			$this->returnCode('1','','设备已在'.$village['village_name'].'小区存在');
		}
		$addDoor	=	D('House_village_door')->add_door($arr);
		if($addDoor==3){
			$this->returnCode('20090089');
		}else if($addDoor==0){
			$this->returnCode('20090090');
		}
		$this->returnCode(0);
	}
	//	修改门禁
    public function save_door(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(227, $this->power)){    		
			$this->returnCode('20090103');
    	}
    	
		$door_id['door_id']	=	I('door_id');
		if(empty($door_id)){
			$this->returnCode('20044004');
		}
		$arr	=	array(
			'door_name'		=>	I('door_name'),
			'door_psword'	=>	I('door_psword'),
			'door_status'	=>	I('door_status'),
			'all_status'	=>	I('all_status'),
			'floor_id'		=>	I('floor_id'),
			'lng'		    =>	I('lng'),
			'lat'		    =>	I('lat'),
		);
		foreach($arr as $k=>$v){
			if(empty($v)){
				if($v != 0){
					unset($arr[$k]);
				}
			}
		}
		$saveDoor	=	D('House_village_door')->save_door($door_id,$arr);
		if($saveDoor==1){
			$this->returnCode('20090089');
		}else if($saveDoor==2){
			$this->returnCode('20090091');
		}else if($saveDoor==3){
			$this->returnCode('20090092');
		}
		$this->returnCode(0);
	}
	//	删除门禁
    public function del_door(){
		$this->is_existence();
    	//验证权限
    	if(!in_array(260, $this->power)){    		
			$this->returnCode('20090103');
    	}
    	
		$door_id['door_id']	=	I('door_id');
		if(empty($door_id)){
			$this->returnCode('20044004');
		}
		$delDoor	=	D('House_village_door')->del_door($door_id);
		if($delDoor==3){
			$this->returnCode('20090089');
		}else if($delDoor==2){
			$this->returnCode('20090093');
		}
		$this->returnCode(0);
	}
	//	楼层列表
	public	function	floor_list(){
		$this->is_existence();
		$where['village_id']	=	I('village_id');
		$page		=	I('page',1);
		$page_coun	=	I('page_coun',10);
		$aFloorList	=	D('House_village_door')->floor_list($where,$page,$page_coun);
		if($aFloorList == 2){
			$this->returnCode('20090095');
		}else if($aFloorList == 3){
			$this->returnCode('30000001');
		}
		if($page == 1){
			$array = array(
				'floor_id' => '-1',
				'village_id' => '33',
				'floor_name' => '小区',
				'floor_layer' => '大门',
				'status' => '1',
				'add_time' => time(),
				'floor_type' => '0',
				'property_fee' => '0',
				'add_time_s' => date('Y-m-d H:i'),
			);
			array_unshift($aFloorList['door_list'],$array);
		}
		$this->returnCode(0,$aFloorList);
	}
}