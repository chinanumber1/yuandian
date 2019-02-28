<?php
/*
 * 社区2.0首页
 *
 */
class H_indexAction extends BaseAction{
	//	获取社区基本信息管理
    public function index(){
		$this->is_existence();
    	$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}
			$village_info = M('House_village')->field(array('qrcode_id','wx_image','wx_desc'),true)->where(array('village_id'=>$village_id))->find();

    		$uid = $info['uid']-10000000;
            $type = substr($uid, 0,1);
            $uid = substr($uid, 1);
            if ($type==1) { // 主账号
            }elseif($type==2){ // 角色
            	$role = D('House_admin')->field(true)->where(array('id'=>$uid))->find();
            	$village_info['account'] = $role['realname'] ? $role['realname'] : $role['account'];
            }
        	$menus = $this->getMenu($this->power);

			$village_info	=	isset($village_info)?$village_info:array();
//			$village_info['long'] = floatval($village_info['long']);
//			$village_info['lat'] = floatval($village_info['lat']);
			unset($village_info['pwd']);
			if($village_info['province_id']){
				$province_id	=	$this->getCityId($village_info['province_id']);
				$village_info['default']['province_name']	=	$province_id['area_name'];
				$village_info['default']['province_id']	=	$village_info['province_id'];
			}
			if($village_info['city_id']){
				$city_id	=	$this->getCityId($village_info['city_id']);
				$village_info['default']['city_name']	=	$city_id['area_name'];
				$village_info['default']['city_id']	=	$village_info['city_id'];
			}
			if($village_info['area_id']){
				$area_id	=	$this->getCityId($village_info['area_id']);
				$village_info['default']['area_name']	=	$area_id['area_name'];
				$village_info['default']['area_id']	=	$village_info['area_id'];
			}
			if($village_info['circle_id']){
				$circle_id	=	$this->getCityId($village_info['circle_id']);
				$village_info['default']['circle_name']	=	$circle_id['area_name'];
				$village_info['default']['circle_id']	=	$village_info['circle_id'];
			}
			if(empty($village_info['default'])){
				$village_info['default']	=	(object)array();
			}
			$village_info['longs']	=	$village_info['long'];
			$village_info['lats']	=	$village_info['lat'];
			$village_info['now_city']	=	$this->config['now_city'];
			$village_info['many_city']	=	$this->config['many_city'];
			unset($village_info['long'],$village_info['lat']);
			
			//是否开启车牌识别
			$plate_house_open = false;
			if (C('config.plate_house_open') == 1) {
				if (C('config.plate_house_way') == 1) { // 平台配置
					$AppID = C('config.plate_house_appid');
					$APIKey = C('config.plate_house_api_key');
					$SecretKey = C('config.plate_house_secret_key');

				}else{ // 小区配置
					if ($_SESSION['house']['plate_open'] == 1) {
						$AppID = $_SESSION['house']['plate_appid'];
						$APIKey = $_SESSION['house']['plate_api_key'];
						$SecretKey = $_SESSION['house']['plate_secret_key'];
					}
				}
				if ($AppID && $APIKey && $SecretKey) {
					$plate_house_open = true;
				}
			}
			$village_info['plate_house_open'] = $plate_house_open;

			if($_POST['Device-Id'] != 'wxapp'){
				$appConfig  =   D('Appapi_app_config')->field(true)->select();
				foreach($appConfig as $k=>$v){
					$appConfig[$v['var']]   =   nl2br($v['value']);
				}
			}


			

			$arr	=	array(
				'village'	=>	isset($village_info)?$village_info:(object)array(),
				'power'	=>	$this->power,
				'menus'	=>	$menus,
			);
			$arr['village_manerge_android_v'] = $appConfig['v_manerge_android_v'];
			$arr['village_manerge_android_vcode'] = $appConfig['v_manerge_android_vcode'];
			$arr['village_manerge_android_url'] = $appConfig['v_manerge_android_url'];
			$arr['village_manerge_android_vdesc'] = $appConfig['v_manerge_android_vdesc'];
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
    }
    //	获取省、市、区、商圈
    public function	getProvince($stats=0){
    	$province_id	=	I('area_pid',0);
    	$aProvince_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_pid'=>$province_id,'is_open'=>1))->select();
    	if($stats){
			return $aProvince_id;
    	}else{
			$this->returnCode(0,$aProvince_id);
    	}
    }

    //	用ID获取城市
    public function	getCityId($area_id=0){
		if(empty($area_id)){
			return array();
		}
		$aArea_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_id'=>$area_id,'is_open'=>1))->find();
		if(empty($aArea_id)){
			$aArea_id	=	(object)array();
		}
		return $aArea_id;
    }
    //	修改社区基本信息管理
    public function	villageEdit(){
		$this->is_existence();

		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$info = ticket::get($ticket, $this->DEVICE_ID, true);
    	if(empty($info)){
			$this->returnCode('20000009');
		}
		if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
    	}
    	//验证权限
    	if(!in_array(3, $this->power)){
			$this->returnCode('20090103');
    	}
    	$village_id	=	I('village_id');
    	if(empty($village_id)){
			$this->returnCode('30000001');
    	}else{
    		$arr	=	array(
    			'property_phone'	=>	I('property_phone'),		//物业联系电话
    			'property_address'	=>	I('property_address'),		//物业联系地址
    			'long'				=>	I('longs'),					//经度
    			'lat'				=>	I('lats'),					//纬度
    			'province_id'		=>	I('province_id'),			//省
    			'city_id'			=>	I('city_id'),				//市
    			'area_id'			=>	I('area_id'),				//区
    			'circle_id'			=>	I('circle_id'),				//商圈
    			'village_address'	=>	I('village_address'),		//社区地址
    			'property_price'	=>	I('property_price'),		//一平方米的物业费单价
    			'water_price'		=>	I('water_price'),			//水费单价
    			'electric_price'	=>	I('electric_price'),		//电费单价
    			'gas_price'			=>	I('gas_price'),				//燃气费单价
    			'park_price'		=>	I('park_price'),			//停车位每月价格
    			'has_custom_pay'	=>	I('has_custom_pay'),		//是否支持自定义缴费
    			'has_express_service'=>	I('has_express_service'),	//是否开启快递代收
    			'has_visitor'		=>	I('has_visitor'),			//是否开启访客登记
    			'has_slide'			=>	I('has_slide'),				//是否开启社区幻灯片
    			'has_service_slide'	=>	I('has_service_slide'),		//是否开启便民页面幻灯片 0，关闭 1，开启
    		);
    		foreach($arr as $k=>$v){
				if($v==null){
					unset($arr[$k]);
				}
    		}
    		$aVillage_id	=	M('House_village')->field(array('village_id','status'))->where(array('village_id'=>$village_id))->find();
    		if($aVillage_id){
    			if($aVillage_id['status'] == 0){
					if($arr['long'] && $arr['lat']){
						$arr['status']	=	1;
					}
    			}
				$aSave	=	M('House_village')->where(array('village_id'=>$village_id))->data($arr)->save();
    		}else{
				$this->returnCode('20090005');
    		}
    	}
    	if($aSave){
			$this->returnCode(0);
    	}else if($aSave === 0){
			$this->returnCode('20090007');
    	}else{
			$this->returnCode('20090006');
    	}
    }
    public function see_qrcode(){
    	$this->is_existence();
    	$type		=	'house';
    	$village_id	=	I('village_id');
		//判断ID是否正确，如果正确且以前生成过二维码则得到ID
		$pigcms_return = D('House_village')->get_qrcode($village_id);
		if(empty($pigcms_return)){
			$this->returnCode('20090053');
		}
		if(empty($pigcms_return['qrcode_id'])){
			$qrcode_return = D('Recognition')->get_new_qrcode($type,$village_id);
		}else{
			$qrcode_return = D('Recognition')->get_qrcode($pigcms_return['qrcode_id']);
			if($qrcode_return['error_code']){
				$this->returnCode('20090055');
			}
		}
		if($qrcode_return['error_code']){
			$this->returnCode('20090056');
		}else if($qrcode_return['qrcode'] == 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='){
			$qrcode_return = D('Recognition')->get_new_qrcode($type,$village_id);
		}

		//echo $_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house';
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house/'.$village_id.'.png')){
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house')){
				echo $_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house';
				mkdir($_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house/',0777,true);
			}
			import('ORG.Net.Http');
			$http = new Http();
			file_put_contents('./runtime/qrcode/house/'.$village_id.'.png',Http::curlGet($qrcode_return['qrcode']));
		}
		$arr	=	array(
			'img'	=>	$this->config['site_url'].'/runtime/qrcode/house/'.$village_id.'.png',
		);
		$this->returnCode(0,$arr);
    }

    // 获得首页菜单
	function getMenu($menu=array()){
		$arr = array(
			// 物业管理
			'property' => array(
				'is_show' => false,
				'child' => array(
					'user' =>false, // 业主管理
					'visitor' =>false, // 访客登记
					'parking' =>false, // 车位管理
					'vehicle' =>false, // 车辆管理
					'deposit' =>false, //押金管理
				)
			),
			// 费用管理
			'fees' => array(
				'is_show' => false,
				'child' => array(
					'unpaid' =>false, //未缴
					'paid' =>false,	// 已缴
				)
			),
			// 物业服务
			'sevices' => array(
				'is_show' => false,
				'child' => array(
					'newsreply' =>false, //新闻评论
					'collection' =>false, //快递代收
					'phone' =>false, //常用电话
				)
			),
			// 底部菜单
			'footer' => array(
				'is_show' => false,
				'child' => array(
					'cashier' =>false, //收银台
					'door' =>false, //智慧门禁
				)
			),
		);
		// 角色权限
		foreach ($menu as $value) {
			switch ($value) {
				case '91':
					$arr['property']['is_show'] = true;
					$arr['property']['child']['user'] = true;
					break;
				case '216':
					$arr['property']['is_show'] = true;
					$arr['property']['child']['visitor'] = true;
					break;
				case '45':
					$arr['property']['is_show'] = true;
					$arr['property']['child']['parking'] = true;
					break;
				case '55':
					$arr['property']['is_show'] = true;
					$arr['property']['child']['vehicle'] = true;
					break;
				case '41':
					$arr['property']['is_show'] = true;
					$arr['property']['child']['deposit'] = true;
					break;
				case '85':
					$arr['fees']['is_show'] = true;
					$arr['fees']['child']['unpaid'] = true;
					break;
				case '83':
					$arr['fees']['is_show'] = true;
					$arr['fees']['child']['paid'] = true;
					break;
				case '122':
					$arr['sevices']['is_show'] = true;
					$arr['sevices']['child']['newsreply'] = true;
					break;
				case '207':
					$arr['sevices']['is_show'] = true;
					$arr['sevices']['child']['collection'] = true;
					break;
				case '199':
					$arr['sevices']['is_show'] = true;
					$arr['sevices']['child']['phone'] = true;
					break;
				case '66':
					$arr['footer']['is_show'] = true;
					$arr['footer']['child']['cashier'] = true;
					break;
				case '226':
					$arr['footer']['is_show'] = true;
					$arr['footer']['child']['door'] = true;
					break;
			}
		}
		return $arr;
	}

	 // 获得首页菜单
	function getMenu1($menu=array()){
		$arr = array(
			// 物业管理
			'wygl' => array(
			),
			// 费用管理
			'fygl' => array(
			),
			// 物业服务
			'wyfw' => array(
			),
			// 底部菜单
			'footer' => array(
			),
		);


		// 角色权限
		foreach ($menu as $value) {
			switch ($value) {
				// 物业管理
				case '91':
					$arr['wygl'][] = array(
						'name' => '业主管理',
						'pic' => '业主管理',
						'url' => U('H_user/index'),
						'sort' =>1
					);
					break;
				case '216':
					$arr['wygl'][] = array(
						'name' => '访客登记',
						'pic' => '访客登记',
						'url' => U('H_function/visitor_list'),
						'sort' =>2
					);
					break;
				case '45':
					$arr['wygl'][] = array(
						'name' => '车位管理',
						'pic' => '车位管理',
						'url' => U('H_function/visitor_list'),
						'sort' =>3
					);
					break;
				case '55':
					$arr['wygl'][] = array(
						'name' => '车辆管理',
						'pic' => '车辆管理',
						'url' => U('H_function/visitor_list'),
						'sort' =>4
					);
				case '41':
					$arr['wygl'][] = array(
						'name' => '押金管理',
						'pic' => '押金管理',
						'url' => U('H_function/visitor_list'),
						'sort' =>5
					);
					break;
				// 费用管理
				case '85':
					$arr['fygl'][] = array(
						'name' => '未缴',
						'pic' => '未缴',
						'url' => U('H_function/visitor_list'),
						'sort' =>1
					);
					break;
				case '83':
					$arr['fygl'][] = array(
						'name' => '已缴',
						'pic' => '已缴',
						'url' => U('H_function/visitor_list'),
						'sort' =>2
					);
					break;

				// 物业服务
				case '122':
					$arr['wyfw'][] = array(
						'name' => '新闻评论',
						'pic' => '新闻评论',
						'url' => U('H_news/reply'),
						'sort' =>1
					);
					break;
				case '207':
					$arr['wyfw'][] = array(
						'name' => '快递代收',
						'pic' => '快递代收',
						'url' => U('H_function/express_service_list'),
						'sort' =>2
					);
					break;
				case '199':
					$arr['wyfw'][] = array(
						'name' => '常用电话',
						'pic' => '常用电话',
						'url' => U('H_function/phone_category'),
						'sort' =>3
					);
					break;

				// 底部菜单
				case '66':
					$arr['footer'][] = array(
						'name' => '收银台',
						'pic' => '收银台',
						'url' => U('H_function/visitor_list'),
						'sort' =>1
					);
					break;
				case '226':
					$arr['footer'][] = array(
						'name' => '智慧门禁',
						'pic' => '智慧门禁',
						'url' => U('H_door/get_door'),
						'sort' =>2
					);
					break;
			}
		}

		// 排序
		foreach ($arr as &$value) {
			$array_sort = array();
			if ($value) {
				foreach ($value as $_v) {
					$array_sort[] = $_v['sort'];
				}
				array_multisort($array_sort,SORT_ASC,SORT_NUMERIC,$value);
			}
		}
		return $arr;
	}
}