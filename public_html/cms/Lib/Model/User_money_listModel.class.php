<?php
class User_money_listModel extends Model{
	/*增加记录行数*/
	public function add_row($uid,$type,$money,$msg,$record_ip = true,$ask=0,$ask_id=0,$admin=false,$time =0 ){
		if(!$time){
			$time = $_SERVER['REQUEST_TIME'];
		}
		$data_user_money_list['uid'] = $uid;
		$data_user_money_list['type'] = $type;
		$data_user_money_list['money'] = $money;
		$data_user_money_list['desc'] = $msg;
		$data_user_money_list['time'] = $time;
		$data_user_money_list['ask'] = $ask;
		$data_user_money_list['ask_id'] = $ask_id;
		if($_SESSION['system']['id'] && $admin){
			$data_user_money_list['admin_id'] = $_SESSION['system']['id'];
		}
		if($record_ip){
			$data_user_money_list['ip'] = get_client_ip(1);
		}
		if($this->data($data_user_money_list)->add()){
			return true;
		}else{
			return false;
		}
	}
	/*获取列表*/
	public function get_list($uid,$page=0,$page_count=0){
		$condition_user_money_list['uid'] = $uid;
		$condition_user_money_list['_string'] =" `desc` not like '%兑换余额%' AND `desc` not like '%兑换记录%'  AND `desc` not like '%不可提现%' ";
		import('@.ORG.user_page');
		$count = $this->where($condition_user_money_list)->count();
		$p = new Page($count,10);
		if($page){
			$return['money_list'] = $this->field(true)->where($condition_user_money_list)->order('`time` DESC')->page($page.','.$page_count)->select();
		}else{
			$return['money_list'] = $this->field(true)->where($condition_user_money_list)->order('`time` DESC')->limit($p->firstRow.',10')->select();
		}
		$return['pagebar'] = $p->show();
		return $return;
	}

	public function get_redpack_list($uid,$page,$page_count){
		$condition['uid'] = $uid;

		import('@.ORG.user_page');
		$red =  M('User_redpack_history');
		$count = $red->where($condition)->count();
		$p = new Page($count,10);
		if($page){
			$return['money_list'] = $red->field(true)->where($condition)->order('`add_time` DESC')->page($page.','.$page_count)->select();
		}else{
			$return['money_list'] = $red->field(true)->where($condition)->order('`add_time` DESC')->limit($p->firstRow.',10')->select();
		}

		$return['pagebar'] = $p->show();
		return $return;
	}


	/*获取列表*/
	public function get_pc_list($uid,$limit='10'){
		$condition_user_money_list['uid'] = $uid;
		$return = $this->field(true)->where($condition_user_money_list)->order('`time` DESC')->limit($limit)->select();
		return $return;
	}

	public function get_admin_recharge_list($where,$is_system){
		if($is_system){
			import('@.ORG.system_page');
		}else{
			import('@.ORG.merchant_page');
		}
		$count = $this->join('as l left join '.C('DB_PREFIX').'admin a ON a.id = l.admin_id')->where($where)->count();
		$p = new Page($count, 20);
		$arr['recharge_list'] = $this->field('l.pigcms_id,l.money,l.desc,l.type,l.time,l.admin_id,l.uid,u.nickname,u.phone,a.realname,a.level')->join('as l left join '.C('DB_PREFIX').'user u ON l.uid = u.uid left join '.C('DB_PREFIX').'admin a ON a.id = l.admin_id')->where($where)->order('l.pigcms_id DESC')->limit($p->firstRow,$p->listRows)->select();
		$arr['pagebar'] = $p->show();
		return $arr;
	}
}
?>