<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>扫码支付</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<style>
		.imgContainer{width:60%;margin:100px auto 0;}
		.imgContainer img{width:100%;max-width:300px;}
		.tips{
			margin:20px 10% 0;
			text-align:center;
		}
	</style>
</head>
<body>
	<div class="imgContainer">
		<img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_own_qrcode&qrCon={pigcms{:urlencode($qrcode)}"/>
	</div>
	<div class="tips">请长按上方二维码识别进行支付</div>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
	<!--script>layer.open({type:2,content:'支付跳转中,请稍等',shadeClose:false});</script-->
{pigcms{$hideScript}
</body>
</html>