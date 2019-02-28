<!doctype html>
<html>
<head lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">
<title>{pigcms{$now_merchant.name}的会员特权</title>
<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/style_bai.css"/>
</head>
<body>
	<div class="myde_tq">
		<img src="{pigcms{$static_path}my_card/images/zs.png" width="92" height="92"/>
	</div>
	<div class="myxq">
		<p class="myxq_title">会员特权</p>
		<div class="myxq_mx" id="member_right">								
		{pigcms{$card_info.info}
		</div>
	</div>
</body>
</html>