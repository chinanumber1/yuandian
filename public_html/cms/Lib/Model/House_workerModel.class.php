<?php
class House_workerModel extends Model
{

	public function add_score($wid, $score = 5)
	{
		if ($worker = $this->field(true)->where(array('wid' => $wid))->find()) {
			$data = array();
			$data['reply_count'] = $worker['reply_count'] + 1;
			$data['score_all'] = $worker['score_all'] + $score;
			$data['score_mean'] = round($data['score_all']/$data['reply_count'] * 10)/10;
			$this->where(array('wid' => $wid))->save($data);
		}
	}
	
	public function add_num($wid)
	{
		$this->where(array('wid' => $wid))->setInc('num');
	}
}
?>