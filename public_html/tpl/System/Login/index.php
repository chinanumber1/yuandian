<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>后台登录 - {pigcms{$config.site_name}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}login/login.css"/>
	</head>
	<body>
		<div id="login">
			<h1>{pigcms{$config.site_name} - 后台登录</h1>
			<form method="post" id="form">
				<p>
					<label>用户名：</label>
					<input class="text-input" type="text" name="account" id="account" value="{pigcms{$_GET.account}"/>
				</p>
				<p>
					<label>密码：</label>
					<input class="text-input" type="password" name="pwd" id="pwd" value="{pigcms{$_GET.pwd}"/>
				</p>
				<p>
					<label>验证码：</label>
					<input class="text-input" type="text" id="verify" style="width:60px;" maxlength="4" name="verify"/>
					<span id="verify_box">
						<img src="{pigcms{:U('Login/verify')}" id="verifyImg" onclick="fleshVerify('{pigcms{:U('Login/verify')}')" title="刷新验证码" alt="刷新验证码"/>
						<a href="javascript:fleshVerify('{pigcms{:U('Login/verify')}')" id="fleshVerify">刷新验证码</a>
					</span>
					<!--input class="text-input" type="text" id="code" style="width:60px;" maxlength="6" name="code"/>
					<span id="verify_box">
						<a href="javascript:void(0);" id="send_code">获取验证码</a>
					</span-->
				</p>
				<p class="btn_p">
					<input class="button" type="submit" value="登录后台" style="float:left">
					<a href="javascript:void(0)" class="scan_login">扫码登录</a>
				</p>
			</form>	
		</div>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
		<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.scan_login').click(function(){
					art.dialog.open("{pigcms{:U('Login/see_admin_qrcode')}&t="+Math.random(),{
						init: function(){
							var iframe = this.iframe.contentWindow;
							window.top.art.dialog.data('login_iframe_handle',iframe);
						},
						id: 'login_handle',
						title:'请使用微信扫描二维码登录',
						padding: 0,
						width: 430,
						height: 433,
						lock: true,
						resize: false,
						background:'black',
						button: null,
						fixed: false,
						close: null,
						left: '50%',
						top: '38.2%',
						opacity:'0.4'
					});
					return false;
				});
				$('#send_code').click(function(){
					$.post('{pigcms{:U("Login/send_code")}', {account:$('#account').val()}, function(response){
						if (response.errcode) {
						} else {
						}
					}, 'json');
				});
			});
		</script>
		<script type="text/javascript">
			if(self!=top){window.top.location.href="{pigcms{:U('Index/index')}";}
			var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",login_check="{pigcms{:U('Login/check')}",system_index="{pigcms{:U('Index/index')}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}login/login.js"></script>
	</body>
</html>