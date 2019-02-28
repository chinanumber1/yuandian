<?php
/*
 * 导航管理
 *
 * @  Writers    Go
 * @  BuildTime  2014/12/02 9:43
 * 
 */
class Wap_aroundAction extends BaseAction{
	public function alise_name(){
		return array(
				'group'=>$this->config['group_alias_name'],
				'shop'=>$this->config['shop_alias_name'],
				'meal'=>$this->config['meal_alias_name'],
				'appoint'=>$this->config['appoint_alias_name'],
				'merchant'=>'商家',
		);
	}
	public function index(){

		$database_wap = D('Wap_around');
		$list = $database_wap->field(true)->order('`sort` DESC,`id` ASC')->select();
		$this->assign('list',$list);
		$this->assign('alias_name',$this->alise_name());

		$this->display();
	}
	public function add(){
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}
	public function modify(){
		if($_FILES['pic']['error'] != 4){
			$image = D('Image')->handle($this->system_session['id'], 'wap');
			if (!$image['error']) {
				$_POST = array_merge($_POST, str_replace('/upload/wap/', '', $image['url']));
			} else {
				$this->frame_submit_tips(0, $image['msg']);
			}
		}
		$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
		$database_wap = D('Wap_around');
		if($id = $database_wap->data($_POST)->add()){
			D('Image')->update_table_id('/upload/wap/' . $_POST['pic'], $id, 'wap');
			$this->frame_submit_tips(1,'添加成功！');
		}else{
			$this->frame_submit_tips(0,'添加失败！请重试~');
		}
	}
	public function edit(){
		$this->assign('bg_color','#F3F3F3');
		
		$database_wap = D('Wap_around');
		$condition_wap['id'] = $_GET['id'];
		$wap_around = $database_wap->field(true)->where($condition_wap)->find();
		if(empty($wap_around)){
			$this->frame_error_tips('该导航不存在！');
		}
		$this->assign('wap_around',$wap_around);
		$this->assign('alias_name',$this->alise_name());
		$this->display();
	}
	
	public function amend(){
		$database_wap = D('Wap_around');
		$condition_wap['id'] = $_POST['id'];
		$now_wap = $database_wap->field(true)->where($condition_wap)->find();
		if($_FILES['pic']['error'] != 4){
			$image = D('Image')->handle($this->system_session['id'], 'wap');
			if (!$image['error']) {
				$_POST = array_merge($_POST, str_replace('/upload/wap/', '', $image['url']));
			} else {
				$this->frame_submit_tips(0, $image['msg']);
			}
		}
		$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
		if($database_wap->where(array('id'=>$_POST['id']))->data($_POST)->save()){
			D('Image')->update_table_id('/upload/wap/' . $_POST['pic'], $_POST['id'], 'wap');
			if($_POST['pic']){
				if(strpos($now_wap['pic'],'2014/') === false){
					unlink('./upload/wap/'.$now_wap['pic']); 
				}
			}
			$this->frame_submit_tips(1,'编辑成功！');
		}else{
			$this->frame_submit_tips(0,'编辑失败！请重试~');
		}
	}
	
	public function del(){
		$database_wap = D('Wap_around');
		$condition_wap['id'] = $_POST['id'];
		$now_wap = $database_wap->field(true)->where($condition_wap)->find();
		if($database_wap->where($condition_wap)->delete()){
			if($now_wap['pic']){
				if(strpos($now_wap['pic'],'2014/') === false){
					unlink('./upload/wap/'.$now_wap['pic']); 
				}
			}
			$this->success('删除成功');
		}else{
			$this->error('删除失败！请重试~');
		}
	}

	public function hide(){
		$hide = $_POST['hide'];
		M('Config')->where(array('name'=>'wap_around_show_type'))->setField('value',$hide);
	}

}