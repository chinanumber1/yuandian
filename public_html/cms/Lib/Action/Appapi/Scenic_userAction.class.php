<?php
/*
                  _oo8oo_
                 o8888888o
                 88" . "88
                 (| -_- |)
                 0\  =  /0
               ___/'==='\___
             .' \\|     |// '.
            / \\|||  :  |||// \
           / _||||| -:- |||||_ \
          |   | \\\  -  /// |   |
          | \_|  ''\---/''  |_/ |
          \  .-\__  '-'  __/-.  /
        ___'. .'  /--.--\  '. .'___
     ."" '<  '.___\_<|>_/___.'  >' "".
    | | :  `- \`.:`\ _ /`:.`/ -`  : | |
    \  \ `-.   \_ __\ /__ _/   .-` /  /
=====`-.____`.___ \_____/ ___.`____.-`=====
                  `=---=`
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
          佛祖保佑		永无bug
 * APP端前台页面----用户相关
 *   Writers    hanlu
 *   BuildTime  2016/10/27 15:49
 */
class Scenic_userAction extends BaseAction{
	private $user_info;
	# 构造方法
	public function __construct(){
		parent::__construct();
		$ticket = I('ticket', false);
		if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info) {
                $this->user_info = $info['uid'];
            }
        }else{
			$this->returnCode('20044013');
        }
    }
	# 个人中心
	public function index(){
		$User = M('User')->where(array('uid'=>$this->user_info))->find();
		if(empty($User['avatar'])){
            $avatar   =   $this->config['site_url'] . '/static/images/user_avatar.jpg';
        }
        $arr	=	array(
			'avatar'	=>	$avatar,
			'nickname'	=>	$User['nickname'],
			'real_name'	=>	$User['real_name'],
        );
        $this->returnCode(0,$arr);
	}
	# 订单列表
	public function ticket_order_list_json(){
		$where['user_id']	=	$this->user_info;
		$page	=	I('page',1);
		$field	=	array('order_id','ticket_time','add_time','order_status','paid','scenic_id');
		$count	=	M('Scenic_order')->where($where)->count();
		$user_order	=	D('Scenic_order')->get_user_order($where,$page,$field);
		$date	=	date('Y-m-d',$_SERVER['REQUEST_TIME']);
		if($user_order){
			foreach($user_order as &$v){
				# 订单未支付，过期时间30分钟，默认关闭状态，订单状态改为5
				if($v['paid'] == 1){
					$branch	=	1800-($_SERVER['REQUEST_TIME']-$v['add_time']);
					if($branch < 0){
						$v['order_status']	=	5;
						D('Scenic_order')->save_order(array('order_id'=>$v['order_id']),array('order_status'=>5));
					}
				}
				# 购买门票了，到了时间未入园，默认关闭状态，订单状态改为6
				if($v['order_status'] == 1 && $v['paid'] == 2){
					// $ticket_data	=	strtotime($v['ticket_time']);
					// $ticket_data	=	$ticket_data+(60*60*24);
					$ticket_data	=	$v['endtime'];
					if($_SERVER['REQUEST_TIME'] >= $ticket_data){
						$v['order_status']	=	6;
						D('Scenic_order')->save_order(array('order_id'=>$v['order_id']),array('order_status'=>6));
					}
				}
				$field	=	array('scenic_title');
				$scenic_list	=	D('Scenic_list')->get_one_list(array('scenic_id'=>$v['scenic_id']),$field);
				$v['scenic_title']	=	$scenic_list['scenic_title'];
				$v['add_time']	=	date('Y-m-d H:i',$v['add_time']);
				$ticket_time	=	explode('-',$v['ticket_time']);
				if($ticket_time[1] < 10){
					$ticket_time[1]	=	'0'.$ticket_time[1];
				}
				if($ticket_time[2] < 10){
					$ticket_time[2]	=	'0'.$ticket_time[2];
				}
				$v['ticket_time']	=	$ticket_time[0].'-'.$ticket_time[1].'-'.$ticket_time[2];
			}
		}else{
			$default_img	=	$this->config['site_url'].'/tpl/Wap/pure/static/scenic/images/s_03.png';
		}
		$arr	=	array(
			'order'	=>	$user_order,
			'default_img'	=>	isset($default_img)?$default_img:'',
			'count'	=>	ceil($count/10),
		);
		$this->returnCode(0,$arr);
	}
	# 取消订单
	public function cancel_order(){
		$where['order_id']	=	$_POST['order_id'];
		$order = D('Scenic_order')->get_one_order($where);
		$now_ticket = M('Scenic_ticket')->where(array('ticket_id'=>$order['ticket_id']))->find();
		if(!$now_ticket['is_refund']||date('Y-m-d')>$order['ticket_time']){
			$this->returnCode('40000021');
		}
		if($order['order_status']!=1){
			$this->returnCode('40000018');
		}elseif($order['pay_type']=='weixin'){
			$import_result = import('@.ORG.pay.weixin');
			$pay_method = D('Config')->get_pay_method();
			$pay_class = new Weixin($order,$order['payment_money'],'weixin',$pay_method['weixin']['config'],$this->user_session,1);
			$go_refund_param = $pay_class->refund();
			if(empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok'){
				$date['order_status']=4;
				if(!M('Scenic_order')->where($where)->save($date)){
					$this->returnCode('40000019');
				}
			}
		}else{
			$date['order_status']=4;
			$date['last_time']=$_SERVER['REQUEST_TIME'];
			$date['refund_fee']=$order['balance_pay']+$order['payment_money'];
			if(M('Scenic_order')->where($where)->save($date)){
				D('User')->add_money($order['user_id'],$date['refund_fee'],'景区门票退款');
			}else{
				$this->returnCode('40000019');
			}
		}
		$this->returnCode(0);
	}
	# 订单详情
	public function ticket_order_details(){
		# 订单详情
		$where['order_id']	=	$_POST['order_id'];
		$field	=	array('order_id','ticket_time','order_status','paid','scenic_id','family_id','ticket_id');
		$user_order	=	D('Scenic_order')->get_one_order($where,$field);
		# 订单未支付，过期时间30分钟，默认关闭状态，订单状态改为5
		if($user_order['paid'] == 1){
			$branch	=	1800-($_SERVER['REQUEST_TIME']-$user_order['add_time']);
			if($branch < 0){
				$user_order['order_status']	=	5;
				D('Scenic_order')->save_order(array('order_id'=>$user_order['order_id']),array('order_status'=>5));
			}
		}
		# 购买门票了，到了时间未入园，默认关闭状态，订单状态改为6
		if($user_order['order_status'] == 1 && $user_order['paid'] == 2){
			// $ticket_data	=	strtotime($user_order['ticket_time']);
			// $ticket_data	=	$ticket_data+(60*60*24);
			$ticket_data	=	$user_order['endtime'];
			if($_SERVER['REQUEST_TIME'] >= $ticket_data){
				$user_order['order_status']	=	6;
				D('Scenic_order')->save_order(array('order_id'=>$user_order['order_id']),array('order_status'=>6));
			}
		}
		$user_order['order_total']	=	floor($user_order['order_total']);
		if(empty($user_order)){
			$this->returnCode('40000041');
		}
		# 订单商品
		$field	=	array('com_id','code','type','price','type_id','status');
		$order_com	=	D('Scenic_order')->get_order_com($where,$field);
		if(empty($order_com)){
			$this->returnCode('40000041');
		}
		$field	=	array('scenic_id','scenic_title','scenic_pic','guide_price');
		$scenic_list	=	D('Scenic_list')->get_one_list(array('scenic_id'=>$user_order['scenic_id']),$field);
		$ticket	=	array();
		$park	=	array();
		$guide	=	array();
		foreach($order_com as $v){
			if($v['type'] == 1){
				$v['price']	=	intval($v['price']);
				$ticket[]	=	$v;
			}else if($v['type'] == 2){
				$park[]		=	$v;
			}else if($v['type'] == 3){
				$guide[]	=	$v;
			}
		}
		# 门票信息
		if(empty($ticket)){
			$this->returnCode('40000041');
		}else{
			$ticket_id['ticket_id']	=	$user_order['ticket_id'];
			$field	=	array('ticket_title','ticket_cue','park_intr','ticket_id');
			$scenic_ticket	=	D('Scenic_ticket')->get_scenic_one_ticket($ticket_id,$field);
			$ticket_count	=	count($ticket);
		}
		# 车位信息
		if(!empty($park)){
			$park_id['parking_id']	=	$park[0]['type_id'];
			$field	=	array('parking_id','parking_name','parking_address','parking_long','parking_lat');
			$scenic_prak	=	D('Scenic_park')->get_user_park($park_id,$field);
			$prak_count		=	count($park);
		}
		$scenic_image_class = new scenic_image();
		# 向导信息
		if(!empty($guide)){
			$guide_id['guide_id']	=	$guide[0]['type_id'];
			$field	=	array('guide_id','guide_name','guide_gender','guide_age','guide_pig','guide_phone');
			$scenic_guide	=	D('Scenic_guide')->city_get_one_guide($guide_id,$field);
			$scenic_guide['pic'] = $scenic_image_class->get_image_by_path($scenic_guide['guide_pig'],$this->config['site_url'],'guide','s');
			$guide_number	=	1;
		}else{
			$guide_number	=	0;
		}
		$scenic_ticket['ticket_cue'] = htmlspecialchars_decode($scenic_ticket['ticket_cue']);
		$scenic_ticket['park_intr'] = htmlspecialchars_decode($scenic_ticket['park_intr']);
		$arr	=	array(
			'prak_count'	=>	$prak_count,
			'ticket_count'	=>	$ticket_count,
			'prak_price'	=>	floor($park[0]['price']),
			'ticket_price'	=>	floor($scenic_ticket['price']),
			'ticket_prices'	=>	floor($ticket[0]['price']),
			'total_price'	=>	floor($park[0]['price']*$prak_count)+floor($scenic_list['guide_price']*$guide_number)+floor($ticket[0]['price']*$ticket_count),
		);
		$family_id = explode(",",$user_order['family_id']);
		$field	=	array('family_id','family_name','certificates');
		foreach($family_id as $v){
			$family[]	=	D('Scenic_family')->get_one(array('family_id'=>$v),$field);
		}
		# 图片
		$tmp_pic_arr = explode(';',$scenic_list['scenic_pic']);
		$scenic_list['pic'] = $scenic_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'config','s');
		unset($scenic_list['scenic_pic']);
		$array	=	array(
			'user_order'	=>	$user_order,
			'scenic_list'	=>	$scenic_list,
			'scenic_ticket'	=>	$scenic_ticket,
			'scenic_prak'	=>	isset($scenic_prak)?$scenic_prak:array(),
			'scenic_guide'	=>	isset($scenic_guide)?$scenic_guide:array(),
			'ticket'	=>	isset($ticket)?$ticket:array(),
			'park'	=>	isset($park)?$park:array(),
			'arr'	=>	$arr,
			'family'	=>	$family,
		);
		$this->returnCode(0,$array);
	}
	# 向导订单
	public function aguide_order_list(){
		$where['user_id']	=	$this->user_info;
		$page	=	I('page',1);
		$this->aguide_order_refresh($where['user_id'],'user_id');
		$user_order	=	D('Scenic_aguide')->get_aguide_order_list($where,$page);
		if($user_order){
			foreach($user_order as $k=>&$v){
				$aguide	=	D('Scenic_aguide')->get_one_aguide(array('guide_id'=>$v['guide_id']));
				$user	=	D('User')->get_user($aguide['user_id']);
				$guide_user	=	D('Scenic_aguide')->city_get_one_aguide(array('guide_id'=>$v['guide_id']));
				if($v['pay_status'] == 2 && $v['rela_status'] == 1){
					$ticket_data	=	strtotime($v['end_time']);
					$ticket_data	=	$ticket_data+(60*60*24);
					if($_SERVER['REQUEST_TIME'] >= $ticket_data){
						$v['rela_status']	=	2;
						D('Scenic_order')->save_guide_order(array('order_id'=>$v['order_id']),array('rela_status'=>2));
					}
				}
				if($v['family_id']){
					$family_id = explode(",",$v['family_id']);
					$field	=	array('family_id','family_name','certificates');
					foreach($family_id as $zzz){
						$family[]	=	D('Scenic_family')->get_one(array('family_id'=>$zzz),$field);
					}
					$family_number	=	count($family);
				}
				if($user['avatar']){
					$avatar	=	$user['avatar'];
				}else{
					$avatar	=	$this->config['site_url'] . '/static/images/user_avatar.jpg';
				}
				$arr[]	=	array(
					'phone'	=>	$v['phone'],
					'number'	=>	$v['number'],
					'total_price'	=>	floor($v['total_price']),
					'start_time'	=>	$v['start_time'],
					'end_time'	=>	$v['end_time'],
					'rela_status'	=>	$v['rela_status'],
					'pay_status'	=>	$v['pay_status'],
					'add_time'	=>	date('Y-m-d H:i',$v['create_time']),
					'family'	=>	$family,
					'family_number'	=>	$family_number,
					'avatar'	=>	$avatar,
					'guide_name'	=>	$guide_user['guide_name'],
				);
				unset($family,$user);
			}
		}
		$this->returnCode(0,$arr);
	}
	# 刷新向导订单
	public function aguide_order_refresh($guide_id,$where_id){
		$start_tiem	=	date('Y-m-d',$_SERVER['REQUEST_TIME']);
		$scenic_aguide	=	D('Scenic_aguide')->order_refresh($guide_id,$where_id,$start_tiem);
		//导游服务验证，验证后加钱
		$user_id	=	$this->user_info;
		$aguide_service_verify	=	D('Scenic_aguide')->verify($user_id);
	}
	# 实名认证
	public function add_guide(){
		$where['uid']	=	$this->user_info;
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
			$auth_data['uid']	=	$this->user_info;
			$user_authentication	=	M('User_authentication')->data($auth_data)->add();
		}
		if(empty($user_authentication)){
			$this->returnCode('40000031');
		}else{
			$data['truename']	=	$_POST['user_truename'];
			$data['real_name']	=	2;
			$save	=	D('User')->scenic_save_user($where,$data);
			//if($save){
//				$_SESSION['user']['real_name']	=	2;
//				$_SESSION['user']['truename']	=	$data['truename'];
//			}
		}
		$this->returnCode(0);
	}
	# 实名认证页面展示页面
	public function guide(){
		$authentication	=	D('User_authentication')->field(true)->order('authentication_time DESC')->where(array('uid'=>$this->user_info))->find();
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
		}
		$scenic_aguide	=	D('Scenic_aguide')->field('guide_id')->where(array('user_id'=>$this->user_info))->find();
		unset($authentication['hand_authentication'],$authentication['authentication_time'],$authentication['examine_time']);
		$arr	=	array(
			'authentication'	=>	isset($authentication)?$authentication:array(),
			'scenic_aguide'	=>	isset($scenic_aguide)?$scenic_aguide:array(),
		);
		$this->returnCode(0,$arr);
	}
	# 申请导游（编辑导游）
	public function guide_release(){
		$scenic_aguide	=	D('Scenic_aguide')->get_one_aguide(array('user_id'=>$this->user_info));
		if($scenic_aguide){
			$scenic_aguide['guide_price']	=	intval($scenic_aguide['guide_price']);
		}
		$where['is_open']	=	1;
		$where['area_type']	=	1;
		$scenic_area	=	D('Area')->scenic_get_area_list_pc($where);
		if($scenic_aguide){
			foreach($scenic_area as &$v){
				unset($v['area_sort'],$v['first_pinyin'],$v['is_open'],$v['area_url'],$v['area_ip_desc'],$v['area_type'],$v['is_hot']);
				foreach($v['son'] as &$vv){
					unset($vv['area_sort'],$vv['first_pinyin'],$vv['is_open'],$vv['area_url'],$vv['area_ip_desc'],$vv['area_type'],$vv['is_hot']);
				}
			}
		}
		$arr	=	array(
			'scenic_aguide'	=>	isset($scenic_aguide)?$scenic_aguide:array(),
			'scenic_area'	=>	$scenic_area,
		);
		$this->returnCode(0,$arr);
	}
	# 申请导游（编辑导游）的提交
	public function guide_release_json(){
		$uid	=	$this->user_info;
		$User_authentication	=	M('User_authentication')->field(true)->order('authentication_time DESC')->where(array('uid'=>$uid))->find();
		if(empty($User_authentication)){
			$this->returnCode('40000014');
		}
		$arr	=	array(
			'user_id'		=>	$this->user_info,
			'guide_nickname'=>	$_POST['guide_nickname'],
			'guide_life'	=>	$_POST['guide_life'],
			'province_id'	=>	$_POST['province'],
			'city_id'		=>	$_POST['city'],
			'guide_autograph'=>	$_POST['guide_autograph'],
			'guide_intr'	=>	$_POST['guide_intr'],
			'guide_phone'	=>	$_POST['guide_phone'],
			'guide_price'	=>	$_POST['guide_price'],
			'guide_sex'		=>	$_POST['guide_sex'],
			'date'			=>	$_POST['date'],
			'guide_pic'		=>	$_POST['image4'],
		);
		if($_POST['type'] == 2){
			$arr['guide_name']	=	$_POST['guide_name'];
			$arr['guide_card']	=	$_POST['guide_card'];
			$arr['guide_card_img']	=	$_POST['image1'];
			$arr['guide_card_back_img']	=	$_POST['image2'];
			$arr['create_time']	=	$_SERVER['REQUEST_TIME'];
			$add_scenic_aguide	=	D('Scenic_aguide')->add_aguide($arr);
		}else{
			$where['guide_id']	=	$_POST['guide_id'];
			$arr['update_time']	=	$_SERVER['REQUEST_TIME'];
			$add_scenic_aguide	=	D('Scenic_aguide')->edit_aguide($where,$arr);
		}
		if($add_scenic_aguide){
			$url	=	$this->config['site_url'].U('guide_service');
			$this->returnCode(0,$url);
		}else{
			$this->returnCode('40000006');
		}
	}
	# 向导从新审核
	public function again_guide_release(){
		$uid	=	$this->user_info;
		$User_authentication	=	M('User_authentication')->field(true)->order('authentication_time DESC')->where(array('uid'=>$uid))->find();
		if(empty($User_authentication)){
			$this->returnCode('40000014');
		}
		$arr['guide_name']	=	$_POST['guide_name'];
		$arr['guide_card']	=	$_POST['guide_card'];
		$arr['guide_card_img']		=	$_POST['image1'];
		$arr['guide_card_back_img']		=	$_POST['image2'];
		$arr['guide_status']=	2;
		$arr['update_time']	=	$_SERVER['REQUEST_TIME'];
		$where['guide_id']	=	$_POST['guide_id'];
		$add_scenic_aguide	=	D('Scenic_aguide')->edit_aguide($where,$arr);
		if($add_scenic_aguide){
			$this->returnCode(0);
		}else{
			$this->returnCode('40000006');
		}
	}
	# 导游展示页面（包括订单）
	public function guide_service(){
		$where['user_id']	=	$this->user_info;
		$scenic_aguide	=	D('Scenic_aguide')->get_one_aguide($where);
		$this->aguide_order_refresh($scenic_aguide['guide_id'],'guide_id');
		$scenic_aguide['guide_price']	=	intval($scenic_aguide['guide_price']);
		$scenic_aguide_order	=	D('Scenic_aguide')->get_aguide_order_list_me(array('guide_id'=>$scenic_aguide['guide_id'],'pay_status'=>2,'rela_status'=>array('between',array('1','3'))));
		if($scenic_aguide_order){
			foreach($scenic_aguide_order as $kk=>&$vv){
				if(empty($vv)){
					continue;
				}
				$vv['guide_price']	=	intval($vv['guide_price']);
				if($vv['family_id']){
					$family_id = explode(",",$vv['family_id']);
					foreach($family_id as $zzz){
						$scenic_aguide_order[$kk]['family'][]	=	D('Scenic_family')->get_one(array('family_id'=>$zzz));
					}
				}
			}
		}
		$store_image_class = new scenic_image();
		$tmp_pic_arr = explode(';',$scenic_aguide['guide_pic']);
		foreach($tmp_pic_arr as $k=>$v){
			if(empty($v)){
				continue;
			}
			$scenic_aguide['pic'][$k] = $store_image_class->get_image_by_path($v,$this->config['site_url'],'aguide','1');
		}
		$scenic_aguide['age']	=	D('User')->age($scenic_aguide['date']);
		unset($scenic_aguide['guide_card_img'],$scenic_aguide['guide_card_back_img'],$scenic_aguide['update_time'],$scenic_aguide['remarks_time'],$scenic_aguide['create_time'],$scenic_aguide['guide_pic']);
		$arr	=	array(
			'scenic_aguide'	=>	$scenic_aguide,
			'scenic_aguide_order'	=>	$scenic_aguide_order,
		);
		$this->returnCode(0,$arr);
	}
	# 关闭向导
	public function close_guide(){
		$data['guide_status']	=	$_POST['guide_status'];
		$data['update_time']	=	$_SERVER['REQUEST_TIME'];
		$where['guide_id']	=	$_POST['guide_id'];
		if(empty($data) && empty($where)){
			$this->returnCode('40000007');
		}
		$scenic_aguide	=	D('Scenic_aguide')->save_aguide($where,$data);
		if($scenic_aguide){
			$this->returnCode(0);
		}else{
			$this->returnCode('40000007');
		}
	}
	# 我的结伴
	public function mate(){
		$city_ids	=	$_POST['city_id'];
		$start_tiem	=	date('Y-m-d',$_SERVER['REQUEST_TIME']);
		$page	=	I('page',1);
		$scenic_min_mate	=	D('Scenic_mate')->save_all_scenic_mate_time($city_ids,$start_tiem);
		$city_id['user_id']	=	$this->user_info;
		$city_id['is_mate']	=	1;
		$scenic_mate_order	=	D('Scenic_mate')->get_all_scenic_mate_order($city_id,$page);
		foreach($scenic_mate_order as $v){
			$scenic_mate[]	=	D('Scenic_mate')->get_one_scenic_mate(array('mate_id'=>$v['mate_id']));
		}
		if($scenic_mate){
			foreach($scenic_mate as &$v){
				$scenic_list	=	D('Scenic_list')->get_one_list(array('scenic_id'=>$v['scenic_id']));
				$v['create_time']	=	date('Y-m-d H:i',$v['create_time']);
				$v['scenic_title']	=	$scenic_list['scenic_title'];
				unset($v['end_time'],$v['mate_status'],$v['order_id'],$v['scenic_id'],$v['province_id'],$v['city_id'],$scenic_list);
			}
		}else{
			$this->returnCode('40000008');
		}
		$this->returnCode(0,$scenic_mate);
	}
	# 我的参与结伴
	public function mate_partake(){
		$city_ids	=	$_POST['city_id'];
		$start_tiem	=	date('Y-m-d',$_SERVER['REQUEST_TIME']);
		$page	=	I('page',1);
		$scenic_min_mate	=	D('Scenic_mate')->save_all_scenic_mate_time($city_ids,$start_tiem);
		$city_id['user_id']	=	$this->user_info;
		$city_id['is_mate']	=	2;
		$scenic_mate_order	=	D('Scenic_mate')->get_all_scenic_mate_order($city_id,$page);
		foreach($scenic_mate_order as $k=>$v){
			$scenic_mate[]	=	D('Scenic_mate')->get_one_scenic_mate(array('mate_id'=>$v['mate_id']));
		}
		if($scenic_mate){
			foreach($scenic_mate as $k=>&$v){
				$v['scenic_mate']	=	D('Scenic_mate')->get_one_scenic_mate_order(array('mate_id'=>$v['mate_id'],'is_mate'=>1));
				$scenic_list	=	D('Scenic_list')->get_one_list(array('scenic_id'=>$v['scenic_id']));
				$v['scenic_title']	=	$scenic_list['scenic_title'];
				$scenic_user	=	D('User')->get_user($v['scenic_mate']['user_id']);
				$v['create_time']	=	date('Y-m-d H:i',$v['create_time']);
				$v['truename']	=	$scenic_user['truename'];
				if($scenic_user['avatar']){
					$v['avatar']	=	$scenic_user['avatar'];
				}else{
					$v['avatar']	=	$this->config['site_url'] . '/static/images/user_avatar.jpg';
				}
				unset($v['end_time'],$v['mate_status'],$v['order_id'],$v['scenic_id'],$v['province_id'],$v['city_id'],$scenic_list,$scenic_user,$v['scenic_mate']);
			}
		}else{
			$this->returnCode('40000008');
		}
		$this->returnCode(0,$scenic_mate);
	}
	# 我的结伴详情
	public function mate_details(){
		$where['mate_id']	=	$_POST['mate_id'];
		# 单个结伴详情
		$field	=	array('mate_id','order_id','mate_intr','people_number','words_number','mate_status','create_time','start_time','scenic_id');
		$scenic_mate	=	D('Scenic_mate')->get_one_scenic_mate($where,$field);
		# 结伴发起人
		$promoter	=	D('Scenic_mate')->get_one_scenic_mate_order(array('mate_id'=>$scenic_mate['mate_id'],'is_mate'=>1));
		# 景区
		$scenic_title	=	D('Scenic_list')->get_one_list(array('scenic_id'=>$scenic_mate['scenic_id']),array('scenic_title'));
		$scenic_mate['scenic_title']	=	$scenic_title['scenic_title'];
		# 发起人详情
		$scenic_user	=	D('User')->get_user($promoter['user_id']);
		if(empty($scenic_user['avatar'])){
			$scenic_mate['avatar']	=	$this->config['site_url'] . '/static/images/user_avatar.jpg';
		}else{
			$scenic_mate['avatar']	=	$scenic_user['avatar'];
		}
		$scenic_mate['nickname']	=	$scenic_user['nickname'];
		$scenic_mate['create_time']	=	date('Y-m-d H:i',$scenic_mate['create_time']);
		# 结伴响应人
		$scenic_mate['scenic_mates']	=	D('Scenic_mate')->get_all_scenic_mate_order(array('mate_id'=>$scenic_mate['mate_id']));
		# 响应人详情
		foreach($scenic_mate['scenic_mates'] as $k=>&$v){
			$scenic_mates_user	=	D('User')->get_user($v['user_id']);
			if(empty($scenic_mates_user['avatar'])){
				$scenic_mate['scenic_mates'][$k]['avatar']	=	$this->config['site_url'] . '/static/images/user_avatar.jpg';
			}else{
				$scenic_mate['scenic_mates'][$k]['avatar']	=	$scenic_mates_user['avatar'];
			}
			$scenic_mate['scenic_mates'][$k]['nickname']	=	$scenic_mates_user['nickname'];
			$scenic_mate['scenic_mates'][$k]['sex']	=	$this->config['site_url']."/static/weather/dixdt_0{$scenic_mates_user['sex']}.png";
			unset($scenic_mates_user,$v['is_car'],$v['is_mate'],$v['car_seat'],$v['create_time'],$v['rela_status'],$v['user_id'],$v['mate_id'],$v['day_number']);
		}
		$words	=	array(
			'mate_id'	=>	$where['mate_id'],
			'words_sid'	=>	0,
		);
		$scenic_mate['words']	=	D('Scenic_mate')->get_scenic_mate_words($words,$promoter['user_id']);
		foreach($scenic_mate['words'] as $w=>&$wor){
			if($wor['user']['avatar']){
				$wor['avatar']	=	$wor['user']['avatar'];
			}else{
				$wor['avatar']	=	$this->config['site_url'] . '/static/images/user_avatar.jpg';
			}
			$wor['nickname']	=	$wor['user']['nickname'];
			unset($wor['user']);
		}
		# 相关游伴
		$mate_where['city_id']	=	$_POST['city_id'];
		$mate_where['mate_id']	=	array('neq',$scenic_mate['mate_id']);
		$scenic_mate['hot']	=	D('Scenic_mate')->get_hot_scenic_mate($mate_where,'start_time DESC');
		foreach($scenic_mate['hot'] as &$v){
			$v['scenic_user']	=	D('User')->get_user($v['scenic_mate']['user_id']);
			if(empty($v['scenic_user']['avatar'])){
				$v['avatar']	=	$this->config['site_url'] . '/static/images/user_avatar.jpg';
			}else{
				$v['avatar']	=	$v['scenic_user']['avatar'];
			}
			$title		=	D('Scenic_list')->get_one_list(array('scenic_id'=>$v['scenic_id']),array('scenic_title'));
			$v['scenic_list']	=	$title['scenic_title'];
			$v['create_time']	=	date('Y-m-d H:i',$v['create_time']);
			unset($v['scenic_user'],$v['province_id'],$v['city_id'],$v['end_time'],$v['scenic_id'],$v['order_id'],$v['people_number'],$v['words_number'],$v['mate_status'],$v['start_time']);
		}
		$this->assign('scenic_mate',$scenic_mate);
		$this->returnCode(0,$scenic_mate);
	}
	# 报名
	public function sign_up(){
		$mate_id	=	$_POST['mate_id'];
		if(empty($mate_id)){
			$this->returnCode('40000009');
		}
		$where	=	array(
			'user_id'	=>	$this->user_info,
			'mate_id'	=>	$mate_id,
		);
		$mate_where	=	D('Scenic_mate')->get_one_scenic_mate_order($where);
		if($mate_where){
			$this->returnCode('40000010');
		}
		$arr	=	array(
			'mate_id'	=>	$mate_id,
			'phone'		=>	$_POST['phone'],
			'user_id'	=>	$this->user_info,
			'rela_status'	=>	1,
			'create_time'	=>	$_SERVER['REQUEST_TIME'],
			'is_mate'	=>	2,
		);
		$scenic_mate	=	D('Scenic_mate')->add_scenic_mate_order($arr);
		if($scenic_mate){
			M('Scenic_mate')->where(array('mate_id'=>$mate_id))->setInc('people_number');
			$this->returnCode(0);
		}else{
			$this->returnCode('40000009');
		}
	}
	# 回复结伴
	public function reply_json(){
		$data	=	array(
			'user_id'	=>	$this->user_info,
			'mate_id'	=>	$_POST['mate_id'],
			'words_sid'	=>	$_POST['words_id'],
			'words_content'	=>	$_POST['words_content'],
			'create_time'	=>	$_SERVER['REQUEST_TIME'],
		);
		$scenic_mate	=	D('Scenic_mate')->add_scenic_mate_words($data);
		if($scenic_mate){
			M('Scenic_mate')->where(array('mate_id'=>$_POST['mate_id']))->setInc('words_number');
			$this->returnCode(0);
		}else{
			$this->returnCode('40000011');
		}
	}
	# 评论
	public function comment(){
		$type		=	$_POST['type'];
		$order_id	=	$_POST['order_id'];
		$reply_content	=	$_POST['reply_content'];
		$reply_score	=	$_POST['reply_score'];
		if($type == 1){
			$scenic_order	=	D('Scenic_order')->one_order(array('order_id'=>$order_id));
		}else if($type == 2){
			$scenic_order	=	D('Scenic_order')->one_order(array('order_id'=>$order_id));
		}
		if($scenic_order){
			$arr	=	array(
				'scenic_id'	=>	$scenic_order['scenic_id'],
				'order_id'	=>	$scenic_order['order_id'],
				'user_id'	=>	$this->user_info,
				'ticket_id'	=>	$scenic_order['ticket_id'],
				'reply_content'	=>	$reply_content,
				'reply_score'	=>	$reply_score,
				'reply_type'	=>	$type,
				'reply_time'	=>	$_SERVER['REQUEST_TIME'],
				'reply_ip'	=>	get_client_ip(1),
				'status'	=>	0,
			);
			$add	=	D('Scenic_reply')->add_scenic_reply($arr);
			if($add){
				if($type == 1){
					D('Scenic_order')->save_order(array('order_id'=>$scenic_order['order_id']),array('order_status'=>3));
					D('Scenic_reply')->get_ticket_reply_count(array('scenic_id'=>$scenic_order['scenic_id'],'reply_type'=>1));
				}else if($type == 2){
					D('Scenic_aguide')->save_guide_order(array('order_id'=>$scenic_order['order_id']),array('rela_status'=>3));
					D('Scenic_reply')->get_aguide_reply_count(array('scenic_id'=>$scenic_order['scenic_id'],'reply_type'=>2));
				}
				$this->returnCode(0);
			}else{
				$this->returnCode('40000013');
			}
		}else{
			$this->returnCode('40000012');
		}
	}
	# 我的收藏
	public function collection(){
		$where['user_id']	=	$this->user_info;
		$page	=	I('page',1);
		$count	=	M('Scenic_collection')->where($where)->count();
		$scenic_collection	=	M('Scenic_collection')->field(array('scenic_id','collection_id'))->where($where)->page($page,10)->select();
		if($scenic_collection){
			$scenic_image_class = new scenic_image();
			foreach($scenic_collection as &$v){
				$scenic_list	=	D('Scenic_list')->get_one_list(array('scenic_id'=>$v['scenic_id']));
				$v['scenic_title']	=	$scenic_list['scenic_title'];
				$v['money']	=	floatval($scenic_list['money']);
				$v['reply_count']	=	$scenic_list['reply_count'];
				$tmp_pic_arr = explode(';',$scenic_list['scenic_pic']);
				$v['pic'] = $scenic_image_class->get_image_by_path($tmp_pic_arr[0],$this->config['site_url'],'config','s');
			}
		}else{
			$default_img	=	$this->config['site_url'].'/tpl/Wap/pure/static/scenic/images/s_03.png';
		}
		$arr	=	array(
			'scenic_list'	=>	$scenic_collection,
			'default_img'	=>	isset($default_img)?$default_img:'',
			'count'	=>	ceil($count/10),
		);
		$this->returnCode(0,$arr);
	}
	# 出行人列表
	public function travel_person(){
		$page	=	I('page',1);
		$where	=	array('user_id'=>$this->user_info);
		$count	=	M('Scenic_family')->where($where)->count();
		$scenic_family	=	D('Scenic_family')->get_all_list($where,true,$page);
		if($scenic_family){
			foreach($scenic_family as &$v){
				if($v['gender'] == 1){
					$v['gender']	=	'男';
				}else{
					$v['gender']	=	'女';
				}
			}
		}else{
			$default_img	=	$this->config['site_url'].'/tpl/Wap/pure/static/scenic/images/s_02.jpg';
		}
		$arr	=	array(
			'scenic_list'	=>	$scenic_family,
			'default_img'	=>	isset($default_img)?$default_img:'',
			'count'	=>	ceil($count/10),
		);
		$this->returnCode(0,$arr);
	}
	# 获取单条出行人
	public function travel_person_one(){
		$find	=	M('Scenic_family')->where(array('family_id'=>$_POST['family_id']))->find();
		if($find){
			$this->returnCode(0,$find);
		}else{
			$this->returnCode('40000059');
		}
	}
	# 新增出行人
	public function add_travel(){
		$is_repeat	=	D('Scenic_family')->is_repeat($_POST['certificates'],$_POST['family_id'],$this->user_info);
		if($is_repeat){
			$this->returnCode('40000033');
		}
		$arr	=	array(
			'family_name'	=>	$_POST['family_name'],
			'gender'	=>	$_POST['gender'],
			'phone'		=>	$_POST['phone'],
			'certificates'	=>	$_POST['certificates'],
		);
		if($_POST['family_id'] != 0){
			$add_family	=	D('Scenic_family')->edit_family(array('family_id'=>$_POST['family_id']),$arr);
		}else{
			$arr['user_id']		=	$this->user_info;
			$arr['add_time']	=	$_SERVER['REQUEST_TIME'];
			$add_family	=	D('Scenic_family')->add_family($arr);
		}
		if($add_family){
			$this->returnCode(0);
		}else{
			$this->returnCode('40000029');
		}
	}
}
?>