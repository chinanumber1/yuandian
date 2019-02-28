<?php
	class CouponAction extends	BaseAction{
//		public function index(){
//			$res = D('System_coupon')->get_coupon_list_by_type('appoint',0,10,0);
//
//		}
		public function show(){
			if(empty($_GET['coupon_id'])){
				$this->error_tips('没有相关优惠券！');
			}else{
				if(!empty($this->user_session)&&!empty($this->user_session['phone'])){
					$this->assign('phone',$this->user_session['phone']);
					$phone = $this->user_session['phone'];
					$has_get = D('System_coupon')->get_coupon_count_by_phone($_GET['coupon_id'],$this->user_session['phone']);
					if($has_get==0){
						$res = $this->had_pull($_GET['coupon_id'],$phone);
						$has_get = $res['coupon']['has_get'];
					}
				}else if(!empty($_SESSION['unlogin_user_coupon']['phone'])){
					$this->assign('unlogin_phone',$_SESSION['unlogin_user_coupon']['phone']);
					$phone = $_SESSION['unlogin_user_coupon']['phone'];
					$has_get = D('System_coupon')->get_coupon_count_by_phone($_GET['coupon_id'],$_SESSION['unlogin_user_coupon']['phone']);
					if($has_get>1){
						$this->error_tips('请先进行登录在领取！',U('Login/index'));
					}else{
						$has_get=0;
					}
				}
				$_SESSION[$phone]['browse_coupon_count']+=1;
				$coupon = D('System_coupon')->get_coupon($_GET['coupon_id']);
				if(!empty($phone)){
					$is_new = D('User')->check_new($phone,$coupon['cate_name']);
					$this->assign('is_new',$is_new);
				}
				$coupon['has_get']=$has_get;
				if($coupon['status']!=1){
					$msg = $this->coupon_error($res['error_code']);
					$this->error($msg['msg']);
				}
				$coupon['des_detial']=explode(PHP_EOL,$coupon['des_detial']);
				if(($coupon['num']-$coupon['had_pull'])>($coupon['limit']-$has_get)){
					if($coupon['limit']-$has_get>0){
						$coupon['can_get'] = $coupon['limit']-$has_get;
					}else{
						$coupon['can_get']=0;
					}
				}else{
					if($coupon['num']-$coupon['had_pull']>0){
						$coupon['can_get'] = $coupon['num']-$coupon['had_pull'];
					}else{
						$coupon['can_get'] = 0;
					}
				}
				$coupon['cate_id']=unserialize($coupon['cate_id']);
				switch($coupon['cate_name']){
					case 'all':
						$url = $this->config['site_url'];
						break;
					case 'group':
						$url = $this->config['site_url'].'/category/meishi/all/';
						break;
					case 'meal':
						$url = $this->config['site_url'].'/kd/all/';
						break;
					case 'appoint':
						if(!empty($this->config['appoint_site_url'])){
							$url = $this->config['appoint_site_url'];
						}else{
							$url = $this->config['site_url'].'/appoint/';
						}
						break;
				}
				$coupon['url'] = $url;
				$this->assign('coupon',$coupon);
				$this->assign('browse_coupon_count',$_SESSION[$phone]['browse_coupon_count']);
			}
			$this->display();
		}

		public function coupon_error($error_code){
			switch($error_code){
				case 0:
					return array("msg"=>"领取成功！");
					break;
				case 1:
					return array("msg"=>"领取失败！");
					break;
				case 2:
					return array("msg"=>"该优惠券已过期");
					break;
				case 3:
					return array("msg"=>"该优惠券已经被领完了！");
					break;
				case 4:
					return array("msg"=>"该优惠券只能新用户领取！");
					break;
			}
		}

		public function had_pull($coupon_id,$phone){
			if(IS_POST){
				if(empty($_POST['coupon_id'])){
					$return = array('error_code'=>1);
					$this->ajaxReturn($return);exit;
				}else{
					$coupon_id = $_POST['coupon_id'];
					$phone = $_POST['phone'];
				}
			}
			$coupon = D('System_coupon');
			$has_get = $coupon->get_coupon_count_by_phone($coupon_id,$phone);
			$return['phone']=$_POST['phone'];
			$return['has_get'] =$has_get;
			if((empty($this->user_session)||$this->user_session['phone']!=$phone)&&$has_get>=1){
				unset($_SESSION['unlogin_user_coupon']['phone']);
				$return['login'] = 0;
				$return['error_code']=1;
				$this->ajaxReturn($return);exit;
			}else{
				$return['login'] = 1;
			}
			if(empty($this->user_session)) {
				if($_POST['verify_type']=='sms'){
					if ($this->config['bind_phone_verify_sms']!='0'&&!empty($this->config['sms_key'])) {
						if (isset($_POST['verify']) && !empty($_POST['verify'])) {
							$sms_verify_result = D('Smscodeverify')->verify($_POST['verify'], $phone);
							if ($sms_verify_result['error_code']) {
								exit(json_encode(array('error_code' => '1', 'msg' => '验证码不正确！', 'dom_id' => 'verify')));
							}
						}
					}else{
						exit(json_encode(array('error_code' => '1', 'msg' => '短信功能异常！', 'dom_id' => 'verify')));
					}
				}else if($_POST['verify_type']=='nosms'){
					if (md5($_POST['verify']) != $_SESSION['merchant_reg_verify']) {
						exit(json_encode(array('error_code' => '1', 'msg' => '随机验证码不正确！', 'dom_id' => 'verify')));
					}
				}
			}
			$res = $coupon->had_pull($coupon_id,$phone);
			$return  =array_merge($return,$res);
			if(!$return['error_code']||$return['error_code']==2){
				$_SESSION['unlogin_user_coupon']['phone']=$_POST['phone'];
			}
			$coupon_info = $return['coupon'];
			if(($coupon_info['num']-$coupon_info['had_pull'])>($coupon_info['limit']-$coupon_info['has_get'])){
				if($coupon_info['limit']-$coupon_info['has_get']>0){
					$return['can_get'] = $coupon_info['limit']-$coupon_info['has_get'];
				}else{
					$return['can_get']=0;
				}
			}else{
				if($coupon_info['num'] - $coupon_info['had_pull']>0){
					$return['can_get'] = $coupon_info['num'] - $coupon_info['had_pull'];
				}else{
					$return['can_get'] = 0;
				}
			}
			if(IS_POST){
				$this->ajaxReturn($return);exit;
			}else{
				return $return;
			}

		}

		public function verify(){
			$verify_type = $_GET['type'];
			if(empty($verify_type)){exit;}
			import('ORG.Util.Image');
			Image::buildImageVerify(4,1,'jpeg',53,26,'merchant_'.$verify_type.'_verify');
		}
	}