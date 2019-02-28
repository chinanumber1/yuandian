<?php
/*
 * 付款管理
 *
 */
class CompanypayAction extends BaseAction{
	public function index(){
		if($this->config['company_pay_open']==0){   redirect(U('Companypay/withdraw_by_hand'));die;}
		if (!empty($_GET['keyword'])) {
			if ($_GET['searchtype'] == 'pay_id') {
				$condition['pay_id'] = $_GET['keyword'];
			} else if ($_GET['searchtype'] == 'phone') {
				$condition['phone'] = $_GET['keyword'] ;
			} else if($_GET['searchtype'] == 'user'){
				$condition['pay_id'] = $_GET['keyword'] ;
				$condition['pay_type'] = 'user' ;
			} else if($_GET['searchtype'] == 'house'){
				$condition['pay_id'] = $_GET['keyword'] ;
				$condition['pay_type'] = 'house' ;
			}

		}
		if($_GET['searchstatus']>-1){
			$condition['status']=$_GET['searchstatus'];
		}else{
			$_GET['searchstatus']=-1;
		}
		$order_string = '`pigcms_id` DESC';
		if($_GET['sort']){
			switch($_GET['sort']){
				case 'uid':
					$order_string = '`uid` DESC';
					break;
				case 'lastTime':
					$order_string = '`last_time` DESC';
					break;
				case 'money':
					$order_string = '`now_money` DESC';
					break;
				case 'score':
					$order_string = '`score_count` DESC';
					break;
			}
		}

		if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$time_condition = " (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition['_string']=$time_condition;
			$this->assign('begin_time',$_GET['begin_time']);
			$this->assign('end_time',$_GET['end_time']);
		}

		$pay = M('Companypay');
		$count_pay = $pay->where($condition)->count();
		$pay_type = array(
				'merchant'=>'商家',
				'user'=>'用户',
				'house'=>'社区',
				'withdraw'=>'商家提现',
		);
		$status = array('未支付','已支付', '已取消');
		if($_GET['export']==1){
			set_time_limit(0);
			require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
			$title = '导出付款数据';
			if($_GET['begin_time']&&$_GET['end_time']){
				$title.='('.$_GET['begin_time'].'至'.$_GET['end_time'].')';
			}
			$objExcel = new PHPExcel();
			$objProps = $objExcel->getProperties();
			// 设置文档基本属性
			$objProps->setCreator($title);
			$objProps->setTitle($title);
			$objProps->setSubject($title);
			$objProps->setDescription($title);
			$pay_list = $pay->field('pigcms_id,pay_type,pay_id,openid,nickname,phone,money,desc,status,add_time,pay_time,trade_no,payment_no,result')->where($condition)->order($order_string)->select();

			// 设置当前的sheet
			$length = ceil(count($pay_list) / 1000);
			for ($i = 0; $i < $length; $i++) {
				$i && $objExcel->createSheet();
				$objExcel->setActiveSheetIndex($i);

				$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个提现信息');
				$objActSheet = $objExcel->getActiveSheet();

				$objActSheet->setCellValue('A1', '编号');
				$objActSheet->setCellValue('B1', '付款类型');
				$objActSheet->setCellValue('C1', '商家/用户/社区ID');
				$objActSheet->setCellValue('D1', '联系电话');
				$objActSheet->setCellValue('E1', '金额');
				$objActSheet->setCellValue('F1', '描述');
				$objActSheet->setCellValue('G1', '添加时间');
				$objActSheet->setCellValue('H1', '支付时间');
				$objActSheet->setCellValue('I1', '状态');

				if (!empty($pay_list)) {
					$index = 2;
					foreach ($pay_list as $value) {
						$objActSheet->setCellValueExplicit('A' . $index, $value['pigcms_id']);
						$objActSheet->setCellValueExplicit('B' . $index, $pay_type[$value['pay_type']]);
						$objActSheet->setCellValueExplicit('C' . $index, $value['pay_id'] );
						$objActSheet->setCellValueExplicit('D' . $index, $value['phone']);
						$objActSheet->setCellValueExplicit('E' . $index, floatval($value['money']/100));
						$objActSheet->setCellValueExplicit('F' . $index, $value['desc']. '');
						$objActSheet->setCellValueExplicit('G' . $index, $value['add_time'] ? date('Y-m-d H:i:s', $value['add_time']) : '');
						$objActSheet->setCellValueExplicit('H' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '无');
						$objActSheet->setCellValueExplicit('I' . $index,$status[$value['status']]. '');
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
			header('Content-Disposition:attachment;filename="'.$title.'_' . date("Ymd h:i:s", time()) . '.xls"');
			header("Content-Transfer-Encoding:binary");
			$objWriter->save('php://output');
			exit();
		}
		import('@.ORG.system_page');
		$p = new Page($count_pay, 25);
		$pay_list = $pay->field('pigcms_id,pay_type,pay_id,openid,nickname,phone,money,desc,status,add_time,pay_time,trade_no,payment_no,result')->where($condition)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();

		$this->assign('pay_list',$pay_list);
		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);
		$this->display();
	}
	//将已取消的订单恢复到未支付状态
	public function restore(){
		$res = M('Companypay')->where(array('pigcms_id'=>$_GET['pigcms_id']))->find();
		foreach ($res as $key=>$v) {
			$data[$key] = $v;
		}

		if($res['pay_type']=='merchant'){
			D('Merchant_money_list')->withdraw_status_notice($res['pigcms_id']);
		}else if($res['pay_type']=='user'){
			D('Merchant_money_list')->withdraw_status_notice($res['pigcms_id'],1,true);
		}
		$data['status']=$_GET['status'];
		if(M('Companypay')->where(array('pigcms_id'=>$_GET['pigcms_id']))->delete() && M('Companypay')->add($data)){
			$this->success("操作成功！");
		}else{
			$this->success('操作失败！请联系管理员！');
		}
	}
	public function api(){
		if(empty($this->config['company_pay_open'])){
			echo json_encode(array('err_code'=>1003,'err_msg'=>'网站未开启软件付款功能'));
			exit();
		}
		if($_POST['webKey'] != $this->config['company_pay_encrypt']){
			echo json_encode(array('err_code'=>1001,'err_msg'=>'通信密钥错误，请重新填写'));
			exit();
		}
		if($_POST['action'] == 'saveOrder'){
			if($_POST['status'] == 'ok'){
				$condition_companypay = array(
					'pigcms_id'	 => $_POST['pigcms_id'],
				);
				$data_companypay = array(
					'trade_no' 	 => $_POST['trade_no'],
					'payment_no' => $_POST['payment_no'],
					'status'	 => '1',
					'pay_time'	 => strtotime($_POST['payment_time'])
				);
				if(D('Companypay')->where($condition_companypay)->data($data_companypay)->save()){
					echo json_encode(array('err_code'=>0,'err_msg'=>'订单保存成功'));
				}else{
					echo json_encode(array('err_code'=>1002,'err_msg'=>'订单保存失败，请重试'));
				}
				exit();
			}else if($_POST['status'] == 'del'){
				$condition_companypay = array(
					'pigcms_id'	 => $_POST['pigcms_id'],
				);
				$data_companypay = array(
					'status'	 => '2'
				);
				if(D('Companypay')->where($condition_companypay)->data($data_companypay)->save()){
					echo json_encode(array('err_code'=>0,'err_msg'=>'订单保存成功'));
				}else{
					echo json_encode(array('err_code'=>1002,'err_msg'=>'订单保存失败，请重试'));
				}
				exit();
			}
		}else{
			// if(C('config.company_pay_house_oneDayPay')){ 
			// 	$village = D('House_village');
			// 	$village_info = $village->field('village_id,village_name,property_phone,last_bill_time')->select();
			// 	$village_pay = D('House_village_pay_order');
			// 	$companypay  = D('Companypay');
			// 	$add_time = time();
			// 	foreach($village_info as $v){
			// 		$sql_sum_money = "SELECT SUM(money) AS money FROM ".C('DB_PREFIX')."house_village_pay_order WHERE paid=1 AND is_pay_bill=0 AND pay_time>=".$v['last_bill_time']." AND village_id =".$v['village_id'];
			// 		$money = $village_pay->query($sql_sum_money);
			// 		$res= $village_pay->where("paid=1 AND is_pay_bill=0 AND pay_time>".$v['last_bill_time'])->setField('is_pay_bill',1);
			// 		if(!empty($money[0]['money'])){
			// 			$Values.="('house','".$v['village_id']."','".$v['property_phone']."','".($money[0]['money']*100)."','小区".$v['village_name']."订单对账|时间(".date('Y-m-d',$v['last_bill_time'])."~".date('Y-m-d').")|转账 ".$money[0]['money']." 元',0,".$add_time." ),";
			// 		}
			// 		$ids[]=$v['village_id'];
			// 	}
			// 	$sql_add_companypay = "INSERT INTO ".C('DB_PREFIX')."companypay (`pay_type`,`pay_id`,`phone`,`money`,`desc`,`status`,`add_time`) VALUES ".substr($Values,0,-1);
			// 	if(!$companypay->query($sql_add_companypay)){
			// 		if(!empty($ids)){
			// 			$where['village_id']=array('in',implode(',',$ids));
			// 			$village->where($where)->setField('last_bill_time',$add_time);
			// 		}	
			// 	}
				
			// }
			
			// $condition_companypay['status'] = '0';
			// if($_POST['webLastId']){
			// 	$condition_companypay['pigcms_id'] = array('gt',$_POST['webLastId']);
			// }
			
			// $payList = D('Companypay')->where($condition_companypay)->order('`pigcms_id` ASC')->limit(10)->select();
			// $returnList = array();
			// foreach($payList as $value){
			// 	$returnList[] = array(
			// 		'pigcms_id'	=>	$value['pigcms_id'],
			// 		'pay_type'	=>	$value['pay_type'],
			// 		'alias_type'=>	$this->getType($value['pay_type']),
			// 		'pay_id'	=>	$value['pay_id'],
			// 		'openid'	=>	$value['openid'],
			// 		'nickname'	=>	$value['nickname'],
			// 		'money'		=>	$value['money'],
			// 		'desc'		=>	$value['desc'],
			// 		'add_time'	=>	date('Y-m-d H:i:s',$value['add_time']),
			// 		'status'	=>	$value['status'],
			// 	);
			// }
			// echo json_encode(array('err_code'=>0,'result'=>$returnList,'count'=>count($returnList)));
			// exit();
		}
	}
	public function getType($pay_type){
		switch($pay_type){
			case 'merchant':
				return '商家';
			case 'user':
				return '用户';
			case 'house':
				return '社区';
			case 'village':
				return '社区';
		}
	}

	public function withdraw_by_hand(){
		if (!empty($_GET['keyword'])) {
			if ($_GET['searchtype'] == 'pay_id') {
				$condition['pay_id'] = $_GET['keyword'];
			} else if ($_GET['searchtype'] == 'phone') {
				$condition['phone'] = $_GET['keyword'] ;
			} else if($_GET['searchtype'] == 'user'){
				$condition['pay_id'] = $_GET['keyword'] ;

			}else if($_GET['searchtype'] == 'truename'){
				$condition['pay_id'] = array('like','%'.$_GET['keyword'].'%') ;

			}

		}
		$condition['pay_type'] = array('neq',2);
		$condition['withdraw_id'] = 0;
		if($_GET['searchstatus']>-1){
			$condition['status']=$_GET['searchstatus'];
		}else{
			$_GET['searchstatus']=-1;
		}
		$order_string = '`id` DESC';


		if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$time_condition = " (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition['_string']=$time_condition;
			$this->assign('begin_time',$_GET['begin_time']);
			$this->assign('end_time',$_GET['end_time']);
		}

		$pay = M('Withdraw_list');
		$count_pay = $pay->where($condition)->count();

		$pay_type = array(
				'0'=>'银行',
				'1'=>'支付宝',
		);
		$type = array(
				'user'=>'用户',
				'mer'=>'商家',
				'village'=>'社区',
		);
		$status = array('未支付','已支付', '已取消');
		if($_GET['export']==1){
			set_time_limit(0);
			require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
			$title = '导出付款数据';
			if($_GET['begin_time']&&$_GET['end_time']){
				$title.='('.$_GET['begin_time'].'至'.$_GET['end_time'].')';
			}
			$objExcel = new PHPExcel();
			$objProps = $objExcel->getProperties();
			// 设置文档基本属性
			$objProps->setCreator($title);
			$objProps->setTitle($title);
			$objProps->setSubject($title);
			$objProps->setDescription($title);
			$pay_list = $pay->field(true)->where($condition)->order($order_string)->select();

			// 设置当前的sheet
			$length = ceil(count($pay_list) / 1000);
			for ($i = 0; $i < $length; $i++) {
				$i && $objExcel->createSheet();
				$objExcel->setActiveSheetIndex($i);

				$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个提现信息');
				$objActSheet = $objExcel->getActiveSheet();

				$objActSheet->setCellValue('A1', '编号');
				$objActSheet->setCellValue('B1', '付款类型');
				$objActSheet->setCellValue('C1', '商家/用户/社区ID');
				$objActSheet->setCellValue('D1', '联系电话');
				$objActSheet->setCellValue('E1', '金额');
				$objActSheet->setCellValue('F1', '描述');
				$objActSheet->setCellValue('G1', '添加时间');
				$objActSheet->setCellValue('H1', '支付时间');
				$objActSheet->setCellValue('I1', '状态');

				if (!empty($pay_list)) {
					$index = 2;
					foreach ($pay_list as $value) {
						$objActSheet->setCellValueExplicit('A' . $index, $value['pigcms_id']);
						$objActSheet->setCellValueExplicit('B' . $index, $pay_type[$value['pay_type']]);
						$objActSheet->setCellValueExplicit('C' . $index, $value['pay_id'] );
						$objActSheet->setCellValueExplicit('D' . $index, $value['phone']);
						$objActSheet->setCellValueExplicit('E' . $index, floatval($value['money']/100));
						$objActSheet->setCellValueExplicit('F' . $index, $value['desc']. '');
						$objActSheet->setCellValueExplicit('G' . $index, $value['add_time'] ? date('Y-m-d H:i:s', $value['add_time']) : '');
						$objActSheet->setCellValueExplicit('H' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '无');
						$objActSheet->setCellValueExplicit('I' . $index,$status[$value['status']]. '');
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
			header('Content-Disposition:attachment;filename="'.$title.'_' . date("Ymd h:i:s", time()) . '.xls"');
			header("Content-Transfer-Encoding:binary");
			$objWriter->save('php://output');
			exit();
		}
		import('@.ORG.system_page');
		$p = new Page($count_pay, 25);
		$pay_list = $pay->field(true)->where($condition)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();

		$this->assign('pay_list',$pay_list);
		$this->assign('pay_type',$pay_type);
		$this->assign('type',$type);
		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);
		$this->display();
	}


	public function restore_withdraw(){
		$data['status']=$_GET['status'];
		if(D('Withdraw_list')->where(array('id'=>$_GET['id']))->save($data)){
			$withdraw = M('Withdraw_list')->where(array(array('id'=>$_GET['id'])))->find();
			if($withdraw['type']=='user'){
				D('Merchant_money_list')->withdraw_status_notice($withdraw['id'],1);
			}
			if($withdraw['withdraw_id']>0){
				M('Merhcant_withdraw')->where(array('id'=>$withdraw['withdraw_id']))->setField('status',1);
			}
			$this->frame_main_ok_tips("操作成功！",3,U('Companypay/withdraw_by_hand'));
		}else{
			$this->error_tips('操作失败！请联系管理员！',3,U('Companypay/withdraw_by_hand'));
		}
	}
}