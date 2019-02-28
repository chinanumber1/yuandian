var now_page = 0, hasMorePage = true, isLoading = true, search_type = 0;
$(function(){
	var store_id = parseInt($('#store_id').val());
	if (store_id > 0) {
		$(".se_input").width($(window).width() - 110);
	} else {
		$(".se_input").width($(window).width() - 140);
	}
	
	// 图片等比例
	$(".bd_a img").each(function(){
		$(this).height($(this).width()*0.66);
	});
	$(".bd_a a:nth-child(2n)").css("margin-right",0); 

	$(".cond span.on").click(function(){
		if($(".cond_list").is(":hidden")){
			$(".cond_list").show();
		}else{
			$(".cond_list").hide();
		}
	});
	$(".cond_list span").click(function(){
		if ($(this).attr('class') == 'sp') {
			search_type = 0;
		} else {
			search_type = 1;
		}
		$(".cond span.on").text($(this).text());
		$(".cond_list").hide();
		
		if($('.se_input').val().length > 0){
			hasMorePage = true;
			now_page = 0;
			getList(0);
		}
	});
	$(window).scroll(function() {
		if ($(window).scrollTop() > $('.search_list').offset().top) {
			if(isLoading == false && hasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height()){
				getList(true);
			}
		}
	});
	$('#search').click(function(){
		hasMorePage = true;
		now_page = 0;
		getList(0);
	});
	$('.se_input').keyup(function(event){
		console.log(event.keyCode);
		if(event.keyCode == 13){
			hasMorePage = true;
			now_page = 0;
			getList(0);
		}
	});
	if($('.se_input').val().length > 0){
		hasMorePage = true;
		now_page = 0;
		getList(0);
	}
});

function getList(more)
{
	$('.se_input').val($.trim($('.se_input').val()));
	if($('.se_input').val().length == 0){
		alert('请输入搜索关键词');
		return false;
	}
	pageLoadTip(0);
	isLoading = true;
	now_page += 1;
	$.post(ajax_url, {'page':now_page, 'key':$('.se_input').val(), 'store_id':$('#store_id').val(), 'search_type':search_type}, function(result){
		if (search_type == 0) {
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
				$('.navBox_list').html('');
			} else {
				$('.navBox_list').html('');
				$('.bd_a').html('');
				$('.psnone').show();
			}
		} else {
			if (result.total > 0) {
				$('.psnone').hide();
				hasMorePage = now_page < result.total_page ? true : false;
				laytpl($('#storeListBoxTpl').html()).render(result, function(html){
					if (more) {
						$('.navBox_list').append(html);
					} else {
						$('.navBox_list').html(html);
					}
					$('.bd_a').html('');
				});
			} else {
				$('.bd_a').html('');
				$('.navBox_list').html('');
				$('.psnone').show();
			}
		}
		pageLoadTipHide();
		isLoading = false;
	}, 'json');
}