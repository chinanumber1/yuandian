<?php
class Share_lotteryAction extends BaseAction{
	public function share_coupon(){
		$now_user = D('User')->get_user($this->user_session['uid']);

		if(empty($now_user)){
			session('user',null);
			$this->error_tips('请登录后领取！',U('Login/index'));
		}
		$share_coupon_adver = D('Adver')->get_adver_by_key('share_coupon',5);

		if($share_coupon_adver){
			shuffle($share_coupon_adver);
			$this->assign('share_coupon_adver',$share_coupon_adver[0]);
		}
		$res = M('Share_coupon_list')->where(array('type'=>$_GET['type'],'order_id'=>$_GET['order_id']))->find();

		if($res['uid'] == $now_user['uid']){
			$this->error_tips('您是分享用户不能抢券');
		}
		$error = array(
			0=>'未抢',
			1=>'已经抢过了',
			2=>'已经抢完了',
		);
		$share_status = 0; //未抢

		if(strpos($res['userlist'],$now_user['uid'])!==false){
			//$share_status = 1; //已抢
			$condition['uid'] = $this->user_session['uid'];
			$condition['share_get'] = $_GET['order_id'];
			$coupon  = D('System_coupon')->get_coupon_hadpull($condition);
			$platform = array('wap' => '移动网页', 'app' => 'App', 'weixin' => '微信');
			$coupon['platform'] = unserialize($coupon['platform']);
			$tmp_platform = '';
			foreach ($coupon['platform'] as $vt) {
				$tmp_platform .= $platform[$vt] . '/';
			}
			$category = array(
					'all' => '通用券',
					'group' => C('config.group_alias_name'),
					'meal' => C('config.meal_alias_name'),
					'appoint' => C('config.appoint_alias_name'),
					'shop' => C('config.shop_alias_name'),
					'store' => '优惠买单',
			);
			switch($coupon['cate_name']){
				case 'all':
					$coupon['coupon_url'] = './wap.php';
					break;
				case 'group':
					$coupon['coupon_url'] = './wap.php?c=Group&a=index';
					break;
				case 'meal':
					$coupon['coupon_url'] = './wap.php?c=Foodshop&a=index';
					break;
				case 'appoint':
					$coupon['coupon_url'] = './wap.php?c=Appoint&a=index';
					break;
				case 'shop':
					$coupon['coupon_url'] = './wap.php?c=Shop&a=index';
					break;
				case 'store':
					$coupon['coupon_url'] = './wap.php';
					break;
			}
			$coupon['category_txt'] = $category[$coupon['cate_name']];
			$coupon['platform'] = substr($tmp_platform, 0, -1);
			//$coupon['start_time'] = date('m月d日' ,$coupon['start_time']);
			//$coupon['end_time'] = date('m月d日' ,$coupon['end_time']);
			$this->assign('coupon',$coupon);
			$this->display('get_coupon');exit;
		}
		if($res['had_pull']==$res['share_num']){
			$this->error_tips($error[2]);
		}
		$share_user = D('User')->get_user($res['uid']);
		$this->assign('share_user',$share_user);
		$this->assign('share_status',$share_status);
		$this->assign('error',$error);

		$this->display();
	}

	public function ajax_get_coupon(){
		$param['uid'] = $this->user_session['uid'];
		$param['order_id'] = $_POST['order_id'];
		$param['type'] = $_POST['type'];

		$res  = D('System_coupon')->share_coupon_rand_get_coupon($param);
		if($res['error']){
			$this->error($res['msg']);
		}else{
			$this->success($res['msg']);
		}
	}

	public function my_get_coupon(){
		$param['uid']=$where['uid'] = $this->user_session['uid'];
		$param['order_id'] =$where['order_id'] = $_GET['order_id'];
		$param['type'] = $_GET['type'];
		$res = M('Share_coupon_list')->where(array('type'=>$_GET['type'],'order_id'=>$_GET['order_id']))->find();
		if($res && $res['get_num']>0){

			switch($param['type']){
				case 'shop':
					if($order = M('Shop_order')->where($where)->find()){
						$order['status'] == 2  && $result = M('Shop_order')->where($where)->setField('share_status',2);
					}
					break;
				case 'store':
					if($order = M('Store_order')->where($where)->find()){
						$order['status']==2 && $result = M('Store_order')->where($where)->setField('share_status',2);
					}
					break;
				default:
					$this->error_tips('无效请求');
					break;
			}
			$share_coupon_adver = D('Adver')->get_adver_by_key('share_coupon',5);

			if($share_coupon_adver){
				shuffle($share_coupon_adver);
				$this->assign('share_coupon_adver',$share_coupon_adver[0]);
			}
			$condition['uid'] = $this->user_session['uid'];
			$condition['share_get'] = $_GET['order_id'];
			$coupon  = D('System_coupon')->get_coupon_hadpull($condition);
			$platform = array('wap' => '移动网页', 'app' => 'App', 'weixin' => '微信');
			$coupon['platform'] = unserialize($coupon['platform']);
			$tmp_platform = '';
			foreach ($coupon['platform'] as $vt) {
				$tmp_platform .= $platform[$vt] . '/';
			}
			$category = array(
					'all' => '通用券',
					'group' => C('config.group_alias_name'),
					'meal' => C('config.meal_alias_name'),
					'appoint' => C('config.appoint_alias_name'),
					'shop' => C('config.shop_alias_name'),
					'store' => '优惠买单',
			);
			switch($coupon['cate_name']){
				case 'all':
					$coupon['coupon_url'] = './wap.php';
					break;
				case 'group':
					$coupon['coupon_url'] = './wap.php?c=Group&a=index';
					break;
				case 'meal':
					$coupon['coupon_url'] = './wap.php?c=Foodshop&a=index';
					break;
				case 'appoint':
					$coupon['coupon_url'] = './wap.php?c=Appoint&a=index';
					break;
				case 'shop':
					$coupon['coupon_url'] = './wap.php?c=Shop&a=index';
					break;
				case 'store':
					$coupon['coupon_url'] = './wap.php';
					break;
			}
			$coupon['category_txt'] = $category[$coupon['cate_name']];
			$coupon['platform'] = substr($tmp_platform, 0, -1);
			//$coupon['start_time'] = date('m月d日' ,$coupon['start_time']);
			//$coupon['end_time'] = date('m月d日' ,$coupon['end_time']);
			$this->assign('coupon',$coupon);
			$this->display();
		}elseif($res['get_num']==0){
			$res = D('System_coupon')->share_coupon_rand_get_coupon($param);
			if($res['error']){
				$this->error_tips('获取失败');
			}else{
				redirect(U('Share_lottery/my_get_coupon',$param));
			}
		}else{

			$this->error_tips('访问失败');
		}
	}

}
	
?>