$(function(){
	$('.return').click(function(){
		history.go(-1)
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
