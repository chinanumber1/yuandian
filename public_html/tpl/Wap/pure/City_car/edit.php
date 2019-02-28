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
			<h1 class="page__title">编辑车辆</h1>
		</div>
		<div class="page__bd">
			<div class="weui-cells weui-cells_form">
				<div class="weui-cell">
					<div class="weui-cell__hd"><label class="weui-label" style="width:85px;">车牌号</label></div>
					<div class="weui-cell__hd" style="margin-right:15px;">
						<select class="weui-select" name="car_area" id="car_area" style="border:1px solid rgb(204, 204,204);border-radius: 4px;background-color: rgb(255, 255, 255);box-shadow: rgba(0, 0, 0, 0.0980392) 0px 1px 2px inset;padding-right:15px;height:30px;line-height:30px;">
							<volist name="city_arr" id="vo">
								<option value="{pigcms{$vo}" <if condition="$vo eq $now_car['car_area']">selected="selected"</if>>{pigcms{$vo}</option>
							</volist>
						</select>
					</div>
					<div class="weui-cell__bd">
						<input class="weui-input" name="carnum" id="carnum" type="text" placeholder="请输入车牌号" value="{pigcms{$now_car.car_num}"/>
					</div>
				</div>
				<div class="weui-cell">
					<div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
					<div class="weui-cell__bd">
						<input class="weui-input" name="phone" id="phone" type="number" placeholder="请输入手机号" value="{pigcms{$now_car.car_phone}"/>
					</div>
				</div>
				<div class="weui-cell weui-cell_select weui-cell_select-after">
					<div class="weui-cell__hd">
						<label for="" class="weui-label">通知方式</label>
					</div>
					<div class="weui-cell__bd">
						<select class="weui-select" name="tip_type" id="tip_type">
							<option value="0" <if condition="$now_car['tip_type'] eq 0">selected="selected"</if>>显示手机号</option>
							<option value="1" <if condition="$now_car['tip_type'] eq 1">selected="selected"</if>>先通知后显示手机号</option>
						</select>
					</div>
				</div>
			</div>
			<div class="weui-btn-area mt3em">
				<a class="weui-btn weui-btn_primary" href="javascript:" data-car_id="{pigcms{$now_car.car_id}" data-url="{pigcms{:U('amend')}" id="car_form_edit">保存</a>
			</div>
			<div class="weui-btn-area">
				<a href="javascript:;" class="weui-btn weui-btn_warn" data-url="{pigcms{:U('delete')}" id="car_form_del" data-car_id="{pigcms{$now_car.car_id}" data-index_url="{pigcms{:U('index')}">删除</a>
			</div>
			<div class="weui-btn-area">
				<a href="{pigcms{:U('get_pic',array('car_id'=>$now_car['car_id']))}" class="weui-btn weui-btn_default"><i class="weui-icon-success"></i>领取车贴图</a>
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