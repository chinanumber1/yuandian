var goodsCart = [], goodsNumber = 0, cookie_index = 'shop_cart_'+store_id, cookie_buy_index = 'buy_shop_cart_'+store_id, goodsCartMoney = 0, goods_price_list = [], goods_index_list = [], goodsCartPackCharge = 0;
$(document).ready(function(){
	var myswiper = new Swiper('.swiper-container1', {
		pagination: '.swiper-container1 .swiper-pagination',
		direction : 'horizontal',
		paginationClickable :true,
		autoplay :'2000',
		autoplayDisableOnInteraction : false,
		loop: true 
	});
	//----优惠部分的操作▼------
	$(".p75").each(function(){
		var height = $(this).height();
		if (height > 64) {
			$(this).css({"height":"64px","overflow":"hidden"});
		} else{
			$(this).siblings("a.more").hide();
		}
	});
	$("a.more").toggle(function(){
		$(this).addClass("morelv").find("span").text("收起更多");
		$(this).siblings(".p75").css("height","auto");
	},function(){
		$(this).removeClass("morelv").find("span").text("展开更多");
		$(this).siblings(".p75").css("height","64px");
	});
	//----优惠部分的操作▲------
	//加减号的操作
	$(document).on('click', '.plus a', function() {
		var num = parseInt($('.plus input').val());
		if ($(this).attr('class') == 'jia') {
			num ++;
		} else {
			num --;
		}
		if (num < 1) {
			num = 1;
		}
		if (num >1) {
			$(".plus a.jian").css("color","#333333")
		}
		if (num <2) {
			$(".plus a.jian").css("color","#bfbfbf")
		}
		$('.plus input').val(num);
	});

	// 图片等比例
	$(".details .swiper-wrapper img").each(function(){
		$(this).height($(this).width());
	});
	//评论星星
	$(".title p").each(function(index, element) {
		$(this).css("width", $(this).attr("tip") * 16);
	}); 

	//联系商家
	

	//选择规格
	$(".mask").height($(window).height());
	$(document).on('click', '.spec li', function(){
		$(this).addClass("on").siblings().removeClass("on");
		show_select_txt();
	});
	
	$(document).on('click', '.property li', function(){
		if ($(this).hasClass("on")) {
			$(this).removeClass("on");
		} else {
			var max_num = parseInt($(this).parents('ul').data('num'));
			var now_num = parseInt($(this).parents('ul').find('.on').size());
			if (now_num >= max_num) {
				motify.log('最多能选' + max_num + '个');
				return false;
			}
			$(this).addClass("on");
		}
		show_select_txt();
	});
	//关闭选择的商品的窗口
	$(".del").click(function(){
		$(this).parents(".Speci").slideUp();
		$(".mask").hide();
	});
	$(".mask").click(function(){
				$(this).hide();
				$(".Speci").slideUp();
			})
	//点击规格显示
	$(".show_detail").click(function(){
		$('.one_btn').hide();
		$(".Speci").slideDown();
		$(".mask, .two_btn").show();
	});
	
	
	$(".Choice_n").click(function(){
		if ($(".Speci").is(":hidden")) {
			$(".Speci").slideDown();
			$('.two_btn').hide();
			$(".mask, .one_btn").show();
			$('.one_btn').data('type', $(this).data('type'));
		} /*else {
			if (add_cart(true)) {
				if ($(this).data('type') == 'buy') {
					
					location.href = save_url;
				}
				$(".Speci").slideUp();
				$(".mask").hide();
			}
		}
		return false;
		add_cart(true);
		return false;
		$(".Speci").slideDown();
		$(".mask").show();*/

	});
	$(document).on('click', '.one_btn, .two_btn', function(){
		if (add_cart(true)) {
			if ($(this).data('type') == 'buy') {
				location.href = save_url;// + '&params=' + window.localStorage.getItem(cookie_buy_index);
			}
			$(".Speci").slideUp();
			$(".mask").hide();
		}
	});


	$(".cars").click(function(){
		var text = $(".red span").text();
		$(".namic").text(text).css("color","#ff4d45");
		$(".opt").text("已选");
	});
	$('.evaluate dt.clr').click(function(){
		location.href = reply_url;
	});
	
	init_goods_menu();
});

function show_select_txt()
{
	var txt = [], spec_ids = [], price = $('.title_data').data('prcie'), is_seckill = $('.title_data').data('is_seckill'),extra_pay_price = $('.title_data').data('extra_pay_price');
	$('.cations .on').each(function(){
		txt.push($(this).data('name'));
	});
	$(".namic").text(txt.join(' ')).css("color","#ff4d45");
	$(".opt").text("已选");
	
	$('.cations ul').each(function(){
		if ($(this).data('type') == 'spec') {
			$(this).find('li.on').each(function(){
				spec_ids.push($(this).data('id'));
			});
		}
	});
	var now_goods_list = $.parseJSON(goods_list);
	var t_price = 0;
	if (spec_ids.length > 0) {
		if (spec_ids.length > 1) {
			var str = spec_ids.join('_');
		} else {
			var str = spec_ids[0];
		}
		max_num = 0;
		if (now_goods_list[str] != undefined) {
			if (is_seckill) {
				t_price = now_goods_list[str].seckill_price;
				max_num = now_goods_list[str].max_num;
			} else {
				t_price = now_goods_list[str].price;
				max_num = now_goods_list[str].max_num;
			}
			
			if(now_goods_list[str].stock_num==0){
				$('#show_stock_num').html('库存为0')
			}else if (now_goods_list[str].stock_num==-1){
				$('#show_stock_num').html('库存充足')
			}else{
				$('#show_stock_num').html('剩余'+now_goods_list[str].stock_num)
			}
		}
		if (max_num > 0) {
		    $('#showMax').text(max_num);
            $('#showDiscount').show();
		} else {
		    $('#showDiscount').hide();
		}
	} else if (now_goods_list == null) {
		t_price = price;
	}
	if(extra_pay_price>0&&open_extra_price==1){
		
		$('#show_format_price').html(t_price+'+'+extra_pay_price+extra_price_name);
	}else{
		
		$('#show_format_price').text(t_price);
	}
}



function add_cart(is_check)
{
	var productParam = [], store_id = $('.title_data').data('store_id'), is_seckill = $('.title_data').data('is_seckill'), maxNum = $('.title_data').data('max_num'), oldPrice = $('.title_data').data('o_price'), goods_id = $('.title_data').data('goods_id'), pack = $('.title_data').data('pack'), price = $('.title_data').data('price'), name = $('.title_data').data('name'), stock = $('.title_data').data('stock'), extra_pay_price = $('.title_data').data('extra_pay_price'),spec_ids = [], is_false = false;

	var index_key = 's_' + store_id + '_g_' + goods_id;
	$('.cations ul').each(function(){
		var type = $(this).data('type'), fid = $(this).data('id'), fname = $(this).data('name'), datas = null, select_num = $(this).data('num');
		if (type == 'spec') {
			var num = 0;
			$(this).find('li.on').each(function(){
				num = 1;
				var id = $(this).data('id'), name = $(this).data('name');
				datas = {
						type:'spec',
						spec_id:fid,
						id:id,
						name:name
				};
				index_key += '_s_' + id;
				spec_ids.push(id);
				productParam.push(datas);
			});
			if (num < 1 && is_check) {
				motify.log(fname + '规格必须选择一个');
				is_false = true;
			}
		} else {
			var temp_data = [], num = 0;
			$(this).find('li.on').each(function(){
				num ++;
				temp_data.push({'id':$(this).data('id'), 'list_id':fid, 'name':$(this).data('name')});
				index_key += '_v_' + $(this).data('id');
			});
			if (num < 1 && is_check) {
				motify.log(fname + '属性至少选择一个');
				is_false = true;
			}
			if (num > select_num && is_check) {
				motify.log(fname + '属性最多选择' + select_num + '个');
				is_false = true;
			}
			if (temp_data.length > 0) {
				datas = {type:'properties', data:temp_data};
				productParam.push(datas);
			}
		}
	});
	if (is_false) return false;
	var now_goods_list = $.parseJSON(goods_list);
	var t_price = 0;
	if (spec_ids.length > 0) {
		if (spec_ids.length > 1) {
			var str = spec_ids.join('_');
		} else {
			var str = spec_ids[0];
		}
		
		if (now_goods_list[str] != undefined) {
			if (is_seckill) {
				t_price = now_goods_list[str].seckill_price;
			} else {
				t_price = now_goods_list[str].price;
			}
			
			
			if(now_goods_list[str].stock_num==0){
        
				motify.log('库存不足，不能购买！');
				return false;
			}
			maxNum = now_goods_list[str].max_num;
			oldPrice = now_goods_list[str].price;
		}
	} else if (now_goods_list == null) {
		t_price = price;
	}
//	$('#show_format_price').text(t_price);
	
	var this_index = format_cart_data(index_key);
	
	var num = parseInt($('.plus input').val());
	if (num < 1) {
		motify.log('请选择您要购买的数量');
		return false;
	}
	if (this_index != null) {
		this_num = goodsCart[this_index].count + num;
		
        if (maxNum > 0 && maxNum < this_num) {
            if (is_seckill) {
                motify.log('每单可享受' + maxNum + '份限时优惠价，超出恢复原价');
            } else {
                motify.log('每单限购' + maxNum + '份');
                return false;
            }
        }
        
        
		if (stock != -1 && this_num > stock) {
			motify.log('库存不足，不能购买！');
			return false;
		}
		goodsCart[this_index].count = this_num;
		goodsCart[this_index].productStock = stock;
		goodsCart[this_index].productPrice = t_price;
		goodsCart[this_index].extra_pay_price = extra_pay_price;
		
		$.cookie(cookie_buy_index + '_0', JSON.stringify([goodsCart[this_index]]), {expires:700,path:'/'});
	} else {
		this_num = num;
		if (maxNum > 0 && maxNum < this_num) {
            if (is_seckill) {
                motify.log('每单可享受' + maxNum + '份限时优惠价，超出恢复原价');
            } else {
                motify.log('每单限购' + maxNum + '份');
                return false;
            }
        }
		
		if (stock != -1 && this_num > stock) {
			motify.log('库存不足，不能购买！');
			return false;
		}
		goodsCart.push({
			'store_id':store_id,
			'productId':goods_id,
			'count':this_num,
			'productName':name,
			'extra_pay_price':extra_pay_price,
			'productStock':stock,
			'productPrice':t_price,
			'productPackCharge':pack,
            'maxNum':maxNum,
            'isSeckill':is_seckill,
            'oldPrice':oldPrice,
			'productParam':productParam});
	}
	console.log(goodsCart)
	$.cookie(cookie_buy_index + '_0', JSON.stringify([{
		'store_id':store_id,
		'productId':goods_id,
		'count':this_num,
		'productName':name,
		'extra_pay_price':extra_pay_price,
		'productStock':stock,
		'productPrice':t_price,
		'productPackCharge':pack,
        'maxNum':maxNum,
        'isSeckill':is_seckill,
        'oldPrice':oldPrice,
		'productParam':productParam}]), {expires:700,path:'/'});
	goodsNumber += num;
	stringifyCart();
	$('.Number').text(goodsNumber);
	return true;
}

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
function stringifyCart()
{
    for(var i = 0; i<40; i++){
        $.cookie(cookie_index + '_' + i, null);
    }
    
	var cookieProductCart = [];
	for(var i in goodsCart){
		if (goodsCart[i].count > 0) {
			cookieProductCart.push(goodsCart[i]);
		}
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
	var cart_goods_html = '';
	for (var i in nowShopCart) {
		if (nowShopCart[i] != null && nowShopCart[i].count > 0) {
			var detail_name = '', goodsCartKey = 's_' + nowShopCart[i].store_id + '_g_' + nowShopCart[i].productId;
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
			goodsNumber += parseInt(nowShopCart[i].count);
			goodsCart[i] = nowShopCart[i];
		}
	}
	if (goodsNumber > 0){
	 $('.Number').text(goodsNumber);
	}else{
	 $('.Number').text('');
		
	}
		
	
	
	$.post(shopReplyUrl,{showCount:1},function(result){
		result = $.parseJSON(result);
		if(result){
			$('.evaluate dt span.fl').text('店铺评价('+result.all_count+')');
			var data = [];
			for (var i = 0; i < result.list.length; i++) {
				if (i < 3) {
					data.push(result.list[i]);
				} else {
					 break;
				}
			}
			laytpl($('#shopReplyTpl').html()).render(data, function(html){
				$('.evaluate dl').append(html);
			});
			$(".title p").each(function(index, element) {
				$(this).css("width", $(this).attr("tip") * 16);
			});
		}
		isLoading = false;
	});
}