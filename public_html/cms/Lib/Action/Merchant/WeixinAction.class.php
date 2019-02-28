<?php
class WeixinAction extends BaseAction
{
	
	public function _initialize()
	{
		parent::_initialize();
		if (empty($this->config['is_open_oauth']) && empty($this->merchant_session['is_open_oauth'])) {
			$this->error('你没有这个使用权限', U('Index/index'));
		}
	}
	
	public function index()
	{
		$weixin_bind = array();
		if ($weixin_bind = D('Weixin_bind')->where(array('mer_id' => $this->merchant_session['mer_id'], 'type' => 0))->find()) {
			if ($weixin_bind['service_type_info'] == 0 || $weixin_bind['service_type_info'] == 1) {
				if ($weixin_bind['verify_type_info'] == -1) {
					$weixin_bind['type_info'] = '未认证的订阅号';
				} else {
					$weixin_bind['type_info'] = '认证的订阅号';
				}
			} else {
				if ($weixin_bind['verify_type_info'] == -1) {
					$weixin_bind['type_info'] = '未认证服务号';
				} else {
					$weixin_bind['type_info'] = '认证服务号';
				}
			}
		} else {

			import('ORG.Net.Http');
// 			$result = $_SESSION['component_access_token'];
// 			if ($result && $result[0] > time()) {
// 				$result['component_access_token'] = $result[1];
// 			} else {
				$url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
				$data = array('component_appid' => $this->config['wx_appid'], 'component_appsecret' => $this->config['wx_appsecret'], 'component_verify_ticket' => $this->config['wx_componentverifyticket']);
				$result = Http::curlPost($url, json_encode($data));
				if (empty($result['errcode'])) {
					$_SESSION['component_access_token'] = array($result['expires_in'] + time(), $result['component_access_token']);
				} else {
					//exit(json_encode(array('err_code' => 1, 'err_msg' => '获取授权地址失败')));
				}
// 			}
			$url = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=' . $result['component_access_token'];//
			$data = array('component_appid' => $this->config['wx_appid']);
			$auth_code = Http::curlPost($url, json_encode($data));
			if (empty($auth_code['errcode'])) {
				$url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.$this->config['wx_appid'].'&pre_auth_code='.$auth_code['pre_auth_code'].'&redirect_uri=' . urlencode($this->config['site_url'] . '/merchant.php?g=Merchant&c=Weixin&a=auth_back');
				$this->assign('url', $url);
				//exit(json_encode(array('err_code' => 0, 'err_msg' => $url)));
			} else {
				$this->assign('url', '');
			}
		}
		if($weixin_bind){

			$weixin_bind['qrcode_url'] = $this->config['site_url'].'/index.php?c=Image&a=wx_image_download&url='.urlencode($weixin_bind['qrcode_url']).'&path='.$this->merchant_session['mer_id'];
		}

		$this->assign('bind', $weixin_bind);
		$this->display();
	}
	
// 	public function images()
// 	{
// 		$count = D('Source_material')->where(array('mer_id' => $this->merchant_session['mer_id']))->count('pigcms_id');
// 		import('@.ORG.merchant_page');
// 		$p = new Page($count, 10);
// 		$list = D('Source_material')->where(array('mer_id' => $this->merchant_session['mer_id']))->order('pigcms_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
// 		$it_ids = array();
// 		$temp = array();
// 		foreach ($list as $l) {
// 			foreach (unserialize($l['it_ids']) as $id) {
// 				if (!in_array($id, $it_ids)) $it_ids[] = $id;
// 			}
// 		}
// 		$result = array();
// 		$image_text = D('Image_text')->field('pigcms_id, title')->where(array('pigcms_id' => array('in', $it_ids)))->order('pigcms_id asc')->select();
// 		foreach ($image_text as $txt) {
// 			$result[$txt['pigcms_id']] = $txt;
// 		}
// 		foreach ($list as &$l) {
// 			$l['dateline'] = date('Y-m-d H:i:s', $l['dateline']);
// 			foreach (unserialize($l['it_ids']) as $id) {
// 				$l['list'][] = isset($result[$id]) ? $result[$id] : array();
// 			}
// 		}
// 		$this->assign('list', $list);
// 		$this->assign('page', $p->show());
// 		$this->display();
// 	}
	
// 	public function del_image()
// 	{
// 		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
// 		if ($data = D('Source_material')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
// 			if ($data['type'] == 0) {
// 				$it_ids = unserialize($data['it_ids']);
// 				$id = isset($it_ids[0]) ? intval($it_ids[0]) : 0;
// 				D('Image_text')->where(array('pigcms_id' => $id, 'mer_id' => $this->merchant_session['mer_id']))->delete();
// 			}
// 			D('Source_material')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->delete();
// 			$this->error('删除成功', U('Weixin/images'));
// 		} else {
// 			$this->error('不合法的操作');
// 		}
// 	}
// 	public function one()
// 	{
// 		if (IS_POST) {
// 			$pigcms_id = isset($_POST['pigcms_id']) ? intval($_POST['pigcms_id']) : 0;
// 			$thisid = isset($_POST['thisid']) ? intval($_POST['thisid']) : 0;
// 			$data['content'] = isset($_POST['content']) ? fulltext_filter($_POST['content']) : '';
// 			$data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
// 			$data['author'] = isset($_POST['author']) ? htmlspecialchars($_POST['author']) : '';
// 			$data['url'] = isset($_POST['url']) ? htmlspecialchars($_POST['url']) : '';
// 			$data['url_title'] = isset($_POST['url_title']) ? htmlspecialchars($_POST['url_title']) : '';
// 			$data['cover_pic'] = isset($_POST['cover_pic']) ? htmlspecialchars($_POST['cover_pic']) : '';
// 			$data['digest'] = isset($_POST['digest']) ? htmlspecialchars($_POST['digest']) : '';
// 			$data['is_show'] = isset($_POST['is_show']) ? intval($_POST['is_show']) : 0;
// 			$data['classid'] = isset($_POST['classid']) ? intval($_POST['classid']) : 0;
// 			$data['classname'] = isset($_POST['classname']) ? htmlspecialchars($_POST['classname']) : '';
// 			if (empty($data['title'])) {
// 				$this->error('标题不能为空！');
// 			}
// 			if (empty($data['cover_pic'])) {
// 				$this->error('必须得有封面图！');
// 			}
// 			if (empty($data['content'])) {
// 				$this->error('内容不能为空！');
// 			}
// 			$data['dateline'] = time();
// 			$data['mer_id'] = $this->merchant_session['mer_id'];
// 			if ($pigcms_id && $thisid) {
// 				if (D('Image_text')->where(array('pigcms_id' => $thisid, 'mer_id' => $this->merchant_session['mer_id']))->data($data)->save()) {
// 					D('Source_material')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->data(array('it_ids' => serialize(array($thisid)), 'mer_id' => $this->merchant_session['mer_id'], 'dateline' => time()))->save();
// 					$this->success('编辑成功！');
// 				} else {
// 					$this->error('操作失败稍后重试！');
// 				}
// 			} else {
// 				if ($id = D('Image_text')->data($data)->add()) {
// 					D('Source_material')->data(array('it_ids' => serialize(array($id)), 'mer_id' => $this->merchant_session['mer_id'], 'dateline' => time()))->add();
// 					$this->success('新增成功！');
// 				} else {
// 					$this->error('操作失败稍后重试！');
// 				}
// 			}
			
// 		} else {
// 			$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
// 			$image_text = array('title' => '标题', 'cover_pic' => '', 'author' => '', 'content' => '', 'digest' => '', 'url' => '', 'dateline' => time(), 'pigcms_id' => 0);
// 			if ($data = D('Source_material')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
// 				$it_ids = unserialize($data['it_ids']);
// 				$id = isset($it_ids[0]) ? intval($it_ids[0]) : 0;
// 				$image_text = D('Image_text')->where(array('pigcms_id' => $id, 'mer_id' => $this->merchant_session['mer_id']))->find();
// 			}
// 			$this->assign('pigcms_id', $pigcms_id);
// 			$this->assign('image_text', $image_text);
// 			$this->display();
// 		}
// 	}
// 	public function multi()
// 	{
// 		if (IS_POST) {
// 			$ids = isset($_POST['imgids']) ? htmlspecialchars($_POST['imgids']) : '';
// 			$ids = explode(",", $ids);
// 			if (count($ids) > 10) {
// 				$this->error('最多十条图文');
// 			}
			
// 			$pigcms_id = isset($_POST['pigcms_id']) ? intval($_POST['pigcms_id']) : 0;
// 			if ($pigcms_id && ($data = D('Source_material')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->find())) {
// 				D('Source_material')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->data(array('it_ids' => serialize($ids), 'mer_id' => $this->merchant_session['mer_id'], 'dateline' => time(), 'type' => 1))->save();
// 				$this->success('编辑成功！');
// 			} else {
// 				D('Source_material')->data(array('it_ids' => serialize($ids), 'mer_id' => $this->merchant_session['mer_id'], 'dateline' => time(), 'type' => 1))->add();
// 				$this->success('创建成功！');
// 			}
// 		} else {
// 			$this->display();
// 		}
// 	}
	
	
	public function menu()
	{
		$weixin = D('Weixin_bind')->get_account_type($this->merchant_session['mer_id']);
		if (isset($weixin['code']) && $weixin['code'] > 0) {
			$diymenus = D('Diymenu_class')->where(array('mer_id' => $this->merchant_session['mer_id']))->order('sort ASC')->select();
			$lists = array();
			foreach ($diymenus as $diy) {
				if ($diy['pid']) {
					if (!isset($lists[$diy['pid']]['list'])) {
						$lists[$diy['pid']]['list'] = array(1 => $diy);
					} else {
						$lists[$diy['pid']]['list'][] = $diy;
					}
				} else {
					if (isset($lists[$diy['id']])) {
						$lists[$diy['id']] = array_merge($lists[$diy['id']], $diy);
					} else {
						$lists[$diy['id']] = $diy;
					}
				}
			}
			$dlists = array();
			$i = 0;
			foreach ($lists as $l) {
				$dlists[++$i] = $l;
			}
			$this->assign('dlists', $dlists);
		}
		$this->assign('weixin', $weixin);
		$this->display();
	}
	
	public function savemenu()
	{
		$data = isset($_POST['custommenu']) ? $_POST['custommenu'] : array();

		$diymenus = D('Diymenu_class')->where(array('mer_id' => $this->merchant_session['mer_id']))->select();
		$ids = array();
		foreach ($diymenus as $diy) {
			$ids[$diy['id']] = $diy['id'];
		}
		
		foreach ($data as $index => $val) {
// 			$val['url'] = htmlspecialchars_decode($val['url']);
// 			if ($val['url'] && !strstr($val['url'], $this->config['site_url'])) exit(json_encode(array('errcode' => 1, 'errmsg' => 'URL地址不合法')));
			unset($val['type']);
			$val['is_show'] = 1;
			if ($index > 10) {//二级菜单
				$pindex = $index / 10;
				if ($val['title'] && isset($data[$pindex]['id']) && $data[$pindex]['id']) {
					if ($val['id'] && ($diymenu = D('Diymenu_class')->where(array('mer_id' => $this->merchant_session['mer_id'], 'id' => $val['id']))->find())) {
						$id = $val['id'];
						unset($ids[$val['id']]);
						unset($val['id']);
						$val['pid'] = $data[$pindex]['id'];
						$val['url'] = htmlspecialchars_decode($val['url']);
						D('Diymenu_class')->where(array('mer_id' => $this->merchant_session['mer_id'], 'id' => $id))->save($val);
					} else {
						unset($val['id']);
						$val['mer_id'] = $this->merchant_session['mer_id'];
						$val['pid'] = $data[$pindex]['id'];
						$val['url'] = htmlspecialchars_decode($val['url']);
						D('Diymenu_class')->add($val);
					}
				} elseif (empty($val['title']) && isset($data[$pindex]['id']) && $data[$pindex]['id']) {
					unset($ids[$val['id']]);
					D('Diymenu_class')->where(array('mer_id' => $this->merchant_session['mer_id'], 'id' => $val['id']))->delete();
				}
			} else {//一级菜单
				if ($val['title']) {
					if ($val['id'] && ($diymenu = D('Diymenu_class')->where(array('mer_id' => $this->merchant_session['mer_id'], 'id' => $val['id']))->find())) {
						$id = $val['id'];
						unset($ids[$val['id']]);
						unset($val['id']);
						$val['url'] = htmlspecialchars_decode($val['url']);
						D('Diymenu_class')->where(array('mer_id' => $this->merchant_session['mer_id'], 'id' => $id))->save($val);
					} else {
						unset($val['id']);
						$val['mer_id'] = $this->merchant_session['mer_id'];
						$val['url'] = htmlspecialchars_decode($val['url']);
						$data[$index]['id'] = D('Diymenu_class')->add($val);
					}
				} else {
					unset($ids[$val['id']]);
					D('Diymenu_class')->where(array('mer_id' => $this->merchant_session['mer_id'], 'id' => $val['id']))->delete();
				}
			}
		}
		if ($ids) D('Diymenu_class')->where(array('mer_id' => $this->merchant_session['mer_id'], 'id' => array('in', $ids)))->delete();
		$result = $this->class_send();
		exit($result);
	}	
	
	public function testmenu()
	{
		$token_data = D('Weixin_bind')->get_access_token($this->merchant_session['mer_id']);
		'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=ACCESS_TOKEN';
		$data = file_get_contents('https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$token_data['access_token']);
		
		$data = json_decode($data, true);
		echo "<pre/>";
		print_r($data);
	}
	public function class_send()
	{
// 		if (IS_GET) {
			$token_data = D('Weixin_bind')->get_access_token($this->merchant_session['mer_id']);
			if ($token_data['errcode']) exit(json_encode($token_data));
			$class = D('Diymenu_class')->where(array('pid' => 0, 'mer_id' => $this->merchant_session['mer_id']))->limit(3)->order('id asc')->select();//dump($class);
			$kcount = D('Diymenu_class')->where(array('pid' => 0, 'mer_id' => $this->merchant_session['mer_id']))->count('id');
			$k = 1;
			$data = '{"button":[';
			foreach ($class as $key => $vo) {
				//主菜单
				$data .= '{"name":"'.$vo['title'].'",';
				$c = D('Diymenu_class')->where(array('pid'=>$vo['id'], 'mer_id' => $this->merchant_session['mer_id']))->limit(5)->order('sort asc')->select();
				$count = D('Diymenu_class')->where(array('pid'=>$vo['id'], 'mer_id' => $this->merchant_session['mer_id']))->count('id');
				//子菜单
				if ($c != false) {
					$data .= '"sub_button":[';
				} else {
					if ($vo['url']) {
						$vo['url'] = str_replace('&amp;', '&', $vo['url']);
						$data .= '"type":"view","url":"'.$vo['url'].'"';
					} elseif($vo['keyword']) {
						$data .='"type":"click","key":"'.$vo['keyword'].'"';
					} elseif($vo['wxsys'] != 0 && $vo['wxsys'] != 1) {
						$data .='"type":"'.$vo['wxsys'].'","key":"'.$vo['wxsys'].'"';
					}
				}
				$i=1;
				foreach ($c as $voo) {
					if ($i == $count) {
						if ($voo['url']) {
							$voo['url'] = str_replace('&amp;', '&', $voo['url']);
							$data .= '{"type":"view","name":"'.$voo['title'].'","url":"'.$voo['url'].'"}';
						} elseif($voo['keyword']) {
							$data .= '{"type":"click","name":"'.$voo['title'].'","key":"'.$voo['keyword'].'"}';
						} elseif($voo['wxsys'] != 0 && $voo['wxsys'] != 1) {
							$data .= '{"type":"'.$voo['wxsys'].'","name":"'.$voo['title'].'","key":"'.$voo['wxsys'].'"}';
						}
					} else {
						if ($voo['url']) {
							$data .= '{"type":"view","name":"'.$voo['title'].'","url":"'.$voo['url'].'"},';
						} elseif($voo['keyword']) {
							$data .= '{"type":"click","name":"'.$voo['title'].'","key":"'.$voo['keyword'].'"},';
						} elseif($voo['wxsys'] != 0 && $voo['wxsys'] != 1) {
							$data .= '{"type":"'.$voo['wxsys'].'","name":"'.$voo['title'].'","key":"'.$voo['wxsys'].'"},';
						}
					}
					$i++;
				}
				if ($c != false) {
					$data .= ']';
				}
	
				if ($k == $kcount) {
					$data .= '}';
				} else {
					$data .= '},';
				}
				$k++;
			}
			$data .= ']}';
			
			
// 			$authorizer_access_token = $json->access_token;
			file_get_contents('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$token_data['access_token']);
			$url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$token_data['access_token'];
			import('ORG.Net.Http');
			$rt = Http::curlPost($url, $data);
			if ($rt['errcode']) {
				return json_encode($rt);
			} else {
				return json_encode(array('errcode' => 0, 'errmsg' => '自定义菜单生产成功'));
			}
// 		} else {
// 			exit(json_encode(array('errcode' => 1, 'errmsg' => '非法操作')));
// 		}
	}
	
	
	public function get_url()
	{
		import('ORG.Net.Http');
		$result = $_SESSION['component_access_token'];
		if ($result && $result[0] > time()) {
			$result['component_access_token'] = $result[1];
		} else {
			$url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
			$data = array('component_appid' => $this->config['wx_appid'], 'component_appsecret' => $this->config['wx_appsecret'], 'component_verify_ticket' => $this->config['wx_componentverifyticket']);
			$result = Http::curlPost($url, json_encode($data));
			if (empty($result['errcode'])) {
				$_SESSION['component_access_token'] = array($result['expires_in'] + time(), $result['component_access_token']);
			} else {
				exit(json_encode(array('err_code' => 1, 'err_msg' => '获取授权地址失败')));
			}
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=' . $result['component_access_token'];//
		$data = array('component_appid' => $this->config['wx_appid']);
		$auth_code = Http::curlPost($url, json_encode($data));
		if (empty($auth_code['errcode'])) {
			$url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.$this->config['wx_appid'].'&pre_auth_code='.$auth_code['pre_auth_code'].'&redirect_uri=' . urlencode($this->config['site_url'] . '/merchant.php?g=Merchant&c=Weixin&a=auth_back');
			exit(json_encode(array('err_code' => 0, 'err_msg' => $url)));
		}
		exit(json_encode(array('err_code' => 1, 'err_msg' => '获取授权地址失败')));
	}
	
	public function auth_back()
	{
		if (isset($_GET['auth_code']) && isset($_GET['expires_in'])){
			
			//获取 component_access_token
			import('ORG.Net.Http');
			
// 			$result = $_SESSION['component_access_token'];
// 			if ($result && $result[0] > time()) {
// 				$result['component_access_token'] = $result[1];
// 			} else {
				$url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
				$data = array('component_appid' => $this->config['wx_appid'], 'component_appsecret' => $this->config['wx_appsecret'], 'component_verify_ticket' => $this->config['wx_componentverifyticket']);
				$result = Http::curlPost($url, json_encode($data));
				if ($result['errcode']) {
					$this->assign('errmsg', $result['errmsg']);
					$this->display('fail');
					exit();
				}
// 			}
			
			//获取 authorizer_appid
			$url = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=' . $result['component_access_token'];//
			$data = array('component_appid' => $this->config['wx_appid'], 'authorization_code' => $_GET['auth_code']);
			$result1 = Http::curlPost($url, json_encode($data));
			if ($result1['errcode']) {
				$this->assign('errmsg', $result1['errmsg']);
				$this->display('fail');
				exit();
			}
			$_SESSION['authorizer_access_token'] = array($result1['authorization_info']['expires_in'] + time(), $result1['authorization_info']['authorizer_access_token']);
			$url = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=' . $result['component_access_token'];
			$data = array('component_appid' => $this->config['wx_appid'], 'authorizer_appid' => $result1['authorization_info']['authorizer_appid']);
			$result2 = Http::curlPost($url, json_encode($data));
			if ($result2['errcode']) {
				$this->assign('errmsg', $result2['errmsg']);
				$this->display('fail');
				exit();
			} else {
				$data = array();
				$data = $result2['authorizer_info'];
				$data['service_type_info'] = $data['service_type_info']['id'];
				$data['verify_type_info'] = $data['verify_type_info']['id'];
				$pre = '';
				$func_info = '';
				foreach ($result2['authorization_info']['func_info'] as $val) {
					$func_info .= $pre . $val['funcscope_category']['id'];
					$pre = ',';
				}
				$data['func_info'] = $func_info;
				$data['authorizer_appid'] = $result1['authorization_info']['authorizer_appid'];
				$data['authorizer_refresh_token'] = $result1['authorization_info']['authorizer_refresh_token'];
				$data['mer_id'] = $this->merchant_session['mer_id'];
				
				if ($is_bind = D('Weixin_bind')->where(array('user_name' => $data['user_name']))->find()) {
					if ($is_bind['mer_id'] != $this->merchant_session['mer_id']) {
						$this->assign('errmsg', '该微信公众号已在其他店铺完成绑定，无法绑定到当前店铺！');
						$this->display('fail');
						exit();
					}
				}
				if ($weixin_bind = D('Weixin_bind')->where(array('mer_id' => $this->merchant_session['mer_id']))->find()) {
					D('Weixin_bind')->where(array('mer_id' => $this->merchant_session['mer_id']))->data($data)->save();
				} else {
					D('Weixin_bind')->data($data)->add();
				}
				$this->display('success');
			}
		} else {
			$this->assign('errmsg', '不合法的请求授权');
			$this->display('fail');
		}
	}
	
	private function _get_sys($type='',$key='')
	{
		$wxsys 	= array(
				'扫码带提示',
				'扫码推事件',
				'系统拍照发图',
				'拍照或者相册发图',
				'微信相册发图',
				'发送位置',
		);
	
		if($type == 'send'){
			$wxsys 	= array(
					'扫码带提示'=>'scancode_waitmsg',
					'扫码推事件'=>'scancode_push',
					'系统拍照发图'=>'pic_sysphoto',
					'拍照或者相册发图'=>'pic_photo_or_album',
					'微信相册发图'=>'pic_weixin',
					'发送位置'=>'location_select',
			);
			return $wxsys[$key];
			exit;
		}
		return $wxsys;
	}
	
	/**
	 * 创建文本回复
	 */
	public function reply_txt()
	{
		if (IS_POST) {
			$pigcms_id = isset($_POST['pigcms_id']) ? intval($_POST['pigcms_id']) : 0;
			$keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
			$content = isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '';
			$this->assign('keyword', array('pigcms_id' => $pigcms_id, 'content' => $content, 'keyword' => $keyword));
			if (empty($keyword)) {
				$this->assign('error', '关键词 不可为空白.');
				$this->display();
				exit();
			}
			if (empty($content)) {
				$this->assign('error', '回复内容 不可为空白.');
				$this->display();
				exit();
			}
			if ($keyobj = D('Keyword')->where(array('mer_id' => $this->merchant_session['mer_id'], 'content' => $keyword))->find()) {
				if ($keyobj['pigcms_id'] != $pigcms_id) {
					$this->assign('error', '关键词 "'.$keyword.'" 已被取用.');
					$this->display();
					exit();
				}
			}
			
			if ($pigcms_id && ($keywordData = D('Keyword')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->find())) {
			    $txt = D($keywordData['table'])->where(array('pigcms_id' => $keywordData['from_id'], 'mer_id' => $this->merchant_session['mer_id']))->find();
				
				if ($txt) {
				    D($keywordData['table'])->where(array('pigcms_id' => $keywordData['from_id'], 'mer_id' => $this->merchant_session['mer_id']))->save(array('content' => $content));
				    $txtid = $keywordData['from_id'];
				} else {
				    $txtid = D($keywordData['table'])->add(array('mer_id' => $this->merchant_session['mer_id'], 'content' => $content));
				}
				if (empty($txtid)) {
					$this->assign('error', '创建失败');
					$this->display();
					exit();
				}
				D('Keyword')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->save(array('content' => $keyword, 'from_id' => $txtid, 'table' => 'Text'));
			} else {
				$txtid = D('Text')->add(array('mer_id' => $this->merchant_session['mer_id'], 'content' => $content));
				D('Keyword')->add(array('mer_id' => $this->merchant_session['mer_id'], 'content' => $keyword, 'from_id' => $txtid, 'table' => 'Text'));
			}
			$this->redirect(U('Weixin/txt'));
		} else {
			$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
			if ($keyword = D('Keyword')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
				$keyword['keyword'] = $keyword['content'];
				$content = D($keyword['table'])->where(array('pigcms_id' => $keyword['from_id'], 'mer_id' => $this->merchant_session['mer_id']))->find();
				$keyword['content'] = $content ? $content['content'] : '';
				$this->assign('keyword', $keyword);
			}
		}
		$this->display();
	}
	
	public function del_txt()
	{
		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
		if ($keyword = D('Keyword')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
			$content = D($keyword['table'])->where(array('pigcms_id' => $keyword['from_id'], 'mer_id' => $this->merchant_session['mer_id']))->delete();
			D('Keyword')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->delete();
			$this->redirect(U('Weixin/txt'));
		} else {
			$this->error('不合法的请求');
		}
	}
	
	/**
	 * 文本回复列表
	 */
	public function txt()
	{
		$count = D('Keyword')->where(array('mer_id' => $this->merchant_session['mer_id'], 'table' => 'Text'))->count('pigcms_id');
		import('@.ORG.merchant_page');
		$p = new Page($count, 10);
		$list = D('Keyword')->where(array('mer_id' => $this->merchant_session['mer_id'], 'table' => 'Text'))->order('pigcms_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$temp = $result = array();
		foreach ($list as $l) {
			$temp[] = $l['from_id'];
		}
		$tmp = array();
		if ($temp) {
			$texts = D('Text')->where(array('mer_id' => $this->merchant_session['mer_id'], 'pigcms_id' => array('in', $temp)))->select();
			foreach ($texts as $t) {
				$tmp[$t['pigcms_id']] = $t;
			}
		}
		foreach ($list as &$v) {
			$v['keyword'] = $v['content'];
			$v['content'] = isset($tmp[$v['from_id']]) ? $tmp[$v['from_id']]['content'] : '';
		}
		$this->assign('lists', $list);
		$this->assign('page', $p->show());
		$this->display();
	}
	
	/**
	 * 创建图文回复
	 */
	public function reply_img()
	{
		$list = D('Source_material')->where(array('mer_id' => $this->merchant_session['mer_id']))->order('pigcms_id DESC')->select();
		$it_ids = array();
		$temp = array();
		foreach ($list as $l) {
			foreach (unserialize($l['it_ids']) as $id) {
				if (!in_array($id, $it_ids)) $it_ids[] = $id;
			}
		}
		$result = array();
		$image_text = D('Image_text')->field('pigcms_id, title')->where(array('pigcms_id' => array('in', $it_ids)))->order('pigcms_id asc')->select();
		foreach ($image_text as $txt) {
			$result[$txt['pigcms_id']] = $txt;
		}
		foreach ($list as &$l) {
			$l['dateline'] = date('Y-m-d H:i:s', $l['dateline']);
			foreach (unserialize($l['it_ids']) as $id) {
				$l['list'][] = isset($result[$id]) ? $result[$id] : array();
			}
		}
		$this->assign('list', $list);
		
		if (IS_POST) {
			$pigcms_id = isset($_POST['pigcms_id']) ? intval($_POST['pigcms_id']) : 0;
			$keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
			$source_id = isset($_POST['source_id']) ? intval($_POST['source_id']) : 0;
			$this->assign('keyword', array('pigcms_id' => $pigcms_id, 'from_id' => $source_id, 'keyword' => $keyword));
			if (empty($keyword)) {
				$this->assign('error', '关键词 不可为空白.');
				$this->display();
				exit();
			}
			if ($keyobj = D('Keyword')->where(array('mer_id' => $this->merchant_session['mer_id'], 'content' => $keyword))->find()) {
				if ($keyobj['pigcms_id'] != $pigcms_id) {
					$this->assign('error', '关键词 "'.$keyword.'" 已被取用.');
					$this->display();
					exit();
				}
			}
			if (!($obj = D('Source_material')->where(array('pigcms_id' => $source_id, 'mer_id' => $this->merchant_session['mer_id']))->find())) {
				$this->assign('error', '选择了不存在的文图.');
				$this->display();
				exit();
			}
			if ($pigcms_id && ($keyword = D('Keyword')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->find())) {
				D('Keyword')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->save(array('content' => $keyword, 'from_id' => $source_id, 'table' => 'Source_material'));
			} else {
				D('Keyword')->add(array('mer_id' => $this->merchant_session['mer_id'], 'content' => $keyword, 'from_id' => $source_id, 'table' => 'Source_material'));
			}
			$this->redirect(U('Weixin/img'));
		} else {
			$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
			if ($keyword = D('Keyword')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
				$keyword['keyword'] = $keyword['content'];
				$this->assign('keyword', $keyword);
			}
		}
		$this->display();
	}
	
	
	public function del_img()
	{
		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
		if ($keyword = D('Keyword')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->find()) {
			D('Keyword')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->delete();
			$this->redirect(U('Weixin/img'));
		} else {
			$this->error('不合法的请求');
		}
	}
	
	public function img()
	{
		$count = D('Keyword')->where(array('mer_id' => $this->merchant_session['mer_id'], 'table' => 'Source_material'))->count('pigcms_id');
		import('@.ORG.merchant_page');
		$p = new Page($count, 10);
		$list = D('Keyword')->where(array('mer_id' => $this->merchant_session['mer_id'], 'table' => 'Source_material'))->order('pigcms_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$ids = array();
		foreach ($list as $l) {
			$ids[] = $l['from_id'];
		}
		
		$sources = D('Source_material')->where(array('pigcms_id' => array('in', $ids)))->order('pigcms_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$it_ids = array();
		$temp = array();
		
		foreach ($sources as $so) {
			foreach (unserialize($so['it_ids']) as $id) {
				if (!in_array($id, $it_ids)) $it_ids[] = $id;
			}
		}
		$result = array();
		$image_text = D('Image_text')->field('pigcms_id, title')->where(array('pigcms_id' => array('in', $it_ids)))->order('pigcms_id asc')->select();
		foreach ($image_text as $txt) {
			$result[$txt['pigcms_id']] = $txt;
		}
		
		$tuwen_list = array();
		foreach ($sources as $s) {
			$s['dateline'] = date('Y-m-d H:i:s', $s['dateline']);
			foreach (unserialize($s['it_ids']) as $id) {
				$s['list'][] = isset($result[$id]) ? $result[$id] : array();
			}
			$tuwen_list[$s['pigcms_id']] = $s;
		}
		
		foreach ($list as &$li) {
			$li['list'] = isset($tuwen_list[$li['from_id']]['list']) ? $tuwen_list[$li['from_id']]['list'] : array();
		}
		
		
		$this->assign('lists', $list);
		$this->assign('page', $p->show());
		$this->display();
	}
	
	public function auto()
	{
		$type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
		if ($type == 0) {
			$this->assign('tips', '关注时');
		} elseif ($type == 1) {
			$this->assign('tips', '无效词');
		} else {
			$this->assign('tips', '图片');
		}
		$this->assign('type', $type);
		if (IS_POST) {
			$type = isset($_POST['type']) ? intval($_POST['type']) : 0;
			$reply_type = isset($_POST['reply_type']) ? intval($_POST['reply_type']) : 0;
			$is_open = isset($_POST['is_open']) ? intval($_POST['is_open']) : 0;
			$source_id = isset($_POST['source_id']) ? intval($_POST['source_id']) : 0;
			$content = isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '';
			if (empty($reply_type) && empty($content)) {
				$this->assign('error', '回复内容 不可为空白.');
				$this->display();
				exit();
			}
			if ($reply_type == 1) {
				$data = D('Source_material')->where(array('mer_id' => $this->merchant_session['mer_id'], 'pigcms_id' => $source_id))->find();
				if (empty($data)) {
					$this->assign('error', '选择了不存在的文图.');
					$this->display();
					exit();
				}
			}
			if ($other = D("Reply_other")->where(array('mer_id' => $this->merchant_session['mer_id'], 'type' => $type))->find()) {
				D("Reply_other")->where(array('mer_id' => $this->merchant_session['mer_id'], 'type' => $type))->data(array('content' => $content, 'from_id' => $source_id, 'reply_type' => $reply_type, 'is_open' => $is_open))->save();
				$this->success('更新成功');
			} else {
				D("Reply_other")->data(array('mer_id' => $this->merchant_session['mer_id'], 'type' => $type, 'content' => $content, 'from_id' => $source_id, 'reply_type' => $reply_type, 'is_open' => $is_open))->add();
				$this->success('创建成功');
			}
			
		} else {
			
			$list = D('Source_material')->where(array('mer_id' => $this->merchant_session['mer_id']))->order('pigcms_id DESC')->select();
			$it_ids = array();
			$temp = array();
			foreach ($list as $l) {
				foreach (unserialize($l['it_ids']) as $id) {
					if (!in_array($id, $it_ids)) $it_ids[] = $id;
				}
			}
			$result = array();
			$image_text = D('Image_text')->field('pigcms_id, title')->where(array('pigcms_id' => array('in', $it_ids)))->order('pigcms_id asc')->select();
			foreach ($image_text as $txt) {
				$result[$txt['pigcms_id']] = $txt;
			}
			foreach ($list as &$l) {
				$l['dateline'] = date('Y-m-d H:i:s', $l['dateline']);
				foreach (unserialize($l['it_ids']) as $id) {
					$l['list'][] = isset($result[$id]) ? $result[$id] : array();
				}
			}
			$this->assign('list', $list);
			$other = D("Reply_other")->where(array('mer_id' => $this->merchant_session['mer_id'], 'type' => $type))->find();
			$this->assign('other', $other);
			$this->display();
		}
	}

    public function template()
    {
        $where = array('mer_id' => $this->merchant_session['mer_id']);
        $list = M('Tempmsg')->where($where)->field(true)->select();
        $this->assign('list', $list);
        $weixin = D('Weixin_bind')->get_account_type($this->merchant_session['mer_id']);
        $this->assign('weixin', $weixin);
        $this->display();
    }

    public function tpladd()
    {
        if (IS_POST) {
            $data = array();
            $data['tempkey'] = trim($_POST['tempkey']);
			$tmpKeyArr = explode('（',$data['tempkey']);
			$data['tempkey'] = $tmpKeyArr[0];
            $data['name'] = trim($_POST['name']);
            $data['content'] = trim($_POST['content']);
            $data['topcolor'] = trim($_POST['topcolor']);
            $data['textcolor'] = trim($_POST['textcolor']);
            $data['status'] = intval($_POST['status']);
            $data['tempid'] = trim($_POST['tempid']);
            $data['mer_id'] = $this->merchant_session['mer_id'];
            $data['status'] = $data['tempid'] == '' ? 0 : $data['status'];
            
            $where = array(
                'tempkey' => $data['tempkey'],
                'mer_id' => $this->merchant_session['mer_id']
            );
            
            if (M('Tempmsg')->where($where)->getField('id')) {
                M('Tempmsg')->where($where)->save($data);
            } else {
                M('Tempmsg')->add($data);
            }
            $this->success('操作成功', U('template'));
        } else {
            $this->assign('tplList', $this->tplArray());
            $this->display();
        }
    }

    public function tpledit()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $tempmsg = M('Tempmsg')->where(array('mer_id' => $this->merchant_session['mer_id'], 'id' => $id))->find();
        if (empty($tempmsg)) $this->error('错误的数据信息');
        if (IS_POST) {
            $data = array();
            $data['tempkey'] = trim($_POST['tempkey']);
			$tmpKeyArr = explode('（',$data['tempkey']);
			$data['tempkey'] = $tmpKeyArr[0];
            $data['name'] = trim($_POST['name']);
            $data['content'] = trim($_POST['content']);
            $data['topcolor'] = trim($_POST['topcolor']);
            $data['textcolor'] = trim($_POST['textcolor']);
            $data['status'] = intval($_POST['status']);
            $data['tempid'] = trim($_POST['tempid']);
            $data['status'] = $data['tempid'] == '' ? 0 : $data['status'];
            
            $where = array(
                'id' => $id,
                'tempkey' => $data['tempkey'],
                'mer_id' => $this->merchant_session['mer_id']
            );
            M('Tempmsg')->where($where)->save($data);
            $this->success('操作成功', U('template'));
        } else {
            $this->assign('tplList', $this->tplArray());
            $this->assign('tempmsg', $tempmsg);
            $this->display();
        }
    }

    public function tpldel()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $tempmsg = M('Tempmsg')->where(array('mer_id' => $this->merchant_session['mer_id'], 'id' => $id))->delete();
        if ($tempmsg) {
            $this->success('删除成功');
        } else {
            $this->error('错误的数据信息');
        }
    }

    private function tplArray()
    {
        $tplList['请选择模板编号'] = array('key' => '请选择模板编号', 'name' => '', 'content' => '');
        $tplList['OPENTM201752540（订单支付成功通知）'] = array('key' => 'OPENTM201752540', 'name' => '订单支付成功通知', 'content' => '
{{first.DATA}}
订单商品：{{keyword1.DATA}}
订单编号：{{keyword2.DATA}}
支付金额：{{keyword3.DATA}}
支付时间：{{keyword4.DATA}}
{{remark.DATA}}');
		$tplList['OPENTM207498902（订单支付成功通知）'] = array('key' => 'OPENTM207498902', 'name' => '订单支付成功通知', 'content' => '
{{first.DATA}}
用户名：{{keyword1.DATA}}
订单号：{{keyword2.DATA}}
订单金额：{{keyword3.DATA}}
商品信息：{{keyword4.DATA}}
{{remark.DATA}}');
        $tplList['OPENTM201682460（订单生成通知）'] = array('key' => 'OPENTM201682460', 'name' => '订单生成通知', 'content' => '
{{first.DATA}}
时间：{{keyword1.DATA}}
商品名称：{{keyword2.DATA}}
订单号：{{keyword3.DATA}}
{{remark.DATA}}');
        $tplList['OPENTM202521011（订单完成通知）'] = array('key' => 'OPENTM202521011', 'name' => '订单完成通知', 'content' => '
{{first.DATA}}
订单号：{{keyword1.DATA}}
完成时间：{{keyword2.DATA}}
{{remark.DATA}}');
        $tplList['TM00017（订单状态更新）'] = array('key' => 'TM00017', 'name' => '订单状态更新', 'content' => '
{{first.DATA}}
订单编号：{{OrderSn.DATA}}
订单状态：{{OrderStatus.DATA}}
{{remark.DATA}}');
        $tplList['OPENTM200964573（会员卡领取通知）'] = array('key' => 'OPENTM200964573', 'name' => '会员卡领取通知', 'content' => '
{{first.DATA}}
会员编号：{{keyword1.DATA}}
会员姓名：{{keyword2.DATA}}
会员电话：{{keyword3.DATA}}
申请时间：{{keyword4.DATA}}
{{remark.DATA}}');
        $tplList['TM00251（领取成功通知(领取优惠券)）'] = array('key' => 'TM00251', 'name' => '领取成功通知(领取优惠券)', 'content' => '
{{first.DATA}}
领取人：{{toName.DATA}}
赠品：{{gift.DATA}}
领取时间：{{time.DATA}}
{{remark.DATA}}');
		$tplList['OPENTM200772305（礼品领取成功通知(领取优惠券)）'] = array('key' => 'OPENTM200772305', 'name' => '礼品领取成功通知(领取优惠券)', 'content' => '
{{first.DATA}}
领取人：{{keyword1.DATA}}
礼品：{{keyword2.DATA}}
领取时间：{{keyword3.DATA}}
{{remark.DATA}}');
        $tplList['OPENTM205984119（排号提醒通知）'] = array('key' => 'OPENTM205984119', 'name' => '排号提醒通知', 'content' => '
{{first.DATA}}
队列号：{{keyword1.DATA}}
取号时间：{{keyword2.DATA}}
等待人数：{{keyword3.DATA}}
{{remark.DATA}}');

		$tplList['OPENTM401833445（余额变动提示）'] = array('key' => 'OPENTM401833445', 'name' => '余额变动提示', 'content' => '
{{first.DATA}}
变动时间：{{keyword1.DATA}}
变动类型：{{keyword2.DATA}}
变动金额：{{keyword3.DATA}}
当前余额：{{keyword4.DATA}}
{{remark.DATA}}');
        return $tplList;
    }
    
    
    public function cancelBind()
    {
        D('Weixin_bind')->where(array('mer_id' => $this->merchant_session['mer_id'], 'type' => 0))->delete();
        exit(json_encode(array('err_code' => 0, 'msg' => 'ok')));
    }
}