<?php

class House_village_parking_positionModel extends Model
{
	/**
	 * [get_parking_list 获取车位信息]
	 * @return [type] [description]
	 */
   public function get_parking_list($where=array(),$pageSize = 20){
   		  import('@.ORG.merchant_page');
        // $count = $this->where($where)->count();
        // $p = new Page($count,$pageSize,'page');
        // $info_list = $this->where($where)->order('position_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
        $return = array();
        $condition_table  = array(C('DB_PREFIX').'house_village_parking_position'=>'p');
        $condition_where = 'p.village_id='.$where['village_id'];
        $left_join = 'left join '.C('DB_PREFIX').'house_village_parking_garage as g on p.village_id=g.village_id AND p.garage_id=g.garage_id';

        if ($where['position_num']) {//车位编号
            $condition_where .= ' AND p.position_num like "%'.trim($where['position_num']).'%"';
        }

        if ($where['position_status']) {//车位状态
            $condition_where .= ' AND p.position_status ='.intval($where['position_status']);
        }
        
        if ($where['garage_id']) {//车位状态
            $condition_where .= ' AND p.garage_id ='.intval($where['garage_id']);
        }
         
        $condition_field = 'g.garage_num,p.*';
        $order = ' `p`.`position_id` DESC';

        if ($pageSize) {
          $count = D('')->table($condition_table)->where($condition_where)->join($left_join)->count();
          $p = new Page($count,$pageSize,'page');
          $info_list = D('')->table($condition_table)->field($condition_field)->where($condition_where)->join($left_join)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
          $return['pagebar'] = $p->show();
        }else{
          $info_list = D('')->table($condition_table)->field($condition_field)->where($condition_where)->join($left_join)->order($order)->select();
        }

        $return['info_list'] = $info_list;
        return $return;
   }

   /**
    * [get_parking_one 获取单个车位信息]
    * @return [type] [description]
    */
   public function get_parking_one($where=array()){
      $info_list = $this->where($where)->find();//停车位信息
      if($info_list){
        return $info_list;
      }else{
        return false;
      }
   }

    /**
    * [get_parking_one 获取单个车位信息]
    * @return [type] [description]
    */
   public function get_one($where=array()){
   		$info_list = $this->where($where)->find();//停车位信息
	  	return $info_list;
   }

   /**
    * [get_parking_select 获取多个车位信息]
    * @param  [type] $where [description]
    * @return [type]        [description]
    */
   public function get_parking_select($where){
      $info_list = $this->where($where)->select();//停车位信息
      if($info_list){
        return $info_list;
      }else{
        return false;
      }
   }

   /**
    * [del_prking_position 删除车位信息]
    * @param  [type] $where [description]
    * @return [type]        [description]
    */
   public function del_prking_position($where){
   		$result = $this->where($where)->delete();
   		return $result;
   }

   /**
    * [parking_position_save 车位信息修改]
    * @param  [type] $where [description]
    * @return [type]        [description]
    */
   public function parking_position_save($where){
      $result = $this->save($where);
      return $result;
   }

   /**
    * [parking_position_count 获取车位数量]
    * @return [type] [description]
    */
   public function parking_position_count($where){
      $count = $this->where($where)->count();
      return $count;
   }

   /**
    * [parking_position_addAll 批量添加]
    * @return [type] [description]
    */
   public function parking_position_addAll($data){
      $result = $this->addAll($data);
      return $result;
   }

   /**
    * [parking_position_add 单个添加]
    * @return [type] [description]
    */
   public function parking_position_add($data){
      $result = $this->add($data);
      return $result;
   }
	
  /**
   * [get_parking_position_count 获取数量]
   * @return [type] [description]
   */
  public function get_parking_position_count($field='',$where=array()){
      $count = $this->where($where)->count($field);//已绑定业主的车位
      return $count;
  }
}

?>