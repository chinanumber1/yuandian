<?php
/*
 * 订餐管理
 *
 * @  BuildTime  2014/11/18 11:21
 */

class FoodshopAction extends BaseAction
{
    public function index()
    {
    	$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$database_meal_category = D('Meal_store_category');
		$category = $database_meal_category->field(true)->where(array('cat_id' => $parentid))->find();
		$category_list = $database_meal_category->field(true)->where(array('cat_fid' => $parentid))->order('`cat_sort` DESC,`cat_id` ASC')->select();
		$this->assign('category', $category);
		$this->assign('category_list', $category_list);
		$this->assign('parentid', $parentid);
		$this->display();
    }
    
	public function cat_add()
	{
		$this->assign('bg_color','#F3F3F3');
		$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$this->assign('parentid', $parentid);
		$this->display();
	}
	public function cat_modify()
	{
		if(IS_POST){
			$database_meal_category = D('Meal_store_category');
			if($database_meal_category->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function cat_edit()
	{
		$this->assign('bg_color','#F3F3F3');
		
		$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$database_meal_category = D('Meal_store_category');
		$condition_now_meal_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_meal_category->field(true)->where($condition_now_meal_category)->find();
		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		$this->assign('parentid', $parentid);
		$this->assign('now_category',$now_category);
		$this->display();
	}
	public function cat_amend(){
		if(IS_POST){
			$database_meal_category = D('Meal_store_category');
			$where = array('cat_id' => $_POST['cat_id']);
			unset($_POST['cat_id']);
			if($database_meal_category->where($where)->save($_POST)){
				$this->success('编辑成功！');
			}else{
				$this->error('编辑失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function cat_del(){
		if(IS_POST){
			$database_meal_category = D('Meal_store_category');
			$condition_now_meal_category['cat_id'] = intval($_POST['cat_id']);
			
			if ($obj = $database_meal_category->field(true)->where($condition_now_meal_category)->find()) {
				$t_list = $database_meal_category->field(true)->where(array('cat_fid' => $obj['cat_id']))->select();
				if ($t_list) {
					$this->error('该分类下有子分类，先清空子分类，再删除该分类');
				}
			}
			if($database_meal_category->where($condition_now_meal_category)->delete()){
				$database_meal_category_relation = D('Meal_category_relation');
				$condition_meal_category_relation['cat_id'] = intval($_POST['cat_id']);
				$database_meal_category_relation->where($condition_meal_category_relation)->delete();
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	
	public function order()
	{
		$where_store = array('status' => 1);
		
		$condition_where = 'WHERE s.status=1';
		
		if(!empty($_GET['keyword']) && $_GET['searchtype'] == 's_name'){
		    $condition_where .= " AND s.name LIKE '%" . $_GET['keyword'] . "%'";
// 			$where_store['name'] = array('like', '%'.$_GET['keyword'].'%');
		}
		
		
		
		if ($this->system_session['area_id']) {
			$now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
			if ($now_area['area_type'] == 3) {
                $area_index = 'area_id';
                $condition_where .= " AND s.area_id=" . $this->system_session['area_id'];
            } elseif ($now_area['area_type'] == 2) {
                $area_index = 'city_id';
                $condition_where .= " AND s.city_id=" . $this->system_session['area_id'];
            } elseif ($now_area['area_type'] == 1) {
                $area_index = 'province_id';
                $condition_where .= " AND s.province_id=" . $this->system_session['area_id'];
            }
// 			$where_store[$area_index] = $this->system_session['area_id'];
// 			$stores = D('Merchant_store')->field('store_id')->where($where_store)->select();//
		} else {
// 			$stores = D('Merchant_store')->field('store_id')->where($where_store)->select();
		}
// 		$store_ids = array();
// 		foreach ($stores as $row) {
// 			$store_ids[] = $row['store_id'];
// 		}

// 		$condition_where = 'Where 1=1 ';
// 		if ($store_ids) {
// 			$where['store_id'] = array('in', $store_ids);
// 			$condition_where .=' AND o.store_id in('.implode(',',$store_ids).')';
// 		} else {
// 			import('@.ORG.system_page');
// 			$p = new Page(0, 20);
// 			$this->assign('order_list', null);
// 			$this->assign('pagebar', $p->show());
// 			$this->display();
// 			exit;
// 		}
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['real_orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.real_orderid ='.$where['real_orderid'];
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.orderid ='.$where['orderid'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['name'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.name ='.$where['name'];
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['phone'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.phone ='.$where['phone'];
			}
		}
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
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type&&$pay_type!='balance'){
			$condition_where .=' AND p.pay_type ="'.$pay_type.'"';
		}else if($pay_type=='balance'){
			$condition_where .= " AND (`p`.`system_balance`<>0 OR `p`.`merchant_balance_pay` <> 0 )";
		}


		if(!empty($_GET['begin_time']) && ! empty($_GET['end_time'])) {
            if ($_GET['begin_time'] > $_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(
                strtotime($_GET['begin_time'] . " 00:00:00"),
                strtotime($_GET['end_time'] . " 23:59:59")
            );
            $where['_string'] .= ($where['_string'] ? ' AND ' : '') . " (create_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
            $condition_where .= " AND  (o.create_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
        }
        
        if ($status != - 1) {
            $where['status'] = $status;
            $condition_where .= ' AND o.status =' . $status;
        }
// 		echo '<pre/>';
// 		print_r($where);die;
		//$count = D("Foodshop_order")->where($where)->count();
		$sql_cont = 'SELECT count(distinct(o.order_id)) as count from pigcms_merchant as m';
		$sql_cont .= ' INNER JOIN pigcms_merchant_store as s ON m.mer_id=s.mer_id';
		$sql_cont .= ' INNER JOIN pigcms_foodshop_order as o ON o.store_id=s.store_id LEFT JOIN';
		$sql_cont .= " pigcms_plat_order AS p ON o.order_id=p.business_id AND p.business_type='foodshop' "; 
		$sql_cont .= $condition_where;
		
// 		'ON p.AND   (select a.* from pigcms_plat_order a,(select max(order_id) as order_id,business_id from pigcms_plat_order group by business_id) b where a.order_id=b.order_id and a.business_id=b.business_id) p on o.order_id = p.business_id '.$condition_where.' ORDER BY o.order_id DESC';
		$count = M()->query($sql_cont);
		$count = $count[0]['count'];
		import('@.ORG.system_page');
		$p = new Page($count, 20);
		//$list = D("Foodshop_order")->where($where)->order($order_sort)->limit($p->firstRow . ',' . $p->listRows)->select();
// 		$sql = 'SELECT o.*, p.pay_type as pay_method, s.name as store_name, m.name as merchant_name from pigcms_merchant as m INNER JOIN pigcms_merchant_store as s ON m.mer_id=s.mer_id INNER JOIN pigcms_foodshop_order o ON s.store_id=o.store_id LEFT JOIN (select a.* from pigcms_plat_order a,(select max(order_id) as order_id,business_id from pigcms_plat_order group by business_id) b where a.order_id=b.order_id and a.business_id=b.business_id) p on o.order_id = p.business_id '.$condition_where.' ORDER BY o.order_id DESC'.' limit '.$p->firstRow.','.$p->listRows;
		
		$sql = 'SELECT o.*, p.pay_type as pay_method, s.name as store_name, m.name as merchant_name from pigcms_merchant as m';
		$sql .= ' INNER JOIN pigcms_merchant_store as s ON m.mer_id=s.mer_id';
		$sql .= ' INNER JOIN pigcms_foodshop_order as o ON o.store_id=s.store_id';
		$sql .= " LEFT JOIN pigcms_plat_order AS p ON p.business_type='foodshop' AND o.order_id=p.business_id ";
		$sql .= $condition_where . ' GROUP BY o.order_id ORDER BY o.order_id DESC, p.order_id DESC';
		$sql .= ' limit ' . $p->firstRow . ',' . $p->listRows;
		$list = M()->query($sql);
// 		echo M()->_sql();die;
		$mer_ids = $store_ids = array();
		foreach ($list as $l) {
// 			$mer_ids[] = $l['mer_id'];
// 			$store_ids[] = $l['store_id'];
			$table_types[] = $l['table_type'];
			$tids[] = $l['table_id'];

		}
		

		$type_list = array();
		if ($table_types) {
			$temp_type_list = M('Foodshop_table_type')->field(true)->where(array('id' => array('in', $table_types)))->select();
			foreach ($temp_type_list as $tmp) {
				$type_list[$tmp['id']] = $tmp;
			}
		}
		$table_list = array();
		if ($tids) {
			$temp_table_list = M('Foodshop_table')->field(true)->where(array('id' => array('in', $tids)))->select();
			foreach ($temp_table_list as $temp) {
				$table_list[$temp['id']] = $temp;
			}
		}
		
		
// 		$store_temp = $mer_temp = array();
// 		if ($mer_ids) {
// 			$merchants = D("Merchant")->where(array('mer_id' => array('in', $mer_ids)))->select();
// 			foreach ($merchants as $m) {
// 				$mer_temp[$m['mer_id']] = $m;
// 			}
// 		}
// 		if ($store_ids) {
// 			$merchant_stores = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
// 			foreach ($merchant_stores as $ms) {
// 				$store_temp[$ms['store_id']] = $ms;
// 			}
// 		}
		foreach ($list as &$li) {
// 			$li['merchant_name'] = isset($mer_temp[$li['mer_id']]['name']) ? $mer_temp[$li['mer_id']]['name'] : '';
// 			$li['store_name'] = isset($store_temp[$li['store_id']]['name']) ? $store_temp[$li['store_id']]['name'] : '';
			
			$li['table_type_name'] = isset($type_list[$li['table_type']]) ? $type_list[$li['table_type']]['name'] . '(' . $type_list[$li['table_type']]['min_people'] . '-' . $type_list[$li['table_type']]['max_people'] . '人)' : '';
			$li['table_name'] = isset($table_list[$li['table_id']]) ? $table_list[$li['table_id']]['name'] : '';
			$li['show_status'] = D('Foodshop_order')->status_list[$li['status']];
		}
		$this->assign('order_list', $list);
		
		$pagebar = $p->show();
		
		$this->assign('pagebar', $pagebar);
		$this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status,'pay_type'=>$pay_type));
		$this->assign('status_list', D('Foodshop_order')->status_list);
		$pay_method = D('Config')->get_pay_method('','',1);
		$this->assign('pay_method',$pay_method);
		$this->display();
		
	}


	public function order_detail()
	{
		$this->assign('bg_color','#F3F3F3');
		if(strlen($_GET['order_id'])>15){
			$where['real_orderid'] = $_GET['order_id'];
		}else{
			$where['order_id'] = intval($_GET['order_id']);
		}
		$order = D('Foodshop_order')->get_order_detail($where, 3);
		$this->assign('order', $order);
		$this->display();
	}


	public function export(){
		$param = $_POST;
		$param['type'] = 'meal';
		$param['rand_number'] = $_SERVER['REQUEST_TIME'];
		$param['system_session']['area_id'] = $this->system_session['area_id'];
		if($res = D('Order')->order_export($param)){
			echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
		}else{
			echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
		}
		die;
		$where_store = array('status' => 1);
		if(!empty($_GET['keyword']) && $_GET['searchtype'] == 's_name'){
			$where_store['name'] = array('like', '%'.$_GET['keyword'].'%');
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
			$where_store[$area_index] = $this->system_session['area_id'];
			$stores = D('Merchant_store')->field('store_id')->where($where_store)->select();//
		} else {
			$stores = D('Merchant_store')->field('store_id')->where($where_store)->select();
		}
		$store_ids = array();
		foreach ($stores as $row) {
			$store_ids[] = $row['store_id'];
		}

		$condition_where = 'Where 1=1 ';
		if ($store_ids) {
			$where['store_id'] = array('in', $store_ids);
			$condition_where .=' AND o.store_id in('.implode(',',$store_ids).')';
		}

		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['real_orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.real_orderid ='.$where['real_orderid'];
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.orderid ='.$where['orderid'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['name'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.name ='.$where['name'];
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['phone'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.phone ='.$where['phone'];
			}
		}
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
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type&&$pay_type!='balance'){
			$condition_where .=' AND p.pay_type ="'.$pay_type.'"';
		}else if($pay_type=='balance'){
			$condition_where .= " AND (`p`.`system_balance`<>0 OR `p`.`merchant_balance_pay` <> 0 )";
		}


		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] .=( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition_where .=" AND  (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}

		if ($status != -1) {
			$where['status'] = $status;
			$condition_where .= ' AND o.status ='.$status;
		}
// 		echo '<pre/>';
// 		print_r($where);die;
		//$count = D("Foodshop_order")->where($where)->count();
		$sql_cont = 'SELECT count(o.order_id) as count from pigcms_foodshop_order o LEFT JOIN (select a.* from pigcms_plat_order a,(select max(order_id) as order_id,business_id from pigcms_plat_order group by business_id) b where a.order_id=b.order_id and a.business_id=b.business_id) p on o.order_id  = p.business_id '.$condition_where;
		$count  = M()->query($sql_cont);
		$count = $count[0]['count'];
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '订单信息';
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
		$length = ceil($count / 1000);


		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);

			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
			$objActSheet = $objExcel->getActiveSheet();
			$objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
			$objExcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);

			$objActSheet->setCellValue('A1', '订单流水号');
			$objActSheet->setCellValue('B1', '商家名称');
			$objActSheet->setCellValue('C1', '店铺名称');
			$objActSheet->setCellValue('D1', '客户名称');
			$objActSheet->setCellValue('E1', '客户电话');
			$objActSheet->setCellValue('F1', '预定金');
			$objActSheet->setCellValue('G1', '预定时间');
			$objActSheet->setCellValue('H1', '桌台类型');
			$objActSheet->setCellValue('I1', '桌台名称');
			$objActSheet->setCellValue('J1', '商品名称');
			$objActSheet->setCellValue('K1', '规格/属性');
			$objActSheet->setCellValue('L1', '单价');
			$objActSheet->setCellValue('M1', '数量');
			$objActSheet->setCellValue('N1', '单位');
			$objActSheet->setCellValue('O1', '订单状态');
			$objActSheet->setCellValue('P1', '订单总价');
			$objActSheet->setCellValue('Q1', '余额支付');
			$objActSheet->setCellValue('R1', '平台在线支付');
			$objActSheet->setCellValue('S1', '商家余额支付');
			$objActSheet->setCellValue('T1', $this->config['score_name']);
			$objActSheet->setCellValue('U1', '支付时间');
			$objActSheet->setCellValue('V1', '支付方式');
			$objActSheet->setCellValue('W1', '支付类型');


			//$objActSheet->setCellValue('R1', '支付情况');

			//$sql = 'SELECT o.*,p.pay_type as pay_method,p.system_balance,p.merchant_balance_pay,p.pay_money,p.system_score_money,p.pay_time ,p.paid from pigcms_foodshop_order o LEFT JOIN  pigcms_plat_order  p on o.order_id  = p.business_id '.$condition_where .'limit '.($i*1000).',1000';
			$sql = 'SELECT o.*,fd.name as goods_name,fd.num as goods_num ,fd.spec as goods_spec,fd.unit as goods_unit,fd.price as goods_price,p.pay_type as pay_method,p.system_balance,p.merchant_balance_pay,p.pay_money,p.system_score_money,p.pay_time ,p.paid from '.C('DB_PREFIX').'foodshop_order o LEFT JOIN  '.C('DB_PREFIX').'plat_order  p on o.order_id  = p.business_id AND p.paid =1 LEFT JOIN '.C('DB_PREFIX').'foodshop_order_detail as fd ON fd.order_id = o.order_id '.$condition_where.' ORDER BY o.order_id DESC ' .'limit '.($i*1000).',1000';

			$list = M('')->query($sql);
			//appdump(M());
			$mer_ids = $store_ids = array();
			foreach ($list as $l) {
				$mer_ids[] = $l['mer_id'];
				$store_ids[] = $l['store_id'];
				$table_types[] = $l['table_type'];
				$tids[] = $l['table_id'];

			}


			$type_list = array();
			if ($table_types) {
				$temp_type_list = M('Foodshop_table_type')->field(true)->where(array('id' => array('in', $table_types)))->select();
				foreach ($temp_type_list as $tmp) {
					$type_list[$tmp['id']] = $tmp;
				}
			}
			$table_list = array();
			if ($tids) {
				$temp_table_list = M('Foodshop_table')->field(true)->where(array('id' => array('in', $tids)))->select();
				foreach ($temp_table_list as $temp) {
					$table_list[$temp['id']] = $temp;
				}
			}


			$store_temp = $mer_temp = array();
			if ($mer_ids) {
				$merchants = D("Merchant")->where(array('mer_id' => array('in', $mer_ids)))->select();
				foreach ($merchants as $m) {
					$mer_temp[$m['mer_id']] = $m;
				}
			}
			if ($store_ids) {
				$merchant_stores = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
				foreach ($merchant_stores as $ms) {
					$store_temp[$ms['store_id']] = $ms;
				}
			}
			foreach ($list as &$li) {
				$li['merchant_name'] = isset($mer_temp[$li['mer_id']]['name']) ? $mer_temp[$li['mer_id']]['name'] : '';
				$li['store_name'] = isset($store_temp[$li['store_id']]['name']) ? $store_temp[$li['store_id']]['name'] : '';

				$li['table_type_name'] = isset($type_list[$li['table_type']]) ? $type_list[$li['table_type']]['name'] . '(' . $type_list[$li['table_type']]['min_people'] . '-' . $type_list[$li['table_type']]['max_people'] . '人)' : '';
				$li['table_name'] = isset($table_list[$li['table_id']]) ? $table_list[$li['table_id']]['name'] : '';
				$li['show_status'] = D('Foodshop_order')->status_list[$li['status']];
			}

			$tmp_id = 0;
			$index_good =0;
			if (!empty($list)) {
				$index = 1;
				foreach ($list as $value) {
					if($tmp_id == $value['order_id']){
						if($index_good==1){
							$objActSheet->setCellValueExplicit('A' . $index, '');
							$objActSheet->setCellValueExplicit('B' . $index, '');
							$objActSheet->setCellValueExplicit('C' . $index, '');
							$objActSheet->setCellValueExplicit('D' . $index, '');
							$objActSheet->setCellValueExplicit('E' . $index, '');
							$objActSheet->setCellValueExplicit('F' . $index, '');
							$objActSheet->setCellValueExplicit('G' . $index, '');
							$objActSheet->setCellValueExplicit('H' . $index, '');
							$objActSheet->setCellValueExplicit('I' . $index, '');
							$objActSheet->setCellValueExplicit('J' . $index, $value['goods_name']);
							$objActSheet->setCellValueExplicit('K' . $index, $value['goods_spec']);
							$objActSheet->setCellValueExplicit('L' . $index, $value['goods_price']);
							$objActSheet->setCellValueExplicit('M' . $index, $value['goods_num']);
							$objActSheet->setCellValueExplicit('N' . $index, $value['goods_unit']);
							$objActSheet->setCellValueExplicit('O' . $index, '');
							$objActSheet->setCellValueExplicit('P' . $index, '');
							$objActSheet->setCellValueExplicit('Q' . $index, '');
							$objActSheet->setCellValueExplicit('R' . $index, '');
							$objActSheet->setCellValueExplicit('S' . $index, '');
							$objActSheet->setCellValueExplicit('T' . $index, '');
							$objActSheet->setCellValueExplicit('U' . $index, '');
							$objActSheet->setCellValueExplicit('V' . $index, '');
							$objActSheet->setCellValueExplicit('W' . $index, '');
						}
						if($index_good==0) {
							$objActSheet->setCellValueExplicit('A' . $index, '');
							$objActSheet->setCellValueExplicit('B' . $index, '');
							$objActSheet->setCellValueExplicit('C' . $index, '');
							$objActSheet->setCellValueExplicit('D' . $index, '');
							$objActSheet->setCellValueExplicit('E' . $index, '');
							$objActSheet->setCellValueExplicit('F' . $index, '');
							$objActSheet->setCellValueExplicit('G' . $index, '');
							$objActSheet->setCellValueExplicit('H' . $index, '');
							$objActSheet->setCellValueExplicit('I' . $index, '');
							$objActSheet->setCellValueExplicit('J' . $index, $value['goods_name']);
							$objActSheet->setCellValueExplicit('K' . $index, $value['goods_spec']);
							$objActSheet->setCellValueExplicit('L' . $index, $value['goods_price']);
							$objActSheet->setCellValueExplicit('M' . $index, $value['goods_num']);
							$objActSheet->setCellValueExplicit('N' . $index, $value['goods_unit']);
							$objActSheet->setCellValueExplicit('O' . $index, $value['show_status']);
							$objActSheet->setCellValueExplicit('P' . $index, floatval($value['total_money']));
							$objActSheet->setCellValueExplicit('Q' . $index, floatval($value['system_balance']));
							$objActSheet->setCellValueExplicit('R' . $index, floatval($value['pay_money']));
							$objActSheet->setCellValueExplicit('S' . $index, floatval($value['merchant_balance_pay']));
							$objActSheet->setCellValueExplicit('T' . $index, floatval($value['system_score_money']));
							$objActSheet->setCellValueExplicit('U' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
							$objActSheet->setCellValueExplicit('V' . $index, D('Pay')->get_pay_name($value['pay_method'], $value['is_mobile_pay'], $value['paid']));
							$objActSheet->setCellValueExplicit('W' . $index, '支付余额');
							$index_good = 1;
						}

						$index++;
					}else{
						$index++;
						$objActSheet->setCellValueExplicit('A' . $index, $value['order_id']);
						$objActSheet->setCellValueExplicit('B' . $index, $value['merchant_name']);
						$objActSheet->setCellValueExplicit('C' . $index, $value['store_name']);
						$objActSheet->setCellValueExplicit('D' . $index, $value['name']);
						$objActSheet->setCellValueExplicit('E' . $index,$value['phone']);
						$objActSheet->setCellValueExplicit('F' . $index, $value['book_price']);
						$objActSheet->setCellValueExplicit('G' . $index, $value['book_time'] ? date('Y-m-d H:i:s', $value['book_time']) : '');
						$objActSheet->setCellValueExplicit('H' . $index,$value['table_type_name']);
						$objActSheet->setCellValueExplicit('I' . $index,$value['table_name']);
						$objActSheet->setCellValueExplicit('J' . $index,'');
						$objActSheet->setCellValueExplicit('K' . $index,'');
						$objActSheet->setCellValueExplicit('L' . $index,'');
						$objActSheet->setCellValueExplicit('M' . $index,'');
						$objActSheet->setCellValueExplicit('N' . $index,'');
						$objActSheet->setCellValueExplicit('O' . $index, $value['show_status']);
						$objActSheet->setCellValueExplicit('P' . $index, floatval($value['total_money']));
						$objActSheet->setCellValueExplicit('Q' . $index, floatval($value['system_balance']));
						$objActSheet->setCellValueExplicit('R' . $index, floatval($value['pay_money']));
						$objActSheet->setCellValueExplicit('S' . $index, floatval($value['merchant_balance_pay']));
						$objActSheet->setCellValueExplicit('T' . $index, floatval($value['system_score_money']));
						$objActSheet->setCellValueExplicit('U' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
						$objActSheet->setCellValueExplicit('V' . $index,D('Pay')->get_pay_name($value['pay_method'], $value['is_mobile_pay'], $value['paid']));
						$objActSheet->setCellValueExplicit('W' . $index,'支付定金');
						$index++;
						$index_good = 0;
					}
					$tmp_id = $value['order_id'];

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

	public function shop_list()
	{
		$where = "s.status=1 AND s.have_meal=1";//array('status' => 1);

		if(!empty($_GET['keyword'])){
			$where .= " AND s.name LIKE '%{$_GET['keyword']}%'";
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
			$where .= " AND s.{$area_index} = '{$this->system_session['area_id']}'";
		}
		$sql_count = "SELECT count(1) AS cnt FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "merchant_store_foodshop AS sh ON `sh`.`store_id`=`s`.`store_id` WHERE {$where}";
		$mode = new model();
		$count = $mode->query($sql_count);
		$count = isset($count[0]['cnt']) ? $count[0]['cnt'] : 0;
		import('@.ORG.system_page');
		$p = new Page($count, 20);

		$sql = "SELECT `m`.`name` AS merchant_name, `s`.`name` AS store_name, `m`.`phone` AS merchant_phone, `s`.`phone` AS store_phone, `s`.`store_id`, `sh`.* FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "merchant_store_foodshop AS sh ON `sh`.`store_id`=`s`.`store_id` WHERE {$where} ORDER BY `sh`.`sort` DESC, `sh`.`store_id` DESC LIMIT {$p->firstRow}, {$p->listRows}";
		$order_list = $mode->query($sql);

		$this->assign('order_list', $order_list);
		$this->assign('pagebar', $p->show());
		$this->display();
	}
	
	public function edit_sort()
	{
	    $this->assign('bg_color','#F3F3F3');
	    $database = D('Merchant_store_foodshop');
	    $where['store_id'] = intval($_GET['store_id']);
	    $now_shop = $database->field(true)->where($where)->find();
	
	    if (empty($now_shop)) {
	        $this->frame_error_tips('没有找到该店铺信息！');
	    }
	    $now_store = D('Merchant_store')->field(true)->where($where)->find();
	    unset($now_store['sort']);
	    $now_shop = array_merge($now_shop, $now_store);
	    $this->assign('now_shop', $now_shop);
	    $this->display();
	}
	
	public function edit_amend()
	{
	    if (IS_POST) {
	        $where = array('store_id' => $_POST['id']);
	        unset($_POST['id']);
	        if (D('Merchant_store_foodshop')->where($where)->save($_POST)) {
	            $this->success('编辑成功！');
	        } else {
	            $this->error('编辑失败！请重试~');
	        }
	    } else {
	        $this->error('非法提交,请重新提交~');
	    }
	}

}