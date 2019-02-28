<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>关键字搜索-文章资讯栏目首页</title>
	<meta name="keywords" content="本地资讯栏目关键词,关键词,关键词,关键词,关键词,关键词,关键词,关键词">
	<meta name="description" content="本地资讯栏目介绍">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
	<script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
	<script src="{pigcms{$static_path}portal/js/wap_common_2015.js"></script>
	<!--必须在现有的script外-->
	<script>
		var isapp ="0";//在现有的js内:是否app平台
		var YDB;
		if(isapp === '1'){
			YDB = new YDBOBJ();
		}
	</script>
</head>
<body class="<if condition="$like_app_browser">like_app</if>">
	<div id="pageMain">
		<div class="header">
			<a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
			<div class="search" id="search_ico" onclick="showNewPage(&#39;搜索&#39;,searchHtml,newPageSearch);" style="">搜索</div>
			<a href="{pigcms{:U('Wap/My/index')}" class="my <if condition="$user_session['uid']">ico_ok</if>" id="login_ico" style="display: none;">我的</a>
			<div class="type" id="nav_ico">导航</div>
			<span id="ipageTitle" style="">本地资讯</span>
			<include file="Portal:top_nav"/>
		</div>

		<div class="content">
			<ul class="list_normal list_news">

				<volist name="article_list" id="vo">
					<li class="haspic1">
						<a href="{pigcms{:U('Portal/article_detail',array('aid'=>$vo['aid']))}" class="link">
							<p class="img">
								<if condition="$vo['thumb']">
							   		<img src="{pigcms{$vo.thumb}">
							   	<else/>
							   		<img src="{pigcms{$static_path}public/images/livelistnopic.gif">
							   	</if>
							</p>
							<p class="tit">{pigcms{$vo.title}</p>
							<p class="txt clearfix">
								<span class="left">{pigcms{$vo.dateline|date="m-d H:i",###}</span>
								<span class="right">人气：{pigcms{$vo.PV}</span>
							</p>
						</a>
					</li>
				</volist>
				<li class="line">&nbsp;</li>
			</ul>
			<div class="pageNav2">
				{pigcms{$pagebar}
			</div>
		</div>
		<p style="display:none;"></p>
	</div>
	<div class="windowIframe" id="windowIframe" data-loaded="0" style="min-height: 640px; display: none;">
		<div class="header">
			<a href="javascript:;" class="back close">返回</a>
			<span id="windowIframeTitle">搜索</span>
		</div>
		<div class="body" id="windowIframeBody">
			<div class="searchbar2">
				<form id="myform" action="" method="get">
					<input type="hidden" name="g" value="Wap">
					<input type="hidden" name="c" value="Portal">
					<input type="hidden" name="a" value="search_list">
					<input type="text" name="v" id="meSleKey" class="s_ipt" value="" placeholder="输入关键字">
					<input type="submit" class="s_btn po_ab" value="搜索">
				</form>
			</div>
		</div>
	</div>
	<div id="l-map" style="display:none;"></div>
	<script src="{pigcms{$static_path}portal/js/wap_common.js"></script>
	<script>
		
		var searchHtml = '<div class="searchbar2">'+
			'<form id="myform" action="'+"{pigcms{:U('Portal/search_list')}"+'" method="get">'+
				'<input type="hidden" name="g" value="Wap" />'+
				'<input type="hidden" name="c" value="Portal" />'+
				'<input type="hidden" name="a" value="search_list" />'+
				'<input type="text" name="v" id="meSleKey" class="s_ipt" value="" placeholder="输入关键字" />'+
				'<input type="submit" class="s_btn po_ab" value="搜索">'+
			'</form></div>';
		function newPageSearch(){
		
		}

	</script>

</body>
</html>