<?php
class ServiceAction extends BaseAction{
    public function service_category(){
        //便民分类-查看 权限
        if (!in_array(164, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_service_category = D('House_service_category');

        $village_id = $this->house_session['village_id'];

        $where['parent_id'] = 0;
        $where['village_id'] = $village_id;
        $where['status'] = array('neq',4);
        $list = $database_service_category->house_service_category_list($where , true , 'sort desc');

        if(!$list){
            $this->error('处理数据有误！');
        }else{
            $this->assign('list',$list['list']);
        }
        
        $this->display();
    }
    
    
    public function s_service_category(){
        //顶子级分类-查看 权限
        if (!in_array(168, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_service_category = D('House_service_category');

        $cat_id = $_GET['cat_id'] + 0;
        if(!$cat_id){
            $this->error('传递参数有误！');
        }
        $village_id = $this->house_session['village_id'];
        $Map['id'] = $cat_id;
        $detail = $database_service_category->house_service_category_detail($Map,'cat_name');
        $this->assign('detail',$detail['detail']);
        $where['parent_id'] = $cat_id;
        $where['village_id'] = $village_id;
        $where['status'] = array('neq',4);
        $list = $database_service_category->house_service_category_list($where , true , 'sort desc');

        if(!$list){
            $this->error('处理数据有误！');
        }else{
            $this->assign('list',$list['list']);
        }
        
        $this->display();
    }
    
    public function service_category_add(){
        $database_service_category = D('House_service_category');
        $cat_id = $_GET['cat_id'] + 0;
        
        if($cat_id){
            //子级分类-添加 权限
            if (!in_array(169, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $cat_url = U('service_category',array('cat_id'=>$cat_id));
            $where['id'] = $cat_id;
            $detail = $database_service_category->house_service_category_detail($where,'cat_name');
            $this->assign('detail',$detail['detail']);
        }else{
            //顶级分类-添加 权限
            if (!in_array(165, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $cat_url = U('service_category');
        }
        if(IS_POST){
            $result = $database_service_category->house_service_category_add();
            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg'] , $cat_url);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            
            $this->display();
        }
    }
    
    public function service_category_del(){
        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }
        
        $database_house_service_category = D('House_service_category');
        $where['id'] = $id;

        $Map['parent_id'] = $where['id'];
        $s_cate_arr = $database_house_service_category->where($Map)->getField('id',true);
        if($s_cate_arr){
            //顶级分类-删除 权限
            if (!in_array(167, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
        }else{
            //子级分类-删除 权限
            if (!in_array(171, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
        }

        $result = $database_house_service_category->house_service_category_del($where);
        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }
    
    public function service_category_edit(){
        $id = $_GET['id'] + 0;
        $cat_id = $_GET['cat_id'] + 0;
        if(!$id){
            $this->error('传递参数');
        }
        
        if($cat_id){
            $cat_url = U('service_category',array('cat_id'=>$cat_id));
        }else{
            $cat_url = U('service_category');
        }
        $database_house_service_category = D('House_service_category');
        $where['id'] = $id;
        
        if(IS_POST){
           
            if($cat_id){
                //子级分类-编辑 权限
                if (!in_array(170, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }
            }else{
                //顶级分类-编辑 权限
                if (!in_array(166, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }
            }

            $result = $database_house_service_category->house_service_category_edit($where);
            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg'],$cat_url);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $detail = $database_house_service_category->house_service_category_detail($where);

            if(!$detail){
                $this->error('数据处理有误！');
            }  else {
                if(!$detail['status']){
                    $this->error('该条信息不存在！');
                }else{
                    $this->assign('detail',$detail['detail']);
                    $parent_id = $detail['detail']['parent_id'];
                    if($parent_id){
                        $Map['id'] = $parent_id;
                        $parent_detail = $database_house_service_category->house_service_category_detail($Map);
                        $this->assign('parent_detail',$parent_detail['detail']);
                    }
                }
            }

            $this->display();
        }
    }
    
    public function service_info_edit(){
        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数');
        }
        
        $database_house_service_info = D('House_service_info');
        $where['id'] = $id;
        
        if(IS_POST){
            //便民列表-编辑 权限
            if (!in_array(172, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            
            $result = $database_house_service_info->house_service_info_edit($where);
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
            $detail = $database_house_service_info->house_service_info_detail($where);
            $database_house_service_category= D('House_service_category');
            $Map['status'] = 1;
            $Map['parent_id'] = 0;
            $Map['village_id'] = $this->house_session['village_id'];
            $cat_flist = $database_house_service_category->where($Map)->getField('id,cat_name');
            
            foreach($cat_flist as $k=>$v){
                $Map['parent_id'] = $k;
                $cat_num = $database_house_service_category->where($Map)->count();
                if($cat_num == 0){
                    unset($cat_flist[$k]);
                    continue;
                }
            }

            $Map['parent_id'] = $detail['detail']['cat_fid'];
            $cat_slist = $database_house_service_category->where($Map)->field('id,cat_name,cat_url')->select();

            if(!$detail){
                $this->error('数据处理有误！');
            }  else {
                if(!$detail['status']){
                    $this->error('该条信息不存在！');
                }else{
                    $this->assign('detail',$detail['detail']);
                }
            }

            $this->assign('cat_flist',$cat_flist);
            $this->assign('cat_slist',$cat_slist);
            $this->display();
        }
        
    }
    
    public function ajax_upload_pic(){
	if ($_FILES['imgFile']['error'] != 4) {
		$img_mer_id = sprintf("%09d", $this->house_session['village_id']);
		$rand_num = mt_rand(10, 99) . '/' . substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

		$upload_dir = './upload/service/' . $rand_num . '/';
		if (!is_dir($upload_dir)) {
		    mkdir($upload_dir, 0777, true);
		}
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
		$upload->maxSize = $this->config['group_pic_size'] * 1024 * 1024;
		$upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
		$upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
		$upload->savePath = $upload_dir;
		$upload->imageClassPath = 'ORG.Util.Image';
		$upload->saveRule = 'uniqid';
		if ($upload->upload()) {
		    $uploadList = $upload->getUploadFileInfo();

		    $title = $rand_num . ',' . $uploadList[0]['savename'];

		    $service_image_class = new service_image();
		    $url = $service_image_class->get_image_by_path($title);
		    $title = $rand_num . '/' . $uploadList[0]['savename'];
		    exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
		} else {
		    exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
		}
	    } else {
		exit(json_encode(array('error' => 1, 'message' => '没有选择图片')));
	    }
    }
    
    
    public function service_info(){
        //便民列表-查看 权限
        if (!in_array(172, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $where['village_id'] = $this->house_session['village_id'];

        if ($_GET['title']) {
            $where['title'] = array('like','%'.trim($_GET['title']).'%');
        }
        
        if ($_GET['cate']) {
            $where['cat_fid'] = $_GET['cate'];
        }
        
        if ($_GET['subcate']) {
            $where['cat_id'] = $_GET['subcate'];
        }

        if ($_GET['status']==1 || $_GET['status']==='0') {
            $where['status'] = $_GET['status'];
        }

        $cate_where = array(
            'village_id'=>$where['village_id'],
            'parent_id'=>0,
        );
        $cate_list = D('House_service_category')->where($cate_where)->getField('id,cat_name');
        $this->assign('cate_list',$cate_list);
        // var_dump($cate_list);


        $database_house_service_info = D('House_service_info');
        $list = $database_house_service_info->house_service_info_page_list($where);
        if(!$list){
            $this->error('数据处理有误！');
        }else{
            $this->assign('list',$list['list']);
        }

        $this->display();
    }
    
    public function service_info_add(){
        //便民列表-添加 权限
        if (!in_array(173, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            $database_house_service_info = D('House_service_info');
            $result = $database_house_service_info->house_service_info_add();
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
            $database_house_service_category= D('House_service_category');
            $where['status'] = 1;
            $where['parent_id'] = 0;
            $where['village_id'] = $this->house_session['village_id'];
            
            $field = array('id','cat_name','cat_url','parent_id');
            $cat_flist = $database_house_service_category->where($where)->field($field)->select();
            $where['parent_id'] = array('neq',0);
            $where['cat_url'] = array('eq','');
            $cat_slist = $database_house_service_category->where($where)->field($field)->select();
            
            foreach($cat_flist as $k=>$v){
                $Map['parent_id'] = $v['id'];
                $Map['cat_url'] = array('eq','');
                $Map['status'] = 1;
                $son = $database_house_service_category->where($Map)->field($field)->select();
                if($son){
                    $cat_flist[$k]['son']=$son;
                }
            }
            
            if((count($cat_flist)==count($cat_flist,1)) || !$cat_flist || !$cat_slist){
                $this->error('请先添加没有设置链接的分类及子分类！');
            }
            
           foreach($cat_flist as $k=>$v){
               if(!$v['son']){
                   unset($cat_flist[$k]);
               }
           }
            $this->assign('cat_flist',$cat_flist);
            $this->display();
        }
    }
    
    public function service_info_del(){
        //便民列表-删除 权限
        if (!in_array(175, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }
        
        $where['id'] = $id;
        $database_house_service_info = D('House_service_info');
        $result = $database_house_service_info->house_service_info_del($where);
        
        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }
    
    public function ajax_get_category(){
        $cat_id = $_POST['cat_id'] + 0;
        if(!$cat_id){
            return false;
        }
        
        $database_house_service_category= D('House_service_category');
        $where['parent_id'] = $cat_id;
        $where['status'] = 1;
        $where['cat_url'] = array('eq','');
        $cat_list = $database_house_service_category->where($where)->field('id,cat_name,cat_url')->select();
        if($cat_list){
            exit(json_encode(array('status'=>1,'cat_list'=>$cat_list)));
        }else{
            exit(json_encode(array('status'=>0,'cat_list'=>$cat_list)));
        }
    }
    
    public function service_category_detail(){
        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }
        
        $database_house_service_category = D('House_service_category');
        $where['id'] = $id;
        $detail = $database_house_service_category->house_service_category_detail($where);
        
        if(!$detail){
            $this->error('数据处理有误！');
        }else{
            $this->assign('detail',$detail['detail']);
        }
        
        $this->display();
    }
    
    public function service_slide(){
        //便民页面幻灯片-查看 权限
        if (!in_array(175, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_shequ_slider = D('House_village_slider');
        $village_id = $this->house_session['village_id'];
        $has_service_slide = $this->getHasConfig($village_id, 'has_service_slide');
        $this->assign('has_service_slide',$has_service_slide);
        if($has_service_slide){
            $where['village_id'] = $village_id;
            $where['type'] = 1;
            $list = $database_shequ_slider->shequ_slider_list($where , true , 'sort desc');
            $this->assign('list' , $list);
        }
		$this->display();
    }
    
    public function service_slide_add(){
        //便民页面幻灯片-添加 权限
        if (!in_array(245, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_shequ_slider = D('House_village_slider');
        $village_id = $this->house_session['village_id'];
        $has_service_slide = $this->getHasConfig($village_id, 'has_service_slide');
        if($has_service_slide){
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
    
    
    public function service_slide_edit(){
    	$id = $_GET['id'] + 0;
    	if(!$id){
    	    $this->error('传递参数有误！');
    	}
        $village_id = $this->house_session['village_id'];
        $has_service_slide = $this->getHasConfig($village_id, 'has_service_slide');
        if($has_service_slide){
            $database_shequ_slider = D('House_village_slider');
            $where['id'] = $id;
            if(IS_POST){
                //便民页面幻灯片-编辑 权限
                if (!in_array(246, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }

                $_POST['village_id'] = $_SESSION['house']['village_id'];
                $result = $database_shequ_slider->shequ_slider_edit($where,$_POST);
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }else{
                //便民页面幻灯片-查看 权限
                if (!in_array(175, $this->house_session['menus'])) {
                    $this->error('对不起，您没有权限执行此操作');
                }

                $slider_info = $database_shequ_slider->where($where)->find();
                if(!$slider_info){
                    $this->error('该信息不存在！');
                }
                $this->assign('slider_info' , $slider_info);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
    }
    
    public function slider_del(){
        //便民页面幻灯片-删除 权限
        if (!in_array(247, $this->house_session['menus'])) {
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
    
    private function getHasConfig($village_id,$field){
        $database_house_village = D('House_village');
        $house_village_info = $database_house_village->get_one($village_id,$field);
        $config_info = $house_village_info[$field];
        return $config_info;
   }
}
?>
