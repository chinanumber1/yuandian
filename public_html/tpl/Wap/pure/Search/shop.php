<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$config.shop_alias_name}搜索</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/list.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">
			var location_url="{pigcms{$config.site_url}/appapi.php?c=Shop&a=search&key="+"{pigcms{:urlencode($keywords)}",now_sort="<if condition="!empty($now_sort)">{pigcms{$now_sort}<else/>defaults</if>";
			//location_url+='&lat={pigcms{$user_long.lat}&long={pigcms{$user_long.long}'
			var user_long, deliverName = "{pigcms{$config['deliver_name']}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/shopsearch.js?210" charset="utf-8"></script>
		<style>
			.goodList li{
					padding: 8px 0 6px 0px;
			}
			.goodList{
				border-top:none;
			}
			.goodList .goods_name{
				margin-left:70px;
				font-size:14px;
			}
			.goodList .goods_sale{
				margin-left:70px;
				font-size:12px;
					color: #999;
			}
			.goodList .goods_price{
				float:right;
				margin-top:-40px;
				color:#06c1ae;
			}
			.showMoreGoods{
				    border-bottom: none;
				height: 26px;
				line-height: 26px;
				text-align: center;
				color: #999;
			}
			.showMoreGoods:after{
				content: "";
				display: inline-block;
				margin-left: 6px;
				width: 8px;
				height: 8px;
				border: 1px solid #999;
				border-width: 0 1px 1px 0;
				border-top-width: 0px;
				border-right-width: 1px;
				border-bottom-width: 1px;
				border-left-width: 0px;
				-webkit-transform: rotate(45deg);
				margin-top: 6px;
				vertical-align: top;
			}
			.hideMoreGoods:after{
				content: "";
				display: inline-block;
				margin-left: 6px;
				width: 8px;
				height: 8px;
				border: 1px solid #999;
				border-width: 0 1px 1px 0;
				border-top-width: 0px;
				border-right-width: 1px;
				border-bottom-width: 1px;
				border-left-width: 0px;
				-webkit-transform: rotate(225deg);
				margin-top: 6px;
				vertical-align: top;
			}
		
		</style>
	</head>
	<body>
		<section class="searchBar pageSliderHide <if condition="!$is_wexin_browser">wap</if>" style="background-color:white;border-bottom:1px solid #edebeb;">
			<div class="searchBox">
				<form id="search-form" action="{pigcms{:U('Search/shop')}" method="post">
					<input type="search" id="keyword" name="w" placeholder="请输入搜索词" autocomplete="off" value="{pigcms{$keywords}"/>
				</form>
			</div>
			<div class="voiceBtn"></div>
		</section>
		<section class="searchBox pageSliderHide">
			<ul>
				<li class="dropdown-toggle active"  data-url-type="openLeftWindow"><span class="nav-head-name">{pigcms{$config.shop_alias_name}</span></li>
				<li class="dropdown-toggle link-url" data-url="{pigcms{:U('Search/group',array('w'=>urlencode($keywords)))}"><span class="nav-head-name">{pigcms{$config.group_alias_name}</span></li>

				<li class="dropdown-toggle link-url" data-url="{pigcms{:U('Search/meal',array('w'=>urlencode($keywords)))}"><span class="nav-head-name">{pigcms{$config.meal_alias_name}</span></li>
				<if condition="$config.appoint_alias_name neq ''"><li class="dropdown-toggle link-url" data-url="{pigcms{:U('Search/appoint',array('w'=>urlencode($keywords)))}" ><span class="nav-head-name">{pigcms{$config.appoint_alias_name}</span></li></if>
                   <li class="dropdown-toggle link-url" data-url="{pigcms{:U('Search/worker',array('w'=>urlencode($keywords)))}" style="display:none"><span class="nav-head-name">技师</span></li>
			</ul>
		</section>
		<php>$no_footer = true;</php>
		<include file="Public:footer"/>
		<div id="container">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
				</div>
				
				
				<script id="groupListBoxTpl" type="text/html">
					
				
					{{# for(var i = 0, len = d.length; i <len; i++){ }}
					{{# console.log(i) }}
							
						<dd class="page-link link-url" data-url="/wap.php?c=Shop&a=index#shop-{{ d[i].store_id }}" data-url-type="openRightFloatWindow" {{# if(d[i].is_close==1){ }}style="opacity:0.6;"{{# } }}>
							<div class="dealcard-img imgbox">
								{{# if(d[i].isverify == 1){ }}
									<img src="./static/images/kd_rec.png" style="width: 41px;height: 15px;position: absolute;z-index: 15;margin: 2px 0 0 0;">
								{{# } }}
								<img src="{{ d[i].image }}" alt="{{ d[i].name }}">
								{{# if(d[i].is_close==1){ }}<div class="closeTip">休息中</div>{{# } }}
							</div>
							<div class="dealcard-block-right">
								<div class="brand">{{ d[i].name }}<em class="location-right">{{# if(user_long != '0'){ }}{{ d[i].range }}{{# } }}</em></div>
								<div class="title {{# if(!d[i].delivery){ }}pick{{# } }}">
									<span class="star"><i class="full"></i><i class="full"></i><i class="full"></i><i class="half"></i><i></i></span>
                                    {{# if (d[i].month_sale_count > 0) { }}
                                    <span>已售{{ d[i].month_sale_count }}单</span>
                                    {{# } else if (d[i].is_new) { }}
                                    <span>新店上市</span>
                                    {{# } else { }}
                                    <span>　</span>
                                    {{# } }}
									{{# if(d[i].delivery){ }}
										<em class="location-right">{{ d[i].delivery_time }}分钟</em>
									{{# }else{ }}
										<em class="location-right">门店自提</em>
									{{# } }}
								</div>
								{{# if(d[i].delivery){ }}
									<div class="price">
										<span>起送价 ￥{{ d[i].delivery_price }}</span><span class="delivery">配送费 ￥{{ d[i].delivery_money }}</span>
										{{# if(d[i].delivery_system){ }}
											<em class="location-right">{{ deliverName }}</em>
										{{# }else{ }}
											<em class="location-right">商家配送</em>
										{{# } }}
									</div>
								{{# } }}
							</div>
								{{# if(d[i].coupon_count > 0){ }}
									<div class="coupon {{# if(d[i].coupon_count > 2){ }}hasMore{{# } }}">
										<ul>
											{{# for(var e = 0, lenc = d[i].coupon_list.length ; e < lenc; e++){ }}
													
												{{# var tmpCouponList =  d[i].coupon_list[e]; }}
												{{# if(tmpCouponList['type']=='invoice'){ }}
													<li><em class="merchant_invoice"></em>{{ tmpCouponList['value'] }}</li>
												{{# } }}
												
												{{# if(tmpCouponList['type']=='discount'){ }}
													<li><em class="merchant_discount"></em>{{ tmpCouponList['value'] }}</li>
												{{# } }}
												{{# if(tmpCouponList['type']=='minus'){ }}
													<li><em class="merchant_minus"></em>{{ tmpCouponList['value'] }}</li>
												{{# } }}
												{{# if(tmpCouponList['type']=='newuser'){ }}
													<li><em class="newuser"></em>{{ tmpCouponList['value'] }}</li>
												{{# } }}
												{{# if(tmpCouponList['type']=='delivery'){ }}
													<li><em class="delivery"></em>{{ tmpCouponList['value'] }}</li>
												{{# } }}
												{{# if(tmpCouponList['type']=='system_minus'){ }}
												<li><em class="system_minus"></em>{{ tmpCouponList['value'] }}</li>
												{{# } }}
												{{# if(tmpCouponList['type']=='system_newuser'){ }}
													<li><em class="system_newuser"></em>{{ tmpCouponList['value'] }}</li>
												{{# } }}
											{{# } }}
										</ul>
										{{# if(d[i].coupon_count > 2){ }}
											<div class="more">{{ d[i].coupon_count }}个活动</div>
										{{# } }}
									</div>
								{{# } }}	
								
								
						</dd>
						<!--{{# if(d[i].goods_list.length > 0){ }}
									<div class="goodList goods_detail {{# if(d[i].goods_list.length  > 2){ }}hasMore{{# } }}">
										<ul>
											{{# for(var c = 0, lens = d[i].goods_list.length ; c < lens; c++){ }}
												<li {{# if(c > 1){ }}style="display:none"{{# } }}>
													<div class="goods_name">{{ d[i].goods_list[c]['search_name'] }}</div>
													<div class="goods_sale">已售{{ d[i].goods_list[c]['sell_count'] }}</div>
													<div class="goods_price">￥{{ d[i].goods_list[c]['price'] }}</div>
												</li>
											{{# } }}
											{{# if(d[i].goods_list.length > 2){ }}
											<li><div class="more showMoreGoods">其他{{ d[i].goods_list.length-2 }}个商品</div></li>
											{{# } }}
										</ul>
										
									</div>
						{{# } }}-->
					{{# } }}
				</script>
				<section class="listBox">
					<dl class="dealcard">
						<!--<volist name="shop_list" id="vo">
							<dd class="link-url" data-url="{pigcms{$vo.url}">
								<if condition="$vo['isverify'] eq  1">
									<img src="./static/images/kd_rec.png" style="width:41px;height:15px;position: absolute;z-index: 15;margin:2px 0 0 0">
								</if>
								<div class="dealcard-img imgbox">
									<img src="{pigcms{$vo.image}" alt="{pigcms{$vo.name}"/>
								</div>
								<div class="dealcard-block-right">
									<div class="brand">{pigcms{$vo.name}</div>
									<div class="title">{pigcms{$vo.txt_info}</div>
									<div class="price">
										<if condition="$vo['mean_money']"><strong>{pigcms{$vo.mean_money}</strong><span class="strong-color">元(人均)</span></if>&nbsp;<span class="line-right">已售{pigcms{$vo.sale_count}</span>
									</div>
								</div>
							</dd>
						</volist>-->
					</dl>
					<!--<div class="noMoreList <if condition="empty($group_list) || $totalPage gt 1">hide</if>">没有更多内容了!</div>
					<div class="shade hide"></div>
					<div class="no-deals <if condition="!empty($group_list)">hide</if>">没有找到相关的{pigcms{$config.shop_alias_name}</div>-->
				</section>
				<div id="pullUp" <if condition="$totalPage lt 2">class="noMore loading" style="display:none;"</if>>
					<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
				</div>
			</div>
		</div>
		{pigcms{$hideScript}
	</body>
</html>