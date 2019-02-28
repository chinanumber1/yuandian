<?php
//订单详情
class OrderdetailAction extends BaseAction{
    public function store_detail(){
        $where['order_id'] = $_GET['order_id'];
        $order = M('Store_order')->field('s.*,u.nickname,u.phone')->join('as s left join '.C('DB_PREFIX').'user u ON s.uid = u.uid ')->where($where)->find();
        $pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
        $this->assign('order',$order);
        $this->assign('pay_method',$pay_method);
        $this->display();
    }

    public function wxapp_detail(){
        $where['order_id'] = $_GET['order_id'];
        $order = M('Wxapp_order')->field(true)->where($where)->find();
        $pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
        $this->assign('order',$order);
        $this->assign('pay_method',$pay_method);
        $this->display();
    }

    public function weidian_detail(){
        $where['order_id'] = $_GET['order_id'];
        $order = M('Weidian_order')->field(true)->join('as w left join '.C('DB_PREFIX').'user u ON w.uid = u.uid ')->where($where)->find();
        $pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
        $this->assign('order',$order);
        $this->assign('pay_method',$pay_method);
        $this->display();
    }

    public function yydb_detail(){
        $where['pigcms_id'] = $_GET['order_id'];
        $order = M('Extension_activity_list')->field(true)->join('as e left join '.C('DB_PREFIX').'user u ON e.lottery_uid = u.uid ')->where($where)->find();
        $this->assign('order',$order);
        $this->display();
    }

    public function coupon_detail(){

        $condition_table = array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_coupon_record'=>'ecr',C('DB_PREFIX').'user'=>'u');
        $condition_where = "`ear`.`activity_list_id`=`eal`.`pigcms_id` AND `ecr`.`record_id`=`ear`.`pigcms_id` AND `eal`.`mer_id`='{$_GET['mer_id']}' AND `ecr`.`pigcms_id`='{$_GET['order_id']}' AND `ear`.`uid`=`u`.`uid`";
        $now_order = D('')->field('`ecr`.`pigcms_id`,`ecr`.`number`,`eal`.`title`,`ear`.`uid`,`eal`.`pigcms_id` as id,`eal`.`money`,`eal`.`name`,`u`.`nickname`,`u`.`phone`,`ecr`.`check_time`,`ecr`.`last_staff`')->where($condition_where)->table($condition_table)->find();

        $this->assign('order',$now_order);
        $this->display();
    }


}