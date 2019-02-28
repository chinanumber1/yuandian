<?php
class Lottery_shopAction extends BaseAction{

	public function index(){
		if(IS_POST) {
			$tmp['mer_id'] =  $this->merchant_session['mer_id'];
			$tmp['detail_msg'] = $_POST['detail_msg'];
			$tmp['lottery_msg'] = $_POST['lottery_msg'];
			$tmp['lottery_rule'] = $_POST['lottery_rule'];
			$tmp['add_time'] =time();
			$flag = false;

			foreach ($_POST['image_url'] as $k=>$v) {
				if(empty($_POST['title'][$k])){
					$this->error('抽奖标题不能为空');
				}
				if(!is_numeric($_POST['probability'][$k])||$_POST['probability'][$k]<0){
					$this->error('概率应设置为大于0的数字');
				}
				if($_POST['probability'][$k]!=0){
					$flag = true;
				}
				if($_POST['type'][$k]==0){
					$arr[] = array('is_win'=>$_POST['is_win'][$k],'type'=>$_POST['type'][$k],'coupon_id'=>$_POST['coupon_id'][$k],'image_url'=>$v,'title'=>$_POST['title'][$k],'probability'=>$_POST['probability'][$k]);
				}else{
					$arr[] = array('is_win'=>$_POST['is_win'][$k],'type'=>$_POST['type'][$k],'image_url'=>$v,'title'=>$_POST['title'][$k],'probability'=>$_POST['probability'][$k]);
				}

			}
			if(!$flag){
				$this->error('概率不能全部设置为0');
			}
			$tmp['content'] = serialize($arr);
			if (M('Lottery_shop')->where(array('mer_id' => $this->merchant_session['mer_id']))->find()) {
				$res = M('Lottery_shop')->where(array('mer_id' => $this->merchant_session['mer_id']))->save($tmp);
			} else {
				$res = M('Lottery_shop')->add($tmp);
			}

			if ($res) {
				$this->success('操作成功');
			} else {
				$this->error('操作失败');

			}

		}else{
			$lottery  = M('Lottery_shop')->where(array('mer_id' => $this->merchant_session['mer_id']))->find();
			$lottery['content'] = unserialize($lottery['content']);
			$lottery['sys_content'] = unserialize($lottery['sys_content']);
			foreach ($lottery['content'] as $v) {
				$lottery['probability_all']+=$v['probability'] ;
			}

			foreach ($lottery['sys_content'] as $vv) {
				$lottery['probability_all']+=$vv['probability'] ;
			}

			$this->assign('lottery',$lottery);
			$this->display();
		}


	}

	public function had_pull(){
		//if (!empty($_GET['keyword'])) {
		//	if ($_GET['searchtype'] == 'id') {
		//		$condition['l.uid'] = $_GET['keyword'];
		//	} else if ($_GET['searchtype'] == 'phone') {
		//		$condition['u.uid'] = $_GET['keyword'];
		//	}
		//}
		$condition['l.is_win']  = 1;
		$condition['l.mer_id']  = $this->merchant_session['mer_id'];
		$count =  M('Lottery_shop_list')->where(array('is_win'=>1,'mer_id'=>$this->merchant_session['mer_id']))->count();

		import('@.ORG.merchant_page');
		$p = new Page($count, 15);
		$lottery_list = M('Lottery_shop_list')->join('as l left join '.C('DB_PREFIX').'user as u ON u.uid=l.uid ')->field('l.*,u.phone')->where($condition)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();
		$this->assign('lottery_list',$lottery_list);

		$this->assign('pagebar',$p->show());
		$this->display();
	}

}


?>