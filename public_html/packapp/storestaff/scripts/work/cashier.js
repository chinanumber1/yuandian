var order_id = 0;
var isSearch = false;
var hasMore = true;
var nowPage = 1;
// var 
$(document).ready(function(){
	var indexData = common.getCache('indexData',true);
	$('.public .content').html(indexData.have_cash_name);
	
	if(indexData.open_score_fenrun==1){
		$('.census').show();
		$('.found').hide();
	}else{
		$('.found').show();
		$('.census').hide();
	}
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
	
	 $(".sus_popup").click(function(e){
        event.stopPropagation();
        $(".sus_url").is(":hidden") ? $(".sus_url").show() : $(".sus_url").hide();
    });
	$('.search').click(function(){
		common.scan('scanCardResult');
	});
});

function scanCardResult(str){
	var code = str
	common.http('Storestaff&a=scan_payid_check',{'payid':str}, function(data){
		
		if(data.uid>0){
		
			window.location.href="cashier_set.html?uid="+data.uid+"&from_scan=1&payid="+data.payid;
		}
	});
}

function showList(scrollIndex){
	if(hasMore == false){
		return false;
	}
	if(isSearch == false){
		common.http('Storestaff&a=store_arrival',{'order_id':order_id,noTip:true}, function(data){
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
		common.http('Storestaff&a=store_arrival',{'order_id':order_id,keyword:$('#keyword').val(),condition:$('#condition').val(),noTip:true}, function(data){
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