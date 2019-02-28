<?php
/*
 * 用户推广
 *
 */
class SpreadAction extends BaseAction {
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

		//推广码
		if($this->config['open_score_fenrun']){
			if(empty($this->now_user['spread_code'])){
				$spread_code = $this->make_password();
				M('User')->where(array('uid'=>$this->now_user['uid']))->setField('spread_code',$spread_code);
			}else{
				$spread_code = $this->now_user['spread_code'];
			}
			$this->assign('spread_code',$spread_code);
		}
		//待结算订单
		$un_spread_list = D('User_spread_list')->field(true)->where(array('uid'=>$this->user_session['uid'],'status'=>'0'))->order('`pigcms_id` DESC')->select();
		if($un_spread_list){
			foreach($un_spread_list as $key=>$value){
				if($value['order_type'] == 'group'){
					$order_info = $un_spread_list[$key]['order_info'] = D('Group_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					if($order_info['status'] == 0){
						unset($un_spread_list[$key]);
						continue;
					}
					$value['group_info'] = $un_spread_list[$key]['group_info'] = D('Group')->field('`group_id`,`name`')->where(array('group_id'=>$value['third_id']))->find();
				}
				
				if($value['spread_uid']){
					$value['spread_user'] = $un_spread_list[$key]['spread_user'] = D('User')->get_user($value['spread_uid']);
				}
				$value['get_user'] = $un_spread_list[$key]['get_user'] = D('User')->get_user($value['get_uid']);
				//组成描述语句
				if($value['spread_user']){
					$un_spread_list[$key]['desc']['txt'] = '子用户 '.$value['spread_user']['nickname'].' 推广用户 '.$value['get_user']['nickname'].' 购买';
				}else{
					$un_spread_list[$key]['desc']['txt'] = '推广用户 '.$value['get_user']['nickname'].' 购买';
				}
				
				if($value['order_type'] == 'group'){
					$un_spread_list[$key]['desc']['url'] = $this->config['site_url'].'/group/'.$value['group_info']['group_id'].'.html';
					$un_spread_list[$key]['desc']['info'] = $value['group_info']['name'];
				}
			}
		}
		$this->assign('un_spread_list',$un_spread_list);
		
		import('@.ORG.user_page');
		$count = D('User_spread_list')->where(array('uid'=>$this->user_session['uid']))->count();
		$p = new Page($count,20);
		$spread_list = D('User_spread_list')->field(true)->where(array('uid'=>$this->user_session['uid']))->order('`pigcms_id` DESC')->limit($p->firstRow.',20')->select();
		foreach($spread_list as $key=>$value){
			if($value['spread_uid']){
				$value['spread_user'] = $spread_list[$key]['spread_user'] = D('User')->get_user($value['spread_uid']);
			}
			$value['get_user'] = $spread_list[$key]['get_user'] = D('User')->get_user($value['get_uid']);
			
			if($value['order_type'] == 'group'){
				$value['group_info'] = $spread_list[$key]['group_info'] = D('Group')->field('`group_id`,`name`')->where(array('group_id'=>$value['third_id']))->find();
				if($value['status'] == 0){
					$value['order_info'] = $spread_list[$key]['order_info'] = D('Group_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
				}
			}
			//组成描述语句
			if($value['spread_user']){
				$spread_list[$key]['desc']['txt'] = '子用户 '.$value['spread_user']['nickname'].' 推广用户 '.$value['get_user']['nickname'].' 购买';
			}else{
				$spread_list[$key]['desc']['txt'] = '推广用户 '.$value['get_user']['nickname'].' 购买';
			}
			
			if($value['order_type'] == 'group'){
				$spread_list[$key]['desc']['url'] = $this->config['site_url'].'/group/'.$value['group_info']['group_id'].'.html';
				$spread_list[$key]['desc']['info'] = $value['group_info']['name'];
			}
		}
		$this->assign('spread_list',$spread_list);
		$this->assign('pagebar',$p->show());
		
		$this->display();
    }
	public function check(){
		$now_spread = D('User_spread_list')->where(array('uid'=>$this->user_session['uid'],'pigcms_id'=>$_GET['id']))->find();
		if($now_spread && $now_spread['status'] == 0){
			if($now_spread['order_type'] == 'group'){
				$order_info = D('Group_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();
				if($order_info['status'] == '1' || $order_info['status'] == '2'){
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>1))->save()){
						D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['group_alias_name'].'商品获得佣金');
						$this->success('结算完成');
					}else{
						$this->error('操作失败');
					}
				}else if($order_info['status'] == '3'){
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>2))->save()){
						$this->success('用户已退款');
					}else{
						$this->error('操作失败');
					}
				}
			}
		}
	}

	function make_password( $length = 8)
	{
		$str = substr(md5(time()), 0, 6);
		if(!M('User')->where(array('spread_code'=>$str))->find()){
			return $str;
		}else{
			return  $this->make_password();
		}
	}
}