<?php
$plan_documentRoot = dirname(__file__).'/';	//当前文件的目录

$plan_timeDocumentRoot = $plan_documentRoot.'time/';		//时间文件存放目录

$plan_postTimeFile 	= $plan_timeDocumentRoot.'post.time';	//执行任务的间隔时间

$domainArr = explode('.',$_SERVER['HTTP_HOST']);
$count = count($domainArr);
if(str_replace(array('.gov.cn','.com.cn','.weihubao.com','.dazhongbanben.com'),'',$_SERVER['HTTP_HOST']) == $_SERVER['HTTP_HOST']){
	$top_domain = $domainArr[$count-2].'.'.$domainArr[$count-1];
}else{
	$top_domain = $domainArr[$count-3].'.'.$domainArr[$count-2].'.'.$domainArr[$count-1];
}
$top_domain = strtolower($top_domain);

$plan_processTimeFile  = $plan_timeDocumentRoot.$top_domain.'process.time';	//线程当前执行时间

$plan_stopTheadFile  = $plan_documentRoot.$top_domain.'stop.thead';

$plan_selfUrl  = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/';

// $plan_postTime = gethostbyname($_SERVER['HTTP_HOST']) == '127.0.0.1' ? 200 : 600;
$plan_postTime = 600;

if($top_domain != 'group.com' && (!file_exists($plan_processTimeFile) || file_get_contents($plan_processTimeFile) < time() - 20)){
	$plan_theadSafe = md5(mt_rand(100000,999999).uniqid().mt_rand(100000,999999));
	
	file_put_contents($plan_processTimeFile,time());
	
	file_put_contents($plan_documentRoot.$top_domain.'md5.php','<?php return \''.$plan_theadSafe.'\';?>');
	
	// echo $plan_selfUrl.'?c=Plan&pigcms_process_theadSafe='.$plan_theadSafe;exit;
	
	plan_curlGet($plan_selfUrl.'index.php?c=Plan&pigcms_process_theadSafe='.$plan_theadSafe,$plan_postTime);
	return true;
}else{
	return true;
}

function plan_curlGet($url,$timeout){
	$ch = curl_init($url);  
	curl_setopt($ch, CURLOPT_HEADER, 0);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 	//不需要等待返回结果，
	curl_setopt($ch, CURLOPT_NOSIGNAL, true);
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout);
	curl_exec($ch);  
	curl_close($ch);
}