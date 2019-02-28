<?php
class AppintroclassifyAction extends BaseAction{
	public function index(){
		$intro  = D('Classify_appintro')->select();
		$this->assign('intro',$intro);
		$this->display();
	}
	public function add(){
		if(IS_POST){
			$data['title'] = $_POST['title'];
			if(empty($_POST['content'])){
				$this->error('内容不能为空！');
			}
			$data['content'] = htmlspecialchars_decode($_POST['content']);
			$data['add_time'] = time();
			if(D('Classify_appintro')->add($data)){
				$this->success('添加公告成功！');
			}else{
				$this->error('添加失败！');
			}
		}else {
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data['title'] = $_POST['title'];
			$data['content'] = htmlspecialchars_decode($_POST['content']);
			$data['add_time'] = time();
			if(D('Classify_appintro')->where('id='.$_POST['id'])->save($data)){
				$this->success('保存成功！');
			}else{
				$this->error('保存失败！');
			}
		}else {
			$intro = D('Classify_appintro')->where('id='.$_GET['id'])->select();
			$this->assign("intro",$intro[0]);

			$this->display();
		}
	}
	public function del(){
		if(!empty($_POST['id'])){
			if(D('Classify_appintro')->where('id='.$_POST['id'])->delete()){
				$this->success('删除成功');
			}else{
				$this->error('删除失败！');
			}
		}
	}
}