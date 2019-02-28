<?php
class House_adminModel extends Model{
	/*角色列表*/
	public function get_limit_list_page($column,$pageSize=20,$isSystem=false){
		if(!$column['village_id']){
			return null;
		}
    	$order = '`id` DESC';
    	if($isSystem){
    		import('@.ORG.system_page');
    	}else{
    		import('@.ORG.merchant_page');
    	}
    	$count_order = $this->where($column)->count();
    	$p = new Page($count_order,$pageSize,'page');
    	$list = $this->where($column)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
    	$return['pagebar'] = $p->show();
    	$return['list'] = $list;
    	return $return;
	}

	public function get_one($id){
		if(!$id){
			return false;
		}
		return $this->where(array('id'=>$id))->find();
	}

	/**
	 * [get_info_one 获取单个信息]
	 * @return [type] [description]
	 */
	public function get_info_one($field='',$where=array()){
		$result = D('House_admin')->field($field)->where($where)->find();
		if($result !== false){
			return $result;
		}else{
			return false;
		}
	}
}