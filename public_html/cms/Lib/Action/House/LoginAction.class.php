<?php
/*
 * 社区中心-登录
 *
 */

class LoginAction extends BaseAction{
    public function index(){
		$this->display();
    }
	public function check(){
		if($this->isAjax()){
			if(md5($_POST['verify']) != $_SESSION['house_login_verify']){
				exit(json_encode(array('error'=>'1','msg'=>'验证码不正确！','dom_id'=>'verify')));
			}
			
			$database_house = D('House_village');
			$database_house_admin = D('House_admin');
			$condition_house['account'] = trim($_POST['account']);
			if($_POST['village_id']){
				$condition_house['village_id'] = trim($_POST['village_id']);
			}
			$now_house = $database_house->field(true)->where($condition_house)->find();
			if(empty($now_house)){
				//社区管理角色
				$now_role = $database_house_admin->field(true)->where($condition_house)->find();
				if (!$now_role) {
					exit(json_encode(array('error'=>'2','msg'=>'用户名不存在！','dom_id'=>'account')));
				}
				if ($now_role['status'] != 1) {
					exit(json_encode(array('error'=>'2','msg'=>'用户已禁止！','dom_id'=>'account')));
				}

				$pwd = md5($_POST['pwd']);
				if($pwd != $now_role['pwd']){
					exit(json_encode(array('error'=>'3','msg'=>'密码错误！','dom_id'=>'pwd')));
				}

				// 社区信息
				$now_house = $database_house->field(true)->where(array('village_id'=>$now_role['village_id']))->find();
				$data_house = array();
				$data_house['id'] = $now_role['id'];
				$data_house['login_count'] = $now_role['login_count'] + 1;
				$data_house['last_ip'] = get_client_ip(1);;
				$data_house['last_time'] = $_SERVER['REQUEST_TIME'];
				$now_house['menus'] = explode(',', $now_role['menus']);
				$now_house['user_name'] = $now_role['realname'] ? $now_role['realname'] : $now_role['account'];
				$now_house['role_id'] = $now_role['id'];

				//判断社区是否到期
				if (isset($now_house['expiration_time']) && $now_house['expiration_time'] && $now_house['expiration_time']<time()) {
					exit(json_encode(array('error'=>'8','msg'=>'该社区已到期，请联系管理员！')));
				}
				
				if($database_house_admin->data($data_house)->save()){
					session('house',$now_house);
					exit(json_encode(array('error'=>'0','msg'=>'登录成功,现在跳转~','dom_id'=>'account')));
				}else{
					exit(json_encode(array('error'=>'6','msg'=>'登录信息保存失败,请重试！','dom_id'=>'account')));
				}

			}else{ //超级管理员
				$pwd = md5($_POST['pwd']);
				if($pwd != $now_house['pwd']){
					exit(json_encode(array('error'=>'3','msg'=>'密码错误！','dom_id'=>'pwd')));
				}

				if($now_house['status'] == 2){
					exit(json_encode(array('error'=>'5','msg'=>'您被禁止登录！请联系工作人员获得详细帮助。','dom_id'=>'account')));
				}

				if(empty($_POST['village_id'])){
					$house_list = $database_house->field('`village_id`,`village_name`,`status`')->where(array('account'=>$now_house['account'],'pwd'=>$now_house['pwd'],'status'=>array('neq','2')))->order('`village_id` ASC')->select();
					if(is_array($house_list) && count($house_list) > 1){
						exit(json_encode(array('error'=>'7','house_list'=>$house_list)));
					}
				}

				//判断社区是否到期
				if (isset($now_house['expiration_time']) && $now_house['expiration_time'] && $now_house['expiration_time']<time()) {
					exit(json_encode(array('error'=>'8','msg'=>'该社区已到期，请联系管理员！')));
				}

				$data_house['village_id'] = $now_house['village_id'];
				$data_house['last_time'] = $_SERVER['REQUEST_TIME'];
				//查询权限 超级管理员赋予所有权限				
				$admin_menus = D('House_menu')->field(true)->where(array('status'=>1))->select();
				$menus = array();
				foreach ($admin_menus as $value) {
					$menus[] = $value['id'];
				}
				$now_house['menus'] = $menus;
				if($database_house->data($data_house)->save()){
					session('house',$now_house);
					exit(json_encode(array('error'=>'0','msg'=>'登录成功,现在跳转~','dom_id'=>'account')));
				}else{
					exit(json_encode(array('error'=>'6','msg'=>'登录信息保存失败,请重试！','dom_id'=>'account')));
				}
			}
		}else{
			exit('deney Access !');
		}
	}
	
	public function logout(){
		session('house',null);
		header('Location: '.U('Login/index'));
	}
	public function verify(){
		$verify_type = $_GET['type'];
		if(empty($verify_type)){exit;}
		import('ORG.Util.Image');
		Image::buildImageVerify(4,1,'jpeg',53,26,'house_'.$verify_type.'_verify');
	}
}