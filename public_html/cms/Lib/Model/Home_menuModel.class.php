<?php
class Home_menuModel extends Model{
    protected $_validate = array(
	array('name' , 'require' , '名称必须存在！'),
	array('url' , 'require' , '链接不能为空！'),
	array('sort' , 'require' , '排序必须存在！'),
    );
    
    protected $_auto = array(
	array('add_time','time',3,'function'),
    );

	public function getMenuList($cat_key){
		$menu_category = M('Home_menu_category')->field('cat_id')->where(array('cat_key'=>$cat_key))->find();
		if($menu_category){
			$footer_menu_list = $this->where(array('status'=>'1','cat_id'=>$menu_category['cat_id']))->order('`sort` DESC,`id` ASC')->select();
			return $footer_menu_list;
		}
	}
	
    public function home_menu_add($data){
	if(!$data){
	    return false;
	}
	if(!$this->create()){
	    return array('status'=>0,'msg'=>$this->getError());
	}else{
	    if($this->add()){
		return array('status' => 1,'msg' => '添加成功！');
	    }else{
		return array('status' => 0,'msg' => '添加失败！请重试~');
	    }
	}
    }
    
    public function home_menu_list($where , $field = true , $order = '`sort` DESC,`id` ASC',$cat_id = 0){
	if(!$where){
	    return false;
	}
	
	if($cat_id){
	   $where['cat_id'] = $cat_id;
	}
	$list = $this->where($where)->field($field)->order($order)->select();
	if($list){
	    	return array('status'=>1,'list'=>$list);
	}else{
	    return array('status'=>0,'list'=>$list);
	}
    }
    
    public function home_menu_info($where,$field=true,$order='id desc'){
	if(!$where){
	    return false;
	}
	
	$info = $this->where($where)->field($field)->order($order)->find();
	if(!$info){
	    return array('status'=>0,'info'=>$info);
	}else{
	    return array('status'=>1,'info'=>$info);
	}
    }
    
    
    public function home_menu_edit($where,$data){
	if(!$where || !$data){
	    return false;
	}
	
	if(!$this->create()){
	    return array('status'=>0,'msg'=>$this->getError());
	}else{
	    if($this->where($where)->data($data)->save()){
		return array('status' => 1,'msg' => '修改成功！');
	    }else{
		return array('status' => 0,'msg' => '修改失败！');
	    }
	}
    }
    
    public function home_menu_del($where){
	if(!$where){
	    return false;
	}

	$info = $this->where($where)->home_menu_info($where);
	if(!$info['status']){
	    return array('status'=>0,'msg'=>'数据不存在！');
	}
	
	$info = $info['info'];
	if($info['pic_path']){
	    unlink('./upload/slider/'.$info['pic_path']); 
	}
	if($info['hover_pic_path']){
	    unlink('./upload/slider/'.$info['hover_pic_path']); 
	}
	    
	$insert_id = $this->where($where)->delete();
	if($insert_id){
	    return array('status'=>1,'msg'=>'删除成功！');
	}else{
	    return array('status'=>0,'msg'=>'删除失败！请重试~');
	}
    }
}
?>