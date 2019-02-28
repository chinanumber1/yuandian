<?php
/*
 * 后台管理软件通信API
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/05 15:28
 * 
 */

class SoftapiAction extends CommonAction{
	protected $system_session;
	protected function _initialize(){	
		parent::_initialize();
		
		$this->static_path   = './tpl/System/Static/';
		$this->static_public = './static/';
		$this->assign('static_path',$this->static_path);
		$this->assign('static_public',$this->static_public);
		
		C('DEFAULT_THEME','');
	}
	public function index(){
		$this->display();
	}
	public function login(){
		$database_admin = M('Admin');
		$condition_admin['account'] = $this->_post('account');
		$condition_admin['status'] = 1;
		$now_admin = $database_admin->field(true)->where($condition_admin)->find();
		if(empty($now_admin)){
			$this->returnCode('1001','用户名不存在');
		}
		$pwd = $this->_post('pwd','htmlspecialchars,md5');
		if($pwd != $now_admin['pwd']){
			$this->returnCode('1002','密码错误');
		}
		if($now_admin['status'] != 1){
			$this->returnCode('1003','用户状态不正常');
		}
		$now_admin['show_account'] = '超级管理员';
		if ($now_admin['level'] == 1) {
			if ($now_admin['area_id']) {
				$area = M('Area')->field(true)->where(array('area_id' => $now_admin['area_id']))->find();
				$now_admin['show_account'] = $area['area_name'] . '管理员';
			}
		} else {
			$now_admin['show_account'] = '普通管理员';
		}
		
		$data_admin['id'] = $now_admin['id'];
		$data_admin['last_ip'] = get_client_ip(1);
		$data_admin['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_admin['login_count'] = $now_admin['login_count']+1;
		if($database_admin->data($data_admin)->save()){
			$now_admin['login_count'] += 1;
			if(!empty($now_admin['last_ip'])){
				import('ORG.Net.IpLocation');
				$IpLocation = new IpLocation();
				$last_location = $IpLocation->getlocation(long2ip($now_admin['last_ip']));
				$now_admin['last']['country'] = iconv('GBK','UTF-8',$last_location['country']);
				$now_admin['last']['area'] = iconv('GBK','UTF-8',$last_location['area']);
			}
			session('soft_system',$now_admin);
			$this->returnCode('0',session_id());
		}else{
			$this->returnCode('1004','登录失败，请重试');
		}
	}
	
	public function menu(){
		session_commit();
		session_id($_GET['session_id']);
		session_start();
		$this->system_session = session('soft_system');
		
		$menu_ico_list = array(
			'1'=>'index',
			'2'=>'system',
			'3'=>'group',
			'4'=>'meal',
			'5'=>'user',
			'12'=>'merchant',
			'24'=>'weixin',
			'36'=>'classify',
			'48'=>'extension',
			'56'=>'appoint',
			'59'=>'waimai',
			'66'=>'deliver',
			'74'=>'house',
			'96'=>'wxapp',
		);
		
		/****实时查找账号的权限****/
		$tmerch = M("Admin")->field('menus')->where(array('id' => $this->system_session['id']))->find();
		if (empty($tmerch['menus'])) {
			$this->system_session['menus'] = '';
		} else {
			$this->system_session['menus'] = explode(",", $tmerch['menus']);
		}
		/****实时查找账号的权限****/
		
		$database_system_menu = M('System_menu');
		$condition_system_menu['status'] = 1;
		$condition_system_menu['show'] = 1;
		$menu_list = $database_system_menu->field(true)->where($condition_system_menu)->order('`sort` DESC,`fid` ASC,`id` ASC')->select();
		$flag = false;
		$module = $action = '';
		foreach($menu_list as $key=>$value){
			//****处理权限****//
			if (strtolower($value['module']) == strtolower(MODULE_NAME) && strtolower($value['action']) == strtolower(ACTION_NAME)) {
				if (!empty($this->system_session['menus']) && !in_array($value['id'], $this->system_session['menus'])) {
					$flag = true;
					continue;
				}
			}
			//****处理权限****//
				
			if (empty($value['area_access']) && $this->system_session['area_id'] && !in_array($value['id'], $this->system_session['menus'])) continue;
			/**********控制账号的菜单显示************/
			if (!empty($this->system_session['menus']) && !in_array($value['id'], $this->system_session['menus'])) continue;
			/**********控制账号的菜单显示************/
			
			if (empty($module) && $value['fid']) {
				$module = ucfirst($value['module']);
				$action = $value['action'];
			}
			
			$value['name'] =  str_replace('订餐',$this->config['meal_alias_name'],$value['name']);
			$value['name'] =  str_replace('餐饮',$this->config['meal_alias_name'],$value['name']);
			$value['name'] = str_replace('团购',$this->config['group_alias_name'],$value['name']);
			if($value['fid'] == 0){	
				$system_menu[$value['id']] = $value;
				$system_menu[$value['id']]['ico'] = $menu_ico_list[$value['id']];
			}else{
				$system_menu[$value['fid']]['menu_list'][] = $value;
			}
		}
		$this->assign('system_menu',$system_menu);
		$this->display();
	}
	public function keepLogin(){
		session_commit();
		session_id($_GET['session_id']);
		session_start();
		$_SESSION['last_api_time'] = $_SERVER['REQUEST_TIME'];
	}
	public function returnCode($code=0,$result=array()){
        if($code == 0){
            $array = array(
                'errorCode'=>0,
                'errorMsg'=>'success',
                'result'=>$result
            );
        }else{
            $array = array(
				'errorCode'=>$code,
				'errorMsg'=>$result
            );
        }
        echo json_encode($array);
        exit();
    }
}