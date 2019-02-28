<?php
/*
 * 社区新闻
 *
 */
class NewsAction extends BaseAction{
	protected $village_id;
	protected $village;

	public function _initialize(){
		parent::_initialize();

		$this->village_id = $this->house_session['village_id'];
		$this->village = D('House_village')->where(array('village_id'=>$this->village_id))->find();
		if(empty($this->village)){
			$this->error('该小区不存在！');
		}
		if($this->village['status'] == 0){
			$this->assign('jumpUrl',U('Index/config'));
			$this->error('您需要先完善信息才能继续操作');
		}
	}

	// 所有新闻列表
	public function index(){
        //新闻列表-查看 权限
        if (!in_array(158, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$condition['village_id'] = $this->village_id;
		$news_list = D('House_village_news')->getlist($condition);

		$this->assign('news_list',$news_list);
		$this->display();
	}

	public function news_edit(){
		$news_id = $_GET['news_id'];

		// 所有新闻的分类
		$condition['village_id'] = $this->village_id;
		$condition['cat_status'] = 1;
		$news_categorys = D('House_village_news_category')->order('cat_sort DESC')->where($condition)->select();

		if(count($news_categorys) < 1){
			$this->error('您还未添加新闻分类，请先去添加新闻分类',U('News/cate_edit'));
		}
		$this->assign('news_categorys',$news_categorys);

		if($news_id){
			$condition_village['village_id'] = $this->village_id;
			$condition_village['news_id'] = $news_id;
			//$condition_village['status'] = 1;
			$news_info = D('House_village_news')->where($condition_village)->find();
			if(empty($news_info)){
				$this->error('暂无此新闻');
			}
			$this->assign('news_info',$news_info);
		}

		$this->display();
	}

	public function news_del(){
        //新闻列表-删除 权限
        if (!in_array(161, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$news_id = $_GET['news_id'] + 0;
		if(!$news_id){
			$this->error('传递参数有误！~~~');
		}

		$database_house_village_news = D('House_village_news');

		$news_where['news_id'] = $news_id;
		$insert_id = $database_house_village_news->where($news_where)->delete();
		if($insert_id){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}

	public function news_edit_do(){
		$news_id = $_POST['news_id'];
		if(IS_POST){

			if(!trim($_POST['title'])){
				$this->error('标题必填！');
			}
			if(empty($_POST['cat_id'])){
				$this->error('分类必选！');
			}
			if(empty($_POST['description'])){
				$this->error('内容必填！');
			}
			$_POST['is_hot'] = intval($_POST['is_hot']);
			$_POST['status'] = intval($_POST['status']);
			$_POST['cat_id'] = intval($_POST['cat_id']);
			$_POST['content'] = $_POST['description'];
			$_POST['village_id'] = $this->village_id;
			unset($_POST['description']);
			if($news_id){
		        //新闻列表-编辑 权限
		        if (!in_array(160, $this->house_session['menus'])) {
		            $this->error('对不起，您没有权限执行此操作');
		        }

				$condition_village['village_id'] = $this->village_id;
				$condition_village['news_id'] = $news_id;
				//$condition_village['status'] = 1;
				$news_info = D('House_village_news')->where($condition_village)->find();
				if(empty($news_info)){
					$this->error('暂无此新闻');
				}
				$cate = D('House_village_news_category')->where(array('status'=>1,'cat_id'=>$_POST['cat_id'],'village_id'=>$this->village_id))->find();
				if(empty($cate)){
					$this->error('暂无此分类');
				}

				$result = D('House_village_news')->where($condition_village)->data($_POST)->save();
				if($result !== false){
					$this->success('修改成功！');
				}else{
					$this->error('修改失败！请重试。');
				}
			}else{
		        //新闻列表-添加 权限
		        if (!in_array(159, $this->house_session['menus'])) {
		            $this->error('对不起，您没有权限执行此操作');
		        }

				$_POST['add_time'] = $_SERVER['REQUEST_TIME'];

				$result = D('House_village_news')->data($_POST)->add();
				if($result !== false){
					$this->success('添加成功！',U('News/index'));
				}else{
					$this->error('添加失败！请重试。');
				}
			}
		}
	}
	public function send_over(){
		$this->display();
	}
	// 微信群发
	public function send(){
		$news_id = $_GET['news_id'];
        //新闻列表-微信群发 权限
        if (!in_array(162, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
		if($news_id){
			$condition_village['village_id'] = $this->village_id;
			$condition_village['news_id'] = $news_id;
			$condition_village['status'] = 1;
			$news_info = D('House_village_news')->where($condition_village)->find();
			if(empty($news_info)){
				$this->error('暂无此新闻');
				$this->display();
			}

			// 小区下所有的业主
			$users = D('House_village_user_bind')->get_limit_list_open($this->village_id);
			if(!$users){
				$this->error('暂无业主',U('News/index'));
			}
			$page = $_GET['page']?intval($_GET['page']):1;

			if($page > $users['totalPage']){
				$users = D('House_village_news')->where(array('news_id'=>$news_id))->save(array('is_notice'=>1));
				$this->assign('over',1);
				$this->display();
				//
			}else{
				// 模板消息
				foreach ($users['user_list'] as $userInfo){
					$href = C('config.site_url').'/wap.php?g=Wap&c=House&a=village_news&village_id='.$this->village_id.'&news_id='.$news_id;

					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));

					$model->sendTempMsg('OPENTM203574543', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，社区发布了一条新的消息\n', 'keyword1' => $news_info['title'], 'keyword2' => date('H时i分',$_SERVER['REQUEST_TIME']), 'keyword3' => '点击查看', 'remark' => '\n请点击查看详细信息！'));

					// 新增模板消息（消息发送状态提醒）
					$model->sendTempMsg('OPENTM405462911', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，'.$this->house_session['village_name'].'发布了一条新的消息【'.$news_info['title'].'】\n', 'keyword1' => '社区新闻', 'keyword2' =>'已发送','keyword3' => date('H时i分',$_SERVER['REQUEST_TIME']), 'keyword4' => $userInfo['nickname'], 'remark' => '\n请点击查看详细信息！'));
				}
				D('House_village_news')->where(array('news_id'=>$news_id))->save(array('is_notice'=>1));
				$this->success('发送完毕，正在跳转下一页'.$_GET['page'],U('News/send',array('news_id'=>$news_id,'page'=>$page+1)));
			}
		}
	}

	// 新闻分类
	public function cate(){
        //新闻分类-查看 权限
        if (!in_array(155, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$condition['village_id'] = $this->village_id;
		$news_category = D('House_village_news_category')->where($condition)->order('cat_sort DESC,cat_status DESC')->select();

		$this->assign('news_category',$news_category);
		$this->display();
	}

	// 新闻分类
	public function cate_edit(){
		$cate_id = intval($_GET['cat_id']);
		if($cate_id){

			$condition['village_id'] = $this->village_id;
			$condition['cat_id'] = $_GET['cat_id'];
			$news_category = D('House_village_news_category')->where($condition)->find();
			if(!$news_category){
				$this->error('此分类不存在');
			}

			$this->assign('cate_id',$cate_id);
			$this->assign('cate_info',$news_category);

		}
		$this->display();
	}

	// 新闻分类
	public function cate_edit_do(){
		$cate_id = intval($_POST['c_id']);
		if($cate_id){
	        //新闻分类-编辑 权限
	        if (!in_array(157, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');
	        }

			$condition['village_id'] = $this->village_id;
			$condition['cat_id'] = $cate_id;
			$news_category = D('House_village_news_category')->where($condition)->find();
			if(!$news_category){
				$this->error('此分类不存在');
			}			

			if(!trim($_POST['cat_name'])){
				$this->error('分类名称必填！');
			}

			$data['cat_name'] = $_POST['cat_name'];
			$data['cat_sort'] = $_POST['cat_sort'];
			$data['cat_status'] = intval($_POST['cat_status']);
			$result = D('House_village_news_category')->where($condition)->save($data);

			if($result !== false){
				$this->success('修改成功！',U('News/cate'));
			}else{
				$this->error('修改失败！请重试。');
			}
		}else{
			// 添加
	        //新闻分类-添加 权限
	        if (!in_array(156, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');
	        }

			if(!trim($_POST['cat_name'])){
				$this->error('分类名称必填！');
			}

			$data['cat_name'] = $_POST['cat_name'];
			$data['cat_sort'] = intval($_POST['cat_sort']);
			$data['cat_status'] = intval($_POST['cat_status']);
			$data['village_id'] = $this->village_id;

			$result = D('House_village_news_category')->data($data)->add();

			if($result !== false){
				$this->success('添加成功！',U('News/cate'));
			}else{
				$this->error('添加失败！请重试。');
			}
		}

		$this->assign('news_category',$news_category);
	}

	//新闻评论列表
	public function reply(){
        //新闻评论列表-查看 权限
        if (!in_array(122, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$condition['village_id'] = $this->village_id;
		$news = D('House_village_news_reply')->getlist($condition);
        $is_check =  D('House_village_config')->where($condition)->getField('village_news_is_need_check');

        $this->assign('is_check',$is_check);
		$this->assign('news',$news);
		$this->display();
	}

	public function read(){
        //已读 权限
        if (!in_array(124, $this->house_session['menus'])) {
            $this->ajaxReturn(array('status'=>0,'msg'=>'对不起，您没有权限执行此操作'));
        }

		if(IS_AJAX && $_POST['cmsid']){
			$condition['village_id'] = $this->village_id;
			$condition['is_read'] = 0;
			$condition['pigcms_id'] = $_POST['cmsid'];

			$data['is_read'] = 1;
			$result = D('House_village_news_reply')->where($condition)->data($data)->save();

			if($result){
				$this->ajaxReturn(array('status'=>1,'msg'=>'已读'));
			}

			$this->ajaxReturn(array('status'=>0,'msg'=>'失败'));
		}
	}
    //删除评论
    public function reply_del(){
        //删除 权限
        if (!in_array(125, $this->house_session['menus'])) {
            $this->ajaxReturn(array('status'=>0,'msg'=>'对不起，您没有权限执行此操作'));
        }

        if(IS_AJAX && $_POST['cmsid']){
            $condition['village_id'] = $this->village_id;

            $condition['pigcms_id'] = $_POST['cmsid'];

            $result = D('House_village_news_reply')->where($condition)->delete();

            if($result){
                $this->ajaxReturn(array('status'=>1,'msg'=>'已删除'));
            }

            $this->ajaxReturn(array('status'=>0,'msg'=>'删除失败'));
        }
    }
    //评论显示隐藏
    public function change_status()
    {
        //前台是否显示 权限
        if (!in_array(126, $this->house_session['menus'])) {
            exit('对不起，您没有权限执行此操作');
        }

        $status = $_POST['type'] == 'open' ? '1' : '2';
        if (D('House_village_news_reply')->where(array('pigcms_id' => $_POST['id']))->save(array('status' => $status))) {
            exit('1');
        } else {
            exit;
        }
    }
    //是否需要审核
    public function is_check()
    {
        //开启关闭评论审核 权限
        if (!in_array(123, $this->house_session['menus'])) {
            exit('对不起，您没有权限执行此操作');
        }

        $status = $_POST['type'] == 'open' ? '1' : '2';
        if (D('House_village_config')->where(array('village_id' => $this->village_id))->save(array('village_news_is_need_check' => $status))) {
            exit('1');
        } else {
            exit('操作失败');
        }
    }

}