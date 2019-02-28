<?php
class Foodshop_goods_package_detailModel extends Model
{

	public function get_detail_by_pid($pid)
	{
		$details = $this->field(true)->where(array('pid' => $pid))->select();
		$goods_ids = array();
		$list = array();
		$return = array();
		foreach ($details as $row) {
			$goods_detail = json_decode($row['goods_detail'], true);
			foreach ($goods_detail as $goods_id) {
				$goods_ids[] = $goods_id;
				$list[$goods_id][] = $row['id'];
			}
			$return[$row['id']] = $row;
		}
		
		$goods_list = M('Foodshop_goods')->field(true)->where(array('status' => 1, 'goods_id' => array('in', $goods_ids)))->select();
		foreach ($goods_list as $goods) {
			foreach ($list[$goods['goods_id']] as $detail_id) {
				if (isset($return[$detail_id])) {
					$return[$detail_id]['goods_list'][] = $goods;
				}
			}
		}
		return $return;
	}
}
?>