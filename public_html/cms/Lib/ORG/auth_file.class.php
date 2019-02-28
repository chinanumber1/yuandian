<?php
/** 
 * 店铺的资质资料
 */
class auth_file
{
	/**根据店铺数据表的图片字段的一段来得到图片*/
	public function get_image_by_path($path, $image_type='-1')
	{
		if (!empty($path)) {
			$image_tmp = explode(',', $path);
			if ($image_type == '-1') {
				$return['image'] = C('config.site_url') . '/upload/authfile/' . $image_tmp[0] . '/' . $image_tmp['1'];
				$return['m_image'] = C('config.site_url') . '/upload/authfile/' . $image_tmp[0] . '/m_' . $image_tmp['1'];
				$return['s_image'] = C('config.site_url') . '/upload/authfile/' . $image_tmp[0] . '/s_' . $image_tmp['1'];
			} else {
				$return = C('config.site_url') . '/upload/authfile/' . $image_tmp[0] . '/' . $image_type . '_' . $image_tmp['1'];
			}
			return $return;
		} else {
			return false;
		}
	}
	/**根据店铺数据表的图片字段来得到图片*/
	public function get_allImage_by_path($path, $image_type='-1')
	{
		if (!empty($path)) {
			$tmp_pic_arr = explode(';', $path);
			foreach ($tmp_pic_arr as $key => $value) {
				$image_tmp = explode(',', $value);
				if ($image_type == '-1') {
					$return[$key]['image'] = C('config.site_url') . '/upload/authfile/' . $image_tmp[0] . '/' . $image_tmp['1'];
					$return[$key]['m_image'] = C('config.site_url') . '/upload/authfile/' . $image_tmp[0] . '/m_' . $image_tmp['1'];
					$return[$key]['s_image'] = C('config.site_url') . '/upload/authfile/' . $image_tmp[0] . '/s_' . $image_tmp['1'];
				} else {
					$return[$key] = C('config.site_url') . '/upload/authfile/' . $image_tmp[0] . '/' . $image_type . '_' . $image_tmp['1'];
				}
			}
			return $return;
		} else {
			return false;
		}
	}
	
	/**根据店铺数据表的图片字段来删除图片*/
	public function del_image_by_path($path)
	{
		if (!empty($path)) {
			$image_tmp = explode(',', $path);
			unlink('./upload/authfile/' . $image_tmp[0] . '/' . $image_tmp['1']);
			unlink('./upload/authfile/' . $image_tmp[0] . '/m_' . $image_tmp['1']);
			unlink('./upload/authfile/' . $image_tmp[0] . '/s_' . $image_tmp['1']);
			return true;
		} else {
			return false;
		}
	}
}
?>