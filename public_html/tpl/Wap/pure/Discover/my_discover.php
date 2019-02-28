<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>我的发现</title>
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
		
		<div id="my_add" class="titleBorder">
			<div class="title">我的发布</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>

        <div id="my_collection" class="titleBorder">
            <div class="title">我的收藏</div>
            <img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
        </div>

<!--        <div id="find_list" class="titleBorder">-->
<!--            <div class="title">发现首页</div>-->
<!--            <img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>-->
<!--        </div>-->
		
	</dl>
	
	
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			
			$('#my_add').on('click',function(){
				location.href =	"{pigcms{:U('Discover/my_discover_list')}";
			});
            $('#my_collection').on('click',function(){
                location.href =	"{pigcms{:U('Discover/my_collection')}";
            });
            // $('#find_list').on('click',function(){
            //     location.href =	"{pigcms{:U('Discover/discover_index')}";
            // });
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