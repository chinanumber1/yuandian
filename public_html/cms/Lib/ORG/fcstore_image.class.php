<?php
/* 
 * 得到店铺的图片
 * 
 */
class fcstore_image{
	/*根据店铺数据表的图片字段的一段来得到图片*/
	public function get_image_by_path($path){
		if(!empty($path)){
			$image_tmp = explode(',',$path);
			$return = C('config.site_url').'/upload/fcstore/'.$image_tmp[0].'/'.$image_tmp['1'];
			return $return;
		}else{
			return false;
		}
	}
	
	
	/*根据店铺数据表的图片字段来删除图片*/
	public function del_image_by_path($path){
		if(!empty($path)){
			$image_tmp = explode(',',$path);
			unlink('./upload/fcstore/'.$image_tmp[0].'/'.$image_tmp['1']);
			return true;
		}else{
			return false;
		}
	}

}
?>