<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><if condition="$title"><?php echo ($title);?><else/>跳转提示</if></title>
	</head>
	<body id="body">
		<style>
		body {margin:0;padding:0;background:#f8f8f8}
		div { font-size:12px;}
		a:link {COLOR: #0a4173; text-decoration:none;}
		a:visited {COLOR: #0a4173; text-decoration:none;}
		a:hover {COLOR: #1274ba; text-decoration:none;}
		a:active {COLOR: #1274ba; text-decoration:none;}
		</style>
		<div style="background:#fff;font-size:14px;width:600px; margin:60px auto; line-height:48px;min-height:48px;_height:48px;text-align:center;padding:60px 30px;border:5px solid #f3f3f3">
			<present name="message">
				<img src="{pigcms{$config.site_url}/static/js/artdialog/skins/icons/face-smile.png" align="absmiddle" />&nbsp;&nbsp;<?php echo($message); ?><else/><img src="{pigcms{$config.site_url}/static/js/artdialog/skins/icons/face-sad.png" align="absmiddle" />&nbsp;&nbsp;<?php echo($error); ?>
			<if condition="$title eq '网站关闭'">
				<style>.div_box{display:inline-block; padding-left:30px;font-size:16px; } .div_box a{border:1px solid #95C6E6;padding:6px;border-radius:6px;}</style>
				<div style="margin-top:30px;">
					<div class="div_box">您还可以继续访问：</div>
					<div class="div_box div_url"><a href="/merchant.php?g=Merchant&c=Login&a=index" target="_blank">商家中心</a></div>
					<div class="div_box div_url"><a href="/store.php?g=Merchant&c=Store&a=login" target="_blank">店员中心</a></div>
					<div class="div_box div_url"><a href="/shequ.php?g=House&c=Login&a=index" target="_blank">社区中心</a></div>
				</div>
			</if>
			</present>
			<if condition="$jumpUrl neq '-1'"><br/><span style="font-size:12px;color:#999"><b id="wait"><?php echo($waitSecond); ?></b> 秒后将自动跳转，如果您的浏览器不能跳转</span> <a style="font-size:12px;" id="href" href="<?php echo($jumpUrl);?>">请点击</a></if>
		</div>
		<if condition="$jumpUrl neq '-1'">
			<script type="text/javascript">
				(function(){
					var wait = document.getElementById('wait'),href = document.getElementById('href').href;
					var interval = setInterval(function(){
						var time = --wait.innerHTML;
						if(time <= 0) {
							location.href = href;
							clearInterval(interval);
						};
					}, 1000);
				})();
			</script>
		</if>
	</body>
</html>