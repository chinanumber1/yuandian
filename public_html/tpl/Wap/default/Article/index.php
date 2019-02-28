<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>{pigcms{$nowImage['title']}</title>
		<meta name="description" content="{pigcms{$config.seo_description}">
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name='apple-touch-fullscreen' content='yes'>
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">

		<link href="{pigcms{$static_path}css/mp_news.css" rel="stylesheet"/>
		<style>
			#listHeader {
				top: 0;
				height: 50px;
				background-color: #06c1ae;
				display: -webkit-box;
				/*position: fixed;*/
				width: 100%;
				z-index: 21;
				color:#fff;
			}
			header{
				    height: 50px;
    background-color: #06c1ae;
    color: white;
    line-height: 50px;
    text-align: center;
    position: relative;
    font-size: 16px;
			}
		</style>
	</head>
		<body id="activity-detail" class=" ">
	

		<php>if(isset($_GET['preview']) && $nowImage['preview']){</php>
			<header class="pageSliderHide">该文章为预览状态</header>
		<php>}</php>
		<div class="rich_media container">
			<div class="header" style="display:none;"></div>
			<div class="rich_media_inner content">
				<h2 class="rich_media_title" id="activity-name">{pigcms{$nowImage['title']}</h2>
				<div class="rich_media_meta_list">
					<em id="post-date" class="rich_media_meta text">{pigcms{$nowImage['now']}</em> 
					<em class="rich_media_meta text"></em> 
					<a class="rich_media_meta link nickname js-no-follow js-open-follow" href="<if condition="$config['wechat_follow_txt_url']">{pigcms{$config.wechat_follow_txt_url}<else/>javascript:;</if>" id="post-user">{pigcms{$nowImage['author']}</a>
				</div>
				<div id="page-content" class="content">
					<div id="img-content">
						<if condition="$nowImage['cover_pic'] && $nowImage['is_show']">
						<div class="rich_media_thumb" id="media">
							<img onerror="this.parentNode.removeChild(this)" src="{pigcms{$nowImage['cover_pic']}">
						</div>
						</if>
						<div class="rich_media_content" id="js_content">{pigcms{$nowImage['content']|htmlspecialchars_decode=ENT_QUOTES}</div>
						<if condition="$nowImage['url']">
						<div class="rich_media_tool" id="js_toobar">
							<a class="media_tool_meta meta_primary" href="{pigcms{$nowImage['url']}">阅读原文</a>
						</div>
						</if>
					</div>
				</div>
			</div>
		</div>
		<div style="display:none;">{pigcms{$config.wap_site_footer}</div>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script>
			$('.rich_media_inner').css('min-height',$(window).height()+'px');
			$('iframe').each(function(i,item){
				var src = $(item).attr('src');
				if(src.indexOf('https://v.qq.com') == 0){
					$(item).height(($(window).width()-30)*9/16);
				}
			});
		</script>
		<script type="text/javascript">
		window.shareData = {  
		            "moduleName":"Article",
		            "moduleID":"0",
		            "imgUrl": '<if condition="strpos($nowImage['cover_pic'],'http://') heq 0">{pigcms{$nowImage['cover_pic']}<else/>{pigcms{$config.site_url}{pigcms{$nowImage['cover_pic']}</if>', 
		            "sendFriendLink": "{pigcms{$config.site_url}{pigcms{$url}",
		            "tTitle": "{pigcms{$nowImage['title']}",
		            "tContent": "{pigcms{$nowImage['digest']}"
		};
		</script>
		{pigcms{$shareScript}
	</body>
</html>