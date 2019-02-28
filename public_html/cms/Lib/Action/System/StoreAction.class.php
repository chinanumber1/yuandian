<?php
/*
 * 到店消费
 *
 * @  BuildTime  2016/8/13 15:41
 */

class StoreAction extends BaseAction{
	public function buy_order(){
		$store_order = M('Store_order');
		import('@.ORG.system_page');
		$where = array('paid' => 1, 'from_plat' => 1);
		
		$count = $store_order->where($where)->count();
		$p = new Page($count, 20);

		$sql = "SELECT s.*, u.nickname, u.phone, m.name AS merchant_name, ms.name AS store_name FROM " . C('DB_PREFIX') . "store_order AS s LEFT JOIN ".C('DB_PREFIX')."merchant AS m ON s.mer_id=m.mer_id LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS ms ON s.store_id=ms.store_id LEFT JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid WHERE s.paid=1 AND s.from_plat=1 ORDER BY s.order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
		$order_list = M()->query($sql);
		foreach ($order_list as &$l) {
			$l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'], $l['is_mobile_pay']);
		}
		$pagebar = $p->show();
		
		$this->assign(array('order_list' => $order_list, 'pagebar' => $pagebar));
		$this->display();
	}
	public function store_ticket(){
		if(IS_POST){
			$database_config = D('Config');
			foreach($_POST as $key=>$value){
				$data['name'] = $key;
				$data['value'] = trim(stripslashes(htmlspecialchars_decode($value)));
				$database_config->data($data)->save();
			}
			$this->success('修改成功');
		}else{
			$this->display();
		}
	}
	public function arrival_order(){
		$store_order = M('Store_order');
		import('@.ORG.system_page');
		$where = array('paid' => 1, 'from_plat' => array('in','2,3'));
		
		$count = $store_order->where($where)->count();

		$p = new Page($count, 20);

		$sql = "SELECT s.*, u.nickname, u.phone, m.name AS merchant_name, ms.name AS store_name FROM " . C('DB_PREFIX') . "store_order AS s LEFT JOIN ".C('DB_PREFIX')."merchant AS m ON s.mer_id=m.mer_id LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS ms ON s.store_id=ms.store_id LEFT JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid WHERE s.paid=1 AND (s.from_plat=2 OR s.from_plat=3) ORDER BY s.order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
		$order_list = M()->query($sql);
		foreach ($order_list as &$l) {
			$l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'], $l['is_mobile_pay']);
		}
		$pagebar = $p->show();
		
		$this->assign(array('order_list' => $order_list, 'pagebar' => $pagebar));
		$this->display();
	}
}