<?php
class BbsAction extends BaseAction{
	protected	$village_id;
	protected	$village_type;
	protected	$aBbs;
	protected	$third;
	protected	$uid;
	protected	$urlTot	=	'/tpl/Wap/default/static/bbs/img/tou.png';
	protected $village_bind;
	public function __construct(){
		parent::__construct();
		$this->village_bind = $_SESSION['now_village_bind'];
		$this->village_id = I('village_id',0);
		if(empty($this->village_id)){
			$this->village_id = I('get.village_id',0);
		}
		$this->check_village_session($this->village_id);
		$this->village_type = I('village_type',0);
		if(empty($this->village_type)){
			$this->village_type = I('get.village_type','house');
		}
		$this->uid	=	$this->user_session['uid'];
		$site_url	=	addslashes($this->config['site_url'].'/');
		$this->assign('site_url',$site_url);
		if($this->user_session){
			$this->assign('userSession',$this->user_session);
		}else{
			$this->assign('userSession',0);
		}
		//	论坛第一个图标
		$this->aBbs	=	D('Bbs')->isVillage($this->village_id,$this->village_type);
		if($this->aBbs['status']){
			$this->returnCode('30000002');
		}
		$this->third	=	D($this->aBbs['third_type'].'_village')->field(array('village_name'))->where(array('village_id'=>$this->village_id))->find();
		$this->assign('bbs_index',$this->third['village_name']);
		//	进入论坛的网址
		if(empty($_SESSION['returnUrl'])){
			$returnUrl	=	I('referer');
			$_SESSION['returnUrl']	=	$this->config['site_url'].$returnUrl;
		}
		//$returnUrl	=	str_replace('amp;','',$_SESSION['returnUrl']);
		$returnUrl	=	U('House/village',array('village_id'=>$this->village_id));
        if(defined('IS_INDEP_HOUSE')){
            $returnUrl = U('House/village',array('village_id'=>$this->village_id));
        }
		$this->assign('returnUrl',$returnUrl);

		$now_village = $this->get_village($_GET['village_id']);
	}

	protected function get_village($village_id){
		$now_village = D('House_village')->get_one($village_id);
		if(empty($now_village)){
			$this->error_tips('当前访问的小区不存在或未开放');
		}
		$this->assign('now_village',$now_village);
		return $now_village;
	}


	private function bbs_category_test(){
		$database_bbs = D('Bbs');
		$database_bbs_category = D('Bbs_category');

		$bbs_data['third_type'] = $bbs_where['third_type'] = 'house';
		$bbs_data['third_id'] = $bbs_where['third_id']  = $_GET['village_id'] + 0;
		$bbs_data['auto_verify_post'] = $bbs_where['auto_verify_post']   = 1;
		$bbs_data['auto_verify_reply'] = $bbs_where['auto_verify_reply']   = 1;
		$bbs_data['index_name'] = $bbs_where['index_name'] = '全部';

		$bbs_info = $database_bbs->where($bbs_where)->order('bbs_id desc')->find();

		if(!$bbs_info){
			$insert_id = $database_bbs->data($bbs_data)->add();
		}else{
			$insert_id = $bbs_info['bbs_id'];
		}


		if($insert_id){
			$bbs_cat_data['bbs_id'] = $insert_id;
			$count = $database_bbs_category->where($bbs_cat_data)->count();
			if($count >= 6){
				return false;
			}
			$bbs_cat_data['cat_status'] = 1;
			$bbs_cat_data['cat_aricle_total'] = 0;
			$bbs_cat_data['cat_aricle_num'] = 0;
			$bbs_cat_data['cat_order'] = 0;
			$bbs_cat_data['add_time'] = $bbs_cat_data['last_time'] = time();

			$bbs_cat_data['cat_name'] = '邻里乐园';
			$bbs_cat_data['cat_logo'] = '/tpl/Wap/pure/static/img/jiaoliu.png';
			$database_bbs_category->data($bbs_cat_data)->add();

			$bbs_cat_data['cat_name'] = '活动部落';
			$bbs_cat_data['cat_logo'] = '/tpl/Wap/pure/static/img/news.png';
			$database_bbs_category->data($bbs_cat_data)->add();

			$bbs_cat_data['cat_name'] = '二手闲置';
			$bbs_cat_data['cat_logo'] = '/tpl/Wap/pure/static/img/ershou.png';
			$database_bbs_category->data($bbs_cat_data)->add();
			$bbs_cat_data['cat_name'] = '便民生活';
			$bbs_cat_data['cat_logo'] = '/tpl/Wap/pure/static/img/bianmin.png';
			$database_bbs_category->data($bbs_cat_data)->add();
			$bbs_cat_data['cat_name'] = '美食美刻';
			$bbs_cat_data['cat_logo'] = '/tpl/Wap/pure/static/img/food.png';
			$database_bbs_category->data($bbs_cat_data)->add();
			$bbs_cat_data['cat_name'] = '宠物之家';
			$bbs_cat_data['cat_logo'] = '/tpl/Wap/pure/static/img/chongwu.png';
			$database_bbs_category->data($bbs_cat_data)->add();






		}
	}
	public function check_village_session($village_id){
		if(empty($this->village_bind) && !empty($this->user_session)){
			D('House_village')->get_bind_list($this->user_session['uid'],$this->user_session['phone']);
			$bind_village_list = D('House_village_user_bind')->get_user_bind_list($this->user_session['uid'],$village_id);

			if(!$bind_village_list){
				$bind_village_list = D('House_village_user_bind')->get_family_user_bind_list($this->user_session['uid'],$village_id);
				$bind_village_list = D('House_village_user_bind')->get_user_bind_list($bind_village_list[0]['parent_id'],$village_id);
			}

			if(!empty($bind_village_list)){
				if(count($bind_village_list) == 1){
					$this->village_bind = $_SESSION['now_village_bind'] = $bind_village_list[0];
				}else{
					redirect(U('House/village_select',array('village_id'=>$village_id,'referer'=>urlencode($_SERVER['REQUEST_URI']))));
				}
			}
		}elseif(!empty($this->village_bind) && ($this->village_bind['village_id'] != $village_id)){
			$this->village_bind = array();
		}
	}
	//	论坛首页接口
	public function web_index(){
		$this->check_village_session($this->village_id);
		
		$r	=	D('Bbs')->bbsHotAricle('house',3);
		$this->assign('village_id',$this->village_id);
		$this->assign('index','论坛首页');


		$aPage	=	I('page',1);
		$indexType	=	D('Bbs')->indexType($this->aBbs['bbs_id'],$this->village_id);

		if($indexType_arr['status'] == 0){
			$this->bbs_category_test();
			$indexType	=	D('Bbs')->indexType($this->aBbs['bbs_id'],$this->village_id);
			
		}



		foreach($indexType as $k=>$v){
			$indexType[$k]['cat_logo']	=	$this->config['site_url'].$v['cat_logo'];

			if(defined('IS_INDEP_HOUSE')){
				$v['url'] =  str_replace('wap.php', C('INDEP_HOUSE_URL'), $v['url']);
			}

			$indexType[$k]['url']	=	$this->config['site_url'].$v['url'];
		}

		$newBbsAricleList	=	D('Bbs')->newBbsAricle($this->aBbs['bbs_id'],$aPage,10,'bbs_id');
		//dump($newBbsAricleList);die;
		foreach($newBbsAricleList as $k=>&$v){
			$mezan_num = D('Bbs_praise')->where(array('aricle_id'=>$v['aricle_id'],'uid'=>$this->user_session['uid']))->count();
			if($mezan_num > 0) $v['mezan'] = 1; 	
		}

		if(empty($newBbsAricleList)){
			$newBbsAricleList	=	array();
		}else{
			$newBbsAricleList	=	$this->bbsAricleFormat($newBbsAricleList);
			foreach($newBbsAricleList as $kk=>$vv){
				$isZan	=	D('Bbs')->bbsAricleGetMeZan($vv['aricle_id'],$this->uid);
				if($isZan){
					//$newBbsAricleList[$kk]['zan']	=	1;
					$newBbsAricleList[$kk]['zan']	=	D('Bbs_praise')->where(array('aricle_id'=>$vv['aricle_id']))->count();
				}else{
					$newBbsAricleList[$kk]['zan']	=	0;
				}
			}
			$img	=	$this->imgGet();
		}
		$arr	=	array(
				'indexType'	=>	$indexType,
				'newBbsAricleList'	=>	$newBbsAricleList,
				'img'	=>	$img,
				'icon'	=>	array(
						'index_icon'	=>	$this->config['site_url'].$this->aBbs['index_icon'],
						'index_name'	=>	$this->aBbs['index_name'],
				)
		);
		$this->assign('bbs_list',$arr);
		$this->display();
	}
	public function web_index_json(){
		$aPage	=	I('page',1);
		$indexType	=	D('Bbs')->indexType($this->aBbs['bbs_id'],$this->village_id);
		foreach($indexType as $k=>$v){
			$indexType[$k]['cat_logo']	=	$this->config['site_url'].$v['cat_logo'];

			if(defined('IS_INDEP_HOUSE')){
				$v['url'] =  str_replace('wap.php', C('INDEP_HOUSE_URL'), $v['url']);
			}

			$indexType[$k]['url']	=	$this->config['site_url'].$v['url'];
		}
		$newBbsAricleList	=	D('Bbs')->newBbsAricle($this->aBbs['bbs_id'],$aPage,10,'bbs_id');
		if(empty($newBbsAricleList)){
			$newBbsAricleList	=	array();
		}else{
			$newBbsAricleList	=	$this->bbsAricleFormat($newBbsAricleList);
			foreach($newBbsAricleList as $k=>$v){
				$isZan	=	D('Bbs')->bbsAricleGetMeZan($v['aricle_id'],$this->uid);
				if($isZan){
					$newBbsAricleList[$k]['zan']	=	1;
				}else{
					$newBbsAricleList[$k]['zan']	=	0;
				}
			}
			$img	=	$this->imgGet();
		}
		$arr	=	array(
			'indexType'	=>	$indexType,
			'newBbsAricleList'	=>	$newBbsAricleList,
			'img'	=>	$img,
			'icon'	=>	array(
				'index_icon'	=>	$this->config['site_url'].$this->aBbs['index_icon'],
				'index_name'	=>	$this->aBbs['index_name'],
			)
		);
		$this->returnCode(0,$arr);
	}
	//	论坛文章接口
	public function web_bbs_aricle(){
		$this->assign('village_id',$this->village_id);
		$cat_id	=	I('cat_id',0);
		$cateName	=	M('Bbs_category')->field('cat_name')->where(array('cat_id'=>$cat_id))->find();
		$this->assign('cat_id',$cat_id);
		$this->assign('index',$cateName['cat_name']);



		$aCatId	=	I('cat_id',0);
		$aPage	=	I('page',1);
		$newBbsAricleList	=	D('Bbs')->newBbsAricle($aCatId,$aPage,10,'cat_id');
		if(empty($newBbsAricleList)){
			$newBbsAricleList	=	array();
		}else{
			$newBbsAricleList	=	$this->bbsAricleFormat($newBbsAricleList);
			foreach($newBbsAricleList as $k=>$v){
				$isZan	=	D('Bbs')->bbsAricleGetMeZan($v['aricle_id'],$this->uid);
				if($isZan){
					$newBbsAricleList[$k]['zan']	=	1;
				}else{
					$newBbsAricleList[$k]['zan']	=	0;
				}
			}
			$img	=	$this->imgGet();
		}

		foreach($newBbsAricleList as $k=>&$v){
			$mezan_num = D('Bbs_praise')->where(array('aricle_id'=>$v['aricle_id'],'uid'=>$this->user_session['uid']))->count();
			if($mezan_num > 0) $v['mezan'] = 1; 	
		}
		
		$arr	=	array(
				'newBbsAricleList'	=>	$newBbsAricleList,
				'img'	=>	$img,
				'page'	=>	$aPage,
		);

		

		$this->assign('bbs_list',$arr);
		$this->display();
	}
	public function web_bbs_aricle_json(){
		$aCatId	=	I('cat_id',0);
		$aPage	=	I('page',2);
		$newBbsAricleList	=	D('Bbs')->newBbsAricle($aCatId,$aPage,10,'cat_id');
		if(empty($newBbsAricleList)){
			$newBbsAricleList	=	array();
		}else{
			$newBbsAricleList	=	$this->bbsAricleFormat($newBbsAricleList);
			foreach($newBbsAricleList as $k=>$v){
				$isZan	=	D('Bbs')->bbsAricleGetMeZan($v['aricle_id'],$this->uid);
				if($isZan){
					$newBbsAricleList[$k]['zan']	=	1;
				}else{
					$newBbsAricleList[$k]['zan']	=	0;
				}
			}
			$img	=	$this->imgGet();
		}
		$arr	=	array(
			'newBbsAricleList'	=>	$newBbsAricleList,
			'img'	=>	$img,
			'page'	=>	$aPage,
		);
		$this->returnCode(0,$arr);
	}
	//	文章的连接格式化
	public function bbsAricleFormat($arr=array()){
		foreach($arr as $kk=>$vv){
			if($vv['aricle_img']){
				$aricle_img_arr = explode(';' , $vv['aricle_img']);
				$tmp_aricle_img = array();
				foreach($aricle_img_arr as $img){
					$tmp_aricle_img[] = $this->config['site_url'].$img;
				}
				$arr[$kk]['aricle_img']	=	$tmp_aricle_img;
			}
			if($vv['uid']['avatar']){
				$arr[$kk]['uid']['avatar']	=	$vv['uid']['avatar'];
			}else{
				$arr[$kk]['uid']['avatar']	=	$this->config['site_url'].$this->urlTot;
			}
			if($vv['update_time']){
				//$arr[$kk]['update_time']	=	date('m-d',$arr[$kk]['update_time']);
				$arr[$kk]['update_time']	=	tmspan($arr[$kk]['update_time']);
			}

			if($vv['aricle_content']){

			}

			if($vv['address']){
				$arr[$kk]['address']	=	unserialize(html_entity_decode($vv['address']));
			}

			if($vv['exp_time'] <= time() && $vv['exp_time'] != 0){
				$arr[$kk]['exp_time']	=	'已结束';
			}else if($vv['exp_time'] == 0){
				$arr[$kk]['exp_time']	=	'';
			}else{
				$expTime	=	$vv['exp_time']-time();
				if($expTime >= 86400){
					$arr[$kk]['exp_time']	=	'还剩'.floor($expTime/86400).'天';
				}else if($expTime >= 3600){
					$arr[$kk]['exp_time']	=	'还剩'.intval($expTime/3600).'小时';
				}else if($expTime >= 60){
					$arr[$kk]['exp_time']	=	'还剩'.intval($expTime/60).'分钟';
				}else if($expTime >= 0){
					$arr[$kk]['exp_time']	=	'还剩'.intval($expTime).'秒';
				}
			}
			foreach($vv['img'] as $kkk=>$vvv){
				$arr[$kk]['img'][$kkk]['aricle_img']	=	$this->config['site_url'].$vvv['aricle_img'];
			}
		}
		return $arr;
	}
	//	点赞图片获取
	public function imgGet(){
		$img	=	array(
			'aricle_praise_default'	=>	$this->config['site_url'].'/tpl/Wap/default/static/bbs/img/zan1.png',
			'aricle_praise_click'	=>	$this->config['site_url'].'/tpl/Wap/default/static/bbs/img/zan2.png',
			'aricle_comment_default'=>	$this->config['site_url'].'/tpl/Wap/default/static/bbs/img/ping.png',
		);
		return	$img;
	}
	//	论坛文章发布接口
	public function web_bbs_aricele_wite(){
		if(empty($_SESSION['now_village_bind'])){
			//$this->error_tips('您不属于该小区',U('House/bind_village',array('village_id'=>$_GET['village_id'])));
			redirect(U('House/bind_village',array('village_id'=>$_GET['village_id'])));
		}
		if($_SESSION['now_village_bind']['village_id'] != $_GET['village_id']){
			//$this->error_tips('您不属于该小区！');
			redirect(U('House/bind_village',array('village_id'=>$_GET['village_id'])));
		}

		$cat_id	=	I('cat_id');
		if(IS_POST){
			$aricle_title	=	I('aricle_title');
      $aricle_content	=	I('aricle_content');
      var_dump(json_encode(I('cat_id')));
      exit();
			if(empty($cat_id)){
				$this->error_tips('分类ID不能为空！');
			}else if(empty($aricle_title)){
				$this->error_tips('标题不能为空！');
			}else if(empty($aricle_content)){
				//$this->returnCode('30010013');
			}

			$aricle_content	=	str_replace("\n", "</p><p>",$aricle_content);
			$aricle_content	=	str_replace("&amp;amp;quot;", '"',$aricle_content);
			$aricle_title	=	str_replace("&amp;amp;quot;", '"',$aricle_title);
			$aricle_content	=	'<p>'.$aricle_content.'</p>';
			$inputimg	=	implode(';',I('inputimg'));

			/*if($inputimg[0]){
				$img	=	explode(';',$inputimg);
				foreach($img as $k=>$v){
					if($v=='undefined' || empty($v) || $v==null){
						continue;
					}else{
						$tmpImg[]	=	$v;
					}
				}
			}else{
				$tmpImg[]	=	array();
			}*/

			if($_POST['type'] == 0){
				$arr	=	array(
						'bbs_id'	=>	$this->aBbs['bbs_id'],
						'cat_id'	=>	$cat_id,
						'aricle_title'	=>	$aricle_title,
						'aricle_content'	=>	'',
						'uid'			=>	$this->uid,
						'aricle_img'		=>	$inputimg,
						'aricle_status'		=>	$this->aBbs['auto_verify_post'],
						'create_time'		=>	time(),
						'update_time'		=>	time(),
						'exp_time'			=>	time(),
						'address'			=> $_POST['address'],
						'type' 				=> 0
				);
			}else{
				$aricle_title	=	I('aricle_title_activity');
				$num = $_POST['num'] + 0;
				if($num > 200){
					$this->error_tips('人数最大为200人！');
				}

				$arr	=	array(
						'bbs_id'	=>	$this->aBbs['bbs_id'],
						'cat_id'	=>	$cat_id,
						'aricle_title'	=>	$aricle_title,
						'aricle_content'	=>	$aricle_content,
						'uid'			=>	$this->uid,
						'aricle_img'		=>	$inputimg,
						'aricle_status'		=>	$this->aBbs['auto_verify_post'],
						'create_time'		=>	time(),
						'update_time'		=>	time(),
						'exp_time'			=>	time(),
						'address'			=> $_POST['address'],
						'type' 				=> 1,
						'num'				=> $_POST['num'] + 0,
						'close_time'=>strtotime($_POST['activity_date'])
				);
			}

			$aAricleAdd	=	D('Bbs')->newBbsAricleWite($arr,explode(';',$inputimg));
			if(empty($aAricleAdd)){
				$this->error_tips('文章发布失败！');
			}else{
        M('Bbs_category')->where(array('cat_id'=>$cat_id))->setInc('cat_aricle_total');
        // header('Content-Type:application/json');
        // echo json_encode(['status'=>1,'data'=>'新增成功']);


				// if($this->aBbs['auto_verify_post'] == 1){
				// 	M('Bbs_category')->where(array('cat_id'=>$cat_id))->setInc('cat_aricle_num');
				// 	$this->success_tips('文章发布成功！', U('Bbs/web_bbs_aricle',array('village_id'=>$_GET['village_id'],'cat_id'=>$_GET['cat_id'])));
				// }else{
				// 	$this->success_tips('文章发布成功,请等待审核！', U('Bbs/web_bbs_aricle',array('village_id'=>$_GET['village_id'],'cat_id'=>$_GET['cat_id'])));
				// }
			}
		}else{
			$this->assign('cat_id',$cat_id);
			$this->assign('village_id',$this->village_id);
			$this->assign('index','发布');
			$this->display();
		}
	}
	public function web_bbs_aricele_wite_json(){
		$cat_id	=	I('cat_id');
		$aricle_title	=	I('aricle_title');
		$aricle_content	=	I('aricle_content');
		$exp_time	=	I('exp_time',0);
		if(empty($cat_id)){
			$this->returnCode('30000006');
		}else if(empty($aricle_title)){
			$this->returnCode('30010012');
		}else if(empty($aricle_content)){
			$this->returnCode('30010013');
		}
		;
		$aricle_content	=	str_replace("\n", "</p><p>",$aricle_content);
		$aricle_content	=	str_replace("&amp;amp;quot;", '"',$aricle_content);
		$aricle_title	=	str_replace("&amp;amp;quot;", '"',$aricle_title);
		$aricle_content	=	'<p>'.$aricle_content.'</p>';
		$inputimg	=	I('inputimg');
		if($inputimg){
			$img	=	explode(';',$inputimg);
			foreach($img as $k=>$v){
				if($v=='undefined' || empty($v) || $v==null){
					continue;
				}else{
					$tmpImg[]	=	$v;
				}
			}
		}else{
			$tmpImg[]	=	array();
		}
		if($exp_time){
			$exp_time	=	time()+(60*60*24)*$exp_time;
		}
		$arr	=	array(
			'bbs_id'	=>	$this->aBbs['bbs_id'],
			'cat_id'	=>	$cat_id,
			'aricle_title'	=>	$aricle_title,
			'aricle_content'	=>	$aricle_content,
			'uid'			=>	$this->uid,
			'aricle_img'		=>	$tmpImg[0],
			'aricle_status'		=>	$this->aBbs['auto_verify_post'],
			'create_time'		=>	time(),
			'update_time'		=>	time(),
			'exp_time'			=>	$exp_time,
		);
		$aAricleAdd	=	D('Bbs')->newBbsAricleWite($arr,$tmpImg);
		if(empty($aAricleAdd)){
			$this->returnCode('30020001');
		}else{
			M('Bbs_category')->where(array('cat_id'=>$cat_id))->setInc('cat_aricle_total');
			if($this->aBbs['auto_verify_post'] == 1){
				M('Bbs_category')->where(array('cat_id'=>$cat_id))->setInc('cat_aricle_num');
			}
			$this->returnCode(0,$aAricleAdd);
		}
	}
	//	论坛文章详情接口
	public function web_bbs_aricele_details(){
		$aricle_id	=	I('aricle_id',0);
		if(empty($aricle_id)){
			$aricle_id	=	I('get.aricle_id',0);
		}
		$cat_id		=	I('cat_id',0);
		if(empty($cat_id)){
			$cat_id	=	I('get.cat_id',0);
		}
		if(empty($cat_id)){
			$findCat_id	=	M('Bbs_aricle')->field('cat_id')->where(array('aricle_id'=>$aricle_id))->find();
			$cat_id	=	$findCat_id['cat_id'];
		}
		$cateName	=	M('Bbs_category')->field('cat_name')->where(array('cat_id'=>$cat_id))->find();
		$status		=	I('status',2);
		
		$mezan_num = D('Bbs_praise')->where(array('aricle_id'=>$aricle_id,'uid'=>$this->user_session['uid']))->count();
		if($mezan_num > 0) $this->assign('mezan',1); else $this->assign('mezan',0);

		
		$this->assign('aricle_id',$aricle_id);
		$this->assign('cat_id',$cat_id);
		$this->assign('status',$status);
		$this->assign('village_id',$this->village_id);
		$this->assign('index',$cateName['cat_name']);

		$aricle_id	=	I('aricle_id',0);
		$page	=	I('page',1);
		$zanPageNumber	=	I('zanPageNumber',1);
		if(empty($aricle_id)){
			$this->returnCode('30000003');
		}
		$aDetails	=	D('Bbs')->bbsAricleDetails($aricle_id);
		if($aDetails['uid']['uid'] == $this->uid){
			$is_uid	=	1;
		}else{
			$is_uid	=	2;
		}
		if($aDetails && is_array($aDetails)){
			$aDteais	=	$this->bbsAricleFormat(array($aDetails));
			$aDteais[0]['third']	=	$this->third['village_name'];
			$aComment	=	D('Bbs')->bbsAricleComment($aricle_id,$page,9999);
			if($aComment['status'] != 0){
				foreach($aComment['list'] as $k=>$v){
					if(empty($v['uid']['avatar'])){
						$aComment['list'][$k]['uid']['avatar']	=	$this->config['site_url'].$this->urlTot;
					}
					$aCommentNumber	=	$k+1;
					if($v['comment_fid'] != 0){
						$sFUid	=	D('Bbs_comment')->field('uid')->where(array('comment_id'=>$v['comment_fid']))->find();

						if($sFUid){
							$sFUid	=	D('User')->field(array('uid','nickname','last_time','avatar'))->where(array('uid'=>$sFUid['uid']))->find();
						}
						if(empty($sFUid['avatar'])){
							$sFUid['avatar']	=	$this->config['site_url'].$this->urlTot;
						}
						$aComment['list'][$k]['comment_fname']	=	$sFUid;
					}
					if($v['uid']['uid']	==	$this->uid){
						$aComment['list'][$k]['uid']['status']	=	1;
					}else{
						$aComment['list'][$k]['uid']['status']	=	2;
					}
					$aComment['list'][$k]['create_time']	=	date('m-d H:i',$aComment['list'][$k]['create_time']);
				}
			}
		}

		$tmp_comment_list = array();
		foreach($aComment['list'] as $key=>$comment){
			if($comment['comment_fid'] == 0){
				$tmp_comment_list[$comment['comment_id']] = $comment;
			}else{
				$tmp_comment_list[$comment['comment_fid']]['comment_reply_list'][] = $comment;
			}
		}
		$aComment['list'] = $tmp_comment_list;

		$aZanList	=	D('Bbs')->bbsAricleGetZan($aricle_id,$zanPageNumber,9999);

		$tmp_aZanList = array();
		foreach($aZanList as $k=>$v){
			$tmp_aZanList[$v['uid']['uid']] = $v;
		}
		$aZanList = $tmp_aZanList;

		/*foreach($aZanList as $k=>$v){
			if(empty($v['uid']['avatar'])){
				$aZanList[$k]['uid']['avatar']	=	$this->config['site_url'].$this->urlTot;
			}
		}*/
		foreach($aZanList as $k=>$v){
			if($this->user_session['uid'] == $v['uid']['uid']){
				$exist	=	1;break;
			}else{
				$exist	=	0;
			}
		}

		if($aDteais[0]['type']==1){
			$activity_apply_num = D('Bbs_activity_apply')->where(array('bbs_id'=>$aDteais[0]['aricle_id'],'is_del'=>0))->count();
			$aDteais[0]['activity_apply_num'] = $activity_apply_num;
		}

		$database_bbs_activity_apply = D('Bbs_activity_apply');
		$is_bbs_activity_apply = ($database_bbs_activity_apply->where(array('aricle_id'=>$_GET['aricle_id'],'uid'=>$this->uid))->count()) ? true : false;


		$arr	=	array(
				'aDetails'	=>	$aDteais[0],
				'is_uid'	=>	$is_uid,
				'aComment'	=>	$aComment['list'],
				'aZanList'	=>	$aZanList,
				'exist'		=>	$exist,
				'img'		=>	$this->imgGet(),
				'page'		=>	$page,
				'is_bbs_activity_apply' => $is_bbs_activity_apply,
		);

		$this->assign('detail' , $arr);
		$this->display();
	}


	public function ajax_bbs_activity(){
		$aricle_id = I('aricle_id');
		$uid = $this->uid;
		$database_bbs_aricle = D('Bbs_aricle');
		$database_bbs_activity_apply = D('Bbs_activity_apply');

		$activity_where['aricle_id'] = $aricle_id;
		$count = $database_bbs_activity_apply->where($activity_where)->count();
		$activity_data['aricle_id'] = $aricle_id;
		$bbs_aricle_info = $database_bbs_aricle->where(array('aricle_id'=>$aricle_id))->find();

		if($bbs_aricle_info['num'] <= $count){
			exit(json_encode(array('status'=>0,'msg'=>'报名人数已满！')));
		}

		$activity_where['uid'] = $uid;
		$count = $database_bbs_activity_apply->where($activity_where)->count();
		if($count){
			exit(json_encode(array('status'=>0,'msg'=>'您已经报过名！')));
		}



		$activity_data['uid'] = $uid;
		$activity_data['add_time'] = time();
		$insert_id = $database_bbs_activity_apply->add($activity_data);

		if($insert_id){
			exit(json_encode(array('status'=>1,'msg'=>'报名成功！')));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>'报名失败！')));
		}
	}

	public function web_bbs_aricele_details_json(){
		$aricle_id	=	I('aricle_id',0);
		$page	=	I('page',1);
		$zanPageNumber	=	I('zanPageNumber',1);
		if(empty($aricle_id)){
			$this->returnCode('30000003');
		}
		$aDetails	=	D('Bbs')->bbsAricleDetails($aricle_id);
		if($aDetails['uid']['uid'] == $this->uid){
			$is_uid	=	1;
		}else{
			$is_uid	=	2;
		}
		if($aDetails && is_array($aDetails)){
			$aDteais	=	$this->bbsAricleFormat(array($aDetails));
			$aDteais[0]['third']	=	$this->third['village_name'];
			$aComment	=	D('Bbs')->bbsAricleComment($aricle_id,$page);
			if($aComment['status'] != 0){
				foreach($aComment['list'] as $k=>$v){
					if(empty($v['uid']['avatar'])){
						$aComment['list'][$k]['uid']['avatar']	=	$this->config['site_url'].$this->urlTot;
					}
					$aCommentNumber	=	$k+1;
					if($v['comment_fid'] != 0){
						$sFUid	=	D('Bbs_comment')->field('uid')->where(array('comment_id'=>$v['comment_fid']))->find();

						if($sFUid){
							$sFUid	=	D('User')->field(array('uid','nickname','last_time','avatar'))->where(array('uid'=>$sFUid['uid']))->find();
						}
						if(empty($sFUid['avatar'])){
							$sFUid['avatar']	=	$this->config['site_url'].$this->urlTot;
						}
						$aComment['list'][$k]['comment_fname']	=	$sFUid;
					}
					if($v['uid']['uid']	==	$this->uid){
						$aComment['list'][$k]['uid']['status']	=	1;
					}else{
						$aComment['list'][$k]['uid']['status']	=	2;
					}
					$aComment['list'][$k]['create_time']	=	date('m-d H:i',$aComment['list'][$k]['create_time']);
				}
			}
		}
		$aZanList	=	D('Bbs')->bbsAricleGetZan($aricle_id,$zanPageNumber);
		foreach($aZanList as $k=>$v){
			if(empty($v['uid']['avatar'])){
				$aZanList[$k]['uid']['avatar']	=	$this->config['site_url'].$this->urlTot;
			}
		}
		foreach($aZanList as $k=>$v){
			if($this->user_session['uid'] == $v['uid']['uid']){
				$exist	=	1;break;
			}else{
				$exist	=	0;
			}
		}
		//if($aZanList){
//			$aZanNumber	=	count($aZanList);
//		}else{
//			$aZanNumber	=	0;
//		}
		$arr	=	array(
			'aDetails'	=>	$aDteais[0],
			'is_uid'	=>	$is_uid,
			'aComment'	=>	$aComment['list'],
			'aZanList'	=>	$aZanList,
			'exist'		=>	$exist,
			'img'		=>	$this->imgGet(),
			'page'		=>	$page,
		);
		$this->returnCode(0,$arr);
	}
	//	删除文章接口
	public function web_bbs_aricele_delete(){
		$aricle_id	=	I('aricle_id',0);
		if(empty($aricle_id)){
			$this->returnCode('30000003');
		}
		$sSave	=	D('Bbs')->bbsAricleDelete($aricle_id);
		if($sSave){
			$this->returnCode(0,$sSave);
		}else{
			$this->returnCode('30020002');
		}
	}
	//	文章评论接口
	public function web_bbs_comment(){
		$aricele_id	=	I('aricele_id',0);
		$page	=	1;
		if(empty($aricele_id)){
			$this->error_tips('文章ID不能为空');
		}
		$aComment	=	D('Bbs')->bbsAricleComment($aricele_id,$page);
		$this->assign('aComment',$aComment);
		$this->display();
	}
	//	论坛文章写评论和再评论接口
	public function web_bbs_wite_comment(){
		$aricle_id	=	I('aricle_id',0);
		$village_id	=	I('village_id',0);
		$cat_id		=	I('cat_id',0);
		$comment_id	=	I('comment_id',0);
		$status		=	I('status',0);

		if($_SESSION['now_village_bind']['village_id'] != $village_id){
			$this->error_tips('您不属于该小区！');
		}

		if($comment_id){
			$aUid	=	D('Bbs_comment')->field(array('uid'))->where(array('comment_id'=>$comment_id))->find();
			$aUid	=	D('User')->field(array('uid','nickname'))->where(array('uid'=>$aUid['uid']))->find();
		}
		$this->assign('aUid',$aUid);
		$this->assign('aricle_id',$aricle_id);
		$this->assign('village_id',$village_id);
		$this->assign('cat_id',$cat_id);
		$this->assign('comment_id',$comment_id);
		$this->assign('status',$status);
		$this->assign('index','评论');
		$this->display();
	}
	public function web_bbs_wite_comment_json(){
		$aricle_id	=	I('aricle_id');
		$comment_fid	=	I('comment_fid',0);
		$comment_content	=	I('comment_content',0);
		$village_id	=	$_GET['village_id'];
		if(empty($aricle_id)){
			$this->error_tips('文章不能为空');
		}else if(empty($comment_content)){
			$this->error_tips('评论不能为空');
		}
		if($this->village_bind['village_id']  != $village_id){
			$this->error_tips('您不属于该小区！');
		}

		$arr	=	array(
			'comment_fid'	=>	$comment_fid,
			'aricle_id'	=>	$aricle_id,
			'comment_content'	=>	$comment_content,
			'comment_status'	=>	$this->aBbs['auto_verify_reply'],
			'create_time'	=>	time(),
		);
		
		if(I('uid')){
			$arr['uid'] = I('uid');
		}else{
			$arr['uid'] = $this->uid;
		}
		$arr['uid'] = $this->uid;

		$sWite	=	D('Bbs')->bbsAricleCommentWite($arr);
		if($sWite){
			if($this->aBbs['auto_verify_reply'] == 1){
				$this->success('评论成功');
			}else{
				$this->success('评论成功，请等待审核');
			}
		}else{
			$this->error('评论失败');
		}
	}
	//	论坛文章点赞接口
	public function web_bbs_aricele_zan($aricle_id){
		if($_REQUEST['village_id'] != $_SESSION['now_village_bind']['village_id']){
			$this->returnCode('30010014');
		}

		$aricle_id	=	I('aricle_id',0);
		if(empty($aricle_id)){
			$this->returnCode('30000003');
		}
		$uid	=	$this->user_session['uid'];
		if(empty($uid)){
			$this->returnCode('30010010');
		}
		$sZan	=	D('Bbs')->bbsAricleZan($aricle_id,$uid);
		$this->returnCode(0,$sZan);
	}
	//	论坛文章读取赞列表
	public function web_bbs_aricele_zan_list($aricle_id){
		if($_REQUEST['village_id'] != $_SESSION['now_village_bind']['village_id']){
			$this->returnCode('30010014');
		}

		$aricle_id	=	I('aricle_id',0);
		if(empty($aricle_id)){
			$this->returnCode('30000003');
		}
		$sZan	=	D('Bbs')->bbsAricleGetZan($aricle_id);
		if($sZan){
			foreach($sZan as $k=>$v){
				if(empty($v['uid']['avatar'])){
					$sZan[$k]['uid']['avatar']	=	$this->config['site_url'].$this->urlTot;
				}
			}
			foreach($sZan as $k=>$v){
				if($this->user_session['uid'] == $v['uid']['uid']){
					$exist	=	1;break;
				}else{
					$exist	=	0;
				}
			}
		}
		$this->returnCode(0,$sZan);
	}
	//	论坛评论
	public function web_bbs_comment_delete(){
		$comment_id	=	I('comment_id',0);
		$aricle_id	=	I('aricle_id',0);
		if(empty($comment_id)){
			$this->returnCode('30000005');
		}else{
			$isComment	=	D('Bbs')->bbsCommentDelete($comment_id);
		}
		$this->returnCode(0,$isComment);
	}
	
	
	#我的帖子列表 - wangdong
	public function my_bbs_list(){
		
		$database_bbs_aricle = D("Bbs_aricle");
		$table = array(C('DB_PREFIX') . 'bbs_aricle' => 'a', C('DB_PREFIX') . 'bbs_category' => 'c');
		$where = "`a`.`uid`=".$this->user_session['uid']." AND `a`.`aricle_status` in (1,2,3) AND `a`.`cat_id`=`c`.`cat_id`";
		$my_bbs_list = D('')->table($table)->where($where)->order('`a`.`aricle_sort` DESC,`a`.`aricle_id` DESC')->select();
		foreach($my_bbs_list as $k=>&$v){
			
			$arr_address = unserialize(htmlspecialchars_decode($v['address']));
			$v['address_text'] = $arr_address['city'].$arr_address['district'].$arr_address['street'].$arr_address['street_number'];
				
		}
		$this->assign('my_bbs_list' , $my_bbs_list);
		
		$user['avatar'] = $this->user_session['avatar'];
		$user['name'] = $this->user_session['nickname'] ? $this->user_session['nickname'] : substr($this->user_session['phone'],0,3)."***".substr($this->user_session['phone'],7,4);
		$this->assign('user' , $user);
		
		$this->display();		
	}
	
	#删除我的帖子 - wangdong
	public function ajax_bbs_delete(){
		
		if(IS_AJAX){
			
			$aricle_id = $_POST['aricle_id'] + 0;
			
			if(!$aricle_id || !$this->user_session) $this->error('参数传递有误！~~~~');	
			
			$database_bbs_aricle = D('Bbs_aricle');
			$where['aricle_id'] = $aricle_id;
			$where['uid']       = $this->user_session['uid'];
			
			$data['aricle_status'] = 4;
			$delete_id = $database_bbs_aricle->where($where)->data($data)->save();
			if($delete_id){
                exit(json_encode(array('status'=>0,'msg'=>"帖子删除成功")));
			}else{
				exit(json_encode(array('status'=>1,'msg'=>'帖子删除失败')));
			}
            
			
			
		}
			
	}
	
	//	图片上传
    public function ajaxImgUpload() {
		$mulu=isset($_GET['ml']) ? trim($_GET['ml']):'group';
		$mulu=!empty($mulu) ? $mulu : 'group';
        $filename = trim($_POST['filename']);
        $img = $_POST[$filename];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $imgdata = base64_decode($img);
		$rand_num = date('Ymd').'/'.$this->user_session['uid'];
        $getupload_dir = "/upload/bbs/aricle_img/".$rand_num;
        $upload_dir = "." . $getupload_dir;
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $newfilename = $mulu.'_' . date('YmdHis').mt_rand(10,99). '.jpg';
        $save = file_put_contents($upload_dir . '/' . $newfilename, $imgdata);
        if ($save) {
        	$this->dexit(array('error' => 0, 'data' => array('code' => 1, 'siteurl'=>$this->config['site_url'],'imgurl' =>$getupload_dir . '/' . $newfilename, 'msg' => '')));
        } else {
            $this->dexit(array('error' => 1, 'data' => array('code' => 0, 'url' => '', 'msg' => '保存失败！')));
        }
    }
    /*     * json 格式封装函数* */
    private function dexit($data = '') {
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
        exit();
    }
    public function login(){
		$this->error_tips('请先进行登录！',U('Login/index'));
    }


	public function ajax_get_address_list(){
		if(IS_AJAX){
			$lat = $_POST['lat'];
			$long = $_POST['long'];
			$url = 'http://api.map.baidu.com/geocoder/v2/?location='.$lat.','.$long.'&output=json&pois=1&ak=4c1bb2055e24296bbaef36574877b4e2';
			$result = httpRequest($url);
			$result = json_decode($result[1],true);
			exit(json_encode(array('formatted_address'=>$result['result']['formatted_address'],'addressComponent'=>serialize($result['result']['addressComponent']))));
		}else{
			$this->error_tips('访问页面有误！');
		}
	}
}
?>
