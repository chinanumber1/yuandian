<?php
class Baidu_orderModel extends Model
{
	public function get_bf_order($order_id, $type, $is_own = 0)
	{
		$flag = true;
		if ($order = $this->field(true)->where(array('order_id' => $order_id, 'type' => $type))->find()) {
			return $order['order_no'];
		}
		while ($flag) {
			$order_no = date("YmdHis"). sprintf('%06d', rand(0, 999999));
			$bf_order = $this->field(true)->where(array('order_no' => $order_no))->find();
			if ($bf_order && ($bf_order['order_id'] != $order_id || $bf_order['type'] != $type)) {
				continue;
			} elseif (empty($bf_order)) {
				if (!($this->add(array('order_id' => $order_id, 'order_no' => $order_no, 'is_own' => intval($is_own), 'type' => $type)))) {
					continue;
				}
			}
			$flag = false;
			break;
		}
		
		return $order_no;
	}

	public function get_source_order($bf_order_id)
	{
		$bf_order = $this->field(true)->where(array('order_no' => $bf_order_id))->find();
		return $bf_order;
	}
}