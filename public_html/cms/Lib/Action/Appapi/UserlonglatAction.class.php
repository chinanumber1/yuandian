<?php
//保存用户的地理位置
class UserlonglatAction extends BaseAction{
	public function report(){
		if($this->_uid){
			if($_POST['type'] == 'gcj02'){
				$_POST['type'] = '3';
			}else if($_POST['type'] == 'wgs84'){
				$_POST['type'] = '1';
			}else if($_POST['type'] == 'gps'){
				$_POST['type'] = '1';
			}else if($_POST['type'] == 'baidu'){
				$_POST['type'] = '5';
			}
			$now_user = D('User')->get_user($this->_uid);
			if($now_user['openid'] && $_POST['lng'] && $_POST['lat'] && $_POST['type']){
				D('User_long_lat')->saveLocation($now_user['openid'],$_POST['lng'],$_POST['lat'],$_POST['type']);
			}
		}
		$this->returnCodeOk('ok');
	}
}
?>