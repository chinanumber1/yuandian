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
		<else/>
			<style>.fix_float{top:0px;}</style>
		</if>
		<if condition="$conarr">
			<div class="cl fix_float_fix"></div>
			<div class="weui-navbar fix_float">
				<volist name="conarr" id="value">
					<a data-id="{pigcms{$key}" class="dist_nav weui-navbar__item dist_nav_{pigcms{$key}">
						<span><em class="optName">{pigcms{$value.name}</em> <i class="iconfont icon-xiangxia f12"></i></span>
					</a>
				</volist>
			</div>
		</if>
		<div class="dist_show">
			<volist name="conarr" id="value">
				<div id="dist_show_{pigcms{$key}" data-id="{pigcms{$key}" class="nav_expand_panel border_top">
					<div class="weui-flex">
						<div class="weui-flex__item">
							<ul>
								<volist name="value['data']" key="kk" id="dv">
									<php>if(($value['opt']==1) && ($kk==1) && (strpos($dv, '-') === false)){
											$opt="opt,ty=".$value[opt].",fd=".$value['input'].",vv=0-".$dv;
										}elseif(($value['opt']==1) && ($kk>1) && (strpos($dv, '-') === false)){
											$opt="opt,ty=".$value[opt].",fd=".$value['input'].",vv=".$dv."-0";
										}else{
											$opt="opt,ty=".$value[opt].",fd=".$value['input'].",vv=".$dv;
										}
										$opt=base64_encode($opt);
									</php>
									<li class="<if condition="!empty($original) AND ($original eq trim($dv))">checked main_color</if> border_bfull"><a href="{pigcms{:U('category',array('cat_id'=>$cid))}&opt={pigcms{$opt}">{pigcms{$dv}</a></li>
								</volist>
							</ul>
						</div>
					</div>
				</div>
			</volist>
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
		<if condition="$fcid">
			var loadingurl = '{pigcms{:U('getList',array('opt'=>$_GET['opt'],'cid'=>$_GET['cat_id']))}&page=';
		<else/>
			var loadingurl = '{pigcms{:U('getList',array('opt'=>$_GET['opt'],'fcid'=>$_GET['cat_id']))}&page=';
		</if>
	</script>
	<script>
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
	<if condition="$is_wexin_browser">
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('category',array('cat_id'=>$_GET['cat_id']))}",
				"tTitle": "{pigcms{$page_title}",
				"tContent": "{pigcms{$config.site_name}"
			};
		</script>
		{pigcms{$shareScript}
	</if>
	<include file="footer"/>
	<if condition="$config['is_demo_domain']">
		<script>
			var isShow = hb_getcookie('classifyCategoryDemoTip');
			if(!isShow){
				demoDomain_tip("每个分类都是在系统后台添加，同时可以根据分类特征在后台设置自定义填写项，例如房屋分类是否需要填写房型、楼层，均可自定义，发布后其他用户浏览可见。同时也可设置为分类筛选项其他用户进行筛选查看，每分类最多支持4个筛选项。", "分类页面使用提醒");
				hb_setcookie('classifyCategoryDemoTip','1',86400*365);
			}
		</script>
	</if>
</body>
</html>