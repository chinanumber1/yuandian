<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>身边{pigcms{$config.shop_alias_name}-选择位置 | {pigcms{$config.site_name}</title>
    <!--[if IE 6]>
		<script src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a-min.v86c6ab94.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
		<script src="{pigcms{$static_path}js/html5shiv.min-min.v01cbd8f0.js"></script>
    <![endif]-->
    
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/list.css"/>

<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/around.v2be69a85.css" />
	<script type="text/javascript">
	 var shop_alias_name = "{pigcms{$config.shop_alias_name}";
	</script>
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
<script src="{pigcms{$static_path}js/common.js"></script>
<script src="{pigcms{$static_path}js/list.js"></script>
<script src="{pigcms{$static_path}js/shop_around_map.js"></script>
<style>
#around-map .infowin-box{margin-top:0px;}
</style>
</head>
<body id="index" style="position:static;">
<include file="Public:header_top"/>
	<div id="doc" class="body">
		<div id="bdw" class="bdw">
			<div id="bd" class="cf">
				<h2 style="font-size:18px;margin:20px 0;">身边{pigcms{$config.shop_alias_name}</h2>
				<div class="pg-around-position">
					<div class="bd">
						<p class="location-label">我的位置：</p>
						<p class="mobile-link">
							<span class="F-glob F-glob-phone mobile-icon"></span>
							访问 <a href="{pigcms{$config.site_url}/topic/weixin.html" target="_blank">微信版</a>，随时随地查看身边{pigcms{$config.shop_alias_name}
						</p>
						<p class="locate-map" id="locate-map">您可以点击地图直接定位</p>
						<div class="left-box">
							<form name="aroundForm" id="aroundForm">
								<div class="search cf">
									<input type="text" class="s-text" name="q" id="aroundQ" placeholder="请输入收货地址" value="" autocomplete="off" />
									<input type="submit" class="s-submit" value="定位" hidefocus="true"/>
								</div>
							</form>
							<div id="result-panel" class="result-panel"></div>
						</div>
						<div id="around-map"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<include file="Public:footer"/>
	</body>
</html>
