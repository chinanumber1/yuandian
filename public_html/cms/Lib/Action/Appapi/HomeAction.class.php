<?php
class HomeAction  extends BaseAction{
    public  function    index(){
        $user_long_lat  =   $this->user_long_lat;
		
        $arr['new_group_list']    =   array();
        $arr['user_long_lat']    =   !empty($user_long_lat)?$user_long_lat:null;
		
		//顶部广告 小程序读WAP端
		if($_POST['Device-Id'] != 'wxapp'){
			$head_adver = D('Adver')->get_adver_by_key('app_index_top',5);
		}
		if(empty($head_adver)){
			$head_adver = D('Adver')->get_adver_by_key('wap_index_top',5);
		}
		if(!empty($head_adver)){
			foreach($head_adver as &$head_adver_value){
				unset($head_adver_value['id'],$head_adver_value['bg_color'],$head_adver_value['cat_id'],$head_adver_value['status'],$head_adver_value['last_time'],$head_adver_value['sub_name']);
			}
			$arr['head_adver'] = $head_adver;
		}else{
			$arr['head_adver'] = array();
		}
		
		//首页附近模块
		if($this->config['wap_around_show_type']){
			$index_around = M('Wap_around')->order('sort DESC')->limit(3)->select();
			foreach($index_around as &$around_value){
				if($around_value['name'] != 'merchant'){
					$around_value['name'] = '附近'.$this->config[$around_value['name'].'_alias_name'];
				}else{
					$around_value['name'] = '附近商家';
				}
				$around_value['url'] = htmlspecialchars_decode($around_value['url']);
				$around_value['pic'] = $around_value['pic'] ? $this->config['site_url'].'/upload/wap/'.$around_value['pic'] : '';
			}
		}
		if(empty($index_around)){
			$index_around = array();
		}

        if($this->config['wap_around_show_type'] == 1){
            $arr['index_around'] = $index_around;
        }else{
    		$arr['index_around'] = array();
        }
		
        //我的社区
        if($this->config['house_open'] == 1){
            $house_alias_name = isset($this->config['house_name']) && $this->config['house_name'] ? $this->config['house_name'] : '社区';
            $arr['community']['house_open'] =   $this->config['house_open'];
            $arr['community']['img']        =   $this->config['wechat_share_img'];
            $arr['community']['name']       =   '我的' . $house_alias_name . '服务';
            $arr['community']['url']        =   htmlspecialchars_decode($this->config['site_url'].'/wap.php?g=Wap&c=House&a=village_list');
        }else{
            $arr['community']   =   (object)array();
        }
        //导航条
        $arr['silder_limit']= $this->config['wap_slider_number'];
        $tmp_wap_index_slider = D('Slider')->get_slider_by_key('wap_slider',0);
        $wap_index_slider = array();
        foreach($tmp_wap_index_slider as $key=>$value){
            $tmp_i = floor($key/8);
            if(!stristr($value['url'],'Weidian') && (!stristr($value['url'],'Invitation') || $this->DEVICE_ID == 'wxapp') && (!stristr($value['url'],'City_car') || $this->DEVICE_ID == 'wxapp') ){
                $wap_index_slider[$tmp_i][] = $value;
            }
        }
        foreach($wap_index_slider as $v){
            foreach($v as $vv){
                $vv['url']  =   htmlspecialchars_decode($vv['url']);
                $arr['slider'][]  =   $vv;
            }
        }
        if(empty($arr['slider'])){
            $arr['slider']  =   array();
        }
		// 快报
        $news_info = M('')->field('`n`.`id`,`n`.`title`,`c`.`name`')->table(array(C('DB_PREFIX').'system_news'=>'n',C('DB_PREFIX').'system_news_category'=>'c'))->where("`n`.`status`='1' AND `c`.`id`=`n`.`category_id` AND `c`.`status`='1'")->order('`n`.`sort` DESC,`n`.`id` DESC')->limit(8)->select();
		
        $news_count = count($news_info);

		if($news_count){
			for($i=0;$i<$news_count; $i++){
				$arr['news_list'][]=array('cat_name'=>$news_info[$i]['name'],'title'=>$news_info[$i]['title']);
			}
		}else{
			$arr['news_list']=array();
		}

        //中间广告
        $arr['Adver'] = D('Adver')->get_adver_by_key('wap_index_center',4);
        if(empty($arr['Adver'])){
            $arr['Adver']   =   array();
        }

        //活动列表
        if($this->config['activity_open']){
            $now_activity = D('Extension_activity')->where(array('begin_time'=>array('lt',$_SERVER['REQUEST_TIME']),'end_time'=>array('gt',$_SERVER['REQUEST_TIME'])))->order('`activity_id` ASC')->find();
            if($now_activity){
                $time = $now_activity['end_time'] - $_SERVER['REQUEST_TIME'];
                $time_array['d'] = isset($time)?floor($time/86400):0;
                $time_array['h'] = isset($time)?floor($time%86400/3600):0;
                $time_array['m'] = isset($time)?floor($time%86400%3600/60):0;
                $time_array['s'] = isset($time)?floor($time%86400%60):0;

                $activity_list = D('')->field('`eac`.`pigcms_id`,`eac`.`name`,`eac`.`title`,`eac`.`pic`,`eac`.`all_count`,`eac`.`part_count`,`eac`.`price`,`eac`.`mer_score`,`eac`.`type`')->table(array(C('DB_PREFIX').'extension_activity_list'=>'eac',C('DB_PREFIX').'merchant'=>'m'))->where("`eac`.activity_term='{$now_activity['activity_id']}' AND `eac`.`status`='1' AND `eac`.`is_finish`='0' AND `eac`.`index_sort`>0 AND `eac`.`mer_id`=`m`.`mer_id` AND `m`.`city_id`='{$this->config['now_city']}'")->order('`eac`.`index_sort` DESC,`eac`.`pigcms_id` DESC')->limit(3)->select();

                if(empty($activity_list)){
                    unset($now_activity);
                }
                $extension_image_class = new extension_image();
                foreach($activity_list as &$activity_value){
                    $activity_value['list_pic'] = $extension_image_class->get_image_by_path(array_shift(explode(';',$activity_value['pic'])),'s');
                    $activity_value['url']   =   htmlspecialchars_decode($this->config['site_url'].U('Wapactivity/detail',array('id'=>$activity_value['pigcms_id'])));
                }
            }
        }
        if(empty($time)){
            $time_array['d'] = 0;
            $time_array['h'] = 0;
            $time_array['m'] = 0;
            $time_array['s'] = 0;
        }
        $arr['sActivity']   =   isset($activity_list)?$activity_list:array();
        $arr['activity_time']   =   isset($time_array)?$time_array:array();
        $arr['activity']  =   isset($now_activity)?$now_activity:array();
        //分类信息分类
        if($this->config['wap_home_show_classify']){
            $database_Classify_category = D('Classify_category');
            $Zcategorys = $database_Classify_category->field('`cid`,`cat_name`,`cat_pic`')->where(array('subdir' => 1, 'cat_status' => 1))->order('`cat_sort` DESC,`cid` ASC')->select();
            if (!empty($Zcategorys)) {
                $newtmp = array();
                foreach ($Zcategorys as $key=>$vv) {
                    if(!empty($vv['cat_pic'])){
                        $Zcategorys[$key]['cat_pic'] = $this->config['site_url'].'/upload/system/'.$vv['cat_pic'];
                        $Zcategorys[$key]['cat_url']  =  str_replace('appapi.php','wap.php',htmlspecialchars_decode($this->config['site_url'].U('Classify/index',array('cid'=>$vv['cid'],'ctname'=>urlencode($vv['cat_name']))).'#ct_item_'.$vv['cid']));
                    }else{
                        unset($Zcategorys[$key]);
                    }
                }
            }
        }
        
        //请求红包
        $arr['redpack'] = false;
        if($this->_uid){
            $result = D('LotteryMarketing')->check_redpack($this->_uid);
            if(!$result['error_code']){
                $arr['redpack'] = true;
            }
        }



        if(empty($Zcategorys)){
            $arr['zcategorys']  =   array();
        }else{
            $arr['zcategorys']  =   array_values($Zcategorys);
        }
        $this->returnCode(0,$arr);
    }
    //  猜你喜欢
    public  function    like(){
        $new_group_list = D('Group')->get_group_list('index_sort',8,true);
        $user_long_lat  =   $this->user_long_lat;
        //判断是否微信浏览器，
        if($new_group_list && $user_long_lat){
            $group_store_database = D('Group_store');
            $rangeSort = array();
            foreach($new_group_list as &$storeGroupValue){
                $tmpStoreList = $group_store_database->get_storelist_by_groupId($storeGroupValue['group_id']);
                if($tmpStoreList){
                    foreach($tmpStoreList as &$tmpStore){
                        $tmpStore['Srange'] = getDistance($user_long_lat['lat'],$user_long_lat['long'],$tmpStore['lat'],$tmpStore['long']);
                        $tmpStore['range'] = getRange($tmpStore['Srange'],false);
                        $rangeSort[] = $tmpStore['Srange'];
                    }
                    array_multisort($rangeSort, SORT_ASC, $tmpStoreList);
                    $storeGroupValue['range'] = $tmpStoreList[0]['range'];
                }
                $storeGroupValue['url'] =   $this->config['site_url'].$storeGroupValue['url'];
            }
        }else{
            foreach($new_group_list as &$storeGroupValue){
                $storeGroupValue['url'] =   $this->config['site_url'].$storeGroupValue['url'];
            }
        }
        $new_group_list    =   isset($new_group_list)?$new_group_list:array();
        foreach($new_group_list as $k=>$v){
            $arr[]    =   array(
                'group_id'      =>  $v['group_id'],        //商品ID
                'group_name'    =>  $v['group_name'],      //标题名
                'merchant_name' =>  $v['merchant_name'],   //商店名
                'price'         =>  rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.'),   //商品价格
                'wx_cheap'      =>  $v['wx_cheap'],        //微信立减
                'sale_count'    =>  $v['sale_count']+$v['virtual_num'],      //已售数量
                'old_price'    	=>  $v['old_price'],      //已售数量
                'list_pic'      =>  $v['list_pic'],        //图片url
                'url'           =>  $v['url'],             //跳转url
                'range'         =>  isset($v['range'])?$v['range']:'',           //离我距离
                'pin_num'         =>  $v['pin_num'],           //离我距离
            );
            if($v['tuan_type'] == 2){
				$arr[$k]['s_name']	=	$v['s_name'];
            }else{
				$arr[$k]['s_name']	=	$v['name'];
            }
        }
        $arr	=	isset($arr)?$arr:array();
        $this->returnCode(0,$arr);
    }
    //顶部广告
    public  function    head_adver(){
        $head_adver         = D('Adver')->get_adver_by_key('wap_index_top',5);
        $arr['head_adver']    =   isset($head_adver)?$head_adver:array();
        $this->returnCode(0,$arr);
    }
    //我的社区
    public  function    community(){
        if($this->config['house_open'] == 1){
            $arr['community']['house_open'] =   $this->config['house_open'];
            $arr['community']['img']        =   $this->config['wechat_share_img'];
            $arr['community']['name']       =   '我的社区服务';
            $arr['community']['url']        =   $this->config['site_url'].'/wap.php?g=Wap&c=House&a=village_list';
        }else{
            $arr['community']   =   null;
        }
        $this->returnCode(0,$arr);
    }
    //导航条
    public  function    slider(){
        $tmp_wap_index_slider = D('Slider')->get_slider_by_key('wap_slider',0);
        $wap_index_slider = array();
        foreach($tmp_wap_index_slider as $key=>$value){
            $tmp_i = floor($key/8);
            if(!stristr($value['url'],'Weidian') && !stristr($value['url'],'Invitation')){
                $wap_index_slider[$tmp_i][] = $value;
            }
        }
        foreach($wap_index_slider as $v){
            foreach($v as $vv){
                $vv['url']  =   htmlspecialchars_decode($vv['url']);
                $arr['slider'][]  =   $vv;
            }
        }
        if(empty($arr['slider'])){
            $arr['slider']  =   array();
        }
        $this->returnCode(0,$arr);
    }
    //中间广告
    public  function    adver(){
        $arr['Adver'] = D('Adver')->get_adver_by_key('wap_index_center',4);
        if(empty($arr['Adver'])){
            $arr['Adver']   =   array();
        }
        $this->returnCode(0,$arr);
    }
    //活动列表
    public  function   activity(){
        if($this->config['activity_open']){
            $now_activity = D('Extension_activity')->where(array('begin_time'=>array('lt',$_SERVER['REQUEST_TIME']),'end_time'=>array('gt',$_SERVER['REQUEST_TIME'])))->order('`activity_id` ASC')->find();
            if($now_activity){
                $time = $now_activity['end_time'] - $_SERVER['REQUEST_TIME'];
                $time_array['d'] = floor($time/86400);
                $time_array['h'] = floor($time%86400/3600);
                $time_array['m'] = floor($time%86400%3600/60);
                $time_array['s'] = floor($time%86400%60);

                $activity_list = D('')->field('`eac`.`pigcms_id`,`eac`.`name`,`eac`.`title`,`eac`.`pic`,`eac`.`all_count`,`eac`.`part_count`,`eac`.`price`,`eac`.`mer_score`,`eac`.`type`')->table(array(C('DB_PREFIX').'extension_activity_list'=>'eac',C('DB_PREFIX').'merchant'=>'m'))->where("`eac`.activity_term='{$now_activity['activity_id']}' AND `eac`.`status`='1' AND `eac`.`is_finish`='0' AND `eac`.`index_sort`>0 AND `m`.`city_id`='{$this->config['now_city']}'")->order('`eac`.`index_sort` DESC,`eac`.`pigcms_id` DESC')->limit(3)->select();

                if(empty($activity_list)){
                    unset($now_activity);
                }
                $extension_image_class = new extension_image();
                foreach($activity_list as &$activity_value){
                    $activity_value['list_pic'] = $extension_image_class->get_image_by_path(array_shift(explode(';',$activity_value['pic'])),'s');
                    $activity_value['url']   =   htmlspecialchars_decode($this->config['site_url'].U('Wapactivity/detail',array('id'=>$activity_value['pigcms_id'])));
                }
            }
        }
        $arr['sActivity']   =   isset($activity_list)?$activity_list:array();
        $arr['activity_time']   =   isset($time_array)?$time_array:0;
        $arr['activity']  =   isset($now_activity)?$now_activity:null;
        $this->returnCode(0,$arr);
    }
    //分类信息分类
    public  function    zcategorys(){
        if($this->config['wap_home_show_classify']){
            $database_Classify_category = D('Classify_category');
            $Zcategorys = $database_Classify_category->field('`cid`,`cat_name`,`cat_pic`')->where(array('subdir' => 1, 'cat_status' => 1))->order('`cat_sort` DESC,`cid` ASC')->select();
            if (!empty($Zcategorys)) {
                $newtmp = array();
                foreach ($Zcategorys as $key=>$vv) {
                    if(!empty($vv['cat_pic'])){
                        $Zcategorys[$key]['cat_pic'] = $this->config['site_url'].'/upload/system/'.$vv['cat_pic'];
                        $Zcategorys[$key]['cat_url']  =   str_replace('appapi.php','wap.php',htmlspecialchars_decode($this->config['site_url'].U('Classify/Subdirectory',array('cid'=>$vv['cid'],'ctname'=>urlencode($vv['cat_name'])))));
                    }else{
                        unset($Zcategorys[$key]);
                    }
                }
            }
        }
        if(empty($Zcategorys)){
            $arr['zcategorys']  =   array();
        }else{
            $arr['zcategorys']  =   array_values($Zcategorys);
        }
        $this->returnCode(0,$arr);
    }

    public function rand_coupon(){
        $ticket = I('ticket', false);
        $device_id    =   I('Device-Id',false);
        if($ticket && $device_id){
            $info = ticket::get($ticket, $device_id, true);
            $now_user = D('User')->get_user($info['uid']);
        }else{
            $this->returnCode('20046009');
        }

        if($this->config['open_rand_send']){
            $coupon_html = D('System_coupon')->rand_send_coupon_get(array('time'=>$_SERVER['REQUEST_TIME'],'uid'=>$now_user['uid'],'from'=>'app'));
            if(empty($coupon_html)){
                $this->returnCode(0,array(
                    'coupon'=>array(),
                    'coupon_count'=>0,
                ));

            }else{
                $this->returnCode(0,$coupon_html);
            }

        }else{
            $this->returnCode(0,array(
                'coupon'=>array(),
                'coupon_count'=>0,
            ));
        }

    }

    public function get_city_id_by_name(){
        if(!empty($_POST['lat']) && !empty($_POST['lng'])) {
            $lat = $_POST['lat'];
            $lng = $_POST['lng'];
            $url = 'http://api.map.baidu.com/geocoder/v2/?output=json&ak=4c1bb2055e24296bbaef36574877b4e2&location=' . $lat . ',' . $lng;
            import('ORG.Net.Http');
            $http = new Http();
            $result = $http->curlGet($url);
            $result = json_decode($result, true);
//        $area['area_name'] = $result['result']['addressComponent']['district'];
            $city_name = $result['result']['addressComponent']['city'];
//        $area['province_name'] = $result['result']['addressComponent']['province'];
        }else if(!empty($_POST['now_city'])){
            $city_name = $_POST['now_city'];
        }else{
            $this->returnCode('40000029');
        }
        $long = strlen($city_name);
        if ($long >= 7) {
            $city_name = str_replace('省', '', $city_name);
            $city_name = str_replace('市', '', $city_name);
            $city_name = str_replace('地区', '', $city_name);
            $city_name = str_replace('特别行政区', '', $city_name);
            $city_name = str_replace('特別行政區', '', $city_name);
            $city_name = str_replace('蒙古自治州', '', $city_name);
            $city_name = str_replace('回族自治州', '', $city_name);
            $city_name = str_replace('柯尔克孜自治州', '', $city_name);
            $city_name = str_replace('哈萨克自治州', '', $city_name);
            $city_name = str_replace('土家族苗族自治州', '', $city_name);
            $city_name = str_replace('藏族羌族自治州', '', $city_name);
            $city_name = str_replace('傣族自治州', '', $city_name);
            $city_name = str_replace('布依族苗族自治州', '', $city_name);
            $city_name = str_replace('苗族侗族自治州', '', $city_name);
            $city_name = str_replace('壮族苗族自治州', '', $city_name);
            $city_name = str_replace('澳门', '澳門', $city_name);
            $city_name = str_replace('朝鲜族自治州', '', $city_name);
			$city_name = str_replace('哈尼族彝族自治州','',$city_name);
        }
        $now_city = M('Area')->where(array('area_name'=>$city_name))->find();
        $this->returnCode(0,array('city_id'=>$now_city['area_id'],'city_name'=>$now_city['area_name']));
    }

    public function wxapp_openid(){
        $spread_openid = $_POST['spread_openid'];
        $now_user_openid = $_POST['now_user_openid'];
        $now_user = D('User')->get_user($now_user_openid,'wxapp_openid');
        $spread_user = D('User')->get_user($spread_openid,'wxapp_openid');
        //小程序授权已经创建用户

        if($this->config['open_user_spread']  && $spread_openid && $now_user_openid && $now_user_openid!=$spread_openid && ($now_user['openid']=='' || !M('Merchant_spread')->where(array('openid'=>$now_user['openid'],'mer_id'=>array('gt',0)))->find() )){
            if(!empty($spread_user)){
                if(empty($now_user['openid']) || $this->config['user_spread_replace']){
                    D('User_spread')->where(array('openid'=>$now_user_openid))->delete();
                    $now_user['openid'] &&  D('User_spread')->where(array('openid'=>$now_user['openid']))->delete();
                    D('User_spread')->data(array('spread_openid'=>$spread_openid,'spread_uid'=>$spread_user['uid'],'openid'=>$now_user_openid,'is_wxapp'=>1))->add();
                    D('User_spread')->send_spread_msg($now_user_openid,$spread_openid);
                }
            }
        }
    }
}
?>