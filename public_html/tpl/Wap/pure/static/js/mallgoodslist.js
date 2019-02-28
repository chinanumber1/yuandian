var myScroll = null, myScroll1 = null, myScroll2 = null, myScroll3 = null, now_page = 0, hasMorePage = true, isLoading = true;
var pids1 = '', pids2 = '', pids3 = '', pids4 = '';
var sort_type = 1, sort = 1;
$(function(){
	names = names.split(',');
	if ($('.drop_top0').size()) myScroll = new IScroll('.drop_top0', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
	if ($('.drop_top1').size()) myScroll1 = new IScroll('.drop_top1', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
	if ($('.drop_top2').size()) myScroll2 = new IScroll('.drop_top2', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
	if ($('.drop_top3').size()) myScroll3 = new IScroll('.drop_top3', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
	
	//背景单窗高度  
	$(".se_input").width($(window).width() - 100);
	$(".maskt").height($(window).height() - 141);
	$(".drop_top").each(function(){
		$(this).css("max-height", $(window).height() - 240);
	});

	$(".bd_a a:nth-child(2n)").css("margin-right", 0); 

	// 分类箭头切换
	$(".dity_top li").click(function(){
		sort = $(this).data('sort');
		var is_load = true;
		if (sort == 1 && ($(this).hasClass("on") || $(this).hasClass("ou"))) {
			is_load = false;
		}
		if (sort == 3) {
			if ($(this).hasClass("on")) {
				sort_type = 1;
				$(this).removeClass("on").addClass("ou");  
			} else {
				sort_type = 2;
				$(this).addClass("on").removeClass("ou");
			}
		} else {
			if ($(this).hasClass("ou")) {
				sort_type = 2;
				$(this).removeClass("ou").addClass("on");  
			} else {
				sort_type = 1;
				$(this).addClass("ou").removeClass("on");
			}
		}
		$(this).siblings().removeClass("on").removeClass("ou");
		if (is_load) {
			now_page = 0;
			getList(0);
		}
		
		$(".drop").height(0);
		$(".dity_end li").removeClass("on");
		$(".maskt").hide();
	});

	//下拉
	$(".dity_end li").click(function(){
		if (myScroll != null) myScroll.refresh();
		if (myScroll1 != null) myScroll1.refresh();
		if (myScroll2 != null) myScroll2.refresh();
		if (myScroll3 != null) myScroll3.refresh();
		var index = $(this).index();
		var he = $(".drop").eq(index).find(".drop_top").height();
		if($(this).hasClass("on")){
			$(".maskt").hide();
			$(this).removeClass("on")
			$(".drop").eq(index).animate({height:"0px"}, 300)
		}else{
			$(".maskt").show();
			$(this).addClass("on").siblings().removeClass("on");
			$(".drop").eq(index).animate({height:he + 45}, 300).siblings(".drop").animate({height:"0px"}, 300)
		}
	});

	 // 选择下拉按钮
	$(".drop dd").click(function(){
		if ($(this).hasClass("on")) {
			$(this).removeClass("on");
		} else {
			$(this).addClass("on");
		} 
	});
	
	$(".confirm .fl").click(function(){
		$(this).parents('.drop').find('dd').removeClass("on");
	});
	
	$(".confirm .fr").click(function(){
		var index = $(this).parents(".drop").index();
		if ($(this).parents(".drop").find("dd").hasClass("on")) {
			var text = $(this).parents(".drop").find("dd.on").text();
			$(this).parents(".drop").animate({height:"0px"},300);
			$(".dity_end li").eq(index-1).removeClass("on").find("span").text(text).css({"color":"#f23030"}).addClass("on");
			$(".maskt").hide();
		} else {
			$(".dity_end li").eq(index-1).removeClass("on").find("span").text(names[index-1]).css({"color":"#232326"}).removeClass("on"); 
			$(this).parents(".drop").height(0);
			$(".maskt").hide();
		}
		var t_pids = [];
		$(this).parents(".drop").find("dd.on").each(function(){
			t_pids.push($(this).data('pid'));
		});
		if (index == 1) {
			pids1 = t_pids.length > 0 ? t_pids.join() : '';
		} else if (index == 2) {
			pids2 = t_pids.length > 0 ? t_pids.join() : '';
		} else if (index == 3) {
			pids3 = t_pids.length > 0 ? t_pids.join() : '';
		} else if (index == 4) {
			pids4 = t_pids.length > 0 ? t_pids.join() : '';
		}
		now_page = 0;
		getList(0);
	});
	
	$(".maskt").click(function(){
		$(".dity_end li").removeClass("on");
		$(this).hide();
		$(".drop").height(0);
	});
 
	//分类定位 
	$(window).scroll(function() {
		if ($(window).scrollTop() > $(".bd_a").offset().top) {
			if(isLoading == false && hasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height() - 50){
				getList(true);
			}
		}
	});
	$('#search').click(function(){
		now_page = 0;
		getList(0);
	});
	getList(0);
});

function getList(more)
{
	pageLoadTip(92);
	isLoading = true;
	now_page += 1;
	$.post(ajax_url, {'pids1':pids1, 'pids2':pids2, 'pids3':pids3, 'pids4':pids4, 'page':now_page, 'cat_fid':cat_fid, 'cat_id':cat_id, 'sort':sort, 'sort_type':sort_type, 'key':$('.se_input').val()}, function(result){
		if(result.total > 0){
			$('.psnone').hide();
			hasMorePage = now_page < result.total_page ? true : false;
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
			$('.psnone').show();
		}
		isLoading = false;
		pageLoadTipHide();
	}, 'json');
}