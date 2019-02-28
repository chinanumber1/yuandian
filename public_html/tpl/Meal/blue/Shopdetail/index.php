<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>{pigcms{$store.name} - {pigcms{$config.site_name}</title>
<meta name="keywords" content="{pigcms{$store.name}网上{pigcms{$config.shop_alias_name},{pigcms{$store.name}电话,{pigcms{$store.name}外卖,{pigcms{$store.name}菜单,{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/shop.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/kuaisonWM.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/header.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/shop_header.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/ydyfx.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/a.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/meal_detail.css" type="text/css" rel="stylesheet">
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
<script src="{pigcms{$static_public}js/layer/layer.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
<script type="text/javascript">var  shop_alias_name = "{pigcms{$config.shop_alias_name}";</script>
<script src="{pigcms{$static_path}js/common.js"></script>
<script src="{pigcms{$static_path}js/requestAnimationFrame.js"></script>
<script src="{pigcms{$static_path}js/fly.js"></script>
<script type="text/javascript">var store_id = '{pigcms{$store['id']}',store_long = '{pigcms{$store.long}',store_lat = '{pigcms{$store.lat}', get_reply_url="{pigcms{:U('Index/Reply/ajax_get_list',array('order_type'=>1,'parent_id'=>$store['id'],'store_count'=>1))}",default_avatar="{pigcms{$static_path}images/meal_default_avatar.png",static_path = "{pigcms{$static_path}";</script>
<script src="{pigcms{$static_path}js/shopbuy.js"></script>
<style type="text/css">
	.module_s .img li{z-index:auto;}
	.notice_discount li{
		position: relative;
		padding-left: 20px;
		font-size: 12px;
		line-height: 22px;
		margin-bottom: 5px;
	}
	.notice_discount li em {
		width: 16px;
		height: 16px;
		line-height: 16px;
		display: inline-block;
		background-color: red;
		position: absolute;
		left: 0;
		top: 3px;
		border-radius: 2px;
	}
	.notice_discount li em:before {
		content: '惠';
		font-size: 11px;
		color: white;
		margin-left: 2px;
	}
	em.merchant_minus{
		background-color:#FF6655!important;
	}
	em.system_minus{
		background-color:#5D26EA!important;
	}
	em.merchant_minus:before,em.system_minus:before{
		content:'减'!important;
	}
	em.newuser{
		background-color:#FFAA22!important;
	}
	em.system_newuser{
		background-color:#0EC0A8!important;
	}
	em.newuser:before,em.system_newuser:before{
		content:'首'!important;
	}
	em.merchant_delivery{
		background-color:#6C5CB4!important;
	}
	em.merchant_delivery:before{
		content:'送'!important;
	}
	em.merchant_discount{
		background-color:#DD1111!important;
	}
	em.merchant_discount:before{
		content:'折'!important;
	}
	em.merchant_invoice:before {
		content: '票'!important;
	}
	.map{
		height:250px;border:1px solid #fafafa;
	}
	.map .anchorBL {
		display: none;
	}
	.module_s .img li{
		overflow: visible;
	}
	.module_s_open .bd{
		overflow: visible;
	}
	.module_s .hd{
		    z-index: 0;
	}
	.spec-tip{
		z-index: 1000;
		display: block;
		position: absolute;
		top: 184px;
		left: -14px;
		width:98%;
		display: none;
	}
	.add-overlay .close {
		cursor: pointer;
		height: 36px;
		width: 36px;
		display: inline-block;
		float: right;
		background-image:url({pigcms{$static_path}images/meal_close.png?t=1);
		background-repeat: no-repeat;
	}
	.add-overlay .content {
		clear: both;
		background: #fff;
		border-top: 2px solid #ff2d4b;
		-moz-box-shadow: 0 0 5px 0 #e4e4e4;
		-webkit-box-shadow: 0 0 5px 0 #e4e4e4;
		-o-box-shadow: 0 0 5px 0 #e4e4e4;
		-ms-box-shadow: 0 0 5px 0 #e4e4e4;
		box-shadow: 0 0 5px 0 #e4e4e4;
		border: 1px solid #e4e4e4\9;
	}
	.add-overlay .size-table {
		margin: 15px 20px;
		font-size: 12px;
		color: #999;
		font-weight: 400;
		text-decoration: none;
	}
	.add-overlay .size-table td {
    padding: 4px 4px 4px 0;
}
.add-overlay .size-table .attr-title {
    width: 61px;
}
.add-overlay .size-table .s-item {
    padding: 2px 8px;
    margin: 0 8px 4px 0;
    cursor: pointer;
    color: #333;
    display: inline-block;
    text-align: center;
    -moz-border-radius: 2px;
    border-radius: 2px;
    background: #fafafa;
}
.add-overlay .size-table .s-item.sec{
    background: #ff2d4b;
    color: #fff;
}
.m-sel-icon {
    visibility: visible;
    color: #ff2d4b;
    height: 26px;
    line-height: 26px;
    position: absolute;
    bottom: 10px;
    right: 10px;
    -webkit-user-select: none;
    -moz-user-select: none;
    width: 30px;
    overflow: hidden;
}
.add-overlay .m-sel-icon {
    position: static;
    float: left;
    margin: 0;
}
.add-overlay .btn-con {
    height: 30px;
    padding: 12px 20px;
    background: #f9f9f9;
    text-align: center;
}
.add-overlay .submit-btn {
    display: inline-block;
    width: auto;
    height: 16px;
    padding: 0;
    border: 0;
    text-align: center;
    zoom: 1;
    -webkit-transition: background-color .2s ease-in 0s;
    -moz-transition: background-color .2s ease-in 0s;
    -o-transition: background-color .2s ease-in 0s;
    transition: background-color .2s ease-in 0s;
    -moz-border-radius: 2px;
    border-radius: 2px;
    font-size: 1em;
    background-color: #ff2d4b;
    cursor: pointer;
    font-size: 16px;
    color: #fff;
    font-weight: 400;
    text-decoration: none;
    padding: 4px 70px 12px;
	line-height:22px;
}
.m-sel-icon .minusfrcart {
    cursor: pointer;
    display: inline-block;
    width: 26px;
    height: 26px;
    padding: 0;
    border: 0;
    text-align: center;
    zoom: 1;
    background-repeat: no-repeat;
}
.m-sel-icon .addtocart {
    cursor: pointer;
    display: inline-block;
    width: 26px;
    height: 26px;
    padding: 0;
    border: 0;
    text-align: center;
    zoom: 1;
    background-repeat: no-repeat;
}
.m-sel-icon .select_count {
    display: inline-block;
    width: 30px;
    height: 26px;
    padding: 0;
    border: 0;
    text-align: center;
    zoom: 1;
    overflow: hidden;
    vertical-align: top;
}
.m-sel-icon .minusfrcart {
    background-position: -4px -108px;
}
.m-sel-icon .addtocart {
    background-position: -4px -72px;
}
.m-sel-icon .addtocart,.m-sel-icon .minusfrcart{
    background-image: url({pigcms{$static_path}images/meal_menu.png?t=1);
}
</style>
</head>
<body>
<header>
	<header class="header" style="padding-bottom:10px;"> 
		<div class="content">
			<div class="header_top">
				<div class="hot">
			        <div class="loginbar cf">
						<if condition="$now_select_city">
							<div class="span" style="font-size:16px;color:red;padding-right:3px;cursor:default;">{pigcms{$now_select_city.area_name}</div>
							<div class="span" style="padding-right:10px;">[<a href="{pigcms{:UU('Index/Changecity/index')}">切换城市</a>]</div>
							<div class="span" style="padding-right:10px;">|</div>
						</if>
						<if condition="empty($user_session)">
							<div class="login"><a href="{pigcms{:U('Index/Login/index')}"> 登录 </a></div>
							<div class="regist"><a href="{pigcms{:U('Index/Login/reg')}">注册 </a></div>
						<else/>
							<p class="user-info__name growth-info growth-info--nav">
								<span>
									<a rel="nofollow" href="{pigcms{:U('User/Index/index')}" class="username">{pigcms{$user_session.nickname}</a>
								</span>
								<a class="user-info__logout" href="{pigcms{:U('Index/Login/logout')}">退出</a>
							</p>
						</if>
						<div class="span">|</div>
						<div class="weixin cf">
							<div class="weixin_txt"><a href="{pigcms{$config.site_url}/topic/weixin.html"> 微信版</a></div>
							<div class="weixin_icon"><p><span>|</span><a href="{pigcms{$config.site_url}/topic/weixin.html">访问微信版</a></p><img src="{pigcms{$config.wechat_qrcode}"/></div>
						</div>
			        </div>
			        <div class="list">
						<ul class="cf">
							<li>
								<div class="li_txt"><a href="{pigcms{:U('User/Index/index')}">我的订单</a></div>
								<div class="span">|</div>
							</li>
							<li class="li_txt_info cf">
								<div class="li_txt_info_txt"><a href="{pigcms{:U('User/Index/index')}">我的信息</a></div>
								<div class="li_txt_info_ul">
									<ul class="cf">
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Index/index')}">我的订单</a></li>
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Rates/index')}">我的评价</a></li>
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Collect/index')}">我的收藏</a></li>
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Point/index')}">我的{pigcms{$config['score_name']}</a></li>
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Credit/index')}">帐户余额</a></li>
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Adress/index')}">收货地址</a></li>
									</ul>
								</div>
								<div class="span">|</div>
							</li>
							<li class="li_liulan">
								<div class="li_liulan_txt"><a>最近浏览</a></div>	 
								<div class="history" id="J-my-history-menu"></div> 
								<div class="span">|</div>
							</li>
							<li class="li_shop">
								<div class="li_shop_txt"><a>我是商家</a></div>
								<ul class="li_txt_info_ul cf">
									<li><a class="dropdown-menu__item first" rel="nofollow" href="{pigcms{$config.site_url}/merchant.php">商家中心</a></li>
									<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{$config.site_url}/merchant.php">我想合作</a></li>
								</ul>
							</li>
						</ul>
			        </div>
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="nav">
			<div class="logo">
				<a href="{pigcms{$config.site_url}" title="{pigcms{$config.site_name}">
					<img src="{pigcms{$config.site_logo}" />
				</a>
			</div>
			<div class="menu" style="width:262px;">
				<div class="ment_left">
					<div class="ment_left_img"><img src="{pigcms{$static_path}images/dianpu_03.png"/></div>
					<a href="{pigcms{$config.site_url}/shop/"><div class="ment_left_txt">{pigcms{$config.shop_alias_name}主页</div></a>
				</div>
				<div class="hr">|</div>
				<div class="ment_left" style="margin-left:15px;">
					<div class="ment_left_img"><img src="{pigcms{$static_path}images/dianpu_05.png"></div>
					<a href="{pigcms{:U('User/Index/shop_list')}"><div class="ment_left_txt">我的订单</div></a>
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
		<div style="clear:both"></div>
 	</header>
  	<div class="shopping-cart clearfix" data-status="1" data-poiname="{pigcms{$store.name}" data-poiid="{pigcms{$store.store_id}">
		<form method="post" action="/shop/order/{pigcms{$store['id']}.html" id="shoppingCartForm">
			<div class="order-list">
				<div class="title cf">
					<span class="fl dishes">商品<a href="javascript:;" class="clear-cart">[清空]</a></span>
					<span class="fl">份数</span>
					<span class="fl ti-price">价格</span>
				</div>
				<ul class="clearfix">
				</ul>
				<div class="other-charge hidden">
					<div class="clearfix packing-cost hidden">
						<span class="fl">包装盒</span>
						<span class="fr boxtotalprice">￥0</span>
					</div>
					<div class="clearfix delivery-cost">
						<span class="fl">配送费</span>
						<span class="fr shippingfee">￥0</span>
					</div>
				</div>
				<div class="privilege hidden"></div>
				<div class="total">共<span class="totalnumber">0</span>份，总计<span class="bill">￥0</span></div>
			</div>
			   
			<div class="footer clearfix">
				<div class="logo fl" id="i-shopping-cart"></div>
				<div class="brief-order fl">
					<span class="count"></span>
					<span class="tprice"></span>
				</div>
				<div class="fr">
					<a class="ready-pay borderradius-2" href="javascript:;">还是空的<!--还差<span data-left="20" class="margintominprice">20</span>元起送--></a>
					<input class="go-pay borderradius-2" type="submit" value="去下单">
					<input type="hidden" value="" class="order-data" name="shop_cart" id="shop_cart">
				</div>
			</div>
		</form>
	</div>
	<div class="w-1200 cf">
		<div class="grid_subHead clearfix">
			<div class="col_main">
				<div class="col_sub">
					<div class="shop_logo"><img src="{pigcms{$store['image']}"></div>
				</div>
				<div class="main_wrap cf">
					<div class="mian_wrap_shop">
						<div class="shop_name">{pigcms{$store['name']}</div>
						<div class="top_shop_qrcode">微信访问<img src="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=shop&id={pigcms{$store['id']}" /></div>
					</div>
					<div class="main_wrap_left">
						<div class="appraise_title cf">
							<div class="appraise_icon"><div><span style="width:{pigcms{$store['star']/5*100}%"></span></div></div>
							<em>{pigcms{$store['star']} 分</em>
						</div>
	 					<p class="shop_state">营业时间：{pigcms{$store['time']}<!--if condition="$store['state']"><span class="inner state_1" id="state_node">营业中</span><-else /><span class="inner state_3" id="state_node">已打烊，还可以预订</span></if--></p>
						<p class="shop_address">地址：{pigcms{$store['adress']}</p>
					</div>
					<div class="main_wrap_right">
						<ul class="songcan_data clearfix">
							<if condition="$store['delivery']">
								<li class="songda">
									<strong><em>{pigcms{$store['delivery_time']}分钟</em></strong>
									<span>送达时间</span>
								</li>
								<li class="renjun">
									<strong><em>{pigcms{:floatval($store['delivery_price'])}元</em></strong>
									<span>起送价</span>
								</li>
								<li class="peison">
									<strong><em class="psfee_">{pigcms{:floatval($store['delivery_money'])}元</em></strong>
									<span>配送费</span>
								</li>
							<else/>
								<li class="songda">
									<span>到店自提</span>
								</li>
							</if>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="body"> 
	<article class="shop_list cf">
		<div class="tabright">
			<div class="notice_title">店铺公告</div>
			<div class="notice_con"><if condition="$store['store_notice'] neq '' AND $store['store_notice'] neq ' '">{pigcms{$store['store_notice']}<else />本店暂无公告</if></div>
			<div class="notice_discount">
				<ul>
					<if condition="$store['coupon_list_txt']['invoice']">
						<li><em class="merchant_invoice"></em>{pigcms{$store['coupon_list_txt']['invoice']}</li>
					</if>
					<if condition="$store['coupon_list_txt']['discount']">
						<li><em class="merchant_discount"></em>{pigcms{$store['coupon_list_txt']['discount']}</li>
					</if>
					<if condition="$store['coupon_list_txt']['minus']">
						<li><em class="merchant_minus"></em>{pigcms{$store['coupon_list_txt']['minus']}</li>
					</if>
					<if condition="$store['coupon_list_txt']['newuser']">
						<li><em class="newuser"></em>{pigcms{$store['coupon_list_txt']['newuser']}</li>
					</if>
					<if condition="$store['coupon_list_txt']['delivery']">
						<li><em class="delivery"></em>{pigcms{$store['coupon_list_txt']['delivery']}</li>
					</if>
					<if condition="$store['coupon_list_txt']['system_minus']">
						<li><em class="system_minus"></em>{pigcms{$store['coupon_list_txt']['system_minus']}</li>
					</if>
					<if condition="$store['coupon_list_txt']['system_newuser']">
						<li><em class="system_newuser"></em>{pigcms{$store['coupon_list_txt']['system_newuser']}</li>
					</if>
				</ul>
			</div>
			<div class="map" id="StoreMap"></div>
		</div>
		<div class="tab1" id="tab1">
			<div class="menu" style="color: #4c4c4c;">
				<ul class="cf">
					<li class="off tab">商品列表</li>
					<!--li class="tab">网友点评<if condition="$store['reply_count']"><span>({pigcms{$store['reply_count']})</span></if></li-->
					<li class="merchantWeb"><a href="{pigcms{$config.site_url}/merindex/{pigcms{$store.mer_id}.html" target="_blank">商家网站</a></li>
				</ul>
				<div class="btmline"></div>
			</div>
			<div class="menudiv">
				<div id="con_one_1">
					<section>
						<div class="content">
							<div class="bgk">
								<div id="prolist">
									<div class="can_cat cf">
										<div class="bd">
											<ul class="clearfix">
												<volist name="product_list" id="sort">
													<li>
														<a href="#sort_{pigcms{$sort['cat_id']}" data-scrolld="plist_{pigcms{$sort['cat_id']}">{pigcms{$sort['cat_name']}</a>
													</li>
												</volist>
											</ul>
										</div>
									</div>
									<volist name="product_list" id="vo" key="y">
										<if condition="$vo['product_list']">
										<div class="module_s module_s_open">
											<div class="hd">
												<div class="tit">{pigcms{$vo.cat_name}</div>
												<a href="javascript:;" class="s module_s_open_btn" name="sort_{pigcms{$vo['cat_id']}">收起</a>
											</div>
											<div class="bd">
												<ul class="img clearfix">
													<volist name="vo['product_list']" id="meal" key="j">
														<li class="item_{pigcms{$meal['product_id']} buygoods <if condition="$j%3 eq 0 || $j eq count($vo['product_list'])">last-br</if> <if condition="$j gt 3">no-bt</if>" id="{pigcms{$meal['product_id']}" data-title="{pigcms{$meal['des']}">
															<a href="javascript:;" class="link" name="meal_{pigcms{$meal['product_id']}">
																<img class="lazy_img" src="http://hf.pigcms.com/static/images/blank.gif" data-original="<if condition="$meal['product_image']">{pigcms{$meal['product_image']}<else />../static/images/nopic.jpg</if>" />
																<div class="product_info">
																	<span class="tit">{pigcms{$meal['product_name']}</span>
																	<span class="price">¥{pigcms{:floatval($meal['product_price'])}<if condition="$meal['has_format']"><span style="font-size:12px;color:#999;margin-left:20px;">多规格可选</span></if></span>
																	<span class="add_btn"></span>
																</div>
																<span class="buycar <if condition='$meal["has_format"]'>hasFormat</if>" data-id="{pigcms{$meal['product_id']}" data-name="{pigcms{$meal['product_name']}" data-price="{pigcms{:floatval($meal['product_price'])}" <if condition='!$meal["has_format"]'>data-stock="{pigcms{$meal.stock}"</if> data-mincount="1">来一份</span>
																<span class="buycar2" style="display: none;">已点</span>
															</a>
															<if condition='$meal["has_format"]'>
															<div class="spec-tip" data-spec_data="{pigcms{$meal.list_txt}" data-name="{pigcms{$meal['product_name']}" data-stock="{pigcms{$meal['stock']}">
																<div class="add-overlay">
																	<div class="close"></div>
																	<div class="content">
																		<table class="size-table">
																			<volist name="meal['spec_list']" id="spec">
																				<tr class="type-spec" data-key="{pigcms{$spec.name}" data-type="spec"> 
																					<td valign="top" class="attr-title">{pigcms{$spec.name}：</td> 
																					<td>
																						<php>$tmpSpecKey = 0;foreach($spec['list'] as $spec_list){ </php>
																							<span class="s-item <if condition="$tmpSpecKey eq 0">sec</if>" data-spec_list_id="{pigcms{$spec_list.id}" data-spec_id="{pigcms{$spec_list.sid}">{pigcms{$spec_list.name}</span> 
																						<php>$tmpSpecKey++;}</php>
																					</td> 
																				</tr>
																			</volist>
																			<volist name="meal['properties_list']" id="properties">
																				<tr class="type-properties" data-key="{pigcms{$properties.name}" data-num="{pigcms{$properties.num}" data-type="properties"> 
																					<td valign="top" class="attr-title">{pigcms{$properties.name}：</td> 
																					<td>
																						<php>$tmpPropertiesKey = 0;foreach($properties['val'] as $properties_list){</php>
																							<span class="s-item <if condition="$properties['num'] eq 1 && $tmpPropertiesKey eq 0">sec</if>">{pigcms{$properties_list}</span> 
																						<php>$tmpPropertiesKey++;}</php>
																					</td> 
																				</tr>
																			</volist>
																			<tr>  
																				<td valign="middle" width="40px">单价：</td>     
																				<td><span>￥<span class="product_price">{pigcms{:floatval($meal['product_price'])}</span></span></td>   
																			</tr> 
																			<tr> 
																				<td valign="middle">数量：</td>
																				<td> 
																					<div class="m-sel-icon" unselectable="on" style="width:auto;">
																						<strong class="minusfrcart"></strong>
																						<strong class="select_count">1</strong>
																						<strong class="addtocart"></strong>                                                                                          
																					</div> 
																				</td>
																			</tr>
																		</table>
																	</div>
																</div>
															</div>
															</if>
														</li>
													</volist>
												</ul>
											</div>
										</div>
										</if>
									</volist>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div id="con_one_2" style="display:none;">
					<div class="content_left">
						<div class="appraise_list cf">
							<div class="appraise_li">
								<div class="zzsc">
									<div class="tab">
										<div class="tab_title rate-filter__item">
											<a href="javascript:;" class="on" data-tab="all">全部</a>
											<a href="javascript:;" data-tab="high">好评</a>
											<a href="javascript:;" data-tab="mid">中评</a>
											<a href="javascript:;" data-tab="low">差评</a>
											<a href="javascript:;" data-tab="withpic">有图</a>
										</div>
										<div class="tab_form">
											<div class="form_sec">
												<select name="时间排序" class="select J-filter-ordertype">
													<option value="default">默认排序</option>
													<option value="time">时间排序</option>
													<option value="score">好评排序</option>
												</select>
											</div>
										</div>
									</div>
									<div class="content ratelist-content">
										<div class="appraise_li-list">
											<dl class="J-rate-list"></dl>
										</div>
										<div class="page J-rate-paginator cf"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</article>
</div>
<include file="Public:footer"/>
</body>
</html>
