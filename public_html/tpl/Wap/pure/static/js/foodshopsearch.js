var myScroll, myScroll2 = null, myScroll3 = null, now_page = 0, hasMorePage = true, isLoading = true;
$(function(){
	$(".nav_top li").last().css("background", "none");
	$(".navBox_list dl").each(function(){
		$(this).find("dd.Menulink").last().css("border-bottom", "none");
	});
	$('#pageShopSearchTxt').width($(window).width()-124-32);
	$("#pageShopSearchTxt").bind('input', function(e){
		var address = $.trim($(this).val());
		if(address.length > 0){
			$('#pageShopSearchDel').show();
			$('#pageShopSearchBtn').addClass('so');
		}else{
			$('#pageShopSearchDel').hide();
			$('#pageShopSearchBtn').removeClass('so');
		}
	});

	$('#pageShopSearchDel').click(function(){
		$('#pageShopSearchTxt').val('').trigger('input');
	});
	//分类定位 
	$(window).scroll(function() {
		if ($(window).scrollTop() > $(".navBox").offset().top) {
			$(".pageSliderHide").addClass("nav_topfied");
			$(".he45").css("display", "block");
			if(isLoading == false && hasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height() - 50){
				getList(true);
			}
		} else {
			$(".pageSliderHide").removeClass("nav_topfied");
			$(".he45").css("display", "none");
		}
	});

	$(document).on('click', 'dt', function(){
		location.href = $(this).data('url');
	});
	$(document).on('click', '#pageShopSearchBackBtn', function(){
		location.href = back_url;
	});

	$(document).on('click', '#pageShopSearchBtn', function(){
		getList(false);
	});

});
function getList(more)
{
	pageLoadTip(0);
	isLoading = true;
	var go_url = location_url;
	if(now_cat_url != '-1'){
		go_url += "&cat_url="+now_cat_url;
	}
	if(now_area_url != '-1'){
		go_url += "&area_url="+now_area_url;
	}
	if(now_sort_id != 'defaults'){
		go_url += "&sort="+now_sort_id;
	}
	if(now_queue != -1){
		go_url += "&queue="+now_queue;
	}
	now_page += 1;
	go_url += "&page="+now_page;
	$.post(go_url, {'keyword':$('#pageShopSearchTxt').val()}, function(result){
		if(result.store_count > 0){
			hasMorePage = now_page < result.totalPage ? true : false;
			laytpl($('#storeListBoxTpl').html()).render(result, function(html){
				if (more) {
					$('.navBox_list').append(html);
				} else {
					$('.navBox_list').html(html);
				}
			});
			
			$(".show_number li p").each(function(index, element) {
		        $(this).css("width", $(this).attr("tip") * 2 * 8);
		    });
		} else {
			$('.navBox_list').html('');
		}
		pageLoadTipHide();
		isLoading = false;
	});
}