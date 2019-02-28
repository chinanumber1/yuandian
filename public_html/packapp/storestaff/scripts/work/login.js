$(document).ready(function(){
	var ticket = common.getCache('ticket');
	if (ticket) {
		location.href = 'index.html';
		return false;
	}
	$(".signin").height($(window).height());
	$('#passwd').keyup(function(e){
		if(e.keyCode == 13){
			$(".sign_bun").trigger('click');
			return false;
		}
	});
	$(".sign_bun").click(function(){
		var account = $('#account').val(), passwd = $('#passwd').val();
		if (account.length < 1) {
			motify.log("登录账号不能为空");
			return false;
		}
		if (passwd.length < 1) {
			motify.log("登录密码不能为空");
			return false;
		}
		var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
		common.http('Storestaff&a=login', {'account':account, 'passwd':passwd,'client':client}, function(data){
			common.setCache('ticket',data.ticket,true);
			common.setCache('store_staff',data.user,true);
			if($('#checkbox_c1').attr('checked')){
				common.setCache('ticket',data.ticket);
			}
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