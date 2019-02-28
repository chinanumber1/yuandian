<?php
class promote_image{
	/*根据商品数据表的图片字段的一段来得到图片*/
	public function get_image_by_path($path,$image_type='-1'){
		if(!empty($path)){
			$image_tmp = explode(',',$path);
			if($image_type == '-1'){
				$return = C('config.site_url').'/upload/promote/'.$image_tmp[0].'/'.$image_tmp['1'];
			}
			return $return;
		}else{
			return false;
		}
	}
	
	
	/*根据商品数据表的图片字段来删除图片*/
	public function del_image_by_path($path){
		if(!empty($path)){
			$image_tmp = explode(',',$path);
			unlink('./upload/promote/'.$image_tmp[0].'/'.$image_tmp['1']);
			return true;
		}else{
			return false;
		}
	}
}
?>