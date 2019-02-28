<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title><if condition="!$now_order">银行卡列表<else/>选择银行卡支付</if></title>
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
		<script type="text/javascript" charset="utf-8">
			var delete_url = "{pigcms{:U('delete')}";
			var order_sms_url = "{pigcms{:U('order_sms')}";
			var order_id = "{pigcms{$now_order.sandpay_rand_id}";
			var order_money = "{pigcms{$now_order.order_price}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/sandpay.js?2112222" charset="utf-8"></script>
	</head>
	<body>
		<if condition="$_GET['now_order']">
			<p class="page__desc mt40" style="margin-top:20px!important;">订单名称：{pigcms{$now_order.order_name}</p>
			<p class="page__desc mt40" style="margin-top:5px!important;">	订单金额：￥{pigcms{$now_order.order_price|floatval=###}</p>
			<p class="page__desc mt40" style="margin-top:5px!important;">订单流水号：{pigcms{$now_order.order_id}</p>
		</if>
		<div class="weui-panel weui-panel_access">
            <div class="weui-panel__bd">
				<volist name="card_list" id="vo">
					<div class="weui-media-box weui-media-box_appmsg weui-cell_access flex card_row bind_{pigcms{$vo.card_id}" data-bind_id="{pigcms{$vo.card_id}">
						<div class="weui-media-box_text weui-cell__bd">
							<h4 class="weui-media-box__title">{pigcms{$vo.cardNo}</h4>
							<p class="weui-media-box__desc">{pigcms{$vo.bankname} - <span><if condition="$vo['creditFlag'] eq 1">储蓄卡<else/>信用卡</if></span></p>
						</div>
						<span class="weui-cell__ft"></span>
					</div>
				</volist>
            </div>
        </div>
		<div class="weui-btn-area">
            <a href="{pigcms{:U('add',array('order_id'=>$now_order['sandpay_rand_id']))}" class="weui-btn weui-btn_primary mt40" onclick="location.href='{pigcms{:U('add',array('order_id'=>$now_order['sandpay_rand_id']))}'">添加银行卡</a>
        </div>
		<div class="page msg_success js_show" <if condition="!empty($card_list)">style="display:none;"</if>>
			<div class="weui-msg">
				<div class="weui-msg__icon-area"><i class="weui-icon-info weui-icon_msg"></i></div>
				<div class="weui-msg__text-area">
					<h2 class="weui-msg__title">银行卡列表为空</h2>
					<p class="weui-msg__desc">请点击下方按钮添加银行卡</p>
				</div>
				<div class="weui-msg__opr-area">
					<p class="weui-btn-area">
						<a href="{pigcms{:U('add',array('order_id'=>$now_order['sandpay_rand_id']))}" class="weui-btn weui-btn_primary" onclick="location.href='{pigcms{:U('add',array('order_id'=>$now_order['sandpay_rand_id']))}'">添加银行卡</a>
					</p>
				</div>
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
		{pigcms{$hideScript}
	</body>
</html>