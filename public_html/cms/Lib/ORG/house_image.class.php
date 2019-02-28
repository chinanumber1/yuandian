<?php
class house_image
{
	public function get_image_by_path($path)
	{
		if (!empty($path)) {
			$image_tmp = explode(',',$path);
			$return = C('config.site_url').'/upload/house/'.$image_tmp[0].'/'.$image_tmp['1']; 
			return $return;
		} else {
			return false;
		}
	}
	
	public function del_image_by_path($path)
	{
		if (!empty($path)) {
			$image_tmp = explode(',', $path);
			unlink('./upload/house/' . $image_tmp[0] . '/' . $image_tmp['1']);
			return true;
		} else {
			return false;
		}
	}
}
?>