$(document).ready(function(){
	var height1=$(window).height();
	$('body,.mui-content').height(height1);
	var ticket = common.getCache('ticket');
	if (ticket) {
		location.href = 'index.html';
		return false;
	}
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
		if(phone.length < 1){
			motify.log("登录手机号/账号不能为空");
			return false;
		}
		if(pwd.length < 1){
			motify.log("登录密码不能为空");
			return false;
		}
		var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
		common.http('Merchantapp&a=login', {'phone':phone, 'pwd':pwd, 'client':client}, function(data){
			console.log(data);
			common.setCache('ticket',data.ticket,true);
			common.setCache('merchant_info',data.user,true);
			common.setCache('ticket',data.ticket);
			common.setCache('mer_id',data.mer_id);
			common.setCache('power',data.auth);
			common.setCache('card_recharge',data.auth.card_recharge);
			location.href = (urlParam.back ? urlParam.back : 'index')+'.html';
		});
	});
});

mui.init();

//我要入住点击
mui('.mui-content').on('tap','#register',function(e){
	openWindow({
		url:'reg.html',
		id:'reg'
	});
});
//忘记密码点击
mui('.mui-content').on('tap','#forgot',function(e){
	openWindow({
		url:'forgot_password.html',
		id:'forgot_password'
	});
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