<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>注册 | {pigcms{$config.site_name}</title>
    <!--[if IE 6]>
		<script src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a-min.v86c6ab94.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
		<script src="{pigcms{$static_path}js/html5shiv.min-min.v01cbd8f0.js"></script>
    <![endif]-->
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/common.v113ea197.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/base.v492b572b.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/login.v7e870f72.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/login-section.vfa22738e.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/qrcode.v74a11a81.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/footer.css" />
	<script src="{pigcms{$static_public}js/jquery.min.js"></script>
	<script>var international_phone = {pigcms{$config.international_phone|intval=###};</script>
</head>
<body id="login" class="theme--www" style="position: static;">
	<header id="site-mast" class="site-mast site-mast--mini">
	    <div class="site-mast__branding cf">
			<a href="{pigcms{$config.site_url}"><img src="{pigcms{$config.site_logo}" alt="{pigcms{$config.site_name}" title="{pigcms{$config.site_name}" style="width:190px;height:60px;"/></a>
	    </div>
	</header>
	<div class="site-body pg-login cf">
	    <div class="promotion-banner">
	        <img src="{pigcms{$config.site_url}/tpl/Static/default/css/img/web_login/{pigcms{:mt_rand(1,4)}.jpg" width="480" height="370">    
	    </div>
	    <div class="component-login-section component-login-section--page mt-component--booted" >
		    <div class="origin-part theme--www">
			    <div class="validate-info" style="visibility:hidden"></div>
		        <h2>账号注册</h2>
		        <form id="J-login-form" method="post" class="form form--stack J-wwwtracker-form">
					<if condition="$config.international_phone eq 1">
					<select name="phone_country_type" id="phone_country_type" style="width:270px; height:34px;">
						  <option value="">请选择国家...,choose country</option>
						  <option value="86" <if condition="$config.qcloud_sms_default_country eq 86">selected</if>>+86 中国 China</option>
						  <option value="1" <if condition="$config.qcloud_sms_default_country eq 1">selected</if>>+1 加拿大 Canada</option>
				    </select>
					</if>
			        <div class="form-field form-field--icon">
					
			            <i class="icon icon-user" ></i>
			            <input type="text" id="login-phone" class="f-text" name="phone" placeholder="手机号"/>
			        </div>
					<if condition="$config['open_score_fenrun'] eq 1 ">
					<div class="form-field form-field--icon">
			            <i class="icon icon-user"></i>
			            <input type="text" id="spread_code" class="f-text" name="spread_code" placeholder="推广码,从推荐人处获取"/>
			        </div>
					</if>
					<if condition="$config['reg_verify_sms'] AND $config['sms_key']">
					
					<div class="form-field form-field--icon" >
			            <i class="icon icon-user"></i>
						<if condition="$config.sms_verify_fleshcode eq 1">
						<input id="sms_flesh_type" name = "type" type="hidden" value="sms" />
						<input id="sms_flesh_verify" name = "verify" type="hidden" value="" />
						</if>
			            <input type="text" id="sms_code" style ="width:96px;" class="f-text" name="sms_code" placeholder="短信验证码"/>
						&nbsp;&nbsp;<input type="button" class="btn" id="send_sms" style="width: 118px;font-weight: normal;padding: 7px 10px 6px;" onclick="<if condition="$config.sms_verify_fleshcode eq 1">verify_flesh();<else />sendsms(this);</if>" value="发送短信">
			        </div>
					</if>
			        <div class="form-field form-field--icon" >
			            <i class="icon icon-password"></i>
			            <input type="password" id="login-password" class="f-text" name="pwd" placeholder="密码"/>
			        </div>
					<div class="form-field form-field--icon" >
			            <i class="icon icon-password"></i>
			            <input type="password" id="login-repassword" class="f-text" name="repwd" placeholder="再输入一次密码"/>
			        </div>
			        <if condition="$config['register_agreement'] neq ''">
					<p class="signup-guide"><input type="checkbox" id="register_agreement" checked>同意《注册协议》<a href="javascript:;" id="read_register_agreement">查看协议</a></p>
					</if>
			        <div class="form-field form-field--ops">
			            <input type="hidden" name="fingerprint" class="J-fingerprint"/>
			            <input type="hidden" name="origin" value="account-login"/>
			            <input type="submit" class="btn" id="commit" value="注册"/>
			        </div>
			    </form>
				<p class="signup-guide">已有账号？<a href="{pigcms{:U('Login/index',array('referer'=>urlencode($referer)))}">立即登录</a></p>
		    </div>
		</div>
	</div>
	<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#sms_code').focus(function(){
				if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
					verify_flesh();
				}
			});
			if($('body').height() < $(window).height()){
				$('.site-info-w').css({'position':'absolute','width':'100%','bottom':'0'});
			}
			$('#login-phone').change(function(){
				$('#send_sms').attr('onclick','verify_flesh();')
			});
			$("#J-login-form").submit(function(){
				$('.validate-info').css('visibility','hidden');
				$('#commit').val('注册中...').prop('disabled',true);
				
				$("#login-phone").val($.trim($("#login-phone").val()));
				$("#login-password").val($.trim($("#login-password").val()));
				$("#login-repassword").val($.trim($("#login-repassword").val()));
				
				if ($('#register_agreement').size() > 0 && !($('#register_agreement').is(':checked'))) {
					error_tips('同意注册协议后才能注册','register_agreement');
					return false;
				}
				var phone = $("#login-phone").val();
				var pwd = $("#login-password").val();
				<if condition="C('config.reg_verify_sms') AND C('config.sms_key')">
					var sms_code = $("#sms_code").val();
				</if>
				<if condition="C('config.sms_verify_fleshcode') eq 1">
					var fleshcode = $("#sms_flesh_verify").val();
					var fleshtype = $("#sms_flesh_type").val();
					if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
						verify_flesh();
					}
				</if>
				var repwd = $("#login-repassword").val();
				if(phone == '' || phone == null){
					error_tips('手机号不能为空','login-phone');
					return false;
				}
				if(!international_phone && !/^[0-9]{11}$/.test(phone)){
					error_tips('请输入11位数字的手机号码','login-phone');
					return false;
				}
				if(pwd == '' || pwd == null){
					error_tips('密码不能为空','login-password');
					return false;
				}
				if(pwd != repwd){
					error_tips('两次密码输入不一致','login-repassword');
					return false;
				}
				
				var spread_code = $('#spread_code').val()
				if(typeof(spread_code)=='undefined'){
					spread_code='';
				}
				if( typeof($('#phone_country_type').val())!='undefined'&& $('#phone_country_type').val()!=''){
					phone = $('#phone_country_type').val()+'|'+phone;
				}
				$.post("{pigcms{:U('Index/Login/reg')}", {'phone':phone,'pwd':pwd <if condition="C('config.reg_verify_sms') AND C('config.sms_key')">,sms_code:sms_code</if><if condition="$config.sms_verify_fleshcode eq 1 ">,verify:fleshcode,type:fleshtype</if><if condition="$config.open_score_fenrun eq 1">,spread_code:spread_code</if>}, function(data){
					if(data.error_code){
						$("#commit").val('注册').prop('disabled',false);
						$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+data.msg).css('visibility','visible');
						return false;
					}else if(data.user){
						$('.validate-info').html('<i class="tip-status tip-status--success"></i>注册且登录成功！正在跳转.').css('visibility','visible');
						setTimeout("location.href='{pigcms{$referer}'", 1000);
					}else{
						$('.validate-info').html('<i class="tip-status tip-status--success"></i>注册成功！正在跳转登录页.').css('visibility','visible');
						setTimeout("location.href='{pigcms{:U('Login/index',array('referer'=>urlencode($referer)))}'", 1000);
					}
				}, 'json');
				return false;
			});
			$('#read_register_agreement').click(function(){
				art.dialog.open("{pigcms{:U('Login/register_agreement')}&"+Math.random(),{
					init: function(){
						var iframe = this.iframe.contentWindow;
						window.top.art.dialog.data('login_iframe_handle',iframe);
					},
					id: 'login_handle',
					title:'注册协议',
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
			});
			
			art.dialog.open("{pigcms{:U('Index/Recognition/see_login_qrcode',array('referer'=>urlencode($referer)))}&"+Math.random(),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('login_iframe_handle',iframe);
				},
				id: 'login_handle',
				title:'微信扫码快速注册',
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
		});
		function error_tips(msg,id){
			$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+msg).css('visibility','visible');
			$("#commit").val('注册').prop('disabled',false);
			$('#'+id).focus();
		}
		
		var countdown = 60;
		
		function verify_flesh(){
			art.dialog.open("{pigcms{:U('Index/Verify/verify_fleshcode')}&"+Math.random(),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('login_iframe_handle',iframe);
				},
				id: 'login_handle',
				title:'发送短信前请验证短信码',
				padding: 0,
				width: 430,
				height: 200,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4',
				close: function () {   
					var verify = art.dialog.data('sms_flesh_verify'); // 读取子窗口返回的数据  
					if(verify){
						$('#sms_flesh_verify').val(verify);
						$('#send_sms').attr('onclick','sendsms(this);')
					}
				}  
			});
		}
		
		
		function sendsms(val){
	
			if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
				error_tips('请先验证4位验证码','login-repassword');
				return false;
			}
			if($("input[name='phone']").val()==''){
				alert('手机号码不能为空！');
			}else{
				
				var fleshcode = $("#sms_flesh_verify").val();
				var fleshtype = $("#sms_flesh_type").val();
				if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
					verify_flesh();
				}
				
				if(countdown==60){
					$.ajax({
						url: '{pigcms{$config.site_url}/index.php?g=Index&c=Login&a=Generate',
						type: 'POST',
						dataType: 'json',
						data: {phone: $("input[name='phone']").val(),reg:1<if condition="$config.sms_verify_fleshcode eq 1">,verify:fleshcode,type:fleshtype</if>},
						success:function(date){
							if(date.error_code){
								$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+date.msg).css('visibility','visible');
							}
						}

					});
				}
				if (countdown == 0) {
					val.removeAttribute("disabled");
					val.value="验证短信";
					countdown = 60;
					//clearTimeout(t);
				} else {
					val.setAttribute("disabled", true);
					val.value="重新发送(" + countdown + ")";
					countdown--;
					setTimeout(function() {
						sendsms(val);
					},1000)
				}
			}
		}
	</script>
	<include file="Public:footer"/>
</body>
</html>