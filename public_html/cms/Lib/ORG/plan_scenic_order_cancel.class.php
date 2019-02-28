<?php
/*门票自动验证消费

 * */
class plan_scenic_order_cancel extends plan_base{
	public function runTask(){
		$condition['order_status']=1;
		$condition['ticket_time']=array('lt',date('Y-m-d',time()));
		$order_list  = M('Scenic_order')->where($condition)->select();
		fdump($order_list,'cancel');
		fdump(M(),'cancel',1);
		foreach ($order_list as $v) {
			$verify_count = M('Scenic_order_com')->where(array('order_id'=>$v['order_id'],'status'=>2))->count();
			$ticket_count = count(explode(',',$v['family_id']));
			if($verify_count>0&&$verify_count<$ticket_count){
				$ticket_list = M('Scenic_order_com')->where(array('order_id'=>$v['order_id'],'status'=>1))->select();
				$total_money = 0;
				foreach ($ticket_list as $vv) {
					$total_money += $vv['price'];
				}
				if($res = D('Scenic_money_list')->add_row($v['scenic_id'],1,$total_money,'该订单已过期，'.($ticket_count-$verify_count).'张门票未验证，自动计入商家余额',$v['order_id'])){
					fdump($res,'res_c',1);
					M('Scenic_order_com')->where(array('order_id'=>$v['order_id']))->setField('status',2);
				}
			}else{
				if($res = D('Scenic_money_list')->add_row($v['scenic_id'],1,$v['order_total'],'订单已过期，自动计入商家余额',$v['order_id'])){
					fdump($res,'res_c',1);
					M('Scenic_order')->where(array('order_id'=>$v['order_id']))->setField('order_status',2);
				}
			}
		}

		return true;
	}
}
?>