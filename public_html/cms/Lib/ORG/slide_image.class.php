<?php
/*
 * 得到商品的图片
 *
 */
class slide_image{
	/*根据商品数据表的图片字段的一段来得到图片*/
	public function get_image_by_path($path,$image_type='-1'){
		if(!empty($path)){
			$image_tmp = explode(',',$path);
			if($image_type == '-1'){
				$return = C('config.site_url').'/upload/slider/'.$image_tmp[0].'/'.$image_tmp['1'];
			}else if($image_type == '2'){
				$return = '/upload/bbs/category/'.$image_tmp[0].'/'.$image_tmp['1'];
			}else if($image_type == 3){
				$return = '/upload/building/'.date('Y-m-d').'/'.$image_tmp[0];
			}else if($image_type == 4){
				$return = '/upload/article/'.date('Y-m-d').'/'.$image_tmp[0];
			}else if($image_type == 5){
				$return = C('config.site_url').'/upload/activity/'.$image_tmp[0].'/'.$image_tmp['1'];
			}else if($image_type == 6){
				$return = C('config.site_url').'/upload/nav/'.$image_tmp[0].'/'.$image_tmp['1'];
			}
			return $return;
		}else{
			return false;
		}
	}


	/*根据商品数据表的图片字段来删除图片*/
	public function del_image_by_path($path,$type = 0){
		if(!empty($path)){
			$image_tmp = explode(',',$path);
			unlink('./upload/slider/'.$image_tmp[0].'/'.$image_tmp['1']);

			return true;
		}else{
			return false;
		}
	}
}
?>