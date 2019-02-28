<?php
/*
 * 地址
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/12/29 16:09
 * 
 */
class AdressAction extends BaseAction{
    public function index(){
		//导航条
    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
    	$this->assign('web_index_slider',$web_index_slider);
    	
		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12);
    	$this->assign('search_hot_list',$search_hot_list);

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
		
		//地址列表
		$user_adress_list = D('User_adress')->get_adress_list($this->now_user['uid'],false);
		$this->assign('user_adress_list',$user_adress_list);
		
		//省市区
		$province_list = D('Area')->get_arealist_by_areaPid(0);
		$this->assign('province_list',$province_list);
			
		$city_list = D('Area')->get_arealist_by_areaPid($province_list[0]['area_id']);
		$this->assign('city_list',$city_list);
			
		$area_list = D('Area')->get_arealist_by_areaPid($city_list[0]['area_id']);
		$this->assign('area_list',$area_list);
		
		$this->display();
    }
    public function set_default(){
		if(D('User_adress')->set_default($this->now_user['uid'],$_POST['adress_id'])){
			$this->success('设置成功！');
		}else{
			$this->error('设置失败！请重试。');
		}
	}
	public function del_adress(){
		if(D('User_adress')->delete_adress($this->now_user['uid'],$_POST['adress_id'])){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！请重试。');
		}
	}
	public function amend_adress(){
		if(IS_POST){
			if(D('User_adress')->post_form_save($this->user_session['uid'])){
				$this->success('保存成功！');
			}else{
				$this->error('地址保存失败！请重试。');
			}
		}else{
			$this->error('必须是POST提交表单！');
		}
	}
	public function select_area(){
		$area_list = D('Area')->get_arealist_by_areaPid($_POST['pid']);
		if(!empty($area_list)){
			$return['error'] = 0;
			$return['list'] = $area_list;
		}else{
			$return['error'] = 1;
		}
		echo json_encode($return);
	}
}