<?php

class PortalAction extends BaseAction{
	// 首页
	public function index(){
		$near_shop_list = D("Merchant_store")->get_hot_list(8);
		$this->assign('near_shop_list',$near_shop_list);

		//轮播广告
		$portal_wap_index = D('Adver')->get_adver_by_key('portal_wap_index_ad',5);
		$this->assign('portal_wap_index',$portal_wap_index);

		//横幅广告
		$portal_wap_index_banner = D('Adver')->get_adver_by_key('portal_wap_index_banner',3);
		$this->assign('portal_wap_index_banner',$portal_wap_index_banner);

		//平台快报
		$news_list = M('')->field('`n`.`id`,`n`.`title`,`c`.`name`')->table(array(C('DB_PREFIX').'system_news'=>'n',C('DB_PREFIX').'system_news_category'=>'c'))->where("`n`.`status`='1' AND `c`.`id`=`n`.`category_id`")->order('`n`.`sort` DESC,`n`.`id` DESC')->limit(8)->select();
		$this->assign('news_list',$news_list);
		// dump($news_list);		

		//精华帖
		$essenceList = D('Portal_tieba')->where(array('is_essence'=>1,'status'=>0,'target_id'=>0))->order('add_time desc')->limit(5)->select();
		foreach ($essenceList as $key => $value) {
			$soContent = $value['content'];
			$soImages = '/img src=&quot;(.*?)&quot; title=&quot;/';
			preg_match_all( $soImages, $soContent, $thePics );
			$essenceList[$key]['pic'] = $thePics[1][0];
		}
		$this->assign('essenceList',$essenceList);

		//精华帖
		$newsTieList = D('Portal_tieba')->where(array('target_id'=>0,'status'=>0))->order('add_time desc')->limit(5)->select();
		foreach ($newsTieList as $key => $value) {
			$soContent = $value['content'];
			$soImages = '/img src=&quot;(.*?)&quot; title=&quot;/';
			preg_match_all( $soImages, $soContent, $thePics );
			$newsTieList[$key]['pic'] = $thePics[1][0];
		}
		$this->assign('newsTieList',$newsTieList);

		// 今日热点
		$hot_news = M()->table(array(C('DB_PREFIX').'portal_article'=>'a',C('DB_PREFIX').'portal_article_cat'=>'c'))->where('a.status=1 and a.cid=c.cid')->field('a.*,c.cat_name')->order('a.dateline desc ,a.PV desc')->limit('0,5')->select();
		$this->assign('hot_news',$hot_news);



		$activityList = D('Portal_activity')->where(array('status'=>1))->order('a_id desc')->limit(3)->select();
		foreach ($activityList as $k => $v) {
			if($v['enroll_time']<time()){
				$v['state'] = 5;//结束了
			}else if($v['number']<=$v['already_sign_up'] && $v['number']>0){
				$v['state'] = 3;//即将组团
			}else{
				$v['state'] = 2;//招募中
			}
			$activityList[$k] = $v;
		}
		$this->assign('activityList',$activityList);

		$this->display();
	}
	// 贴吧首页
	public function tieba(){

		//轮播广告
		$portal_wap_index = D('Adver')->get_adver_by_key('portal_wap_index',3);
		$this->assign('portal_wap_index',$portal_wap_index);
		
		$tiebaPlateList = D('Portal_tieba_plate')->where(array('status'=>1))->order('sort desc')->select();
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
		$tiebaList = D('Portal_tieba')->where($where)->order($order)->limit(5)->select();
		foreach ($tiebaList as $key => $value) {
			$soContent = $value['content'];
			$soImages = '/img src=&quot;(.*?)&quot; title=&quot;/';
			preg_match_all( $soImages, $soContent, $thePics );
			$tiebaList[$key]['pic'] = $thePics[1];
			$soImages = '/&lt;img (.*?)&gt;/';
			$content = preg_replace($soImages, '', $soContent);
			$content = trim(strip_tags(htmlspecialchars_decode($content)));
			$tiebaList[$key]['content'] = substr($content , 0 , 180).'...';
		}
		$this->assign('tiebaList',$tiebaList);
		$this->display();
	}


	// 贴吧搜索
	public function tieba_list(){
		$keyword = trim($_GET['keyword']);
		$tiebaList = array();
		if($keyword){
			$keyword = htmlspecialchars($keyword);
			$where['title'] = array('like','%'.$keyword.'%');

	        $where['status'] = 0;
			$count = D('Portal_tieba')->where($where)->count();
	        import('@.ORG.portal_wap_page');
	        $p = new Page($count,10);
			$tiebaList = D('Portal_tieba')->where($where)->order(array('is_top'=>'desc','last_time'=>'desc'))->limit($p->firstRow.','.$p->listRows)->select();
			$pagebar = $p->show();
			$this->assign('pagebar',$pagebar);

			foreach ($tiebaList as $key => $value) {
				$soContent = $value['content'];
				$soImages = '/img src=&quot;(.*?)&quot; title=&quot;/';
				preg_match_all( $soImages, $soContent, $thePics );
				$tiebaList[$key]['pic'] = $thePics[1];
				$soImages = '/&lt;img (.*?)&gt;/';
				$content = preg_replace($soImages, '', $soContent);
				$content = trim(strip_tags(htmlspecialchars_decode($content)));
				$tiebaList[$key]['content'] = substr($content , 0 , 180).'...';
			}
		}
		$this->assign('tiebaList',$tiebaList);
		$this->display();
	}
 
	// 贴吧详情
	public function tieba_detail(){
		// 贴吧收藏
		$colInfo = D('Portal_tieba_collection')->where(array('tie_id'=>intval($_GET['tie_id']),'uid'=>$this->user_session['uid']))->field('col_id')->find();
		if($colInfo){
			$this->assign('col_status',1);
		}

		//帖子详情
		$tieInfo = M()->table(array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'portal_tieba'=>'t'))->where('t.tie_id='.intval($_GET['tie_id']).' and u.uid=t.uid and t.status = 0')->field('u.avatar,t.*,u.nickname')->find();
		if(!$tieInfo){
			$this->error_tips('你查看的帖子不存在。');
		}

		$plateUser = D('Portal_tieba_plate_user')->where(array('plate_id'=>$tieInfo['plate_id']))->select();

		$this->assign('plateUser',$plateUser);

		foreach ($plateUser as $k => $v) {
    		if($tieInfo['uid'] == $v['uid']){
    			$tieInfo['plate_admin_status'] = 1;
    		}

    		if($this->user_session['uid'] == $v['uid']){
    			$plateUserUid = 1;
    		}
    	}

        if($plateUserUid != 1 && $tieInfo['uid'] == $this->user_session['uid']){
            $plateUserUid = 1;
        }

		$this->assign('tieInfo',$tieInfo);
		$this->assign('plateUserUid',$plateUserUid);
		
		// 增加回复量
		D('Portal_tieba')->where(array('tie_id'=>intval($_GET['tie_id'])))->setInc('pageviews');

		//回复列表
		if($_GET['single']){
			$replyWhere = 't.target_id='.$tieInfo['tie_id'].' and u.uid=t.uid and t.status = 0 and t.uid='.$tieInfo['uid'];
		}else{
			$replyWhere = 't.target_id='.$tieInfo['tie_id'].' and u.uid=t.uid and t.status = 0';
		}
		$tieList = M()->table(array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'portal_tieba'=>'t'))->where($replyWhere)->field('u.avatar,u.nickname,t.*,u.nickname')->order('t.sort asc')->select();
        $this->assign('pagebar',$pagebar);
        
        foreach ($tieList as $key => $value) {
        	foreach ($plateUser as $p_u_k => $p_u_v) {
        		if($p_u_v['uid'] == $value['uid']){
        			$tieList[$key]['plate_admin_status'] = 1;
        		}
        	}
        }
        
		$this->assign('tieList',$tieList);
		$this->display();
	}

	public function tie_del(){
		if(D('Portal_tieba')->where(array('tie_id'=>intval($_POST['tie_id'])))->delete()){
			exit(json_encode(array('error'=>1,'msg'=>'删除成功')));
		}else{
			exit(json_encode(array('error'=>2,'msg'=>$_POST['tie_id'])));
		}
	}

	public function tieba_add(){
		exec('/home/ffmpeg/bin/ffmpeg -version',$output,$return_val); 
		$this->assign('return_val',$return_val);
		$tiebaPlateList = D('Portal_tieba_plate')->where(array('status'=>1))->order('sort desc')->select();
		$this->assign('tiebaPlateList',$tiebaPlateList);
		if(IS_POST){
			$data['title'] = $_POST['title'];
			foreach ($_POST['imgList'] as $key => $value) {
				$_POST['content'].= '&lt;img src=&quot;'.$value.'&quot; title=&quot;3_3.png&quot; /&gt;';
			}
			$data['content'] = $_POST['content'];
			$data['type'] = $_POST['type']?$_POST['type']:0;
			$data['videoUrl'] = $_POST['videoUrl'];
			$data['add_time'] = time();
			$data['last_time'] = time();
			$data['uid'] = $this->user_session['uid'];
			$data['nickname'] = $this->user_session['nickname'];
			$data['last_nickname'] = $this->user_session['nickname'];
			if(intval($_POST['plate_id'])>0){
				$data['plate_id'] = $_POST['plate_id'];
				$tieba_plate = D('Portal_tieba_plate')->where(array('plate_id'=>intval($_POST['plate_id'])))->field('plate_name')->find();
				$data['plate_name'] = $tieba_plate['plate_name'];
			}

			$res = D('Portal_tieba')->data($data)->add();
			if($res){
				$this->success_tips('发布成功！', U('Portal/tieba'));
			}else{
				$this->error_tips('发布失败，请重试！');
			}
		}else{
			$this->display();
		}
	}

	public function tieba_reply(){
		$tieInfo = D('Portal_tieba')->where(array('tie_id'=>intval($_POST['target_id'])))->find();
		if(!is_array($tieInfo)){
			exit(json_encode(array('error'=>3,'msg'=>'数据异常！')));
		}

		$data['target_id'] = $_POST['target_id'];
		$data['reply_tie_id'] = $_POST['reply_tie_id'];
		$data['sort'] = $tieInfo['sort']+1;
		foreach ($_POST['imgList'] as $key => $value) {
			$_POST['content'].= '&lt;img src=&quot;'.$value.'&quot; title=&quot;3_3.png&quot; /&gt;';
		}
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
			$this->success_tips('发布成功！', U('Portal/tieba_detail', array('tie_id' => $_POST['target_id'])));
		}else{
			$this->error_tips('发布失败，请重试！');
		}
		
		
	}

	public function ajax_upload_file(){
		if($_FILES['imgFile']['error'] == 4){
			exit(json_encode(array('error'=>1,'msg'=>'没有选择图片')));
		}
		$upload_file = D('Image')->handle($this->user_session['uid'], 'portal', 0, array('size' => 4), false);
		if ($upload_file['error']){
			exit(json_encode(array('error'=>1,'msg'=>'上传失败，请重试！')));
		}else{
			exit(json_encode(array('error'=>2,'msg'=>'上传成功','url'=>$upload_file['url']['imgFile'])));
		}
		
	}

	// 上传图片
	public function ajax_upload_video(){
		if($_FILES['videoFile']['error'] == 4){
			exit(json_encode(array('error'=>1,'msg'=>'没有选择视频')));
		}
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize = 50 * 1024 * 1024 ;
		$upload->allowExts = array('mp4', 'flv', 'png', 'gif', 'mp3', 'ico','mov','rmvb','avi','wmv','4gp','mpeg1');

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
			
			$from = $info[0]['savepath'].$info[0]['savename'];  
			$to = $info[0]['savepath'].$info[0]['savename'].'.png';
			$str = "ffmpeg -i ".$from." -y -f mjpeg -ss 0.01 -t 1 -s 740x500 ".$to;  
			exec($str); 
			// ffmpeg常用参数的介绍
			// -i 指定要转换视频的源文件
			// -s 视频转换后视频的分辨率
			// -vcodec 视频转换时使用的编解码器
			// -r 视频转换换的桢率(默认25桢每秒)
			// -b 视频转换换的bit率
			// -ab 音频转换后的bit率(默认64k)
			// -acodec 制度音频使用的编码器
			// -ac 制定转换后音频的声道
			// -ar 音频转换后的采样率
			// avi to mp4
			// ffmpeg -i source.avi -f psp -r 29.97 -b 768k -ar 24000 -ab 64k -s 320×240 destination.mp4

			$name_all=$info[0]['savename'];
			$name = substr($name_all,0,strrpos($name_all,'.'));
			$test = "ffmpeg -i ".$info[0]['savepath'].$info[0]['savename']." -c:v libx264 -strict -2 ".$info[0]['savepath'].$name."_copy.mp4 &";
			exec($test);
			$url = $info[0]['savepath'].$name.'_copy.mp4';
			exit(json_encode(array('error'=>2,'msg'=>'上传成功','url'=>$url,'image'=>$to)));
		}
		
	}


	public function ajax_tieba_list(){
		$listRows = 5;
		$firstRow = $_POST['page'] * $listRows;

		if($_POST['essence'] == 1){
			$where['is_essence'] = 1;
		}

		if($_POST['plate_id'] >0){
			$where['plate_id'] = $_POST['plate_id'];
		}

        if($_POST['order']){
        	$order['is_top'] = 'desc';
            $order[$_POST['order']] = $_POST['sort'];
        }else{
            $order = array('is_top'=>'desc','last_time'=>'desc');
        }

        $keyword = trim($_GET['keyword']);
		if($keyword){
			$keyword = htmlspecialchars($keyword);
			$where['title'] = array('like','%'.$keyword.'%');
		}else{
			$where['target_id'] = 0;
		}
		
        $where['status'] = 0;
		$tiebaList = D('Portal_tieba')->where($where)->order($order)->limit($firstRow,$listRows)->select();
		foreach ($tiebaList as $key => $value) {
			$id .=$value['tie_id'].'--';
			$soContent = $value['content'];
			$soImages = '/img src=&quot;(.*?)&quot; title=&quot;/';
			preg_match_all( $soImages, $soContent, $thePics );

			$pic = '';
			foreach ($thePics[1] as $p_k => $p_v) {
				if($p_k < 3){
					$pic .='<a href="'.U('Portal/tieba_detail',array('tie_id'=>$value['tie_id'])).'"  class="itemAlbum">
						<img src="'.$p_v.'" style="width: 106px; height: 79px;">
					</a>'; 
				}
				
			}

			$top_essence = '';
			if($value['is_essence'] == 1){
				$top_essence .= '<span class="j">精</span>';
			}
			if($value['is_top'] == 1){
				$top_essence .= '<span class="d">顶</span>';
			}
			$html .= '<div class="item iszhiding0" id="item39">
						<h2>
							'.$top_essence.'
							<a href="'.U('Portal/tieba_detail',array('tie_id'=>$value['tie_id'])).'">'.$value['title'].'</a>
						</h2>
						<div class="con">
							<div class="n_img" id="n_img_39" data-ischeck="1">
								'.$pic.'
							</div>
						</div>
						<a href="'.U('Portal/tieba_detail',array('tie_id'=>$value['tie_id'])).'">
							<dl>
								<dt> <span class="chrname">'.$value['last_nickname'].'</span> <span class="revertnum">'.$value['pageviews'].'</span> 阅读 </dt>
								<dd> <span class="stime">'. date("m月d日 H:i",$value['last_time']).'</span> </dd>
							</dl>
						</a>
					</div>';
		}

		$sql = D('Portal_tieba')->getlastsql();


		if(is_array($tiebaList)){
			exit(json_encode(array('error'=>1,'sql'=>$sql,'id'=>$id,'html'=>$html)));
		}else{
			exit(json_encode(array('error'=>2,'sql'=>$sql)));
		}

		
		// dump($tiebaList);
	}
	// 公司黄页
	public function yellow(){
		// 黄页板块分类
		$all_category_list = D('Group_category')->get_category();
		$yellowPvList = D('Portal_yellow')->where(array('status'=>1))->order('top_time desc,PV desc,status asc')->limit(10)->select();
		$yellowAddList = D('Portal_yellow')->where(array('status'=>1))->order('top_time desc,dateline desc,status asc')->limit(10)->select();
	
		$this->assign('yellowPvList',$yellowPvList);
		$this->assign('yellowAddList',$yellowAddList);
		$this->assign('all_category_list',$all_category_list);
		$this->display();
	}
	// 黄页列表
	public function yellow_list(){
		// 黄页板块分类
		$all_category_list = D('Group_category')->get_category();

		$where['status'] = 1;
		$pid = (int)$_GET['pid'];
		$cid = (int)$_GET['cid'];
		if($pid){
			$where['pid'] = $pid;
			$catInfo = D('Group_category')->where(array('cat_id'=>$pid))->find();
		}
		if($cid){
			$where['cid'] = $cid;
			$catInfo = D('Group_category')->where(array('cat_id'=>$cid))->find();
		}

		if($catInfo){
			$this->assign('cat_name',$catInfo['cat_name']);
		}else{
			$this->assign('cat_name','全部分类');
		}
		

		$title = trim($_GET['keyword']);
		if($title){
			$where['title'] = array('like','%'.$title.'%');
		}

		$where['city'] = C('config.now_city');
		
		$area_id = (int)$_GET['area_id'];
		if($area_id){
			$where['area'] = $area_id;
			$this->assign('area_id',$area_id);
		}

		$count = D('Portal_yellow')->where($where)->count();
        import('@.ORG.portal_wap_page');
        $p = new Page($count,10);
        $pagebar = $p->show();

		$yellow_list = D('Portal_yellow')->where($where)->order('top_time desc,status asc,dateline desc')->limit($p->firstRow.','.$p->listRows)->select();
		// 分类
		foreach($yellow_list as $key => $yellow){

			foreach($all_category_list as $category){
				if($yellow['pid'] == $category['cat_id']){
					// 一级分类
					$yellow_list[$key]['parent_cat_name'] = $category['cat_name'];
					// 二级分类
					foreach($category['category_list'] as $child){
						if($yellow['cid'] == $child['cat_id']){
							$yellow_list[$key]['child_cat_name'] = $child['cat_name'];
							break;
						}
					}
					break;
				}
			}
		}

		if($pid==0){
			$pid = (int)$_GET['parent_id'];
		}

		$area_list = D('Area')->get_area_list();
		$this->assign('yellow_list',$yellow_list);
		$this->assign('all_category_list',$all_category_list);
		$this->assign('area_list',$area_list);
		$this->assign('pagebar',$pagebar);
		$this->assign('pid',$pid);
		$this->assign('cid',$cid);
		$this->assign('title',$title);
		$this->display();
	}
	// 黄页详情
	public function yellow_detail(){
		$id = (int)$_GET['id'];
		$detail = D('Portal_yellow')->where(array('id'=>$id))->find();
		$custome_info = D('Portal_yellow_detail')->where(array('yellow_id'=>$id))->find();

		// 统计PV
		D('Portal_yellow')->where(array('id'=>$id))->setInc('PV');

		// 分类信息
		$p_info = D('Group_category')->get_category_by_id($detail['pid']);
		$c_info = D('Group_category')->get_category_by_id($detail['cid']);
		$detail['parent_cat_name'] = $p_info['cat_name'];
		$detail['child_cat_name'] = $c_info['cat_name'];

		// 评论信息
		$recomment_list = D('Portal_yellow_recomment')->where(array('yellow_id'=>$id))->order('id asc')->select();

		$this->assign('recomment_list',$recomment_list);
		$this->assign('detail',$detail);
		// dump($detail);
		$this->assign('custome_info',$custome_info);
		$this->display();
	}

	// 评论
	public function release_comment(){
		if(!$_SESSION['user']['uid']){
			exit(json_encode(array('code'=>1,'msg'=>'您还未登录，不能评论')));
		}

		$yellow_id = (int)$_POST['yellow_detail_id'];
		$msg = trim($_POST['msg']);
		if($yellow_id <= 0){
			exit(json_encode(array('code'=>1,'msg'=>'公司ID错误')));
		}
		if($msg == ''){
			exit(json_encode(array('code'=>1,'msg'=>'没有评论内容')));
		}
		$data['yellow_id'] = $yellow_id;
		$data['msg'] = $msg;
		$data['uid'] = $_SESSION['user']['uid'];
		$data['nickname'] = $_SESSION['user']['nickname'];
		$data['avatar'] = $_SESSION['user']['avatar'];
		$data['dateline'] = time();
		$res = D('Portal_yellow_recomment')->data($data)->add();
		if(!$res){
			exit(json_encode(array('code'=>1,'msg'=>'评论失败')));
		}
		exit(json_encode(array('code'=>0,'msg'=>'评论成功')));
	}
	// 商户首页
	public function company(){
		$this->display();
	}
	// 活动首页
	public function activity(){
		$activityCatgoryList = D('Portal_activity_cat')->where(array('cat_status'=>1,'fcid'=>0))->order('cat_sort desc,cid desc')->select();
		$this->assign('activityCatgoryList',$activityCatgoryList);
		// dump($activityCatgoryList);
		if($_GET['cid']){
			$activity_where['cid'] = $_GET['cid'];
		}
		$activity_where['status'] = 1;
		$count = D('Portal_activity')->where($activity_where)->count();
        import('@.ORG.portal_wap_page');
        $p = new Page($count,10);
		$activityList = D('Portal_activity')->where($activity_where)->order('a_id desc')->limit($p->firstRow.','.$p->listRows)->select();
		foreach ($activityList as $k => $v) {
			if($v['enroll_time']<time()){
				$v['state'] = 5;//结束了
			}else if($v['number']<=$v['already_sign_up'] && $v['number']>0){
				$v['state'] = 3;//即将组团
			}else{
				$v['state'] = 2;//招募中
			}
			$activityList[$k] = $v;
		}
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
		$this->assign('activityList',$activityList);
		$this->display();
	}
	// 活动详情
	public function activity_detail(){
		$a_id = intval($_GET['a_id']);
		$activityInfo = D('Portal_activity')->where(array('a_id'=>$a_id))->find();
		if(!is_array($activityInfo)){
			$this->error_tips('查询的目标不存在');
		}

		if($activityInfo['enroll_time']<time()){
			$activityInfo['state'] = 5;//活动结束
		}else if($activityInfo['number']<=$activityInfo['already_sign_up'] && $activityInfo['number']>0){
			$activityInfo['state'] = 3;//即将组团
		}else{
			$activityInfo['state'] = 2;//正在召集
		}

		
		$activityInfo['over_time'] = intval(($activityInfo['enroll_time'] - time())/60/60/24);
		$this->assign('activityInfo',$activityInfo);

		$recommentCount = D('Portal_recomment')->where(array('type'=>1,'target_id'=>$a_id))->count();
		$recommentList = D('Portal_recomment')->where(array('type'=>1,'target_id'=>$a_id))->select();
		$this->assign('recommentCount',$recommentCount);
		$this->assign('recommentList',$recommentList);
		

		$activitySignList = D('Portal_activity_sign')->where(array('a_id'=>$a_id))->order('create_time desc')->select();
		$this->assign('activitySignList',$activitySignList);
		$this->display();
	}

	public function activity_baoming(){
        $allowed = D('Portal_activity')->where(array('a_id'=>$_POST['a_id']))->find();
        if($allowed['already_sign_up']>=$allowed['number']) {
            exit(json_encode(array('error'=>2,'msg'=>'名额已满，报名失败！')));
        }
		if(is_array($_SESSION['user'])){
			$_POST['uid'] = $_SESSION['user']['uid'];
			$_POST['avatar'] = $_SESSION['user']['avatar'];
			$_POST['nickname'] = $_SESSION['user']['nickname'];
            $_POST['create_time'] = time();
		}else{
			$_POST['uid'] = 0;
			$_POST['avatar'] =  '';
			$_POST['nickname'] = '游客';
            $_POST['create_time'] = time();
		}
		$res = D('Portal_activity_sign')->data($_POST)->add();
		if($res){
			D('Portal_activity')->where(array('a_id'=>intval($_POST['a_id'])))->setInc('already_sign_up');
			exit(json_encode(array('error'=>1,'msg'=>'报名成功！')));
		}else{
			exit(json_encode(array('error'=>2,'msg'=>'报名失败，请重试！')));
		}
	}

	public function recomment(){
		if(IS_POST){
			$data['uid']=$this->user_session['uid'];
			$data['nickname']=$this->user_session['nickname'];
			$data['target_id']=$_POST['target_id'];
			$data['avatar']=$this->user_session['avatar'];
			$data['type']=1;
			$data['msg']=$_POST['msg'];
			$data['dateline']=time();
			$res = D('Portal_recomment')->data($data)->add();
			if($res){
				exit(json_encode(array('error'=>1,'msg'=>'评论成功！','data'=>$data)));
			}else{
				exit(json_encode(array('error'=>2,'msg'=>'评论失败，请重试！')));
			}
		}
	}

	// 文章首页
	public function article(){
		// 资讯分类
		$cate_list = D('Portal_article_cat')->where(array('cat_status'=>1,'fcid'=>0))->select();
		$this->assign('cate_list',$cate_list);

		// 精彩图文
		$hot_img_news = D('Portal_article')->where(array('status'=>1,'thumb'=>array('neq','')))->order('PV desc')->limit(0,5)->select();
		$this->assign('hot_img_news',$hot_img_news);

		if($_GET['cid'] >0){
			$where['fcid'] = $_GET['cid'];
		}
		$where['status'] = 1;
		$article_list = D('Portal_article')->where($where)->order('status asc,dateline desc')->limit(3)->select();
		$this->assign('article_list',$article_list);
		$this->display();
	}

	public function ajax_article_list(){
		$listRows = 3;
		$firstRow = $_POST['page'] * $listRows;

		if($_POST['cid'] >0){
			$where['fcid'] = $_POST['cid'];
		}

		$keyword = trim($_GET['keyword']);
		if($keyword){
			$keyword = htmlspecialchars($keyword);
			$where['title'] = array('like','%'.$keyword.'%');
		}

        $order = array('dateline'=>'desc');
        $where['status'] = 1;
		$article_list = D('Portal_article')->where($where)->order($order)->limit($firstRow,$listRows)->select();

		foreach ($article_list as $key => $value) {
			$html .= '<li class="haspic1">
							<a href="'.U('Portal/article_detail',array('aid'=>$value['aid'])).'" class="link">
								<p class="img">
							   		<img src="'.$value['thumb'].'">
								</p>
								<p class="tit">'.$value['title'].'</p>
								<p class="txt clearfix">
									<span class="left">'.date('m-d H:i',$value['dateline']).'</span>
									<span class="right">人气：'.$value['PV'].'</span>
								</p>
							</a>
						</li>';
		}

		if(is_array($article_list)){
			exit(json_encode(array('error'=>1,'id'=>$id,'html'=>$html)));
		}else{
			exit(json_encode(array('error'=>2,'msg'=>'没有更多')));
		}

	}

	public function search_list(){

		if($_GET['v']){
			$where['title'] = array('like','%'.$_GET['v'].'%');
		}
		$where['status'] = 1;
		$count = D('Portal_article')->where($where)->count();
        import('@.ORG.portal_wap_page');
        $p = new Page($count,2);
		$article_list = D('Portal_article')->where($where)->order('status asc,dateline desc')->limit($p->firstRow.','.$p->listRows)->select();
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('article_list',$article_list);
		$this->display();
	}

	// 搜所讯列表
	public function article_list(){
		$keyword = trim($_GET['keyword']);
		$article_list = array();
		if($keyword){
			$keyword = htmlspecialchars($keyword);
			$where['title'] = array('like','%'.$keyword.'%');
		
        
			$where['status'] = 1;
			$count = D('Portal_article')->where($where)->count();
	        import('@.ORG.portal_wap_page');
	        $p = new Page($count,10);
			$article_list = D('Portal_article')->where($where)->order('dateline desc')->limit($p->firstRow.','.$p->listRows)->select();
			$pagebar = $p->show();
		}
		$this->assign('article_list',$article_list);
		$this->display();
	}

	// 文章详情
	public function article_detail(){
		$aid = (int)$_GET['aid'];
		$article = D('Portal_article')->where(array('aid'=>$aid))->find();
		// 如果需要打赏，查询一下当前登录用户是否打赏过
        // 获取文章信息
        if (!empty($article) && $article['is_reward'] == 2) {
            $uid = (int)$_SESSION['user']['uid'];
            if ($uid) {
                $article_pay = M('Reward_order')->where(array('uid'=>$uid,'aid'=>$aid, 'status' => 1, 'type' => 1))->find();
                if ($article_pay) {
                    $article['is_reward'] = 1;
                }
            }
        }
        if ($article && $article['reward_money']) {
            $article['reward_money'] = round($article['reward_money'], 2);
        }
		// 精彩图文
		$hot_img_news = D('Portal_article')->where(array('status'=>1,'thumb'=>array('neq','')))->order('PV desc')->limit(0,5)->select();
		$this->assign('hot_img_news',$hot_img_news);
		// 资讯评论
		$recomment_list = M()->table(array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'portal_recomment'=>'r'))->where('r.target_id='.$aid.' and r.type=0'.' and u.uid=r.uid')->field('r.*,u.avatar,u.nickname')->order('r.dateline asc')->select();
		$fcid_info = D('Portal_article_cat')->where(array('cid'=>$article['fcid'],'fcid'=>0))->find();
		$cid_info = D('Portal_article_cat')->where(array('cid'=>$article['cid'],'fcid'=>array('neq',0)))->find();

		D('Portal_article')->where(array('aid'=>$aid))->setInc('PV');
		$this->assign('recomment_list',$recomment_list);
		$this->assign('article',$article);
		$this->assign('fcid_info',$fcid_info);
		$this->assign('cid_info',$cid_info);
		$this->display();
	}

	// 精彩图文
	private function get_wonderful_img_news(){
		$res = D('Portal_article')->where(array('status'=>1,'thumb'=>array('neq',''),'dateline'=>array('between',array(time()-604800,time()))))->order('PV desc')->limit(0,5)->select();
		return $res;
	}


	// 用户发表评论
	public function ajax_save_recomment(){

		$uid = (int)$_SESSION['user']['uid'];
		if($uid <= 0){
			exit(json_encode(array('code'=>2,'msg'=>'您还未登录，请先登录')));
		}
		$aid = (int)$_POST['aid'];
		$msg = trim($_POST['msg']);
		$article = D('Portal_article')->where(array('aid'=>$aid))->find();
		if(!$article){
			exit(json_encode(array('code'=>1,'msg'=>'没有找到该资讯')));
		}
		$res = D('Portal_recomment')->data(array('target_id'=>$aid,'uid'=>$uid,'nickname'=>$_SESSION['user']['nickname'],'msg'=>$msg,'dateline'=>time()))->add();
		if(!$res){
			exit(json_encode(array('code'=>1,'msg'=>'保存失败')));
		}
		exit(json_encode(array('code'=>0,'msg'=>'评论成功')));
	}

	// 用户点赞
	public function article_setarticle(){
		$aid = (int)$_POST['aid'];
		$uid = (int)$_SESSION['user']['uid'];
		if($uid <= 0){
			exit(json_encode(array('code'=>2,'msg'=>'您还未登录，请先登录')));
		}
		$res = D('Portal_article')->where(array('aid'=>$aid))->setInc('zan');
		// echo D('Portal_article')->getlastsql();
		if(!$res){
			exit(json_encode(array('code'=>1,'msg'=>'点赞失败')));
		}
		exit(json_encode(array('code'=>0,'msg'=>'点赞成功')));
	}



    // 生成用户打赏金钱信息
    public function article_reward_pay_order() {
        $aid = (int)$_POST['aid'];
        $uid = (int)$_SESSION['user']['uid'];
        if($uid <= 0){
            exit(json_encode(array('code'=>2,'msg'=>'您还未登录，请先登录')));
        }
        // 查询一下用户是否支付过
        $article_pay = M('Reward_order')->where(array('uid'=>$uid,'reward_id'=>$aid, 'status' => 1, 'type' => 1))->find();
        // 如果已经打赏过了，提示打赏过了
        if (!empty($article_pay)) {
            exit(json_encode(array('error'=>1,'msg'=>'已打赏')));
        }
        // 获取文章信息
        $article_info = M('Portal_article')->where(array('aid'=>$aid))->field('title, is_reward, reward_money')->find();
        // 该资讯已被删除
        if (empty($article_info)) {
            exit(json_encode(array('error'=>2,'msg'=>'该资讯已被删除')));
        }
        $userInfo = D('User')->where(array('uid'=>$uid))->field('uid, now_money')->find();
        // 如果余额不足提示去充值
        $reward_money = floatval($article_info['reward_money']);
        $now_money = floatval($userInfo['now_money']);
        $data = array(
            'now_money' => $userInfo['now_money'],  // 当前余额
            'reward_money' => $article_info['reward_money'],  // 打赏金额
        );
        if($reward_money > $now_money){
            $data['difference'] = $reward_money - $now_money; // 差额
            exit(json_encode(array('error'=>3,'msg'=>'当前余额不足请充值', 'info' => $data)));
        }
        $data['difference'] = $now_money - $reward_money; // 差额

        exit(json_encode(array('error'=>5,'info'=>$data)));
    }

	// 用户打赏支付
    public function article_reward_pay() {
        $aid = (int)$_POST['aid'];
        $uid = (int)$_SESSION['user']['uid'];
        if($uid <= 0){
            exit(json_encode(array('code'=>2,'msg'=>'您还未登录，请先登录')));
        }
        // 查询一下用户是否支付过
        $article_pay = M('Reward_order')->where(array('uid'=>$uid,'reward_id'=>$aid, 'status' => 1, 'type' => 1))->find();
        // 如果已经打赏过了，提示打赏过了
        if (!empty($article_pay)) {
            exit(json_encode(array('error'=>1,'msg'=>'已打赏')));
        }
        // 获取文章信息
        $article_info = M('Portal_article')->where(array('aid'=>$aid))->field('title, is_reward, reward_money')->find();
        // 该资讯已被删除
        if (empty($article_info)) {
            exit(json_encode(array('error'=>2,'msg'=>'该资讯已被删除')));
        }
        $userInfo = M('User')->where(array('uid'=>$uid))->field('uid, now_money')->find();
        // 如果余额不足提示去充值
        $reward_money = floatval($article_info['reward_money']);
        $now_money = floatval($userInfo['now_money']);
        if($reward_money > $now_money){
            $data = array(
                'now_money' => $userInfo['now_money'],  // 当前余额
                'reward_money' => $article_info['reward_money'],  // 打赏金额
                'difference' => $reward_money - $now_money,  // 差额
            );
            exit(json_encode(array('error'=>3,'msg'=>'当前余额不足请充值', 'info' => $data)));
        }
        $dec_money = D('User')->user_money($this->user_session['uid'], $reward_money, "打赏门户资讯【".$article_info['title']."】扣除余额 ". $reward_money ." 元");
        if(!$dec_money['error_code']){
            if (empty($article_pay)) {
                $order_info = array(
                    'reward_id' => $aid,
                    'uid' => $uid,
                    'money' => $reward_money,
                    'pay_type' => 3,
                    'status' => 1,
                    'type' => 1,
                    'add_time' => time()
                );
                M('Reward_order')->data($order_info)->add();
            } else {
                M('Reward_order')->where(array('uid'=>$uid,'aid'=>$aid))->data(array('status' => 1, 'money' => $reward_money))->save();
            }
            exit(json_encode(array('error'=>1,'msg'=>'打赏成功')));
        }else{
            exit(json_encode(array('error'=>2,'msg'=>$dec_money['msg'])));
        }
    }


}