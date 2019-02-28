var myScroll = null;
var order_id = 0;
var isSearch = false;
var hasMore = true;
var nowPage = 1;
$(document).ready(function(){
	var indexData = common.getCache('indexData',true);
	
	
	$('#order_list').css({height:$(window).height()- 104});
	$('#order_list ul').after('<div class="jroll-infinite-tip">正在加载中...</div>');
	common.scroll($('#order_list'),function(scrollIndex){
		showList(scrollIndex);
	});
	
	showList();

	$('#searchForm').submit(function(){
		$('#order_list ul').empty();
		if($('#keyword').val() == ''){
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

	$(document).on('click', 'div.consum', function(){
		var pass = $(this).data('pass'), id = $(this).data('id');
		console.log(pass)
		common.http('Storestaff&a=sub_card_verify', {'pass':pass, 'noTip':false}, function(data){
			motify.log('验证消费成功！');
			setTimeout(location.reload(), 5000);
		});
		
	});
});



function showList(scrollIndex){
	if(hasMore == false){
		return false;
	}
	if(isSearch == false){
		common.http('Storestaff&a=sub_card',{'id':order_id,noTip:true}, function(data){

			if(data.order_list.length > 0){
				order_id = data.order_list[data.order_list.length-1].ids;
				if(nowPage >= data.pagenum){
					hasMore = false;
					$('.jroll-infinite-tip').addClass('hideText');
				}
				laytpl($('#listTpl').html()).render(data.order_list, function(html){
					$('#order_list ul').append(html);
					common.scrollEnd(scrollIndex);
				});
				nowPage++;
			}else{
					$('.jroll-infinite-tip').html('暂无数据');
			}
			
		});
	}else{
		common.http('Storestaff&a=sub_card',{'id':order_id,keyword:$('#keyword').val(),find_type:$('#find_type').val(),noTip:true}, function(data){
			if(data.order_list.length > 0){
				order_id = data.order_list[data.order_list.length-1].id;
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