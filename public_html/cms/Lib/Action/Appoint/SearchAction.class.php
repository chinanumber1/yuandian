<?php
/*
 * 预约首页
 *
 */
class SearchAction extends BaseAction{
    public function index(){

        $keywords = htmlspecialchars($_POST['w']);
		if(empty($keywords)){
			$this->error('关键词不能为空');
		}

        $this->assign('keywords',$keywords);

        $sort = empty($_GET['sort']) ? 'default' : $_GET['sort'];

        $this->assign('now_sort',$sort);

        //得到搜索的店铺列表
        $return = D('Appoint')->get_list_by_search($keywords, $sort, true);

        $this->assign($return);
                //评论end
                
		$this->display();
    }


}