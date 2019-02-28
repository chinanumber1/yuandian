<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>分享抢券</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/share_coupon.css?215"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	</head>
	<body>

		<section class="coupens_content">
			<if condition="$share_coupon_adver">
				<img style="width:100%" src = "{pigcms{$share_coupon_adver.pic}">
			<else />
			
			<h1 style="font-style:normal">{pigcms{$config.share_friend_coupon_notice}</h1>
			</if>
			<div class="content_text">
				<div class="content_text_left">
					<ul>
						<li><span>￥</span><b>{pigcms{$coupon.discount|floatval}</b></li>
						<li><span>满{pigcms{$coupon.order_money|floatval}元减{pigcms{$coupon.discount|floatval}元</span></li>
					</ul>
				</div>
				<div class="content_text_right">
					<ul>
						<li><h3>{pigcms{$coupon.name}</h3></li>
						<li><span>使用平台:{pigcms{$coupon.platform}</span></li>
						<li><span>使用类别:{pigcms{$coupon.category_txt}</span></li>
						<li style="color:#f00;">{pigcms{$coupon.start_time|date="Y-m-d",###}至{pigcms{$coupon.end_time|date="Y-m-d",###}</li>
					</ul>
				</div>
				<div class="content_text_buttom">
					<h3>恭喜你获得一张平台优惠劵</h3>
					<p>已存入您的账户</p>
				</div>
				<div class="content_text_last">
					<a  href="{pigcms{$coupon.coupon_url}">立即使用</a>
				</div>
			</div>
			<span class="content_text_end">{pigcms{$config.share_friend_coupon_des}</span>
		</section>
		<script type="text/javascript">
			
			$(function(){
				$('.open_coupon').click(function(){
					
				});
			});
		
		</script>
		{pigcms{$hideScript}
	</body>
</html>