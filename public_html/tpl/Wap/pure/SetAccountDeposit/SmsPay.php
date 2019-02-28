<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="utf-8"/>
		<title>确认支付</title>
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
			<div class="content">请输入验证码</div>
		</section>
		
	
        <div id="container" >
        	<div id="tips"></div>
			<div id="login" style="    margin-top: 62px;"> 
				<form id="reg-form" action="{pigcms{:U('SmsPay')}" autocomplete="off" method="post" location_url="{pigcms{$referer}" login_url="{pigcms{:U('Login/index')}">
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			            <input id="order_id" name = "order_id" type="hidden" value="{pigcms{$_GET['order_id']}" />
			            <input id="payment_money" name = "payment_money" type="hidden" value="{pigcms{$_GET['payment_money']}" />
			         
									
								
			            		<dd class="kv-line-r dd-padding"  id="sms" >
			            			<input id="sms_code" class="input-weak kv-k" name = "verificationCode" type="text" placeholder="填写短信验证码" />
			            		</dd>
							
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
<!--						<button type="submit" class="btn btn-larger btn-block">注册并绑定</button>-->
						<button type="submit" class="btn btn-larger btn-block" style="width:50%;margin:0 auto;">确认支付</button>
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