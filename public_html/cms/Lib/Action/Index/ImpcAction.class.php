<?php
/*
 * 异步加载评论
 *
 */
class ImpcAction extends BaseAction{
    public function index(){
		$this->display();
    }
	public function get_qrcode(){
		$qrcode_return = D('Recognition')->get_im_qrcode();	
		echo json_encode($qrcode_return);
	}
	public function ajax_weixin_login() {
        for ($i = 0; $i < 6; $i++) {
            $database_im_qrcode = M('Im_qrcode');
            $condition_im_qrcode['id'] = $_GET['qrcode_id'];
            $now_qrcode = $database_im_qrcode->field('`uid`')->where($condition_im_qrcode)->find();
            if (!empty($now_qrcode['uid'])) {
                if ($now_qrcode['uid'] == -1) {
                    $data_im_qrcode['uid'] = 0;
                    $database_im_qrcode->where($condition_im_qrcode)->data($data_im_qrcode)->save();
                    $this->error('reg_user');
                }
                $database_im_qrcode->where($condition_im_qrcode)->delete();
                $result = D('User')->autologin('uid', $now_qrcode['uid']);
                if (empty($result['error_code'])) {
                    session('user', $result['user']);
                    $this->success($result['user']['avatar']);
                } else if ($result['error_code'] == 1001) {
                    $this->error('no_user');
                } else if ($result['error_code']) {
                    $this->error('false');
                }
            }
            if ($i == 5) {
                $this->error('false');
            }
            sleep(3);
        }
    }
	public function redirect(){
		if(empty($this->config['im_appid'])){
			$this->error('系统未开启此功能');
		}
		if(empty($this->user_session)){
			$this->assign('jumpUrl',U('index'));
			$this->error('请先登录');
		}
		if(empty($this->user_session['openid'])){
			$this->assign('jumpUrl',U('index'));
			$this->error('您没有使用过微信');
		}
		$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $this->user_session['openid']), $this->config['im_appkey']);
		$kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $this->user_session['openid'] . '&key=' . $key;
		
		if($_SERVER['REQUEST_SCHEME'] == 'https'){
			redirect($kf_url);die;
		}
		
		$this->assign('kf_url', $kf_url);
		$this->display();
	}
}