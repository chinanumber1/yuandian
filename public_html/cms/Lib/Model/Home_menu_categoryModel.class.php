<?php
class Home_menu_categoryModel extends Model{
    protected $_auto = array(
	array('add_time' , 'time' , 3 , 'function'), 
    );
    public function home_menu_category_add($data){
	if(!$data){
	    return false;
	}
	
	if(!$this->create()){
	    return array('status'=>0,$this->getError());
	}else{
	    if($this->add()){
		return array('status' => 1,'msg'=>'添加成功！');
	    }else{
		return array('status'=>0 , 'msg'=>'添加失败！');
	    }
	}
    }
    
    public function home_menu_category_list($where,$field = true,$order='cat_id desc'){
	if(!$where){
	    return false;
	}
	
	$list = $this->where($where)->order($order)->field($field)->select();
	if($list){
	    return array('status'=>1,'list'=>$list);
	}else{
	    return array('status'=>0,'list'=>$list);
	}
    }
    
    
    public function home_menu_category_del($where){
	if(!$where){
	    return false;
	}
	
	$insert_id = $this->where($where)->delete();
	if($insert_id){
	    return array('status'=>1,'msg'=>'删除成功！');
	}else{
	    return array('status'=>0,'msg'=>'删除失败！');
	}
    }
    
    
    public function home_menu_category_info($where,$field = true,$order=''){
	if(!$where){
	    return false;
	}
	
	$info = $this->where($where)->field($field)->order($order)->find();
	if($info){
	    return array('status'=>1,'info'=>$info);
	}else{
	    return array('status'=>0,'info'=>$info);
	}
    }
    
    public function home_menu_category_edit($where,$data){
	if(!$where || !$data){
	    return false;
	}
	
	if(!$this->create()){
	    return array('status'=>0,'msg'=>$this->getError());
	}else{
	    if($this->where($where)->save()){
		return array('status'=>1,'msg'=>'修改成功！');
	    }  else {
		return array('status'=>0,'msg'=>'修改失败！');
	    }
	}
    }
}
?>