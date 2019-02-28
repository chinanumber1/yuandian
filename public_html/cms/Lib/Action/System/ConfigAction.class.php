<?php
/*
 * 修改站点配置
 *
 */
class ConfigAction extends BaseAction {
    public function index(){
		$database_config_group = D('Config_group');
		if(empty($_GET['galias'])){
			$condition_config_group['status'] = '1';
		}

		$group_list = $database_config_group->field(true)->where($condition_config_group)->order('`gsort` DESC,`gid` ASC')->select();
		foreach($group_list as &$gListValue){
			$gListValue['gname'] = str_replace('订餐',$this->config['meal_alias_name'],$gListValue['gname']);
			$gListValue['gname'] = str_replace('团购',$this->config['group_alias_name'],$gListValue['gname']);
			$gListValue['gname'] = str_replace('预约',$this->config['appoint_alias_name'],$gListValue['gname']);
			$gListValue['gname'] = str_replace('礼品',$this->config['gift_alias_name'],$gListValue['gname']);
		}
		$this->assign('group_list',$group_list);
		if(empty($_GET['galias'])){
			$gid = $this->_get('gid');
		}else{
			foreach($group_list as $value){
				if($value['galias'] == $_GET['galias']){
					$gid = $value['gid'];
					break;
				}
			}
			$header_file = $_GET['header'];
			$this->assign('header_file',$header_file);
		}
		if(empty($gid)) $gid = $group_list[0]['gid'];
		$this->assign('gid',$gid);
		$this->assign('galias', $_GET['galias']);

		$database_config = D('Config');
		$condition_config['gid'] = $gid;
		$condition_config['status'] = '1';
		$tmp_config_list = $database_config->where($condition_config)->order('`sort` DESC')->select();

		foreach($tmp_config_list as $key=>$value){
			$value['info'] = str_replace('订餐',$this->config['meal_alias_name'],$value['info']);
			$config_list[$value['tab_id']]['name'] = $value['tab_name'];
			$config_list[$value['tab_id']]['list'][] = $value;
		}
		$this->assign($this->build_html($config_list));

		if(!empty($_GET['galias'])){
			import('ORG.Util.Dir');
			Dir::delDirnotself('./runtime/Cache/System/');
		}
		$this->display();
	}
	public function amend(){
		if(IS_POST){
		    $isEditSendTime = false;
			$database_config = D('Config');
			foreach($_POST as $key=>$value){
				$data['name'] = $key;
				if(is_array($value)){
				    if($key == 'delivery_time' || $key == 'delivery_time2' || $key == 'delivery_time3'){
						$data['value'] = implode('-',$value);
					}
					if($key == 'service_delivery_time' || $key == 'service_delivery_time2'){
						$data['value'] = implode('-',$value);
					}
				}else{
					$data['value'] = trim(stripslashes(htmlspecialchars_decode($value)));
				}

				if($key=='site_url'){
					$data['value'] = trim($value);
				}

				if($key == 'alipay_app_prikey' || $key == 'pay_alipayh5_merchant_private_key' || $key == 'pay_alipayh5_public_key'){
					$data['value'] = str_replace(array(PHP_EOL,' '),'',$value);
				}
				if($key == 'new_pay_alipay_app_public_key' || $key == 'new_pay_alipay_app_private_key'){
					$data['value'] = str_replace(array(PHP_EOL,' '),'',$value);
				}
				
				if($_POST['many_city'] && (strpos($this->config['site_url'],'weihubao.com') || strpos($this->config['site_url'],'dazhongbanben.com'))){
					$this->error('临时域名不能开启多城市功能');
				}
				if($_POST['many_city'] && (!$_POST['many_city_top_domain'] || !$_POST['many_city_main_domain'])){
					$this->error('若开启多城市，请填写顶级域名和主域名');
				}
				if($_POST['many_city'] && strpos($_POST['many_city_main_domain'],'.') !== FALSE){
					$this->error('请正式填写您的主域名的二级域名部分，用来跳转。一般为 www，不能带有 “ . ”（符号点）');
				}

				if(strpos($key,'score_max') && strpos($value,'%')  ){
					$tmp_v = floatval(str_replace('%','',$value));
					if( $tmp_v>100){
						$this->error('积分使用数据不能大于100%');
					}
				}
				


				$num = $database_config->where(array('name'=>$key))->count();
				//分润开关，计划任务初始化到第二天凌晨
				if($key == 'open_score_fenrun'){
					M('Process_plan')->where(array('file'=>'user_fenrun'))->setField('plan_time',(strtotime(date("Y-m-d",$_SERVER['REQUEST_TIME']))+86400));
				}
				if($num>0){
					$database_config->data($data)->save();
				}else{
					$database_config->data($data)->add();
				}
				if ($key == 'deliver_send_time') {
				    if ($_POST['deliver_send_time'] != $this->config['deliver_send_time']) {
				        $isEditSendTime = true;
				    }
				}

				if ($key == 'wechat_sourceid') {
					$data['name'] = 'wechat_token';
					$data['value'] = md5('pigcms_wechat_token' . $data['value']);
					$database_config->data($data)->save();
				}
				if($key == 'weidian_url' && $value && !file_put_contents('./api/weidian.urls',$data['value'])){
					$this->error('配置保存失败，请检查网站根目录下的api文件夹是否拥有可写权限。');
				}
				if($key == 'appoint_site_url' && $value && !file_put_contents('./api/appoint.urls',$data['value'])){
					$this->error('配置保存失败，请检查网站根目录下的api文件夹是否拥有可写权限。');
				}
				if($key == 'portal_switch' && !file_put_contents('./api/default.urls',$data['value'])){
					$this->error('配置保存失败，请检查网站根目录下的api文件夹是否拥有可写权限。');
				}
			}
			S(C('now_city').'config',null);

			if ($isEditSendTime) {
		        $sql = "UPDATE pigcms_merchant_store_shop SET ";
		        $sql .= "send_time={$_POST['deliver_send_time']}, s_send_time={$_POST['deliver_send_time']}, sort_time = CASE ";
		        $sql .= "WHEN send_time_type =0 THEN {$_POST['deliver_send_time']} + work_time ";
		        $sql .= "WHEN send_time_type =1 THEN {$_POST['deliver_send_time']} + work_time *60 ";
		        $sql .= "WHEN send_time_type =2 THEN {$_POST['deliver_send_time']} + work_time *1440 ";
		        $sql .= "WHEN send_time_type =3 THEN {$_POST['deliver_send_time']} + work_time *10080 ";
		        $sql .= "WHEN send_time_type =4 THEN {$_POST['deliver_send_time']} + work_time *43200 END";
		        $sql .= " WHERE (`deliver_type`=0 OR `deliver_type`=3) AND (`s_send_time`=0 OR `s_send_time`={$this->config['deliver_send_time']})";
		        D()->execute($sql);
			}
			
			if(str_replace(array('.gov.cn','.com.cn','.weihubao.com','.dazhongbanben.com'),'',$_SERVER['HTTP_HOST']) == $_SERVER['HTTP_HOST']){
				$top_domain = $domainArr[$count-2].'.'.$domainArr[$count-1];
			}else{
				$top_domain = $domainArr[$count-3].'.'.$domainArr[$count-2].'.'.$domainArr[$count-1];
			}
			$top_domain = strtolower($top_domain);
			unlink('./source/plan/'.$top_domain.'md5.php');
			unlink('./source/plan/time/'.$top_domain.'process.time');

			$this->success('修改成功!');
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}

	public  function ajax_upload_wx_media(){
		if($_FILES['imgFile']['error'] != 4){
			$path = $_GET['path'] ?$_GET['path']:'system';
			$image = D('Image')->handle(rand(1,100), $path, 1);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$url = $_SERVER['DOCUMENT_ROOT'].$image['url']['imgFile'];
				$mode = D('Access_token_expires');
				$res = $mode->get_access_token();
				import('ORG.Net.Http');
				$http = new Http();
				$file  = $url;
				$return = $http->curlUploadFile('https://api.weixin.qq.com/cgi-bin/media/upload?type=image&access_token='.$res['access_token'],$file,1);
				$return = json_decode($return,true);

				exit(json_encode(array('error' => 0, 'url' => $return['media_id'],'title' => $image['url']['imgFile'])));
			}

		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}

	public  function ajax_upload_pic_wx(){
		if($_FILES['imgFile']['error'] != 4){
			$path = $_GET['path'] ?$_GET['path']:'merchant';
			$image = D('Image')->handle(rand(1), $path, 1);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$url = $_SERVER['DOCUMENT_ROOT'].$image['url']['imgFile'];
				$mode = D('Access_token_expires');
				$res = $mode->get_access_token();
				import('ORG.Net.Http');
				$http = new Http();
				$file  = $url;
				$return = $http->curlUploadFile('https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$res['access_token'],$file,1);
				$return = json_decode($return,true);
				exit(json_encode(array('error' => 0, 'url' => $return['url'])));
			}

		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}

	public function show(){
		$this->assign('bg_color','#F3F3F3');
		$config = D('Config')->get_config();
		$this->display();
	}
	protected function build_html($config_list){
		if(is_array($config_list)){
			$config_html = '';
			if(count($config_list) > 1) $has_tab = true;
			if($has_tab) $config_tab_html = '<ul class="tab_ul">';
			$pigcms_auto_key = 1;
			foreach($config_list as $pigcms_key=>$pigcms_value){
				if($has_tab) $config_tab_html .= '<li '.($pigcms_auto_key==1 ? 'class="active"' : '').'><a data-toggle="tab" href="#tab_'.$pigcms_key.'">'.($pigcms_value['name'] ? $pigcms_value['name'] : '基本设置').'</a></li>';

				$config_html .= '<table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="display:none;" id="tab_'.$pigcms_key.'">';
				foreach($pigcms_value['list'] as $key=>$value){
					$tmp_type_arr = explode('&',$value['type']);
					$type_arr = array();
					foreach($tmp_type_arr as $k=>$v){
						$tmp_value = explode('=',$v);
						$type_arr[$tmp_value[0]] = $tmp_value[1];
					}

					$config_html .= '<tr>'; 
					if (in_array($value['name'],array('service1','service2'))) {
						$config_html .= '<th width="160" style="color:red;">'.$value['info'].'：</th>' ;
					}else{
						$config_html .= '<th width="160">'.$value['info'].'：</th>' ;
					}
					$config_html .= '<td>';
					if($type_arr['type'] == 'text'){
						$size = !empty($type_arr['size']) ? $type_arr['size'] : '60';
						$config_html .= '<input type="text" class="input-text" name="'.$value['name'].'" id="config_'.$value['name'].'" value="'.$value['value'].'" size="'.$size.'" validate="'.$type_arr['validate'].'" tips="'.$value['desc'].'"/>';
					}else if($type_arr['type'] == 'textarea'){
						$rows = !empty($type_arr['rows']) ? $type_arr['rows'] : '4';
						$cols = !empty($type_arr['cols']) ? $type_arr['cols'] : '80';
						$config_html .= '<textarea rows="'.$rows.'" cols="'.$cols.'" name="'.$value['name'].'" id="config_'.$value['name'].'" validate="'.$type_arr['validate'].'" tips="'.$value['desc'].'">'.$value['value'].'</textarea>';
					}else if($type_arr['type'] == 'radio'){
						$radio_option = explode('|',$type_arr['value']);
						foreach($radio_option as $radio_k=>$radio_v){
							$radio_one = explode(':',$radio_v);
							if($radio_k == 0){
								$config_html .= '<span class="cb-enable"><label class="cb-enable '.($value['value']==$radio_one[0] ? 'selected' : '').'"><span>'.$radio_one[1].'</span><input type="radio" name="'.$value['name'].'" value="'.$radio_one[0].'" '.($value['value']==$radio_one[0] ? 'checked="checked"' : '').'/></label></span>';
							}else if($radio_k == 1){
								$config_html .= '<span class="cb-disable"><label class="cb-disable '.($value['value']==$radio_one[0] ? 'selected' : '').'"><span>'.$radio_one[1].'</span><input type="radio" name="'.$value['name'].'" value="'.$radio_one[0].'" '.($value['value']==$radio_one[0] ? 'checked="checked"' : '').'/></label></span>';
							}
						}
						if($value['desc']){
							$config_html .= '<em tips="'.$value['desc'].'" class="notice_tips"></em>';
						}
					}else if($type_arr['type'] == 'image'){
						$config_html .= '<span class="config_upload_image_btn"><input type="button" value="上传图片" class="button" style="margin-left:0px;margin-right:10px;"/></span><input type="text" class="input-text input-image" name="'.$value['name'].'" id="config_'.$value['name'].'" value="'.$value['value'].'" size="48" validate="'.$type_arr['validate'].'" tips="'.$value['desc'].'"/> ';
					}else if($type_arr['type'] == 'file'){
						$config_html .= '<span class="config_upload_file_btn" file_validate="'.$type_arr['file'].'"><input type="button" value="上传文件" class="button" style="margin-left:0px;margin-right:10px;"/></span><input type="text" class="input-text input-file" name="'.$value['name'].'" id="config_'.$value['name'].'" value="'.$value['value'].'" size="48" readonly="readonly" validate="'.$type_arr['validate'].'" tips="'.$value['desc'].'"/> ';
					}else if($type_arr['type'] == 'select'){
						$radio_option = explode('|',$type_arr['value']);
						$config_html .= '<select name="'.$value['name'].'">';
						foreach($radio_option as $radio_k=>$radio_v){
							$radio_one = explode(':',$radio_v);
							$config_html .= '<option value="'.$radio_one[0].'" '.($value['value']==$radio_one[0] ? 'selected="selected"' : '').'>'.$radio_one[1].'</option>';
						}
						$config_html .= '</select>';
						if($value['desc']){
							$config_html .= '<em tips="'.$value['desc'].'" class="notice_tips"></em>';
						}
					}else if($type_arr['type'] == 'twoTime'){
						$tmpTime = explode('-',$value['value']);
						$config_html .= '<input type="text" class="input-text" name="'.$value['name'].'[]" value="'.$tmpTime[0].'" size="5" onfocus="WdatePicker({isShowClear:false,dateFmt:\''.$type_arr['format'].'\'})"/>&nbsp;-&nbsp;&nbsp;';
						$config_html .= '<input type="text" class="input-text" name="'.$value['name'].'[]" value="'.$tmpTime[1].'" size="5" onfocus="WdatePicker({isShowClear:false,dateFmt:\''.$type_arr['format'].'\'})"/>';
					}else if($type_arr['type'] == 'webTpl'){
						$hostArr = parse_url($this->config['site_url']);
						import('ORG.Util.Dir');
						$dirObj = new Dir(TMPL_PATH.C('DEFAULT_GROUP'));
						$radio_option = array();
						foreach($dirObj->_values as $dirValue){
							if($dirValue['isDir']){
								$tpl_theme_ini = parse_ini_file($dirValue['pathname'].'/theme.ini');
								if($tpl_theme_ini){
									$tmpArr = array(
											'name'=>$tpl_theme_ini['name'],
											'path'=>$tpl_theme_ini['path'],
											'isUse'=> ($tpl_theme_ini['path'] == $value['value'] ? true : false)
										);
									if(empty($tpl_theme_ini['domain']) || $tpl_theme_ini['domain'] == $hostArr['host']){
										array_push($radio_option,$tmpArr);
									}
								}
							}
						}
						$config_html .= '<select name="'.$value['name'].'">';
						foreach($radio_option as $radio_k=>$radio_v){
							$config_html .= '<option value="'.$radio_v['path'].'" '.($radio_v['isUse'] ? 'selected="selected"' : '').'>'.$radio_v['name'].'</option>';
						}
						$config_html .= '</select>';
						if($value['desc']){
							$config_html .= '<em tips="'.$value['desc'].'" class="notice_tips"></em>';
						}
					}else if($type_arr['type'] == 'wapTpl'){
						$hostArr = parse_url($this->config['site_url']);
						import('ORG.Util.Dir');
						$dirObj = new Dir(TMPL_PATH.'Wap/');
						$radio_option = array();
						foreach($dirObj->_values as $dirValue){
							if($dirValue['isDir']){
								$tpl_theme_ini = parse_ini_file($dirValue['pathname'].'/theme.ini');
								if($tpl_theme_ini){
									$tmpArr = array(
											'name'=>$tpl_theme_ini['name'],
											'path'=>$tpl_theme_ini['path'],
											'isUse'=> ($tpl_theme_ini['path'] == $value['value'] ? true : false)
										);
									if(empty($tpl_theme_ini['domain']) || $tpl_theme_ini['domain'] == $hostArr['host']){
										array_push($radio_option,$tmpArr);
									}
								}
							}
						}
						$config_html .= '<select name="'.$value['name'].'">';
						foreach($radio_option as $radio_k=>$radio_v){
							$config_html .= '<option value="'.$radio_v['path'].'" '.($radio_v['isUse'] ? 'selected="selected"' : '').'>'.$radio_v['name'].'</option>';
						}
						$config_html .= '</select>';
						if($value['desc']){
							$config_html .= '<em tips="'.$value['desc'].'" class="notice_tips"></em>';
						}
					}
					$config_html .= '</td>';
					$config_html .= '</tr>';
				}
				$config_html .= '</table>';
				$pigcms_auto_key++;
			}
			if($has_tab) $config_tab_html .= '</ul>';

			$return_config['config_html'] = $config_html;
			if($has_tab) $return_config['config_tab_html'] = $config_tab_html;
			return $return_config;
		}
	}

	public function ajax_upload_pic(){
		if($_FILES['imgFile']['error'] != 4){

			$image = D('Image')->handle($this->system_session['id'], 'config', 0, array('size' => 3), false);
			if (!$image['error']) {
				exit(json_encode(array('error' => 0,'url' => $image['url']['imgFile'], 'title' => $image['title']['imgFile'])));
			}
			exit(json_encode($image));

// 			$img_admin_id = sprintf("%09d", $this->system_session['id']);
// 			$rand_num = substr($img_admin_id,0,3) . '/' . substr($img_admin_id,3,3) . '/' . substr($img_admin_id,6,3);
// 			$upload_dir = "./upload/images/{$rand_num}/";
// 			if(!is_dir($upload_dir)){
// 				mkdir($upload_dir,0777,true);
// 			}
// 			$upload->maxSize = 3*1024*1024;
// 			$upload->allowExts = array('jpg','jpeg','png','gif');
// 			$upload->savePath = $upload_dir;
// 			$upload->saveRule = 'uniqid';
// 			if($upload->upload()){
// 				$title = $rand_num.','.$uploadList[0]['savename'];
// 				exit(json_encode(array('error' => 0,'url' => '/upload/images/'.$rand_num.'/'.$uploadList[0]['savename'],'title'=>$title)));
// 			}else{
// 				exit(json_encode(array('error' => 1,'message' =>$upload->getErrorMsg())));
// 			}
		}else{
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
	public function ajax_upload_file(){
		if(empty($_GET['name'])){
			exit(json_encode(array('error' => 1,'message' =>'不知道您要上传到哪个配置项，请重试。')));
		}
		$now_config = D('Config')->field('`name`,`type`')->where(array('name'=>$_GET['name']))->find();
		if(empty($now_config)){
			exit(json_encode(array('error' => 1,'message' =>'您正在上传的配置项不存在，请重试。')));
		}
		$tmp_type_arr = explode('&',$now_config['type']);
		$type_arr = array();
		foreach($tmp_type_arr as $k=>$v){
			$tmp_value = explode('=',$v);
			$type_arr[$tmp_value[0]] = $tmp_value[1];
		}
		$allowExts = array_key_exists('file',$type_arr) ? explode(',',$type_arr['file']) : array();
		if($_FILES['imgFile']['error'] != 4){

// 			$image = D('Image')->handle($this->system_session['id'], 'files');
// 			if (!$image['error']) {
// 				exit(json_encode(array('error' => 0,'url' => $image['url']['imgFile'],'title' => $image['title']['imgFile'])));
// 			} else {
// 				exit(json_encode(array('error' => 1,'message' => $image['msg'])));
// 			}

			$img_admin_id = sprintf("%09d", $this->system_session['id']);
			$rand_num = substr($img_admin_id,0,3) . '/' . substr($img_admin_id,3,3) . '/' . substr($img_admin_id,6,3);
			$upload_dir = "./upload/files/{$rand_num}/";
			if(!is_dir($upload_dir)){
				mkdir($upload_dir,0777,true);
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->maxSize = 10*1024*1024;
			$upload->allowExts = $allowExts;
			$upload->savePath = $upload_dir;
			$upload->saveRule = 'uniqid';
			if($upload->upload()){
				$uploadList = $upload->getUploadFileInfo();
				$title = $rand_num.','.$uploadList[0]['savename'];
				exit(json_encode(array('error' => 0,'url' =>'./upload/files/'.$rand_num.'/'.$uploadList[0]['savename'],'title'=>$title)));
			}else{
				exit(json_encode(array('error' => 1,'message' =>$upload->getErrorMsg())));
			}
		}else{
			exit(json_encode(array('error' => 1,'message' =>'没有选择文件')));
		}
	}
	public function live_service(){
		if (empty($this->config['site_url'])) {
			exit(json_encode(array('error_code' => true, 'msg' => '先填写您网站的域名')));
		}
		$live_service = new live_service();
		$live_service->create();
	}
	public function im(){
		if (empty($this->config['site_url'])) {
			exit(json_encode(array('error_code' => true, 'msg' => '先填写您网站的域名')));
		}
		if (empty($this->config['wechat_appid']) || empty($this->config['wechat_appsecret'])) {
			exit(json_encode(array('error_code' => true, 'msg' => '先设置站点的微信公众号信息')));
		}
		$im = new im();
		$im->create();
	}
}
