var myScroll = null, myScroll2 = null, myScroll3 = null, now_page = 1, goods_page = 0, hasMorePage = true, isLoading = true, hasGMorePage = true, isGLoading = true;
var sort_type = 1, sort = 1;
$(function(){
	//高度
	$(".mask").height($(window).height());
	//超出影藏
	$(".p53").each(function(){
		var w = $(this).find("h2").width();
		var w2 = $(this).find("div.fl").width();
		$(this).find(".title").css("max-width",w-w2-70);
		$(this).find("p").css("max-width",w-75);
	});
	// 图片等比例
	$(".bd_a img").each(function(){
		$(this).height($(this).width()*0.66)
	})
	$(".bd_a a:nth-child(2n)").css("margin-right",0); 
	//菜单切换
	$(".switch li.icons").click(function (e) {
		if ($(this).hasClass('slider')) {
			return;
		}
		var w = $(this).width();
		var whatTab = $(this).index();
		var howFar = w * whatTab;
		$(".slider").css({
			left: howFar + "px"
		});
		$(this).addClass("on").siblings().removeClass("on"); 
		var index = $(this).index();
		$(".hand").eq(index).show(500).siblings(".hand").hide(500);
		if ($(this).data('type') == 'reply' && $('#isLoadReply').val() == 0) {
			$.post(shopReplyUrl,{showCount:1},function(result){
				result = $.parseJSON(result);
				$('.hand_discuss li').eq(0).find('p').text(result.all_count);
				$('.hand_discuss li').eq(1).find('p').text(result.good_count);
				$('.hand_discuss li').eq(2).find('p').text(result.wrong_count);
				if(result){
					laytpl($('#shopReplyTpl').html()).render(result.list, function(html){
						$('.evaluate dl').html(html);
					});
				}
				$(".title p").each(function(index, element) {
					$(this).css("width", $(this).attr("tip") * 16);
				});
				isLoading = false;
			});
			$('#isLoadReply').val(1);
		}
	});
	
	// 分类箭头切换
	$(".hand_list li.sort").click(function(){
		if ($(".halist_n").is(":hidden")) {
			$(this).find(".halist_n").fadeIn();
			$(".mask").show();
		} else {
			$(this).addClass("on");
			$(this).find(".halist_n").fadeOut();
			$(".mask").hide();
		}
	});
	$(".halist_n dd").click(function(){
		$(".sort span").text($(this).find(".agio_top").text());
		if ($(".hand_list .sort span").data('sort_id') != $(this).data('sort_id')) {
			$(".hand_list .sort span").data('sort_id', $(this).data('sort_id'));
			goods_page = 0;
			hasGMorePage = true;
			isGLoading = true;
			goods_data(false);
		}
	});
	
	$(".mask").click(function(){
		$(this).hide();
		$(".halist_n").hide();
	});
	
	
	// 销量箭头切换
	$(".hand_list li.sorts").click(function(){
		sort = $(this).data('sort');
		if (sort != 2) {
			if ($(this).hasClass("on")) {
				sort_type = 2;//ASC
				$(this).removeClass("on").addClass("ou");
			} else {
				sort_type = 1;//DESC
				$(this).addClass("on").removeClass("ou");
			}
		} else {
			if ($(this).hasClass("ou")) {
				sort_type = 1;//ASC
				$(this).removeClass("ou").addClass("on");
			} else {
				sort_type = 2;//DESC
				$(this).addClass("ou").removeClass("on");
			}
		}
		$(this).siblings().removeClass("on").removeClass("ou");

		goods_page = 0;
		hasGMorePage = true;
		isGLoading = true;
		goods_data(false);
	});
	
	//评论星星
//	$(".title p").each(function(index, element) {
//		$(this).css("width", $(this).attr("tip") * 16);
//	}); 
	
	$(".hand_discuss li").click(function(){
		if($(this).hasClass('on')){
			return false;
		}
		$(this).addClass("on").siblings().removeClass("on");
		
		$('.evaluate dl').empty();
		$.post(shopReplyUrl,{tab:$(this).data('tab')},function(result){
			result = $.parseJSON(result);
			if(result){
				laytpl($('#shopReplyTpl').html()).render(result.list, function(html){
					$('.evaluate dl').html(html);
				});
				$(".title p").each(function(index, element) {
					$(this).css("width", $(this).attr("tip") * 16);
				}); 
			}
			isLoading = false;
		});
	});
	
	
	// 跳转
	$(".purl").click(function(){
		$(".switch li:nth-child(3)").addClass("on").siblings().removeClass("on");
		$(".slider").css("left",2*$(".switch li").width());
		$(".hand").eq(2).show(500).siblings(".hand").hide(500);
	});
	
	$(window).scroll(function() {
		if ($(window).scrollTop() > $('.evaluate dl').offset().top) {
			if(isLoading == false && hasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height()){
				getList(true);
			}
		}
	});
	
	$(window).scroll(function() {
		if ($(window).scrollTop() > $('.bd_a').offset().top) {
			if(isGLoading == false && hasGMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height()){
				goods_data(true);
			}
		}
	});
	goods_data(false);
});

function goods_data(more)
{
//	pageLoadTip(250);
	isGLoading = true;
	goods_page += 1;
	if (goods_page == 1) {
		var index = layer.open({type: 2});
		$('.bd_a').html('');
	}
	var sort_id = $(".hand_list .sort span").data('sort_id');
	$.post(ajax_url, {'sort_id':sort_id, 'sort':sort, 'sort_type':sort_type,page:goods_page}, function(result){
		if(result.count > 0){
			hasGMorePage = goods_page < result.total ? true : false;
			laytpl($('#goodsListBoxTpl').html()).render(result, function(html){
				if (more) {
					$('.bd_a').append(html);
				} else {
					$('.bd_a').html(html);
				}
			});
			// 图片等比例
			$(".bd_a img").each(function(){
				$(this).height($(this).width());
			});
			$(".bd_a a:nth-child(2n)").css("margin-right", 0); 
		} else {
			$('.bd_a').html('');
		}
		isGLoading = false;
		layer.close(index);
	}, 'json');
}
function getList(more)
{
	pageLoadTip(250);
	isLoading = true;
	now_page += 1;
	$.post(shopReplyUrl, {tab:$('.hand_discuss li.on').data('tab'),page:now_page}, function(result){
		result = $.parseJSON(result);
		if(result.count > 0){
			hasMorePage = now_page < result.total ? true : false;
			laytpl($('#shopReplyTpl').html()).render(result.list, function(html){
				if (more) {
					$('.evaluate dl').append(html);
				} else {
					$('.evaluate dl').html(html);
				}
			});
			$(".title p").each(function(index, element) {
				$(this).css("width", $(this).attr("tip") * 16);
			}); 
		} else {
			$('.evaluate dl').html('');
		}
		isLoading = false;
		pageLoadTipHide();
	});
}