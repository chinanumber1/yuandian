<?php

class UpdateAction extends BaseAction{
	public function create_wxapp_page(){
		if(IS_POST){
			echo '<!DOCTYPE html><html lang="zh-CN"><head><meta charset="utf-8" /><title>生成小程序原生页面二维码</title></head><body><img src="'.U('Recognition_wxapp/create_page_qrcode',array('page'=>urlencode(htmlspecialchars_decode($_POST['page'].($_POST['param'] ? '?'.$_POST['param'] : ''))))).'"/><br/><br/>小程序链接（可用于自定义菜单）：<span style="color:red;">'.htmlspecialchars_decode($_POST['page'].($_POST['param'] ? '?'.$_POST['param'] : '')).'</span><br/><br/><a href=" ">重新填写</a>
			</body></html>';
		}else{
			echo '<!DOCTYPE html><html lang="zh-CN"><head><meta charset="utf-8" /><title>生成小程序原生页面二维码</title></head><body>原生页面 | <a href="'.U('create_wxapp_url').'" style="color:blue;">网页页面</a><br/><br/><form method="post">选择页面：<select name="page"><option value="/pages/index/index">平台首页</option><option value="/pages/shop_new/index/index">快店首页</option><option value="/pages/merchant/index">商家列表</option><option value="/pages/my/index">个人中心</option></select><br/><br/><input name="param" placeholder="原生页面地址参数" style="width:600px;height:30px;line-height:30px;"/><br/><br/><input type="submit" value="生成"/></form></body></html>';
		}
	}
	public function create_wxapp_url(){
		if(IS_POST){
      $qr_path = '/pages/index/index?redirect=webview&webview_url='.urlencode(htmlspecialchars_decode($_POST['webview_url'])).'&webview_title='.urlencode($_POST['webview_title']);

			echo '<!DOCTYPE html><html lang="zh-CN"><head><meta charset="utf-8" /><title>生成小程序二维码</title></head><body><img src="'.U('Recognition_wxapp/create_url_qrcode',array('webview_url'=>urlencode(htmlspecialchars_decode($_POST['webview_url'])),'webview_title'=>urlencode($_POST['webview_title']))).'"/><br/><br/>小程序链接（可用于自定义菜单）：<span style="color:red;">'.$qr_path.'</span><br/><br/><a href=" ">重新填写</a>
			</body></html>';
		}else{
			echo '<!DOCTYPE html><html lang="zh-CN"><head><meta charset="utf-8" /><title>生成小程序二维码</title></head><body><a href="'.U('create_wxapp_page').'" style="color:blue;">原生页面</a> | 网页页面<br/><br/><form method="post"><input name="webview_title" placeholder="标题，打开小程序先显示这个标题，然后页面加载完成之后会调整为页面标题" style="width:600px;height:30px;line-height:30px;"/><br/><br/><input name="webview_url" placeholder="H5页面的网址" style="width:600px;height:30px;line-height:30px;"/><br/><br/><input type="submit" value="生成"/></form></body></html>';
		}
	}

	public function get_union_id(){
		if(empty($_GET['now_uid'])){
			$_GET['now_uid'] = 0;
		}
		$user_list = D('User')->where(array('openid'=>array(array('neq',''),array('NOTLIKE','%~no_use')),'union_id'=>'','uid'=>array('gt',$_GET['now_uid'])))->order('`uid` ASC')->limit(50)->select();
		// echo D('User')->getLastSql();
		if(empty($user_list)){
			exit('ok');
		}
		$error_count = 0;
		$access_token_array = D('Access_token_expires')->get_access_token();
		if (!$access_token_array['errcode']) {
			import('ORG.Net.Http');
			$http = new Http();
			foreach($user_list as $key=>$value){
				$return = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_array['access_token'].'&openid='.$value['openid'].'&lang=zh_CN');
				$userinfo = json_decode($return,true);
				// if($key == 0){
					// dump($value);
					// dump($userinfo);
				// }
				// if($userinfo['errcode']){
					// dump($value);
					// dump($userinfo);
					// die;
				// }
				if($userinfo['unionid']){
					D('User')->where(array('uid'=>$value['uid']))->data(array('union_id'=>$userinfo['unionid']))->save();
				}else{
					$error_count++;
				}
			}
		}
		if($error_count == 50){
			exit('一个都没有获取到，可能没有绑定开放平台！如果确定已绑定，请手动访问以下网址进入下一页：<br/><br/><br/>'.$this->config['site_url'].U('get_union_id',array('now_uid'=>$value['uid'])));
		}
		echo  '处理完一批，正在跳转';
		// if(count($user_list) < 50){
			// exit('ok');
		// }
		echo '<script>location.href = "'.U('get_union_id',array('now_uid'=>$value['uid'])).'";</script>';exit;
	}
}