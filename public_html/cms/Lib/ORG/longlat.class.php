<?php
/* 
 * 经纬度转换
 * 
 */
class longlat{
	private $PI = 3.14159265358979324;
	private $x_pi = 0;

	public function __construct()
	{
		$this->x_pi = 3.14159265358979324 * 3000.0 / 180.0;
	}
	
	/**
	   * http://lbsyun.baidu.com/index.php?title=webapi/guide/changeposition
	   *
       * @param [String] $lat 坐标的纬度
       * @param [String] $lng 坐标的经度
       * @param [Int]    $fromType 坐标来源   仅百度地图正常调用时正常
	   *	源坐标类型：
	   *	1：GPS设备获取的角度坐标，wgs84坐标;
	   *	2：GPS获取的米制坐标、sogou地图所用坐标;
	   *	3：google地图、soso地图、aliyun地图、mapabc地图和amap地图所用坐标，国测局（gcj02）坐标;
	   *	4：3中列表地图坐标对应的米制坐标;
	   *	5：百度地图采用的经纬度坐标;
	   *	6：百度地图采用的米制坐标;
	   *	7：mapbar地图坐标;
	   *	8：51地图坐标
       * @return [Array] 返回记录纬度经度的数组
	*/
	function toBaidu($wgsLat, $wgsLon, $fromType = 3){
		if($fromType == 5){
			return array('lat' => $wgsLat,'lng' => $wgsLon);
		}
	    $url = 'http://api.map.baidu.com/geoconv/v1/?coords=' . $wgsLon. ',' . $wgsLat. '&from='.$fromType.'&to=5&ak=' . C('config.baidu_map_ak') . '&output=json';
	    import('ORG.Net.Http');
	    $http = new Http();
	    $result = $http->curlGet($url);
	    if ($result) {
	        $result = json_decode($result, true);
	        if ($result['status'] == 0) {
	            return array('lat' => $result['result'][0]['y'],'lng' => $result['result'][0]['x']);
	        }
	    }
	    
		return false;
	}
	
	
	/**
       * 腾讯地图坐标转百度地图坐标
       * @param [String] $lat 腾讯地图坐标的纬度
       * @param [String] $lng 腾讯地图坐标的经度
       * @return [Array] 返回记录纬度经度的数组
	*/
	function gpsToBaidu($wgsLat, $wgsLon)
	{

	    $url = 'http://api.map.baidu.com/geoconv/v1/?coords=' . $wgsLon. ',' . $wgsLat. '&from=3&to=5&ak=' . C('config.baidu_map_ak') . '&output=json';
	    import('ORG.Net.Http');
	    $http = new Http();
	    $result = $http->curlGet($url);
	    if ($result) {
	        $result = json_decode($result, true);
	        if ($result['status'] == 0) {
	            return array('lat' => $result['result'][0]['y'],'lng' => $result['result'][0]['x']);
	        }
	    }
	    
		if ($this->outOfChina($wgsLat, $wgsLon)) {
			return $this->bd_encrypt($gcjLat, $wgsLon);
		}
		$d = $this->delta($wgsLat, $wgsLon);
		return $this->bd_encrypt($wgsLat + $d['lat'], $wgsLon + $d['lng']);
		
		
		
		$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
		$x = $lng;
		$y = $lat;
		$z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
		$theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
		$lng = $z * cos($theta) + 0.0065;
		$lat = $z * sin($theta) + 0.006;
		return array('lng'=>$lng,'lat'=>$lat);
	}
	
	private function outOfChina($lat, $lon)
	{
		if ($lon < 72.004 || $lon > 137.8347)
			return TRUE;
		if ($lat < 0.8293 || $lat > 55.8271)
			return TRUE;
		return FALSE;
	}

	private function delta($lat, $lon)
	{
		// Krasovsky 1940
		//
		// a = 6378245.0, 1/f = 298.3
		// b = a * (1 - f)
		// ee = (a^2 - b^2) / a^2;
		$a = 6378245.0;//  a: 卫星椭球坐标投影到平面地图坐标系的投影因子。
		$ee = 0.00669342162296594323;//  ee: 椭球的偏心率。
		$dLat = $this->transformLat($lon - 105.0, $lat - 35.0);
		$dLon = $this->transformLon($lon - 105.0, $lat - 35.0);
		$radLat = $lat / 180.0 * $this->PI;
		$magic = sin($radLat);
		$magic = 1 - $ee * $magic * $magic;
		$sqrtMagic = sqrt($magic);
		$dLat = ($dLat * 180.0) / (($a * (1 - $ee)) / ($magic * $sqrtMagic) * $this->PI);
		$dLon = ($dLon * 180.0) / ($a / $sqrtMagic * cos($radLat) * $this->PI);
		return array('lat' => $dLat, 'lng' => $dLon);
	}

	private function transformLat($x, $y) {
		$ret = -100.0 + 2.0 * $x + 3.0 * $y + 0.2 * $y * $y + 0.1 * $x * $y + 0.2 * sqrt(abs($x));
		$ret += (20.0 * sin(6.0 * $x * $this->PI) + 20.0 * sin(2.0 * $x * $this->PI)) * 2.0 / 3.0;
		$ret += (20.0 * sin($y * $this->PI) + 40.0 * sin($y / 3.0 * $this->PI)) * 2.0 / 3.0;
		$ret += (160.0 * sin($y / 12.0 * $this->PI) + 320 * sin($y * $this->PI / 30.0)) * 2.0 / 3.0;
		return $ret;
	}

	private function transformLon($x, $y) {
		$ret = 300.0 + $x + 2.0 * $y + 0.1 * $x * $x + 0.1 * $x * $y + 0.1 * sqrt(abs($x));
		$ret += (20.0 * sin(6.0 * $x * $this->PI) + 20.0 * sin(2.0 * $x * $this->PI)) * 2.0 / 3.0;
		$ret += (20.0 * sin($x * $this->PI) + 40.0 * sin($x / 3.0 * $this->PI)) * 2.0 / 3.0;
		$ret += (150.0 * sin($x / 12.0 * $this->PI) + 300.0 * sin($x / 30.0 * $this->PI)) * 2.0 / 3.0;
		return $ret;
	}
	//GCJ-02 to BD-09
	public function bd_encrypt($gcjLat, $gcjLon) {
		$x = $gcjLon; $y = $gcjLat;
		$z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $this->x_pi);
		$theta = atan2($y, $x) + 0.000003 * cos($x * $this->x_pi);
		$bdLon = $z * cos($theta) + 0.0065;
		$bdLat = $z * sin($theta) + 0.006;
		return array('lat' => $bdLat,'lng' => $bdLon);
	}
	
	
	/**
       * 百度地图坐标转腾讯地图等火星坐标
       * @param [String] $lat 百度地图坐标的纬度
       * @param [String] $lng 百度地图坐标的经度
       * @return [Array] 返回记录纬度经度的数组
	*/
	function baiduToGcj02($lat,$lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta);
        $lat = $z * sin($theta);
        return array('lng'=>$lng,'lat'=>$lat);
	}
	/* function baiduToGcj02($lat,$lng){
		$qqMapSecret = 'Y75BZ-N3KA5-CJQIF-QJ7QQ-UGDBE-FRBJM';
		import('ORG.Net.Http');
		$http = new Http();
		$return = Http::curlGet('http://apis.map.qq.com/ws/coord/v1/translate?locations='.$lat.','.$lng.'&type=3&key='.$qqMapSecret);
		$returnArr = json_decode($return,true);
		file_put_contents('./runtime/baidu.php',var_export($returnArr,true));
		if($returnArr && $returnArr['status'] == 0){
			return array('lng'=>$returnArr['locations'][0]['lng'],'lat'=>$returnArr['locations'][0]['lat']);
		}else{
			return false;
		}
	} */
	//百度地图坐标计算
	function rad($d){  
		   return $d * 3.1415926535898 / 180.0;  
	}
	/**
       * 百度地图坐标计算两点之间的距离
       * @param [String] $lat1 A点的纬度
       * @param [String] $lng1 A点的经度
       * @param [String] $lat2 B点的纬度
       * @param [String] $lng2 B点的经度
       * @return [String] 两点坐标间的距离，输出单位为米
	*/
	function GetDistance($lat1,$lng1,$lat2,$lng2){
	   $EARTH_RADIUS = 6378.137;//地球的半径
	   $radLat1 = $this->rad($lat1);   
	   $radLat2 = $this->rad($lat2);  
	   $a = $radLat1 - $radLat2;  
	   $b = $this->rad($lng1) - $this->rad($lng2);  
	   $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));  
	   $s = $s *$EARTH_RADIUS;  
	   $s = round($s * 10000) / 10000;
	   $s=$s*1000;
	   return ceil($s);  
	}
	
	/**
       * 标记大概的距离，做出友好的距离提示
       * @param [$number] 距离数量
       * @return[String] 距离提示
	*/
	function mToKm($range){
		$return = array();
		if($range < 100){
			$return['num'] = $range;
			$return['unit'] = 'm';
			$return['cunit'] = '米';
		}elseif($range < 1000){
			$return['num'] = round($range/1000,1);
			$return['unit'] = 'km';
			$return['cunit'] = '千米';
		}else if($range<5000){
			$return['num'] = round($range/1000,2);
			$return['unit'] = 'km';
			$return['cunit'] = '千米';
		}else if($range<10000){
			$return['num'] = round($range/1000,1);
			$return['unit'] = 'km';
			$return['cunit'] = '千米';
		}else{
			$return['num'] = floor($range/1000);
			$return['unit'] = 'km';
			$return['cunit'] = '千米';
		}
		return $return;
	}
	function GetDistanceMToKm($lat1,$lng1,$lat2,$lng2,$is_chinese = false){
		$mToKm = $this->mToKm($this->GetDistance($lat1,$lng1,$lat2,$lng2));
		return $is_chinese ? $mToKm['num'].$mToKm['cunit'] : $mToKm['num'].$mToKm['unit'];
	}
	
	public function getRidingDistance($frmLat, $frmLng, $orgLat, $orgLng)
	{
	    $url = 'http://api.map.baidu.com/routematrix/v2/riding?origins=' . $frmLat. ',' . $frmLng. '&destinations=' . $orgLat . ',' . $orgLng . '&ak=' . C('config.baidu_map_ak') . '&output=json';
	    import('ORG.Net.Http');
	    $http = new Http();
	    $result = $http->curlGet($url);
	    $newLatLong = -1;
	    if ($result) {
	        $result = json_decode($result, true);
	        if ($result['status'] == 0) {
	            $newLatLong = floatval($result['result'][0]['distance']['value']);
	        }
	    }
	    return $newLatLong;
	}
}
?>