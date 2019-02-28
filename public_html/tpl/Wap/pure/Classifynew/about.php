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
		<div class="weui-cells__title">{pigcms{$page_title}</div>
		<div class="weui-cells">
			<volist name="intro" id="vo">
				<a class="weui-cell weui-cell_access" href="javascript:;" onclick='$("#content{pigcms{$vo.id}").popup();'>
					<div class="weui-cell__bd">
						<p>{pigcms{$vo.title}</p>
					</div>
					<div class="weui-cell__ft">
					</div>
				</a>
			</volist>
		</div>
		<volist name="intro" id="vo">
			<div id="content{pigcms{$vo.id}" class="weui-popup__container" style="z-index:503">
				<div class="weui-popup__overlay"></div>
				<div class="weui-popup__modal">
					<div class="fixpopuper">
						<article class="weui-article">
							<h1>{pigcms{$vo.title}</h1>
							<div>{pigcms{$vo.content}</div>
						</article>
						<div class="footer_fix"></div>
						<div class="bottom_fix"></div>
					</div>
					<div class="fix-bottom">
						<a class="weui-btn weui-btn_primary close-popup" href="javascript:;">关闭</a>
					</div>
				</div>
			</div>
		</volist>
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
		<a href="{pigcms{:U('my')}" class="weui-tabbar__item">
			<span style="display: inline-block;position: relative;">
				<i class="iconfont icon-xiaolian2 weui-tabbar__icon"></i>
			</span>
			<p class="weui-tabbar__label">我的</p>
		</a>
	</div>
	<include file="footer"/>
	<if condition="$_GET['id']">
		<script>
			$("#content{pigcms{$_GET['id']}").popup();
		</script>
	</if>
</body>
</html>