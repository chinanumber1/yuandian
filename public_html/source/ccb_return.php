<?php
header("Content-type: text/html; charset=utf-8");

define('APP_NAME', 'cms');	//项目名称
define('APP_PATH','../cms/');	//项目目录
define('CONF_PATH','../conf/');	//配置文件地址
define('RUNTIME_PATH','../runtime/');	//缓存文件地址
define('TMPL_PATH','../tpl/');	//模板目录
define('APP_DEBUG',true);	//开启DEBUG
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));

$_GET['g'] = 'Wap';
$_GET['c'] = 'Pay';
$_GET['a'] = 'ccb_return';
$_GET['pay_type'] = 'alipay';
$runtime = '~wap_ccb_notice_runtime.php';
define('RUNTIME_FILE',RUNTIME_PATH.$runtime);
if(!APP_DEBUG && is_file(RUNTIME_FILE)){
    require RUNTIME_FILE;
}else{
    define('THINK_PATH', dirname(__FILE__).'/../core/');
    require THINK_PATH.'Common/runtime.php';
}
?>