<?php
/*
 *系统新闻系统前台显示
 */
 
 class NewsAction extends BaseAction{
	 public function index(){
            $cate = D('System_news_category');
            $news_cat = $cate->where(array('status'=>1))->order('sort DESC')->select();
            $this->assign('news_cat',$news_cat);
            $cat_id = $_GET['category_id'];

            if($cat_id){
                $where =array('category_id'=>$cat_id,'status'=>1);
                $count_news =  D('System_news')->where($where)->count();
                import('@.ORG.news_page');
                $p = new Page($count_news, 15,'page');
                $news_title = D('System_news')->field('id,title,add_time')->where($where)->order('sort DESC,id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
                $this->assign('news_title',$news_title);
                $now_cat = $cate->where(array('id'=>$cat_id))->find();
                $this->assign('now_cat',$now_cat);
                $pagebar = $p->show();
                $this->assign('pagebar', $pagebar);
            }else if(!empty($_GET['id'])){
                $news = D('System_news')->where(array('id'=>$_GET['id']))->find();
                $now_cat = $cate->where(array('id'=>$news['category_id']))->find();
                $this->assign('now_cat',$now_cat);
                $this->assign('news',$news);
            }else{
                $news = M('System_news');
                $where['n.status'] = 1;
                $count_news = $news->where($where)->count();
                import('@.ORG.news_page');
                $p = new Page($count_news, 15,'page');
                $news_title = $news->field('n.id,n.title,n.add_time,c.name')->join('as n left join '.C('DB_PREFIX').'system_news_category c ON c.id = n.category_id ')->where($where)->order('n.sort DESC,n.id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
                $this->assign('news_title',$news_title);
                $pagebar = $p->show();
                $this->assign('pagebar', $pagebar);
            }
            $this->display();
	 }
 }
