<?php

class House_village_parking_carModel extends Model
{
  /**
   * [get_parking_car_list 获取绑定车辆数量]
   * @return [type] [description]
   */
	public function get_parking_car_list($field='',$where=array(),$join='',$order='',$pageSize=20){
      $count = D('House_village_parking_car')->alias('a')->where($where)->join($join)->count();
      $p = new Page($count,$pageSize,'page');
      $info_list['info_list'] = D('House_village_parking_car')
                              ->alias('a')
                              ->field($field)
                              ->where($where)
                              ->join($join)
                              ->order($order)
                              ->limit($p->firstRow.','.$p->listRows)
                              ->select();
      $info_list['pagebar'] = $p->show();
      if($info_list){
        return $info_list;
      }else{
        return false;
      }
  }

  /**
   * [get_parking_car_one 获取单个车辆信息]
   * @return [type] [description]
   */
  public function get_parking_car_one($where=array()){
     $result = D('House_village_parking_car')->where($where)->find();
     if($result){
      return $result;
     }else{
      return false;
     }
  }

  /**
   * [parking_car_add 添加单个信息]
   * @return [type] [description]
   */
  public function parking_car_add($data=array()){
      $result = D('House_village_parking_car')->data($data)->add();
      return $result;
  }
	
  /**
   * [parking_car_save 修改车辆信息]
   * @return [type] [description]
   */
  public function parking_car_save($data){
      $result = D('House_village_parking_car')->save($data);
      if($result !== false){
          return $result;
      }else{
          return false;
      }
  }

  /**
   * [get_parking_car_info 联表查询]
   * @return [type] [description]
   */
  public function get_parking_car_info($field='',$where=array(),$join=''){
      $info_list = D('House_village_parking_car')->alias('a')->field($field)->where($where)->join($join)->find();
      if($info_list !== false){
        return $info_list;
      }else{
        return false;
      }
  }

  /**
   * [parking_car_del 删除车辆信息]
   * @return [type] [description]
   */
  public function parking_car_del($where){
    $result = D('House_village_parking_car')->where($where)->delete();
    if($result !== false){
        return $result;
    }else{
        return false;
    }
  }

}

?>