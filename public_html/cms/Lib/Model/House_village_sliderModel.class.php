<?php
class House_village_sliderModel extends Model{
      protected $_validate = array(
	    array('name','require','幻灯片名称不能为空！',1,'regex'), 
	    array('pic','require','幻灯片图片不能为空！',1,'regex'), 
	    array('url','require','链接地址不能为空！',1,'regex'), 
	    array('village_id','require','社区ID不能为空',1,'regex'),
	    array('sort','number','请填写数字',1),
	    array('sort',array(1,10),'请填写1-10之间的值',1,'between'),
      );
      
       protected $_auto = array ( 
	  array('add_time','time',1,'function'),
          array('url','url_decode',3,'callback'),
      );
       
      protected function url_decode(){
          return htmlspecialchars_decode($_POST['url']);
      }


      public function shequ_slider_add($data){
	if(!$this->create()){
	    return array('status' => 0,'msg' => $this->getError());
	}else{
	    if($this->add()){
		return array('status' => 1,'msg' => '添加成功！');
	    }else{
		return array('status' => 0,'msg' => '添加失败！');
	    }
	}
    }
    
    
    public function shequ_slider_list($where , $field = true , $order = 'id desc' , $pageSize = 20){
	if(!$where){
	    return false;
	}
	
	$return = array();
	$count = $this->where($where)->count();
	import('@.ORG.merchant_page');
	$p = new Page($count,$pageSize,'page');
	$list = $this->field($field)->where($where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
	if($list){
		$return['totalPage'] = ceil($count/$pageSize);
		$return['count'] = count($list);
		$return['pagebar'] = $p->show();
		$return['list'] = $list;
	}
	return $return;
    }
    
    
    public function shequ_slider_del($where){
	if(!$where){
	    return false;
	}
	
	$insert_id = $this->where($where)->delete();
	if($insert_id){
	    return true;
	}else{
	    return false;
	}
    }
    
    
    public function shequ_slider_edit($where,$data){
	if(!$where || !$data){
	    return array('status' => 0,'msg' => '传递参数有误！');
	}
	
	if(!$this->create()){
	    return array('status' => 0,'msg' => $this->getError());
	}else{
	   if($this->where($where)->data($data)->save()){
		return array('status' => 1,'msg' => '修改成功！');
	    }else{
		return array('status' => 0,'msg' => '修改失败！');
	    }
	}
    }
}
?>