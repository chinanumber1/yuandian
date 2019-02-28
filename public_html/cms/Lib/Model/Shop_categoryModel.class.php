<?php
class Shop_categoryModel extends Model
{

	public function lists($is_add_all = false)
	{
	    $items = $this->field(true)->where('(cat_fid>0 AND cat_status=1) OR cat_fid=0')->order('cat_sort DESC')->select();
		$tmpMap = array();
		foreach ($items as $item) {
			$tmpMap[$item['cat_id']] = $item;
		}
		$list = array();
		if ($is_add_all) {
			$list[] = array('cat_id' => '0', 'cat_name' => '全部分类', 'cat_url' => 'all');
		}
		
		$tlist = array();
		foreach ($items as $item) {
			if (isset($tmpMap[$item['cat_fid']])) {
				$tmpMap[$item['cat_fid']]['son_list'][] = &$tmpMap[$item['cat_id']];
			} else {
				$tlist[] = &$tmpMap[$item['cat_id']];
			}
		}
		foreach ($tlist as $tl) {
			if ($tl['cat_status'] == 1) {
				$list[] = $tl;
			}
		}
		unset($tmpMap);
		return $list;
	}
	
	public function get_category_by_catUrl($cat_url){
		$condition_group_category['cat_url'] = $cat_url;
		$condition_group_category['cat_status'] = '1';
		$now_category = $this->field(true)->where($condition_group_category)->find();
// 		if(!empty($now_category)){
// 			$now_category['url'] = $this->get_category_url($now_category);
// 		}
		return $now_category;
	}
	
	protected function get_category_url($category){
		return C('config.site_url').'/category/'.$category['cat_url'];
	}
}
?>