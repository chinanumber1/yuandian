<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="utf-8"/>
		<title>签约电子协议</title>
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>

		<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/index_wap.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
		<style>
			/*#login{margin: 0.5rem 0.2rem;}*/
			.btn-wrapper{margin:.28rem 0;}
			dl.list{border-bottom:0;border:1px solid #ddd8ce;}
			dl.list:first-child{border-top:1px solid #ddd8ce;}
			dl.list dd dl{padding-right:0.2rem;}
			dl.list dd dl>.dd-padding, dl.list dd dl dd>.react, dl.list dd dl>dt{padding-right:0;}
			.nav{text-align: center;}
			.subline{margin:.28rem .2rem;}
			.subline li{display:inline-block;}
			.captcha img{margin-left:.2rem;}
			.captcha .btn{margin-top:-.15rem;margin-bottom:-.15rem;margin-left:.2rem;}
			.public{ height: 44px; line-height: 44px; background: #f32516; color: #fff; position: fixed; width: 100%; top: 0px; left: 0px; z-index: 880; }
			.public .content{ text-align: center;font-size: 16px;   }
			.public .return{ position: absolute; width: 50px; height: 100%; left: 0px; top: 0px; }
			.public .return:after{ display: block;content: "";border-top: 2px solid #fff;border-left: 2px solid #fff;width: 10px;height: 10px;-webkit-transform: rotate(-45deg);background-color: transparent;position: absolute; left: 16px;top: 16px; }
		</style>
	</head>
	<body>
		<section class="public pageSliderHide">
			<div class="return link-url" data-url-type="openLeftWindow" data-url="back"></div>
			<div class="content">开户</div>
		</section>
        <div id="container" style="margin-top:90px;">
        	<div id="tips"></div>
			<div id="login">
				<form id="reg-form" action="{pigcms{:U('signConnect')}" autocomplete="off" method="post" location_url="{pigcms{$referer}" login_url="{pigcms{:U('Login/index')}">
			        <div class="btn-wrapper">
<!--						<button type="submit" class="btn btn-larger btn-block">注册并绑定</button>-->
						<button type="submit" class="btn btn-larger btn-block">去签约</button>
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
		<script src="{pigcms{$static_path}js/deposit_send_sms.js"></script>
	
	</body>
</html>