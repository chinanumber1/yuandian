<?php
class Foodshop_goods_packageModel extends Model
{

	public function save_post_form($data, $store_id)
	{
		$id = isset($data['id']) ? intval($data['id']) : 0;
		
		$package_data = array('name' => $data['name'], 'store_id' => $store_id);
		$package_data['price'] = $data['price'];
		$package_data['old_price'] = empty($data['old_price']) ? $data['price'] : $data['old_price'];
		$package_data['note'] = $data['note'];
		$package_data['status'] = $data['status'];
		$package_data['dateline'] = time();
		$package_data['image'] = isset($data['image']) && $data['image'] ? $data['image'] : '';
		
		$delete_detail_ids = array();
		$detail_db = M('Foodshop_goods_package_detail');
		if ($check_data = $this->field(true)->where(array('store_id' => $store_id, 'id' => $id))->find()) {
			if ($this->where(array('store_id' => $store_id, 'id' => $id))->save($package_data)) {
				$old_details = $detail_db->field(true)->where(array('pid' => $id))->select();
				foreach ($old_details as $row) {
					$delete_detail_ids[] = $row['id'];
				}
			} else {
				return false;
			}
		} else {
			$id = $this->add($package_data);
			if (empty($id)) return false;
		}
		
		$data_detail = array('pid' => $id);
		foreach ($data['nums'] as $k => $num) {
			if (!isset($data['goods_ids'][$k])) continue;
			$data_detail['num'] =  max(1, $num);
			$data_detail['goods_detail'] = json_encode($data['goods_ids'][$k]);
			$did = isset($data['dids'][$k]) ? intval($data['dids'][$k]) : 0;
			if ($detail_db->field(true)->where(array('id' => $did))->find()) {
				$detail_db->where(array('id' => $did))->save($data_detail);
				$delete_detail_ids = array_diff($delete_detail_ids, array($did));
			} else {
				$did = $detail_db->add($data_detail);
			}
		}
		
		$delete_detail_ids && $detail_db->where(array('id' => array('in', $delete_detail_ids)))->delete();
		return $id;
	}
	
	public function get_detail_by_id($param, $flag = false)
	{
		$detail = $this->field(true)->where($param)->find();
		if ($detail) {
			if ($flag) {
				$detail['goods_detail'] = D('Foodshop_goods_package_detail')->get_detail_by_pid($detail['id']);
			}
			return $detail;
		} else {
			return false;
		}
	}
	
	public function get_list_by_store_id($store_id){
		$list = $this->where(array('store_id'=>$store_id,'status'=>'1'))->order('`id` ASC')->select();
		foreach($list as &$value){
			$value['price'] = floatval($value['price']);
		}
		return $list;
	}
}
?>