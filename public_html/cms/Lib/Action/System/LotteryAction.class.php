<?php 
/*
 *2016年2月24日09:04:46
 *平台优惠券功能
 */
class LotteryAction extends BaseAction {
		
		public function index(){

			if (!empty($_GET['keyword'])) {
				if ($_GET['searchtype'] == 'id') {
					$condition['id'] = $_GET['keyword'];
				} else if ($_GET['searchtype'] == 'name') {
					$condition['name'] = array('like', '%' . $_GET['keyword'] . '%');
				}
			}
			//排序 /*/
			$order_string = '`id` DESC';
			$lottery = M('Lottery_shop');
			$count = $lottery->where($condition)->count();
			import('@.ORG.system_page');
			$p = new Page($count, 15);
			$lottery_list = $lottery->join('as l left join '.C('DB_PREFIX').'merchant m ON m.mer_id = l.mer_id')->field('l.*,m.name')->where($condition)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();
			//foreach ($lottery_list as &$v) {
			//	$v['content'] = unserialize($v['content']);
			//	$v['sys_content'] = unserialize($v['sys_content']);
			//}
			$this->assign('lottery_list',$lottery_list);
			$pagebar = $p->show();
			$this->assign('pagebar', $pagebar);
			$this->display();
		}

	public function edit(){
		if(IS_POST){
			$now_lottery = M('Lottery_shop')->where(array('id' => $_POST['id']))->find();
			foreach ($_POST['image_url'] as $k=>$v) {
				if(empty($_POST['title'][$k])){
					$this->error('抽奖标题不能为空');
				}
				if(!is_numeric($_POST['probability'][$k])||$_POST['probability'][$k]<0){
					$this->error('概率应设置为大于0的数字');
				}
				if(empty($v)){
					$_POST['type'][$k]==1 && $v=$this->config['site_url'].'/tpl/Wap/pure/static/images/lottery/cjt.png';
					$_POST['type'][$k]==0 && $v=$this->config['site_url'].'/tpl/Wap/pure/static/images/lottery/cj3.png';
				}
				if($_POST['type'][$k]==1){
					$arr[] = array('type'=>$_POST['type'][$k],'coupon_id'=>$_POST['coupon_id'][$k],'image_url'=>$v,'title'=>$_POST['title'][$k],'probability'=>$_POST['probability'][$k]);
				}else{
					$arr[] = array('type'=>$_POST['type'][$k],'image_url'=>$v,'title'=>$_POST['title'][$k],'probability'=>$_POST['probability'][$k]);
				}
			}
			$tmp['sys_content'] = serialize($arr);
			$tmp['status'] = $_POST['status'];

			if ($res = M('Lottery_shop')->where(array('mer_id' => $now_lottery['mer_id']))->save($tmp)) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');
			}
		}else {
			$lottery = D('Lottery_shop')->where(array('id'=>$_GET['id']))->find();
			$lottery['content'] = unserialize($lottery['content']);
			$lottery['sys_content'] = unserialize($lottery['sys_content']);
			foreach ($lottery['content'] as $v) {
				$lottery['probability_all']+=$v['probability'] ;
			}
			foreach ($lottery['sys_content'] as $vv) {
				$lottery['probability_all']+=$vv['probability'] ;
			}

			$this->assign("lottery",$lottery);
			$this->display();
		}
	}

	public function had_pull(){
		if (!empty($_GET['keyword'])) {
			if ($_GET['searchtype'] == 'id') {
				$condition['l.uid'] = $_GET['keyword'];
			} else if ($_GET['searchtype'] == 'phone') {
				$condition['u.phone'] = $_GET['keyword'];
			}
		}
		$condition['l.status']  = 1;
		$count =  M('Lottery_shop_list')->where(array('status'=>1))->count();
		import('@.ORG.system_page');
		$p = new Page($count, 15);
		$lottery_list = M('Lottery_shop_list')->join('as l left join '.C('DB_PREFIX').'user as u ON u.uid=l.uid ')->field('l.*,u.phone')->where($condition)->order('l.id DESC')->limit($p->firstRow,$p->listRows)->select();
		$this->assign('lottery_list',$lottery_list);
		$this->assign('pagebar',$p->show());
		$this->display();
	}
}
