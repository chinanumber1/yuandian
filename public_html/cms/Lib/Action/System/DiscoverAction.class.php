<?php
/**
 * Created by PhpStorm.
 * User: wanzy
 * Date: 2018/6/25
 * Time: 13:47
 * 发现-发布信息-后台管理
 */

class DiscoverAction extends BaseAction {

    /**
     * 发现-发布信息列表
     */
    public function index(){
        $where = array();
        // 类型过滤查询
        $type_id = !empty($_GET['type_id']) ? $_GET['type_id'] : '';
        if($type_id) {
            $where['type_id'] = $type_id;
        }
        // 状态过滤查询
        $discover_status = !empty($_GET['discover_status']) ? $_GET['discover_status'] : '';
        if($discover_status) {
            $where['discover_status'] = $discover_status;
        }

        // 时间范围查询
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] =" (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        // 只查询正常和关闭的数据
        $where['discover_status'] = array('in', '1, 2');

        $count = M('Discover_msg')->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count,15);
        $discover_msg = M('Discover_msg')->where($where)->limit($p->firstRow,$p->listRows)->select();
        $m_user = M('User');
        $m_discover_category = M('Discover_category');
        foreach ($discover_msg as &$val) {
            $val['user'] = $m_user->field('nickname')->where(array('uid'=>$val['discover_uid']))->find();
            $val['type_info'] = $m_discover_category->field('type_name')->where(array('type_id'=>$val['type_id']))->find();
            $val['discover_content'] = str_replace('\"','\'',strip_tags($val['discover_content'],'<img>'));
        }

        $this->assign('discover_msg',$discover_msg);

        $page = $p->show();
        $this->assign('page',$page);
        $this->display();
    }

    /**
     * 发现-发布信息详情
     */
    public function look_discover_msg(){
        $discover_id = intval($_GET['discover_id']);
        // 查询单条信息
        $info = M("Discover_msg")->where(array('discover_id'=>$discover_id))->find();
        // 查询发布者昵称
        $userInfo = M('User')->field('nickname')->where(array('uid'=>$info['discover_uid']))->find();
        // 查询发布类型
        $categoryInfo = D('Discover_category')->field('type_name')->where(array('type_id'=>$info['type_id']))->find();
        $info['nickname'] = $userInfo['nickname'];
        $info['type_name'] = $categoryInfo['type_name'];
        // 过滤处理
        $info['discover_content'] = str_replace('\"','\'',strip_tags($info['discover_content'],'<img>'));
        $this->assign('info', $info);
        // 处理图片
        $img_arr = !empty($info['discover_img']) ? unserialize($info['discover_img']) : false;
        foreach($img_arr as &$v){
            $v=$this->config['site_url'].$v;
        }
        $this->assign('img_list', $img_arr);
        $this->display();
    }

    /**
     * 发现-关闭（前端看不到）
     */
    public function close_discover_msg(){
        $discover_id = intval($_POST['discover_id']);
        // 查询单条信息-确认是否存在该信息-并判断是否已经关闭
        $info = M("Discover_msg")->where(array('discover_id'=>$discover_id))->find();
        if (empty($info) || $info['discover_status'] == 3) {
            $this->error('该信息不存在或者已经删除');
        }
        if ($info['discover_status'] == 2) {
            $this->error('该信息已经关闭');
        }
        // 确认存在未关闭的正常该信息 做关闭处理
        $close = M("Discover_msg")->where(array('discover_id'=>$discover_id))->data(array('discover_status' => 2))->save();
        if($close){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败！请重试~');
        }
    }

    /**
     * 发现-开启（前端看到）
     */
    public function open_discover_msg(){
        $discover_id = intval($_POST['discover_id']);
        // 查询单条信息-确认是否存在该信息-并判断是否已经开启
        $info = M("Discover_msg")->where(array('discover_id'=>$discover_id))->find();
        if (empty($info) || $info['discover_status'] == 3) {
            $this->error('该信息不存在或者已经删除');
        }
        if ($info['discover_status'] == 1) {
            $this->error('该信息已经开启');
        }
        // 确认存在未开启的正常该信息 做开启处理
        $close = M("Discover_msg")->where(array('discover_id'=>$discover_id))->data(array('discover_status' => 1))->save();
        if($close){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败！请重试~');
        }
    }


    /**
     * 发现-删除（前后端均看不到）
     */
    public function del_discover_msg(){
        $discover_id = intval($_POST['discover_id']);
        // 查询单条信息-确认是否存在该信息-并判断是否已经删除
        $info = M("Discover_msg")->where(array('discover_id'=>$discover_id))->find();
        if (empty($info) || $info['discover_status'] == 3) {
            $this->error('该信息不存在或者已经删除');
        }
        // 确认存在未删除的正常该信息 做删除处理
        $close = M("Discover_msg")->where(array('discover_id'=>$discover_id))->data(array('discover_status' => 3))->save();
        if($close){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败！请重试~');
        }
    }

    /**
     * 发现-分类信息列表
     */
    public function category(){
        $where = array();
        $count = M('Discover_category')->count();
        import('@.ORG.system_page');
        $p = new Page($count,15);
        $discover_category = M('Discover_category')->where($where)->limit($p->firstRow,$p->listRows)->select();

        $this->assign('discover_category',$discover_category);
        $page = $p->show();
        $this->assign('page',$page);
        $this->display();
    }

    /**
     * 发现-添加分类信息
     */
    public function add_category() {
        if(IS_POST){
            if (empty($_POST['type_name'])) {
                $this->frame_submit_tips(0,'添加失败！请填写分类名称~');
            }
            $data = array(
                'type_name' => $_POST['type_name'],
                'type_status' => 1,
                'type_add_time' => time()
            );
            if(M('Discover_category')->data($data)->add()){
                $this->frame_submit_tips(1,'添加成功！');
            }else{
                $this->frame_submit_tips(0,'编辑失败！请重试~');
            }
        }else{
            $this->display();
        }
    }

    /**
     * 发现-编辑分类信息
     */
    public function edit_category() {
        if($_POST['type_name'] || $_POST['type_status']){
            $type_id = intval($_POST['type_id']);
            // 查询单条信息-确认是否存在该信息
            $info = M("Discover_category")->where(array('type_id'=>$type_id))->find();
            if (empty($info)) {
                $this->error('该分类不存在或者已经删除');
            }
            $data = array();
            // 相同信息不写入
            if ($_POST['type_name'] && $info['type_name'] != $_POST['type_name']) {
                $data['type_name'] = $_POST['type_name'];
            }
            // 相同信息不写入
            if ($_POST['type_status'] && $info['type_status'] != $_POST['type_status']) {
                $data['type_status'] = $_POST['type_status'];
            }
            // 不同信息写入
            if (!empty($data)) {
                if(M('Discover_category')->where(array('type_id'=>$type_id))->data($data)->save()){
                    $this->frame_submit_tips(1,'编辑成功！');
                }else{
                    $this->frame_submit_tips(0,'编辑失败！请重试~');
                }
            } else {
                // 没有信息改变，直接返回成功
                $this->frame_submit_tips(1,'编辑成功！');
            }
        }else{
            $type_id = intval($_GET['type_id']);
            // 查询单条信息-确认是否存在该信息
            $info = M("Discover_category")->where(array('type_id'=>$type_id))->find();
            if (empty($info)) {
                $this->error('该分类不存在或者已经删除');
            }
            $this->assign('info',$info);
            $this->display();
        }
    }

    /**
     * 删除分类-分类下没有发布过信息可以删除，否则只能禁止
    */
    public function del_category(){
        $type_id = intval($_POST['type_id']);
        // 查询单条信息-确认是否存在该信息
        $info = M("Discover_category")->where(array('type_id'=>$type_id))->find();
        if (empty($info)) {
            $this->error('该分类不存在或者已经删除');
        }
        // 查询改分类下存不存在信息，存在就不可删除
        $where['discover_status'] = array('in', '1, 2');
        $where['type_id'] = $type_id;
        $count = M('Discover_msg')->where($where)->count();
        if ($count) {
            $this->error('该分类已经存在信息，请先删除这些信息');
        }
        if(M("Discover_category")->where(array('type_id'=>$type_id))->delete()){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！请重试~');
        }
    }

    /**
     * 禁止分类-原有发布过的可以查看，后续的无法发布，前端无法查看
    */
    public function close_category(){
        $type_id = intval($_POST['type_id']);
        // 查询单条信息-确认是否存在该信息-并判断是否已经关闭
        $info = M("Discover_category")->where(array('type_id'=>$type_id))->find();
        if (empty($info)) {
            $this->error('该分类不存在或者已经删除');
        }
        if ($info['discover_status'] == 2) {
            $this->error('该分类已经禁止');
        }
        // 确认存在未关闭的正常该信息 做关闭处理
        $close = M("Discover_category")->where(array('type_id'=>$type_id))->data(array('type_status' => 2))->save();
        if($close){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败！请重试~');
        }
    }


    /**
     * 开启分类-前端可以查看，可以发布
     */
    public function open_category(){
        $type_id = intval($_POST['type_id']);
        // 查询单条信息-确认是否存在该信息-并判断是否已经关闭
        $info = M("Discover_category")->where(array('type_id'=>$type_id))->find();
        if (empty($info)) {
            $this->error('该分类不存在或者已经删除');
        }
        if ($info['discover_status'] == 1) {
            $this->error('该分类已经开启');
        }
        // 确认存在未开启的正常该信息 做开启处理
        $close = M("Discover_category")->where(array('type_id'=>$type_id))->data(array('type_status' => 1))->save();
        if($close){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败！请重试~');
        }
    }
}