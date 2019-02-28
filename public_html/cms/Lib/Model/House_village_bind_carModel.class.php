<?php

class House_village_bind_carModel extends Model
{
	/**
	 * [get_bind_car_select 查询多个绑定车辆用户]
	 * @return [type] [description]
	 */
	public function get_bind_car_select($field='',$where=array()){
		$result = D('House_village_bind_car')->field($field)->where($where)->select();//已绑定的用户ID
		if($result !== false){
			return $result;
		}else{
			return false;
		}
	}

	/**
	 * [bind_car_addAll 批量添加绑定车辆用户]
	 * @return [type] [description]
	 */
	public function bind_car_addAll($data){
		$result = D('House_village_bind_car')->addAll($data);
		if($result !== false){
			return $result;
		}else{
			return false;
		}
	}


	/**
	 * [del_bind_car 删除绑定信息]
	 * @return [type] [description]
	 */
	public function del_bind_car($where){
		$result = D('House_village_bind_car')->where($where)->delete();
		if($result !==false){
			return $result;
		}else{
			return false;
		}
	}

	/**
	 * [get_bind_car_one 获取单个绑定车辆信息]
	 * @return [type] [description]
	 */
	public function get_bind_car_one($where){
		$result = D('House_village_bind_car')->where($where)->find();
		if($result !==false){
			return $result;
		}else{
			return false;
		} 
	}


	 /**
	 *获得用户绑定车辆信息
	 */
   	public function get_user_car_bind_list($where=array()){
   		//车位信息
        $condition_table  = array(C('DB_PREFIX').'house_village_bind_car'=>'b',C('DB_PREFIX').'house_village_parking_car'=>'c');
        $condition_where = "`b`.`car_id` =`c`.`car_id`";
        $condition_field = '`b`.`id`,`c`.*,`p`.`position_num`';


        $join=' left join '.C('DB_PREFIX').'house_village_parking_position as p on c.car_position_id=p.position_id';
        if ($where['pigcms_id']) {
        	$condition_where .= " AND `b`.`user_id` =".$where['pigcms_id'];
        }
        if ($where['car_id']) {
        	$condition_where .= " AND `b`.`car_id` =".$where['car_id'];
        }

        $car_list = D()->field($condition_field)->table($condition_table)->where($condition_where)->join($join)->select();

       	foreach ($car_list as &$value) {
            $value['position_num'] = $value['position_num'] ? $value['position_num'] : '';
       	}
        
        $car_list =  $car_list ? $car_list : array();
       	return $car_list;
	}
}

?>