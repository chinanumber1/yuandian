<?php
class Group_share_relationModel extends Model{
	public function check_share($uid,$order_id){

		$res = $this->where(array('uid'=>$uid,'order_id'=>$order_id))->find();

		if($res['fid']==$res['order_id']){
			return array('is_shared'=>0,'res'=>$res);
		}else{
			return array('is_shared'=>1,'res'=>$res);
		}
		
	}
	
	//获取组团购人数
	//未消费状态的订单才能分享
	public function get_share_num($uid,$order_id){
		//$fid = M('')->table(C('DB_PREFIX').'group_share_relation as r')->field('r.fid')->join(C('DB_PREFIX').'group_order o ON r.order_id = o.order_id')->where(array('r.uid'=>$uid,'r.order_id'=>$order_id,'o.status'=>0,'o.paid'=>1))->find();
		$fid = $this->get_share_fid($uid,$order_id);
		//$sql = "SELECT sum(o.num) as num from pigcms_group_share_relation r LEFT JOIN pigcms_group_order o on r.order_id = o.order_id where r.fid = ".$fid." AND o.uid = ".$uid;
		$num = M('')->table(C('DB_PREFIX').'group_share_relation as r')->field('SUM(o.num) num')->join(C('DB_PREFIX').'group_order o ON r.order_id = o.order_id')->where(array('r.fid'=>$fid,'o.status'=>0,'o.paid'=>1))->find();
		$num = $num['num'];
		return $num;
	}

	public function get_share_fid ($uid,$order_id){

		$fid = M('')->table(C('DB_PREFIX').'group_share_relation as r')->field('r.fid')->join(C('DB_PREFIX').'group_order o ON r.order_id = o.order_id')->where(array('r.uid'=>$uid,'r.order_id'=>$order_id))->find();
		return $fid['fid'];
	}


	public  function  get_share_user($uid,$order_id){
		$fid = $this->get_share_fid($uid,$order_id);
		//$ress = M('')->table(C('DB_PREFIX').'group_share_relation as r')->field('r.uid,u.avatar as img ,u.nickname as name')->join(C('DB_PREFIX').'user u ON r.uid = u.uid')->where(array('r.fid'=>$fid))->group('uid')->limit(11)->select();
		$res = M('')->table(C('DB_PREFIX').'group_share_relation as r')->field('r.uid,u.avatar as img ,u.nickname as name,o.status')->join(C('DB_PREFIX').'user u ON r.uid = u.uid')->join(C('DB_PREFIX').'group_order o ON r.order_id = o.order_id')->where(array('r.fid'=>$fid,'o.status'=>array('neq',3),'o.paid'=>1))->group('uid')->limit(11)->order('r.order_id ASC')->select();
		return $res;
	}

}