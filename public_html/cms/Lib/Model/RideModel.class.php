<?php
class RideModel extends Model
{
	protected	$urlTot	=	'/tpl/Wap/default/static/bbs/img/tou.png';
	/*
	*	顺风车的接口
	*	统一返回值：
	*		1，条件
	*		2，根据条件，未搜索到数据
	*		99,在没有数据返回时，99代表成功
	*	$where	搜索条件，必须在action里组合完整
	*/
	//	搜索单条顺风车，也可以用作顺风车详情
	public function get_ride_one($where,$user_id=''){
		if(empty($where)){
			return	1;
		}
		$aRideDetails	=	$this->field(true)->where($where)->page($page,$page_coun)->find();
		if($aRideDetails){
			$avatar	=	M('user')->field(array('avatar'))->where(array('uid'=>$aRideDetails['user_id']))->find();
			if($avatar['avatar']){
				$aRideDetails['avatar']	=	$avatar['avatar'];
			}else{
				$aRideDetails['avatar']	=	$this->urlTot;
			}
			if($aRideDetails['ride_date_number'] == 1){
				$aRideDetails['start_date']	=	date('Y-m-d H:i',$aRideDetails['start_time']);
				$aRideDetails['start_time_s']	=	date('Y-m-d H:i',$aRideDetails['start_time']);
			}else{
				$aRideDetails['start_date']	=	date('H:i',$aRideDetails['start_time']);
				$aRideDetails['start_time_s']	=	date('Y-m-d',time()).' '.date('H:i',$aRideDetails['start_time']);
			}
			$aRideDetails['time']	=	date('Y-m-d H:i',$aRideDetails['time']);
			if((time()>$aRideDetails['start_time']) && ($aRideDetails['ride_date_number'] == 1)){
				$aRideDetails['is_time']	=	1;
			}else{
				$aRideDetails['is_time']	=	0;
			}
			$aRideOrder	=	$this->get_ride_order($where['ride_id'],$aRideDetails['up_time']);
			if($user_id	==	$aRideDetails['user_id']){
				$user	=	1;
			}else{
				$user	=	0;
			}
			$rideLog	=	M('Ride_order_log')->field(array('proportion'))->where(array('user_id'=>$aRideDetails['user_id']))->find();
			$arr	=	array(
				'ride_details'	=>	isset($aRideDetails)?$aRideDetails:array(),
				'ride_order'	=>	isset($aRideOrder)?$aRideOrder:array(),
				'user_id'		=>	$user,
				'ride_log'		=>	$rideLog,
			);
			return	$arr;
		}else{
			return	2;
		}
	}
	//	搜索顺风车列表，可以用作全部列表，搜索列表
	public	function	get_ride_list($where,$page=1,$page_coun=10,$order=array('start_time'=>'asc')){
		if(empty($where)){
			return	1;
		}
		$count_arr	=	$this->where($where)->count();
		$aRideList	=	$this->field(array('ride_id','ride_title','departure_place','destination','ride_date_number','start_time','ride_price','remain_number','user_id','owner_name','status'))
								->where($where)->order($order)->page($page,$page_coun)->select();
		if($aRideList){
			foreach($aRideList as $k=>$v){
				$avatar	=	M('user')->field(array('avatar'))->where(array('uid'=>$v['user_id']))->find();
				if(empty($v['avatar'])){
					$aRideList[$k]['avatar']	=	$this->urlTot;
				}else{
					$aRideList[$k]['avatar']	=	$avatar['avatar'];
				}
				if($v[ride_date_number] == 1){
					$aRideList[$k]['start_date']	=	date('Y-m-d H:i',$v['start_time']);
				}else{
					$aRideList[$k]['start_date']	=	date('H:i',$v['start_time']);
				}
				$aRideLog	=	M('Ride_order_log')->field(array('proportion'))->where(array('user_id'=>$v['user_id']))->find();
				$aRideList[$k]['proportion']	=	$aRideLog['proportion'];
				$aRideList[$k]['status_s']		=	$this->ride_status($v['status']);
			}
			$arr	=	array(
				'totalPage'	=>	ceil($count_arr/$page_coun),
				'page'		=>	intval($page),		//当前页面
				'count'		=>	count($aRideList),	//当前条数
				'ride_list'	=>	$aRideList,	//顺风车列表
			);
			return $arr;
		}else{
			return	2;
		}
	}
	//	发起顺风车，$data数据必须(以数组)在action全部传入
	public	function	get_ride_add($data){
		if(empty($data)){
			return	false;
		}
		$add	=	$this->data($data)->add();
		if($add == 0){
			return	false;
		}else{
			return	$add;
		}
	}
	//	修改顺风车，$data数据必须(以数组)在action全部传入
	public	function	get_ride_eidt($where,$data){
		if(empty($data)){
			return	1;
		}
		if(empty($where)){
			return	1;
		}
		$save	=	$this->where($where)->data($data)->save();
		if($save){
			return	99;
		}else if($save == 0){
			return	99;
		}else{
			return	2;
		}
	}
	//	查询顺风车
	public	function	get_ride_order_number($ride_id){
		if(empty($ride_id)){
			return	false;
		}
		$aRide	=	$this->field(true)->where($ride_id)->find();
		if($aRide){
			return	$aRide;
		}else{
			return	false;
		}
	}
	//	顺风车已预约的多个订单
	public	function	get_ride_order($ride_id,$time){
		if(empty($ride_id)){
			return	false;
		}
		$where	=	array(
			'ride_id'=>$ride_id,
			'up_time'=>$time,
		);
		$wheres['_complex']	=	$where;
		$wheres['_string']	=	'(status = 1) OR (status = 4)';
		$aRideOrder	=	M('Ride_order')->field(true)->order(array('order_time'=>'desc'))->where($wheres)->select();
		foreach($aRideOrder as $k=>$v){
			$aUser	=	M('user')->field(array('nickname','avatar'))->where(array('uid'=>$v['user_id']))->find();
			$aRideOrder[$k]['nickname']	=	$aUser['nickname'];
			if($aUser['avatar']){
				$aRideOrder[$k]['avatar']	=	$aUser['avatar'];
			}else{
				$aRideOrder[$k]['avatar']	=	$this->urlTot;
			}
		}
		if($aRideOrder){
			return	$aRideOrder;
		}else{
			return	array();
		}
	}
	//	获取顺风车的今天订单
	public	function	get_ride_day_order($ride_id,$data,$time){
		if(empty($data)){
			return	false;
		}
		$aRideOrder	=	M('Ride_order')->field(true)->where(array('ride_id'=>$ride_id,'status'=>1,'up_time'=>$time))->select();
		if($aRideOrder){
			return	$aRideOrder;
		}else{
			return	false;
		}
	}
	//	增加顺风车司机的日志
	public	function	add_ride_log($data){
		if(empty($data)){
			return	false;
		}
		$aLog	=	M('Ride_order_log')->data($data)->add();
		if($aLog){
			return	$aLog;
		}else{
			return	false;
		}
	}
	//	获取顺风车司机的日志
	public	function	get_ride_log($data){
		if(empty($data)){
			return	false;
		}
		$aLog	=	M('Ride_order_log')->field(true)->where($data)->find();
		if($aLog){
			return	$aLog;
		}else{
			return	false;
		}
	}
	//	修改顺风车司机的日志
	public	function	save_ride_log($data,$uid){
		if(empty($data)){
			return	false;
		}
		$aLog	=	M('Ride_order_log')->where($uid)->data($data)->save();
		if($aLog){
			return	$aLog;
		}else{
			return	false;
		}
	}
	//	新增顺风车订单
	public	function	ride_place_order($data=array()){
		if(empty($data)){
			return	false;
		}
		$add	=	M('Ride_order')->data($data)->add();
		if($add){
			return	true;
		}else{
			return	false;
		}
	}
	//	修改顺风车订单
	public	function	ride_order_save($where,$data){
		if(!$where){
			return	false;
		}
		if(!$data){
			return	false;
		}
		$rideSave	=	M('Ride_order')->where($where)->data($data)->save();
		if($rideSave	==	0){
			return	2;
		}else if(empty($rideSave)){
			return	3;
		}else{
			return	1;
		}
	}
	//	查询顺风车订单
	public	function	ride_order_select($where){
		if(!$where){
			return	false;
		}
		$aFind	=	M('Ride_order')->field(true)->where($where)->find();
		if($aFind){
			return	$aFind;
		}else{
			return	false;
		}
	}
	//	顺风车发送微信消息
	public	function	weixin($data){
		//	给司机发信息
		$userInfo	=	M('user')->field('openid')->where(array('phone'=>$data['owner_phone']))->find();
		//发送微信模板消息start
		if($data['visitor_name']){
			$work_info ='\n姓名为：'.$data['visitor_name'].'  手机号为：'.$data['visitor_phone'].'的乘客，已经预约了您'.$data['time'].'出发的顺风车';
		}else{
			$work_info ='\n手机号为：'.$data['visitor_phone'].'的乘客，已经预约了您的顺风车！';
		}
		if($userInfo['openid']){
			$href = C('config.site_url').'/wap.php?g=Wap&c=Ride&a=ride_history';
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$data['owner_name'].' 车主\n', 'work' => $work_info, 'remark' => '\n请点击查看详细信息！'));
		}
		//发送手机模板消息end
		$sms_data['uid'] = $data['owner_uid'];
		$sms_data['mobile'] = $data['owner_phone'];
		$sms_data['sendto'] = 'user';
		$sms_data['content'] = '车主，您好！有乘客预约了您'.$data['time'].'出发的顺风车。乘客姓名为：'.$data['visitor_name'].'，手机号码为'.$data['visitor_phone'].'。';
		Sms::sendSms(($sms_data));

		//	给乘客发信息
		$userInfos	=	M('user')->field('openid')->where(array('phone'=>$data['visitor_phone']))->find();
		//发送微信模板消息start
		if($data['owner_name']){
			$work_info ='\n您已经预约了，姓名为：'.$data['owner_name'].'  手机号为：'.$data['owner_phone'].'的顺风车，出发时间：'.$data['time'];
		}else{
			$work_info ='\n您已经预约了，手机号为：'.$data['owner_phone'].'的顺风车！';
		}
		if($userInfos['openid']){
			$href = C('config.site_url').'/wap.php?g=Wap&c=Ride&a=ride_carpooling';
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfos['openid'], 'first' => '您好，'.$data['visitor_name'].' 乘客\n', 'work' => $work_info, 'remark' => '\n请点击查看详细信息！'));
		}
		//发送手机模板消息end
		$sms_data_s['uid'] = $data['visitor_uid'];
		$sms_data_s['mobile'] = $data['visitor_phone'];
		$sms_data_s['sendto'] = 'user';
		$sms_data_s['content'] = '乘客，您好！您成功预约了'.$data['time'].'的出发顺风车。车主姓名为：'.$data['owner_name'].'，手机号码为'.$data['owner_phone'].'。';
		Sms::sendSms(($sms_data_s));
	}
	//	获取我的拼车记录
	public	function	get_ride_carpooling($where=array(),$page=1,$page_coun=10){
		if(!$where){
			return	false;
		}
		$count	=	M('Ride_order')->where($where)->count();
		if(empty($count)){
			return	false;
		}
		$aRideOrder	=	M('Ride_order')->where($where)->order(array('order_time'=>'desc'))->page($page,$page_coun)->select();
		foreach($aRideOrder as $k=>$v){
			$aRide		=	$this->field(array('start_time','departure_place','destination'))->where(array('ride_id'=>$v['ride_id']))->find();
			$start_time		=	date('m-d H:i',$aRide['start_time']);
			$aRideOrder[$k]['start_time']	=	$start_time;
			$aRideOrder[$k]['departure_place']	=	$aRide['departure_place'];
			$aRideOrder[$k]['destination']	=	$aRide['destination'];
			$aRideOrder[$k]['statuss']	=	$this->ride_order_status($v['status']);
		}
		if($aRideOrder){
			$arr	=	array(
				'totalPage'	=>	ceil($count/$page_coun),
				'page'		=>	intval($page),		//当前页面
				'count'		=>	count($aRideOrder),	//当前条数
				'ride_list'	=>	$aRideOrder,		//顺风车列表
			);
			return	$arr;
		}
		return	false;
	}
	//	获取我的拼车详情
	public	function	get_ride_details($where){
		if(!$where){
			return	false;
		}
		$aRideDetails	=	M('Ride_order')->field(true)->where($where)->find();
		if($aRideDetails){
			$aRideDetails['statuss']	=	$this->ride_order_status($aRideDetails['status']);
			$aRide		=	$this->field(true)->where(array('ride_id'=>$aRideDetails['ride_id']))->find();
			$avatar	=	M('User')->field(array('avatar'))->where(array('uid'=>$aRide['user_id']))->find();
			$driver	=	M('User_authentication_car')->where(array('uid'=>$aRide['user_id']))->find();
			if($avatar['avatar']){
				$aRide['avatar']	=	$avatar['avatar'];
			}else{
				$aRide['avatar']	=	$this->urlTot;
			}
			$aRide['start_date']	=	date('Y-m-d H:i',$aRide['start_time']);
			$aRide['time_s']	=	date('Y-m-d H:i',$aRide['time']);
			$aRide['car_number']	=	substr_replace($driver['front'],'***',0,-3);
			$aRideDetails['order_time_s']	=	date('Y-m-d H:i',$aRideDetails['order_time']);
			if($aRide['cancel_time'] == 0){
				$aRideDetails['order_date']	=	0;
			}else{
				if(($aRide['start_time']-$aRide['cancel_time']*60) > time()){
					$aRideDetails['order_date']	=	$aRide['cancel_time'];
				}else{
					$aRideDetails['order_date']	=	0;
				}
				//$aRideDetails['order_cancel_time_s']	=	$aRideDetails['order_cancel_time']	-	time();
//				if($aRideDetails['order_cancel_time_s']	> 0){
//					$aRideDetails['order_date']	=	(int)($aRideDetails['order_cancel_time_s']/60);
//				}else{
//					$aRideDetails['order_date']	=	0;
//				}
			}
			$ride_log	=	M('Ride_order_log')->field(true)->where(array('user_id'=>$aRide['user_id']))->find();
			$arr['ride_order']	=	$aRideDetails;
			$arr['ride']		=	$aRide;
			$arr['ride_log']		=	$ride_log;
			return	$arr;
		}else{
			return	false;
		}
	}
	//	用户退单发送消息(给司机发信息)
	public	function	weixin_user($data){
		//	给司机发信息
		$userInfo	=	M('user')->field('openid')->where(array('phone'=>$data['owner_phone']))->find();
		//发送微信模板消息start
		if($data['visitor_name']){
			$work_info ='\n姓名为：'.$data['visitor_name'].'，手机号为：'.$data['visitor_phone'].'的乘客，已经取消了您'.$data['time'].'出发的顺风车！';
		}else{
			$work_info ='\n手机号为：'.$data['visitor_phone'].'的乘客，已经取消了您'.$data['time'].'出发的顺风车！';
		}
		if($userInfo['openid']){
			$href = C('config.site_url').'/wap.php?g=Wap&c=Ride&a=ride_history';
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$data['owner_name'].' 车主\n', 'work' => $work_info, 'remark' => '\n请点击查看详细信息！'));
		}
		//发送手机模板消息end
		$sms_data['uid'] = $data['owner_uid'];
		$sms_data['mobile'] = $data['owner_phone'];
		$sms_data['sendto'] = 'user';
		$sms_data['content'] = '车主，您好！有乘客取消了您'.$data['time'].'出发的顺风车。乘客姓名为：'.$data['visitor_name'].'，手机号码为'.$data['visitor_phone'].'。';
		Sms::sendSms(($sms_data));
	}
	//	司机退单发送消息(给乘客发信息)
	public	function	weixin_driver($data){
		//	给司机发信息
		$userInfo	=	M('user')->field('openid')->where(array('phone'=>$data['visitor_phone']))->find();
		//发送微信模板消息start
		if($data['visitor_name']){
			$work_info ='\n姓名为：'.$data['owner_name'].'，手机号为'.$data['owner_phone'].'的车主，已经取消了您'.$data['time'].'出发的顺风车！';
		}else{
			$work_info ='\n手机号为'.$data['owner_phone'].'的车主，已经取消了您'.$data['time'].'出发的顺风车！';
		}
		if($userInfo['openid']){
			$href = C('config.site_url').'/wap.php?g=Wap&c=Ride&a=ride_history';
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$data['visitor_name'].' 乘客\n', 'work' => $work_info, 'remark' => '\n请点击查看详细信息！'));
		}
		//发送手机模板消息end
		$sms_data['uid'] = $data['visitor_uid'];
		$sms_data['mobile'] = $data['visitor_phone'];
		$sms_data['sendto'] = 'user';
		$sms_data['content'] = '乘客，您好！有车主取消了您'.$data['time'].'出发的顺风车。乘客姓名为：'.$data['owner_name'].'，手机号码为'.$data['owner_phone'].'。';
		Sms::sendSms(($sms_data));
	}
	//	顺风车订单状态更改
	private	function	ride_order_status($data){
		switch($data){
			case 1:
				$sData	=	'预定成功';
				break;
			case 2:
				$sData	=	'司机取消';
				break;
			case 3:
				$sData	=	'乘客取消';
				break;
			case 4:
				$sData	=	'已完成';
				break;
		}
		return	$sData;
	}
	//	顺风车状态更改
	private	function	ride_status($data){
		switch($data){
			case 1:
				$sData	=	'正常';
				break;
			case 2:
				$sData	=	'结束';
				break;
			case 3:
				$sData	=	'人满';
				break;
			case 4:
				$sData	=	'停止';
				break;
			case 5:
				$sData	=	'暂停';
				break;
			case 6:
				$sData	=	'关闭';
				break;
		}
		return	$sData;
	}
}
?>
