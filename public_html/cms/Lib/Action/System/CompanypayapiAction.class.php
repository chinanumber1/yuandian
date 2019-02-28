<?php
/*
 * 付款管理
 *
 */
class CompanypayapiAction extends CommonAction{
	public function api(){
		if(empty($this->config['company_pay_open'])){
			echo json_encode(array('err_code'=>1003,'err_msg'=>'网站未开启软件付款功能'));
			exit();
		}
		if($_POST['webKey'] != $this->config['company_pay_encrypt']){
			echo json_encode(array('err_code'=>1001,'err_msg'=>'通信密钥错误，请重新填写'));
			exit();
		}
		if($_POST['action'] == 'saveOrder'){
			if($_POST['status'] == 'ok'){
				$condition_companypay = array(
					'pigcms_id'	 => $_POST['pigcms_id'],
				);
				$data_companypay = array(
					'trade_no' 	 => $_POST['trade_no'],
					'payment_no' => $_POST['payment_no'],
					'status'	 => '1',
					'pay_time'	 => strtotime($_POST['payment_time'])
				);
				if(D('Companypay')->where($condition_companypay)->data($data_companypay)->save()){
					echo json_encode(array('err_code'=>0,'err_msg'=>'订单保存成功'));
				}else{
					echo json_encode(array('err_code'=>1002,'err_msg'=>'订单保存失败，请重试'));
				}
				exit();
			}else if($_POST['status'] == 'del'){
				$condition_companypay = array(
					'pigcms_id'	 => $_POST['pigcms_id'],
				);
				$data_companypay = array(
					'status'	 => '2'
				);
				if(D('Companypay')->where($condition_companypay)->data($data_companypay)->save()){
					echo json_encode(array('err_code'=>0,'err_msg'=>'订单保存成功'));
				}else{
					echo json_encode(array('err_code'=>1002,'err_msg'=>'订单保存失败，请重试'));
				}
				exit();
			}
		}else{
			$condition_companypay['status'] = '0';
			if($_POST['webLastId']){
				$condition_companypay['pigcms_id'] = array('gt',$_POST['webLastId']);
			}
			
			$payList = D('Companypay')->where($condition_companypay)->order('`pigcms_id` ASC')->limit(10)->select();
			$returnList = array();
			foreach($payList as $value){
				$returnList[] = array(
					'pigcms_id'	=>	$value['pigcms_id'],
					'pay_type'	=>	$value['pay_type'],
					'alias_type'=>	$this->getType($value['pay_type']),
					'pay_id'	=>	$value['pay_id'],
					'openid'	=>	$value['openid'],
					'nickname'	=>	$value['nickname'],
					'money'		=>	$value['money'],
					'desc'		=>	$value['desc'],
					'add_time'	=>	date('Y-m-d H:i:s',$value['add_time']),
					'status'	=>	$value['status'],
				);
			}
			echo json_encode(array('err_code'=>0,'result'=>$returnList,'count'=>count($returnList)));
			exit();
		}
	}
	public function getType($pay_type){
		switch($pay_type){
			case 'merchant':
				return '商家';
			case 'user':
				return '用户';
			case 'house':
			case 'village':
				return '社区';
		}
	}
}