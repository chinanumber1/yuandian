<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>支付提示</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}layer/need/layer.css" rel="stylesheet"/>
</head>
<body>
	<!--header  class="navbar">
        <h1 class="nav-header">支付提示</h1>
    </header-->
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
	<if condition="$_GET['type']=='error'">
		<script>layer.open({title:'支付提示',content:'支付失败',end:function(){location.href='{pigcms{$_GET.redirctUrl}';}});</script>
	<else/>
		<script>layer.open({type:2,content:'支付成功',shadeClose:false});</script>
		<script>window.location.href = '{pigcms{$_GET.redirctUrl}';</script>
	</if>
{pigcms{$hideScript}
<div style="display:none;">{pigcms{$config.wap_site_footer}</div>
</body>
</html>