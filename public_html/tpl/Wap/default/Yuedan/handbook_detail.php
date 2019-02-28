<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<title>{pigcms{$handbook_info.title}</title>
		<style>
			*{margin: 0;padding: 0;}
			ul,ol,li{list-style:none;}
			body{background: #f4f4f4;}	
			header {
			    height: 50px;
			    background-color: #06c1ae;
			    color: white;
			    line-height: 50px;
			    text-align: center;
			    position: relative;
			    font-size: 16px;
			}
			header #backBtn {
			    position: absolute;
			    width: 50px;
			    height: 100%;
			    top: 0;
			    left: 0;
			}
			header #backBtn:after {
			    display: block;
			    content: "";
			    border-top: 2px solid white;
			    border-left: 2px solid white;
			    width: 12px;
			    height: 12px;
			    -webkit-transform: rotate(315deg);
			    background-color: transparent;
			    position: absolute;
			    top: 19px;
			    left: 19px;
			}
			.content{
				margin:13px 4%;
			}
		</style>
	</head>
	<body style="background: #ffffff;">
		<header class="pageSliderHide"><div id="backBtn" onclick="javascript :history.back(-1);"></div>{pigcms{$handbook_info.title}</header>
		<div class="content"> {pigcms{$handbook_info.content|htmlspecialchars_decode} </div>
	</body>
</html>