<?php
class User_score_listModel extends Model{
	/*增加记录行数*/
	public function add_row($uid,$type,$score,$msg,$record_ip = true,$clean=false,$time = 0){
		if(!$time){
			$time=  time();
		}
        $data_user_score_list['uid']   = $uid;
        $data_user_score_list['type']  = $type;
        $data_user_score_list['score'] = $score;
        $data_user_score_list['desc']  = $msg;
        $data_user_score_list['time']  = $time;
		if($type==1) {
			if( C('config.score_end_days')!=0){
				$data_user_score_list['end_time']     = $_SERVER['REQUEST_TIME'] + C('config.score_end_days') * 86400;
			}
			$data_user_score_list['used_count']   = 0;
			$data_user_score_list['clean_time']   = 0;
			$data_user_score_list['clean_count']  = 0;
			$data_user_score_list['clean_status'] = 0;
		}else if($type==2 && !$clean){
			$where['type'] = 1;
			$where['uid'] = $uid;
			$where['clean_status'] = 0;
			$where['_string'] = 'used_count < score';
			$score_list = $this->where($where)->select();
			foreach ($score_list as $index => $item) {
				$date=array();
				$item['score_have'] = $item['score']-$item['used_count'];
				if($score>$item['score_have']){
					$date['used_count'] = $item['score'];
					$date['clean_status'] = 3;
					$score-=$item['score_have'];
					$this->where(array('id'=>$item['id']))->save($date);

				}else if($score<=$item['score_have']){
					$date['used_count'] = $item['used_count']+$score;
					if($score==$item['score_have']){
						$date['clean_status'] = 3;
					}
					$this->where(array('id'=>$item['id']))->save($date);

					break;
				}
			}
		}
		if($record_ip){
			$data_user_score_list['ip'] = get_client_ip(1);
		}
		if($this->data($data_user_score_list)->add()){
			return true;
		}else{
			return false;
		}
	}
	/*获取列表*/
	public function get_list($uid){
		$condition_user_score_list['uid'] = $uid;
		$_GET['page'] = isset($_POST['page'])?$_POST['page']:$_GET['page'];
		import('@.ORG.user_page');
		$count = $this->where($condition_user_score_list)->count();
		$p = new Page($count,10);
		$return['score_list'] = $this->field(true)->where($condition_user_score_list)->order('`time` DESC')->limit($p->firstRow,$p->listRows)->select();
		if($_GET['page'] >  $p->totalPage &&  $p->totalPage>0){
			$return['score_list'] = array();
		}
		$return['pagebar'] = $p->show();
		return $return;
	}

	//清理score
	public function clean_user_score(){
		$where['type'] = 1;
		$where['clean_status'] = 0;
		$where['clean_time'] = 0;
		//$where['end_time'] = array('elt',time());
		$time = time();
		$where['_string'] = "user_score>0 AND used_count < score AND end_time > 0 AND end_time <={$time}";
		$clean_score_list = $this->field('id,uid,score,clean_count,used_count')->where($where)->select();
		$user_score =array();

		foreach ($clean_score_list as $c) {
			//if($c['uid']!=1358674) continue;
			$date['clean_count'] = $c['score']-$c['used_count'];
			$date['clean_time'] =$_SERVER['REQUEST_TIME'];
			$date['clean_status'] = 1;
			$user_score[$c['uid']]+=$date['clean_count'];
			$this->where(array('id'=>$c['id']))->save($date);
		}
		foreach ($user_score as $key=>$v) {
			D('User')->use_score($key,$v,'积分清零',1);
		}

	}

	//public function next_score_clean_count($conditon){
    //
	//}
	public function get_all_score(){
		$where['status'] = 1;
		return M('User')->where($where)->sum('score_count');
	}
}
?>