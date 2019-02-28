<?php
class SlideAction extends BaseAction{
    public function index(){
        //首页幻灯片-查看 权限
        if (!in_array(195, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_shequ_slider = D('House_village_slider');
        $village_id = $this->house_session['village_id'];
        $has_slide = $this->getHasConfig($village_id, 'has_slide');
        $this->assign('has_slide',$has_slide);
        if($has_slide){
            $where['village_id'] = $village_id;
            $where['type'] = 0;
            $list = $database_shequ_slider->shequ_slider_list($where , true , 'sort desc');
            $this->assign('list' , $list);
        }
	$this->display();
    }
    
    public function slide_add(){
        //首页幻灯片-添加 权限
        if (!in_array(196, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_shequ_slider = D('House_village_slider');
        $village_id = $this->house_session['village_id'];
        $has_slide = $this->getHasConfig($village_id, 'has_slide');
        if($has_slide){
            if(IS_POST){
                $_POST['village_id'] = $_SESSION['house']['village_id'];
                $result = $database_shequ_slider->shequ_slider_add($_POST);
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }else{
               $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
    }
    
    public function ajax_upload_pic(){
		if ($_FILES['imgFile']['error'] != 4) {
			$img_mer_id = sprintf("%09d", $this->house_session['village_id']);
			$rand_num = mt_rand(10, 99) . '/' . substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

			$upload_dir = './upload/slider/' . $rand_num . '/';
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
			    $url = $slide_image_class->get_image_by_path($title);
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
        $group_image_class->del_image_by_path($_POST['path']);
    }
    
    
    public function slider_del(){
	    //首页幻灯片-添加 权限
	    if (!in_array(197, $this->house_session['menus'])) {
	        $this->error('对不起，您没有权限执行此操作');
	    }

		$id = $_GET['id'] + 0;
		if(!$id){
		    $this->error('传递参数有误！');
		}
		$database_shequ_slider = D('House_village_slider');
		$where['id'] = $id;
		$result = $database_shequ_slider->shequ_slider_del($where);
		if($result){
		    $this->success('删除成功！');
		}else{
		    $this->error('删除失败！');
		}
    }
    
    public function slider_edit(){
		$id = $_GET['id'] + 0;
		if(!$id){
		    $this->error('传递参数有误！');
		}
	        
	        $database_shequ_slider = D('House_village_slider');
		$where['id'] = $id;
		if(IS_POST){
	        //首页幻灯片-编辑 权限
	        if (!in_array(197, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');
	        }

		    $_POST['village_id'] = $_SESSION['house']['village_id'];
	            $_POST['url'] = htmlspecialchars_decode($_POST['url']);
		    $result = $database_shequ_slider->shequ_slider_edit($where,$_POST);
		    if($result['status']){
			$this->success($result['msg']);
		    }else{
			$this->error($result['msg']);
		    }
		}else{
	        //首页幻灯片-查看 权限
	        if (!in_array(195, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');
	        }

		    $slider_info = $database_shequ_slider->where($where)->find();
		    if(!$slider_info){
			$this->error('该信息不存在！');
		    }
		    $this->assign('slider_info' , $slider_info);
		    $this->display();
		}
    }
    
    
    private function getHasConfig($village_id,$field){
        $database_house_village = D('House_village');
        $house_village_info = $database_house_village->get_one($village_id,$field);
        $config_info = $house_village_info[$field];
        return $config_info;
   }
}

