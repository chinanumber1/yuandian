$(document).ready(function(){
	
	$("body").css({"height":$(window).height()});
	
	$(".Land_end input").focus(function(){
		$(this).siblings("a").show();
	});
	$(".Land_end a").click(function(){
		$(this).hide();
		$(this).siblings("input").val("");
	});     
	
	var ticket = common.getCache('ticket');
	if (ticket) {
		location.href = 'index.html';
		return false;
	}
	
	$('.fillBg').css('min-height',$(window).height());
	if(common.checkWeixin()){
		common.fillPageBg(1,'#ebf3f8');
	}

	$('#login_pwd').keyup(function(e){
		if(e.keyCode == 13){
			$("#login_form").trigger('click');
			return false;
		}
	});
	$("#login_form").click(function(){
		var phone = $('#login_phone').val(), pwd = $('#login_pwd').val();
		if (phone.length < 1) {
			motify.log("登录手机号不能为空");
			return false;
		}
		if (pwd.length < 1) {
			motify.log("登录密码不能为空");
			return false;
		}
		var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
		common.http('Deliver&a=login', {'phone':phone, 'pwd':pwd, 'client':client}, function(data){
			common.setCache('ticket',data.ticket,true);
			common.setCache('deliver_user',data.user,true);
			common.setCache('ticket',data.ticket);
			location.href = (urlParam.back ? urlParam.back : 'index')+'.html';
		});
	})
});

var exitLayer = -1;
function appbackmonitor(){
	if(exitLayer != -1){
		window.pigcmspackapp.closewebview(2);
	}else{
		layer.closeAll();
		exitLayer = layer.open({
			content: '您确定要退出程序吗？再次按返回键将退出。'
			,btn: ['确定', '取消']
			,yes: function(index){
				window.pigcmspackapp.closewebview(2);
				layer.close(index);
			}
			,end: function(index){
				exitLayer = -1;
			}
		});
	}
}