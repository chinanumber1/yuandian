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
		#wrapper2 { height:41px;}
		.slide_tabs{position:relative; overflow:hidden; background-color:#fafafa;}
		.slide_tabs ul{}
		.slide_tabs li{max-width:4em; padding:0 10px; height:40px; line-height:40px;border-bottom:1px solid #eee; overflow:hidden; float:left;text-align:center;overflow:hidden;}
		.slide_tabs li.current{border-bottom:1px solid #06c1ae; color:#06c1ae;}
		.slide_tabs li.current a { color:#06c1ae;}
		.slide_tabs_wrap .more{right:0;background:url('{pigcms{$static_path}portal/images/nav2015BG.png') repeat-y 0 0;position:absolute; z-index:1;top:0;width:50px;height:40px;}
		.slide_tabs_wrap .more span,.slide_tabs_wrap .more span:after { position:absolute; top:16px; left:26px; display:inline-block; border-color:#adadad transparent transparent transparent; border-width:8px; border-style:solid; transition:transform .3s ease; -webkit-transition:-webkit-transform .3s ease; transform-origin:50% 25% 0; -webkit-transform-origin:50% 25% 0;}
		.slide_tabs_wrap .more span:after { position:absolute; top:-10px; left:-8px; content:' '; border-color:#fafafa transparent transparent transparent;}
		.open .more span { transform:rotate(180deg); -webkit-transform:rotate(180deg);}
		#scroller2 {-webkit-tap-highlight-color: rgba(0,0,0,0);	width: 100%;-webkit-transform: translateZ(0);-moz-transform: translateZ(0);	-ms-transform: translateZ(0);-o-transform: translateZ(0);transform: translateZ(0);-webkit-touch-callout: none;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;-webkit-text-size-adjust: none;-moz-text-size-adjust: none;-ms-text-size-adjust: none;-o-text-size-adjust: none;text-size-adjust: none;}
		.slide_tabs_wrap { position:relative; z-index:3;}
		.slide_tabs_wrap .node2 { display:none; position:absolute; left:0; top:0; right:0; background-color:#fafafa; box-shadow:0 2px 5px rgba(0,0,0,.2);}
		.open .node2 { display:block;}
		.slide_tabs_wrap .node2 .hd { border-bottom:1px solid #fff;}
		.slide_tabs_wrap .node2 .hd .tit { display:inline-block; padding:7px 3px; color:#fb9031; border-bottom:1px solid #fb9031;}
		.slide_tabs_wrap .node2 ul { padding:10px 0;}
		.slide_tabs_wrap .node2 li { float:left; width:25%; padding:0 5px; -webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box; margin:5px 0;}
		.slide_tabs_wrap .node2 li a { display:inline-block; vertical-align:top; border:1px solid #ddd; border-radius:15px; font-size:12px; line-height:30px; height:30px; overflow:hidden; padding:0 10px; width:4em; text-align:center;}
		.slide_tabs_wrap .node2 li.current a { color:#06c1ae;border:1px solid #06c1ae;}

	</style>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
	<script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
	<script src="{pigcms{$static_path}portal/js/wap_common_2015.js"></script>
	<!--必须在现有的script外-->

</head>
<body style="min-height: 640px;" class="<if condition="$like_app_browser">like_app</if>">
	<div id="pageMain">
		<div class="header">
			<a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
			<!-- <div class="search" id="search_ico" onclick="showNewPage(&#39;搜索&#39;,searchHtml,newPageSearch);" style="">搜索</div> -->
			<a href="{pigcms{:U('Wap/My/index')}" class="my <if condition="$user_session['uid']">ico_ok</if>" id="login_ico" style="display: none;">我的</a>
			<div class="type" id="nav_ico">导航</div>
			<span id="ipageTitle" style="">本地资讯</span>
			<include file="Portal:top_nav"/>
		</div>

		

		<div class="nav_index_bottom" style="overflow:visible;">
			<ul>
				<li class="current">
					<a href="{pigcms{:U('Portal/index')}">
						<span class="home"></span>
						首页
					</a>
				</li>
				<li>
					<a href="{pigcms{:U('Wap/My/index')}">
						<span class="mine"></span>
						我的
					</a>
				</li>
			</ul>
		</div>
		<div class="content news_index">
			<div class="slide_tabs_wrap">
				<div class="slide_tabs" id="wrapper2">
					<ul id="scroller2" style="width: 990px; transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">

						<li class="<if condition="$_GET['cid'] eq ''">current</if> item">
							<a href="{pigcms{:U('Portal/article')}">全部分类</a>
						</li>

						<volist name="cate_list" id="vo">
							<li class="<if condition="$_GET['cid'] eq $vo['cid']">current</if> item">
								<a href="{pigcms{:U('Portal/article',array('cid'=>$vo['cid']))}">{pigcms{$vo.cat_name}</a>
							</li>
						</volist>
					</ul>
				</div>
				<div class="node2">
					<div class="hd">
						<span class="tit">全部分类</span>
					</div>
					<ul id="cloneNav" class="clearfix">
					
					</ul>
				</div>
				<div class="more" id="iscrollto">
					<span></span>
				</div>
			</div>
			<div id="wrapper" style="top:86px;bottom:51px;">
				<div id="scroller" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
					<div id="slide" class="clearfix" style="width: 360px;">
						<div id="content" style="width: 1440px; transform: translate3d(-360px, 0px, 0px) scale(1);">
							<volist name="hot_img_news" id="vo">
								<div class="cell" style="width: 360px;">
									<a href="{pigcms{:U('Portal/article_detail',array('aid'=>$vo['aid']))}">
										<img src="{pigcms{$vo.thumb}" style=" height: 270px;" alt=""></a>
									<span class="title">{pigcms{$vo.title}</span>
								</div>
							</volist>
						</div>
						<ul id="indicator" class="text_right">
							<volist name="hot_img_news" id="kvo">
								<li class="">{pigcms{$key}</li>
							</volist>
						</ul>
					</div>
					<span class="prev" id="slide_prev" style="display:none">上一张</span>
					<span class="next" id="slide_next" style="display:none">下一张</span>
					<ul id="innerrow" class="list_normal list_news">
						<volist name="article_list" id="vo">
							<li class="haspic1" onclick="location.href='{pigcms{:U('Portal/article_detail',array('aid'=>$vo['aid']))}'">
								<a href="javascript:;" class="link">
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
var siteUrl = '';

(function($){
	var w_w = $(window).width();
	$('#foot_link').hide();
	var list = $('#content').find('.cell');
	if(list.length > 0){
		var txt = '';
		list.each(function(i){
			if(i === 0){
				txt += '<li class="active">1</li>';
			}else{
				txt += '<li>'+(i+1)+'</li>';
			}
		});
		$('#slide').show();
		$('#indicator').html(txt);
		window['myScroll1'] = new C_Scroll({container:'slide',content:'content',ct:'indicator',size:w_w,intervalTime:5000,lazyIMG:!!0});
	}
	var star_nav = $('#scroller2');
	star_nav.css('width',(90*star_nav.find('li').length)+'px'); 
	window['myScroll2'] = new IScroll('#wrapper2', {
		scrollX: true,
		scrollY: false,
		click:true,
		keyBindings: true
	});
	$('#iscrollto').click(function(e){
		e.preventDefault();
		if(!$(this).parent().hasClass('open')){
			$(this).parent().addClass('open');
		}else{
			$(this).parent().removeClass('open');
		}
	});
	$('#cloneNav').html(star_nav.html());
})(jQuery);


var page = 1
$(window).scroll(function () {
	var scrollTop = $(this).scrollTop();
	var scrollHeight = $(document).height();
	var windowHeight = $(this).height();
	if (scrollTop + windowHeight == scrollHeight) {
		var cid = $("#cid").val();
		var articleListUrl = "{pigcms{:U('Portal/ajax_article_list')}";
		$.post(articleListUrl,{'page':page,'cid':cid},function(data){
			if(data.error == 1){
				page = page+1;
				$("#innerrow").append(data.html);
			}else{
				$("#over_list").html('<li class="noMore">没有更多了</li>');
			}
		},'json');
	}
});

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
{pigcms{$shareScript}
<script type="text/javascript">
	window.shareData = {
		"moduleName":"Home",
		"moduleID":"0",
		"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Portal/article')}",
		"tTitle": "文章资讯栏目首页",
		"tContent": "文章资讯栏目首页-{pigcms{$config.site_name}"
	};
</script>
</body>
</html>