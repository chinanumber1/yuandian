<?php
/**
* 贴吧板块
*/
class TiebaAction extends BaseAction {
	public function _initialize(){
		parent::_initialize();
		$this->assign('portal_name', $this->config['portal_alias_name']);
	}
	// 首页
	public function index(){

		$tiebaPlateList = D('Portal_tieba_plate')->where(array('status'=>1))->select();
		$this->assign('tiebaPlateList',$tiebaPlateList);

		if($_GET['essence'] == 1){
			$where['is_essence'] = 1;
		}

		if($_GET['plate_id'] >0){
			$where['plate_id'] = $_GET['plate_id'];
		}

        if($_GET['order']){
        	$order['is_top'] = 'desc';
            $order[$_GET['order']] = $_GET['sort'];
        }else{
            $order = array('is_top'=>'desc','last_time'=>'desc');
        }

        if($_GET['search']){
            $where['title'] = array('like','%'.$_GET['search'].'%');
        }
        $where['target_id'] = 0;
        $where['status'] = 0;
		$count = D('Portal_tieba')->where($where)->count();
        import('@.ORG.page');
        $p = new Page($count,20);
		$tiebaList = D('Portal_tieba')->where($where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		
		foreach ($tiebaList as $key => $value) {

			$plate_name = D('Portal_tieba_plate')->where(array('plate_id'=>$value['plate_id']))->field('plate_name')->find();
			$tiebaList[$key]['plate_name'] = $plate_name['plate_name'];

			$soContent = $value['content'];
			$soImages = '/img src=&quot;(.*?)&quot; title=&quot;/';
			preg_match_all( $soImages, $soContent, $thePics );
			$tiebaList[$key]['pic'] = $thePics[1];
			$soImages = '/&lt;img (.*?)&gt;/';
			$content = preg_replace($soImages, '', $soContent);
			$content = trim(strip_tags(htmlspecialchars_decode($content)));
 			// echo str_replace(' ','',htmlspecialchars_decode($content));
 			// echo "<br/>";
			// $tiebaList[$key]['content'] = substr($content , 0 , 180).'...';
			// mb_substr($str, 0, 2, 'utf-8'); 
			// echo mb_substr(str_replace(' ','',$content), 0, 60, 'utf-8');
			// echo "<br/>";
			$tiebaList[$key]['content'] = mb_substr(str_replace(' ','',htmlspecialchars_decode($content)), 0, 63, 'utf-8'); 

		}

		$pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->assign('count',$count);
		$this->assign('tiebaList',$tiebaList);

		$this->display();
	}

	public function detail(){
		// 贴吧收藏
		$colInfo = D('Portal_tieba_collection')->where(array('tie_id'=>intval($_GET['tie_id']),'uid'=>$this->user_session['uid']))->field('col_id')->find();
		if($colInfo){
			$this->assign('col_status',1);
		}
		//贴吧板块
		$tiebaPlateList = D('Portal_tieba_plate')->where(array('status'=>1))->select();
		$this->assign('tiebaPlateList',$tiebaPlateList);
		//精华帖
		$essenceList = D('Portal_tieba')->where(array('is_essence'=>1,'status'=>0))->order('tie_id desc')->limit(12)->select();
		$this->assign('essenceList',$essenceList);

		//帖子详情
		$tieInfo = M()->table(array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'portal_tieba'=>'t'))->where('t.tie_id='.intval($_GET['tie_id']).' and u.uid=t.uid and t.status = 0')->field('u.avatar,t.*,u.nickname')->find();
		if(!$tieInfo){
			$this->error('你查看的帖子不存在。');
		}

		$plateUser = D('Portal_tieba_plate_user')->where(array('plate_id'=>$tieInfo['plate_id']))->select();

		$this->assign('plateUser',$plateUser);

		foreach ($plateUser as $k => $v) {
    		if($this->user_session['uid'] == $v['uid']){
    			$plate_admin_status = 1;
    		}else{
    			$plate_admin_status = 0;
    		}
    	}
    	// dump($tieInfo);
    	$this->assign('plate_admin_status',$plate_admin_status);
		$this->assign('tieInfo',$tieInfo);

		
		// 增加回复量
		D('Portal_tieba')->where(array('tie_id'=>intval($_GET['tie_id'])))->setInc('pageviews');


		//回复列表
		if($_GET['single']){
			$replyWhere = 't.target_id='.$tieInfo['tie_id'].' and u.uid=t.uid and t.status = 0 and t.uid='.$tieInfo['uid'];
		}else{
			$replyWhere = 't.target_id='.$tieInfo['tie_id'].' and u.uid=t.uid and t.status = 0';
		}
		$count = D('Portal_tieba')->where(array('target_id'=>$tieInfo['tie_id']))->count();
        import('@.ORG.page');
        $p = new Page($count,20);
		$tieList = M()->table(array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'portal_tieba'=>'t'))->where($replyWhere)->field('u.avatar,u.nickname,t.*,u.nickname')->order('t.sort asc')->limit($p->firstRow.','.$p->listRows)->select();
		$pagebar = $p->show();
        $this->assign('pagebar',$pagebar);

        
        foreach ($tieList as $key => $value) {
        	foreach ($plateUser as $p_u_k => $p_u_v) {
        		if($p_u_v['uid'] == $value['uid']){
        			$tieList[$key]['plate_admin_status'] = 1;
        		}
        	}
        }
        

        $tieCount = D('Portal_tieba')->where(array('uid'=>$this->user_session['uid'],'target_id'=>0))->field('tie_id')->count();
        $tieEssenceCount = D('Portal_tieba')->where(array('uid'=>$this->user_session['uid'],'target_id'=>0,'is_essence'=>1))->field('tie_id')->count();
        $this->assign('tieCount',intval($tieCount));
        $this->assign('tieEssenceCount',intval($tieEssenceCount));

		$this->assign('tieList',$tieList);
		$this->display();
	}

	public function add(){
		if(IS_POST){
			$verify = $_POST['verify'];
			if(md5($verify) != $_SESSION['verify']){
				$this->error('验证码不正确');
			}
			if(empty($_POST['title'])){
				$this->error('标题不可以为空');
			}
			$data['title'] = $_POST['title'];
			$data['content'] = $_POST['content'];
			$data['add_time'] = time();
			$data['last_time'] = time();
			$data['uid'] = $this->user_session['uid'];
			$data['nickname'] = $this->user_session['nickname'];
			$data['last_nickname'] = $this->user_session['nickname'];
			$data['type'] = $_POST['type']?$_POST['type']:0;
			$data['videoUrl'] = $_POST['videoUrl'];
			if(intval($_POST['plate_id'])>0){
				$data['plate_id'] = $_POST['plate_id'];
				$tieba_plate = D('Portal_tieba_plate')->where(array('plate_id'=>intval($_POST['plate_id'])))->field('plate_name')->find();
				$data['plate_name'] = $tieba_plate['plate_name'];
			}
	
			$res = D('Portal_tieba')->data($data)->add();
			if($res){
				$this->success('添加成功');
			}else{
				$this->error('添加失败，请重试！');
			}
		}else{

			$plateList = D('Portal_tieba_plate')->where(array('status'=>1))->order('`sort` DESC,`plate_id` ASC')->select();
			$this->assign('plateList',$plateList);
			$this->display();
		}
	}


	public function reply(){
		$tieInfo = D('Portal_tieba')->where(array('tie_id'=>intval($_POST['target_id'])))->find();
		if(!is_array($tieInfo)){
			exit(json_encode(array('error'=>3,'msg'=>'数据异常！')));
		}

		$data['target_id'] = $_POST['target_id'];
		$data['reply_tie_id'] = $_POST['reply_tie_id'];
		$data['sort'] = $tieInfo['sort']+1;
		$data['content'] = $_POST['content'];
		$data['uid'] = $this->user_session['uid'];
		$data['nickname'] = $this->user_session['nickname'];
		$data['add_time'] = time();

		$res = D('Portal_tieba')->data($data)->add();
		if($res){
			$tiedata['last_nickname'] = $this->user_session['nickname'];
			$tiedata['sort'] = $tieInfo['sort']+1;
			$tiedata['last_time'] = time();
			$tiedata['reply_sum'] = $tieInfo['reply_sum']+1;
			D('Portal_tieba')->where(array('tie_id'=>$tieInfo['tie_id']))->data($tiedata)->save();
			exit(json_encode(array('error'=>1,'msg'=>'回复成功')));

		}else{
			exit(json_encode(array('error'=>2,'msg'=>'回复失败！请重试')));
		}
		
		
	}

	public function tieba_collection(){
		$uid = $this->user_session['uid'];
		$info = D('Portal_tieba_collection')->where(array('tie_id'=>intval($_POST['tie_id']),'uid'=>$uid))->find();
		if(is_array($info)){
			D('Portal_tieba_collection')->where(array('tie_id'=>intval($_POST['tie_id']),'uid'=>$uid))->delete();
			exit(json_encode(array('error'=>1,'msg'=>'取消成功')));
		}else{
			$res = D('Portal_tieba_collection')->data(array('tie_id'=>intval($_POST['tie_id']),'uid'=>$uid))->add();
			if($res){
				exit(json_encode(array('error'=>1,'msg'=>'收藏成功')));
			}else{
				exit(json_encode(array('error'=>2,'msg'=>'收藏失败，请重试！')));
			}

		}
	}

	public function tie_del(){
		if(empty($this->user_session['uid'])){
			exit(json_encode(array('error'=>3,'msg'=>'请先登录')));
		}
		$res = D('Portal_tieba')->where(array('tie_id'=>intval($_POST['tie_id'])))->data(array('status'=>3,'del_uid'=>$this->user_session['uid']))->save();
		if($res){
			exit(json_encode(array('error'=>1,'msg'=>'删除成功','type'=>$_POST['type'])));
		}else{
			// echo  D('Portal_tieba')->getlastsql();
			exit(json_encode(array('error'=>2,'msg'=>'删除失败，请重试！')));
		}
	}

	public function set_essence_top(){
		$res = D('Portal_tieba')->where(array('tie_id'=>intval($_POST['tie_id'])))->data(array($_POST['type']=>$_POST['status']))->save();
		if($res){
			exit(json_encode(array('error'=>1,'msg'=>'设置成功','type'=>$_POST['type'])));
		}else{
			// echo  D('Portal_tieba')->getlastsql();
			exit(json_encode(array('error'=>2,'msg'=>'设置失败，请重试！')));
		}
	}

	

	// 编辑器图片上传
	public function ajax_upload_pic(){

		if($_FILES['imgFile']['error'] == 4){
			exit(json_encode(array('error'=>1,'message'=>'没有选择图片')));
		}

		$upload_file = D('Image')->handle($this->system_session['uid'], 'portal', 0, array('size' => 5), false);
		if ($upload_file['error']){
			exit(json_encode(array('error'=>1,'message'=>$upload_file['message'])));
		}

		exit(json_encode(array('error' => 0, 'url' => $upload_file['url']['imgFile'], 'title' => '图片')));
	}


	public function verify(){
		import('ORG.Util.Image');
		Image::buildImageVerify(4,1,'jpeg',53,26,'verify');
	}

	// 上传默认视频
	function upload_video(){

		// if($_FILES['file_video']['type'] != 'video/mp4'){
		// 	exit(json_encode(array('code'=>1,'msg'=>'只允许上传mp4格式视频')));
		// }
		// if(empty($_FILES['file_video']) || $_FILES['file_video']['error'] == 4){
		// 	exit(json_encode(array('code'=>1,'msg'=>'没有选择视频')));
		// }
		
		$video_size =100;
		// if($_FILES['file_video']['size']>$video_size*1024*1024){
		// 	exit(json_encode(array('code'=>1,'msg'=>'视频太大')));
		// }

		dump($_FILES);
		die;
		$image = D('Image')->handle($this->user_session['uid'], 'default_video', 0, array('size' => $video_size), false);
		if ($image['error']){
			exit(json_encode(array('code'=>1,'msg'=>'上传失败')));	
		}

		// D('Anchor')->where(array('uid'=>$this->user_session['uid']))->data(array('default_video'=>$image['url']['file_video']))->save();
		exit(json_encode(array('code'=>0,'msg'=>'上传成功')));
	}


	// 上传图片
	public function ajax_upload_file(){
		if($_FILES['videoFile']['error'] == 4){
			exit(json_encode(array('error'=>1,'msg'=>'没有选择视频')));
		}

		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize = 20 * 1024 * 1024 ;
		$upload->allowExts = array('mp4', 'flv', 'png', 'gif', 'mp3', 'ico');

		$upload->savePath =  './Public/Uploads/';// 设置附件上传目录

		$img_mer_id = sprintf("%09d", $this->user_session['uid']);
		
		$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

		$upload_dir = "./upload/video/{$rand_num}/";
		
		if(!is_dir($upload_dir)){
			mkdir($upload_dir, 0777, true);
		}

		$upload->savePath = $upload_dir;// 设置附件上传目录


		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
			exit(json_encode(array('error'=>1,'msg'=>'上传失败，请重试！')));
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			exit(json_encode(array('error'=>2,'msg'=>'上传成功','url'=>$info[0]['savepath'].$info[0]['savename'])));
		}
		
	}

}