<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>车牌号找车</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}weui/weui.css"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/city_car.css?216"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?2112222" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">var noAnimate = true;</script>
	</head>
	<body>
		<div class="page__hd add_hd">
			<h1 class="page__title">领取车贴图 ({pigcms{$now_car.car_area}{pigcms{$now_car.car_num})</h1>
		</div>
		<div class="page__bd">
			<div style="text-align:center;">
				<img src="{pigcms{$now_car_pic}"/>
			</div>
			<p class="page__desc mt40">
				1. 长按图片可以保存到手机<br/>
				2. 可以将图片打印成彩色纸或照片纸，使用胶水和静电贴粘在车玻璃上，用户微信扫码即可方便安全的通知挪车。<br/>
				<if condition="$config['open_user_spread']">
				3. 用户扫码即成为您的推广粉丝，您可享受平台推广福利。<br/>
				<a class="weui-btn weui-btn_mini weui-btn_primary" href="{pigcms{:U('My/my_spread')}" style="margin-top:40px;">查看我的推广</a>
				</if>
				<a href="{pigcms{:U('index')}" class="weui-btn weui-btn_mini weui-btn_default">回到车辆列表</a>
			</p>
		</div>
		{pigcms{$hideScript}
	</body>
</html>