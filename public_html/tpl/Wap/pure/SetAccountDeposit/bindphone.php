<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="utf-8"/>
		<title>绑定手机号码</title>
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>

		<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/index_wap.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/deposit.css" rel="stylesheet"/>
		
	</head>
	<body>
		<section class="public pageSliderHide">
			<div class="return link-url" data-url-type="openLeftWindow" data-url="back"></div>
			<div class="content"><if condition="$_GET['edit'] eq 1">修改手机号<else />绑定手机</if></div>
		</section>
		
		<div class="fieldset" style="border-width: 0 0 1px 0;margin-top:44px;display:none;" >
			<div class="field" style="border: 0;">
				<div class="line line-a"></div>
				<span class="navigation">
					<span class="icon-a icon-1-1">
						<img src="/static/api-view/images/icon/1_1.png">
					</span>
					<span class="_label">绑定手机</span>
				</span>
				<span class="navigation nav1">
					<span class="icon-a icon-2-1">
						<img src="/static/api-view/images/icon/2_2.png">
					</span>
					<span class="_label">实名认证</span>
				</span>
				<span class="navigation nav2">
					<span class="icon-a icon-3-2">
						<img src="/static/api-view/images/icon/3_2.png">
					</span>
					<span class="_label">绑定银行卡</span>
				</span>
			</div>
		</div>
        <div id="container" style="margin-top:44px;">
        	<div id="tips"></div>
			<div id="login">
				<form id="reg-form" action="{pigcms{:U('bindphone')}" autocomplete="off" method="post">
					<input type="hidden" name="referer" value="{pigcms{$referer}"/>
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			            		<dd class="dd-padding">
			            			<input id="reg_phone" class="input-weak" type="tel" placeholder="手机号" name="phone" value="" required=""/>
			            		</dd>
			            		<dd class="kv-line-r dd-padding"  id="sms" >
			            			<input id="sms_code" class="input-weak kv-k" name = "code" type="tel" placeholder="填写短信验证码" />
									<input id="change_phone"  name = "change_phone" type="hidden" value="<if condition="$now_user['phone'] eq ''">0<else />1</if>" />
			            			<button id="reg_send_sms" type="button" onclick="sendsms(this)" class="btn btn-weak kv-v">获取验证码</button>
			            		</dd>
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
<!--						<button type="submit" class="btn btn-larger btn-block">注册并绑定</button>-->
						<button type="submit" class="btn btn-larger btn-block">绑定</button>
			        </div>
			    </form>
			</div>
		</div>
		
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>var international_phone = {pigcms{$config.international_phone|intval=###};</script>
		<script src="{pigcms{$static_path}js/deposit.js"></script>
		<script type="text/javascript">
			var countdown = 60;
			var sms_url = '{pigcms{:U('sendsms')}';
			
			var sms_data  = {phone:$('#reg_phone').val()}
			
			$(function(){
				$('#reg_phone').change(function(){
					sms_data.phone = $(this).val();
				})
		
			})
		</script>
	</body>
</html>