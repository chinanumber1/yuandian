var tab = '', order = '', tips = '', timeout = 0, temp = '';
$(document).ready(function(){
	if((document.domain.indexOf('.pigcms.com') != -1 || document.domain.indexOf('.group.com') != -1) && store_id != '2'){
		layer.open({
			// icon: 5,
			skin: 'layui-layer-molv',
			title: '温馨提示',
			area:['400px','230px'],
			closeBtn: 0,
			content:'小猪CMS推荐您前往默认店铺体验试用，获得最佳真实的体验感受。（演示网站的信息多为客户体验功能时添加，数据比较随意会影响真实体验）',
			btn:['前往观看最佳效果','不要走'],
			yes:function(){
				layer.closeAll();
				layer.load(1, {
					shade: [0.6,'#fff']
				});
				window.location.href = 'http://'+document.domain+'/meal/2.html';
			}
		});
	}
	$('#tab1 .menu li.tab').hover(function(){
		$(this).addClass('hover');
		var offIndex = $(this).index();
		var left = offIndex > 0 ? offIndex*140+20+20 : offIndex*140+20;
		$('#tab1 .menu .btmline').animate({'left':left+'px'},'fast');
	},function(){
		$(this).removeClass('hover');
		var offIndex = $('#tab1 .menu li.off').index();
		var left =  offIndex > 0 ? offIndex*140+20+20 : offIndex*140+20;
		$('#tab1 .menu .btmline').animate({'left':left+'px'},'fast');
	}).click(function(){
		var offIndex = $(this).index();
		$(this).addClass('off').siblings('.tab').removeClass('off');
		$('#con_one_'+(offIndex+1)).show().siblings().hide();
		if(offIndex == 1){
			get_reply_list(1, tab, order);
		}
	});
	
	$('.top_shop_qrcode').hover(function(){
		$(this).addClass('hover').find('img').css('left',$(this).position().left+50);
	},function(){
		$(this).removeClass('hover');
	});
	$('.buygoods').on('click', function(event){
		var src = $(this).find('img').attr('src');
		var offset = $('#i-shopping-cart').offset(), flyer = $('<img class="temp_image" src="'+src+'" width="70" height="70" style="z-index:10000"/>');
		var id = parseInt($(this).find('.buycar').attr('data-id'));
		var name = $(this).find('.buycar').attr('data-name');
		var price = $(this).find('.buycar').attr('data-price');
		var star = $(this).find('.buycar').offset();

		flyer.fly({
		    start: {
		        left: event.pageX,
		        top: event.pageY - $(window).scrollTop()
		    },
		    end: {
		        left: offset.left,
		        top: offset.top  - $(window).scrollTop(),
		        width: 24,
		        height: 20
		    },
		    onEnd:function() {
		    	var total = parseInt($('.totalnumber').html());
		    	if ($('.tprice').html() == null || $('.tprice').html() == '') {
		    		var total_price = 0;
		    	} else {
		    		var total_price = parseFloat($('.tprice').html());
		    	}
		    	get_meals(id, '+');
		    	$('.totalnumber, .count').html(total+1);
		    	total_price += parseFloat(price);
		    	$('.tprice').html(total_price.toFixed(2));
		    	$('.bill').html('￥' + total_price.toFixed(2));
		    	if (total > 0) {
		    		var flag = true;
		    		var i = 0;
		    		$('.order-list li').each(function(index, domEle){
		    			if (parseInt($(domEle).attr('data-fid')) == id) {
		    				flag = false;
		    				$(domEle).find('input').val(parseInt($(domEle).find('input').val()) + 1);
		    			}
		    			i++;
		    		});
		    		if (flag) {
		    			var html = '<li class="clearfix  food-' + id + '" data-fid="' + id + '" data-price="' + price + '">';
				    	html += '<div class="fl na" title="' + name + '">' + name + '</div>';
				    	html += '<div class="fl modify clearfix">';
				    	html += '<a href="javascript:;" class="fl del">×</a><a href="javascript:;" class="fl minus">-</a>';
				    	html += '<input type="text" class="fl txt-count" value="1 " maxlength="2">';
				    	html += '<a href="javascript:;" class="fl plus">+</a>';
				    	html += '</div>';
				    	html += '<div class="fl pri">';   
				    	html += '<span>¥' + price + '</span>';
				    	html += '</div>';
				    	html += '</li>';
				    	var h = 129 + i * 50;
						if(i > 5){
							h = 379;
							$('.order-list ul').css({'height':'300px','overflow-y':'scroll'});
						}else{
							$('.order-list ul').css({'height':'','overflow-y':''});
						}
						$('.order-list').find('ul').append(html);
				    	$('.order-list').css('top', '-' + h + 'px');
		    		}
		    	} else {
			    	var html = '<li class="clearfix  food-' + id + '" data-fid="' + id + '" data-price="' + price + '">';
			    	html += '<div class="fl na" title="' + name + '">' + name + '</div>';
			    	html += '<div class="fl modify clearfix">';
			    	html += '<a href="javascript:;" class="fl del">×</a><a href="javascript:;" class="fl minus">-</a>';
			    	html += '<input type="text" class="fl txt-count" value="1 " maxlength="2">';
			    	html += '<a href="javascript:;" class="fl plus">+</a>';
			    	html += '</div>';
			    	html += '<div class="fl pri">';   
			    	html += '<span>¥' + price + '</span>';
			    	html += '</div>';
			    	html += '</li>';
			    	$('.order-list').find('ul').append(html);
			    	$('.order-list').css('top', '-129px');
		    	}
		    	$('.ready-pay').css('display', 'none');//,.temp_image
		    	$('.go-pay').css('display', 'inline-block');
		    	flyer.remove();   //2:3,2|4,1|5,1|78,1
			}
		});
	}).hover(function(){
		var title = $(this).data('title');
		if($(this).find('.goodsDesc').size() ==0 && title!=''){
			$(this).append('<div class="goodsDesc">'+$(this).data('title')+'</div>');
		}
	},function(){
		$(this).find('.goodsDesc').remove();
	});
	
	if($(window).width() < 1280){
		$('.shopping-cart').css({'margin-right':'0px','right':'0px'})
	}
	$(window).resize(function(){
		if($(window).width() < 1280){
			$('.shopping-cart').css({'margin-right':'0px','right':'0px'})
		}
	});
	
	$('.clear-cart').on('click', function(){
		get_meals(0, '--');
		$('.order-list').find('ul').html('');
		$('.order-list').css('top', '0px');
		$('.totalnumber, .count').html(0);
    	$('.tprice').html(0);
    	$('.bill').html('￥0');
    	$('.ready-pay').css('display', 'inline-block');
    	$('.go-pay').css('display', 'none');
	});
	$(document).on('click', '.minus', function(e){
		var obj = $(this).parents('li');
		var num = parseInt(obj.find('.txt-count').val()), price = obj.attr('data-price'), meal_id = parseInt(obj.attr('data-fid'));
		get_meals(meal_id, '-');
		if (num > 1) {
			obj.find('input').val(num - 1);
		} else {
			obj.remove();
			var top = parseInt($('.order-list').css('top'));
			top += 50;
			if (top > -129) {
				$('.order-list').css('top', '0px');
		    	$('.ready-pay').css('display', 'inline-block');
		    	$('.go-pay').css('display', 'none');
			} else {
				var i = 0;
	    		$('.order-list li').each(function(index, domEle){
	    			i++;
	    		});
		    	var h = 129 + (i - 1) * 50;
				if(i > 6){
					h = 379;
					$('.order-list ul').css({'height':'300px','overflow-y':'scroll'});
				}else{
					$('.order-list ul').css({'height':'','overflow-y':''});
				}
		    	$('.order-list').css('top', '-' + h + 'px');
			}
		}
		
		var total = parseInt($('.totalnumber').html());
    	if ($('.tprice').html() == null || $('.tprice').html() == '') {
    		var total_price = 0;
    	} else {
    		var total_price = parseFloat($('.tprice').html());
    	}
		$('.totalnumber, .count').html(total - 1);
    	total_price -= parseFloat(price);
    	$('.tprice').html(total_price.toFixed(2));
    	$('.bill').html('￥' + total_price.toFixed(2));
	});
	
	$(document).on('click', '.del', function(e){
		var obj = $(this).parents('li');
		var num = parseInt(obj.find('.txt-count').val()), price = obj.attr('data-price'), meal_id = parseInt(obj.attr('data-fid'));
		get_meals(meal_id, '-*');
		
		obj.remove();
		var top = parseInt($('.order-list').css('top'));
		top += 50;
		if (top > -129) {
			$('.order-list').css('top', '0px');
	    	$('.ready-pay').css('display', 'inline-block');
	    	$('.go-pay').css('display', 'none');
		} else {
			var i = 0;
    		$('.order-list li').each(function(index, domEle){
    			i++;
    		});
	    	var h = 129 + (i - 1) * 50;
			if(i > 6){
				h = 379;
				$('.order-list ul').css({'height':'300px','overflow-y':'scroll'});
			}else{
				$('.order-list ul').css({'height':'','overflow-y':''});
			}
	    	$('.order-list').css('top', '-' + h + 'px');
		}
		
		var total = parseInt($('.totalnumber').html());
    	if ($('.tprice').html() == null || $('.tprice').html() == '') {
    		var total_price = 0;
    	} else {
    		var total_price = parseFloat($('.tprice').html());
    	}
		$('.totalnumber, .count').html(total - num);
    	total_price -= parseFloat(price) * num;
    	$('.tprice').html(total_price.toFixed(2));
    	$('.bill').html('￥' + total_price.toFixed(2));
	});
	
	$(document).on('click', '.plus', function(e){
		var obj = $(this).parents('li');
		var num = parseInt(obj.find('.txt-count').val()), price = obj.attr('data-price'), meal_id = parseInt(obj.attr('data-fid'));
		obj.find('input').val(num + 1);
		get_meals(meal_id, '+');
		
		var total = parseInt($('.totalnumber').html());
    	if ($('.tprice').html() == null || $('.tprice').html() == '') {
    		var total_price = 0;
    	} else {
    		var total_price = parseFloat($('.tprice').html());
    	}
		$('.totalnumber, .count').html(total+1);
    	total_price += parseFloat(price);
    	$('.tprice').html(total_price.toFixed(2));
    	$('.bill').html('￥' + total_price.toFixed(2));
	});
	init();
	
	$('.tab_title a').click(function(){
		tab = $(this).attr('data-tab');
		$('.tab_title a').removeClass('on');
		$(this).addClass('on');
		get_reply_list(1, tab, order);
	});
	$('.module_s_open_btn').click(function(){
		var parentModule = $(this).parents('.module_s');
		if(parentModule.hasClass('module_s_open')){
			parentModule.removeClass('module_s_open');
			$(this).html('展开');
		}else{
			parentModule.addClass('module_s_open');
			$(this).html('收起');
		}
	});
	
	$('.select').change(function(){
		order = $(this).val();
		get_reply_list(1, tab, order);
	});
	
	$(document).on('click', '.page div, .page dd', function(e){
		if (parseInt($(this).find('a').attr('data-index')) > 0)
		get_reply_list(parseInt($(this).find('a').attr('data-index')), tab, order);
	});
});
//stroe_id:meal_id,num|
function init()
{
	var str = $.cookie('meal_list');
	if (str == '' || str == null) return false;
	var arr = str.split(":");
	if (arr[0] != store_id) {
		$.cookie("meal_list", '', {expires:365, path:"/"});
		return false;
	}
	
	var meals = arr[1].split('|');
	var html = '', it = 0, total = 0, total_price = 0;
	$.each(meals, function(i, item){
		var t = item.split(',');
		var id = t[0], num = t[1];
		var obj = $('.item_' + id).find('.buycar');
		var name = obj.attr('data-name'), price = obj.attr('data-price');
		total += parseInt(num);
		total_price += parseFloat(price) * parseInt(num);
		
		html += '<li class="clearfix  food-' + id + '" data-fid="' + id + '" data-price="' + price + '">';
    	html += '<div class="fl na" title="' + name + '">' + name + '</div>';
    	html += '<div class="fl modify clearfix">';
    	html += '<a href="javascript:;" class="fl del">×</a><a href="javascript:;" class="fl minus">-</a>';
    	html += '<input type="text" class="fl txt-count" value="' + num + '" maxlength="2">';
    	html += '<a href="javascript:;" class="fl plus">+</a>';
    	html += '</div>';
    	html += '<div class="fl pri">';   
    	html += '<span>¥' + price + '</span>';
    	html += '</div>';
    	html += '</li>';
    	it ++;
	});
	$('#shop_cart').val(str);
	var h = 129 + parseInt(it - 1) * 50;
	if(it > 5){
		h = 379;
		$('.order-list ul').css({'height':'300px','overflow-y':'scroll'});
	}else{
		$('.order-list ul').css({'height':'','overflow-y':''});
	}
	$('.order-list').find('ul').append(html);
	$('.order-list').css('top', '-' + h + 'px');
	
	
	
	$('.ready-pay').css('display', 'none');
	$('.go-pay').css('display', 'inline-block');
	$('.totalnumber, .count').html(total);
	$('.tprice').html(total_price.toFixed(2));
	$('.bill').html('￥' + total_price.toFixed(2));
}

function get_meals(meal_id, type)
{
	if (type == '--') {
		$('#shop_cart').val('');
		$.cookie("meal_list", '', {expires:365, path:"/"});
		return false;
	}
	var str = $.cookie('meal_list');
	if (str == '' || str == null) {
		$('#shop_cart').val(store_id + ':' + meal_id + ',' + 1);
		$.cookie("meal_list", store_id + ':' + meal_id + ',' + 1, {expires:365, path:"/"});
		return false;
	}
	var arr = str.split(":");
	var meals = arr[1].split('|');
	var new_str = store_id + ':', pre = '', flag = true;
	$.each(meals, function(i, item){
		var t = item.split(',');
		if (t[0] == meal_id) {
			flag = false;
			if (type == '+') {
				new_str += pre + t[0] + ',' + parseInt(parseInt(t[1]) + 1);
				pre = '|';
			} else if (type == '-'){
				if (parseInt(t[1]) > 1) {
					new_str += pre + t[0] + ',' + parseInt(parseInt(t[1]) - 1);
					pre = '|';
				}
			}
		} else {
			new_str += pre + item;
			pre = '|';
		}
	});
	if (flag) {
		new_str += pre + meal_id + ',1';
	}
	if (new_str != store_id + ':') {
		$('#shop_cart').val(new_str);
		$.cookie("meal_list", new_str, {expires:365, path:"/"});
		return false;
	} else {
		$('#shop_cart').val('');
		$.cookie("meal_list", '', {expires:365, path:"/"});
		return false;
	}
}


function get_reply_list(page, tab, order){
	$('.ratelist-content').prepend('<div class="loading-surround--large ratelist-content__loading J-list-loading"></div>');
	$('.J-rate-list').empty();
	$('.J-rate-paginator').empty();
	
	$.post(get_reply_url,{tab:tab,order:order,page:page},function(result){
		$('.J-list-loading').remove();
		if(result == '0'){
			$('.J-rate-list').html('<li class="norate-tip">暂无该类型评价</li>');
		}else{
			result = $.parseJSON(result);
			$('.J-rate-paginator').html(result.page);
			$.each(result.list,function(i,item){
				var item_html = '<dd class="cf"><div class="appraise_li-list_img"><div class="appraise_li-list_icon"><img src="'+(item.avatar!='' ? item.avatar : default_avatar)+'" /></div></div><div class="appraise_li-list_right cf"><p class="nickname">'+item.nickname+'</p><div class="appraise_li-list_top cf"><div class="appraise_li-list_top_icon"><div><span style="width:'+(parseInt(item.score)/5*100)+'%"></span></div></div>'+(item.score == 5 ? '<div class="appraise_li-list_top_icon_txt">好评</div>' : (item.score == 1 ? '<div class="appraise_li-list_top_icon_txt bad">差评</div>' : '<div class="appraise_li-list_top_icon_txt middle">中评</div>'))+'<div class="appraise_li-list_data">'+item.add_time+'</div></div><div class="appraise_li-list_txt">'+item.comment+'</div>';
				if(item.pics){
					item_html+= '<div class="pic-list J-piclist-wrapper"><div class="J-pic-thumbnails pic-thumbnails"><ul class="pic-thumbnail-list widget-carousel-indicator-list">';
					$.each(item.pics,function(j,jtem){
						item_html+= '<li m-src="'+jtem.m_image+'" big-src="'+jtem.image+'"><a class="pic-thumbnail" href="#" hidefocus="true"><img src="'+jtem.s_image+'"></a></li>';
					});
					item_html+= '</ul></div></div>';
				}
				if(item.merchant_reply_content != ''){
					item_html+= '<p class="biz-reply">商家回复：'+item.merchant_reply_content+'</p>';
				}
				item_html+= ''+(item.store_name ? '<p class="shopname">'+item.store_name+'</p>' : '')+'</dd>';
				$('.J-rate-list').append(item_html);
			});
		}
//		if(page>1){$(window).scrollTop($('.J-rate-filter').offset().top+50);}
	});
}