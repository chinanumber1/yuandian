<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>小区管家</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name='apple-touch-fullscreen' content='yes' />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />

		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/new_village.css" />
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
		<style type="text/css">
			header{background:none}
		</style>
	</head>

	<body>
		<div id="container">
			<div id="scroller" class="village_my">
				<div class="cat-top cat-top2">
					<if condition="!$is_app_browser">
                    <header class="pageSliderHide"><div id="backBtn"></div>小区管家</header>
                    </if>
				</div>
				<div class="cat-content">
					<ul>
						<li onclick="location.href='{pigcms{:U('village_my_repairlists',array('village_id'=>$_GET['village_id']))}'">
							<p><img src="{pigcms{$static_path}img/house_index_10.png" />物业报修</p>
						</li>
						
						<li onclick="location.href='{pigcms{:U('village_my_suggestlist',array('village_id'=>$_GET['village_id']))}'">
							<p><img src="{pigcms{$static_path}img/house_index_11.png" />投诉建议</p>
						</li>
					</ul>
				</div>
			</div>

			<div id="pullUp" style="bottom:-60px;">
				<img src="/static/logo.png" style="width:130px;height:40px;margin-top:10px" />
			</div>
		</div>
<include file="House:footer"/>
		{pigcms{$shareScript}
		</footer>
	</body>

</html>