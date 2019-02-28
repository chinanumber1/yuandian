<?php
class Sms_buy_orderModel extends Model{

	public function after_pay($order_param){

        
		if($order_param['pay_type']!=''){
			$where['orderid'] = $order_param['order_id'];
		}else{
			$where['order_id'] = $order_param['order_id'];
		}

		$now_order = $this->field(true)->where($where)->find();
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在');
		}else if($now_order['paid'] == 1){
			return array('error'=>1,'msg'=>'该订单已付款！','url'=>$this->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay'],$now_order));
		}else{
			//得到当前社区信息，不将session作为调用值，因为可能会失效或错误。
			$now_village = D('House_village')->get_one($now_order['type_id']);
			if(empty($now_village)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的社区，请联系管理员！');
			}

			$data_user_recharge_order = array();
			$data_user_recharge_order['pay_time'] = $_SERVER['REQUEST_TIME'];
			$data_user_recharge_order['pay_type'] = $order_param['pay_type'];
			$data_user_recharge_order['paid'] = 1;
			if($this->where($where)->save($data_user_recharge_order)){
				
				M('House_village')->where(array('village_id'=>$now_order['type_id']))->setInc('now_sms_number',$now_order['sms_number']);

				$order_param['village_id'] = $now_order['type_id'];
				$order_param['desc'] ='社区短信充值';
				$return  = D('Village_money_list')->add_money($order_param);
	
				return array('error'=>0,'msg'=>'短信充值成功！','url'=>$this->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay'],$now_order));

			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}

	public function get_pay_after_url($label,$is_mobile = false,$now_order){
		if($label){
			$labelArr = explode('_',$label);

			if($labelArr[0] == 'wap'){
				switch($labelArr[1]){
				}
			}else{


			}
		}else{
			if($is_mobile){
				return U('Village_money/sms_note');
			}else{
				return  U('Village_money/sms_note');
			}
		}
	}


}
?>