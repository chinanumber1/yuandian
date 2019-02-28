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
			<h1 class="page__title">我的车辆</h1>
		</div>
		<div class="weui-panel weui-panel_access">
            <div class="weui-panel__bd">
				<volist name="car_list" id="vo">
					<a href="{pigcms{:U('edit',array('car_id'=>$vo['car_id']))}" class="weui-media-box weui-media-box_appmsg weui-cell_access flex">
						<div class="weui-media-box_text weui-cell__bd">
							<h4 class="weui-media-box__title">{pigcms{$vo.car_area}{pigcms{$vo.car_num}</h4>
							<p class="weui-media-box__desc">{pigcms{$vo.car_phone}&nbsp;<if condition="$vo['tip_type'] eq 1">先通知后显示手机号<else/>直接显示号码</if></p>
						</div>
						<span class="weui-cell__ft"></span>
					</a>
				</volist>
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
		<div class="weui-btn-area">
            <a href="{pigcms{:U('add')}" class="weui-btn weui-btn_primary mt40">添加车辆</a>
        </div>
		<div class="page msg_success js_show" <if condition="!empty($car_list)">style="display:none;"</if>>
			<div class="weui-msg">
				<div class="weui-msg__icon-area"><i class="weui-icon-info weui-icon_msg"></i></div>
				<div class="weui-msg__text-area">
					<h2 class="weui-msg__title">车库空空如也</h2>
					<p class="weui-msg__desc">请点击下方按钮先添加车辆充实一下您的车库</p>
				</div>
				<div class="weui-msg__opr-area">
					<p class="weui-btn-area">
						<a href="{pigcms{:U('add')}" class="weui-btn weui-btn_primary">添加车辆</a>
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
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('City_car/index')}",
				"tTitle": "快捷挪车-{pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};
		</script>
		{pigcms{$shareScript}
	</body>
</html>