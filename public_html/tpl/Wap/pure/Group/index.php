<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$config.group_alias_name}列表</title>
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
			var open_extra_price =Number("{pigcms{$config.open_extra_price}");
			var extra_price_name ="{pigcms{$config.extra_price_alias_name}";
			var location_url = "{pigcms{:U('Group/ajaxList')}";
			var now_cat_url="<if condition="!empty($now_cat_url)">{pigcms{$now_cat_url}<else/>-1</if>";
			var now_area_url="<if condition="!empty($now_area_url) && $all_area_list">{pigcms{$now_area_url}<else/>-1</if>";
			var now_sort_id="<if condition="!empty($now_sort_array) AND $config.open_default_sort eq 0 AND $config.open_group_default_sort eq 0">{pigcms{$now_sort_array.sort_id}<else/>defaults</if>";
			<if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if>
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/dropdown.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/grouplist.js?210" charset="utf-8"></script>
		
	</head>
	<body>
		<section class="searchBar pageSliderHide <if condition="!$is_wexin_browser">wap</if>">
			<div class="searchBox">
				<form id="search-form" action="{pigcms{:U('Search/group')}" method="post">
					<input type="search" id="keyword" name="w" placeholder="请输入搜索词" autocomplete="off"/>
				</form>
			</div>
			<div class="voiceBtn"></div>
		</section>
		<section class="navBox pageSliderHide">
			<ul>
				<li class="dropdown-toggle caret category" data-nav="category">
					<span class="nav-head-name"><if condition="$now_category">{pigcms{$now_category.cat_name}<else/>全部分类</if></span>
				</li>
				<li class="dropdown-toggle caret biz subway" data-nav="biz">
					<span class="nav-head-name"><if condition="$now_area">{pigcms{$now_area.area_name}<else/>全城</if></span>
				</li>
				<li class="dropdown-toggle caret sort" data-nav="sort">
					<span class="nav-head-name">{pigcms{$now_sort_array.sort_value}</span>
				</li>
			</ul>
			<div class="dropdown-wrapper">
				<div class="dropdown-module">
					<div class="scroller-wrapper">
						<div id="dropdown_scroller" class="dropdown-scroller" style="overflow:hidden;">
							<div>
								<ul>
									<li class="category-wrapper">
										<ul class="dropdown-list">
											<li data-category-id="-1" <if condition="empty($top_category)">class="active"</if> onclick="list_location($(this));return false;"><span data-name="全部分类">全部分类</span></li>
											<volist name="all_category_list" id="vo">
												<li data-category-id="{pigcms{$vo.cat_url}" <if condition="$vo['cat_count'] gt 1">data-has-sub="true"<else/>onclick="list_location($(this));return false;"</if> class="<if condition="$vo['cat_count'] gt 1">right-arrow-point-right</if> <if condition="$top_category['cat_url'] eq $vo['cat_url']">active</if>">
													<span data-name="{pigcms{$vo.cat_name}">{pigcms{$vo.cat_name}</span>
													<if condition="$vo['cat_count'] gt 1"><span class="quantity"><b></b></span></if>
													<div class="sub_cat hide" style="display:none;">
														<if condition="$vo['cat_count'] gt 1">
															<ul class="dropdown-list sub-list">
																<li data-category-id="{pigcms{$vo.cat_url}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{pigcms{$vo.cat_name}">全部</span></div></li>
																<volist name="vo['category_list']" id="voo" key="j">
																	<li data-category-id="{pigcms{$voo.cat_url}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{pigcms{$voo.cat_name}">{pigcms{$voo.cat_name}</span></div></li>
																</volist>
															</ul>
														</if>
													</div>
												</li>
											</volist>
										</ul>
									</li>
									<if condition="$all_area_list">
										<li class="biz-wrapper">
											<ul class="dropdown-list">
												<li data-area-id="-1" <if condition="empty($now_area_url)">class="active"</if> onclick="list_location($(this));return false;"><span data-name="全城">全城</span></li>
												<volist name="all_area_list" id="vo">
													<li data-area-id="{pigcms{$vo.area_url}" <if condition="$vo['area_count'] gt 0">data-has-sub="true"<else/>onclick="list_location($(this));return false;"</if> class="<if condition="$vo['area_count'] gt 0">right-arrow-point-right</if> <if condition="$top_area['area_url'] eq $vo['area_url']">active</if>">
														<span>{pigcms{$vo.area_name}</span>
														<if condition="$vo['area_count'] gt 0"><span class="quantity"><b></b></span></if>
														<div class="sub_cat hide" style="display:none;">
															<if condition="$vo['area_count'] gt 0">
																<ul class="dropdown-list sub-list">
																	<li data-area-id="{pigcms{$vo.area_url}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{pigcms{$vo.area_name}">全部</span></div></li>
																	<volist name="vo['area_list']" id="voo" key="j">
																		<li data-area-id="{pigcms{$voo.area_url}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{pigcms{$voo.area_name}">{pigcms{$voo.area_name}</span></div></li>
																	</volist>
																</ul>
															</if>
														</div>
													</li>
												</volist>
											</ul>
										</li>
									</if>
									<li class="sort-wrapper">
										<ul class="dropdown-list">
											<volist name="sort_array" id="vo">
												<li data-sort-id="{pigcms{$vo.sort_id}" <if condition="$vo['sort_id'] eq $now_sort_array['sort_id']">class="active"</if> onclick="list_location($(this));return false;"><span data-name="{pigcms{$vo.sort_value}">{pigcms{$vo.sort_value}</span></li>
											</volist>
										</ul>
									</li>
								</ul>
							</div>
						</div>
						<div id="dropdown_sub_scroller" class="dropdown-sub-scroller"><div></div></div>
					</div>
				</div>
			</div>
		</section>
		<php>$no_footer = true;</php>
		<include file="Public:footer"/>
		<style>
			.pin_style{
				background-repeat: no-repeat;
				background-image:url({pigcms{$static_path}images/pin.png);
				width:100%;
				height:100%;
				position: absolute;
			}
			.line_m{text-decoration:line-through;display: inline;color: #ccc}
			.s_name{
				text-overflow: ellipsis;
				display: -webkit-box;
				-webkit-line-clamp: 1;
				-webkit-box-orient: vertical;
				overflow: hidden;
				width: 83%;
			}
			
			.discount{
				border: 1px solid #eda4a4;
				color: #cc0000;
				background-color: #fae6e6 !important;
			}
			.tag{
				border: 1px solid #fbd3a4;
				color: #f8a100;
				background-color:#fef3e6;
			}
		</style>
		<div id="container">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
				</div>
				<script id="storeListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
						<dd>
							<div class="brand link-url" data-url="{{ d.store_list[i].url }}">
								<div class="brandCon">{{ d.store_list[i].store_name }}<span class="location-right">{{ d.store_list[i].range_txt }}</span></div>
							</div>
							<ul class="goodList">
								{{# for(var j = 0, jlen = d.store_list[i].group_list.length; j < jlen; j++){ }}
									<li class="link-url" data-url="{{ d.store_list[i].group_list[j].url }}" {{# if(j > 1){ }}style="display:none;"{{# } }}>
										<div class="dealcard-img imgbox ">
											{{# if(d.store_list[i].group_list[j].pin_num > 0){ }}<div class="pin_style"></div>{{# } }}
											<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.store_list[i].group_list[j].list_pic) }}" alt="{{ d.store_list[i].group_list[j].group_name }}"/>
											{{# if(d.store_list[i].group_list[j].is_start == 0){ }}<i class="store_state">未开始</i>{{# } }}
										</div>
										<div class="dealcard-block-right">
											<div class="title">{{ d.store_list[i].group_list[j].group_name }}</div>
											<div class="price">
												<strong>{{ d.store_list[i].group_list[j].price }}</strong><span class="strong-color">元{{# if( d.store_list[i].group_list[j].extra_pay_price!=''&&open_extra_price){ }}+{{  d.store_list[i].group_list[j].extra_pay_price }}{{  extra_price_name }}{{# } }}</span><div class="line_m">{{ d.store_list[i].group_list[j].old_price }}元{{# if( d.store_list[i].group_list[j].extra_pay_price){ }}{{  d.store_list[i].group_list[j].extra_pay_price }}{{# } }}</div>
												{{# if(d.store_list[i].group_list[j].discount && d.store_list[i].group_list[j].vip_discount_type>0){ }}<span class="tag discount">{{ d.store_list[i].group_list[j].discount }}折</span>{{# } }}
												
												{{# if(d.store_list[i].group_list[j].wx_cheap){ }}<span class="tag">微信再减{{ d.store_list[i].group_list[j].wx_cheap }}元</span>{{# } }}<span class="line-right">
												{{ d.store_list[i].group_list[j].sale_txt }}</span>
											</div>
										</div>
									</li>
								{{# } }}
								{{# if(d.store_list[i].group_list.length > 2){ }}
									<li class="more">其他{{ d.store_list[i].group_list.length-2 }}个{pigcms{$config.group_alias_name}</li>
								{{# } }}
							</ul>
						</dd>
					{{# } }}
				</script>
				<script id="groupListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.group_list.length; i < len; i++){ }}
						<dd class="link-url" data-url="{{ d.group_list[i].url }}">
						{{# if(d.group_list[i].pin_num > 0){ }}<div class="pin_style"></div>{{# } }}
							<div class="dealcard-img imgbox">
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.group_list[i].list_pic) }}" alt="{{ d.group_list[i].s_name }}"/>
								{{# if(d.group_list[i].is_start == 0){ }}<i class="store_state">未开始</i>{{# } }}
							</div>
							<div class="dealcard-block-right">
								<div class="brand"><div class="s_name">{{ d.group_list[i].s_name }}</div> {{# if(d.group_list[i].juli){ }}<span class="location-right">{{ d.group_list[i].juli_txt }}</span>{{# } }}</div>
								<div class="title" style="padding-left:0px;">{{ d.group_list[i].intro }}</div>
								<div class="price">
									<strong>{{ d.group_list[i].price }}</strong><span class="strong-color">元{{# if( d.group_list[i].extra_pay_price!='' && open_extra_price){ }}{{  d.group_list[i].extra_pay_price }}{{# } }}</span>
									{{# if(d.group_list[i].discount && d.group_list[i].vip_discount_type>0){ }}<span class="tag discount">{{ d.group_list[i].discount }}折</span>{{# } }}
									
									{{# if(d.group_list[i].wx_cheap){ }}<span class="tag">微信再减{{ d.group_list[i].wx_cheap }}元</span>{{# }else{ }}<del>{{ d.group_list[i].old_price }}</del>{{# } }} {{# if( d.group_list[i].extra_pay_price!=''){ }}{{  d.group_list[i].extra_pay_price }}{{# } }}<span class="line-right">{{ d.group_list[i].sale_txt }}</span>
								</div>
							</div>
						</dd>
					{{# } }}
				</script>
				<section class="storeListBox listBox">
					<dl></dl>
					<div class="shade hide"></div>
					<div class="no-deals hide">暂无此类{pigcms{$config.group_alias_name}，请查看其他分类</div>
				</section>
				<div id="pullUp">
					<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
				</div>
			</div>
		</div>

		<script type="text/javascript">
		window.shareData = {
					"moduleName":"Group",
					"moduleID":"0",
					"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
					"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Group/index')}",
					"tTitle": "{pigcms{$config.group_alias_name}列表",
					"tContent": "{pigcms{$config.site_name}"
		};
		</script>
		{pigcms{$shareScript}
		{pigcms{$coupon_html}
	</body>
</html>