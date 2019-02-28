<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>实体卡条形码/二维码</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">

    <style>

		.main {position: absolute;text-align:center;display: table-cell;vertical-align:middle;*display: block;*font-size: 175px;*font-family:Arial;border: 1px solid #eee;top: 0;bottom: 0;left: 0;right: 0;margin: auto;height: 380px;width: 300px;background-color:#fff;}
		.main img{ margin-top:8px;vertical-align:middle;}
		.mydiv {height: 100%;background-color:#f0efed;position: absolute;width: 100%;}
	</style>
</head>
<body style="margin:0px">
<div class="mydiv">
	
	<div class="main"><h4>我的实体卡付款码</h4>
		<empty name="cardid">
	   
		用户没有绑定实体卡
		<else />
			<img src="{pigcms{:U('My/cardbarcode')}"/>
			<img src="{pigcms{:U('My/cardqrcode')}"/>
		</empty>
	</div>
</div>
</body>
</html>