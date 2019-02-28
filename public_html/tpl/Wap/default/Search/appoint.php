<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$config.group_alias_name}搜索</title>
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
			var location_url="{pigcms{:U('Appointservice/search',array('w'=>urlencode($keywords)))}",now_sort="<if condition="!empty($now_sort)">{pigcms{$now_sort}<else/>defaults</if>";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/groupsearch.js?210" charset="utf-8"></script>
	</head>
	<body>
		<section class="searchBar pageSliderHide <if condition="!$is_wexin_browser">wap</if>" style="background-color:white;border-bottom:1px solid #edebeb;">
			<div class="searchBox">
				<form id="search-form" action="{pigcms{:U('Search/appoint')}" method="post">
					<input type="search" id="keyword" name="w" placeholder="请输入搜索词" autocomplete="off" value="{pigcms{$keywords}"/>
				</form>
			</div>
			<div class="voiceBtn"></div>
		</section>
		<section class="searchBox pageSliderHide">
			<ul>	
				<li class="dropdown-toggle active"><span class="nav-head-name">预约</span></li>
				<li class="dropdown-toggle link-url" data-url="{pigcms{:U('Search/appoint',array('w'=>urlencode($keywords)))}"><span class="nav-head-name">预约</span></li>
			</ul>
		</section>
		<div id="container">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
				</div>
				<section class="navBox">
					<ul style="border-top: 1px solid #edebeb;">	
						<li class="dropdown-toggle caret sort <if condition="$now_sort eq 'default'">active</if>" data-sort="default"><span class="nav-head-name">默认排序</span></li>
						<li class="dropdown-toggle caret sort <if condition="$now_sort eq 'appointNum'">active</if>" data-sort="hot"><span class="nav-head-name">预约最多</span></li>
						<li class="dropdown-toggle caret sort <if condition="$now_sort eq 'priceDesc'">active</if>" data-sort="price"><span class="nav-head-name">价格最低</span></li>
					</ul>
				</section>
				<script id="groupListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.group_list.length; i < len; i++){ }}
						<dd class="link-url" data-url="{{d.group_list[i].url }}">
							<div class="dealcard-img imgbox">
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.group_list[i].list_pic) }}" alt="{{d.group_list[i].appoint_name }}"/>
							</div>
							<div class="dealcard-block-right">									
								<div class="brand">{{d.group_list[i].appoint_name }} {{# if(d.group_list[i].juli){ }}<span class="location-right">约：{{d.group_list[i].juli }}km</span>{{# } }}</div>					
								<div class="title">{{d.group_list[i].appoint_content }}</div>
								<div class="price">
									{{# if(d.group_list[i].payment_money){ }}<strong>定金:￥{{d.group_list[i].payment_money }}</strong>&nbsp;{{# } }}
									<span class="tag">{{# if(d.group_list[i].appoint_type == 1){ }}上门{{# }else{ }}到店{{# } }}</span>&nbsp;
									{{# if(d.group_list[i].appoint_sum ){ }}<span class="line-right">已预约{{d.group_list[i].appoint_sum }}</span>{{# } }}
								</div>
							</div>
						</dd>
					{{# } }}
				</script>
				<section class="listBox">
					<dl class="dealcard">
						<volist name="group_list" id="vo">
							<dd class="link-url" data-url="{pigcms{$vo.url}">
								<div class="dealcard-img imgbox">
									<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{$vo.list_pic) }" alt="{pigcms{$vo.appoint_name}"/>
								</div>
								<div class="dealcard-block-right">									
									<div class="brand">{pigcms{$vo.appoint_name} <if condition="$vo.juli"><span class="location-right">约：{pigcms{:round($vo.juli/1000,1)}km</span></div>					
									<div class="title">{pigcms{$vo.appoint_content}</div>
									<div class="price">
										<if condition="$vo.payment_money"><strong>定金:￥{pigcms{$vo.payment_money}</strong>&nbsp;</if>
										<span class="tag"><if condition="$vo.appoint_type eq 1">上门<else />到店</if></span>&nbsp;
										<if condition="$vo.appoint_sum"><span class="line-right">已预约{pigcms{$vo.appoint_sum}</span></if>
									</div>
								</div>
							</dd>
						</volist>
					</dl>
					
					<div class="noMoreList <if condition="empty($group_list) || $totalPage gt 1">hide</if>">没有更多内容了!</div>
					<div class="shade hide"></div>
					<div class="no-deals <if condition="!empty($group_list)">hide</if>">没有找到相关的预约</div>
				</section>
				<div id="pullUp" <if condition="$totalPage lt 2">class="noMore loading" style="display:none;"</if>>
					<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
				</div>
			</div>
		</div>
		{pigcms{$shareScript}
	</body>
</html>