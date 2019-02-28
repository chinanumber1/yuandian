<?php
class Pay_async_recordModel extends Model{
	public function weixin_record($param,$xml){
		$order_id_arr = explode('_',$param['out_trade_no']);
		$data['type'] = $order_id_arr[0];
		$data['orderid'] = $order_id_arr[1];
		$data['third_id'] = $param['transaction_id'];
		$data['xml_date'] = $xml;
		$data['pay_type'] = 'weixin';
		$data['get_time'] = time();
		$data['paid'] = $this->order_status($data['type'],$data['orderid'] );
		$this->add($data);
	}

	public function alipay_record($param){

		$data['type'] = $param['order_type'];
		$data['orderid'] = $param['order_id'];
		$data['third_id'] = $param['third_id'];
		$data['xml_date'] = serialize($param);
		$data['pay_type'] = 'alipay';
		$data['get_time'] = time();
		$data['order_status'] = $this->order_status($data['type'],$data['orderid'] );
		$this->add($data);
	}

	public function order_status($type,$orderid){
		if($type=='recharge'){
			$type='user_recharge';
		}
		$now_order = $this->get_orderid(ucfirst($type).'_order',$orderid);
		//$now_order = M(ucfirst($type).'_order')->where(array('orderid'=>$orderid))->find();
		return $now_order['paid']?$now_order['paid']:0;
	}

	public function get_orderid($table,$orderid){
		$order =  D($table);
		$tmp_orderid = D('Tmp_orderid');

		$now_order = $order->where(array('orderid'=>$orderid))->find();
		if(empty($now_order)){
			$res = $tmp_orderid->where(array('orderid'=>$orderid))->find();
			$now_order = $order->where(array('order_id'=>$res['order_id']))->find();
			//$order->where(array('order_id'=>$res['order_id']))->setField('orderid',$orderid);
			//$now_order['orderid']=$orderid;
		}

		if(empty($now_order)){
			return '';
		}else{
//            $tmp_orderid->where(array('order_id'=>$now_order['order_id']))->delete();
		}

		return $now_order;
	}
}
?>