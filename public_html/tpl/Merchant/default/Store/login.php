<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>店员登录 - {pigcms{$config.site_name}</title>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}login/login.css"/>
	</head>
	<body>
		<div id="hdw">
			<div id="hd">店员登录 - {pigcms{$config.site_name}</div>
		</div>
		<div id="login">
			<div id="switch_btn">
				<a class="login_p on" types="login">登 录</a>
				<div class="clear"></div> 
			</div>
			<div id="box">
				<form method="post" id="login_form">
					<p>
						<label>帐 号：</label>
						<input class="text-input" type="text" name="account" id="login_account"/>
					</p>
					<p>
						<label>密码：</label>
						<input class="text-input" type="password" name="pwd" id="login_pwd"/>
					</p>
					<p>
						<label>验证码：</label>
						<input class="text-input" type="text" id="login_verify" style="width:60px;" maxlength="4" name="verify"/>
						<span id="verify_box">
							<img src="{pigcms{:U('Login/verify',array('type'=>'store_login'))}" id="login_verifyImg" onclick="login_fleshVerify('{pigcms{:U('Login/verify',array('type'=>'store_login'))}')" title="刷新验证码" alt="刷新验证码"/>
							<a href="javascript:login_fleshVerify('{pigcms{:U('Login/verify',array('type'=>'store_login'))}')">刷新验证码</a>
						</span>
					</p>
					<p class="btn_login"><input type="submit" value="登 录" class="login_btn"/></p>
				</form>
			</div>
		</div>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script type="text/javascript">
			var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",login_check="{pigcms{:U('Store/login')}",store_index="{pigcms{:U('Store/index')}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}login/store_login.js"></script>
	</body>
</html>