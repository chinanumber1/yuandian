<?php
/*
 * 商户中心管理首页
 *
 */

class HelpAction extends Action{
	public function help()
	{
		$this->assign('answer_id', $_GET['answer_id']);
		$this->display();
	}
}