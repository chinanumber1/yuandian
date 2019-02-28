var noAnimate = true;
var wait_timer = null;
var notice_timer = null;
var wait_time = 120;
var is_notice = false;
$(function(){
	$('#car_num_see').click(function(){
		if(is_notice){
			$('#iosDialog2 .weui-dialog__bd').html('已经通知过该车主，请等待');
			$('#iosDialog2').show();
			return false;
		}
		is_notice = true;
		$.post($('#car_num_see').data('url'),{car_id:$('#car_num_see').data('car_id')},function(result){
			if(result.status == 1){
				$('#iosDialog2 .weui-dialog__bd').html('已经成功通知车主');
				$('#iosDialog2').show();
				wait_timer = setInterval(function(){
					wait_time--;
					$('#wait_time').html('（'+wait_time+'秒）');
					if(wait_time == 0){
						$('#car_num_see').hide();
						$('#seeCarPhone').show();
						clearInterval(wait_timer);
						clearInterval(notice_timer);
					}
				},1000);
				notice_timer = setInterval(function(){
					$.post($('#car_num_see').data('notice_url'),{notice_id:result.info},function(noticeResult){
						if(noticeResult.status == 1){
							$('#iosDialog2 .weui-dialog__bd').html(noticeResult.info);
							$('#iosDialog2').show();
							clearInterval(wait_timer);
							clearInterval(notice_timer);
							$('#car_num_see').html('车主正在过来，请稍等');
						}
					});
				},3000);
			}else{
				$('#iosDialog2 .weui-dialog__bd').html(result.info);
				$('#iosDialog2').show();
			}
		});
		return false;
	});
	$('#iosDialog2').on('click', '.weui-dialog__btn', function(){
		$(this).parents('.js_dialog').fadeOut(200);
	});
});