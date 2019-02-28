var myScroll = null;
var order_id = 0;
var isSearch = false;
var hasMore = true;
var nowPage = 1;
$(document).ready(function(){
	var indexData = common.getCache('indexData',true);
	$('.public .content').html(indexData.have_store_name);
	
	$('#order_list').css({height:$(window).height()- 104});
	$('#order_list ul').after('<div class="jroll-infinite-tip">正在加载中...</div>');
	common.scroll($('#order_list'),function(scrollIndex){
		showList(scrollIndex);
	});
	
	showList();

	$('#searchForm').submit(function(){
		$('#order_list ul').empty();
		if($('#find_value').val() == ''){
			isSearch = false;
		}else{
			isSearch = true;
		}
		hasMore = true;
		order_id = 0;
		nowPage = 1;
		showList();
		
		return false;
	});
});



function showList(scrollIndex){
	if(hasMore == false){
		return false;
	}
	if(isSearch == false){
		common.http('Storestaff&a=store_order',{'order_id':order_id,noTip:true}, function(data){
			if(nowPage == 1){
				$('#order_list').css({height:$(window).height()- 104});
				common.scrollEnd(scrollIndex);
			}
			if(data.order_list.length > 0){
				order_id = data.order_list[data.order_list.length-1].order_id;
			}
			if(nowPage >= data.pagenum){
				hasMore = false;
				$('.jroll-infinite-tip').addClass('hideText');
			}
			laytpl($('#listTpl').html()).render(data.order_list, function(html){
				$('#order_list ul').append(html);
				common.scrollEnd(scrollIndex);
			});
			nowPage++;
		});
	}else{
		common.http('Storestaff&a=store_order',{'order_id':order_id,keyword:$('#keyword').val(),condition:$('#condition').val(),noTip:true}, function(data){
			if(data.order_list.length > 0){
				order_id = data.order_list[data.order_list.length-1].order_id;
			}
			if(nowPage >= data.pagenum){
				hasMore = false;
				$('.jroll-infinite-tip').addClass('hideText');
			}
			laytpl($('#listTpl').html()).render(data.order_list, function(html){
				$('#order_list ul').append(html);
				common.scrollEnd(scrollIndex);
			});
			nowPage++;
		});
	}
}