<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>登录 - {pigcms{$config.site_name}</title>
	<meta name="description" content="{pigcms{$config.seo_description}"/>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name='apple-touch-fullscreen' content='yes'/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta name="format-detection" content="address=no"/>

    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/index_wap.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
	<style>
		#login{margin: 0.5rem 0.2rem;}
		.btn-wrapper{margin:.28rem 0;}
		dl.list{border-bottom:0;border:1px solid #ddd8ce;}
		dl.list:first-child{border-top:1px solid #ddd8ce;}
		dl.list dd dl{padding-right:0.2rem;}
		dl.list dd dl>.dd-padding, dl.list dd dl dd>.react, dl.list dd dl>dt{padding-right:0;}
	    .nav{text-align: center;}
	    .subline{margin:.28rem .2rem;}
	    .subline li{display:inline-block;}
	    .captcha img{margin-left:.2rem;}
	    .captcha .btn{margin-top:-.15rem;margin-bottom:-.15rem;margin-left:.2rem;}
	</style>
</head>
<body id="index" data-com="pagecommon">
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_path}js/common_wap.js"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
	<script>
		layer.open({type: 2});
		$(function(){
			var json = {
				url: '{pigcms{$referer}'
			};
			json = JSON.stringify(json);
			json = encodeURI(json);
			var iFrame;
			iFrame = document.createElement("iframe");
			iFrame.setAttribute("src",'app://login?' + json);
			iFrame.setAttribute("style","display:none;");
			iFrame.setAttribute("width","0px");
			iFrame.setAttribute("height","0px");
			iFrame.setAttribute("frameborder","0");
			document.body.appendChild(iFrame);
			iFrame.parentNode.removeChild(iFrame);
			iFrame = null;
			setTimeout(function(){
				history.go(-1);
			},200);
		});
		
	</script>
	<include file="Public:footer"/>
	{pigcms{$hideScript}
</body>
</html>