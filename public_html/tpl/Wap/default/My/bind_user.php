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
		</style>
	</head>
	<body>
        <div id="container">
        	<div id="tips"></div>
			<div id="login">
				<form id="reg-form" action="{pigcms{:U('My/bind_user')}" autocomplete="off" method="post" location_url="{pigcms{$referer}" login_url="{pigcms{:U('Login/index')}">
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
								<if condition="$config.international_phone eq 1">
			            		<dd class="dd-padding">
									 <select name="phone_country_type" id="phone_country_type" style="width:100%; height:30px;">
										  <option value="">请选择国家...,choose country</option>
										  <option value="86" <if condition="$config.qcloud_sms_default_country eq 86">selected</if>>+86 中国 China</option>
										  <option value="1" <if condition="$config.qcloud_sms_default_country eq 1">selected</if>>+1 加拿大 Canada</option>
									</select>
								</dd>
								</if>
								<dd class="dd-padding">
								<input id="reg_phone" class="input-weak" type="tel" placeholder="手机号" name="phone" value="" required="" />
			            		</dd>
								<if condition="C('config.bind_phone_verify_sms') AND C('config.sms_key')">
			            		<if condition="$config.sms_verify_fleshcode eq 1">
										<dd class="kv-line-r dd-padding" id="flesh_code" >
											<input id="sms_flesh_code" class="input-weak kv-k" name = "flesh_code" type="tel" placeholder="填写验证码" value=""  required style="width: 3.0rem;"/>
											<input id="sms_flesh_type"  name = "type" type="hidden" value="sms" />
											<input id="sms_flesh_verify"  name = "verify" type="hidden" value="" />
											<img src="./index.php?c=Verify&a=fleshcode&type=sms" id="reg_verifyImg" class="btn  kv-v"title="刷新验证码" alt="刷新验证码" style="background:none;    padding: 0rem;"/>
											<button id="verify_flesh" type="button" class="btn btn-weak kv-v" style="    padding: 0rem;">发送短信前请验证</button>
										</dd>
									</if>
			            		<dd class="kv-line-r dd-padding"  id="sms" <if condition="$config.sms_verify_fleshcode eq 1">style="display:none"</if>>
			            			<input id="sms_code" class="input-weak kv-k" name = "vcode" type="text" placeholder="填写短信验证码" />
									<input id="change_phone"  name = "change_phone" type="hidden" value="<if condition="$now_user['phone'] eq ''">0<else />1</if>" />
			            			<button id="reg_send_sms" type="button" onclick="sendsms(this)" class="btn btn-weak kv-v">获取短信验证码</button>
			            		</dd>
								</if>
								<if condition="$now_user['pwd'] eq '' OR $now_user['phone'] eq '' OR C('config.bind_phone_verify_sms') eq 0">
									<dd class="kv-line-r dd-padding">
										<input id="reg_pwd_password" class="input-weak kv-k" type="password" placeholder="<if condition="$now_user['phone'] eq ''">设置一个6位以上的密码<else />验证原密码</if>"/>
										<input id="reg_txt_password" class="input-weak kv-k" type="text" placeholder="<if condition="$now_user['phone'] eq ''">设置一个6位以上的密码<else />验证原密码</if>" style="display:none;"/>
										<input type="hidden" id="reg_password_type" value="0"/>
										<button id="reg_changeWord" type="button" class="btn btn-weak kv-v">显示明文</button>
									</dd>
								</if>
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
		</script>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>var international_phone = {pigcms{$config.international_phone|intval=###};</script>
		<script src="{pigcms{$static_path}js/bind_user.js"></script>
		<include file="Public:footer"/>
	</body>
</html>