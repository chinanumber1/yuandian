<?php
class Alipay_jumpAction extends BaseAction{
	public function index(){
		$order_type = $_GET['type'];
		$order_id = $_GET['order_id'];
		$token = $_GET['token'];
		if(empty($token)){
			$token = str_shuffle(mt_rand(100000,999999).uniqid().mt_rand(100000,999999));
			$data['uid'] = $this->user_session['uid'];
			$data['token'] = $token;
			if(M('Alipay_token')->add($data)){
				$url = U('index',array('type'=>$_GET['order_type'],'order_id'=>$_GET['order_id'],'token'=>$token));
				redirect($url);die;
			}else{
				$this->error_tips('添加失败，请返回后重试');
			}
		}
		if(!$this->is_wexin_browser){
			if($_GET['type']=='ticket'){
				$pay_url = $this->config['site_url'].'/wap.php?g=Wap&c=Scenic_pay&a=index&order_id='.$order_id;
			}else{
				$pay_url = $this->config['site_url'].'/wap.php?g=Wap&c=Pay&a=check&order_id='.$order_id.'&type='.$order_type;
			}
			if($uid = M('Alipay_token')->where(array('token'=>$token))->getField('uid')){

				$result = D('User')->autologin('uid',$uid);

				if(empty($result['error_code'])){
					$now_user = $result['user'];
					session('user',$now_user);
					redirect($pay_url);
					exit;
				}else if($result['error_code'] && $result['error_code'] != 1001){
					$this->error_tips($result['msg'],U('Login/index'));
				}
			}
		}else{
			$this->display();
		}
	}


	public  function ajax_get_order_paid(){
		$order_type = $_POST['type'];
		$order_id = $_POST['order_id'];
		if ($order_type == 'takeout' || $order_type == 'food' || $order_type == 'foodPad') {
			$order_table = 'Meal_order';
		}else if($order_type=='recharge'){
			$order_table = 'User_recharge_order';
		}else if($order_type=='balance-appoint'){
			$order_table = 'Appoint_order';
		}else{
			$order_table = ucfirst($order_type).'_order';
		}
		if($order_type=='balance-appoint'){
			$paid = M($order_table)->where(array('order_id'=>$order_id))->field('paid,orderid,service_status')->find();
		}else{
			$paid = M($order_table)->where(array('order_id'=>$order_id))->field('paid,orderid')->find();
		}
		if($paid['paid'] && ($order_type!='balance-appoint'||$paid['service_status']==1)){
			$this->success($paid['orderid']);
		}else{
			$this->error('error');
		}
	}
}


?>
