<?php
/*
 * 图片上传
 *
 */

class UploadAction extends BaseAction{
    public function editor_ajax_upload(){
		if(!in_array($_GET['upload_dir'],array('group/content','merchant/news','merchant/activity','activity/content','system/image','activity/index_pic','appoint/content','system/news','system/intro','bbs/category','system/gift','fc/school','portal/activity','Circle/editor'))){
			$this->editor_alert('非法的目录！');
		}

		if($_FILES['imgFile']['error'] != 4){
			$uid = $_SESSION['merchant']['mer_id'] ? $_SESSION['merchant']['mer_id'] : ($_SESSION['system']['mer_id'] ? $_SESSION['system']['mer_id'] : mt_rand(10000,99999));
			$param = array('size' => $this->config['group_pic_size']);
			$image = D('Image')->handle($uid, $_GET['upload_dir'], 0, $param);
			if (!$image['error']) {
				exit(json_encode(array('error' => 0, 'url' => $image['url']['imgFile'])));
			} else {
				$this->editor_alert($image['msg'] ? $image['msg'] : $image['message']);
			}



// 			if(!is_dir($upload_dir)){
// 				mkdir($upload_dir,0777,true);
// 			}
// 			import('ORG.Net.Upload File');
// 			$upload = new Upload File();
// 			// $upload->maxSize = $this->config['group_pic_size']*1024*1024;
// 			$upload->allowExts = array('jpg','jpeg','png','gif');
// 			$upload->allowTypes = array('image/png','image/jpg','image/jpeg','image/gif');
// 			$upload->savePath = $upload_dir;
// 			$upload->thumb=false;
// 			$upload->saveRule = 'uniqid';
// 			if($upload->upload()){
// 				$uploadList = $upload->getUpload FileInfo();
// 				$url = $upload_dir.$uploadList[0]['savename'];
// 				exit(json_encode(array('error' => 0, 'url' => $url)));
// 			}else{
// 				$this->editor_alert($upload->getErrorMsg());
// 			}
		}else{
			$this->editor_alert('没有选择图片！');
		}
    }

	public function ajax_upload_file(){
		if(empty($_GET['name'])){
			exit(json_encode(array('error' => 1,'message' =>'不知道您要上传到哪个配置项，请重试。')));
		}
		$now_config = D('Config')->field('`name`,`type`')->where(array('name'=>$_GET['name']))->find();
		if(empty($now_config)){
			exit(json_encode(array('error' => 1,'message' =>'您正在上传的配置项不存在，请重试。')));
		}
		$tmp_type_arr = explode('&',$now_config['type']);
		$type_arr = array();
		foreach($tmp_type_arr as $k=>$v){
			$tmp_value = explode('=',$v);
			$type_arr[$tmp_value[0]] = $tmp_value[1];
		}
		$allowExts = array_key_exists('file',$type_arr) ? explode(',',$type_arr['file']) : array();
		if($_FILES['imgFile']['error'] != 4){

// 			$image = D('Image')->handle($this->system_session['id'], 'files');
// 			if (!$image['error']) {
// 				exit(json_encode(array('error' => 0,'url' => $image['url']['imgFile'],'title' => $image['title']['imgFile'])));
// 			} else {
// 				exit(json_encode(array('error' => 1,'message' => $image['msg'])));
// 			}

			$img_admin_id = sprintf("%09d", $this->system_session['id']);
			$rand_num = substr($img_admin_id,0,3) . '/' . substr($img_admin_id,3,3) . '/' . substr($img_admin_id,6,3);
			$upload_dir = "./upload/files/{$rand_num}/";
			if(!is_dir($upload_dir)){
				mkdir($upload_dir,0777,true);
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->maxSize = 10*1024*1024;
			$upload->allowExts = $allowExts;
			$upload->savePath = $upload_dir;
			$upload->saveRule = 'uniqid';
			if($upload->upload()){
				$uploadList = $upload->getUploadFileInfo();
				$title = $rand_num.','.$uploadList[0]['savename'];
				exit(json_encode(array('error' => 0,'url' =>'./upload/files/'.$rand_num.'/'.$uploadList[0]['savename'],'title'=>$title)));
			}else{
				exit(json_encode(array('error' => 1,'message' =>$upload->getErrorMsg())));
			}
		}else{
			exit(json_encode(array('error' => 1,'message' =>'没有选择文件')));
		}
	}
}