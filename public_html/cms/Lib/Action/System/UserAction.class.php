<?php

/*
 * 用户中心
 *
 */

class UserAction extends BaseAction {
    public function index() {
        //搜索
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $condition_user['uid'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'nickname') {
                $condition_user['nickname'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'phone') {
                $condition_user['phone'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }


        $condition_user['openid'] = array('notlike','%no_use');
		//排序
		$order_string = '`uid` DESC';
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
        $levelDb = M('User_level');
        $tmparr = $levelDb->field(true)->order('id ASC')->select();
        $levelarr = array();
        if ($tmparr) {
            foreach ($tmparr as $vv) {
                $levelarr[$vv['level']] = $vv;
            }
        }

		//状态
        $condition_user['status'] = array('neq',4);
        if ($_GET['status'] != '') {
        	$condition_user['status']	=	$_GET['status'];
        }
        if($_GET['level']>0){
            $condition_user['level']	=	$_GET['level'];
        }

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_user['_string'] =" (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $database_user = D('User');
        $count_user = $database_user->where($condition_user)->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 30);
        $user_list = $database_user->field(true)->where($condition_user)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();

        if (!empty($user_list)) {
            import('ORG.Net.IpLocation');
            $IpLocation = new IpLocation();
            foreach ($user_list as &$value) {
                $last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
                $value['last_ip_txt'] = mb_convert_encoding($last_location['country'],'UTF-8','GBK');
            }
        }
        $this->assign('user_list', $user_list);
        $user_balance	=	array(
			'count'	=>	$database_user->sum('now_money'),
			'open'	=>	$database_user->where(array('status'=>1))->sum('now_money'),
			'close'	=>	$database_user->where(array('status'=>0))->sum('now_money'),
			'score'	=>	$database_user->where(array('status'=>1))->sum('score_count'),
        );
        $this->assign('levelarr', $levelarr);
        $this->assign('user_balance', $user_balance);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('client', array(0=>'WAP端',1=>'苹果',2=>'安卓',3=>'电脑',4=>'小程序',5=>'微信',6=>'支付宝','7'=>'微信扫描商家二维码'));
        $this->display();
    }

    public function spread_user(){
       // dump($_GET);
        $uid = $_GET['uid'];
        $openid = $_GET['openid'];
        if(empty($_GET['from_uid'])){
            $_GET['from_uid'] = $_GET['uid'];
        }
        !isset($_GET['lvl']) && $_GET['lvl']=0;
        $_GET['lvl']+=1;
        if($_GET['lvl']>3){
            $this->error_tips('平台最多支持3级分佣！');
        }
        $now_user = D('User')->get_user($_GET['from_uid']);
        $user_list = D('User_spread')->get_spread_user($openid,$uid);
        if (!empty($user_list)) {
            import('ORG.Net.IpLocation');
            $IpLocation = new IpLocation();
            foreach ($user_list['spread_user_list'] as $key=>&$value) {
                $last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
                $value['last_ip_txt'] = iconv('GBK', 'UTF-8', $last_location['country']);
                if($value['openid']==''){
                    unset($user_list['spread_user_list'][$key]);
                }
            }
        }

        $this->assign('user_list', $user_list['spread_user_list']);
        $this->assign('client', array(0=>'WAP端',1=>'苹果',2=>'安卓',3=>'电脑/暂时未知',4=>'小程序',5=>'微信'));
        $this->assign('now_user', $now_user);
        $this->display();
    }

    public function edit() {
        $this->assign('bg_color', '#F3F3F3');
        if($this->system_session['level']!=2&&!in_array(198,$this->system_session['menus']) || $this->config['open_allinyun']==1){
            $can_recharge = 0;
        }else{
            $can_recharge = 1;
        }
        $this->assign('can_recharge',$can_recharge);

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
		if(!empty($now_user['cardid'])){
			$balance_money = D('Physical_card')->where(array('cardid'=>$now_user['cardid']))->getField('balance_money');
			$this->assign('balance_money',$balance_money);
		}
        if($now_user['free_time']>$_SERVER['REQUEST_TIME']){
            $now_user['frozen_time'] = $now_user['frozen_time']>0?date('Y-m-d',$now_user['frozen_time']):0;
            $now_user['free_time'] = $now_user['free_time']>0?date('Y-m-d',$now_user['free_time']):0;
        }else{
            $now_user['frozen_money'] = 0;
            $now_user['frozen_time'] = 0;
            $now_user['frozen_reason'] = '';
            $now_user['free_time'] = 0;
        }
        $now_user['spread'] = 0;
        if($now_user['openid'] ){
            if($res = M('Merchant_spread')->where(array('openid'=>$now_user['openid']))->find()){
                $now_user['spread'] = 2;
                $now_user['spread_txt'] = '商家'.$res['mer_id'].'的推广用户';

            }

            $condition_where['_string'] = "(openid=".$now_user['openid']." AND openid<> '') OR uid =".$now_user['uid'];
            if($res = M('User_spread')->where($condition_where)->find()){
                $now_user['spread'] = 1;
                $now_user['spread_txt'] = '用户'.$res['spread_uid'].'的推广用户';
            }
        }
        $this->assign('levelarr', $levelarr);
        $this->assign('now_user', $now_user);

        $this->display();
    }

    //管理员充值导出
    public function admin_recharge_export(){
        $param = $_POST;
        $param['type'] = 'admin_recharge';
        $param['rand_number'] = $_SERVER['REQUEST_TIME'];
        // $param['system_session']['area_id'] = $this->system_session['area_id'] ;

        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
    }
	public function export(){

        $param = $_POST;
        $param['type'] = 'user';
        $param['rand_number'] = $_SERVER['REQUEST_TIME'];
      // $param['system_session']['area_id'] = $this->system_session['area_id'] ;
        
        if($res = D('Order')->order_export($param)){
            echo json_encode(array('error_code'=>0,'msg'=>'添加导出计划成功','file_name'=>$res['file_name'],'export_id'=>$res['export_id'],'rand_number'=> $param['rand_number']));
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'导出失败'));
        }
        die;
		set_time_limit(0);
		import('@.ORG.csv');
		$csv = new csv();
		
		import('ORG.Net.IpLocation');
		$IpLocation = new IpLocation();
				
		$header_data = array('用户ID','昵称','真实姓名','手机号','性别 (男； 女； 其他)','省份','城市','QQ','注册时间','注册IP','最后登录时间','最后登录IP',$this->config['score_name'],'余额','不可提现的余额','是否手机认证','是否关注公众号','账号是否正常');
		
		$data = array();
		$user_list = D('User')->field(true)->select();
		foreach($user_list as $value){
			$last_location = $IpLocation->getlocation(long2ip($value['add_ip']));
			$add_ip = iconv('GBK', 'UTF-8', $last_location['country']);
			
			$last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
			$last_ip = iconv('GBK', 'UTF-8', $last_location['country']);
			
			$data[] = array(
				'uid'      => $value['uid'],
				'nickname' => $value['nickname'],
				'truename' => $value['truename'],
				'phone'    => $value['phone'].' ',
				'sex'      => $value['sex'] == 0 ? '未知' : ($value['sex'] == 1 ? '男' : '女'),
				'province' => $value['province'],
				'city'     => $value['city'],
				'qq'       => $value['qq'].' ',
				'add_time' => date('Y-m-d H:i:s', $value['add_time']),
				'add_ip'   => $add_ip,
				'last_time'=> date('Y-m-d H:i:s', $value['last_time']),
				'last_ip'  => $last_ip,
				'score_count'  => $value['score_count'].' ',
				'now_money'  => $value['now_money'] . ' ',
				'score_recharge_money'  => $value['score_recharge_money'] . ' ',
				'is_check_phone'  => $value['is_check_phone'] == 0 ? '否' : '是',
				'is_follow'  => $value['is_follow'] ? '是' : '否',
				'status'  => $value['status'] ? '正常' : '禁用',
			);
		}
		
		$csv->export_csv_2($data,$header_data,'平台用户信息.csv');
	}
	
    public function export1() {
    	set_time_limit(0);
    	require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
    	$title = '平台用户信息';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		// 设置当前的sheet

		$database_user = D('User');
		$count_user = $database_user->count();

		$length = ceil($count_user/1000);
		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);

			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个用户');
			$objActSheet = $objExcel->getActiveSheet();

			$objActSheet->setCellValue('A1', '用户ID');
			$objActSheet->setCellValue('B1', '昵称');
			$objActSheet->setCellValue('C1', '真实姓名');
			$objActSheet->setCellValue('D1', '手机号');
			$objActSheet->setCellValue('E1', '性别 (男； 女； 其他)');
			$objActSheet->setCellValue('F1', '省份');
			$objActSheet->setCellValue('G1', '城市');
			$objActSheet->setCellValue('H1', 'QQ');
			$objActSheet->setCellValue('I1', '注册时间');
			$objActSheet->setCellValue('J1', '注册IP');
			$objActSheet->setCellValue('K1', '最后登录时间');
			$objActSheet->setCellValue('L1', '最后登录IP');
			$objActSheet->setCellValue('M1', $this->config['score_name']);
			$objActSheet->setCellValue('N1', '余额');
			$objActSheet->setCellValue('O1', '不可提现的余额');
			$objActSheet->setCellValue('P1', '是否手机认证');
			$objActSheet->setCellValue('Q1', '是否关注公众号');
			$objActSheet->setCellValue('R1', '账号是否正常');




			$user_list = $database_user->field(true)->limit($i * 1000 . ',1000')->select();
			if (!empty($user_list)) {
				import('ORG.Net.IpLocation');
				$IpLocation = new IpLocation();
				$index = 2;
				foreach ($user_list as $value) {

					$objActSheet->setCellValueExplicit('A' . $index, $value['uid']);
					$objActSheet->setCellValueExplicit('B' . $index, $value['nickname']);
					$objActSheet->setCellValueExplicit('C' . $index, $value['truename']);
					$objActSheet->setCellValueExplicit('D' . $index, $value['phone'] . ' ');
					$sex = $value['sex'] == 0 ? '未知' : ($value['sex'] == 1 ? '男' : '女');
					$objActSheet->setCellValueExplicit('E' . $index, $sex);

					$objActSheet->setCellValueExplicit('F' . $index, $value['province']);
					$objActSheet->setCellValueExplicit('G' . $index, $value['city']);
					$objActSheet->setCellValueExplicit('H' . $index, $value['qq'] . ' ');
					$objActSheet->setCellValueExplicit('I' . $index, date('Y-m-d H:i:s', $value['add_time']));

					$last_location = $IpLocation->getlocation(long2ip($value['add_ip']));
					$add_ip = iconv('GBK', 'UTF-8', $last_location['country']);
					$objActSheet->setCellValueExplicit('J' . $index, $add_ip);

					$objActSheet->setCellValueExplicit('K' . $index, date('Y-m-d H:i:s', $value['last_time']));

					$last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
					$last_ip = iconv('GBK', 'UTF-8', $last_location['country']);
					$objActSheet->setCellValueExplicit('L' . $index, $last_ip);

					$objActSheet->setCellValueExplicit('M' . $index, $value['score_count'] . ' ');
					$objActSheet->setCellValueExplicit('N' . $index, $value['now_money'] . ' ');
					$objActSheet->setCellValueExplicit('O' . $index, $value['score_recharge_moeny'] . ' ');
					$is_check_phone = $value['is_check_phone'] == 0 ? '否' : '是';
					$objActSheet->setCellValueExplicit('P' . $index, $is_check_phone);
					$is_follow = $value['is_follow'] ? '是' : '否';
					$objActSheet->setCellValueExplicit('Q' . $index, $is_follow);
					$status = $value['status'] ? '正常' : '禁用';
					$objActSheet->setCellValueExplicit('R' . $index, $status);

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
            $data_user['frozen_money'] = $_POST['frozen_money'];
            $data_user['frozen_reason'] = trim($_POST['frozen_reason']);
            $data_user['phone_country_type'] = trim($_POST['phone_country_type']);
            if($_POST['frozen_money']>0 && $this->config['open_frozen_money']==1){
                if(empty($_POST['frozen_time'])||empty($_POST['free_time'])){
                    $this->error("设置冻结时间必须设置【冻结时间】");
                }
                if($now_user['now_money']<$_POST['frozen_money']){
                    $this->error("冻结金额不能比当前金额大");
                }

                if(empty($_POST['frozen_reason'])){
                    $this->error("设置冻结时间必须设置【冻结理由】");
                }
            }
            if(!empty($_POST['frozen_time'])&&!empty($_POST['free_time'])){

                if ($_POST['frozen_time']>$_POST['free_time']) {
                    $this->error("结束时间应大于开始时间");
                }
                $data_user['frozen_time'] = strtotime($_POST['frozen_time']." 00:00:00");
                $data_user['free_time'] = strtotime($_POST['free_time']." 23:59:59");
            }

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
                if($_POST['money_remark']==''){
                    $this->error('修改余额请填写备注');
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
                if($_POST['score_remark']==''){
                    $this->error('修改积分请填写备注');
                }
            }

			$cardid = $_POST['cardid'];
			$data_user['cardid'] = $cardid;
			$card = M('Physical_card');
			if(!empty($cardid)){

				$condition_card['cardid']=$cardid;
				$res = $card->where($condition_card)->getField('cardid,uid,status');
				if(empty($res)&&!empty($res[$cardid]['uid'])&&$res[$cardid]['status']!=0&&!empty($now_user['cardid'])){
					$this->error('实体卡ID不存在,或者实体卡已绑定用户，请检查');
				}else{
					$card_data['uid'] = $now_user['uid'];
					$card_data['status'] = 1;
					$card_data['regtime'] = time();
					$card_data['last_time'] = time();
					$card_data['balance_money'] = $_POST['balance_money'];
					if(!$card->where($condition_card)->save($card_data)){
						$this->error('保存实体卡是失败');
					}
				}
			}else{
				$card->where(array('uid'=>$now_user['uid']))->save(array('uid'=>NULL,'regtime'=>NULL,'last_time'=>time()));
			}


            $data_user['level'] = intval($_POST['level']);
            $data_user['forzen_score'] =$_POST['forzen_score'];
            if ($database_user->where($condition_user)->data($data_user)->save()) {
                if (!empty($_POST['set_money'])) {
//                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
//
//                    $model->sendTempMsg('TM00356',
//                        array('href' => C('config.site_url').'/wap.php?c=My&a=transaction',
//                            'wecha_id' => $now_user['openid'],
//                            'first' => $now_user['nickname'].'您好！',
//                            'work' => '平台已为您的账户手动'.($_POST['set_money']>0?'增加':'减少').'余额'.abs($_POST['set_money']).'元',
//                            'remark' => date('Y年m月d日 H:i:s')
//                        )
//                    );

                    if($now_user['openid']) {
                        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                        $href = C('config.site_url') . '/wap.php?c=My&a=transaction';
                        $model->sendTempMsg('OPENTM401833445', array('href' => $href,
                            'wecha_id' => $now_user['openid'],
                            'first' => '尊敬的' . $now_user['nickname'] . ',您的平台余额账户发生变动',
                            'keyword1' => date('Y-m-d H:i'),
                            'keyword2' => '管理员手动'.($_POST['set_money']>0?'充值':'减少').'平台余额（备注：'.$_POST['money_remark'].'）',
                            'keyword3' => $_POST['set_money'],
                            'keyword4' => $data_user['now_money'],
                            'remark' => '详情请点击此消息进入会员中心-余额记录进行查询!'),
                            0);
                    }

                    if (C('config.sms_place_order') == 1 || C('config.sms_place_order') == 3) {
                        $sms_data['uid'] = $now_user['uid'];
                        $sms_data['mobile'] = $now_user['phone'];
                        $sms_data['sendto'] = 'user';
                        $sms_data['content'] = "平台在".date('Y-m-d H:i:s')."为您账户手动".($_POST['set_money']>0?'增加':'减少').'余额'.abs($_POST['set_money'])."元，请及时查看";
                        Sms::sendSms($sms_data);
                    }

                    if(C('config.zbw_key')){

                        if($now_user['zbw_cardid']){
                            if($_POST['set_money_type']==1){
                                $result = D('ZbwErp')->VipFullAmt($now_user,$_POST['set_money'],'管理员充值');
                            }else{
                                $result = D('ZbwErp')->VipPaySheet($now_user,-1*$_POST['set_money'],'管理员减少');
                            }
                    
                            D('ZbwErp')->sync_data($now_user['uid']);
                        }
                    }else{

                        D('User_money_list')->add_row($now_user['uid'], $_POST['set_money_type'], $_POST['set_money'], '管理员后台操作'.(empty($_POST['money_remark'])?'':',备注：'.$_POST['money_remark']), false,0,1,true);
                    }

                }
                if (!empty($_POST['set_score'])) {

                     if(C('config.zbw_key')){

                         if($now_user['zbw_cardid']){
                             $result = D('ZbwErp')->VipSaleSheet($now_user,($_POST['set_score_type']==1?$_POST['set_score']:-1*$_POST['set_score']),'管理员操作积分');
                             D('ZbwErp')->sync_data($now_user['uid']);
                         }
                     }else {

                         D('User_score_list')->add_row($now_user['uid'], $_POST['set_score_type'], $_POST['set_score'], '管理员后台操作' . (empty($_POST['score_remark']) ? '' : ',备注：' . $_POST['score_remark']), false, 0, 0, true);
                     }
                }
                $this->success('修改成功！');
            } else {
                $this->error('修改失败！请重试。');
            }
        } else {
            $this->error('非法访问！');
        }
    }

    public function money_list() {
        $this->assign('bg_color', '#F3F3F3');
        $database_user_money_list = D('User_money_list');
        $condition_user_money_list['uid'] = intval($_GET['uid']);
        if($_GET['ask']){
			$condition_user_money_list['ask'] = intval($_GET['ask']);
        }
		if($_GET['ask_id']){
			$condition_user_money_list['ask_id'] = intval($_GET['ask_id']);
        }
        $count = $database_user_money_list->where($condition_user_money_list)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 15);
        $condition_user_money_list['_string'] = "`desc` not like '%余额记录减扣%' AND  `desc` not like '%不可提现%' ";
        $money_list = $database_user_money_list->field(true)->where($condition_user_money_list)->order('`time` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

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

        $score_list = $database_user_score_list->field(true)->where($condition_user_score_list)->order('`time` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

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
                        !empty($vv['D']) && $tmpdata['mer_id'] = intval(trim($vv['D']));
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
        $tmparr = $levelDb->order('level DESC')->find();
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

            $use_money = intval($_POST['use_money']);
            if (!($use_money >= 0))
                $this->error('等级金额没有填写！');
            $newdata['use_money'] = $use_money;

            $newdata['icon'] = trim($_POST['icon']);
            $newdata['validity'] = trim($_POST['validity']);
            $newdata['type'] = trim($_POST['fltype']);
            $newdata['boon'] = trim($_POST['boon']);
            $newdata['description'] = trim($_POST['description']);
            $newdata['spread_user_give_score'] = empty($_POST['spread_user_give_score'])?0:$_POST['spread_user_give_score'];
            $newdata['spread_user_give_moeny'] =  empty($_POST['spread_user_give_moeny'])?0:$_POST['spread_user_give_moeny'];
            $newdata['score_clean_time'] =empty($_POST['score_clean_time'])?'':$_POST['score_clean_time'];
            $newdata['score_clean_percent'] =empty($_POST['score_clean_percent'])?0:$_POST['score_clean_percent'];

            if ($lid > 0) {
                $inser_id = $levelDb->where(array('id' => $lid))->save($newdata);
            } else {
                $inser_id = $levelDb->add($newdata);
            }
            if ($inser_id) {

                //更新 店铺 快店 团
                if($this->config['discount_sync']==1){
                    $levelDb = M('User_level');
                    $tmparr = $levelDb->order('id ASC')->select();
                    $newleveloff = [];
                    foreach ($tmparr as $kk => $vv) {
                        $vl['type'] = intval($vv['type']);
                        $vl['vv'] = intval($vv['boon']);
                        if (($vl['type'] > 0) && ($vl['vv'] > 0)) {
                            $vl['level'] = $vv['level'];
                            $vl['lname'] = $vv['lname'];
                            $vl['lid'] = $vv['id'];
                            $newleveloff[$vv['level']] = $vl;
                        }
                    }
                    $newleveloff =  serialize($newleveloff);
                    //批量更新等级
                    M('')->execute('UPDATE '.C('DB_PREFIX')."Merchant_store_shop SET leveloff='".$newleveloff."'");
                    M('')->execute('UPDATE '.C('DB_PREFIX')."Merchant_store SET leveloff='".$newleveloff."'");
                    M('')->execute('UPDATE '.C('DB_PREFIX')."group SET leveloff='".$newleveloff."'");

                }
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

    /*     * **删除一条导入的记录**** */

    function delimportuser() {
        $idx = (int) trim($_POST['id']);
        $user_importDb = D('User_import');
        if ($user_importDb->where(array('id' => $idx))->delete()) {
        	$this->success('删除成功');
        } else {
        	$this->error('删除失败' . $this->_get('id'));
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
    //	用户实名认证列表
	public function authentication(){
		$status	=	$_GET['status'];
		if(empty($status)){
			$where['authentication_status']	=	0;
			$order['authentication_time']	=	'desc';
        }else{
			$where['authentication_status']	=	array('neq',0);
			$order['examine_time']	=	'desc';
        }
		$card = M('User_authentication');
        $count_card = $card->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count_card, 20);
        $card_list	=	$card->field(true)->order($order)->limit($p->firstRow . ',' . $p->listRows)->where($where)->select();
        foreach($card_list as &$v){
			if(strstr($v['authentication_img'], ',')){
				$merchant_image_class = new scenic_image();
				$v['authentication_img'] = $merchant_image_class->get_image_by_path($v['authentication_img'],$this->config['site_url'],'aguide','1');
			}else{
				$v['authentication_img'] =	$this->config['site_url'].$v['authentication_img'];
			}
        }
		$pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->assign('card_list',$card_list);
		$this->assign('status',$status);
		$this->display();
	}
	//	审核用户实名认证
	public function check(){
		if(IS_POST){
			$where['authentication_id']	=	$_POST['authentication_id'];
			$data	=	array(
				'examine_remarks'	=>	$_POST['examine_remarks'],
				'examine_time'	=>	time(),
			);
			if($_POST['examine_remarks'] == 0){
				$data['authentication_status']	=	1;
				$user_data['real_name']	=	1;
			}else{
				$data['authentication_status']	=	2;
				$user_data['real_name']	=	3;
			}
			$save	=	M('User_authentication')->where($where)->data($data)->save();
			if($save){
				$user_where['uid']	=	$_POST['uid'];
				$sReal	=	M('User')->where($user_where)->data($user_data)->save();
				if($sReal){
					$this->success('审核成功');
				}else{
					$this->error('用户审核失败');
				}
			}else{
				$this->error('审核失败');
			}
		}
		$where['authentication_id']	=	$_GET['authentication_id'];
		$status	=	$_GET['status'];
		$userAuth	=	M('User_authentication')->field(true)->where($where)->find();
		$merchant_image_class = new scenic_image();
		if(strstr($userAuth['authentication_img'], ',')){
			$userAuth['authentication_img'] = $merchant_image_class->get_image_by_path($userAuth['authentication_img'],$this->config['site_url'],'aguide','1');
		}
		if(strstr($userAuth['authentication_back_img'], ',')){
			$userAuth['authentication_back_img'] = $merchant_image_class->get_image_by_path($userAuth['authentication_back_img'],$this->config['site_url'],'aguide','1');
		}
		if(strstr($userAuth['hand_authentication'], ',')){
			$userAuth['hand_authentication'] = $merchant_image_class->get_image_by_path($userAuth['hand_authentication'],$this->config['site_url'],'aguide','1');
		}
		$this->assign('userAuth',$userAuth);
		$this->assign('status',$status);
		$this->display();
	}
	# 车主认证
	public function authentication_car(){
		$status	=	$_GET['status'];
		if(empty($status)){
			$where['status']	=	0;
			$order['add_time']	=	'desc';
        }else{
			$where['status']	=	array('neq',0);
			$order['examine_time']	=	'desc';
        }
		$card = M('User_authentication_car');
        $count_card = $card->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count_card, 20);
        $card_list	=	$card->field(true)->order($order)->limit($p->firstRow . ',' . $p->listRows)->where($where)->select();
        $merchant_image_class = new scenic_image();
        foreach($card_list as &$v){
			//$v['authentication_img'] = $merchant_image_class->get_car_by_path($v['authentication_img'],$this->config['site_url'],'authentication_car','s');
			$v['authentication_img'] = $merchant_image_class->get_car_by_path($v['drivers_license'],$this->config['site_url'],'authentication_car','s');
			//$v['driving_license'] = $merchant_image_class->get_car_by_path($v['driving_license'],$this->config['site_url'],'authentication_car','s');

        }

		$pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->assign('card_list',$card_list);
		$this->assign('status',$status);
		$this->display();
	}

    #证件照片
    public function car_authority_img(){
        $where['car_id'] = $_GET['car_id'] ;
        $find = M('User_authentication_car')->where($where)->find();
        if($find){
            $image_class = new scenic_image();
            $find['authentication_img'] = $image_class->get_car_by_path($find['authentication_img'],$this->config['site_url'],'authentication_car','s');
            $find['authentication_back_img'] = $image_class->get_car_by_path($find['authentication_back_img'],$this->config['site_url'],'authentication_car','s');
            $find['drivers_license'] = $image_class->get_car_by_path($find['drivers_license'],$this->config['site_url'],'authentication_car','s');
            $find['driving_license'] = $image_class->get_car_by_path($find['driving_license'],$this->config['site_url'],'authentication_car','s');
        }
        $this->assign('userAuth',$find);

        $this->display();

    }
	# 车主审核
	public function car_check(){
		if(IS_POST){
			$where['car_id']	=	$_POST['car_id'];
			$data	=	array(
				'examine_remarks'	=>	$_POST['examine_remarks'],
				'status'		=>	$_POST['status'],
				'examine_time'	=>	time(),
			);
			$save	=	M('User_authentication_car')->where($where)->data($data)->save();
			if($save){
				$this->success('审核成功');
			}else{
				$this->error('审核失败');
			}
		}
		$where['car_id']	=	$_GET['car_id'];
		$statuss	=	$_GET['statuss'];
		$userAuth	=	M('User_authentication_car')->field(true)->where($where)->find();
		$merchant_image_class = new scenic_image();
		$userAuth['authentication_img'] = $merchant_image_class->get_car_by_path($userAuth['authentication_img'],$this->config['site_url'],'authentication_car','1');
		$userAuth['authentication_back_img'] = $merchant_image_class->get_car_by_path($userAuth['authentication_back_img'],$this->config['site_url'],'authentication_car','1');
		$userAuth['drivers_license'] = $merchant_image_class->get_car_by_path($userAuth['drivers_license'],$this->config['site_url'],'authentication_car','1');
		$userAuth['driving_license'] = $merchant_image_class->get_car_by_path($userAuth['driving_license'],$this->config['site_url'],'authentication_car','1');
		$this->assign('userAuth',$userAuth);
		$this->assign('statuss',$statuss);
		$this->display();
	}

    public function recharge_list(){

        $condition_where = "`o`.`uid`=`u`.`uid` ";
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'orderid') {
                $where['orderid'] = htmlspecialchars($_GET['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
                $condition_where .= " AND `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
            } elseif ($_GET['searchtype'] == 'name') {
                $condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where .= " AND `u`.`phone` like '%" . htmlspecialchars($_GET['keyword']) ."%'";
            } elseif ($_GET['searchtype'] == 'order_id') {
                $condition_where .= " AND `o`.`order_id`='" . htmlspecialchars($_GET['keyword']) . "'";
            }

        }

//        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
        if ($type != 'price' && $type != 'pay_time') $type = '';
        $order_sort = '';
        if ($type && $sort) {
            $order_sort .= 'o.' . $type . ' ' . $sort . ',';
            $order_sort .= 'o.order_id DESC';
        } else {
            $order_sort .= 'o.order_id DESC';
        }

        //if ($status != -1) {
            $condition_where .= " AND `o`.`paid`=1";
//        }

        $condition_table = array( C('DB_PREFIX').'user_recharge_order'=>'o', C('DB_PREFIX').'user'=>'u');
        $order_count = D('')->where($condition_where)->table($condition_table)->count();
        import('@.ORG.system_page');
        $p = new Page($order_count,30);
        $order_list = D('')->field('`o`.*,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order($order_sort)->limit($p->firstRow.','.$p->listRows)->select();
        $this->assign('order_list',$order_list);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
//        $this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status));
        $this->assign(array('type' => $type, 'sort' => $sort));
        $this->assign('status_list', D('Group_order')->status_list);
        $this->display();
    }

    /**
     * @return    充值详情
     */
    public function order_detail(){
        $this->assign('bg_color','#F3F3F3');
        $order = D('User_recharge_order');
        $condition_group_order['o.order_id'] = $_GET['order_id'];
        $order = $order->join('as o left join '.C('DB_PREFIX').'user u ON u.uid = o.uid')->where($condition_group_order)->find();

        if(empty($order)){
            $this->frame_error_tips('此订单不存在！');
        }

        $this->assign('now_order',$order);
        $this->display();
    }

    /**
     * @return    商家线下充值的记录
     */
    public function card_recharge_list(){
        $this->assign(D('Card_new')->offline_recharge_list(1));
        $this->display();
    }

    /**
     * @return  在线充值的记录
     */
    public function online_recharge_list(){
        $list = D('Card_new')->online_recharge_list(1);
        $this->assign($list);
        $this->display();
    }

    /**
     * @return  分润转换记录
     */
    public function fenrun_list(){
        $condition_where = '22=22 ';
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'name') {
                $condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where .= " AND  `u`.`phone` like '%" . htmlspecialchars($_GET['keyword']) ."%'";
            }
        }
        $condition_where .= $_GET['type']? ' AND o.type = '.$_GET['type']:' AND o.type = 1 ';

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .=" AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $order_count = D('User_fenrun_list')->join('as o left join '.C('DB_PREFIX').'user AS u ON u.uid = o.uid')->where($condition_where)->count();
        $all_fenrun = D('User_fenrun_list')->join('as o left join '.C('DB_PREFIX').'user AS u ON u.uid = o.uid')->where($condition_where)->sum('o.fenrun_money');
        import('@.ORG.system_page');
        $p = new Page($order_count,20);
        $order_list =  D('User_fenrun_list')->field('o.*,u.nickname,u.phone')->join('as o left join '.C('DB_PREFIX').'user AS u ON u.uid = o.uid')->where($condition_where)->order('o.id DESC')->limit($p->firstRow.','.$p->listRows)->select();

        $this->assign('order_list',$order_list);
        $this->assign('all_fenrun',$all_fenrun);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->display();
    }

    public function system_fenrun_list(){
        $condition_where = '22=22 ';

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .=" AND (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $order_count = D('System_fenrun_list')->where($condition_where)->count();

        import('@.ORG.system_page');
        $p = new Page($order_count,20);
        $order_list =  D('System_fenrun_list')->where($condition_where)->order('id DESC')->limit($p->firstRow.','.$p->listRows)->select();

        $fenrun_yesterday = false;
        if(M('System_fenrun_list')->where(array('record_time'=>strtotime(date("Y-m-d", strtotime("-1 day")))))->find()) {
            $fenrun_yesterday = true;
        }
        $all_date['fenrun_yesterday'] = $fenrun_yesterday;
        if($fenrun_yesterday){
            $all_date['all_score'] = D('Fenrun')->get_fenrun_score(false,1);
            $all_date['all_money'] = D('Fenrun')->get_system_take(1);
        }else{
            $all_date['all_score']  =D('Fenrun')->get_fenrun_score(false);
            $all_date['all_money'] = D('Fenrun')->get_system_take();

        }
        $this->assign('all_date',$all_date);

        $this->assign('order_list',$order_list);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->display();
    }

    //获取百分比
    public  function ajax_get_fenrun_percent(){

        $this->success(sprintf("%.2f", $_SESSION['percent']));
        exit;
    }

    public function award_list(){
        $condition_where = '22=22 ';
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'name') {
                $condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where .= " AND  `u`.`phone` like '%" . htmlspecialchars($_GET['keyword']) ."%'";
            }

        }
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .=" AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $order_count = D('Fenrun_recommend_award_list')->join('as o left join '.C('DB_PREFIX').'user AS u ON u.uid = o.uid')->where($condition_where)->count();

        import('@.ORG.system_page');
        $p = new Page($order_count,20);
        $order_list =  D('Fenrun_recommend_award_list')->field('o.*,u.nickname,u.phone')->join('as o left join '.C('DB_PREFIX').'user AS u ON u.uid = o.uid')->where($condition_where)->order('o.id DESC')->limit($p->firstRow.','.$p->listRows)->select();
        foreach($order_list as &$v){
            if($v['type']==1){
                $v['spread_info'] = D('User')->get_user($v['type_id']);
            }elseif($v['type']==2){
                $v['spread_info'] = D('Merchant')->get_info($v['type_id']);
            }
        }

        $this->assign('order_list',$order_list);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->display();
    }

    public function free_award_list(){
        $condition_where = '22=22 ';
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'name') {
                $condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where .= " AND  `u`.`phone` like '%" . htmlspecialchars($_GET['keyword']) ."%'";
            }

        }
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .=" AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $order_count = D('Fenrun_free_award_money_list')->join('as o left join '.C('DB_PREFIX').'user AS u ON u.uid = o.uid')->where($condition_where)->count();

        import('@.ORG.system_page');
        $p = new Page($order_count,20);
        $order_list =  D('Fenrun_free_award_money_list')->field('o.*,u.nickname,u.phone')->join('as o left join '.C('DB_PREFIX').'user AS u ON u.uid = o.uid')->where($condition_where)->order('o.id DESC')->limit($p->firstRow.','.$p->listRows)->select();
        foreach($order_list as &$v){
            if($v['type']==1){
                $v['spread_info'] = D('User')->get_user($v['type_id']);
            }elseif($v['type']==2){
                $v['spread_info'] = D('Merchant')->get_info($v['type_id']);
            }
        }

        $this->assign('order_list',$order_list);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->display();
    }

    public function score_all_list(){
        $condition_where = '22=22 ';
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'name') {
                $condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where .= " AND  `u`.`phone` like '%" . htmlspecialchars($_GET['keyword']) ."%'";
            }

        }
        $condition_where .= $_GET['type']? ' AND o.type = '.$_GET['type']:' AND o.type = 1 ';
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .=" AND (o.time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $order_count = D('User_score_list')->join('as o left join '.C('DB_PREFIX').'user AS u ON u.uid = o.uid')->where($condition_where)->count();
        import('@.ORG.system_page');
        $p = new Page($order_count,20);
        $order_list =  D('User_score_list')->join('as o left join '.C('DB_PREFIX').'user AS u ON u.uid = o.uid')->where($condition_where)->order('o.id DESC')->limit($p->firstRow.','.$p->listRows)->select();
        $this->assign('order_list',$order_list);
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);
        $this->display();
    }


    public function fenrun_by_admin(){
        if(M('System_fenrun_list')->where(array('record_time'=>strtotime(date("Y-m-d", strtotime("-1 day")))))->find()) {
            $this->error("昨日已经分润成功，不能再进行分润了");
        }
        if(C('config.open_score_fenrun') && C('config.auto_fenrun')) {
            $this->error("您开启了自动分润，不能再手动分润了");
        }
        $Fenrun_model =  D('Fenrun');
        $user_list = $Fenrun_model->get_fenrun_user();
        //$_SESSION['percent'] = 0;
        //$count = count($user_list);
        set_time_limit(0);
        if (!empty($user_list)) {
            foreach ($user_list as $key=>$v) {
                //$_SESSION['percent']=($key+1)/$count*100;
                $Fenrun_model->fenrun($v['uid']);
            }
           $result =  $Fenrun_model->save_today_fenrun_income_date(2);
        }
        if($result)
            $this->success('分润成功');
        else
            $this->error('昨天没有可分润数据');
    }

    /**
     * @return  管理员充值列表
     */
    public function admin_recharge_list(){
//        $recharge_list = M('User_money_list')->where(array('admin_id'=>array('neq','')))->select();
        $admin_list = M('Admin')->where(array('status'=>1))->select();
        $this->assign('admin_list',$admin_list);
        $where['l.admin_id'] = array('neq', 0);
        if(!empty($_GET['admin_id'])) {
            if ($_GET['admin_id'] == '0') {
                $where['l.admin_id'] = array('neq', 0);
            } else{
                $where['l.admin_id'] = $_GET['admin_id'];
            }
        }
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] =" (l.time BETWEEN ".$period[0].' AND '.$period[1].")";

        }
        $recharge_list =  D('User_money_list')->get_admin_recharge_list($where,1);

        $this->assign($recharge_list);
        $this->display();
    }

    public function dellevel(){

        if(IS_POST){
          $user_level = M('User_level');
            /**改软删除*4禁用***/
//            if(M('User')->where(array('level'=>$_POST['lid']))->data(array('level'=>0))->save()){
            M('User')->where(array('level'=>$_POST['lid']))->data(array('level'=>0))->save();

                if($user_level->where(array('id'=>$_POST['lid']))->delete()){
                    $this->success('删除成功！');
                }else{
                    $this->error('删除失败！请重试~');
                }
//            }else{
//                $this->error('用户等级删除失败，请重试');
//            }
        }else{
            $this->error('非法提交,请重新提交~');
        }
    }

    public function select_user(){
        if (!empty($_POST['search'])) {
            $condition_user['_string'] = "nickname like '%".$_POST['search']."%' OR phone like '%".$_POST['search']."%'";
        }
        $condition_user['openid'] = array('neq','');
//        $condition_user['openid'] = array('notlike','%no_use');
        //排序
        $order_string = '`uid` DESC';
        $database_user = D('User');
        $count_user = $database_user->where($condition_user)->count();
        import('@.ORG.system_page');
        $p = new Page($count_user, 5);
        $user_list = $database_user->field(true)->where($condition_user)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign('list',$user_list);
        $this->assign('page',$p->show());
        $this->display();
    }

    public function ajax_get_user(){
        if($_POST['search_val']){
            $where['openid'] = array('neq','');
            $where['_string'] = "nickname like '%".$_POST['search_val']."%' OR phone like '%".$_POST['search_val']."%'";
            $res = M('User')->field('nickname,phone,openid,avatar')->where($where)->limit(10)->select();
//			$this->ajaxReturn($res);
            $this->assign('list',$res);
        }
        $this->display('select_user');

    }

    public function score_pay_back_list(){
        $model = D('Merchant_money_list');
        $now_time = time();
        $today_time_zero = strtotime(date('Y-m-d',$now_time));;
        $today_time_condition['_string'] = "use_time>{$today_time_zero} AND use_time<{$now_time}";
        $today_system_take = $model->get_system_take_by_condition($today_time_condition);
        $yesterday_time_zero = $today_time_zero-86400;
        $yesterday_time_condition['_string'] = "use_time<{$today_time_zero} AND use_time>{$yesterday_time_zero}";
        $yesterday_system_take = $model->get_system_take_by_condition($today_time_condition);
        $yesterday_money_condition['_string'] = "time<{$today_time_zero} AND use_time>{$yesterday_time_zero}";
        $yesterday_money_condition['ask'] = 3;
        $yesterday_pay_back = M('User_money_list')->where($yesterday_money_condition)->sum('money');

        $condition_where['_string'] = '1=1 ';
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'name') {
                $condition_where['_string'] .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where['_string'] .= " AND  `u`.`phone` like '%" . htmlspecialchars($_GET['keyword']) ."%'";
            }

        }
       
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where['_string'] .=" AND (o.time BETWEEN ".$period[0].' AND '.$period[1].")";
        }


        $condition_where['is_pay_back']  =1 ;
        $order_count = D('User_score_list')->join('as o left join '.C('DB_PREFIX').'user AS u ON u.uid = o.uid')->where($condition_where)->count();
        import('@.ORG.system_page');
        $p = new Page($order_count,20);
        $order_list =  D('User_score_list')->field('o.*,u.nickname,u.phone')->join('as o left join '.C('DB_PREFIX').'user AS u ON u.uid = o.uid')->where($condition_where)->order('o.id DESC')->limit($p->firstRow.','.$p->listRows)->select();

        $this->assign('order_list',$order_list);
        $this->assign('today_system_take',floatval($today_system_take));
        $this->assign('yesterday_system_take',floatval($yesterday_system_take));
        $this->assign('yesterday_pay_back',floatval($yesterday_pay_back));
        $pagebar = $p->show();
        $this->assign('pagebar',$pagebar);

        $this->display();
    }

    //代理商记录
    public function agent_log(){
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $condition_user['a.uid'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'nickname') {
                $condition_user['u.nickname'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'phone') {
                $condition_user['u.phone'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }
        $condition_user['type']=2;
        $agent_count  = M('Distributor_agent')->join('as a inner JOIN '.C('DB_PREFIX').'user as u ON u.uid = a.uid')->where($condition_user)->group('uid')->count();
        import('@.ORG.system_page');
        $p = new Page($agent_count,20);
        $agent_list  = M('Distributor_agent')->join('as a inner JOIN '.C('DB_PREFIX').'user as u ON u.uid = a.uid')->where($condition_user)->group('a.uid')->field('a.uid,u.nickname,u.phone')->limit($p->firstRow,$p->listRows)->select();

        foreach ($agent_list as &$v) {
            $v['spread_money'] = D('Distributor_agent')->get_all_agent_money('uid',$v['uid']);
            $v['counts'] = M('Agent_spread_log')->where(array('uid'=>$v['uid']))->count();
        }

        $pagebar = $p->show();

        $all_spread_money = D('Distributor_agent')->get_all_agent_money();
        $this->assign('all_spread_money',$all_spread_money);
        $this->assign('pagebar',$pagebar);
        $this->assign('agent_list',$agent_list);

        $this->display();
    }

    public function agent_merchant_list(){

        $condition_user['uid'] = $_GET['uid'];
        $agent_count  = M('Agent_spread_log')->where($condition_user)->count();
        import('@.ORG.system_page');

        $p = new Page($agent_count,20);
        unset($condition_user['uid']);
        $condition_user['a.uid'] = $_GET['uid'];
        $agent_list  = M('Agent_spread_log')->join('as a LEFT JOIN '.C('DB_PREFIX').'merchant as m ON m.mer_id = a.mer_id')->where($condition_user)->field('m.mer_id,m.name,m.phone')->limit($p->firstRow,$p->listRows)->select();

        foreach ($agent_list as &$v) {
            $v['spread_money'] = D('Distributor_agent')->get_all_agent_money('mer_id',$v['mer_id']);
        }

        $pagebar = $p->show();
        $user= D('User')->get_user($_GET['uid']);
        $this->assign('user',$user);
        $this->assign('pagebar',$pagebar);
        $this->assign('agent_list',$agent_list);
        $this->display();
    }

    public function agent_money_list(){
        $money_list = D('Distributor_agent')->get_agent_spread_list($_GET['uid']);
        foreach ($money_list['money_list'] as &$v) {
            $user = D('User')->get_user($v['uid']);
            $mer = D('Merchant')->get_info($v['mer_id']);
            $v['nickname']  =$user['nickname'];
            $v['name']  =$mer['name'];
        }
        $money_list['user'] = D('User')->get_user($_GET['uid']);
        $this->assign('money_list',$money_list);

        $this->display();
    }

    //分销员 代理商下一级
    public function agent_next_log(){
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $condition_user['uid'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'nickname') {
                $condition_user['nickname'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'phone') {
                $condition_user['phone'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }

        $condition_user['openid'] = array('notlike','%no_use');
        //排序
        $order_string = '`uid` DESC';
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

        //状态
        $condition_user['u.status'] = 1;


        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_user['_string'] =" (u.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $database_user = D('User');
        if($_GET['action']=='agent'){

            $count_user = $database_user->join(' as u RIGHT JOIN '.C('DB_PREFIX').'distributor_agent l ON l.uid=u.uid AND l.type=2')->where($condition_user)->group('l.uid')->count();
        }else{

            $count_user = $database_user->join(' as u RIGHT JOIN '.C('DB_PREFIX').'distributor_agent l ON l.uid=u.uid AND l.type=1')->where($condition_user)->group('l.uid')->count();
        }
        import('@.ORG.system_page');
        $p = new Page($count_user, 20);
        if($_GET['action']=='agent') {
            $user_list = $database_user->join(' as u RIGHT JOIN '.C('DB_PREFIX').'distributor_agent l ON l.uid=u.uid AND l.type=2')->field('u.*')->where($condition_user)->order($order_string)->group('l.uid')->limit($p->firstRow . ',' . $p->listRows)->select();
        }else{
            $user_list = $database_user->join(' as u RIGHT JOIN '.C('DB_PREFIX').'distributor_agent l ON l.uid=u.uid AND l.type=1')->field('u.*')->where($condition_user)->order($order_string)->group('l.uid')->limit($p->firstRow . ',' . $p->listRows)->select();

        }


        $this->assign('user_list', $user_list);

        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }

    public function agent_user(){
        // dump($_GET);
        $uid = $_GET['uid'];
        $openid = $_GET['openid'];
        if(empty($_GET['from_uid'])){
            $_GET['from_uid'] = $_GET['uid'];
        }

        $now_user = D('User')->get_user($_GET['from_uid']);
        $user_list = D('User_spread')->get_spread_user($openid,$uid);

        if (!empty($user_list)) {

            foreach ($user_list['spread_user_list'] as $key=>&$value) {
                if($value['openid']==''){
                    unset($user_list['spread_user_list'][$key]);
                }
            }
        }

        $this->assign('user_list', $user_list['spread_user_list']);

        $this->assign('now_user', $now_user);
        $this->display();
    }


}
