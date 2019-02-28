<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	</head>
	<body>
		<p style="margin-top:20px;margin-bottom:20px;text-align:center;">请使用微信扫描二维码登录</p>
		<p style="text-align:center;"><img src="{pigcms{$ticket}" style="width:300px;height:300px;"/></p>
		<p id="login_status" style="margin-top:20px;display:none;text-align:center;"></p>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script>
			var redirect_url = window.top.location.href;
			window.setInterval("ajax_weixin_login()", 5000);
			function ajax_weixin_login(){
				$.get("{pigcms{:U('Login/ajax_scan_login')}",{qrcode_id:{pigcms{$id}},function(result){
					if (result.err_code == -2) {
						$('#login_status').html('您的微信号没有绑定平台账号，不能登录！').css('color','red').show();
					} else if(result.err_code == -4) {
						$('#login_status').html('用户被禁止登录！').css('color','red').show();
					} else if(result.err_code == -5) {
						$('#login_status').html('登录信息保存失败,请重新登录！').css('color','red').show();
					} else if (result.err_code == 1) {
						$('#login_status').html('登录成功！正在跳转。').css('color','green').show();
						window.top.location.href = '/admin.php';
					}
				}, 'json');
			}
		</script>
	</body>
</html>