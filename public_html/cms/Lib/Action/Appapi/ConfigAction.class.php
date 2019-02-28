<?php
class ConfigAction  extends BaseAction{
    //返回小猪cms O2O系统所有配置
    public  function    index(){
        if( $this->config['site_close']==3){
            $this->returnCode('60000002');
        }
        $config    =   $this->config;
        $city_id   =   I('area_name');
        $app_type   =   I('app_type');
        $app_version    =   I('app_version');
        if($city_id){
            $city    =   $this->cityMatching($city_id);
			if(!$city){
				$city    =   $this->nowCity($config['now_city']);
			}
        }else{
            $city    =   $this->nowCity($config['now_city']);
        }
		
        if($app_version){
                $arr['city']    =   $city;
        }else{
            if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'android')){
                $arr['city']    =   $city;
            }else{
                $arr['city'][]    =   $city;
            }
        }
        if($config['many_city']){
			$arr['many_city']	=	$config['many_city'];
        }else{
			$arr['many_city']	=	0;
        }
		$arr['config']['mobile_head_color']  =  preg_match("/^rgb/", $this->config['mobile_head_color']) ? RGBToHex($this->config['mobile_head_color']) : $this->config['mobile_head_color'];
		if(!$arr['config']['mobile_head_color']){
			$arr['config']['mobile_head_color'] = '#06c1ae';
		}
		$arr['config']['house_position']    =   isset($this->config['house_position'])?$this->config['house_position']:1;
		$arr['config']['open_voice_search'] = $this->config['open_voice_search'];
		$arr['config']['have_appoint'] = !$this->config['appoint_page_row'] ? 0 : 1;
        $arr['config']['group_alias_name']  =   $this->config['group_alias_name'];
        $arr['config']['meal_alias_name']   =   $this->config['meal_alias_name'];
		$arr['config']['shop_alias_name']   =   $this->config['shop_alias_name'];
		$arr['config']['cash_alias_name']   =   $this->config['cash_alias_name'];
		$arr['config']['classify_name']   =   $this->config['classify_name'];
		$arr['config']['appoint_alias_name']   =   $this->config['appoint_alias_name'];
		$arr['config']['house_show_center']   =   $this->config['house_show_center'];
		$arr['config']['house_name'] = isset($this->config['house_name']) && $this->config['house_name'] ? $this->config['house_name'] : '小区';
		$arr['config']['house_market_name'] = isset($this->config['house_market_name']) && $this->config['house_market_name'] ? $this->config['house_market_name'] : '社区超市';
		
		$arr['config']['wxapp_paotui_appid'] = isset($this->config['pay_wxapp_paotui_appid']) && $this->config['pay_wxapp_paotui_appid'] ? $this->config['pay_wxapp_paotui_appid'] : '';
		
        $arr['config']['site_logo']      =   $this->config['site_logo'];
        //分润判断
        $arr['config']['open_score_fenrun']    =   isset($this->config['open_score_fenrun'])?$this->config['open_score_fenrun']:'';
        $arr['config']['fenrun_btn_location']    =   isset($this->config['fenrun_btn_location'])?$this->config['fenrun_btn_location']:'';
        $arr['config']['fenrun_award_url']    =   $this->config['site_url'].'/wap.php?c=Fenrun&a=user_free_award_list';
        $arr['config']['fenrun_money_url']    =   $this->config['site_url'].'/wap.php?c=Fenrun&a=fenrun_money_list';

        $arr['pay_share']['pay_weixinapp_key']    =    isset($this->config['pay_weixinapp_key']) ? $this->config['pay_weixinapp_key']: '';
        $arr['pay_share']['pay_weixinapp_appsecret']    =   isset($this->config['pay_weixinapp_appsecret']) ? $this->config['pay_weixinapp_appsecret'] : '';
        $arr['pay_share']['pay_weixinapp_appid']    =   isset($this->config['pay_weixinapp_appid'])?$this->config['pay_weixinapp_appid']:'';
        $arr['pay_share']['pay_weixinapp_mchid']    =   isset($this->config['pay_weixinapp_mchid'])?$this->config['pay_weixinapp_mchid']:'';
        $arr['wap_around_show_type']  = intval($this->config['wap_around_show_type']);
		if($_POST['Device-Id'] != 'wxapp'){
			$appConfig  =   D('Appapi_app_config')->field(true)->select();
			foreach($appConfig as $k=>$v){
				if($v['var'] == 'ios_version_desc'){
					$arr['appConfig'][$v['var']]   =   $v['value'];
				}else{
					$arr['appConfig'][$v['var']]   =   nl2br($v['value']);
				}
			}
        }
		
		
        if(empty($arr)){
            $this->returnCode('20000002');
        }else if(empty($arr['appConfig'])){
            $arr['appConfig']   =   array();
        }else if(empty($arr['config'])){
            $arr['config']      =   array();
        }else if(empty($arr['city'])){
            $arr['city']    =   null;
        }
        if(empty($arr['appConfig'])){
            $arr['appConfig'] = (Object)array();
        }
        $arr['house']	=	array(
			'house_door'	=>	$this->config['house_door'],
			'house_open'	=>	$this->config['house_open'],
			'show_open_door_btn'=>	$this->config['show_open_door_btn'],
        );
        $arr['config']['pay_alipay_app_open'] = $this->config['pay_alipay_app_open'];
        $arr['config']['pay_alipay_app_pid'] =  isset($this->config['pay_alipay_app_pid']) ? $this->config['pay_alipay_app_pid']: '';
        $arr['config']['pay_alipay_app_appid'] =  isset($this->config['pay_alipay_app_appid']) ? $this->config['pay_alipay_app_appid']: '';
        $arr['config']['pay_alipay_app_count'] =  isset($this->config['pay_alipay_app_count']) ? $this->config['pay_alipay_app_count']: '';


        $arr['config']['new_pay_alipay_app_pid'] =  isset($this->config['new_pay_alipay_app_appid']) ? $this->config['new_pay_alipay_app_appid']: '';
        $arr['config']['new_pay_alipay_app_private_key'] =  isset($this->config['new_pay_alipay_app_private_key']) ? $this->config['new_pay_alipay_app_private_key']: '';


        if($app_type==1){
            $arr['config']['pay_alipay_app_private_key'] =  isset($this->config['pay_alipay_app_private_key_ios']) ? $this->config['pay_alipay_app_private_key_ios']: '';
        }elseif($app_type==2){
            $arr['config']['pay_alipay_app_private_key'] =  isset($this->config['pay_alipay_app_private_key_android']) ? $this->config['pay_alipay_app_private_key_android']: '';
        }
        $arr['config']['pay_alipay_app_public_key'] =  isset($this->config['pay_alipay_app_public_key']) ? $this->config['pay_alipay_app_public_key']: '';
		
		
		//平台小程序参数
		$arr['config']['pay_wxapp_appid']    =   isset($this->config['pay_wxapp_appid'])?$this->config['pay_wxapp_appid'] : '';
		$arr['config']['shop_wxapp_share_img'] = isset($this->config['shop_wxapp_share_img'])?$this->config['shop_wxapp_share_img'] : '';
		$arr['config']['shop_wxapp_share_title'] = isset($this->config['shop_wxapp_share_title'])?$this->config['shop_wxapp_share_title'] : '';
		
		
		// 定制-发现功能
        $arr['config']['find_msg']  =   $this->config['find_msg'];
		
		$menu_category = M('Home_menu_category')->field('cat_id')->where(array('cat_key'=>'app_footer'))->find();
        if($menu_category){
            $footer_menu_list = M('Home_menu')->where(array('status'=>'1','cat_id'=>$menu_category['cat_id']))->order('`sort` DESC,`id` ASC')->limit(4)->select();
        }
        $arr['url_check_arr'] = array(
            array('key'=>'c=Foodshop&a=book_success')
            ,array('key'=>'c=My')
            ,array('key'=>'c=Shop&a=order_detail')
            ,array('key'=>'c=Shop&a=status')
            ,array('key'=>'c=Takeout&a=order_detail')
            ,array('key'=>'c=Food&a=order_detail')
            ,array('key'=>'c=Service&a=price_list')
            ,array('key'=>'group_type=hotel')
            ,array('key'=>'c=Group&a=detail')
            ,array('key'=>'c=Group&a=shop')
            ,array('key'=>'c=Shop&a=index')
            ,array('key'=>'c=Shop&a=classic_good')
            ,array('key'=>'c=Shop&a=classic_shop')
            ,array('key'=>'c=Mall&a=detail')
            ,array('key'=>'c=Diypage&a=page')
            ,array('key'=>'c=Mall&a=store')
        );

        if(count($footer_menu_list)<4){
            $arr['footer_menu_list']=array();
        }else{
            $footer_menu=array();

            foreach ($footer_menu_list as $key=>$v) {
				$url = parse_url($v['url']);

				if(($key!=3&&$key!=0)&&(empty($url['query'])||strpos($v['url'],'Home')||strpos($v['url'],'My'))){
					$arr['footer_menu_list']=array();
					//break;
				}
                $tmp['name'] = $v['name'];
				// if($key==3||$key==0){

					// $tmp['url'] = '';
				// }else{

					$tmp['url'] = $v['url'];
				// }
                $tmp['pic_path'] = C('config.site_url').'/upload/slider/'.$v['pic_path'];
                $tmp['hover_pic_path'] = C('config.site_url').'/upload/slider/'.$v['hover_pic_path'];
                $footer_menu[] = $tmp;
            }

            $arr['footer_menu_list']=$footer_menu;
        }
		$head_adver = D('Adver')->get_adver_by_key('app_index_top',1);
        $content_type = $this->config['guess_content_type'];
		$arr['is_app_adver'] = $head_adver ? true : false;
		$arr['home_like_type'] = $content_type;
		$arr['register_agreement_url'] = $this->config['site_url'].'/wap.php?c=Login&a=register_agreement';
		$arr['score_name'] = $this->config['score_name'];
		$arr['search_default_type'] =  $this->config['search_first_type']?$this->config['search_first_type']:'group';

        $this->returnCode(0,$arr);
    }
    # 景区配置
    public function scenic_index(){
		$config    =   $this->config;
        if($config['many_city']){
			$arr['many_city']	=	$config['many_city'];
        }else{
			$arr['many_city']	=	0;
        }
        $arr['scenic_now_city'] = $config['scenic_now_city'];
    }

	/**
	 * 获取微信js配置信息
	 */
	public function wx_config(){
		$share = new WechatShare($this->config, '');
		$arr = $share->get_wx_config();
		
		if($_POST['work'] == 'storestaff'){
			switch($_POST['page']){
				case 'index':
					$arr['share'] = array(
						'title'=>'店员中心',
						'content'=>'微信版店员中心-'.$this->config['site_name'],
						'url'=>$_POST['location_url']
					);
					break;
			}
		}
		if($_POST['work'] == 'deliver'){
			switch($_POST['page']){
				case 'index':
				case 'tongji':
				case 'info':
					$arr['share'] = array(
						'title'=>'配送员中心',
						'content'=>'微信版配送员中心-'.$this->config['site_name'],
						'url'=>$_POST['location_url']
					);
					break;
			}
		}
		if($_POST['work'] == 'merchant'){
			switch($_POST['page']){
				case 'index':
				case 'tongji':
				case 'info':
					$arr['share'] = array(
						'title'=>'商家中心',
						'content'=>'微信版商家中心-'.$this->config['site_name'],
						'url'=>$_POST['location_url']
					);
					break;
			}
		}
		
		if($arr['share']){
			$arr['share']['image'] = $this->config['site_url'].'/packapp/'.$_POST['work'].'/logo.png';
		}
		
		$this->returnCode(0, $arr);
	}
	
	public function wxapp_error_report(){
		fdump(date('Y-m-d H:i:s'),'api/wxapp_error_log',true);
		fdump(intval($this->_uid),'api/wxapp_error_log',true);
		fdump($_POST,'api/wxapp_error_log',true);
		fdump($_SERVER['HTTP_REFERER'],$fdumpname,true);
		fdump('======================================','api/wxapp_error_log',true);
		
		$this->returnCode(0, array());
	}
	
	public function wxapp_door_report(){ 
		$data = array();
		//公共参数
			$data['add_time'] = time();
		
		//手机信息
			$data['phone_plat'] = $_POST['systemInfo']['platform'] == 'android' ? 2 : 1;
			$data['phone_brand'] = strtolower($_POST['systemInfo']['brand']);
			
			//手机号版本只取前两位
			$phone_version = trim(str_replace(array('ios','android'),'',strtolower($_POST['systemInfo']['system'])));
			$phone_version_arr = explode('.',$phone_version);
			$data['phone_version'] = $phone_version_arr[0] . '.' . ($phone_version_arr[1] ? $phone_version_arr[0] : '0');
			
		//未搜索到门禁，不知道用户情况
		if($_POST['file'] == 'wxapp_notfounddoor_error_log'){
			//如果用户属于一个小区，则归为该小区
			$user_bind = M('House_village_user_bind')->field('`village_id`')->where(array('uid'=>$this->_uid))->group('village_id')->select();

			if(count($user_bind) == 1){
				$data['village_id'] = $user_bind[0]['village_id'];
			}
			$data['uid'] = $this->_uid;
			$data['add_time'] = time();
			$data['open_status'] = '1';
			
			//蓝牙搜索到的个数，部分安卓手机需要开定位才能使用蓝牙，作为部分依据查看蓝牙是否真的可用
			$data['searched_bluetooth'] = count($_POST['msg']['devicesArr']);
			
			$_POST['log_id'] = M('House_village_open_door')->data($data)->add();
		}else if($_POST['file'] == 'wxapp_connetion_error_log'){  //连接不上蓝牙，
			//通过门禁ID进行查询
			$door_id = intval($_POST['msg']['door_id']);
			
			$now_door = M('House_village_door')->where(array('door_id'=>$door_id))->find();
			$data['village_id'] = $now_door['village_id'];
			$data['door_device_id'] = $now_door['door_device_id'];
			$data['floor_id'] = $now_door['floor_id'];
			$data['uid'] = $this->_uid;	
			$data['open_status'] = $_POST['msg']['has_reconnect'] ? '3' : '2';
			M('House_village_open_door')->data($data)->add();
		}else if($_POST['file'] == 'wxapp_door_log'){  //正常连接蓝牙
			//通过门禁ID进行查询
			$door_id = intval($_POST['msg']['door_id']);
			
			$now_door = M('House_village_door')->where(array('door_id'=>$door_id))->find();
			
			$data['village_id'] = $now_door['village_id'];
			$data['door_device_id'] = $now_door['door_device_id'];
			$data['floor_id'] = $now_door['floor_id'];
			$data['uid'] = $this->_uid;	
			$data['open_status'] = '0';
			
			//去重
			$condition['add_time'] 		 = $data['add_time'];
			$condition['village_id']	 = $data['village_id'];
			$condition['door_device_id'] = $data['door_device_id'];
			$condition['uid'] 			 = $this->_uid;
			$condition['open_status'] 	 = '0';
			if(!M('House_village_open_door')->where($condition)->find()){
				M('House_village_open_door')->data($data)->add();
				
				//修改门禁最后开门时间
				M('House_village_door')->where(array('door_device_id'=>$data['door_device_id']))->data(array('last_open_time'=>$data['add_time']))->save();
			}
		}
		
		//未连接上门禁，
		
		$fdumpname = $_POST['file'] ? $_POST['file'] : 'wxapp_door_log';
		
		fdump(date('Y-m-d H:i:s'),'api/'.$fdumpname,true);
		fdump(intval($this->_uid),'api/'.$fdumpname,true);
		fdump($_POST,'api/'.$fdumpname,true);
		fdump($_SERVER['HTTP_REFERER'],'api/'.$fdumpname,true);
		fdump('======================================','api/'.$fdumpname,true);
		
		$this->returnCode(0, array());
	}
	
	//通过城市名或经纬度获取城市信息
	public function getLocationCity(){ 
		$lat = $_POST['lat'];
        $lng = $_POST['lng'];
		$arr = $this->geocoder($lat,$lng);
		if ($arr) {
			if ($this->config['location_mode']!=2) { // 城市、区县
				$city = $this->cityMatchingAll($arr['city'],$arr['district']);

			}elseif($this->config['location_mode']==2){ // 商城
				$city = $this->cityMatchingAll($arr['city'],$arr['district'],$lat,$lng);
			}
			if ($this->config['location_mode']) { // 区县 商场
				if (!$city) {
        			$this->returnCodeError('转换位置信息失败');
				}else{
					$this->returnCodeOk(array('area_id'=>$city['now_area_id'],'area_name'=>$city['now_area_name'],'top_area_id'=>$city['area_id'],'top_area_name'=>$city['area_name']));
				}
			}else{ // 城市
				if (!$city) {
        			$this->returnCodeError('转换位置信息失败');
				}else{
					$this->returnCode(0,array('area_id'=>$city['area_id'],'area_name'=>$city['area_name']));
				}
			}
		}else{
        	$this->returnCodeError('转换位置信息失败');
		}
	}

	public function geocoder($lat, $lng){
		// $lat = $this->user_long_lat['lat'];
		// $lng = $this->user_long_lat['long'];
		
		if ($lat&&$lng) {
			$url = 'http://api.map.baidu.com/geocoder/v2/?location='.$lat.','.$lng.'&output=json&pois=1&ak=4c1bb2055e24296bbaef36574877b4e2';
			import('ORG.Net.Http');
			$http = new Http();
			$result = $http->curlGet($url);
			if($result){
				$result = json_decode($result,true);
				$arr = array(
						'city' => $result['result']['addressComponent']['city'],
						'province' => $result['result']['addressComponent']['province'],
						'district' => $result['result']['addressComponent']['district'],
					);
				return $arr;
			}else{
				return array();
			}
		}else{
			return array();
		}
	}

    public function cityMatchingAll($city_id,$area_name='',$lat=0,$lng=0){
        $long   =   strlen($city_id);
        if($long >= 7){
            $city_id    =   str_replace('市','',$city_id);
			$city_id    =   str_replace('地区','',$city_id);
            $city_id    =   str_replace('特别行政区','',$city_id);
            $city_id    =   str_replace('蒙古自治州','',$city_id);
            $city_id    =   str_replace('回族自治州','',$city_id);
            $city_id    =   str_replace('柯尔克孜自治州','',$city_id);
            $city_id    =   str_replace('哈萨克自治州','',$city_id);
            $city_id    =   str_replace('土家族苗族自治州','',$city_id);
            $city_id    =   str_replace('藏族羌族自治州','',$city_id);
			$city_id    =   str_replace('傣族自治州','',$city_id);
			$city_id    =   str_replace('布依族苗族自治州','',$city_id);
			$city_id    =   str_replace('苗族侗族自治州','',$city_id);
			$city_id    =   str_replace('壮族苗族自治州','',$city_id);
			$city_id    =   str_replace('澳门','澳門',$city_id);
			$city_id    =   str_replace('朝鲜族自治州','',$city_id);
			$city_id    =   str_replace('哈尼族彝族自治州','',$city_id);
			$city_id    =   str_replace('傣族景颇族自治州','',$city_id);
			$city_id    =   str_replace('藏族自治州','',$city_id);
			$city_id    =   str_replace('彝族自治州','',$city_id);
			$city_id    =   str_replace('白族自治州','',$city_id);
			$city_id    =   str_replace('傈僳族自治州','',$city_id);
        }
        $database_area = D('Area');
        $database_field = '`area_id`,`area_name`,`area_url`';
        $condition_all_city['area_name'] = $city_id;
        $condition_all_city['area_type'] = 2;
        $condition_all_city['is_open'] = 1;
        $oCity = $database_area->field($database_field)->where($condition_all_city)->find();
        if($oCity){
            // 获取当前区县
            if($area_name){
                $maybe_areaname = str_replace(array('区','县'),'',$area_name);
                $now_area = D('Area')->where(array('_string'=>"`area_pid`= '{$oCity['area_id']}' AND (`area_name`='{$area_name}' OR `area_name`='{$maybe_areaname}')"))->find();
                
                if(empty($now_area)){
                    import('@.ORG.words');
                    $words_class = new words();
                    $data_area['area_pid'] = $oCity['area_id'];
                    $data_area['area_name'] = $area_name;
                    $data_area['area_url'] = $words_class->stringgetFirstCharter($area_name);
                    $data_area['area_type'] = '3';
                    if($data_area['area_id'] = D('Area')->data($data_area)->add()){
                        $now_area = $data_area;
                    }
                }
                
                $oCity['now_area_id'] = $now_area['area_id'];
                $oCity['now_area_name'] = $now_area['area_name'];
                $oCity['now_area_url'] = $now_area['area_url'];
            }

            // 通过经纬度获取该区县下距离最近的商场信息
            if($lat && $lng ){
                $oCity['now_area_id'] = $oCity['area_id'];
                $oCity['now_area_name'] = $oCity['area_name'];
                $oCity['now_area_url'] = $oCity['area_url'];
                if ($now_area) {
                    $now_tranding = D('Area')->get_tranding_by_countyPid($now_area['area_id'],true);
                    $min_distance_tranding = array();
                    $distance_old = 0;
                    if ($now_tranding) {
                        foreach ($now_tranding as $key => $value) {
                            $distance = getDistance($lat, $lng, $value['lat'], $value['lng']); // 距离
                            if($min_distance_tranding){
                                if ($distance < $distance_old) { // 取较小的距离
                                    $min_distance_tranding = $value;
                                }
                            }else{
                                $min_distance_tranding = $value;
                                $distance_old = $distance;
                            }
                        }
                        if ($min_distance_tranding) {
                            $oCity['now_area_id'] = 'm_'.$min_distance_tranding['market_id'];
                            $oCity['now_area_name'] = $min_distance_tranding['market_name'];
                        }
                    }
                }
            }
            return  $oCity;
        }else{
        	$config = D('Config')->get_config();
	        $condition_all_city = array();
	        $condition_all_city['area_id'] = $config['now_city'];
	        $condition_all_city['area_type'] = 2;
	        $condition_all_city['is_open'] = 1;
	        $oCity = $database_area->field($database_field)->where($condition_all_city)->find();
        	if ($oCity) {
            	return  $oCity;
        	}else{
        		return array();
        	}
        }
    }
}
?>