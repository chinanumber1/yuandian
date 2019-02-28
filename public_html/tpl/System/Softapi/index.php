<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style.css"/>
		<title>后台管理 - {pigcms{$config.site_name}</title>
		<script type="text/javascript">if(self!=top){window.top.location.href = "{pigcms{:U('Index/index')}";}var selected_module="{pigcms{:strval($_GET['module'])}",selected_action="{pigcms{:strval($_GET['action'])}",selected_url="{pigcms{:urldecode(strval(htmlspecialchars_decode($_GET['url'])))}";</script>
		
		<script type="text/javascript">var isSoftView = <if condition="$_SESSION['soft_system']">true<else/>false</if>;</script>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.colorpicker.js"></script>
	</head>
	<body style="background:#E2E9EA;overflow:hidden;padding: 0;margin: 0;">
		<div id="Main_content">
			<div id="MainBox" style="margin:0px;padding:5px;float:none;width:auto;">
				<div class="main_box" style="margin-left:0px">
					<iframe name="main" id="Main" src="{pigcms{$_GET.loadUrl|htmlspecialchars_decode=###}" frameborder="false" scrolling="auto"  width="100%" height="auto" allowtransparency="true"></iframe>
				</div>
				<!--input type="button" value="gosofturl" onclick = "gosofturl('http://www.group.com/');" /><input type="button" value="屏蔽alert" onclick = "alert('alert');" />
				<input type="button" value="调用函数" onclick = "alert(msgbox( '123','456'));" /-->
			</div>
		</div>
		<script type="text/javascript" src="{pigcms{$static_path}js/index.js"></script>
		<script type="text/javascript">$('#Main').height($(window).height()-10);$(window).resize(function(){$('#Main').height($(window).height()-10);});
		</script>
	</body>
</html>