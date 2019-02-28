<?php
class UserModel extends Model
{
	/*得到所有用户*/
	public function get_user($uid,$field='uid')
	{
		$condition_user[$field] = $uid;
		$now_user = $this->field(true)->where($condition_user)->find();
		if(!empty($now_user)){
			if(C('config.open_frozen_money')==1) {
				if ($now_user['frozen_time'] < $_SERVER['REQUEST_TIME'] && $_SERVER['REQUEST_TIME'] < $now_user['free_time']) {
					$now_user['now_money'] -= $now_user['frozen_money'];
					$now_user['now_money'] = $now_user['now_money']<0?0:$now_user['now_money'];
				}
//				if ($now_user['score_extra_count'] > 0) {
//					$now_user['score_count'] += $now_user['score_extra_count'];
//				}
			}
//			if(C('config.zbw_key') && $now_user['zbw_cardid']){
//				$zbw_card = D('ZbwErp')->GetVipInfo($now_user['zbw_cardid']);
//				if(floatval($zbw_card['money'])!=floatval($now_user['now_money'])){
//					D('ZbwErp')->sync_data($uid);

//					$now_user['now_money'] = floatval($zbw_card['money']);
//				}
//			}
			//dump($now_user['frozen_money']);
			$now_user['now_money'] = getFormatNumber($now_user['now_money']);
		}
		return $now_user;
	}
	/*帐号密码检入*/
	public function checkin($phone,$pwd,$type=false,$smscode=false,$phone_country_type=''){

		if (empty($phone)){
			if($type){
				return array('error_code' => true, 'msg' => '20042001');
			}else{
				return array('error_code' => true, 'msg' => '手机号不能为空');
			}
		}
		if (empty($pwd) && !$smscode ){
			if($type){
				return array('error_code' => true, 'msg' => '20042002');
			}else{
				return array('error_code' => true, 'msg' => '密码不能为空');
			}
		}
		if(C('config.international_phone')==1 ){
			$now_user = $this->field(true)->where(array('phone' => $phone,'phone_country_type'=>$phone_country_type))->find();
		}else{
			$now_user = $this->field(true)->where(array('phone' => $phone))->find();
		}
		if($smscode && $now_user){
			return array('error_code' => false, 'msg' => 'OK' ,'user'=>$now_user);
		}
		if ($now_user){
			if($now_user['pwd'] != md5($pwd)){
				if($type){
					return array('error_code' => true, 'msg' => '20120007');
				}else{
					return array('error_code' => true, 'msg' => '密码不正确!');
				}
			}
			if(empty($now_user['status'])){
				if($type){
					return array('error_code' => true, 'msg' => '20120008');
				}else{
					return array('error_code' => true, 'msg' => '该帐号被禁止登录!');
				}
			}
			if($now_user['status'] == 2){
				if($type){
					return array('error_code' => true, 'msg' => '20120008');
				}else{
					return array('error_code' => true, 'msg' => '该帐号未审核，无法登录');
				}
			}

			$condition_save_user['uid'] = $now_user['uid'];
			$data_save_user['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_save_user['last_ip'] = get_client_ip(1);
			/****判断此用户是否在user_import表中***/
			$user_importDb=D('User_import');
			$user_import=$user_importDb->where(array('telphone'=>$phone,'isuse'=>'0'))->find();
			if(!empty($user_import)){
			   !empty($user_import['ppname']) && $data_save_user['truename']=$user_import['ppname'];
			   $data_save_user['qq']=$user_import['qq'];
			   $data_save_user['email']=$user_import['email'];
			   $data_save_user['level']=max($now_user['level'],$user_import['level']);
			   $data_save_user['score_count']=max($now_user['score_count'],$user_import['integral']);
			   $data_save_user['now_money']=max($now_user['now_money'],$user_import['money']);
			   $data_save_user['importid']=$user_import['id'];
			   	if(C('config.reg_verify_sms')){
					$data_user['status'] = 1; //开启注册验证短信就不需要审核
				}else{
					$data_user['status'] = 2; /*             * *未审核*** */

				}
			   $mer_id=$user_import['mer_id'];
			   $data_save_user['openid']=isset($_SESSION['weixin']) && isset($_SESSION['weixin']['user']) ? $_SESSION['weixin']['user']['openid'] :'';
			   if(($mer_id>0) && !empty($data_save_user['openid'])){
			      $merchant_user_relationDb=M('Merchant_user_relation');
				  $mwhere=array('openid'=>$data_save_user['openid'],'mer_id'=>$mer_id);
				  $mtmp=$merchant_user_relationDb->where($mwhere)->find();
				  if(empty($mtmp)){
					 $mwhere['dateline']=time();
					 $mwhere['from_merchant']=3;
				     $merchant_user_relationDb->add($mwhere);
				  }
			   }
			}

			if($this->where($condition_save_user)->data($data_save_user)->save()){
			    if(!empty($user_import)){
				   $user_importDb->where(array('id'=>$user_import['id']))->save(array('isuse'=>1));
				}


				$database_house_village_user_bind = D('House_village_user_bind');
				$bind_where['uid'] = $now_user['uid'];
				$database_house_village_user_bind->where($bind_where)->data(array('phone'=>$_POST['phone']))->save();
			}
			return array('error_code' => false, 'msg' => 'OK' ,'user'=>$now_user);
		} else {
			if($type){
				return array('error_code' => true, 'msg' => '20120009');
			}else{
				return array('error_code' => true, 'msg' => '手机号不存在!');
			}
		}
	}
	/*手机号、union_id、open_id 直接登录入口*/
	public function autologin($field,$value){
		$condition_user[$field] = $value;
		$now_user = $this->field(true)->where($condition_user)->find();
		if($now_user){
			if(empty($now_user['status'])){
				return array('error_code' => true, 'msg' => '该帐号被禁止登录!');
			}
			if($now_user['status'] == 2){
				return array('error_code' => true, 'msg' => '该帐号未审核，无法登录!');
			}
			$condition_save_user['uid'] = $now_user['uid'];
			$data_save_user['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_save_user['last_ip'] = get_client_ip(1);
			$this->where($condition_save_user)->data($data_save_user)->save();
			
			$now_user['can_withdraw_money'] = floatval($now_user['now_money'])- floatval($now_user['score_recharge_money']);
			if($now_user['can_withdraw_money'] < 0){
				$now_user['can_withdraw_money'] = 0;
			}
			
			return array('error_code' => false, 'msg' => 'OK' ,'user'=>$now_user);
		}else{
			return array('error_code'=>1001,'msg'=>'没有此用户！');
		}
	}
	/*
	 *	提供用户信息注册用户，密码需要自行md5处理
	 *
	 *	**** 请自行处理逻辑，此处直接插入用户表 ****
	 */
	public function autoreg($data_user){
		if($data_user['avatar']){
			$data_user['avatar'] = str_replace('http://wx.qlogo.cn','https://thirdwx.qlogo.cn',$data_user['avatar']);
		}
		if($res = $this->where(array('openid'=>$data_user['openid']))->find()){
			if($res['status']==0){
				return array('error_code' => true, 'msg' => '您已被禁止登录');
			}
			return array('error_code' => true, 'msg' => '您已经注册过了');
		}
		$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);
		$data_user['status'] = 1;
		$data_user['score_clean_time'] = $_SERVER['REQUEST_TIME'];

		if($data_user['openid']){
			$data_user['last_weixin_time'] = $_SERVER['REQUEST_TIME'];
		}
		if($uid = $this->data($data_user)->add()){
			$this->register_give_money($uid);
			$spread_user_give_type = C('config.spread_user_give_type');
			if($spread_user_give_type!=3&&!empty($_SESSION['openid'])){
 				$now_user_spread = M('User_spread')->field('`spread_openid`, `openid`')->where(array('openid'=>$_SESSION['openid']))->find();
  				if($now_user_spread) {
  					$spread_user = $this->get_user($now_user_spread['spread_openid'],'openid');
  					$now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();
  					if ($spread_user_give_type == 0 ||$spread_user_give_type == 2) {
						$spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level: C('config.spread_give_money');
						if(C('config.open_score_fenrun')){
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额','','',$uid);
						}else{
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额');
						}
  						D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠平台余额'.$spread_give_money.'元');
  					}
  
 					if ($spread_user_give_type == 1 || $spread_user_give_type == 2) {
						$spread_give_score = $now_level['spread_user_give_score']>0?$now_level: C('config.spread_give_score');
						$this->add_score($spread_user['uid'], $spread_give_score, '推荐新用户注册平台赠送' . C('config.score_name'));
						if($spread_give_score>0){
							D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠'.C('config.score_name').$spread_give_score.'个');
						}
  					}
  
				}
			}
			return array('error_code' =>false,'msg' =>'OK','uid'=>$uid);
		}else{
			return array('error_code' => true, 'msg' => '注册失败！请重试。');
		}
	}

	public function register_give_money($uid,$is_app=false){
		$register_give_money_condition = C('config.register_give_money_condition');
		if ($register_give_money_condition == 2 || $register_give_money_condition == 4 || ($is_app && $register_give_money_condition==3)) {
			$register_give_money_type = C('config.register_give_money_type');
			if($register_give_money_type==1 ||$register_give_money_type==2 ){
				$this->add_money($uid, C('config.register_give_money'), '新用户注册平台赠送余额');
			}
			if($register_give_money_type==0 ||$register_give_money_type==2 ){
				$this->add_score($uid,C('config.register_give_score'), '新用户注册平台赠送'.C('config.score_name'));
			}
		}
	}

	public function autoreg_by_scan_merchant_qrcode($data_user){
		$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
		//$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);
		$data_user['status'] = 1;
		$data_user['score_clean_time'] = $_SERVER['REQUEST_TIME'];

		if($data_user['openid']){
			$data_user['last_weixin_time'] = $_SERVER['REQUEST_TIME'];
		}
		if($uid = $this->data($data_user)->add()){
			// $register_give_money_condition = C('config.register_give_money_condition');
			// if ($register_give_money_condition == 2 || $register_give_money_condition == 4) {
			// 	//$this->add_money($uid, C('config.register_give_money'), '新用户注册平台赠送余额');
			// 	//if(C('config.register_give_score')>0){
			// 	//	$this->add_score($uid,1,C('config.register_give_score'), '新用户注册平台赠送'.C('config.score_name'));
			// 	//}
			// 	$register_give_money_type = C('config.register_give_money_type');
			// 	if($register_give_money_type==1 ||$register_give_money_type==2 ){
			// 		$this->add_money($uid, C('config.register_give_money'), '新用户注册平台赠送余额');
			// 	}
			// 	if($register_give_money_type==0 ||$register_give_money_type==2 ){
			// 		$this->add_score($uid,C('config.register_give_score'), '新用户注册平台赠送'.C('config.score_name'));
			// 	}
			// }
			$this->register_give_money($uid);

			$spread_user_give_type = C('config.spread_user_give_type');
			if($spread_user_give_type!=3&&!empty($_SESSION['openid'])){
 				$now_user_spread = M('User_spread')->field('`spread_openid`, `openid`')->where(array('openid'=>$_SESSION['openid']))->find();
  				if($now_user_spread) {
  					$spread_user = $this->get_user($now_user_spread['spread_openid'],'openid');
  					$now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();
  					if ($spread_user_give_type == 0 ||$spread_user_give_type == 2) {
						$spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level: C('config.spread_give_money');
						if(C('config.open_score_fenrun')){
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额','','',$uid);
						}else{
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额');
						}
  						D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠平台余额'.$spread_give_money.'元');
  					}
  
 					if ($spread_user_give_type == 1 || $spread_user_give_type == 2) {
						$spread_give_score = $now_level['spread_user_give_score']>0?$now_level: C('config.spread_give_score');
						$this->add_score($spread_user['uid'], $spread_give_score, '推荐新用户注册平台赠送' . C('config.score_name'));
						if($spread_give_score>0){
							D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠'.C('config.score_name').$spread_give_score.'个');
						}
  					}
  
				}
			}
			return array('error_code' =>false,'msg' =>array('uid'=>$uid));
		}else{
			return array('error_code' => true, 'msg' => '注册失败！请重试。');
		}
	}

	/*帐号密码注册*/
	public function checkreg($phone,$pwd,$phone_country_type=''){
		if (empty($phone)) {
			return array('error_code' => true, 'msg' => '手机号不能为空');
		}
		if (empty($pwd)) {
			return array('error_code' => true, 'msg' => '密码不能为空');
		}

		if(!C('config.international_phone') && !preg_match('/^[0-9]{11}$/',$phone)){
			return array('error_code' => true, 'msg' => '请输入有效的手机号');
		}

		$condition_user['phone'] = $phone;
		if(C('config.international_phone')){
			$condition_user['phone_country_type'] = $phone_country_type;
		}
		if($this->field('`uid`')->where($condition_user)->find()){
			return array('error_code' => true, 'msg' => '手机号已存在');
		}

		$data_user['phone'] = $phone;
		$data_user['pwd'] = md5($pwd);
		$data_user['status'] = 1;
		$data_user['nickname'] = substr($phone,0,3).'****'.substr($phone,7);

		$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);
		$data_user['score_clean_time'] = $_SERVER['REQUEST_TIME'];
		$phone_country_type && $data_user['phone_country_type'] =$phone_country_type;
		if($uid = $this->data($data_user)->add()){
			// $register_give_money_condition = C('config.register_give_money_condition');
			// if ($register_give_money_condition == 1 || $register_give_money_condition == 4) {
			// 	//$this->add_money($uid, C('config.register_give_money'), '新用户注册平台赠送余额');
			// 	//if(C('config.register_give_score')>0){
			// 	//	$this->add_score($uid,1,C('config.register_give_score'), '新用户注册平台赠送'.C('config.score_name'));
			// 	//}
			// 	$register_give_money_type = C('config.register_give_money_type');
			// 	if($register_give_money_type==1 ||$register_give_money_type==2 ){
			// 		$this->add_money($uid, C('config.register_give_money'), '新用户注册平台赠送余额');
			// 	}
			// 	if($register_give_money_type==0 ||$register_give_money_type==2 ){
			// 		$this->add_score($uid,C('config.register_give_score'), '新用户注册平台赠送'.C('config.score_name'));
			// 	}
			// }

			$this->register_give_money($uid);

			$spread_user_give_type = C('config.spread_user_give_type');

			if($spread_user_give_type!=3&&!empty($_SESSION['openid'])){
 				$now_user_spread = M('User_spread')->field('`spread_openid`, `openid`')->where(array('openid'=>$_SESSION['openid']))->find();
  				if($now_user_spread) {
  					$spread_user = $this->get_user($now_user_spread['spread_openid'],'openid');
  					$now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();
  					if ($spread_user_give_type == 0 ||$spread_user_give_type == 2) {
						$spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level: C('config.spread_give_money');
						if(C('config.open_score_fenrun')){
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额','','',$uid);
						}else{
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额');
						}
  						D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠平台余额'.$spread_give_money.'元');
  					}
  
 					if ($spread_user_give_type == 1 || $spread_user_give_type == 2) {
						$spread_give_score = $now_level['spread_user_give_score']>0?$now_level: C('config.spread_give_score');
						$this->add_score($spread_user['uid'], $spread_give_score, '推荐新用户注册平台赠送' . C('config.score_name'));
						if($spread_give_score>0){
							D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠'.C('config.score_name').$spread_give_score.'个');
						}
  					}
  
				}
			}
			$return = $this->checkin($phone,$pwd,false,false,$phone_country_type);

			if(empty($result['error_code'])){
    			return $return;
    		}else{
				return array('error_code' =>false,'msg' =>'OK');
			}
		}else{
			return array('error_code' => true, 'msg' => '注册失败！请重试。');
		}
	}

	public function check_phone($phone){
		$condition_user['phone'] = $phone;
		if($this->field('`uid`')->where($condition_user)->find()){
			return array('error_code' => true, 'msg' => '手机号已存在');
		}
	}
	/*修改用户信息*/
	public function save_user($uid,$field,$value){
		if(!$uid){
			return array('error'=>1,'msg'=>'请求参数必须携带 uid');
		}
		$condition_user['uid'] = $uid;
		$data_user[$field] = $value;
		if($this->where($condition_user)->data($data_user)->save()){
			return array('error'=>0,$field=>$value);
		}else{
			return array('error'=>1,'msg'=>'修改失败！请重试。');
		}
	}
	/*修改用户信息*/
	public function scenic_save_user($where,$data){
		if(empty($where)){
			return 0;
		}
		if(!is_array($where)){
			return 0;
		}
		$save	=	$this->where($where)->data($data)->save();
		if($save){
			return 1;
		}else{
			return 0;
		}
	}

	/*增加用户的钱*/
	public function add_money($uid,$money,$desc,$ask=0,$ask_id=0,$type_id=0){
		if($money>0){
			
			if(C('config.mdd_api_url')){
				// mdd 用户查询
				import('@.ORG.mdd_user');
				$mdd_user = new mdd_user();
				$mdd_result = $mdd_user->select($uid);
				if($mdd_result['error_code']){
					return array('error_code' => true, 'msg' => $mdd_result['msg']);
				}
				// mdd 用户查询完
		
				// mdd 扣款
				import('@.ORG.mdd_user');
				$mdd_user = new mdd_user();
				$mdd_result = $mdd_user->add_money($uid,$money,$desc);
				if($mdd_result['error_code']){
					return array('error_code' => true, 'msg' => $mdd_result['msg']);
				}
				// mdd 扣款完
			}
			
			$condition_user['uid'] = $uid;
			$now_user = $this->get_user($uid);
			if($type_id>0){
				D('Fenrun')->add_recommend_award($uid,$type_id,1,$money,$desc);
			}else {
				$zbw_sync =false;
				if(C('config.zbw_key')){

					if($now_user['zbw_cardid']){
						$result = D('ZbwErp')->VipFullAmt($now_user,$money,$desc);

						if(!$result){
							return array('error_code' => true, 'msg' => '智百威卡余额充值失败，请联系管理员');
						}
						if(!$result['result'] ){
							return array('error_code' => true, 'msg' => $result['err']);
						}else{
							D('ZbwErp')->sync_data($uid);
							$zbw_sync = true;
						}

					}
				}
				if ($this->where($condition_user)->setInc('now_money', $money)) {
					if(!$zbw_sync) {
						D('User_money_list')->add_row($uid, 1, $money, $desc, true, $ask, $ask_id);
					}
					if($now_user['openid'] && (strpos($desc,'广用户') || $desc=='在线充值')) {
						if(strpos($desc,'广用户')){
							$money_type  ='用户三级推广佣金结算';
						}else{
							$money_type  ='平台余额在线充值';

						}
						$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
						$href = C('config.site_url') . '/wap.php?c=My&a=transaction';
						$model->sendTempMsg('OPENTM401833445', array('href' => $href,
								'wecha_id' => $now_user['openid'],
								'first' => '尊敬的' . $now_user['nickname'] . ',您的平台余额账户发生变动',
								'keyword1' => date('Y-m-d H:i'),
								'keyword2' => $money_type,
								'keyword3' => $money,
								'keyword4' => $now_user['now_money'] + $money,
								'remark' => '详情请点击此消息进入会员中心-余额记录进行查询!'),
								0);
					}
					return array('error_code' => 0, 'msg' => 'OK');
				} else {
					return array('error_code' => 1, 'msg' => '用户余额充值失败！请联系管理员协助解决。');
				}
			}
		}else{
			return array('error_code' => 1, 'msg' => '充值金额有误');
		}
	}

	public function add_score_recharge_money($uid,$money,$desc){
		if($money>0){
			$condition_user['uid'] = $uid;
			if($this->where($condition_user)->setInc('score_recharge_money',$money)){
				D('User_money_list')->add_row($uid,1,$money,$desc);
				return array('error_code' =>false,'msg' =>'OK');
			}else{
				return array('error_code' => true, 'msg' => '用户'.C('config.score_name').'兑换余额保存记录失败！请联系管理员协助解决。');
			}
		}else{
			return array('error_code' => 1, 'msg' => '充值金额有误');
		}
	}

	/*使用用户的钱*/
	public function user_money($uid,$money,$desc,$ask=0,$ask_id=0,$withdraw=0){
		if($money>0){

			if(C('config.mdd_api_url')){
				// mdd 用户查询
				import('@.ORG.mdd_user');
				$mdd_user = new mdd_user();
				$mdd_result = $mdd_user->select($uid);
				if($mdd_result['error_code']){
					return array('error_code' => true, 'msg' => $mdd_result['msg']);
				}
				// mdd 用户查询完
		
				// mdd 扣款
				import('@.ORG.mdd_user');
				$mdd_user = new mdd_user();
				$mdd_result = $mdd_user->use_money($uid,$money,$desc);
				if($mdd_result['error_code']){
					return array('error_code' => true, 'msg' => $mdd_result['msg']);
				}
				// mdd 扣款完
			}
			
			$zbw_sync =false;
			if(C('config.zbw_key')){
				$now_user = $this->where(array('uid'=>$uid))->find();
				if($now_user['zbw_cardid']){
					$zbw_card = D('ZbwErp')->GetVipInfo($now_user['zbw_cardid']);
					if($zbw_card['money']!=$now_user['now_money']){
						return array('error_code' => true, 'msg' => '智百威卡余额不足扣款失败，请联系管理员');
					}
					$result = D('ZbwErp')->VipPaySheet($now_user,$money,$desc);
					if(!$result){
						return array('error_code' => true, 'msg' => '智百威卡余额扣款失败，请联系管理员');
					}
					if(!$result['result'] ){
						return array('error_code' => true, 'msg' => $result['err']);
					}else{
						D('ZbwErp')->sync_data($uid);
						$zbw_sync = true;
					}

				}
			}
			$now_user = $this->get_user($uid);
			if($now_user['now_money']<$money){
				return array('error_code' => true, 'msg' => '用户余额扣除失败！请联系管理员协助解决。');
			}
			$condition_user['uid'] = $uid;
			if($this->where($condition_user)->setDec('now_money',$money)){

				$score_recharge_money = $this->where($condition_user)->getField('score_recharge_money');
				if($score_recharge_money>0 && $withdraw==0){
					$now_score_recharge_money = $score_recharge_money>$money?$money:$score_recharge_money;
					$this->where($condition_user)->setDec('score_recharge_money',$now_score_recharge_money);
					D('User_money_list')->add_row($uid,2,$now_score_recharge_money,C('config.score_name')."兑换余额记录减扣 ".$now_score_recharge_money." 元",true,$ask,$ask_id);
				}
				if(!$zbw_sync){
					D('User_money_list')->add_row($uid,2,$money,$desc,true,$ask,$ask_id);
				}
				return array('error_code' =>false,'msg' =>'OK');
			}else{
				return array('error_code' => true, 'msg' => '用户余额扣除失败！请联系管理员协助解决。');
			}
		}else{
			return array('error_code' => true, 'msg' => '余额数据有误');
		}
	}


	/*增加用户的积分*/
	public function add_score($uid,$score,$desc,$clean=0){
		if($score>0){
			
			if(C('config.mdd_api_url')){
				// mdd 用户查询
				import('@.ORG.mdd_user');
				$mdd_user = new mdd_user();
				$mdd_result = $mdd_user->select($uid);
				if($mdd_result['error_code']){
					return array('error_code' => true, 'msg' => $mdd_result['msg']);
				}
				// mdd 用户查询完
				
				// mdd 增加积分
				import('@.ORG.mdd_user');
				$mdd_user = new mdd_user();
				$mdd_result = $mdd_user->add_score($uid,$score,$desc);
				if($mdd_result['error_code']){
					return array('error_code' => true, 'msg' => $mdd_result['msg']);
				}
				// mdd 增加积分完
			}
			
			$zbw_sync =false;
			if(C('config.zbw_key')){
				$now_user = $this->get_user($uid);
				if($now_user['zbw_cardid']){
					$result = D('ZbwErp')->VipSaleSheet($now_user,$score,'获得系统积分');
					D('ZbwErp')->sync_data($now_user['uid']);

				}
			}

			$condition_user['uid'] = $uid;
			if($this->where($condition_user)->setInc('score_count',$score)){
				if(!$zbw_sync){
					D('User_score_list')->add_row($uid,1,$score,$desc,1,$clean);
				}
				return array('error_code' =>false,'msg' =>C('config.score_name').'回退成功！');
			}else{

				return array('error_code' => true, 'msg' => '添加'.C('config.score_name').'失败！请联系管理员协助解决。');
			}
		}else{
			return array('error_code' => true, 'msg' => C('config.score_name').'数据有误');
		}
	}
	
	
	/*使用用户的积分*/
	public function use_score($uid,$score,$desc){
		if($score>0){
			
			if(C('config.mdd_api_url')){
				// mdd 用户查询
				import('@.ORG.mdd_user');
				$mdd_user = new mdd_user();
				$mdd_result = $mdd_user->select($uid);
				if($mdd_result['error_code']){
					return array('error_code' => true, 'msg' => $mdd_result['msg']);
				}
				// mdd 用户查询完
				
				// mdd 消耗积分
				import('@.ORG.mdd_user');
				$mdd_user = new mdd_user();
				$mdd_result = $mdd_user->use_score($uid,$score,$desc);
				if($mdd_result['error_code']){
					return array('error_code' => true, 'msg' => $mdd_result['msg']);
				}
				// mdd 消耗积分完
			}
			
			$zbw_sync =false;
			if(C('config.zbw_key')){
				$now_user = $this->get_user($uid);
				if($now_user['zbw_cardid']){
					$zbw_card = D('ZbwErp')->GetVipInfo($now_user['zbw_cardid']);

					if($zbw_card['score']<$now_user['score_count']){
						return array('error_code' => true, 'msg' => '智百威卡积分不足扣款失败，请联系管理员');
					}

					$result = D('ZbwErp')->VipSaleSheet($now_user,(-1*$score),'消费扣除积分');

					D('ZbwErp')->sync_data($now_user['uid']);
					$zbw_sync =true;
				}
			}
			$condition_user['uid'] = $uid;
			if($this->where($condition_user)->setDec('score_count',$score)){
				if(!$zbw_sync){
					D('User_score_list')->add_row($uid,2,$score,$desc);
				}
				return array('error_code' =>false,'msg' =>C('config.score_name').'回退成功！');
			}else{

				return array('error_code' => true, 'msg' => '添加'.C('config.score_name').'失败！请联系管理员协助解决。');
			}
		}else{
			return array('error_code' => true, 'msg' => C('config.score_name').'数据有误');
		}
	}
	

	//这个方法增加的积分到一定时间会清零
	public function add_extra_score($uid,$score,$desc){
		if($score>0){

			$condition_user['uid'] = $uid;
			if($this->where($condition_user)->setInc('score_extra_count',$score) && $this->where($condition_user)->setInc('score_count',$score)){ //积分 跟 奖励积分同时增加，清理的时候同时减少
				D('User_score_list')->add_row($uid,1,$score,$desc);
				return array('error_code' =>false,'msg' =>C('config.score_name').'回退成功！');
			}else{
				return array('error_code' => true, 'msg' => '添加'.C('config.score_name').'失败！请联系管理员协助解决。');
			}
		}else{
			return array('error_code' => true, 'msg' => C('config.score_name').'数据有误');
		}
	}

	/*使用用户的积分*/
	public function user_score($uid,$score,$desc,$type=2){
		if($score>0){
			
			if(C('config.mdd_api_url')){
				// mdd 用户查询
				import('@.ORG.mdd_user');
				$mdd_user = new mdd_user();
				$mdd_result = $mdd_user->select($uid);
				if($mdd_result['error_code']){
					return array('error_code' => true, 'msg' => $mdd_result['msg']);
				}
				// mdd 用户查询完
				
				// mdd 消耗积分
				import('@.ORG.mdd_user');
				$mdd_user = new mdd_user();
				$mdd_result = $mdd_user->use_score($uid,$score,$desc);
				if($mdd_result['error_code']){
					return array('error_code' => true, 'msg' => $mdd_result['msg']);
				}
				// mdd 消耗积分完
			}
			$now_user = $this->get_user($uid);
			if($now_user['score_count']<$score){
				return array('error_code' => true, 'msg' => '减少'.C('config.score_name').'失败！请联系管理员协助解决。');
			}
			$condition_user['uid'] = $uid;
			if($this->where($condition_user)->setDec('score_count',$score)){

				$dec_extra_score = $now_user['score_extra_count']<$score?$now_user['score_extra_count']:$score;
				$this->where($condition_user)->setDec('score_extra_count',$dec_extra_score); //同时减少
				D('User_score_list')->add_row($uid,$type,$score,$desc);
				return array('error_code' =>false,'msg' =>C('config.score_name').'回退成功！');
			}else{
				return array('error_code' => true, 'msg' => '减少'.C('config.score_name').'失败！请联系管理员协助解决。');
			}
		}else{
			return array('error_code' => true, 'msg' => C('config.score_name').'数据有误');
		}
	}

	public  function  check_new($phone,$cate_name){

		$user = $this->field('uid')->where(array('phone'=>$phone))->find();
		if(empty($user)){
			$user = $this->field('uid')->where(array('uid'=>$phone))->find();
		}
		$m = new Model();
		$table = array(C('DB_PREFIX').'group_order',C('DB_PREFIX').'meal_order',C('DB_PREFIX').'appoint_order',C('DB_PREFIX').'shop_order',C('DB_PREFIX').'foodshop_order',C('DB_PREFIX').'store_order');
		$count = 0;
		$where['uid']=$user['uid'];
		switch($cate_name){
			case 'all':
				foreach($table as  $v){
					$count += $m->table($v)->where($where)->count('order_id');
				}
				break;
			case 'group':
				$count  = $m->table($table[0])->where($where)->count('order_id');
				break;
			case 'meal':
				$count  = $m->table($table[1])->where($where)->count('order_id');
				break;
			case 'appoint':
				$count  = $m->table($table[2])->where($where)->count('order_id');
				break;
			case 'shop':
				$count  = $m->table($table[3])->where($where)->count('order_id');
				break;
			case 'foodshop':
				$count  = $m->table($table[4])->where($where)->count('order_id');
				break;
			case 'store':
				$count  = $m->table($table[5])->where($where)->count('order_id');
				break;
		}

		if($count>0){
			return 0;
		}else{
			return 1;
		}
	}

	public function check_score_can_use($uid,$money,$order_type,$group_id=0,$mer_id=0){
		$now_user = $this->get_user($uid);
		$score_count = $now_user['score_count'];
		$score_can_use_count=0;
		$score_deducte=0;
		if ($order_type == 'group'||$order_type == 'meal'||$order_type == 'takeout'||$order_type == 'food'||$order_type == 'foodPad') {
			$user_score_use_condition = C('config.user_score_use_condition');
			$user_score_max_use = D('Percent_rate')->get_max_core_use($mer_id, $order_type);//不同业务不同积分
			if($order_type=='group'){
				$group_info = D('Group')->where(array('group_id'=>$group_id))->find();

				if($group_info['score_use']){
					if($group_info['group_max_score_use']!=0){
						$user_score_max_use = $group_info['group_max_score_use'];
					}
				}else{
					$user_score_max_use = 0;
				}
			}
			$user_score_use_percent=(float)C('config.user_score_use_percent');
			$score_max_deducte=bcdiv($user_score_max_use,$user_score_use_percent,2);


			if($user_score_use_percent>0&&$score_max_deducte>0&&$user_score_use_condition>0){   //如果设置没有错误
				if ($money>=$user_score_use_condition) {             //判断是否够使用积分的条件，即这个订单的总额是否大于规定的金额
					if($money>$score_max_deducte){                    //判断积分最大抵扣金额是否比这个订单的总额大
						$score_can_use_count = (int)($score_count>$user_score_max_use?$user_score_max_use:$score_count);//如果用户积分大于每个订单的积分可使用数 则按可使用数算
						$score_deducte = bcdiv($score_can_use_count,$user_score_use_percent,2);
						$score_deducte = $score_deducte>$money?$money:$score_deducte;
					}else{
						//最大可抵扣的金额比总单金额大 只扣掉总单范围内的积分 扣除积分=总单*积分抵扣比例
						$score_can_use_count = ceil($money*$user_score_use_percent);
						$score_deducte = bcdiv($score_can_use_count,$user_score_use_percent,2);
						$score_deducte = $score_deducte>$money?$money:$score_deducte;
					}
				}
			}
		}
		return array('score'=>$score_can_use_count,'score_money'=>floatval($score_deducte));
	}

	public function get_user_by_phone($phone){
		$now_user = $this->where(array('phone'=>$phone))->find();
		return $now_user['uid'];
	}
	# 按出生年月算年龄	韩露
	public function age($birthday){
		if(empty($birthday)){
			return '';
		}
		$age = date('Y', time()) - date('Y', strtotime($birthday)) - 1;
		if (date('m', time()) == date('m', strtotime($birthday))){
			if (date('d', time()) > date('d', strtotime($birthday))){
			$age++;
			}
		}elseif (date('m', time()) > date('m', strtotime($birthday))){
			$age++;
		}
		return $age;
	}

	/*
	 * 用户签到功能
	 * */

	public function check_sign_today($uid){
		$recently_sign = M('User_sign')->where(array('uid'=>$uid))->order('id DESC')->find();
		if($recently_sign && strtotime(date('Ymd',$_SERVER['REQUEST_TIME']))==strtotime(date('Ymd',$recently_sign['sign_time']))){
			return array('error_code'=>0,'msg'=>'已经签到了');
		}else{
			return array('error_code'=>1,'msg'=>'今天没签到');
		}
	}

	public function sign_in($uid){
		$recently_sign = M('User_sign')->where(array('uid'=>$uid))->order('id DESC')->find();
		$now_user = $this->get_user($uid);
		if(empty($now_user['phone'])){
			return array('error_code'=>2,'msg'=>'您未绑定手机，请绑定手机');
		}
		if($recently_sign && strtotime(date('Ymd',$_SERVER['REQUEST_TIME']))==strtotime(date('Ymd',$recently_sign['sign_time']))){
			return array('error_code'=>1,'msg'=>'同一天只能签到一次，请明天再来！');
		}

		if($recently_sign && (strtotime(date('Ymd',$_SERVER['REQUEST_TIME']))-strtotime(date('Ymd',$recently_sign['sign_time'])))>86400){
			$score_get = C('config.sign_get_score');
			$sign_day = 1;
		}else{

			$score_get = (($recently_sign['day']+1)%30===0?30:($recently_sign['day']+1)%30)+C('config.sign_get_score')-1;
			$sign_day = $recently_sign['day']+1;
		}
		$data['uid'] = $uid;
		$data['day'] = $sign_day;
		$data['score_count'] = $score_get;
		$data['sign_time'] = $_SERVER['REQUEST_TIME'];
		M('User_sign')->add($data);
		$this->add_extra_score($uid,$score_get,'第'.$sign_day.'天签到获得'.$score_get.'个'.C('config.score_name').'');

		D('Scroll_msg')->add_msg('sign',$uid,'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'签到获得'.$score_get.'个'.C('config.score_name'));
		$return_msg = '签到成功!获得'.$score_get.'个'.C('config.score_name');
		if($sign_day>1){
			$return_msg = '连续'.$sign_day.'天签到!获得'.$score_get.'个'.C('config.score_name');
		}
		return array('error_code'=>0,'msg'=>$return_msg);
	}

	/*
	 * 今天签到人数
	 * */
	public function sign_num_today(){
		$today = date('Y-m-d',$_SERVER['REQUEST_TIME']);
		$today_start = strtotime($today.' 00:00:00');
		$today_end = strtotime($today.' 23:59:59');
		$where['_string'] = 'sign_time > '.$today_start.' OR sign_time < '.$today_end;
		$today_sign_num = M('User_sign')->where($where)->count();
		return $today_sign_num;
	}

	public function clean_canel_userlist(){
		$this->join('AS u LEFT JOIN '.C('DB_PREFIX').'user_level AS l ON l.level = u.level_id')->where(array('score_clean_notice_time'=>0))->limit(10)->select();
	}

	public function merge_user_from_new($uid,$old_uid){
		if(C('config.open_score_get_percent')==1){
			$score_get = C('config.score_get_percent')/100;
		}else{
			$score_get = C('config.user_score_get');
		}
		//$now_user = $this->get_user($uid);
		$where['uid'] =$uid;
		$where['paid'] =1;
		$arr[] = M('Store_order')->field('(SUM(balance_pay)+SUM(payment_money*(SIGN(0-is_own)+1)) +SUM(merchant_balance)) AS paymoney')->where($where)->group('uid')->select();

		$where['_string'] = 'status = 2 OR status = 3';
		$arr[] = M('Shop_order')->field('(SUM(balance_pay) +SUM(payment_money*(SIGN(0-is_own)+1)) +SUM(merchant_balance) )AS paymoney')->where($where)->group('uid')->select();

		//unset($where['_string']);
		//$where['business_type']='foodshop';
		//$arr[] = M('Plat_order')->field('(SUM(system_balance)+SUM(pay_money*(SIGN(0-is_own)+1)) +SUM(merchant_balance_pay)) AS paymoney')->where($where)->group('uid')->select();
		$all_money =0;

		foreach ($arr as $item) {
			$all_money+=$item[0]['paymoney'];
		}
		$merge_score = round($all_money*$score_get);
		$this->add_score($old_uid,$merge_score,'来自合并账号ID:'.$uid.'返还积分');
		//return $all_money;

	}

	public function get_userinfo_weixin($openid){
		import('ORG.Net.Http');
		$http = new Http();
		$access_token_array = D('Access_token_expires')->get_access_token();
		if (!$access_token_array['errcode']) {
			$return = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_array['access_token'].'&openid='.$openid.'&lang=zh_CN');
			$userifo = json_decode($return,true);
			if(empty($userifo)){
				return false;
			}else{
				return $userifo;
			}
		}
		return false;
	}

	public function get_userinfo_wxapp($code=''){
		$appid = C('config.pay_wxapp_appid');
		$appsecret = C('config.pay_wxapp_appsecret');

		import('ORG.Net.Http');
		$http = new Http();

		$return = Http::curlPost('https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code='.$code.'&grant_type=authorization_code', array());

		import('@.ORG.aeswxapp.wxBizDataCrypt');

		$pc = new WXBizDataCrypt($appid, $return['session_key']);
		$errCode = $pc->decryptData($_POST['encryptedData'],$_POST['iv'],$data);
		$jsonrt = json_decode($data,true);
		if(empty($jsonrt) || $jsonrt['errcode']){
			return false;
		}else{
			return $jsonrt;
		}
	}
	//获取单个用户信息
	public function user_find($field,$where){
		$result= D('User')->field($field)->where($where)->find();
		if($result){
			return $result;
		}else{
			return false;
		}
	}
}
?>