<?php
class HomeAction extends BaseAction{
	public function _initialize() {
	    parent::_initialize();

		if(IS_GET){
			$this->database_home_menu = D('Home_menu');

			//底部导航
			$home_menu_list = $this->database_home_menu->getMenuList('plat_footer');
			if($home_menu_list){
				$this->assign('home_menu_list',$home_menu_list);
			}
		}
	}

	public function index(){
		if(!$_GET['no_house'] && cookie('is_house')){
			if(cookie('house_village_id')){
				redirect(str_replace('wap.php','wap_house.php',U('House/village',array('village_id'=>  cookie('house_village_id')))));
			}else{
				redirect(str_replace('wap.php','wap_house.php',U('House/village_list')));
			}
		}else{
			cookie('visit_village_id','');
		}
		//判断是否是微信浏览器，如果是读取微信提供的位置信息。
		if($_SESSION['openid']) $this->assign('user_long_lat',D('User_long_lat')->getLocation($_SESSION['openid']));

		//活动列表
		if($this->config['activity_open']){
			$now_activity = D('Extension_activity')->where(array('begin_time'=>array('lt',$_SERVER['REQUEST_TIME']),'end_time'=>array('gt',$_SERVER['REQUEST_TIME'])))->order('`activity_id` ASC')->find();
            if($now_activity){
				// list($time_array['d'],$time_array['h'],$time_array['m'],$time_array['s']) = explode(' ',date('j H i s',$now_activity['end_time'] - $_SERVER['REQUEST_TIME']));
				$time = $now_activity['end_time'] - $_SERVER['REQUEST_TIME'];
				$time_array['d'] = floor($time/86400);
				$time_array['h'] = floor($time%86400/3600);
				$time_array['m'] = floor($time%86400%3600/60);
				$time_array['s'] = floor($time%86400%60);
				// $activity_list = D('Extension_activity_list')->field('`pigcms_id`,`name`,`title`,`pic`,`all_count`,`part_count`,`price`,`mer_score`,`type`')->where(array('activity_term'=>$now_activity['activity_id'],'status'=>'1','is_finish'=>'0','index_sort'=>array('neq','0')))->order('`index_sort` DESC,`pigcms_id` DESC')->limit(3)->select();
				$activity_list = D('')->field('`eac`.`pigcms_id`,`eac`.`name`,`eac`.`title`,`eac`.`pic`,`eac`.`all_count`,`eac`.`part_count`,`eac`.`price`,`eac`.`money`,`eac`.`mer_score`,`eac`.`type`')->table(array(C('DB_PREFIX').'extension_activity_list'=>'eac',C('DB_PREFIX').'merchant'=>'m'))->where("`eac`.activity_term='{$now_activity['activity_id']}' AND `eac`.`status`='1' AND `eac`.`is_finish`='0' AND `eac`.`index_sort`>0 AND `eac`.`mer_id`=`m`.`mer_id` AND `m`.`city_id`='{$this->config['now_city']}'")->order('`eac`.`index_sort` DESC,`eac`.`pigcms_id` DESC')->limit(3)->select();
                if(empty($activity_list)){
					unset($now_activity);
				}
				$this->assign('now_activity',$now_activity);
				$this->assign('time_array',$time_array);
				$extension_image_class = new extension_image();
				foreach($activity_list as &$activity_value){
					$activity_value['list_pic'] = $extension_image_class->get_image_by_path(array_shift(explode(';',$activity_value['pic'])),'s');
				}
				$this->assign('activity_list',$activity_list);
			}
		}
		//手动首页排序团购
		$index_sort_group_list = D('Group')->get_group_list('index_sort',3,true);
		$this->assign('index_sort_group_list',$index_sort_group_list);

		//顶部广告
		$wap_index_top_adver = D('Adver')->get_adver_by_key('wap_index_top',5);
		$this->assign('wap_index_top_adver',$wap_index_top_adver);

		//中间广告
		$wap_index_center_adver = D('Adver')->get_adver_by_key('wap_index_center',4);
		$this->assign('wap_index_center_adver',$wap_index_center_adver);
		
		//房产广告
		if($this->config['wap_index_house_adver']){
		  $wap_index_house_adver = D('Adver')->get_adver_by_key('wap_index_house',6);
		  $this->assign('wap_index_house_adver',$wap_index_house_adver);
		}
	
		//首页附近模块
		$around = M('Wap_around')->order('sort DESC')->limit(3)->select();
		$this->assign('wap_around',$around);

		//导航条
		$tmp_wap_index_slider = D('Slider')->get_slider_by_key('wap_slider',0);

		$wap_index_slider = array();
		
		if(count($tmp_wap_index_slider) >= 10 &&$this->config['wap_slider_number'] == 10){
			$wap_slider_number = $this->config['wap_slider_number'];
		}else{
			$wap_slider_number = 8;
		}
		
		foreach($tmp_wap_index_slider as $key=>$value){
			$tmp_i = floor($key/$wap_slider_number);
			$wap_index_slider[$tmp_i][] = $value;
		}

		$this->assign('wap_index_slider',$wap_index_slider);
		$this->assign('wap_index_slider_number',$wap_slider_number);
		
		//平台快报
		$news_list = M('')->field('`n`.`id`,`n`.`title`,`c`.`name`')->table(array(C('DB_PREFIX').'system_news'=>'n',C('DB_PREFIX').'system_news_category'=>'c'))->where("`n`.`status`='1' AND `c`.`id`=`n`.`category_id`")->order('`n`.`sort` DESC,`n`.`id` DESC')->limit(8)->select();
		$this->assign('news_list',$news_list);
		if( $this->user_session['uid'] && $this->config['open_rand_send']){
			 $coupon_html = D('System_coupon')->rand_send_coupon_get(array('time'=>$_SERVER['REQUEST_TIME'],'uid'=>$this->user_session['uid']));
			$coupon_html && $this->assign('coupon_html',$coupon_html);
		}

		//最新20条团购
//		$new_group_list = D('Group')->get_group_list('index_sort',15,true);
//		$this->assign('new_group_list',$new_group_list);

		//分类信息分类
		if($this->config['wap_home_show_classify']){
			$database_Classify_category = D('Classify_category');
			$Zcategorys = $database_Classify_category->field('`cid`,`cat_name`,`cat_pic`')->where(array('subdir' => 1, 'cat_status' => 1))->order('`cat_sort` DESC,`cid` ASC')->select();
            if (!empty($Zcategorys)) {
				$newtmp = array();
				foreach ($Zcategorys as $vv) {
					if(!empty($vv['cat_pic'])){
						$vv['cat_pic'] = $this->config['site_url'].'/upload/system/'.$vv['cat_pic'];
					}else{
						continue;
					}
					unset($vv['cat_field']);
					$subdir_info = $this->get_Subdirectory($vv['cid'], 1);
					if(!empty($subdir_info)){
						$vv['subdir'] = $subdir_info;
					}
					$newtmp[$vv['cid']] = $vv;
					if(count($vv['subdir'])%3 != 0){
						$newtmp[$vv['cid']]['subEmptyCount'] = 3-(count($vv['subdir'])%3);
					}
				}
				$Zcategorys = $newtmp;
			}
			$this->assign('classify_Zcategorys', $Zcategorys);
			//dump($Zcategorys);exit;
		}
		/* 粉丝行为分析 */
		//$this->behavior(array('model'=>'Home_index'),true);

		$model = new Model();
		$sql = " select m.pic_info, m.logo,m.name, m.mer_id, m.share_open from " . C('DB_PREFIX') . "merchant as m inner join " . C('DB_PREFIX') . "merchant_user_relation as r on r.mer_id=m.mer_id where r.openid='{$_SESSION['openid']}' order by r.dateline asc limit 1";
		$result = $model->query($sql);
		$now_merchant = isset($result[0]) && $result[0] ? $result[0] : null;
		if ($now_merchant) {
			$pic = '';
			if ($now_merchant['pic_info']) {
				$images = explode(";", $now_merchant['pic_info']);
				$merchant_image_class = new merchant_image();
				$images = explode(";", $images[0]);
				$pic = $merchant_image_class->get_image_by_path($images[0]);
				if($now_merchant['logo']){
					$pic = $this->config['site_url'].$now_merchant['logo'];
				}
			}
			switch ($this->config['home_share_show_open']) {
				case 0: //总关闭
					if ($now_merchant['share_open'] == 1) {
						$share = D('Home_share')->where(array('mer_id' => $now_merchant['mer_id']))->find();
						if (empty($share)) {
						    $share = array('title' => str_replace('{title}', msubstr($now_merchant['name'], 0, 6), $this->config['home_share_txt']), 'a_name' => '进入', 'a_href' => $this->config['site_url'] . '/wap.php?c=Index&a=index&token=' . $now_merchant['mer_id']);
						}
						$share['image'] = $pic;
						$this->assign('share', $share);
					} elseif ($now_merchant['share_open'] == 2) {
						header('Location:' . $this->config['site_url'] . '/wap.php?c=Index&a=index&token=' . $now_merchant['mer_id']);
						exit();
					}
					break;
				case 1:	//全开启首页广告
					$share = D('Home_share')->where(array('mer_id' => $now_merchant['mer_id']))->find();
					if (empty($share)) {
					    $share = array('title' => str_replace('{title}', msubstr($now_merchant['name'], 0, 6), $this->config['home_share_txt']), 'a_name' => '进入', 'a_href' => $this->config['site_url'] . '/wap.php?c=Index&a=index&token=' . $now_merchant['mer_id']);
					}
					$share['image'] = $pic;
					$this->assign('share', $share);
					break;
				case 2:	//全开启跳转到首页
					header('Location:' . $this->config['site_url'] . '/wap.php?c=Index&a=index&token=' . $now_merchant['mer_id']);
					exit();
					break;
			}
		}
		if($this->config['show_scroll_msg']){
			$scroll_msg = D('Scroll_msg')->get_msg();
			$this->assign('scroll_msg',$scroll_msg);
		}
		$guess_num = C('config.guess_num');
		$this->assign('guess_num',$guess_num);
		$guess_content_type = C('config.guess_content_type');
		$this->assign('guess_content_type',$guess_content_type);
		$this->display();
	}
	public function near_info(){
		$condition_where  = "`status`='1'";
		switch($_POST['type']){
			case 'merchant':
// 				$condition_where  = '';
				break;
			case 'meal':
				$condition_where .= " AND `have_meal`='1'";
				break;
			case 'group':
				$condition_where .= " AND `have_group`='1'";
				break;
			default:
				$this->error('非法访问！');
		}
		$x = $_POST['lat'];
		$y = $_POST['long'];

		import('@.ORG.longlat');
		$longlat_class = new longlat();
		$location = $longlat_class->gpsToBaidu($x,$y);//转换腾讯坐标到百度坐标
		$x = $location['lat'];
		$y = $location['lng'];
		if($this->is_wexin_browser && !empty($_SESSION['openid'])){
			$condition_user_long_lat['open_id'] = $_SESSION['openid'];
			$data_user_long_lat['lat'] = $x;
			$data_user_long_lat['long'] = $y;
			$data_user_long_lat['dateline'] = $_SERVER['REQUEST_TIME'];
			$database_user_long_lat = D('User_long_lat');
			if($database_user_long_lat->field('`open_id`')->where($condition_user_long_lat)->find()){
				$database_user_long_lat->where($condition_user_long_lat)->data($data_user_long_lat)->save();
			}else{
				$data_user_long_lat['open_id'] = $_SESSION['openid'];
				$database_user_long_lat->data($data_user_long_lat)->add();
			}
		}

		$store_list = D("Merchant_store")->field("*, ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$x}*PI()/180-`lat`*PI()/180)/2),2)+COS({$x}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$y}*PI()/180-`long`*PI()/180)/2),2)))*1000) AS juli")->where($condition_where)->order('`juli` ASC')->limit('0,6')->select();

		if(!empty($store_list)){
			$store_image_class = new store_image();
			foreach($store_list as &$store){
				$images = $store_image_class->get_allImage_by_path($store['pic_info']);
				$store['image'] = $images ? array_shift($images) : '';

				if($store['juli'] > 1000){
					$store['juli'] = ' '.floatval(round($store['juli']/1000,1)).' 千米';
				}else{
					$store['juli'] = ' '.$store['juli'].' 米';
				}

				switch($_POST['type']){
					case 'merchant':
						$store['url'] = U('Index/index',array('token'=>$store['mer_id']));
						break;
					case 'meal':
						$store['url'] = U('Meal/menu',array('mer_id'=>$store['mer_id'],'store_id'=>$store['store_id']));
						break;
					case 'group':
						$store['url'] = U('Group/shop',array('store_id'=>$store['store_id']));
						break;
					default:
						$this->error('非法访问！');
				}
			}
			echo json_encode(array('error'=>0,'store_list'=>$store_list));
		}else{
			echo json_encode(array('error'=>1));
		}
	}

	public function group_index_sort(){
		$group_id = $_POST['id'];
		$database_index_group_hits = D('Index_group_hits');
		$data_index_group_hits['group_id'] = $group_id;
		$data_index_group_hits['ip']		= get_client_ip();
		if(!$database_index_group_hits->field('`group_id`')->where($data_index_group_hits)->find()){
			$condition_group['group_id'] = $group_id;
			if(M('Group')->where($condition_group)->setDec('index_sort')){
				if ($this->config['is_open_click_fans'] && $_SESSION['openid']) {
					$group = M('Group')->where($condition_group)->find();
					if (!($relation = D('Merchant_user_relation')->field(true)->where(array('openid' => $_SESSION['openid'], 'mer_id' => $group['mer_id']))->find())) {
						D('Merchant_user_relation')->add(array('openid' => $_SESSION['openid'], 'mer_id' => $group['mer_id'], 'dateline' => time(), 'from_merchant' => 3));//点击获取的粉丝类型
					}
				}
				$data_index_group_hits['time'] = $_SERVER['REQUEST_TIME'];
				$database_index_group_hits->data($data_index_group_hits)->add();
			}
		}
	}
	private function get_Subdirectory($cid, $subdir, $m = 2) {
        $Classify_categoryDb = M('Classify_category');
        $Subdirectory = array();
        $where = false;
        if ($m == 2) {
            $where = array('fcid' => $cid, 'subdir' => 2, 'cat_status' => 1);
        } elseif ($m == 3) {
            if ($subdir == 1) {
                $where = array('pfcid' => $cid, 'subdir' => 3, 'cat_status' => 1);
            } else {
                $where = array('fcid' => $cid, 'subdir' => 3, 'cat_status' => 1);
            }
        }
        if ($where) {
            $Subdirectory = $Classify_categoryDb->field('`cid`,`cat_name`')->where($where)->order('`cat_sort` DESC,`cid` ASC')->select();
        }
        return $Subdirectory;
    }
	public  function    ajaxLogin(){
        echo 1;
    }


	private function parseCoupon($coupon_list , $type){
		$returnObj = array();
		foreach($coupon_list as $val){

		}
	}
	
	public  function cityMatching(){
		$city_name = $_POST['city_name'];
        $long   =   strlen($city_name);
        if($long >= 7){
            $city_name    =   str_replace('市','',$city_name);
            $city_name    =   str_replace('地区','',$city_name);
            $city_name    =   str_replace('特别行政区','',$city_name);
			$city_name    =   str_replace('特別行政區','',$city_name);
            $city_name    =   str_replace('蒙古自治州','',$city_name);
            $city_name    =   str_replace('回族自治州','',$city_name);
            $city_name    =   str_replace('柯尔克孜自治州','',$city_name);
            $city_name    =   str_replace('哈萨克自治州','',$city_name);
            $city_name    =   str_replace('土家族苗族自治州','',$city_name);
            $city_name    =   str_replace('藏族羌族自治州','',$city_name);
            $city_name    =   str_replace('傣族自治州','',$city_name);
            $city_name    =   str_replace('布依族苗族自治州','',$city_name);
			$city_name    =   str_replace('苗族侗族自治州','',$city_name);
			$city_name    =   str_replace('壮族苗族自治州','',$city_name);
            $city_name    =   str_replace('澳门','澳門',$city_name);
			$city_name    =   str_replace('朝鲜族自治州','',$city_name);
			$city_name 	  =   str_replace('哈尼族彝族自治州','',$city_name);
			$city_name    =   str_replace('傣族景颇族自治州','',$city_name);
        }
        $database_area = D('Area');
        $database_field = '`area_id`,`area_pid`,`area_name`,`area_url`';
        $condition_all_city['area_name'] = $city_name;
		$condition_all_city['area_type'] = 2;
		
		if(!$_POST['all_city']){
			$condition_all_city['is_open'] = 1;
		}
        $oCity = $database_area->field($database_field)->where($condition_all_city)->find();
        if($oCity){
			if($_POST['get_province']){
				$pCity = D('Area')->where(array('area_id'=>$oCity['area_pid']))->find();
				$oCity['province_name'] = $pCity['area_name'];
				$oCity['province_id'] = $pCity['area_id'];
			}
			if($_POST['area_name']){
				$maybe_areaname = str_replace(array('区','县'),'',$_POST['area_name']);
				$now_area = D('Area')->where(array('_string'=>"`area_pid`= '{$oCity['area_id']}' AND (`area_name`='{$_POST['area_name']}' OR `area_name`='{$maybe_areaname}')"))->find();
				
				if(empty($now_area)){
					import('@.ORG.words');
					$words_class = new words();
					$data_area['area_pid'] = $oCity['area_id'];
					$data_area['area_name'] = $_POST['area_name'];
					$data_area['area_url'] = $words_class->stringgetFirstCharter($_POST['area_name']);
					$data_area['area_type'] = '3';
					if($data_area['area_id'] = D('Area')->data($data_area)->add()){
						$now_area = $data_area;
					}
				}
				
				$oCity['now_area_id'] = $now_area['area_id'];
				$oCity['now_area_name'] = $now_area['area_name'];
				
				$oCity['province_name'] = $pCity['area_name'];
				$oCity['province_id'] = $pCity['area_id'];
			}
            $this->success($oCity);
        }else{
			$this->error('未开启当前城市');
        }
    }

	public function jump_im(){
		if ($_SESSION['openid']) {
			$key = $this->get_encrypt_key(array('app_id'=>$this->config['im_appid'],'openid' => $_SESSION['openid']), $this->config['im_appkey']);
			$kf_url = ($this->config['im_url'] ? $this->config['im_url'] : 'http://im-link.weihubao.com').'/?app_id=' . $this->config['im_appid'] . '&openid=' . $_SESSION['openid'] . '&key=' . $key . '#serviceList_' . $_GET['mer_id'];
			redirect($kf_url);
		}
	}
	
	public function get_merchant_card_spread_info(){
		$merchant_card_spread_info = D('Card_new')->get_card_by_mer_id_and_uid($_POST['mer_id'],$this->user_session['uid']);
		$this->success($merchant_card_spread_info);
	}
}

?>