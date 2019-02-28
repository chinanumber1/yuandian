<?php
/**
* 商家板块
*/
class CompanyAction extends BaseAction {
	public function _initialize(){
		parent::_initialize();
		$this->assign('portal_name','商家列表');
	}
	public function index(){
		// 店铺分类
		$all_shop_category = D('Shop_category')->lists();
		// 店铺列表
		$pageSize = 15;
		$where['p'] = max(1,(int)$_GET['p']);
		$where['pagesize'] = $pageSize;

		$pid = (int)$_GET['pid'];
		if($pid){
			$where['cat_fid'] = $pid;
		}
		$cid = (int)$_GET['cid'];
		if($cid){
			$where['cat_id'] = $cid;
		}
		$area_id = (int)$_GET['area_id'];
		if($area_id){
			$where['area_id'] = $area_id;
			$this->assign('area_id',$area_id);
		}
		$order_flag = (int)$_GET['order_flag'];
		if($order_flag){
			switch ($order_flag) {
				case 1:
					$where['order'] = 'permoney';
					break;
				case 2:
					$where['order'] = 'score_mean';
					break;
			}
			$this->assign('order_flag',$order_flag);
		}
		$is_verify = $_GET['isverify'];
		if(empty($is_verify)){
			$is_verify = -1;
		}

		$title = trim($_GET['wd']);
		if($title){
			$where['key'] = $title;
		}

		import('@.ORG.page');
		$shop_list = D('Merchant_store_shop')->getStores($where,$is_verify);
		$p = new Page($shop_list['total'],$pageSize);
        $pagebar = $p->show();

		
		//区域列表
		$area_list = D('Area')->get_area_list();
		//exit(dump($area_list));
		if($pid==0){
			$pid = (int)$_GET['parent_id'];
		}
		$this->assign('all_shop_category',$all_shop_category);
		$this->assign('shop_list',$shop_list['shop_list']);
		$this->assign('shop_total',$shop_list['total']);
		$this->assign('area_list',$area_list);
		$this->assign('pagebar',$pagebar);
		$this->assign('pid',$pid);
		$this->assign('cid',$cid);
		$this->display();
	}

	public function detail(){

		$this->display();
	}
}