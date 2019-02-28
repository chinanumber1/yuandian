<?php
date_default_timezone_set('PRC');
require_once 'RSAUtil.php';

$alias = "";   //密钥密码
$path = "";    //证书名称
$pwd = "";     //证书文件路径，用户指定

///////////////////
$sysid = $_REQUEST["sysid"];
$sign = $_REQUEST["sign"];
$timestamp = $_REQUEST["timestamp"];
$v = $_REQUEST["v"];
$rps = $_REQUEST["rps"];
echo "sysid=".$sysid.",<br>sign=".$sign.",<br>timestamp=".$timestamp.",<br>v=".$v.",<br>rps=".$rps.'<br>';
$publicKey = RSAUtil::loadPublicKey($alias, $path, $pwd);
$seccess = RSAUtil::verify($publicKey, $sysid.$rps.$timestamp, $sign);
if(!$seccess)
	throw new Exception("签名验证错误");
?>