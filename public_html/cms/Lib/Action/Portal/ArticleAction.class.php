<?php
/**
* 文章板块
*/
class ArticleAction extends BaseAction {
	public function _initialize(){
		parent::_initialize();
		$this->assign('portal_name','资讯中心');
	}
	// 首页
	public function index(){
		//精品帖子推荐
		$essenceTiebaList = D('Portal_tieba')->where(array('is_essence'=>1,'target_id'=>0,'status'=>0))->order('last_time desc')->limit(10)->select();
		$this->assign('essenceTiebaList',$essenceTiebaList);
		// 最新添加的帖子
		$addTiebaList = D('Portal_tieba')->where(array('target_id'=>0,'status'=>0))->order('add_time desc')->limit(10)->select();
		$this->assign('addTiebaList',$addTiebaList);
		//顶部广告
		$portal_article_index_top = D('Adver')->get_adver_by_key('portal_article_index_top');
		$this->assign('portal_article_index_top',$portal_article_index_top);

		//顶部左侧
		$portal_article_index_left = D('Adver')->get_adver_by_key('portal_article_index_left');
		$this->assign('portal_article_index_left',$portal_article_index_left);

		//顶部右侧
		$portal_article_index_right = D('Adver')->get_adver_by_key('portal_article_index_right');
		$this->assign('portal_article_index_right',$portal_article_index_right);


		//轮播广告
		$portal_article_round = D('Adver')->get_adver_by_key('portal_article_round',4);
		$this->assign('portal_article_round',$portal_article_round);

		// 前4个标签
		$labels = D('Portal_article_label')->order('id asc')->limit('0,4')->select();
		
		// 标签文章列表
		foreach($labels as $key => $label){
			$relations = D('Portal_article_label_relation')->field('article_id')->where(array('label_id'=>$label['id']))->select();
			$article_ids = array();
			if($relations){
				foreach($relations as $relation){
					if(!in_array($relation['article_id'],$article_ids)){
						$article_ids[] = $relation['article_id'];
					}
				}
			}

			$articles = array();
			if($article_ids){
				$articles = D('Portal_article')->where(array('aid'=>array('in',$article_ids),'status'=>1))->order('aid desc')->limit(0,10)->select();
			}
			$labels[$key]['article_list'] = $articles;
		}

		// 特别推荐
		$recommend_list = D('Portal_article')->where(array('recommend'=>1,'status'=>1,'thumb'=>array('neq','./tpl/System/Static/images/addimg.jpg')))->order('dateline desc')->limit('0,10')->select();

		// 本周热点排行
		$hot_news = D('Portal_article')->where(array('status'=>1,'dateline'=>array('between',array(time()-604800,time()))))->order('PV desc')->limit(0,10)->select();

		// 精彩图文
		$hot_img_news = D('Portal_article')->where(array('status'=>1,'thumb'=>array('neq','./tpl/System/Static/images/addimg.jpg')))->order('PV desc')->limit(0,10)->select();

		// 楼市快讯
		// $this->cur_city = $this->getcity();
		// $building_articles = D('Fc_news_main')->field('news_id,title,visit')->where(array('city_id'=>$this->cur_city['area_id'],'is_display'=>1))->limit('0,10')->select();
		
		// 最新资讯
		$news_article = D('Portal_article')->where(array('status'=>1))->order('dateline desc')->limit(0,10)->select();

		// 资讯分类
		$cate_list = D('Portal_article_cat')->where(array('cate_status'=>1,'fcid'=>0))->select();
		$this->assign('cate_list',$cate_list);

		$this->assign('labels',$labels);
		$this->assign('recommend_list',$recommend_list);
		$this->assign('hot_news',$hot_news);
		$this->assign('hot_img_news',$hot_img_news);
		$this->assign('news_article',$news_article);
		$this->display();
	}

	// 文章列表
	public function lists(){
		
		// 分类信息
		$fcid_info = $cid_info = array();

		$where['status'] = 1;
		$fcid = (int)$_GET['fcid'];	// 一级分类
		$cid = (int)$_GET['cid'];	// 二级分类
		$keyword = trim($_GET['keyword']);
		if($fcid){
			$where['fcid'] = $fcid;
			$fcid_info = D('Portal_article_cat')->where(array('cid'=>$fcid,'fcid'=>0))->find();
		}
		if($cid){
			$where['cid'] = $cid;
			$cid_info = D('Portal_article_cat')->where(array('cid'=>$cid,'fcid'=>array('neq',0)))->find();
			$fcid = $cid_info['fcid'];
		}
		if($keyword){
			$keyword = htmlspecialchars($keyword);
			$where['title'] = array('like','%'.$keyword.'%');
		}

		$count = D('Portal_article')->where($where)->count();
        import('@.ORG.page');
        $p = new Page($count,15);
		$article_list = D('Portal_article')->where($where)->order('status asc,dateline desc')->limit($p->firstRow.','.$p->listRows)->select();
        $pagebar = $p->show();

        // 精彩图文
		$hot_img_news = $this->get_wonderful_img_news();

		// 资讯分类
		$tmp_article_cates = D('Portal_article_cat')->where(array('cat_status'=>1))->select();
		$article_cates = array();
		foreach($tmp_article_cates as $tmp){
			if($tmp['fcid'] == 0){
				$article_cates[] = $tmp;
			}
		}

		foreach($article_cates as $key => $cate){
			foreach($tmp_article_cates as $item){
				if($cate['cid'] == $item['fcid']){
					$article_cates[$key]['childs'][] = $item;
				}
			}
		}


        $this->assign('article_list',$article_list);
        $this->assign('hot_img_news',$hot_img_news);
        $this->assign('article_cates',$article_cates);
        $this->assign('pagebar',$pagebar);
        $this->assign('fcid_info',$fcid_info);
        $this->assign('cid_info',$cid_info);
        $this->assign('fcid',$fcid);
        $this->assign('cid',$cid);
		$this->display();
	}

	// 文章详情
	public function detail(){
		$aid = (int)$_GET['aid'];
		$article = D('Portal_article')->where(array('aid'=>$aid))->find();
		// 精彩图文
		$hot_img_news = $this->get_wonderful_img_news();

		// 资讯评论
		$recomment_list = M()->table(array(C('DB_PREFIX').'user'=>'u',C('DB_PREFIX').'portal_recomment'=>'r'))->where('r.target_id='.$aid.' and r.type=0'.' and u.uid=r.uid')->field('u.avatar,u.nickname,r.*')->order('r.dateline asc')->select();
		$fcid_info = D('Portal_article_cat')->where(array('cid'=>$article['fcid'],'fcid'=>0))->find();
		$cid_info = D('Portal_article_cat')->where(array('cid'=>$article['cid'],'fcid'=>array('neq',0)))->find();

		D('Portal_article')->where(array('aid'=>$aid))->setInc('PV');
		//查询来源
		$source_list = D('Portal_article_source')->select();
		if ($source_list) {
			foreach ($source_list as $_k => $_v) {
				if ($_v['id'] == $article['source_id']) {
					$article['source_name'] = $_v['title'];
				}
			}
		}
		$article['source_name'] = $article['source_name'] ? $article['source_name'] : '本站';
		$this->assign('recomment_list',$recomment_list);
		$this->assign('article',$article);
		$this->assign('fcid_info',$fcid_info);
		$this->assign('cid_info',$cid_info);
		$this->assign('hot_img_news',$hot_img_news);
		$this->display();
	}

	// 精彩图文
	private function get_wonderful_img_news(){
		$res = D('Portal_article')->where(array('status'=>1,'thumb'=>array('neq','./tpl/System/Static/images/addimg.jpg'),'dateline'=>array('between',array(time()-604800,time()))))->order('PV desc')->limit(0,5)->select();
		return $res;
	}

	// 用户点赞
	public function setarticle(){
		$aid = (int)$_POST['aid'];
		$uid = (int)$_SESSION['user']['uid'];
		if($uid <= 0){
			exit(json_encode(array('code'=>2,'msg'=>'您还未登录，请先登录')));
		}
		$res = D('Portal_article')->where(array('aid'=>$aid))->setInc('zan');
		if(!$res){
			exit(json_encode(array('code'=>1,'msg'=>'点赞失败')));
		}
		exit(json_encode(array('code'=>0,'msg'=>'点赞成功')));
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
}