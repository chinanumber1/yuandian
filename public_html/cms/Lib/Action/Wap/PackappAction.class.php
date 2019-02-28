<?php

class PackappAction extends BaseAction{
	public function bind(){
		if($_GET['referer']){
			$referer = array_shift(explode('?',$_GET['referer']));
			redirect($referer.'?openid='.$_SESSION['openid']);
		}else{
			redirect(U('Home/index'));
		}
	}
}