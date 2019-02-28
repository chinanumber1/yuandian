<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2016/8/2
 * Time: 14:23
 */

class BbsAction extends BaseAction {

    public function index(){

        $type = $_GET['type'];
        $where = array();
        if($type == 'name'){
            $where['b.name'] = array('like','%'.$_GET['keyword'].'%');
        } else if ($type == 'budding'){
            $where['v.name'] = array('like','%'.$_GET['keyword'].'%');
            $where['bu.title'] = array('like','%'.$_GET['keyword'].'%');
            $where['_logic'] = 'OR';
        }

        $count = D('FcBbs')->getFcBbsCount($where);
        import('@.ORG.system_page');
        $p = new Page($count,15);
        $fcbbsList = D('FcBbs')->getFcBbsList($where,$p->firstRow,$p->listRows);

        foreach($fcbbsList as &$list){
            if($list['owners'] == 1){
                $viliageInfo = D('Fc_village')->where(array('village_id'=>$list['village_id']))->field('name')->find();
                $list['viliage_name'] = $viliageInfo['name'];
            } else if($list['owners'] == 2){
                $buildingInfo = D('Fc_building')->where(array('id'=>$list['building_id']))->field('title')->find();
                $list['building_name'] = $buildingInfo['title'];
            }

        }

        $this->assign('fcbbsList',$fcbbsList);

        $page = $p->show();
        $this->assign('page',$page);
        $this->display();
    }


    /*public function create (){

        $villages = D("FcVillage")->field('village_id,name')->all();
        $building = D("Fc_building")->field('id,title')->select();

        $this->assign('villages', $villages);
        $this->assign('building', $building);
        $this->display();
    }

    public function create_data(){

        $data['name'] = $_POST['name'];
        $data['is_display'] = intval($_POST['is_display']);
        $data['village_id'] = !empty($_POST['village_id']) ? intval($_POST['village_id']) : 0;
        $data['building_id'] = !empty($_POST['id']) ? intval($_POST['id']) : 0;
        $data['owners'] = $_POST['owners'];

        $data['create_time'] = time();
        $data['update_time'] = time();

        if($data['owners'] == 1){ //小区

            if(D('Fc_bbs')->where(array('village_id'=>$data['village_id']))->find()){
                $this->frame_submit_tips(0,'该小区已存在论坛！');
            }

            if(empty($data['village_id'])) {
                $this->frame_submit_tips(0,'请选择小区！');
            }
        }

        if($data['owners'] == 2){ //楼盘

            if(D('Fc_bbs')->where(array('building_id'=>$data['building_id']))->find()){
                $this->frame_submit_tips(0,'该楼盘已存在论坛！');
            }

            if(empty($data['building_id'])) {
                $this->frame_submit_tips(0,'请选择楼盘！');
            }
        }

        $result = D('Fc_bbs')->data($data)->add();

        if($result){
            $this->frame_submit_tips(1,'添加成功！');
        }else{
            $this->frame_submit_tips(0,'添加失败！请重试~');
        }
    }*/


    public function bbs_disable(){
        $is_display = $_POST['disable'];
        $result = D('Fc_bbs')->where(array('id'=>$_POST['id']))->data(array('is_display'=>$is_display))->save();

        if($result){
            $this->success('更新成功！');
        }else{
            $this->error('更新失败');
        }
    }
}