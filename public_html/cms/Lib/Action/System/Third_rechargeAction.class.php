<?php

/*
 * 用户中心
 *
 */

class Third_rechargeAction extends BaseAction {
    public function mobile_recharge() {
        //搜索
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $condition_user['o.uid'] = $_GET['keyword'];
            }  else if ($_GET['searchtype'] == 'phone') {
                $condition_user['o.phone'] = array('like', '%' . $_GET['keyword'] . '%');
            }else if ($_GET['searchtype'] == 'nickname') {
                $condition_user['u.nickname'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }

		//排序
		$order_string = '`order_id` DESC';

		//状态
       isset( $_GET['status']) && $_GET['status']>=0 && $condition_user['o.status'] = $_GET['status'];
        if($_GET['status']==12){
            $where['o.status'] = array('in',array(5,6,12));
        }
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_user['_string'] =" (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $database_user = D('Mobile_recharge_order');

        $count_user = $database_user->join('as o left join '.C('DB_PREFIX').'user u ON u.uid = o.uid')->where($condition_user)->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 20);
        $user_list = $database_user->field('o.* ,u.nickname,u.phone as phone_user')->join('as o left join '.C('DB_PREFIX').'user u ON u.uid = o.uid')->where($condition_user)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();


        $this->assign('user_list', $user_list);
        $this->assign('status', array(0=>'未扣款',1=>'扣款成功',2=>'订单提交成功等待服务商充值',3=>'充值中',4=>'充值成功',12=>'充值失败'));

        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

        $this->display();
    }

    /**
     * @return  管理员充值列表
     */
    public function admin_recharge_list(){
        $recharge_list = M('User_money_list')->where(array('admin_id'=>array('neq','')))->select();
        $admin_list = M('Admin')->where(array('status'=>1))->select();
        $this->assign('admin_list',$admin_list);
        $where['l.admin_id'] = array('neq', 0);
        if(!empty($_GET['admin_id'])) {
            if ($_GET['admin_id'] == '0') {
                $where['l.admin_id'] = array('neq', 0);
            } else{
                $where['l.admin_id'] = $_GET['admin_id'];
            }
        }
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] =" (l.time BETWEEN ".$period[0].' AND '.$period[1].")";

        }
        $recharge_list =  D('User_money_list')->get_admin_recharge_list($where,1);

        $this->assign('recharge_list',$recharge_list);
        $this->display();
    }

}
