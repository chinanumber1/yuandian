function parseCoupon(obj,type){
	var returnObj = {};
	for(var i in obj){
		if(typeof(obj[i]) == 'object'){
			returnObj[i] = [];
			for(var j in obj[i]){
				returnObj[i].push('满'+obj[i][j].money+'元减'+obj[i][j].minus+'元');
			}
		}else if(i=='invoice'){
			returnObj[i] = '满'+obj[i]+'元支持开发票，请在下单时填写发票抬头';
		}else if(i=='discount'){
			returnObj[i] = '店内全场'+obj[i]+'折';
		}else if(i=='verify'){
            returnObj[i] = '平台已认证店铺';
        }else if(i=='isDiscountGoods'){
            returnObj[i] = '店内有部分商品限时优惠';
        }
	}
	var textObj = [];
	for(var i in returnObj){
		if(typeof(returnObj[i]) == 'object'){
			switch(i){
				case 'system_newuser':
					textObj[i] = '平台首单'+returnObj[i].join(',');
					break;
				case 'system_minus':
					textObj[i] = '平台优惠'+returnObj[i].join(',');
					break;
				case 'newuser':
					textObj[i] = '店铺首单'+returnObj[i].join(',');
					break;
				case 'minus':
					textObj[i] = '店铺优惠'+returnObj[i].join(',');
					break;
				case 'system_minus':
					textObj[i] = '平台优惠'+returnObj[i].join(',');
					break;
				case 'delivery':
					textObj[i] = '配送费'+returnObj[i].join(',');
					break;
			}
		}else if(i=='invoice' || i=='discount' || i=='verify' || i=='isDiscountGoods'){
			textObj[i] = returnObj[i];
		}
	}
	if(type == 'text'){
		var tmpObj = [];
		for(var i in textObj){
			tmpObj.push(textObj[i]);
		}
		return tmpObj.join(';');
	}else{
		return textObj;
	}
}

var data = {};
function getList(more, keyword)
{
	if ($('.fication_list').find('a.on').data('cat_url')) {
		var cat_url = $('.fication_list').find('a.on').data('cat_url');
	} else if ($('.fication_end').find('li.on').data('cat_url')) {
		var cat_url = $('.fication_end').find('li.on').data('cat_url');
	} else {
		var cat_url = 'all';
	}
	if (keyword != undefined && keyword != '') {
		data = {
				'type_url':'-1',
				'cat_url':'all',
				'sort_url':'juli',
				'key':keyword,
				'page':more
		};
	} else {
		var type_url = -1;
		if ($('.deliver').find('span.on').size()) {
			type_url = $('.deliver').find('span.on').data('type');
		}
		data = {
				'type_url':type_url,
				'cat_url':cat_url,
				'sort_url':$('.sort').find('a.on').data('sort_url'),
				'key':'',
				'page':more
		};
	}
//	$.cookie('shop_select_params', JSON.stringify(data), {expires:700,path:'/'});
//	$.cookie('shop_select_params', data);
	$.post(ajax_list, data, function(result){
		if (keyword != undefined && keyword != '') {
			$('.search-tip').show();
			$('.Shoplist_top, .fication').hide();
			$('.keyword').text('“' + keyword + '”');
		} else {
			$('.search-tip').hide();
			$('.Shoplist_top, .fication').show();
			$('#keyword').val('');
		}
		if(result.total > 0){
// 			hasMorePage = now_page < result.totalPage ? true : false;
//			var html = format_html(result.store_list);
			laytpl($('#storeListBoxTpl').html()).render(result, function(html){
				if (more) {
					$('.navBox_list').append(html);
				} else {
					$('.navBox_list').html(html);
				}
			});
			$(".middle .fl").each(function() {
				$(this).find("p").css("width", parseFloat($(this).find("i").text()) * 15);
			});
			$(".text dd.top").each(function() {
				$(this).find("h2").css("max-width", parseFloat($(this).width()) - parseFloat($(this).find('span').width()));
			});
			
			
		  	if($('.lazy_img').size()){
				$('.lazy_img').lazyload({
					threshold:200,
					effect:'fadeIn',
					skip_invisible:false,
					failurelimit :8
				});
			}
		} else {
			$('.navBox_list').html('');
		}
		$('.count').text(result.total);
		if (result.next_page == 0) {
	  		$('.Load').hide();
	  	} else {
	  		$('.Load').show().data('page', result.next_page);
	  	}
	}, 'json');
}

function format_html(data)
{
	var html = '';
	for (var i in data) {
		html += '<li>';
		html += '<a href="' + data[i].detail_url + '">';
		html += '<div class="img">';
		html += '<img src="' + data[i].image + '" width="222" height="148">';
		html += '<div class="imgewm">';
		html += '<img class="lazy_img" src="' + data[i].qrcode_url + '" data-original="' + data[i].qrcode_url + '" width="78" height="78"/>';
		html += '<p>微信扫码 手机查看</p>';
		html += '</div>';
		html += '</div>';
		html += '<div class="text">';
		html += '<dl>';
		html += '<dd class="clr top">';
		html += '<h2 class="fl">' + data[i].name + '</h2>';
		html += '<span class="fr">' + data[i].range + '</span>';
		html += '</dd>';
		html += '<dd class="clr middle">';
		html += '<div class="fl">';
		html += '<div class="atar_Show">';
		html += '<p></p>';
		html += '</div>';
		html += '<span class="Fraction"><i>' + data[i].star + '</i>分</span>';
		html += '</div>';
		html += '<span class="fr">月售' + data[i].month_sale_count + '单</span>';
		html += '</dd>';
		html += '<dd class="clr end">';
		html += '<span class="r5">起送:￥<i>' + data[i].delivery_price + '</i></span>';
		html += '<span class="r5">配送费:￥<i>' + data[i].delivery_money + '</i></span>';
		html += '<span class="fr">' + data[i].delivery_time + '分钟</span>';
		html += '</dd>';
		html += '</dl>';
		html += '</div>';
		html += '<div class="list">';
		html += '<dl class="clr">';
		if(data[i].coupon_list.system_newuser != undefined){ 
			html += '<dd class="fl platform">首</dd>';
		}
		if(data[i].coupon_list.system_minus != undefined){
			html += '<dd class="fl reduce">减</dd>';
		}
		if(data[i].coupon_list.delivery != undefined){
			html += '<dd class="fl red">惠</dd>';
		}
		if(data[i].coupon_list.discount != undefined){
			html += '<dd class="fl zhe">折</dd>';
		}
		if(data[i].coupon_list.newuser != undefined){
			html += '<dd class="fl business">首</dd>';
		}
        if(data[i].coupon_list.minus != undefined){
            html += '<dd class="fl ticket">减</dd>';
        }
        if(data[i].coupon_list.isDiscountGoods != undefined && data[i].coupon_list.isDiscountGoods != 0){
            html += '<dd class="fl ticket">限</dd>';
        }
		if(data[i].deliver_type == 0){
			html += '<dd class="fr platform">' + deliverName + '</dd>';
		} else if(data[i].deliver_type == 1){
			html += '<dd class="fr business">商家配送</dd>';
		} else if(data[i].deliver_type == 2){
			html += '<dd class="fr express">客户自提</dd>';
		} else if(data[i].deliver_type == 3){
			html += '<dd class="fr platform">' + deliverName + '/自提</dd>';
		} else if(data[i].deliver_type == 4){
			html += '<dd class="fr business">商家配送/自提</dd>';
		} else if(data[i].deliver_type == 5){
			html += '<dd class="fr Since">快递配送</dd>';
		}
		html += '</dl>';
		html += '</div>';
		html += '<div class="position">';
		html += '<h2 class="h2top">' + data[i].name + '</h2>';
		html += '<div class="activity">';
		html += '<dl>';
		var tmpCouponList = parseCoupon(data[i].coupon_list,'array');
		if (tmpCouponList['system_newuser']) {
			html += '<dd>';
			html += '<span class="fl platform">首</span>';
			html += '<div class="a_text">' + tmpCouponList['system_newuser'] + '</div>';
			html += '</dd>';
		}
		if (tmpCouponList['system_minus']) {
			html += '<dd>';
			html += '<span class="fl reduce">减</span>';
			html += '<div class="a_text">' + tmpCouponList['system_minus'] + '</div>';
			html += '</dd>';
		}
		if (tmpCouponList['delivery']) {
			html += '<dd>';
			html += '<span class="fl red">惠</span>';
			html += '<div class="a_text">' + tmpCouponList['delivery'] + '</div>';
			html += '</dd>';
		}
		if (data[i].coupon_list.discount != undefined) {
			html += '<dd>';
			html += '<span class="fl zhe">折</span>';
			html += '<div class="a_text">店内全场' + data[i].coupon_list.discount + '折</div>';
			html += '</dd>';
		}
		if (tmpCouponList['newuser']) {
			html += '<dd>';
			html += '<span class="fl red">首</span>';
			html += '<div class="a_text">' + tmpCouponList['newuser'] + '</div>';
			html += '</dd>';
		}
        if (tmpCouponList['minus']) {
            html += '<dd>';
            html += '<span class="fl reduce">减</span>';
            html += '<div class="a_text">' + tmpCouponList['minus'] + '</div>';
            html += '</dd>';
        }
        if (tmpCouponList['isDiscountGoods']) {
            html += '<dd>';
            html += '<span class="fl reduce">限</span>';
            html += '<div class="a_text">' + tmpCouponList['isDiscountGoods'] + '</div>';
            html += '</dd>';
        }
		html += '</dl>';
		html += '</div>';
		html += '<div class="notice">';
		html += '<h2>商家公告</h2>' + data[i].store_notice;
		html += '</div>';
		html += '</div>';
		html += '</a>';
		html += '</li>';
	}
	return html;
}

$(document).ready(function(){
	$('#search').click(function(){
		getList(0, $('#keyword').val());
	});
	
	$(document).on('mouseover mouseout', ".Shoplist_end li .fix", function(event){
		if(event.type == "mouseover"){
			$(this).siblings(".position").show();
			$(this).find(".imgewm").show();
		}else if(event.type == "mouseout"){
			$(this).siblings(".position").hide();
			$(this).find(".imgewm").hide();
		}
	});

	$(".middle .fl").each(function() {
		$(this).find("p").css("width", parseFloat($(this).find("i").text()) * 15);
	});
	$(".text dd.top").each(function() {
		$(this).find("h2").css("max-width", parseFloat($(this).width()) - parseFloat($(this).find('span').width()));
	});
	$('.Load').click(function(){
		getList($(this).data('page'), $('#keyword').val());
	});

	$(".fication_end li").click(function(e){
		e.stopPropagation();
		var cat_id = $(this).data('cat_id');
		$('.fication_list').hide();
		$('.fication_list_' + cat_id).show();
		$('.fication_end').find("a").removeClass("on");
		$(this).addClass('on').siblings("li").removeClass("on");
		$('.fication_list_' + cat_id).find('a').eq(0).addClass('on');
//		getList(0, $('#keyword').val());
	});

//	$(".fication_list a").click(function(e){
//		e.stopPropagation();
//		$('.fication_end').find("a").removeClass("on");
//		$(this).addClass("on");//.parents("dd").siblings("dd").find("a").removeClass("on");
//		getList(0, $('#keyword').val());
//	});
	//列表筛选条件
	$(".Shoplist_top a:last-child").css("background","none");

	//排序的筛选
//	$(".Shoplist_top a").click(function(){
//		$(this).addClass("on").siblings("a").removeClass("on");
//		getList(0, $('#keyword').val());
//	});

	//配送方式的选择
//	$(".Shoplist_top span").click(function(){
//		if ($(this).hasClass('on')) {
//			$(this).removeClass("on");
//		} else {
//			$(this).addClass("on").siblings().removeClass("on");
//		}
//		getList(0, $('#keyword').val());
//	});

	/*底部返回顶部*/  
	$(window).scroll(function() {
		if ($(window).scrollTop() > 200) {
			$(".Return").fadeIn();
		} else {
			$(".Return").fadeOut(500);
		}
	});
	$(".Return").click(function() {
		$('body,html').animate({scrollTop: 0}, 500);
		return false;
	});
//	getList(0, '');
});