<?php
class Express_templateModel extends Model
{
	public function get_list_by_mer_id($mer_id)
	{
		$templates = $this->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->select();
		$area_ids = array();
		foreach ($templates as $vo) {
			$t_ids = explode(',', $vo['area_ids']);
			$area_ids = array_merge($area_ids, $t_ids);
		}
		$area_list = array();
		if ($area_ids) {
			$temp_list = D('Area')->field(true)->where(array('area_id' => array('in', $area_ids)))->select();
			foreach ($temp_list as $tl) {
				$area_list[$tl['area_id']] = $tl;
			}
		}
		
		foreach ($templates as &$vo) {
			$t_ids = explode(',', $vo['area_ids']);
			$area_names = $pre = '';
			foreach ($t_ids as $id) {
				if (isset($area_list[$id])) {
					$area_names .= $pre . $area_list[$id]['area_name'];
					$pre = ',';
				}
			}
			$vo['area_name'] = $area_names;
		}
		return $templates;
	}
	
	public function get_deliver_list($mer_id, $store_id)
	{
		if ($templates = $this->field(true)->where(array('mer_id' => $mer_id))->select()) {
			$tids = array();
			foreach ($templates as $temp) {
				$tids[] = $temp['id'];
			}
			$return = array();
			if ($tids) {
				$tids = implode(',', $tids);
				$sql = "SELECT v.*, a.area_id FROM " . C('DB_PREFIX') . "express_template_value AS v INNER JOIN " . C('DB_PREFIX') . "express_template_area AS a ON v.id=a.vid WHERE v.tid IN ({$tids}) ORDER BY v.id DESC";
				$list = $this->query($sql);
				$store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
				$this_city = $store['city_id'];
				foreach ($list as $row) {
					if ($row['area_id'] == 0) {//同城
						$return[$row['tid']][$this_city] = $row;
					} else {
						$return[$row['tid']][$row['area_id']] = $row;
					}
				}
			}
			return $return;
		} else {
			return false;
		}
	}
}