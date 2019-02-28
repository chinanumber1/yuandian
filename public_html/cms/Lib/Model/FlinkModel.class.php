<?php
class FlinkModel extends Model{
	/*得到友情列表*/
	public function get_flink_list($limit=0){
		$condition_flink['status'] = 1;
		return $this->field(true)->where($condition_flink)->order('`sort` DESC,`id` ASC')->select();
	}
}

?>