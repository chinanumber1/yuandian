<?php
class LinkAction extends BaseAction
{
	public $where;
	public $modules;
	public $arr;
	
	public function _initialize() 
	{
		parent::_initialize();
		
		$this->where = array('token' => $this->token);
		$this->modules = array(
			'Home' => '首页',
			'Classify' => '网站分类',
			'Group' => $this->config['group_alias_name'],
			'Meal' => $this->config['meal_alias_name'],
// 			'PadMeal' => 'Pad点餐',
			'Takeout' => '外卖',
			'Lottery' => '大转盘',
			'Guajiang' => '刮刮卡',
// 			'Coupon' => '优惠券',
			'GoldenEgg' => '砸金蛋',
			'LuckyFruit' => '水果机',
			'Article' => '文章',
			'Weidian' => '微店',
			'Wxapp' => '营销活动',
			'Workerstaff'=>'技师中心',
			'Card_new' =>'会员卡',
			'Card_new_coupon' =>'会员卡优惠券',
			'Shop' =>$this->config['shop_alias_name'],
			'ShopLink' => '链接版'.$this->config['shop_alias_name'],
			'Appoint' =>$this->config['appoint_alias_name'],
			'Appoint_product' =>$this->config['appoint_alias_name'].'产品',
			'Mer_lottery' =>'活动',
			'Mall' => '商城',
			'Store' => $this->config['cash_alias_name'],
			'Im' => '客服聊天',
		);

		$this->modules_content = array(
			'Home' => '首页',
			'Group_content' => $this->config['group_alias_name'],
			'Meal_content' => $this->config['meal_alias_name'],
			'Shop_content' =>$this->config['shop_alias_name'],
			'Appoint_content' =>$this->config['appoint_alias_name'],
			'Article_content' => '文章',
			//'Weidian_content' => '微店',
			//'Wxapp_content' => '营销活动',
			'Card_new' =>'会员卡',
			'Card_new_coupon' =>'优惠券',
			'Mer_lottery' =>'活动',
		);


		$this->_accessListAction = array(
			'bargain'       => '砍价', 
			'seckill'       =>'秒杀', 
			'crowdfunding'  =>'众筹',
			'unitary'       =>'一元夺宝',
			'cutprice'      =>'降价拍',
			'lottery'		=>'大转盘',
			'red_packet'	=>'微信红包',
			'guajiang'		=>'刮刮卡',
			'jiugong'		=>'九宫格',
			'luckyFruit'	=>'幸运水果机',
			'goldenEgg'		=>'砸金蛋',
			'voteimg'		=>'图片投票',
			'custom'		=>'万能表单',
			'card'			=>'微贺卡',
			'game'			=>'微游戏',
			'live'			=>'微场景',
			'research'		=>'微调研',
			'forum'			=>'讨论社区',
			'autumn'		=>'中秋吃月饼活动',
			'helping'		=>'微助力',
			'donation'		=>'募捐',
			'cointree'		=>'摇钱树',
			'collectword'	=>'集字游戏',
			'sentiment'		=>'谁是情圣',
			'frontpage_action'=>'我要上头条',
			'frontPage'		=>'我要上头条',
			'test'			=>'趣味测试',
			'punish'		=>'惩罚台',
			'shakelottery'	=>'摇一摇',
			'yousetdiscount'=>'优惠接力',
			'popularity'	=>'人气冲榜',
			'problem_game'	=>'一战到底',
			'auction'		=>'微拍卖',
			'person_card'	=>'微名片',
		);
		$this->arr = array('game','Red_packet');
		$mer_pic = explode(';',$this->merchant_session['pic_info']);

		$this->assign('mer_pic',$this->config['site_url'].'/upload/merchant/'.str_replace(',','/',$mer_pic[0]));
	}
	
	public function insert()
	{
// 		if ($_GET['iskeyword']) {
			//$modules = $this->keywordModules();
// 		} else {
			$modules = $this->modules();
// 		}
		$this->assign('modules', $modules);
		$this->display();
	}

	/*
	 * 内容插入
	*/
	public function content_insert()
	{
		$modules = $this->content_modules();
		$this->assign('modules', $modules);
		$this->display();
	}
	
	public function keywordModules()
	{
// 		$school = M('School_set_index')->where(array('token' => $this->token))->find();
// 		$t = array(
// 			array('module'=>'Home','linkcode'=>'{siteUrl}/wap.php?g=Wap&c=Index&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>'微站首页','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Home'],'askeyword'=>1),
// 			array('module'=>'Group','linkcode'=>'{siteUrl}/wap.php?g=Wap&c=Group&a=grouponIndex&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Groupon'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->config['group_alias_name'],'askeyword'=>1),
// 			array('module'=>'Meal','linkcode'=>'{siteUrl}/wap.php?g=Wap&c=Meal&a=menu&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Meal'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'订餐','askeyword'=>1),
// 			array('module'=>'Lottery','linkcode'=>'','name'=>$this->modules['Lottery'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
// 			array('module'=>'Guajiang','linkcode'=>'','name'=>$this->modules['Guajiang'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
// 			array('module'=>'Coupon','linkcode'=>'','name'=>$this->modules['Coupon'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
// 			array('module'=>'Card','linkcode'=>'{siteUrl}/wap.php?g=Wap&c=Card&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['MemberCard'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'会员卡','askeyword'=>1),
// 			array('module'=>'GoldenEgg','linkcode'=>'','name'=>$this->modules['GoldenEgg'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
// 			array('module'=>'LuckyFruit','linkcode'=>'','name'=>$this->modules['LuckyFruit'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
// 		);
// 		//
// 		$sub=isset($_GET['sub'])?intval($_GET['sub']):0;
// 		foreach ($this->arr as $ka){
// 			$className='FunctionLibrary_'.$ka;
// 			if (class_exists($className)){
// 				$classInstance=new $className($this->token,$sub);
// 				$o=$classInstance->index();
// 				$canselected=$o['keyword']?1:0;
// 				$s=array('module'=>$ka,'linkcode'=>'','name'=>$o['name'],'sub'=>$o['subkeywords'],'canselected'=>$canselected,'linkurl'=>'?g=User&m=Link&a=commondetail&module='.$ka.'&iskeyword=1','keyword'=>$o['keyword'],'askeyword'=>1);
// 				array_push($t,$s);
// 			}
// 		}
// 		return $t;
	}
	
	public function commondetail()
	{
		$sub=isset($_GET['sub'])?intval($_GET['sub']):1;
		$className='FunctionLibrary_'.$this->_get('module');
		if (class_exists($className)){
			$classInstance=new $className($this->token,$sub);
			$o=$classInstance->index();
			/*
			$canselected=$o['keyword']?1:0;
			$s=array('module'=>$ka,'linkcode'=>'','name'=>$o['name'],'sub'=>$o['subkeywords'],'canselected'=>$canselected,'linkurl'=>'?g=User&m=Link&a=commondetail&module='.$ka.'&iskeyword=1','keyword'=>$o['keyword'],'askeyword'=>1);
			*/
			
			$this->assign('moduleName',$o['name']);
			$fromitems=intval($_GET['iskeyword'])?$o['subkeywords']:$o['sublinks'];
			$items=array();
			if ($fromitems){
				$i=0;
				foreach ($fromitems as $item){
					array_push($items,array('id'=>$i,'name'=>$item['name'],'linkcode'=>$item['link'],'linkurl'=>'','keyword'=>$item['keyword']));
				}
			}

		}
		
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function modules()
	{
		$t = array(
			array('module' => 'Home',		'linkcode' => str_replace('merchant.php', 'wap.php', U('Wap/Index/index', array('token' => $this->token), true, false, true)), 'name'=>'微站首页','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Home'],'askeyword'=>1),
			array('module' => 'Classify',	'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Index/lists', array('token' => $this->token), true, false, true)),'name'=>$this->modules['Classify'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>0),
			array('module' => 'Group',		'linkcode'=>'','name'=>$this->modules['Group'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'Meal',		'linkcode'=>'','name'=>$this->modules['Meal'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'Lottery',	'linkcode'=>'','name'=>$this->modules['Lottery'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'Guajiang',	'linkcode'=>'','name'=>$this->modules['Guajiang'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'GoldenEgg',	'linkcode'=>'','name'=>$this->modules['GoldenEgg'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'LuckyFruit',	'linkcode'=>'','name'=>$this->modules['LuckyFruit'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'Article',	'linkcode'=>'','name'=>$this->modules['Article'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'Shop',	'linkcode'=>'','name'=>$this->modules['Shop'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'ShopLink',	'linkcode'=>'','name'=>$this->modules['ShopLink'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'Wxapp',	'linkcode'=>'','name'=>$this->modules['Wxapp'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
// 			array('module' => 'PadMeal',		'linkcode'=>'','name'=>$this->modules['PadMeal'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'Mall', 'linkcode' => '', 'name' => $this->modules['Mall'], 'sub' => 1, 'canselected' => 0, 'linkurl' => '', 'keyword' => '', 'askeyword' => 1),
			array('module' => 'Workstaff',		'linkcode' => str_replace('merchant.php', 'wap.php', U('Wap/Workerstaff/login', array(), true, false, true)), 'name'=>'技师中心','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Workerstaff'],'askeyword'=>1),
			array('module' => 'Card_new_coupon',		'linkcode' => str_replace('merchant.php', 'wap.php', U('Wap/My_card/merchant_coupon_list', array('mer_id'=>$this->merchant_session['mer_id']), true, false, true)), 'name'=>'商家会员卡优惠券','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
			array('module' => 'Card_new',		'linkcode' => str_replace('merchant.php', 'wap.php', U('Wap/My_card/merchant_card', array('mer_id'=>$this->merchant_session['mer_id']), true, false, true)), 'name'=>'商家会员卡','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
			array('module' => 'Store',		'linkcode' => str_replace('merchant.php', 'wap.php', U('Wap/My/pay', array('mer_id'=>$this->merchant_session['mer_id']), true, false, true)), 'name'=>$this->modules['Store'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
			array('module' => 'Appoint',		'linkcode' => '', 'name'=>$this->modules['Appoint'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
			array('module' => 'Storestaff',		'linkcode' => $this->config['site_url'].'/packapp/storestaff/index.html', 'name'=>'手机版店员中心','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
			array('module' => 'Deliver',		'linkcode' => $this->config['site_url'].'/packapp/deliver/index.html', 'name'=>'手机版配送员中心','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
			array('module' => 'Im',		'linkcode' => $this->config['site_url'].'/wap.php?c=Home&a=jump_im&mer_id='.$this->merchant_session['mer_id'], 'name'=>$this->modules['Im'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Im'],'askeyword'=>1),
		);
		if($this->config['is_open_weidian']){
			array_push($t,array('module' => 'Weidian',	'linkcode'=>'','name'=>$this->modules['Weidian'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1));
		}
		return $t;
	}

	/*
	 *content_insert
	 */

	public function content_modules()
	{
		$t = array(
				array('module' => 'Home',		'linkcode' => str_replace('merchant.php', 'wap.php', U('Wap/Index/index', array('token' => $this->token), true, false, true)), 'name'=>'微站首页','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules_content['Home'],'askeyword'=>1),
				array('module' => 'Group_content',	'linkcode'=>'','name'=>$this->modules['Group'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
				array('module' => 'Shop_content',		'linkcode'=>'','name'=>$this->modules['Shop'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
				array('module' => 'Meal_content',		'linkcode'=>'','name'=>$this->modules['Meal'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
				array('module' => 'Appoint_content',	'linkcode'=>'','name'=>$this->modules['Appoint'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
				array('module' => 'Mer_lottery',	'linkcode'=>'','name'=>$this->modules['Mer_lottery'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
				array('module' => 'Article_content',	'linkcode'=>'','name'=>$this->modules['Article'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
				array('module' => 'Card_new',	'linkcode' => str_replace('merchant.php', 'wap.php', U('Wap/My_card/merchant_card', array('mer_id'=>$this->merchant_session['mer_id']), true, false, true)), 'name'=>'商家会员卡','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules_content['Card_new'],'askeyword'=>1),
			//array('module' => 'Wxapp_content',	'linkcode'=>'','name'=>$this->modules['Wxapp'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
				array('module' => 'Card_new_coupon',		'linkcode' => str_replace('merchant.php', 'wap.php', U('Wap/My_card/merchant_coupon_list', array('mer_id'=>$this->merchant_session['mer_id']), true, false, true)), 'name'=>'商家优惠券','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules_content['Card_new_coupon'],'askeyword'=>1),
		);
		//if($this->config['is_open_weidian']){
			//array_push($t,array('module' => 'Weidian',	'linkcode'=>'','name'=>$this->modules['Weidian'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1));
		//}
		return $t;
	}

	public function Weidian()
	{
		$data = array(
			'token'      => $this->token,
			'type'       => 'product,wei_page',
			'site_url'   => $this->config['site_url']
		);
		$sort_data = $data;
		$sort_data['salt'] = C('config.weidian_sign');
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		$data['sign_key'] = $sign_key;
		$data['request_time'] = time();
		$request_url = $this->config['weidian_url'].'/api/store.php';
		$resultArr = json_decode($this->curl_post($request_url,$data),true);
		if(!empty($resultArr['error_code'])){
			$this->error($resultArr['error_msg']);
		}
		if(empty($resultArr['stores'])){
			$this->error('您的微店没有添加店铺');
		}
		$items = array();
		foreach ($resultArr['stores'] as $item){
			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> $item['url'],'linkurl'=>'','keyword' => $item['name'],'product_count'=>$item['product_count'],'wei_page_count'=>$item['wei_page_count']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('weidian_store_list');
		
	}
	public function Weidian_page()
	{
		$data = array(
			'token'      => $this->token,
			'type'       => 'wei_page',
			'p'       => $_GET['p'] > 0 ? $_GET['p'] : 1,
			'store_id'       => $_GET['id'],
			'page_size'       => 5,
			'site_url'   => $this->config['site_url']
		);
		$sort_data = $data;
		$sort_data['salt'] = C('config.weidian_sign');
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		$data['sign_key'] = $sign_key;
		$data['request_time'] = time();
		$request_url = $this->config['weidian_url'].'/api/store.php';
		$resultArr = json_decode($this->curl_post($request_url,$data),true);
		if(!empty($resultArr['error_code'])){
			$this->error($resultArr['error_msg']);
		}
		if(empty($resultArr['stores'])){
			$this->error('您的微店没有添加店铺');
		}
		if(empty($resultArr['stores'][0]['wei_pages'])){
			$this->error('您的微店没有添加微页面');
		}
		$items = array();
		foreach ($resultArr['stores'][0]['wei_pages'] as $item){
			array_push($items,array('id' => $item['page_id'], 'name' => $item['name'], 'linkcode'=> $item['url'],'linkurl'=>'','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		
		$Page       = new Page($resultArr['stores'][0]['wei_page_count'],5);
		$this->assign('page', $Page->show());
		$this->display('weidian_page_list');
		
	}
	public function Weidian_product()
	{
		$data = array(
			'token'      => $this->token,
			'type'       => 'product',
			'p'       => $_GET['p'] > 0 ? $_GET['p'] : 1,
			'store_id'       => $_GET['id'],
			'page_size'       => 5,
			'site_url'   => $this->config['site_url']
		);
		$sort_data = $data;
		$sort_data['salt'] = C('config.weidian_sign');
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		$data['sign_key'] = $sign_key;
		$data['request_time'] = time();
		$request_url = $this->config['weidian_url'].'/api/store.php';
		$resultArr = json_decode($this->curl_post($request_url,$data),true);
		if(!empty($resultArr['error_code'])){
			$this->error($resultArr['error_msg']);
		}
		if(empty($resultArr['stores'])){
			$this->error('您的微店没有添加店铺');
		}
		if(empty($resultArr['stores'][0]['products'])){
			$this->error('您的微店没有添加商品');
		}
		$items = array();
		foreach ($resultArr['stores'][0]['products'] as $item){
			array_push($items,array('id' => $item['product_id'], 'name' => $item['name'], 'linkcode'=> $item['url'],'linkurl'=>'','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		
		$Page       = new Page($resultArr['stores'][0]['product_count'],5);
		$this->assign('page', $Page->show());
		$this->display('weidian_product_list');
		
	}
	public function Meal()
	{
		$this->assign('moduleName', $this->modules['Meal']);
		$db = M('Merchant_store');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$where['status'] = 1;
		$where['have_meal'] = 1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('store_id DESC')->select();
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Food/shop', array('token' => $this->token, 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id'], 'otherwc' => 1), true, false, true)),'linkurl'=>'','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
		
	}


	public function Appoint()
	{
		$this->assign('moduleName', $this->modules['Appoint']);
		$db = M('Appoint');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$where['check_status'] = 1;

		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('appoint_id DESC')->select();
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
			array_push($items,array('id' => $item['appoint_id'], 'name' => $item['appoint_name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Appoint/detail', array('appoint_id' => $item['appoint_id']), true, false, true)),'linkurl'=>'','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');

	}


	public function Meal_content()
	{
		$this->assign('moduleName', $this->modules_content['Meal_content']);
		$db = M('Merchant_store');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$where['status'] = 1;
		$where['have_meal'] = 1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('store_id DESC')->select();
		$items = array();
		$store_image_class = new store_image();
		foreach ($list as $item){
			$images = $store_image_class->get_allImage_by_path($item['pic_info']);
			$img_url = $images[0];
			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Food/shop', array('token' => $this->token, 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id'], 'otherwc' => 1), true, false, true)),'linkurl'=>'','keyword' => $item['name'],'img_url'=>$img_url));
		}

		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('content_detail');

	}

	public function Mer_lottery()
	{
		$this->assign('moduleName', $this->modules_content['Mer_lottery']);

		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$token = $this->merchant_session['mer_id'];
		$where['status'] = 1;
		$where['have_meal'] = 1;
		$count      = D("Lottery")->field(true)->where(array('token' =>  $this->merchant_session['mer_id'], 'statdate' => array('lt', time()), 'enddate' => array('gt', time())))->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		//, 'statdate' => array('lt', time()), 'enddate' => array('gt', time()))
		$list = D("Lottery")->field(true)->where(array('token' =>  $this->merchant_session['mer_id']))->limit($Page->firstRow.','.$Page->listRows)->select();
		//dump($list);die;
		$items = array();
		foreach ($list as $lottery){
			switch ($lottery['type']){
				case 1:
					$url = $this->config['site_url'] . "/wap.php?c=Lottery&a=index&wxscan=1&token={$token}&id={$lottery['id']}";
					break;
				case 2:
					$url = $this->config['site_url'] . "/wap.php?c=Guajiang&a=index&wxscan=1&token={$token}&id={$lottery['id']}";
					break;
				case 3:
					$url =$this->config['site_url'] . "/wap.php?c=Coupon&a=index&wxscan=1&token={$token}&id={$lottery['id']}";
					break;
				case 4:
					$url = $this->config['site_url'] . "/wap.php?c=LuckyFruit&a=index&wxscan=1&token={$token}&id={$lottery['id']}";
					break;
				case 5:
					$url = $this->config['site_url'] . "/wap.php?c=GoldenEgg&a=index&wxscan=1&token={$token}&id={$lottery['id']}";
					break;
			}
			array_push($items,array('id' => $lottery['id'], 'name' => $lottery['keyword'], 'linkcode'=> $url,'linkurl'=>'','keyword' => $lottery['title'],'img_url'=> $this->config['site_url'] . $lottery['starpicurl']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('content_detail');

	}

	public function PadMeal()
	{
		$this->assign('moduleName', $this->modules['Meal']);
		$db = M('Merchant_store');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$where['status'] = 1;
		$where['have_meal'] = 1;
		$where['store_type'] = array('neq',2);
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('store_id DESC')->select();
		// dump($list);
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Food/pad', array('mer_id' => $item['mer_id'], 'store_id' => $item['store_id'], 'otherwc' => 1), true, false, true)),'linkurl'=>'','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
		
	}
	public function Takeout()
	{
		$this->assign('moduleName', $this->modules['Takeout']);
		$db = M('Merchant_store');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$where['status'] = 1;
		$where['have_shop'] = 1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('store_id DESC')->select();
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Takeout/menu', array('token' => $this->token, 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id'], 'otherwc' => 1), true, false, true)),'linkurl'=>'','keyword' => $item['name']));
			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> $this->config['site_url'] . "/wap.php?c=Shop&a=index&shop-id={$item['store_id']}",'linkurl'=>'','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
		
	}
	
	public function Article()
	{
		$this->assign('moduleName', $this->modules['Article']);
		$db = M('Image_text');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('pigcms_id DESC')->select();
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
			array_push($items,array('id' => $item['pigcms_id'], 'name' => $item['title'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Article&a=index&imid=' . $item['pigcms_id'],'linkurl'=>'','keyword' => $item['title']));//str_replace('merchant.php', 'wap.php', U('Wap/Article/index', array('imid' => $item['pigcms_id']), true, false, true))
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
		
	}

	public function Article_content()
	{
		$this->assign('moduleName', $this->modules_content['Article_content']);
		$db = M('Image_text');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('pigcms_id DESC')->select();
		$items = array();
		foreach ($list as $item){
			$img_url = $this->config['site_url'].$item['cover_pic'];
			array_push($items,array('id' => $item['pigcms_id'], 'name' => $item['title'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Article&a=index&imid=' . $item['pigcms_id'],'linkurl'=>'','keyword' => $item['title'],'img_url'=>$img_url));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('content_detail');

	}
	
	public function Group()
	{
		$this->assign('moduleName', $this->modules['Group']);
		$db = M('Group');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$where['type'] = array('lt', 3);
		$where['end_time'] = array('gt', time());
		$where['begin_time'] = array('lt', time());
		$where['status'] = 1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('group_id DESC')->select();
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id' => $item['group_id'], 'name' => $item['s_name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/Group/detail', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'group_id' => $item['group_id']))),'linkurl'=>'','keyword' => $item['s_name']));
// 			array_push($items,array('id' => $item['group_id'], 'name' => $item['s_name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Group/detail', array('token' => $this->token, 'mer_id' => $item['mer_id'], 'group_id' => $item['group_id'], 'otherwc' => 1), true, false, true)),'linkurl'=>'','keyword' => $item['s_name']));
			array_push($items,array('id' => $item['group_id'], 'name' => $item['s_name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Group/detail', array('pin_num' => 0, 'group_id' => $item['group_id']), true, false, true)),'linkurl'=>'','keyword' => $item['s_name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
		
	}

	public function Group_content()
	{
		$this->assign('moduleName', $this->modules_content['Group_content']);
		$db = M('Group');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$where['type'] = array('lt', 3);
		$where['end_time'] = array('gt', time());
		$where['begin_time'] = array('lt', time());
		$where['status'] = 1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('group_id DESC')->select();
		$items = array();
		$group_image_class = new group_image();
		foreach ($list as $item){
			$tmp_pic_arr = explode(';',$item['pic']);
			$img_url = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
			array_push($items,array('id' => $item['group_id'], 'name' => $item['s_name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Group/detail', array('pin_num' => 0, 'group_id' => $item['group_id']), true, false, true)),'linkurl'=>'','keyword' => $item['name'],'img_url'=>$img_url));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('content_detail');

	}

	public function Classify()
	{
		$pid = (int)$_GET['pid'];
		$this->assign('moduleName', $this->modules['Classify']);
		$db = M('Classify');
		$where = $this->where;
		if ($pid != NULL) {
			$where['fid'] = $pid;
			$count      = $db->where($where)->count();
			$Page       = new Page($count,10);
			$show       = $Page->show();
			$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		} else {
			$where['fid'] = 0;
			$count      = $db->where($where)->count();
			$Page       = new Page($count,10);
			$show       = $Page->show();
			$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		}

		$items = array();
		foreach ($list as $k=>$item){
			$fid = $item['id'];
// 			array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'sublink'=> '{siteUrl}'. U('Merchant/Link/Classify', array('pid' => $item['id'], 'iskeyword' => 0)), 'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/Index/lists', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'classid' => $item['id']))),'linkurl'=>'','keyword'=>$item['keyword'],'sub'=>$db->where(array('token'=>$this->token,'fid'=>$fid))->field('id,name')->select()));
			array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'sublink'=> U('Merchant/Link/Classify', array('pid' => $item['id'], 'iskeyword' => 0), true, false, true), 'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Index/lists', array('token' => $this->token, 'classid' => $item['id']), true, false, true)),'linkurl'=>'','keyword'=>$item['keyword'],'sub'=>$db->where(array('token'=>$this->token,'fid'=>$fid))->field('id,name')->select()));
		}
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	
	public function Lottery()
	{
		$moduleName = 'Lottery';
		$this->assign('moduleName', $this->modules[$moduleName]);
		$db = M($moduleName);
		$where = $this->where;
		$where['type'] = 1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/Lottery/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'id' => $item['id']))),'linkurl'=>'','keyword'=>$item['keyword']));
			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Lottery/index', array('token' => $this->token, 'id' => $item['id']), true, false, true)),'linkurl'=>'','keyword'=>$item['keyword']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function Guajiang()
	{
		$moduleName = 'Guajiang';
		$this->assign('moduleName', $this->modules[$moduleName]);
		$db = M('Lottery');
		$where = $this->where;
		$where['type'] = 2;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/Guajiang/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'id' => $item['id']))),'linkurl'=>'','keyword'=>$item['keyword']));
			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Guajiang/index', array('token' => $this->token, 'id' => $item['id']), true, false, true)),'linkurl'=>'','keyword'=>$item['keyword']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	public function Coupon()
	{
		$moduleName = 'Coupon';
		$this->assign('moduleName', $this->modules[$moduleName]);
		$db = M('Lottery');
		$where = $this->where;
		$where['type'] = 3;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/Coupon/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'id' => $item['id']))),'linkurl'=>'','keyword'=>$item['keyword']));
			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Coupon/index', array('token' => $this->token, 'id' => $item['id']), true, false, true)),'linkurl'=>'','keyword'=>$item['keyword']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function LuckyFruit()
	{
		$moduleName = 'LuckyFruit';
		$this->assign('moduleName', $this->modules[$moduleName]);
		$db = M('Lottery');
		$where = $this->where;
		$where['type'] = 4;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/LuckyFruit/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'id' => $item['id']))),'linkurl'=>'','keyword'=>$item['keyword']));
			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/LuckyFruit/index', array('token' => $this->token, 'id' => $item['id']), true, false, true)),'linkurl'=>'','keyword'=>$item['keyword']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function GoldenEgg()
	{
		$moduleName = 'GoldenEgg';
		$this->assign('moduleName', $this->modules[$moduleName]);
		$db = M('Lottery');
		$where = $this->where;
		$where['type'] = 5;
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/GoldenEgg/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'id' => $item['id']))),'linkurl'=>'','keyword'=>$item['keyword']));
			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/GoldenEgg/index', array('token' => $this->token, 'id' => $item['id']), true, false, true)),'linkurl'=>'','keyword'=>$item['keyword']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	
	public function Wxapp()
	{
		$this->assign('moduleName', $this->modules['Wxapp']);
		// 得到营销活动列表
		$listType = array_keys($this->_accessListAction);
		$this->assign('listType',$listType);
		
		$listType = join(',', $listType);
		
		$db = M('Wxapp_list');
		$where = $this->where;
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$where['type'] = array('in',$listType);
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		
		$items = array();
		foreach ($list as $i=>$item){
			$item['type'] = $this->_accessListAction[$item['type']];
			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'type'=>$item['type'],'linkcode'=>$this->config['site_url'] . "/wap.php?c=Wxapp&a=location_href&id=".$item['pigcms_id'],'linkurl'=>'','keyword'=>$item['keyword']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		
		$this->display('detail');
	
	}
	public function ShopLink(){
		$this->assign('moduleName', $this->modules['ShopLink']);
		$db = M('Merchant_store');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$where['have_shop'] = 1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('store_id DESC')->select();
		$items = array();
		foreach ($list as $item){
			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Shop&a=classic_shop&shop_id=' . $item['store_id'],'linkurl'=>'','keyword' => $item['name']));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}
	public function Shop(){
		$this->assign('moduleName', $this->modules['Shop']);
		$db = M('Merchant_store');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$where['have_shop'] = 1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('store_id DESC')->select();
		$items = array();
		foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('merchant.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Shop&a=index&shop-id=' . $item['store_id'],'linkurl'=>'','keyword' => $item['name']));//str_replace('merchant.php', 'wap.php', U('Wap/Article/index', array('imid' => $item['pigcms_id']), true, false, true))
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

	public function Shop_content(){
		$this->assign('moduleName', $this->modules_content['Shop_content']);
		$db = M('Merchant_store');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];
		$where['have_shop'] = 1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('store_id DESC')->select();
		$items = array();
		$store_image_class = new store_image();
		foreach ($list as $item){
			$images = $store_image_class->get_allImage_by_path($item['pic_info']);
			$img_url = $images[0];
			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Shop&a=index&shop-id=' . $item['store_id'],'linkurl'=>'','keyword' => $item['name'],'img_url'=>$img_url));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('content_detail');
	}

	public function Appoint_content(){
		$this->assign('moduleName', $this->modules_content['Appoint_content']);
		$db = M('Appoint');
		$where = array();
		$where['mer_id'] = $this->merchant_session['mer_id'];

		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('appoint_id DESC')->select();
		$items = array();

		foreach ($list as $item){
			$img = str_replace(',','/',$item['pic']);
			$img_url = $this->config['site_url'].'/upload/appoint/'.$img;
			array_push($items,array('id' => $item['appoint_id'], 'name' => $item['appoint_name'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Appoint&a=detail&appoint_id=' . $item['appoint_id'],'linkurl'=>'','keyword' => $item['appoint_content'],'img_url'=>$img_url));
		}
		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('content_detail');
	}
	
    public function Mall()
    {
        $this->assign('moduleName', $this->modules['Mall']);
        $db = M('Merchant_store_shop');
        $sqlCount = 'SELECT count(1) AS cnt FROM ' . C('DB_PREFIX') . 'merchant_store AS s INNER JOIN ' . C('DB_PREFIX') . 'merchant_store_shop AS p ON s.store_id=p.store_id WHERE s.mer_id=' . $this->merchant_session['mer_id'] . ' AND s.have_shop=1 AND s.status=1 AND p.store_theme=1';
        
        $countResult = D()->query($sqlCount);
        $count = isset($countResult[0]['cnt']) ? intval($countResult[0]['cnt']) : 0;
        $Page = new Page($count, 5);
        $show = $Page->show();
        $sql = 'SELECT s.name, s.store_id FROM ' . C('DB_PREFIX') . 'merchant_store AS s INNER JOIN ' . C('DB_PREFIX') . 'merchant_store_shop AS p ON s.store_id=p.store_id WHERE s.mer_id=' . $this->merchant_session['mer_id'] . ' AND s.have_shop=1 AND s.status=1 AND p.store_theme=1 LIMIT ' . $Page->firstRow . ',' . $Page->listRows;
        $list = D()->query($sql);
        $items = array();
        foreach ($list as $item){
        	array_push($items, array('id' => $item['store_id'],
        	    'name' => $item['name'],
        	    'sub' => 1, 
        	    'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Mall/store', array('store_id' => $item['store_id'], 'otherwc' => 1), true, false, true)),
        	    'sublink' => U('Link/MallSort', array('store_id' => $item['store_id'])),
        	    'keyword' => $item['name']));
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }
	
    public function MallSort()
    {
        $this->assign('moduleName', $this->modules['Mall'] . '商品分类');
        
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $items = M('Shop_goods_sort')->field(true)->where(array('store_id' => $store_id))->order('`sort` DESC,`sort_id` ASC')->select();
        $s_list = array();
        foreach ($items as $value) {
            $s_list[$value['sort_id']] = $value;
        }
    
        foreach ($s_list as $row) {
            unset($s_list[$row['fid']]);
        }
        $count = count($s_list);
        $Page = new Page($count, 5);
        
        
        $list = array_slice($s_list, $Page->firstRow, $Page->listRows);
        $show = $Page->show();
        $items = array();
        foreach ($list as $item){
        	array_push($items, array('id' => $item['sort_id'],
        	    'name' => $item['sort_name'],
        	    'sub' => 0, 
        	    'linkcode'=> str_replace('merchant.php', 'wap.php', U('Wap/Mall/store', array('store_id' => $item['store_id'], 'sort_id' => $item['sort_id'], 'otherwc' => 1), true, false, true)),
        	    'keyword' => $item['name']));
        }
        $this->assign('list', $items);
        $this->assign('page', $show);
        $this->display('detail');
    }

	public function Coupon_list()
	{
		$this->assign('moduleName', $this->modules['Coupon']);
		$where['end_time'] = array('gt',time());
		$where['status'] = 1;
		$where['allow_new'] = 0;
		$where['mer_id'] =  $this->merchant_session['mer_id'];
		$db2 = D('Card_new_coupon');
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
    
	protected function curl_post($url,$data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
		return curl_exec($ch);
	}
}
?>