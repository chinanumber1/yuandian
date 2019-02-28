<?php
class IndexAction extends BaseAction {
	public function _initialize(){
		parent::_initialize();
		$this->assign('portal_name','门户');
	}
	public function index(){

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);

		//首页大分类下的团购列表
		$index_group_list = D('Group')->get_category_arr_group_list($all_category_list,6);
		$this->assign('index_group_list',$index_group_list);

		// dump($index_group_list);

		$near_shop_list = D("Merchant_store")->get_hot_list(8);
		$this->assign('near_shop_list',$near_shop_list);
		// dump($near_shop_list);

		//顶部广告
		$portal_index_banner_top = D('Adver')->get_adver_by_key('portal_index_banner_top',4);
		$this->assign('portal_index_banner_top',$portal_index_banner_top);
		//轮播广告
		$portal_index_round = D('Adver')->get_adver_by_key('portal_index_round',4);
		$this->assign('portal_index_round',$portal_index_round);
		//顶部广告
		$portal_index_group = D('Adver')->get_adver_by_key('portal_index_group',5);
		$this->assign('portal_index_group_right',$portal_index_group);

		//横幅广告one
		$portal_index_banner_one = D('Adver')->get_adver_by_key('portal_index_banner_one');
		$this->assign('portal_index_banner_one',$portal_index_banner_one);
		//横幅广告two
		$portal_index_store = D('Adver')->get_adver_by_key('portal_index_store',2);
		$this->assign('portal_index_store',$portal_index_store);

		// 房产出售
		$houseSellList = D('Fc_sell_houses')->where($where)->order(array('update_time'=>'desc','price'=>'asc','area'=>'asc'))->limit(9)->select();
		$this->assign('houseSellList',$houseSellList);
		// dump($houseSellList);

		// 房产出租
		$houseRentList = D('Fc_rent_houses')->where($where)->order(array('update_time'=>'desc','price'=>'asc','area'=>'asc'))->limit(9)->select();
		$this->assign('houseRentList',$houseRentList);

		// 最新经纪人列表
		$staffListAdd = D('Fc_staff')->where(array('status'=>1))->order(array('add_time desc'))->limit(6)->select();
		$this->assign('staffListAdd',$staffListAdd);
		// 推荐经纪人列表
		$staffList = D('Fc_staff')->where(array('status'=>1))->order(array('second_house_num desc'))->limit(6)->select();
		$this->assign('staffList',$staffList);
		//精华帖
		$essenceList = D('Portal_tieba')->where(array('is_essence'=>1,'status'=>0,'target_id'=>0))->order('add_time desc')->limit(10)->select();
		$this->assign('essenceList',$essenceList);

		//精华帖
		$newsTieList = D('Portal_tieba')->where(array('target_id'=>0,'status'=>0))->order('add_time desc')->limit(10)->select();
		$this->assign('newsTieList',$newsTieList);

		$activityList = D('Portal_activity')->where(array('status'=>1))->order('a_id desc')->limit(5)->select();
		foreach ($activityList as $k => $v) {
			if($v['enroll_time']<time()){
				$v['state'] = 5;//结束了
			}else if($v['number']<=$v['already_sign_up'] && $v['number']>0){
				$v['state'] = 3;//即将组团
			}else{
				$v['state'] = 2;//招募中
			}
			$activityList[$k] = $v;
		}
		$this->assign('activityList',$activityList);

		//友情链接
		$flink_list = D('Flink')->get_flink_list();
		$this->assign('flink_list',$flink_list);

		// 今日热点
		//$hot_news = D('Portal_article')->where(array('status'=>1))->order('PV desc')->limit('0,8')->select();
		$hot_news = M()->table(array(C('DB_PREFIX').'portal_article'=>'a',C('DB_PREFIX').'portal_article_cat'=>'c'))->where('a.status=1 and a.cid=c.cid')->field('a.*,c.cat_name')->order('a.dateline desc ,a.PV desc')->limit('0,8')->select();
		$this->assign('hot_news',$hot_news);

		// 便民电话
		// $yellowList = D('Portal_yellow')->where(array('status'=>1))->order('top_time desc,status asc,dateline desc')->limit('0,8')->select();
		$yellowList = D('Portal_yellow')->where(array('status'=>1,'city'=>C('config.now_city')))->order('PV desc')->limit('0,8')->select();
		$this->assign('yellowList',$yellowList);
		
		$this->display();
	}
}