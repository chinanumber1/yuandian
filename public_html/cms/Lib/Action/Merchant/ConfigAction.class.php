<?php
/*
 * 系统配置
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/05 15:28
 *
 */

class ConfigAction extends BaseAction{
	public $staff_type=array(0=>'店小二',1=>'核销',2=>'店长');
	/* 商家设置 */
    public function merchant(){
		$database_merchant = D('Merchant');
		if(IS_POST){
			$data_merchant['phone'] = $_POST['phone'];
			if(empty($data_merchant['phone'])){
				$this->error('请输入联系人电话');
			}

			$data_merchant['email'] = $_POST['email'];
			$data_merchant['open_money_tempnews'] = $_POST['open_money_tempnews'];
			$data_merchant['is_offline'] = isset($_POST['is_offline']) ? intval($_POST['is_offline']) : 1;
			// if(empty($data_merchant['email'])){
				// $this->error('请输入联系人邮箱');
			// }

			if(!empty($_POST['new_pass'])){
				$condition_merchant['mer_id'] = $this->merchant_session['mer_id'];
				$now_merchant = $database_merchant->field('`pwd`')->where($condition_merchant)->find();
				if(md5($_POST['old_pass']) != $now_merchant['pwd']){
					$this->error('原密码输入错误');
				}else if(strlen($_POST['new_pass']) < 6){
					$this->error('新密码最少6个字符');
				}else if($_POST['new_pass'] != $_POST['re_pass']){
					$this->error('两次新密码输入不一致，请重新输入');
				}else{
					$data_merchant['pwd'] = md5($_POST['new_pass']);
				}
			}

			if(empty($_POST['pic'])){
				$this->error('请至少上传一张图片');
			}
			$data_merchant['pic_info'] = implode(';',$_POST['pic']);

			$data_merchant['txt_info'] = $_POST['txt_info'];
			$data_merchant['group_express_outtime'] = $_POST['group_express_outtime'];
			if(!is_numeric($_POST['group_express_outtime'])||$_POST['group_express_outtime']<0){
				$this->error('超时时间设置有误');
			}

			if(empty($data_merchant['txt_info'])){
				$this->error('请输入商家描述信息');
			}
			$data_merchant['adverimg']=isset($_POST['adverimg']) ? trim($_POST['adverimg']) : '';
			$data_merchant['mer_id'] = $this->merchant_session['mer_id'];
			$data_merchant['scan_group_show_other'] = $_POST['scan_group_show_other'];

			if($_POST['logo']){
				$data_merchant['logo'] = $_POST['logo'];
			}
			if($this->config['open_merchant_reg_sms']==1){

//				if(empty($_POST['new_phone'])){
//					$this->error('新手机为空');
//				}
//				if(empty($_POST['SmsCode'])){
//					$this->error('老手机验证码为空');
//				}
//				if(empty($_POST['SmsCode2'])){
//					$this->error('新手机验证码为空');
//				}

				if( $_POST['old_phone'] && $_POST['new_phone'] && $_POST['SmsCode'] && $_POST['SmsCode2']) {
					$now_merchant = M('Merchant')->where(array('phone' => $_POST['new_phone']))->find();
					if ($now_merchant) {
						$this->error('手机已绑定其他商家');
					}

					$where = array();
					$where['mer_id'] = $this->merchant_session['mer_id'];
					$where['extra'] = $_POST['SmsCode'];
					$where['status'] = 0;
					$where['expire_time'] = array('gt', time());
					$sms_result = M("Merchant_sms_record")->where($where)->find();
					if (!$sms_result) {
						$this->error('旧手机验证码错误');
					}

					$where = array();
					$where['mer_id'] = $this->merchant_session['mer_id'];
					$where['extra'] = $_POST['SmsCode2'];
					$where['status'] = 0;
					$where['expire_time'] = array('gt', time());
					$sms_result = M("Merchant_sms_record")->where($where)->find();
					if (!$sms_result) {
						$this->error('新手机验证码错误');
					}
//				$condition_merchant['mer_id'] = $this->merchant_session['mer_id'];
					$data_merchant['phone'] = $_POST['new_phone'];
				}
			}else{
				$_POST['new_phone'] && $data_merchant['phone'] = $_POST['new_phone'];
			}


			$_POST['phone_country_type'] && $data_merchant['phone_country_type'] = $_POST['phone_country_type'];
			if($database_merchant->data($data_merchant)->save()){
				$_SESSION['merchant']['logo'] = $data_merchant['logo'];
				$this->success('保存成功！');
			}else{
				$this->error('保存失败！请检查是否有修改过内容后重试');
			}
		}else{
			$condition_merchant['mer_id'] = $this->merchant_session['mer_id'];
			$now_merchant = $database_merchant->field(true,'pwd')->where($condition_merchant)->find();
			if(!empty($now_merchant['pic_info'])){
				$merchant_image_class = new merchant_image();
				$now_merchant['adverimgurl']=!empty($now_merchant['adverimg']) ? $merchant_image_class->get_image_by_path($now_merchant['adverimg']) :'';
				$tmp_pic_arr = explode(';',$now_merchant['pic_info']);
				foreach($tmp_pic_arr as $key=>$value){
					$now_merchant['pic'][$key]['title'] = $value;
					$now_merchant['pic'][$key]['url'] = $merchant_image_class->get_image_by_path($value);
				}
			}
			$this->assign('now_merchant',$now_merchant);
			$mer_percent_rate = M('Merchant_percent_rate')->where(array('mer_id'=>$this->merchant_session['mer_id']))->find();
			if(!empty($mer_percent_rate)){
				$mer_offline = $mer_percent_rate['merchant_offline'];
			}else{
				$mer_offline = true;
			}

			$deposit =D('Deposit')->get_merchant_info($this->merchant_session['mer_id']);
			$this->assign('deposit', $deposit);
			$user = null;
			if ($now_merchant['uid']) {
				$user = M('User')->field(true)->where(array('uid' => $now_merchant['uid']))->find();
			}
			$this->assign('user', $user);
			$this->assign('pay_offline_open', $mer_offline);
			if(isset($config['group_page_row'])){
				$merchant_group_list = D('Group')->get_grouplist_by_MerchantId($now_merchant['mer_id']);
				$this->assign('merchant_group_list',$merchant_group_list);
			}
		}
		$this->display();
    }

	public function sendsms(){
		$type = I('type',2);
		if($type != 4 && empty($this->merchant_session['mer_id'])){
			$this->error('商家不存在');
		}

		if(empty($_POST['phone'])){
			$this->error('请输入手机号');
		}
		$now_merchant = M('Merchant')->where(array('phone'=>$_POST['phone']))->find();
		if(empty($now_merchant)){
			$this->error('该手机号暂未注册商家');
		}
//		$this->merchant = $now_merchant;
//		$this->mer_id = $now_merchant['mer_id'];

		//防止1分钟内多次发送短信
//		$laste_sms = M('Merchant_sms_record')->where(array('mer_id'=>$this->merchant_session['mer_id']))->order('pigcms_id DESC')->find();
//		if(time()-$laste_sms['send_time']<60){
//			$this->error('一分钟内不能多次发送短信');
//		}
		$phone = $now_merchant['phone'];
		$code = mt_rand(1000, 9999);

		if($type == 4){
			$text = '确认码：'.$code.'。您正在进行找回密码操作，请不要把确认码泄露给其他人。';
		}else{
			$text = '您的验证码是：' . $code . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
		}

		if($_POST['newphone']){
			$phone = $_POST['newphone'];
		}


		$columns = array();
		$columns['phone'] = $phone;
		$columns['mer_id'] = $this->mer_id;
		$columns['extra'] = $code;
		$columns['type'] = $type;
		$columns['status'] = 0;
		$columns['send_time'] = time();
		$columns['expire_time'] = $columns['send_time'] + 72000;
		$result = M("Merchant_sms_record")->add($columns);
		if (!$result){
			$this->error('短信数据写入失败');
		}
		$sms_data = array('mer_id' => $this->mer_id, 'store_id' => 0, 'content' => $text, 'mobile' => $phone, 'uid' => 0, 'type' => 'merchant_pwd');
		$_POST['phone_country_type'] && $sms_data['nationCode']  = $_POST['phone_country_type'];
		$return = Sms::sendSms($sms_data);

		if ($result != 0) {
			$this->error(self::ConverSmsCode($return));
		}
		$this->error(0, $return);
	}

	public function sendsms2(){
		$type = I('type',2);
		if($type != 4 && empty($this->merchant_session['mer_id'])){
			$this->error('商家不存在');
		}
		$now_merchant = M('Merchant')->where(array('phone'=>$_POST['newphone']))->find();
		if(!empty($now_merchant)){
			$this->error('该手机号已注册商家');
		}

		if(empty($_POST['newphone'])){
			$this->error('请输入手机号');
		}
		$code = mt_rand(1000, 9999);

		if($type == 4){
			$text = '确认码：'.$code.'。您正在进行找回密码操作，请不要把确认码泄露给其他人。';
		}else{
			$text = '您的验证码是：' . $code . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
		}

		$phone = $_POST['newphone'];
		$columns = array();
		$columns['phone'] = $phone;
		$columns['mer_id'] = $this->mer_id;
		$columns['extra'] = $code;
		$columns['type'] = $type;
		$columns['status'] = 0;
		$columns['send_time'] = time();
		$columns['expire_time'] = $columns['send_time'] + 72000;
		$result = M("Merchant_sms_record")->add($columns);
		if (!$result){
			$this->error('短信数据写入失败');
		}
		$sms_data = array('mer_id' => $this->mer_id, 'store_id' => 0, 'content' => $text, 'mobile' => $phone, 'uid' => 0, 'type' => 'merchant_pwd');
		$_POST['phone_country_type'] && $sms_data['nationCode']  = $_POST['phone_country_type'];
		$return = Sms::sendSms($sms_data);

		if ($result != 0) {
			$this->error(self::ConverSmsCode($return));
		}
		$this->error(0, $return);
	}

    public function merchant_promote()
    {
    	$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = $this->merchant_session['mer_id'];
		$now_merchant = $database_merchant->field(true,'pwd')->where($condition_merchant)->find();

		if(!empty($now_merchant['pic_info'])){
			$merchant_image_class = new merchant_image();
			$tmp_pic_arr = explode(';',$now_merchant['pic_info']);
			foreach($tmp_pic_arr as $key=>$value){
				$now_merchant['pic'][$key]['title'] = $value;
				$now_merchant['pic'][$key]['url'] = $merchant_image_class->get_image_by_path($value);
			}
		}
		$this->assign('now_merchant',$now_merchant);

		$merchant_group_list = D('Group')->get_grouplist_by_MerchantId($now_merchant['mer_id']);

		$this->assign('merchant_group_list',$merchant_group_list);


		$hits = D('Group')->get_hits_log($now_merchant['mer_id']);
		$this->assign('hits', $hits['group_list']);

		$this->assign('pagebar', $hits['pagebar']);

    	$this->display();
    }
	public function merchant_indexsort(){
		if(IS_POST){
			$database_merchant = D('Merchant');
			//转存首页储存值
			$group_indexsort = intval($_POST['group_indexsort']);
			if($group_indexsort){
				$condition_merchant['mer_id'] = $this->merchant_session['mer_id'];
				$now_merchant = $database_merchant->field('`storage_indexsort`')->where($condition_merchant)->find();
				if($now_merchant['storage_indexsort']){
					$condition_group['group_id'] = $group_indexsort;
					if(D('Group')->where($condition_group)->setInc('index_sort',$now_merchant['storage_indexsort'])){
						$database_merchant->where($condition_merchant)->setField('storage_indexsort','0');
					}
				}
			}

			//设置团购自动增长
			$indexsort_groupid = intval($_POST['indexsort_groupid']);
			$condition_merchant['mer_id'] = $this->merchant_session['mer_id'];
			$database_merchant->where($condition_merchant)->setField('auto_indexsort_groupid',$indexsort_groupid);
		}
	}

	public  function ajax_upload_wx_media(){
		if($_FILES['imgFile']['error'] != 4){
			$path = $_GET['path'] ?$_GET['path']:'system';
			$image = D('Image')->handle(rand(1,100), $path, 1);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$url = $_SERVER['DOCUMENT_ROOT'].$image['url']['imgFile'];
				$mode = D('Access_token_expires');
				$res = $mode->get_access_token();
				import('ORG.Net.Http');
				$http = new Http();
				$file  = $url;
				$return = $http->curlUploadFile('https://api.weixin.qq.com/cgi-bin/media/upload?type=image&access_token='.$res['access_token'],$file,1);
				$return = json_decode($return,true);

				exit(json_encode(array('error' => 0, 'url' => $return['media_id'],'title' => $image['url']['imgFile'])));
			}

		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}

	public function ajax_upload_pic(){
		if($_FILES['imgFile']['error'] != 4){
			$path = $_GET['path'] ?$_GET['path']:'merchant';
			$image = D('Image')->handle($this->merchant_session['mer_id'], $path, 1);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$title = $image['title']['imgFile'];
				$merchant_image_class = new merchant_image();
				$url = $merchant_image_class->get_image_by_path($title);
				$url = str_replace('merchant',$path,$url);
				exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
			}
		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}

	public  function ajax_upload_pic_wx(){
		if($_FILES['imgFile']['error'] != 4){
			$path = $_GET['path'] ?$_GET['path']:'merchant';
			$image = D('Image')->handle($this->merchant_session['mer_id'], $path, 1);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$url = $_SERVER['DOCUMENT_ROOT'].$image['url']['imgFile'];
				$mode = D('Access_token_expires');
				$res = $mode->get_access_token();
				import('ORG.Net.Http');
				$http = new Http();
				$file  = $url;
				$return = $http->curlUploadFile('https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$res['access_token'],$file,1);
				$return = json_decode($return,true);
				exit(json_encode(array('error' => 0, 'url' => $return['url'])));
			}

		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
	public function ajax_del_pic(){
		$merchant_image_class = new merchant_image();
		$merchant_image_class->del_image_by_path($_POST['path']);
	}
	/* 店铺管理 */
	public function store()
    {
        $mer_id = $this->merchant_session['mer_id'];
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['mer_id'] = $mer_id;
        if ($this->merchant_session['store_id']) {
            $count_store = 1;
        } else {
            $count_store = $database_merchant_store->where("mer_id='{$mer_id}' AND status<>4")->count();
        }
        
        $db_arr = array(
            C('DB_PREFIX') . 'area' => 'a',
            C('DB_PREFIX') . 'merchant_store' => 's'
        );
        
        import('@.ORG.merchant_page');
        $p = new Page($count_store, 15);
        if ($this->merchant_session['store_id']) {
            $store_list = D()->table($db_arr)->field(true)->where("`s`.`mer_id`='$mer_id' AND `s`.`area_id`=`a`.`area_id` AND s.status!=4 AND s.store_id={$this->merchant_session['store_id']}")->select();
        } else {
            $store_list = D()->table($db_arr)->field(true)->where("`s`.`mer_id`='$mer_id' AND `s`.`area_id`=`a`.`area_id` AND s.status!=4")->order('`sort` DESC,`store_id` ASC')->limit($p->firstRow . ',' . $p->listRows)->select();
        }
        
        $this->assign('store_list', $store_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        
        $this->display();
    }
	public function store_ajax_upload_pic() {
		if ($_FILES['imgFile']['error'] != 4) {
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'store', 1);
			if ($image['error']) {
				exit(json_encode($image));
			} else {
				$title = $image['title']['imgFile'];
				$store_image_class = new store_image();
				$url = $store_image_class->get_image_by_path($title);
				exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
			}
		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
	public function store_ajax_del_pic(){
		$store_image_class = new store_image();
		$store_image_class->del_image_by_path($_POST['path']);
	}
	/* 添加店铺 */
	public function store_add(){
	    if ($this->merchant_session['store_id']) {
	        $this->error('您没有创建店铺的权限！');
	    }
		$database_merchant_store = D('Merchant_store');
		if(IS_POST){
			if(empty($_POST['name'])){
				$this->error('店铺名称必填！');
			}
			if(empty($_POST['phone'])){
				$this->error('联系电话必填！');
			}
			if(empty($_POST['long_lat'])){
				$this->error('店铺经纬度必填！');
			}
			if(empty($_POST['adress'])){
				$this->error('店铺地址必填！');
			}
			if(empty($_POST['permoney'])){
				$this->error('人均消费必填！');
			}
			if(empty($_POST['feature'])){
				$this->error('店铺特色必填！');
			}
// 			if(empty($_POST['trafficroute'])){
// 				$this->error('交通路线必填！');
// 			}
			if(empty($_POST['pic'])){
				$this->error('请至少上传一张图片');
			}
			$_POST['pic_info'] = implode(';',$_POST['pic']);

			if(empty($_POST['txt_info'])){
				$this->error('请输入店铺描述信息');
			}

			//判断关键词
			$keywords = trim($_POST['keywords']);
			if(!empty($keywords)){
				$tmp_key_arr = explode(' ',$keywords);
				$key_arr = array();
				foreach($tmp_key_arr as $value){
					if(!empty($value)){
						array_push($key_arr,$value);
					}
				}
				if(count($key_arr)>5){
					$this->error('关键词最多5个。');
				}
			}
			//营业时间
// 			$office_time = array();
// 			if($_POST['office_start_time'] != '00:00' || $_POST['office_stop_time'] != '00:00'){
// 				array_push($office_time,array('open'=>$_POST['office_start_time'],'close'=>$_POST['office_stop_time']));
// 			}
// 			if($_POST['office_start_time2'] != '00:00' || $_POST['office_stop_time2'] != '00:00'){
// 				array_push($office_time,array('open'=>$_POST['office_start_time2'],'close'=>$_POST['office_stop_time2']));
// 			}
// 			if($_POST['office_start_time3'] != '00:00' || $_POST['office_stop_time3'] != '00:00'){
// 				array_push($office_time,array('open'=>$_POST['office_start_time3'],'close'=>$_POST['office_stop_time3']));
// 			}
			$_POST['office_time'] = '';

			$_POST['sort'] = intval($_POST['sort']);
			$long_lat = explode(',',$_POST['long_lat']);
			$_POST['long'] = $long_lat[0];
			$_POST['lat'] = $long_lat[1];
			$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
			$_POST['add_from'] = '0';
			$_POST['mer_id'] = $this->merchant_session['mer_id'];
			$ismain=intval($_POST['ismain']);
			if($this->config['store_verify']){
				$_POST['status'] = $this->merchant_session['issign'] ? '1' :'2';
			}else{
				$_POST['status'] = '1';
			}

			$_POST['discount_txt'] = '';
			$discount_type = isset($_POST['discount_type']) ? intval($_POST['discount_type']) : 0;
			if ($discount_type == 1) {
				$discount_percent = isset($_POST['discount_percent']) ? (intval($_POST['discount_percent'] * 10) / 10) : 0;
				if ($discount_percent > 0 && $discount_percent < 10) {
					//$_POST['discount_txt'] = serialize(array('discount_type' => $discount_type, 'discount_percent' => $discount_percent));
					if($this->config['open_extra_price']==1){
						$_POST['discount_txt'] = serialize(array('discount_type' => $discount_type, 'discount_percent' => $discount_percent,'discount_limit'=>$_POST['discount_limit'],'discount_limit_percent'=>$_POST['discount_limit_percent']));
					}else{
						$_POST['discount_txt'] = serialize(array('discount_type' => $discount_type, 'discount_percent' => $discount_percent));
					}
				} elseif ($discount_percent < 0 || $discount_percent > 10) {
					$this->error('折扣率必须在0~10之间的数。');
				}
			} elseif ($discount_type == 2) {
				$condition_price = isset($_POST['condition_price']) ? (intval($_POST['condition_price'] * 100) / 100) : 0;
				$minus_price = isset($_POST['minus_price']) ? (intval($_POST['minus_price'] * 100) / 100) : 0;
				if ($condition_price < 0 || $minus_price < 0 || $minus_price > $condition_price) {
					$this->error('满减的填写不正确，必须都是大于0且满足的金额要大于减免金额。');
				}
				if ($condition_price > 0 && $minus_price > 0 && $minus_price < $condition_price) {
					$_POST['discount_txt'] = serialize(array('discount_type' => $discount_type, 'condition_price' => $condition_price, 'minus_price' => $minus_price));
				}
			}

			if($ismain==1){
			   $database_merchant_store->where(array('mer_id'=>$_POST['mer_id']))->save(array('ismain'=>0));
			}
			$_POST['store_type'] = isset($_POST['store_type']) ? intval($_POST['store_type']) : 1;
			$leveloff = isset($_POST['leveloff']) ? $_POST['leveloff'] : false;
			$newleveloff = array();
			if (!empty($leveloff)) {
				foreach ($leveloff as $kk => $vv) {
					$vv['type'] = intval($vv['type']);
					$vv['vv'] = intval($vv['vv']);
					if (($vv['type'] > 0) && ($vv['vv'] > 0)) {
						$vv['level'] = $kk;
						$newleveloff[$kk] = $vv;
					}
				}
			}

			$_POST['leveloff'] = !empty($newleveloff) ? serialize($newleveloff) : '';

			if($merchant_store_id = $database_merchant_store->data($_POST)->add()){
				M('Merchant_score')->add(array('parent_id'=>$insert_id,'type'=>2));
				//判断关键词
				if(!empty($key_arr)){
					$database_keywords = D('Keywords');
					$data_keywords['third_id'] = $merchant_store_id;
					$data_keywords['third_type'] = 'Merchant_store';
					foreach($key_arr as $value){
						$data_keywords['keyword'] = $value;
						$database_keywords->data($data_keywords)->add();
					}
				}

				//外卖
				$have_waimai = I('have_waimai', 0);
				if ($have_waimai) {
					$this->success('添加成功！', U("Merchant/Waimai/store", array('store_id'=>$merchant_store_id)));
				}

				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
		    $merchant_mstore = $database_merchant_store->where(array('mer_id' => $this->merchant_session['mer_id'], 'ismain' => 1))->find();
			$levelDb = M('User_level');
			$tmparr = $levelDb->order('id ASC')->select();
			$levelarr = array();
			if ($tmparr && $this->config['level_onoff']) {
				foreach ($tmparr as $vv) {
					$vv['vv'] = $vv['level']['vv'];
					$vv['type'] = $vv['level']['type'];
					$levelarr[$vv['level']] = $vv;
				}
			}
			unset($tmparr);
			$this->assign('levelarr', $levelarr);

		   $ismainno=true;
		   if(!empty($merchant_mstore)) $ismainno=false;
		   $this->assign('ismainno',$ismainno);
		   $this->display();
		}
	}
	/* 编辑店铺 */
	public function store_edit(){
		$database_merchant_store = D('Merchant_store');

		if(IS_POST){
		    if ($this->merchant_session['store_id'] && $this->merchant_session['store_id'] != $_POST['store_id']) {
		        $this->error('您没有修改的权限');
		    }
			if(empty($_POST['name'])){
				$this->error('店铺名称必填！');
			}
			if(empty($_POST['phone'])){
				$this->error('联系电话必填！');
			}
			if(empty($_POST['long_lat'])){
				$this->error('店铺经纬度必填！');
			}
			if(empty($_POST['adress'])){
				$this->error('店铺地址必填！');
			}
			if(empty($_POST['permoney'])){
				$this->error('人均消费必填！');
			}
			if(empty($_POST['feature'])){
				$this->error('店铺特色必填！');
			}
// 			if(empty($_POST['trafficroute'])){
// 				$this->error('交通路线必填！');
// 			}
			if(empty($_POST['pic'])){
				$this->error('请至少上传一张图片');
			}
			$_POST['pic_info'] = implode(';',$_POST['pic']);

			if(empty($_POST['txt_info'])){
				$this->error('请输入店铺描述信息');
			}
			//判断关键词
			$keywords = trim($_POST['keywords']);
			if(!empty($keywords)){
				$tmp_key_arr = explode(' ',$keywords);
				$key_arr = array();
				foreach($tmp_key_arr as $value){
					if(!empty($value)){
						array_push($key_arr,$value);
					}
				}
				if(count($key_arr)>5){
					$this->error('关键词最多5个。');
				}
			}

			//营业时间
// 			$office_time = array();
// 			if($_POST['office_start_time'] != '00:00' || $_POST['office_stop_time'] != '00:00'){
// 				array_push($office_time,array('open'=>$_POST['office_start_time'],'close'=>$_POST['office_stop_time']));
// 			}
// 			if($_POST['office_start_time2'] != '00:00' || $_POST['office_stop_time2'] != '00:00'){
// 				array_push($office_time,array('open'=>$_POST['office_start_time2'],'close'=>$_POST['office_stop_time2']));
// 			}
// 			if($_POST['office_start_time3'] != '00:00' || $_POST['office_stop_time3'] != '00:00'){
// 				array_push($office_time,array('open'=>$_POST['office_start_time3'],'close'=>$_POST['office_stop_time3']));
// 			}
			$_POST['office_time'] = '';

			$_POST['sort'] = intval($_POST['sort']);
			$long_lat = explode(',',$_POST['long_lat']);
			$_POST['long'] = $long_lat[0];
			$_POST['lat'] = $long_lat[1];
			$_POST['last_time'] = $_SERVER['REQUEST_TIME'];

			$_POST['discount_txt'] = '';
			$discount_type = isset($_POST['discount_type']) ? intval($_POST['discount_type']) : 0;
			if ($discount_type == 1) {
				$discount_percent = isset($_POST['discount_percent']) ? (intval($_POST['discount_percent'] * 10) / 10) : 0;
				if ($discount_percent > 0 && $discount_percent < 10) {
					if($this->config['open_extra_price']==1){
						$_POST['discount_txt'] = serialize(array('discount_type' => $discount_type, 'discount_percent' => $discount_percent,'discount_limit'=>$_POST['discount_limit'],'discount_limit_percent'=>$_POST['discount_limit_percent']));
					}else{
						$_POST['discount_txt'] = serialize(array('discount_type' => $discount_type, 'discount_percent' => $discount_percent));
					}
				} elseif ($discount_percent < 0 || $discount_percent > 10) {
					$this->error('折扣率必须在0~10之间的数。');
				}
			} elseif ($discount_type == 2) {
				$condition_price = isset($_POST['condition_price']) ? (intval($_POST['condition_price'] * 100) / 100) : 0;
				$minus_price = isset($_POST['minus_price']) ? (intval($_POST['minus_price'] * 100) / 100) : 0;
				if ($condition_price < 0 || $minus_price < 0 || $minus_price > $condition_price) {
					$this->error('满减的填写不正确，必须都是大于0且满足的金额要大于减免金额。');
				}
				if ($condition_price > 0 && $minus_price > 0 && $minus_price < $condition_price) {
					$_POST['discount_txt'] = serialize(array('discount_type' => $discount_type, 'condition_price' => $condition_price, 'minus_price' => $minus_price));
				}
			}

			$condition_merchant_store['store_id'] = $_POST['store_id'];
			$condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
			$_POST['store_type'] = isset($_POST['store_type']) ? intval($_POST['store_type']) : 1;
			unset($_POST['store_id']);
			$ismain = intval($_POST['ismain']);
			if($ismain==1){
			   $database_merchant_store->where(array('mer_id'=>$this->merchant_session['mer_id']))->save(array('ismain'=>0));
			}
			$leveloff = isset($_POST['leveloff']) ? $_POST['leveloff'] : false;
			$newleveloff = array();
			if (!empty($leveloff)) {
				foreach ($leveloff as $kk => $vv) {
					$vv['type'] = intval($vv['type']);
					$vv['vv'] = intval($vv['vv']);
					if (($vv['type'] > 0) && ($vv['vv'] > 0)) {
						$vv['level'] = $kk;
						$newleveloff[$kk] = $vv;
					}
				}
			}

			$_POST['leveloff'] = !empty($newleveloff) ? serialize($newleveloff) : '';

			if($database_merchant_store->where($condition_merchant_store)->data($_POST)->save()){

				$data_keywords['third_id'] = $condition_merchant_store['store_id'];
				$data_keywords['third_type'] = 'Merchant_store';
				$database_keywords = D('Keywords');
				$database_keywords->where($data_keywords)->delete();
				//判断关键词
				if(!empty($key_arr)){
					foreach($key_arr as $value){
						$data_keywords['keyword'] = $value;
						$database_keywords->data($data_keywords)->add();
					}
				}
				
				//判断票务插件
				if($this->config['store_ticket_have']){
					$database_store_trade_ticket = D('Merchant_store_trade_ticket');
					$condition_store_trade_ticket = array('store_id'=>$condition_merchant_store['store_id']);
					$data_store_trade_ticket = $_POST['store_trade_ticket'];
					if($database_store_trade_ticket->where($condition_store_trade_ticket)->find()){
						// var_dump($data_store_trade_ticket);
						$database_store_trade_ticket->where($condition_store_trade_ticket)->data($data_store_trade_ticket)->save();
						// var_dump($database_store_trade_ticket);
					}else{
						$data_store_trade_ticket = array_merge($condition_store_trade_ticket,$data_store_trade_ticket);
						$database_store_trade_ticket->data($data_store_trade_ticket)->add();
					}
				}
				
				$this->success('保存成功！');
			}else{
				$this->error('保存失败！！您是不是没做过修改？请重试~');
			}
		}else{
		    if ($this->merchant_session['store_id'] && $this->merchant_session['store_id'] != $_GET['id']) {
		        $this->error('您没有修改的权限');
		    }
			$condition_merchant_store['store_id'] = $_GET['id'];
			$condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
			$now_store = $database_merchant_store->where($condition_merchant_store)->find();

			if(empty($now_store)){
				$this->error('店铺不存在！');
			}

			if(!empty($now_store['pic_info'])){
				$store_image_class = new store_image();
				$tmp_pic_arr = explode(';',$now_store['pic_info']);
				foreach($tmp_pic_arr as $key=>$value){
					$now_store['pic'][$key]['title'] = $value;
					$now_store['pic'][$key]['url'] = $store_image_class->get_image_by_path($value);
				}
			}
			$keywords = D('Keywords')->where(array('third_type' => 'Merchant_store', 'third_id' => $condition_merchant_store['store_id']))->select();
			$str = "";
			foreach ($keywords as $key) {
				$str .= $key['keyword'] . " ";
			}
			$now_store['keywords'] = $str;

			$now_store['discount_txt'] = unserialize($now_store['discount_txt']);
			
			
			//判断票务插件
			// if($this->config['store_ticket_have']){
				$database_store_trade_ticket = D('Merchant_store_trade_ticket');
				$condition_store_trade_ticket = array('store_id'=>$condition_merchant_store['store_id']);
				$now_store['store_trade_ticket'] = $database_store_trade_ticket->where($condition_store_trade_ticket)->find();
			// }
			
			$this->assign('now_store',$now_store);
			$leveloff = !empty($now_store['leveloff']) ? unserialize($now_store['leveloff']) : false;
			$levelDb = M('User_level');
			$tmparr = $levelDb->order('id ASC')->select();

			$levelarr = array();
			if ($tmparr && $this->config['level_onoff']) {
				foreach ($tmparr as $vv) {
					if (!empty($leveloff) && isset($leveloff[$vv['level']])) {
						$vv['vv'] = $leveloff[$vv['level']]['vv'];
						$vv['type'] = $leveloff[$vv['level']]['type'];
					} else {
						$vv['vv'] = '';
						$vv['type'] = '';
					}
					$levelarr[$vv['level']] = $vv;
				}
			}
			unset($tmparr);
			$this->assign('levelarr', $levelarr);

			$this->display();
		}
	}
	/* 店铺状态 */
	public function store_status(){
		$database_merchant_store = D('Merchant_store');
		$data_merchant_store['status'] = $_POST['type'] == 'open' ? '1' : '0';
		$condition_merchant_store['store_id'] = $_POST['id'];
		$condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
		if($database_merchant_store->where($condition_merchant_store)->data($data_merchant_store)->save()){
			exit('1');
		}else{
			exit;
		}
	}
	/* 删除店铺 */
	public function store_del()
    {
        if ($this->merchant_session['store_id']) {
            $this->error('您没有权限删除');
        }
        $condition_merchant_store['store_id'] = intval(trim($_GET['id']));
        $group_storeDb = D('Group_store');
        if ($group_storeDb->where($condition_merchant_store)->order('group_id desc')->find()) {
            $this->error('该店铺下有' . $this->config['group_alias_name'] . '，请先解除店铺与对应' . $this->config['group_alias_name'] . '的关系才能删除！');
            exit();
        }
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
        /**
         * *$database_merchant_store->where($condition_merchant_store)->delete()**改软删除*4禁用**
         */
        if ($database_merchant_store->where($condition_merchant_store)->save(array('status' => 4))) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

	public function staff()
    {
        if ($this->merchant_session['store_id'] && $_GET['store_id'] != $this->merchant_session['store_id']) {
            $this->error('您没有权限');
        }
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['store_id'] = $_GET['store_id'];
        $condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
        $now_store = $database_merchant_store->where($condition_merchant_store)->find();
        if (empty($now_store)) {
            $this->error('店铺不存在！');
        }
        $this->assign('now_store', $now_store);
        
        $condition_store_staff['token'] = $this->token;
        $condition_store_staff['store_id'] = $_GET['store_id'];
        $staff_list = D('Merchant_store_staff')->where($condition_store_staff)->order('`id` desc')->select();
        $this->assign('staff_list', $staff_list);
        $this->assign('staff_type', $this->staff_type);
        $this->display();
    }
	public function staffSet(){
		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['store_id'] = $_GET['store_id'];
		$condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
		$now_store = $database_merchant_store->where($condition_merchant_store)->find();
		if(empty($now_store)){
			$this->error('店铺不存在！');
		}
		$this->assign('staff_type', $this->staff_type);
		$this->assign('now_store',$now_store);

		$_POST['store_id'] = $now_store['store_id'];
		$company_staff_db = M('Merchant_store_staff');

		if(IS_POST){
			if (!trim($_POST['name']) || !trim($_POST['username'])){
				$this->error('姓名、帐号都不能为空');
			}
			$_POST['token'] = $this->token;
			$_POST['time'] = time();

			if (!isset($_GET['itemid'])){
				$condition_store_staff_username['username'] = $_POST['username'];
				if($company_staff_db->field('`id`')->where($condition_store_staff_username)->find()){
					$this->error('帐号已经存在！请换一个。');
				}
				if(!trim($_POST['password'])){
					$this->error('密码不能为空');
				}
				$_POST['password'] = md5($_POST['password']);

				if(!$company_staff_db->add($_POST)){
					$this->error('添加失败，请重试。');
				}
			}else{
				/* 检测帐号 */
				$condition_store_staff_username['username'] = $_POST['username'];
				$username_staff = $company_staff_db->field('`id`')->where($condition_store_staff_username)->find();
				if($username_staff['id'] != $_GET['itemid']){
					$this->error('帐号已经存在！请换一个。');
				}

				if(!trim($_POST['password'])){
					unset($_POST['password']);
				}else{
					$_POST['password'] = md5($_POST['password']);
				}
				if(!$company_staff_db->where(array('id'=>intval($_GET['itemid'])))->save($_POST)){
					$this->error('修改失败，请重试。');
				}

			}
			$this->success('操作成功',U('Config/staff',array('store_id'=>$now_store['store_id'])));
		}else{
			if (isset($_GET['itemid'])) {
				$thisItem = $company_staff_db->where(array('id'=>intval($_GET['itemid'])))->find();
			} else {
				$thisItem['companyid'] = 0;
			}
			$this->assign('item', $thisItem);
			$this->display('staffSet');
		}
	}
	public function staffDelete(){
		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['store_id'] = $_GET['store_id'];
		$condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
		$now_store = $database_merchant_store->where($condition_merchant_store)->find();
		if(empty($now_store)){
			$this->error('店铺不存在！');
		}
		$this->assign('now_store',$now_store);

		$company_staff_db = M('Merchant_store_staff');

		$condition_store_staff['token'] = $this->token;
		$condition_store_staff['id'] = $_GET['itemid'];
		if($company_staff_db->where($condition_store_staff)->delete()){
			$this->success('操作成功',U('Config/staff',array('store_id'=>$now_store['store_id'])));
		}else{
			$this->error('操作失败，请重试。');
		}

	}
	
    public function pick()
    {
        $pick_addr = D('Pick_address')->get_pick_addr_by_merid($this->merchant_session['mer_id'],true);
        $this->assign('pick_addr', $pick_addr);
        $this->display();
    }

    public function pick_address_add()
    {
        if (IS_POST) {
			if (empty($_POST['long_lat'])) {
				$this->error('店铺经纬度必填！');
			}
            if (empty($_POST['pick_addr'])||empty($_POST['phone'])) {
                $this->error("信息不全，请检查！");
            }
			$long_lat = explode(',',$_POST['long_lat']);
			$_POST['long'] = $long_lat[0];
			$_POST['lat'] = $long_lat[1];
			
            $_POST['mer_id'] = $this->merchant_session['mer_id'];
            if(M('Pick_address')->add($_POST)){
                $this->success("保存成功！");
            }else{
                $this->error("保存失败！");
            }

        }else{
            $this->display();
        }
    }

    public function pick_address_edit(){
        if(IS_POST){
			if(empty($_POST['long_lat'])){
				$this->error('店铺经纬度必填！');
			}
            if(empty($_POST['pick_addr'])||empty($_POST['phone'])){
                $this->error("信息不全，请检查！");
            }
        	$long_lat = explode(',',$_POST['long_lat']);
        	$_POST['long'] = $long_lat[0];
        	$_POST['lat'] = $long_lat[1];
            if(M('Pick_address')->where(array('id'=>$_POST['id']))->save($_POST)){
                $this->success("保存成功！");
            }else{
                $this->error("保存失败！");
            }

        }else{
            if(!empty($_GET['id'])){
                $n = preg_match('/\d+/',$_GET['id'],$id);
                $pick_addr = M('Pick_address')->where(array('id'=>$id[0]))->find();

                $this->assign('pick_addr',$pick_addr);
                $this->display();
            }else{
                $this->error("访问失败！");
            }
        }
    }

    public function pick_address_del()
    {
        if(IS_GET){
            $n = preg_match('/\d+/',$_GET['id'],$id);
            if(M('Pick_address')->where(array('id'=>$id[0]))->delete()){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        }
    }
    
    public function see_pick_pwd()
    {
    	$pick_id = isset($_GET['pick_id']) ? htmlspecialchars($_GET['pick_id']) : '';
    	$pick_id = substr($pick_id, 1);
    	$pick_address = M('Pick_address')->where(array('id' => $pick_id))->find();
    	if ($pick_address) {
    		if (IS_POST) {
    			$now_time = time();
    			$pick_address['pwd'] = $pwd = md5($pick_id . $now_time . rand(10000, 99999));
    			D('Pick_address')->where(array('id' => $pick_id))->save(array('last_time' => $now_time, 'pwd' => $pwd));
    		}
    		if (empty($pick_address['pwd'])) {
    			$now_time = time();
    			$pick_address['pwd'] = $pwd = md5($pick_id . $now_time . rand(10000, 99999));
    			D('Pick_address')->where(array('id' => $pick_id))->save(array('last_time' => $now_time, 'pwd' => $pwd));
    		}
    	} else {
    		$this->error('配送点不存在！');
    		exit;
    	}
    	$pick_address['pick_addr_id'] = 'p' . $pick_address['id'];
    	$this->assign('pick_address', $pick_address);
    	$this->display();
    }
	
	/**
	 * 自定义支付列表
	 */
	public function pay()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$now_store = $this->check_store($store_id);
		$pay_list = M('Store_pay')->field(true)->where(array('store_id' => $store_id))->select();
		$this->assign(array('now_store' => $now_store, 'pay_list' => $pay_list));
		$this->display();
	}
	
	public function pay_add()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$now_store = $this->check_store($store_id);
		$this->assign('now_store',$now_store);
		
		if (IS_POST) {
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			if (empty($name)) {
				$this->error('支付名称不能为空');
				exit;
			}
			$database_pay = D('Store_pay');
			$data['store_id'] = $now_store['store_id'];
			$data['name'] = $name;
			if ($database_pay->add($data)) {
				$this->success('添加成功！！', U('Config/pay',array('store_id' => $now_store['store_id'])));
				exit;
			} else {
				$this->error('添加失败！！请重试。', U('Config/pay',array('store_id' => $now_store['store_id'])));
				exit;
			}
		}
		$this->display();
	}
	
	public function pay_edit()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$now_store = $this->check_store($store_id);
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if (!($pay = D('Store_pay')->field(true)->where(array('id' => $id, 'store_id' => $now_store['store_id']))->find())) {
			$this->error('不存在的支付方式。', U('Config/pay', array('store_id' => $now_store['store_id'])));
			exit;
		}
		$this->assign('now_store',$now_store);

		if (IS_POST) {
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			if (empty($name)) {
				$this->error('支付名称不能为空');
				exit;
			}
			$database_pay = D('Store_pay');
			$data['id'] = $pay['id'];
			$data['store_id'] = $now_store['store_id'];
			$data['name'] = $name;
			if ($database_pay->data($data)->save()) {
				$this->success('修改成功！！', U('Config/pay', array('store_id' => $now_store['store_id'])));
				exit;
			} else {
				$this->error('修改失败！！请重试。', U('Config/pay', array('store_id' => $now_store['store_id'])));
				exit;
			}
		} else {
			$this->assign('now_pay', $pay);
		}
		$this->display();
		
	}
	
	public function pay_del()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$now_store = $this->check_store($store_id);
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$where = array('store_id' => $store_id, 'id' => $id);
		if ($pay = D('Store_pay')->field(true)->where($where)->find()) {
			if (D('Store_pay')->where($where)->delete()) {
				$this->success('删除成功！');
			} else {
				$this->error('删除失败！');
			}
		} else {
			$this->error('不存在的支付方式。', U('Config/pay', array('store_id' => $now_store['store_id'])));
		}
	}
	
	/* 检测店铺存在，并检测是不是归属于商家 */
	protected function check_store($store_id)
	{
		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['store_id'] = $store_id;
		$condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
		$now_store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
		if (empty($now_store)) {
			$this->error('店铺不存在！');
		} else {
			return $now_store;
		}
	}
	
	/**
	 * 编辑店铺资质审核资料
	 */
	public function auth_edit()
	{
		$database_merchant_store = D('Merchant_store');
		$database_store_authfile = D('Merchant_store_authfile');
		$store_id = intval($_GET['id']);

		if ($this->merchant_session['store_id'] && $this->merchant_session['store_id'] != $store_id) {
		    $this->error('您没有修改的权限');
		}
		$now_store = $this->check_store($store_id);
		if (IS_POST) {
			if(empty($_POST['pic'])){
				$this->error('请至少上传一张图片');
			}
			$where = array('store_id' => $store_id);
			$data['auth_files'] = implode(';', $_POST['pic']);
			
			if ($now_store['auth'] < 3) {
				if ($authfile_row = $database_store_authfile->field(true)->where($where)->find()) {
					$data['dateline'] = time();
					$result = $database_store_authfile->where($where)->save($data);
				} else {
					$data['dateline'] = time();
					$data['store_id'] = $store_id;
					$result = $database_store_authfile->add($data);
				}
				if ($result) {
					$store_data = array('auth_files' => $data['auth_files'], 'auth' => 1, 'auth_time' => time());
					$database_merchant_store->where($where)->save($store_data);
					$this->success('保存成功！');
				} else {
					$this->error('保存失败！！您是不是没做过修改？请重试~');
				}
			} else {
				$data['dateline'] = time();
				if ($database_store_authfile->where($where)->save($data)) {
					$store_data = array('auth' => 4, 'auth_time' => time());
					$database_merchant_store->where($where)->save($store_data);
					$this->success('保存成功！');
				} else {
					$this->error('保存失败！！您是不是没做过修改？请重试~');
				}
			}
		} else {
			if (empty($now_store)) {
				$this->error('店铺不存在！');
			}
			$now_store['reason'] = '';
			$auth_files = array();
			if ($store_authfile = $database_store_authfile->field(true)->where(array('store_id' => $store_id))->find()) {
				if (!empty($store_authfile['auth_files'])) {
					$auth_file_class = new auth_file();
					$tmp_pic_arr = explode(';', $store_authfile['auth_files']);
					foreach($tmp_pic_arr as $key => $value){
						$auth_files[] = array('title' => $value, 'url' => $auth_file_class->get_image_by_path($value, 's'));
					}
				}
				$now_store['reason'] = $store_authfile['reason'];
			}
			$now_store['auth_files'] = $auth_files;
			$this->assign('now_store', $now_store);
			$this->display();
		}
	}
	
	public function ajax_upload_authfile()
	{
		if ($_FILES['file']['error'] != 4) {
			$param = array('size' => 5);
			$param['thumb'] = true;
			$param['imageClassPath'] = 'ORG.Util.Image';
			$param['thumbPrefix'] = 'm_,s_';
			$param['thumbMaxWidth'] = '1000,160';
			$param['thumbMaxHeight'] = '1000,160';
			$param['thumbRemoveOrigin'] = false;
			
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'authfile', 1, $param);
			if ($image['error']) {
				exit(json_encode(array('error' => 1,'message' =>$image['msg'])));
			} else {
				$title = $image['title']['file'];
				$auth_file_class = new auth_file();
				$url = $auth_file_class->get_image_by_path($title, 's');
				exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
			}
		} else {
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
	public function ajax_del_authfile() 
	{
		$auth_file_class = new auth_file();
		$auth_file_class->del_image_by_path($_POST['path']);
	}
	
	public function ajax_del_binduser() 
	{
		if (D('Merchant')->where(array('mer_id' => $this->merchant_session['mer_id']))->save(array('uid' => 0))) {
			exit(json_encode(array('error_code' => false, 'msg' => '成功解除绑定')));
		} else {
			exit(json_encode(array('error_code' => true, 'msg' => '解绑失败,稍后重试！')));
		}
	}

	public function see_tmp_qrcode(){
		$qrcode_id = $this->merchant_session['mer_id'] + 2550000000;
		$qrcode_return = D('Recognition')->get_tmp_qrcode($qrcode_id);
		if($qrcode_return['error_code']){
			echo '<html><head></head><body>'.$qrcode_return['msg'].'<br/><br/><font color="red">请关闭此窗口再打开重试。</font></body></html>';
		}else{
			$this->assign($qrcode_return);
			$this->display();
		}
	}
	
	
	public function has_bind()
	{
		$condition_merchant['mer_id'] = $this->merchant_session['mer_id'];
		$now_merchant = D('Merchant')->field('uid')->where($condition_merchant)->find();
		if ($now_merchant['uid']) {
			$user = M('User')->field(true)->where(array('uid' => $now_merchant['uid']))->find();
			exit(json_encode(array('error_code' => false, 'nickname' => $user['nickname'])));
		} else {
			exit(json_encode(array('error_code' => true)));
		}
	}

	static private function ConverSmsCode($smscode) {
		$errCode = array(
				'2'    => '20060001',
				'400'  => '20060002',
				'401'  => '20060003',
				'402'  => '20060004',
				'403'  => '20060005',
				'4030' => '20060006',
				'404'  => '20060007',
				'405'  => '20060008',
				'4050' => '20060009',
				'4051' => '20060010',
				'4052' => '20060011',
				'406'  => '20060012',
				'407'  => '20060013',
				'4070' => '20060014',
				'4071' => '20060015',
				'4072' => '20060016',
				'4073' => '20060017',
				'408'  => '20060018',
				'4085' => '20060019',
				'4084' => '20060020',
		);
		return $errCode[$smscode];
	}
}