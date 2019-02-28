<?php
class House_village_phoneModel extends Model{
	public function getList($where=array(),$pageSize = 20){

		import('@.ORG.merchant_page');
        $return = array();
        $condition_table  = array(C('DB_PREFIX').'house_village_phone'=>'p',C('DB_PREFIX').'house_village_phone_category'=>'c');
        $condition_where = 'p.village_id=c.village_id AND p.cat_id=c.cat_id AND p.village_id='.$where['village_id'];

        if ($where['name']) {
            $condition_where .= ' AND p.name like "%'.trim($where['name']).'%"';
        }

        if ($where['phone']) {
            $condition_where .= ' AND p.phone like "%'.trim($where['phone']).'%"';
        }

        if ($where['cat_id']) {//车位状态
            $condition_where .= ' AND p.cat_id ='.intval($where['cat_id']);
        }

        $condition_where .= ' AND p.status <> 4';
         
        $condition_field = 'c.cat_name,p.*';
        $order = ' `p`.`pigcms_id` DESC';

        if ($pageSize) {
          $count = D('')->table($condition_table)->where($condition_where)->count();
          $p = new Page($count,$pageSize,'page');
          $info_list = D('')->table($condition_table)->field($condition_field)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
          $return['pagebar'] = $p->show();
        }else{
          $info_list = D('')->table($condition_table)->field($condition_field)->where($condition_where)->order($order)->select();
        }

        $return['list'] = $info_list;
        return $return;
	}
}