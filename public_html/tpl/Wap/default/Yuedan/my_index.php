<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>个人中心</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/person_center.css"/>
		<script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
		
	</head>
	<body>
	
		<header>
				<if condition="$userInfo['avatar']">
					<img src="{pigcms{$userInfo['avatar']}"/>
				<else/>
					<img src="{pigcms{$static_path}yuedan/imanges/user_avatar.jpg"/>
				</if>
			
			<ul>
				<li>{pigcms{$userInfo['nickname']}</li>
				<li>{pigcms{$userInfo['phone']}</li>
			</ul>
		</header>

		<section class="content">
			<ul>
				<li class="after"><a href="{pigcms{:U('My/my_money')}"><i></i> 我的余额 <span class="rg">{pigcms{$userInfo['now_money']}  <b></b></span></a></li>
				<li class="after"><a href="{pigcms{:U('authentication')}"><i></i> 个人认证  <b class="rg"></b></a></li>
				<li class="after"><a href="{pigcms{:U('my_order')}"><i></i> 服务订单  <b class="rg"></b></a></li>
				<li class="after"><a href="{pigcms{:U('Yuedan/my_service_list')}"><i></i> 技能管理  <b class="rg"></b></a></li>
				<li class="after"><a href="{pigcms{:U('Yuedan/my_collection')}"><i></i> 我的收藏  <b class="rg"></b></a></li>
				<li class="after"><a href="{pigcms{:U('Yuedan/adress')}"><i></i> 预约地址  <b class="rg"></b></a></li>
				<li class="after"><a href="{pigcms{:U('Yuedan/handbook')}"><i></i> 约单手册  <b class="rg"></b></a></li>
				<li class="after"><a href="{pigcms{:U('Yuedan/grade')}"><i></i> 等级购买  <b class="rg"></b></a></li>
			</ul>
		</section>

		<div class="bottom">
			<div class="index"> <dd class="icon1"></dd> <dd>首页</dd> </div>
			<div class="release"> <dd class="icon2"></dd> <dd>发布</dd> </div>
			<div class="person active"> <dd class="icon3"></dd> <dd>我的</dd> </div>
		</div>

		<script type="text/javascript">
			$('.index').click(function(e){
				location.href="{pigcms{:U('index')}";
			});
			$('.release').click(function(e){
				location.href="{pigcms{:U('release')}";
			});
			$('.person').click(function(e){
				location.href="{pigcms{:U('my_index')}";
			});
		</script>
	</body>
</html>
