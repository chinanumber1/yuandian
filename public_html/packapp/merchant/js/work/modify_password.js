if(common.checkWeixin()){
	$('.mui-bar-nav').remove();
}


var merchant_info = common.getCache('merchant_info',true);

$('#phoneTxt').html(merchant_info.phone.substr(0,3)+'****'+merchant_info.phone.substr(-4,4));

common.http('Merchantapp&a=config', {}, function(data){
	if(data.open_merchant_reg_sms == '1'){
		$('.smscode').show();
	}
	
		
	if(data.international_phone == '1'){
		$('.phone_country').show();

	}

});




mui.init();

mui('.mui-content').on('tap','.sendCode',function(e){
	common.http('Merchantapp&a=sendCode',{}, function(data){
		motify.log('短信发送成功');
		$('.sendCode').data('second',59).html('59 秒').prop('disabled',true);
		var smsTimer = setInterval(function(){
			var second = $('.sendCode').data('second');
			if(second == 1){
				$('.sendCode').html('获取验证码').prop('disabled',false);
				clearInterval(smsTimer);
			}else{
				second--;
				$('.sendCode').data('second',second).html(second + ' 秒');
			}
		},1000);
	});
	
	
});

mui('.mui-content').on('tap','.sendCode2',function(e){
	if($('#new_phone').val()==''){
		motify.log('新手机不能为空');
		return false;
	}
	common.http('Merchantapp&a=sendCode',{newphone:$('#new_phone').val()}, function(data){
		motify.log('短信发送成功');
		$('.sendCode2').data('second',59).html('59 秒').prop('disabled',true);
		var smsTimer = setInterval(function(){
			var second = $('.sendCode2').data('second');
			if(second == 1){
				$('.sendCode2').html('获取验证码').prop('disabled',false);
				clearInterval(smsTimer);
			}else{
				second--;
				$('.sendCode2').data('second',second).html(second + ' 秒');
			}
		},1000);
	});
});

mui('.mui-content').on('tap','.modify_password',function(e){
	if($('#smsCode').val().length != 4){
		mui.alert('请输入4位的短信验证码');
	}else if($('#newPwd').val().length < 6){
		mui.alert('请输入6位以上的新密码');
	}else if($('#newPwd').val() != $('#confirmPwd').val()){
		mui.alert('两次输入密码不一致');
	}else{
		var postData = {
			smsCode:$('#smsCode').val(),
			newPwd:$('#newPwd').val()
		};
		common.http('Merchantapp&a=changePwd',postData,function(data){
			motify.log('密码修改成功，下次请使用新密码登录');
		});
	}
});

mui('.mui-content').on('tap','.modify_phone',function(e){
	if($('#smsCode').val().length != 4 && config.open_merchant_reg_sms==1){
		mui.alert('请输入4位的短信验证码');
	}else if(!/^[0-9]{11}$/.test($('#new_phone').val())){
		mui.alert('请输入正确的手机号码');
	}else{
		var postData = {
			smsCode:$('#smsCode').val(),

			new_phone:$('#new_phone').val(),
			phone_country_type:$('#phone_country_type').val(),
			smsCode2:$('#smsCode2').val()
		};
		common.http('Merchantapp&a=changePhone',postData,function(data){
			 merchant_info.phone = $('#new_phone').val()
			 // common.setCache('merchant_info','');
			 common.setCache('merchant_info',merchant_info,true);
			motify.log('手机修改成功，下次请使用新手机登录');
			window.location.reload();
		});
	}
});