<?php
/*
 * 关于我们
 *
 */
class AppRedpackAction extends BaseAction {
	public function get_redpack(){
		$ticket = I('ticket', false);
		if ($ticket) {
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			$this->user_session['uid'] = $info['uid'];
		}

		if(empty($this->user_session)){
			$this->returnCode('20044010');
		}

		$result = D('LotteryMarketing')->get_redpack($this->user_session['uid']);
		if(!$result['error_code']){
			unset($result['error_code']);

			$result['avatar'] = $this->config['redpack_img'];
			$this->returnCode(0,$result);
		}else{
			$this->returnCode(10052016,'',$result['msg']);
		}
	}

}