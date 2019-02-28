<?php

class TemplateMsgAction extends BaseAction{

	public function __construct(){
		parent::__construct();
	}

	public function cat_list(){
		$cat_list = M('Tempmsg_cat')->order('`cat_id` ASC')->select();
		$this->assign('cat_list', $cat_list);
		
		$this->display();
	}
	public function index(){
		if(IS_POST){
			$data = array();
			$data['cat_id'] = $_REQUEST['cat_id'];
			$data['tempkey'] = $_REQUEST['tempkey'];
			$data['name'] = $_REQUEST['name'];
			$data['content'] = $_REQUEST['content'];
			$data['topcolor'] = $_REQUEST['topcolor'];
			$data['textcolor'] = $_REQUEST['textcolor'];
			$data['status'] = $_REQUEST['status'];
			$data['tempid'] = $_REQUEST['tempid'];
			$data['wxapp_id'] = $_REQUEST['wxapp_id'];
			$data['wxapp_tempid'] = $_REQUEST['wxapp_tempid'];
			
			foreach ($data as $key => $val){
				foreach ($val as $k => $v){
					$info[$k][$key] = $v;
				}
			}
			foreach ($info as $kk => $vv){
				if($vv['tempid'] == ''){
					$info[$kk]['status'] = 0;
				}
// 				$info[$kk]['token'] = session('token');
				$where = array('tempkey'=>$info[$kk]['tempkey'], 'mer_id' => 0);

				if(M('Tempmsg')->where($where)->getField('id')){
					M('Tempmsg')->where($where)->save($info[$kk]);
				}else{
					M('Tempmsg')->add($info[$kk]);
				}
			}
			$this->success('操作成功');
		} else {
			$model = new templateNews();
			$templs = $model->templates();
			
			if($this->config['single_system']){
				unset($templs['TM00785']);
				unset($templs['TM01008']);
				unset($templs['OPENTM205984119']);
				unset($templs['OPENTM414860535']);
				unset($templs['OPENTM408101810']);
			}

			$where = array('mer_id' => 0);
			$list = M('Tempmsg')->where($where)->field(true)->select();
			$data = array();
			foreach ($list as $row) {
				$data[$row['tempkey']] = $row;
			}
			
			$result = array();
			foreach ($templs as $k => $v){			
				$temp = $v;
				if($_GET['cat_id'] && $temp['cat_id'] != $_GET['cat_id']){
					continue;
				}
				if (isset($data[$k])) {
					$temp = $data[$k];
					$temp['name'] = $v['name'];
					$temp['content'] = $v['content'];
					$temp['wxapp_id'] = $v['wxapp_id'];
					$temp['wxapp_content'] = $v['wxapp_content'];
					$temp['cat_id'] = $v['cat_id'];
				} else {
					$temp['tempkey'] = $k;
					$temp['name'] = $v['name'];
					$temp['content'] = $v['content'];
					$temp['wxapp_id'] = $v['wxapp_id'];
					$temp['wxapp_content'] = $v['wxapp_content'];
					$temp['topcolor'] = '#029700';
					$temp['textcolor'] = '#000000';
					$temp['status'] = 0;
					$temp['cat_id'] = $v['cat_id'];
				}
				$result[] = $temp;
			}
			// dump($result);
			$this->assign('list', $result);
			$this->display();
		}
	}
	public function getWxappTemplateID(){
		$tempkey = isset($_POST['tempkey']) ? htmlspecialchars($_POST['tempkey']) : '';
		if(empty($tempkey)){
			$this->error('模板ID不存在');
		}
		$model = new templateNews();
		$templs = $model->templates();
		$nowTmp = $templs[$tempkey];
		
		if(empty($nowTmp['wxapp_id'])){
			$this->error('小程序模板暂不支持');
		}
		
		//选择模板消息
		$access_token_array = D('Access_token_wxapp_expires')->get_access_token();
		if ($access_token_array['errcode']) {
			$this->error('获取access_token发生错误：错误代码' . $access_token_array['errcode'] .',微信返回错误信息：' . $access_token_array['errmsg']);
		}
		$access_token = $access_token_array['access_token'];
		$send_to_url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/library/get?access_token='.$access_token;
		$postData = array(
			'id'=>$nowTmp['wxapp_id']
		);
		$rt = $this->curlPost($send_to_url, json_encode($postData));
		$tmpKeyListArr = json_decode($rt,true);
		if($tmpKeyListArr['errcode']){
			$this->error('选择模板消息时，微信返回错误：'.$template_id_arr['errmsg']);
		}
		$keyListArr = array();
		foreach($tmpKeyListArr['keyword_list'] as $value){
			$keyListArr[$value['name']] = $value['keyword_id'];
		}
		$keyArr = array();
		$conArr = explode(PHP_EOL,$nowTmp['wxapp_content']);
		foreach($conArr as $value){
			$tmp = explode(' ',$value);
			if(count($tmp) == 2){
				$tmp[0] = trim($tmp[0]);
				if(empty($keyListArr[$tmp[0]])){
					$this->error('模板疑似损坏，请寻找技术支持');
				}
				$keyArr[] = $keyListArr[$tmp[0]];
			}
		}
		if(empty($keyArr)){
			$this->error('模板疑似损坏，请寻找技术支持');
		}
		
		//添加模板消息
		$send_to_url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/add?access_token='.$access_token;
		$postData = array(
			'id'				=>	$nowTmp['wxapp_id'],
			'keyword_id_list'	=>	$keyArr,
		);
		$rt = $this->curlPost($send_to_url, json_encode($postData));
		$template_id_arr = json_decode($rt,true);
		if($template_id_arr['errcode']){
			$this->error('添加模板消息时，微信返回错误：'.$template_id_arr['errmsg']);
		}
		$this->success($template_id_arr['template_id']);
	}
	public function getTemplateID()
	{
		$tempkey = isset($_POST['tempkey']) ? htmlspecialchars($_POST['tempkey']) : '';
		
		$access_token_array = D('Access_token_expires')->get_access_token();
		if ($access_token_array['errcode']) {
			$this->error('获取access_token发生错误：错误代码' . $access_token_array['errcode'] .',微信返回错误信息：' . $access_token_array['errmsg']);
		}
		$access_token = $access_token_array['access_token'];
		
		$send_to_url = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token='.$access_token;
		
		import('ORG.Net.Http');
		
		$rt = $this->curlPost($send_to_url, '{"template_id_short":"' . $tempkey . '"}');
		exit($rt);
	}
	
	private function curlPost($url, $data, $timeout=15){
		$ch = curl_init();
		$headers[] = "Accept-Charset: utf-8";//"Content-Type: multipart/form-data; boundary=" .  uniqid('------------------');
		// 		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch ,CURLOPT_TIMEOUT ,$timeout);
		$result = curl_exec($ch);
	
		//关闭curl
		curl_close($ch);
		// echo $result;exit;
// 		$result = json_decode($result, true);
		return $result;
	}
}