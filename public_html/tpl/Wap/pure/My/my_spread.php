<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>账户管理</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?211" charset="utf-8"></script>
    <style>
	    .titleImg{
			width:25px;
			height:25px;
			margin-right:10px;
	    }
	    .titleBorder{
			padding-bottom:10px;
			border-bottom:1px solid #e5e5e5;
	    }
	    .title{
			padding-top:12px;
			width:95%;
	    }
	    .imgRirht{
			float:right;
			margin-top:-19px;
			width:10px;
	    }
	</style>
</head>
<body>
	<dl style="padding:0 10px;background-color:#fff;margin-top:10px;margin-bottom:10px;">
		
		<div id="spread_list" class="titleBorder">
			<div class="title">我的推广佣金记录</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>
		
		<div id="spread_user" class="titleBorder">
			<div class="title">我的推广用户</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>
		<if condition="!$is_app_browser || $now_user['openid'] neq ''">
		<div id="spread_qrcode" class="titleBorder">
			<div class="title">我的推广二维码</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>
		</if>
		<if condition="$config.open_extra_price eq 0">
		<div id="spread_change" class="titleBorder">
			<div class="title">佣金过户</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>
		<div id="my_settlement_user" class="titleBorder">
			<div class="title">我的结算用户</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>
		</if>
		
		<if condition="$config['open_score_fenrun'] eq 1 && $is_app_browser">
		<div id="my_spread_code" class="titleBorder">
			<div class="title">我的推广码</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>
		</if>
		<if condition="$config.open_distributor eq 1">
		<div id="system_card" class="titleBorder">
			<div class="title">平台会员卡</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>
		</if>
	</dl>
	
	
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			
			$('#spread_list').on('click',function(){
				location.href =	"{pigcms{:U('My/spread_list')}";
			});
			$('#spread_user').on('click',function(){
				location.href =	"{pigcms{:U('My/spread_user_list')}";
			});
			$('#spread_qrcode').on('click',function(){
				location.href =	"{pigcms{:U('My/my_spread_qrcode')}";
			});
			$('#spread_change').on('click',function(){
				location.href =	"{pigcms{:U('My/my_spread_change')}";
			});
			$('#my_settlement_user').on('click',function(){
				location.href =	"{pigcms{:U('My/my_settlement_user')}";
			});
			$('#my_spread_code').on('click',function(){
				location.href =	"{pigcms{:U('My/my_spread_code')}";
			});
			$('#system_card').on('click',function(){
				location.href =	"{pigcms{:U('My/levelUpdate')}";
			});
		</script>
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