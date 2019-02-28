<?php
class CrowdsourcingAction extends BaseAction{
	protected $defaultImg = array(
		'urlEnd'	=>	'/tpl/Wap/default/static/criwdsiyrcubg/img/end.png',
		'urlMe'		=>	'/tpl/Wap/default/static/criwdsiyrcubg/img/me.png',
		'urlStar'	=>	'/tpl/Wap/default/static/criwdsiyrcubg/img/star.png',
		'urlBox'	=>	'/tpl/Wap/default/static/criwdsiyrcubg/img/box.png',
		'urlV'		=>	'/tpl/Wap/default/static/criwdsiyrcubg/img/v.png',
		'urlVyet'	=>	'/tpl/Wap/default/static/criwdsiyrcubg/img/v_yet.png',
		'urlGo'		=>	'/tpl/Wap/default/static/criwdsiyrcubg/img/go.png',
		'urlKuaidi'	=>	'/tpl/Wap/default/static/criwdsiyrcubg/img/kuaidi.png',
	);
	public function _initialize(){
		parent::_initialize();

		//	查看是否开启众包功能
		$this->crowdsourcing	=	$this->config['crowdsourcing_is'];
		$this->assign('crowdsourcing',$this->crowdsourcing);
		//	众包的默认图片
		$this->assign('defaultImg',$this->defaultImg);
		$category	=	M('Crowdsourcing_category')->where(array('category_status'=>1))->order('category_sort DESC')->select();
		$category_count	=	count($category);
		$this->assign('category',$category);
		$this->assign('category_count',$category_count);
	}
	//	众包首页
	public function index(){
		$this->assign('index','众包平台');
		$this->display();
	}
	//	众包首页接口
	public function index_json(){
		$page		=	I('page',1);
		$pageCount	=	I('page_count',10);
		$ride_price	=	I('ride_price');
		$user_id	=	I('user_id');
		if($user_id){
			$where['user_id']	=	$this->user_session['uid'];
		}else{
			if($ride_price){
				switch($ride_price){
					case 50:
						$where['package_money']	=	array(array('gt',0),array('elt',50));
						break;
					case 100:
						$where['package_money']	=	array(array('egt',50),array('elt',100));
						break;
					case 200:
						$where['package_money']	=	array(array('egt',100),array('elt',200));
						break;
					case 1000:
						$where['package_money']	=	array('egt',200);
						break;
				}
			}
			$remain_number	=	I('remain_number');
			if($remain_number > 0){
				$where['car_type']	=	array('eq',$remain_number);
			}
			$destination	=	I('destination');
			if($destination	!= 'undefined' && $destination){
				$where['package_end']	=	array('like','%'.$destination.'%');
			}
			$where['package_status']	=	1;
		}
		$aList		=	D('Crowdsourcing')->get_list($where,$page,$pageCount);
		if(empty($aList)){
			$this->returnCode('20110001');
		}
		$this->returnCode(0,$aList);
	}
	//	众包详情
	public function details(){
		$package_id	=	$_GET['package_id'];
		$this->assign('package_id',$package_id);
		$status		=	$_GET['status'];
		$this->assign('status',$status);
		$this->assign('index','众包详情');
		$this->display();
	}
	//	众包详情接口
	public function details_json(){
		$where['package_id']	=	$_POST['package_id'];
		if(empty($where)){
			$this->returnCode('20110003');
		}
		//	获取众包详情
		$aDetails	=	D('Crowdsourcing')->get_details($where);
		if(empty($aDetails)){
			$this->returnCode('20110004');
		}
		//	获取众包的订单
		$aDetails['order']	=	D('Crowdsourcing')->get_order_details(array('package_id'=>$aDetails['package_id'],'status'=>array('neq',5)),1);
		if($aDetails['user_id'] == $this->user_session['uid']){
			$aDetails['status']	=	1;
		}else{
			$aDetails['status']	=	0;
			$aDetails['user_phone']	=	substr($aDetails['user_phone'],0,3).'********';
		}
		//	众包的数据整理
		$aDetails['IMG']		=	$this->defaultImg;
		if($aDetails['is_authentication'] == 1){
			$aDetails['is_authentication']	=	'需要认证';
			$aDetails['is_authentication_img']	=	$this->defaultImg['urlVyet'];
		}else if($aDetails['is_authentication'] == 2){
			$aDetails['is_authentication']	=	'不需要认证';
			$aDetails['is_authentication_img']	=	$this->defaultImg['urlV'];
		}
		$aDetails['car_type_img']	=	$this->carFormat($aDetails['car_type']);
		$this->returnCode(0,$aDetails);
	}
	//	发布众包
	public function add(){
		$this->is_login(U('Crowdsourcing/add'));
		$where['uid']	=	$this->user_session['uid'];
		$aUser	=	D('Crowdsourcing')->user($where);
		$category	=	D('Crowdsourcing_category')->field(true)->where(array('category_status'=>1))->order('category_sort DESC')->select();
		$this->assign('category',$category);
		$this->assign('user',$aUser);
		$this->assign('index','发起众包');
		$this->display();
	}
	//	添加众包接口
	public function add_json(){
		$this->is_login(U('Crowdsourcing/add'));
		$need	=	$_POST['need'];
		$car_type	=	$_POST['car_type'];
		if($need == 'null' || $need == null || empty($need)){
			$need	=	1;
		}
		//if($small == 'null' || $small == null || empty($small)){
//			$small	=	1;
//		}
		$data	=	array(
			'package_start'		=>	$_POST['package_start'],
			'package_start_long'=>	$_POST['package_start_long'],
			'package_start_lat'=>	$_POST['package_start_lat'],
			'package_end'		=>	$_POST['package_end'],
			'package_end_long'	=>	$_POST['package_end_long'],
			'package_end_lat'	=>	$_POST['package_end_lat'],
			'package_title'		=>	$_POST['package_title'],
			'package_money'		=>	$_POST['package_money'],
			'package_deposit'	=>	$_POST['package_deposit'],
			'user_id'			=>	$this->user_session['uid'],
			'user_name'			=>	$_POST['user_name'],
			'user_phone'		=>	$_POST['user_phone'],
			'package_remarks'	=>	$_POST['package_remarks'],
			'is_authentication'	=>	$need,
			'package_status'	=>	1,
			'add_tims'			=>	time(),
			'car_type'			=>	$car_type,
		);
		$aUser	=	D('Crowdsourcing')->user(array('uid'=>$data['user_id']));
		if($aUser['now_money'] < $data['package_money']){
			$this->returnCode('20110023');
		}
		$add	=	D('Crowdsourcing')->add_crow($data);
		if(empty($add)){
			$this->returnCode('20110001');
		}
		//	添加众包成功，先收取众包运费
		if($data['package_money']){
			$save_result	=	D('User')->user_money($data['user_id'],$data['package_money'],'发布众包，预收运费',1,$add);
			if($save_result['error_code']){
				D('Crowdsourcing')->eidt_crow(array('package_id'=>$add),array('package_status'=>2));
				$this->returnCode('20110014');
			}
		}
		$this->returnCode(0);
	}
	//	修改众包
	public function eidt(){
		$this->is_login(U('Crowdsourcing/eidt'));
		$where['package_id']	=	I('package_id');
		$status	=	I('status');
		if(empty($where['package_id'])){
			$this->returnCode('20110003');
		}
		$details	=	D('Crowdsourcing')->get_details($where);
		$category	=	D('Crowdsourcing_category')->field(true)->where(array('category_status'=>1))->order('category_sort DESC')->select();
		$this->assign('category',$category);
		$this->assign('details',$details);
		$this->assign('status',$status);
		$this->assign('index','修改众包');
		$this->display();
	}
	//	修改众包接口
	public function eidt_json(){
		$this->is_login(U('Crowdsourcing/eidt'));
		$where['package_id']	=	I('package_id');
		if(empty($where['package_id'])){
			$this->returnCode('20110003');
		}
		$data	=	$_POST;
		foreach($data as $k=>$v){
			if(empty($v)){
				unset($data[$k]);
			}
		}
		unset($data['package_id']);
		$save	=	D('Crowdsourcing')->eidt_crow($where,$data);
		if($save === false){
			$this->returnCode('20110008');
		}
		$this->returnCode(0);
	}
	//	关闭众包
	public function close_json(){
		$this->is_login(U('Crowdsourcing/details'));
		$where['package_id']	=	I('package_id');
		if(empty($where['package_id'])){
			$this->returnCode('20110003');
		}
		$data	=	array(
			'package_status'	=>	2,
		);
		$aFind	=	D('Crowdsourcing')->get_details($where);
		if(empty($aFind)){
			$this->returnCode('20110007');
		}
		if($aFind['package_status'] != 1){
			$this->returnCode('20110024');
		}
		$save	=	D('Crowdsourcing')->eidt_crow($where,$data);
		if($save === false){
			$this->returnCode('20110024');
		}
		$package_money = D('User')->add_money($aFind['user_id'],$aFind['package_money'],'关闭众包ID '.$aFind['package_id'].' 的众包，返回预缴运费',1,$where['package_id']);
		if($package_money['error_code'] != false){
			$this->returnCode('20110022');
		}
		$this->returnCode(0);
	}
	//	抢单
	public function grab_single(){
		$this->is_login(U('Crowdsourcing/grab_single',array('package_id'=>I('package_id'))));
		$where['package_id']	=	$_GET['package_id'];
		$status	=	$_GET['status'];
		if(empty($where['package_id'])){
			$this->returnCode('20110003');
		}
		$details	=	D('Crowdsourcing')->get_details($where);
		if($details){
			$user['uid']	=	$this->user_session['uid'];
			$details['user']	=	D('Crowdsourcing')->user($user);
		}
		$this->assign('details',$details);
		$this->assign('status',$status);
		$this->assign('index','立即抢单');
		$this->display();
	}
	//	抢单接口
	public function grab_single_json(){
		$this->is_login(U('Crowdsourcing/grab_single'));
		$where['package_id']	=	I('package_id');
		if(empty($where['package_id'])){
			$this->returnCode('20110003');
		}
		$name	=	I('name');
		$phone	=	I('phone');
		if(empty($name)){
			$this->returnCode('20110009');
		}else if(empty($phone)){
			$this->returnCode('20110010');
		}
		//	查询众包详情
		$aDetails	=	D('Crowdsourcing')->get_details($where);
		if(empty($aDetails)){
			$this->returnCode('20110004');
		}
		if($aDetails['package_status'] != 1){
			$this->returnCode('20110017');
		}
		$data	=	array(
			'package_id'	=>	$aDetails['package_id'],
			'user_id'		=>	$this->user_session['uid'],
			'user_name'		=>	$name,
			'user_phone'	=>	$phone,
			'status'		=>	1,
			'order_time'	=>	time(),
		);
		//	查询抢单人的信息
		$aUser	=	D('Crowdsourcing')->user(array('uid'=>$data['user_id']));
		if(empty($aUser)){
			$this->returnCode('20110012');
		}
		//	如果用户表没有真实姓名，自动更新
		if(empty($aUser['truename'])){
			M('User')->where(array('uid'=>$data['user_id']))->data(array('truename'=>$data['user_name']))->save();
		}
		//	查看众包，用户 认证
		if($aDetails['is_authentication'] == 1){
			if($aUser['real_name']!=1){
				$this->returnCode('20110015');
			}
		}
		//	用户余额 比 押金少，需要去支付
		if($aUser['now_money'] < $aDetails['package_deposit']){
			$this->returnCode('20110013');
		}
		//	增加众包订单
		$details	=	D('Crowdsourcing')->add_order($data);
		if(empty($details)){
			$this->returnCode('20110011');
		}
		//	抢单成功，如果需要押金，先扣押金
		if($aDetails['package_deposit']){
			$save_result	=	D('User')->user_money($aUser['uid'],$aDetails['package_deposit'],'收取众包ID '.$aDetails['package_id'].' 的押金',1,$where['package_id']);
			if($save_result['error_code'] && $aDetails['package_deposit']>0){
				$this->returnCode('20110014');
			}
		}
		//	众包更改状态，1启用 2关闭 3进行中(已被抢)
		$aSave	=	D('Crowdsourcing')->eidt_crow($where,array('package_status'=>3));
		if(empty($aSave)){
			$this->returnCode('20110016');
		}
		//	发送微信、手机通知
		$weixin_data	=	array(
			'visitor_uid'	=>	$aDetails['user_id'],	//众包ID
			'visitor_name'	=>	$aDetails['user_name'],	//众包名字
			'visitor_phone'	=>	$aDetails['user_phone'],//众包手机
			'owner_uid'		=>	$data['user_id'],		//抢单司机ID
			'owner_name'	=>	$data['user_name'],		//抢单司机名字
			'owner_phone'	=>	$data['user_phone'],	//抢单司机手机
			'package_start'	=>	$aDetails['package_start'],	//出发地
		);
		D('Crowdsourcing')->weixin_user($weixin_data,1);
		$this->returnCode(0);
	}
	//	取消订单
	public function cancel_json(){
		$where['order_id']	=	I('order_id');
		$identity	=	I('identity');
		if(empty($where['order_id'])){
			$this->returnCode('20110006');
		}
		$data	=	array(
			'status'	=>	5,
			'cancel_time'	=>	time(),
		);
		$sUpdata	=	$this->up_status($where,$data,1,$identity);
		if($sUpdata){
			$aWhere['package_id']	=	I('package_id');
			if(empty($aWhere['package_id'])){
				$this->returnCode('20110003');
			}
			$aCrowds	=	D('Crowdsourcing')->get_details($aWhere);
			if(empty($aCrowds)){
				$this->returnCode('20110004');
			}
			$sCrowds	=	D('Crowdsourcing')->eidt_crow($aWhere,array('package_status'=>1));
			if(empty($sCrowds)){
				$this->returnCode('20110025');
			}
			$aOrder	=	D('Crowdsourcing')->get_order_details($where,1);
			if($aCrowds['package_deposit'] != 0){
				$package_deposit = D('User')->add_money($aOrder['user_id'],$aCrowds['package_deposit'],'订单取消，返回ID为： '.$aCrowds['package_id'].' 的众包押金',1,$aWhere['package_id']);
				if($package_deposit['error_code'] != false){
					$this->returnCode('20110021');
				}
			}
			//	发送微信、手机通知
			$weixin_data	=	array(
				'visitor_uid'	=>	$aCrowds['user_id'],	//众包ID
				'visitor_name'	=>	$aCrowds['user_name'],	//众包名字
				'visitor_phone'	=>	$aCrowds['user_phone'],	//众包手机
				'owner_uid'		=>	$aOrder['user_id'],		//抢单司机ID
				'owner_name'	=>	$aOrder['user_name'],	//抢单司机名字
				'owner_phone'	=>	$aOrder['user_phone'],	//抢单司机手机
				'package_start'	=>	$aCrowds['package_start'],	//出发地
			);
			if($identity == 1){
				D('Crowdsourcing')->weixin_user($weixin_data,2);
			}else if($identity == 2){
				D('Crowdsourcing')->weixin_user($weixin_data,3);
			}
			$this->returnCode(0);
		}else{
			$this->returnCode('20110026');
		}
	}
	//	司机已收货
	public function goods_go(){
		$where['order_id']	=	I('order_id');
		if(empty($where['order_id'])){
			$this->returnCode('20110006');
		}
		$data	=	array(
			'status'	=>	2,
			'collect_time'	=>	time(),
		);
		$this->up_status($where,$data);
	}
	//	司机完成送货
	public function complete(){
		$where['order_id']	=	I('order_id');
		if(empty($where['order_id'])){
			$this->returnCode('20110006');
		}
		$data	=	array(
			'status'	=>	3,
			'give_time'	=>	time(),
		);
		$this->up_status($where,$data);
	}
	//	给司机付款
	public function pay_go_json(){
		$where['order_id']	=	I('order_id');
		if(empty($where['order_id'])){
			$this->returnCode('20110006');
		}
		$data	=	array(
			'status'	=>	4,
			'complete_time'	=>	time(),
		);
		$payGo	=	$this->up_status($where,$data,1);
		$aOrder	=	D('Crowdsourcing')->get_order_details($where);
		if($aOrder['details']['package_deposit']){
			$package_deposit = D('User')->add_money($aOrder['user_id'],$aOrder['details']['package_deposit'],'收回自己的众包押金',1,$aOrder['details']['package_id']);
			if($package_deposit['error_code'] != false){
				$this->returnCode('20110021');
			}
		}

		if(C('config.crowdsourcing_proportion_full') > 0){
			$total_money = $aOrder['details']['package_money'] - ($aOrder['details']['package_money'] * (C('config.crowdsourcing_proportion_full') / 100));
			$package_money = D('User')->add_money($aOrder['user_id'],$total_money,'收取 '.$aOrder['details']['user_name'].' 众包运费',1,$aOrder['details']['package_id']);
		}else{
			$package_money = D('User')->add_money($aOrder['user_id'],$aOrder['details']['package_money'],'收取 '.$aOrder['details']['user_name'].' 众包运费',1,$aOrder['details']['package_id']);
		}

		if($package_money['error_code'] != false){
			$this->returnCode('20110022');
		}
		//	发送微信、手机通知
		$weixin_data	=	array(
			'visitor_uid'	=>	$aOrder['details']['user_id'],	//众包ID
			'visitor_name'	=>	$aOrder['details']['user_name'],	//众包名字
			'visitor_phone'	=>	$aOrder['details']['user_phone'],	//众包手机
			'owner_uid'		=>	$aOrder['user_id'],		//抢单司机ID
			'owner_name'	=>	$aOrder['user_name'],	//抢单司机名字
			'owner_phone'	=>	$aOrder['user_phone'],	//抢单司机手机
			'package_start'	=>	$aOrder['details']['package_start'],	//出发地
		);
		D('Crowdsourcing')->weixin_user($weixin_data,5);
		$this->returnCode(0);
	}
	//	更改状态送货状态
	public function up_status($where,$data,$status,$identity=1){
		if(!$where || !$data){
			$this->returnCode('20110020');
		}
		//	查询订单是否存在
		$aOrder	=	D('Crowdsourcing')->get_order_details($where);
		if(empty($aOrder)){
			$this->returnCode('20110007');
		}
		//	查看是否自己的订单
		if($data['status']==2 || $data['status']==4){
			$aOrder['is_me']	=	$this->is_me($aOrder['details']['user_id']);
		}else if($data['status']==1 || $data['status']==3){
			$aOrder['is_me']	=	$this->is_me($aOrder['user_id']);
		}else if($data['status']==5){
			if($identity == 1){
				$aOrder['is_me']	=	$this->is_me($aOrder['details']['user_id']);
			}else if($identity == 2){
				$aOrder['is_me']	=	$this->is_me($aOrder['user_id']);
			}
		}
		if(empty($aOrder['is_me'])){
			$this->returnCode('20110019');
		}
		//	更改状态和时间
		$aOrderSavr	=	D('Crowdsourcing')->save_order_details($where,$data);
		if(empty($aOrderSavr)){
			$this->returnCode('20110018');
		}
		if($status == 1){
			return	true;
		}else{
			$this->returnCode(0);
		}
	}
	//	登录检测
	public function is_login($url=''){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$this->error_tips('请先登录!',U('Login/index',array('referer'=>urlencode($url))));
			}else{
				if(defined('IS_INDEP_HOUSE')){
					redirect(U('House/village_list',array('referer'=>urlencode($url))));
				}else{
					redirect(U('Login/index',array('referer'=>urlencode($url))));
				}

			}
		}
	}
	//	检测是否是自己的订单和众包，防御别人用url输入ID方式操作别人的订单和众包
	public function is_me($where){
		if($where == $this->user_session['uid']){
			return	1;
		}else{
			return	0;
		}
	}
	//	车型匹配图片
	public function carFormat($car_type){
		$category	=	M('Crowdsourcing_category')->where(array('category_status'=>1))->order('category_sort DESC')->select();
		foreach($category as $k=>$v){
			if($car_type == $v['category_id']){
				return	$v['category_img'];
			}
		}
	}
	//	我的发起
	public function my_launch(){
		$this->is_login(U('Crowdsourcing/my_launch'));
		$this->assign('index','我的发起');
		$this->display();
	}
	//	我的抢单
	public function my_response(){
		$this->is_login(U('Crowdsourcing/my_response'));
		$this->assign('index','我的抢单');
		$this->display();
	}
	//	我的抢单接口
	public function my_response_json(){
		$this->is_login(U('Crowdsourcing/my_response'));
		$page		=	I('page',1);
		$page_count	=	I('page_count',10);
		$where['user_id']	=	$this->user_session['uid'];
		$order	=	D('Crowdsourcing')->get_order($where,$page,$page_count);
		foreach($order as $k=>$v){
			$order[$k]['urlStar']	=	$this->defaultImg['urlStar'];
			$order[$k]['urlEnd']	=	$this->defaultImg['urlEnd'];
			$order[$k]['urlGo']		=	$this->defaultImg['urlGo'];
		}
		if(empty($order)){
			$this->returnCode('20110001');
		}
		$this->returnCode(0,$order);
	}
	//	我的抢单详情
	public function my_response_details(){
		$this->is_login(U('Crowdsourcing/my_response_details'));
		$order_id	=	$_GET['order_id'];
		$this->assign('index','我的抢单');
		$this->assign('order_id',$order_id);
		$this->display();
	}
	//	我的抢单详情接口
	public function my_response_details_json(){
		$where['order_id']	=	I('order_id');
		if(empty($where)){
			$this->returnCode('20110006');
		}
		//	获取众包和订单
		$aOrder	=	D('Crowdsourcing')->get_order_details($where);
		if(empty($aOrder)){
			$this->returnCode('20110007');
		}
		//	查看是否自己的订单
		$aOrder['is_me']	=	$this->is_me($aOrder['user_id']);
		if(empty($aOrder['is_me'])){
			$this->returnCode('20110019');
		}
		//	数据的图片整理
		$aOrder['urlStar']	=	$this->defaultImg['urlStar'];
		$aOrder['urlEnd']	=	$this->defaultImg['urlEnd'];
		$aOrder['urlKuaidi']=	$this->defaultImg['urlKuaidi'];
		if($aOrder['details']['is_authentication'] == 1){
			$aOrder['details']['is_authentication']	=	'需要认证';
			$aOrder['details']['is_authentication_img']	=	$this->defaultImg['urlVyet'];
		}else if($aOrder['details']['is_authentication'] == 2){
			$aOrder['details']['is_authentication']	=	'不需要认证';
			$aOrder['details']['is_authentication_img']	=	$this->defaultImg['urlV'];
		}
		$aOrder['details']['car_type_img']	=	$this->carFormat($aOrder['details']['car_type']);
		$this->returnCode(0,$aOrder);
	}
	/* 地图 */
	public function adres_map(){
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
		$type	=	$_GET['type'];
		$this->assign('type',$type);
		$package_id	=	$_GET['package_id'];
		$this->assign('package_id',$package_id);
		$this->display();
	}
}