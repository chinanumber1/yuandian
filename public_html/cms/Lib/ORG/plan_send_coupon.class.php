<?php
	/*
	 * 派发优惠券，平台和商家
	 * */
class plan_send_coupon extends plan_base{
	public function runTask($param){
		$where['pigcms_id']= $param['id'];
		$res = M('Send_coupon_log')->where($where)->find();

		$plan_list = M('Process_plan')->where(array('file'=>'send_coupon'))->select();
		//防止服务器意外挂掉重新启动后又再次运行，二次进入时需要把该任务删除
		foreach($plan_list	 as $v){
			$tmp = unserialize($v['param']);
			if($res['status']==1 && $tmp['id']==$res['pigcms_id']){
				M('Progress_plan')->where(array('id'=>$v['id']))->delete();
				return true;
			}
		}
		M('Send_coupon_log')->where($where)->setField('status',1);
		$coupon_list = explode(',',$res['coupon_id']);
		if($res['group_id']){
			$group_id = explode(',',$res['group_id']);
			$where['gid'] = array('in',$group_id);
			$user_list = M('Card_userlist')->field('uid')->where($where)->select();
		}elseif($res['mer_id'] && $res['type']==1){
			$user_list = M('Card_userlist')->field('uid')->where(array('mer_id'=>$res['mer_id']))->select();
		}elseif($res['uid'] && $res['type']==2){
			$user_list[] = array('uid'=>$res['uid']);

		}else{
			$where['status'] = 1;
			$user_list = M('User')->where($where)->field('uid')->select();
		}
		if($res['mer_id']){
			$model = D('Card_new_coupon');
			$history_model = M('Card_coupon_send_history');

		}else{
			$model = D('System_coupon');
			$history_model = M('System_coupon_send_history');
		}

		foreach ($user_list as $v) {
			foreach ($coupon_list as $item) {
				$tmp = $model->had_pull($item,$v['uid']);
				$model->decrease_sku(0,1,$item);//网页领取完，微信卡券库存需要同步减少
				if($tmp['error_code']>0){
					$tmp['msg'] = '发送失败（'.$this->coupon_error($tmp['error_code']).')';
				}else{
					$tmp['msg'] = $this->coupon_error($tmp['error_code']);
				}

				$data['uid'] = $v['uid'];
				$data['coupon_id'] = $item;
				$res['mer_id'] && $data['mer_id'] = $res['mer_id'];
				$data['error_code']  =$tmp['error_code'];
				$data['msg']  =$tmp['msg'];
				$data['add_time']  =time();
				$history_model->add($data);
				$return[$tmp['coupon']['coupon_id']]['error_msg'] = $tmp['msg'];
				$return[$tmp['coupon']['coupon_id']]['coupon_name'] = $tmp['coupon']['name'];
				$return[$tmp['coupon']['coupon_id']]['send_code'] = $tmp['error_code'];
			}
		}

		return true;
	}

	public function coupon_error($error_code){
		switch($error_code) {
			case '0':
				$error_msg = '领取成功';
				break;
			case '1':
				$error_msg = '领取发生错误';
				break;
			case '2':
				$error_msg = '优惠券已过期';
				break;
			case '3':
				$error_msg = '优惠券已经领完了';
				break;
			case '4':
				$error_msg = '只允许新用户领取';
				break;
			case '5':
				$error_msg = '不能再领取了';
				break;
		}
		return $error_msg;
	}

}
?>