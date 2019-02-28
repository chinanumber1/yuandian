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
		.village_title{ font-weight:bold}
		.red{ color:red}
		.fr{ float:right}
	</style>
</head>
<body>
	<dl style="padding:0 10px;background-color:#fff;margin-top:10px;margin-bottom:10px;">
		
		<volist name='village_list' id='row'>
			<div id="spread_list" class="titleBorder">
				<div class="title village_title">{pigcms{$row['village_name']}</div>
				<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
				
				<volist name='row["bind_list"]' id='bind_info'>
					<div>
						<div class="title">
							<span>{pigcms{$bind_info['address']}</span>
						<if condition='$bind_info["type"] eq 0'>
							<span class="red">房主</span>
						<elseif condition='$bind_info["type"] eq 1'/>
							<span class="red">家人</span>
						<else />
							<span class="red">租客</span>
						</if>
						
						
						<if condition='$bind_info["status"] eq 0'>
							<span class="fr">禁止</span>
						<elseif condition='$bind_info["status"] eq 1'/>
							<a class="fr" href="javascript:void(0)" onclick="bind_del({pigcms{$bind_info['pigcms_id']})">解绑</a>
						<else />
							<span>审核中</span>
						</if>
						</div>
					</div>
				</volist>
			</div>
		</volist>
	</dl>
<footer class="footerMenu wap">
    <ul>
		<li>
			<a href="{pigcms{:U('House/village_list',array('choose'=>1))}">加入房屋</a>
	    </li>
		<li>
			<a href="{pigcms{:U('bind_family')}">绑定家属</a>
		</li>
    </ul>
</footer>
	
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script type="text/javascript">
		var url = "{pigcms{:U('bind_del')}";
		function bind_del(pigcms_id){
			$.post(url,{'pigcms_id':pigcms_id},function(data){
				alert(data.msg);
				if(data['status']){
					location.reload();
				}
			},'json')
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