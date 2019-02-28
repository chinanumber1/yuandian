<?php
class GroupwxappAction extends BaseAction{
	// 本地优选分类
    public function index(){
		$database_group_category = D('Group_wxapp_category');
		$category_list = $database_group_category->field(true)->order('`cat_sort` DESC,`cat_id` ASC')->select();
		$this->assign('category_list',$category_list);
		
        $this->display();
    }
	public function cat_add(){
		$this->display();
	}
	public function cat_modify(){
		if(IS_POST){
			$database_group_category = D('Group_wxapp_category');
			if($database_group_category->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function cat_edit(){
		$this->assign('bg_color','#F3F3F3');

		$database_group_category = M('Group_wxapp_category');
		$condition_now_group_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		$this->assign('now_category',$now_category);
		$this->display();
	}
	public function cat_amend(){
		if(IS_POST){
			$database_group_category = M('Group_wxapp_category');
			if($database_group_category->data($_POST)->save()){
				$this->frame_submit_tips(1,'编辑成功！');
			}else{
				$this->frame_submit_tips(0,'编辑失败！请重试~');
			}
		}else{
			$this->frame_submit_tips(0,'非法提交,请重新提交~');
		}
	}
	public function cat_del(){
		if(IS_POST){
			$database_group_category = M('Group_wxapp_category');
			$condition_now_group_category['cat_id'] = intval($_POST['cat_id']);
			if($database_group_category->where($condition_now_group_category)->delete()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	
	public function product_add(){
		$this->display();
	}
	public function product_modify(){
		$image = D('Image')->handle($this->system_session['id'], 'groupwxapp', 0, array('size' => 10), false);
		if (!$image['error']) {
			$_POST = array_merge($_POST, str_replace('/upload/groupwxapp/', '', $image['url']));
		} else {
			$this->frame_submit_tips(0, $image['message']);
		}
		$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
		$database_group_wxapp_product = M('Group_wxapp_product');
		if($id = $database_group_wxapp_product->data($_POST)->add()){
			D('Image')->update_table_id('/upload/groupwxapp/' . $_POST['product_pic'], $id, 'groupwxapp');
			
			$this->frame_submit_tips(1,'添加成功！');
		}else{
			$this->frame_submit_tips(0,'添加失败！请重试~');
		}
	}
	public function product_edit(){
		$database_group_wxapp_product = M('Group_wxapp_product');
		$condition_group_wxapp_product['group_id'] = intval($_GET['group_id']);
		$now_group = $database_group_wxapp_product->field(true)->where($condition_group_wxapp_product)->find();
		if(empty($now_group)){
			$this->frame_error_tips('该优选不存在！');
		}
		$this->assign('now_group',$now_group);

		$this->display();
	}
	public function product_amend(){
		$database_group_wxapp_product = M('Group_wxapp_product');
		$condition_group_wxapp_product['group_id'] = intval($_GET['group_id']);
		$now_group = $database_group_wxapp_product->field(true)->where($condition_group_wxapp_product)->find();
		
		if($_FILES['product_pic']['error'] != 4){
			$image = D('Image')->handle($this->system_session['id'], 'groupwxapp', 0, array('size' => 10), false);
			if (!$image['error']) {
				$_POST = array_merge($_POST, str_replace('/upload/groupwxapp/', '', $image['url']));
			} else {
				$this->frame_submit_tips(0, $image['message']);
			}
		}
		
		$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
		$database_group_wxapp_product = M('Group_wxapp_product');
		if($id = $database_group_wxapp_product->data($_POST)->save()){
			D('Image')->update_table_id('/upload/groupwxapp/' . $_POST['product_pic'], $id, 'groupwxapp');
			
			$this->frame_submit_tips(1,'编辑成功！');
		}else{
			$this->frame_submit_tips(0,'编辑失败！请重试~');
		}
	}
	public function product_del(){
		if(IS_POST){
			$database_group_wxapp_product = M('Group_wxapp_product');
			$condition_group_wxapp_product['group_id'] = intval($_POST['group_id']);
			if($database_group_wxapp_product->where($condition_group_wxapp_product)->delete()){
				$this->success('删除成功！');
			}else{                              
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function ajax_group_detail(){
		if((!$_POST['edit_group_id'] || $_POST['edit_group_id'] != $_POST['group_id']) && M('Group_wxapp_product')->where(array('group_id'=>$_POST['group_id']))->find()){
			$this->error('当前'.$this->config['group_alias_name'].'已被添加！');
		}
		$now_group = D('Group')->get_group_by_groupId($_POST['group_id'],'hits-setInc');
		if(empty($now_group)){
			$this->error('当前'.$this->config['group_alias_name'].'不存在！');
		}
		if($now_group['end_time']<$_SERVER['REQUEST_TIME']){
			$this->error('当前'.$this->config['group_alias_name'].'已结束！');
		}
		if($now_group['trade_info']){
			$this->error('暂时不支持带有插件的'.$this->config['group_alias_name']);
		}
		$now_group['begin_time_txt'] = date('Y-m-d H:i:s',$now_group['begin_time']);
		$now_group['end_time_txt'] = date('Y-m-d H:i:s',$now_group['end_time']);
		$now_group['deadline_time_txt'] = date('Y-m-d H:i:s',$now_group['deadline_time']);
		$this->success($now_group);
	}
}