<?php
/*
 * 地图处理
 *
 */
class MapAction extends BaseAction{
	public function suggestion(){
		header("Content-type: application/json");
		$city_id = isset($_GET['city_id']) ? intval($_GET['city_id']) : $this->config['now_city'];
		$now_city = D('Area')->field(true)->where(array('area_id' => $city_id))->find();
		
		if($now_city['area_name'] == '黔东南'){
			$now_city['area_name'] = '黔东南苗族侗族自治州';
		}
		
		$_GET['query'] = str_replace('澳門','澳门',$_GET['query']);
		$now_city['area_name'] = str_replace('澳門','澳门',$now_city['area_name']);
		
		$url = 'http://api.map.baidu.com/place/v2/suggestion?query='.urlencode($_GET['query']).'&region='.urlencode($now_city['area_name']).'&ak=4c1bb2055e24296bbaef36574877b4e2&output=json';
		import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
		if($result){
			$result = json_decode($result,true);
			if($result['status'] == 0 && $result['result']){
				$return = array();
				foreach($result['result'] as $value){
					if (!isset($value['location'])) continue; 
					$return[] = array(
						'name'=>$value['name'],
						'lat'=>$value['location']['lat'],
						'long'=>$value['location']['lng'],
						'city'=>$value['city'],
						'district'=>$value['district'],
						'address'=>$value['city'].$value['district'].$value['name']
					);
				}
				exit(json_encode(array('status'=>1,'result'=>$return)));
			}else{
				exit(json_encode(array('status'=>2,'result'=>'没有查找到内容')));
			}
		}else{
			exit(json_encode(array('status'=>0,'result'=>'获取失败')));
		}
	}
	public function toBaidu(){
		if(empty($_POST)){
			$input_post = file_get_contents('php://input');
			$_POST = json_decode($input_post,true);
		}
		header("Content-type: application/json");
		import('@.ORG.longlat');
		$longlat_class = new longlat();
		$location2 = $longlat_class->toBaidu($_POST['lat'],$_POST['lng'],1);//转换腾讯坐标到百度坐标
		if($_POST['geocoder']){
			$geocoder = $this->geocoder($location2['lng'],$location2['lat']);
			$location2['name'] = $geocoder['name'];
			$location2['city'] = $this->formart_city_name($geocoder['city']);
		}
		exit(json_encode($location2));
	}
	public function gpsToBaidu(){
		if(empty($_POST)){
			$input_post = file_get_contents('php://input');
			$_POST = json_decode($input_post,true);
		}
		header("Content-type: application/json");
		import('@.ORG.longlat');
		$longlat_class = new longlat();
		$location2 = $longlat_class->gpsToBaidu($_POST['lat'],$_POST['lng']);//转换腾讯坐标到百度坐标
		if($_POST['geocoder']){
			$geocoder = $this->geocoder($location2['lng'],$location2['lat']);
			$location2['name'] = $geocoder['name'];
			$location2['city'] = $this->formart_city_name($geocoder['city']);
		}
		exit(json_encode($location2));
	}
	public function formart_city_name($v){
		$long = strlen($v);
		if ($long >= 7) {
			$v = str_replace('省', '', $v);
			$v = str_replace('市', '', $v);
			$v = str_replace('地区', '', $v);
			$v = str_replace('特别行政区', '', $v);
			$v = str_replace('特別行政區', '', $v);
			$v = str_replace('蒙古自治州', '', $v);
			$v = str_replace('回族自治州', '', $v);
			$v = str_replace('柯尔克孜自治州', '', $v);
			$v = str_replace('哈萨克自治州', '', $v);
			$v = str_replace('土家族苗族自治州', '', $v);
			$v = str_replace('藏族羌族自治州', '', $v);
			$v = str_replace('傣族自治州', '', $v);
			$v = str_replace('布依族苗族自治州', '', $v);
			$v = str_replace('苗族侗族自治州', '', $v);
			$v = str_replace('壮族苗族自治州', '', $v);
			$v = str_replace('澳门', '澳門', $v);
			$v = str_replace('朝鲜族自治州', '', $v);
			$v = str_replace('哈尼族彝族自治州','',$v);
		}
		
		return $v;
	}
	public function geocoder($lng,$lat){
		$url = 'http://api.map.baidu.com/geocoder/v2/?location='.$lat.','.$lng.'&output=json&pois=1&ak=4c1bb2055e24296bbaef36574877b4e2';
		import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
		if($result){
			$result = json_decode($result,true);
			if(!empty($result['result']['pois'])){
				return array(
					'name' => $result['result']['pois'][0]['name'],
					'city' => $result['result']['addressComponent']['city'],
					'province' => $result['result']['addressComponent']['province'],
					'district' => $result['result']['addressComponent']['district'],
				);
			}else{
				return array(
					'name' => $result['result']['addressComponent']['street'].$result['result']['addressComponent']['street_number'],
					'city' => $result['result']['addressComponent']['city'],
					'province' => $result['result']['addressComponent']['province'],
					'district' => $result['result']['addressComponent']['district'],
				);
			}
		}else{
			return array();
		}
	}
    //暂时停用
	public function geocoder_google($lng,$lat){
	    $key =C('config.google_map_ak');
        $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$lat.','.$lng.'&radius=500&key='.$key;
        echo $url;
        import('ORG.Net.Http');
        $http = new Http();
        $result = $http->curlGet($url);
        if($result) {
            $result = json_decode($result, true);
            return $result;
        }
    }
}