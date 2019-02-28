<!doctype html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<title>{pigcms{$now_merchant.name}的会员资料</title>
	<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/style_bai.css"/>
</head>
<body>
   <form name="boundcard" action="" method="post"> 
	<div id="bigbox">
		<div class="jtxx">
			<p class="jtxx_l_c left">实体卡号：</p>
			<input class="jtxx_r_c xb left" type="text" id="cardid" name="cardid" value="{pigcms{$card_info.physical_id}" <if condition="$card_info['physical_id'] neq ''">disabled="disabled"</if>placeholder="">
		</div>
	</div>
	<if condition="$card_info.physical_id eq ''">
		<button class="btn" id="commitBtn">确定</button>
	</if>
	</form>
</body>
</html>