var goodsCart = [], goodsNumber = 0, goodsCartMoney = 0;
/* 简单的消息弹出层 */
var motify = {
	timer:null,
	/*shade 为 object调用 show为true显示 opcity 透明度*/
	log:function(msg,time,shade){
		$('.motifyShade,.motify').hide();
		if(motify.timer) clearTimeout(motify.timer);
		if($('.motify').size() > 0){
			$('.motify').show().find('.motify-inner').html(msg);
		}else{
			$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
		}
		if(shade && shade.show){
			if($('.motifyShade').size() > 0){
				$('.motifyShade').css({'background-color':'rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+')'}).show();
			}else{
				$('body').append('<div class="motifyShade" style="display:block;background-color:rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+');"></div>');
			}
		}
		if(typeof(time) == 'undefined'){
			time = 3000;
		}
		if(time != 0){
			motify.timer = setTimeout(function(){
				$('.motify').hide();
			},time);
		}
	},
	clearLog:function(){
		$('.motifyShade,.motify').hide();
	}
};

$(function() {
	//背景单窗高度  
	$(".Mask").css("height", $(document).height());

	//弹框
	$(".immediately").click(function(){
		$(".Popup").slideDown();
		$(".Mask").show();
	});
	$(".Popup_gb").click(function(){
		$(".Popup").slideUp();
		$(".Mask").hide();
	});

//	$(".tcTakewc").click(function(){
//		$(".Popup,.Takethe").hide();
//		$(".Takewc").show();
//		$(".Mask").hide();
//	});
	var minNum = $('#city').find("option:selected").data('min'), maxNum = $('#city').find("option:selected").data('max');
	if (minNum == maxNum && minNum == 1) {
		$('#num').parents('li').hide();
	} else {
		$('#num').parents('li').show();
	}
	var opt = {'select':{preset:'select'}}
    opt.default = {
	        theme: 'android-ics light', //皮肤样式
	        mode: 'scroller', //日期选择模式
			display: 'bottom', //显示方式
    		onSelect: function (valueText, inst) {
    			var minNum = $('#city').find("option:selected").data('min'), maxNum = $('#city').find("option:selected").data('max');
    			if (minNum == maxNum && minNum == 1) {
    				$('#num').parents('li').hide();
    			} else {
    				$('#num').parents('li').show();
    			}
            }
	};
	$('.demo-test-select').scroller($.extend(opt['select'], opt['default']));
	var queue_save = false;
	$(document).on('click', '#queue_save', function(){
		if (queue_save) return false;
		queue_save = true;
		if (1 > parseInt($('#num').val())) {
			motify.log('人数必须是大于0的数字');
			queue_save = false;
			return false;
		}
		$.post(queue_save_url, {'table_type':$('#city').val(), 'store_id':$('#store_id').val(), 'num':$('#num').val()}, function(response){
			if (response.err_code) {
				motify.log(response.msg);
				queue_save = false;
			} else {
				location.reload();
			}
		}, 'json');
	});
	var queue_cancel = false;
	$(document).on('click', '#queue_cancel', function(){
		if (queue_cancel) return false;
		queue_cancel = true;
		$.post(queue_cancel_url, {'store_id':$('#store_id').val()}, function(response){
			if (response.err_code) {
				motify.log(response.msg);
				queue_cancel = false;
			} else {
				location.reload();
			}
		}, 'json');
	});
	var notice_save = false;
	$(document).on('click', '#notice_save', function(){
		if (notice_save) return false;
		notice_save = true;
		$.post(notice_save_url, {'store_id':$('#store_id').val()}, function(response){
			if (response.err_code) {
				motify.log(response.msg);
				queue_cancel = false;
			} else {
				$('#notice_save').val('已设置取号提醒');
			}
		}, 'json');
	});
	
	$('.picture').click(function(){
		var album_array = [];
		$('.img').each(function(l){
			album_array[l] = $(this).val();
		});
		if(is_weixin()){
			wx.previewImage({
				current:album_array[0],
				urls:album_array
			});
		}else{
//			var album_html = '<div class="albumContainer h_gesture_ tap_gesture_" style="display:block;">';
//				album_html += '<div class="swiper-container">';
//				album_html += '		<div class="swiper-wrapper">';
//			$.each(album_array,function(i,item){
//				album_html += '			<div class="swiper-slide">';
//				album_html += '				<img src="'+item+'"/>';
//				album_html += '			</div>';
//			});
//				album_html += '		</div>';
//				album_html += '  	<div class="swiper-pagination"></div><div class="swiper-close" onclick="close_swiper()">X</div>';
//				album_html += '</div>';
//			
//			album_html += '</div>';
//			$('body').append(album_html);
//		
//			mySwiper = $('.swiper-container').swiper({
//				pagination:'.swiper-pagination',
//				loop:true,
//				grabCursor: true,
//				paginationClickable: true
//			});
		}
	});
});

function is_weixin(){
    var ua = navigator.userAgent.toLowerCase();
    if(is_mobile() && ua.match(/MicroMessenger/i)=="micromessenger") {  
        return true;  
    } else {  
        return false;  
    }  
}
function is_mobile(){
	if ((navigator.userAgent.match(/(iPhone|iPod|Android|ios|iPad)/i))){
		if((navigator.platform.indexOf("Win") == 0) || (navigator.platform.indexOf("Mac") == 0)){
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}