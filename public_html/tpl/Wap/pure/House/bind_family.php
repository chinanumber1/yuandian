<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>我的小区</title>
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
		
		.footerMenu.wap ul li{ width:50%;}
		.footerMenu.wap ul li a{ line-height:46px}
	</style>
</head>
<body>
	<if condition='$village_list'>
		<dl style="padding:0 10px;background-color:#fff;margin-top:10px;margin-bottom:10px;">
			<volist name='village_list' id='row'>
				<div id="spread_list" class="titleBorder">
					<div class="title">{pigcms{$row['village_name']}</div>
					<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
					
					<volist name='row["bind_list"]' id='bind_info'>
						<div>
							<div class="title">&nbsp;&nbsp;{pigcms{$bind_info['address']}&nbsp;&nbsp;
							<span>房主</span>
							
							
							<a href="javascript:void(0)" onclick="bind_family_info({pigcms{$bind_info['pigcms_id']})">绑定家属</a>
							</div>
						</div>
					</volist>
				</div>
			</volist>
		</dl>
	</if>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}js/common_wap.js"></script>
<script type="text/javascript">
var bind_family_info_url = "{pigcms{:U('bind_family_info')}";
function bind_family_info(pigcms_id){
	bind_family_info_url += '&pigcms_id='+pigcms_id;
	location.href=bind_family_info_url;
}
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