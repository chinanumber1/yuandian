<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>校验短信验证码</title>
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
		<script type="text/javascript" src="{pigcms{$static_path}js/sandpay.js?2112222" charset="utf-8"></script>
		<style>
			button.weui-vcode-btn {
				background-color: transparent;
				border-top: 0;
				border-right: 0;
				border-bottom: 0;
				outline: 0;
				font-size:14px;
			}
			.weui-input.vcode{
				font-size:14px;
			}
			button.weui-vcode-btn.gray{
				color:gray;
			}
		</style>
	</head>
	<body>
		<p class="page__desc mt40" style="margin-top:20px!important;">使用该 {pigcms{$now_card.card_bank}<if condition="$now_card['creditFlag'] eq 1">储蓄卡<else/>信用卡</if> 付款 ￥{pigcms{$now_order.order_price|floatval=###}</p>
		<div class="page__bd">
			<div class="weui-cells weui-cells_form">
				<div class="weui-cell">
					<div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
					<div class="weui-cell__bd">
						<input class="weui-input" name="phoneNo" id="phoneNo" type="number" placeholder="银行预留手机号" value="{pigcms{$now_card.phoneNo}" disabled="disabled"/>
					</div>
				</div>
				<div class="weui-cell weui-cell_vcode">
					<div class="weui-cell__hd"><label class="weui-label">验证码</label></div>
					<div class="weui-cell__bd">
						<input class="weui-input vcode" name="smsCode" id="smsCode" type="number" placeholder="请输入短信验证码" value=""/>
					</div>
					<div class="weui-cell__ft">
						<button class="weui-vcode-btn" id="get_order_sms" data-order_id="{pigcms{$_GET['order_id']}" data-bind_id="{pigcms{$now_card.card_id}" data-url="{pigcms{:U('get_order_sms')}">获取验证码</button>
					</div>
				</div>
			</div>
			<div class="weui-btn-area mt3em">
				<a class="weui-btn weui-btn_primary " href="javascript:" data-order_id="{pigcms{$_GET['order_id']}" data-bind_id="{pigcms{$now_card.card_id}" data-url="{pigcms{:U('card_pay')}" id="card_pay_order">确认支付</a>
			</div>
		</div>
		<div id="loadingToast" style="display:none;">
			<div class="weui-mask_transparent"></div>
			<div class="weui-toast">
				<i class="weui-loading weui-icon_toast"></i>
				<p class="weui-toast__content">数据加载中</p>
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
		<div class="page msg_success js_show" style="display:none;">
			<div class="weui-msg">
				<div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
				<div class="weui-msg__text-area">
					<h2 class="weui-msg__title">绑卡成功</h2>
					<p class="weui-msg__desc"></p>
				</div>
				<div class="weui-msg__opr-area">
					<p class="weui-btn-area">
						<a href="{pigcms{:U('index',array('order_id'=>$_GET['order_id']))}" class="weui-btn weui-btn_primary" onclick="location.href='{pigcms{:U('index',array('order_id'=>$_GET['order_id']))}'">回到银行卡列表</a>
					</p>
				</div>
			</div>
		</div>
		{pigcms{$hideScript}
	</body>
</html>