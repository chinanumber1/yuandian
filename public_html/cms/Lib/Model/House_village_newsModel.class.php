<?php
class House_village_newsModel extends Model{
	
	protected $tableName = 'house_village_news';
	
	public function getlist($column){
		if(!$column['village_id']){
			return '';
		}
		$condition_table  = array(C('DB_PREFIX').'house_village_news'=>'n',C('DB_PREFIX').'house_village_news_category'=>'c',C('DB_PREFIX').'house_village'=>'v');
		$condition_where = " n.village_id = v.village_id  AND n.village_id = c.village_id AND  n.cat_id = c.cat_id AND c.cat_status=1  AND n.village_id=".$column['village_id'];
		if($column['status']){
			$condition_where .= " AND n.status = ".intval($column['status']);
		}
		$condition_field = 'n.*,c.cat_name';
		
		$order = ' n.is_hot DESC,n.news_id DESC ';
		import('@.ORG.merchant_page');
		$count_news = D('')->table($condition_table)->where($condition_where)->count();
		$p = new Page($count_news,20,'page');
		$village_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		
		$return['pagebar'] = $p->show();
		$return['news_list'] = $village_list;
		
		return $return;
	}
	
	/*得到小区的新闻列表*/
	public function get_limit_list($village_id,$limit){
		$condition_table = array(C('DB_PREFIX').'house_village_news'=>'n',C('DB_PREFIX').'house_village_news_category'=>'c');
		$condition_where = 'n.village_id='.$village_id.' AND n.status=1 AND n.village_id = c.village_id AND  n.cat_id = c.cat_id AND c.cat_status=1';
		$order = '`is_hot` DESC,`add_time` DESC';
		return D('')->table($condition_table)->where($condition_where)->order($order)->limit($limit)->select();

		//return $this->field(true)->where(array('village_id'=>$village_id,'status'=>'1'))->order('`is_hot` DESC,`add_time` DESC')->limit($limit)->select();
	}
	
	/*得到分类下的新闻列表*/
	public function get_list_by_cid($cat_id){
		return $this->field(true)->where(array('status'=>'1','cat_id'=>$cat_id))->order('`is_hot` DESC,`add_time` DESC')->select();
	}
	
	public function get_one($news_id){
		return $this->field(true)->where(array('status'=>'1','news_id'=>$news_id))->find();
	}
}