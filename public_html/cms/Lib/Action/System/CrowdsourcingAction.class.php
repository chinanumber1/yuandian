<?php
/*
 * 众包管理
 *   Writers    hanlu
 *   BuildTime  2016/10/10 13:22
 */
class CrowdsourcingAction extends BaseAction{
	# 众包车型
	public function index(){
		$list	=	M('Crowdsourcing_category')->field(true)->order('category_sort DESC')->select();
		$this->assign('list', $list);
		$this->display();
	}
	# 添加车型
	public function add(){
		if(IS_POST){
			$image = D('Image')->handle($this->system_session['id'], 'crowdsourcing', 0, array('size' => 10), false);
    		if (!$image['error']) {
				$_POST = array_merge($_POST,$image['url']);
			} else {
				$this->frame_submit_tips(0,$image['msg']);
			}
			$add	=	M('Crowdsourcing_category')->data($_POST)->add();
			if($add){
				$this->frame_submit_tips(1,'添加成功！');
			}else{
				$this->frame_submit_tips(0,'添加失败！请重试~');
			}
		}else{
			$this->display();
		}
	}
	# 修改车型
	public function edit(){
		if(IS_POST){
			if($_FILES['category_img']['error'] === 0){
				$image = D('Image')->handle($this->system_session['id'], 'crowdsourcing', 0, array('size' => 10), false);
    			if (!$image['error']) {
					$_POST = array_merge($_POST,$image['url']);
				} else {
					$this->frame_submit_tips(0,$image['msg']);
				}
			}
			$category_id	=	$_POST['category_id'];
			unset($_POST['category_id']);
			$add	=	M('Crowdsourcing_category')->where(array('category_id'=>$category_id))->data($_POST)->save();
			if($add){
				$this->frame_submit_tips(1,'修改成功！');
			}else{
				$this->frame_submit_tips(0,'修改失败！请重试~');
			}
		}else{
			$category_id	=	$_GET['category_id'];
			$find	=	M('Crowdsourcing_category')->where(array('category_id'=>$category_id))->find();
			$this->assign('find', $find);
			$this->display();
		}
	}
	# 删除车型
	public function del(){
		$del	=	M('Crowdsourcing_category')->where(array('category_id'=>$_POST['category_id']))->delete();
    	if($del){
			$this->success('删除成功！');
    	}else{
			$this->error('删除失败！请重试~');
    	}
	}
	# 众包列表
	public function crow_list(){
		$count_user = M('Crowdsourcing')->count();
		import('@.ORG.system_page');
        $p = new Page($count_user, 15);
		$list	=	M('Crowdsourcing')->field(true)->limit($p->firstRow . ',' . $p->listRows)->order('add_tims DESC')->select();
		if($list){
			foreach($list as &$v){
				$v['order_id']	=	M('Crowdsourcing_order')->where(array('package_id'=>$v['package_id']))->getField('order_id');
			}
		}
		$this->assign('list', $list);
		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);
		$this->display();
	}
	# 查看众包
	public function crow_show(){
		$package_id = $_GET['package_id'];
		$list	=	M('Crowdsourcing')->field(true)->where(array('package_id'=>$package_id))->find();
		if($list){
			$list['category_name']	=	M('Crowdsourcing_category')->where(array('category_id'=>$list['car_type']))->getField('category_name');
		}
		$this->assign('list', $list);
		$this->display();
	}
	# 查看订单
	public function crow_order(){
		$package_id = $_GET['package_id'];
		$count_user = M('Crowdsourcing_order')->where(array('package_id'=>$package_id))->count();
		import('@.ORG.system_page');
        $p = new Page($count_user, 15);
		$list	=	M('Crowdsourcing_order')->field(true)->where(array('package_id'=>$package_id))->limit($p->firstRow . ',' . $p->listRows)->order('order_id DESC')->select();
		$this->assign('list', $list);
		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);
		$this->display();
	}
}