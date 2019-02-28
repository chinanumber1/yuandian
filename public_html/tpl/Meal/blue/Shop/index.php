<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{pigcms{$config.shop_alias_name}列表_{pigcms{$config.seo_title}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css" rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css" rel="stylesheet" type="text/css" />
<link href="{pigcms{$static_path}css/order.css" type="text/css" rel="stylesheet" />
<link href="{pigcms{$static_path}css/meal_list.css" type="text/css" rel="stylesheet" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_path}js/jquery.nav.js"></script>
	<script type="text/javascript">
	   var  shop_alias_name = "{pigcms{$config.shop_alias_name}";
	</script>
<script src="{pigcms{$static_path}js/common.js"></script>
<script src="{pigcms{$static_path}js/list.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
<!--[if IE 6]>
	<script src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a-min.v86c6ab94.js"></script>
<![endif]-->
<!--[if lt IE 9]>
	<script src="{pigcms{$static_path}js/html5shiv.min-min.v01cbd8f0.js"></script>
<![endif]-->

<!--[if IE 6]>
<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">
   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');
</script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css"> 
	body{ behavior:url("csshover.htc");}
	.category_list li:hover .bmbox {filter:alpha(opacity=50);}
</style>
<![endif]-->
<style>
	.shop .category_list_title{
		width:100%;
	}
	.category_list li .bmbox{
		height:175px;
	}
	.category_list li .bmbox_list{
		margin:29px 0 0 80px;
	}
	.category_list li{
		margin-right:20px;
	}
	.category_list li.last--even{
		margin-right:0px;
	}
</style>
</head>
<body>
<include file="Public:header_top"/>
<div class="menu_table">
	<div class="bdw" id="bdw">
		<h2 style="font-size:18px;margin:20px 0;">身边快店&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight:normal;font-size:12px;color:#333;">{pigcms{$_COOKIE.userLocationName}&nbsp;&nbsp;<a href="/shop/around/" style="color:#0089dc;">[切换地址]</a></span></h2>
		<article class="menu_table">
			<if condition="$cat_option_html || $top_category">
				<div class="filter-section-wrapper">
					<if condition="$top_category || $area_list">
						<div class="filter-breadcrumb ">
							<span class="breadcrumb__item">
								<a class="filter-tag filter-tag--all" href="{pigcms{$default_url}">全部</a>
							</span>
							<php>if($top_category){</php>
							<span class="breadcrumb__crumb"></span>
							<span class="breadcrumb__item">
								<span class="breadcrumb_item__title filter-tag">{pigcms{$top_category.cat_name}<i class="tri"></i></span><a href="{pigcms{$default_url}" class="breadcrumb-item--delete"><i></i></a>
								<span class="breadcrumb_item__option">
									<span class="option-list--wrap inline-block">
										<span class="option-list--inner inline-block">
											<a href="{pigcms{$default_url}" class="log-mod-viewed">全部</a>
											<volist name="all_category_list_meal" id="vo">
												<a class="<if condition="$vo['cat_id'] eq $top_category['cat_id']">current</if> log-mod-viewed" href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a>
											</volist>
										</span>
									</span>
								</span>
							</span>
							<php>}</php>
							<php>if($now_category['cat_id'] != $top_category['cat_id'] && false){</php>
								<span class="breadcrumb__crumb"></span>
								<span class="breadcrumb__item">
									<span class="breadcrumb_item__title filter-tag">{pigcms{$now_category.cat_name}<i class="tri"></i></span><a href="{pigcms{$top_category.url}" class="breadcrumb-item--delete"><i></i></a>
									<span class="breadcrumb_item__option">
										<span class="option-list--wrap inline-block">
											<span class="option-list--inner inline-block">
												<a href="{pigcms{$top_category.url}" class="log-mod-viewed">全部</a>
												<volist name="son_category_list" id="vo">
													<a class="<if condition="$vo['cat_id'] eq $now_category['cat_id']">current</if> log-mod-viewed" href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a>
												</volist>
											</span>
										</span>
									</span>
								</span>
							<php>}</php>
						</div>
					</if>
					{pigcms{$cat_option_html}
				</div>
			</if>
		</article>
		<div id="filter">
			<div class="filter-sortbar">
				<div class="button-strip inline-block">
					<a href="{pigcms{$default_sort_url}" title="智能排序" class="button-strip-item inline-block button-strip-item-right <if condition="$_GET['order'] eq ''">button-strip-item-checked</if>"><span class="inline-block button-outer-box"><span class="inline-block button-content">智能排序</span></span></a>
					<a href="{pigcms{$hot_sort_url}" title="销量从高到低" class="button-strip-item inline-block button-strip-item-right button-strip-item-desc <if condition="$_GET['order'] eq 'hot'">button-strip-item-checked</if>"><span class="inline-block button-outer-box"><span class="inline-block button-content">销量</span><span class="inline-block button-img"></span></span></a><a href="{pigcms{$basic_price_url}" title="起送价最低" class="button-strip-item inline-block button-strip-item-right button-strip-item-asc <if condition="$_GET['order'] eq 'basic_price'">button-strip-item-checked</if>"><span class="inline-block button-outer-box"><span class="inline-block button-content">起送价</span><span class="inline-block button-img"></span></span></a><a href="{pigcms{$delivery_fee_url}" title="配送费最低" class="button-strip-item inline-block button-strip-item-right button-strip-item-asc <if condition="$_GET['order'] eq 'delivery_fee'">button-strip-item-checked</if>"><span class="inline-block button-outer-box"><span class="inline-block button-content">配送费</span><span class="inline-block button-img"></span></span></a><a href="{pigcms{$rating_sort_url}" title="评分从高到低" class="button-strip-item inline-block button-strip-item-right button-strip-item-desc <if condition="$_GET['order'] eq 'score_mean'">button-strip-item-checked</if>"><span class="inline-block button-outer-box"><span class="inline-block button-content">好评</span><span class="inline-block button-img"></span></span></a><a href="{pigcms{$time_sort_url}" title="发布时间从新到旧" class="button-strip-item inline-block button-strip-item-right button-strip-item-desc large-button  <if condition="$_GET['order'] eq 'create_time'">button-strip-item-checked</if>"><span class="inline-block button-outer-box"><span class="inline-block button-content">最新发布</span><span class="inline-block button-img"></span></span></a>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="f1" style="width:1210px;" class="cf">
	<div class="category_list">
		<ul>
			<volist name="shop_list" id="vo">
				<li <if condition='$i%4 eq 0'>class="last--even"</if>>
					<a href="{pigcms{$config.site_url}/shop/{pigcms{$vo.id}.html" target="_blank">
						<div class="category_list_img">
							<img src="{pigcms{$vo.image}"/>
							<div class="shop_data">
								<div class="shop_state" <if condition="$vo['delivery']">id="shop_state"</if>><if condition="$vo['delivery']"><if condition="$vo['delivery_system']">{pigcms{$config['deliver_name']}<else />商家配送</if> <else />门店自提</if> </div>
								<div class="shop_time">{pigcms{$vo['work_time']}</div>
							</div>
							<div class="bmbox">
								<div class="bmbox_list">
									<div class="bmbox_list_img"><img  class="lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'shop','id'=>$vo['id']))}" /></div>
								</div>
							</div>
						</div>
						<div class="datal">
							<div class="shop">
								<div class="category_list_title">{pigcms{$vo.name} </div>
								<div class="shop_icon">
									<if condition="$vo['zeng']">
									<span><img src="{pigcms{$static_path}images/dingcan_20.png" title="{pigcms{$vo['zeng']}"/></span>
									</if>
									<if condition="$vo['full_money'] neq 0.00 AND $vo['minus_money'] neq 0.00">
									<span><img src="{pigcms{$static_path}images/dingcan_22.png" title="支持立减优惠，每单满{pigcms{$vo['full_money']}元减{pigcms{$vo['minus_money']}元"/></span>
									</if>
									<if condition="$vo['song']">
									<span><img src="{pigcms{$static_path}images/dingcan_24.png" title="{pigcms{$vo['song']}"/></span>
									</if>
								</div>
								<div style="clear:both"></div>
							</div>
							<div class="deal-tile__detail">
								<div class="shop_add">
									<div class="shop_add_icon"><img src="{pigcms{$static_path}images/dingcan_30.png" /> </div>
									<div class="shop_add_txt">{pigcms{$vo.range} </div>
								</div>
								<!--div id="cheap">品牌快餐</div-->
							</div>
						</div>
					</a>
				</li>
			</volist>
		</ul>
	</div>
</div>
{pigcms{$pagebar}
<include file="Public:footer"/>
</body>
</html>