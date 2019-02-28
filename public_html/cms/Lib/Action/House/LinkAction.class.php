<?php
class LinkAction extends BaseAction{
    public $modules;

    public function _initialize() 
    {
            parent::_initialize();

            $this->modules = array(
                    'Member' => '会员中心', 
                    'HousevillageList' => '小区列表',
                    'Housevillage'=>'小区首页',
                    'HousevillageNewsCatelist'=>'小区新闻分类',
                    'HousevillageNewslist'=>'小区新闻',
                    'ActiveGroupList'=>'推荐'.$this->config['group_alias_name'],
                    'ActiveMealList'=>"推荐".$this->config['meal_alias_name'],
                    'ActiveAppointList'=>'推荐'.$this->config['appoint_alias_name'],
                    'HousevillageMy'=>"个人中心",
                    'HouseserviceserviceCategory'=>'便民服务',
                    'HousevillagePayProperty'=>'缴物业费',
                    'HousevillagePayWater'=>'缴水费',
                    'HousevillagePayElectric'=>'缴电费',
                    'HousevillagePayGas'=>'缴燃气费',
                    'HousevillagePayPark'=>'缴停车费',
                    'HousevillagePayCustom'=>'自定义缴费',
                    'HousevillageMyRepair'=>"在线报修",
                    'HousevillageMyUtilities'=>'水电煤上报',
                    'HousevillageMyPaylists'=>'缴费订单列表',
                    'HousevillageMyRepairlists'=>'在线报修列表',
                    'HousevillageMyUtilitieslists'=>'水电煤上报列表',
                    'HousevillageMySuggest'=>'投诉建议',
                    'HousevillageMyBindFamilyAdd'=>'绑定家属',
                    'HousevillageMyBindFamilyList'=>'绑定家属列表',
                    'HousephoneIndex'=>'常用电话列表',
                    'HouseRide'=>'小区顺风车',
                    'PlatformPayWater'=>'平台缴水费',
                    'PlatformPayGas'=>'平台缴燃气费',
                    'PlatformPayElectric'=>'平台缴电费',
                    'HousevillageMySuggestist'=>'投诉列表',
                    'HousevillageActivity'=>'社区活动',
                    'HousePay'=>'社区缴费',
                    'Crowdsourcing' => '众包',
					'HousevillageManager' => '小区管家',
					'HousevillageExpress' => '快递代收',
					'MyVillage' => '我的小区',
					'VisitorRegistration' => '访客登记',
                    'ActiveStoreList'=>'推荐快店',
            );
    }
    
    
    public function insert()
    {
            $modules = $this->modules();
            $this->assign('modules', $modules);
            $this->display();
    }
    
    private function modules()
	{
        $village_id = $this->house_session['village_id'];
		$t = array(
			array('module' => 'Home', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/Home/index', '', true, false, true)), 'name'=>'平台首页','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Home'],'askeyword'=>1),
			array('module' => 'Housevillage', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['Housevillage'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HousevillageList', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_list', '', true, false, true)),'name'=>$this->modules['HousevillageList'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HouseRide', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/Ride/ride_list', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HouseRide'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
            array('module' => 'HousephoneIndex', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/Housephone/index', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousephoneIndex'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HousevillageNewsCatelist', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_newslist', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillageNewsCatelist'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'Member', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/My/index', '', true, false, true)),'name'=>$this->modules['Member'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'ActiveGroupList', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_grouplist', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['ActiveGroupList'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'ActiveMealList', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_meallist', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['ActiveMealList'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'ActiveAppointList', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_appointlist', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['ActiveAppointList'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HousevillageMy', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_my', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillageMy'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			//array('module' => 'HousevillagePay', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'property','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayProperty'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			//array('module' => 'HousevillagePay', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'water','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayWater'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			//array('module' => 'HousevillagePay', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'electric','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayElectric'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			//array('module' => 'HousevillagePay', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'gas','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayGas'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			//array('module' => 'HousevillagePay', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'park','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayPark'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
          //  array('module' => 'HousevillagePay', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'custom','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayCustom'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),

			array('module' => 'HousevillageMyRepair', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_my_repair', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillageMyRepair'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HousevillageMyUtilities', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_my_utilities', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillageMyUtilities'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HousevillageMyPaylists', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_my_paylists', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillageMyPaylists'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HousevillageMyRepairlists', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_my_repairlists', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillageMyRepairlists'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HousevillageMyUtilitieslists', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_my_utilitieslists', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillageMyUtilitieslists'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HousevillageMySuggest', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_my_suggest', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillageMySuggest'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HousevillageMyBindFamilyAdd', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_my_bind_family_add', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillageMyBindFamilyAdd'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HousevillageMyBindFamilyList', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_my_bind_family_list', array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillageMyBindFamilyList'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HouseserviceserviceCategory', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/Houseservice/index',array('village_id'=>$village_id) , true, false, true)),'name'=>$this->modules['HouseserviceserviceCategory'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
            array('module' => 'HousevillageMySuggestlist', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_my_suggestlist',array('village_id'=>$village_id) , true, false, true)),'name'=>$this->modules['HousevillageMySuggestist'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
            array('module' => 'HousevillageActivity', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_activitylist',array('village_id'=>$village_id) , true, false, true)),'name'=>$this->modules['HousevillageActivity'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
            array('module' => 'HousePay', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_my_pay',array('village_id'=>$village_id) , true, false, true)),'name'=>$this->modules['HousePay'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),

			array('module' => 'HousevillageManager', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_manager_list',array('village_id'=>$village_id) , true, false, true)),'name'=>$this->modules['HousevillageManager'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
			array('module' => 'HousevillageExpress', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/Library/express_service_list',array('village_id'=>$village_id) , true, false, true)),'name'=>$this->modules['HousevillageExpress'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		    array('module' => 'MyVillage', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/my_village_list', null, true, false, true)),'name'=>$this->modules['MyVillage'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		    array('module' => 'VisitorRegistration', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/Library/visitor_list',array('village_id'=>$village_id), true, false, true)),'name'=>$this->modules['VisitorRegistration'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
            array('module' => 'ActiveStoreList', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/shop', array('village_id'=>$village_id.'#cat-all'), true, false, true)),'name'=>$this->modules['ActiveStoreList'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
        );
// wap.php?g=Wap&c=House&a=shop&village_id=1
		//$systemLiveServiceTypeArr = explode(',',$this->config['live_service_type']);
		//if(in_array('water',$systemLiveServiceTypeArr)){
		//	$t[] = array('module' => 'PlatformPayWater', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Lifeservice/query',array('type'=>'water'), true, false, true)),'name'=>$this->modules['PlatformPayWater'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		//}
		//if(in_array('electric',$systemLiveServiceTypeArr)){
		//	$t[] = array('module' => 'PlatformPayElectric', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Lifeservice/query',array('type'=>'electric'), true, false, true)),'name'=>$this->modules['PlatformPayElectric'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		//}
		//if(in_array('gas',$systemLiveServiceTypeArr)){
		//	$t[] = array('module' => 'PlatformPayGas', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Lifeservice/query',array('type'=>'gas'), true, false, true)),'name'=>$this->modules['PlatformPayGas'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
		//}

        if($this->config['crowdsourcing_is']){
            $t[] = array('module' => 'Crowdsourcing', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/Crowdsourcing/index', array('village_id'=>$_SESSION['house']['village_id']), true, false, true)),'name'=>$this->modules['Crowdsourcing'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
        }
		
		return $t;
	}
        
        
        public function HouseserviceserviceCategory(){
                $id = $_GET['id'] + 0;
                if($id){
                    $this->assign('moduleName', '便民服务子分类');
                    $where['parent_id'] = $id;
                }else{
                    $this->assign('moduleName', '便民服务分类');
                    $where['parent_id'] = 0;
                }
                
                $village_id = $this->house_session['village_id'];
		$where['village_id'] = $village_id;
                $where['status']  = 1;
		$db = D('House_service_category');
		$count      = $db->where($where)->count();
		$Page       = new Page($count, 5);
		$show       = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
                
                $items = array();
                if($list){
                    foreach ($list as $item){
                        if (!$item['parent_id']) {
                                array_push($items, array('id' => $item['id'], 'sub' => 1, 'name' => $item['cat_name'],'sublink' => U('Link/HouseserviceserviceCategory', array('id' => $item['id']), true, false, true),'keyword' => $item['cat_name']));
                            }else{
                                if(!$item['cat_url']){
                                    array_push($items, array('id' => $item['id'], 'sub' => 1, 'name' => $item['cat_name'], 'linkcode'=> str_replace('shequ.php', 'wap.php', U('Wap/Houseservice/cat_list', array('village_id' => $village_id ,'id'=>$item['id']), true, false, true)),'sublink' => U('Link/HouseserviceserviceCategory', array('id' => $item['id']), true, false, true),'keyword' => $item['cat_name']));
                                }else{
                                    array_push($items, array('id' => $item['id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> str_replace('shequ.php', 'wap.php', U('Wap/Houseservice/cat_list', array('village_id' => $village_id ,'id'=>$item['id']), true, false, true)),'sublink' => U('Link/HouseserviceserviceCategory', array('id' => $item['id']), true, false, true),'keyword' => $item['cat_name']));
                            }
                                }
                               
                        }
                }else{
                    $this->assign('moduleName', '便民服务便民详情');
					
                    $db = D('House_service_info');
                    $Map['status'] = 1;
                    $Map['village_id'] = $village_id;
                    $Map['cat_id'] = $id;
                    $list = $db->where($Map)->limit($Page->firstRow.','.$Page->listRows)->select();
                    foreach ($list as $item){
                        $tmpLbs = wapLbsTranform($item['url'],array('title'=>$item['title'],'pic'=>$item['img_path'],'phone'=>$item['phone']),true);
                        
                        if($tmpLbs){
                            array_push($items, array('id' => $item['id'], 'sub' => 0, 'name' => $item['title'], 'linkcode'=> $tmpLbs['url'],'sublink' => $tmpLbs['url'],'keyword' => $item['title']));
                        }
                       
                    }
                }

                $this->assign('list', $items);
		$this->assign('page', $show);
		$this->display('detail');
	}

        //社区缴费
        public function HousePay(){
            $this->assign('moduleName', $this->modules['HousePay']);
            $village_id = $this->house_session['village_id'];
            $items = array();
            array_push($items, array('id' => 1, 'sub' => 0,'linkcode' =>str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'property','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayProperty'], 'keyword' => $this->modules['HousevillagePayProperty']));
            array_push($items, array('id' =>2, 'sub' => 0, 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'water','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayWater'], 'keyword' => $this->modules['HousevillagePayWater']));
            array_push($items, array('id' => 3, 'sub' => 0,'linkcode' =>str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'electric','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayElectric'], 'keyword' => $this->modules['HousevillagePayElectric']));
            array_push($items, array('id' => 4, 'sub' => 0,'linkcode' =>str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'gas','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayGas'], 'keyword' => $this->modules['HousevillagePayGas']));
            array_push($items, array('id' =>5, 'sub' => 0, 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'park','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayPark'], 'keyword' => $this->modules['HousevillagePayPark']));
            array_push($items, array('id' => 6, 'sub' => 0,'linkcode' =>str_replace('shequ.php', 'wap.php', U('Wap/House/village_pay', array('type'=>'custom','village_id'=>$village_id), true, false, true)),'name'=>$this->modules['HousevillagePayCustom'], 'keyword' => $this->modules['HousevillagePayCustom']));

            $systemLiveServiceTypeArr = explode(',',$this->config['live_service_type']);
            if(in_array('water',$systemLiveServiceTypeArr)){
            	//$t[] = array('module' => 'PlatformPayWater', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Lifeservice/query',array('type'=>'water'), true, false, true)),'name'=>$this->modules['PlatformPayWater'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
                array_push($items, array('id' => 7, 'sub' => 0,'linkcode' =>str_replace('shequ.php', 'wap.php',  U('Lifeservice/query',array('type'=>'water'), true, false, true)),'name'=>$this->modules['PlatformPayWater'], 'keyword' => $this->modules['PlatformPayWater']));

            }
            if(in_array('electric',$systemLiveServiceTypeArr)){
                //$t[] = array('module' => 'PlatformPayElectric', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Lifeservice/query',array('type'=>'electric'), true, false, true)),'name'=>$this->modules['PlatformPayElectric'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
                array_push($items, array('id' => 7, 'sub' => 0,'linkcode' =>str_replace('shequ.php', 'wap.php',  U('Lifeservice/query',array('type'=>'electric'), true, false, true)),'name'=>$this->modules['PlatformPayElectric'], 'keyword' => $this->modules['PlatformPayElectric']));
            }
            if(in_array('gas',$systemLiveServiceTypeArr)){
                //$t[] = array('module' => 'PlatformPayGas', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Lifeservice/query',array('type'=>'gas'), true, false, true)),'name'=>$this->modules['PlatformPayGas'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);
                array_push($items, array('id' => 7, 'sub' => 0,'linkcode' =>str_replace('shequ.php', 'wap.php',  U('Lifeservice/query',array('type'=>'gas'), true, false, true)),'name'=>$this->modules['PlatformPayGas'], 'keyword' => $this->modules['PlatformPayGas']));
            }
            $this->assign('list', $items);
            $this->display('detail');
        }


        public function ActiveStoreList(){
            $this->assign('moduleName', $this->modules['ActiveStoreList']);
            $village_id = $this->house_session['village_id'];
            $database_house_village_appoint = D('House_village_store');
            
            $now_time = $_SERVER['REQUEST_TIME'];
            $condition_table = array(C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_store'=>'hvs');
            $condition_field = "`s`.`name` AS `store_name`,`m`.`name` AS `merchant_name`,`s`.*,`m`.*,`hvs`.*";
            $condition_where = "`s`.`mer_id`=`m`.`mer_id` AND `m`.`status`='1' AND `hvs`.`store_id`=`s`.`store_id` AND `hvs`.`village_id`='$village_id'";

            // $store_list = D('')->field('`s`.`name` AS `store_name`,`m`.`name` AS `merchant_name`,`s`.*,`m`.*,`hvs`.*')->table(array(C('DB_PREFIX').'merchant_store'=>'s',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_store'=>'hvs'))->where("`s`.`mer_id`=`m`.`mer_id` AND `hvs`.`store_id`=`s`.`store_id` AND `hvs`.`village_id`='$village_id'")->order('`hvs`.`sort` DESC,`hvs`.`pigcms_id` DESC')->limit($limit)->select();

            $count = D('')->table($condition_table)->where($condition_where)->count();
            
            $Page       = new Page($count, 5);
            $show       = $Page->show();

            $list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->limit($Page->firstRow.','.$Page->listRows)->select();
// dump($list);
            $items = array();
            foreach ($list as $item){
                if($item!=''){
                    array_push($items, array('id' => $item['store_id'], 'sub' => 0, 'name' => $item['store_name'], 'linkcode'=> str_replace('shequ.php', 'wap.php',$item['url']),'sublink' => $item['url'],'keyword' => $item['name']));
                }else {
                    array_push($items, array('id' => $item['appoint_id'], 'sub' => 0, 'name' => $item['appoint_name'], 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/Appoint/detail', array('appoint_id' => $item['appoint_id']), true, false, true)), 'sublink' => U('Wap/Appoint/detail', array('appoint_id' => $item['appoint_id']), true, false, true), 'keyword' => $item['appoint_name']));
                }
            }
            
            $this->assign('list', $items);
            $this->assign('page', $show);
            $this->display('detail');
        }

        
        public function ActiveAppointList(){
            $this->assign('moduleName', $this->modules['ActiveAppointList']);
            $village_id = $this->house_session['village_id'];
            $database_house_village_appoint = D('House_village_appoint');
            
            $now_time = $_SERVER['REQUEST_TIME'];
            $condition_table = array(C('DB_PREFIX').'appoint'=>'a',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_appoint'=>'hva');
            $condition_field = "`m`.`name` AS `merchant_name`,`a`.*,`m`.*,`hva`.*";
            $condition_where = "`a`.`mer_id`=`m`.`mer_id` AND `a`.`check_status`='1' AND `a`.`appoint_status`='0' AND `m`.`status`='1' AND `a`.`start_time`<'$now_time' AND `a`.`end_time`>'$now_time' AND `hva`.`appoint_id`=`a`.`appoint_id` AND `hva`.`village_id`='$village_id'";
            $count = D('')->table($condition_table)->where($condition_where)->count();
            
            $Page       = new Page($count, 5);
            $show       = $Page->show();

            $list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->limit($Page->firstRow.','.$Page->listRows)->select();
            
            $items = array();
            foreach ($list as $item){
                if($item!=''){
                    array_push($items, array('id' => $item['store_id'], 'sub' => 0, 'name' => $item['name'], 'linkcode'=> str_replace('shequ.php', 'wap.php',$item['url']),'sublink' => $item['url'],'keyword' => $item['name']));
                }else {
                    array_push($items, array('id' => $item['appoint_id'], 'sub' => 0, 'name' => $item['appoint_name'], 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/Appoint/detail', array('appoint_id' => $item['appoint_id']), true, false, true)), 'sublink' => U('Wap/Appoint/detail', array('appoint_id' => $item['appoint_id']), true, false, true), 'keyword' => $item['appoint_name']));
                }
            }
            
            $this->assign('list', $items);
            $this->assign('page', $show);
            $this->display('detail');
        }
        
        
        public function ActiveGroupList(){
            $this->assign('moduleName', $this->modules['ActiveGroupList']);
            $village_id = $this->house_session['village_id'];
            
            $now_time = $_SERVER['REQUEST_TIME'];
            $condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'merchant'=>'m',C('DB_PREFIX').'house_village_group'=>'hvg');
            $condition_field = "`g`.`name` AS `group_name`,`m`.`name` AS `merchant_name`,`g`.*,`m`.*,`hvg`.`sort`,`hvm`.`url`";
            $condition_where = "`g`.`mer_id`=`m`.`mer_id` AND `g`.`status`='1' AND `m`.`status`='1' AND `g`.`type`='1' AND `g`.`begin_time`<'$now_time' AND `g`.`end_time`>'$now_time' AND `hvg`.`group_id`=`g`.`group_id` AND `hvg`.`village_id`='$village_id'";
            $count = D('')->field($condition_field)->table($condition_table)->where($condition_where)->count();
            $Page       = new Page($count, 5);
            $show       = $Page->show();

            $list = D('')->table($condition_table)->where($condition_where)->limit($Page->firstRow.','.$Page->listRows)->select();
            $items = array();
            foreach ($list as $item){
                if($item!=''){
                    array_push($items, array('id' => $item['store_id'], 'sub' => 0, 'name' => $item['name'], 'linkcode'=> str_replace('shequ.php', 'wap.php',$item['url']),'sublink' => $item['url'],'keyword' => $item['name']));
                }else {
                    array_push($items, array('id' => $item['group_id'], 'sub' => 0, 'name' => $item['name'], 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/Group/detail', array('group_id' => $item['group_id']), true, false, true)), 'sublink' => U('Wap/Group/detail', array('group_id' => $item['group_id']), true, false, true), 'keyword' => $item['name']));
                }
            }

            $this->assign('list', $items);
            $this->assign('page', $show);
            $this->display('detail');
        }

        
        public function ActiveMealList(){
            $this->assign('moduleName', $this->modules['ActiveMealList']);
            $village_id = $this->house_session['village_id'];

            $condition_table = array(C('DB_PREFIX').'merchant_store'=>'ms',C('DB_PREFIX').'merchant_store_foodshop'=>'msm',C('DB_PREFIX').'house_village_meal'=>'hvm',C('DB_PREFIX').'merchant'=>'m');
            $condition_field = "`ms`.*,`msm`.*,`m`.`name` AS `merchant_name`,`ms`.`name` AS `store_name`,`hvm`.`url` ";
            $condition_where = "`ms`.`have_meal`='1' AND `ms`.`status`='1' AND `ms`.`store_id`=`msm`.`store_id` AND `ms`.`store_id`=`hvm`.`store_id` AND `hvm`.`village_id`='$village_id' AND `m`.`mer_id`=`ms`.`mer_id` ";
            
            $count = D('')->table($condition_table)->where($condition_where)->count();
            $Page       = new Page($count, 5);
            $show       = $Page->show();

            $list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->limit($Page->firstRow.','.$Page->listRows)->select();
            $items = array();
            foreach ($list as $item){
                if($item!=''){
                    array_push($items, array('id' => $item['store_id'], 'sub' => 0, 'name' => $item['name'], 'linkcode'=> str_replace('shequ.php', 'wap.php',$item['url']),'sublink' => $item['url'],'keyword' => $item['name']));
                }else{
                    array_push($items, array('id' => $item['store_id'], 'sub' => 0, 'name' => $item['name'], 'linkcode'=> str_replace('shequ.php', 'wap.php', U('Wap/Food/shop', array('store_id' => $item['store_id'],'mer_id'=>$item['mer_id']), true, false, true)),'sublink' => U('Wap/Food/shop', array('store_id' => $item['store_id'],'mer_id'=>$item['mer_id']), true, false, true),'keyword' => $item['name']));
                }
            }

            $this->assign('list', $items);
            $this->assign('page', $show);
            $this->display('detail');
        }
        
        
        public function HousevillageNewsCatelist(){
            $this->assign('moduleName', $this->modules['HousevillageNewsCatelist']);
            $village_id = $this->house_session['village_id'];
            $condition_table  = array(C('DB_PREFIX').'house_village_news_category'=>'c',C('DB_PREFIX').'house_village'=>'v');
            $condition_where = "v.village_id = c.village_id AND  c.cat_status=1  AND c.village_id=".$village_id;
           
            $condition_field = 'c.*';
            
            $count = D('')->table($condition_table)->where($condition_where)->count();
            $Page       = new Page($count, 5);
            $show       = $Page->show();

            $list = D('')->table($condition_table)->where($condition_where)->limit($Page->firstRow.','.$Page->listRows)->select();
            $items = array();
            foreach ($list as $item){
                $items[] = array('module' => 'HousevillageNewslist', 'linkcode' => str_replace('shequ.php', 'wap.php', U('Wap/House/village_newslist', array('village_id' => $item['village_id'],'cat_id'=>$item['cat_id']), true, false, true)), 'name'=>$item['cat_name'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1);

                // array_push($items, array('id' => $item['store_id'], 'sub' => 0, 'name' => $item['cat_name'], 'linkcode'=> str_replace('shequ.php', 'wap.php', U('Wap/House/village_newslist', array('village_id' => $item['village_id'],'cat_id'=>$item['cat_id']), true, false, true)),'sublink' => U('Wap/House/village_newslist', array('village_id' => $item['village_id'],'cat_id'=>$item['cat_id']), true, false, true),'keyword' => $item['cat_name']));
            }

            $this->assign('modules', $items);
            $this->display('insert');
        }     
        public function HousevillageNewslist(){
            $this->assign('moduleName', $this->modules['HousevillageNewslist']);
            $village_id = $this->house_session['village_id'];
            $condition_table  = array(C('DB_PREFIX').'house_village_news'=>'n',C('DB_PREFIX').'house_village_news_category'=>'c',C('DB_PREFIX').'house_village'=>'v');
            $condition_where = " n.village_id = v.village_id  AND n.village_id = c.village_id AND  n.cat_id = c.cat_id AND c.cat_status=1  AND n.village_id=".$village_id;
            if($column['status']){
                    $condition_where .= " AND n.status = ".intval($column['status']);
            }
            $condition_field = 'n.*,c.cat_name';
            
            $count = D('')->table($condition_table)->where($condition_where)->count();
            $Page       = new Page($count, 5);
            $show       = $Page->show();

            $list = D('')->table($condition_table)->where($condition_where)->limit($Page->firstRow.','.$Page->listRows)->select();
            $items = array();
            foreach ($list as $item){
                array_push($items, array('id' => $item['store_id'], 'sub' => 0, 'name' => $item['title'], 'linkcode'=> str_replace('shequ.php', 'wap.php', U('Wap/House/village_news', array('village_id' => $item['village_id'],'news_id'=>$item['news_id']), true, false, true)),'sublink' => U('Wap/House/village_news', array('village_id' => $item['village_id'],'news_id'=>$item['news_id']), true, false, true),'keyword' => $item['title']));
            }

            $this->assign('list', $items);
            $this->assign('page', $show);
            $this->display('detail');
        }
        
        public function HousevillageMy(){
            $this->assign('HousevillageMy', $this->modules['HousevillageMy']);
            $this->display('detail');
        }
        
        
         public function HousevillageActivity(){
            $this->assign('moduleName', $this->modules['HousevillageActivity']);
            $village_id = $this->house_session['village_id'];
            $where['village_id'] = $village_id;
            $where['status'] = 1;
            $database_house_village_activity = D('House_village_activity');
            
            $count = $database_house_village_activity->where($where)->count();
            $Page       = new Page($count, 5);
            $show       = $Page->show();

            $list = $database_house_village_activity->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
            $items = array();
            foreach ($list as $item){
                array_push($items, array('id' => $item['id'], 'sub' => 0, 'name' => $item['title'], 'linkcode'=> str_replace('shequ.php', 'wap.php', U('Wap/House/village_activity', array('village_id' => $item['village_id'],'id'=>$item['id']), true, false, true)),'sublink' => U('Wap/House/village_activity', array('village_id' => $item['id'],'news_id'=>$item['id']), true, false, true),'keyword' => $item['title']));
            }

            $this->assign('list', $items);
            $this->assign('page', $show);
            $this->display('detail');
	}
}
?>