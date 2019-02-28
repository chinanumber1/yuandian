<?php
/*
 * APP云打包
 */

class AppAction extends BaseAction{
	public $database_appyundabao;
	public $database_appyundabao_list;
	public $now_app;
	public $createUrl = 'http://app-service.weihubao.com/api/create.php';
	public $createAppUrl = 'http://app-service.weihubao.com/api/createApp.php';
	public $getAppUrl = 'http://app-service.weihubao.com/api/getApp.php';
	public $downAppUrl = 'http://app-service.weihubao.com/api/downApp.php';
	protected function _initialize(){
		parent::_initialize();
		$this->database_appyundabao = D('Appyundabao');
		$this->database_appyundabao_list = D('Appyundabao_list');
		$this->now_app = $this->database_appyundabao->field(true)->where(array('type'=>'merchant','third_id'=>$this->merchant_session['mer_id']))->find();
	}
    public function index(){
		if(empty($this->now_app)){
			$hostArray = parse_url(C('config.site_url'));
			$data = array(
				'domain' => $hostArray['host'],
				'label' => 'merchant_'.$this->merchant_session['mer_id'],
				'from' => '2',
			);
			$create_result = $this->yundabaoapi('create',$data);
			if(!empty($create_result['app_id']) && !empty($create_result['app_key'])){
				if(!$this->database_appyundabao->data(array('type'=>'merchant','third_id'=>$this->merchant_session['mer_id'],'app_id'=>$create_result['app_id'],'app_key'=>$create_result['app_key']))->add()){
					$this->error('初始化App云打包失败');
				}
			}else{
				$this->error($create_result['err_msg']);
			}
		}else{
			$condition_group['mer_id'] = $this->merchant_session['mer_id'];
			$app_count = $this->database_appyundabao_list->where(array('parent_id'=>$this->now_app['pigcms_id']))->count();
			import('@.ORG.merchant_page');
			$p = new Page($app_count, 20);
			$app_list = $this->database_appyundabao_list->field(true)->where(array('parent_id'=>$this->now_app['pigcms_id']))->order('`pigcms_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
			$this->assign('pagebar',$p->show());
		
			$this->assign('app_list',$app_list);
		}
    	$this->display();
    }
	public function download(){
		$now_app = $this->database_appyundabao_list->field(true)->where(array('pigcms_id'=>$_GET['id']))->find();
		switch($now_app['status']){
			case '0':
				$get_result = $this->yundabaoapi('getApp',array('web_app_id'=>$now_app['web_app_id']));
				if($get_result['err_code'] == 0 && $get_result['okTime']){
					$this->database_appyundabao_list->data(array('pigcms_id'=>$now_app['pigcms_id'],'status'=>'1','okTime'=>$get_result['okTime']))->save();
					$downloadUrl = $this->downAppUrl.'?id='.$now_app['web_app_id'];
				}else if($get_result['err_code'] == 1002){
					$this->database_appyundabao_list->data(array('pigcms_id'=>$now_app['pigcms_id'],'status'=>'2','err_result'=>$get_result['err_msg']))->save();
					$err_msg = '应用打包失败！返回原因：'.$get_result['err_msg'];
				}else if($get_result['err_code'] == 1001){
					if($get_result['err_msg']['lineUp']){
						$err_msg = $get_result['err_msg']['msg'].'<br/>前面还有'.$get_result['err_msg']['lineUp'].'个应用正在排队打包，大约还需要'.$get_result['err_msg']['waitTime'].'秒。';
					}else{
						$err_msg = '该应用正在打包中，请稍候后再试。应用打包大概需要20秒。';
					}
				}else{
					$err_msg = $get_result['err_msg'];
				}
				break;
			case '1':
				$downloadUrl = $this->downAppUrl.'?id='.$now_app['web_app_id'];
				break;
			case '2':
				$err_msg = '应用打包失败！返回原因：'.$now_app['err_result'];
				break;
		}
		$this->assign('now_app',$now_app);
		$this->assign('downloadUrl',$downloadUrl);
		$this->assign('err_msg',$err_msg);
		$this->display();
	}
	
	public function add(){
		if(IS_POST){
			$ext_name = strrchr($_POST['icoPic'],'.');
			if($ext_name != '.png'){
				$this->error('请上传png格式的图标');
			}
			$ext_name = strrchr($_POST['helloPic'],'.');
			if($ext_name != '.png'){
				$this->error('请上传png格式的欢迎图');
			}
			$post_data = array(
				'name' 		=> $_POST['name'],
				'intro' 	=> $_POST['intro'],
				'webUrl' 	=> htmlspecialchars_decode($_POST['webUrl']),
				'appType' 	=> $_POST['appType'],
				'icoPic' 	=> $this->config['site_url'].$_POST['icoPic'],
				'helloPic' 	=> $this->config['site_url'].$_POST['helloPic'],
				'hideTop' 	=> $_POST['hideTop'],
				'screen' 	=> $_POST['screen'],
			);
			$post_data['app_id'] = $this->now_app['app_id'];
			$post_data['key'] = $this->get_encrypt_key($post_data,$this->now_app['app_key']);
			$create_result = $this->yundabaoapi('createApp',$post_data);
			if(!empty($create_result['web_app_id'])){
				$data_list = $post_data;
				$data_list['parent_id'] = $this->now_app['pigcms_id'];
				$data_list['addTime']   = $_SERVER['REQUEST_TIME'];
				$data_list['status']    = '0';
				$data_list['web_app_id']    = $create_result['web_app_id'];
				$list_id = $this->database_appyundabao_list->data($data_list)->add();
				if(empty($list_id)){
					$this->error('应用创建失败，请重试');
				}else{
					$this->success('应用创建请求成功');
				}
			}else{
				$this->error('应用创建失败，请重试！错误原因'.$create_result['err_msg']);
			}
		}else{
			$this->display();
		}
	}
	public function ajax_upload_pic(){
        if ($_FILES['imgFile']['error'] != 4){
			$param = array('size' => 2);
            $param['thumb'] = false;
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'appyundabao', 1, $param,false);
			if ($image['error']) {
				exit(json_encode(array('error' => 1,'message' =>$image['msg'])));
			} else {
				$url = $image['url']['imgFile'];
				exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $url)));
			}
		}else{
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
	//云打包api执行
	protected function yundabaoapi($type,$data=array()){
		switch($type){
			case 'create':
				return $this->yundabao($this->createUrl,$data);
			case 'createApp':
				return $this->yundabao($this->createAppUrl,$data);
			case 'getApp':
				return $this->yundabao($this->getAppUrl,$data);
			default:
				return false;
		}
	}
	protected function yundabao($url,$data){
		// echo $url;exit;
		import('ORG.Net.Http');
		$http = new Http();
		return Http::curlPost($url,$data);
	}
	/**
	 *   加密串
	 */
	protected function get_encrypt_key($array,$app_key){
		$new_arr = array();
		ksort($array);
		foreach($array as $key=>$value){
			$new_arr[] = $key.'='.$value;
		}
		$new_arr[] = 'app_key='.$app_key;
		
		$string = implode('&',$new_arr);
		return md5($string);
	}
}