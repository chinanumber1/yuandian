<?php
/*
 * 团购管理
 *
 */

class GroupAction extends BaseAction{
    public function index(){
		$database_group_category = D('Group_category');
		$condition_group_category['cat_fid'] = intval($_GET['cat_fid']);
		$condition_group_category['cat_status'] = array('neq','4');
		
		$count_group_category = $database_group_category->where($condition_group_category)->count();
		import('@.ORG.system_page');
		$p = new Page($count_group_category,50);
		$category_list = $database_group_category->field(true)->where($condition_group_category)->order('`cat_sort` DESC,`cat_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('category_list',$category_list);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);

		if($_GET['cat_fid']){
			$condition_now_group_category['cat_id'] = intval($_GET['cat_fid']);
			$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
			if(empty($now_category)){
				$this->error_tips('没有找到该分类信息！',3,U('Group/index'));
			}
			$this->assign('now_category',$now_category);
		}
		$this->display();
    }
	public function cat_add(){
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}
	public function cat_modify(){
		if(IS_POST){
			$database_group_category = D('Group_category');
			if($database_group_category->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function cat_edit(){
		$this->assign('bg_color','#F3F3F3');

		$database_group_category = D('Group_category');
		$condition_now_group_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		$this->assign('now_category',$now_category);
		$this->display();
	}
	public function cat_amend(){
		if(IS_POST){

			$image = D('Image')->handle($this->system_session['id'], 'system', 0, array('size' => 10));
			if (!$image['error']) {
				$_POST = array_merge($_POST, str_replace('/upload/system/', '', $image['url']));
				// $_POST ['cat_pic']=$_POST['pic'];
				//$_POST = array_merge($_POST, $image['url']);
			} else {
				//$this->frame_submit_tips(0, $image['msg']);
			}

			$database_group_category = D('Group_category');
			if($database_group_category->data($_POST)->save()){
				D('Image')->update_table_id('/upload/system/' . $_POST['cat_pic'], $_POST['cat_id'], 'group_category');
				$this->frame_submit_tips(1,'编辑成功！');
			}else{
				$this->frame_submit_tips(0,'编辑失败！请重试~');
			}
		}else{
			$this->frame_submit_tips(0,'非法提交,请重新提交~');
		}
	}
	public function cat_del(){
		if(IS_POST){
			$database_group_category = D('Group_category');
			$condition_now_group_category['cat_id'] = intval($_POST['cat_id']);
			$data_now_group_category['cat_status'] = '4';
			$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
			if($database_group_category->where($condition_now_group_category)->data($data_now_group_category)->save()){
				if(empty($now_category['cat_fid'])){
					$condition_son_group_category['cat_fid'] = $now_category['cat_id'];
					$database_group_category->where($condition_son_group_category)->data($data_now_group_category)->save();
					$condition_group['cat_fid'] = $now_category['cat_id'];
				}else{
					$condition_group['cat_id'] = $now_category['cat_id'];
				}
				$data_group['status'] = '4';
				D('Group')->where($condition_group)->data($data_group)->save();
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function cat_field(){
		$database_group_category = D('Group_category');
		$condition_now_group_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		if(!empty($now_category['cat_fid'])){
			$this->frame_error_tips('该分类不是主分类，无法使用商品字段功能！');
		}
		if(!empty($now_category['cat_field'])){
			$now_category['cat_field'] = unserialize($now_category['cat_field']);
			foreach($now_category['cat_field'] as $key=>$value){
				if($value['use_field'] == 'area'){
					$now_category['cat_field'][$key]['name'] = '区域(内置)';
					$now_category['cat_field'][$key]['url'] = 'area';
				}
				if($value['use_field'] == 'price'){
					$now_category['cat_field'][$key]['name'] = '价格(内置)';
					$now_category['cat_field'][$key]['url'] = 'area';
				}
			}
		}
		$this->assign('now_category',$now_category);

		$this->display();
	}
	public function cat_field_add(){
		$this->assign('bg_color','#F3F3F3');

		$this->display();
	}
	public function cat_field_modify(){
		if(IS_POST){
			$database_group_category = D('Group_category');
			$condition_now_group_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();

			if(!empty($now_category['cat_field'])){
				$cat_field = unserialize($now_category['cat_field']);
				foreach($cat_field as $key=>$value){
					if( (!empty($_POST['use_field']) && $value['use_field'] == $_POST['use_field']) || (!empty($_POST['url']) && $value['url'] == $_POST['url']) ){
						$this->error('字段已经添加，请勿重复添加！');
					}
				}
			}else{
				$cat_field = array();
			}
			if(count($cat_field) >= 5){
				$this->error('添加字段失败，最多5个自定义字段！');
			}
			if(empty($_POST['use_field'])){
				$post_data['name'] = $_POST['name'];
				$post_data['url'] = $_POST['url'];
				$post_data['value'] = explode(PHP_EOL,$_POST['value']);
				$post_data['type'] = $_POST['type'];

				//$post_data['sort'] = strval($_POST['sort']);
				//$post_data['status'] = strval($_POST['status']);
			}else{
				$post_data['use_field'] = $_POST['use_field'];

				//$post_data['sort'] = strval($_POST['sort']);
				//$post_data['status'] = strval($_POST['status']);
			}

			array_push($cat_field,$post_data);
			$data_group_category['cat_field'] = serialize($cat_field);
			$data_group_category['cat_id'] = $now_category['cat_id'];
			if($database_group_category->data($data_group_category)->save()){
				$this->success('添加字段成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function cue_field(){
		$database_group_category = D('Group_category');
		$condition_now_group_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		if(!empty($now_category['cat_fid'])){
			$this->frame_error_tips('该分类不是主分类，无法使用商品字段功能！');
		}
		if(!empty($now_category['cue_field'])){
			$now_category['cue_field'] = unserialize($now_category['cue_field']);
		}
		$this->assign('now_category',$now_category);

		$this->display();
	}
	public function cue_field_add(){
		$this->assign('bg_color','#F3F3F3');

		$this->display();
	}

	public function group_pass_array(){
		$this->check_group();
		$database_group_order = D('Group_order');
		$now_order = $database_group_order->where(array('order_id'=>$_GET['order_id']))->find();
		$pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
		$this->assign('pass_array',$pass_array);
		$this->assign('now_order',$now_order);
		$this->display();
	}

	public function cue_field_modify(){
		if(IS_POST){
			$database_group_category = D('Group_category');
			$condition_now_group_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();

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

			array_push($cue_field,$post_data);
			$data_group_category['cue_field'] = serialize($cue_field);
			$data_group_category['cat_id'] = $now_category['cat_id'];
			if($database_group_category->data($data_group_category)->save()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function cue_field_edit(){
		$database_group_category = D('Group_category');
		$condition_now_group_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		if(!empty($now_category['cat_fid'])){
			$this->frame_error_tips('该分类不是主分类，无法使用商品字段功能！');
		}
		if(!empty($now_category['cue_field'])){
			$now_category['cue_field'] = unserialize($now_category['cue_field']);
		}
		$now_cue = $now_category['cue_field'][$_GET['id']];
		$this->assign('now_cue',$now_cue);
		$this->assign('now_category',$now_category);

		$this->display();
	}
	public function cue_field_amend(){
		if(IS_POST){
			$database_group_category = D('Group_category');
			$condition_now_group_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();

			if(!empty($now_category['cue_field'])){
				$cue_field = unserialize($now_category['cue_field']);
				if(!empty($cue_field[$_POST['id']])){
					$post_data['name'] = $_POST['name'];
					$post_data['type'] = $_POST['type'];
					$post_data['sort'] = strval($_POST['sort']);
					$cue_field[$_POST['id']] = $post_data;
				}else{
					$this->error('此填写项不存在！');
				}
			}else{
				$this->error('此填写项不存在！');
			}
			foreach ($cue_field as $val){
				$sort[] = $val['sort'];
			}
			array_multisort($sort, SORT_DESC, $cue_field);
			$data_group_category['cue_field'] = serialize($cue_field);
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
	public function cue_field_del(){
		if(IS_POST){
			$database_group_category = D('Group_category');
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
	public function store_add(){
		$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = intval($_GET['mer_id']);
		$merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if(empty($merchant)){
			$this->frame_error_tips('数据库中没有查询到该商户的信息！无法添加店铺。',5);
		}
		$this->assign('merchant',$merchant);

		$this->assign('bg_color','#F3F3F3');

		$this->display();
	}
	public function store_modify(){
		if(IS_POST){
			$long_lat = explode(',',$_POST['long_lat']);
			$_POST['long'] = $long_lat[0];
			$_POST['lat'] = $long_lat[1];
			$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
			$_POST['add_from'] = '1';
			$database_merchant_store = D('Merchant_store');
			if($database_merchant_store->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}

	public function store_edit(){
		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['store_id'] = intval($_GET['store_id']);
		$store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
		if(empty($store)){
			$this->frame_error_tips('数据库中没有查询到该店铺的信息！',5);
		}
		$this->assign('store',$store);

		$this->assign('bg_color','#F3F3F3');

		$this->display();
	}

	public function store_amend(){
		if(IS_POST){
			$long_lat = explode(',',$_POST['long_lat']);
			$_POST['long'] = $long_lat[0];
			$_POST['lat'] = $long_lat[1];
			$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
			$database_merchant_store = D('Merchant_store');
			if($database_merchant_store->data($_POST)->save()){
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！请检查内容是否有过修改（必须修改）后重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function store_del(){
		if(IS_POST){
			$database_merchant_store = D('Merchant_store');
			$condition_merchant_store['store_id'] = intval($_POST['store_id']);
			$data_merchant_store['status'] = 4;
			if($database_merchant_store->where($condition_merchant_store)->data($data_merchant_store)->save()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	/*待商品列表*/
	public function wait_product(){
		//搜索
		$condition_where = "`gs`.`mer_id`=`g`.`mer_id` AND `g`.`status`='2' ";
		if ($this->system_session['area_id']) {
			$now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
			if($now_area['area_type']==3){
				$area_index = 'area_id';
			}elseif($now_area['area_type']==2){
				$area_index = 'city_id';
			}elseif($now_area['area_type']==1){
				$area_index = 'province_id';
			}
			$condition_where .= " AND `gs`.`{$area_index}`='{$this->system_session['area_id']}' ";
		}
		if(!empty($_GET['keyword'])){
			if($_GET['searchtype'] == 'group_id'){
				$condition_where .= " AND `g`.`group_id`=" . intval($_GET['keyword']);
			}else if($_GET['searchtype'] == 's_name'){
				$condition_where .= " AND `g`.`s_name` LIKE '%" . $_GET['keyword'] . "%'";
			}else if($_GET['searchtype'] == 'name'){
				$condition_where .= " AND `g`.`name` LIKE '%" . $_GET['keyword'] . "%'";
			}
		}
		//指定商家
		if(!empty($_GET['mer_id'])){
			$condition_where .= " AND `g`.`mer_id`=" . intval($_GET['mer_id']);
		}

		$condition_table  = array(C('DB_PREFIX').'group'=>'g', C('DB_PREFIX').'group_store'=>'gs');
		$condition_field  = '`g`.*';

		import('@.ORG.system_page');
		//$count_group = D('')->table($condition_table)->where($condition_where)->count('DISTINCT `g`.`group_id`');
		$count_group = D('Group')->join('as g LEFT JOIN '.C('DB_PREFIX').'merchant gs ON g.mer_id = gs.mer_id')->where($condition_where)->count();
		$p = new Page($count_group, 20);
		$group_list = D('Group')->field($condition_field)->join('as g LEFT JOIN '.C('DB_PREFIX').'merchant gs ON g.mer_id = gs.mer_id ')->where($condition_where)->order('`g`.`group_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
//		$group_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order('`g`.`group_id` DESC')->group('`g`.`group_id`')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('group_list', $group_list);

		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);

		$this->display();
	}
	/*商品管理*/
	public function product(){
		//本地优选判断
		if($_GET['wxapp_cat_id']){
			$now_groupwxapp_category = M('Group_wxapp_category')->where(array('cat_id'=>$_GET['wxapp_cat_id']))->find();
			$this->assign('now_groupwxapp_category',$now_groupwxapp_category);
		}
		
		//搜索
		$condition_where = "`gs`.`group_id`=`g`.`group_id`";
		if(empty($_GET['mer_id'])){
			$condition_where .= " AND `g`.`status`<>'2'";
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
			$condition_where .= " AND `gs`.`{$area_index}`='{$this->system_session['area_id']}' ";
		}
		if(!empty($_GET['keyword'])){
			switch($_GET['searchtype']){
				case 'group_id':
					$condition_where .= " AND `g`.`group_id`=" . intval($_GET['keyword']);
					break;
				case 's_name':
					$condition_where .= " AND `g`.`s_name` LIKE '%" . $_GET['keyword'] . "%'";
					break;
				case 'name':
					$condition_where .= " AND `g`.`name` LIKE '%" . $_GET['keyword'] . "%'";
					break;
			}
		}
		$condition_where .= ' AND `g`.`status`<>4';
		//指定商家
		if(!empty($_GET['mer_id'])){
			$condition_where .= " AND `g`.`mer_id`=" . intval($_GET['mer_id']);
		}

		if(!empty($_GET['searchstatus'])){
			$now_time = $_SERVER['REQUEST_TIME'];
			switch($_GET['searchstatus']){
				case '1':
					$condition_where .= " AND `g`.`status`='1'  AND `g`.`type`='1' AND `g`.`begin_time`<'$now_time' AND `g`.`end_time`>'$now_time'";
					break;
				case '2':
					$condition_where .= " AND (`g`.`status`<>'1' OR `g`.`type`<>'1' OR `g`.`begin_time`>'$now_time' OR `g`.`end_time`<'$now_time')";
					break;
			}
		}
		$condition_table  = array(C('DB_PREFIX').'group'=>'g', C('DB_PREFIX').'group_store'=>'gs');
		$condition_field  = '`g`.*,`gs`.*';
		
		//本地优选判断
		if($now_groupwxapp_category){
			$condition_field .= ',`gwp`.`product_price`';
			$condition_table[C('DB_PREFIX').'group_wxapp_product'] = 'gwp';
			$condition_where.= "AND `gwp`.`group_id`=`g`.`group_id` AND `gwp`.`cat_id`='{$now_groupwxapp_category['cat_id']}'";
		}
		
		import('@.ORG.system_page');
		$count_group = D('')->table($condition_table)->where($condition_where)->count('DISTINCT `g`.`group_id`');
		$p = new Page($count_group, 20);
		$group_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order('`g`.`group_id` DESC')->group('`g`.`group_id`')->limit($p->firstRow.','.$p->listRows)->select();
		// dump(D(''));
		
		
		$this->assign('group_list', $group_list);

		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);

		$this->display();
	}
	/*商品编辑*/
	public function group_edit(){

		$this->display();
	}
	/*订单列表*/
	public function order_list(){
		//团购信息
		$database_group = D('Group');
		$condition_group['group_id'] = $_GET['group_id'];
		$now_group = $database_group->field(true)->where($condition_group)->find();
		if(empty($now_group)){
			$this->error_tips('当前'.$this->config['group_alias_name'].'不存在！');
		}
		$this->assign('now_group',$now_group);

		//商家信息
		$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = $now_group['mer_id'];
		$now_merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if(empty($now_merchant)){
			$this->error_tips('当前'.$this->config['group_alias_name'].'所属的商家不存在！');
		}
		$this->assign('now_merchant',$now_merchant);

		//订单列表
		$group_id = $now_group['group_id'];
		$condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `o`.`paid`='1' AND `o`.`group_id`='$group_id'";
		$condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'group_order'=>'o',C('DB_PREFIX').'user'=>'u');

		$order_count = D('')->where($condition_where)->table($condition_table)->count();
		import('@.ORG.system_page');
		$p = new Page($order_count,30);

		$order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order('`o`.`add_time` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		if(empty($order_list)){
			$this->error_tips('当前'.$this->config['group_alias_name'].'并未产生订单！');
		}
		$this->assign('order_list',$order_list);

		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);

		$this->display();
	}
	/*操作订单*/
	public function order_detail(){
		$this->assign('bg_color','#F3F3F3');

		$database_group_order = D('Group_order');
		if(strlen($_GET['order_id'])>=20){
			$condition_group_order['real_orderid'] = $_GET['order_id'];
		}else{
			$condition_group_order['order_id'] = $_GET['order_id'];
		}
		$order = $database_group_order->field('`order_id`,`mer_id`')->where($condition_group_order)->find();
		$now_order = $database_group_order->get_order_detail_by_id_and_merId($order['mer_id'],$order['order_id'],false);
		
		if(empty($now_order)){
			$this->frame_error_tips('此订单不存在！');
		}
		if($now_order['store_id']){
			$now_store = D('Merchant_store')->field('`name`')->where(array('store_id'=>$now_order['store_id']))->find();
			$now_order['store_name'] = $now_store['name'];
		}
		$pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
		$this->assign('pass_array',$pass_array);
		//if(!empty($now_order['coupon_id'])) {
		//	$system_coupon = D('System_coupon')->get_coupon_info($now_order['coupon_id']);
		//	$now_order['coupon_price'] = $system_coupon['price'];
		//	$this->assign('system_coupon',$system_coupon);
		//}else if(!empty($now_order['card_id'])) {
		//	$card = D('Member_card_coupon')->get_coupon_info($now_order['card_id']);
		//	$now_order['coupon_price'] = $card['price'];
		//	$this->assign('card', $card);
		//}

		if($now_order['refund_detail']){
		
			$now_order['refund_detail'] = unserialize($now_order['refund_detail']);

		}

		if($now_order['trade_info']){
			$trade_info_arr = unserialize($now_order['trade_info']);
			if($trade_info_arr['type'] == 'hotel'){
				$trade_hotel_info = D('Trade_hotel_category')->format_order_trade_info($now_order['trade_info']);
				$this->assign('trade_hotel_info',$trade_hotel_info);
			}
		}
		
		$this->assign('now_order',$now_order);
		$this->display();
	}
	/*评论列表*/
	public function reply_list(){
		//团购信息
		$database_group = D('Group');
		$condition_group['group_id'] = $_GET['group_id'];
		$now_group = $database_group->field(true)->where($condition_group)->find();
		if(empty($now_group)){
			$this->error_tips('当前'.$this->config['group_alias_name'].'不存在！');
		}
		$this->assign('now_group',$now_group);

		//商家信息
		$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = $now_group['mer_id'];
		$now_merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if(empty($now_merchant)){
			$this->error_tips('当前'.$this->config['group_alias_name'].'所属的商家不存在！');
		}
		$this->assign('now_merchant',$now_merchant);

		//评论列表
		$group_id = $now_group['group_id'];
		$table = array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'group_order'=>'o');
		$condition = "`r`.`order_type`='0' AND `r`.`order_id`=`o`.`order_id` AND `o`.`group_id`='$group_id'";

		$reply_count = D('')->table($table)->where($condition)->count();
		import('@.ORG.system_page');
		$p = new Page($reply_count,20);

		$reply_list = D('')->table($table)->where($condition)->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('reply_list',$reply_list);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);

		$this->display();
	}
	public function reply_detail(){
		$this->assign('bg_color','#F3F3F3');

		$pigcms_id = $_GET['id'];
		$table = array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'group_order'=>'o');
		$condition = "`r`.`order_type`='0' AND `r`.`order_id`=`o`.`order_id` AND `r`.`pigcms_id`='$pigcms_id'";
		$reply_detail = D('')->table($table)->where($condition)->find();

		if(empty($reply_detail)){
			$this->frame_error_tips('该评论不存在！');
		}
		$this->assign('reply_detail',$reply_detail);

		if($reply_detail['pic']){
			$reply_image_class = new reply_image();
			$image_list = $reply_image_class->get_image_by_id($reply_detail['order_id'],0);
			$this->assign('image_list',$image_list);
		}

		$this->display();
	}
	public function reply_del(){
		$database_reply = D('Reply');
		$condition_reply['pigcms_id'] = $_POST['id'];
		$now_reply = $database_reply->field(true)->where($condition_reply)->find();
		if(empty($now_reply)){
			$this->frame_error_tips('该评论不存在！');
		}
		if($database_reply->where($condition_reply)->delete()){
			if($now_reply['pic']){
				$reply_image_class = new reply_image();
				$reply_image_class->del_image_by_id($now_reply['order_id'],0);
			}
			//减少团购一个评论数
			$database_group = D('Group');
			$condition_group['group_id'] = $now_reply['parent_id'];
			$database_group->where($condition_group)->setDec('reply_count');

			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}

	public function order()
	{
		$condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `m`.`mer_id`=`o`.`mer_id`";
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$condition_where .= " AND `o`.`real_orderid`='" . htmlspecialchars($_GET['keyword'])."'";
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
				$condition_where .= " AND `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
			} elseif ($_GET['searchtype'] == 'name') {
				$condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			} elseif ($_GET['searchtype'] == 'phone') {
				$condition_where .= " AND `u`.`phone`='" . htmlspecialchars($_GET['keyword']) . "'";
			} elseif ($_GET['searchtype'] == 's_name') {
				$condition_where .= " AND `g`.`s_name` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}elseif ($_GET['searchtype'] == 'third_id') {
				$condition_where .= " AND `o`.`third_id` ='".$_GET['keyword']."'";
			} elseif ($_GET['searchtype'] == 'm_name') {
				$condition_where .= " AND `m`.`name` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}
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
			$condition_where .= " AND `m`.`{$area_index}`={$this->system_session['area_id']}";
		}

		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		if($status==8){
			$condition_where .=' AND paid=0';
		}

		if($status==0){
			$condition_where .=' AND paid=1';
		}
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= 'o.' . $type . ' ' . $sort . ',';
			$order_sort .= 'o.order_id DESC';
		} else {
			$order_sort .= 'o.order_id DESC';
		}

		if ($status != -1 && $status!=8) {
			if($status == '1'){
				$condition_where .= " AND (`o`.`status`='1' OR `o`.`status`='2')";
			}else{
				$condition_where .= " AND `o`.`status`={$status}";
			}
		}
		if($pay_type){
			if($pay_type=='balance'){
				$condition_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 ) AND `o`.`paid` = 1";
			}else{
				$condition_where .= " AND `o`.`pay_type`='{$pay_type}'";
			}
		}

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}

			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$condition_where .= " AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
			//$condition_where['_string']=$time_condition;
		}

		$condition_table = array(C('DB_PREFIX').'group'=>'g', C('DB_PREFIX').'group_order'=>'o', C('DB_PREFIX').'user'=>'u', C('DB_PREFIX').'merchant'=>'m');
		$order_count = D('')->where($condition_where)->table($condition_table)->count();
		import('@.ORG.system_page');
		$p = new Page($order_count,30);

		$order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`g`.`price` as g_price,`u`.`uid`,`u`.`nickname`,`u`.`phone`,`m`.`phone` as m_phone,`m`.`name` as m_name,`m`.`mer_id`,`g`.`group_id`')->where($condition_where)->table($condition_table)->order($order_sort)->limit($p->firstRow.','.$p->listRows)->select();
		
		//下单总金额统计
		$return = D('')->field('sum(total_money) AS total_price, sum(total_money - card_price - merchant_balance - balance_pay - payment_money - score_deducte - coupon_price - card_give_money) AS offline_price, sum(card_price + merchant_balance + balance_pay + payment_money + score_deducte + coupon_price + card_give_money) AS online_price')->where($condition_where)->table($condition_table)->find();
		
		$pay_method = D('Config')->get_pay_method('','',1);
		$this->assign('pay_method',$pay_method);
		$this->assign('order_list',$order_list);
		$this->assign($return);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status,'pay_type'=>$pay_type));
		$status_list = D('Group_order')->status_list;
		$status_list[2] = '已评论';
		$this->assign('status_list', $status_list);

		$this->display();
	}



	public function refund_update(){
		$database_group_order = D('Group_order');
		$status = 3;
		$_GET['status'] && $status = $_GET['status'];
		if($status==3){
			$status_txt =  '已退款';
		}else if($status==4){
			$status_txt =  '已取消';

		}
		$condition_group_order['order_id'] = $_GET['order_id'];
		$order = $database_group_order->field('`order_id`,`mer_id`')->where($condition_group_order)->find();
		$now_order = $database_group_order->get_order_detail_by_id_and_merId($order['mer_id'],$order['order_id'],false);
		if(empty($now_order)){
			$this->error_tips('此订单不存在！');
		}
		$save_date['status'] = $status;
		$save_date['last_time'] = time();
		if($database_group_order->where($condition_group_order)->save($save_date)){
			$this->success("订单状态已改为{$status_txt}！");
		}else{
			$this->error_tips('订单状态改变失败！');
		}
	}

	public function export1()
	{
		set_time_limit(0);
		header("Content-type:text/html;charset=utf-8");
		$title = '团购订单信息';

		// 设置当前的sheet
		$condition_where = "WHERE 1=1";
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$condition_where .= " AND `o`.`real_orderid`='" . htmlspecialchars($_GET['keyword'])."'";
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
				$condition_where .= " AND  `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
			} elseif ($_GET['searchtype'] == 'name') {
				$condition_where .= " AND  `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			} elseif ($_GET['searchtype'] == 'phone') {
				$condition_where .= " AND `u`.`phone`='" . htmlspecialchars($_GET['keyword']) . "'";
			} elseif ($_GET['searchtype'] == 's_name') {
				$condition_where .= " AND `g`.`s_name` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}elseif ($_GET['searchtype'] == 'third_id') {
				$condition_where .= " AND `o`.`third_id` ='".$_GET['keyword']."'";
			}
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
			$condition_where .= " AND `m`.`{$area_index}`={$this->system_session['area_id']}";
		}

		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= 'o.' . $type . ' ' . $sort . ',';
			$order_sort .= 'o.order_id DESC';
		} else {
			$order_sort .= 'o.order_id DESC';
		}

		if ($status != -1) {
			$condition_where .= " AND `o`.`status`={$status}";
		}
		if($pay_type){
			if($pay_type=='balance'){
				$condition_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )";
			}else{
				$condition_where .= " AND `o`.`pay_type`='{$pay_type}'";
			}
		}

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$condition_where .= " AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
			//$condition_where['_string']=$time_condition;
		}


		$sql = "SELECT count(order_id) as count FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC ";
		$count = D()->query($sql);

		$length = ceil($count[0]['count'] / 1000);

		//$fp = fopen('php://output', 'a');

		$head=array('订单编号', '商家名称', '客户姓名', '客户电话', '订单总价', '平台余额', '商家余额', '在线支付金额', '平台'.$this->config['score_name'], '平台优惠券', '商家优惠券', '商家折扣', '支付时间', '订单状态', '支付情况');
		//foreach ($head as $i => $v) {
		//	// CSV的Excel支持GBK编码，一定要转换，否则乱码
		//	$head[$i] = iconv('utf-8', 'gbk', $v);
		//}
		//fputcsv($fp, $head);
		for ($i = 0; $i < $length; $i++) {
			ob_flush();
			flush();
			$sql = "SELECT o.*, m.name AS merchant_name,u.nickname as username FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";
			$result_list = D()->query($sql);

			if (!empty($result_list)) {
				foreach ($result_list as $value) {
					//foreach ($value as $va) {
						$tmp[] = $value['real_orderid'];
						$tmp[] = $value['merchant_name'];
						$tmp[] = $value['username'] . ' ';
						$tmp[] = $value['phone'] . ' ';
						$tmp[] = floatval($value['total_money']);
						$tmp[] = floatval($value['balance_pay']);
						$tmp[] = floatval($value['merchant_balance']);
						$tmp[] = floatval($value['payment_money']);
						$tmp[] = floatval($value['score_reducte']);
						$tmp[] = floatval($value['coupon_price']);
						$tmp[] = floatval($value['card_price']);
						$tmp[] = floatval($value['card_discount'])?floatval($value['card_discount']). '折':'';
						$tmp[] = $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '';
						$tmp[] = $this->get_order_status($value);
						$tmp[] = D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']);
					//}

					//fputcsv($fp, $tmp);
					$str_tmp = implode(',',$tmp).PHP_EOL;
					unset($tmp);
				}
			}
			sleep(2);
		}
		//输出

		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=".$title.'_' . date("Y-m-d h:i:sa", time()) . '.csv"');
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $str_tmp;
		exit();
	}

	public function export()
	{
		$param = $_POST;
		$param['type'] = 'group';
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
		$title = '团购订单信息';
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
		$condition_where = "WHERE 1=1";
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$condition_where .= " AND `o`.`real_orderid`='" . htmlspecialchars($_GET['keyword'])."'";
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
				$condition_where .= " AND  `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
			} elseif ($_GET['searchtype'] == 'name') {
				$condition_where .= " AND  `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			} elseif ($_GET['searchtype'] == 'phone') {
				$condition_where .= " AND `u`.`phone`='" . htmlspecialchars($_GET['keyword']) . "'";
			} elseif ($_GET['searchtype'] == 's_name') {
				$condition_where .= " AND `g`.`s_name` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}elseif ($_GET['searchtype'] == 'third_id') {
				$condition_where .= " AND `o`.`third_id` ='".$_GET['keyword']."'";
			}elseif ($_GET['searchtype'] == 'm_name') {
				$condition_where .= " AND `m`.`name` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}
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
			$condition_where .= " AND `m`.`{$area_index}`={$this->system_session['area_id']}";
		}

		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= 'o.' . $type . ' ' . $sort . ',';
			$order_sort .= 'o.order_id DESC';
		} else {
			$order_sort .= 'o.order_id DESC';
		}

		if ($status != -1) {
			$condition_where .= " AND `o`.`status`={$status}";
		}
		if($pay_type){
			if($pay_type=='balance'){
				$condition_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )";
			}else{
				$condition_where .= " AND `o`.`pay_type`='{$pay_type}'";
			}
		}

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$condition_where .= " AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
			//$condition_where['_string']=$time_condition;
		}


		$sql = "SELECT count(order_id) as count FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC ";
		$count = D()->query($sql);

		$length = ceil($count[0]['count'] / 1000);
		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);

			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
			$objActSheet = $objExcel->getActiveSheet();

			$objActSheet->setCellValue('A1', '订单编号');
			$objActSheet->setCellValue('B1', '商家名称');
			$objActSheet->setCellValue('C1', '客户姓名');
			$objActSheet->setCellValue('D1', '客户电话');
			$objActSheet->setCellValue('E1', '订单总价');
			$objActSheet->setCellValue('F1', '平台余额');
			$objActSheet->setCellValue('G1', '商家余额');
			$objActSheet->setCellValue('H1', '在线支付金额');
			$objActSheet->setCellValue('I1', '平台'.$this->config['score_name']);
			$objActSheet->setCellValue('J1', '平台优惠券');
			$objActSheet->setCellValue('K1', '商家优惠券');
			$objActSheet->setCellValue('L1', '商家折扣');
			$objActSheet->setCellValue('M1', '支付时间');
			$objActSheet->setCellValue('N1', '订单状态');
			$objActSheet->setCellValue('O1', '支付情况');
			$sql = "SELECT o.*, m.name AS merchant_name,u.nickname as username FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";
			$result_list = D()->query($sql);

			if (!empty($result_list)) {
				$index = 2;
				foreach ($result_list as $value) {
					$objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
					$objActSheet->setCellValueExplicit('B' . $index, $value['merchant_name']);
					$objActSheet->setCellValueExplicit('C' . $index, $value['username'] . ' ');
					$objActSheet->setCellValueExplicit('D' . $index, $value['phone'] . ' ');
					$objActSheet->setCellValueExplicit('E' . $index, floatval($value['total_money']));
					$objActSheet->setCellValueExplicit('F' . $index, floatval($value['balance_pay']));
					$objActSheet->setCellValueExplicit('G' . $index, floatval($value['merchant_balance']));
					$objActSheet->setCellValueExplicit('H' . $index, floatval($value['payment_money']));
					$objActSheet->setCellValueExplicit('I' . $index, floatval($value['score_reducte']));
					$objActSheet->setCellValueExplicit('J' . $index, floatval($value['coupon_price']));
					$objActSheet->setCellValueExplicit('K' . $index, floatval($value['card_price']));
					$objActSheet->setCellValueExplicit('L' . $index, floatval($value['card_discount'])?floatval($value['card_discount']). '折':'');
					$objActSheet->setCellValueExplicit('M' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
					$objActSheet->setCellValueExplicit('N' . $index, $this->get_order_status($value));
					$objActSheet->setCellValueExplicit('O' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));


					$index++;
				}
			}
			sleep(2);
		}
		//输出
		ob_end_clean();
		//$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		$filename   = $title.'_' . date("Y-m-d", time()) . '.xls';
		$objWriter  = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');

		$objWriter->save('./runtime/'.iconv("utf-8", "gb2312", $filename));

//		header("Pragma: public");
//		header("Expires: 0");
//		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
//		header("Content-Type:application/force-download");
//		header("Content-Type:application/vnd.ms-execl");
//		header("Content-Type:application/octet-stream");
//		header("Content-Type:application/download");
//		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
//		header("Content-Transfer-Encoding:binary");
//		$objWriter->save('php://output');
		exit();
	}

	public function get_order_status($order){
		$status = '';
		if($order['paid']){
			if($order['pay_type']=='offline' && empty($order['third_id'])&& $order['status'] == 0){
				$status='线下支付，未付款';
			}elseif($order['status']==0){
				$status='已付款';
				if($order['tuan_type'] != 2){
					$status.='已付款';
				}else{
					if($order['is_pick_in_store']){
						$status.='未取货';
					}else{
						$status.='未发货';
					}
				}
			}elseif($order['status']==1){
				if($order['tuan_type'] != 2){
					$status='已消费';
				}else{
					if($order['is_pick_in_store']){
						$status='已取货';
					}else{
						$status='已发货';
					}
				}
				$status.='待评价';
			}elseif($order['status']==2){
				$status='已完成';
			}elseif($order['status']==3){
				$status='已退款';
			}elseif($order['status']==4){
				$status='已取消';
			}
		}else{
			if($status==4){
				$status='已取消';
			}else{
				$status='未付款';
			}
		}

		return $status;
	}

	//直接通过审核
	public function verify(){
		M('Group')->where(array('group_id'=>$_GET['group_id']))->setField('status',1);
		$this->success('审核成功！');
	}

	public function main_page(){

		$where['type'] = $_GET['type']?$_GET['type']:$this->config['group_main_page_center_type'];
		$slider_list = M('Group_main_page_pic_slider')->where($where)->order('sort DESC')->select();
		$this->assign('slider_list',$slider_list);
		$this->display();
	}

	//添加团购首页中部导航图文
	public function add_group_center_type_img(){

		if(IS_POST){
			$where['type'] = $_POST['type'];
			$count = M('Group_main_page_pic_slider')->where($where)->count();
			if($_FILES['pic']['error'] != 4){
				$image = D('Image')->handle($this->system_session['id'], 'slider');
				if (!$image['error']) {
					$_POST = array_merge($_POST, str_replace('/upload/slider/', '', $image['url']));
				} else {
					$this->frame_submit_tips(0, $image['message']);
				}
			}
			$data['name'] = $_POST['name'];
			$data['sub_name'] = $_POST['sub_name'];
			$data['url'] = $_POST['url'];
			$data['last_time'] = $_SERVER['REQUEST_TIME'];
			$_FILES['pic']['error'] != 4 && $data['pic']    = $_POST['pic'];
			$data['sort']    = $_POST['sort'];
			$data['type']    = $_POST['type'];

			if($_POST['id']){
				$result = M('Group_main_page_pic_slider')->where(array('id'=>$_POST['id']))->save($data);

			}else{
				if($count==abs($_POST['type'])){
					$this->error_tips(abs($_POST['type']).'图模式只能添加'.abs($_POST['type']).'个');
				}
				$result = M('Group_main_page_pic_slider')->add($data);

			}

			if($result){
				$this->frame_submit_tips(1,'保存成功！');
			}else{
				$this->frame_submit_tips(0,'保存失败！请重试~');
			}
		}else{
			if($_GET['id']){
				$where['id'] =  $_GET['id'];
				$slider = M('Group_main_page_pic_slider')->where($where)->find();
				$this->assign('slider',$slider);
			}
			$this->display();
		}
	}

	public function image_show(){
		$this->display();
	}
}