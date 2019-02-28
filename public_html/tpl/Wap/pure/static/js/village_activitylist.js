var myScroll,isLoading=false;
var isApp = motify.checkApp();
$(function(){
	$('#backBtn').click(function(){
		if(document.referrer){
			redirect(document.referrer,'openLeftWindow');
		}else{
			redirect(backUrl,'openLeftWindow');
		}
	});
	$('#scroller').css({'min-height':($(window).height()-50+1)+'px'});
	$('.newsBox dd div').css({width:$(window).width()-16-$('.newsBox dd:last .right').width()-5});
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
	if(isApp){
        $('#container').css({'top':'0px'});
    }
	var mySwiper = $('.swiper-container1').swiper({
		slidesPerView:'auto',
		grabCursor: true,
		freeMode: true,
		simulateTouch:false
	});
	
	$('.newsheader li').click(function(){
		if(!$(this).hasClass('on')){
			pageLoadTip(100);
			$(this).addClass('on').siblings('li').removeClass('on');
			getList(village_id);
		}
	});
});
getList(village_id);
function getList(village_id){
	$('.newsListBox dl').empty();
	isLoading = true;
	var go_url = location_url;
	$.post(go_url,{'village_id':village_id},function(result){
		if(result){
			laytpl($('#newsListBoxTpl').html()).render(result, function(html){
				$('.newsListBox dl').html(html);
			});
		}
		pageLoadTipHide();
		setTimeout(function(){
			// console.log(more);
			myScroll.refresh();
			if(!more){
				myScroll.scrollTo(0,0);
			}
		},200);
		isLoading = false;
	});
}