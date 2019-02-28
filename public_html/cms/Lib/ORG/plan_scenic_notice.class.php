<?php
/*门票自动验证消费

 * */
class plan_scenic_notice extends plan_base{
	public function runTask(){
		$where['open_notice'] =1;
		$where['notice_time'] =array('neq','00:00:00');
		$scenic_list = M('Scenic_list')->where($where)->select();
		if(empty($scenic_list)){
			return true;
		}
		$now_time = time();

		$time_zero = strtotime(date('Y-m-d'),$now_time);
		foreach ($scenic_list as $item) {
			$notice_time = strtotime($item['notice_time']);
			$time_cha =$now_time-$notice_time;
			if($time_cha>0 && $time_cha<60){
				$scenic_id = $item['scenic_id'];

				$condition_log['_string'] =  's.last_time >'.$time_zero .' AND s.last_time<'.$notice_time;
				$condition_log['s.scenic_id'] =  $scenic_id;
				$ticket_list = M('Scenic_into_log')->join('as s LEFT JOIN '.C('DB_PREFIX').'scenic_ticket t ON t.ticket_id = s.ticket_id')->field('s.ticket_id,t.*')->where($condition_log)->group("s.ticket_id")->select();

				$now_scenic = $item;

				$where['_string']  = 'last_time >'.$time_zero .' AND last_time<'.$notice_time;
				$into_num = 0;
				foreach ($ticket_list as $v) {
					$where['ticket_id'] = $v['ticket_id'];
					$where['scenic_id'] = $scenic_id;
					//$tmp['into_num'] =  M('Scenic_into_num')->where($where)->sum('num');
					$subQuery = M('Scenic_into_log')->field('num')->where($where)->group("order_id")->buildSql();
					$tmp['into_num'] =  M()->table($subQuery.' a')->count();
					$tmp['into_num'] = $tmp['into_num']?$tmp['into_num']:0;
					$into_num += $tmp['into_num'];
				}
				$sale_where['scenic_id'] = $scenic_id;
				$sale_where['paid'] = 2;
				$sale_where['_string'] = 'pay_time >'.$time_zero .' AND pay_time<'.$notice_time;
				$arr['sale_num'] = intval(M('Scenic_order')->where($sale_where)->sum('ticket_num'));
				$arr['order_total'] = M('Scenic_order')->where($sale_where)->sum('order_total');
				$arr['order_total'] = floatval($arr['order_total']);
				$arr['scenic_id'] = $scenic_id;
				$arr['name'] = $now_scenic['scenic_title'];
				$arr['into_num'] = $into_num;
				$arr['desc'] = $now_scenic['scenic_title'] .' '.date("Y年m月d日",$time_zero)." 售票:{$arr['sale_num']}张 入园：".$into_num.'人';

				$sms_time = date("Y-m-d H:i",$notice_time);

				$sms_data = array( 'type' => 'scenic');
				$sms_data['mer_id'] =$item['company_id'];
				$sms_data['mobile'] = $now_scenic['scenic_phone'];
				$sms_data['sendto'] = 'scenic';
				$sms_data['content'] = "景区数据通知，{$arr['name']}于{$sms_time}的入园数为{$arr['into_num']}人，售票额为{$arr['order_total']}元。";
				Sms::sendSms($sms_data);

			}
		}
		return true;
	}
}
?>