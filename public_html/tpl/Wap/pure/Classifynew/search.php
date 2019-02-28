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
		<if condition="!$is_wexin_browser && !$is_app_browser">
			<header class="x_header bgcolor_11 cl f15">
				<a class="z f14" href="javascript:window.history.go(-1);"><i class="iconfont icon-fanhuijiantou w15"></i>返回</a>
				<a class="y sidectrl " href="javascript:$('#srh_popup').popup()">搜索</a>  
				<div class="navtitle">{pigcms{$page_title}</div>
			</header>
			<div class="x_header_fix" ></div>
		</if>
		<div id="list" class="mod-post x-postlist pt0"></div>
		<div id="loading-show" class="weui-loadmore">
			<i class="weui-loading"></i>
			<span class="weui-loadmore__tips">正在加载</span>
		</div>
		<div id="loading-none" class="weui-loadmore weui-loadmore_line hidden">
			<div class="hs_empty"><i class="icon iconfont icon-zanwuwenda"></i><p>没有更多了</p></div>
		</div>
	</div>

	<div id="srh_popup" class="weui-popup__container" style="z-index:1000">
		<div class="weui-popup__overlay"></div>
		<div class="weui-popup__modal">
			<div class="fixpopuper">
				<form action="{pigcms{:U('search')}" method="get" id="searchForm">
					<input type="hidden" name="c" value="Classifynew"/>
					<input type="hidden" name="a" value="search"/>
					<div class="weui-cells weui-cells_form" id="searchBar">
						<div class="weui-cell weui-cell_vcode">
							<div class="weui-cell__hd">
								<label class="weui-label" style="width:auto"><i class="c9 iconfont icon-sousuo vm"></i></label>
							</div>
							<div class="weui-cell__bd">
								<input type="search" class="weui-input" id="searchInput" placeholder="输入关键词" required="required" name="keyword" />
							</div>
							<div class="weui-cell__ft">
								<button class="weui-vcode-btn" type="submit">搜索</button>
							</div>
						</div>
					</div>
				</form>
				<div class="footer_fix"></div>
				<div class="bottom_fix"></div>
			</div>
			<div class="fix-bottom">
				<a class="weui-btn weui-btn_default close-popup" >取消</a>
			</div>
		</div>
	</div>

	<script>
		var loadingurl = '{pigcms{:U('getList',array('keyword'=>$_GET['keyword']))}&page=';
		scrollto = 1;
		$('li.checked.main_color').each(function(i,item){
			var name = $(item).find('a').text();
			var id = $(item).closest('.nav_expand_panel').data('id');
			$('.dist_nav_'+id+' .optName').html(name);
		});
	</script>
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
</body>
</html>