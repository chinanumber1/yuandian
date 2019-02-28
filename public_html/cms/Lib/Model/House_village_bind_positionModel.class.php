<?php

class House_village_bind_positionModel extends Model
{
	/**
	 * [get_parking_list 绑定住户]
	 * @return [type] [description]
	 */
   public function get_position_bind_list($position_bind_user_ids,$field='',$where=array(),$position_id){
	   	if (!$where['village_id']) {
            return array('code'=>2,'msg'=>'绑定住户失败!');
	   	}
   		$data = $this->field($field)->where($where)->select();//已绑定的用户ID
   		$model = D('House_village_user_bind');
	    if(!$data || $data ==''){//数据库不存在数据时
	        foreach ($position_bind_user_ids as $key => $value) {
	            $arr[$key]['user_id'] = $value;
	            $arr[$key]['position_id'] = $position_id;
	            $arr[$key]['village_id'] = $where['village_id'];
	        }
	    }else{
	        foreach ($position_bind_user_ids as $key => $value) {
	            foreach ($data as $k => $v) {
	                if($value == $v['user_id']){
	                	$msg = $model->field('phone,name')->where(array('pigcms_id'=>$value))->find();
	                    return array('code'=>2,'msg'=>'用户'.$msg['name'].'--'.$msg['phone'].'已绑定!');
	                }else{
	                    $arr[$key]['user_id'] = $value;
	                    $arr[$key]['position_id'] = $position_id;
	                    $arr[$key]['village_id'] = $where['village_id'];
	                }
	            }
	        }
	    }
        $result = $this->addAll($arr);

        if($result !== false){
        	$opsition_info = D('House_village_parking_position')->get_one(array('position_id'=>$position_id));
        	if ($opsition_info['position_status']==1) { //状态置为已使用
        		D('House_village_parking_position')->data(array('position_status'=>2))->where(array('position_id'=>$position_id))->save();
        	}
            return array('code'=>1,'msg'=>'绑定住户成功!');
        }else{
            return array('code'=>2,'msg'=>'绑定住户失败!');
        }
	  }


	  /**
	   * [get_position_bind_select 获取车位住户绑定关系]
	   * @return [type] [description]
	   */
	  public function get_position_bind_select($where=array()){
	  	$bind_list = $this->where($where)->select();//车位住户绑定关系
	  	if($bind_list){
	  		return $bind_list;
	  	}else{
	  		return false;
	  	}
	  }


	  /**
	   * [del_bind_position 解绑车位信息]
	   * @return [type] [description]
	   */
	  public function del_bind_position($where){

        $bind_info = $this->get_bind_position_one($where);
	  	$result = $this->where($where)->delete();//解除

   		$count = $this->where(array('position_id'=>$bind_info['position_id']))->count();//已绑定的用户数
   		if (!$count) { //状态置为空置
    		D('House_village_parking_position')->data(array('position_status'=>1))->where(array('position_id'=>$bind_info['position_id']))->save();
    	}
	  	return $result;
	  }

	  /**
	   * [get_bind_position_one 获取单个成为绑定信息]
	   * @return [type] [description]
	   */
	  public function get_bind_position_one($where){
	  	$result = $this->where($where)->find();
	  	return $result;
	  }

	  /**
	   * [get_bind_position_count 获取数量]
	   * @return [type] [description]
	   */
	  public function get_bind_position_count($field='',$where=array()){
	  	$count = $this->where($where)->count($field);//已绑定业主的车位
	  	return $count;
	  }

	 /**
	 *获得用户绑定车位信息
	 */
   	public function get_user_position_bind_list($where=array()){
   		//车位信息
        $condition_table  = array(C('DB_PREFIX').'house_village_bind_position'=>'b',C('DB_PREFIX').'house_village_parking_position'=>'p',C('DB_PREFIX').'house_village_parking_garage'=>'g');
        $condition_where = " `p`.`position_id` = `b`.`position_id` AND `g`.`garage_id` =`p`.`garage_id`";
        $condition_field = '`b`.`bind_id`,`p`.*,`g`.`garage_num`';
        if ($where['pigcms_id']) {
        	$condition_where .= " AND `b`.`user_id` =".$where['pigcms_id'];
        }
        if ($where['position_id']) {
        	$condition_where .= " AND `b`.`position_id` =".$where['position_id'];
        }

        $position_list = D()->field($condition_field)->table($condition_table)->where($condition_where)->select();
        $total_area = 0;
        if ($position_list) {
            foreach ($position_list as $key => $value) {
                $total_area += $value['position_area'];
            }
        }
        $list['list'] =  $position_list ? $position_list : array();
        $list['total_area'] =  $total_area;
       	return $list;
	}

	 /**
	 *获得车位绑定缴费项信息信息
	 */
   	public function get_user_position_payment_list($where=array()){
   		//车位信息
        $condition_table  = array(C('DB_PREFIX').'house_village_bind_position'=>'b',C('DB_PREFIX').'house_village_parking_position'=>'p',C('DB_PREFIX').'house_village_parking_garage'=>'g',C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb', C('DB_PREFIX').'house_village_payment_standard'=>'ps', C('DB_PREFIX').'house_village_payment'=>'pa');
        $condition_where = " `p`.`position_id` = `b`.`position_id` AND `g`.`garage_id` =`p`.`garage_id` AND pa.payment_id = psb.payment_id AND ps.standard_id = psb.standard_id AND psb.position_id=b.position_id";
        $condition_field = '`pa`.*,`ps`.*,`psb`.*,`p`.`position_num`,`g`.`garage_num`';
        if ($where['pigcms_id']) {
        	$condition_where .= " AND `b`.`user_id` =".$where['pigcms_id'];
        }
        if ($where['position_id']) {
        	$condition_where .= " AND `b`.`position_id` =".$where['position_id'];
        }

        $position_list = D()->field($condition_field)->table($condition_table)->where($condition_where)->select();
       	return $position_list ? $position_list : array();
	}
}

?>