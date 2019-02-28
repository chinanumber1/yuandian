<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$config.store_alias_name}列表</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?222">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/list.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">
			var location_url = "{pigcms{:U('Merchant/ajaxList')}";
			var now_cat_url="<if condition="!empty($now_cat_url)">{pigcms{$now_cat_url}<else/>-1</if>";
			var now_area_url="<if condition="!empty($now_area_url) && $all_area_list">{pigcms{$now_area_url}<else/>-1</if>";
			var now_sort_id="<if condition="!empty($now_sort_array)">{pigcms{$now_sort_array.sort_id}<else/>defaults</if>";
			<if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if>
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/dropdown.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/grouplist.js?210" charset="utf-8"></script>
	</head>
	<body>
		<section class="searchBar pageSliderHide <if condition="!$is_wexin_browser">wap</if>">
			<div class="searchBox">
				<form id="search-form" action="" method="">
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
		<div id="container">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
				</div>
				<script id="groupListBoxTpl" type="text/html">
				{{# if(d.market_list.length>0){ }}
					{{# for(var i = 0, len = d.market_list.length; i < len; i++){ }}
					<dd class="link-url" data-url="{{ d.market_list[i].url }}">
						<div class="dealcard-img imgbox">
							<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.market_list[i].img) }}" alt="{{ d.market_list[i].market_name }}"/>
							<div style="display:block;position:absolute;top:0;left:0;background:url(/tpl/Wap/pure/static/img/market.png) no-repeat;height:65px;width:65px;background-size:100% 100%;"></div>
						</div>
						<div class="dealcard-block-right" style="font-family:'Microsoft YaHei' !important;">
							<div class="brand" style="padding:0px 8px 8px 1px;font-weight:bolder;">{{ d.market_list[i].market_name }}</div>
							<div class="price" style="margin-bottom:5px;font-size:14px;border-bottom:1px solid #f1f1f1;padding-bottom:5px;margin-right:10px;">{{# if(d.market_list[i].count != 0){ }} <span style="color:#ff4c42;font-size:18px;">{{ d.market_list[i].count }}</span>个优惠商家 {{# }else{ }} 没有优惠商家 {{# } }} <span style="color:#909090;float:right;font-size:12px;margin-top:6px;">{{ d.market_list[i].range_txt }}</span></div>
							<div class="price" style="margin-top:10px;">
								{{# if(d.market_list[i].introduce){ }}
									<div class="reteInfo_font">{{ d.market_list[i].introduce }}</div>
								{{# }else{ }}
									<div class="reteInfo_font">暂无</div>
								{{# } }}
							</div>
						</div>
					</dd>
					{{# } }}
					<div style="paading:2px;background-color:#f3f5f6;border-top:1px solid #d8d8d8;border-bottom:1px solid #d8d8d8;">&nbsp;</div>
				{{# } }}
					{{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
						<dd class="link-url" data-url="{{ d.store_list[i].url }}">
							<div class="dealcard-img imgbox">
								{{# if(d.store_list[i].isverify == 1){ }}
									<img src="./static/images/kd_rec.png" style="width:41px;height:15px;position: absolute;z-index: 15;margin:2px 0 0 0">
								{{# } }}
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.store_list[i].list_pic) }}" style="margin-left:0px;" alt="{{ d.store_list[i].store_name }}"/>
							</div>
							<div class="dealcard-block-right" style="font-family:'Microsoft YaHei' !important;">
								<div class="brand" style="padding:0px 8px 0px 1px;font-weight:bolder;float:left;">{{ d.store_list[i].store_name }}</div>
								<div class="brand" style="float:right;">
									{{# if(d.store_list[i].have_group == 1){ }}
										<i class="text-icon order-jiudian order-icon" style="background-color:#EAAD0D;width:20px;height:22px;font-size:14px;">{pigcms{$config.group_alias_name}</i>
									{{# } }}
									{{# if(d.store_list[i].have_meal == 1){ }}
										<i class="text-icon order-jiudian order-icon" style="width:20px;height:22px;font-size:14px;">{pigcms{$config.meal_alias_name}</i>
									{{# } }}
									{{# if(d.store_list[i].now_appoint){ }}
										<i class="text-icon order-jiudian order-icon" style="background-color:#0092DE;width:20px;height:22px;font-size:14px;">{pigcms{$config.appoint_alias_name}</i>
									{{# } }}
								</div>
								<div style="clear:both"></div>
								<div class="price" style="margin-bottom:5px;font-size:14px;">商家有<span style="color: #fe5842;font-size: 18px;">{{ d.store_list[i].fans_count }}</span>个粉丝</div>
								<div class="rateInfo" style="margin-top:3px;float:left;">
								{{# if(d.store_list[i].pingjun){ }}
									<div class="starIconBg">
										<div class="starIcon" style="width:{{ d.store_list[i].xing }}%"></div>
									</div>
									<div class="starText">{{ d.store_list[i].pingjun }}</div>
								{{# }else{ }}
									<span style="color:#999">暂无评分</span>
								{{# } }}
								</div>
								<div class="rateInfo" style="float:right;margin-top:4px;">
									<span style="color:#909090;float:right;font-size:12px">{{ d.store_list[i].range_txt }}</span>
								</div>
							</div>
						</dd>
					{{# } }}
				</script>
				<section class="storeListBox listBox">
					<dl></dl>
					<div class="shade hide"></div>
					<div class="no-deals hide">暂无店铺</div>
				</section>
				<div id="pullUp">
					<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$(function(){
			$('#keyword').bind('input', function(e){				
				var search_txt = $(this).val();
				location_url ='{pigcms{:U('Merchant/ajaxList')}&w='+search_txt;
				now_page = 0;
				getList(false)
				$('.no-deals').css('display','none')
			});
			$('#search-form').submit(function(){
				return false;
			});
		})
		window.shareData = {
					"moduleName":"Merchant",
					"moduleID":"0",
					"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
					"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Merchant/store_list')}",
					"tTitle": "{pigcms{$config.store_alias_name}列表",
					"tContent": "{pigcms{$config.site_name}"
		};
		</script>
		{pigcms{$shareScript}
		<script type="text/javascript">
		if(window.__wxjs_environment === 'miniprogram'){
			wx.ready(function(){
				wx.miniProgram.switchTab({url: '/pages/store/index'});
			});
		}
		</script>
	</body>
</html>