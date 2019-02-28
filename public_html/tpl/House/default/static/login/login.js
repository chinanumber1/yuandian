$(function(){
	$('#login_account').focus();
	$('#login_form').submit(function(){
		notice('正在登录中~','loading');
		if($('#login_account').val()==''){
			notice('请输入帐号~','error');
			$('#login_account').focus();
			return false;
		}else if($('#login_pwd').val()==''){
			notice('请输入密码~','error');
			$('#login_pwd').focus();
		}else if($('#login_verify').val().length!=4){
			notice('请输入4位验证码~','error');
			$('#login_verify').focus();
		}else{
			$.post(login_check,$("#login_form").serialize(),function(result){
				result = $.parseJSON(result);
				if(result){
					if(result.error == 0){
						notice(result.msg,'ok');
						setTimeout(function(){
							window.parent.location = house_index;
						},1000);
					}else if(result.error == 7){
						var html = '<ul class="village_list_select">';
						$.each(result.house_list,function(i,item){
							html+= '<li data-village_id="'+item.village_id+'" '+(i == 0 ? 'class="first"' : '')+'><div>'+item.village_name+'</div></li>';
						});
							html+= '</ul>';
						art.dialog({
							title: '请选择小区',
							// background :'#600',
							opacity:'0.4',
							lock: true,
							fixed: true,
							resize: false,
							padding:'25px 0',
							content: html
						});
					}else{
						$('#login_'+result.dom_id).focus();
						notice(result.msg,'error');
					}
				}else{
					notice('登录出现异常，请重试！','error');
				}
			});
		}
		return false;
	});
	$('.village_list_select li').live('click',function(){
		var list = art.dialog.list;
		for (var i in list) {
			list[i].close();
		};
		$('#village_id').val($(this).data('village_id'));
		$('#login_form').trigger('submit');
	});
});
function login_fleshVerify(url){
	var time = new Date().getTime();
	$('#login_verifyImg').attr('src',url+"&time="+time);
}
function reg_fleshVerify(url){
	var time = new Date().getTime();
	$('#reg_verifyImg').attr('src',url+"&time="+time);
}
var notice_timer = null;
function notice(msg,pic){
	if($(window).height() > $('body').height()){
		if(notice_timer) clearTimeout(notice_timer);
		$('.notice').remove();
		$('body').append('<div class="notice"><img src="'+static_path+'login/img/'+pic+'.gif" />'+msg+'</div>');
		notice_timer = setTimeout(function(){
			$('.notice').remove();
		},5000);
	}else{
		if(pic != 'loading'){
			alert(msg);
		}
	}
}