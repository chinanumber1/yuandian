<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<if condition="$is_wexin_browser or $is_app_browser">
<title>营销活动列表</title>
<else />
<title>营销活动列表_{pigcms{$config.site_name}</title>
</if>
<meta name="keywords" content="{pigcms{$now_category.cat_name},{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}">
<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name='apple-touch-fullscreen' content='yes'>
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">

<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet" />
<link href="{pigcms{$static_path}css/index_wap.css" rel="stylesheet" />
<style>
.dealcard .title {
	height: .36rem;
}

.w-goods-price {
	color: #9E9E9E;
	font-size: .26rem;
	height: .46rem;
}
/*.w-progressBar {
  margin-right: 50px;
}*/
.w-progressBar .wrap {
	position: relative;
	margin-bottom: 8px;
	height: 5px;
	border-radius: 5px;
	background-color: #efeeee;
	overflow: hidden;
}

.w-progressBar .bar, .w-progressBar .color {
	display: block;
	height: 100%;
	border-radius: 4px;
}

.w-progressBar .bar {
	overflow: hidden;
}

.w-progressBar .color {
	width: 100%;
	background: #FFA538;
	background: -webkit-gradient(linear, left top, right top, from(#FFCB3D), to(#FF8533));
	background: -moz-linear-gradient(left, #FFCB3D, #FF8533);
	background: -o-linear-gradient(left, #FFCB3D, #FF8533);
	background: -ms-linear-gradient(left, #FFCB3D, #FF8533);
}

.w-progressBar .txt {
	overflow: hidden;
}

.w-progressBar li {
	float: left;
	color: #9E9E9E;
	font-size: .26rem;
}

.w-progressBar .txt b {
	font-weight: normal;
}

.w-progressBar .txt-r {
	float: right;
	border: 0;
	text-align: right;
}

.finish_tip {
	display: inline-block;
	/* margin-left: 30px; */
	color: red;
}

.txt-blue {
	color: #0079fe;
}
</style>
</head>
<body id="index">
	<div id="container">
		<!--section class="banner"></section>  -->
		<div class="nav-bar">
			<ul class="nav">
				<li class="dropdown-toggle caret category" data-nav="category"><span class="nav-head-name"><if condition="$now_category">{pigcms{$now_category.cat_name}<else />全部分类</if></span></li>
			</ul>
			<div class="dropdown-wrapper">
				<div class="dropdown-module">
					<div class="scroller-wrapper">
						<div id="dropdown_scroller" class="dropdown-scroller" style="overflow: hidden;">
							<ul>
								<li class="category-wrapper">
									<ul class="dropdown-list">
										<li data-category-id="-1"<if condition="empty($now_category)">class="active"</if>><span>全部分类</span></li>
										<volist name="all_category_list" id="vo">
										<li data-category-id="{pigcms{$vo.cat_url}" onclick="list_location($(this));return false;" class="<if condition="$now_category['cat_url'] eq $vo['cat_url']">active</if>">
											<span>{pigcms{$vo.cat_name}</span>
										</li>
										</volist>
									</ul>
								</li>
								<if condition="$all_area_list">
								<li class="biz-wrapper">
									<ul class="dropdown-list">
										<li data-area-id="-1" <if condition="empty($now_area_url)">class="active"</if> onclick="list_location($(this));return false;"><span>全城</span></li>
										<volist name="all_area_list" id="vo">
										<li data-area-id="{pigcms{$vo.area_url}"<if condition="$vo['area_count'] gt 0">data-has-sub="true"<else />onclick="list_location($(this));return false;"</if> class="<if condition="$vo['area_count'] gt 0">right-arrow-point-right</if> <if condition="$top_area['area_url'] eq $vo['area_url']">active</if>">
											<span>{pigcms{$vo.area_name}</span>
											<if condition="$vo['area_count'] gt 0">
											<span class="quantity"><b></b></span>
											</if>
											<div class="sub_cat hide" style="display: none;">
												<if condition="$vo['area_count'] gt 0">
												<ul class="dropdown-list">
													<li data-area-id="{pigcms{$vo.area_url}" onclick="list_location($(this));return false;">
														<div>
															<span class="sub-name">全部</span>
														</div>
													</li>
													<volist name="vo['area_list']" id="voo" key="j">
													<li data-area-id="{pigcms{$voo.area_url}" onclick="list_location($(this));return false;">
														<div>
															<span class="sub-name">{pigcms{$voo.area_name}</span>
														</div>
													</li>
													</volist>
												</ul>
												</if>
											</div>
										</li>
										</volist>
									</ul>
								</li>
								</if>
							</ul>
						</div>
						<div id="dropdown_sub_scroller" class="dropdown-sub-scroller" style="overflow: hidden;"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="deal-container">
			<div class="deals-list" id="deals">
				<if condition="$wxapp_list">
				<dl class="list list-in">
					<volist name="wxapp_list" id="vo">
					<dd>
						<a href="{pigcms{$vo.url}" class="react">
							<div class="dealcard">
								<div class="dealcard-img imgbox">
									<img src="{pigcms{$vo.image}" style="width: 100%; height: 100%;" />
								</div>
								<div class="dealcard-block-right">
									<div class="dealcard-brand single-line">{pigcms{$vo.title}</div>
									<div class="title text-block">{pigcms{$vo.info|strip_tags=###}</div>
									<p class="w-goods-price">[{pigcms{$vo.type_name}]</p>
									<p class="w-goods-price">
										<span class="finish_tip">[查看]</span>
									</p>
								</div>
							</div>
						</a>
					</dd>
					</volist>
				</dl>
				<if condition="$pagebar">
				<dl class="list">
					<dd>
						<div class="pager">{pigcms{$pagebar}</div>
					</dd>
				</dl>
				</if>
				<else />
				<div class="no-deals">暂无此类活动，请查看其他类别</div>
				</if>
			</div>
			<div class="shade hide"></div>
			<div class="loading hide">
				<div class="loading-spin" style="top: 91px;"></div>
			</div>
		</div>
	</div>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_path}js/common_wap.js"></script>
	<script src="{pigcms{$static_public}js/fastclick.js"></script>
	<script>
	var myScroll3 = null;
	var mer_id = {pigcms{$_GET['mer_id']|intval=###};
	$(function(){
		FastClick.attach(document.body);
		$('#container').css('min-height',$(window).height()-$('header.navbar').height()-60+'px');
	});
	</script>
	<script src="{pigcms{$static_path}js/dropdown.js"></script>
	<script>
		var location_url = "{pigcms{:U('Wxapp/index')}";
		var now_cat_url="<if condition="!empty($now_cat_url)">'{pigcms{$now_cat_url}'<else/>'all'</if>";
	</script>
	<script src="{pigcms{$static_path}js/wxapplist.js"></script>
	<php>$no_footer = true;</php>
	<include file="Public:footer" />

	<script type="text/javascript">
	window.shareData = {  
		"moduleName":"Wxapp",
		"moduleID":"0",
		"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Wxapp/index')}",
		"tTitle": "营销活动列表_{pigcms{$config.site_name}",
		"tContent": ""
	};
	</script>
	{pigcms{$shareScript}
</body>
</html>