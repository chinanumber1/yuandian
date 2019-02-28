<?php
/*
 * 顺风车管理
 *   Writers    hanlu
 *   BuildTime  2016/10/10 13:22
 */
class RideAction extends BaseAction{
    # 顺风车列表
    public function index(){
        $count_user = M('Ride')->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 15);
        $list   =   M('Ride')->field(true)->limit($p->firstRow . ',' . $p->listRows)->order('ride_id DESC')->select();
        if($list){
            foreach($list as &$v){
                $v['area_name'] =   M('Area')->where(array('area_id'=>$v['city_id']))->getField('area_name');
                $v['order_id']  =   M('Ride_order')->where(array('ride_id'=>$v['ride_id']))->getField('order_id');
            }
        }
        $this->assign('list', $list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }
    # 查看顺风车
    public function show(){
        $ride_id    =   $_GET['ride_id'];
        $list   =   M('Ride')->field(true)->where(array('ride_id'=>$ride_id))->find();
        if($list){
            $list['area_name']  =   M('Area')->where(array('area_id'=>$list['city_id']))->getField('area_name');
        }
        $this->assign('list', $list);
        $this->display();
    }
    # 顺风车订单
    public function order(){
        $ride_id    =   $_GET['ride_id'];
        $count_user = M('Ride_order')->where(array('ride_id'=>$ride_id))->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 15);
        $list   =   M('Ride_order')->field(true)->where(array('ride_id'=>$ride_id))->limit($p->firstRow . ',' . $p->listRows)->order('order_time DESC')->select();

        $this->assign('list', $list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }

    public function save_status(){

        $res = M('Ride')->where(array('ride_id'=>$_POST['ride_id']))->data(array('status'=>$_POST['status']))->save();
        if($res){
            $this->frame_submit_tips(1,'修改成功');
        }else{
            $this->frame_submit_tips(0,'修改失败！请重试~');
        }

    }
}