<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <title>电脑版聊天登录 | {pigcms{$config.site_name}</title>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/impc.css"/>
	<script src="{pigcms{$static_public}js/jquery.min.js"></script>
	<script src="{pigcms{$static_path}js/impc.js"></script>
</head>
<body>
	<div class="login_box">
		<div class="qrcode">
			<img class="img" src="{pigcms{$static_path}images/impc_qrcode.jpg"/>
			<p class="sub_title">微信扫描二维码登录</p>
		</div>
		<div class="avatar">
			<img class="img" src=""/>
			<h4 class="sub_title">扫描成功</h4>
			<p class="tips"><span id="time">3</span>秒后跳转登录</p>
			<a href="javascript:;" class="action">返回扫码登录</a>
		</div>
	</div>
</body>
</html>