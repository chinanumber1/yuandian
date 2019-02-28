<?php
/**
 * 预约管理
 * AppointAction
 * @author yaolei
 */
class AppointAction extends BaseAction{
	/* 此方法下的首页信息 */
	public function index(){
		$database_apponit_category = D('Appoint_category');
		$condition_apponit_category['cat_fid'] = intval($_GET['cat_fid']);
		
		$count_apponit_category = $database_apponit_category->where($condition_apponit_category)->count();
		import('@.ORG.system_page');
		$page = new Page($count_apponit_category, 50);
		$category_list = $database_apponit_category->field(true)->where($condition_apponit_category)->order('`cat_sort` DESC, `cat_id` ASC')->limit($page->firstRow. ',' .$page->listRows)->select();
		$this->assign('category_list', $category_list);
		$pagebar = $page->show();
		$this->assign('pagebar', $pagebar);
		if($_GET['cat_fid']){
			$condition_now_apponit_category['cat_id'] = intval($_GET['cat_fid']);
			$now_category = $database_apponit_category->field(true)->where($condition_now_apponit_category)->find();
			if(empty($now_category)){
				$this->error_tips('没有找到该分类信息！', 3, U('Appoint/index'));
			}
			$this->assign('now_category', $now_category);
		}
		$this->display();
	}
	
	// 预约自定义表单所有字段展示
	public function cue_field(){
		$database_appoint_category = D('Appoint_category');
		$condition_now_appoint_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_appoint_category->field(true)->where($condition_now_appoint_category)->find();

		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		if(!empty($now_category['cue_field'])){
			$now_category['cue_field'] = unserialize($now_category['cue_field']);
			foreach ($now_category['cue_field'] as $val){
				$sort[] = $val['sort'];
			}
			array_multisort($sort, SORT_DESC, $now_category['cue_field']);
		}
		$this->assign('now_category',$now_category);
		$this->display();
	}
	
	// 预约自定义表单添加字段
	public function cue_field_add(){
		$this->assign('bg_color','#F3F3F3');
		
		$this->display();
	}
	
	// 预约自定义表单添加字段 操作
	public function cue_field_modify(){
		if(IS_POST){
			$database_appoint_category = D('Appoint_category');
			$condition_now_appoint_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_appoint_category->field(true)->where($condition_now_appoint_category)->find();
			
			if(!empty($now_category['cue_field'])){
				$cue_field = unserialize($now_category['cue_field']);
				foreach($cue_field as $key=>$value){
					if($value['name'] == $_POST['name']){
						$this->error('该填写项已经添加，请勿重复添加！');
					}
				}
			}else{
				$cue_field = array();
			}

			$post_data['name'] = $_POST['name'];
			$post_data['type'] = $_POST['type'];
			$post_data['sort'] = strval($_POST['sort']);
			$post_data['iswrite'] = $_POST['iswrite'];
			if(!empty($_POST['use_field'])){
				$post_data['use_field'] = explode(PHP_EOL, $_POST['use_field']);
			}
			array_push($cue_field,$post_data);
			$data_group_category['cue_field'] = serialize($cue_field);
			$data_group_category['cat_id'] = $now_category['cat_id'];
			if($database_appoint_category->data($data_group_category)->save()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	
	public function cue_field_del(){
		if(IS_POST){
			$database_group_category = D('Appoint_category');
			$condition_now_group_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
			
			if(!empty($now_category['cue_field'])){
				$cue_field = unserialize($now_category['cue_field']);
				$new_cue_field = array();

				foreach($cue_field as $key=>$value){
					if($value['name'] != $_POST['name']){
						array_push($new_cue_field,$value);
					}
				}
			}else{
				$this->error('此填写项不存在！');
			}
			$data_group_category['cue_field'] = serialize($new_cue_field);
			$data_group_category['cat_id'] = $now_category['cat_id'];
			if($database_group_category->data($data_group_category)->save()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	
	/* 添加 */
	public function cat_add(){
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}
	/* 执行添加 */
	public function cat_modify(){
		if(IS_POST){
			$database_appoint_category = D('Appoint_category');
			$_POST['create_time'] = time();
			$condition_cat['cat_url'] = $_POST['cat_url'];
                        
			if($_POST['is_autotrophic'] == 2){
				$phone_match='/^(0|86|17951)?(13[0-9]|15[0-9]|17[678]|18[0-9]|14[57])[0-9]{8}$/';
				$mobile_match='/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/';
				if(!$_POST['outsourced_phone']){
					$this->frame_submit_tips(0,'第三方联系号码不能为空！');
				}else if((!preg_match($phone_match, $_POST['outsourced_phone']))&&(!preg_match($mobile_match, $_POST['outsourced_phone']))){
					$this->frame_submit_tips(0,'第三方联系号码填写不正确！');
				}
			}else{
				$_POST['outsourced_phone'] = '';
			}


			$image = D('Image')->handle($this->system_session['id'], 'system', 0, array('size' => 10));
			if (!$image['error']) {
				$_POST = array_merge($_POST, str_replace('/upload/system/', '', $image['url']));
			}
			
			$cat_url = $database_appoint_category->field('`cat_url`')->where($condition_cat)->find();
			if(!empty($cat_url)){
				$this->error('短标记已存在！');
			} else {
				if( $database_appoint_category->data($_POST)->add() ){
				    $this->frame_submit_tips(1, '添加成功！');
				} else {
				    $this->frame_submit_tips(0, '添加失败！请重试~');
				}
			}
		} else {
		    $this->frame_submit_tips(0, '非法提交,请重新提交~');
		}
	}
	
	/* 编辑  */
	public function cat_edit(){
		$this->assign('bg_color','#F3F3F3');
		
		$database_appoint_category = D('Appoint_category');
		$database_now_appoint_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_appoint_category->field(true)->where($database_now_appoint_category)->find();
		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
                
		$this->assign('now_category', $now_category);
		$this->display();
	}
	/* 执行编辑 */
	public function cat_amend(){
		if(IS_POST){
			$database_group_category = D('Appoint_category');
			$image = D('Image')->handle($this->system_session['id'], 'system', 0, array('size' => 10));
			if (!$image['error']) {
				$_POST = array_merge($_POST, str_replace('/upload/system/', '', $image['url']));
			}
			
			$condition_where['cat_id'] = $_POST['cat_id'];
			$cat_info = $database_group_category->field(true)->where($condition_where)->find();
			if($cat_info['cat_url'] != $_POST['cat_url']){
				if($database_group_category->field(true)->where(array('cat_url'=>$_POST['cat_url']))->find()){
					$this->frame_submit_tips(0, '短标记已存在！');
				}
			}
			if($cat_info['cat_name'] != $_POST['cat_name']){
				if($database_group_category->field(true)->where(array('cat_name'=>$_POST['cat_name']))->find()){
					$this->frame_submit_tips(0, '分类名称已存在！');
				}
			}
                        
			if($_POST['is_autotrophic']==0){
				$_POST['pc_content'] = '';
				$_POST['wap_content'] = '';
			}

			if($_POST['is_autotrophic'] == 2){
				$phone_match = '/^((0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8})|((400|800)-(\d{3})-(\d{4}))$/';
				$mobile_match = '/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/';
				if(!$_POST['outsourced_phone']){
					$this->frame_submit_tips(0,'第三方入驻手机号码不能为空！');
				}else if(!$this->config['international_phone'] && (!preg_match($phone_match, $_POST['outsourced_phone'])) && (!preg_match($mobile_match, $_POST['outsourced_phone']))){
					$this->frame_submit_tips(0,'第三方入驻手机号码填写不正确！');
				}
			}else{
				$_POST['outsourced_phone'] = '';
			}

			if($database_group_category->data($_POST)->save()){
				$this->frame_submit_tips(1, '编辑成功！');
			}else{
				$this->frame_submit_tips(0, '编辑失败！请重试~');
			}
		}else{
			$this->frame_submit_tips(0, '非法提交,请重新提交~');
		}
	}
	
	/* 删除 */
	public function cat_del(){
		if(IS_POST){
			$database_appoint_category = D('Appoint_category');
			$condition_now_appoint_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_appoint_category->field(true)->where($condition_now_appoint_category)->find();
			if($database_appoint_category->where($condition_now_appoint_category)->delete()){
				if(empty($now_category['cat_fid'])){
					$condition_son_appoint_category['cat_fid'] = $now_category['cat_id'];
					$database_appoint_category->where($condition_son_appoint_category)->delete();
					$condition_appoint['cat_fid'] = $now_category['cat_id'];
				} else {
					$condition_appoint['cat_id'] = $now_category['cat_id'];
				}
				D('Appoint')->where($condition_appoint)->delete();
				$this->success('删除成功！');
			} else {
				$this->error('删除失败！请重试~');
			}
		} else {
			$this->error('非法提交,请重新提交~');
		}
	}
	
	/* 服务列表 */
	public function product_list(){
		//筛选
		if(!empty($_GET['keyword'])){
			if($_GET['searchtype'] == 'appoint_id'){
				$condition_where['appoint_id'] = intval($_GET['keyword']);
			} elseif($_GET['searchtype'] == 'appoint_name') {
				$condition_where['appoint_name'] = array('LIKE', '%'.$_GET['keyword'].'%');
			}
		}
		$database_appoint = D('Appoint');
		$database_merchant = D('Merchant');
		$database_category = D('Appoint_category');

        $condition_where['type'] = 1;
		$appoint_count = $database_appoint->where($condition_where)->count();
		
		import('@.ORG.system_page');
		$page = new Page($appoint_count, 20);
		$appoint_info = $database_appoint->field(true)->where($condition_where)->order('`sort`, `appoint_id` DESC')->limit($page->firstRow. ',' .$page->listRows)->select();
		$merchant_info = $database_merchant->field(true)->select();
		$category_info = $database_category->field(true)->select();
		$appoint_list = $this->formatArray($appoint_info, $merchant_info, $category_info);
		foreach($appoint_list as $key=>$val){
			if($appoint_list[$key]['category_name'] == null){
				$cat_where['cat_id'] = $val['cat_fid'];
				$category_name = $database_category->field('`cat_name`')->where($cat_where)->find();
				$appoint_list[$key]['category_name'] = implode('',$category_name);
			}
		}
		
		$this->assign('appoint_list', $appoint_list);
		$pagebar = $page->show();
		$this->assign('pagebar', $pagebar);
		$this->display();
	}
	
	/* 服务详情 */
	public function product_detail(){
		$this->assign('bg_color','#F3F3F3');
		
		$database_appoint_product = D('Appoint');
		$database_merchant = D('Merchant');
		$database_category = D('Appoint_category');
		$where['appoint_id'] = intval($_GET['appoint_id']);
		$appoint = $database_appoint_product->field(true)->where($where)->find();
		if(empty($appoint)){
			$this->frame_error_tips('没有找到该服务的信息！');
		}
		$where_mer['mer_id'] = $appoint['mer_id'];
		$mer_info = $database_merchant->field(true)->where($where_mer)->find();
		$appoint['mer_name'] = $mer_info['name'];
		if($appoint['cat_id'] != 0){
			$where_category['cat_id'] = $appoint['cat_id'];
		} else {
			$where_category['cat_id'] = $appoint['cat_fid'];
		}
		$category_info = $database_category->field(true)->where($where_category)->find();
		
		$appoint['cat_name'] = $category_info['cat_name'];
		$this->assign('appoint', $appoint);
		$this->display();
	}
	
	/*订单列表*/
	public function order_list(){
		$database_order = D('Appoint_order');
		$database_user = D('User');
		$database_appoint = D('Appoint');
		$database_store = D('Merchant_store');
		$_GET['appoint_id'] && $where['a.appoint_id'] = intval($_GET['appoint_id']);
		$where['a.mer_id'] = array('neq',0);
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'order_id') {
				$where['a.order_id'] = $_GET['keyword'];
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['a.order_id'] = $tmp_result['order_id'];
			}elseif ($_GET['searchtype'] == 'name') {
				$where['u.username'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['u.phone'] = htmlspecialchars($_GET['keyword']);
			}elseif ($_GET['searchtype'] == 'third_id') {
				$where['a.third_id'] =$_GET['keyword'];
			}
		}
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type){
			if($pay_type=='balance'){
				$where['_string'] = "(`a`.`balance_pay`<>0 OR `a`.`merchant_balance` <> 0 OR a.product_merchant_balance <> 0 OR a.product_balance_pay <> 0 )";
			}else{
				$where['a.pay_type'] = $pay_type;
			}
		}

		$_GET['appoint_id'] && $now_appoint = $database_appoint->field(true)->where(array('appoint_id'=>$_GET['appoint_id']))->find();
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] .= (empty($where['_string'])?'':' AND ')." (a.order_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}
		$order_count = $database_order->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
				->where($where)->count();

		import('@.ORG.system_page');
		$page = new Page($order_count, 20);
		$order_list = $database_order->field('a.*,ap.appoint_name,a.appoint_type,a.mer_id,u.uid,u.nickname,a.order_time,a.pay_time,u.phone,s.name as store_name,s.adress as store_adress')
				->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
				->where($where)->order('`order_id` DESC')->limit($page->firstRow.','.$page->listRows)->select();

		//商家信息
		$_GET['appoint_id'] &&$database_merchant = D('Merchant');
		$_GET['appoint_id'] &&$condition_merchant['mer_id'] = $now_appoint['mer_id'];

		$_GET['appoint_id'] &&$now_merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if($_GET['appoint_id'] && empty($now_merchant)){
				$this->error_tips('当前商家不存在！');
		}
		$this->assign('now_merchant', $now_merchant);

		$pagebar = $page->show();
		$pay_method = D('Config')->get_pay_method('','',1);
		$this->assign('pay_method',$pay_method);
		$this->assign('pay_type',$pay_type);
		$_GET['appoint_id'] && $this->assign('now_appoint', $now_appoint);
		$this->assign('pagebar', $pagebar);
		$this->assign('order_list', $order_list);
		$this->display();
	}
	
	/* 订单详情  */
    public function order_detail(){
		$this->assign('bg_color','#F3F3F3');
		
    	$database_order = D('Appoint_order');
    	$database_user = D('User');
    	$database_appoint = D('Appoint');
    	$database_store = D('Merchant_store');
        $database_appoint_category = D('Appoint_category');
        $database_merchant_store_staff = D('Merchant_store_staff');
		$database_appoint_product = D('Appoint_product');
    	$where['order_id'] = intval($_GET['order_id']);
    	
    	$now_order = $database_order->field(true)->where($where)->find();
    	$where_user['uid'] = $now_order['uid'];
    	$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where($where_user)->find();
    	$where_appoint['appoint_id'] = $now_order['appoint_id'];
    	$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->where($where_appoint)->find();
    	$where_store['store_id'] = $now_order['store_id'];
    	$store_info = $database_store->field('`store_id`, `name`, `adress`')->where($where_store)->find();
        $where_cat['cat_id'] = $now_order['cat_id'];
        $cat_info = $database_appoint_category->field('cat_name')->where($where_cat)->find();
    	
    	$now_order['phone'] = $user_info['phone'];
    	$now_order['nickname'] = $user_info['nickname'];
    	$now_order['appoint_name'] = $appoint_info['appoint_name'];
    	$now_order['appoint_type'] = $appoint_info['appoint_type'];
    	$now_order['appoint_price'] = $appoint_info['appoint_price'];
    	$now_order['store_name'] = $store_info['name'];
    	$now_order['store_adress'] = $store_info['adress'];
        $now_order['cat_name'] = $cat_info['cat_name'];
        $now_order['staff_name'] = $database_merchant_store_staff->where(array('id'=>$now_order['del_staff_id']))->getField('name');
    	$cue_info = unserialize($now_order['cue_field']);
    	$cue_list = array();
    	foreach($cue_info as $key=>$val){
    		if(!empty($cue_info[$key]['value'])){
    			$cue_list[$key]['name'] = $val['name'];
    			$cue_list[$key]['value'] = $val['value'];
    			$cue_list[$key]['type'] = $val['type'];
    			if($cue_info[$key]['type'] == 2){
    				$cue_list[$key]['long'] = $val['long'];
    				$cue_list[$key]['lat'] = $val['lat'];
    				$cue_list[$key]['address'] = $val['address'];
    			}
    		}
    	}
		$product_detail = $database_appoint_product->get_product_info($now_order['product_id']);
		if($product_detail['status']){
			$now_order['product_detail'] = $product_detail['detail'];
		}
    	$this->assign('cue_list', $cue_list);
    	$this->assign('now_order', $now_order);
    	$this->display();
    }
	
	/* 格式化服务列表数据 */
	public function formatArray($appoint_info, $merchant_info, $category_info){
		if(!empty($merchant_info)){
			$merchant_array = array();
			foreach($merchant_info as $val ){
				$merchant_array[$val['mer_id']]['mer_name'] = $val['name'];
			}
		}
		if(!empty($category_info)){
			$category_array = array();
			foreach($category_info as $val){
				$category_array[$val['cat_id']]['category_name'] = $val['cat_name'];
			}
		}
		if(!empty($appoint_info)){
			foreach($appoint_info as &$val ){
				$val['mer_name'] = $merchant_array[$val['mer_id']]['mer_name'];
				$val['category_name'] = $category_array[$val['cat_id']]['category_name'];
			}
		}
		return $appoint_info;
	}
	
	/* 格式化订单数据  */
    protected function formatOrderArray($order_info, $user_info, $appoint_info, $store_info){
    	if(!empty($user_info)){
    		$user_array = array();
    		foreach($user_info as $val){
    			$user_array[$val['uid']]['phone'] = $val['phone'];
    			$user_array[$val['uid']]['nickname'] = $val['nickname'];
    		}
    	}
    	if(!empty($appoint_info)){
    		$appoint_array = array();
    		foreach($appoint_info as $val){
    			$appoint_array[$val['appoint_id']]['appoint_name'] = $val['appoint_name'];
    			$appoint_array[$val['appoint_id']]['appoint_type'] = $val['appoint_type'];
    			$appoint_array[$val['appoint_id']]['appoint_price'] = $val['appoint_price'];
    		}
    	}
    	if(!empty($store_info)){
    		$store_array = array();
    		foreach($store_info as $val){
    			$store_array[$val['store_id']]['store_name'] = $val['name'];
    			$store_array[$val['store_id']]['store_adress'] = $val['adress'];
    		}
    	}
    	if(!empty($order_info)){
    		foreach($order_info as &$val){
    			$val['phone'] = $user_array[$val['uid']]['phone'];
    			$val['nickname'] = $user_array[$val['uid']]['nickname'];
    			$val['appoint_name'] = $appoint_array[$val['appoint_id']]['appoint_name'];
    			$val['appoint_type'] = $appoint_array[$val['appoint_id']]['appoint_type'];
    			$val['appoint_price'] = $appoint_array[$val['appoint_id']]['appoint_price'];
    			$val['store_name'] = $store_array[$val['store_id']]['store_name'];
    			$val['store_adress'] = $store_array[$val['store_id']]['store_adress'];
    		}
    	}
    	return $order_info;
    }
    
    
    //分类pc端编辑
    public function pc_cat_edit(){
        $database_appoint_category = D('Appoint_category');
        $where['cat_id'] = $_GET['cat_id'] + 0;
        if(IS_POST){
            $pc_content = $_POST['pc_content'];
            $pc_title = $_POST['pc_title'];
            foreach($pc_title as $k=>$v){
                if(empty($v)){
                    unset($pc_title[$k]);
                    continue;
                }
                if(!$pc_content[$k]){
                    $this->frame_submit_tips(0, '内容不能为空！');
                }
                
                $pc_title[$k] = htmlspecialchars_decode($v);
            }
            
            foreach($pc_content as $k=>$v){
                if(empty($v)){
                    unset($pc_content[$k]);
                    continue;
                }
                
                if(!$pc_title[$k]){
                    $this->frame_submit_tips(0, '标题不能为空！');
                }
                
                $pc_content[$k] = htmlspecialchars_decode($v);
            }
            
            $data['pc_content'] = $pc_content ? serialize($pc_content) : '';
            $data['pc_title'] = $pc_title ? serialize($pc_title) : '';
            
            $result = $database_appoint_category->appoint_category_edit($where,$data);
            
            if(!$result){
               $this->frame_submit_tips(0, '数据处理有误！');
            }else{
               $this->frame_submit_tips($result['status'], $result['msg']);
            }
        }else{
            $field = array('pc_content,pc_title');
            $detail = $database_appoint_category->appoint_category_detail($where,$field);
            if(!$detail){
                $this->frame_submit_tips(0, '数据处理有误！');
            }else{
                $this->assign('detail',$detail['detail']);
            }
            
            $this->display();
        }
    }
    
    //分类wap端编辑
    public function wap_cat_edit(){
        $database_appoint_category = D('Appoint_category');
        $where['cat_id'] = $_GET['cat_id'] + 0;
        if(IS_POST){
            $wap_title = $_POST['wap_title'];
            $wap_content = $_POST['wap_content'];
            
            foreach($wap_content as $k=>$v){
                if(empty($v)){
                    unset($wap_content[$k]);
                    continue;
                }
                
                if(!$wap_title[$k]){
                    $this->frame_submit_tips(0, '标题不能为空！');
                }
                
                $wap_content[$k] = htmlspecialchars_decode($v);
            }
            
            $data['wap_content'] = $wap_content ? serialize($wap_content) : '';
            
            foreach($wap_title as $k=>$v){
                if(empty($v)){
                    unset($wap_title[$k]);
                    continue;
                }
                
                if(!$wap_content[$k]){
                    $this->frame_submit_tips(0, '内容不能为空！');
                }
                
                $wap_title[$k] = htmlspecialchars_decode($v);
            }
            $data['wap_title'] = $wap_title ? serialize($wap_title) : '';
            $result = $database_appoint_category->appoint_category_edit($where,$data);

			$this->frame_submit_tips($result['status'], $result['msg']);
        }else{
            $field = array('wap_content,wap_title');
            $detail = $database_appoint_category->appoint_category_detail($where,$field);

            if(!$detail){
                $this->frame_submit_tips(0, '数据处理有误！');
            }else{
                $this->assign('detail',$detail['detail']);
            }
            
            $this->display();
        }
    }
    
    
    public function store_list(){
        //搜索
        if(!empty($_GET['keyword'])){
			if($_GET['searchtype'] == 'mer_id'){
					$condition_merchant['mer_id'] = $_GET['keyword'];
			}else if($_GET['searchtype'] == 'account'){
					$condition_merchant['account'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['searchtype'] == 'name'){
					$condition_merchant['name'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['searchtype'] == 'phone'){
					$condition_merchant['phone'] = array('like','%'.$_GET['keyword'].'%');
			}
        }
        $searchstatus = intval($_GET['searchstatus']);
        switch($searchstatus){
			case 0:
				$condition_merchant['status'] = 1;
				break;
			case 1:
				$condition_merchant['status'] = 2;
				break;
			case 2:
				$condition_merchant['status'] = 0;
				break;
        }


        if ($this->system_session['area_id']) {
			$now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
			if($now_area['area_type']==3){
				$area_index = 'area_id';
			}elseif($now_area['area_type']==2){
				$area_index = 'city_id';
			}elseif($now_area['area_type']==1){
				$area_index = 'province_id';
			}
			$condition_merchant[$area_index] = $this->system_session['area_id'];
        }

        $database_merchant = D('Merchant');
        $count_merchant = M('Merchant_workers_appoint')->join('AS a LEFT JOIN '.C('DB_PREFIX').'merchant AS m on a.mer_id =m.mer_id')->where($condition_merchant)->group('a.mer_id')->count();
        import('@.ORG.system_page');
        $p = new Page($count_merchant,15);
        $merchant_list = M('Merchant_workers_appoint')->field('m.*')->join('AS a LEFT JOIN '.C('DB_PREFIX').'merchant AS m on a.mer_id =m.mer_id')->where($condition_merchant)->group('a.mer_id')->order('`mer_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
        $this->assign('merchant_list',$merchant_list);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->display();
    }
    
    
        /*店铺管理*/
	public function store(){
		$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = intval($_GET['mer_id']);
		$merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if(empty($merchant)){
			$this->frame_submit_tips(0 , '数据库中没有查询到该商户的信息！');
		}
		$this->assign('merchant',$merchant);
		$condition_merchant['a.mer_id'] = $condition_merchant['mer_id'];
		unset($condition_merchant['mer_id']);
		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['a.mer_id'] = $merchant['mer_id'];
		$condition_merchant_store['m.status'] = array('neq',4);
		$count_store = M('Merchant_workers_appoint')->join('AS a LEFT JOIN '.C('DB_PREFIX').'merchant_store AS m on a.merchant_store_id =m.store_id')->where($condition_merchant)->group('a.merchant_store_id')->count();
		import('@.ORG.system_page');
		$p = new Page($count_store,15);
		$store_list = M('Merchant_workers_appoint')->field('m.*')->join('AS a LEFT JOIN '.C('DB_PREFIX').'merchant_store AS m on a.merchant_store_id =m.store_id')->where($condition_merchant)->group('a.merchant_store_id')->order('`sort` DESC,`store_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();

		$this->assign('store_list',$store_list);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		
		$this->display();
	}
        
        public function worker_list(){
			$store_id = $_GET['store_id'] + 0;
            if(!$store_id){
                $this->error_tips('传递参数有误！');
            }
            
            $database_merchant_workers = D('Merchant_workers');
            $database_merchant_store = D('Merchant_store');
            $where['merchant_store_id'] = $store_id;
            $worker_list = $database_merchant_workers->merchant_worker_list($where);
            $worker_list = $worker_list['list'];
            if(!$worker_list){
				$this->frame_submit_tips(0 , '数据处理有误！');
            }else{
                $this->assign('worker_list',$worker_list['list']);
            }
            
            $merchant_store = $database_merchant_store->get_store_by_storeId($store_id);
            $this->assign('merchant_store',$merchant_store);
            
            $this->display();
        }
        
        
        public function worker_time(){
			$database_merchant_workers = D('Merchant_workers');
			$database_appoint_order = D('Appoint_order');

			$worker_id = $_GET['worker_id'] + 0;
			if(!$worker_id){
				exit(json_encode(array('status'=>0)));
			}
	    
	    	// 预约开始时间 结束时间
			$merchant_workers_info = $database_merchant_workers->where(array('merchant_worker_id'=>$worker_id))->find();
			$office_time = unserialize($merchant_workers_info['office_time']);
		
			// 如果设置的营业时间为0点到0点则默认是24小时营业
			if(count($office_time)<1){
				$office_time[0]['open'] = '00:00';
				$office_time[0]['close'] = '24:00';
			}else{
				foreach ($office_time as $i=>$time){
					if($time['open'] == '00:00' && $time['close'] == '00:00'){
						unset($office_time[$i]);
					}
				}
			}

			// 发起预约时候的起始时间 还有提前多长时间可预约
			$beforeTime = $merchant_workers_info['before_time'] > 0 ? ($merchant_workers_info['before_time']) * 3600 : 0;
			$gap = $merchant_workers_info['time_gap'] * 60 > 0 ? $merchant_workers_info['time_gap'] * 60 : 1800;

			$startTime = strtotime(date('Y-m-d') . ' ' . $office_time['open']);
			$endTime = strtotime(date('Y-m-d') . ' ' . $office_time['close']);
			for ($time = $startTime; $time < $endTime; $time = $time + $gap) {
				$tempKey = date('H:i', $time) . '-' . date('H:i', $time + $gap);
				$tempTime[$tempKey]['time'] = $tempKey;
				$tempTime[$tempKey]['start'] = date('H:i', $time);
				$tempTime[$tempKey]['end'] = date('H:i', $time + $gap);
				$tempTime[$tempKey]['order'] = 'no';
				if ((date('H:i') < date('H:i', $time - $beforeTime))) {
					$tempTime[$tempKey]['order'] = 'yes';
				}
			}

			$startTimeAppoint = strtotime('now');
			$endTimeAppoint = strtotime('+3 day');
			$dateArray[date('Y-m-d',$startTimeAppoint)] = date('Y-m-d',$startTimeAppoint);
			$dateArray[date('Y-m-d',$endTimeAppoint)] = date('Y-m-d',$endTimeAppoint);
			for($date = $startTimeAppoint;$date < $endTimeAppoint;$date = $date + 86400){
				$dateArray[date('Y-m-d',$date)] = date('Y-m-d',$date);
			}

			ksort($dateArray);
			foreach ($dateArray as $i=>$date){
				$timeOrder[$date] = $tempTime;
			}

			ksort($timeOrder);
			//计算技师所有预约总数start
			$where['merchant_worker_id'] = $worker_id;
			$where['check_status'] = 1;
			$where['appoint_status'] = 0;
			$where['start_time'] = array('lt',time());
			$where['end_time'] = array('gt',time());
			$worker_count = D('Merchant_workers_appoint')->join( 'as m left join '.C('DB_PREFIX')."appoint `a` on `m`.`appoint_id`=`a`.`appoint_id`")->where($where)->count();
			//$appoint_people_num = $merchant_workers_info['appoint_people'] * $worker_count;
			$appoint_people_num = $worker_count;
			//计算技师所有预约总数end

			foreach($timeOrder as $i=>$tem){
				foreach ($tem as $key=>$temval)
					if(strtotime($i.' '.$temval['end'])<strtotime('now')+$beforeTime && ($temval['order'] == 'yes')){
						$timeOrder[$i][$key]['order'] = 'no';
					}elseif(strtotime($i.' '.$temval['end']) > strtotime('now') + $beforeTime + $gap && strtotime($i.' '.$temval['start']) > strtotime('now')+ $beforeTime && ($temval['order'] == 'no')){
						$timeOrder[$i][$key]['order'] = 'yes';
					}
			}

			// 查询可预约时间点
			$appoint_num = $database_appoint_order->get_worker_appoint_num(0,$worker_id);
			if(count($appoint_num)>0){
				foreach ($appoint_num as $val){
					$key = date('Y-m-d',strtotime($val['appoint_date']));
					if($timeOrder[$key][$val['appoint_time']]['order'] != 'no'){
						if(isset($timeOrder[$key]) && ($appoint_people_num == $val['appointNum'])){
							$timeOrder[$key][$val['appoint_time']]['order'] = 'all';
						}
					}
				}
			}

			$this->assign('timeOrder',$timeOrder);
			$this->display();
        }
        
        
        public function allot_merchant(){
			$order_id = $_GET['order_id'] + 0;
            if(!$order_id){
                $this->error_tips('传递参数有误！');
            }
            $where['order_id'] = $order_id;
            $database_appoint_order = D('Appoint_order');
            if(IS_POST){
                $mer_id = $_POST['mer_id'] + 0;
                if(!$mer_id){
                    $this->frame_submit_tips(0,'传递数据有误！');
                }
                $data['mer_id'] = $mer_id;
                $data['platform_allocation_time'] = time();
                $insert_id = $database_appoint_order->where($where)->data($data)->save();
                if($insert_id){
					$database_merchant = D('Merchant');
					$merchant = $database_merchant->where(array('mer_id' => $mer_id))->find();
					if((C('config.platform_merchant_sms_order') == 1) && !empty($merchant)){
						$sms_data = array('mer_id' => $merchant['mer_id'], 'store_id' => 0, 'type' => 'appoint');
						$sms_data['uid'] = 0;
						$sms_data['mobile'] = $merchant['phone'];
						$sms_data['sendto'] = 'merchant';

						$sms_data['content'] = '平台在'.date('Y-m-d H:i',time()).'为您分配新的预约订单， 订单号：'.$order_id.'，请注意查收！';
						Sms::sendSms($sms_data);
					}
                    $this->frame_submit_tips(1,'处理成功！');
                }else{
                    $this->frame_submit_tips(0,'处理失败！');
                }
            }else{
                $database_merchant = D('Merchant');
                $database_merchant_store = D('Merchant_store');
                $database_appoint_visit_order_info = D('Appoint_visit_order_info');
                $database_merchant_worker = D('Merchant_workers');
                
                $Map['order_id'] = $order_id;
                $appoint_order_info = $database_appoint_order->where($Map)->field('mer_id,store_id,cat_id,cat_fid,paid,is_del')->find();
                
                if($appoint_order_info){
                    $_Map['mer_id'] = $appoint_order_info['mer_id'];
                    $name = $database_merchant->where($_Map)->getField('name');
                    $appoint_order_info['merchant_name'] = $name ? $name : '';
                    
                    $_where['store_id'] = $appoint_order_info['store_id'];
                    $store_name = $database_merchant_store->where($_where)->getField('name');
                    $appoint_order_info['store_name'] = $store_name ? $store_name : '';
                    
                    $_condition['appoint_order_id'] = $order_id;
                    $merchant_worker_id = $database_appoint_visit_order_info->where($_condition)->getField('merchant_worker_id');
                    if($merchant_worker_id){
                        $condition['merchant_worker_id'] = $merchant_worker_id;
                        $merchant_worker = $database_merchant_worker->appoint_worker_info($condition,'name');
                        $appoint_order_info['worker_name'] = $merchant_worker['name'];
                    }
                }
                $this->assign('appoint_order_info',$appoint_order_info);
                $where = array();
                $where['cat_id'] = $appoint_order_info['cat_id'];
                $where['cat_fid'] = $appoint_order_info['cat_fid'];
                $_field = array('mer_id');
                $merchant_list = $database_merchant->merchant_list($where,$_field);
                if(!$merchant_list){
					$this->frame_submit_tips(0 , '数据处理有误！');
                }else{
                    $this->assign('merchant_list',$merchant_list['merchant_list']);
                }
                $this->display();
            }
        }
        
        
        //删除订单
        public function ajax_merchant_del(){
            $order_id = $_POST['order_id'] + 0;
            if(!$order_id){
                exit(json_encode(array('msg' => '传递参数有误！','status' => 0)));
            }
            
            $database_appoint_order = D('Appoint_order');
            $where['order_id'] = $order_id;
            $data['del_time'] = time();
            $data['is_del'] = 2;
            $result = $database_appoint_order->data($data)->where($where)->save();
            if($result){
                exit(json_encode(array('msg' => '取消成功！','status' => 1)));
            }else{
                exit(json_encode(array('msg' => '取消失败！','status' => 0)));
            }
        }
        
        
        public function appoint_dealer_list(){
            $database_order = D('Appoint_order');
            $database_user = D('User');
            $database_appoint = D('Appoint');
            $database_store = D('Merchant_store');
            $where['type'] = 1;
            $order_count = $database_order->where($where)->count();

            import('@.ORG.system_page');
            $page = new Page($order_count, 20);
            $order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->limit($page->firstRow.','.$page->listRows)->select();
            if(empty($order_info)){
				$this->frame_submit_tips(0 , '当前预约未产生订单！');
            }
            $user_info = $database_user->field('`uid`, `phone`, `nickname`')->select();
            $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
            $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
            $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
            $pagebar = $page->show();
            $this->assign('pagebar', $pagebar);
            $this->assign('order_list', $order_list);
            $this->display();
        }

        
       public function news_list(){
           $database_appoint_news = D('Appoint_news');
           $list = $database_appoint_news->appoint_news_page_list(true);
           if(!$list){
               $this->frame_submit_tips(0 , '数据处理有误！');
           }else{
               $this->assign('list',$list['list']);
           }
           $this->display();
       }
       
       public function news_add(){
           if(IS_POST){
               $database_appoint_news = D('Appoint_news');
               $result = $database_appoint_news->appoint_news_add($_POST);
               if(!$result){
                   $this->frame_submit_tips(0,'数据处理有误！');
               }else{
                   $this->frame_submit_tips($result['status'],$result['msg']);
               }
           }else{
               $this->display();
           }
       }
       
       
       public function news_del(){
            if(IS_POST){
				 $id = $_POST['id'] + 0;
				 if(!$id){
					 $this->frame_submit_tips(0 , '数据传递有误！');
				 }

				$where['id'] = $id;
				$database_appoint_news = D('Appoint_news');
				$result = $database_appoint_news->appoint_news_del($where);
				if(!$result){
					$this->frame_submit_tips(0 , '数据处理有误！');
				}else{
					$this->frame_submit_tips($result['status'] , $result['msg']);
				}
            }else{
                $this->frame_submit_tips(0 , '非法提交,请重新提交~');
            }
      	 }
       
       
       public function news_edit(){
           $id = $_GET['id'] + 0;
           if(!$id){
               $this->frame_submit_tips(0 , '传递参数有误！');
           }
           $where['id'] = $id;
           $database_appoint_news = D('Appoint_news');
           
           if(IS_POST){
               $result = $database_appoint_news->appoint_news_edit($where,$_POST);
               if(!$result){
                   $this->frame_submit_tips(0 , '数据处理有误！');
               }else{
                   $this->frame_submit_tips($result['status'],$result['msg']);
               }
           }else{
               $detail = $database_appoint_news->appoint_news_detail($where);
                if(!$detail){
                    $this->frame_submit_tips(0 , '数据处理有误！');
                }else{
                    $this->assign('detail',$detail['detail']);
                }
                $this->display();
           }
       }

	public function export()
	{
		$param = $_POST;
		$param['type'] = 'appoint';
		$param['rand_number'] = $_SERVER['REQUEST_TIME'];
		$param['system_session']['area_id'] = $this->system_session['area_id'] ;

        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
		die;
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '预约订单信息';
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
		$cacheSettings = array( 'dir' => './runtime' );
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		// 设置当前的sheet
		$_GET['appoint_id']  && $where['a.appoint_id'] = intval($_GET['appoint_id']);
		$where['a.mer_id'] = array('neq',0);
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'order_id') {
				$where['a.order_id'] = $_GET['keyword'];
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['a.order_id'] = $tmp_result['order_id'];
			}elseif ($_GET['searchtype'] == 'name') {
				$where['u.username'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['u.phone'] = htmlspecialchars($_GET['keyword']);
			}elseif ($_GET['searchtype'] == 'third_id') {
				$where['a.third_id'] =$_GET['keyword'];
			}
		}
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type){
			if($pay_type=='balance'){
				$where['_string'] = "(`a`.`balance_pay`<>0 OR `a`.`merchant_balance` <> 0 OR a.product_merchant_balance <> 0 OR a.product_balance_pay <> 0 )";
			}else{
				$where['a.pay_type'] = $pay_type;
			}
		}
		$_GET['appoint_id']  && $database_appoint = D('Appoint');
		$database_order = D('Appoint_order');
		$_GET['appoint_id']  && $now_appoint = $database_appoint->field(true)->where(array('appoint_id'=>$_GET['appoint_id']))->find();
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] .= (empty($where['_string'])?'':' AND ')." (a.order_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}
		$order_count = $database_order->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
				->where($where)->count();

		$length = ceil($order_count / 1000);
		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);

			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
			$objActSheet = $objExcel->getActiveSheet();

			$objActSheet->setCellValue('A1', '订单编号');
			$objActSheet->setCellValue('B1', '定金');
			$objActSheet->setCellValue('C1', '总价');
			$objActSheet->setCellValue('D1', '类型');
			$objActSheet->setCellValue('E1', '用户昵称');
			$objActSheet->setCellValue('F1', '手机号码');
			$objActSheet->setCellValue('G1', '订单状态');
			$objActSheet->setCellValue('H1', '服务状态');
			$objActSheet->setCellValue('I1', '平台余额支付');
			$objActSheet->setCellValue('J1', '商家会员卡支付');
			$objActSheet->setCellValue('K1', '在线支付金额');
			$objActSheet->setCellValue('L1', '下单时间');
			$objActSheet->setCellValue('M1', '支付时间');
			$objActSheet->setCellValue('N1', '支付方式');
			$order_list = $database_order->field('a.*,ap.appoint_name,a.appoint_type,a.mer_id,u.uid,u.nickname,a.order_time,a.pay_time,u.phone,s.name as store_name,s.adress as store_adress')
					->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
					->where($where)->limit(($i*1000).',1000')->order('`order_id` DESC')->select();
			$result_list = $order_list;

			if (!empty($result_list)) {
				$index = 2;
				foreach ($result_list as $value) {
					$objActSheet->setCellValueExplicit('A' . $index, $value['order_id']);
					if($value['product_id']>0){
						$objActSheet->setCellValueExplicit('B' . $index,floatval($value['product_payment_price']));
					}else{
						$objActSheet->setCellValueExplicit('B' . $index, floatval($value['payment_money']));
					}
					if($value['product_price']>0){
						$objActSheet->setCellValueExplicit('C' . $index,floatval($value['product_price']));
					}else{
						$objActSheet->setCellValueExplicit('C' . $index, floatval($value['appoint_price']));
					}
					if($value['type']==1){
						$objActSheet->setCellValueExplicit('D' . $index, '自营');
					}else{
						$objActSheet->setCellValueExplicit('D' . $index, '商家');
					}
					$objActSheet->setCellValueExplicit('E' . $index, $value['nickname'] . ' ');
					$objActSheet->setCellValueExplicit('F' . $index, $value['phone'] . ' ');
					if($value['paid']==0){
						$objActSheet->setCellValueExplicit('G' . $index, '未支付');
					}elseif($value['paid']==1){
						$objActSheet->setCellValueExplicit('G' . $index, '已支付');
					}elseif($value['paid']==2){
						$objActSheet->setCellValueExplicit('G' . $index, '已退款');
					}
					if($value['service_status']==1){
						$objActSheet->setCellValueExplicit('H' . $index, '未服务');
					}elseif($value['service_status']==2){
						$objActSheet->setCellValueExplicit('H' . $index, '已服务');
					}elseif($value['service_status']==3){
						$objActSheet->setCellValueExplicit('H' . $index, '已评价');
					}
					$objActSheet->setCellValueExplicit('I' . $index, floatval($value['balance_pay']));
					$objActSheet->setCellValueExplicit('J' . $index, floatval($value['merchant_balance']));
					$objActSheet->setCellValueExplicit('K' . $index, floatval($value['pay_money']));
					$objActSheet->setCellValueExplicit('L' . $index, $value['order_time'] ? date('Y-m-d H:i:s', $value['order_time']) : '');
					$objActSheet->setCellValueExplicit('M' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
					$objActSheet->setCellValueExplicit('N' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));


					$index++;
				}
			}
			sleep(2);
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}

           
}