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

$(function() {
    //减少点击
    $('.bottom_length .less').click(function(e){
        var text = 1;
        text = $(this).next('input').val();
        if (text <= 1) {
            text = 1;
            $(this).next('input').val(text);
        } else {
            text --;
            $(this).next('input').val(text);
        }
    });
    //添加点击
    $('.bottom_length .add').click(function(e){
        var  text = $(this).prev('input').val();  
        text ++;
        if (stockNum != -1 && stockNum < text) {
            motify.log('最多可以选择' + stockNum + '份');
            return false;
        }
        $(this).prev('input').val(text);
    });
    
    $(document).on('click', '.changeFood ul li', function() {
        var maxNum = $(this).parents('.changeFood').data('num');
        if (maxNum == 1) {
            $(this).addClass("active").siblings('li').removeClass("active");
        } else {
            if ($(this).is('.active')) {
                $(this).removeClass("active");
            } else {
                if ($(this).parents('ul').find('.active').size() >= maxNum) {
                    motify.log('您只能选择'+maxNum+'项');
                    return false;
                } else {
                    $(this).addClass("active");
                }
            }
        }
    });
	
    
	//规格中选项的选择
	$(document).on('click', '.size .big', function(){
		var goods_id = parseInt($(this).data('goods_id'));
		$(this).addClass('active').siblings('button').removeClass('active');
		var spec_ids = [];
		$('.size').each(function(dom){
			$(this).find('.big').each(function(){
				if ($(this).hasClass('active')) {
					spec_ids.push($(this).data('id'))
				}
			});
		});
		if (spec_ids.length > 0) {
			var ALL_GOODS = $.parseJSON(all_goods);
			var price = 0;
			var properties = [];
			if (typeof(ALL_GOODS[spec_ids.join('_')]) != 'undefined') {
				price = ALL_GOODS[spec_ids.join('_')]['price'];
				stockNum = ALL_GOODS[spec_ids.join('_')]['stock_num'];
				properties = ALL_GOODS[spec_ids.join('_')]['properties'];
			}
			$('#show_price').text(price);
			if (stockNum >= 0 && stockNum < 10) {
			    $('.stock').text('剩余：' + stockNum);
			} else {
			    $('.stock').text('');
			}
			
			$('.addCart').data('price', price);
			if (properties.length > 0) {
			    for (var p in properties) {
			        $('#properties_' + properties[p].id).data('num', properties[p].num);
			    }
			}
			console.log(stockNum)
			$('.addCart').data('stock_num', stockNum);
		}
	});
	
	
   $(document).on('click', '.practice button', function(){
        var father_obj = $(this).parents('.practice'), num = father_obj.data('num');
        $(this).toggleClass("active");
        if (father_obj.find('.active').length > num) {
            $(this).removeClass("active");
            motify.log('最多可以选择' + num + '个');
            return false;
        }
    });
	
	//提交规格选中的
	$(document).on('click', '.addCart', function(){
		var goodsCartKey = $(this).data('goods_id'), goods_id = parseInt($(this).data('goods_id')), name = $(this).data('name'), price = parseFloat($(this).data('price'));
		var type = $(this).data('type');
		stockNum = parseInt($(this).data('stock_num'));
		if (type == 'only') {
    		var flag = false;
    		var params = [];
    		$('.sku').each(function(dom){
    		    var id = $(this).data('id'), name = $(this).data('name'), type = $(this).data('type'), num = $(this).data('num');
    		    console.log(name)
    		    var temp = {
                        'type':type,
                        'id':id,
                        'name':name,
                        'data':[]
                };
    		    var select_num = 0;
                $(this).find('button').each(function(){
                    if ($(this).hasClass('active')) {
                        temp['data'].push({'id':$(this).data('id'), 'name':$(this).data('name')});
                        select_num ++;
                    }
                });
                
                
                if (select_num == 0 && type == 'spec') {
                    flag = true;
                    motify.log('在' + name + '下选择一项');
                    return false;
                } else if (select_num > num) {
                    flag = true;
                    motify.log('属性选项：' + name + '下最多可选' + num + '项');
                    return false;
                }
                params.push(temp);
            });
    		if (flag) return false;
    		if (params.length) {
    			for (var pi in params) {
    				if (params[pi].type == 'spec') {
    					goodsCartKey += '_s_' + params[pi].id;
    				}
                    if (params[pi]['data'].length) {
                        for (var di in params[pi]['data']) {
                            goodsCartKey += '_v_' + params[pi]['data'][di].id;
                        }
                    }
    			}
    		}
    		var this_num = $('#num').val();
    		var this_index = null;
    		for (var i in goodsCart) {
    		    if (goodsCart[i].type == 'group') continue;
    			var old_goodsCartKey = goodsCart[i].goods_id;
    			
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
    			if (goodsCartKey == old_goodsCartKey) {
    				this_index = i;
    				break;
    			}
    		}
    		this_num = parseInt(this_num);
            if (this_num > stockNum && stockNum != -1) {
                motify.log('最多可以选择' + stockNum + '份');
                return false;
            }
    		if (this_index != null) {
    			goodsCart[this_index].num = parseInt(this_num) + parseInt(goodsCart[this_index].num);
    		} else {
    			goodsCart.push({
    				'goods_id':goods_id,
    				'num':this_num,
    				'type':'only',
    				'name':name,
    				'price':price,
    				'stockNum':stockNum,
    				'params':params});
    		}
    	} else {
    	    var tempData = [], is_no_select = false, tnum = 0, max = 0, id = $(this).data('id'), name = $(this).data('name'), price = parseFloat($(this).data('price'));
            var goodsCartKey = id;
            $('.changeFood').each(function(){
                max = parseInt($(this).data('num'));
                tnum = 0;
                $(this).find('.active').each(function(){
                    tempData.push({'goods_id':$(this).data('goods_id'), 'unit':$(this).data('unit'), 'name':$(this).data('name'), 'price':$(this).data('price')});
                    goodsCartKey += '_' + $(this).data('goods_id');
                    tnum ++;
                });
                if (tnum < max) is_no_select = true;
            });
            if (is_no_select) {
                motify.log('您有菜品未选择');
                return false;
            }
            var this_index = null;
            for (var i in goodsCart) {
                if (goodsCart[i].type == 'only') continue;
                
                var old_goodsCartKey = goodsCart[i].goods_id;
                if (goodsCart[i]['params'].length) {
                    for (var pi in goodsCart[i]['params']) {
                        old_goodsCartKey += '_' + goodsCart[i]['params'][pi].goods_id;
                    }
                }
                if (goodsCartKey == old_goodsCartKey) {
                    this_index = i;
                    break;
                }
            }
            
            var this_num = $('#num').val();
            if (this_index != null) {
                goodsCart[this_index].num += this_num;
            } else {
                goodsCart.push({
                    'goods_id':id,
                    'type':'group',
                    'index':goodsCartKey,
                    'num':this_num,
                    'name':name,
                    'price':price,
                    'stockNum':-1,
                    'params':tempData});
            }
    	}
		stringifyCart();
		location.href = $(this).data('href');
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
		if (nowShopCart[i] != null && nowShopCart[i].num > 0) {
			goodsCart[i] = nowShopCart[i];
		}
	}
}