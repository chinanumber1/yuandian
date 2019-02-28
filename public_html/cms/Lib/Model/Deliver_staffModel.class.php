<?php
/**
 *	配送员工model
 *	@author newfly07@163.com
 */
class Deliver_staffModel extends Model{
	/**
	 * 根据条件获取配送员工
	 */
	public function getlist($field='*',$where='',$order='`create_time` DESC',$limit=10,$page=1){
		if($where){
			$this->where($where);
		}
		
		if($field){
			$this->field($field);
		}
	
		if($order){
			$this->order($order);
		}
		
		if($limit){
			$this->limit($limit);
		}
		
		if($page){
			$this->page($page);
		}
	
		return  $this->select();
	}
	
	
	/**
	 * 获取商家配送员列表
	 * @param int mer_id 商家id 0为平台配送员
	 * @param int limit 每页限制条数
	 * @param int page 页数
	 */
	public function getStaffByMer($mer_id=0,$limit=10,$page){
		if(!is_numeric($mer_id)){
			return false;
		}
		
		$where = array(
			'mer_id' => $mer_id,
		);
		
		
		return $this->getlist('*',$where);
	
	}
	
	
	/**
	 * 根据条件获取配送员信息
	 */
	public function getInfoByCondition($field='*',$where=''){
		if($where){
			$this->where($where);
		}
		
		if($field){
			$this->field($field);
		}
		return  $this->find();
	
	}
	
	
	/**
	 * 判断手机号是否存在
	 */
	public function is_exist($phone){
		if(!$phone){
			return false;
		}
		
		$info = $this->getInfoByCondition('staff_id',array('phone'=>$phone));
		
		if($info){
			return true;
		}else {
			return false;
		}
	
	}
	
	/**
	 * 添加配送员
	 */
	public function add_staff($data){
		if(!isset($data['phone'])){
			return false;
		}
	
		if(!$this->is_exist($data['phone'])){
			return false;
		}
		
		return $this->add($data);
	}
	
	/**
	 * 修改配送员信息
	 */
	public function update_staff($data,$where){
		if(empty($data)){
			return false;
		}
		
		if(!isset($data['phone'])){
			return false;
		}
	
		if(empty($where)){
			return false;
		}
		
		return $this->where($where)->save($data);
	}
	
}