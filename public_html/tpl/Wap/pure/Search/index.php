<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>搜索</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/search.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript">var searchUrl = "{pigcms{:U('Search/'.$type)}";</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/search.js?210" charset="utf-8"></script>
	</head>
	<body>
		<div id="container">
			<div id="scroller">
				<section class="searchBar <if condition="!$is_wexin_browser">wap</if>">
					<div class="searchBox">
						<form id="search-form" action="{pigcms{$config.site_url}/wap.php?g=Wap&c=Search&a={pigcms{$type}" method="post">
							<input type="search" id="keyword" name="w" placeholder="请输入搜索词" autocomplete="off"/>
						</form>
					</div>
					<div class="voiceBtn"></div>
				</section>
				<php>$no_footer = true;</php>
				<include file="Public:footer"/>
				<section class="hotBox">
					<div class="title">热门搜索</div>
					<ul class="hotKeyUl">
						<volist name="search_hot_list" id="vo">
							<li><a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a></li>
						</volist>
					</ul>
				</section>
				<section class="historyBox" style="display:none;">
					<div class="title">搜索历史</div>
					<ul>
						<li class="clear">清除搜索记录</li>
					</ul>
				</section>
			</div>
		</div>
		{pigcms{$hideScript}
	</body>
</html>