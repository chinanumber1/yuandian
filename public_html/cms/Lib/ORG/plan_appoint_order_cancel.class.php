<?php
/*拼团自动退款

 * */
class plan_appoint_order_cancel extends plan_base{
	public function runTask(){
		$where['paid'] = 0;
		$where['service_status'] = 0;
		$where['_string'] = 'order_time<'.(time()-C('config.appoint_cancel_over_time')*60);
		$order_list  = M('Appoint_order')->field('order_id')->where($where)->select();

		if(!empty($order_list)){
			foreach ($order_list as $v){
				$this->keepThread();
				$data['is_del'] = 5;
				M('Appoint_order')->where(array('order_id'=>$v['order_id']))->save($data);
			}
		}
		return true;
	}

}
?>