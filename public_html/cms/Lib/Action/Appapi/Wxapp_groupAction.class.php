<?php
class Wxapp_groupAction extends BaseAction{
	public function wxapp_login(){
        if($_POST['ticket']){
            if ($this->_uid){
                $now_user = $this->autologin('uid',$this->_uid);
                if(!empty($now_user)){
                    $return = array(
                        'ticket'    =>	$_POST['ticket'],
                        'user'      =>	$now_user,
                    );
					$plat_wxapp_ticket_info = ticket::create($now_user['uid'],'wxapp', true);
					$return['user']['plat_wxapp_ticket'] = $plat_wxapp_ticket_info['ticket'];
                    $this->returnCode(0, $return);
                }else{
                    $this->returnCode(0,array('emptyUser'=>true));
                }
            }else{
                $this->returnCode(0,array('emptyUser'=>true));
            }
        }

        $appid = $this->config['pay_wxapp_groupwxapp_appid'];
        $appsecret = $this->config['pay_wxapp_groupwxapp_appsecret'];


        import('ORG.Net.Http');
        $http = new Http();

        $return = Http::curlPost('https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code='.$_POST['code'].'&grant_type=authorization_code', array());

        import('@.ORG.aeswxapp.wxBizDataCrypt');

        $pc = new WXBizDataCrypt($appid, $return['session_key']);
        $errCode = $pc->decryptData($_POST['encryptedData'],$_POST['iv'],$data);
        $jsonrt = json_decode($data,true);
        /*优先使用 unionId 登录*/
        if(!empty($jsonrt['unionId'])){
            $now_user = $this->autologin('union_id',$jsonrt['unionId'],$jsonrt['openId']);
        }else{
            /*再次使用 openId 登录*/
            $now_user = $this->autologin('wxappgroup_openid',$jsonrt['openId']);
        }
		$jsonrt['avatarUrl'] = str_replace('http://wx.qlogo.cn','https://thirdwx.qlogo.cn',$jsonrt['avatarUrl']);
        if(empty($now_user)){
            $data_user = array(
                'wxappgroup_openid' 	=> $jsonrt['openId'],
                'union_id' 	=> ($jsonrt['unionId'] ? $jsonrt['unionId'] : ''),
                'nickname' 	=> $jsonrt['nickName'],
                'sex' 		=> $jsonrt['gender'],
                'province' 	=> $jsonrt['province'],
                'city' 		=> $jsonrt['city'],
                'avatar' 	=> $jsonrt['avatarUrl'],
                'is_follow' => 1,
                'source' 	=> 'wxapp_groupwxapp'
            );
            $reg_result = D('User')->autoreg($data_user);
            if(!$reg_result['error_code']){
                $now_user = $this->autologin('wxappgroup_openid',$jsonrt['openId']);
            }
        }else{
			if($now_user['avatar'] != $jsonrt['avatarUrl']){
				D('User')->save_user($now_user['uid'],'avatar',$jsonrt['avatarUrl']);
			}
		}

        if(!empty($now_user)){
            $ticket = ticket::create($now_user['uid'],'wxapp_groupwxapp', true);
            $return = array(
                'ticket'=>	$ticket['ticket'],
                'user'	=>	$now_user,
            );
			
			$plat_wxapp_ticket_info = ticket::create($now_user['uid'],'wxapp', true);
			$return['user']['plat_wxapp_ticket'] = $plat_wxapp_ticket_info['ticket'];
			
            $this->returnCode(0,$return);
        }else{
            $this->returnCode(0,array('emptyUser'=>true));
        }
    }
    protected function autologin($field,$value,$openid = ''){
        $result = D('User')->autologin($field,$value);
        $now_user = array();
        if(empty($result['error_code'])){
            if($field == 'union_id' && empty($result['user']['wxappgroup_openid'])){
                $condition_user['union_id'] = $value;
                D('User')->where($condition_user)->data(array('wxappgroup_openid'=>$openid))->save();
                $result['user']['wxappgroup_openid'] = $openid;
            }
            $result['user']['showPhone'] = substr_replace($result['user']['phone'], '****', 3, 4);
            $now_user = $result['user'];
        }
        return $now_user;
    }
	
	//得到团购顶级分类
	public function index(){
		$cat_list = M('Group_wxapp_category')->where(array('cat_status'=>'1'))->order('`cat_sort` DESC')->select();
		if(!$cat_list){
			$return_cat_list = array();
		}else{
			foreach($cat_list as $value){
				$return_cat_list[] = array(
					'cat_id'=>$value['cat_id'],
					'cat_name'=>$value['cat_name'],
				);
			}
		}
		$returnArr['cat_list'] = $return_cat_list;
		
		//顶部广告
		$head_adver = D('Adver')->get_adver_by_key('app_index_top',5);
		if(empty($head_adver)){
			$head_adver = D('Adver')->get_adver_by_key('wap_index_top',5);
		}
		if(!empty($head_adver)){
			foreach($head_adver as &$head_adver_value){
				unset($head_adver_value['id'],$head_adver_value['bg_color'],$head_adver_value['cat_id'],$head_adver_value['status'],$head_adver_value['last_time'],$head_adver_value['sub_name']);
			}
			$returnArr['head_adver'] = $head_adver;
		}else{
			$returnArr['head_adver'] = array();
		}
		
		//导航条
        $tmp_wap_index_slider = D('Slider')->get_slider_by_key('wap_slider',5);
        $wap_index_slider = array();
        foreach($tmp_wap_index_slider as $key=>$value){
            if(!stristr($value['url'],'Weidian') && !stristr($value['url'],'Invitation')&& !stristr($value['url'],'City_car')){
                $wap_index_slider[] = $value;
            }
        }
        foreach($wap_index_slider as $v){
            $v['url']  =   htmlspecialchars_decode($v['url']);
            $returnArr['index_slider'][]  =   $v;
        }
        if(empty($returnArr['index_slider'])){
            $returnArr['index_slider']  =   array();
        }
		
		
		$now_time = $_SERVER['REQUEST_TIME'];
		//推荐优选，6个
		$condition_table  = array(C('DB_PREFIX').'group'=>'g', C('DB_PREFIX').'group_wxapp_product' => 'gwp',C('DB_PREFIX').'merchant'=>'m');
		$condition_where = "`m`.`city_id`='".C('config.now_city')."' AND `g`.`mer_id` = `m`.`mer_id` AND `gwp`.`group_id`=`g`.`group_id` AND `g`.`status`='1'  AND `g`.`type`='1' AND `g`.`begin_time`<'$now_time' AND `g`.`end_time`>'$now_time'";
		$condition_field  = '`g`.`name` AS `group_name`,`g`.`group_id`,`g`.`old_price`,`g`.`pin_num`,`g`.`sale_count`,`g`.`virtual_num`,`gwp`.`product_price`,`gwp`.`product_pic`';
		$group_list = M('')->field($condition_field)->table($condition_table)->where($condition_where)->order('`gwp`.`product_sort` DESC')->limit(6)->select();
		if(!$group_list){
			$group_list = array();
		}else{
			$group_image_class = new group_image();
			foreach($group_list as $key=>$value){
				$group_list[$key]['pin_num'] = floatval($value['pin_num']);
				$group_list[$key]['sale_count'] =$value['sale_count']+$value['virtual_num'];
				$group_list[$key]['list_pic'] = $this->config['site_url'].'/upload/groupwxapp/'.$value['product_pic'];
			}
		}
		$returnArr['group_list'] = $group_list;
		
		$this->returnCode(0,$returnArr);
	}
	
	public function product_list(){
		$page 		= !empty($_POST['page']) ? ($_POST['page']-1) : 0;
		$pageRow 	= 10;
		$pageFirst  = $page*$pageRow;
		
		
		$now_time = $_SERVER['REQUEST_TIME'];
		//推荐优选，6个
		$condition_table  = array(C('DB_PREFIX').'group'=>'g', C('DB_PREFIX').'group_wxapp_product' => 'gwp',C('DB_PREFIX').'merchant'=>'m');
		$condition_where = "`m`.`city_id`='".C('config.now_city')."' AND `g`.`mer_id` = `m`.`mer_id` AND `gwp`.`group_id`=`g`.`group_id` AND `g`.`status`='1'  AND `g`.`type`='1' AND `g`.`begin_time`<'$now_time' AND `g`.`end_time`>'$now_time'";
		if($_POST['cat_id']){
			$condition_where.= " AND `gwp`.`cat_id`='{$_POST['cat_id']}'";
		}
		$condition_field  = '`g`.`name` AS `group_name`,`g`.`group_id`,`g`.`old_price`,`g`.`pin_num`,`g`.`sale_count`,`g`.`virtual_num`,`gwp`.`product_price`,`gwp`.`product_pic`';
		$group_list = M('')->field($condition_field)->table($condition_table)->where($condition_where)->order('`gwp`.`product_sort` DESC')->limit($pageFirst.','.$pageRow)->select();
		if(!$group_list){
			$group_list = array();
		}else{
			$group_image_class = new group_image();
			foreach($group_list as $key=>$value){
				$group_list[$key]['pin_num'] = floatval($value['pin_num']);
				$group_list[$key]['sale_count'] =$value['sale_count']+$value['virtual_num'];
				$group_list[$key]['list_pic'] = $this->config['site_url'].'/upload/groupwxapp/'.$value['product_pic'];
			}
		}
		
		$this->returnCode(0,$group_list);
	}
	
	public function search_list(){
		$page 		= !empty($_POST['page']) ? ($_POST['page']-1) : 0;
		$pageRow 	= 10;
		$pageFirst  = $page*$pageRow;
		
		
		$now_time = $_SERVER['REQUEST_TIME'];
		//推荐优选，6个
		$condition_table  = array(C('DB_PREFIX').'group'=>'g', C('DB_PREFIX').'group_wxapp_product' => 'gwp',C('DB_PREFIX').'merchant'=>'m');
		$condition_where = "`m`.`city_id`='".C('config.now_city')."' AND `g`.`mer_id` = `m`.`mer_id` AND `gwp`.`group_id`=`g`.`group_id` AND `g`.`status`='1'  AND `g`.`type`='1' AND `g`.`begin_time`<'$now_time' AND `g`.`end_time`>'$now_time' AND `g`.`name` LIKE '%".$_POST['txt']."%'";
		
		$condition_field  = '`g`.`name` AS `group_name`,`g`.`group_id`,`g`.`old_price`,`g`.`pin_num`,`g`.`sale_count`,`g`.`virtual_num`,`gwp`.`product_price`,`gwp`.`product_pic`';
		$group_list = M('')->field($condition_field)->table($condition_table)->where($condition_where)->order('`gwp`.`product_sort` DESC')->limit($pageFirst.','.$pageRow)->select();
		
		// echo M()->getLastSql();die;
		
		if(!$group_list){
			$group_list = array();
		}else{
			$group_image_class = new group_image();
			foreach($group_list as $key=>$value){
				$group_list[$key]['pin_num'] = floatval($value['pin_num']);
				$group_list[$key]['sale_count'] =$value['sale_count']+$value['virtual_num'];
				$group_list[$key]['list_pic'] = $this->config['site_url'].'/upload/groupwxapp/'.$value['product_pic'];
			}
		}
		
		$this->returnCode(0,$group_list);
	}
	public function group_share_img(){
		$group_id	=	I('group_id');
		
		$now_wxapp_group = M('Group_wxapp_product')->where(array('group_id'=>$group_id))->find();
		if(empty($now_wxapp_group)){
			$this->returnCode('1001',array(),'此商品不是优选商品');
		}
		
		$now_group = D('Group')->get_group_by_groupId($group_id,'hits-setInc');
		if(empty($now_group)){
			$this->returnCode('20046010');
		}
		$group_id = $now_group['group_id'];
		$group_name = $now_group['group_name'].'商品图片绘制商品图片绘制';
		$group_price = number_format($now_group['price'],2);
		
		$img_rand_path = sprintf("%09d", $group_id);
		$rand_num = substr($img_rand_path, 0, 3) . '/' . substr($img_rand_path, 3, 3) . '/' . substr($img_rand_path, 6, 3);
		$imgFriendPath = 'upload/wxapp_group/'.$rand_num.'/friend_'.$group_id.'.png';
		$imgGroupPath = 'upload/wxapp_group/'.$rand_num.'/group_'.$group_id.'.png';
		if(file_exists($imgGroupPath)){
			$share_img_arr = array(
				'friend_img'=>C('config.site_url').'/'.$imgFriendPath,
				'group_img'=>C('config.site_url').'/'.$imgGroupPath,
			);
			$this->returnCode(0,$share_img_arr);
		}
		if(!file_exists(dirname($imgFriendPath))){
            mkdir(dirname($imgFriendPath),0777,true);
        }
		
		$goodMainPic = 'upload/groupwxapp/'.$now_wxapp_group['product_pic'];
		
		/*
		 *   分享朋友圈 600*822 图片
		 */
			$img = imagecreatetruecolor(600,822);
			
			$white  =  imagecolorallocate ( $img ,  255 ,  255 ,  255 );
			imagefill ( $img ,  0 ,   0 ,  $white );
			
			//背景图片绘制
				$src_im = imagecreatefrompng('static/wxapp/wxapp_group_bg.png');
				//裁剪
				// imagecopyresampled($newimg, $src_im, 0, 0, 0,0, 600,822,600,822);
				// imagedestroy($src_im); //销毁原图
				imagecopy($img,$src_im,0,0,0,0,600,822);
			
			//商品图片绘制
				$info   = getimagesize($goodMainPic);
				$fun    = 'imagecreatefrom'.image_type_to_extension($info[2], false);
				$src_im =  call_user_func_array($fun,array($goodMainPic));
			
			
			//创建新图像
				$newimg = imagecreatetruecolor(510,510);
				// 调整默认颜色
				$color = imagecolorallocate($newimg, 255, 255, 255);
				imagefill($newimg, 0, 0, $color);
				//裁剪
				imagecopyresampled($newimg, $src_im, 0, 0, 0,0, 510,510,$info[0],$info[1]);
				imagedestroy($src_im); //销毁原图
				imagecopy($img,$newimg,45,45,0,0,510,510);
			
			//商品标题
				$font = './static/fonts/apple_lihei.otf';
				$tmpGoodName = $group_name;
				$goodNameArr = array();
				$goodNameArr[] = mb_strimwidth($tmpGoodName,0,30,'','utf-8');
				if($group_name != $goodNameArr[0]){
					$tmpGoodName = str_replace($goodNameArr[0],'',$tmpGoodName);
					$goodNameArr[] = mb_strimwidth($tmpGoodName,0,30,'...','utf-8');
				}
				// dump($goodNameArr);die;
				$good_name = implode($goodNameArr,"\n");
				$fontSize = 18;//像素字体
				$fontColor = imagecolorallocate ($img, 0, 0, 0 );//字的RGB颜色
				$fontBox = imagettfbbox($fontSize, 0, $font,$good_name);//文字水平居中实质
				imagettftext ( $img, $fontSize, 0, 45, 660, $fontColor, $font, $good_name);
			
			//实际价格
				$fontSize = 26;//像素字体
				$fontColor = imagecolorallocate ($img, 238, 0, 0 );//字的RGB颜色
				$fontBox = imagettfbbox($fontSize, 0, $font,'￥'.$group_price);//文字水平居中实质
				imagettftext ($img, $fontSize, 0, 40, 760, $fontColor, $font, '￥'.$group_price);
			
			//小程序二维码
				$qrcode = $this->get_wxapp_qrcode('/pages/index/product?group_id='.$group_id,280);
				if(!file_exists('./runtime/tempQrcode')){
					mkdir('./runtime/tempQrcode',0777,true);
				}
				$filePath = './runtime/tempQrcode/'.uniqid().'.jpg';
				file_put_contents($filePath,$qrcode);
				
				$src_im =  imagecreatefromjpeg($filePath);
				
				//创建新图像
				$newimg = imagecreatetruecolor(145,145);
				// 调整默认颜色
				$color = imagecolorallocate($newimg, 255, 255, 255);
				imagefill($newimg, 0, 0, $color);
				//裁剪
				imagecopyresampled($newimg, $src_im, 0, 0, 0,0, 145,145,280,280);
				imagedestroy($src_im); //销毁原图
				
				imagecopy($img,$newimg,412,630,0,0,145,145);
				
				imagepng($img,$imgGroupPath);
		
		
		
		$share_img_arr = array(
			'friend_img'=>C('config.site_url').'/'.$imgFriendPath,
			'group_img'=>C('config.site_url').'/'.$imgGroupPath,
		);
		$this->returnCode(0,$share_img_arr);
		
		// header('Content-type: image/png');
		// imagepng($img);
		// die;
	}
	
	
	protected function get_wxapp_qrcode($path,$width){
		$wxapp_access_token = D('Access_token_wxapp_group_expires')->get_access_token();
		$wxapp_access_token = $wxapp_access_token['access_token'];
		
		import('ORG.Net.Http');
		$http = new Http();
		$url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$wxapp_access_token;
		$postData = array(
			'path'=>$path,
			'width'=>$width
		);
		$qrcode = $http->curlPostOwn($url,json_encode($postData));
		
		return $qrcode;
	}
}
?>