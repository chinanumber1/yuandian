<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$config.shop_alias_name|default="快店"}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/shopBase.css?t={pigcms{$_SERVER.REQUEST_TIME}"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?220" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="http://api.map.baidu.com/api?type=quick&ak=4c1bb2055e24296bbaef36574877b4e2&v=1.0" charset="utf-8"></script>		
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?220" charset="utf-8"></script>
		<script type="text/javascript">
			var locationClassicHash = 'index';
			
			var user_long = '0',user_lat  = '0';
			var user_address='';
			var ajax_url_root = "{pigcms{$config.site_url}/wap.php?c=Shop&a=";
			var check_cart_url = "{pigcms{$config.site_url}/wap.php?c=Shop&a=confirm_order";
			var ajax_map_url = "{pigcms{$config.site_url}/index.php?g=Index&c=Map&a=suggestion&city_id={pigcms{$config.now_city}";
			var get_route_url = "{pigcms{:U('Group/get_route')}";
			var baiduToGcj02Url = "{pigcms{:U('Userlonglat/baiduToGcj02')}";
			var city_id="{pigcms{$config.now_city}";
			var cat_url="",sort_url="",type_url="";
			var noAnimate= true;
			var userOpenid="{pigcms{$_SESSION.openid}";
			var shopShareUrl = "{pigcms{$config.site_url}{pigcms{:U('Shop/index',array('openid'=>$_SESSION['openid']))}&shop-id=";
			var shopReplyUrl = "{pigcms{$config.site_url}/index.php??g=Index&c=Reply&a=ajax_get_list&order_type=3&parent_id=";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}shop/js/shopClassicBase.js?t={pigcms{$_SERVER.REQUEST_TIME}" charset="utf-8"></script>
	</head>
	<body>
		<div id="pageList" class="pageDiv show" <if condition="$config['shop_show_footer']">style="padding-bottom:56px;"</if>>
			<section id="listHeader" class="roundBg">
				<div id="listBackBtn" class="listBackBtn hide"><div></div></div>
				<div id="locationBtn" class="page-link" data-url="address" data-url-type="openRightFloatWindow">
					<span class="location"></span>
					<span id="locationText">正在定位</span>
					<span class="go"></span>
				</div>
				<div id="searchBtn" class="listSearchBtn page-link" data-url="shopsearch"><div></div></div>
			</section>
			<section id="listBanner" class="banner">
				<div class="swiper-container swiper-container1">
					<div class="swiper-wrapper"></div>
					<div class="swiper-pagination swiper-pagination1"></div>
				</div>
			</section>
			<section id="listSlider" class="slider">
				<div class="swiper-container swiper-container2" style="height:178px;">
					<div class="swiper-wrapper"></div>
					<div class="swiper-pagination swiper-pagination2"></div>
				</div>
			</section>
			<section id="listRecommend" class="recommend"></section>
			<section id="listNavBox" class="navBox">
				<ul>
					<li class="dropdown-toggle caret category" data-nav="category">
						<span class="nav-head-name">店铺分类</span>
					</li>
					<li class="dropdown-toggle caret sort" data-nav="sort">
						<span class="nav-head-name">智能排序</span>
					</li>
					<li class="dropdown-toggle caret type subway" data-nav="type">
						<span class="nav-head-name">类型</span>
					</li>
				</ul>
				<div class="dropdown-wrapper category">
					<div class="dropdown-module">
						<div class="scroller-wrapper">
							<div id="dropdown_scroller" class="dropdown-scroller">
								<div>
									<ul>
										<li class="category-wrapper" style="min-height:200px;">
											<ul class="dropdown-list"></ul>
										</li>
										<li class="sort-wrapper">
											<ul class="dropdown-list"></ul>
										</li>
										<li class="type-wrapper">
											<ul class="dropdown-list"></ul>
										</li>
									</ul>
								</div>
							</div>
							<div id="dropdown_sub_scroller" class="dropdown-sub-scroller"><div></div></div>
						</div>
					</div>
				</div>
			</section>
			<section id="listNavPlaceHolderBox">
			</section>
			<section id="storeList">
				<dl class="dealcard"></dl>
				<div id="storeListLoadTip">正在加载中...</div>
			</section>
			<section class="shade"></section>
			<php>if(!$config['shop_show_footer']){$no_footer = true;$no_small_footer = true;}</php>
			<include file="Public:footer"/>
		</div>
		<div id="pageLoadTipShade" class="pageLoadTipBg">
			<div id="pageLoadTipBox" class="pageLoadTipBox">
				<div class="pageLoadTipLoader">
					<div style="background-image:url({pigcms{$config.shop_load_bg});"><!--img src="{pigcms{$static_path}shop/images/pageTipImg.png"/--></div>
				</div>
			</div>
		</div>
		<include file="Shop:classic_js_theme"/>
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Shop",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Shop/index')}",
				"tTitle": "{pigcms{$config.shop_alias_name|default="快店"} - {pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};
		</script>
		{pigcms{$shareScript}
	</body>
</html>