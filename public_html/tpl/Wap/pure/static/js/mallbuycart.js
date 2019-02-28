var goodsCart = [], goodsNumber = 0, cookie_index = 'mall_goods_cart', cookie_buy_index = 'buy_mall_goods_cart', goodsCartMoney = 0, goods_price_list = [], goods_index_list = [], goodsCartPackCharge = 0;
/* jQuery cookie 操作*/
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
var motify = {
		timer:null,
		/*shade 为 object调用 show为true显示 opcity 透明度*/
		log:function(msg,time,shade){
			$('.motifyShade,.motify').hide();
			if(motify.timer) clearTimeout(motify.timer);
			if($('.motify').size() > 0){
				$('.motify').show().find('.motify-inner').html(msg);
			}else{
				$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
			}
			if(shade && shade.show){
				if($('.motifyShade').size() > 0){
					$('.motifyShade').css({'background-color':'rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+')'}).show();
				}else{
					$('body').append('<div class="motifyShade" style="display:block;background-color:rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+');"></div>');
				}
			}
			if(typeof(time) == 'undefined'){
				time = 3000;
			}
			if(time != 0){
				motify.timer = setTimeout(function(){
					$('.motify').hide();
				},time);
			}
		},
		clearLog:function(){
			$('.motifyShade,.motify').hide();
		}
};
var goodsCart = [], goodsNumber = 0, goodsCartMoney = 0, goods_price_list = [], goods_index_list = [], goodsCartPackCharge = 0;
$(document).ready(function(){
	
	//购物车选中 全选
	$("dt .piton span").click(function(){
		if ($(this).hasClass("on")) {
			$(this).removeClass("on");
			$(this).parents("dt").siblings("dd").find(".piton span").removeClass("on");
		} else {
			if ($(this).parents('li').siblings('li').find('span.on').size() > 0) {
				motify.log('暂时无法支持跨店支付！');
				return false;
			} else {
				$(this).addClass("on");
				$(this).parents("dt").siblings("dd").find(".piton span").addClass("on");
				$('.carbot .carbot_right .close').data('store_id', $(this).data('store_id'));
			}
		}
		count_price();
	});
	//单个选择
	$("dd .piton span").click(function(){
		if ($(this).hasClass("on")) {
			$(this).removeClass("on");
			$(this).parents('li').find('dt span').removeClass("on");
		} else {
			if ($(this).parents('li').siblings('li').find('span.on').size() > 0) {
				motify.log('暂时无法支持跨店支付！');
				return false;
			}
			$(this).addClass("on");
			var is_all = true;
			$(this).parents('li').find('dd .piton span').each(function(){
				if (!$(this).hasClass("on")) {
					is_all = false;
				}
			});
			if (is_all) $(this).parents('li').find('dt span').addClass("on");
			$('.carbot .carbot_right .close').data('store_id', $(this).data('store_id'));
		}
		count_price();
	});

	$(".whole span").click(function(){
		if ($(this).hasClass("on")) {
			$(this).removeClass("on");
			$(".piton span").removeClass("on"); 
		} else {
			$(this).addClass("on");
			$(".piton span").addClass("on");
			$(".carbot_right .whole").text("删除").css("background","none") 
		}
	});

	$('.carbot .carbot_right .close').click(function(){
		if ($('dd .piton span.on').size() < 1) {
			motify.log('请选择您要买单的商品！');
			return false;
		} else {
			var cookieProductCart = [];
			$("dd .piton span.on").each(function(){
				var this_index = format_cart_data($(this).data('index_key'));
				if (this_index != null) {
					cookieProductCart.push(goodsCart[this_index]);
				}
			});
			
			
		   var cookieProductCartObj = {};
		    for(var i in cookieProductCart){
		        var k = Math.floor(i/5);
		        if(!cookieProductCartObj[k]){
		            cookieProductCartObj[k] = [];
		        }
		        cookieProductCartObj[k].push(cookieProductCart[i]);
		    }
		    for(var i in cookieProductCartObj){
		        $.cookie(cookie_buy_index + '_' + i, JSON.stringify(cookieProductCartObj[i]),{expires:700,path:'/'});
		    }
            $.cookie('mall_buy_type','add');
//			$.cookie(cookie_buy_index, JSON.stringify(cookieProductCart), {expires:700,path:'/'});
			
//			window.localStorage.setItem(cookie_buy_index, JSON.stringify(cookieProductCart));//, {expires:700,path:'/'});
			location.href = save_url + '&store_id=' + $(this).data('store_id');
//			location.href = save_url + '&store_id=' + $(this).data('store_id') + '&params=' + JSON.stringify(cookieProductCart);
		}
	});
	var h=$(window).height();
	$(".mask").height(h);
	$(".carxq_ul").css("max-height",h-200);

	//购物单
	$(".total_end").click(function(){
		$(".carxq").show();
		$(".mask").show();
	});
	$(".shut").click(function(){
		$(this).parents(".carxq").hide();
		$(".mask").hide();
	});


	//加减号的操作
	$(document).on('click', '.plus em', function() {
		var index_key = $(this).data('index_key'), store_id = $(this).data('store_id'), price = $(this).data('price'), stock = $(this).data('stock'),extra_pay_price = $(this).data('extra_pay_price');
		var this_index = format_cart_data(index_key);
		if (this_index != null) {
			if ($(this).attr('class') == 'jia') {
				this_num = goodsCart[this_index].count + 1;
				if (stock != -1 && this_num > stock) {
					motify.log('库存不足，不能购买！');
					return false;
				}
			} else {
				this_num = goodsCart[this_index].count - 1;
				if (this_num < 1) {
					motify.log('不能再减了');
					return false;
					if ($('#' + index_key).parents('li').find('.goods').size() > 1) {
						$('#' + index_key).remove();
					} else {
						$('#' + index_key).parents('li').remove();
					}
				}
			}
			$(this).parents('.plus').find('input').val(this_num);
			goodsCart[this_index].count = this_num;
			goodsCart[this_index].productStock = stock;
			goodsCart[this_index].productPrice = price;
			goodsCart[this_index].extra_pay_price = extra_pay_price;
			
				
			$(this).parents('li').find('.total_top span').text(stringifyCart(store_id));
			
			count_price();
		}
	});

	
	$('.carbot .carbot_right .whole').click(function(){
		if ($('dd .piton span.on').size() < 1) {
			motify.log('请选择您要删除的商品！');
			return false;
		} else {
			$('dd .piton span.on').each(function(){
				var index_key = $(this).data('index_key'), store_id = $(this).data('store_id');
				var this_index = format_cart_data(index_key);
				if (this_index != null) {
						if ($('#' + index_key).parents('li').find('.goods').size() > 1) {
							$('#' + index_key).remove();
						} else {
							$('#' + index_key).parents('li').remove();
						}
					$(this).parents('.plus').find('input').val(0);
					goodsCart[this_index].count = 0;
					$(this).parents('li').find('.total_top span').text(stringifyCart(store_id));
				}
			});
			if ($('.endcar').find('.goods').size() > 0) {
				$('.stroll').hide();
			} else {
				$('.stroll').show();
			}
			count_price();
		}
	});
	
	
	init_goods_menu();
	count_price();
});

function format_cart_data(index)
{
	var this_index = null;
	for (var i in goodsCart) {
		if (goodsCart[i].count > 0) {
			var old_goodsCartKey = 's_' + goodsCart[i].store_id + '_g_' + goodsCart[i].productId;
			if (goodsCart[i]['productParam'].length) {
				for (var pi in goodsCart[i]['productParam']) {
					if (goodsCart[i]['productParam'][pi].type == 'spec') {
						old_goodsCartKey += '_s_' + goodsCart[i]['productParam'][pi].id;
					} else {
						if (goodsCart[i]['productParam'][pi]['data'].length) {
							for (var di in goodsCart[i]['productParam'][pi]['data']) {
								old_goodsCartKey += '_v_' + goodsCart[i]['productParam'][pi]['data'][di].id;
							}
						}
					}
				}
			}
			if (index == old_goodsCartKey) {
				this_index = i;
				break;
			}
		}
	}
	return this_index;
}

function count_price()
{
	var total_price = 0;
	var extra_total_price = 0;
	$("dd .piton span.on").each(function(){
		var this_index = format_cart_data($(this).data('index_key'));
		if (this_index != null) {
		    if (goodsCart[this_index].maxNum > 0 && goodsCart[this_index].count > goodsCart[this_index].maxNum) {
		        total_price += parseFloat(goodsCart[this_index].maxNum * goodsCart[this_index].productPrice);
		        total_price += parseFloat((parseInt(goodsCart[this_index].count) - parseInt(goodsCart[this_index].maxNum)) * goodsCart[this_index].oldPrice);
		    } else {
		        total_price += parseFloat(goodsCart[this_index].count * goodsCart[this_index].productPrice);
		    }
			
	
			extra_total_price+=parseFloat(goodsCart[this_index].count * goodsCart[this_index].extra_pay_price);
		}
	});
	total_price = parseFloat(total_price.toFixed(2));
	if(open_extra_price==1&&extra_total_price>0){
		$('.carbot .carbot_left .close').text('合计:￥' + total_price+'+'+extra_total_price+extra_price_name);
	}else{
		$('.carbot .carbot_left .close').text('合计:￥' + total_price);	
	}
	return total_price;
}


function stringifyCart(store_id)
{
	var cookieProductCart = [], total_price = 0,extra_price=0;
    for(var i = 0; i<40; i++){
        $.cookie(cookie_index + '_' + i, null);
    }
    
	for(var i in goodsCart){
		if (goodsCart[i].count > 0) {
			cookieProductCart.push(goodsCart[i]);
			if (goodsCart[i].store_id == store_id) {
			    if (goodsCart[i].maxNum > 0 && goodsCart[i].count > goodsCart[i].maxNum) {
	                total_price += parseFloat(goodsCart[i].maxNum * goodsCart[i].productPrice);
	                total_price += parseFloat((parseInt(goodsCart[i].count) - parseInt(goodsCart[i].maxNum)) * goodsCart[i].oldPrice);
	            } else {
	                total_price += parseFloat(goodsCart[i].count * goodsCart[i].productPrice);
	            }
				extra_price += parseFloat(goodsCart[i].count * goodsCart[i].extra_pay_price);
			}
		}
	}
	if(extra_price>0&&open_extra_price){
		total_price=total_price+'+'+extra_price+extra_price_name;
	}
	
   var cookieProductCartObj = {};
    for(var i in cookieProductCart){
        var k = Math.floor(i/5);
        if(!cookieProductCartObj[k]){
            cookieProductCartObj[k] = [];
        }
        cookieProductCartObj[k].push(cookieProductCart[i]);
    }
    for(var i in cookieProductCartObj){
        $.cookie(cookie_index + '_' + i, JSON.stringify(cookieProductCartObj[i]),{expires:700,path:'/'});
    }
	    
//	$.cookie(cookie_index, JSON.stringify(cookieProductCart), {expires:700,path:'/'});
	
//	window.localStorage.setItem(cookie_index, JSON.stringify(cookieProductCart));
	
	total_price = parseFloat(total_price.toFixed(2));
	return total_price;
}



function init_goods_menu()
{
    var nowShopCart = '';//$.parseJSON(window.localStorage.getItem(cookie_index));//$.parseJSON($.cookie(cookie_index));
    var tmpShopCart = [];
    for (var i = 0; i < 40; i++) {
        var tmp = $.cookie(cookie_index + '_' + i);
        if (tmp) {
            tmpShopCart = tmpShopCart.concat($.parseJSON(tmp));
        } else {
            break;
        }
    }
    nowShopCart = tmpShopCart;
    
	goodsCart = [];
	var cart_goods_html = '', total_prices = [],extra_prices = [];
	for (var i in nowShopCart) {
		if (nowShopCart[i] != null && nowShopCart[i].count > 0) {
			var detail_name = '', goodsCartKey = 's_' + nowShopCart[i].store_id + '_g_' + nowShopCart[i].productId;
			if (total_prices[nowShopCart[i].store_id] != undefined) {
			    
			    if (nowShopCart[i].maxNum > 0 && nowShopCart[i].count > nowShopCart[i].maxNum) {
			        total_prices[nowShopCart[i].store_id] += parseFloat(nowShopCart[i].maxNum * nowShopCart[i].productPrice);
			        total_prices[nowShopCart[i].store_id] += parseFloat((parseInt(nowShopCart[i].count) - parseInt(nowShopCart[i].maxNum)) * nowShopCart[i].oldPrice);
                } else {
                    total_prices[nowShopCart[i].store_id] += parseFloat(nowShopCart[i].count * nowShopCart[i].productPrice);
                }
				extra_prices[nowShopCart[i].store_id] += nowShopCart[i].count * nowShopCart[i].extra_pay_price;
			} else {
			    if (nowShopCart[i].maxNum > 0 && nowShopCart[i].count > nowShopCart[i].maxNum) {
                    total_prices[nowShopCart[i].store_id] = parseFloat(nowShopCart[i].maxNum * nowShopCart[i].productPrice);
                    total_prices[nowShopCart[i].store_id] += parseFloat((parseInt(nowShopCart[i].count) - parseInt(nowShopCart[i].maxNum)) * nowShopCart[i].oldPrice);
                } else {
                    total_prices[nowShopCart[i].store_id] = parseFloat(nowShopCart[i].count * nowShopCart[i].productPrice);
                }
			    
//				total_prices[nowShopCart[i].store_id] = parseFloat(nowShopCart[i].count * nowShopCart[i].productPrice);
				extra_prices[nowShopCart[i].store_id] = nowShopCart[i].count * nowShopCart[i].extra_pay_price;
			}
			if (nowShopCart[i]['productParam'].length) {
				for (var pi in nowShopCart[i]['productParam']) {
					if (nowShopCart[i]['productParam'][pi].type == 'spec') {
						goodsCartKey += '_s_' + nowShopCart[i]['productParam'][pi].id;
					} else {
						if (nowShopCart[i]['productParam'][pi]['data'].length) {
							for (var di in nowShopCart[i]['productParam'][pi]['data']) {
								goodsCartKey += '_v_' + nowShopCart[i]['productParam'][pi]['data'][di].id;
								if (detail_name.length > 0) {
									detail_name += ',' + nowShopCart[i]['productParam'][pi]['data'][di].name
								} else {
									detail_name += nowShopCart[i]['productParam'][pi]['data'][di].name;
								}
							}
						}
					}
				}
			}
//			goodsNumber += parseInt(nowShopCart[i].count);
			goodsCart[i] = nowShopCart[i];
		}
	}

	for (var t in total_prices) {
		if(extra_prices[t]>0&&open_extra_price){
			$('#this_total_' + t).text('￥' + total_prices[t]+'+'+extra_prices[t]+extra_price_name);
		}else{
			$('#this_total_' + t).text('￥' + parseFloat(total_prices[t].toFixed(2)));
		}
	}
//	if (goodsNumber > 0) $('.Number').text(goodsNumber);
}
