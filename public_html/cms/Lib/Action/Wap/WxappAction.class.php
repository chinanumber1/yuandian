<?php
/*
 * 营销系统对接
 *
 */

class WxappAction extends BaseAction{
	public $apiUrl;
	public $selectUrl;
	public $insertUrl;
	public $oauth2Url;
	public $orderUrl;
	public $SALT;
	public $synType;
	public $_accessListAction;
	public $mer_id;
	public $token;
	protected function _initialize(){
		parent::_initialize();
		$this->apiUrl  = $this->config['wxapp_url'];
		if(empty($this->apiUrl)){
			$this->error_tips('请联系管理员在后台配置营销功能');
		}
		$this->selectUrl = $this->apiUrl.'/index.php?g=Home&m=Auth&a=select';
		$this->insertUrl = $this->apiUrl.'/index.php?g=Home&m=Auth&a=insert';
		$this->oauth2Url = $this->apiUrl.'/index.php?g=Home&m=Auth&a=oauth2';
		$this->orderUrl = $this->apiUrl.'/index.php?g=Home&m=Auth&a=order';
		$this->SALT 	= $this->config['wxapp_encrypt'] ? $this->config['wxapp_encrypt'] : 'pigcms';
		$this->synType 	= 'o2o';
		$this->_accessListAction = array(
			'bargain'       => '砍价', 
			'seckill'       =>'秒杀', 
			'crowdfunding'  =>'众筹',
			'unitary'       =>'一元夺宝',
			'cutprice'      =>'降价拍',
			'lottery'		=>'大转盘',
			'red_packet'	=>'微信红包',
			'guajiang'		=>'刮刮卡',
			'jiugong'		=>'九宫格',
			'luckyFruit'	=>'幸运水果机',
			'goldenEgg'		=>'砸金蛋',
			'voteimg'		=>'图片投票',
			'custom'		=>'万能表单',
			'card'			=>'微贺卡',
			'game'			=>'微游戏',
			'live'			=>'微场景',
			'research'		=>'微调研',
			'forum'			=>'讨论社区',
			'autumn'		=>'中秋吃月饼活动',
			'helping'		=>'微助力',
			'donation'		=>'募捐',
			'cointree'		=>'摇钱树',
			'collectword'	=>'集字游戏',
			'sentiment'		=>'谁是情圣',
			'frontpage_action'=>'我要上头条',
			'frontPage'		=>'我要上头条',
			'test'			=>'趣味测试',
			'punish'		=>'惩罚台',
			'shakelottery'	=>'摇一摇',
			'yousetdiscount'=>'优惠接力',
			'popularity'	=>'人气冲榜',
			'problem_game'	=>'一战到底',
			'auction'		=>'微拍卖',
			'person_card'	=>'微名片',
		);
		$this->_accessListModel = array(
			'bargain'       => array('name'=>'Bargain','condition'=>array(),'autoid'=>'pigcms_id'), 
			'seckill'       => array('name'=>'SeckillAction','condition'=>array(),'autoid'=>'action_id'), 
			'crowdfunding'  => array('name'=>'Crowdfunding','condition'=>array()),
			'unitary'       => array('name'=>'Unitary','condition'=>array()),
			'cutprice'      => array('name'=>'Cutprice','condition'=>array(),'autoid'=>'pigcms_id'),
			'lottery'		=> array('name'=>'Lottery',),
			'red_packet'	=> array('name'=>'RedPacket','condition'=>array()),
			'guajiang'		=> array('name'=>'Guajiang',),
			'jiugong'		=> array('name'=>'Jiugong',),
			'luckyFruit'	=> array('name'=>'LuckyFruit',),
			'goldenEgg'		=> array('name'=>'GoldenEgg',),
			'voteimg'		=> array('name'=>'Voteimg','condition'=>array()),
			'custom'		=> array('name'=>'CustomSet','condition'=>array(),'autoid'=>'set_id'),
			'card'			=> array('name'=>'Cards','condition'=>array()),
			'game'			=> array('name'=>'Games','condition'=>array()),
			'live'			=> array('name'=>'Live','condition'=>array()),
			'research'		=> array('name'=>'Research','condition'=>array()),
			'forum'			=> array('name'=>'ForumConfig','condition'=>array()),
			'autumn'		=> array('name'=>'Autumn','condition'=>array()),
			'helping'		=> array('name'=>'Helping','condition'=>array()),
			'donation'		=> array('name'=>'Donation','condition'=>array()),
			'cointree'		=> array('name'=>'Cointree','condition'=>array()),
			'collectword'		=> array('name'=>'Collectword','condition'=>array()),
			'sentiment'		=> array('name'=>'Sentiment','condition'=>array()),
			'frontpage_action'		=> array('name'=>'Frontpage_action','condition'=>array()),
			'frontPage'		=> array('name'=>'Frontpage','condition'=>array()),
			'test'		=> array('name'=>'Test','condition'=>array()),
			'punish'		=> array('name'=>'Punish','condition'=>array()),
			'shakelottery'		=> array('name'=>'Shakelottery','condition'=>array()),
			'yousetdiscount'		=> array('name'=>'Yousetdiscount','condition'=>array()),
			'popularity'		=> array('name'=>'Popularity','condition'=>array()),
			'problem_game'		=> array('name'=>'Problem_game','condition'=>array()),
			'auction'		=> array('name'=>'Auction','condition'=>array()),
		);
	}
	public function check_follow(){
		if($_REQUEST['wecha_id']){
			$now_user = D('User')->get_user($_REQUEST['wecha_id']-100000000);
			if($now_user['is_follow']){
				echo json_encode(array('code'=>'1','msg'=>'已关注'));
			}else{
				$access_token_array = D('Access_token_expires')->get_access_token();
				if (!$access_token_array['errcode']) {
					import('ORG.Net.Http');
					$http = new Http();
					$return = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_array['access_token'].'&openid='.$now_user['openid'].'&lang=zh_CN');
					$userinfo = json_decode($return,true);
					if($userinfo['subscribe']){
						if(D('User')->where(array('uid'=>$now_user['uid']))->data(array('is_follow'=>1))->save()){
							$this->check_follow();
						}
					}
				}
				
				if(!empty($_GET['token']) && !empty($_GET['wxmodel']) && !empty($_GET['id'])){
					$wxapp_token = M('Wxpp_token')->where(array('pigcms_token'=>$_GET['token']))->find();
					if(!empty($wxapp_token)){
						$qrcode_url = $this->config['site_url'].U('qrcode',array('mer_id'=>$wxapp_token['mer_id'],'modle'=>$_GET['wxmodel'],'id'=>$_GET['id']));
					}
				}
				$qrcode_url = $qrcode_url ? $qrcode_url : $this->config['wechat_qrcode'];
				
				echo json_encode(array('code'=>'2','follow'=>array('url'=>$this->config['wechat_follow_txt_url'],'qrcode'=>$qrcode_url,'wechat'=>$this->config['wechat_id'])));
			}
		}else{
			echo json_encode(array('code'=>'10001','msg'=>'请POST wecha_id'));
		}
	}
	public function follow_qrcode(){
		// $wxappCon = D('Wxapp_list')->field('`pigcms_id`')->where(array('mer_id'=>$this->mer_id,'type'=>$modle,'id'=>$id))->find();
		
		// redirect($this->config['site_url'].'/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id='.(4100000000+$pigcms_id));
	}
	public function qrcode(){
		
		if(!empty($_GET['mer_id'])){
			$this->mer_id = $_GET['mer_id'];
		}else{
			if(empty($_SESSION['wxapp_now_active'])){
				redirect($this->config['site_url'].'/static/qrcode/wxapp/not_login.jpg');
			}
		
			$this->mer_id = $_SESSION['wxapp_now_active'] == 'merchant' ? $_SESSION['merchant']['mer_id'] : -1;
		}
		
		$this->token = $this->getToken($this->mer_id);
		
		$modle  = lcfirst($_GET['modle']);
		$id 	= $_GET['id'];
		if($modle == 'frontPage'){
			$modle = 'frontpage_action';
		}
		if($modle == 'problem'){
			$modle = 'problem_game';
		}
		if($modle == 'youSetDiscount'){
			$modle = 'yousetdiscount';
		}
		// echo $modle;
		// dump($this->_accessListAction);exit;
		if(empty($this->_accessListAction[$modle])){
			redirect($this->config['site_url'].'/static/qrcode/wxapp/not_found.jpg');
		}else{
			/*查询本地数据库*/
			$wxappConWhere = array('mer_id'=>$this->mer_id,'type'=>$modle);
			if($id){
				$wxappConWhere['id'] = $id;
			}
			$wxappCon = D('Wxapp_list')->field('`pigcms_id`')->where($wxappConWhere)->find();
			
			$post_data 	= array(
				'token' 	=> $this->token,
				'model' 	=> $this->_accessListModel[$modle]['name'],
				'debug'		=> true,
				'option' 	=> array(
					'where' => $this->_accessListModel[$modle]['condition'],
				),
			);
			if($this->_accessListModel[$modle]['autoid']){
				$post_data['option']['where'][$this->_accessListModel[$modle]['autoid']] = $id;
			}else if($modle == 'forum'){
				$post_data['option']['where']['token'] = $this->token;
			} elseif($modle == 'test'){
				$post_data['option']['where']['pigcms_id'] = $id;
			}else if($modle != 'forum'){
				$post_data['option']['where']['id'] = $id;
			}
			
			if(empty($post_data['option']['where'])){
				unset($post_data['option']);
			}
			$post_data['sign'] 	= $this->getSign($post_data);
			$result = $this->curl_post($this->selectUrl,$post_data);
			$resultArr = json_decode($result,true);
			if($resultArr['status'] != 0 || $resultArr['message'] != 'success'){
				redirect($this->config['site_url'].'/static/qrcode/wxapp/not_found.jpg');
			}else{
				$wxappInfo = $this->getWxappInfo($modle,$resultArr['data']);
				$wxappInfo = $wxappInfo[0];
				if(empty($wxappCon)){
					$pigcms_id = D('Wxapp_list')->data(array('mer_id'=>$this->mer_id,'type'=>$modle,'id'=>$wxappInfo['modelId'],'title'=>$wxappInfo['title'],'info'=>$wxappInfo['info'],'image'=>$wxappInfo['image'],'time'=>$wxappInfo['time'],'add_time'=>$_SERVER['REQUEST_TIME'],'status'=>'1'))->add();
				}else{
					D('Wxapp_list')->data(array('pigcms_id'=>$wxappCon['pigcms_id'],'mer_id'=>$this->mer_id,'type'=>$modle,'id'=>$wxappInfo['modelId'],'title'=>$wxappInfo['title'],'info'=>$wxappInfo['info'],'image'=>$wxappInfo['image'],'time'=>$wxappInfo['time']))->save();
					$pigcms_id = $wxappCon['pigcms_id'];
				}
				if(empty($pigcms_id)){
					redirect($this->config['site_url'].'/static/qrcode/wxapp/not_found.jpg');
				}
			}
			redirect($this->config['site_url'].'/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id='.(4100000000+$pigcms_id));
			// dump($resultArr);
			// dump($wxappInfo);exit;
			// $imgUrl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQEy8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2FYWDFMbmpsbEZidFVaUkpZRm5mAAIEDUuWVQMEAAAAAA%3D%3D';
		}
		redirect($imgUrl);
	}
	public function share(){
		$share_data = S('wxapp_share_data');
		if(empty($share_data)){
			$share = new WechatShare($this->config,'');
			$share->checkTicket();
			$share_data = array('appid'=>$this->config['wechat_appid'],'ticket'=>$share->share_ticket);
			S('wxapp_share_data',$share_data,3600);
		}
		echo json_encode($share_data);
	}
	/*跳转活动到营销系统*/
	public function location_href(){
		$wxapp = D("Wxapp_list")->field(true)->where(array('pigcms_id' => $_GET['id']))->find();
		if($wxapp){
			$url = $this->getWxappUrl($wxapp);
			if($url){
				// echo '<html><title>'.$this->_accessListAction[$wxapp['type']].'</title><body style="margin:0;padding:0;"><iframe src="'.$url.'" style="width:100%;height:100%;margin:0;padding:0;border:0;"></iframe></body></html>';
				redirect($url);
			}else{
				$this->error_tips('该活动不存在！',U('Home/index'));
			}
		}else{
			$this->error_tips('该活动不存在！',U('Home/index'));
		}
	}
	
	/*将各种类型的活动转换为统一的方式*/
	public function getWxappUrl($data){
		switch($data['type']){
			case 'red_packet':
				return $this->apiUrl.'/index.php?g=Wap&m=Red_packet&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'lottery':
				return $this->apiUrl.'/index.php?g=Wap&m=Lottery&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'bargain':
				return $this->apiUrl.'/index.php?g=Wap&m=Bargain&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'guajiang':
				return $this->apiUrl.'/index.php?g=Wap&m=Guajiang&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'crowdfunding':
				return $this->apiUrl.'/index.php?g=Wap&m=Crowdfunding&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'unitary':
				return $this->apiUrl.'/index.php?g=Wap&m=Unitary&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'jiugong':
				return $this->apiUrl.'/index.php?g=Wap&m=Jiugong&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'luckyFruit':
				return $this->apiUrl.'/index.php?g=Wap&m=LuckyFruit&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'goldenEgg':
				return $this->apiUrl.'/index.php?g=Wap&m=GoldenEgg&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'seckill':
				return $this->apiUrl.'/index.php?g=Wap&m=Seckill&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'cutprice':
				return $this->apiUrl.'/index.php?g=Wap&m=Cutprice&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;	
			case 'voteimg':
				return $this->apiUrl.'/index.php?g=Wap&m=Voteimg&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;	
			case 'custom':
				return $this->apiUrl.'/index.php?g=Wap&m=Custom&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'card':
				return $this->apiUrl.'/index.php?g=Wap&m=Game&a=card&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'live':
				return $this->apiUrl.'/index.php?g=Wap&m=Live&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'forum':
				return $this->apiUrl.'/index.php?g=Wap&m=Forum&a=index&token='.$this->getToken($data['mer_id']);
				break;
			case 'autumn':
				return $this->apiUrl.'/index.php?g=Wap&m=Autumn&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'research':
				return $this->apiUrl.'/index.php?g=Wap&m=Research&a=index&token='.$this->getToken($data['mer_id']).'&reid='.$data['id'];
				break;
			case 'game':
				return $this->apiUrl.'/index.php?g=Wap&m=Game&a=link&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'helping':
				return $this->apiUrl.'/index.php?g=Wap&m=Helping&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'donation':
				return $this->apiUrl.'/index.php?g=Wap&m=Donation&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'cointree':
				return $this->apiUrl.'/index.php?g=Wap&m=CoinTree&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'collectword':
				return $this->apiUrl.'/index.php?g=Wap&m=Collectword&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'sentiment':
				return $this->apiUrl.'/index.php?g=Wap&m=Sentiment&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'frontpage_action':
				return $this->apiUrl.'/index.php?g=Wap&m=FrontPage&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'test':
				return $this->apiUrl.'/index.php?g=Wap&m=Test&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'punish':
				return $this->apiUrl.'/index.php?g=Wap&m=Punish&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'shakelottery':
				return $this->apiUrl.'/index.php?g=Wap&m=ShakeLottery&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'yousetdiscount':
				return $this->apiUrl.'/index.php?g=Wap&m=YouSetDiscount&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'popularity':
				return $this->apiUrl.'/index.php?g=Wap&m=Popularity&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'problem_game':
				return $this->apiUrl.'/index.php?g=Wap&m=Problem&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
			case 'auction':
				return $this->apiUrl.'/index.php?g=Wap&m=Auction&a=index&token='.$this->getToken($data['mer_id']).'&id='.$data['id'];
				break;
		}
	}
	/*统一的支付方式*/
	public function pay(){
		// dump($_GET);exit;
		$wxapp_token = D('Wxpp_token')->field('`mer_id`')->where(array('pigcms_token'=>$_GET['token']))->find();
		if(empty($wxapp_token)){
			$this->error_tips('访问出错！该商家不存在。');
		}
		$now_user = D('User')->get_user($_GET['wecha_id']-100000000);
		if(empty($now_user)){
			$this->error_tips('访问出错！该用户不存在。');
		}
		$_GET['mer_id'] = $wxapp_token['mer_id'];
		$_GET['uid'] 	= $now_user['uid'];
		$_GET['type'] 	= 'wxapp';
		$param = array();
		foreach($_GET as $key=>$value){
			$param[$key] = urlencode($value);
		}
		redirect(U('Pay/check',$param));
	}
	/*支付后跳转到营销系统*/
	public function pay_back(){
		$now_order = D('Wxapp_order')->get_order_by_id($_GET['order_id']);
		if(empty($now_order)){
			$this->error_tips('访问出错！该订单不存在。');
		}else if(empty($now_order['paid'])){
			$this->error_tips('当前订单还未支付！',U('Pay/check',array('type'=>'wxapp','order_id'=>$now_order['order_id'])));
		}
		$params = array(
			'from' => $now_order['from'],
			'transactionid' => $now_order['third_id'],
			'token' => $this->getToken($now_order['mer_id']),
			'orderid' => $now_order['wxapp_order_id'],
			'payType' => $now_order['pay_type'],
		);
		$params['sign'] 	= $this->getSign($params);
		redirect($this->orderUrl.'&'.http_build_query($params));
	}
	/*将各种类型的活动转换为统一的方式*/
	public function getWxappInfo($type,$data){
		$return = array();
		switch($type){
			case 'red_packet':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'red_packet';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['desc'];
					$return[$key]['image'] = substr($val['msg_pic'],0,4) == 'http' ? $val['msg_pic'] : $this->apiUrl.$val['msg_pic'];
					$return[$key]['token'] = $val['token'];
					$return[$key]['time'] = $val['start_time'];
				}
				break;
			case 'lottery':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'lottery';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['info'];
					$return[$key]['image'] = substr($val['starpicurl'],0,4) == 'http' ? $val['starpicurl'] : $this->apiUrl.$val['starpicurl'];
					$return[$key]['token'] = $val['token'];
					$return[$key]['time'] = $val['statdate'];
				}
				break;
			case 'bargain':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'bargain';
					$return[$key]['modelId'] = $val['pigcms_id'];
					$return[$key]['title'] = $val['wxtitle'];
					$return[$key]['info'] = $val['wxinfo'];
					$return[$key]['image'] = substr($val['wxpic'],0,4) == 'http' ? $val['wxpic'] : $this->apiUrl.$val['wxpic'];
					$return[$key]['token'] = $val['token'];
					$return[$key]['time'] = $val['addtime'];
				}
				break;
			case 'guajiang':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'guajiang';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['info'];
					$return[$key]['image'] = substr($val['starpicurl'],0,4) == 'http' ? $val['starpicurl'] : $this->apiUrl.$val['starpicurl'];
					$return[$key]['token'] = $val['token'];
					$return[$key]['time'] = $val['statdate'];
				}
				break;
			case 'cutprice':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'cutprice';
					$return[$key]['modelId'] = $val['pigcms_id'];
					$return[$key]['title'] = $val['wxtitle'];
					$return[$key]['info'] = $val['wxinfo'];
					$return[$key]['image'] = substr($val['wxpic'],0,4) == 'http' ? $val['wxpic'] : $this->apiUrl.$val['wxpic'];
					$return[$key]['token'] = $val['token'];
					$return[$key]['time'] = $val['starttime'];
				}
				break;
			case 'seckill':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'seckill';
					$return[$key]['modelId'] = $val['action_id'];
					$return[$key]['title'] = $val['reply_title'];
					$return[$key]['info'] = $val['reply_content'];
					$return[$key]['image'] = substr($val['reply_pic'],0,4) == 'http' ? $val['reply_pic'] : $this->apiUrl.$val['reply_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['action_sdate'];
				}
				break;
			case 'crowdfunding':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'crowdfunding';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['name'];
					$return[$key]['info'] = $val['intro'];
					$return[$key]['image'] = substr($val['pic'],0,4) == 'http' ? $val['pic'] : $this->apiUrl.$val['pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['start'];
				}
				break;
			case 'unitary':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'unitary';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['name'];
					$return[$key]['info'] = $val['wxinfo'];
					$return[$key]['image'] = substr($val['wxpic'],0,4) == 'http' ? $val['wxpic'] : $this->apiUrl.$val['wxpic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['addtime'];
				}
				break;
			case 'jiugong':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'jiugong';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['info'];
					$return[$key]['image'] = substr($val['starpicurl'],0,4) == 'http' ? $val['starpicurl'] : $this->apiUrl.$val['starpicurl'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['statdate'];
				}
				break;
			case 'jiugong':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'jiugong';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['info'];
					$return[$key]['image'] = substr($val['starpicurl'],0,4) == 'http' ? $val['starpicurl'] : $this->apiUrl.$val['starpicurl'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['statdate'];
				}
				break;
			case 'luckyFruit':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'luckyFruit';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['info'];
					$return[$key]['image'] = substr($val['starpicurl'],0,4) == 'http' ? $val['starpicurl'] : $this->apiUrl.$val['starpicurl'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['statdate'];
				}
				break;
			case 'goldenEgg':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'goldenEgg';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['info'];
					$return[$key]['image'] = substr($val['starpicurl'],0,4) == 'http' ? $val['starpicurl'] : $this->apiUrl.$val['starpicurl'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['statdate'];
				}
				break;
			case 'voteimg':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'voteimg';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['reply_title'];
					$return[$key]['info'] = $val['reply_content'];
					$return[$key]['image'] = substr($val['reply_pic'],0,4) == 'http' ? $val['reply_pic'] : $this->apiUrl.$val['reply_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['start_time'];
				}
				break;
			case 'custom':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'custom';
					$return[$key]['modelId'] = $val['set_id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['intro'];
					$return[$key]['image'] = substr($val['top_pic'],0,4) == 'http' ? $val['top_pic'] : $this->apiUrl.$val['top_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['addtime'];
				}
				break;
			case 'card':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'card';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['intro'];
					$return[$key]['image'] = substr($val['picurl'],0,4) == 'http' ? $val['picurl'] : $this->apiUrl.$val['picurl'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['time'];
				}
				break;
			case 'live':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'live';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['name'];
					$return[$key]['info'] = $val['intro'];
					$return[$key]['image'] = substr($val['end_pic'],0,4) == 'http' ? $val['end_pic'] : $this->apiUrl.$val['end_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['add_time'];
				}
				break;
			case 'forum':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'forum';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['forumname'];
					$return[$key]['info'] = $val['intro'];
					$return[$key]['image'] = substr($val['picurl'],0,4) == 'http' ? $val['picurl'] : $this->apiUrl.$val['picurl'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $_SERVER['REQUEST_TIME'];
				}
				break;
			case 'autumn':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'autumn';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['info'];
					$return[$key]['image'] = substr($val['starpicurl'],0,4) == 'http' ? $val['starpicurl'] : $this->apiUrl.$val['starpicurl'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['statdate'];
				}
				break;
			case 'research':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'research';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['description'];
					$return[$key]['image'] = substr($val['logourl'],0,4) == 'http' ? $val['logourl'] : $this->apiUrl.$val['logourl'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['dateline'];
				}
				break;
			case 'game':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'game';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['intro'];
					$return[$key]['image'] = substr($val['picurl'],0,4) == 'http' ? $val['picurl'] : $this->apiUrl.$val['picurl'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['time'];
				}
				break;
			case 'helping':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'helping';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['intro'];
					$return[$key]['image'] = substr($val['reply_pic'],0,4) == 'http' ? $val['reply_pic'] : $this->apiUrl.$val['reply_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['add_time'];
				}
				break;
			case 'donation':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'donation';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['name'];
					$return[$key]['info'] = $val['note'];
					$return[$key]['image'] = substr($val['reply_pic'],0,4) == 'http' ? $val['reply_pic'] : $this->apiUrl.$val['reply_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['starttime'];
				}
				break;
			case 'cointree':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'cointree';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['action_name'];
					$return[$key]['info'] = $val['reply_content'];
					$return[$key]['image'] = substr($val['reply_pic'],0,4) == 'http' ? $val['reply_pic'] : $this->apiUrl.$val['reply_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['starttime'];
				}
				break;
			case 'collectword':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'collectword';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['intro'];
					$return[$key]['image'] = substr($val['reply_pic'],0,4) == 'http' ? $val['reply_pic'] : $this->apiUrl.$val['reply_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['start'];
				}
				break;
			case 'sentiment':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'sentiment';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['intro'];
					$return[$key]['image'] = substr($val['reply_pic'],0,4) == 'http' ? $val['reply_pic'] : $this->apiUrl.$val['reply_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['start'];
				}
				break;
			case 'frontpage_action':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'frontpage_action';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['reply_title'];
					$return[$key]['info'] = $val['reply_content'];
					$return[$key]['image'] = substr($val['reply_pic'],0,4) == 'http' ? $val['reply_pic'] : $this->apiUrl.$val['reply_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['start_time'];
				}
				break;
			case 'test':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'test';
					$return[$key]['modelId'] = $val['pigcms_id'];
					$return[$key]['title'] = $val['name'];
					$return[$key]['info'] = $val['wxinfo'];
					$return[$key]['image'] = substr($val['wxpic'],0,4) == 'http' ? $val['wxpic'] : $this->apiUrl.$val['wxpic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = time();
				}
				break;
			case 'punish':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'punish';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['name'];
					$return[$key]['info'] = $val['info'];
					$return[$key]['image'] = substr($val['pic'],0,4) == 'http' ? $val['pic'] : $this->apiUrl.$val['pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = time();
				}
				break;
			case 'shakelottery':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'shakelottery';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['reply_title'];
					$return[$key]['info'] = $val['reply_content'];
					$return[$key]['image'] = substr($val['reply_pic'],0,4) == 'http' ? $val['reply_pic'] : $this->apiUrl.$val['reply_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['starttime'];
				}
				break;
			case 'yousetdiscount':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'yousetdiscount';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['wxtitle'];
					$return[$key]['info'] = $val['wxinfo'];
					$return[$key]['image'] = substr($val['wxpic'],0,4) == 'http' ? $val['wxpic'] : $this->apiUrl.$val['wxpic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['startdate'];
				}
				break;
			case 'popularity':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'popularity';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['title'];
					$return[$key]['info'] = $val['info'];
					$return[$key]['image'] = substr($val['pic'],0,4) == 'http' ? $val['pic'] : $this->apiUrl.$val['pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['start'];
				}
				break;
			case 'problem_game':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'problem_game';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['name'];
					$return[$key]['info'] = $val['explain'];
					$return[$key]['image'] = substr($val['logo_pic'],0,4) == 'http' ? $val['logo_pic'] : $this->apiUrl.$val['logo_pic'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['start_time'];
				}
				break;
			case 'auction':
				foreach($data as $key=>$val){
					$return[$key]['type'] = 'auction';
					$return[$key]['modelId'] = $val['id'];
					$return[$key]['title'] = $val['name'];
					$return[$key]['info'] = $val['info'];
					$return[$key]['image'] = substr($val['logo'],0,4) == 'http' ? $val['logo'] : $this->apiUrl.$val['logo'];
					$return[$key]['token'] = $this->token;
					$return[$key]['time'] = $val['start'];
				}
				break;
		}
		return $return;
	}
	/*营销系统授权登录接口*/
	public function redirect(){
		if(empty($this->user_session)){
			if(!empty($_GET['return_url'])){
				$_SESSION['weidian_return_url'] = $_GET['return_url'];
			}
			redirect(U('Login/index',array('referer'=>urlencode(U('Wxapp/redirect',array('token'=>$_GET['token'],'source'=>$_GET['source']))))));
		}else{
			$wxapp_token = D('Wxpp_token')->field('`mer_id`')->where(array('pigcms_token'=>$_GET['token']))->find();
			if(empty($wxapp_token)){
				$this->error_tips('访问出错！',U('Home/index'));
			}
			$now_user = D('User')->get_user($this->user_session['uid']);
			$this->saverelation($now_user['openid'],$wxapp_token['mer_id']);
			
			$post_data 	= array(
				'token' 	=> $_GET['token'],
				'model' 	=> 'Userinfo',
				'option'    => array(
					'where'=>array(
						'token' 		=> $_GET['token'],
						'wecha_id' 		=> 100000000+$now_user['uid'],
					),					
				),
				'data' 		=> array(
					'token' 		=> $_GET['token'],
					'wecha_id' 		=> 100000000+$now_user['uid'],
					'wechaname' 	=> $now_user['nickname'],
					'truename' 		=> $now_user['truename'] ? $now_user['truename'] : $now_user['nickname'],
					'tel' 			=> $now_user['phone'],
					'sex' 			=> $now_user['sex'],
					'portrait' 		=> $now_user['avatar'],
					'province' 		=> $now_user['province'],
					'city' 			=> $now_user['city'],
					'issub' 		=> $now_user['is_follow'],
				),
			);
			$post_data['sign'] 	= $this->getSign($post_data);
			$result = $this->curl_post($this->insertUrl,$post_data);
			$resultArr = json_decode($result,true);
			// dump($post_data);exit;
			if($resultArr['status'] != 0 || $resultArr['message'] != 'success'){
				$this->error_tips('访问出错！',U('Home/index'));
			}else{
				redirect($this->oauth2Url.'&token='.$_GET['token'].'&wechat_id='.(100000000+$now_user['uid']));
			}
		}
	}
	/*保存用户和商家的关注关系*/
	private function saverelation($openid,$mer_id){
		if(empty($openid) || empty($mer_id)){
			return false;
		}
    	$relation = D('Merchant_user_relation')->field('mer_id')->where(array('openid' => $openid, 'mer_id' => $mer_id))->find();
    	$where = array('img_num' => 1);
    	if (empty($relation)){
    		$relation = array('openid' => $openid, 'mer_id' => $mer_id, 'dateline' => time(), 'from_merchant' => 0);
    		D('Merchant_user_relation')->add($relation);
    		$where['follow_num'] = 1;
    		D('Merchant')->where(array('mer_id' => $mer_id))->setInc('fans_count', 1);
    	}
    	D('Merchant_request')->add_request($mer_id, $where);
		return true;
    }
	/*将Pigcms的控制器名转换成Tp识别的Model形式*/
	public function getModuleName($actionName){
		$actionArr = explode('_',$actionName);
		$moduleName = '';
		foreach($actionArr as $value){
			$moduleName .= ucfirst($value);
		}
		return $moduleName;
	}
	/*模拟公众号token*/
	public function getToken($id){
		$site_url=str_replace('https','http',$this->config['site_url']);

		return substr(md5($site_url.$id.$this->synType),8,16);
	}
	/*Pigcms规定的密钥*/
	private function getSign($data){
		foreach ($data as $key => $value) {
			$validate[$key] = is_array($value) ? $this->getSign($value) : $value;
		}
		$validate['salt'] = $this->SALT;
		sort($validate, SORT_STRING);
		return sha1(implode($validate));
	}
	protected function curl_post($url,$data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
		return curl_exec($ch);
	}
	
	public function index()
	{
		import('ORG.Net.Http');
		$http = new Http();
		$return = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_array['access_token'].'&openid='.$now_user['openid'].'&lang=zh_CN');
		$userinfo = json_decode($return,true);

		$activity_type = $_GET['cat_url'] ? $_GET['cat_url'] : 'all';
		$where = array('status' => 1);
		if($_GET['mer_id']){
			$where['mer_id'] = $_GET['mer_id'];
		}
		if($activity_type != 'all'){
			$where['type'] = $activity_type;
			$now_category['cat_url'] = $activity_type;
			$now_category['cat_name'] = $this->_accessListAction[$activity_type];
			$this->assign('now_category', $now_category);
		}
		$this->assign('now_cat_url', $activity_type);
		$tp_count = D('Wxapp_list')->where($where)->count();
		import('@.ORG.wap_group_page');
		$P = new Page($tp_count,20,'page');
		$wxapp_list = D('Wxapp_list')->where($where)->order('pigcms_id DESC')->limit($P->firstRow.','.$P->listRows)->select();
		
		foreach ($wxapp_list as &$wx) {
			$wx['url'] = U('Wxapp/location_href', array('id' => $wx['pigcms_id']));
			$wx['type_name'] = $this->_accessListAction[$wx['type']];
		}
		$this->assign('wxapp_list', $wxapp_list);
		
		foreach ($this->_accessListAction as $key => $val) {
			if($key == 'frontPage'){
				continue;
			}
			$all_category_list[] = array('cat_name' => $val,'cat_url' => $key);
		}
		$this->assign('all_category_list', $all_category_list);
		$this->assign('pagebar', $P->show());
		$this->display();
	}
}