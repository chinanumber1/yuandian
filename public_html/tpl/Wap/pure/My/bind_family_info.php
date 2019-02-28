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
		.bind_phone{ height:3rem; line-height:3rem}
		.bind_phone input{ height:2rem; width:100%}
		.chk_bind{ height:2rem; background:green; border:none; border-radius:5px; margin:0 auto; color:#fff; display:block;}
	</style>
</head>
<body>
<dl style="padding:0 10px;background-color:#fff;margin-top:10px;margin-bottom:10px;">
	<div class="bind_phone">
		<input type="text" name="phone" placeholder="请输入绑定业主手机号" />
	</div>
	
	<div class="btn_bind">
		<input type="button" class="chk_bind" value="确认绑定" />
	</div>
</dl>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}js/common_wap.js"></script>
<script type="text/javascript">
$('.chk_bind').click(function(){
	var url = "{pigcms{:U('ajax_bind_family')}";
	var pigcms_id = "{pigcms{$_GET['pigcms_id']}" ? "{pigcms{$_GET['pigcms_id']}" : 0;
	$.post(url,{'phone': $('input[name="phone"]').val(),'pigcms_id': pigcms_id},function(data){
		alert(data.msg);
	},'json')
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