<?php
/* 
 * 得到商品的图片
 * 
 */
class foodshop_goods_image
{
	/*根据商品数据表的图片字段的一段来得到图片*/
	public function get_image_by_path($path, $image_type='-1')
	{
		if (!empty($path)) {
		    $image_tmp = explode(',', $path);
		    $filepath = 'foodshop_goods';
		    if (strstr($image_tmp[0], 'sysgoods_')) {
		        $filepath = 'sysgoods';
		    }
			if ($image_type == '-1') {
			    $return['image'] = C('config.site_url') . '/upload/' . $filepath. '/' . $image_tmp[0] . '/' . $image_tmp['1'];
			    $return['m_image'] = C('config.site_url') . '/upload/' . $filepath. '/' . $image_tmp[0] . '/m_' . $image_tmp['1'];
			    $return['s_image'] = C('config.site_url') . '/upload/' . $filepath. '/' . $image_tmp[0] . '/s_' . $image_tmp['1'];
			} else {
			    $return = C('config.site_url') . '/upload/' . $filepath. '/' . $image_tmp[0] . '/' . $image_type . '_' . $image_tmp['1'];
			}
			return $return;
		} else {
			return false;
		}
	}
	/*根据商品数据表的图片字段来得到图片*/
	public function get_allImage_by_path($path, $image_type='-1')
	{
		if (!empty($path)) {
			$tmp_pic_arr = explode(';', $path);
			foreach ($tmp_pic_arr as $key => $value) {
			    $image_tmp = explode(',', $value);
			    $filepath = 'foodshop_goods';
			    if (strstr($image_tmp[0], 'sysgoods_')) {
			        $filepath = 'sysgoods';
			    }
				if ($image_type == '-1') {
				    $return[$key]['image'] = C('config.site_url') . '/upload/' . $filepath . '/' . $image_tmp[0] . '/' . $image_tmp['1'];
				    $return[$key]['m_image'] = C('config.site_url') . '/upload/' . $filepath . '/' . $image_tmp[0] . '/m_' . $image_tmp['1'];
				    $return[$key]['s_image'] = C('config.site_url') . '/upload/' . $filepath . '/' . $image_tmp[0] . '/s_' . $image_tmp['1'];
				} else {
				    $return[$key] = C('config.site_url') . '/upload/' . $filepath . '/' . $image_tmp[0] . '/' . $image_type . '_' . $image_tmp['1'];
				}
			}
			return $return;
		} else {
			return false;
		}
	}
	/*根据商品数据表的自增ID字段来得到图片*/
	public function get_image_by_id($id, $image_type)
	{
		$database_goods = D('Foodshop_goods');
		$condition_goods['goods_id'] = $id;
		$now_goods = $database_goods->field('`image`')->where($condition_goods)->find();
		return $this->get_image_by_path($now_goods['image'], $image_type);
	}
	
	/*根据商品数据表的图片字段来删除图片*/
	public function del_image_by_path($path)
	{
		if (!empty($path)) {
			$image_tmp = explode(',', $path);
			unlink('./upload/foodshop_goods/' . $image_tmp[0] . '/' . $image_tmp['1']);
			unlink('./upload/foodshop_goods/' . $image_tmp[0] . '/m_' . $image_tmp['1']);
			unlink('./upload/foodshop_goods/' . $image_tmp[0] . '/s_' . $image_tmp['1']);
			return true;
		} else {
			return false;
		}
	}
	/*根据商品数据表的自增ID字段来得到图片*/
	public function del_image_by_id($id)
	{
		$database_goods = D('Foodshop_goods');
		$condition_goods['goods_id'] = $id;
		$now_goods = $database_goods->field('`image`')->where($condition_goods)->find();
		return $this->del_image_by_path($now_goods['image']);
	}
}
?>