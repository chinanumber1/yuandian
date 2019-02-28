<?php
/*
 * 地图处理
 *
 */
class MapAction extends BaseAction{
	public function geocoder(){
		$lng = $_POST['lng'];
		$lat = $_POST['lat'];
		$url = 'http://api.map.baidu.com/geocoder/v2/?location='.$lat.','.$lng.'&output=json&pois=1&ak=4c1bb2055e24296bbaef36574877b4e2';
		import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
		if($result){
			$result = json_decode($result,true);
			if($result['status'] == 0){
				$return = array();
				if($result['result']['pois']){
					foreach($result['result']['pois'] as $value){
						if(empty($value['point']['x'])){
							continue;
						}
						$return[] = array(
							'name'=>$value['name'],
							'lat'=>$value['point']['y'],
							'long'=>$value['point']['x'],
							'lng'=>$value['point']['x'],
							'adress'=>$value['addr'],
							'address'=>$value['addr']
						);
					}
				}
				$this->returnCode(0,$return);
			}else{
				$this->returnCode('20000002');
			}
		}else{
			$this->returnCode('20000002');
		}
	}
	public function suggestion(){
		if($_POST['area_name']){
			$area_name = $_POST['area_name'];
		}else{
			$now_city = D('Area')->get_area_by_areaId($this->config['now_city']);
			$area_name = $now_city['area_name'];
		}
		$city_limit = 'true';
		if($area_name == '西双版纳'){
			$area_name = '西双版纳傣族自治州';
		}else{
			$city_limit = 'false';
		}
		$query = $_POST['query'] ? $_POST['query'] : $_GET['query'];
		$url = 'http://api.map.baidu.com/place/v2/suggestion?query='.urlencode($query).'&region='.urlencode($area_name).'&ak='.$this->config['baidu_map_ak'].'&city_limit='.$city_limit.'&output=json';
		
		import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
		if($result){
			$result = json_decode($result,true);
			if($result['status'] == 0){
				$return = array();
				if($result['result']){
					foreach($result['result'] as $value){
						if(empty($value['location']['lng'])){
							continue;
						}
						$tmpInfo = array(
							'name'=>$value['name'],
							'address_name'=>$value['name'],
							'lat'=>$value['location']['lat'],
							'long'=>$value['location']['lng'],
							'lng'=>$value['location']['lng'],
							'adress'=>$value['city'].$value['district'].$value['name'],
							'address'=>$value['city'].$value['district'].$value['name'],
							'district'=>$value['district']
						);
						$tmpInfoName = str_replace($query,'&^&'.$query.'&^&',$tmpInfo['name']);
						$tmpNameArr = explode('&^&',$tmpInfoName);
						$tmpInfo['name_arr'] = array();
						foreach($tmpNameArr as $tmpValue){
							if(!empty($tmpValue)){
								$tmpInfo['name_arr'][] = array(
									'value'   => $tmpValue,
									'is_high' => $tmpValue == $query ? true : false
								);
							}
						}
						if($_POST['lng'] && $_POST['lat']){
							$tmpInfo['distance'] = getDistance($_POST['lat'],$_POST['lng'],$tmpInfo['lat'],$tmpInfo['lng']);
							$tmpInfo['distance'] = getFormatNumber($tmpInfo['distance']/1000);
						}
						$return[] = $tmpInfo;
					}
				}
				$this->returnCode(0,$return);
			}else{
				$this->returnCode('20000002');
			}
		}else{
			$this->returnCode('20000002');
		}
	}
	/*百度经纬度转火星经纬度*/
	public function baiduToGcj02(){
		import('@.ORG.longlat');
		$longlat_class = new longlat();
		$location2 = $longlat_class->baiduToGcj02($_POST['baidu_lat'], $_POST['baidu_lng']);
		if($location2){
			$this->returnCode(0,$location2);
		}else{
			$this->returnCode('20000002');
		}
	}
}