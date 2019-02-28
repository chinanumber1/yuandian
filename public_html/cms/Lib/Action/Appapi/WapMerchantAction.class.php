<?php
class WapMerchantAction extends BaseAction
{

    protected $merchant_session;

    protected $store;

    protected $merid;
    
    const GOODS_SORT_LEVEL = 3;
    
    protected $order_froms  = array('手机版', '商城', '安卓应用', '苹果应用', '小程序', '电脑版快店', '线下零售', '饿了么', '美团','扫码购');//array('手机版', '商城', '安卓应用', '苹果应用', '小程序', '电脑版快店', '线下零售');
    
    protected function _initialize()
    {
        parent::_initialize();
        $ticket = I('ticket', false);
        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info) {
                $condition_merchant['mer_id'] = $info['uid'];
            }
            $database_merchant = D('Merchant');
            $this->merchant_session = $database_merchant->field(true)->where($condition_merchant)->find();
            $this->merid = $info['uid'];
        }
        $this->config['have_group_name'] = isset($this->config['group_alias_name']) ? $this->config['group_alias_name'] : '团购';// 团购
        $this->config['have_meal_name'] = isset($this->config['meal_alias_name']) ? $this->config['meal_alias_name'] : '餐饮'; // 餐饮
        $this->config['have_shop_name'] = isset($this->config['shop_alias_name']) ? $this->config['shop_alias_name'] : '快店'; // 快店
        $this->config['have_appoint_name'] = isset($this->config['appoint_alias_name']) ? $this->config['appoint_alias_name'] : '预约'; // 预约
    }
	public function config(){
		$arr['open_score_fenrun'] = isset($this->config['open_score_fenrun']) ? $this->config['open_score_fenrun'] : 0; // 店员中心APP包名
		$arr['withdraw_fee_percent'] = isset($this->config['company_pay_mer_percent']) ? $this->config['company_pay_mer_percent'] : 0; // 提现手续费率
		$arr['withdraw_type'] = array(0=>'银行卡','1'=>'支付宝','2'=>'平台');
		$this->returnCode(0, $arr);
	}
        // 商户中心获取信息
    public function indexshow()
    {
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
        $database_merchant = D('Merchant');
        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info) {
                $condition_merchant['mer_id'] = $info['uid'];
            }
        } else {
            $account = I('account');
            $condition_merchant['account'] = trim($account);
        }
        $now_merchant = $database_merchant->field(true)
            ->where($condition_merchant)
            ->find();
        if (empty($now_merchant)) {
            $this->returnCode('20140001');
        }
        if (empty($ticket)) {
            $pwd = I('pwd');
            $pwd = md5(trim($pwd));
            if ($pwd != $now_merchant['pwd']) {
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
        if ($database_merchant->data($data_merchant)->save()) {
            $now_merchant['login_count'] += 1;
            if (! empty($now_merchant['last_ip'])) {
                import('ORG.Net.IpLocation');
                $IpLocation = new IpLocation();
                $last_location = $IpLocation->getlocation(long2ip($now_merchant['last_ip']));
                $now_merchant['last']['country'] = iconv('GBK', 'UTF-8', $last_location['country']);
                $now_merchant['last']['area'] = iconv('GBK', 'UTF-8', $last_location['area']);
            }
            $this->returnCode(0, $return);
        } else {
            $this->returnCode('20140005');
        }
    }
	# 商家入驻
	public function mer_reg() {
		//帐号
		$database_merchant = D('Merchant');
		$arr['account'] =	$condition_merchant['account'] = I('account');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
		if (!empty($now_merchant)) {
			$this->returnCode('20140006');
		}
		//名称
		$arr['name'] = $condition_merchant['name'] = I('mername');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
		if (!empty($now_merchant)) {
			$this->returnCode('20140007');
		}
		//邮箱
		$arr['email'] =	$condition_merchant['email'] = I('email');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
		if (!empty($now_merchant)) {
			$this->returnCode('20140008');
		}
		//手机号
		$arr['phone'] =	$condition_merchant['phone'] = I('phone');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
		if (!empty($now_merchant)) {
			$this->returnCode('20140009');
		}
		$config = D('Config')->get_config();
        
		$arr['mer_id'] = null;
		if ($config['merchant_verify']) {
			$arr['status'] = 2;
		} else {
			$arr['status'] = 1;
		}
		$pwd	=	I('pwd');
		$city_id	=	I('city_id');
		$area_id	=	I('area_id');
		$arr['pwd'] = md5($pwd);
		$arr['reg_ip'] = get_client_ip(1);
		$arr['reg_time'] = $_SERVER['REQUEST_TIME'];
		$arr['city_id'] = $city_id;
		$arr['area_id'] = $area_id;
		$arr['login_count'] = 0;
		$arr['reg_from'] = 0;
		$arr['spread_code'] = 0;
		if ($insert_id = $database_merchant->data($arr)->add()) {
			M('Merchant_score')->add(array('parent_id' => $insert_id, 'type' => 1));
			if ($config['merchant_verify']) {
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
	    if (empty($this->merchant_session['qrcode_id'])) {
	        $qrcode_return = D('Recognition')->get_new_qrcode('merchant', $this->merchant_session['mer_id']);
	    } else {
	        $qrcode_return = D('Recognition')->get_qrcode($this->merchant_session['qrcode_id']);
	    }
		$number	=	$this->getallordercount();
		$arr	=	array(
			'wap_merchantAd'	=>	$wap_MerchantAd==false?array():$Ad,		//广告牌
			'qrcodeinfo'		=>	isset($qrcode_return['qrcode'])?$qrcode_return['qrcode']:'',	//二维码
			'count_number'		=>	array(
				'allincomecount'	=>	(int)$allincomecount,					//收入总数
				'webviwe'			=>	(int)$this->merchant_session['hits'],	//浏览总数
				'allordercount'		=>	(int)$number['allordercount'],			//订单总数
				'monthordercount'	=>	(int)$number['monthordercount'],		//本月订单总数
				'todayordercount'	=>	(int)$number['todayordercount'],		//本日订单总数
				'fans_count'		=>	(int)$number['fans_count'],				//粉丝总数
				'logo'				=>	$this->config['site_merchant_logo'],	//商户logo
//				'appoint_page_row'	=>	isset($this->config['appoint_page_row']) ? 1 : 0,	预约判断
			),
		);
		$arr['type_name'] = $this->get_alias_c_name();         //业务类型.
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
                $mer,
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
        $shop_orderDb = M('Shop_order');
        switch ($action) {
            case 'order' :
                $mdatas = $meal_orderDb->where('mer_id=' . $this->merchant_session['mer_id'] . ' AND dateline >' . $startime . ' AND dateline <=' . $nowtime . " AND status!=3")->field('count(order_id) as percount,FROM_UNIXTIME(dateline,"%m-%d") as perdate')->group('perdate')->select();
                foreach ($mdatas as $mvv) {
                    $newdatas[$mvv['perdate']] = (int)$mvv['percount'];
                }
                unset($mdatas);
                $gdatas = $group_orderDb->where('mer_id=' . $this->merchant_session['mer_id'] . ' AND add_time  >' . $startime . ' AND add_time  <=' . $nowtime . " AND status!=3")->field('count(order_id) as percount,FROM_UNIXTIME(add_time,"%m-%d") as perdate')->group('perdate')->select();
                foreach ($gdatas as $gvv) {
                    $newdatas[$gvv['perdate']] = isset($newdatas[$gvv['perdate']]) ? $newdatas[$gvv['perdate']] + $gvv['percount'] : $gvv['percount'];
                }

                $sdatas = $shop_orderDb->where('mer_id=' . $this->merchant_session['mer_id'] . ' AND create_time  >' . $startime . ' AND create_time  <=' . $nowtime . " AND status!=4")->field('count(order_id) as percount,FROM_UNIXTIME(create_time,"%m-%d") as perdate')->group('perdate')->select();
                foreach ($sdatas as $svv) {
                    $newdatas[$svv['perdate']] = isset($newdatas[$svv['perdate']]) ? $newdatas[$svv['perdate']] + $svv['percount'] : $svv['percount'];
                }
                break;
            case 'income' :
                $mdatas = $meal_orderDb->where('mer_id=' . $this->merchant_session['mer_id'] . ' AND paid="1" AND dateline >' . $startime . ' AND dateline <=' . $nowtime . " AND status!=3")->field('sum(if(total_price>0,total_price,price)) as tprice,sum(minus_price) as offprice,FROM_UNIXTIME(dateline,"%m-%d") as perdate')->group('perdate')->select();
                if (!empty($mdatas)) {
                    foreach ($mdatas as $mvv) {
                        $newdatas[$mvv['perdate']] = (int)$mvv['tprice'] - (int)$mvv['offprice'];
                    }
                }
                unset($mdatas);
                $gdatas = $group_orderDb->where('mer_id=' . $this->merchant_session['mer_id'] . ' AND paid="1" AND add_time  >' . $startime . ' AND add_time  <=' . $nowtime . " AND status!=3")->field('sum(total_money) as tprice,sum(wx_cheap) as offprice,FROM_UNIXTIME(add_time,"%m-%d") as perdate')->group('perdate')->select();
                if (!empty($gdatas)) {
                    foreach ($gdatas as $gvv) {
                        $perprice = $gvv['tprice'] - $gvv['offprice'];
                        $newdatas[$gvv['perdate']] = isset($newdatas[$gvv['perdate']]) ? $newdatas[$gvv['perdate']] + $perprice : $perprice;
                    }
                }

                $sdatas =  $shop_orderDb->where('mer_id=' . $this->merchant_session['mer_id'] . ' AND create_time  >' . $startime . ' AND create_time  <=' . $nowtime . " AND status<4 AND status>1")->field('sum(price) as tprice,FROM_UNIXTIME(create_time,"%m-%d") as perdate')->group('perdate')->select();
                foreach ($sdatas as $svv) {
                    $newdatas[$svv['perdate']] = isset($newdatas[$svv['perdate']]) ? $newdatas[$svv['perdate']] + $svv['tprice'] : $svv['tprice'];
                }
                break;
            case 'member' :
                $fansdata = M('')->table(array(C('DB_PREFIX') . 'merchant_user_relation' => 'm', C('DB_PREFIX') . 'user' => 'u'))->where("`m`.`openid`=`u`.`openid` AND `m`.`mer_id`='" . $this->merchant_session['mer_id'] . "' AND dateline >" . $startime . " AND dateline <=" . $nowtime)->field('count(dateline) as percount,FROM_UNIXTIME(dateline,"%m-%d") as perdate')->group('perdate')->select();
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
		$tmp_m_price = $shopOrderDb->where('mer_id=' . $this->merid . ' AND paid="1" AND status != 0')->field('price as tprice')->find();
		$tmp_m_price['tprice'] = number_format($tmp_m_price['tprice']);
		$meal_price = $tmp_m_price['tprice'] ;
		$tmp_g_price = $group_orderDb->where('mer_id=' . $this->merid . ' AND paid="1" AND status != 3')->field('sum(total_money) as tprice')->find();
		$group_price = $tmp_g_price['tprice'] ;
		$tmp_g_price = $appoint_orderDb->where('mer_id=' . $this->merid . ' AND paid="1" AND status != 3')->field('sum(pay_money) as tprice')->find();
		$appoint_price = $tmp_g_price['tprice'] ;
		return ($meal_price + $group_price+$appoint_price);
	}
    # 订单总数 月订单总数 日订单总数 粉丝数量
    private function getallordercount() {
    	$shopOrderDb = M('Foodshop_order');
    	$group_orderDb = M('Group_order');
    	$shop_orderDb = M('Shop_order');
    	$store_orderDb = M('Store_order');
    	$income = M('Merchant_money_list');
    	$meal_order_all = $shopOrderDb->where(array('mer_id' => $this->merid, 'status' => array('neq', 0)))->count();
        $nowtime = time();
        $todaystartime = strtotime(date('Y-m-d'));
        $monthstartime = strtotime(date('Y-m') . '-01 00:00:00');
        $meal_order_m = $shopOrderDb->where('mer_id=' . $this->merid . ' AND status=3 AND create_time >' . $monthstartime . ' AND create_time <=' . $nowtime)->count();
        $meal_order_d = $shopOrderDb->where('mer_id=' . $this->merid . ' AND status=3 AND create_time >' . $todaystartime . ' AND create_time <=' . $nowtime)->count();
        $group_order_all = $group_orderDb->where(array('paid' => 1, 'mer_id' => $this->merid, 'status' => array('neq', 3)))->count();
        $group_order_m = $group_orderDb->where('paid=1 AND mer_id=' . $this->merid . ' AND status!=3 AND add_time >' . $monthstartime . ' AND add_time <=' . $nowtime)->count();
        $group_order_d = $group_orderDb->where('paid=1 AND mer_id=' . $this->merid . ' AND status!=3 AND add_time >' . $todaystartime . ' AND add_time <=' . $nowtime)->count();

        $shop_order_all = $shop_orderDb->where(array('paid' => 1, 'mer_id' => $this->merid, 'status' => array('neq', 4)))->count();
        $shop_order_m = $shop_orderDb->where('paid=1 AND mer_id=' . $this->merid . ' AND status!=4 AND create_time >' . $monthstartime . ' AND create_time <=' . $nowtime)->count();
        $shop_order_d = $shop_orderDb->where('paid=1 AND mer_id=' . $this->merid . ' AND status!=4 AND create_time >' . $todaystartime . ' AND create_time <=' . $nowtime)->count();

        $store_order_all = $store_orderDb->where(array('paid' => 1, 'mer_id' => $this->merid))->count();
        $store_order_m = $store_orderDb->where('paid=1 AND mer_id=' . $this->merid . ' AND pay_time >' . $monthstartime . ' AND pay_time <=' . $nowtime)->count();
        $store_order_d = $store_orderDb->where('paid=1 AND mer_id=' . $this->merid . ' AND pay_time >' . $todaystartime . ' AND pay_time <=' . $nowtime)->count();

        $income_all = $income->where(array('type'=>1,'mer_id' => $this->merid))->sum('money');
        $fans_count = M('')->table(array(C('DB_PREFIX') . 'merchant_user_relation' => 'm', C('DB_PREFIX') . 'user' => 'u'))->where("`m`.`openid`=`u`.`openid` AND `m`.`mer_id`='$this->merid'")->count();
        $arr	=	array(
			'allordercount'	=>	intval($meal_order_all + $group_order_all+$shop_order_all+$store_order_all),
			'monthordercount'	=>	intval($meal_order_m + $group_order_m+$shop_order_m+$store_order_m),
			'todayordercount'	=>	intval($meal_order_d + $group_order_d+$shop_order_d+$store_order_d),
			'fans_count'	=>	$fans_count,
			'income_all'	=>	$income_all,

        );
        return $arr;
    }

    // 店铺列表
    public function store_list()
    {
        $where['mer_id'] = $this->merchant_session['mer_id'];
        $where['status'] = array('neq', 4);
        $page = I('pindex', 1);
        $all = M('Merchant_store')->where($where)->count();
        $condition['mer_id'] = $this->merchant_session['mer_id'];
        if(isset($_POST['status'])){
            $condition['status'] = $_POST['status'];
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
        $where['status'] =4;
        $arr['close'] = M('Merchant_store')->where($where)->count();

        $arr['page'] = ceil($arr['all'] / 10);
        $this->returnCode(0, $arr);
    }
    # 店铺修改状态
    public function store_status(){
		$where['store_id']	=	I('store_id');
		$data['status']	=	I('status',1);
		//$data['status']	= $data['status'] == 1 ? 1 : 0;
		if($data['status'] == 4){
			$this->returnCode(1001,array(),'您当前APP版本无法执行关闭店铺操作');
		}
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
    	$where['mer_id']	=	$this->merchant_session['mer_id'];
    	$where['status']		=	1;
    	$where['have_shop']		=	1;
    	$page	=	I('pindex',1);
        $store_id = I('store_id', '');
        $store_id && $where['store_id'] =  $store_id;
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
        $data = M('Merchant_store')->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
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
        $this->merid = $this->merchant_session['mer_id'];
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

    private function formatList($list)
    {
        if (empty($list)) {
            return $list;
        }
        $result = array();
        foreach ($list as $l) {
            $l['week_str'] = isset($l['week_str']) ? $l['week_str'] : '';
            $l['sort_discount'] = $l['sort_discount'] / 10;
            $l['son_list'] = $this->formatList($l['son_list']);
            $result[] = $l;
        }
        return $result;
    }
    // 快店商品分类
    public function goodsSort()
    {
        $store_id = I('store_id');
        $arr = array();
        if ($store_id) {
            $database_goods_sort = D('Shop_goods_sort');
            $list = D('Shop_goods_sort')->lists($store_id, false);
            $result = array();
            foreach ($list as $l) {
                if ($l['is_weekshow']) {
                    $l['week_str'] = isset($l['week_str']) ? $l['week_str'] : '';
                } else {
                    $l['week_str'] = '';
                }
                $l['sort_discount'] = $l['sort_discount'] / 10;
                $l['son_list'] = $this->formatList($l['son_list']);
                $result[] = $l;
            }
            $arr['list'] = $result;
        } else {
            $this->returnCode('20140029');
        }
        $this->returnCode(0, $arr);
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

    public function sortAdd()
    {
        $fid = I('fid');;
        $store_id = I('store_id');
        $sort_name = I('sort_name');
        $_sort = I('sort');
        $is_weekshow = I('is_weekshow');
        $week = I('week', '');
        $sort_discount = I('sort_discount');
        if (empty($sort_name)) {
            $this->returnCode('20140030');
        } 
        
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['store_id'] = $store_id;
        $condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
        $now_store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺不存在！');
        } else {
            if ($now_shop = D('Merchant_store_shop')->field(true)->where($condition_merchant_store)->find()) {
                $now_store = array_merge($now_store, $now_shop);
            }
        }
        if ($sort = M('Shop_goods_sort')->field(true)->where(array('sort_id' => $fid, 'store_id' => $now_store['store_id']))->find()) {
            if ($now_store['is_mult_class'] == 0) {
                $this->returnCode(1, null, $sort['sort_name'] . '店铺暂未开启多级分类');
            }
            if ($sort['level'] == self::GOODS_SORT_LEVEL) {
                $this->returnCode(1, null, $sort['sort_name'] . '分类下不能再增加子分类');
            }
        } else {
            $fid = 0;
            $sort = null;
        }
        $database_goods_sort = D('Shop_goods_sort');
        $data_goods_sort['store_id'] = $now_store['store_id'];
        $data_goods_sort['sort_name'] = htmlspecialchars(trim($sort_name));
        $data_goods_sort['sort'] = intval($_sort);
        $data_goods_sort['sort_discount'] = intval(floatval($_POST['sort_discount']) * 10);
        $data_goods_sort['sort_discount'] = ($data_goods_sort['sort_discount'] > 100 || $data_goods_sort['sort_discount'] < 0) ? 0 : $data_goods_sort['sort_discount']; 
        $data_goods_sort['is_weekshow'] = intval($is_weekshow);
        $data_goods_sort['fid'] = $fid;
        $data_goods_sort['level'] = 1;
        if ($week != '') {
            $data_goods_sort['week'] = $week;
        }
        if ($sort) {
            $data_goods_sort['level'] = $sort['level'] + 1;
            if (M('Shop_goods')->field(true)->where(array('sort_id' => $fid, 'store_id' => $now_store['store_id']))->find()) {
                $this->returnCode(1, null, $sort['sort_name'] . '分类下有归属商品了，不能给它增加子分类');
            }
        }
        
        if ($data_goods_sort['level'] < self::GOODS_SORT_LEVEL) {
            $data_goods_sort['operation_type'] = 2;
        } else {
            $data_goods_sort['operation_type'] = 0;
        }
        
        if ($id = $database_goods_sort->data($data_goods_sort)->add()) {
            if ($sort && $sort['operation_type'] == 2) {
                $database_goods_sort->where(array('sort_id' => $sort['sort_id']))->save(array('operation_type' => 1));
            }
            $data_goods_sort['week_str'] = '';
            if ($week != '') {
                $week_arr = explode(',', $week);
                $week_str = '';
                foreach ($week_arr as $k => $v) {
                    $week_str .= $this->getWeek($v) . ' ';
                }
                $data_goods_sort['week_str'] = $week_str;
            }
            
            $data_goods_sort['sort_id'] = $id;
            $this->returnCode(0, $data_goods_sort);
        } else {
            $this->returnCode(1, null, '添加失败！！请重试。');
        }
    }
    protected function getWeek($num)
    {
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

    public function sortModify()
    {
        $sort_id = I('sort_id');
        $store_id = I('store_id');
        $now_sort = D('Shop_goods_sort')->field(true)->where(array('store_id' => $store_id, 'sort_id' => $sort_id))->find();
        $now_sort['sort_discount'] = $now_sort['sort_discount'] / 10;
        if ($now_sort['fid']) {
            $fsort = D('Shop_goods_sort')->field(true)->where(array('store_id' => $store_id, 'sort_id' => $now_sort['fid']))->find();
            $now_sort['ffid'] = isset($fsort['fid']) ? $fsort['fid'] : 0;
        } else {
            $now_sort['ffid'] = 0;
        }
        if (empty($now_sort)) {
            $this->returnCode(1, null, '请求的数据有误');
        } else {
            $this->returnCode(0, $now_sort);
        }
    }
    
    public function sortEdit()
    {
        $sort_id = I('sort_id');
        $fid = I('fid');
        $store_id = I('store_id');
        $sort_name = I('sort_name');
        $sort = I('sort');
        $is_weekshow = I('is_weekshow');
        $week = I('week', '');
        $sort_discount = I('sort_discount');
        $sort_discount = intval(floatval($sort_discount) * 10);
        $sort_discount = ($sort_discount > 100 || $sort_discount < 0) ? 0 : $sort_discount; 
        if (empty($sort_name)) {
            $this->returnCode('20140030');
        }
        
        $database_goods_sort = D('Shop_goods_sort');
        $condition_goods_sort['sort_id'] = $sort_id;
        $now_sort = $database_goods_sort->field(true)->where($condition_goods_sort)->find();
        if (empty($now_sort)) {
            $this->returnCode(1, null, '分类不存在！');
        }
        
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['store_id'] = $store_id;
        $condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
        $now_store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺不存在！');
        } else {
            if ($now_shop = D('Merchant_store_shop')->field(true)->where($condition_merchant_store)->find()) {
                $now_store = array_merge($now_store, $now_shop);
            }
        }
        if ($fsort = $database_goods_sort->field(true)->where(array('sort_id' => $fid, 'store_id' => $now_store['store_id']))->find()) {
            if ($fsort['level'] == self::GOODS_SORT_LEVEL) {
                $this->returnCode(1, null, $fsort['sort_name'] . '分类下不能再增加子分类');
            }
            
            if (M('Shop_goods')->field(true)->where(array('sort_id' => $fid, 'store_id' => $now_store['store_id']))->find()) {
                $this->returnCode(1, null, $fsort['sort_name'] . '分类下有归属商品了，不能给它增加子分类');
            }
            $data_goods_sort['level'] = $fsort['level'] + 1;
        } else {
            $fsort = null;
            $data_goods_sort['level'] = 1;
        }
        $data_goods_sort['sort_id'] = $sort_id;
        $data_goods_sort['store_id'] = $store_id;
        $data_goods_sort['sort_name'] = $sort_name;
        $data_goods_sort['sort'] = intval($sort);
        $data_goods_sort['is_weekshow'] = intval($is_weekshow);
        $data_goods_sort['sort_discount'] = intval($sort_discount);
        if ($week != '') {
            $data_goods_sort['week'] = $week;
        }
        $data_goods_sort['fid'] = $fid;
        
        if ($database_goods_sort->data($data_goods_sort)->save()) {
            if ($fsort && $fsort['operation_type'] == 2) {
                $database_goods_sort->where(array('sort_id' => $fsort['sort_id']))->save(array('operation_type' => 1));
            }
            $data_goods_sort['week_str'] = '';
            if ($week != '') {
                $week_arr = explode(',', $week);
                $week_str = '';
                foreach ($week_arr as $k => $v) {
                    $week_str .= $this->getWeek($v) . ' ';
                }
                $data_goods_sort['week_str'] = $week_str;
            }
            $this->returnCode(0, $data_goods_sort);
        } else {
            $this->returnCode(1, null, '保存失败！！您是不是没做过修改？请重试。');
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
		$sort_id = I('sort_id');
        $store_id = I('store_id');
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
		
		if ($fsort = $database_goods_sort->where(array('fid' => $sort_id))->select()) {
		    $this->returnCode(1, null, '先删除该分类下的子分类后才能删除该分类');
		}
		if ($database_goods_sort->where($condition_goods_sort)->delete()) {
			$this->returnCode(0);
		} else {
			$this->returnCode('20140036');
		}
	}

	private function getGoodsBySortId($sortId, $store_id)
	{
	    $now_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find();
	    $sortIds = D('Shop_goods_sort')->getAllSonIds($sortId, $store_id, true);
	    $product_list = D('Shop_goods')->getGoodsBySortIds($sortIds, $store_id, true);

	    $list = array();
	    foreach ($product_list as $row) {
	        foreach ($row['goods_list'] as $r) {
	            $list[] = $r;
	        }
	    }
	    return $list;
	}

	# 快店商品分类下的商品
	public function goods_list(){
		$sort_id	=	I('sort_id');
		$store_id	=	I('store_id');
		$page		=	I('pindex',1);
		$database_goods = D('Shop_goods');
// 		$condition_goods['sort_id'] = $sort_id;
// 		$count_goods = $database_goods->where($condition_goods)->count();
// 		$goods_list = $database_goods->field(true)->where($condition_goods)->order('`sort` DESC, `goods_id` ASC')->page($page,10)->select();
		$goods_list = $this->getGoodsBySortId($sort_id, $store_id);

		if(empty($goods_list)){
			$arr['list']	=	array();
			$arr['count']	=	0;
		}else{
			$plist = array();
			$sort_image_class = new goods_image();
			$prints = D('Orderprinter')->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id))->select();
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
        $condition_group['mer_id'] = $this->merchant_session['mer_id'];
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
    # 团购订单详情
    public function group_edit() {
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'],$order_id,false);
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
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'], $order_id, false);
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
		$status_format	=	$this->status_format($now_order['status'],$now_order['paid'],$now_order['pay_type'],$now_order['tuan_type']);
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
		);
		if($now_order['third_id']==0 && $now_order['pay_type']=='offline'){
			$arr['now_order']['total_moneys']	=	$now_order['total_money'];			//总金额
			$arr['now_order']['balance_pay']	=	$now_order['balance_pay'];			//平台余额支付
			$arr['now_order']['merchant_balance']	=	$now_order['merchant_balance'];	//商家会员卡余额支付
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
			$arr['now_order']['merchant_balance']=	$now_order['merchant_balance'];		//商家会员卡余额支付
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
		if($order_status	==	3){
			$status	=	1;	//已取消
		}else if($paid){
			if($third_id==3 && $pay_type=='offline' && $order_status==0){
				$status	=	2;	//线下未付款
			}else if($order_status==0){
				$status	=	3;	//已付款
				if($tuan_type!=2){
					$type	=	1;	//未消费
				}else{
					$type	=	2;	//未发货
				}
			}else if($order_status==1){
				$status	=	4;	//待评价
				if($tuan_type!=2){
					$type	=	3;	//已消费
				}else{
					$type	=	4;	//已发货
				}
			}else{
				$status	=	5;	//已完成
			}
		}else{
			$status	=	6;	//未付款
		}
		$arr	=	array(
			'type'	=>	$type,
			'status'	=>	$status,
		);
		return $arr;
    }
    # 修改团购订单归属店铺
    public function order_store_id() {
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'],$order_id, true, false);
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
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'], $order_id, true, false);
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
        $condition_group = 'mer_id=' . $this->merchant_session['mer_id'];
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
		$where['mer_id']	=	$this->merchant_session['mer_id'];
    	$where['status']	=	array('elt','2');
    	$page	=	I('pindex',1);
        $data = M('Merchant_store')->field(array('`mer_id`,`name`,`store_id`,`status`'))->where($where)->page($page,10)->select();
        if ($data != false) {
        	foreach($data as &$v){
				$v['qrcode']	=	$this->erwm($v['store_id']);
        	}
            $arr['data']	=	$data;
            $arr['all']		=	M('Merchant_store')->where($where)->count();
            $arr['status1'] =	M('Merchant_store')->where(array('status' => 1, 'mer_id' => $this->merchant_session['mer_id']))->count();
            $arr['status2'] =	M('Merchant_store')->where(array('status' => 2, 'mer_id' => $this->merchant_session['mer_id']))->count();
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
    	$where['mer_id']	=	$this->merchant_session['mer_id'];
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
        $mer_id = $this->merchant_session['mer_id'];
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
        $order = $Meal_order->where(array('mer_id' => $this->merchant_session['mer_id'], 'order_id' => $order_id))->find();
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
        $condition_appoint['mer_id'] = $this->merchant_session['mer_id'];
        $appoint_count = $database_appoint->where($condition_appoint)->count();
		$pindex	=	I('pindex',1);
        $appoint_info = $database_appoint->field(true)->where($condition_appoint)->order('`appoint_id` DESC')->page($pindex,10)->select();
        $merchant_info = $database_merchant->field(true)->where('mer_id = ' . $this->merchant_session['mer_id'] . '')->select();
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
	        $v['pics']	=	$pic_list[0]['url'];
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
        $where['mer_id'] = $this->merchant_session['mer_id'];
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
    /*预约订单查找*/
	public function appoint_find(){
		$database_order = D('Appoint_order');
	    $database_user = D('User');
	    $database_appoint = D('Appoint');
	    $database_store = D('Merchant_store');
		$order_id	=	I('order_id');
		$find_type	=	I('find_type');
		$find_value	=	I('find_value');
		$appoint_where['mer_id'] = $this->merchant_session['mer_id'];
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
    # 格式化订单数据
    protected function formatOrderArray($order_info, $user_info, $appoint_info, $store_info){
        if (!empty($user_info)) {
            $user_array = array();
            foreach ($user_info as $val) {
                $user_array[$val['uid']]['phone'] = $val['phone'];
                $user_array[$val['uid']]['nickname'] = $val['nickname'];
            }
        }
        if (!empty($appoint_info)) {
            $appoint_array = array();
            foreach ($appoint_info as $val) {
                $appoint_array[$val['appoint_id']]['appoint_name'] = $val['appoint_name'];
                $appoint_array[$val['appoint_id']]['appoint_type'] = $val['appoint_type'];
            }
        }
        if (!empty($store_info)) {
            $store_array = array();
            foreach ($store_info as $val) {
                $store_array[$val['store_id']]['store_name'] = $val['name'];
                $store_array[$val['store_id']]['store_adress'] = $val['adress'];
            }
        }
        if (!empty($order_info)) {
            foreach ($order_info as &$val) {
                $val['phone'] = $user_array[$val['uid']]['phone'];
                $val['nickname'] = $user_array[$val['uid']]['nickname'];
                $val['appoint_name'] = $appoint_array[$val['appoint_id']]['appoint_name'];
                $val['appoint_type'] = $appoint_array[$val['appoint_id']]['appoint_type'];
                $val['store_name'] = $store_array[$val['store_id']]['store_name'];
                $val['store_adress'] = $store_array[$val['store_id']]['store_adress'];
            }
        }
        return $order_info;
    }
    # 预约工作人员列表
    public function merchant_worker(){
		//工作人员列表
        $Map['status'] = 1;
        $Map['mer_id'] = $this->merchant_session['mer_id'];
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
        $where['mer_id'] = $this->merchant_session['mer_id'];

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
    public function Merchant_store($type=''){
		$store = M('Merchant_store')->field('store_id,name')->where(array('status' => 1, 'mer_id' => $this->merchant_session['mer_id']))->select();
        if ($store == false){
			$this->returnCode('20140015');
        }
        if($type == 1){
			return $store;
        }
        $this->returnCode(0,$store);
    }
	# 店员列表
	public function staff() {
		$database_merchant_store = M('Merchant_store');
        $mer_id = $this->merchant_session['mer_id'];
        $all_store = $database_merchant_store->where(array('mer_id' => $mer_id, 'status' => 1))->field('store_id,mer_id,name,status')->order('sort desc,store_id  ASC')->select();
        if (empty($all_store)) {
            $this->returnCode('20046001');
        }
        $allstore = array();
        foreach ($all_store as $vv) {
            $allstore[$vv['store_id']] = $vv;
        }

		$staffList = M('Merchant_store_staff')->where(array('token' => $mer_id))->order('`id` desc')->select();
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
        $data['time'] = $_SERVER['REQUEST_TIME'];
        if(empty($data['store_id'])){
			$this->returnCode('20140048');
        }
        $data['password'] = md5(I('password'));
        $data['token'] = $this->merchant_session['mer_id'];
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
    # 店员修改
    public function staff_edit() {
//    	$statua		=	I('statua',2);
//        if ($statua == 1) {
            $data['tel'] = I('tel');
            $data['name'] = I('name');
            $password	=	I('password');
            if($password){
				$data['password'] = md5(I('password'));
            }
            $where['token'] = $this->merchant_session['mer_id'];
            $where['id'] = I('staff_id');
            $sql = M('Merchant_store_staff')->where($where)->save($data);
            if ($sql == false) {
                $this->returnCode('20140019');
            } else {
                $this->returnCode(0);
            }
        //} else {
//        	$id	=	I('staff_id');
//            if ($id == false){
//            	$this->returnCode('20140020');
//            }
//            $staff = M('Merchant_store_staff')->where(array('id' => $id, 'token' => $this->merchant_session['mer_id']))->find();
//            if ($staff == false){
//            	$this->returnCode('20140021');
//            }
//            $staff['staff_id']	=	$staff['id'];
//            unset($staff['token'],$staff['last_time'],$staff['time'],$staff['openid'],$staff['id']);
//            $this->returnCode(0,$staff);
//        }
    }
    # 店员删除
    public function staff_dell() {
        $id = I('staff_id');
        if ($id == false)
            $this->returnCode('20140020');
        $staff = M('Merchant_store_staff')->where(array('id' => $id, 'token' => $this->merchant_session['mer_id']))->delete();
        if ($staff == false) {
            $this->returnCode('20140022');
        } else {
            $this->returnCode(0);
        }
    }
	# 打印机设备列表
	public function hardware() {
        $staffList = M('Orderprinter')->where(array('mer_id' => $this->merchant_session['mer_id']))->select();
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
		$status	=	I('status',2);
        if ($status == 1) {
            $data['mcode']		=	I('mcode');
            $data['username']	=	I('username');
            $data['mkey']		=	I('mkey');
            $data['mp']			=	I('mp');
            $data['count']		=	I('count');
            $data['paid']		=	I('paid');
            $data['store_id']	=	I('store_id');
            $data['mer_id']		=	$this->merchant_session['mer_id'];
			$pigcms_id			=	I('pigcms_id');
			if($pigcms_id >0){
            	$sql = M('Orderprinter')->where(array('pigcms_id'=>$pigcms_id,'mer_id'=>$data['mer_id']))->save($data);
			}else{
				$sql = M('Orderprinter')->add($data);
			}
            if ($sql == false) {
            	if($pigcms_id){
					$this->returnCode('20140014');
            	}else{
					$this->returnCode('20140013');
            	}
            } else {
            	$this->returnCode(0,$pigcms_id>0?'修改成功':'添加成功');
            }
        } else {
			$pigcms_id=I('pigcms_id');
			if($pigcms_id>0){
			   $Orderprinter	=	M('Orderprinter')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
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
    public function hardware_dell() {
    	$pigcms_id	=	I('pigcms_id');
        if ($pigcms_id == false)
            $this->error_tips('非法操作');
        $staff = M('Orderprinter')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->delete();
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
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'goods', 1, $param);
			if ($image['error']) {
				$this->returnCode('20140052');
			} else {
				$arr	=	array(
					'url'	=>	$this->config['site_url'].$image['url']['file'],
					'title'	=>	$image['title']['file'],
				);
				$this->returnCode(0,$arr);
			}
		} else {
			$this->returnCode('20140051');
		}
	}

	//商家余额接口
	public function merchant_money_info(){
		$mer_id  = $this->merchant_session['mer_id'];

		$type = !empty($_POST['type'])?$_POST['type']:'group';
		//$arr['type'] = $this->get_alias_name();
		$now_mer = M('Merchant')->where(array('mer_id'=>$mer_id))->find();
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
			$arr[] = array('type' => 'store', 'name' => '到店');
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
			$store_list=array();
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
		$mer_id = intval($this->merchant_session['mer_id']);
		$store_id = $_POST['store_id'];
		$day  = $_POST['day'];
		$period = false;
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
			$condition_merchant_request['o.store_id'] = $_POST['store_id'];
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
				$request_list = M('Merchant_money_list l')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join(C('DB_PREFIX').$type.'_order o ON o.order_id = l.order_id ')->where($condition_merchant_request)->select();
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
				$request_list = M('Merchant_money_list l')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join(C('DB_PREFIX').$type.'_order o ON o.order_id = l.order_id ')->where($condition_merchant_request)->select();
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
		$mer_id = intval($this->merchant_session['mer_id']);
		if(!empty($_POST['store_id'])){
			$condition['store_id'] = $_POST['store_id'];
		}

		if($_POST['type']!='all'&&!empty($_POST['type'])){
			$condition['type'] = $_POST['type'];

		}

		if(isset($_POST['period'])&&!empty($_POST['period'])){
			$period = explode('-',$_POST['period']);
			$_POST['begin_time'] = $period[0];
			$_POST['end_time'] = $period[1];

			if ($_POST['begin_time']>$_POST['end_time']) {
				$this->returnCode('20140055');
			}
			$period = $_POST['begin_time']==$_POST['end_time']?array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['begin_time']." 23:59:59")):array(strtotime($_POST['begin_time']),strtotime($_POST['end_time']));
			$time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition['_string']=$time_condition;
           // $where['_string'] = " use_time BETWEEN ".$period[0].' AND '.$period[1];
		}
		$res = D('Merchant_money_list')->get_income_list($mer_id,0,$condition);
		$alias_name = $this->get_alias_c_name2();
		$income_list = array();
		foreach ( $res['income_list'] as $inc) {
			$arr=array();
			$arr['id']=$inc['id'];
			if($inc['store_id']>0){
				$arr['store_name'] = $inc['store_name'];
			}else{
				$arr['store_name'] = '';
			}
			$arr['type'] = $inc['type'];
			$arr['type_name'] = $alias_name[$inc['type']];
			$arr['desc'] = str_replace('</br>','',$inc['desc']);
			$arr['money'] = strval(pow(-1,($inc['income']+1))*$inc['money']);
			$income_list[]=$arr;
		}
        $condition['_string']  .= "type <> 'withdraw'";
        $arr = array('income_list'=>$income_list,'page_num'=>$res['page_num']);
        $condition['mer_id'] = $mer_id;
        $today_money  = M('Merchant_money_list')->where($condition)->sum('money');
        $arr['all_money']  = empty($today_money)?0:$today_money;
        $arr['all_count'] =  M('Merchant_money_list')->where($condition)->count();

        $today_zero = strtotime(date('Y-m-d'));
        $where['_string'] = 'use_time>'.$today_zero.' AND use_time <='.($today_zero+86400);
        $today_money  = M('Merchant_money_list')->where($condition)->sum('money');
        $arr['today_money']  = empty($today_money)?0:$today_money;
        $arr['today_count'] =  M('Merchant_money_list')->where($condition)->count();

		$this->returnCode(0,$arr);

	}

	//商家提现记录
	public function withdraw_list(){
		$mer_id = intval($this->merchant_session['mer_id']);
		$_GET['page'] = $_POST['page'];
		$res = D('Merchant_money_list')->get_withdraw_list($mer_id);

		$withdraw=array();
		foreach($res['withdraw_list'] as $v){
			$arr = array();
			$arr['id'] = $v['id'];
			$arr['time'] = date('Y/m/d H:i:s',$v['withdraw_time']);
			$arr['money'] = strval($v['money']/100);
			if($v['status']==0){
				$arr['status'] = '待审核';
			}elseif($v['status']==1){
				$arr['status'] = '已通过';
			}elseif($v['status']==2){
				$arr['status'] = '被驳回';
			}
			$arr['remark'] = $v['remark'];
			$withdraw[]=$arr;
		}
		$return['withdraw']=$withdraw;
		$return['page_num']=$res['page_num'];
		$this->returnCode(0,$return);
	}

	//提现记录详情
	public function withdraw_info(){
		$withdraw = M('Merchant_withdraw')->where(array('id'=>$_POST['id']))->find();
		$withdraw['name'] = $this->merchant_session['name'];
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
		$mer_id = $this->merchant_session['mer_id'];
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
				$income[] = array('name'=>'提现商家','value'=>$this->merchant_session['name']);
				$income[] = array('name'=>'夺宝用户','value'=>$order['nickname']);
				$income[] = array('name'=>'夺宝用户','value'=>$order['phone']);
				break;
			case 'withdraw':
				$order = M('Merchant_withdraw')->where(array('id'=>$res['order_id']))->find();
				$income[] = array('name'=>'提现人','value'=>$order['name']);
				$income[] = array('name'=>'提现商家','value'=>$this->merchant_session['name']);
			break;
		}

		$this->returnCode(0,array('income_info'=>$income));

	}
	//提现

    public function withdraw_method(){
        $arr=array(
            '0'=>'银行卡',
            '1'=>'支付宝',
            '2'=>'微信'
        );
        $this->returnCode(0,$arr);
    }
	public function withdraw(){
		if($this->config['company_pay_open']=='0') {
			$this->returnCode('20140059');
		}
		$mer_id = intval($this->merchant_session['mer_id']);
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
				$this->returnCode('20140062');
			}

            if(isset($_POST['withdraw_type']) && $_POST['withdraw_type']!=2){
                $money = $_POST['money'];
                if(empty($_POST['name'])){
                    $this->returnCode('20046031');
                }
                if($money<$this->config['company_least_money']){
                    $this->returnCode(0,'','不能低于最低提款额 '.$this->config['company_least_money'].' 元!');
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
                $data_companypay['money'] = bcmul($money*((100-$this->config['company_pay_mer_percent'])/100),100);
                $data_companypay['old_money'] = $money*100;
                $data_companypay['desc'] = "商户提现对账订单|商户ID ".$now_merchant['mer_id']." |转账 ".$money." 元" ;
                if($this->config['company_pay_mer_percent']>0){
                    $data_companypay['desc'] .= '|手续费 '.(($data_companypay['old_money'] -  $data_companypay['money'])/100) .' 比例 '.$this->config['company_pay_mer_percent'].'%';
                }
                $data_companypay['status'] = 0;
                $data_companypay['add_time'] = time();


                $date_mer['mer_id']=$mer_id;
                $date_mer['name']=$_POST['name'];
                $date_mer['money']=   $data_companypay['money'] ;
                $date_mer['remark']=  (empty($remark)?"":$remark). $data_companypay['desc'];
                $date_mer['withdraw_time'] = time();
                $date_mer['status'] = 4;
                $date_mer['from_type'] = 1;
                $res =M('Merchant_withdraw')->add($date_mer);
                if(!$res){
                     $this->returnCode('20140063');die;
                }
                $data_companypay['withdraw_id'] = $res;
                $result = D('Merchant_money_list')->use_money($mer_id,$money,'withdraw',  $data_companypay['desc'] ,$data_companypay['withdraw_id'],$this->config['company_pay_mer_percent'],(($data_companypay['old_money'] -  $data_companypay['money'])/100));

                M('Withdraw_list')->add($data_companypay);



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

    }
    public function card_new_list(){
        if (!empty($_POST['keyword'])) {
            if ($_POST['searchtype'] == 'phone') {
                $condition_user['u.phone'] = array('like', '%' . $_POST['keyword'] . '%');
            }
            if ($_POST['searchtype'] == 'card_id') {
                $condition_['c.id'] = array('like', '%' . $_GET['keyword'] . '%');
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
        $condition_user['c.mer_id'] = $this->merchant_session['mer_id'];
        $card_count = M('Card_userlist')->join('as c left join '.C('DB_PREFIX').'user as u ON u.uid = c.uid')->where($condition_user)->count();

        $page	=	I('pindex',1);
        $card_user_list = M('Card_userlist')->field('c.*,u.*,c.add_time as card_add_time,c.status as card_status')->join('as c left join '.C('DB_PREFIX').'user as u ON u.uid = c.uid')->where($condition_user)->order('c.id DESC')->limit($page,10)->select();

        $arr['data']	=	isset($card_user_list)?$card_user_list:array();
        $arr['all']		=	$card_count;
        $arr['page'] 	=	ceil($arr['all']/10);
        $this->returnCode(0,$arr);
    }

    // 快店详情
    public function store()
    {
        $store_id = I('store_id');
        if (empty($store_id)) {
            //$this->returnCode('20140029');
        }
        $data = M('Merchant_store')->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (! empty($data)) {
//             $data['office_time'] = unserialize($data['office_time']);
            if (! empty($data['pic_info'])) {
                $store_image_class = new store_image();
                $tmp_pic_arr = explode(';', $data['pic_info']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $data['pic'][$key]['title'] = $value;
                    $data['pic'][$key]['url'] = $store_image_class->get_image_by_path($value);
                }
            }
        }
        $keywords = D('Keywords')->where(array('third_type' => 'Merchant_store', 'third_id' => $data['store_id']))->select();
        $str = "";
        foreach ($keywords as $key) {
            $str .= $key['keyword'] . " ";
        }
        
        
        $catList = D('Merchant_category')->lists();
        $arr = array(
            'store_id' => $data['store_id'], // 商铺ID
            'name' => $data['name'], // 商户ID
            'mer_id' => $data['mer_id'], // 商户ID
            'ismain' => $data['ismain'], // 是否是主点 1主店 0不是主店
            'phone' => $data['phone'], // 手机
            'kefu_phone' => $data['kefu_phone'], // 客服电话
//             'weixin' => $data['weixin'], // 微信
//             'qq' => $data['qq'], // QQ
            'keywords' => $str, // 关键词
            'permoney' => $data['permoney'], // 人均消费
            'feature' => $data['feature'], // 店铺特色
            'cat_id' => $data['cat_id'], // 省
            'cat_fid' => $data['cat_fid'], // 市
            'province_id' => $data['province_id'], // 省
            'city_id' => $data['city_id'], // 市
            'area_id' => $data['area_id'], // 区
            'circle_id' => $data['circle_id'], // 商圈
            'adress' => $data['adress'], // 地址
            'trafficroute' => $data['trafficroute'], // 交通路线
            'sort' => $data['sort'], // 排序
            'have_meal' => $data['have_meal'], // 餐饮是否开启 0关闭 1开启
            'have_group' => $data['have_group'], // 团购是否开启 0关闭 1开启
            'have_shop' => $data['have_shop'], // 快店是否开启 0关闭 1开启
            'open_1' => $data['open_1'] ? substr($data['open_1'], 0, 5) : '00:00', // 打开时间1
            'open_2' => $data['open_2'] ? substr($data['open_2'], 0, 5) : '00:00', // 打开时间2
            'open_3' => $data['open_3'] ? substr($data['open_3'], 0, 5) : '00:00', // 打开时间3
            'close_1' => $data['close_1'] ? substr($data['close_1'], 0, 5) : '00:00', // 结束时间1
            'close_2' => $data['close_2'] ? substr($data['close_2'], 0, 5) : '00:00', // 结束时间2
            'close_3' => $data['close_3'] ? substr($data['close_3'], 0, 5) : '00:00', // 结束时间3
            'lat' => $data['lat'], // 经
            'long' => $data['long'], // 纬
            'txt_info' => $data['txt_info'], // 简介
            'pic' => isset($data['pic']) ? $data['pic'] : array(),
//             'discount_type' => $data['discount_type'],
            'discount_txt' => unserialize($data['discount_txt']),
            'vip_discount_type'=>$data['vip_discount_type'],
            'catList' => $catList
        );
        
        $areaList = D('Area')->field(true)->where(array('area_id' => array('in', array($data['province_id'], $data['city_id'], $data['area_id'], $data['circle_id']))))->select();
        foreach ($areaList as $al) {
            if ($al['area_id'] == $arr['province_id']) {
                $arr['province_name'] = $al['area_name'];
            } elseif ($al['area_id'] == $arr['city_id']) {
                $arr['city_name'] = $al['area_name'];
            } elseif ($al['area_id'] == $arr['area_id']) {
                $arr['area_name'] = $al['area_name'];
            } elseif ($al['area_id'] == $arr['circle_id']) {
                $arr['circle_name'] = $al['area_name'];
            }
        }
        foreach ($catList as $cl) {
            if ($cl['value'] == $arr['cat_fid']) {
                $arr['cat_fname'] = $cl['text'];
                foreach ($cl['children'] as $ch) {
                    if ($ch['value'] == $arr['cat_id']) {
                        $arr['cat_name'] = $ch['text'];
                        break;
                    }
                }
                break;
            }
        }
        $this->returnCode(0, $arr);
    }
    
    public function storeModify()
    {
        
        $database_merchant_store = D('Merchant_store');
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;

        if(empty($_POST['name'])){
            $this->returnCode(1, null, '店铺名称必填！');
        }
        if(empty($_POST['phone'])){
            $this->returnCode(1, null, '联系电话必填！');
        }
        if(empty($_POST['long_lat'])){
            $this->returnCode(1, null, '店铺经纬度必填！');
        }
        if(empty($_POST['adress'])){
            $this->returnCode(1, null, '店铺地址必填！');
        }
//         if(empty($_POST['permoney'])){
//             $this->returnCode(1, null, '人均消费必填！');
//         }
//         if(empty($_POST['feature'])){
//             $this->returnCode(1, null, '店铺特色必填！');
//         }
            // 			if(empty($_POST['trafficroute'])){
            // 				$this->returnCode(1, null, '交通路线必填！');
            // 			}
        if(empty($_POST['pic'])){
            $this->returnCode(1, null, '请至少上传一张图片');
        }
        $_POST['pic_info'] = implode(';',$_POST['pic']);
        
        if(empty($_POST['txt_info'])){
            $this->returnCode(1, null, '请输入店铺描述信息');
        }
        //判断关键词
        $keywords = trim($_POST['keywords']);
        if(!empty($keywords)){
            $tmp_key_arr = explode(' ',$keywords);
            $key_arr = array();
            foreach($tmp_key_arr as $value){
                if(!empty($value)){
                    array_push($key_arr,$value);
                }
            }
            if(count($key_arr)>5){
                $this->returnCode(1, null, '关键词最多5个。');
            }
        }
            

//         $_POST['office_time'] = '';
        
//         $_POST['sort'] = intval($_POST['sort']);
        $long_lat = explode(',', $_POST['long_lat']);
        $_POST['long'] = $long_lat[0];
        $_POST['lat'] = $long_lat[1];
        $_POST['open_1'] = isset($_POST['open_1']) && $_POST['open_1'] ? $_POST['open_1'] : '00:00';
        $_POST['close_1'] = isset($_POST['close_1']) && $_POST['close_1'] ? $_POST['close_1'] : '00:00';
        $_POST['open_2'] = isset($_POST['open_2']) && $_POST['open_2'] ? $_POST['open_2'] : '00:00';
        $_POST['close_2'] = isset($_POST['close_2']) && $_POST['close_2'] ? $_POST['close_2'] : '00:00';
        $_POST['open_3'] = isset($_POST['open_3']) && $_POST['open_3'] ? $_POST['open_3'] : '00:00';
        $_POST['close_3'] = isset($_POST['close_3']) && $_POST['close_3'] ? $_POST['close_3'] : '00:00';
//         $area = D('Area')->cityMatching($long_lat[1], $long_lat[0]);
//         $_POST['province_id'] = isset($area['area_info']['province_id']) ? intval($area['area_info']['province_id']) : 0;
//         $_POST['city_id'] = isset($area['area_info']['city_id']) ? intval($area['area_info']['city_id']) : 0;
//         $_POST['area_id'] = isset($area['area_info']['area_id']) ? intval($area['area_info']['area_id']) : 0;
        
        if (empty($_POST['province_id']) || empty($_POST['city_id']) || empty($_POST['area_id'])) {
            $this->returnCode(1, null, '省市区必选。');
        }
        $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
        $_POST['circle_id'] = isset($_POST['circle_id']) ? intval($_POST['circle_id']) : 0;
        $_POST['discount_txt'] = '';
        $discount_type = isset($_POST['discount_type']) ? intval($_POST['discount_type']) : 0;
        if ($discount_type == 1) {
            $discount_percent = isset($_POST['discount_percent']) ? (intval($_POST['discount_percent'] * 10) / 10) : 0;
            if ($discount_percent > 0 && $discount_percent < 10) {
                if($this->config['open_extra_price']==1){
                    $_POST['discount_txt'] = serialize(array('discount_type' => $discount_type, 'discount_percent' => $discount_percent,'discount_limit'=>$_POST['discount_limit'],'discount_limit_percent'=>$_POST['discount_limit_percent']));
                }else{
                    $_POST['discount_txt'] = serialize(array('discount_type' => $discount_type, 'discount_percent' => $discount_percent));
                }
            } elseif ($discount_percent < 0 || $discount_percent > 10) {
                $this->returnCode(1, null, '折扣率必须在0~10之间的数。');
            }
        } elseif ($discount_type == 2) {
            $condition_price = isset($_POST['condition_price']) ? (intval($_POST['condition_price'] * 100) / 100) : 0;
            $minus_price = isset($_POST['minus_price']) ? (intval($_POST['minus_price'] * 100) / 100) : 0;
            if ($condition_price < 0 || $minus_price < 0 || $minus_price > $condition_price) {
                $this->returnCode(1, null, '满减的填写不正确，必须都是大于0且满足的金额要大于减免金额。');
            }
            if ($condition_price > 0 && $minus_price > 0 && $minus_price <= $condition_price) {
                $_POST['discount_txt'] = serialize(array('discount_type' => $discount_type, 'condition_price' => $condition_price, 'minus_price' => $minus_price));
            }
        }
        
        $condition_merchant_store['store_id'] = $_POST['store_id'];
        $condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
        $_POST['store_type'] = isset($_POST['store_type']) ? intval($_POST['store_type']) : 1;
        $_POST['vip_discount_type'] = isset($_POST['vip_discount_type']) ? intval($_POST['vip_discount_type']) : 0;
        unset($_POST['store_id']);
        $ismain = intval($_POST['ismain']);
        if($ismain==1){
            $database_merchant_store->where(array('mer_id'=>$this->merchant_session['mer_id']))->save(array('ismain'=>0));
        }
        if ($store_id) {
            if ($store = $database_merchant_store->where($condition_merchant_store)->find()) {
                if($database_merchant_store->where($condition_merchant_store)->data($_POST)->save()){
                    $data_keywords['third_id'] = $condition_merchant_store['store_id'];
                    $data_keywords['third_type'] = 'Merchant_store';
                    $database_keywords = D('Keywords');
                    $database_keywords->where($data_keywords)->delete();
                    //判断关键词
                    if(!empty($key_arr)){
                        foreach($key_arr as $value){
                            $data_keywords['keyword'] = $value;
                            $database_keywords->data($data_keywords)->add();
                        }
                    }
                    
                    $this->returnCode(0);
                } else {
                    $this->returnCode(1, null, '保存失败！！您是不是没做过修改？请重试~');
                }
            } else {
                $this->returnCode(1, null, '数据不合法');
            }
        } else {
            $_POST['mer_id'] = $this->merchant_session['mer_id'];
            if($store_id = $database_merchant_store->add($_POST)){
                $data_keywords['third_id'] = $store_id;
                $data_keywords['third_type'] = 'Merchant_store';
                $database_keywords = D('Keywords');
                $database_keywords->where($data_keywords)->delete();
                //判断关键词
                if(!empty($key_arr)){
                    foreach($key_arr as $value){
                        $data_keywords['keyword'] = $value;
                        $database_keywords->data($data_keywords)->add();
                    }
                }
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '保存失败！！您是不是没做过修改？请重试~');
            }
        }
    }
    public function cityList()
    {
        $items = M('Area')->field('`area_id`,`area_pid`,`area_name`')->where(array('is_open'=>'1','area_type'=>array('elt','4')))->order('`area_type` ASC')->select();
        
        $tmpMap = array();
        foreach ($items as $item) {
            $tmpMap[$item['area_id']] = $item;
        }
        $list = array();
        foreach ($items as $item) {
            if (isset($tmpMap[$item['area_id']])) {
                $tmpMap[$item['area_pid']]['children'][$item['area_id']] = &$tmpMap[$item['area_id']];
            } else {
                $list[$item['area_id']] = &$tmpMap[$item['area_id']];
            }
        }
        $result = array();
        foreach ($list as $l) {
            if ($l['area_pid']) {
                continue;
            }
            $value = array();
            $value['value'] = $l['area_id'];
            $value['text'] = $l['area_name'];
            $value['children'] = $this->children($l['children']);
            $result[] = $value;
        }
        $this->returnCode(0, $result);
    }
    private function children($list)
    {
        if (empty($list)) {
            return $list;
        }
        $result = array();
        foreach ($list as $l) {
            $value = array();
            $value['value'] = $l['area_id'];
            $value['text'] = $l['area_name'];
            $value['children'] = $this->children($l['children']);
            $result[] = $value;
        }
        return $result;
    }
    
    public function ajaxCircle()
    {
        $condition_area = array();
        $condition_area['area_pid'] = intval($_POST['id']);
        $condition_area['is_open'] = 1;
        $result = D('Area')->field('`area_id`,`area_name`')->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
        $this->returnCode(0, $result);
    }
    
    public function uploadStoreImage()
    {
        if ($_FILES['imgFile']['error'] != 4) {
            $image = D('Image')->handle($this->merchant_session['mer_id'], 'store', 1);
            if ($image['error']) {
                $this->returnCode('20140052');
            } else {
                $title = $image['title']['imgFile'];
                $store_image_class = new store_image();
                $url = $store_image_class->get_image_by_path($title);
                $arr = array(
                    'url' => $url,
                    'title' => $title,
                );
                exit(json_encode(array('errorCode' => 0,'errorMsg'=>'success', 'result' => $arr)));
                $this->returnCode(0, $arr);
            }
        } else {
            $this->returnCode('20140051');
        }
    }
    public function uploadShopBackground()
    {
        if ($_FILES['file']['error'] != 4) {
            $param = array('size' => 10);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbMaxWidth'] = 640;
            $param['thumbMaxHeight'] = 420;
            $param['thumbRemoveOrigin'] = false;
            $image = D('Image')->handle($this->merchant_session['mer_id'], 'background', 1, $param);
            if ($image['error']) {
                $this->returnCode('20140052');
            } else {
                $title = $image['title']['file'];
                $image_tmp = explode(',', $title);
                $url = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
                exit(json_encode(array('errorCode' => 0,'errorMsg'=>'success', 'result' => array('url' => $url, 'title' => $title))));
            }
        } else {
            $this->returnCode('20140051');
            exit(json_encode(array('errorCode' => 1,'errorMsg' =>'没有选择图片')));
        }
    }
    
    public function shopList()
    {
        $mer_id = $this->merchant_session['mer_id'];
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['mer_id'] = $mer_id;
        $condition_merchant_store['have_shop'] = '1';
        $condition_merchant_store['status'] = '1';
        $store_id = I('store_id', '');
        if($store_id){
            $this->merchant_session['store_id'] = $store_id;
        }
        
        $db_arr = array(C('DB_PREFIX') . 'area' => 'a', C('DB_PREFIX') . 'merchant_store' => 's');

        
        $sql = "SELECT `s`.`store_id`, `s`.`mer_id`, `s`.`name`, `s`.`adress`, `s`.`phone`, `s`.`sort`, `s`.`meituan_token`, `s`.`eleme_shopId`, `ss`.`store_theme`, `ss`.`store_id` AS sid FROM ". C('DB_PREFIX') . "merchant_store AS s LEFT JOIN  ". C('DB_PREFIX') . "merchant_store_shop AS ss ON `s`.`store_id`=`ss`.`store_id`";
        $sql .= " WHERE `s`.`mer_id`={$mer_id} AND `s`.`status`='1' AND `s`.`have_shop`='1'";
        if ($this->merchant_session['store_id']) {
            $sql .= " AND `s`.`store_id`={$this->merchant_session['store_id']}";
        }
        $sql .= " ORDER BY `s`.`sort` DESC,`s`.`store_id` ASC";

        $store_list = D()->query($sql);
        
//         import('@.ORG.Meituan');
//         $eleme = new Meituan();
        $arr = array();
        foreach ($store_list as $store) {
            $temp = array();
            $temp['store_id'] = $store['store_id'];
            $temp['name'] = $store['name'];
            $temp['phone'] = $store['phone'];
            $temp['sid'] = intval($store['sid']);
//             $temp['meituan_url'] = $eleme->getAuthUrl($store['store_id'], $store['name']);
//             $temp['meituan_cancel_url'] = $eleme->cancelBind($store['meituan_token']);
            $arr[] = $temp;
        }

        $this->returnCode(0, $arr);
    }
    
    private function childrenCategory($list, $relation_array)
    {
        if (empty($list)) {
            return $list;
        }
        $result = array();
        
        foreach ($list as $crow) {
            $temp = array();
            $temp['cat_fid'] = $crow['cat_fid'];
            $temp['cat_id'] = $crow['cat_id'];
            $temp['cat_name'] = $crow['cat_name'];
            $temp['is_select'] = 0;
            if (in_array($crow['cat_id'], $relation_array)) {
                $temp['is_select'] = 1;
            }
            $temp['son_list'] = $this->childrenCategory($crow['son_list'], $relation_array);
            $result[] = $temp;
        }
        return $result;
    }
    
    /**
     *  店铺信息修改
     */
    public function shopEdit()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['store_id'] = $store_id;
        $condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
        $now_store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺不存在！');
        }
        
        
        $database_merchant_store_shop = D('Merchant_store_shop');
        $condition_merchant_store_shop['store_id'] = $now_store['store_id'];
        $store_shop = $database_merchant_store_shop->field(true)->where($condition_merchant_store_shop)->find();
        if ($store_shop && $store_shop['delivery_range_polygon']) {
            $store_shop['delivery_range_polygon'] = substr($store_shop['delivery_range_polygon'], 9, strlen($store_shop['delivery_range_polygon']) - 11);
            $lngLatData = explode(',', $store_shop['delivery_range_polygon']);
            array_pop($lngLatData);
            $lngLats = array();
            foreach ($lngLatData as $lnglat) {
                $lng_lat = explode(' ', $lnglat);
                $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
            }
            $store_shop['delivery_range_polygon'] = json_encode(array($lngLats));
        }
        if ($store_shop) {
            if (!empty($store_shop['background'])) {
                $image_tmp = explode(',', $store_shop['background']);
                $store_shop['background_image'] = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
            }
            $store_shop['delivertime_start'] = substr($store_shop['delivertime_start'], 0, 5);
            $store_shop['delivertime_stop'] = substr($store_shop['delivertime_stop'], 0, 5);
            $store_shop['delivertime_start2'] = substr($store_shop['delivertime_start2'], 0, 5);
            $store_shop['delivertime_stop2'] = substr($store_shop['delivertime_stop2'], 0, 5);
        }
        //所有分类
        $database_shop_category = D('Shop_category');
        $category_list = $database_shop_category->lists();
        
        //此店铺有的分类
        $database_shop_category_relation = D('Shop_category_relation');
        $condition_shop_category_relation['store_id'] = $now_store['store_id'];
        $relation_list = $database_shop_category_relation->field(true)->where($condition_shop_category_relation)->select();
        $relation_array = array();
        foreach ($relation_list as $key => $value) {
            array_push($relation_array, $value['cat_id']);
        }
        $categoryList = array();
        foreach ($category_list as $crow) {
            $temp = array();
            $temp['cat_fid'] = $crow['cat_fid'];
            $temp['cat_id'] = $crow['cat_id'];
            $temp['cat_name'] = $crow['cat_name'];
            $temp['is_select'] = 0;
            if (in_array($crow['cat_id'], $relation_array)) {
                $temp['is_select'] = 1;
            }
            $temp['son_list'] = $this->childrenCategory($crow['son_list'], $relation_array);
            $categoryList[] = $temp;
        }
        $store_shop['store_discount'] *= 0.1;
        
        $leveloff = !empty($store_shop['leveloff']) ? unserialize($store_shop['leveloff']) :false;
        $tmparr = M('User_level')->order('id ASC')->select();
        $levelarr = array();
        if ($tmparr && $this->config['level_onoff']) {
            foreach ($tmparr as $vv) {
                if (!empty($leveloff) && isset($leveloff[$vv['level']])) {
                    $vv['vv'] = $leveloff[$vv['level']]['vv'];
                    $vv['type'] = $leveloff[$vv['level']]['type'];
                } else {
                    $vv['vv'] = '';
                    $vv['type'] = '';
                }
                $levelarr[$vv['level']] = $vv;
            }
        }
        
        if ($this->config['is_open_system_deliver']) {
            $deliver_types = array(
                array(
                    'id' => 0,
                    'name' => $this->config['deliver_name']
                ),
                array(
                    'id' => 1,
                    'name' => '商家配送'
                ),
                array(
                    'id' => 2,
                    'name' => '客户自提'
                ),
                array(
                    'id' => 3,
                    'name' => $this->config['deliver_name'] . '或自提'
                ),
                array(
                    'id' => 4,
                    'name' => '商家配送或自提'
                ),
                array(
                    'id' => 5,
                    'name' => '快递配送'
                )
            );
        } else {
            $deliver_types = array(
                array(
                    'id' => 1,
                    'name' => '商家配送'
                ),
                array(
                    'id' => 2,
                    'name' => '客户自提'
                ),
                array(
                    'id' => 4,
                    'name' => '商家配送或自提'
                ),
                array(
                    'id' => 5,
                    'name' => '快递配送'
                )
            );
            $store_shop['deliver_type'] = ($store_shop['deliver_type'] == 0 || $store_shop['deliver_type'] == 3) ? 1 : $store_shop['deliver_type'];
        }
        $now_store = array_merge($now_store, $store_shop);
        $now_store['levelarr'] = $levelarr;
        $now_store['deliver_types'] = $deliver_types;
        $now_store['relation_array'] = $relation_array;
        $now_store['category_list'] = $categoryList;
        
        $this->returnCode(0, $now_store);
    }
    
    public function shopSave()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['store_id'] = $store_id;
        $condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
        $now_store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺不存在！');
        }
        
        if ($_POST['delivery_range_type'] == 1 && ($_POST['deliver_type'] == 1 || $_POST['deliver_type'] == 4)) {
            if ($_POST['delivery_range_polygon']) {
                $latLngArray = explode('|', $_POST['delivery_range_polygon']);
                if (count($latLngArray) < 3) {
                    $this->returnCode(1, null, '请绘制一个合理的服务范围！');
                } else {
                    $latLngData = array();
                    foreach ($latLngArray as $row) {
                        $latLng = explode('-', $row);
                        $latLngData[] = $latLng[1] . ' ' . $latLng[0];
                    }
                    $latLngData[] = $latLngData[0];
                    $_POST['delivery_range_polygon'] = 'POLYGON((' . implode(',', $latLngData) . '))';
                }
            } else {
                $this->returnCode(1, null, '请绘制您的服务范围！');
            }
            unset($_POST['delivery_radius']);
        } else {
            unset($_POST['delivery_range_polygon']);
        }
        $_POST['store_id'] = $now_store['store_id'];
        if(substr($_POST['store_notice'], -1) == ' '){
            $_POST['store_notice'] = trim($_POST['store_notice']);
        }else{
            $_POST['store_notice'] = trim($_POST['store_notice']);
        }
        if(empty($_POST['store_category'])){
            $this->returnCode(1, null, '请至少选一个分类！');
        }
        $cat_ids = array();
        foreach ($_POST['store_category'] as $cat_a) {
            $a = explode('-', $cat_a);
            $cat_ids[] = array('cat_fid' => $a[0], 'cat_id' => $a[1]);
        }
        
        $leveloff = isset($_POST['leveloff']) ? $_POST['leveloff'] :false;
        unset($_POST['leveloff']);
        
        $newleveloff = array();
        if (!empty($leveloff)) {
            foreach ($leveloff as $kk => $vv) {
                $vv['type'] = intval($vv['type']);
                $vv['vv'] = intval($vv['vv']);
                if (($vv['type'] > 0) && ($vv['vv'] > 0)) {
                    $newleveloff[$vv['level']] = $vv;
                }
            }
        }
        
        $_POST['store_discount'] = intval(floatval($_POST['store_discount']) * 10);
        $_POST['store_discount'] = ($_POST['store_discount'] > 100 || $_POST['store_discount'] < 0) ? 0 : $_POST['store_discount'];
        
        $_POST['discount_type'] = isset($_POST['discount_type']) ? intval($_POST['discount_type']) : 0;
        $_POST['reduce_stock_type'] = isset($_POST['reduce_stock_type']) ? intval($_POST['reduce_stock_type']) : 0;
        $_POST['rollback_time'] = isset($_POST['rollback_time']) ? intval($_POST['rollback_time']) : 20;
        $_POST['rollback_time'] = max(10, $_POST['rollback_time']);
        $_POST['work_time'] = isset($_POST['work_time']) ? intval($_POST['work_time']) : 20;
        
        $_POST['leveloff'] = !empty($newleveloff) ? serialize($newleveloff) : '';
        if($leveloff === false) unset($_POST['leveloff']);
        $database_merchant_store_shop = D('Merchant_store_shop');
        $deliver_type = isset($_POST['deliver_type']) ? intval($_POST['deliver_type']) : 0;
        //unset($_POST['deliver_type']);
        
        $store_shop = $database_merchant_store_shop->field(true)->where(array('store_id' => $store_id))->find();
        if ($store_shop) {
            if (in_array($store_shop['deliver_type'], array(0, 3)) && in_array($deliver_type, array(0, 3))) {//平台=>平台 配送距离不修改
                unset($_POST['delivery_radius']);
                $_POST['s_send_time'] = $store_shop['s_send_time'] ? $store_shop['s_send_time'] : $this->config['deliver_send_time'];
                $_POST['send_time'] = $_POST['s_send_time'];
            }
            if (in_array($store_shop['deliver_type'], array(1, 2, 4)) && in_array($deliver_type, array(0, 3))) {//商家=>平台 配送距离设置为0
                $_POST['delivery_radius'] = 0;
            }
            if (empty($store_shop['create_time'])) $_POST['create_time'] = time();
            $operat_shop = $database_merchant_store_shop->data($_POST)->save();
        } else {
            if ($deliver_type == 0 || $deliver_type == 3) {
                $_POST['delivery_radius'] = 0;
                $_POST['send_time'] = $_POST['s_send_time'] = $this->config['deliver_send_time'];
            } else {
                $_POST['s_send_time'] = $this->config['deliver_send_time'];
            }
            $_POST['create_time'] = time();
            $operat_shop = $database_merchant_store_shop->add($_POST);
        }
        
        //处理配送
        $deliver_store = D('Deliver_store')->field(true)->where(array('store_id' => $now_store['store_id']))->find();
        if ($deliver_type != 2) {
            $t_type = ($deliver_type == 0 || $deliver_type == 3) ? 0 : 1;
            $deliver['store_id'] = $now_store['store_id'];
            $deliver['mer_id'] = $now_store['mer_id'];
            $deliver['site'] = $now_store['adress'];
            $deliver['type'] = $t_type;
            $deliver['range'] = $_POST['delivery_radius'];
            if ($deliver_store) {
                D('Deliver_store')->where(array('pigcms_id' => $deliver_store['pigcms_id']))->save($deliver);
            } else {
                D('Deliver_store')->data($deliver)->add();
            }
        } elseif ($deliver_type == 2 && $deliver_store) {
            D('Deliver_store')->field(true)->where(array('store_id' => $now_store['store_id']))->save(array('type' => 2));
        }
        
        $database_shop_category_relation = D('Shop_category_relation');
        $condition_shop_category_relation['store_id'] = $now_store['store_id'];
        $database_shop_category_relation->where($condition_shop_category_relation)->delete();
        foreach($cat_ids as $key => $value){
            $data_shop_category_relation[$key]['cat_id'] = $value['cat_id'];
            $data_shop_category_relation[$key]['cat_fid'] = $value['cat_fid'];
            $data_shop_category_relation[$key]['store_id'] = $now_store['store_id'];
        }
        $database_shop_category_relation->addAll($data_shop_category_relation);
        
        $this->returnCode(0);
    }
    private function hasGoods($sort_id)
    {
        $count = D('Shop_goods')->where(array('sort_id' => $sort_id))->count();
        return $count;
    }
    public function getGoodsSort()
    {
        
        $store_id = I('store_id');
        $sort_id = I('sort_id');
        $shop = D('Merchant_store_shop')->field('is_mult_class')->where(array('store_id' => $store_id))->find();
        if (empty($shop)) {
            $this->returnCode('20140029');
        }
        if ($shop['is_mult_class'] == 0) {
            $this->returnCode(0, array('list' => array()));
        }
        $now_sort = D('Shop_goods_sort')->field(true)->where(array('store_id' => $store_id, 'sort_id' => $sort_id))->find();
        if ($now_sort['level'] == 1) {
            $this->returnCode(0, array('list' => array()));
        }
        $arr = array();
        if ($store_id) {
            $database_goods_sort = D('Shop_goods_sort');
            $list = D('Shop_goods_sort')->lists($store_id, false);
            $result = array();
            foreach ($list as $l) {
                if ($sort_id == $l['sort_id']) {
                    continue;
                } elseif ($this->hasGoods($l['sort_id'])) {
                    continue;
                } elseif (empty($result)) {
                    $result[] = array('value' => '0', 'text' => '不选择');
                }
                $temp = array();
                $temp['value'] = $l['sort_id'];
                $temp['text'] = $l['sort_name'];
                if ($l['son_list'] && (empty($now_sort) || $now_sort['level'] == 3)) {
                    $temp['children'] = array();
                    foreach ($l['son_list'] as $lt) {
                        if ($sort_id == $lt['sort_id']) {
                            continue;
                        } elseif ($this->hasGoods($lt['sort_id'])) {
                            continue;
                        } elseif (empty($temp['children'])) {
                            $temp['children'][] = array('value' => '0', 'text' => '不选择');
                        }
                        $tt = array();
                        $tt['value'] = $lt['sort_id'];
                        $tt['text'] = $lt['sort_name'];
                        $temp['children'][] = $tt;
                    }
                }
                $result[] = $temp;
            }
            
            $arr['list'] = $result;
        } else {
            $this->returnCode('20140029');
        }
        $this->returnCode(0, $arr);
    }
    
    /**
     * 打包APP的商家中心的快店店铺的商品列表
     */
    public function shopGoods()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $sortId = isset($_POST['sort_id']) ? $_POST['sort_id'] : 0;
        if ($sortId) {
            $this->returnCode(0, array('product_list' => $this->getGoodsBySortId($sortId, $store_id)));
        } else {
            $sortList = D('Shop_goods_sort')->lists($store_id, false);
            $firstSort = reset($sortList);
            $sortId = isset($firstSort['sort_id']) ? $firstSort['sort_id'] : 0;
            $this->returnCode(0, array('product_list' => $this->getGoodsBySortId($sortId, $store_id), 'sort_list' => array_values($sortList)));
        }
    }
    
    public function goodsDel()
    {
        $goods_ids = isset($_POST['goods_id']) ? $_POST['goods_id'] : null;
        $store_id = I('store_id');
        $database_goods = D('Shop_goods');
        if (empty($goods_ids)) {
            $this->returnCode('20140045');
        }
        $where = array('store_id' => $store_id);
        $where['goods_id'] = array('in', $goods_ids);
        if ($database_goods->where($where)->delete()) {
            $spec_obj = M('Shop_goods_spec'); //规格表
            $old_spec = $spec_obj->field(true)->where(array('goods_id' => array('in', $goods_ids), 'store_id' => $store_id))->select();
            foreach ($old_spec as $os) {
                $delete_spec_ids[] = $os['id'];
            }
            $spec_obj->where(array('goods_id' => array('in', $goods_ids), 'store_id' => $store_id))->delete();
            if ($delete_spec_ids) {
                $old_spec_val = M('Shop_goods_spec_value')->where(array('sid' => array('in', $delete_spec_ids)))->delete();
            }
            M('Shop_goods_properties')->where(array('goods_id' => array('in', $goods_ids)))->delete();
            $this->returnCode(0);
        }else{
            $this->returnCode('20140045');
        }
    }
    
    public function updateGoodsStatus()
    {
        $status = isset($_POST['status']) ? intval($_POST['status']) : 1;
        $goods_ids = isset($_POST['goods_ids']) ? $_POST['goods_ids'] : null;
        $store_id = I('store_id');
        $database_goods = D('Shop_goods');
        if (empty($goods_ids)) {
            $this->returnCode(1, null, '请选择要修改的商品');
        }
        $where = array('store_id' => $store_id);
        $where['goods_id'] = array('in', $goods_ids);
        if ($database_goods->where($where)->save(array('status' => $status, 'last_time' => time()))) {
            $this->returnCode(0);
        }else{
            $this->returnCode(0);
            $this->returnCode(1, null, '修改商品状态失败，请重试');
        }
    }
    
    /**
     * 商品上传图片
     */
    public function goodsImage()
    {
        if ($_FILES['imgFile']['error'] != 4) {
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
            $image = D('Image')->handle($this->merchant_session['mer_id'], 'goods', 1, $param);
            if ($image['error']) {
                $this->returnCode('20140052');
            } else {
                $title = $image['title']['imgFile'];
                $store_image_class = new goods_image();
                $url = $store_image_class->get_image_by_path($title);
                $arr = array(
                    'url' => $url,
                    'title' => $title,
                );
                exit(json_encode(array('errorCode' => 0,'errorMsg'=>'success', 'result' => $arr)));
//                 $arr = array(
//                     'url' => $this->config['site_url'] . $image['url']['file'],
//                     'title' => $image['title']['file']
//                 );
//                 exit(json_encode(array('errorCode' => 0,'errorMsg'=>'success', 'result' => $arr)));
            }
        } else {
            $this->returnCode('20140051');
        }
    }
    
    public function goodsSortList()
    {
        $store_id = I('store_id');
        $shop = D('Merchant_store_shop')->field('is_mult_class')->where(array('store_id' => $store_id))->find();
        if (empty($shop)) {
            $this->returnCode('20140029');
        }
        $arr = array();
        $list = D('Shop_goods_sort')->lists($store_id, false);
        $result = array();
        foreach ($list as $l) {
            $temp = array();
            $temp['value'] = $l['sort_id'];
            $temp['text'] = $l['sort_name'];
            $temp['children'] = $this->valueKey($l['son_list']);
            $result[] = $temp;
        }
        $arr['list'] = $result;
        $this->returnCode(0, $arr);
    }
    
    private function valueKey($list, $id = 'sort_id', $name = 'sort_name', $children = 'son_list')
    {
        if (empty($list)) {
            return $list;
        }
        $result = array();
        foreach ($list as $l) {
            $value = array();
            $value['value'] = $l[$id];
            $value['text'] = $l[$name];
            $value['children'] = $this->valueKey($l[$children], $id, $name, $children);
            $result[] = $value;
        }
        return $result;
    }
    
    /**
     * 商品编辑操作
     */
    public function goodsModify()
    {
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
        if ($goods_id) {
            $goods = D('Shop_goods')->field(true)->where(array('goods_id' => $goods_id))->find();
            if (empty($goods)) {
                $this->returnCode(1, null, '商品数据不存在！');
            }
            $goods['show_start_time'] = substr($goods['show_start_time'], 0, -3);
            $goods['show_end_time'] = substr($goods['show_end_time'], 0, -3);
            $goods['seckill_price'] = floatval($goods['seckill_price']);
            if ($goods['seckill_open_time']) {
                $goods['seckill_start_date'] = date('Y-m-d', $goods['seckill_open_time']);
                $goods['seckill_start_time'] = date('H:i', $goods['seckill_open_time']);
            } else {
                $goods['seckill_start_date'] = '';
                $goods['seckill_start_time'] = '00:00';
            }
            if ($goods['seckill_close_time']) {
                $goods['seckill_end_date'] = date('Y-m-d', $goods['seckill_close_time']);
                $goods['seckill_end_time'] = date('H:i', $goods['seckill_close_time']);
            } else {
                $goods['seckill_end_date'] = '';
                $goods['seckill_end_time'] = '00:00';
            }
        }
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求的店铺信息不存在');
        }
        $shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $store_id))->find();
        if(!empty($goods['image'])){
            $goods_image_class = new goods_image();
            $tmp_pic_arr = explode(';', $goods['image']);
            foreach ($tmp_pic_arr as $key => $value) {
                $goods['pic_arr'][$key]['title'] = $value;
                $goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
            }
        }
        
        $print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
        foreach ($print_list as &$l) {
            if ($l['is_main']) {
                $l['name'] .= '(主打印机)';
            } else {
                $l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
            }
        }
        
        
        //freight_value 其他区域运费
        //freight_type  商品分类
        //limit_type 限购类型
        //max_num 每单限购
        $express_template = D('Express_template')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->select();
        $category_list = D('Goods_category')->get_list();
        $categoryList = array();
        foreach ($category_list as $cl) {
            $temp = array();
            $temp['value'] = $cl['id'];
            $temp['text'] = $cl['name'];
            $temp['children'] = $this->valueKey($cl['son_list'], 'id', 'name');
            $categoryList[] = $temp;
        }
        $this->returnCode(0, array('store_theme' => intval($shop['store_theme']), 'goods' => $goods ? $goods : array(), 'print_list' => $print_list ? $print_list : array(), 'category_list' => $categoryList ? $categoryList : array(), 'express_template' => $express_template));
    }
    
    public function getGoodsProperties()
    {
        $cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
        $properties = D('Goods_properties')->field(true)->where(array('status' => 1, 'cat_id' => $cat_id))->select();
        if ($properties) {
            $value_ids = array();
            $relations = D('Goods_properties_relation')->field(true)->where(array('gid' => $goods_id))->select();
            foreach ($relations as $r) {
                $value_ids[] = $r['pid'];
            }
            $pids = array();
            $list = array();
            foreach ($properties as $row) {
                $pids[] = $row['id'];
                $row['value_list'] = null;
                $list[$row['id']] = $row;
            }
            $value_list = D('Goods_properties_value')->field(true)->where(array('pid' => array('in', $pids)))->select();
            foreach ($value_list as $v) {
                if (isset($list[$v['pid']])) {
                    if (in_array($v['id'], $value_ids)) {
                        $v['checked'] = 1;
                    } else {
                        $v['checked'] = 0;
                    }
                    $list[$v['pid']]['value_list'][] = $v;
                }
            }
            $data = array();
            foreach ($list as $row) {
                if (isset($row['value_list']) && $row['value_list']) {
                    $data[] = $row;
                }
            }
            $this->returnCode(0, $data);
            exit(json_encode(array('error_code' => false, 'data' => $data)));
        } else {
            $this->returnCode(0, array());
            exit(json_encode(array('error_code' => true, 'msg' => '没有数据')));
        }
    }
    
    /**
     * 保存商品
     */
    public function goodsAdd()
    {
        $sort_id = isset($_POST['sort_id']) ? intval($_POST['sort_id']) : 0;
        $shopGoodsSortDB = D('Shop_goods_sort');
        $nowSort = $shopGoodsSortDB->field(true)->where(array('sort_id' => $sort_id))->find();
        if (empty($nowSort)) {
            $this->returnCode(1, null, '请选择商品分类');
        }
        
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $nowSort['store_id'], 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺不存在！');
        }
        
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
        
        if ($goods_id) {
            $goods = D('Shop_goods')->field(true)->where(array('goods_id' => $goods_id, 'store_id' => $now_store['store_id']))->find();
            if (empty($goods)) {
                $this->returnCode(1, null, '商品数据不存在！');
            }
        }
        if (!empty($_POST['sysname']) && (empty($_POST['name']) || empty($_POST['unit']) || empty($_POST['price']) || $_POST['pic'])) {
            $number = htmlspecialchars(trim($_POST['sysname']));
            $systemGoods = D('System_goods');
            $condition = array();
            
            if ($number) {
                $condition['number'] = $number;
            }
            if (empty($condition)) {
                $this->returnCode(1, null, '请输入查询条件');
            }
            $now_goods = $systemGoods->field(true)->where($condition)->find();
            if (empty($now_goods)) {
                $this->returnCode(1, null, '商品不存在');
            }
            $_POST['name'] = $_POST['name'] ? $_POST['name'] : $now_goods['name'];
            $_POST['unit'] = $_POST['unit'] ? $_POST['unit'] : $now_goods['unit'];
            $_POST['price'] = $_POST['price'] ? $_POST['price'] : $now_goods['price'];
            $pics = array();
            if(!empty($now_goods['image'])){
                $goods_image_class = new goods_image();
                $tmp_pic_arr = explode(';', $now_goods['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $pics[] = $value;
                }
            }
        }
        $_POST['pic'] = $_POST['pic'] ? $_POST['pic'] : $pics;
        if (empty($_POST['name'])) {
            $this->returnCode(1, null, '商品名称必填！');
        }
        if (empty($_POST['unit'])) {
            $this->returnCode(1, null, '商品单位必填！');
        }
        if ($_POST['price'] === '' && !$this->config['open_extra_price']) {
            $this->returnCode(1, null, '商品价格可以设置为0，但是必填！');
        }
        if ($_POST['price'] < 0 && !$this->config['open_extra_price']) {
            $this->returnCode(1, null, '商品价格必须大于或等于0！');
        }
        if (empty($_POST['pic'])) {
            $this->returnCode(1, null, '请至少上传一张照片！');
        }
        
        $_POST['des'] = fulltext_filter($_POST['des']);
        
        $img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
        $rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
        foreach($_POST['pic'] as $kp => $vp){
            $tmp_vp = explode(',', $vp);
            if (!strstr($tmp_vp[0], '/upload/')) $rand_num = $tmp_vp[0];
            $_POST['pic'][$kp] = $rand_num . ',' . $tmp_vp[1];
        }
        $_POST['image'] = implode(';', $_POST['pic']);
        $_POST['print_id'] = isset($_POST['print_id']) ? intval($_POST['print_id']) : 0;
        
        if ($fsort = $shopGoodsSortDB->field(true)->where(array('fid' => $sort_id))->find()) {
            $this->returnCode(1, null, '该分类有子分类，不能直接添加商品');
        } elseif ($nowSort['operation_type'] != 0) {
            $shopGoodsSortDB->where(array('sort_id' => $sort_id))->save(array('operation_type' => 0));
        }
        
//         $_POST['seckill_open_time'] = strtotime($_POST['seckill_open_time'] . ":00");
//         $_POST['seckill_close_time'] = strtotime($_POST['seckill_close_time'] . ":00");
        
        $_POST['sort_id'] = $sort_id;
        $_POST['store_id'] = $now_store['store_id'];
        $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
        $_POST['original_stock'] = $_POST['stock_num'];
        
        $seckill_start_date = isset($_POST['seckill_start_date']) && $_POST['seckill_start_date'] ? $_POST['seckill_start_date'] : '';
        $seckill_start_time = isset($_POST['seckill_start_time']) && $_POST['seckill_start_time'] ? $_POST['seckill_start_time'] : '';
        
        $seckill_end_date = isset($_POST['seckill_end_date']) && $_POST['seckill_end_date'] ? $_POST['seckill_end_date'] : '';
        $seckill_end_time = isset($_POST['seckill_end_time']) && $_POST['seckill_end_time'] ? $_POST['seckill_end_time'] : '';
        
        if ($seckill_start_time) {
            if ($seckill_start_date) {
                $_POST['seckill_open_time'] = strtotime($seckill_start_date . ' ' . $seckill_start_time);
            } else {
                $_POST['seckill_open_time'] = strtotime(date('Y-m-d') . ' ' . $seckill_start_time);
            }
        }
        
        if ($seckill_end_time) {
            if ($seckill_end_date) {
                $_POST['seckill_close_time'] = strtotime($seckill_end_date . ' ' . $seckill_end_time);
            } else {
                $_POST['seckill_close_time'] = strtotime(date('Y-m-d') . ' ' . $seckill_end_time);
            }
        }
        
        if ($goods_id) {
            if (D('Shop_goods')->where(array('goods_id' => $goods_id))->save($_POST)) {
                D('Goods_properties_relation')->where(array('gid' => $goods_id))->delete();
                if (isset($_POST['goodsproperties'])) {
                    foreach ($_POST['goodsproperties'] as $pid) {
                        D('Goods_properties_relation')->add(array('gid' => $goods_id, 'pid' => $pid));
                    }
                }
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '编辑失败！请重试！');
            }
        } else {
            if ($goods_id = D('Shop_goods')->add($_POST)) {
                D('Goods_properties_relation')->where(array('gid' => $goods_id))->delete();
                if (isset($_POST['goodsproperties'])) {
                    foreach ($_POST['goodsproperties'] as $pid) {
                        D('Goods_properties_relation')->add(array('gid' => $goods_id, 'pid' => $pid));
                    }
                }
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '添加失败！请重试！');
            }
        }
    }
    
    public function spec()
    {
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
        $goods = D('Shop_goods')->field(true)->where(array('goods_id' => $goods_id))->find();
        if (empty($goods)) {
            $this->returnCode(1, null, '商品数据不存在！');
        }
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $goods['store_id'], 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        
        $return = D('Shop_goods')->format_spec_value($goods['spec_value'], $goods['goods_id']);
        $goods['json'] = isset($return['json']) ? json_encode($return['json']) : '';
        $goods['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
        $goods['properties_status_list'] = isset($return['properties_status_list']) ? $return['properties_status_list'] : '';
        $goods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
        $goods['list'] = isset($return['list']) ? $return['list'] : '';
        $this->returnCode(0, $goods);
    }
    
    public function specSave()
    {
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
        $goods = D('Shop_goods')->field(true)->where(array('goods_id' => $goods_id))->find();
        if (empty($goods)) {
            $this->returnCode(1, null, '商品数据不存在！');
        }
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $goods['store_id'], 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        if ($_POST['specs']) {
            foreach ($_POST['specs'] as $val) {
                if (empty($val)) {
                    $this->returnCode(1, null, '请给规格取名，若不需要的请删除后重新生成');
                }
            }
        }
        
        if ($_POST['spec_val']) {
            foreach ($_POST['spec_val'] as $rowset) {
                foreach ($rowset as $val) {
                    if (empty($val)) {
                        $this->returnCode(1, null, '请给规格的属性值取名，若不需要的请删除后重新生成');
                    }
                }
            }
        }
        
        if ($_POST['properties']) {
            foreach ($_POST['properties'] as $val) {
                if (empty($val)) {
                    $this->returnCode(1, null, '请给属性取名，若不需要的请删除后重新生成');
                }
            }
        }
        
        if ($_POST['properties_val']) {
            foreach ($_POST['properties_val'] as $rowset) {
                foreach ($rowset as $val) {
                    if (empty($val)) {
                        $this->returnCode(1, null, '请给属性的属性值取名，若不需要的请删除后重新生成');
                    }
                }
            }
        }
        $return = D('Shop_goods')->saveSpec($_POST, $goods['store_id']);
        if ($return) {
            $this->returnCode(0);
        } else {
            $this->returnCode(1, null, '保存失败稍后重试');
        }
    }
    /**
     * 搜索商品
     */
    public function searchGoods()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $keyword = isset($_POST['keyword']) ? htmlspecialchars(trim($_POST['keyword'])) : '';
        $goodsList = D('Shop_goods')->field(true)->where(array('store_id' => $store_id, 'name' => array('like' , '%' . $keyword . '%')))->select();
        $goods_image_class = new goods_image();
        foreach ($goodsList as &$goods) {
            if(!empty($goods['image'])){
                $tmp_pic_arr = explode(';', $goods['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $goods['pic_arr'][$key]['title'] = $value;
                    $goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
                }
            }
        }
        $this->returnCode(0, $goodsList);
    }
    /**
     * 克隆商品
     */
    public function cloneGoods()
    {
        $source_store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $store_ids = isset($_POST['store_ids']) ? $_POST['store_ids'] : 0;
        
        if ($store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $source_store_id, 'have_shop' => 1))->find()) {
            if ($shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $source_store_id))->find()) {
                
            } else {
                $this->returnCode(1, null, '店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
            }
        } else {
            $this->returnCode(1, null, '店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
        }
        foreach ($store_ids as $store_id) {
            if ($target_store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id, 'have_shop' => 1))->find()) {
                if (!$target_shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find()) {
                    continue;
                }
            } else {
                continue;
            }
            
            $goods_sorts = M('Shop_goods_sort')->field(true)->where(array('store_id' => $source_store_id))->order('level ASC')->select();
            foreach ($goods_sorts as $sv) {
                $oldIds[$sv['sort_id']] = $sv['fid'];//id ==> fid
            }
            $listOldFidToNewFid = array();
            foreach ($goods_sorts as $sort) {
                $source_sort_id = $sort['sort_id'];
                if ($target_sort = M('Shop_goods_sort')->field(true)->where(array('store_id' => $store_id, 'sort_name' => $sort['sort_name']))->find()) {
                    $target_sort_id = $target_sort['sort_id'];
                } else {
                    $sort['store_id'] = $store_id;
                    unset($sort['sort_id']);
                    $sort['fid'] = 0;
                    if (isset($oldIds[$source_sort_id]) && $oldIds[$source_sort_id]) {// oldFId = $oldIds[$source_sort_id];
                        $sort['fid'] = isset($listOldFidToNewFid[$oldIds[$source_sort_id]]) ? $listOldFidToNewFid[$oldIds[$source_sort_id]] : 0;
                    }
                    $target_sort_id = M('Shop_goods_sort')->add($sort);
                }
                $listOldFidToNewFid[$source_sort_id] = $target_sort_id;//oldID ==> newID
                
                $goods_list = M('Shop_goods')->field(true)->where(array('store_id' => $source_store_id, 'sort_id' => $source_sort_id))->select();
                foreach ($goods_list as $goods) {
                    if ($tmp_goods = M('Shop_goods')->field(true)->where(array('name' => $goods['name'], 'store_id' => $store_id))->find()) {
                        continue;
                    } else {
                        $source_goods_id = $goods['goods_id'];
                        unset($goods['goods_id']);
                        $goods['store_id'] = $store_id;
                        $goods['sort_id'] = $target_sort_id;
                        $goods['print_id'] = 0;
                        $target_goods_id = M('Shop_goods')->add($goods);
                        $pro_map = $spec_map = $spec_value_map = array();
                        if ($goods['is_properties']) {
                            $properties = M('Shop_goods_properties')->field(true)->where(array('goods_id' => $source_goods_id))->select();
                            foreach ($properties as $pro_data) {
                                $source_pro_id = $pro_data['id'];
                                unset($pro_data['id']);
                                $pro_data['goods_id'] = $target_goods_id;
                                $pro_map[$source_pro_id] = M('Shop_goods_properties')->add($pro_data);
                            }
                        }
                        if ($goods['spec_value']) {
                            $spec_list = M('Shop_goods_spec')->field(true)->where(array('goods_id' => $source_goods_id, 'store_id' => $source_store_id))->select();
                            foreach ($spec_list as $spec) {
                                $source_spec_id = $spec['id'];
                                unset($spec['id']);
                                
                                $spec['store_id'] = $store_id;
                                $spec['goods_id'] = $target_goods_id;
                                
                                if ($new_spec_id = M('Shop_goods_spec')->add($spec)) {
                                    
                                    $spec_value_list = M('Shop_goods_spec_value')->field(true)->where(array('sid' => $source_spec_id))->select();
                                    foreach ($spec_value_list as $spec_value) {
                                        $source_spec_value_id = $spec_value['id'];
                                        unset($spec_value['id']);
                                        $spec_value['sid'] = $new_spec_id;
                                        $spec_value_map[$source_spec_value_id] = M('Shop_goods_spec_value')->add($spec_value);
                                    }
                                }
                            }
                            
                            //规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#
                            $spec_array = explode('#', $goods['spec_value']);
                            $target_spec_value_array = array();
                            foreach ($spec_array as $str) {
                                $row_array = explode('|', $str);
                                $spec_val_ids = explode(':', $row_array[0]);
                                $new_ids = array();
                                foreach ($spec_val_ids as $tid) {
                                    $new_ids[] = $spec_value_map[$tid];
                                }
                                $row_array[0] = implode(':', $new_ids);
                                if (count($row_array) > 2 && $row_array[2] && strstr($row_array[2], '=')) {
                                    $pro_str_ids = explode(':', $row_array[2]);
                                    $new_pro_ids = array();
                                    foreach ($pro_str_ids as $pstr) {
                                        $v_k_a = explode('=', $pstr);
                                        $new_pro_ids[] = $pro_map[$v_k_a[0]] . '=' . $v_k_a[1];
                                    }
                                    $row_array[2] = implode(':', $new_pro_ids);
                                }
                                $target_spec_value_array[] = implode('|', $row_array);
                            }
                            M('Shop_goods')->where(array('goods_id' => $target_goods_id))->save(array('spec_value' => implode('#', $target_spec_value_array)));
                        }
                    }
                }
            }
            $this->returnCode(0);
        }
    }
    /**
     * 优惠列表
     */
    public function discount()
    {
        $now_store = $this->checkStore($_POST['store_id']);
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺信息错误');
        }
        $discount = D('Shop_discount')->field(true)->where(array('store_id' => $now_store['store_id']))->select();
        
        $this->returnCode(0, $discount);
    }
    /**
     * 单个优惠查看
     */
    public function discountModify()
    {
        $discountId = isset($_POST['discountId']) ? intval($_POST['discountId']) : 0;
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = $this->checkStore($store_id);
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺信息错误');
        }
        $discount = D('Shop_discount')->field(true)->where(array('id' => $discountId, 'store_id' => $now_store['store_id']))->find();
        
        $this->returnCode(0, $discount);
    }
    /**
     * 保存优惠
     */
    public function discountSave()
    {
        $discountId = isset($_POST['discountId']) ? intval($_POST['discountId']) : 0;
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = $this->checkStore($store_id);
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺信息错误');
        }
        $database_discount = D('Shop_discount');
        $where = array('id' => $discountId, 'store_id' => $now_store['store_id']);
        $discount = $database_discount->field(true)->where($where)->find();
        
        $data_discount = array();
        $data_discount['store_id'] = $now_store['store_id'];
        $data_discount['mer_id'] = $now_store['mer_id'];
        $data_discount['full_money'] = floatval($_POST['full_money']);
        $data_discount['reduce_money'] = floatval($_POST['reduce_money']);
        $data_discount['type'] = intval($_POST['type']);
        $data_discount['status'] = intval($_POST['status']);
        $data_discount['is_share'] = intval($_POST['is_share']);
        $data_discount['source'] = 1;
        if ($discount) {
            if ($database_discount->where($where)->save($data_discount)) {
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '修改失败,请重试!');
            }
        } else {
            if ($database_discount->add($data_discount)) {
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '新建失败,请重试!');
            }
        }
    }
    
    /* 检测店铺存在，并检测是不是归属于商家 */
    protected function checkStore($store_id)
    {
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['store_id'] = $store_id;
        $condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
        $now_store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
        if (empty($now_store)) {
            return false;
        } else {
            if ($now_shop = D('Merchant_store_shop')->field(true)->where($condition_merchant_store)->find()) {
                if (!empty($now_shop['background'])) {
                    $image_tmp = explode(',', $now_shop['background']);
                    $now_shop['background_image'] = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
                }
                return array_merge($now_store, $now_shop);
            }
            return $now_store;
        }
    }
    
    public function foodshop()
    {
        $store_id = I('store_id', '');
        if($store_id){
            $this->merchant_session['store_id'] = $store_id;
        }
        $mer_id = $this->merchant_session['mer_id'];
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['mer_id'] = $mer_id;
        $condition_merchant_store['have_meal'] = '1';
        $condition_merchant_store['status'] = '1';
//         $count_store = $database_merchant_store->where($condition_merchant_store)->count();
        
//         import('@.ORG.merchant_page');
//         $p = new Page($count_store, 30);
        
        $sql = "SELECT `s`.`store_id`, `s`.`mer_id`, `s`.`name`, `s`.`adress`, `s`.`phone`, `s`.`sort`, `fs`.`store_id` AS `sid` FROM ". C('DB_PREFIX') . "merchant_store AS s LEFT JOIN  ". C('DB_PREFIX') . "merchant_store_foodshop AS fs ON `s`.`store_id`=`fs`.`store_id`";
        $sql .= " WHERE `s`.`mer_id`={$mer_id} AND `s`.`status`='1' AND `s`.`have_meal`='1'";
        if ($this->merchant_session['store_id']) {
            $sql .= " AND `s`.`store_id`={$this->merchant_session['store_id']}";
        }
        $sql .= " ORDER BY `s`.`sort` DESC,`s`.`store_id` ASC";
//         $sql .= " LIMIT {$p->firstRow}, {$p->listRows}";
        $store_list = D()->query($sql);
        
        $this->returnCode(0, $store_list);
    }
    
    /**
     * 编辑店铺
     */
    public function foodshopModify()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺不存在！');
        }
        $store_shop = D('Merchant_store_foodshop')->field(true)->where(array('store_id' => $store_id))->find();
        if(!empty($store_shop['pic'])){
            $goods_image_class = new foodshopstore_image();
            $tmp_pic_arr = explode(';', $store_shop['pic']);
            foreach ($tmp_pic_arr as $key => $value) {
                $store_shop['pic_arr'][$key]['title'] = $value;
                $store_shop['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
            }
        }
        
        if(!empty($store_shop['background'])){
            $goods_image_class = new foodshopstore_image();
            $tmp_pic_arr = explode(';', $store_shop['background']);
            foreach ($tmp_pic_arr as $key => $value) {
                $store_shop['background_arr'][$key]['title'] = $value;
                $store_shop['background_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
            }
        }
        if ($store_shop) {
            $store_shop['book_start'] = substr($store_shop['book_start'], 0, 5);
            $store_shop['book_stop'] = substr($store_shop['book_stop'], 0, 5);
        }
        //所有分类
        $category_list = D('Meal_store_category')->lists();//field(true)->where(array('cat_status' => 1))->order('`cat_sort` DESC,`cat_id` ASC')->select();
        
        //此店铺有的分类
        $relation_list = D('Meal_store_category_relation')->field(true)->where(array('store_id' => $store_id))->select();
        $relation_array = array();
        foreach ($relation_list as $key => $value) {
            array_push($relation_array, $value['cat_id']);
        }
        
        
        $categoryList = array();
        foreach ($category_list as $crow) {
            $temp = array();
            $temp['cat_fid'] = $crow['cat_fid'];
            $temp['cat_id'] = $crow['cat_id'];
            $temp['cat_name'] = $crow['cat_name'];
            $temp['is_select'] = 0;
            if (in_array($crow['cat_id'], $relation_array)) {
                $temp['is_select'] = 1;
            }
            $temp['son_list'] = $this->childrenCategory($crow['son_list'], $relation_array);
            $categoryList[] = $temp;
        }
        
        
        $this->returnCode(0, array('store' => array_merge($now_store, $store_shop), 'category_list' => $categoryList));
    }
    
    /**
     * 保存餐饮店铺
     */
    public function foodshopSave()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺不存在！');
        }

        if(substr($_POST['store_notice'], -1) == ' '){
            $_POST['store_notice'] = trim($_POST['store_notice']);
        }else{
            $_POST['store_notice'] = trim($_POST['store_notice']);
        }
        
        if(empty($_POST['store_category'])){
            $this->returnCode(1, null, '请至少选一个分类！');
        }
        $cat_ids = array();
        foreach ($_POST['store_category'] as $cat_a) {
            $a = explode('-', $cat_a);
            $cat_ids[] = array('cat_fid' => $a[0], 'cat_id' => $a[1]);
        }
        
//         $img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
//         $rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
//         foreach($_POST['pic'] as $kp => $vp){
//             $tmp_vp = explode(',', $vp);
//             $_POST['pic'][$kp] = $rand_num . ',' . $tmp_vp[1];
//         }
        $_POST['pic'] = implode(';', $_POST['pic']);
        
//         foreach($_POST['background'] as $kp => $vp){
//             $tmp_vp = explode(',', $vp);
//             $_POST['background'][$kp] = $rand_num . ',' . $tmp_vp[1];
//         }
        $_POST['background'] = implode(';', $_POST['background']);
        
        $_POST['store_discount'] = isset($_POST['store_discount']) ? intval($_POST['store_discount']) : 0;
        $_POST['discount_type'] = isset($_POST['discount_type']) ? intval($_POST['discount_type']) : 0;
        $_POST['book_day'] = isset($_POST['book_day']) ? intval($_POST['book_day']) : 1;
        $_POST['is_auto_order'] = isset($_POST['is_auto_order']) ? intval($_POST['is_auto_order']) : 1;
        
        $foodshopDB = D('Merchant_store_foodshop');
        if ($store_shop = $foodshopDB->field(true)->where(array('store_id' => $store_id))->find()) {
            if (empty($store_shop['create_time'])) $_POST['create_time'] = time();
            $_POST['last_time'] = time();
            $operat_shop = $foodshopDB->where(array('store_id' => $store_id))->save($_POST);
        } else {
            $_POST['create_time'] = time();
            $_POST['last_time'] = time();
            $operat_shop = $foodshopDB->add($_POST);
        }
        
        $database_shop_category_relation = D('Meal_store_category_relation');
        $condition_shop_category_relation['store_id'] = $now_store['store_id'];
        $database_shop_category_relation->where($condition_shop_category_relation)->delete();
        foreach($cat_ids as $key => $value){
            $data_shop_category_relation[$key]['cat_id'] = $value['cat_id'];
            $data_shop_category_relation[$key]['cat_fid'] = $value['cat_fid'];
            $data_shop_category_relation[$key]['store_id'] = $now_store['store_id'];
        }
        $database_shop_category_relation->addAll($data_shop_category_relation);
        
        $this->returnCode(0);
    }
    
    /**
     * 餐饮的背景图和餐厅环境图片
     */
    public function uploadPic()
    {
        $picType = isset($_POST['picType']) ? $_POST['picType'] : 'foodshopstore';//foodshop_goods
        if ($_FILES['file']['error'] != 4) {
            $width = '900,450';
            $height = '500,250';
            $param = array('size' => 10);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $width;
            $param['thumbMaxHeight'] = $height;
            $param['thumbRemoveOrigin'] = false;
            $image = D('Image')->handle($this->merchant_session['mer_id'], $picType, 1, $param);
            if ($image['error']) {
                exit(json_encode(array('errorCode' => 1, 'errorMsg' => $image['msg'])));
            } else {
                $title = $image['title']['file'];
                if ($picType == 'foodshop_goods') {
                    $goods_image_class = new foodshop_goods_image();
                } else {
                    $goods_image_class = new foodshopstore_image();
                }
                
                $url = $goods_image_class->get_image_by_path($title, 's');
                exit(json_encode(array('errorCode' => 0,'errorMsg'=>'success', 'result' => array('url' => $url, 'title' => $title))));
            }
        } else {
            exit(json_encode(array('errorCode' => 1, 'errorMsg' => '没有选择图片')));
        }
    }
    
    /**
     * 餐饮分类列表
     */
    public function foodshopSort()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id))->find();
        if (empty($now_store)) {
            $this->returnCode(1, [], '店铺不存在！');
        }
        
        $database_goods_sort = D('Foodshop_goods_sort');
        $condition_goods_sort['store_id'] = $now_store['store_id'];
        $sort_list = $database_goods_sort->field(true)->where($condition_goods_sort)->order('`sort` DESC,`sort_id` ASC')->select();
        
        foreach ($sort_list as $key => $value) {
            if (!empty($value['week'])) {
                $week_arr = explode(',', $value['week']);
                $week_str = '';
                foreach ($week_arr as $k => $v) {
                    $week_str .= $this->get_week($v) . ' ';
                }
                $sort_list[$key]['week_str'] = $week_str;
            }
        }
        $this->returnCode(0, $sort_list ? $sort_list : []);
    }
    
    
    
    /**
     * 编辑餐饮商品分类
     */
    public function foodSortModify()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $sort_id = isset($_POST['sort_id']) ? intval($_POST['sort_id']) : 0;
        $now_sort = array();
        if ($sort_id) {
            $now_sort = D('Foodshop_goods_sort')->field(true)->where(array('sort_id' => $sort_id))->find();
            if (empty($now_sort)) {
                $this->returnCode(1, null, '请求数据不合法！');
            }
        }
        $this->returnCode(0, $now_sort);
    }
    
    /**
     * 保存餐饮商品分类
     */
    public function foodSortSave()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $sort_id = isset($_POST['sort_id']) ? intval($_POST['sort_id']) : 0;
        $now_sort = array();
        if ($sort_id) {
            $now_sort = D('Foodshop_goods_sort')->field(true)->where(array('sort_id' => $sort_id))->find();
            if (empty($now_sort)) {
                $this->returnCode(1, null, '请求数据不合法！');
            }
        }
        if (empty($_POST['sort_name'])) {
            $this->returnCode(1, null, '分类名称必填！');
        }
        $database_goods_sort = D('Foodshop_goods_sort');
        //         $data_goods_sort['sort_id'] = $now_sort['sort_id'];
        $data_goods_sort['sort_name'] = $_POST['sort_name'];
        $data_goods_sort['sort'] = intval($_POST['sort']);
        $data_goods_sort['sort_discount'] = intval($_POST['sort_discount']);
        $data_goods_sort['is_weekshow'] = intval($_POST['is_weekshow']);
        if ($_POST['week']) {
            $data_goods_sort['week'] = implode(',', $_POST['week']);
        }
        
        $data_goods_sort['store_id'] = $store_id;
        if ($now_sort) {
            if ($database_goods_sort->where(array('sort_id' => $sort_id))->save($data_goods_sort)) {
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '保存失败！！您是不是没做过修改？请重试。');
            }
        } else {
            if ($database_goods_sort->add($data_goods_sort)) {
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '保存失败,请重试。');
            }
        }
    }
    
    
    /**
     * 删除分类 
     */
    public function foodSortDel()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $sort_id = isset($_POST['sort_id']) ? intval($_POST['sort_id']) : 0;
        $now_sort = D('Foodshop_goods_sort')->field(true)->where(array('sort_id' => $sort_id))->find();
        if (empty($now_sort)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        
        $count = D('Foodshop_goods')->where(array('sort_id' => $now_sort['sort_id'], 'store_id' => $now_sort['store_id']))->count();
        if ($count) {
            $this->returnCode(1, null, '该分类下有商品，先删除商品后再来删除该分类');
        }
        $database_goods_sort = D('Foodshop_goods_sort');
        $condition_goods_sort['sort_id'] = $now_sort['sort_id'];
        if ($database_goods_sort->where($condition_goods_sort)->delete()) {
            $this->returnCode(0);
        } else {
            $this->returnCode(1, null, '删除失败！');
        }
    }
    /**
     * 餐饮商品列表
     */
    public function foodshopGoods()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺不存在！');
        }
        $goodsList = D('Foodshop_goods')->field(true)->where(array('store_id' => $store_id))->select();
        
        $goods_image_class = new foodshop_goods_image();
        
        $list = array();
        foreach ($goodsList as $goods) {
            if(!empty($goods['image'])){
                $tmp_pic_arr = explode(';', $goods['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $goods['pic_arr'][$key]['title'] = $value;
                    $goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
                }
            }
            $list[$goods['sort_id']][] = $goods;
        }
        $database_goods_sort = D('Foodshop_goods_sort');
        $condition_goods_sort['store_id'] = $now_store['store_id'];
        $sort_list = $database_goods_sort->field(true)->where($condition_goods_sort)->order('`sort` DESC,`sort_id` ASC')->select();
        
        foreach ($sort_list as $key => $value) {
            if (!empty($value['week'])) {
                $week_arr = explode(',', $value['week']);
                $week_str = '';
                foreach ($week_arr as $k => $v) {
                    $week_str .= $this->get_week($v) . ' ';
                }
                $sort_list[$key]['week_str'] = $week_str;
            }
            $sort_list[$key]['goodsList'] = array();
            if (isset($list[$value['sort_id']])) {
                $sort_list[$key]['goodsList'] = $list[$value['sort_id']];
            }
        }
        
        $this->returnCode(0, $sort_list);
    }
    
    /**
     * 商品编辑操作
     */
    public function foodModify()
    {
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
        if ($goods_id) {
            $goods = D('Foodshop_goods')->field(true)->where(array('goods_id' => $goods_id))->find();
            if (empty($goods)) {
                $this->returnCode(1, null, '商品数据不存在！');
            }
        }
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        if(!empty($goods['image'])){
            $goods_image_class = new foodshop_goods_image();
            $tmp_pic_arr = explode(';', $goods['image']);
            foreach ($tmp_pic_arr as $key => $value) {
                $goods['pic_arr'][$key]['title'] = $value;
                $goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
            }
        }
        $database_goods_sort = D('Foodshop_goods_sort');
        $condition_goods_sort['store_id'] = $now_store['store_id'];
        $sort_list = $database_goods_sort->field(true)->where($condition_goods_sort)->order('`sort` DESC,`sort_id` ASC')->select();
        
        $print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
        foreach ($print_list as &$l) {
            if ($l['is_main']) {
                $l['name'] .= '(主打印机)';
            } else {
                $l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
            }
        }
        $stock_type_list = array(
           array('id'=>0,'name'=>'每天更新') ,
           array('id'=>1,'name'=>'固定不变') ,
        );
        $labels = D('Store_label')->field(true)->select();
        $this->returnCode(0, array('goods' => $goods, 'sort_list' => $sort_list, 'print_list' => $print_list ? $print_list : array(), 'labels' => $labels,'stock_type_list' => $stock_type_list));
    }
    /**
     * 保存商品
     */
    public function foodAdd()
    {
        $sort_id = isset($_POST['sort_id']) ? intval($_POST['sort_id']) : 0;
        $goodsSortDB = D('Foodshop_goods_sort');
        $nowSort = $goodsSortDB->field(true)->where(array('sort_id' => $sort_id))->find();
        if (empty($nowSort)) {
            $this->returnCode(1, null, '商品分类不存在');
        }
        
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $nowSort['store_id'], 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '店铺不存在！');
        }
        
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
        
        if ($goods_id) {
            $goods = D('Foodshop_goods')->field(true)->where(array('goods_id' => $goods_id, 'store_id' => $now_store['store_id']))->find();
            if (empty($goods)) {
                $this->returnCode(1, null, '商品数据不存在！');
            }
        }
        
        if (empty($_POST['name'])) {
            $this->returnCode(1, null, '商品名称必填！');
        }
        if (empty($_POST['unit'])) {
            $this->returnCode(1, null, '商品单位必填！');
        }
        if ($_POST['price'] === '' && !$this->config['open_extra_price']) {
            $this->returnCode(1, null, '商品价格可以设置为0，但是必填！');
        }
        if ($_POST['price'] < 0 && !$this->config['open_extra_price']) {
            $this->returnCode(1, null, '商品价格必须大于或等于0！');
        }
        if (empty($_POST['pic'])) {
            $this->returnCode(1, null, '请至少上传一张照片！');
        }
        
        $_POST['des'] = fulltext_filter($_POST['des']);
        
        $img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
        $rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
        foreach($_POST['pic'] as $kp => $vp){
            $tmp_vp = explode(',', $vp);
            if (!strstr($tmp_vp[0], '/upload/')) $rand_num = $tmp_vp[0];
            $_POST['pic'][$kp] = $rand_num . ',' . $tmp_vp[1];
        }
        $_POST['image'] = implode(';', $_POST['pic']);
        $_POST['print_id'] = isset($_POST['print_id']) ? intval($_POST['print_id']) : 0;
        
        $_POST['seckill_open_time'] = strtotime($_POST['seckill_open_time'] . ":00");
        $_POST['seckill_close_time'] = strtotime($_POST['seckill_close_time'] . ":00");
        $_POST['label'] = isset($_POST['label']) && $_POST['label'] ? htmlspecialchars($_POST['label']) : '';
        $_POST['show_type'] = isset($_POST['show_type']) ? intval($_POST['show_type']) : 0;
        
        
        $_POST['sort_id'] = $sort_id;
        $_POST['store_id'] = $now_store['store_id'];
        $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
        if ($goods_id) {
            if (D('Foodshop_goods')->where(array('goods_id' => $goods_id))->save($_POST)) {
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '编辑失败！请重试！');
            }
        } else {
            if (D('Foodshop_goods')->add($_POST)) {
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '添加失败！请重试！');
            }
        }
    }
    /**
     * 修改规格
     */
    public function foodSpec()
    {
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
        $goods = D('Foodshop_goods')->field(true)->where(array('goods_id' => $goods_id))->find();
        if (empty($goods)) {
            $this->returnCode(1, null, '商品数据不存在！');
        }
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $goods['store_id'], 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        
        $return = D('Foodshop_goods')->format_spec_value($goods);
        $goods['json'] = isset($return['json']) ? json_encode($return['json']) : '';
        $goods['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
        $goods['properties_status_list'] = isset($return['properties_status_list']) ? $return['properties_status_list'] : '';
        $goods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
        $goods['list'] = isset($return['list']) ? $return['list'] : '';
        
        $this->returnCode(0, $goods);
    }
    
    /**
     * 保存规格
     */
    public function foodSpecSave()
    {
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
        $goods = D('Foodshop_goods')->field(true)->where(array('goods_id' => $goods_id))->find();
        if (empty($goods)) {
            $this->returnCode(1, null, '商品数据不存在！');
        }
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $goods['store_id'], 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        if ($_POST['specs']) {
            foreach ($_POST['specs'] as $val) {
                if (empty($val)) {
                    $this->returnCode(1, null, '请给规格取名，若不需要的请删除后重新生成');
                }
            }
        }
        
        if ($_POST['spec_val']) {
            foreach ($_POST['spec_val'] as $rowset) {
                foreach ($rowset as $val) {
                    if (empty($val)) {
                        $this->returnCode(1, null, '请给规格的属性值取名，若不需要的请删除后重新生成');
                    }
                }
            }
        }
        
        if ($_POST['properties']) {
            foreach ($_POST['properties'] as $val) {
                if (empty($val)) {
                    $this->returnCode(1, null, '请给属性取名，若不需要的请删除后重新生成');
                }
            }
        }
        
        if ($_POST['properties_val']) {
            foreach ($_POST['properties_val'] as $rowset) {
                foreach ($rowset as $val) {
                    if (empty($val)) {
                        $this->returnCode(1, null, '请给属性的属性值取名，若不需要的请删除后重新生成');
                    }
                }
            }
        }
        $return = D('Foodshop_goods')->saveSpec($_POST, $goods['store_id']);
        if ($return) {
            $this->returnCode(0);
        } else {
            $this->returnCode(1, null, '保存失败稍后重试');
        }
    }
    
    /**
     *  商品删除
     */
    public function foodDel()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $goods_ids = isset($_POST['goods_ids']) ? $_POST['goods_ids'] : array();
        
        if ($goods_ids) {
            $database_goods = D('Foodshop_goods');
            $condition_goods['goods_id'] = array('in', $goods_ids);
            $condition_goods['store_id'] = $store_id;
            if ($database_goods->where($condition_goods)->delete()) {
                $spec_obj = M('Foodshop_goods_spec'); //规格表
                $old_spec = $spec_obj->field(true)->where(array('goods_id' => array('in', $goods_ids), 'store_id' => $now_sort['store_id']))->select();
                foreach ($old_spec as $os) {
                    $delete_spec_ids[] = $os['id'];
                }
                $spec_obj->where(array('goods_id' => array('in', $goods_ids), 'store_id' => $now_sort['store_id']))->delete();
                if ($delete_spec_ids) {
                    $old_spec_val = M('Foodshop_goods_spec_value')->where(array('sid' => array('in', $delete_spec_ids)))->delete();
                }
                M('Foodshop_goods_properties')->where(array('goods_id' => array('in', $goods_ids)))->delete();
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '删除失败！请检查后重试。');
            }
        } else {
            $this->returnCode(1, null, '请传递要删除的商品ID');
        }
    }
    /**
     * 桌台分类列表
     */
    public function tableType()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        
        $types = M('Foodshop_table_type')->field(true)->where(array('store_id' => $now_store['store_id']))->select();
        $this->returnCode(0, $types);
    }
    
    /**
     * 桌台列表
     */
    public function table()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        
        $tables = M('Foodshop_table')->field(true)->where(array('store_id' => $now_store['store_id']))->order('tid ASC,id ASC')->select();
        $types = M('Foodshop_table_type')->field(true)->where(array('store_id' => $now_store['store_id']))->select();
        $staffs = D('Merchant_store_staff')->field('id, name')->where(array('store_id' => $now_store['store_id']))->select();
        $sData = array();
        foreach ($staffs as $staff) {
            $sData[$staff['id']] = $staff['name'];
        }
        $format_types = array();
        foreach ($types as $type) {
            $format_types[$type['id']] = $type;
        }
        foreach ($tables as &$table) {
            $table['type_name'] = isset($format_types[$table['tid']]['name']) ? $format_types[$table['tid']]['name'] : '';
            $table['staff_name'] = isset($sData[$table['staff_id']]) ? $sData[$table['staff_id']] : '';
            $table['qrcode'] = $this->config['site_url'] . '/wap.php?g=Wap&c=Foodshop&a=scan_qcode&store_id=' . $store_id . '&table_id=' . $table['id'];
			$table['qrcode_url'] = $this->config['site_url'].'/index.php?c=Recognition&a=get_own_qrcode&qrCon='.urlencode($table['qrcode']);
        }
        $this->returnCode(0, $tables);
    }
    
    /**
     * 编辑桌台分类
     */
    public function typeModify()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $typeId = isset($_POST['typeId']) ? intval($_POST['typeId']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $table_type = array();
        if ($typeId) {
            $table_type = M('Foodshop_table_type')->field(true)->where(array('id' => $typeId, 'store_id' => $now_store['store_id']))->find();
            if (empty($table_type)) {
                $this->returnCode(1, null, '桌台分类不存在');
            }
        }
        $this->returnCode(0, $table_type);
    }
    
    /**
     * 保存桌台分类
     */
    public function typeSave()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $typeId = isset($_POST['typeId']) ? intval($_POST['typeId']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $table_type = array();
        if ($typeId) {
            $table_type = M('Foodshop_table_type')->field(true)->where(array('id' => $typeId, 'store_id' => $now_store['store_id']))->find();
            if (empty($table_type)) {
                $this->returnCode(1, null, '桌台分类不存在');
            }
        }
        
        $data = array('store_id' => $now_store['store_id']);
        if (empty($_POST['name'])) {
            $this->returnCode(1, null, '桌台分类名称必填！');
        } else {
            $data['name'] = htmlspecialchars(trim($_POST['name']));
        }
        
        $min_people = isset($_POST['min_people']) ? intval($_POST['min_people']) : 0;
        $max_people = isset($_POST['max_people']) ? intval($_POST['max_people']) : 0;
        if ($max_people < 1 || $min_people < 1) {
            $this->returnCode(1, null, '容纳人数填写不正确！');
        } elseif ($max_people < $min_people) {
            $this->returnCode(1, null, '最大容纳人数不能小于最少人数！');
        } else {
            $data['min_people'] = $min_people;
            $data['max_people'] = $max_people;
        }
        
        $data['deposit'] = isset($_POST['deposit']) ? floatval($_POST['deposit']) : 0;
        $data['use_time'] = isset($_POST['use_time']) ? intval($_POST['use_time']) : 60;
        $data['number_prefix'] = isset($_POST['number_prefix']) ? htmlspecialchars($_POST['number_prefix']) : '';
        if (empty($data['number_prefix'])) {
            $this->returnCode(1, null, '排号前缀不能为空！');
        }
        
        $tobj = D('Foodshop_table_type')->field(true)->where(array('store_id' => $now_store['store_id'], 'number_prefix' => $data['number_prefix']))->find();
        if ($tobj && $tobj['id'] != $typeId) {
            $this->returnCode(1, null, '排号前缀已存在！');
        }
        
        if ($table_type) {
            if (D('Foodshop_table_type')->where(array('store_id' => $now_store['store_id'], 'id' => $typeId))->save($data)) {
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '保存失败！！您是不是没做过修改？请重试。');
            }
        } else {
            if (D('Foodshop_table_type')->add($data)) {
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '保存失败,请重试。');
            }
        }
    }
    /**
     * 餐饮桌台分类的删除
     */
    public function typeDel()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $typeId = isset($_POST['typeId']) ? intval($_POST['typeId']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        

        if ($table_type = M('Foodshop_table_type')->field(true)->where(array('store_id' => $now_store['store_id'], 'id' => $typeId))->find()) {
            $tables = M('Foodshop_table')->field(true)->where(array('store_id' => $now_store['store_id'], 'tid' => $typeId))->select();
            if ($tables) {
                $this->returnCode(1, null, '该分类下还有桌台，先清空桌台后才能删除');
            } else {
                if (M('Foodshop_table_type')->where(array('store_id' => $now_store['store_id'], 'id' => $typeId))->delete()) {
                    $this->returnCode(0);
                } else {
                    $this->returnCode(1, null, '删除失败，稍后重试');
                }
            }
        } else {
            $this->returnCode(1, null, '非法的数据');
        }
    }
    
    /**
     * 编辑桌台
     */
    public function tableModify()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $types = M('Foodshop_table_type')->field(true)->where(array('store_id' => $now_store['store_id']))->select();
        if (empty($types)) {
            $this->returnCode(1, null, '桌台还没有分类，请先增加桌台分类。');
        }
        $table_id = isset($_POST['tableId']) ? intval($_POST['tableId']) : 0;
        $table = array();
        if ($table_id) {
            $table = M('Foodshop_table')->field(true)->where(array('id' => $table_id, 'store_id' => $now_store['store_id']))->find();
            if (empty($table)) {
                $this->returnCode(1, null, '桌台信息有误');
            }
        }
        $staffs = D('Merchant_store_staff')->field('id, name')->where(array('store_id' => $now_store['store_id']))->select();
        $this->returnCode(0, array('table' => $table, 'staffs' => $staffs, 'types' => $types));
    }
    
    /**
     * 保存桌台
     */
    public function tableSave()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $types = M('Foodshop_table_type')->field(true)->where(array('store_id' => $now_store['store_id']))->select();
        if (empty($types)) {
            $this->returnCode(1, null, '桌台还没有分类，请先增加桌台分类。');
        }
        $table_id = isset($_POST['tableId']) ? intval($_POST['tableId']) : 0;
        $table = array();
        if ($table_id) {
            $table = M('Foodshop_table')->field(true)->where(array('id' => $table_id, 'store_id' => $now_store['store_id']))->find();
            if (empty($table)) {
                $this->returnCode(1, null, '桌台信息有误');
            }
        }
        
        $data = array('store_id' => $now_store['store_id']);
        $error_tips = '';
        if (empty($_POST['name'])) {
            $this->returnCode(1, null, '桌台名称必填！');
        } else {
            $data['name'] = htmlspecialchars(trim($_POST['name']));
        }
        $tid = isset($_POST['tid']) ? intval($_POST['tid']) : 0;
        $type_data = M('Foodshop_table_type')->field(true)->where(array('id' => $tid))->find();
        if (empty($type_data)) {
            $this->returnCode(1, null, '桌台所属分类不存在！');
        } else {
            $data['tid'] = $tid;
        }
        $staff_id = isset($_POST['staff_id']) ? intval($_POST['staff_id']) : 0;
        $staff = D('Merchant_store_staff')->field(true)->where(array('id' => $staff_id, 'store_id' => $now_store['store_id']))->find();
        if (empty($staff)) {
            $this->returnCode(1, null, '店员不存在！');
        } else {
            $data['staff_id'] = $staff_id;
        }
        
        if ($table) {
            if (D('Foodshop_table')->where(array('id' => $table_id, 'store_id' => $now_store['store_id']))->save($data)) {
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '保存失败！！您是不是没做过修改？请重试。');
            }
        } else {
            if (D('Foodshop_table')->add($data)) {
                M('Foodshop_table_type')->where(array('store_id' => $now_store['store_id'], 'id' => $tid))->setInc('num');
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '保存失败,请重试。');
            }
        }
    }
    
    /**
     * 删除桌台
     */
    public function tableDel()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $table_id = isset($_POST['tableId']) ? intval($_POST['tableId']) : 0;
        
        if ($table = M('Foodshop_table')->field(true)->where(array('store_id' => $now_store['store_id'], 'id' => $table_id))->find()) {
            if (M('Foodshop_table')->where(array('store_id' => $now_store['store_id'], 'id' => $table_id))->delete()) {
                M('Foodshop_table_type')->where(array('store_id' => $now_store['store_id'], 'id' => $table['tid']))->setDec('num');
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '删除失败，稍后重试');
            }
        } else {
            $this->returnCode(1, null, '非法的数据');
        }
    }
    
    /**
     * 套餐列表
     */
    public function package()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $packages = M('Foodshop_goods_package')->field(true)->where(array('store_id' => $now_store['store_id']))->select();
        $this->returnCode(0, $packages);
    }
    
    /**
     * 套餐编辑
     */
    public function packageModify()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $package = D('Foodshop_goods_package')->get_detail_by_id(array('id' => $id, 'store_id' => $now_store['store_id']), true);
        $this->returnCode(0, $package);
    }
    
    
    private function dstrlen($str)
    {
        $count = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $value = ord($str[$i]);
            if ($value > 127) {
                if ($value >= 192 && $value <= 223) {
                    $i++;
                } elseif ($value >= 224 && $value <= 239) {
                    $i = $i + 2;
                } elseif ($value >= 240 && $value <= 247) {
                    $i = $i + 3;
                }
            }
            $count++;
        }
        return $count;
    }
    
    /**
     * 套餐保存
     */
    public function packageSave()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        if (empty($_POST['name'])) {
            $this->returnCode(1, null, '套餐名称必填！');
        }
        if ($this->dstrlen($_POST['name']) > 20) {
            $this->returnCode(1, null, '套餐名称不超过20个字');
        }
        if ($this->dstrlen($_POST['note']) > 200) {
            $this->returnCode(1, null, '使用说明不超过200个字');
        }
        if ($goods_id = D('Foodshop_goods_package')->save_post_form($_POST, $now_store['store_id'])) {
            $this->returnCode(0);
        } else {
            $this->returnCode(1, null, '添加失败！请重试！');
        }
    }
    
    /**
     * 套餐选择菜品
     */
    public function foodMenu()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $database_goods = D('Foodshop_goods');
        $condition_goods['status'] = 1;
        $condition_goods['is_must'] = 0;
        $condition_goods['store_id'] = $now_store['store_id'];
        $condition_goods['spec_value'] = '';
        $condition_goods['is_properties'] = 0;
//         $count_goods = $database_goods->where($condition_goods)->count();
//         $p = new Page($count_goods, 20);
//         $goods_list = $database_goods->field(true)->where($condition_goods)->order('`sort` DESC, `goods_id` ASC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $goods_list = $database_goods->field(true)->where($condition_goods)->order('`sort` DESC, `goods_id` ASC')->select();
        $this->returnCode(0, $goods_list);
    }
    
    /**
     * 一键上下架
     */
    public function updateFoodStatus()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $status = isset($_POST['status']) ? intval($_POST['status']) : 1;
        $goods_ids = isset($_POST['goods_ids']) ? $_POST['goods_ids'] : null;
        $database_goods = D('Foodshop_goods');
        if (empty($goods_ids)) {
            $this->returnCode('20140045');
        }
        $where = array('store_id' => $store_id);
        $where['goods_id'] = array('in', $goods_ids);
        if ($database_goods->where($where)->save(array('status' => $status, 'last_time' => time()))) {
            $this->returnCode(0);
        }else{
            $this->returnCode('20140045');
        }
    }
    /**
     * 搜索餐饮的商品
     */
    public function searchFood()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $keyword = isset($_POST['keyword']) ? htmlspecialchars(trim($_POST['keyword'])) : '';
        $goodsList = D('Foodshop_goods')->field(true)->where(array('store_id' => $store_id, 'name' => array('like' , '%' . $keyword . '%')))->select();
        $goods_image_class = new foodshop_goods_image();
        foreach ($goodsList as &$goods) {
            if(!empty($goods['image'])){
                $tmp_pic_arr = explode(';', $goods['image']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $goods['pic_arr'][$key]['title'] = $value;
                    $goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
                }
            }
        }
        $this->returnCode(0, $goodsList);
    }
    /**
     * 快店一键修改库存
     */
    public function updateGoodsStock()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find();
        if (empty($shop)) {
            $this->returnCode(1, null, $this->config['shop_alias_name'] . '信息不正确');
        }
        
        $goods_ids = isset($_POST['goods_ids']) ? $_POST['goods_ids'] : array();
        
        if (empty($goods_ids)) {
            $this->returnCode(1, null, '请选择要更新库存的商品');
        }
        // 0 置空，-1：填满，>0自定义
        $stock = isset($_POST['stock']) ? intval($_POST['stock']) : -1;
        
        $goodsDB = D('Shop_goods');
        $goods_list = $goodsDB->field(true)->where(array('store_id' => $store_id, 'goods_id' => array('in', $goods_ids)))->select();
        
        $today = date('Ymd');
        foreach ($goods_list as $goods) {
            if ($stock == -1) {
                $goodsDB->where(array('goods_id' => $goods['goods_id']))->save(array('stock_num' => $goods['original_stock'], 'sell_day' => date('Ymd')));
            } elseif ($stock == 0) {
                $goodsDB->where(array('goods_id' => $goods['goods_id']))->save(array('stock_num' => $stock, 'sell_day' => date('Ymd')));
            } else {
                $num = max(0, $stock);
                $goodsDB->where(array('goods_id' => $goods['goods_id']))->save(array('stock_num' => $num, 'sell_day' => date('Ymd')));
            }
        }
        $this->returnCode(0);
    }
    
    /**
     * 快店的订单列表
     */
    public function shopOrderList()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        
        $pay_types = array('balance' => '余额支付', 'alipay' => '支付宝', 'tenpay' => '财付通', 'yeepay' => '易宝支付', 'allinpay' => '通联支付', 'chinabank' => '网银在线', 'weixin' => '微信支付', 'baidu' => '百度钱包', 'unionpay' => '银联支付', 'offline' => '货到付款');
        $where = array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id);
        
        $stauts = isset($_POST['st']) ? intval(trim($_POST['st'])) : -1;//订单状态
        $ftype = isset($_POST['ft']) ? trim($_POST['ft']) : '';//搜索关键词类型
        $fvalue = isset($_POST['fv']) ? trim(htmlspecialchars($_POST['fv'])) : '';//搜索关键词
        $order_id = $_POST['order_id'];
        
        $pay_type = isset($_POST['pay_type']) ? htmlspecialchars(trim($_POST['pay_type'])) : -2;//支付类型
        $order_from = isset($_POST['order_from']) ? intval(trim($_POST['order_from'])) : -2;//订单来源
        
        $stime = isset($_POST['stime']) ? trim(htmlspecialchars($_POST['stime'])) : '';//搜索开始时间
        $etime = isset($_POST['etime']) ? trim(htmlspecialchars($_POST['etime'])) : '';//搜索结束时间
        if ($stime && $etime) {
            $where['create_time'] = array(array('gt', strtotime($stime . ' 00:00:01')), array('lt', strtotime($etime . ' 23:59:59'))) ;
        }
        
        if ($stauts != -1 && $stauts != -2) {
            if ($status == 2) {
                $where['status'] = array('in', '2, 3');
            } else {
                $where['status'] = $status;
            }
        }
        if ($pay_type != -2) {
            if ($pay_type == 'balance') {
                $where['paid'] = 1;
                $where['pay_type'] = '';
            } else {
                $where['pay_type'] = $pay_type;
            }
        }
        if ($order_from != -2) {
            $where['order_from'] = $order_from;
        }
        
        switch ($ftype) {
            case 'oid': //订单id
                $fvalue && $where['real_orderid'] = array('like', "%$fvalue%");
                break;
            case 'xm':  //下单人姓名
                $fvalue && $where['username'] = array('like', "%$fvalue%");
                break;
            case 'dh':  //下单人电话
                $fvalue && $where['userphone'] = array('like', "%$fvalue%");
                break;
            case 'mps': //消费码
                $fvalue && $where['orderid'] = $fvalue;
                break;
            default:
                break;
        }
        
        $_GET['page'] = isset($_POST['page']) ? intval($_POST['page']) : 1;
        
        $shop_order = D('Shop_order')->get_order_list($where, 'pay_time DESC', false, false);
        if ($shop_order['order_list']) {
            foreach ($shop_order['order_list'] as $k => $v) {
                if ($v['pay_type_str'] == '未支付') {
                    $pay_status = '未支付';
                } else {
                    if (empty($v['third_id']) && ($v['pay_type'] == 'offline')) {
                        $pay_status = '线下未支付';
                    } elseif($v['paid'] == 0) {
                        $pay_status = '未支付';
                    } else {
                        $pay_status = '已支付';
                    }
                }
                
                $arr['shop_order'][$k] = array(
                    'order_id' => $v['order_id'],
                    'username' => $v['username'],
                    'userphone' => $v['userphone'],
                    'address' => $v['address'],
                    'create_time' => date('Y-m-d H:i:s', $v['create_time']),
                    'price' => $v['price'],
                    'pay_type_str' => $v['pay_type_str'],
                    'status' => $v['status'],
                    'paid' => $v['paid'],
                    'is_pick_in_store' => $v['is_pick_in_store'],
                    'deliverName' => $this->config['deliver_name'],
                    'pay_status' => $pay_status,
                    'total_price' => floatval($v['total_price']),
                    'change_price' => floatval($v['change_price']),
                    'price' => floatval($v['price']),
                    'change_price_reason' => $v['change_price_reason'],
                    'order_from_name' => $this->order_froms[$v['order_from']],
                    'cancel_type' => $v['cancel_type'],
                    'order_from' => $v['order_from'],
                    'fetch_number' => $v['fetch_number'],
                );
            }
        } else {
            $arr['shop_order'] = array();
        }
        $arr['page'] = $shop_order['page'];
        $arr['count'] = $shop_order['count'];
        if ($_GET['page'] == 1) {
            $shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find();
            $arr['deliver_type'] = $shop['deliver_type'];
            $arr['is_open_pick'] = $shop['is_open_pick'];
            $arr['is_change'] = $this->staff_session['is_change'];
        }
        $arr['pay_type'] = $pay_types;
        $arr['order_from'] = $this->order_froms;
        $arr['status_list'] = D('Shop_order')->status_list;
        $this->returnCode(0, $arr);
    }
    
    /**
     * 快店的订单详情（打包app使用）
     */
    public function shopOrderDetail()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        if ($store_shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find()) {
            $arr['deliver_type'] = $store_shop['deliver_type'];
            $arr['is_open_pick'] = $store_shop['is_open_pick'];
            $arr['is_change'] = $this->staff_session['is_change'];
            $arr['freight_alias'] = $store_shop['freight_alias'] ? $store_shop['freight_alias'] : '配送费';
            $arr['pack_alias'] = $store_shop['pack_alias'] ? $store_shop['pack_alias'] : '打包费';
        } else {
            $this->returnCode(1, null, '店铺信息不存在');
        }
        $order = D("Shop_order")->get_order_detail(array('mer_id' => $this->merchant_session['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id));
        if ($order) {
            if($order['pay_type'] == 'offline' && empty($order['third_id'])){
                $payment = rtrim(rtrim(number_format($order['price']-$order['card_price']-$order['merchant_balance']-$order['card_give_money']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-floatval($order['coupon_price']),2,'.',''),'0'),'.');
            }
            $sure = true; //可以确认消费和取消
            if ($order['is_pick_in_store'] == 0) {
                $supply = D('Deliver_supply')->field('uid')->where(array('order_id' => $order['order_id'], 'item' => 2))->find();
                if (isset($supply['uid']) && $supply['uid']) {
                    $sure = false;
                }
            }
            
            $discount_price = floatval(round($order['discount_price'] + $order['freight_charge'] + $order['packing_charge'] + $order['other_money'], 2));
            $order['card_discount'] = $order['card_discount'] == 0 ? 10 : $order['card_discount'];
            $arr['order_details'] = array(
                'sure' => $sure,
                'orderid' => $order['orderid'],
                'order_id' => $order['order_id'],
                'real_orderid' => $order['real_orderid'],
                'username' => $order['username'],
                'userphone' => $order['userphone'],
                'create_time' => date('Y-m-d H:i:s',$order['create_time']),
                'pay_time' => date('Y-m-d H:i:s',$order['pay_time']),
                'expect_use_time' => $order['expect_use_time'] != 0 ? date('Y-m-d H:i',$order['expect_use_time']) : '尽快',
                'is_pick_in_store' => $order['is_pick_in_store'],//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
                'address' => $order['address'],
                'deliver_str' => $order['deliver_str'],
                'deliver_status_str' => $order['deliver_status_str'],
                'note' => isset($order['desc']) && $order['desc'] ? $order['desc'] : '无',
                'invoice_head' => $order['invoice_head'],//发票抬头
                'pay_status' => $order['pay_status_print'],
                'pay_type_str' => $order['pay_type_str'],
                'status_str' => $order['status_str'],
                'score_used_count' => $order['score_used_count'],//抵用的积分
                'score_deducte' => strval(floatval($order['score_deducte'])),//积分兑现的金额
                'card_give_money' => strval(floatval($order['card_give_money'])),//会员卡赠送余额
                'merchant_balance' => strval(floatval($order['merchant_balance'])),//商家余额
                'balance_pay' => strval(floatval($order['balance_pay'])),//平台余额
                'payment_money' => strval(floatval($order['payment_money'])),//在线支付的金额
                'change_price' => strval(floatval($order['change_price'])),//店员修改前的原始价格（如果是0表示没有修改过，可不显示）
                'change_price_reason' => $order['change_price_reason'],//店员修改价格的理由
                'card_id' => $order['card_id'],
                'card_price' => strval(floatval($order['card_price'])),//商家优惠券的金额
                'coupon_price' => strval(floatval($order['coupon_price'])),//平台优惠券的金额
                'payment' => isset($payment) ? $payment : 0,
                'use_time' => $order['use_time'] != 0 ? date('Y-m-d H:i:s',$order['use_time']) : '0',
                'last_staff' => $order['last_staff'],
                'status' => $order['status'],
                'paid' => $order['paid'],
                'register_phone' => $order['register_phone'],//注册时的用户手机号
                'lat' => $order['lat'],
                'lng' => $order['lng'],
                'cue_field' => $order['cue_field'],//商家自定义字段值（如果没有的话是空 即：''）
                'card_discount' => $order['card_discount'],//会员卡折扣
                'goods_price' => strval(floatval($order['goods_price'])),//商品的总价
                'freight_charge' => strval(floatval($order['freight_charge'])),//配送费
                'packing_charge' => strval(floatval($order['packing_charge'])),//打包费
                'total_price' => strval(floatval($order['total_price'])),//订单总价
                'merchant_reduce' => strval(floatval($order['merchant_reduce'])),//商家优惠的金额
                'balance_reduce' => strval(floatval($order['balance_reduce'])),//平台优惠的金额
                'price' => strval(floatval($order['price'])),//实际支付金额
                'distance' => round(getDistance($order['lat'], $order['lng'], $now_store['lat'], $now_store['long'])/1000, 2),//距离
                'discount_price' => strval($discount_price),//折扣后的总价  = floatval(round($order['discount_price'] + $order['freight_charge'] + $order['packing_charge'], 2));
                'minus_price' => strval(floatval(round($order['merchant_reduce'] + $order['balance_reduce'], 2))),//平台和商家的优惠金额
                'go_pay_price' => strval(floatval(round($discount_price - $order['merchant_reduce'] - $order['balance_reduce'], 2))),//应付的金额
                'minus_card_discount' => strval(floatval(round(($discount_price - $order['merchant_reduce'] - $order['balance_reduce'] - $order['freight_charge']) * (1 - $order['card_discount'] * 0.1), 2))),//折扣与优惠的优惠金额
                'notes' => '注：改成已消费状态后同时如果是未付款状态则修改成线下支付已支付，状态修改后就不能修改了',
                'order_from_txt' => $this->order_froms[$order['order_from']],
                'order_from' => $order['order_from'],
                'deliver_log_list' => $order['deliver_log_list'],
                'express_name' => isset($order['express_name']) ? $order['express_name'] : '',
                'express_number' => $order['express_number'],
                'change_price_reason' => $order['change_price_reason'],
                'change_price' => floatval($order['change_price']),
                'cancel_type' => $order['cancel_type'],
                'other_money' => floatval($order['other_money']),
                'fetch_number' => $order['fetch_number'],
                'order_from_name' => $this->order_froms[$order['order_from']],
            );
            $tempList = array();
            foreach($order['info'] as $v) {
                $discount_price = floatval($v['discount_price']) > 0 ? floatval($v['discount_price']) : floatval($v['price']);
                $tGoods = array(
                    'name' => $v['name'],
                    'discount_type' => $v['discount_type'],
                    'price' => strval(floatval($v['price'])),
                    'discount_price' => strval($discount_price),
                    'spec' => empty($v['spec']) ? '' : $v['spec'],
                    'num' => $v['num'],
                    'total' => strval(floatval($v['price'] * $v['num'])),
                    'discount_total' => strval(floatval($discount_price * $v['num'])),
                );
                
                $index = isset($v['packname']) && $v['packname'] ? $v['packname'] : 0;
                if (isset($tempList[$index])) {
                    $tempList[$index]['list'][] = $tGoods;
                } else {
                    $tempList[$index] = array('name' => $v['packname'], 'list' => array($tGoods));
                }
            }
            if (count($tempList) == 1) {
                $tempList[$index]['name'] = '';
            }
            $arr['info'] = $tempList;
            $arr['discount_detail'] = $order['discount_detail'] ?: '';
        } else {
            $this->returnCode(1, null, '订单信息错误！');
        }
        if(empty($arr['info'])){
            $arr['info'] = array();
        }
        $this->returnCode(0, $arr);
    }
    
    /**
     * 餐饮的订单列表
     * status = 1 预定待确认 is_order = 0 未点餐 1已点餐
     * status = 2 店员已确认，在用餐中， is_order = 0 就餐中，1店员待确认菜品
     * status = 3买单完成
     */
    
    public function foodshopOrderList()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        
        $status = isset($_POST['status']) ? intval($_POST['status']) : -1;
        $keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
        $searchType = isset($_POST['searchType']) ? htmlspecialchars($_POST['searchType']) : '';
        
        $where = array('store_id' => $store_id);
        switch ($status) {
            case 1:
                $where['status'] = 1;//预定中
                $where['running_state'] = 0;
                break;
            case 2:
                $where['status'] = 2;//就餐中
                $where['running_state'] = 0;
                break;
            case 3:
                $where['status'] = array('in', array(3, 4));//已买单
                break;
            case 4:
                $where['running_state'] = 1;//待确认菜品
                $where['status'] = array('in', array(1, 2));//已买单
                break;
            case 5:
                $where['status'] = 5;//已取消
                break;
            default:
                $where['status'] = array('gt', 0);
        }
        if ($keyword) {
            switch($searchType){
                case 'dh':
                    $where['phone'] = $keyword;
                    break;
                case 'xm':
                    $where['name'] = $keyword;
                    break;
                case 'zt':
                    $tables = M('Foodshop_table')->where(array('name'=>array('like','%'.$keyword.'%'),'store_id'=>$store_id))->select();
                    foreach ($tables as $t) {
                        $tmp_table_id[] = $t['id'];
                    }
                    
                    $where['table_id'] = array('in',$tmp_table_id);
                    break;
            }
            
        }
        $data = D('Foodshop_order')->get_order_list($where, 'order_id DESC', 2);
        $this->returnCode(0, $data);
    }
    
    /**
     * 订单的菜品详情
     */
    public function foodshopOrderDetail()
    {
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (empty($now_store)) {
            $this->returnCode(1, null, '请求数据不合法！');
        }
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $where = array('order_id' => $order_id, 'store_id' => $store_id);
        
        $now_order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $store_id), 2);
        
        if (empty($now_order)) $this->returnCode(1, null, '订单不存在');
        
        $price = D('Foodshop_order')->count_price($now_order);
        
        $goods_detail_list = $now_order['info'];
        $package_list = array();
        $must_list = array();
        $old_goods_list = array();
        $total_num = 0;
        $total_price = 0;
        foreach ($goods_detail_list as $new) {
            $new['price'] = floatval($new['price']);
            $new['num'] = floatval($new['num']);
            $total_num += $new['num'];
            if ($new['package_id']) {
                if (isset($package_list[$new['package_id']])) {
                    if (isset($package_list[$new['package_id']]['list'][$new['goods_id']])) {
                        $package_list[$new['package_id']]['list'][$new['goods_id']]['num'] += $new['num'];
                    } else {
                        $package_list[$new['package_id']]['list'][$new['goods_id']] = $new;
                    }
                } else {
                    $package_list[$new['package_id']] = array('list' => array($new['goods_id'] => $new), 'name' => '', 'num' => 0, 'price' => 0);
                }
            } /*elseif ($new['is_must']) {
            $must_list[] = $new;
            } */else {
            // 				$total_price += $new['num'] * $new['price'];
            $old_goods_list[] = $new;
            }
        }
        if ($now_order['package_ids']) {
            $package_ids = json_decode($now_order['package_ids'], true);
            $packages = D('Foodshop_goods_package')->field(true)->where(array('in' => array('id', $package_ids)))->select();
            foreach ($package_ids as $pid) {
                foreach ($packages as $p) {
                    if ($pid == $p['id']) {
                        $package_list[$pid]['num']++;
                        $package_list[$pid]['price'] += $p['price'];
                        $package_list[$pid]['name'] = $p['name'];
                    }
                }
            }
        }
        
        $total_price = floatval($price + $now_order['book_price']);
        $goods_temp_list = $now_order['info_temp'];
        foreach ($goods_temp_list as &$temp) {
            $temp['price'] = floatval($temp['price']);
            $temp['num'] = floatval($temp['num']);
            $total_num += $temp['num'];
            $total_price += $temp['num'] * $temp['price'];
        }
        $this->returnCode(0, array('goods_list' => $old_goods_list, 'package_list' => $package_list, 'temp_list' => $goods_temp_list, 'total_price' => floatval($total_price), 'total_num' => $total_num));
    }
    
    
    /**
     * 系统商品库商品列表
     */
    public function sysGoods()
    {
        $sort_id = isset($_POST['sort_id']) ? intval($_POST['sort_id']) : 0;
        $keyword = isset($_POST['keyword']) ? htmlspecialchars(trim($_POST['keyword'])) : '';
        $sort_list = array();
        if (empty($sort_id)) {
            $systemGoodsSort= D('System_goods_sort');
            $sort_list = $systemGoodsSort->field(true)->where(array('status' => 1))->order('`sort_id` DESC')->select();
            $sort_id = isset($sort_list[0]['sort_id']) ? intval($sort_list[0]['sort_id']) : 0;
        }
        
        
        $where = array('status' => 1);
        
        if(!empty($keyword)){
            $where['name'] = array('like', '%' . $keyword . '%');
        }
        
        if ($sort_id && empty($keyword)) {
            $where['sort_id'] = $sort_id;
        }
        
        $systemGoods = D('System_goods');
        $count= $systemGoods->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 5);
        $goods_list = $systemGoods->field(true)->where($where)->order('`goods_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $goods_image_class = new goods_image();
        foreach ($goods_list as &$goods) {
            $goods['sort_name'] = isset($list[$goods['sort_id']]['name']) ? $list[$goods['sort_id']]['name'] : '';
            if(!empty($goods['image'])){
                $tmp_pic_arr = explode(';', $goods['image']);
                $image = '';
                foreach ($tmp_pic_arr as $key => $value) {
                    if (empty($image)) $image = $goods_image_class->get_image_by_path($value, 's');
                }
                $goods['image'] = $image;
            }
        }
        $next = 1;
        if ($p->totalPage <= $p->nowPage) {
            $next = 0;
        }
        $this->returnCode(0, array('goods_list' => empty($goods_list) ? array() : $goods_list, 'sort_list' => $sort_list, 'next' => $next));
    }
    
    /**
     * 获取系统商品库中的商品信息
     */
    public function getSysGoods()
    {
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
        $number = isset($_POST['number']) ? htmlspecialchars(trim($_POST['number'])) : '';
        $systemGoods = D('System_goods');
        $condition = array();
        if ($goods_id) {
            $condition['goods_id'] = $goods_id;
        }
        if ($number) {
            $condition['number'] = $number;
        }
        if (empty($condition)) {
            $this->returnCode(1, null, '请输入查询条件');
        }
        $now_goods = $systemGoods->field(true)->where($condition)->find();
        if (empty($now_goods)) {
            $this->returnCode(1, null, '商品不存在');
        }
        if(!empty($now_goods['image'])){
            $goods_image_class = new goods_image();
            $tmp_pic_arr = explode(';', $now_goods['image']);
            $image = '';
            $title = '';
            foreach ($tmp_pic_arr as $key => $value) {
                if (empty($image)) {
                    $title = $value;
                    $image = $goods_image_class->get_image_by_path($value, 's');
                }
            }
            $now_goods['image'] = $image;
            $now_goods['imageTitle'] = $title;
        }
        $this->returnCode(0, array('data' => $now_goods));
    }
}
?>
