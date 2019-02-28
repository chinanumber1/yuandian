var myScroll,now_page = 0,hasMorePage = totalPage > 1 ? true : false;
var isApp = motify.checkApp();
$(function(){
	$('#backBtn').click(function(){
		if(document.referrer){
			redirect(document.referrer,'openLeftWindow');
		}else{
			redirect(backUrl,'openLeftWindow');
		}
	});
	$('#scroller').css({'min-height':($(window).height()-10+1)+'px'});
	$('.newsBox dd div').css({width:$(window).width()-16-$('.newsBox dd:last .right').width()-5});
	// myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
    if(isApp){
        $('#container').css({'top':'10px'});
    }
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
			pageLoadTip();
			window.location.href =window.location.href;
		}
	});
	myScroll.on("slideUp",function(){
		if(hasMorePage){
			$('#listDom').append('<dd class="loadMoreList">正在加载</dd>');
			// upIcon.addClass('loading');
			// setTimeout(function(){
				myScroll.refresh();
				myScroll.scrollTo(0,this.maxScrollY);
				getList(true);
			// },200);
		}
		/* if(this.maxScrollY - this.y > 50 && !upIcon.hasClass('noMore')){
			upIcon.addClass('noMore').hide();
		} */
	});
	getList(false);
});

function getList(more){
	isLoading = true;
	var go_url = location_url;
	now_page += 1;
	go_url += "&page="+now_page;
	$('.noMoreDiv').hide();
	$.post(go_url,function(result){
		$('.loadMoreList').remove();
		if(result){
			hasMorePage = now_page < totalPage ? true : false;
			laytpl($('#BoxTpl').html()).render(result, function(html){
				$('#listDom').append(html);
			});
			if(!hasMorePage){
				$("#pullUp").addClass('noMore').hide();
				$('#listDom').append('<dd class="noMore">更多商户正在入驻，敬请期待!</dd>');
			}
		}else{
			$('.noMoreDiv').show();
			$("#pullUp").addClass('noMore').hide();
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