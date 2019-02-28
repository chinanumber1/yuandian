<?php
class LinkAction extends BaseAction
{
	public $modules;

	public function _initialize()
	{
		parent::_initialize();

		$this->modules = array(
			'Home' => '首页',
			'AroundGroup' => '附近'.$this->config['group_alias_name'],
			'Group' => $this->config['group_alias_name'],
			'Store' => $this->config['merchant_alias_name'],
// 			'AroundMeal' => '附近'.$this->config['meal_alias_name'],
			'Meal' => $this->config['meal_alias_name'],
			'Meal_order' => $this->config['meal_alias_name'].'订单',
			'Group_order' => $this->config['group_alias_name'].'订单',
			'Shop_order' => $this->config['shop_alias_name'].'订单',
			'Group_collect' => $this->config['group_alias_name'].'收藏',
			'Card_list' => '我的优惠券',
			'Member' => '会员中心',
			'Invitation' => '交友',
			'Navigation' => $this->config['group_alias_name'].'导航',
			'Activity' => '找活动',
			'Classify' => '分类信息',
			'Storestaff' => '店员中心',
			'Storestaff_new' => '新店员中心',
			'NearWeiDian' => '附近微店',
			'Takeout' => '外卖',
			'NearMerchant' => '附近商家',
			'Lifeservice' => '生活缴费',
			'Wapmerchant' => '手机版商家中心',
			'WapmerchantReg' => '手机版商家入驻',
			'AppointList' => '预约首页',
			'AppointCategory' => '预约分类',
			'AppointOrder' => '预约订单',
			'WaimaiIndex' => $this->config['waimai_alias_name'].'首页',
			'WaimaiUser' => $this->config['waimai_alias_name'].'用户中心',
			'WaimaiOrder' => $this->config['waimai_alias_name'].'订单列表',
			'WaimaiCoupon2' => $this->config['waimai_alias_name'].'平台红包列表',
			'WaimaiCoupon1' => $this->config['waimai_alias_name'].'店铺红包列表',
			'WaimaiAddress' => $this->config['waimai_alias_name'].'用户地址列表',
// 			'Deliver' => '配送员中心',
			'Deliver_new' => '配送员中心',
			'HousevillageList' => '社区小区列表',
			'News' => '平台快报',
			'Coupon' => '平台优惠券',
			'All_Merchant_coupon' => '全部商家优惠券',
			'Ride' => '顺风车',
			'Crowdsourcing' => '众包',
			'Shop' => $this->config['shop_alias_name'],
			'ShopLink' => '链接版'.$this->config['shop_alias_name'],
			'MerchantStore' => $this->config['store_alias_name'].'列表',
			'Gift' => $this->config['gift_alias_name'],
			'Workerstaff'=>'技师中心',
			'Mall'=>'商城',
			'Sign'=>'签到',
			'Wxapp'=>'营销活动',
			'Portal'=>'门户首页',
			'Wapactivity'=>'限时活动',
			'City_car'=>'快捷挪车',
			'Mobile_recharge'=>'手机充值',
			'Scenic_index'=>'景区首页',
			'Service_index'=>'服务快派',
			'Sub_card'=>'免单套餐',
			'Yuedan'=>'约单',
			'Hotel'=>'酒店',
			'Paotui'=>'跑腿',
			'Special_shop'=>$this->config['shop_alias_name'].'专题',
			'NewMerchantapp'=>'新版移动商家中心',
			'GroupMainPage'=>$this->config['group_alias_name'].'首页',
			'Miniprogram'=> '小程序',
            'Discover' => '发现'
		);
	}
	public function insert()
	{
	    $type = isset($_GET['type']) ? $_GET['type'] : 'modules';
	    if ($type == 'modules') {
	        $modules = $this->modules();
	    } else {
	        $modules = $this->programs();
	    }
	    
	    $this->assign('modules', $modules);
	    $this->display();
	}
	
	public function programs()
	{
	    $t = array();
	    if (isset($this->config['pay_wxapp_appid']) && $this->config['pay_wxapp_appid']) {
	        $t[] = array('module' => 'Miniprogram', 'linkcode' => '', 'name' => $this->modules['Miniprogram'],'sub' => 1,'canselected' => 0,'linkurl' => '','keyword' => '','askeyword' => 1);
			redirect(U('Miniprogram'));die;
	    }
	    return $t;
	}
	public function modules()
	{
		$t = array(
		array('module' => 'Home', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Home/index', array('no_house'=>'1'), true, false, true)), 'name'=>'网站首页','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Home'],'askeyword'=>1),
		array('module' => 'Group', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Group/index', '', true, false, true)),'name'=>$this->modules['Group'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Store', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Merchant/store_list', '', true, false, true)),'name'=>$this->modules['Store'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'News', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Systemnews/index', '', true, false, true)),'name'=>$this->modules['News'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'AroundGroup', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Group/around', '', true, false, true)),'name'=>$this->modules['AroundGroup'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
// 		array('module' => 'Meal', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Foodshop/index', '', true, false, true)),'name'=>$this->modules['Meal'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
// 		array('module' => 'AroundMeal', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Meal_list/around', '', true, false, true)),'name'=>$this->modules['AroundMeal'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Meal_order', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/My/foodshop_order_list', '', true, false, true)),'name'=>$this->modules['Meal_order'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Group_order', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/My/group_order_list', '', true, false, true)),'name'=>$this->modules['Group_order'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Shop_order', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/My/shop_order_list', '', true, false, true)),'name'=>$this->modules['Shop_order'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Group_collect', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/My/group_collect', '', true, false, true)),'name'=>$this->modules['Group_collect'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Card_list', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/My/card_list', '', true, false, true)),'name'=>$this->modules['Card_list'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Member', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/My/index', '', true, false, true)),'name'=>$this->modules['Member'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Navigation', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Group/navigation', '', true, false, true)),'name'=>$this->modules['Navigation'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Activity', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Activity/index', '', true, false, true)),'name'=>$this->modules['Activity'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Storestaff', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Storestaff/index', '', true, false, true)),'name'=>$this->modules['Storestaff'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Storestaff_new', 'linkcode' => $this->config['site_url'].'/packapp/storestaff/index.html','name'=>$this->modules['Storestaff_new'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'NearMerchant', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Merchant/around', '', true, false, true)),'name'=>$this->modules['NearMerchant'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Systemcoupon', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Systemcoupon/index', '', true, false, true)),'name'=>$this->modules['Coupon'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Shop', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Shop/index', '', true, false, true)),'name'=>$this->modules['Shop'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'ShopLink', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Shop/classic_index', '', true, false, true)),'name'=>$this->modules['ShopLink'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		
		array('module' => 'Special_shop', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Special/index', '', true, false, true)),'name'=>$this->modules['Special_shop'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		
		array('module' => 'MerchantStore', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Merchant/store_list', '', true, false, true)),'name'=>$this->modules['MerchantStore'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Mall', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Mall/index', '', true, false, true)),'name'=>$this->modules['Mall'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		
		array('module' => 'Wxapp', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Wxapp/index', '', true, false, true)),'name'=>$this->modules['Wxapp'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		
		array('module' => 'Wapactivity', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Wapactivity/index', '', true, false, true)),'name'=>$this->modules['Wapactivity'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'City_car', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/City_car/index', '', true, false, true)),'name'=>$this->modules['City_car'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'Hotel', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Hotel/index', '', true, false, true)),'name'=>$this->modules['Hotel'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'GroupMainPage', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Group/main_page', '', true, false, true)),'name'=>$this->modules['GroupMainPage'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module' => 'All_Merchant_coupon', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/My_card/merchant_coupon_list',array('all_mer'=>1), true, false, true)),'name'=>$this->modules['All_Merchant_coupon'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),

		);
		// 功能-发现模块控制
        if ($this->config['find_msg']) {
            $t[] = array('module' => 'Discover', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Discover/discover_index', '', true, false, true)),'name'=>$this->modules['Discover'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
        }

		if($this->config['open_sub_card']){
			$t[] = array('module' => 'Sub_card', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Sub_card/index', '', true, false, true)),'name'=>$this->modules['Sub_card'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}

		if (isset($this->config['no_foodshop'])) {
			$t[] = array('module' => 'Meal', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Meal_list/index', '', true, false, true)),'name'=>$this->modules['Meal'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		} else {
			$t[] = array('module' => 'Meal', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Foodshop/index', '', true, false, true)),'name'=>$this->modules['Meal'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		if(isset($this->config['sign_get_score'])){
			$t[] = array('module' => 'Sign', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/My/sign', '', true, false, true)),'name'=>$this->modules['Sign'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}

		if(isset($this->config['portal_switch'])){
			$t[] = array('module' => 'Portal', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Portal/index', '', true, false, true)),'name'=>$this->modules['Portal'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}

		if(isset($this->config['appoint_site_name'])){
			$t[] = array('module' => 'Invitation', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Invitation/datelist', '', true, false, true)),'name'=>$this->modules['Invitation'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		if($this->config['appoint_page_row']){
			$t[] = array('module' => 'AppointList', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Appoint/index', '', true, false, true)),'name'=>$this->modules['AppointList'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
			$t[] = array('module' => 'AppointCategory', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Appoint/category', '', true, false, true)),'name'=>$this->modules['AppointCategory'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
			$t[] = array('module' => 'AppointOrder', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/My/appoint_order_list', '', true, false, true)),'name'=>$this->modules['AppointOrder'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
			$t[] = array('module' => 'Home', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Workerstaff/index', array(), true, false, true)), 'name'=>'技师中心','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Workerstaff'],'askeyword'=>1);
		}
		if(isset($this->config['wap_home_show_classify'])){
			$t[] = array('module' => 'Classify', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Classify/index', '', true, false, true)),'name'=>$this->modules['Classify'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		if ($this->config['house_open']) {
			$t[] = array('module' => 'HousevillageList', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/House/village_list', '', true, false, true)),'name'=>$this->modules['HousevillageList'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		if ($this->config['is_open_weidian']) {
			$t[] = array('module' => 'NearWeiDian', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Weidian/near_store_redirect', '', true, false, true)),'name'=>$this->modules['NearWeiDian'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		if($this->config['waimai_alias_name']){
			$t[] = array('module' => 'WaimaiIndex', 'linkcode' => str_replace('admin.php', 'index.php', U('WaimaiWap/Index/index', '', true, false, true)),'name'=>$this->modules['WaimaiIndex'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
			$t[] = array('module' => 'WaimaiUser', 'linkcode' => str_replace('admin.php', 'index.php', U('WaimaiWap/User/index', '', true, false, true)),'name'=>$this->modules['WaimaiUser'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
			$t[] = array('module' => 'WaimaiOrder', 'linkcode' => str_replace('admin.php', 'index.php', U('WaimaiWap/Order/index', '', true, false, true)),'name'=>$this->modules['WaimaiOrder'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
			$t[] = array('module' => 'WaimaiCoupon2', 'linkcode' => str_replace('admin.php', 'index.php', U('WaimaiWap/User/coupon', array('coupon_type'=>2), true, false, true)),'name'=>$this->modules['WaimaiCoupon2'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
			$t[] = array('module' => 'WaimaiCoupon1', 'linkcode' => str_replace('admin.php', 'index.php', U('WaimaiWap/User/coupon', array('coupon_type'=>1), true, false, true)),'name'=>$this->modules['WaimaiCoupon1'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
			$t[] = array('module' => 'WaimaiAddress', 'linkcode' => str_replace('admin.php', 'index.php', U('WaimaiWap/User/adresList', '', true, false, true)),'name'=>$this->modules['WaimaiAddress'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}

// 			$t[] = array('module' => 'Deliver', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Deliver/grab', '', true, false, true)),'name'=>$this->modules['Deliver'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
			$t[] = array('module' => 'Deliver_new', 'linkcode' => $this->config['site_url'].'/packapp/deliver/index.html','name'=>$this->modules['Deliver_new'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);

		if($this->config['ride_is']){
			$t[] = array('module' => 'Ride', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Ride/ride_list', array('plat'=>1), true, false, true)),'name'=>$this->modules['Ride'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		if($this->config['crowdsourcing_is']){
			$t[] = array('module' => 'Crowdsourcing', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Crowdsourcing/index', '', true, false, true)),'name'=>$this->modules['Crowdsourcing'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		if($this->config['live_service_have']){
			$t[] = array('module' => 'Lifeservice', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Lifeservice/index', '', true, false, true)),'name'=>$this->modules['Lifeservice'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
//		if($this->config['pay_in_store']){
			$t[] = array('module' => 'Wapmerchant', 'linkcode' => str_replace('admin.php', 'index.php', U('WapMerchant/Index/index', '', true, false, true)),'name'=>$this->modules['Wapmerchant'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
			$t[] = array('module' => 'WapmerchantReg', 'linkcode' => str_replace('admin.php', 'index.php', U('WapMerchant/Index/merreg', '', true, false, true)),'name'=>$this->modules['WapmerchantReg'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
			$t[] = array('module' => 'NewMerchantapp', 'linkcode' =>  $this->config['site_url'].'/packapp/merchant/index.html','name'=>$this->modules['NewMerchantapp'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
//		}
		if(isset($this->config['gift_alias_name'])){
			$t[] = array('module' => 'Gift', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Gift/index', '', true, false, true)),'name'=>$this->modules['Gift'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		if($this->config['mobile_recharge_APIKey'] && $this->config['mobile_recharge_openid'] ){
			$t[] = array('module' => 'Mobile_recharge', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Third_recharge/mobile_recharge', '', true, false, true)),'name'=>$this->modules['Mobile_recharge'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		
		if(isset($this->config['scenic_now_city'])){
			$t[] = array('module' => 'Scenic_index', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Scenic_index/index', '', true, false, true)),'name'=>$this->modules['Scenic_index'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}

		if(isset($this->config['service_days_number'])){
			$t[] = array('module' => 'Service_index', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Service/index', '', true, false, true)),'name'=>$this->modules['Service_index'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}

		if(isset($this->config['is_examine'])){
			$t[] = array('module' => 'Yuedan', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Yuedan/index', '', true, false, true)),'name'=>$this->modules['Yuedan'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		if(isset($this->config['paotui_service'])){
			$t[] = array('module' => 'Paotui', 'linkcode' => str_replace('admin.php', 'wap.php', U('Wap/Service/publish_detail', array('type'=>2), true, false, true)),'name'=>$this->modules['Paotui'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		}
		

		return $t;
	}
	
	public function Miniprogram()
	{
	    $items = array();
	    $items[] = array('id' => 1, 'sub' => 0, 'name' => '平台首页', 'linkcode'=> '/pages/index/index','sublink' => '','keyword' => '平台首页');
	    $items[] = array('id' => 2, 'sub' => 0, 'name' => $this->config['shop_alias_name'].'首页', 'linkcode'=> '/pages/shop_new/index/index','sublink' => '','keyword' => $this->config['shop_alias_name'].'首页');
	    $items[] = array('id' => 3, 'sub' => 0, 'name' => '店铺列表', 'linkcode'=> '/pages/store/index','sublink' => '','keyword' => '店铺列表');
	    $items[] = array('id' => 4, 'sub' => 0, 'name' => '个人中心', 'linkcode'=> '/pages/my/index','sublink' => '','keyword' => '个人中心');
	    $this->assign('list', $items);
	    $this->display('detail');
	}
	public function ShopLink()
	{
		$this->assign('moduleName', $this->modules['ShopLink']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid);
		$db = D('Shop_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();

		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Shop/classic_cat', array('cat_url'=>$item['cat_url']), true, false, true)),'sublink' => U('ShopLink', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Shop/classic_cat', array('cat_url'=>$item['cat_url']), true, false, true)),'sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	public function Shop()
	{
		$this->assign('moduleName', $this->modules['Shop']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid);
		$db = D('Shop_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();

		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		
		$items[] = array('id' => '0', 'sub' => 0, 'name' => '全部分类', 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Shop/index', array('cat'=>'all'), true, false, true)),'keyword' => '全部分类');
		
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Shop/index', array('cat'=>$item['cat_url']), true, false, true)),'sublink' => U('Link/shop', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Shop/index', array('cat'=>$item['cat_url']), true, false, true)),'sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

	public function Meal()
	{
		$this->assign('moduleName', $this->modules['Meal']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid, 'cat_status' => 1);
		$db = D('Meal_store_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();

		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
// 			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
// 				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Foodshop/index', array('cat_url'=>$item['cat_url']), true, false, true)),'sublink' => U('Link/Meal', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
// 			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Foodshop/index', array('cat_url'=>$item['cat_url']), true, false, true)),'sublink' => '','keyword' => $item['cat_name']));
// 			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

	public function Group()
	{
		$this->assign('moduleName', $this->modules['Group']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid);
		$db = D('Group_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();

		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Group/index', array('cat_url' => $item['cat_url']), true, false, true)),'sublink' => U('Link/group', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Group/index', array('cat_url' => $item['cat_url']), true, false, true)),'sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	public function Store()
	{
		$this->assign('moduleName', $this->modules['Store']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('cat_fid' => $cat_fid);
		$db = D('Merchant_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();

		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_fid' => $item['cat_id']))->find()) {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Merchant/store_list', array('cat_url' => $item['cat_url']), true, false, true)),'sublink' => U('Link/Store', array('cat_fid' => $item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
			} else {
				array_push($items, array('id' => $item['cat_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Merchant/store_list', array('cat_url' => $item['cat_url']), true, false, true)),'sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

	public function Sub_card()
	{
		$this->assign('moduleName', $this->modules['Sub_card']);
		$db = D('Sub_card');
		$where['status']  = 1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();

		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			array_push($items, array('id' => $item['id'], 'sub' => 0, 'name' => $item['name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Sub_card/sub_card_detail', array('sub_card_id'=>$item['id']), true, false, true)),'sublink' => '','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

	public function Yuedan()
	{
		$this->assign('moduleName', $this->modules['Yuedan']);
		$cat_fid = isset($_GET['cat_fid']) ? intval($_GET['cat_fid']) : 0;
		$where = array('status' => 2);
		$db = D('Yuedan_service_release');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();

		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){

			array_push($items, array('id' => $item['rid'], 'sub' => 0, 'name' => $item['title'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Shop/service_detail', array('rid'=>$item['rid']), true, false, true)),'sublink' => '','keyword' => $item['cat_name']));

		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

	public function News()
	{
		$this->assign('moduleName', $this->modules['News']);
		$where['status'] =  1;
		$db = D('System_news_category');
		$db2 = D('System_news');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db2->where(array('categroy_id' => $item['id']))->find()) {
				array_push($items, array('id' => $item['id'], 'sub' => 1, 'name' => $item['name'], 'linkcode'=> str_replace('admin.php', 'wap.php',  U('Wap/Systemnews/index', array('category_id' => $item['id']), true, false, true)),'sublink' => U('Link/news_content', array('category_id' => $item['id']), true, false, true),'keyword' => $item['name']));
			} else {
				array_push($items, array('id' => $item['id'], 'sub' => 0, 'name' => $item['name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Systemnews/index', array('category_id' => $item['id']), true, false, true)),'sublink' => '','keyword' => $item['cat_name']));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

	public function News_content()
	{
		$this->assign('moduleName', $this->modules['News']);
		$where['status'] =  1;
		$cat_fid = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
		$where['category_id'] =$cat_fid;
		$db2 = D('System_news');
		$count      = $db2->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		$list = $db2->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			array_push($items, array('id' => $item['id'], 'sub' => 0, 'name' => $item['title'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Systemnews/news', array('id' => $item['id']), true, false, true)),'sublink' => '','keyword' => $item['title']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

	public function Mall()
	{
		$this->assign('moduleName', $this->modules['Mall']);
		$where = array('fid' => array('gt', 0), 'status' => 1);
		$db2 = D('Goods_category');
		$count = $db2->where($where)->count();
		$Page = new Page($count, 5);
		$show = $Page->show();
		$list = $db2->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('`sort` DESC, `id` ASC')->select();
		$items = array();
		foreach ($list as $item){
			array_push($items, array('id' => $item['id'], 'sub' => 0, 'name' => $item['name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Mall/goods_list', array('cat_id' => $item['id'], 'cat_fid' => $item['fid']), true, false, true)),'sublink' => '','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

    public function Wxapp()
    {
        $this->assign('moduleName', $this->modules['Wxapp']);
        
        $data = array(
            'bargain' => '砍价',
            'seckill' => '秒杀',
            'crowdfunding' => '众筹',
            'unitary' => '一元夺宝',
            'cutprice' => '降价拍',
            'lottery' => '大转盘',
            'red_packet' => '微信红包',
            'guajiang' => '刮刮卡',
            'jiugong' => '九宫格',
            'luckyFruit' => '幸运水果机',
            'goldenEgg' => '砸金蛋',
            'voteimg' => '图文投票',
            'custom' => '万能表单',
            'card' => '微贺卡', // 暂时不显示渠道二维码
            'game' => '微游戏',
            'live' => '微场景',
            'research' => '微调研',
            'forum' => '讨论社区',
            'autumn' => '中秋吃月饼活动',
            'helping' => '微助力',
            'donation' => '募捐',
            'coinTree' => '摇钱树',
            'collectword' => '集字游戏',
            'sentiment' => '谁是情圣',
            'frontPage' => '我要上头条',
            'test' => '趣味测试',
            'punish' => '惩罚台',
            'shakeLottery' => '摇一摇',
            'youSetDiscount' => '优惠接力',
            'popularity' => '人气冲榜',
            'problem' => '一战到底',
            'seniorScene' => '微场景',
            'auction' => '微拍卖'
        );
        
        $count = count($data);
        
        $Page = new Page($count, 6);
        $show = $Page->show();
        $list = array_slice($data, $Page->firstRow, $Page->listRows); 
        $items = array();
        foreach ($list as $index => $item){
            array_push($items, array('id' => 0, 'sub' => 0, 'name' => $item, 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Wxapp/index', array('cat_url' => $index), true, false, true)),'sublink' => '','keyword' => $item));
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
    
	public function Coupon()
	{
		$this->assign('moduleName', $this->modules['Coupon']);
		$where['end_time'] = array('gt',time());
		$where['status'] = array('neq',0);
		$where['allow_new'] = 0;
		$db2 = D('System_coupon');
		$count = $db2->where($where)->count();

		$Page = new Page($count, 5);
		$list = $db2->where($where)->limit($Page->firstRow,$Page->listRows)->order(' `coupon_id` DESC')->select();
		$show = $Page->show();
		$items = array();
		foreach ($list as $item){
			array_push($items, array('id' => $item['id'], 'sub' => 0, 'name' => $item['name'], 'linkcode'=>$item['coupon_id'],'sublink' => '','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('coupon_select');
	}
	
	public function Special_shop()
	{
		$this->assign('moduleName', $this->modules['Special_shop']);
		$where = array('type' => 1);
		$db2 = D('Special');
		$count = $db2->where($where)->count();
		$Page = new Page($count, 5);
		$show = $Page->show();
		$list = $db2->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('`pigcms_id` ASC')->select();
		$items = array();
		foreach ($list as $item){
			array_push($items, array('id' => $item['id'], 'sub' => 0, 'name' => $item['name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Special/index', array('id' => $item['pigcms_id']), true, false, true)),'sublink' => '','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

	public function Classify()
	{
		$this->assign('moduleName', $this->modules['Classify']);
		$cid = isset($_GET['cid']) ? intval($_GET['cid']) : '';
		$where['fcid'] = $cid;
		$where['cat_status'] = 1;
		$db = D('Classify_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();

		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('fcid' => $item['cid']))->getField('subdir') == 2 || $item['fcid'] == 0) {
				array_push($items, array('id' => $item['cid'],'fcid'=>$item['cid'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Classify/index', array('cid' => $item['cid']), true, false, true)),'sublink' => U('Link/Classify', array('cid' => $item['cid']), true, false, true),'keyword' => ''));
			} else {
				array_push($items, array('id' => $item['cid'], 'fcid'=>$item['fcid'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Classify/Lists', array('cid' => $item['cid']), true, false, true)),'sublink' => '','keyword' => ''));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
		
	}

	public function AppointCategory()
	{
		$this->assign('moduleName', $this->modules['AppointCategory']);
		$db = D('AppointCategory');
		$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
		$where['cat_fid'] = $cat_id;
		$where['cat_status'] = 1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();

		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$items = array();
		foreach ($list as $item){
			if ($db->where(array('cat_id' => $item['cat_id']))->getField('cat_fid')) {
				array_push($items, array('id' => $item['cat_id'], 'cat_fid'=>$item['cat_fid'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Appoint/two_category', array('cat_id' => $item['cat_id']), true, false, true)),'sublink' => '','keyword' => $item['cat_name']));
			}else{
				array_push($items, array('id' => $item['cat_id'], 'cat_fid'=>$item['cat_fid'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> str_replace('admin.php', 'wap.php', U('Wap/Appoint/category', array('cat_id' => $item['cat_id']), true, false, true)),'sublink' => U('Link/AppointCategory', array('cat_id' => $item['cat_id']), true, false, true),'keyword' => ''));
			}
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
}
?>