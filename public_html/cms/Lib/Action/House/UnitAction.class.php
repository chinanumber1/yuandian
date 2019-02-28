<?php
/*
    小区单元控制器
 */
class UnitAction extends BaseAction{
    protected $village_id;

    public function _initialize(){
        parent::_initialize();
        if (empty($this->house_session['is_open_estate'])) {
            redirect($this->config['site_url'].'/shequ.php?g=House&c=Index&a=index');
            exit;
        }
        $this->village_id = $this->house_session['village_id'];
        $this->village = D('House_village')->where(array('village_id' => $this->village_id))->find();
        $this->role_id = $this->house_session['role_id']; //角色id
    }
    public $pay_list_type = array(
            'property'=>'物业费',
            'water'=>'水费',
            'electric'=>'电费',
            'gas'=>'燃气费',
            'park'=>'停车费',
            'custom'=>'其他缴费',
            'custom_payment'=>'自定义缴费',
    );
    public function index(){
        //单元列表-查看 权限
        if (!in_array(20, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        $database_house_village_floor = D('House_village_floor');
        $where['village_id'] = $this->house_session['village_id'];
        $list = $database_house_village_floor->house_village_floor_page_list($where);
        if(!$list){
            $this->error('数据处理有误！');
        }else{
            $this->assign($list);

        }
        $this->display();
    }
    
    public function unit_add(){
        //单元列表-添加 权限
        if (!in_array(21, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            $database_house_village_floor = D('House_village_floor');
            $result = $database_house_village_floor->house_village_floor_add($_POST);
            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $this->_get_floor_type_list();
            $this->_get_property_list();
            $this->display();
        }
    }

    public function import_village(){
        //房间管理-查看 权限
        if (!in_array(37, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $where['village_id'] = $this->village_id;
        if($_GET['status'] !=''){
            $where['status'] = $_GET['status'];
        }
        $result = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($where);

        if(!$result){
            $this->error('数据处理有误！');
        }

        $this->assign('result',$result['result']);
        $this->display();
    }
	
	//提取老用户的房间信息
	public function updata_old_village_room_info(){
			
			set_time_limit(0);
			$database_house_village_user_bind = D('House_village_user_bind');
			//$database_house_village_user_bind_test = D('House_village_user_bind_test');
			$database_house_village_user_vacancy = D('House_village_user_vacancy');
			
			$is_true_old_user = $_GET['$is_true_old_user'] + 0;
			
			if(empty($is_true_old_user)){
				$old_condition['status']     = 1;
				$old_condition['parent_id']  = 0;
				$old_condition['type']       = 0;
				$old_condition['vacancy_id'] = 0;
				$old_condition['layer_num'] = array('neq' , "");
				$old_condition['room_address'] = array('neq' , "");
				$is_true_old_user = $database_house_village_user_bind -> where($old_condition) -> count();
			}
			//$is_true_old_user = $_GET['is_true_old_user'] + 0;
			$limit = $_GET['limit'] + 0;
			if(!$limit) $limit = 1;
			
			if(empty($is_true_old_user) || empty($this->village_id))  $this->error('参数错误！');
			
			
			
			$old_condition['status']     = 1;
			$old_condition['parent_id']  = 0;
			$old_condition['type']       = 0;
			$old_condition['vacancy_id'] = 0;
			$old_condition['layer_num'] = array('neq' , "");
			$old_condition['room_address'] = array('neq' , "");
			//$old_condition['village_id'] = $this->village_id; //需要修改成动态小区ID

			$old_data = $database_house_village_user_bind -> field('pigcms_id,village_id,usernum,floor_id,layer_num,room_addrss,uid,name,phone,housesize,park_flag,add_time') -> where($old_condition)->limit($limit) -> select();
			if($old_data){
				foreach($old_data as $k=>$v){
					
					$insert_id = $update_id = 0;
					
					$data = array();
					$data['usernum']     =   $v['usernum'];
					$data['floor_id']    =   $v['floor_id'];
					$data['layer']       =   $v['layer_num'];
					$data['room']        =   $v['room_addrss'];
					$data['status']      =   3;
					$data['village_id']  =   $v['village_id'];
					$data['add_time']    =   $v['add_time'] ? $v['add_time'] : time();
					$data['uid']         =   $v['uid'];
					$data['name']        =   $v['name'];
					$data['phone']       =   $v['phone'];
					$data['type']        =   0;
					$data['memo']        =   $v['memo'] ? $v['memo'] : 'null';
					$data['is_del']      =   0;
					$data['del_time']    =   0;
					$data['housesize']   =   $v['housesize'];
					$data['park_flag']   =   $v['park_flag'];
					
					//生成房间信息
					$insert_id = $database_house_village_user_vacancy->data($data)->add();
					
					if($insert_id){
						$now_data = array();
						$now_data['vacancy_id'] = $insert_id;
						$now_data['pass_time'] = time();
						//更新业主绑定数据的房间ID
						$update_id = $database_house_village_user_bind->where(array('pigcms_id'=>$v['pigcms_id']))->data($now_data)->save();
						if($update_id){	
							//查询业主是否绑定亲属 如果绑定 那么也更新信息
							$relatives = array();
							$relatives['housesize']     = $v['housesize'];
							$relatives['park_flag']     = $v['park_flag'];
							$relatives['address']       = $v['address'];
							$relatives['layer_num']     = $v['layer_num'];
							$relatives['room_address']  = $v['room_address'];
							$relatives['floor_id']      = $v['floor_id'];
							$relatives['type']          = $v['type'];
							$relatives['vacancy_id']    = $v['vacancy_id'];
							$database_house_village_user_bind->where(array('parent_id'=>$v['pigcms_id']))->data($relatives)->save();
							
						}
					}
					
				}
				$is_true_old_user = $is_true_old_user < $limit ? $is_true_old_user : $is_true_old_user - $limit;
				$this->success('剩余'.($is_true_old_user).'个房间正在导入，请勿关闭页面，耐心等待！',U('updata_old_village_room_info',array('is_true_old_user'=>$is_true_old_user,'limit'=>50)),0);
			}else{
				$this->success('导入成功，正在跳转',U('import_village'));
			}
	}

    public function import_village_add(){
        //房间管理-导入 权限
        if (!in_array(38, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            if ($_FILES['file']['error'] != 4) {
                set_time_limit(0);
                $upload_dir = './upload/excel/villageuser/' . date('Ymd') . '/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();
                $upload->maxSize = 10 * 1024 * 1024;
                $upload->allowExts = array('xls', 'xlsx');
                $upload->allowTypes = array(); // 允许上传的文件类型 留空不做检查
                $upload->savePath = $upload_dir;
                $upload->thumb = false;
                $upload->thumbType = 0;
                $upload->imageClassPath = '';
                $upload->thumbPrefix = '';
                $upload->saveRule = 'uniqid';
                if ($upload->upload()) {
                    $uploadList = $upload->getUploadFileInfo();
                    require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel/IOFactory.php';
                    $path = $uploadList['0']['savepath'] . $uploadList['0']['savename'];
                    $fileType = PHPExcel_IOFactory::identify($path); //文件名自动判断文件类型
                    $objReader = PHPExcel_IOFactory::createReader($fileType);
                    $excelObj = $objReader->load($path);
                    $result = $excelObj->getActiveSheet()->toArray(null, true, true, true);
                    $error_arr = array();
                    if (!empty($result) && is_array($result)) {
                        unset($result[1]);
                        $last_user_id = 0;
                        $err_msg = '';
                        foreach ($result as $kk => $vv) {
                            if (array_sum($vv) == 0) {
                                continue;
                            }
                            if ($vv['A'] === null && $vv['B'] === null && $vv['C'] === null && $vv['D'] === null && $vv['E'] === null) continue;

                            if (empty($vv['A'])) {
                                $vv['G'] = '请填写物业编号！';
                                $error_arr[] = $vv;
                                // $err_msg .= '请填写物业编号！'.PHP_EOL;
                                continue;
                            }
                            if (empty($vv['B'])) {
                                $vv['G'] = '请填写单元号！';
                                $error_arr[] = $vv;
                                // $err_msg .= '请填写单元号！'.PHP_EOL;
                                continue;
                            }
                            if (empty($vv['C'])) {
                                $vv['G'] = '请填写楼号！';
                                $error_arr[] = $vv;
                                // $err_msg .= '请填写楼号！'.PHP_EOL;
                                continue;
                            }

                            if (empty($vv['D'])) {
                                $vv['G'] = '请填写层号！';
                                $error_arr[] = $vv;
                                // $err_msg .= '请填写层号！'.PHP_EOL;
                                continue;
                            }

                            if (empty($vv['E'])) {
                                $vv['G'] = '请填写房号！';
                                $error_arr[] = $vv;
                                // $err_msg .= '请填写房号！'.PHP_EOL;
                                continue;
                            }

                            if (empty($vv['F'])) {
                                $vv['G'] = '请填写房间面积！';
                                $error_arr[] = $vv;
                                // $err_msg .= '请填写房间面积！'.PHP_EOL;
                                continue;
                            }

                            $floor_name =  htmlspecialchars(trim($vv['B']), ENT_QUOTES);
                            $floor_layer = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                            $where['floor_name'] = $floor_name;
                            $where['floor_layer'] = $floor_layer;
                            $where['status'] = 1;
                            $where['village_id'] = $this->village_id;
                            $database_house_village_floor = D('House_village_floor');
                            $house_village_floor_info = $database_house_village_floor->where($where)->find();
                            if (!$house_village_floor_info) {
                                $vv['G'] = '单元不存在，请查看社区中心，单元管理-单元列表！';
                                $error_arr[] = $vv;
                                $err_msg .= '单元不存在，请查看社区中心，单元管理-单元列表！(ERROR_'.($kk+1).')'.PHP_EOL;
                                continue;
                            }

                            $tmpdata = array();
                            $tmpdata['usernum'] = $this->village_id . '-' . htmlspecialchars(trim($vv['A']), ENT_QUOTES);
                            //检测用户是否已存在
                            if (D('House_village_user_vacancy')->field('`usernum`')->where(array('usernum' => $tmpdata['usernum']))->find()) {
                                $vv['G'] = '房间已存在。';
                                $error_arr[] = $vv;
                                $err_msg .= '房间已存在。'.PHP_EOL;
                                continue;
                            }

                            if (D('House_village_user_bind')->field('`usernum`')->where(array('usernum' => $tmpdata['usernum']))->find()) {
                                $vv['G'] = '业主已存在。';
                                $error_arr[] = $vv;
                                $err_msg .= '业主已存在。'.PHP_EOL;
                                continue;
                            }

                            $tmpdata['layer'] = htmlspecialchars(trim($vv['D']), ENT_QUOTES);
                            $tmpdata['room'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
                            $tmpdata['housesize'] = htmlspecialchars(trim($vv['F']), ENT_QUOTES);
                            $tmpdata['floor_id'] = $house_village_floor_info['floor_id'];
                            $tmpdata['village_id'] = $this->village_id;
                            $tmpdata['status'] = 1;
                            $tmpdata['add_time'] = time();
                            $last_user_id = D('House_village_user_vacancy')->data($tmpdata)->add();
                            if (!$last_user_id) {
                                $vv['G'] = '保存失败。';
                                $error_arr[] = $vv;
                                $err_msg .= '业主编号为' . $vv['A'] . ' 导入失败！'.PHP_EOL;
                            }
                        }
                        if (empty($error_arr)) {
                            $this->success('导入成功');
                            exit;
                        } else {
                            $num = count($error_arr);
                            echo '<javascript>alert("失败'. $num .'条，点击下载)";</javascript>';
                            //导出失败信息
                            require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
                            error_reporting(E_ALL);  
                            date_default_timezone_set('Europe/London');  
                            $objExcel = new PHPExcel();  
                      
                            $title = $this->village['village_name'] . '社区-房间导入失败列表';
                            /*以下是一些设置 ，什么作者  标题之类的*/  
                             $objExcel->getProperties()->setCreator($title)  
                               ->setLastModifiedBy($title)  
                               ->setTitle($title)  
                               ->setSubject("失败信息导出")  
                               ->setDescription("备份数据")  
                               ->setKeywords("excel")  
                               ->setCategory("result file");  
                            
                            $i = 0;
                            $objExcel->createSheet();
                            $objExcel->setActiveSheetIndex($i);
                            $objExcel->getActiveSheet()->setTitle('房间列表');
                            $objActSheet = $objExcel->getActiveSheet();
                            $objActSheet->setCellValue('A1', '物业编号');
                            $objActSheet->setCellValue('B1', '单元名称');
                            $objActSheet->setCellValue('C1', '楼号');
                            $objActSheet->setCellValue('D1', '层号');
                            $objActSheet->setCellValue('E1', '房号');
                            $objActSheet->setCellValue('F1', '房子平方(必填/计算物业费使用');
                            $objActSheet->setCellValue('G1', '失败原因');
                            $index = 2;
                            foreach ($error_arr as  $value) {  
                                $objActSheet->setCellValueExplicit('A' . $index, $value['A']);
                                $objActSheet->setCellValueExplicit('B' . $index, $value['B']);
                                $objActSheet->setCellValueExplicit('C' . $index, $value['C']);
                                $objActSheet->setCellValueExplicit('D' . $index, $value['D']);
                                $objActSheet->setCellValueExplicit('E' . $index, $value['E']);
                                $objActSheet->setCellValueExplicit('F' . $index, $value['F']);
                                $objActSheet->setCellValueExplicit('G' . $index, $value['G']);
                                $index++;
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
                            header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
                            header("Content-Transfer-Encoding:binary");
                            $objWriter->save('php://output');
                            exit();
                        }
                    }
                } else {
                    $this->error($upload->getErrorMsg());
                    exit;
                }
            }
            $this->error('文件上传失败');
            exit;
        }else{
            $this->display();
        }
    }

    public function import_village_edit(){
        //房间管理-查看 权限
        if (!in_array(37, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $where['pigcms_id'] = $id;
        $where['village_id'] = $this->village_id;

        if(IS_POST){
            //房间管理-编辑 权限
            if (!in_array(39, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            $result = $database_house_village_user_vacancy->house_village_user_vacancy_edit_find($where,$_POST);

            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $detail = $database_house_village_user_vacancy->house_village_user_vacancy_detail($where);

            if(!$detail){
                $this->error('数据处理有误！');
            }else{
                if($detail['status']){
                    $this->assign('detail',$detail['detail']);
                }else{
                    $this->error('信息不存在！');
                }
                $this->display();
            }
        }
    }

    public function import_village_del(){
        //房间管理-删除 权限
        if (!in_array(40, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $database_house_village_user_bind = D('House_village_user_bind');
		
		$where['pigcms_id'] = $id;
		
		$yz_info = $database_house_village_user_vacancy->where(array('pigcms_id'=>$id))->find();
		$yz_bind_num = $database_house_village_user_bind -> where(array('vacancy_id'=>$id,'type'=>array('in','0,3'),'status'=>array('in','1,2')))->count();
		//要多一个判断 是否存在这条绑定信息  如果不存在也可以删除
		
		if(($yz_info['uid'] || $yz_info['name'] || $yz_info['phone']) && $yz_bind_num>0){
			$this->error('已绑定业主，无法删除！');	
		}
		
        $result = $database_house_village_user_vacancy->import_village_del($where);

        if($result['status']){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
        
    }

    public function import_village_del_many(){
        //房间管理-删除 权限
        if (!in_array(40, $this->house_session['menus'])) {
            exit(json_encode(array('status'=>0,'msg'=>'对不起，您没有权限执行此操作')));
        }

        $where['pigcms_id'] = array('in',$_POST['pigcms_id']);
        $result = D('House_village_user_vacancy')->import_village_del($where);
        if(!$result){
            $this->error('数据处理有误！');
        }else{
            exit(json_encode($result));
        }

    }

    public function unit_del(){
        //单元列表-删除 权限
        if (!in_array(23, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $floor_id = $_GET['floor_id'] + 0;
        if(!$floor_id){
            $this->error('传递参数有误！');
        }
        
        $database_house_village_floor = D('House_village_floor');
        $where['floor_id'] = $floor_id;
        $where['village_id'] = $this->house_session['village_id'];
        $where['parent_id'] = 0;
        $result = $database_house_village_floor->house_village_floor_del($where);
        
        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }
    
    
    public function unit_edit(){
        //单元列表-查看 权限
        if (!in_array(20, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        $floor_id = $_GET['floor_id'] + 0;
        if(!$floor_id){
            $this->error('传递参数有误！');
        }
        
        $database_house_village_floor = D('House_village_floor');
        $where['floor_id'] = $floor_id;
        if(IS_POST){
            //单元列表-编辑 权限
            if (!in_array(22, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            $result = $database_house_village_floor->house_village_floor_edit($where,$_POST);
            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $detail = $database_house_village_floor->house_village_floor_detail($where);
            if(!$detail){
                $this->error('数据处理有误！');
            }else{
                $this->assign('detail',$detail['detail']);
            }

            $this->_get_floor_type_list();
            $this->display();
        }
    }

    //单元类型列表
    public function unittype_list(){
        //单元类型列表-查看 权限
        if (!in_array(24, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        $database_house_village_floor_type = D('House_village_floor_type');

        $where['village_id'] = $_SESSION['house']['village_id'];
        $list = $database_house_village_floor_type->house_village_floor_type_page_list($where);

        if(!$list){
            $this->error('数据处理有误！~~~');
        }else{
            $this->assign('list' , $list['list']);
        }

        $this->display();
    }
    

    public function unittype_add(){
        //单元类型列表-添加 权限
        if (!in_array(25, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            $database_house_village_floor_type = D('House_village_floor_type');
            $result = $database_house_village_floor_type->house_village_floor_type_add($_POST);

            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }else{
            $this->display();
        }
    }


    public function unittype_edit(){
        //单元类型列表-查看 权限
        if (!in_array(24, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_floor_type = D('House_village_floor_type');
        $id = $_GET['id'] + 0;
        if(empty($id)){
            $this->error('传递参数有误！~~~');
        }

        $where['id'] = $id;
        if(IS_POST){
            //单元类型列表-编辑 权限
            if (!in_array(26, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $result = $database_house_village_floor_type->house_village_floor_type_edit($where , $_POST);
            if(!$result){
                $this->error('数据处理有误！~~~~');
            }else{
                if($result['status']==0){
                    $this->error($result['msg']);
                }else{
                    $this->success($result['msg']);
                }
            }

        }else{
            $detail = $database_house_village_floor_type->house_village_floor_type_detail($where);

            if(!$detail){
                $this->error('数据处理有误！~~~');
            }else{
                if($detail['status'] == 0){
                    $this->error('该信息不存在！');
                }else{
                    $this->assign('detail',$detail['detail']);
                }
            }
            $this->display();
        }
    }

    public function unittype_del(){
        //单元类型列表-删除 权限
        if (!in_array(27, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        $id = $_GET['id'] + 0;

        $database_house_village_floor_type = D('House_village_floor_type');
        $where['id'] = $id;
        $where['village_id'] = $_SESSION['house']['village_id'];
        $result = $database_house_village_floor_type->village_floor_type_delete($where);

        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }


    public function pay_order(){
        //物业对账-查看 权限
        if (!in_array(32, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'order_name') {
                $condition['order_name'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'phone') {
                $condition['phone'] = $_GET['keyword'] ;
            }
        }

  //       if(isset($_GET['searchstatus'])){
  //           $condition['is_pay_bill'] = $_GET['searchstatus'];
  //       }
		// if(($_GET['searchstatus']) == 0){
		// 	$condition['pay_type'] = 1;
		// }

        if(($_GET['searchstatus']) == 1){
            $condition['pay_type'] = 0;
        }

        if(($_GET['searchstatus']) == 2){
            $condition['pay_type'] = 1;
        }

        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['pay_time_str']=$time_condition;
        }

        $village_id = $_SESSION['house']['village_id'];
        if($village_id){
            $condition['village_id'] = $village_id;
            $condition['paid'] = 1;
            $result = D('House_village_pay_order')->get_limit_list_page($condition,20);

            $finshtotal = $total = 0;
            if($result){
                foreach ($result['order_list'] as &$v){
                    $total += $v['money'];								//本页的总额
                    $v['is_pay_bill'] && $finshtotal += $v['money'];	//本页已对账的总额
                    //查询线下支付方式
                    if ($v['cashier_id']&&$v['pay_type']) {
                        $condition_table  = array(C('DB_PREFIX').'house_village_pay_cashier_order'=>'o',C('DB_PREFIX').'house_village_pay_type'=>'t');
                        $condition_where = " `o`.`village_id` = `t`.`village_id`  AND `o`.`pay_type` = `t`.`id`  AND `o`.`cashier_id` =".$v['cashier_id'];
                        $condition_field = '`t`.`name`';
                        $pay_type = D('')->field($condition_field)->table($condition_table)->where($condition_where)->find();
                        $v['pay_type_name'] = $pay_type['name'] ? $pay_type['name'] : '';
        
                    }
                }
            }
            $this->assign('finshtotal',$finshtotal);
            $this->assign('total',$total);
            // dump($result);
            $this->assign('order_list',$result);
        }
        $this->assign('village_id',$village_id);
        $this->display();
    }

    public function pay_order_all(){
        //物业对账-查看全部数据 权限
        if (!in_array(33, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        
        if (!empty($_GET['keyword'])) {
            $condition['order_name'] = $_GET['keyword'];
        }

        if($_GET['searchstatus']==1){
            $condition['is_pay_bill'] = array('in',array(1,2)) ;
        }else if($_GET['searchstatus']==2){
            $condition['is_pay_bill'] = 0;
        }

        // if(isset($_GET['searchstatus'])){
        //     $condition['is_pay_bill'] = $_GET['searchstatus'];
        // }

        // if(($_GET['searchstatus']) == 0){
        //     $condition['pay_type'] = 1;
        // }

        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['pay_time_str']=$time_condition;
        }

        $village_id = $_SESSION['house']['village_id'];

        if($village_id){
            $condition['village_id'] = $village_id;
            $condition['paid'] = 1;
            // $result = D('House_village_pay_order')->get_limit_list_page($condition,20);
            
            
            import('@.ORG.merchant_page');

            $count_order = D('House_village_pay_order')->where($condition)->count();
            $p = new Page($count_order,20,'page');


            $order_list = D('House_village_pay_order')->where($condition)->limit($p->firstRow.','.$p->listRows)->select();

            $database_house_village_property_paylist = D('House_village_property_paylist');
            $pay_list = $database_house_village_property_paylist->where(array('village_id'=>$condition['village_id']))->select();

            if(!empty($pay_list)){
                foreach($order_list as $Key=>$order){
                    foreach($pay_list as $pay_info){
                        if($order['order_id'] ==  $pay_info['order_id']){
                            $order_list[$Key]['property_time_str'] = date('Y-m-d',$pay_info['start_time']) . '至' . date('Y-m-d',$pay_info['end_time']);
                        }
                    }
                }
            }


            $total = D('House_village_pay_order')->field(' SUM(`money` ) AS totalMoney ')->where($condition)->find();
            $already = D('House_village_pay_order')->field(' SUM(`money` ) AS readyMoney ')->where($condition." AND `is_pay_bill`=1 ")->find();
            
            $return['pagebar'] = $p->show();
            $return['order_list'] = $order_list;
            $return['totalMoney'] = $total;
            $return['readyMoney'] = $already;

            $result = $return;

            $finshtotal = $total = 0;
            if($result){
                foreach ($result['order_list'] as $v){
                    $total += $v['money'];                              //本页的总额
                    $v['is_pay_bill'] && $finshtotal += $v['money'];    //本页已对账的总额
                }
            }
            $this->assign('finshtotal',$finshtotal);
            $this->assign('total',$total);
            // dump($result);
            $this->assign('order_list',$result);
        }
        $this->assign('village_id',$village_id);
        $this->display();
    }


    public function export(){
        //物业对账-导出 权限
        if (!in_array(34, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '社区账单';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'order_name') {
                $condition['order_name'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'phone') {
                $condition['phone'] = $_GET['keyword'] ;
            }
        }

        if(isset($_GET['searchstatus'])){
            $condition['is_pay_bill'] = $_GET['searchstatus'];
        }

        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['pay_time_str']=$time_condition;
        }

        $village_id = $_SESSION['house']['village_id'];
        if($village_id){
            $now_village = D('House_village')->get_one($village_id);
            $where_condition['village_id'] = $condition['village_id'] = $village_id;
            $where_condition['paid']= $condition['paid']  = 1;
//            $where_condition['is_pay_bill'] = $condition['is_pay_bill']==1?2:0;
            $where_condition['is_pay_bill'] = array('egt',1);
            $count = D('House_village_pay_order')->where($where_condition)->count();
            $length = ceil($count / 1000);
            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);

                $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千订单');
                $objActSheet = $objExcel->getActiveSheet();
                $objActSheet->setCellValue('A1', '缴费项');
                $objActSheet->setCellValue('B1', '已缴金额');
                $objActSheet->setCellValue('C1', '支付时间');
                $objActSheet->setCellValue('D1', '业主名');
                $objActSheet->setCellValue('E1', '联系方式');
                $objActSheet->setCellValue('F1', '住址');
                $objActSheet->setCellValue('G1', '编号');
                $objActSheet->setCellValue('H1', '物业服务周期');
                $objActSheet->setCellValue('I1', '自定义内容/赠送物业服务时间');
                $objActSheet->setCellValue('J1', '服务时间');
                $objActSheet->setCellValue('K1', '自定义缴费周期');
                $objActSheet->setCellValue('L1', '对账状态');

                $condition['is_pay_bill'] = 1;
                $result = D('House_village_pay_order')->get_limit_list_page($condition , ($i+1)*1000 , true);
                if (!empty($result['order_list'])) {
                    $index = 2;
                    foreach ($result['order_list'] as $value) {
                        $objActSheet->setCellValueExplicit('A' . $index, $value['order_name']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['money']);
                        $objActSheet->setCellValueExplicit('C' . $index, date('Y-m-d H:i:s',$value['time']));
                        $objActSheet->setCellValueExplicit('D' . $index, $value['username']);
                        $objActSheet->setCellValueExplicit('E' . $index, $value['phone']);
                        $objActSheet->setCellValueExplicit('F' . $index, $value['address']);
                        $objActSheet->setCellValueExplicit('G' . $index, $value['usernum']);
                        if($value['property_month_num']){
                            $objActSheet->setCellValueExplicit('H' . $index, $value['property_month_num'].'个月');
                        }else{
                            $objActSheet->setCellValueExplicit('H' . $index, '暂无');
                        }

                        if(!empty($value["presented_property_month_num"]) AND ($value["diy_type"] == 0)){
                            $objActSheet->setCellValueExplicit('I' . $index, $value['presented_property_month_num'].'个月');
                        }elseif($value["diy_type"] == 1){
                            $objActSheet->setCellValueExplicit('I' . $index, $value['diy_content']);
                        }else{
                            $objActSheet->setCellValueExplicit('I' . $index, '暂无');
                        }

                        if($value['property_time_str']){
                            $objActSheet->setCellValueExplicit('J' . $index, $value['property_time_str']);
                        }else{
                            $objActSheet->setCellValueExplicit('J' . $index, '暂无');
                        }

                        if($value['order_type'] == 'custom_payment'){
                            $objActSheet->setCellValueExplicit('K' . $index, $value['payment_paid_cycle'].'/周期');
                        }else{
                            $objActSheet->setCellValueExplicit('K' . $index, '暂无');
                        }

                        if($value['is_pay_bill'] == 0){
                            $objActSheet->setCellValueExplicit('L' . $index, '未对账');
                        }else{
                            $objActSheet->setCellValueExplicit('L' . $index, '已对账');
                        }
                        $index++;
                    }
                }
                sleep(2);
            }
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
        header('Content-Disposition:attachment;filename="'.$now_village['village_name'].'_'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function merchant_order(){
        //物业商家流水-查看 权限
        if (!in_array(36, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $type=I('type')?I('type'):'group';
        $village_id = $_SESSION['house']['village_id'];
        $time_condition ='';
        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])&&!empty($_POST['begin_time'])&&!empty($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
            $time_condition = " (o.pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }

        $order_list = D('House_village_group')->get_order_list($type,$village_id,$time_condition);
        $this->assign($order_list);
        $this->display();
    }

    //缴费优惠列表
    public function preferential_list(){
        //缴费优惠列表-查看 权限
        if (!in_array(28, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_property = D('House_village_property');

        $where['village_id'] = $_SESSION['house']['village_id'];
        $list = $database_house_village_property->house_village_proerty_page_list($where,true,'id desc',20);

        if(!$list){
            $this->error('数据处理有误！');
        }else{
            $this->assign('list',$list['list']);
        }

        $this->display();
    }

    public function preferential_add(){
        //缴费优惠列表-添加 权限
        if (!in_array(29, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            $database_house_village_property = D('House_village_property');

            $_POST['village_id'] = $_SESSION['house']['village_id'];
            $result = $database_house_village_property->house_village_property_add($_POST);

            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $this->display();
        }
    }

    public function preferential_del(){
        //缴费优惠列表-删除 权限
        if (!in_array(31, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_property = D('House_village_property');
        $where['id'] = $id;
        $result = $database_house_village_property->house_village_property_del($where);

        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }

    public function preferential_edit(){
        //缴费优惠列表-查看 权限
        if (!in_array(28, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_property = D('House_village_property');
        $where['id'] = $id;

        if(IS_POST){
            //缴费优惠列表-编辑 权限
            if (!in_array(30, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $result = $database_house_village_property->house_village_property_edit($where,$_POST);

            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $detail = $database_house_village_property->house_village_property_detail($where ,true);

            if(!$detail){
                $this->error('数据处理有误！');
            }else{
                $this->assign('detail',$detail['detail']);
            }
            $this->display();
        }
    }


    private function _get_floor_type_list(){
        $database_house_village_floor_type = D('House_village_floor_type');
        $house_village_floor_type_condition['status'] = 1;
        $house_village_floor_type_condition['village_id'] = $_SESSION['house']['village_id'];
        $house_village_floor_type_num = $database_house_village_floor_type->where($house_village_floor_type_condition)->count();

        if($house_village_floor_type_num <= 0){
            $this->error('请先添加单元类型！',U('unittype_add'));
        }

        $house_village_floor_type_list = $database_house_village_floor_type->house_village_floor_type_page_list($house_village_floor_type_condition , true , 'id desc' , 9999);
        $this->assign('house_village_floor_type_list' , $house_village_floor_type_list['list']);
    }

    private function _get_property_list(){
        $database_house_village_property = D('House_village_property');
        $house_village_property_condition['status'] = 1;
        $house_village_property_condition['village_id'] = $_SESSION['house']['village_id'];

        $house_village_property_list = $database_house_village_property->house_village_proerty_page_list($house_village_property_condition , true , 'id desc' , 9999);
        $this->assign('house_village_property_list' , $house_village_property_list['list']);
    }

    //缴费提醒
    public function payment_reminder(){
        //物业对账-查看 权限
        if (!in_array(32, $this->house_session['menus'])) {
            exit(json_encode(array('error'=>0,'msg'=>'您没有权限查看缴费信息')));
        }
        $sum = M('House_village')->where(array('village_id'=>$this->village_id))->getField('payment_reminder');
        if($sum > 0){
            M('House_village')->where(array('village_id'=>$this->village_id))->setField('payment_reminder',0);
            exit(json_encode(array('error'=>1,'msg'=>'您有 ('.$sum.') 条缴费提醒！')));
        }else{
            exit(json_encode(array('error'=>0,'msg'=>'暂无缴费信息')));
        }
    }

    // 上传图片
    public function ajax_upload_pic(){
        if ($_FILES['imgFile']['error'] != 4) {
            $upload_dir = './upload/house/village/'.date('Ymd').'/'.date('H').'/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();
            $upload->maxSize = $this->config['group_pic_size'] * 1024 * 1024;
            $upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
            $upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif','application/octet-stream');
            $upload->savePath = $upload_dir;
            $upload->saveRule = 'uniqid';
            if ($upload->upload()) {
                $uploadList = $upload->getUploadFileInfo();
                $title = $uploadList[0]['savename'];
                $url = $upload_dir.$title;
                exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
            } else {
                exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
            }
        } else {
            exit(json_encode(array('error' => 1, 'message' => '没有选择图片')));
        }
    }

    /**
     * [parking_management 车位管理]
     * @return [type] [description]
     */
    public function parking_management(){
        if(IS_POST){
            //车位管理-绑定用户 权限
            if (!in_array(253, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            $position_bind_user_ids = array_unique(json_decode($_POST['position_bind_user_ids']));//需要绑定的用户ID

            $position_id = trim($_POST['position_id']);//车位ID
            if(!$position_bind_user_ids || !$position_id){
                $this->ajaxReturn(array('code'=>2,'msg'=>'数据获取错误!'));
            }
            if($position_id){
                $where['position_id'] = $position_id;
                $where['village_id'] = $this->village_id;
            }
            $field='user_id';
            $result = D('House_village_bind_position')->get_position_bind_list($position_bind_user_ids,$field,$where,$position_id);
            if($result){
                if ($result['code']==1) { // 发送微信通知
                    $bind_condition['pigcms_id'] = implode(',', $position_bind_user_ids);
                    $bind_condition['village_id'] = $this->village_id;
                    $user_bind_list = D('House_village_user_bind')->get_cashier_unpaid_list($bind_condition,0,0);
                    if($user_bind_list['list']){
                        D('House_village_user_bind')->send_weixin_pay($this->village_id,$user_bind_list['list']);
                    }
                }
                $this->ajaxReturn($result);
            }else{
                $this->ajaxReturn(array('code'=>2,'msg'=>'数据获取错误!'));
            }

        }else{
            //车位管理-查看 权限
            if (!in_array(45, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            if ($_GET['position_num'] && $_GET['position_num'] !== '') {//车位编号
                $where['position_num'] = trim($_GET['position_num']);
            }

            if ($_GET['position_status'] && $_GET['position_status'] !=='') {//车位状态
                $where['position_status'] = trim($_GET['position_status']);
            }

            if ($_GET['garage'] ) {//车库
                $where['garage_id'] = trim($_GET['garage']);
            }
            $where['village_id'] = $this->village_id;
            $info_list = D('House_village_parking_position')->get_parking_list($where);

            // 查询车库
            $garage_list = D('House_village_parking_garage')->get_garage_list('garage_id,garage_num',array('village_id'=>$this->village_id));

            $this->assign('garage_list',$garage_list);
            $this->assign('info_list',$info_list);
        }
        $this->display();
    }

    // 获得车位列表
    public function ajax_get_parking_list(){
        $where['village_id'] = $this->village_id;
        if ($_POST['position_num'] && $_POST['position_num'] !== '') {//车位编号
            $where['position_num'] = trim($_POST['position_num']);
        }
        $info_list = D('House_village_parking_position')->get_parking_list($where,0);
        exit(json_encode($info_list['info_list']));
    }

    /**
     * [parking_detail 车位详情]
     * @return [type] [description]
     */
    public function parking_detail(){
        //绑定用户-查看 权限
        if (!in_array(49, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if ($_GET['position_id'] && $_GET['position_id'] !== '') {//车位id
            $where['position_id'] = trim($_GET['position_id']);
        }
        $where['village_id'] = $this->village_id;
        // $info_list = D('House_village_parking_position')->get_parking_one($where);//停车位信息
        $bind_list = D('House_village_bind_position')->get_position_bind_select($where);//车位住户绑定关系
        $village_id = $this->village_id;
        $field='a.pigcms_id,a.name,a.phone,a.room_addrss,b.floor_name,b.floor_layer';
        $join='join '.C('DB_PREFIX').'house_village_floor as b on a.floor_id=b.floor_id';
        foreach ($bind_list as $key => $value) {
            $where = array('a.pigcms_id'=>$value['user_id'],'a.village_id'=>$village_id);
            $data_list[$key] = D('House_village_user_bind')->get_user_bind_info($field,$join,$where);
            $data_list[$key]['position_id'] = $info_list['position_id'];               
            $data_list[$key]['bind_id'] = $value['bind_id'];               
        }
        // $this->assign('info_list',$info_list);
        $this->assign('data_list',$data_list);
        $this->display();
    }

    /**
     * [parking_edit 车位管理修改页面]
     * @return [type] [description]
     */
    public function parking_edit(){
        if (IS_POST) {
            //车位管理-编辑 权限
            if (!in_array(47, $this->house_session['menus'])) {
                $this->ajaxReturn(array('code'=>2,'msg'=>'对不起，您没有权限执行此操作'));
            }
            $arr['position_note'] = htmlspecialchars(trim($_POST['position_note']));//车主备注
            $arr['position_id'] = htmlspecialchars(intval(trim($_POST['position_id'])));//主键
            $arr['position_area'] = htmlspecialchars(trim($_POST['position_area']));//车位面积
            $arr['position_num'] = htmlspecialchars(trim($_POST['position_num']));//车位号
            // $arr['position_status'] = htmlspecialchars(trim($_POST['position_status']));//车位状态
            // $arr['position_type'] = htmlspecialchars(trim($_POST['position_type']));//车位类型
            $arr['garage_id'] = htmlspecialchars(trim($_POST['garage_id']));//车位类型
            $result = D('House_village_parking_position')->parking_position_save($arr);
            if($result !==false){
                $this->ajaxReturn(array('code'=>1,'msg'=>'修改车位信息成功!'));
            }else{
                $this->ajaxReturn(array('code'=>2,'msg'=>'修改车位信息失败!'));
            }
        }else{
            //车位管理-查看 权限
            if (!in_array(45, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            $position_id = intval(trim($_GET['position_id']));
            if (!$position_id && $position_id='') {
                $this->error('错误的用户信息');
            }
            $where['village_id']=$this->village_id;
            $garage_list = D('House_village_parking_garage')->get_garage_list('',$where);
            $arr['village_id'] = $this->village_id;
            $arr['position_id'] = $position_id;
            $position_list = D('House_village_parking_position')->get_parking_one($arr);
            $this->assign('garage_list',$garage_list);
            $this->assign('position_list',$position_list);

            $payment_list = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb', C('DB_PREFIX').'house_village_payment_standard'=>'ps', C('DB_PREFIX').'house_village_payment'=>'p'))->where("psb.position_id= '".$position_id."' AND p.payment_id = psb.payment_id AND ps.standard_id = psb.standard_id")->select();

            // dump($payment_list);
            $cycle_type = array(
                        'Y'=>'年',
                        'M'=>'月',
                        'D'=>'日',
                    );
            $this->assign('cycle_type',$cycle_type);
            $this->assign('payment_list',$payment_list);
    }
        $this->display();
    }/**
     * [parking_position_addall 批量录入车位信息]
     * @return [type] [description]
     */
    public function parking_position_addall(){
        //车位管理-添加 权限
        if (!in_array(46, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            $data['start_num'] = htmlspecialchars(intval(trim($_POST['start_num'])));//开始编号
            $data['end_num'] = htmlspecialchars(intval(trim($_POST['end_num'])));//结束编号
            $data['garage_id'] = htmlspecialchars(trim($_POST['garage_id']));
            // $data['position_type'] = htmlspecialchars(trim($_POST['position_type']));
            $data['position_status'] = htmlspecialchars(trim($_POST['position_status']));
            $data['position_area'] = htmlspecialchars(trim($_POST['position_area']));
            $data['position_note'] = htmlspecialchars(trim($_POST['position_note']));
            // $data['prefix'] = htmlspecialchars(trim($_POST['prefix'])) ;
           
            if($data['start_num']>=$data['end_num']){
                $this->ajaxReturn(array('code'=>2,'msg'=>'请输入有效编号'));
            }
            $str = '';
            for( $i=$data['start_num']; $i<=$data['end_num']; $i++ ){
                // $str .= $data['prefix'].$i.',';
                $str .= $i.',';
            }
            $num=explode(',',substr($str, 0, -1));
            foreach ($num as $key => $value) {
                // $arr[$key]['position_type']=$data['position_type'];
                $arr[$key]['garage_id']=$data['garage_id'];
                $arr[$key]['position_num']=$value;
                $arr[$key]['position_status']=$data['position_status'];
                $arr[$key]['position_area']=$data['position_area'];
                $arr[$key]['position_note']=$data['position_note'];
                $arr[$key]['village_id'] = $this->village_id;
            }
            $result = D('House_village_parking_position')->parking_position_addAll($arr);
            if($result !== false){
                $this->ajaxReturn(array('code'=>1,'msg'=>'批量录入车位信息系成功!'));
            }else{
                $this->ajaxReturn(array('code'=>2,'msg'=>'批量录入车位信息系失败!'));
            }
        }else{
            $where['village_id'] = $this->village_id;
            $garage_list = D('House_village_parking_garage')->get_garage_list('',$where);
            $this->assign('garage_list',$garage_list);
        }
        $this->display();
    }

    /**
     * [parking_position_add 单个车位信息添加]
     * @return [type] [description]
     */
    public function parking_position_add(){
        //车位管理-添加 权限
        if (!in_array(46, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            // $data['prefix'] = htmlspecialchars(trim($_POST['prefix']));
            $data['garage_id'] = htmlspecialchars(intval(trim($_POST['garage_id'])));
            // $data['position_num'] =  $data['prefix'].htmlspecialchars(trim($_POST['position_num']));
            $data['position_num'] = htmlspecialchars(trim($_POST['position_num']));
            // $data['position_type'] = htmlspecialchars(trim($_POST['position_type']));
            $data['position_area'] = htmlspecialchars(trim($_POST['position_area']));
            // $data['position_status'] = htmlspecialchars(trim($_POST['position_status']));
            $data['position_note'] = htmlspecialchars(trim($_POST['position_note']));
            $data['village_id'] = $this->village_id;
            $result = D('House_village_parking_position')->parking_position_add($data);
            if($result !== false){
                $this->ajaxReturn(array('code'=>1,'msg'=>'增加车位成功!'));
            }else{
                $this->ajaxReturn(array('code'=>2,'msg'=>'增加车位失败!'));
            }
        }else{
            $where['village_id'] = $this->village_id;
            $garage_list = D('House_village_parking_garage')->get_garage_list('',$where);
            $this->assign('garage_list',$garage_list);
        }
        $this->display();
    }


    /**
     * [vehicle_import_add 车位信息导入]
     * @return [type] [description]
     */
    public function parking_import_add(){
        //车位管理-添加 权限
        if (!in_array(46, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            if ($_FILES['file']['error'] != 4) {
                set_time_limit(0);
                $upload_dir = './upload/excel/villageposition/' . date('Ymd') . '/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();
                $upload->maxSize = 10 * 1024 * 1024;
                $upload->allowExts = array('xls', 'xlsx');
                $upload->allowTypes = array(); // 允许上传的文件类型 留空不做检查
                $upload->savePath = $upload_dir;
                $upload->thumb = false;
                $upload->thumbType = 0;
                $upload->imageClassPath = '';
                $upload->thumbPrefix = '';
                $upload->saveRule = 'uniqid';
                if ($upload->upload()) {
                    $uploadList = $upload->getUploadFileInfo();
                    require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel/IOFactory.php';
                    $path = $uploadList['0']['savepath'] . $uploadList['0']['savename'];
                    $fileType = PHPExcel_IOFactory::identify($path); //文件名自动判断文件类型
                    $objReader = PHPExcel_IOFactory::createReader($fileType);
                    $excelObj = $objReader->load($path);
                    $result = $excelObj->getActiveSheet()->toArray(null, true, true, true);

                    $error_arr = array();
                    if (!empty($result) && is_array($result)) {
                        unset($result[1]);
                        $last_user_id = 0;
                        $err_msg = '';
                        $car_model = D('House_village_parking_position');

                        foreach ($result as $kk => $vv) {
                            if (array_sum($vv) == 0) {
                                continue;
                            }
                            if ($vv['A'] === null && $vv['B'] === null && $vv['C'] === null && $vv['D'] === null) continue;

                            if (empty($vv['A'])) {
                                $vv['E'] = '车库名为空！';
                                $error_arr[] = $vv;
                                // $err_msg .= '车库名为空！'.PHP_EOL;
                                continue;
                            }
                            if (empty($vv['B'])) {
                                $vv['E'] = '车位号为空！';
                                $error_arr[] = $vv;
                                // $err_msg .= '车位号为空！'.PHP_EOL;
                                continue;
                            }
                            if (empty($vv['C'])) {
                                $vv['E'] = '车位面积为空！';
                                $error_arr[] = $vv;
                                // $err_msg .= '车位面积为空！'.PHP_EOL;
                                continue;
                            }
                        

                            $where['village_id'] = $this->village_id;
                            $where['garage_num'] = $vv['A'];
                            $garaga_info = D('House_village_parking_garage')->get_one($where);
                            if (!$garaga_info) {
                                $vv['E'] = '车库不存在！';
                                $error_arr[] = $vv;
                                // $err_msg .= '车库不存在！'.PHP_EOL;
                                continue;
                            }

                            $tmpdata = array();
                            $tmpdata['garage_id'] = $garaga_info['garage_id'];
                            $tmpdata['position_num'] =htmlspecialchars(trim($vv['B']), ENT_QUOTES);
                            $tmpdata['position_area'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                            $tmpdata['position_note'] = htmlspecialchars(trim($vv['D']), ENT_QUOTES);
                            $tmpdata['village_id'] = $this->village_id;
                            $res = D('House_village_parking_position')->parking_position_add($tmpdata);
                           
                        }
                        if (empty($error_arr)) {
                            $this->success('导入成功');
                            exit;
                        } else {
                            $num = count($error_arr);
                            // echo "<script language='JavaScript' type='text/javascript'>alert('失败{$num}条，点击下载！')</script>";
                            //导出失败信息
                            require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
                            error_reporting(E_ALL);  
                            date_default_timezone_set('Europe/London');  
                            $objExcel = new PHPExcel();  
                      
                            $title = $this->village['village_name'] . '社区-车位导入失败列表';
                            /*以下是一些设置 ，什么作者  标题之类的*/  
                             $objExcel->getProperties()->setCreator($title)  
                               ->setLastModifiedBy($title)  
                               ->setTitle($title)  
                               ->setSubject("失败信息导出")  
                               ->setDescription("备份数据")  
                               ->setKeywords("excel")  
                               ->setCategory("result file");  
                            
                            $i = 0;
                            $objExcel->createSheet();
                            $objExcel->setActiveSheetIndex($i);
                            $objExcel->getActiveSheet()->setTitle($title);
                            $objActSheet = $objExcel->getActiveSheet();
                            $objActSheet->setCellValue('A1', '车库名');
                            $objActSheet->setCellValue('B1', '车位号');
                            $objActSheet->setCellValue('C1', '车位面积');
                            $objActSheet->setCellValue('D1', '备注');
                            $objActSheet->setCellValue('E1', '失败信息');
                            $index = 2;
                            foreach ($error_arr as  $value) {  
                                $objActSheet->setCellValueExplicit('A' . $index, $value['A']);
                                $objActSheet->setCellValueExplicit('B' . $index, $value['B']);
                                $objActSheet->setCellValueExplicit('C' . $index, $value['C']);
                                $objActSheet->setCellValueExplicit('D' . $index, $value['D']);
                                $objActSheet->setCellValueExplicit('E' . $index, $value['E']);
                                $index++;
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
                            header('Content-Disposition:attachment;filename="' . $title . '_' . date("Y-m-d h:i:s", time()) . '.xls"');
                            header("Content-Transfer-Encoding:binary");
                            $objWriter->save('php://output');
                            exit();
                        }

                        // if (!empty($res)) {
                        //     $this->success('导入成功');
                        //     exit;
                        // } else {
                        //     $this->error('导入失败！原因：' . $err_msg);
                        //     exit;
                        // }
                    }
                } else {
                    $this->error($upload->getErrorMsg());
                    exit;
                }
            }
            $this->error('文件上传失败');
            exit;
        }else{
            $this->display();
        }
    }


    /**
     * [bind_position_del 解除车位绑定]
     * @return [type] [description]
     */
    public function unbind_position(){
        $bind_id = trim($_POST['bind_id']);
        //绑定用户-解绑 权限
        if (!in_array(254, $this->house_session['menus'])) {
            $this->ajaxReturn(array('code'=>2,'msg'=>'对不起，您没有权限执行此操作'));
        }

        if(!$bind_id || $bind_id ==''){
            $this->ajaxReturn(array('code'=>2,'msg'=>'参数传递错误!'));
        }else{
            $where['bind_id'] = $bind_id;
        }

        $result = D('House_village_bind_position')->del_bind_position($where);//解除

        if($result !== false){
            $this->ajaxReturn(array('code'=>1,'msg'=>'解绑成功!'));
        }else{
            $this->ajaxReturn(array('code'=>2,'msg'=>'解绑失败!'));
        }
    }

    /**
     * [parking_del 车位信息删除]
     * @return [type] [description]
     */
    public function parking_del(){
        //车位管理-删除 权限
        if (!in_array(48, $this->house_session['menus'])) {
            $this->ajaxReturn(array('code'=>2,'msg'=>'对不起，您没有权限执行此操作'));
        }

        if(!$id && $id=''){
            $this->ajaxReturn(array('code'=>2,'msg'=>'获取删除对象失败!'));
        }

        $id = htmlspecialchars(trim($_POST['position_id']));
        $id_arr = explode(',', $id);
        if ($id_arr) {
            foreach ($id_arr as $key => $value) {
                $res = D('House_village_parking_car')->get_parking_car_one(array('car_position_id'=>$value));//判断该车位是否存在绑定车辆
                if($res){
                    $this->ajaxReturn(array('code'=>2,'msg'=>'请先删除已绑定该车位的车辆!'));
                }

                $ress = D('House_village_bind_position')->get_bind_position_one(array('position_id'=>$value));
                if($ress){
                    $this->ajaxReturn(array('code'=>2,'msg'=>'请先解绑该车位的绑定用户!'));
                }
            }
        }

        $where['position_id'] = array('in',$_POST['position_id']);
        $result = D('House_village_parking_position')->del_prking_position($where);
        if($result !== false){
            $this->ajaxReturn(array('code'=>1,'msg'=>'删除成功!'));
        }else{
            $this->ajaxReturn(array('code'=>2,'msg'=>'删除失败!'));
        }
    }



    /**
     * 车位绑定消费标准
    */

    //添加缴费项
    public function payment_add(){
        $payment_list = D('House_village_payment')->where(array('village_id'=>$this->village_id))->select();
        $this->assign('payment_list',$payment_list);
        $this->display();
    }

    // 车位签订缴费项合同
    public function position_payment_add(){
        // 车位-添加缴费项 权限
        if (!in_array(261, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $payment_info = D('House_village_payment')->where(array('village_id'=>$this->village_id,'payment_id'=>$_POST['payment_id']))->find();
        $standard_info = D('House_village_payment_standard')->where(array('payment_id'=>$_POST['payment_id'],'standard_id'=>$_POST['standard_id']))->find();
        
        $cycle_type = array('Y'=>'year', 'M'=>'month', 'D'=>'day', );
        $data['payment_id'] = $_POST['payment_id'];
        $data['standard_id'] = $_POST['standard_id'];
        
        $data['village_id'] = $this->village_id;
        if($_POST['start_time'] == ''){
            $_POST['start_time'] = date('Y-m-d',time());
        }

        if($_POST['cycle_sum'] == ''){
            $_POST['cycle_sum'] = $standard_info['max_cycle'];
        }
        $data['start_time'] = strtotime($_POST['start_time']);
        $data['end_time'] = strtotime($_POST['start_time'].'+'.$_POST['cycle_sum']*$standard_info['pay_cycle'].$cycle_type[$standard_info['cycle_type']]);
        $data['cycle_sum'] = $_POST['cycle_sum'];
        $data['pay_cycle'] = 0;
        $data['remarks'] = $_POST['remarks'];
        $data['bind_type'] = 2; //绑定类型


        if(empty($_POST['uid'])){
            $data['position_id'] = $_POST['position_id'];
            if($_POST['metering_mode_type'] == 3){ //车位面积
                $position_info = D('House_village_parking_position')->where(array('position_id'=>$_POST['position_id']))->get_one();
                $data['metering_mode_val'] = $position_info['position_area'] ? $position_info['position_area'] : 0;
            }else{
                $data['metering_mode_val'] = $_POST['metering_mode_val'];
            }
            if($standard_info['pay_type'] == 1){
                $data['metering_mode_val'] = '';
            }
            $res = D('House_village_payment_standard_bind')->data($data)->add(); 

            // 发送微信通知
            if ($data['start_time']<time()) {
                $bind_user = D('house_village_bind_position')->where(array('position_id'=>$data['position_id'],'village_id'=>$this->village_id));
            }
        }else{
            // 批量添加
            $position_ids = explode(',',trim($_POST['uid'],',')); 
            foreach ($position_ids as $key => $value) {
                if($value > 0){
                    $data['position_id'] = $value;
                    if($_POST['metering_mode_type'] == 3){ //车位面积
                        $position_info = D('House_village_parking_position')->where(array('position_id'=>$value))->get_one();
                        $data['metering_mode_val'] = $position_info['position_area'] ? $position_info['position_area'] : 0;
                    }else{
                        $data['metering_mode_val'] = $_POST['metering_mode_val'];
                    }
                    if($standard_info['pay_type'] == 1){
                        $data['metering_mode_val'] = '';
                    }
                    $res = D('House_village_payment_standard_bind')->data($data)->add();
                }
            }  

            // 发送微信通知
            if ($data['start_time']<time()) {
                $bind_user = D('house_village_bind_position')->where(array('position_id'=>array('in',$position_ids),'village_id'=>$this->village_id));
            }
        }
        if ($bind_user) {
            // 发送微信通知
            $pigcms_ids = array();
            foreach ($bind_user as $key => $value) {
                $pigcms_ids[] = $value['user_id'];
            }

            $database_house_village_user_bind = D('House_village_user_bind');
            $pigcms_ids = array_unique($pigcms_ids);
            $bind_condition['pigcms_id'] = implode(',', $pigcms_ids);
            $bind_condition['village_id'] = $this->village_id;
            $user_bind_list = $database_house_village_user_bind->get_cashier_unpaid_list($bind_condition,0,0);

            if($user_bind_list['list']){
                $database_house_village_user_bind->send_weixin_pay($this->village_id,$user_bind_list['list']);
            }
        }
        if($res){
            $this->success('保存成功！');
        }else{
            $this->error('保存失败！');
        }
    }

    /**
     * [garage_management 车库管理]
     * @return [type] [description]
     */
    public function garage_management(){
        //车库管理-查看 权限
        if (!in_array(51, $this->house_session['menus'])) {
            $this->ajaxReturn('对不起，您没有权限执行此操作');
        }

        $where['village_id'] = $this->village_id;
        $field='garage_id,garage_position,garage_num,garage_remark';
        $info_list = D('House_village_parking_garage')->get_garage_select($field,$where);
        if($info_list){
            $this->assign('info_list',$info_list);
        }else{
            $this->error('信息错误!');
        }
        $this->display();
    }

     
    /**
     * [garage_del 车库删除]
     * @return [type] [description]
     */
    public function garage_del(){
        //车库管理-删除 权限
        if (!in_array(54, $this->house_session['menus'])) {
            $this->ajaxReturn(array('code'=>2,'msg'=>'对不起，您没有权限执行此操作'));
        }
        $id = trim($_POST['garage_id']);
        
        if(!$id && $id=''){
            $this->ajaxReturn(array('code'=>2,'msg'=>'获取删除对象失败'));
        }

        $where['garage_id'] = array('in',$id);
        $where['village_id'] = $this->village_id;

        //先查看是否绑定车位
        $position_list = D('House_village_parking_position')->get_parking_select($where);
        if ($position_list) {
            $this->ajaxReturn(array('code'=>1,'msg'=>'删除车库时请先删除该车库下所有车位!'));
        }
        
        $result = D('House_village_parking_garage')->del_prking_garage($where);
        if($result !== false){
            $this->ajaxReturn(array('code'=>1,'msg'=>'删除车库信息成功!'));
        }else{
            $this->ajaxReturn(array('code'=>2,'msg'=>'删除车库信息成功!'));
        }
    }
    /**
     * [garage_add 车库添加]
     * @return [type] [description]
     */
    public function garage_add(){
        //车库管理-添加 权限
        if (!in_array(52, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            $data['garage_num'] = htmlspecialchars(trim($_POST['garage_num']));
            $data['garage_position'] = htmlspecialchars(trim($_POST['garage_position']));
            $data['garage_remark'] = htmlspecialchars(trim($_POST['garage_remark']));
            $data['garage_addtime'] = time();
            $data['village_id'] = $this->village_id;
            $result = D('House_village_parking_garage')->add_parking_garage($data);
            if($result !== false){
                $this->ajaxReturn(array('code'=>1,'msg'=>'添加车库信息成功!'));
            }else{
                $this->ajaxReturn(array('code'=>2,'msg'=>'添加车库信息失败!'));
            }
        }else{
            
        }
        $this->display();
    }

  
     /* 导出excel函数*/  
    public function garage_push($name='Excel'){  
        //车位管理-导出 权限
        if (!in_array(50, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        error_reporting(E_ALL);  
        date_default_timezone_set('Europe/London');  
        $objExcel = new PHPExcel();  
  
        $title = $this->village['village_name'] . '社区-车位列表';
        /*以下是一些设置 ，什么作者  标题之类的*/  
         $objExcel->getProperties()->setCreator($title)  
           ->setLastModifiedBy($title)  
           ->setTitle($title)  
           ->setSubject("数据EXCEL导出")  
           ->setDescription("备份数据")  
           ->setKeywords("excel")  
           ->setCategory("result file");  
        
         /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/ 
        if ($_GET['position_num'] && $_GET['position_num'] !== '') {//车位编号
            $where['position_num'] = trim($_GET['position_num']);
        }

        if ($_GET['position_status'] && $_GET['position_status'] !=='') {//车位状态
            $where['position_status'] = trim($_GET['position_status']);
        }  

        if ($_GET['garage'] ) {//车库
            $where['garage_id'] = trim($_GET['garage']);
        }
        $where['village_id']=$this->village_id;
        $count = D('House_village_parking_position')->parking_position_count($where);
        $data = D('House_village_parking_position')->get_parking_list($where,9999999);
        $length = ceil($count / 1000);
        
        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);
            $objExcel->getActiveSheet()->setTitle('车位管理');
            $objActSheet = $objExcel->getActiveSheet();
            $objActSheet->setCellValue('A1', '车位编号');
            $objActSheet->setCellValue('B1', '所属小区');
            $objActSheet->setCellValue('C1', '车库名称');
            $objActSheet->setCellValue('D1', '车位号');
            $objActSheet->setCellValue('E1', '车位状态');
            $objActSheet->setCellValue('F1', '车位面积(㎡)');
            $objActSheet->setCellValue('G1', '备注');
            $field='village_name';
            $village_id = $this->village_id;
            $village_name = D('House_village')->get_village_info($village_id,$field);    
            if (!empty($data)) {
                $index = 2;
                for ($k = $i*1000; $k < ($i+1)*1000; $k++) { 
                    if (!$data['info_list'][$k]['position_id']) {
                       break;
                    }
                    $objActSheet->setCellValueExplicit('A' . $index, $data['info_list'][$k]['position_id']);
                    $objActSheet->setCellValueExplicit('B' . $index, $village_name['village_name']);
                    $objActSheet->setCellValueExplicit('C' . $index, $data['info_list'][$k]['garage_num']);
                    $objActSheet->setCellValueExplicit('D' . $index, $data['info_list'][$k]['position_num']);
                    if($data['info_list'][$k]['position_status'] == 1){
                        $objActSheet->setCellValueExplicit('E' . $index, '未使用');
                    }else{
                        $objActSheet->setCellValueExplicit('E' . $index, '已使用');
                    }
                    $objActSheet->setCellValueExplicit('F' . $index, $data['info_list'][$k]['position_area']);
                    $objActSheet->setCellValueExplicit('G' . $index, $data['info_list'][$k]['position_note']);
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
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();                      
    }  


    /**
     * [garage_edit 车库信息修改]
     * @return [type] [description]
     */
    public function garage_edit(){
        if (IS_POST) {
            //车库管理-编辑 权限
            if (!in_array(53, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $data['garage_num'] = htmlspecialchars(trim($_POST['garage_num']));
            $data['garage_position'] = htmlspecialchars(trim($_POST['garage_position']));
            $data['garage_remark'] = htmlspecialchars(trim($_POST['garage_remark']));
            $data['garage_id'] = htmlspecialchars(trim($_POST['garage_id']));
            $result = D('House_village_parking_garage')->save_parking_garage($data);
            if($result !== false){
                $this->ajaxReturn(array('code'=>1,'msg'=>'修改车库信息成功!'));
            }else{
                $this->ajaxReturn(array('code'=>2,'msg'=>'修改车库信息失败!'));
            }
        }else{
            $garage_id = trim($_GET['garage_id']);

            //车库管理-查看 权限
            if (!in_array(51, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            if (!$garage_id && $garage_id='') {
                $this->error('错误的车库信息');
            }

            $field='garage_id,garage_num,garage_position,garage_remark';
            $where['garage_id']=$garage_id;
            $info_list = D('House_village_parking_garage')->get_garage_one($field,$where);
            $this->assign('info_list',$info_list);
        }
        $this->display();
    }

    
     

    /**
     * [vehicle_management 车辆管理]
     * @return [type] [description]
     */
    public function vehicle_management(){
        if(IS_POST){
            //车辆管理-绑定 权限
            if (!in_array(255, $this->house_session['menus'])) {
                $this->ajaxReturn(array('code'=>3,'msg'=>'对不起，您没有权限执行此操作'));
            }

            $position_bind_user_ids = array_unique(json_decode($_POST['position_bind_user_ids']));//需要绑定的用户ID
            $car_id = htmlspecialchars(trim($_POST['car_id']));//车位ID
            if(!$position_bind_user_ids || !$car_id){
                $this->ajaxReturn(array('code'=>2,'msg'=>'数据获取错误!'));
            }
            $field='user_id';
            $where['car_id'] = $car_id;
            $data = D('House_village_bind_car')->get_bind_car_select($field,$where);//已绑定的用户ID
            if(!$data || $data ==''){//数据库不存在数据时
                foreach ($position_bind_user_ids as $key => $value) {
                    $arr[$key]['user_id'] = $value;
                    $arr[$key]['car_id'] = $car_id;
                    $arr[$key]['village_id'] = $this->village_id;
                }
            }else{
                foreach ($position_bind_user_ids as $key => $value) {
                    foreach ($data as $k => $v) {

                        if($value == $v['user_id']){
                            $msg = D('House_village_user_bind')->field('phone,name')->where(array('pigcms_id'=>$value))->find();
                            $this->ajaxReturn(array('code'=>2,'msg'=>'用户'.$msg['name'].'--'.$msg['phone'].'已绑定!'));exit;
                        }else{
                            $arr[$key]['user_id'] = $value;
                            $arr[$key]['car_id'] = $car_id;
                            $arr[$key]['village_id'] = $this->village_id;
                        }
                    }
                }
            }
                $result = D('House_village_bind_car')->bind_car_addAll($arr);

                if($result !== false){
                    $this->ajaxReturn(array('code'=>1,'msg'=>'绑定住户成功!'));
                }else{
                    $this->ajaxReturn(array('code'=>2,'msg'=>'绑定住户失败!'));
                }
        }else{
            //车辆管理-查看 权限
            if (!in_array(55, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            import('@.ORG.merchant_page');
            $where=array();
            if ($_GET['search_type'] && $_GET['search_value']) {
                switch ($_GET['search_type']) {
                    case '1'://车牌号
                        $car_number_1 = preg_replace("/[\x{4e00}-\x{9fa5}]/iu","",trim($_GET['search_value'])); //这样是去掉汉字
                        $where['a.car_number'] = array('like','%'.$car_number_1.'%');
                        break;
                    case '2': //停车卡号
                        $where['a.car_stop_num'] =array('like','%'.$_GET['search_value'].'%');
                        break;
                    case '3'://车位号
                        $where['b.position_num'] = array('like','%'.$_GET['search_value'].'%');
                        break;
                    case '4'://车主姓名
                        $where['a.car_user_name'] = array('like','%'.$_GET['search_value'].'%');
                        break;
                    case '5'://车主手机号
                        $where['a.car_user_phone'] = array('like','%'.$_GET['search_value'].'%');
                        break;
                }
            }
          
            $where['a.village_id'] = $this->village_id;
            $join=' left join pigcms_house_village_parking_position as b on a.car_position_id=b.position_id';
            $field='a.*,b.position_num';
            $order='a.car_addtime desc';
            $info_list = D('House_village_parking_car')->get_parking_car_list($field,$where,$join,$order);
            $this->assign('info_list',$info_list);
        }
        $this->display();
    }


    /**
     * [vehicle_import_add 车辆管理信息导入]
     * @return [type] [description]
     */
    public function vehicle_import_add(){
        //车辆管理-添加 权限
        if (!in_array(56, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            if ($_FILES['file']['error'] != 4) {
                set_time_limit(0);
                $upload_dir = './upload/excel/villagevehicle/' . date('Ymd') . '/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();
                $upload->maxSize = 10 * 1024 * 1024;
                $upload->allowExts = array('xls', 'xlsx');
                $upload->allowTypes = array(); // 允许上传的文件类型 留空不做检查
                $upload->savePath = $upload_dir;
                $upload->thumb = false;
                $upload->thumbType = 0;
                $upload->imageClassPath = '';
                $upload->thumbPrefix = '';
                $upload->saveRule = 'uniqid';
                if ($upload->upload()) {
                    $uploadList = $upload->getUploadFileInfo();
                    require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel/IOFactory.php';
                    $path = $uploadList['0']['savepath'] . $uploadList['0']['savename'];
                    $fileType = PHPExcel_IOFactory::identify($path); //文件名自动判断文件类型
                    $objReader = PHPExcel_IOFactory::createReader($fileType);
                    $excelObj = $objReader->load($path);
                    $result = $excelObj->getActiveSheet()->toArray(null, true, true, true);
                        // fdump($result,'house_import_error_'.$this->village_id,true);
                    $error_arr = array();
                    if (!empty($result) && is_array($result)) {
                        unset($result[1]);
                        $last_user_id = 0;
                        $err_msg = '';
                        $car_model = D('House_village_parking_car');

                        foreach ($result as $kk => $vv) {
                            if (array_sum($vv) == 0) {
                                continue;
                            }
                            if ($vv['A'] === null && $vv['B'] === null && $vv['C'] === null && $vv['D'] === null && $vv['E'] === null && $vv['F'] === null) continue;

                            if (empty($vv['B'])) {
                                $vv['H'] = '请填写车牌号码！';
                                $error_arr[] = $vv;
                                // $err_msg .= '请填写车牌号码！'.PHP_EOL;
                                continue;
                            }

                            if (empty($vv['E'])) {
                                $vv['H'] = '请填写车主姓名！';
                                $error_arr[] = $vv;
                                // $err_msg .= '请填写车主姓名！'.PHP_EOL;
                                continue;
                            }

                            if (empty($vv['F'])) {
                                $vv['H'] = '请填写车主手机号！';
                                $error_arr[] = $vv;
                                // $err_msg .= '请填写车主手机号！'.PHP_EOL;
                                continue;
                            }   

                            if(!preg_match('/^[0-9]{11}$/',$vv['F'])){
                                $vv['H'] = '请填写正确的手机号！';
                                $error_arr[] = $vv;
                                continue;
                            }

                            $where['position_id'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                            $where['village_id'] = $this->village_id;
                            $position_model = D('House_village_parking_position');
                            $position_info = $car_model->get_parking_car_one($where);
                            if (!$position_info) {
                                // $err_msg .= '车位编号不存在，请查看社区中心，车位管理-单元列表！'.PHP_EOL;
                                $vv['H'] = '车位编号不存在，请查看社区中心，车位管理-单元列表！';
                                $error_arr[] = $vv;
                                continue;
                            }

                            $tmpdata = array();
                            $tmpdata['province'] = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
                            $tmpdata['car_number'] = strtoupper(htmlspecialchars(trim($vv['B']), ENT_QUOTES));
                            $tmpdata['car_stop_num'] = htmlspecialchars(trim($vv['D']), ENT_QUOTES);
                            //检测用户是否已存在
                        if ($car_model->field('`car_number`')->where(array('car_number' => $tmpdata['car_number'],'province' => $tmpdata['province'],'village_id'=> $this->village_id))->find()) {
                                $vv['H'] = '车牌号已存在。';
                                $error_arr[] = $vv;
                                // $err_msg .= '车牌号已存在。'.PHP_EOL;
                                continue;
                            }
                            if ($tmpdata['car_stop_num']) {
                                if ($car_model->field('`car_stop_num`')->where(array('car_stop_num' => $tmpdata['car_stop_num'],'village_id'=> $this->village_id))->find()) {
                                    
                                $vv['H'] = '停车卡号已存在。';
                                $error_arr[] = $vv;
                                // $err_msg .= '停车卡号已存在。'.PHP_EOL;
                                    continue;
                                }
                            }

                            $tmpdata['car_position_id'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                            $tmpdata['car_user_name'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
                            $tmpdata['car_user_phone'] = htmlspecialchars(trim($vv['F']), ENT_QUOTES);
                            $tmpdata['car_displacement'] = htmlspecialchars(trim($vv['G']), ENT_QUOTES);
                            $tmpdata['village_id'] = $this->village_id;
                            $tmpdata['car_addtime'] = time();
                            $res = $car_model->parking_car_add($tmpdata);
                            // fdump($car_model->getLastSql(),'house_import_error_'.$this->village_id,true);
                            if (!$res) {
                                $vv['Q'] = '车牌号码为' . $vv['A'] .$vv['B']. ' 导入失败！';
                                $error_arr[] = $vv;
                                // $err_msg .= '车牌号码为' . $vv['A'] .$vv['B']. ' 导入失败！'.PHP_EOL;
                            }
                        }if (empty($error_arr)) {
                            $this->success('导入成功');
                            exit;
                        } else {
                            $num = count($error_arr);
                            // echo "<script language='JavaScript' type='text/javascript'>alert('失败{$num}条，点击下载！')</script>";
                            //导出失败信息
                            require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
                            error_reporting(E_ALL);  
                            date_default_timezone_set('Europe/London');  
                            $objExcel = new PHPExcel();  
                      
                            $title = $this->village['village_name'] . '社区-车辆导入失败列表';
                            /*以下是一些设置 ，什么作者  标题之类的*/  
                             $objExcel->getProperties()->setCreator($title)  
                               ->setLastModifiedBy($title)  
                               ->setTitle($title)  
                               ->setSubject("失败信息导出")  
                               ->setDescription("备份数据")  
                               ->setKeywords("excel")  
                               ->setCategory("result file");  
                            
                            $i = 0;
                            $objExcel->createSheet();
                            $objExcel->setActiveSheetIndex($i);
                            $objExcel->getActiveSheet()->setTitle($title);
                            $objActSheet = $objExcel->getActiveSheet();
                            $objActSheet->setCellValue('A1', '省份');
                            $objActSheet->setCellValue('B1', '车牌号码');
                            $objActSheet->setCellValue('C1', '车位编号');
                            $objActSheet->setCellValue('D1', '停车卡号');
                            $objActSheet->setCellValue('E1', '车主姓名');
                            $objActSheet->setCellValue('F1', '车主手机号');
                            $objActSheet->setCellValue('G1', '车辆排量');
                            $objActSheet->setCellValue('H1', '失败信息');
                            $index = 2;
                            foreach ($error_arr as  $value) {  
                                $objActSheet->setCellValueExplicit('A' . $index, $value['A']);
                                $objActSheet->setCellValueExplicit('B' . $index, $value['B']);
                                $objActSheet->setCellValueExplicit('C' . $index, $value['C']);
                                $objActSheet->setCellValueExplicit('D' . $index, $value['D']);
                                $objActSheet->setCellValueExplicit('E' . $index, $value['E']);
                                $objActSheet->setCellValueExplicit('F' . $index, $value['F']);
                                $objActSheet->setCellValueExplicit('G' . $index, $value['G']);
                                $objActSheet->setCellValueExplicit('H' . $index, $value['H']);
                                $index++;
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
                            header('Content-Disposition:attachment;filename="'.$title . '_' . date("Y-m-d h:i:s", time()) . '.xls"');
                            header("Content-Transfer-Encoding:binary");
                            $objWriter->save('php://output');
                            exit();
                        }
                    
                        // fdump($err_msg,'house_import_error_'.$this->village_id,true);
                        // if (!empty($res)) {
                        //     $this->success('导入成功');
                        //     exit;
                        // } else {
                        //     $this->error('导入失败！原因：' . $err_msg);
                        //     exit;
                        // }
                    }
                } else {
                    $this->error($upload->getErrorMsg());
                    exit;
                }
            }
            $this->error('文件上传失败');
            exit;
        }else{
            $this->display();
        }
    }



    /**
     * [vehicle_add 车辆添加]
     * @return [type] [description]
     */
    public function vehicle_add(){
        //车辆管理-添加 权限
        if (!in_array(56, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            $data['car_number'] = strtoupper(htmlspecialchars(trim($_POST['car_number'])));
            $data['car_position_id'] = htmlspecialchars(trim($_POST['car_position_id']));
            $data['car_stop_num'] = htmlspecialchars(trim($_POST['car_stop_num']));
            $data['car_user_name'] = htmlspecialchars(trim($_POST['car_user_name']));
            $data['car_user_phone'] = htmlspecialchars(trim($_POST['car_user_phone']));
            $data['car_displacement'] = htmlspecialchars(trim($_POST['car_displacement']));
            $data['province'] = htmlspecialchars(trim($_POST['province']));
            $data['car_addtime'] = time();
            $data['village_id'] = $this->village_id;

            if(!$data['car_number'] && $data['car_number'] == ''){
                $this->ajaxReturn(array('code'=>'2','msg'=>'车牌号不能为空'));
            }

            if (empty($data['car_user_name'])) {
                $this->ajaxReturn(array('code'=>'2','msg'=>'请输入车主姓名！'));
            }

            if (empty($data['car_user_phone'])) {
                $this->ajaxReturn(array('code'=>'2','msg'=>'请输入车主手机号！'));
            }

            if(!preg_match('/^[0-9]{11}$/',$data['car_user_phone'])){
                $this->ajaxReturn(array('code'=>'2','msg'=>'请输入正确的手机号！'));
            }

            $res = D('House_village_parking_car')->get_parking_car_one(array('car_number'=>$data['car_number'],'province'=>$data['province'],'village_id'=> $this->village_id));
            if($res){
                $this->ajaxReturn(array('code'=>'2','msg'=>'车牌号已存在'));
            }

            if($data['car_stop_num']){
                $res = D('House_village_parking_car')->get_parking_car_one(array('car_stop_num'=>$data['car_stop_num'],'village_id'=> $this->village_id));
                if ($res) {
                    $this->ajaxReturn(array('code'=>'2','msg'=>'停车卡号已存在'));
                 }
            }

            $result = D('House_village_parking_car')->parking_car_add($data);
            if($result !== false){
                $this->ajaxReturn(array('code'=>'1','msg'=>'添加车辆信息成功!'));
            }else{
                $this->ajaxReturn(array('code'=>'2','msg'=>'添加车辆信息失败'));
            }
        }else{
            $where['village_id'] = $this->village_id;
            $info_list = D('House_village_parking_position')->get_parking_select($where);
            $this->assign('info_list',$info_list); 
        }
        $city_arr = array('京','津','冀','晋','蒙','辽','吉','黑','沪','苏','浙','皖','闽','赣','鲁','豫','鄂','湘','粤','桂','琼','渝','川','贵','云','藏','陕','甘','青','宁','新');
        $this->assign('city_arr',$city_arr);
        $this->display();
    }

    /**
     * [vehice_edit 车辆信息修改]
     * @return [type] [description]
     */
    public function vehicle_edit(){
        if(IS_POST){
            //车辆管理-编辑 权限
            if (!in_array(57, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $data['car_number'] = strtoupper(htmlspecialchars(trim($_POST['car_number'])));
            $data['car_position_id'] = htmlspecialchars(trim($_POST['car_position_id']));
            $data['car_stop_num'] = htmlspecialchars(trim($_POST['car_stop_num']));
            $data['car_user_name'] = htmlspecialchars(trim($_POST['car_user_name']));
            $data['car_user_phone'] = htmlspecialchars(trim($_POST['car_user_phone']));
            $data['car_displacement'] = htmlspecialchars(trim($_POST['car_displacement']));
            $data['car_id'] = intval($_POST['car_id']);
            $data['province'] = htmlspecialchars(trim($_POST['province']));
            $data['village_id'] = $this->village_id;

            if(!$data['car_number'] && $data['car_number'] == ''){
                $this->ajaxReturn(array('code'=>'2','msg'=>'车牌号不能为空'));
            } 

            if (empty($data['car_user_name'])) {
                $this->ajaxReturn(array('code'=>'2','msg'=>'请输入车主姓名！'));
            }

            if (empty($data['car_user_phone'])) {
                $this->ajaxReturn(array('code'=>'2','msg'=>'请输入车主手机号！'));
            }

            if(!preg_match('/^[0-9]{11}$/',$data['car_user_phone'])){
                $this->ajaxReturn(array('code'=>'2','msg'=>'请输入正确的手机号！'));
            }

            $res = D('House_village_parking_car')->get_parking_car_one(array('car_number'=>$data['car_number'],'province'=>$data['province'],'village_id'=> $this->village_id));
            if($res && $res['car_id'] != $data['car_id']){
                $this->ajaxReturn(array('code'=>'2','msg'=>'车牌号已存在'));
            }
            
            if($data['car_stop_num']){
                $res = D('House_village_parking_car')->get_parking_car_one(array('car_stop_num'=>$data['car_stop_num'],'village_id'=> $this->village_id));
                if ($res && $res['car_id'] != $data['car_id']) {
                    $this->ajaxReturn(array('code'=>'2','msg'=>'停车卡号已存在'));
                 }
            }
            
            
            $result = D('House_village_parking_car')->parking_car_save($data);
            if($result !== false){
                $this->ajaxReturn(array('code'=>'1','msg'=>'修改成功!'));
            }else{
                $this->ajaxReturn(array('code'=>'2','msg'=>'修改失败!&nbsp;&nbsp;请检查是否车牌号码或停车卡号已存在!'));
            }
        }else{
            //车辆管理-查看 权限
            if (!in_array(55, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }

            $car_id = trim($_GET['car_id']);
            if (!$car_id && $car_id='') {
                $this->error('错误的车辆信息');
            }
           $info_list = D('House_village_parking_car')->get_parking_car_one(array('car_id'=>$car_id,'village_id'=>$this->village_id));
           $position_info = D('House_village_parking_position')->get_one(array('position_id'=>$info_list['car_position_id']));
           $this->assign('position_info',$position_info);
           $this->assign('info_list',$info_list);
        }
        $city_arr = array('京','津','冀','晋','蒙','辽','吉','黑','沪','苏','浙','皖','闽','赣','鲁','豫','鄂','湘','粤','桂','琼','渝','川','贵','云','藏','陕','甘','青','宁','新');
        $this->assign('city_arr',$city_arr);
        $this->display();
    }

    /**
     * [vehicle_detail 车辆详情]
     * @return [type] [description]
     */
    public function vehicle_detail(){
        //车辆管理-查看绑定用户 权限
        if (!in_array(59, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $where=array();
        $car_id = intval(trim($_GET['car_id']));
        if ($car_id  &&  $car_id!=='') {
            $where['car_id'] = $car_id;
        }
        $bind_list = D('House_village_bind_car')->get_bind_car_select('',$where);//车辆住户绑定关系
        // $field='a.*,b.position_num';
        // $join='join pigcms_house_village_parking_position as b on a.car_position_id=b.position_id';
        // $info_list = D('House_village_parking_car')->get_parking_car_info($field,$where,$join);
        // if($info_list == false){
        //     $this->error('获取信息错误!');
        // }
        $model = D('House_village_user_bind');
        $field='a.pigcms_id,a.name,a.phone,a.room_addrss,b.floor_name,b.floor_layer';
        $join='join '.C('DB_PREFIX').'house_village_floor as b on a.floor_id=b.floor_id';

        foreach ($bind_list as $key => $value) {
            $condition['a.pigcms_id'] = $value['user_id'];
            $condition['a.village_id'] = $this->village_id;
            $data_list[$key] = $model->get_user_bind_info($field,$join,$condition);
            $data_list[$key]['car_id'] = $info_list['car_id'];               
            $data_list[$key]['id'] = $value['id'];               
        }
        // $this->assign('info_list',$info_list);
        $this->assign('data_list',$data_list);
        $this->display();

    }

    /**
     * [bind_position_del 解除车辆绑定]
     * @return [type] [description]
     */
    public function unbind_car(){
        //车辆管理-解绑 权限
        if (!in_array(256, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $bind_id = intval(trim($_POST['bind_id']));

        if(!$bind_id || $bind_id ==''){
            $this->ajaxReturn(array('code'=>2,'msg'=>'参数传递错误!'));
        }else{
            $where['id'] = $bind_id;
        }
        $result = D('House_village_bind_car')->del_bind_car($where);//解除
        if($result !== false){
            $this->ajaxReturn(array('code'=>1,'msg'=>'解绑成功!'));
        }else{
            $this->ajaxReturn(array('code'=>2,'msg'=>'解绑失败!'));
        }
    }

     /* 导出车辆excel函数*/  
    public function vehicle_export($name='Excel'){  
        //车辆管理-导出 权限
        if (!in_array(263, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        error_reporting(E_ALL);  
        date_default_timezone_set('Europe/London');  
        $objExcel = new PHPExcel();  
  
        $title = $this->village['village_name'] . '社区-车辆列表';
        /*以下是一些设置 ，什么作者  标题之类的*/  
         $objExcel->getProperties()->setCreator($title)  
           ->setLastModifiedBy($title)  
           ->setTitle($title)  
           ->setSubject("数据EXCEL导出")  
           ->setDescription("备份数据")  
           ->setKeywords("excel")  
           ->setCategory("result file");  
        
         /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/ 
         $where=array();
        if ($_GET['search_type'] && $_GET['search_value']) {
            switch ($_GET['search_type']) {
                case '1'://车牌号
                    $where['a.car_number'] = array('like','%'.$_GET['search_value'].'%');
                    break;
                case '2': //停车卡号
                    $where['a.car_stop_num'] =array('like','%'.$_GET['search_value'].'%');
                    break;
                case '3'://车位号
                    $where['b.position_num'] = array('like','%'.$_GET['search_value'].'%');
                    break;
                case '4'://车主姓名
                    $where['a.car_user_name'] = array('like','%'.$_GET['search_value'].'%');
                    break;
                case '5'://车主手机号
                    $where['a.car_user_phone'] = array('like','%'.$_GET['search_value'].'%');
                    break;
            }
        }
      
        $where['a.village_id'] = $this->village_id;
        $join=' left join pigcms_house_village_parking_position as b on a.car_position_id=b.position_id';
        $field='a.*,b.position_num';
        $order='a.car_addtime desc';

        // import('@.ORG.merchant_page');
        $count = D('House_village_parking_car')->alias('a')->where($where)->join($join)->count();
        $data = D('House_village_parking_car')->get_parking_car_list($field,$where,$join,$order,9999999);
        $length = ceil($count / 1000);
        
        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);
            $objExcel->getActiveSheet()->setTitle($title);
            $objActSheet = $objExcel->getActiveSheet();
            $objActSheet->setCellValue('A1', '所属小区');
            $objActSheet->setCellValue('B1', '车位编号');
            $objActSheet->setCellValue('C1', '车牌号');
            $objActSheet->setCellValue('D1', '停车卡号');
            $objActSheet->setCellValue('E1', '车主姓名');
            $objActSheet->setCellValue('F1', '车主手机号');
            $objActSheet->setCellValue('G1', '车辆排量(升)');
            $field='village_name';
            $village_id = $this->village_id;
            $village_name = D('House_village')->get_village_info($village_id,$field);    
            if (!empty($data)) {
                $index = 2;
                for ($k = $i*1000; $k < ($i+1)*1000; $k++) { 
                    if (!$data['info_list'][$k]['car_id']) {
                       break;
                    }
                    $objActSheet->setCellValueExplicit('A' . $index, $village_name['village_name']);
                    $objActSheet->setCellValueExplicit('B' . $index, $data['info_list'][$k]['position_num']);
                    $objActSheet->setCellValueExplicit('C' . $index, $data['info_list'][$k]['province'].$data['info_list'][$k]['car_number']);
                    $objActSheet->setCellValueExplicit('D' . $index, $data['info_list'][$k]['car_stop_num']);
                    $objActSheet->setCellValueExplicit('E' . $index, $data['info_list'][$k]['car_user_name']);
                    $objActSheet->setCellValueExplicit('F' . $index, $data['info_list'][$k]['car_user_phone']);
                    $objActSheet->setCellValueExplicit('G' . $index, $data['info_list'][$k]['car_displacement']);
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
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();                      
    }  


    /**
     * [car_del 车辆信息删除]
     * @return [type] [description]
     */
    public function car_del(){
        //车辆管理-删除 权限
        if (!in_array(58, $this->house_session['menus'])) {
            $this->ajaxReturn(array('code'=>2,'msg'=>'对不起，您没有权限执行此操作'));
        }

        $id = trim($_POST['car_id']);
        if(!$id && $id=''){
            $this->ajaxReturn(array('code'=>2,'msg'=>'获取删除对象失败!'));
        }
        $res = D('House_village_bind_car')->get_bind_car_one(array('car_id'=>$id));
        if($res){
            $this->ajaxReturn(array('code'=>2,'msg'=>'请先解绑该车辆绑定的住户!'));
        }
        $where['car_id'] = array('in',$id);
        $result = D('House_village_parking_car')->parking_car_del($where);
        if($result !== false){
            $this->ajaxReturn(array('code'=>1,'msg'=>'删除成功!'));
        }else{
            $this->ajaxReturn(array('code'=>2,'msg'=>'删除失败!'));
        }
    }


    /**
     * [deposit_management 押金管理]
     * @return [type] [description]
     */
    public function deposit_management(){
        //押金管理-查看 权限
        if (!in_array(41, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if ($_GET['is_refund'] && $_GET['is_refund'] !=='') {//押金状态
            $where['is_refund'] = trim($_GET['is_refund']);
        }

        $begin_time = $_GET['begin_time'];
        $end_time = $_GET['end_time'];

        if ($begin_time && !$end_time) {
            $where['a.pay_time'] = array('gt', strtotime($begin_time));
        }

        if (!$begin_time && $end_time) {
            $where['a.pay_time'] = array('lt', strtotime($end_time.' 23:59:59'));
        }

        if ($begin_time && $end_time) {
            $where['a.pay_time'] = array('between', array(strtotime($begin_time), strtotime($end_time.' 23:59:59')));
        }

        $where['c.village_id'] = $this->village_id;
        $field='a.*,b.name,c.name as pay_name,d.realname,d.account';
        $join='join '.C('DB_PREFIX').'house_village_user_bind as b on a.pigcms_id=b.pigcms_id join '.C('DB_PREFIX').'house_village_pay_type as c on a.pay_type=c.id left join '.C('DB_PREFIX').'house_admin as d on a.role_id=d.id';
        $order='a.pay_time desc';
        $info_list = D('House_village_deposit')->get_deposit_list($field,$join,$where,$order);
        //打印模板
        $print_template = D('House_village_print_template')->get_select(array('village_id'=>$this->village_id));
        $this->assign('print_template',$print_template);
        $this->assign('info_list',$info_list);
        $this->display();
    }

    /**
     * [print_deposit 押金打印]
     * @return [type] [description]
     */
    public function print_deposit(){
        //押金管理-打印 权限
        if (!in_array(44, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $deposit_id = $_GET['deposit_id'] + 0;
        $template_id = $_GET['template_id'] + 0;
        $where = array(
            'a.deposit_id' => $deposit_id,
            'a.village_id' => $this->village_id
        );
        //获取详情
        $field='a.*,b.name,c.name as pay_name,b.usernum,b.phone,b.address';
        $join='join '.C('DB_PREFIX').'house_village_user_bind as b on a.pigcms_id=b.pigcms_id join '.C('DB_PREFIX').'house_village_pay_type as c on a.pay_type=c.id';
        $info_list = D('House_village_deposit')->get_deposit_one($field,$join,$where);
        //获取模板详情
        $house_village_print_template = D('House_village_print_template');

        $print_template = $house_village_print_template->get_one($template_id);
        $info_list['username'] = $info_list['name'];
        $info_list['order_name'] = $info_list['deposit_name'];
        $info_list['money'] = $info_list['payment_money'];
        $info_list['real_money'] = $info_list['actual_money'];
        $info_list['remarks'] = $info_list['deposit_note'];
        $info_list['pay_time'] = date("Y-m-d",$info_list['pay_time']);
        $info_list['totalMoney'] = '￥'.$info_list['actual_money'].'（人民币大写：'.cny($info_list['actual_money']).'）';
        $info_list['case'] = cny($info_list['actual_money']);
        $info_list['desc'] = $print_template['desc'];
        $info_list['print_time'] = date('Y-m-d H:i:s');
        $info_list['payer'] = $info_list['name'];
        $info_list['room_num'] = $info_list['address'];
        $info_list['village_name'] = $this->house_session['village_name'];
         //收款人
        if ($_SESSION['house']['role_id']) {
            $role_info = D('House_admin')->where(array('id'=>$_SESSION['house']['role_id']))->find();
            $info_list['payee'] = $role_info['realname'] ? $role_info['realname'] : $role_info['account'];
        }else{
            $info_list['payee'] =  $_SESSION['house']['account'];
        }
        $this->assign('info_list',$info_list);
        $this->assign('print_template',$print_template);
        $this->display();
    }



    /**
     * [deposit_add 添加押金]
     * @return [type] [description]
     */
    public function deposit_add(){
        //押金管理-添加 权限
        if (!in_array(42, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            $data['room_num'] = htmlspecialchars(trim($_POST['room_num']));
            $data['pay_type'] = htmlspecialchars(trim($_POST['pay_type']));
            $data['pigcms_id'] = htmlspecialchars(trim($_POST['pigcms_id']));
            $data['payment_money'] = htmlspecialchars(trim($_POST['payment_money']));
            $data['actual_money'] = htmlspecialchars(trim($_POST['actual_money']));
            $data['deposit_balance'] = htmlspecialchars(trim($_POST['actual_money']));
            $data['deposit_note'] = htmlspecialchars(trim($_POST['deposit_note']));
            $data['deposit_name'] = htmlspecialchars(trim($_POST['deposit_name']));
            $data['pay_time'] = time();
            $data['role_id'] = !empty($_SESSION['house']['role_id']) ? $_SESSION['house']['role_id'] : 0 ;

            $data['village_id'] = $this->village_id;
            $result = D('House_village_deposit')->house_deposit_add($data);
            if($result !=false){
                $this->ajaxReturn(array('code'=>1,'msg'=>'添加成功!'));
            }else{
                $this->ajaxReturn(array('code'=>2,'msg'=>'添加失败!'));
            }
        }else{
            $pay_type_list = D('House_village_pay_type')->get_pay_type_select(array('village_id'=>$this->village_id));
            $this->assign('pay_type_list',$pay_type_list);
        }
        $this->display();
    }

    /**
     * [deposit_del 押金删除]
     * @return [type] [description]
     */
    public function deposit_del(){
        //押金管理-删除 权限
        if (!in_array(43, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $id = trim($_POST['deposit_id']);
        if(!$id && $id=''){
            $this->ajaxReturn(array('code'=>2,'msg'=>'获取删除对象失败!'));
        }

        $where['deposit_id'] = array('in',$id);
        $result = D('House_village_deposit')->deposit_delete($where);
        if($result !== false){
            $this->ajaxReturn(array('code'=>1,'msg'=>'删除成功!'));
        }else{
            $this->ajaxReturn(array('code'=>1,'msg'=>'删除失败!'));
        }
    }

    /**
     * [deposit_refund 押金退款]
     * @return [type] [description]
     */
    public function deposit_refund(){
        //押金管理-退款 权限
        if (!in_array(252, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        if(IS_POST){
            $data['deposit_id'] = htmlspecialchars(trim($_POST['deposit_id']));
            $arr['refund_money'] = htmlspecialchars(trim($_POST['refund_money'])); 
            $data['refund_note'] = htmlspecialchars(trim($_POST['refund_note']));
            $data['refund_time'] = time();

            if($arr['refund_money'] < 0 || !is_numeric($arr['refund_money'])){
                $this->ajaxReturn(array('code'=>2,'msg'=>'请输入正确的退款金额!'));
            }
            
            $deposit_model = D('House_village_deposit');
            $res = $deposit_model->get_deposit_one('','',array('deposit_id'=>$data['deposit_id']));


            $data['deposit_balance'] = $res['actual_money'] - $res['refund_money'] - $arr['refund_money'];
            if($data['deposit_balance'] < 0){
                $this->ajaxReturn(array('code'=>2,'msg'=>'退款金额大于押金余额!'));
            }else{
                $data['is_refund'] = 2;
            }
            $data['refund_money'] = $arr['refund_money'] + $res['refund_money'];

            $result = $deposit_model->save($data);
            if($reuslt !== false){
                $this->ajaxReturn(array('code'=>1,'msg'=>'退款成功!'));
            }else{
                $this->ajaxReturn(array('code'=>2,'msg'=>'退款失败!'));
            }

        }else{
            $where['deposit_id'] = trim($_GET['deposit_id']);
            $field='a.*,b.name';
            $join='join '.C('DB_PREFIX').'house_village_user_bind as b on a.pigcms_id=b.pigcms_id';
            $info_list = D('House_village_deposit')->get_deposit_one($field,$join,$where);
            $this->assign('info_list',$info_list);
        }
        $this->display();
    }

    /**
     * [count_management 统计管理]
     * @return [type] [description]
     */
    public function count_management(){
        //统计管理-查看 权限
        if (!in_array(64, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }
        $user_bind_model = D('House_village_user_bind');
        //空置
        $room_count['total_count'] = D('House_village_user_vacancy')->get_user_vacancy_count(array('type'=>'0','is_del'=>'0','status'=>array('neq',3),'village_id'=>$this->village_id),'pigcms_id');
        $room_count['bind_count'] = D('House_village_user_vacancy')->get_user_vacancy_count(array('type'=>'0','is_del'=>'0','status'=>array('eq',3),'village_id'=>$this->village_id),'pigcms_id');
        //车位统计
        $position_count['yes_bind'] = D('House_village_bind_position')->get_bind_position_count('distinct(position_id)',array('village_id'=>$this->village_id));

        //未绑定业主的车位
        $position_count['no_bind'] = D('House_village_parking_position')->get_parking_position_count('distinct(position_id)',array('village_id'=>$this->village_id))-$position_count['yes_bind'];//车位总量
        
        //欠费统计
        $unpaid = $user_bind_model->get_cashier_unpaid_list(array('village_id'=>$this->village_id),0,0);
         $pay_count['water_price'] = $unpaid['water_money'];
         $pay_count['electric_price'] = $unpaid['electric_money'];
         $pay_count['gas_price'] = $unpaid['gas_money'];
         $pay_count['park_price'] = $unpaid['park_money'];
         $pay_count['property_price'] = $unpaid['property_money'];
         $pay_count['cunstom_money'] = $unpaid['cunstom_money'];
        
        //收入统计
       /* $pay_order_model = D('House_village_pay_order');
        $pay_order['property'] = $pay_order_model->get_pay_order_sum('money',array('paid'=>'1','order_type'=>'property','village_id'=>$this->village_id));//已支付的物业费
        $pay_order['water'] = $pay_order_model->get_pay_order_sum('money',array('paid'=>'1','order_type'=>'water','village_id'=>$this->village_id));//已支付的水费
        $pay_order['electric'] = $pay_order_model->get_pay_order_sum('money',array('paid'=>'1','order_type'=>'electric','village_id'=>$this->village_id));//已支付的电费
        $pay_order['gas'] = $pay_order_model->get_pay_order_sum('money',array('paid'=>'1','order_type'=>'gas','village_id'=>$this->village_id));//已支付的燃气费
        $pay_order['custom'] = $pay_order_model->get_pay_order_sum('money',array('paid'=>'1','order_type'=>'custom','village_id'=>$this->village_id));//社区自定义缴费
        */
        //业主统计
        $user_bind_model = D('House_village_user_bind');
        $user_bind['owner'] = $user_bind_model->get_user_bind_count(array('type'=>'0','village_id'=>$this->village_id),'');//房主
        $user_bind['family'] = $user_bind_model->get_user_bind_count(array('type'=>'1','village_id'=>$this->village_id),'');//家人
        $user_bind['tenant'] = $user_bind_model->get_user_bind_count(array('type'=>'2','village_id'=>$this->village_id),'');//租客
        $user_bind['new_owner'] = $user_bind_model->get_user_bind_count(array('type'=>'3','village_id'=>$this->village_id),'');//更新房主

        $this->assign('room_count',$room_count);
        $this->assign('position_count',$position_count);
        $this->assign('pay_count',$pay_count);
        $this->assign('pay_order',$pay_order);
        $this->assign('user_bind',$user_bind);
        $this->display();
    }


    //未缴账单 
    public function cashier_unpaid(){
        //未缴 权限
        if (!in_array(85, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_village_pay_order = D('House_village_pay_order');
        $village_id = $this->village_id;

        if($village_id){
            $where = array(
                'village_id' => $village_id,
            );
            if (!empty($_GET['keyword'])) {
                if ($_GET['searchtype'] == 'name') {
                    $where['name'] = trim($_GET['keyword']);
                } else if ($_GET['searchtype'] == 'phone') {
                    $where['phone'] =  trim($_GET['keyword']);
                }
            }

            if($_GET['is_bind_weixin']){
                $where['is_bind_weixin'] = $_GET['is_bind_weixin'];
            }

            // 查询总账单
            $total = D('House_village_user_bind')->get_cashier_unpaid_list($where,0,0);

            $list = D('House_village_user_bind')->get_cashier_unpaid_list($where,0,20);
            $this->assign('total',$total);
            $this->assign('list',$list);
        }
        $this->assign('village_id',$village_id);
        $this->display();
    }

    //导出未缴账单 
    public function cashier_unpaid_export(){
        //未缴账单-导出 权限
        if (!in_array(267, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        error_reporting(E_ALL);  
        date_default_timezone_set('Europe/London');  
        $objExcel = new PHPExcel();  
  
        $title = $this->village['village_name'] . '社区-物业未缴总账单列表';
        /*以下是一些设置 ，什么作者  标题之类的*/  
         $objExcel->getProperties()->setCreator($title)  
           ->setLastModifiedBy($title)  
           ->setTitle($title)  
           ->setSubject("数据EXCEL导出")  
           ->setDescription("备份数据")  
           ->setKeywords("excel")  
           ->setCategory("result file");  
        
         /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/ 
        //  $where=array();
        // if ($_GET['search_type'] && $_GET['search_value']) {
        //     switch ($_GET['search_type']) {
        //         case '1'://车牌号
        //             $where['a.car_number'] = array('like','%'.$_GET['search_value'].'%');
        //             break;
        //         case '2': //停车卡号
        //             $where['a.car_stop_num'] =array('like','%'.$_GET['search_value'].'%');
        //             break;
        //         case '3'://车位号
        //             $where['b.position_num'] = array('like','%'.$_GET['search_value'].'%');
        //             break;
        //         case '4'://车主姓名
        //             $where['a.car_user_name'] = array('like','%'.$_GET['search_value'].'%');
        //             break;
        //         case '5'://车主手机号
        //             $where['a.car_user_phone'] = array('like','%'.$_GET['search_value'].'%');
        //             break;
        //     }
        // }
      


        $where = array(
            'village_id' => $this->village_id,
        );


        $data = D('House_village_user_bind')->get_cashier_unpaid_list($where,0,0);

        $length = ceil(count($data) / 1000);
        
        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);
            $objExcel->getActiveSheet()->setTitle('车辆列表');
            $objActSheet = $objExcel->getActiveSheet();
            $objActSheet->setCellValue('A1', '所属小区');
            $objActSheet->setCellValue('B1', '物业编号');
            $objActSheet->setCellValue('C1', '业主姓名');
            $objActSheet->setCellValue('D1', '手机号');
            $objActSheet->setCellValue('E1', '地址');
            $objActSheet->setCellValue('F1', '水费');
            $objActSheet->setCellValue('G1', '电费');
            $objActSheet->setCellValue('H1', '燃气费');
            $objActSheet->setCellValue('I1', '停车费');
            $objActSheet->setCellValue('J1', '物业费');
            $objActSheet->setCellValue('K1', '自定义项费');
            $objActSheet->setCellValue('L1', '合计');
            $field='village_name';
            $village_id = $this->village_id;
            $village_name = D('House_village')->get_village_info($village_id,$field);    
            if (!empty($data)) {
                $index = 2;
                for ($k = $i*1000; $k < ($i+1)*1000; $k++) {
                    if (!$data['list'][$k]['usernum']) {
                        break;
                    }
                    $objActSheet->setCellValueExplicit('A' . $index, $village_name['village_name']);
                    $objActSheet->setCellValueExplicit('B' . $index, $data['list'][$k]['usernum']);
                    $objActSheet->setCellValueExplicit('C' . $index, $data['list'][$k]['name']);
                    $objActSheet->setCellValueExplicit('D' . $index, $data['list'][$k]['phone']);
                    $objActSheet->setCellValueExplicit('E' . $index, $data['list'][$k]['address']);
                    $objActSheet->setCellValueExplicit('F' . $index, $data['list'][$k]['water_price']);
                    $objActSheet->setCellValueExplicit('G' . $index, $data['list'][$k]['electric_price']);
                    $objActSheet->setCellValueExplicit('H' . $index, $data['list'][$k]['gas_price']);
                    $objActSheet->setCellValueExplicit('I' . $index, $data['list'][$k]['park_price']);
                    $objActSheet->setCellValueExplicit('J' . $index, $data['list'][$k]['property_price']);
                    $objActSheet->setCellValueExplicit('K' . $index, $data['list'][$k]['cunstom_money']);
                    $objActSheet->setCellValueExplicit('L' . $index, $data['list'][$k]['total']);
                    $index++;

                }
                $objActSheet->setCellValueExplicit('A' . $index, '合计');
                $objActSheet->setCellValueExplicit('B' . $index, '');
                $objActSheet->setCellValueExplicit('C' . $index, '');
                $objActSheet->setCellValueExplicit('D' . $index, '');
                $objActSheet->setCellValueExplicit('E' . $index, '');
                $objActSheet->mergeCells('A' . $index.':E' . $index);
                $objActSheet->setCellValueExplicit('F' . $index, $data['water_money']);
                $objActSheet->setCellValueExplicit('G' . $index, $data['electric_money']);
                $objActSheet->setCellValueExplicit('H' . $index, $data['gas_money']);
                $objActSheet->setCellValueExplicit('I' . $index, $data['park_money']);
                $objActSheet->setCellValueExplicit('J' . $index, $data['property_money']);
                $objActSheet->setCellValueExplicit('K' . $index, $data['cunstom_money']);
                $objActSheet->setCellValueExplicit('L' . $index, $data['total_money']);
                $index++;
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
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();                      
    }

}
?>

