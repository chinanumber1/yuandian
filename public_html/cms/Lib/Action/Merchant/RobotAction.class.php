<?php
/*
 * 机器人
 *
 * @  Writers    Goi
 * @  BuildTime  2017-2-7 11:06:17
 *
 */

class RobotAction extends BaseAction{
	public function index(){
		import('@.ORG.merchant_page');
		$count = M('Robot_list')->where(array('mer_id'=>$this->merchant_session['mer_id']))->count();
		$p = new Page($count, 20);
		$robot_list = M('Robot_list')->where(array('mer_id'=>$this->merchant_session['mer_id']))->limit($p->firstRow,$p->listRows)->select();
		$pagebar = $p->show();
		$this->assign('robot_list',$robot_list);
		$this->assign('pagebar',$pagebar);
		$this->display();
	}

	public function add_robot(){
		if(IS_POST){
			if($_POST['robot_name']&&$_POST['avatar']){
				$_POST['mer_id'] = $this->merchant_session['mer_id'];
				$_POST['add_time'] = time();
				if(M('Robot_list')->add($_POST)){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}
			}else{
				$this->error('数据不全');
			}
		}else{
			$this->display();
		}
	}

	public function ajax_get_user_name(){
		import('@.ORG.randName');
		$name = new randName();
		$rand_name = $name->getName();
		echo json_encode(array('name'=>$rand_name));exit;
	}

	public function del(){
		if(M('Robot_list')->where(array('id'=>$_GET['id']))->delete()){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

}