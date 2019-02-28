<?php
/*
 * 社区APP版	登录
 *
 */
class House_loginAction extends BaseAction{
	//	登录
    public function login(){
    	$ticket = I('ticket', false);
        $database_house = D('House_village');
        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info) {
                $uid = $info['uid'];
                $now_house = $database_house->field(true)->where(array('village_id'=>$uid))->find();
                unset($now_house['pwd']);
                $arr	=	array(
                	'user'      => $now_house,
                    'ticket'    => $ticket
                );
                $this->returnCode(0,$arr);
            }else{
				$this->returnCode('20000009');
            }
        }
		$condition_house['account'] = I('account');
		if($_POST['version_code']>=300){
			$village_id = $_POST['village_id'];

			if($village_id>0){
				$condition_house['village_id'] = $village_id;
			}
			$now_house = $database_house->field(true)->where($condition_house)->select();
			if(count($now_house)==1){
				$now_house = $now_house[0];
			}else{
				$this->returnCode(0,array('village_list'=>$now_house));
			}
		}else{
			$now_house = $database_house->field(true)->where($condition_house)->find();
		}
		if(empty($now_house)){
			$this->returnCode('20000005');
		}
		$pwd = md5(I('pwd'));
		if($pwd != $now_house['pwd']){
			$this->returnCode('20000006');
		}
		if($now_house['status'] == 2){
			$this->returnCode('20000007');
		}
		unset($now_house['pwd']);
		$village_id	=	$now_house['village_id']+10000000;
		$ticket = ticket::create($village_id, $this->DEVICE_ID, true);
        $arr	=	array(
			'user'	=>	$now_house,
			'ticket'	=>	$ticket['ticket'],
        );
		$data_house['village_id'] = $now_house['village_id'];
		$data_house['last_time'] = $_SERVER['REQUEST_TIME'];
		if($database_house->data($data_house)->save()){
			$this->returnCode(0,$arr);
		}else{
			$this->returnCode('20000008');
		}
	}
}