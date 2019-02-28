<?php
class WaimaiAction extends BaseAction{
    protected $discount_type;
    protected $store_discount;
    protected $waimai_coupon;
    public function _initialize() {
        parent::_initialize();
        $this->discount_type = D("Waimai_discount");
        $this->store_discount = D("Waimai_store_discount");
        $this->waimai_coupon = D("Waimai_coupon");
    }

    /**
     * waimai store list
     * @return template store_list
     */
    public function index() {
        $mer_id = $this->merchant_session['mer_id'];
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['mer_id'] = $mer_id;
        $condition_merchant_store['have_waimai'] = '1';
        $condition_merchant_store['status'] = '1';
        $count_store = $database_merchant_store->where($condition_merchant_store)->count();
    	if(count($count_store) <1){
    		redirect(U('Config/store'),1,'暂无店铺 请先去添加店铺');
    	}

        $db_arr = array(C('DB_PREFIX').'area'=>'a',C('DB_PREFIX').'merchant_store'=>'s');
        import('@.ORG.merchant_page');
        $p = new Page($count_store,30);
        $store_list = D()->table($db_arr)->field(true)->where("`s`.`mer_id`='$mer_id' AND `s`.`status`='1' AND `s`.`have_waimai`='1' AND `s`.`area_id`=`a`.`area_id`")->order('`s`.`sort` DESC,`s`.`store_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();
        $this->assign('store_list',$store_list);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);

        $this->display();
    }

    public function discount_setting() {
        $store_id = I("store_id");
        if (empty($store_id)) {
            $this->error("参数错误！");
        }
        $store = D("Waimai_store")->where(array('store_id'=>$store_id))->find();
        if (!$store) {
            $this->error("请完善外卖店铺信息后再添加外卖店铺优惠！", U("Merchant/Waimai/store", array('store_id'=>$store_id)));
        }
        $mer_id = $this->merchant_session['mer_id'];
        if (empty($mer_id)) {
            $this->error("请登录！");
        }
        $store = D("Waimai_store")->field(true)->where(array('store_id'=>$store_id, 'mer_id'=>$mer_id))->find();
        if (!$store) {
            $this->error("店铺不存在");
        }
        if (! IS_POST) {
            $store_id = I("store_id");
            if (empty($store_id)) {
                $this->error("参数错误！");
            }
            $discount_type = $this->discount_type->showlist();
            $this->assign('discount_type_data', $discount_type);

            $condition['mer_id'] = $mer_id;
            $condition['store_id'] = $store_id;
            $condition['delete'] = 0;

            import('@.ORG.merchant_page');

            $total = $this->store_discount->where($condition)->count();
            $p = new Page($total, 30);
            $db_arr = array(C('DB_PREFIX').'waimai_store_discount'=>'s',C('DB_PREFIX').'waimai_discount_type'=>'t');

            $list = D()->table($db_arr)->field('s.*,t.`name` as type_name,t.`icon`')->where("s.`mer_id` = $mer_id AND s.`store_id` = $store_id AND s.`delete` = 0 and s.`type_id` = t.`type_id`")->order('`discount_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();

//             if(count($list)>1){
//             	$pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
//             	if(!empty($pay_method)){
//             		foreach ($list as $l=>$listInfo){
//             			if($pay_method[$listInfo['pay_method_term']]){
//             				$list[$l]['pay_method_term'] = $pay_method[$listInfo['pay_method_term']]['name'];
//             			}else{
//             				$list[$l]['pay_method_term'] = '';
//             			}
//             		}
//             	}
//             }
            
            $pagebar = $p->show();
            $this->assign('store_discount_list',$list);
            $this->assign('pagebar',$pagebar);
            $this->assign('store_id', $store_id);
            $pay_method = D('Config')->get_pay_method();
            $this->assign('pay_method', $pay_method);
            $this->display("discount_setting");exit;
        }

        $store_id = I('store_id', 0, 'intval');
        if (! isset($store_id)) {
            $this->error("参数错误！");
        }
        $type_id = I('type_id', 0, 'intval');
        if (! isset($type_id)) {
            $this->error("参数错误！");
        }
        $discount_money = I('discount_money', 0);
        if (! isset($discount_money)) {
            $this->error("参数错误！");
        }
        $money_term = I('money_term', 0);
        if (! isset($money_term)) {
            $this->error("参数错误！");
        }
        $newuser_term = I('newuser_term', 0);
        if (! isset($newuser_term)) {
            $this->error("参数错误！");
        }
        $pay_method_term = I('pay_term', 0);
        $desc = I('desc', 0);
        if (in_array($type_id, array(2, 3, 4))) {
            $pay_method_term = 0;
        }
        if (in_array($type_id, array(2, 4))) {
            $money_term = 0;
            $newuser_term = 0;
            $discount_money = 0;
            $desc = ($type_id == 2)? '平台代金券': '店铺代金券';
        }
        if(in_array($type_id, array(3,101))){
            $desc = '满'.$money_term.'减'.$discount_money;
        }

        $columns = array();
        $columns['store_id'] = $store_id;
        $columns['type_id'] = $type_id;
        $columns['discount_money'] = $discount_money;
        $columns['money_term'] = $money_term;
        $columns['newuser_term'] = $newuser_term;
        $columns['pay_method_term'] = $pay_method_term;
        $columns['mer_id'] = $mer_id;
        $columns['desc'] = $desc;

        $result = $this->store_discount->insert($columns);
        if (! $result) {
            $this->error("添加失败！");
        }
        //修改商家字段，便于搜索
        $data = D("Waimai_discount_type")->field("alias")->where(array('type_id'=>$type_id))->find();
        if ($data) {
            $alias_field = $data['alias'];
            $columns = array($alias_field => 1);
            D("Waimai_store")->where(array('store_id'=>$store_id))->data($columns)->save();
        }
        $this->success("添加成功");
    }
    
	public function discount_store_del() {
        $discount_id = I('discount_id');
        if (empty($discount_id)) {
            $this->error("参数错误！");
        }
        $mer_id = $this->merchant_session['mer_id'];
        if (empty($mer_id)) {
            $this->error("参数错误！");
        }
        $store_id = I("store_id", 0, 'intval');
        if (!$store_id) {
            $this->error("参数错误！");
        }

        $where = array();
        $where['discount_id'] = $discount_id;

        $discount = $this->store_discount->where($where)->find();
        if (!$discount) {
            $this->error("优惠不存在");
        }

        $result = $this->store_discount->del($where);
        if (! $result) {
            $this->error("删除失败！");
        }

        //修改商家字段，便于搜索
        $where = array();
        $where['type_id'] = $discount['type_id'];
        $where['delete'] = 0;
        $where['store_id'] = $store_id;
        $data = $this->store_discount->where($where)->find();
        if (! $data) {
            $discount = D("Waimai_discount_type")->field("alias")->where(array('type_id'=>$discount['type_id']))->find();
            $alias_field = $discount['alias'];
            $columns = array($alias_field => 0);
            D("Waimai_store")->where(array('store_id'=>$store_id))->data($columns)->save();
        }
        $this->success("删除成功");
    }

    public function product_category(){
    	$mer_id = $this->merchant_session['mer_id'];
    	// 所有的商品列表
    	$columnCondition['merId'] = $mer_id;
        $columnCondition['gcat_status'] = 1;
    	$category = D('Waimai_goods_category')->get_all_category($columnCondition);
    	
    	$this->assign('categoryList',$category);
		$this->display();
    }
    
 	/**
     * 商品分类管理
     */
    public function product_category_manage(){
    	$mer_id = $this->merchant_session['mer_id'];
    	$gcat_id = isset($_GET['cat_id'])?intval($_GET['cat_id']):0;
    	
    	if(!empty($mer_id) && !IS_POST){
    		$condition['mer_id'] = intval($mer_id);
    		if(!$gcat_id){
    			$condition['have_waimai'] = 1;
    		}
    		$storeList = D('Merchant_store')->field('name,store_id')->where($condition)->select();
    		if(count($storeList) <1){
    			redirect(U('Config/store'),1,'暂无店铺 请先去添加店铺');
    		}
    		 
    		$this->assign('storeList',$storeList);
    	}
    	if(!empty($gcat_id)){
    		$condition['gcat_id'] = $gcat_id;
    		$condition['mer_id'] = $mer_id;
    		$categoryDetail = D('Waimai_goods_category')->field(true)->where($condition)->find();

			$this->assign('gcat_id',$gcat_id);
    		$this->assign('categoryDetail',$categoryDetail);
    	}
    	if(IS_POST)
    	{
    		$category['mer_id'] = $mer_id;
    		$category['gcat_name'] = $_POST['name'];
			$category['gcat_pinyin'] = $_POST['pinyin'];
			$category['gcat_status'] = intval($_POST['iswrite']);
			$category['gcat_sort'] = intval($_POST['cat_sort']);
			$category['gcat_id'] = $_POST['gcat_id']?intval($_POST['gcat_id']):'';
			if(empty($_POST['storeIdStr'])){
				$this->error('没选择店铺，请重试');
			}
    		$_POST['storeIdStr'] = trim($_POST['storeIdStr'],',');
    		$storeIdArray = explode(',', $_POST['storeIdStr']);
    		$count = count($storeIdArray);
    		for($i=0;$i<$count;$i++){
    			$categoryMany[$i] = $category;
    			$categoryMany[$i]['store_id'] = $storeIdArray[$i];
    		}
    		if(count($categoryMany)<1){
    			return;
    		}
			foreach ($categoryMany as $categoryInfo){
				$result = D('Waimai_goods_category')->save_category($categoryInfo);	
				if(!$result['error_code']){
					$this->error($result['msg']);
				}		
			} 
			$this->success('保存成功',U('Waimai/product_category'));
    	}else{
    		$this->display();
    	}
    	
    }
    
	// 删除某一分类
     public function product_category_del(){
     	$cat_id = intval($_GET['cat_id']);
     	$mer_id = $this->merchant_session['mer_id'];
     	if($cat_id){
			$condition['gcat_id'] = $cat_id;
			$condition['mer_id'] = $mer_id;
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

	public function coupon() {
        $db_arr = array(C('DB_PREFIX').'area'=>'a',C('DB_PREFIX').'merchant_store'=>'s');
        import('@.ORG.merchant_page');
        $p = new Page($count_store,30);
        $store_list = D()->table($db_arr)->field(true)->where("`s`.`mer_id`='$mer_id' AND `s`.`status`='1' AND `s`.`have_waimai`='1' AND `s`.`area_id`=`a`.`area_id`")->order('`sort` DESC,`store_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();

        $mer_id = $this->merchant_session['mer_id'];

        $store_list = D()->table(C('DB_PREFIX').'merchant_store')->field("`name`, `store_id`")->where("`mer_id`='$mer_id' AND `status`='1' AND `have_waimai`='1'")->order('`sort` DESC,`store_id` ASC')->select();
        $this->assign('store_list', $store_list);
        
        $condition_merchant_store['mer_id'] = $mer_id;
        $condition_merchant_store['delete'] = '0';
        $total = $this->waimai_coupon->where($condition_merchant_store)->count();
        $db_arr = array(C('DB_PREFIX').'waimai_coupon'=>'c',C('DB_PREFIX').'merchant_store'=>'s');
        import('@.ORG.merchant_page');
        $p = new Page($total, 30);
        $list = D()->table($db_arr)->field("c.*,s.`name` as `store_name`")->where("c.`mer_id`='$mer_id' AND c.`delete`=0 AND c.`store_id` = s.`store_id`")->order('c.`coupon_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
        import('ORG.Crypt.Mcrypt');
        //$coupon_key = "coupon_id=16&mer_id=727&store_id=235&end_time=1439527689&type=1";
        $key = C('WAIMAI_COUPON_KEY');
        $mcrypt = new Mcrypt($key);
        foreach ($list as $key=>$v) {
        	$list[$key]['coupon_key'] = $mcrypt->encode("coupon_id=".$v['coupon_id']."&mer_id=".$v['mer_id']."&store_id=".$v['store_id']."&end_time=".$v['end_time']."&type=1");	
        }
        $pagebar = $p->show();
        $this->assign('coupon_list',$list);
        $this->assign('pagebar',$pagebar);

        $this->display("coupon_list");
    }

	public function store() {
		$store_id = $_GET['store_id'];
		$mer_id = $this->merchant_session['mer_id'];
		if(!$store_id){
			return ;
		}
		$condition['store_id'] = $store_id;
		$condition['mer_id'] = $mer_id;
		$storeInfo = D('merchant_store')->where($condition)->find();
		
		//店铺名称
		$storeName = $storeInfo['name'];
		$this->assign('storeName', $storeName);
		if(!$storeInfo){
			$this->error('非法操作',U('Waimai/index')) ;
		}
		
		$Waimai_store = D('waimai_store')->where($condition)->find();
		
		//关键字查询
		$keywords_where['third_id'] = $storeInfo['store_id'];
		$keywords_where['third_type'] = 'waimai';
		$keywordsInfo = D("Keywords")->field(true)->where($keywords_where)->select();
		$keywordsStr = "";
		foreach ($keywordsInfo as $key) {
			$keywordsStr .= $key['keyword'] . " ";
		}
		$this->assign('keywordsStr', $keywordsStr);
		
		// 店铺分类
		$where['cat_status'] = 1;
		$database_meal_category = D('waimai_store_category');
		$category_list = $database_meal_category->field(true)->where($where)->order('`cat_sort` DESC,`cat_id` ASC')->select();
		$list = array();
		foreach ($category_list as $row) {
			if ($row['cat_fid']) {
				$list[$row['cat_fid']]['list'][] = $row;
			} else {
				$list[$row['cat_id']] = isset($list[$row['cat_id']]) ? array_merge($list[$row['cat_id']], $row) : $row;
			}
		}
		foreach($list as $list_key=>$list_value){
			if(empty($list_value['cat_id']) || empty($list_value['list'])){
				unset($list[$list_key]);
			}
		}
		$this->assign('category_list',$list);
		
		$database_meal_store_category_relation = D('Waimai_store_category_relate');
		$condition_meal_store_category_relation['store_id'] = $storeInfo['store_id'];
		$relation_list = $database_meal_store_category_relation->field(true)->where($condition_meal_store_category_relation)->select();
		$relation_array = array();
		foreach($relation_list as $key=>$value){
			array_push($relation_array,$value['cat_id']);
		}
		$this->assign('relation_array',$relation_array);
		
		$deliver = D('deliver_store')->field('type,range')->where(array('store_id'=>$store_id))->find();
		if($deliver){
			$Waimai_store['deliver_type'] = intval($deliver['type']);
			$Waimai_store['send_range'] = intval($deliver['range']);
		}
		
		if(IS_POST){
			
			if(empty($_POST['store_category'])){
				$this->error('请至少选一个分类！');
			}
			
			$column['mer_id'] = intval($mer_id);
			$column['store_id'] = $_POST['store_id']?intval($_POST['store_id']):0;
			$currentStore = D('merchant_store')->field('adress')->where(array('mer_id'=>$column['mer_id'],'store_id'=>$column['store_id'],'have_waimai'=>1))->find();
			if(!$currentStore){
				$this->error('请至少选一个店铺！');
			}
			
			$column['start_send_money'] = floatval($_POST['start_send_money']);
        	$column['send_money']  = floatval($_POST['send_money']);
        	$column['total_money'] = floatval($_POST['total_money']);
       	 	$column['tools_money_have'] = intval($_POST['tools_money_have']);
        	$column['send_time'] = intval($_POST['send_time']);
			$column['last_time'] = $_SERVER['REQUEST_TIME'];
        	$column['create_time'] = $_SERVER['REQUEST_TIME'];
        	$column['close'] = intval($_POST['close_waimai']);
        	$column['tips'] = $_POST['txt_info'];
			//判断关键词
			$keywords = trim($_POST['keywords']);
			if(!empty($keywords)){
				$tmp_key_arr = explode(' ',$keywords);
				$key_arr = array();
				foreach($tmp_key_arr as $value){
					if(!empty($value)){
						array_push($key_arr,$value);
					}
				}
				if(count($key_arr)>5){
					$this->error('关键词最多5个。');
				}
			}
        	
            if (isset($_POST['start_time_1']) && $this->checkTime($_POST['start_time_1'], "/^\d{2}:\d{2}:\d{2}$/")) {
                $column['start_time_1'] = $_POST['start_time_1'];
            }
            if (isset($_POST['end_time_1']) && $this->checkTime($_POST['end_time_1'], "/^\d{2}:\d{2}:\d{2}$/")) {
                $column['end_time_1'] = $_POST['end_time_1'];
            }
            if (isset($_POST['start_time_2']) && $this->checkTime($_POST['start_time_2'], "/^\d{2}:\d{2}:\d{2}$/")) {
                $column['start_time_2'] = $_POST['start_time_2'];
            }
            if (isset($_POST['end_time_2']) && $this->checkTime($_POST['end_time_2'], "/^\d{2}:\d{2}:\d{2}$/")) {
                $column['end_time_2'] = $_POST['end_time_2'];
            }
            if (isset($_POST['start_time_3']) && $this->checkTime($_POST['start_time_3'], "/^\d{2}:\d{2}:\d{2}$/")) {
                $column['start_time_3'] = $_POST['start_time_3'];
            }
            if (isset($_POST['end_time_3']) && $this->checkTime($_POST['end_time_3'], "/^\d{2}:\d{2}:\d{2}$/")) {
                $column['end_time_3'] = $_POST['end_time_3'];
            }
			
			$waimaiStore = D('waimai_store')->field('store_id')->where(array('mer_id'=>$column['mer_id'],'store_id'=>$column['store_id']))->find();
			$deliver_type = intval($_POST['deliver_type']);
			$send_range = intval($_POST['send_range']);
			if($waimaiStore && $column['store_id'] !== 0){
				D('Waimai_store_category_relate')->where(array('store_id'=>$_POST['store_id']))->delete();
				$result = D('Waimai_store')->where(array('store_id'=>$column['store_id']))->data($column)->save();
				if($deliver_type !== false){
					$res = D('deliver_store')->where(array('store_id'=>$column['store_id']))->data(array('type'=>$deliver_type,'range'=>$send_range))->save();
				}
				//修改关键词（@author yaolei）
				$database_keywords = D('Keywords');
				$data_keywords['third_id'] = $column['store_id'];
				$data_keywords['third_type'] = 'waimai';
				$database_keywords->where($data_keywords)->delete();
				//判断关键词
				if(!empty($key_arr)){
					foreach($key_arr as $value){
						$data_keywords['keyword'] = $value;
						$database_keywords->data($data_keywords)->add();
					}
				}
			}else{
				$result = D('Waimai_store')->data($column)->add();
				// 添加一种默认的配送方式
				$deliver['store_id'] = $column['store_id'];
				$deliver['mer_id'] = intval($mer_id);
				$deliver['site'] = $currentStore['adress'];
				$deliver['type'] = $deliver_type;
				$deliver['range'] = $send_range;
				D('deliver_store')->data($deliver)->add();
				//判断关键词（@author yaolei）
				if(!empty($key_arr)){
					$database_keywords = D('Keywords');
					$data_keywords['third_id'] = $result;
					$data_keywords['third_type'] = 'waimai';
					foreach($key_arr as $value){
						$data_keywords['keyword'] = $value;
						$database_keywords->data($data_keywords)->add();
					}
				}
			}

			// 保存分类管理
			$cat_ids = array();
			foreach ($_POST['store_category'] as $cat_a) {
				$a = explode('-', $cat_a);
				$cat_ids[] = array('cat_fid' => $a[0], 'cat_id' => $a[1]);
			}
			foreach($cat_ids as $key => $value){
				$cateRelate[$key]['store_id'] = $_POST['store_id'];
				$cateRelate[$key]['cat_id'] = intval($value['cat_id']);
				$cateRelate[$key]['cat_fid'] = intval($value['cat_fid']);
				$cateRelate[$key]['mer_id'] = intval($mer_id);
				$cateRelate[$key]['create_time'] = $_SERVER['REQUEST_TIME'];
			}
			D('Waimai_store_category_relate')->addAll($cateRelate);
			
			if($result){
				$this->success('保存成功！',U('Waimai/index'));
			}else{
				$this->error('保存失败！请重试或联系管理员。',U('Waimai/index'));
			}
		}else{
			//$this->assign('storeCategory',$storeCategory);
			$this->assign('store',$Waimai_store);
			$this->assign('store_id',$store_id);
			$this->display();
		}
    }
    
    public function coupon_add() {
        $store_id = I('store_id');
        if (! isset($store_id)) {
            $this->error("店铺id不能为空！");
        }
        $name = I('name');
        if (! isset($name)) {
            $this->error("红包名字不能为空！");
        }
        $num = I('num', 0, 'intval');
        if (! isset($num)) {
            $this->error("发放数量不能为0！");
        }
        $limit = I('limit', 1);
        $money = I('money');
        if (! isset($money)) {
            $this->error("金额不能为空！");
        }
        if ($money < 0) {
            $this->error("红包金额不能小于0");
        }
        $order_money = I('order_money');
        if (! isset($order_money)) {
            $this->error("订单最低消费金额不能为空！");
        }
        $start_time = I('start_time');
        if (! isset($start_time)) {
            $this->error("红包活动开始时间不能为空！");
        }
        $end_time = I('end_time');
        if (! isset($end_time)) {
            $this->error("红包活动结束时间不能为空！");
        }
        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        if ($end_time < time()) {
            $this->error("结束时间不能小于当前时间！");
        }
        if ($start_time > $end_time) {
            $this->error("开始时间不能大于结束时间！");
        }
        $desc = I('desc');
        $is_show = I('is_show', 0, 'intval');
        $mer_id = $this->merchant_session['mer_id'];
        $min_money = $money;
        $max_money = $money;
        $out_time = $end_time;

        $columns = array();
        $columns['name'] = $name;
        $columns['desc'] = $desc;
        $columns['num'] = $num;
        $columns['type'] = 1;
        $columns['limit'] = $limit;
        $columns['order_money'] = $order_money;
        $columns['start_time'] = $start_time;
        $columns['end_time'] = $end_time;
        $columns['min_money'] = $min_money;
        $columns['max_money'] = $max_money;
        $columns['mer_id'] = $mer_id;
        $columns['store_id'] = $store_id;
        $columns['is_show'] = $is_show;

        $result = $this->waimai_coupon->insert($columns);
        if (! $result) {
            $this->error("添加失败！");
        }
        $this->success("添加成功");
    }

    public function goods() {
    	$mer_id = $this->merchant_session['mer_id'];
    	$store_id = $_GET['store_id']?intval($_GET['store_id']):'';
    	if(!$store_id){
    		$this->error('请选择店铺');
    	}
    	// 所有商品列表
    	$goodsConditon['merId'] = $mer_id;
    	$goodsConditon['storeId'] = $store_id;
    	$goodsList = D('Waimai_goods')->get_all_goods($goodsConditon);
    	
    	if(count($goodsList)<1){
    		return;
    	}
    	$waimai_image_class = new waimai_image();
    	foreach ($goodsList['goods_list'] as $k=>$goodsInfo){
    		$tmp_pic_arr = explode(';', $goodsInfo['image']);
    		$goodsList['goods_list'][$k]['list_pic'] = $waimai_image_class->get_image_by_path($tmp_pic_arr[0], 's');
    	}
    	
    	$this->assign('goodsList',$goodsList);
    	$this->assign('store_id',$store_id);
    	$this->display();
    }
    
    // 修改商品
    public function goods_manage(){
    	$mer_id = $this->merchant_session['mer_id'];
    	$store_id = $_GET['store_id']?intval($_GET['store_id']):'';
    	if(!$store_id){
    		$this->error('请选择店铺');
    	}
    	// 所有的商品分类列表
    	$columnCondition['merId'] = $mer_id;
    	$columnCondition['store_id'] = $store_id;
    	$columnCondition['gcat_status'] = 1;
    	$category = D('Waimai_goods_category')->get_all_category($columnCondition);
 
   	 	if(!$category || count($category['category_list']) <1){
    		redirect(U('Waimai/product_category'),1,'暂无商品分类 请先去添加商品分类');
    	}
    	$this->assign('categoryList',$category);
    	
    	$storeInfo = D('Waimai_store')->field('tools_money_have')->where(array('store_id'=>$store_id,'mer_id'=>$mer_id))->find();
    	$this->assign('storeInfo',$storeInfo);
    	
    	$goodsId = isset($_GET['goods_id'])?intval($_GET['goods_id']):'';
    	if($goodsId){
    		$waimai_image_class = new waimai_image();
    		$goodCondition = array(
    			'goods_id' => $goodsId,
    			'mer_id' => $mer_id,
    			'store_id' => $_GET['store_id']
    		);
	    	$goodInfo = D('Waimai_goods')->where($goodCondition)->find();
	    	if($goodInfo['mer_id'] !== $mer_id){
	    		$this->error('非法操作');
	    	}
	    	$tmp_pic_arr = explode(';', $goodInfo['image']);
	    
	    	foreach($tmp_pic_arr as $key=>$value){
				$goodInfo['pic'][$key]['title'] = $value;
				$goodInfo['pic'][$key]['url'] = $waimai_image_class->get_image_by_path($value, 's');
			}
	    	
	    	$this->assign('goodInfo',$goodInfo);
    	}
    	
    	$this->assign('store_id',$store_id);
    	$this->display();
    }
    
    public function save_goods(){
    	$mer_id = $this->merchant_session['mer_id'];
    	
    	if(IS_POST){
    		$store_id = intval($_POST['store_id'])?intval($_POST['store_id']):0;
    		$column['mer_id']  = $mer_id;
    		$column['gcat_id'] = intval($_POST['gcat_id']);
    		$column['name']  = $_POST['goodname'];
    		$column['unit']  = $_POST['unit'];
    		$column['price'] = floatval($_POST['price']);
    		$column['old_price'] = floatval($_POST['old_price']);
    		$column['vip_price'] = floatval($_POST['vip_price']);
    		$column['image'] = implode(';',$_POST['pic']);
    		$column['desc'] = $_POST['desc'];
    		$column['sort'] = intval($_POST['cat_sort']);
    		$column['limit'] = intval($_POST['daylimit']);
    		$column['status'] = intval($_POST['iswrite']);
    		$column['create_time'] = $_SERVER['REQUEST_TIME'];
    		$column['last_time'] = $_SERVER['REQUEST_TIME'];
    		$column['store_id'] = $store_id;
    		$column['tools_price'] = floatval($_POST['tools_price']);
    		
    		if($_POST['goods_id']){
    			$columnCondition['goods_id'] = $_POST['goods_id'];
    			$columnCondition['mer_id'] = $mer_id;
    			$columnCondition['store_id'] = $_POST['store_id'];
    			$result = D('waimai_goods')->where($columnCondition)->save($column);	
    			
    			if(!$result){
					$this->error($result['msg']);
				}	
    		}else{
    			$result = D('waimai_goods')->data($column)->add();	
    		}
    		if(!$result){
				$this->error($result['msg']);
			}
			$this->success('保存成功',U('Waimai/goods',array('store_id'=>$store_id)));
    	}
    }
	public function store_ajax_upload_pic(){
		if ($_FILES['imgFile']['error'] != 4) {
            $img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
            $rand_num = mt_rand(10, 99) . '/' . substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

            $upload_dir = './upload/waimai/' . $rand_num . '/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();
            $upload->maxSize = $this->config['waimai_pic_size'] * 1024 * 1024;
            $upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
            $upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
            $upload->savePath = $upload_dir;
            $upload->thumb = true;
            $upload->imageClassPath = 'ORG.Util.Image';
            $upload->thumbPrefix = 'm_,s_';
            $upload->thumbMaxWidth = $this->config['waimai_pic_width'];
            $upload->thumbMaxHeight = $this->config['waimai_pic_height'];
            $upload->thumbRemoveOrigin = false;
            $upload->saveRule = 'uniqid';
            if ($upload->upload()) {
                $uploadList = $upload->getUploadFileInfo();

                $title = $rand_num . ',' . $uploadList[0]['savename'];

                $group_image_class = new waimai_image();
                $url = $group_image_class->get_image_by_path($title, 's');

                exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
            } else {
                exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
            }
        } else {
            exit(json_encode(array('error' => 1, 'message' => '没有选择图片')));
        }
	}
	
	public function store_ajax_del_pic(){
		$store_image_class = new waimai_image();
		$store_image_class->del_image_by_path($_POST['path']);
	}
	
    // 删除商品
 	public function  goods_del(){
 		$goods_id = intval($_GET['goods_id']);
 		$store_id = intval($_GET['store_id']);
     	$mer_id = $this->merchant_session['mer_id'];
     	if($goods_id && $store_id){
			$condition['goods_id'] = $goods_id;
			$condition['mer_id'] = $mer_id;
			$condition['store_id'] = $store_id;
			$now_goods = D('Waimai_goods')->field(true)->where($condition)->find();
			
			if(!empty($now_goods)){
				if(D('Waimai_goods')->where($condition)->delete()){
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
    
    public function coupon_del() {
        $coupon_id = I('coupon_id');
        if (! isset($coupon_id)) {
            $this->error("参数错误！");
        }
        $mer_id = $this->merchant_session['mer_id'];

        $where = array();
        $where['coupon_id'] = $coupon_id;
        $where['mer_id'] = $mer_id;
        $where['delete'] = 0;

        $result = $this->waimai_coupon->del($where);
        if (! $result) {
            $this->error("删除失败！");
        }
        $this->success("删除成功");
    }

    private function checkTime($time, $format) {
        if (is_array($time)) {
            foreach ($time as $t) {
                if (! preg_match($format, $t)) {
                    return false;
                }
            }
        } else {
           if (! preg_match($format, $time)) {
                return false;
            } 
        }

        return true;
    }
	
    /*
	 * 外卖订单列表
	 * @author yaolei
	 */
    public function order(){
    	$database_order = D('Waimai_order');
    	$database_store = D('Merchant_store');
    	$database_merchant = D('Merchant');
    	$order_condition['mer_id'] = $this->merchant_session['mer_id'];
    	if(!empty($_GET['store_id'])){
    		$order_condition['store_id'] = $_GET['store_id'];
    	}
    	
    	import('@.ORG.merchant_page');
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
    	$order_condition['order_id'] = $_GET['order_id'];
    	
    	$order_info = $database_order->field(true)->where($order_condition)->select();
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
		$order_condition['order_id'] = $_POST['order_id'];
		$params = array();
		$params['paid'] = $_POST['paid'];
		$params['order_status'] = $_POST['order_status'];
		$params['comment_status'] = $_POST['comment_status'];
		
		$now_order = D('Waimai_order')->where($order_condition)->find();
		if(D('Waimai_order')->where($order_condition)->data($params)->save()){
			if ($now_order['pay_type'] == 'offline' && $now_order['paid'] == 0 && $params['paid'] == 1) {
			    $printHaddle = new PrintHaddle();
			    $printHaddle->printit($now_order['order_id'], 'waimai_order', 1);
			}
			if(!empty($_POST['order_status'])){
				$data['order_id'] = $_POST['order_id'];
				$data['status'] = $_POST['order_status'];
				$data['store_id'] = $_POST['store_id'];
				$data['uid'] = $_POST['uid'];
				$data['time'] = time();
				$data['group'] = 2;
				D("Waimai_order_log")->data($data)->add();
			}
			$this->success('修改成功');
		} else {
			$this->error('修改失败');
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
		import('@.ORG.merchant_page');
		$count_comment = $dataBase_comment->where($store_where)->count();
		$storeInfo = $dataBase_store->field(true)->where($store_where)->find();
		
		$p = new Page($count_comment, 20);
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