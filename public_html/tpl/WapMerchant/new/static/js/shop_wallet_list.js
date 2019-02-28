
var myScroll_left;
function loaded() {
	myScroll = new iScroll('draw-list-wrapper', {
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

// 加载数据
var loading = false,wallet_list = Array();

load_wallet_list();
function load_wallet_list(){
	if(loading==true) return;

	var params = {
		'action': 'list',
		'pindex': wallet_list.pindex
	}

	loading = true;
	$.post(url, params, function(data) {
		try {
			data = $.parseJSON(data);
			wallet_list = data;
			appendItemHtml(data.has_more, data.list);
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

	wallet_list.pindex++;
	
	var params = {
		'action': 'list',
		'pindex': wallet_list.pindex
	};

	loading = true;
	$.post(url, params, function(data) {
		try {
			data = $.parseJSON(data);
			wallet_list = data;
			appendItemHtml(data.has_more, data.list);
		} catch (e) {
			alert("请求更多数据错误");
		}
		loading = false;
		return;
	});
}

//将结果加入页面
function appendItemHtml(has_more, item_list) {
	if(item_list.length == 0 && wallet_list.pindex == 1){
		var content = "<li class='draw-list-container'><p class='draw-detail' style='text-align:center'>暂无数据</p></li> ";
		$(content).appendTo('#draw-list-ul');
	}
	for (var i = 0; i < item_list.length; i++) {
		var item = item_list[i];
		var content = "<li class='draw-list-container'>"+
					"<div class='draw-list-detail'>"+
						"<p class='detail-money'>"+
							"<span class='draw-detail'>"+item.description+"</span>"+
							"<span class='draw-money'><strong>"+item.amount+"</strong>元</span>"+
							"<div class='clearfix'></div>"+
						"</p>"+
						"<p class='id-date-type'>"+
							"<span class='draw-id'>流水号:<strong>"+item.id+"</strong></span>"+
							"<span class='draw-date'>"+item.created+"</span>"+
							"<span class='draw-type'>"+item.wallet_type+"</span>"+
							"<div class='clearfix'></div>"+
						"</p>"+
					"</div>"+
				"</li> ";
			
		$(content).appendTo('#draw-list-ul');
	}
		
	if (has_more) {
		$('#load_more').show();
	}else{
		$('#load_more').hide();
	}
}