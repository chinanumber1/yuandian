<?php
class House_village_doorModel extends Model{
	//	获取门禁设备的列表
	private $open_door_status = array(0=>'开门成功',1=>'扫描失败',2=>'连接失败',3=>'重连失败',4=>'获取不到蓝牙关键字');
	public	function	get_door($where,$page,$page_coun,$device='app'){
		if(!$where){
			return	3;
		}
		$count_arr	=	$this->where($where)->count();
		if(!$count_arr){
			return	2;
		}
		
		if($_GET['sort'] == 'last_open_time'){
			$sort = '`last_open_time` DESC';
		}else{
			$sort = '`door_id` ASC';
		}
		
		$aDoor	=	$this->field(true)->page($page,$page_coun)->where($where)->order($sort)->select();
		foreach($aDoor as $k=>$v){
			if ( $v['floor_id'] == -1) {
				$aDoor[$k]['floor_name']	=	'小区';
				$aDoor[$k]['floor_layer']	=	'大门';
			}else{				
				$aFloor	=	M('House_village_floor')->field(array('floor_name','floor_layer'))->where(array('floor_id'=>$v['floor_id']))->find();
				$aDoor[$k]['floor_name']	=	$aFloor['floor_name'] ? $aFloor['floor_name'] : '';
				$aDoor[$k]['floor_layer']	=	$aFloor['floor_layer'] ? $aFloor['floor_layer'] : '';
			}
		}
		if($aDoor){
			if($device == 'app'){
				$arr	=	array(
					'totalPage'	=>	ceil($count_arr/$page_coun),
					'page'		=>	intval($page),		//当前页面
					'count'		=>	count($aDoor),	//当前条数
					'door_list'	=>	isset($aDoor)?$aDoor:array(),	//顺风车列表
				);
			}else{
				import('@.ORG.merchant_page');
				$p = new Page($count_arr,$page_coun,'page');
				$arr	=	array(
					'door_list'	=>	isset($aDoor)?$aDoor:array(),
					'pagebar'	=>	$p->show(),
				);
			}
			return	$arr;
		}else{
			return	2;
		}
	}
	//	获取单个设备
	public	function	get_one_door($where){
		if(!$where){
			return	1;
		}
		$aDoor	=	$this->field(true)->where($where)->find();
		if($aDoor){
			return	$aDoor;
		}else{
			return	2;
		}
	}
	//	添加门禁设备
	public	function	add_door($data){
		if(!$data){
			return	3;
		}
		$addDoor	=	$this->data($data)->add();
		if($addDoor){
			return	$addDoor;
		}else{
			return	0;
		}
	}
	//	修改门禁设备
	public	function	save_door($where,$data){
		if(!$where || !$data){
			return	4;
		}
		$addDoor	=	$this->where($where)->data($data)->save();
		if($addDoor == 0){
			return	2;
		}else if($addDoor){
			return	99;
		}else{
			return	3;
		}
	}
	//	删除门禁设备
	public	function	del_door($where){
		if(!$where){
			return	3;
		}
		$delDoor	=	$this->where($where)->delete();
		if($delDoor){
			return	99;
		}else{
			return	2;
		}
	}
	//	查询栋的列表
	public	function	floor_list($where,$page,$page_coun){
		if(!$where){
			return	3;
		}
		$count_arr	=	M('House_village_floor')->where($where)->count();
		$aFloorList	=	M('House_village_floor')->field(true)->page($page,$page_coun)->where($where)->select();
		if($aFloorList){
			foreach($aFloorList as $k=>$v){
				$aFloorList[$k]['add_time_s']	=	date('Y-m-d H:i',$v['add_time']);
			}
			$arr	=	array(
				'totalPage'	=>	ceil($count_arr/$page_coun),
				'page'		=>	intval($page),		//当前页面
				'count'		=>	count($aFloorList),	//当前条数
				'door_list'	=>	isset($aFloorList)?$aFloorList:array(),	//顺风车列表
			);
			return	$arr;
		}else{
			return	2;
		}
	}
	//	获取门禁设备的用户
	public	function	get_door_user($where,$page,$page_coun,$device='app'){
		if(!$where){
			return	3;
		}
		$count_arr	=	M('House_village_door_user')->where($where)->count();
		if(!$count_arr){
			return	2;
		}
		$aDoor	=	M('House_village_door_user')->field(true)->page($page,$page_coun)->where($where)->select();
		foreach($aDoor as $k=>$v){
			$aFloor	=	M('House_village_user_bind')->field(array('name','address','room_addrss'))->where(array('pigcms_id'=>$v['user_id']))->find();
			$aDoor[$k]['name']	=	$aFloor['name'];
			$aDoor[$k]['address']	=	$aFloor['address'];
			$aDoor[$k]['room_addrss']	=	$aFloor['room_addrss'];
		}
		if($aDoor){
			if($device == 'app'){
				$arr	=	array(
					'totalPage'	=>	ceil($count_arr/$page_coun),
					'page'		=>	intval($page),		//当前页面
					'count'		=>	count($aDoor),	//当前条数
					'door_list'	=>	isset($aDoor)?$aDoor:array(),	//顺风车列表
				);
			}else{
				import('@.ORG.merchant_page');
				$p = new Page($count_arr,$page_coun,'page');
				$arr	=	array(
					'list'	=>	isset($aDoor)?$aDoor:array(),
					'pagebar'	=>	$p->show(),
				);
			}
			return	$arr;
		}else{
			return	2;
		}
	}

	//开门历史
	public function door_open_history($door_id,$time_condition=0,$limit=0){
		$where = array('door_device_id'=>$door_id);
		if($time_condition){

		}

		$door_history = M('House_village_open_door')->field('floor_id,village_id,door_device_id,open_status,phone_plat,phone_version,phone_brand,add_time')
				->where($where)->limit($limit)->order('id DESC')->select();

		$door = array();
		$h = array();
		$open_by_date = array();
		foreach ($door_history as &$his) {

			if($h[$his['open_status']]){
				$h[$his['open_status']]['counts']++;
			}else{
				$his['status_txt'] = $this->open_door_status[$his['open_status']];
				$h[$his['open_status']] = $his;
				$h[$his['open_status']]['counts'] = 1;
			}

			$time_r = date('Y-m-d H',$his['add_time']);
			if($open_by_date[$time_r]){
				$open_by_date[$time_r]['counts']++;
			}else{
				$open_by_date[$time_r] = $his;
				$open_by_date[$time_r]['counts'] = 1;
				$open_by_date[$time_r]['status_txt'] =$this->open_door_status[$his['open_status']] ;
			}

			if($his['open_status']==0){
				$door[0] = $h[$his['open_status']];
				$door[0]['status_txt'] ='成功';
			}else{
				if($door[1]){
					$door[1]['counts'] ++;
				}else{
					$door[1] = $h[$his['open_status']];
					$door[1]['counts'] =1;
				}
				$door[1]['status_txt'] ='失败';
			}
		}
		return array('history'=>$h,'door_success_fail'=>$door,'open_by_date'=>$open_by_date,'all_count'=>count($door_history));

	}

	//获取用户可以开的门禁
	public function get_user_door($uid,$village_id=0){

		$where['uid']	=	$uid;
		$village_id && $where['village_id']  =$village_id;
		$aUserSelect	=	M('House_village_user_bind')->distinct(true)->where($where)->getField('village_id,floor_id,property_price,property_endtime,phone,type');

		$now_user = D('User')->get_user($uid);
		$aFloor	=	M('House_village_floor')->field(array('floor_name','floor_layer'))->getField('floor_id,floor_name,floor_layer,village_id');
		$work_condition = array('phone'=>$now_user['phone'],'status'=>array('neq',4));
		$village_id && $work_condition['village_id']  =$village_id;
		$worker_info = M('House_worker')->where($work_condition)->getField('village_id,wid,phone,status,open_door');

		$aDoor = array();

		//user_type 1 用户 2 工作人员 3 （1，2）合并
		foreach ($aUserSelect as &$item) {
			if($worker_info[$item['village_id']]){
				$item = array_merge($item,$worker_info[$item['village_id']]);
				$item['user_type']=3;
			}else{
				$item['user_type']=1;
			}
		}


		foreach ($worker_info as &$v) {
			if(!$aUserSelect[$v['village_id']]){
				$v['user_type'] = 2;
				$aUserSelect[$v['village_id']] = $v;
			}
		}

		if($aUserSelect){

			foreach ($aUserSelect as $w) {
				$village_arr[] = $w['village_id'];
				$ban_open_status = $w['open_door']?$w['open_door']:true;

				if($w['user_type']==2  ){
					$ban_open_status = !$w['open_door'];
				} else{
					$now_house = M('House_village')->where(array('village_id'=>$w['village_id']))->find();

					if(!$w['property_endtime'] || $now_house['owe_property_open_door']){
						$ban_open_status = false;
					}else{
						$owe_property_days = (strtotime(date('y-m-d',time()))-strtotime(date('y-m-d',$w['property_endtime'])))/86400;

						$ban_open_status = true;
						if(!$now_house['owe_property_open_door'] &&(time()>$w['property_endtime'])){
							$ban_open_status = false;
						}else if($now_house['owe_property_open_door_day']!=0 && $owe_property_days>0 && $owe_property_days > $now_house['owe_property_open_door_day']){
							$ban_open_status = false;
						}
					}
				}
				$ban_open_status_arr[$w['village_id']] = $ban_open_status;
				$user_door[] = $w['floor_id'];
			}

			$condition_door['door_status']	=	1;
			$condition_door['village_id']	=	array('in',$village_arr);
			$aDoorList	=	$this->distinct(true)->field(true)->where($condition_door)->select();

			foreach($aDoorList as $kk=>&$vv){
				// $userWhere	=	array(
				// 'user_id'	=>	$uid,
				// 'door_fid'	=>	$vv['door_id'],
				// );
				// $aDoorFind	=	M('House_village_door_user')->field(true)->where($userWhere)->find();
				if($ban_open_status_arr[$vv['village_id']]){
					$vv['open_status']	=	3;//欠物业费
					$vv['open_status_txt']	=	'您已欠物业费不能开门';
				}elseif(in_array($vv['floor_id'],$user_door) || $vv['floor_id']==-1 || $aUserSelect[$vv['village_id']]['user_type']>1){
					$vv['open_status']	=	1;
					$vv['open_status_txt']	=	'可以打开';
					$aDoor[]	=	$vv;
				}else if($aDoorFind['status'] == 1){
					if($aDoorFind == 0 || time()>$aDoorFind['end_time']){
						$vv['open_status']	=	1;	//允许使用
						$vv['open_status_txt']	=	'可以打开';
						$aDoor[]	=	$vv;
					}else{
						$vv['open_status']	=	2;	//时间过期
						$vv['open_status_txt']	=	'已过期，请联系物业';
					}
				}else{
					$vv['open_status']	=	0;		//禁止使用
					$vv['open_status_txt']	=	'您没有权限开门，请联系物业';		//禁止使用
				}
//                $tmp[$vv['village_id']]	=	$vv;
				if($vv['floor_id'] != "-1"){

					$vv['floor_name']	=	strval($aFloor[$vv['floor_id']]['floor_name']);
					$vv['floor_layer']	=	strval($aFloor[$vv['floor_id']]['floor_layer']);
				}else{
					$vv['floor_name']	=	'小区';
					$vv['floor_layer']	=	'大门';
				}
			}
		}
		return $aDoor;
	}

	//检查开门情况  	$fail :0成功率100%  1 完全开不开 2 错误率较大  3 不是全成功 4没有开门历史 （暂不处理）
	public function check_fail_door($uid){
		$door_list  =$this->get_user_door($uid);

		$fail_door = [];
		foreach ($door_list as $d) {
			$history = $this->door_open_history($d['door_device_id']);
			if(empty($history['history'])){
				continue;
			}
			$open_time = $this->format_time($history['open_by_date']);
			$same_time_open_percent =$this->same_time_open_status($_SERVER['REQUEST_TIME'],$open_time);
			$next_open_door =$this->check_user_open_door_relation($uid,$d['door_device_id']);

			$fail = $this->check_open_door_history($history);
			if($fail>0){
				$fail_door[] = array(
					'door_device_id' => $d['door_device_id'],
					'status' => $fail,
					'fail_percent'=>$history['door_success_fail'][1]['counts']/$history['all_count'],
					'same_time_open_percent'=>$same_time_open_percent,
					'percent_weighting'=>$same_time_open_percent*$history['door_success_fail'][1]['counts']/$history['all_count'],
					'next_open_door'=>$next_open_door,
				);
			}
		}
		if(count($fail_door)==1){
			return $fail_door;
		}else if(count($fail_door)>0){
			$fail_door = sortArrayAsc($fail_door,'fail_percent');
			$fail_door_ = sortArrayAsc($fail_door,'percent_weighting');
			if($fail_door[0]==$fail_door_[0]){
				return $fail_door[0];
			}else{
				foreach ($fail_door as $fd) {
					if($fd['door_device_id']==$fd['next_open_door']){
						return $fd;
					}
				}
				return $fail_door_[0];
			}
		}else{
			return false;
		}

	}

	//短时间内连续开门情况
	public function check_open_door_continuous($door_device_id){
		$history = $this->door_open_history($door_device_id, '', 10);
		$fail = $this->check_open_door_history($history);
		return $fail;
	}

	//
	public function check_open_door_history($history){
		if(empty($history['history'])) {
			$fail = 4;
		}else if($history['door_success_fail'][0]['counts']==0){
			$fail = 1;
		}else if($history['door_success_fail'][0]['counts']<$history['door_success_fail'][1]['counts']){
			$fail = 2;
		}else if($history['door_success_fail'][1]['counts']==0){
			$fail = 0;
		}else {
			$fail = 3;
		}
		return $fail;
	}

	//统一时间段的开门情况
	public function same_time_open_status($open_time,$open_by_date){
		$time_key = date('G',$open_time);
		$open_by_date = $this->format_time($open_by_date);


		$arr = [];
		$arr[0]['counts'] = 0;
		$arr[1]['counts'] = 0;
		foreach ($open_by_date[$time_key] as $item) {
			if($item['open_status']==0){
				if($arr[0]['counts']){
					$arr[0]['counts'] ++;
				}else{
					$arr[0]['counts']=1;
				}
			}else{
				if($arr[1]['counts']){
					$arr[1]['counts'] ++;
				}else{
					$arr[1]['counts']=1;
				}
			}
		}
		if(empty($open_by_date[$time_key] )){
			return 1;
		}

		return $arr[1]['counts']/($arr[0]['counts']+$arr[1]['counts']);
	}

	//检查用户们逻辑情况 返回可能的门 有可能找不到相似的逻辑
	public function check_user_open_door_relation($uid=0,$village_id=0){
		$open_history = M('House_village_open_door')->where(array('uid'=>$uid,'village_id'=>$village_id))->order('id DESC')->limit(20)->getField('id,door_device_id');
		$door_list = $this->where(array('village_id'=>$village_id))->getField('door_device_id,door_id');
		$recent_door_history = M('House_village_open_door')->where(array('uid'=>$uid,'village_id'=>$village_id))->order('id DESC')->limit(3)->getField('id,door_device_id');

		$door_key=1;
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXY';
		$tmp_door_list = [];
		foreach ($door_list as $key=>&$item) {
			$tmp_key = $door_key%48;
			$tmp_key_ = intval($door_key/48);
			if($tmp_key_>0 && $tmp_key>0){
				$tmp_door_list[$key] = array(
					'door_device_id'=>$key,
					'door_id'=>$item,
					'tmp_key'=> $chars[$tmp_key-1].($door_key-47),
				);
			}else{
				$tmp_door_list[$key] = array(
					'door_device_id'=>$key,
					'door_id'=>$item,
					'tmp_key'=> $chars[$tmp_key-1],
				);
			}
			$door_key++;
		}
		$str='';
		$recent_open_str='';

		foreach($open_history as $h){
			$str.=$tmp_door_list[$h]['tmp_key'];
		}

		foreach ($recent_door_history as $item) {
			$recent_open_str .=$tmp_door_list[$item]['tmp_key'];;
		}

		$map = [];
		$len = strlen($str);
		$flag['num'] = 0;
		$flag['str'] = '';
		$flag['len'] = 0;

		for($i = 0; $i < $len-1; $i++){

			for ($j = 2; $j < $len - 1 - $i; $j++) {
				$tar = substr($str, $i, $j);
				
				$count = substr_count($str, $tar);
				$tmp_str = $str;
				$count_=0;
				for($h=0;$h<$count;$h++){
					$key = strpos($tmp_str,$tar);

					$next_str =  substr($tmp_str,$key+strlen($tar),1);

					if(!is_numeric($next_str)){
						$count_++;
					}
					$tmp_str = substr($tmp_str,$key+strlen($tar));

				}
				//1 次以上
				if($count_>1 && empty($arr[$tar] )){
					$map[$tar]  =$count_;
				}
			}
		}
		arsort($map);
		foreach ($map as $key_=>$m) {
			if(strpos($key_,$recent_open_str)!==false){
				if($key_!=$recent_open_str){
					$next_tmp_key = $key_[strlen($recent_open_str)];
					foreach ( $tmp_door_list as $door_) {
						if($door_['tmp_key']==$next_tmp_key){
							return $door_['door_device_id'];
						}
					}
				}
			}
		}

		return false;

	}

	public function format_time($open_by_date){

		foreach ($open_by_date as $key=>$v) {
			$time_f = date('G',strtotime($key.':00:00'));
			$arr[$time_f][] = $v;
		}
		return $arr;
	}






}
?>
