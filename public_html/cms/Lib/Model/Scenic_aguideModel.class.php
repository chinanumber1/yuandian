<?php
/*
 * 向导的model
 *   Writers    hanlu
 *   BuildTime  2016/07/08 16:00
 */
class Scenic_aguideModel extends Model{
	# 通过搜索获取该城市下的全部向导
	public function city_get_all_aguide($page=0,$city_id=0){
		if($city_id == 0){
			$sql = 'select a.*,b.avatar,b.real_name from pigcms_scenic_aguide as a, pigcms_user as b where a.user_id=b.uid AND b.real_name=1 AND b.`status`=1 AND a.`guide_status`=1 order by a.update_time DESC limit '.$page.',10';
		}else{
			$sql = 'select a.*,b.avatar,b.real_name from pigcms_scenic_aguide as a, pigcms_user as b where a.user_id=b.uid AND a.city_id='.$city_id.' AND b.real_name=1 AND b.`status`=1 AND a.`guide_status`=1 order by a.update_time DESC limit '.$page.',10';
		}
		$aguide	=	M()->query($sql);
		if($aguide){
			return	$aguide;
		}else{
			return	array();
		}
	}
	# 通过ID获取该城市下的单个向导
	public function city_get_one_aguide($where,$field=true){
		if(empty($where)){
			return	array();
		}
		$aguide	=	$this->field($field)->where($where)->find();
		if($aguide){
			return	$aguide;
		}else{
			return	array();
		}
	}
	# 添加向导
	public function add_aguide($data){
		if(empty($data)){
			return	array();
		}
		$aguide	=	$this->data($data)->add();
		if($aguide){
			return	$aguide;
		}else{
			return	array();
		}
	}
	# 修改向导
	public function edit_aguide($where,$data){
		if(empty($where)){
			return	array();
		}
		if(empty($data)){
			return	array();
		}
		$aguide	=	$this->where($where)->data($data)->save();
		if($aguide){
			return	$aguide;
		}else{
			return	array();
		}
	}
	# 获取单个向导
	public function get_one_aguide($where,$field=true){
		if(empty($where)){
			return	array();
		}
		$aguide	=	$this->field($field)->where($where)->order('create_time DESC')->find();
		if($aguide){
			$province_id	=	D('Area')->scenic_get_one_city($aguide['province_id']);
			$aguide['province_ids']	=	$province_id['area_name'];
			$city_id	=	D('Area')->scenic_get_one_city($aguide['city_id']);
			$aguide['city_ids']	=	$city_id['area_name'];
			return	$aguide;
		}else{
			return	array();
		}
	}
	# 获取订单列表
	public function get_aguide_order_list($where,$page=1,$field=true){
		if(empty($where)){
			return	array();
		}
		$aguide_order	=	M('Scenic_aguide_order')->field($field)->page($page,10)->where($where)->order('pay_time DESC')->select();
		if($aguide_order){
			return	$aguide_order;
		}else{
			return	array();
		}
	}
	# 获取订单列表
	public function get_aguide_order_list_me($where){
		if(empty($where)){
			return	array();
		}
		$aguide_order	=	M('Scenic_aguide_order')->field(true)->order('create_time DESC')->where($where)->select();
		if($aguide_order){
			return	$aguide_order;
		}else{
			return	array();
		}
	}
	# 获取订单列表
	public function get_aguide_order_list_me_pc($where,$limit){
		if(empty($where)){
			return	array();
		}
		$aguide_order	=	M('Scenic_aguide_order')->field(true)->order('create_time DESC')->where($where)->limit($limit)->select();
		if($aguide_order){
			return	$aguide_order;
		}else{
			return	array();
		}
	}
	# 修改向导
	public function save_aguide($where=array(),$data=array()){
		if(empty($where) && empty($data)){
			return	0;
		}
		$aguide_order	=	$this->where($where)->data($data)->save();
		if($aguide_order){
			return	$aguide_order;
		}else{
			return	0;
		}
	}
	# 向导下单
	public function add_aguide_order($data=array()){
		if(empty($data)){
			return	array();
		}
		$aguide_order	=	M('Scenic_aguide_order')->data($data)->add();
		if($aguide_order){
			return	$aguide_order;
		}else{
			return	array();
		}
	}
	# 修改向导订单
	public function save_guide_order($where,$data){
		if(empty($data) && empty($where)){
			return 0;
		}
		$save	=	M('Scenic_aguide_order')->where($where)->data($data)->save();
		return $save;
	}
	# 刷新大于今天的向导订单
	public function order_refresh($user_id,$where_id='guide_id',$start_tiem,$compare='<'){
		if(empty($user_id)){
			return 0;
		}
		$mate	=	"SELECT
						`guide_id`
					FROM
						`pigcms_scenic_aguide_order`
					WHERE
						(`{$where_id}` = {$user_id})
					AND (`rela_status` = 1)
					AND (
						DATE_FORMAT(`end_time`, '%Y-%m-%d') {$compare}'{$start_tiem}')";
		$select		=	$this->execute($mate);
		$mate	=	"UPDATE
						`pigcms_scenic_aguide_order`
					SET
						`rela_status`	=	2
					WHERE
						(`{$where_id}` = {$user_id})
					AND	(`rela_status` = 1)
					AND (DATE_FORMAT( `end_time`, '%Y-%m-%d'){$compare}'{$start_tiem}')";
		$return		=	$this->execute($mate);
		foreach($select as $v){
			M('pigcms_scenic_aguide')->where(array('guide_id'=>$v['guide_id']))->setInc('guide_service_number');
		}
		$mate	=	"UPDATE
						`pigcms_scenic_aguide_order`
					SET
						`rela_status`	=	5
					WHERE
						(`{$where_id}` = {$user_id})
					AND	(`rela_status` = 0)
					AND (DATE_FORMAT( `end_time`, '%Y-%m-%d'){$compare}'{$start_tiem}')";
		$return		=	$this->execute($mate);
		if($return){
			return $return;
		}else{
			return 0;
		}
	}

	#验证导游的服务，把钱划给导游
	public function verify($uid){
		$where['guide_id']=$uid;
		$where['rela_status']=2;
		$where['verify_status']=1;
		$where['end_time']=array('lt',date('Y-m-d'));
		$verify_list = M('Scenic_aguide_order')->where($where)->select();
		foreach($verify_list as $v ){
			if(D('User')->add_money($uid,$v['total_price'],'用户预定导游支付费用')){
				M('Scenic_aguide_order')->where(array('order_id'=>$v['order_id']))->setField('verify_status',2);
			}
		}
	}

	public function get_pay_order($user_id, $order_id, $is_web=false){
		$now_order = $this->get_order_by_id($user_id, $order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}
		if($now_order['pay_status']==2){
			return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>str_replace('/source/','/',U('Scenic_user/aguide_order_list')));
		}

		$condition['order_id'] = $now_order['order_id'];
		$order_info = array(
				'order_id'			=>	$now_order['order_id'],
				'guide_id'			=>	$now_order['guide_id'],
				'order_type'		=>	'guide',
				'order_price'		=>	floatval($now_order['total_price']),
				'add_time'        =>$now_order['create_time'],
		);
		return array('error' => 0,'order_info' => $order_info,'now_order'=>$now_order);
	}


	#获取订单
	public function get_order_by_id($user_id, $order_id){
		$condition = array();
		$condition['order_id'] = $order_id;
		$condition['user_id'] = $user_id;
		return M('Scenic_aguide_order')->field(true)->where($condition)->find();
	}

	public function get_one_order($where){
		if(empty($where)){
			return 0;
		}
		$select	=	M('Scenic_aguide_order')->field('*,total_price as order_total')->where($where)->find();
		return $select;
	}
	#手机端支付前订单处理
	public function wap_befor_pay($order_info,$now_user){
		$pay_money = $order_info['order_price'];
		//判断帐户余额
		$data_scenic_order['balance_pay']=0;
		if (!empty($now_user['now_money'])&&$order_info['use_balance']) {
			if ($now_user['now_money'] >= $pay_money) {
				$data_scenic_order['balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info, $data_scenic_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			} else {
				$data_scenic_order['balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
		}

		//在线支付
		$order_result = $this->wap_pay_save_order($order_info, $data_scenic_order);

		if ($order_result['error_code']) {
			return $order_result;
		}
		return array('error_code' => false, 'pay_money' => $pay_money);
	}

	//手机端支付前保存各种支付信息
	public function wap_pay_save_order($order_info, $data_store_order){
		$data_scenic_order['balance_pay']	 	= !empty($data_store_order['balance_pay']) ? $data_store_order['balance_pay'] : 0;
		$data_scenic_order['last_time']	 	= $_SERVER['REQUEST_TIME'];
		$condition_scenic_order['order_id'] = $order_info['order_id'];
		$result = M('Scenic_aguide_order')->where($condition_scenic_order)->data($data_scenic_order)->save();
		if ($result) {
			return array('error_code' => false, 'msg' => '保存订单成功！');
		} else {
			return array('error_code' => true, 'msg' => '保存订单失败！请重试或联系管理员。');
		}
	}

	//如果无需调用在线支付，使用此方法即可。
	public function wap_after_pay_before($order_info){
		$order_param = array(
				'order_id' => $order_info['order_id'],
				'pay_type' => '',
				'third_id' => '',
				'is_mobile' => 1,
		);
		$result_after_pay = $this->after_pay($order_param);
		if ($result_after_pay['error']) {
			return array('error_code' => true,'msg'=>$result_after_pay['msg']);
		}
		return array('error_code'=>false,'msg'=>'支付成功！','url'=>str_replace('/source/','/',U('Scenic_user/aguide_order_list')));
	}

	//支付之后
	public function after_pay($order_param){
		if($order_param['pay_type']!=''){
			$condition_order['orderid'] = $order_param['order_id'];
		}else{
			$condition_order['order_id'] = $order_param['order_id'];
		}
		$now_order = M('Scenic_aguide_order')->field(true)->where($condition_order)->find();
		if (empty($now_order)) {
			return array('error' => 1, 'msg' => '当前订单不存在！');
		} elseif($now_order['pay_status'] == 2) {
			return array('error' => 1, 'msg' => '该订单已付款！', 'url' => U('Scenic_user/aguide_order_list'));
		} else {
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['user_id']);
			$now_guide = M('Scenic_guide')->where(array('guide_id'=>$now_order['guide_id']))->find();
			if (empty($now_user)) {
				return array('error' => 1, 'msg' => '没有查找到此订单归属的用户，请联系管理员！');
			}

			//判断帐户余额
			$balance_pay = floatval($now_order['balance_pay']);
			if($balance_pay){
				if($now_user['now_money'] < $balance_pay){
					return $this->wap_after_pay_error($now_order, $order_param, '您的帐户余额不够此次支付！');
				}
			}

			//如果用户使用了余额支付，则扣除相应的金额。
			if(!empty($balance_pay)){

				$use_result = D('User')->user_money($now_order['user_id'],$balance_pay,'预定了导游服务(导游'.$now_guide['guide_name'].')扣除余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			$data_store_order['pay_time'] = isset($order_param['pay_time']) && $order_param['pay_time'] ? strtotime($order_param['pay_time']) : $_SERVER['REQUEST_TIME'];
			$data_store_order['payment_money'] = floatval($order_param['pay_money']);
			$data_store_order['pay_type'] = $order_param['pay_type'];
			$data_store_order['third_id'] = $order_param['third_id'];
			$data_store_order['pay_status'] = 2;
			$data_store_order['rela_status'] = 1;
			$data_store_order['last_time'] = $_SERVER['REQUEST_TIME'];
			//$data_store_order['order_status'] = 1;
			if(M('Scenic_aguide_order')->where($condition_order)->data($data_store_order)->save()){
				//支付成功发送模板消息给用户
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				$href = C('config.site_url').'/wap.php?c=Scenic_user&a=aguide_order_list';
				$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => '导游预定提醒', 'keyword1' => '导游预定订单', 'keyword2' => $now_order['order_id'], 'keyword3' => $now_order['total_price'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '付款成功，感谢您的使用'));
				$now_guide = M('Scenic_aguide')->field('u.openid,a.user_id,a.guide_phone')->join('as a left join '.C('DB_PREFIX').'user u ON u.uid = a.user_id')->where(array('guide_id'=>$now_order['guide_id']))->find();
				$sms_data = array('mer_id' => 0, 'store_id' => 0, 'type' => 'guide');
				$sms_data['uid'] = $now_user['uid'];
				$sms_data['mobile'] = $now_user['phone'];
				$sms_data['sendto'] = 'user';
				$sms_data['content'] = '您成功预约了导游' .$now_guide['guide_name'] . '，联系方式：'.$now_guide['guide_phone'];
				Sms::sendSms($sms_data);

				//发送消息给导游
				$href=C('config.site_url').'/wap.php?c=Scenic_user&a=guide_service';
				$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_guide['openid'], 'first' => '导游预定提醒', 'keyword1' => '导游预定订单', 'keyword2' => $now_order['order_id'], 'keyword3' => $now_order['total_price'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '顾客'.$now_user['nickname'].'预定了您的服务！'));

				//发短信给导游
				$sms_data['uid'] = $now_guide['user_id'];
				$sms_data['mobile'] = $now_guide['guide_phone'];
				$sms_data['sendto'] = 'guide';
				$sms_data['content'] = '顾客' . $now_user['nickname'] . '在' . date("Y-m-d H:i:s") . '时，预定您的导游服务,联系方式：'.$now_user['phone'];
				Sms::sendSms($sms_data);

				if($order_param['is_mobile']){
					return array('error'=>0,'url'=>str_replace('/source/','/',U('Scenic_user/aguide_order_list')));
				}else{
					return array('error'=>0,'url'=>U('Scenic_user/aguide_order_list'));
				}
			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}

	public function wap_after_pay_error($now_order,$order_param,$error_tips){
		//记录充值的金额，因为 Pay/return_url 处没有返回order的具体信息，故在此调用。
		$user_result = D('User')->add_money($now_order['user_id'],$order_param['pay_money'],'支付订单：'.$now_order['order_id'].'发生错误！原因是：'.$error_tips);
		if($order_param['pay_type']=='weixin'&&ACTION_NAME=='return_url'){
			exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
		}
		if($user_result['error_code']){
			return array('error'=>1,'msg'=>$user_result['msg']);
		}else{
			if($order_param['is_mobile']){
				$return_url = str_replace('/source/','/',U('Scenic_user/aguide_order_list'));
			}
			return array('error'=>1,'msg'=>$error_tips.'已将您充值的金额添加到您的余额内。','url'=>$return_url);
		}
	}

}
?>