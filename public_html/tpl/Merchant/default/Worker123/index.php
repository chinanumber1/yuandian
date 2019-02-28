<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffIndex.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffIndex.css"/>
	</head>
	<body>
		<div class="infoBox">
			<div class="logo">
				<img src="{pigcms{$config.site_url}/upload/appoint/{pigcms{$worker_session.avatar_path}"/>
			</div>
			<div class="text">
				{pigcms{$worker_session.name}
				<br/>
				<span>{pigcms{$store.name}</span>
			</div>
		</div>
		<div class="pageBg"></div>
		<div class="pageLink">
			<ul>
				<li class="appoint" data-url="{pigcms{:U('appoint_list')}">
					<div class="icon"></div>
					<div class="text">{pigcms{$config.appoint_alias_name}</div>
				</li>
				<li class="logout" data-confirm="您确定要退出吗？" data-url="{pigcms{:U('logout')}">
					<div class="icon"></div>
					<div class="text">退出</div>
				</li>
			</ul>
		</div>
	</body>
</html>