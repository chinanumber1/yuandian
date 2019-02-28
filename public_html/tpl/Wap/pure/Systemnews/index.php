<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <title>平台快报</title>     
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
		<script type="text/javascript" src="{pigcms{$static_path}/layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">
			var location_url = "{pigcms{:U('Systemnews/ajaxList')}";
			var backUrl = "{pigcms{:C('config.site_url')}/wap.php";
			var now_cat_id = "{pigcms{$now_cat_id}";
			var count = "{pigcms{$count}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/newslist.js?215" charset="utf-8"></script>
		<style>
			.newsListBox dd div{
				font-size:12px;
			}
			.newsListBox dd div{
				margin-right:90px;
				height:21px;
				overflow:hidden;
				word-break:keep-all;
				white-space:nowrap;
				text-overflow:ellipsis;
			}
		</style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>平台快报</header>
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
								<volist name="category" id="vo">
									<li class="swiper-slide <if condition="$i eq 1">on</if>" data-cat_id="{pigcms{$vo.id}">{pigcms{$vo.name}</li>
								</volist>
							</ul>
						</div>
					</div>
					<dl>
						<!--<volist name="news_list" id="vo">
							<dd class="link-url" data-url="{pigcms{:U('Systemnews/news',array('id'=>$vo['id']))}">
								<div>{pigcms{$vo.title}</div>
								<span class="right">{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</span>
							</dd>
						</volist>-->
					</dl>
					
				</section>
				<script id="newsListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.length; i < len; i++){ }}
						<dd class="link-url" data-url="{pigcms{:U('Systemnews/news')}&id={{ d[i].id }}">
							<div>{{ d[i].title }}</div>
							<span class="right">{{ d[i].add_time }}</span>
						</dd>
					{{# } }}
				</script>
			
                <if condition="!$is_app_browser">
                    <div id="pullUp" style="bottom:-60px;">
						<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
                        <!--<img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>-->
                    </div>
                </if>
				
			</div>
		</div>
		<script type="text/javascript">
			window.shareData = {  
				"moduleName":"Systemnews",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Systemnews/index')}",
				"tTitle": "平台快报 - {pigcms{$config.site_name}",
				"tContent": "点击查看快报详细内容"
			};
		</script>
		{pigcms{$shareScript}
	</body>
</html>