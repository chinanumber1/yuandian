<?php

/*
 * 社区O2O功能
 *
 */

class HouseAction extends BaseAction{
    private $open_door_status = array(0=>'开门成功',1=>'扫描失败',2=>'连接失败',3=>'重连失败',4=>'获取不到蓝牙关键字');
	public function config(){
		redirect(U('Config/index',array('galias'=>'house','header'=>'House/header')));
	}
	
    public function village(){
        //搜索
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'village_id') {
                $condition_house_village['village_id'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'village_name') {
                $condition_house_village['village_name'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'property_name') {
                $condition_house_village['property_name'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'property_phone') {
                $condition_house_village['property_phone'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'account') {
                $condition_house_village['account'] = $_GET['keyword'];
            }
        }
        if($condition_house_village){
			$count	=	10000;
        }else{
			$count	=	30;
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
            $this->assign('admin_area',$now_area['area_type']);
            $condition_house_village[$area_index] = $this->system_session['area_id'];
        }
        if($_GET['city_idss'] && $this->config['many_city']){
            $condition_house_village['city_id'] =$where['city_id']= $_GET['city_idss'];
        }
        if($_GET['area_id']){
            $condition_house_village['area_id'] =$where['area_id']= $_GET['area_id'];
        }

        $database_house_village = D('House_village');
        $count_village = $database_house_village->where($condition_house_village)->count();
        import('@.ORG.system_page');
        $p = new Page($count_village, $count);
        $village_list = $database_house_village->field(true)->where($condition_house_village)->order('`village_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign('village_list', $village_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

        $this->display();
    }

    //小区短信批量添加
    public function sms_add_all(){
    	if(IS_POST){
			$add_a = M('House_village')->where(array())->setInc('now_sms_number',intval($_POST['sms_number']));
			if($add_a){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->display();
		}
    }


	//小区添加
	public function village_add(){
		if(IS_POST){
            // 添加默认允许游客访问
            if (!$_POST['tourist']) {
                $_POST['tourist'] = 1;
            }
			$_POST['pwd'] = md5($_POST['pwd']);
			$database_house_village = D('House_village');
			$database_house_village_config = D('House_village_config');
			$_POST['add_time'] = time();
			if ($_POST['expiration_time']) { // 到期时间
				$_POST['expiration_time'] = strtotime($_POST['expiration_time']);
			}
			$add_a = $database_house_village->data($_POST)->add();

			if($add_a){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->display();
		}
	}
	//小区添加
	public function village_edit(){

		if($this->system_session['level']!=2&&!in_array(247,$this->system_session['menus'])){
            $can_recharge = 0;
        }else{
            $can_recharge = 1;
        }
        $this->assign('can_recharge',$can_recharge);
		$database_house_village = D('House_village');
		$database_house_village_config = D('House_village_config');
		if(IS_POST){

			$now_sms_number = $database_house_village->where(array('village_id'=>$_POST['village_id']))->getField('now_sms_number');

			if (!empty($_POST['set_sms_number'])) {

				$sms_buy_order_data['orderid'] = 'sq'.date("ymdHis").rand(10,99).sprintf("%06d",$_POST['village_id']);
				$sms_buy_order_data['type_id'] = $_POST['village_id'];
				$sms_buy_order_data['type'] = 1;
				$sms_buy_order_data['payment_money'] = 0.00;
				$sms_buy_order_data['sms_number'] = $_POST['set_sms_number'];
				$sms_buy_order_data['add_time'] = time();
				$sms_buy_order_data['pay_time'] = time();
				$sms_buy_order_data['pay_type'] = 'system';
				$sms_buy_order_data['paid'] = 3;
				$sms_buy_order_data['operation_type'] = 1;
				$sms_buy_order_data['operation'] = $this->system_session['realname'];



                if ($_POST['set_sms_type'] == 1) {
                    $_POST['now_sms_number'] = $now_sms_number + $_POST['set_sms_number'];
                    $sms_buy_order_data['set_type'] = 0;
                } else {
                    $_POST['now_sms_number'] = $now_sms_number - $_POST['set_sms_number'];
                    $sms_buy_order_data['set_type'] = 1;
                }

                $sms_buy_order_data['current_number'] = $_POST['now_sms_number'];

                if ($_POST['now_sms_number'] < 0) {
                    $this->error('修改后，短信条数不能小于0');
                }
            }

			if($_POST['pwd']){
				$_POST['pwd'] = md5($_POST['pwd']);
			}else{
				unset($_POST['pwd']);
			}

            $data['open_score_get_percent']             =   $_POST['open_score_get_percent'];
            $data['user_score_get']             =   $_POST['user_score_get'];
            $data['score_get_percent']             =   $_POST['score_get_percent'];

            $data['village_pay_integral']             =   $_POST['village_pay_integral'];
			$data['village_owe_pay_integral']         =   $_POST['village_owe_pay_integral'];
			$data['village_pay_use_integral']         =   $_POST['village_pay_use_integral'];
			$data['village_pay_owe_use_integral']     =   $_POST['village_pay_owe_use_integral'];
			$data['use_max_integral_num']             =   $_POST['use_max_integral_num'];
			$data['use_max_integral_percentage']      =   $_POST['use_max_integral_percentage'];
			$data['village_id']                       =   $_POST['village_id'];
			if ($_POST['expiration_time']) { // 到期时间
				$_POST['expiration_time'] = strtotime($_POST['expiration_time']);
			}

			$edit_a = $database_house_village->where(array('village_id'=>$_POST['village_id']))->data($_POST)->save();
			
			$find = $database_house_village_config->where(array('village_id'=>$_POST['village_id']))->find();
			if($find){
				$edit_b = $database_house_village_config->where(array('village_id'=>$_POST['village_id']))->save($data);
			}else{
				$edit_b = $database_house_village_config->add($data);	
			}
			
			if(!$edit_a && !$edit_b){
				$this->error('编辑失败！请重试~');
			}else{
				if (!empty($_POST['set_sms_number'])) {
               		D('Sms_buy_order')->data($sms_buy_order_data)->add();
	            }
				$this->success('编辑成功！');
			}
		}else{
			
			$now_village = $database_house_village->field(true)->where(array('village_id'=>$_GET['village_id']))->find();
			if(empty($now_village)){
				$this->frame_error_tips('当前小区不存在');
			}else{
				$now_village['expiration_time'] = $now_village['expiration_time'] ? date('Y-m-d H:i',$now_village['expiration_time']) : '';	
				$info = $database_house_village_config->field(true)->where(array('village_id'=>$now_village['village_id']))->find();	
				if($info){
					$now_village['village_pay_integral'] = $info['village_pay_integral'];
					$now_village['village_owe_pay_integral'] = $info['village_owe_pay_integral'];
					$now_village['village_pay_use_integral'] = $info['village_pay_use_integral'];
					$now_village['village_pay_owe_use_integral'] = $info['village_pay_owe_use_integral'];
					$now_village['use_max_integral_num'] = $info['use_max_integral_num'];
					$now_village['use_max_integral_percentage'] = $info['use_max_integral_percentage'];	
				}
			}
			
			$this->assign('now_village',$now_village);

			$this->display();
		}
	}


	public function sms_order(){
		$condition_where['type_id']=$_GET['village_id'];
        $condition_where['type']=1;
       
        if($_GET['paid'] != ''){
            $condition_where['paid'] = array('eq',$_GET['paid']);
        }

        $condition_where['pay_type'] = 'system';
        // $count = D("Sms_buy_order")->where($condition_where)->count();
        // import('@.ORG.merchant_page');
        // $p = new Page($count, 10);
        $orderList= M('Sms_buy_order')->where($condition_where)->order('order_id DESC')->select();
        // $this->assign('pagebar',$p->show());
        $this->assign('orderList',$orderList);

		$this->display();
	}

	//小区导入
	public function village_import(){
		if(IS_POST){
			if ($_FILES['file']['error'] != 4) {
				$upload_dir = './upload/excel/village/'.date('Ymd').'/';
				if (!is_dir($upload_dir)) {
					mkdir($upload_dir, 0777, true);
				}
				import('ORG.Net.UploadFile');
				$upload = new UploadFile();
				$upload->maxSize = 10 * 1024 * 1024;
				$upload->allowExts = array('xls','xlsx');
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
					if (!empty($result) && is_array($result)) {
						unset($result[1]);
						$database_house_village = D('House_village');
						$last_village_id = 0;
						$err_msg = '';
						foreach ($result as $kk => $vv) {
							if($vv['A'] === null && $vv['B'] === null && $vv['C'] === null && $vv['D'] === null && $vv['E'] === null && $vv['F'] === null && $vv['G'] === null && $vv['H'] === null && $vv['I'] === null && $vv['J'] === null && $vv['K'] === null && $vv['L'] === null) continue;
							if(empty($vv['A'])){
								$err_msg = '请填写小区名称！';
								continue;
							}
							if(empty($vv['B'])){
								$err_msg = '请填写小区地址！';
								continue;
							}
							if(empty($vv['C'])){
								$err_msg = '请填写物业公司名称！';
								continue;
							}
							if(empty($vv['D'])){
								$err_msg = '请填写物业联系地址！';
								continue;
							}
							if(empty($vv['E'])){
								$err_msg = '请填写物业联系电话！';
								continue;
							}
							if(empty($vv['F'])){
								$err_msg = '请填写管理帐号！';
								continue;
							}
							if(empty($vv['G'])){
								$err_msg = '请填写管理密码！';
								continue;
							}

							$tmpdata = array();
							$tmpdata['village_name'] = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
							//检测小区是否已存在
							if($database_house_village->field('`village_id`')->where(array('village_name'=>$tmpdata['village_name']))->find()){
								$err_msg = $vv['A'].' 已存在！';
								continue;
							}

							$tmpdata['village_address'] = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
							$tmpdata['property_name'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
							$tmpdata['property_address'] = htmlspecialchars(trim($vv['D']), ENT_QUOTES);
							$tmpdata['property_phone'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
							$tmpdata['account'] = htmlspecialchars(trim($vv['F']), ENT_QUOTES);
							$tmpdata['pwd'] = md5(htmlspecialchars(trim($vv['G']), ENT_QUOTES));
							!empty($vv['H']) && $tmpdata['property_price'] = htmlspecialchars(trim($vv['H']));
							!empty($vv['I']) && $tmpdata['water_price'] = htmlspecialchars(trim($vv['I']), ENT_QUOTES);
							!empty($vv['J']) && $tmpdata['electric_price'] = htmlspecialchars(trim($vv['J']));
							!empty($vv['K']) && $tmpdata['gas_price'] = htmlspecialchars(trim($vv['K']), ENT_QUOTES);
							!empty($vv['L']) && $tmpdata['park_price'] = htmlspecialchars(trim($vv['L']), ENT_QUOTES);
							$tmpdata['status'] = 0;
							$tmpdata['add_time'] = time();
							$last_village_id = $database_house_village->data($tmpdata)->add();
							if(!$last_village_id){
								$err_msg = $vv['A'].' 导入失败！';
							}
						}
						if(!empty($last_village_id)){
							$this->frame_submit_tips(1,'导入成功');
							// $this->success('导入成功');
						}else{
							// $this->error('导入失败');
							$this->frame_submit_tips(0,'导入失败！原因：'.$err_msg);
						}
					}
				} else {
					// $this->error($upload->getErrorMsg());
					$this->frame_submit_tips(0,$upload->getErrorMsg());
				}
			}
			// $this->error('文件上传失败');
			$this->frame_submit_tips(0,'文件上传失败');
		}else{
			$this->display();
		}
	}

    public function edit() {
        $this->assign('bg_color', '#F3F3F3');

        $database_user = D('User');
        $condition_user['uid'] = intval($_GET['uid']);
        $now_user = $database_user->field(true)->where($condition_user)->find();
        if (empty($now_user)) {
            $this->frame_error_tips('没有找到该用户信息！');
        }

        $levelDb = M('User_level');
        $tmparr = $levelDb->field(true)->order('id ASC')->select();
        $levelarr = array();
        if ($tmparr) {
            foreach ($tmparr as $vv) {
                $levelarr[$vv['level']] = $vv;
            }
        }

        $this->assign('levelarr', $levelarr);
        $this->assign('now_user', $now_user);

        $this->display();
    }

    public function amend() {
        if (IS_POST) {
            $database_user = D('User');
            $condition_user['uid'] = intval($_POST['uid']);
            $now_user = $database_user->field(true)->where($condition_user)->find();
            if (empty($now_user)) {
                $this->error('没有找到该用户信息！');
            }
            $condition_user['uid'] = $now_user['uid'];
            $data_user['nickname'] = $_POST['nickname'];
            $data_user['phone'] = $_POST['phone'];
            if ($_POST['pwd']) {
                $data_user['pwd'] = md5($_POST['pwd']);
            }
            $data_user['sex'] = $_POST['sex'];
            $data_user['province'] = $_POST['province'];
            $data_user['city'] = $_POST['city'];
            $data_user['qq'] = $_POST['qq'];
            $data_user['status'] = $_POST['status'];
			$data_user['youaddress'] = trim($_POST['youaddress']);
			$data_user['truename'] = trim($_POST['truename']);

            $_POST['set_money'] = floatval($_POST['set_money']);
            if (!empty($_POST['set_money'])) {
                if ($_POST['set_money_type'] == 1) {
                    $data_user['now_money'] = $now_user['now_money'] + $_POST['set_money'];
                } else {
                    $data_user['now_money'] = $now_user['now_money'] - $_POST['set_money'];
                }
                if ($data_user['now_money'] < 0) {
                    $this->error('修改后，余额不能小于0');
                }
            }

            $_POST['set_score'] = intval($_POST['set_score']);
            if (!empty($_POST['set_score'])) {
                if ($_POST['set_score_type'] == 1) {
                    $data_user['score_count'] = $now_user['score_count'] + $_POST['set_score'];
                } else {
                    $data_user['score_count'] = $now_user['score_count'] - $_POST['set_score'];
                }
                if ($data_user['score_count'] < 0) {
                    $this->error('修改后，'.$this->config['score_name'].'不能小于0');
                }
            }

            $data_user['level'] = intval($_POST['level']);

            if ($database_user->where($condition_user)->data($data_user)->save()) {
                if (!empty($_POST['set_money'])) {
                    D('User_money_list')->add_row($now_user['uid'], $_POST['set_money_type'], $_POST['set_money'], '管理员后台操作', false);
                }
                if (!empty($_POST['set_score'])) {
                    D('User_score_list')->add_row($now_user['uid'], $_POST['set_score_type'], $_POST['set_score'], '管理员后台操作', false);
                }
                $this->success('修改成功！');
            } else {
                $this->error('修改失败！请重试。');
            }
        } else {
            $this->error('非法访问！');
        }
    }

	//平台提现
	public function companypay(){
		if(IS_POST){
			if(!$village_info=D('House_village')->field('village_name,property_phone')->where('village_id='.(int)$_POST['village_id'])->select()){
				$this->error('小区不存在！');
			}
			sort($_POST['orderid']);
			$orderids = implode(',',$_POST['orderid']);
			$data['pay_type'] = 'house';
			$data['pay_id'] = $_POST['village_id'];
			$data['phone'] = $village_info[0]['property_phone'];
			$data['money'] = $_POST['money'];
			$data['desc'] = '小区'.$village_info[0]['village_name'].'订单对账|订单号('.$orderids.')'.'|转账'.(float)($_POST['money']/100).' 元';
			$data['status'] = 0;
			$data['add_time'] = time();

			$model=new Model();
			$where['order_id']=array('in',$orderids);
			if($model->table(C('DB_PREFIX').'companypay')->add($data)&&$model->table(C('DB_PREFIX').'house_village_pay_order')->where($where)->setField('is_pay_bill',1)){
				$this->success("提现申请成功！");
			}else{
				$this->error("提现失败！请联系管理员！");
			}
		}else{
			$this->error('您提交的数据不正确');
		}
	}

    public function money_list() {
        $this->assign('bg_color', '#F3F3F3');


        $database_user_money_list = D('User_money_list');
        $condition_user_money_list['uid'] = intval($_GET['uid']);

        $count = $database_user_money_list->where($condition_user_money_list)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 15);

        $money_list = $database_user_money_list->field(true)->where($condition_user_money_list)->order('`time` DESC')->select();

        $this->assign('pagebar', $p->show());
        $this->assign('money_list', $money_list);
        $this->display();
    }

    public function score_list() {
        $this->assign('bg_color', '#F3F3F3');


        $database_user_score_list = D('User_score_list');
        $condition_user_score_list['uid'] = intval($_GET['uid']);

        $count = $database_user_score_list->where($condition_user_score_list)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 15);

        $score_list = $database_user_score_list->field(true)->where($condition_user_score_list)->order('`time` DESC')->select();

        $this->assign('pagebar', $p->show());
        $this->assign('score_list', $score_list);
        $this->display();
    }

    /*     * *导入客户页**** */

    public function import() {

        $this->display();
    }

    /*     * *导入客户页**** */

    public function execimport() {
        if ($_FILES['file']['error'] != 4) {

            $getupload_dir = "/upload/excel/user/" . date('Ymd') . '/';
            $upload_dir = "." . $getupload_dir;
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
                //$reader = PHPExcel_IOFactory::createReader('Excel5');
                $fileType = PHPExcel_IOFactory::identify($path); //文件名自动判断文件类型
                $objReader = PHPExcel_IOFactory::createReader($fileType);
                $excelObj = $objReader->load($path);
                $result = $excelObj->getActiveSheet()->toArray(null, true, true, true);
                if (!empty($result) && is_array($result)) {
                    unset($result[1]);
                    $user_importDb = D('User_import');
                    foreach ($result as $kk => $vv) {
                        if (empty($vv['A']) || empty($vv['B']) || empty($vv['C']))
                            continue;
                        $tmpdata = array();
                        $tmpdata['ppname'] = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
                        $tmpdata['telphone'] = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
                        $tmpdata['address'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                        !empty($vv['D']) && $tmpdata['village_id'] = intval(trim($vv['D']));
                        !empty($vv['E']) && $tmpdata['memberid'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
                        !empty($vv['F']) && $tmpdata['level'] = intval(trim($vv['F']));
                        !empty($vv['G']) && $tmpdata['qq'] = htmlspecialchars(trim($vv['G']), ENT_QUOTES);
                        !empty($vv['H']) && $tmpdata['email'] = htmlspecialchars(trim($vv['H']), ENT_QUOTES);
                        !empty($vv['I']) && $tmpdata['money'] = intval(trim($vv['I']));
                        !empty($vv['J']) && $tmpdata['integral'] = htmlspecialchars(trim($vv['J']), ENT_QUOTES);
                        !empty($vv['K']) && $tmpdata['useraccount'] = htmlspecialchars(trim($vv['K']), ENT_QUOTES);
                        if (!empty($vv['L'])) {
                            $tmpdata['pwdmw'] = trim($vv['L']);
                            $tmpdata['pwd'] = md5($tmpdata['pwdmw']);
                        }
                        $tmpdata['isuse'] = 0;
                        $tmpdata['addtime'] = time();
                        $user_importDb->add($tmpdata);
                    }
                    if (!empty($tmpdata)) {
                        $this->dexit(array('error' => 0));
                    } else {
                        $this->dexit(array('error' => 1, 'msg' => '导入失败！'));
                    }
                }
            } else {
                $this->dexit(array('error' => 1, 'msg' => $upload->getErrorMsg()));
            }
        }
        $this->dexit(array('error' => 1, 'msg' => '文件上传失败！'));
    }

    /*     * *导入客户的列表页**** */

    public function importlist() {
        $user_importDb = D('User_import');
        $count_userimportDb = $user_importDb->where('22=22')->count();
        import('@.ORG.system_page');
        $p = new Page($count_userimportDb, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $tmpdatas = $user_importDb->where('22=22')->order('id ASC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('userimport', $tmpdatas);
        $this->display();
    }

    /*     * *导入客户的列表页**** */

    public function levellist() {
        $user_levelDb = D('User_level');
        $count_userlevelDb = $user_levelDb->count();
        import('@.ORG.system_page');
        $p = new Page($count_userlevelDb, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $tmpdatas = $user_levelDb->where('22=22')->order('id ASC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('userlevel', $tmpdatas);
        $this->display();
    }

    /*     * *添加等级**** */

    public function addlevel() {
        $levelDb = M('User_level');
        $tmparr = $levelDb->where('22=22')->order('level DESC')->find();
        $level = 0;
        if (!empty($tmparr)) {
            $level = $tmparr['level'];
        }
        $level = $level + 1;
        if (IS_POST) {
            $lid = intval($_POST['lid']);
            if (!($lid > 0)) {
                $newdata = array('level' => $level);
            }
            $lname = trim($_POST['lname']);
            if (empty($lname))
                $this->error('等级名称没有填写！');
            $newdata['lname'] = $lname;

            $integral = intval($_POST['integral']);
            if (!($integral > 0))
                $this->error('等级'.$this->config['score_name'].'没有填写！');
            $newdata['integral'] = $integral;

            $newdata['icon'] = trim($_POST['icon']);
            $newdata['type'] = trim($_POST['fltype']);
            $newdata['boon'] = trim($_POST['boon']);
            $newdata['description'] = trim($_POST['description']);

            if ($lid > 0) {
                $inser_id = $levelDb->where(array('id' => $lid))->save($newdata);
            } else {
                $inser_id = $levelDb->add($newdata);
            }
            if ($inser_id) {
                $this->success('保存成功！');
            } else {
                $this->error('保存失败！');
            }
        } else {
            $lid = intval($_GET['lid']);
            $tmpdata = $levelDb->where(array('id' => $lid))->find();
            if (empty($tmpdata)) {
                $tmpdata = array('id' => 0, 'level' => $level, 'lname' => '', 'integral' => '', 'icon' => '', 'boon' => '', 'type' => 0, 'description' => '');
            }
            $this->assign('leveldata', $tmpdata);
            $this->display();
        }
    }

    public function pay_order(){
		if (!empty($_GET['keyword'])) {
			if ($_GET['searchtype'] == 'order_name') {
				$condition['order_name'] = $_GET['keyword'];
			} else if ($_GET['searchtype'] == 'phone') {
				$condition['phone'] = $_GET['keyword'] ;
			}
		}


        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['pay_time_str']=$time_condition;
            $this->assign('begin_time',$_GET['begin_time']);
            $this->assign('end_time',$_GET['end_time']);
        }

        if(isset($_GET['searchstatus'])){
            $condition['is_pay_bill'] = $_GET['searchstatus'];
        }


    	$village_id = $_GET['village_id'];
    	if($village_id){
    		$condition['village_id'] = $village_id;
    		$condition['paid'] = 1;
			$condition['pay_type'] = 1;
    		$result = D('House_village_pay_order')->get_limit_list_page($condition,20,true);

    		$finshtotal = $total = 0;
    		if($result){
    			foreach ($result['order_list'] as $v){
    				$total += $v['money'];								//本页的总额
    				$v['is_pay_bill'] && $finshtotal += $v['money'];	//本页已对账的总额
    			}
    		}
    		$this->assign('finshtotal',$finshtotal);
    		$this->assign('total',$total);

    		$this->assign('order_list',$result);
    	}
    	$this->assign('village_id',$village_id);
    	$this->display();
    }

    public function change(){
    	$village_id = $_POST['village_id'];
    	$strids = isset($_POST['strids']) ? htmlspecialchars($_POST['strids']) : '';
    	if ($strids && $village_id) {
    		$array = explode(',', $strids);
            $array && D('House_village_pay_order')->where(array('village_id' => $village_id, 'order_id' => array('in', $array)))->save(array('is_pay_bill' => 1));
    	}
    	exit(json_encode(array('error_code' => 0)));
    }

    /*     * **删除一条导入的记录**** */

    function delimportuser() {
        $idx = (int) trim($_POST['id']);
        $user_importDb = D('User_import');
        if ($user_importDb->where(array('id' => $idx))->delete()) {
            $this->dexit(array('error' => 0));
        } else {
            $this->dexit(array('error' => 1));
        }
    }

    /*     * json 格式封装函数* */

    private function dexit($data = '') {
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
        exit();
    }
	public function village_login(){
		$database_village = D('House_village');
		$condition_village['village_id'] = $_GET['village_id'];
		$now_village = $database_village->field(true)->where($condition_village)->find();
		if(empty($now_village) || $now_village['status'] == 2){
			exit('<html><head><script>window.top.toggleMenu(0);window.top.msg(0,"该小区的状态不存在！请查阅。",true,5);window.history.back();</script></head></html>');
		}

		// 是否为平台登录
		$now_village['is_system'] = 1;

		//查询权限 超级管理员赋予所有权限				
		$admin_menus = D('House_menu')->field(true)->where(array('status'=>1))->select();
		$menus = array();
		foreach ($admin_menus as $value) {
			$menus[] = $value['id'];
		}
		$now_village['menus'] = $menus;
		session('house',$now_village);
		$script_name = trim($_SERVER['SCRIPT_NAME'],'/');
		if($_GET['group_id']){
			redirect($this->config['site_url'].'/shequ.php?c=Group&a=frame_edit&group_id='.$_GET['group_id'].'&system_file='.$script_name);
		}else if($_GET['activity_id']){
			redirect($this->config['site_url'].'/shequ.php?c=Activity&a=frame_edit&id='.$_GET['activity_id'].'&system_file='.$script_name);
		}else if($_GET['appoint_id']){
			redirect($this->config['site_url'].'/shequ.php?c=Appoint&a=frame_edit&appoint_id='.$_GET['appoint_id'].'&system_file='.$script_name);
		}else{
			redirect($this->config['site_url'].'/shequ.php');
		}
	}
	
	public function market_order()
	{
		$village_id = isset($_GET['village_id']) ? intval($_GET['village_id']) : 0;
		$where = "village_id='$village_id' AND (status=2 OR status=3)";//array('village_id' => 0, 'status' => array('in', array(2,3)));
		
		$begin_time = isset($_POST['begin_time']) ? $_POST['begin_time'] : 0;
		$end_time = isset($_POST['end_time']) ? $_POST['end_time'] : 0;
		
		$this->assign(array('village_id' => $village_id, 'begin_time' => $begin_time, 'end_time' => $end_time));

		if ($begin_time && $end_time) {
			$where .= ' AND pay_time>' .  strtotime($begin_time) . ' AND pay_time<' . strtotime($end_time . '23:59:59');
		}
		$result = D("Shop_order")->get_order_list($where, "order_id DESC", 3, false);
		$this->assign($result);
		$this->display();
	}

    //不同业务对账返点
    public function merchant_order(){
        $type=I('type')?I('type'):'group';
        $village_id = I('village_id');
        $time_condition ='';
        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])&&!empty($_POST['begin_time'])&&!empty($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
            $time_condition = " (o.pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            //$condition['_string']=$time_condition;
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }

        $order_list = D('House_village_group')->get_order_list($type,$village_id,$time_condition,1);
        $this->assign($order_list);
        $this->display();
    }

    protected  function get_alias_c_name(){
        $c_name = array(
            'all'=>'所有分类',
            'sqrecharge'=>'充值',
            'withdraw'=>'提现',
            'village_pay'=>'社区缴费',
            'express'=>'快递代送',
        );

        return $c_name ;
    }

    public function village_money_export()
    {
        $villge_id = $_GET['village_id'];
        //$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';

        $type = 'income';
        $title = '';

        switch ($type) {

            case 'income':
                $title = '收入明细';
                break;


        }
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);
        // 设置当前的sheet
        $objExcel->setActiveSheetIndex(0);


        $objExcel->getActiveSheet()->setTitle($type);
        $objActSheet = $objExcel->getActiveSheet();
        $cell_income   = array('type'=>'类型','order_id'=>'订单编号', 'num'=>'数量', 'money'=>'金额','use_time'=>'对账时间','system_take'=>'平台佣金','percent'=>'佣金百分比','now_village_money'=>'当前社区余额','desc'=>'描述');
        // 开始填充头部
        $cell_name = 'cell_'.$type;
        $cell_count = count($$cell_name);
        $cell_start = 1;
        for($f='A';$f<='Z';$f++,$cell_start++){
            if($cell_start>$cell_count){
                break;
            }
            $col_char[]=$f;
        }
        $col_k=0;
        foreach($$cell_name as $key=>$v){
            $objActSheet->getColumnDimension($col_char[$col_k])->setWidth(20);
            $objActSheet->setCellValue($col_char[$col_k].'1', $v);
            $col_k++;
        }
        $i = 2;
        //if($type=='income'){
        $where['village_id']=$villge_id;
        if($_GET['order_type']&&$_GET['order_type']!='all'){
            $where['type']=$_GET['order_type'];
        }
        if($_GET['order_id']){
            $where['order_id']=$_GET['order_id'];
        }



        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));

            $time_condition=" (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $where['_string']=$time_condition;
        }
        $result = M('Village_money_list')->field('type,order_id,num,pow(-1,income+1)*money as money,use_time,desc,system_take,percent,now_village_money')->where($where)->order('use_time DESC')->select();

        //}
        $alias_name = $this->get_alias_c_name();
        foreach ($result as $row) {
            $col_k=0;
            foreach($$cell_name as $k=>$vv){
                switch($k){
                    case 'type':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $alias_name[$row[$k]].' ');
                        break;
                    case 'order_id':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'real_orderid':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'orderid':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'pay_time':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
                        break;
                    case 'use_time':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
                        break;
                    case 'desc':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    default:
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]);
                        break;
                }
                $col_k++;
            }
            if($type!='income'){
                $objActSheet->setCellValue($col_char[$cell_count-1] . $i, $row['balance_pay']+$row['coupon_price']+$row['score_deducte']+$row['payment_money']);
            }
            $i++;
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

    public function export(){
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '社区账单';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        // 设置当前的sheet
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'order_name') {
                $condition['order_name'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'phone') {
                $condition['phone'] = $_GET['keyword'] ;
            }
        }


        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['pay_time_str']=$time_condition;
            $this->assign('begin_time',$_GET['begin_time']);
            $this->assign('end_time',$_GET['end_time']);
        }

        if(isset($_GET['searchstatus'])){
            $condition['is_pay_bill'] = $_GET['searchstatus'];
        }

        $village_id = $_GET['village_id'] + 0;
        if($village_id){
            $now_village = D('House_village')->get_one($village_id);
            $condition['village_id'] = $village_id;
            $condition['paid'] = 1;
            $count = D('House_village_pay_order')->where($condition)->count();
            $length = ceil($count / 1000);


            $objActSheet = $objExcel->getActiveSheet();
            $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
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
            $objActSheet->setCellValue('K1', '对账状态');

            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);

                $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千订单');
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

                        if($value['is_pay_bill'] == 0){
                            $objActSheet->setCellValueExplicit('K' . $index, '未对账');
                        }else{
                            $objActSheet->setCellValueExplicit('K' . $index, '已对账');
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


    public function market_export(){
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '社区超市账单';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $village_id = isset($_GET['village_id']) ? intval($_GET['village_id']) : 0;
        $where = "village_id='$village_id' AND (status=2 OR status=3)";

        $begin_time = isset($_GET['begin_time']) ? $_GET['begin_time'] : 0;
        $end_time = isset($_GET['end_time']) ? $_GET['end_time'] : 0;

        if ($begin_time && $end_time) {
            $where .= ' AND pay_time>' .  strtotime($begin_time) . ' AND pay_time<' . strtotime($end_time . '23:59:59');
        }

        if($village_id){
            $now_village = D('House_village')->get_one($village_id);

            $count = D('Shop_order')->where($where)->count();
            $length = ceil($count / 1000);

            $objActSheet = $objExcel->getActiveSheet();
            $objActSheet->setCellValue('A1', '订单编号');
            $objActSheet->setCellValue('B1', '下单人');
            $objActSheet->setCellValue('C1', '电话');
            $objActSheet->setCellValue('D1', '支付时间');
            $objActSheet->setCellValue('E1', '总价');
            $objActSheet->setCellValue('F1', '订单状态');
            $objActSheet->setCellValue('G1', '支付情况');

            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);
                $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千订单');

                $objActSheet->getColumnDimension('A')->setWidth(50);
                $objActSheet->getColumnDimension('B')->setWidth(50);
                $objActSheet->getColumnDimension('C')->setWidth(50);
                $objActSheet->getColumnDimension('D')->setWidth(50);
                $objActSheet->getColumnDimension('E')->setWidth(50);
                $objActSheet->getColumnDimension('F')->setWidth(50);
                $objActSheet->getColumnDimension('G')->setWidth(50);

                $result = D("Shop_order")->get_order_list($where, "order_id DESC", 3, false);
                if (!empty($result['order_list'])) {
                    $index = 2;
                    foreach ($result['order_list'] as $value) {
                        $objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['username']);
                        $objActSheet->setCellValueExplicit('C' . $index,  $value['userphone']);
                        $objActSheet->setCellValueExplicit('D' . $index, date('Y-m-d H:i:s',$value['pay_time']));
                        $objActSheet->setCellValueExplicit('E' . $index, $value['price']);
                        $objActSheet->setCellValueExplicit('F' . $index, strip_tags($value['status_str']));
                        $objActSheet->setCellValueExplicit('G' . $index, $value['pay_type_str']);
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


    //社区余额  余额列表 
    public function village_withdraw(){
        if(!empty($_GET['keyword'])){
            if($_GET['searchtype'] == 'village_id'){
                $condition_village['village_id'] =$where['village_id']= $_GET['keyword'];
            }else if($_GET['searchtype'] == 'account'){
                $condition_village['account'] =$where['accounts']= array('like','%'.$_GET['keyword'].'%');
            }else if($_GET['searchtype'] == 'name'){
                $condition_village['village_name'] =$where['village_name']= array('like','%'.$_GET['keyword'].'%');
            }else if($_GET['searchtype'] == 'phone'){
                $condition_village['property_phone'] =$where['property_phone']= array('like','%'.$_GET['keyword'].'%');
            }
        }

        if(isset($_GET['withdraw_status'])){
            $searchstatus = intval($_GET['withdraw_status']);
            switch($searchstatus){
                case 1:
                    $condition_village['w.status'] = array('in','0,4'); //待体现
                    break;
            }
        }
        //$condition_village['province_id'] = $_GET['province_idss'];
        if($_GET['province_idss']  && $this->config['many_city']){
            $condition_village['province_id'] =$where['province_id']= $_GET['province_idss'];
        }
        if($_GET['city_idsss']  && $this->config['many_city']){
            $condition_village['city_id'] =$where['city_id']= $_GET['city_idss'];
        }
        if($_GET['area_id']){
            $condition_village['area_id'] =$where['area_id']= $_GET['area_id'];
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
            $condition_village[$area_index] = $this->system_session['area_id'];
        }
        $village_withdraw_list = D('Village_money_list');
        $all_money = $village_withdraw_list->get_all_village_money($where);

        $this->assign('all_money',$all_money?$all_money:0);

        if($_GET['export']){
            $this->village_withdraw_export($condition_village);exit;
        }

        $village_withdraw_list = $village_withdraw_list->get_village_withdraw_list($condition_village);

        $this->assign($village_withdraw_list);
        $this->display();
    }

    public function village_withdraw_export($condition_village){
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '小区余额记录';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);



        if($condition_village['province_id']){
            $province_id = $condition_village['province_id'];
            $where['a.area_pid'] = $condition_village['province_id'];
            unset($condition_village['province_id']);
            $where = array_merge($where,$condition_village);
            $count_merchant =M("Merchant" )->join("as m left join ".C('DB_PREFIX').'area as a ON m.city_id = a.area_id')->where($where)->count();
        }else{
            $count_merchant =M("Merchant as m" )->where($condition_village)->count();
        }



        $length = ceil($count_merchant[0]['tp_count'] / 1000);

        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);
            $objExcel->getActiveSheet()->setTitle('第' . ($i + 1) . '个一千个订单信息');
            $objActSheet = $objExcel->getActiveSheet();
            $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

            $objActSheet->setCellValue('A1', '编号');
            $objActSheet->setCellValue('B1', '小区名称');
            $objActSheet->setCellValue('C1', '联系电话');
            $objActSheet->setCellValue('D1', '最近待兑现');
            $objActSheet->setCellValue('E1', '小区余额');
            $objActSheet->setCellValue('F1', '最近提现时间');


            if($province_id){
                $mer_withdraw_list =  M('House_village')->join('as m left join '.'(SELECT  village_id,SUM(money) AS  withdraw_money,withdraw_time,status as withdraw_status FROM pigcms_village_withdraw WHERE status in (0,4)  GROUP BY village_id) w ON m.village_id = w.village_id left join '.C('DB_PREFIX').'area as a on a.area_id = m.city_id')
                    ->field('m.village_id,m.property_phone as phone,m.village_name as name,m.money,w.withdraw_time,w.withdraw_money ')
                    ->where($where)
                    ->order('m.money DESC')
                    ->group('m.village_id')
                    ->limit($i * 1000,1000)
                    ->select();
            }else{
                $mer_withdraw_list =  M('House_village')->join('as m left join '.'(SELECT  village_id,SUM(money) AS  withdraw_money,withdraw_time,status as withdraw_status FROM pigcms_village_withdraw WHERE status in (0,4)  GROUP BY village_id) w ON m.village_id = w.village_id ')
                    ->field('m.village_id,m.property_phone as phone,m.village_name as name,m.money,w.withdraw_time,w.withdraw_money ')
                    ->where($condition_village)
                    ->order('m.money DESC')
                    ->group('m.village_id')
                    ->limit($i * 1000,1000)
                    ->select();
            }
           // dump($mer_withdraw_list);die;

            if (!empty($mer_withdraw_list)) {
                $index = 1;
                foreach ($mer_withdraw_list as $value) {

                    $index++;
                    $objActSheet->setCellValueExplicit('A' . $index, $value['village_id']);
                    $objActSheet->setCellValueExplicit('B' . $index, $value['name']);
                    $objActSheet->setCellValueExplicit('C' . $index, $value['phone']);
                    $objActSheet->setCellValueExplicit('D' . $index, $value['withdraw_money']?$value['withdraw_money']/100:'');
                    $objActSheet->setCellValueExplicit('E' . $index, $value['money']);
                    $objActSheet->setCellValueExplicit('F' . $index, $value['withdraw_time'] ? date('Y-m-d H:i:s', $value['withdraw_time']) : '');
                }
            }
            sleep(2);

        }


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

    //抽成列表
    public function village_percentage(){

        if(!empty($_GET['keyword'])){
            if($_GET['searchtype'] == 'village_id'){
                $condition_village['village_id'] = $_GET['keyword'];
            }else if($_GET['searchtype'] == 'account'){
                $condition_village['account'] = array('like','%'.$_GET['keyword'].'%');
            }else if($_GET['searchtype'] == 'name'){
                $condition_village['village_name'] = array('like','%'.$_GET['keyword'].'%');
            }else if($_GET['searchtype'] == 'phone'){
                $condition_village['property_phone'] = array('like','%'.$_GET['keyword'].'%');
            }
        }

//            if ($this->system_session['area_id']) {
//                $area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
//                $condition_village[$area_index] = $this->system_session['area_id'];
//            }
        if($_GET['area_id']){
            $condition_village['area_id'] = $_GET['area_id'];
        }
        if($_GET['city_id']){
            $condition_village['city_id'] = $_GET['city_id'];
        }

        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_village['_string']=$time_condition;
            $this->assign('begin_time',$_GET['begin_time']);
            $this->assign('end_time',$_GET['end_time']);
        }
        $village_withdraw_list = D('Village_money_list');
        $all_money = $village_withdraw_list->get_all_percent_money($condition_village);
        $all_score = $village_withdraw_list->get_all_score($condition_village);
        $this->assign('all_money',$all_money);
        $this->assign('all_score',$all_score);
        $village_percentage_list = $village_withdraw_list->get_village_percentage_list($condition_village);

        $this->assign('result',$village_percentage_list);
        $this->display();
    }

    public function village_withdraw_list(){
        if(!empty($_GET['keyword'])){
            if($_GET['searchtype'] == 'village_id'){
                $condition_village['w.village_id'] = $_GET['keyword'];
            }else if($_GET['searchtype'] == 'account'){
                $condition_village['m.account'] = array('like','%'.$_GET['keyword'].'%');
            }else if($_GET['searchtype'] == 'name'){
                $condition_village['m.village_name'] = array('like','%'.$_GET['keyword'].'%');
            }else if($_GET['searchtype'] == 'phone'){
                $condition_village['m.property_phone'] = array('like','%'.$_GET['keyword'].'%');
            }
        }


        $condition_village['w.status'] = 1;
        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (w.withdraw_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_village['_string']=$time_condition;
            $this->assign('begin_time',$_GET['begin_time']);
            $this->assign('end_time',$_GET['end_time']);
        }

        import('@.ORG.system_page');
        $count = M('Village_withdraw')->join('as w LEFT JOIN '.C('DB_PREFIX').'house_village m ON m.village_id = w.village_id ')->where($condition_village)->count();

        $p = new Page($count, 20);
        $withdraw_list = M('Village_withdraw')->field('w.id,w.village_id,w.withdraw_time,w.money,m.village_name as name,m.account,m.property_phone as phone')->join('as w LEFT JOIN '.C('DB_PREFIX').'house_village m ON m.village_id = w.village_id ')->where($condition_village)->order('w.withdraw_time DESC')->limit($p->firstRow,$p->listRows)->select();

        $pagebar=$p->show();

        $all_money =  M('Village_withdraw')->join('as w LEFT JOIN '.C('DB_PREFIX').'house_village m ON m.village_id = w.village_id ')->where($condition_village)->sum('w.money');
        $this->assign('all_money',floatval($all_money/100));
        $this->assign('withdraw_list',$withdraw_list);
        $this->assign('pagebar',$pagebar);
        $this->display();
    }

    public function withdraw_info(){

        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
            $time_condition = " (withdraw_time BETWEEN ".$period[0].' AND '.$period[1].")";
            // $condition['_string']=$time_condition;
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }
        $village_id = I('village_id');
        $status = I('status');
        $village = D('House_village')->field(true)->where(array('village_id'=>$village_id))->find();
        $withdraw_list = D('Village_money_list')->get_withdraw_list($village_id,1,$status,$time_condition);

        foreach ($withdraw_list['withdraw_list'] as &$v) {
          //  if($v['status']==4){
                $tmp = M('Withdraw_list')->where(array('type'=>'village','withdraw_id'=>$v['id']))->find();

                $v['account'] = $tmp['account'];
                $v['account_detail'] = $tmp['remark'];
          //  }
        }
        $this->assign('now_village', $village);
        $this->assign('village_id', $village_id);
        $this->assign('status', $status);
        $this->assign('un_withdraw_list',$withdraw_list['withdraw_list']);
        $this->assign('pagebar',$withdraw_list['pagebar']);
        $this->display();
    }

    public function withdraw_order_info(){
        $withdraw = M('Village_withdraw')->where(array('id'=>$_GET['id']))->find();
        $now_village = M('Village')->where(array('village_id'=>$withdraw['village_id']))->find();
        $this->assign('withdraw',$withdraw);
        $this->assign('now_village',$now_village);
        $this->display();
    }

    public function village_money_list(){
        if(!empty($_POST['order_id'])){
            if(empty($_POST['order_type'])){
                $this->error_tips("没有选分类");
            }
            if($_POST['order_type']=='all'){
                $this->error("该分类下不能填写订单id");
            }else if($_POST['order_type']=='withdraw'){
                $condition['id'] = $_POST['order_id'];
            }else{
                $condition['order_id'] = $_POST['order_id'];
            }
        }


        $this->assign('order_id',$_POST['order_id']); 
        $this->assign('order_type',$_POST['order_type']);
        $_POST['order_type']!='' && $condition['type'] = $_POST['order_type'];
        if($_POST['order_type']=='all'){
            unset($condition['type']);
        }
        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])&&!empty($_POST['begin_time'])&&!empty($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
            $time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['_string']=$time_condition;
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }
        //dump($condition);die;
        $village_id = I('village_id');
        if(!$_GET['page']){
            $_SESSION['condition'] = $condition;
            $_SESSION['condition_villageid'] = $village_id;
        }else{
            $village_id =  $_SESSION['condition_villageid'];
        }

        $res = D('Village_money_list')->get_income_list($village_id,1,$condition);
        $village = D('House_village')->field(true)->where(array('village_id'=>$village_id))->find();

        $this->assign('now_village', $village);
        $this->assign('village_id', $village_id);
        $this->assign('income_list',$res['income_list']);
        $this->assign('alias_name',D('Village_money_list')->get_alias_c_name());
        $this->assign('pagebar',$res['pagebar']);
        $this->display();
    }

    public function agree_withdraw(){
        if(D('Village_money_list')->agree($_GET['village_id'],$_GET['id'])){
            $this->success('保存成功！');
        }else{
            $this->error_tips('保存失败！');
        }
    }

    public function reject_withdraw(){
        $res = D('Village_money_list')->reject($_GET['village_id'],$_GET['id']);
        if(!$res['error_code']){
            $this->success('保存成功！');
        }else{
            $this->error_tips($res['msg']);
        }
    }

    public function edit_reason(){
        if(IS_POST){
            if(empty($_POST['reason'])){
                $this->error('理由不能为空！');
            }
            $res = D('Village_money_list')->reject($_POST['village_id'],$_POST['id'],$_POST['reason']);

            if(!$res['error_code']){
                $this->success('保存成功！');
            }else{
                $this->error($res['msg']);
            }
        }else{
            $this->assign('id',$_GET['id']);
            $this->assign('village_id',$_GET['village_id']);
            $this->display();
        }

    }

    public function edit_withdraw(){
        $this->assign('id',I('id'));
        $this->assign('village_id',I('village_id'));
        $now_withdraw = M('Village_withdraw')->where(array('id'=>I('id'),'village_id'=>I('village_id')))->find();

        $this->assign('now_withdraw',$now_withdraw);
        if(IS_POST){
            if(empty($_POST['remark'])){
                $this->error('理由不能为空！');
            }
            if($_POST['money']>$now_withdraw['money']){
                $this->error('修改的金额不能大于用户提现的金额！');
            }
            $res = D('Village_money_list')->agree($_POST['village_id'],$_POST['money'],$_POST['id'],$_POST['remark'],$_POST['is_online']);
            if($_POST['is_online']){
                $res=D('House_village')->where(array('village_id'=>$_POST['village_id']))->find();
                $data['pay_type'] = 'village';
                $data['pay_id'] = $_POST['village_id'];
                $data['phone'] = $res['property_phone'];
                $data['money'] = $_POST['money'];
                $data['desc'] = '社区('.$res['village_name'].')申请提现|转账 '.(float)($_POST['money']/100).' 元';
                $data['status'] = 0;
                $data['add_time'] = time();
                M('Companypay')->add($data);
            }
            if(!$res['error_code']){
                $this->success('保存成功！');
            }else{
                $this->error($res['msg']);
            }
        }else{
            $this->display();
        }

    }

    protected  function get_alias_name(){
        $c_name = array(
            'all'=>'选择分类',
            'withdraw'=>'提现',
            'sqrecharge'=>'充值',
            'village_pay'=>'社区缴费',
            'express'=>'快递代送',
            'village_pay_cashier'=>'社区收银台缴费',
        );

        return $c_name ;
    }

    //提现导出excel
    public function export_withdraw(){

        if(!empty($_GET['keyword'])){
            if($_GET['searchtype'] == 'village_id'){
                $condition_village['w.village_id'] = $_GET['keyword'];
            }else if($_GET['searchtype'] == 'account'){
                $condition_village['m.account'] = array('like','%'.$_GET['keyword'].'%');
            }else if($_GET['searchtype'] == 'name'){
                $condition_village['w.name'] = array('like','%'.$_GET['keyword'].'%');
            }else if($_GET['searchtype'] == 'phone'){
                $condition_village['m.phone'] = array('like','%'.$_GET['keyword'].'%');
            }
        }
        $condition_village['w.status'] = 1;
        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (w.withdraw_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_village['_string']=$time_condition;
            $this->assign('begin_time',$_GET['begin_time']);
            $this->assign('end_time',$_GET['end_time']);
        }

        $count = M('Village_withdraw')->join('as w LEFT JOIN '.C('DB_PREFIX').'house_village m ON m.village_id = w.village_id ')->where($condition_village)->order('w.withdraw_time DESC')->count();
        $withdraw_list = M('Village_withdraw')->field('w.id,w.village_id,w.withdraw_time,w.money,m.village_name as name,m.account,m.property_phone as phone')->join('as w LEFT JOIN '.C('DB_PREFIX').'house_village m ON m.village_id = w.village_id ')->where($condition_village)->order('w.withdraw_time DESC')->select();
        //dump(M()->getDbError());die;
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '导出提现数据';
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

        // 设置当前的sheet
        $length = ceil($count / 1000);
        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个提现信息');
            $objActSheet = $objExcel->getActiveSheet();

            $objActSheet->setCellValue('A1', '社区编号');
            $objActSheet->setCellValue('B1', '社区名称');
            $objActSheet->setCellValue('C1', '联系电话');
            $objActSheet->setCellValue('D1', '提现金额');
            $objActSheet->setCellValue('E1', '提现时间');

            if (!empty($withdraw_list)) {
                $index = 2;
                foreach ($withdraw_list as $value) {
                    $objActSheet->setCellValueExplicit('A' . $index, $value['village_id']);
                    $objActSheet->setCellValueExplicit('B' . $index, $value['name']);
                    $objActSheet->setCellValueExplicit('C' . $index, $value['phone'] . ' ');
                    $objActSheet->setCellValueExplicit('D' . $index, floatval($value['money']/100) . ' ');
                    $objActSheet->setCellValueExplicit('E' . $index, $value['withdraw_time'] ? date('Y-m-d H:i:s', $value['withdraw_time']) : '');
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


    /*
	 * 开房门广告
	 * */

    public function open_door_adver(){
        import('@.ORG.system_page');
        $count = M('House_open_door_adver')->count();
        $p = new Page($count,20);
        $adver_list = M('House_open_door_adver')->limit($p->firstRow,$p->listRows)->select();
        $this->assign('adver_list',$adver_list);
        $this->display();
    }

    public function open_door_adver_add(){
        if(IS_POST){

            //if(empty($_POST['name']) || empty($_POST['ios_pic_s']) || empty($_POST['ios_pic_b']) || empty($_POST['android_pic']) || empty($_POST['url'])|| empty($_POST['begin_time'])|| empty($_POST['end_time'])|| empty($_POST['play_time'])){
            if(empty($_POST['name']) || empty($_POST['android_pic']) || empty($_POST['url'])){
                $this->frame_submit_tips(0,'数据不全');
            }
            //if($_POST['play_time']<3 || $_POST['play_time']>9){
            //    $this->frame_submit_tips(0,"播放时间请设置3至9秒的范围");
            //}
            //$_POST['begin_time'] =strtotime($_POST['begin_time']." 00:00:00");
            //$_POST['end_time'] = strtotime($_POST['end_time']." 23:59:59");
            //if ($_POST['begin_time']>$_POST['end_time']) {
            //    $this->frame_submit_tips(0,"结束时间应大于开始时间");
            //}
            $_POST['add_time'] = $_SERVER['REQUEST_TIME'];
            if(M('House_open_door_adver')->add($_POST)){
                $this->frame_submit_tips(1,'添加成功');
            }else{
                $this->frame_submit_tips(0,'添加失败');
            }
        }else{
            $this->display();
        }
    }

    public function open_door_adver_edit(){
        if(IS_POST){
            //if(empty($_POST['name']) || empty($_POST['ios_pic_s']) || empty($_POST['ios_pic_b']) || empty($_POST['android_pic']) || empty($_POST['url']) || empty($_POST['begin_time'])|| empty($_POST['end_time'])|| empty($_POST['play_time'])){
            if(empty($_POST['name']) || empty($_POST['android_pic']) || empty($_POST['url']) ){
                $this->error('数据不全');
            }
            //$_POST['begin_time'] =strtotime($_POST['begin_time']." 00:00:00");
            //$_POST['end_time'] = strtotime($_POST['end_time']." 23:59:59");
            //if ($_POST['begin_time']>$_POST['end_time']) {
            //    $this->frame_submit_tips(0,"结束时间应大于开始时间");
            //}
            $_POST['add_time'] = $_SERVER['REQUEST_TIME'];
            if(M('House_open_door_adver')->where(array('id'=>$_POST['id']))->save($_POST)){
                $this->frame_submit_tips(1,'编辑成功');
            }else{

                $this->frame_submit_tips(0,'编辑失败');
            }
        }else{
            $now_adver  =M('House_open_door_adver')->where(array('id'=>$_GET['id']))->find();

            $condition_house_village['village_id'] = $now_adver['village_id'];
            $village_info =  M('House_village')->where($condition_house_village)->find();

            $this->assign('village_info',$village_info);
            $this->assign('now_adver',$now_adver);
            $this->display();
        }
    }

    public function open_door_adver_del(){
        if(IS_POST){
            $database_adver_category  = D('House_open_door_adver');
            $condition_adver_category['id'] = $_POST['id'];
            if($database_adver_category->where($condition_adver_category)->delete()){
                $this->success('删除成功');
            }else{
                $this->frame_submit_tips(0,'删除失败！请重试~');
            }
        }else{
            $this->frame_submit_tips(0,'非法提交,请重新提交~');
        }
    }

    public function open_door_adv_village(){
        $adver_id =$where['adver_id']= $_GET['adver_id'];
        $where['status'] = 1;
        $adver_list = M('Adver_village')->where($where)->getField('village_id,status');
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'village_id') {
                $condition_house_village['village_id'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'village_name') {
                $condition_house_village['village_name'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'property_name') {
                $condition_house_village['property_name'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'property_phone') {
                $condition_house_village['property_phone'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'account') {
                $condition_house_village['account'] = $_GET['keyword'];
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
            $this->assign('admin_area',$now_area['area_type']);
            $condition_house_village[$area_index] = $this->system_session['area_id'];
        }

        $database_house_village = D('House_village');
        $count_village = $database_house_village->where($condition_house_village)->count();
        import('@.ORG.system_page');
        $p = new Page($count_village, 20);
        $village_list = $database_house_village->field(true)->where($condition_house_village)->order('`village_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign('village_list', $village_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

        $this->assign('adver_list',$adver_list);
        $this->display();
    }

    public function ajax_change_adver_village(){
        $where['adver_id'] =$data['adver_id']= $_POST['adver_id'];
        $where['village_id']=$data['village_id'] = $_POST['village_id'];
        $status =$data['status']= $_POST['status'];
        if( M('Adver_village')->where($where)->find()){
            M('Adver_village')->where($where)->setField('status',$status);
        }else{
            M('Adver_village')->add($data);
        }
    }

    public function ajax_change_adver_village_all(){

        $where['adver_id'] =$data['adver_id']= $_POST['adver_id'];
        $status =$data['status']= $_POST['status'];
        $_POST['village_id'] && $condition['village_id']  =array('in',$_POST['village_id']);
        $database_house_village = D('House_village');
        $village_list = $database_house_village->where($condition)->field(true)->select();
        foreach ($village_list as $item) {
            $where['village_id'] = $item['village_id'];
            if( M('Adver_village')->where($where)->find()){
                M('Adver_village')->where($where)->setField('status',$status);
            }else{
                $data['village_id'] = $item['village_id'];
                M('Adver_village')->add($data);
            }
        }


    }

    public function sms_buy_order(){
        import('@.ORG.system_page');
        if ($this->system_session['open_admin_area'] == 1) {
            $now_area = D('Area')->field(true)->where(array('area_id' => $this->system_session['area_id']))->find();
            if($now_area['area_type']==3){
                $area_index = 'area_id';
            }elseif($now_area['area_type']==2){
                $area_index = 'city_id';
            }elseif($now_area['area_type']==1){
                $area_index = 'province_id';
            }

            $count = D('')->table(array(C('DB_PREFIX').'house_village'=>'hv', C('DB_PREFIX').'sms_buy_order'=>'sbo'))->where("hv.".$area_index."= '".$this->system_session['area_id']."' AND sbo.type = 1 AND sbo.type_id = hv.village_id AND sbo.paid >= 1")->field('sbo.*,hv.village_name,hv.village_id')->order('sbo.order_id desc')->count();
            $p = new Page($count, 20);
            $sms_buy_orde_list = D('')->table(array(C('DB_PREFIX').'house_village'=>'hv', C('DB_PREFIX').'sms_buy_order'=>'sbo'))->where("hv.".$area_index."= '".$this->system_session['area_id']."' AND sbo.type = 1 AND sbo.type_id = hv.village_id AND sbo.paid >= 1")->field('sbo.*,hv.village_name,hv.village_id')->order('sbo.order_id desc')->select();
        }else{

            $condition_where['paid'] = array('egt',1);
            $count = D('Sms_buy_order')->where($condition_where)->count();
            $p = new Page($count, 20);

            $sms_buy_orde_list = D('')->table(array(C('DB_PREFIX').'house_village'=>'hv', C('DB_PREFIX').'sms_buy_order'=>'sbo'))->where("sbo.type = 1 AND sbo.type_id = hv.village_id AND sbo.paid >= 1")->field('sbo.*,hv.village_name,hv.village_id')->order('sbo.order_id desc')->select();
        }
        $this->assign('sms_buy_orde_list', $sms_buy_orde_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }

    //开门监控 基于百度地图
    public function door_notice_map(){
        $database_house_village = D('House_village');
        $condition['status'] =1;
        $village_list = $database_house_village->field('village_id,village_name,village_address,property_name,long,lat,property_phone,wx_image')->where($condition)->order('`village_id` DESC')->select();
//        $open_history  = M('House_village_open_door')->join('h LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = h.uid')->field('h.* ,u.nickname,u.avatar,u.phone')->where(array('open_status'=>array('gt',0)))->limit(30)->order('add_time DESC')->select();
        $door_list = M('House_village_door')->getField('door_device_id,door_id,door_name,village_id');

        $this->assign('village_list_json',json_encode($village_list));
        $this->assign('village_list',$village_list);
//        $this->assign('open_history',$open_history);
        $this->assign('open_status',$this->open_door_status);
        $this->assign('door_list',$door_list);

        $this->display('desk');
    }

    public function search_village()
    {
        if (!empty($_POST['keyword'])) {
            $condition_house_village['village_name'] = array('like', '%' . $_POST['keyword'] . '%');
        }
        $condition_house_village['status'] =1;
        $database_house_village = D('House_village');
        $village_list = $database_house_village->field(true)->where($condition_house_village)->order('`village_id` DESC')->select();

        echo json_encode(array('village_list'=>$village_list));
    }



    public function get_village_name(){
        $village_name=$_POST['village_name'];
        $condition_house_village['village_name'] =$village_name;
        $village_info =  M('House_village')->where($condition_house_village)->find();
        echo json_encode(array('village'=>$village_info));
    }

    //查询开门情况
    public function get_house_open_status(){
        $village_list = M('House_village')->where(array('status'=>1))->field(true)->select();
        $start_time = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME']));
        $end_time = $start_time+86400;
        $where['_string'] = "add_time>{$start_time} AND add_time";
        foreach ($village_list as $item) {
            $where['village_id'] = $item['village_id'];
            $res  = M('House_village_open_door')->where($where)->field('open_status,COUNT(open_status) as counts')->group('open_status')->select();
            $all_count=0;
            $success_count=0;
            $tmp=[];
            foreach ($res as $re) {
                $re['status_txt'] = $this->open_door_status[$re['open_status']];

                $all_count += $re['counts'];
                $tmp[$re['open_status']] = $re;
            }

            $success_count = $tmp[0]['counts'];

            $arr[]  =array(
                'village_id'=>$item['village_id'],
                'status'=>$tmp,
                'all_count'=>intval($all_count),
                'success_count'=>intval($success_count),
                'fail_count'=>$all_count-$success_count,
                'now_lng'=>$item['long'],
                'now_lat'=>$item['lat'],
                'village_name'=>$item['village_name'],
                'village_phone'=>$item['property_phone'],
            );
        }

        echo json_encode(array('errcode'=>0,'data'=>$arr));
    }

    public function notice_village_manager(){
        echo json_encode(array('errcode'=>0));
    }

    public function check_fail_door(){
        $last_id = $_POST['new_id'];
        if($last_id){
            $where = array('h.id'=>array('gt',$last_id));
        }
        $fail_door  = M('House_village_open_door')->join('h LEFT JOIN '.C('DB_PREFIX').'user u ON u.uid = h.uid')->field('h.* ,u.nickname,u.avatar,u.phone')->where($where)->limit(10)->order('add_time DESC')->select();
        $door_list = M('House_village_door')->getField('door_device_id,door_id,door_name');
        $condition['status']=1;
        $village_list = M('House_village')->where($condition)->getField('village_id,village_name,village_address,property_name,long,lat,property_phone');
        foreach ($fail_door as &$item) {
            $item['add_time'] = date('Y-m-d H:m:s');
            $item['status_txt'] = $this->open_door_status[$item['open_status']];
            $item['door_name'] = $village_list[$item['village_id']]['village_name'].'('.($door_list[$item['door_id']]?$door_list[$item['door_id']]:'未知').')';
            $item['nickname'] = ($item['nickname']?$item['nickname'].' '.$item['phone']:$item['phone']);
        }
        echo json_encode(array('fail_door'=>$fail_door));

    }

    //查看用户开门情况
    public function get_user_open_status(){
        $uid=$_POST['uid']  = 1616;
        $id=$_POST['id'] = 6319;
        $open_status = M('House_village_open_door')->where(array('id'=>$id))->find();
        $now_village = M('House_village')->field('village_id,village_name')->where(array('village_id'=>$open_status['village_id']))->find();
        $door_list = D('House_village_door')->get_user_door($uid,$now_village['village_id']);
        $arr['now_village'] = $now_village;

        $arr['success_count'] = M('House_village_open_door')->where(array('open_status'=>0,'uid'=>$uid,'village_id'=>$now_village['village_id']))->count();
        $arr['fail_count'] = M('House_village_open_door')->where(array('open_status'=>array('gt',0),'uid'=>$uid,'village_id'=>$now_village['village_id']))->count();
        $arr['all_count'] =   $arr['success_count'] +  $arr['fail_count'] ;
        $arr['success_percent'] =   $arr['all_count']==0?0:round($arr['all_count']/$arr['all_count']*100,2);
        foreach ($door_list as &$item) {
            $item['success_count'] = M('House_village_open_door')->where(array('open_status'=>0,'uid'=>$uid,'door_device_id'=>$item['door_device_id']))->count();
            $item['fail_count'] = M('House_village_open_door')->where(array('open_status'=>array('gt',0),'uid'=>$uid,'door_device_id'=>$item['door_device_id']))->count();
        }
        $arr['door_list'] = $door_list;
        echo json_encode($arr);
    }


}
