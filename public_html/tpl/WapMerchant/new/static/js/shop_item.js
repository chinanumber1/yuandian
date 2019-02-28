var myScroll;
function loaded() {
	myScroll = new iScroll('item-list-wrapper', {
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
	$("#input-wrap").click(function(){
		var e = jQuery.Event("select");
		$('.pigcms-search').trigger(e);
	})
	$("[name='keyword']").on('input',function(){
		$this = $(this);
		if(t){
			clearTimeout(t);
		}
		t = setTimeout(function(){
			load_search($this.val());
		},500);
	})
	$(".header-fliter").click(function(){
		$(this).addClass('fliter-active').siblings().removeClass('fliter-active');
		$("#fliter-layer").css('top', $("#item-list-div").offset().top-5).show();
	})

	var online_click = true,cat_click = true,sort_click = true;
	$("#online").click(function(event) {
		sort_click = true;
		$("#sort").find('.icon-fold').addClass('icon-unfold').removeClass('icon-fold');
		
		cat_click = true;
		$("#cat").find('.icon-fold').addClass('icon-unfold').removeClass('icon-fold');
		
		if(online_click){
			online_click = false;
			$(this).find('.icon-unfold').addClass('icon-fold').removeClass('icon-unfold');
			$("#online-fliter-wrapper").show().siblings().hide();
		}else{
			online_click = true;
			$(this).find('.icon-fold').addClass('icon-unfold').removeClass('icon-fold');
			$("#fliter-layer").hide();
		}
	});
	$("#cat").click(function(event) {
		online_click = true;
		$("#online").find('.icon-fold').addClass('icon-unfold').removeClass('icon-fold');
			
		sort_click = true;
		$("#sort").find('.icon-fold').addClass('icon-unfold').removeClass('icon-fold');
		if(cat_click){
			cat_click = false;
			$(this).find('.icon-unfold').addClass('icon-fold').removeClass('icon-unfold');
			$("#cat-fliter-wrapper").show().siblings().hide();
		}else{
			cat_click = true;
			$(this).find('.icon-fold').addClass('icon-unfold').removeClass('icon-fold');
			$("#fliter-layer").hide();
		}
	});
	$("#sort").click(function(event) {
		online_click = true;
		$("#online").find('.icon-fold').addClass('icon-unfold').removeClass('icon-fold');
		
		cat_click = true;
		$("#cat").find('.icon-fold').addClass('icon-unfold').removeClass('icon-fold');
		
		if(sort_click){
			sort_click = false;
			$(this).find('.icon-unfold').addClass('icon-fold').removeClass('icon-unfold');
			$("#sort-fliter-wrapper").show().siblings().hide();
		}else{
			sort_click = true;
			$(this).find('.icon-fold').addClass('icon-unfold').removeClass('icon-fold');
			$("#fliter-layer").hide();
		}
	});
	$("li.fliter-container").click(function(){
		$(this).addClass('fliter-selected').siblings().removeClass('fliter-selected');
		$("#fliter-layer").hide();
	})

})

/*function item_delete(obj){
	if(confirm("确定删除吗？")){
		$this = $(obj);
		var params = {
			'action'	: 'delete_item',
			'item_id'	: $this.attr('data-itemid')
		};
		$.post(edit_url, params, function(data) {
			$this.parents('.item-list-container').remove();
		});
	}
}
function item_online(obj){
	$this = $(obj);
	var params = {
		'action'	: 'online_item',
		'item_id'	: $this.attr('data-itemid')
	};
	$.post(edit_url, params, function(data) {
		var content = "<i class='iconfont icon-fangxiang4' style='color:#fc9a79'></i><span>下架</span>";
		$this.attr('onclick', 'item_offline(this)').html('');
		$this.append(content);
	});
}*/
function item_delete(obj){
	if(confirm("确定删除吗？")){
		$this = $(obj);
		var params = {
			'item_id'	: $this.attr('data-itemid'),
			'storeid'	: $this.attr('data-storeid')
		};
		$.post('/index.php?g=WapMerchant&c=Index&a=mdel', params, function(data) {
			if(!data.error){
			  $this.parents('.item-list-container').remove();
			}else{
			  alert('删除失败！');
			}
		},'JSON');
	}
}
function OnOffline(obj,st){
	$this = $(obj);
	st=parseInt(st);
	var params = {
		'st' : st,
		'item_id'	: $this.attr('data-itemid'),
		'storeid'	: $this.attr('data-storeid')
	};
	var content=sellstr='';
	if(st>0){
	   st=0;
	   content = "<i class='iconfont icon-fangxiang4' style='color:#fc9a79'></i><span>下架</span>";
	   sellstr='在售';
	}else{
	   st=1;
	   content = "<i class='iconfont icon-iconfontdown2' style='color:#fc9a79'></i><span>上架</span>";
	   sellstr='停售';
	}
	$.post('/index.php?g=WapMerchant&c=Index&a=mstatusopt', params, function(data) {
		if(!data.error){
		$this.attr('onclick', 'OnOffline(this,'+st+')').html('');
		$this.parent().siblings('.item-price-sell').find('.statusstr').find('strong').text(sellstr);
		$this.append(content);
		}else{
		  alert('状态修改失败！');
		}
	},'JSON');
}
var list_is_online,list_cat,list_sort;
/*// 筛选上下架商品
function online_fliter(obj){
 	var txt = $(obj).find('.fliter-selected span').text();
 	$("[name='keyword']").val("");
 	$("#online span").text(txt);
 	if(loading==true) return;
 	var online_status = $(obj).find('.fliter-selected span').attr('data-isonline');
 	if(online_status != item_list.is_online){
 		item_list.pindex = 1;
 	}
	var params = {
		'is_online'	: online_status,
		'sort'		: item_list.sort,
		'top_cat_id': item_list.top_cat_id,
		'pindex'	: item_list.pindex
	};

	$('#item-list-div').html("");
	item_ajax(params);
}
// 筛选商品分类
function cat_fliter(obj){
 	var txt = $(obj).find('.fliter-selected span').text();
 	$("[name='keyword']").val("");
 	$("#cat span").text(txt);
 	if(loading==true) return;
 	var item_top_cat_id = $(obj).find('.fliter-selected span').attr('data-catid');
 	if(item_top_cat_id != item_list.top_cat_id){
 		item_list.pindex = 1;
 	}
	var params = {
		'is_online'	: item_list.is_online,
		'sort'		: item_list.sort,
		'top_cat_id': item_top_cat_id,
		'pindex'	: item_list.pindex
	};

	$('#item-list-div').html("");
	item_ajax(params);
}
// 升降序
function sort_fliter(obj){ 
	var txt = $(obj).find('.fliter-selected span').text();
 	$("[name='keyword']").val("");
 	$("#sort span").text(txt);
 	if(loading==true) return;
 	var item_sort = $(obj).find('.fliter-selected span').attr('data-sort');
 	if(item_sort != item_list.sort){
 		item_list.pindex = 1;
 	}
	var params = {
		'is_online'	: item_list.is_online,
		'sort'		: item_sort,
		'top_cat_id': item_list.top_cat_id,
		'pindex'	: item_list.pindex
	};
	$('#item-list-div').html("");
	item_ajax(params);
}*/
// 加载数据
var loading = false,item_list = Array();
item_list.pindex=1;
load_item_list();
function load_item_list(){
	if(loading==true) return;
	var params = {
		'pindex': item_list.pindex
	}

	loading = true;
	item_ajax(params);
}

//加载更多数据
var  Currentpindex=0,endpindex=0;
function load_more() {
	if(loading==true) return;
	Currentpindex=item_list.pindex;
	item_list.pindex++;
	
	var params = {
		'keyword'	: item_list.keyword,
		//'is_online'	: item_list.is_online,
		//'sort'		: item_list.sort,
		//'top_cat_id': item_list.top_cat_id,
		'pindex'	: item_list.pindex
	};

	loading = true;
	item_ajax(params);
}

// 加载搜索结果
function load_search(str){
	if(loading==true) return;

	var params = {
		'keyword': str,
		'pindex':1
	}
	$('#item-list-div').html("");
	item_ajax(params);
}
// AJAX请求
function item_ajax(par){
	loading = true;
	$.post(url, par, function(data) {
		try {
			data = $.parseJSON(data);
			item_list = data;
			if(!data.has_more) { 
				if(endpindex>0){
				   item_list.pindex=endpindex;
				}else{
				   endpindex=item_list.pindex=Currentpindex+1;
				}
			}
			appendItemHtml(data.has_more, data.list,data.type);
		} catch (e) {
			alert("请求数据错误");
		}
		loading = false;
		return;
	});
}
//将结果加入页面
function appendItemHtml(has_more, list,type) {
	if(list.length == 0){
		if(!($('#item-list-div').size()>0)){
		var content = "<li class='item-list-container' style='text-align:center'>暂无商品</li>";
		$(content).appendTo('#item-list-div');
		}else{
		   return false;
		}
	}
	for (var i = 0; i < list.length; i++) {
		var item = list[i];
		
		/*if(item.is_online == 0){
			var online_content = "<a class='item-operation' data-itemid='"+item.group_id+"' onclick='item_online(this)'><i class='iconfont icon-iconfontdown2' style='color:#fc9a79'></i><span>上架</span></a>";
		}else{
			var online_content = "<a class='item-operation' data-itemid='"+item.group_id+"' onclick='item_offline(this)'><i class='iconfont icon-fangxiang4' style='color:#fc9a79'></i><span>下架</span></a>";
		}
		var operation = '';
		if(can_manage){
			operation = "<div class='item-operation-container'>"+
							online_content+
							"<a class='item-operation' data-itemid='"+item.group_id+"' onclick='item_delete(this)'>"+
								"<i class='iconfont icon-shanchu' style='color:#cd0009'></i><span>删除</span>"+
							"</a>"+
							"<a href='"+item.url+"' class='item-operation'>"+
								"<i class='iconfont icon-edit' style='color:#449fc6'></i><span>编辑</span>"+
							"</a>"+
						"</div>"+
						"<div class='clearfix'></div>";
		}*/
		if(type=='meal'){
		item.status=parseInt(item.status);
		var content = "<li class='item-list-container'>"+
					"<img src='"+item.list_pic+"' alt='商品图片' class='item-img' onerror=this.src='"+staticpath+"images/nopic.jpg'>"+
					"<div class='item-detail'>"+
						"<p class='item-name'>"+item.s_name+"</p>"+
						"<p class='item-price-sell'>"+
							"<span class='item-price'>原价：<strong>"+item.old_price+"</strong> 元</span>&nbsp;&nbsp;&nbsp;<span class='item-price'>现价：<strong>"+item.price+"</strong> 元</span><br/><span class='statusstr'>状态：<strong>"+item.statusstr+"</strong></span>"+
							"<span class='item-sell'>已售出：<strong>"+item.sell_count+"</strong></span>"+
						"</p>"+
					"<div class='item-operation-container'>"+
							"<a class='item-operation' data-itemid='"+item.meal_id+"' data-storeid= '"+item.store_id+"' onclick='OnOffline(this,"+item.statusopt+")'><i class='iconfont "+(item.status>0 ? 'icon-fangxiang4' :'icon-iconfontdown2')+"'  style='color:#fc9a79'></i><span>"+item.statusoptstr+"</span></a>"+
							"<a class='item-operation' data-itemid='"+item.meal_id+"'  data-storeid= '"+item.store_id+"' onclick='item_delete(this)'>"+
								"<i class='iconfont icon-shanchu' style='color:#cd0009'></i><span>删除</span>"+
							"</a>"+
							"<a href='/index.php?g=WapMerchant&c=Index&a=meal_add&sid="+item.store_id+"&mealid="+item.meal_id+"' class='item-operation'>"+
								"<i class='iconfont icon-edit' style='color:#449fc6'></i><span>编辑</span>"+
							"</a></div>"+
						"<div class='clearfix'></div></div>"+
					"<div class='clearfix'></div>"+
				"</li>";
		}else{
				var content = "<li class='item-list-container'>"+
					"<img src='"+item.list_pic+"' alt='商品图片' class='item-img' onerror=this.src='"+staticpath+"images/nopic.jpg'>"+
					"<div class='item-detail'>"+
						"<p class='item-name'>"+item.s_name+"</p>"+
						"<p class='item-price-sell'>"+
							"<span class='item-price'><!--原价：<strong>"+item.old_price+"</strong> 元</span>&nbsp;&nbsp;&nbsp;--><span class='item-price'>现价：<strong>"+item.price+"</strong> 元</span><br/>"+(item.wx_cheap > 0 ?'<span class="item-price">微信优惠：<strong>'+item.wx_cheap+'</strong> 元' :'')+
							"<span class='item-sell'>已售出：<strong>"+item.sale_count+"</strong></span>"+
						"</p>"+
					"</div>"+
					"<div class='clearfix'></div>"+
				"</li>";
		
		}

		$(content).appendTo('#item-list-div');
	}
	
	$(".item-detail").css('width', $(window).width()-120);
	if (has_more) {
		$('#load_more').show();
	}else{
		$('#load_more').hide();
	}
}

