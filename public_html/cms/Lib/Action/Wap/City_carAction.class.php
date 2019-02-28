<?php
class City_carAction extends BaseAction{
	public function __construct(){
		parent::__construct();
		if(IS_GET){
			if(!$this->is_wexin_browser || empty($_SESSION['openid'])){
				$this->error_tips('该功能现仅在微信端使用',U('Home/index'));
			}
			
			$city_arr = array('京','津','冀','晋','蒙','辽','吉','黑','沪','苏','浙','皖','闽','赣','鲁','豫','鄂','湘','粤','桂','琼','渝','川','贵','云','藏','陕','甘','青','宁','新');
			$this->assign('city_arr',$city_arr);
		}
		
	}
    public function index(){
		$car_list = D('Smart_city_car')->where(array('uid'=>$this->user_session['uid']))->order('`car_id` DESC')->select();
		$this->assign('car_list',$car_list);
		
    	$this->display();
    }
	public function add(){
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				$this->error_tips('请先进行登录！',U('Login/index',$location_param));
			}else{
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				redirect(U('Login/index',$location_param));
			}
		}
    	$this->display();
    }
	public function motify(){
		$data['car_area'] = $_POST['car_area'];
		$data['car_num'] = $_POST['carnum'];
		$data['car_phone'] = $_POST['phone'];
		$data['tip_type'] = intval($_POST['tip_type']);
		$data['uid'] = $this->user_session['uid'];
		$data['last_time'] = $_SERVER['REQUEST_TIME'];
		if($car_id = M('Smart_city_car')->data($data)->add()){
			$this->success($car_id);
		}else{
			$this->error('添加失败，请重试');
		}
	}
	public function edit(){
		$car_id = intval($_GET['car_id']);
		
		$now_car = D('Smart_city_car')->where(array('uid'=>$this->user_session['uid'],'car_id'=>$car_id))->find();
		if(empty($now_car)){
			$this->error_tips('该车辆不存在');
		}
		$this->assign('now_car',$now_car);
		
    	$this->display();
    }
	public function amend(){
		$car_id = intval($_POST['car_id']);
		$data['car_area'] = $_POST['car_area'];
		$data['car_num'] = $_POST['carnum'];
		$data['car_phone'] = $_POST['phone'];
		$data['uid'] = $this->user_session['uid'];
		$data['tip_type'] = intval($_POST['tip_type']);
		$data['last_time'] = $_SERVER['REQUEST_TIME'];
		if(M('Smart_city_car')->where(array('uid'=>$this->user_session['uid'],'car_id'=>$car_id))->data($data)->save()){
			$this->success('修改成功');
		}else{
			$this->error('修改失败，请检查有无修改后重试');
		}
	}
	public function delete(){
		$car_id = intval($_POST['car_id']);
		if(M('Smart_city_car')->where(array('uid'=>$this->user_session['uid'],'car_id'=>$car_id))->delete()){
			$this->success('删除成功');
		}else{
			$this->error('删除成功，请重试');
		}
	}
	public function car_num(){
		$car_id = intval($_GET['car_id']);
		$now_car = D('Smart_city_car')->where(array('car_id'=>$car_id))->find();
		if(empty($now_car)){
			$this->error_tips('该车辆不存在');
		}
		$this->assign('now_car',$now_car);
		
		$this->display();
	}
	public function notice_car(){
		$car_id = intval($_POST['car_id']);
		$now_car = D('Smart_city_car')->where(array('car_id'=>$car_id))->find();
		if(empty($now_car)){
			$this->error('该车辆不存在');
		}
		$data_notice['car_id'] = $now_car['car_id'];
		$data_notice['notice_time'] = $_SERVER['REQUEST_TIME'];
		if($notice_id = M('Smart_city_car_notice')->data($data_notice)->add()){
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			
			$userInfo = D('User')->get_user($now_car['uid']);
			if($userInfo['openid'] && $userInfo['is_follow']){
				$href = $this->config['site_url'].U('City_car/notice_answer',array('notice_id'=>$notice_id));

		
				if (($dbinfo = M('Tempmsg')->where(array('tempkey' => 'OPENTM414860535','mer_id'=>0))->find()) && $dbinfo['status'] && $dbinfo['tempid']) {
					
						$model->sendTempMsg('OPENTM414860535', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '尊敬的车主，您好！你有新的挪车提醒', 'keyword1' => $now_car['car_num'], 'keyword2' => date('Y年m月d日 H时i分'),'remark' => '\n有人通知您需要进行挪车，点击消息应答该次提醒'));
					

				}else{
					$model->sendTempMsg('TM00356', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '挪车提醒（'.$now_car['car_area'].$now_car['car_num'].'）', 'work' => '有人通知您需要进行挪车', 'remark' => '\n点击消息进行应答该次提醒'));
				}
			}else if($userInfo['phone']){
				$sms_data['uid'] = $userInfo['uid'];
				$sms_data['mobile'] = $userInfo['phone'];
				$sms_data['sendto'] = 'user';
				$sms_data['content'] = '挪车提醒（'.$now_car['car_area'].$now_car['car_num'].'），有人通知您需要进行挪车。';
				Sms::sendSms($sms_data);
			}
			
			$this->success($notice_id);
		}else{
			$this->error('提醒失败');
		}
	}
	public function notice_loop(){
		$notice_id = intval($_POST['notice_id']);
		$now_notice = D('Smart_city_car_notice')->where(array('notice_id'=>$notice_id))->find();
		if($now_notice['is_answer']){
			$this->success('车主已应答');
		}else{
			$this->error('车主未应答');
		}
	}
	public function notice_answer(){
		$notice_id = intval($_GET['notice_id']);
		$now_notice = M('Smart_city_car_notice')->where(array('notice_id'=>$notice_id))->find();
		if(empty($now_notice)){
			$this->assign('answer_tip','该通知不存在');
			$this->assign('is_answer',false);
			$this->display();
			exit();
		}
		$this->assign('now_notice',$now_notice);
		if($now_notice['is_answer']){
			$this->assign('answer_tip','该通知曾经应答过');
			$this->assign('is_answer',false);
			$this->display();
			exit();
		}
		
		$data_notice['is_answer'] = 1;
		$data_notice['answer_time'] = $_SERVER['REQUEST_TIME'];
		if(M('Smart_city_car_notice')->where(array('notice_id'=>$notice_id))->data($data_notice)->save()){
			$this->assign('answer_tip','应答成功');
			$this->assign('is_answer',true);
			$this->display();
		}else{
			$this->assign('answer_tip','应答失败，请返回重试');
			$this->assign('is_answer',false);
			$this->display();
		}
	}
	public function get_pic(){
		$car_id = intval($_GET['car_id']);
		
		$now_car = D('Smart_city_car')->where(array('uid'=>$this->user_session['uid'],'car_id'=>$car_id))->find();
		if(empty($now_car)){
			$this->error_tips('该车辆不存在');
		}
		$this->assign('now_car',$now_car);
		
		
		$img_mer_id = sprintf("%09d", $_GET['car_id']);
		$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
		$upload_dir = "./upload/city_car/{$rand_num}/";
		$this->assign('now_car_pic',$upload_dir.$car_id.'.png');
		
		$this->car_pic();
		
		$this->display();
	}
	public function car_pic(){
		if(!file_exists('./upload/smart_city/')){
			mkdir('./upload/smart_city/',0777,true);
		}
		$qrcode_file = './upload/smart_city/car_'.$_GET['car_id'].'.png';
		
		if(!file_exists($qrcode_file)){
			$recognition = D('Recognition')->where(array('third_type'=>'smart_city_car','third_id'=>$_GET['car_id']))->find();
			if($recognition['ticket']){
				$ticket_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$recognition['ticket'];
			}else{
				$qrcode_return = D('Recognition')->get_new_qrcode('smart_city_car',$_GET['car_id']);
				if($qrcode_return['error_code']){
					exit();
				}else{
					$ticket_url = $qrcode_return['qrcode'];
				}
			}
			import('ORG.Net.Http');
			$http = new Http();
			$qrcode_content = $http->curlGet($ticket_url);
			// echo $qrcode_content
			file_put_contents($qrcode_file,$qrcode_content);
			
		}

		$sImage = imagecreatefrompng('./static/fonts/car_num_bg.png');
	
        $wImage = imagecreatefromjpeg($qrcode_file);
		
        $getImage = imagecreatefrompng('./static/fonts/car_num_get.png');
		list($width, $height) = getimagesize($qrcode_file);		
		$dim = imagecreate(140, 140);
		imagecopyresized ($dim,$wImage,0,0,0,0,140,140,$width,$height);
		imagecopymerge($sImage, $dim,66,66, 0, 0, 140, 140, 100);
		
		imagecopymerge($sImage, $getImage,70,235, 0, 0, 22, 22, 100);
		
		$color=imagecolorallocatealpha($sImage,255,255,255,0);

		imagettftext($sImage,15,0,(270-7*15)/2+18,254,$color,'static/fonts/apple_lihei.otf',$this->config['wechat_name']);
		
		imagesavealpha($sImage, TRUE);
	
		$img_mer_id = sprintf("%09d", $_GET['car_id']);
		$rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
		$upload_dir = "./upload/city_car/{$rand_num}/";
		
		if(!file_exists($upload_dir)){
			mkdir($upload_dir,0777,true);
		}
		
        imagepng($sImage,$upload_dir.$_GET['car_id'].'.png');
	}
}
?>