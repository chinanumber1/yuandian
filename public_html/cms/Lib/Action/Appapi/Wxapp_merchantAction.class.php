<?php
/*
 * 商家小程序
 *
 */
class Wxapp_merchantAction extends BaseAction {
	private function replaceImg($url){
		$url = str_replace('http://','https://',$url);
		if(substr($url,0,8) == '/upload/'){
			$url = $this->config['site_url'].$url;
		}else if(substr($url,0,9) == './static/'){
			$url = $this->config['site_url'].ltrim($url,'.');
		}
		return $url;
	}
	public function login(){
		if($_POST['ticket']){
            $info = ticket::get($_POST['ticket'],'wxapp', true);
            if ($info && $info['uid']){
				if($_POST['isPlatUser']){
					$now_user = D('User')->get_user($info['uid']);
					if($now_user){
						$ticket = ticket::create($now_user['uid'],'wxapp', true);
						$return = array(
							'ticket'=>	$ticket['ticket'],
							'user'	=>	$now_user,
						);
						$this->returnCode(0,$return);
					}else{
						$this->returnCode(0,array('emptyUser'=>true));
					}
				}else{
					$now_bind_user = D('Weixin_bind_user')->get_info($_POST['mer_id'],'id',$info['uid']);
					if($now_bind_user && $now_bind_user['uid']){
						$now_user = D('User')->get_user($now_bind_user['uid']);
						if($now_user){
							$ticket = ticket::create($now_user['uid'],'wxapp', true);
							$return = array(
								'ticket'=>	$ticket['ticket'],
								'user'	=>	$now_user,
							);
							$this->returnCode(0,$return);
						}
					}
					if(!empty($now_bind_user)){
						$ticket = ticket::create($now_bind_user['id'],'wxapp', true);
						$return = array(
							'ticket'=>	$ticket['ticket'],
							'user'	=>	$now_bind_user,
						);
						$this->returnCode(0,$return);
					}else{
						$this->returnCode(0,array('emptyUser'=>true));
					}
				}
            }else{
				$this->returnCode(0,array('emptyUser'=>true));
			}
        }
		
		$now_bind = M('Weixin_app_bind')->where(array('bind_type'=>0,'other_id'=>$_POST['mer_id']))->find();
		
		$appid = $now_bind['appid'];
		$appsecret = $now_bind['appsecret'];
		
		import('ORG.Net.Http');
		$http = new Http();

		$return = Http::curlPost('https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code='.$_POST['code'].'&grant_type=authorization_code', array());
		
		if($return['errcode'] == 40125){
			$this->returnCode($return['errcode'],array(),$return['errmsg']);
		}
		
		
		import('@.ORG.aeswxapp.wxBizDataCrypt');
		
		$pc = new WXBizDataCrypt($appid, $return['session_key']);
		$errCode = $pc->decryptData($_POST['encryptedData'],$_POST['iv'],$data);
		$jsonrt = json_decode($data,true);
		if(!empty($jsonrt['unionId'])){
			$now_bind_user = D('Weixin_bind_user')->get_info($now_bind['other_id'],'union_id',$jsonrt['unionId']);
			if($now_bind_user && $now_bind_user['uid']){
				$now_user = D('User')->get_user($now_bind_user['uid']);
				if($now_user){
					$ticket = ticket::create($now_user['uid'],'wxapp', true);
					$return = array(
						'ticket'=>	$ticket['ticket'],
						'user'	=>	$now_user,
					);
					$this->returnCode(0,$return);
				}
			}
		}else{
			$now_bind_user = D('Weixin_bind_user')->get_info($now_bind['other_id'],'wxapp_openid',$jsonrt['openId']);
			if($now_bind_user && $now_bind_user['uid']){
				$now_user = D('User')->get_user($now_bind_user['uid']);
				if($now_user){
					$ticket = ticket::create($now_user['uid'],'wxapp', true);
					$return = array(
						'ticket'=>	$ticket['ticket'],
						'user'	=>	$now_user,
					);
					$this->returnCode(0,$return);
				}
			}
		}
		if(empty($now_bind_user)){
			$data_bind_user = array(
				'uid'			=> 0,
				'mer_id'		=> $now_bind['other_id'],
				'wxapp_openid' 	=> $jsonrt['openId'],
				'union_id' 	=> ($jsonrt['unionId'] ? $jsonrt['unionId'] : ''),
				'nickname' 	=> $jsonrt['nickName'],
				'sex' 		=> $jsonrt['gender'],
				'province' 	=> $jsonrt['province'],
				'city' 		=> $jsonrt['city'],
				'avatar' 	=> str_replace('http://wx.qlogo.cn','https://thirdwx.qlogo.cn',$jsonrt['avatarUrl']),
				'source' 	=> 'merchant_wxapp'
			);
			$data_bind_user['id'] = M('Weixin_bind_user')->data($data_bind_user)->add();
			if($data_bind_user['id']){
				$now_bind_user = $data_bind_user;
			}
		}
		
		if(!empty($now_bind_user)){
			$ticket = ticket::create($now_bind_user['id'],'wxapp', true);
			$return = array(
				'ticket'=>	$ticket['ticket'],
				'user'	=>	$now_bind_user,
			);
			$this->returnCode(0,$return);
		}else{
			$this->returnCode(0,array('emptyUser'=>true));
		}
	}
	protected function auto_login($now_bind_user,$field,$openid = ''){
		if($now_bind_user['uid']){
			$login_result = D('User')->autologin('uid',$now_bind_user['uid']);
			if(empty($login_result['error_code'])){
				$now_user = $result['user'];
				if($field == 'union_id' && empty($now_user['wxapp_openid'])){
					$condition_bind_user['union_id'] = $value;
					M('Weixin_bind_user')->where($condition_bind_user)->data(array('wxapp_openid'=>$openid))->save();
				}
				$now_user['can_withdraw_money'] = floatval($now_user['now_money'])-floatval($now_user['score_recharge_money']);
			}
			return $now_user;
		}
	}
    public function index(){
		$now_bind = M('Weixin_app_bind')->where(array('bind_type'=>0,'other_id'=>$_POST['mer_id']))->find();
		$condition_page['page_id'] = $now_bind['mer_index_page'];
		$now_page = M('Merchant_store_diypage')->where($condition_page)->find();
		if(empty($now_page)){
			$this->returnCode(1000,array(),'商家未设置主页');
		}
		D('Merchant_store_diypage')->where($condition_page)->setInc('hits');
		
		$condition_field['page_id'] = $now_page['page_id'];
		$field_list = M('Merchant_store_diypage_field')->field('`field_type`,`content`')->where($condition_field)->order('`field_id` ASC')->select();
		
		//主店信息
		$now_store = D('Merchant_store')->get_store_by_storeId($now_page['store_id']);
		if($now_store){
			import('@.ORG.longlat');
			$longlat = new longlat();
			$tmpGcj02 = $longlat->baiduToGcj02($now_store['lat'],$now_store['long']);
			$now_store['gcj02_long'] = $tmpGcj02['lng'];
			$now_store['gcj02_lat'] = $tmpGcj02['lat'];
			
			$now_store['good_count'] = M('Shop_goods')->where(array('store_id' => $now_store['store_id'],'status'=>1))->count();
			
			$now_store_shop = D('Merchant_store_shop')->where(array('store_id'=>$now_store['store_id']))->find();
			if($now_store_shop){
				$now_store['shop_store_theme'] = $now_store_shop['store_theme'];
			}
		}else{
			$now_store = array();
		}
		
		$image_ad_index = 0;
		foreach($field_list as &$value){
			$value['content'] = unserialize($value['content']);
			if($value['field_type'] == 'tpl_shop'){
				$value['content']['shop_head_bg_img'] = $this->replaceImg($value['content']['shop_head_bg_img']);
				$value['content']['shop_head_logo_img'] = $this->replaceImg($now_store['all_pic'][0]);
				$value['content']['shop_url'] = $this->config['site_url'].'/wap.php?c=Mall&a=store&store_id='.$now_store['store_id'];
				$value['content']['card_url'] = $this->config['site_url'].'/wap.php?c=My_card&a=merchant_card&mer_id='.$now_page['mer_id'];
				$value['content']['order_url'] = $this->config['site_url'].'/wap.php?c=My_card&a=merchant_order&mer_id='.$now_page['mer_id'];
			}
			if($value['field_type'] == 'notice'){
				$value['content']['length'] = $this->dstrlen($value['content']['content'])/2;
			}
			if($value['field_type'] == 'rich_text'){
				$value['content']['content'] = str_replace('<img src="/upload/','<img src="'.$this->config['site_url'].'/upload/',$value['content']['content']);
			}
			if($value['field_type'] == 'image_nav'){
				foreach($value['content'] as $k=>$v){
					$value['content'][$k]['image'] = $this->replaceImg($v['image']);
				}
			}
			if($value['field_type'] == 'image_ad'){
				foreach($value['content']['nav_list'] as $k=>$v){
					$value['content']['nav_list'][$k]['index'] = $image_ad_index;
					$value['content']['nav_list'][$k]['image'] = $this->replaceImg($v['image']);
					$image_ad_index++;
				}
				$value['content']['nav_list'] = array_values($value['content']['nav_list']);
			}
			if($value['field_type'] == 'coupons'){
				$tmpArr = [];
				foreach($value['content']['coupon_arr'] as $k=>$v){
					$tmpArr[] = $v['id'];
				}
				$value['content']['coupon_arr'] = D('Card_new_coupon')->get_coupon_list_by_ids($tmpArr,true);
				$value['content']['coupon_arr'] = array_values($value['content']['coupon_arr']);
			}
			if($value['field_type'] == 'map'){
				$value['content']['lng'] = $now_store['gcj02_long'];
				$value['content']['lat'] = $now_store['gcj02_lat'];
				$value['content']['markers'] = array(
					array(
						'latitude' => $value['content']['lat'],
						'longitude' => $value['content']['lng'],
						'title' => $now_store['name'],
						'iconPath' => '../../images/my_pos.png',
						'callout' => array(
							'content' => $now_store['name'],
							'color' => '#000000',
							'fontSize' => 14,
							'borderRadius' => 8,
							'bgColor' => '#FFFFFF',
							'padding' => 14,
							'display' => 'ALWAYS',
						),
					),
				);
			}
			if($value['field_type'] == 'goods'){
				if($value['content']['size'] == 1 && $value['content']['size_type'] == 2){
					$value['content']['show_title'] = 0;
				}
				if(!empty($value['content']['goods'])){
					foreach ($value['content']['goods'] as $k=>$v){
						$product = D('Shop_goods')->get_goods_by_id($v['id']);
						if(empty($product)){
							unset($value['content']['goods'][$k]);
						}else{
							$value['content']['goods'][$k]['price'] = $product['price'];
							$value['content']['goods'][$k]['title'] = $product['name'];
							$value['content']['goods'][$k]['image'] = $this->replaceImg($value['content']['goods'][$k]['image']);
							$value['content']['goods'][$k]['url'] = 'c=Mall&a=detail&product_id='.$v['id'].'&otherLink=1';
						}
					}
				}
			}
		}
		$return['now_store'] = $now_store; 
		$return['now_page'] = $now_page; 
		$return['field_list'] = $field_list; 
		
		$this->returnCode(0,$return);
    }
	public function had_pull(){
        $coupon_id = $_POST['coupon_id'];
        $uid = $this->_uid;
        $model = D('Card_new_coupon');

        $result = $model->had_pull($coupon_id, $uid,'',true);
		if ($result['error_code'] != 0) {
			switch ($result['error_code']) {
				case '1':
					$error_msg = '领取失败';
					break;
				case '2':
                    $error_msg = '优惠券已过期';
                    break;
				case '3':
                    $error_msg = '优惠券已经领完了';
                    break;
				case '4':
                    $error_msg = '只允许新用户领取';
                    break;
				case '5':
                    $error_msg = '您已经领取过，不能再领取该优惠券了';
                    break;
			}
			$this->returnCode(1001,array(),$error_msg);
        }
		$model->decrease_sku(0,1,$coupon_id);//网页领取完，微信卡券库存需要同步减少
		$this->returnCode(0,$result['coupon']);
    }
	public function merchant_card(){
		//商家信息
        $now_merchant = D('Merchant')->get_info($_POST['mer_id']);
        $now_card = M('Card_new')->where(array('mer_id' => $now_merchant['mer_id']))->find();
        $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->_uid, $now_merchant['mer_id']);
       
        if (empty($card_info['id'])) {
            if ($now_card['self_get']) {
                $result = D('Card_new')->auto_get($this->_uid, $now_merchant['mer_id']);
                if (!$result['error_code']) {
                    $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->_uid, $now_merchant['mer_id']);
                } else {
					$this->returnCode(1001,array(),$result['msg']);
                }
            } else {
				$this->returnCode(1001,array(),'您没有会员卡，请去领卡！');
            }

        } elseif ($card_info['status'] == 0) {
			$this->returnCode(1001,array(),'您的会员卡不能使用！');
        }

		$card_info['real_card_money'] = $card_info['card_money'] + $card_info['card_money_give'];
		if($card_info['discount'] == 10){
			$card_info['discount'] = 0;
		}
		if($card_info['wx_param']){
			$card_info['wx_param_arr'] = unserialize($card_info['wx_param']);
		}else{
			$card_info['wx_param_arr']['notice'] = '出示会员卡，扫码即可';
		}
        if(!empty($card_info['diybg'])){
            $card_info['card_bg'] = $card_info['diybg'];
        }elseif(!empty($card_info['bg'])){
			$card_info['card_bg'] = $card_info['bg'];
        }
		$card_info['card_bg'] = $this->replaceImg($card_info['card_bg']);
		
		$return['now_card'] = $now_card;
		$return['card_info'] = $card_info;
		$this->returnCode(0,$return);
	}
	public function merchant_coupon(){
        $coupon_list = D('Card_new_coupon')->get_user_coupon_list($this->_uid, $_POST['mer_id']);
        $tmp = array(
			0 => array(),
			1 => array(),
			2 => array(),
		);
        foreach ($coupon_list as $key => $v) {
            if (!empty($tmp[$v['is_use']][$v['coupon_id']])) {
                $tmp[$v['is_use']][$v['coupon_id']]['get_num']++;
            } else {
				$tmpV['order_money'] = getFormatNumber($v['order_money']);
				$tmpV['time'] = '有效期至'.date('y.m.d',$v['end_time']);
				$tmpMoney = explode('.',$v['discount']);
				$tmpV['int'] = $tmpMoney[0];
				$tmpV['float'] = $tmpMoney[1];
				$tmpV['is_use'] = $v['is_use'];
				$tmpV['coupon_id'] = $v['coupon_id'];
				
                $tmp[$v['is_use']][$v['coupon_id']] = $tmpV;
                $tmp[$v['is_use']][$v['coupon_id']]['get_num'] = 1;
            }
        }
		$return['not_use']['count'] = 0;
		$return['use']['count'] = 0;
		$return['over']['count'] = 0;
		foreach($tmp[0] as &$value){
			$return['not_use']['count'] += $value['get_num'];
		}
		$return['not_use']['list'] = array_values($tmp[0]);
		
		foreach($tmp[1] as &$value){
			$return['use']['count'] += $value['get_num'];
		}
		$return['use']['list'] = array_values($tmp[1]);
		
		foreach($tmp[2] as &$value){
			$return['over']['count'] += $value['get_num'];
		}
		$return['over']['list'] = array_values($tmp[2]);
		
        $this->returnCode(0,$return);
    }
	public function store_list(){
		if($_POST['lng'] && $_POST['lat']){
			$user_long_lat['lat'] = $_POST['lat'];
			$user_long_lat['long'] = $_POST['lng'];
		}
		//店铺列表
        $store_list = D('Merchant_store')->get_store_list_by_merId($_POST['mer_id'],$_POST['store_type']);
		$new_store_list = array();
		if($store_list){
	        foreach($store_list as &$v){
				if($user_long_lat){
					$newV['Srange'] = getDistance($user_long_lat['lat'], $user_long_lat['long'], $v['lat'], $v['long']);
					$newV['range'] = getRange($newV['Srange'], false);
				}
				$newV['store_id'] = $v['store_id'];
				$newV['name'] = $v['name'];
				$newV['adress'] = $v['adress'];
				$newV['lng'] = $v['long'];
				$newV['lat'] = $v['lat'];
				$newV['phone'] = $v['phone'];
				$newV['url']	=	U('Merchant/shop',array('store_id'=>$v['store_id']));
				$new_store_list[] = $newV;
	        }
        }
		$new_store_list = sortArrayAsc($new_store_list,'Srange');
		$this->returnCode(0,$new_store_list);
	}
	public function order_type_list(){
		$return = array();
		//店铺列表
        $store_list = D('Merchant_store')->get_store_list_by_merId($_POST['mer_id']);
		foreach($store_list as $value){
			if(empty($return['shop']) && $value['have_shop']){
				$return['shop'] = array(
					'name' => $this->config['shop_alias_name'],
					'url'  => 'c=My&a=shop_order_list&mer_id='.$_POST['mer_id'],
				);
			}
			if(empty($return['group']) && $value['have_group']){
				$return['group'] = array(
					'name' => $this->config['group_alias_name'],
					'url'  => 'c=My&a=group_order_list&mer_id='.$_POST['mer_id'],
				);
			}
		}
		if($this->config['pay_in_store']){
			$return['store'] = array(
				// 'name' => $this->config['cash_alias_name'],
				'name' => '到店付',
				'url'  => 'c=My&a=store_order_list&mer_id='.$_POST['mer_id'],
			);
		}
		$return = array_values($return);
		$this->returnCode(0,$return);
	}
	public function merchant_transrecord(){
        $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->_uid, $_POST['mer_id']);
        $record = D('Card_new')->card_use_record($card_info['id'], 'all');
		if(empty($record['record'])){
			$record['record'] = array();
		}else{
			foreach($record['record'] as &$value){
				$value['time_txt'] = date('Y-m-d H:i:s',$value['time']);
			}
		}
		$this->returnCode(0,$record['record']);
    }
	public static function dstrlen($string){
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n ++;
                $noc ++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n ++;
            }
        }
        return $noc;
    }
}