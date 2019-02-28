<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="utf-8"/>
		<title>验证原手机</title>
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
				<form id="reg-form" action="{pigcms{:U('My/verify_original_phone')}" autocomplete="off" method="post" location_url="<if condition="$_GET['go'] eq 'password'">{pigcms{:U('password')}<elseif condition="$_GET['go'] eq 'bind_user'" />{pigcms{:U('bind_user')}</if>" login_url="{pigcms{:U('Login/index')}">
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
								<if condition="$config.international_phone eq 1">
			            		<dd class="dd-padding">
									 <select name="phone_country_type" id="phone_country_type" style="width:100%; height:30px;" disabled>
										  <option value="">请选择国家...,choose country</option>
										  <option value="86" <if condition="$now_user.phone_country_type eq 86">selected</if>>+86 中国 China</option>
										  <option value="1" <if condition="$now_user.phone_country_type eq 1">selected</if>>+1 加拿大 Canada</option>
									</select>
								</dd>
								</if>
			            		<dd class="dd-padding">
			            			<input id="phone" class="input-weak" type="text" placeholder="手机号" name="phone" value="{pigcms{$now_user['phone']}" readOnly required=""/>
									<input name="go" type="hidden" value="{pigcms{$_GET['go']}" /> 
			            		</dd>
								<if condition="C('config.bind_phone_verify_sms') AND C('config.sms_key')">
									<if condition="$config.sms_verify_fleshcode eq 1">
										<dd class="kv-line-r dd-padding" id="flesh_code" >
											<input id="sms_flesh_code" class="input-weak kv-k" name = "flesh_code" type="text" placeholder="填写验证码" value=""  required style="width: 3.0rem;"/>
											<input id="sms_flesh_type"  name = "type" type="hidden" value="sms" />
											<input id="sms_flesh_verify"  name = "verify" type="hidden" value="" />
											<img src="./index.php?c=Verify&a=fleshcode&type=sms" id="reg_verifyImg" class="btn  kv-v"title="刷新验证码" alt="刷新验证码" style="background:none"/>
											<button id="verify_flesh" type="button" class="btn btn-weak kv-v">发送短信前请验证</button>
										</dd>
									</if>
			            		<dd class="kv-line-r dd-padding"  id="sms" <if condition="$config.sms_verify_fleshcode eq 1">style="display:none"</if>>
			            			<input id="sms_code" class="input-weak kv-k" name = "vcode" type="text" placeholder="填写短信验证码" required/>
			            			<button id="reg_send_sms" type="button" onclick="sendsms(this)" class="btn btn-weak kv-v">获取短信验证码</button>
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
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script type="text/javascript">
			var countdown = 60;
			function sendsms(val){
				if($("input[name='phone']").val()==''){
					alert('手机号码不能为空！');
				}else{
					
					if(countdown==60){
						$.ajax({
							url: '{pigcms{$config.site_url}/index.php?g=Index&c=Login&a=Generate',
							type: 'POST',
							dataType: 'json',
							data: {phone: $("input[name='phone']").val(),verify_original_phone:1,verify:$('#sms_flesh_verify').val(),type:$('#sms_flesh_type').val()},

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
			
			
			function send_fleshcode(code,type){
				$.ajax({
					url: './index.php?c=Verify&a=verify_fleshcode',
					type: 'POST',
					dataType: 'json',
					data: {verify: code,type:type},
					success:function(date){
						if(date.error_code==1){
							$('#tips').html(date.msg).show();
						}else{
							$('#tips').html(date.msg).show();
							$('#flesh_code').hide();
							$('#sms_flesh_verify').val(code);
							$('#sms').show();
							$('.sms_code').attr('required', 'true');
						
						}
					}
				});
				
			}

			$('#verify_flesh').click(function(event) {
				send_fleshcode($('#sms_flesh_code').val(),$('#sms_flesh_type').val())
			});
		</script>
		
	

		<include file="Public:footer"/>
	</body>
</html>