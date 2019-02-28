<?php
class Meal_listAction extends BaseAction{
    public function index(){
//        $store_type =   I('store_type',1);
        $area_url =   I('area_url','all');
        $cat_url =   I('cat_url','all');
        $_GET['page']   =   I('page');
        //所有区域
        $all_area_lists = D('Area')->get_all_area_list();
        foreach($all_area_lists as $k => $v){
            $all_area_list[]    =   $v;
        }
        $circle_id = 0;
        if($area_url != 'all'){
            $tmp_area = D('Area')->get_area_by_areaUrl($area_url);
            if(empty($tmp_area)){
                $this->returnCode('20045008');
            }

            if($tmp_area['area_type'] == 3){
                $now_area = $tmp_area;
            } else {
                $now_circle = $tmp_area;
                $now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
                if (empty($tmp_area)) {
                    $this->returnCode('20045008');
                }
                $circle_url = $now_circle['area_url'];
                $circle_id = $now_circle['area_id'];
                $area_url = $now_area['area_url'];
            }
            $area_id = $now_area['area_id'];
        }else{
            $area_id = 0;
        }
        $sort_id =   I('sort_id','juli');
        $long_lat   =   $this->user_long_lat;
        if(empty($long_lat)){
            $sort_id = $sort_id == 'juli' ? 'store_id' : $sort_id;
            $sort_array = array(
                array('sort_id'=>'store_id','sort_value'=>'默认排序'),
                array('sort_id'=>'hot','sort_value'=>'人气最高'),
                array('sort_id'=>'price-asc','sort_value'=>'价格最低'),
                array('sort_id'=>'price-desc','sort_value'=>'价格最高'),
                array('sort_id'=>'time','sort_value'=>'最近开业'),
            );
        } else {
            $sort_array = array(
                array('sort_id'=>'juli','sort_value'=>'离我最近'),
                array('sort_id'=>'hot','sort_value'=>'人气最高'),
                array('sort_id'=>'price-asc','sort_value'=>'价格最低'),
                array('sort_id'=>'price-desc','sort_value'=>'价格最高'),
                array('sort_id'=>'time','sort_value'=>'最近开业'),
            );
        }
        foreach($sort_array as $key=>$value){
            if($sort_id == $value['sort_id']){
                $now_sort_array = $value;
                break;
            }
        }
        $cat_id = 0;
        if($cat_url != 'all'){
            $now_category = D('Meal_store_category')->get_category_by_catUrl($cat_url);
            if (empty($now_category)) {
                $this->returnCode('20045009');
            }
            $this->assign('now_category',$now_category);

            if (!empty($now_category['cat_fid'])) {
                $f_category = D('Meal_store_category')->get_category_by_id($now_category['cat_fid']);
                $all_category_url = $f_category['cat_url'];

                $this->assign('top_category',$f_category);

                $cat_fid = $now_category['cat_fid'];
                $cat_id = $now_category['cat_id'];
            } else {
                $this->assign('top_category',$now_category);

                $cat_id = 0;
                $cat_fid = $now_category['cat_id'];
            }
        }
        $category_list = D('Meal_store_category')->get_all_category();
        foreach($category_list as $k=>$v){
            foreach($v['category_list'] as $kk => $vv){
                $v['one_category_list'][] =   $vv;
            }
            unset($v['category_list']);
            $all_category_list[]    =   $v;
        }
        C('config.meal_page_row',10);
        $arr['merchant_store']    =   D('Merchant_store')->get_list_by_option($area_id, $circle_id, $sort_id, $long_lat['lat'], $long_lat['long'], $cat_id, $cat_fid, null);
        foreach($arr['merchant_store']['meal_list'] as $k => $v){
            $arr['merchant_store']['meal_list'][$k]['wap_url']   =   $this->config['site_url'].$v['wap_url'];
        }
        if(empty($arr['merchant_store'])){
            $arr['merchant_store']  =   array();
        }
        $this->returnCode(0,$arr);
    }
    public function indexList(){
//        $store_type =   I('store_type',1);
        $area_url =   I('area_url','all');
        $cat_url =   I('cat_url','all');
//        $arr['store_type']    =   isset($store_type)?$store_type:null;
        //所有区域
        $all_area_lists = D('Area')->get_all_area_list();
        foreach($all_area_lists as $k => $v){
            $all_area_list[]    =   $v;
        }
        if($all_area_list){
			foreach($all_area_list as $k=>$v){
				$all_area[$k]	=	array(
					'area_id'	=>	$v['area_id'],
					'area_name'	=>	$v['area_name'],
					'area_url'	=>	$v['area_url'],
					'cat_count'	=>	$v['cat_count'],
				);
				foreach($v['area_list'] as $kk=>$vv){
					$all_area[$k]['area_list'][]	=	array(
						'area_id'	=>	$vv['area_id'],
						'area_name'	=>	$vv['area_name'],
						'area_url'	=>	$vv['area_url'],
					);
				}
			}
        }else{
			$all_area	=	array();
        }
        $arr['all_area_list']    =   $all_area;
        $arr['area_url']    =   isset($area_url)?$area_url:null;
        $arr['cat_url']    =   isset($cat_url)?$cat_url:null;

        $circle_id = 0;
        if($area_url != 'all'){
            $tmp_area = D('Area')->get_area_by_areaUrl($area_url);
            if(empty($tmp_area)){
                $this->returnCode('20045008');
            }

            if($tmp_area['area_type'] == 3){
                $now_area = $tmp_area;
            } else {
                $now_circle = $tmp_area;
                $now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
                if (empty($tmp_area)) {
                    $this->returnCode('20045008');
                }
                $circle_url = $now_circle['area_url'];
                $circle_id = $now_circle['area_id'];
                $area_url = $now_area['area_url'];
            }
            $area_id = $now_area['area_id'];
        }else{
            $area_id = 0;
        }
        $arr['now_area']    =   isset($now_area)?$now_area:array();
        $sort_id =   I('sort_id','juli');

        $long_lat   =   $this->user_long_lat;
        if (empty($long_lat)) {
            $sort_array = array(
                array('sort_id'=>'defaults','sort_value'=>'智能排序'),
                array('sort_id'=>'rating','sort_value'=>'评价最高'),
            );
        } else {
            $sort_array = array(
                array('sort_id'=>'juli', 'sort_value'=>'离我最近'),
                array('sort_id'=>'rating', 'sort_value'=>'评价最高'),
                array('sort_id'=>'defaults', 'sort_value'=>'智能排序'),
            );
            $this->assign('long_lat', $long_lat);
        }
        foreach($sort_array as $key=>$value){
            if($sort_id == $value['sort_id']){
                $now_sort_array = $value;
                break;
            }
        }
        $arr['sort_array']    =   isset($sort_array)?$sort_array:array();
        $arr['now_sort_array']    =   isset($now_sort_array)?$now_sort_array:null;
        $cat_id = 0;
        if($cat_url != 'all'){
            $now_category = D('Meal_store_category')->get_category_by_catUrl($cat_url);
            if (empty($now_category)) {
                $this->returnCode('20045009');
            }

            if (!empty($now_category['cat_fid'])) {
                $f_category = D('Meal_store_category')->get_category_by_id($now_category['cat_fid']);
                $all_category_url = $f_category['cat_url'];

                $cat_fid = $now_category['cat_fid'];
                $cat_id = $now_category['cat_id'];
            } else {
                $cat_id = 0;
                $cat_fid = $now_category['cat_id'];
            }
        }
        $arr['now_category']    =    isset($now_category)?$now_category:array();
        $category_list = D('Meal_store_category')->get_all_category();
        foreach($category_list as $k=>$v){
            foreach($v['category_list'] as $kk => $vv){
                $v['one_category_list'][] =   $vv;
            }
            unset($v['category_list']);
            $all_category_list[]    =   $v;
        }
		if($all_category_list){
			foreach($all_category_list as $k=>$v){
				$all_category[$k]	=	array(
					'cat_id'	=>	$v['cat_id'],
					'cat_name'	=>	$v['cat_name'],
					'cat_url'	=>	$v['cat_url'],
					'cat_count'	=>	$v['cat_count'],
				);
				foreach($v['one_category_list'] as $kk=>$vv){
					$all_category[$k]['one_category_list'][]	=	array(
						'cat_id'	=>	$vv['cat_id'],
						'cat_name'	=>	$vv['cat_name'],
						'cat_url'	=>	$vv['cat_url'],
					);
				}
			}
        }else{
			$all_category	=	array();
        }
        $arr['all_category_list']   =    $all_category;
        $this->returnCode(0,$arr);
    }
}

?>