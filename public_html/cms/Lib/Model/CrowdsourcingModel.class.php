<?php
class CrowdsourcingModel extends Model{
	//	头像默认地址
	protected	$urlTot	=	'/tpl/Wap/default/static/bbs/img/tou.png';
	//	获取众包的列表
	public function get_list($where,$page=1,$pageCount=10){
		if(!$where){
			return	false;
		}
		$field	=	array(
						'package_id',
						'package_start',
						'package_end',
						'package_title',
						'package_money',
						'package_deposit',
						'user_id',
						'user_name',
						'is_authentication',
						'add_tims',
						'car_type',
						'package_status',
						);
		$aList	=	$this->field($field)->order('add_tims desc')->where($where)->page($page,$pageCount)->select();
		if(empty($aList)){
			return	false;
		}
		foreach($aList as $k=>$v){
			$aUser	=	$this->user(array('uid'=>$v['user_id']));
			$aList[$k]['avatar']	=	$aUser['avatar'];
			$carFormat	=	$this->carFormat($v['car_type']);
			$aList[$k]['car_type_name']	=	$carFormat['category_name'];
			$aList[$k]['car_type_img']	=	$carFormat['category_img'];
			$orderStatus	=	M('Crowdsourcing_order')->field(array('status'))->where(array('package_id'=>$v['package_id'],'status'=>array('neq',5)))->find();
			$aList[$k]['order_status']	=	isset($orderStatus['status'])?$orderStatus['status']:0;
		}
		return	$aList;
	}
	//	众包详情
	public	function get_details($where){
		if(empty($where)){
			return	false;
		}
		$aDetails	=	$this->field(true)->where($where)->find();
		if(empty($aDetails)){
			return	false;
		}else{
			$avatar	=	$this->user(array('uid'=>$aDetails['user_id']));
			$aDetails['avatar']		=	$avatar['avatar'];
			$aDetails['add_tims_s']	=	date('Y-m-d H:i',$aDetails['add_tims']);
			$carFormat	=	$this->carFormat($aDetails['car_type']);
			$aDetails['car_type_s']	=	$carFormat['category_name'];
			$aDetails['car_type_img']	=	$carFormat['category_img'];
			return	$aDetails;
		}
	}
	//	发布众包
	public	function add_crow($data){
		if(!$data){
			return	false;
		}
		$add	=	$this->add($data);
		if(empty($add)){
			return	false;
		}
		$user['uid']	=	$data['user_id'];
		$aUser	=	$this->user($user);
		if(empty($aUser['truename'])){
			M('User')->where(array($user))->data(array('truename'=>$data['user_name']))->save();
		}
		return	$add;
	}
	//	修改众包
	public	function eidt_crow($where,$data){
		if(!$data || !$where){
			return	false;
		}
		$save	=	$this->where($where)->data($data)->save();
		if($save === false){
			return	false;
		}
		return	true;
	}
	//	查询用户信息
	public	function user($where){
		if(!$where){
			return	false;
		}
		$aUser	=	M('User')->field(array('avatar','truename','phone','now_money','uid','real_name'))->where($where)->find();
		if(empty($aUser)){
			return	false;
		}
		if(empty($aUser['avatar'])){
			$aUser['avatar']	=	$this->urlTot;
		}
		return	$aUser;
	}
	//	我的抢单
	public	function get_order($where,$page,$page_count,$order=array()){
		if(!$where){
			return	false;
		}
		$aOrder	=	M('Crowdsourcing_order')->order(array('order_time'=>'desc'))->field(true)->where($where)->page($page,$page_count)->select();
		if(empty($aOrder)){
			return	false;
		}
		//	获取订单后，循环组装数据
		foreach($aOrder as $k=>$v){
			$aOrder[$k]['status_s']	=	$this->order_status($v['status']);
			$aOrder[$k]['order_time_s']	=	date('Y-m-d H:i',$v['order_time']);
			$aOrder[$k]['collect_time_s']	=	date('Y-m-d H:i',$v['collect_time']);
			$aOrder[$k]['give_time_s']	=	date('Y-m-d H:i',$v['give_time']);
			$aOrder[$k]['complete_time_s']	=	date('Y-m-d H:i',$v['complete_time']);
			$aOrder[$k]['cancel_time_s']	=	date('Y-m-d H:i',$v['cancel_time']);
			if(!$order){
				$aOrder[$k]['details']	=	$this->get_details(array('package_id'=>$v['package_id']));
			}
		}
		return	$aOrder;
	}
	//	进行抢单
	public	function add_order($data){
		if(!$data){
			return	false;
		}
		$aSave	=	M('Crowdsourcing_order')->data($data)->add();
		if(!$aSave){
			return	false;
		}
		return	true;
	}
	//	抢单详情
	public function get_order_details($where,$order=array()){
		if(!$where){
			return	false;
		}
		$aOrder	=	M('Crowdsourcing_order')->field(true)->where($where)->find();

		if(empty($aOrder)){
			return	false;
		}
		$aOrder['status_s']			=	$this->order_status($aOrder['status']);
		$aOrder['order_time_s']		=	date('Y-m-d H:i',$aOrder['order_time']);
		$aOrder['collect_time_s']	=	date('Y-m-d H:i',$aOrder['collect_time']);
		$aOrder['give_time_s']		=	date('Y-m-d H:i',$aOrder['give_time']);
		$aOrder['complete_time_s']	=	date('Y-m-d H:i',$aOrder['complete_time']);
		$aOrder['cancel_time_s']	=	date('Y-m-d H:i',$aOrder['cancel_time']);
		if(!$order){
			$aOrder['details']			=	$this->get_details(array('package_id'=>$aOrder['package_id']));
		}
		return	$aOrder;
	}
	//	更新抢单状态
	public	function save_order_details($where,$data){
		if(!$where || !$data){
			return	false;
		}
		$aOrder	=	M('Crowdsourcing_order')->where($where)->data($data)->save();
		if(empty($aOrder)){
			return	false;
		}

		return	true;
	}
	//	车型匹配
	public function carFormat($car_type){
		$category	=	M('Crowdsourcing_category')->where(array('category_status'=>1))->order('category_sort DESC')->select();
		foreach($category as $k=>$v){
			if($car_type == $v['category_id']){
				$arr	=	array(
					'category_name'	=>	$v['category_name'],
					'category_img'	=>	$v['category_img'],
				);
				return	$arr;
			}
		}
	}
	//	订单状态
	public function order_status($status=1){
		if($status == 1){
			$status	=	'抢单成功';
		}else if($status == 2){
			$status	=	'收货完成';
		}else if($status == 3){
			$status	=	'送货完成';
		}else if($status == 4){
			$status	=	'付款完成';
		}else{
			$status	=	'取消订单';
		}
		return	$status;
	}
	//	给用户发送微信和短信
	public	function weixin_user($data,$type=1){
		if($type==1){
			//	司机抢单成功，给发布者发送微信和短信
			$userInfo	=	M('user')->field('openid')->where(array('phone'=>$data['visitor_phone']))->find();
			//发送微信模板消息start
			$work_info ='\n姓名为：'.$data['owner_name'].'，手机号为：'.$data['owner_phone'].'的司机，已经接了您从'.$data['package_start'].'出发的包裹！';
			if($userInfo['openid']){
				$href = C('config.site_url').U('my_launch');
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$data['visitor_name'].' 用户\n', 'work' => $work_info, 'remark' => '\n请点击查看详细信息！'));
			}
			//发送手机模板消息end
			$sms_data['uid'] = $data['visitor_uid'];
			$sms_data['mobile'] = $data['visitor_phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = $data['visitor_name'].'，您好！有司机已经接了您从'.$data['package_start'].'出发的包裹！司机姓名为：'.$data['owner_name'].'，手机号码为：'.$data['owner_phone'].'。';
			Sms::sendSms(($sms_data));
		}else if($type==2){
			//	发布者取消订单，给司机发送微信和短信
			$userInfo	=	M('user')->field('openid')->where(array('phone'=>$data['owner_phone']))->find();
			//发送微信模板消息start
			$work_info ='\n姓名为：'.$data['visitor_name'].'，手机号为：'.$data['visitor_phone'].'的用户，已经取消了您从'.$data['package_start'].'出发的包裹！';
			if($userInfo['openid']){
				$href = C('config.site_url').U('my_response');
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$data['owner_name'].' 师傅\n', 'work' => $work_info, 'remark' => '\n请点击查看详细信息！'));
			}
			//发送手机模板消息end
			$sms_data['uid'] = $data['owner_uid'];
			$sms_data['mobile'] = $data['owner_phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = $data['owner_name'].'，您好！有用户取消了您从'.$data['package_start'].'出发的包裹！用户姓名为：'.$data['visitor_name'].'。';
			Sms::sendSms(($sms_data));
		}else if($type == 3){
			//	司机取消订单，给发布者发送微信和短信
			$userInfo	=	M('user')->field('openid')->where(array('phone'=>$data['visitor_phone']))->find();
			//发送微信模板消息start
			$work_info ='\n姓名为：'.$data['owner_name'].'，手机号为：'.$data['owner_phone'].'的司机，已经取消了您从'.$data['package_start'].'出发的包裹！';
			if($userInfo['openid']){
				$href = C('config.site_url').U('my_launch');
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$data['visitor_name'].' 用户\n', 'work' => $work_info, 'remark' => '\n请点击查看详细信息！'));
			}
			//发送手机模板消息end
			$sms_data['uid'] = $data['visitor_uid'];
			$sms_data['mobile'] = $data['visitor_phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = $data['visitor_name'].'，您好！有司机取消了您从'.$data['package_start'].'出发的包裹！司机姓名为：'.$data['owner_name'].'。';
			Sms::sendSms(($sms_data));
		}else if($type == 5){
			//	发布者确定订单完成，给司机发送微信和短信
			$userInfo	=	M('user')->field('openid')->where(array('phone'=>$data['owner_phone']))->find();
			//发送微信模板消息start
			$work_info ='\n姓名为：'.$data['visitor_name'].'，手机号为：'.$data['visitor_phone'].'的用户，已经付款给您了！从'.$data['package_start'].'出发的包裹。';
			if($userInfo['openid']){
				$href = C('config.site_url').U('my_response');
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$data['owner_name'].' 师傅\n', 'work' => $work_info, 'remark' => '\n请点击查看详细信息！'));
			}
			//发送手机模板消息end
			$sms_data['uid'] = $data['owner_uid'];
			$sms_data['mobile'] = $data['owner_phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = $data['owner_name'].'，您好！您从'.$data['package_start'].'出发的包裹！用户姓名为：'.$data['visitor_name'].'，已经付款给您了！';
			Sms::sendSms(($sms_data));
		}
	}
}