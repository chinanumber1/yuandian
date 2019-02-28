var myScroll = null, now_page = 0, hasMorePage = true, isLoading = true;
$(function(){
//	$(".Dgrab").css({'height':$(window).height()});
//	$(".details_comment p").css("width", $(".details_comment_top span").text() * 20);
	$('#container').css({'height':$(window).height() + 'px'});
//	$('#scroller').css({'min-height':$(window).height()+'px'});
//	myScroll = new IScroll('#container',{ click: true });
	myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransition:false});
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
			pageLoadTip(0);
			getList(false);
		}
	});
	myScroll.on("slideUp", function(){
		if (hasMorePage) {
			pageLoadTip(0);
			myScroll.refresh();
			myScroll.scrollTo(0,this.maxScrollY);
			getList(true);
		}
	});
	pageLoadTip(0);
	getList(false);
	
	$(document).on('click', '.del', function(e){
		e.stopPropagation();
		var supply_id = $(this).attr("data-id");
		layer.open({
		    content: '删除后就不再显示了，但是不影响您的接单统计!',
		    btn: ['确认', '取消'],
		    shadeClose: false,
		    yes: function(){
		    	layer.closeAll();
					$.post(del_url, {supply_id:supply_id}, function(json){
						if (json.status) {
							$('.supply_' + supply_id).hide();
						} else {
							layer.open({title:['提示：','background-color:#FF658E;color:#fff;'],content:json.info,btn: ['确定'],end:function(){}});
						}
					}, 'json');
		    }, no: function(){
		        layer.open({content: '你选择了取消', time: 1});
		    }
		});
		
	});
	var is_flag = false;
	$(document).on('click', '.go_detail', function(e){
		e.stopPropagation();
		if (is_flag) {
			return false;
		}
		is_flag = true;
		var supply_id = $(this).attr("data-id");
		location.href = DetailUrl.replace(/d%/, supply_id);
	});
});

function getList(more){
//	pageLoadTipHide();
//	return false;
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
			laytpl($('#replyListBoxTpl').html()).render(result, function(html){
				if(more){
					if (hasMorePage) {
						$("#pullUp").removeClass('noMore loading').show();
					}
					$('.loadMoreList').remove();
					$('#finish_list').append(html);
				}else{
					$('#finish_list').html(html).show();
				}
				$(".delivery p em").each(function(){
			       $(this).width($(window).width()-$(this).siblings("i").width()-55) 
			    });
			});
			if(!hasMorePage){
				$("#pullUp").addClass('noMore').hide();
			}
			$('.psnone').hide();
		} else {
			$('.psnone').show();
			$("#pullUp").addClass('noMore').hide();
			$('#finish_list').hide();
		}
		pageLoadTipHide();
		setTimeout(function(){
			myScroll.refresh();
			if (!more) {
				myScroll.scrollTo(0,0);
			}
		},200);
		isLoading = false;
	}, 'json');
}