<?php
class WaimaiAction extends BaseAction
{
    protected $discount_type;
    public function _initialize() {
        parent::_initialize();
        $this->discount_type = D("Waimai_discount");
    }

    /**
     * 外卖优惠管理
     */
    public function discount() {
        $list = $this->discount_type->getlist();
        $this->assign("list", $list);
        $this->display("discount_list");
    }

    /**
     * 外卖优惠添加
     */
    public function discount_add() {
        if (! IS_POST) {
            $this->display("discount_add");exit;
        }
        if ($_FILES) {
            $icon = $this->uploadFile();
        }
        $name = I('name');
        if (empty($name)) {
            $this->frame_submit_tips(0,'名称不能为空');
        }
        $sort = I('sort', 0);
        $status = I('status', 1);
        $desc = I('desc');

        $columns = array();
        $columns['name'] = $name;
        $columns['desc'] = $desc;
        $columns['sort'] = $sort;
        $columns['status'] = $status;
        $columns['icon'] = $icon;

        $result = $this->discount_type->insert($columns);
        if (!$result) {
            $this->frame_submit_tips(0,'添加失败');
        }

        $this->frame_submit_tips(1,'添加成功');
    }

    /**
     * 外卖优惠方式编辑
     */
    public function discount_edit() {
        if (! IS_POST) {
            $type_id = I("type_id");
            $data = $this->discount_type->find($type_id);
            if (! $data) {
                $this->error("优惠方式不存在");
            }
            $this->assign($data);
            $this->display("discount_edit");exit;
        }
        $type_id = I('type_id');
        if (empty($type_id)) {
            $this->error("type_id不能为空");
        }
        $name = I('name');
        if (empty($name)) {
            $this->error("名称不能为空");
        }
        $sort = I('sort');
        if (empty($sort)) {
            $this->error("排序不能为空");
        }
        $status = I('status');
        if (empty($status)) {
            $this->error("名称不能为空");
        }
        $desc = I('desc');

        $columns = array();
        $columns['name'] = $name;
        $columns['desc'] = $desc;
        $columns['sort'] = $sort;
        $columns['status'] = $status;

        $result = $this->discount_type->edit($type_id, $columns);
        if (!$result) {
            $this->error("修改失败");
        }

        $this->success("修改成功");
    }

    /**
     * 外卖优惠方式删除
     */
    public function discount_del() {
        $type_id = I("type_id");
        if (empty($type_id)) {
            $this->error("type_id不能为空");
        }

        $columns = array();
        $columns['delete'] = 1;

        $result = $this->discount_type->edit($type_id, $columns);
        if (!$result) {
            $this->error("删除失败");
        }

        $this->success("删除成功");
    }
  
    /**
     * 商品分类列表
     */
    public function product_category(){
		
		$keyWord = isset($_GET['keyword'])?$_GET['keyword']:'';
		$searchtype = isset($_GET['searchtype'])?$_GET['searchtype']:'';
		$categoryid = $storeId = $categoryName = $merId = '';
		if(!empty($searchtype)){
			switch ($searchtype){
				case 'gcat_id':
					$categoryid = intval($keyWord);
					break;
				case 'gcat_name':
					$categoryName = $keyWord;
					break;
				case 'storeId':
					$storeId = $keyWord;
					break;
				case 'merId':
					$merName = $keyWord;
					break;
			}
		}
		
    	// 所有的商品列表
    	$columnCondition['merName'] = $merName;
    	$columnCondition['categoryid'] = $categoryid;
    	$columnCondition['categoryName'] = $categoryName;
    	$columnCondition['storeName'] = $storeId;
    	
    	$category = D('Waimai_goods_category')->get_all_category($columnCondition);

    	$this->assign('categoryList',$category);
    	$this->assign('searchtype', $searchtype);
		$this->assign('keyWord', $keyWord);
		
    	$this->display();
    }
    
     /**
     * 商品分类管理
     */
    public function product_category_manage(){
    	$gcat_id = !empty($_GET['gcat_id'])?intval($_GET['gcat_id']):'';
    	$store_id = !empty($_GET['store_id'])?intval($_GET['store_id']):'';
    	if(!empty($gcat_id) && !empty($store_id)){
    		 
    		$categoryResult = D('Waimai_goods_category')->get_category_by_id($gcat_id,$store_id);
    		$categoryDetail = $categoryResult['error_code']?$categoryResult['category']:'';
    		$this->assign('categoryDetail',$categoryDetail);
    		$this->assign('gcat_id',$gcat_id);
    		$this->assign('store_id',$store_id);
    		$this->assign('bg_color','#F3F3F3');
    		$this->display();
    	}
    	if(IS_POST)
    	{
    		$category['gcat_name'] = $_POST['name'];
			$category['gcat_pinyin'] = $_POST['pinyin'];
			$category['gcat_status'] = intval($_POST['iswrite']);
			if(!empty($_POST['gcat_id'])){
				$category['gcat_id'] = $_POST['gcat_id'];
			}
    		if(!empty($_POST['store_id'])){
				$category['store_id'] = $_POST['store_id'];
			}
			$result = D('Waimai_goods_category')->save_category($category);	
			if(!$result['error_code']){
				$this->error($result['msg']);
			}
			$this->success('保存成功',U('Waimai/product_category'));
    	}
    }

	public function uploadFile(){
        $rand_num = date('Y/m',$_SERVER['REQUEST_TIME']);
        $upload_dir = './upload/waimai/'.$rand_num.'/'; 
        if(!is_dir($upload_dir)){
            mkdir($upload_dir,0777,true);
        }
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();
        $upload->maxSize = 10*1024*1024;
        $upload->allowExts = array('jpg','jpeg','png','gif');
        $upload->allowTypes = array('image/png','image/jpg','image/jpeg','image/gif');
        $upload->savePath = $upload_dir; 
        $upload->saveRule = 'uniqid';
        if($upload->upload()){
            $uploadList = $upload->getUploadFileInfo();
            
            return $rand_num.'/'.$uploadList[0]['savename'];
        }else{
            $this->frame_submit_tips(0,$upload->getErrorMsg());
        }
    }
    
    // 删除某一分类
     public function product_category_del(){
     	$cat_id = intval($_POST['gcat_id']);
     	if($cat_id){
			$condition['gcat_id'] = $cat_id;
			$now_category = D('Waimai_goods_category')->field(true)->where($condition)->find();
			
			if(!empty($now_category)){
				if(D('Waimai_goods_category')->where($condition)->delete()){
					$this->success('删除成功！');
				}else{
					$this->error('删除失败！请重试~');
				}
			}else{
				$this->error('非法提交,请重新提交~！');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
     }
     
	/*
	 * 外卖订单列表
	 * @author yaolei
	 */
	public function order(){
    	if(!empty($_GET['keyword'])){
    		if($_GET['searchtype'] == 'order_number'){
    			$order_condition['order_number'] = $_GET['keyword'];
    		}
    	}
    	
    	$database_order = D('Waimai_order');
    	$database_store = D('Merchant_store');
    	$database_merchant = D('Merchant');
    	
    	import('@.ORG.system_page');
    	$count_order = $database_order->where($order_condition)->count();
    	$p = new Page($count_order, 20);
    	$order_info = $database_order->field(true)->where($order_condition)->order('`order_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
    	$pagebar = $p->show();
    	$this->assign('pagebar', $pagebar);
    	
    	foreach($order_info as $key => $val){
    		$storeId[$val['store_id']] = $val['store_id'];
    		$merId[$val['mer_id']] = $val['mer_id'];
    		$orderId[$val['order_id']] = $val['order_id'];
    	}
    	
    	$store_where['store_id'] = array('in', $storeId);
    	$store_info = $database_store->field(true)->where($store_where)->select();
    	 
    	$merchant_where['mer_id'] = array('in', $merId);
    	$merchant_info = $database_merchant->field(true)->where($merchant_where)->select();
    	 
    	$deliver_where['order_id'] = array('in', $orderId);
    	$deliverSupplyInfo = D("Deliver_supply")->field('`order_id`,`start_time`,`end_time`')->where($deliver_where)->select();
    	 
    	$orderObj = new Waimai_orderModel();
    	$order_list = $orderObj->formatArray($order_info, $store_info, $merchant_info, $deliverSupplyInfo);
    	
		$this->assign('order_list', $order_list);
		$this->display();
	}
	
	/**
	 * 外卖订单详情
	 * @author yaolei
	 */
	public function order_detail(){
		$database_order = D('Waimai_order');
    	$database_store = D('Merchant_store');
    	$database_merchant = D('Merchant');
    	$database_user = D('User');
    	$order_condition['order_id'] = $_GET['order_id'];
    	
    	$order_info = $database_order->field(true)->where($order_condition)->select();
    	foreach($order_info as $key => $val){
    		$storeId[$val['store_id']] = $val['store_id'];
    		$merId[$val['mer_id']] = $val['mer_id'];
    		$orderId[$val['order_id']] = $val['order_id'];
    		$uid[$val['uid']] = $val['uid'];
    	}
    	
    	$store_where['store_id'] = array('in', $storeId);
    	$store_info = $database_store->field(true)->where($store_where)->select();
    	
    	$merchant_where['mer_id'] = array('in', $merId);
    	$merchant_info = $database_merchant->field(true)->where($merchant_where)->select();
    	
    	$deliver_where['order_id'] = array('in', $orderId);
    	$deliverSupplyInfo = D("Deliver_supply")->field('`order_id`,`start_time`,`end_time`')->where($deliver_where)->select();
    	
    	$user_where['uid'] = array('in', $uid);
    	$user_info = $database_user->field(true)->where($user_where)->select();
    	
    	$orderObj = new Waimai_orderModel();
    	$now_order = $orderObj->formatArray($order_info, $store_info, $merchant_info, $deliverSupplyInfo);
    	$now_order = $now_order[0];

		$this->assign('now_order', $now_order);
		$this->display();
	}
	
	/**
	 * 外卖订单修改
	 * @author yaolei
	 */
	public function order_edit(){
		if(empty($_POST['paid']) && empty($_POST['order_status']) && empty($_POST['comment_status'])){
			$this->error('未做修改，提交失败！');
		}
		$order_condition['order_id'] = $_POST['order_id'];
		$params = array();
		if(!empty($_POST['paid'])){
			$params['paid'] = $_POST['paid'];
		}
		if(!empty($_POST['order_status'])){
			$params['order_status'] = $_POST['order_status'];
		}
		if(!empty($_POST['comment_status'])){
			$params['comment_status'] = $_POST['comment_status'];
		}
		
		if(D('Waimai_order')->where($order_condition)->data($params)->save()){
			if(!empty($_POST['order_status'])){
				$data['order_id'] = $_POST['order_id'];
				$data['status'] = $_POST['order_status'];
				$data['store_id'] = $_POST['store_id'];
				$data['uid'] = $_POST['uid'];
				$data['time'] = time();
				$data['group'] = 1;
				D("Waimai_order_log")->data($data)->add();
			}
			$this->success('编辑成功！');
		} else {
			$this->error('编辑失败！请重试~');
		}
	}
	
 	/**
     * 店铺分类列表
     */
    public function mer_category() {
        $keyword = isset($_GET['keyword'])? trim($_GET['keyword']): '';
		$cat_fid = isset($_GET['cat_fid'])? trim($_GET['cat_fid']): 0;
        
        $condition = array();
        $condition = "`cat_fid`=$cat_fid";
        if ($keyword) {
            $condition .= " AND `cat_name like` %$keyword%";
        }
        $count = D('Waimai_store_category')->where($condition)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 20);
        $list = D('Waimai_store_category')->field("`cat_id`, `cat_pinyin`, `cat_sort`, `cat_name`, `cat_status`, `create_time`, `last_time`")->where($condition)->order('`cat_sort` DESC')->limit($p->firstRow.','.$p->listRows)->select();
file_put_contents('ll222l.txt',var_export(D(),true));
        $pagebar = $p->show();
    	$this->assign('categoryList',$list);
        $this->assign('keyWord', $keyWord);
		$this->assign('cat_fid', $cat_fid);
		$this->assign('pagebar',$pagebar);
    	$this->display();
    }
    
     /**
     * 店铺分类管理
     */
    public function mer_category_manage(){
    	$cat_id = intval(I('cat_id'));
        if (!$cat_id) {
            $this->error("参数错误");
        }

        if (IS_POST) {
            $category = array();
            if ($_FILES['icon']['error'] != 4) {
              $icon = $this->uploadFile();
              $category['icon'] = $icon;
            }
            
            $category['cat_name'] = $_POST['name'];
            $category['cat_pinyin'] = $_POST['pinyin'];
            $category['cat_status'] = intval($_POST['iswrite']);
            $category['cat_sort'] = intval($_POST['sort']);
            $category['last_time'] = time();
            $result = D("Waimai_store_category")->where("`cat_id`=$cat_id")->data($category)->save();
            if (false === $result) {
                $this->frame_submit_tips(0,"修改失败");
            }
            $this->frame_submit_tips(1,"修改成功");
        } else {
            $data = D("Waimai_store_category")->find($cat_id);
            $this->assign($data);
            $this->assign('bg_color','#F3F3F3');
            $this->display();
        }
    }

    /**
     * 店铺分类管理
     */
    public function mer_category_add(){
        if (IS_POST) {
        	$category = array();
        	if ($_FILES) {
        		$icon = $this->uploadFile();
        		$category['icon'] = $icon;
        	}
            $category['cat_name'] = $_POST['name'];
            $category['cat_fid'] = empty($_POST['cat_fid'])? 0: intval($_POST['cat_fid']);
            $category['cat_pinyin'] = $_POST['pinyin'];
            $category['cat_status'] = intval($_POST['iswrite']);
            $category['cat_sort'] = intval($_POST['sort']);
            $category['create_time'] = time();
            $category['last_time'] = time();

            $result = D("Waimai_store_category")->add($category);
			if (false === $result) {
                $this->frame_submit_tips(0,"添加失败");
            }else{
				$this->frame_submit_tips(1,"添加成功");
			}
        }
        if (I("cat_fid")) {
            $this->assign('cat_fid', I('cat_fid'));
        }
		$this->assign('bg_color','#F3F3F3');
        $this->display();
    }

    // 删除某一分类
     public function mer_category_del(){
     	$cat_id = intval($_POST['cat_id']);
        if (!$cat_id) {
            $this->error("参数错误");exit;
        }

		$condition['cat_id'] = $cat_id;
		$now_category = D('Waimai_store_category')->field(true)->where($condition)->find();
		
		if(!empty($now_category)){
			if(D('Waimai_store_category')->where($condition)->delete()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~！');
		}
		
     }
     
     //商品列表
     public function goods(){
     	//筛选
     	if(!empty($_GET['keyword'])){
     		if($_GET['searchtype']=='goods_id'){
     			$condition_where['goods_id'] = intval($_GET['keyword']);
     		} else if($_GET['searchtype']=='name'){
     			$condition_where['name'] = array('LIKE', '%'.$_GET['keyword'].'%');
     		}
     	}
     	
     	$database_goods = D('Waimai_goods');
     	$database_merchant = D('Merchant');
     	$database_store = D('Merchant_store');
     	$database_category = D('Waimai_goods_category');
     	$goods_count = $database_goods->where($condition_where)->count();
     	
     	import('@.ORG.system_page');
     	$page = new Page($goods_count, 20);
     	$goods_info = $database_goods->field(true)->where($condition_where)->order('`sort`, `goods_id` DESC')->limit($page->firstRow.','.$page->listRows)->select();
     	$merchant_info = $database_merchant->field('`mer_id`, `name`')->select();
     	$store_info = $database_store->field('`store_id`, `name`')->select();
     	$category_where['gcat_status'] = '1';
     	$category_info = $database_category->field('`gcat_id`, `gcat_name`')->where($category_where)->select();
     	$goodsObj = new Waimai_goodsModel();
     	$goods_list = $goodsObj->formatArray($goods_info, $merchant_info, $store_info, $category_info);
     	
     	$pagebar = $page->show();
     	$this->assign('pagebar', $pagebar);
     	$this->assign('goods_list', $goods_list);
     	$this->display();
     }
     
     //商品详情
     public function goods_detail(){
     	$database_goods = D('Waimai_goods');
     	$database_merchant = D('Merchant');
     	$database_store = D('Merchant_store');
     	$database_category = D('Waimai_goods_category');
     	$goods_count = $database_goods->count();
     	$condition_where['goods_id'] = $_GET['goods_id'];
     	
     	$goods_info = $database_goods->field(true)->where($condition_where)->order('`sort`, `goods_id` DESC')->select();
     	$merchant_info = $database_merchant->field('`mer_id`, `name`')->select();
     	$store_info = $database_store->field('`store_id`, `name`')->select();
     	$category_where['gcat_status'] = '1';
     	$category_info = $database_category->field('`gcat_id`, `gcat_name`')->where($category_where)->select();
     	$goodsObj = new Waimai_goodsModel();
     	$goods_list = $goodsObj->formatArray($goods_info, $merchant_info, $store_info, $category_info);
     	$now_order = $goods_list[0];
     	
     	$this->assign('now_order', $now_order);
     	$this->display();
     }
     
     //商品编辑
     public function goods_edit(){
     	$where['goods_id'] = $_POST['goods_id'];
     	$date['status'] = $_POST['status'];
     	
     	if(D('Waimai_goods')->where($where)->data($date)->save()){
     		$this->frame_submit_tips(1, '编辑成功！');
     	} else {
     		$this->frame_submit_tips(0, '编辑失败！请重试~');
     	}
     }
     
     //外卖店铺补充列表
     public function store(){
     	$database_gstore = D('Waimai_store');
     	$database_merchant = D('Merchant');
     	$database_mstore = D('Merchant_store');
     	$gstore_count = $database_gstore->count();
     	
     	import('@.ORG.system_page');
     	$page = new Page($gstore_count, 20);
     	$gstore_info = $database_gstore->field(true)->order('`id` DESC')->limit($page->firstRow.','.$page->listRows)->select();
     	$merchant_info = $database_merchant->field('`mer_id`, `name`')->select();
     	$mstore_info = $database_mstore->field('`store_id`, `name`')->select();
     	$gstoreObj = new Waimai_storeModel();
     	$gstore_list = $gstoreObj->formatArray($gstore_info, $merchant_info, $mstore_info);
     	
     	$pagebar = $page->show();
     	$this->assign('pagebar', $pagebar);
     	$this->assign('gstore_list', $gstore_list);
     	$this->display();
     }
     
     //外卖店铺补充详情
     public function store_detail(){
     	$database_gstore = D('Waimai_store');
     	$database_merchant = D('Merchant');
     	$database_mstore = D('Merchant_store');
     	$gstore_count = $database_gstore->count();
     	$gstore_where['id'] = $_GET['id'];
     	
     	$gstore_info = $database_gstore->field(true)->order('`id` DESC')->where($gstore_where)->select();
     	$merchant_info = $database_merchant->field('`mer_id`, `name`')->select();
     	$mstore_info = $database_mstore->field('`store_id`, `name`')->select();
     	$gstoreObj = new Waimai_storeModel();
     	$gstore_list = $gstoreObj->formatArray($gstore_info, $merchant_info, $mstore_info);
     	$now_order = $gstore_list[0];
     	
     	$this->assign('now_order', $now_order);
     	$this->display();
     }
     
     //修改店铺信息
     public function store_edit(){
     	$gstore_where['id'] = $_POST['id'];
     	$date['tools_money_have'] = $_POST['tools_money_have'];
     	
     	if(D('Waimai_store')->where($gstore_where)->data($date)->save()){
     		$this->frame_submit_tips(1, '编辑成功！');
     	} else {
     		$this->frame_submit_tips(0, '编辑失败！请重试~');
     	}
     }
     
     // 底部导航管理
     public function footer(){
     	$database_slider_category  = D('waimai_slider_category');
     	$category_list = $database_slider_category->field(true)->where(array('cat_fid'=>0))->order('`cat_id` ASC')->select();
     	$this->assign('category_list',$category_list);
     	$this->display();
     }
     
     public function footer_edit(){
     	if(IS_POST){
     		$id = $_POST['id'];
     		$column['cat_name'] = $_POST['name'];
     		$column['cat_key'] = $_POST['url'];
     		$database_slider_category  = D('waimai_slider_category');
     		$result = $database_slider_category->where(array('cat_id'=>$id))->save($column);
     		if($result){
     			$this->success('修改成功');
     		}
     		$this->error('修改失败');
     	}else{
     		$cat_id = $_GET['cat_id'];
     		$database_slider_category  = D('waimai_slider_category');
     		$category_list = $database_slider_category->field(true)->where(array('cat_id'=>$cat_id))->order('`cat_id` ASC')->find();
     		$this->assign('now_link',$category_list);
     		$this->display();
     	}
     }
     
     public function footer_add(){
     	if(IS_POST){
     		$column['cat_name'] = $_POST['name'];
     		$column['cat_key'] = $_POST['url'];
     		$database_slider_category  = D('waimai_slider_category');
     		$result = $database_slider_category->data($column)->add();
     		if($result){
     			$this->success('添加成功');
     		}
     		$this->error('添加失败');
     	}else{
     		$this->display();
     	}
     }
     
     public function footer_del(){
     	$cat_id = $_POST['id'];
     	$database_slider_category  = D('waimai_slider_category');
     	$category_list = $database_slider_category->field(true)->where(array('cat_id'=>$cat_id))->find();
     	
     	if(!$category_list){
     		$this->error('非法操作');
     	}
     	if($database_slider_category->where(array('cat_id'=>$cat_id))->delete()){
     		 
     		$this->success('删除成功');
     	}else{
     		$this->error('删除失败！请重试~');
     	}
     }
     
     public function footer_child(){
     	$id = $_GET['cat_id'];
     	$database_slider_category  = D('waimai_slider_category');
     	$category_list = $database_slider_category->field(true)->where(array('cat_fid'=>$id))->order('`cat_id` ASC')->select();
     	$this->assign('category_list',$category_list);
     	$this->assign('fid',$id);
     	$this->display();
     }
     
     public function footer_child_add(){
     	if(IS_POST){
     		$column['cat_name'] = $_POST['name'];
     		$column['cat_key'] = $_POST['url'];
     		$column['cat_url'] = $_POST['cat_url'];
     		$column['cat_fid'] = $_POST['fid'];
     		$database_slider_category  = D('waimai_slider_category');
     		$result = $database_slider_category->data($column)->add();
     		if($result){
     			$this->success('添加成功');
     		}
     		$this->error('添加失败');
     	}else{
     		$id = $_GET['fid'];
     		
     		$this->assign('fid',$id);
     		$this->display();
     	}
     }
     
     public function footer_child_edit(){
     	if(IS_POST){
     		$id = $_POST['id'];
     		$column['cat_name'] = $_POST['name'];
     		$column['cat_key'] = $_POST['url'];
     		$column['cat_url'] = $_POST['cat_url'];
     		$database_slider_category  = D('waimai_slider_category');
     		$result = $database_slider_category->where(array('cat_id'=>$id))->save($column);
     		if($result){
     			$this->success('修改成功');
     		}
     		$this->error('修改失败');
     	}else{
     		$cat_id = $_GET['cat_id'];
     		$database_slider_category  = D('waimai_slider_category');
     		$category_list = $database_slider_category->field(true)->where(array('cat_id'=>$cat_id))->order('`cat_id` ASC')->find();
     		$this->assign('now_link',$category_list);
     		$this->display();
     	}
     }

	public function mobile() {
		$config = D("Waimai_app_config");
		if (IS_POST) {
			$ios_version = I("ios_version", false);
			$android_version = I("android_version", false);
			$ios_download_url = I("ios_download_url", false);
			$android_download_url = I("android_download_url", false);
            $ios_version_code = I("ios_version_code", false);
            $ios_version_desc = I("ios_version_desc", false);
            $android_version_desc = I("android_version_desc", false);
            $android_version_code = I("android_version_code", false);
			$about = htmlspecialchars_decode($_POST['about']);
			$rules = htmlspecialchars_decode($_POST['rules']);

			$columns = array();
			$columns['ios_version'] = $ios_version;
			$columns['android_version'] = $android_version;
			$columns['ios_download_url'] = $ios_download_url;
            $columns['android_download_url'] = $android_download_url;
            $columns['android_version_code'] = $android_version_code;
            $columns['ios_version_code'] = $ios_version_code;
            $columns['android_version_desc'] = $android_version_desc;
			$columns['ios_version_desc'] = $ios_version_desc;
			$columns['about'] = $about;
			$columns['rules'] = $rules;
			foreach ($columns as $key=>$val) {
				$config->where(array('var'=>$key))->data(array('value'=>$val))->save();
			}

			$this->success("修改成功");
		} else {
			$data = $config->select();
			$return = array();
			foreach ($data as $val) {
				$return[$val['var']] = $val['value'];
			}
			$this->assign($return);
			$this->display();
		}
	}
	
	/**
	 * 外卖评论列表
	 * @author yaolei
	 */
	public function comment(){
		$store_where['store_id'] = $_GET['store_id'];
		$dataBase_store = D('Merchant_store');
		$dataBase_comment = D('Waimai_comment');
		$storeInfo = $dataBase_store->field(true)->where($store_where)->find();
		
		import('@.ORG.system_page');
		$count_order = $dataBase_comment->where($store_where)->count();
		$p = new Page($count_order, 20);
		$commentInfo = $dataBase_comment->field(true)->where($store_where)->order('`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);
		
		foreach($commentInfo as $key => $val){
			$user_where['uid'] = $val['uid'];
			$userInfo = D('User')->field(true)->where($user_where)->find();
			$commentInfo[$key]['nickname'] = $userInfo['nickname'];
			$commentInfo[$key]['phone'] = $userInfo['phone'];
		}
		
		$this->assign('storeInfo', $storeInfo);
		$this->assign('commentInfo', $commentInfo);
		$this->display();
	}
}