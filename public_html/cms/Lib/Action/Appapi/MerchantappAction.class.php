<?php
class MerchantappAction extends BaseAction{
    protected $merid;
    protected $merchant;

    protected function _initialize(){
        parent::_initialize();
        $ticket = I('ticket', false);
        if($ticket){
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if($info){
                $condition_merchant['mer_id'] = $info['uid'];
            }else{
                $this->returnCode('20140001');
            }
            $database_merchant = D('Merchant');
            $this->merchant = $database_merchant->field(true)->where($condition_merchant)->find();
            $this->mer_id = $this->merchant['mer_id'];
        }
        $this->config['have_group_name'] = isset($this->config['group_alias_name']) ? $this->config['group_alias_name'] : '团购';// 团购
        $this->config['have_meal_name'] = isset($this->config['meal_alias_name']) ? $this->config['meal_alias_name'] : '餐饮'; // 餐饮
        $this->config['have_shop_name'] = isset($this->config['shop_alias_name']) ? $this->config['shop_alias_name'] : '快店'; // 快店
        $this->config['have_appoint_name'] = isset($this->config['appoint_alias_name']) ? $this->config['appoint_alias_name'] : '预约'; // 预约
    }
	public function cityList(){
		$area_list = M('Area')->field('`area_id`,`area_pid`,`area_name`')->where(array('is_open'=>'1','area_type'=>array('lt','4')))->order('`area_type` ASC')->select();
		
		$tmp_area_list = array();
		foreach($area_list as $key=>$value){
			$tmp_area_list[$value['area_pid']][] = $value;
		}

		$new_area_list = $tmp_area_list[0];
				
		foreach($new_area_list as $key=>$value){
			if(empty($tmp_area_list[$value['area_id']])){
				unset($new_area_list[$key]);
			}else{
				$new_area_list[$key]['children'] = $tmp_area_list[$value['area_id']];
                $tmp_new_area_list[] = array('children'=>$tmp_area_list[$value['area_id']],'area_id'=>$value['area_id'],'area_name'=>$value['area_name']);
			}
		}


		
		foreach($tmp_new_area_list as $key=>$value){
            $tmp_new_area_list[$key]['children'] = array();
			foreach($value['children'] as $k=>$v){
				if(empty($tmp_area_list[$v['area_id']])){
					unset($tmp_new_area_list[$key]['children'][$k]);
				}else{
                   // $tmp_new_area_list[$key]['children'][$k]['children'] = $tmp_area_list[$v['area_id']];

                    $tmp_new_area_list[$key]['children'][] = array('children'=>$tmp_area_list[$v['area_id']],'area_id'=>$v['area_id'],'area_name'=>$v['area_name']);
				}
			}
		}


		$returnAreaList = array();
		foreach($tmp_new_area_list as $key=>&$value){
			$value['value'] = $value['area_id'];
			$value['text'] = $value['area_name'];
			unset($value['area_id'],$value['area_name'],$value['area_pid']);
			foreach($value['children'] as $k=>&$v){
				$v['value'] = $v['area_id'];
				$v['text'] = $v['area_name'];
				unset($v['area_id'],$v['area_name'],$v['area_pid']);
				foreach($v['children'] as $kk=>&$vv){
					$vv['value'] = $vv['area_id'];
					$vv['text'] = $vv['area_name'];
					unset($vv['area_id'],$vv['area_name'],$vv['area_pid']);
				}
			}
		}
		
		$this->returnCode(0,$tmp_new_area_list);
		// dump($new_area_list);
	}
	public function config(){
		$arr['open_score_fenrun'] = isset($this->config['open_score_fenrun']) ? $this->config['open_score_fenrun'] : 0; // 店员中心APP包名

        if($this->config['open_distributor']==1){
            $arr['open_score_fenrun'] =1;
        }
		$arr['withdraw_fee_percent'] = isset($this->config['company_pay_mer_percent']) ? $this->config['company_pay_mer_percent'] : 0; // 提现手续费率
		$arr['withdraw_type'] = array(0=>'银行卡','1'=>'支付宝','2'=>'平台');
		$arr['company_least_money'] =   $this->config['company_least_money'];

		$arr['merchant_verify'] = $this->config['merchant_verify'];	//注册商家是否审核
		
		$arr['site_phone'] = $this->config['site_phone']; //网站电话
        $arr['coupon_wx_sync'] = $this->config['coupon_wx_sync'];
        $arr['group_name'] = $this->config['have_group_name'];// 团购
        $arr['meal_name'] = $this->config['have_meal_name'];// 餐饮
        $arr['shop_name'] = $this->config['have_shop_name'];// 快店
        $arr['appoint_name'] = $this->config['have_appoint_name']; // 预约
        $arr['score_name'] = $this->config['score_name']; // 积分
        $arr['cash_alias_name'] = $this->config['cash_alias_name']; // 优惠买单
        $arr['open_admin_code'] = $this->config['open_admin_code']; // 优惠买单
        $arr['open_merchant_reg_sms'] = $this->config['open_merchant_reg_sms']; // 优惠买单

       // $config['can_register'] = true;
        $arr['site_phone'] = $this->config['site_phone'];
        $arr['is_packapp'] = true;
        $appConfig  =   D('Appapi_app_config')->field(true)->select();

        foreach($appConfig as $k=>$v){

            $appConfig[$v['var']]   =   nl2br($v['value']);

        }

        $arr['mer_android_v'] = $appConfig['mer_android_v']?$appConfig['mer_android_v']:'';
        $arr['mer_android_vcode'] = $appConfig['mer_android_vcode']?$appConfig['mer_android_vcode']:'';
        $arr['mer_android_url'] = $appConfig['mer_android_url']?$appConfig['mer_android_url']:'';
        $arr['mer_android_vdesc'] = $appConfig['mer_android_vdesc']?$appConfig['mer_android_vdesc']:'';


        $arr['staff_android_url'] = $appConfig['staff_android_url']?$appConfig['staff_android_url']:'';
        $arr['mer_android_package_name'] = $appConfig['mer_android_package_name']?$appConfig['mer_android_package_name']:'';
        $arr['storestaff_android_package_name'] = $appConfig['storestaff_android_package_name']?$appConfig['storestaff_android_package_name']:'';
        $arr['storestaff_ios_download_url'] = $appConfig['storestaff_ios_download_url']?$appConfig['storestaff_ios_download_url']:'';
        $arr['mer_ios_package_name'] = $appConfig['mer_ios_package_name']?$appConfig['mer_ios_package_name']:'';
        $arr['storestaff_ios_package_name'] = $appConfig['storestaff_ios_package_name']?$appConfig['storestaff_ios_package_name']:'';
        $arr['store_register_agreement'] = $this->config['store_register_agreement'];
        $arr['international_phone'] = $this->config['international_phone']?$this->config['international_phone']:0;
        $arr['discount_controler'] = $this->config['discount_controler']?$this->config['discount_controler']:0;
        $arr['open_merchant_change_phone'] = $this->config['open_merchant_change_phone']?$this->config['open_merchant_change_phone']:0;
        $arr['open_distributor'] = $this->config['open_distributor']?$this->config['open_distributor']:0;

		$this->returnCode(0, $arr);
	}
	
	// 商户中心获取信息
    public function indexshow(){
        $arr['site_phone'] = $this->config['site_phone']; // 电话
        $arr['have_group_name'] = isset($this->config['group_alias_name']) ? $this->config['group_alias_name'] : '团购';// 团购
        $arr['have_meal_name'] = isset($this->config['meal_alias_name']) ? $this->config['meal_alias_name'] : '餐饮'; // 餐饮
        $arr['have_shop_name'] = isset($this->config['shop_alias_name']) ? $this->config['shop_alias_name'] : '快店'; // 快店
        $arr['have_appoint_name'] = isset($this->config['appoint_alias_name']) ? $this->config['appoint_alias_name'] : '预约'; // 预约
        $arr['discount_prompt'] = '请填写0~100之间的整数，0和100都是表示无折扣，98表示9.8折';
        $arr['sort_prompt'] = '默认添加顺序排序！手动调值，数值越大，排序越前';
        $arr['out_img'] = $this->config['site_url'] . '/static/appapi/merchant/out.png'; // 退出
        $arr['more_img'] = $this->config['site_url'] . '/static/appapi/merchant/more.png'; // 更多
        $arr['morecolor_img'] = $this->config['site_url'] . '/static/appapi/merchant/morecolor.png'; // 更多-彩
        $arr['closer_img'] = $this->config['site_url'] . '/static/appapi/merchant/close.png'; // 关闭
        $arr['app_android_shop'] = isset($this->config['app_android_shop']) ? $this->config['app_android_shop'] : ''; // 店员中心APP包名
        $arr['app_ios_shop'] = isset($this->config['app_ios_shop']) ? $this->config['app_ios_shop'] : ''; // 店员中心APP包名
      // $arr['time'] = time();
        
        $this->returnCode(0, $arr);
    }
	# 登录
	public function login()
    {
        $ticket = I('ticket', false);
        $store_ticket = I('store_ticket', false);
        $store_device = I('store_device', false);
        $store_device =strtolower( $store_device);
        $from = I('from',false);

        $auth =array(
            'group'=>1,
            'shop'=>1,
            'meal'=>1,
            'appoint'=>1,
            'store_list'=>1,
            'hardware'=>1,
            'merchant_money'=>1,
            'fans_send'=>1,
            'card'=>1,
            'card_recharge'=>1,
        );
        if ($this->config['merchant_card_recharge_offline']==0) {
            $auth['card_recharge'] = 0;
        }
        if($from=='storestaff'){
            $info = ticket::get($store_ticket, $store_device, true);

            if ($info) {
                $now_staff =  D('Merchant_store_staff')->field(true)->where(array('id' => $info['uid']))->find();
                $condition_merchant_store['store_id'] = $now_staff['store_id'];
                $now_store = M('Merchant_store')->field(true)->where($condition_merchant_store)->find();
                $now_merchant = D('Merchant')->get_info($now_store['mer_id']);

                $merchant_menus = $now_merchant['menus'];
                $merchant = explode(',',$merchant_menus);

                if ($merchant_menus) {

                    if (!in_array(8,$merchant)) {
                        $auth['group'] = 0; // 团购
                    }
                    if (!in_array(108,$merchant)) {
                        $auth['shop'] = 0; // 快店
                    }
                    if (!in_array(6,$merchant)) {
                        $auth['meal'] = 0; // 餐饮
                    }
                    if (!in_array(60,$merchant)) {
                        $auth['appoint'] =0; // 预约
                    }
                    if (!in_array(5,$merchant)) {
                        $auth['store_list'] = 0;
                    }
                    if (!in_array(49,$merchant)) {
                        $auth['hardware'] = 0; // 打印机
                    }
                    if (!in_array(118,$merchant)) {
                        $auth['merchant_money'] = 0; // 打印机
                    }
                    if (!in_array(35,$merchant)) {
                        $auth['fans_send'] = 0; // 打印机
                    }
                    if (!in_array(146,$merchant)) {
                        $auth['card_recharge'] = 0; // 打印机
                    }


                }
                $arr = array(
                    'mer_id' => $now_merchant['mer_id'],
                    'name' => $now_merchant['name'],
                    'phone' => $now_merchant['phone'],
                    'email' => $now_merchant['email'],
                    'txt_info' => $now_merchant['txt_info']
                );
                $aTicket = ticket::create($now_merchant['mer_id'], $this->DEVICE_ID, true);
                $ticket = $aTicket['ticket'];
                $return = array(
                    'ticket' => $ticket,
                    'user' => $arr,
                    'auth' => $auth,
                    'store_id' => $now_store['store_id'],
                );

                $this->returnCode(0, $return);
            }
        }
        $database_merchant = D('Merchant');
        if($ticket){
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if($info){
                $condition_merchant['mer_id'] = $info['uid'];
            }
        }else{
            $condition_merchant['phone'] = $_POST['phone'];
        }
        $condition_merchant['status'] = 1;
        $now_merchant = $database_merchant->field(true)->where($condition_merchant)->find();
        if(empty($now_merchant)){
			$condition_merchant_account['account'] = $_POST['phone'];
			$condition_merchant_account['status'] = 1;

			$now_merchant = $database_merchant->field(true)->where($condition_merchant_account)->find();
			if(empty($now_merchant)){
				$this->returnCode('20140001');
			}
        }
        if(empty($ticket)){
            if(md5(trim($_POST['pwd'])) != $now_merchant['pwd']){
                $this->returnCode('20140002');
            }
            $aTicket = ticket::create($now_merchant['mer_id'], $this->DEVICE_ID, true);
            $ticket = $aTicket['ticket'];
        }
        
        if ($now_merchant['status'] == 0) {
            $this->returnCode('20140003');
        } elseif ($now_merchant['status'] == 2) {
            $this->returnCode('20140004');
        }
        $arr = array(
            'mer_id' => $now_merchant['mer_id'],
            'name' => $now_merchant['name'],
            'phone' => $now_merchant['phone'],
            'email' => $now_merchant['email'],
            'txt_info' => $now_merchant['txt_info']
        );
        $return = array(
            'ticket' => $ticket,
            'user' => $arr
        );
        
        $data_merchant['mer_id'] = $now_merchant['mer_id'];
        $data_merchant['last_ip'] = get_client_ip(1);
        $data_merchant['last_time'] = $_SERVER['REQUEST_TIME'];
        $data_merchant['login_count'] = $now_merchant['login_count'] + 1;

        $database_merchant = D('Merchant');
        $merchant = $database_merchant->field('menus')->where(array('mer_id' => $now_merchant['mer_id']))->find();
        $merchant_menus = $merchant['menus'];
        $merchant = explode(',',$merchant_menus);

        if ($merchant_menus) {

            if (!in_array(8,$merchant)) {
                $auth['group'] = 0; // 团购
            }
            if (!in_array(108,$merchant)) {
                $auth['shop'] = 0; // 快店
            }
            if (!in_array(6,$merchant)) {
                $auth['meal'] = 0; // 餐饮
            }
            if (!in_array(60,$merchant)) {
                $auth['appoint'] =0; // 预约
            }
            if (!in_array(5,$merchant)) {
                $auth['store_list'] = 0;
            }
            if (!in_array(49,$merchant)) {
                $auth['hardware'] = 0; // 打印机
            }
            if (!in_array(118,$merchant)) {
                $auth['merchant_money'] = 0; // 打印机
            }
            if (!in_array(35,$merchant)) {
                $auth['fans_send'] = 0; // 打印机
            }
            if (!in_array(146,$merchant)) {
                $auth['card'] = 0; // 打印机
            }
        }
        $return['auth'] = $auth;

        if($database_merchant->data($data_merchant)->save()){
            $this->returnCode(0, $return);
        }else{
            $this->returnCode('20140005');
        }
    }
	# 商家入驻
	public function mer_reg() {
		//短信
		if ($this->config['reg_verify_sms']&&$this->config['sms_key']&&$this->config['open_merchant_reg_sms']) {
            $laste_sms = M('Merchant_sms_record')->where(array('mer_id' => $this->mer_id,'extra'=>$_POST['sms_code']))->order('pigcms_id DESC')->find();
            if (empty( $laste_sms) ) {
                $this->returnCode('10044009');
            }
        }
		
		//帐号
		$database_merchant = D('Merchant');
		$arr['account'] =	$condition_merchant_account['account'] = I('account');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant_account)->find();
		if (!empty($now_merchant)) {
			$this->returnCode('20140006');
		}
		
		//手机号作为帐号
		$database_merchant = D('Merchant');
		$condition_merchant_account_phone['phone'] = I('account');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant_account_phone)->find();
		if(!empty($now_merchant)){
			$this->returnCode(1001,array(),'该账号作为手机号已经存在，不允许重复');
		}
		
		
		//名称
		$arr['name'] = $condition_merchant_name['name'] = I('mername');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant_name)->find();
		if (!empty($now_merchant)) {
			$this->returnCode('20140007');
		}

        if($this->config['open_admin_code']==1){
            if(!M('Admin')->where(array('invit_code'=>$_POST['invit_code']))->find()){
                $this->returnCode('20140008');
            }
        }

		//手机号
		$arr['phone'] =	$condition_merchant_phone['phone'] = I('phone');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant_phone)->find();
		if (!empty($now_merchant)) {
			$this->returnCode('20140009');
		}
		
		//帐号作为手机号
		$condition_merchant_phone_account['account'] = I('phone');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant_phone_account)->find();
		if(!empty($now_merchant)){
			$this->returnCode(1001,array(),'该手机号作为账号已经存在，不允许重复');
		}
		
		$arr['mer_id'] = null;
		if ($this->config['merchant_verify']) {
			$arr['status'] = 2;
		} else {
			$arr['status'] = 1;
		}
		$pwd	=	I('pwd');
		$province_id	=	I('province_id');
		$city_id	=	I('city_id');
		$area_id	=	I('area_id');
		$arr['pwd'] = md5($pwd);
		$arr['reg_ip'] = get_client_ip(1);
		$arr['reg_time'] = $_SERVER['REQUEST_TIME'];
		$arr['province_id'] = $province_id;
		$arr['city_id'] = $city_id;
		$arr['area_id'] = $area_id;
		$arr['login_count'] = 0;
		$arr['reg_from'] = 0;
        $_POST['spread_code'] && $arr['spread_code'] =  $_POST['spread_code'];
		$arr['invit_code'] = $_POST['invit_code'];

        $_POST['phone_country_type'] && $arr['phone_country_type'] = $_POST['phone_country_type'];
		if ($insert_id = $database_merchant->data($arr)->add()) {
            if (0 == $this->config['merchant_verify'] && $this->config['open_distributor']==1 && $_POST['spread_code']) {
                D('Distributor_agent')->agent_spread_log($insert_id);
            }
            D('Merchant')->reg_notice($insert_id);
			M('Merchant_score')->add(array('parent_id' => $insert_id, 'type' => 1));
			if ($this->config['merchant_verify']) {
				$this->returnCode(0,array('type'=>2));	//注册成功,请耐心等待审核或联系工作人员审核。
			} else {
				$this->returnCode(0,array('type'=>1));
			}
		} else {
			$this->returnCode('20140010');
		}
	}
	# 商家后台管理首页
	public function index() {
        $this->ticket();
        $allincomecount = $this->getallincomecount();
        $wap_MerchantAd = D('Adver')->get_adver_by_key('wap_Merchant', 7);
        if($wap_MerchantAd){
            foreach($wap_MerchantAd as $v){
                $Ad[]	=	array(
                    'url'	=>	$v['url'],
                    'pic'	=>	$v['pic'],
                );
            }
        }
        if (empty($this->merchant['qrcode_id'])) {
            $qrcode_return = D('Recognition')->get_new_qrcode('merchant', $this->merchant['mer_id']);
        } else {
            $qrcode_return = D('Recognition')->get_qrcode($this->merchant['qrcode_id']);
        }
        $number	=	$this->getallordercount();
        $arr	=	array(
            'wap_merchantAd'	=>	$wap_MerchantAd==false?array():$Ad,		//广告牌
            'qrcodeinfo'		=>	isset($qrcode_return['qrcode'])?$qrcode_return['qrcode']:'',	//二维码
            'name'				=>	$this->merchant['name'],	//商户logo
            'count_number'		=>	array(
            'allincomecount'	=>	(int)$allincomecount,					//收入总数
            'webviwe'			=>	(int)$this->merchant['hits'],	//浏览总数
            'allordercount'		=>	(int)$number['allordercount'],			//订单总数
            'monthordercount'	=>	(int)$number['monthordercount'],		//本月订单总数
            'todayordercount'	=>	(int)$number['todayordercount'],		//本日订单总数
            'fans_count'		=>	(int)$number['fans_count'],				//粉丝总数

//				'appoint_page_row'	=>	isset($this->config['appoint_page_row']) ? 1 : 0,	预约判断
            ),
            'logo'				=>	$this->merchant['logo'],	//商户logo
            'invit_code'				=>	$this->merchant['invit_code'],	//商户logo
        );
        $arr['type_name'] = $this->get_alias_c_name();         //业务类型.
        $arr['admin'] = M('Admin')->field('realname as text,invit_code as value')->where(array('invit_code'=>array('neq','')))->select();
        foreach ($arr['admin'] as $value) {
            if($value['value']==$this->merchant['invit_code']){
                $arr['admin_name'] = $value['text'];
                break;
            }
        }
        $config = M('Appapi_app_config')->select();
        foreach($config as $v){
            if($v['var']=='mer_android_v'){
                $arr['android_version'] = $v['value'];
            }elseif($v['var']=='mer_android_url'){
                $arr['android_downurl'] = $v['value'];
            }elseif($v['var']=='mer_android_vcode'){
                $arr['android_version_code'] = $v['value'];
            }elseif($v['var']=='mer_android_vdesc'){
                $arr['android_version_version_desc'] = $v['value'];
            }

        }
        $this->returnCode(0,$arr);
	}
	# 商家后台管理左划
	public function leftMenu()
    {
        $mer_id = $this->ticket();
        $appoint_row = isset($this->config['appoint_page_row']) ? 1 : 0; // 预约判断
        $database_merchant = D('Merchant');
        $merchant = $database_merchant->field('menus')->where(array('mer_id' => $mer_id))->find();
        $merchant = $merchant['menus'];
        $mer = array(
            'name' => $this->config['merchant_alias_name'] . '管理', // 快店名
            'alias_name' => $this->config['merchant_alias_name'], // 快店名
            'label' => 'merchant',
            'img' => $this->config['site_url'] . '/static/appapi/merchant/merchant.png',
            'img_color' => $this->config['site_url'] . '/static/appapi/merchant/merchantcolor.png',
            'so_list' => array()
        );
        $group = array(
            'name' => $this->config['group_alias_name'] . '管理', // 团购名
            'alias_name' => $this->config['group_alias_name'], // 团购名
            'label' => 'group',
            'img' => $this->config['site_url'] . '/static/appapi/merchant/group.png',
            'img_color' => $this->config['site_url'] . '/static/appapi/merchant/groupcolor.png',
            'so_list' => array()
        );
        $shop = array(
            'name' => $this->config['shop_alias_name'] . '管理', // 快店名
            'alias_name' => $this->config['shop_alias_name'], // 团购名
            'label' => 'shop',
            'img' => $this->config['site_url'] . '/static/appapi/merchant/merchant.png',
            'img_color' => $this->config['site_url'] . '/static/appapi/merchant/merchantcolor.png',
            'so_list' => array()
        );
        $meal = array(
            'name' => $this->config['meal_alias_name'] . '管理', // 餐饮名
            'alias_name' => $this->config['meal_alias_name'], // 团购名
            'label' => 'meal',
            'img' => $this->config['site_url'] . '/static/appapi/merchant/meal.png',
            'img_color' => $this->config['site_url'] . '/static/appapi/merchant/mealcolor.png',
            'so_list' => array()
        );
        $appoint = array(
            'name' => $this->config['appoint_alias_name'] . '管理', // 预约名
            'alias_name' => $this->config['appoint_alias_name'], // 团购名
            'label' => 'appoint',
            'img' => $this->config['site_url'] . '/static/appapi/merchant/appoint.png',
            'img_color' => $this->config['site_url'] . '/static/appapi/merchant/appointcolor.png',
            'so_list' => array()
        );
        $staff = array(
            'name' => '店员管理', // 店员名
            'alias_name' => '店员管理', // 团购名
            'label' => 'staff',
            'img' => $this->config['site_url'] . '/static/appapi/merchant/staff.png',
            'img_color' => $this->config['site_url'] . '/static/appapi/merchant/staffcolor.png',
            'so_list' => array()
        );
        $hardware = array(
            'name' => '打印机管理', // 打印机
            'alias_name' => '打印机管理', // 团购名
            'label' => 'hardware',
            'img' => $this->config['site_url'] . '/static/appapi/merchant/hardware.png',
            'img_color' => $this->config['site_url'] . '/static/appapi/merchant/hardwarecolor.png',
            'so_list' => array()
        );
        if ($merchant) {
            $arr = array();
            $arr[] = $mer; // 店铺
            if (stripos($merchant, ',8,') !== FALSE) {
                $arr[] = $group; // 团购
            }
            if (stripos($merchant, ',108,') !== FALSE) {
                $arr[] = $shop; // 快店
            }
            if (stripos($merchant, ',6,') !== FALSE) {
                $arr[] = $meal; // 餐饮
            }
            if (stripos($merchant, ',60,') !== FALSE) {
                $arr[] = $appoint; // 预约
            }
            if (stripos($merchant, ',47,') !== FALSE) {
                $arr[] = $staff; // 店员列表
            }
            if (stripos($merchant, ',49,') !== FALSE) {
                $arr[] = $hardware; // 打印机
            }
        } else {
            $arr = array(
                $group,
                $shop,
                $meal
            );
            if ($appoint_row) {
                $arr[] = $appoint;
            }
            $arr[] = $staff;
            $arr[] = $hardware;
        }
        $this->returnCode(0, $arr);
    }
	/***首页图标统计数据***/
    public function getchart() {
    	$this->ticket();
        $nowtime = time();
        $todaystartime = strtotime(date('Y-m-d'));
        $startime = $todaystartime - (7 * 24 * 3600);
        $act	=	I('act');
        $action = trim($act);
        $newdatas = array();
        for ($d = 0; $d < 8; $d++) {
            $datekey = date('m-d', $startime + $d * 24 * 3600);
            $newdatas[$datekey] = 0;
        }
        $meal_orderDb = M('Meal_order');
        $group_orderDb = M('Group_order');
        switch ($action) {
            case 'order' :
                $mdatas = $meal_orderDb->where('mer_id=' . $this->merchant['mer_id'] . ' AND dateline >' . $startime . ' AND dateline <=' . $nowtime . " AND status!=3")->field('count(order_id) as percount,FROM_UNIXTIME(dateline,"%m-%d") as perdate')->group('perdate')->select();
                foreach ($mdatas as $mvv) {
                    $newdatas[$mvv['perdate']] = (int)$mvv['percount'];
                }
                unset($mdatas);
                $gdatas = $group_orderDb->where('mer_id=' . $this->merchant['mer_id'] . ' AND add_time  >' . $startime . ' AND add_time  <=' . $nowtime . " AND status!=3")->field('count(order_id) as percount,FROM_UNIXTIME(add_time,"%m-%d") as perdate')->group('perdate')->select();
                foreach ($gdatas as $gvv) {
                    $newdatas[$gvv['perdate']] = isset($newdatas[$gvv['perdate']]) ? $newdatas[$gvv['perdate']] + $gvv['percount'] : $gvv['percount'];
                }
                break;
            case 'income' :
                $mdatas = $meal_orderDb->where('mer_id=' . $this->merchant['mer_id'] . ' AND paid="1" AND dateline >' . $startime . ' AND dateline <=' . $nowtime . " AND status!=3")->field('sum(if(total_price>0,total_price,price)) as tprice,sum(minus_price) as offprice,FROM_UNIXTIME(dateline,"%m-%d") as perdate')->group('perdate')->select();
                if (!empty($mdatas)) {
                    foreach ($mdatas as $mvv) {
                        $newdatas[$mvv['perdate']] = (int)$mvv['tprice'] - (int)$mvv['offprice'];
                    }
                }
                unset($mdatas);
                $gdatas = $group_orderDb->where('mer_id=' . $this->merchant['mer_id'] . ' AND paid="1" AND add_time  >' . $startime . ' AND add_time  <=' . $nowtime . " AND status!=3")->field('sum(total_money) as tprice,sum(wx_cheap) as offprice,FROM_UNIXTIME(add_time,"%m-%d") as perdate')->group('perdate')->select();
                if (!empty($gdatas)) {
                    foreach ($gdatas as $gvv) {
                        $perprice = $gvv['tprice'] - $gvv['offprice'];
                        $newdatas[$gvv['perdate']] = isset($newdatas[$gvv['perdate']]) ? $newdatas[$gvv['perdate']] + $perprice : $perprice;
                    }
                }
                break;
            case 'member' :
                $fansdata = M('')->table(array(C('DB_PREFIX') . 'merchant_user_relation' => 'm', C('DB_PREFIX') . 'user' => 'u'))->where("`m`.`openid`=`u`.`openid` AND `m`.`mer_id`='" . $this->merchant['mer_id'] . "' AND dateline >" . $startime . " AND dateline <=" . $nowtime)->field('count(dateline) as percount,FROM_UNIXTIME(dateline,"%m-%d") as perdate')->group('perdate')->select();
                if (!empty($fansdata)) {
                    foreach ($fansdata as $fvv) {
                        $newdatas[$fvv['perdate']] = (int)$fvv['percount'];
                    }
                }
                break;
            default:
                break;
        }
        $arr	=	array(
			'key'	=>	array_keys($newdatas),
			'value'	=>	array_values($newdatas),
        );
        $this->returnCode(0,$arr);
    }
    # 收入总数
	private function getallincomecount() {
	    $shopOrderDb= M('Shop_order');
		$group_orderDb = M('Group_order');
		$appoint_orderDb = M('Appoint_order');
		$foodshop_orderDb = M('Foodshop_order');
		$store_orderDb = M('Store_order');
		$tmp_m_price = $shopOrderDb->where('mer_id=' . $this->merchant['mer_id'] . ' AND paid="1" AND status != 0')->field('price as tprice')->find();
		$tmp_m_price['tprice'] = number_format($tmp_m_price['tprice']);
		$meal_price = $tmp_m_price['tprice'] ;
		$tmp_g_price = $group_orderDb->where('mer_id=' . $this->merchant['mer_id'] . ' AND paid="1" AND status != 3')->field('sum(total_money) as tprice')->find();
		$group_price = $tmp_g_price['tprice'] ;
		$tmp_g_price = $appoint_orderDb->where('mer_id=' . $this->merchant['mer_id'] . ' AND paid="1" AND status != 3')->field('sum(pay_money) as tprice')->find();
		$appoint_price = $tmp_g_price['tprice'] ;

        $tmp_g_price = $foodshop_orderDb->where('mer_id=' . $this->merchant['mer_id'] . '  AND (status = 3 OR status = 4)')->field('sum(total_price) as tprice')->find();
        $foodshop_price = $tmp_g_price['tprice'] ;

        $tmp_g_price = $store_orderDb->where('mer_id=' . $this->merchant['mer_id'] . '  AND paid  =1')->field('sum(price) as tprice')->find();
        $store_price = $tmp_g_price['tprice'] ;
		return ($meal_price + $group_price+$appoint_price+$foodshop_price+$store_price);
	}
    # 订单总数 月订单总数 日订单总数 粉丝数量
    private function getallordercount() {
        $shopOrderDb = M('Foodshop_order');
        $group_orderDb = M('Group_order');
        $shop_orderDb = M('Shop_order');
        $store_orderDb = M('Store_order');
        $income = M('Merchant_money_list');
        $meal_order_all = $shopOrderDb->where(array('mer_id' => $this->merchant['mer_id'], 'status' => array('neq', 0)))->count();
        $nowtime = time();
        $todaystartime = strtotime(date('Y-m-d'));
        $monthstartime = strtotime(date('Y-m') . '-01 00:00:00');
        $meal_order_m = $shopOrderDb->where('mer_id=' . $this->merchant['mer_id'] . ' AND status=3 AND create_time >' . $monthstartime . ' AND create_time <=' . $nowtime)->count();
        $meal_order_d = $shopOrderDb->where('mer_id=' . $this->merchant['mer_id'] . ' AND status=3 AND create_time >' . $todaystartime . ' AND create_time <=' . $nowtime)->count();
        $group_order_all = $group_orderDb->where(array('paid' => 1, 'mer_id' => $this->merchant['mer_id'], 'status' => array('neq', 3)))->count();
        $group_order_m = $group_orderDb->where('paid=1 AND mer_id=' . $this->merchant['mer_id'] . ' AND status!=3 AND add_time >' . $monthstartime . ' AND add_time <=' . $nowtime)->count();
        $group_order_d = $group_orderDb->where('paid=1 AND mer_id=' . $this->merchant['mer_id'] . ' AND status!=3 AND add_time >' . $todaystartime . ' AND add_time <=' . $nowtime)->count();

        $shop_order_all = $shop_orderDb->where(array('paid' => 1, 'mer_id' => $this->merchant['mer_id'], 'status' => array('neq', 4)))->count();
        $shop_order_m = $shop_orderDb->where('paid=1 AND mer_id=' . $this->merchant['mer_id'] . ' AND status!=4 AND create_time >' . $monthstartime . ' AND create_time <=' . $nowtime)->count();
        $shop_order_d = $shop_orderDb->where('paid=1 AND mer_id=' . $this->merchant['mer_id'] . ' AND status!=4 AND create_time >' . $todaystartime . ' AND create_time <=' . $nowtime)->count();

        $store_order_all = $store_orderDb->where(array('paid' => 1, 'mer_id' => $this->merchant['mer_id']))->count();
        $store_order_m = $store_orderDb->where('paid=1 AND mer_id=' . $this->merchant['mer_id'] . ' AND pay_time >' . $monthstartime . ' AND pay_time <=' . $nowtime)->count();
        $store_order_d = $store_orderDb->where('paid=1 AND mer_id=' . $this->merchant['mer_id'] . ' AND pay_time >' . $todaystartime . ' AND pay_time <=' . $nowtime)->count();

        $income_all = $income->where(array('type'=>1,'mer_id' => $this->merchant['mer_id']))->sum('money');
        $fans_count = M('')->table(array(C('DB_PREFIX') . 'merchant_user_relation' => 'm', C('DB_PREFIX') . 'user' => 'u'))->where("`m`.`openid`=`u`.`openid` AND `m`.`mer_id`='{$this->merchant['mer_id']}'")->count();
        $arr	=	array(
            'allordercount'	=>	intval($meal_order_all + $group_order_all+$shop_order_all+$store_order_all),
            'monthordercount'	=>	intval($meal_order_m + $group_order_m+$shop_order_m+$store_order_m),
            'todayordercount'	=>	intval($meal_order_d + $group_order_d+$shop_order_d+$store_order_d),
            'fans_count'	=>	$fans_count,
            'income_all'	=>	$income_all,

        );
        return $arr;
    }
    # 店铺列表
    public function store_list() {
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['status'] = array('neq', 4);
        $page = I('pindex', 1);
        $store_id = I('store_id', '');
        $store_id && $where['store_id'] = $condition['store_id'] = $store_id;
        $all = M('Merchant_store')->where($where)->count();
        $condition['mer_id'] = $this->merchant['mer_id'];
        if(isset($_POST['status']) && $_POST['status']>-1){
            $condition['status'] = $_POST['status'];
        }else{
            $condition['status'] =  $where['status'];
        }
        $data = M('Merchant_store')->field(true)
            ->where($condition)
            ->page($page, 10)
            ->select();
        $arr['data'] = isset($data) ? $data : array();
        $arr['all'] = $all;
        $where['status'] = 1;
        $arr['normal'] = M('Merchant_store')->where($where)->count();
        $where['status'] = 2;
        $arr['verify'] = M('Merchant_store')->where($where)->count();
        $where['status'] =0;
        $arr['close'] = M('Merchant_store')->where($where)->count();
        $arr['page'] = ceil($arr['all'] / 10);
        $this->returnCode(0, $arr);
    }
    # 店铺修改状态
    public function store_status(){
		$where['store_id']	=	I('store_id');
		$data['status']	=	I('status',1);
		//$data['status']	= $data['status'] == 1 ? 1 : 0;
		if($where['store_id']){
			$save	=	M('Merchant_store')->where($where)->data($data)->save();
		}else{
			$this->returnCode('20140029');
		}
		if($save){
			$this->returnCode(0);
		}else{
			$this->returnCode('20140028');
		}
    }
    # 快店列表
    public function shop_list() {
    	$where['mer_id']	=	$this->merchant['mer_id'];
    	$where['status']		=	1;
    	$where['have_shop']		=	1;
    	$page	=	I('pindex',1);
    	$all	=	M('Merchant_store')->where($where)->count();
        $data	=	M('Merchant_store')->field(array('`mer_id`,`name`,`store_id`,`status`,`phone`'))->where($where)->page($page,10)->select();
        foreach($data as &$v){
			$shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $v['store_id']))->find();
        	$store_theme = isset($shop['store_theme']) ? intval($shop['store_theme']) : 0;
        	if ($store_theme) {
        		$v['width'] = '900';
        		$v['height'] = '900';
        	} else {
        		$v['width'] = '900';
        		$v['height'] = '500';
        	}
		}
        $arr['data']	=	isset($data)?$data:array();
        $arr['all']		=	$all;
        $arr['page'] 	=	ceil($arr['all']/10);
        $this->returnCode(0,$arr);
    }
    # 快店详情
    public function store_details(){
    	$store_id	=	I('store_id');
    	if(empty($store_id)){
			$this->returnCode('20140029');
    	}
        $data = M('Merchant_store')->where(array('store_id' => $store_id, 'mer_id' => $this->merchant['mer_id']))->find();
        if (!empty($data)) {
            $data['office_time'] = unserialize($data['office_time']);
            if (!empty($data['pic_info'])) {
                $store_image_class = new store_image();
                $tmp_pic_arr = explode(';', $data['pic_info']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $data['pic'][$key] = "'" . $store_image_class->get_image_by_path($value) . "'";
                }
//                $data['picstr'] = implode(',', $data['pic']);
            }
        }
        $keywords = D('Keywords')->where(array('third_type' => 'Merchant_store', 'third_id' => $data['store_id']))->select();
        $str = "";
        foreach ($keywords as $key) {
            $str .= $key['keyword'] . " ";
        }
        $arr	=	array(
			'store_id'	=>	$data['store_id'],		//商铺ID
			'mer_id'	=>	$data['mer_id'],		//商户ID
			'ismain'	=>	$data['ismain'],		//是否是主点	1主店 0不是主店
			'phone'		=>	$data['phone'],			//手机
			'weixin'	=>	$data['weixin'],		//微信
			'qq'		=>	$data['qq'],			//QQ
			'keywords'	=>	$str,					//关键词
			'permoney'	=>	$data['permoney'],		//人均消费
			'feature'	=>	$data['feature'],		//店铺特色
			'province_id'=>	$data['province_id'],	//省
			'city_id'	=>	$data['city_id'],		//市
			'area_id'	=>	$data['area_id'],		//区
			'circle_id'	=>	$data['circle_id'],		//商圈
			'adress'	=>	$data['adress'],		//地址
			'trafficroute'=>$data['trafficroute'],	//交通路线
			'sort'		=>	$data['sort'],			//排序
			'have_meal'	=>	$data['have_meal'],		//餐饮是否开启  0关闭  1开启
			'have_group'=>	$data['have_group'],	//团购是否开启  0关闭  1开启
			'open_1'	=>	$data['open_1'],		//打开时间1
			'open_2'	=>	$data['open_2'],		//打开时间2
			'open_3'	=>	$data['open_3'],		//打开时间3
			'close_1'	=>	$data['close_1'],		//结束时间1
			'close_2'	=>	$data['close_2'],		//结束时间2
			'close_3'	=>	$data['close_3'],		//结束时间3
			'lat'		=>	$data['lat'],			//经
			'long'		=>	$data['long'],			//纬
			'txt_info'	=>	$data['txt_info'],		//简介
			'pic'		=>	isset($data['pic'])?$data['pic']:array(),//图片
        );
        $this->returnCode(0,$arr);
    }
    # 快店二维码
    public function erwm($id='',$type='meal') {
        $type = trim($type);
        $id = trim(2);
        if ($type == 'group') {
            $pigcms_return = D('Group')->get_qrcode($id);
        } elseif ($type == 'meal') {
            $pigcms_return = D('Merchant_store')->get_qrcode($id);
        }

        if (empty($pigcms_return['qrcode_id'])) {
            $qrcode_return = D('Recognition')->get_new_qrcode($type, $id);
        } else {
            $qrcode_return = D('Recognition')->get_qrcode($pigcms_return['qrcode_id']);
        }
        if(empty($qrcode_return['error_code'])){
			return $qrcode_return['qrcode'];
        }
        return '';
    }
    # 快店订单
    public function sorder(){
    	$shop_order_obj = D('Shop_order');
        $store_id	=	I('store_id');
        $status	=	I('status');
        $keyword	=	I('keyword');
        $status = isset($status) ? trim($status) : 'all';
        $keyword = isset($keyword) ? trim($keyword) : '';
        $where = '`s`.`store_id`=' . $store_id;
        if ($status != 'all') {
            $status = intval($status);
            if ($status == 0) {
                $where.=' AND (`s`.`paid`="0" OR (`s`.`third_id` ="0" AND `s`.`pay_type`="offline"))';
            } elseif ($status == 1) {
                $where.=' AND `s`.`status`<2';
            } else {
                $where.=' AND `s`.`status`=' . $status;
            }
        }
        if (!empty($keyword)) {
            $where.=' AND (`s`.`userphone` like "%' . $keyword . '%" OR `s`.`username` like "%' . $keyword . '%")';
        }
        //订单列表
        $tp_count = "SELECT COUNT(*) as tp_count FROM " . C('DB_PREFIX') . "merchant_store AS ms INNER JOIN " . C('DB_PREFIX') . "shop_order AS s ON `s`.`store_id`=`ms`.`store_id` WHERE {$where}";
        $order_count = $shop_order_obj->query($tp_count);
        $pindex	=	I('pindex');
        $pindex = max(1, intval(trim($pindex)));
        $pagsize = 10;
        $offsize = ($pindex - 1) * 10;
        $sql = "SELECT `s`.*, `ms`.`name` AS storename FROM " . C('DB_PREFIX') . "merchant_store AS ms INNER JOIN " . C('DB_PREFIX') . "shop_order AS s ON `s`.`store_id`=`ms`.`store_id` WHERE {$where} ORDER BY `s`.`order_id` DESC LIMIT {$offsize}, {$pagsize}";
        $order_list = $shop_order_obj->query($sql);
        $hasmore = $order_count[0]['tp_count'] > ($pindex * $pagsize) ? 1 : 0;
        $newdatas = array();
		if (!empty($order_list)) {
			foreach ($order_list as $kk => $vv) {
				$order_status	=	'';
				$temp = array();
				$temp['order_status'] = '';
				if ($vv['paid']) {
					if (empty($vv['third_id']) && $vv['pay_type'] == 'offline') {
						$temp['order_status'] = '线下未付款';
					} else {
						$temp['order_status'] = '已付款';
					}
				}
				switch ($vv['status']) {
					case 0:
						$order_status	= '未确认';
						break;
					case 1:
						$order_status	= '已确认';
						break;
					case 2:
						$order_status	= '已消费';
						break;
					case 3:
						$order_status	= '已评价';
						break;
					case 4:
						$order_status	= '已退款';
						break;
					case 5:
						$order_status	= '已取消';
						break;
				}
				$temp['order_statuss'] =	isset($order_status)?$order_status:'';
				$temp['order_id'] = $vv['order_id'];
                $temp['nickname'] = $vv['username'];
                $temp['storename'] = $vv['storename'];
                $temp['phone'] = $vv['userphone'];
                $temp['address'] = $vv['address'];
                $temp['final_price'] = $vv['price'];
                $temp['num'] = $vv['num'] . '道菜';
                $temp['created'] = date('Y-m-d H:i:s', $vv['create_time']);
                $newdatas[] = $temp;
            }
        }
        unset($order_list);
        $arr	=	array(
			'order_count'	=>	$order_count[0]['tp_count'],
			'page'	=>	ceil($order_count[0]['tp_count']/10),
			'list'	=>	$newdatas,
        );
        $this->returnCode(0,$arr);
	}
	# 快店订单详情
	public function sdetail(){
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $this->merid = $this->merchant['mer_id'];
        $order = D("Shop_order")->get_order_detail(array('mer_id' => $this->merid, 'order_id' => $order_id));
        if($order){
    		if($order['pay_type'] == 'offline' && empty($order['third_id'])){
				$payment	=	floatval($order['price']-$order['card_price']-$order['merchant_balance']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-floatval($order['coupon_price']));
    		}
			$arr['order_details']	=	array(
				'orderid'	=>	$order['orderid'],
				'order_id'	=>	$order['order_id'],
				'real_orderid'	=>	$order['real_orderid'],
				'username'	=>	$order['username'],
				'userphone'	=>	$order['userphone'],
				'create_time'	=>	date('Y-m-d H:i:s',$order['create_time']),
				'pay_time'	=>	date('Y-m-d H:i:s',$order['pay_time']),
				'expect_use_time'	=>	$order['expect_use_time']!=0 ? (date('Y-m-d H:i:s',$order['expect_use_time'])) : '0',
				'is_pick_in_store'	=>	$order['is_pick_in_store'],
				'address'	=>	$order['address'],
				'deliver_str'	=>	$order['deliver_str'],
				'deliver_status_str'	=>	$order['deliver_status_str'],
				'note'	=>	isset($order['desc'])?$order['desc']:'',
				'invoice_head'	=>	$order['invoice_head'],
				'pay_status'	=>	$order['pay_status_print'],
				'pay_type_str'	=>	$order['pay_type_str'],
				'status_str'	=>	$order['status_str'],
				'score_used_count'	=>	$order['score_used_count'],
				'score_deducte'	=>	floatval($order['score_deducte']),
				'merchant_balance'	=>	floatval($order['merchant_balance']),
				'balance_pay'	=>	floatval($order['balance_pay']),
				'payment_money'	=>	floatval($order['payment_money']),
				'card_id'	=>	$order['card_id'],
				'card_price'	=>	$order['card_price'],
				'coupon_price'	=>	$order['coupon_price'],
				'payment'	=>	isset($payment)?$payment:0,
				'use_time'	=>	$order['use_time']!=0 ? (date('Y-m-d H:i:s',$order['use_time'])) : '0',
				'last_staff'	=>	$order['last_staff'],
				'status'	=>	$order['status'],
				'paid'	=>	$order['paid'],
				'goods_price'	=>	floatval($order['goods_price']),
				'freight_charge'	=>	floatval($order['freight_charge']),
				'total_price'	=>	floatval($order['total_price']),
				'merchant_reduce'	=>	floatval($order['merchant_reduce']),
				'balance_reduce'	=>	floatval($order['balance_reduce']),
				'price'	=>	floatval($order['price']),
				'notes'	=>	'注：改成已消费状态后同时如果是未付款状态则修改成线下支付已支付，状态修改后就不能修改了',
			);
			foreach($order['info'] as $k=>$v){
				$arr['info'][]	=	array(
					'name'	=>	$v['name'],
					'price'	=>	floatval($v['price']),
					'num'	=>	$v['num'],
					'total'	=>	floatval($v['price']*$v['num']),
				);
			}
    	}else{
			$arr['order_details']	=	array();
    	}
    	if(empty($arr['info'])){
			$arr['info']	=	array();
    	}
    	$this->returnCode(0,$arr);
    }
	# 快店商品分类
	public function goods_sort(){
		$page	=	I('pindex',1);
		$store_id	=	I('store_id');
		$arr	=	array();
		if($store_id){
			$database_goods_sort = D('Shop_goods_sort');
			$sort_image_class = new goods_sort_image();
			$condition_goods_sort['store_id'] = $store_id;
			$count = $database_goods_sort->field(true)->where($condition_goods_sort)->count();
			$sort_list = $database_goods_sort->field(true)->where($condition_goods_sort)->order('`sort` DESC,`sort_id` ASC')->page($page,10)->select();
			if($sort_list){
				foreach ($sort_list as $key => $value) {
					if (!empty($value['week'])) {
						$week_arr = explode(',',$value['week']);
						$week_str = '';
						foreach ($week_arr as $k=>$v){
							$week_str .= $this->get_week($v).' ';
						}
						$value['week_str'] = $week_str;
					}
					$image	=	$sort_image_class->get_image_by_path($value['image'],$this->config['site_url'],'s');
					$value['image'] = $image===false?'':$image;
					$arr['list'][]	=	$value;
				}
			}
		}else{
			$this->returnCode('20140029');
		}
		$arr['count']	=	ceil($count/10);
		$this->returnCode(0,$arr);
	}
	# 添加商品分类
	public function sort_add(){
		$store_id	=	I('store_id');
		$sort_name	=	I('sort_name');
		$sort		=	I('sort');
		$is_weekshow=	I('is_weekshow');
		$week		=	I('week');
		$sort_discount=	I('sort_discount');
		if (empty($sort_name)) {
			$this->returnCode('20140030');
		} else {
			$database_goods_sort = D('Shop_goods_sort');
			$data_goods_sort['store_id'] = $store_id;
			$data_goods_sort['sort_name'] = $sort_name;
			$data_goods_sort['sort'] = intval($sort);
			$data_goods_sort['is_weekshow'] = intval($is_weekshow);
			$data_goods_sort['sort_discount'] = intval($sort_discount);
			if ($week) {
				$data_goods_sort['week'] = $week;
			}
			if ($database_goods_sort->data($data_goods_sort)->add()) {
				$this->returnCode(0);
			}else{
				$this->returnCode('20140031');
			}
		}
	}
	# 修改商品分类
	public function sort_edit(){
		$sort_id	=	I('sort_id');
		$sort_name	=	I('sort_name');
		$sort		=	I('sort');
		$is_weekshow=	I('is_weekshow');
		$week		=	I('week');
		$sort_discount=	I('sort_discount');
		if (empty($sort_name)) {
			$this->returnCode('20140030');
		} else {
			$database_goods_sort = D('Shop_goods_sort');
			$data_goods_sort['sort_name'] = $sort_name;
			$data_goods_sort['sort'] = intval($sort);
			$data_goods_sort['is_weekshow'] = intval($is_weekshow);
			$data_goods_sort['sort_discount'] = intval($sort_discount);
			if ($week) {
				$data_goods_sort['week'] = $week;
			}
			//$files	=	move_uploaded_file($_FILES['file']['tmp_name'], "./upload/".$_FILES["file"]["name"]);
//			if(empty($files)){
//				$this->returnCode('20140032');
//			}
//			$image	=	$this->config['site_url']."/upload/".$_FILES["file"]["name"];
//			$data_goods_sort['image'] = $image;
			if ($database_goods_sort->where(array('sort_id'=>$sort_id))->data($data_goods_sort)->save()) {
				$this->returnCode(0);
			} else {
				$this->returnCode('20140033');
			}
		}
	}
	# 删除分类
	public function sort_del(){
		$sort_id	=	I('sort_id');
		$store_id	=	I('store_id');
		if(empty($sort_id)){
			$this->returnCode('20140034');
		}
		if(empty($store_id)){
			$this->returnCode('20140033');
		}
		$count = D('Shop_goods')->where(array('sort_id' => $sort_id, 'store_id' => $store_id))->count();
		if ($count){
			$this->returnCode('20140035');
		}
		$database_goods_sort = D('Shop_goods_sort');
		$condition_goods_sort['sort_id'] = $sort_id;
		if ($database_goods_sort->where($condition_goods_sort)->delete()) {
			$this->returnCode(0);
		} else {
			$this->returnCode('20140036');
		}
	}
	# 快店商品分类下的商品
	public function goods_list(){
		$sort_id	=	I('sort_id');
		$store_id	=	I('store_id');
		$page		=	I('pindex',1);
		$database_goods = D('Shop_goods');
		$condition_goods['sort_id'] = $sort_id;
		$count_goods = $database_goods->where($condition_goods)->count();
		$goods_list = $database_goods->field(true)->where($condition_goods)->order('`sort` DESC, `goods_id` ASC')->page($page,10)->select();
		if(empty($goods_list)){
			$arr['list']	=	array();
			$arr['count']	=	0;
		}else{
			$plist = array();
			$sort_image_class = new goods_image();
			$prints = D('Orderprinter')->where(array('mer_id' => $this->merchant['mer_id'], 'store_id' => $store_id))->select();
			foreach ($prints as $l) {
				if ($l['is_main']) {
					$l['name'] .= '(主打印机)';
				} else {
					$l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
				}
				$plist[$l['pigcms_id']] = $l;
			}
			foreach ($goods_list as &$rl) {
				$image_tmp = explode(';', $rl['image']);
				foreach($image_tmp as $v){
					$tmp_image	=	$sort_image_class->get_image_by_path($v,'-1');
					$image[]	=	array(
						'url'	=>	$tmp_image['image'],
						'sql'	=>	$v,
					);
				}

				$rl['images'] = $image===false?'':$image;
				$arr['list'][] = array(
					'print_name'=>	isset($plist[$rl['print_id']]['name']) ? $plist[$rl['print_id']]['name'] : '',
					'goods_id'	=>	$rl['goods_id'],
					'sort_id'	=>	$rl['sort_id'],
					'store_id'	=>	$rl['store_id'],
					'number'	=>	$rl['number'],
					'name'		=>	$rl['name'],
					'price'		=>	strval(floatval($rl['price'])),
					'unit'		=>	$rl['unit'],
					'stock_num'	=>	$rl['stock_num'],
					'sell_count'=>	$rl['sell_count'],
					'last_time'=>	date('Y-m-d H:i:s',$rl['last_time']),
					'unit'		=>	$rl['unit'],
					'old_price'	=>	strval(floatval($rl['old_price'])),
					'seckill_price'	=>	strval(floatval($rl['seckill_price'])),
					'seckill_open_time'=>	date('Y-m-d H:i:s',$rl['seckill_open_time']),
					'seckill_close_time'=>	date('Y-m-d H:i:s',$rl['seckill_close_time']),
					'seckill_type'=>	$rl['seckill_type'],
					'seckill_stock'=>	$rl['seckill_stock'],
					'sort'=>	$rl['sort'],
					'status'=>	$rl['status'],
					'sell_mouth'=>	$rl['sell_mouth'],
					'today_sell_count'=>	$rl['today_sell_count'],
					'reply_count'=>	$rl['reply_count'],
					'is_properties'=>	$rl['is_properties'],
					'number'	=>	$rl['number'],
					'image'		=>	$image,
				);
				unset($image);
			}
			$arr['count']	=	$count_goods;
			$arr['page'] 	=	ceil($count_goods/10);
		}
		$this->returnCode(0,$arr);
	}
	# 商品状态
	public function goods_status(){
		$goods_id	=	I('goods_id');
		if($goods_id){
			$this->returnCode('20140023');
		}
		$type	=	I('type',1);
		$database_goods = D('Shop_goods');
		$condition_goods['goods_id'] = $goods_id;
		$data_goods['status'] =	$type;
		if($database_goods->where($condition_goods)->data($data_goods)->save()){
			$this->returnCode(0);
		}else{
			$this->returnCode('20140024');
		}
	}
	# 添加店铺商品
	public function goods_add(){
		$sort_id	=	I('sort_id');	//分类ID
		$store_id	=	I('store_id');	//店铺ID
		$name		=	I('name');		//商品名
		$unit		=	I('unit');		//单位
		$old_price	=	I('price');		//老价格
		$price		=	I('price');		//新价格
		$stock_num	=	'-1';			//库存
		$pic		=	I('pic');		//图片
		if(empty($sort_id)){
			$this->returnCode('20140034');
		}
		if(empty($store_id)){
			$this->returnCode('20140029');
		}
		if (empty($name)) {
			$this->returnCode('20140037');
		}
		if (empty($unit)) {
			$this->returnCode('20140038');
		}
		if (empty($price)) {
			$this->returnCode('20140039');
		}
        if (empty($pic)) {
            $this->returnCode('20140040');
        }
		$arr	=	array(
			'sort_id'	=>	$sort_id,
			'store_id'	=>	$store_id,
			'name'		=>	$name,
			'unit'		=>	$unit,
			'old_price'	=>	$old_price,
			'price'		=>	$price,
			'stock_num'	=>	$stock_num,
			'image'		=>	$pic,
			'last_time'	=>	$_SERVER['REQUEST_TIME'],
		);
		$goods_id = D('Shop_goods')->data($arr)->add();
		if ($goods_id) {
			$this->returnCode(0);
		} else {
			$this->returnCode('20140041');
		}
	}
	/* 编辑商品 */
	public function goods_edit(){
		$goods_id	=	I('goods_id');
		if(empty($goods_id)){
			$this->returnCode('20140023');
		}
		$sort_id	=	I('sort_id');
		$store_id	=	I('store_id');
		$name		=	I('name');
		$number		=	I('number');
		$unit		=	I('unit');
		$old_price	=	I('old_price');
		$price		=	I('price');
//		$stock_num	=	I('stock_num');
		$pic		=	I('pic');
		$des		=	I('des');
		$print_id	=	I('print_id');
		$specs		=	I('specs');
		$spec_val	=	I('spec_val');
		$properties	=	I('properties');
		$properties_val	=	I('properties_val');
		$prices		=	I('prices');
		if (empty($name)) {
			$this->returnCode('20140037');
		}
		if (empty($unit)) {
			$this->returnCode('20140038');
		}
		if (empty($price)) {
			$this->returnCode('20140039');
		}
        if (empty($pic)) {
            $this->returnCode('20140040');
        }
		$arr	=	array(
			'sort_id'	=>	$sort_id,
			'store_id'	=>	$store_id,
			'name'		=>	$name,
			'number'	=>	$number,
			'unit'		=>	$unit,
			'old_price'	=>	$old_price,
			'price'		=>	$price,
//			'stock_num'	=>	$stock_num,
			'image'		=>	$pic,
			'last_time'	=>	$_SERVER['REQUEST_TIME'],
		);
		$goods_id = D('Shop_goods')->where(array('goods_id'=>$goods_id))->data($arr)->save();
		if ($goods_id) {
			$this->returnCode(0);
		} else {
			$this->returnCode('20140041');
		}
	}
	# 删除店铺商品
	public function goods_del(){
		$goods_id	=	I('goods_id');
		$store_id	=	I('store_id');
		$database_goods = D('Shop_goods');
		$condition_goods['goods_id'] = $goods_id;
		if ($database_goods->where($condition_goods)->delete()) {
			$spec_obj = M('Shop_goods_spec'); //规格表
			$old_spec = $spec_obj->field(true)->where(array('goods_id' => $goods_id, 'store_id' => $store_id))->select();
			foreach ($old_spec as $os) {
				$delete_spec_ids[] = $os['id'];
			}
			$spec_obj->where(array('goods_id' => $goods_id, 'store_id' => $store_id))->delete();
			if ($delete_spec_ids) {
				$old_spec_val = M('Shop_goods_spec_value')->where(array('sid' => array('in', $delete_spec_ids)))->delete();
			}
			M('Shop_goods_properties')->where(array('goods_id' => $goods_id))->delete();
			$this->returnCode(0);
		}else{
			$this->returnCode('20140045');
		}
	}
    # 团购列表
    public function glist() {
        $database_group = D('Group');
        $condition_group['mer_id'] = $this->merchant['mer_id'];
        $keyword	=	I('keyword');
        $keyword 	=	isset($keyword) ? trim($keyword) : '';
        if (!empty($keyword)) {
            $condition_group.=' AND (s_name like "%' . $keyword . '%" OR name like "%' . $keyword . '%")';
        }
        $group_count = $database_group->where($condition_group)->count();
        $page	=	I('pindex',1);
        $group_list = $database_group->field(true)->where($condition_group)->order('`group_id` DESC')->page($page,10)->select();

        $group_image_class = new group_image();
        foreach ($group_list as $key => $value) {
            $tmp_pic_arr = explode(';', $value['pic']);
            if($value['begin_time'] > $_SERVER['REQUEST_TIME']){
				$type	=	'未开团';
            }else if($value['end_time'] < $_SERVER['REQUEST_TIME']){
				$type	=	'已结束';
            }else if($value['type'] == 3){
				$type	=	'已结束';
            }else if($value['type'] == 4){
				$type	=	'结束失败';
            }else{
				$type	=	'进行中';
            }
            $tmp_group_list[]	=	array(
				'group_id'	=>	$value['group_id'],
				's_name'	=>	$value['s_name'],
				'price'		=>	$value['price'],
				'old_price'	=>	$value['old_price'],
				'sale_count'	=>	$value['sale_count'],
				'count_num'	=>	$value['count_num'],	//库存	0是无限制
				'virtual_num'	=>	$value['virtual_num'],
				'begin_time'	=>	date('Y-m-d H:i:s',$value['begin_time']),
				'end_time'	=>	date('Y-m-d H:i:s',$value['end_time']),
				'deadline_time'	=>	date('Y-m-d H:i:s',$value['deadline_time']),
				'hits'		=>	$value['hits'],
				'reply_count'	=>	$value['reply_count'],
				'qrcode'	=>	$this->config['site_url'].'/index.php?g=Index&c=Recognition&a=see_qrcode&type=group&id='.$value['group_id'].'&img=1',
				'type'		=>	$type,
				'status'	=>	$value['status'],
				'list_pic'	=>	$group_image_class->get_image_by_path($tmp_pic_arr[0], 's'),
            );
        }
		$arr	=	array(
			'group_list'	=>	isset($tmp_group_list)?$tmp_group_list:array(),
			'group_count'	=>	$group_count,
			'page' 	=>	ceil($group_count/10),
		);
        $this->returnCode(0,$arr);
    }
    # 团购状态
    public function gorder_status(){
    	$condition_group['group_id']	=	I('group_id');
    	$data['status']	=	I('status',1);
    	$database_group = D('Group');
    	if($condition_group){
			$save	=	$database_group->where($condition_group)->data($data)->save();
    	}else{
			$this->returnCode('20140049');
    	}
    	if($save){
			$this->returnCode(0);
    	}else{
			$this->returnCode('20140028');
    	}
    }
    # 团购订单
    public function gorder() {
        $group_id	=	I('group_id');
        if(empty($group_id)){
			$this->returnCode('20140048');
        }
        $status	=	I('status');
        $keyword	=	I('keyword');
        $status = isset($status) ? trim($status) : 'all';
        $keyword = isset($keyword) ? trim($keyword) : '';
        $where = 'gord.group_id=' . $group_id;
        if ($status != 'all') {
            $status = intval($status);
            if ($status == 0) {
                $where.=' AND (gord.paid="0" OR (gord.third_id ="0" AND gord.pay_type="offline"))';
            } else {
                $where.=' AND gord.status="' . ($status - 1) . '"';
            }
        }
        if (!empty($keyword)) {
            $where.=' AND (gord.phone like "%' . $keyword . '%" OR u.nickname like "%' . $keyword . '%" OR u.truename like "%' . $keyword . '%")';
        }
        //订单列表
        $group_orderDb = M('Group_order');
        $jointable = C('DB_PREFIX') . 'user';
        $group_orderDb->join('as gord LEFT JOIN ' . $jointable . ' as u on gord.uid=u.uid');
        $order_count = $group_orderDb->where($where)->count();
        $pindex	=	I('pindex');
        $pindex = intval(trim($pindex));
        $pindex = $pindex > 0 ? $pindex : 1;
        $pagsize = 10;
        $offsize = ($pindex - 1) * 20;
        $newdatas = array();
        $group_orderDb->join('as gord LEFT JOIN ' . $jointable . ' as u on gord.uid=u.uid');
        $order_list = $group_orderDb->field('gord.*,u.nickname,u.truename')->where($where)->order('gord.add_time DESC')->limit($offsize . ',' . $pagsize)->select();
//        $hasmore = $order_count > ($pindex * $pagsize) ? 1 : 0;
        if (!empty($order_list)) {
            foreach ($order_list as $kk => $vv) {
            	$order_statuss	=	'';
                if ($vv['status'] == 3) {
                    $newdatas[$kk]['order_status'] = '已取消';
                } elseif ($vv['status'] == 4) {
                    $newdatas[$kk]['order_status'] = '已删除';
                } elseif ($vv['paid'] > 0) {
                    if (($vv['third_id'] == "0") && ($vv['pay_type'] == 'offline')) {
                        $newdatas[$kk]['order_status'] = '线下未付款';
                    } elseif ($vv['status'] == 0) {
                        $newdatas[$kk]['order_status'] = '已付款';
                        if ($vv['tuan_type'] != 2) {
                            $order_statuss='未消费';
                        } else {
                            $order_statuss='未发货';
                        }
                    } elseif ($vv['status'] == 1) {
                        if ($vv['tuan_type'] != 2) {
                            $newdatas[$kk]['order_status'] = '已消费';
                        } else {
                            $newdatas[$kk]['order_status'] = '已发货';
                        }
                    } else {
                        $newdatas[$kk]['order_status'] = '已完成';
                    }
                } else {
                    $newdatas[$kk]['order_status'] = '未付款';
                }
                $newdatas[$kk]['order_statuss']	=	isset($order_statuss)?$order_statuss:'';
                $newdatas[$kk]['order_id'] = $vv['order_id'];
                $newdatas[$kk]['nickname'] = !empty($vv['truename']) ? $vv['truename'] : $vv['nickname'];
                $newdatas[$kk]['phone'] = $vv['phone'];
                $newdatas[$kk]['address'] = $vv['adress'];
                $newdatas[$kk]['final_price'] = $vv['total_money'] - $vv['wx_cheap'];
                $newdatas[$kk]['num'] = $vv['num'] . '份';
                $newdatas[$kk]['created'] = date('Y-m-d H:i:s', $vv['add_time']);
            }
        }
        unset($order_list);
        $arr	=	array(
        	'order_count'	=>	$order_count,
			'list'		=>	$newdatas,
			'page' 	=>	ceil($order_count/10),
        );
        $this->returnCode(0,$arr);
    }
    #团购列表

    public function group_list()
    {
        $mer_id = $this->merchant['mer_id'];

        $condition_where = "mer_id='$mer_id'";
        $page	=	I('pindex',1);
        if($_POST['keyword']){
            switch($_POST['searchtype']){
                case 'group_id':
                    $condition_where .= " AND `group_id`=" . intval($_POST['keyword']);
                    break;
                case 's_name':
                    $condition_where .= " AND `s_name` LIKE '%" . $_POST['keyword'] . "%'";
                    break;
                case 'name':
                    $condition_where .= " AND `name` LIKE '%" . $_POST['keyword'] . "%'";
                    break;
            }
        }

        isset($_POST['status']) && $condition_where .= " AND status = ".$_POST['status'];

        $count = M('Group')->where($condition_where)->count();

        $list = M('Group')
            ->field('group_id,name,s_name,old_price,price,status,sale_count,pic,status,begin_time,end_time')
            ->where($condition_where)
            ->page($page, '10')
            ->order('group_id DESC')
            ->select();
        $group_image_class = new group_image();
        foreach ($list as &$v) {
            if($v['begin_time'] > $_SERVER['REQUEST_TIME']){
                $group_status = '未开团';
            }else if($v['end_time'] < $_SERVER['REQUEST_TIME']){
                $group_status = '已结束';
            }else if($v['status'] == 3){
                $group_status = '已结束';
            }else if($v['status'] == 4){
                $group_status = '结束失败';
            }else{
                $group_status = '进行中';
            }
            $v['group_status_txt'] = $group_status;
            $tmp_pic_arr = explode(';', $v['pic']);
            $v['pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
        }
        if (empty($list)) {
            $arr['group_list'] = array();
        } else {
            $arr['group_list'] = $list;
        }
        $arr['page'] = ceil($count / 10);
        $arr['count'] = $count;
        $arr['status'] = 1;
        $this->returnCode(0, $arr);
    }


    #团购订单列表

    public function group_order_list()
    {
        $mer_id = $this->merchant['mer_id'];
        $order_id = $_POST['order_id'];
        $group_id = $_POST['group_id'];
        $page	=	I('pindex',1);
        $condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `o`.`mer_id`='$mer_id'";
        $group_id && $condition_where.=' AND o.group_id = '.$group_id;
        $order_id && $condition_where.=' AND o.order_id = '.$order_id;
        $condition_table = array(
            C('DB_PREFIX') . 'group' => 'g',
            C('DB_PREFIX') . 'group_order' => 'o',
            C('DB_PREFIX') . 'user' => 'u'
        );

        if(!empty($_POST['keyword'])){
            if ($_POST['searchtype'] == 'real_orderid') {
                $condition_where .= " AND `o`.`real_orderid`='" . htmlspecialchars($_POST['keyword'])."'";
            } elseif ($_POST['searchtype'] == 'orderid') {
                $where['orderid'] = htmlspecialchars($_POST['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_POST['keyword']))->find();
                $condition_where .= " AND `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
            } elseif ($_POST['searchtype'] == 'name') {
                $condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_POST['keyword']) . "%'";
            } elseif ($_POST['searchtype'] == 'phone') {
                $condition_where .= " AND `u`.`phone`='" . htmlspecialchars($_POST['keyword']) . "'";
            } elseif ($_POST['searchtype'] == 's_name') {
                $condition_where .= " AND `g`.`s_name` like '%" . htmlspecialchars($_POST['keyword']) . "%'";
            }elseif ($_POST['searchtype'] == 'third_id') {
                $condition_where .= " AND `o`.`third_id` ='".$_POST['keyword']."'";
            }elseif ($_POST['searchtype'] == 'express_id') {
                $condition_where .= " AND `o`.`express_id` ='".$_POST['keyword']."'";
            }
        }

        $count = D('')->where($condition_where)
            ->table($condition_table)
            ->count();


        $order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`g`.`pic`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')
            ->where($condition_where)
            ->table($condition_table)
            ->page($page, '10')
            ->order('`o`.`add_time` DESC')
            ->select();
        if (empty($order_list)) {
            $arr['order_list'] = array();
        } else {
            $arr['order_list'] = $this->group_format($order_list);
        }
        $arr['page'] = ceil($count / 10);
        $arr['count'] = $count;
        $arr['status'] = 1;
        $this->returnCode(0, $arr);
    }


    private function group_format($order_list)
    {
        if ($order_list) {
            foreach ($order_list as $v) {
                $status_format = $this->status_format($v['status'], $v['paid'], $v['third_id'],$v['pay_type'], $v['tuan_type'],$v['express_id'],$v['is_pick_in_store']);
                $group_image_class = new group_image();
                $all_pic = $group_image_class->get_allImage_by_path($v['pic']);
                $arr[] = array(
                    's_name' => $v['s_name'], // 名称
                    'num' => $v['num'], // 数量
                    'total_money' => $v['total_money'], // 总价
                    'status' => $status_format['status'], // 状态
                    'type' => $status_format['type'], // 团购券状态
                    'type_txt' => $status_format['type_txt'], // 团购券状态
                    'status_txt' => $status_format['status_txt'], // 团购券状态
                    'order_id' => $v['order_id'], // 团购ID
                    'pic' => $all_pic[0]['image'], // 图片
                    'pass_array' => $v['pass_array'], // 判断多个优惠券还是单个
                    'is_pick_in_store' => $v['is_pick_in_store'], // 取货确认
                    'tuan_type' => $v['tuan_type'], // 取货确认
                    'express' => $v['tuan_type'], // 取货确认
                );
            }
            return $arr;
        }
        return array();
    }


    public function group_status_change(){

        if($this->config['group_verify'] == 1 && $_POST['status']){
            $_POST['status'] = 2;
        }
        M('Group')->save($_POST);
        $this->returnCode(0,array('status'=>$_POST['status']));
    }
    # 团购订单详情
    public function group_edit() {
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant['mer_id'],$order_id,false);
        if (empty($now_order)) {
            $this->returnCode('20140026');
        }
        $arr['store_id']	=	$now_order['store_id'];
        $arr['order_id']	=	$now_order['order_id'];
        $arr['s_name']	=	$now_order['s_name'];
        if($now_order['tuan_type'] == 0){
			$now_order['tuan_type']	=	$this->config['group_alias_name'].'券';
        }else if($now_order['tuan_type'] == 1){
			$now_order['tuan_type']	=	'代金券';
        }else{
			$now_order['tuan_type']	=	'实物';
        }
        if($now_order['status'] == 3){
			$arr['status']	=	'已取消';
        }else if($now_order['paid'] == 1){
        	if($now_order['third_id'] == 0 && $now_order['pay_type'] == 'offline'){
				$arr['status']	=	'线下未付款';
        	}else if($now_order['status'] == 0){
				$arr['status']	=	'已付款';
				if($now_order['tuan_type'] != 2){
					$arr['statuss']	=	'未消费';
				}else{
					$arr['statuss']	=	'未发货';
				}
        	}else if($now_order['status'] == 1){
				$arr['status']	=	'待评价';
				if($now_order['tuan_type'] != 2){
					$arr['statuss']	=	'已消费';
				}else{
					$arr['statuss']	=	'已发货';
				}
        	}else{
				$arr['status']	=	'已完成';
        	}
        }else{
			$arr['status']	=	'未付款';
        }
        $arr['num']	=	$now_order['num'];
        $arr['price']	=	$now_order['price'];
        $arr['total_money']	=	$now_order['total_money'];
        $arr['score_used_count']	=	$now_order['score_used_count'];
        $arr['score_deducte']	=	$now_order['score_deducte'];
        if(!empty($now_order['coupon_id'])) {
            $system_coupon = D('System_coupon')->get_coupon_info($now_order['coupon_id']);
			$arr['system_coupon']	=	$system_coupon['price'];
        }else if(!empty($now_order['card_id'])) {
            $card = D('Member_card_coupon')->get_coupon_info($now_order['card_id']);
			$arr['card_coupon']	=	$card['price'];
        }
        if(empty($arr['system_coupon'])){
			$arr['system_coupon']	=	'';
        }
        if(empty($arr['card_id'])){
			$arr['card_coupon']	=	'';
        }
        $arr['pay']	=	$now_order['payment_money']+$now_order['balance_pay'];
        $arr['add_time']	=	date('Y-m-d H:i:s',$now_order['add_time']);
        $arr['pay_time']	=	date('Y-m-d H:i:s',$now_order['pay_time']);
        $arr['use_time']	=	date('Y-m-d H:i:s',$now_order['use_time']);
        $arr['last_staff']	=	$now_order['last_staff'];
        if($now_order['status'] > 0 && $now_order['status'] < 3){
			if($now_order['tuan_type'] != 2){
				$arr['font']	=	'消费';
			}else{
				$arr['font']	=	'发货';
			}
        }
        if (!empty($now_order['pay_type'])) {
            $arr['paytypestr'] = D('Pay')->get_pay_name($now_order['pay_type']);
            if (($now_order['pay_type'] == 'offline') && !empty($now_order['third_id']) && ($now_order['paid'] == 1)) {
                $arr['paytypestrs'] ='已支付';
            } else if (($now_order['pay_type'] != 'offline') && ($now_order['paid'] == 1)) {
                $arr['paytypestrs'] ='已支付';
            } else {
                $arr['paytypestrs'] ='未支付';
            }
        } else {
        	if ($now_order['balance_pay'] > 0) {
        		$arr['paytypestr'] = '平台余额支付';
        	} elseif ($now_order['merchant_balance'] > 0) {
        		$arr['paytypestr'] = '商家余额支付';
        	} elseif ($now_order['paid']) {
        		$arr['paytypestr'] = '其他';
        	} else {
        		$arr['paytypestr'] = '未支付';
        	}
        }
        $arr['delivery_comment']	=	$now_order['delivery_comment'];
        if($now_order['paid'] == 1){
			$arr['uid']	=	$now_order['uid'];
	        $arr['nickname']	=	$now_order['nickname'];
	        $arr['order_phone']	=	$now_order['phone'];
	        $arr['user_phone']	=	$now_order['user_phone'];
	        if($now_order['tuan_type'] == 2){
	        	$arr['contact_name']	=	$now_order['contact_name'];
	        	$arr['phone']	=	$now_order['phone'];
	        	$arr['zipcode']	=	$now_order['zipcode'];
	        	$arr['adress']	=	$now_order['adress'];
	        	$arr['delivery_type']	=	$this->order_distribution($now_order['delivery_type']);
	        }else{
				$arr['contact_name']	=	'';
	        	$arr['phone']	=	'';
	        	$arr['zipcode']	=	'';
	        	$arr['adress']	=	'';
	        	$arr['delivery_type']	=	'';
	        }
			$arr['merchant_remark']	=	$now_order['merchant_remark'];
        }else{
			$arr['uid']	=	'';
	        $arr['nickname']	=	'';
	        $arr['order_phone']	=	'';
	        $arr['user_phone']	=	'';
	        $arr['contact_name']	=	'';
	        $arr['phone']	=	'';
	        $arr['zipcode']	=	'';
	        $arr['adress']	=	'';
	        $arr['delivery_type']	=	'';
	        $arr['merchant_remark']	=	'';
        }
        $this->returnCode(0,$arr);
    }
    //	团购详情
    public function gdetail(){
		$order_id	=	I('order_id');
		if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant['mer_id'], $order_id, false);
        $now_group = M('Group')->where(array('group_id'=>$now_order['group_id']))->find();
        if (empty($now_order)) {
            $this->returnCode('20130006');
        }
		if(!empty($now_order['paid'])){
			if($now_order['is_pick_in_store']){
				$now_order['paytypestr']="到店自提";
			}else{
				$now_order['paytypestr'] = D('Pay')->get_pay_name($now_order['pay_type']);
			}
			if(($now_order['pay_type']=='offline') && !empty($now_order['third_id']) && ($now_order['paid']==1)){
				$paytypestr	=	'已支付';
			}else if(($now_order['pay_type']!='offline') && ($now_order['paid']==1)){
				$paytypestr	=	'已支付';
			}else{
				$paytypestr	=	'未支付';
			}
		}else{
		    $now_order['paytypestr'] = '未支付';
		}
		if($now_order['tuan_type'] == 0){
			$order_type	=	$this->config['group_alias_name'].'劵';
		}else if($now_order['tuan_type'] == 1){
			$order_type	=	'代金券';
		}else{
			$order_type	=	'实物';
		}
		$status_format	=	$this->status_format($now_order['status'],$now_order['paid'],$now_order['third_id'],$now_order['pay_type'],$now_order['tuan_type']);
		if($now_order['status']>0 && $now_order['status']<3){
			if($now_order['tuan_type'] != 2){
				$operation	=	'消费';
			}else{
				$operation	=	'发货';
			}
		}
		$group_image_class = new group_image();
		$all_pic = $group_image_class->get_allImage_by_path($now_order['pic']);

        $arr['now_order']	=	array(
			's_name'	=>	$now_order['s_name'],				//团购名
			'pic'	=>	$all_pic[0]['image'],					//团购名
			'order_id'	=>	$now_order['order_id'],				//订单ID
			'real_orderid'	=>	$now_order['real_orderid'],		//订单ID
			'group_id'	=>	$now_order['group_id'],				//团购ID
			'status_s'	=>	$now_order['status'],				//状态
			'is_pick_in_store'	=>	$now_order['is_pick_in_store'],				//状态
			'order_type'=>	$order_type,						//订单类型
			'status'	=>	$status_format['status'],			//订单状态
			'type'		=>	$status_format['type'],				//订单状态
			'pass_array'=>	isset($now_order['pass_array'])?$now_order['pass_array']:'',		//操作
			'group_pass'=>	$now_order['group_pass'],
			'num'		=>	(int)$now_order['num'],						//数量
			'price'		=>	$now_order['price'],						//单价
			'add_time'	=>	date('Y-m-d H:i',$now_order['add_time']),	//下单时间
			'pay_time'	=>	date('Y-m-d H:i:s',$now_order['pay_time']),	//付款时间
			'operation'	=>	isset($operation)?$operation:'',			//消费 发货
			'use_time'	=>	date('Y-m-d H:i:s',$now_order['use_time']),	//消费 发货  时间
			'last_staff'=>	$now_order['last_staff'],			//操作店员
			'paystatus'	=>	isset($paytypestr)?$paytypestr:'',	//已支付	未支付
			'paytypestr'=>	$now_order['paytypestr'],			//货到付款  未支付
			'delivery_comment'=>	$now_order['delivery_comment'],			//备注
			'total_money'	=>	$now_order['total_money'],			//总金额
			'merchant_remark'	=>	$now_order['merchant_remark'],
			'tuan_type'	=>	$now_order['tuan_type'],
			'pin_num'	=>	$now_group['pin_num'],
			'store_id'	=>	$now_order['store_id'],
			'discount'	=>	$now_order['card_discount'],
		);
        $pin_info = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
        $arr['pin_info']  = $pin_info;
        if(empty($pin_info) || $pin_info['status']==1 || $pin_info['status']==3){
            $arr['now_order']['can_assign_store'] = 1;
        }else{
            $arr['now_order']['can_assign_store'] = 0;
        }
		if($now_order['third_id']==0 && $now_order['pay_type']=='offline'){
			$arr['now_order']['total_moneys']	=	$now_order['total_money'];			//总金额
			$arr['now_order']['balance_pay']	=	$now_order['balance_pay'];			//平台余额支付
			$arr['now_order']['merchant_balance']	=	$now_order['merchant_balance']+$now_order['card_give_money'];	//商家会员卡余额支付
			if($now_order['wx_cheap']!='0.00'){
				$arr['now_order']['wx_cheap']	=	$now_order['wx_cheap'];				//微信优惠
			}else{
				$arr['now_order']['wx_cheap']	=	0;
			}
			$arr['now_order']['payment_money']	=	0;									//在线支付金额
			$arr['now_order']['payment']	=	$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price'];	//线下需向商家付金额 红色字体
		}else{
			$arr['now_order']['total_moneys']	=	0;									//总金额
			$arr['now_order']['balance_pay']	=	$now_order['balance_pay'];			//平台余额支付
			$arr['now_order']['merchant_balance']=	$now_order['merchant_balance']+$now_order['card_give_money'];		//商家会员卡余额支付
			$arr['now_order']['wx_cheap']		=	0;									//微信优惠
			$arr['now_order']['payment_money']	=	$now_order['payment_money'];		//在线支付金额
			$arr['now_order']['payment']		=	0;
		}
		$arr['user']	=	array(
			'uid'	=>	$now_order['uid'],						//用户ID
			'nickname'	=>	$now_order['nickname'],				//用户名
			'phone'	=>	$now_order['phone'],					//订单手机号
			'user_phone'=>	$now_order['user_phone'],			//用户手机
		);
		$arr['distribution']	=	array(
			'contact_name'	=>	$now_order['contact_name'],			//联系名
			'phone'		=>	$now_order['phone'],					//联系电话
			'zipcode'	=>	$now_order['zipcode'],					//邮编
			'adress'	=>	$now_order['adress'],					//地址
			'express_id'	=>	$now_order['express_id'], 			//快递单号
			'express_type'	=>	$now_order['express_type'], 		//快递公司
			'merchant_remark'	=>	$now_order['merchant_remark'], //标记
		);
		switch($now_order['delivery_type']){
			case 1:
				$arr['distribution']['delivery_type']	=	'工作日、双休日与假日均可送货';
				break;
			case 2:
				$arr['distribution']['delivery_type']	=	'只工作日送货';
				break;
			case 3:
				$arr['distribution']['delivery_type']	=	'只双休日、假日送货';
				break;
			case 4:
				$arr['distribution']['delivery_type']	=	'白天没人，其它时间送货';
				break;
		}
        $express_list = D('Express')->get_express_list();
        if($express_list){
			foreach($express_list as &$v){
				if($v['id'] == $now_order['express_type']){
					$arr['distribution']['express_name']	=	$v['name'];
				}
				$v['ids']	=	$v['id'];
				unset($v['code'],$v['url'],$v['sort'],$v['add_time'],$v['status'],$v['id']);
			}
			if(empty($arr['distribution']['express_name'])){
				$arr['distribution']['express_name']	=	$express_list[0]['name'];
				$arr['distribution']['express_type']	=	$express_list[0]['ids'];
			}
        }else{
			$express_list	=	array();
        }
        $arr['express_list']	=	$express_list;


        $this->returnCode(0,$arr);
    }
    //	团购状态格式化
    private function status_format($order_status,$paid,$third_id,$pay_type,$tuan_type){
    	$type	=	0;
		$status	=	0;
        $status_txt='';
        $type_txt='';
		if($order_status	==	3){
			$status	=	1;	//已取消
            $status_txt='已取消';
		}else if($paid){
			if($third_id==3 && $pay_type=='offline' && $order_status==0){
				$status	=	2;	//线下未付款
                $status_txt='线下未付款';
			}else if($order_status==0){
				$status	=	3;	//已付款
                $status_txt='已付款';
				if($tuan_type!=2){
					$type	=	1;	//未消费
                    $type_txt='已付款';
				}else{
					$type	=	2;	//未发货
                    $type_txt='未发货';
				}
			}else if($order_status==1){
				$status	=	4;	//待评价
                $status_txt='待评价';
				if($tuan_type!=2){
					$type	=	3;	//已消费
                    $type_txt='已消费';
				}else{
					$type	=	4;	//已发货
                    $type_txt='已发货';
				}
			}else{
				$status	=	5;	//已完成
                $status_txt='已完成';
			}
		}else{
			$status	=	6;	//未付款
            $status_txt='未付款';
		}
		$arr	=	array(
			'type'	=>	$type,
			'status'	=>	$status,
			'status_txt'	=>	$status_txt,
			'type_txt'	=>	$type_txt,
		);
		return $arr;
    }
    # 修改团购订单归属店铺
    public function order_store_id() {
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant['mer_id'],$order_id, true, false);
        if (empty($now_order)) {
            $this->returnCode('20140026');
        }
        if (empty($now_order['paid'])) {
            $this->returnCode('20140027');
        }
        $condition_group_order['order_id'] = $now_order['order_id'];
        $data_group_order['store_id'] = I('store_id');
        if (D('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {
            $this->returnCode(0);
        } else {
            $this->returnCode('20140028');
        }
    }
    # 团购订单额外信息
    public function group_remark() {
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant['mer_id'], $order_id, true, false);
        if (empty($now_order)) {
            $this->returnCode('20140026');
        }
        if (empty($now_order['paid'])) {
            $this->returnCode('20140027');
        }
        $condition_group_order['order_id'] = $now_order['order_id'];
        $data_group_order['merchant_remark'] = I('merchant_remark');
        if (D('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {
            $this->returnCode(0);
        } else {
            $this->returnCode('20140028');
        }
    }
    # 团购商品管理
    public function gpro() {
        $database_group = D('Group');
        $condition_group = 'mer_id=' . $this->merchant['mer_id'];
        $keyword	=	I('keyword');
        $keyword = isset($keyword) ? trim($keyword) : '';
        if (!empty($keyword)) {
            $condition_group.=' AND (s_name like "%' . $keyword . '%" OR name like "%' . $keyword . '%")';
        }
        $group_count = $database_group->where($condition_group)->count();
        $pindex	=	I('pindex');
        $pindex = intval(trim($pindex));
        $pindex = $pindex > 0 ? $pindex : 1;
        $pagsize = 20;
        $offsize = ($pindex - 1) * 20;
        $group_list = $database_group->field('group_id,mer_id,prefix_title,name,s_name,pic ,old_price,price,wx_cheap,discount,sale_count,status,type,tuan_type,qrcode_id')->where($condition_group)->order('`group_id` DESC')->limit($offsize . ',' . $pagsize)->select();
        $group_image_class = new group_image();
        foreach ($group_list as $key => $value) {
            $tmp_pic_arr = explode(';', $value['pic']);
			$group[]	=	array(
				'list_pic'	=>	$group_image_class->get_image_by_path($tmp_pic_arr[0], 's'),
				's_name'	=>	$value['s_name'],
				'old_price'	=>	floatval($value['old_price']),
				'price'		=>	floatval($value['price']),
				'wx_cheap'	=>	floatval($value['wx_cheap']),
				'sale_count'=>	$value['sale_count'],
				'group_id'	=>	$value['group_id'],
			);
        }
        $hasmore = $group_count > ($pindex * $pagsize) ? 1 : 0;
        $arr	=	array(
        	'group_count'	=>	$group_count,
        	'page'	=>	ceil($group_count/10),
			'list' => !empty($group) ? $group : array(),
        );
        $this->returnCode(0,$arr);
    }
    # 餐饮列表
    public function mlist(){
		$where['mer_id']	=	$this->merchant['mer_id'];
    	$where['status']	=	array('elt','2');
    	$page	=	I('pindex',1);
        $data = M('Merchant_store')->field(array('`mer_id`,`name`,`store_id`,`status`'))->where($where)->page($page,10)->select();
        if ($data != false) {
        	foreach($data as &$v){
				$v['qrcode']	=	$this->erwm($v['store_id']);
        	}
            $arr['data']	=	$data;
            $arr['all']		=	M('Merchant_store')->where($where)->count();
            $arr['status1'] =	M('Merchant_store')->where(array('status' => 1, 'mer_id' => $this->merchant['mer_id']))->count();
            $arr['status2'] =	M('Merchant_store')->where(array('status' => 2, 'mer_id' => $this->merchant['mer_id']))->count();
            $arr['page']	=	ceil($arr['all']/10);
        }else{
			$arr	=	array(
				'data' 		=>	array(),
				'all'		=>	array(),
				'status1'	=>	array(),
				'status2'	=>	array(),
			);
        }
        $this->returnCode(0,$arr);
    }
    # 餐饮店铺列表
    public function meal_list() {
    	$where['mer_id']	=	$this->merchant['mer_id'];
    	$where['status']		=	array('neq','2');
    	$where['have_meal']		=	array('eq','1');
    	$page	=	I('pindex',1);
    	$all	=	M('Merchant_store')->where($where)->count();
        $data	=	M('Merchant_store')->field(array('`mer_id`,`name`,`store_id`,`status`,`phone`'))->where($where)->page($page,10)->select();
        foreach($data as &$v){
			$shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $v['store_id']))->find();
        	$store_theme = isset($shop['store_theme']) ? intval($shop['store_theme']) : 0;
        	if ($store_theme) {
        		$v['width'] = '900';
        		$v['height'] = '900';
        	} else {
        		$v['width'] = '900';
        		$v['height'] = '500';
        	}
		}
        $arr['data']	=	isset($data)?$data:array();
        $arr['all']		=	$all;
        $arr['page'] 	=	ceil($arr['all']/10);
        $this->returnCode(0,$arr);
    }
    # 餐饮订单
    public function morder() {
        $mer_id = $this->merchant['mer_id'];
        $status	=	I('status');
        $keyword	=	I('keyword');
        $status = isset($status) ? trim($status) : 'all';
        $keyword = isset($keyword) ? trim($keyword) : '';
        $where = 'mord.mer_id=' . $mer_id;
        if ($status != 'all') {
            $status = intval($status);
            if ($status == 0) {
                $where.=' AND (mord.paid="0" OR (mord.third_id ="0" AND mord.pay_type="offline"))';
            } else {
                $where.=' AND mord.status="' . ($status - 1) . '"';
            }
        }
        if (!empty($keyword)) {
            $where.=' AND (mord.phone like "%' . $keyword . '%" OR mord.name like "%' . $keyword . '%")';
        }
        //订单列表
        $meal_orderDb = M('Meal_order');
        $jointable = C('DB_PREFIX') . 'merchant_store';
        $order_count = $meal_orderDb->join('as mord LEFT JOIN ' . $jointable . ' as ms on mord.store_id=ms.store_id')->where($where)->count();
        $pindex	=	I('pindex');
        $pindex = intval(trim($pindex));
        $pindex = $pindex > 0 ? $pindex : 1;
        $pagsize = 20;
        $offsize = ($pindex - 1) * 20;
        $newdatas = array();
        $order_list = $meal_orderDb->join('as mord LEFT JOIN ' . $jointable . ' as ms on mord.store_id=ms.store_id')->field('mord.*,ms.name as storename')->where($where)->order('order_id  DESC')->limit($offsize . ',' . $pagsize)->select();
        $hasmore = $order_count > ($pindex * $pagsize) ? 1 : 0;
        if (!empty($order_list)) {
            foreach ($order_list as $kk => $vv) {
            	$order_statuss='';
                if ($vv['status'] == 3) {
                    $newdatas[$kk]['order_status'] = '已取消';
                } elseif ($vv['status'] == 4) {
                    $newdatas[$kk]['order_status'] = '已删除';
                } elseif ($vv['paid'] > 0) {
                    if (($vv['third_id'] == "0") && ($vv['pay_type'] == 'offline')) {
                        $newdatas[$kk]['order_status'] = '线下未付款';
                    } elseif ($vv['status'] == 0) {
                        $newdatas[$kk]['order_status'] = '已付款';
                        if ($vv['tuan_type'] != 2) {
                            $order_statuss='未消费';
                        } else {
                            $order_statuss='未发货';
                        }
                    } elseif ($vv['status'] == 1) {
                        if ($vv['tuan_type'] != 2) {
                            $newdatas[$kk]['order_status'] = '已消费';
                        } else {
                            $newdatas[$kk]['order_status'] = '已发货';
                        }
                    } else {
                        $newdatas[$kk]['order_status'] = '已完成';
                    }
                } else {
                    $newdatas[$kk]['order_status'] = '未付款';
                }
				$newdatas[$kk]['order_statuss']	=	isset($order_statuss)?$order_statuss:'';
                $newdatas[$kk]['order_id'] = $vv['order_id'];
                $newdatas[$kk]['nickname'] = $vv['name'];
                $newdatas[$kk]['storename'] = $vv['storename'];
                $newdatas[$kk]['phone'] = $vv['phone'];
                $newdatas[$kk]['address'] = $vv['address'];
                $newdatas[$kk]['final_price'] = $vv['total_price'] > 0 ? $vv['total_price'] - $vv['minus_price'] : $vv['price'] - $vv['minus_price'];
                $newdatas[$kk]['num'] = $vv['total'] . '道菜';
                $newdatas[$kk]['created'] = date('Y-m-d H:i:s', $vv['dateline']);
            }
        }
        unset($order_list);
        $arr	=	array(
			'order_count'	=>	$order_count,
			'list'	=>	$newdatas,
			'page'	=>	ceil($order_count/10),
        );
        $this->returnCode(0,$arr);
    }
    # 餐饮订单详情
    public function mdetail() {
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
    	$Meal_order	=	M('Meal_order');
        $order = $Meal_order->where(array('mer_id' => $this->merchant['mer_id'], 'order_id' => $order_id))->find();
        if(empty($order)){
			$this->returnCode('20140026');
        }
        $order['info'] = unserialize($order['info']);
        if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
            $order['paid'] = 0;
        }
        if (!empty($order['pay_type'])) {
            $order['paytypestr'] = D('Pay')->get_pay_name($order['pay_type']);
            if (($order['pay_type'] == 'offline') && !empty($order['third_id']) && ($order['paid'] == 1)) {
                $order['paytypestrs'] =' 已支付';
            } else if (($order['pay_type'] != 'offline') && ($order['paid'] == 1)) {
                $order['paytypestrs'] =' 已支付';
            } else {
                $order['paytypestrs'] =' 未支付';
            }
        } else {
        	if ($order['balance_pay'] > 0) {
        		$order['paytypestr'] = '平台余额支付';
        	} elseif ($order['merchant_balance'] > 0) {
        		$order['paytypestr'] = '商家余额支付';
        	} elseif ($order['paid']) {
        		$order['paytypestr'] = '其他';
        	} else {
        		$order['paytypestr'] = '未支付';
        	}
        }
        $arr['order_id']	=	$order['order_id'];
        if(!empty($order['coupon_id'])) {
            $system_coupon = D('System_coupon')->get_coupon_info($order['coupon_id']);
            $this->assign('system_coupon',$system_coupon);
        }else if(!empty($order['card_id'])) {
            $card = D('Member_card_coupon')->get_coupon_info($order['card_id']);
            $this->assign('card', $card);
        }
		$mode = new Model();
		$sql = "SELECT u.name, u.phone FROM " . C('DB_PREFIX') . "deliver_supply AS s INNER JOIN " . C('DB_PREFIX') . "deliver_user AS u ON u.uid=s.uid WHERE s.order_id={$order['order_id']} AND s.item=0";
		$res = $mode->query($sql);
		$res = isset($res[0]) && $res[0] ? $res[0] : '';
        $arr	=	array(
			'order_id'	=>	$order['order_id'],
			'name'	=>	$order['name'],
			'phone'	=>	$order['phone'],
			'price'	=>	$order['price'],
			'address'	=>	$order['address'],
			'dateline'	=>	date('Y-m-d H:i:s',$order['dateline']),
			'arrive_time'	=>	$order['arrive_time']==0?0:date('Y-m-d H:i:s',$order['arrive_time']),
			'use_time'	=>	$order['use_time']==0?0:date('Y-m-d H:i:s',$order['use_time']),
			'note'	=>	$order['note'],
			'tuan_type'	=>	isset($order['tuan_type'])?$order['tuan_type']:0,	//不等于2 消费时间use_time，2发货时间use_time
			'balance_pay'	=>	$order['balance_pay'],
			'score_deducte'	=>	$order['score_deducte'],
			'score_used_count'	=>	$order['score_used_count'],
			'payment_money'	=>	$order['payment_money'],
			'merchant_balance'	=>	$order['merchant_balance'],
			'coupon_price'	=>	$order['coupon_price'],
			'card_price'	=>	floatval($order['card_price']),
			'paytypestr'	=>	$order['paytypestr'],
			'paytypestrs'	=>	$order['paytypestrs'],
			'info'			=>	isset($order['info'])?$order['info']:array(),
			'status'		=>	$order['status'],	//0未使用	1已使用	2已评价	3已退款	4已取消
			'user_name'		=>	isset($order['deliver_user_info']['name'])?$order['deliver_user_info']['name']:'',
			'user_phone'	=>	isset($order['deliver_user_info']['phone'])?$order['deliver_user_info']['phone']:'',
			'deliver_user_info'	=>	$res,
        );
        if($order['total_price'] == 0){
			$arr['total_price']	=	$order['total_price']-$order['minus_price']-$order['balance_pay']-$order['merchant_balance']-$order['coupon_price']-$order['card_price']-floatval($order['score_deducte']);
        }else{
			$arr['total_price']	=	$order['price']-$order['balance_pay']-$order['merchant_balance']-$order['coupon_price']-$order['card_price']-floatval($order['score_deducte']);
        }
        $this->returnCode(0,$arr);
    }
    # 餐饮商品分类
    public function meal_sort(){
    	$store_id	=	I('store_id');
    	$page		=	I('pindex',1);
		$database_meal_sort = D('Meal_sort');
		$condition_merchant_sort['store_id'] = $store_id;
		$count_sort = $database_meal_sort->where($condition_merchant_sort)->count();
		$sort_list = $database_meal_sort->field(true)->where($condition_merchant_sort)->order('`sort` DESC,`sort_id` ASC')->page($page,10)->select();
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
		$arr	=	array(
			'sort_list'	=>	$sort_list,
			'count'		=>	$count_sort,
			'page'		=>	ceil($count_sort/10),
		);
		$this->returnCode(0,$arr);
    }
    # 餐饮商品
    public function mpro() {
        $database_meal = D('Meal');
        $sort_id	=	I('sort_id');
        $condition_meal = 'sort_id in (' . $sort_id . ')';
        $keyword	=	I('keyword');
        $keyword = isset($keyword) ? trim($keyword) : '';
        if (!empty($keyword)) {
            $condition_meal.=' AND (name like "%' . $keyword . '%")';
        }
        $count_meal = $database_meal->where($condition_meal)->count();
        $pindex	=	I('pindex',1);
        $pagsize = 20;
        $offsize = ($pindex - 1) * 20;
        $meal_list = $database_meal->field(true)->where($condition_meal)->order('`sort` DESC,`meal_id` ASC')->limit($offsize . ',' . $pagsize)->select();
        $meal_image_class = new meal_image();
        if (!empty($meal_list)) {
            foreach ($meal_list as $mk => $mv) {
                $meal[$mk]['list_pic'] = $meal_image_class->get_image_by_path($mv['image'], $this->config['site_url'], 's');
                $meal[$mk]['s_name'] = $mv['name'];
                $meal[$mk]['meal_id'] = $mv['meal_id'];
                $meal[$mk]['sort_id'] = $mv['sort_id'];
                $meal[$mk]['store_id'] = $mv['store_id'];
                $meal[$mk]['sell_count'] = $mv['sell_count'];
                $meal[$mk]['statusstr'] = $mv['status'] == 1 ? '在售' : '停售';
                $meal[$mk]['statusoptstr'] = $mv['status'] == 1 ? '下架' : '上架';
                $meal[$mk]['statusopt'] = $mv['status'] == 1 ? '0' : '1';
                $meal[$mk]['old_price'] = floatval($mv['old_price']);
                $meal[$mk]['price'] = floatval($mv['price']);
            }
        }
        $hasmore = $count_meal > ($pindex * $pagsize) ? 1 : 0;
        $arr	=	array(
			'list'	=>	!empty($meal) ? $meal : array(),
			'count'	=>	$count_meal,
			'page'		=>	ceil($count_meal/10),
        );
        $this->returnCode(0,$arr);
    }
    public function getstore_id_Bymerid($mer_id, $name = false) {
        $tmpdatas = M('merchant_store')->field('store_id,name')->where(array('mer_id' => $mer_id, 'have_meal' => '1', 'status' => '1'))->select();
        if ($name)
            return $tmpdatas;
        $storeids = array();
        if (!empty($tmpdatas)) {
            foreach ($tmpdatas as $vv) {
                $storeids[] = $vv['store_id'];
            }
        }
        return $storeids;
    }
    # 餐饮商品上架、下架
    public function mstatusopt() {
    	$status		=	I('status');
    	$meal_id	=	I('meal_id');
    	$store_id	=	I('store_id');
        if ($store_id > 0 && $meal_id > 0) {
            if (M('Meal')->where(array('store_id' => $store_id, 'meal_id' => $meal_id))->save(array('status' => $status))) {
                $this->returnCode(0);
            }else{
				$this->returnCode('20140046');
            }
        }
        $this->returnCode('20140047');
    }
    # 餐饮商品删除
    public function mdel() {
    	$meal_id	=	I('meal_id');
    	$store_id	=	I('store_id');
        if (M('Meal')->where(array('store_id' => $store_id, 'meal_id' => $meal_id))->delete()) {
            $this->returnCode(0);
        } else {
            $this->returnCode('20140045');
        }
    }
    # 预约列表
    public function appoint(){
        $database_appoint = D('Appoint');
        $database_merchant = D('Merchant');
        $database_category = D('Appoint_category');
        $condition_appoint['mer_id'] = $this->merchant['mer_id'];
        $appoint_count = $database_appoint->where($condition_appoint)->count();
		$pindex	=	I('pindex',1);
        $appoint_info = $database_appoint->field(true)->where($condition_appoint)->order('`appoint_id` DESC')->page($pindex,10)->select();
        $merchant_info = $database_merchant->field(true)->where('mer_id = ' . $this->merchant['mer_id'] . '')->select();
        $category_info = $database_category->field(true)->where($condition_appoint)->select();
        $appoint_list = $this->formatArray($appoint_info, $merchant_info, $category_info);
        foreach($appoint_list as &$v){
			$v['start_time']	=	date('Y-m-d H:i:s',$v['start_time']);
        	$v['end_time']	=	date('Y-m-d H:i:s',$v['end_time']);
        	if($v['appoint_status'] == 1){
				$v['appoint_status'] = 0;
        	}else{
				$v['appoint_status'] = 1;
        	}
        	$tmp_pic_arr = explode(';', $v['pic']);
	        $appoint_image_class = new appoint_image();
	        foreach ($tmp_pic_arr as $key => $value) {
	            $pic_list[$key]['title'] = $value;
	            $pic_list[$key]['url'] = $appoint_image_class->get_image_by_path($value, 's');

	        }
            if(strpos($pic_list[0]['url'],'http')!==false){
                $v['pic']	=	$pic_list[0]['url'];

            }else{
                $v['pic']	=$this->config['site'].$pic_list[0]['url'];
            }
	        $v['qrcode_id']	=	$this->config['site_url'].'/index.php?g=Index&c=Recognition&a=see_qrcode&type=appoint&id='.$v['appoint_id'].'&img=1';
			unset($v['office_time'],$v['appoint_pic_content'],$v['is_store'],$v['cat_fid'],$v['cat_id'],$v['create_time'],$v['mer_id'],$v['pics']);
        }
        $arr	=	array(
			'count'			=>	isset($appoint_count)?$appoint_count:0,
			'appoint_list'	=>	isset($appoint_list)?$appoint_list:array(),
			'page'	=>	ceil($appoint_count/10),
        );
        $this->returnCode(0,$arr);
    }

    public function appoint_list() {
        $mer_id = $this->merchant['mer_id'];
        $database_order = D('Appoint_order');
        $database_user = D('User');
        $database_appoint = D('Appoint');
        $database_store = D('Merchant_store');
        $order_id	=	$_POST['order_id'];
        $page = I('pindex',1);
        $where['o.mer_id'] = $mer_id;

        if(!empty($_POST['keyword'])){
            if ($_POST['searchtype'] == 'order_id') {
                $where['o.order_id'] = $_POST['keyword'] ;
            } elseif ($_POST['searchtype'] == 'orderid') {
                $where['orderid'] = htmlspecialchars($_POST['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_POST['keyword']))->find();
                $where['o.order_id'] = $tmp_result['order_id'] ;
            } elseif ($_POST['searchtype'] == 'name') {
                $where['u.nickname'] = array('like','%'.htmlspecialchars($_POST['keyword']) .'%') ;
            } elseif ($_POST['searchtype'] == 'phone') {
                $where['u.phone'] = array('like','%'.htmlspecialchars($_POST['keyword']) .'%') ;
            } elseif ($_POST['searchtype'] == 'third_id') {
                $where['o.third_id'] = $_POST['keyword'] ;
            }
        }

        if($_POST['pay_type']){
            $where['o.pay_type'] = $_POST['pay_type'];
        }
        if($_POST['stime']&&$_POST['etime']){
            $stime = strtotime($_POST['stime']);
            $etime = strtotime($_POST['etime'])+86400;
            $where['_string'] = " o.order_time >= {$stime} AND o.order_time >={$etime}";
        }


        $count = $database_order->join('as o LEFT JOIN '.C('DB_PREFIX').'user AS u ON u.uid  = o.uid')->where($where)->count();
        if($order_id){
            $where['order_id']	=	array('lt',$order_id);
        }
        $order_info = $database_order->field('o.*,u.nickname,u.phone')->join('as o LEFT JOIN '.C('DB_PREFIX').'user AS u ON u.uid  = o.uid')->where($where)->page($page,10)->order('`o`.`order_id` DESC')->select();

        $uidArr = array();
        foreach($order_info as $v){
            array_push($uidArr,$v['uid']);
        }
        $uidArr = array_unique($uidArr);
        $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();
        $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
        $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
        $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
        if($order_list){
            foreach($order_list as $v){
                $truename	=	'';
                if($v['truename']){
                    $truename	=	$v['truename'];
                }else{
                    $truename	=	$v['nickname'];
                }
                $arr['order_list'][]	=	array(
                    'order_id'	=>	$v['order_id'],
                    'appoint_name'	=>	$v['appoint_name'],
                    'truename'	=>	$truename,
                    'phone'	=>	$v['phone'],
                    'appoint_date'	=>	$v['appoint_date'].' '.$v['appoint_time'],
                    'payment_money'	=>	floatval($v['payment_money']),
                    'appoint_price'	=>	floatval($v['appoint_price']),
                    'paid'			=>	$v['paid'],
                    'service_status'=>	$v['service_status'],
                    'appoint_type'=>	$v['appoint_type'],
                    'order_time'=>	date('Y-m-d H:i:s',$v['order_time']),
                );
            }
        }else{
            $arr['order_list']	=	array();
        }
        $arr['count']	=	$count;
        $arr['page'] = ceil($count/10);
        $arr['status']	=	1;
        $this->returnCode(0,$arr);
    }

    # 预约列表--修改状态
    public function appoint_status(){
		$database_appoint = D('Appoint');
		$condition_appoint['appoint_id']	=	I('appoint_id');
		$data['appoint_status']	=	I('appoint_status');
		if($data['appoint_status'] == 1){
			$data['appoint_status']	=	0;
		}else{
			$data['appoint_status']	=	1;
		}
		if(empty($condition_appoint)){
			$this->returnCode('20140050');
		}
		$appoint_info = $database_appoint->where($condition_appoint)->data($data)->save();
		if($appoint_info){
			$this->returnCode(0);
		}else{
			$this->returnCode('20140028');
		}
    }

    /* 格式化订单数据  */
    protected function formatOrderArray($order_info, $user_info, $appoint_info, $store_info){
        if(!empty($user_info)){
            $user_array = array();
            foreach($user_info as $val){
                $user_array[$val['uid']]['phone'] = $val['phone'];
                $user_array[$val['uid']]['nickname'] = $val['nickname'];
            }
        }
        if(!empty($appoint_info)){
            $appoint_array = array();
            foreach($appoint_info as $val){
                $appoint_array[$val['appoint_id']]['appoint_name'] = $val['appoint_name'];
                $appoint_array[$val['appoint_id']]['appoint_type'] = $val['appoint_type'];
                $appoint_array[$val['appoint_id']]['appoint_price'] = $val['appoint_price'];
            }
        }
        if(!empty($store_info)){
            $store_array = array();
            foreach($store_info as $val){
                $store_array[$val['store_id']]['store_name'] = $val['name'];
                $store_array[$val['store_id']]['store_adress'] = $val['adress'];
            }
        }
        if(!empty($order_info)){
            foreach($order_info as &$val){
                $val['phone'] = $user_array[$val['uid']]['phone'];
                $val['nickname'] = $user_array[$val['uid']]['nickname'];
                $val['appoint_name'] = $appoint_array[$val['appoint_id']]['appoint_name'];
                $val['appoint_type'] = $appoint_array[$val['appoint_id']]['appoint_type'];
                $val['appoint_price'] = $appoint_array[$val['appoint_id']]['appoint_price'];
                $val['store_name'] = $store_array[$val['store_id']]['store_name'];
                $val['store_adress'] = $store_array[$val['store_id']]['store_adress'];
                $val['appoint_type'] = $appoint_array[$val['appoint_id']]['appoint_type'];
                $val['order_time'] = $val['order_time'];
            }
        }
        return $order_info;
    }

    public function appointDetail()
    {
        $where = array('mer_id' => $this->merchant['mer_id']);
        $where['order_id'] = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;


        $orderInfo = D('Appoint_order')->field(true)->where($where)->find();
        if (empty($orderInfo)) {
            $this->returnCode(1, null, '订单信息不存在');
        }

        $orderInfo['order_time'] = $orderInfo['order_time'] ? date('Y-m-d H:i:s', $orderInfo['order_time']) : 0;
        $orderInfo['user_pay_time'] = $orderInfo['user_pay_time'] ? date('Y-m-d H:i:s', $orderInfo['user_pay_time']) : 0;

        $orderInfo['user_name'] = '';
        $orderInfo['user_phone'] = '';
        if ($userInfo = D('User')->field('`uid`, `phone`, `nickname`')->where(array('uid' => $orderInfo['uid']))->find()) {
            $orderInfo['user_name'] = $userInfo['nickname'];
            $orderInfo['user_phone'] = $userInfo['phone'];
        }

        $appointInfo = M('Appoint')->where(array('appoint_id'=>$orderInfo['appoint_id']))->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->find();
        $orderInfo['appoint_name'] = $appointInfo['appoint_name'];

        $cue_info = unserialize($orderInfo['cue_field']);
        $cue_list = array();
        foreach ($cue_info as $key => $val) {
            if (!empty($cue_info[$key]['value'])) {
                $cue_list[$key]['name'] = $val['name'];
                $cue_list[$key]['value'] = $val['value'];
                $cue_list[$key]['type'] = $val['type'];
                if($cue_info[$key]['type'] == 2){
                    $cue_list[$key]['long'] = $val['long'];
                    $cue_list[$key]['lat'] = $val['lat'];
                    $cue_list[$key]['address'] = $val['address'];
                }
            }
        }
        $orderInfo['product_detail'] = '';
        $product_detail = D('Appoint_product')->field(true)->where(array('id' => $orderInfo['product_id']))->find();;
        if ($product_detail['status']) {
            $orderInfo['product_detail'] = $product_detail['detail'];
        }

        $service_address_info = array();
        $orderInfo['worker_name'] = '';
        $orderInfo['worker_phone'] = '';
        $orderInfo['worker_time'] = 0;
        if ($orderInfo['appoint_type'] == 1) {
            if ($appoint_visit_order_info = D('Appoint_visit_order_info')->where(array('appoint_order_id' => $orderInfo['order_id'], 'uid' => $orderInfo['uid']))->find()) {
                $service_address = unserialize($appoint_visit_order_info['service_address']);
                foreach ($service_address as $key => $val) {
                    if (!empty($service_address[$key]['value'])) {
                        $service_address_info[$key]['name'] = $val['name'];
                        $service_address_info[$key]['value'] = $val['value'];
                        $service_address_info[$key]['type'] = $val['type'];
                        if($appoint_visit_order_info['type'] == 2){
                            $service_address_info[$key]['long'] = $val['long'];
                            $service_address_info[$key]['lat'] = $val['lat'];
                            $service_address_info[$key]['address'] = $val['address'];
                        }
                    }
                }
                if ($merchant_workers_info = M('Merchant_workers')->field(array('merchant_worker_id', 'name', 'mobile'))->where(array('merchant_worker_id' => $appoint_visit_order_info['merchant_worker_id'])) ->find()) {
                    $orderInfo['worker_name'] = $merchant_workers_info['name'];
                    $orderInfo['worker_phone'] = $merchant_workers_info['mobile'];
                    $orderInfo['worker_time'] = date('Y-m-d H:i:s', $appoint_visit_order_info['add_time']);
                }
            }
        }

        if($service_address_info){
            $cue_list = $service_address_info;
        }

        $orderInfo['cue_list'] = $cue_list ?: '';
        $this->returnCode(0, $orderInfo);

    }
    /*验证预约服务*/
    public function appoint_verify(){
        $database_order = D('Appoint_order');
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['order_id'] = $_POST['order_id'];
        $now_order = $database_order->field(true)->where($where)->find();
        if(empty($now_order)){
            $this->returnCode('20130006');
        } else if ($now_order['paid']!=2 && $now_order['service_status'] == 0) {
            $condition_group['appoint_id'] = $now_order['appoint_id'];
            D('Appoint')->where($condition_group)->setInc('appoint_sum',1);

            $fields['last_time'] = time();
            $fields['service_status'] = 1;
            if($database_order->where($where)->data($fields)->save()){

                //验证增加商家余额
                $order_info['order_id'] = $now_order['order_id'];
                $order_info['mer_id'] = $now_order['mer_id'];
                $order_info['store_id'] = $now_order['store_id'];
                $order_info['order_type'] = 'appoint';
                $order_info['balance_pay'] = $now_order['balance_pay'];
                $order_info['score_deducte'] = $now_order['score_deducte'];
                $order_info['payment_money'] = $now_order['pay_money'];
                $order_info['is_own'] = $now_order['is_own'];
                $order_info['merchant_balance'] = $now_order['merchant_balance'];
                $order_info['score_used_count'] = $now_order['score_used_count'];
                $order_info['money'] = $order_info['balance_pay'] + $order_info['score_deducte'] + $order_info['payment_money'] + $order_info['merchant_balance'];

                if($now_order['product_id'] > 0){
                    $order_info['total_money'] = $now_order['product_price'];
                }else{
                    $order_info['total_money'] = $now_order['appoint_price'];
                }

                $order_info['payment_money'] = $now_order['pay_money'] + $now_order['user_pay_money'];
                $order_info['balance_pay'] = $now_order['balance_pay'] + $now_order['product_balance_pay'];
                $order_info['merchant_balance'] = $now_order['merchant_balance'] + $now_order['product_merchant_balance'];
                $order_info['card_give_money'] = $now_order['card_give_money'] + $now_order['product_card_give_money'];
                $order_info['uid'] = $now_order['uid'];

                $appoint_name = M('Appoint')->field('appoint_name')->where(array('appoint'=>$now_order['appoint_id']))->find();
                //D('Merchant_money_list')->add_money($this->store['mer_id'],'用户预约'.$appoint_name['appoint_name'].'记入收入',$order_info);
                $order_info['desc']='用户预约'.$appoint_name['appoint_name'].'记入收入';
                $order_info['score_discount_type']=$now_order['score_discount_type'];
                D('SystemBill')->bill_method($order_info['is_own'],$order_info);
                $now_user = D('User')->get_user($order_info['uid']);
                D('Merchant_spread')->add_spread_list($order_info,$now_user,'appoint',$now_user['nickname']."用户购买预约获得佣金");
                //if($this->config['open_score_get_percent']==1){
                //	$score_get = $this->config['score_get_percent']/100;
                //}else{
                //	$score_get = $this->config['user_score_get'];
                //}
                if($this->config['add_score_by_percent']==0 && ($this->config['open_score_discount']==0 || $order_info['score_discount_type']!=2)){
                    if($order_info['is_own'] && $this->config['user_own_pay_get_score']!=1){
                        $order_info['payment_money'] = 0;
                    }
                    D('User')->add_score($order_info['uid'], round(($order_info['balance_pay']+$order_info['payment_money']) * $this->config['score_get']), '购买预约商品获得'.$this->config['score_name']);
                }
                $this->returnCode(0);
            } else {
                $this->returnCode('20130007');
            }
        }else{
            $this->returnCode('20130008');
        }
    }


    /*预约订单详情*/
    public function appoint_edit(){
        $where['order_id'] = $_POST['order_id'];
        $database_order = D('Appoint_order');
        $database_user = D('User');
        $database_appoint = D('Appoint');
        $database_store = D('Merchant_store');
        $order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->select();
        $uidArr = array();
        foreach($order_info as $v){
            array_push($uidArr,$v['uid']);
        }
        $uidArr = array_unique($uidArr);
        $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();
        $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
        $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
        $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
        $now_order = $order_list[0];
        $cue_info = unserialize($now_order['cue_field']);
        $cue_list = array();
        foreach($cue_info as $key=>$val){
            $address	=	'';
            if(!empty($val['value'])){
                if($val['type'] == 2){
                    $address = $val['address'];
                }
                if($val['long'] && $val['lat']){
                    $long	=	$val['long'];
                    $lat	=	$val['lat'];
                }
                $cue_list[]	=	array(
                    'name'	=>	$val['name'],
                    'value'	=>	$val['value'],
                    'type'	=>	$val['type'],
                    'address'=>	isset($address)?$address:'',
                );
            }
        }
        if($now_order){
            $arr['now_order']	=	array(
                'appoint_id'	=>	$now_order['appoint_id'],
                'appoint_name'	=>	$now_order['appoint_name'],
                'order_id'	=>	$now_order['order_id'],
                'appoint_date'	=>	$now_order['appoint_date'].' '.$now_order['appoint_time'],
                'paid'	=>	$now_order['paid'],
                'service_status'	=>	$now_order['service_status'],
                'payment_money'	=>	floatval($now_order['payment_money']),
                'appoint_price'	=>	floatval($now_order['appoint_price']),
                'order_time'	=>	date('Y-m-d H:i',$now_order['order_time']),
                'pay_time'	=>	$now_order['pay_time']!=0 ? date('Y-m-d H:i',$now_order['pay_time']) :'',
                'paytypestr'	=>	isset($now_order['paytypestr'])?$now_order['paytypestr']:'',
                'balance_pay'	=>	$now_order['balance_pay'],
                'merchant_balance'	=>	$now_order['merchant_balance'],
                'pay_money'	=>	$now_order['pay_money'],
                'last_time'	=>	$now_order['last_time']!=0 ? date('Y-m-d H:i',$now_order['last_time']) :'',
                'last_staff'	=>	isset($now_order['last_staff'])?$now_order['last_staff']:'',
                'content'	=>	isset($now_order['content'])?$now_order['content']:'',
                'uid'	=>	$now_order['uid'],
                'nickname'	=>	$now_order['nickname'],
                'phone'	=>	$now_order['phone'],
                'longs'	=>	isset($long)?$long:0,
                'lats'	=>	isset($lat)?$lat:0,
            );
        }else{
            $arr['now_order']	=	array();
        }
        $arr['cue_list']	=	$cue_list;
        $this->returnCode(0,$arr);
    }
    # 预约订单--访店员中心
    public function order_list(){
		$store_id = I('appoint_id');
		$database_order = D('Appoint_order');
    	$database_user = D('User');
    	$database_appoint = D('Appoint');
    	$database_store = D('Merchant_store');
    	$order_id	=	I('order_id');
    	$where['appoint_id'] = $store_id;
    	$count = $database_order->field(true)->where($where)->count();
    	if($order_id){
			$where['order_id']	=	array('lt',$order_id);
    	}
    	$order_info = $database_order->field(true)->where($where)->page(1,10)->order('`order_id` DESC')->select();
        $uidArr = array();
        foreach($order_info as $v){
        	array_push($uidArr,$v['uid']);
        }
        $uidArr = array_unique($uidArr);
    	$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();
    	$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
    	$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
    	$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
    	if($order_list){
    		foreach($order_list as $v){
    			$truename	=	'';
				if($v['truename']){
					$truename	=	$v['truename'];
    			}else{
					$truename	=	$v['nickname'];
    			}
				$arr['order_list'][]	=	array(
					'order_id'	=>	$v['order_id'],
					'appoint_name'	=>	$v['appoint_name'],
					'truename'	=>	$truename,
					'phone'	=>	$v['phone'],
					'appoint_date'	=>	$v['appoint_date'].' '.$v['appoint_time'],
					'payment_money'	=>	floatval($v['payment_money']),
					'appoint_price'	=>	floatval($v['appoint_price']),
					'paid'			=>	$v['paid'],
					'service_status'=>	$v['service_status'],
				);
    		}
    	}else{
			$arr['order_list']	=	array();
    	}
    	$arr['count']	=	$count;
    	$arr['page'] = ceil($count/10);
    	$arr['status']	=	1;
    	$this->returnCode(0,$arr);
    }
    # 预约订单
    public function order_list1(){
        $database_order = D('Appoint_order');
        $database_user = D('User');
        $database_appoint = D('Appoint');
        $database_store = D('Merchant_store');
        $database_merchant_workers_appoint = D('Merchant_workers_appoint');
        $appoint_id	=	I('appoint_id');
        if ($appoint_id) {
            $where['appoint_id'] = $appoint_id;
        }else{
			$this->returnCode('20140050');
        }
		$merchant_worker_id	=	I('merchant_worker_id');
        if ($merchant_worker_id) {
            $where['merchant_worker_id'] = $merchant_worker_id;
        }
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['store_id'] = array('neq', 0);
        $order_count = $database_order->where($where)->count();
		$pindex	=	I('pindex',1);
        $order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->page($pindex,10)->select();
        $uidArr = array();
        foreach ($order_info as $v) {
            array_push($uidArr, $v['uid']);
        }
        $uidArr = array_unique($uidArr);
        $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid' => array('in', $uidArr)))->select();
        $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`')->select();
        $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
        $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
        if($order_list){
	        foreach($order_list as $v){
				$order[]	=	array(
					'order_id'	=>	$v['order_id'],
					'payment_money'	=>	$v['payment_money'],
					'store_name'	=>	$v['store_name'],
					'store_adress'	=>	$v['store_adress'],
					'appoint_type'	=>	$v['appoint_type'],
					'uid'		=>	$v['uid'],
					'nickname'	=>	$v['nickname'],
					'phone'		=>	$v['phone'],
					'content'	=>	isset($v['content'])?$v['content']:'',
					'paid'		=>	$v['paid'],
					'service_status'=>	$v['service_status'],
					'is_del'	=>	$v['is_del'],
					'order_time'=>	date('Y-m-d H:i:s',$v['order_time']),
					'pay_time'	=>	$v['pay_time']==0?0:date('Y-m-d H:i:s',$v['pay_time']),
					'type'		=>	$v['type'],
				);
	        }
        }
        $arr	=	array(
			'count'	=>	$order_count,
			'order_list'	=>	isset($order)?$order:array(),
			'page'	=>	ceil($order_count/10),
        );
        $this->returnCode(0,$arr);
    }

    public function pay_list(){
        $pay_method = D('Config')->get_pay_method('','',1);
        foreach ($pay_method as $key=>$v) {
            $temp[$key] = $v['name'];
        }
        $this->returnCode(0,$temp);
    }
    /*预约订单查找*/
	public function appoint_find(){
		$database_order = D('Appoint_order');
	    $database_user = D('User');
	    $database_appoint = D('Appoint');
	    $database_store = D('Merchant_store');
		$order_id	=	I('order_id');
		$find_type	=	I('find_type');
		$find_value	=	I('find_value');
		$appoint_where['mer_id'] = $this->merchant['mer_id'];
		if($find_type == 1 && strlen($find_value) == 16){
			$appoint_where['appoint_pass'] = $find_value;
		} else {
			if($find_type == 1){
				$appoint_where['appoint_pass'] = array('LIKE', '%'.$find_value.'%');
			} else if($find_type == 2){
				$appoint_where['order_id'] = $find_value;
			} else if($find_type == 3){
				$appoint_where['appoint_id'] = $find_value;
			} else if($find_type == 4){
				$user_where['uid'] = $find_value;
			} else if($find_type == 5){
				$user_where['nickname'] = array('LIKE', '%'.$find_value.'%');
			} else if($find_type == 6){
				$user_where['phone'] = array('LIKE', '%'.$find_value.'%');
			}
		}
		if($order_id && $find_type != 2){
			$appoint_where['order_id']	=	array('lt',$order_id);
    	}
    	$count = $database_order->where($appoint_where)->count();
	    $order_info = $database_order->field(true)->where($appoint_where)->order('`order_id` DESC')->select();
	    $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where($user_where)->select();
	    $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
	    $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
	    $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
	    if($order_list){
	    	foreach($order_list as $k=>$v){
	    		if($_POST['find_type'] == 5){
                    if(!isset($v['nickname'])){
                        unset($order_list[$k]);
                        continue;
                    }
                }else if($_POST['find_type'] == 6){
                    if(!isset($v['phone'])){
                        unset($order_list[$k]);
                        continue;
                    }
                }
    			$truename	=	'';
				if($v['truename']){
					$truename	=	$v['truename'];
    			}else{
					$truename	=	$v['nickname'];
    			}
				$arr['order_list'][]	=	array(
					'order_id'	=>	$v['order_id'],
					'appoint_name'	=>	isset($v['appoint_name'])?$v['appoint_name']:'',
					'truename'	=>	$truename,
					'phone'	=>	$v['phone'],
					'appoint_date'	=>	$v['appoint_date'].' '.$v['appoint_time'],
					'payment_money'	=>	floatval($v['payment_money']),
					'appoint_price'	=>	floatval($v['appoint_price']),
					'paid'			=>	$v['paid'],
					'service_status'=>	$v['service_status'],
				);
    		}
	    }else{
			$arr['order_list']	=	array();
	    }

		if($arr['order_list']){
			$arr['count'] = 1;
			$arr['page'] = 1;
		}else{
			$arr['count'] = 0;
			$arr['page'] = 0;
			$arr['order_list']	=	array();
		}
		$arr['status']	=	2;
		$this->returnCode(0,$arr);
	}
	//删除预约订单
    public function appoint_del(){
    	$order_id	=	I('order_id');
        if (empty($order_id)) {
            $this->returnCode('20140050');
        }
        $database_appoint_order = D('Appoint_order');
        $where['order_id'] = $order_id;
        $data['del_time'] = time();
        $data['is_del'] = 3;
        $result = $database_appoint_order->where($where)->data($data)->save();
        if ($result) {
            $this->returnCode(0);
        } else {
            $this->returnCode('20140053');
        }
    }

    # 预约工作人员列表
    public function merchant_worker(){
		//工作人员列表
        $Map['status'] = 1;
        $Map['mer_id'] = $this->merchant['mer_id'];
        $database_merchant_workers = D('Merchant_workers');
        $merchant_worker_list = $database_merchant_workers->where($Map)->field(array('merchant_worker_id','name'))->select();
        if(empty($merchant_worker_list)){
			$merchant_worker_list	=	array();
        }
        $this->returnCode(0,$merchant_worker_list);
    }
    # 预约更改服务人员
    public function worker(){
    	$order_id	=	I('order_id');
        if (empty($order_id)) {
            $this->returnCode('20140050');
        }
        $database_appoint_order = D('Appoint_order');
        $where['order_id'] = $order_id;
		$data['merchant_worker_id']	=	I('merchant_worker_id');
		$data['merchant_allocation_time']	=	time();
		if(empty($where)){
			$this->returnCode('20140054');
		}
		$result = $database_appoint_order->where($where)->data($data)->save();
		if($result){
			$this->returnCode(0);
		}else{
			$this->returnCode('20140028');
		}
    }
    # 订单详情
    public function order_detail(){
        $database_order = D('Appoint_order');
        $database_user = D('User');
        $database_appoint = D('Appoint');
        $database_store = D('Merchant_store');
        $database_merchant_workers = D('Merchant_workers');
        $database_appoint_visit_order_info = D('Appoint_visit_order_info');
        $database_appoint_product = D('Appoint_product');
        $order_id = I('order_id');
        if(empty($order_id)){
			$this->returnCode('20140025');
        }
        $where['order_id'] = $order_id;
        $where['mer_id'] = $this->merchant['mer_id'];

        $now_order = $database_order->field(true)->where($where)->find();
        $where_user['uid'] = $now_order['uid'];
        $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where($where_user)->find();
        $where_appoint['appoint_id'] = $now_order['appoint_id'];
        $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->where($where_appoint)->find();
        $where_store['store_id'] = $now_order['store_id'];
        $store_info = $database_store->field('`store_id`, `name`, `adress`')->where($where_store)->find();

        $now_order['phone'] = $user_info['phone'];
        $now_order['nickname'] = $user_info['nickname'];
        $now_order['appoint_name'] = $appoint_info['appoint_name'];
        $now_order['appoint_type'] = $appoint_info['appoint_type'];
        $now_order['appoint_price'] = $appoint_info['appoint_price'];
        $now_order['store_name'] = $store_info['name'];
        $now_order['store_adress'] = $store_info['adress'];


        $cue_info = unserialize($now_order['cue_field']);
        $cue_list = array();
    	foreach($cue_info as $key=>$val){
    		$address	=	'';
    		if(!empty($val['value'])){
    			if($val['type'] == 2){
    				$address = $val['address'];
    			}
    			if($val['long'] && $val['lat']){
					$long	=	$val['long'];
					$lat	=	$val['lat'];
    			}
    			$cue_list[]	=	array(
					'name'	=>	$val['name'],
					'value'	=>	$val['value'],
					'type'	=>	$val['type'],
					'address'=>	isset($address)?$address:'',
    			);
    		}
    	}
        $product_detail = $database_appoint_product->get_product_info($now_order['product_id']);
        if ($product_detail['status']) {
            $now_order['product_detail'] = $product_detail['detail'];
        }
		$order	=	array(
			'order_id'	=>	$now_order['order_id'],
			'store_id'	=>	$now_order['store_id'],
			'store_name'	=>	$now_order['store_name'],
			'appoint_name'	=>	$now_order['appoint_name'],
			'nickname'	=>	$now_order['nickname'],
			'phone'	=>	$now_order['phone'],
			'appoint_date'	=>	$now_order['appoint_date'],
			'appoint_time'	=>	$now_order['appoint_time'],
			'order_time'	=>	$now_order['order_time']==0?0:date('Y-m-d H:i:s',$now_order['order_time']),
			'payment_money'	=>	$now_order['payment_money'],
			'appoint_type'	=>	$now_order['appoint_type'],
			'appoint_price'	=>	$now_order['appoint_price'],
			'paid'			=>	$now_order['paid'],
			'service_status'=>	$now_order['service_status'],
			'is_del'		=>	$now_order['is_del'],
			'payment_money'	=>	$now_order['payment_money'],
			'del_time'	=>	$now_order['del_time']==0?0:date('Y-m-d H:i:s',$now_order['del_time']),
			'detail_name'	=>	isset($now_order['product_detail']['name'])?$now_order['product_detail']['name']:'',
			'detail_price'	=>	isset($now_order['product_detail']['price'])?$now_order['product_detail']['price']:'',
			'content'		=>	isset($now_order['content'])?$now_order['content']:'',
			'store_name'	=>	$now_order['store_name'],
			'tuan_type'		=>	isset($now_order['tuan_type'])?$now_order['tuan_type']:'',
			'use_time'	=>	$now_order['use_time']==0?0:date('Y-m-d H:i:s',$now_order['use_time']),
			'last_staff'	=>	$now_order['last_staff'],
			'uid'			=>	$now_order['uid'],
			'merchant_balance'=>	$now_order['merchant_balance'],
			'balance_pay'	=>	$now_order['balance_pay'],
			'pay_money'	=>	$now_order['pay_money'],
			'last_time'	=>	$now_order['last_time']==0?0:date('Y-m-d H:i:s',$now_order['last_time']),
			'longs'	=>	isset($long)?$long:0,
			'lats'	=>	isset($lat)?$lat:0,
		);
        //上门预约工作人员信息end
		$arr	=	array(
			'cue_list'	=>	$cue_list,
			'now_order'	=>	$order,
		);
		$this->returnCode(0,$arr);
    }
    # 预约格式化数据
    protected function formatArray($appoint_info, $merchant_info, $category_info){
        if (!empty($merchant_info)) {
            $merchant_array = array();
            foreach ($merchant_info as $val) {
                $merchant_array[$val['mer_id']]['mer_name'] = $val['name'];
            }
        }
        if (!empty($category_info)) {
            $category_array = array();
            foreach ($category_info as $val) {
                $category_array[$val['cat_id']]['category_name'] = $val['cat_name'];
                $category_array[$val['cat_id']]['is_autotrophic'] = $val['is_autotrophic'];
            }
        }
        if (!empty($appoint_info)) {
            foreach ($appoint_info as &$val) {
                $val['mer_name'] = $merchant_array[$val['mer_id']]['mer_name'];
                $val['category_name'] = $category_array[$val['cat_id']]['category_name'];
                $val['is_autotrophic'] = $category_array[$val['cat_id']]['is_autotrophic'];
            }
        }
        return $appoint_info;
    }
    # 店铺列表--选择使用
    public function merchant_store($type=''){
		$store = M('Merchant_store')->field('store_id,name')->where(array('status' => 1, 'mer_id' => $this->mer_id))->select();
        if ($store == false){
			$this->returnCode('20140015');
        }
		if(empty($store)){
			$store = array();
		}
        if($type == 1){
			return $store;
        }
        $this->returnCode(0,$store);
    }
	# 店员列表
	public function staff() {
		$database_merchant_store = M('Merchant_store');
        $mer_id = $this->merchant['mer_id'];
        $all_store = $database_merchant_store->where(array('mer_id' => $mer_id, 'status' => 1))->field('store_id,mer_id,name,status')->order('sort desc,store_id  ASC')->select();
        if (empty($all_store)) {
            $this->error_tips('店铺不存在！');
        }
        $allstore = array();
        foreach ($all_store as $vv) {
            $allstore[$vv['store_id']] = $vv;
        }
        $where['token'] = $mer_id;
        $_POST['store_id'] && $where['store_id'] = $_POST['store_id'];
		$staffList = M('Merchant_store_staff')->where($where)->order('`id` desc')->select();
        $arr = array();
        if (!empty($staffList)) {
            foreach ($staffList as $sv) {
            	$sv['staff_id']	=	$sv['id'];
            	$ticket = ticket::create($sv['id'], $this->DEVICE_ID, true);
            	$sv['ticket']	=	$ticket['ticket'];
                if (isset($allstore[$sv['store_id']])) {
                    $sv['storename'] = $allstore[$sv['store_id']]['name'];
                    $sv['mer_id'] = $allstore[$sv['store_id']]['mer_id'];
                    unset($sv['id'],$sv['password']);
                    $arr[] = $sv;
                }
            }
        }
		unset($staff_list, $allstore, $all_store);
		$this->returnCode(0,$arr);
    }
    # 店员添加
    public function staff_add() {
        $data['tel'] = I('tel');
        $data['name'] = I('name');
        $data['username'] = I('username');
        $data['store_id'] = I('store_id');
        $data['type'] = I('type');
        $data['phone_country_type'] = I('phone_country_type','');
        $data['time'] = $_SERVER['REQUEST_TIME'];
        if(empty($data['store_id'])){
			$this->returnCode('20140048');
        }
        $data['password'] = md5(I('password'));
        $data['token'] = $this->merchant['mer_id'];
        $checkUserName = M('Merchant_store_staff')->where(array('username' => $data['username']))->find();
        if ($checkUserName) {
            $this->returnCode('20140017');
        }
        $sql = M('Merchant_store_staff')->add($data);
        if ($sql == false) {
            $this->returnCode('20140018');
        } else {
        	$sql['staff_id']	=	$sql['id'];
        	unset($sql['id']);
            $this->returnCode(0);
        }
    }

    public function staff_type(){
        $this->returnCode(0,array(0=>'店小二',1=>'核销',2=>'店长'));
    }
    # 店员修改
    public function staff_edit() {
		$data['tel'] = I('tel');
		$data['name'] = I('name');
		$data['username'] = I('username');
		$data['is_change'] = I('is_change');
		$data['type'] = I('type');
		$data['phone_country_type'] = I('phone_country_type','');
		$password	=	I('password');
		if($password){
			$data['password'] = md5(I('password'));
		}
		$where['token'] = $this->merchant['mer_id'];
		$where['id'] = I('staff_id');
		$sql = M('Merchant_store_staff')->where($where)->save($data);
		if ($sql == false) {
			$this->returnCode('20140019');
		} else {
			$this->returnCode(0);
		}
    }

    public function staff_detail(){
        $id	=	I('staff_id');
        if ($id == false){
            $this->returnCode('20140020');
        }
        $staff = M('Merchant_store_staff')->where(array('id' => $id, 'token' => $this->merchant['mer_id']))->find();
        if ($staff == false){
            $this->returnCode('20140021');
        }
        $staff['staff_id']	= $staff['id'];
        unset($staff['token'],$staff['last_time'],$staff['time'],$staff['openid'],$staff['id']);
        $staff['phone_country_type'] = empty( $staff['phone_country_type'])?'区号': $staff['phone_country_type'];
        $this->returnCode(0,$staff);
    }
    # 店员删除
    public function staff_dell() {
        $id = I('staff_id');
        if ($id == false)
            $this->returnCode('20140020');
        $staff = M('Merchant_store_staff')->where(array('id' => $id, 'token' => $this->merchant['mer_id']))->delete();
        if ($staff == false) {
            $this->returnCode('20140022');
        } else {
            $this->returnCode(0);
        }
    }
	# 打印机设备列表
	public function hardware() {
        $where = array('mer_id' => $this->mer_id);
        $store_id = I('store_id', '');
        $store_id && $where['store_id']  = $store_id;
        $staffList = M('Orderprinter')->where($where)->select();
        foreach($staffList as &$v){
			$store = M('Merchant_store')->field('name')->where(array('store_id'=>$v['store_id']))->find();
			$v['store_name']	=	$store['name'];
        }
        if(empty($staffList)){
			$staffList	=	array();
        }
        $this->returnCode(0,$staffList);
    }
	# 添加和修改打印机
	public function hardware_add() {
		// $this->returnCode('20140013');
		$status	=	I('status',2);
        if ($status == 1) {
            $data['is_main']	=	I('is_main');
            $data['name']		=	I('name');
            $data['mcode']		=	I('mcode');
            $data['username']	=	I('username');
            $data['mkey']		=	I('mkey');
            $data['mp']			=	I('mp');
            $data['count']		=	I('count');
            $data['paid']		=	I('paid');
            $data['store_id']	=	intval($_POST['store_id']);
            $data['mer_id']		=	$this->mer_id;
			$pigcms_id			=	intval($_POST['pigcms_id']);
			$data['image']		=	intval($_POST['image']);
			$data['paper']		=	intval($_POST['paper']);
			$data['is_big']		=	intval($_POST['is_big']);
			$data['device_type']		=	intval($_POST['device_type']);

			$data['print_type']				=	intval($_POST['print_type']);
			$data['print_bluetooth_name']		=	$_POST['print_bluetooth_name'];
			$data['print_bluetooth_code']		=	$_POST['print_bluetooth_code'];
			$data['print_mobile_code']		=	$_POST['print_mobile_code']?$_POST['print_mobile_code']:'';

			if($pigcms_id >0){
            	$result = M('Orderprinter')->where(array('pigcms_id'=>$pigcms_id,'mer_id'=>$data['mer_id']))->save($data);
			}else{
				$result = M('Orderprinter')->add($data);
			}
            if ($result == false) {
            	if($pigcms_id){
					$this->returnCode('20140014');
            	}else{
					$this->returnCode('20140013');
            	}
            } else {
            	$this->returnCode(0,$pigcms_id > 0 ? '修改成功' : '添加成功');
            }
        } else {
			$pigcms_id=I('pigcms_id');
			if($pigcms_id>0){
			   $Orderprinter	=	M('Orderprinter')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant['mer_id']))->find();
			   if(empty($Orderprinter)){
				   $Orderprinter	=	array();
			   }
			}else{
			   $pigcms_id=0;
			   $Orderprinter=array();
			}
			$this->returnCode(0,$Orderprinter);
        }
    }
    # 删除打印机
    public function hardware_del() {
    	$pigcms_id	=	I('pigcms_id');
        if ($pigcms_id == false) $this->error_tips('非法操作');
        $staff = M('Orderprinter')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->mer_id))->delete();
        if ($staff !== false) {
            $this->returnCode(0);
        } else {
            $this->returnCode('20140016');
        }
    }
    # 换取星期
	protected function get_week($num) {
        switch ($num) {
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
    # 订单配送时间
    protected function order_distribution($num) {
        switch ($num) {
            case 1:
                return '工作日、双休日与假日均可送货';
            case 2:
                return '只工作日送货';
            case 3:
                return '只双休日、假日送货';
            case 4:
                return '白天没人，其它时间送货';
            default:
                return '';
        }
    }
    # 验证ticket
    private function ticket() {
    	$ticket = I('ticket', false);
		if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if (!$info) {
                $this->returnCode('20140012');
            }
        }else{
			$this->returnCode('20140011');
        }
        return $info['uid'];
	}
	# 获取省市区
	public function select_area(){
		$pid	=	I('pid',0);
		$city	=	$this->select_area_array($pid);
		$area_type	=	I('area_type',1);
		if(!empty($city)){
			if($area_type == 1){
				$arr = isset($city[0])?$this->del_field($city[0],2):array();
			}else if($area_type){
				$city_list =	M('Area')->where(array('area_pid'=>$pid,'is_open'=>1))->find();
				if($city_list){
					$city_p =	M('Area')->where(array('area_id'=>$city_list['area_pid'],'is_open'=>1))->find();
					if($city_p){
						$city_pp = D('Area')->get_arealist_by_areaPid($city_p['area_pid'],1);
						if($city_pp){
							$arr =	$this->del_field($city_pp,1);
						}else{
							$arr =	$this->del_field($city_p,1);
						}
					}else{
						$arr[] =	$this->del_field($city_list,1);
					}
				}else{
					$arr =	array();
				}
			}
			$this->returnCode(0,$arr);
		}else{
			$this->returnCode('20046027');
		}
	}
	# 获取省市区
	private function select_area_array($pid){
		$area_list[] = D('Area')->get_arealist_by_areaPid($pid,1);
		if($area_list){
			if($area_list[0][0]['area_type'] == 3){
				return $area_list;
			}else{
				$area_list[] = D('Area')->get_arealist_by_areaPid($area_list[0][0]['area_id'],1);
				return $area_list;
			}
		}else{
			return null;
		}
	}
	# 删除多余字段
	private function del_field($arr,$type){
		if($type == 2){
			foreach($arr as &$v){
				unset($v['area_sort'],$v['first_pinyin'],$v['is_open'],$v['area_url'],$v['area_ip_desc'],$v['area_type'],$v['is_hot'],$v['url']);
			}
		}else if($type == 1){
			unset($arr['area_sort'],$arr['first_pinyin'],$arr['is_open'],$arr['area_url'],$arr['area_ip_desc'],$arr['area_type'],$arr['is_hot'],$arr['url']);
		}
		return $arr;
	}
	# 图片上传
	public function up_img(){
            if ($_FILES['imgFile']['error'] != 4) {
                if($_POST['wxcard_img']){
                    $path = 'merchant';
                    $image = D('Image')->handle($this->merchant['mer_id'], $path, 1);
                    if ($image['error']) {
                        $this->returnCode('20140052');
                    } else {
                        $url = $_SERVER['DOCUMENT_ROOT'].$image['url']['imgFile'];
                        $mode = D('Access_token_expires');
                        $res = $mode->get_access_token();
                        import('ORG.Net.Http');
                        $http = new Http();
                        $file  = $url;
                        $return = $http->curlUploadFile('https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$res['access_token'],$file,1);
                        $return = json_decode($return,true);
                         //$arr['url']  =$return['url'];
                         //$this->returnCode(0,$arr);
                        $return['root_img'] = $this->config['site_url'].$image['url']['imgFile'];
                        exit(json_encode(array('errorCode' => 0,'errorMsg'=>'success', 'result' => $return)));
                    }
                }else{
                    $store_id = I('store_id');
                    $shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $store_id))->find();
                    $store_theme = isset($shop['store_theme']) ? intval($shop['store_theme']) : 0;
                    if ($store_theme) {
                        $width = '900,450';
                        $height = '900,450';
                    } else {
                        $width = '900,450';
                        $height = '500,250';
                    }
                    $param = array('size' => $this->config['group_pic_size']);
                    $param['thumb'] = true;
                    $param['imageClassPath'] = 'ORG.Util.Image';
                    $param['thumbPrefix'] = 'm_,s_';
                    $param['thumbMaxWidth'] = $width;
                    $param['thumbMaxHeight'] = $height;
                    $param['thumbRemoveOrigin'] = false;
                    $image = D('Image')->handle($this->merchant['mer_id'], 'goods', 1, $param);
                    if ($image['error']) {
                        $this->returnCode('20140052');
                    } else {
                        $arr	=	array(
                            'url'	=>	$this->config['site_url'].$image['url']['file'],
                            'title'	=>	$image['title']['file'],
                        );
                        $this->returnCode(0,$arr);
                    }
                }
            } else {
                $this->returnCode('20140051');
            }

	}

    //商家余额接口
    public function merchant_money_info(){
        $mer_id  = $this->merchant['mer_id'];
        $type = !empty($_POST['type'])?$_POST['type']:'group';
        //$arr['type'] = $this->get_alias_name();
        $now_mer = M('Merchant')->where(array('mer_id'=>$mer_id))->find();
        if($this->config['open_allinyun']==1){
            import('@.ORG.AccountDeposit.AccountDeposit');
            $deposit = new AccountDeposit('Allinyun');
            $allyun = $deposit->getDeposit();
            $allinyun = M('Merchant_allinyun_info')->where(array('mer_id'=>$mer_id))->find();
            $allyun->setUser($allinyun);
            if($allinyun['status']!=0){
                $balance = $allyun->queryBalance();
            }
            $now_mer['money'] = floatval($balance['signedResult']['allAmount']/100);
        }
        $arr['merchant_money'] = $now_mer['money'];            //商家余额
        $arr['store_list'] = $this->get_store_name($mer_id,$type);
        $where['mer_id'] = $mer_id;
        $where['type'] = array('neq','withdraw');
        $today_zero = strtotime(date('Y-m-d'));
        $where['_string'] = 'use_time>'.$today_zero.' AND use_time <='.($today_zero+86400);
        $today_money  = M('Merchant_money_list')->where($where)->sum('money');
        $arr['today_money']  = empty($today_money)?0:$today_money;
        $arr['today_count'] =  M('Merchant_money_list')->where($where)->count();
        $this->returnCode(0,$arr);
    }

    //获取业务类型
    public  function get_alias_name(){
        $arr[]='group';
        $arr[]='shop';
        $arr[]='group';
        $arr[]='meal';
        if(C('config.appoint_page_row')>0){
            $arr[]='appoint';
        }
        if(C('config.is_cashier')	||C('config.pay_in_store')){
            $arr[]='store';
        }
        if(C('config.is_open_weidian')){
            $arr[]='weidian';
        }
        if(C('config.wxapp_url')){
            $arr[]='wxapp';
        }
        return $arr;
    }

    //业务类型中文
    public  function get_alias_c_name(){
        $arr[]=array('type'=>'group','name'=>$this->config['group_alias_name']);
        $arr[]=array('type'=>'shop','name'=>$this->config['shop_alias_name']);
        $arr[]=array('type'=>'meal','name'=>$this->config['meal_alias_name']);
        if(C('config.appoint_page_row')>0) {
            $arr[] = array('type' => 'appoint', 'name' => $this->config['appoint_alias_name']);
        }
        if(C('config.is_cashier')	||C('config.pay_in_store')) {
            $arr[] = array('type' => 'store', 'name' => '优惠买单');
        }
        if ($this->config['pay_in_store']|| $this->config['is_cashier']) {
            $arr[]=array('type'=>'cash','name'=>'到店支付');
        }
        if(C('config.is_open_weidian')) {
            $arr[] = array('type' => 'weidian', 'name' => '微店');
        }
        if(C('config.wxapp_url')) {
            $arr[] = array('type' => 'wxapp', 'name' => '营销');
        }
        $arr[]=array('type'=>'withdraw','name'=>'提现');
        $arr[]=array('type'=>'activity','name'=>'平台活动');

        return $arr;
    }

    /**
     * @return 选择分类
     */
    public  function get_alias_c_name2(){
        return array(
            'all'=>'选择分类',
            'group'=>$this->config['group_alias_name'],
            'shop'=>$this->config['shop_alias_name'],
            'meal'=>$this->config['meal_alias_name'],
            'appoint'=>$this->config['appoint_alias_name'],
            'waimai'=>$this->config['waimai_alias_name'],
            'store'=>'到店',
            'weidian'=>'微店',
            'wxapp'=>'营销',
            'withdraw'=>'提现',
            'coupon'=>'提现',
            'withdraw'=>'提现',
            'activity'=>'平台活动',
            'spread'=>'商家推广佣金'
        );
    }

    public function get_store_name($mer_id,$type){
        $store_list = D('Merchant_store')->field('store_id,name,have_group,have_meal,have_shop')->where(array('mer_id'=>$mer_id))->select();
        if($type=='group'){
            foreach($store_list as $key=>$value){
                if(empty($value['have_group'])){
                    unset($store_list[$key]);
                }
            }
        }else if($type=='meal'){
            foreach($store_list as $key=>$value){
                if(empty($value['have_meal'])){
                    unset($store_list[$key]);
                }
            }
        }else if($type=='shop'){
            foreach($store_list as $key=>$value){
                if(empty($value['have_shop'])){
                    unset($store_list[$key]);
                }
            }
        }else{
            $store_list=$store_list;
        }
        $arr=array();
        foreach($store_list as $key=>$value){
            unset($store_list[$key]['have_group']);
            unset($store_list[$key]['have_meal']);
            unset($store_list[$key]['have_shop']);
            $arr[]=$store_list[$key];
        }
        return $arr;
    }

    //获取商家余额按时间统计数据
    public function merchant_money_date(){
        $mer_id = intval($this->merchant['mer_id']);
        $store_id = $_POST['store_id'];
        $day  = $_POST['day'];
        if(empty($_POST['period']) || $_POST['period'] == '-'){
			$_POST['period'] = date('Y/m/d').'-'.date('Y/m/d');
		}
        if(isset($_POST['period'])&&!empty($_POST['period'])){
            $period = explode('-',$_POST['period']);
            $_POST['begin_time'] = $period[0];
            $_POST['end_time'] = $period[1];
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->returnCode('20140055'); //##
            }
            $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
            if($_POST['store_id']){
                $time_condition = " (l.use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            }else{
                $time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            }
            $condition_merchant_request['_string']=$time_condition;
            $period = true;
        }

        if(isset($_POST['type'])&&!empty($_POST['type'])){
            $type=$_POST['type'];
            if($type=='activity'){
                $condition_merchant_request['type'] = 'coupon or yydb';
            }else{
                $condition_merchant_request['type'] = $type;
            }
        }else{
            $type='group';
            $condition_merchant_request['type'] = $type;
        }
        if($_POST['store_id']!=0&&$type!='wxapp'&&$type!='activity'){
            foreach($condition_merchant_request as $k=>$v){
                if($k != '_string'){
                    $condition_merchant_request['l.'.$k] = $v;
                    unset($condition_merchant_request[$k]);
                }
            }
            $condition_merchant_request['l.store_id'] = $_POST['store_id'];
        }
        $now_time = $_SERVER['REQUEST_TIME'];
        $today_zero_time = mktime(0,0,0,date('m',$now_time),date('d',$now_time), date('Y',$now_time));

        if(empty($day)){
            $day =2;
        }
        if($day < 1){
            $this->returnCode('20140056');
        }
        if($day==1&&!$period){
            if(!empty($store_id)){
                $condition_merchant_request['l.use_time'] = array(array('egt',$today_zero_time),array('elt',$now_time));
            }else{
                $condition_merchant_request['use_time'] = array(array('egt',$today_zero_time),array('elt',$now_time));
            }

            if(!empty($store_id)){
                $request_list = M('Merchant_money_list l')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,l.store_id')->join(C('DB_PREFIX').$type.'_order o ON o.order_id = l.order_id ')->where($condition_merchant_request)->select();
            }else{
                $condition_merchant_request['mer_id'] =$mer_id;
                $request_list = M('Merchant_money_list')->field(true)->where($condition_merchant_request)->select();
            }
        }else{
            if(!$period) {
                if ($day == 2) {
                    //本月
                    $today_zero_time = mktime(0, 0, 0, date('m'), 1, date('Y'));
                    if (!empty($store_id)) {
                        $condition_merchant_request['l.use_time'] = array(array('egt', $today_zero_time), array('elt', $now_time));
                    } else {
                        $condition_merchant_request['use_time'] = array(array('egt', $today_zero_time), array('elt', $now_time));

                    }
                } else {
                    if (!empty($store_id)) {
                        $condition_merchant_request['l.use_time'] = array(array('egt', $today_zero_time - (($day - 1) * 86400)), array('elt', $today_zero_time));
                    } else {
                        $condition_merchant_request['use_time'] = array(array('egt', $today_zero_time - (($day) * 86400)), array('elt', $now_time));
                    }
                }
            }
            if(!empty($store_id)){
                $request_list = M('Merchant_money_list l')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,l.store_id')->join(C('DB_PREFIX').$type.'_order o ON o.order_id = l.order_id ')->where($condition_merchant_request)->select();
            }else{
                $condition_merchant_request['mer_id'] = $mer_id;
                $request_list = M('Merchant_money_list')->field(true)->where($condition_merchant_request)->select();
            }
        }
        $tmp_array=array();
        if(($day==1&&!$period)||($period&&($_POST['end_time']==$_POST['begin_time']))){
            foreach($request_list as $value){
                $tmp_time = date('H',$value['use_time']);
                if($tmp_array[$tmp_time][$value['type']]['count']){
                    $tmp_array[$tmp_time][$value['type']]['count']=1;
                }else{
                    $tmp_array[$tmp_time][$value['type']]['count']++;
                }
                if($value['income']==1){
                    $tmp_array[$tmp_time][$value['type']]['income'] += $value['money'];
                }else{
                    $tmp_array[$tmp_time][$value['type']]['expend'] += $value['money'];
                }
            }
        }else{
            foreach($request_list as $value){
                if($day==2&&!$period){
                    $tmp_time = date('d',$value['use_time']);
                }else{
                    $tmp_time = date('ymd',$value['use_time']);
                }
                if(empty($tmp_array[$tmp_time][$value['type']]['count'])){
                    $tmp_array[$tmp_time][$value['type']]['count']=1;
                }else{
                    $tmp_array[$tmp_time][$value['type']]['count']++;
                }
                if($value['income']==1){
                    $tmp_array[$tmp_time][$value['type']]['income'] += $value['money'];
                }else{
                    $tmp_array[$tmp_time][$value['type']]['expend'] += $value['money'];
                }
            }
        }

        ksort($tmp_array);
        $alias_name = $this->get_alias_name();
        if(($day==1&&!$period)||($period&&($_POST['end_time']==$_POST['begin_time']))){
            for($i=0;$i<=date('H',$now_time);$i++){
                $pigcms_list['xAxis_arr'][]  = $i.'时';
                $time_arr[]=$i;
            }
        }else{
            if($day==2){
                $days = date('d',$now_time);
                for($i=1;$i<=$days;$i++){
                    $pigcms_list['xAxis_arr'][]  = $i.'日';
                    $time_arr[]=$i;
                }
            }else{
                for($i=$day-1;$i>=0;$i--){
                    $pigcms_list['xAxis_arr'][]  = date('y-m-d',$today_zero_time-$i*86400);
                    $time_arr[]=date('ymd',$today_zero_time-$i*86400);
                }
            }
        }
        if($period){
            unset($pigcms_list['xAxis_arr']);
            unset($time_arr);
            $start_day =strtotime($_POST['end_time']);
            $day = (strtotime($_POST['end_time'])-strtotime($_POST['begin_time']))/86400;
            if($day==0){
                for($i=0;$i<24;$i++){
                    $pigcms_list['xAxis_arr'][]  = $i.'时';
                    $time_arr[]=$i;
                }
            }else{
                for($i=$day;$i>=0;$i--){
                    $pigcms_list['xAxis_arr'][]  = date('y-m-d',$start_day-$i*86400);
                    $time_arr[]=date('ymd',$start_day-$i*86400);
                }
            }
        }
        $no_data_time= array();
        foreach($time_arr as $v){
            if($tmp_array[$v]){
                $pigcms_list['income'][] = round($tmp_array[$v][$type]['income'],2);
                $pigcms_list['income_all'] += round($tmp_array[$v][$type]['income'],2);
                $pigcms_list['order_count'][]   = intval($tmp_array[$v][$type]['count']);
                $pigcms_list['order_count_all'] += intval($tmp_array[$v][$type]['count']);
            }else{
                if(!in_array($v,$no_data_time)){
                    $pigcms_list['income'][] = 0;
                    $pigcms_list['order_count'][]   = 0;
                }
            }
        }
        $this->returnCode(0,$pigcms_list);
    }

    //商家收入
    public function get_income_list(){

        $_GET['page'] = $_POST['page'];
        $mer_id = intval($this->merchant['mer_id']);
        if(!empty($_POST['store_id'])){
            $condition['store_id'] = $_POST['store_id'];
        }


        if($_POST['type']!='all'){
            $condition['type'] = $_POST['type'];
        }


        if(isset($_POST['period'])&&!empty($_POST['period'])){
            $period = explode('-',$_POST['period']);
            $_POST['begin_time'] = $period[0];
            $_POST['end_time'] = $period[1];

            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->returnCode('20140055');
            }
            $period = array(strtotime($_POST['begin_time'] . " 00:00:00"), strtotime($_POST['end_time'] . " 23:59:59"));
            $time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['_string']=$time_condition;
            // $where['_string'] = " use_time BETWEEN ".$period[0].' AND '.$period[1];

        }
        if ($_GET['page']) {
            $_SESSION['condition'] = $condition;
        }

        if($_POST['type']=='store'){
            unset(  $condition['type'] );
            $condition['_string'] = (empty($condition['_string'])?'':$condition['_string'].' AND ')."(type='store' OR type='cash') ";
        }

        $res = D('Merchant_money_list')->get_income_list($mer_id,0,$condition);

        $alias_name = $this->get_alias_c_name2();
        $income_list = array();
        foreach ( $res['income_list'] as &$inc) {
            $arr=array();
            $arr['id']=$inc['id'];
            if($inc['store_id']>0){
                $arr['store_name'] = $inc['store_name'];
            }else{
                $arr['store_name'] = '';
            }
            $arr['type'] = $inc['type'];
            $arr['type_name'] = $alias_name[$inc['type']]? $alias_name[$inc['type']]:'';
            $arr['desc'] = str_replace('</br>','',$inc['desc']);
            $arr['money'] = strval(pow(-1,($inc['income']+1))*$inc['money']);

            $income_list[]=$arr;
        }

        $arr = array('income_list'=>$income_list,'page_num'=>$res['page_num']);
        $condition['mer_id'] = $mer_id;
        $where_total = array('mer_id'=>$mer_id,'income'=>1,'type'=>array('neq','withdraw'));
        $all_money  = M('Merchant_money_list')->where($where_total)->sum('money');

        $arr['all_money']  = empty($all_money)?0:$all_money;
        $arr['all_count'] =  intval(M('Merchant_money_list')->where($where_total)->count());
        if(empty($condition['_string'])|| strtotime($_POST['begin_time'])==strtotime(date('Y-m-d',time()))){
            $today_zero = strtotime(date('Y-m-d'));
            $condition['_string'] = 'use_time>'.$today_zero.' AND use_time <='.($today_zero+86400)." AND type <> 'withdraw'";
        }else{
            $condition['_string'] .= " AND type <> 'withdraw'";
        }
        if($income_list){


            $arr['today_money'] = $res['income_sum'];

            $arr['today_count'] = $res['income_count'];

//			$today_money  = M('Merchant_money_list')->where($condition)->sum('money');

//			$arr['today_money']  = empty($today_money)?0:$today_money;
//			$arr['today_count'] =  intval(M('Merchant_money_list')->where($condition)->count());
		}else{
			$arr['today_money'] = 0;
			$arr['today_count'] = 0;
		}

        $this->returnCode(0,$arr);

    }

    //商家提现记录
    public function withdraw_list(){
//        $mer_id = intval($this->merchant['mer_id']);
//        $_GET['page'] = $_POST['page'];
//        $res = D('Merchant_money_list')->get_withdraw_list($mer_id);
//
//        $withdraw=array();
//        $pay_type = array(
//            '0' => '银行',
//            '1' => '支付宝',
//        );
//        foreach($res['withdraw_list'] as $v){
//            $arr = array();
//            $arr['id'] = $v['id'];
//            $arr['time'] = date('Y/m/d H:i:s',$v['withdraw_time']);
//            $arr['money'] = strval($v['money']/100);
//            if($v['status']==0){
//                $arr['status'] = '待审核';
//            }elseif($v['status']==1){
//                $arr['status'] = '已通过';
//            }elseif($v['status']==2){
//                $arr['status'] = '被驳回';
//            }else{
//                $arr['status'] = '待审核';
//            }
//            $arr['remark'] = $v['remark'];
//            $withdraw[]=$arr;
//        }
//        $return['withdraw']=$withdraw;
//        $return['page_num']=$res['page_num'];

        import('@.ORG.merchant_page');
        $_GET['page'] = $_POST['page'];
        $mer_id = intval($this->merchant['mer_id']);
        if ($_POST['type'] == 1 || !isset($_POST['type'])) {
            $where['status'] = array('neq',4);
            $where['mer_id'] = $mer_id;
            $withdraw_to_other = M('Withdraw_list')->where(array('pay_id'=>$mer_id))->select();
            foreach ($withdraw_to_other as $value) {
                $ids[] = $value['withdraw_id'];
            }
            if($ids){
                $where['id'] = array('not in',$ids);
            }
            $count = M('Merchant_withdraw')->where($where)->count();
            $p = new Page($count, 20);
            $withdraw_list =M('Merchant_withdraw')->where($where)->order('withdraw_time DESC')->limit($p->firstRow,$p->listRows)->select();

        } elseif ($_POST['type'] == 2) {
            $count = M('Withdraw_list')->where(array('pay_id'=>$mer_id))->count();
            $p = new Page($count, 20);
            $withdraw_list = M('Withdraw_list')->field('id,status,pay_type,remark,desc,old_money as money,add_time as withdraw_time,withdraw_id')->where(array('pay_id' => $mer_id))->order('withdraw_time DESC')->limit($p->firstRow,$p->listRows)->select();

        }
        $where['pay_type'] = 0;
        $where['pay_id'] =$mer_id;
        //$account_list = M('Withdraw_list')->field('account,remark')->where($where)->group('account')->select();
        $this->assign('withdraw_list', $withdraw_list);
        $pay_type = array(
            '0' => '银行',
            '1' => '支付宝',
        );

        foreach($withdraw_list as $v){
            $arr = array();
            $arr['id'] = $v['id'];
            $arr['time'] = date('Y/m/d H:i:s',$v['withdraw_time']);
            $arr['money'] = strval($v['money']/100);
            if($v['status']==0){
                $arr['status'] = '待审核';
            }elseif($v['status']==1){
                $arr['status'] = '已通过';
            }elseif($v['status']==4){
                $arr['status'] = '被驳回';
            }elseif($v['status']==2){
                $arr['status'] = '被驳回';
            }else{
                $arr['status'] = '待审核';
            }
            $mer_withdraw = M('Merchant_withdraw')->where(array('id'=>$v['withdraw_id']))->find();
            $arr['remark'].=$mer_withdraw['remark'];
            $arr['pay_type'] = isset($v['pay_type'])?$pay_type[$v['pay_type']]:'微信';
            if($v['desc']!=''){
                $arr['remark'] .= '|'.$arr['pay_type'].'|'.$v['desc'].'|'.$v['remark'];
            }else{

                $arr['remark'] .= '|'.$v['remark'];
            }

            $withdraw[]=$arr;
        }


        $return['withdraw']=$withdraw?$withdraw:array();
        $return['page_num']=$p->totalPage;

        $this->returnCode(0,$return);
    }

    //提现记录详情
    public function withdraw_info(){
        $withdraw = M('Merchant_withdraw')->where(array('id'=>$_POST['id']))->find();
        $withdraw['name'] = $this->merchant['name'];
        return $withdraw;
    }

    public function income_info(){
        $id = $_POST['id'];
        $res = M('Merchant_money_list')->where(array('id'=>$id))->find();
        if(empty($res)){
            $this->returnCode('20140064');
        }

        $type = $res['type'];
        $alias_name = $this->get_alias_c_name2();
        $mer_id = $this->merchant['mer_id'];
        $merchant = M('Merchant')->field(true)->where(array('mer_id '=> $mer_id))->find();
        $arr['percent'] =  $res['percent'];

        $order_id = $res['order_id'];
        $income[]=array('name'=>'订单编号','value'=>$res['order_id']);
        $income[]=array('name'=>'数量','value'=>$res['num']);
        if($type!='withdraw'){
            $income[]=array('name'=>'平台抽佣比例'.$arr['percent'].'%','value'=>$res['system_take']);
        }
        $income[]=array('name'=>'当前商家余额','value'=>strval($res['now_mer_money']));
        if($type!='withdraw'){
            $income[]=array('name'=>'支付时间','value'=>date('Y/m/d H:i:s',$res['use_time']));
        }else{
            $income[]=array('name'=>'提现时间','value'=>date('Y/m/d H:i:s',$res['use_time']));
        }
        if($type=='withdraw'){
            $income[]=array('name'=>'总额','value'=>strval($res['money']));
        }else{
            $income[]=array('name'=>'总额','value'=>strval($res['total_money']));
        }
        $income[]=array('name'=>'描述','value'=>$res['desc']);
        $income[]=array('name'=>'类型','value'=>$alias_name[$res['type']]);

        switch($type){
            case 'group':
                $where['real_orderid']=$order_id;
                $order = M('Group_order')->where($where)->find();
                $pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
                $income[]=array('name'=>'手机号码','value'=>$order['phone']);
                $income[]=array('name'=>'支付方式','value'=>$pay_method);
                $income[]=array('name'=>'订单流水','value'=>$order['orderid']);
                break;
            case 'meal':
                $where['order_id']=$order_id;
                $order = M('Meal_order')->where($where)->find();
                $income[]=array('name'=>'手机号码','value'=>$order['phone']);
                $pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
                $income[]=array('name'=>'支付方式','value'=>$pay_method);
                $income[]=array('name'=>'订单流水','value'=>$order['orderid']);
                break;
            case 'shop':
                $where['real_orderid']=$order_id;
                $order = M('Shop_order')->where($where)->find();
                $income[]=array('name'=>'手机号码','value'=>$order['userphone']);
                $pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
                $income[]=array('name'=>'支付方式','value'=>$pay_method);
                $income[]=array('name'=>'订单流水','value'=>$order['orderid']);
                break;
            case 'appoint':
                $where['order_id']=$order_id;
                $order = M('Appoint_order')->where($where)->find();
                $income[]=array('name'=>'手机号码','value'=>$order['phone']);
                $pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
                $income[]=array('name'=>'支付方式','value'=>$pay_method);
                $income[]=array('name'=>'订单流水','value'=>$order['orderid']);
                $income[]=array('name'=>'预约时间','value'=>$order['appoint_date'].'/'.$order['appoint_time']);
                break;
            case 'store':
                $where['order_id']=$order_id;
                $order = M('Store_order s')->field(true)->join(C('DB_PREFIX').'user u ON s.uid = u.uid ')->where($where)->find();
                $pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
                $income[]=array('name'=>'手机号码','value'=>$order['phone']);
                $income[]=array('name'=>'支付方式','value'=>$pay_method);
                $income[]=array('name'=>'订单流水','value'=>$order['orderid']);
                break;
            case 'wxapp':
                $where['order_id']=$order_id;
                $order = M('Wxapp_order w')->field(true)->join(C('DB_PREFIX').'user u ON w.uid = u.uid ')->where($where)->find();
                $pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
                $income[]=array('name'=>'手机号码','value'=>$order['phone']);
                $income[]=array('name'=>'支付方式','value'=>$pay_method);
                $income[]=array('name'=>'手机号码','value'=>$order['phone']);
                $income[]=array('name'=>'订单流水','value'=>$order['orderid']);
                break;
            case 'weidian':
                $where['order_id']=$order_id;
                $order = M('Weidian_order w')->field(true)->join(C('DB_PREFIX').'user u ON w.uid = u.uid ')->where($where)->find();
                $pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
                $income[]=array('name'=>'手机号码','value'=>$order['phone']);
                $income[]=array('name'=>'支付方式','value'=>$pay_method);
                $income[]=array('name'=>'订单流水','value'=>$order['orderid']);
                break;
            case 'coupon':
                $condition_table = array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_coupon_record'=>'ecr',C('DB_PREFIX').'user'=>'u');
                $condition_where = "`ear`.`activity_list_id`=`eal`.`pigcms_id` AND `ecr`.`record_id`=`ear`.`pigcms_id` AND `eal`.`mer_id`='{$mer_id}' AND `ecr`.`pigcms_id`='{$order_id}' AND `ear`.`uid`=`u`.`uid`";
                $order= D('')->field('`ecr`.`pigcms_id`,`ecr`.`number`,`eal`.`title`,`ear`.`uid`,`eal`.`pigcms_id` as id,`eal`.`money`,`eal`.`name`,`u`.`nickname`,`u`.`phone`,`ecr`.`check_time`,`ecr`.`last_staff`')->where($condition_where)->table($condition_table)->find();
                $income[]=array('name'=>'优惠券码','value'=>$order['number']);
                $income[]=array('name'=>'消费者','value'=>$order['nickname']);
                $income[]=array('name'=>'手机号码','value'=>$order['phone']);
                break;
            case 'yydb':
                $where['pigcms_id'] = $order_id;
                $order = M('Extension_activity_list e')->field(true)->join(C('DB_PREFIX').'user u ON e.lottery_uid = u.uid ')->where($where)->find();
                $income[] = array('name'=>'项目名称','value'=>$order['title']);
                $income[] = array('name'=>'提现商家','value'=>$this->merchant['name']);
                $income[] = array('name'=>'夺宝用户','value'=>$order['nickname']);
                $income[] = array('name'=>'夺宝用户','value'=>$order['phone']);
                break;
            case 'withdraw':
                $order = M('Merchant_withdraw')->where(array('id'=>$res['order_id']))->find();
                $income[] = array('name'=>'提现人','value'=>$order['name']);
                $income[] = array('name'=>'提现商家','value'=>$this->merchant['name']);
                break;
        }

        $this->returnCode(0,array('income_info'=>$income));

    }
    //提现

    public function withdraw_method(){
        $arr['withdraw_type']=array(
            '0'=>'银行卡',
            '1'=>'支付宝',
            '2'=>'微信'
        );
        if($this->config['company_bank_pay']==0){
            unset($arr['withdraw_type'][0]);
        }
        if($this->config['company_alipay_pay']==0){
            unset($arr['withdraw_type'][1]);
        }
        $now_merchant = D('Merchant')->get_info($this->merchant['mer_id']);
        $arr['now_money'] = $now_merchant['money'];
        $arr['merchant_withdraw_fee_type'] = $this->config['merchant_withdraw_fee_type'];
        $arr['company_pay_mer_money'] = $this->config['company_pay_mer_money'];
        $arr['company_pay_mer_percent'] = $this->config['company_pay_mer_percent'];
        $arr['company_least_money'] =   $this->config['min_withdraw_money']?$this->config['min_withdraw_money']:$this->config['company_least_money'];

        $this->returnCode(0,$arr);
    }
    public function withdraw(){
        if($this->config['company_pay_open']=='0') {
            $this->returnCode('20140059');
        }
        $mer_id = intval($this->merchant['mer_id']);
        $now_merchant = M('Merchant')->where(array('mer_id'=>$mer_id))->find();

        if(M('Merchant_withdraw')->where(array('mer_id'=>$mer_id,'status'=>array('in','0,4')))->find()){
            $this->returnCode('20140060');
        }
        if($_POST['money']>0){
            if($_POST['money']>$now_merchant['money']){
                $this->returnCode('20140061');
            }
            $money = floatval(($_POST['money']))*100;
            if($_POST['money']<$this->config['min_withdraw_money']){
                $this->returnCode('20140062','','不能低于最低提款额 '.$this->config['min_withdraw_money'].' 元!');
            }
            if ($_POST['money'] < $this->config['company_least_money']) {
                $this->returnCode('20140062','','不能低于最低提款额 '.$this->config['company_least_money'].' 元!');
            }

            if(isset($_POST['withdraw_type']) && $_POST['withdraw_type']!=2){
                $money = $_POST['money'];
                if(empty($_POST['name'])){
                    $this->returnCode('20046031');
                }
                if($money<$this->config['company_least_money']){
                    $this->returnCode('20140062','','不能低于最低提款额 '.$this->config['company_least_money'].' 元!');
                }

                if(!is_numeric($_POST['withdraw_type'])){
                    $this->returnCode('20140066');
                }
                $data_companypay['type'] = 'mer';
                $data_companypay['pay_type'] = $_POST['withdraw_type'];//0 银行卡，1 支付宝 2微信
                $data_companypay['pay_id'] = $now_merchant['mer_id'];
                $remark = '';
                if($_POST['withdraw_type']==0){
                    $data_companypay['account'] = $_POST['card_number'];
                    if(empty($_POST['card_number']) || empty($_POST['card_username']) ||empty($_POST['bank']) ){
                        $this->returnCode('20140067');
                    }
                    $remark = '开户名：'.$_POST['card_username'].',开户行：'.$_POST['bank'];
                }else if ($_POST['withdraw_type']==1){
                    if(empty($_POST['alipay_account'])  ){
                        $this->returnCode('20140068');
                    }
                    $data_companypay['account'] = $_POST['alipay_account'];
                }


                $data_companypay['truename'] = $_POST['name'];
                $data_companypay['name'] = $now_merchant['name'];
                $data_companypay['remark'] = $remark ;
                $data_companypay['phone'] = $now_merchant['phone'];
                if($this->config['merchant_withdraw_fee_type']==0){
                    $data_companypay['money']     = bcmul($money * ((100 - $this->config['company_pay_mer_percent']) / 100), 100);
                }else if($this->config['merchant_withdraw_fee_type']==1){
                    $data_companypay['money']     = ($money-$this->config['company_pay_mer_money'])*100;
                }
                if($data_companypay['money']<=0){
                    $this->returnCode('20140064','','您的提现金额不足以抵扣提现手续费，不能提现');
                }
                $data_companypay['old_money'] = $money*100;
                $data_companypay['desc'] = "商户提现对账订单|商户ID ".$now_merchant['mer_id']." |转账 ".$money." 元" ;
//                if($this->config['company_pay_mer_percent']>0){
//                    $data_companypay['desc'] .= '|手续费 '.(($data_companypay['old_money'] -  $data_companypay['money'])/100) .' 比例 '.$this->config['company_pay_mer_percent'].'%';
//                }

                if ($this->config['company_pay_mer_percent'] > 0 && $this->config['merchant_withdraw_fee_type']==0) {
                    $system_take = ($data_companypay['old_money'] -  $data_companypay['money'])/100;
                    $data_companypay['desc'] .= '|手续费 ' .(($data_companypay['old_money'] -  $data_companypay['money'])/100) . ' 比例 ' . $this->config['company_pay_mer_percent'] . '%';
                }else if($this->config['merchant_withdraw_fee_type']==1&&$this->config['company_pay_mer_money']>0){
                    $data_companypay['desc'] .= '|手续费 '.$this->config['company_pay_mer_money'].'元';
                    $system_take = $this->config['company_pay_mer_money'];
                }

                $data_companypay['status'] = 0;
                $data_companypay['add_time'] = time();


                $date_mer['mer_id']=$mer_id;
                $date_mer['name']=$_POST['name'];
                $date_mer['money']=   $data_companypay['money'] ;
                $date_mer['old_money']=    $data_companypay['old_money'] ;
                $date_mer['remark']=  $_POST['remark'];
                $date_mer['withdraw_time'] = time();
                $date_mer['status'] = 4;
                $date_mer['from_type'] = 1;
                $res =M('Merchant_withdraw')->add($date_mer);
                if(!$res){
                    $this->returnCode('20140063');die;
                }
                $data_companypay['withdraw_id'] = $res;
                $result = D('Merchant_money_list')->use_money($mer_id,$money,'withdraw',  $data_companypay['desc'] ,$data_companypay['withdraw_id'],$this->config['company_pay_mer_percent'],$system_take);

                $withdraw_id = M('Withdraw_list')->add($data_companypay);
                D('Merchant_money_list')->withdraw_notice($res);

                $this->returnCode(0,array('status'=>1,'msg'=>'提现成功'));die;
            }else {
                $res = D('Merchant_money_list')->withdraw($mer_id, $_POST['name'], $money, $_POST['remark']);

                if ($res['error_code']) {
                    $this->returnCode('20140063');
                } else {
                    $this->returnCode(0,array('status'=>1,'msg'=>'提现成功'));
                }
            }
        }else{
            $this->returnCode('20045014');
        }
    }

    public  function  get_bank_name(){
        $card_number = $_POST['card_number'];
        require_once APP_PATH . 'Lib/ORG/BankList.class.php';
        if($res = $this->bankInfo($card_number,$bankList)){
            $this->returnCode(0,array('bank_name'=>$res));
        }else{
            $this->returnCode('20140065');
        }
    }

    public  function  get_withdraw_fee(){
        $money = $_POST['money']?$_POST['money']:0;
        $this->returnCode(0,array('withdraw_fee'=>$money*($this->config['company_pay_mer_percent'])/100));
    }


    function bankInfo($card,$bankList)
    {
        $card_8 = substr($card, 0, 8);
        if (isset($bankList[$card_8])) {
            return $bankList[$card_8];
        }
        $card_6 = substr($card, 0, 6);
        if (isset($bankList[$card_6])) {
            return $bankList[$card_6];

        }
        $card_5 = substr($card, 0, 5);
        if (isset($bankList[$card_5])) {
            return $bankList[$card_5];

        }
        $card_4 = substr($card, 0, 4);
        if (isset($bankList[$card_4])) {
            return $bankList[$card_4];

        }
        return null;
    }

    //会员卡

    public function card_num_date(){

        //所有
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['uid'] = array('gt',0);
        $arr['all_count'] = M('Card_userlist')->where($where)->count();
        //今日
        $begin=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $where['_string'] = 'add_time >'.$begin.' AND add_time <'.$end;
        $arr['today_count'] = M('Card_userlist')->where($where)->count();
        //本周
        $begin=mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"));
        $end=mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"));
        $where['_string'] = 'add_time >'.$begin.' AND add_time <'.$end;
        $arr['this_week_count'] = M('Card_userlist')->where($where)->count();
        //本月
        $begin=mktime(0, 0 , 0,date("m"),1,date("Y"));
        $end=mktime(23,59,59,date("m"),date("t"),date("Y"));
        $where['_string'] = 'add_time >'.$begin.' AND add_time <'.$end;
        $arr['this_month_count'] = M('Card_userlist')->where($where)->count();
        //本季度
        $season = ceil((date('n'))/3);
        $begin= mktime(0, 0, 0,$season*3-3+1,1,date('Y'));
        $end=mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'));
        $where['_string'] = 'add_time >'.$begin.' AND add_time <'.$end;
        $arr['this_season_count'] = M('Card_userlist')->where($where)->count();
        //本年
        $t = time();
        $begin = mktime(0,0,0,1,1,date("Y",$t));
        $end=mktime(23,59,59,12,31,date("Y",$t));
        $where['_string'] = 'add_time >'.$begin.' AND add_time <'.$end;
        $arr['this_year_count'] = M('Card_userlist')->where($where)->count();

        //上周
        $begin=mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"));
        $end=mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"));
        $where['_string'] = 'add_time >'.$begin.' AND add_time <'.$end;
        $arr['last_week_count'] = M('Card_userlist')->where($where)->count();
        //上月
        $begin=mktime(0, 0 , 0,date("m")-1,1,date("Y"));
        $end=mktime(23,59,59,date("m") ,0,date("Y"));
        $where['_string'] = 'add_time >'.$begin.' AND add_time <'.$end;
        $arr['last_month_count'] = M('Card_userlist')->where($where)->count();
        //上季度
        $season = ceil((date('n'))/3)-1;
        $begin=mktime(0, 0, 0,$season*3-3+1,1,date('Y'));
        $end=mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'));

        $where['_string'] = 'add_time >'.$begin.' AND add_time <'.$end;
        $arr['last_seasion_count'] = M('Card_userlist')->where($where)->count();
        //上年
        $begin=strtotime(date('Y-01-01',strtotime('-1 year')));
        $end=strtotime(date('Y-12-31',strtotime('-1 year')));
        $where['_string'] = 'add_time >'.$begin.' AND add_time <'.$end;
        $arr['last_year_count'] = M('Card_userlist')->where($where)->count();

        $this->returnCode(0,$arr);
    }
    public function card_new_list(){
        if (!empty($_POST['keyword'])) {
            if ($_POST['searchtype'] == 'phone') {
                $condition_user['u.phone'] = array('like', '%' . $_POST['keyword'] . '%');
            }
            if ($_POST['searchtype'] == 'card_id') {
                $condition_['c.id'] = array('like', '%' . $_POST['keyword'] . '%');
                $condition_['c.wx_card_code'] = array('like', '%' . $_POST['keyword'] . '%');
                $condition_['_logic'] = 'or';
                $condition_user['_complex'] = $condition_;
            }
            if ($_POST['searchtype'] == 'physical_id') {
                $condition_user['c.physical_id'] = array('like', '%' . $_POST['keyword'] . '%');
            }
            if ($_POST['searchtype'] == 'nickname') {
                $condition_user['u.nickname'] = array('like', '%' . $_POST['keyword'] . '%');
            }
        }
        $condition_user['c.mer_id'] = $this->merchant['mer_id'];
        $card_count = M('Card_userlist')->join('as c left join '.C('DB_PREFIX').'user as u ON u.uid = c.uid')->where($condition_user)->count();
        $page	=	I('pindex',1);
        $card_user_list = M('Card_userlist')->field('c.id,c.card_id,c.mer_id,c.uid,c.card_money,c.card_money_give,c.card_score,c.qrcode_id,c.physical_id,c.add_time,c.status,c.ticket,c.gid,c.wx_card_code,c.cancel_wx,u.nickname,u.phone,c.add_time as card_add_time')->join('as c left join '.C('DB_PREFIX').'user as u ON u.uid = c.uid')->where($condition_user)->order('c.id DESC')->page($page,10)->select();
        foreach ($card_user_list as &$v) {
            if($v['add_time']>0){

                $v['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
            }else{
                $v['add_time']='';
            }
            $v['card_money']= $v['card_money']+$v['card_money_give'];
            !$v['nickname'] && $v['nickname']='';
            !$v['phone'] && $v['phone']='';
        }

        $arr['data']	=	isset($card_user_list)?$card_user_list:array();
        $arr['all']		=	$card_count;
        $arr['page'] 	=	ceil($arr['all']/10);
        $this->returnCode(0,$arr);
    }

    public function  card_new_status(){
        $where['id']	=	I('card_id');
        $data['status']	=	I('status',1);
        //$data['status']	= $data['status'] == 1 ? 1 : 0;
        if($where['id']){
            $save	=	M('Card_userlist')->where($where)->data($data)->save();
        }else{
            $this->returnCode('20140074');
        }
        if($save){
            $this->returnCode(0);
        }else{
            $this->returnCode('20140028');
        }
    }
	
	//修改密码
	public function changePwd(){
		$type = I('type',2);
		if($type != 4 && empty($this->mer_id)){
			$this->returnCode('20140071');
		}
		if($type == 4){
			if(empty($_POST['phone'])){
				$this->returnCode('20140072');
			}
			$now_merchant = M('Merchant')->where(array('phone'=>$_POST['phone']))->find();
			if(empty($now_merchant)){
				$this->returnCode('20140073');
			}
			$this->mer_id = $now_merchant['mer_id'];
		}
		$where = array();
		$where['mer_id'] = $this->mer_id;
		$where['extra'] = $_POST['smsCode'];
		$where['type']   = $type;
		$where['status'] = 0;
		$where['expire_time'] = array('gt', time());
		$sms_result = M("Merchant_sms_record")->where($where)->find();
		if(!$sms_result){
		   $this->returnCode('20140070');
		}
		$condition_merchant['mer_id'] = $this->mer_id;
		$data_merchant['pwd'] = md5($_POST['newPwd']);
		if(M('Merchant')->where($condition_merchant)->data($data_merchant)->save()){
			M("Merchant_sms_record")->where(array('pigcms_id'=>$sms_result['pigcms_id']))->data(array('status'=>'1'))->save();
			$this->returnCode(0);
		}else{
			$this->returnCode('20140069');
		}
	}

    public function changePhone(){

        if(empty($this->mer_id)){
            $this->returnCode('20140071');
        }

        if(empty($_POST['new_phone'])){
            $this->returnCode('20140072');
        }
        $now_merchant = M('Merchant')->where(array('phone'=>$_POST['new_phone']))->find();
        if($now_merchant){
            $this->returnCode('20140084');
        }

        if($this->config['open_merchant_reg_sms']) {
            $where = array();
            $where['mer_id'] = $this->mer_id;
            $where['extra'] = $_POST['smsCode'];
            $where['status'] = 0;
            $where['expire_time'] = array('gt', time());
            $sms_result = M("Merchant_sms_record")->where($where)->find();

            if (!$sms_result) {
                $this->returnCode('20140070');
            }

            $where = array();
            $where['mer_id'] = $this->mer_id;
            $where['extra'] = $_POST['smsCode2'];
            $where['status'] = 0;
            $where['expire_time'] = array('gt', time());
            $sms_result = M("Merchant_sms_record")->where($where)->find();

            if (!$sms_result) {
                $this->returnCode('20140070');
            }
        }
        $condition_merchant['mer_id'] = $this->mer_id;
        $data_merchant['phone'] = $_POST['new_phone'];
        $_POST['phone_country_type'] && $data_merchant['phone_country_type'] = $_POST['phone_country_type'];

        if(M('Merchant')->where($condition_merchant)->data($data_merchant)->save()){
            M("Merchant_sms_record")->where(array('pigcms_id'=>$sms_result['pigcms_id']))->data(array('status'=>'1'))->save();
            $this->returnCode(0);
        }else{
            $this->returnCode('20140083');
        }
    }

    public function card_edit()
    {
        $mer_id = $this->merchant['mer_id'];
        if (empty($_POST['support_score_select'])) {
            $_POST['support_score']=0;
        } elseif (!is_numeric($_POST['support_score']) || empty($_POST['support_score'])) {
            $this->returnCode('20170101','','消费一元获得积分数必须是大于1的数字');
        }

        if(!is_numeric($_POST['discount'])){
            $this->returnCode('20170102','','请填写0到10的数字,0相当于不打折,比如95折 填写9.5即可');
        }


        if ($_POST['recharge_back_rule'] == 1 && !$_POST['support_score_select']) {
            $this->returnCode('20170103','','积分支持已关闭，充值返现规则不能再选积分');
        }
        foreach ($_POST['recharge_count'] as $key=>$v) {
            $tmp['count'] = $v;
            $tmp['back_money'] = $_POST['recharge_back_money'][$key];
            $tmp['back_score'] = $_POST['recharge_back_score'][$key];

            if (!is_numeric($tmp['back_money'])&&!is_numeric($tmp['back_score'])) {
                $this->returnCode('20170104','','请填写正确的数据');
            }
            $_POST['recharge_rule'][] = $tmp;
        }
        $_POST['recharge_rule'] = serialize( $_POST['recharge_rule']);
        unset($_POST['recharge_count'],$_POST['recharge_back_money'],$_POST['recharge_back_score']);
//            if (!is_numeric($_POST['recharge_back_money'])&&!is_numeric($_POST['recharge_back_score'])) {
//                $this->returnCode('20170104','','请填写正确的数据');
//            }
        if (!is_numeric($_POST['begin_money']) && $this->config['merchant_card_recharge_offline'] || $_POST['begin_money'] < 0) {
            $this->returnCode('20170105','','开卡初始金额设置错误');
        }

        if($_POST['discount']<0||$_POST['discount']>10){
            $this->returnCode('20170106','','会员卡折扣设置错误,不给折扣请填10');
        }
        //逗号替换
        $_POST['recharge_suggest'] = preg_replace("/(，)/",',',$_POST['recharge_suggest']);
        $_POST['mer_id'] = $mer_id;
        $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
        $tmp=array();
        foreach($_POST as $key=>$v){
            if(!(strpos($key,'wx')===false)){
                $tmp[str_replace('wx_','',$key)] =is_array($v)?$v:htmlspecialchars_decode($v);
            }
        }

        $_POST['wx_param']  = serialize($tmp);
		
		if($card = M('Card_new')->where(array('mer_id'=>$_POST['mer_id']))->find() ){
			M('Card_new')->where(array('mer_id'=>$mer_id))->data($_POST)->save();
			if($_POST['sysc_weixin'] && $this->config['coupon_wx_sync']){
				if(empty($_POST['wx_prerogative'])&& $card['card_id']!=''){
					$this->returnCode('20170107','','特权说明不能为空！');
				}

				$_POST['sysc_weixin'] && $res = $this->sysc_wxcard();
			}
			$this->returnCode(0,array('msg'=>'保存成功'.$res['msg']));
		}else{
			$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
			M('Card_new')->add($_POST);
			if($this->config['coupon_wx_sync']){
				$_POST['sysc_weixin'] && $res = $this->sysc_wxcard();
			}
			$this->returnCode(0,array('msg'=>'添加会员卡成功'.$res['msg']));
		}
    }

    //增加会员
    public function card_new_add_user(){
            //卡号规则
            $_POST['mer_id'] = $this->merchant['mer_id'];
            $_POST['add_time']  = time();
            $_POST['card_money_give'] = $_POST['card_money'];
            unset($_POST['card_money']);
            if( $card_id = M('Card_userlist')->add($_POST)){
                if (!empty($_POST['card_money_give'])) {
                    $data_money['card_id'] = $card_id ;
                    $data_money['type'] = 1;
                    $data_money['money_add'] = $_POST['card_money_give'];
                    $data_money['desc'] = '会员卡创建初始金额';
                    D('Card_new')->add_row($data_money);
                }
                if (!empty($_POST['card_score'])) {
                    $data_score['card_id'] = $card_id ;
                    $data_score['type'] = 1;
                    $data_score['score_add'] = $_POST['card_score'];
                    $data_score['desc'] = '会员卡创建初始积分';
                    D('Card_new')->add_row($data_score);
                }
                $this->returnCode(0,array('msg'=>'添加成功','id'=>$card_id));
            }else{
                $this->returnCode('20170113','','添加失败！');
            }
    }
    //会员详情
    public function card_new_user_detail(){
        $id = $_POST['id'];
        $card_info = D('Card_new')->get_cardinfo_by_id($id);
        $card_info['card_group']  = M('Card_group')->where(array('mer_id'=>$this->merchant['mer_id']))->select();
        if($card_info['add_time']>0){
            $card_info['add_time'] = date('Y-m-d H:i:s',$card_info['add_time']);
        }else{
            $card_info['add_time'] = '';
        }
        !$card_info['nickname'] && $card_info['nickname']='';
        !$card_info['phone'] && $card_info['phone']='';
        if (empty($card_info)) {
            $this->returnCode('20170114','','会员卡不存在！');
        } else {
            $this->returnCode(0,array('card'=>$card_info));
        }
    }
    //会员编辑
    public function card_new_user_edit(){
        $card_info = D('Card_new')->get_cardinfo_by_id($_POST['id']);
        if (empty($card_info)) {
            $this->returnCode('20170114','','会员卡不存在！');
        } else {
            $data['card_money_give'] = pow(-1, ($_POST['set_money_type'] + 1)) * $_POST['set_money'] + $card_info['card_money_give'];
            $data['card_score'] = pow(-1, ($_POST['set_score_type'] + 1)) * $_POST['set_score'] + $card_info['card_score'];
            if ($data['card_money_give'] < 0) {
                $data['card_money_give'] = 0;
            }

            if ($data['card_score'] < 0) {
                $data['card_score'] = 0;
            }

            $_POST['physical_id'] && $data['physical_id'] = $_POST['physical_id'];
            $data['status'] = $_POST['status'];
            $_POST['gid'] && $data['gid'] = $_POST['gid'];

            if (M('Card_userlist')->where(array('id' => $_POST['id']))->data($data)->save()) {
                if (!empty($_POST['set_money'])) {
                    $des = $_POST['set_money_type'] == 1 ? '增加' : '减少';
                    $add_use = $_POST['set_money_type'] == 1 ? 'add' : 'use';
                    $data_money['card_id'] = $card_info['id'];
                    $data_money['type'] = $_POST['set_money_type'];
                    $data_money['money_' . $add_use] = $_POST['set_money'];
                    $data_money['desc'] = '商家后台操作' . $des . '赠送余额';

                    D('Card_new')->add_row($data_money);
                    if ($card_info['wx_card_code'] != '') {
                        D('Card_new')->update_wx_card($card_info['wx_card_code'], $data['card_money_give'] - $card_info['card_money_give'], 0, $data_money['desc']);
                    }
                }

                if (!empty($_POST['set_score'])) {
                    $des = $_POST['set_score_type'] == 1 ? '增加' : '减少';
                    $add_use = $_POST['set_score_type'] == 1 ? 'add' : 'use';
                    $data_score['card_id'] = $card_info['id'];
                    $data_score['type'] = $_POST['set_score_type'];
                    $data_score['score_' . $add_use] = $_POST['set_score'];
                    $data_score['desc'] = '商家后台操作' . $des . '积分';
                    D('Card_new')->add_row($data_score);

                    if ($card_info['wx_card_code'] != '') {
                        D('Card_new')->update_wx_card($card_info['wx_card_code'], 0, $data['card_score'] - $card_info['card_score'], '', $data_score['desc']);
                    }
            }
                $this->returnCode(0,array('msg'=>'修改成功'));
            } else {
                $this->returnCode('20170115', '', '修改失败！请重试。');

            }
        }
    }

    #微信购买派发设置
    public function card_new_weixin_send_save(){
        $mer_id  =$this->merchant['mer_id'];
        if(!is_numeric($_POST['money'])||$_POST['money']<0){
            $this->returnCode('20170122', '', '金额设置错误');
        }
        $_POST['coupon_id'] = implode(',',$_POST['coupon_id']);
        $data['weixin_send_money'] = $_POST['money'];
        $data['weixin_send_couponlist'] = $_POST['coupon_id'];

        if(M('Card_new')->where(array('mer_id'=>$mer_id))->save($data)){
            $this->returnCode(0,array('msg'=>'保存成功'));
        }else{
            $this->returnCode('20170122', '', '保存失败');
        }
    }

    //用户扫描二维码绑定会员卡
    public function see_qrcode(){

        $where['id']  = $_POST['id'];
        $qrcode_id =800000000+$_POST['id'];
        $res = D('Recognition')->get_tmp_qrcode($qrcode_id);
        $date['qrcode_id']=$qrcode_id;
        $date['ticket']=$res['ticket'];
        $where['id']=$_POST['id'];
        M('Card_userlist')->where($where)->save($date);
        $this->returnCode(0,array('qrcode'=>$res['ticket']));
    }


    //获取背景 颜色等属性
    public function get_card_new_config(){
        for ($i = 4; $i <= 20; $i++) {
            $i = $i < 10 ? '0' . $i : $i;
            $str = $this->config['site_url'].'/static/images/card/card_bg' . $i . '.png';
            $arr['bg_list'][] = $str;
        }
        $arr['color_list'] =  D('System_coupon')->color_list();
        $arr['coupon_wx_sync'] =  $this->config['coupon_wx_sync'];

        $arr['wx_server']=array(
            'BIZ_SERVICE_DELIVER'=>'外卖服务',
            'BIZ_SERVICE_FREE_PARK'=>'停车位',
            'BIZ_SERVICE_WITH_PET'=>'可带宠物',
            'BIZ_SERVICE_FREE_WIFI'=>'免费wifi',
        );

        $this->returnCode(0,$arr);
    }

    public function ajax_ordertype_cateid()
    {
        if ($_POST['order_type'] == 'meal') {
            $cate_id = D(ucfirst($_POST['order_type']) . '_store_category')->field('cat_id,cat_name')->where(array('cat_status' => 1, 'cat_fid' => 0))->select();
        } else {
            $cate_id = D(ucfirst($_POST['order_type']) . '_category')->field('cat_id,cat_name')->where(array('cat_status' => 1, 'cat_fid' => 0))->select();
        }
        $this->returnCode(0,$cate_id);
    }

    public function card_detail(){
        $mer_id = $this->merchant['mer_id'];
        $data = M('Card_new')->where(array('mer_id'=>$mer_id))->find();
        preg_match('/\d+/',$data['bg'],$bg);
        $data['bg_num']=intval($bg[0]);
        $data['wx_param'] = unserialize($data['wx_param']);
        $data['recharge_rule'] = unserialize($data['recharge_rule']);
        if(strpos($data['bg'],'http')!==false){
        }else{
            $data['bg'] = $this->config['site_url'].str_replace('./','/',$data['bg']);
        }
        foreach ($data['wx_param']['text_image_list'] as $k => $v) {
            if(empty($v)){
                continue;
            }
            $text_image_list[] =$v;
        }
        $color_list =  D('System_coupon')->color_list();

        $data['wx_param']['text_image_list'] = $text_image_list;
        $data['wx_param']['color_val'] = $color_list[$data['wx_param']['color']];
		
		$data['wx_param']['center_url'] = htmlspecialchars_decode($data['wx_param']['center_url']);
		$data['wx_param']['custom_url'] = htmlspecialchars_decode($data['wx_param']['custom_url']);
		$data['wx_param']['promotion_url'] = htmlspecialchars_decode($data['wx_param']['promotion_url']);
		$data['wx_param']['custom_cell1_url'] = htmlspecialchars_decode($data['wx_param']['custom_cell1_url']);
		$data['wx_param']['custom_cell2_url'] = htmlspecialchars_decode($data['wx_param']['custom_cell2_url']);
		$data['wx_param']['custom_cell3_url'] = htmlspecialchars_decode($data['wx_param']['custom_cell3_url']);
		
        $arr['data'] = $data;
        $this->returnCode(0,$arr);
    }

    //消费记录
    public function card_new_consume_record()
    {
        $where['card_id']=$_POST['id'];
        $count = M('Card_new_record')->where($where)->count();
        $page = I('pindex',1);
        $where['c.card_id']= $where['card_id'];
        unset( $where['card_id']);
        $result =  M('Card_new_record')->field('c.* ,uu.nickname,uu.phone')->join('as c left join '.C('DB_PREFIX').'card_userlist as u ON c.card_id = u.id left join '.C('DB_PREFIX').'user as uu on uu.uid=u.uid')->where($where)->order('c.time DESC')->page($page,10)->select();

        foreach ($result as &$v) {
            $v['time'] = date('Y-m-d H:i:s',$v['time']);
            foreach ($v as $key=>$vv) {
                if(!$v[$key]&&$v[$key]!=0){
                    $v[$key] = '';
                }
            }
        }
        $arr['data']	=	isset($result)?$result:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);
        $this->returnCode(0,$arr);
    }

    //会员卡总体消费记录
    public function recharge_list(){
        $mer_id  =$this->merchant['mer_id'];
        $where['u.mer_id'] = $mer_id;
        $count = M('Card_new_record')->join('as c left join '.C('DB_PREFIX').'card_userlist as u ON c.card_id = u.id')->where($where)->count();
        $page = I('pindex',1);
        $result =  M('Card_new_record')->field('c.*,uu.phone,uu.nickname')->join('as c left join '.C('DB_PREFIX').'card_userlist as u ON c.card_id = u.id left join '.C('DB_PREFIX').'user as uu on uu.uid=u.uid')->where($where)->order('c.time DESC')->page($page,10)->select();
        foreach ($result as &$v) {
            $v['time'] = date('Y-m-d H:i:s',$v['time']);
        }
        $arr['data']	=	isset($result)?$result:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);
        $this->returnCode(0,$arr);
    }


    /*
     * 会员卡分组
     * */

    //会员卡分组列表
    public function card_new_group(){

        $card_group_list = M('Card_group')->where(array('mer_id'=>$this->merchant['mer_id']))->select();
        foreach ($card_group_list as &$v) {
            $v['user_count'] = M('Card_userlist')->where(array('mer_id'=>$this->merchant['mer_id'],'gid'=>$v['id']))->count();
        }

        $arr['group_list']		=	$card_group_list?$card_group_list:array();

        $this->returnCode(0,$arr);
    }

    //添加分组
    public function add_card_group(){

        $_POST['mer_id'] = $this->merchant['mer_id'];
        if(empty($_POST['name'])){
            $this->returnCode('20170122','',array('msg'=>'名字不能空'));
        }
        if(empty($_POST['gid'])){
            $res = M('Card_group')->add($_POST);
        }else{
            $date['mer_id'] = $_POST['mer_id'];
            $date['name'] = $_POST['name'];
            $date['des'] = $_POST['des'];
            $res = M('Card_group')->where(array('id'=>$_POST['gid']))->save($date);
        }
        if($res){
            $this->returnCode(0,array('msg'=>'操作成功'));
        }else {
            $this->returnCode('20170116','','操作失败');
        }

    }

    public  function card_group_detail(){
        $gid = $_POST['gid'];
        $res = M('Card_group')->where(array('id'=>$_POST['gid']))->find();
        $this->returnCode(0,array('group'=>$res));
    }

    //分组用户列表
    public function card_group_user_list(){
        $gid = $_POST['gid'];
        $mer_id  = $this->merchant['mer_id'];
        $where['c.mer_id']=$mer_id;
        $where['c.gid']=$gid;
        $page = I('pindex',1);
        $count = M('Card_userlist')->join('as c left join '.C('DB_PREFIX').'user as u ON c.uid=u.uid')->where($where)->count();
        $result = M('Card_userlist')->field('c.id,c.uid,c.card_money,c.card_money_give,c.card_score,c.physical_id,c.add_time,c.status,c.gid,u.nickname,u.phone')->join('as c left join '.C('DB_PREFIX').'user as u ON c.uid=u.uid')->where($where)->page($page,10)->select();
        foreach ($result as &$v) {
            if($v['add_time']>0){
                $v['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
            }else{
                $v['add_time']='';
            }
            !$v['nickname'] && $v['nickname']='';
            !$v['phone'] && $v['phone']='';
        }
        $arr['data']	=	isset($result)?$result:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);
        $this->returnCode(0,$arr);
    }


    //优惠券派发

    //派发记录
    public function send_history(){
        $mer_id  =$this->merchant['mer_id'];
        $page = I('pindex',1);
        $count = M('Card_coupon_send_history')->where(array('mer_id'=>$mer_id))->count();

        $res = M('Card_coupon_send_history')->join('as h left join '.C('DB_PREFIX').'user u ON h.uid = u.uid')
            ->join(C('DB_PREFIX').'card_new_coupon c ON h.coupon_id = c.coupon_id')->field('h.*,u.nickname,c.name as coupon_name')->where(array('h.mer_id'=>$mer_id))->order('add_time DESC')->page($page,10)->select();
        foreach ($res as &$re) {
            $re['add_time'] = date('Y-m-d H:i:s',$re['add_time']);
        }
        $arr['data']	=	isset($res)?$res:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);
        $this->returnCode(0,$arr);
    }

    //派发优惠券
    public function send_coupon(){
        $mer_id  =$this->merchant['mer_id'];

        $coupon_list = D('Card_new_coupon')->get_coupon_list_by_merid($mer_id,1);

        foreach ($coupon_list as $v) {
            if(strpos($v['img'],'http')===false){
                $v['img'] = $this->config['site_url'].$v['img'];
            }
            $tmp[] = $v;
        }
        $arr['coupon_list']=$tmp?$tmp:array();
        $card_group_list = M('Card_group')->where(array('mer_id'=>$this->merchant['mer_id']))->select();
        foreach ($card_group_list as &$v) {
            $v['user_count'] = M('Card_userlist')->where(array('mer_id'=>$this->merchant['mer_id'],'gid'=>$v['id']))->count();
        }
        $arr['card_group']=$card_group_list?$card_group_list:array();

        $this->returnCode(0,$arr);
    }

    //根据分组id 获取派送的优惠券列表
    public function ajax_get_send_coupon(){
        if(!empty($_POST['card_group_id'])){
            $count = M('Card_userlist')->where(array('gid'=>array('in',$_POST['card_group_id'])))->count();
            $coupon_list  = D('Card_new_coupon')->get_coupon_list_by_merid($this->merchant['mer_id'],1);
            foreach ($coupon_list as &$v) {

                if($count>$v['num']-$v['had_pull']){
                    $v['disable'] = true;
                }else{
                    $v['disable'] = false;
                }

                if(strpos($v['img'],'http')===false){
                    $v['img'] = $this->config['site_url'].$v['img'];
                }
            }
        }else{
            $coupon_list  = D('Card_new_coupon')->get_coupon_list_by_merid($this->merchant['mer_id'],1);

        }
        $arr['coupon_list']=$coupon_list;
        $this->returnCode(0,$arr);
    }

    //派发优惠券
    public function card_new_send(){
        $_POST['coupon_id']  =implode(',',$_POST['coupon_id']);
        if($_POST['card_group_id']){
            $_POST['card_group_id']  =implode(',',$_POST['card_group_id']);
            //$res = D('Card_new')->get_userlist_by_car_group($_GET['card_group_id']);
            $res =D('Card_new')->add_send_log($this->merchant['mer_id'],$_POST['card_group_id'],$_POST['coupon_id']);
            // $this->assign('user_list',$res);
            if($res['error_code']){
                $this->error($res['msg']);
                $this->returnCode('20170118','',array('msg'=>$res['msg'],'status'=>0));
            }else{
                $this->template_plan_msg($res['id']);
                $this->returnCode(0,array('msg'=>$res['msg'],'status'=>1));
            }
            die;
        }else if($_POST['all']){
            //$res = D('Card_new')->get_card_user_list_by_mer_id($this->merchant['mer_id']);
            $res =D('Card_new')->add_send_log($this->merchant['mer_id'],'',$_POST['coupon_id']);
            //$this->assign('user_list',$res);
            if($res['error_code']){
                $this->returnCode('20170118','',array('msg'=>$res['msg'],'status'=>0));

            }else{
                $this->template_plan_msg($res['id']);
                $this->returnCode(0,array('msg'=>$res['msg'],'status'=>1));
            }
            die;
        }else if($_POST['uid']){
            $res =D('Card_new')->add_send_log($this->merchant['mer_id'],'',$_POST['coupon_id'],$_POST['uid']);
            if($res['error_code']){
                $this->returnCode('20170118','',array('msg'=>$res['msg'],'status'=>0));

            }else{
                $this->template_plan_msg($res['id']);
                $this->returnCode(0,array('msg'=>$res['msg'],'status'=>1));
            }
        }

    }

    // 添加临时计划任务
    public function template_plan_msg($send_id){
        import('@.ORG.plan');
        $plan_class = new plan();
        $param = array(
            'file'=>'send_coupon',
            'plan_time'=>time(),
            'param'=>array(
                'id'=>$send_id,
            ),
        );
        $plan_class->addTask($param);
    }

    public function send_all(){
        $mer_id  =$this->merchant['mer_id'];
        $where['status'] = 1;
        $where['mer_id'] = $mer_id;
        $user_count = M('Card_userlist')->where($where)->count();
        $coupon_list = D('Card_new_coupon')->get_coupon_list_by_merid($mer_id);
        foreach ($coupon_list as $key=>$vv) {
            if($vv['num']<$user_count){
                unset($coupon_list[$key]);
            }
        }
        if(empty($coupon_list)){
            $this->returnCode('20170119','','没有可分配的优惠券');
        }
        $arr['coupon_list']=$coupon_list;
        $this->returnCode(0,$arr);
    }

    public function get_user(){
        $where['c.mer_id'] = $this->merchant['mer_id'];
        $where['u.'.$_POST['keyword']] = array('like',"%".$_POST['search_val']."%");
        $res = M('Card_userlist')->join('as c left join '.C('DB_PREFIX').'user u ON c.uid = u.uid')->field('c.*,u.nickname,u.phone')->where($where)->limit(10)->select();
        $arr['user_list']=$res;
        $this->returnCode(0,$arr);
    }



    public function uploadimg($url){
        $mode = D('Access_token_expires');
        $res = $mode->get_access_token();
        import('ORG.Net.Http');
        $http = new Http();
        $file  =str_replace('https','http',$url);

        $return = $http->curlUploadFile('https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$res['access_token'],$file,1);
        return json_decode($return,true);
    }

    public function card_new_add_coupon()
    {
            $now_card = M('Card_new')->where(array('mer_id'=>$this->merchant['mer_id']))->find();
            if(empty($now_card)){
                $this->returnCode(1,'','您还没有开启会员卡，请编辑会员卡信息后再添加优惠券');
            }
      
            if ($_POST['limit'] > $_POST['num']) {
                $this->returnCode('20170109','','请完善基本信息-领取限制不能大于数量!');
            }
            if ($_POST['use_limit'] > $_POST['limit'] || $_POST['use_limit'] > $_POST['num']) {
                $this->returnCode('20170110','','请完善基本信息-使用限制设置错误，不能大于领取限制和数量!');

            }
            if ($_POST['cate_name'] != 'all') {
                if ($_POST['cate_id'] != 0) {
                    if ($_POST['cate_name'] == 'meal') {
                        $cate_id = D(ucfirst($_POST['cate_name']) . '_store_category')->field('cat_id,cat_name')->where(array('cat_id' => $_POST['cate_id']))->find();
                    } else {
                        $cate_id = D(ucfirst($_POST['cate_name']) . '_category')->field('cat_id,cat_name')->where(array('cat_id' => $_POST['cate_id']))->find();
                    }
                    $_POST['cate_id'] = serialize($cate_id);
                }
            } else {
                $_POST['cate_id'] = 0;
            }
            $data['platform'] = serialize($_POST['platform']);
            unset($_POST['dosubmit']);
            unset($_POST['platform']);
            $image = D('Image')->handle($this->merchant['mer_id'], 'card_new_coupon', 1);
            if (!$image['error']) {
                $data = array_merge($data, $image['url']);
            }
            $data = array_merge($data, $_POST);
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']) + 86399;//到 23:59:59
            $data['add_time'] = $data['last_time'] = time();
            $data['mer_id'] = $this->merchant['mer_id'];
            $data['card_id'] = $now_card['card_id'];
            $data['notice'] =$_POST['notice'];
            $data['auto_get'] =$_POST['auto_get'];
            if(empty($_POST['store_id'])){
                $this->returnCode('20170111','','请完善基本信息-店铺不能为空!');
            }
            $data['store_id'] = implode(',',$_POST['store_id']);
            if($_POST['sync_wx']) {
                import('@.ORG.weixincard');
                import('ORG.Net.Http');
                $http	=	new Http();
                $mode = D('Access_token_expires');
                $res = $mode->get_access_token();

                if($this->merchant['logo']){
                    $logo_url = $this->uploadimg($_SERVER['DOCUMENT_ROOT'].str_replace($this->config['site_url'],'',$this->merchant['logo']));
                }else{
                    $logo_url = $this->uploadimg($_SERVER['DOCUMENT_ROOT'].str_replace($this->config['site_url'],'',$this->config['wechat_share_img']));
                }
                $param['logo_url'] = $logo_url['url'];
                $param['brand_name'] = mb_substr($_POST['brand_name'],0,12,'utf-8');
                $param['title'] =  mb_substr($_POST['name'],0,9,'utf-8');
                $param['color'] = $_POST['color'];
                $param['notice'] = mb_substr($_POST['notice'],0,16,'utf-8');
                $param['phone'] = $this->config['site_phone'];
                $param['description'] = $_POST['des'];
                $param['begin_time'] = $data['start_time'];
                $param['end_time'] = $data['end_time'];
                $param['num'] = $_POST['num'];
                $param['limit'] = $_POST['limit'];
                $param['center_title'] = '立即使用';
                $param['center_sub_title'] = mb_substr($_POST['center_sub_title'],0,6,'utf-8');
                $param['center_url'] = html_entity_decode($_POST['center_url']);
                $param['custom_url_name'] = mb_substr($_POST['custom_url_name'],0,5,'utf-8');
                $param['custom_url'] = html_entity_decode($_POST['custom_url']);
                $param['custom_url_sub_title'] = mb_substr($_POST['custom_url_sub_title'],0,6,'utf-8');
                $param['promotion_url'] = html_entity_decode($_POST['promotion_url']);
                $param['promotion_url_name'] = '更多优惠';
                $param['icon_url_list'] = $_POST['icon_url_list']; //封面图片
                $param['abstract'] = $_POST['abstract']; //封面图片
                $param['share_friends'] = $_POST['share_friends'];

                foreach ($_POST['image_url'] as $k => $v) {
                    $text_image_list[] = array(
                        'image_url' => $v,
                        'text' => $_POST['textall'][$k],
                    );
                }

                $param['text_image_list'] = $text_image_list;
                $param['business_service'] = $_POST['business_service'];
                $param['least_cost'] = $_POST['order_money']*100;
                $param['reduce_cost'] = $_POST['discount']*100;
                $param['res'] = $res;

                $card = new Create_card($param);
                $cardinfo = $card->create();
                $ticket = $cardinfo['ticket'];
                $qrcode_url = $cardinfo['qrcode_url'];
                $return = $cardinfo['return'];
                if($return['errcode']){
                    $this->returnCode('20170112','','同步微信卡券出错，请检查您配置的数据是否正确，微信返回信息：'.$return['errmsg']);
                }
            }
            //dump($data);die;
            if ($id = M('Card_new_coupon')->add($data)) {
                $errorms= '';
                if($this->config['coupon_wx_sync'] && $_POST['sync_wx']) {
                    $wx_data['sync_wx'] = $_POST['sync_wx'];
                    unset($param['res']);

                    $wx_data['wx_param'] = serialize($param);
                    $errormsg = '';
                    if ($return['errcode'] == 0) {
                        $wx_data['wx_cardid'] = $return['card_id'];
                        $wx_data['jsapi_ticket'] = $ticket['ticket'];
                        $wx_data['expires_in'] = $ticket['expires_in'];
                        $wx_data['wx_qrcode'] = $qrcode_url['show_qrcode_url'];
                        $wx_data['wx_ticket_addtime'] = $_SERVER['REQUEST_TIME'];
                        $wx_data['is_wx_card'] = 1;
//                        unset($param['res']);
//                        $wx_data['wx_param'] = serialize($param);
                        $wx_data['cardsign'] = sha1($wx_data['wx_ticket_addtime'] . $ticket['ticket'] . $return['card_id']);
                        M('Card_new_coupon')->where(array('coupon_id' => $id))->save($wx_data);
                        $errormsg = $return['errmsg'];

                    } else {
                        $wx_data['is_wx_card'] = 0;
                        $errormsg = $return['errmsg'];
                        $wx_data['weixin_err'] = serialize($errormsg);
                        M('Card_new_coupon')->where(array('coupon_id' => $id))->save($wx_data);

                    }
                }

                $this->returnCode(0,array('msg'=>'添加优惠券成功！'.$errormsg));
            } else {

                $this->returnCode('20170113','','添加失败！');

            }

    }

    public function card_new_edit_coupon()
    {
        $now_coupont = M('Card_new_coupon')->where(array('coupon_id' => $_POST['coupon_id']))->find();
        $_POST['num']  =$now_coupont['num'];
            $add = pow(-1, (int)$_POST['add']);
            $_POST['num'] += $add * (int)$_POST['num_add'];//数量增减
            if ((int)$_POST['num'] < (int)$_POST['had_pull']) {
                $this->returnCode('20170114','','请完善基本信息-更新优惠券数量有误，不能小于已领取的数量！');
            } else if (strtotime($_POST['end_time']) < strtotime($_POST['start_time'])) {
                $this->returnCode('20170108','','请完善基本信息-起始时间设置有误!');
            }

            if ($_POST['num'] > $_POST['had_pull'] && $_POST['status'] == 3) {
                if ($_POST['end_time'] > time()) {
                    $_POST['status'] = 1;
                }
            }
            if ($_POST['num'] <= $_POST['had_pull']) {
                $_POST['status'] = 3;
            }

            unset($_POST['dosubmit']);
            $data = $_POST;
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']) + 86399;//到 23:59:59
            $image = D('Image')->handle($this->merchant['mer_id'], 'card_new_coupon', 1);
            if (!$image['error']) {
                $data = array_merge($data, $image['url']);
            }
            $data['last_time'] = time();
            $data['mer_id'] = $this->merchant['mer_id'];
            if(empty($_POST['store_id'])){
                $this->returnCode('20170111','','店铺不能为空!');
            }
            $data['store_id'] = implode(',',$_POST['store_id']);

            if (M('Card_new_coupon')->where(array('coupon_id' => $_POST['coupon_id']))->save($data)) {

                $errorms= '';
                if($this->config['coupon_wx_sync']) {
                    if ($_POST['is_wx_card']) {
                        import('ORG.Net.Http');
                        $res = D('Access_token_expires')->get_access_token();
                        //修改描述
                        $update_info['card_id'] = $_POST['wx_cardid'];
                        if ($image['url']['img']) {
                            $update_info['general_coupon']['base_info']['logo_url'] = $image['url']['img'];
                        }
                        $update_info['general_coupon']['base_info']['description'] = $_POST['des_detial'];
                        $update_wx_card = httpRequest('https://api.weixin.qq.com/card/update?access_token=' . $res['access_token'], 'post', json_encode($update_info, JSON_UNESCAPED_UNICODE));
                        $update_wx_card = json_decode($update_wx_card[1], true);

                        //修改库存
                        $wx_data['card_id'] = $_POST['wx_cardid'];
                        // $wx_data['color'] = $_POST['color'];
                        $wx_data['increase_stock_value'] = $add > 0 ? $_POST['num_add'] : 0;
                        $wx_data['reduce_stock_value'] = $add < 0 ? $_POST['num_add'] : 0;
                        $wx_data['begin_timestamp'] = $data['start_time'] ;
                        $wx_data['end_timestamp'] = $data['end_time'] ;
                        $update_wx_card = httpRequest('https://api.weixin.qq.com/card/modifystock?access_token=' . $res['access_token'], 'post', json_encode($wx_data, JSON_UNESCAPED_UNICODE));
                        $update_wx_card = json_decode($update_wx_card[1], true);
                        $errorms = $update_wx_card['errmsg'];
                    }
                }


                $this->returnCode(0,array('msg'=>'保存成功！'.$errorms));
            } else {

                $this->returnCode('20170115','','保存失败！');
            }

    }

    public function card_new_coupon_config(){
        $this->returnCode(0,D('Card_new_coupon')->cate_platform());
    }

    public function card_new_coupon_detail(){
        $return = D('Card_new_coupon')->cate_platform(); //模板中定义相关中文名字
        $coupon = D('Card_new_coupon')->where(array('coupon_id' => $_POST['coupon_id']))->find();
        $coupon['platform'] = unserialize($coupon['platform']);
        $coupon['wx_param'] = unserialize($coupon['wx_param']);
        $coupon['store_id'] = explode(',',$coupon['store_id']);
        $coupon['now_num'] = $coupon['now_num'];
        foreach ($coupon['platform'] as &$vv) {
            $vv = $return['platform'][$vv];
        }
        $coupon['platform'] = implode(',', $coupon['platform']);
        $coupon['cate_name'] = $coupon['cate_name'] == 'all' ? '全品类通用' : $return['category'][$coupon['cate_name']];
        if (empty($coupon['cate_id'])) {
            $coupon['cate_id'] = '全部分类';
        } else {
            $coupon['cate_id'] = unserialize($coupon['cate_id']);
            $coupon['cate_id'] = $coupon['cate_id']['cat_name'];
        }
//        $store_list =  D('Merchant_store')->get_store_list_by_merId($this->merchant['mer_id']);
//        $color_list =  D('System_coupon')->color_list();

      // $this->assign("store_list",$store_list);
        $coupon['category']= $return['category'];

        $coupon['start_time'] = date('Y-m-d',$coupon['start_time']);
        $coupon['end_time'] = date('Y-m-d',$coupon['end_time']);
        $arr['coupon']= $coupon;
        $this->returnCode(0,$arr);
    }

    public function card_new_coupon_list(){
        if (!empty($_POST['keyword'])) {
            if ($_POST['searchtype'] == 'id') {
                $condition_coupon['id'] = $_POST['keyword'];
            } else if ($_POST['searchtype'] == 'name') {
                $condition_coupon['name'] = array('like', '%' . $_POST['keyword'] . '%');
            }
        }
        $_POST['status']>0 && $condition_coupon['status'] = $_POST['status'];
        if($_POST['status']!=1 && $_POST['status']>-1){
            $condition_coupon['status'] =array('neq',1);
        }else{
            $condition_coupon['status'] = array('neq',4);
        }
        //排序 /*/
        $order_string = '`coupon_id` DESC';
        if ($_POST['sort']) {
            switch ($_POST['sort']) {
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
        $page	=	I('pindex',1);
        $condition_coupon['mer_id'] = $this->merchant['mer_id'];
        $coupon = M('Card_new_coupon');
        $coupon_count = $coupon->where($condition_coupon)->count();

        $coupon_list = $coupon->field(true)->where($condition_coupon)->order($order_string)->page($page,10)->select();
        $return = D('Card_new_coupon')->cate_platform();
        foreach ($coupon_list as $key => &$v) {
            $ctmp['platform'] = unserialize($v['platform']);
            if ($v['cate_name'] != 'all' && !empty($v['cate_id'])) {
                $tmp = unserialize($v['cate_id']);
                $c_tmp['cate_id'] = $tmp['cat_name'];
            }
            if ($v['end_time'] < time()) {
                D('Card_new_coupon')->where(array('coupon_id' => $v['coupon_id']))->setField('status', 2);
                $c_tmp['status'] = 2;
            }

            $c_tmp['start_time'] = date('Y-m-d',$v['start_time']);
            $c_tmp['end_time'] = date('Y-m-d',$v['end_time']);
            $c_tmp['coupon_id'] =$v['coupon_id'];
            $c_tmp['mer_id'] =$v['mer_id'];
            $c_tmp['card_id'] =$v['card_id'];
          //  $c_tmp['img'] =$v['img'];
            $c_tmp['order_money'] =$v['order_money'];
            $c_tmp['discount'] =$v['discount'];
            $c_tmp['wx_qrcode'] =$v['wx_qrcode'];
            $c_tmp['status'] = $v['status'];
            $c_tmp['name'] = $v['name'];
            switch($c_tmp['status']){
                case 0:
                    $v['status_txt'] ='禁止';
                    break;
                case 1:
                    $v['status_txt'] ='正常';
                    break;
                case 2:
                    $v['status_txt'] ='过期';
                    break;
                case 3:
                    $v['status_txt'] ='领完了';
                    break;
            }
            $c_tmp['category'] = $return['category'][$v['cate_name']];
            $c_tmp['platform']='';
            foreach ($ctmp['platform'] as $vv) {
                $c_tmp['platform'].=$return['platform'][$vv].' ';
            }
            //$v['platform'] = $return['platform']['cate_name'];
            $coupon_list_[] = $c_tmp;
        }

//        $this->assign("category", $return['category']);
//        $this->assign("platform", $return['platform']);
//        $this->assign('coupon_list', $coupon_list);


        $arr['data']	=	isset($coupon_list_)?$coupon_list_:array();
        $arr['all']		=	$coupon_count;
        $arr['page'] 	=	ceil($arr['all']/10);
        $where['mer_id'] = $this->merchant['mer_id'];
        $arr['all_count'] 	=	$coupon->where($where)->count();
        $where['status'] =1;
        $arr['normal_cont'] 	=		$coupon->where($where)->count();
        $where['status'] =array('neq',1);
        $arr['ban_count'] 	=		$coupon->where($where)->count();
        $this->returnCode(0,$arr);
    }



    //同步微信卡包
    public function sysc_wxcard(){
        $mer_id = $this->merchant['mer_id'];
        $card_info = M('Card_new')->where(array('mer_id'=>$mer_id))->find();
        $card_info['wx_param'] = unserialize($card_info['wx_param']);
        $_POST = $card_info['wx_param'];
        import('@.ORG.weixincard');
        $mode = D('Access_token_expires');
        $token = $mode->get_access_token();

        if($this->merchant['logo']){
            $logo_url = $this->uploadimg($_SERVER['DOCUMENT_ROOT'].str_replace($this->config['site_url'],'',$this->merchant['logo']));
        }else{
            $logo_url = $this->uploadimg($_SERVER['DOCUMENT_ROOT'].str_replace($this->config['site_url'],'',$this->config['wechat_share_img']));
        }
        $param['logo_url'] = $logo_url['url'];
        if($card_info['bg']!=''&& strpos( $card_info['wx_param']['background_pic_url'],'mmbiz')==false ){
            $background_url =$this->uploadimg($_SERVER['DOCUMENT_ROOT'].str_replace($this->config['site_url'],'',str_replace('./','/',$card_info['bg'])));
        }else if($card_info['diybg']!='' && strpos( $card_info['wx_param']['background_pic_url'],'mmbiz')==true){
            $background_url =  $card_info['wx_param']['background_pic_url'] ?array('url'=>$card_info['wx_param']['background_pic_url'] ):$this->uploadimg($_SERVER['DOCUMENT_ROOT'].str_replace($this->config['site_url'],'',$card_info['diybg']));
        }else{
            $this->returnCode('20170116','','没有设置背景！');
        }
        $param['background_pic_url'] =$background_url['url'];
        $param['card_id'] =$card_info['wx_cardid'];
        $param['brand_name'] = mb_substr($this->merchant['name'],0,12,'utf-8');
        $param['title'] =  mb_substr($_POST['title'],0,9,'utf-8');
        $param['notice'] = mb_substr($_POST['notice'],0,16,'utf-8');
        $param['phone'] = substr($this->merchant['phone'],0,11);
        $param['description'] = $card_info['info'];
        $param['prerogative'] = $_POST['prerogative'];
        $param['color'] = $_POST['color'];
        if($card_info['discount']==0){
            $card_info['discount'] = 10;
        }
        $param['discount'] = (100-$card_info['discount']*10)/10;
        $param['coupon_url'] = $this->config['site_url'].'/wap.php?c=My_card&a=merchant_coupon&mer_id='.$mer_id;
        $param['balance_url'] = $this->config['site_url'].'/wap.php?c=My_card&a=merchant_prepay&mer_id='.$mer_id;
        $param['bonus_url'] = $this->config['site_url'].'/wap.php?c=My_card&a=merchant_point&mer_id='.$mer_id;
        $param['card_url'] = $this->config['site_url'].'/wap.php?c=My_card&a=merchant_point&mer_id='.$mer_id;

        $param['center_title'] = mb_substr($_POST['center_title'],0,6,'utf-8');;
        $param['center_sub_title'] = mb_substr($_POST['center_sub_title'],0,8,'utf-8');
        $param['center_url'] = html_entity_decode($_POST['center_url']);
        $param['custom_url_name'] = mb_substr($_POST['custom_url_name'],0,5,'utf-8');
        $param['custom_url'] = html_entity_decode($_POST['custom_url']);
        $param['custom_url_sub_title'] = mb_substr($_POST['custom_url_sub_title'],0,6,'utf-8');
        $param['promotion_url'] = html_entity_decode($_POST['promotion_url']);
        $param['promotion_url_name'] = mb_substr($_POST['promotion_url_name'],0,6,'utf-8');
        $param['promotion_url_sub_title'] = mb_substr($_POST['promotion_url_sub_title'],0,6,'utf-8');

        $param['custom_cell1_name'] = mb_substr($_POST['custom_cell1_name'],0,5,'utf-8');
        $param['custom_cell2_name'] = mb_substr($_POST['custom_cell2_name'],0,5,'utf-8');
        $param['custom_cell3_name'] = mb_substr($_POST['custom_cell3_name'],0,5,'utf-8');

        $param['custom_cell1_tips'] = mb_substr($_POST['custom_cell1_tips'],0,6,'utf-8');
        $param['custom_cell2_tips'] = mb_substr($_POST['custom_cell2_tips'],0,6,'utf-8');
        $param['custom_cell3_tips'] = mb_substr($_POST['custom_cell3_tips'],0,6,'utf-8');

        $param['custom_cell1_url'] = html_entity_decode($_POST['custom_cell1_url']);
        $param['custom_cell2_url'] = html_entity_decode($_POST['custom_cell2_url']);
        $param['custom_cell3_url'] = html_entity_decode($_POST['custom_cell3_url']);
        foreach ( $card_info['wx_param']['text_image_list'] as $k => $v) {
            if(empty($v)){
                continue;
            }
            $text_image_list[] = array(
                'image_url' => $v['image_url'],
                'text' => $v['text'],
            );
        }


        $param['text_image_list'] = $text_image_list;
        $param['business_service'] = $_POST['business_service'];

        $param['token'] = $token;

        $card = new Create_wxcard($param);
        if($param['card_id']){
            $cardinfo = $card->update();
        }else{
            $cardinfo = $card->create();
        }
        $ticket = $cardinfo['ticket'];
        $qrcode_url = $cardinfo['qrcode_url'];
        $return = $cardinfo['return'];

        if ($return['errcode'] == 0) {
            if($param['card_id']=='') {
                $wx_data['wx_cardid']         = $return['card_id'];
                $wx_data['jsapi_ticket']      = $ticket['ticket'];
                $wx_data['expires_in']        = $ticket['expires_in'];
                $wx_data['wx_qrcode']         = $qrcode_url['show_qrcode_url'];
                $wx_data['wx_ticket_addtime'] = $_SERVER['REQUEST_TIME'];
                $wx_data['is_wx_card']        = 1;
//                        unset($param['res']);
//                        $wx_data['wx_param'] = serialize($param);
                $wx_data['cardsign'] = sha1($wx_data['wx_ticket_addtime'] . $ticket['ticket'] . $return['card_id']);
                M('Card_new')->where(array('mer_id' => $mer_id))->save($wx_data);
                $errormsg = $return['errmsg'];
            }
            if(IS_AJAX){

                $this->returnCode(0,array('msg'=>'微信会员卡同步成功'));
            }else{
                return array('error_code'=>0,'msg'=>'，微信会员卡同步成功');
            }
        } else {
            $wx_data['is_wx_card'] = 0;
            $errormsg = $return['errmsg'];
            $wx_data['weixin_err'] = serialize($errormsg);
            M('Card_new')->where(array('mer_id' => $mer_id))->save($wx_data);

            if(IS_AJAX){
                $this->returnCode('20170108','','同步微信会员卡出错，请检查您配置的数据是否正确，微信返回信息：'.$return['errmsg']);
            }else{
                return array('error_code'=>1,'msg'=>'，同步微信会员卡出错，请检查您配置的数据是否正确，微信返回信息：'.$return['errmsg']);
            }
        }
    }
	
	//修改密码验证码
	public function sendCode(){
		$type = I('type',2);

        if($type!=1) {
            if ($type != 4 && empty($this->mer_id)) {
                $this->returnCode('20140071');
            }

            if ($type == 4) {
                if (empty($_POST['phone'])) {
                    $this->returnCode('20140072');
                }
                $now_merchant = M('Merchant')->where(array('phone' => $_POST['phone']))->find();
                if (empty($now_merchant)) {
                    $this->returnCode('20140073');
                }
                $this->merchant = $now_merchant;
                $this->mer_id = $now_merchant['mer_id'];
            }
            //防止1分钟内多次发送短信
//            $laste_sms = M('Merchant_sms_record')->where(array('mer_id' => $this->mer_id))->order('pigcms_id DESC')->find();
//            if (time() - $laste_sms['send_time'] < 60) {
//                $this->returnCode('20046036');
//            }
            $phone = $this->merchant['phone'];
        }else{
            if(empty($_POST['phone'])){
                $this->returnCode('20140072');
            }
            $phone = $_POST['phone'];
            $this->mer_id = 0;
        }
        $code = mt_rand(1000, 9999);
		
		if($type == 4){
			$text = '确认码：'.$code.'。您正在进行找回密码操作，请不要把确认码泄露给其他人。';		
		}else{
			$text = '您的验证码是：' . $code . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
		}
		if($_POST['newphone']){
            $phone = $_POST['newphone'];
        }

        $columns = array();
        $columns['phone'] = $phone;
        $columns['mer_id'] = $this->mer_id;
        $columns['extra'] = $code;
        $columns['type'] = $type;
        $columns['status'] = 0;
        $columns['send_time'] = time();
        $columns['expire_time'] = $columns['send_time'] + 72000;
        $result = M("Merchant_sms_record")->add($columns);
        if (!$result){
            $this->returnCode('20044007');
        }
        $sms_data = array('mer_id' => $this->mer_id, 'store_id' => 0, 'content' => $text, 'mobile' => $phone, 'uid' => 0, 'type' => 'merchant_pwd');
        $_POST['phone_country_type'] && $sms_data['nationCode']  = $_POST['phone_country_type'];
        $return = Sms::sendSms($sms_data);

        if ($result != 0) {
            $this->returnCode(self::ConverSmsCode($return));
        }
        $this->returnCode(0, $return);
	}
	
	
	//群发记录
    public function custom_fans_list(){
        $mer_id = $this->merchant['mer_id'];

        $sql = "SELECT COUNT(1) as count, from_merchant FROM " . C('DB_PREFIX') . "merchant_user_relation WHERE `mer_id`='$mer_id' GROUP BY from_merchant";
        $mode = new Model();
        $count_list = $mode->query($sql);
        $total = 0;
        $arr['from_type']=array(
            '-1'=>'全部粉丝',
            '0'=>'扫描商家产品二维码',
            '1'=>'扫描商家二维码',
            '2'=>'平台赠送',
            '3'=>'扫描产品推广二维码',
            '5'=>$this->config['group_alias_name'],
            '6'=>$this->config['shop_alias_name'],
            '7'=>$this->config['meal_alias_name'],
            '8'=>$this->config['appoint_alias_name'],
            '9'=>$this->config['cash_alias_name'],
        );
        $tmp =array();
        $tmp[] = array('id' =>'-1', 'name' =>'全部粉丝');
        foreach ($count_list as $rr) {
            $total += $rr['count'];
            $tmp[] = array('id' => $rr['from_merchant'], 'name' =>  $arr['from_type'][$rr['from_merchant']], 'value' => $rr['count'],'score_need'=>$this->config['customer_one_score']*$rr['count']);
        }
        $tmp[0]['value'] = $total;
        $tmp[0]['score_need'] = $this->config['customer_one_score']*$total;
        $arr['fans_list']  =$tmp;


        $list = D('Source_material')->where(array('mer_id' => $this->merchant['mer_id']))->order('pigcms_id DESC')->select();
        $it_ids = array();
        $temp = array();
        foreach ($list as $l) {
            foreach (unserialize($l['it_ids']) as $id) {
                if (!in_array($id, $it_ids)) $it_ids[] = $id;
            }
        }
        $result = array();
        $image_text = D('Image_text')->field('pigcms_id, title')->where(array('pigcms_id' => array('in', $it_ids)))->order('pigcms_id asc')->select();
        foreach ($image_text as $txt) {
            $result[$txt['pigcms_id']] = $txt;
        }
        foreach ($list as &$l) {
            $l['dateline'] = date('Y-m-d H:i:s', $l['dateline']);
            foreach (unserialize($l['it_ids']) as $id) {
                $l['title'] = $result[$id]['title'];
//                $l['list'][] = isset($result[$id]) ? $result[$id] : array();
            }
        }
        $arr['list'] = $list;

        $arr['customer_one_score'] = $this->config['customer_one_score'];
        $arr['plat_score'] = $this->merchant['plat_score'];
        $arr['storage_indexsort'] = $this->merchant['storage_indexsort'];
        $arr['customer_one_score_exchange'] = $this->config['customer_one_score_exchange'];

        if(IS_AJAX){
            $this->returnCode(0, $arr);
        }else{
            return $arr;
        }
    }

    public  function ChangeScore(){
        $mer_id = $this->merchant['mer_id'];
        $score = $_POST['score'];

        $database_merchant = D('Merchant');
        $condition_merchant['mer_id'] = $this->merchant['mer_id'];
        $now_merchant = $database_merchant->field(true,'pwd')->where($condition_merchant)->find();
        if($now_merchant['storage_indexsort'] >= $score){
            // 可以兑换的值
            $exchangeScore = $score*$this->config['customer_one_score_exchange'];
            $storage_indexsort = $now_merchant['storage_indexsort']-$score;
            $storage_indexsort = $storage_indexsort>0?$storage_indexsort:0;
            // 更新积分
            $database_merchant->where($condition_merchant)->setInc('plat_score',$exchangeScore);
            $database_merchant->where($condition_merchant)->save(array('storage_indexsort'=>$storage_indexsort));

            $current = D('Merchant')->field(true,'pwd')->where($condition_merchant)->find();
            $this->returnCode(0, array('error_code' => 0,'current_score'=>$current['plat_score'],'storage_indexsort'=>$current['storage_indexsort']));
        }
    }
    public function custom_send(){
        //$date = $this->custom_fans_list();
        //$fans_list  = $date['fan_list'];
        $mer_id = $this->merchant['mer_id'];
        $source_id = isset($_POST['source_id']) ? intval($_POST['source_id']) : 0;
        $type = isset($_POST['type']) ? intval($_POST['type']) : 0;
        if (empty($source_id)) {
            $this->returnCode('20140080');
        }
        $data = array('mer_id' => $mer_id, 'c_id' => $source_id, 'type' => $type, 'dateline' => time());

        // 扣除积分
        $condition_merchant['mer_id'] = $this->merchant['mer_id'];
        $exchangeScore = $_POST['score'];
        $current = D('Merchant')->field(true,'pwd')->where($condition_merchant)->find();
        if($current['plat_score'] < $exchangeScore){
            $this->returnCode('20140081');
        }

        $id = D('Send_log')->add($data);
        if (empty($id))  $this->returnCode('20140082');
        D('Merchant')->where($condition_merchant)->setDec('plat_score',$exchangeScore);
        $this->returnCode(0);
    }

    public function custom_user_detail(){
        $id = isset($_POST['logid']) ? intval($_POST['logid']) : 0;
        $page =I('pindex',1);

        $sql = "SELECT u.nickname,u.phone,u.avatar,u.sex,u.youaddress,u.uid, s.status as st FROM " . C('DB_PREFIX') . "send_user AS s LEFT JOIN  " . C('DB_PREFIX') . "user AS u ON `s`.`openid`=`u`.`openid` WHERE `s`.`log_id`={$id} LIMIT {$page},10";
        $mode = new Model();
        $fans_list = $mode->query($sql);
        $this->returnCode(0,  $fans_list);
    }

    public  function custom_txtdetail(){
        $p_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        $source = D('Source_material')->where(array('pigcms_id' => $p_id))->find();
        $ids = unserialize($source['it_ids']);
        $image_text = D('Image_text')->field(true)->where(array('pigcms_id' => array('in', $ids)))->select();
        $result = array();
        foreach ($image_text as $key=>$txt) {
            unset($txt['content']);
            $txt['url']  =$this->config['site_url'].'/wap.php?c=Article&a=index&imid='.$txt['pigcms_id'];
            $txt['cover_pic']  =$this->config['site_url'].$txt['cover_pic'];
            $result[$txt['pigcms_id']] = $txt;
        }
        $image_text = array();
        foreach ($ids as $id) {
            $image_text[] = isset($result[$id]) ? $result[$id] : array();
        }
        $this->returnCode(0,  $image_text);

    }

	public function custom_send_list(){

        $page =I('pindex',1);
        $status = I('status',1);

        $from_type=array(
            '0'=>'扫描商家产品二维码',
            '1'=>'扫描商家二维码',
            '2'=>'平台赠送',
            '3'=>'扫描产品推广二维码',
            '5'=>$this->config['group_alias_name'],
            '6'=>$this->config['shop_alias_name'],
            '7'=>$this->config['meal_alias_name'],
            '8'=>$this->config['appoint_alias_name'],
            '9'=>$this->config['cash_alias_name'],
        );

        $log_list = D('Send_log')->where(array('mer_id' => $this->merchant['mer_id'],'status'=>$status))->order('pigcms_id DESC')->page($page,10)->select();

        $arr['send_count'] = D('Send_log')->where(array('mer_id' => $this->merchant['mer_id'],'status'=>1))->count();
        $arr['check_count'] = D('Send_log')->where(array('mer_id' => $this->merchant['mer_id'],'status'=>0))->count();
        $arr['rej_count'] = D('Send_log')->where(array('mer_id' => $this->merchant['mer_id']))->count()-$arr['send_count']-$arr['check_count'];

        foreach ($log_list as &$v) {
            $source = D('Source_material')->where(array('pigcms_id' => $v['c_id']))->find();
            $ids = unserialize($source['it_ids']);
            $image_text = D('Image_text')->field(true)->where(array('pigcms_id' => array('in', $ids)))->select();

            $v['title'] = $image_text[0]['title'];
            $v['digest'] = $image_text[0]['digest'];
            $v['fan_type'] = $from_type[$v['type']];
            $v['txt_count'] = count($image_text);
            $v['dateline'] = date('Y-m-d H:i:s',$v['dateline']);
        }
        $arr['log_list'] = $log_list?$log_list:array();
        $this->returnCode(0,  $arr);
	}
	
	//回复评价
	public function user_rating_reply(){
		$pigcms_id = $_POST['detail_id'];
		
		if(empty($pigcms_id) || empty($_POST['reply_content'])){
			$this->returnCode('20140079');
		}
		
		$database_reply = D('Reply');
			
		$reply_detail = $database_reply->where(array('pigcms_id'=>$pigcms_id))->find();
		if(empty($reply_detail)){
			$this->returnCode('20140075');
		}
		if($reply_detail['mer_id'] != $this->mer_id){
			$this->returnCode('20140078');
		}
		if($reply_detail['merchant_reply_time']){
			$this->returnCode('20140076');
		}
	
		$condition_reply['pigcms_id'] = $reply_detail['pigcms_id'];
		$data_reply['merchant_reply_content'] = $_POST['reply_content'];
		$data_reply['merchant_reply_time'] = $_SERVER['REQUEST_TIME'];
		if($database_reply->where($condition_reply)->data($data_reply)->save()){
			$this->returnCode('0');
		}else{
			$this->returnCode('20140077');
		}
	}
	
	//评价列表
	public function user_rating_list(){
		if(empty($_POST['page'])){
			$_POST['page'] = 1;
		}
		$return = array();
		
		$condition_reply['order_type'] = isset($_POST['order_type']) ? $_POST['order_type'] : 3;
		
		if($_POST['page'] <= 1 && empty($_POST['tab']) && empty($_POST['detail_id'])){
			$store_list = D('Merchant_store')->field('`store_id`,`name`,`have_group`,`have_meal`,`have_shop`')->where(array('mer_id'=>$this->mer_id))->order('`sort` DESC,`store_id` ASC')->select();
			$return['store_list'] = $store_list;
			
			$return['have_group'] = false;
			$return['have_meal'] = false;
			$return['have_shop'] = false;
			foreach($return['store_list'] as $value){
				if($value['have_group']){
					$return['have_group'] = true;
				}
				if($value['have_meal']){
					$return['have_meal'] = true;
				}
				if($value['have_shop']){
					$return['have_shop'] = true;
				}
			}
			if(empty($return['have_shop'])){
				$condition_reply['order_type'] = 0;
			}
			if(empty($return['have_group'])){
				$condition_reply['order_type'] = 4;
			}
		}
		
		if($_POST['store_id']){
			$condition_reply['store_id'] = $_POST['store_id'];
		}else{
			$condition_reply['mer_id']   = $this->mer_id;
		}
		
		if($_POST['tab']){
			switch($_POST['tab']){
				case 'high':
					$condition_reply['score'] = array('gt',3);
					break;
				case 'mid':
					$condition_reply['score'] = 3;
					break;
				case 'low':
					$condition_reply['score'] = array('lt',3);
					break;
			}
		}
		$condition_reply['status'] = array('elt',1);
		if($_POST['merchant_reply']){
			switch($_POST['merchant_reply']){
				case 'yes':
					$condition_reply['merchant_reply_content'] = array('neq','');
					break;
				case 'no':
					$condition_reply['merchant_reply_content'] = '';
					break;
			}
		}
		
		$reply_count = M('Reply')->where($condition_reply)->count();
		
		$page_size = 10;
		$firstRow = ($_POST['page']-1) * $page_size;
		
		if($_POST['detail_id']){
			$condition_reply = array(
				'pigcms_id' => $_POST['detail_id'],
			);
		}
		
		$condition_reply_string = '';
		foreach($condition_reply as $key=>$value){
			$character = '=';
			if(is_array($value)){
				if($value[0] == 'lt'){
					$character = '<' ;
				}else if($value[0] == 'gt'){
					$character = '>' ;
				}else if($value[0] == 'neq'){
					$character = '<>' ;
				}else if($value[0] == 'elt'){
					$character = '<=' ;
				}
				$value = $value[1];
			}
			
			$condition_reply_string .= "`r`.`$key`$character'$value' AND";
		}
		$condition_reply_string .= '`r`.`uid`=`u`.`uid`';
		
		$return['reply_list'] = D('')->field('`u`.`nickname`,`u`.`avatar`,`r`.*')->table(array(C('DB_PREFIX').'reply'=>'r',C('DB_PREFIX').'user'=>'u'))->where($condition_reply_string)->order('`r`.`add_time` DESC')->limit($firstRow . ',' . $page_size)->select();
		
		if($return['reply_list']){
			$pic_arr = array();
			$new_pic_arr = array();
			foreach($return['reply_list'] as $key=>$value){
				if (isset($value['goods']) && $value['goods']) {
					$return['reply_list'][$key]['goods'] = explode('#@#', $value['goods']);
				}else{
					$return['reply_list'][$key]['goods'] = array();
				}
				$return['reply_list'][$key]['add_time'] = date('Y-m-d',$value['add_time']);
				$return['reply_list'][$key]['add_time_hi'] = date('Y-m-d H:i',$value['add_time']);
				if($value['merchant_reply_time']){
					$return['reply_list'][$key]['merchant_reply_time_hi'] = date('Y-m-d H:i',$value['merchant_reply_time']);
				}
				if($value['anonymous']){
					if(msubstr($value['nickname'],0,2,false) == $value['nickname']){
						$return['reply_list'][$key]['nickname'] = msubstr($value['nickname'],0,1,false).'**';
					}else{
						$return['reply_list'][$key]['nickname'] = msubstr($value['nickname'],0,1,false).'**'.msubstr($value['nickname'],-1,1,false);
					}
				}
				if(!empty($value['pic'])){
					$tmp_arr = explode(',',$value['pic']);
					foreach($tmp_arr as $v){
						$new_pic_arr[$v] = $key;
					}
					$pic_arr = array_merge($pic_arr,$tmp_arr);
				}
			}
			if ($order_type == 0) {
				$pic_filepath = 'group';
			} elseif($order_type == 1) {
				$pic_filepath = 'meal';
			} else {
				$pic_filepath = 'appoint';
			}
			if($pic_arr){
				$condition_reply_pic['pigcms_id'] = array('in',implode(',',$pic_arr));
				$reply_pic_list = D('Reply_pic')->field('`pigcms_id`,`pic`')->where($condition_reply_pic)->select();
				$reply_image_class = new reply_image();
				foreach($reply_pic_list as $key=>$value){
					$tmp_value = $reply_image_class->get_image_by_path($value['pic'],$pic_filepath);
					$return['reply_list'][$new_pic_arr[$value['pigcms_id']]]['pics'][] = $tmp_value;
				}
			}
		}else{
			$return['reply_list'] = array();
		}
		$return['store_score'] = -1;
		$return['store_deliver_score'] = -1;
		if ($condition_reply['order_type'] == 3 && $_POST['store_id']) {
		    $store = D('Merchant_store_shop')->field('score_mean, reply_deliver_score')->where(array('store_id' => $_POST['store_id']))->find();
		    $return['store_score'] = isset($store['score_mean']) ? $store['score_mean'] : 0;
		    $return['store_deliver_score'] = isset($store['reply_deliver_score']) ? $store['reply_deliver_score'] : 0;
		}
		$this->returnCode(0,$return);
	}

    public function allinyun_money_list(){
        import('@.ORG.AccountDeposit.AccountDeposit');
        $deposit = new AccountDeposit('Allinyun');
        $allyun = $deposit->getDeposit();
        $allinyun = M('Merchant_allinyun_info')->where(array('mer_id'=>$this->mer_id))->find();
        $allyun->setUser($allinyun);
        if($allinyun['status']!=0){
            $balance = $allyun->queryBalance();
        }
        if(isset($_POST['period'])&&!empty($_POST['period'])) {

            $period = explode('-', $_POST['period']);
            $_POST['begin_time'] = str_replace('/','-',$period[0]);
            $_POST['end_time'] = str_replace('/','-',$period[1]);
        }

        $arr['money'] = floatval($balance['signedResult']['allAmount']/100);

        $result = $allyun->queryInExpDetail($_GET['page']?($_POST['page']-1)*10+1:1,$_POST['begin_time'],$_POST['end_time']);
        $order_type  =$this->get_alias_c_name2();
        foreach ($result['signedResult']['inExpDetail'] as &$v) {
            $tmp = explode('_',$v['bizOrderNo']);
            $v['order_type']  =$order_type[$tmp[0]];
            $v['order_id']  =$tmp[1];
            $v['chgAmount']  =$v['chgAmount']/100;
        }
        $count = $result['signedResult']['totalNum'];
        $arr = array('income_list'=>$result['signedResult']['inExpDetail'],'page_num'=>$count);
        $this->returnCode(0,$arr);

    }

    public function create_bank_account(){
		$data_companypay['from']     = 1;
		$data_companypay['type']     = 0;
		$data_companypay['pay_id']   = $this->mer_id;
		$data_companypay['account_name'] = $_POST['account_name'];
		$data_companypay['account'] = $_POST['account'];
		$data_companypay['is_default'] = $_POST['is_default'];
		$data_companypay['add_time'] = $_SERVER['REQUEST_TIME'];
		$data_companypay['remark']    = $_POST['remark'];

		if($_POST['bank_id']){
			$url  = U('bank_list');
			$result = M('User_withdraw_account_list')->where(array('id'=>$_POST['bank_id']))->save($data_companypay);
			if($result){
				$aid = $_POST['bank_id'];
			}else{
				$aid = false;
			}
		}else{
			$aid = M('User_withdraw_account_list')->add($data_companypay);
			$url  = U('withdraw');
		}

		if($aid){
			if($data_companypay['is_default']){
				$where['from'] = 1;
				$where['type'] = 0;
				$where['pay_id'] =   $this->mer_id;
				$where['id'] = array('neq',$aid);
				M('User_withdraw_account_list')->where($where)->setField('is_default',0);

			}
			$this->returnCode(0,array('status'=>1,'msg'=>'保存成功'));die;
		}else{
			$this->returnCode('20170108','','保存失败');
		}
    }

    public function bank_account_info(){
        $where['id'] =  $_POST['bank_id'];
        $bank = M('User_withdraw_account_list')->where($where)->find();
        if(empty($bank)){
            $this->returnCode('20170109','','卡不存在');
        }
        $bank['add_time'] = date('Y-m-d H:i:s',$bank['add_time']);
        $return['bank']  = $bank;
        $this->returnCode(0,$return);
    }

    public function bank_list(){
        $where['from'] = 1;
        $where['type'] = 0;
        $where['pay_id'] =  $this->mer_id;
        $bank_list = M('User_withdraw_account_list')->where($where)->order('is_default DESC,id DESC')->select();
		if(!$bank_list){
			$bank_list = array();
		}else{
			foreach ($bank_list as &$v) {
				$v['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
			}
        }
        $return['bank_list']  = $bank_list;

        $this->returnCode(0,$return);
    }

    public function delete_bank_account(){
        $where['id'] = $_POST['bank_id'];
        M('User_withdraw_account_list')->where($where)->delete();
        $this->returnCode(0,array('status'=>1,'msg'=>'删除成功'));die;
    }

    //添加业务员
    public function set_invit_code()
    {
        M('Merchant')->where(array('mer_id'=>$this->merchant['mer_id']))->setField('invit_code',$_POST['invit_code']);
        $this->returnCode(0);
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
}
?>
