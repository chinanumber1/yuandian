<!doctype html>
<html>
<head>
	<include file="header"/>
</head>
<body>
	<div class="weui-pull-to-refresh__layer">
		<div class='weui-pull-to-refresh__arrow'></div>
		<div class='weui-pull-to-refresh__preloader'></div>
		<div class="down">下拉刷新</div>
		<div class="up">释放刷新</div>
		<div class="refresh">正在刷新</div>
	</div>
	<div class="page__bd">
		<header class="x_header bgcolor_11 cl  weui-flex f15" style="background:transparent!important;position:absolute">
			<a class="z x_logo" href=""><span style="margin:0 15px">{pigcms{$config.classify_title}</span></a>    
			<form class="z x_form" style="position: relative;" id="search" method="get" action="{pigcms{:U('search')}">
				<input type="hidden" name="c" value="Classifynew"/>
				<input type="hidden" name="a" value="search"/>
				<input name="keyword" class="x_logo_input" type="text" value="" placeholder="输入关键词" x-webkit-speech="" style="background: rgba(255,255,255,.8)"/>
				<button class="x_logo_search main_color" type="submit">搜索</button>
			</form>
		</header>
		<div class="x_header_fix" style="display:none"></div>
		<div class="swipe cl" data-speed="5000">
			<div class="swipe-wrap">
				<volist name="classify_index_ad" id="vo">
					<div><a href='{pigcms{$vo.url}'><img src='{pigcms{$vo.pic}'/></a></div>
				</volist>
			</div>
			<nav class="cl bullets bullets1">
				<ul class="position">
					<li class="current"></li>
				</ul>
			</nav>
		</div>
		<div class="weui-cells mt0 border_bottom before_none">
			<div class="weui-cell tl" style="white-space:nowrap">
				<div class="weui-cell__bd">
					<i class="iconfont icon-hot1 color-red"></i>
					<span class="ml3 f15">浏览：<em class="main_color">{pigcms{$classify_count.views_count_txt}</em></span>
					<span class="ml3 f15">发布：<em class="main_color">{pigcms{$classify_count.content_count_txt}</em></span>
					<span class="ml3 f15">分享：<em class="main_color">{pigcms{$classify_count.shares_count_txt}</em></span>
				</div>
				<div class="weui-cell__hd">
					<a class="f15 c9" href="{pigcms{:U('about')}">帮助</a>
				</div>
			</div>
		</div>
		<nav class=" nav-list cl swipe" style="background:url({pigcms{$static_path}classifynew/images/cjbg1.png); background-size:cover;background-color:#fff">
			<div class="swipe-wrap">
				<volist name="wap_classify_slider" id="vo">
					<div>
						<ul class="cl">
							<volist name="vo" id="voo">
								<li>
									<a href="{pigcms{$voo.url}">
										<span>
											<img src="{pigcms{$voo.pic}"/>
										</span>
										<em class="m-piclist-title">{pigcms{$voo.name}</em>
									</a>
								</li>
							</volist>
						</ul>
					</div>
				</volist>
			</div>
			<nav class="cl bullets bullets1">
				<ul class="position position1">
					<volist name="wap_classify_slider" id="vo">
						<if condition="$key eq 0">
							<li class="current"></li>
						<else/>
							<li></li>
						</if>
					</volist>
				</ul>
			</nav>
		</nav>
		<if condition="$classify_scrollmsg">
			<div class="weui-cells mt0 after_none">
				<div class="chip-row">
					<div class="toutiao">同城动态</div>
					<div class="toutiao-slider swiper-container" id="newsSlider">
						<ul class="swiper-wrapper">
							<volist name="classify_scrollmsg" id="vo">
								<li class="swiper-slide">
									<a href="{pigcms{:U('view',array('id'=>$vo['classify_id']))}">[{pigcms{$vo.time|date='m-d',###}]{pigcms{$vo.text}</a>
								</li>
							</volist>
						</ul>
					</div>
				</div>
			</div>
		</if>
		<div class="weui-cells fixbanner before_none">
			<div class="weui-navbar weui-banner nobg fixbanner_in">
				<a href="javascript:;" class="weui-navbar__item weui_bar__item_on ajaxcat" data-id="0">
					<span>最新发布</span>
				</a>
				<volist name="classify_hot_category" id="vo">
					<a href="javascript:;" class="weui-navbar__item ajaxcat" data-id="{pigcms{$vo.cid}" data-loadingurl="{pigcms{:U('getList',array('fcid'=>$vo['cid']))}&page=">
						<span>{pigcms{$vo.cat_name}</span>
					</a>
				</volist>
			</div>
		</div>
		<div id="list" class="mod-post x-postlist pt0">

		</div>
		<div id="loading-show" class="weui-loadmore">
			<i class="weui-loading"></i>
			<span class="weui-loadmore__tips">正在加载</span>
		</div>
		<div id="loading-none" class="weui-loadmore weui-loadmore_line hidden">
			<div class="hs_empty"><i class="icon iconfont icon-zanwuwenda"></i><p>没有更多了</p></div>
		</div>
	</div>
	<div class="cl footer_fix"></div>
	<div class="weui-tabbar">
		<a href="{pigcms{:U('index')}" class="weui-tabbar__item weui-bar__item_on">
			<i class="iconfont icon-index weui-tabbar__icon"></i>
			<p class="weui-tabbar__label">首页</p>
		</a>
        <a href="{pigcms{:U('hongbao')}" class="weui-tabbar__item ">
			<i class="iconfont icon-hongbao2 weui-tabbar__icon"></i>
			<p class="weui-tabbar__label">红包</p>
		</a>
		<a href="{pigcms{:U('fabu')}" class="weui-tabbar__item weui-bar__item_on showpubfont">
			<div class="pub_circle"></div>
			<i class="iconfont icon-fabuhei weui-tabbar__icon"></i>
			<p class="weui-tabbar__label pub_circle_p" style="color:#777!important">发布</p>    
		</a>
        <a href="{pigcms{:U('collect')}" class="weui-tabbar__item ">
			<i class="iconfont icon-jieban weui-tabbar__icon"></i>
			<p class="weui-tabbar__label">收藏</p>
		</a>
		<a href="{pigcms{:U('my')}" class="weui-tabbar__item ">
			<span style="display: inline-block;position: relative;">
				<i class="iconfont icon-xiaolian2 weui-tabbar__icon"></i>
			</span>
			<p class="weui-tabbar__label">我的</p>
		</a>
	</div>
	<if condition="$is_wexin_browser">
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Classifynew/index')}",
				"tTitle": "{pigcms{$page_title}",
				"tContent": "{pigcms{$config.site_name}"
			};
		</script>
		{pigcms{$shareScript}
	</if>
	<include file="footer"/>
	<script>
		var loadingurl = "{pigcms{:U('getList')}&t={pigcms{:time()}&page=";
		scrollto = 1;
		var SH_SLIDER = $('.sh_slider');
		SH_SLIDER.animate({"scrollLeft":635}, 20000, 'linear');
		$('.sh_slider').on('scroll', function(){
			if($(this).scrollLeft()>630){
				$(this).animate({"scrollLeft":0}, 1, 'linear');
				$(this).animate({"scrollLeft":635}, 20000, 'linear');
			}
		});
		SH_SLIDER.on('touchstart', function () {
			$('.sh_slider').stop().unbind();
		});
	</script>
</body>
</html>