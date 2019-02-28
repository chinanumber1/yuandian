<!doctype html>
<html>
<head lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">
<title>{pigcms{$now_merchant.name}的会员{pigcms{$config['score_name']}</title>
<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/style_bai.css"/>
</head>
<body>
	<div class="myde">
		<span class="left">我的{pigcms{$config['score_name']}</span><span class="myshu" id="point">{pigcms{$card_info.card_score}分</span>
	</div>
	<div class="myxq">
		<div class="myxq_mx">
		{pigcms{$card_info.score_des}
		</div>
	</div>
</body>
</html>