var goodsCart = [], goodsNumber = 0, goodsCartMoney = 0,goodsExtraPrice=0; 
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
/* 简单的消息弹出层 */
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

function hidece(){
    $('.bg_menubtn').removeClass('active');
    $('.mask').animate('fast',function(e){
        $('.mask').hide();
    });
    $('.bg_menubtn').animate('fast',function(e){
        $('.bg_menubtn').animate({right:'-2px'},200);
    });
    $('.food_mask').animate('fast',function(e){
        $('.food_mask').animate({right:'-2.203389rem'},200);
    });
}

$(function() {
    var height = $(window).height();
  //菜单按钮点击
    
    $('.bg_menubtn').click(function(e){
        if(!$(this).is('.active')){
            $(this).addClass('active');
            $('.mask').animate('fast',function(e){
                $('.mask').show();
            });
            $(this).animate('fast',function(e){
                $(this).animate({right:'2.11389rem'},200);
            });
            $('.food_mask').animate('fast',function(e){
                $('.food_mask').animate({right:'0'},200);
            });
        }else{
            hidece();
        }
    });
    
    //$('.bg_menubtn').trigger("click");
    //蒙层点击
    $('.mask').click(function(e){hidece();});

    //右边栏点击菜类主页面回滚
    $('.shop_groom a').click(function(e){hidece();});
    //购物车按钮点击
    $('.shop_cat').click(function(e){
        $('.shopping_cat').show();
    });
    //继续点菜按钮点击
    $('.place_order a:eq(0)').click(function(e){
        $('.shopping_cat').hide();
    });
    //点菜菜名锚点点击
    $('.shop_groom>a').click(function(e){
        $(this).find('p').addClass('active').parents('a').siblings('a').find('p').removeClass('active');
    });
    $(document).on('click', '.foods_add .less_cai, .foods_add .add_cai', function(){
        var this_num = $(this).siblings("input").val(), name = $(this).data('name'), price = parseFloat($(this).data('price')), goods_id = parseInt($(this).data('id')), goodsCartKey = $(this).data('index'),goods_extra_price = parseFloat($(this).data('extra_pay_price')),goods_extra_price_name = $(this).data('extra_price_name');
        var class_type = $(this).attr('class');
        if ($(this).attr('class') == 'add_cai') {
            this_num ++;
            goodsNumber ++;
            goodsCartMoney += price;
            if(goods_extra_price > 0) {
                goodsExtraPrice += goods_extra_price;
            }
        } else {
            this_num --;
            goodsNumber --;
            goodsCartMoney -= price;
            if (goods_extra_price > 0) {
                goodsExtraPrice -= goods_extra_price;
            }
        }
		console.log(this_num)
        goodsCartMoney = parseFloat(goodsCartMoney.toFixed(2));
        var this_index = null;
		var del_key = '';
        for (var i in goodsCart) {
			if(this_num==0 && goodsCart[i].goods_id==goods_id){
				del_key = i;
			}
			if(this_num>0 && goodsCart[i].goods_id==goods_id){
				goodsCart[i].num=this_num
			}
            var old_goodsCartKey = goodsCart[i].goods_id;
            if (goodsCart[i]['params'].type == 'only') {
                if (goodsCart[i]['params'].length) {
                    for (var pi in goodsCart[i]['params']) {
                        if (goodsCart[i]['params'][pi].type == 'spec') {
                            old_goodsCartKey += '_s_' + goodsCart[i]['params'][pi].id;
                        }
                        if (goodsCart[i]['params'][pi]['data'].length) {
                            for (var di in goodsCart[i]['params'][pi]['data']) {
                                old_goodsCartKey += '_v_' + goodsCart[i]['params'][pi]['data'][di].id;
                            }
                        }
                    }
                }
            } else {
                if (goodsCart[i]['params'].length) {
                    for (var pi in goodsCart[i]['params']) {
                        old_goodsCartKey += '_' + goodsCart[i]['params'][pi].goods_id;
                    }
                }
            }
            if (goodsCartKey == old_goodsCartKey) {
                this_index = i;
                break;
            }
        }
		console.log('del_key:'+del_key)
		if (this_index != null) {
            goodsCart[this_index].num = this_num;
        } 
        if(del_key){
			goodsCart.splice(del_key, 1);
		}
       
		// else {
            // if(goods_extra_price>0){
                // goodsCart.push({
                    // 'goods_id':goods_id,
                    // 'num':this_num,
                    // 'type':'only',
                    // 'name':name,
                    // 'price':price,
                    // 'extra_price':goods_extra_price,
                    // 'extra_price_name':goods_extra_price_name,
                    // 'params':''});
            // }else{
                // goodsCart.push({
                    // 'goods_id':goods_id,
                    // 'num':this_num,
                    // 'type':'only',
                    // 'name':name,
                    // 'price':price,
                    // 'params':''});
            // }
        // }
        
        $('.goods_' + goodsCartKey).find("input").val(this_num);
        
        if ('add_cai' == class_type) {
            if ($('#goods_' + goods_id).text() != '') {
                var tNum = parseInt($('#goods_' + goods_id).text()) + 1;
            } else {
                var tNum = 1;
            }
            $('#goods_' + goods_id).text(tNum).show();
        } else {
            var tNum = parseInt($('#goods_' + goods_id).text()) - 1;
            if (tNum < 1) { 
                $('#goods_' + goods_id).hide();
            } else {
                $('#goods_' + goods_id).text(tNum).show();
            }
        }
        if (this_num < 1) {
            $('.goods_' + goodsCartKey).remove();
        }
        if (goodsNumber > 0) {
            $('#total_num').text(goodsNumber);
            if(goodsExtraPrice>0){
                $('#total_price').text(goodsCartMoney+'+'+goodsExtraPrice+extra_price_name);
            }else{
                $('#total_price').text(goodsCartMoney);
            }
            $('.shop_cat').html('<i></i>');
            $('#showTotal').show();
        } else {
            $('.shop_cat i').remove();
            $('#showTotal').hide();
        }
        stringifyCart();
    });
    
    
    $(document).on('keyup', 'input[type=tel]', function(){
        var this_num = parseInt($(this).val()), name = $(this).data('name'), price = parseFloat($(this).data('price')), goods_id = parseInt($(this).data('id')), goodsCartKey = $(this).data('index'),goods_extra_price = parseFloat($(this).data('extra_pay_price')),goods_extra_price_name = $(this).data('extra_price_name');
        if (this_num < 1 || isNaN(this_num)) {
            return false;
        }
        var this_index = null;
        for (var i in goodsCart) {
            var old_goodsCartKey = goodsCart[i].goods_id;
            if (goodsCart[i].type == 'only') {
                if (goodsCart[i]['params'].length) {
                    for (var pi in goodsCart[i]['params']) {
                        if (goodsCart[i]['params'][pi].type == 'spec') {
                            old_goodsCartKey += '_s_' + goodsCart[i]['params'][pi].id;
                        }
                        if (goodsCart[i]['params'][pi]['data'].length) {
                            for (var di in goodsCart[i]['params'][pi]['data']) {
                                old_goodsCartKey += '_v_' + goodsCart[i]['params'][pi]['data'][di].id;
                            }
                        }
                    }
                }
            } else {
                if (goodsCart[i]['params'].length) {
                    for (var pi in goodsCart[i]['params']) {
                        old_goodsCartKey += '_' + goodsCart[i]['params'][pi].goods_id;
                    }
                }
                
            }
            if (goodsCartKey == old_goodsCartKey) {
                this_index = i;
                break;
            }
        }
        if (this_index != null) {
            var oldNum = goodsCart[this_index].num;
            var diffNum = oldNum - this_num;
            if (diffNum > 0) {
                goodsNumber = goodsNumber - diffNum;
                goodsCartMoney = goodsCartMoney - diffNum * price;
                if(goods_extra_price > 0) {
                    goodsExtraPrice = goodsExtraPrice - diffNum * goods_extra_price;
                }
                goodsCartMoney = parseFloat(goodsCartMoney.toFixed(2));
                
                var tNum = parseInt($('#goods_' + goods_id).text());
                
                $('#goods_' + goods_id).text(tNum - diffNum).show();
                
            } else {
                diffNum = Math.abs(diffNum);
                goodsNumber = goodsNumber + diffNum;
                goodsCartMoney = goodsCartMoney + diffNum * price;
                if(goods_extra_price > 0) {
                    goodsExtraPrice = goodsExtraPrice + diffNum * goods_extra_price;
                }
                if ($('#goods_' + goods_id).text() != '') {
                    var tNum = parseInt($('#goods_' + goods_id).text());
                } else {
                    var tNum = 1;
                }
                $('#goods_' + goods_id).text(tNum + diffNum).show();
                goodsCartMoney = parseFloat(goodsCartMoney.toFixed(2));
            }
            goodsCart[this_index].num = this_num;
        } else {
            return false;
        }
        if (goodsNumber > 0) {
            $('#total_num').text(goodsNumber);
            if(goodsExtraPrice>0){
                $('#total_price').text(goodsCartMoney+'+'+goodsExtraPrice+extra_price_name);
            }else{
                $('#total_price').text(goodsCartMoney);
            }
            $('.shop_cat').html('<i></i>');
            $('#showTotal').show();
        } else {
            $('.shop_cat i').remove();
            $('#showTotal').hide();
        }
        stringifyCart();
    });
	
	$(document).on('click', '.next', function(){
		if (goodsNumber < 1) {
		    $('.shopping_cat').hide();
			motify.log('您还没有点餐呢！');
			return false;
		}
        $.post(saveGoods, function(res){
            if (res.err_code) {
                motify.log(res.msg);
                return false;
            } else {
                location.href = res.url;
            }
        }, 'json');
//		document.cart_confirm_form.submit();
		return false;
	});
	
	init_goods_menu();
});

function stringifyCart()
{
	var cookieProductCart = [];
	for(var i in goodsCart){
		if (goodsCart[i].num > 0) {
			cookieProductCart.push(goodsCart[i]);
		}
	}
	$.cookie(cookie_index, JSON.stringify(cookieProductCart), {expires:700,path:'/'});
}

function init_goods_menu()
{
	var nowShopCart = $.parseJSON($.cookie(cookie_index));
	goodsCart = [];
	var cart_goods_html = '';
	for (var i in nowShopCart) {
	    console.log(nowShopCart[i])
		if (nowShopCart[i] != null && nowShopCart[i].num > 0) {
			var detail_name = '', goodsCartKey = nowShopCart[i].goods_id;
            if (nowShopCart[i].type == 'only') {
    			if (nowShopCart[i]['params'].length) {
    				for (var pi in nowShopCart[i]['params']) {
    					if (nowShopCart[i]['params'][pi].type == 'spec') {
    						goodsCartKey += '_s_' + nowShopCart[i]['params'][pi].id;
    					}
    					if (nowShopCart[i]['params'][pi]['data'].length) {
    						for (var di in nowShopCart[i]['params'][pi]['data']) {
    							goodsCartKey += '_v_' + nowShopCart[i]['params'][pi]['data'][di].id;
    							if (detail_name.length > 0) {
    								detail_name += ',' + nowShopCart[i]['params'][pi]['data'][di].name
    							} else {
    								detail_name += nowShopCart[i]['params'][pi]['data'][di].name;
    							}
    						}
    					}
    				}
    			}
            } else {
                if (nowShopCart[i]['params'].length) {
                    for (var pi in nowShopCart[i]['params']) {
                        goodsCartKey += '_' + nowShopCart[i]['params'][pi].goods_id;
                    }
                }
            }
			var tmp_extra_price = '';
			if(nowShopCart[i].extra_price>0&&open_extra_price==1){
				tmp_extra_price = '+'+nowShopCart[i].extra_price+nowShopCart[i].extra_price_name
		    }
            cart_goods_html += '<div class="list_foods goods_' + goodsCartKey + '">';
            cart_goods_html += '<div class="selected_foods">';
            cart_goods_html += '<p>';
            cart_goods_html += '<img src="' + $('#goodsImage_' + nowShopCart[i].goods_id).attr('src') + '" />';
            cart_goods_html += '</p>';
            cart_goods_html += '<dl>';
            cart_goods_html += '<dt>' + nowShopCart[i].name + '</dt>';
            if (detail_name.length) {
                cart_goods_html += '<dt>' + detail_name + '</dt>';
            }
            cart_goods_html += '<dd>￥ ' + nowShopCart[i].price + tmp_extra_price + '</dd>';
            cart_goods_html += '</dl>';
            cart_goods_html += '</div>';
            cart_goods_html += '<div class="foods_add">';
            cart_goods_html += '<span class="less_cai" style="cursor: pointer;" data-price="' + nowShopCart[i].price + '" data-id="' + nowShopCart[i].goods_id + '" data-index="' + goodsCartKey + '" data-name="' + nowShopCart[i].name +'"'+ 'data-extra_pay_price="' + nowShopCart[i].extra_price +'" data-extra_price_name="' + nowShopCart[i].extra_price_name +'"></span>';
            cart_goods_html += '<input type="tel" value="' + nowShopCart[i].num + '" data-oldNum="' + nowShopCart[i].num + '" data-price="' + nowShopCart[i].price + '" data-id="' + nowShopCart[i].goods_id + '" data-index="' + goodsCartKey + '" data-name="' + nowShopCart[i].name +'"'+ 'data-extra_pay_price="' + nowShopCart[i].extra_price +'" data-extra_price_name="' + nowShopCart[i].extra_price_name +'"/>';
            cart_goods_html += '<span class="add_cai" style="cursor: pointer;" data-price="' + nowShopCart[i].price + '" data-id="' + nowShopCart[i].goods_id + '" data-index="' + goodsCartKey + '" data-name="' + nowShopCart[i].name +'"'+ 'data-extra_pay_price="' + nowShopCart[i].extra_price +'" data-extra_price_name="' + nowShopCart[i].extra_price_name +'"></span>';
            cart_goods_html += '</div>';
            cart_goods_html += '</div>';
			$('.goods_' + goodsCartKey).find("input").val(parseInt(nowShopCart[i].num)).show();
			console.log($('#goods_' + nowShopCart[i].goods_id).text())
			if ($('#goods_' + nowShopCart[i].goods_id).text() != '') {
			    $('#goods_' + nowShopCart[i].goods_id).text(parseInt($('#goods_' + nowShopCart[i].goods_id).text()) + parseInt(nowShopCart[i].num)).show();
			} else {
			    $('#goods_' + nowShopCart[i].goods_id).text(parseInt(nowShopCart[i].num)).show();
			}
			
			goodsNumber += parseInt(nowShopCart[i].num);
			goodsCartMoney += parseFloat(nowShopCart[i].price) * parseInt(nowShopCart[i].num);
			if(nowShopCart[i].extra_price>0&&open_extra_price==1){
				goodsExtraPrice+=parseFloat(nowShopCart[i].extra_price) * parseInt(nowShopCart[i].num);
				var extra_price_name = nowShopCart[i].extra_price_name;
			}
			goodsCart[i] = nowShopCart[i];
		}
	}
	$('.shopping_cat .all_foods').html(cart_goods_html);
	if (goodsNumber > 0) {
		$('#total_num').text(goodsNumber);
		if(goodsExtraPrice>0){
			$('#total_price').text(goodsCartMoney+'+'+goodsExtraPrice+extra_price_name);
		}else{			
			$('#total_price').text(goodsCartMoney);
		}
		$('.shop_cat').html('<i></i>');
		$('#showTotal').show();
	} else {
	    $('.shop_cat i').remove();
	    $('#showTotal').hide();
	}
}