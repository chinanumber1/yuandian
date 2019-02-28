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
/** laytpl-v1.1 */

;!function(){"use strict";var f,b={open:"{{",close:"}}"},c={exp:function(a){return new RegExp(a,"g")},query:function(a,c,e){var f=["#([\\s\\S])+?","([^{#}])*?"][a||0];return d((c||"")+b.open+f+b.close+(e||""))},escape:function(a){return String(a||"").replace(/&(?!#?[a-zA-Z0-9]+;)/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/'/g,"&#39;").replace(/"/g,"&quot;")},error:function(a,b){var c="Laytpl Error：";return"object"==typeof console&&console.error(c+a+"\n"+(b||"")),c+a}},d=c.exp,e=function(a){this.tpl=a};e.pt=e.prototype,e.pt.parse=function(a,e){var f=this,g=a,h=d("^"+b.open+"#",""),i=d(b.close+"$","");a=a.replace(/[\r\t\n]/g," ").replace(d(b.open+"#"),b.open+"# ").replace(d(b.close+"}"),"} "+b.close).replace(/\\/g,"\\\\").replace(/(?="|')/g,"\\").replace(c.query(),function(a){return a=a.replace(h,"").replace(i,""),'";'+a.replace(/\\/g,"")+'; view+="'}).replace(c.query(1),function(a){var c='"+(';return a.replace(/\s/g,"")===b.open+b.close?"":(a=a.replace(d(b.open+"|"+b.close),""),/^=/.test(a)&&(a=a.replace(/^=/,""),c='"+_escape_('),c+a.replace(/\\/g,"")+')+"')}),a='"use strict";var view = "'+a+'";return view;';try{return f.cache=a=new Function("d, _escape_",a),a(e,c.escape)}catch(j){return delete f.cache,c.error(j,g)}},e.pt.render=function(a,b){var e,d=this;return a?(e=d.cache?d.cache(a,c.escape):d.parse(d.tpl,a),b?(b(e),void 0):e):c.error("no data")},f=function(a){return"string"!=typeof a?c.error("Template not found"):new e(a)},f.config=function(a){a=a||{};for(var c in a)b[c]=a[c]},f.v="1.1","function"==typeof define?define(function(){return f}):"undefined"!=typeof exports?module.exports=f:window.laytpl=f}();

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
	//设置遮罩层的高度
	$(".Mask").css("height", $(document).height());
	
	//搜素
	$(".foodleft .search").click(function(){
		$(this).addClass("foodleftOn")
		var w = $(window).width();
		$(".foodleftOn").css("width", w - 3);
		$(".foodleftOn input").css("width", w - 100);
		$('.sr').focus();
	});

	$(".foodnav li a").click(function(){
		if($(".search").hasClass("foodleftOn")){
			$(".search").removeClass("foodleftOn");
		}
	});

	//背景单窗高度  
	var hi = $(window).height()
//	$(".foodnav").css("height", hi - 104);
	$(".foodnav").css("height", hi - 50);
	$(".foodright").css("height", hi - 50);
	$(".foodright dl").last().css("min-height", hi-50);
	
	/*左侧滚动条*/
	var myScroll2 = new IScroll('.foodnav', {click: true});
	$(".foodright").scroll(function(){
		var top = $(".foodright").scrollTop();
		var menu = $(".foodnav");
		var item = $(".foodright dl");
		var onid = "";
		item.each(function() {
			var n = $(this);
			var itemtop = $('.foodright-'+$(this).data('cat_id')).offset().top-$('.foodright').offset().top+$('.foodright').scrollTop();
			if (top > itemtop - 100) {
				onid = n.data('cat_id');
			}
		});
		var link = menu.find(".on");
		link.removeClass("on");
		menu.find("[data-cat_id="+onid+"]").addClass("on");
	});
	$(document).on('click','.foodnav a',function(){
		$('.foodright').animate({scrollTop:$('.foodright-'+$(this).data('cat_id')).offset().top-$('.foodright').offset().top+$('.foodright').scrollTop()},500) ;
	});
	
	

	$(document).on('click', '.Addsub a', function(){
		var this_num = $(this).siblings("input").val(), name = $(this).data('name'), price = parseFloat($(this).data('price')), goods_id = parseInt($(this).data('id')), goodsCartKey = $(this).data('index'),goods_extra_price = parseFloat($(this).data('extra_pay_price')),goods_extra_price_name = $(this).data('extra_price_name');
		var stockNum = parseInt($(this).data('stock_num'));
		if ($(this).attr('class') == 'jia') {
			this_num ++;
			goodsNumber ++;
			goodsCartMoney += price;
			if(goods_extra_price>0){
				goodsExtraPrice +=goods_extra_price;
			}
		} else {
			this_num --;
			goodsNumber --;
			goodsCartMoney -= price;
			if(goods_extra_price>0){
				goodsExtraPrice-=goods_extra_price;
			}
		}
		if (this_num > stockNum && stockNum != -1) {
		    motify.log('最多可以选择' + stockNum + '份');
		    return false;
		}
		goodsCartMoney = parseFloat(goodsCartMoney.toFixed(2));
		var this_index = null;
		for (var i in goodsCart) {
			var old_goodsCartKey = goodsCart[i].goods_id;
			if (goodsCart[i].type == 'group') {
			    if (goodsCart[i]['params'].length) {
                    for (var pi in goodsCart[i]['params']) {
                        old_goodsCartKey += '_' + goodsCart[i]['params'][pi].goods_id;
                    }
                }
			} else {
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
			}
			if (goodsCartKey == old_goodsCartKey) {
				this_index = i;
				break;
			}
		}
		
		if (this_index != null) {
			goodsCart[this_index].num = this_num;
		} else {
			if(goods_extra_price>0){
				goodsCart.push({
					'goods_id':goods_id,
					'num':this_num,
					'type':'only',
					'name':name,
					'price':price,
					'stockNum':stockNum,
					'extra_price':goods_extra_price,
					'extra_price_name':goods_extra_price_name,
					'params':''});
			}else{
				goodsCart.push({
					'goods_id':goods_id,
					'num':this_num,
                    'type':'only',
					'name':name,
					'price':price,
                    'stockNum':stockNum,
					'params':''});
			}
		}
		
		$('.goods_' + goodsCartKey).find("input").val(this_num);
		if (this_num > 0) {
			$(this).siblings().show();
			if (!$('.Cart_list ul').find('.goods_' + goodsCartKey).length) {
				if(goods_extra_price>0){
					price +='+'+goods_extra_price+goods_extra_price_name;
				}
				var cart_goods_html = '';
				cart_goods_html += '<li class="clr goods_' + goodsCartKey + '">';
				cart_goods_html += '<div class="Clist_left">';
				cart_goods_html += '<h2>' + name + '</h2>';
//				cart_goods_html += '<span>(大份、微辣)</span>';
				cart_goods_html += '</div>';
				cart_goods_html += '<div class="Clist_right">';
				cart_goods_html += '<div class="MenuPrice"><i>￥</i>' + price + '</div>';
				cart_goods_html += '<div class="Addsub">';
				cart_goods_html += '<a href="javascript:void(0)" class="jian" data-stock_num="' + stockNum + '" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"'+ 'data-extra_pay_price="' + goods_extra_price +'" data-extra_price_name="' + goods_extra_price_name + '"></a>';
				cart_goods_html += '<input type="text" value="' + this_num + '" readOnly="true" class="num">';
				cart_goods_html += '<a href="javascript:void(0)" class="jia" data-stock_num="' + stockNum + '" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"'+ 'data-extra_pay_price="' + goods_extra_price +'" data-extra_price_name="' + goods_extra_price_name + '"></a>';
				cart_goods_html += '</div>';
				cart_goods_html += '</div>';
				cart_goods_html += '</li>';
				$('.Cart_list ul').append(cart_goods_html);
			}
		} else {
			$('.goods_' + goodsCartKey).find('.jia').siblings().hide();
			$('.Cart_list ul').find('.goods_' + goodsCartKey).remove();
			if (!$('.Cart_list ul').find('li').length) {
				$(".Cart").slideUp();
				$(".Mask").hide();
			}
		}

		if (goodsNumber > 0) {
			$(".floor").addClass("floorOn");
			$(".qty").show(500).text(goodsNumber);
			if(goodsExtraPrice>0){
				$('#total_price').text(goodsCartMoney+'+'+goodsExtraPrice+goods_extra_price_name);
			}else{
				$('#total_price').text(goodsCartMoney);
			}
			
		} else {
			goodsCart = [];
			$(".floor").removeClass("floorOn");
			$(".qty").hide(500);
			$('#total_price').text(0);
		}
		stringifyCart();
	});
	
	//清空购物车
	$(".Cart_top span").click(function(){
		$(".Cart_list").find("li").remove();
		$(".floor").removeClass("floorOn");
		$(".qty").hide(500);
		$('#total_price').text(0);
		$(".Cart").slideUp();
		$(".Mask").hide();
		$('.foodright .Addsub').find('input').val(0);
		$('.foodright .Addsub').find('.jia').siblings().hide();
		goodsNumber = 0;
		goodsCartMoney = 0;
		goodsCart = [];
		stringifyCart();
	});
	
	//购物效果
	$(".trolley").toggle(function(){
		$(".Cart").slideDown();
		$(".Mask").show();
	},function(){
		$(".Cart").slideUp();
		$(".Mask").hide()
	});
	
	//弹出规格
	$(".Speci").click(function(){
		$(this).parents(".food_right").siblings(".TcancelT").slideDown();
		$(".Mask").show();
	});
	//关闭规格弹出
	$(".gb").click(function(){
		$(this).parents(".TcancelT").slideUp();
		$(".Mask").hide();
	});
	
	//规格中选项的选择
	$(document).on('click', '.fications li', function(){
		var father_obj = $(this).parents('.fications');
		var type = father_obj.data('type'), id = father_obj.data('id'), name = father_obj.data('name'), num = father_obj.data('num');
		var this_id = $(this).data('id'), this_name = $(this).data('name'), goods_id = parseInt($(this).data('goods_id'));
		if (num == 1) {
			$(this).addClass('on').siblings('li').removeClass('on');
		} else {
			$(this).toggleClass("on");
			if (father_obj.find('.on').length > num) {
				$(this).removeClass("on");
				motify.log('最多可以选择' + num + '个');
				return false;
			}
		}
		var select_html = '已选：';
		var spec_ids = [];
		$(this).parents('.TcancelT').find('.fications').each(function(dom){
			$(this).find('li').each(function(){
				if ($(this).hasClass('on')) {
					select_html += '<span>' + $(this).data('name') + '</span>';
					if ($(this).data('type') == 'spec') {
						spec_ids.push($(this).data('id'))
					}
				}
			});
		});

		$(this).parents(".TcancelT_zh").siblings(".Selected").html(select_html);
		if (type == 'spec' && spec_ids.length > 0) {
			var ALL_GOODS = $.parseJSON(all_goods);
			console.log(ALL_GOODS)
			var price = 0, stockNum = 0;
			if (typeof(ALL_GOODS[goods_id][spec_ids.join('_')]) != 'undefined') {
			    var tData = ALL_GOODS[goods_id][spec_ids.join('_')];
				price = ALL_GOODS[goods_id][spec_ids.join('_')]['price'];
				stockNum = ALL_GOODS[goods_id][spec_ids.join('_')]['stock_num'];
				if (tData.properties.length > 0) {
				    for (var i in tData.properties) {
				        $('#properties_' + tData.properties[i].id).data('num', tData.properties[i].num);
				    }
				}
				
				
			}
			$(this).parents('.TcancelT').find('.TcancelT_topL .price').html('<i>￥</i>' + price);
			if (stockNum >= 0 && stockNum < 10) {
			    $(this).parents('.TcancelT').find('.TcancelT_topL .stock').html('剩余：' + stockNum);
			} else {
			    $(this).parents('.TcancelT').find('.TcancelT_topL .stock').html('');
			}
			
			$(this).parents('.TcancelT').find('.join').data('price', price);
			$(this).parents('.TcancelT').find('.join').data('stock_num', stockNum);
		}
	});
	
	
	//提交规格选中的
	$(document).on('click', '.TcancelT .join', function(){
		var goodsCartKey = $(this).data('goods_id'), goods_id = parseInt($(this).data('goods_id')), name = $(this).data('name'), price = parseFloat($(this).data('price'));
		var stockNum = parseInt($(this).data('stock_num'));
		var flag = false;
		var params = [];
		$(this).parents('.TcancelT').find('.fications').each(function(dom){
			var id = $(this).data('id'), name = $(this).data('name'), type = $(this).data('type'), num = $(this).data('num');
			var temp = {
					'type':type,
					'id':id,
					'name':name,
					'data':[]
			};
			var select_num = 0;
			$(this).find('li').each(function(){
				if ($(this).hasClass('on')) {
					temp['data'].push({'id':$(this).data('id'), 'name':$(this).data('name')});
					select_num ++;
				}
			});
			if (select_num == 0 && type == 'spec') {
				flag = true;
				motify.log('必须在' + name + '下选择一项');
				return false;
			} else if (select_num > num) {
				flag = true;
				motify.log('必须在' + name + '下最多可选' + num + '项');
				return false;
			}
			params.push(temp);
//			details.push(goodsDetail);
		});
		if (flag) return false;
		var names_str = '';
		if (params.length) {
			for (var pi in params) {
				if (params[pi].type == 'spec') {
					goodsCartKey += '_s_' + params[pi].id;
				}
				if (params[pi]['data'].length) {
					for (var di in params[pi]['data']) {
						goodsCartKey += '_v_' + params[pi]['data'][di].id;
						if (names_str.length > 0) {
							names_str += ',' + params[pi]['data'][di].name
						} else {
							names_str += params[pi]['data'][di].name;
						}
					}
				}
			}
		}
		
		if ($('.Cart_list ul').find('.goods_' + goodsCartKey).length) {
			var this_num = parseInt($('.goods_' + goodsCartKey).find("input").val());
		} else {
			var this_num = 0;
		}
		
		this_num ++;
		goodsNumber ++;
		goodsCartMoney += price;
		
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
		if (this_num > stockNum && stockNum != -1) {
            motify.log('最多可以选择' + stockNum + '份');
            return false;
		}
		
		if (this_index != null) {
			goodsCart[this_index].num = this_num;
		} else {
			goodsCart.push({
				'goods_id':goods_id,
				'type':'only',
				'num':this_num,
				'name':name,
				'price':price,
                'stockNum':stockNum,
				'params':params});
		}
		


		
		$('.goods_' + goodsCartKey).find("input").val(this_num);
		if (this_num > 0) {
			if (!$('.Cart_list ul').find('.goods_' + goodsCartKey).length) {
				var cart_goods_html = '';
				cart_goods_html += '<li class="clr goods_' + goodsCartKey + '">';
				cart_goods_html += '<div class="Clist_left">';
				cart_goods_html += '<h2>' + name + '</h2>';
				cart_goods_html += '<span>' + names_str + '</span>';
				cart_goods_html += '</div>';
				cart_goods_html += '<div class="Clist_right">';
				cart_goods_html += '<div class="MenuPrice"><i>￥</i>' + price + '</div>';
				cart_goods_html += '<div class="Addsub">';
				cart_goods_html += '<a href="javascript:void(0)" class="jian" data-stock_num="' + stockNum + '" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"></a>';
				cart_goods_html += '<input type="text" value="' + this_num + '" readOnly="true" class="num">';
				cart_goods_html += '<a href="javascript:void(0)" class="jia" data-stock_num="' + stockNum + '" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"></a>';
				cart_goods_html += '</div>';
				cart_goods_html += '</div>';
				cart_goods_html += '</li>';
				$('.Cart_list ul').append(cart_goods_html);
			}
		} 
		if (goodsNumber > 0) {
			$(".floor").addClass("floorOn");
			$(".qty").show(500).text(goodsNumber);
			$('#total_price').text(goodsCartMoney);
		}
		stringifyCart();
		$(this).parents(".TcancelT").slideUp();
		$(".Mask").hide();
	});
	init_goods_menu();
	$(document).on('click', '.next', function(){
		if (goodsNumber < 1) {
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
	
	   //团购套餐点击查看产品详情
    $(document).on('click', '.packageSpeci', function() {
        var id = $(this).data('id');
        $.post('wap.php?c=Foodshop&a=foodshop_getgroup_detail',{'group_id':id, 'store_id':store_id}, function(response){
            if (response.errcode == 0) {
                var data = response.data;
                laytpl($('#groupDetailTpl').html()).render(data, function(html){
                    $('.setmenu').html(html).slideDown();
                });
                //团购套餐
                
                $(".setmenu_list dd:last-child").css("border-bottom", "#f1f1f1 1px solid");
                $(".setmenu_list .condition").each(function(){
                    $(this).height($(this).siblings(".set_list").height());
                })
                $(".Mask").show();
            } else {
                motify.log(response.msg);
            }
        }, 'json');
    });

    //选中团购菜品
    $(document).on('click', '.set_list li', function() {
        var maxNum = $(this).parents('ul').data('num');
        
        if (maxNum == 1) {
            $(this).addClass("on").siblings('li').removeClass("on");
        } else {
            if ($(this).is('.on')) {
                $(this).removeClass("on");
            } else {
                if ($(this).parents('ul').find('.on').size() >= maxNum) {
                    motify.log('您只能选择' + maxNum + '项');
                    return false;
                } else {
                    $(this).addClass("on");
                }
            }
        }
    });
    
    $(document).on('click', '.setmenu .join', function() {
        var tempData = [], is_no_select = false, tnum = 0, max = 0, id = $(this).data('id'), name = $(this).data('name'), price = parseFloat($(this).data('price'));
        var goodsCartKey = id;
        $(this).parents('.setmenu').find('.setmenu_list .set_list ul').each(function(){
            max = parseInt($(this).data('num'));
            tnum = 0;
            $(this).find('.on').each(function(){
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
        
        var num = 1;
        if (this_index != null) {
            goodsCart[this_index].num ++;
            num = goodsCart[this_index].num;
        } else {
            goodsCart.push({
                'goods_id':id,
                'type':'group',
                'index':goodsCartKey,
                'num':1,
                'name':name,
                'price':price,
                'stockNum':-1,//套餐默认是无限量(暂定)
                'params':tempData});
        }
        
        $('.goods_' + goodsCartKey).find("input").val(num);
        
        if (!$('.Cart_list ul').find('.goods_' + goodsCartKey).length) {
            var cart_goods_html = '';
            cart_goods_html += '<li class="clr goods_' + goodsCartKey + '">';
            cart_goods_html += '<div class="Clist_left">';
            cart_goods_html += '<h2>' + name + '</h2>';
//            cart_goods_html += '<span>' + names_str + '</span>';
            cart_goods_html += '</div>';
            cart_goods_html += '<div class="Clist_right">';
            cart_goods_html += '<div class="MenuPrice"><i>￥</i>' + price + '</div>';
            cart_goods_html += '<div class="Addsub">';
            cart_goods_html += '<a href="javascript:void(0)" class="jian" data-stock_num="-1" data-price="' + price + '" data-id="' + id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"></a>';
            cart_goods_html += '<input type="text" value="' + num + '" readOnly="true" class="num">';
            cart_goods_html += '<a href="javascript:void(0)" class="jia" data-stock_num="-1" data-price="' + price + '" data-id="' + id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"></a>';
            cart_goods_html += '</div>';
            cart_goods_html += '</div>';
            cart_goods_html += '</li>';
            $('.Cart_list ul').append(cart_goods_html);
        }

        $(".TcancelT,.setmenu,setmenu_n").slideUp();
        $(".spot").fadeOut();
        $(".Mask").hide();
        goodsNumber ++;
        goodsCartMoney += price;
        if (goodsNumber > 0) {
            $(".floor").addClass("floorOn");
            $(".qty").show(500).text(goodsNumber);
            $('#total_price').text(goodsCartMoney);
        }
        stringifyCart();
    });
    
    $(document).on('click', '.Mask,.setmenu .gb', function() {
        $(".TcancelT,.setmenu,setmenu_n").slideUp();
        $(".spot").fadeOut();
        $(".Mask").hide();
    });
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
			cart_goods_html += '<li class="clr goods_' + goodsCartKey + '">';
			cart_goods_html += '<div class="Clist_left">';
			cart_goods_html += '<h2>' + nowShopCart[i].name + '</h2>';
			if (detail_name.length) {
				cart_goods_html += '<span>' + detail_name + '</span>';
			}
			
			cart_goods_html += '</div>';
			cart_goods_html += '<div class="Clist_right">';
			cart_goods_html += '<div class="MenuPrice"><i>￥</i>' + nowShopCart[i].price +tmp_extra_price+ '</div>';
			cart_goods_html += '<div class="Addsub">';
			cart_goods_html += '<a href="javascript:void(0)" class="jian" data-stock_num="' + nowShopCart[i].stockNum + '" data-price="' + nowShopCart[i].price + '" data-id="' + nowShopCart[i].goods_id + '" data-index="' + goodsCartKey + '" data-name="' + nowShopCart[i].name +'"'+ 'data-extra_pay_price="' + nowShopCart[i].extra_price +'" data-extra_price_name="' + nowShopCart[i].extra_price_name +'"></a>';
			cart_goods_html += '<input type="text" value="' + nowShopCart[i].num + '" readOnly="true" class="num">';
			cart_goods_html += '<a href="javascript:void(0)" class="jia" data-stock_num="' + nowShopCart[i].stockNum + '" data-price="' + nowShopCart[i].price + '" data-id="' + nowShopCart[i].goods_id + '" data-index="' + goodsCartKey + '" data-name="' + nowShopCart[i].name+'"' + 'data-extra_pay_price="' + nowShopCart[i].extra_price +'" data-extra_price_name="' + nowShopCart[i].extra_price_name+'"></a>';
			cart_goods_html += '</div>';
			cart_goods_html += '</div>';
			cart_goods_html += '</li>';
			
			$('.goods_' + goodsCartKey).find("input").val(parseInt(nowShopCart[i].num)).show();
			$('.goods_' + goodsCartKey).find(".jian").show();
			goodsNumber += parseInt(nowShopCart[i].num);
			goodsCartMoney += parseFloat(nowShopCart[i].price) * parseInt(nowShopCart[i].num);
			if(nowShopCart[i].extra_price>0&&open_extra_price==1){
				goodsExtraPrice+=parseFloat(nowShopCart[i].extra_price) * parseInt(nowShopCart[i].num);
				var extra_price_name = nowShopCart[i].extra_price_name;
			}
			
			goodsCart[i] = nowShopCart[i];
		}
	}
	$('.Cart_list ul').append(cart_goods_html);
	if (goodsNumber > 0) {
		$(".floor").addClass("floorOn");
		$(".qty").show(500).text(goodsNumber);
		if(goodsExtraPrice>0){
			$('#total_price').text(goodsCartMoney+'+'+goodsExtraPrice+extra_price_name);
		}else{			
			$('#total_price').text(goodsCartMoney);
		}
	}
	
}
