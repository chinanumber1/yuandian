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
		<div class="page__bd">
			<div class="weui-cells weui-cells_form">
				<div class="weui-cell">
					<div class="weui-cell__hd"><label class="weui-label" style="width:85px;">车牌号</label></div>
					<div class="weui-cell__hd" style="margin-right:15px;">
						<select class="weui-select" name="car_area" id="car_area" style="border:1px solid rgb(204, 204,204);border-radius: 4px;background-color: rgb(255, 255, 255);box-shadow: rgba(0, 0, 0, 0.0980392) 0px 1px 2px inset;padding-right:15px;height:30px;line-height:30px;">
							<volist name="city_arr" id="vo">
								<option value="{pigcms{$vo}">{pigcms{$vo}</option>
							</volist>
						</select>
					</div>
					<div class="weui-cell__bd">
						<input class="weui-input" name="carnum" id="carnum" type="text" placeholder="请输入车牌号"/>
					</div>
				</div>
				<div class="weui-cell">
					<div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
					<div class="weui-cell__bd">
						<input class="weui-input" name="phone" id="phone" type="number" placeholder="请输入手机号" value="{pigcms{$user_session.phone}"/>
					</div>
				</div>
				<div class="weui-cell weui-cell_select weui-cell_select-after">
					<div class="weui-cell__hd">
						<label for="" class="weui-label">通知方式</label>
					</div>
					<div class="weui-cell__bd">
						<select class="weui-select" name="tip_type" id="tip_type">
							<option value="0">显示手机号</option>
							<option value="1">先通知后显示手机号</option>
						</select>
					</div>
				</div>
			</div>
			<div class="weui-btn-area mt3em">
				<a class="weui-btn weui-btn_primary " href="javascript:" data-url="{pigcms{:U('motify')}" id="car_form_add">保存</a>
			</div>
			<p class="page__desc mt40">
				1. 通知方式：支持微信通知、短信通知<br/>
				2. 先通知后显示手机号，即在收到通知后两分钟无应答，则显示手机号。<br/>
				3. 用户扫码即成为您的推广粉丝，您可享受推广福利。<br/>
				<a class="weui-btn weui-btn_mini weui-btn_primary" href="{pigcms{:U('My/my_spread')}" style="margin-top:40px;">查看我的推广</a>
			</p>
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
					<h2 class="weui-msg__title">操作成功</h2>
					<p class="weui-msg__desc"></p>
				</div>
				<div class="weui-msg__opr-area">
					<p class="weui-btn-area">
						<a data-href="{pigcms{:U('get_pic')}" id="get_pic_btn" class="weui-btn weui-btn_primary">获取车辆车贴</a>
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