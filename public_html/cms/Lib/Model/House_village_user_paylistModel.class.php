<?php
class House_village_user_paylistModel extends Model{
	
	/*得到小区下所有的业主列表*/
	public function get_limit_list_page($usernum,$village_id,$pageSize=20){
		if(!$village_id){
			return null;
		}
		
		$return = array();
		$condition_where['village_id'] = $village_id;
		$condition_where['usernum'] = $usernum;
		$count_user = D('House_village_user_paylist')->where($condition_where)->count();

		import('@.ORG.merchant_page');
		$p = new Page($count_user,$pageSize,'page');
		$user_list = $this->where($condition_where)->order('`ydate` DESC,`mdate` DESC')->limit($p->firstRow.','.$p->listRows)->select();

		if($user_list){
			$return['totalPage'] = ceil($count_user/$pageSize);
			$return['user_count'] = count($user_list);
			$return['pagebar'] = $p->show();
			$return['user_list'] = $user_list;
		}
		
		return $return;
	}

	
}

?>