<?php

class Distributor_agentAction extends BaseAction{
	public function __construct()
	{
		parent::__construct();
		if (empty($this->user_session)) {
			if ($this->is_app_browser) {
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				$this->error_tips('请先进行登录！', U('Login/index', $location_param));
			} else {
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				redirect(U('Login/index', $location_param));
			}
		}
		$this->user_session = D('User')->get_user($this->user_session['uid']);
	}
	public function index(){
		$uid = $this->user_session['uid'];

		$agent = M('Distributor_agent')->where(array('uid'=>$uid,'type'=>1))->find();
		if(empty($agent)){
			$this->buy_distributor();
		}else{
			redirect(U('My/my_spread'));
		}
	}



	public  function agent(){
		$uid = $this->user_session['uid'];

		$agent = M('Distributor_agent')->where(array('uid'=>$uid,'type'=>2))->find();
		$spread_count = M('Merchant')->where(array('spread_code'=>$this->user_session['spread_code']))->count();
		$spread_zhengchang_count = M('Merchant')->where(array('spread_code'=>$this->user_session['spread_code'],'status'=>1))->count();
//		M('Merchant_spread_list')->where(array())
		if(empty($agent)){
			$this->buy_agent();
		}
		$this->assign('agent',$agent);
		$this->assign('spread_count',$spread_count);
		$this->assign('spread_zhengchang_count',$spread_zhengchang_count);
		$this->display();
	}

	//购买分销员
	public  function buy_distributor(){
		if(IS_POST){

		}else{
			$adver_agent = D('Adver')->get_adver_by_key('buy_distributor',1);

			$this->assign('adver_agent',$adver_agent[0]);
			$money = $this->config['buy_distributor_money'];
			$this->assign('money',$money);
			$this->display('buy_distributor');die;
		}
	}

	public  function buy_agent(){
		if(IS_POST){

		}else{
			$adver_agent = D('Adver')->get_adver_by_key('buy_agent',1);

			$this->assign('adver_agent',$adver_agent[0]);
			$money = $this->config['buy_agent_money'];
			$this->assign('money',$money);
			$this->display('buy_agent');die;
		}
	}

	//购买代理商 分销员
	public function buy(){
		if($_GET['agree']==1){
			$data_distributor_order['add_time'] = $_SERVER['REQUEST_TIME'];
			$data_distributor_order['uid'] = $this->user_session['uid'];
			$data_distributor_order['type'] = $_GET['type'];
			$data_distributor_order['order_name'] = $_GET['type']==1?'开通分销员':'开通代理商';
			$money= $_GET['type']==1?$this->config['buy_distributor_money']:$this->config['buy_agent_money'];
			$data_distributor_order['money']  = $money;
			if ($order_id = M('Distributor_agent_order')->data($data_distributor_order)->add()) {
				$pay_order_param = array(
						'business_type' => 'distributor_agent',
						'business_id' => $order_id,
						'order_name' => $data_distributor_order['order_name'],
						'uid' => $this->user_session['uid'],
						'total_money' => $money,
				);
				$plat_order_result = D('Plat_order')->add_order($pay_order_param);
				if ($plat_order_result['error_code']) {
					$this->error_tips($plat_order_result['error_msg']);
				} else {
					redirect(U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')));
				}
			} else {
				$this->error_tips('订单创建失败，请重试。');
			}
		}else{
			if($_GET['type']==1){

				$this->error_tips('必须同意《分销员协议》');
			}else if($_GET['type']==2){

				$this->error_tips('必须同意《代理商协议》');
			}
		}
	}

	public function agent_log(){
		$this->display();
	}

	public function agent_money_log(){
		$this->display();
	}

	//	交易记录json
	public function agent_money_json(){
		$page	=	$_POST['page'];
		$transaction	=	D('Distributor_agent')->get_agent_spread_list($this->user_session['uid'],$page,20);
		$transaction['count'] = count($transaction['money_list']);
		foreach($transaction['money_list'] as $k=>&$v){
			$v['time_s']	=	date('Y/m/d H:i',$v['add_time']);
			$v['money']	=	floatval($v['money']);
		}
		echo json_encode($transaction);
	}

	public function agent_spread_json(){
		$page	=	$_POST['page'];
		$transaction	=	D('Distributor_agent')->get_list($this->user_session['uid'],$page,20);
		$transaction['count'] = count($transaction['money_list']);
		foreach($transaction['money_list'] as $k=>$v){
			$transaction['money_list'][$k]['time_s']	=	date('Y/m/d H:i',$v['add_time']);
		}
		echo json_encode($transaction);
	}
}