<?php
/*
 * APP端前台页面----支付页面
 *   Writers    hanlu
 *   BuildTime  2016/11/3 10:41
 */
class Scenic_payAction extends BaseAction{
	private $user_info;
	private $user;
	# 构造方法
	public function __construct(){
		parent::__construct();
		$ticket = I('ticket', false);
		if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info) {
                $this->user_info = $info['uid'];
                $this->user = D('User')->field(true)->where(array('uid'=>$info['uid']))->find();
            }
        }else{
			$this->returnCode('20044013');
        }
    }
	# 生成订单页面
	public function index(){
		if(empty($this->user['phone'])){
			$this->returnCode('40000045');
		}
		if($_POST['type'] == 1){
			$order_model = D('Scenic_aguide_order');
			$now_order = D('Scenic_aguide')->get_pay_order($this->user_info,intval($_POST['order_id']));
		}else{
			$order_model = D('Scenic_order');
			$now_order = D('Scenic_order')->get_pay_order($this->user_info,intval($_POST['order_id']));
		}
		if($now_order['error'] == 1){
			if($now_order['url']){
				$this->returnCode('40000046');
			}else{
				$this->returnCode('40000047');
			}
		}
		$where['order_id']	=	intval($_POST['order_id']);
		if(empty($where)){
			$this->returnCode('40000048');
		}
		if($_POST['type']==1){
			$scenic_order	=	D('Scenic_aguide')->get_one_order($where);
			$add_time	=	$scenic_order['create_time'];
			$pay_status	=	$scenic_order['pay_status'];
			$scenic_list	=	D('Scenic_aguide')->city_get_one_aguide(array('guide_id'=>$scenic_order['guide_id']),'guide_name');
			$name	=	$scenic_list['guide_name'];
		}else{
			$scenic_order	=	D('Scenic_order')->get_one_order($where);
			$add_time	=	$scenic_order['add_time'];
			$pay_status	=	$scenic_order['paid'];
			# 获取景区信息
			$scenic_list	=	D('Scenic_list')->get_one_list(array('scenic_id'=>$scenic_order['scenic_id']),'scenic_title');
			$name	=	$scenic_list['scenic_title'];
		}
		if(empty($scenic_order)){
			$this->returnCode('40000041');
		}
		# 订单未支付，过期时间30分钟，默认关闭状态，订单状态改为5
		if($scenic_order['paid'] == 1){
			$branch	=	1800-($_SERVER['REQUEST_TIME']-$scenic_order['add_time']);
			if($branch < 0){
				$scenic_order['order_status']	=	5;
				if($_GET['type']==1){
					D('Scenic_aguide')->save_aguide(array('order_id'=>$scenic_order['order_id']),array('rela_status'=>5));
				}else{
					D('Scenic_order')->save_order(array('order_id'=>$scenic_order['order_id']),array('order_status'=>5));
				}
			}
		}
		if($scenic_order['order_status'] == 5 || $scenic_order['rela_status'] == 5){
			$this->returnCode('40000049');
		}
		if($pay_status == 1){
			$count['down']		=	1800-($_SERVER['REQUEST_TIME']-$add_time-1);
			$count['branch']	=	floor($count['down']/60);
			$count['second']	=	($count['down']%60);
		}else if($pay_status == 2){
			$this->returnCode('40000050');
		}
		if($count['down']){
			if($count['down'] < 0){
				$this->returnCode('40000049');
			}
		}
		//支付方式
		$pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
		if(empty($pay_method) && $this->is_app_browser==false){
			$this->returnCode('40000052');
		}
		foreach($pay_method as $key=>$pm){
			if($key=='offline'){
				unset($pay_method[$key]);
			}elseif(empty($_SESSION['openid'])){
				unset($pay_method['weixin']);
			}
		}
		$arr	=	array(
			'now_money'	=>	floatval($this->user['now_money']),
			'count'	=>	$count,
			'order_total'	=>	$scenic_order['order_total'],
			'pay_method'	=>	$pay_method,
			'name'	=>	$name,
		);
		$this->returnCode(0,$arr);
	}

	#去支付
	public function go_pay(){
		$order_id = $_POST['order_id'];
		$where['order_id'] = $order_id;
		if($_POST['type']==1){
			$now_order = D('Scenic_aguide')->get_pay_order($this->user_info,$order_id);
			$now_order['order_info']['scenic_id'] = $now_order['order_info']['guide_id'];
		}else{
			$now_order = D('Scenic_order')->get_pay_order($this->user_info,$order_id);
		}
		if($now_order['error'] == 1){
			$this->error_tips($now_order['msg'],$now_order['url']);
		}
		$order_info = $now_order['order_info'];

		if($order_info['add_time']+30*60<$_SERVER['REQUEST_TIME']){
			$this->returnCode('40000053');
		}
		//判断积分是否够用 防止支付同时积分被改动

		//这里为了解决app支付
		if($_POST['use_balance']==1){
			$order_info['use_balance'] = 1;
		}else{
			$order_info['use_balance'] = 0;
		}

		//订单支付前
		if($order_info['order_type'] == 'ticket'){
			$order_model = D('Scenic_order');
			$save_result = $order_model->wap_befor_pay($order_info,$this->user);
		}else if($order_info['order_type'] == 'guide'){
			$order_model = D('Scenic_aguide');
			$save_result = $order_model->wap_befor_pay($order_info,$this->user);
			$order_model = D('Scenic_aguide_order');
			$order_info['order_name'] = '导游订单_'.$order_id;
		}
		if($save_result['error_code']){
			$this->error_tips($save_result['msg']);
		}else if($save_result['url']){
			$this->success_tips($save_result['msg'],$save_result['url']);
		}

		//需要支付的钱
		$pay_money = round($save_result['pay_money']*100)/100;

		$pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);

		if(empty($pay_method)){
			$this->returnCode('40000053');
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		if(empty($pay_method[$_POST['pay_type']])){
			$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
		}

		$pay_class_name = ucfirst($_POST['pay_type']);
		$import_result = import('@.ORG.pay.'.$pay_class_name);
		if(empty($import_result)){
			$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
		}

		//更新长id
		$nowtime = date("ymdHis");
		$orderid = $nowtime.rand(10,99).sprintf("%08d",$this->user_info);
		$data_tmp['pay_type'] = $_POST['pay_type'];
		$data_tmp['order_type'] = $order_info['order_type'];
		$data_tmp['order_id'] = $order_id;
		$data_tmp['orderid'] = $orderid;
		$data_tmp['addtime'] = $nowtime;
		if(!D('Tmp_orderid')->add($data_tmp)){
			$this->error_tips('更新失败，请联系管理员');
		}
		$save_pay_id = $order_model->where(array("order_id"=>$order_id))->setField('orderid',$orderid);
		if(!$save_pay_id){
			$this->error_tips('更新失败，请联系管理员');
		}else{
			$order_info['order_id']=$orderid;
		}

		$pay_class = new $pay_class_name($order_info,$pay_money,$_POST['pay_type'],$pay_method[$_POST['pay_type']]['config'],$this->user_session,1);
		$go_pay_param = $pay_class->pay();

		if(empty($go_pay_param['error'])){
			if(!empty($go_pay_param['url'])){
				$this->assign('url',$go_pay_param['url']);
				$this->display();
			}else if(!empty($go_pay_param['form'])){
				$this->assign('form',$go_pay_param['form']);
				$this->display();
			}else if(!empty($go_pay_param['weixin_param'])){
				$redirctUrl = C('config.site_url').'/wap.php?g=Wap&c=Scenic_pay&a=weixin_back&order_id='.$order_info['order_id'];
				$this->assign('redirctUrl',$redirctUrl);
				$this->assign('pay_money',$pay_money);
				$this->assign('weixin_param',json_decode($go_pay_param['weixin_param']));
				$this->display('weixin_pay');
			}else{
				$this->error_tips('调用支付发生错误，请重试。');
			}
		}else{
			$this->error_tips($go_pay_param['msg']);
		}
	}

	# 支付完成
	public function pay_complete(){
		$this->display();
	}
	# 支付方式
    protected function getPayName($label){
        $payName = array(
            'weixin' => '微信支付',
            'tenpay' => '财付通支付',
            'yeepay' => '银行卡支付(易宝支付)',
            'allinpay' => '银行卡支付(通联支付)',
            'chinabank' => '银行卡支付(网银在线)',
        );
        return $payName[$label];
    }

	//微信同步回调页面
	public function weixin_back(){
		switch($_GET['order_type']){
			case 'ticket':
				$now_order =$this->get_orderid('Scenic_order',$_GET['order_id']);
				break;
			case 'guide':
				// $now_order = D('Meal_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('Scenic_aguide_order',$_GET['order_id']);
				break;
			default:
				$this->error_tips('非法的订单');
		}

		if(empty($now_order)){
			$this->error_tips('该订单不存在');
		}
		$now_order['order_type'] = $_GET['order_type'];
		if($now_order['paid']){
			switch($_GET['order_type']){
				case 'ticket':
					$redirctUrl = C('config.site_url').'/wap.php?c=Scenic_user&a=ticket_order_details&order_id='.$now_order['order_id'];
					break;
				case 'guide':
					$redirctUrl = C('config.site_url').'/wap.php?c=Scenic_user&a=aguide_order_list';
					break;
			}
			redirect($redirctUrl);exit;
		}

		$pay_method = D('Config')->get_pay_method();
		$_GET['order_id'] = $now_order['order_id'];
		$now_order['order_id']  =   $now_order['orderid'];
		$import_result = import('@.ORG.pay.Weixin');
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		$pay_class = new Weixin($now_order,0,'weixin',$pay_method['weixin']['config'],$this->user_session,1);
		$go_query_param = $pay_class->query_order();
		if($go_query_param['error'] === 0){
			$go_query_param['order_param']['return']=1;
			switch($_GET['order_type']){
				case 'ticket':
					D('Scenic_order')->after_pay($go_query_param['order_param']);
					break;
				case 'guide':
					D('Scenic_aguide')->after_pay($go_query_param['order_param']);
					break;
			}
		}
		switch($_GET['order_type']){
			case 'ticket':
				$redirctUrl = C('config.site_url').'/wap.php?c=Scenic_user&a=ticket_order_details&order_id='.$_GET['order_id'];
				break;
			case 'guide':
				$redirctUrl = C('config.site_url').'/wap.php?c=Scenic_user&a=aguide_order_list';
				break;

		}
		if($go_query_param['error'] == 1){
			$this->error_tips('校验时发生错误！'.$go_query_param['msg'],$redirctUrl);
		}else{
			redirect($redirctUrl);
		}
	}
	//跳转通知
	public function return_url(){
		$pay_type = isset($_GET['paytype']) ? $_GET['paytype'] : $_GET['pay_type'];
		if($pay_type == 'weixin'){
			$array_data = json_decode(json_encode(simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA)), true);
			if($array_data && $array_data['attach'] != 'weixin'){
				$_GET['own_mer_id'] = $array_data['attach'];
			}
		}
		$pay_method = D('Config')->get_pay_method();
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		if(empty($pay_method[$pay_type])){
			$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
		}

		$pay_class_name = ucfirst($pay_type);
		$import_result = import('@.ORG.pay.'.$pay_class_name);
		if(empty($import_result)){
			$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
		}
		$pay_class = new $pay_class_name('','',$pay_type,$pay_method[$pay_type]['config'],$this->user_session,1);
		$get_pay_param = $pay_class->return_url();
		$get_pay_param['order_param']['return']=1;
		$offline = $pay_type!='offline'?false:true;


		if(empty($get_pay_param['error'])){
			if($get_pay_param['order_param']['order_type'] == 'ticket') {
				$now_order = $this->get_orderid('Scenic_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Scenic_order')->after_pay($get_pay_param['order_param']);
			}else if($get_pay_param['order_param']['order_type'] == 'guide'){
				$now_order = $this->get_orderid('Scenic_aguide_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Scenic_aguide')->after_pay($get_pay_param['order_param']);
			}else{
				$this->error_tips('订单类型非法！请重新下单。');
			}
			$urltype = isset($_GET['urltype']) ? $_GET['urltype'] : '';
			if(empty($pay_info['error'])){
				if($get_pay_param['order_param']['pay_type'] == 'weixin'){
					exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
				} elseif ($get_pay_param['order_param']['pay_type'] == 'baidu') {//百度的异步通知返回
					exit("<html><head><meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\"></head><body><h1>这是一个return_url页面</h1></body></html>");
				} elseif ('unionpay' == $get_pay_param['order_param']['pay_type'] && $urltype == 'back') {
					exit("验签成功");
				}
				$pay_info['msg'] = '订单付款成功！现在跳转.';
			}
			if(empty($pay_info['url'])){
				$this->error_tips($pay_info['msg']);
			}else{
				$pay_info['url'] = preg_replace('#/source/(\w+).php#','/wap.php',$pay_info['url']);
				if ($pay_info['error'] && $urltype == 'front') {
					$this->redirect($pay_info['url']);
					exit;
				}
				$this->assign('pay_info', $pay_info);
				C('DEFAULT_THEME','pure');
				$this->display('after_pay');
			}
		}else{
			$this->error_tips($get_pay_param['msg']);
		}
	}

	public function get_orderid($table,$orderid,$offline=0){
		$order =  D($table);
		$tmp_orderid = D('Tmp_orderid');
		if($offline){
			$now_order = $order->where(array('orderid'=>$orderid))->find();
		}else{
			$now_order = $order->where(array('orderid'=>$orderid))->find();
			if(empty($now_order)){
				$res = $tmp_orderid->where(array('orderid'=>$orderid))->find();
				$now_order = $order->where(array('order_id'=>$res['order_id']))->find();
				$order->where(array('order_id'=>$res['order_id']))->setField('orderid',$orderid);
				$now_order['orderid']=$orderid;
			}
		}
		if(empty($now_order)){
			$this->error_tips('该订单不存在');
		}
		return $now_order;
	}
	#微信支付弹出层
	public function get_weixin_param(){
		if(IS_POST){
			if(empty($this->user_session)){
				$this->error_tips('请先进行登录！',U('Login/index'));
			}
			$order_id = $_POST['order_id'];
			$where['order_id'] = $order_id;
			if($_POST['type']==1){
				$now_order = D('Scenic_aguide')->get_pay_order($this->user_session['uid'],$order_id);
				$now_order['order_info']['scenic_id'] = $now_order['order_info']['guide_id'];
			}else{
				$now_order = D('Scenic_order')->get_pay_order($this->user_session['uid'],$order_id);
			}

			if($now_order['error'] == 1){
				$this->error($now_order['msg'],$now_order['url']);
			}
			$order_info = $now_order['order_info'];

			//用户信息
			$now_user = D('User')->get_user($this->user_session['uid']);
			if(empty($now_user)){
				$this->error_tips('未获取到您的帐号信息，请重试！');
			}

			//这里为了解决app支付
			if($_POST['use_balance']==1){
				$order_info['use_balance'] = 1;
			}else{
				$order_info['use_balance'] = 0;
			}

			//订单支付前
			if($order_info['order_type'] == 'ticket'){
				$order_model = D('Scenic_order');
				$save_result = $order_model->wap_befor_pay($order_info,$now_user);
			}else if($order_info['order_type'] == 'guide'){
				$order_model = D('Scenic_aguide');
				$save_result = $order_model->wap_befor_pay($order_info,$now_user);
				$order_model = D('Scenic_aguide_order');
			}



			if($save_result['error_code']){
				$this->error($save_result['msg']);
			}else if($save_result['url']){
				$this->success_tips($save_result['msg'],$save_result['url']);
			}

			//需要支付的钱
			$pay_money = round($save_result['pay_money']*100)/100;

			$pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);

			if(empty($pay_method)){
				$this->error('系统管理员没开启任一一种支付方式！');
			}

			if(empty($pay_method['weixin'])){
				$this->error('您选择的支付方式不存在，请更新支付方式！');
			}



			//更新长id
			$nowtime = date("ymdHis");
			$orderid = $nowtime.rand(10,99).sprintf("%08d",$this->user_session['uid']);
			$data_tmp['pay_type'] = 'weixin';
			$data_tmp['order_type'] = $order_info['order_type'];
			$data_tmp['order_id'] = $order_id;

			$data_tmp['orderid'] = $orderid;
			$data_tmp['addtime'] = $nowtime;
			if(!D('Tmp_orderid')->add($data_tmp)){
				$this->error_tips('更新失败，请联系管理员');
			}

			$save_pay_id = $order_model->where(array("order_id"=>$order_id))->setField('orderid',$orderid);
			if(!$save_pay_id){
				$this->error_tips('更新失败，请联系管理员');
			}else{
				$order_info['order_id']=$orderid;
			}

			$scenic_id = $order_info['scenic_id'];
			$import_result = import('@.ORG.pay.Weixin');

			$pay_class = new Weixin($order_info,$pay_money,$_POST['pay_type'],$pay_method['weixin']['config'],$this->user_session,1);
			$go_pay_param = $pay_class->pay();

			if($go_pay_param['error']){
				$this->error($go_pay_param['msg']);
			}
			if(empty($go_pay_param['error'])) {
				if (!empty($go_pay_param['weixin_param'])) {
					$redirctUrl = C('config.site_url') . '/'.$this->indep_house.'?g=Wap&c=Scenic_pay&a=weixin_back&order_id=' . $order_id;
					$arr['redirctUrl'] = $redirctUrl;
					$arr['pay_money'] = $pay_money;
					$arr['weixin_param'] = json_decode($go_pay_param['weixin_param']);
					$arr['error'] = 0;
					echo json_encode($arr);
				} else {
					echo json_encode(array('error'=>1,'msg'=>$go_pay_param['msg']));
				}
			}
		}
	}
}
?>