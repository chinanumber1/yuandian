<?php
class SearchAction extends BaseAction{
	public function index()
    {
		//热门搜索词
		$type = I('type') == 'meal' ? 1 : 0;
    	$search_hot_list = D('Search_hot')->get_list(18,$type,true);
        if(empty($search_hot_list)){
            $this->returnCode('20000001');
        }
        foreach($search_hot_list as $k=>$v){
            $search_hot_list[$k]['url'] =   $this->config['site_url'].urldecode($v['url']);
        }
        $this->returnCode(0,$search_hot_list);
	}
	public function group()
    {
        if($this->app_version > 55){
            $this->group_version_55();
            exit();
        }
		$keywords = htmlspecialchars(I('w'));
//        $_GET['page']   =   I('page');
//		$group_category = D('Group_category')->field('`cat_url`')->where(array('cat_name'=>$keywords,'cat_status'=>'1'))->find();
//		if($group_category['cat_url']){
//			redirect(U('Group/index',array('cat_url'=>$group_category['cat_url'],'w'=> urlencode($keywords))));exit;
//		}
		$sort = I('sort','default');
		//得到搜索的团购列表
        C('config.group_page_row',10);
		$group_return = D('Group')->get_group_list_by_keywords($keywords,$sort,true);
        if($group_return['group_list']  == null){
            $this->returnCode('20000003');
        }else{
            foreach($group_return['group_list'] as $key => $arr){
                $group_return['group_list'][$key]['url']  =   $this->config['site_url'].str_replace('appapi.php','wap.php',$arr['url']);
                $group_return['group_list'][$key]['price']   =   rtrim(rtrim(number_format($arr['price'],2,'.',''),'0'),'.');
                $group_return['group_list'][$key]['wx_cheap']   =   rtrim(rtrim(number_format($arr['wx_cheap'],2,'.',''),'0'),'.');
                $group_return['group_list'][$key]['sale_count']   =   $arr['sale_count']+$arr['virtual_num'];
            }
            $this->returnCode(0,$group_return);
        }
	}

    public function check_group_category(){
        $keywords = htmlspecialchars(I('w'));
        $group_category = D('Group_category')->field('`cat_url`')->where(array('cat_name'=>$keywords,'cat_status'=>'1'))->find();
        if($group_category['cat_url']){
            $arr['cat_url'] =$group_category['cat_url'];
            $arr['is_group_category'] =1;
            $this->returnCode(0,$arr);
        }else{
            $arr['is_group_category'] =0;
            $this->returnCode(0,$arr);
        }
    }
    public function group_version_55()
    {
        $keywords = htmlspecialchars(I('w'));
        $_GET['page']   =   I('page');
//        $group_category = D('Group_category')->field('`cat_url`')->where(array('cat_name'=>$keywords,'cat_status'=>'1'))->find();
//        if($group_category['cat_url']){
//            $arr['cat_url'] =$group_category['cat_url'];
//            $arr['is_group_category'] =1;
//            $this->returnCode(1,$arr);
//        }
        $sort = I('sort','default');
        //得到搜索的团购列表
        C('config.group_page_row',10);
        $group_return = D('Group')->get_group_list_by_keywords($keywords,$sort,true);
        if($group_return['group_list']  == null){
            $this->returnCode('20000003');
        }else{
            foreach($group_return['group_list'] as $key => $arr){
                $group_return['group_list'][$key]['url']  =   $this->config['site_url'].str_replace('appapi.php','wap.php',$arr['url']);
                $group_return['group_list'][$key]['price']   =   rtrim(rtrim(number_format($arr['price'],2,'.',''),'0'),'.');
                $group_return['group_list'][$key]['wx_cheap']   =   rtrim(rtrim(number_format($arr['wx_cheap'],2,'.',''),'0'),'.');
            }
            $this->returnCode(0,$group_return);
        }
    }
	
	public function meal()
	{
		$keywords = htmlspecialchars(I('w'));
		$sort = I('sort','default');
        $_GET['page']   =   I('page');
		//得到搜索的店铺列表
        C('config.group_page_row',10);
		$return = D('Merchant_store')->get_list_by_search($keywords, $sort, true);
        if($return['group_list'] == null){
            $this->returnCode('20000001');
        }else{
            foreach($return['group_list'] as $key => $arr){
				$return['group_list'][$key]['mean_money'] = $arr['permoney'];//使用店铺人均消费替换注释了的餐饮人均消费
                $return['group_list'][$key]['url']  =   $this->config['site_url'].str_replace('appapi.php','wap.php',$arr['url']);
            }
            $this->returnCode(0,$return);
        }
	}
	
	public function appoint()
	{
        //搜索关键字
		$keywords = htmlspecialchars(I('w'));
        //排序方式
		$sort = I('sort','default');
        $_GET['page']   =   I('page');
		//得到搜索的店铺列表
        C('config.appoint_page_row',10);
		$return = D('Appoint')->get_list_by_search($keywords, $sort, true);
        if($return['group_list'] == null){
            $this->returnCode('20046003');
        }else{
            foreach($return['group_list'] as $key => $arr){
                $return['group_list'][$key]['url']  =   $this->config['site_url'].str_replace('appapi.php','wap.php',$arr['url']);
                $return['group_list'][$key]['payment_money']   =   rtrim(rtrim(number_format($arr['payment_money'],2,'.',''),'0'),'.');
            }
            $this->returnCode(0,$return);
        }
	}


}
?>