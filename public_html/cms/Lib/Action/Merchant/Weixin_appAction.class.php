<?php
/*
 * 小程序授权
 *
 * @  Writers    Jaty
 * @  BuildTime  2017/09/25 10:12
 * 
 */
class Weixin_appAction extends BaseAction{
	protected function get_bind(){
		$database_app_bind = D('Weixin_app_bind');
		$condition_app_bind['bind_type'] = '0';
		$condition_app_bind['other_id'] = $this->mer_id;
		$now_bind = $database_app_bind->where($condition_app_bind)->find();
		return $now_bind;
	}	
    public function index(){
		$now_bind = $this->get_bind($now_bind);
		
		$go_url = 'https://o2o-service.pigcms.com/wxapp/authorize.php?domain='.$_SERVER['SERVER_NAME'].'&label='.$this->mer_id.'&type=0&auth_back='.urlencode($this->config['site_url'].'/merchant.php?c=Weixin_app&a=auth_back');
		$this->assign('go_url',$go_url);
			
		if($now_bind['authorizer_appid']){
			import('ORG.Net.Http');
			$url = 'https://o2o-service.pigcms.com/wxapp/wxapp.json';
			$next_bind_json = Http::curlGet($url);
			
			$new_bind = json_decode($next_bind_json,true);
			
			$tester_list = D('Weixin_app_tester')->where(array('bind_id'=>$now_bind['bind_id']))->order('`tester_id` ASC')->select();
			
			//如果提交版本大于当前版本，请求微信校验。
			if($now_bind['update_version_id'] > $now_bind['now_version_id']){
				$result2 = $this->authorizer_access_token($now_bind);
				$url = 'https://o2o-service.pigcms.com/wxapp/get_latest_auditstatus.php';
				$post_param['authorizer_access_token'] = $result2['authorizer_access_token'];
				$audit_result = Http::curlPost($url, $post_param);
				if($audit_result['status'] == 0){
					$data_bind['now_version'] = $now_bind['update_version'];
					$data_bind['now_version_id'] = $now_bind['update_version_id'];
					$data_bind['update_version'] = '';
					$data_bind['update_version_id'] = '0';
					$data_bind['update_error_tip'] = '';
					M('Weixin_app_bind')->where(array('bind_id'=>$now_bind['bind_id']))->data($data_bind)->save();
					$now_bind = $this->get_bind($now_bind);
				}else if($audit_result['status'] == 1){
					$data_bind['update_version'] = '';
					$data_bind['update_version_id'] = '0';
					$data_bind['update_error_tip'] = $audit_result['reason'];
					M('Weixin_app_bind')->where(array('bind_id'=>$now_bind['bind_id']))->data($data_bind)->save();
					$now_bind = $this->get_bind($now_bind);
				}
				$this->assign('audit_result',$audit_result);
			}
			
			$this->assign('now_bind',$now_bind);
			$this->assign('new_bind',$new_bind);
			$this->assign('tester_list',$tester_list);
			
			$this->display('app_info');
		}else{
			$this->display();
		}
    }
	public function auth_back(){
		$database_app_bind = D('Weixin_app_bind');
		$condition_app_bind['bind_type'] = '0';
		$condition_app_bind['other_id'] = $this->mer_id;
		$data_app_bind['authorizer_appid'] = $_GET['authorizer_appid'];
		$data_app_bind['appid'] = $_GET['authorizer_appid'];
		$data_app_bind['authorizer_access_token'] = $_GET['authorizer_access_token'];
		$data_app_bind['authorizer_refresh_token'] = $_GET['authorizer_refresh_token'];
		if($database_app_bind->where($condition_app_bind)->find()){	
			$database_app_bind->where($condition_app_bind)->data($data_app_bind)->save();
		}else{
			$data_app_bind['bind_type'] = '0';
			$data_app_bind['other_id'] = $this->mer_id;
			$database_app_bind->data($data_app_bind)->add();
		}
		redirect(U('index'));
	}
	protected function authorizer_access_token($now_bind){
		import('ORG.Net.Http');
		$url = 'https://o2o-service.pigcms.com/wxapp/api_authorizer_token.php';
		$data = array('authorizer_appid'=>$now_bind['authorizer_appid'],'authorizer_refresh_token'=>$now_bind['authorizer_refresh_token']);
		$result2 = Http::curlPost($url, $data);
		
		if($result2['authorizer_access_token']){
			$condition_app_bind['bind_id'] = $now_bind['bind_id'];
			$data_app_bind['authorizer_access_token'] = $result2['authorizer_access_token'];
			$data_app_bind['authorizer_refresh_token'] = $result2['authorizer_refresh_token'];
			D('Weixin_app_bind')->where($condition_app_bind)->data($data_app_bind)->save();	
		}
		
		return $result2;
	}
	public function add_tester(){
		import('ORG.Net.Http');
		$now_bind = $this->get_bind();
		$result2 = $this->authorizer_access_token($now_bind);
		if($result2['authorizer_access_token']){
			$url = 'https://o2o-service.pigcms.com/wxapp/bind_tester.php';
			$data = array('wechatid'=>$_POST['wechatid'],'authorizer_access_token'=>$result2['authorizer_access_token']);
			$result3 = Http::curlPost($url, $data);
			if($result3['errcode'] == '85001'){
				$this->error('微信号不存在或微信号设置为不可搜索');
			}else if($result3['errcode'] == '85002'){
				$this->error('小程序绑定的体验者数量达到上限');
			}else if($result3['errcode'] == '85003'){
				$this->error('微信号绑定的小程序体验者达到上限');
			}
			
			$data_weixin_app_tester['bind_id'] = $now_bind['bind_id'];
			$data_weixin_app_tester['wechatid'] = $_POST['wechatid'];
			$data_weixin_app_tester['wechatname'] = $_POST['wechatname'];
			$data_weixin_app_tester['add_time'] = time();
			if(M('Weixin_app_tester')->data($data_weixin_app_tester)->add()){
				$this->success('绑定成功');
			}else{
				$this->error('绑定失败，请重试');
			}
		}else{
			$this->error('绑定失败，请重试');
		}
	}
	public function unbind_tester(){
		import('ORG.Net.Http');
		$now_bind = $this->get_bind();
		
		$now_tester = M('Weixin_app_tester')->where(array('bind_id'=>$now_bind['bind_id'],'tester_id'=>$_GET['id']))->find();
		
		
		$result2 = $this->authorizer_access_token($now_bind);
		if($result2['authorizer_access_token']){
			$url = 'https://o2o-service.pigcms.com/wxapp/unbind_tester.php';
			$data = array('wechatid'=>$_POST['wechatid'],'authorizer_access_token'=>$result2['authorizer_access_token']);
			$result3 = Http::curlPost($url, $data);
			if($result3 && $result3['errcode'] == '0'){
				$condition_weixin_app_tester['tester_id'] = $now_tester['tester_id'];
				if(M('Weixin_app_tester')->where($condition_weixin_app_tester)->delete()){
					$this->success('解绑成功');
				}else{
					$this->error('解绑失败，请重试');
				}
			}else if($result3['errcode'] == '-1'){
				$this->error($result3['errmsg'].'，管理员无法解除绑定');
			}else{
				$this->error('解绑失败，请重试');
			}
		}else{
			$this->error('解绑失败，请重试');
		}
	}
	public function commit_version(){
		import('ORG.Net.Http');
		$now_bind = $this->get_bind();
		$result2 = $this->authorizer_access_token($now_bind);
		$ext_json_arr = array(
			'extEnable' => true,
			'extAppid' => $now_bind['authorizer_appid'],
			'ext'  => array(
				'mer_id' => $this->mer_id,
				'mer_name' => $this->merchant_session['name'],
				'http_domain' => str_replace('http://','https://',$this->config['site_url']).'/',
			),
		);
		$url = 'https://o2o-service.pigcms.com/wxapp/commit.php';
		$post_param['ext_json'] = json_encode($ext_json_arr);
		$post_param['authorizer_access_token'] = $result2['authorizer_access_token'];
		$result3 = Http::curlPost($url, $post_param);
		
		if($result3['errcode'] == 0){
			$data_bind['commit_version'] = $result3['now_version'];
			$data_bind['commit_version_id'] = $result3['now_version_id'];
			$data_bind['commit_time'] = time();
			if(M('Weixin_app_bind')->where(array('bind_id'=>$now_bind['bind_id']))->data($data_bind)->save()){
				$this->success('提交成功',U('index'));
			}else{
				$this->error('提交保存失败，请重试');
			}
		}else{
			$this->error('提交失败，错误原因：'.$result3['errmsg']);
		}
	}
	public function get_qrcode(){
		import('ORG.Net.Http');
		$now_bind = $this->get_bind();
		$result2 = $this->authorizer_access_token($now_bind);
		
		$url = 'https://o2o-service.pigcms.com/wxapp/get_qrcode.php';
		$post_param['authorizer_access_token'] = $result2['authorizer_access_token'];
		$result3 = Http::curlPost($url, $post_param);
		$this->success($result3['qrcode_url']);
	}
	public function get_category(){
		import('ORG.Net.Http');
		$now_bind = $this->get_bind();
		$result2 = $this->authorizer_access_token($now_bind);
		
		$url = 'https://o2o-service.pigcms.com/wxapp/get_category.php';
		$post_param['authorizer_access_token'] = $result2['authorizer_access_token'];
		$result3 = Http::curlPost($url, $post_param);
		
		$this->success($result3['category_list']);
	}
	public function submit_audit(){
		import('ORG.Net.Http');
		$now_bind = $this->get_bind();
		$result2 = $this->authorizer_access_token($now_bind);
		
		$category_arr = explode('||',$_POST['category']);
		
		$post_param = array(
			'authorizer_access_token' => $result2['authorizer_access_token'],
			'item_list' => array(
				array(
					'address' => 'pages/merchant/index',
					'tag' => trim($_POST['tag']),
					'first_class' => $category_arr[3],
					'second_class' => $category_arr[4],
					'third_class' => $category_arr[5] != 'undefined' ? $category_arr[5] : '',
					'first_id' => $category_arr[0],
					'second_id' => $category_arr[1],
					'third_id' => $category_arr[2] != 'undefined' ? $category_arr[2] : '',
					'title'		=> '首页'
				),
			),
		);
		$post_param['item_list'] = json_encode($post_param['item_list']);
		$url = 'https://o2o-service.pigcms.com/wxapp/submit_audit.php';
		$result3 = Http::curlPost($url, $post_param);

		if($result3['errcode'] == 0 || $result3['errcode'] == 85009){
			$data_bind['update_version'] = $_POST['new_version'];
			$data_bind['update_version_id'] = $_POST['new_version_id'];
			$data_bind['update_time'] = time();
			M('Weixin_app_bind')->where(array('bind_id'=>$now_bind['bind_id']))->data($data_bind)->save();
			$this->success('提交审核成功，请耐心等待微信官方审核');
		}else{
			$this->error('提交出现错误，微信返回内容：'.$result3['errmsg']);
		}
	}
	public function get_setting(){
		$now_bind = $this->get_bind();
		$diypage_list = M('Merchant_store_diypage')->field('`page_id`,`page_name`')->where(array('mer_id'=>$this->mer_id,'is_remove'=>'0'))->select();
		
		$return['diypage_list'] = $diypage_list;
		$return['appid'] = $now_bind['appid'];
		$return['appsecret'] = $now_bind['appsecret'];
		$return['wxpay_merid'] = $now_bind['wxpay_merid'];
		$return['wxpay_key'] = $now_bind['wxpay_key'];
		$return['mer_index_page'] = $now_bind['mer_index_page'];
		$this->success($return);
	}
	public function set_setting(){
		$now_bind = $this->get_bind();
		$data_bind['mer_index_page'] = $_POST['mer_index_page'];
		$data_bind['appsecret'] = $_POST['appsecret'];
		$data_bind['wxpay_merid'] = $_POST['wxpay_merid'];
		$data_bind['wxpay_key'] = $_POST['wxpay_key'];
		if(M('Weixin_app_bind')->where(array('bind_id'=>$now_bind['bind_id']))->data($data_bind)->save()){
			$this->success('编辑成功');
		}else{
			$this->error('编辑失败，请先检查有没有修改过值后再提交修改');
		}
	}
	public function release_version(){
		import('ORG.Net.Http');
		$now_bind = $this->get_bind();
		$result2 = $this->authorizer_access_token($now_bind);
		$url = 'https://o2o-service.pigcms.com/wxapp/release.php';
		$post_param['authorizer_access_token'] = $result2['authorizer_access_token'];
		$result3 = Http::curlPost($url, $post_param);

		if($result3['errcode'] == 0){
			$data_bind['commit_version'] = $result3['now_version'];
			$data_bind['commit_version_id'] = $result3['now_version_id'];
			$data_bind['commit_time'] = time();
			if(M('Weixin_app_bind')->where(array('bind_id'=>$now_bind['bind_id']))->data($data_bind)->save()){
				$this->success('提交成功',U('index'));
			}else{
				$this->error('提交保存失败，请重试');
			}
		}else{
			$this->error('提交失败，错误原因：'.$result3['errmsg']);
		}
	}
	public function modify_domain(){
		import('ORG.Net.Http');
		$now_bind = $this->get_bind();
		$result2 = $this->authorizer_access_token($now_bind);
		$url = 'https://o2o-service.pigcms.com/wxapp/modify_domain.php';
		$post_param['domain'] = $_SERVER['HTTP_HOST'];
		$post_param['authorizer_access_token'] = $result2['authorizer_access_token'];
		$result3 = Http::curlPost($url, $post_param);
		dump($result3);
	}
}