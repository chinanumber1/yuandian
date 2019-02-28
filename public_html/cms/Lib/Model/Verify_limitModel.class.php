<?php
	class Verify_limitModel extends Model{
		public function check_false_times($uid,$time_limit=3600){
			$where['uid'] = $uid;
			$res = $this->field(true)->where($where)->find();
			$times = $res['times'];
			if($res['end_time']!=0&&$res['end_time']<time()) {
				$this->where($where)->delete();
				$times = 0;
			}
			if($times>=2||$res['end_time']>=time()){
				if($res['end_time']==0){
					$data['end_time'] = $end_time=time()+$time_limit;
					$this->where($where)->save($data);
				}else{
					$end_time = $res['end_time'];
				}
				return array('error_code'=>true,'times'=>0,'end_time'=>$end_time,'msg'=>'功能锁定！1小时后重试');
			}elseif($times==0) {
				$data['uid'] = $uid;
				$data['type'] = 1;
				$data['times'] = 1;
				$this->add($data);
			}else{
				$data['times'] = $times+1;
				$this->where($where)->save($data);
			}
			$times++;
			return array('error_code'=>true,'times'=>$times,'msg'=>'密码错误!你还有'.(3-$times).'次机会');
		}
	}
	
	