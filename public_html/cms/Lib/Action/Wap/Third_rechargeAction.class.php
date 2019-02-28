<?php
	class Third_rechargeAction extends BaseAction{
		public $price_arr = array(10,20,30,50,100,300);
		private $recharge,$now_user;
		public function __construct()
		{
			parent::__construct();
			if(empty($this->user_session)&&!isset($_POST['sporder_id'])){
				if($this->is_app_browser){
					$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
					$this->error_tips('请先进行登录！',U('Login/index',$location_param));
				}else{
					$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
					redirect(U('Login/index',$location_param));
				}
			}
			import('@.ORG.mobile_recharge');
			$appkey = $this->config['mobile_recharge_APIKey'];
			$openid = $this->config['mobile_recharge_openid'];
			$this->recharge = new mobile_recharge($appkey,$openid);
			$this->now_user = D('User')->get_user($this->user_session['uid']);
		}

		//手机充值异步通知 聚合接口
		public function mobile_recharge_return(){
			$appkey = $this->config['mobile_recharge_APIKey'];  //您申请的数据的APIKey

			$sporder_id = addslashes($_POST['sporder_id']); //聚合订单号
			$orderid = addslashes($_POST['orderid']); //商户的单号
			$sta = addslashes($_POST['sta']); //充值状态
			$sign = addslashes($_POST['sign']); //校验值

			$local_sign = md5($appkey.$sporder_id.$orderid); //本地sign校验值

			if ($local_sign == $sign) {
				$data['status'] = $sta+3;
				M('Mobile_recharge_order')->where(array('orderid'=>$orderid))->save($data);
			}
		}

		public function mobile_recharge_wx_refund(){
			$appkey = $this->config['mobile_recharge_APIKey'];  //您申请的数据的APIKey
			$order_id = $_GET['order_id'];
			$now_order =  D('Mobile_recharge_order')->get_order_by_id($order_id);
			$return  = $this->recharge->sta($now_order['orderid']);
			if($return['error_code'] && $return['result']['game_state']==9){
				$refund  = D('Plat_order')->order_refund(array('business_type'=>'mobile_recharge','business_id'=>$now_order['order_id']));
				if(!$return['error']){
					$return['msg'] = '充值失败，已退款';
					$data['status'] = 5;
					$data['label'] .= serialize($return);
					M('Mobile_recharge_order')->where(array('order_id'=>$now_order['order_id']))->save($data);
					$this->success_tips('退款成功');
				}else{
					$this->success_tips('退款成功',U('mobile_recharge_detial',array('order_id'=>$order_id)));
				}
			}else{
				$this->error_tips('退款失败，请联系管理员');
			}

		}

		//手机充值同步请求
		public  function mobile_recharge_back($flag =false){
			$order_id = $_GET['order_id']; //第三方id
			$now_order = D('Mobile_recharge_order')->get_order_by_id($order_id);

			if(!$now_order['paid'] && !$flag){
				$this->error_tips('订单未支付,请先重新支付',U('mobile_recharge'));
			}
			$res =  $this->recharge->sta($now_order['orderid']);

			$data['status'] = $res['result']['game_state']+3; //成功
			if(!$res['error_code']){
				$data['label']=serialize($res['result']);
			}
			if($now_order['status']!=5 && $now_order['status']!=6){
				//redirect(U('mobile_recharge_detial',array('order_id'=>$_GET['order_id'])));
				M('Mobile_recharge_order')->where(array('order_id'=>$_GET['order_id']))->save($data);
			}
			switch($res['result']['game_state']){
				case 0:
					$res['reason']  = '您的订单已提交成功，正在等待服务商充值';
					break;
				case 1:
					$res['reason']  = '充值成功';
					break;
				case 12:
					$res['reason']  = '充值失败';
					break;
			}
			if($now_order['status']==5 || $now_order['status']==6){
				$res['reason'] = '充值失败,已退款';
			}
			!$flag && !$res['error_code'] && $this->success_tips($res['reason'],U('mobile_recharge_detial',array('order_id'=>$_GET['order_id'])));
			!$flag && $res['error_code'] && $this->error_tips($res['reason'],U('mobile_recharge_detial',array('order_id'=>$_GET['order_id'])));
			return ;
		}


		public function mobile_recharge(){

			$this->assign('price_arr',$this->price_arr);
			$this->display();
		}

		public function ajax_get_phone()
		{
			$money = $_POST['money']>0?$_POST['money']:10;
			$res =$this->recharge->telquery($_POST['phone'],$money);
			echo json_encode($res,true);die;
		}

		public function mobile_recharge_pay(){
			$phone = $_GET['phone'];
			$money = $_GET['money'];
			if($money<=0){
				$this->error_tips('无法充值，请联系管理员');
			}
			$res = $this->recharge->telcheck($phone,$money);
			$result = $this->recharge->telquery($phone,$money);
			if($res && !$result['error_code'] ){
				$data['old_money'] = $money;
				$money = $result['result']['inprice'];
				$data['phone'] = $phone;
				$data['money'] = $money;
				$data['uid'] = $this->now_user['uid'];
				$data['add_time'] =$_SERVER['REQUEST_TIME'];
				$data['orderid'] =date('YmdHis',$_SERVER['REQUEST_TIME']).$this->now_user['uid'].$data['old_money'];
				if($order_id= D('Mobile_recharge_order')->add($data)){
					$pay_order_param = array(
							'business_type' => 'mobile_recharge',
							'business_id' => $order_id,
							'order_name' => '话费充值',
							'uid' => $this->now_user['uid'],
							'total_money' => $money,
							'store_id' => '',
							'wx_cheap' => 0,
					);
					$result = D('Plat_order')->add_order($pay_order_param);
					if ($result['error_code']) {
						$this->error_tips('支付失败稍后重试');
					} else {
						redirect(U('Pay/check', array('order_id' => $result['order_id'], 'type' => 'plat')));
					}
				}else{

					$this->error_tips('无法充值，请联系管理员1');
				}
			}else{
				$this->error_tips('该号码无法充值');
			}

		}

		public function mobile_recharge_detial(){
			$this->mobile_recharge_back(true);
			$now_order = D('Mobile_recharge_order')->get_order_by_id($_GET['order_id']);
			$order = D('Plat_order')->get_order_by_business_id(array('business_type'=>'mobile_recharge','business_id'=>$_GET['order_id']));

			switch($now_order['status']){
				case 3:
					$now_order['status_txt']  = '您的订单已提交成功，正在等待服务商充值';
					break;
				case 4:
					$now_order['status_txt']  = '充值成功';
					break;
				case 5:
				case 6:
					$now_order['label'] = unserialize($now_order['label']);
					$now_order['status_txt']  = $now_order['label']['msg'];
					break;
				case 12:
					$now_order['status_txt']  = '充值失败';
					break;
			}
			$this->assign('order',$order);
			$this->assign('now_order',$now_order);
			$this->display();

		}

		public function mobile_recharge_list(){
			$order_list =  M('Mobile_recharge_order')->where(array('uid'=>$this->now_user['uid']))->order('order_id DESC')->select();

			$this->assign('order_list',$order_list);
			$this->display();
		}
		public function ajax_mobile_recharge_order_list(){
			isset($_GET['status']) && $_GET['status']>=0 && $where['status'] = intval($_GET['status']);
			if($_GET['status']==12){
				$where['status'] = array('in',array(5,6,12));
			}
			$where['uid'] = $this->user_session['uid'];//array('uid' => $this->user_session['uid'], 'status' => array('lt', 3));
			$where['paid'] = 1;
			$order_list = D("Mobile_recharge_order")->field(true)->where($where)->order('order_id DESC')->select();

			foreach($order_list as $key=>$val){
				$order_list[$key]['order_url'] = U('Third_recharge/mobile_recharge_detial', array('order_id' => $val['order_id']));
			}

			if(!empty($order_list)){
				exit(json_encode(array('status'=>1,'order_list'=>$order_list)));
			}else{
				exit(json_encode(array('status'=>0,'order_list'=>$order_list)));
			}
		}

	}


?>
