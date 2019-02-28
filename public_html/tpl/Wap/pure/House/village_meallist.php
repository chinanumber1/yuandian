<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>推荐{pigcms{$config.meal_alias_name}</title>
        </if>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">
			var location_url = "{pigcms{:U('House/village_meallist',array('village_id'=>$now_village['village_id']))}",totalPage = {pigcms{$totalPage|default=1};var backUrl = "{pigcms{:U('House/village',array('village_id'=>$now_village['village_id']))}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_grouplist.js?213" charset="utf-8"></script>
		<style>
			body{background-color:#f4f4f4;}
			.meal{border:none;}
			.dealcard{padding:0px;}
			.dealcard dd{padding:8px;}
		</style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>推荐{pigcms{$config.meal_alias_name}</header>
    </if>
		<div id="container">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新页面</span>
				</div>
				<if condition="$store_list">
					<section class="meal">
						<dl class="likeBox dealcard" id="listDom">
							<!-- <volist name="store_list" id="vo">
								<dd class="link-url" data-url="{pigcms{$config.site_url}/wap.php?c=Foodshop&a=shop&store_id={pigcms{$vo.store_id}">
									<div class="dealcard-img imgbox">
										<img src="/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.name}"/>
									</div> 
									<div class="dealcard-block-right">
										<div class="brand">{pigcms{$vo.name} <if condition="$vo['range']"><span class="location-right">{pigcms{$vo.range}</span></if></div>
										<div class="title" style="font-size:14px;margin:4px 0;">{pigcms{$vo.adress}<if condition="$vo['mean_money']">|人均{pigcms{$vo.mean_money}元</if></div>
										<div class="price">
											<if condition="$vo['store_type'] eq 0 || $vo['store_type'] eq 1"><span class="imgLabel daodian"></span></if><if condition="$vo['store_type'] eq 0 || $vo['store_type'] eq 2"><span class="imgLabel waisong"></span></if>
											<if condition="$vo['sale_count']"><span class="line-right">已售{pigcms{$vo.sale_count}</span></if>
										</div>
									</div>
								</dd>
							</volist>
							<if condition="$totalPage eq 1">
								<dd class="noMore">更多商户正在入驻，敬请期待!</dd>
							</if> -->
						</dl>
					</section>
				</if>
				<div id="pullUp" <if condition="$totalPage eq 1">style="display:none;"</if>>
					<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
				</div>
				<script id="BoxTpl" type="text/html">
					{{# for(var i = 0, len = d.length; i < len; i++){ }}
						<dd class="link-url" data-url="{{ d[i].wap_url }}">
							<div class="dealcard-img imgbox">
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d[i].list_pic) }}" alt="{{ d[i].name }}"/>
							</div>
							<div class="dealcard-block-right">									
								<div class="brand">{{ d[i].name }} {{# if(d[i].range){ }}<span class="location-right">{{ d[i].range }}</span>{{# } }}</div>				
								<div class="title" style="font-size:14px;margin:4px 0;">{{ d[i].adress }}{{# if(d[i].mean_money){ }}|人均{{ d[i].mean_money }}元{{# } }}</div>
								<div class="price">
									{{# if(d[i].store_type == '0' || d[i].store_type == '1'){ }}<span class="imgLabel daodian"></span>{{# } }}{{# if(d[i].store_type == '0' || d[i].store_type == '2'){ }}<span class="imgLabel waisong"></span>{{# } }}
									{{# if(d[i].sale_count){ }}<span class="line-right">已售{{ d[i].sale_count }}</span>{{# } }}
								</div>
							</div>
						</dd>
					{{# } }}
				</script>
			</div>
		</div>
		{pigcms{$shareScript}
	</body>
</html>