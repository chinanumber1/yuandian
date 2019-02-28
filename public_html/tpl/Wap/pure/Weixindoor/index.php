<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8" />
	<title>微信门禁</title>
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name='apple-touch-fullscreen' content='yes'/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta name="format-detection" content="address=no"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/sketch.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/stopExecutionOnTimeout.js" charset="utf-8"></script>
	<style>
		*{margin:0;border:0;padding:0;}
		body{background-color: rgb(31, 34, 41);}
		.scanDeviceBtn{
			position:absolute;width:120px;height:120px;background:#06c1ae;border-radius:100%;top:50%;left:50%;margin-top:-60px;
			margin-left:-60px;
			text-align:center;color:white;line-height:120px;
		}
		.scanDeviceTip{
			padding:10px 20px;
			background:#06c1ae;
			color:white;
			position:absolute;
			width:100px;
			left:50%;
			margin-left:-70px;
			top:80%;
			text-align:center;
			border-radius:5px;
		}
		.scanWechatQrcode{
			position:absolute;
			top:0px;
			left:0px;
			width:100%;
			height:100%;
			background: rgba(255,255,255,0.3);
			display:none;
		}
		.scanWechatCon{
			background:white;
			width:220px;
			margin:0 auto;
			margin-top:200px;
			text-align:center;
			padding-bottom:20px;
		}
		.scanWechatCon img{
			width:220px;
			height:220px;
		}
		.scanWechatCon div{
			margin-top:10px;
		}
	</style>
</head>
<body>
<div class="scanDeviceBtn" id="scanDeviceBtn">扫描门禁</div>
<div class="scanDeviceTip" id="scanDeviceTip" style="display:none;">扫描中</div>
<div class="scanWechatQrcode" id="scanWechatQrcode">
	<div class="scanWechatCon">
		<img src="{pigcms{$config.wechat_qrcode}"/>
		<div>长按二维码识别进入公众号</div>
	</div>
</div>
{pigcms{$shareScript}
<script>
	window.shareData = {
		"moduleName":"Weixindoor",
		"moduleID":"0",
		"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Weixindoor/index')}",
		"tTitle": "微信门禁 - {pigcms{$config.site_name}",
		"tContent": "{pigcms{$config.seo_description}"
	};
	// var deviceId = 'F36867E3';
	// alert(JSON.stringify(res));
	
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/weixindoor.js" charset="utf-8"></script>
</body>
</html>