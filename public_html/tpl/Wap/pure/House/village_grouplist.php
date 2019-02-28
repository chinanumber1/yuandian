<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head> 
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>推荐{pigcms{$config.group_alias_name}</title>
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
			var location_url = "{pigcms{:U('House/village_grouplist',array('village_id'=>$now_village['village_id']))}",totalPage = '{pigcms{$totalPage}';var backUrl = "{pigcms{:U('House/village',array('village_id'=>$now_village['village_id']))}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_grouplist.js?213" charset="utf-8"></script>
		<style>
			body{background-color:#f4f4f4;}
			.group{border:none;}
			.dealcard{padding:0px;}
			.dealcard dd{padding:5px;}
		</style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>推荐{pigcms{$config.group_alias_name}</header>
    </if>
		<div id="container">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新页面</span>
				</div>
					<section class="group">
						<dl class="likeBox dealcard" id="listDom">
						</dl>
					</section>
				<div id="pullUp" style="display:none;">
					<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
				</div>
				<script id="BoxTpl" type="text/html">
					{{# for(var i = 0, len = d.length; i < len; i++){ }}
						<dd class="link-url" data-url="{pigcms{$config.site_url}/wap.php?c=Group&a=detail&group_id={{ d[i].group_id }}">
							<div class="dealcard-img imgbox">
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d[i].list_pic) }}" alt="{{ d[i].s_name }}"/>
							</div>
							<div class="dealcard-block-right">
								<div class="brand">{{# if(d[i].tuan_type != 2){ }} {{ d[i].merchant_name }}  {{# if(d[i].range){ }}<span class="location-right">{{ d[i].range }}</span>{{# } }}   {{# }else{ }} {{ d[i].s_name }} {{# } }}</div>
								<div class="title">{{ d[i].group_name }}</div>
								<div class="price">
									<strong>{{ d[i].price }}</strong><span class="strong-color">元</span>{{# if(d[i].wx_cheap){ }}<span class="tag">微信再减{{ d[i].wx_cheap }}元</span>{{# }else{ }}<del>{{ d[i].old_price }}</del>{{# } }} <span class="line-right">已售{{ d[i].sale_count }}</span>
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