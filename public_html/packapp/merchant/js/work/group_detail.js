$(document).ready(function(){
	$(".mask").height($(window).height());
	
//	common.onlyScroll($('.scroll'));
	
	$(document).on('click', '.consum', function(){
		var type = $(this).data('type');
		if (type == 1) {
			common.http('Storestaff&a=group_pass_array', {'order_id':urlParam.order_id, 'noTip':true}, function(data){
				laytpl($('#passList').html()).render(data.pass_array, function(html){
					$('.consum_tc').find('ul').html(html);
				});
			});
			$(".consum_tc,.mask").fadeIn();
			myScroll.refresh();
		} else {
			common.http('Storestaff&a=group_verify', {'order_id':urlParam.order_id, 'noTip':true}, function(data){
				motify.log('验证消费成功！');
				setTimeout(location.reload(), 5000);
			});
		}
	});

	$(document).on('click', '.consum_tc_n span.a39', function(){
		var group_pass = $(this).data('pass'), obj = $(this);
		common.http('Storestaff&a=group_array_verify', {'order_id':urlParam.order_id, 'group_pass':group_pass, 'noTip':true}, function(data){
			motify.log('验证消费成功！');
			obj.removeClass('a39').addClass('ecc').html('已消费').unbind('click');
		});
	});
	$(document).on('click', '#allVerify', function(){
		common.http('Storestaff&a=group_verify', {'order_id':urlParam.order_id, 'noTip':true}, function(data){
			motify.log('验证消费成功！');
			setTimeout(location.reload(), 5000);
		});
	});
	
	$(document).on('click', '.del, .mask', function(){
		$(".consum_tc,.mask").fadeOut();
	});
	
//	common.http('Storestaff&a=group_edit', {'order_id':urlParam.order_id}, function(data){
//		laytpl($('#goodsDetail').html()).render(data.now_order, function(html){
//			$('.commod').html(html);
//		});
//		laytpl($('#userDetail').html()).render(data.user, function(html){
//			$('.user').html(html);
//		});
//		laytpl($('#addressDetail').html()).render(data, function(html){
//			$('.distri').html(html);
//		});
//		laytpl($('#orderDetail').html()).render(data.now_order, function(html){
//			$('.order').html(html);
//		});
//		if (data.trade_hotel_info != '') {
//			laytpl($('#hotelDetail').html()).render(data.trade_hotel_info, function(html){
//				$('.hotel').html(html);
//			});
//		} else {
//			$('.hotel').remove();
//		}
//		laytpl($('#paymentDetail').html()).render(data.now_order, function(html){
//			$('.payment').html(html);
//		});
//		
//		if(data.now_order.tuan_type < 2){
//			$('#distriBox').hide();
//		}
//		if(data.now_order.status == 6){
//			$('#paymentBox').hide();
//		}
//		
//		$('.leaving').html('<div class="top clr"><h2 class="fl">买家留言</h2></div><div class="textarea"><textarea placeholder="暂无" readonly="readonly">' + data.now_order.delivery_comment + '</textarea></div>');
//	});
	
	$(document).on('click', '.preser', function(){
		var express_id = $('.distri').find('.input input').val(), express_type = $(".distri").find("select option:selected").val();
		common.http('Storestaff&a=group_express', {'order_id':urlParam.order_id, 'express_id':express_id, 'express_type':express_type, 'noTip':true}, function(data){
			motify.log('保存成功！');
		});
	});
	
	$(document).on('click', '.distri .modify', function(){
		var merchant_remark = $('.distri').find('.textarea textarea').val();
		common.http('Storestaff&a=group_remark', {'order_id':urlParam.order_id, 'merchant_remark':merchant_remark, 'noTip':true}, function(data){
			motify.log('修改成功！');
		});
	});
});