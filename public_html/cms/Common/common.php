<?php
/*
 * 截取中文字符串	
 */
function msubstr($str,$start=0,$length,$suffix=true,$charset="utf-8"){
    if(function_exists("mb_substr")){
        if ($suffix && mb_strlen($str, $charset)>$length)
            return mb_substr($str, $start, $length, $charset)."...";
        else
            return mb_substr($str, $start, $length, $charset);
    }elseif(function_exists('iconv_substr')) {
        if ($suffix && strlen($str)>$length)
            return iconv_substr($str,$start,$length,$charset)."...";
        else
            return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}

function arr_htmlspecialchars(&$value,$key,$isget=false){
	if($isget == true){
		$value = str_replace(array('<','>','"','\'','%3c', '%3e','%3C', '%3E'),'',$value);
	}
	$value = htmlspecialchars($value);
}
function arr_htmlspecialchars_decode(&$value,$key,$isget=false){
	if($isget == true){
		$value = str_replace(array('<','>','"','\'','%3c', '%3e','%3C', '%3E'),'',$value);
	}
	$value = htmlspecialchars_decode($value);
}

function fulltext_filter($value){
	return htmlspecialchars_decode($value);
}

    /**
     * 加密和解密函数
     *
     * <code>
     * // 加密用户ID和用户名
     * $auth = authcode("{$uid}\t{$username}", 'ENCODE');
     * // 解密用户ID和用户名
     * list($uid, $username) = explode("\t", authcode($auth, 'DECODE'));
     * </code>
     *
     * @access public
     * @param  string  $string    需要加密或解密的字符串
     * @param  string  $operation 默认是DECODE即解密 ENCODE是加密
     * @param  string  $key       加密或解密的密钥 参数为空的情况下取全局配置encryption_key
     * @param  integer $expiry    加密的有效期(秒)0是永久有效 注意这个参数不需要传时间戳
     * @return string
     */
    function Encryptioncode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;
        $key = md5($key != '' ? $key : 'lhs_simple_encryption_code_45120');
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

 /*****
 **生成简单的随机数
 **$length 需要的长度
 **$onlynum 生成纯数字的
 **$nouppLetter  不需要大写的，数字和小写的混合
 **/
function createRandomStr($length=6,$onlynum=false,$nouppLetter=false){
	if(!($length>0)) return false;
	$returnstr='';
	if($onlynum){
	   for($i=0;$i<$length;$i++){
	     $returnstr .= rand(0,9);
	   }
	}else if($nouppLetter){
	   $strarr = array_merge(range(0,9),range('a','z'));
	   shuffle($strarr);
	   shuffle($strarr);
	   $returnstr = implode('',array_slice($strarr,0,$length));
	}else{
	  $strarr = array_merge(range(0,9),range('a','z'),range('A','Z'));
	  shuffle($strarr);
	  shuffle($strarr);
	  $returnstr = implode('',array_slice($strarr,0,$length));
	}
    return $returnstr;
}

/**
 * *封装一个通用的
 * cURL封装**
 * *$postfields 参数
 * */
function httpRequest($url, $method = 'GET', $postfields = null, $headers = array(), $debug = false) {
    /* $Cookiestr = "";  * cUrl COOKIE处理* 
      if (!empty($_COOKIE)) {
      foreach ($_COOKIE as $vk => $vv) {
      $tmp[] = $vk . "=" . $vv;
      }
      $Cookiestr = implode(";", $tmp);
      } */
    $method = strtoupper($method);
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    switch ($method) {
        case "POST":
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
            }
            break;
        default:
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
            break;
    }
    $ssl = preg_match('/^https:\/\//i', $url) ? TRUE : FALSE;
    curl_setopt($ci, CURLOPT_URL, $url);
    if ($ssl) {
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
    }
    //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
    curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ci, CURLOPT_MAXREDIRS, 2); /* 指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的 */
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
    /* curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
    $response = curl_exec($ci);
    $requestinfo = curl_getinfo($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    if ($debug) {
        echo "=====post data======\r\n";
        var_dump($postfields);
        echo "=====info===== \r\n";
        print_r($requestinfo);

        echo "=====response=====\r\n";
        print_r($response);
    }
    curl_close($ci);
    return array($http_code, $response, $requestinfo);
}

/**
 * *封装一个通用的带cookie
 * cURL封装**
 * *$postfields 参数
 * */
function httpRequestWithCookie($url, $method = 'GET', $postfields = null, $headers = array(), $debug = false,$header_out = true) {

    $Cookiestr = "";  //* cUrl COOKIE处理* 
    if (!empty($_COOKIE)) {
        foreach ($_COOKIE as $vk => $vv) {
            if($vk=='PHPSESSID'){
                continue;
            }
            $tmp[] = $vk . "=" . $vv;
        }
        $Cookiestr = implode(";", $tmp);
    }
    $method = strtoupper($method);
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36");
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    switch ($method) {
        case "POST":
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
            }
            break;
        default:
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
            break;
    }
    $ssl = preg_match('/^https:\/\//i', $url) ? TRUE : FALSE;
    curl_setopt($ci, CURLOPT_URL, $url);
    if ($ssl) {
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
    }

    if($header_out){
        curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
    }
    curl_setopt($ci, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ci, CURLOPT_MAXREDIRS, 2); /* 指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的 */
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
    curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); /* *COOKIE带过去** */
    $response = curl_exec($ci);

    preg_match_all('/Set-Cookie:(.*);/iU',$response,$cookies); //正则匹配

    $response_list = explode(PHP_EOL.PHP_EOL, $response);

    $requestinfo = curl_getinfo($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    if ($debug) {
        echo "=====post data======\r\n";
        var_dump($postfields);
        echo "=====info===== \r\n";
        print_r($requestinfo);

        echo "=====response=====\r\n";
        print_r($response);
    }
    curl_close($ci);
    if($header_out){
        return array($http_code, $response_list[count($response_list)-1], $requestinfo,$cookies[1]);
    }else{
        return array($http_code, $response, $requestinfo,$cookies[1]);
    }
    
}

/** 
* @desc 根据两点间的经纬度计算距离 
* @param float $lat 纬度值 
* @param float $lng 经度值 
*/
function getDistance($lat1, $lng1, $lat2, $lng2){
	$earthRadius = 6367000;
	$lat1 = ($lat1 * pi() ) / 180;
	$lng1 = ($lng1 * pi() ) / 180;

	$lat2 = ($lat2 * pi() ) / 180;
	$lng2 = ($lng2 * pi() ) / 180;

	$calcLongitude = $lng2 - $lng1;
	$calcLatitude = $lat2 - $lat1;
	$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
	$stepTwo = 2 * asin(min(1, sqrt($stepOne)));
	$calculatedDistance = $earthRadius * $stepTwo;
	return round($calculatedDistance);
} 

function getRange($range,$space = true){
	if($range < 1000){
		return $range.($space ? ' ' : '').'m';
	}else{
		return floatval(round($range/1000,2)).($space ? ' ' : '').'km';
	}
}

/**
 * 指点的经纬是否在多边形的地图内
 * @param float $lng
 * @param float $lat
 * @param array $latLngData
 * @return boolean
 */
function isPtInPoly($lng, $lat, $latLngData) 
{
    foreach ($latLngData as $latLng) {
        $iCount = count($latLng);
        if ($iCount < 3) continue;
        $iSum = 0;
        for ($i = 0; $i < $iCount; $i++) {
            if ($i == $iCount - 1) {
                $dLon1 = $latLng[$i]['lng'];
                $dLat1 = $latLng[$i]['lat'];
                $dLon2 = $latLng[0]['lng'];
                $dLat2 = $latLng[0]['lat'];
            } else {
                $dLon1 = $latLng[$i]['lng'];
                $dLat1 = $latLng[$i]['lat'];
                $dLon2 = $latLng[$i + 1]['lng'];
                $dLat2 = $latLng[$i + 1]['lat'];
            }
            //以下语句判断A点是否在边的两端点的水平平行线之间，在则可能有交点，开始判断交点是否在左射线上
            if ((($lat >= $dLat1) && ($lat < $dLat2)) || (($lat >= $dLat2) && ($lat < $dLat1))) {
                if (abs($dLat1 - $dLat2) > 0) {
                    //得到 A点向左射线与边的交点的x坐标：
                    $dLon = $dLon1 - (($dLon1 - $dLon2) * ($dLat1 - $lat)) / ($dLat1 - $dLat2);
                    if ($dLon < $lng) $iSum ++;
                }
            }
        }
        if ($iSum % 2 != 0) return true;
    }
    return false;
}


//得到带URL的链接
//支持最多5个参数
function UU(){
	switch(func_num_args()){
		case 0:
			return C('config.config_site_url');
		case 1:
			return C('config.config_site_url').U(func_get_arg(0));
		case 2:
			return C('config.config_site_url').U(func_get_arg(0),func_get_arg(1));
		case 3:
			return C('config.config_site_url').U(func_get_arg(0),func_get_arg(1),func_get_arg(2));
		case 4:
			return C('config.config_site_url').U(func_get_arg(0),func_get_arg(1),func_get_arg(2),func_get_arg(3));
		case 5:
			return C('config.config_site_url').U(func_get_arg(0),func_get_arg(1),func_get_arg(2),func_get_arg(3),func_get_arg(4));
	}
}

//通用的加密数组串
function get_butt_encrypt_key($butt_array,$butt_key,$only_key = false){
	$new_arr = array();
	if(empty($butt_array['encrypt_time'])){
		$butt_array['encrypt_time'] = $_SERVER['REQUEST_TIME'];		//为了多页面能调到统一的时候，采用SERVER中的REQUEST_TIME。
	}
	ksort($butt_array);
	foreach($butt_array as $key=>$value){
		$new_arr[] = $key.'='.$value;
	}
	$new_arr[] = 'butt_key='.$butt_key;
	
	$string = implode('&',$new_arr);
	if($only_key){
		return md5($string);
	}else{
		$butt_array['encrypt_key'] = md5($string);
		return $butt_array;
	}
}

//转换Wap下的LBS链接
function wapLbsTranform($url,$param=array(),$returnLbs = false){
	if(stripos($url , 'LBS://')!==FALSE){
		$url = parse_url($url);
		$long_lat = explode(',',$url['host']);
		$param['long'] = $long_lat[0];
		$param['lat'] = $long_lat[1];
                
                if(defined('IS_INDEP_HOUSE')){
                    $url= C('config.site_url').'/wap_house.php?c=Lbs&a=show&'.http_build_query($param);
                }else{
                    $url= C('config.site_url').'/wap.php?c=Lbs&a=show&'.http_build_query($param);
                }
		
		
		if($returnLbs){
			$return['url'] = $url;
			$return['long'] = $param['long'];
			$return['lat'] = $param['lat'];
			return $return;
		}
	}
	return $url;
}

//查询数据整理递归函数（无限制级别）
function arrayPidProcess($data,$res=array(),$pid='0',$endlevel='0'){
    foreach ($data as $k => $value){
         /**********控制商家的菜单显示************/
        if($value['fid']==$pid){
            $select_module = explode(',',$value['select_module']);
            $select_action = explode(',',$value['select_action']);
            if(in_array(MODULE_NAME,$select_module) && (empty($value['select_action']) || in_array(ACTION_NAME,$select_action))){
                $value['is_active'] = true;
            }
            $value['url'] = U($value['module'].'/'.$value['action']);
            $res[$value['id']]=$value;
            unset($data[$k]);
            if($endlevel!='0'){
                if($value['level']!=$endlevel){
                     $child=arrayPidProcess($data,array(),$value['id'],$endlevel);
                }
                $res[$value['id']]['menu_list']=$child;
            }else{
                $child=arrayPidProcess($data,array(),$value['id']);
                if(!($child==''||$child==null)){
                     $res[$value['id']]['menu_list']=$child;
                }
            }
        }
    }

    return $res;
}


function uniqid_rand(){
    return uniqid().mt_rand(100,999);
}

function sortArrayAsc($preData,$sortType='price'){    
    $sortData = array();
    foreach ($preData as $key_i => $value_i){
        $price_i = $value_i[$sortType];
        $value_i['array_key'] = $key_i;
        $min_key = '';
        $sort_total = count($sortData);
        foreach ($sortData as $key_j => $value_j){
            if($price_i<$value_j[$sortType]){
                $min_key = $key_j+1;
                break;
            }
        }
        if(empty($min_key)){
            array_push($sortData, $value_i);
        }else {
            $sortData1 = array_slice($sortData, 0,$min_key-1);
            array_push($sortData1, $value_i);
            if(($min_key-1)<$sort_total){
                $sortData2 = array_slice($sortData, $min_key-1);
                foreach ($sortData2 as $value){
                    array_push($sortData1, $value);
                }
            }
            $sortData = $sortData1;
        }
    }
    return $sortData;
}
//二维数组排序
function sortArray($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC){  
	if(is_array($arrays)){
		foreach ($arrays as $array){
			if(is_array($array)){
				$key_arrays[] = $array[$sort_key];  
			}else{
				return $arrays;  
			}
		}
	}else{
		return $arrays;  
	} 
	array_multisort($key_arrays,$sort_order,$sort_type,$arrays);  
	return $arrays;
}

/**
 * 调试数据的本地保存
 *
 * <code>
 * // O2O缓存目录在网站根目录下的runtime文件夹
 * // 简单的调试
 * fdump($arr); 会在缓存目录下保存一个  test_fdump.php 的文件
 * // 自定义文件名的调试
 * fump($arr,'custom'); 会在缓存目录下替换保存一个  custom_fdump.php 的文件
 * // 追加到文件中的调试
 * fump($arr,'custom',true); 会在缓存目录下保存一个  custom_fdump.php 的文件 在文件末尾追加内容
 * </code>
 *
 * @access public
 * @param  string  $data    进行调试的数据
 * @param  string  $filename 调试文件的文件名，后面会自动追加 _fdump.php，方便文件存储的分类辨别
 * @param  string  $append    是否采用追加的模式，默认不采用、覆盖文件
 * @return string
 */
function fdump($data,$filename='test',$append=false){
	if(strpos($filename,'/') > 0){
		$fileName = rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/'.$filename.'_fdump.php';
	}else{
		$fileName = rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/runtime/'.$filename.'_fdump.php';
	}
	
	if($append){
		if(!file_exists($fileName)){
			file_put_contents($fileName,'<?php');
		}
		file_put_contents($fileName,PHP_EOL.var_export($data,true).PHP_EOL,FILE_APPEND);
	}else{
		file_put_contents($fileName,'<?php'.PHP_EOL.var_export($data,true));
	}
}


/**
 * 调试数据的数据库保存
 *
 *
 * @access public
 * @param  string  $data     调试数据
 * @param  string  $filename 调试名称
 * @return string
 */
function fdump_sql($data,$name){
	$data = var_export($data,true);
	$data_fdump = array(
		'data' => $data,
		'name' => $name,
		'time' => time(),
	);
	M('Fdump_sql')->data($data_fdump)->add();
}


/**
 * 计算给定时间戳与当前时间相差的时间，并以一种比较友好的方式输出
 * @param  [int] $timestamp [给定的时间戳]
 * @param  [int] $current_time [要与之相减的时间戳，默认为当前时间]
 * @return [string]            [相差天数]
 */
function tmspan($timestamp,$current_time=0){
    if(!$current_time) $current_time=time();
    $span=$current_time-$timestamp;
    if($span<60){
        return "刚刚";
    }else if($span<3600){
        return intval($span/60)."分钟前";
    }else if($span<24*3600){
        return intval($span/3600)."小时前";
    }else if($span<(7*24*3600)){
        return intval($span/(24*3600))."天前";
    }else{
        return date('Y-m-d',$timestamp);
    }
}

function getAttachmentUrl($fileUrl, $is_remote = true){

    if(empty($fileUrl)){
        return '';
    }else{
        // 如果已经是完整url地址，则不做处理
        if (strstr($fileUrl, 'http://') !== false) {
            return $fileUrl;
        }
		if (strstr($fileUrl, 'https://') !== false) {
            return $fileUrl;
        }
		
        $attachment_upload_type = C('config.attachment_upload_type');
        $url = C('config.site_url') . '/upload/';

        // 如果当前路径中已有upload，将不增加此路径
        if (strstr($fileUrl, 'upload/') !== false) {
            $url = C('config.site_url') . '/';
        }

        if ($attachment_upload_type == '1' && $is_remote) {
            $url = 'http://' . C('config.attachment_up_domainname') . '/';
        }

        return $url . $fileUrl;
    }
}

function getFormatNumber($number){
	$number = number_format($number,2);
	if(strpos($number,'.') !== false){
		$number = rtrim($number,'0');
		$number = rtrim($number,'0');
		$number = rtrim($number,'.');
	}
	$number = str_replace(',','',$number);
	
	return $number;
}

/* param，多个参数使用英文逗号分隔 */
function removeUrlParam($url,$param){
	if(substr($url,0,7) != 'http://' && substr($url,0,8) != 'https://'){
		$url = 'http://'.$url;
	}
	
	$parts = parse_url($url);
	
	if($parts['query']){
		parse_str($parts['query'],$queryArr);
	}else{
		return $url;
	}

	$paramArr = explode(',',$param);
	
	$newQueryArr = array();
	foreach($queryArr as $queryKey=>$queryValue){
		if(!in_array($queryKey,$paramArr)){
			$newQueryArr[$queryKey] = $queryValue;
		}
	}

	$url =  $parts['scheme'].'://'.$parts['host'].($parts['port'] ? ':'.$parts['port'] : '').$parts['path'].'?'.http_build_query($newQueryArr).'#'.$parts['fragment'];
	
	return $url;
}

function long2short_url($token,$url){
    $short_date['action']= 'long2short';
    $short_date['long_url']= $url;
    $short_date['access_token']= $token;

    $json =  json_encode($short_date, JSON_UNESCAPED_UNICODE);
    $return = httpRequest('https://api.weixin.qq.com/cgi-bin/shorturl?access_token='.$token,'post',$json);

    $short_url = json_decode($return[1],true);
    return $short_url['short_url'];
}
/**
 * 计算给定时间戳加一定时间加减月份
 * @param  [int] $timestamp [给定的时间戳]
 * @param  [int] $plusMonth [要与之相加减的月份]
 * @param  [int] $plus [true 为加 false 为减]
 * @return [string]            [获取的时间戳]
 */

function plus_time($timestamp = '', $plusMonth = 1, $plus = true) {
    if (!$timestamp) {
        $timestamp = time();
    }
    // 获取需要计算的年月份
    $time = date( "Y-m", $timestamp);
    // 获取需要计算的时间的日期
    $day = date( "d", $timestamp);
    $endDay = date( "d", strtotime(" +1 month -1 hour", $time));
    // 计算月份
    if ($plus) {
        $plusTime = date( "Y-m", strtotime( $time . " +" . $plusMonth . " month"));
    } else {
        $plusTime = date( "Y-m", strtotime( $time . " -" . $plusMonth . " month"));
    }
    $plusday = date( "d", strtotime( $plusTime . " +1 month -1 hour"));

    if (intval($day) == intval($endDay)) {
        $date = $plusTime . '-' . $plusday;
    } elseif ((int)$day > (int)$plusday) {
        $date = $plusTime . '-' . $plusday;
    } else {
        $date = $plusTime . '-' . $day;
    }
    $date = strtotime($date);
    return $date;
}


/**
 * 计算给定时间戳的显示信息
 * @param  [int] $timestamp [给定的时间戳]
 * @param  [string] $str [显示的动作，可以为空]
 * @param  [bool] $showHour [是否显示小时分钟，默认为不显示]
 * @return [string]            [获取的时间显示]
 */
// 时间显示处理
function time_info($timestamp, $str = '', $showHour = false)
{
    if (!$timestamp) {
        $timestamp = time();
    }
    // 取今天的时间范围
    $now_start_time = strtotime(date('Y-m-d', time()) . " 00:00:00");
    $now_end_time = strtotime(date('Y-m-d', time()) . " 23:59:59");
    if (intval($timestamp) >= intval($now_start_time) && intval($timestamp) <= intval($now_start_time)) {
        $time_info = date('H:i', $timestamp) . $str;
        return $time_info;
    }
    // 取昨天的时间范围
    $yesterday_start_time = strtotime("-1 day", $now_start_time);
    $yesterday_end_time = strtotime("-1 day", $now_end_time);
    if (intval($timestamp) >= intval($yesterday_start_time) && intval($timestamp) <= intval($yesterday_end_time)) {
        $time_info = $showHour ? '昨天 '. date('H:i', $timestamp) . $str : '昨天' . $str;
        return $time_info;
    }
    // 取前天的时间范围
    $week_start_time = strtotime("-2 day", $now_start_time);
    $week_end_time = strtotime("-2 day", $now_end_time);
    if (intval($timestamp) >= intval($week_start_time) && intval($timestamp) <= intval($week_end_time)) {
        $weekarray = array("日", "一", "二", "三", "四", "五", "六");
        $w = date("w", $timestamp);
        $time_info = $showHour ? '周' . $weekarray[$w] . date('H:i', $timestamp) . $str : '周' . $weekarray[$w] . $str;
        return $time_info;
    }
    // 获取年份
    $now_year = date('Y');
    $year = date('Y', $timestamp);
    if (intval($now_year) == intval($year)) {
        $time_info = $showHour ? date('m月d日 H:i', $timestamp) . $str : date('m月d日', $timestamp) . $str;
    } else {
        $time_info = $showHour ? date('Y年m月d日 H:i', $timestamp) . $str : date('Y年m月d日', $timestamp) . $str;
    }
    return $time_info;
}
//获取微信头像到本地
function put_file_from_url_content($url,$save_dir='',$filename='') {
    //根据url获取远程文件
    $curl =curl_init();
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_TIMEOUT,500);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
    
    curl_setopt($curl,CURLOPT_URL,$url);
    
    $res =curl_exec($curl); 
    curl_close($curl);  
    //把图片保存到指定目录下的指定文件
    $result = file_put_contents($save_dir.$filename,$res);
    if($result){
        return array(
            'file_name' =>$filename,
            'save_path'=>$save_dir.$filename,
            'error' =>0     
        );
    }else{
        return false;
    }
}

/** 
 * 人民币小写转大写 
 * 
 * @param string $number 数值 
 * @param string $int_unit 币种单位，默认"元"，有的需求可能为"圆" 
 * @param bool $is_round 是否对小数进行四舍五入 
 * @param bool $is_extra_zero 是否对整数部分以0结尾，小数存在的数字附加0,比如1960.30， 
 *             有的系统要求输出"壹仟玖佰陆拾元零叁角"，实际上"壹仟玖佰陆拾元叁角"也是对的 
 * @return string 
 */ 
function cny($number = 0, $int_unit = '圆', $is_round = TRUE, $is_extra_zero = FALSE) 
{ 
    // 将数字切分成两段 
    $parts = explode('.', $number, 2); 
    $int = isset($parts[0]) ? strval($parts[0]) : '0'; 
    $dec = isset($parts[1]) ? strval($parts[1]) : ''; 
 
    // 如果小数点后多于2位，不四舍五入就直接截，否则就处理 
    $dec_len = strlen($dec); 
    if (isset($parts[1]) && $dec_len > 2) 
    { 
        $dec = $is_round 
                ? substr(strrchr(strval(round(floatval("0.".$dec), 2)), '.'), 1) 
                : substr($parts[1], 0, 2); 
    } 
 
    // 当number为0.001时，小数点后的金额为0元 
    if(empty($int) && empty($dec)) 
    { 
        return '零'; 
    } 
 
    // 定义 
    $chs = array('0','壹','贰','叁','肆','伍','陆','柒','捌','玖'); 
    $uni = array('','拾','佰','仟'); 
    $dec_uni = array('角', '分'); 
    $exp = array('', '万'); 
    $res = ''; 
 
    // 整数部分从右向左找 
    for($i = strlen($int) - 1, $k = 0; $i >= 0; $k++) 
    { 
        $str = ''; 
        // 按照中文读写习惯，每4个字为一段进行转化，i一直在减 
        for($j = 0; $j < 4 && $i >= 0; $j++, $i--) 
        { 
            $u = $int{$i} > 0 ? $uni[$j] : ''; // 非0的数字后面添加单位 
            $str = $chs[$int{$i}] . $u . $str; 
        } 
        //echo $str."|".($k - 2)."<br>"; 
        $str = rtrim($str, '0');// 去掉末尾的0 
        $str = preg_replace("/0+/", "零", $str); // 替换多个连续的0 
        if(!isset($exp[$k])) 
        { 
            $exp[$k] = $exp[$k - 2] . '亿'; // 构建单位 
        } 
        $u2 = $str != '' ? $exp[$k] : ''; 
        $res = $str . $u2 . $res; 
    } 
 
    // 如果小数部分处理完之后是00，需要处理下 
    $dec = rtrim($dec, '0'); 
 
    // 小数部分从左向右找 
    if(!empty($dec)) 
    { 
        if ($res) {
            $res .= $int_unit; 
        }
        // 是否要在整数部分以0结尾的数字后附加0，有的系统有这要求 
        if ($is_extra_zero) 
        { 
            if (substr($int, -1) === '0') 
            { 
                $res.= '零'; 
            } 
        } 
 
        for($i = 0, $cnt = strlen($dec); $i < $cnt; $i++) 
        { 
            if ($dec{$i} > 0 ) {
                $u = $dec{$i} > 0 ? $dec_uni[$i] : ''; // 非0的数字后面添加单位 
                $res .= $chs[$dec{$i}] . $u; 
            }
        } 
        $res = rtrim($res, '0');// 去掉末尾的0 
        $res = preg_replace("/0+/", "零", $res); // 替换多个连续的0 
    } 
    else 
    { 
        if ($res) {
            $res .= $int_unit . '整'; 
        }else{

            $res = '零'.$int_unit.'整'; 
        }
    }
    return $res; 
} 

/**
 * RGB转 十六进制
 * @param $rgb RGB颜色的字符串 如：rgb(255,255,255);
 * @return string 十六进制颜色值 如：#FFFFFF
 */
function RGBToHex($rgb){
    $regexp = "/^rgb\(([0-9]{0,3})\,\s*([0-9]{0,3})\,\s*([0-9]{0,3})\)/";
    $re = preg_match($regexp, $rgb, $match);
    $re = array_shift($match);
    $hexColor = "#";
    $hex = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
    for ($i = 0; $i < 3; $i++) {
        $r = null;
        $c = $match[$i];
        $hexAr = array();
        while ($c > 16) {
            $r = $c % 16;
            $c = ($c / 16) >> 0;
            array_push($hexAr, $hex[$r]);
        }
        array_push($hexAr, $hex[$c]);
        $ret = array_reverse($hexAr);
        $item = implode('', $ret);
        $item = str_pad($item, 2, '0', STR_PAD_LEFT);
        $hexColor .= $item;
    }
    return $hexColor;
}
function safeValue($value){
	$value = htmlspecialchars($value);
	$value = str_replace('if(','',$value);
	$value = str_replace('sleep(','',$value);
	return $value;
}
?>