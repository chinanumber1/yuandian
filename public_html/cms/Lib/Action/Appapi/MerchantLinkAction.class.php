<?php
class MerchantLinkAction extends BaseAction{
    protected $merid;
    protected $token;
    protected $merchant;

    protected function _initialize(){
        parent::_initialize();
        $ticket = I('ticket', false);
        if($ticket){
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if($info){
                $condition_merchant['mer_id'] = $info['uid'];
            }
            $database_merchant = D('Merchant');
            $this->merchant = $database_merchant->field(true)->where($condition_merchant)->find();
            $this->mer_id = $this->merchant['mer_id'];
            $this->token = $this->merchant['mer_id'];
        }
        $this->config['have_group_name'] = isset($this->config['group_alias_name']) ? $this->config['group_alias_name'] : '团购';// 团购
        $this->config['have_meal_name'] = isset($this->config['meal_alias_name']) ? $this->config['meal_alias_name'] : '餐饮'; // 餐饮
        $this->config['have_shop_name'] = isset($this->config['shop_alias_name']) ? $this->config['shop_alias_name'] : '快店'; // 快店
        $this->config['have_appoint_name'] = isset($this->config['appoint_alias_name']) ? $this->config['appoint_alias_name'] : '预约'; // 预约

        $this->where = array('token' => $this->merchant['mer_id']);
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
            'coinTree'		=>'摇钱树',
            'collectword'		=>'集字游戏',
            'sentiment'		=>'谁是情圣',
            'frontPage'		=>'我要上头条',
            'test'		=>'趣味测试',
            'punish'		=>'惩罚台',
            'shakeLottery'		=>'摇一摇',
            'youSetDiscount'		=>'优惠接力',
            'popularity'		=>'人气冲榜',
            'problem'		=>'一战到底',
        );
        $this->arr = array('game','Red_packet');
    }

    public function insert(){
        $modules = $this->modules();
        $type = $_POST['module'];
        $this->returnCode(0,array('modules'=>$modules));
    }

    public function modules()
    {
        $t = array(
            array('module' => 'Home',		'linkcode' => str_replace('appapi.php', 'wap.php', U('Wap/Index/index', array('token' => $this->token), true, false, true)), 'name'=>'微站首页','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Home'],'askeyword'=>1),
            array('module' => 'Classify',	'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Index/lists', array('token' => $this->token), true, false, true)),'name'=>$this->modules['Classify'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>0),
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
            array('module' => 'Workstaff',		'linkcode' => str_replace('appapi.php', 'wap.php', U('Wap/Workerstaff/login', array(), true, false, true)), 'name'=>'技师中心','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Workerstaff'],'askeyword'=>1),
            array('module' => 'Card_new_coupon',		'linkcode' => str_replace('appapi.php', 'wap.php', U('Wap/My_card/merchant_coupon_list', array('mer_id'=>$this->merchant['mer_id']), true, false, true)), 'name'=>'商家会员卡优惠券','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
            array('module' => 'Card_new',		'linkcode' => str_replace('appapi.php', 'wap.php', U('Wap/My_card/merchant_card', array('mer_id'=>$this->merchant['mer_id']), true, false, true)), 'name'=>'商家会员卡','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
            array('module' => 'Store',		'linkcode' => str_replace('appapi.php', 'wap.php', U('Wap/My/pay', array('mer_id'=>$this->merchant['mer_id']), true, false, true)), 'name'=>$this->modules['Store'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
            array('module' => 'Appoint',		'linkcode' => '', 'name'=>$this->modules['Appoint'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
            array('module' => 'Storestaff',		'linkcode' => $this->config['site_url'].'/packapp/storestaff/index.html', 'name'=>'手机版店员中心','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
            array('module' => 'Deliver',		'linkcode' => $this->config['site_url'].'/packapp/deliver/index.html', 'name'=>'手机版配送员中心','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Card_new'],'askeyword'=>1),
        );
        if($this->config['is_open_weidian']){
            array_push($t,array('module' => 'Weidian',	'linkcode'=>'','name'=>$this->modules['Weidian'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1));
        }
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
           // $this->error($resultArr['error_msg']);
            $this->returnCode('20170119','',$resultArr['error_msg']);
        }
        if(empty($resultArr['stores'])){
            //$this->error('您的微店没有添加店铺');
            $this->returnCode('20170119','','您的微店没有添加店铺');
        }
        $items = array();
        foreach ($resultArr['stores'] as $item){
            array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> $item['url'],'linkurl'=>'','keyword' => $item['name'],'product_count'=>$item['product_count'],'wei_page_count'=>$item['wei_page_count'],'sub' => 0));
        }
        $arr['list']= $items;
        $this->returnCode(0,$arr);
       // $this->display('weidian_store_list');

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
        $arr['moduleName']= $this->modules['Meal'];
        $db = M('Merchant_store');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['status'] = 1;
        $where['have_meal'] = 1;
        $count      = $db->where($where)->count();
        $page       = I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('store_id DESC')->select();
        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
            array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Food/shop', array('token' => $this->token, 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id'], 'otherwc' => 1), true, false, true)),'linkurl'=>'','keyword' => $item['name'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);

    }


    public function Appoint()
    {
        $arr['moduleName']= $this->modules['Appoint'];
        $db = M('Appoint');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['check_status'] = 1;

        $count      = $db->where($where)->count();
        $page       = I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('appoint_id DESC')->select();
        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
            array_push($items,array('id' => $item['appoint_id'], 'name' => $item['appoint_name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Appoint/detail', array('appoint_id' => $item['appoint_id']), true, false, true)),'linkurl'=>'','keyword' => $item['name'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);

    }


    public function Meal_content()
    {
        $this->assign('moduleName', $this->modules_content['Meal_content']);

        $db = M('Merchant_store');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['status'] = 1;
        $where['have_meal'] = 1;
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('store_id DESC')->select();
        $items = array();
        $store_image_class = new store_image();
        foreach ($list as $item){
            $images = $store_image_class->get_allImage_by_path($item['pic_info']);
            $img_url = $images[0];
            array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Food/shop', array('token' => $this->token, 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id'], 'otherwc' => 1), true, false, true)),'linkurl'=>'','keyword' => $item['name'],'img_url'=>$img_url));
        }

        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);

    }

    public function Mer_lottery()
    {
        $this->assign('moduleName', $this->modules_content['Mer_lottery']);

        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $token = $this->merchant['mer_id'];
        $where['status'] = 1;
        $where['have_meal'] = 1;
        $count      = D("Lottery")->field(true)->where(array('token' =>  $this->merchant['mer_id'], 'statdate' => array('lt', time()), 'enddate' => array('gt', time())))->count();
        $page=I('pindex',1);
        //, 'statdate' => array('lt', time()), 'enddate' => array('gt', time()))
        $list = D("Lottery")->field(true)->where(array('token' =>  $this->merchant['mer_id']))->page($page,5)->select();
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
            array_push($items,array('id' => $lottery['id'], 'name' => $lottery['keyword'], 'linkcode'=> $url,'linkurl'=>'','keyword' => $lottery['title'],'img_url'=> $this->config['site_url'] . $lottery['starpicurl'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);

    }

    public function PadMeal()
    {
        $arr['moduleName']= $this->modules['Meal'];
        $db = M('Merchant_store');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['status'] = 1;
        $where['have_meal'] = 1;
        $where['store_type'] = array('neq',2);
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('store_id DESC')->select();
        // dump($list);
        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
            array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Food/pad', array('mer_id' => $item['mer_id'], 'store_id' => $item['store_id'], 'otherwc' => 1), true, false, true)),'linkurl'=>'','keyword' => $item['name'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);

    }
    public function Takeout()
    {
        $arr['moduleName']= $this->modules['Takeout'];
        $db = M('Merchant_store');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['status'] = 1;
        $where['have_shop'] = 1;
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('store_id DESC')->select();
        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Takeout/menu', array('token' => $this->token, 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id'], 'otherwc' => 1), true, false, true)),'linkurl'=>'','keyword' => $item['name']));
            array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> $this->config['site_url'] . "/wap.php?c=Shop&a=index&shop-id={$item['store_id']}",'linkurl'=>'','keyword' => $item['name'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);

    }

    public function Article()
    {

        $arr['moduleName']= $this->modules['Article'];
        $db = M('Image_text');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('pigcms_id DESC')->select();
        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
            array_push($items,array('id' => $item['pigcms_id'], 'name' => $item['title'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Article&a=index&imid=' . $item['pigcms_id'],'linkurl'=>'','keyword' => $item['title'],'sub' => 0));//str_replace('appapi.php', 'wap.php', U('Wap/Article/index', array('imid' => $item['pigcms_id']), true, false, true))
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);

    }

    public function Article_content()
    {
        $this->assign('moduleName', $this->modules_content['Article_content']);
        $db = M('Image_text');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('pigcms_id DESC')->select();
        $items = array();
        foreach ($list as $item){
            $img_url = $this->config['site_url'].$item['cover_pic'];
            array_push($items,array('id' => $item['pigcms_id'], 'name' => $item['title'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Article&a=index&imid=' . $item['pigcms_id'],'linkurl'=>'','keyword' => $item['title'],'img_url'=>$img_url));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);

    }

    public function Group()
    {

        $arr['moduleName']= $this->modules['Group'];
        $db = M('Group');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['type'] = array('lt', 3);
        $where['end_time'] = array('gt', time());
        $where['begin_time'] = array('lt', time());
        $where['status'] = 1;
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('group_id DESC')->select();
        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id' => $item['group_id'], 'name' => $item['s_name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/Group/detail', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'group_id' => $item['group_id']))),'linkurl'=>'','keyword' => $item['s_name']));
// 			array_push($items,array('id' => $item['group_id'], 'name' => $item['s_name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Group/detail', array('token' => $this->token, 'mer_id' => $item['mer_id'], 'group_id' => $item['group_id'], 'otherwc' => 1), true, false, true)),'linkurl'=>'','keyword' => $item['s_name']));
            array_push($items,array('id' => $item['group_id'], 'name' => $item['s_name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Group/detail', array('pin_num' => 0, 'group_id' => $item['group_id']), true, false, true)),'linkurl'=>'','keyword' => $item['s_name'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);

    }

    public function Group_content()
    {
        $this->assign('moduleName', $this->modules_content['Group_content']);
        $db = M('Group');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['type'] = array('lt', 3);
        $where['end_time'] = array('gt', time());
        $where['begin_time'] = array('lt', time());
        $where['status'] = 1;
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('group_id DESC')->select();
        $items = array();
        $group_image_class = new group_image();
        foreach ($list as $item){
            $tmp_pic_arr = explode(';',$item['pic']);
            $img_url = $group_image_class->get_image_by_path($tmp_pic_arr[0],'s');
            array_push($items,array('id' => $item['group_id'], 'name' => $item['s_name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Group/detail', array('pin_num' => 0, 'group_id' => $item['group_id']), true, false, true)),'linkurl'=>'','keyword' => $item['name'],'img_url'=>$img_url));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);

    }

    public function Classify()
    {
        $pid = (int)$_POST['pid'];

        $arr['moduleName']= $this->modules['Classify'];
        $db = M('Classify');
        $where = $this->where;

        if ($pid != NULL) {
            $where['fid'] = $pid;
            $count      = $db->where($where)->count();
            $page=I('pindex',1);
            $list=$db->where($where)->page($page,5)->order('id DESC')->select();
        } else {
            $where['fid'] = 0;
            $count      = $db->where($where)->count();
            $page=I('pindex',1);
            $list = $db->where($where)->page($page,5)->order('id DESC')->select();

        }

        $items = array();
        foreach ($list as $k=>$item){
            $fid = $item['id'];
            $sub = $db->where(array('token'=>$this->token,'fid'=>$fid))->field('id,name')->find();
            if(!empty($sub)){
                $tmp = array('pid'=>$item['id'],'name'=>$item['name'],
                    'linkurl'=> str_replace('appapi.php', 'wap.php', U('Wap/Index/lists', array('token' => $this->token, 'classid' => $item['id']), true, false, true)),
                    'keyword'=>$item['name'],
                    'module'=>'Classify',
                    'sub'=>1,

                );
            }else{
                $tmp = array('pid'=>$item['id'],'name'=>$item['name'],
                    'linkurl'=> str_replace('appapi.php', 'wap.php', U('Wap/Index/lists', array('token' => $this->token, 'classid' => $item['id']), true, false, true)),
                    'keyword'=>$item['name'],
                    'module'=>'Classify',
                    'sub'=>0,
                );
            }
            array_push($items,$tmp

            );

        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
    }

    public function Lottery()
    {
        $moduleName = 'Lottery';

        $arr['moduleName']= $this->modules['Lottery'];
        $db = M($moduleName);
        $where = $this->where;
        $where['type'] = 1;
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list=$db->where($where)->page($page,5)->order('id DESC')->select();

        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/Lottery/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'id' => $item['id']))),'linkurl'=>'','keyword'=>$item['keyword']));
            array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Lottery/index', array('token' => $this->token, 'id' => $item['id']), true, false, true)),'linkurl'=>'','keyword'=>$item['keyword'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
    }

    public function Guajiang()
    {
        $moduleName = 'Guajiang';

        $arr['moduleName']= $this->modules['Guajiang'];
        $db = M('Lottery');
        $where = $this->where;
        $where['type'] = 2;
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('id DESC')->select();
        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/Guajiang/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'id' => $item['id']))),'linkurl'=>'','keyword'=>$item['keyword']));
            array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Guajiang/index', array('token' => $this->token, 'id' => $item['id']), true, false, true)),'linkurl'=>'','keyword'=>$item['keyword'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
    }
    public function Coupon()
    {
        $moduleName = 'Coupon';

        $arr['moduleName']= $this->modules['Coupon'];
        $db = M('Lottery');
        $where = $this->where;
        $where['type'] = 3;
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('id DESC')->select();
        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/Coupon/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'id' => $item['id']))),'linkurl'=>'','keyword'=>$item['keyword']));
            array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Coupon/index', array('token' => $this->token, 'id' => $item['id']), true, false, true)),'linkurl'=>'','keyword'=>$item['keyword'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
    }

    public function LuckyFruit()
    {
        $moduleName = 'LuckyFruit';

        $arr['moduleName']= $this->modules['LuckyFruit'];
        $db = M('Lottery');
        $where = $this->where;
        $where['type'] = 4;
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('id DESC')->select();
        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/LuckyFruit/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'id' => $item['id']))),'linkurl'=>'','keyword'=>$item['keyword']));
            array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/LuckyFruit/index', array('token' => $this->token, 'id' => $item['id']), true, false, true)),'linkurl'=>'','keyword'=>$item['keyword'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
    }

    public function GoldenEgg()
    {
        $moduleName = 'GoldenEgg';

        $arr['moduleName']= $this->modules['GoldenEgg'];
        $db = M('Lottery');
        $where = $this->where;
        $where['type'] = 5;
        $count      = $db->where($where)->count();
        $page=I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('id DESC')->select();
        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/GoldenEgg/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'id' => $item['id']))),'linkurl'=>'','keyword'=>$item['keyword']));
            array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/GoldenEgg/index', array('token' => $this->token, 'id' => $item['id']), true, false, true)),'linkurl'=>'','keyword'=>$item['keyword'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
    }

    public function Wxapp()
    {

        $arr['moduleName']= $this->modules['Wxapp'];
        // 得到营销活动列表
        $listType = array_keys($this->_accessListAction);
        $this->assign('listType',$listType);

        $listType = join(',', $listType);

        $db = M('Wxapp_list');
        $where = $this->where;
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['type'] = array('in',$listType);
        $count      = $db->where($where)->count();
        $page = I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('id DESC')->select();

        $items = array();
        foreach ($list as $i=>$item){
            $item['type'] = $this->_accessListAction[$item['type']];
            array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'type'=>$item['type'],'linkcode'=>$this->config['site_url'] . "/wap.php?c=Wxapp&a=location_href&id=".$item['pigcms_id'],'linkurl'=>'','keyword'=>$item['keyword'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);

    }
    public function ShopLink(){

        $arr['moduleName']= $this->modules['ShopLink'];
        $db = M('Merchant_store');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['have_shop'] = 1;
        $count      = $db->where($where)->count();
        $page = I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('store_id DESC')->select();
        $items = array();
        foreach ($list as $item){
            array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Shop&a=classic_shop&shop_id=' . $item['store_id'],'linkurl'=>'','keyword' => $item['name'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
    }
    public function Shop(){

        $arr['moduleName']= $this->modules['Shop'];
        $db = M('Merchant_store');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['have_shop'] = 1;
        $count      = $db->where($where)->count();
        $page = I('pindex',1);
        $list = $db->where($where)->page($page,5)->order('store_id DESC')->select();
        $items = array();
        foreach ($list as $item){
// 			array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> str_replace('appapi.php', 'wap.php', '{siteUrl}'. U('Wap/Meal/index', array('token' => $this->token, 'wecha_id' => '{wechat_id}', 'mer_id' => $item['mer_id'], 'store_id' => $item['store_id']))),'linkurl'=>'','keyword' => $item['name']));
            array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Shop&a=index&shop-id=' . $item['store_id'],'linkurl'=>'','keyword' => $item['name'],'sub' => 0));//str_replace('appapi.php', 'wap.php', U('Wap/Article/index', array('imid' => $item['pigcms_id']), true, false, true))
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
    }

    public function Shop_content(){
        $this->assign('moduleName', $this->modules_content['Shop_content']);
        $db = M('Merchant_store');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];
        $where['have_shop'] = 1;
        $count      = $db->where($where)->count();
        $Page       = new Page($count,5);
        $show       = $Page->show();
        $list = $db->where($where)->page($page,5)->order('store_id DESC')->select();
        $items = array();
        $store_image_class = new store_image();
        foreach ($list as $item){
            $images = $store_image_class->get_allImage_by_path($item['pic_info']);
            $img_url = $images[0];
            array_push($items,array('id' => $item['store_id'], 'name' => $item['name'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Shop&a=index&shop-id=' . $item['store_id'],'linkurl'=>'','keyword' => $item['name'],'img_url'=>$img_url));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
    }

    public function Appoint_content(){
        $this->assign('moduleName', $this->modules_content['Appoint_content']);
        $db = M('Appoint');
        $where = array();
        $where['mer_id'] = $this->merchant['mer_id'];

        $count      = $db->where($where)->count();
        $Page       = new Page($count,5);
        $show       = $Page->show();
        $list = $db->where($where)->page($page,5)->order('appoint_id DESC')->select();
        $items = array();

        foreach ($list as $item){
            $img = str_replace(',','/',$item['pic']);
            $img_url = $this->config['site_url'].'/upload/appoint/'.$img;
            array_push($items,array('id' => $item['appoint_id'], 'name' => $item['appoint_name'], 'linkcode'=> $this->config['site_url'] . '/wap.php?g=Wap&c=Appoint&a=detail&appoint_id=' . $item['appoint_id'],'linkurl'=>'','keyword' => $item['appoint_content'],'img_url'=>$img_url));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
    }

    public function Mall()
    {
        $arr['moduleName']= $this->modules['Mall'];
        $db = M('Merchant_store_shop');
        $sqlCount = 'SELECT count(1) AS cnt FROM ' . C('DB_PREFIX') . 'merchant_store AS s INNER JOIN ' . C('DB_PREFIX') . 'merchant_store_shop AS p ON s.store_id=p.store_id WHERE s.mer_id=' . $this->merchant['mer_id'] . ' AND s.have_shop=1 AND s.status=1 AND p.store_theme=1';

        $countResult = D()->query($sqlCount);
        $count = isset($countResult[0]['cnt']) ? intval($countResult[0]['cnt']) : 0;
//        $Page = new Page($count, 5);
//        $show = $Page->show();
//        $sql = 'SELECT s.name, s.store_id FROM ' . C('DB_PREFIX') . 'merchant_store AS s INNER JOIN ' . C('DB_PREFIX') . 'merchant_store_shop AS p ON s.store_id=p.store_id WHERE s.mer_id=' . $this->merchant['mer_id'] . ' AND s.have_shop=1 AND s.status=1 AND p.store_theme=1 LIMIT ' . $Page->firstRow . ',' . $Page->listRows;
//        $list = D()->query($sql);
        $page = I('pindex',1);
        $list =  D('Merchant_store')->join('as s INNER JOIN '.C('DB_PREFIX').'merchant_store_shop AS p ON s.store_id= p.store_id')->where('s.mer_id=' . $this->merchant['mer_id'] . ' AND s.have_shop=1 AND s.status=1 AND p.store_theme=1')->page($page,5)->select();
        $items = array();
        foreach ($list as $item){
            array_push($items, array('id' => $item['store_id'],
                'name' => $item['name'],
                'sub' => 1,
                'linkcode'=> str_replace('appapi.php', 'wap.php', U('Wap/Mall/store', array('store_id' => $item['store_id'], 'otherwc' => 1), true, false, true)),
                'sublink' => U('Link/MallSort', array('store_id' => $item['store_id'])),
                'keyword' => $item['name'],
                'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
    }



    public function Coupon_list()
    {
        $arr['moduleName']= $this->modules['Coupon'];
        $where['end_time'] = array('gt',time());
        $where['status'] = 1;
        $where['allow_new'] = 0;
        $where['mer_id'] =  $this->merchant['mer_id'];
        $db2 = D('Card_new_coupon');
        $count = $db2->where($where)->count();

        $page = I('pindex',1);
        $list = $db2->where($where)->page($page,5)->order(' `coupon_id` DESC')->select();

        $items = array();
        foreach ($list as $item){
            array_push($items, array('id' => $item['id'], 'sub' => 0, 'name' => $item['name'], 'linkcode'=>$item['coupon_id'],'sublink' => '','keyword' => $item['name'],'sub' => 0));
        }
        $arr['data']	=	isset($items)?$items:array();
        $arr['all']		=	$count;
        $arr['page'] 	=	ceil($arr['all']/10);

        $this->returnCode(0,$arr);
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
