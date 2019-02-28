<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2016/8/3
 * Time: 10:52
 */

class ArticleAction extends BaseAction {

    /**
     * 类别列表
     */
    public function index(){
        $where = array();
        $name = !empty($_GET['keyword']) ? $_GET['keyword'] : '';
        if($name) {
            $where['name'] = array('like','%' . $name .'%');
        }

        $count = D('FcArticleCategory')->getFcArticleCount($where);
        import('@.ORG.system_page');
        $p = new Page($count,15);
        $categoryList = D('FcArticleCategory')->getFcArticleList($where,$p->firstRow,$p->listRows);

        $this->assign('categoryList',$categoryList);

        $page = $p->show();
        $this->assign('page',$page);
        $this->display();
    }

    public function create(){
        $this->display();
    }

    /**
     * 创建类别
     */
    public function create_data(){

        $data['name'] = $_POST['name'];
        $data['order'] = $_POST['order'];
        $data['status'] = $_POST['status'];
        $data['create_time'] = time();
        $data['update_time'] = time();

        if(D('Fc_article_category')->where(array('name'=>$data['name']))->find()){
            $this->frame_submit_tips(0,'分类名称重复');
        }

        $result = D('Fc_article_category')->data($data)->add();

        if($result){
            $this->frame_submit_tips(1,'添加成功！');
        }else{
            $this->frame_submit_tips(0,'添加失败！请重试~');
        }
    }

    public function edit(){

        $id = intval($_GET['id']);

        $now_category = D('Fc_article_category')->where(array('id'=>$id))->find();
        $this->assign('now_category',$now_category);
        $this->display();
    }

    /**
     * 编辑类别
     */
    public function edit_data(){

        $data['name'] = $_POST['name'];
        $data['order'] = $_POST['order'];
        $data['status'] = $_POST['status'];
        $data['update_time'] = time();
        $id = intval($_POST['id']);

        /*if(D('Fc_article_category')->where(array('name'=>$data['name']))->find()){
            $this->frame_error_tips(0,'分类名称重复');
        }*/

        $result = D('Fc_article_category')->where(array('id'=>$id))->data($data)->save();

        if($result){
            $this->frame_submit_tips(1,'修改成功！');
        }else{
            $this->frame_submit_tips(0,'修改失败！请重试~');
        }
    }

    /**
     * 删除类别
     */
    public function cat_del(){
        $id = intval($_POST['id']);
        $categoryInfo = D('Fc_article_category')->where(array('id'=>$id))->find();
        if($categoryInfo['article_total'] > 0){
            $this->error('请先删除分类下的文章！');
        } else {
            $result = D('Fc_article_category')->where(array('id'=>$id))->delete();

            if($result){
                $this->success('删除成功！');
            }else{
                $this->error('删除失败');
            }
        }
    }

    /**
     * 文章列表
     */
    public function article_list(){

        $type = $_GET['type'];
        $where = array();
        if($type == 'article_title'){
            $where['a.article_title'] = array('like','%'.$_GET['keyword'].'%');
        } else if($type == 'nickname'){
            $where['u.nickname'] = array('like','%'.$_GET['keyword'].'%');
        }
        $count = D('FcArticle')->getArticleCount($where);

        import('@.ORG.system_page');
        $p = new Page($count,15);

        $articleList = D('FcArticle')->getArticleList($where,$p->firstRow,$p->listRows);

        $this->assign('articleList',$articleList);

        $page = $p->show();
        $this->assign('page',$page);
        $this->display();
    }

    /**
     * 文章详情
     */
    public function aricle_list_details(){

        $id = intval($_GET['id']);
        $info = D("Fc_article")->where(array('id'=>$id))->find();
        //var_dump($info['article_content']);
        $userInfo = D('User')->field('nickname,uid')->where(array('uid'=>$info['uid']))->find();
        $categoryInfo = D('Fc_article_category')->field('name')->where(array('id'=>$info['cat_id']))->find();
        $info['nickname'] = $userInfo['nickname'];
        $info['cat_name'] = $categoryInfo['name'];
        $info['article_content'] = str_replace('\"','\'',strip_tags($info['article_content'],'<img>'));

        $this->assign('info', $info);
        $this->display();
    }

    /**
     * 文章删除
     */
    public function article_del(){

        $id = intval($_GET['id']);
        $articleInfo = D('Fc_article')->where(array('id'=>$id))->find();
        if($articleInfo['article_comment_total'] > 0){
            if(D('Fc_comment')->where(array('article_id'=>$articleInfo['id']))->delete()){
                $result = D('Fc_article')->where(array('id'=>$id))->delete();
            }
        } else {
            $result = D('Fc_article')->where(array('id'=>$id))->delete();
        }

        if($result){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 评论列表
     */
    public function comment_list(){

        $article_id = $_GET['article_id'];
        $where = array();
        if($article_id){
            $where['article_id'] = $article_id;
        }
        $count = D('Fc_comment')->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count,15);
        $commentList = D('Fc_comment')->limit($p->firstRow.','.$p->listRows)->where($where)->select();

        foreach($commentList as &$list){
            $articleInfo = D('Fc_article')->field('article_title')->where(array('id'=>$list['article_id']))->find();
            $userInfo = D('User')->field('avatar,nickname')->where(array('uid'=>$list['uid']))->find();
            $list['article_title'] = $articleInfo['article_title'];
            $list['nickname'] = $userInfo['nickname'];
            $list['avatar'] = $userInfo['avatar'];
        }

        $this->assign('commentList',$commentList);

        $page = $p->show();
        $this->assign('page',$page);
        $this->display();
    }

    /**
     * 查看评论
     */
    public function comment_edit(){

        $id = intval($_GET['id']);
        $info = D("Fc_comment")->where(array('id'=>$id))->find();
        $articleInfo = D('Fc_article')->field('article_title,id')->where(array('id'=>$info['article_id']))->find();
        $userInfo = D('User')->field('nickname,uid')->where(array('uid'=>$info['uid']))->find();
        $info['article_name'] = $articleInfo['article_title'];
        $info['nickname'] = $userInfo['nickname'];
        $this->assign('info', $info);
        $this->display();
    }

    /**
     * 评论删除
     */
    public function comment_delete(){
        $id = intval($_POST['id']);

        if(D("Fc_comment")->where(array('id'=>$id))->delete()){
                $this->success('删除成功！');
        }else{
            $this->error('删除失败');
        }
    }
	public function diyVideo()
	{
		$this->display();
	}
}