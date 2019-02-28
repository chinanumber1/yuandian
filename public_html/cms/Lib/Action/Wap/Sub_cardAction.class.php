<?php
	class Sub_cardAction extends BaseAction{
		private $now_user ;
		public function __construct()
		{
			parent::__construct();
			if(empty($this->user_session) && ACTION_NMAE!='index'){
				if($this->is_app_browser){
					$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
					$this->error_tips('请先进行登录！',U('Login/index',$location_param));
				}
//				else{
//					$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
//					redirect(U('Login/index',$location_param));
//				}
			}

			$this->now_user = D('User')->get_user($this->user_session['uid']);
		}


		public function index(){
			$this->display();
		}

		public function sub_card_detail(){

			$sub_card_id = $_GET['sub_card_id'];

			$sub_card = D('Sub_card')->get_sub_card($sub_card_id);
			if($sub_card['status']==3){
				$this->error_tips('该免单套餐已过期');
			}
			if($sub_card['status']==0){
				$this->error_tips('该免单套餐不能购买');
			}
			$this->assign('sub_card',$sub_card);
			$this->display();
		}

		public function select_store(){

			$sub_card_id = $_GET['sub_card_id'];

			$sub_card = D('Sub_card')->get_sub_card($sub_card_id);

			$this->assign('sub_card',$sub_card);
			$this->display();
		}

		public function  ajax_get_card(){
			$limit = $_POST['page'].',10';

			$up = $_POST['up']?' ASC ':' DESC ';
			$sort = $_POST['sort'].$up;

			$card_list = D('Sub_card')->get_can_buy_sub_card($sort,$limit);
			foreach ($card_list['list'] as &$v) {

				$sub_card = D('Sub_card')->get_sub_card($v['id']);

				$v['join_num'] = $sub_card['join_num'];
				$v['start_time'] = date('Y-m-d',$v['start_time']);
				$v['end_time'] = date('Y-m-d',$v['end_time']);
			}

//			for($i=0;$i<5;$i++){
//				$card_list[0]['price'] =$card_list[0]['price']+$i;
//				$card_list[0]['sale_count']=  $card_list[0]['sale_count']+$i;
//				$tmp = $card_list[0];
//
//				$date[] = $tmp;
//			}
//			$card_list = $date;

			echo json_encode(array('card_list'=>$card_list));exit;
		}

		public function  ajax_get_card_store(){
			$where['sub_card_id'] =$_POST['sub_card_id'];
			$where['ms.status'] = 1;
			$where['ms.sku'] = array('gt',0);
//			$where['_string'] = 'ms.sku>0 AND ms.sku >ms.sale_count';
			$sub_card = D('Sub_card')->get_sub_card($_POST['sub_card_id']);
			$count = M('Sub_card_mer_apply')
					->join('AS ms LEFT JOIN '.C('DB_PREFIX').'merchant_store AS s ON ms.store_id = s.store_id')->where($where)->count();
			$store_list = M('Sub_card_mer_apply')
					->join('AS ms LEFT JOIN '.C('DB_PREFIX').'merchant_store AS s ON ms.store_id = s.store_id LEFT JOIN '.C('DB_PREFIX').'merchant m ON m.mer_id=s.mer_id')
					->where($where)
					->field("ms.*,s.name,s.long,s.lat,s.adress,s.phone,s.pic_info,m.name as mer_name ,ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$_POST['lat']}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$_POST['lat']}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$_POST['lng']}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli")
					->order('juli ASC')
					->limit($_POST['page'],20)
					->select();

			$store_image_class = new store_image();
			foreach ($store_list as &$v) {
				if(!empty($v['pic_info'])){
					$all_pic = $store_image_class->get_allImage_by_path($v['pic_info']);
					$v['pic_info'] = $all_pic[0];
				}
				$v['pic_lists'] = empty($v['pic_list'])?'':explode(';',$v['pic_list']);
				$v['juli'] = getRange($v['juli']);
				$v['mer_free_num'] =$sub_card['mer_free_num'];
				$v['use_time_type'] =$sub_card['use_time_type'];
				$v['sku_']=$v['sku'];
				if($v['sku']<=0){
					$v['sku']=0;
					$v['sku_']=0;
				}else{
					$v['sku'] = $v['sku']<$sub_card['mer_free_num']?$v['sku']:$sub_card['mer_free_num'];
				}
				$v['status']=1;
				if($v['end_time']!=''&&$v['end_time']<time()){
					$v['status']=3;
				}
				$v['start_time'] =date('Y-m-d',$v['start_time']);
				$v['end_time'] =date('Y-m-d',$v['end_time']);
			}
			echo json_encode(array('store_list'=>$store_list,'count'=>$count));exit;
		}



		public function sub_card_buy(){
			if(empty($this->now_user)){
				$this->error_tips('您还没有登录',U('Login/index'));
			}
			$sub_card_id = $_POST['sub_card_id'];
			$store_list = implode(',',$_POST['store_id']);
			if(empty($store_list)){
				$this->error('您没有选择店铺');
			}
			$sub_card = D('Sub_card')->get_sub_card($sub_card_id);
			if($sub_card['status']!=1){
				$this->error($sub_card['status_txt']);
			}

			if(count($_POST['store_id'])<$sub_card['free_total_num']){
				$this->error('您选的数量不足');
			}

			if(count($_POST['store_id'])>$sub_card['free_total_num']){
				$this->error('您选的数量超出限制');
			}

			$data['money'] = $sub_card['price'];
			$data['sub_card_id'] = $sub_card_id;
			$data['uid'] = $this->now_user['uid'];
			$data['store_id'] = $store_list;
			$data['order_name'] =$sub_card['name'];
			$data['status'] = 0; //0 正常 2用完了
			$data['add_time'] =$_SERVER['REQUEST_TIME'];
			if($order_id= D('Sub_card_order')->add($data)){
				$pay_order_param = array(
						'business_type' => 'sub_card',
						'business_id' => $order_id,
						'order_name' => '免单订单',
						'uid' => $this->now_user['uid'],
						'total_money' => $sub_card['price'],
						'store_id' => '',
						'wx_cheap' => 0,
				);
				$result = D('Plat_order')->add_order($pay_order_param);
				if ($result['error_code']) {
					$this->error('支付失败稍后重试');
				} else {
					$this->success('订单创建成功',U('Pay/check', array('order_id' => $result['order_id'], 'type' => 'plat')));exit;
				}
			}else{
				$this->error('无法购买！');
			}


		}

		//订单详情
		public function order_detail(){
			if(empty($this->now_user)){
				$this->error_tips('您还没有登录',U('Login/index'));
			}
			$order_id = $_GET['order_id'];
			$order_info = D('Sub_card_order')->get_order_by_id($order_id);
			if($order_info['uid']!=$this->now_user['uid']){
				$this->error_tips('订单不存在');
			}
			$pass_array = D('Sub_card_order')->get_user_pass($order_id);
			$sub_card = D('Sub_card')->get_sub_card($order_info['sub_card_id']);
			$sub_card['effective_days'] = $sub_card['effective_days']-floor((time()-$order_info['pay_time'])/86400);
			$this->assign('order_info',$order_info);
			$this->assign('sub_card',$sub_card);
			$this->assign('now_user',$this->now_user);
			$this->assign('share_num',M('Sub_card_user_pass')->where(array('fid'=>$order_id,'share'=>1))->count());
			$this->assign('consume_num',M('Sub_card_user_pass')->where(array('fid'=>$order_id,'status'=>1))->count());
			$this->display();

		}

		public function  ajax_get_order_store(){
			$order_info = D('Sub_card_order')->get_order_by_id($_POST['order_id']);
			$sub_card = D('Sub_card')->get_sub_card($order_info['sub_card_id']);
			$sub_card['effective_days'] = $sub_card['effective_days']-floor((time()-$order_info['pay_time'])/86400);
			$today_zero = strtotime(date('Ymd',time()));
			$where['ms.sub_card_id'] =$order_info['sub_card_id'];
			$where['ms.status'] = 1;
			$pass_array = D('Sub_card_order')->get_user_pass($_POST['order_id']);
			$order_info['store_id'] = explode(',',$order_info['store_id']);
			$order_info['store_id'] = array_unique($order_info['store_id']);
			$where['ms.store_id'] = array('in',$order_info['store_id']);
			$store_list = M('Sub_card_mer_apply')
				->join('AS ms LEFT JOIN '.C('DB_PREFIX').'merchant_store AS s ON ms.store_id = s.store_id LEFT JOIN '.C('DB_PREFIX').'merchant m ON m.mer_id=s.mer_id')
				->where($where)
				->field("ms.*,s.name,s.long,s.lat,s.adress,s.phone,m.name as mer_name,s.pic_info,ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$_POST['lat']}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$_POST['lat']}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$_POST['lng']}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli")
				->order('juli ASC')
				->select();
			$store_image_class = new store_image();
			$consume_store =array();
			$store_unconsume_num =array();
			$consume_num = 0;
			$unconsume_num = 0;
			$share_num = 0;
			$unshare_num = 0;
			$share_store =array();
			$store_shera_num =array();
			$store_unshera_num =array();
			$store_num =array();
			foreach ($pass_array as $p) {
				$store_num[$p['store_id']]++;
				if($p['status']==1 ){
					$consume_num++;
					if( !in_array($p['store_id'],$consume_store)){
						$consume_store[] = $p['store_id'];
					}
				}else{
					$store_unconsume_num[$p['store_id']]++;
					$unconsume_num++;
				}
				if($p['share']==1 ){
					$share_num++;
					$store_shera_num[$p['store_id']]++;
					if( !in_array($p['store_id'],$share_store)){
						$share_store[] = $p['store_id'];
					}
				}elseif($p['share']==0 && $p['status']==0){
					$store_unshera_num[$p['store_id']]++;
					$unshare_num++;
				}
			}
			$tmp_store_id =array();
			foreach ($store_list as $key=>&$v) {
				if(!empty($v['pic_info'])){
					$all_pic = $store_image_class->get_allImage_by_path($v['pic_info']);
					$v['pic_info'] = $all_pic[0];
				}
				$v['pic_lists'] = empty($v['pic_lists'])?'':explode(';',$v['pic_list']);
				$v['consume'] = 0;
				if(in_array($v['store_id'],$consume_store)){
					$v['consume'] = 1;
				}
				if(empty($store_unconsume_num[$v['store_id']])){
					$v['num'] = 0;
				}else{
					$v['num'] = $store_unconsume_num[$v['store_id']];
//					$v['num'] = $store_unconsume_num[$v['store_id']]-$store_shera_num[$v['store_id']];
				}
				$v['total_num'] =$store_num[$v['store_id']];
//				$v['consume_num'] =$v['total_num']-$v['num']-$store_shera_num[$v['store_id']];
				$v['consume_num'] =$v['total_num']-$v['num'];
				$v['share_num'] =$store_shera_num[$v['store_id']];
				$v['unshare_num'] =$store_unshera_num[$v['store_id']];
				$v['juli'] = getRange($v['juli']);
				if($sub_card['effective_days']<=0 && $sub_card['use_time_type']==1){
					$v['effective_days'] = $sub_card['effective_days'];
				}else if($v['end_time']>0){
					$v['effective_days'] =floor(($v['end_time']-$today_zero)/86400);
				}else{
					$v['effective_days'] = $sub_card['effective_days'];
				}
				$v['use_time_type'] = $sub_card['use_time_type'];
				$tmp_store_id[]  =$store_list[$key];
			}

			echo json_encode(array('store_list'=>$store_list,
				'consume_count'=>count($consume_store),
				'unconsume_count'=>count($store_list)-count($consume_store),
				'consume_num'=>$consume_num,
				'unconsume_num'=>$unconsume_num,
				'share_num'=>$share_num,
				'unshare_num'=>$unshare_num,
			));exit;
		}
		public function order_detail_pass(){
			if(empty($this->now_user)){
				$this->error_tips('您还没有登录',U('Login/index'));
			}
			$order_id = $_GET['order_id'];
			$store_id = $_GET['store_id'];

			$order_info = D('Sub_card_order')->get_order_by_id($order_id);
			$pass_by_share = M('Sub_card_user_pass')->where(array('share_uid'=>$this->now_user['uid'],'fid'=>$order_id,'store_id'=>$store_id))->select();
			if($order_info['uid']!=$this->now_user['uid'] && empty($pass_by_share)){
				$this->error_tips('非法请求');
			}
			if(!empty($pass_by_share)){
				$pass_array = $pass_by_share;
			}else{
				$pass_array = D('Sub_card_order')->get_user_pass($order_id);
			}

			$sub_card_store = D('Sub_card')->sub_card_store_info($order_info['sub_card_id'],$_GET['store_id']);
			$sub_card = D('Sub_card')->get_sub_card($order_info['sub_card_id']);
			if($sub_card['use_time_type']==1){
				$sub_card['effective_days'] = $sub_card['effective_days']-floor((time()-$order_info['pay_time'])/86400);
			}else{
				$sub_card['effective_days'] = floor(($sub_card_store['end_time']- strtotime(date('Ymd',time())))/86400);
			}

			$this->assign('pic_list',!empty($sub_card_store['pic_list'])?explode(';',$sub_card_store['pic_list']):'');
			$consume_num = 0;
			$share_num = 0;
			foreach ($pass_array as $value) {
//				if($value['store_id']==$_GET['store_id'] && $value['share']==0){
				if($value['store_id']==$_GET['store_id'] ){
					$pass[] = $value;
				}
				if($value['status']==1){
					$consume_num++;
				}
				if($value['share']==1 ){
					$share_num++;
				}
			}


			$this->assign('sub_card',$sub_card);
			$this->assign('consume_num',$consume_num);
			$this->assign('share_num',$share_num);
			$this->assign('sub_card_store',$sub_card_store);
			$this->assign('pass',$pass);
			$this->assign('pass_by_share',$pass_by_share);

			//foodshp
			$store = M('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
			$slider_list = M('Sub_card_store_slider')->where(array('store_id'=>$store_id,'sub_card_id'=>$order_info['sub_card_id'],'status'=>1))->select();
			$this->assign('slider_list',$slider_list);
//			if (!empty($store)) {
//
//				$store['business_time'] = '';
//				$store['is_close']      = 1;
//				if ($store['open_1'] == '00:00:00' && $store['close_1'] == '00:00:00') {
//					$store['is_close']      = 0;
//					$store['business_time'] = '24小时营业';
//				} else {
//					$now_time               = date('H:i:s');
//					$store['business_time'] = substr($store['open_1'], 0, -3) . '~' . substr($store['close_1'], 0, -3);
//					if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
//						$store['is_close'] = 0;
//					}
//					if ($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00') {
//						$store['business_time'] .= ';' . substr($store['open_2'], 0, -3) . '~' . substr($store['close_2'], 0, -3);
//						if ($store['open_2'] < $now_time && $now_time < $store['close_2']) {
//							$store['is_close'] = 0;
//						}
//					}
//					if ($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00') {
//						$store['business_time'] .= ';' . substr($store['open_3'], 0, -3) . '~' . substr($store['close_3'], 0, -3);
//						if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
//							$store['is_close'] = 0;
//						}
//					}
//				}
//			}
//			$foodshop = M('Merchant_store_foodshop')->field(true)->where(array('store_id' => $store_id))->find();
//			if($foodshop) {
//				$foodshop['pic_count'] = 1;
//				$foodshop['pic_str'] = '';
//				if (!empty($foodshop['pic'])) {
//					$goods_image_class = new foodshopstore_image();
//					$foodshop['pic'] = $goods_image_class->get_allImage_by_path($foodshop['pic'], 'm');
//					$foodshop['pic_count'] = count($foodshop['pic']);
//					$foodshop['pic_str'] = implode(',', $foodshop['pic']);
//				}
//
//				$foodshop = array_merge($store, $foodshop);
//
//				$card_info = D('Card_new')->get_card_by_mer_id($foodshop['mer_id']);
//				$coupon_list = D('Card_new_coupon')->get_coupon_list_by_type_merid('meal', $foodshop['mer_id'], 0, 5, -1);
//
//				$this->assign('card_info', $card_info);
//				$this->assign('coupon_list', $coupon_list);
//
//				$now_time = time();
//				$sql = 'SELECT * FROM ' . C('DB_PREFIX') . 'group_store AS gs INNER JOIN ' . C('DB_PREFIX') . 'group AS g ON g.group_id=gs.group_id WHERE gs.store_id=' . $store_id . ' AND `g`.`status`=1  AND `g`.`type`=1 AND `g`.`end_time`>\'' . $now_time . '\' ORDER BY `g`.`sort` DESC,`g`.`group_id` DESC';
//				$groups = D()->query($sql);
//				$group_image_class = new group_image();
//				foreach ($groups as $row) {
//					$tmp_pic_arr = explode(';', $row['pic']);
//					$row['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
//					$row['url'] = U('Group/detail', array('pin_num' => 0, 'group_id' => $row['group_id']), true, false, true);
//
//					$row['price'] = floatval($row['price']);
//					$row['old_price'] = floatval($row['old_price']);
//					$row['wx_cheap'] = floatval($row['wx_cheap']);
//					$row['is_start'] = 1;
//					$row['pin_num'] = $row['pin_num'];
//					if ($now_time < $row['begin_time']) {
//						$row['is_start'] = 0;
//					}
//					if ($row['begin_time'] + 864000 > time() && $row['sale_count'] == 0) {
//						$row['sale_txt'] = '新品上架';
//					} elseif ($row['begin_time'] + 864000 < time() && $row['sale_count'] == 0) {
//						$row['sale_txt'] = '';
//					} else {
//						$row['sale_txt'] = '已售' . floatval($row['sale_count'] + $row['virtual_num']);
//					}
//					$row['begin_time'] = date("Y-m-d H:i:s", $row['begin_time']);
//					$foodshop['group_list'][] = $row;
//				}
//				if ($foodshop['is_takeout'] && ($shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find())) {
//					$foodshop['is_takeout'] = 1;
//				} else {
//					$foodshop['is_takeout'] = 0;
//				}
//				$this->assign('shop', $foodshop);
//			}else{
//				$this->assign('shop', $store);
//			}
			$this->display();
		}

		public function ajax_share_sub_card(){
			if(empty($this->now_user)){
				$this->error('您还没有登录',U('Login/index'));
			}
			$store_id = $_POST['store_id'];
			$order_id = $_POST['order_id'];
			$share_id = array();
			if($_POST['share_id']>0){
				foreach ($store_id as $v) {
					$where['fid']= $order_id;
					$where['status']= 0;
					$where['share']= 0;
					$where['store_id']= $v;
					$date['share'] = 1;
					$share_pass = M('Sub_card_user_pass')->where($where)->find();
					$condition['id'] = $share_pass['id'];
					$share_id[] = $share_pass['id'];
					M('Sub_card_user_pass')->where($condition)->save($date);
					$data = array('share_id'=>implode(',',$share_id),'num'=>count($share_id));
					M('Sub_card_share_list')->where(array('id'=>$_POST['share_id']))->save($data);
					//$this->success(array('msg'=>'分享成功','share_id'=>$id));
				}
			}else{
//				$share_data['share_id'] = implode(',',$share_id);
				$share_data['order_id'] = $order_id;
				$share_data['uid'] = $this->now_user['uid'];
				//$share_data['num'] = count($share_id);
				$share_data['add_time'] = time();
				if($id = M('Sub_card_share_list')->add($share_data)){
					$this->success(array('msg'=>'分享成功','share_id'=>$id));

				}else{

					$this->error('分享失败');
				}
			}
		}

		//我的免单列表
		public function sub_card_list(){
			if(empty($this->now_user)){
				$this->error('您还没有登录',U('Login/index'));
			}
			$sub_card_list = D('Sub_card_order')->sub_card_list_by_uid($this->now_user['uid']);
			$this->assign('sub_card_list',$sub_card_list);
			$this->display();
		}

		public function share_order_detail(){
			if(empty($this->now_user)){
				$this->error_tips('您还没有登录',U('Login/index'));
			}
			$order_id = $_GET['order_id'];
			$share_uid = $_GET['share_uid'];
			$share_pass = M('Sub_card_user_pass')->where(array('share_uid'=>$share_uid,'fid'=>$order_id))->select();
			if(empty($share_pass)){
				$this->error_tips('订单不存在');
			}
			
			// $share_info = M('Sub_card_share_list')->where(array('share_id'=>array('like','%'.$share_pass[0]['id'].'%','order_id'=>$order_id)))->find();
		
			if(count($share_pass)>1){
				foreach($share_pass as $key=>$v){
					$tmp = M('Sub_card_share_list')->where(array('order_id'=>$order_id,'_string'=>"FIND_IN_SET({$v['id']},share_id)"))->find();
		
					if($key==0){
						$share_info =$tmp;
					}else{
						$share_info['num']++;
						$share_info['hadpull']++;
						$share_info['id'].=','.$tmp['id'];
					}
				}
				
		
			}else{
				$share_info = M('Sub_card_share_list')->where(array('order_id'=>$order_id,'_string'=>"FIND_IN_SET({$share_pass[0]['id']},share_id)"))->find();
			}
		
			$share_user = D('User')->get_user($share_info['uid']);
			$order_info = D('Sub_card_order')->get_order_by_id($order_id);
			$sub_card = D('Sub_card')->get_sub_card($order_info['sub_card_id']);
			$sub_card['share_num']  =count($share_pass);
			$sub_card['effective_days'] = $sub_card['effective_days']-floor((time()-$order_info['pay_time'])/86400);
			$this->assign('sub_card',$sub_card);
			$this->assign('share_info',$share_info);

			$this->assign('share_user',$share_user);
			$this->assign('share_num',M('Sub_card_user_pass')->where(array('fid'=>$order_id,'share'=>1))->count());
			$this->assign('consume_num',M('Sub_card_user_pass')->where(array('fid'=>$order_id,'status'=>1,'share_uid'=>$share_uid))->count());
			$this->display();

		}

		//分享页面
		public function order_share(){
			
			if(empty($_GET['share_id'])){
				$this->error_tips('非法请求');
			}
			if(empty($this->now_user)){
				$this->error_tips('您还没有登录,请登录后领取',U('Login/index'));
			}
			$order_id = $_GET['order_id'];
			$share_id = $_GET['share_id'];
			$share_info = M('Sub_card_share_list')->where(array('id'=>$share_id))->find();
			if($order_id!=$share_info['order_id']){
				$this->error_tips('非法请求');
			}
			if($share_info['uid']==$this->now_user['uid']){
				$this->error_tips('您是分享用户不能领取');
			}
			$share_user = D('User')->get_user($share_info['uid']);
			$order_info = D('Sub_card_order')->get_order_by_id($order_id);

			$pass_array = D('Sub_card_order')->get_user_pass($order_id);
			$sub_card = D('Sub_card')->get_sub_card($order_info['sub_card_id']);
//			$sub_card['effective_days'] = $sub_card['effective_days']-floor((time()-$order_info['pay_time'])/86400);
//
//			if($sub_card['effective_days']<=0 && $sub_card['use_time_type']==1){
//				$this->error_tips('该免单已过期');
//			}
			$share_num = M('Sub_card_user_pass')->where(array('fid'=>$order_id,'share'=>1,))->count();
			$share_can_get_num = M('Sub_card_user_pass')->where(array('fid'=>$order_id,'share'=>1,'status'=>0,'share_uid'=>0))->count();
			$my_hadpull_count = M('Sub_card_user_pass')->where(array('id'=>array('in',explode(',',$share_info['share_id'])),'share_uid'=>$this->now_user['uid']))->count();

			$this->assign('my_hadpull_count',$my_hadpull_count);
			$this->assign('share_info',$share_info);
			$this->assign('share_user',$share_user);
			$this->assign('sub_card',$sub_card);
			$this->assign('share_num',$share_num);
			$this->assign('consume_num',M('Sub_card_user_pass')->where(array('fid'=>$order_id,'status'=>1))->count());
			if($share_num==0){
				$this->error_tips('该订单未分享');
			}
			if($share_can_get_num==0 ){
				$this->error_tips('没有可以领取的免单',U('sub_card_list'));
			}
			$this->display();
		}

		public function my_share_list(){
			if(empty($this->now_user)){
				$this->error('您还没有登录,请登录后领取',U('Login/index'));
			}
			$share_info = M('Sub_card_share_list')->where(array('uid'=>$this->now_user['uid']))->select();
		}

		public function  ajax_get_share_order(){
			$order_info = D('Sub_card_order')->get_order_by_id($_POST['order_id']);
			$sub_card = D('Sub_card')->get_sub_card($order_info['sub_card_id']);
			$sub_card['effective_days'] = $sub_card['effective_days']-floor((time()-$order_info['pay_time'])/86400);
			$today_zero = strtotime(date('Ymd',time()));
			$where['ms.sub_card_id'] =$order_info['sub_card_id'];
			$where['ms.status'] = 1;
			$share_id = explode(',',$_POST['share_id']);
			if(count($share_id)>1){
				
				$share_pass_tmp = M('Sub_card_share_list')->where(array('id'=>array('in',$share_id)))->select();
				foreach($share_pass_tmp as $vs){
					$tmp[] = $vs['share_id'];
				}
				$share_pass['share_id'] = implode(',',$tmp);  
			}else{
				$share_pass = M('Sub_card_share_list')->where(array('id'=>$_POST['share_id']))->find();
			
			}
			$pass_array = D('Sub_card_order')->get_user_pass($_POST['order_id'],explode(',',$share_pass['share_id']));
		
			$order_info['store_id'] = explode(',',$order_info['store_id']);
			$order_info['store_id'] = array_unique($order_info['store_id']);
			$where['ms.store_id'] = array('in',$order_info['store_id']);
			$store_list = M('Sub_card_mer_apply')
				->join('AS ms LEFT JOIN '.C('DB_PREFIX').'merchant_store AS s ON ms.store_id = s.store_id LEFT JOIN '.C('DB_PREFIX').'merchant m ON m.mer_id=s.mer_id')
				->where($where)
				->field("ms.*,s.name,s.long,s.lat,s.adress,s.phone,s.pic_info,m.name as mer_name,ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$_POST['lat']}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$_POST['lat']}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$_POST['lng']}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli")
				->order('juli ASC')
				->select();
			
		
			$store_image_class = new store_image();
			$hadpull_store = array();
			$store_unhadpull_num =array();
			$store_id_arr =array();
			$consume_store =array();
			$store_unconsume_num =array();
			$unconsume_num =array();
			$hadpull_num = 0;
			$unhadpull_num = 0;
			$consume_num = 0;

			foreach ($pass_array as $p) {
				if($_POST['all']){
					if($p['share']==1){
						$store_id_arr[] = $p['store_id'];
					}
				}else{
					if($p['share_uid']>0){
						$store_id_arr[] = $p['store_id'];
					}
				}

				if(!$consume_store[$p['store_id']]){
					$consume_store[$p['store_id']] =0;
				}

				if($p['status']==1 ){
					$consume_num++;
					$consume_store[$p['store_id']] ++;
				}
				if($p['share_uid']>0 ){
					if($p['share_uid']==$this->now_user['uid'] && $p['status']!=1){
						$hadpull_store[$p['store_id']]++;
						$hadpull_num++;
					}
				}else{
					$store_unhadpull_num[$p['store_id']]++;
					$unhadpull_num++;
				}

			}
			$tmp_store_id =array();
			foreach ($store_list as $key=>&$v) {

				if(!in_array($v['store_id'],$store_id_arr)){
					unset($store_list[$key]);
					continue;
				}
				if(!empty($v['pic_info'])){
					$all_pic = $store_image_class->get_allImage_by_path($v['pic_info']);
					$v['pic_info'] = $all_pic[0];
				}
				$v['pic_lists'] = empty($v['pic_list'])?'':explode(';',$v['pic_list']);
				$v['hadpull'] = 0;
				if(in_array($v['store_id'],$hadpull_store)){
					$v['hadpull'] = $hadpull_store[$v['store_id']];
				}
				if(empty($store_unhadpull_num[$v['store_id']])){
					$v['num'] = 0;
				}else{
					$v['num'] = $store_unhadpull_num[$v['store_id']];
				}
				$v['user_hadpull_num'] = M('Sub_card_user_pass')->where(array('share_uid'=>$this->now_user['uid'],'fid'=>$order_info['order_id']))->count();
				if(empty($hadpull_store[$v['store_id']])){
					$v['hadpull_num'] = 0;
				}else{
					$v['hadpull_num'] = $hadpull_store[$v['store_id']];
				}
				$v['consume_num'] =$consume_store[$v['store_id']];
				$v['juli'] = getRange($v['juli']);
				if($sub_card['effective_days']<=0 && $sub_card['use_time_type']==1){
					$v['effective_days'] = $sub_card['effective_days'];
				}else if($v['end_time']>0){
					$v['effective_days'] =floor(($v['end_time']-$today_zero)/86400);
				}else{
					$v['effective_days'] = $sub_card['effective_days'];
				}
				$v['use_time_type'] = $sub_card['use_time_type'];
				$tmp_store_id[] = $store_list[$key];
			}
			$my_hadpull_count = M('Sub_card_user_pass')->where(array('id'=>array('in',explode(',',$share_pass['share_id'])),'share_uid'=>$this->now_user['uid']))->count();
			
			echo json_encode(array('store_list'=>$tmp_store_id,
				'hadpull_count'=>$my_hadpull_count,
				'unhadpull_count'=>count($store_list)-count($hadpull_store),
				'hadpull_num'=>$hadpull_num,
				'unhadpull_num'=>$unhadpull_num,
			));
			exit;
		}

		public function ajax_hadpull(){
			if(empty($this->now_user)){
				$this->error('您还没有登录',U('Login/index'));
			}
			$store_id = $_POST['store_id'];
			$share_id = $_POST['share_id'];
			$order_id = $_POST['order_id'];
			$share_info = M('Sub_card_share_list')->where(array('id'=>$share_id))->find();


			if($share_info['hadpull']>=$share_info['num']){
				$this->error('已领完了');
			}
			$can_hadpull_pass = M('Sub_card_user_pass')->where(array('fid'=>$order_id,'store_id'=>$store_id,'share_uid'=>0,'status'=>0,'id'=>array('in',explode(',',$share_info['share_id']))))->find();
			if(empty($can_hadpull_pass)){
				$this->error('改免单已被领完');
			}else{
				$date['share_uid'] = $this->now_user['uid'];
				$date['add_time'] = time();
				if(M('Sub_card_user_pass')->where(array('id'=>$can_hadpull_pass['id']))->save($date)){
					M('Sub_card_share_list')->where(array('id'=>$share_id))->setInc('hadpull');


					$this->success('领取成功');
				}else{
					$this->error('领取失败');
				}
			}

		}
		//导航
		public function map(){
			$this->display();
		}

		//二维码展示
		public function passqrcode(){
			import('@.ORG.phpqrcode');
			QRcode::png($_GET['pass'],false,2,8,2);

		}

		public function ajax_get_distance(){
			$lng1 = $_POST['lng1'];
			$lng2 = $_POST['lng2'];
			$lat1 = $_POST['lat1'];
			$lat2 = $_POST['lat2'];

			echo json_encode(array('distance'=>getRange(getDistance($lat1,$lng1,$lat2,$lng2))));exit;
		}




	}


?>
