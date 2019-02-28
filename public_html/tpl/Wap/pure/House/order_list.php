<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>个人中心</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name='apple-touch-fullscreen' content='yes' />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />

		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/new_village.css" />
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
	</head>

	<body>
		<div id="container">
			<div id="scroller" class="village_my">
				<nav>
					<section class="link-url" data-url="{pigcms{:U('My/group_order_list')}"><span><img src="{pigcms{$static_path}images/tuan.png" /></span>
						<p>{pigcms{$config.group_alias_name}订单</p>
					</section>
					<section class="link-url" data-url="{pigcms{:U('My/shop_order_list')}"><span><img src="{pigcms{$static_path}images/dian.png" /></span>
						<p>{pigcms{$config.shop_alias_name}订单</p>
					</section>
					<section class="link-url" data-url="{pigcms{:U('My/appoint_order_list')}"><span><img src="{pigcms{$static_path}images/yuyue.png" /></span>
						<p>{pigcms{$config.appoint_alias_name}订单</p>
					</section>
				</nav>
			</div>

			<div id="pullUp" style="bottom:-60px;">
				<img src="/static/logo.png" style="width:130px;height:40px;margin-top:10px" />
			</div>
		</div>

		<footer class="footerMenu wap house">
			<div class="footer_top"></div>
			<ul>
				<li>
					<a href="/wap.php?g=Wap&c=House&a=village&village_id=1"><em class="home"></em>
						<p>首页</p>
					</a>
				</li>
				<li>
					<a href="/wap.php?g=Wap&c=Houseservice&a=index&village_id=1"><em class="group"></em>
						<p>便民</p>
					</a>
				</li>
				<li class="phoneBtn">
					<a href="/wap.php?g=Wap&c=Housemarket&a=index&village_id=1"><em class="marketBtn"></em>
						<p>社区超市</p>
					</a>
				</li>
				<li>
					<a href="/wap.php?g=Wap&c=Bbs&a=web_index&village_id=1&referer=%2Fwap.php%3Fg%3DWap%26c%3DHouse%26a%3Dvillage%26village_id%3D1"><em class="bbs"></em>
						<p>邻里</p>
					</a>
				</li>
				<li>
					<a class="active" href="/wap.php?g=Wap&c=House&a=village_my&village_id=1"><em class="my"></em>
						<p>我的</p>
					</a>
				</li>
			</ul>
		</footer>
	</body>

</html>