<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>找回密码  - {pigcms{$config.site_name}</title>
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
			    
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			            		<dd class="dd-padding">
			            			<input id="phone" class="input-weak" type="tel" placeholder="手机号" name="phone" value="" required="">
			            		</dd>
								<if condition=" $config.sms_key">
									<if condition="$config.sms_verify_fleshcode eq 1">
										<dd class="kv-line-r dd-padding" id="flesh_code" >
											<input id="sms_flesh_code" class="input-weak kv-k" name = "flesh_code" type="text" placeholder="填写验证码" value=""  required style="width: 3.0rem;"/>
											<input id="sms_flesh_type" name = "fleshtype" type="hidden" value="sms" />
											<input id="sms_flesh_verify" name = "verify" type="hidden" value="" />
											<img src="./index.php?c=Verify&a=fleshcode&type=sms" id="reg_verifyImg" class="btn  kv-v"title="刷新验证码" alt="刷新验证码" style="background:none;padding:0rem"/>
											<button id="verify_flesh" type="button" class="btn btn-weak kv-v" style="padding:0rem">发送短信前请验证</button>
										</dd>
									</if>
									<dd class="kv-line-r dd-padding" id="sms" <if condition="$config.sms_verify_fleshcode eq 1">style="display:none"</if>>
										<input id="vfycode" class="input-weak kv-k sms_code" name = "vcode" type="text" placeholder="填写短信验证码"  <if condition="$config.sms_verify_fleshcode eq 0">required</if>/>
										<button id="reg_send_sms" type="button" onclick="sendsmspwd(this)" class="btn btn-weak kv-v">获取短信验证码</button>
									</dd>
								</if>
			            		
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
						<button type="submit" onclick="forgetpwd(this);" class="btn btn-larger btn-block">提交</button>
			        </div>
			   
			</div>
			<ul class="subline">
			    <li><a href="{pigcms{:U('Login/index')}">立即登录</a></li>
			</ul>
		</div>
		
		
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}js/reg.js"></script>
		<script>
		function  forgetpwd(val){
			var vfycode  = $('#vfycode').val();
			if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
				$('#tips').html('请先验证4位验证码').show();
				return false;
			}
			<if condition="$config.sms_verify_fleshcode eq 1">
				var fleshcode = $("#sms_flesh_verify").val();
				var fleshtype = $("#sms_flesh_type").val();
				if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
					verify_flesh();
				}
			</if>
			$.ajax({
				url: '{pigcms{$config.site_url}/index.php?g=Index&c=Login&a=Generate',
				type: 'POST',
				dataType: 'json',
				data: {phone: $("input[name='phone']").val(),vfycode:vfycode,tmpid:1<if condition="$config.sms_verify_fleshcode eq 1">,verify:fleshcode,type:fleshtype</if>},
				success:function(date){
					if(date.error_code==2){
						window.location.href="{pigcms{:U('Login/pwdModify')}&pm="+date.urlpm;
					}else{
						$('#tips').html(date.msg).show();
					}
				}
			});
			
		}
			
			var countdown = 60;
			function sendsmspwd(val){
			<if condition="$config.sms_verify_fleshcode eq 1">
				var fleshcode = $("#sms_flesh_verify").val();
				var fleshtype = $("#sms_flesh_type").val();
				if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
					verify_flesh();
				}
			</if>
				if($("input[name='phone']").val()==''){
					alert('手机号码不能为空！');
				}else{
					
					if(countdown==60){
						$.ajax({
							url: '{pigcms{$config.site_url}/index.php?g=Index&c=Login&a=Generate',
							type: 'POST',
							dataType: 'json',
							data: {phone: $("input[name='phone']").val(),vfycode:'',tmpid:0<if condition="$config.sms_verify_fleshcode eq 1">,verify:fleshcode,type:fleshtype</if>},
							success:function(date){
								flage=date.id;
								if(date.error_code){
									$('#tips').html(date.msg).show();
								}
							}

						});
					}
					if (countdown == 0) {
						val.removeAttribute("disabled");
						val.innerText="获取短信验证码";
						countdown = 60;
						//clearTimeout(t);
					} else {
						val.setAttribute("disabled", true);
						val.innerText="重新发送(" + countdown + ")";
						countdown--;
						setTimeout(function() {
							sendsms(val);
						},1000)
					}
				}
			}
		</script>
		<include file="Public:footer"/>

{pigcms{$hideScript}
	</body>
</html>