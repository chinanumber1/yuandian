var myScroll,isLoading=false,hasMorePage = true;
var isApp = motify.checkApp();
var now_cat_id ;
var more = false,now_page = 0;
$(function(){
	
	getList(now_cat_id,false);
	$('#backBtn').click(function(){
		if(document.referrer){
			redirect(document.referrer,'openLeftWindow');
		}else{
			redirect(backUrl,'openLeftWindow');
		}
	});
	$('#scroller').css({'min-height':($(window).height()-50+1)+'px'});
	$('.newsBox dd div').css({width:$(window).width()-16-$('.newsBox dd:last .right').width()-5});
	// myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
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
		
		if(maxY >= 50){
			if(!upIcon.hasClass("reverse_icon")) upIcon.addClass("reverse_icon").find('.pullUpLabel').html('松开加载更多');
			return "";
		}else if(maxY < 50 && maxY >=0){
			if(upIcon.hasClass("reverse_icon")) upIcon.removeClass("reverse_icon").find('.pullUpLabel').html('上拉加载更多');
			return "";
		}
	});
	myScroll.on("slideDown",function(){
		if(this.y > 50){
			now_page = 0;
			hasMorePage = true;
			upIcon.removeClass('noMore loading').show();
			pageLoadTip(50);
			//getUserLocation({okFunction:'pageGetList',okFunctionParam:[true],errorFunction:'pageGetList',errorFunctionParam:[false]});
			getList(now_cat_id,false);
		}
	}); 
	
	myScroll.on("slideUp",function(){
		if(hasMorePage){	
			$('.newsListBox dl').append('<dd class="loadMoreList">正在加载</dd>');
			myScroll.refresh();
			myScroll.scrollTo(0,this.maxScrollY);
			getList(now_cat_id,true);
		}
	});
	if(isApp || window.__wxjs_environment === 'miniprogram'){
		$('header').remove();
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
			now_page=0;
			pageLoadTip(100);
			$(this).addClass('on').siblings('li').removeClass('on');
			now_cat_id =$(this).data('cat_id');
			getList($(this).data('cat_id'),false);
		}
	});
});

function getList(cat_id,more){
	isLoading = true;
	var go_url = location_url;
	go_url += "&cat_id="+cat_id;
	now_page += 1;
	go_url += "&page="+now_page;
	$.post(go_url,function(result){
		if(result.count > 0){
			hasMorePage = now_page < result.totalPage ? true : false;
			$('.listBox').addClass('storeListBox');
			laytpl($('#newsListBoxTpl').html()).render(result.news_list, function(html){
				if(more){
					if(hasMorePage){
						$("#pullUp").removeClass('noMore loading').show();
					}
					
					$('.loadMoreList').remove();
					$('.newsListBox dl').append(html);
				}else{
					$('.newsListBox dl').html(html).removeClass('dealcard').show();
				}
			});
			
			if(!hasMorePage){
				$("#pullUp").addClass('noMore').hide();
			}
		}else{
			$("#pullUp").addClass('noMore').hide();
			$('.newsListBox dl').hide();
			$('.newsListBox dl .no-deals').removeClass('hide');
		}
		pageLoadTipHide();
		setTimeout(function(){
			myScroll.refresh();
			if(!more){
				myScroll.scrollTo(0,0);
			}
		},200);
		isLoading = false;
	});
}