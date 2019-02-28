<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <title>电脑版群文件 | {pigcms{$config.site_name}</title>
    <script src="{pigcms{$static_public}js/jquery.min.js"></script>
	<script>
		$(function(){
			$('#imIframe').height($(window).height());
			$('#imIframe').width($(window).width());
		});
	</script>
</head>
<body style="overflow:hidden;margin:0;padding:0;">
	<iframe id="imIframe" src="{pigcms{$kf_url}" style="border:none;margin:0;padding:0;"></iframe>
</body>
</html>