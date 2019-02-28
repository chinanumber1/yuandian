<?php
/*
 * 推广营销 - 活动列表
 *
 * @  Writers    Jaty
 * @  BuildTime  2015/06/15 14:25
 * 
 */
class ActivityAction extends BaseAction{
	public function index(){
		$database_extension_activity = D('Extension_activity');
		$activity_list = $database_extension_activity->field(true)->order('`activity_id` DESC')->select();
		$this->assign('activity_list',$activity_list);
		$this->display();
	}
	public function add(){
		//找到活动的下一个时间段
		$database_extension_activity = D('Extension_activity');
		$max_end_time = $database_extension_activity->max('end_time');
		$this->assign('next_time',($max_end_time ? $max_end_time : 0));
		
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}
	public function modify(){
		if(IS_POST){
			//上传图片
			
			$image = D('Image')->handle($this->system_session['id'], 'extension', 0, array('size' => 10));
			if (!$image['error']) {
				$_POST = array_merge($_POST, str_replace('/upload/extension/', '', $image['url']));
			} else {
				$this->frame_submit_tips(0, $image['msg']);
			}
// 			$rand_num = date('Y/m',$_SERVER['REQUEST_TIME']);
// 			$upload_dir = './upload/extension/'.$rand_num.'/'; 
// 			if(!is_dir($upload_dir)){
// 				mkdir($upload_dir,0777,true);
// 			}
// 			import('ORG.Net.Upload File');
// 			$upload = new Upload File();
// 			$upload->maxSize = 10*1024*1024;
// 			$upload->allowExts = array('jpg','jpeg','png','gif');
// 			$upload->allowTypes = array('image/png','image/jpg','image/jpeg','image/gif');
// 			$upload->savePath = $upload_dir; 
// 			$upload->saveRule = 'uniqid';
// 			if($upload->upload()){
// 				$uploadList = $upload->getUpload FileInfo();
// 				$_POST['bg_pic'] = $rand_num.'/'.$uploadList[0]['savename'];
// 			}else{
// 				$this->frame_submit_tips(0,$upload->getErrorMsg());
// 			}
			$_POST['begin_time'] = strtotime($_POST['begin_time']);
			$_POST['end_time'] = strtotime($_POST['end_time']);
			$database_extension_activity = D('Extension_activity');
			if($activity_id = $database_extension_activity->data($_POST)->add()){
				D('Image')->update_table_id('/upload/extension/' . $_POST['bg_pic'], $activity_id, 'extension_activity');
				$this->frame_submit_tips(1,'添加成功！');
			}else{
				$this->frame_submit_tips(0,'添加失败！请重试~');
			}
		}else{
			$this->frame_submit_tips(0,'非法提交,请重新提交~');
		}
	}
	public function edit(){
		$this->assign('bg_color','#F3F3F3');
		$database_extension_activity = D('Extension_activity');
		$condition_extension_activity['activity_id'] = intval($_GET['id']);
		$now_activity = $database_extension_activity->field(true)->where($condition_extension_activity)->find();
		if(empty($now_activity)){
			$this->frame_error_tips('该活动不存在！');
		}
		$this->assign('now_activity',$now_activity);
		$this->display();
	}
	public function amend(){
		if(IS_POST){
			if($_FILES['bg_pic']['error'] != 4){
				
				$image = D('Image')->handle($this->system_session['id'], 'extension', 0, array('size' => 10));
				if (!$image['error']) {
					$_POST = array_merge($_POST, str_replace('/upload/extension/', '', $image['url']));
				} else {
					$this->frame_submit_tips(0, $image['msg']);
				}
				
				//上传图片
// 				$rand_num = date('Y/m',$_SERVER['REQUEST_TIME']);
// 				$upload_dir = './upload/extension/'.$rand_num.'/'; 
// 				if(!is_dir($upload_dir)){
// 					mkdir($upload_dir,0777,true);
// 				}
// 				import('ORG.Net.Uplo adFile');
// 				$upload = new Upload File();
// 				$upload->maxSize = 10*1024*1024;
// 				$upload->allowExts = array('jpg','jpeg','png','gif');
// 				$upload->allowTypes = array('image/png','image/jpg','image/jpeg','image/gif');
// 				$upload->savePath = $upload_dir; 
// 				$upload->saveRule = 'uniqid';
// 				if($upload->upload()){
// 					$uploadList = $upload->getUploadFi leInfo();
// 					$_POST['bg_pic'] = $rand_num.'/'.$uploadList[0]['savename'];
// 				}else{
// 					$this->frame_submit_tips(0,$upload->getErrorMsg());
// 				}
			}
			$_POST['begin_time'] = strtotime($_POST['begin_time']);
			$_POST['end_time'] = strtotime($_POST['end_time']);
			$database_extension_activity = D('Extension_activity');
			if($database_extension_activity->data($_POST)->save()){
				D('Image')->update_table_id('/upload/extension/' . $_POST['bg_pic'], $activity_id, 'extension_activity');
				$this->frame_submit_tips(1,'修改成功！');
			}else{
				$this->frame_submit_tips(0,'修改失败！请重试~');
			}
		}else{
			$this->frame_submit_tips(0,'非法提交,请重新提交~');
		}
	}
	public function del(){
		if(IS_POST){

			if(!empty($_POST['id'])){
				if(D('Extension_activity_list')->where(array('pigcms_id'=>$_POST['id']))->delete()){
					$this->success('删除成功');
				}else{
					$this->error('删除失败！');
				}
			}
			if(!empty($_POST['category_id'])){
				if(!M('Extension_activity')->where(array('activity_id'=>$_POST['category_id']))->delete()){
					$this->error('删除失败！！');
				}else{
					M('Extension_activity_list')->where(array('activity_term'=>$_POST['category_id']))->delete();
					$this->success('删除成功');
				}
			}
		}
		$this->error('活动暂时不能删除~');
	}
	
	public function activity_list(){
		$database_extension_activity = D('Extension_activity');
		$condition_extension_activity['activity_id'] = intval($_GET['id']);
		$now_activity = $database_extension_activity->field(true)->where($condition_extension_activity)->find();
		if(empty($now_activity)){
			$this->error_tips('该活动不存在');
		}
		$this->assign('now_activity',$now_activity);
		
		$database_extension_activity_list = D('Extension_activity_list');
		$condition_extension_activity_list['activity_term'] = $_GET['id'];
		$activity_list = $database_extension_activity_list->field(true)->where($condition_extension_activity_list)->order('`pigcms_id` DESC')->select();
		if(empty($activity_list)){
			$this->error_tips('该活动暂时没有商家参与');
		}
		foreach($activity_list as &$value){
			$value['type_txt'] = $this->type_txt($value['type']);
		}

		if($this->system_session['level'] == 1 || $this->system_session['level'] == 3){
			$database_merchant = D('Merchant');
			foreach($activity_list as $key=>$value){
				$now_merchant = $database_merchant->field('`city_id`,`area_id`')->where(array('mer_id'=>$value['mer_id']))->find();
				if($now_merchant['city_id'] != $this->system_session['area_id'] && $now_merchant['area_id'] != $this->system_session['area_id']){
					unset($activity_list[$key]);
				}
			}
		}
		
		
		$this->assign('activity_list',$activity_list);
		$this->display();
	}
	protected function type_txt($type){
		switch($type){
			case '1':
				return '一元夺宝';
			case '2':
				return '优惠券';
			case '3':
				return '秒杀';
			case '4':
				return '红包';
			case '5':
				return '卡券';
		}
	}

	public function yydb_order_list(){
		$activity_id = $_GET['activity_id'];
		$this->assign('activity_id',$activity_id);
		$where =array('activity_list_id'=>$activity_id);
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] = " (time BETWEEN ".$period[0].' AND '.$period[1].")";
		}
		$count = M('Extension_activity_record')->where($where)->count();
		import('@.ORG.system_page');
		$p = new Page($count,20);
		$list = M('Extension_activity_record')->join('a left join '.C('DB_PREFIX').'user u ON a.uid = u.uid')->where($where)->order('pigcms_id DESC')->limit($p->firstRow,$p->listRows)->select();
		$this->assign('pagebar',$p->show());
		$this->assign('list',$list);
		$this->display();
	}

	public function export()
	{
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '一元夺宝订单信息';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		// 设置当前的sheet
		$activity_id = $_GET['activity_id'];
		$this->assign('activity_id',$activity_id);
		$where =array('activity_list_id'=>$activity_id);
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] = " (time BETWEEN ".$period[0].' AND '.$period[1].")";
		}
		$count = M('Extension_activity_record')->where($where)->count();


		$length = ceil($count/ 1000);
		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);

			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
			$objActSheet = $objExcel->getActiveSheet();

			$objActSheet->setCellValue('A1', '编号');
			$objActSheet->setCellValue('B1', '用户姓名');
			$objActSheet->setCellValue('C1', '用户手机');
			$objActSheet->setCellValue('D1', '数量');
			$objActSheet->setCellValue('E1', '金额');
			$objActSheet->setCellValue('F1', '购买时间');

			$result_list = M('Extension_activity_record')->join('a left join '.C('DB_PREFIX').'user u ON a.uid = u.uid')->where($where)->order('pigcms_id DESC')->limit(($i*1000).',1000')->select();


			if (!empty($result_list)) {
				$index = 2;
				foreach ($result_list as $value) {
					$objActSheet->setCellValueExplicit('A' . $index, $value['pigcms_id']);
					$objActSheet->setCellValueExplicit('B' . $index, $value['nickname']);
					$objActSheet->setCellValueExplicit('C' . $index, $value['phone'] . ' ');
					$objActSheet->setCellValueExplicit('D' . $index, $value['part_count'] . ' ');
					$objActSheet->setCellValueExplicit('E' . $index, $value['part_count'].'元');
					$objActSheet->setCellValueExplicit('F' . $index, date('Y-m-d H:i:s',$value['time']));
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