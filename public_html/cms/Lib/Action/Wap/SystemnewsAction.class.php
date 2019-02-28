<?php
	class SystemnewsAction extends BaseAction{
		public function index(){
			$news = M('System_news');
			$category = D('System_news_category')->where(array('status'=> 1))->order('sort DESC')->select();
			$condition_news['category_id'] = $category[0]['id'];
			$condition_news['status'] = 1;

			$page = $_GET['page'] ? intval($_GET['page']) : 1;
			$news = M('System_news');
			$return['count'] = $news->where(array('category_id'=> $category[0]['id'],'status'=>'1'))->count();
			if($return['count'] > ($page-1)*20){
				$return['news_list'] = $news->field('`id`,`title`,FROM_UNIXTIME(`add_time`, \'%Y-%m-%d %H:%i:%S\') AS add_time')->where(array('category_id'=> $category[0]['id'],'status'=>'1'))->order('sort DESC,id DESC')->limit((($page-1)*10).',10')->select();
			}else{
				$return['news_list'] = array();
			}
			$return['totalPage'] = ceil($return['count']/10);
			$key = array_search($condition_news['category_id'],$category);
			$this->assign('now_cat',$category[$key]['name']);
			$this->assign("category",$category);
			$this->assign("now_cat_id",$category[0]['id']);
			$this->assign("count",$return['count']);
			$this->assign("news_list",$return['news_list']);
			$this->display();
		}

		public function ajaxList(){
			$this->header_json();
			$totalRows=15;
			$page = $_GET['page'] ? intval($_GET['page']) : 1;
			$news = M('System_news');
			$return['count'] = $news->where(array('category_id'=>$_GET['cat_id'],'status'=>'1'))->count();
			if($return['count'] > ($page-1)*$totalRows){
				$return['news_list'] = $news->field('`id`,`title`,FROM_UNIXTIME(`add_time`, \'%Y年%m月%d日\') AS add_time')->where(array('category_id'=>$_GET['cat_id'],'status'=>'1'))->order('sort DESC,id DESC')->limit((($page-1)*$totalRows),$totalRows)->select();
			}else{
				$return['news_list'] = array();
			}
			$return['totalPage'] = ceil($return['count']/10);
			echo json_encode($return);
		}

		public function news(){
			$news = D('System_news')->where(array('id'=>$_GET['id']))->find();
			$this->assign('news',$news);
			$this->display();
		}

	}