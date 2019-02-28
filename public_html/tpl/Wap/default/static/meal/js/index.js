jQuery.cookie = function (key, value, options) {
    if (arguments.length > 1 && (value === null || typeof value !== "object")){
        options = jQuery.extend({}, options);
        if (value === null) {
            options.expires = -1;
        }
        if (typeof options.expires === 'number'){
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }
        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? String(value) : encodeURIComponent(String(value)),
            options.expires ? '; expires=' + options.expires.toUTCString() : '',
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

$(function(){
	FastClick.attach(document.body);
	var height=$(window).height();
	var width=$(window).width();
  	$(".shop_menu").css("height",height-80);
//	$(".shop_product").css("height",height-40);
	$(".choos_base").css("height",height-60);
	var sw=$(".shop_img").width();
	$(".shop_img_mark").css("height",sw*11/16);
	
	$(".shop_img img").css("height",sw*11/16);
	if(width<480){
		$(".shop_product").css("width",width-90);
	}
	if(width>480){
		$(".shop_product").css("width",width-160);
	}
	$(".shop_menu li").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
	});
    $(".menu_num,.layer").click(function(){
		$(".choose").toggle();
		$(".layer").toggle();
	});
});
$(document).ready(function(){
	$('.shop_product li').on('click', function(event){
		var src = $(this).find('img').attr('src');
		var offset = $('.menu_num i').offset(), flyer = $('<img class="temp_image" src="'+src+'" width="70" height="70" style="z-index:10000"/>');
		var id = parseInt($(this).find('.buycar').attr('data-id'));
		var name = $(this).find('.buycar').val();
		var price = $(this).find('.buycar').attr('data-price');
		var star = $(this).find('.buycar').offset();
		
		var total = parseInt($('#show_total_num').html());
    	if ($('#total_price').html() == null || $('#total_price').html() == '') {
    		var total_price = 0;
    	} else {
    		var total_price = parseFloat($('#total_price').html());
    	}
    	get_meals(id, '+');
    	$('#show_total_num, #total_num').html(total+1);
    	total_price += parseFloat(price);
    	$('#total_price').html(total_price.toFixed(2));
    	if (total > 0) {
    		var flag = true;
    		$('.choose_list li').each(function(index, domEle){
    			if (parseInt($(domEle).attr('data-id')) == id) {
    				flag = false;
    				$('#food_' + id).text(parseInt($(domEle).find('.number').html()) + 1);
    				$(domEle).find('.number').html(parseInt($(domEle).find('.number').html()) + 1);
    			}
    		});
    		if (flag) {
    			var html = '<li class="clearfix" data-id="' + id + '" data-price="' + price + '">';
    			html += '<div class="chw1">' + name + '</div>';
    			html += '<div class="chw2">';
    			html += '<b class="minus">-</b><i class="number">1</i><b class="plus">+</b>';
    			html += '</div>';
    			html += '<div class="chw3">¥' + price + '</div>';
    			html += '</li>';
				$('.choose_list').append(html);
				$('#food_' + id).text(1);
    		}
    	} else {
			var html = '<li class="clearfix" data-id="' + id + '" data-price="' + price + '">';
			html += '<div class="chw1">' + name + '</div>';
			html += '<div class="chw2">';
			html += '<b class="minus">-</b><i class="number">1</i><b class="plus">+</b>';
			html += '</div>';
			html += '<div class="chw3">¥' + price + '</div>';
			html += '</li>';
			$('.choose_list').append(html);
			$('#food_' + id).text(1);
    	}
    	
		/*flyer.fly({
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
		    	var total = parseInt($('#show_total_num').html());
		    	if ($('#total_price').html() == null || $('#total_price').html() == '') {
		    		var total_price = 0;
		    	} else {
		    		var total_price = parseFloat($('#total_price').html());
		    	}
		    	get_meals(id, '+');
		    	$('#show_total_num, #total_num').html(total+1);
		    	total_price += parseFloat(price);
		    	$('#total_price').html(total_price.toFixed(2));
		    	if (total > 0) {
		    		var flag = true;
		    		var i = 0;
		    		$('.choose_list li').each(function(index, domEle){
		    			if (parseInt($(domEle).attr('data-id')) == id) {
		    				flag = false;
		    				$(domEle).find('.number').html(parseInt($(domEle).find('.number').html()) + 1);
							$('#food_' + id).text(parseInt($(domEle).find('.number').html()) + 1);
		    			}
		    			i++;
		    		});
		    		if (flag) {
		    			var html = '<li class="clearfix" data-id="' + id + '" data-price="' + price + '">';
		    			html += '<div class="chw1">' + name + '</div>';
		    			html += '<div class="chw2">';
		    			html += '<b class="minus">-</b><i class="number">1</i><b class="plus">+</b>';
		    			html += '</div>';
		    			html += '<div class="chw3">¥' + price + '</div>';
		    			html += '</li>';
						$('.choose_list').append(html);
						$('#food_' + id).text(1);
		    		}
		    	} else {
	    			var html = '<li class="clearfix" data-id="' + id + '" data-price="' + price + '">';
	    			html += '<div class="chw1">' + name + '</div>';
	    			html += '<div class="chw2">';
	    			html += '<b class="minus">-</b><i class="number">1</i><b class="plus">+</b>';
	    			html += '</div>';
	    			html += '<div class="chw3">¥' + price + '</div>';
	    			html += '</li>';
					$('.choose_list').append(html);
					$('#food_' + id).text(1);
		    	}
		    	flyer.remove();   //2:3,2|4,1|5,1|78,1
			}
		});*/
	});
	
	$('.clear-cart').on('click', function(){
		get_meals(0, '--');
		$('.choose_list').html('');
		$('#show_total_num, #total_num').html(0);
    	$('#total_price').html(0);
    	$('.shop_product').find('b').text(0);
	});
	
	$(document).on('click', '.minus', function(e){
		var obj = $(this).parents('li');
		var num = parseInt(obj.find('.number').html()), price = obj.attr('data-price'), meal_id = parseInt(obj.attr('data-id'));
		get_meals(meal_id, '-');
		if (num > 1) {
			obj.find('.number').html(num - 1);
			$('#food_' + meal_id).text(num - 1);
		} else {
			obj.remove();
			$('#food_' + meal_id).text(0);
		}
		
		var total = parseInt($('#show_total_num').html());
    	if ($('#total_price').html() == null || $('#total_price').html() == '') {
    		var total_price = 0;
    	} else {
    		var total_price = parseFloat($('#total_price').html());
    	}
		$('#show_total_num, #total_num').html(total - 1);
    	total_price -= parseFloat(price);
    	$('#total_price').html(total_price.toFixed(2));
	});
	
	$(document).on('click', '.del', function(e){
		var obj = $(this).parents('li');
		var num = parseInt(obj.find('.number').html()), price = obj.attr('data-price'), meal_id = parseInt(obj.attr('data-id'));
		get_meals(meal_id, '-*');
		$('#food_' + meal_id).text(0);
		obj.remove();
		
		var total = parseInt($('#show_total_num').html());
    	if ($('#total_price').html() == null || $('#total_price').html() == '') {
    		var total_price = 0;
    	} else {
    		var total_price = parseFloat($('#total_price').html());
    	}
		$('#show_total_num, #total_num').html(total - 1);
    	total_price -= parseFloat(price) * num;
    	$('#total_price').html(total_price.toFixed(2));
	});
	
	$(document).on('click', '.plus', function(e){
		var obj = $(this).parents('li');
		var num = parseInt(obj.find('.number').html()), price = obj.attr('data-price'), meal_id = parseInt(obj.attr('data-id'));
		obj.find('.number').html(num + 1);
		$('#food_' + meal_id).text(num);
		get_meals(meal_id, '+');
		
		var total = parseInt($('#show_total_num').html());
    	if ($('#total_price').html() == null || $('#total_price').html() == '') {
    		var total_price = 0;
    	} else {
    		var total_price = parseFloat($('#total_price').html());
    	}
		$('#show_total_num, #total_num').html(total + 1);
    	total_price += parseFloat(price);
    	$('#total_price').html(total_price.toFixed(2));
	});
	init();
	$('#click_login').click(function(){
		art.dialog.open(login_url,{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'login_handle',
			title:'请使用微信扫描二维码登录',
			padding: 0,
			width: 430,
			height: 433,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
		return false;
		
	});
	var submit_flag = false;
	$('button').click(function(){
		if(is_login == false){
			art.dialog.open(login_url,{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'login_handle',
				title:'请使用微信扫描二维码登录',
				padding: 0,
				width: 430,
				height: 433,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		}
		if (submit_flag) return false;
		var num = $('#num').val(), tableid = $('#tableid').val(), shop_cart = $('#shop_cart').val();
		if (num < 1) {
			layer.open({
			    content: '客官您好！您几位用餐？',
			    style: 'background-color:#8DCE16; color:#fff; border:none;',
			    time: 3
			});
			return false;
		}
		if (tableid < 1) {
			layer.open({
			    content: '客官您好！您现在坐在那个位置',
			    style: 'background-color:#8DCE16; color:#fff; border:none;',
			    time: 3
			});
			return false;
		}
		if (shop_cart.length < 1) {
			layer.open({
			    content: '客官您好！请点餐',
			    style: 'background-color:#8DCE16; color:#fff; border:none;',
			    time: 3
			});
			return false;
		}
		submit_flag = true;
		if ($(this).attr('id') == 'offline_pay') {
			var pay_type = 0;
		} else {
			var pay_type = 1;
		}
		layer.open({type: 2});
		$.post('/wap.php?g=Wap&c=Food&a=save_pad_order', {'store_id':store_id, 'num':num, 'tableid':tableid, 'shop_cart':shop_cart, 'pay_type':pay_type}, function(response){
			submit_flag = false;
			layer.closeAll();
			if (response.error_code) {
				layer.open({
				    content: response.msg,
				    style: 'background-color:#8DCE16; color:#fff; border:none;',
				    time: 3
				});
			} else {
				$('#shop_cart').val('');
				$.cookie("meal_list", '', {expires:365, path:"/"});
				location.href = response.url;
			}
		}, 'json');
		
	});
	
	$('#logout').click(function(){
		layer.open({
		    content: '您确定要退出当前的登录状态吗？',
		    btn: ['确定退出', '点错了，不退出'],
		    shadeClose: false,
		    yes: function(){
				$.get('/wap.php?g=Wap&c=Food&a=logout', function(response){
					location.reload();
				});
		    }, no: function(){}
		});
	});
});
 
//$(function(){
//     	var num=0;
//	    $(".shop_product li").click(function(){
//	      var i=$(this).children().find("b").text();
//		  var name=$(this).children().find(".product_name").text();
//		  var price=$(this).children().find(".product_price").text();
//		  i=parseFloat(i);
//		 
//	      i++;num++;
//		  $(this).children().find("b").text(i);
//		  $(".menu_num i").text(num);
//		//  $(".choose_list").prepend(" <li class=' clearfix'><div class='chw1'>"+name+"</div> <div class='chw2'><b class='minus'>-</b><i>"+i
//		 // +"</i><b class='plus'>+</b></div> <div class='chw3'>"+price+"</div>");
//		}) 
//});
$(function() {
	$('.barNavLi').onePageNav();
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
	var html = '', total = 0, total_price = 0;
	$.each(meals, function(i, item){
		var t = item.split(',');
		var id = t[0], num = t[1];
		var obj = $('#meal_' + id).find('.buycar');
		var name = obj.val(), price = obj.attr('data-price');
		total += parseInt(num);
		total_price += parseFloat(price) * parseInt(num);
		
		html += '<li class="clearfix" data-id="' + id + '" data-price="' + price + '">';
		html += '<div class="chw1">' + name + '</div>';
		html += '<div class="chw2">';
		html += '<b class="minus">-</b><i class="number">' + num + '</i><b class="plus">+</b>';
		html += '</div>';
		html += '<div class="chw3">¥' + price + '</div>';
		html += '</li>';
		$('#food_' + id).text(num);
	});
	$('#shop_cart').val(str);
	$('.choose_list').append(html);
	$('#show_total_num, #total_num').html(total);
	$('#total_price').html(total_price.toFixed(2));
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