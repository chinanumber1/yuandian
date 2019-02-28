<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>添加银行卡</title>
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
	</head>
	<body>
		<div class="page__bd">
			<div class="weui-cells weui-cells_form">
				<div class="weui-cell">
					<div class="weui-cell__hd"><label class="weui-label">持卡人</label></div>
					<div class="weui-cell__bd">
						<input class="weui-input" name="userName" id="userName" type="text" placeholder="请输入持卡人姓名" value=""/>
					</div>
				</div>
				<div class="weui-cell">
					<div class="weui-cell__hd"><label class="weui-label">卡号</label></div>
					<div class="weui-cell__bd">
						<input class="weui-input" name="cardNo" id="cardNo" type="number" placeholder="请输入银行卡号" value=""/>
					</div>
				</div>
				<div class="weui-cell">
					<div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
					<div class="weui-cell__bd">
						<input class="weui-input" name="phoneNo" id="phoneNo" type="number" placeholder="银行预留手机号" value="{pigcms{$user_session.phone}"/>
					</div>
				</div>
				<div class="weui-cell">
					<div class="weui-cell__hd"><label class="weui-label">身份证</label></div>
					<div class="weui-cell__bd">
						<input class="weui-input" name="certificateNo" id="certificateNo" type="text" placeholder="身份证号码" value=""/>
					</div>
				</div>
				<div class="weui-cell weui-cell_select weui-cell_select-after">
					<div class="weui-cell__hd">
						<label for="" class="weui-label">银行卡类型</label>
					</div>
					<div class="weui-cell__bd">
						<select class="weui-select" name="creditFlag" id="creditFlag">
							<option value="1">储蓄卡</option>
							<option value="2">信用卡</option>
						</select>
					</div>
				</div>
				<div class="weui-cell creditCardBox hide">
					<div class="weui-cell__hd"><label class="weui-label">cvn2码</label></div>
					<div class="weui-cell__bd">
						<input class="weui-input" name="checkNo" id="checkNo" type="number" placeholder="请输入信用卡背面最后3位数字" value=""/>
					</div>
				</div>
				<div class="weui-cell weui-cell_select weui-cell_select-after creditCardBox hide">
					<div class="weui-cell__hd">
						<label for="" class="weui-label">卡有效期(年)</label>
					</div>
					<div class="weui-cell__bd">
						<select class="weui-select" name="checkExpiryYear" id="checkExpiryYear">
							<volist name="card_over_year_array" id="vo">
								<option value="{pigcms{$vo.two}">{pigcms{$vo.four}</option>
							</volist>
						</select>
					</div>
				</div>
				<div class="weui-cell weui-cell_select weui-cell_select-after creditCardBox hide">
					<div class="weui-cell__hd">
						<label for="" class="weui-label">卡有效期(月)</label>
					</div>
					<div class="weui-cell__bd">
						<select class="weui-select" name="checkExpiryMonth" id="checkExpiryMonth">
							<volist name="card_over_month_array" id="vo">
								<option value="{pigcms{$vo}">{pigcms{$vo}</option>
							</volist>
						</select>
					</div>
				</div>
			</div>
			<div class="weui-btn-area mt3em">
				<a class="weui-btn weui-btn_primary " href="javascript:" data-url="{pigcms{:U('motify')}" data-ok_url="{pigcms{:U('confirmSms',array('order_id'=>$_GET['order_id']))}" id="car_form_add">下一步</a>
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