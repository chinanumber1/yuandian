var myScroll = null;
var isSearch = false;
var hasMore = true;
var nowPage = 1, status = urlParam.status, is_order = 0;
var contents = ['全部订单', '预订中', '就餐中', '已买单', '待确认菜品', '已取消'];
$(document).ready(function(){
	$(".mask").height($(window).height());
	$('.public .content').html(contents[urlParam.status]);
	$('.entry ul').empty();
	showList();
	$('body,html').animate({scrollTop : 0}, 300);
	$('.entry').css({'overflow-y':'auto','-webkit-overflow-scrolling':'touch',height:$(window).height() - 99});
	$('.entry ul').after('<div class="jroll-infinite-tip">正在加载中...</div>');
	common.scroll($('.entry'),function(scrollIndex){
		showList(scrollIndex);
	});
	$('#searchForm').submit(function(){
		$('.entry ul').empty();
		if($('#find_value').val() == ''){
			isSearch = false;
		}else{
			isSearch = true;
		}
		hasMore = true;
		nowPage = 1;
		showList();
		return false;
	});

	$(document).on('click', '.pro_list .more', function() {
		var order_id = $(this).data('order_id');
		common.http('Storestaff&a=foodshop_detail',{'order_id':order_id, noTip:true}, function(data){
			if (data.temp_list == null) {
				$('.tol_price').html('<div class="fl">共' + data.total_num + '份</div><div class="fr price">总计：<span>￥' + data.total_price + '</span></div>');
			} else {
				$('.tol_price').html('<div class="fl">共' + data.total_num + '份</div><div class="fl price">总计：<span>￥' + data.total_price + '</span></div><a class="fr go" href="foodshop_menu.html?order_id=' + order_id + '&isShow=1">去确认</a>');
			}
			
			laytpl($('#orderDetailTpl').html()).render(data, function(html){
				$('.cease .surface ul').html(html);
			});
			 new JRoll(".surface");
		});
		$(".cease,.mask").fadeIn();
		$(".surface").height($(".cease").height() - 125);
	});
	
	
	//取消订单  
	$(document).on('click', '.pitch .cancel', function() {
		$('#order_id').val($(this).data('order_id'))
		$(".book,.mask").fadeIn();
	});

	$(".book .ensure").click(function() {
		var order_id = $('#order_id').val();
		common.http('Storestaff&a=cancel_book', {'order_id':order_id, 'noTip':true}, function(data){
			$('#li_' + order_id).fadeOut();
		});
		$(".book,.mask").fadeOut();
	});
	
	$(".seek .del,.seek .close,.mask").click(function() {
		$(".book,.prints,.mask").fadeOut();
	});
	$(".cease .del,.mask").click(function() {
	    $(".cease,.mask").fadeOut();
	});

	//打印菜单
	$(document).on('click', '.click .edit', function() {
		var order_id = $(this).data('order_id');
		common.http('Storestaff&a=foodshop_order_before', {'order_id':order_id, 'noTip':true}, function(data){
			selectData = data.list;
			laytpl(order_html).render(data, function(html){
				$('.setup').remove();
				$('body').append(html);
				$(".Mask,.setup").show();
			});
		});
	});

	//打印菜单
	$(document).on('click', '.click .print', function() {
		var order_id = $(this).data('order_id');
		$('.prints .button .ensure').data('order_id', $(this).data('order_id'));
		$(".prints,.mask").fadeIn();
	});
	
	$('.prints .button .ensure').click(function() {
		common.http('Storestaff&a=foodshop_print_order',{'order_id':$(this).data('order_id'), noTip:true}, function(data){
			motify.log(data);
			$(".prints,.mask").fadeOut();
		});
	});
});



function showList(scrollIndex){
	if(hasMore == false){
		return false;
	}
	if(isSearch == false){
		common.http('Storestaff&a=foodshop_order',{'page':nowPage, 'status':urlParam.status, noTip:true}, function(data){
			if(nowPage >= data.totalPage){
				hasMore = false;
				$('.jroll-infinite-tip').addClass('hideText');
			}
			laytpl($('#listTpl').html()).render(data.order_list, function(html){
				$('.entry ul').append(html);
				$(".remark").each(function() {
					var h = $(this).find(".p30").height();
					if (h > 36) {
						$(this).find(".p30").addClass("has_on");
					}
				});
				common.scrollEnd(scrollIndex);
			});
			nowPage++;
		});
	}else{
		common.http('Storestaff&a=foodshop_order',{'page':nowPage, keyword:$('#find_value').val(), searchType:$('#searchType').val(),'status':urlParam.status,noTip:true}, function(data){
			if(nowPage >= data.totalPage){
				hasMore = false;
				$('.jroll-infinite-tip').addClass('hideText');
			}
			laytpl($('#listTpl').html()).render(data.order_list, function(html){
				$('.entry ul').append(html);
				$(".remark").each(function() {
					var h = $(this).find(".p30").height();
					if (h > 36) {
						$(this).find(".p30").addClass("has_on");
					}
				});
				common.scrollEnd(scrollIndex);
			});
			nowPage++;
		});
	}
}