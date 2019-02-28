var bind_id = 0;
$(function(){
	$('.card_row').click(function(){
		bind_id = $(this).data('bind_id');
		if(order_id != ''){
			location.href = order_sms_url+'&order_id='+order_id+'&bind_id='+bind_id;
		}else{
			$('#iosDialog1 .weui-dialog__title').html('解绑确认');
			$('#iosDialog1 .weui-dialog__bd').html('您确认要解绑卡吗？<br/>卡号：'+($(this).find('.weui-media-box__title').html()));
			$('#iosDialog1').show();
		}
		
	});
	
	$('#creditFlag').change(function(){
		if($(this).val() == '2'){
			$('.creditCardBox').removeClass('hide');
		}else{
			$('.creditCardBox').addClass('hide');
		}
	});
	
	
	$('#car_form_add').click(function(){
		$('#userName').val($.trim($('#userName').val()));
		$('#cardNo').val($.trim($('#cardNo').val()));
		$('#phoneNo').val($.trim($('#phoneNo').val()));
		$('#certificateNo').val($.trim($('#certificateNo').val()));
		$('#checkNo').val($.trim($('#checkNo').val()));
		
		var postData = {};
		postData.userName = $('#userName').val();
		postData.cardNo = $('#cardNo').val();
		postData.phoneNo = $('#phoneNo').val();
		postData.certificateType = '01';
		postData.certificateNo = $('#certificateNo').val();
		postData.creditFlag = $('#creditFlag').val();
		
		if(postData.creditFlag == '2'){
			postData.checkNo = $('#checkNo').val();
			postData.checkExpiry = $('#checkExpiryYear').val()+$('#checkExpiryMonth').val();
		}
		
		console.log(postData);
		
		if(postData.userName == ''){
			alertMsg('请输入持卡人姓名');
			return false;
		}
		if(postData.cardNo == ''){
			alertMsg('请输入银行卡号');
			return false;
		}
		if(postData.phoneNo == ''){
			alertMsg('请输入银行预留手机号');
			return false;
		}
		if(postData.certificateNo == ''){
			alertMsg('请输入身份证号码');
			return false;
		}
		if(postData.creditFlag == '2' && postData.checkNo == ''){
			alertMsg('请输入信用卡背面最后3位数字');
			return false;
		}
		
		$('#loadingToast').fadeIn(100);
		var that = $(this);
		$.post(that.data('url'),postData,function(result){
			if(result.status == '1'){
				location.href = that.data('ok_url')+'&bind_id='+result.info;
			}else{
				$('#loadingToast').fadeOut(100);
				$('#iosDialog2 .weui-dialog__bd').html(result.info);
				$('#iosDialog2').show();
			}
			return false;
		});
	});
	
	
	$('#car_form_save').click(function(){
		$('#smsCode').val($.trim($('#smsCode').val()));
		
		var postData = {};
		postData.bind_id = $('#car_form_save').data('bind_id');
		postData.smsCode = $('#smsCode').val();
		
		if(postData.smsCode == ''){
			alertMsg('请输入短信验证码');
			return false;
		}
		$('#loadingToast').fadeIn(100);
		var that = $(this);
		$.post(that.data('url'),postData,function(result){
			if(result.status == '1'){
				$('#loadingToast').fadeOut(100);
				$('.msg_success').show();
			}else{
				$('#loadingToast').fadeOut(100);
				$('#iosDialog2 .weui-dialog__bd').html(result.info);
				$('#iosDialog2').show();
			}
			return false;
		});
	});
	
	
	$('#iosDialog2').on('click', '.weui-dialog__btn', function(){
		$(this).parents('.js_dialog').fadeOut(200);
	});
	
	$('#iosDialog1').on('click', '.weui-dialog__btn_default', function(){
		$(this).parents('.js_dialog').fadeOut(200);
	});
	$('#iosDialog1').on('click', '.weui-dialog__btn_primary', function(){
		$(this).parents('.js_dialog').hide(200);
		$('#loadingToast').fadeIn(100);
		var postData = {bind_id:bind_id};
		$.post(delete_url,postData,function(result){
			if(result.status == '1'){
				if($('.card_row').size() == 1){
					window.location.reload();
				}else{
					$('#loadingToast').fadeOut(100);
					$('.bind_'+bind_id).remove();
				}
			}else{
				$('#loadingToast').fadeOut(100);
				$('#iosDialog2 .weui-dialog__bd').html(result.info);
				$('#iosDialog2').show();
			}
			return false;
		});
	});
	
	if($('#get_order_sms').size() > 0){
		$('#get_order_sms').click(function(){
			if($(this).hasClass('gray')){
				console.log('error');
				return false;
			}
			$('#loadingToast').fadeIn(100);
			var postData = {bind_id:$(this).data('bind_id'),order_id:$(this).data('order_id')};
			$.post($(this).data('url'),postData,function(result){
				$('#loadingToast').fadeOut(100);
				if(result.status == '1'){
					var time = 59;
					$('#get_order_sms').addClass('gray').html(time + ' 秒');
					var timer = setInterval(function(){
						if(time == 1){
							clearInterval(timer);
							$('#get_order_sms').removeClass('gray').html('获取验证码');
						}else{
							time--;
							$('#get_order_sms').html(time + ' 秒');
						}
					},1000);
				}else{
					$('#iosDialog2 .weui-dialog__bd').html(result.info);
					$('#iosDialog2').show();
				}
				return false;
			});
		});
		$('#get_order_sms').trigger('click');
	}
	
	$('#card_pay_order').click(function(){
		var postData = {bind_id:$(this).data('bind_id'),order_id:$(this).data('order_id')};
		$('#smsCode').val($.trim($('#smsCode').val()));
		postData.smsCode = $('#smsCode').val();
		
		if(postData.smsCode == ''){
			alertMsg('请输入短信验证码');
			return false;
		}
		
		$('#loadingToast').fadeIn(100);
		$.post($(this).data('url'),postData,function(result){
			$('#loadingToast').fadeOut(100);
			if(result.status == '1'){
				location.href = result.info;
			}else{
				$('#iosDialog2 .weui-dialog__bd').html(result.info);
				$('#iosDialog2').show();
			}
			return false;
		});
	});
});

function alertMsg(msg){
	$('#iosDialog2 .weui-dialog__bd').html(msg);
	$('#iosDialog2').show();
}