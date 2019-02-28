<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>社区新闻</title>
        </if>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?213"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">var location_url = "{pigcms{:U('House/village_ajax_news')}";var backUrl = "{pigcms{:U('House/village',array('village_id'=>$now_village['village_id']))}"; var now_url="{pigcms{:U('House/village_newslist',array('village_id'=>$now_village['village_id']))}"</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_newslist.js?20180714" charset="utf-8"></script>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>社区新闻</header>
    </if>
		<div id="container">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新页面</span>
				</div>
				<section class="villageBox newsBox newsListBox">
					<div class="headBox newsheader">
						<div class="swiper-container swiper-container1">
							<ul class="swiper-wrapper">
								<volist name="category_list" id="vo">
									<li class="swiper-slide <if condition="$vo.cat_id eq $_GET['cat_id']">on</if>" data-cat_id="{pigcms{$vo.cat_id}">
										{pigcms{$vo.cat_name}
									</li>
								</volist>
							</ul>
						</div>
					</div>
					<dl>
						<volist name="news_list" id="vo">
							<dd class="link-url" data-url="{pigcms{:U('House/village_news',array('village_id'=>$now_village['village_id'],'news_id'=>$vo['news_id']))}">
								<div>{pigcms{$vo.title}</div>
								<span class="right">{pigcms{$vo.add_time|date='m-d H:i',###}</span>
							</dd>
						</volist>
					</dl>
				</section>
				<script id="newsListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.length; i < len; i++){ }}
						<dd class="link-url" data-url="{pigcms{:U('House/village_news',array('village_id'=>$now_village['village_id']))}&news_id={{ d[i].news_id }}">
							<div>{{ d[i].title }}</div>
							<span class="right">{{ d[i].add_time_txt }}</span>
						</dd>
					{{# } }}
				</script>
                <if condition="!$is_app_browser">
                    <div id="pullUp" style="bottom:-60px;">
                        <img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
                    </div>
                </if>
				
			</div>
		</div>
		{pigcms{$shareScript}
	</body>
</html>