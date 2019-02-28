<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>绑定帐号</title>
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
		<div id="tips" style="-webkit-transform-origin:0px 0px;opacity:1;-webkit-transform:scale(1, 1);"></div>
		<div id="login">
			<dl class="list">
				<dd class="nav">
					<ul class="taba taban noslide" data-com="tab">
						<li  class="active" tab-target="reg-form"><a class="react">注册新帐号</a></li>
						<li  tab-target="login-form"><a class="react">绑定已有帐号</a></li>
						<div class="slide" style="left:0px;width:0px;"></div>
					</ul>
				</dd>
			</dl>
			<form id="login-form" autocomplete="off" method="post" action="{pigcms{:U('Login/weixin_bind')}" location_url="{pigcms{$referer}" style="display:none;">
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
								<input id="login_phone" class="input-weak" type="tel" placeholder="手机号" name="phone" value="" required=""/>
							</dd>
							<dd class="dd-padding">
								<input id="login_password" class="input-weak" type="password" placeholder="请设置您的登录密码" name="password" required=""/>
							</dd>
							
						</dl>
					</dd>
				</dl>
				<div class="btn-wrapper">
					<button type="submit" class="btn btn-larger btn-block">绑定</button>
				</div>
				<div class="btn-wrapper">
					<a href="{pigcms{:U('Login/forgetpwd')}">找回密码</a>
				</div>
			</form>
			<form id="reg-form" action="{pigcms{:U('Login/weixin_bind_reg')}" autocomplete="off" method="post" location_url="{pigcms{$referer}" >
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
							<if condition="$config['open_score_fenrun'] eq 1 ">
									<dd class="kv-line-r dd-padding"  >
										<input id="spread_code" class="input-weak" type="text" placeholder="推广码,从推荐人处获取" name="spread_code" value="">
							
									</dd>
									</if>
							<if condition="$config['reg_verify_sms'] eq 1 AND $config['sms_key'] neq ''">
								<if condition="$config.sms_verify_fleshcode eq 1">
									<dd class="kv-line-r dd-padding" id="flesh_code" >
										<input id="sms_flesh_code" class="input-weak kv-k" name = "flesh_code" type="text" placeholder="填写验证码" value=""  required />
										<input id="sms_flesh_type"  name = "type" type="hidden" value="sms" />
										<input id="sms_flesh_verify"  name = "verify" type="hidden" value="" />
										<img src="./index.php?c=Verify&a=fleshcode&type=sms" id="reg_verifyImg" class="btn  kv-v"title="刷新验证码" alt="刷新验证码" style="background:none;padding:0rem"/>
									
									</dd>
								</if>
				            		<dd class="kv-line-r dd-padding" id="sms" <if condition="$config.sms_verify_fleshcode eq 1">style="display:none"</if>>
				            			<input id="sms_code" class="input-weak kv-k sms_code" name="vcode" type="text" placeholder="填写短信验证码"  <if condition="$config.sms_verify_fleshcode eq 0">required</if>/>
				            			<button id="reg_send_sms" type="button" onclick="sendsms(this)" class="btn btn-weak kv-v">获取短信验证码</button>
				            		</dd>
								</if>
							
							<dd class="kv-line-r dd-padding" id="password" <if condition="$config.sms_verify_fleshcode eq 1 AND $config['reg_verify_sms'] eq 1 AND $config['sms_key'] neq ''">style="display:none"</if>>
								<input id="reg_pwd_password" class="input-weak kv-k" type="password" placeholder="请设置您的登录密码"/>
								<input id="reg_txt_password" class="input-weak kv-k" type="text" placeholder="请设置您的登录密码" style="display:none;"/>
								<input type="hidden" id="reg_password_type" value="0"/>
								<input type="hidden" id="openid" value="{pigcms{$_SESSION['openid']}"/>
								<button id="reg_changeWord" type="button" class="btn btn-weak kv-v">显示明文</button>
							</dd>
						</dl>
					</dd>
				</dl>
				<div class="btn-wrapper">
				<php>if($config['sms_verify_fleshcode']== 1 && $config['reg_verify_sms'] == 1 && $config['sms_key']){</php>
					<button type="submit" class="btn btn-larger btn-block"  id="register"  style="display:none">注册并绑定</button>
					<button type="button" class="btn btn-larger btn-block"id="verify_flesh" >下一步</button>
				<php>}else{</php>
					<button type="submit" class="btn btn-larger btn-block"  id="register" >注册并绑定</button>
				<php>}</php>
				</div>
				<div class="btn-wrapper register_agreement"  <php>if($config['sms_verify_fleshcode'] == 1 && $config['reg_verify_sms'] == 1 && $config['sms_key'] != ''){ </php>style="display:none"<php> }</php>>
					<input type="checkbox" id="register_agreement" checked="checked">我已阅读并且同意<font color="#EE3968"><a href="{pigcms{:U('Login/register_agreement')}">《注册协议》</a></font>
				</div>
			</form>
		</div>
		
	</div>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_path}js/common_wap.js"></script>
	<script>var is_specificfield = <php>if(isset($config['specificfield'])){</php>true<php>}else{</php>false<php>}</php></script>
	<script>var international_phone = {pigcms{$config.international_phone|intval=###};</script>
	<script src="{pigcms{$static_path}js/weixin_back.js?ms=222"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
	<script>
		$('#weixin_nobind').click(function(){
			layer.open({
				title:['提醒：','background-color:#8DCE16;color:#fff;'],
				content:'直接将微信号作为用户登录，以后将无法绑定已有帐号！请确认：',
				btn: ['确认', '取消'],
				shadeClose: false,
				yes: function(){
					layer.open({content: '你点了确认，正在跳转！', time:3});
					window.location.href = "{pigcms{:U('Login/weixin_nobind')}";
				}
			});
			return false;
		});
	</script>


{pigcms{$hideScript}
	</body>
</html>