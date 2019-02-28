<?php
class ActivityAction extends BaseAction{
    public function index(){
        //社区活动-查看 权限
        if (!in_array(232, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->house_session['village_id'];
        $has_activity = $this->getHasConfig($village_id, 'has_activity');
        $this->assign('has_activity',$has_activity);
        
        if($has_activity){
            $database_house_village_activity = D('House_village_activity');
            $where['village_id'] = $village_id;
            $list = $database_house_village_activity->house_village_activity_page_list($where, true,'sort desc,id desc');
            $this->assign('list',$list['list']);
        }
        $this->display();
    }
    
    public function activity_add(){
        //社区活动-添加 权限
        if (!in_array(233, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_activity = D('House_village_activity');
        $village_id = $this->house_session['village_id'];
        $has_activity = $this->getHasConfig($village_id, 'has_activity');
        $this->assign('has_activity',$has_activity);
        if($has_activity){
            if(IS_POST){
                $result = $database_house_village_activity->house_village_activity_add($_POST);
                if(!$result){
                    $this->error('数据处理有误！');
                }else{
                    if($result['status']){
                        $this->success($result['msg']);
                    }else{
                       $this->error($result['msg']) ;
                    }
                }
            }else{
               $this->display(); 
            }
        }else{
            $this->error('非法访问！');
        }
    }
    
    public function activity_del(){
        //社区活动-删除 权限
        if (!in_array(235, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_activity = D('House_village_activity');
        $village_id = $this->house_session['village_id'];
        $has_activity = $this->getHasConfig($village_id, 'has_activity');
        $this->assign('has_activity',$has_activity);
        if($has_activity){
            $id = $_GET['id'] + 0;
            if(!$id){
                $this->error('传递参数有误！');
            }
            $where['id'] = $id;
            $result = $database_house_village_activity->house_village_activity_del($where);
            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                   $this->error($result['msg']) ;
                }
            }
        }else{
            $this->error('非法访问！');
        }
    }
    
    public function activity_edit(){
        $database_house_village_activity = D('House_village_activity');
        $village_id = $this->house_session['village_id'];
        $has_activity = $this->getHasConfig($village_id, 'has_activity');
        $this->assign('has_activity',$has_activity);
        if($has_activity){
            $id = $_GET['id'] + 0;
            if(!$id){
                $this->error('传递参数有误！');
            }
            $where['id'] = $id;
            if(IS_POST){
                //社区活动-编辑 权限
                if (!in_array(234, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }

                $result = $database_house_village_activity->house_village_activity_edit($where,$_POST);
                if(!$result){
                    $this->error('数据处理有误！');
                }else{
                    if($result['status']){
                        $this->success($result['msg']);
                    }else{
                       $this->error($result['msg']) ;
                    }
                }
            }else{
            //社区活动-查看 权限
            if (!in_array(232, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

                $detail = $database_house_village_activity->house_village_activity_detail($where);
                $this->assign('detail',$detail['detail']);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
    }
    
    
    public function apply_list(){
        //报名列表-查看 权限
        if (!in_array(236, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->house_session['village_id'];
        $has_activity = $this->getHasConfig($village_id, 'has_activity');
        $this->assign('has_activity',$has_activity);
        if($has_activity){
            $database_house_village_activity_apply = D('House_village_activity_apply');
            $where['village_id'] = $village_id;
            $where['is_del'] = 0;
            $activity_id = $_GET['activity_id'] + 0;
            if($activity_id){
                $where['activity_id'] = $activity_id;
            }
            
            $list = $database_house_village_activity_apply->house_village_activity_apply_page_list($where);
            $this->assign('list',$list['list']);
        }
        $this->display();
    }
    
    public function apply_del(){
        //报名列表-删除 权限
        if (!in_array(238, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $village_id = $this->house_session['village_id'];
        $has_activity = $this->getHasConfig($village_id, 'has_activity');
        if($has_activity){
            $id = $_GET['id'] + 0;
            if(!$id){
                $this->error('传递参数有误！');
            }
            $where['id'] = $id;
            $database_house_village_activity_apply = D('House_village_activity_apply');
            $result = $database_house_village_activity_apply->house_village_activity_apply_del($where);

            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $this->error('非法访问！');
        }
    }
    
    public function apply_edit(){
        $village_id = $this->house_session['village_id'];
        $has_activity = $this->getHasConfig($village_id, 'has_activity');
        if($has_activity){
            $id = $_GET['id'] + 0;
            if(!$id){
                $this->error('传递参数有误！');
            }
            $where['id'] = $id;
            $database_house_village_activity_apply = D('House_village_activity_apply');
            if(IS_POST){
                //报名列表-编辑 权限
                if (!in_array(237, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }

                $result = $database_house_village_activity_apply->house_village_activity_apply_edit($where,$_POST);
                if(!$result){
                    $this->error('数据处理有误！');
                }else{
                    if($result['status']){
                        $this->success($result['msg']);
                    }else{
                        $this->error($result['msg']);
                    }
                }
            }else{
                //报名列表-查看 权限
                if (!in_array(236, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }

                $detail = $database_house_village_activity_apply->house_village_activity_apply_detail($where);
                $this->assign($detail,$detail['detail']);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
        
    }
    
    
    public function ajax_activity_time(){
        if(IS_POST){
            $activity_start_time = $_POST['activity_start_time'] + 0;
            $apply_end_time = $_POST['apply_end_time'] + 0;
            if($activity_start_time < $apply_end_time){
                exit(json_encode(array('status'=>0,'msg'=>'活动开始时间必须大于报名结束时间')));
            }else{
                exit(json_encode(array('status'=>1,'msg'=>'时间可用')));
            }
        }else{
            exit(json_encode(array('status'=>0,'msg'=>'访问页面有误！')));
        }
    }


    public function ajax_upload_pic(){
        if ($_FILES['imgFile']['error'] != 4) {
            $img_mer_id = sprintf("%09d", $this->house_session['village_id']);
            $rand_num = mt_rand(10, 99) . '/' . substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

            $upload_dir = './upload/activity/' . $rand_num . '/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();
            $upload->maxSize = $this->config['group_pic_size'] * 1024 * 1024;
            $upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
            $upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
            $upload->savePath = $upload_dir;
            $upload->saveRule = 'uniqid';
            if ($upload->upload()) {
                $uploadList = $upload->getUploadFileInfo();
                $title = $rand_num . ',' . $uploadList[0]['savename'];
                $slide_image_class = new slide_image();
                $url = $slide_image_class->get_image_by_path($title , 5);
                $title = $rand_num . '/' . $uploadList[0]['savename'];
                exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
            } else {
                exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
            }
        } else {
            exit(json_encode(array('error' => 1, 'message' => '没有选择图片')));
        }
    }

    public function ajax_del_pic(){
        $group_image_class = new slide_image();
        $group_image_class->del_image_by_path($_POST['path'],1);
    }
    
    private function getHasConfig($village_id,$field){
        $database_house_village = D('House_village');
        $house_village_info = $database_house_village->get_one($village_id,$field);
        $config_info = $house_village_info[$field];
        return $config_info;
   }
}

