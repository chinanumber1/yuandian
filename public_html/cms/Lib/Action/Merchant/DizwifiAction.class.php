<?php 
class DizwifiAction extends BaseAction
{
	public $expiration_time;
	public function _initialize()
	{
		parent::_initialize();
		$this->dizwifi = new Dizwifi();
		$this->expiration_time = 12*3600;
	}
	
	
	//主页
	public function index()
	{  
// 		$total = M('Dizwifi_device')->where(array('mer_id' => $this->merchant_session['mer_id']))->count();
// 		$Page = new Page($total,10);
// 		$list = M('Dizwifi_device')->where(array('mer_id' => $this->merchant_session['mer_id']))->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
		$list = M('Dizwifi_device')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->order('id desc')->select();
		$this->assign('list', $list);
// 		$this->assign('page',$Page->show());
// 		$this->assign('token',$this->token);
		$this->display();
	}
	
	
	//给门店添加无线设备
	public function device()
	{
		if (IS_POST) {
			if ($_POST['shop_id'] == "" || $_POST['shop_name'] == "") {
				$this->error('必须选择一个门店');
			}
			if ($_POST['ssid'] == "") {
				$this->error('无线网名称不能为空');
			}
			if ($_POST['password'] == "") {
				$this->error('无线网密码不能为空');
			}
// 			if ($_POST['bssid'] == "") {
// 				$this->error('无线网mac地址不能为空');
// 			} elseif(!preg_match("/^[A-Fa-f0-9]{2}(:[A-Fa-f0-9]{2}){5}$/", $_POST['bssid'])) {
// 				$this->error('无线网mac地址格式错误');
// 			}
			/*
			$device = M('dizwifi_device')->where(array('shop_id'=>$_POST['shop_id'],'token'=>$this->token))->order('add_time asc')->find();
			if(!empty($device)){
				if('WX'.$_POST['ssid'] != $device['ssid']){
					$this->error('同一个门店下的设备，无线网名称必须相同');
				}
				if($_POST['password'] != $device['password']){
					$this->error('同一个门店下的设备，无线网密码必须相同');
				}
			}*/
			$device_count = M('Dizwifi_device')->where(array('ssid' => 'WX'.trim($_POST['ssid']), 'mer_id' => $this->merchant_session['mer_id']))->count();
			if($device_count > 0){
				$this->error('该设备已经被添加');
			}
			$data = array();
			$data['shop_id'] = $_POST['shop_id'];
			$data['shop_name'] = $_POST['shop_name'];
			$data['ssid'] = 'WX'.trim($_POST['ssid']);
			$data['password'] = trim($_POST['password']);
// 			$data['bssid'] = strtolower(trim($_POST['bssid']));
			$adddevice = $this->dizwifi->AddDevice($data['shop_id'], $data['ssid'], $data['password']);

			if ($adddevice['errcode'] == 0) {
				$data['mer_id'] = $this->merchant_session['mer_id'];
				$data['add_time'] = time();
				$add = M('dizwifi_device')->add($data);
				if ($add) {
					$this->success('添加成功',U('Dizwifi/index',array('mer_id' => $this->merchant_session['mer_id'])));
					exit;
				} else {
					$this->error($adddevice['errmsg']);
				}
			} else {
				$this->error('添加失败');
			}
		} else {
			//门店列表
			$cache_data = S('wx_shoplist');
			if ($cache_data['status'] == 1) {
				$result = $cache_data['data'];
			} else {
				$records = array();
				$list = array();
				$shoplist = $this->dizwifi->ShopList(1,20);
				if ($shoplist['errcode'] == 0) {
					$records  = $shoplist['successmsg']['records'];
					$totalcount = $shoplist['successmsg']['totalcount'];
					if($totalcount > 20){
						for($i=2;$i<=ceil($totalcount/20);$i++){
							$list  = $this->dizwifi->ShopList($i,20);
							if($list['errcode'] == 0){
								$records = array_merge($records,$list['successmsg']['records']);
							}
						}
					}
					$result = $records;
					$cache_data = array('status'=>1,'data'=>$result);
					S('wx_shoplist',$cache_data,$this->expiration_time);
				}else{
					$this->error($shoplist['errmsg']);
				}
			}
			$list = array();
			foreach ($result as $row) {
				$list[$row['poi_id']] = $row;
			}
			if ($this->merchant_session['store_id']) {
			    $stores = D('Merchant_store')->field(true)->where(array('store_id' => $this->merchant_session['store_id'], 'status' => 1))->select();
			} else {
			    $stores = D('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'status' => 1))->select();
			}
			$shop_list = array();
			foreach ($stores as $str) {
			    if ($str['poi_id'] && isset($list[$str['poi_id']])) {
					$shop_list[] = $list[$str['poi_id']];
				}
			}
			if (empty($shop_list)) {
			    $this->error('暂无可选店铺');
			}
			$this->assign('shop_list', $shop_list);
			$this->display();
		}
	}
	
	//获取二维码
	public function getcode()
	{
		$id = (int)$_GET['id'];
		$device = M('Dizwifi_device')->where(array('id' => $id, 'mer_id' => $this->merchant_session['mer_id']))->find();
		if(S('shopcode_' . $device['ssid'] . '_' . $device['shop_id'] . '_' . $device['password']) != ""){

			echo '<html><head><style>*{margin:0;padding:0;}</style></head><body><img src="' . S('shopcode_' . $device['ssid'] . '_' . $device['shop_id'] . '_' . $device['password']) . '"/></body></html>';
			//$this->success(S('shopcode_' . $device['ssid'] . '_' . $device['shop_id'] . '_' . $device['password']));
			exit;
		}

		if (!empty($device)) {
			$code = $this->dizwifi->GetQrcode($device['shop_id'],$device['ssid'],1);
			//echo '<script>window.open("' .$code['successmsg']. '")</script>';
			//header('Location:' . $code['successmsg']);
			//exit;

			if ($code['errcode'] == 0) {
				$image_url = $this->config['site_url'].'/index.php?c=Image&a=wx_image_download&url='.urlencode($code['successmsg']).'&path='.$this->merchant_session['mer_id'];

				//$this->success($image_url);

				S('shopcode_' . $device['ssid'] . '_' . $device['shop_id'] . '_' . $device['password'], $image_url , $this->expiration_time);
				echo '<html><head><style>*{margin:0;padding:0;}</style></head><body><img src="' .  $image_url  . '"/></body></html>';
				exit;
			} else {
				$this->error($code['errmsg']);
			}
		} else {
			$this->error('操作失败');
		}
	}
	
	//删除设备
	public function DelDevice()
	{
		$id = (int)$_GET['id'];
		$device = M('Dizwifi_device')->where(array('id' => $id, 'mer_id' => $this->merchant_session['mer_id']))->find();
		if (!empty($device)) {
			$DeleteDevice = $this->dizwifi->DeleteDevice($device['shop_id'], $device['ssid']);
			if ($DeleteDevice['errcode'] == 0) {
				$del = M('dizwifi_device')->where(array('id' => $id, 'mer_id' => $this->merchant_session['mer_id']))->delete();
				if ($del) {
					$this->success('删除成功', U('Dizwifi/index', array('mer_id' => $this->merchant_session['mer_id'])));
					exit;
				} else {
					$this->error('删除失败，请稍后再试');
				}
			} else {
				$this->error($DeleteDevice['errmsg']);
			}
		} else {
			$this->error('未找到删除项');
		}
	}
	
	
	//设置商家主页
	public function sethomepage()
	{
		if (IS_POST) {
			$data = array();
			if ($_POST['template_id'] == 1 && $_POST['url'] == "") {
				$this->error('自定义链接不能为空');
			} elseif($_POST['template_id'] == 1 && $_POST['url'] != "") {
				$url = htmlspecialchars_decode(trim($_POST['url']));
			}
			$data['url'] = $url;
			$data['template_id'] = $_POST['template_id'];
			$data['bar_type'] = $_POST['bar_type'];
			$data['shop_id'] = $_POST['shop_id'];
			$data['dateline'] = time();
			$setpage = $this->dizwifi->SetHomgpage($_POST['shop_id'],$_POST['template_id'],$url);
			if (!empty($url)) {
				$setfinishpage = $this->dizwifi->SetFinishpage($_POST['shop_id'], $url);
			}
			$setbar = $this->dizwifi->SetBar($_POST['shop_id'], $_POST['bar_type']);
			if ($setpage['errcode'] == 0 && $setbar['errcode'] == 0) {
				if ($homepage = M('Dizwifi_homepage')->field(true)->where(array('shop_id' => $_POST['shop_id'], 'mer_id' => $this->merchant_session['mer_id']))->find()) {
					$set = M('Dizwifi_homepage')->where(array('id' => $homepage['id']))->save($data);
				} else {
					$data['mer_id'] = $this->merchant_session['mer_id'];
					$set = M('Dizwifi_homepage')->add($data);
				}
				if ($set) {
					$this->success('设置成功', U('Dizwifi/index',array('mer_id' => $this->merchant_session['mer_id'])));
					exit;
				} else {
					$this->error('设置失败');
				}
			} else {
				$msg = '';
				$msg .= ($setpage['errmsg'] != "") ? $setpage['errmsg'] : "";
				$msg .= ($setbar['errmsg'] != "") ? $setbar['errmsg'] : "";
				$this->error($msg);
			}
		}
		$id = (int)$_GET['id'];
		$device = M('Dizwifi_device')->where(array('id' => $id, 'mer_id' => $this->merchant_session['mer_id']))->find();
		if(!empty($device)){
			$set = M('Dizwifi_homepage')->where(array('shop_id'=>$device['shop_id']))->find();
			$this->assign('set',$set);
			$this->assign('shop_id',$device['shop_id']);
			$this->assign('shop_name',$device['shop_name']);
		}else{
			//$this->error('操作失败');
		}
		$this->display();
	}
	
	//统计信息总页面
	public function statistics()
	{
		$devices = M('Dizwifi_device')->where(array('mer_id' => $this->merchant_session['mer_id']))->order('id desc')->select();
		$this->assign('devices', $devices);
		$this->assign('current_month', date('n'));
		$this->display();
	}
	
	
	public function statistics_success()
	{
		$shop_id = $this->_get('shop_id','intval');
		$month = $this->_get('month','intval');
		$charts = array();
		$cache_name = 'dizwifi' . substr(md5($this->merchant_session['mer_id'] . '_' . $shop_id . '_' . $month), 0, 5);
		$map = array();
		$map['shop_id'] = $shop_id;
		$map['mer_id'] = $this->merchant_session['mer_id'];
		$device_info = M('Dizwifi_device')->where($map)->find();
		//如果查询开始时间需大于设备添加时间
		$begin_date = mktime(0,0,0,$month,1,date('Y'));//(mktime(0,0,0,$month,1,date('Y')) >  $device_info['add_time']) ? mktime(0,0,0,$month,1,date('Y')) : $device_info['add_time'];
		//如果查询的月份为当月月份
		if ($month == date('n')) {
			$end_date = time() - 24*3600;
		} else {
			$end_date = mktime(0, 0, 0, $month, date('t', $begin_date), date('Y'));//date('t')月份的天数
		}
		//如果请求的月份在添加设备之前,默认统计数据为0---减小请求接口的次数
		if ($month < date('n',$device_info['add_time'])) {
			$this->default_charts('charts', date('t', $begin_date));
			exit;
		}
		//如果请求的月份在今日月份之后默认统计数据为0
		if($month > date('n', time())){
			$this->default_charts('charts', date('t', $begin_date));
			exit;
		}
		if ($device_info) {
			$statistics_info = S($cache_name);
			if($statistics_info['status'] == 1){
				//echo 66;exit;
				foreach((array)$statistics_info['data'] as $key=>$val){
					$charts['xAxis']  .= '"'.date('d',intval($val['statis_time']/1000)).'日",';
					$charts['total_user'] .= '"'.$val['total_user'].'",';
					$charts['homepage_uv']   .= '"'.$val['homepage_uv'].'",';
					$charts['new_fans'] .= '"'.$val['new_fans'].'",';
					$charts['total_fans']   .= '"'.$val['total_fans'].'",';
				}
			} else {
				//echo 555;exit;
				$statistics_result = $this->dizwifi->StatisticsList(date('Y-m-d',$begin_date),date('Y-m-d',$end_date),$device_info['shop_id']);
				if($statistics_result['errcode'] == 0){
					foreach($statistics_result['successmsg'] as $key=>$val){
						$charts['xAxis']  .= '"'.date('d',intval($val['statis_time']/1000)).'日",';
						$charts['total_user'] .= '"'.$val['total_user'].'",';
						$charts['homepage_uv']   .= '"'.$val['homepage_uv'].'",';
						$charts['new_fans'] .= '"'.$val['new_fans'].'",';
						$charts['total_fans']   .= '"'.$val['total_fans'].'",';
					}
					$cache_data = array('status'=>1,'data'=>$statistics_result['successmsg']);
					S($cache_name,$cache_data,$this->expiration_time);// 缓存数据
					//S($cache_name,$statistics_result,$this->expiration_time);
				}else{
					$this->default_charts('charts',date('t',$begin_date));exit;
				}
			}
		} else {
			$this->default_charts('charts',date('t',$begin_date));exit;
		}
		$this->assign('charts',$charts);
		$this->display();
	}

	private function default_charts($assign = 'charts',$times = 30)
	{
		$data = array();
		for ($i = 1; $i <= $times; $i++) {
			$data['xAxis']  .= '"'.$i.'日",';
			$data['total_user'] .= '"0",';
			$data['homepage_uv']   .= '"0",';
			$data['new_fans'] .= '"0",';
			$data['total_fans']   .= '"0",';
		}
		$this->assign($assign,$data);
		$this->display();
		exit;
	}
	
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
		$db_arr = array(C('DB_PREFIX').'area'=>'a',C('DB_PREFIX').'merchant_store'=>'s');
		import('@.ORG.merchant_page');
		$p = new Page($count_store,15);
		if ($this->merchant_session['store_id']) {
		    $store_list = D()->table($db_arr)->field(true)->where("`s`.`mer_id`='$mer_id' AND `s`.`area_id`=`a`.`area_id` AND s.status!=4 AND s.store_id={$this->merchant_session['store_id']}")->select();
		} else {
		    $store_list = D()->table($db_arr)->field(true)->where("`s`.`mer_id`='$mer_id' AND `s`.`area_id`=`a`.`area_id` AND s.status!=4")->order('`sort` DESC,`store_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();
		}
		
		$this->assign('store_list',$store_list);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);

		$this->display();
		
		$json = '{
			   "sid":"'.$company['sid'].'",
			   "business_name":"'.$company['name'].'",
			   "branch_name":"'.$company['shortname'].'",
			   "province":"'.$company['province'].'",
			   "city":"'.$company['city'].'",
			   "district":"'.$company['district'].'",
			   "address":"'.$company['address'].'",
			   "telephone":"'.$company['tel'].'",
			   "categories":["'.$company['categories'].'"],
			   "offset_type":1,
			   "longitude":'.$company['longitude'].',
			   "latitude":'.$company['latitude'].',
			   "photo_list":[{"photo_url":"'.$photo_res['url'].'"}],
			   "recommend":"'.$company['recommend'].'",
			   "special":"'.$company['special'].'",
			   "introduction":"'.$company['introduction'].'",
			   "open_time":"'.$company['open_time'].'",
			   "avg_price":'.$company['avg_price'].'
			}';
		$post_data = '{"business":{"base_info":'.$json.'}}';
	}
	
	public function syn()
	{
		$store_id = intval($_GET['store_id']);
		$where = array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']);
		$store = D('Merchant_store')->field(true)->where($where)->find();
		if (empty($store)) {
		    $this->error('不存在的店铺');
		}
		if ($store['status'] == 2) {
		    //$this->error('平台还没有审核通过');
		}
		if ($store['status'] == 4) {
		    //$this->error('该店铺已被禁用了');
		}
		
		$merchant = D('Merchant')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->find();
		
		$areas = D('Area')->field(true)->where(array('area_id' => array('in', array($store['province_id'], $store['city_id'], $store['area_id']))))->select();
		$province = $city = $district = '';
		foreach ($areas as $area) {
			if ($area['area_id'] == $store['province_id']) {
				$province = $area['area_name'];
			} elseif ($area['area_id'] == $store['city_id']) {
				$city = $area['area_name'];
			} elseif ($area['area_id'] == $store['area_id']) {
				$district = $area['area_name'];
			}
		}
		$open_time = '';
		if ($store['office_time']) {
			foreach (unserialize($store['office_time']) as $time) {
			    $open_time || $open_time = substr($time['open'], 0, 5) . '-' . substr($time['close'], 0, 5);
			}
		} else {
			if ($store['open_1'] == '00:00:00' && $store['close_1'] == '00:00:00') {
				$open_time = '00:00-23:59';
			} else {
			    $open_time = substr($store['open_1'], 0, 5) . '-' . substr($store['close_1'], 0, 5);
			}
		}
		
		if (IS_POST) {
		    $branch_name = isset($_POST['branch_name']) && $_POST['branch_name'] ? htmlspecialchars(trim($_POST['branch_name'])) : $store['name'];
		    $address = isset($_POST['address']) && $_POST['address'] ? htmlspecialchars(trim($_POST['address'])) : $store['adress'];
		    $telephone = isset($_POST['telephone']) && $_POST['telephone'] ? htmlspecialchars(trim($_POST['telephone'])) : $store['phone'];
		    $avg_price = isset($_POST['avg_price']) && $_POST['avg_price'] ? htmlspecialchars(trim($_POST['avg_price'])) : $store['permoney'];
		    $categories = isset($_POST['categories']) && $_POST['categories'] ? htmlspecialchars(trim($_POST['categories'])) : '其他';
		    $recommend = isset($_POST['recommend']) && $_POST['recommend'] ? htmlspecialchars(trim($_POST['recommend'])) : '';
		    $introduction = isset($_POST['introduction']) && $_POST['introduction'] ? htmlspecialchars(trim($_POST['introduction'])) : '';
		    $special = isset($_POST['special']) && $_POST['special'] ? htmlspecialchars(trim($_POST['special'])) : '';
		    $open_time = isset($_POST['open_time']) && $_POST['open_time'] ? htmlspecialchars(trim($_POST['open_time'])) : $open_time;
		    
		    import('@.ORG.longlat');
		    $longlat_class = new longlat();
		    $lngLat = $longlat_class->baiduToGcj02($store['lat'], $store['long']);
		    $json = '{
			   "sid":"' . $store['store_id'] . '",
			   "business_name":"' . $merchant['name'] . '",
			   "branch_name":"' . $branch_name . '",
			   "province":"' . $province . '",
			   "city":"' . $city . '",
			   "district":"' . $district . '",
			   "address":"' . $address.'",
			   "telephone":"' . $telephone. '",
			   "categories":["' . $categories . '"],
			   "offset_type":1,
			   "longitude":' . $lngLat['lng'] . ',
			   "latitude":' . $lngLat['lat'] . ',
			   "recommend":"' . $recommend .'",
			   "special":"' . $special. '",
			   "introduction":"' . $introduction . '",
			   "open_time":"' . $open_time . '",
			   "avg_price":' . $avg_price . '
			}';
		    $post_data = '{"business":{"base_info":'.$json.'}}';
		    
		    $result = $this->dizwifi->synShop($post_data);
		    
		    if (empty($result['errcode'])) {
		        D('Merchant_store')->where($where)->save(array('available_state' => 2));
		        $this->success('同步成功', U('Dizwifi/store'));
		    } else {
		        $this->error($result['errmsg']);
		    }
		} else {
    		$access_token_array = D('Access_token_expires')->get_access_token();
    		
    		$categories = file_get_contents('https://api.weixin.qq.com/cgi-bin/poi/getwxcategory?access_token='.$access_token_array['access_token']);
    		$categories = json_decode($categories, true);
    		$this->assign($categories);
    		$store['open_time'] = $open_time;
    		$store['province'] = $province;
    		$store['city'] = $city;
    		$store['district'] = $district;
    		$store['business_name'] = $merchant['name'];
    		$this->assign('store', $store);
    		$this->display();
		}
	}
}
?>