<?php
class House_village_news_categoryModel extends Model{
	/*得到小区的新闻列表*/
	public function get_limit_list($village_id,$limit=10){
		return $this->field(true)->where(array('village_id'=>$village_id,'cat_status'=>'1'))->order('`cat_sort` DESC,`cat_id` DESC')->limit($limit)->select();
	}
	
}