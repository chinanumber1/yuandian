<?php
	/*
	 *  计划任务 投票结束后通知创建者和参与用户
	 * */
class plan_community_finish_vote extends plan_base{
	public function runTask(){

	    // 获取没有推送的结束时间小于当前时间的投票信息
        $time = time();
        $vote_info = M('Community_vote')->field('vote_id')->where(array('is_already_push' => 1, 'end_time' => array('lt', $time)))->select();
        if ($vote_info) {
            // 推送给参与投票的用户投票结果
            $d_community_join = D('Community_join');
            foreach ($vote_info as $val) {
                $this->keepThread();
                $d_community_join->comm_finish_vote($val['vote_id']);
            }
        }
		return true;
	}
}
?>