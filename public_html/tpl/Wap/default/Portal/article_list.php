<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>文章资讯栏目首页-{pigcms{$config.site_name}</title>
	<!-- UC默认竖屏 ，UC强制全屏 -->
	<meta name="full-screen" content="yes">
	<meta name="browsermode" content="application">
	<!-- QQ强制竖屏 QQ强制全屏 -->
	<meta name="x5-orientation" content="portrait">
	<meta name="x5-fullscreen" content="true">
	<meta name="x5-page-mode" content="app">
	<meta name="keywords" content="本地资讯栏目关键词,关键词,关键词,关键词,关键词,关键词,关键词,关键词">
	<meta name="description" content="本地资讯栏目介绍">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/news-mb.css">
	<!-- <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/news-scroll5.css"> -->
	<style>
	

	</style>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
	<script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
	<script src="{pigcms{$static_path}portal/js/wap_common_2015.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
	<!--必须在现有的script外-->

</head>
<section class="search searcht">
	<div class="searcht_n">
		<div class="cond">
			<span class="on">资讯</span>
			<div class="cond_list">
				<span class="sp">资讯</span>
				<span class="dp">贴吧</span>
			</div>
		</div>
		<input type="text" placeholder="输入关键字搜索" class="se_input" value="{pigcms{$_GET['keyword']}"  style="padding-left: 55px;"/>
		<input type="hidden" id="store_id" value="{pigcms{$store_id}" />
		<a href="javascript:void(0)" id="search">搜索</a>
	</div>
</section>
<body style="min-height: 640px;" class="<if condition="$like_app_browser">like_app</if>">
	<div id="pageMain">
		
		<div class="content news_index">
			<div id="wrapper" style="top:86px;bottom:51px;">
				<div id="scroller" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
					<ul id="innerrow" class="list_normal list_news">
						<if condition="$article_list">
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
						<else/>
							<p class="txt clearfix" style="text-align: center;margin-top: 20px;">
								暂未搜索到相关内容
							</p>
						</if>
					</ul>
					<ul id="over_list" class="list_normal list_news">
						
					</ul>
					<br/><br/>
				</div>
			</div>
		</div>
	</div>
<script src="{pigcms{$static_path}portal/js/wap_common.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}portal/js/jquery.cookie.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}portal/js/purl.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}portal/js/mustache.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}portal/js/iscroll-probe.js"></script>
<input type="hidden" id="cid" value="{pigcms{$_GET['cid']}">
<script>

var page = 1,search_type = 0;
$(window).scroll(function () {
	var scrollTop = $(this).scrollTop();
	var scrollHeight = $(document).height();
	var windowHeight = $(this).height();
	if (scrollTop + windowHeight == scrollHeight) {
		var cid = $("#cid").val();
		var keyword = '{pigcms{$_GET['keyword']}';
		var articleListUrl = "{pigcms{:U('Portal/ajax_article_list')}" + '&keyword='+keyword;
		$.post(articleListUrl,{'page':page},function(data){
			if(data.error == 1){
				page = page+1;
				$("#innerrow").append(data.html);
			}else{
				$("#over_list").html('<li class="noMore">没有更多了</li>');
			}
		},'json');
	}
});

//搜索
$(".cond span.on").click(function(){
	if($(".cond_list").is(":hidden")){
		$(".cond_list").show();
	}else{
		$(".cond_list").hide();
	}
});
$(".cond_list span").click(function(){
	if ($(this).attr('class') == 'sp') {
		search_type = 0;
	} else {
		search_type = 1;
	}
	$(".cond span.on").text($(this).text());
	$(".cond_list").hide();
});

$('#search').click(function(){
	var keyword = $('.se_input').val();
	if (!keyword) {
		layer.open({
			content: '请输入关键字'
			,skin: 'msg'
			,time: 2  
		});
		return false;
	}
	if (search_type==0) {
		window.location.href="{pigcms{:U('Portal/article_list')}" + '&keyword='+keyword;
	}else if(search_type==1){
		window.location.href="{pigcms{:U('Portal/tieba_list')}" + '&keyword='+keyword;
	}
	
});

</script>
{pigcms{$shareScript}
<script type="text/javascript">
	window.shareData = {
		"moduleName":"Home",
		"moduleID":"0",
		"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Portal/article_list')}",
		"tTitle": "文章资讯栏目首页",
		"tContent": "文章资讯栏目首页-{pigcms{$config.site_name}"
	};
</script>
</body>
</html>