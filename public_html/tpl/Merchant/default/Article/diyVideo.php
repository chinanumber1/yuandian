<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>自定义插件</title>
	<link href="/static/img/css/reset.css" rel="stylesheet" type="text/css" />
	<link href="/static/img/css/codemirror.css" rel="stylesheet" type="text/css"/>
	<link href="/static/img/css/farbtastic.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<div class="big_main">
		<div class="m">
			<div style="float: right;width:100%;padding:10px 0;margin:0 auto;text-align :center;">
				视频链接：<input id="preview_content" type="text" />
				<input id="preview_btok" type="button" class="btn" value="插入到编辑器" onclick=""/>
			</div>
		</div>
		<div>视频地址找寻方法：<br/>1，打开腾讯视频网站。<br/>2，打开需要上传的视频<br />3，视频左下角有(分享)<br/>4，复制&#60;iframe开头的链接。</div>
		<img src="static/kindeditor/themes/default/diyVideo.png" />
	</div>
<script src="/static/js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script src="/static/img/js/diyVideo.js?ver=9" type="text/javascript"></script>
</body>
</html>