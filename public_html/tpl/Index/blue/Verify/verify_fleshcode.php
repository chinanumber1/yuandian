<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/common.v113ea197.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/base.v492b572b.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/login.v7e870f72.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/login-section.vfa22738e.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/qrcode.v74a11a81.css" />
	</head>
	<body>
		 <div class="validate-info" style="visibility:hidden"></div>	
		<div class="form-field form-field--icon" style="padding: 54px 0 8px 63px;">
      
			<img src="./index.php?c=Verify&a=fleshcode&type=sms" id="reg_verifyImg" title="刷新验证码" alt="刷新验证码" style="    height: 34px;"/>
			<input id="sms_flesh_code" class="f-text" name = "flesh_code" style="width:120px;" type="text" placeholder="填写验证码" value=""  required />
			<input id="sms_flesh_type"  name = "type" type="hidden" value="sms" />
			<button id="verify_flesh" type="button" class="btn" style="width:80px;font-weight: normal;float: right;margin-right: 41px;">验证</button>
        </div>
	</body>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<script>
		$(document).ready(function() {
			$('#close').click(function(event) {
				close_verify();
			});

			$('#verify_flesh').click(function(event) {
				send_fleshcode($('#sms_flesh_code').val(),$('#sms_flesh_type').val())
			});

			$('#reg_verifyImg').click(function(event) {
				reg_fleshVerify();
			});
		});
		function close_verify(){
		
	 		art.dialog.close();
		}
		function reg_fleshVerify(){
			var time = new Date().getTime();
			$('#reg_verifyImg').attr('src','./index.php?c=Verify&a=fleshcode&type=sms'+"&time="+time);
		}

		function send_fleshcode(code,type){
			$.ajax({
				url: './index.php?c=Verify&a=verify_fleshcode',
				type: 'POST',
				dataType: 'json',
				data: {verify: code,type:type},
				success:function(date){
					if(date.error_code==1){
						$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+date.msg).css('visibility','visible');
					}else{
						$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+date.msg).css('visibility','visible');
						// window.top('#sms_flesh_verify')
					     artDialog.data("sms_flesh_verify", code); //将值存起来，供父页面读取 
						close_verify();
					}
				}
			});
			
		}
	</script>
</html>