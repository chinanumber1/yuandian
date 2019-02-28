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
				<if condition="$config['is_im'] && $nowUser['uid'] neq $user_session['uid']">
					<a class="y sidectrl" href="{pigcms{:U('My/go_im',array('hash'=>'group_user'.$nowUser['uid'],'title'=>urlencode($nowUser['nickname'])))}"><i class="icon-sixin2 iconfont"></i> 私信</a>
				</if>
			</header>
		</if>
		<div class="my__head" >
			<div class="my__head_wap">
				<div class="my__head_user tc">
					<span class="my__head_avatar" style="padding:0">
						<img class="my__head_avatar" style="padding:0" src="{pigcms{$nowUser.avatar|default='./static/images/user_avatar.jpg'}" />
					</span>
					<span class="my__head_nickname">{pigcms{$nowUser.nickname}</span>
				</div>
			</div>
		</div>
		<div class="weui-navbar mt0">
			<a href="{pigcms{:U('member',array('uid'=>$nowUser['uid']))}" class="weui-navbar__item  weui_bar__item_on">
				<span>发布</span>
			</a>
			<a href="{pigcms{:U('member_visit',array('uid'=>$nowUser['uid']))}" class="weui-navbar__item ">
				<span>访客</span>
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
	<div class="cl footer_fix"></div>
	<div class="weui-tabbar">
		<a href="{pigcms{:U('index')}" class="weui-tabbar__item">
			<i class="iconfont icon-index weui-tabbar__icon"></i>
			<p class="weui-tabbar__label">首页</p>
		</a>
        <a href="{pigcms{:U('hongbao')}" class="weui-tabbar__item">
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
	<include file="footer"/>
	<script>
		var loadingurl = "{pigcms{:U('getList',array('uid'=>$nowUser['uid']))}&page=";
	</script>
</body>
</html>