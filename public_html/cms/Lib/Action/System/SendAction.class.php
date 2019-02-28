<?php
class SendAction extends BaseAction
{
	private $face_key = array(1 => "/::)", "/::~", "/::B", "/::|", "/:8-)", "/::<", "/::$", "/::X", "/::Z", "/::'(", "/::-|", "/::@", "/::P", "/::D", "/::O", "/::(", "/::+", "/:--b", "/::Q", "/::T", "/:,@P", "/:,@-D", "/::d", "/:,@o", "/::g", "/:|-)", "/::!", "/::L", "/::>", "/::,@", "/:,@f", "/::-S", "/:?", "/:,@x", "/:,@@", "/::8", "/:,@!", "/:!!!", "/:xx", "/:bye", "/:wipe", "/:dig", "/:handclap", "/:&amp;-(", "/:B-)", "/:<@", "/:@>", "/::-O", "/:>-|", "/:P-(", "/::'|", "/:X-)", "/::*", "/:@x", "/:8*", "/:pd", "/:<W>", "/:beer", "/:basketb", "/:oo", "/:coffee", "/:eat", "/:pig", "/:rose", "/:fade", "/:showlove", "/:heart", "/:break", "/:cake", "/:li", "/:bome", "/:kn", "/:footb", "/:ladybug", "/:shit", "/:moon", "/:sun", "/:gift", "/:hug", "/:strong", "/:weak", "/:share", "/:v", "/:@)", "/:jj", "/:@@", "/:bad", "/:lvu", "/:no", "/:ok", "/:love", "/:<L>", "/:jump", "/:shake", "/:<O>", "/:circle", "/:kotow", "/:turn", "/:skip", "/:oY", "/:#-0", "/:hiphot", "/:kiss", "/:<&amp;", "/:&amp;>");
	private $face_image = array();
	
	private function qq_face()
	{
		for ($i = 1; $i < 106; $i++) $this->face_image[$i] = '<img src="/static/images/qq/' . $i . '.gif" />';
	}
	public function index()
	{

		$from_type=array(
				'-1'=>'全部粉丝',
				'0'=>'扫描商家产品二维码',
				'1'=>'扫描商家二维码',
				'2'=>'平台赠送',
				'3'=>'扫描产品推广二维码',
				'5'=>$this->config['group_alias_name'],
				'6'=>$this->config['shop_alias_name'],
				'7'=>$this->config['meal_alias_name'],
				'8'=>$this->config['appoint_alias_name'],
				'9'=>$this->config['cash_alias_name'],
		);
		$table = array(C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'send_log'=>'s');
		$condition = "`m`.`mer_id`=`s`.`mer_id`";
		if ($this->system_session['area_id']) {
			$now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
			if($now_area['area_type']==3){
				$area_index = 'area_id';
			}elseif($now_area['area_type']==2){
				$area_index = 'city_id';
			}elseif($now_area['area_type']==1){
				$area_index = 'province_id';
			}
			$condition .= " AND `m`.`{$area_index}`='{$this->system_session['area_id']}'";
		}
		$log_list = D('')->table($table)->where($condition)->order('s.status ASC, s.pigcms_id DESC')->select();
		$this->assign('list', $log_list);
		$this->assign('from_type', $from_type);
		$this->display();
	}
	
	public function send_del()
	{
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	
		if (D('Send_log')->where(array('pigcms_id' => $id))->save(array('status' => 2))) {
			// 拒绝后讲积分返回给该商户
			$send_log = D('Send_log')->where(array('pigcms_id' => $id))->find();
			
			$table = array(C('DB_PREFIX').'send_user'=>'s',C('DB_PREFIX').'user'=>'u');
			$condition = "`s`.`openid`=`u`.`openid` AND `s`.`log_id`='$id'";
				
			$fans_count = D('')->table($table)->where($condition)->count();
			$exchangeScore = $fans_count*$this->config['customer_one_score'];
				
			$database_merchant = D('Merchant');
			$condition_merchant['mer_id'] = $send_log['mer_id'];
			$database_merchant->where($condition_merchant)->setInc('plat_score',$exchangeScore);
			
			$this->success('拒绝成功');
		} else {
			$this->error('请删除该分类下的子分类');
		}
	}
	
	public function info()
	{
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		$table = array(C('DB_PREFIX').'send_user'=>'s',C('DB_PREFIX').'user'=>'u');
		$condition = "`s`.`openid`=`u`.`openid` AND `s`.`log_id`='$id'";
		
		$fans_list = D('')->table($table)->where($condition)->select();
		$this->assign('fans_list',$fans_list);
		$this->display();
	}
	
	public function txtinfo()
	{
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		$source = D('Source_material')->where(array('pigcms_id' => $id))->find();
		$ids = unserialize($source['it_ids']);
		$image_text = D('Image_text')->field(true)->where(array('pigcms_id' => array('in', $ids)))->select();
		$result = array();
		foreach ($image_text as $txt) {
			$result[$txt['pigcms_id']] = $txt;
		}
		$image_text = array();
		foreach ($ids as $id) {
			$image_text[] = isset($result[$id]) ? $result[$id] : array();
		}
		
		$this->assign('image_text',$image_text);
		$this->display();
	}
	
	public function send()
	{
		if(IS_GET){
			set_time_limit(0);
			$send_id = isset($_GET['send']) ? intval($_GET['send']) : 0;
			if (empty($send_id)) $this->error('没有发送内容');
			$log = D('Send_log')->where(array('pigcms_id' => $send_id))->find();
			if (empty($log))$this->error('没有发送内容');
			
			D('Send_log')->where(array('pigcms_id' => $send_id))->save(array('status' => 3));
			
			import('@.ORG.plan');
			$plan_class = new plan();
			$param = array(
				'file'=>'wechat_mass',
				'plan_time'=>time(),
				'param'=>array(
					'id'=>$send_id,
				),
			);
			$plan_class->addTask($param);
			
			$this->success('处理成功！正在后台进行发送。', U('Send/index'));
			exit;
		} else {
			$this->error('非法操作');
		}
	}

	public function message()
	{
		$this->qq_face();
		$count = D('Weixin_message')->count();
		import('@.ORG.system_page');
		$p = new Page($count, 15);

		$result = M('Weixin_message')->join(' as w left join '.C('DB_PREFIX').'user as u ON w.openid=u.openid')->field(' u.nickname, u.avatar, u.truename, w.openid , w.question, w.answer, w.dateline ')->order('w.dateline DESC')->limit($p->firstRow,$p->listRows)->select();
		foreach ($result as &$row) {
			$row['question'] = str_replace($this->face_key, $this->face_image, $row['question']);
			$row['answer'] = str_replace($this->face_key, $this->face_image, $row['answer']);
		}

		$this->assign('message_list', $result);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->display();
	}
	
	public function detail()
	{
		$this->qq_face();
		$openid = isset($_GET['openid']) ? htmlspecialchars(trim($_GET['openid'])) : '';
		$user = D('User')->field(true)->where(array('openid' => $openid))->find();
		if (empty($user)) $this->error('不存在的询问对象');
		$logs = D('Weixin_message_log')->field(true)->where(array('openid' => $openid))->order('id DESC')->limit('0, 100')->select();
		foreach ($logs as &$row) {
			$row['message'] = str_replace($this->face_key, $this->face_image, $row['message']);
		}
		krsort($logs);
		$this->assign('message_log', $logs);
		$this->assign('user', $user);
		$this->display();
	}
	public function sendUser()
	{
		$openid = isset($_POST['openid']) ? trim(htmlspecialchars($_POST['openid'])) : '';
		$content = isset($_POST['content']) ? trim(htmlspecialchars($_POST['content'])) : '';
		if (empty($openid)) {
			exit(json_encode(array('errcode' => 1, 'errmsg' => '回复对象不能为空')));
		}
		if (empty($content)) {
			exit(json_encode(array('errcode' => 1, 'errmsg' => '回复内容不能为空')));
		}
		
		$access_token_array = D('Access_token_expires')->get_access_token();
		if ($access_token_array['errcode']) {
			$this->error('获取access_token发生错误：错误代码' . $access_token_array['errcode'] .',微信返回错误信息：' . $access_token_array['errmsg']);
		}
		$access_token = $access_token_array['access_token'];
		
		$send_to_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
		$str = '{
		    "touser":"' . $openid . '",
		    "msgtype":"text",
		    "text":{"content":"' . $content . '"}
		}';
		
		import('ORG.Net.Http');
		$result = Http::curlPost($send_to_url, $str);
		if ($result['errcode']) {
			exit(json_encode($result));
		} else {
			D('Weixin_message')->where(array('openid' => $openid))->save(array('answer' => $content));
			$log_data = array('openid' => $openid, 'type' => 1, 'message' => $content, 'dateline' => time());
			D('Weixin_message_log')->add($log_data);
			exit(json_encode(array('errcode' => 0, 'errmsg' => 'ok', 'time' => date('Y-m-d H:i:s'))));
		}
	}

	function api_notice_increment($url, $data){
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
	function curlGet($url){
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

	//渠道二维码列表
	public function chanel_msg_list(){
		if (!empty($_GET['keyword'])) {
			if ($_GET['searchtype'] == 'chanel_id') {
				$condition_chanel['chanel_id'] = $_GET['keyword'];
			} else if ($_GET['searchtype'] == 'title') {
				$condition_chanel['title'] = array('like', '%' . $_GET['keyword'] . '%');
			} 
		}
		$condition_chanel['type_id'] = 0;
		//排序 /*/
		$order_string = '`chanel_id` ASC';
		if($_GET['sort']){
			switch($_GET['sort']){
				case 'uid':
					$order_string = '`uid` DESC';
					break;
				case 'lastTime':
					$order_string = '`last_time` DESC';
					break;
				case 'money':
					$order_string = '`now_money` DESC';
					break;
				case 'score':
					$order_string = '`score_count` DESC';
					break;
			}
		}
		$chanel = M('Chanel_msg_list');
		$count_chanel = $chanel->where($condition_chanel)->count();
		import('@.ORG.system_page');
		$p = new Page($count_chanel, 15);
		$chanel_list = $chanel->field('chanel_id,title,add_time,last_time,status')->where($condition_chanel)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->getField('chanel_id,title,add_time,last_time,status');
		$this->assign('chanel_list',$chanel_list);
		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);

		$this->display();
	}
	public function change_chanel_status(){
		if(IS_POST){
			if($_POST['status']){
				$data['status']=0;
			}else{
				$data['status']=1;
			}
			if($_POST['is_mer']){
				$data['status']=$_POST['status'];
			}
			if(!D('Chanel_msg_list')->where('chanel_id='.$_POST['chanel_id'])->save($data)){
				$this->error('改变状态失败');
			}else{
				$this->success('改变状态成功');
			}
		}
	}
	public function chanel_msg_add(){
		if(IS_POST){
			$data['title']=$_POST['Full_title'];
			$data['add_time']=$data['last_time']=time();
			$data['status']=1;
			$data['type_id']=0;
			$fid = D('Chanel_msg_list')->add($data);
			foreach($_POST[title] as $key=>$v){
				if(empty($v)||empty($_POST['img'][$key])||empty($_POST['des'][$key])||empty($_POST['url'][$key])){
					D('Chanel_msg_list')->where('chanel_id='.$fid)->delete();
					$this->error('数据不能为空,内容保存失败');exit;
				}
				$data_content[]=array('fid'=>$fid,'title'=>$v,'img'=>$_POST['img'][$key],'des'=>$_POST['des'][$key],'url'=>html_entity_decode($_POST['url'][$key]));
			}

			if(!D('Chanel_msg_content')->addAll($data_content)){
				$this->error("保存失败");
			}else{
				$this->success("保存成功");
			}
			
		}else{
			$this->display();
		}
	}
	
	public function chanel_msg_edit(){
		if(IS_POST){
			$data['title']=$_POST['Full_title'];
			$data['last_time']=time();
			D('Chanel_msg_list')->where('chanel_id='.$_POST['chanel_id'])->save($data);
			$flag = false;

			foreach($_POST[title] as $key=>$v){
				if(empty($v)||empty($_POST['img'][$key])||empty($_POST['des'][$key])||empty($_POST['url'][$key])){
					$this->error('数据不能为空,内容保存失败');exit;
				}
				if(empty($_POST['id'][$key])){
					$data_content = array('fid'=>$_POST['chanel_id'],'title'=>$v,'img'=>$_POST['img'][$key],'des'=>$_POST['des'][$key],'url'=>html_entity_decode($_POST['url'][$key]));
					D('Chanel_msg_content')->add($data_content);
					$flag = true;
				}else{
					$data_content = array('id'=>$_POST['id'][$key],'title'=>$v,'img'=>$_POST['img'][$key],'des'=>$_POST['des'][$key],'url'=>html_entity_decode($_POST['url'][$key]));
					if(!$res=D('Chanel_msg_content')->where('id='.$_POST['id'][$key])->save($data_content)){
						if(!$flag){
							$flag=false;
						}
					}else{
						$flag=true;
					}
				}
			}
			if(!$flag){
				$this->error('编辑失败');
			}else{
				$this->success('编辑成功');
			}
		}else{
			$chanel_id=$_GET['chanel_id'];
			$Full_title =  D('Chanel_msg_list')->where('chanel_id='.$chanel_id)->getField('title');
			$this->assign('Full_title',$Full_title);
			$chanel_content = D('Chanel_msg_content')->where('fid='.$chanel_id)->select();
			$this->assign('chanel_content',$chanel_content);
			$this->display();
		}
	}

	public function mer_chanel_info(){

		$chanel_id=$_GET['chanel_id'];

		$Full_title =  D('Chanel_msg_list')->where('chanel_id='.$chanel_id)->getField('title');
		$this->assign('Full_title',$Full_title);
		$chanel_content = D('Chanel_msg_content')->where('fid='.$chanel_id)->select();
		$this->assign('chanel_content',$chanel_content);
		$this->display();
	}

	public function delete_chanel_msg_list(){
		if(IS_GET){
			if(!empty($_GET['delete_content'])){
				if(D('Chanel_msg_content')->where('id='.$_GET['delete_content'])->delete()){
					$this->success("删除成功");exit;
				}else{
					$this->error("删除失败");
				}
				
			}
		}
		if(IS_POST){
			if(!D('Chanel_msg_list')->where('chanel_id='.$_GET['chanel_id'])->delete()||!D('Chanel_msg_content')->where('fid='.$_GET['chanel_id'])->delete()){
				$this->error("删除失败");
			}else{
				$this->success("删除成功");exit;
			}
		}
	}

	public function mer_chanel_list(){
		if (!empty($_GET['keyword'])) {
			if ($_GET['searchtype'] == 'chanel_id') {
				$condition_chanel['chanel.chanel_id'] = $_GET['keyword'];
			} else if ($_GET['searchtype'] == 'title') {
				$condition_chanel['chanel.title'] = array('like', '%' . $_GET['keyword'] . '%');
			}else if ($_GET['searchtype'] == 'mer_name') {
				$condition_chanel['mer.name'] = array('like', '%' . $_GET['keyword'] . '%');
			}
		}
		$condition_chanel['chanel.type_id'] = array('neq','0');
		//排序 /*/
		$order_string = '`chanel_id` ASC';
		if($_GET['sort']){
			switch($_GET['sort']){
				case 'uid':
					$order_string = '`uid` DESC';
					break;
				case 'lastTime':
					$order_string = '`last_time` DESC';
					break;
				case 'money':
					$order_string = '`now_money` DESC';
					break;
				case 'score':
					$order_string = '`score_count` DESC';
					break;
			}
		}
		$chanel = M('Chanel_msg_list chanel');
		$count_chanel = $chanel->where($condition_chanel)->count();
		import('@.ORG.system_page');
		$p = new Page($count_chanel, 15);
		$chanel_list = $chanel->join(C('DB_PREFIX').'merchant mer ON chanel.type_id = mer.mer_id')->where($condition_chanel)->field('mer.name,chanel.chanel_id,chanel.qrcode_id,chanel.title,chanel.add_time,chanel.last_time,chanel.status')->limit($p->firstRow . ',' . $p->listRows)->select();
		$this->assign('chanel_list',$chanel_list);
		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);

		$this->assign('chanel_list',$chanel_list);
		$this->display();
	}

	//微信平台群发
	public function all_send(){
		$count =  M('Send_log')->where(array('type'=>10))->count();
		import('@.ORG.system_page');
		$p = new Page($count, 15);
		$list  = M('Send_log')->where(array('type'=>10))->order('pigcms_id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
		$pagebar = $p->show();
		$send_type = array(
			0=>'按粉丝等级',
			1=>'指定粉丝',
			2=>'所有粉丝',
		);
		foreach($list as &$v){
			$v['success_num'] = M('Send_user')->where(array('log_id'=>$v['pigcms_id'],'status'=>1))->count();
			$v['fail_num'] = M('Send_user')->where(array('log_id'=>$v['pigcms_id'],'status'=>2))->select();
			if($v['success_num']>0 || $v['fail_num']>0){
				$v['sended']  =1;
			}else{
				$v['sended'] = 0;
			}
		}
		$this->assign('pagebar', $pagebar);
		$this->assign('list',$list);
		$this->assign('send_type',$send_type);
		$this->display();
	}

	public function add_all_send(){
		$levelDb = M('User_level');
		$tmparr = $levelDb->field(true)->order('id ASC')->select();
		$levelarr = array();
		if ($tmparr) {
			foreach ($tmparr as $vv) {
				$levelarr[$vv['level']] = $vv;
			}
		}
		if($_GET['id']){
			$send_log = D("Send_log")->where(array('pigcms_id' => $_GET['id']))->find();
			$user_list = M('User')->where(array('uid'=>array('in',trim($send_log['send_to'],','))))->select();
			$source = D('Source_material')->where(array('pigcms_id' => $send_log['c_id']))->find();
			$ids = unserialize($source['it_ids']);
			foreach($ids as $v){
				$tmp = M('Image_text')->field('title,cover_pic')->where(array('pigcms_id'=>$v))->find();
				$image_text[] = $tmp;
			}

			$this->assign('ids', implode(',',$ids));
			$this->assign('image_text', $image_text);
			$this->assign('user_list', $user_list);
			$this->assign('send_log', $send_log);
		}
		$this->assign('levelarr', $levelarr);
		$this->display();
	}

	public function multi()
	{

		if (IS_POST) {
			$ids = isset($_POST['imgids']) ? htmlspecialchars($_POST['imgids']) : '';
			$ids = explode(",", $ids);

			if (count($ids) == 0) {
				$this->error('没有选择图文消息');
			}
			if ($_POST['send_type']==1 && empty($_POST['uids'])) {
				$this->error('没有选择粉丝');
			}
			if ($_POST['send_now']==0 && empty($_POST['send_time'])) {
				$this->error('没有设置发送时间');
			}
			if (count($ids) > 10) {
				$this->error('最多十条图文');
			}
			$data = array();
			if($_POST['send_type']==0){
				$data['send_to'] = $_POST['level'];
			}else{
				$data['send_to'] = $_POST['uids'];
			}
			$data['send_type'] = $_POST['send_type'];
			$pigcms_id = isset($_POST['pigcms_id']) ? intval($_POST['pigcms_id']) : 0;

			if ($pigcms_id && ($data = D('Source_material')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => 0))->find())) {
				D('Source_material')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => 0))->data(array('it_ids' => serialize($ids), 'mer_id' => 0, 'dateline' => time(), 'type' => 1))->save();
			} else {
				$pigcms_id = D('Source_material')->data(array('it_ids' => serialize($ids), 'mer_id' => 0, 'dateline' => time(), 'type' => 1,'send_type'=>1))->add();
			}

			$data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
			$data['type'] = 10;
			$data['mer_id'] = 0;
			$data['c_id'] = $pigcms_id;
			$_POST['send_time'] && $data['dateline'] = strtotime($_POST['send_time']);
			$data['send_now'] = $_POST['send_now'];

			if (empty($_POST['id']) ) {
				$send_id = D("Send_log")->add($data);
			} else {
				$send_id= $_POST['id'];
				D("Send_log")->where(array('pigcms_id' => $_POST['id']))->save($data);
			}
			if($_POST['send_now']==1){
				$this->template_plan_msg($send_id);
			}else{
				$this->template_plan_msg($send_id, $data['dateline']);
			}
			$this->success("创建成功",U('Send/all_send'));
		}
	}

	public function  preview_send(){

		if(IS_POST){
			$openid = $_POST['openid'];
			$send_id = $_POST['send_id'];

			if(empty($openid)){
				$this->error('用户openid不能为空');
			}

			$where['pigcms_id'] = $send_id;
			$send_log =  M('Send_log')->where($where)->find();

			$source = D('Source_material')->where(array('pigcms_id' => $send_log['c_id']))->find();

			if (empty($source)) $this->error('没有数据');
			$ids = unserialize($source['it_ids']);
			$image_text = D('Image_text')->field(true)->where(array('pigcms_id' => array('in', $ids)))->select();
			$result = array();
			foreach ($image_text as $txt) {
				$result[$txt['pigcms_id']] = $txt;
			}
			$image_text = array();
			foreach ($ids as $id) {
				$image_text[] = isset($result[$id]) ? $result[$id] : array();
			}

			$access_token_array = D('Access_token_expires')->get_access_token();
			if ($access_token_array['errcode']) {
				return true;
			}

			$access_token = $access_token_array['access_token'];

			$send_to_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
			$send_obj = M('Send_user');
			import('ORG.Net.Http');



			$str = '{"touser":"'.$openid.'","msgtype":"news","news":{"articles": [';
			$pre = '';
			foreach ($image_text as $txt) {
				$url = C('config.site_url') . '/wap.php?g=Wap&c=Article&a=index&imid=' . $txt['pigcms_id'].'&preview=1&lid='.$send_id;
				$str .= $pre . '{"title":"'.$txt['title'].'", "description":"'.$txt['digest'].'", "url":"'. $url .'", "picurl":"'.C('config.site_url') . $txt['cover_pic'].'"}';
				$pre = ',';
			}
			$str .= ']}}';

			$rt = Http::curlPost($send_to_url, $str);

			if ($rt['errcode']) {
				$this->error('发送失败，原因：'.$rt['errmsg']);
			} else {
				$this->success('发送成功');
			}

		}else{
			$this->display();
		}

	}

	public function ajax_get_user(){
		if($_POST['search_val']){
			$where['openid'] = array('neq','');
			$where['_string'] = "nickname like '%".$_POST['search_val']."%' OR phone like '%".$_POST['search_val']."%'";
			$res = M('User')->field('nickname,phone,openid,avatar')->where($where)->limit(10)->select();
//			$this->ajaxReturn($res);
			$this->assign('list',$res);
		}
			$this->display('');

	}

	public function select_user(){
		if (!empty($_POST['search'])) {
			$condition_user['_string'] = "nickname like '%".$_POST['search']."%' OR phone like '%".$_POST['search']."%'";
		}
		$condition_user['openid'] = array('neq','');
//        $condition_user['openid'] = array('notlike','%no_use');
		//排序
		$order_string = '`uid` DESC';
		$database_user = D('User');
		$count_user = $database_user->where($condition_user)->count();
		import('@.ORG.system_page');
		$p = new Page($count_user, 5);
		$user_list = $database_user->field(true)->where($condition_user)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();
		$this->assign('send_id',$_POST['send_id']);
		$this->assign('list',$user_list);
		$this->assign('page',$p->show());
		$this->display('preview_send');
	}

	public function send_all_del()
	{
		D('Send_log')->where(array('pigcms_id' => intval($_POST['id'])))->delete();
		$this->success('删除成功');
	}

	public function template_plan_msg($send_id,$time=''){
		import('@.ORG.plan');
		$plan_class = new plan();
		if(!$time){
			$time = time();
		}
		$param = array(
				'file'=>'wechat_all_send',
				'plan_time'=>$time,
				'param'=>array(
						'id'=>$send_id,
				),
		);
		$plan_class->addTask($param);
	}

}