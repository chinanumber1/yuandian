var myScroll,myScroll2;
$(function(){
	$('#container').css({'top':61,'background-color':'#F6F6F7','display':'block'});
	$('.leftBar,.rightBar').css({'min-height':($(window).height()-110+1)+'px'});
	$('.rightBar,.rightBar .scrollerBox').width($(window).width()-76-1);
	$('.rightBar .scrollerBox').width($(window).width()-76-1);
	
	$('#right_'+$('.leftBar li.cur').data('catid')).show();
	$.each($('#right_'+$('.leftBar li.cur').data('catid')+' .imgBox img'),function(i,item){
		$(this).attr('src',$(this).data('src')).data('src','');
	});
	
	var eachWidth = $('#right_'+$('.leftBar li.cur').data('catid')).find('.imgBox').width();
	$(".imgBox").each(function(){
		$(this).height(eachWidth*(5/9));
	})
	
	myScroll = new IScroll('.leftBar', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false});
	myScroll2 = new IScroll('.rightBar', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false});
	
	
	$('.leftBar li').click(function(){
		$(this).addClass('cur').siblings().removeClass('cur');
		$('#right_'+$(this).data('catid')).show().siblings().hide();
		$.each($('#right_'+$('.leftBar li.cur').data('catid')+' .imgBox img'),function(i,item){
			$(this).data('src') != '' && $(this).attr('src',$(this).data('src'));
		});
		myScroll2.refresh();
		myScroll2.scrollTo(0,0);
	});
	
	$('#qrcodeBtn').click(function(){
		if(motify.checkWeixin()){
			motify.log('正在调用二维码功能');
			wx.scanQRCode({
				desc:'scanQRCode desc',
				needResult:0,
				scanType:["qrCode"],
				success:function (res){
					// alert(res);
				},
				error:function(res){
					motify.log('微信返回错误！请稍后重试。',5);
				},
				fail:function(res){
					motify.log('无法调用二维码功能');
				}
			});
		}else{
			motify.log('您不是微信访问，无法使用二维码功能');
		}
	});
});