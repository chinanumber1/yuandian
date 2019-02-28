<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>平台快报</title>
		<meta name="description" content="{pigcms{$config.seo_description}">
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name='apple-touch-fullscreen' content='yes'>
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		
		<link href="{pigcms{$static_path}css/mp_news.css" rel="stylesheet"/>
		<style>img{width:100%;height:auto;}</style>
	</head>
		<body id="activity-detail" class=" ">
		<div class="rich_media container">
			<div class="header" style="display:none;"></div>
			<div class="rich_media_inner content">
				<h2 class="rich_media_title" id="activity-name">{pigcms{$news.title}</h2>
				<div class="rich_media_meta_list">
					<em id="post-date" class="rich_media_meta text">{pigcms{$news['add_time']|date='Y-m-d H:i:s',###}</em> 
					<span><a href="{pigcms{:U('Home/index')}" style="font-size:12px;color:blue;">{pigcms{$config.wechat_name}</a></span>
				</div>
				<div id="page-content" class="content">
					<div id="img-content">{pigcms{$news.content}</div>
				</div>
			</div>
		</div>
		<div style="display:none;">{pigcms{$config.wap_site_footer}</div>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script>
			$('.rich_media_inner').css('min-height',$(window).height()+'px');
		</script>
		<script type="text/javascript">
		window.shareData = {  
		            "moduleName":"Systemnews",
		            "moduleID":"0",
		            "imgUrl": '<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>', 
		            "sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Systemnews/news', array('id' => $news['id']))}",
		            "tTitle": "{pigcms{$news['title']}",
		            "tContent": "点击查看快报详细内容"
		};
		</script>
		{pigcms{$shareScript}
	</body>
</html>