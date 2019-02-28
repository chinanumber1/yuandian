var qrcode_id = 0,redirectTimer=null;
$(function(){
	$.getJSON('./index.php?c=CommFilePc&a=get_qrcode',function(result){
		if(result && result.error_code == false){
			console.log('code--------->', "./index.php?g=Index&c=CommFilePc&a=get_qr_code&file_ticket=" + result.file_ticket + "&id=" + result.id);
			$('.qrcode .img').attr('src', "./index.php?g=Index&c=CommFilePc&a=get_qr_code&file_ticket=" + result.file_ticket + "&id=" + result.id);
			qrcode_id = result.id;
			window.setTimeout(function(){
				ajax_weixin_login();
			},3000);
		}
	});
	$('.action').click(function(){
		$('.qrcode').show();
		$('.avatar').hide();
		$('#time').html('3');
		clearInterval(redirectTimer);
	});
});


function ajax_weixin_login(){
	$.getJSON("./index.php?c=CommFilePc&a=ajax_weixin_login",{qrcode_id:qrcode_id},function(result){
		if(result.status == 0 && result.info == 'reg_user'){
			$('#login_status').html('已扫描！请在微信公众号里点击授权登录。').css('color','green').show();
			ajax_weixin_login();
		}else if(result.status == 0 && result.info == 'no_user'){
			$('#login_status').html('没有查找到此用户，请重新扫描二维码！').css('color','red').show();
		}else if(result.status != 1){
			ajax_weixin_login();
		}else{
			$('.qrcode').hide();
			$('.avatar').show();
			$('.avatar .img').attr('src',result.info);
			var time = 3;
			redirectTimer = setInterval(function(){
				if(time == 0){
					clearInterval(redirectTimer);
					location.href = './index.php?c=CommFilePc&a=redirect';
				}else{
					$('#time').html(time);
				}
				time--;
			},1000);
			// $('#login_status').html('登录成功！正在跳转。').css('color','green').show();
			// location.href = './index.php?c=Impc&a=redirect';
		}
	});
}
