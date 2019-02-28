
var myScroll;
function loaded() {
	myScroll = new iScroll('order-list-wrapper', {
		checkDOMChanges: true,
		onScrollEnd: function() {
			y = this.y;
			Y = this.maxScrollY;
			if(y < Y + 200){
				load_more();
			}
		}
	});
}
document.addEventListener('touchmove', function(e) {
	e.preventDefault();
}, false);
document.addEventListener('DOMContentLoaded', loaded, false);
var t;
$(function(){
	$('#fliter-close').css('height',$("#fliter-layer").height()-150).click(function(event) {
		$("#fliter-layer").hide();
	});
	$("[name='keyword']").on('input',function(){
		$this = $(this);
		if(t){
			clearTimeout(t);
		}
		t = setTimeout(function(){
			load_search($this.val());
		},500);
	})
	$(".header-fliter-container").click(function(){
		$("#fliter-layer").toggle();
	})
	$("li.header-fliter-container").click(function(){
		var fliter = $(this).find('span').text(),
			fliter_status = $(this).find('span').attr('data-status');
		$("#fliter-active span").text(fliter).attr('data-status', fliter_status);;
		load_fliter(fliter_status);
	})
})

// 加载数据
var loading = false,
	order_list = Array();

load_order_list();
function load_order_list(){
	if(loading==true) return;

	var params = {
		'pindex': order_list.pindex
	}

	loading = true;
	$.post(url, params, function(data) {
		try {
			data = $.parseJSON(data);
			order_list = data;
			appendItemHtml(data.has_more, data.list,data.type);
		} catch (e) {
			alert("请求数据错误");
		}
		loading = false;
		return;
	});
}

//加载筛选数据
function load_fliter(str){
	if(loading==true) return;
	var params = {
		'status': str,
		'pindex': 1
	}
	loading = true;
	$.post(url, params, function(data) {
		try {
			data = $.parseJSON(data);
			order_list = data;
			$('#order-list-ul').html("");
			appendItemHtml(data.has_more, data.list,data.type);
		} catch (e) {
			alert("请求数据错误");
		}
		loading = false;
		return;
	});
}

// 加载搜索结果

function load_search(str){
	if(loading==true) return;
	var params = {
		'keyword': str,
		'pindex': 1
	}
	loading = true;
	$.post(url, params, function(data) {
		try {
			data = $.parseJSON(data);
			order_list = data;
			$('#order-list-ul').html("");
			appendItemHtml(data.has_more, data.list,data.type);
		} catch (e) {
			alert("请求数据错误");
		}
		loading = false;
		return;
	});
}
//加载更多数据
function load_more() {
	if(loading==true) return;
	var  Currentpindex=order_list.pindex
	order_list.pindex++;
	if(order_list.status || order_list.status == '0'){
		var params = {
			'status': order_list.status,
			'pindex': order_list.pindex
		};
	}else if(order_list.keyword || order_list.keyword != ''){
		var params = {
			'keyword': order_list.keyword,
			'pindex': order_list.pindex
		};
	}else{
		var params = {
			'pindex': order_list.pindex
		};
	}
	
	loading = true;
	$.post(url, params, function(data) {
		try {
			data = $.parseJSON(data);
			order_list = data;
			if(!data.has_more)order_list.pindex=Currentpindex;
			appendItemHtml(data.has_more, data.list,data.type);
		} catch (e) {
			alert("请求更多数据错误");
		}
		loading = false;
		return;
	});
}

//将结果加入页面
function appendItemHtml(has_more, item_list,type) {
	var detailurl="javascript:;";
	if(type=='group'){
       detailurl="/index.php?g=WapMerchant&c=Index&a=gdetail";
	}else if(type=='meal'){
	   detailurl="/index.php?g=WapMerchant&c=Index&a=mdetail";
	}
	for (var i = 0; i < item_list.length; i++) {
		var item = item_list[i],new_order = '';
		/*if(item.order_status == '待接单'){
			var order_status_content = " <span class='status' style='color:#fa8a71'>"+item.order_status+"</span><span style='display:inline-block;background:red;color:#fff;padding:1px 5px;margin-left:5px;border-radius:3px;font-size:12px;font-weight:100;transform:scale(0.8)'>NEW</span>";
			new_order = "style='background:#fffff5'";
		}else{
			
		}*/
		var order_status_content = " <span class='status' style=''>"+item.order_status+"</span>";
		var content = "<a class='order-list-container' href='"+detailurl+"&order_id="+item.order_id+"'>"+(type=='meal' ? "<p class='shop-name'>"+item.storename+"</p>":'')+
			"<div class='order-list-detail'>"+
				"<p class='name-phone'><span class='name'>"+item.nickname+"</span><span class='phone'>"+item.phone+"</span></p>"+
				"<p class='address'>"+item.address+"</p>"+
				"<p class='price'>合计：<span class='strong'>"+item.final_price+"</span>元<span class='count'>("+item.num+")</span></p>"+
				"<p class='date'>"+item.created+order_status_content+"</p>"+
			"</div>"+
			"<div class='order-list-pointer'>"+
				"<i class='iconfont icon-right'></i>"+
			"</div>"+
			"<div class='clearfix'></div>"+
		"</a>";
			
		$(content).appendTo('#order-list-ul');
	}
	$(".address").css('width', $(window).width()-50);
	if (has_more) {
		$('#load_more').show();
	}else{
		$('#load_more').hide();
	}
}