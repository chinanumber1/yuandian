var layer_index = null;
function checkApp(){
	if(/(pigcms_pack_app)/.test(navigator.userAgent.toLowerCase())){
		return true;
	}else{
		return false;
	}
}
function checkAndroid(){
	if(/(android)/.test(navigator.userAgent.toLowerCase())){
		return true;
	}else{
		return false;
	}
}
function checkAndroidApp(){
	if(checkApp() && checkAndroid()){
		return true;
	}else{
		return false;
	}
}
function iosFunction(string){
	$('body').append('<iframe id="iosIframe" src="pigcmspackapp://'+string+'"></iframe>');
	setTimeout(function(){
		$('#iosIframe').remove();
	},50);
}
$(function(){

	if(checkApp()){
		$('.down_excel').remove();
	}
	
	if($('.leftMenu').size() > 0){
		$('.leftMenu').height($(window).height()-50);
		$('.rightMain').height($(window).height()-60);
	}
	$('.urlLink').click(function(){
		var url = $(this).data('url');
		if(url == 'reload'){
			location.reload();
		}else{
			if(checkApp()){
				if($(this).attr('title') == '返回首页'){
					if(checkAndroidApp()){
						window.pigcmspackapp.closewebview(2);
					}else{
						common.iosFunction('closewebview/2');
					}
					return false;
				}
			}
			location.href = url;
		}	
	});
	
	if($('.fixed_header').size() > 0){
		var fhh = $('.fixed_header').height()+20;
		var fht = $('.fixed_header').offset().top
		$('.rightMain').css('padding-top',fhh);
		$('.fixed_header').css({'position':'fixed','top':fht-10,'width':$('.rightMain').width()});
		
		$('.rightMain').height($(window).height()-60-fhh);
	}
	
	$('.handle_btn').live('click',function(){
		var areaWH = ['80%', '80%'];
		if($(this).data('box_width')){
			areaWH[0] = $(this).data('box_width');
		}
		if($(this).data('box_height')){
			areaWH[1] = $(this).data('box_height');
		}
		layer_index = layer.open({
			id: $(this).data('layer_id') ? $(this).data('layer_id') : '',
			type: 2,
			title: $(this).data('title') ? ($(this).data('title') != 'no' ? $(this).data('title') : false) : '按钮缺少 data-title 参数',
			shadeClose: true,
			shade: 0.6,
			area: areaWH,
			content: $(this).attr('href')
		});
		return false;
	});
	
	//微信支付
	$(".scan_pay").click(function(){
		$(".chat, .shadow_two").show();
		$('#weixin_txt').focus();
		return false;
	});
	
		//返回
	$(".return").click(function(){
		window.location.reload();
		$(this).parents(".fix").hide();
		$('#weixin_txt').val('');
		$(".shadow_two").hide();
	});
	$("#scan_pay").click(function(){
		var add_url = $(this).data('href');
		var title_ = $(this).data('title') ? ($(this).data('title') != 'no' ? $(this).data('title') : false) : '按钮缺少 data-title 参数';
		$.ajax({
			url: scan_pay_url,
			type: 'POST',
			dataType: 'json',
			data: {payid: $('#scan_payid').val()},
			success:function(date){
				if (date.status == 1) {
					var areaWH = ['80%', '80%'];
					if($(this).data('box_width')){
						areaWH[0] = $(this).data('box_width');
					}
					if($(this).data('box_height')){
						areaWH[1] = $(this).data('box_height');
					}
					layer_index = layer.open({
						id: $(this).data('layer_id') ? $(this).data('layer_id') : '',
						type: 2,
						title:title_ ,
						shadeClose: true,
						shade: 0.6,
						area: areaWH,
						content: add_url+'&uid='+date.info.uid+'&from_scan=1&payid='+date.info.payid
					});

					return false;
				} else {
					layer.msg(date.info)
					
				}
			}
		});
		
	});
});
setInterval(function(){
	$.post("/store.php?g=Merchant&c=Store&a=ping");
},60000);