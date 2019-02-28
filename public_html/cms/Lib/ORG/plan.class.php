<?php
class plan{
	/*
	 *  添加任务
	 *
	 *  param array 消息参数
	 *
	 *  param.type   1为短信通知，2为模板消息，3为图文消息，4为APP通知
	 *
	 *
	 *
	 *  param.content 不同的业务，不同的值，传数组
	 *
	 *  param.send_time  发送时间
	 *
	 *
	 *
	 *
	 */
	public function addTask($param){
		if(!is_array($param) || empty($param)){
			return array('code'=>'1000','msg'=>'参数必须是数组');
		}
		$data['add_time'] = time();

		if(!empty($param['param']) && !is_array($param['param'])){
			return array('code'=>'1002','msg'=>'任务参数 param 必须为空是数组');
		}
		if(!empty($param['param'])){
			$data['param'] = serialize($param['param']);
		}
		
		$param['plan_time'] = intval($param['plan_time']);
		if(empty($param['plan_time'])){
			return array('code'=>'1003','msg'=>'任务类别 plan_time 参数必填且是时间戳格式');
		}
		$data['plan_time'] = $param['plan_time'];
		
		$param['space_time'] = intval($param['space_time']);
		if(!empty($param['space_time'])){
			$data['space_time'] = $param['space_time'];
		}
		if(!empty($param['file'])){
			$data['file'] = $param['file'];
		}
		if(!empty($param['url'])){
			$data['url'] = $param['url'];
		}
		$id = M('Process_plan')->data($data)->add();
		if($id){
			return array('code'=>'0','msg'=>'success','id'=>$id);
		}else{
			return array('code'=>'1004','msg'=>'任务添加失败，请重试');
		}
	}
	public function delTask($id){
		M('Process_plan')->where(array('id'=>$id))->delete();
	}
}
?>