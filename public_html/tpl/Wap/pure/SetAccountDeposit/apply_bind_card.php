<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="utf-8"/>
		<title>绑定银行卡</title>
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
			<div class="content"><if condition="$_GET['add'] eq 1">添加银行卡<else />开户</if></div>
		</section>
		<div class="fieldset" style="border-width: 0 0 1px 0;margin-top:44px;">
			<div class="field" style="border: 0;">
				<div class="line line-a"></div>
				<span class="navigation">
					<span class="icon-a icon-complete">
						<img src="/static/api-view/images/icon/complete.png">
					</span>
					<span class="_label">绑定手机</span>
				</span>
				<span class="navigation nav1">
					<span class="icon-a icon-2-1">
						<img src="/static/api-view/images/icon/complete.png">
					</span>
					<span class="_label">实名认证</span>
				</span>
				<span class="navigation nav2">
					<span class="icon-a icon-3-2">
						<img src="/static/api-view/images/icon/3_1.png">
					</span>
					<span class="_label">绑定银行卡</span>
				</span>
			</div>
		</div>
		<span class="notice">姓名、证件信息需与绑定卡的银行预留信息一致</span>
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
			            			<div style="float:left;width: 30%;">银行名称</div>     
									<select name="unionCode" id="unionCode">
										<volist name="payMethod.unionCode" id="vo">
											<option value="{pigcms{$vo.1}" >{pigcms{$vo.0}</option>	
										</volist>
									</select>  
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
					<input type="hidden" name="aid" value="">
					<input type="hidden" name="transDate" value="">
					
					
					
			        <div class="btn-wrapper">
<!--						<button type="submit" class="btn btn-larger btn-block">注册并绑定</button>-->
						<button type="submit" class="btn btn-larger btn-block" style="    padding: 0 1px 1px 1px;width: 103px;height: 0.78rem;margin: 0 auto;font-size: 17px;">下一步</button>
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
				
				$('#unionCode').change(function(){
					sms_data.unionCode = $(this).val();
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
									alert(date.info+','+date.url);
								}else{
									$('input[name="tranceNum"]').val(date.url.tranceNum)
									$('input[name="aid"]').val(date.url.aid)
									$('input[name="transDate"]').val(date.url.transDate)
									
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