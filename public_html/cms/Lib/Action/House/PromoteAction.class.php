<?php
class PromoteAction extends BaseAction{
    public function index(){
        $database_house_village = D('House_village');
        $house_session = session('house');
        $where['village_id'] = $house_session['village_id'];

        //推广二维码-查看 权限
        if (!in_array(17, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){    
	        //推广二维码-编辑 权限
	        if (!in_array(18, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');
	        }
            $result = $database_house_village->house_village_edit($where);
            if(!$result){
                $this->error('传递参数有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $now_house_village = $database_house_village->where($where)->find();
            $this->assign('now_house_village',$now_house_village);
            $this->display();
        }
    }
    
    public function ajax_upload_pic(){
	if ($_FILES['imgFile']['error'] != 4) {
		$img_mer_id = sprintf("%09d", $this->house_session['village_id']);
		$rand_num = mt_rand(10, 99) . '/' . substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
		$upload_dir = './upload/promote/' . $rand_num . '/';
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
                    
                    $promote_image_class = new promote_image();
		    $url = $promote_image_class->get_image_by_path($title);
		    $title = $rand_num . '/' . $uploadList[0]['savename'];
		    exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
		} else {
		    exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
		}
	    } else {
		exit(json_encode(array('error' => 1, 'message' => '没有选择图片')));
	    }
    }
}
?>
