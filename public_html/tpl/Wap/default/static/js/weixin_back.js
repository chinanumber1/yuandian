var reg_flag = true,countdown = 60;
$(function(){
	$('.taba .slide').css({'left':$('.taba .active').offset().left,'width':$('.taba .active').width()});
	$(window).resize(function(){
		$('.taba .slide').css({'left':$('.taba .active').offset().left,'width':$('.taba .active').width()});
	});
	
	$('#login-form').submit(function(){
		var phone = $.trim($('#login_phone').val());
		$('#login_phone').val(phone);
		if(phone.length == 0){
			$('#tips').html('请输入手机号码。').show();
			return false;
		}
		if(!international_phone && !/^[0-9]{11}$/.test(phone)){
			$('#tips').html('请输入11位数字的手机号码。').show();
			return false;
		}
		
		var password = $('#login_password').val();
		if(password.length == 0){
			$('#tips').html('请输入密码。').show();
			return false;
		}
		if( typeof($('#phone_country_type').val())!='undefined'&& $('#phone_country_type').val()!=''){
			phone = $('#phone_country_type').val()+'|'+phone;
		}
		
		$.post($('#login-form').attr('action'),{phone:phone,password:password},function(result){
			if(result.status == '1'){
				if(is_specificfield){
					layer.open({
						title:['提醒：','background-color:#8DCE16;color:#fff;'],
						content:'请填写个人完善信息，能更快通过审核认证，获得优惠哦！',
						btn: ['确认', '取消'],
						shadeClose: false,
						yes: function(){
							window.location.href = "{pigcms{:U('My/inputinfo')}";
						},
						no: function(){
							window.location.href = $('#login-form').attr('location_url');
						}
					});
				}else{
					window.location.href = $('#login-form').attr('location_url');
				}
			}else{
				$('#tips').html(result.info).show();
			}
		});
		
		return false;
	});
	
	$('.taban li').click(function(){
		$(this).addClass('active').siblings('li').removeClass('active');
		$('#'+$(this).attr('tab-target')).show().siblings('form').hide();
		
		$('.taba .slide').css({'left':$('.taba .active').offset().left,'width':$('.taba .active').width()});
	});
	
	$('#reg-form').submit(function(){
		if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
			$('#tips').html('请先验证4位验证码').show();
			return false;
		}

		var verify = '',type='';
		if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()!=''){
			verify = $('#sms_flesh_verify').val();
			type = $('#sms_flesh_type').val();
		}
		var openid = $('#openid').val();
		var phone = $.trim($('#reg_phone').val());
		$('#reg_phone').val(phone);
		if(phone.length == 0){
			$('#tips').html('请输入手机号码。').show();
			return false;
		}
		if(!/^[0-9]{11}$/.test(phone)){
			$('#tips').html('请输入11位数字的手机号码。').show();
			return false;
		}
		
		var password_type = $('#reg_password_type').val();
		if($('#sms_code')&&$('#sms_code').val()==''){
			$('#tips').html('输入的短信验证码有误。').show();
			return false;
		}
		
		var sms_code = $('#sms_code').val();
		if(password_type === '0'){
			var password = $('#reg_pwd_password').val();
		}else{
			var password = $('#reg_txt_password').val();
		}
		if(password.length < 6){
			$('#tips').html('请输入6位以上的密码。').show();
			return false;
		}
		
		if(typeof(sms_code)!='undefined'){
			if(sms_code.length > 6||isNaN(sms_code)){
				$('#tips').html('输入的短信验证码有误。').show();
				return false;
			}
		}
		var spread_code = $('#spread_code').val()
		if(typeof(spread_code)=='undefined'){
			spread_code='';
		}
		if(reg_flag){
			reg_flag = false;
		}else{
			$('#tips').html('注册中，请不要重复提交').show();
			return false;
		}
		$.post($('#reg-form').attr('action'),{phone:phone,password:password,sms_code:sms_code,openid:openid,verify:verify,type:type,spread_code:spread_code},function(result){
			if(result.status == '1'){
				if(is_specificfield){
					layer.open({
						title:['提醒：','background-color:#8DCE16;color:#fff;'],
						content:'请填写个人完善信息，能更快通过审核认证，获得优惠哦！',
						btn: ['确认', '取消'],
						shadeClose: false,
						yes: function(){
							window.location.href = "{pigcms{:U('My/inputinfo')}";
						},
						no: function(){
							window.location.href = $('#reg-form').attr('location_url');
						}
					});
				}else{
					window.location.href = $('#reg-form').attr('location_url');
				}
			}else{
				if(result.info == '-1'){
					alert('您的微信号已经注册，正在跳转');
					window.location.href = $('#reg-form').attr('location_url');
				}else{
					reg_flag = true;
					$('#tips').html(result.info).show();
				}
			}
		});
		return false;
	});
	
	
	$('#reg_changeWord').click(function(){
		if($(this).html() == '显示明文'){
			$('#reg_txt_password').val($('#reg_pwd_password').val()).show();
			$('#reg_pwd_password').hide();
			$(this).html('显示密文');
			$('#reg_password_type').val(1);
		}else{
			$('#reg_pwd_password').val($('#reg_txt_password').val()).show();
			$('#reg_txt_password').hide();
			$(this).html('显示明文');
			$('#reg_password_type').val(0);
		}
	});

	$('#verify_flesh').click(function(event) {
		send_fleshcode($('#sms_flesh_code').val(),$('#sms_flesh_type').val())
	});

	$('#reg_verifyImg').click(function(event) {
		reg_fleshVerify();
	});
});


function sendsms(val){
	if($("#reg_phone").val()==''){
		alert('手机号码不能为空！');
	}else{
		
		if(countdown==60){
			$.ajax({
				url: './index.php?g=Index&c=Login&a=Generate',
				type: 'POST',
				dataType: 'json',
				data: {phone: $("#reg_phone").val(),verify:$('#sms_flesh_verify').val(),type:$('#sms_flesh_type').val(),reg:1},
				success:function(date){
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
				$('#tips').html(date.msg).show();
				
			}else{
				$('#flesh_code').hide();
				$('#sms_flesh_verify').val(code);
				$('#sms').show();
				$('#password').show();
				$('#register').show();
				$('.register_agreement').show();
				$('#verify_flesh').hide();
				$('.sms_code').attr('required', 'true');
			
			}
			setTimeout(function(){ $('#tips').hide(); }, 3000);
		}
	});
	
}