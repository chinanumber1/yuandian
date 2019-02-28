<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="utf-8"/>
		<title>实名认证</title>
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
			<div class="content">开户</div>
		</section>
		<div class="fieldset" style="border-width: 0 0 1px 0;margin-top:44px;">
			<div class="field" style="border: 0;">
				<div class="line line-a"></div>
				<span class="navigation">
					<span class="icon-a icon-complete">
						<img src="/static/api-view/images/icon/complete.png">
					</span>
					<span class="_label">绑定手机</span>
				</span>
				<span class="navigation nav1">
					<span class="icon-a icon-2-1">
						<img src="/static/api-view/images/icon/2_1.png">
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
        <div id="container" >
        	<div id="tips"></div>
			<div id="login">
				<form id="reg-form" action="{pigcms{:U('verify_real_name')}" autocomplete="off" method="post" location_url="{pigcms{$referer}" login_url="{pigcms{:U('Login/index')}">
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			            		<dd class="dd-padding">
			            			<input id="name" class="input-weak" type="text" placeholder="真实姓名" name="name" value="" required=""/>
			            		</dd>
								<dd class="dd-padding">
			            			<input id="identityNo" class="input-weak" type="tel" placeholder="身份证号码" name="identityNo" value="" required=""/>
			            		</dd>
							
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
<!--						<button type="submit" class="btn btn-larger btn-block">注册并绑定</button>-->
						<button type="submit" class="btn btn-larger btn-block">下一步</button>
			        </div>
			    </form>
			</div>
		</div>
		<script type="text/javascript">
			var countdown = 60;
			var sms_url = '{pigcms{:U('sendsms')}';
		</script>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>var international_phone = {pigcms{$config.international_phone|intval=###};</script>
		<script src="{pigcms{$static_path}js/deposit.js"></script>
	
	</body>
</html>