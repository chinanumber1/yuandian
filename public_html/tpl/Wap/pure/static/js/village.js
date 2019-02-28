var myScroll;
var isApp = motify.checkApp();
$(function(){
	$('#backBtn').click(function(){
		redirect(backUrl,'openLeftWindow');
	});
	$('#scroller').css({'min-height':($(window).height()-50+1)+'px'});
	$('.newsBox dd div').css({width:$(window).width()-16-$('.newsBox dd:last .right').width()-5});
	// myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
    if(isApp){
        $('#container').css({'top':'0px'});
        $('#container,#scroller').css({'position':'static'});
        $('body').append('<style>::-webkit-scrollbar{width:0px;}</style>');
    }else{
        myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
        var upIcon = $("#pullUp"),
        downIcon = $("#pullDown");
	    myScroll.on("scroll",function(){
		    var maxY = this.maxScrollY - this.y;
		    if(this.y >= 50){
			    if(!downIcon.hasClass("reverse_icon")) downIcon.addClass("reverse_icon").find('.pullDownLabel').html('松开可以刷新');
			    return "";
		    }else if(this.y < 50 && this.y > 0){
			    if(downIcon.hasClass("reverse_icon")) downIcon.removeClass("reverse_icon").find('.pullDownLabel').html('下拉可以刷新');
			    return "";
		    }
	    });
	    myScroll.on("slideDown",function(){
		    if(this.y > 50){
			    pageLoadTip();
			    window.location.href =window.location.href;
		    }
	    });
    }
	
	var mySwiper = $('.swiper-container1').swiper({
		pagination:'.swiper-pagination1',
		loop:true,
		grabCursor: true,
		paginationClickable: true,
		autoplay:3000,
		autoplayDisableOnInteraction:false,
		simulateTouch:false
	});
	
	
	var mySwiper = $('.swiper-container1').swiper({
		pagination:'.swiper-pagination1',
		loop:true,
		grabCursor: true,
		paginationClickable: true,
		autoplay:3000,
		autoplayDisableOnInteraction:false,
		simulateTouch:false
	});
	var mySwiper2 = $('.swiper-container2').swiper({
		pagination:'.swiper-pagination2',
		loop:true,
		grabCursor: true,
		paginationClickable: true,
		simulateTouch:false
	});
	$('.swiper-container3 .swiper-slide').width($('.swiper-container3 .swiper-slide').width());
	var mySwiper3 = $('.swiper-container3').swiper({
		freeMode:true,
		freeModeFluid:true,
		slidesPerView: 'auto',
		simulateTouch:false/*,
		centeredSlides: true*/
	});
	$('.swiper-container4 .swiper-slide').width($('.swiper-container4 .swiper-slide').width());
	var mySwiper4 = $('.swiper-container4').swiper({
		freeMode:true,
		freeModeFluid:true,
		slidesPerView: 'auto',
		simulateTouch:false/*,
		centeredSlides: true*/
	});
});