<?php
class RedirectAction extends BaseAction{
    public function index(){
    	$url = htmlspecialchars_decode($_GET['url']);
		$param = 'openid='.$_SESSION['openid'].'&uid='.$_SESSION['user']['uid'];
		if(strpos($url,'?') !== false){
			redirect($url.'&'.$param);
		}else{
			redirect($url.'?'.$param);
		}
    }
	public function need_login(){
		$param['referer'] = urlencode($_GET['referer']);
		if($this->is_app_browser){
			$this->error_tips('请先进行登录！',U('Login/index',$param));
		}else{
			redirect(U('Login/index',$param));
		}
	}
}
?>