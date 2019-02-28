<?php
class ActivityAction extends BaseAction{
	public function index(){
		$action = '';
		
		$now_time = time();
		
		import('@.ORG.wap_group_page');
		$count = D('Lettory')->where("`statdate`<'{$now_time}' AND `enddate`>'{$now_time}'")->count();
		$p = new Page($count, C('config.group_page_row'),C('config.group_page_val'));
		
		
		$lotterys = D('')->field('`m`.`name`,`l`.*')->table(array(C('DB_PREFIX').'lottery'=>'l',C('DB_PREFIX').'merchant'=>'m'))->where("`l`.`mer_id`=`m`.`mer_id` AND `l`.`statdate`<'{$now_time}' AND `l`.`enddate`>'{$now_time}' AND `m`.`status`='1'")->order('l.id DESC')->limit($p->firstRow.','.$p->listRows)->select();
		foreach ($lotterys as &$lottery) {
			switch ($lottery['type']) {
				case 1:
					$action = 'Lottery';
					break;
				case 2:
					$action = 'Guajiang';
					break;
				case 3:
					$action = 'Coupon';
					break;
				case 4:
					$action = 'LuckyFruit';
					break;
				case 5:
					$action = 'GoldenEgg';
					break;
			}
			$lottery['url'] = U($action . '/index', array('token' => $lottery['token'], 'id' => $lottery['id']));
		}
		$this->assign('pagebar', $p->show());
		$this->assign('lottery_list', $lotterys);
		$this->assign('title', '热门活动');
		$this->display();
	}
	
	public function wxapp()
	{
		import('@.ORG.wap_group_page');
		$wxappObj = D('Wxapp_list');
		$count = $wxappObj->where("`status`=1")->count();
		$p = new Page($count, C('config.group_page_row'),C('config.group_page_val'));
		$list = $wxappObj->field(true)->where("`status`=1")->limit($p->firstRow.','.$p->listRows)->select();
		foreach ($list as &$l) {
			$l['url'] = $this->config['site_url'] . "/wap.php?c=Wxapp&a=location_href&id=" . $l['pigcms_id'];
		}
		$this->assign('pagebar', $p->show());
		$this->assign('list', $list);
		$this->assign('title', '营销活动');
		$this->display();
		
	}
}
?>