<?php

class AppRedpackAction extends BaseAction
{
	public function index()
	{
		import('@.ORG.system_page');

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$condition_user['_string'] =" (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}

		$count = M('Redpack_cycle')->where($condition_user)->count();

		$p = new Page($count,20);
		$redpack_list = M('Redpack_cycle')->where($condition_user)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();
		$this->assign('redpack_list',$redpack_list);
		$database_user = D('User');
		$user_balance	=	array(
				'count'	=>	$database_user->sum('now_money'),
				'open'	=>	$database_user->where(array('status'=>1))->sum('now_money'),
				'close'	=>	$database_user->where(array('status'=>0))->sum('now_money'),
				'score'	=>	$database_user->where(array('status'=>1))->sum('score_count'),
				'redpack_money'	=>	M('User_redpack_history')->sum('money'),
		);

		$this->assign('user_balance', $user_balance);
		$this->assign('pagebar',$p->show());
		$this->display();
	}

	public function setting()
	{
		$this->display();
	}

	public function hadpull_list()
	{
		import('@.ORG.system_page');

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$condition_user['_string'] =" (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}
		$condition_user['fid']  =$_GET['id'];
		$count = M('User_redpack_history')->where($condition_user)->count();

		$p = new Page($count,20);
		$redpack_list = M('User_redpack_history')->where($condition_user)->field('h.*,u.nickname')->join('as h LEFT JOIN '.C('DB_PREFIX').'user as u ON u.uid = h.uid')->where(array('fid'=>$_GET['id']))->order('id DESC')->limit($p->firstRow,$p->listRows)->select();
		$this->assign('redpack_list',$redpack_list);
		$this->assign('pagebar',$p->show());
		$this->display();
	}
}