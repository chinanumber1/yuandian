<?php

//社区O2O

class HouseAction extends BaseAction{
	protected $village_bind;
	protected $send_time_type = array('分钟', '小时', '天', '周', '月');
	public function __construct(){
		parent::__construct();
		$this->village_bind = $_SESSION['now_village_bind'];
		if($_GET['village_id']){
			if((!cookie('house_village_id')||(cookie('house_village_id')!=$_GET['village_id']))&&(cookie('is_house'))){
				setcookie('house_village_id',$_GET['village_id'],time() + 3600 * 24);
			}
		}
		// 判断社区是否到期
		if( ACTION_NAME!='village_list' && ACTION_NAME!='ajax_village_list'  && ACTION_NAME!='village_select' ){
			$now_house = D('House_village')->field(true)->where(array('village_id'=>$_SESSION['now_village_bind']['village_id']))->find();
			if (isset($now_house['expiration_time']) && $now_house['expiration_time'] && $now_house['expiration_time']<time()) {
				$this->error_tips('该小区已到期，请联系小区管理员',U('House/village_list'));
			}

		}
	}
	public $pay_list_type = array(
			'property'=>'物业费',
			'water'=>'水费',
			'electric'=>'电费',
			'gas'=>'燃气费',
			'park'=>'停车费',
			'custom'=>'其他缴费',
			'custom_payment'=>'自定义缴费',
	);
	public function check_village_session($village_id){
		if(empty($this->village_bind) && !empty($this->user_session) ||$this->village_bind['uid']!=$this->user_session['uid']){
			D('House_village')->get_bind_list($this->user_session['uid'],$this->user_session['phone']);
			$bind_village_list = D('House_village_user_bind')->get_user_bind_list($this->user_session['uid'],$village_id);

			if(!$bind_village_list){
				$bind_village_list = D('House_village_user_bind')->get_family_user_bind_list($this->user_session['uid'],$village_id);
				if(!$bind_village_list){
					$bind_village_list = D('House_village_user_bind')->get_user_bind_list($bind_village_list[0]['parent_id'],$village_id);
				}
			}
			if(!empty($bind_village_list)){
				if(count($bind_village_list) == 1){
					$this->village_bind = $_SESSION['now_village_bind'] = $bind_village_list[0];
				}else{
					redirect(U('House/village_select',array('village_id'=>$village_id,'referer'=>urlencode($_SERVER['REQUEST_URI']))));
				}
			}
		}elseif(!empty($this->village_bind) && ($this->village_bind['village_id'] != $village_id)){
			$this->village_bind = array();
		}
	}


	public function bind_village(){
		$database_house_village_floor = D('House_village_floor');
		$database_house_village_user_vacancy = D('House_village_user_vacancy');
		$village_id =  $_GET['village_id'] + 0;

		if(empty($this->user_session['phone'])){
			$this->error_tips('请先绑定手机号码',U('My/bind_user',array('referer'=>urlencode(U('village_my',array('village_id'=>$_GET['village_id']))))));
		}
		
		if(!$village_id){
			$this->error_tips('传递参数有误！');
		}
		$condition['village_id'] =  $village_id;
		if($_GET['type'] == 'go'){
			$condition['status'] =  1;
			$unit_list = $database_house_village_floor->field(true)->where($condition)->select();

			if(!$unit_list){
				$this->error_tips('暂无小区单元信息，请联系管理员');
			}

			$this->assign('unit_list',$unit_list);
			$this->display('go_bind_village');
		}elseif($_GET['type'] == 'go_layer'){
			$floor_id = $_GET['floor_id'] + 0;
			if(!$floor_id){
				$this->error_tips('传递参数有误！');
			}
			$database_house_village_user_bind = D('House_village_user_bind');

			$bind_where['parent_id'] = 0;
			$bind_where['village_id'] = $village_id;
			$bind_where['floor_id'] = $condition['floor_id'] = $_GET['floor_id'] + 0;
			//$condition['is_del'] = 0;
			$condition['status'] = 1;
			$bind_list = $database_house_village_user_bind->where($bind_where)->select();
			$vacancy_list = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($condition , true ,'pigcms_id desc' , 999999);
			$vacancy_list = $vacancy_list['result']['list'];

			if($vacancy_list){
				foreach($vacancy_list as $Key=>$row){
					$vacancy_list[$Key]['address'] = $row['layer'].$row['room'];
					$vacancy_list[$Key]['is_vacancy'] = true;
				}

				$bind_list = array_merge($bind_list , $vacancy_list);
			}

			if(!$bind_list){
				$this->error_tips('暂无房屋数据。');
			}

			$this->assign('bind_list',$bind_list);
			$this->display('go_layer_bind_village');
		}else{
			$this->display();
		}
	}


	public function bind_village_info(){
		$database_house_village_user_bind = D('House_village_user_bind');
		$database_house_village_user_vacancy = D('House_village_user_vacancy');

		$pigcms_id = $_GET['pigcms_id'] + 0;
		$village_id = $_GET['village_id'] + 0;
		$bind_where['village_id'] = $village_id;
		$bind_where['pigcms_id'] = $pigcms_id;
		$is_vacancy = $_GET['is_vacancy'] + 0;
		if(!$is_vacancy){
			$bind_where['parent_id'] = 0;
			$bind_info = $database_house_village_user_bind->where($bind_where)->find();
		}else{
			//$bind_where['is_del'] = 0;
			$bind_info = $database_house_village_user_vacancy->where($bind_where)->find();
		}

		if(!$bind_info){
			$this->error_tips('信息不存在。');
		}
		if($bind_info['status'] == 2){
			$this->error_tips('审核已提交，请耐心等待。');
		}

		if($bind_info['status'] == 3){
			$this->success_tips('审核已通过。');
		}

		if(IS_POST){
			$type = $_POST['type'] + 0;
			$phone = $_POST['phone'];
			$name = trim($_POST['name']);
			$chk_phone = $_POST['chk_phone'] + 0;


			if(!$this->config['international_phone'] && !preg_match('/^[0-9]{11}$/',$phone)){
				$this->error_tips('请输入有效的手机号。');
			}

			$uid = D('User')->get_user_by_phone($_POST['phone']);
			if($uid > 0){
				$bind_data['uid'] = $uid;
			}else{
				$bind_data['uid'] = 0;
			}

			$bind_data['housesize'] = $_POST['housesize'] + 0;
			$bind_data['park_flag'] = $_POST['park_flag'] + 0;
			if($type == 0){
				$bind_data['name'] = $name;
				$bind_data['phone'] = $phone;
				$bind_data['memo'] = $_POST['memo'];
				$bind_data['type'] = 0;
				$bind_data['status'] = 2;
				$bind_data['application_time'] = time();
				$insert_id = $database_house_village_user_vacancy->data($bind_data)->where($bind_where)->save();
				if($insert_id){
					$this->success_tips('提交审核成功！');
				}else{
					$this->error_tips('提交审核失败！');
				}
			}elseif($type == 1){//家人
				if(!$is_vacancy){
					if(mb_substr($bind_info['phone'],-4) != $chk_phone){
						$this->error_tips('业主手机号后四位输入不正确。');
					}
					$bind_where['parent_id'] = 0;
					$bind_info = $database_house_village_user_bind->where($bind_where)->find();
					if($phone == $bind_info['phone']){
						$this->error_tips('手机号不能与业主一致');
					}

					$bind_condition['parent_id'] = $pigcms_id;
					$bind_condition['phone'] = $phone;
					$family_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
					if($family_bind_info){
						$this->error_tips('已提交审请，请您耐心等待。');
					}

					$bind_data = $bind_info;
					unset($bind_data['pigcms_id']);

					$bind_data['name'] = $name;
					$bind_data['type'] = 1;
					$bind_data['status'] = 2;
					$bind_data['phone'] = $phone;
					$bind_data['add_time'] = time();
					$bind_data['usernum'] = rand(0,99999) . '-' . time();
					$bind_data['parent_id'] = $pigcms_id;
					$bind_data['memo'] = $_POST['memo'];
					$insert_id = $database_house_village_user_bind->data($bind_data)->add();
					if($insert_id){
						$this->success_tips('提交审核成功！');
					}else{
						$this->error_tips('提交审核失败！');
					}
				}else{
					if($bind_info['status'] == 2){
						$this->error_tips('已成功提交审核，请耐心等待！');
					}

					$bind_data['uid'] = $uid;
					$bind_data['name'] = $name;
					$bind_data['phone'] = $phone;
					$bind_data['type'] = 1;
					$bind_data['status'] = 2;
					$bind_data['memo'] = $_POST['memo'];

					$insert_id = $database_house_village_user_vacancy->data($bind_data)->where($bind_where)->save();
					if($insert_id){
						$this->success_tips('提交审核成功！');
					}else{
						$this->error_tips('提交审核失败！');
					}
				}
			}elseif($type == 2){//租客
				if(!$is_vacancy){
					$bind_where['parent_id'] = 0;
					$bind_info = $database_house_village_user_bind->where($bind_where)->find();
					if($phone == $bind_info['phone']){
						$this->error_tips('手机号不能与业主一致');
					}

					$bind_condition['parent_id'] = $pigcms_id;
					$bind_condition['phone'] = $phone;
					$renter_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
					if($renter_bind_info){
						$this->error_tips('已提交审请，请您耐心等待。');
					}

					$bind_data = $bind_info;
					unset($bind_data['pigcms_id']);
					$uid = D('User')->get_user_by_phone($_POST['phone']);
					if($uid > 0){
						$bind_data['uid'] = $uid;
					}else{
						$bind_data['uid'] = 0;
					}

					$bind_data['name'] = $name;
					$bind_data['type'] = 2;
					$bind_data['status'] = 2;
					$bind_data['phone'] = $phone;
					$bind_data['add_time'] = time();
					$bind_data['usernum'] = rand(0,99999) . '-' . time();
					$bind_data['parent_id'] = $pigcms_id;
					$bind_data['memo'] = $_POST['memo'];
					$insert_id = $database_house_village_user_bind->data($bind_data)->add();
					if($insert_id){
						$this->success_tips('提交审核成功！');
					}else{
						$this->error_tips('提交审核失败！');
					}
				}else{
					$bind_data['name'] = $name;
					$bind_data['type'] = 2;
					$bind_data['status'] = 2;
					$bind_data['phone'] = $phone;
					$bind_data['add_time'] = time();
					$bind_data['usernum'] = $bind_info['usernum'];
					$bind_data['memo'] = $_POST['memo'];
					$bind_data['village_id'] = $village_id;
					$insert_id = $database_house_village_user_vacancy->data($bind_data)->where($bind_where)->save();
					if($insert_id){
						$this->success_tips('提交审核成功！');
					}else{
						$this->error_tips('提交审核失败！');
					}
				}
			}elseif($type == 3){//替换业主
				if(!$is_vacancy){
					$bind_where['parent_id'] = 0;
					$bind_info = $database_house_village_user_bind->where($bind_where)->find();
					if($phone == $bind_info['phone']){
						$this->error_tips('手机号不能与业主一致');
					}

					$bind_condition['parent_id'] = $pigcms_id;
					$bind_condition['phone'] = $phone;
					$owner_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
					if($owner_bind_info){
						$this->error_tips('已提交审请，请您耐心等待。');
					}

					$bind_data = $bind_info;
					unset($bind_data['pigcms_id']);
					$uid = D('User')->get_user_by_phone($_POST['phone']);
					if($uid > 0){
						$bind_data['uid'] = $uid;
					}else{
						$bind_data['uid'] = 0;
					}

					$bind_data['name'] = $name;
					$bind_data['type'] = 3;
					$bind_data['status'] = 2;
					$bind_data['phone'] = $phone;
					$bind_data['add_time'] = time();
					$bind_data['usernum'] = rand(0,99999) . '-' . time();
					$bind_data['parent_id'] = $pigcms_id;
					$bind_data['memo'] = $_POST['memo'];
					$insert_id = $database_house_village_user_bind->data($bind_data)->add();
					//$insert_id = $database_house_village_user_bind->data($bind_data)->where($bind_where)->save();
					if($insert_id){
						$this->success_tips('提交审核成功！');
					}else{
						$this->error_tips('提交审核失败！');
					}
				}else{
					$this->error_tips('提交有误！');
				}
			}else{

			}
		}else{
			$this->assign('bind_info',$bind_info);
			$this->display();
		}
	}

	//小区列表
	public function village_list(){
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		$this->assign('long_lat',$long_lat);
		//只有一个社区自动跳转
		if(empty($_GET['choose'])){

			$bind_village_list = D('House_village')->get_bind_list($this->user_session['uid'],'',true);
			
			if(count($bind_village_list) == 1){
				// 判断社区是否到期 到期则不自动跳转
				if ( $bind_village_list[0]['expiration_time']>=time()) {
					redirect(U('village_select',array('village_id'=>$bind_village_list[0]['village_id'])));
				}
			}
		}

		$this->display();
	}
	public function ajax_village_list(){
		$this->header_json();
		if($_POST['user_long'] && $_POST['lat']){
			$long_lat = array(
				'long'=>$_POST['user_long'],
				'lat'=>$_POST['user_lat'],
			);
		}else if($_SESSION['openid']){
			$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
		}
		$return = array();

		//找到用户已绑定的小区
		if($this->user_session && $_GET['page'] < 2 && empty($_POST['keyword'])){
			$bind_village_list = D('House_village')->get_bind_list($this->user_session['uid'],$this->user_session['phone']);
	
			//判断得到用户位置
			if($bind_village_list && $long_lat){
				$rangeSort = array();
				foreach($bind_village_list as &$village_value){
					$village_value['range_int'] = getDistance($village_value['lat'],$village_value['long'],$long_lat['lat'],$long_lat['long']);
					$village_value['range'] = getRange($village_value['range_int']);
					$rangeSort[] = $village_value['range_int'];
				}
				array_multisort($rangeSort, SORT_ASC, $bind_village_list);
			}
			if($_SERVER['HTTP_HOST'] == 'hf.pigcms.com' && $bind_village_list[0]['first_test']){
				$return['first_test'] = true;
			}
			$return['bind_village_list'] = $bind_village_list;
		}
		if(empty($this->user_session) && $_GET['page'] < 2 && empty($_POST['keyword'])){
			$return['login_test'] = true;
		}

		//绑定家属小区
		$database_house_village_user_bind = D('House_village_user_bind');
		$database_house_village = D('House_village');
		$where['phone'] = $this->user_session['phone'];
		$where['parent_id'] = array('neq',0);
		$where['status'] = array('eq',1); //2017-05-09 
		$parent_id_arr = $database_house_village_user_bind->where($where)->getField('pigcms_id,parent_id');
		if(!empty($parent_id_arr)){
			$Map['pigcms_id'] = array('in' , $parent_id_arr);
			$Map['uid'] = array('gt' , 0);
			$bind_family_list = $database_house_village->get_bind_family_list($Map);
			foreach($bind_family_list as $v){
				$return['bind_village_list'][]=$v;
			}
		}

		$return['village_list'] = D('House_village')->wap_get_list($long_lat,$_POST['keyword'],$_POST['account']);
		if(empty($return['village_list'])){
			unset($return['village_list']);
		}
		echo json_encode($return);
	}
	//户号选择，只有一个房间自动跳转
	public function village_select(){
		if($_GET['village_id']){
			$now_village = $this->get_village($_GET['village_id']);
			$referer = $_GET['referer'] ? htmlspecialchars_decode($_GET['referer']) : U('House/village',array('village_id'=>$_GET['village_id']));
		}else{
			$this->error_tips('非法访问');
		}

		$database_house_village_user_bind = D('House_village_user_bind');
		$database_house_village = D('House_village');
		if(!empty($this->user_session)){
			if($_GET['bind_id']){
				$bind_where['parent_id'] = $_GET['bind_id'];
				$bind_where['village_id'] = $_GET['village_id'];
				$bind_where['uid']=$_SESSION['user']['uid'];
				$bind_where['phone']=$_SESSION['user']['phone'];
				$pigcms_id = $database_house_village_user_bind->where($bind_where)->getField('pigcms_id');
				if($pigcms_id){
					$bind_village_info = $database_house_village_user_bind->get_one_by_bindId($pigcms_id);
					$bind_village_info = $database_house_village_user_bind->where(array('pigcms_id'=>$bind_village_info['parent_id'],'parent_id'=>0))->order('`pigcms_id` DESC')->find();
					$bind_village_info['flag'] = 1;
					if(!empty($bind_village_info)){
						$_SESSION['now_village_bind'] = $bind_village_info;
						redirect($referer);
					}
				}else{
					$bind_village = $database_house_village_user_bind->get_one($_GET['village_id'],$_GET['bind_id'],'pigcms_id');
					if(empty($bind_village) || $bind_village['uid'] != $this->user_session['uid']){
						$this->error_tips('非法访问');
					}
					$_SESSION['now_village_bind'] = $bind_village;
					redirect($referer);
				}
			}else{
				
				if($pigcms_id = $_GET['pigcms_id'] + 0){
					$house_village_user_bind_info = $database_house_village_user_bind->get_one_by_bindId($pigcms_id);
					if(!$house_village_user_bind_info['uid']){
						$this->error_tips('尚未是平台用户！');
					}

					$database_house_village->get_bind_list($house_village_user_bind_info['uid'],$house_village_user_bind_info['phone']);
					$bind_village_list = $database_house_village_user_bind->get_user_bind_list($house_village_user_bind_info['uid'],$_GET['village_id']);

					$binded_where['uid'] = $this->user_session['uid'];
					$binded_where['parent_id'] = array('neq',0);
					$binded_where['village_id'] = $_GET['village_id'];
					$binded_where['phone'] = $this->user_session['phone'];
					$binded_where['status'] = 1;
					$binded_village_arr = $database_house_village_user_bind->where($binded_where)->getField('parent_id',true);
					foreach($bind_village_list as $k => $v){
						$bind_village_list[$k]['flag'] = 1;
						if(in_array($v['pigcms_id'],$binded_village_arr)){
							$bind_village_list[$k]['is_allow'] = 1;
						}
					}
				}else{
					$database_house_village->get_bind_list($this->user_session['uid'],$this->user_session['phone']);
					
					$bind_village_list = $database_house_village_user_bind->get_user_bind_list($this->user_session['uid'],$_GET['village_id']);
					
				}
				if(empty($bind_village_list)){
					redirect($referer);
				}else{
					if(count($bind_village_list) == 1){ //只有一个小区
						$_SESSION['now_village_bind'] = $bind_village_list[0];
						redirect($referer);
					}else{
						$this->assign('bind_village_list',$bind_village_list);
					}
				}
			}
		}else{
			redirect($referer);
		}
		$this->assign('referer',$referer);
		$this->display();
	}
	public function village(){
		if(!$_GET['village_id']){
			redirect(U('village',array('village_id'=>cookie('house_village_id'))));
		}

		$database_shequ_slider = D('House_village_slider');
		$database_merchant_store_shop = D('Merchant_store_shop');
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);

		$tourist = $this->getHasConfig($now_village['village_id'],'tourist');
		if($tourist!=1){
			$this->village_my_check();
		}
		
		$has_slide = $this->getHasConfig($now_village['village_id'],'has_slide');

		if($has_slide){
			//幻灯片
			$where['village_id'] = $now_village['village_id'];
			$where['status'] = '1';
			$where['type'] = '0';
			$slider_list = $database_shequ_slider->where($where)->order('`sort` DESC,`id` ASC')->select();
			if(defined('IS_INDEP_HOUSE')){
				foreach($slider_list as $k=>$v){
					$slider_list[$k]['url'] = str_replace('wap.php', C('INDEP_HOUSE_URL'), $v['url']);
				}
			}

			$this->assign('slider_list',$slider_list);
		}
		
		$has_index_store = $this->getHasConfig($now_village['village_id'],'has_index_store');
		$this->assign('has_index_store',$has_index_store);


		if( $this->user_session['uid'] && $this->config['open_rand_send']){
			 $coupon_html = D('System_coupon')->rand_send_coupon_get(array('time'=>$_SERVER['REQUEST_TIME'],'uid'=>$this->user_session['uid']));
			$coupon_html && $this->assign('coupon_html',$coupon_html);
		}
		
		//获取快店 团购 别名
		//$database_config = D('Config');
		//$houseConfig = $database_config->get_config();
		//$this->assign('houseConfig' , $houseConfig);

		
		//$has_slide = $this->getHasConfig($now_village['village_id'],'has_slide');

		//找到模板排序
		/*$displayArr = explode(' ',$this->config['house_display']);
		$displayTplArr = array(
				1=>'village_index_news',
				2=>'village_index_pay',
				3=>'village_index_group',
				4=>'village_index_meal',
				5=>'village_index_appoint',
				6=>'village_index_bbs',
				7=>'village_index_activity',
		);

		$displayIncludeTplArr = array();
		foreach($displayArr as $value){
			if($value >= 1 && $value <= 7){
				$displayIncludeTplArr[] = $displayTplArr[$value];
			}
		}

		$this->assign('displayIncludeTplArr',implode(',',$displayIncludeTplArr));*/

		//if(in_array('1',$displayArr)){
			//5条最新新闻
			$news_list = D('House_village_news')->get_limit_list($now_village['village_id'],3);
			$this->assign('news_list',$news_list);
		//}

		//if(in_array('2',$displayArr)){
			$nav_where['village_id'] = $now_village['village_id'];
			$nav_where['status'] = 1;

			if($this->config['house_bbsservice_limit']){
				$tmp_index_service_cat_list = D('House_village_nav')->house_village_nav_page_list($nav_where,true,'sort desc',20);
			}else{
				$tmp_index_service_cat_list = D('House_village_nav')->house_village_nav_page_list($nav_where,true,'sort desc',99999);
			}

			$index_service_cat_list = array();
			foreach($tmp_index_service_cat_list['result']['list'] as $key=>$value){
				$tmp_i = floor($key/8);
				$index_service_cat_list[$tmp_i][] = $value;
			}

			$this->assign('index_service_cat_list',$index_service_cat_list);
		//}


		$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);


		//if(in_array('3',$displayArr)){
			//推荐团购
			$group_list = D('House_village_group')->get_limit_list($now_village['village_id'],3,$user_long_lat);
			$this->assign('group_list',$group_list);
		//}

		//if(in_array('4',$displayArr)){
			//推荐快店
			$meal_list = D('House_village_meal')->get_limit_list($now_village['village_id'],3,$user_long_lat);
			$this->assign('meal_list',$meal_list);
		//}

		//if(in_array('5',$displayArr)){
			//推荐预约
			$appoint_list = D('House_village_appoint')->get_limit_list($now_village['village_id'],3,$user_long_lat);
			$this->assign('appoint_list',$appoint_list);
		//}

		//if(in_array('6',$displayArr)){
			//热门帖子
			if($this->config['house_bbsarticle_limit']){
				$bbs_article_list = D('Bbs')->bbsHotAricle('house',$now_village['village_id'],$this->config['house_bbsarticle_limit'],$this->config['house_bbsarticle_limit']);
			}else{
				$bbs_article_list = D('Bbs')->bbsHotAricle('house',$now_village['village_id'],$this->config['house_bbsarticle_limit']);
			}
			$this->assign('bbs_article_list',$bbs_article_list);
		//}

		//if(in_array('7',$displayArr) && $now_village['has_activity']){
			//社区活动
			$database_house_village_activity = D('House_village_activity');
			$activity_where['status'] = 1;
			$activity_where['village_id'] = $_GET['village_id'] + 0;
			$activity_where['pic'] = array('neq' , '');
			$activity_list = $database_house_village_activity->house_village_activity_page_list($activity_where,true,'sort desc',3);
			
			foreach ($activity_list['list']['list'] as $key => $value) {
				$tempImg = explode(';', $value['pic']);
				$activity_list['list']['list'][$key]['pic'] = $tempImg[0];
			}
			$this->assign('activity_list',$activity_list['list']);
		//}


		$merchant_store_shop_where['lat'] = $now_village['lat'];
		$merchant_store_shop_where['long'] = $now_village['long'];
		$merchant_store_shop_lists = $database_merchant_store_shop->get_list_by_option($merchant_store_shop_where);
		$merchant_store_shop_result = array();
		$deliver_type = 'all';
		$now_time = date('H:i:s');
		foreach ($merchant_store_shop_lists['shop_list'] as $row) {
			$temp = array();
			$temp['id'] = $row['store_id'];
			$temp['name'] = $row['name'];
			$temp['range'] = $row['range'];
			$temp['image'] = $row['image'];
			$temp['star'] = $row['score_mean'];
			$temp['month_sale_count'] = $row['sale_count'];
			$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
			$temp['delivery_time'] = $row['send_time'];//配送时长
			$temp['delivery_price'] = floatval($row['basic_price']);//起送价
			$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
			$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
			$temp['is_close'] = 1;
			$temp['txt_info'] = $row['txt_info'];

			if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
				$temp['time'] = '24小时营业';
				$temp['is_close'] = 0;
			} else {
				$temp['time'] = $row['open_1'] . '~' . $row['close_1'];
				if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
					$temp['is_close'] = 0;
				}
				if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
					$temp['time'] .= ',' . $row['open_2'] . '~' . $row['close_2'];
					if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
						$temp['is_close'] = 0;
					}
				}
				if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
					$temp['time'] .= ',' . $row['open_3'] . '~' . $row['close_3'];
					if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
						$temp['is_close'] = 0;
					}
				}
			}

			$temp['coupon_list'] = array();
			if ($row['is_invoice']) {
				$temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
			}
			if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
				$temp['coupon_list']['discount'] = $row['store_discount']/10;
			}
			$system_delivery = array();
			foreach ($row['system_discount'] as $row_d) {
				if ($row_d['type'] == 0) {//新单
					$temp['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 1) {//满减
					$temp['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 2) {//配送
					if ($row_d['full_money'] > 0 && $row_d['reduce_money'] > 0) {
						$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
					}
				}
			}
			foreach ($row['merchant_discount'] as $row_m) {
				if ($row_m['type'] == 0) {
					$temp['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				} elseif ($row_m['type'] == 1) {
					$temp['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				}
			}
			if ($row['deliver']) {
				if ($temp['delivery_system']) {
					$system_delivery && $temp['coupon_list']['delivery'] = $system_delivery;
				} else {
					if ($row['is_have_two_time']) {
						if ($row['reach_delivery_fee_type2'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
							}
						} elseif ($row['reach_delivery_fee_type2'] == 1) {
							//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
						} elseif ($row['reach_delivery_fee_type2'] == 2) {
							$row['delivery_fee2'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
						}
					} else {
						if ($row['reach_delivery_fee_type'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
							}
						} elseif ($row['reach_delivery_fee_type'] == 1) {
						} elseif ($row['reach_delivery_fee_type'] == 2) {
							$row['delivery_fee'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
						}
					}
				}
			}
			$temp['coupon_count'] = count($temp['coupon_list']);
			$merchant_store_shop_result[] = $temp;
		}

		$this->assign('merchant_store_shop_list' , $merchant_store_shop_result);
                
                /**
                 * 广告调用
                 */
                $wap_dis = D('Adver')->get_adver_by_key('wap_dis_'.$now_village['village_id'],5);
                if(!$wap_dis){
                    $wap_dis = D('Adver')->get_adver_by_key('wap_dis',5);
                }
		$this->assign('wap_dis',$wap_dis);
                
		$this->display();
	}


	public function store_ajax_list(){

		$lat = isset($_GET['user_lat']) ? htmlspecialchars($_GET['user_lat']) : 0;
		$long = isset($_GET['user_long']) ? htmlspecialchars($_GET['user_long']) : 0;
		$limit = isset($_GET['limit']) ? htmlspecialchars($_GET['limit']) : 0;
		$village_id = isset($_GET['village_id']) ? htmlspecialchars($_GET['village_id']) : 0;

		$where = array('lat' => $lat, 'long' => $long,'village_id'=>$village_id);
		$lists = D('House_village_store')->get_list_by_option($where,$limit);
		// dump($lists);
		$return = array();
		$now_time = date('H:i:s');

		foreach ($lists['shop_list'] as $row) {
			$temp = array();
			$temp['id'] = $row['store_id'];
			$temp['name'] = $row['name'];
			$temp['range'] = $row['range'];
			$temp['image'] = $row['image'];
			$temp['star'] = $row['score_mean'];
			$temp['month_sale_count'] = $row['sale_count'];
			$temp['is_new'] = ($row['create_time'] + 864000) < time() ? 0 : 1;
			$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
			$temp['delivery_time'] = $row['send_time'];//配送时长
			$temp['send_time_type'] = $row['send_time_type'];//配送时长
			$temp['delivery_time_type'] = $this->send_time_type[$row['send_time_type']];//配送时长
			$temp['delivery_price'] = floatval($row['basic_price']);//起送价
			$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
			$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
			$temp['is_close'] = $row['state'] ? intval($row['is_close']) : 1;
			$temp['isverify'] = $row['isverify'];
			$temp['is_mult_class'] = $row['is_mult_class'];
			$temp['merchant_coupon'] = $row['merchant_coupon'];

// 			if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
// 				$temp['time'] = '24小时营业';
// 				$temp['is_close'] = 0;
// 			} else {
// 				$temp['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
// 				if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
// 					$temp['is_close'] = 0;
// 				}
// 				if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
// 					$temp['time'] .= ',' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
// 					if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
// 						$temp['is_close'] = 0;
// 					}
// 				}
// 				if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
// 					$temp['time'] .= ',' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
// 					if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
// 						$temp['is_close'] = 0;
// 					}
// 				}
// 			}

			$temp['coupon_list'] = array();
			if ($row['is_invoice']) {
				$temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
			}
			if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
				$temp['coupon_list']['discount'] = $row['store_discount']/10;
			}
			$system_delivery = array();
			foreach ($row['system_discount'] as $row_d) {
				if ($row_d['type'] == 0) {//新单
					$temp['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 1) {//满减
					$temp['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 2) {//配送
					if ($row_d['full_money'] > 0 && $row_d['reduce_money'] > 0) {
						$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
					}
				}
			}
			foreach ($row['merchant_discount'] as $row_m) {
				if ($row_m['type'] == 0) {
					$temp['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				} elseif ($row_m['type'] == 1) {
					$temp['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				}
			}
			if ($row['deliver']) {
				if ($temp['delivery_system']) {
					$system_delivery && $temp['coupon_list']['delivery'] = $system_delivery;
				} else {
					if ($row['is_have_two_time']) {
						if ($row['reach_delivery_fee_type2'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
							}
						} elseif ($row['reach_delivery_fee_type2'] == 1) {
							//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
						} elseif ($row['reach_delivery_fee_type2'] == 2) {
							$row['delivery_fee2'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
						}
					} else {
						if ($row['reach_delivery_fee_type'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
							}
						} elseif ($row['reach_delivery_fee_type'] == 1) {
							//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
						} elseif ($row['reach_delivery_fee_type'] == 2) {
							$row['delivery_fee'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
						}
					}
				}
			}
			$temp['coupon_count'] = count($temp['coupon_list']);
			$return[] = $temp;
		}
		echo json_encode(array('store_list' => $return, 'has_more' => $lists['has_more'] ? true : false));
	}


	public function village_manager_list(){
		$now_village = $this->get_village($_GET['village_id']);
		$this->display();
	}

	public function village_more_list(){
		$now_village = $this->get_village($_GET['village_id']);
		
		//获取快店 团购 别名
		$database_config = D('Config');
		$houseConfig = $database_config->get_config();
		$this->assign('houseConfig' , $houseConfig);
		
		$this->display();
	}

	public function shop(){
		$now_village = $this->get_village($_GET['village_id']);
		$this->display();
	}

	public function ajax_category(){
		$return = array();
		$return['category_list'] = D('Shop_category')->lists(true);
		$return['sort_list'] = array(
				array(
						'name' => '智能排序',
						'sort_url' => 'juli'
				),
				array(
						'name' => '销售数量最高',
						'sort_url'=>'sale_count'
				),
				array(
						'name' => '配送时间最短',
						'sort_url'=>'send_time'
				),
				array(
						'name' => '起送价最低',
						'sort_url' => 'basic_price'
				),
// 				array(
// 						'name' => '配送费最低',
// 						'sort_url' => 'delivery_fee'
// 				),
				array(
						'name' => '评分最高',
						'sort_url' => 'score_mean'
				),
				array(
						'name' => '最新发布',
						'sort_url' => '	create_time'
				)
		);
		$return['type_list'] = array(
				array(
						'name' => '全部',
						'type_url' => 'all'
				),
				array(
						'name' => '配送',
						'type_url' => 'delivery'
				),
				array(
						'name' => '自提',
						'type_url' => 'pick'
				)
		);
		echo json_encode($return);
	}

	public function ajax_list()
	{
		$key = isset($_GET['key']) ? htmlspecialchars($_GET['key']) : '';
		$cat_url = isset($_GET['cat_url']) ? htmlspecialchars($_GET['cat_url']) : 'all';
		$order = isset($_GET['sort_url']) ? htmlspecialchars($_GET['sort_url']) : 'juli';
		$deliver_type = isset($_GET['type_url']) ? htmlspecialchars($_GET['type_url']) : 'all';
		$lat = isset($_GET['user_lat']) ? htmlspecialchars($_GET['user_lat']) : 0;
		$long = isset($_GET['user_long']) ? htmlspecialchars($_GET['user_long']) : 0;
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$is_wap = $_GET['is_wap'] + 0;
		//$page = max(1, $page);
		$page = 1;
		$cat_id = 0;
		$cat_fid = 0;
		if ($cat_url != 'all') {
			$now_category = D('Shop_category')->get_category_by_catUrl($cat_url);
			if ($now_category) {
				if ($now_category['cat_fid']) {
					$cat_id = $now_category['cat_id'];
					$cat_fid = $now_category['cat_fid'];
				} else {
					$cat_id = 0;
					$cat_fid = $now_category['cat_id'];
				}
			}
		}

		$where = array('deliver_type' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page);
		$key && $where['key'] = $key;

		if($is_wap > 0){
			$lists = D('Merchant_store_shop')->get_list_by_option($where,$is_wap);
		}else{
			$lists = D('Merchant_store_shop')->get_list_by_option($where);
		}
		$return = array();
		$now_time = date('H:i:s');

		foreach ($lists['shop_list'] as $Key=>$row) {
			if($Key >= 10){
				break;
			}

			$temp = array();
			$temp['id'] = $row['store_id'];
			$temp['name'] = $row['name'];
			$temp['range'] = $row['range'];
			$temp['image'] = $row['image'];
			$temp['star'] = $row['score_mean'];
			$temp['month_sale_count'] = $row['sale_count'];
			$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
			$temp['delivery_time'] = $row['send_time'];//配送时长
			$temp['delivery_price'] = floatval($row['basic_price']);//起送价
			$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
			$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
			$temp['is_close'] = 1;
			$temp['isverify'] = $row['isverify'];

			if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
				$temp['time'] = '24小时营业';
				$temp['is_close'] = 0;
			} else {
				$temp['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
				if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
					$temp['is_close'] = 0;
				}
				if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
					$temp['time'] .= ',' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
					if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
						$temp['is_close'] = 0;
					}
				}
				if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
					$temp['time'] .= ',' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
					if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
						$temp['is_close'] = 0;
					}
				}
			}

			$temp['coupon_list'] = array();
			if ($row['is_invoice']) {
				$temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
			}
			if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
				$temp['coupon_list']['discount'] = $row['store_discount']/10;
			}
			$system_delivery = array();
			foreach ($row['system_discount'] as $row_d) {
				if ($row_d['type'] == 0) {//新单
					$temp['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 1) {//满减
					$temp['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 2) {//配送
					if ($row_d['full_money'] > 0 && $row_d['reduce_money'] > 0) {
						$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
					}
				}
			}
			foreach ($row['merchant_discount'] as $row_m) {
				if ($row_m['type'] == 0) {
					$temp['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				} elseif ($row_m['type'] == 1) {
					$temp['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				}
			}
			if ($row['deliver']) {
				if ($temp['delivery_system']) {
					$system_delivery && $temp['coupon_list']['delivery'] = $system_delivery;
				} else {
					if ($row['is_have_two_time']) {
						if ($row['reach_delivery_fee_type2'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
							}
						} elseif ($row['reach_delivery_fee_type2'] == 1) {
							//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
						} elseif ($row['reach_delivery_fee_type2'] == 2) {
							$row['delivery_fee2'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
						}
					} else {
						if ($row['reach_delivery_fee_type'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
							}
						} elseif ($row['reach_delivery_fee_type'] == 1) {
							//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
						} elseif ($row['reach_delivery_fee_type'] == 2) {
							$row['delivery_fee'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
						}
					}
				}
			}
			$temp['coupon_count'] = count($temp['coupon_list']);
			$return[] = $temp;
		}

		echo json_encode(array('store_list' => $return, 'has_more' => $lists['has_more'] ? true : false));
	}

	public function village_newslist(){
		$now_village = $this->get_village($_GET['village_id']);
		$cat_id = $_GET['cat_id'];

		$category_list = D('House_village_news_category')->get_limit_list($now_village['village_id']);
		if($category_list){
			$this->assign('category_list',$category_list);

			// $news_list = D('House_village_news')->get_list_by_cid($category_list[0]['cat_id']);
			$cat_id = $cat_id ? $cat_id : $category_list[0]['cat_id'];
			$news_list = D('House_village_news')->get_list_by_cid($cat_id);
			$this->assign('news_list',$news_list);

			$this->display();
		}else{
			$this->error_tips('本社区没有发布过新闻',U('House/village',array('village_id'=>$now_village['village_id'])));
		}
	}
	public function village_ajax_news(){
		$this->header_json();
		$news_list = D('House_village_news')->get_list_by_cid($_GET['cat_id']);
		foreach($news_list as &$newsValue){
			$newsValue['add_time_txt'] = date('m-d H:i',$newsValue['add_time']);
		}
		echo json_encode($news_list);
	}
	public function village_ajax_activity(){
		if(IS_AJAX){
			$this->header_json();
			$database_house_village_activity = D('House_village_activity');
			$village_id = $_POST['village_id'] + 0;
			$where['village_id'] = $village_id;
			$where['status'] = 1;
			$activity_list = $database_house_village_activity->house_village_activity_page_list($where,true,'id desc',9999);
			$activity_list = $activity_list['list']['list'];

			foreach($activity_list as $k=>$v){
				$activity_list[$k]['add_time_txt'] = date('m-d H:i',$v['add_time']);
				$activity_list[$k]['apply_end_time'] = date('m-d',$v['apply_end_time']);
				if(($v['remain_num'] == 0) && (isset($v['remain_num']))){
					$activity_list[$k]['flag'] = 2;
				}else if(time() > $v['apply_end_time']){
					$activity_list[$k]['flag'] = 1;
				}
			}
			echo json_encode($activity_list);
		}else{
			echo json_encode('非法访问！');
		}
	}
	public function ajax_village_activity(){
		if(IS_POST){
			$this->header_json();
			$database_house_village_activity = D('House_village_activity');
			$village_id = $_POST['village_id'] + 0;
			$where['village_id'] = $village_id;
			$where['status'] = 1;
			$activity_list = $database_house_village_activity->where($where)->select();
			foreach($activity_list as &$activityValue){
				$activityValue['add_time_txt'] = date('m-d H:i',$activityValue['add_time']);
			}
			echo json_encode($activity_list);
		}else{
			$this->error_tips('访问页面有误！~');
		}
	}
	public function village_news(){
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);

		$now_news = D('House_village_news')->get_one($_GET['news_id']);
		if(empty($now_news)){
			$this->error_tips('当前文章不存在',U('House/village_newslist',array('village_id'=>$now_village['village_id'])));
		}
        $column['village_id'] = $_GET['village_id'];
        $column['news_id'] = $_GET['news_id'];

		if($_POST['list_num']){
            $listnum = $_POST['list_num'];
            $amount = $_POST['amount'];
            $column['status'] = 1;
            $reply = D('House_village_news_reply')->get_ajax_list($column,$listnum,$amount);
            exit(json_encode($reply));
        }
        $column['status'] = 1;
        $reply = D('House_village_news_reply')->get_ajax_list($column);

		$this->assign('now_news',$now_news);
        $this->assign('reply',$reply);
		$this->display();
	}
	public function village_activity(){
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if($now_village['has_activity']){
			$database_house_village_activity = D('House_village_activity');
			$where['id'] = $_GET['id'] + 0;
			$where['status'] = 1;
			$now_activity = $database_house_village_activity->house_village_activity_detail($where);
			if($now_activity['status'] == 0){
				$this->error_tips('当前活动不存在',U('House/village_activitylist',array('village_id'=>$now_village['village_id'])));
			}
			
			// 查询是否已报名
			$condition = array();
			$condition['user_bind_id'] = $this->user_session['uid'];
			$condition['activity_id'] = $_GET['id'] + 0;
			$exist_activity = D('House_village_activity_apply')->where($condition)->count();

			$this->assign('now_activity',$now_activity['detail']);
			$this->assign('exist_activity',$exist_activity);
			$this->display();
		}else{
			$this->error_tips('该社区没有开启活动');
		}
	}
	public function village_activitylist(){
		$village_id = $_GET['village_id'] + 0;
		$now_village = $this->get_village($village_id);
		if($now_village['has_activity']){
			$database_house_village_activity = D('House_village_activity');
			$where['village_id'] = $village_id;
			$where['status'] = 1;
			$activity_list = $database_house_village_activity->house_village_activity_page_list($where,true,'id desc',9999);
			$activity_list = $activity_list['list']['list'];

			foreach($activity_list as $Key=>$row){
				$row['pic'] = explode(';',$row['pic']);
				if($row['activity_end_time'] > time()){
					$activity_list['running_activity'][] = $row;
				}else{
					$activity_list['stop_activity'][] = $row;
				}
				unset($activity_list[$Key]);
			}

			$this->assign('activity_list',$activity_list);
			$this->display();
		}else{
			$this->error_tips('该社区没有开启活动');
		}
	}
	public function village_news_reply(){
		$this->header_json();
		$now_news = D('House_village_news')->get_one($_GET['news_id']);
		if(empty($now_news)){
			echo json_encode(array('errcode'=>2,'errmsg'=>'当前文章不存在'));
		}else if(empty($_POST['content'])){
			echo json_encode(array('errcode'=>3,'errmsg'=>'请填写评论的内容'));
		}else if(empty($this->user_session)){
			echo json_encode(array('errcode'=>4,'errmsg'=>'请先进行登录'));
		}else{
            $data_reply = array(
                'uid'=>$this->user_session['uid'],
                'village_id'=>$now_news['village_id'],
                'news_id'=>$now_news['news_id'],
                'content'=>$_POST['content'],
                'add_time'=>$_SERVER['REQUEST_TIME'],
                'add_ip'=>get_client_ip(1),
            );
            $is_check = D('House_village_config')->where(array('village_id' => $now_news['village_id']))->getField('village_news_is_need_check' );
            $msg = '发布成功';
            if($is_check==1){
            	$msg = '发布成功，已经提交给小区管理员';
                $data_reply['status']=2;
            }
			if(D('House_village_news_reply')->data($data_reply)->add()){
				echo json_encode(array('errcode'=>1,'errmsg'=>$msg));
			}else{
				echo json_encode(array('errcode'=>5,'errmsg'=>'发布失败'));
			}
		}
	}
	public function village_grouplist(){
		$now_village = $this->get_village($_GET['village_id']);

		$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);

		//推荐团购
		$group_list = D('House_village_group')->get_limit_list_page($now_village['village_id'],10,$user_long_lat,true);
		if(I('app_version') && IS_POST){
			$this->assign($group_list);
			$this->display();
		}else{
			if(IS_POST){
				$this->header_json();
				echo json_encode($group_list['group_list']);
			}else{
				$this->assign($group_list);
				$this->display();
			}
		}
	}
	public function village_meallist(){
		$now_village = $this->get_village($_GET['village_id']);

		$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);

		//推荐快店
		$store_list = D('House_village_meal')->get_limit_list_page($now_village['village_id'],10,$user_long_lat);

		if(I('app_version') && IS_POST){
			$this->assign($store_list);
			$this->display();
		}else{
			if(IS_POST){
				$this->header_json();
				echo json_encode($store_list['store_list']);
			}else{
				$this->assign($store_list);
				$this->display();
			}
		}
	}
	public function village_appointlist(){
		$now_village = $this->get_village($_GET['village_id']);

		$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);

		//推荐预约
		$appoint_list = D('House_village_appoint')->get_limit_list_page($now_village['village_id'],10,$user_long_lat);

		if(I('app_version') && IS_POST){
			$this->assign($appoint_list);
			$this->display();
		}else{
			if(IS_POST){
				$this->header_json();
				echo json_encode($appoint_list['appoint_list']);
			}else{
				$this->assign($appoint_list);
				$this->display();
			}
		}

	}

	public function village_payment(){

		if(empty($this->user_session)){
			$this->error_tips('请先进行登录',U('Login/index'));
		}

		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);

		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$pay_type = $_GET['type'];
		$pay_name = $this->pay_list_type[$pay_type];
		if(empty($pay_name)){
			$this->check_ajax_error_tips('当前访问的缴费类型不存在');
		}


		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		$payment_bind_info = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb', C('DB_PREFIX').'house_village_payment_standard'=>'ps', C('DB_PREFIX').'house_village_payment'=>'p'))->where("psb.bind_id = '".$_GET['bind_id']."' AND p.payment_id = psb.payment_id AND ps.standard_id = psb.standard_id")->find();
		// dump($payment_bind_info);

		$this->display();
	}

	public function village_pay(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录',U('Login/index'));
		}

		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);

		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
			
		}
		$pay_type = $_GET['type'];
		$pay_name = $this->pay_list_type[$pay_type];
		if(empty($pay_name)){
			$this->check_ajax_error_tips('当前访问的缴费类型不存在');
		}

		//判断用户是否属于本小区
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}

		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		//家属 获取业主的欠费信息
		if ($now_user_info['parent_id']) {
			$now_user_info = $this->get_user_village_info($now_user_info['parent_id']);
		}

		$pay_money = 0;
		switch($pay_type){
			case 'property':

				if(empty($now_village['property_price'])) $this->check_ajax_error_tips('当前小区不支持缴纳'.$pay_name);
				$pay_money = $now_user_info['property_price'];
				$order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`property_price` AS `price`')->where(array('usernum'=>$now_user_info['usernum']))->order('`pigcms_id` DESC')->select();
				foreach($order_list as $key=>$value){
					$order_list[$key]['desc'] = '物业费 '.floatval($value['price']).' 元';
				}
				if($now_user_info['floor_id']){
					$database_house_village_floor = D('House_village_floor');

					$floor_where['status'] =  1;
					$floor_where['floor_id'] = $now_user_info['floor_id'];
					$floor_info = $database_house_village_floor->where($floor_where)->find();
					if($floor_info['property_fee'] != '0.00'){
						$now_user_info['property_fee'] = $floor_info['property_fee'];
					}else{
						$village_info = D('House_village')->where(array('village_id'=>$now_village['village_id']))->find();
						$now_user_info['property_fee'] = $village_info['property_price'];
					}
					$database_house_village_floor_type = D('House_village_floor_type');

					$type_where['status'] = 1;
					$type_where['id'] = $floor_info['floor_type'];
					if($floor_type_name = $database_house_village_floor_type->where($type_where)->getField('name')){
						$now_user_info['floor_type_name'] = $floor_type_name;
					}else{
						$now_user_info['floor_type_name'] = '暂无';
					}
				}else{
					$village_info = D('House_village')->where(array('village_id'=>$now_village['village_id']))->find();
					$now_user_info['property_fee'] = $village_info['property_price'];
				}

				$database_house_village_property_paylist = D('House_village_property_paylist');
				$pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$now_user_info['pigcms_id']))->order('add_time asc')->select();

				if(!empty($pay_list)){
					$start_pay_info = reset($pay_list);
					$end_pay_info = end($pay_list);
					if($start_pay_info && $end_pay_info){
						$now_user_info['property_time_str'] = date('Y-m-d',$start_pay_info['start_time']).'&nbsp;至&nbsp;'.date('Y-m-d',$end_pay_info['end_time']);
					}else{
						$now_user_info['property_time_str'] = date('Y-m-d',$pay_list['start_time']).'&nbsp;至&nbsp;'.date('Y-m-d',$pay_list['end_time']);
					}
				}
				break;
			case 'water':
				if(empty($now_village['water_price'])) $this->check_ajax_error_tips('当前小区不支持缴纳'.$pay_name);
				$pay_money = $now_user_info['water_price'];
				$order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_water` AS `use`,`water_price` AS `price`')->where(array('usernum'=>$now_user_info['usernum']))->order('`pigcms_id` DESC')->select();
				foreach($order_list as $key=>$value){
					$order_list[$key]['desc'] = '用水 '.floatval($value['use']).' 立方米，总费用 '.floatval($value['price']).' 元';
				}
				break;
			case 'electric':
				if(empty($now_village['electric_price'])) $this->check_ajax_error_tips('当前小区不支持缴纳'.$pay_name);
				$pay_money = $now_user_info['electric_price'];
				$order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_electric` AS `use`,`electric_price` AS `price`')->where(array('usernum'=>$now_user_info['usernum']))->order('`pigcms_id` DESC')->select();
				foreach($order_list as $key=>$value){
					$order_list[$key]['desc'] = '用电 '.floatval($value['use']).' 千瓦时(度)，总费用 '.floatval($value['price']).' 元';
				}
				break;
			case 'gas':
				if(empty($now_village['gas_price'])) $this->check_ajax_error_tips('当前小区不支持缴纳'.$pay_name);
				$pay_money = $now_user_info['gas_price'];
				$order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`use_gas` AS `use`,`gas_price` AS `price`')->where(array('usernum'=>$now_user_info['usernum']))->order('`pigcms_id` DESC')->select();
				foreach($order_list as $key=>$value){
					$order_list[$key]['desc'] = '使用燃气 '.floatval($value['use']).' 立方米，总费用 '.floatval($value['price']).' 元';
				}
				break;
			case 'park':
				if(empty($now_village['park_price'])) $this->check_ajax_error_tips('当前小区不支持缴纳'.$pay_name);
				$pay_money = $now_user_info['park_price'];
				$order_list = D('House_village_user_paylist')->field('`ydate`,`mdate`,`park_price` AS `price`')->where(array('usernum'=>$now_user_info['usernum']))->order('`pigcms_id` DESC')->select();
				foreach($order_list as $key=>$value){
					$order_list[$key]['desc'] = '停车费 '.floatval($value['price']).' 元';
				}
				break;
			case 'custom':
				if(empty($now_village['has_custom_pay'])) $this->check_ajax_error_tips('当前小区不支持缴纳'.$pay_name);
				break;
		}
		if(IS_POST){
			$app_version	=	I('app_version');
			

			if(!empty($app_version)){

			}else{
				if($pay_type == 'custom'){
					if(empty($_POST['txt'])){
						$this->check_ajax_error_tips('请填写缴费事项');
					}else{
						$data_order['order_name'] = '社区缴费：'.$_POST['txt'];
					}
					$_POST['money'] = floatval($_POST['money']);
					if(empty($_POST['money'])){
						$this->check_ajax_error_tips('请填写缴费金额');
					}else{
						$data_order['money'] = $_POST['money'] > 10000 ? 10000 : $_POST['money'];
					}
				}elseif ($pay_type == 'custom_payment') {
			
					$data_order['payment_bind_id'] = $_POST['bind_id'];
					$data_order['payment_paid_cycle'] = $_POST['diy_cycle'];
					if(empty($_POST['txt'])){
						$this->check_ajax_error_tips('请填写缴费事项');
					}else{
						$data_order['order_name'] = $_POST['txt'];
					}


					$data_order['money'] = $_POST['money'];
				}else{
					$data_order['order_name'] = $pay_name;
					if($pay_type == 'property'){
						$property_month_num = $_POST['property_month_num'] + 0;
						if($property_month_num <= 0 || $property_month_num > 36){
							exit(json_encode(array('error_code'=>1,'err_msg'=>'月份请填写1-36之间')));
						}

						$data_order['property_month_num'] = intval($_POST['property_month_num'] + 0);
						$data_order['money'] = $_POST['money'] + 0;
						$data_order['house_size'] = $now_user_info['housesize'] ? $now_user_info['housesize'] : '0.00';
						$data_order['property_fee'] = $now_user_info['property_fee'] ? $now_user_info['property_fee'] : '0.00';
						$data_order['floor_type_name'] = $now_user_info['floor_type_name'] ? $now_user_info['floor_type_name'] : '';
					}else{
						$data_order['money'] = $pay_money > 10000 ? 10000 : $pay_money;
					}
				}
				$data_order['uid'] = $this->user_session['uid'];
				$data_order['bind_id'] = $now_user_info['pigcms_id'];
				$data_order['village_id'] = $now_village['village_id'];
				$data_order['time'] = $_SERVER['REQUEST_TIME'];
				$data_order['paid'] = 0;
				$data_order['order_type'] = $pay_type;

				// dump($data_order);die;
				if($_POST['house_village_property_id']){
					$database_house_village_property = D('House_village_property');

					$property_where['status'] = 1;
					$property_where['id'] = $_POST['house_village_property_id'] + 0;
					$house_village_property_info = $database_house_village_property->house_village_property_detail($property_where);
					if(!$house_village_property_info['status']){
						exit(json_encode(array('error_code'=>1,'err_msg'=>'内部错误，请联系管理员！')));
					}else{
						$house_village_property_info = $house_village_property_info['detail'];
						$data_order['diy_type'] = $house_village_property_info['diy_type'];
						if($house_village_property_info['diy_type'] == 1){
							$data_order['diy_content'] = $house_village_property_info['diy_content'];
						}else{
							$data_order['presented_property_month_num'] = intval($_POST['presented_property_month_num'] + 0);
						}
					}
				}else{
					$data_order['presented_property_month_num'] = intval($_POST['presented_property_month_num'] + 0);
				}
				
				
				if($pay_type == 'property'){
					$percent = 0;
					$is_use_integral = $_POST['is_use_integral'] + 1;
					//计算物业费和抵扣金额
					//判断是否允许使用积分
					$village_config = D('House_village_config')->where(array('village_id'=>$now_village['village_id']))->find();
					//首先得到 积分比例 和 用户总积分
					$system_config = D('Config')->get_config();
					//得到多少积分抵扣一元
					$moneytointegral = $system_config['user_score_use_percent'];
					//得到用户总积分
					$userinfo =  D('User')->get_user($this->user_session['uid']);
					$user_score_count = $userinfo['score_count'];
					//得到总金额
					$total_price = $_POST['money'] + 0;
					//第一步 判断是否允许使用积分 (注意 这里是针对设置了小区本身的缴费配置)
					if($is_use_integral==1){ //用户使用了积分
						if($village_config['village_pay_use_integral']==1){ //允许使用积分 针对小区开启
							$percent = $village_config['use_max_integral_percentage'];
							if($village_config['village_pay_owe_use_integral']==1){ //欠缴物业费允许使用积分
								$property_info = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
							}else{ // 欠缴物业费不允许使用积分情况
								//首先计算缴费月份 并且得到是否欠缴物业费
								$database_house_village_property_paylist = D('House_village_property_paylist');
								$pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$this->village_bind['pigcms_id'],'uid'=>$this->user_session['uid']))->order('add_time asc')->select();
								if(!empty($pay_list)){
									$start_pay_info = reset($pay_list);
									$end_pay_info = end($pay_list);
									if($start_pay_info && $end_pay_info){
										//这里判断是否欠缴物业费
										if($end_pay_info['end_time'] > time()){
											$property_info = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
										}else{
											$date1 = explode("-",date("Y-m-d"));
											$date2 = explode("-",date("Y-m-d" , $end_pay_info['end_time']));
											$time1 = strtotime(date("Y-m-d"));
											$time2 = strtotime(date("Y-m-d" , $end_pay_info['end_time']));
											$months =abs($date1[0]-$date2[0])*12;
											if($time1 > $time2){
												$owe_mouth = $months+$date1[1]-$date2[1];
											}else{
												$owe_mouth =  $months+$date2[1]-$date1[1];
											}
											if($owe_mouth >= $property_month_num){
												$property_info['use_total_score_count'] = 0;
												$property_info['use_total_money'] = 0;		
											}else{
												$now_total_integral_money = $total_price - $data_order['property_fee'] * $data_order['house_size'] * $owe_mouth;
												$property_info = $this->ajax_integral_info($now_total_integral_money ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
											}
										}
									}else{
										$property_info = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
									}
								}else{ // 第一次缴物业费 就不存在欠费
									$property_info = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
								}
							}
						}elseif($village_config['village_pay_use_integral']==0){ // 继承系统设置
							if($system_config['village_pay_use_integral']==1){
								$percent = $system_config['use_max_integral_percentage'];
								if($system_config['village_owe_pay_use_integral']==1){ //欠缴物业费允许使用积分
									$property_info = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
								}else{ // 欠缴物业费不允许使用积分情况
									//首先计算缴费月份 并且得到是否欠缴物业费
									$database_house_village_property_paylist = D('House_village_property_paylist');
									$pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$this->village_bind['pigcms_id'],'uid'=>$this->user_session['uid']))->order('add_time asc')->select();
									if(!empty($pay_list)){
										$start_pay_info = reset($pay_list);
										$end_pay_info = end($pay_list);
										if($start_pay_info && $end_pay_info){
											//这里判断是否欠缴物业费
											if($end_pay_info['end_time'] > time()){
												$property_info = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
											}else{
												$date1 = explode("-",date("Y-m-d"));
												$date2 = explode("-",date("Y-m-d" , $end_pay_info['end_time']));
												$time1 = strtotime(date("Y-m-d"));
												$time2 = strtotime(date("Y-m-d" , $end_pay_info['end_time']));
												$months =abs($date1[0]-$date2[0])*12;
												if($time1 > $time2){
													$owe_mouth = $months+$date1[1]-$date2[1];
												}else{
													$owe_mouth =  $months+$date2[1]-$date1[1];
												}
												if($owe_mouth >= floatval($property_info['property_month_num'])){
													$property_info['use_total_score_count'] = 0;
													$property_info['use_total_money'] = 0;		
												}else{
													$now_total_integral_money = $total_price - $data_order['property_fee'] * $data_order['house_size'] * $owe_mouth;
													$property_info = $this->ajax_integral_info($now_total_integral_money ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
												}
											}
										}else{
											$property_info = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
										}
									}else{ // 第一次缴物业费 就不存在欠费
										$property_info = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
									}
								}
							}else{
								$property_info['use_total_score_count'] = 0;
								$property_info['use_total_money'] = 0;	
							}
						}else{
							$property_info['use_total_score_count'] = 0;
							$property_info['use_total_money'] = 0;
						}
					}else{ //用户没有使用积分
						
						$property_info['use_total_score_count'] = 0;
						$property_info['use_total_money'] = 0;
					}
					
					
					//计算可生成积分多少
					if($village_config['village_pay_integral']==1){ //允许生成积分
						if($village_config['village_owe_pay_integral']==1){ //欠缴物业费 允许生成积分

                            if($village_config['open_score_get_percent']==1){
                                // 开启了积分百分比， 取当前小区的积分百分比计算
                                $score_get = $village_config['score_get_percent']/100;
                            }else{
                                // 关闭了积分百分比， 取当前小区的积分计算
                                $score_get = $village_config['user_score_get'];
                            }

							$property_info['generate_integral'] = ($total_price - $property_info['use_total_money']) * $score_get; // 得到生成积分
						}else{ //欠缴物业费不允许生成积分
							//首先计算缴费月份 并且得到是否欠缴物业费
							$database_house_village_property_paylist = D('House_village_property_paylist');
							$pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$this->village_bind['pigcms_id'],'uid'=>$this->user_session['uid']))->order('add_time asc')->select();
							if(!empty($pay_list)){
								$start_pay_info = reset($pay_list);
								$end_pay_info = end($pay_list);
								if($start_pay_info && $end_pay_info){
									//这里判断是否欠缴物业费
									if($end_pay_info['end_time'] > time()){
										$property_info['generate_integral'] = ($total_price - $property_info['use_total_money']) * $system_config['user_score_get']; // 得到生成积分
									}else{
										$date1 = explode("-",date("Y-m-d"));
										$date2 = explode("-",date("Y-m-d" , $end_pay_info['end_time']));
										$time1 = strtotime(date("Y-m-d"));
										$time2 = strtotime(date("Y-m-d" , $end_pay_info['end_time']));
										$months =abs($date1[0]-$date2[0])*12;
										if($time1 > $time2){
											$owe_mouth = $months+$date1[1]-$date2[1];
										}else{
											$owe_mouth =  $months+$date2[1]-$date1[1];
										}
										if($owe_mouth >= $property_month_num){
											$property_info['use_total_score_count'] = 0;
											$property_info['use_total_money'] = 0;		
										}else{
											$now_total_integral_money = $total_price - $data_order['property_fee'] * $data_order['house_size'] * $owe_mouth;
											$property_info['generate_integral'] = ($now_total_integral_money - $property_info['use_total_money'] - $property_info['use_total_money']) * $system_config['user_score_get']; // 得到生成积分
										}
									}
								}else{
									$property_info['generate_integral'] = ($total_price - $property_info['use_total_money']) * $system_config['user_score_get']; // 得到生成积分
								}
							}else{ // 第一次缴物业费 就不存在欠费
								$property_info['generate_integral'] = ($total_price - $property_info['use_total_money']) * $system_config['user_score_get']; // 得到生成积分
							}	
						}
						
					}elseif($village_config['village_pay_integral']==0){ //继承平台设置 是否允许生成积分
						if($system_config['village_pay_integral']==1){ // 平台允许生成积分
							if($system_config['village_owe_pay_integral']==1){ //欠缴物业费允许使用积分
								$property_info['generate_integral'] = ($total_price - $property_info['use_total_money']) * $system_config['user_score_get']; // 得到生成积分
							}else{ // 欠缴物业费不允许使用积分情况
								//首先计算缴费月份 并且得到是否欠缴物业费
								$database_house_village_property_paylist = D('House_village_property_paylist');
								$pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$this->village_bind['pigcms_id'],'uid'=>$this->user_session['uid']))->order('add_time asc')->select();
								if(!empty($pay_list)){
									$start_pay_info = reset($pay_list);
									$end_pay_info = end($pay_list);
									if($start_pay_info && $end_pay_info){
										//这里判断是否欠缴物业费
										if($end_pay_info['end_time'] > time()){
											$property_info['generate_integral'] = ($total_price - $property_info['use_total_money']) * $system_config['user_score_get']; // 得到生成积分
										}else{
											$date1 = explode("-",date("Y-m-d"));
											$date2 = explode("-",date("Y-m-d" , $end_pay_info['end_time']));
											$time1 = strtotime(date("Y-m-d"));
											$time2 = strtotime(date("Y-m-d" , $end_pay_info['end_time']));
											$months =abs($date1[0]-$date2[0])*12;
											if($time1 > $time2){
												$owe_mouth = $months+$date1[1]-$date2[1];
											}else{
												$owe_mouth =  $months+$date2[1]-$date1[1];
											}
											if($owe_mouth >= floatval($property_info['property_month_num'])){
												$property_info['use_total_score_count'] = 0;
												$property_info['use_total_money'] = 0;		
											}else{
												$now_total_integral_money = $total_price - $data_order['property_fee'] * $data_order['house_size'] * $owe_mouth;
												$property_info['generate_integral'] = ($total_price - $now_total_integral_money - $property_info['use_total_money']) * $system_config['user_score_get']; // 得到生成积分
											}
										}
									}else{
										$property_info['generate_integral'] = ($total_price - $property_info['use_total_money']) * $system_config['user_score_get']; // 得到生成积分
									}
								}else{ // 第一次缴物业费 就不存在欠费
									$property_info['generate_integral'] = ($total_price - $property_info['use_total_money']) * $system_config['user_score_get']; // 得到生成积分
								}
							}
						}else{
							$property_info['generate_integral'] = 0; // 得到生成积分
						}
					}elseif($village_config['village_pay_integral']==-1){ //不允许生成积分 
						$property_info['generate_integral'] = 0;	
					}
					
					//if($property_info['use_total_score_count']>0 && $property_info['use_total_money']>0){ // 这表示需要扣除积分 和 减少钱
						$data_order['money'] = $data_order['money'];
						$data_order['score_can_use'] = $property_info['use_total_score_count'];
						$data_order['score_can_pay'] = $property_info['use_total_money'];
						$data_order['score_percent'] = $percent;
						$data_order['score_can_get'] = $property_info['generate_integral'];
						$data_order['money'] = $data_order['money'];
						$score_data['score'] = $property_info['use_total_score_count'];
						$score_data['desc'] = "缴纳物业费 ".$_POST['money']." 元 支出积分";
						$score_data['generate_integral'] = $property_info['generate_integral'];
						$score_data['generate_integral_desc'] = "缴纳物业费 ".$_POST['money']." 元 增加积分";
					//}

				}else{
					// 其他缴费也可获得积分
					$percent = 0;
					$is_use_integral = $_POST['is_use_integral'] + 1;
					//计算物业费和抵扣金额
					//判断是否允许使用积分
					$village_config = D('House_village_config')->where(array('village_id'=>$now_village['village_id']))->find();
					//首先得到 积分比例 和 用户总积分
					$system_config = D('Config')->get_config();
					//得到多少积分抵扣一元
					$moneytointegral = $system_config['user_score_use_percent'];
					//得到用户总积分
					$userinfo =  D('User')->get_user($this->user_session['uid']);
					$user_score_count = $userinfo['score_count'];
					//得到总金额
					$total_price = $data_order['money'];
					//第一步 判断是否允许使用积分 (注意 这里是针对设置了小区本身的缴费配置)
					if($is_use_integral==1){ //用户使用了积分
						if($village_config['village_pay_use_integral']==1){ //允许使用积分 针对小区开启
							$percent = $village_config['use_max_integral_percentage'];
							$property_info = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
						
						}elseif($village_config['village_pay_use_integral']==0){ // 继承系统设置
							if($system_config['village_pay_use_integral']==1){
								$percent = $system_config['use_max_integral_percentage'];
								$property_info = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
							}else{
								$property_info['use_total_score_count'] = 0;
								$property_info['use_total_money'] = 0;	
							}
						}else{
							$property_info['use_total_score_count'] = 0;
							$property_info['use_total_money'] = 0;
						}
					}else{ //用户没有使用积分
						
						$property_info['use_total_score_count'] = 0;
						$property_info['use_total_money'] = 0;
					}
					
					
					//计算可生成积分多少
					if($village_config['village_pay_integral']==1){ //允许生成积分

                        if($village_config['open_score_get_percent']==1){
                            // 开启了积分百分比， 取当前小区的积分百分比计算
                            $score_get = $village_config['score_get_percent']/100;
                        }else{
                            // 关闭了积分百分比， 取当前小区的积分计算
                            $score_get = $village_config['user_score_get'];
                        }

                        $property_info['generate_integral'] = ($total_price - $property_info['use_total_money']) * $score_get; // 得到生成积分
					}elseif($village_config['village_pay_integral']==0){ //继承平台设置 是否允许生成积分
						if($system_config['village_pay_integral']==1){ // 平台允许生成积分
							$property_info['generate_integral'] = ($total_price - $property_info['use_total_money']) * $system_config['user_score_get']; // 得到生成积分
						}else{
							$property_info['generate_integral'] = 0; // 得到生成积分
						}
					}elseif($village_config['village_pay_integral']==-1){ //不允许生成积分 
						$property_info['generate_integral'] = 0;	
					}
					
					$data_order['money'] = $data_order['money'];
					$data_order['score_can_use'] = $property_info['use_total_score_count'];
					$data_order['score_can_pay'] = $property_info['use_total_money'];
					$data_order['score_percent'] = $percent;
					$data_order['score_can_get'] = $property_info['generate_integral'];
					$data_order['money'] = $data_order['money'];
					$score_data['score'] = $property_info['use_total_score_count'];
					$score_data['desc'] = "缴纳".$data_order['order_name']." ".$_POST['money']." 元 支出积分";
					$score_data['generate_integral'] = $property_info['generate_integral'];
					$score_data['generate_integral_desc'] = "缴纳".$data_order['order_name']." ".$_POST['money']." 元 增加积分";
				}
				
				
				
				if($order_id = D('House_village_pay_order')->data($data_order)->add()){
					$this->header_json();
					// if($pay_type == 'property'){
						echo json_encode(array('err_code'=>1,'order_url'=>U('House/pay_order',array('order_id'=>$order_id,'village_id'=>$_GET['village_id'],'score'=>$score_data['score'],'desc'=>$score_data['desc'],'generate_integral'=>$score_data['generate_integral'],'generate_integral_desc'=>$score_data['generate_integral_desc']))));
					// }else{
					// 	echo json_encode(array('err_code'=>1,'order_url'=>U('House/pay_order',array('order_id'=>$order_id,'village_id'=>$_GET['village_id']))));
					// }
					exit();
				}else{
					$this->check_ajax_error_tips('下单失败，请重试');
				}
				exit();
			}
		}
		$this->assign('pay_type',$pay_type);
		$this->assign('pay_name',$pay_name);
		$this->assign('pay_money',$pay_money);
		$this->assign('order_list',$order_list);
		$this->assign('now_user_info',$now_user_info);
		$this->assign('now_village',$now_village);


		if($pay_type == 'property'){
			$database_house_village_property = D('House_village_property');
			$database_house_village_config = D('House_village_config');
			
			$property_condition['village_id'] = $now_village['village_id'];
			$property_condition['status'] = 1;
			$property_list = $database_house_village_property->house_village_proerty_page_list($property_condition,true,'property_month_num desc',99999);

			$property_list = $property_list['list'];
			if(!$property_list['list']){
				$this->error_tips('社区管理员暂未添加！');
			}
			$this->assign('property_list' , $property_list);
			$this->display($pay_type . '_village_pay');
		}elseif($pay_type == 'custom_payment'){

			
			$payment_info = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb', C('DB_PREFIX').'house_village_payment_standard'=>'ps', C('DB_PREFIX').'house_village_payment'=>'p'))->where("psb.bind_id= '".$_GET['bind_id']."' AND p.payment_id = psb.payment_id AND ps.standard_id = psb.standard_id")->find();

			if($payment_info['pay_type'] == 2){
				$payment_info['price'] = $payment_info['metering_mode_val']*$payment_info['pay_money'];
			}else{
				$payment_info['price'] = $payment_info['pay_money'];
			}

        	if ($payment_info['position_id']) { //车位缴费
        		$position_info = D('')->table(array(C('DB_PREFIX').'house_village_parking_position'=>'p', C('DB_PREFIX').'house_village_parking_garage'=>'g'))->where("p.position_id= '".$payment_info['position_id']."' AND p.garage_id = g.garage_id")->find();
        		if ($position_info['garage_num']) {
            		$payment_info['payment_name'] = $payment_info['payment_name'].'('.$position_info['garage_num'].'-'.$position_info['position_num'].')';
        		}
        	}
	        
		// dump($payment_info);

		$cycle_type = array(
                        'Y'=>'年',
                        'M'=>'月',
                        'D'=>'日',
                    );
        $this->assign('cycle_type',$cycle_type);
            
		$this->assign('payment_info',$payment_info);


			$this->display($pay_type . '_village_pay');
		}else{
			$this->display();
		}
	}


	public function pay_order(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_user = D('User')->get_user($this->user_session['uid']);
		$order_id = $_GET['order_id'];
		$now_order = D('House_village_pay_order')->field(true)->where(array('order_id'=>$order_id,'uid'=>$this->user_session['uid']))->find();
		$now_village = D('House_village')->get_one($now_order['village_id']);
		if(empty($now_order)){
			$this->check_ajax_error_tips('当前订单不存在');
		}
		if($now_order['paid']){
			redirect(U('House/village_my_paylists',array('village_id'=>$now_order['village_id'])));
		}

		//这里直接跳转支付，不走充值

		$pay_order_param = array(
				'business_type' => 'house_village_pay',
				'business_id' => $now_order['order_id'],
				'order_name' => $now_order['order_name'],
				'uid' => $now_order['uid'],
				'total_money' => $now_order['money'],
				'wx_cheap' => 0,
		);
		if($this->config['open_village_sub_mchid'] && $now_village['sub_mch_id']){
			$pay_order_param['is_own'] = 5;
		}else{
			$pay_order_param['is_own'] = 4;

		}

		$plat_order_result = D('Plat_order')->add_order($pay_order_param);
		if ($plat_order_result['error_code']) {
			$this->error_tips($plat_order_result['error_msg']);
		} else {
			redirect(U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')));
		}
		exit;
		//redirect(U('Pay/check',array('type'=>'plat')));


		if($now_user['now_money'] >= $now_order['money']){
			$use_result = D('User')->user_money($now_order['uid'],$now_order['money'],$now_order['order_name'].' 扣除余额');
			if($use_result['error_code']){
				redirect(U('House/village_my_paylists',array('village_id'=>$now_order['village_id'])));
			}
			$data_order['order_id'] = $order_id;
			$data_order['pay_time'] = $_SERVER['REQUEST_TIME'];
			$data_order['paid'] = 1;
			D('House_village_pay_order')->data($data_order)->save();

			if($now_order['order_type'] != 'custom'){
				switch($now_order['order_type']){
					case 'property':
						
						//扣除积分 做好记录
						$scoreData['score'] = $_GET['score'];
						$scoreData['desc'] = $_GET['desc'];
						if($scoreData['score'] > 0){
							D('User')->use_score($this->user_session['uid'] , $scoreData['score'] , $scoreData['desc']);
						}
						//扣除积分结束
						
						//生成积分 并且添加
						
						$scoreAddData['score'] = $_GET['generate_integral'];
						$scoreAddData['desc'] = $_GET['generate_integral_desc'];
						if($scoreAddData['score'] > 0){
							D('User')->add_score($this->user_session['uid'] , $scoreAddData['score'] , $scoreAddData['desc']);
						}
						//生成积分结束
						
						$bind_field = 'property_price';
						$now_user_info = $this->get_user_village_info($now_order['bind_id']);
						$database_house_village_property_paylist = D('House_village_property_paylist');
						$paylist_data['bind_id'] = $now_order['bind_id'];
						$paylist_data['uid'] = $now_order['uid'];
						$paylist_data['village_id'] = $now_order['village_id'];
						$paylist_data['property_month_num'] = $now_order['property_month_num'];
						$paylist_data['presented_property_month_num'] = $now_order['presented_property_month_num'];
						$paylist_data['house_size'] = $now_order['house_size'];
						$paylist_data['property_fee'] = $now_order['property_fee'];
						$paylist_data['floor_type_name'] = $now_order['floor_type_name'];

						$now_pay_info = $database_house_village_property_paylist->where(array('bind_id'=>$now_order['bind_id']))->order('add_time desc')->find();
						if(!empty($now_pay_info)){
							$paylist_data['start_time'] = $now_pay_info['end_time'] ;
							$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_pay_info['end_time']);
						}else{
							if($now_user_info['add_time'] > 0){
								$paylist_data['start_time'] = $now_user_info['add_time'] ;
								$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['add_time']);
							}else{
								$paylist_data['start_time'] = time();
								$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", time());
							}

						}
						$paylist_data['add_time'] = time();
						$paylist_data['order_id'] = $order_id;

						$database_house_village_property_paylist->data($paylist_data)->add();
						break;
					case 'water':
						$bind_field = 'water_price';
						break;
					case 'electric':
						$bind_field = 'electric_price';
						break;
					case 'gas':
						$bind_field = 'gas_price';
						break;
					case 'park':
						$bind_field = 'park_price';
						break;
					default:
						$bind_field = '';
				}
				if(!empty($bind_field)){
					$now_user_info = D('House_village_user_bind')->get_one($now_order['village_id'],$now_order['bind_id'],'pigcms_id');
					$data_bind['pigcms_id'] = $now_user_info['pigcms_id'];
					if($now_user_info[$bind_field] - $now_order['money'] >= 0){
						$data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'];
					}else{
						$data_bind[$bind_field] = 0;
					}
					$data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'] >= 0 ? $now_user_info[$bind_field] - $now_order['money'] : 0;
					D('House_village_user_bind')->data($data_bind)->save();
				}
			}

			if(!empty($now_user['openid'])){
				$href = $this->config['site_url'].'/wap.php?g=Wap&c=House&a=village_my_paylists&village_id='.$now_order['village_id'];
				$model = new templateNews($this->config['wechat_appid'],$this->config['wechat_appsecret']);
				$model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' =>  ' 缴费成功提醒', 'keynote1' =>$now_order['order_name'], 'keynote2' =>'物业号 '. $now_user_info['usernum'], 'remark' => '缴费时间：'.date('Y年n月j日 H:i',$now_order['time']).'\n'.'缴费金额：￥'.$now_order['money']));
			}
			
			$village_info = D('House_village')->where(array('village_id'=>$now_order['village_id']))->find();
			$village_bind = D('House_village_user_bind')->where(array('pigcms_id'=>$now_order['bind_id']))->find();
			if($village_info['openid']){
				$href = "";
				$model = new templateNews($this->config['wechat_appid'],$this->config['wechat_appsecret']);
				$model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' =>  ' 业主 '.$village_bind['name'].'( '.$village_bind['address'].' )缴费成功提醒', 'keynote1' =>$village_info['property_name'], 'keynote2' =>$village_bind['name']."( ".$village_bind['phone']." )", 'remark' => '缴费周期： '.$now_order['property_month_num'].' 个月\n缴费时间：'.date('Y年n月j日 H:i',$now_order['time']).'\n'.'缴费金额：￥'.$now_order['money']));
			}



			redirect(U('House/village_my_paylists',array('village_id'=>$now_order['village_id'])));
		}else{
			redirect(U('My/recharge',array('money'=>$now_order['money']-$now_user['now_money'],'label'=>'wap_village_'.$order_id)));
		}
	}



	public function pay_order_cashier(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_user = D('User')->get_user($this->user_session['uid']);
		$order_id = $_GET['order_id'];
		// $now_order = D('House_village_pay_cashier_order')->field(true)->where(array('cashier_id'=>$order_id,'uid'=>$this->user_session['uid']))->find();
		// 任何人都可以缴费
		$now_order = D('House_village_pay_cashier_order')->field(true)->where(array('cashier_id'=>$order_id))->find();
		$now_village = D('House_village')->get_one($now_order['village_id']);
		if(empty($now_order)){
			$this->check_ajax_error_tips('当前订单不存在');
		}

		// 已支付
		if($now_order['paid']){
			redirect(U('House/village_my_paylists',array('village_id'=>$now_order['village_id'])));
		}

		// 不是订单创建者 更新订单所有者
		if ($now_order['uid'] != $this->user_session['uid']) {
			D('House_village_pay_cashier_order')->where(array('cashier_id'=>$order_id))->data(array('uid'=>$this->user_session['uid']))->save();
			D('House_village_pay_order')->where(array('cashier_id'=>$order_id))->data(array('uid'=>$this->user_session['uid']))->save();
		}

		//这里直接跳转支付，不走充值
		$pay_order_param = array(
				'business_type' => 'house_village_pay_cashier',
				'business_id' => $now_order['cashier_id'],
				'order_name' => '社区收银台缴费',
				'uid' => $this->user_session['uid'],
				'total_money' => $now_order['money'],
				'wx_cheap' => 0,
		);
		if($this->config['open_village_sub_mchid'] && $now_village['sub_mch_id']){
			$pay_order_param['is_own'] = 5;
		}else{
			$pay_order_param['is_own'] = 4;

		}

		$plat_order_result = D('Plat_order')->add_order($pay_order_param);
		if ($plat_order_result['error_code']) {
			$this->error_tips($plat_order_result['error_msg']);
		} else {
			redirect(U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')));
		}
		exit;
	}
	public function village_my(){
		//判断用户是否属于本小区
		
		if(empty($this->user_session)){
			if(IS_POST){
				$this->check_ajax_error_tips('请先进行登录',U('Login/index',array('referer'=>urlencode(U('House/village_my',array('village_id'=>$_GET['village_id']))))));
			}else{
				$this->error_tips('请先进行登录',U('Login/index',array('referer'=>urlencode(U('House/village_my',array('village_id'=>$_GET['village_id']))))));
			}
		}

		if(empty($this->user_session['phone'])){
			if(IS_POST){
				$this->check_ajax_error_tips('请先绑定手机号码',U('My/bind_user',array('referer'=>urlencode(U('village_my',array('village_id'=>$_GET['village_id']))))));
			}else{
				$this->error_tips('请先绑定手机号码',U('My/bind_user',array('referer'=>urlencode(U('village_my',array('village_id'=>$_GET['village_id']))))));
			}
		}


		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		
		$database_house_village_user_vacancy = D('House_village_user_vacancy');
        $where['village_id'] = $_GET['village_id'];
        $where['is_del'] = 0;
		$where['uid'] = $this->user_session['uid'];
        $result = $database_house_village_user_vacancy->where($where)->find();
		if(empty($this->village_bind)){
			if($result){
				redirect(U('my_village_list'));
			}else{
				redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
			}
			
		}

		// if(empty($this->village_bind)){
		// 	redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		// }
		
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);
		$where['phone'] = $now_user_info['phone'];
		$where['village_id'] = $now_user_info['village_id'];
		$vancany_list = M('House_village_user_vacancy')->where($where)->select();
		foreach ($vancany_list as $item) {
			if($item['uid']==0){
				M('House_village_user_vacancy')->where(array('pigcms_id'=>$item['pigcms_id']))->setField('uid',$now_user_info['uid']);
			}
		}
		$now_user = D('User')->get_user($this->user_session['uid']);
		$this->assign('now_user',$now_user);
		$find	=	M('User_authentication')->field('authentication_id')->where(array('uid'=>$this->user_session['uid']))->order('authentication_time DESC')->count();
		$this->assign('find',$find);

		$find_car	=	M('User_authentication_car')->field('car_id')->where(array('uid'=>$this->user_session['uid']))->order('add_time DESC')->count();
		$this->assign('find_car',$find_car);
		$this->display();
	}
	
	//我的小区列表 - wangdong
	public function my_village_list(){
		
		if(empty($this->user_session)){
			if(IS_POST){
				$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
			}else{
				$this->error_tips('请先进行登录',U('Login/index'));
			}
		}

		if(empty($this->user_session['phone'])){
			if(IS_POST){
				$this->check_ajax_error_tips('请先绑定手机号码',U('My/bind_user',array('referer'=>urlencode(U('my_village_list')))));
			}else{
				$this->error_tips('请先绑定手机号码',U('My/bind_user',array('referer'=>urlencode(U('my_village_list')))));
			}
		}
		$uid = $this->user_session['uid'];
		//查询我的小区
		
		//查询我的小区 应该从House_village_user_vacancy 这个表
		$database_house_village_user_vacancy = D('House_village_user_vacancy');
		$database_house_village_floor = D('House_village_floor');
		$database_village_list = D('House_village');
		$database_house_village_user_bind = D('House_village_user_bind');
		$database_house_village_user_unbind = D('House_village_user_unbind');
		
		$my_village_lists = $database_house_village_user_vacancy->get_my_village_lists($this->user_session['uid']);
		foreach($my_village_lists as $k=>&$v){
				
				$v['unbind_status'] = $database_house_village_user_unbind->where(array('type'=>array('in','0,3'),'village_id'=>$v['village_id'],'name'=>$v['name'] ,'phone'=>$v['phone'],'floor_id'=>$v['floor_id'],'room_id'=>$v['pigcms_id'],'uid'=>$v['uid'],'status'=>1))->count();
				
				//根据信息得到我所在房间的单元信息和小区信息
				$floor = $database_house_village_floor->get_unit_find($v['floor_id'] , 'village_id,floor_name,floor_layer');
				$village_info = $database_village_list->get_village_info($v['village_id'] , 'village_name,village_address');
				$v['village_name']        =  $village_info['village_name'];
				$v['village_address']     =  $village_info['village_address'];
				$v['floor_name']          =  $floor['floor_name'];
				$v['floor_layer']         =  $floor['floor_layer'];
				
				if($v['status']==3){
					$info_id = $database_house_village_user_bind->where(array('vacancy_id'=>$v['pigcms_id'] , 'village_id'=>$v['village_id'] , 'type'=>array("in","0,3") , 'status'=>1))->getField('pigcms_id');
					$v['bind_pigcms_id'] = $info_id;
				}
				
				//获取亲属/租客
				$v['child_list'] = $database_house_village_user_bind->get_my_room_user($v['pigcms_id']);
		}
		//print_r($my_village_lists);

		$my_village['my_village_lists'] = $my_village_lists;
		
		
		//查询我加入的小区房间的亲属或者租客 并且这个房主不是我自己本人
		
		//得到我是业主的所有房间号
		$my_village_room_array_id = $database_house_village_user_vacancy->get_my_village_lists($this->user_session['uid'] , 'pigcms_id');
		
		$village_room_str = "";
		if(!empty($my_village_room_array_id)){
			foreach($my_village_room_array_id as $key=>$value) $village_room_str .= ",".$value['pigcms_id'];
		}

		//得到我不是业主的绑定信息
		$my_room_not_master = $database_house_village_user_bind->get_my_room_not_master($this->user_session['uid'] , '1,2' , substr($village_room_str,1));
		if(!empty($my_room_not_master)){
			foreach($my_room_not_master as $k=>&$v){
				
				$v['unbind_status'] = $database_house_village_user_unbind->where(array('type'=>$v['type'],'village_id'=>$v['village_id'],'name'=>$v['name'] ,'phone'=>$v['phone'],'floor_id'=>$v['floor_id'],'room_id'=>$v['vacancy_id'],'uid'=>$v['uid'],'status'=>1))->getField('itemid');
				
				//根据信息查询小区信息 单元信息 房间信息
				$find_village_room = $database_house_village_user_vacancy->get_find_room_info($v['vacancy_id'] , "layer,room");
				$find_village_floor = $database_house_village_floor->get_unit_find($v['floor_id'] , 'floor_name,floor_layer');
				$find_village = $database_village_list->get_village_info($v['village_id'] , 'village_name,village_address');
				
				$v['vacancy_layer']       =  $find_village_room['layer'];
				$v['vacancy_room']        =  $find_village_room['room'];
				$v['village_name']        =  $find_village['village_name'];
				$v['village_address']     =  $find_village['village_address'];
				$v['floor_name']          =  $find_village_floor['floor_name'];
				$v['floor_layer']         =  $find_village_floor['floor_layer'];
				
			}	
		}
		//print_r($my_room_not_master);
		$my_village['my_village_vacancy'] = $my_room_not_master;
		$this->assign("my_village" , $my_village);
		
		$this->display();	
	}

	#用户删除申请记录 房主/亲属/租客
	public function ajax_delete_audit(){
		if(IS_AJAX){
			$pigcms_id = $_POST['pigcms_id'] + 0;
			if(!$pigcms_id){
				exit(json_encode(array('status'=>0,'msg'=>"传递参数有误！")));
			}
			
			//查询bind信息
			$database_house_village_user_bind = D('House_village_user_bind');
			
			$bind_info = $database_house_village_user_bind->where(array('pigcms_id'=>$pigcms_id ))->find();
			if(!$bind_info){
				exit(json_encode(array('status'=>0,'msg'=>'住户信息不存在！')));
			}

			if($bind_info['status'] != 2){
				exit(json_encode(array('status'=>0,'msg'=>'不在审核中，不能删除！')));
			}
			if ($bind_info['uid'] != $this->user_session['uid']) { //不是当前用户信息
				//查看是不是业主删除家属记录
				$user_info = $database_house_village_user_bind->where(array('vacancy_id'=>$bind_info['vacancy_id'],'type'=>0,'status'=>1,'uid'=>$this->user_session['uid'] ))->find();
				if (!$user_info) {
					exit(json_encode(array('status'=>0,'msg'=>'对不起，您没有权限删除！')));
				}
			}

			$where = array();
			$where['pigcms_id'] = $pigcms_id;
			$result = $database_house_village_user_bind->data($where)->delete();

			if ($bind_info['type'] == 0) { // 业主 更新房屋信息
				$where = array();
				$where['pigcms_id'] = $bind_info['vacancy_id'];
				$data = array(
					'status' => 1, //空置
					'uid' => 0,
					'name' => '',
					'phone' => '',
				);
				D('House_village_user_vacancy')->where($where)->data($data)->save();
			}
			if($result){
				exit(json_encode(array('status'=>1,'msg'=>'删除申请成功！')));
			}else{
				exit(json_encode(array('status'=>0,'msg'=>'删除申请失败！')));
			}
		}
	}

	#用户删除申请记录 房主
	public function ajax_delete_audit_yezhu(){
		if(IS_AJAX){
			$pigcms_id = $_POST['pigcms_id'] + 0;
			if(!$pigcms_id){
				exit(json_encode(array('status'=>0,'msg'=>"传递参数有误！")));
			}
			
			//查询bind信息
			$database_house_village_user_vacancy = D('House_village_user_vacancy');
			
			$bind_info = $database_house_village_user_vacancy->where(array('pigcms_id'=>$pigcms_id ))->find();
			if(!$bind_info){
				exit(json_encode(array('status'=>0,'msg'=>'申请信息不存在！')));
			}

			if($bind_info['status'] != 2){
				exit(json_encode(array('status'=>0,'msg'=>'不在审核中，不能删除！')));
			}
				
			if ($bind_info['uid'] != $this->user_session['uid']) { //不是当前用户信息
				exit(json_encode(array('status'=>0,'msg'=>'对不起，您没有权限删除！')));
			}

		
			$where = array();
			$where['pigcms_id'] = $pigcms_id;
			$data = array(
				'status' => 1, //空置
				'uid' => 0,
				'name' => '',
				'phone' => '',
			);
			$result = $database_house_village_user_vacancy->where($where)->data($data)->save();
			
			if($result){
				exit(json_encode(array('status'=>1,'msg'=>'删除申请成功！')));
			}else{
				exit(json_encode(array('status'=>0,'msg'=>'删除申请失败！')));
			}
		}
	}
	
	#绑定亲属 - wangdong
	public function empty_bind_relatives(){
		
		if(empty($this->user_session)){
			if(IS_POST){
				$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
			}else{
				$this->error_tips('请先进行登录',U('Login/index'));
			}
		}
		
		$database_house_village_user_vacancy = D("House_village_user_vacancy");
		$database_house_village_floor = D('House_village_floor');
		$database_village_list = D('House_village');
		$database_house_village_user_bind = D('House_village_user_bind');
		
		//查询用户所属业主的所有房屋列表
		$user_all_village_list = $database_house_village_user_vacancy -> get_my_village_lists($this->user_session['uid']);
		
		foreach($user_all_village_list as $k=>&$v){
			
			//获取小区信息和房间信息	
			$find_village_floor = $database_house_village_floor->get_unit_find($v['floor_id'] , 'floor_name,floor_layer');
			$find_village = $database_village_list->get_village_info($v['village_id'] , 'village_name,village_address');
			$v['village_name']        =  $find_village['village_name'];
			$v['village_address']     =  $find_village['village_address'];
			$v['floor_name']          =  $find_village_floor['floor_name'];
			$v['floor_layer']         =  $find_village_floor['floor_layer'];
				
		}
		$this->assign("user_all_village_list" , $user_all_village_list);
		
		if(IS_POST){
			$type = intval($_POST['type']);
			$phone = trim($_POST['phone']);
			$name = trim($_POST['name']);
			$pigcms_id = intval($_POST['pigcms_id']);
			
			$where_bind_info['type'] = array("in" , "0,3");
			$where_bind_info['status'] = 1;
			$where_bind_info['vacancy_id'] = $pigcms_id;
			$bind_info = $database_house_village_user_bind->where($where_bind_info)->find();
			$parent_id = $bind_info['pigcms_id'];

			if(!$this->config['international_phone'] && !preg_match('/^[0-9]{11}$/',$phone)){
				$this->error_tips('请输入有效的手机号。');
			}

			$uid = D('User')->get_user_by_phone($phone);

			if($uid > 0){
				$bind_data['uid'] = $uid;
			}else{
				$this->error_tips('用户手机号码不正确！');
			}

			$bind_data['housesize'] = $_POST['housesize'] + 0;
			$bind_data['park_flag'] = $_POST['park_flag'] + 0;

			
			$vacancy_condition['pigcms_id'] = $pigcms_id;
			//$vacancy_condition['is_del'] = 0;
			
			$bind_info = $database_house_village_user_vacancy->where($vacancy_condition)->find();
			
			if($phone == $bind_info['phone']){
				$this->error_tips('手机号不能与业主一致');
			}

			$bind_condition['parent_id'] =   $parent_id;
			$bind_condition['phone']     =   $phone;
			$bind_condition['type']      =   $type;
			$bind_condition['status']    =   1;
		
			$family_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
			
			//判断是否被拒绝 如果不被拒绝再提交的 应该删除之前拒绝的信息 重新添加
			$bind_condition_not['parent_id'] = $parent_id;
			$bind_condition_not['phone'] = $phone;
			$bind_condition_not['type'] = $type;
			$bind_condition_not['status'] = array('in' , '0,2');
			$family_bind_info_not = $database_house_village_user_bind->where($bind_condition_not)->find();
			if($family_bind_info_not){
				$database_house_village_user_bind->where($bind_condition_not)->delete();	
			}
			//删除over
			
			
			if($family_bind_info){
				$this->error_tips('该用户已经是你的亲属了!' , U('my_village_list'));
			}
			
			$floor_condition['floor_id'] =  $bind_info['floor_id'];
			$floor_info = $database_house_village_floor->where($floor_condition)->find();
			
			$bind_data['village_id'] = $bind_info['village_id'];
			$bind_data['address']    = $floor_info['floor_layer'].$floor_info['floor_name'].$bind_info['layer'].$bind_info['room'];
			$bind_data['layer_num']  = intval($bind_info['layer']);
			$bind_data['room_addrss']    = intval($bind_info['room']);
			$bind_data['floor_id']   = $bind_info['floor_id'];
			$bind_data['vacancy_id']    =  $pigcms_id;
			$bind_data['housesize'] =  $bind_info['housesize'];
			$bind_data['park_flag'] =  $bind_info['park_flag'];
			
			$bind_data['uid'] = $uid;	
			$bind_data['name'] = $name;
			$bind_data['type'] = 1;
			$bind_data['status'] = 1;
			$bind_data['phone'] = $phone;
			$bind_data['add_time'] = time();
			$bind_data['usernum'] = rand(0,99999) . '-' . time();
			$bind_data['parent_id'] = $parent_id;
			$bind_data['memo'] = "";
			
			$insert_id = $database_house_village_user_bind->data($bind_data)->add();
			if($insert_id){
				$this->success_tips('提交审核成功！' , U('my_village_list'));
			}else{
				$this->error_tips('提交审核失败！' , U('my_village_list'));
			}
				
		}	
		
		
		$this->display();
			
	}
	
	# 根据手机号 获取用户 -  wangdong
	public function ajax_empty_bind_relatives_user(){
	
		$database_user = D('User');
		
		$phone = $_POST['relatives_user'];
		
		if(empty($phone)) return false;

		if($phone == $this->user_session['phone']){
		
			echo "10001";
			
			exit;
			
		}

		$condition['phone'] = $phone;
		$condition['status'] =1;
		
		$user_info = D('User') -> field(true)->where($condition)->find();
		
		echo json_encode($user_info);
		
		exit;
			
	}
	
	
	# 申请车主认证
	public function car_apply(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$plate_number	=	$this->plate_number();
		$find	=	M('User_authentication_car')->field(true)->where(array('uid'=>$this->user_session['uid']))->order('add_time DESC')->find();
		if($find){
			if($find['status'] == 2){
				$find	=	0;
			}
		}
		$this->assign('plate_number',$plate_number);
		$this->assign('find',$find);
		$this->display();
	}

	# 车主实名认证
	public function car_owner(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$find	=	M('User_authentication_car')->field(true)->where(array('uid'=>$this->user_session['uid']))->order('add_time DESC')->find();
		if($find){
			$image_class = new scenic_image();
			$find['authentication_img'] = $image_class->get_car_by_path($find['authentication_img'],$this->config['site_url'],'authentication_car','s');
			$find['authentication_back_img'] = $image_class->get_car_by_path($find['authentication_back_img'],$this->config['site_url'],'authentication_car','s');
			$find['drivers_license'] = $image_class->get_car_by_path($find['drivers_license'],$this->config['site_url'],'authentication_car','s');
			$find['driving_license'] = $image_class->get_car_by_path($find['driving_license'],$this->config['site_url'],'authentication_car','s');
		}
		$this->assign('find',$find);
		$this->display();
	}

	public function authentication_index(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$authentication	=	D('User_authentication')->field(true)->where(array('uid'=>$this->user_session['uid']))->find();
		if($authentication){
			$store_image_class = new scenic_image();
			$a_img = strstr($authentication['authentication_img'], ',',true);
			$b_img = strstr($authentication['authentication_back_img'], ',',true);
			if($a_img){
				$authentication['authentication_img'] = $store_image_class->get_image_by_path($authentication['authentication_img'],$this->config['site_url'],'aguide','1');
			}
			if($b_img){
				$authentication['authentication_back_img'] = $store_image_class->get_image_by_path($authentication['authentication_back_img'],$this->config['site_url'],'aguide','1');
			}
		}else{
			redirect(U('authentication'));
		}
		$this->assign('authentication',$authentication);
		$this->display();
	}
	public function village_my_pay(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			// redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
			redirect(U('village_list'));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		//家属 获取业主的欠费信息
		if ($now_user_info['parent_id']) {
			$now_user_info = $this->get_user_village_info($now_user_info['parent_id']);
		}

		//缴费
		$pay_list = array();
		$now_village['property_price'] = getFormatNumber($now_village['property_price']);
		$now_village['water_price'] = getFormatNumber($now_village['water_price']);
		$now_village['electric_price'] = getFormatNumber($now_village['electric_price']);
		$now_village['gas_price'] = getFormatNumber($now_village['gas_price']);
		$now_village['park_price'] = getFormatNumber($now_village['park_price']);
		$pay_list[] = array(
				'type' => 'property',
				'name' => $this->pay_list_type['property'],
				'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'property')),
				'money'=>floatval($now_user_info['property_price']),
		);
		if($now_village['water_price']){
			$pay_list[] = array(
					'type' => 'water',
					'name' => $this->pay_list_type['water'],
					'url' => $now_village['water_price'] ? U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'water')) : U('Lifeservice/query',array('type'=>'water')),
					'money'=>floatval($now_user_info['water_price']),
			);
		}
		if($now_village['electric_price']){
			$pay_list[] = array(
					'type' => 'electric',
					'name' => $this->pay_list_type['electric'],
					'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'electric')),
					'money'=>floatval($now_user_info['electric_price']),
			);
		}
		if($now_village['gas_price']){
			$pay_list[] = array(
					'type' => 'gas',
					'name' => $this->pay_list_type['gas'],
					'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'gas')),
					'money'=>floatval($now_user_info['gas_price']),
			);
		}
		if($now_village['park_price']){
			$pay_list[] = array(
					'type' => 'park',
					'name' => $this->pay_list_type['park'],
					'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'park')),
					'money'=>floatval($now_user_info['park_price']),
			);
		}
		if($now_village['has_custom_pay']){
			$pay_list[] = array(
					'type' => 'custom',
					'name' => $this->pay_list_type['custom'],
					'url' => U('House/village_pay',array('village_id'=>$now_village['village_id'],'type'=>'custom')),
					'money'=> -1,
			);
		}

		$payment_list = D('')->table(array(C('DB_PREFIX').'house_village_payment_standard_bind'=>'psb',
				C('DB_PREFIX').'house_village_payment_standard'=>'ps',
				C('DB_PREFIX').'house_village_payment'=>'p'))
				->where("psb.pigcms_id= '".$now_user_info['pigcms_id']."' AND p.payment_id = psb.payment_id AND ps.standard_id = psb.standard_id")->select();
        $payment_list = $payment_list ? $payment_list : array();

        // 车位缴费
        $position_payment_list = D('House_village_bind_position')->get_user_position_payment_list(array('pigcms_id'=>$now_user_info['pigcms_id']));
        $payment_list = array_merge($payment_list, $position_payment_list);
       	foreach ($payment_list as $kk => $vv) {
        	if ($vv['garage_num']) {
            	$payment_list[$kk]['payment_name'] = $vv['payment_name'].'('.$vv['garage_num'].'-'.$vv['position_num'].')';
        	}
        }

// dump($payment_list);
		$this->assign('payment_list',$payment_list);
		$this->assign('pay_list',$pay_list);


		// 增加物业缴费定制连接
		// $floor_where['floor_id'] = $_SESSION['now_village_bind']['floor_id'];
		// $floor_where['status'] = 1;
		// $floor_info = D('House_village_floor')->where($floor_where)->find();
		// $urlInfo['village_name'] = $now_village['village_name'];
		// $urlInfo['room_addrss'] = $_SESSION['now_village_bind']['room_addrss'];
		// $urlInfo['floor_name'] = $floor_info['floor_name'];
		// $urlInfo['floor_layer'] = $floor_info['floor_layer'];
		// $this->assign('urlInfo',$urlInfo);

		$this->display();
	}
	public function village_my_paylists(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		//显示自己所缴费用 业主显示房屋所有缴费

		$field='`o`.*,`p`.`start_time`,`p`.`end_time`';
        $join='left join '.C('DB_PREFIX').'house_village_property_paylist as `p` on `o`.`order_id`=`p`.`order_id`';
        $order='`o`.order_id desc';
        $where = '`o`.`paid`=1 AND (`o`.`uid` = '.$now_user_info['uid'].' OR `o`.`bind_id`='.$now_user_info['pigcms_id'].') AND `o`.`village_id`='.$now_village['village_id'];

		if($_GET['order_type']){
			$where .= ' AND `o`.`order_type`="'.$_GET['order_type'].'"';
		}

		$order_list = D('House_village_pay_order')->alias('`o`')->field($field)->join($join)->where($where)->order($order)->select();
		foreach($order_list as $Key=>$order){
			// 业主信息
			$user_info = D('House_village_user_bind')->where(array('pigcms_id'=>$order['bind_id']))->find();
			$order_list[$Key]['to_name'] = $user_info['name'];
			$order_list[$Key]['to_phone'] = $user_info['phone'];
			$order_list[$Key]['to_room'] = $user_info['address'];
			// 缴费人
			$onwer_user = D('User')->where(array('uid'=>$order['uid']))->find();
			$order_list[$Key]['onwer_name'] = $onwer_user['nickname'];
			$order_list[$Key]['onwer_phone'] = $onwer_user['phone'];
		}
		$this->assign('order_list',$order_list);
		$this->assign('pay_type_list',$this->pay_list_type);
		$this->display();
	}
	public function ajax_village_my_paylists(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		// $order_where['bind_id'] = $now_user_info['pigcms_id'];
		//显示自己所缴费用 业主显示房屋所有缴

		$field='o.*,`p`.`start_time`,`p`.`end_time`';
        $join='left join '.C('DB_PREFIX').'house_village_property_paylist as `p` on o.order_id=p.order_id';
        $order='o.order_id desc';
        $where = '`o`.`paid`=1 AND (`o`.`uid` = '.$now_user_info['uid'].' OR `o`.`bind_id`='.$now_user_info['pigcms_id'].') AND `o`.`village_id`='.$now_village['village_id'];

		if($_GET['order_type']){
			$where .= ' AND `o`.`order_type`="'.$_GET['order_type'].'"';
		}

		$order_list = D('House_village_pay_order')->alias('`o`')->field($field)->join($join)->where($where)->order($order)->select();

		foreach($order_list as $Key=>$order){
			// 业主信息
			$user_info = D('House_village_user_bind')->where(array('pigcms_id'=>$order['bind_id']))->find();
			$order_list[$Key]['to_name'] = $user_info['name'];
			$order_list[$Key]['to_phone'] = $user_info['phone'];
			$order_list[$Key]['to_room'] = $user_info['address'];
			// 缴费人
			$onwer_user = D('User')->where(array('uid'=>$order['uid']))->find();
			$order_list[$Key]['onwer_name'] = $onwer_user['nickname'];
			$order_list[$Key]['onwer_phone'] = $onwer_user['phone'];
			$order_list[$Key]['time'] = date('Y-m-d H:i',$order['time']);
		}
		if($order_list){
			exit(json_encode(array('status'=>1,'order_list'=>$order_list)));
		}else{
			exit(json_encode(array('status'=>0,'order_list'=>$order_list)));
		}
	}
	public function village_my_repairlists(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		$repair_list = D('House_village_repair_list')->field(true)->where(array('bind_id'=>$now_user_info['pigcms_id'],'village_id'=>$now_village['village_id'],'type'=>'1'))->order('`pigcms_id` DESC')->select();
		$this->assign('repair_list',$repair_list);

		$this->display();
	}
	public function village_my_repair_detail(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		$repair_detail = D('House_village_repair_list')->field(true)->where(array('pigcms_id'=>$_GET['id']))->find();
		if(empty($repair_detail)){
			$this->check_ajax_error_tips('当前报修内容不存在');
		}
		if ($repair_detail['wid']) $this->worker($repair_detail['wid']);
		if($repair_detail['pic']){
			$repair_detail['picArr'] = explode('|',$repair_detail['pic']);
		}
		if($repair_detail['reply_pic']){
			$repair_detail['reply_picArr'] = explode('|',$repair_detail['reply_pic']);
		}
		if($repair_detail['comment_pic']){
			$repair_detail['comment_picArr'] = explode('|',$repair_detail['comment_pic']);
		}
		$logs = D('House_village_repair_log')->field(true)->where(array('repair_id' => $repair_detail['pigcms_id']))->order('lid desc')->select();
		foreach ($logs as $log) {
			$repair_detail['status_time_' . $log['status']] = $log['dateline'];
		}
		$this->assign('repair_detail', $repair_detail);
		$this->assign('logs', $logs);
		$this->assign('back_url', U('House/village_my_repairlists', array('village_id' => $now_village['village_id'])));
		$this->assign('title', '在线报修');
		$this->assign('type', 'repair');
		$this->display('status');
	}
	private function worker($wid){
		$worker = D('House_worker')->field(true)->where(array('wid' => $wid))->find();
		$this->assign('worker', $worker);
	}
	public function village_my_repair(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$village_id	=	I('get.village_id');
		if(empty($village_id)){
			$village_id	=	$_GET['village_id'];
		}
		$now_village = $this->get_village($village_id);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);
		if(IS_POST && !empty($_POST['content'])){
			if(empty($_POST['content'])){
				$this->check_ajax_error_tips('请填写内容');
			}
			$inputimg=isset($_POST['inputimg']) ? $_POST['inputimg'] :'';
			$picArr = array();
			if(!empty($inputimg)){
				foreach($inputimg as $imgv){
					$imgv = str_replace('/upload/house/','',$imgv);
					$picArr[] = $imgv;
				}
			}
			$data_repair['pic'] = implode('|',$picArr);
			$data_repair['content'] = $_POST['content'];
			$data_repair['village_id'] = $now_village['village_id'];
			$data_repair['uid'] = $this->user_session['uid'];
			$data_repair['bind_id'] = $now_user_info['pigcms_id'];
			$data_repair['is_read'] = '0';
			$data_repair['status'] = '0';
			$data_repair['type'] = '1';
			$data_repair['time'] = $_SERVER['REQUEST_TIME'];
			if($repair_id = D('House_village_repair_list')->data($data_repair)->add()){
				M('House_village')->where(array('village_id'=>$now_village['village_id']))->setInc('repair_reminder');
				D('House_village_repair_log')->add_log(array('status' => 0, 'repair_id' => $repair_id));
				$this->header_json();
				echo json_encode(array('err_code'=>1,'order_url'=>U('House/pay_order',array('order_id'=>$order_id))));
				exit();
			}else{
				$this->check_ajax_error_tips('提交失败，请重试');
			}
		}else{
			$this->display();
		}
	}
	public function village_my_utilitieslists(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		$repair_list = D('House_village_repair_list')->field(true)->where(array('bind_id'=>$now_user_info['pigcms_id'],'village_id'=>$now_village['village_id'],'type'=>'2'))->order('`pigcms_id` DESC')->select();
		$this->assign('repair_list',$repair_list);

		$this->display();
	}
	public function village_my_utilities_detail(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		$repair_detail = D('House_village_repair_list')->field(true)->where(array('pigcms_id'=>$_GET['id']))->find();
		if(empty($repair_detail)){
			$this->check_ajax_error_tips('当前报修内容不存在');
		}
		if ($repair_detail['wid']) $this->worker($repair_detail['wid']);
		if($repair_detail['pic']){
			$repair_detail['picArr'] = explode('|',$repair_detail['pic']);
		}
		if($repair_detail['reply_pic']){
			$repair_detail['reply_picArr'] = explode('|',$repair_detail['reply_pic']);
		}
		if($repair_detail['comment_pic']){
			$repair_detail['comment_picArr'] = explode('|',$repair_detail['comment_pic']);
		}
		$logs = D('House_village_repair_log')->field(true)->where(array('repair_id' => $repair_detail['pigcms_id']))->order('status asc')->select();
		foreach ($logs as $log) {
			$repair_detail['status_time_' . $log['status']] = $log['dateline'];
		}
		$this->assign('repair_detail', $repair_detail);
		$this->assign('logs', $logs);
		$this->assign('back_url', U('House/village_my_utilitieslists', array('village_id' => $now_village['village_id'])));
		$this->assign('title', '水电煤上报');
		$this->assign('type', 'utilities');

		$this->display('status');
	}
	public function village_my_utilities(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		if(IS_POST && !empty($_POST['content'])){
			if(empty($_POST['content'])){
				$this->check_ajax_error_tips('请填写内容');
			}
			$inputimg=isset($_POST['inputimg']) ? $_POST['inputimg'] :'';
			$picArr = array();
			if(!empty($inputimg)){
				foreach($inputimg as $imgv){
					$imgv = str_replace('/upload/house/','',$imgv);
					$picArr[] = $imgv;
				}
			}
			$data_repair['pic'] = implode('|',$picArr);
			$data_repair['content'] = $_POST['content'];
			$data_repair['village_id'] = $now_village['village_id'];
			$data_repair['uid'] = $this->user_session['uid'];
			$data_repair['bind_id'] = $now_user_info['pigcms_id'];
			$data_repair['is_read'] = '0';
			$data_repair['status'] = '0';
			$data_repair['type'] = '2';
			$data_repair['time'] = $_SERVER['REQUEST_TIME'];
			if($repair_id = D('House_village_repair_list')->data($data_repair)->add()){
				D('House_village_repair_log')->add_log(array('status' => 0, 'repair_id' => $repair_id));
				$this->header_json();
				echo json_encode(array('err_code'=>1,'order_url'=>U('House/pay_order',array('order_id'=>$order_id))));
				exit();
			}else{
				$this->check_ajax_error_tips('提交失败，请重试');
			}
		}else{
			$this->display();
		}
	}
	public function village_my_suggest(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		if(IS_POST && !empty($_POST['content'])){
			if(empty($_POST['content'])){
				$this->check_ajax_error_tips('请填写内容');
			}
			$inputimg=isset($_POST['inputimg']) ? $_POST['inputimg'] :'';
			$picArr = array();
			if(!empty($inputimg)){
				foreach($inputimg as $imgv){
					$imgv = str_replace('/upload/house/','',$imgv);
					$picArr[] = $imgv;
				}
			}
			$data_repair['pic'] = implode('|',$picArr);
			$data_repair['content'] = $_POST['content'];
			$data_repair['village_id'] = $now_village['village_id'];
			$data_repair['uid'] = $this->user_session['uid'];
			$data_repair['bind_id'] = $now_user_info['pigcms_id'];
			$data_repair['is_read'] = '0';
			$data_repair['status'] = '0';
			$data_repair['type'] = '3';
			$data_repair['time'] = $_SERVER['REQUEST_TIME'];
			if($repair_id = D('House_village_repair_list')->data($data_repair)->add()){
				M('House_village')->where(array('village_id'=>$now_village['village_id']))->setInc('suggest_reminder');
				D('House_village_repair_log')->add_log(array('status' => 0, 'repair_id' => $repair_id));
				$this->header_json();
				echo json_encode(array('err_code'=>1,'order_url'=>U('House/pay_order',array('order_id'=>$order_id))));
				exit();
			}else{
				$this->check_ajax_error_tips('提交失败，请重试');
			}
		}else{
			$this->display();
		}
	}
	public function village_my_suggestlist(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}

		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);
		$repair_list = D('House_village_repair_list')->where(array('bind_id'=>$now_user_info['pigcms_id'],'village_id'=>$now_village['village_id'],'type'=>'3'))->order('`pigcms_id` DESC')->select();
		$this->assign('repair_list',$repair_list);
		$this->display();
	}
	public function village_my_suggest_detail(){
		if(empty($this->user_session)){
			$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
		}
		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);
		if(empty($this->village_bind)){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}
		$now_user_info = $this->get_user_village_info($this->village_bind['pigcms_id']);

		$repair_detail = D('House_village_repair_list')->field(true)->where(array('pigcms_id'=>$_GET['id']))->find();
		if(empty($repair_detail)){
			$this->check_ajax_error_tips('当前报修内容不存在');
		}
		if ($repair_detail['wid']) $this->worker($repair_detail['wid']);
		if($repair_detail['pic']){
			$repair_detail['picArr'] = explode('|',$repair_detail['pic']);
		}
		if($repair_detail['reply_pic']){
			$repair_detail['reply_picArr'] = explode('|',$repair_detail['reply_pic']);
		}
		if($repair_detail['comment_pic']){
			$repair_detail['comment_picArr'] = explode('|',$repair_detail['comment_pic']);
		}
		$logs = D('House_village_repair_log')->field(true)->where(array('repair_id' => $repair_detail['pigcms_id']))->order('status asc')->select();
		foreach ($logs as $log) {
			$repair_detail['status_time_' . $log['status']] = $log['dateline'];
		}
		$this->assign('repair_detail', $repair_detail);
		$this->assign('logs', $logs);
		$this->assign('back_url', U('House/village_my_suggestlist', array('village_id' => $now_village['village_id'])));
		$this->assign('title', '投诉');
		$this->assign('type', 'suggest');
		$this->display('status');
	}
	public function village_my_bind_family_add(){
		$this->check_village_session(I('get.village_id'));
		if(IS_POST && !empty($_POST['phone']) && !empty($_POST['name'])){
			if(empty($this->user_session)){
				$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
			}

			$phone = $_POST['phone'];
			$name = $_POST['name'];
			if(!$phone){
				$this->check_ajax_error_tips('当前手机号不存在');
			}
			if(!$this->config['international_phone'] && !preg_match('/^[0-9]{11}$/',$phone)){
				$this->check_ajax_error_tips('手机号码不正确！');
			}

			$database_user = D('User');
			$user_info = $database_user->get_user($phone,'phone');
			if(!$user_info){
				$this->check_ajax_error_tips('当前手机号未注册，请先注册，再进行绑定！');
			}

			if(!$user_info['status']){
				$this->check_ajax_error_tips('该注册手机号已被禁止！');
			}

			if($this->user_session['phone'] == $user_info['phone']){
				$this->check_ajax_error_tips('手机号码不能与自己相同！');
			}

			$database_house_village_user_bind = D('House_village_user_bind');
			$where['phone'] = $this->user_session['phone'];
			$pigcms_id = $database_house_village_user_bind->where($where)->getField('pigcms_id');
			$_where['parent_id'] = $pigcms_id;
			$family_num = $database_house_village_user_bind->where($_where)->count();
			if($family_num > 5){
				$this->check_ajax_error_tips('最多添加5名家属！');
			}

			$data['phone'] = $phone;
			$data['uid'] = $user_info['uid'];
			$data['name'] = $name;
			$list = $database_house_village_user_bind->where(array('phone'=>$this->user_session['phone']))->select();
			$data['parent_id'] = $_SESSION['now_village_bind']['pigcms_id'];
			$data['village_id'] = $_SESSION['now_village_bind']['village_id'];

			$data['usernum'] = rand(0,99999) . '-' . time();
			$result = $database_house_village_user_bind->house_village_my_bind_family_add($data);
			if(!$result['status']){
				$this->check_ajax_error_tips($result['msg']);
			}

			$this->header_json();
			echo json_encode(array('err_code'=>$result['status'] , 'err_msg'=>$result['msg']));
			//}
		}else{
			$village_id	=	I('get.village_id');
			if(empty($village_id)){
				$village_id = $_GET['village_id'] + 0;
			}
			if($this->village_bind['village_id'] != $village_id){
				$this->error_tips('您不属于当前小区');
			}else{
				$this->display();
			}
		}
	}
//家属列表
	public function village_my_bind_family_list(){
		$village_id = $_GET['village_id'] + 0;
		$this->check_village_session($village_id);
		if($this->village_bind['village_id'] != $village_id){
			$this->error_tips('您不属于当前小区',U('Login/index'));
		}

		$database_house_village_user_bind = D('House_village_user_bind');
		$Map['parent_id'] = $_SESSION['now_village_bind']['pigcms_id'];
		$Map['village_id'] = $village_id;
		$family_list = $database_house_village_user_bind->house_village_my_bind_family_list($Map);
		$this->assign('family_list',$family_list);

		$this->display();
	}
//删除家属关系
	public function ajax_village_my_bind_family_del(){
		$pigcms_id = $_POST['pigcms_id'] + 0;
		if(!$pigcms_id){
			return false;
		}

		$database_house_village_user_bind = D('House_village_user_bind');
		$where['pigcms_id'] = $pigcms_id;
		$insert_id = $database_house_village_user_bind->where($where)->delete();
		if($insert_id){
			exit(json_encode(array('status'=>1,'msg'=>'解绑成功！')));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>'解绑失败！')));
		}
	}
	/*     * *图片上传** */
	public function ajaxImgUpload(){
		$filename = trim($_POST['filename']);
		$img = $_POST[$filename];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$imgdata = base64_decode($img);
		$img_order_id = sprintf("%09d",$this->user_session['uid']);
		$rand_num = mt_rand(10,99).'/'.substr($img_order_id,0,3).'/'.substr($img_order_id,3,3).'/'.substr($img_order_id,6,3);
		$getupload_dir = "/upload/house/" .$rand_num;

		$upload_dir = "." . $getupload_dir;
		if (!is_dir($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}
		$newfilename = date('YmdHis') . '.jpg';
		$save = file_put_contents($upload_dir . '/' . $newfilename, $imgdata);
		$save = file_put_contents($upload_dir . '/m_' . $newfilename, $imgdata);
		$save = file_put_contents($upload_dir . '/s_' . $newfilename, $imgdata);
		if ($save) {
			$this->dexit(array('error' => 0, 'data' => array('code' => 1, 'siteurl'=>$this->config['site_url'],'imgurl' =>$getupload_dir . '/' . $newfilename, 'msg' => '')));
		} else {
			$this->dexit(array('error' => 1, 'data' => array('code' => 0, 'url' => '', 'msg' => '保存失败！')));
		}
	}
//微信访客信息
	public function village_visitor_info(){
		$id = $_GET['id'] + 0;
		if(!$id){
			$this->check_ajax_error_tips('传递参数有误！');
		}
		$where['id'] = $id;
		$database_house_village_visitor = D('House_village_visitor');
		if(IS_POST){
			if(empty($this->user_session)){
				$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
			}
			$where['owner_uid'] = $this->user_session['uid'];
			$result = $database_house_village_visitor->house_village_visitor_edit($where,$_POST);
			if(!$result){
				exit(json_encode(array('status'=>$result['status'],'msg'=>'数据处理有误！')));
			}else{
				exit(json_encode(array('status'=>$result['status'],'msg'=>$result['msg'])));
			}
		}else{
			$detail = $database_house_village_visitor->house_village_visitor_detail($where);
			if(!$detail){
				$this->check_ajax_error_tips('数据处理有误！');
			}else{
				$detail = $detail['detail'];
				$this->assign('detail' , $detail);
				$now_village = $this->get_village($detail['village_id']);
				$this->assign('now_village',$now_village);
			}
			$this->display();
		}
	}
	public function village_activityapply(){
		$village_id = $_GET['village_id'] + 0;
		$this->check_village_session($village_id);
		if($this->village_bind['village_id'] != $village_id){
			$this->error_tips('您不属于当前小区',U('Login/index'));
		}


		$activity_id = $_GET['activity_id'] + 0;
		if(!$activity_id){
			$this->error_tips('传递参数有误！');
		}

		$database_house_village_activity = D('House_village_activity');
		$where['id'] = $activity_id;
		$now_activity = $database_house_village_activity->house_village_activity_detail($where);
		$now_activity = $now_activity['detail'];
		if(!$now_activity){
			$this->error_tips('社区活动不存在！');
		}

		if(IS_POST){
			$database_house_village_activity_apply = D('House_village_activity_apply');

			//判断是否可以重复报名
			if ($now_activity['is_repeat_join'] == 0) { // 不可重复报名
				$where = array();
				$where['phone'] = $_POST['phone'];
				$where['activity_id'] = $activity_id;
				$exist_activity = $database_house_village_activity_apply->where($where)->count();
				if ($exist_activity) {
					exit(json_encode(array('status'=>0,'msg'=>'您已报名，本活动不可重复报名！')));
				}
			}

			$result = $database_house_village_activity_apply->house_village_activityapply_add($_POST);
			exit(json_encode($result));
		}else{
			if(time() > $now_activity['apply_end_time']+86400){
				$this->error_tips('活动已截止！');
			}

			if($now_activity['is_full']){
				$this->error_tips('活动人数已满！');
			}
			$this->display();
		}
	}
	public function chk_village_express_info(){
		$id = $_GET['id'] + 0;
		if(!$id){
			$this->check_ajax_error_tips('传递参数有误！');
		}

		$database_house_village_express = D('House_village_express');
		$where['id'] = $id;

		if(IS_POST){
			if(empty($this->user_session)){
				$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
			}

			$where['uid'] = $this->user_session['uid'];
			$result = $database_house_village_express->house_village_express_edit($where,$_POST);
			if(!$result){
				exit(json_encode(array('status'=>$result['status'],'msg'=>'数据处理有误！')));
			}else{
				exit(json_encode(array('status'=>$result['status'],'msg'=>$result['msg'])));
			}
		}else{
			$detail = $database_house_village_express->house_village_express_detail($where);
			if(!$detail){
				$this->check_ajax_error_tips('数据处理有误！');
			}else{
				$detail = $detail['detail'];
				$this->assign('detail' , $detail);
				$now_village = $this->get_village($detail['village_id']);
				$this->assign('now_village',$now_village);
			}

			$this->display();
		}
	}
	
	#计算是否使用积分 - wangdong
	public function ajax_integral_info($total_price ,$use_max_integral_percentage , $use_max_integral_num , $moneytointegral , $user_score_count){
		
		/****************************************************************
			$total_price 总价格	
			$use_max_integral_percentage 使用最大积分占总额百分比
			$use_max_integral_num 允许使用的最大积分
			$moneytointegral 多少积分抵扣一元
			$user_score_count 用户总共多少积分
		****************************************************************/
		if ($user_score_count<=0) {
			$property_info['use_total_score_count'] = 0;
			$property_info['use_total_money'] = 0;			
			return $property_info;
		}
		if($use_max_integral_percentage > 0){ //使用最大百分比
						
			//得到最大抵扣金额
			$max_use_price = sprintf("%.2f",$total_price * $use_max_integral_percentage / 100);
			
			//计算可以使用多少积分
			$use_total_score_count = floor($max_use_price * $moneytointegral);
			
			//这里计算用户应该支出的积分  和 应该抵扣的钱  和 要支付的钱
			if($user_score_count < $use_total_score_count){
				
				$property_info['use_total_money'] =sprintf("%.2f",$user_score_count/$moneytointegral);
				$property_info['use_total_score_count'] = $property_info['use_total_money'] * $moneytointegral;
				
			}else{
				$property_info['use_total_money'] = $max_use_price;
				$property_info['use_total_score_count'] = $use_total_score_count;
				
			}
			
				
		}elseif($use_max_integral_num > 0){ //使用最大积分
			
			if($use_max_integral_num < $user_score_count){
				$property_info['use_total_money'] = sprintf("%.2f",$use_max_integral_num / $moneytointegral);
				$property_info['use_total_score_count'] = $property_info['use_total_money'] * $moneytointegral;
			}else{
				$property_info['use_total_money'] = sprintf("%.2f",$user_score_count/$moneytointegral);
				$property_info['use_total_score_count'] = $property_info['use_total_money'] * $moneytointegral;
			}
			
		}else{ // 否则不计算
			$property_info['use_total_score_count'] = 0;
			$property_info['use_total_money'] = 0;	
			
		}
		return $property_info;
		
	}
	
	# 积分和扣费展示 - wangdong update
	public function ajax_get_presented_property_month(){
		if(IS_AJAX){
			$id = $_POST['id'] + 0;
			if(!$id){
				exit(json_encode(array('status'=>0,'msg'=>'传递参数有误！')));
			}

			$database_house_village_property = D('House_village_property');
			$where['id'] = $id;
			$property_info = $database_house_village_property->where($where)->find();

			if(!empty($property_info)){
				$database_house_village_user_bind = D('House_village_user_bind');
				$database_house_village_floor = D('House_village_floor');
				$bind_info = $database_house_village_user_bind->get_one_by_bindId($_SESSION['now_village_bind']['pigcms_id']);
				$floor_where['floor_id'] = $bind_info['floor_id'];
				$floor_where['status'] = 1;
				$floor_info = $database_house_village_floor->house_village_floor_detail($floor_where);
				$floor_info = $floor_info['detail'];

				if( !isset($floor_info['property_fee']) ||($floor_info['property_fee'] == '0.00')){
					$database_house_village = D('House_village');
					$now_village = $database_house_village->get_one($property_info['village_id']);
					$property_fee = $now_village['property_price'];
				}else{
					$property_fee = $floor_info['property_fee'];
				}
				
				$total_price = floatval($property_fee) * floatval($bind_info['housesize']) * floatval($property_info['property_month_num']);
				
				//计算物业费和抵扣金额
				//判断是否允许使用积分
				$village_config = D('House_village_config')->where(array('village_id'=>$floor_info['village_id']))->find();
				
				//首先得到 积分比例 和 用户总积分
				$system_config = D('Config')->get_config();
				
				//得到多少积分抵扣一元
				$moneytointegral = $system_config['user_score_use_percent'];
				//得到用户总积分
				$userinfo =  D('User')->get_user($this->user_session['uid']);
				$user_score_count = $userinfo['score_count'];
				
				//第一步 判断是否允许使用积分 (注意 这里是针对设置了小区本身的缴费配置)
				if($village_config['village_pay_use_integral']==1){ //允许使用积分 针对小区开启
				
					if($village_config['village_pay_owe_use_integral']==1){ //欠缴物业费允许使用积分
						
						$property_info_arr = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
						
					}else{ // 欠缴物业费不允许使用积分情况
						
						//首先计算缴费月份 并且得到是否欠缴物业费

						$database_house_village_property_paylist = D('House_village_property_paylist');
						$pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$this->village_bind['pigcms_id'],'uid'=>$this->user_session['uid']))->order('add_time asc')->select();
	
						if(!empty($pay_list)){
							$start_pay_info = reset($pay_list);
							$end_pay_info = end($pay_list);
							if($start_pay_info && $end_pay_info){
								
								//这里判断是否欠缴物业费
								if($end_pay_info['end_time'] > time()){
									
									$property_info_arr = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);

								}else{
								
									$date1 = explode("-",date("Y-m-d"));
									$date2 = explode("-",date("Y-m-d" , $end_pay_info['end_time']));
									$time1 = strtotime(date("Y-m-d"));
  									$time2 = strtotime(date("Y-m-d" , $end_pay_info['end_time']));
									$months =abs($date1[0]-$date2[0])*12;
									if($time1 > $time2){
										$owe_mouth = $months+$date1[1]-$date2[1];
									}else{
										$owe_mouth =  $months+$date2[1]-$date1[1];
									}
									
									if($owe_mouth >= floatval($property_info['property_month_num'])){
										$property_info_arr['use_total_score_count'] = 0;
										$property_info_arr['use_total_money'] = 0;		
									}else{
										
										$now_total_integral_money = $total_price - floatval($property_fee) * floatval($bind_info['housesize']) * $owe_mouth;
										
										$property_info_arr = $this->ajax_integral_info($now_total_integral_money ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);

									}
									
								}

							}else{
								$property_info_arr = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
							}
						}else{ // 第一次缴物业费 就不存在欠费
							$property_info_arr = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
							
						}
							 
						
					}
				
				}elseif($village_config['village_pay_use_integral']==0){ // 继承系统设置
				
					if($system_config['village_pay_use_integral']==1){
						
						if($system_config['village_owe_pay_use_integral']==1){ //欠缴物业费允许使用积分
						
							$property_info_arr = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
							
						}else{ // 欠缴物业费不允许使用积分情况
							
							//首先计算缴费月份 并且得到是否欠缴物业费
	
							$database_house_village_property_paylist = D('House_village_property_paylist');
							$pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$this->village_bind['pigcms_id'],'uid'=>$this->user_session['uid']))->order('add_time asc')->select();
		
							if(!empty($pay_list)){
								$start_pay_info = reset($pay_list);
								$end_pay_info = end($pay_list);
								if($start_pay_info && $end_pay_info){
									
									//这里判断是否欠缴物业费
									if($end_pay_info['end_time'] > time()){
										
										$property_info_arr = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
	
									}else{
									
										$date1 = explode("-",date("Y-m-d"));
										$date2 = explode("-",date("Y-m-d" , $end_pay_info['end_time']));
										$time1 = strtotime(date("Y-m-d"));
										$time2 = strtotime(date("Y-m-d" , $end_pay_info['end_time']));
										$months =abs($date1[0]-$date2[0])*12;
										if($time1 > $time2){
											$owe_mouth = $months+$date1[1]-$date2[1];
										}else{
											$owe_mouth =  $months+$date2[1]-$date1[1];
										}
										
										if($owe_mouth >= floatval($property_info['property_month_num'])){
											$property_info_arr['use_total_score_count'] = 0;
											$property_info_arr['use_total_money'] = 0;		
										}else{
											
											$now_total_integral_money = $total_price - floatval($property_fee) * floatval($bind_info['housesize']) * $owe_mouth;
											
											$property_info_arr = $this->ajax_integral_info($now_total_integral_money ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
	
										}
										
									}
	
								}else{
									$property_info_arr = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
								}
							}else{ // 第一次缴物业费 就不存在欠费
								$property_info_arr = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
								
							}
								 
							
						}
							
					}else{
						
						$property_info_arr['use_total_score_count'] = 0;
						$property_info_arr['use_total_money'] = 0;	
							
					}
						
				}else{
					$property_info_arr['use_total_score_count'] = 0;
					$property_info_arr['use_total_money'] = 0;	
					
				}
				
				$property_info['total_price'] = $total_price;
				$property_info['use_total_score_count'] = $property_info_arr['use_total_score_count'];
				$property_info['use_total_money'] = $property_info_arr['use_total_money'];
				
				exit(json_encode(array('status'=>1,'property_info'=>$property_info)));
			}else{
				exit(json_encode(array('status'=>0,'property_info'=>$property_info)));
			}
		}else{
			$this->error_tips('访问页面有误！~~~');
		}
	}

	public function ajax_diy_get_presented_property_month(){
		if(IS_AJAX){
			$diy_propertyt_month_num = $_POST['diy_propertyt_month_num'] + 0;
			if(!is_int($diy_propertyt_month_num)){
				exit(json_encode(array('status'=>0,'msg'=>'月份必须为整数！')));
			}

			$database_house_village_property = D('House_village_property');
			$village_id =  $_POST['village_id'] + 0;
			$where['village_id'] = $village_id;
			$where['status'] = 1;
			$property_list = $database_house_village_property->house_village_proerty_page_list($where,true,'property_month_num desc',99999);

			$database_house_village_user_bind = D('House_village_user_bind');
			$database_house_village_floor = D('House_village_floor');
			$bind_info = $database_house_village_user_bind->get_one_by_bindId($_SESSION['now_village_bind']['pigcms_id']);

			$floor_where['floor_id'] = $bind_info['floor_id'];
			$floor_where['status'] = 1;
			$floor_info = $database_house_village_floor->house_village_floor_detail($floor_where);
			$floor_info = $floor_info['detail'];

			if(($floor_info['property_fee'] == '0.00') || (!isset($floor_info['property_fee']))){
				$database_house_village = D('House_village');
				$now_village = $database_house_village->get_one($village_id);
				$property_fee = $now_village['property_price'];
			}else{
				$property_fee = $floor_info['property_fee'];
			}

			$total_price = floatval($property_fee) * floatval($bind_info['housesize']) * floatval($diy_propertyt_month_num);
			
				
			//计算物业费和抵扣金额
			//判断是否允许使用积分
			$village_config = D('House_village_config')->where(array('village_id'=>$floor_info['village_id']))->find();
			
			//首先得到 积分比例 和 用户总积分
			$system_config = D('Config')->get_config();
			
			//得到多少积分抵扣一元
			$moneytointegral = $system_config['user_score_use_percent'];
			//得到用户总积分
			$userinfo =  D('User')->get_user($this->user_session['uid']);
			$user_score_count = $userinfo['score_count'];
			
			//第一步 判断是否允许使用积分 (注意 这里是针对设置了小区本身的缴费配置)
			if($village_config['village_pay_use_integral']==1){ //允许使用积分 针对小区开启

				if($village_config['village_pay_owe_use_integral']==1){ //欠缴物业费允许使用积分
					
					$property_info = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
					
				}else{ // 欠缴物业费不允许使用积分情况
					
					//首先计算缴费月份 并且得到是否欠缴物业费

					$database_house_village_property_paylist = D('House_village_property_paylist');
					$pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$this->village_bind['pigcms_id'],'uid'=>$this->user_session['uid']))->order('add_time asc')->select();

					if(!empty($pay_list)){
						$start_pay_info = reset($pay_list);
						$end_pay_info = end($pay_list);
						if($start_pay_info && $end_pay_info){
							
							//这里判断是否欠缴物业费
							if($end_pay_info['end_time'] > time()){
								
								$property_info = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);

							}else{
							
								$date1 = explode("-",date("Y-m-d"));
								$date2 = explode("-",date("Y-m-d" , $end_pay_info['end_time']));
								$time1 = strtotime(date("Y-m-d"));
								$time2 = strtotime(date("Y-m-d" , $end_pay_info['end_time']));
								$months =abs($date1[0]-$date2[0])*12;
								if($time1 > $time2){
									$owe_mouth = $months+$date1[1]-$date2[1];
								}else{
									$owe_mouth =  $months+$date2[1]-$date1[1];
								}
								
								if($owe_mouth >= floatval($property_info['property_month_num'])){
									$property_info['use_total_score_count'] = 0;
									$property_info['use_total_money'] = 0;		
								}else{
									
									$now_total_integral_money = $total_price - floatval($property_fee) * floatval($bind_info['housesize']) * $owe_mouth;
									
									$property_info = $this->ajax_integral_info($now_total_integral_money ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);

								}
								
							}

						}else{
							$property_info = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
						}
					}else{ // 第一次缴物业费 就不存在欠费
						$property_info = $this->ajax_integral_info($total_price ,$village_config['use_max_integral_percentage'] , $village_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
						
					}
						 
					
				}
			
			}elseif($village_config['village_pay_use_integral']==0){ // 继承系统设置
			
				if($system_config['village_pay_use_integral']==1){
					
					if($system_config['village_owe_pay_use_integral']==1){ //欠缴物业费允许使用积分
					
						$property_info = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
						
					}else{ // 欠缴物业费不允许使用积分情况
						
						//首先计算缴费月份 并且得到是否欠缴物业费

						$database_house_village_property_paylist = D('House_village_property_paylist');
						$pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$this->village_bind['pigcms_id'],'uid'=>$this->user_session['uid']))->order('add_time asc')->select();
	
						if(!empty($pay_list)){
							$start_pay_info = reset($pay_list);
							$end_pay_info = end($pay_list);
							if($start_pay_info && $end_pay_info){
								
								//这里判断是否欠缴物业费
								if($end_pay_info['end_time'] > time()){
									
									$property_info = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);

								}else{
								
									$date1 = explode("-",date("Y-m-d"));
									$date2 = explode("-",date("Y-m-d" , $end_pay_info['end_time']));
									$time1 = strtotime(date("Y-m-d"));
									$time2 = strtotime(date("Y-m-d" , $end_pay_info['end_time']));
									$months =abs($date1[0]-$date2[0])*12;
									if($time1 > $time2){
										$owe_mouth = $months+$date1[1]-$date2[1];
									}else{
										$owe_mouth =  $months+$date2[1]-$date1[1];
									}
									
									if($owe_mouth >= floatval($property_info['property_month_num'])){
										$property_info['use_total_score_count'] = 0;
										$property_info['use_total_money'] = 0;		
									}else{
										
										$now_total_integral_money = $total_price - floatval($property_fee) * floatval($bind_info['housesize']) * $owe_mouth;
										
										$property_info = $this->ajax_integral_info($now_total_integral_money ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);

									}
									
								}

							}else{
								$property_info = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
							}
						}else{ // 第一次缴物业费 就不存在欠费
							$property_info = $this->ajax_integral_info($total_price ,$system_config['use_max_integral_percentage'] , $system_config['use_max_integral_num'] , $moneytointegral , $user_score_count);
							
						}
							 
						
					}
						
				}else{
					
					$property_info['use_total_score_count'] = 0;
					$property_info['use_total_money'] = 0;	
						
				}
					
			}else{
				$property_info['use_total_score_count'] = 0;
				$property_info['use_total_money'] = 0;	
				
			}
			
			
			if($property_list['list']['list']){
				foreach($property_list['list']['list'] as $property){
					if($diy_propertyt_month_num / $property['property_month_num'] > 1){
						if($property['diy_type'] == 0){
							exit(json_encode(array('status'=>1,'max_presented_property_month'=>$property['presented_property_month_num'] * intval($diy_propertyt_month_num / $property['property_month_num']),'total_price'=>$total_price,'diy_type'=>0,'property_id'=>$property['id'],'use_total_score_count'=>$property_info['use_total_score_count'],'use_total_money'=>$property_info['use_total_money'])));
						}else{
							exit(json_encode(array('status'=>1,'diy_content'=>$property['diy_content'],'total_price'=>$total_price,'diy_type'=>1,'property_id'=>$property['id'],'use_total_score_count'=>$property_info['use_total_score_count'],'use_total_money'=>$property_info['use_total_money'])));
						}
					}elseif($diy_propertyt_month_num >= $property['property_month_num']){
						if($property['diy_type'] == 0){
							exit(json_encode(array('status'=>1,'max_presented_property_month'=>$property['presented_property_month_num'],'total_price'=>$total_price,'diy_type'=>0,'property_id'=>$property['id'],'use_total_score_count'=>$property_info['use_total_score_count'],'use_total_money'=>$property_info['use_total_money'])));
						}else{
							exit(json_encode(array('status'=>1,'diy_content'=>$property['diy_content'],'total_price'=>$total_price,'diy_type'=>1,'property_id'=>$property['id'],'use_total_score_count'=>$property_info['use_total_score_count'],'use_total_money'=>$property_info['use_total_money'])));
						}
					}
				}
				
				exit(json_encode(array('status'=>1,'max_presented_property_month'=>0,'total_price'=>$total_price,'diy_type'=>0,'use_total_score_count'=>$property_info['use_total_score_count'],'use_total_money'=>$property_info['use_total_money'])));
			}else{
				exit(json_encode(array('status'=>1,'max_presented_property_month'=>0,'total_price'=>$total_price,'diy_type'=>0,'use_total_score_count'=>$property_info['use_total_score_count'],'use_total_money'=>$property_info['use_total_money'])));
			}
		}else{
			$this->error_tips('访问页面有误！~~~');
		}
	}
	/*     * json 格式封装函数* */
	private function dexit($data = '') {
		if (is_array($data)) {
			echo json_encode($data);
		} else {
			echo $data;
		}
		exit();
	}
	protected function get_user_village_info($bind_id){
		$database_house_village_user_bind = D('House_village_user_bind');
		$now_user_info = $database_house_village_user_bind->get_one_by_bindId($bind_id);
		if(empty($now_user_info)){
			$this->check_ajax_error_tips('您不是该小区业主',U('bind_village',array('village_id'=>$_GET['village_id'])));
		}

		$where['parent_id|pigcms_id'] = $bind_id;
		$where['uid'] = $this->user_session['uid'];
		$where['village_id'] = $_GET['village_id'] + 0;
		$house_village_user_bind_count = $database_house_village_user_bind->where($where)->count();
		if(!$house_village_user_bind_count){
			redirect(U('bind_village',array('village_id'=>$_GET['village_id'])));
		}

		$this->assign('now_user_info',$now_user_info);
		return $now_user_info;
	}
	protected function check_ajax_error_tips($err_tips,$err_url=''){
		if(I('app_version') && IS_POST){
			if($err_url){
				$this->error_tips($err_tips,$err_url);
			}else{
				$this->error_tips($err_tips);
			}
		}else{
			if(IS_POST){
				$this->header_json();
				echo json_encode(array('err_code'=>-1,'err_msg'=>$err_tips,'err_url'=>$err_url));
				exit();
			}else{
				if($err_url){
					$this->error_tips($err_tips,$err_url);
				}else{
					$this->error_tips($err_tips);
				}
			}
		}

	}
	protected function get_village($village_id){
		$now_village = D('House_village')->get_one($village_id);
		if(empty($now_village)){
			$this->error_tips('当前访问的小区不存在或未开放');
		}
		$this->assign('now_village',$now_village);
		return $now_village;
	}
	private function getHasConfig($village_id,$field){
		$database_house_village = D('House_village');
		$house_village_info = $database_house_village->get_one($village_id,$field);
		$config_info = $house_village_info[$field];
		return $config_info;
	}
	public function village_comment(){
		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
		$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'repair';
		$repair_detail = D('House_village_repair_list')->field(true)->where(array('pigcms_id' => $pigcms_id, 'uid' => $this->user_session['uid']))->find();
		if(empty($repair_detail)){
			$this->check_ajax_error_tips('当前报修内容不存在');
		}
		if ($repair_detail['status'] < 3) {
			$this->check_ajax_error_tips('还未处理不能评论');
		}
		if ($repair_detail['status'] == 4) {
			$this->check_ajax_error_tips('已评论');
		}
		if ($repair_detail['wid']) $this->worker($repair_detail['wid']);
		if (IS_POST) {
			if(empty($_POST['comment'])){
				$this->check_ajax_error_tips('请填写评论内容');
			}
			$inputimg = isset($_POST['inputimg']) ? $_POST['inputimg'] :'';
			$picArr = array();
			if(!empty($inputimg)){
				foreach($inputimg as $imgv){
					$imgv = str_replace('/upload/house/','',$imgv);
					$picArr[] = $imgv;
				}
			}
			$data_repair['comment_pic'] = implode('|', $picArr);
			$data_repair['comment'] = htmlspecialchars($_POST['comment']);
			$data_repair['comment_time'] = $_SERVER['REQUEST_TIME'];
			$data_repair['score'] = intval($_POST['score']);
			$data_repair['status'] = 4;


			if($repair_id = D('House_village_repair_list')->where(array('pigcms_id' => $pigcms_id, 'uid' => $this->user_session['uid']))->save($data_repair)){
				D('House_worker')->add_score($repair_detail['wid'], $data_repair['score']);
				D('House_village_repair_log')->add_log(array('status' => 4, 'repair_id' => $pigcms_id));
				$this->header_json();
				echo json_encode(array('err_code' => 1,'url' => U('House/village_my_'.$type.'_detail',array('village_id' => $repair_detail['village_id'], 'id' => $pigcms_id))));
				exit();
			}else{
				$this->check_ajax_error_tips('提交失败，请重试');
			}
		}
		$this->assign('now_village',$now_village);

		$this->display();
	}
	public function tt(){
		D('House_village_repair_list')->notice_work();
	}
	public function car_apply_json(){
		$_POST['uid']	=	$this->user_session['uid'];
		$_POST['add_time']	=	$_SERVER['REQUEST_TIME'];
		$add	=	M('User_authentication_car')->data($_POST)->add();
		if($add){
			$this->returnCode(0,U('car_apply'));
		}else{
			$this->returnCode('20046028');
		}
	}

	function ajax_check_receive(){
		if(IS_AJAX){
			$order_id = $_POST['order_id'] + 0;
			if(!$order_id){
				exit(json_encode(array('status'=>0,'msg'=>"传递参数有误！")));
			}

			$database_house_village_pay_order = D('House_village_pay_order');
			$where['order_id'] = $order_id;

			$data['status'] = 1;
			$data['check_time'] = time();
			$result = $database_house_village_pay_order->where($where)->data($data)->save();
			if($result){
				exit(json_encode(array('status'=>1,'msg'=>'确认领取成功！')));
			}else{
				exit(json_encode(array('status'=>0,'msg'=>'确认领取失败！')));
			}
		}else{
			$this->error_tips('访问页面有误！~~~');
		}
	}


	//	我的实名认证
	public function authentication(){
		$uid	=	$this->user_session['uid'];
		$find	=	M('User_authentication')->field(true)->where(array('uid'=>$uid))->find();
		$this->assign('find',$find);
		$this->display();
	}


	public function my_village(){
		$village_list = D('House_village_user_bind')->where(array('status'=>1,'uid'=>$this->user_session['uid']))->group('village_id')->select();

		$condition_table = array(C('DB_PREFIX') . 'house_village_user_bind' => '`hvub`', C('DB_PREFIX') . 'house_village' => '`hv`');
		$condition_where = "`hvub`.`uid`=" . $this->user_session['uid'] . " AND `hvub`.`village_id`=`hv`.`village_id`";
		$hvub_field = array('`hvub`.*');
		$hv_field = array('`hv`.`village_name`');
		$condition_field = array_merge($hvub_field , $hv_field);
		$list = D('')->table($condition_table)->where($condition_where)->field($condition_field)->select();

		foreach($village_list as $Key=>$village){
			foreach($list as $row){
				if($village['village_id'] == $row['village_id']){
					$village_list[$Key]['village_name'] = $row['village_name'];
					$village_list[$Key]['bind_list'][] = $row;
				}
			}
		}

		$this->assign('village_list',$village_list);
		$this->display();
	}

	public function empty_village_list(){
		$database_house_village = D('House_village');
		$village_where['status'] = 1;
		$village_list = $database_house_village->where($village_where)->select();
		$this->assign('village_list' , $village_list);
		$this->display();
	}
	
	//搜索显示小区列表 - wangdong
	public function ajax_empty_village_list(){
		
		$village_key = I('post.village_key');
		if(!empty($village_key)){
			$village_where['village_name'] = array('like' , '%'.$village_key.'%');
		}
		
		$database_house_village = D('House_village');
		$village_where['status'] = 1;
		
		$village_list = $database_house_village->where($village_where)->select();
		echo json_encode($village_list);
		exit;
		
	}
	
	//根据小区 查询单元 - wangdong
	public function empty_village_unit_list(){
		
		$village_id = $_GET['village_id'] + 0;
		
		if(empty($village_id)){
			
			$this->error_tips("参数错误");
				
		}
		
		$database_House_village = D('House_village');
		
		$database_house_village_floor = D('House_village_floor');
		
		//查询小区信息
		$village_info = $database_House_village->get_village_info($village_id);
		
		$this->assign("village_info" , $village_info);
		
		//查询小区单元信息
		$unit_list = $database_house_village_floor->get_unit_list($village_id);
		
		$this->assign('unit_list' , $unit_list);
		
		$this->display();	
	}
	

	//小区单元房间列表 - wangdong
	public function empty_village_room_list(){
		$floor_id = $_GET['floor_id'] + 0;
		if(!$floor_id){
			$this->error_tips('传递参数有误！');
		}

		$database_house_village_user_vacancy = D('House_village_user_vacancy');
		$database_house_village = D('House_village');
		$database_house_village_floor = D('House_village_floor');
		
		//查询单元信息 小区信息
		$find_village_floor = $database_house_village_floor->get_unit_find($floor_id , 'village_id,floor_name,floor_layer');
		$find_village = $database_house_village->get_village_info($find_village_floor['village_id'] , 'village_name,village_address');
		$find_info = array(
			'village_name'    =>  $find_village['village_name'],
			'village_address' =>  $find_village['village_address'],
			'floor_name'      =>  $find_village_floor['floor_name'],
			'floor_layer'     =>  $find_village_floor['floor_layer'],
			'village_id'     =>  $find_village_floor['village_id']
		);
		$this->assign("find_info" , $find_info);

		//$vacancy_where['status'] = array('in' , '1,3');
		//$vacancy_where['is_del'] = 0;
		$vacancy_where['floor_id'] = $floor_id;

		$vacancy_list = $database_house_village_user_vacancy->house_village_user_vacancy_page_list_room($vacancy_where , true , 'pigcms_id desc' , 9999999);
		$vacancy_list = $vacancy_list['result']['list'];

		$this->assign('vacancy_list' , $vacancy_list);
		$this->display();
	}
	
	
	//业主审核亲戚/租客
	public function village_examine(){
		
		$status = $_GET['status'] + 0;
		$pigcms_id = $_GET['pigcms_id'] + 0;
		$village_id = $_GET['village_id'] + 0;
		$vacancy_id = $_GET['vacancy_id'] + 0;
		if(empty($pigcms_id) || empty($village_id) || empty($vacancy_id)){
			$this->error_tips('传递参数有误！');
		}
		$database_house_village_user_bind = D('House_village_user_bind');
		$where['pigcms_id'] = $pigcms_id;
		$where['village_id'] = $village_id;
		$where['vacancy_id'] = $vacancy_id;
		$update = $database_house_village_user_bind->where($where)->save(array('status'=>$status,'pass_time'=>time()));
		if($update){
			$this->success_tips('审核完成！' , U('my_village_list'));
		}else{
			$this->error_tips('审核失败！' , U('my_village_list'));
		}
			
	}
	
	//绑定房间 news - wangdong
	
	public function empty_village_room_info(){
		
		$database_house_village_user_bind = D('House_village_user_bind');
		$database_house_village_user_vacancy = D('House_village_user_vacancy');
		
		$database_house_village = D('House_village');
		$database_house_village_floor = D('House_village_floor');

		$pigcms_id = $_GET['pigcms_id'] + 0;
		$is_vacancy = $_GET['is_vacancy'] + 0;
		$bind_where['pigcms_id'] = $pigcms_id;
		
		if(!$pigcms_id){
			$this->error_tips('传递参数有误！');
		}
		
		//查询 房间信息 单元信息 小区信息
		$find_village_room = $database_house_village_user_vacancy->get_find_room_info($pigcms_id , "floor_id,village_id,layer,room,housesize");
		// dump($find_village_room);
		$find_village_floor = $database_house_village_floor->get_unit_find($find_village_room['floor_id'] , 'village_id,floor_name,floor_layer');
		$find_village = $database_house_village->get_village_info($find_village_room['village_id'] , 'village_name,village_address');
		$find_info = array(
			'vacancy_layer'         =>  $find_village_room['layer'],
			'vacancy_floor_id'      =>  $find_village_room['floor_id'],
			'vacancy_room'          =>  $find_village_room['room'],
			'vacancy_housesize'          =>  $find_village_room['housesize'],
			'village_name'          =>  $find_village['village_name'],
			'village_address'       =>  $find_village['village_address'],
			'floor_name'            =>  $find_village_floor['floor_name'],
			'floor_layer'           =>  $find_village_floor['floor_layer'],
			'village_id'            =>  $find_village_floor['village_id']
		);
		
		$this->assign("find_info" , $find_info);
		
		//查询有无业主
		$room_true_find = $database_house_village_user_vacancy->get_find_room_count($pigcms_id);
		$this->assign("room_true_find" , $room_true_find);
		
		//print_r($this->user_session);
		$this->assign('userArr' , $this->user_session);
		
		
		
		//$bind_where['is_del'] = 0;
		$bind_info = $database_house_village_user_vacancy->where($bind_where)->find(); // 房间信息
		
		
		if(!$bind_info){
			$this->error_tips('房间不存在。');
		}

		if(IS_POST){
			$type = $_POST['type'] + 0;
			$phone = $_POST['phone'];
			$name = trim($_POST['name']);
			$master_phone = $_POST['master_phone'];
			$chk_phone = "";
			if(!empty($master_phone)){
				for($i=0;$i<4;$i++){
					$chk_phone .= $master_phone[$i];	
				}	
			}
			
			//$chk_phone = $_POST['chk_phone'] + 0;

			if(!$this->config['international_phone'] && !preg_match('/^[0-9]{11}$/',$phone)){
				$this->error_tips('请输入有效的手机号。');
			}

			if ($_POST['phone']) {
				$uid = D('User')->get_user_by_phone($_POST['phone']);
			}
			
			if($uid > 0){
				$bind_data['uid'] = $uid;
			}else{
				$bind_data['uid'] = 0;
			}

			$bind_data['housesize'] = $bind_info['housesize'] + 0;
			$bind_data['park_flag'] = $_POST['park_flag'] + 0;
			if($type == 0){
				$bind_data['name'] = $name;
				$bind_data['phone'] = $phone;
				$bind_data['memo'] = $_POST['memo'];
				$bind_data['type'] = 0;
				$bind_data['status'] = 2;
				$bind_data['application_time'] = time();
                $bind_info = $database_house_village_user_vacancy->where(array('pigcms_id' => $pigcms_id, 'status' => array('gt', 1), 'is_del' => 0))->find();
                if (!empty($bind_info['uid'])) { 
                    if ($bind_info['status'] == 2) {
                        $this->error_tips('已经有用户申请业主！');
                    } else {
                        $this->error_tips('已经存在业主！');
                    }
                }

                // 手机号不能与家属手机号一样 
				$bind_condition['vacancy_id'] = $pigcms_id;
				$bind_condition['phone'] = $phone;
				$bind_condition['type'] = array('in' , '1,2');;
				$bind_condition['status'] = array('in' , '1,2');
			
				$family_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
				if($family_bind_info){
					$this->error_tips('该手机号已绑定或已申请绑定此房间', U('my_village_list'));
				}

				$insert_id = $database_house_village_user_vacancy->data($bind_data)->where($bind_where)->save();
				if($insert_id){
					$this->success_tips('提交审核成功！' , U('my_village_list'));
				}else{
					$this->error_tips('提交审核失败！');
				}
			}elseif($type == 1){//家人
 				// 已经存在业主 存在业主验证手机号后四位
				if (!empty($bind_info['uid']) && $bind_info['status'] == 3 && $bind_info['type'] == 0) {
                    if(mb_substr($bind_info['phone'],-4) != $chk_phone){
						$this->error_tips('业主手机号后四位输入不正确。');
					}
				}

				// 房屋信息
				$vacancy_condition['pigcms_id'] = $pigcms_id;
				//$vacancy_condition['is_del'] = 0;
				
				$bind_info = $database_house_village_user_vacancy->where($vacancy_condition)->find();
				
				if($phone == $bind_info['phone']){
					if ($bind_info['status']==2) {
						$this->error_tips('该手机号已提交申请');
					}else{
						$this->error_tips('手机号不能与业主一致');
					}
				}

				// 验证 审核中或已审合通过
				$bind_condition['vacancy_id'] = $pigcms_id;
				$bind_condition['phone'] = $phone;
				$bind_condition['type'] = $type;
				$bind_condition['status'] = array('in' , '1,2');
			
				$family_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
				if($family_bind_info){
					$this->error_tips('已提交审请，请您耐心等待。' , U('my_village_list'));
				}
				
				//判断是否被拒绝 如果不被拒绝再提交的 应该删除之前拒绝的信息 重新添加
				$bind_condition_not['vacancy_id'] = $pigcms_id;
				$bind_condition_not['phone'] = $phone;
				$bind_condition_not['type'] = $type;
				$bind_condition_not['status'] = 0;
				$family_bind_info_not = $database_house_village_user_bind->where($bind_condition_not)->find();
				if($family_bind_info_not){
					$database_house_village_user_bind->where($bind_condition_not)->delete();	
				}

				// 单元信息
				$floor_condition['floor_id'] =  $bind_info['floor_id'];
				$floor_info = $database_house_village_floor->where($floor_condition)->find();

				// 新增数据
				$bind_data['village_id'] = $bind_info['village_id'];
				$bind_data['address']    = $floor_info['floor_layer'].$floor_info['floor_name'].$bind_info['layer'].$bind_info['room'];
				$bind_data['layer_num']  = intval($bind_info['layer']);
				$bind_data['room_addrss']    = intval($bind_info['room']);
				$bind_data['floor_id']   = $bind_info['floor_id'];
				$bind_data['vacancy_id']    =  $pigcms_id;
				$bind_data['housesize'] =  $bind_info['housesize'];
				$bind_data['park_flag'] =  $bind_info['park_flag'];
				$bind_data['name'] = $name;
				$bind_data['type'] = 1;
				$bind_data['status'] = 2;
				$bind_data['phone'] = $phone;
				$bind_data['add_time'] = time();
				$bind_data['usernum'] = rand(0,99999) . '-' . time();
				$bind_data['memo'] = $_POST['memo'];

				// 查询房主 
                $user_info = $database_house_village_user_bind->where(array('vacancy_id' => $pigcms_id,'type'=>0,'usernum'=>$bind_info['usernum']))->find();
                // var_dump($user_info);
                if ($user_info) {
                	$bind_data['parent_id'] = $user_info['pigcms_id']; 
                }else{
                	// 生成虚拟房主
                	$xuni_data = array();
					$xuni_data['village_id'] = $bind_info['village_id'];
					$xuni_data['address']    = $floor_info['floor_layer'].$floor_info['floor_name'].$bind_info['layer'].$bind_info['room'];
					$xuni_data['layer_num']  = intval($bind_info['layer']);
					$xuni_data['room_addrss']    = intval($bind_info['room']);
					$xuni_data['floor_id']   = $bind_info['floor_id'];
					$xuni_data['vacancy_id']    =  $pigcms_id;
					$xuni_data['housesize'] =  $bind_info['housesize'];
					$xuni_data['park_flag'] =  $bind_info['park_flag'];	
					$xuni_data['name'] = '';
					$xuni_data['type'] = 0;
					$xuni_data['status'] = 1;
					$xuni_data['phone'] = '';
					$xuni_data['add_time'] = time();
					$xuni_data['usernum'] = $bind_info['usernum'];
					$xuni_data['uid'] = 0;
					$xuni_id = $database_house_village_user_bind->data($xuni_data)->add();
                	$bind_data['parent_id'] = $xuni_id; 
                }
				// 这里应该是房主所在house_village_user_bind表中的ID
				// $info_parentid = $database_house_village_user_bind->field('pigcms_id')->where(array('vacancy_id'=>$pigcms_id,'type'=>0,'status'=>1))->find();
				
				
				$insert_id = $database_house_village_user_bind->data($bind_data)->add();
				if($insert_id){
					$this->success_tips('提交审核成功！' , U('my_village_list'));
				}else{
					$this->error_tips('提交审核失败！' , U('my_village_list'));
				}
				
			}elseif($type == 2){//租客
 				// 已经存在业主 存在业主验证手机号后四位
				if (!empty($bind_info['uid']) && $bind_info['status'] == 3 && $bind_info['type'] == 0) {
                    if(mb_substr($bind_info['phone'],-4) != $chk_phone){
						$this->error_tips('业主手机号后四位输入不正确。');
					}
				}
				
				// 房屋信息
				$vacancy_condition['pigcms_id'] = $pigcms_id;
				//$vacancy_condition['is_del'] = 0;
				$bind_info = $database_house_village_user_vacancy->where($vacancy_condition)->find();
				
				if($phone == $bind_info['phone']){
					if ($bind_info['status']==2) {
						$this->error_tips('该手机号已提交申请');
					}else{
						$this->error_tips('手机号不能与业主一致');
					}
				}
				
				// 验证 审核中或已审合通过
				$bind_condition['vacancy_id'] = $pigcms_id;
				$bind_condition['phone'] = $phone;
				$bind_condition['type'] = 2;
				$bind_condition['status'] = array('in' , '1,2');		
				$renter_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
				if($renter_bind_info){
					$this->error_tips('已提交审请，请您耐心等待。' , U('my_village_list'));
				}

				//判断是否被拒绝 如果不被拒绝再提交的 应该删除之前拒绝的信息 重新添加
				$bind_condition_not['vacancy_id'] = $pigcms_id;
				$bind_condition_not['phone'] = $phone;
				$bind_condition_not['type'] = 2;
				$bind_condition_not['status'] = 0;
				$renter_bind_info_not = $database_house_village_user_bind->where($bind_condition_not)->find();
				if($renter_bind_info_not){
					$database_house_village_user_bind->where($bind_condition_not)->delete();	
				}
				
				// 单元信息
				$floor_condition['floor_id'] =  $bind_info['floor_id'];
				$floor_info = $database_house_village_floor->where($floor_condition)->find();
				
				// 新增数据
				$bind_data['village_id'] = $bind_info['village_id'];
				$bind_data['address']    = $floor_info['floor_layer'].$floor_info['floor_name'].$bind_info['layer'].$bind_info['room'];
				$bind_data['layer_num']  = intval($bind_info['layer']);
				$bind_data['room_addrss']    = intval($bind_info['room']);
				$bind_data['floor_id']   = $bind_info['floor_id'];
				$bind_data['vacancy_id']    =  $pigcms_id;
				$bind_data['housesize'] =  $bind_info['housesize'];
				$bind_data['park_flag'] =  $bind_info['park_flag'];
				$bind_data['name'] = $name;
				$bind_data['type'] = 2;
				$bind_data['status'] = 2;
				$bind_data['phone'] = $phone;
				$bind_data['add_time'] = time();
				$bind_data['usernum'] = rand(0,99999) . '-' . time();
				$bind_data['memo'] = $_POST['memo'];

				// 查询房主 
                $user_info = $database_house_village_user_bind->where(array('vacancy_id' => $pigcms_id,'type'=>0,'usernum'=>$bind_info['usernum']))->find();
                if ($user_info) {
                	$bind_data['parent_id'] = $user_info['pigcms_id']; 
                }else{
                	// 生成虚拟房主
                	$xuni_data = array();
					$xuni_data['village_id'] = $bind_info['village_id'];
					$xuni_data['address']    = $floor_info['floor_layer'].$floor_info['floor_name'].$bind_info['layer'].$bind_info['room'];
					$xuni_data['layer_num']  = intval($bind_info['layer']);
					$xuni_data['room_addrss']    = intval($bind_info['room']);
					$xuni_data['floor_id']   = $bind_info['floor_id'];
					$xuni_data['vacancy_id']    =  $pigcms_id;
					$xuni_data['housesize'] =  $bind_info['housesize'];
					$xuni_data['park_flag'] =  $bind_info['park_flag'];	
					$xuni_data['name'] = '';
					$xuni_data['type'] = 0;
					$xuni_data['status'] = 1;
					$xuni_data['phone'] = '';
					$xuni_data['add_time'] = time();
					$xuni_data['usernum'] = $bind_info['usernum'];
					$xuni_data['memo'] = $_POST['memo'];
					$xuni_data['uid'] = 0;
					$xuni_id = $database_house_village_user_bind->data($xuni_data)->add();
                	$bind_data['parent_id'] = $xuni_id; 
                }


				// 这里应该是房主所在house_village_user_bind表中的ID
				// $info_parentid = $database_house_village_user_bind->field('pigcms_id')->where(array('vacancy_id'=>$pigcms_id,'type'=>0,'status'=>1))->find();
				// $bind_data['parent_id'] = $info_parentid['pigcms_id']; 
				
				$insert_id = $database_house_village_user_bind->data($bind_data)->add();
				if($insert_id){
					$this->success_tips('提交审核成功！' , U('my_village_list'));
				}else{
					$this->error_tips('提交审核失败！' , U('my_village_list'));
				}
				
			}elseif($type == 3){//替换业主
					$vacancy_condition['pigcms_id'] = $pigcms_id;
					$vacancy_condition['id_del'] = 0;
					
					$bind_info = $database_house_village_user_vacancy->where($vacancy_condition)->find();
					if($phone == $bind_info['phone']){
						$this->error_tips('手机号不能与业主一致');
					}

					$bind_condition['parent_id'] = $pigcms_id;
					$bind_condition['phone'] = $phone;
					$bind_condition['type'] = 3;
					$bind_condition['status'] = array('in' , '1,2');
					$owner_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
					if($owner_bind_info){
						$this->error_tips('已提交审请，请您耐心等待。' , U('my_village_list'));
					}
					
					$floor_condition['floor_id'] =  $bind_info['floor_id'];
					$floor_info = $database_house_village_floor->where($floor_condition)->find();
					
					$bind_data['village_id'] = $bind_info['village_id'];
					$bind_data['address']    = $floor_info['floor_layer'].$floor_info['floor_name'].$bind_info['layer'].$bind_info['room'];
					$bind_data['layer_num']  = intval($bind_info['layer']);
					$bind_data['room_addrss']    = intval($bind_info['room']);
					$bind_data['floor_id']   = $bind_info['floor_id'];
					$bind_data['vacancy_id']    =  $pigcms_id;
					$bind_data['housesize'] =  $bind_info['housesize'];
					$bind_data['park_flag'] =  $bind_info['park_flag'];
					
					
					//$bind_data = $bind_info;
					//unset($bind_data['pigcms_id']);
					$uid = D('User')->get_user_by_phone($_POST['phone']);
					if($uid > 0){
						$bind_data['uid'] = $uid;
					}else{
						$bind_data['uid'] = 0;
					}

					$bind_data['name'] = $name;
					$bind_data['type'] = 3;
					$bind_data['status'] = 2;
					$bind_data['phone'] = $phone;
					$bind_data['add_time'] = time();
					$bind_data['usernum'] = rand(0,99999) . '-' . time();
					// $info_parentid = $database_house_village_user_bind->field('pigcms_id,property_starttime,property_endtime')->where(array('vacancy_id'=>$pigcms_id,'type'=>array("in","0,3"),'status'=>1))->find();
					$info_parentid = $database_house_village_user_bind->field('pigcms_id,property_starttime,property_endtime')->where(array('vacancy_id'=>$pigcms_id,'type'=>array("in","0,3"),'status'=>1))->order('`type` ASC')->find();
					$bind_data['parent_id'] =  $info_parentid['pigcms_id'];
					$bind_data['property_starttime'] =  $info_parentid['property_starttime'];
					$bind_data['property_endtime'] =  $info_parentid['property_endtime'];
					$bind_data['memo'] = $_POST['memo'];
					$insert_id = $database_house_village_user_bind->data($bind_data)->add();
					//$insert_id = $database_house_village_user_bind->data($bind_data)->where($bind_where)->save();
					if($insert_id){
						$this->success_tips('提交审核成功！' , U('my_village_list'));
					}else{
						$this->error_tips('提交审核失败！' , U('my_village_list'));
					}
				
			}else{

			}
		}else{
			$this->assign('bind_info',$bind_info);
			$this->display();
		}
	}
	
	#用户解除绑定 房主/亲属/租客  -  wangdong
	
	public function ajax_user_unbind(){
		
		if(IS_AJAX){
			$pigcms_id = $_POST['pigcms_id'] + 0;
			$note      = trim($_POST['note']);
			if(!$pigcms_id){
				exit(json_encode(array('status'=>0,'msg'=>"传递参数有误！")));
			}
			$data['bind_id'] = $pigcms_id;
			$data['note'] = $note;
			$data['addtime'] = $data['edittime'] = time();
			$data['uid'] = $this->user_session['uid'];
			
			//查询bind信息
			$database_house_village_user_bind = D('House_village_user_bind');
			$database_house_village_user_unbind = D('House_village_user_unbind');
			
			$bind_info = $database_house_village_user_bind->where(array('pigcms_id'=>$pigcms_id , 'uid'=>$data['uid']))->find();
			if($bind_info){
				
				$unbind_status = $database_house_village_user_unbind->where(array('type'=>$bind_info['type'],'village_id'=>$bind_info['village_id'],'name'=>$bind_info['name'] ,'phone'=>$bind_info['phone'],'floor_id'=>$bind_info['floor_id'],'room_id'=>$bind_info['vacancy_id'],'uid'=>$data['uid'],'status'=>1))->count();
				if($unbind_status > 0){
					exit(json_encode(array('status'=>2,'msg'=>'信息已申请，等待审核！')));	
				}
				
				$data['name'] = $bind_info['name'];
				$data['phone'] = $bind_info['phone'];
				$data['type'] = $bind_info['type'];
				$data['room_id'] = $bind_info['vacancy_id'];
				$data['village_id'] = $bind_info['village_id'];	
				$data['floor_id'] = $bind_info['floor_id'];	
				$data['status'] = 1;
				
				$result = $database_house_village_user_unbind->data($data)->add();
				if($result){
					exit(json_encode(array('status'=>1,'msg'=>'申请解绑成功！')));
				}else{
					exit(json_encode(array('status'=>0,'msg'=>'申请解绑失败！')));
				}		
			}else{
				$this->error_tips('传递参数错误！~~~');	
			}
		}else{
			$this->error_tips('访问页面有误！~~~');
		}
			
	}
	
	#删除业主下面的租客/亲属
	public function ajax_delete_bind(){
		
		if(IS_AJAX){
			$data_pigcms_id = $_POST['data_pigcms_id'] + 0;
			$data_village_id = $_POST['data_village_id'] + 0;
			if(!$data_pigcms_id || !$data_village_id){
				exit(json_encode(array('status'=>0,'msg'=>"传递参数有误！")));
			}
			$where['pigcms_id'] = $data_pigcms_id;
			$where['village_id'] = $data_village_id;
			
			$database_house_village_user_bind = D('House_village_user_bind');
			
			$delete_id = $database_house_village_user_bind->where($where)->delete();
			if($delete_id){
				exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
			}else{
				exit(json_encode(array('status'=>0,'msg'=>'删除失败！')));
			}	
			
		}else{
			$this->error_tips('访问页面有误！~~~');
		}
			
	}
	
	
	
	public function bind_family(){
		$village_list = D('House_village_user_bind')->where(array('status'=>1,'uid'=>$this->user_session['uid']))->group('village_id')->select();

		$condition_table = array(C('DB_PREFIX') . 'house_village_user_bind' => '`hvub`', C('DB_PREFIX') . 'house_village' => '`hv`');
		$condition_where = "`hvub`.`uid`=" . $this->user_session['uid'] . " AND `hvub`.`village_id`=`hv`.`village_id` AND (`hvub`.`type` = 0 or `hvub`.`type` = 3) AND `hvub`.`parent_id` = 0";
		$hvub_field = array('`hvub`.*');
		$hv_field = array('`hv`.`village_name`');
		$condition_field = array_merge($hvub_field , $hv_field);
		$list = D('')->table($condition_table)->where($condition_where)->field($condition_field)->select();
		if(!$list){
			$this->error_tips('暂无信息！');
		}

		foreach($village_list as $Key=>$village){
			foreach($list as $row){
				if($village['village_id'] == $row['village_id']){
					$village_list[$Key]['village_name'] = $row['village_name'];
					$village_list[$Key]['bind_list'][] = $row;
				}
			}
		}

		$this->assign('village_list',$village_list);
		$this->display();
	}
	public function bind_family_info(){
		$condition_table = array(C('DB_PREFIX') . 'house_village_user_bind' => '`hvub`', C('DB_PREFIX') . 'house_village' => '`hv`');
		$condition_where = "`hvub`.`uid`=" . $this->user_session['uid'] . " AND `hvub`.`village_id`=`hv`.`village_id` AND `hvub`.`pigcms_id` = ".$_GET['pigcms_id'];
		$hvub_field = array('`hvub`.*');
		$hv_field = array('`hv`.`village_name`');
		$condition_field = array_merge($hvub_field , $hv_field);
		$info = D('')->table($condition_table)->where($condition_where)->field($condition_field)->find();

		$this->assign('info' , $info);
		$this->display();
	}
	public function ajax_bind_family(){
		if(IS_AJAX){
			$phone = $_POST['phone'];
			$pigcms_id = $_POST['pigcms_id'] + 0;
			if(!$phone){
				exit(json_encode(array('status'=>0,'msg'=>'传递参数有误~！')));
			}

			$database_user = D('User');
			$database_house_village_user_bind = D('House_village_user_bind');
			$bind_info = $database_house_village_user_bind->get_one_by_bindId($pigcms_id);

			if(!$bind_info){
				exit(json_encode(array('status'=>0,'msg'=>'业主信息不存在！')));
			}

			$uid = $database_user->get_user_by_phone($phone);
			if(!$uid){
				exit(json_encode(array('status'=>0,'msg'=>'手机号不是平台用户！')));
			}else{
				$now_user = $database_user->get_user($uid);
			}

			if($bind_info['phone'] == $phone){
				exit(json_encode(array('status'=>0,'msg'=>'提交手机号有误！')));
			}

			$bind_data = $bind_info;
			unset($bind_data['pigcms_id']);
			$bind_data['name'] = $now_user['nickname'];
			$bind_data['phone'] = $phone;
			$bind_data['add_time'] = time();
			$bind_data['usernum'] = rand(0,99999) . '-' . time();
			$bind_data['parent_id'] = $pigcms_id;
			$bind_data['type'] = 1;
			$bind_data['status'] = 2;

			$insert_id = $database_house_village_user_bind->data($bind_data)->add();
			if($insert_id){
				exit(json_encode(array('status'=>1,'msg'=>'提交审核成功！')));
			}else{
				exit(json_encode(array('status'=>0,'msg'=>'提交审核失败！')));
			}
		}else{
			$this->error_tips('访问页面有误！');
		}
	}
	public function bind_del(){
		if(IS_AJAX){
			$pigcms_id = $_POST['pigcms_id'] + 0;
			if(!$pigcms_id){
				$this->error_tips('传递参数有误！');
			}

			$database_house_village_user_bind = D('House_village_user_bind');
			$database_house_village_user_vacancy = D('House_village_user_vacancy');

			$where['pigcms_id'] = $pigcms_id;
			$result = $database_house_village_user_bind->where($where)->find();
			if(!$result){
				$this->error_tips('该信息不存在！');
			}

			$database_house_village_user_vacancy->where(array('usernum'=>$result['usernum']))->setField('status',4);

			$insert_id = $database_house_village_user_bind->where($where)->delete();
			if($insert_id){
				exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
			}else{
				exit(json_encode(array('status'=>0,'msg'=>'删除失败！')));
			}
		}else{
			$this->error_tips('访问页面有误！');
		}
	}

	//	我的实名认证提交
	public function authentication_json(){
		$where['uid']	=	$this->user_session['uid'];
		$find	=	M('User_authentication')->where($where)->find();
		$auth_data	=	array(
				'user_truename'			=>	$_POST['user_truename'],
				'user_id_number'		=>	$_POST['user_id_number'],
				'authentication_img'	=>	$_POST['authentication_img'],
				'authentication_back_img'=>	$_POST['authentication_back_img'],
				'hand_authentication'	=>	$_POST['hand_authentication'],
				'authentication_time'	=>	$_SERVER['REQUEST_TIME'],
				'examine_time'			=>	0,
				'authentication_status'	=>	0,
		);
		if($find){
			$user_authentication	=	M('User_authentication')->where($where)->data($auth_data)->save();
		}else{
			$auth_data['uid']	=	$this->user_session['uid'];
			$user_authentication	=	M('User_authentication')->data($auth_data)->add();
		}
		if(empty($user_authentication)){
			$this->returnCode('40000031');
		}else{
			$data['truename']	=	$_POST['user_truename'];
			$data['real_name']	=	2;
			$save	=	D('User')->scenic_save_user($where,$data);
			if($save){
				$_SESSION['user']['real_name']	=	2;
				$_SESSION['user']['truename']	=	$data['truename'];
			}
		}
		$url	=	U('authentication_index',array('village_id'=>$_POST['village_id']));
		$this->returnCode(0,$url);
	}
	# 车牌
	private function plate_number(){
		$arr	=	array(
				array('id'=>1,'name'=>'北京','front'=>'京'),
				array('id'=>2,'name'=>'天津','front'=>'津'),
				array('id'=>3,'name'=>'上海','front'=>'沪'),
				array('id'=>4,'name'=>'重庆','front'=>'渝'),
				array('id'=>5,'name'=>'内蒙古自治区','front'=>'蒙'),
				array('id'=>6,'name'=>'维吾尔自治区','front'=>'新'),
				array('id'=>7,'name'=>'西藏自治区','front'=>'藏'),
				array('id'=>8,'name'=>'宁夏回族自治区','front'=>'宁'),
				array('id'=>9,'name'=>'广西壮族自治区','front'=>'桂'),
				array('id'=>10,'name'=>'香港特别行政区','front'=>'港'),
				array('id'=>11,'name'=>'澳门特别行政区','front'=>'澳'),
				array('id'=>12,'name'=>'黑龙江省','front'=>'黑'),
				array('id'=>13,'name'=>'吉林省','front'=>'吉'),
				array('id'=>14,'name'=>'辽宁省','front'=>'辽'),
				array('id'=>15,'name'=>'山西省','front'=>'晋'),
				array('id'=>16,'name'=>'河北省','front'=>'冀'),
				array('id'=>17,'name'=>'青海省','front'=>'青'),
				array('id'=>18,'name'=>'山东省','front'=>'鲁'),
				array('id'=>19,'name'=>'河南省','front'=>'豫'),
				array('id'=>20,'name'=>'江苏省','front'=>'苏'),
				array('id'=>21,'name'=>'安徽省','front'=>'皖'),
				array('id'=>22,'name'=>'浙江省','front'=>'浙'),
				array('id'=>23,'name'=>'福建省','front'=>'闽'),
				array('id'=>24,'name'=>'江西省','front'=>'赣'),
				array('id'=>25,'name'=>'湖南省','front'=>'湘'),
				array('id'=>26,'name'=>'湖北省','front'=>'鄂'),
				array('id'=>27,'name'=>'广东省','front'=>'粤'),
				array('id'=>28,'name'=>'海南省','front'=>'琼'),
				array('id'=>29,'name'=>'甘肃省','front'=>'甘'),
				array('id'=>30,'name'=>'陕西省','front'=>'陕'),
				array('id'=>31,'name'=>'贵州省','front'=>'黔'),
				array('id'=>32,'name'=>'云南省','front'=>'滇'),
				array('id'=>33,'name'=>'四川省','front'=>'川'),
		);
		return $arr;
	}


	public function order_list(){
		if(empty($this->user_session)){
			if(IS_POST){
				$this->check_ajax_error_tips('请先进行登录',U('Login/index'));
			}else{
				$this->error_tips('请先进行登录',U('Login/index'));
			}
		}
		$now_village = $this->get_village($_GET['village_id']);
		$this->display();
	}

	public function village_my_check(){
		//判断用户是否属于本小区
		if(empty($this->user_session)){
			if(IS_POST){
				$this->check_ajax_error_tips('请先进行登录',U('Login/index',array('referer'=>urlencode(U('House/village_my',array('village_id'=>$_GET['village_id']))))));
			}else{
				$this->error_tips('请先进行登录',U('Login/index',array('referer'=>urlencode(U('House/village_my',array('village_id'=>$_GET['village_id']))))));
			}
		}

		if(empty($this->user_session['phone'])){
			if(IS_POST){
				$this->check_ajax_error_tips('请先绑定手机号码',U('My/bind_user',array('referer'=>urlencode(U('village_my',array('village_id'=>$_GET['village_id']))))));
			}else{
				$this->error_tips('请先绑定手机号码',U('My/bind_user',array('referer'=>urlencode(U('village_my',array('village_id'=>$_GET['village_id']))))));
			}
		}

		$now_village = $this->get_village($_GET['village_id']);
		$this->check_village_session($now_village['village_id']);


		$database_house_village_user_vacancy = D('House_village_user_vacancy');
        $where['village_id'] = $_GET['village_id'];
        $where['is_del'] = 0;
		$where['uid'] = $this->user_session['uid'];
        $result = $database_house_village_user_vacancy->where($where)->find();
		if(empty($this->village_bind)){
			if($result){
				redirect(U('my_village_list'));
			}else{
				redirect(U('bind_village2',array('village_id'=>$_GET['village_id'])));
			}
			
		}

		$database_house_village_user_bind = D('House_village_user_bind');
		$now_user_info = $database_house_village_user_bind->get_one_by_bindId($this->village_bind['pigcms_id']);
		if(empty($now_user_info)){
			if($result){
				redirect(U('my_village_list'));
			}else{
				redirect(U('bind_village2',array('village_id'=>$_GET['village_id'])));
			}
		}

	}

	public function bind_village2(){
		$this->display();
	}
}

?>