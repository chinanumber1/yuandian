<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <title>平台粉丝群发</title>     
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
			
		</script>
		
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
        <header class="pageSliderHide"><div id="backBtn"></div>平台粉丝群发</header>
    </if>
		<div id="container">
			<div id="scroller">
				
				<section class="villageBox newsBox newsListBox">
				
					<dl>
						<volist name="list" id="vo">
							<dd class="link-url" data-url="{pigcms{:U('Article/index',array('imid'=>$vo['pigcms_id']))}">
								<div>{pigcms{$vo.title}</div>
								<span class="right">{pigcms{$vo.dateline|date='Y-m-d H:i:s',###}</span>
							</dd>
						</volist>
					</dl>
					
				</section>
			</div>
		</div>
		<script type="text/javascript">
			window.shareData = {  
				"moduleName":"Article",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Article/article_list',array('id'=>$_GET['id']))}",
				"tTitle": "平台粉丝群发",
				"tContent": "点击查看群发详细内容"
			};
		</script>
		{pigcms{$shareScript}
	</body>
</html>