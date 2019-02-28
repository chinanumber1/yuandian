$(function(){
	$('#reg-form').submit(function(){
		var phone = $.trim($('#reg_phone').val());
		$('#reg_phone').val(phone);
		if(phone.length == 0){
			$('#tips').html('请输入手机号码。').show();
			return false;
		}
		if( !international_phone && !international_phone && !/^[0-9]{11}$/.test(phone)){
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

		if(typeof(password)!='undefined'&&password.length < 6){
			$('#tips').html('请输入6位以上的密码。').show();
			return false;
		}
		
		if(typeof(sms_code)!='undefined'){
			if(sms_code.length > 6||isNaN(sms_code)){
				$('#tips').html('输入的短信验证码有误。').show();
				return false;
			}
		}
		if( typeof($('#phone_country_type').val())!='undefined'&& $('#phone_country_type').val()!=''){
			phone = $('#phone_country_type').val()+'|'+phone;
		}
		$.post($('#reg-form').attr('action'),{phone:phone,password:password,sms_code:sms_code,bind_exist:0},function(result){
			
			console.log($('#reg-form').attr('location_url'))
			// ="'"+$('#reg-form').attr('login_url')+'&referer'+$('#reg-form').attr('location_url')+"'"
			if(result.status == '1'){
				window.location.href = $('#reg-form').attr('location_url')+'&from_type=bind_user';
			}else{
				if(result.info=='phone_exist'){
					$('#tips').html("手机已存在").show();
					if(confirm("你确定要绑定已存在的账号吗？")){
						$.post($('#reg-form').attr('action'),{phone:phone,password:password,sms_code:sms_code,bind_exist:1},function(res){
							$('#tips').html(res.info).show();
							var referer = $('#reg-form').attr('login_url')+'&referer'+$('#reg-form').attr('location_url')
				
							if(referer){
								window.location.href=referer;
							}else{
								window.location.reload();
							}
						});
					}
				}else{
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
	if($("input[name='phone']").val()==''){
		alert('手机号码不能为空！');
	}else{

		if(countdown==60){
			$.ajax({
				url: './index.php?g=Index&c=Login&a=Generate',
				type: 'POST',
				dataType: 'json',
				data: {phone: $("input[name='phone']").val(),verify:$('#sms_flesh_verify').val(),type:$('#sms_flesh_type').val(),change_phone:$('#change_phone').val()},
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
				$('#tips').html(date.msg).show();
				$('#flesh_code').hide();
				$('#sms_flesh_verify').val(code);
				$('#sms').show();
				$('.sms_code').attr('required', 'true');
			
			}
		}
	});
	
}