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
	<div class="page__bd hong">
		<if condition="!$is_wexin_browser && !$is_app_browser">
			<header class="x_header bgcolor_11 cl f15">
				<a class="z f14" href="javascript:window.history.go(-1);"><i class="iconfont icon-fanhuijiantou w15"></i>返回</a>
				<a class="y sidectrl " href="{pigcms{:U('My/my_money')}">钱包</a>    
			</header>
		</if>
		<div class="hong_top">
			<img class="hong_bg" src="{pigcms{$static_path}classifynew/images/hb2.jpg" />
			<div class="water" style="bottom:-10px">
				<div class="water-c">
					<div class="water-1"></div>
					<div class="water-2"></div>
				</div>
			</div>
		</div>
		<div class="weui-navbar">
			<a style="padding-top:5px" href="{pigcms{:U('hongbao')}" class="weui-navbar__item <if condition="!$_GET['type']">weui_bar__item_on</if>">
				<span>信息红包</span>
			</a>
			<a style="padding-top:5px" href="{pigcms{:U('hongbao',array('type'=>'have'))}" class="weui-navbar__item <if condition="$_GET['type'] eq 'have'">weui_bar__item_on</if>">
				<span>待抢</span>
			</a>
		</div>
		<div id="list" class="mod-post x-postlist pt0"></div>

    
		<div id="loading-show" class="weui-loadmore">
			<i class="weui-loading"></i>
			<span class="weui-loadmore__tips">正在加载</span>
		</div>
		<div id="loading-none" class="weui-loadmore weui-loadmore_line hidden">
			<div class="hs_empty"><i class="icon iconfont icon-zanwuwenda"></i><p>没有更多了</p></div>
		</div>
	</div>
	<script>
		var loadingurl = "{pigcms{:U('getList',array('hongbao'=>1,'type'=>$_GET['type']))}&page=";
		scrollto = 1;
	</script>
	<div class="cl footer_fix"></div>
	<div class="weui-tabbar">
		<a href="{pigcms{:U('index')}" class="weui-tabbar__item">
			<i class="iconfont icon-index weui-tabbar__icon"></i>
			<p class="weui-tabbar__label">首页</p>
		</a>
        <a href="{pigcms{:U('hongbao')}" class="weui-tabbar__item weui-bar__item_on">
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
</body>
</html>