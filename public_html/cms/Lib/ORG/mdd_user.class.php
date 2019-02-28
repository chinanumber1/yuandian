<?php
/*mdd 操作

 * */
class mdd_user extends plan_base{
	public function select($uid){
		$now_user = D('User')->get_user($uid);
		$data_mdd_param['token'] = $now_user['mdd_token'];
		$data_mdd['data'] = json_encode($data_mdd_param);
		import('ORG.Net.Http');
		$http = new Http();
		$return = Http::curlPost(C('config.mdd_api_url').'O2o/userInfo', $data_mdd);
		if($return['respCode'] == '1000'){
			$nowUser = D('User')->get_user($return['data']['id'],'mdd_uid');
			$condition_user['mdd_uid'] = $return['data']['id'];
			$data_user = array(
				'nickname' 		=> $return['data']['nickname'],
				'phone' 		=> $return['data']['mobile'],
				'avatar' 		=> $return['data']['headerimg'] ? $return['data']['headerimg'] : '',
				'now_money' 	=> $return['data']['wallet'],
				'score_count' 	=> $return['data']['Dcoin'],
				'last_time' 	=> time(),
				'mdd_last_time' => uniqid(),
			);
			if(!D('User')->where($condition_user)->data($data_user)->save()){
				return array('error_code' => true, 'msg' => '用户信息 查询保存失败！请联系管理员协助解决。');
			}
		}else{
			return array('error_code' => true, 'msg' => '查询发生错误：'.$return['respContent']);
		}
		return array('error_code' => false, 'msg' => 'ok');
	}
	public function add_money($uid,$money,$desc){
		$now_user = D('User')->get_user($uid);
		$data_mdd_param['token'] = $now_user['mdd_token'];
		$data_mdd_param['type'] = 3;
		$data_mdd_param['orderId'] = $now_user['uid'];
		$data_mdd_param['money'] = $money;
		$data_mdd_param['reason'] = $desc;
		$data_mdd['data'] = json_encode($data_mdd_param);
		import('ORG.Net.Http');
		$http = new Http();
		$return = Http::curlPost(C('config.mdd_api_url').'O2o/getMoneyInfo', $data_mdd);
		if($return['respCode'] != '1000'){
			return array('error_code' => true, 'msg' => '充值发生错误：'.$return['respContent']);
		}
		return array('error_code' => false, 'msg' => 'ok');
	}
	public function use_money($uid,$money,$desc){
		$now_user = D('User')->get_user($uid);
		$data_mdd_param['token'] = $now_user['mdd_token'];
		$data_mdd_param['type'] = 2;
		$data_mdd_param['orderId'] = $now_user['uid'];
		$data_mdd_param['money'] = $money;
		$data_mdd_param['reason'] = $desc;
		$data_mdd['data'] = json_encode($data_mdd_param);
		import('ORG.Net.Http');
		$http = new Http();
		$return = Http::curlPost(C('config.mdd_api_url').'O2o/getMoneyInfo', $data_mdd);
		if($return['respCode'] != '1000'){
			return array('error_code' => true, 'msg' => '提现发生错误：'.$return['respContent']);
		}
		return array('error_code' => false, 'msg' => 'ok');
	}
	public function add_score($uid,$score,$desc){
		$now_user = D('User')->get_user($uid);
		$data_mdd_param['token'] = $now_user['mdd_token'];
		$data_mdd_param['type'] = 2;
		$data_mdd_param['orderId'] = $now_user['uid'];
		$data_mdd_param['integral'] = $score;
		$data_mdd_param['reason'] = $desc;
		$data_mdd['data'] = json_encode($data_mdd_param);
		import('ORG.Net.Http');
		$http = new Http();
		$return = Http::curlPost(C('config.mdd_api_url').'O2o/operationIntegral', $data_mdd);
		if($return['respCode'] != '1000'){
			return array('error_code' => true, 'msg' => '增加积分发生错误：'.$return['respContent']);
		}
		return array('error_code' => false, 'msg' => 'ok');
	}
	public function use_score($uid,$score,$desc){
		$now_user = D('User')->get_user($uid);
		$data_mdd_param['token'] = $now_user['mdd_token'];
		$data_mdd_param['type'] = 3;
		$data_mdd_param['orderId'] = $now_user['uid'];
		$data_mdd_param['integral'] = $score;
		$data_mdd_param['reason'] = $desc;
		// dump($data_mdd_param);die;
		$data_mdd['data'] = json_encode($data_mdd_param);
		import('ORG.Net.Http');
		$http = new Http();
		$return = Http::curlPost(C('config.mdd_api_url').'O2o/operationIntegral', $data_mdd);
		if($return['respCode'] != '1000'){
			return array('error_code' => true, 'msg' => '扣除积分发生错误：'.$return['respContent']);
		}
		return array('error_code' => false, 'msg' => 'ok');
	}
}
?>