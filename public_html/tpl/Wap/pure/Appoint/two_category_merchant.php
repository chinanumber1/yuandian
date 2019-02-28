<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$cat_info['cat_name']}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/list.css?216"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?211" charset="utf-8"></script>
		<script type="text/javascript">
			var location_url = "{pigcms{:U('Appoint/ajaxList')}";
			//var now_area_url="-1";
			//var now_sort_id="defaults";
			var now_area_url="<if condition="!empty($now_area_url) && $all_area_list">{pigcms{$now_area_url}<else/>-1</if>";
			var now_sort_id="<if condition="!empty($now_sort_array)">{pigcms{$now_sort_array.sort_id}<else/>defaults</if>";
			var now_cat_id = "{pigcms{$_GET['cat_id']}";
			<if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if>
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/dropdown.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/appointlist.js?210" charset="utf-8"></script>
		<style>
			.listBox{
				padding: 0 8px;
				background-color:#F6F6F7;
			}
			.listBox dl dd {
				width: 50%;
				float: left;
				margin-bottom: 10px;
				background-color: #F6F6F7;
				padding: 0;
			}
			.listBox dl dd .box {
				margin-right: 4px;
				overflow: hidden;
				background-color: white;
			}
			.listBox dl dd .box .imgBox {
				width: 100%;
				height: 110px;
			}
			.listBox dl dd .box .imgBox img {
				width: 100%;
				height: 100%;
			}
			.listBox dl dd .box .catName {
				margin: 6px;
				text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:100%;
			}
			.listBox dl dd .box .catPrice {
				margin:6px;
			}
			.listBox dl dd .box .catPrice .right{
				float:right;
				color:#999;
				font-size:12px;
				line-height: 21px;
			}
			.listBox dl dd:nth-of-type(even) .box {
				margin-right: 0px;
				margin-left: 4px;
			}
		</style>
	</head>
	<body>
		<section class="searchBar pageSliderHide wap">
			<div class="searchBox">
				<form id="search-form" action="/wap.php?g=Wap&c=Search&a=appoint" method="post">
					<input type="search" id="keyword" name="w" placeholder="请输入搜索词" autocomplete="off"/>
				</form>
			</div>
			<div class="voiceBtn"></div>
		</section>
		<section class="navBox pageSliderHide">
			<ul>
				<li class="dropdown-toggle caret biz subway" data-nav="biz">
					<span class="nav-head-name"><if condition="$now_area">{pigcms{$now_area.area_name}<else/>全城</if></span>
				</li>
				<li class="dropdown-toggle caret sort" data-nav="sort">
					<span class="nav-head-name">默认排序</span>
				</li>
			</ul>
			<div class="dropdown-wrapper">
				<div class="dropdown-module">
					<div class="scroller-wrapper">
						<div id="dropdown_scroller" class="dropdown-scroller" style="overflow:hidden;">
							<div>
								<ul>
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
		<div id="container" style="display:none;">
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
										<div class="dealcard-img imgbox">
											<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.store_list[i].group_list[j].list_pic) }}" alt="{{ d.store_list[i].group_list[j].group_name }}"/>
											{{# if(d.store_list[i].group_list[j].is_start == 0){ }}<i class="store_state">未开始</i>{{# } }}
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
									<li class="more">其他{{ d.store_list[i].group_list.length-2 }}个{pigcms{$config.group_alias_name}</li>
								{{# } }}
							</ul>
						</dd>
					{{# } }}
				</script>
				<script id="groupListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.group_list.length; i < len; i++){ }}
						<dd class="link-url" data-url="{{ d.group_list[i].url }}">
								<div class="box">
									<div class="imgBox">
										<img src="{{ d.group_list[i].list_pic }}"/>
									</div>
									<div class="catName">{{ d.group_list[i].appoint_name }}</div>
									<div class="catPrice">{{# if(d.group_list[i].is_appoint_price == 0){ }}面议{{# }else{ }}￥{{ d.group_list[i].appoint_price }}
									{{# if(d.group_list[i].extra_pay_price>0){ }}
										+ {{ d.group_list[i].extra_pay_price }}{pigcms{$config.extra_price_alias_name}
									{{# } }}
									{{# } }}<div class="right">{{# if(d.group_list[i].juli && d.group_list[i].lat!=null){ }}{{d.group_list[i].juli }}{{# }else{ }}已预订{{d.group_list[i].appoint_sum }}人{{# } }}</div>
									
									<div class="catName">{{# if(d.group_list[i].appoint_type == 0){ }}<img src="{pigcms{$static_path}images/daodian.png" width="50px" height="20px" />{{# }else{ }}<img src="{pigcms{$static_path}images/shangmen.png" width="50px" height="20px" />{{# } }}</div>
									</div>
									<!-- 距离排序显示 多少米，其他排序显示多少人约过，0人次的不显示 -->
								</div>
							</dd>
					{{# } }}
				</script>
				<section class="storeListBox listBox">
					<dl>
					</dl>
					<div class="shade hide"></div>
					<div class="no-deals hide">暂无此类{pigcms{$config.appoint_alias_name}，请查看其他分类</div>
				</section>
				<div id="pullUp">
					<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
				</div>
			</div>
		</div>
		<php>$no_footer = true;</php>
		
		<script type="text/javascript">
			window.shareData = {  
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Home/index')}",
				"tTitle": "{pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};
		</script>
		{pigcms{$shareScript}
	</body>
</html>