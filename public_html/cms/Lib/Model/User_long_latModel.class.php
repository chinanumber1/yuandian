<?php
class User_long_latModel extends Model{
	/*保存地理位置*/
	public function saveLocation($openid,$long,$lat,$type=1){
		if($openid){
			if ($this->field(true)->where(array('open_id' => $openid))->find()) {
				$this->where(array('open_id' => $openid))->save(array('long' => $long, 'lat' => $lat, 'dateline' => $_SERVER['REQUEST_TIME'],'type'=>$type));
			} else {
				$this->add(array('long' => $long, 'lat' => $lat, 'dateline' => $_SERVER['REQUEST_TIME'], 'open_id' => $openid,'type'=>$type));
			}
			if(C('config.im_appid') && $type == 1){
				//20分钟一次推送
				if(empty($_SESSION['last_im_time']) || $_SESSION['last_im_time'] - $_SERVER['REQUEST_TIME'] < 1200){
					$im = new im();
					$im->saveLocation($openid,$long,$lat);
					$_SESSION['last_im_time'] = $_SERVER['REQUEST_TIME'];
				}
			}
		}else{
			return array('errCode'=>true,'errMsg'=>'没有携带openid');
		}
	}
	/*
	 * 得到地理位置
	 *
	 * 时效120秒
	 *
	 * 存的是 GPS定位，系统使用的是百度地图，进行转换
	 *
	*/
	public function getLocation($openid,$timeout=120,$user_long_lat=array()){
		if($openid){
			if(empty($user_long_lat)){
				$user_long_lat = $this->where(array('open_id' => $openid))->find();
				if($timeout == 120 && $_SESSION['user']['from_device_id'] == 'wxapp'){
					$timeout = 240;
				}
			}
           // print_r($user_long_lat);exit;
			if($user_long_lat && $user_long_lat['long']){
				if($timeout != 0 && $user_long_lat['dateline'] < $_SERVER['REQUEST_TIME'] - $timeout){
					return array();
				}
				import('@.ORG.longlat');
				$longlat_class = new longlat();
				$location2 = $longlat_class->toBaidu($user_long_lat['lat'], $user_long_lat['long'],$user_long_lat['type'] ? $user_long_lat['type'] : 1);
				return array('long'=>$location2['lng'],'lat'=>$location2['lat'],'dateline'=>$user_long_lat['dateline']);
			}
		}
		if($_REQUEST['latitude'] && $_REQUEST['longitude']){
			if ($_REQUEST['locateType']=='baidu') {
				import('@.ORG.longlat');
				$longlat_class = new longlat();
				$location2 = $longlat_class->toBaidu($_REQUEST['latitude'], $_REQUEST['longitude'],1);
				return array('long'=>$location2['lng'],'lat'=>$location2['lat'],'dateline'=>$_SERVER['REQUEST_TIME']);
			}else{
				return array('long'=>$_REQUEST['latitude'],'lat'=>$_REQUEST['longitude'],'dateline'=>$_SERVER['REQUEST_TIME']);
			}
		}
		
		if($_COOKIE['userLocationLong'] && $_COOKIE['userLocationLat']){
			//代表是百度地图地位
			if($_COOKIE['userLocationName']){
				return array('long'=>$_COOKIE['userLocationLong'],'lat'=>$_COOKIE['userLocationLat'],'dateline'=>$_SERVER['REQUEST_TIME']);
			}
			import('@.ORG.longlat');
			$longlat_class = new longlat();
			$location2 = $longlat_class->gpsToBaidu($_COOKIE['userLocationLat'], $_COOKIE['userLocationLong']);
			return array('long'=>$location2['lng'],'lat'=>$location2['lat'],'dateline'=>$_SERVER['REQUEST_TIME']);
		}
		return array();
	}
}
?>