<?php
/*
 * 社区功能管理
 *
 */
class House_bbsAction extends BaseAction{
	//	获取电话分类
    public function index(){
    	$this->village_id = I('village_id',0);
    	$this->village_type = I('village_type',0);
    	$uid = I('uid');
    	//	获取小区和论坛关系
		$this->aBbs	=	D('Bbs')->isVillage($this->village_id,$this->village_type);
		$aPage	=	I('page',1);
		//	查询论坛分类
		$indexType	=	D('Bbs')->indexType($this->aBbs['bbs_id'],$this->village_id);

		foreach($indexType as $k=>$v){
			$indexType[$k]['cat_logo']	=	$this->config['site_url'].$v['cat_logo'];
			$indexType[$k]['url']	=	$this->config['site_url'].$v['url']."&app_no_header=1";
		}

		//	最新文章列表统计
		$newBbsAricleCount	=	D('Bbs_aricle')->where(array('bbs_id'=>$this->aBbs['bbs_id'],'aricle_status'=>1))->order('create_time desc')->count();
		//	最新文章列表
		$newBbsAricleList	=	D('Bbs')->newBbsAricle($this->aBbs['bbs_id'],$aPage,10,'bbs_id');

		if(empty($newBbsAricleList)){
			$newBbsAricleList	=	array();
		}else{
			$newBbsAricleList	=	$this->bbsAricleFormat($newBbsAricleList);
			foreach($newBbsAricleList as $k=>$v){
				$newBbsAricleList[$k]['url'] = $this->config['site_url']."/wap.php?g=Wap&c=Bbs&a=web_bbs_aricele_details&aricle_id=".$v['aricle_id']."&village_id=".$this->village_id."&cat_id=".$v['cat_id']['cat_id']."&status=1";
				$isZan	=	D('Bbs')->bbsAricleGetMeZan($v['aricle_id'],$uid);
				if($isZan){
					$newBbsAricleList[$k]['zan']	=	1;
				}else{
					$newBbsAricleList[$k]['zan']	=	0;
				}

				$newBbsAricleList[$k]['create_time'] = date("Y-m-d",$v['create_time']);
			}
		}
		// dump($newBbsAricleList);
		$arr	=	array(
				'indexType'	=>	$indexType,
				'newBbsAricleList'	=>	$newBbsAricleList,
				'newBbsAricleCount'	=>	$newBbsAricleCount,
				'newBbsAriclePage'	=>	ceil($newBbsAricleCount/10),
		);

		$this->returnCode(0,$arr);
	}

	//	对文章点赞
	public function bbs_aricele_zan(){
		$village_id	=	I('village_id');
		$user_village_id = I('user_village_id');
		if($village_id != $user_village_id){
			$this->returnCode('30010014');
		}

		$aricle_id	=	I('aricle_id',0);
		if(empty($aricle_id)){
			$this->returnCode('30000003');
		}

		$uid	=	I('uid');
		if(empty($uid)){
			$this->returnCode('30010010');
		}
		$sZan	=	D('Bbs')->bbsAricleZan($aricle_id,$uid);
		if($sZan == false){
			$this->returnCode(20003,'','您已点赞');
		}else{
			$this->returnCode(0,$sZan);
		}
		
	}

	//用户对社区论坛文章评论
	public function bbs_wite_comment(){
		$aricle_id	=	I('aricle_id');
		$comment_content	=	I('comment_content',0);
		$village_id	=	I('village_id');
		$village_type = I('village_type','house');
		$uid	=	I('uid');
		$comment_fid	=	'';
		
		$this->aBbs	=	D('Bbs')->isVillage($village_id,$village_type);

		if(empty($aricle_id)){
			$this->returnCode(20000,'','文章不能为空');
		}else if(empty($comment_content)){
			$this->returnCode(20001,'','评论不能为空');
		}

		$arr	=	array(
			'comment_fid'	=>	$comment_fid,
			'aricle_id'	=>	$aricle_id,
			'comment_content'	=>	$comment_content,
			'comment_status'	=>	$this->aBbs['auto_verify_reply'],
			'create_time'	=>	time(),
			'uid'	=>	$uid,
		);

		$sWite	=	D('Bbs')->bbsAricleCommentWite($arr);
		$this->returnCode(0,$sWite);
	}

	// 论坛文章详情
	public function aricle_info(){
		$village_id = I('village_id',0);
    	$village_type = I('village_type',0);
    	$aricle_id	=	I('aricle_id');
    	$uid	=	I('uid');
    	//	获取小区和论坛关系
		$aBbs	=	D('Bbs')->isVillage($village_id,$village_type);
		$aricle_info	=	D('Bbs_aricle')->where(array('bbs_id'=>$aBbs['bbs_id'],'aricle_status'=>1,'aricle_id'=>$aricle_id))->find();

		$aricle_info['uid']	=	D('User')->field(array('uid','nickname','last_time','avatar'))->where(array('uid'=>$aricle_info['uid']))->find();
		//	查找文章所属分类
		$aricle_info['cat_id']	=	D('Bbs_category')->field(array('cat_id','cat_name'))->where(array('cat_id'=>$aricle_info['cat_id']))->find();

		$aricle_info['create_time'] = date("Y-m-d",$aricle_info['create_time']);
		$aricle_info	=	$this->bbsAricleInfoFormat($aricle_info);
		$aricle_info['url'] = $this->config['site_url']."/wap.php?g=Wap&c=Bbs&a=web_bbs_aricele_details&aricle_id=".$aricle_info['aricle_id']."&village_id=".$village_id."&cat_id=".$aricle_info['cat_id']['cat_id']."&status=1";
		$isZan	=	D('Bbs')->bbsAricleGetMeZan($aricle_info['aricle_id'],$uid);
		if($isZan){
			$aricle_info['zan']	=	1;
		}else{
			$aricle_info['zan']	=	0;
		}
		// dump($aricle_info);
		$this->returnCode(0,$aricle_info);
	}

	public function bbsAricleFormat($arr=array()){
		$urlTot	=	'/tpl/Wap/default/static/bbs/img/tou.png';
		foreach($arr as $kk=>$vv){
			if($vv['aricle_img']){
				$aricle_img_arr = explode(';' , $vv['aricle_img']);
				$tmp_aricle_img = array();
				foreach($aricle_img_arr as $img){
					$tmp_aricle_img[] = $this->config['site_url'].$img;
				}
				$arr[$kk]['aricle_img']	=	$tmp_aricle_img;
			}else{
				$arr[$kk]['aricle_img']	= array();
			}
			if($vv['uid']['avatar']){
				$arr[$kk]['uid']['avatar']	=	$vv['uid']['avatar'];
			}else{
				$arr[$kk]['uid']['avatar']	=	$this->config['site_url'].$urlTot;
			}
			if($vv['update_time']){
				$arr[$kk]['update_time']	=	date('m-d',$vv['update_time']);
			}

			if($vv['address']){
				$adress_bak	=	unserialize(html_entity_decode($vv['address']));
				$adress = $adress_bak['city'].'-'.$adress_bak['district'];
				$arr[$kk]['address']	=	$adress;
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

	public function bbsAricleInfoFormat($arr=array()){
		$urlTot	=	'/tpl/Wap/default/static/bbs/img/tou.png';
		if($arr['aricle_img']){
			$aricle_img_arr = explode(';' , $arr['aricle_img']);
			$tmp_aricle_img = array();
			foreach($aricle_img_arr as $img){
				$tmp_aricle_img[] = $this->config['site_url'].$img;
			}
			$arr['aricle_img']	=	$tmp_aricle_img;
		}else{
			$arr['aricle_img']	= array();
		}
		if($arr['uid']['avatar']){
			$arr['uid']['avatar']	=	$arr['uid']['avatar'];
		}else{
			$arr['uid']['avatar']	=	$this->config['site_url'].$urlTot;
		}
		if($arr['update_time']){
			$arr['update_time']	=	date('m-d',$arr['update_time']);
		}

		if($vv['address']){
			$adress_bak	=	unserialize(html_entity_decode($arr['address']));
			$adress = $adress_bak['city'].'-'.$adress_bak['district'];
			$arr['address']	=	$adress;
		}

		if($arr['exp_time'] <= time() && $arr['exp_time'] != 0){
			$arr['exp_time']	=	'已结束';
		}else if($arr['exp_time'] == 0){
			$arr['exp_time']	=	'';
		}else{
			$expTime	=	$arr['exp_time']-time();
			if($expTime >= 86400){
				$arr['exp_time']	=	'还剩'.floor($expTime/86400).'天';
			}else if($expTime >= 3600){
				$arr['exp_time']	=	'还剩'.intval($expTime/3600).'小时';
			}else if($expTime >= 60){
				$arr['exp_time']	=	'还剩'.intval($expTime/60).'分钟';
			}else if($expTime >= 0){
				$arr['exp_time']	=	'还剩'.intval($expTime).'秒';
			}
		}
		foreach($arr['img'] as $kkk=>$vvv){
			$arr['img'][$kkk]['aricle_img']	=	$this->config['site_url'].$vvv['aricle_img'];
		}
		return $arr;
	}


}