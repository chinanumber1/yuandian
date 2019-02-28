<?php
/*易宝不支持回调地址带有参数，故写于此。*/

header("Content-type: text/html; charset=utf-8");

$_GET['g'] = 'Wap';
$_GET['c'] = 'Pay';
$_GET['a'] = 'return_url';
$_GET['pay_type'] = 'yeepay';

define('APP_NAME', 'cms');	//项目名称
define('APP_PATH','../cms/');	//项目目录
define('CONF_PATH','../conf/');	//配置文件地址
define('RUNTIME_PATH','../runtime/');	//缓存文件地址
define('TMPL_PATH','../tpl/');	//模板目录
define('APP_DEBUG',true);	//开启DEBUG
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
$runtime = defined('MODE_NAME')?'~'.strtolower(MODE_NAME).'_runtime.php':'~runtime.php';
define('RUNTIME_FILE',RUNTIME_PATH.$runtime);

$runtime_file = RUNTIME_PATH.'~runtime.php';
if(!APP_DEBUG && is_file($runtime_file)) {
    require $runtime_file;
}else{
    define('THINK_PATH', dirname(__FILE__).'/../core/');
    require THINK_PATH.'Common/runtime.php';
}
?>