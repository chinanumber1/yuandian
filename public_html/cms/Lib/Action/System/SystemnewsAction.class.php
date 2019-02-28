<?php
	class SystemnewsAction extends BaseAction{
		public function index(){
			$news = D('System_news')->select();
			$this->assign("news",$news[0]);
			$category = D('System_news_category')->order('sort DESC')->select();
			$this->assign("category",$category);
			$this->display();
		}
		public function news(){
			if (!empty($_GET['keyword'])) {
				if ($_GET['searchtype'] == 'id') {
					$condition_news['id'] = $_GET['keyword'];
				} else if ($_GET['searchtype'] == 'title') {
					$condition_news['title'] = array('like', '%' . $_GET['keyword'] . '%');
				}
			}
			$condition_news['category_id'] = $_GET['category_id'];
			//排序 /*/
			$order_string = '`sort` DESC,`id` DESC';
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
			$news = M('System_news');
			$count_news = $news->where($condition_news)->count();
			import('@.ORG.system_page');
			$p = new Page($count_news, 15);
			$news_list = $news->field('id,title,add_time,last_time,sort,status')->where($condition_news)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();
			$this->assign('news_list',$news_list);
			$pagebar = $p->show();
			$category_name = D('System_news_category')->where('id='.$_GET['category_id'])->getField('name');
			$this->assign('category_name',$category_name);
			$this->assign('pagebar', $pagebar);
			$this->display();
		}

		public function add_news(){
			if(IS_POST){

				$data['title'] = $_POST['title'];
				if(empty($_POST['content'])){
					$this->error('内容不能为空！');
				}
				$data['content'] = htmlspecialchars_decode($_POST['content']);
				$data['sort'] = $_POST['sort'];
				$data['status'] = $_POST['status'];
				$data['category_id'] = $_POST['category_id'];
				$data['add_time'] = $data['last_time']=time();
				if(D('System_news')->add($data)){
					$this->success('添加公告成功！');
				}else{
					$this->error('添加失败！');
				}
			}else {
				$category = D('System_news_category')->select();
				$this->assign("category",$category);
				$this->display();
			}
		}

		public function edit_news(){
			if(IS_POST){
				$data['title'] = $_POST['title'];
				$data['content'] = htmlspecialchars_decode($_POST['content']);
				$data['sort'] = $_POST['sort'];
				$data['category_id'] = $_POST['category_id'];
				$data['status'] = $_POST['status'];
				$data['last_time'] = time();
				if(D('System_news')->where('id='.$_POST['id'])->save($data)){
					$this->success('保存成功！');
				}else{
					$this->error('保存失败！');
				}
			}else {
				$news = D('System_news')->where(array('id'=>$_GET['id']))->find();
				$this->assign("news",$news);
				$category = D('System_news_category')->select();
				$this->assign("category",$category);
				$this->display();
			}
		}
		
		public function add_category(){
			if(IS_POST){
				$data['name'] = $_POST['name'];
				$data['sort'] = $_POST['sort'];
				$data['status'] = $_POST['status'];
				if(D('System_news_category')->add($data)){
					$this->success('添加分类成功！');
				}else{
					$this->error('添加分类失败！');
				}
			}else {
				$this->display();
			}
		}
		
		public function edit_category(){
			if(IS_POST){
				$data['name']=$_POST['name'];
				$data['sort']=$_POST['sort'];
				$data['status']=$_POST['status'];
				if(D('System_news_category')->where(array('id'=>$_POST['id']))->save($data)){
					$this->success('更新成功！');
				}else{
					$this->error('更新失败！');
				}
			}else {
				$category = D('System_news_category')->where(array('id'=>$_GET['id']))->find();
				$this->assign("category",$category);
				$this->display();
			}
		}

		public function del(){
			if(IS_POST){

				if(!empty($_POST['id'])){
					if(D('System_news')->where(array('id'=>$_POST['id']))->delete()){
						$this->success('删除成功');
					}else{
						$this->error('删除失败！');
					}
				}
				if(!empty($_POST['category_id'])){
					if(!D('System_news_category')->where(array('id'=>$_POST['category_id']))->delete()){
						$this->error('删除失败！');
					}else{
						D('System_news')->where(array('category_id'=>$_POST['category_id']))->delete();
						$this->success('删除成功');
					}
				}
			}
		}
	}