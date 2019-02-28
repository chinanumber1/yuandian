<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<if condition="!$is_app_browser">
			<title>{pigcms{$now_village.village_name}</title>
		<else/>
			<title>{pigcms{$config.house_market_name}</title>
		</if>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?2125"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/villagemarket.css?21255"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			var shopId = {pigcms{$house_market_shopId};
			var ajax_url_root = "{pigcms{$config.site_url}/wap.php?c=Shop&a=";
			var check_cart_url = "{pigcms{$config.site_url}/wap.php?c=Shop&a=confirm_order&store_id={pigcms{$house_market_shopId}&village_id={pigcms{$now_village.village_id}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/villagemarket.js?210" charset="utf-8"></script>
		<style>
			#container{top:77px;}
		</style>
	</head>
	<body class="villagemarket" style="overflow-x:hidden;">
		<!--if condition="!$is_app_browser">
			<header class="pageSliderHide">{pigcms{$config.house_market_name}</header>
		</if-->
		<section id="shopBanner">
			<div class="discount">
				<div class="noticeBox"><div class="notice"><div></div></div></div>
				<div id="shopCouponBox">
					<span id="shopCouponText"></span>
				</div>
			</div>
		</section>
		<section id="shopCatBar">	
			<div class="title">全部分类</div>
			<div class="content"><ul></ul></div>
		</section>
		<section id="cartBar">
			<div id="cartBarNumber">0</div>
		</section>
		<div id="container">
			<div id="scroller">
				<div id="shopProductBottomBar">
					<ul class="clearfix"></ul>
					<div id="shopProductBottomLine"></div>
					<div id="shopPageCatShade"></div>
				</div>
			</div>
		</div>
		<div id="shopProductCartShade"></div>
		<div id="shopProductCartBox"></div>
		<div id="shopProductCart">
			<div id="cartInfo" class="cartLeft" style="display:none;">
				<div class="cart">
					<div id="cartNumber">0</div>
				</div>
				<div class="price">共￥<span id="cartMoney">0</span></div>
			</div>
			<div id="emptyCart">
				<div class="cart"></div>购物车是空的
			</div>
			<div id="checkCart" style="display:none;">选好了</div>
			<div id="checkCartEmpty">起送价</div>
		</div>
		<div id="shopDetailPage" style="display:none;">
			<div class-s="scrollerBox">
				<div id="shopDetailpageClose" class="closeBtn"><div></div></div>
				<div id="shopDetailPageImgbox" class="swiper-container swiper-container-productImg">
					<div class="swiper-wrapper"></div>
					<div class="swiper-pagination swiper-pagination-productImg"></div>
				</div>
				<div id="shopDetailPageTitle">
					<div class="title">商品名称</div>
					<div class="desc">商品描述</div>
				</div>
				<div id="shopDetailPageFormat">商品库存</div>
				<div id="shopDetailPageBar" class="clearfix">
					<div class="fl" id="shopDetailPagePrice">价格</div>
					<div class="fr">
						<div id="shopDetailPageBuy">加入购物车</div>
						<div id="shopDetailPageNumber" style="display:none;">
							<div class="product_btn plus"></div>
							<div class="product_btn number">0</div>
							<div class="product_btn min"></div>
						</div>
					</div>
				</div>
				<div id="shopDetailPageLabel">
					<div class="tip">我要备注<div class="question"></div></div>
					<div id="shopDetailPageLabelBox"></div>
				</div>
				<div id="shopDetailPageContent">
					<div class="title">商品描述</div>
					<div class="content">商品描述内容</div>
				</div>
			</div>
		</div>
		<div id="shopZoomInfoBox">
			<div id="shopZoomInfo">
				<div id="shopZoomInfoCoupon">
					<dl>
						<dt>优惠信息</dt>
					</dl>
				</div>
				<div id="shopZoomInfoNotice">
					<dl>
						<dt>商家公告</dt>
					</dl>
				</div>
			</div>
			<div id="shopZoomInfoBoxClose" class="closeBtn"><div></div></div>
		</div>
		<script id="shopProductTopBarTpl" type="text/html">
			<li data-cat_id="0" class="active">全部分类</li>
			{{# for(var i = 0, len = d.length; i < len; i++){ }}
				<li data-cat_id="{{ d[i].cat_id }}">{{ d[i].cat_name }}</li>
			{{# } }}
		</script>
		<script id="shopProductBottomBarTpl" type="text/html">
			{{# for(var i = 0, len = d.length; i < len; i++){ }}
				{{# if(d[i].product_list.length > 0){ }}
					{{# for(var j = 0, jlen = d[i].product_list.length; j < jlen; j++){ }}
						<li class="product_{{ d[i].product_list[j].product_id }} product_cat_{{ d[i].cat_id }}" data-product_id="{{ d[i].product_list[j].product_id }}" data-product_price="{{ d[i].product_list[j].product_price }}" data-product_name="{{ d[i].product_list[j].product_name }}" data-stock="{{ d[i].product_list[j].stock }}">
							<div class="position_img">
								<img src="{{ d[i].product_list[j].product_image }}"/>
							</div>
							<div class="product_text">
								<div class="title">{{ d[i].product_list[j].product_name }}</div>
								{{# if(d[i].product_list[j].has_format){ }}
									<div class="price">￥{{ d[i].product_list[j].product_price }} 起</div>
								{{# }else{ }}
									<div class="price">￥{{ d[i].product_list[j].product_price }}<div class="unit"><em>/ </em>{{ d[i].product_list[j].unit }}</div></div>
								{{# } }}
							</div>
							{{# if(d[i].product_list[j].has_format){ }}
								<div class="product_btn">
									可选规格
								</div>
							{{# }else{ }}
								<div class="product_btn plus"></div>
							{{# } }}
						</li>
					{{# } }}
				{{# } }}
			{{# } }}
		</script>
		<script id="productCartBoxTpl" type="text/html">
			<dl>
				<dt class="clearfix">购物车<div id="shopProductCartDel">清空</div></dt>
				{{# for(var i in d){ console.log(d[i])}}
					<dd class="clearfix cartDD" data-product_id="{{ d[i].productId }}" data-product_price="{{ d[i].productPrice }}" data-product_name="{{ d[i].productName }}" data-stock="{{ d[i].productStock }}">
						<div class="cartLeft {{# if(d[i].productParam.length > 0 && d[i].productParam[0].name.length > 0){}}hasSpec{{# } }}">
							<div class="name">{{ d[i].productName }}</div>
							{{# 
								var tmpParamSpecStr =  d[i].productId;
							}}
							{{# if(d[i].productParam.length > 0 && d[i].productParam[0].name.length > 0){}}
								{{# 
									var tmpParam = [];
									var tmpParamSpec = [d[i].productId];
									for(var j in d[i].productParam){
										if(typeof(d[i].productParam[j].name) == 'string'){
											tmpParam.push(d[i].productParam[j].name);
											tmpParamSpec.push(d[i].productParam[j].id);
										}else{
											tmpParam.push(d[i].productParam[j].name.join(' '));
										}
									}
									var tmpParamStr = tmpParam.join(' ');
									tmpParamSpecStr = tmpParamSpec.join('_');
								}}
								<div class="spec" data-product_id="{{ tmpParamSpecStr }}">{{ tmpParamStr }}</div>
							{{# } }}
						</div>
						<div class="cartRight">
							<div class="product_btn plus cart"></div>
							<div class="product_btn number cart productNum-{{ tmpParamSpecStr }}">{{ d[i].count }}</div>
							<div class="product_btn min cart"></div>
							<div class="price">￥{{ d[i].productPrice }}</div>
						</div>
					</dd>
				{{# } }}
			</dl>
		</script>
		<script id="productFormatTpl" type="text/html">
			{{# for(var i in d){ }}
				<div class="row clearfix">
					<div class="left">{{ d[i].name }}</div>
					<div class="right fl">
						<ul>
							{{# var k = 0; for(var j in d[i].list){ }}
								<li class="fl {{# if(k == 0){ }}active{{# } }}" data-spec_list_id="{{ d[i].list[j].id }}"  data-spec_id="{{ d[i].list[j].sid}}">{{ d[i].list[j].name }}</li>
							{{#  k++; } }}
						</ul>
					</div>
				</div>
			{{# } }}
		</script>
		<script id="productPropertiesTpl" type="text/html">
			{{# for(var i in d){ }}
				<div class="row clearfix productProperties_{{ d[i].id }}" data-label_name="{{ d[i].name }}" data-num="{{ d[i].num }}">
					<div class="left">{{ d[i].name }}</div>
					<div class="right fl">
						<ul>
							{{# var k = 0; for(var j in d[i].val){ }}
								<li class="fl {{# if(k == 0 && d[i].num == 1){ }}active{{# } }}">{{ d[i].val[j] }}</li>
							{{#  k++; } }}
						</ul>
					</div>
				</div>
			{{# } }}
		</script>
		<script id="productSwiperTpl" type="text/html">
			{{# for(var i = 0, len = d.length; i < len; i++){ }}
				<div class="swiper-slide">
					<img src="{{ d[i].url }}"/>
				</div>
			{{# } }}
		</script>
		<include file="House:footer"/>
		{pigcms{$shareScript}
	</body>
</html>