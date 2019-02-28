var myScroll, myScroll2 = null, myScroll3 = null, now_page = 0, hasMorePage = true, isLoading = true;
$(function(){
	
	$(".screenxl li").click(function(){
        $(this).addClass("on").siblings().removeClass("on");
	});
	$("a.screenwc").click(function(){
        $(this).parents(".dropdown-module").height(0);
        $(".shade").height(0);
        $(".category").removeClass("active")
        $(".sort .nav-head-name").html($(".screenxl").first().find("li.on").text() + "/" + $(".screenxl").last().find("li.on").text());
        
        now_sort_id = $(".screenxl").first().find("li.on").data('sort-id');
        now_queue = $(".screenxl").last().find("li.on").data('queueid');
        close_dropdown();
    	now_page = 0;
//    	if(obj.attr('data-category-id')){
//    		obj.addClass('red');
//    		$('.dropdown-toggle.category .nav-head-name').html(obj.find('span').data('name'));
//    		now_cat_url = obj.attr('data-category-id');
//    	}else if(obj.attr('data-area-id')){
//    		$('.dropdown-toggle.biz .nav-head-name').html(obj.find('span').data('name'));
//    		now_area_url = obj.attr('data-area-id');
//    	}else if(obj.attr('data-sort-id')){
//    		alert(1)
//    		obj.addClass('active').siblings('li').removeClass('active');
//    		$('.dropdown-toggle.sort .nav-head-name').html(obj.find('span').data('name'));
//    		now_sort_id = obj.attr('data-sort-id');
//    	}
    	$('.listBox dl').empty().hide();
    	$('.listBox .no-deals').addClass('hide');
//
//    	$("#pullUp").removeClass('noMore loading').show();
//    	$('.listBox dl .noMore').remove();
    	pageLoadTip(0);
    	getList(false);
    	
	});
      
	$(".nav_top li").last().css("background", "none");
	$(".navBox_list dl").each(function(){
		$(this).find("dd.Menulink").last().css("border-bottom", "none");
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
	pageLoadTip(0);
	if (user_long == '0') {
		getUserLocation({okFunction:'pageGetList', okFunctionParam:[true], errorFunction:'pageGetList', errorFunctionParam:[false]});
	} else {
		pageGetList(user_long, user_lat);
	}
});
function pageGetList(type){
	if(type == true){
		now_sort_id = 'juli';
		$('.dropdown-toggle.sort span').html('离我最近');
		$('.sort-wrapper>ul li:first').data('sort-id','juli').find('span').html('离我最近');
	}
	getList(false);
}
function list_location(obj){
	close_dropdown();
	now_page = 0;
	if(obj.attr('data-category-id')){
		obj.addClass('red');
		$('.dropdown-toggle.category .nav-head-name').html(obj.find('span').data('name'));
		now_cat_url = obj.attr('data-category-id');
	}else if(obj.attr('data-area-id')){
		$('.dropdown-toggle.biz .nav-head-name').html(obj.find('span').data('name'));
		now_area_url = obj.attr('data-area-id');
	}else if(obj.attr('data-sort-id')){
		alert(1)
		obj.addClass('active').siblings('li').removeClass('active');
		$('.dropdown-toggle.sort .nav-head-name').html(obj.find('span').data('name'));
		now_sort_id = obj.attr('data-sort-id');
	}
	$('.listBox dl').empty().hide();
	$('.listBox .no-deals').addClass('hide');

	$("#pullUp").removeClass('noMore loading').show();
	$('.listBox dl .noMore').remove();
	pageLoadTip(0);
	getList(false);
}
function getList(more)
{
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
	$.post(go_url,function(result){
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