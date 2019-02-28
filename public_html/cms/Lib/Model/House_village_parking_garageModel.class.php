<?php

class House_village_parking_garageModel extends Model
{
	/**
	 * [get_garage_list 查询多条车库信息]
	 * @param  string $field [查询字段]
	 * @param  array  $where [查询条件]
	 * @return [type]        [description]
	 */
	public function get_garage_list($field='',$where=array()){
		$result = $this->field($field)->where($where)->select();
		if($result !==false){
			return $result;
		}else{
			return false;
		}
	}

	/**
	 * [get_garage_select 车库信息]
	 * @return [type] [description]
	 */
	public function get_garage_select($field='',$where=array()){
		import('@.ORG.merchant_page');
        $count =$this->where($where)->count();
        $p = new Page($count,20,'page');
		$info_list['info_list'] = $this
                    ->field($field)
                    ->where($where)
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
    * [del_prking_position 删除车库信息]
    * @param  [type] $where [description]
    * @return [type]        [description]
    */
   public function del_prking_garage($where){
   		$result = $this->where($where)->delete();
   		if($result !== false){
   			return $result;
   		}else{
   			return false;
   		}
   }

   /**
    * [add_parking_garage  车库信息添加]
    * @param [type] $data [description]
    */
   public function add_parking_garage($data){
   		$result = $this->add($data);
   		if($result !== false){
   			return $result;
   		}else{
   			return false;
   		}
   }

   /**
    * [save_parking_garage 车库信息修改]
    * @return [type] [description]
    */
   public function save_parking_garage($data){
   		$result = $this->save($data);
   		if($result !== false){
   			return $result;
   		}else{
   			return false;
   		}
   }

   /**
    * [get_garage_one 获取一条信息]
    * @return [type] [description]
    */
   public function get_garage_one($field='*',$where=array()){
      $result = $this->field($field)->where($where)->find();
        if($result !== false){
          return $result;
        }else{
          return false;
        }
   }
   /**
    * [get_garage_one 获取一条信息]
    * @return [type] [description]
    */
   public function get_one($where=array()){
   		$result = $this->where($where)->find();
      return $result;
   }
}

?>