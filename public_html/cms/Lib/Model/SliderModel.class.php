<?php
class SliderModel extends Model{
	/*通过导航分类的KEY获取到导航列表*/
	public function get_slider_by_key($cat_key,$limit=20){
		$database_slider_category  = D('Slider_category');
		$condition_slider_category['cat_key'] = $cat_key;
		$now_slider_category = $database_slider_category->field('`cat_id`')->where($condition_slider_category)->find();
		if($now_slider_category){
			$condition_slider['cat_id'] = $now_slider_category['cat_id'];
			$condition_slider['status'] = '1';
			$slidr_list = $this->field(true)->where($condition_slider)->order('`sort` DESC,`id` ASC')->limit($limit)->select();
			foreach($slidr_list as $key=>$value){
				if($value['pic']){
					$slidr_list[$key]['pic'] = C('config.site_url').'/upload/slider/'.$value['pic'];
				}
			}
			//web版导航多城市
			if(C('config.many_city') && ($cat_key == 'web_slider')){
				foreach($slidr_list as $key=>$value){
					if(substr($value['url'],-6) == 'nocity'){
						$slidr_list[$key]['url'] = substr($value['url'],0,strlen($value['url'])-6);
					}else{
						$slidr_list[$key]['url'] = str_replace(C('config.config_site_url'),C('config.now_site_url'),$value['url']);
					}
				}
			}
			return $slidr_list;
		}else{
			return false;
		}
	}
}

?>