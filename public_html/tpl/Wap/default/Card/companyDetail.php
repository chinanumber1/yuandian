<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{pigcms{$thisCompany.name}</title>
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="{pigcms{$static_path}card/style/style.css" rel="stylesheet" type="text/css">
<script src="/static/js/jquery.min.js" type="text/javascript"></script>
</head>

<body id="cardunion" class="mode_webapp2">
<div class="qiandaobanner"><a href="javascript:history.go(-1);"><img src="<if condition="$thisCard.contact neq ''">{pigcms{$thisCard.contact}<else/>/static/images/cart_info/addr.jpg</if>" ></a> </div>
<volist id="thisCompany" name="companies">
<div class="cardexplain">
<ul class="round">
<li class="userinfo"><a href="/wap.php?g=Wap&c=Card&a=companyIntro&token={pigcms{$token}&companyid={pigcms{$thisCompany.store_id}"><span><if condition="$thisCompany.name neq ''">{pigcms{$thisCompany.name}<else/>商家未设置</if></span></a></li>
<li class="tel"><a href="tel:{pigcms{$thisCompany.phone}"><span><if condition="$thisCompany.phone neq ''">{pigcms{$thisCompany.phone}<else/>商家未设置电话</if></span></a></li>
<li class="address"><a href="javascript:;"><span><if condition="$thisCompany.adress neq ''">{pigcms{$thisCompany.adress}<else/>商家未设置地址</if></span></a></li>
<li class="detail"><a href="/wap.php?g=Wap&c=Card&a=companyIntro&token={pigcms{$token}&companyid={pigcms{$thisCompany.store_id}"><span>查看介绍</span></a></li>
</ul>
</div>
</volist>
<include file="Card:bottom"/>
<include file="Card:share"/>
</body>
</html>
