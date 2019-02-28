<?php
class SystemcouponAction extends BaseAction{
	public function index(){
		$coupon  = D('System_coupon');
		$coupon_list = $coupon->get_coupon_list(array('rand_send'=>0));
		if(!empty($this->user_session)) {
			$now_user_coupon  = $coupon->get_coupon_category_by_phone($this->user_session['uid']);
			foreach($now_user_coupon as $v){
				if(!empty($coupon_list[$v['coupon_id']])) {
					$coupon_list[$v['coupon_id']]['selected'] = 1;
				}
			}
		}
		$category = array(
				'all' => '通用券',
				'group' => C('config.group_alias_name'),
				'meal' => C('config.meal_alias_name'),
				'appoint' => C('config.appoint_alias_name'),
				'shop' => C('config.shop_alias_name'),
				'store' =>  C('config.cash_alias_name'),
		);
		if(empty($this->config['appoint_page_row'])){
			unset($category['appoint']);
		}
		$platform = array('wap' => '移动网页', 'app' => 'App', 'weixin' => '微信');
		foreach ($coupon_list as $vv) {
			if($vv['status']==2 && $vv['last_time']<$_SERVER['REQUEST_TIME']-86400)
				continue;
			$had_pull = M('System_coupon_hadpull')->where(array('uid'=>$this->user_session['uid'],'coupon_id'=>$vv['coupon_id']))->count();

			if($had_pull>=$vv['limit']){
				$vv['status'] = 4;//超过限制
			}
			$vv['platform'] = unserialize($vv['platform']);
			$vv['has_get'] =$had_pull;
			if($vv['limit']-intval($had_pull)<=$vv['num']-$vv['had_pull']){
				$vv['can_get_num'] = $vv['limit']-intval($had_pull);
			}else{
				$vv['can_get_num'] = $vv['num']-$vv['had_pull'];
			}
			$vv['can_get'] = unserialize($vv['platform']);
			$tmp_platform = '';
			foreach ($vv['platform'] as $vt) {
				$tmp_platform .= $platform[$vt] . '/';
			}
			$vv['platform'] = substr($tmp_platform, 0, -1);
			$tmp[$vv['cate_name']][] = $vv;
		}

		foreach ($category as $k => $c) {
			if (empty($tmp[$k])) {
				$tmp[$k] = array();
				$category_tmp[$k]['count'] = 0;
			}else{
				$category_tmp[$k]['count'] = count($tmp[$k]);
			}
		}

		arsort($category_tmp);
		$max_category = array_keys($category_tmp);
		$this->assign('max_category', $max_category[0]);
		$this->assign('category', $category);
		$this->assign('category_tmp', $category_tmp);
		$this->assign('isnew', D('User')->check_new($this->user_session['uid'],'all'));
		$this->assign('coupon_list',$tmp);
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

	//领取优惠券
	public function had_pull()
	{
		$coupon_id = intval($_POST['coupon_id']);
		$uid = $this->user_session['uid'];
		if (empty($this->user_session)) {
			echo json_encode(array('error_code' => 6, 'msg' => '未登录'));
			die;
		}elseif(empty($this->user_session['phone'])){
			echo json_encode(array('error_code' => 7, 'msg' => '未绑定手机号码'));
		}
		$model = D('System_coupon');
		$has_get = $model->get_coupon_count_by_uid($coupon_id, $uid);
		//$return['has_get'] = $has_get;

		$result = $model->had_pull($coupon_id, $uid);
		if ($result['error_code'] != 0) {
			switch ($result['error_code']) {
				case '1':
					$error_msg = '领取失败';
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
			echo json_encode(array('error_code' => $result['error_code'], 'msg' => $error_msg));
			die;
		}
		$model->decrease_sku(0,1,$coupon_id);//网页领取完，微信卡券库存需要同步减少
		if($result['coupon']['limit']-$result['coupon']['has_get']<=$result['coupon']['num']-$result['coupon']['had_pull']){
			$result['coupon']['can_get'] = $result['coupon']['limit']-$result['coupon']['has_get'];
		}else{
			$result['coupon']['can_get'] =$result['coupon']['num']-$result['coupon']['had_pull'];
		}
		echo json_encode(array('error_code' => 0, 'msg' => '领取成功', 'coupon' => $result['coupon']));
		die;

	}



	public function ajax_check_login(){
		if (empty($this->user_session)) {
			echo json_encode(array('error_code' => 6, 'msg' => '未登录,登录后才能领取'));
		}else{
			if((!$res = D('User')->check_new($this->user_session['uid'],'all')) &&  intval($_POST['isnew'])){
				echo json_encode(array('error_code' => 2, 'msg' => '只允许新用户领取'));die;
			}
			echo json_encode(array('error_code' => 0, 'msg' => '已登录'));
		}
		exit;
	}

	public function link(){
		if($_GET['cate_name']=='all'){
			redirect($this->config['site_url'].'/wap.php');
		}else if($_GET['cate_name']=='shop'){
			redirect($this->config['site_url'].'/wap.php?c=Shop&a=index');
		}else if($_GET['cate_name']=='group'){
			redirect($this->config['site_url'].'/wap.php?c=Group&a=index');
		}else if($_GET['cate_name']=='meal'){
			redirect($this->config['site_url'].'/wap.php?c=Foodshop&a=index');
		}else if($_GET['cate_name']=='store'){
			redirect($this->config['site_url'].'/wap.php?c=Merchant&a=store_list');
		}else if($_GET['cate_name']=='appoint'){
			redirect($this->config['site_url'].'/wap.php?c=Appoint&a=index');
		}
	}


//	public function verify(){
//		$verify_type = $_GET['type'];
//		if(empty($verify_type)){exit;}
//		import('ORG.Util.Image');
//		Image::buildImageVerify(4,1,'jpeg',53,26,'merchant_'.$verify_type.'_verify');
//	}

}