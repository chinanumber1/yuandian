<?php
class User_collectModel extends Model{
    public function get_collect_num($uid,$openid){
        $type = array('appoint_detail','group_detail','group_shop','meal_detail','merchant_id');
		
		
        $res['appoint_count'] = D('')->table(array(C('DB_PREFIX').'appoint'=>'a', C('DB_PREFIX').'appoint_collection'=>'ac'))->where("ac.uid= '".$uid."' AND ac.appoint_id = a.appoint_id")->count();

		//团购收藏
		$condition_where = "`g`.`mer_id`=`m`.`mer_id` AND `m`.`status`='1' AND `g`.`status`='1' AND `g`.`type`='1' AND `g`.`group_id`=`c`.`id` AND `c`.`uid`='$uid' AND `c`.`type`='group_detail'";
        $condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'user_collect'=>'c');
		$res['group_count'] = D('')->table($condition_table)->where($condition_where)->count();
	
		//餐饮收藏
        $res['meal_count'] = D('')->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_collection'=>'msc',C('DB_PREFIX').'merchant_store_foodshop'=>'msf'))->where("msc.uid= '".$uid."' AND ms.store_id = msc.store_id AND msf.store_id = msc.store_id")->count();
		
		//商家收藏
		if($openid){
			$sql = "SELECT b.* FROM ". C('DB_PREFIX') . "merchant_user_relation AS a INNER JOIN  ". C('DB_PREFIX') . "merchant as b ON a.mer_id=b.mer_id WHERE a.openid='$openid'";
			$tmp_res = M('')->query($sql);
			$res['merchant_count'] = count($tmp_res);
		}else{
			$res['merchant_count'] = 0;
		}
		
        return $res;
    }
}
?>