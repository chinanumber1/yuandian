var myScroll,wx;
$(function(){
	/* if(!app_version){
		$(window).resize(function(){
			window.location.reload();
		});
	} */
	
	$('#container').css({'top':50});
	$('#scroller').css({'min-height':($(window).height()-99+1)+'px'});
	var upIcon = $("#up-icon"),
		downIcon = $("#pullDown");
	myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false});
	myScroll.on("scroll",function(){
		if(this.y >= 50){
			if(!downIcon.hasClass("reverse_icon")) downIcon.addClass("reverse_icon").find('.pullDownLabel').html('释放可以刷新');
			return "";
		}else if(this.y < 50 && this.y > 0){
			if(downIcon.hasClass("reverse_icon")) downIcon.removeClass("reverse_icon").find('.pullDownLabel').html('下拉可以刷新');
			return "";
		}
		
		/*if(maxY >= 50){
			!upHasClass && upIcon.addClass("reverse_icon");
			return "";
		}else if(maxY < 50 && maxY >=0){
			upHasClass && upIcon.removeClass("reverse_icon");
			return "";
		}*/
	});
//http://www.zhangyunling.com/study/slideUpDownRefresh/version_1/iscroll-test.html
	myScroll.on("slideDown",function(){
		if(this.y > 50){
			$('#container').css({'bottom':0});
			$('.footerMenu,#pullDown').hide();
			$('#scroller').animate({'top':$(window).height()+'px'},function(){
				upIcon.removeClass("reverse_icon");
				pageLoadTip();
				window.addEventListener("pagehide", function(){
					$('#container').css({'bottom':'49px'});
					$('#scroller').css({'top':'0px'});
					$('.footerMenu,#pullDown').show();
					pageLoadTipHide();
				},false);
				window.location.href =window.location.href;
			});
		}
	});
	
	/*myScroll.on("slideUp",function(){
		if(this.maxScrollY - this.y > 50){
			alert("slideUp");
			upIcon.removeClass("reverse_icon")
		}
	});*/
	
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
	$('.swiper-container4 .swiper-slide').width($('.swiper-container4 .swiper-slide').width());
	var mySwiper4 = $('.swiper-container4').swiper({
		freeMode:true,
		freeModeFluid:true,
		slidesPerView: 'auto',
		simulateTouch:false/*,
		centeredSlides: true*/
	});
	
	// motify.log('正在加载内容',0,{show:true});
	
	if($('.platformNews').size() > 0){
		var platformNewsIndex = 0;
		var platformNewsSize = $('.platformNews .list li').size();
		setInterval(function(){
			platformNewsIndex += 1;
			if((platformNewsIndex*2)+2>platformNewsSize){
				platformNewsIndex = 0;
			}
			$('.platformNews .list li').hide();
			$('.platformNews .list').find('.num-'+((platformNewsIndex*2)+1)+',.num-'+((platformNewsIndex*2)+2)).show();
		},4000);
	}
	
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
		$('.content_video').css({'width':$(window).width()-20,'height':($(window).width()-20)*9/16});
});