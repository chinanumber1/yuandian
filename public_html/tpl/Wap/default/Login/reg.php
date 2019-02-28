<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>注册  - {pigcms{$config.site_name}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">

    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/index_wap.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
	<style>
		#login{margin: 0.5rem 0.2rem;}
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
<body id="index" data-com="pagecommon">
        <!--header  class="navbar">
            <div class="nav-wrap-left">
                <a class="react back" href="javascript:history.back()"><i class="text-icon icon-back"></i></a>
            </div>
            <h1 class="nav-header">{pigcms{$config.site_name}</h1>
            <div class="nav-wrap-right">
                <a class="react" href="{pigcms{:U('Home/index')}">
                    <span class="nav-btn"><i class="text-icon">⟰</i>首页</span>
                </a>
            </div>
        </header-->
        <div id="container">
        	<div id="tips" style="-webkit-transform-origin:0px 0px;opacity:1;-webkit-transform:scale(1, 1);"></div>
			<div id="login">
			    <form id="reg-form" action="{pigcms{:U('Login/reg')}" autocomplete="off" method="post" <if condition="$config['now_scenic'] eq 1">location_url="{pigcms{:U('My/index')}"<elseif condition="$config['now_scenic'] eq 2"/>location_url="{pigcms{:U('Scenic_user/index')}"<else />location_url="{pigcms{:U('My/index')}"</if>>
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
							
									<input id="phone" class="input-weak" type="tel" placeholder="手机号" name="phone" value="" required="" >
			            		</dd>
							<if condition="$config['open_score_fenrun'] eq 1 ">
								<dd class="kv-line-r dd-padding"  >
								<input id="spread_code" class="input-weak" type="text" placeholder="推广码,从推荐人处获取" name="spread_code" value="" >
								</if>
					
								</dd>
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
			            		<dd class="kv-line-r dd-padding " id="password" <if condition="$config.sms_verify_fleshcode eq 1 AND $config['reg_verify_sms'] eq 1 AND $config['sms_key'] neq ''">style="display:none"</if>>
			            			<input id="pwd_password" class="input-weak kv-k" type="password" placeholder="6位以上的密码"/>
			            			<input id="txt_password" class="input-weak kv-k" type="text" placeholder="6位以上的密码" style="display:none;"/>
			            			<input type="hidden" id="password_type" value="0"/>
			            			<button id="changeWord" type="button" class="btn btn-weak kv-v">显示明文</button>
			            		</dd>
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
						<if condition="$config.sms_verify_fleshcode eq 1 AND $config['reg_verify_sms'] eq 1 AND $config['sms_key'] neq ''">
							<button type="submit" class="btn btn-larger btn-block " id="register" style="display:none">注册</button>
							<button type="button" class="btn btn-larger btn-block"id="verify_flesh" >下一步</button>
						<else />
							<button type="submit" class="btn btn-larger btn-block " id="register" >注册</button>
						</if>
			        </div>
			    </form>
			</div>
			<ul class="subline">
			    <li class="register_agreement" <if condition="$config.sms_verify_fleshcode eq 1 AND $config['reg_verify_sms'] eq 1 AND $config['sms_key'] neq ''">style="display:none"</if>><input type="checkbox" id="register_agreement" checked="checked" >我已阅读并且同意<font color="#EE3968"><a href="{pigcms{:U('Login/register_agreement')}">《注册协议》</a></font></li>
			    <li style="float:right"><a href="{pigcms{:U('Login/index')}">立即登录</a></li>
			</ul>
		</div>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>var international_phone = {pigcms{$config.international_phone|intval=###};</script>
		<script type="text/javascript">
			<if condition="$config['register_agreement'] neq ''">
			var must_agree = true;
			<else />
			var must_agree = false;
			</if>
		</script>
		<script src="{pigcms{$static_path}js/reg.js"></script>
		<include file="Public:footer"/>

{pigcms{$hideScript}
	</body>
</html>