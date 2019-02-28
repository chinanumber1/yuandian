<?php
/*
 * 后台登录
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/05 15:28
 * 
 */

class LoginAction extends BaseAction
{
	
	
    public function index()
    {
		$this->display();
    }
    
    public function see_admin_qrcode()
    {
		$qrcode_return = D('Recognition')->get_admin_qrcode();
		if ($qrcode_return['error_code']) {
			echo '<html><head></head><body>'.$qrcode_return['msg'].'<br/><br/><font color="red">请关闭此窗口再打开重试。</font></body></html>';
		} else {
			$this->assign($qrcode_return);
			$this->display();
		}
    }
    
    public function send_code()
    {
    	$database_admin = D('Admin');
    	$account = $condition_admin['account'] = isset($_POST['account']) ? htmlspecialchars(trim($_POST['account'])) : '';
    	$condition_admin['status'] = 1;
    	$now_admin = $database_admin->field(true)->where($condition_admin)->find();
    	if (empty($now_admin)) exit(json_encode(array('errcode' => 1, 'errmsg' => '不存在的账号')));
    	
    	$access_token_array = D('Access_token_expires')->get_access_token();
    	if ($access_token_array['errcode']) {
    		exit(json_encode($access_token_array));
    	}
    	$access_token = $access_token_array['access_token'];
    	
    	$send_to_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
    	
    	
    	$code_array = $_SESSION[$account];
    	if ($code_array && $code_array['time'] + 600 > time()) {
    		$code = $code_array['code'];
    	} else {
    		$code = rand(100000, 999999);
    		$_SESSION[$account] = array('code' => $code, 'time' => time());
    	}
    	
    	import('ORG.Net.Http');
		$str = '{"touser":"' . $now_admin['openid'] . '","msgtype":"text","text":{"content": "' . $code . '"}}';
    	
		$rt = Http::curlPost($send_to_url, $str);
		if ($rt['errcode']) {
			$_SESSION[$account] = null;
			exit(json_encode($rt));
		} else {
			exit(json_encode($rt));
		}
    }
    
	public function check(){
		$verify = $this->_post('verify');
		if(md5($verify) != $_SESSION['admin_verify']){
			exit('-1');
		}
		
		$database_admin = D('Admin');
		$account = $condition_admin['account'] = $this->_post('account');
		$condition_admin['status'] = 1;
		
// 		$code = $this->_post('code');
// 		$code_array = $_SESSION[$account];
// 		if ($code_array && $code_array['time'] + 600 > time()) {
// 			if ($code_array['code'] != $code) {
// 				exit('-1');
// 			}
// 		} else {
// 			exit('-1');
// 		}
		
		
		$now_admin = $database_admin->field(true)->where($condition_admin)->find();
		if(empty($now_admin)){
			exit('-2');
		}
		$pwd = $this->_post('pwd','htmlspecialchars,md5');
		if($pwd != $now_admin['pwd']){
			exit('-3');
		}
		if($now_admin['status'] != 1){
			exit('-4');
		}
		$now_admin['show_account'] = '超级管理员';
		if ($now_admin['level'] == 1) {
			if ($now_admin['area_id']) {
				$area = D('Area')->field(true)->where(array('area_id' => $now_admin['area_id']))->find();
				$now_admin['show_account'] = $area['area_name'] . '管理员';
			}
		}else if($now_admin['level'] == 2) {
			$now_admin['show_account'] = '超级管理员';
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
				$now_admin['last']['country'] = mb_convert_encoding($last_location['country'],'UTF-8','GBK');
				$now_admin['last']['area'] = mb_convert_encoding($last_location['area'],'UTF-8','GBK');
			}
			session('system',$now_admin);
			exit('1');
		}else{
			exit('-5');
		}
	}
	
	public function ajax_scan_login()
	{
		$database_login_qrcode = D('Admin_qrcode');
		$condition_login_qrcode['id'] = $_GET['qrcode_id'];
		$now_qrcode = $database_login_qrcode->field('`aid`')->where($condition_login_qrcode)->find();
		if (!empty($now_qrcode['aid'])) {
			$database_login_qrcode->where($condition_login_qrcode)->delete();
			$database_admin = D('Admin');
			$now_admin = $database_admin->field(true)->where(array('id' => $now_qrcode['aid']))->find();
			if(empty($now_admin)){
				exit(json_encode(array('err_code' => -2)));
				exit('-2');
			}
			if($now_admin['status'] != 1){
				exit(json_encode(array('err_code' => -4)));
				exit('-4');
			}
			$now_admin['show_account'] = '超级管理员';
			if ($now_admin['level'] == 1) {
				if ($now_admin['area_id']) {
					$area = D('Area')->field(true)->where(array('area_id' => $now_admin['area_id']))->find();
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
				session('system',$now_admin);
				exit(json_encode(array('err_code' => 1)));
			} else {
				exit(json_encode(array('err_code' => -5)));
			}
		}
		exit(json_encode(array('err_code' => 0)));
	}
	public function logout(){
		session('system',null);
		header('Location: '.U('Login/index'));
	}
	public function verify(){
		import('ORG.Util.Image');
		Image::buildImageVerify(4,1,'jpeg',53,26,'admin_verify');
	}
}