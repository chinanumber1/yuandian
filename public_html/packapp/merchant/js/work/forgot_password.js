if(common.checkWeixin()){
	$('.mui-bar-nav').remove();
}

mui.init();

mui('.mui-content').on('tap','.sendCode',function(e){
	if($('#phone').val().length != 11){
		mui.alert('请输入11位的手机号码');
		return false;
	}
	common.http('Merchantapp&a=sendCode',{phone:$('#phone').val(),'type':4}, function(data){
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


mui('.mui-content').on('tap','.yes_mofidy',function(e){
	if($('#phone').val().length != 11){
		mui.alert('请输入11位的手机号码');
	}else if($('#smsCode').val().length != 4){
		mui.alert('请输入4位的短信验证码');
	}else if($('#newPwd').val().length < 6){
		mui.alert('请输入6位以上的新密码');
	}else{
		var postData = {
			type:4,
			phone:$('#phone').val(),
			smsCode:$('#smsCode').val(),
			newPwd:$('#newPwd').val()
		};
		common.http('Merchantapp&a=changePwd',postData,function(data){
			motify.log('密码修改成功，请使用新密码登录');
		setTimeout(function(){
                    openWindow({
		        url:'login.html',
		             id:'login'
	           });
                }, 3000);



		});
	}
});