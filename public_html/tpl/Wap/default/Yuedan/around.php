<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>附近服务</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="http://o2otest.weihubao.com/tpl/Wap/pure/static/css/common.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}yuedan/js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}yuedan/js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}yuedan/js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">var user_long = '0',user_lat  = '0';var static_path = "{pigcms{$static_path}yuedan/";</script>
		<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1"></script>
		<script type="text/javascript" src="{pigcms{$static_path}yuedan/js/service_around.js?222" charset="utf-8"></script>



		<!-- <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/common.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}yuedan/js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}yuedan/js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}yuedan/js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">var user_long = '0',user_lat  = '0';var static_path = "{pigcms{$static_path}yuedan/";</script>
		<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1"></script>
		<script type="text/javascript" src="{pigcms{$static_path}yuedan/js/service_around.js?222" charset="utf-8"></script> -->


		<style>
			.BMap_Marker img{
				width: 50px;
				height: 50px;
				border-radius: 50%;
			}
			#listBtn{background:url(http://hf.pigcms.com/tpl/Wap/pure/static/img/listBtn.png) no-repeat;background-size:100%;width:35px;height:35px;right:8px;bottom:15px;position:absolute;z-index:10;}
			#listBg{position:fixed;top:0;left:0;bottom:0;padding:0;z-index:998;width:100%;background-color:rgba(0,0,0,0.5);display:none;}
			#listList{position:fixed;top:10%;left:10%;bottom:10%;right:10%;z-index:999;background-color:white;border-radius:5px;overflow:hidden;display:none;}
			#listList dl{background-color:#F3F3F3;}
			#listList dd{border-bottom:1px solid #D6D6D6;padding:6px 12px;}
			#listList dd:last-child{border-bottom:none;}
			.windowBox.link-url a{
				display: -webkit-flex;
			    display: flex;
			    -webkit-box-pack: justify;
			    -webkit-justify-content: space-between;
			    justify-content: space-between;
			    -webkit-box-align: center;
			    -webkit-align-items: center;
			    align-items: center
			}
			.windowBox.link-url a div:last-child{
				width: 60%;
			}
		</style>
	</head>
	<body>
		<div id="container">
			<div id="scroller">
				<div id="around-map"></div>
			</div>
		</div>
		<div id="listBtn"></div>
		<div id="listBg"></div>
		<div id="listList">
			<div>
				<dl></dl>
			</div>
		</div>
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
	</body>
</html>