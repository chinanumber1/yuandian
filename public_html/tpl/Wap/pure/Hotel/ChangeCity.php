<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>选择城市</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/changecity.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">var indexUrl="{pigcms{:U('Home/index')}";var cityTopDomain=".{pigcms{$config.many_city_top_domain}";</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/SelectChar.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/changecity.js?210" charset="utf-8"></script>
	</head>
	<body>
		<header <if condition="$config['many_city']">class="hasManyCity"</if>>当前城市-{pigcms{$config.now_select_city.area_name}</header>
		<div id="container">
			<div id="scroller">
				<!--section class="searchBar <if condition="!$is_wexin_browser">wap</if>">
					<div class="searchBox">
						<form id="search-form" action="{pigcms{:U('Changecity/index')}" method="post">
							<input type="search" id="keyword" name="w" placeholder="请输入城市中文名称" autocomplete="off"/>
						</form>
					</div>
					<div class="voiceBtn"></div>
				</section-->
				<if condition="$now_city">
					<section class="hotBox">
						<div class="title">已定位城市</div>
						<ul class="hotKeyUl">
							<li><a class="city_location" data-city_url="{pigcms{$now_city.area_url}">{pigcms{$now_city.area_name}</a></li>
						</ul>
					</section>
				</if>
				<section class="hotBox" id="historyCityList" style="display:none;">
					<div class="title">最近访问的城市</div>
					<ul class="hotKeyUl"></ul>
				</section>
				<section class="hotBox">
					<div class="title">热门城市</div>
					<ul class="hotKeyUl">
						<volist name="hot_city" id="vo">
							<li><a class="city_location" data-city_url="{pigcms{$vo.area_url}">{pigcms{$vo.area_name}</a></li>
						</volist>
					</ul>
				</section>
				<section class="citylistBox">
					<dl>
						<volist name="all_city" id="vo">
							<dt id="city_{pigcms{$key}" class="cityKey" data-city_key="{pigcms{$key}">{pigcms{$key}</dt>
							<volist name="vo" id="voo">
								<dd class="city_location" data-city_url="{pigcms{$voo.area_url}">{pigcms{$voo.area_name}</dd>
							</volist>
						</volist>
					</dl>
				</section>
			</div>
		</div>
		{pigcms{$shareScript}
		<div id="selectCharBox"></div>
	</body>
</html>