<?php
/*
 * wap端前台页面----景区列表
 *   Writers    hanlu
 *   BuildTime  2016/07/11 15:00
 */
class Scenic_listAction extends BaseAction{
	# 景点列表
	public function index(){
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
//		$long_lat = D('User_long_lat')->getLocation('oa0OWwERofGeKzCzqjbn10g_3LTE');
//		$long_lat = array(
//			'long'	=>	'117.30171',
//			'lat'	=>	'31.871616',
//		);
		$this->assign('long_lat',$long_lat);
		$this->display();
	}
	# 景点列表
	public function hot_index(){
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
//		$long_lat = D('User_long_lat')->getLocation('oa0OWwERofGeKzCzqjbn10g_3LTE');
//		$long_lat = array(
//			'long'	=>	'116.315564',
//			'lat'	=>	'40.042068',
//		);
		$this->assign('long_lat',$long_lat);
		$this->display();
	}
	# 景区列表json
	public function index_json(){
		$city_id['city_id']	=	$this->config['scenic_city'];
		$city_id['scenic_status']	=	1;
		$page	=	$_POST['page'];
		$count	=	10;
		$limit	=	$page.','.$count;
		$long	=	$_POST['long'];
		$lat	=	$_POST['lat'];
		if($lat && $long){
			$order	=	'juli';
		}else{
			$order	=	'sort DESC';
		}
		if($_POST['is_hot']){
			$city_id['is_hot']	=	1;
		}
		$long_lat['long']	=	$long;
		$long_lat['lat']	=	$lat;
		$scenic_list	=	D('Scenic_list')->get_all_list($city_id,$limit,$order,$long_lat);
		if($scenic_list){
			$scenic_image_class = new scenic_image();
			foreach($scenic_list as &$v){
				$tmp_pic_arr = explode(';',$v['scenic_pic']);
				$v['money'] = floatval($v['money']);
				$v['pic'] = $scenic_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'config','s');
				unset($v['scenic_account'],$v['scenic_pwd']);
				$v['range_txt'] = $this->wapFriendRange($v['juli']);
				$v['url']	=	$this->config['site_url'].U('details',array('scenic_id'=>$v['scenic_id']));
				$v['ticket'] = D('Scenic_ticket')->get_scenic_ticket_all(array('scenic_id'=>$v['scenic_id'],'ticket_status'=>1),array('ticket_id','ticket_title','ticket_price','is_children'),'is_children DESC,ticket_sort DESC');
			}
		}
		$this->returnCode(0,$scenic_list);
	}
	# 景点详情
	public function details(){
		# 获取景点详情
		$scenic	=	$_GET['scenic_id'];
		$scenic_id['scenic_id']	=	$scenic;
		$scenic_list	=	D('Scenic_list')->get_one_list($scenic_id);
		if(empty($scenic_list)){
			$this->error_tips('景区未找到！');
		}else{
			M('Scenic_list')->where($scenic_id)->setInc('hits');
			$tmp_pic_arr = explode(';',$scenic_list['scenic_pic']);
			$scenic_list['money'] = floatval($scenic_list['money']);
			$scenic_list['scenic_intr'] = htmlspecialchars_decode($scenic_list['scenic_intr']);
			$scenic_list['scenic_explain'] = htmlspecialchars_decode($scenic_list['scenic_explain']);
			$scenic_image_class = new scenic_image();
			foreach($tmp_pic_arr as $k=>$x){
				$scenic_list['pic'][$k] = $scenic_image_class->get_image_by_path($x,$this->config['site_url'],'config','s');
			}
			if($scenic_list['score_mean'] == '0.0'){
				$scenic_list['gray_stars']	=	5;
				$scenic_list['score_mean']	=	'0.0';
			}else{
				$scenic_list['red_stars']	=	floor($scenic_list['score_mean']);
				$half_stars	=		explode(".",$scenic_list['score_mean']);
				if($half_stars[1] == 0){
					$scenic_list['half_stars']	=	0;
				}else{
					$scenic_list['half_stars']	=	1;
				}
				if($scenic_list['half_stars'] == 1){
					$scenic_list['gray_stars']	=	5-$scenic_list['red_stars']-1;
				}else{
					$scenic_list['gray_stars']	=	5-$scenic_list['red_stars'];
				}
			}
			$collection	=	M('Scenic_collection')->field('collection_id')->where(array('scenic_id'=>$scenic,'user_id'=>$this->user_session['uid']))->find();
			if($collection){
				$scenic_list['collection']	=	1;
			}else{
				$scenic_list['collection']	=	0;
			}
			$province_id	=	D('Area')->scenic_get_one_city($scenic_list['province_id'],0);
			$city_id	=	D('Area')->scenic_get_one_city($scenic_list['city_id'],0);
			$area_id	=	D('Area')->scenic_get_one_city($scenic_list['area_id'],0);
			$scenic_list['addr']	=	$province_id['area_name'].$city_id['area_name'].$area_id['area_name'].$scenic_list['scenic_address'];
		}
//		$scenic_list['reply_count']	=	D('Scenic_reply')->get_scenic_reply_count($scenic_id);
		$this->assign('scenic_list',$scenic_list);
		# 成人门票
		$scenic_id['ticket_status']	=	1;
		$scenic_ticket	=	D('Scenic_ticket')->get_scenic_ticket_list($scenic_id,2);
//		foreach ($scenic_ticket as &$v) {
//			$v['notice_arr'] = D('Scenic_ticket')->ticket_notice($v['ticket_id']);
//			$v['prefix_explain'] = '1. '.$v['notice_arr']['rule'].'<br>';
//			$v['notice_arr']['union'] && $v['prefix_explain'] .= '2. '.$v['notice_arr']['union'].'<br>';
//			//dump($v['ticket_explain']);
//			$v['ticket_explain'] = $v['prefix_explain'] .$v['ticket_explain'];
//			//dump($v['ticket_explain']);
//		}
		$this->assign('scenic_ticket',$scenic_ticket);
		//dump($scenic_ticket);die;
		$this->assign('ticket_time_array',D('Scenic_ticket')->ticket_time_array);
		# 儿童门票
		$scenic_ticket_children	=	D('Scenic_ticket')->get_scenic_ticket_list($scenic_id,1);



		$this->assign('scenic_ticket_children',$scenic_ticket_children);
		# 获取就近推荐
		//$long_lat = array(
//			'long'	=>	'116.315564',
//			'lat'	=>	'40.042068',
//		);
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		if($long_lat){
			$nearby	=	D('Scenic_list')->nearby($long_lat);
		}else{
			$nearby	=	D('Scenic_list')->recommend($scenic_list['city_id']);
		}
		if($nearby){
			foreach($nearby as $k=>&$z){
				if($z['scenic_id']==$scenic_list['scenic_id']){
					unset($nearby[$k]);
					continue;
				}
				$tmp_pic_arr = explode(';',$z['scenic_pic']);
				$z['money'] = floatval($z['money']);
				$z['pic'] = $scenic_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'config','s');
				unset($z['scenic_account'],$z['scenic_pwd']);
				if(empty($z['money'])){
					$z['money']	=	M('Scenic_ticket')->where(array('scenic_id'=>$z['scenic_id']))->order('ticket_price')->getField('ticket_price');
				}
				$z['money'] = floatval($z['money']);
				$z['url'] = $this->config['site_url'].U('details',array('scenic_id'=>$z['scenic_id']));
			}
		}
		$this->assign('nearby',$nearby);
		# 活动
		$activity	=	D('Scenic_activity')->get_all_list(array('scenic_id'=>$scenic,'status'=>1),2);
		if($activity){
			foreach($activity as $k=>&$v){
				if(empty($v)){
					continue;
				}
				$tmp_pic_arr = explode(';',$v['activity_img']);
				$v['activity_img'] = $scenic_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'activity','s');
				$v['start_time']	=	date('Y-m-d H:i',$v['start_time']);
				$v['url']	=	U('Scenic_inside/inside_activity_details',array('activity_id'=>$v['activity_id']));
			}
		}

		# 推荐
		$group_category = M('Scenic_com_category')->field(array('cat_id','cat_name'))->where(array('scenic_id'=>$scenic,'is_recom'=>1,'status'=>1))->order('cat_sort DESC,cat_id DESC')->select();
		$arr_category	=	array();
		if($group_category){
			foreach($group_category as $vv){
				$list	=	D('Scenic_com')->field(true)->where(array('cat_id'=>$vv['cat_id'],'is_recom'=>1))->order('sort DESC,com_id DESC')->select();
				if(empty($list)){
					continue;
				}
				$store_image_class = new scenic_image();
				foreach($list as &$vvv){
					$vvv['pic'] = $store_image_class->get_image_by_path($vvv['com_img'],$this->config['site_url'],'com','1');
				}

				$arr_category[]	=	array(
					'list'	=>	$list,
					'color'	=>	'jdxqxt_'.rand(0,1).rand(0,9).'.png',
					'cat_name'	=>	$vv['cat_name'],
					'url'	=>	U('Scenic_inside/inside_com',array('cat_id'=>$vv['cat_id'],'scenic_id'=>$scenic))
				);
			}
		}
		$this->assign('activity',$activity);
		$this->assign('arr_category',$arr_category);
		$this->display();
	}
	# 门票订单填写
	public function order(){
		//$arr	=	array(
//			array(
//				'Date'=>'2016-7-1',
//				'Price'=>'158',
//			),
//			array(
//				'Date'=>'2016-7-2',
//				'Price'=>'158',
//			),
//			array(
//				'Date'=>'2016-7-3',
//				'Price'=>'158',
//			),
//			array(
//				'Date'=>'2016-7-4',
//				'Price'=>'158',
//			),
//			array(
//				'Date'=>'2016-8-4',
//				'Price'=>'158',
//			),
//			array(
//				'Date'=>'2016-8-5',
//				'Price'=>'158',
//			),
//			array(
//				'Date'=>'2016-8-6',
//				'Price'=>'158',
//			),
//			array(
//				'Date'=>'2016-8-6',
//				'Price'=>'158',
//			),
//		);
//		echo serialize($arr);exit;
		$this->user_sessions();
		$where['ticket_id']	=	$_GET['ticket_id'];
		if(empty($where['ticket_id'])){
			$this->error_tips('未找到门票！');
		}
		$scenic_ticket	=	D('Scenic_ticket')->get_scenic_one_ticket($where);
		if(empty($scenic_ticket)){
			$this->error_tips('未找到门票！');
		}
		if($scenic_ticket['ticket_status'] == 2){
			$this->error_tips('此门票已经关闭！请购买别的门票！',U('Scenic_list/details',array('scenic_id'=>$scenic_ticket['scenic_id'])));
		}
		$scenic_ticket['ticket_title'] = $scenic_ticket['ticket_prefix_title'];

		if($scenic_ticket['ticket_union_id']){
			//$union_arr = explode(',',$scenic_ticket['ticket_union_id']);
			$condition['ticket_id'] = $scenic_ticket['ticket_union_id'][0];
			$union_ticket = D('Scenic_ticket')->get_scenic_one_ticket($condition);
			$prefix_union_detail = $union_ticket['ticket_title'];
			if(count($scenic_ticket['ticket_union_id'])>2){
				$prefix_union_detail.='等'.count($scenic_ticket['ticket_union_id']).'景区';
			}

			$scenic_ticket['ticket_title'] .= "(含 {$prefix_union_detail})";
		}
		$scenic_ticket['notice_arr']  = D('Scenic_ticket')->ticket_notice($scenic_ticket['ticket_id']);

		$scenic_ticket['prefix_explain'] = '1. '.$scenic_ticket['notice_arr']['rule'].'<br>';
		$scenic_ticket['notice_arr']['union'] && $scenic_ticket['prefix_explain'] .= '2. '.$scenic_ticket['notice_arr']['union'].'<br>';

		$scenic_ticket['serialize']	=	unserialize($scenic_ticket['serialize']);
		$scenic_ticket['serialize']	=	json_encode($scenic_ticket['serialize']);
		$scenic_ticket['start_time']=	substr($scenic_ticket['start_time'],0,5);
		$scenic_ticket['end_time']	=	substr($scenic_ticket['end_time'],0,5);
		$scenic_ticket['ticket_explain'] = $scenic_ticket['prefix_explain'] .htmlspecialchars_decode($scenic_ticket['ticket_explain']);
		$scenic_ticket['ticket_cue'] = htmlspecialchars_decode($scenic_ticket['ticket_cue']);
		$scenic_ticket['park_intr'] = htmlspecialchars_decode($scenic_ticket['park_intr']);
		$where['scenic_id']	=	$scenic_ticket['scenic_id'];
		$scenic_list	=	D('Scenic_list')->get_one_list($where);

		if($scenic_list['is_parking'] == 2){
			$scenic_list['parking_price']	=	0;
		}

		if($scenic_list['is_parking'] == 1){
			$scenic_park	=	D('Scenic_park')->get_user_park($where);
		}else{
			$scenic_park	=	array();
		}
		if($scenic_list['is_guide'] == 1){
			$scenic_guide	=	D('Scenic_guide')->city_get_all_guide($where);
			if(empty($scenic_guide)){
				$scenic_guide	=	array();
			}else{
				$scenic_image_class = new scenic_image();
				foreach($scenic_guide as $k=>&$v){
					$v['pic'] = $scenic_image_class->get_image_by_path($v['guide_pig'],$this->config['site_url'],'guide','s');
					if($k==0){
						$one_guide	=	$v;
					}
				}
			}
		}else{
			$scenic_guide	=	array();
		}
		if($scenic_list['is_guide'] == 2 || empty($scenic_guide)){
			$scenic_list['guide_price']	=	0;
		}
		$scenic_ticket['total_price']	=	$scenic_ticket['ticket_price']+$scenic_list['parking_price']+$scenic_list['guide_price'];
		$scenic_family	=	D('Scenic_family')->get_all_list(array('user_id'=>$this->user_session['uid']));

		$this->assign('scenic_ticket',$scenic_ticket);
		$this->assign('scenic_list',$scenic_list);
		$this->assign('scenic_park',$scenic_park);
		$this->assign('user_session',$this->user_session);
		$this->assign('scenic_guide',$scenic_guide);
		$this->assign('one_guide',$one_guide);
		$this->assign('scenic_family',$scenic_family);
		$this->display();
	}
	# 门票订单填写
	public function order_json(){
		$this->user_sessions_json();
		$where['ticket_id']	=	$_POST['ticket_id'];
		$ticket_num		=	$_POST['num'];
		if(empty($ticket_num)){
			$this->returnCode('40000030');
		}
		if(empty($where['ticket_id'])){
			$this->returnCode('40000037');
		}
		$scenic_ticket	=	D('Scenic_ticket')->get_scenic_one_ticket($where);
		if(empty($scenic_ticket)){
			$this->returnCode('40000037');
		}
		if($scenic_ticket['ticket_status'] == 2){
			$this->returnCode('40000038');
		}
		$scenic_list	=	D('Scenic_list')->get_one_list(array('scenic_id'=>$scenic_ticket['scenic_id']));
		$serialize	=	unserialize($scenic_ticket['serialize']);
		$ticket_time	=	explode('-',$_POST['ticket_time']);
		if($ticket_time[1] < 10){
			$ticket_time[1]	=	'0'.$ticket_time[1];
		}
		if($ticket_time[2] < 10){
			$ticket_time[2]	=	'0'.$ticket_time[2];
		}
		$ticket_time	=	$ticket_time[0].'-'.$ticket_time[1].'-'.$ticket_time[2];
		if($serialize){
			foreach($serialize as $v){
				if($ticket_time == $v['Date']){
					$ticket_price	=	$v['Price'];
					break;
				}else{
					$ticket_price	=	$scenic_ticket['ticket_price'];
				}
			}
		}else{
			$ticket_price	=	$scenic_ticket['ticket_price'];
		}
		# 总订单
		$data['ticket_id']	=	$_POST['ticket_id'];
		$data['family_id']	=	$_POST['family_id'];
		//$data['truename']	=	$_POST['truename'];
		//$data['phone']		=	$_POST['phone'];
		$data['scenic_id']	=	$scenic_list['scenic_id'];
		$data['ticket_time']=	$_POST['ticket_time'];
		$data['user_id']	=	$this->user_session['uid'];
		$data['add_time']	=	$_SERVER['REQUEST_TIME'];
		$data['order_imm']	=	date('YmdHis',$_SERVER['REQUEST_TIME']).rand(100,999);
		$data['paid']		=	1;
		$data['order_status']=	0;
		$data['ticket_num']=	$ticket_num;
		$data['price']=	$scenic_ticket['ticket_price'];
		$data['is_group']=	$scenic_ticket['ticket_group'];


		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		$isIos = false;
		if($data['is_group']==2){
			if($this->is_wexin_browser) {
				$isIos = true;
				$data['group_traveler_img']=	implode(';',$_POST['group_traveler_img']);
			}else{
				$data['traveler_list_excel'] = $_POST['traveler_list'];
				require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel/IOFactory.php';
				$path = $data['traveler_list_excel'];
				$fileType = PHPExcel_IOFactory::identify($path); //文件名自动判断文件类型
				$objReader = PHPExcel_IOFactory::createReader($fileType);
				$excelObj = $objReader->load($path);
				$excel_result = $excelObj->getActiveSheet()->toArray(null, true, true, true);
				foreach ($excel_result as $key=>$item) {
					if(!empty($item['A'])){
						$result[] = $item;
					}
				}

				if (!empty($result) && is_array($result)) {
					unset($result[0]);
					$last_user_id = 0;
					$err_msg = '';
					$traveler_list = array();

					if(count($result)!=$ticket_num){
						$this->returnCode('40000061');
					}

					foreach ($result as $kk => $vv) {
						if (array_sum($vv) == 0) {
							continue;
						}

						if (empty($vv['A'])) {
							$err_msg = '游客手机号未填写';
							$this->returnCode('40000003','',$err_msg);
							continue;
						}
						if (empty($vv['B'])) {
							$err_msg = '游客身份证未填写';
							$this->returnCode('40000003','',$err_msg);
							continue;
						}
						if (empty($vv['C'])) {
							$err_msg = '游客姓名未填写';
							$this->returnCode('40000003','',$err_msg);
							continue;
						}


						$tmpdata['person_name'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
						$tmpdata['person_id'] = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
						$tmpdata['phone'] = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
						$tmpdata['last_time'] = time();
						$traveler_list[] = $tmpdata;
					}

				}
			}
		}


		if($scenic_ticket['ticket_time_type']==1){
			if($scenic_ticket['end_time'] && $scenic_ticket['end_time']!='00:00:00'){
				$data['endtime']= strtotime($data['ticket_time'].' '.$scenic_ticket['end_time']);
			}else{
				$data['endtime']= strtotime($data['ticket_time'])+86399;
			}
		}else{
			$data['endtime']=	time()+$scenic_ticket['ticket_end_time'];
		}

		if($_POST['park'] != 0){
			$park	=	$_POST['park']*$scenic_list['parking_price'];
		}
		if($_POST['guide_price'] == 1){
			$guide_price	=	$scenic_list['guide_price'];
		}
		$data['order_total']	=	$ticket_price*$ticket_num+$park+$guide_price;
		$add_order	=	D('Scenic_order')->add_order($data);

		if($add_order){
			$order_id	=	D('Scenic_order')->getLastInsID();

//			foreach ($traveler_list as &$item) {
//				$item['order_id'] = $order_id;
//			}
//			M('Scenic_group_user')->addAll($traveler_list);

			# 门票订单
			for($i=0;$i<$ticket_num;$i++){
				if($scenic_ticket['ticket_group']==2 && $i>0){
					break;
				}
				$data_com	=	array(
					'order_id'	=>	$order_id,
					'type_id'	=>	$_POST['ticket_id'],
					'price'		=>	$ticket_price,
					'type'		=>	1,
					'status'	=>	1,
				);
				$add_order_com	=	D('Scenic_order')->add_order_com($data_com);
				if(empty($add_order_com)){
					$this->returnCode('40000003');
				}
			}
			# 车位订单
			if($_POST['park'] != 0){
				$scenic_guide_number	=	explode(',',$_POST['array']);
				for($y=0;$y<$_POST['park'];$y++){
					$data_park	=	array(
						'order_id'	=>	$order_id,
						'type_id'	=>	$_POST['park_id'],
						'price'		=>	$scenic_list['parking_price'],
						'code'		=>	$scenic_guide_number[$y],
						'type'		=>	2,
						'status'	=>	1,
					);
					$add_order_com	=	D('Scenic_order')->add_order_com($data_park);
					if(empty($add_order_com)){
						$this->returnCode('40000004');
					}
				}
			}
			# 导游订单
			if($_POST['guide_price'] == 1){
				$data_guide	=	array(
					'order_id'	=>	$order_id,
					'type_id'	=>	$_POST['guide_id'],
					'price'		=>	$scenic_list['guide_price'],
					'type'		=>	3,
					'status'	=>	1,
				);
				$add_order_com	=	D('Scenic_order')->add_order_com($data_guide);
				if(empty($add_order_com)){
					$this->returnCode('40000005');
				}
			}
			# 结伴
			if(!empty($_POST['mate'])){
				$data_mate	=	array(
					'province_id'	=>	$scenic_list['province_id'],
					'city_id'	=>	$scenic_list['city_id'],
					'scenic_id'	=>	$scenic_list['scenic_id'],
					'order_id'	=>	$order_id,
					'mate_intr'	=>	$_POST['mate'],
					'start_time'	=>	$_POST['ticket_time'],
					'mate_status'	=>	0,
					'create_time'	=>	$_SERVER['REQUEST_TIME'],
				);
				$scenic_mate	=	D('Scenic_mate')->add_scenic_mate($data_mate);
				$mate_order_id	=	D('Scenic_mate')->getLastInsID();
				if($scenic_mate){
					$data_mate_order	=	array(
						'mate_id'	=>	$mate_order_id,
						'user_id'	=>	$this->user_session['uid'],
						'rela_status'	=>	1,
						'phone'		=>	$this->user_session['phone'],
						'is_mate'	=>	1,
						'create_time'	=>	$_SERVER['REQUEST_TIME'],
					);
					D('Scenic_mate')->add_scenic_mate_order($data_mate_order);
				}
			}
		}else{
			$this->returnCode('40000002');
		}
		if(empty($this->user_session['phone'])){
			$user_info['phone']	=	$_POST['phone'];
		}
		if(empty($this->user_session['nickname'])){
			$user_info['nickname']	=	$_POST['name'];
		}
		if($user_info){
			$scenic_save_user	=	D('User')->scenic_save_user($this->user_session['uid'],$user_info);
			if($scenic_save_user){
				$_SESSION['phone']	=	$_POST['phone'];
				$_SESSION['nickname']	=	$_POST['name'];
			}
		}
		$url	=	$this->config['site_url'].U('Scenic_pay/index',array('order_id'=>$order_id));
		$this->returnCode(0,$url);
	}
	# 最新发布
	public function new_mate(){
		$this->display();
	}
	# 最新发布json
	public function new_mate_json(){
		$city_id	=	$this->config['scenic_city'];
		$start_tiem	=	date('Y-m-d',$_SERVER['REQUEST_TIME']);
		$page	=	$_POST['page'];
		$scenic_min_mate	=	D('Scenic_mate')->save_all_scenic_mate_time($city_id,$start_tiem);
		$scenic_mate	=	D('Scenic_mate')->get_all_scenic_mate_time($city_id,$start_tiem,$page);
		if($scenic_mate){
			foreach($scenic_mate as &$v){
				$v['scenic_mate']	=	D('Scenic_mate')->get_one_scenic_mate_order(array('mate_id'=>$v['mate_id'],'is_mate'=>1));
				$v['scenic_list']	=	D('Scenic_list')->get_one_list(array('scenic_id'=>$v['scenic_id']));
				$v['scenic_user']	=	D('User')->get_user($v['scenic_mate']['user_id']);
				if(empty($v['scenic_user']['avatar'])){
					$v['scenic_user']['avatar']	=	$this->config['site_url'] . '/static/images/user_avatar.jpg';
				}
				$v['create_time']	=	date('Y-m-d H:i',$v['create_time']);
				$v['url']			=	$this->config['site_url'].U('Scenic_user/mate_details',array('mate_id'=>$v['mate_id']));
//				$v['response_count']=	D('Scenic_mate')->mate_count($v['mate_id']);
			}
		}else{
			$this->returnCode('40000008');
		}
		$this->returnCode(0,$scenic_mate);
	}
	# 报名最多
	public function most_mate(){
		$this->display();
	}
	# 报名最多json
	public function most_mate_json(){
		$city_id	=	$this->config['scenic_city'];
		$start_tiem	=	date('Y-m-d',$_SERVER['REQUEST_TIME']);
		$page	=	$_POST['page'];
		$scenic_min_mate	=	D('Scenic_mate')->save_all_scenic_mate_time($city_id,$start_tiem);
		$scenic_mate	=	D('Scenic_mate')->get_all_scenic_mate_time($city_id,$start_tiem,$page,'>','people_number DESC');
		if($scenic_mate){
			foreach($scenic_mate as &$v){
				$v['scenic_mate']	=	D('Scenic_mate')->get_one_scenic_mate_order(array('mate_id'=>$v['mate_id'],'is_mate'=>1));
				$v['scenic_list']	=	D('Scenic_list')->get_one_list(array('scenic_id'=>$v['scenic_id']));
				$v['scenic_user']	=	D('User')->get_user($v['scenic_mate']['user_id']);
				if(empty($v['scenic_user']['avatar'])){
					$v['scenic_user']['avatar']	=	$this->config['site_url'] . '/static/images/user_avatar.jpg';
				}
				$v['create_time']	=	date('Y-m-d H:i',$v['create_time']);
				$v['url']			=	$this->config['site_url'].U('Scenic_user/mate_details',array('mate_id'=>$v['mate_id']));
//				$v['response_count']=	D('Scenic_mate')->mate_count($v['mate_id']);
			}
		}else{
			$this->returnCode('40000008');
		}
		$this->returnCode(0,$scenic_mate);
	}
	public function score(){
		if($_GET['scenic_id']){
			$scenic_id	=	$_GET['scenic_id'];
			$reply_type	=	1;
		}else if($_GET['guide_id']){
			$scenic_id	=	$_GET['guide_id'];
			$reply_type	=	2;
		}
		$this->assign('reply_type',$reply_type);
		$this->assign('scenic_id',$scenic_id);
		$this->display();
	}
	public function score_json(){
		$where['scenic_id']	=	$_POST['scenic_id'];
		$where['reply_type']=	$_POST['reply_type'];
		$page	=	$_POST['page'];
		$reply_list	=	D('Scenic_reply')->get_reply_list($where,$page);
		if($reply_list != 0){
			foreach($reply_list as $k=>&$v){
				$user	=	D('User')->get_user($v['user_id']);
				if(empty($user['avatar'])){
					$v['avatar']	=	$this->config['site_url'] . '/static/images/user_avatar.jpg';
				}else{
					$v['avatar']	=	$user['avatar'];
				}
				$v['nickname']	=	$user['nickname'];
				$v['reply_time']	=	date('Y-m-d',$v['reply_time']);
				$v['reply_scores']	=	5-$v['reply_score'];
				$v['reply_score']	=	(int)$v['reply_score'];
			}
			$this->returnCode(0,$reply_list);
		}else{
			$this->returnCode('40000008');
		}
	}
	# 收藏接口
	public function collection_json(){
		$this->user_sessions_json();
		if($_POST['type'] == 1){
			$arr	=	array(
				'user_id'	=>	$this->user_session['uid'],
				'scenic_id'	=>	$_POST['scenic_id'],
				'add_time'	=>	$_SERVER['REQUEST_TIME'],
			);
			$add	=	M('Scenic_collection')->data($arr)->add();
			if(empty($add)){
				$this->returnCode('40000022');
			}
		}else if($_POST['type'] == 2){
			$delect	=	M('Scenic_collection')->where(array('user_id'=>$this->user_session['uid'],'scenic_id'=>$_POST['scenic_id']))->delete();
			if(empty($delect)){
				$this->returnCode('40000023');
			}
		}else if($_POST['type'] == 3){
			$delect	=	M('Scenic_collection')->where(array('collection_id'=>$_POST['collection_id']))->delete();
			if(empty($delect)){
				$this->returnCode('40000023');
			}
		}
		$this->returnCode(0);
	}
	# 公共接口
	public function user_sessions(){
		if(empty($this->user_session)){
			$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
			redirect(U('Login/index',$location_param));
		}
	}
	public function user_sessions_json(){
		if(empty($this->user_session)){
			$this->returnCode('20020008');
		}
	}

	#excel上传
	public function ajaxWebUpload(){
		if ($_FILES['file']['error'] != 4) {
			$upload_dir = './upload/excel/scenic_user/' . date('Ymd') . '/';
			if (!is_dir($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->maxSize = 10 * 1024 * 1024;
			$upload->allowExts = array('xls', 'xlsx');
			$upload->allowTypes = array(); // 允许上传的文件类型 留空不做检查
			$upload->savePath = $upload_dir;
			$upload->thumb = false;
			$upload->thumbType = 0;
			$upload->imageClassPath = '';
			$upload->thumbPrefix = '';
			$upload->saveRule = 'uniqid';
			if ($res = $upload->upload()) {
				$file_info = $upload->getUploadFileInfo();
				exit(json_encode(array('error' => 0,'message' =>$file_info[0]['savepath'].$file_info[0]['savename'],'filename'=>$_FILES['file']['name'])));
			}
		}else{
			exit(json_encode(array('error' => 1,'message' =>'没有文件')));
		}
	}
}
?>