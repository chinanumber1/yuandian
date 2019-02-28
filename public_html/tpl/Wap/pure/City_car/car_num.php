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
		<script type="text/javascript" src="{pigcms{$static_path}js/city_car_num.js?2112222" charset="utf-8"></script>
	</head>
	<body>
		<div class="page__hd add_hd">
			<h1 class="page__title">车主联系方式({pigcms{$now_car.car_area}{pigcms{$now_car.car_num})</h1>
		</div>
		<div class="page__bd">
			<if condition="$now_car['tip_type'] eq 1">
				<div class="weui-btn-area">
					<a class="weui-btn weui-btn_primary" href="javascript:" data-car_id="{pigcms{$now_car.car_id}" data-url="{pigcms{:U('notice_car')}" data-notice_url="{pigcms{:U('notice_loop')}" id="car_num_see">通知车主<span id="wait_time" style="font-size:14px;"></span></a>
				</div>
			</if>
			<div class="weui-cells weui-cells_form" id="seeCarPhone" <if condition="$now_car['tip_type'] neq 0">style="display:none;"</if>>
				<div class="weui-cell">
					<div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
					<div class="weui-cell__bd">
						<a href="tel:{pigcms{$now_car.car_phone}">{pigcms{$now_car.car_phone}</a>
					</div>
				</div>
			</div>
			<div class="page__desc mt40">
				<if condition="$now_car['tip_type'] eq 0">
				<div style="margin-bottom:6px;">1. 点击上方电话可直接拨打</div>
				<else/>
				<div style="margin-bottom:6px;">1. 点击上方按钮即可通知车主，若车主未应答，您在2分钟后可查看车主电话。</div>
				</if>
				<div style="margin-bottom:6px;">2. 使用车贴二维码未能及时应答还有2分钟间隔时间，可有效保护您的隐私。</div>
				<if condition="$config['open_user_spread']">
				<div style="margin-bottom:40px;">3. 用户扫码即成为您的推广粉丝，您可享受平台推广福利。</div>
				</if>
			</div>
			<div class="weui-btn-area">
				<a href="{pigcms{:U('index')}" class="weui-btn weui-btn_default"><i class="weui-icon-success"></i>领取我的车贴图</a>
			</div>
		</div>
		<div id="loadingToast" style="display:none;">
			<div class="weui-mask_transparent"></div>
			<div class="weui-toast">
				<i class="weui-loading weui-icon_toast"></i>
				<p class="weui-toast__content">数据加载中</p>
			</div>
		</div>
		<div class="js_dialog" id="iosDialog1" style="display:none;">
            <div class="weui-mask"></div>
            <div class="weui-dialog">
                <div class="weui-dialog__hd"><strong class="weui-dialog__title">确认提示</strong></div>
                <div class="weui-dialog__bd"></div>
                <div class="weui-dialog__ft">
                    <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_default">取消</a>
                    <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
                </div>
            </div>
        </div>

		<div class="js_dialog" id="iosDialog2" style="display: none;">
            <div class="weui-mask"></div>
            <div class="weui-dialog">
                <div class="weui-dialog__bd"></div>
                <div class="weui-dialog__ft">
                    <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">知道了</a>
                </div>
            </div>
        </div>
		{pigcms{$hideScript}
	</body>
</html>