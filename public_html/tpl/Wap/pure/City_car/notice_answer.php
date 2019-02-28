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
		<script type="text/javascript" src="{pigcms{$static_path}js/city_car.js?2112222" charset="utf-8"></script>
	</head>
	<body>
		<div class="page__hd add_hd">
			<h1 class="page__title">添加车辆</h1>
		</div>
		
		<div class="page <if condition="$is_answer">msg_success<else/>msg_warn</if> js_show">
			<div class="weui-msg">
				<div class="weui-msg__icon-area"><i class="weui-icon-<if condition="$is_answer">success<else/>warn</if> weui-icon_msg"></i></div>
				<div class="weui-msg__text-area">
					<h2 class="weui-msg__title">{pigcms{$answer_tip}</h2>
					<p class="weui-msg__desc"><if condition="$is_answer">请您尽快回到您的车辆，进行挪车<elseif condition="$now_notice['answer_time']"/>应答时间：{pigcms{:date('m-d H:i',$now_notice['answer_time'])}</if></p>
				</div>
				<div class="weui-msg__opr-area">
					<p class="weui-btn-area">
						<a href="{pigcms{:U('index')}" class="weui-btn weui-btn_default" id="">回到车辆列表</a>
					</p>
				</div>
				<div class="weui-msg__extra-area">
					<div class="weui-footer">
						<p class="weui-footer__links">
							<a href="{pigcms{:U('Home/index')}" class="weui-footer__link">平台首页</a>
						</p>
						<p class="weui-footer__text">© {pigcms{$config.wechat_name}</p>
					</div>
				</div>
			</div>
		</div>
		{pigcms{$hideScript}
	</body>
</html>