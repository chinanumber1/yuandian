var noAnimate = true;

$(function(){
	$('#car_form_add').click(function(){
		var carnum = $.trim($('#carnum').val());
		if(carnum == ""){
			$('#iosDialog2 .weui-dialog__bd').html('请填写车牌号');
			$('#iosDialog2').show();
			return false;
		}
		var phone = $.trim($('#phone').val());
		if(phone == ""){
			$('#iosDialog2 .weui-dialog__bd').html('请填写您的手机号');
			$('#iosDialog2').show();
			return false;
		}
		$('#loadingToast').fadeIn(100);
		$.post($(this).data('url'),{car_area:$('#car_area').val(),carnum:carnum,phone:phone,tip_type:$('#tip_type').val()},function(result){
			if(result.status == 1){
				$('#get_pic_btn').attr('href',$('#get_pic_btn').data('href')+'&car_id='+result.info);
				$('#loadingToast').fadeOut(100);
				$('.msg_success').show();
			}else{
				$('#loadingToast').fadeOut(100);
				$('#iosDialog2 .weui-dialog__bd').html(result.info);
				$('#iosDialog2').show();
				return false;
			}
		});
	});
	$('#car_form_edit').click(function(){
		var carnum = $.trim($('#carnum').val());
		if(carnum == ""){
			$('#iosDialog2 .weui-dialog__bd').html('请填写车牌号');
			$('#iosDialog2').show();
			return false;
		}
		var phone = $.trim($('#phone').val());
		if(phone == ""){
			$('#iosDialog2 .weui-dialog__bd').html('请填写您的手机号');
			$('#iosDialog2').show();
			return false;
		}
		$('#loadingToast').fadeIn(100);
		$.post($(this).data('url'),{car_area:$('#car_area').val(),carnum:carnum,phone:phone,car_id:$(this).data('car_id'),tip_type:$('#tip_type').val()},function(result){
			$('#loadingToast').fadeOut(100);
			$('#iosDialog2 .weui-dialog__bd').html(result.info);
			$('#iosDialog2').show();
			return false;
		});
	});
	$('#car_form_del').click(function(){
		$('#iosDialog1 .weui-dialog__bd').html('您确定要删除该车辆吗？');
		$('#iosDialog1').fadeIn(100);
	});
	$('#iosDialog1').on('click', '.weui-dialog__btn_default', function(){
		$(this).parents('.js_dialog').fadeOut(200);
	});
	$('#iosDialog1').on('click', '.weui-dialog__btn_primary', function(){
		$(this).parents('.js_dialog').fadeOut(200);
		$('#loadingToast').fadeIn(100);
		$.post($('#car_form_del').data('url'),{car_id:$('#car_form_del').data('car_id')},function(result){
			$('#loadingToast').fadeOut(100);
			if(result.status == 1){
				window.location.href = $('#car_form_del').data('index_url');
			}
			$('#iosDialog2 .weui-dialog__bd').html(result.info);
			$('#iosDialog2').show();
			return false;
		});
	});
	
	$('#iosDialog2').on('click', '.weui-dialog__btn', function(){
		$(this).parents('.js_dialog').fadeOut(200);
	});
		
	$('#carnum').bind('input',function(){
		$(this).val($(this).val().toUpperCase());
	});
});