<?php
/*
 * 社区首页
 *
 */
class RepairAction extends BaseAction{
	protected $village_id;
	protected $village;
	
	public function _initialize(){
		parent::_initialize();
	
		$this->village_id = $this->house_session['village_id'];
		$this->village = D('House_village')->where(array('village_id'=>$this->village_id))->find();
		if(empty($this->village)){
			$this->error('该小区不存在！');
		}
	}
	
    //在线报修提醒
    public function repair_reminder(){
    	//在线报修-查看 权限
        if (!in_array(219, $this->house_session['menus'])) {
            exit(json_encode(array('error'=>0,'msg'=>'对不起，您没有权限执查看在线报修信息')));
        }
        $sum = M('House_village')->where(array('village_id'=>$this->village_id))->getField('repair_reminder');
        if($sum > 0){
            M('House_village')->where(array('village_id'=>$this->village_id))->setField('repair_reminder',0);
            exit(json_encode(array('error'=>1,'msg'=>'您有 ('.$sum.') 在线报修提醒！')));
        }else{
            exit(json_encode(array('error'=>0,'msg'=>'暂无在线报修信息')));
        }
    }

    //投诉建议提醒
    public function suggest_reminder(){
    	//投诉建议-查看 权限
        if (!in_array(224, $this->house_session['menus'])) {
            exit(json_encode(array('error'=>0,'msg'=>'对不起，您没有权限执查看投诉建议信息')));
        }
        $sum = M('House_village')->where(array('village_id'=>$this->village_id))->getField('suggest_reminder');
        if($sum > 0){
            M('House_village')->where(array('village_id'=>$this->village_id))->setField('suggest_reminder',0);
            exit(json_encode(array('error'=>1,'msg'=>'您有 ('.$sum.') 投诉建议提醒！')));
        }else{
            exit(json_encode(array('error'=>0,'msg'=>'暂无投诉建议信息')));
        }
    }

    public function index()
    {
        //在线报修-查看 权限
        if (!in_array(219, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		if($find_type = $_GET['find_type'] + 0){
			switch($find_type){
				case 1:
					$where['usernum'] = $_GET['find_value'];
					break;
				case 2:
					$where['phone'] = $_GET['find_value'];
					break;
				case 3:
					$where['address'] = $_GET['find_value'];
					break;
				default:
					break;
			}
		}


		$status = $_GET['status'] + 0;
		if($status > 0){
			$where['status'] = $status - 1;
		}

		$begin_time = 0;
		$end_time = 0;
		if(isset($_GET['begin_time']) && !empty($_GET['begin_time'])){
			$begin_time = strtotime($_GET['begin_time'] . '00:00:00');
		}

		if(isset($_GET['end_time']) && !empty($_GET['end_time'])){
			$end_time = strtotime($_GET['end_time'] . '23:59:59');
		}

		if(($begin_time > 0) && ($end_time > 0)){
			if($begin_time > $end_time){
				$this->error("结束时间应大于开始时间");
			}

			$where['begin_time'] = $begin_time;
			$where['end_time'] = $end_time;
		}elseif(isset($begin_time)){
			$where['begin_time'] = $begin_time;
		}elseif(isset($end_time)){
			$where['end_time'] = $end_time;
		}

		$where['village_id'] = $this->village_id;
		$where['type'] = 1;

		$order ='';
		if($_GET['time']){
			$order['time'] = $_GET['time'];
		}


		$repair_list = D('House_village_repair_list')->getlist($where , 20 , $order);
		//print_r($repair_list);
		$this->assign('repair_list', $repair_list);
		$this->display();
    }
	
    public function water()
    {
        //水电煤上报-查看 权限
        if (!in_array(222, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		$repair_list = D('House_village_repair_list')->getlist(array('village_id' => $this->village_id,'type'=>2));
		$this->assign('repair_list', $repair_list);
		$this->display();
    }
    
    public function suggess(){
    	$village_id = $this->village_id;
    	if($village_id){
    		$repair_list = D('House_village_repair_list')->getlist(array('village_id'=>$village_id,'type'=>3));
    		$this->assign('repair_list',$repair_list);
    	}
    	$this->display();
    }
    
    public function do_repair(){
    	if(IS_AJAX){
	        //在线报修-标记为已处理 权限
	        if (!in_array(220, $this->house_session['menus']) && !in_array(223, $this->house_session['menus']) ) {
	            $this->ajaxReturn(array('msg'=>'对不起，您没有权限执行此操作','error'=>1));
	        }

    		$village_id = $this->village_id;
    		$bind_id = $_POST['bind_id']?intval($_POST['bind_id']):0;
    		$cms_id = $_POST['cid']?intval($_POST['cid']):0;
    		if($bind_id && $village_id){
    			$data['village_id'] = $this->village_id;
    			$data['bind_id'] = $bind_id;
    			$data['pigcms_id'] = $cms_id;
    			
    			$result = D('House_village_repair_list')->where($data)->data(array('is_read'=>1))->save();
    			if($result !== false){
    				$this->ajaxReturn(array('error'=>0));
    			}
    			
    			$this->ajaxReturn(array('msg'=>'处理失败请重试','error'=>1));
    		}else{
    			$this->ajaxReturn(array('msg'=>'信息有误','error'=>1));
    		}
    		exit;
    	}else{
    		$this->display();
    	}
    }
    
    public function info()
    {
        //在线报修-查看 权限
        if (!in_array(219, $this->house_session['menus']) && !in_array(222, $this->house_session['menus']) && !in_array(224, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

    	$bind_id = isset($_GET['bindid']) ? intval($_GET['bindid']) : 0;
    	$cms_id = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
    	if ($bind_id && $cms_id) {
    		$condition['bind_id'] = $bind_id;
    		$condition['pigcms_id'] = $cms_id;
    		$condition['village_id'] = $this->village_id;
    		$repair = D('House_village_repair_list')->getlist($condition, 1);
			$repair = $repair['repair_list'][0];

			//查询跟进内容
			$follow = array();
			if ($repair['r_status'] >= 2) {
				$follow = D('House_village_repair_follow')->field(true)->where(array('repair_id' => $cms_id))->select();
				if ($follow) {
					foreach ($follow as &$value) {
						$value['time'] = date('Y-m-d H:i:s',$value['time']);
					}
				}
			}
			// var_dump($follow);
    		$this->assign('repair', $repair);
    		$this->assign('follow', $follow);
    		if ($repair['r_status']) {
    			$worker = D('House_worker')->field(true)->where(array('wid' => $repair['wid'], 'village_id' => $this->village_id))->find();
    			$this->assign('worker', $worker);
    		} else {
	    		$type = $repair['r_type'] == 1 ? 1 : 0;
		    	$workers = D('House_worker')->field(true)->where(array('type' => $type, 'status' => 1, 'village_id' => $this->village_id))->select();
		    	$this->assign('workers', $workers);
    		}
    	}
    	$this->display();
    }
    
    
    public function village_suggest()
    {
        //投诉建议-查看 权限
        if (!in_array(224, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

		if($find_type = $_GET['find_type'] + 0){
			switch($find_type){
				case 1:
					$where['usernum'] = $_GET['find_value'];
					break;
				case 2:
					$where['phone'] = $_GET['find_value'];
					break;
				case 3:
					$where['address'] = $_GET['find_value'];
					break;
				default:
					break;
			}
		}


		$status = $_GET['status'] + 0;
		if($status > 0){
			$where['status'] = $status - 1;
		}

		$begin_time = 0;
		$end_time = 0;
		if(isset($_GET['begin_time']) && !empty($_GET['begin_time'])){
			$begin_time = strtotime($_GET['begin_time'] . '00:00:00');
		}

		if(isset($_GET['end_time']) && !empty($_GET['end_time'])){
			$end_time = strtotime($_GET['end_time'] . '23:59:59');
		}

		if(($begin_time > 0) && ($end_time > 0)){
			if($begin_time > $end_time){
				$this->error("结束时间应大于开始时间");
			}

			$where['begin_time'] = $begin_time;
			$where['end_time'] = $end_time;
		}elseif(isset($begin_time)){
			$where['begin_time'] = $begin_time;
		}elseif(isset($end_time)){
			$where['end_time'] = $end_time;
		}

		$where['village_id'] = $this->village_id;
		$where['type'] = 3;

		$order ='';
		if($_GET['time']){
			$order['time'] = $_GET['time'];
		}

		$database_house_village_repair_list = D('House_village_repair_list');
		$repair_list = $database_house_village_repair_list->getlist( $where , 20 , $order );
		

		$this->assign('repair_list', $repair_list);
        $this->display();
    }
    
    
    public function ajax_suggest_reply()
    {
        if (IS_AJAX) {
            $pigcms_id = isset($_POST['pigcms_id']) ? intval($_POST['pigcms_id']) : 0;
            $worker_id = isset($_POST['worker_id']) ? intval($_POST['worker_id']) : 0;
//             $reply_content = $this->_post('reply_content');
            $database_house_village_repair_list = D('House_village_repair_list');
            $repair = $database_house_village_repair_list->field(true)->where(array('pigcms_id' => $pigcms_id, 'village_id' => $this->village_id))->find();
            if (empty($repair)) {
                exit(json_encode(array('status' => 0,'msg' => '传递参数有误！')));
            }
            $worker = D('House_worker')->field(true)->where(array('wid' => $worker_id, 'village_id' => $this->village_id))->find();
            if (empty($worker)) {
                exit(json_encode(array('status' => 0, 'msg' => '工作人员不能为空！')));
            }
            
            $data['wid'] = $worker_id;
            $data['status'] = 1;
            $where['village_id'] = $this->village_id;
//             $data['reply_time'] = time();
//             $data['is_read'] = 1;
            $where['pigcms_id'] = $pigcms_id;
			if ($database_house_village_repair_list->where($where)->save($data)) {
				D('House_village_repair_log')->add_log(array('status' => 1, 'repair_id' => $pigcms_id, 'phone' => $worker['phone'], 'name' => $worker['name']));
				exit(json_encode(array('status'=>1,'msg'=>'提交成功！')));
			} else {
                exit(json_encode(array('status'=>0,'msg'=>'提交失败！')));
            }
        }else{
            $this->error_tips('访问页面有误！请重试~');
        }
    }


	public function repair_export(){
		if($find_type = $_GET['find_type'] + 0){
			switch($find_type){
				case 1:
					$where['usernum'] = $_GET['find_value'];
					break;
				case 2:
					$where['phone'] = $_GET['find_value'];
					break;
				case 3:
					$where['address'] = $_GET['find_value'];
					break;
				default:
					break;
			}
		}


		$status = $_GET['status'] + 0;
		if($status > 0){
			$where['status'] = $status - 1;
		}

		$begin_time = 0;
		$end_time = 0;
		if(isset($_GET['begin_time']) && !empty($_GET['begin_time'])){
			$begin_time = strtotime($_GET['begin_time'] . '00:00:00');
		}

		if(isset($_GET['end_time']) && !empty($_GET['end_time'])){
			$end_time = strtotime($_GET['end_time'] . '23:59:59');
		}

		if(($begin_time > 0) && ($end_time > 0)){
			if($begin_time > $end_time){
				$this->error("结束时间应大于开始时间");
			}

			$where['begin_time'] = $begin_time;
			$where['end_time'] = $end_time;
		}elseif(isset($begin_time)){
			$where['begin_time'] = $begin_time;
		}elseif(isset($end_time)){
			$where['end_time'] = $end_time;
		}

		$where['village_id'] = $this->village_id;

		$type = $_GET['type'] + 0;
		$where['type'] = $type;

		$count = D('House_village_repair_list')->getlist($where, -100);

		if($count <= 0 ){
			$this->error('无数据导出！');
		}

		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

		if($type == 1){
	        //在线报修-导出 权限
	        if (!in_array(221, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');
	        }

			$title = $this->village['village_name'] . '社区-在线报修列表';
		}elseif($type == 3){
	        //投诉列表-导出 权限
	        if (!in_array(225, $this->house_session['menus'])) {
	            $this->error('对不起，您没有权限执行此操作');
	        }

			$title = $this->village['village_name'] . '社区-投诉列表';
		}

		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		$len = ceil($count/1000);
		for ($i = 0; $i <= $len; $i++) {
		    $_GET['page'] = $i + 1;
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);
			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个用户');
			$objActSheet = $objExcel->getActiveSheet();

			$objActSheet->setCellValue('A1', '业主编号');
			$objActSheet->setCellValue('B1', '报修人');
			$objActSheet->setCellValue('C1', '联系号码');
			$objActSheet->setCellValue('D1', '状态');
			$objActSheet->setCellValue('E1', '报修内容');
			$objActSheet->setCellValue('F1', '报修时间');
			$objActSheet->setCellValue('G1', '报修地址');
			$objActSheet->setCellValue('H1', '处理人员');
			$objActSheet->setCellValue('I1', '处理人员手机号码');
			$objActSheet->setCellValue('J1', '接单留言');
			$objActSheet->setCellValue('K1', '跟进内容');
			$objActSheet->setCellValue('L1', '回复内容');
			$objActSheet->setCellValue('M1', '回复时间');
			$objActSheet->setCellValue('N1', '评论内容');
			$objActSheet->setCellValue('O1', '评论时间');

			
			$repair_list = D('House_village_repair_list')->getlist($where,1000);
			$repair_list = $repair_list['repair_list'];
			if (!empty($repair_list)) {
				$index = 2;

				$cell_list = range('A','M');
				foreach ($cell_list as $cell) {
					$objActSheet->getColumnDimension($cell)->setWidth(40);
				}

				if($type == 1){
					foreach ($repair_list as $value) {
						$objExcel->getActiveSheet()->getStyle('K'.$index)->getAlignment()->setWrapText(true);//设置换行
						$objActSheet->setCellValueExplicit('A' . $index, $value['usernum']);
						$objActSheet->setCellValueExplicit('B' . $index, $value['name']);
						$objActSheet->setCellValueExplicit('C' . $index, $value['phone']);

						if($value['r_status'] == 0){
							$status_val = '未指派';
						}elseif($value['r_status'] == 1){
							$status_val = '已指派';
						}elseif($value['r_status'] == 2){
							$status_val = '已受理';
						}elseif($value['r_status'] == 3){
							$status_val = '已处理';
						}elseif($value['r_status'] == 4){
							$status_val = '业主已评价';
						}
						$objActSheet->setCellValueExplicit('D' . $index, $status_val);
						$objActSheet->setCellValueExplicit('E' . $index, $value['content']);
						$objActSheet->setCellValueExplicit('F' . $index, date('Y-m-d H:i:s',$value['time']));
						$objActSheet->setCellValueExplicit('G' . $index, $value['address']);

						$bind_id = $value['bind_id'];
						$cms_id = $value['pid'];
						if ($bind_id && $cms_id) {
							$condition['bind_id'] = $bind_id;
							$condition['pigcms_id'] = $cms_id;
							$condition['village_id'] = $this->village_id;
							$repair = D('House_village_repair_list')->getlist($condition, 1);
							$repair = $repair['repair_list'][0];

							if ($repair['status']) {
								$worker = D('House_worker')->field(true)->where(array('wid' => $repair['wid'], 'village_id' => $this->village_id))->find();
							}

							$follow_content = '';
							$follow = D('House_village_repair_follow')->field(true)->where(array('repair_id' => $cms_id))->select();
							if ($follow) {
								foreach ($follow as $_follow) {
									$_follow['time'] = date('Y-m-d H:i:s',$_follow['time']);
									$follow_content .= $_follow['time'] .' - '.$_follow['content'] . "\r\n";
								}
							}
							
							$objActSheet->setCellValueExplicit('H' . $index, $worker['name']);
							$objActSheet->setCellValueExplicit('I' . $index, $worker['phone']);
							$objActSheet->setCellValueExplicit('J' . $index, $repair['msg']);
							$objActSheet->setCellValueExplicit('K' . $index, $follow_content);
							$objActSheet->setCellValueExplicit('L' . $index, $repair['reply_content']);
							$objActSheet->setCellValueExplicit('M' . $index, $repair['reply_time']>0 ? date('Y-m-d H:i:s',$repair['reply_time']) : "");
							$objActSheet->setCellValueExplicit('N' . $index, $repair['comment']);
							$objActSheet->setCellValueExplicit('O' . $index, $repair['comment_time']>0 ? date('Y-m-d H:i:s',$repair['comment_time']) : "");
						}

						$index++;
					}
				}elseif($type == 3){

					foreach ($repair_list as $value) {
						$objActSheet->setCellValueExplicit('A' . $index, $value['usernum']);
						$objActSheet->setCellValueExplicit('B' . $index, $value['name']);
						$objActSheet->setCellValueExplicit('C' . $index, $value['phone']);

						if($value['r_status'] == 0){
							$status_val = '未受理';
						}elseif($value['r_status'] == 1){
							$status_val = '物业已受理';
						}elseif($value['r_status'] == 2){
							$status_val = '客服专员已受理';
						}elseif($value['r_status'] == 3){
							$status_val = '客服专员已处理';
						}elseif($value['r_status'] == 4){
							$status_val = '业主已评价';
						}

						$objActSheet->setCellValueExplicit('D' . $index, $status_val);
						$objActSheet->setCellValueExplicit('E' . $index, $value['content']);
						$objActSheet->setCellValueExplicit('F' . $index, date('Y-m-d H:i:s',$value['time']));
						$objActSheet->setCellValueExplicit('G' . $index, $value['address']);


						$bind_id = $value['bind_id'];
						$cms_id = $value['pid'];
						if ($bind_id && $cms_id) {
							$condition['bind_id'] = $bind_id;
							$condition['pigcms_id'] = $cms_id;
							$condition['village_id'] = $this->village_id;
							$repair = D('House_village_repair_list')->getlist($condition, 1);
							$repair = $repair['repair_list'][0];

							if ($repair['status']) {
								$worker = D('House_worker')->field(true)->where(array('wid' => $repair['wid'], 'village_id' => $this->village_id))->find();
							}

							$follow_content = '';
							$follow = D('House_village_repair_follow')->field(true)->where(array('repair_id' => $cms_id))->select();
							if ($follow) {
								foreach ($follow as $_follow) {
									$_follow['time'] = date('Y-m-d H:i:s',$_follow['time']);
									$follow_content .= $_follow['time'] .' - '.$_follow['content'] . "\r\n";
								}
							}

							$objActSheet->setCellValueExplicit('H' . $index, $worker['name']);
							$objActSheet->setCellValueExplicit('I' . $index, $worker['phone']);
							$objActSheet->setCellValueExplicit('J' . $index, $repair['msg']);
							$objActSheet->setCellValueExplicit('K' . $index, $follow_content);
							$objActSheet->setCellValueExplicit('L' . $index, $repair['reply_content']);
							$objActSheet->setCellValueExplicit('M' . $index, $repair['reply_time']>0 ? date('Y-m-d H:i:s',$repair['reply_time']) : "");
							$objActSheet->setCellValueExplicit('N' . $index, $repair['comment']);
							$objActSheet->setCellValueExplicit('O' . $index, $repair['comment_time']>0 ? date('Y-m-d H:i:s',$repair['comment_time']) : "");
						}

						$index++;
					}

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