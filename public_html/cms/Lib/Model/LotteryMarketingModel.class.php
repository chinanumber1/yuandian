<?php
	/**
	 * 奖励营销
	 */

class LotteryMarketingModel extends Model
{
	private $start_redpack_time;
	private $redpack_cycle_time;
	private $redpack_min_money;
	private $redpack_max_money;
	public function __construct()
	{
		parent::__construct();
		if(C('config.open_app_redpack')==0){
			return array('error_code'=>1,'msg'=>'没有开启发红包');
		}
		$this->start_redpack_time  = strtotime(C('config.start_redpack_time'));
		if(time()<$this->start_redpack_time){
			return array('error_code'=>1,'msg'=>'红包还没开始发');
		}

		$this->redpack_cycle_time  = C('config.redpack_cycle_time');
		if($this->redpack_cycle_time==0){
			return array('error_code'=>1,'msg'=>'周期内不能发红包');
		}
		$this->redpack_min_money  = C('config.redpack_min_money');
		if($this->redpack_min_money<=0){
			return array('error_code'=>1,'msg'=>'红包最小金额错误');
		}
		$this->redpack_max_money  = C('config.redpack_max_money');

		if($this->redpack_max_money<=0 ||$this->redpack_max_money<$this->redpack_min_money ){
			return array('error_code'=>1,'msg'=>'红包最大金额错误');
		}
	}

	///检查是否有红包
	public function check_redpack($uid){
		if(!$this->config['open_app_redpack']){
			return array('error_code'=>1,'msg'=>'App红包未开启');
		}
		$now_user = D('User')->get_user($uid);
		if( !$this->redpack_cycle_time){
			return array('error_code'=>1,'msg'=>'红包设置错误');
		}
		if($now_user['status']!=1 || $now_user['forzen_score']==1 || $now_user['score_count']<=0){
			return array('error_code'=>1,'msg'=>'用户状态不能领取');
		}
		$cycle_time = $this->get_cycle_time();
		$time = $cycle_time['now_start_time'].$cycle_time['now_end_time'];
		if($res = M('Redpack_cycle')->where(array('time'=>$time))->find()){
			$all_money = $res['all_money'];
			$all_score = $res['all_score'];
			$per_score_money = $res['per_score_money'];
			$redpack_money = $res['redpack_money'];
			$status = $res['status'];
			$redpack_percent = $res['redpack_percent'];
			$fid  = $res['id'];
		}else {
			$status=1;
			if ($cycle_time['prev_start_time'] != 0) {
				$condion_where['use_time'] = array('BETWEEN', "{$cycle_time['prev_start_time']},{$cycle_time['prev_end_time']}");
				$all_money                 = D('Merchant_money_list')->get_system_income($condion_where);
				$all_score = D('User_score_list')->get_all_score();

			} else {
				$all_money = 0;
				$status = 0;
			}

			if ($all_money > 0) {
				$redpack_money       = $all_money * C('config.redpack_money_percent') / 100;
				$per_score_money = round($redpack_money / $all_score ,2);
			}
			$date['all_money'] = $all_money;
			$date['all_score'] = $all_score;
			$date['per_score_money'] = $per_score_money; //char
			$date['redpack_money'] = $redpack_money;
			$date['status'] = $status;
			$date['redpack_percent'] = C('config.redpack_money_percent');
			$date['time'] =$time;
			$date['add_time'] =time();
			$fid = M('Redpack_cycle')->add($date);
		}

		if($all_money==0 || $per_score_money==0){
			return array('error_code'=>1,'msg'=>'没有可以发红包的金额');
		}else{
			if(M('User_redpack_history')->where(array('uid'=>$uid,'fid'=>$fid))->find()){
				return array('error_code'=>1,'msg'=>'已经领取红包了');
			}else{
				$get_redpack = $now_user['score_count'] * $per_score_money;
				if($get_redpack==0){
					return array('error_code'=>1,'msg'=>'没有可以发红包的金额');
				}
				//$result = D('User')->add_money($uid,$get_redpack,'获取平台红包');
				if($get_redpack<$this->redpack_min_money){
					$get_redpack = $this->redpack_min_money;
				}else if($get_redpack>$this->redpack_max_money){
					$get_redpack = $this->redpack_max_money;
				}
				return array('error_code'=>0,'msg'=>'有红包','money'=>$get_redpack);
			}
		}
	}

	//获取红包
	public function get_redpack($uid){
		$now_user = D('User')->get_user($uid);
		if($now_user['status']!=1|| $now_user['forzen_score']==1 || $now_user['score_count']<=0){
			return array('error_code'=>1,'msg'=>'用户状态不能领取');
		}

		$cycle_time = $this->get_cycle_time();
		$time = $cycle_time['now_start_time'].$cycle_time['now_end_time'];
		if($res = M('Redpack_cycle')->where(array('time'=>$time))->find()){

			if(M('User_redpack_history')->where(array('uid'=>$uid,'fid'=>$res['id']))->find()){
				return array('error_code'=>1,'msg'=>'已经领取红包了');
			}
			$per_score_money = $res['per_score_money'];
			$get_redpack = round($now_user['score_count'] * $per_score_money,2);

			if($get_redpack<$this->redpack_min_money){
				$get_redpack = $this->redpack_min_money;
			}else if($get_redpack>$this->redpack_max_money){
				$get_redpack = $this->redpack_max_money;
			}
			$date['uid'] = $now_user['uid'];
			$date['fid'] = $res['id'];
			$date['score_count'] = $now_user['score_count'];
			$date['per_score_money'] = $per_score_money;
			$date['money'] = $get_redpack;
			$date['add_time'] = time();

			M('User_redpack_history')->add($date);
			if(C('config.app_redpack_withdraw')==1){
				$result = D('User')->add_money($uid,$get_redpack,'App中获取平台红包');
			}else{
				D('User')->add_money($uid,$get_redpack,'App中获取平台红包');
				$result = D('User')->add_score_recharge_money($uid,$get_redpack,'App中获取平台红包');
			}

			if(!$result['error_code']){
				M('Redpack_cycle')->where(array('time'=>$time))->setInc('had_pull',$get_redpack);

				return array('error_code'=>0,'msg'=>'领取成功','money'=>$get_redpack,'url'=>C('config.site_url').'/wap.php?c=My&a=redpack_list');
			}else{
				return array('error_code'=>1,'msg'=>'领取失败，请联系管理员');
			}
		}else{
			return array('error_code'=>1,'msg'=>'当前周期不能领取红包');
		}
	}

	//当前周期开始结束
	public  function get_cycle_time(){
		if( $this->redpack_cycle_time==0){
			return 0;
		}
		$time_long = time()-$this->start_redpack_time;
		$cycle_time_m = $this->redpack_cycle_time*3600;
		$cycle_count = $time_long%$cycle_time_m>0?intval($time_long/$cycle_time_m)+1:intval($time_long/$cycle_time_m);

		$return['now_start_time'] = ($cycle_count-1)*$cycle_time_m+$this->start_redpack_time;
		$return['now_end_time'] = $cycle_count*$cycle_time_m+$this->start_redpack_time;
		$return['next_start_time'] = $cycle_count*$cycle_time_m+$this->start_redpack_time;
		$return['next_end_time'] = ($cycle_count+1)*$cycle_time_m+$this->start_redpack_time;
		if($cycle_count-1 <=0 ){
			$return['prev_start_time'] = 0;
			$return['prev_end_time'] = 0;
		}else{
			$return['prev_start_time'] = ($cycle_count-2)*$cycle_time_m+$this->start_redpack_time;
			$return['prev_end_time'] = ($cycle_count-1)*$cycle_time_m+$this->start_redpack_time;
		}
		return $return;
	}

}
?>