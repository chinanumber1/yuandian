<?php
/* 
 * 得到商品的图片
 * 
 */
class meal_image{
	/*根据商品数据表的图片字段来得到图片*/
	public function get_image_by_path($path,$site_url='',$image_type='-1'){
		if(!empty($path)){
			$image_tmp = explode(',',$path);
			if($image_type == '-1'){
				$return['image'] = $site_url.'/upload/meal/'.$image_tmp[0].'/'.$image_tmp['1'];
				$return['m_image'] = $site_url.'/upload/meal/'.$image_tmp[0].'/m_'.$image_tmp['1'];
				$return['s_image'] = $site_url.'/upload/meal/'.$image_tmp[0].'/s_'.$image_tmp['1'];
			}else{
				$return = $site_url.'/upload/meal/'.$image_tmp[0].'/'.$image_type.'_'.$image_tmp['1'];
			}
			return $return;
		}else{
			return false;
		}
	}
	/*根据商品数据表的自增ID字段来得到图片*/
	public function get_image_by_id($id,$site_url,$image_type){
		$database_meal = D('Meal');
		$condition_meal['meal_id'] = $id;
		$now_meal = $database_meal->field('`image`')->where($condition_meal)->find();
		return $this->get_image_by_path($now_meal['image'],$site_url,$image_type);
	}
	
	/*根据商品数据表的图片字段来删除图片*/
	public function del_image_by_path($path){
		if(!empty($path)){
			$image_tmp = explode(',',$path);
			unlink('./upload/meal/'.$image_tmp[0].'/'.$image_tmp['1']);
			unlink('./upload/meal/'.$image_tmp[0].'/m_'.$image_tmp['1']);
			unlink('./upload/meal/'.$image_tmp[0].'/s_'.$image_tmp['1']);
			return true;
		}else{
			return false;
		}
	}
	/*根据商品数据表的自增ID字段来得到图片*/
	public function del_image_by_id($id){
		$database_meal = D('Meal');
		$condition_meal['meal_id'] = $id;
		$now_meal = $database_meal->field('`image`')->where($condition_meal)->find();
		return $this->del_image_by_path($now_meal['image']);
	}
}
?>