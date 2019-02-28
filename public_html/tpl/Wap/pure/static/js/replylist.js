var myScroll = null, now_page = 0, hasMorePage = true, isLoading = true;
$(function(){
	$(".details_comment p").css("width", $(".details_comment_top span").text() * 20);
	$('#container').css({'top':'35px', 'height':$(window).height()-35 + 'px'});
	$('#scroller').css({'min-height':($(window).height()-35)+'px'});
	myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
	var upIcon = $("#pullUp"), downIcon = $("#pullDown");
	myScroll.on("scroll",function(){
		var maxY = this.maxScrollY - this.y + 700;
		if (this.y >= 50) {
			if(!downIcon.hasClass("reverse_icon")) downIcon.addClass("reverse_icon").find('.pullDownLabel').html('松开可以刷新');
			return "";
		} else if(this.y < 50 && this.y > 0) {
			if(downIcon.hasClass("reverse_icon")) downIcon.removeClass("reverse_icon").find('.pullDownLabel').html('下拉可以刷新');
			return "";
		}
		if (maxY >= 50) {
			if(!upIcon.hasClass("reverse_icon")) upIcon.addClass("reverse_icon").find('.pullUpLabel').html('松开加载更多');
			return "";
		} else if(maxY < 50 && maxY >= 0) {
			if(upIcon.hasClass("reverse_icon")) upIcon.removeClass("reverse_icon").find('.pullUpLabel').html('上拉加载更多');
			return "";
		}
	});
	myScroll.on("slideDown", function(){
		if(this.y > 50){
			now_page = 0;
			hasMorePage = true;
			pageLoadTip(92);
			getList(false);
		}
	});
	myScroll.on("slideUp", function(){
		if (hasMorePage) {
			pageLoadTip(92);
			myScroll.refresh();
			myScroll.scrollTo(0,this.maxScrollY);
			getList(true);
		}
	});



	pageLoadTip(92);
	getList(false);
});

function getList(more){
	isLoading = true;
	var go_url = location_url;
	now_page += 1;
	go_url += "&page="+now_page;
	$.post(go_url,function(result){
		if (result.err_code) {
			return false;
		}
		if(result.count > 0){
			hasMorePage = now_page < result.total ? true : false;
			$('.listBox').addClass('storeListBox');
			laytpl($('#replyListBoxTpl').html()).render(result, function(html){
				if(more){
					if (hasMorePage) {
						$("#pullUp").removeClass('noMore loading').show();
					}
					$('.loadMoreList').remove();
					$('.details_evaluate ul').append(html);
				}else{
					$('.details_evaluate ul').html(html).removeClass('dealcard').show();
				}
				
				$(".details_evaluate p").each(function(index, element) {
					$(this).css("width", $(this).attr("tip") * 18);
				});  
				// 点击展开影藏
				$(".details_evaluate_end").each(function(){
					var hide = $(this).data('hide');
					if ($(this).height() > 48) {
						$(this).css({"height":"48px", "overflow":"hidden"});
						$(this).data('hide', 1);
					} else if (hide == 0) {
						$(this).siblings("a.more").hide();
					}
				});
				$(".details_evaluate .more").click(function(){
					$(this).hide();
					$(this).siblings(".details_evaluate_end").css("height", "auto");
				});
				
			});
			if(!hasMorePage){
				$("#pullUp").addClass('noMore').hide();
			}
		}else{
			$("#pullUp").addClass('noMore').hide();
			$('.listBox dl').hide();
			$('.listBox .no-deals').removeClass('hide');
		}
		pageLoadTipHide();
		setTimeout(function(){
			myScroll.refresh();
			if (!more) {
				myScroll.scrollTo(0,0);
			}
		},200);
		isLoading = false;
	});
}