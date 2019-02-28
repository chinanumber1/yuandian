<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="utf-8"/>
		<title>添加银行卡</title>
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>

		<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/index_wap.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/deposit.css" rel="stylesheet"/>
	</head>
	<body>
		<section class="public pageSliderHide">
			<div class="return link-url" data-url-type="openLeftWindow" data-url="back"></div>
			<div class="content">添加银行卡</div>
		</section>
		
		
        <div id="container" style="margin-top:44px;">
        	
			<div id="login">
				<form id="reg-form" action="{pigcms{:U('bind_card')}" autocomplete="off" method="post" >
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			            		<dd class="dd-padding">
			            			<div style="float:left;width: 30%;">真实姓名</div><input class="input-weak" style="float:right; width:70%"  type="tel" name="name" value="{pigcms{$deposit.realName}" readOnly required=""/>
			            		</dd>
								<dd class="dd-padding">
			            			<div style="float:left;width: 30%;">证件类型</div>    <input class="input-weak" style="float:right; width:70%"  type="tel" name="identityNo_type" value="身份证" readOnly required=""/>
			            		</dd>
								
								<dd class="dd-padding">
			            			<div style="float:left;width: 30%;">证件号码</div>    <input class="input-weak" style="float:right; width:70%"  type="tel" name="identityNo" value="{pigcms{$deposit.identityNo}" readOnly required=""/>
			            		</dd>
								
								<dd class="dd-padding">
			            			<div style="float:left;width: 30%;">银行卡号</div><input id="cardNo" class="input-weak" type="tel"  style="float:right; width:70%"  name="cardNo" value="{pigcms{$deposit.cardNo}"   required=""/>
			            		</dd>
							
			        		</dl>
			        	</dd>
			        </dl>
					 <dl class="list list-in"  style="margin-top: 11px;">
			        	<dd>
			        		<dl>
			            		
								
								<dd class="dd-padding">
			            			<div style="float:left;width: 30%;">手机号</div><input id="phone" class="input-weak" type="tel"  style="float:right; width:70%"  name="phone" value=""   required=""/>
			            		</dd>
								<dd class="dd-padding">
			            			<div style="float:left;width: 30%;">验证码</div><input class="input-weak" type="tel"  style="float: left;width: 30%;"  name="verificationCode" value=""   required=""/>
									<button id="reg_send_sms" type="button" onclick="sendsms(this)" class="btn btn-weak kv-v" style="float:right;">获取验证码</button>
			            		</dd>
			        		</dl>
			        	</dd>
			        </dl>
					<input type="hidden" name="tranceNum" value="">
					
					
					
			        <div class="btn-wrapper">
<!--						<button type="submit" class="btn btn-larger btn-block">注册并绑定</button>-->
						<button type="submit" class="btn btn-larger btn-block" style="    padding: 0 1px 1px 1px;width: 103px;height: 0.78rem;margin: 0 auto;font-size: 17px;">添加</button>
			        </div>
			    </form>
			</div>
		</div>
		
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}js/deposit.js"></script>
		<script>var international_phone = {pigcms{$config.international_phone|intval=###};</script>

	<script type="text/javascript">
			var countdown = 60;
			var sms_url = '{pigcms{:U('apply_bind_card')}';
			var sms_data  = {phone:$('#phone').val(),cardNo:$('#cardNo').val()}
			
			$(function(){
				$('#phone').change(function(){
					sms_data.phone = $(this).val();
				})
				
				$('#cardNo').change(function(){
					sms_data.cardNo = $(this).val();
				})
				
			})
			
			
			function sendsms(val){
				if($("input[name='phone']").val()==''){
					alert('手机号码不能为空！');
				}else{
					console.log(sms_data)
					if(countdown==60){
						$.ajax({
							url: sms_url,
							type: 'POST',
							dataType: 'json',
							data: sms_data,
							success:function(date){
								if(!date.status){
									
								}else{
									$('input[name="tranceNum"]').val(date.url.tranceNum)
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
	</body>
</html>