<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>团购列表</title>
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
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/laytpl.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">
			var location_url = "{pigcms{:U('Group/ajaxList')}";
			var now_cat_url="<if condition="!empty($now_cat_url)">{pigcms{$now_cat_url}<else/>-1</if>";
			var now_area_url="<if condition="!empty($now_area_url) && $all_area_list">{pigcms{$now_area_url}<else/>-1</if>";
			var now_sort_id="<if condition="!empty($now_sort_array)">{pigcms{$now_sort_array.sort_id}<else/>defaults</if>";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/dropdown.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/grouplist.js?210" charset="utf-8"></script>
	</head>
	<body>
		<div id="container">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
				</div>
				<section class="searchBar">
					<div class="searchBox">
						<form id="search-form" action="<if condition="$_GET['type'] neq 'meal'">{pigcms{:U('Search/group')}<else/>{pigcms{:U('Search/meal')}</if>" method="post">
							<input type="search" id="keyword" name="w" placeholder="请输入搜索词" autocomplete="off"/>
						</form>
					</div>
					<div class="voiceBtn"></div>
				</section>
				<section class="navBox">
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
								<div id="dropdown_sub_scroller" class="dropdown-sub-scroller"><div></div></div>
							</div>
						</div>
					</div>
				</section>
				<script id="storeListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
						<dd>
							<div class="brand link-url" data-url="{{ d.store_list[i].url }}">
								<div class="brandCon">{{ d.store_list[i].store_name }}<span class="location-right">{{ d.store_list[i].range_txt }}</span></div>
							</div>
							<ul class="goodList">
								{{# for(var j = 0, jlen = d.store_list[i].group_list.length; j < jlen; j++){ }}
									<li class="link-url" data-url="{{ d.store_list[i].group_list[j].url }}" {{# if(j > 1){ }}style="display:none;"{{# } }}>
										<div class="dealcard-img imgbox">
											<img src="{{ d.store_list[i].group_list[j].list_pic }}" alt="{{ d.store_list[i].group_list[j].group_name }}"/>
										</div>
										<div class="dealcard-block-right">
											<div class="title">{{ d.store_list[i].group_list[j].group_name }}</div>
											<div class="price">
												<strong>{{ d.store_list[i].group_list[j].price }}</strong><span class="strong-color">元</span>{{# if(d.store_list[i].group_list[j].wx_cheap){ }}<span class="tag">微信再减{{ d.store_list[i].group_list[j].wx_cheap }}元</span>{{# } }}<span class="line-right">已售{{ d.store_list[i].group_list[j].sale_count }}</span>
											</div>
										</div>
									</li>
								{{# } }}
								{{# if(d.store_list[i].group_list.length > 2){ }}
									<li class="more">其他{{ d.store_list[i].group_list.length-2 }}个团购</li>
								{{# } }}
							</ul>
						</dd>
					{{# } }}
				</script>
				<script id="groupListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.group_list.length; i < len; i++){ }}
						<dd class="link-url" data-url="{{ d.group_list[i].url }}">
							<div class="dealcard-img imgbox">
								<img src="{{ d.group_list[i].list_pic }}" alt="{{ d.group_list[i].s_name }}"/>
							</div>
							<div class="dealcard-block-right">									
								<div class="brand">{{# if(d.group_list[i].tuan_type != 2){ }} {{ d.group_list[i].merchant_name }} {{# }else{ }} {{ d.group_list[i].s_name }} {{# } }}</div>								
								<div class="title">{{ d.group_list[i].group_name }}</div>
								<div class="price">
									<strong>{{ d.group_list[i].price }}</strong><span class="strong-color">元</span>{{# if(d.group_list[i].wx_cheap){ }}<span class="tag">微信再减{{ d.group_list[i].wx_cheap }}元</span>{{# }else{ }}<del>{{ d.group_list[i].old_price }}</del>{{# } }} <span class="line-right">已售{{ d.group_list[i].sale_count }}</span>
								</div>
							</div>
						</dd>
					{{# } }}
				</script>
				<section class="storeListBox listBox">
					<dl></dl>
					<div class="shade hide"></div>
					<div class="no-deals hide">暂无此类团购，请查看其他分类</div>
				</section>
			</div>
		</div>
	</body>
</html>