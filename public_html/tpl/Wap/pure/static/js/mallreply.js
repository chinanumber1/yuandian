var now_page = 0, hasMorePage = true, isLoading = true;
$(function(){
	$(window).scroll(function() {
		if ($(window).scrollTop() > $('.evaluate dl').offset().top) {
			if(isLoading == false && hasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height()){
				getList(true);
			}
		}
	});
	getList(0);
	$(".details_comment p").each(function(index, element) {
		var num = $(".details_comment_top span").text();
		var www = num*20;//
		$(this).css("width", www);
	});
});
function getList(more)
{
	isLoading = true;
	now_page += 1;
	$.post(shopReplyUrl, {page:now_page}, function(result){
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
	});
}