<?php
class	RideAction	extends	BaseAction{
	protected 	$village_id;
	protected 	$ride_is;
	protected 	$user_id;
	protected 	$user_session;
	protected 	$defaultImg;
	public function _initialize(){
		parent::_initialize();
		//获取社区的信息
		if($_SESSION['ride_village_id']){
			$this->village_id 	= 	$_SESSION['ride_village_id'];
		}else{
			$this->village_id 	= 0;
		}
		$this->assign('village_id',$this->village_id);
		//进入顺风车的网址
		if(empty($_SESSION['carReturnUrl'])){
			$this->assign('returnUrl',$_SESSION['carReturnUrl']);
		}

		//	查看是否开启顺风车功能
		$this->ride_is		=	$this->config['ride_is'];
		if($this->ride_is == 0){
			$this->assign('ride_is',0);
		}else{
			$this->assign('ride_is',$this->ride_is);
		}

		//	获取登录用户的信息
		$this->user_id		=	$this->user_session['uid'];
		if($this->user_session){
			$this->assign('userSession',$this->user_session);
		}else{
			$this->assign('userSession',0);
		}
		//	顺风车的默认图片
		$this->defaultImg	=	array(
			'urlCar'	=>	'/tpl/Wap/default/static/ride/img/car.png',
			'urlEnd'	=>	'/tpl/Wap/default/static/ride/img/end.png',
			'urlMe'		=>	'/tpl/Wap/default/static/ride/img/me.png',
			'urlStar'	=>	'/tpl/Wap/default/static/ride/img/star.png',
			'urlGo'		=>	'/tpl/Wap/default/static/ride/img/go.png',
		);
		$this->assign('defaultImg',$this->defaultImg);
		$this->ride_day_status();
		$this->ride_all_day_status();
	}
	//	更新发布一天的顺风车，小于当前时间，更改为完成
	public function ride_day_status(){
		$where	=	array(
			'ride_date_number'	=>	1,
			'start_time'	=>	array('lt',time()),
		);
		$wheres['_complex']	=	$where;
		$wheres['_string']	=	'(status = 1) OR (status = 3)';
		M('Ride')->where($wheres)->data(array('status'=>2))->save();
	}
	//	更新每日的顺风车
	//	条件1：刷新日期，小于 当前日期
	//	条件2：出发时间，小于 当前时间
	//	条件3：状态为1和3的顺风车
	public function ride_all_day_status(){
		$date	=	date('Ymd',time());
		$time	=	time();
		$update	=	"UPDATE `pigcms_ride`
					SET `start_time`=`start_time`+'86400', `status`='1', `up_time`='$date', `up_number`=`up_number`+1, `sit_number`='0', `remain_number`=`seat_number`
					WHERE ((`status`='1') OR (`status` = '3')) AND (`ride_date_number` = '2') AND (`start_time`<'$time') AND (`up_time`<'$date')";
		M('Ride')->execute($update);
	}
	//	拼车列表
	public function ride_list(){
		//判断来源
		if($_GET['plat']){
			$this->village_id = $_SESSION['ride_village_id'] = 0;
			$_SESSION['carReturnUrl'] = '';
		}else if($_GET['village_id']){
			$this->village_id = $_SESSION['ride_village_id'] = $_GET['village_id'];
			$_SESSION['carReturnUrl'] = U('House/village',array('village_id'=>$this->village_id));
		}else if($_SESSION['ride_village_id']){
			$this->village_id 	= 	$_SESSION['ride_village_id'];
		}else{
			$this->village_id = 0;
		}
		$this->assign('village_id',$this->village_id);
		//进入顺风车的网址

		if(empty($_SESSION['carReturnUrl'])){
			$strstr	=	strstr($_SERVER['HTTP_REFERER'],'c=Ride');
			if($this->village_id){
				$returnUrl = U('House/village',array('village_id'=>$this->village_id));
			}else if($_SERVER['HTTP_REFERER'] && empty($strstr)){
				$returnUrl = $_SERVER['HTTP_REFERER'];
			}else{
				$returnUrl = U('Home/index');
			}
			$_SESSION['carReturnUrl']	=	$returnUrl;
		}else{
			$returnUrl = $_SESSION['carReturnUrl'];
		}
		$authentication_car	=	M('User_authentication_car')->field(true)->where(array('uid'=>$this->user_session['uid']))->order('add_time DESC')->find();
		$this->assign('car_status',$authentication_car['status']);
		$this->assign('returnUrl',$returnUrl);
		$this->assign('index','拼车列表');
		$this->display();
	}
	//	拼车详情
	public function ride_details(){
		$ride_id	=	I('ride_id');
		$status		=	I('status');
		$this->assign('ride_id',$ride_id);
		$this->assign('status',$status);
		$this->assign('index','拼车详情');
		$this->display();
	}
	//	发起拼车
	public function ride_add(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->error_tips('请先登录!');
			}else{
				redirect(U('Login/index'));
			}
		}
		if($this->village_id != 0){
			$houseVillage	=	M('House_village')->field(array('village_name','village_address'))->where(array('village_id'=>$this->village_id))->find();
			$this->assign('houseVillage',$houseVillage['village_address'].$houseVillage['village_name']);
		}
		$authentication_car	=	M('User_authentication_car')->field(true)->where(array('uid'=>$this->user_session['uid']))->order('add_time DESC')->find();
		$type	=	I('type');
		$this->assign('index','发起拼车');
		$this->assign('type',$type);
		$this->assign('truename',$this->user_session['truename']);
		$this->assign('phone',$this->user_session['phone']);
		$this->assign('now_city',$now_city);
		$this->assign('province',$province['area_pid']);
		$this->assign('car_status',$authentication_car['status']);
		$this->display();
	}
	//	修改拼车
	public function ride_eidt(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->error_tips('请先登录!');
			}else{
				redirect(U('Login/index'));
			}
		}
		$ride_id		=	I('ride_id');
		$seat_number	=	I('seat_number');
		$status	=	I('status');
		$this->assign('ride_id',$ride_id);
		$this->assign('seat_number',$seat_number);
		$this->assign('status',$status);
		$this->assign('index','修改拼车');
		$this->display();
	}
	//	我的发起
	public	function	ride_history(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->error_tips('请先进行登录！',U('Login/index'));
			}else{
				redirect(U('Login/index'));
			}
		}
		$this->assign('index','我的发起');
		$this->display();
	}
	//	立即下单
	public	function	ride_place_order(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->error_tips('请先进行登录！',U('Login/index'));
			}else{
				redirect(U('Login/index'));
			}
		}
		$ride_id	=	I('ride_id');
		$RideModel	=	D('Ride');
		$ride_where['ride_id']	=	$ride_id;
		$aRideNumber	=	$RideModel->get_ride_order_number($ride_where);
		$aUser	=	M('User')->field(array('phone','truename'))->where(array('uid'=>$this->user_id))->find();
		$status		=	I('status');
		$this->assign('ride_id',$ride_id);
		$this->assign('status',$status);
		$this->assign('remain_number',$aRideNumber['remain_number']);
		$this->assign('ride_price',$aRideNumber['ride_price']);
		$this->assign('phone',$aUser['phone']);
		$this->assign('truename',$aUser['truename']);
		$this->assign('index','下单');
		$this->display();
	}
	//	拼车记录
	public	function	ride_carpooling(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->error_tips('请先登录!');
			}else{
				redirect(U('Login/index'));
			}
		}
		$this->assign('index','我的订单');
		$this->assign('defaultImg',$this->defaultImg);
		$this->display();
	}
	//	我的拼车详情
	public	function	ride_carpoling_details(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->error_tips('请先登录!');
			}else{
				redirect(U('Login/index'));
			}
		}
		$order_id	=	I('order_id');
		$this->assign('order_id',$order_id);
		$this->assign('index','订单详情');
		$this->display();
	}
	//	取消订单
	public	function	ride_order_cancel(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->error_tips('请先登录!');
			}else{
				redirect(U('Login/index'));
			}
		}
		$order_id	=	I('order_id');
		$aRideOrder	=	M('Ride_order')->field(array('ride_id','sit_number'))->where(array('order_id'=>$order_id))->find();
		$aRide	=	M('Ride')->field(array('ride_price','penalty'))->where(array('ride_id'=>$aRideOrder['ride_id']))->find();
		$penalty	=	$aRideOrder['sit_number']*$aRide['penalty'];
		$total	=	$aRideOrder['sit_number']*$aRide['ride_price'];
		$back	=	$total	-	$penalty;
		$this->assign('sit_number',$aRideOrder['sit_number']);
		$this->assign('ride_price',$aRide['ride_price']);
		$this->assign('order_id',$order_id);
		$this->assign('total',$total);
		$this->assign('penalty',$penalty);
		$this->assign('back',$back);
		$this->assign('index','取消订单');
		$this->display();
	}
	//	司机取消订单
	public	function ride_order_cancel_driver(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->error_tips('请先登录!');
			}else{
				redirect(U('Login/index'));
			}
		}
		$order_id	=	I('order_id');
		$aRideOrder	=	M('Ride_order')->field(array('ride_id','sit_number'))->where(array('order_id'=>$order_id))->find();
		$aRide	=	M('Ride')->field(array('ride_price','penalty'))->where(array('ride_id'=>$aRideOrder['ride_id']))->find();
		$total	=	$aRideOrder['sit_number']*$aRide['ride_price'];
		$this->assign('total',$total);
		$this->assign('sit_number',$aRideOrder['sit_number']);
		$this->assign('ride_price',$aRide['ride_price']);
		$this->assign('order_id',$order_id);
		$this->assign('index','取消订单');
		$this->display();
	}
	/* 地图 */
	public function adres_map()
	{
		$now_city	=	$this->config['now_city'];
		$province	=	D('Area')->field(array('area_pid'))->where(array('area_id'=>$now_city))->find();
		if (empty($now_city) || empty($province)) {
			$this->error('请选择城市');
		}
		$list = D('Area')->field(true)->where("area_id IN ({$province['area_pid']}, {$now_city})")->order('area_type ASC')->select();
		$address = '';
		foreach ($list as $row) {
			$address .= $row['area_name'];
		}
		$this->assign('address', $address);
		$params = $_GET;
		unset($params['adress_id']);
		$this->assign('params',$params);
		$this->display();
	}
	/*
	*	上面是html5页面	下面是接口
	*	现在还没有匹配APP接口的验证，如果有人要给APP做接口，加上验证就能用，记住：html5的验证和APP验证要融合
	*/
	//	拼车列表接口
	public	function	ride_list_api(){
		$ride_price	=	I('ride_price');
		$page		=	I('page',1);
		$page_coun	=	I('page_coun',10);
		if($ride_price){
			switch($ride_price){
				case 20:
					$where['ride_price']	=	array(array('gt',0),array('elt',20));
					break;
				case 40:
					$where['ride_price']	=	array(array('egt',20),array('elt',40));
					break;
				case 60:
					$where['ride_price']	=	array(array('egt',40),array('elt',60));
					break;
				case 1000:
					$where['ride_price']	=	array('egt',60);
					break;
			}
		}
		$remain_number	=	I('remain_number');
		if($remain_number){
			switch($remain_number){
				case 1:
					$where['remain_number']	=	array('egt',1);
					break;
				case 2:
					$where['remain_number']	=	array('egt',2);
					break;
				case 3:
					$where['remain_number']	=	array('egt',3);
					break;
				case 4:
					$where['remain_number']	=	array('egt',4);
					break;
			}
		}
		$destination	=	I('destination');
		if($destination	!= 'undefined' && $destination){
			$where['destination']	=	array('like','%'.$destination.'%');
		}
		$departure_place	=	I('departure_place');
		if($departure_place	==	2){
			$where['village_id']	=	$this->village_id;
		}
		//$where['start_time']	=	array('egt',time());

		$where['_string'] = '((start_time >='.time().') AND (ride_date_number=1)) or (ride_date_number=2)';
		$where['status']	=	array('eq',1);
		$where['city_id']	=	$this->config['now_city'];
		$RideModel	=	D('Ride');
		$aRideList	=	$RideModel->get_ride_list($where,$page,$page_coun);
		if($aRideList == 1){
			$this->returnCode('20100001');
		}else if($aRideList == 2){
			$this->returnCode('20100002');
		}else{
			$aRideList['defaultImg']	=	$this->defaultImg;
			$this->returnCode(0,$aRideList);
		}
	}
	//	拼车详情
	public	function	ride_details_api(){
		$ride_id	=	I('ride_id');
		if(empty($ride_id)){
			$this->returnCode('20100003');
		}
		$where['ride_id']	=	$ride_id;
		$RideModel		=	D('Ride');
		$aRideDetails	=	$RideModel->get_ride_one($where,$this->user_id);
		if($aRideDetails == 2){
			$this->returnCode('20100004');
		}else{
			$aRideDetails['defaultImg']	=	$this->defaultImg;
			$this->returnCode(0,$aRideDetails);
		}
	}
	//	发布拼车
	public	function ride_add_api(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->returnCode('30010010');
			}else{
				redirect(U('Login/index'));
			}
		}
		$start_time	=	I('start_time');
		if(strtotime($start_time) < time()){
			$this->returnCode('20100006');
		}
		$user_id	=	$this->user_id;
		//	判断是否是本小区出发
		if($this->village_id != 0){
			$houseVillage	=	M('House_village')->field(array('village_name','village_address'))->where(array('village_id'=>$this->village_id))->find();
			$departure_place	=	I('departure_place');
			$houseName	=	$houseVillage['village_address'].$houseVillage['village_name'];
			if($departure_place == $houseName){
				$village_id	=	$this->village_id;
			}else{
				$village_id	=	0;
			}
		}else{
			$village_id	=	0;
		}
		$is_cancel_time	=	I('is_cancel_time');
		if($is_cancel_time == 1){
			$cancel_time = 30;
		}else if($is_cancel_time == 2){
			$cancel_time = 0;
		}else{
			$cancel_time = I('cancel_time');
		}
		$data	=	array(
			'ride_title'		=>	I('ride_title'),
			'village_id'		=>	$village_id,
			'departure_place'	=>	I('departure_place'),
			'destination'		=>	I('destination'),
			'ride_date_number'	=>	I('ride_date_number'),
			'start_time'		=>	strtotime($start_time),
			'penalty'			=>	I('penalty'),
			'cancel_time'		=>	$cancel_time,
			'ride_price'		=>	I('ride_price'),
			'seat_number'		=>	I('seat_number'),
			'remain_number'		=>	I('seat_number'),
			'user_id'			=>	$user_id,
			'owner_name'		=>	I('owner_name'),
			'owner_phone'		=>	I('owner_phone'),
			'status'			=>	1,
			'time'				=>	time(),
			'up_time'			=>	0,
			'city_id'			=>	$this->config['now_city'],
		);
		$RideModel	=	D('Ride');
		$aRideAdd	=	$RideModel->get_ride_add($data);
		if(empty($aRideAdd)){
			$this->returnCode('20100005');
		}else if($aRideAdd){
			if($this->user_session['truename']	!=	$data['owner_name']){
				M('user')->where(array('uid'=>$user_id))->data(array('truename'=>$data['owner_name']))->save();
			}
			$where['user_id']	=	$user_id;
			$rideLog	=	$RideModel->get_ride_log($where);
			if(empty($rideLog)){
				$add	=	$RideModel->add_ride_log($where);
				if(empty($add)){
					$this->returnCode('20100032');
				}
			}
			$this->returnCode(0);
		}
	}
	//	修改拼车
	public	function	ride_eidt_api(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->returnCode('30010010');
			}else{
				redirect(U('Login/index'));
			}
		}
		$seat_number	=	I('seat_number');
		$is_status		=	I('is_status');
		$ride_id		=	I('ride_id');
		$RideModel	=	D('Ride');
		$where['ride_id']	=	$ride_id;
		$aRideCount	=	$RideModel->get_ride_order_number($where);
		if($is_status == 1){
			if(empty($seat_number) || $seat_number==0){
				$this->returnCode('20100031');
			}
			if($aRideCount['sit_number'] >	$seat_number){
				$this->returnCode('20100030');
			}else{
				$data	=	array(
					'seat_number'	=>	$seat_number,
					'remain_number'	=>	$seat_number-$aRideCount['sit_number'],
				);
			}
		}else{
			$data	=	array(
				'status'		=>	$is_status,
			);
		}
		$aRideSave	=	$RideModel->get_ride_eidt($where,$data);
		if($aRideSave == 2){
			$this->returnCode('20100007');
		}else if($aRideSave == 99){
			if($is_status	==	4 || $is_status == 5){
				$aRideOrderList	=	$RideModel->get_ride_day_order($ride_id,$data,$aRideCount['up_time']);
				if($aRideOrderList){
					$penalty	=	'';
					foreach($aRideOrderList as $v){
						$penalty	=	$aRideCount['ride_price']*$v['sit_number'];
						$arr	=	$this->ride_order_cancel_api($v['order_id'],1,$penalty);
						if(empty($arr)){
							$this->returnCode('20100029');
						}
					}
				}
			}
			$this->returnCode(0);
		}
	}
	//	我的发布
	public	function	ride_history_api(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->returnCode('30010010');
			}else{
				redirect(U('Login/index'));
			}
		}
		$user_id	=	$this->user_id;
		$page		=	I('page',1);
		$page_coun	=	I('page_coun',10);
		$where['user_id']	=	$user_id;
		$order['start_time']	=	'desc';
		$RideModel	=	D('Ride');
		$aRideList	=	$RideModel->get_ride_list($where,$page,$page_coun,$order);
		if($aRideList == 2){
			$this->returnCode('20100004');
		}else{
			$aRideList['defaultImg']	=	$this->defaultImg;
			$this->returnCode(0,$aRideList);
		}
	}
	//	下单
	public	function ride_place_order_api(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->returnCode('30010010');
			}else{
				redirect(U('Login/index'));
			}
		}
		$ride_id		=	I('ride_id');
		if(empty($ride_id)){
			$this->returnCode('20100003');
		}
		$reserve_number	=	I('reserve_number');
		$reserve_name	=	I('reserve_name');
		$reserve_phone	=	I('reserve_phone');
		$reserve_number	=	(integer)$reserve_number;
		if(empty($reserve_number)){
			$this->returnCode('20100004');
		}else if(!is_int($reserve_number)){
			$this->returnCode('20100011');
		}
		if(empty($reserve_name)){
			$this->returnCode('20100017');
		}
		if(empty($reserve_phone)){
			$this->returnCode('20100018');
		}
		$RideModel	=	D('Ride');
		//	获取顺风车的全部信息
		$ride_where['ride_id']	=	$ride_id;
		$aRideNumber	=	$RideModel->get_ride_order_number($ride_where);
		if(empty($aRideNumber)){
			$this->returnCode('20100002');
		}
		if($reserve_number > $aRideNumber['remain_number']){
			$this->returnCode('20100010');
		}else{
			$use_money	=	$reserve_number*$aRideNumber['ride_price'];
			//	获取用户全部信息，为了实时查看用户的余额
			$aUser	=	M('User')->field(true)->where(array('uid'=>$this->user_id))->find();
			M('')->startTrans();
			if(empty($aUser['truename'])){
				M('User')->where(array('uid'=>$aUser['uid']))->data(array('truename'=>$reserve_name))->save();
			}
			if($aUser['now_money'] < $use_money){
				$arr 	=	array(
					'errorCode'	=>	1,
					'errorMsg'	=>	$aUser['now_money'],
					'result'	=>	$use_money-$aUser['now_money'],
				);
				$this->returnCode(0,$arr);
			}
			if($aRideNumber['cancel_time'] == 0){
				$order_cancel_time	=	0;
			}else{
				$order_cancel_time	=	$aRideNumber['cancel_time']*60+time();
			}
			$order	=	array(
				'ride_id'	=>	$ride_id,
				'user_id'	=>	$aUser['uid'],
				'sit_number'=>	$reserve_number,
				'order_time'=>	time(),
				'order_cancel_time'	=>	$order_cancel_time,
				'paid'		=>	1,
				'status'	=>	1,
				'truename'	=>	$reserve_name,
				'phone'		=>	$reserve_phone,
				'up_time'	=>	$aRideNumber['up_time'],
				'up_number'	=>	$aRideNumber['up_number'],
			);
			//	添加响应顺风车订单
			$add	=	$RideModel->ride_place_order($order);
			if($add){
				$aRideArr	=	array(
					'sit_number'	=>	$aRideNumber['sit_number']+$reserve_number,
					'remain_number'	=>	$aRideNumber['remain_number']-$reserve_number,
				);
				if($aRideArr['remain_number'] == 0){
					$aRideArr['status']	=	3;
				}
				//	修改顺风车的座位数量
				$rideEidt	=	$RideModel->get_ride_eidt($ride_where,$aRideArr);
				if($rideEidt == 99){
					$user_id['user_id']	=	$aRideNumber['user_id'];
					//	获取顺风车的日志
					$total_number	=	$RideModel->get_ride_log($user_id);
				}else{
					M('')->rollback();
					$this->returnCode('20100013');
				}
				if($total_number){
					//	修改顺风车的日志
					$rideLog['total_number']	=	$total_number['total_number']+1;
					$saveRideLog	=	$RideModel->save_ride_log($rideLog,$user_id);
				}else{
					M('')->rollback();
					$this->returnCode('20100014');
				}
			}else{
				M('')->rollback();
				$this->returnCode('20100015');
			}
		}
		if($saveRideLog){
			//	响应顺风车，支付
			$pay	=	$this->pay($aUser,$use_money,$ride_id);
			if($pay['errorCode']	!=	99){
				$this->returnCode('20100028');
			}
			//	发送微信、手机通知
			$data	=	array(
				'visitor_uid'	=>	$aUser['uid'],	//乘客ID
				'visitor_name'	=>	$reserve_name,	//乘客名字
				'visitor_phone'	=>	$reserve_phone,	//乘客手机
				'owner_uid'		=>	$aRideNumber['user_id'],	//司机ID
				'owner_name'	=>	$aRideNumber['owner_name'],	//司机名字
				'owner_phone'	=>	$aRideNumber['owner_phone'],	//司机手机
				'time'			=>	date('m月d日 H时i分',$aRideNumber['start_time']),	//时间
			);
			$RideModel->weixin($data);
			M('')->commit();
			$this->returnCode(0,$pay);
		}else{
			M('')->rollback();
			$this->returnCode('20100016');
		}
	}
	//	拼车记录
	public	function ride_carpooling_api(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->returnCode('30010010');
			}else{
				redirect(U('Login/index'));
			}
		}
		$page		=	I('page',1);
		$page_coun	=	I('page_coun',10);
		$where	=	array(
			'user_id'	=>	$this->user_id,
		);
		$aRideOrder	=	D('Ride')->get_ride_carpooling($where,$page,$page_coun);
		if(empty($aRideOrder)){
			$this->returnCode('20100019');
		}else{
			$aRideOrder['defaultImg']	=	$this->defaultImg;
			$this->returnCode(0,$aRideOrder);
		}
	}
	//	拼车记录详情
	public	function	ride_carpoling_details_api(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->returnCode('30010010');
			}else{
				redirect(U('Login/index'));
			}
		}
		$where['order_id']	=	I('order_id');
		if(empty($where['order_id'])){
			$this->returnCode('20100020');
		}
		$aDetails	=	D('Ride')->get_ride_details($where);
		if($aDetails){
			$aDetails['defaultImg']	=	$this->defaultImg;
			$this->returnCode(0,$aDetails);
		}else{
			$this->returnCode('20100021');
		}
	}
	//	取消订单	$type	0代表用户取消	1代表司机取消
	//	$penalty	传入退款金额，必须传。
	public function ride_order_cancel_api($order_id='',$type='',$penalty=''){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->returnCode('30010010');
			}else{
				redirect(U('Login/index'));
			}
		}
		if(empty($order_id)){
			$order_id	=	I('order_id');
		}
		if(empty($type)){
			$type	=	I('type');
		}
		if(empty($penalty)){
			$penalty	=	I('penalty');
		}
		$where['order_id']	=	$order_id;
		if(empty($where['order_id'])){
			$this->returnCode('20100020');
		}
		$aCancel	=	D('Ride')->get_ride_details($where);
		if($aCancel['ride_order']	||	$aCancel['ride']){
			if($type){
				$data['status']	=	2;
				if($aCancel['ride_order']['paid'] == 3){
					$UserFind	=	M('User')->where(array('uid'=>$aCancel['ride']['user_id']))->find();
					if($UserFind){
						//	平台提成
						$platform	=	$aCancel['ride']['ride_price']/100*$this->config['ride_proportion_full'];
						//	司机需要的单座位退款
						$driver_refund	=	$aCancel['ride']['ride_price']-$platform;
						$driver_refund	=	$driver_refund*$aCancel['ride_order']['sit_number'];
						//	需要退款的金额   大于   司机余额，提示司机去充值
						if($driver_refund > $UserFind['now_money']){
							$this->returnCode('20100034');
						}
					}else{
						$this->returnCode('20100023');
					}
				}
			}else{
				$data['status']	=	3;
			}
			$ride_save	=	D('Ride')->ride_order_save($where,$data);
			if($ride_save	!=	1){
				$this->returnCode('20100024');
			}
			$ride_data	=	array(
				'sit_number'	=>	$aCancel['ride']['sit_number']-$aCancel['ride_order']['sit_number'],
				'remain_number'	=>	$aCancel['ride']['remain_number']+$aCancel['ride_order']['sit_number'],
			);
			if($aCancel['ride']['status'] == 3){
				$ride_data['status']	=	1;
			}
			//	查询司机的下单，和乘客的订单
			$ride_where['ride_id']	=	$aCancel['ride']['ride_id'];
			$rideEidt	=	D('Ride')->get_ride_eidt($ride_where,$ride_data);
			if($rideEidt != 99){
				$this->returnCode('20100025');
			}
			$logWhere['user_id']	=	$aCancel['ride']['user_id'];
			$logSelect	=	D('Ride')->get_ride_log($logWhere);
			if(empty($logSelect)){
				$this->returnCode('20100026');
			}
			if($type){
				$proportion	=	(int)($logSelect['cancel_number']+1)/$logSelect['total_number']*100;
				$proportion	=	(100-$proportion);
				$proData	=	array(
					'cancel_number'	=>	$logSelect['cancel_number']+1,
					'proportion'	=>	$proportion,
				);
				$logSave	=	M('Ride_order_log')->where($logWhere)->data($proData)->save();
			}else{
				$logSave	=	M('Ride_order_log')->where($logWhere)->setInc('user_cancel_number');
			}
			if(empty($logSave)){
				$this->returnCode('20100027');
			}
			if($type){
				if($aCancel['ride_order']['paid'] == 3){
					$add_result = D('User')->user_money($aCancel['ride']['user_id'],$driver_refund,'退款 '.$aCancel['ride']['truename'].' 车主取消顺风车，司机扣除金额',2,$ride_where['ride_id']);
				}
				$add_result = D('User')->add_money($aCancel['ride_order']['user_id'],$penalty,'退款 '.$aCancel['ride_order']['truename'].' 车主取消顺风车，用户增加金额',2,$ride_where['ride_id']);
			}else{
				$add_result = D('User')->add_money($aCancel['ride_order']['user_id'],$penalty,'退款 '.$aCancel['ride_order']['truename'].' 乘客取消顺风车',2,$ride_where['ride_id']);
			}
			if($add_result['error_code'] != false){
				$this->returnCode('20100022');
			}else{
				//	发送微信、手机通知
				$weixin_data	=	array(
					'visitor_uid'	=>	$aCancel['ride_order']['user_id'],	//乘客ID
					'visitor_name'	=>	$aCancel['ride_order']['truename'],	//乘客名字
					'visitor_phone'	=>	$aCancel['ride_order']['phone'],	//乘客手机
					'owner_uid'		=>	$aCancel['ride']['user_id'],		//司机ID
					'owner_name'	=>	$aCancel['ride']['owner_name'],		//司机名字
					'owner_phone'	=>	$aCancel['ride']['owner_phone'],	//司机手机
					'time'			=>	date('m月d日 H时i分',$aCancel['ride']['start_time']),	//时间
				);
				if($type){
					D('Ride')->weixin_driver($weixin_data);
					if($type == 1){
						return	$weixin_data;
					}else{
						$this->returnCode(0);
					}
				}else{
					D('Ride')->weixin_user($weixin_data);
					$this->returnCode(0);
				}
			}
		}else{
			$this->returnCode('20100021');
		}
	}
	//	启用订单
	public	function	ride_start_api(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->returnCode('30010010');
			}else{
				redirect(U('Login/index'));
			}
		}
		$where['ride_id']	=	I('ride_id');
		if(empty($where)){
			$this->returnCode('20100003');
		}
		$aRide	=	M('Ride')->field(true)->where($where)->find();
		$time	=	time();
		if($time>$aRide['start_time']){
			if($aRide['ride_date_number'] == 1){
				$arr['status']	=	2;
			}else if($aRide['ride_date_number'] == 2){
				$date	=	date('Ymd',time());
				$start_time	=	date('Hi',$aRide['start_time']);
				$day	=	strtotime($date.$start_time);
				if($day <= $time){
					$day	+=	86400;
				}
				$arr	=	array(
					'status'	=>	1,
					'start_time'	=>	$day,
					'up_time'	=>	date('Ymd',$time),
					'up_number'	=>	$aRide['up_number']+1,
				);
			}
		}else{
			$arr	=	array(
				'status'	=>	1,
				'up_time'	=>	date('Ymd',$time),
			);
		}
		$aSave	=	M('Ride')->where($where)->data($arr)->save();
		if(empty($aSave)){
			$this->returnCode('20100033');
		}
		$this->returnCode(0,$aSave);
	}
	//	用户完成订单，把钱付给车主
	public	function	complete_pay_api(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->returnCode('30010010');
			}else{
				redirect(U('Login/index'));
			}
		}
		$order_id	=	I('order_id');
		if(empty($order_id)){
			$this->returnCode('20100020');
		}
		$where['order_id']	=	$order_id;
		$ride	=	D('Ride');
		$aRideOrder	=	$ride->ride_order_select($where);
		if(empty($aRideOrder)){
			$this->returnCode('20100002');
		}
		$data	=	array(
			'paid'			=>	3,
			'status'		=>	4,
			'complete_time'	=>	time(),
		);
		$aSave	=	$ride->ride_order_save($where,$data);
		if($aSave != 1){
			$this->returnCode('20100015');
		}else{
			$aRide	=	M('Ride')->field(true)->where(array('ride_id'=>$aRideOrder['ride_id']))->find();
			$total	=	$aRide['ride_price']*$aRideOrder['sit_number'];
			if($this->config['ride_proportion_full']){
				$total	=	$total-$total*($this->config['ride_proportion_full']/100);
			}
			$add_result = D('User')->add_money($aRide['user_id'],$total,'收钱 '.$aRide['owner_name'].' 车主收取顺风车定额',2,$aRideOrder['ride_id']);
		}
		if($add_result['error_code'] != false){
			$this->returnCode('20100022');
		}
		$this->returnCode(0);
	}
	/*
	*	响应顺风车，去付款。
	*	$aUser	用户的全部信息
	*	$use_money	需要付款的金额
	*	如果失败，就直接返回接口
	*/
	private	function pay($aUser=array(),$use_money,$ride_id){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->returnCode('30010010');
			}else{
				redirect(U('Login/index'));
			}
		}
		if($aUser['now_money'] < $use_money){
			$arr 	=	array(
				'errorCode'	=>	1,
				'errorMsg'	=>	$aUser['now_money'],
				'result'	=>	$use_money-$aUser['now_money'],
			);
			$this->returnCode(0,$arr);
		}
		$save_result	=	D('User')->user_money($aUser['uid'],$use_money,'响应顺风车',2,$ride_id);
		if($save_result['error_code']){
			$arr 	=	array(
				'errorCode'	=>	2,
				'errorMsg'	=>	$save_result['msg'],
			);
			$this->returnCode(0,$arr);
		}else{
			$arr 	=	array(
				'errorCode'	=>	99,
				'errorMsg'	=>	'余额支付成功并成功预定',
			);
			return	$arr;
		}
	}
}