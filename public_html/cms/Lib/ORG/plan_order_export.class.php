<?php
	/*
	 * 派发优惠券，平台和商家
	 * */
class plan_order_export extends plan_base{
	public function runTask($param){

		$where['export_id']= $param['export_id'];
		//$where['export_id']= $param['export_id'];

		$res = M('Export_log')->where($where)->find();

		$plan_list = M('Process_plan')->where(array('file'=>'order_export'))->select();

		foreach($plan_list	 as $v){
			$tmp = unserialize($v['param']);
			if($tmp['export_id']==$res['export_id']){
				M('Process_plan')->where(array('id'=>$v['id']))->delete();
				//return true;
			}
		}

		if(D('Order')->order_export_runtask($param)){
			M('Export_log')->where($where)->setField('status',1);
		}

		return true;
	}
}
?>