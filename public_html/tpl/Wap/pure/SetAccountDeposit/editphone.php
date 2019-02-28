<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="utf-8"/>
		<title>重置手机号码</title>
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
			<div class="content">重置手机号码</div>
		</section>
		
		
        <div id="container" style="margin-top:44px;">
        	<div id="tips"></div>
			<div id="login">
				<form id="reg-form" action="{pigcms{:U('editphone')}" autocomplete="off" method="post" location_url="{pigcms{$referer}" login_url="{pigcms{:U('Login/index')}">
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			            		<dd class="dd-padding">
			            			<input class="input-weak" type="tel" placeholder="" name="phone" readOnly value="当前手机 {pigcms{$deposit.phone}" required="" style="    text-align: center;"/>
			            		</dd>
								<dd class="dd-padding">
			            			<div style="float:left;width: 30%;">新手机号码</div><input id="phone" class="input-weak" type="tel"  style="float:right; width:70%"  name="phone" value=""   required=""/>
			            		</dd>
								<dd class="dd-padding">
			            			<div style="float:left;width: 30%;">新手机验证码</div><input class="input-weak" type="tel"  style="float: left;width: 30%;"  name="code" value=""   required=""/>
									<button id="reg_send_sms" type="button" onclick="sendsms(this)" class="btn btn-weak kv-v" style="float:right;">获取验证码</button>
			            		</dd>
							
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
<!--						<button type="submit" class="btn btn-larger btn-block">注册并绑定</button>-->
						<button type="submit" class="btn btn-larger btn-block">重置</button>
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
				$('#phone').change(function(){
					sms_data.phone = $(this).val();
				})
		
			})
		</script>
	</body>
</html>