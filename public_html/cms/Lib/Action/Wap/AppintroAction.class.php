<?php
class AppintroAction extends BaseAction{
	public function  intro(){
		$intro = D('Appintro')->where('id='.$_GET['id'])->find();
		$this->assign('intro',$intro);
		$this->display();
	}
}