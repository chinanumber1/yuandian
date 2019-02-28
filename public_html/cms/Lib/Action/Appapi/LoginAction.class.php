<?php
/**
 * Login Controller
 */
class LoginAction extends BaseAction {
    public function config(){
        $agreement = $this->config['register_agreement'];
        $this->returnCode(0,array('agreement'=>$agreement));
    }
    //登录
    public function login() {
        $ticket = I('ticket', false);
        $client = I('client', 0);
        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info) {
                $uid = $info['uid'];
				
				$login_result = D('User')->autologin('uid',$uid);
				if($login_result['error_code']){
					$this->returnCode(1001,array(),$login_result['msg']);
				}else{
					$user = $login_result['user'];
				}
				
                $doorList	=	$this->door_list($uid,1);
                $return = array(
                    'ticket'    =>	$ticket,
                    'user'      =>	$user,
                    'door'		=>	isset($doorList)?$doorList:array(),
                );
				
                $this->addLog($user,$client);
                $this->upDevice($client,$uid);
                $this->returnCode(0, $return);
            }
        }
        $phone = I('phone', false);
        if (! $phone) {
            $this->returnCode('20042001');
        }
        $passwd = I('passwd', false);
        $smscode = I('code', false);
        if (! $passwd && !$smscode) {
            $this->returnCode('20042002');
        }
        if($smscode){
            $where = array();
            $where['phone'] = $phone;
            $where['extra'] = $smscode;
            $where['type']   = 0;
            $where['status'] = 0;
            $where['expire_time'] = array('gt', time());
            $smsresult = D("App_sms_record")->where($where)->find();
            if (! $smsresult) {
                $this->returnCode('20044009');
            }
        }
        $result = D("User")->checkin($phone, $passwd,true,$smscode);

        if ($result['error_code']) {
            if($smscode){
                $date_user['phone'] = $phone;
                $date_user['client'] = $_POST['client'];
                $date_user['source'] ='sms';
                $date_user['spread_code'] =$_POST['spread_code'];
				$date_user['nickname'] = substr_replace($phone, '****', 3, 4);
                $reg_user  = D("User")->autoreg($date_user);
				if($reg_user['error_code']){
					$this->returnCode(1001,array(),$reg_user['msg']);
				}
				$login_result = D('User')->autologin('uid',$reg_user['uid']);
				if($login_result['error_code']){
					$this->returnCode(1001,array(),$login_result['msg']);
				}
				$result['user'] = $login_result['user'];
				
                D('User')->register_give_money($result['user']['uid'],1);
                if(!empty($result['user']) && $this->config['open_score_fenrun'] && $_POST['spread_code']){
                    if($spread_user = M('User')->where(array('spread_code'=>$_POST['spread_code']))->find()){
                        $now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();
                        D('User_spread')->data(array('spread_openid'=>$spread_user['openid'],'spread_uid'=>$spread_user['uid'],'openid'=>'','uid'=>$result['user']['uid']))->add();
                        if ($this->config['spread_user_give_type'] == 1 || $this->config['spread_user_give_type'] == 2) {
                            $spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level['spread_user_give_moeny']:$this->config['spread_give_money'];
                            //D('User')->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额','','',$uid);
                            $Res = D('Fenrun')->add_recommend_award($spread_user['uid'],$result['user']['uid'],1,$spread_give_money,'推荐新用户注册平台奖励佣金');

                        }

                    }
                }
            }else{
                $this->returnCode($result['msg']);
            }
        }
        unset($result['user']['pwd']);
        $doorList = $this->door_list($result['user']['uid'], 1);
        $ticket = ticket::create($result['user']['uid'], $this->DEVICE_ID, true);
        $return = array(
            'ticket'=>	$ticket['ticket'],
            'user'	=>	$result['user'],
            'door'	=>	isset($doorList)?$doorList:array(),
        );
        $this->addLog($result['user'],$client);
        $this->upDevice($client,$result['user']['uid']);
        $this->returnCode(null, $return);
    }
    //注册
    public function register() {
        $code = I('code', 0);
        if (! $code) {
            $this->returnCode('20044008');
        }
        $phone = I("phone", false);
        if (! $phone) {
            $this->returnCode('20042001');
        }
        $passwd = I("passwd", false);
        if (! $passwd) {
            $this->returnCode('20042002');
        }
        $client = I('client', 0);
        //判断验证码
        //if ($code != '1234') {
//             $this->returnCode('20044009');
//        }
        $where = array();
        $where['phone'] = $phone;
        $where['extra'] = $code;
        $where['type']   = 1;
        $where['status'] = 0;
        $where['expire_time'] = array('gt', time());
        $result = D("App_sms_record")->where($where)->find();
        if (! $result) {
           $this->returnCode('20044009');
        }
        //注册账号
        $result = D('User')->where(array('phone'=>$phone))->find();
        if ($result) {
            $this->returnCode('20044005');
        }


        $result = D('User')->checkreg($phone, $passwd);
        if ($result['error_code']) {
            $this->returnCode('20044003');
        }

        if(!empty($result['user']) && $this->config['open_score_fenrun'] && $_POST['spread_code']){
            if($spread_user = M('User')->where(array('spread_code'=>$_POST['spread_code']))->find()){
                $now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();
                D('User_spread')->data(array('spread_openid'=>$spread_user['openid'],'spread_uid'=>$spread_user['uid'],'openid'=>'','uid'=>$result['user']['uid']))->add();
                if ($this->config['spread_user_give_type'] == 1 || $this->config['spread_user_give_type'] == 2) {
                    $spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level['spread_user_give_moeny']:$this->config['spread_give_money'];
                    //D('User')->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额','','',$uid);
                    $Res = D('Fenrun')->add_recommend_award($spread_user['uid'],$result['user']['uid'],1,$spread_give_money,'推荐新用户注册平台奖励佣金');

                }

            }
        }

        $database_house_village_user_bind = D('House_village_user_bind');
        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $find_village = D('House_village_user_bind')->where(array('phone'=>trim($phone)))->select();
        if($find_village){
            foreach($find_village as $k=>$v){
                $data_vacancy = array();
                $dataInfo['uid'] = $result['user']['uid'];
                $database_house_village_user_bind->where(array('pigcms_id'=>$v['pigcms_id']))->data($dataInfo)->save();
                if($v['type']==0 && $v['vacancy_id']>0){
                    $database_house_village_user_vacancy->where(array('pigcms_id'=>$v['vacancy_id']))->data($dataInfo)->save();
                }
            }
        }

        unset($result['user']['pwd']);
        $uid = $result['user']['uid'];
        $nickname = $result['user']['nickname'];
        D('User')->register_give_money($uid,1);
//        if ($this->config['register_give_money_condition'] == 3 || $this->config['register_give_money_condition'] == 4) {
//            if ($this->config['register_give_money_type'] == 1 || $this->config['register_give_money_type'] == 2) {
//                D('User')->add_money($uid, $this->config['register_give_money'], '新用户注册平台赠送余额');
//                D('Scroll_msg')->add_msg('reg', $uid, '用户' . $nickname . '于' . date('Y-m-d H:i', $_SERVER['REQUEST_TIME']) . 'APP端注册成功获得赠送余额' . $this->config['register_give_money'] . '元');
//            }
//
//            if ($this->config['register_give_money_type'] == 0 || $this->config['register_give_money_type'] == 2) {
//                D('User')->add_score($uid, $this->config['register_give_score'], '新用户注册平台赠送' . $this->config['score_name']);
//                D('Scroll_msg')->add_msg('reg', $uid, '用户' . $nickname . '于' . date('Y-m-d H:i', $_SERVER['REQUEST_TIME']) . 'APP端注册成功获得赠送' . $this->config['score_name'] . $this->config['register_give_score'] . '个');
//            }
//        }
        

        $ticket = ticket::create($uid, $this->DEVICE_ID, true);
        //统一数据结构
        $result['user']['coupon_count'] = 0;
        $return = array(
            'ticket' => $ticket["ticket"],
            'user' => $result['user'],
        );
        $this->addLog($result['user'],$client);
        $this->upDevice($client,$result['user']['uid']);
        $this->returnCode(null, $return);
    }
    //发送验证码
    public function sendCode() {
        $phone = I('phone', 0);
        if (! $phone) {
            $this->returnCode('20042001');
        }
        if(strlen($this->DEVICE_ID)>40 || empty($this->DEVICE_ID)){
            $this->returnCode('20045014');
        }
        //防止1分钟内多次发送短信
        $laste_sms = M('App_sms_record')->where(array('device_id'=>$this->DEVICE_ID))->order('pigcms_id DESC')->find();
        if($this->check_sms($this->DEVICE_ID,$phone)>10){
            $this->returnCode('20046037');
        }
        if(time()-$laste_sms['send_time']<60 && $_POST['type']!=3){
            $this->returnCode('20046036');
        }

        $type = I('type', 1);
        $code = mt_rand(1000, 9999);
        $text = '您的验证码是：' . $code . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
        if($type == 1){        //注册帐号
            //查看用户是否存在
            $where = array();
            $where['phone'] = $phone;
            $result = D("User")->field('`uid`')->where($where)->find();
            if($result){
                $this->returnCode('20044005');
            }
        }else if ($type == 2) {  //忘记密码
            //查看用户是否存在
            $where = array();
            $where['phone'] = $phone;
            $result = D("User")->field('`uid`')->where($where)->find();
            if(!$result){
                $this->returnCode('20044011');
            }
        }

        $columns = array();
        $columns['phone'] = $phone;
        $columns['text'] = $text;
        $columns['extra'] = $code;
        $columns['device_id'] = $this->DEVICE_ID;
        $columns['type'] = $type;
        $columns['status'] = 0;
        $columns['send_time'] = time();
        $columns['expire_time'] = $columns['send_time'] + 1200;
        $result = D("App_sms_record")->add($columns);
        if (! $result) {
            $this->returnCode('20044007');
        }

        $return = Sms::sendSms(array('mer_id' => 0, 'store_id' => 0, 'content' => $text, 'mobile' => $phone, 'uid' => 0, 'type' => 'app_register'));
        if ($result != 0) {
            $this->returnCode(self::ConverSmsCode($return));
        }
        $this->returnCode(null, $return);
    }

    //检查短信一天条数
    public function check_sms($device_id,$phone){
        $today_zero = strtotime(date('Ymd',time()));
		if($device_id == 'wxapp'){
			$where['_string'] = "phone ='".$phone."' AND send_time >".$today_zero.' AND send_time<'.($today_zero+86399);
		}else{
			$where['_string'] = "(device_id = '".$device_id."' OR phone ='".$phone."') AND send_time >".$today_zero.' AND send_time<'.($today_zero+86399);
		}
        return M('App_sms_record')->where($where)->count();
    }

    //  发送更改密码验证码
    public function verifyCode() {
        $phone = I('phone', 0);
        if (! $phone) {
            $this->returnCode('20042001');
        }
        $code = I('code', 0);
        if (! $code) {
            $this->returnCode('20044008');
        }

        $type = I('type', 2);
        $where = array();
        $where['phone'] = $phone;
        $where['extra'] = $code;
        $where['type'] = $type;
        $where['status'] = 0;
        $where['expire_time'] = array('gt', time());
        $result = D("App_sms_record")->where($where)->find();
        if (! $result) {
            $this->returnCode('20044009');
        }
        $result = D("App_sms_record")->where($where)->data(array('status'=>1))->save();
        $this->returnCode();
    }
    //  忘记密码-更改密码
    public function forget() {
        $code = I('code', 0);
        if (! $code) {
            $this->returnCode('20044008');
        }
        $phone = I("phone", false);
        if (! $phone) {
            $this->returnCode('20042001');
        }
        $passwd = I("passwd", false);
        if (! $passwd) {
            $this->returnCode('20042002');
        }

        //查看用户是否存在
        $where = array();
        $where['phone'] = $phone;
        $result = D("User")->field('`uid`')->where($where)->find();
        if (! $result) {
            $this->returnCode('20044011');
        }

        $result = D('User')->save_user($result['uid'],'pwd',md5($passwd));
        if ($result['error_code']) {
            $this->returnCode('20044012');
        }

        $this->returnCode();
    }
    //微信登录
    public function weixin_login() {
        $client = I('client', 0);
        $weixin_open_id = I('weixin_open_id', 0, "trim");
        if (! $weixin_open_id) {
            $this->returnCode('20045001');
        }
        $weixin_union_id = I('weixin_union_id', 0, "trim");
        if (! $weixin_union_id) {
            $this->returnCode('20045002');
        }
        $nickname = I('nickname', "weixin_user", "trim");
        $avatar = I('avatar');
        $sex = I('sex', 1);
        //如果已经绑定账号，直接登录，返回ticket
        $user = D("User")->get_user($weixin_open_id, "app_openid");
        if(strpos($user['openid'],'no_use')){
            $this->returnCode('20120005');
        }
        if($user&&$user['status'] !=1 ){
            $this->returnCode('20120008');
        }

        if ($user) {
            $columns = array();
            $columns['last_time'] = $_SERVER['REQUEST_TIME'];
            $columns['last_ip'] = get_client_ip(1);
            D('User')->where(array('uid' => $user['uid']))->save($columns);
            unset($user['pwd']);
            $ticket = ticket::create($user['uid'], $this->DEVICE_ID, true);
            $return = array(
                'ticket' => $ticket['ticket'],
                'user'=>$user
            );
            $this->addLog($user,$client);
            $this->upDevice($client,$user['uid']);
            $this->returnCode(null, $return);
        }
        //如果已经绑定账号，直接登录，返回ticket
        $user = D("User")->get_user($weixin_union_id, "union_id");
        if ($user) {
            $columns = array();
            $columns['app_openid'] = $weixin_open_id;
            $columns['last_time'] = $_SERVER['REQUEST_TIME'];
            $columns['last_ip'] = get_client_ip(1);
            D('User')->where(array('uid' => $user['uid']))->save($columns);
            unset($user['pwd']);
            $ticket = ticket::create($user['uid'], $this->DEVICE_ID, true);
            $return = array(
                'ticket' => $ticket['ticket'],
                'user'=>$user
            );
            $this->addLog($user,$client);
            $this->upDevice($client,$user['uid']);
            $this->returnCode(null, $return);
        }
        //未绑定，注册新账号，并登录，返回ticket
        $columns = array();
        $columns['app_openid'] = $weixin_open_id;
        $columns['union_id'] = $weixin_union_id;
        $columns['sex'] = $sex;
        $columns['avatar'] = $avatar;
        $columns['add_time'] = $_SERVER['REQUEST_TIME'];
        $columns['last_time'] = $_SERVER['REQUEST_TIME'];
        $columns['source']    = ($client == '1' ? 'iosapp' : 'androidapp');
        $columns['add_ip'] = get_client_ip(1);
        $columns['last_ip'] = get_client_ip(1);
		if($nickname == $this->config['site_name']){
       		$columns['nickname'] = '昵称';
		}else{
        	$columns['nickname'] = $nickname;
		}
        $userId = D("User")->data($columns)->add();
        if (! $userId) {
            $this->returnCode('20045004');
        }
        D('User')->register_give_money($user['uid']);
//       	if ($this->config['register_give_money_condition'] == 2 || $this->config['register_give_money_condition'] == 4) {
//			if($this->config['register_give_money_type']==1 ||$this->config['register_give_money_type']==2 ){
//				D('User')->add_money($userId, $this->config['register_give_money'], '新用户注册平台赠送余额');
//				D('Scroll_msg')->add_msg('reg',$userId,'用户'.$nickname.'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'APP端注册成功获得赠送余额'.$this->config['register_give_money'].'元');
//			}
//
//			if($this->config['register_give_money_type']==0 ||$this->config['register_give_money_type']==2 ){
//				D('User')->add_score($userId, $this->config['register_give_score'], '新用户注册平台赠送'.$this->config['score_name']);
//				D('Scroll_msg')->add_msg('reg',$userId,'用户'.$nickname.'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'APP端注册成功获得赠送'.$this->config['score_name'].$this->config['register_give_score'].'个');
//			}
//		}

        $user = D("User")->get_user($userId, "uid");
        $this->addLog($user,$client);
        $this->upDevice($client,$user['uid']);
        unset($user['pwd']);
        $ticket = ticket::create($user['uid'], $this->DEVICE_ID, true);
        $return = array(
            'ticket' => $ticket['ticket'],
            'user'=>$user
        );

        $this->returnCode(null, $return);
    }
    // 手机号绑定
    public function bind_user(){
    	if($_POST['type'] != 3){
			$code 		= I('code', 0);
	        $phone      =   I('phone');
	        $passwd     =   I('passwd');
	        $ticket     =   I('ticket', false);
	        $client     =   I('client');
	        if (!$code) {
	            $this->returnCode('20044008');
	        }
	        $where = array();
	        $where['phone'] = $phone;
	        $where['extra'] = $code;
	        $where['type']   = 1;
	        $where['status'] = 0;
	        $where['expire_time'] = array('gt', time());
	        $result = D("App_sms_record")->where($where)->find();
	        if (! $result) {
	           $this->returnCode('20044009');
	        }
	        if ($ticket) {
	            $info = ticket::get($ticket, $this->DEVICE_ID, true);
	            if ($info) {
	                $uid = $info['uid'];
	            }
	            if(empty($phone)){
	                $this->returnCode('20042001');
	            }
	            if(empty($passwd)){
	                $this->returnCode('20042002');
	            }
	            $database_user = D('User');
	            $condition_user['phone'] = $phone;
	            if($database_user->field('`uid`')->where($condition_user)->find()){
	                $this->returnCode('20044014');
	            }
	            $condition_save_user['uid'] = $uid;
	            $data_save_user['phone'] = $phone;
	            $data_save_user['pwd'] = md5($passwd);
	            if($database_user->where($condition_save_user)->data($data_save_user)->save()){
	                $user = array(
	                    'uid'    =>  $uid,
	                    'phone'    =>  $phone,
	                );
	                $this->addLog($user,$client);
	                $_SESSION['user']['phone'] = $phone;
	                $arr    =   $database_user->where($condition_save_user)->find();
	                $this->returnCode(0,$arr);
	            }else{
	                $this->returnCode('20045006');
	            }
	        }else{
	            $this->returnCode('20044013');
	        }
    	}else{
			$this->weixin_bind_user();
    	}

    }
    //错误代码
    static private function ConverSmsCode($smscode) {
        $errCode = array(
            '2'    => '20060001',
            '400'  => '20060002',
            '401'  => '20060003',
            '402'  => '20060004',
            '403'  => '20060005',
            '4030' => '20060006',
            '404'  => '20060007',
            '405'  => '20060008',
            '4050' => '20060009',
            '4051' => '20060010',
            '4052' => '20060011',
            '406'  => '20060012',
            '407'  => '20060013',
            '4070' => '20060014',
            '4071' => '20060015',
            '4072' => '20060016',
            '4073' => '20060017',
            '408'  => '20060018',
            '4085' => '20060019',
            '4084' => '20060020',
        );
        return $errCode[$smscode];
    }
    //  记录手机登录，Appapi_app_login_log
    public function addLog($user = array(),$client){
        $log = array(
            'client' => $client,
            'device_id' => $this->DEVICE_ID,
            'uid' => $user['uid'],
            'phone' => $user['phone'],
            'create_time' => time(),
        );
        M("Appapi_app_login_log")->add($log);
    }
    //  更新user表里的Device-Id
    public function upDevice($client,$uid){
        $userUpdata =   array(
            'device_id' =>  $this->DEVICE_ID,
            'client'    =>  $client
        );
        M('User')->where(array('uid' => $uid))->save($userUpdata);
    }
    //	获取用户的门禁列表
    public function door_list($uid='',$type=2){
        if($this->app_version>=200){
            if($type==1){
                return $this->door_list_($uid,$type);
            }else{
                $this->door_list_($uid,$type);die;
            }
        }
    	if($type == 1){
			if(empty($uid)){
				return	array();
			}
    	}else{
			$uid = $this->_uid;
			if(empty($uid)){
				$this->returnCode('20090009');
			}
    	}
		$where['uid']	=	$uid;
		$aUserSelect	=	M('House_village_user_bind')->distinct(true)->field(array('village_id','floor_id','property_price','property_endtime','phone','type'))->where($where)->select();
        $now_user = D('User')->get_user($uid);

        $worker_info = M('House_worker')->where(array('phone'=>$now_user['phone'],'status'=>array('neq',4)))->select();
        $aDoor = array();
        foreach($worker_info as $k=>$v){
            $condition_door['door_status']	=	1;
            $condition_door['village_id'] = $v['village_id'];
            $condition_door['floor_id'] = array(array('eq',-1),array('eq',$v['floor_id']),'or');
            $aDoorList	=	M('House_village_door')->distinct(true)->field(true)->where($condition_door)->select();

            $ban_open_status = !$v['open_door'];

            foreach($aDoorList as $kk=>$vv){
                $userWhere	=	array(
                    'user_id'	=>	$uid,
                    'door_fid'	=>	$vv['door_id'],
                );
                $aDoorFind	=	M('House_village_door_user')->field(true)->where($userWhere)->find();
                if($ban_open_status){
                    $aDoorList[$kk]['open_status']	=	3;//欠物业费
                    $aDoorList[$kk]['open_status_txt']	=	'您已欠物业费不能开门';
                }elseif(empty($aDoorFind)){
                    $aDoorList[$kk]['open_status']	=	1;
                    $aDoorList[$kk]['open_status_txt']	=	'可以打开';
                }else if($aDoorFind['status'] == 1){
                    if($aDoorFind == 0 || time()>$aDoorFind['end_time']){
                        $aDoorList[$kk]['open_status']	=	1;	//允许使用
                        $aDoorList[$kk]['open_status_txt']	=	'可以打开';
                    }else{
                        $aDoorList[$kk]['open_status']	=	2;	//时间过期
                        $aDoorList[$kk]['open_status_txt']	=	'已过期，请联系物业';
                    }
                }else{
                    $aDoorList[$kk]['open_status']	=	0;		//禁止使用
                    $aDoorList[$kk]['open_status_txt']	=	'您没有权限开门，请联系物业';		//禁止使用
                }
            }
            //  $aDoorList[$kk]['open_status']	=	1;
            $aSelect[]	=	$aDoorList;

        }

        if($aSelect){
            foreach($aSelect as $k=>$v){
                if(empty($v)){
                    continue;
                }
                foreach($v as $kk=> $vv){
                    if($vv['floor_id'] != "-1"){
                        $aFloor	=	M('House_village_floor')->field(array('floor_name','floor_layer'))->where(array('floor_id'=>$vv['floor_id']))->find();
                        $vv['floor_name']	=	strval($aFloor['floor_name']);
                        $vv['floor_layer']	=	strval($aFloor['floor_layer']);
                    }else{
                        $vv['floor_name']	=	'小区';
                        $vv['floor_layer']	=	'大门';
                    }
                    $aDoor[]	=	isset($vv)?$vv:array();
                }
            }
        }
        if($type == 1){
            return	$aDoor;
        }else{
            if(empty($aDoor)){
                $aDoor	=	array();
            }else  if(!empty($aDoor)){
                $aDoor = $this->wxapp_door($aDoor);
                $this->returnCode(0,$aDoor);
            }
        }

		if($type	==	1){
			if(empty($aUserSelect)){
				return	array();
			}
		}else{
			if(empty($aUserSelect)){
				$this->returnCode(0,array());
			}
		}
		foreach($aUserSelect as $k=>$v){
			$condition_door['door_status']	=	1;
			$condition_door['village_id'] = $v['village_id'];
			$condition_door['floor_id'] = array(array('eq',-1),array('eq',$v['floor_id']),'or');
			$aDoorList	=	M('House_village_door')->distinct(true)->field(true)->where($condition_door)->select();
            if($v['type']==4){
                //工作人员 不跟状态有关，production
                $worker_info = M('House_worker')->where(array('village_id'=>$v['village_id'],'phone'=>$v['phone']))->find();
                if($worker_info){
                    $ban_open_status = $worker_info['open_door'];
                }else{
                    $ban_open_status = true;
                }
            }else{
                $now_house = M('House_village')->where(array('village_id'=>$v['village_id']))->find();
                if(!$v['property_endtime']|| $now_house['owe_property_open_door']){
                    $ban_open_status = false;
                }else{

                    $owe_property_days = (strtotime(date('y-m-d',time()))-strtotime(date('y-m-d',$v['property_endtime'])))/86400;
                   //$ban_open_status = (!$now_house['owe_property_open_door'] &&(time()>$v['property_endtime'])  || ($now_house['owe_property_open_door_day']!=0 && $owe_property_days>0 && $owe_property_days > $now_house['owe_property_open_door_day'] ));
                    // $ban_open_status = (!$now_house['owe_property_open_door'] &&(time()>$v['property_endtime'])  || ($now_house['owe_property_open_door_day']!=0 && $owe_property_days>0 && $owe_property_days > $now_house['owe_property_open_door_day'] ));
                    $ban_open_status = true;
                    if(!$now_house['owe_property_open_door'] &&(time()>$v['property_endtime'])){
                        $ban_open_status = false;
                    }else if($now_house['owe_property_open_door_day']!=0 && $owe_property_days>0 && $owe_property_days > $now_house['owe_property_open_door_day']){
                        $ban_open_status = false;
                    }
                }
            }

			foreach($aDoorList as $kk=>$vv){
				$userWhere	=	array(
					'user_id'	=>	$uid,
					'door_fid'	=>	$vv['door_id'],
				);
				$aDoorFind	=	M('House_village_door_user')->field(true)->where($userWhere)->find();
                if($ban_open_status){
                    $aDoorList[$kk]['open_status']	=	3;//欠物业费
                    $aDoorList[$kk]['open_status_txt']	=	'您已欠物业费不能开门';
                }elseif(empty($aDoorFind)){
					$aDoorList[$kk]['open_status']	=	1;
                    $aDoorList[$kk]['open_status_txt']	=	'可以打开';
				}else if($aDoorFind['status'] == 1){
					if($aDoorFind == 0 || time()>$aDoorFind['end_time']){
						$aDoorList[$kk]['open_status']	=	1;	//允许使用
                        $aDoorList[$kk]['open_status_txt']	=	'可以打开';
                    }else{
						$aDoorList[$kk]['open_status']	=	2;	//时间过期
                        $aDoorList[$kk]['open_status_txt']	=	'已过期，请联系物业';
					}
				}else{
					$aDoorList[$kk]['open_status']	=	0;		//禁止使用
					$aDoorList[$kk]['open_status_txt']	=	'您没有权限开门，请联系物业';		//禁止使用
				}
			}
          //  $aDoorList[$kk]['open_status']	=	1;
			$aSelect[]	=	$aDoorList;
		}

        $worker_info = M('House_worker')->where(array('village_id'=>$v['village_id'],'phone'=>$v['phone']))->find();
        foreach($worker_info as $k=>$v){
            $condition_door['door_status']	=	1;
            $condition_door['village_id'] = $v['village_id'];
            $condition_door['floor_id'] = array(array('eq',-1),array('eq',$v['floor_id']),'or');
            $aDoorList	=	M('House_village_door')->distinct(true)->field(true)->where($condition_door)->select();

            $ban_open_status = $worker_info['open_door'];

            foreach($aDoorList as $kk=>$vv){
                $userWhere	=	array(
                    'user_id'	=>	$uid,
                    'door_fid'	=>	$vv['door_id'],
                );
                $aDoorFind	=	M('House_village_door_user')->field(true)->where($userWhere)->find();
                if($ban_open_status){
                    $aDoorList[$kk]['open_status']	=	3;//欠物业费
                    $aDoorList[$kk]['open_status_txt']	=	'您已欠物业费不能开门';
                }elseif(empty($aDoorFind)){
                    $aDoorList[$kk]['open_status']	=	1;
                    $aDoorList[$kk]['open_status_txt']	=	'可以打开';
                }else if($aDoorFind['status'] == 1){
                    if($aDoorFind == 0 || time()>$aDoorFind['end_time']){
                        $aDoorList[$kk]['open_status']	=	1;	//允许使用
                        $aDoorList[$kk]['open_status_txt']	=	'可以打开';
                    }else{
                        $aDoorList[$kk]['open_status']	=	2;	//时间过期
                        $aDoorList[$kk]['open_status_txt']	=	'已过期，请联系物业';
                    }
                }else{
                    $aDoorList[$kk]['open_status']	=	0;		//禁止使用
                    $aDoorList[$kk]['open_status_txt']	=	'您没有权限开门，请联系物业';		//禁止使用
                }
            }
            //  $aDoorList[$kk]['open_status']	=	1;
            $aSelect[]	=	$aDoorList;

        }
        $door_device_arr = array();
		if($aSelect){
			foreach($aSelect as $k=>$v){
				if(empty($v)){
					continue;
				}
				foreach($v as $kk=> $vv){
                    if(in_array($vv['door_device_id'],$door_device_arr)){
                        continue;
                    }else{
                        $door_device_arr[] = $vv['door_device_id'];
                    }
					if($vv['floor_id'] != "-1"){
						$aFloor	=	M('House_village_floor')->field(array('floor_name','floor_layer'))->where(array('floor_id'=>$vv['floor_id']))->find();
						$vv['floor_name']	=	strval($aFloor['floor_name']);
						$vv['floor_layer']	=	strval($aFloor['floor_layer']);
					}else{
						$vv['floor_name']	=	'小区';
						$vv['floor_layer']	=	'大门';
					}

					$aDoor[]	=	isset($vv)?$vv:array();
				}
			}
		}
		if($type == 1){
			return	$aDoor;
		}else{
			if(empty($aDoor)){
				$aDoor	=	array();
			}
            $aDoor = $this->wxapp_door($aDoor);

			$this->returnCode(0,$aDoor);
		}
    }

    public function door_list_($uid='',$type=2){
        if($type == 1){
            if(empty($uid)){
                return	array();
            }
        }else{
            $uid = $this->_uid;
            if(empty($uid)){
                $this->returnCode('20090009');
            }
        }
        $where['uid']	=	$uid;
        $aUserSelect	=	M('House_village_user_bind')->distinct(true)->where($where)->getField('village_id,floor_id,property_price,property_endtime,phone,type');
        $now_user = D('User')->get_user($uid);

        $worker_info = M('House_worker')->where(array('phone'=>$now_user['phone'],'status'=>array('neq',4)))->getField('village_id,wid,phone,status,open_door');

        $aDoor = array();


        //user_type 1 用户 2 工作人员 3 （1，2）合并
        foreach ($aUserSelect as &$item) {
            if($worker_info[$item['village_id']]){
                $item = array_merge($item,$worker_info[$item['village_id']]);
                $item['user_type']=3;
            }else{
                $item['user_type']=1;
            }
        }


        foreach ($worker_info as &$v) {
            if(!$aUserSelect[$v['village_id']]){
                $v['user_type'] = 2;
                $aUserSelect[$v['village_id']] = $v;
            }
        }

        if($aUserSelect){

            foreach ($aUserSelect as $w) {
                $village_arr[] = $w['village_id'];
                $ban_open_status = $w['open_door']?$w['open_door']:true;


                if($w['user_type']==2  ){
                    $ban_open_status = !$w['open_door'];
                } else{
                    $now_house = M('House_village')->where(array('village_id'=>$w['village_id']))->find();

                    if(!$w['property_endtime'] || $now_house['owe_property_open_door']){
                        $ban_open_status = false;
                    }else{
                        $owe_property_days = (strtotime(date('y-m-d',time()))-strtotime(date('y-m-d',$w['property_endtime'])))/86400;

                        $ban_open_status = true;
                        if(!$now_house['owe_property_open_door'] &&(time()>$w['property_endtime'])){
                            $ban_open_status = false;
                        }else if($now_house['owe_property_open_door_day']!=0 && $owe_property_days>0 && $owe_property_days > $now_house['owe_property_open_door_day']){
                            $ban_open_status = false;
                        }
                    }
                }
                $ban_open_status_arr[$w['village_id']] = $ban_open_status;
                $user_door[] = $w['floor_id'];
            }

            $condition_door['door_status']	=	1;
            $condition_door['village_id']	=	array('in',$village_arr);

            $aFloor	=	M('House_village_floor')->where(array('village_id'=>$condition_door['village_id']))->getField('floor_id,floor_name,floor_layer,village_id');
            $aDoorList	=	M('House_village_door')->distinct(true)->field(true)->where($condition_door)->select();

            foreach($aDoorList as $kk=>&$vv){
                // $userWhere	=	array(
                // 'user_id'	=>	$uid,
                // 'door_fid'	=>	$vv['door_id'],
                // );
                // $aDoorFind	=	M('House_village_door_user')->field(true)->where($userWhere)->find();
                if($ban_open_status_arr[$vv['village_id']]){
                    $vv['open_status']	=	3;//欠物业费
                    $vv['open_status_txt']	=	'您已欠物业费不能开门';
                }elseif(in_array($vv['floor_id'],$user_door) || $vv['floor_id']==-1 || $aUserSelect[$vv['village_id']]['user_type']>1){
                    $vv['open_status']	=	1;
                    $vv['open_status_txt']	=	'可以打开';
                }else if($aDoorFind['status'] == 1){
                    if($aDoorFind == 0 || time()>$aDoorFind['end_time']){
                        $vv['open_status']	=	1;	//允许使用
                        $vv['open_status_txt']	=	'可以打开';
                    }else{
                        $vv['open_status']	=	2;	//时间过期
                        $vv['open_status_txt']	=	'已过期，请联系物业';
                    }
                }else{
                    $vv['open_status']	=	0;		//禁止使用
                    $vv['open_status_txt']	=	'您没有权限开门，请联系物业';		//禁止使用
                }
//                $tmp[$vv['village_id']]	=	$vv;
                if($vv['floor_id'] != "-1"){

                    $vv['floor_name']	=	strval($aFloor[$vv['floor_id']]['floor_name']);
                    $vv['floor_layer']	=	strval($aFloor[$vv['floor_id']]['floor_layer']);
                }else{
                    $vv['floor_name']	=	'小区';
                    $vv['floor_layer']	=	'大门';
                }
                $aDoor[]	=	isset($vv)?$vv:array();
            }

        }



        if($type == 1){
            return	$aDoor;
        }else{
            if(empty($aDoor)){
                $aDoor	=	array();
            }else  if(!empty($aDoor)){
                $aDoor = $this->wxapp_door($aDoor);
            }
            $this->returnCode(0,$aDoor);
        }


    }

    private function wxapp_door($aDoor){
        foreach ($aDoor as &$item) {
            $lockDate = S($item['door_psword'].$item['door_device_id']);

            if(empty($lockDate) && $this->DEVICE_ID=='wxapp'){
                $return_msg = file_get_contents("http://120.24.53.51:8080/dhpkgcomm/app/wxlockData?DEVICEPSW={$item['door_psword']}&DEVICE_ID={$item['door_device_id']}");
                $return_msg = json_decode($return_msg,true);
                $item['lockData'] = $return_msg['lockData'];
                S($item['door_psword'].$item['door_device_id'],$item['lockData'],3000);
            }else{
                $item['lockData']  = $lockDate;
            }
        }
        return $aDoor;
    }

    public function house_adver_click(){
        $adver_id = $_POST['adver_id'];
       // $type = $_POST['type'];
        $num_name = 'click_num';
        M('House_open_door_adver')->where(array('id'=>$adver_id))->setInc($num_name,1);
        $this->returnCode(0);
    }

    //开门app通知
    public function open_door_notice(){
        $ticket     =   I('ticket', false);
        $code		=	I('code',false);
        if(empty($ticket)){
            $this->returnCode('20044013');
        }else{
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info['uid']) {
                $uid = $info['uid'];
            }else{
                $this->returnCode('20000009');
            }
        }
        $oneday_get_num = $this->config['oneday_opendoor_lottery_num'];
        $condition_door['door_id'] = $_POST['door_id'];
        if(!$door_info = M('House_village_door')->where($condition_door)->find()){
            $this->returnCode('20090094');
        }

        $where['uid'] = $uid;
        $today_zero = strtotime(date('y-m-d',$_SERVER['REQUEST_TIME']));
        $today_end = $today_zero+86399;
        $where['_string'] = "add_time >{$today_zero} AND add_time<{$today_end}";
        $oneday_open_num = M('Coupon_rand_send_hadpull')->where($where)->count();

        $get_score = 0;
        $success_coupon_num = 0;
        // 每日开门最多可获得积分优惠券的次数大于当前次数
        if($oneday_get_num > $oneday_open_num) {


            $min_score = $this->config['open_door_min_score'];
            $max_score = $this->config['open_door_max_score'];
            $get_score = rand($min_score, $max_score);

            if ($get_score > 0) {
                D('User')->add_score($uid, $get_score, '门禁开门成功随机赠送您' . $get_score . '个积分');
            }

            $get_coupon_num = $this->config['open_door_coupon_num'];

            $coupon_list = D('System_coupon')->get_coupon_list();
            shuffle($coupon_list);
            foreach ($coupon_list as $v) {
                //限制随机派送仅一次
                if (M('Coupon_rand_send_hadpull')->where(array('uid' => $uid, 'coupon_id' => $v['coupon_id']))->find()) {
                    continue;
                }
                $result = D('System_coupon')->send_coupon_by_id($v['coupon_id'], $uid, 1);
                if (!$result['error']) {
                    $date['coupon_id'] = $v['coupon_id'];
                    $date['uid']       = $uid;
                    $date['add_time']  = $_SERVER['REQUEST_TIME'];
                    $date['num']       = 1;
                    M('Coupon_rand_send_hadpull')->add($date);
                    $success_coupon[] = $v['coupon_id'];
                }

                if (count($success_coupon) >= $get_coupon_num) {
                    break;
                }
            }
            $success_coupon_num = count($success_coupon);
        }
        $adver_list = M('House_open_door_adver')->where(array('status'=>1))->select();
        $adver_village_list = M('Adver_village')->where(array('status'=>1,'village_id'=>$door_info['village_id']))->getField('adver_id,village_id');
        $tmp_advers = [];
        foreach ($adver_list as $ad) {
            $tmp_advers[$ad['id']] = $ad;
            if($ad['village_id']==$door_info['village_id'] ){
                $tmp_adver[] = $ad;
            }

        }

        foreach ($adver_village_list as $key=>$adv) {
            if($adv['village_id']==$door_info['village_id'] && $tmp_advers[$key] ){
                $tmp_adver[] = $tmp_advers[$key];
            }
        }

        if(!empty($tmp_adver)){
            $adver_list = $tmp_adver;
        }
        // 随机获取一张广告图片
        $adver_key = 0;
        if (!empty($adver_list)) {
            $adver_key = rand(0, count($adver_list) - 1);;
        }

        $return['id'] = $adver_list[$adver_key]['id'];
        $return['name'] = $adver_list[$adver_key]['name'];
        $return['ios_pic_s'] = $adver_list[$adver_key]['android_pic'];
        $return['ios_pic_b'] = $adver_list[$adver_key]['android_pic'];
        $return['android_pic'] = $adver_list[$adver_key]['android_pic'];
        $return['url'] = htmlspecialchars_decode($adver_list[$adver_key]['url']);
        $return['site_name'] = $this->config['site_name'];

        $txt='';
        if($get_score>0){
            $txt = '送您'.$get_score.'个平台积分';
        }
        if($success_coupon_num>0){
            $txt .= ','.$success_coupon_num.'张平台优惠券';
        }
        $return['open_lottery_txt'] = $txt;
        $this->returnCode(0,$return);
    }

    public function modify_phone(){
        $ticket     =   I('ticket', false);
        $code		=	I('code',false);
		$phone		=	$_POST['phone'];
		
		$where = array();
        $where['phone'] = $phone;
        $where['extra'] = $code;
        $where['type']   = 3;
        $where['status'] = 0;
        $where['expire_time'] = array('gt', time());
        $result = D("App_sms_record")->where($where)->find();

        if(!$result){
            $this->returnCode('20044009');
        }
		
		if($_POST['other_id'] && $_POST['bind_id']){
			$now_user = D('User')->get_user($phone,'phone');
			if($now_user){
				$data_bind_user['uid'] = $now_user['uid'];
				$condition_bind_user['id'] = $_POST['bind_id'];
				if(D('Weixin_bind_user')->where($condition_bind_user)->data($data_bind_user)->save()){
					$ticket = ticket::create($now_user['uid'],'wxapp', true);
					$return = array(
						'ticket'=>	$ticket['ticket'],
						'user'	=>	$now_user,
					);
					$this->returnCode(0,$return);
				}else{
					$this->returnCode(1001,array(),'绑定老用户失败');
				}
			}else{
				$now_bind_user = D('Weixin_bind_user')->get_info($_POST['other_id'],'id',$_POST['bind_id']);
				$data_user = array(
					'phone' 	=> $phone,
					'sex' 		=> $now_bind_user['sex'],
					'province' 	=> $now_bind_user['province'],
					'city' 		=> $now_bind_user['city'],
					'avatar' 	=> $now_bind_user['avatar'],
					'source' 	=> $now_bind_user['source'],
				);
				$reg_result = D('User')->autoreg($data_user);
				if($reg_result['error_code']){
					$this->returnCode(1001,array(),$reg_result['msg']);
				}else{
					$login_result = D('User')->autologin('uid',$reg_result['uid']);
					if($login_result['error_code']){
						$this->returnCode(1001,array(),$login_result['msg']);
					}else{
						$now_user = $login_result['user'];
						$data_bind_user['uid'] = $now_user['uid'];
						$condition_bind_user['id'] = $_POST['bind_id'];
						D('Weixin_bind_user')->where($condition_bind_user)->data($data_bind_user)->save();
						
						$ticket = ticket::create($now_user['uid'],'wxapp', true);
						$return = array(
							'ticket'=>	$ticket['ticket'],
							'user'	=>	$now_user,
						);
						$this->returnCode(0,$return);
					}
				}
			}
		}
		
		if(!$this->_uid){
			$this->returnCode('20044013');
		}else{
			$uid = $this->_uid;
		}

        if(empty($phone)){
            $this->returnCode('20042001');
        }
        $database_user = D('User');
        $now_user = $database_user->where(array('uid'=>$uid))->find();
        if(!empty($now_user['phone'])){
            $condition_user['phone'] = $phone;
            if($database_user->field(true)->where($condition_user)->find()){
                $this->returnCode('10044005');
            }
        }
		
        $condition_save_user['uid'] = $uid;
        $data_save_user['phone'] = $phone;

        if($database_user->where($condition_save_user)->data($data_save_user)->save()){
			D('House_village_user_bind')->bind($uid,$phone);
            $this->returnCode(0);
        }else{
            $this->returnCode('20120006');
        }
    }
    //	微信绑定手机
    public function weixin_bind_user(){
    	$ticket     =   I('ticket', false);
    	$code		=	I('code',false);
		if(empty($ticket)){
			$this->returnCode('20044013');
		}else{
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			if ($info['uid']) {
                $uid = $info['uid'];
            }else{
				$this->returnCode('20000009');
            }
		}

		$phone		=	$_POST['phone'];
		$password	=	$_POST['passwd'];
		if(empty($phone)){
			$this->returnCode('20042001');
		}
        $database_user = D('User');
        $now_user = $database_user->where(array('uid'=>$uid))->find();
        if(!empty($now_user['phone'])){
            $condition_user['phone'] = $phone;
            if($database_user->field(true)->where($condition_user)->find()){
                $this->returnCode('10044005');
            }
        }

		$where = array();
        $where['phone'] = $phone;
        $where['extra'] = $code;
        $where['type']   = 3;
        $where['status'] = 0;
        $where['expire_time'] = array('gt', time());
        $result = D("App_sms_record")->where($where)->find();

        if (!$result) {
           $this->returnCode('20044009');
        }
        $openidPost	=	$_POST['openid'];
        $unionidPost	=	$_POST['union_id'];
        if(!$openidPost){
			$this->returnCode('20120010');
        }
		//$database_user = D('User');
		$condition_user['phone'] = $phone;
		if(($res = $database_user->field('`uid`,`pwd`')->where($condition_user)->find())&&empty($now_user['phone'])){
			$openid = $database_user->field('`app_openid`')->where('uid='.$res['uid'])->find();
			if(!empty($openid['app_openid'])){
				$this->returnCode('20120005');
			}
            
            if($res['pwd']==''){
                $login_result = D('User')->checkin($phone,$password,'app',1);
            }else{
                $login_result = D('User')->checkin($phone,$password,'app');
            }

            if($login_result['error_code']){
				$this->returnCode($login_result['msg']);
			}else{
				if($database_user->where('`uid`='.$res['uid'])->setField('app_openid',$openidPost)){
					$database_user->where('`uid`='.$info['uid'])->setField('union_id',$unionidPost);
					$database_user->where('`uid`='.$info['uid'])->setField('app_openid',$openidPost.'~no_use');
					session_destroy();
					unset($_SESSION);
					$this->returnCode(0);
				}else{
					$this->returnCode('20120006');
				}
			}
		}
		$condition_save_user['uid'] = $uid;
		$data_save_user['phone'] = $phone;
        if(!empty($password)){
            $data_save_user['pwd'] = md5($password);
        }
		if($database_user->where($condition_save_user)->data($data_save_user)->save()){
			$_SESSION['user']['phone'] = $phone;
			$this->returnCode(0);
		}else{
			$this->returnCode('20120006');
		}
	}
}