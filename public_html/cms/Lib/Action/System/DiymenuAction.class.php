<?php
/*
 * 广告管理
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/06 16:47
 * 
 */
class DiymenuAction extends BaseAction
{
	public function index()
	{
		$class = M('Diymenu_class')->where(array('pid'=>0, 'mer_id' => -1))->order('sort desc,id ASC')->select();
		foreach ($class as $key => $vo) {
			$c =M('Diymenu_class')->where(array('pid' => $vo['id'], 'mer_id' => -1))->order('sort desc,id ASC')->select();
			$class[$key]['class'] = $c;
		}
		$this->assign('class', $class);
		$this->display();
	}

    public function class_add()
    {
        if (IS_POST) {
            $data = array();
            $data['pid'] = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
            $count = M('Diymenu_class')->where(array('pid' => $data['pid'], 'mer_id' => - 1))->count();
            if ($data['pid'] == 0 && $count >= 3) {
                $this->error('1级菜单最多只能开启3个');
            } elseif ($data['pid'] && $count >= 5) {
                $this->error('2级子菜单最多开启5个');
            }
            
            $data['is_show'] = isset($_POST['is_show']) ? intval($_POST['is_show']) : 1;
            $data['sort'] = isset($_POST['sort']) ? intval($_POST['sort']) : 0;
            $data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
            $data['keyword'] = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
            $data['url'] = isset($_POST['url']) ? htmlspecialchars_decode($_POST['url']) : '';
            $data['pagepath'] = isset($_POST['pagepath']) ? htmlspecialchars_decode($_POST['pagepath']) : '';
            
            if (empty($data['title'])) {
                $this->error('菜单名称不能为空');
            }
            if ($_POST['menu_type'] == 1) {
                $data['url'] = '';
                $data['wxsys'] = '';
                $data['pagepath'] = '';
            } elseif ($_POST['menu_type'] == 2) {
                $data['keyword'] = '';
                $data['wxsys'] = '';
                $data['pagepath'] = '';
                if (strpos($data['url'], 'http') !== 0) {
                    $this->error('URL必须以http开头');
                }
            } elseif ($_POST['menu_type'] == 3) {
                if (!(isset($this->config['pay_wxapp_appid']) && $this->config['pay_wxapp_appid'])) {
                    $this->error('您暂时还不能添加平台小程序');
                }
                $data['keyword'] = '';
                $data['wxsys'] = '';
                $data['url'] = '';
                if (strpos($data['pagepath'], '/pages/') !== 0) {
                    $this->error('小程序页面的路径不正确');
                }
            } elseif ($_POST['menu_type'] == 4) {
                if (empty($this->config['pay_wxapp_paotui_appid'])) {
                    $this->error('您暂时还不能添加跑腿小程序');
                }
                $data['keyword'] = '';
                $data['wxsys'] = '';
                $data['url'] = '';
                $data['pagepath'] = 'paotui';
            } elseif ($_POST['menu_type'] == 5) {
                if (empty($this->config['pay_wxapp_group_appid'])) {
                    $this->error('您暂时还不能添加社群小程序');
                }
                $data['keyword'] = '';
                $data['wxsys'] = '';
                $data['url'] = '';
                $data['pagepath'] = 'group';
            }
            
            $data['mer_id'] = - 1;
            
            if (D("Diymenu_class")->add($data)) {
                $this->success('添加成功');
            } else {
                $this->error('添加失败稍后重试');
            }
                
        } else {
            $this->assign('bg_color', '#F3F3F3');
            $class = M('Diymenu_class')->where(array('pid' => 0, 'mer_id' => - 1))->order('sort desc')->select();
            $this->assign('class', $class);
            $this->assign('wxsys', $this->_get_sys());
            $this->display();
        }
    }

    public function class_edit()
    {
        $this->assign('bg_color', '#F3F3F3');
        $this->assign('wxsys', $this->_get_sys());
        if (IS_POST) {
            $set['pid'] = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
            $set['keyword'] = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
            $set['url'] = isset($_POST['url']) ? htmlspecialchars_decode($_POST['url']) : '';
            $set['pagepath'] = isset($_POST['pagepath']) ? htmlspecialchars_decode($_POST['pagepath']) : '';
            $set['is_show'] = isset($_POST['is_show']) ? intval($_POST['is_show']) : 1;
            $set['sort'] = isset($_POST['sort']) ? intval($_POST['sort']) : 0;
            $set['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
            
            if (empty($set['title'])) {
                $this->error('菜单名称不能为空');
            }
            if ($_POST['menu_type'] == 1) {
                $set['url'] = '';
                $set['wxsys'] = '';
                $set['pagepath'] = '';
            } elseif ($_POST['menu_type'] == 2) {
                $set['keyword'] = '';
                $set['wxsys'] = '';
                $set['pagepath'] = '';
                if (strpos($set['url'], 'http') !== 0) {
                    $this->error('URL必须以http开头');
                }
            } elseif ($_POST['menu_type'] == 3) {
                if (!(isset($this->config['pay_wxapp_appid']) && $this->config['pay_wxapp_appid'])) {
                    $this->error('您暂时还不能添加小程序');
                }
                $set['keyword'] = '';
                $set['wxsys'] = '';
                $set['url'] = '';
                if (strpos($set['pagepath'], '/pages/') !== 0) {
                    $this->error('小程序的路径不正确');
                }
            } elseif ($_POST['menu_type'] == 4) {
				if (empty($this->config['pay_wxapp_paotui_appid'])) {
                    $this->error('您暂时还不能添加跑腿小程序');
                }
                $set['keyword'] = '';
                $set['wxsys'] = '';
                $set['url'] = '';
                $set['pagepath'] = 'paotui';
            } elseif ($_POST['menu_type'] == 5) {
                if (empty($this->config['pay_wxapp_group_appid'])) {
                    $this->error('您暂时还不能添加社群小程序');
                }
                $set['keyword'] = '';
                $set['wxsys'] = '';
                $set['url'] = '';
                $set['pagepath'] = 'group';
            }

            $set['mer_id'] = - 1;
            D('Diymenu_class')->where(array('id' => intval($_POST['id'])))->save($set);
            $this->success('更新成功');
        } else {
            $data = M('Diymenu_class')->where(array('id' => $this->_get('id')))->find();
            if ($data == false) {
                $this->error('您所操作的数据对象不存在！');
            }
			
			$class = M('Diymenu_class')->where(array('pid' => 0, 'mer_id' => - 1))->order('sort desc')->select(); // dump($class);
			
            if ($data['keyword'] != '') {
                $type = 1;
            } elseif ($data['pagepath'] == 'paotui') {
                $type = 4;
				$data['pagepath'] = '';
            } elseif ($data['pagepath'] == 'group') {
                $type = 5;
				$data['pagepath'] = '';
            } elseif ($data['pagepath'] != '') {
                $type = 3;
            } elseif ($data['url'] != '') {
                $type = 2;
            }
			
			$this->assign('class', $class);
			$this->assign('show', $data);
            $this->assign('type', $type);
            $this->display();
        }
    }
	
	public function class_del()
	{
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		$count = M('Diymenu_class')->where(array('pid' => $id, 'mer_id' => -1))->count();
		if (empty($count)) {
			$back = D('Diymenu_class')->where(array('id' => $id))->delete();
			if ($back == true) {
				$this->success('删除成功');
			} else {
				$this->error('删除失败' . $this->_get('id'));
			}
		} else {
			$this->error('请删除该分类下的子分类');
		}
	}

    public function class_send()
    {
        if (IS_GET) {
            $access_token_array = D('Access_token_expires')->get_access_token();
            if ($access_token_array['errcode']) {
                $this->error('获取access_token发生错误：错误代码' . $access_token_array['errcode'] . ',微信返回错误信息：' . $access_token_array['errmsg']);
            }
            
            $data = '{"button":[';
            
            $class = M('Diymenu_class')->where(array('pid' => 0, 'is_show' => 1, 'mer_id' => - 1))->limit(3)->order('sort desc,id ASC')->select();
            $kcount = M('Diymenu_class')->where(array('pid' => 0, 'is_show' => 1, 'mer_id' => - 1))->count();
            $k = 1;
            foreach ($class as $key => $vo) {
                // 主菜单
                $data .= '{"name":"' . $vo['title'] . '",';
                $c = M('Diymenu_class')->where(array('pid' => $vo['id'], 'is_show' => 1, 'mer_id' => - 1))->limit(5)->order('sort desc, id ASC')->select();
                $count = M('Diymenu_class')->where(array('pid' => $vo['id'], 'is_show' => 1, 'mer_id' => - 1))->count();
                if ($c != false) {
                    $data .= '"sub_button":[';
                } else {
                    if ($vo['keyword']) {
                        $data .= '"type":"click","key":"' . $vo['keyword'] . '"';
                    } elseif ($vo['url']) {
                        if (strpos($vo['url'], 'http') !== 0) {
                            $this->error('URL必须以http开头，菜单名称：' . $vo['title']);
                        }
                        $data .= '"type":"view", "url":"' . $vo['url'] . '"';
                    } elseif ($vo['pagepath'] == 'paotui') {
                        $data .= '"type":"miniprogram", "url":"' . $this->config['site_url'] . '/wap.php' . '", "pagepath":"/pages/index/index", "appid":"' . $this->config['pay_wxapp_paotui_appid'] . '"';
                    } elseif ($vo['pagepath'] == 'group') {
                        $data .= '"type":"miniprogram", "url":"' . $this->config['site_url'] . '/wap.php' . '", "pagepath":"/pages/homePage/index/index", "appid":"' . $this->config['pay_wxapp_group_appid'] . '"';
                    } elseif ($vo['pagepath']) {
                        if (strpos($vo['pagepath'], '/pages/') !== 0) {
                            $this->error('小程序的路径不正确，菜单名称：' . $vo['title']);
                        }
                        $data .= '"type":"miniprogram", "url":"' . $this->config['site_url'] . '/wap.php' . '", "pagepath":"' . $vo['pagepath'] . '", "appid":"' . $this->config['pay_wxapp_appid'] . '"';
                    } elseif ($vo['wxsys']) {
                        $data .= '"type":"' . $this->_get_sys('send', $vo['wxsys']) . '","key":"' . $vo['wxsys'] . '"';
                    }
                }
                
                $i = 1;
                foreach ($c as $voo) {
                    if ($i == $count) {
                        if ($voo['keyword']) {
                            $data .= '{"type":"click","name":"' . $voo['title'] . '","key":"' . $voo['keyword'] . '"}';
                        } elseif ($voo['url']) {
                            if (strpos($voo['url'], 'http') !== 0) {
                                $this->error('URL必须以http开头，菜单名称：' . $voo['title']);
                            }
                            $data .= '{"type":"view", "name":"' . $voo['title'] . '", "url":"' . $voo['url'] . '"}';
                        } elseif ($voo['pagepath'] == 'paotui') {
                            $data .= '{"type":"miniprogram", "name":"' . $voo['title'] . '", "url":"' . $this->config['site_url'] . '/wap.php' . '", "pagepath":"/pages/index/index", "appid":"' . $this->config['pay_wxapp_paotui_appid'] . '"}';
                        } elseif ($voo['pagepath'] == 'group') {
							$data .= '{"type":"miniprogram", "name":"' . $voo['title'] . '", "url":"' . $this->config['site_url'] . '/wap.php' . '", "pagepath":"/pages/homePage/index/index", "appid":"' . $this->config['pay_wxapp_group_appid'] . '"}';
						} elseif ($voo['pagepath']) {
                            if (strpos($voo['pagepath'], '/pages/') !== 0) {
                                $this->error('小程序的路径不正确，菜单名称：' . $voo['title']);
                            }
                            $data .= '{"type":"miniprogram", "name":"' . $voo['title'] . '", "url":"' . $this->config['site_url'] . '/wap.php' . '", "pagepath":"' . $voo['pagepath'] . '", "appid":"' . $this->config['pay_wxapp_appid'] . '"}';
                        } elseif ($voo['wxsys']) {
                            $data .= '{"type":"' . $this->_get_sys('send', $voo['wxsys']) . '","name":"' . $voo['title'] . '","key":"' . $voo['wxsys'] . '"}';
                        }
                    } else {
                        if ($voo['keyword']) {
                            $data .= '{"type":"click","name":"' . $voo['title'] . '","key":"' . $voo['keyword'] . '"},';
                        } elseif ($voo['url']) {
                            if (strpos($voo['url'], 'http') !== 0) {
                                $this->error('URL必须以http开头，菜单名称：' . $voo['title']);
                            }
                            $data .= '{"type":"view", "name":"' . $voo['title'] . '", "url":"' . $voo['url'] . '"},';
                        } elseif ($voo['pagepath'] == 'group') {
							$data .= '{"type":"miniprogram", "name":"' . $voo['title'] . '", "url":"' . $this->config['site_url'] . '/wap.php' . '", "pagepath":"/pages/homePage/index/index", "appid":"' . $this->config['pay_wxapp_group_appid'] . '"}';
						} elseif ($voo['pagepath'] == 'paotui') {
                            $data .= '{"type":"miniprogram", "name":"' . $voo['title'] . '", "pagepath":"/pages/index/index", "url":"' . $this->config['site_url'] . '/wap.php' . '", "appid":"' . $this->config['pay_wxapp_paotui_appid'] . '"},';
                        } elseif ($voo['pagepath']) {
                            if (strpos($voo['pagepath'], '/pages/') !== 0) {
                                $this->error('小程序的路径不正确，菜单名称：' . $voo['title']);
                            }
                            $data .= '{"type":"miniprogram", "name":"' . $voo['title'] . '", "pagepath":"' . $voo['pagepath'] . '", "url":"' . $this->config['site_url'] . '/wap.php' . '", "appid":"' . $this->config['pay_wxapp_appid'] . '"},';
                        } elseif ($voo['wxsys']) {
                            $data .= '{"type":"' . $this->_get_sys('send', $voo['wxsys']) . '","name":"' . $voo['title'] . '","key":"' . $voo['wxsys'] . '"},';
                        }
                    }
                    $i ++;
                }
                if ($c != false) {
                    $data .= ']';
                }
                
                if ($k == $kcount) {
                    $data .= '}';
                } else {
                    $data .= '},';
                }
                $k ++;
            }
            $data .= ']}';
            
            file_get_contents('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $access_token_array['access_token']);
            $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token_array['access_token'];
            fdump($data,'data');
            $rt = $this->api_notice_increment($url, $data);
            if ($rt['rt'] == false) {
				dump($rt);
                import('@.ORG.GetErrorMsg');
                $errmsg = GetErrorMsg::wx_error_msg($rt['errorno']);
                $this->error('操作失败,' . $rt['errorno'] . ':' . $errmsg);
            } else {
                $this->success('操作成功');
            }
            exit();
        } else {
            $this->error('非法操作');
        }
    }

	public function api_notice_increment($url, $data){
		$ch = curl_init();
		$header[] = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if ($js['errcode']=='0'){
				return array('rt'=>true,'errorno'=>0);
			}else {
				$errmsg=GetErrorMsg::wx_error_msg($js['errcode']);
				$this->error('发生错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$errmsg);
			}
		}
	}
	public function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
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
}