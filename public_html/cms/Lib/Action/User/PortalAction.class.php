<?php
/**
* 门户板块
*/
class PortalAction extends BaseAction {

	// 首页
	public function tieba(){

		$where['target_id'] = 0;
        $where['status'] = 0;
        $where['uid'] = $this->user_session['uid'];
		$count = D('Portal_tieba')->where($where)->count();
        import('@.ORG.user_page');
        $p = new Page($count,10);
		$tiebaList = D('Portal_tieba')->where($where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		$pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->assign('count',$count);
		$this->assign('tiebaList',$tiebaList);

		$this->display();
	}

}