<?php
/*
 * 营销系统对接
 *
 */

class WxappAction extends BaseAction{
	public $apiUrl;
	public $SALT;
	public $synType 	= 'weidian';
	public $_accessListAction;
	public $pigcmsToken = '';
	public $mer_id;
	public $pigcmsRegUrl;
	public $pigcmsLoginUrl;
	public $pigcmsAccessUrl;
	public $actionName;
	protected function _initialize(){
		parent::_initialize();
		$this->apiUrl  = $this->config['wxapp_url'];
		if(empty($this->apiUrl)){
			$this->error('请联系管理员在后台配置营销功能');
		}
		$this->pigcmsRegUrl = $this->apiUrl.'/index.php?g=Home&m=Auth&a=signup';
		$this->pigcmsLoginUrl = $this->apiUrl.'/index.php?g=Home&m=Auth&a=signin';
		$this->pigcmsAccessUrl = $this->apiUrl.'/index.php?g=Home&m=Auth&a=access';
		
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
			'voteimg'		=>'图文投票',
			'custom'		=>'万能表单',
			'card'			=>'微贺卡',		//暂时不显示渠道二维码
			'game'			=>'微游戏',
			'live'			=>'微场景',
			'research'		=>'微调研',
			'forum'			=>'讨论社区',
			'autumn'		=>'中秋吃月饼活动',
			'helping'		=>'微助力',
			'donation'		=>'募捐',
			'coinTree'		=>'摇钱树',
			'collectword'	=>'集字游戏',
			'sentiment'		=>'谁是情圣',
			'frontPage'		=>'我要上头条',
			'test'			=>'趣味测试',
			'punish'		=>'惩罚台',
			'shakeLottery'	=>'摇一摇',
			'youSetDiscount'=>'优惠接力',
			'popularity'	=>'人气冲榜',
			'problem'		=>'一战到底',
			'seniorScene'	=>'微场景',
			'auction'		=>'微拍卖',
			'whitelist'		=>'城市白名单',
			'person_card'	=>'微名片',
		);
		$this->mer_id = $this->merchant_session['mer_id'];
		$token_result = D('Wxpp_token')->field('`pigcms_token`')->where(array('mer_id'=>$this->mer_id))->find();
		if($token_result){
			$this->pigcmsToken = $token_result['pigcms_token'];
		}
	}
	/*所有活动作为空操作*/
	public function _empty($actionName){
		if(empty($this->_accessListAction[$actionName])){
			$this->error('您访问的活动不存在！');
		}
		$this->actionName = $actionName;
		
		$_SESSION['wxapp_now_active'] = 'merchant';
		
		//如果第一次对接，则注册
		if(empty($this->pigcmsToken)){
			$this->regToken();
		}
		$accessUrl = $this->loginToken();
		$this->assign('accessUrl',$accessUrl);
		$this->assign('accessName',$this->_accessListAction[$actionName]);
		$this->display('wxapp');
	}
	/*登录操作*/
	public function loginToken($isFirst=true){
		$session_data = array(
			'username' => $this->getUserName($this->mer_id), 
			'userid' => $this->mer_id, 
			'type' => $this->synType
		);
		$session_data['sign']	= $this->getSign($session_data);
		$pigcmsReturn  				= $this->curl_post($this->pigcmsLoginUrl,$session_data);
		if(!empty($pigcmsReturn)){
			$returnObj = json_decode($pigcmsReturn);
			if($returnObj->status != 0){
				if($isFirst == true && ($returnObj->status == 40201 || $returnObj->status == 40202)){
					$this->regToken();
					return $this->loginToken(false);
				}
				$this->error('请联系网站管理员解决！对接返回错误：'.$returnObj->message);
			}else{
				$accessData = array(
					'token' 		=> $this->pigcmsToken,
					'action' 		=> $this->actionName,
					'session_id' 	=> $returnObj->session_id
				);
				$accessData['sign'] = $this->getSign($accessData);
				return $this->pigcmsAccessUrl.'&'.http_build_query($accessData);
			}
		}
	}
	/*注册操作*/
	public function regToken(){
		$site_url=str_replace('https','http',$this->config['site_url']);
		$post_data 	= array(
			'userid' 	=> $this->mer_id,
			'username' 	=> $this->getUserName($this->mer_id),
			'type' 		=> $this->synType,
			'time' 		=> $_SERVER['REQUEST_TIME'],
			'randstr' 	=> createRandomStr(4),
			'wxuserid' 	=> $this->mer_id,
			'domain' 	=> $site_url,
			'wxtype' 	=> -1,
		);

		$post_data['sign'] 	= $this->getSign($post_data);
		$pigcmsReturn 			= $this->curl_post($this->pigcmsRegUrl,$post_data);
		if(!empty($pigcmsReturn)){
			$returnObj = json_decode($pigcmsReturn);
			if($returnObj->status != 0){
				$this->error('请联系网站管理员解决！对接返回错误：'.$returnObj->message);
			}
			$this->pigcmsToken = $this->getToken($this->mer_id);
			if(!D('Wxpp_token')->data(array('mer_id'=>$this->mer_id,'pigcms_token'=>$this->pigcmsToken))->add()){				
				$this->error('请联系网站管理员解决！对接返回错误：无法保存对接信息！');
			}
		}else{
			$this->error('请联系网站管理员解决！对接注册没有返回内容');
		}
	}
	/*创建账号名称*/
	public function getUserName($id){
		return $this->synType.'_'.substr(md5($id),8,10);
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
}