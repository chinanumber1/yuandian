<?php
class House_village_floorModel extends Model{
    public function house_village_floor_add($data){
        if(!$data){
            return false;
        }
        
        if(!$data['floor_name']){
            return array('status'=>0,'msg'=>'单元名称不能为空！');
        }
        
        if(!$data['floor_layer']){
            return array('status'=>0,'msg'=>'单元楼号不能为空！');
        }

        if(!$data['floor_type']){
            return array('status'=>0,'msg'=>'单元类型不能为空！');
        }

        if(!$data['property_month_num']){
            $data['property_month_num'] = $data['diy_property_month_num'];
            unset($data['diy_property_month_num']);
        }

        if(!$data['presented_property_month_num']){
            $data['presented_property_month_num'] = $data['diy_presented_property_month_num'];
            unset($data['diy_presented_property_month_num']);
        }
        
        $data['add_time'] = time();
        $data['village_id'] = $_SESSION['house']['village_id'];

        $insert_id = $this->data($data)->add();
        if($insert_id){
            return array('status'=>1,'msg'=>'添加成功！');
        }else{
            return array('status'=>0,'msg'=>'添加失败！');
        }
    }
    
    public function house_village_floor_page_list($where,$field=true,$order='floor_id desc',$pageSize=20){
        if(!$where){
            return false;
        }
        
        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');
        
        $village_floor_list = $this->where($where)->field($field)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
        $list['list'] = $village_floor_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'list'=>$list);
        }else{
            return array('status'=>0,'list'=>$list);
        }
    }
    
    public function house_village_floor_del($where){
        if(!$where){
            return false;
        }

        $database_house_village_user_bind = D('House_village_user_bind');
        $bind_sum  = $database_house_village_user_bind->where($where)->count();
        if($bind_sum > 0){
            return array('status'=>0,'msg'=>'请先删除单元下所有业主！');
        }

        $insert_id = $this->where($where)->delete();
        
        if($insert_id){
            return array('status'=>1,'msg'=>'删除数据成功！');
        }else{
            return array('status'=>0,'msg'=>'删除数据失败！');
        }
    }
    
    
    public function house_village_floor_detail($where,$field=true){
        if(!$where){
            return false;
        }
        
        $detail = $this->where($where)->field($field)->find();
        if($detail){
            return array('status'=>1,'detail'=>$detail);
        }else{
            return array('status'=>0,'detail'=>$detail);
        }
    }
    
    public function house_village_floor_edit($where,$data){
        if(!$where || !$data){
            return false;
        }

        if(!$data['floor_name']){
            return array('status'=>0,'msg'=>'单元名称不能为空！');
        }

        if(!$data['floor_layer']){
            return array('status'=>0,'msg'=>'单元楼号不能为空！');
        }

        if(!$data['floor_type']){
            return array('status'=>0,'msg'=>'单元类型不能为空！');
        }

        if(!$data['property_month_num']){
            $data['property_month_num'] = $data['diy_property_month_num'];
            unset($data['diy_property_month_num']);
        }

        if(!$data['presented_property_month_num']){
            $data['presented_property_month_num'] = $data['diy_presented_property_month_num'];
            unset($data['diy_presented_property_month_num']);
        }
        
        $data['add_time'] = time();
        
        $insert_id = $this->where($where)->data($data)->save();
        
        if($insert_id){
            return array('status'=>1,'msg'=>'修改成功！');
        }else{
            return array('status'=>0,'msg'=>'修改失败！');
        }
    }


    public function get_floor_info($floor_id){
        if(!$floor_id){
            return false;
        }

        $condition_table = array(C('DB_PREFIX').'house_village_floor'=>'`hvf`',C('DB_PREFIX').'house_village_floor_type'=>'`hvft`');
        $condition_where = '`hvft`.`status`=1 AND `hvf`.`status`=1 AND `hvf`.`floor_type` = `hvft`.`id` AND `hvf`.`floor_id` = '.$floor_id;
        $condition_field = array('`hvf`.*,`hvft`.`name`');
        $result = D('')->where($condition_where)->table($condition_table)->field($condition_field)->find();
        return $result;
    }
	
	
	//查询小区单元 - wangdong
	public function get_unit_list($village_id){
		
		$condition['village_id'] = $village_id;
		
		//$condition['status'] = 1;
		
		$unit_list = $this->where($condition)->order('floor_id desc')->select();
		
		return $unit_list;
			
	}
	
	//查询单元信息
	public function get_unit_find($floor_id , $field=""){
		
		if(empty($field)) $field=true;
		
		$condition['floor_id'] = $floor_id;
		
		$info = $this->field($field)->where($condition)->find();
		
		return $info;
			
	}
	
}

