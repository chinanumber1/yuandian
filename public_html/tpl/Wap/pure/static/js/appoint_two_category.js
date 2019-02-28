var myScroll,myScroll2,wx;
var isApp = motify.checkApp();
$(function(){
	$('#backBtn').click(function(){
		if(document.referrer && document.referrer != window.location.href){
			redirect(document.referrer,'openLeftWindow');
		}else{
			redirect(backUrl,'openLeftWindow');
		}
	});
	
	$('#container').css({'top':'99px','background-color':'#F6F6F7','display':'block','bottom':'50px'});
	$('#scroller').css({'min-height':($(window).height()-149+1)+'px'});
	
	var upIcon = $("#up-icon"),
		downIcon = $("#pullDown");
	myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false});
	myScroll.on("scroll",function(){
		$.each($('.newsheader li'),function(i,item){
			if($('#section'+$(item).data('id')).offset().top < 200){
				$(item).addClass('on').siblings('li').removeClass('on');
			}
		});
	});
	
	
	var mySwiper = $('.swiper-container1').swiper({
		slidesPerView:'auto',
		grabCursor: true,
		freeMode: true,
		simulateTouch:false
	});
	$('.headBox li').click(function(){
		// alert($('#section'+$(this).data('id')).offset().top);
		if(!$(this).hasClass('on')){
			$(this).addClass('on').siblings('li').removeClass('on');
			if($(this).data('id') == $('.headBox li:last').data('id')){
				myScroll.scrollToElement(document.querySelector('#section'+$(this).data('id')),0, null, null, IScroll.utils.ease.elastic);
			}else{
				myScroll.scrollToElement(document.querySelector('#section'+$(this).data('id')),1200, null, null, IScroll.utils.ease.elastic);
			}
		}
	});
	
	//判断商品图片加载完成
	var imgNum=$('.content-list img').length;
	$('.content-list img').load(function(){
		console.log($(this).attr('src'));
		if(!--imgNum && !isApp){
			myScroll.refresh();
		}
	});
});