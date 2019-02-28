<?php
/*
 * 订餐管理
 *
 */

class MealAction extends BaseAction{
	
	public function __construct() {
		parent::__construct();
		
		if (!isset($this->config['no_foodshop']) && ACTION_NAME != 'order_detail') {
			redirect(U('Foodshop/index'));
			exit;
		}
	}
	/* 店铺管理 */
	public function index(){
		$mer_id = $this->merchant_session['mer_id'];
		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['mer_id'] = $mer_id;
		$condition_merchant_store['have_meal'] = '1';
		$condition_merchant_store['status'] = '1';
		$count_store = $database_merchant_store->where($condition_merchant_store)->count();
		
		$db_arr = array(C('DB_PREFIX').'area'=>'a',C('DB_PREFIX').'merchant_store'=>'s');
		import('@.ORG.merchant_page');
		$p = new Page($count_store,30);
		$store_list = D()->table($db_arr)->field(true)->where("`s`.`mer_id`='$mer_id' AND `s`.`status`='1' AND `s`.`have_meal`='1' AND `s`.`area_id`=`a`.`area_id`")->order('`sort` DESC,`store_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('store_list',$store_list);
		
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);

		$this->display();
	}
	
	/* 店铺信息修改 */
	public function store_edit(){
		$now_store = $this->check_store($_GET['store_id']);
		
		if(IS_POST){

			$deliver_time = array();
			foreach($_POST['deliver_time'] as $key=>$value){
				if($value['start'] != '00:00' || $value['stop'] != '00:00'){
					array_push($deliver_time,$value);
				}
			}
			$_POST['delivery_fee_valid'] = isset($_POST['delivery_fee_valid']) ? intval($_POST['delivery_fee_valid']) : 0;
			if(is_array($deliver_time)){
				$_POST['deliver_time'] = serialize($deliver_time);
			}else{
				$_POST['deliver_time'] = '';
			}
			$_POST['store_id'] = $now_store['store_id'];
			if(substr($_POST['store_notice'],-1) == ' '){
				$_POST['store_notice'] = trim($_POST['store_notice']);
			}else{
				$_POST['store_notice'] = trim($_POST['store_notice']);
			}
			if(empty($_POST['store_category'])){
				$this->error('请至少选一个分类！');
			}

			$_POST['store_labels'] = serialize($_POST['store_labels']);
			
			$leveloff=isset($_POST['leveloff']) ? $_POST['leveloff'] :false;
			unset($_POST['leveloff']);
// 			$database_meal_category = D('Meal_store_category');
// 			$condition_meal_category['cat_id'] = array('in',implode(',',$_POST['store_category']));
// 			$category_list = $database_meal_category->field(true)->where($condition_meal_category)->order('`cat_sort` DESC,`cat_id` ASC')->select();
// 			$category_txt_arr = array();
// 			foreach($category_list as $key=>$value){
// 				array_push($category_txt_arr,$value['cat_name']);
// 			}
			
// 			$_POST['cat_info'] = implode(' ',$category_txt_arr);
			$cat_ids = array();
			foreach ($_POST['store_category'] as $cat_a) {
				$a = explode('-', $cat_a);
				$cat_ids[] = array('cat_fid' => $a[0], 'cat_id' => $a[1]);
			}

			$newleveloff=array();
			if(!empty($leveloff)){
			   foreach($leveloff as $kk=>$vv){
				   $vv['type']=intval($vv['type']);
				   $vv['vv']=intval($vv['vv']);
			      if(($vv['type'] >0) && ($vv['vv']>0)){
				    $vv['level']=$kk;
					$newleveloff[$kk]=$vv;
				  }
			   }
			}
		
			$_POST['store_discount'] = isset($_POST['store_discount']) ? intval($_POST['store_discount']) : 0;
			$_POST['discount_type'] = isset($_POST['discount_type']) ? intval($_POST['discount_type']) : 0;
			$_POST['leveloff']=!empty($newleveloff) ? serialize($newleveloff) :'';
			if($leveloff === false) unset($_POST['leveloff']);
			$database_merchant_store_meal = D('Merchant_store_meal');
			$deliver_type = isset($_POST['deliver_type']) ? intval($_POST['deliver_type']) : 0;
			unset($_POST['deliver_type']);
			$database_merchant_store_meal->data($_POST)->save();
			
			$deliver['store_id'] = $now_store['store_id'];
			$deliver['mer_id'] = $now_store['mer_id'];
			$deliver['site'] = $now_store['adress'];
			$deliver['type'] = $deliver_type;
			$deliver['range'] = $_POST['delivery_radius'];
			if ($deliver_store = D('Deliver_store')->field(true)->where(array('store_id' => $now_store['store_id']))->find()) {
				D('Deliver_store')->where(array('pigcms_id' => $deliver_store['pigcms_id']))->save($deliver);
			} else {
				D('Deliver_store')->data($deliver)->add();
			}
			
			
// 			if($database_merchant_store_meal->data($_POST)->save()){
				$database_meal_store_category_relation = D('Meal_store_category_relation');
				$condition_meal_store_category_relation['store_id'] = $now_store['store_id'];
				$database_meal_store_category_relation->where($condition_meal_store_category_relation)->delete();
				foreach($cat_ids as $key => $value){
					$data_meal_store_category_relation[$key]['cat_id'] = $value['cat_id'];
					$data_meal_store_category_relation[$key]['cat_fid'] = $value['cat_fid'];
					$data_meal_store_category_relation[$key]['store_id'] = $now_store['store_id'];
				}
				$database_meal_store_category_relation->addAll($data_meal_store_category_relation);
				
				$this->success('编辑成功！');
// 			}else{
// 				$this->error('编辑失败！请重试。');
// 			}
		}else{
			$database_merchant_store_meal = D('Merchant_store_meal');
			$condition_merchant_store_meal['store_id'] = $now_store['store_id'];
			$store_meal = $database_merchant_store_meal->field(true)->where($condition_merchant_store_meal)->find();
			$store_meal && $store_meal['store_labels'] = unserialize($store_meal['store_labels']);
			if(empty($store_meal)){
				$data_merchant_store_meal['store_id'] = $now_store['store_id'];
				if($this->config['store_open_payone']){
					$data_merchant_store_meal['openpayone'] = 1;
				}
				if($this->config['store_open_paythree']){
					$data_merchant_store_meal['openpaythree'] = 1;
				}
				$data_merchant_store_meal['openpaytwo'] = 1;
				$data_merchant_store_meal['last_time'] = $_SERVER['REQUEST_TIME'];
				$database_merchant_store_meal->data($data_merchant_store_meal)->add();
				
				$condition_merchant_store_meal['store_id'] = $now_store['store_id'];
				$store_meal = $database_merchant_store_meal->field(true)->where($condition_merchant_store_meal)->find();
				if(empty($store_meal)){
					$this->error('初始化失败！请重试。');
				}
			}
			
			//所有分类
			$database_meal_category = D('Meal_store_category');
			$category_list = $database_meal_category->field(true)->where(array('cat_status' => 1))->order('`cat_sort` DESC,`cat_id` ASC')->select();
			$list = array();
			foreach ($category_list as $row) {
				if ($row['cat_fid']) {
					$list[$row['cat_fid']]['list'][] = $row;
				} else {
					$list[$row['cat_id']] = isset($list[$row['cat_id']]) ? array_merge($list[$row['cat_id']], $row) : $row;
				}
			}
			$this->assign('category_list',$list);
			
// 			if(!empty($store_meal['cat_info'])){
				//此店铺有的分类
				$database_meal_store_category_relation = D('Meal_store_category_relation');
				$condition_meal_store_category_relation['store_id'] = $now_store['store_id'];
				$relation_list = $database_meal_store_category_relation->field(true)->where($condition_meal_store_category_relation)->select();
				$relation_array = array();
				foreach($relation_list as $key=>$value){
					array_push($relation_array,$value['cat_id']);
				}
				$this->assign('relation_array',$relation_array);
// 			}
			
			if(!empty($store_meal['deliver_time'])){
				$store_meal['deliver_time'] = unserialize($store_meal['deliver_time']);
			}else{
				$store_meal['deliver_time'] = array();
			}
			for($i=count($store_meal['deliver_time']);$i<20;$i++){
				array_push($store_meal['deliver_time'],array('start'=>'00:00','stop'=>'00:00'));
			}


			$deliver = D('Deliver_store')->field('type,range')->where(array('store_id'=>$now_store['store_id']))->find();
			$store_meal['deliver_type'] = 0;
			if($deliver){
				$store_meal['deliver_type'] = intval($deliver['type']);
			}
			
			$this->assign('store_meal',$store_meal);
			$leveloff = !empty($store_meal['leveloff']) ? unserialize($store_meal['leveloff']) :false;
			$levelDb = M('User_level');
		    $tmparr = $levelDb->order('id ASC')->select();
		    $levelarr = array();
			if($tmparr && $this->config['level_onoff']){
				foreach($tmparr as $vv){
				  if(!empty($leveloff) && isset($leveloff[$vv['level']])){
				        $vv['vv']=$leveloff[$vv['level']]['vv'];
						$vv['type']=$leveloff[$vv['level']]['type'];
				   }else{
				   		$vv['vv']='';
						$vv['type']='';
				   }
				   $levelarr[$vv['level']]=$vv;
				}
			}
			unset($tmparr);
			
			
			$this->assign('levelarr', $levelarr);
			$this->assign('now_store', $now_store);
			$labels = D('Store_label')->field(true)->select();
			$this->assign('label_list', $labels);
			$this->display();
		}
	}

	public function order_detail() {
		$now_order = D('Meal_order')->get_order_by_orderid( $_GET['order_id']);
		if (empty($now_order)) {
			exit('此订单不存在！');
		}
		$now_order['info']=unserialize($now_order['info']);
		if(!empty($now_order['coupon_id'])) {
			$system_coupon = D('System_coupon')->get_coupon_info($now_order['coupon_id']);
			$now_order['coupon_price'] = $system_coupon['price'];
			$this->assign('system_coupon',$system_coupon);
		}else if(!empty($now_order['card_id'])) {
			$card = D('Member_card_coupon')->get_coupon_info($now_order['card_id']);
			$now_order['coupon_price'] = $card['price'];
			$this->assign('card', $card);
		}
		
		$mode = new Model();
		$sql = "SELECT u.name, u.phone FROM " . C('DB_PREFIX') . "deliver_supply AS s INNER JOIN " . C('DB_PREFIX') . "deliver_user AS u ON u.uid=s.uid WHERE s.order_id={$now_order['order_id']} AND s.item=0";
		$res = $mode->query($sql);
		$res = isset($res[0]) && $res[0] ? $res[0] : '';
		$now_order['deliver_user_info'] = $res;
		
		$this->assign('order',$now_order);
		$this->display();
	}
	
	/*订餐分类*/
	public function meal_sort(){
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store',$now_store);
		
		$database_meal_sort = D('Meal_sort');
		$condition_merchant_sort['store_id'] = $now_store['store_id'];
		$count_sort = $database_meal_sort->where($condition_merchant_sort)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count_sort,20);
		$sort_list = $database_meal_sort->field(true)->where($condition_merchant_sort)->order('`sort` DESC,`sort_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();
		foreach($sort_list as $key=>$value){
			if(!empty($value['week'])){
				$week_arr = explode(',',$value['week']);
				$week_str = '';
				foreach($week_arr as $k=>$v){
					$week_str .= $this->get_week($v).' ';
				}
				$sort_list[$key]['week_str'] = $week_str;
			}
		}
		$this->assign('sort_list',$sort_list);
		$this->assign('pagebar',$p->show());
		$this->display();
	}
	
	protected function get_week($num){
		switch($num){
			case 1:
				return '星期一';
			case 2:
				return '星期二';
			case 3:
				return '星期三';
			case 4:
				return '星期四';
			case 5:
				return '星期五';
			case 6:
				return '星期六';
			case 0:
				return '星期日';
			default:
				return '';
		}
	}
	/*添加分类*/
	public function sort_add(){
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store',$now_store);
		
		if(IS_POST){
			if(empty($_POST['sort_name'])){
				$error_tips = '分类名称必填！'.'<br/>';
			}else{
				$database_meal_sort = D('Meal_sort');
				$data_meal_sort['store_id'] = $now_store['store_id'];
				$data_meal_sort['sort_name'] = $_POST['sort_name'];
				$data_meal_sort['sort'] = intval($_POST['sort']);
				$data_meal_sort['is_weekshow'] = intval($_POST['is_weekshow']);
				if($_POST['week']){
					$data_meal_sort['week'] = strval(implode(',',$_POST['week']));
				}
				if($database_meal_sort->data($data_meal_sort)->add()){
					$this->success('添加成功！！', U('Meal/meal_sort',array('store_id' => $now_store['store_id'])));
					die;
					$ok_tips = '添加成功！！';
				}else{
					$this->error('添加失败！！请重试。', U('Meal/meal_sort',array('store_id' => $now_store['store_id'])));
					die;
					$error_tips = '添加失败！！请重试。';
				}
			}
			if(!empty($error_tips)){
				$this->assign('now_sort',$_POST);
			}
			$this->assign('ok_tips',$ok_tips);
			$this->assign('error_tips',$error_tips);
		}
		
		$this->display();
	}
	/*修改分类*/
	public function sort_edit(){
		$now_sort = $this->check_sort($_GET['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);		
		$this->assign('now_sort',$now_sort);
		$this->assign('now_store',$now_store);
		if(IS_POST){
			if(empty($_POST['sort_name'])){
				$error_tips = '分类名称必填！'.'<br/>';
			}else{
				$database_meal_sort = D('Meal_sort');
				$data_meal_sort['sort_id'] = $now_sort['sort_id'];
				$data_meal_sort['sort_name'] = $_POST['sort_name'];
				$data_meal_sort['sort'] = intval($_POST['sort']);
				$data_meal_sort['is_weekshow'] = intval($_POST['is_weekshow']);
				$data_meal_sort['week'] = implode(',',$_POST['week']);
				if($database_meal_sort->data($data_meal_sort)->save()){
					$this->success('保存成功！！', U('Meal/meal_sort',array('store_id' => $now_store['store_id'])));
					die;
					$ok_tips = '保存成功！！';
				}else{
					$this->error('保存失败！！您是不是没做过修改？请重试。', U('Meal/meal_sort',array('store_id' => $now_store['store_id'])));
					die;
					$error_tips = '保存失败！！您是不是没做过修改？请重试。';
				}
			}
			$_POST['sort_id'] = $now_sort['sort_id'];
			$this->assign('now_sort',$_POST);
			$this->assign('ok_tips',$ok_tips);
			$this->assign('error_tips',$error_tips);
		}
		
		$this->display();
	}
	/* 分类状态 */
	public function sort_status(){
		$now_sort = $this->check_sort($_POST['id']);
		$now_store = $this->check_store($now_sort['store_id']);
		
		$database_meal_sort = D('Meal_sort');
		$condition_merchant_sort['sort_id'] = $now_sort['sort_id'];
		$data_merchant_sort['is_weekshow'] = $_POST['type'] == 'open' ? '1' : '0';
		if($database_meal_sort->where($condition_merchant_sort)->data($data_merchant_sort)->save()){
			exit('1');
		}else{
			exit;
		}
	}
	/* 删除分类 */
	public function sort_del(){
		$now_sort = $this->check_sort($_GET['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);

		$database_meal_sort = D('Meal_sort');
		$condition_merchant_sort['sort_id'] = $now_sort['sort_id'];
		if($database_meal_sort->where($condition_merchant_sort)->delete()){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
	
	/* 菜品管理 */
	public function meal_list(){
		$now_sort = $this->check_sort($_GET['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);
		$this->assign('now_sort',$now_sort);
		$this->assign('now_store',$now_store);
		
		$database_meal = D('Meal');
		$condition_meal['sort_id'] = $now_sort['sort_id'];
		$count_meal = $database_meal->where($condition_meal)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count_meal,20);
		$meal_list = $database_meal->field(true)->where($condition_meal)->order('`sort` DESC,`meal_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();
		
		$plist = array();
		$prints = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
		foreach ($prints as $l) {
			if ($l['is_main']) {
				$l['name'] .= '(主打印机)';
			} else {
				$l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
			}
			$plist[$l['pigcms_id']] = $l;
		}
		foreach ($meal_list as &$rl) {
			$rl['print_name'] = isset($plist[$rl['print_id']]['name']) ? $plist[$rl['print_id']]['name'] : '';
		}
		
		$this->assign('meal_list',$meal_list);
		$this->assign('pagebar',$p->show());
		
		$this->display();
	}
	/* 添加店铺 */
	public function meal_add(){
		$now_sort = $this->check_sort($_GET['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);
		$this->assign('now_sort',$now_sort);
		$this->assign('now_store',$now_store);
			
		if(IS_POST){
			if(empty($_POST['name'])){
				$error_tips .= '商品名称必填！'.'<br/>';
			}
			if(empty($_POST['unit'])){
				$error_tips .= '商品单位必填！'.'<br/>';
			}
			if(empty($_POST['price'])){
				$error_tips .= '商品价格必填！'.'<br/>';
			}
			if($_FILES['image']['error'] != 4){
				
// 				$img_mer_id = sprintf("%09d",$this->merchant_session['mer_id']);
// 				$rand_num = mt_rand(10,99).'/'.substr($img_mer_id,0,3).'/'.substr($img_mer_id,3,3).'/'.substr($img_mer_id,6,3);
			
// 				$upload_dir = './upload/meal/'.$rand_num.'/'; 
// 				if(!is_dir($upload_dir)){
// 					mkdir($upload_dir,0777,true);
// 				}
// 				import('ORG.Net.UploadFile');
// 				$upload = new UploadFile();
// 				$upload->maxSize = $this->config['meal_pic_size']*1024*1024;
// 				$upload->allowExts = array('jpg','jpeg','png','gif');
// 				$upload->allowTypes = array('image/png','image/jpg','image/jpeg','image/gif');
// 				$upload->savePath = $upload_dir; 
// 				$upload->thumb=true;
// 				$upload->imageClassPath = 'ORG.Util.Image';
// 				$upload->thumbPrefix = 'm_,s_';
// 				$upload->thumbMaxWidth  = $this->config['meal_pic_width'];
// 				$upload->thumbMaxHeight = $this->config['meal_pic_height'];
// 				$upload->thumbRemoveOrigin = false;
// 				$upload->saveRule = 'uniqid';
// 				if($upload->upload()){
// 					$uploadList = $upload->getUploadFileInfo();
// 					$_POST['image'] = $rand_num.','.$uploadList[0]['savename'];
// 				}else{
// 					$error_tips .= $upload->getErrorMsg().'<br/>';
// 				}

				$param = array('size' => $this->config['meal_pic_size']);
				$param['thumb'] = true;
				$param['imageClassPath'] = 'ORG.Util.Image';
				$param['thumbPrefix'] = 'm_,s_';
				$param['thumbMaxWidth'] = $this->config['meal_pic_width'];
				$param['thumbMaxHeight'] = $this->config['meal_pic_height'];
				$param['thumbRemoveOrigin'] = false;
				
				$image = D('Image')->handle($this->merchant_session['mer_id'], 'meal', 1, $param);
              
				if ($image['error']) {
					$error_tips .= $image['msg'] . '<br/>';
				} else {
					$_POST = array_merge($_POST, $image['title']);
				}
			}
			if(!empty($_POST['image_select'])){
				$img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
				$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
			
				$tmp_img = explode(',',$_POST['image_select']);
				$_POST['image']=$rand_num.','.$tmp_img[1];
				
			}
			
			
			$_POST['print_id'] = isset($_POST['print_id']) ? intval($_POST['print_id']) : 0;
			$_POST['des'] = stripslashes($_POST['des']);
			if(empty($error_tips)){
				$_POST['sort_id'] = $now_sort['sort_id'];
				$_POST['store_id'] = $now_store['store_id'];
				$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
				
				$database_meal = D('Meal');
				if($meal_id = $database_meal->data($_POST)->add()){
					D('Image')->update_table_id($_POST['image'], $meal_id, 'meal');
					$this->success('添加成功！', U('Meal/meal_list',array('sort_id' => $now_sort['sort_id'])));
					die;
					$ok_tips = '添加成功！';
				}else{
					$this->error('添加失败！请重试！', U('Meal/meal_list',array('sort_id' => $now_sort['sort_id'])));
					die;
					$error_tips = '添加失败！请重试。';
				}
			}else{
				$this->assign('now_meal',$_POST);
			}
			
			$this->assign('ok_tips',$ok_tips);
			$this->assign('error_tips',$error_tips);
		}
		
		$print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
		foreach ($print_list as &$l) {
			if ($l['is_main']) {
				$l['name'] .= '(主打印机)'; 
			} else {
				$l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
			}
		}
		$this->assign('print_list', $print_list);
		$this->display();
	}
	/* 编辑店铺 */
	public function meal_edit(){
		$now_meal = $this->check_meal($_GET['meal_id']);
		$now_sort = $this->check_sort($now_meal['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);
		$this->assign('now_meal',$now_meal);
		$this->assign('now_sort',$now_sort);
		$this->assign('now_store',$now_store);
			
		if(IS_POST){
			if(empty($_POST['name'])){
				$error_tips .= '商品名称必填！'.'<br/>';
			}
			if(empty($_POST['unit'])){
				$error_tips .= '商品单位必填！'.'<br/>';
			}
			if(empty($_POST['price'])){
				$error_tips .= '商品价格必填！'.'<br/>';
			}
			if($_FILES['image']['error'] != 4){
				$param = array('size' => $this->config['meal_pic_size']);
				$param['thumb'] = true;
				$param['imageClassPath'] = 'ORG.Util.Image';
				$param['thumbPrefix'] = 'm_,s_';
				$param['thumbMaxWidth']  = $this->config['meal_pic_width'];
				$param['thumbMaxHeight'] = $this->config['meal_pic_height'];
				$param['thumbRemoveOrigin'] = false;
				$image = D('Image')->handle($this->merchant_session['mer_id'], 'meal', 1, $param);
				if ($image['error']) {
					$error_tips .= $image['msg'] . '<br/>';
				} else {
					$_POST = array_merge($_POST, $image['title']);
				}
			}else{
				unset($_POST['image']);
			}
			if(!empty($_POST['image_select'])){
				$img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
				$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
			
				$tmp_img = explode(',',$_POST['image_select']);
				$_POST['image']=$rand_num.','.$tmp_img[1];
				
			}
			
			$_POST['print_id'] = isset($_POST['print_id']) ? intval($_POST['print_id']) : 0;
			$_POST['meal_id'] = $now_meal['meal_id'];
			$_POST['des'] = stripslashes($_POST['des']);
			if(empty($error_tips)){
				$_POST['sort_id'] = $now_sort['sort_id'];
				$_POST['store_id'] = $now_store['store_id'];
				$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
				$database_meal = D('Meal');
				if($database_meal->data($_POST)->save()){
					$meal_image_class = new meal_image();
					$t_image = $meal_image_class->get_image_by_path($_POST['image'], '', -1);
					D('Image')->update_table_id($t_image['image'], $_POST['meal_id'], 'meal');

					//删除原有图片
					if(!empty($_POST['image']) && !empty($now_meal['image'])&&empty($_POST['image_select'])){
						//$meal_image_class = new meal_image();
						$meal_image_class->del_image_by_path($now_meal['image']);
					}
					$meal_image_class = new meal_image();
					$now_meal['see_image'] = $meal_image_class->get_image_by_path($_POST['image'],$this->config['site_url'],'s');
					$this->assign('now_meal',$now_meal);
					$this->success('编辑成功！', U('Meal/meal_list',array('sort_id' => $now_sort['sort_id'])));
					die;
					$ok_tips = '编辑成功！';
				}else{
					$this->error('编辑失败！请重试！', U('Meal/meal_list',array('sort_id' => $now_sort['sort_id'])));
					die;
					$error_tips = '编辑失败！请重试。';
				}
			}else{
				$this->assign('now_meal',$_POST);
			}
			
			$this->assign('ok_tips',$ok_tips);
			$this->assign('error_tips',$error_tips);
		}
		
		$print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
		foreach ($print_list as &$l) {
			if ($l['is_main']) {
				$l['name'] .= '(主打印机)'; 
			} else {
				$l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
			}
		}
		$this->assign('print_list', $print_list);
		$this->display();
	}
	/* 商品删除 */
	public function meal_del(){
		$now_meal = $this->check_meal($_GET['meal_id']);
		$now_sort = $this->check_sort($now_meal['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);
		
		$database_meal = D('Meal');
		$condition_meal['meal_id'] = $now_meal['meal_id'];
		if($database_meal->where($condition_meal)->delete()){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！请检查后重试。');
		}
	}
	/* 商品状态 */
	public function meal_status(){
		$now_meal = $this->check_meal($_POST['id']);
		$now_sort = $this->check_sort($now_meal['sort_id']);
		$now_store = $this->check_store($now_sort['store_id']);
		
		$database_meal = D('Meal');
		$condition_meal['meal_id'] = $now_meal['meal_id'];
		$data_meal['status'] = $_POST['type'] == 'open' ? '1' : '0';
		if($database_meal->where($condition_meal)->data($data_meal)->save()){
			exit('1');
		}else{
			exit;
		}
	}
	
	/* 检测店铺存在，并检测是不是归属于商家 */
	protected function check_store($store_id){
		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['store_id'] = $store_id;
		$condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
		$now_store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
		if(empty($now_store)){
			$this->error('店铺不存在！');
		}else{
			return $now_store;
		}
	}
	/* 检测分类存在 */
	protected function check_sort($sort_id){
		$database_meal_sort = D('Meal_sort');
		$condition_merchant_sort['sort_id'] = $sort_id;
		$now_sort = $database_meal_sort->field(true)->where($condition_merchant_sort)->find();
		if(empty($now_sort)){
			$this->error('分类不存在！');
		}
		if(!empty($now_sort['week'])){
			$now_sort['week'] = explode(',',$now_sort['week']);
		}
		return $now_sort;
	}
	/* 检测商品存在 */
	protected function check_meal($meal_id){
		$database_meal = D('Meal');
		$condition_meal['meal_id'] = $meal_id;
		$now_meal = $database_meal->field(true)->where($condition_meal)->find();
		if(!empty($now_meal['image'])){
			$meal_image_class = new meal_image();
			$now_meal['see_image'] = $meal_image_class->get_image_by_path($now_meal['image'],$this->config['site_url'],'s');
		}
		if(empty($now_meal)){
			$this->error('商品不存在！');
		}
		return $now_meal;
	}
	
	public function order()
	{
		$store_id = intval($_GET['store_id']);
		
		$now_store = $this->check_store($store_id);
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= $type . ' ' . $sort . ',';
			$order_sort .= 'order_id DESC';
		} else {
			$order_sort .= 'order_id DESC';
		}
		
		$where = array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']);
		if ($status != -1) {
			$where['status'] = $status;
		}
		
		$this->assign(D("Meal_order")->get_order_list($this->merchant_session['mer_id'], $store_id, $where, $order_sort));
		$this->assign('now_store', $now_store);
		$this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status));
		$this->assign('status_list', D('Meal_order')->status_list);
		$this->display();
	}
	
	public function table()
	{
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store',$now_store);
		
		$database = D('Merchant_store_table');
		$where['store_id'] = $now_store['store_id'];
		$count = $database->where($where)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count, 20);
		$list = $database->field(true)->where($where)->order('`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('list', $list);
		$this->assign('pagebar',$p->show());
		$this->display();
	}
	
	public function table_add()
	{
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store',$now_store);
		if (IS_POST) {
			$name = htmlspecialchars($_POST['name']);
			if(empty($name)){
				$error_tips .= '分类名称必填！'.'<br/>';
			}
			$num = intval($_POST['num']);
			$status = intval($_POST['status']);
			$database = D('Merchant_store_table');
			$table = $database->field(true)->where(array('name' => $name, 'store_id' => $now_store['store_id']))->find();
			if ($table) {
				$error_tips .= '分类名已经存在！'.'<br/>';
			}
			if(empty($error_tips)){
				if($database->data(array('name' => $name, 'status' => $status, 'num' => $num, 'store_id' => $now_store['store_id']))->add()){
					$this->success('添加成功！', U('Meal/table',array('store_id' => $now_store['store_id'])));
					die;
					$ok_tips = '添加成功！';
				}else{
					$this->error('添加失败！请重试！', U('Meal/table',array('store_id' => $now_store['store_id'])));
					die;
					$error_tips = '添加失败！请重试。';
				}
			}
			$this->assign('now_table',$_POST);
			$this->assign('ok_tips',$ok_tips);
			$this->assign('error_tips',$error_tips);
		}
		$this->display();
	}
	
	public function table_edit()
	{
		$now_store = $this->check_store($_GET['store_id']);
		$this->assign('now_store',$now_store);
		$database = D('Merchant_store_table');
		$now_table = $database->field(true)->where(array('pigcms_id' => intval($_GET['pigcms_id']), 'store_id' => $now_store['store_id']))->find();
		if (empty($now_table)) {
			$this->error('该分类不存在！');
		}
		$this->assign('now_table', $now_table);
		if (IS_POST) {
			$name = htmlspecialchars($_POST['name']);
			if(empty($name)){
				$error_tips .= '分类名称必填！'.'<br/>';
			}

			$num = intval($_POST['num']);
			$status = intval($_POST['status']);
			$table = $database->field(true)->where(array('name' => $name, 'store_id' => $now_store['store_id']))->find();
			if ($table && $table['pigcms_id'] != intval($_GET['pigcms_id'])) {
				$error_tips .= '分类名已经存在！'.'<br/>';
			}
			if(empty($error_tips)){
				if ($database->where(array('pigcms_id' => $now_table['pigcms_id']))->save(array('num' => $num, 'status' => $status, 'name' => $name, 'store_id' => $now_table['store_id']))) {
					$this->success('编辑成功！', U('Meal/table',array('store_id' => $now_store['store_id'])));
					die;
				} else {
					$this->error('编辑失败！', U('Meal/table',array('store_id' => $now_store['store_id'])));
					die;
				}
				$ok_tips = '修改成功！';
			}
			$this->assign('now_table', $_POST);
			$this->assign('ok_tips',$ok_tips);
			$this->assign('error_tips',$error_tips);
			
			
		}
		$this->display();
	}
	
	public function table_del()
	{
		$now_store = $this->check_store($_GET['store_id']);
		$database = D('Merchant_store_table');
		if ($database->where(array('pigcms_id' => intval($_GET['pigcms_id']), 'store_id' => $now_store['store_id']))->delete()) {
			$this->success('删除成功！');
		} else{
			$this->error('删除失败！请检查后重试。');
		}
	}
	/* 分类状态 */
	public function table_status()
	{
		$database = D('Merchant_store_table');
		$condition['pigcms_id'] = intval($_POST['id']);
		$now_table = $database->field(true)->where($condition)->find();
		if(empty($now_table)){
			$this->error('桌台不存在！');
		}
		$now_store = $this->check_store($now_table['store_id']);
		$data['status'] = $_POST['type'] == 'open' ? '1' : '0';
		if($database->where($condition)->data($data)->save()){
			exit('1');
		}else{
			exit;
		}
	}
	
	public function table_order()
	{
		$tableid = intval($_GET['id']);
		$database = D('Merchant_store_table');
		$condition['pigcms_id'] = $tableid;
		$now_table = $database->field(true)->where($condition)->find();
		if(empty($now_table)){
			$this->error('桌台不存在！');
		}

		$now_store = $this->check_store($now_table['store_id']);
		$this->assign('now_store',$now_store);
		
		$this->assign('table', $now_table);
		$order_list = D('Meal_order')->field(true)->where(array('tableid' => $tableid, 'mer_id' => $this->merchant_session['mer_id'], 'status' => 0, 'arrive_time' => array('gt', time())))->order('arrive_time ASC')->select();
		$this->assign('order_list', $order_list);
		$this->display();
	}
	
	public function get_own_qrcode(){
		$qrCon = $_GET['qrCon'];
		import('@.ORG.phpqrcode');
		$size = $_GET['size'] ? $_GET['size']: 10;
		QRcode::png(htmlspecialchars_decode(urldecode($qrCon)),false,0,$size,1);
	}
	
	public function url_qrcode()
	{
		$store_id = intval($_GET['store_id']);
		$now_store = $this->check_store($store_id);
		$tableid = intval($_GET['pigcms_id']);
		$now_table = D('Merchant_store_table')->field(true)->where(array('pigcms_id' => $tableid, 'store_id' => $store_id))->find();
		if(empty($now_table)) exit('桌台不存在！');
		$qrCon = $this->config['site_url'] . '/wap.php?g=Wap&c=Food&a=menu&mer_id=' . $this->merchant_session['mer_id'] . '&store_id=' . $store_id . '&tableid=' . $tableid;
		import('@.ORG.phpqrcode');
		$size = $_GET['size'] ? $_GET['size']: 10;
		QRcode::png(htmlspecialchars_decode(urldecode($qrCon)),false,0,$size,1);
	}

}