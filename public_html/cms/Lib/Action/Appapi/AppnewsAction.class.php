<?php
/*
 * 关于我们
 *
 */
class AppnewsAction extends BaseAction {

    public function index(){
        $activity_arr = array();
        $category = D('System_news_category');
        if($category->count()){
            $where['status']=1;
            $category_info = $category->where($where)->order('sort DESC')->select();
            foreach($category_info as $v){
                $activity_arr['category'][] = array(
                    'cat_name'=>$v['name'],
                    'cat_id'=>$v['id']
                );
            }
        }else{
            $activity_arr  = array();
        }
        $this->returnCode(0,$activity_arr);
    }

    public function news(){
        $totalRows=15;    //每页条数
        $condition_cat['status']=1;
        $page = $_POST['page'] ? intval($_POST['page']) : 1;
        if(empty($_POST['cat_id'])){
            $category_info = D('System_news_category')->where($condition_cat)->order('sort DESC')->find();
            $condition_news['category_id']=$category_info['id'];
            $condition_news['status']=1;
            $count =  D('System_news')->field('COUNT(id) as count')->where($condition_news)->find();
            $totalPage = intval($count['count']/$totalRows)+1;
        }else {
            $condition_news['category_id']=$_POST['cat_id'];
            $condition_news['status']=1;
            $count =  D('System_news')->field('COUNT(id) as count')->where($condition_news)->find();
            $totalPage = intval($count['count']/$totalRows)+1;
        }

        if($count['count'] > ($page-1)*$totalRows){
            $news = D('System_news')->field('id,category_id,title,add_time')->where($condition_news)->order('sort DESC')->limit((($page-1)*$totalRows),$totalRows)->select();
            foreach($news as $k=>$v_news){
                $v_news['url']=$this->config['site_url'].'/wap.php?g=Wap&c=Systemnews&a=news&id='.$v_news['id'];
                $activity_arr['news_list'][]=$v_news;
            }
            $activity_arr['totalPage']=$totalPage;
        }else{
            $activity_arr['news_list']=array();
        }
        $this->returnCode(0,$activity_arr);
    }

	
	

}