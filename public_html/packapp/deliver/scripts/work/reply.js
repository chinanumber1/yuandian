var nowPage = 1,hasMore = true, status = 0;
$(document).ready(function(){
    
    $('.classRating li').click(function(e){
        $(this).addClass('active').siblings('li').removeClass('active');
        status = $(this).data('status');
		hasMore = true;
        nowPage = 1;
        get_list();
    });


	if(common.checkApp()){
		$('#pick_list').css({height:$(window).height()-44 - 225});
	}else{
		$('#pick_list').css({height:$(window).height() - 225});
	}
	
	common.scroll($('#pick_list'),function(scrollIndex){
		get_list(scrollIndex);
	});
	common.http('Deliver&a=reply',{}, function(data){
	    $('.ziRight').html(data.score);
	    $('#total').html(data.total);
	    $('.rightUser b').width(data.scoreWidth).css('overflow','hidden');
	    $('#all').html('(' + data.total + ')');
	    $('#good').html('(' + data.good + ')');
	    $('#middle').html('(' + data.middle + ')');
	    $('#bad').html('(' + data.bad + ')');
	    get_list();
	});
});

function get_list(scrollIndex){
	if(hasMore == false){
		return false;
	}
	common.http('Deliver&a=replyList',{page:nowPage, 'status':status}, function(data){
		if(data.list.length > 0){
			laytpl($('#replyListBoxTpl').html()).render(data.list, function(html){
				if(nowPage == 1){
					$('#pick_list').html(html);
				}else{
					$('#pick_list').append(html);
				}
				$(".delivery p em").each(function(){
					$(this).width($(window).width() - $(this).siblings("i").width() -55) 
				});
				common.scrollEnd(scrollIndex);
			});
			if(nowPage >= data.total_page){
				hasMore = false;
			}
			nowPage++;
		}else{
			$('#pick_list').html('<ul  class="zanwu"><li><span></span></li><li>暂无用户对您进行评论哦！</li></ul>');
		}
	},function(data){
		$('#pick_list').html('<ul  class="zanwu"><li><span></span></li><li>暂无用户对您进行评论哦！</li></ul>');
	});
}