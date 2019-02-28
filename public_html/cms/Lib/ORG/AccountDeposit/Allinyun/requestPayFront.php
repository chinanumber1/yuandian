<?php
date_default_timezone_set('PRC');
require_once 'RSAUtil.php';

$sysid = "";              //商户号
$alias = "100000000002";  //密钥密码
$path = "";               //证书名称
$pwd = "";                //证书文件路径，用户指定

///////////////////
$bizUserId = $_REQUEST["bizUserId"];
$bizOrderNo = $_REQUEST["bizOrderNo"];
$consumerIp = $_REQUEST["consumerIp"];
$verificationCode = $_REQUEST["verificationCode"];

$param["bizUserId"] = $bizUserId;
$param["bizOrderNo"] = $bizOrderNo;
$param["consumerIp"] = $consumerIp;
$param["verificationCode"] = $verificationCode;

$req["service"] = "OrderService";
$req["method"] = "pay";
$req["param"] = $param;

$strRequest = json_encode($req);
$strRequest = str_replace("\r\n", "", $strRequest);

$timestamp = date("Y-m-d H:i:s", time());
		
$privateKey = RSAUtil::loadPrivateKey($alias, $path, $pwd);
$sign = RSAUtil::sign($privateKey, $sysid.$strRequest.$timestamp);

$map["sysid"] = $sysid;
$map["sign"] = $sign;
$map["timestamp"] = $timestamp;
$map["req"] = $strRequest;
$map["v"] = "1.0";

$urlParam = '';
foreach ($map as $key => $value){ 
	$urlParam .= $key.'='.urlencode($value).'&';
} 
$href = "http://122.227.225.142:23661/service/gateway/frontTrans.do?".$urlParam;  //soa请求地址
?>
<a href="<?php echo $href; ?>" style="margin-left: 50%; font-size: 36px">前台支付</a>