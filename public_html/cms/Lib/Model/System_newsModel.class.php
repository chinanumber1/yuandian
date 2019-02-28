<?php
class System_newsModel extends Model{
	/*通过导航分类的KEY获取到导航列表*/
	public function get_news($limit=20){
		$news_list = M('')->field('`n`.`id`,`n`.`title`,`c`.`name`')->table(array(C('DB_PREFIX').'system_news'=>'n',C('DB_PREFIX').'system_news_category'=>'c'))->where("`n`.`status`='1' AND `c`.`id`=`n`.`category_id` AND `c`.`status`='1'")->order('`n`.`sort` DESC,`n`.`id` DESC')->limit($limit)->select();
		// dump($news_list);
		return $news_list;
	}
}

?>