<?php
class WeixinAction extends BaseAction
{
	
	public function _initialize()
	{
		parent::_initialize();
		if (empty($this->config['is_open_oauth']) && empty($this->house_session['is_open_oauth'])) {
// 			$this->error('你没有这个使用权限', U('Index/index'));
		}
	}
	
	public function index()
	{
        //公众号绑定-查看 权限
        if (!in_array(177, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$weixin_bind = array();
		if ($weixin_bind = D('Weixin_bind')->where(array('mer_id' => $this->house_session['village_id'], 'type' => 1))->find()) {
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
			$url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
			$data = array('component_appid' => $this->config['wx_house_appid'], 'component_appsecret' => $this->config['wx_house_appsecret'], 'component_verify_ticket' => $this->config['wx_house_componentverifyticket']);
			$result = Http::curlPost($url, json_encode($data));

			if (empty($result['errcode'])) {
				$_SESSION['component_access_token'] = array($result['expires_in'] + time(), $result['component_access_token']);
				
				$url = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=' . $result['component_access_token'];//
				$data = array('component_appid' => $this->config['wx_house_appid']);
				$auth_code = Http::curlPost($url, json_encode($data));
				if (empty($auth_code['errcode'])) {
					$url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.$this->config['wx_house_appid'].'&pre_auth_code='.$auth_code['pre_auth_code'].'&redirect_uri=' . urlencode($this->config['site_url'] . '/shequ.php?g=House&c=Weixin&a=auth_back');
					$this->assign('url', $url);
				} else {
					$this->assign('url', '');
				}
			} else {
				$this->assign('url', '');
				$this->assign('api_component_error_result',$result);
			}
		}

		if($weixin_bind){
			$weixin_bind['qrcode_url'] = $this->config['site_url'].'/index.php?c=Image&a=wx_image_download&url='.urlencode($weixin_bind['qrcode_url']).'&path='.$this->house_session['village_id'];
		}

		$this->assign('bind', $weixin_bind);
		$this->display();
	}

	
	
	public function menu()
	{
        //自定义菜单-查看 权限
        if (!in_array(181, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$weixin = D('Weixin_bind')->get_account_type($this->house_session['village_id'], 1);
		if (isset($weixin['code']) && $weixin['code'] > 0) {
			$diymenus = D('House_diymenu_class')->where(array('mer_id' => $this->house_session['village_id']))->order('sort ASC')->select();
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
        //自定义菜单-编辑 权限
        if (!in_array(182, $this->house_session['menus'])) {
			return json_encode(array('errcode' => 1, 'errmsg' => '对不起，您没有权限执行此操作'));
        }

		$data = isset($_POST['custommenu']) ? $_POST['custommenu'] : array();
		$HouseDiyDB = D('House_diymenu_class');
		$diymenus = $HouseDiyDB->where(array('mer_id' => $this->house_session['village_id']))->select();
		$ids = array();
		foreach ($diymenus as $diy) {
			$ids[$diy['id']] = $diy['id'];
		}
		
		foreach ($data as $index => $val) {
			// $val['url'] = htmlspecialchars_decode($val['url']);
			// if ($val['url'] && !strstr($val['url'], $this->config['site_url'])) exit(json_encode(array('errcode' => 1, 'errmsg' => 'URL地址不合法')));
			unset($val['type']);
			$val['is_show'] = 1;
			if ($index > 10) {//二级菜单
				$pindex = $index / 10;
				if ($val['title'] && isset($data[$pindex]['id']) && $data[$pindex]['id']) {
					if ($val['id'] && ($diymenu = $HouseDiyDB->where(array('mer_id' => $this->house_session['village_id'], 'id' => $val['id']))->find())) {
						$id = $val['id'];
						unset($ids[$val['id']]);
						unset($val['id']);
						$val['pid'] = $data[$pindex]['id'];
						$val['url'] = htmlspecialchars_decode($val['url']);
						$HouseDiyDB->where(array('mer_id' => $this->house_session['village_id'], 'id' => $id))->save($val);
					} else {
						unset($val['id']);
						$val['mer_id'] = $this->house_session['village_id'];
						$val['pid'] = $data[$pindex]['id'];
						$val['url'] = htmlspecialchars_decode($val['url']);
						$HouseDiyDB->add($val);
					}
				} elseif (empty($val['title']) && isset($data[$pindex]['id']) && $data[$pindex]['id']) {
					unset($ids[$val['id']]);
					$HouseDiyDB->where(array('mer_id' => $this->house_session['village_id'], 'id' => $val['id']))->delete();
				}
			} else {//一级菜单
				if ($val['title']) {
					if ($val['id'] && ($diymenu = D('House_diymenu_class')->where(array('mer_id' => $this->house_session['village_id'], 'id' => $val['id']))->find())) {
						$id = $val['id'];
						unset($ids[$val['id']]);
						unset($val['id']);
						$val['url'] = htmlspecialchars_decode($val['url']);
						$HouseDiyDB->where(array('mer_id' => $this->house_session['village_id'], 'id' => $id))->save($val);
					} else {
						unset($val['id']);
						$val['mer_id'] = $this->house_session['village_id'];
						$val['url'] = htmlspecialchars_decode($val['url']);
						$data[$index]['id'] = $HouseDiyDB->add($val);
					}
				} else {
					unset($ids[$val['id']]);
					$HouseDiyDB->where(array('mer_id' => $this->house_session['village_id'], 'id' => $val['id']))->delete();
				}
			}
		}
		if ($ids) $HouseDiyDB->where(array('mer_id' => $this->house_session['village_id'], 'id' => array('in', $ids)))->delete();
		$result = $this->class_send();
		exit($result);
	}	
	
	public function testmenu()
	{
		$token_data = D('Weixin_bind')->get_access_token($this->house_session['village_id'], 1);
		'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=ACCESS_TOKEN';
		$data = file_get_contents('https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$token_data['access_token']);
		
		$data = json_decode($data, true);
		echo "<pre/>";
		print_r($data);
	}
	public function class_send()
	{
// 		if (IS_GET) {
			$token_data = D('Weixin_bind')->get_access_token($this->house_session['village_id'], 1);
			if ($token_data['errcode']) exit(json_encode($token_data));
			$HouseDiyDB = D('House_diymenu_class');
			$class = $HouseDiyDB->where(array('pid' => 0, 'mer_id' => $this->house_session['village_id']))->limit(3)->order('sort asc')->select();//dump($class);
			$kcount = $HouseDiyDB->where(array('pid' => 0, 'mer_id' => $this->house_session['village_id']))->count('id');
			$k = 1;
			$data = '{"button":[';
			foreach ($class as $key => $vo) {
				//主菜单
				$data .= '{"name":"'.$vo['title'].'",';
				$c = $HouseDiyDB->where(array('pid'=>$vo['id'], 'mer_id' => $this->house_session['village_id']))->limit(5)->order('sort asc')->select();
				$count = $HouseDiyDB->where(array('pid'=>$vo['id'], 'mer_id' => $this->house_session['village_id']))->count('id');
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
			$data = array('component_appid' => $this->config['wx_house_appid'], 'component_appsecret' => $this->config['wx_house_appsecret'], 'component_verify_ticket' => $this->config['wx_house_componentverifyticket']);
			$result = Http::curlPost($url, json_encode($data));
			if (empty($result['errcode'])) {
				$_SESSION['component_access_token'] = array($result['expires_in'] + time(), $result['component_access_token']);
			} else {
				exit(json_encode(array('err_code' => 1, 'err_msg' => '获取授权地址失败')));
			}
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=' . $result['component_access_token'];//
		$data = array('component_appid' => $this->config['wx_house_appid']);
		$auth_code = Http::curlPost($url, json_encode($data));
		if (empty($auth_code['errcode'])) {
			$url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.$this->config['wx_house_appid'].'&pre_auth_code='.$auth_code['pre_auth_code'].'&redirect_uri=' . urlencode($this->config['site_url'] . '/shequ.php?g=House&c=Weixin&a=auth_back');
			exit(json_encode(array('err_code' => 0, 'err_msg' => $url)));
		}
		exit(json_encode(array('err_code' => 1, 'err_msg' => '获取授权地址失败')));
	}
	
	public function auth_back()
	{
		if (isset($_GET['auth_code']) && isset($_GET['expires_in'])){
			
			//获取 component_access_token
			import('ORG.Net.Http');
			
			$result = $_SESSION['component_access_token'];
			if ($result && $result[0] > time()) {
				$result['component_access_token'] = $result[1];
			} else {
				$url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
				$data = array('component_appid' => $this->config['wx_house_appid'], 'component_appsecret' => $this->config['wx_house_appsecret'], 'component_verify_ticket' => $this->config['wx_house_componentverifyticket']);
				$result = Http::curlPost($url, json_encode($data));
				if ($result['errcode']) {
					$this->assign('errmsg', $result['errmsg']);
					$this->display('fail');
					exit();
				}
			}
			
			//获取 authorizer_appid
			$url = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=' . $result['component_access_token'];//
			$data = array('component_appid' => $this->config['wx_house_appid'], 'authorization_code' => $_GET['auth_code']);
			$result1 = Http::curlPost($url, json_encode($data));
			if ($result1['errcode']) {
				$this->assign('errmsg', $result1['errmsg']);
				$this->display('fail');
				exit();
			}
			$_SESSION['authorizer_access_token'] = array($result1['authorization_info']['expires_in'] + time(), $result1['authorization_info']['authorizer_access_token']);
			$url = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=' . $result['component_access_token'];
			$data = array('component_appid' => $this->config['wx_house_appid'], 'authorizer_appid' => $result1['authorization_info']['authorizer_appid']);
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
				$data['mer_id'] = $this->house_session['village_id'];
				$data['type'] = 1;
				
				if ($is_bind = D('Weixin_bind')->where(array('user_name' => $data['user_name'], 'type' => 1))->find()) {
					if ($is_bind['mer_id'] != $this->house_session['village_id']) {
						$this->assign('errmsg', '该微信公众号已在其他店铺完成绑定，无法绑定到当前店铺！');
						$this->display('fail');
						exit();
					}
				}
				if ($weixin_bind = D('Weixin_bind')->where(array('mer_id' => $this->house_session['village_id'], 'type' => 1))->find()) {
					D('Weixin_bind')->where(array('mer_id' => $this->house_session['village_id']))->data($data)->save();
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
		$keywordDataBase = D('House_keyword');
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
			
			if ($keyobj = $keywordDataBase->where(array('mer_id' => $this->house_session['village_id'], 'content' => $keyword))->find()) {
				if ($keyobj['pigcms_id'] != $pigcms_id) {
					$this->assign('error', '关键词 "'.$keyword.'" 已被取用.');
					$this->display();
					exit();
				}
			}
			
			if ($pigcms_id && ($keyword = $keywordDataBase->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->find())) {
		        //文字回复-编辑 权限
		        if (!in_array(185, $this->house_session['menus'])) {
					$this->assign('error', '对不起，您没有权限执行此操作');
					$this->display();
					exit();
		        }

				$txt = D($keyword['table'])->where(array('pigcms_id' => $keyword['from_id'], 'mer_id' => $this->house_session['village_id']))->find();
				if ($txt) {
					D($keyword['table'])->where(array('pigcms_id' => $keyword['from_id'], 'mer_id' => $this->house_session['village_id']))->save(array('content' => $content));
					$txtid = $keyword['from_id'];
				} else {
					$txtid = D($keyword['table'])->add(array('mer_id' => $this->house_session['village_id'], 'content' => $content));
				}
				if (empty($txtid)) {
					$this->assign('error', '创建失败');
					$this->display();
					exit();
				}
				$keywordDataBase->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->save(array('content' => $keyword, 'from_id' => $txtid, 'table' => 'House_text'));
				
			} else {   
				//文字回复-添加 权限
		        if (!in_array(184, $this->house_session['menus'])) {
					$this->assign('error', '对不起，您没有权限执行此操作');
					$this->display();
					exit();
		        }

				$txtid = D('House_text')->add(array('mer_id' => $this->house_session['village_id'], 'content' => $content));
				$keywordDataBase->add(array('mer_id' => $this->house_session['village_id'], 'content' => $keyword, 'from_id' => $txtid, 'table' => 'House_text')) ;
			}
			$this->redirect(U('Weixin/txt'));
		} else {
	        //关键词回复-查看 权限
	        if (!in_array(183, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');
	        }

			$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
			if ($keyword = $keywordDataBase->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->find()) {
				$keyword['keyword'] = $keyword['content'];
				$content = D($keyword['table'])->where(array('pigcms_id' => $keyword['from_id'], 'mer_id' => $this->house_session['village_id']))->find();
				$keyword['content'] = $content ? $content['content'] : '';
				$this->assign('keyword', $keyword);
			}
		}
		$this->display();
	}
	
	public function del_txt()
	{
        //文字回复-删除 权限
        if (!in_array(186, $this->house_session['menus'])) {
			$this->error('对不起，您没有权限执行此操作');
			exit();
        }

		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
		$keywordDataBase = D('House_keyword');
		if ($keyword = $keywordDataBase->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->find()) {
			$content = D($keyword['table'])->where(array('pigcms_id' => $keyword['from_id'], 'mer_id' => $this->house_session['village_id']))->delete();
			$keywordDataBase->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->delete();
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
        //关键词回复-查看 权限
        if (!in_array(183, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$keywordDataBase = D('House_keyword');
		$count = $keywordDataBase->where(array('mer_id' => $this->house_session['village_id'], 'table' => 'House_text'))->count('pigcms_id');
		import('@.ORG.merchant_page');
		$p = new Page($count, 10);
		$list = $keywordDataBase->where(array('mer_id' => $this->house_session['village_id'], 'table' => 'House_text'))->order('pigcms_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$temp = $result = array();
		foreach ($list as $l) {
			$temp[] = $l['from_id'];
		}
		$tmp = array();
		if ($temp) {
			$texts = D('House_text')->where(array('mer_id' => $this->house_session['village_id'], 'pigcms_id' => array('in', $temp)))->select();
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
		$list = D('House_source_material')->where(array('mer_id' => $this->house_session['village_id']))->order('pigcms_id DESC')->select();
		$it_ids = array();
		$temp = array();
		foreach ($list as $l) {
			foreach (unserialize($l['it_ids']) as $id) {
				if (!in_array($id, $it_ids)) $it_ids[] = $id;
			}
		}
		$result = array();
		$image_text = D('House_image_text')->field('pigcms_id, title')->where(array('pigcms_id' => array('in', $it_ids)))->order('pigcms_id asc')->select();
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
		$keywordDataBase = D('House_keyword');
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
			if ($keyobj = $keywordDataBase->where(array('mer_id' => $this->house_session['village_id'], 'content' => $keyword))->find()) {
				if ($keyobj['pigcms_id'] != $pigcms_id) {
					$this->assign('error', '关键词 "'.$keyword.'" 已被取用.');
					$this->display();
					exit();
				}
			}
			if (!($obj = D('House_source_material')->where(array('pigcms_id' => $source_id, 'mer_id' => $this->house_session['village_id']))->find())) {
				$this->assign('error', '选择了不存在的文图.');
				$this->display();
				exit();
			}
			if ($pigcms_id && ($keyword = $keywordDataBase->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->find())) {
		        //图文回复-编辑 权限
		        if (!in_array(188, $this->house_session['menus'])) {
					$this->assign('error', '对不起，您没有权限执行此操作');
					$this->display();
					exit();
		        }

				$keywordDataBase->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->save(array('content' => $keyword, 'from_id' => $source_id, 'table' => 'House_source_material'));
			} else {
		        //图文回复-添加 权限
		        if (!in_array(187, $this->house_session['menus'])) {
					$this->assign('error', '对不起，您没有权限执行此操作');
					$this->display();
					exit();
		        }

				$keywordDataBase->add(array('mer_id' => $this->house_session['village_id'], 'content' => $keyword, 'from_id' => $source_id, 'table' => 'House_source_material'));
			}
			$this->redirect(U('Weixin/img'));
		} else {
			$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
			if ($keyword = $keywordDataBase->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->find()) {
				$keyword['keyword'] = $keyword['content'];
				$this->assign('keyword', $keyword);
			}
		}
		$this->display();
	}
	
	
	public function del_img()
	{
        //图文回复-删除 权限
        if (!in_array(189, $this->house_session['menus'])) {
			$this->error('对不起，您没有权限执行此操作');
			exit();
        }

		$keywordDataBase = D('House_keyword');
		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
		if ($keyword = $keywordDataBase->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->find()) {
			$keywordDataBase->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->delete();
			$this->redirect(U('Weixin/img'));
		} else {
			$this->error('不合法的请求');
		}
	}
	
	public function img()
	{
        //图文回复-查看 权限
        if (!in_array(183, $this->house_session['menus'])) {
			$this->error('对不起，您没有权限执行此操作');
			exit();
        }

		$keywordDataBase = D('House_keyword');
		$count = $keywordDataBase->where(array('mer_id' => $this->house_session['village_id'], 'table' => 'House_source_material'))->count('pigcms_id');
		import('@.ORG.merchant_page');
		$p = new Page($count, 10);
		$list = $keywordDataBase->where(array('mer_id' => $this->house_session['village_id'], 'table' => 'House_source_material'))->order('pigcms_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$ids = array();
		foreach ($list as $l) {
			$ids[] = $l['from_id'];
		}
		
		$sources = D('House_source_material')->where(array('pigcms_id' => array('in', $ids)))->order('pigcms_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$it_ids = array();
		$temp = array();
		
		foreach ($sources as $so) {
			foreach (unserialize($so['it_ids']) as $id) {
				if (!in_array($id, $it_ids)) $it_ids[] = $id;
			}
		}
		$result = array();
		$image_text = D('House_image_text')->field('pigcms_id, title')->where(array('pigcms_id' => array('in', $it_ids)))->order('pigcms_id asc')->select();
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
        //自动回复-查看 权限
        if (!in_array(179, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
		if ($type == 0) {
			$this->assign('tips', '关注时');
		} elseif ($type == 1) {
			$this->assign('tips', '无效词');
		} else {
			$this->assign('tips', '图片');
		}
		$this->assign('type', $type);
		
		$otherDataBase = D("House_reply_other");
		$materialDataBase = D('House_source_material');
		$imageDataBase = D('House_image_text');
		if (IS_POST) {
	        //自动回复-修改 权限
	        if (!in_array(180, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');
	        }

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
				$data = $materialDataBase->where(array('mer_id' => $this->house_session['village_id'], 'pigcms_id' => $source_id))->find();
				if (empty($data)) {
					$this->assign('error', '选择了不存在的文图.');
					$this->display();
					exit();
				}
			}
			if ($other = $otherDataBase->where(array('mer_id' => $this->house_session['village_id'], 'type' => $type))->find()) {
				$otherDataBase->where(array('mer_id' => $this->house_session['village_id'], 'type' => $type))->data(array('content' => $content, 'from_id' => $source_id, 'reply_type' => $reply_type, 'is_open' => $is_open))->save();
				$this->success('更新成功');
			} else {
				$otherDataBase->data(array('mer_id' => $this->house_session['village_id'], 'type' => $type, 'content' => $content, 'from_id' => $source_id, 'reply_type' => $reply_type, 'is_open' => $is_open))->add();
				$this->success('创建成功');
			}
			
		} else {
			
			$list = $materialDataBase->where(array('mer_id' => $this->house_session['village_id']))->order('pigcms_id DESC')->select();
			$it_ids = array();
			$temp = array();
			foreach ($list as $l) {
				foreach (unserialize($l['it_ids']) as $id) {
					if (!in_array($id, $it_ids)) $it_ids[] = $id;
				}
			}
			$result = array();
			$image_text = $imageDataBase->field('pigcms_id, title')->where(array('pigcms_id' => array('in', $it_ids)))->order('pigcms_id asc')->select();
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
			$other = $otherDataBase->where(array('mer_id' => $this->house_session['village_id'], 'type' => $type))->find();
			$this->assign('other', $other);
			$this->display();
		}
	}
	
	// 图文素材
	public function article()
	{
        //图文素材-查看 权限
        if (!in_array(190, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$material_database = D('House_source_material');
		$count = $material_database->where(array('mer_id' => $this->house_session['village_id']))->count('pigcms_id');
		import('@.ORG.merchant_page');
		$p = new Page($count, 20);
		$list = $material_database->where(array('mer_id' => $this->house_session['village_id']))->order('pigcms_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$it_ids = array();
		$temp = array();
		foreach ($list as $l) {
			foreach (unserialize($l['it_ids']) as $id) {
				if (!in_array($id, $it_ids)) $it_ids[] = $id;
			}
		}
		$result = array();
		$image_text = D('House_image_text')->field('pigcms_id, title ,read_quantity')->where(array('pigcms_id' => array('in', $it_ids)))->order('pigcms_id asc')->select();
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
		$this->assign('page', $p->show());
		$this->display();
	}
	
	public function select_img()
	{
		$count = D('House_source_material')->where(array('mer_id' => $this->house_session['village_id']))->count('pigcms_id');
		$p = new Page($count, 10);
		$image_text = D('House_image_text')->field(true)->where(array('mer_id' => $this->house_session['village_id']))->order('pigcms_id asc')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('list', $image_text);
		$this->assign('page', $p->show());
		$this->display();
	}
	
	public function del_image()
	{
        //图文素材-删除 权限
        if (!in_array(193, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$material_database = D('House_source_material');
		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
		if ($data = $material_database->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->find()) {
			if ($data['type'] == 0) {
				$it_ids = unserialize($data['it_ids']);
				$id = isset($it_ids[0]) ? intval($it_ids[0]) : 0;
				D('House_image_text')->where(array('pigcms_id' => $id, 'mer_id' => $this->house_session['village_id']))->delete();
			}
			$material_database->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->delete();
			$this->error('删除成功', U('Weixin/article'));
		} else {
			$this->error('不合法的操作');
		}
	}
	
	public function one()
	{
		$image_database = D('House_image_text');
		$material_database = D('House_source_material');
		if (IS_POST) {
			$pigcms_id = isset($_POST['pigcms_id']) ? intval($_POST['pigcms_id']) : 0;
			$thisid = isset($_POST['thisid']) ? intval($_POST['thisid']) : 0;
			$data['content'] = isset($_POST['content']) ? fulltext_filter($_POST['content']) : '';
			$data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
			$data['author'] = isset($_POST['author']) ? htmlspecialchars($_POST['author']) : '';
			$data['url'] = isset($_POST['url']) ? ($_POST['url']) : '';
			$data['url_title'] = isset($_POST['url_title']) ? htmlspecialchars($_POST['url_title']) : '';
			$data['cover_pic'] = isset($_POST['cover_pic']) ? htmlspecialchars($_POST['cover_pic']) : '';
			$data['digest'] = isset($_POST['digest']) ? htmlspecialchars($_POST['digest']) : '';
			$data['is_show'] = isset($_POST['is_show']) ? intval($_POST['is_show']) : 0;
			$data['classid'] = isset($_POST['classid']) ? intval($_POST['classid']) : 0;
			$data['classname'] = isset($_POST['classname']) ? htmlspecialchars($_POST['classname']) : '';
			if (empty($data['classname'])) {
				$data['classid'] = 0;
			}
			if (empty($data['title'])) {
				$this->error('标题不能为空！');
			}
			if (empty($data['cover_pic'])) {
				$this->error('必须得有封面图！');
			}
			if (empty($data['content'])) {
				$this->error('内容不能为空！');
			}
			$data['dateline'] = time();
			$data['mer_id'] = $this->house_session['village_id'];
			if ($pigcms_id && $thisid) {
		        //图文素材-编辑 权限
		        if (!in_array(192, $this->house_session['menus'])) {
		            $this->error('对不起，您没有权限执行此操作');
		        }
		        
				if ($image_database->where(array('pigcms_id' => $thisid, 'mer_id' => $this->house_session['village_id']))->data($data)->save()) {
					$material_database->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->data(array('it_ids' => serialize(array($thisid)), 'mer_id' => $this->house_session['village_id'], 'dateline' => time()))->save();
					$this->success('编辑成功！');
				} else {
					$this->error('操作失败稍后重试！');
				}
			} else {
		        //图文素材-添加 权限
		        if (!in_array(191, $this->house_session['menus'])) {
		            $this->error('对不起，您没有权限执行此操作');
		        }
		        
				if ($id = $image_database->data($data)->add()) {
					$material_database->data(array('it_ids' => serialize(array($id)), 'mer_id' => $this->house_session['village_id'], 'dateline' => time()))->add();
					$this->success('新增成功！');
				} else {
					$this->error('操作失败稍后重试！');
				}
			}
			
		} else {
	        //图文素材-查看 权限
	        if (!in_array(190, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');
	        }

			$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
			$image_text = array('title' => '', 'cover_pic' => '', 'author' => '', 'content' => '', 'digest' => '', 'url' => '', 'dateline' => time(), 'pigcms_id' => 0);
			if ($data = $material_database->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->find()) {
				$it_ids = unserialize($data['it_ids']);
				$id = isset($it_ids[0]) ? intval($it_ids[0]) : 0;
				$image_text = $image_database->where(array('pigcms_id' => $id, 'mer_id' => $this->house_session['village_id']))->find();
			}
			$this->assign('pigcms_id', $pigcms_id);
			$this->assign('image_text', $image_text);
			$this->display();
		}
	}
	
	
	public function multi()
	{
		if (IS_POST) {
			$ids = isset($_POST['imgids']) ? htmlspecialchars($_POST['imgids']) : '';
			$ids = explode(",", $ids);
			if (count($ids) > 10) {
				$this->error('最多十条图文');
			}
			$material_database = D('House_source_material');
			$pigcms_id = isset($_POST['pigcms_id']) ? intval($_POST['pigcms_id']) : 0;
			if ($pigcms_id && ($data = $material_database->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->find())) {
		        //图文素材-编辑 权限
		        if (!in_array(192, $this->house_session['menus'])) {
		            $this->error('对不起，您没有权限执行此操作');
		        }
		        
				$material_database->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->house_session['village_id']))->data(array('it_ids' => serialize($ids), 'mer_id' => $this->house_session['village_id'], 'dateline' => time(), 'type' => 1))->save();
				$this->success('编辑成功！');
			} else {
		        //图文素材-添加 权限
		        if (!in_array(191, $this->house_session['menus'])) {
		            $this->error('对不起，您没有权限执行此操作');
		        }

				$material_database->data(array('it_ids' => serialize($ids), 'mer_id' => $this->house_session['village_id'], 'dateline' => time(), 'type' => 1))->add();
				$this->success('创建成功！');
			}
		} else {
			$this->display();
		}
	}
	
	public function diytool()
	{
		$this->display();
	}
	public function ajax_upload_pic(){
		if($_FILES['imgFile']['error'] != 4){
			$image = D('Image')->handle($this->house_session['village_id'], 'house', 1);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$title = $image['title']['imgFile'];
				$house_image_class = new house_image();
				$url = $house_image_class->get_image_by_path($title);
				exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
			}
		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
	public function ajax_del_pic(){
		$house_image_class = new house_image();
		$house_image_class->del_image_by_path($_POST['path']);
	}
}