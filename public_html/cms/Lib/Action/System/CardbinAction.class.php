<?php
/*
 * 银行卡BIN列表
 *
 * @  Writers    Jaty
 * @  BuildTime  2018/04/10 13:28
 * 
 */
class CardbinAction extends BaseAction{
	public function index(){
		$bank_list = D('Banklist')->field(true)->order('`id` ASC')->select();
		$this->assign('bank_list',$bank_list);
		
		$this->display();
	}
	public function add(){
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}
	public function modify(){
		if(IS_POST){
			$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
			if(D('Banklist')->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function edit(){
		$this->assign('bg_color','#F3F3F3');
		$condition['id'] = intval($_GET['id']);
		$nowBank = D('Banklist')->field(true)->where($condition)->find();
		$this->assign('nowBank',$nowBank);
		$this->display();
	}
	public function amend(){
		if(IS_POST){
			$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
			if(D('Banklist')->data($_POST)->save()){
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function del(){
		if(IS_POST){
			$condition['id'] = intval($_POST['id']);
			if(D('Banklist')->where($condition)->delete()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
}