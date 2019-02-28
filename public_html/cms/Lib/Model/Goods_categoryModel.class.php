<?php
class Goods_categoryModel extends Model
{
	public function get_list()
	{
		$items = $this->field(true)->where('(fid>0 AND status=1) OR fid=0')->order('`sort` DESC, `id` ASC')->select();
		
		$tmpMap = array();
		foreach ($items as $item) {
			$tmpMap[$item['id']] = $item;
		}
		$list = array();
// 		if ($is_add_all) {
// 			$list[] = array('id' => '0', 'name' => '全部分类');
// 		}

		$tlist = array();
		foreach ($items as $item) {
			if (isset($tmpMap[$item['fid']])) {
				$tmpMap[$item['fid']]['son_list'][] = &$tmpMap[$item['id']];
			} else {
				$tlist[] = &$tmpMap[$item['id']];
			}
		}
		foreach ($tlist as $tl) {
			if ($tl['status'] == 1) {
				$list[] = $tl;
			}
		}
		unset($tmpMap);
		foreach ($list as $key=>$va) {
			if(empty($va['son_list'])){
				unset($list[$key]);
			}

		}
		return $list;
	}
}
?>