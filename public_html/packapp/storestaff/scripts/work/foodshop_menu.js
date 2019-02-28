var datas = [], this_goods_data = null, nowShopCart = null;
var goodsCart = [], goodsNumber = 0, goodsCartMoney = 0, goodsExtraPrice = 0, cookie_index = 'foodshop_goods_' + urlParam.order_id, old_total_num = 0, isShow = urlParam.isShow; 
$(document).ready(function(){
	$('.public').html('<div class="return link-url" data-url-type="openLeftWindow" data-url="back"></div><div class="content">点菜</div>');
	
	var hi = $(window).height();
	//背景单窗高度  
	$(".Mask,.Maskcat,.Maskmenu").css("height", hi);
	$(".foodnav").css("height", hi - 94);
	$(".foodright").css("height", hi - 94);
	
	
	
	//初始化菜品分类与菜品信息
	var html_sort = '';
	html_sort += '{{# var loopNum = 0 }}';
	html_sort += '{{# for(var i in d){ }}';
	html_sort += '{{# if(loopNum == 0){ }}';
	html_sort += '<li><a href="javascript:void(0)" data-cat_id="{{d[i].sort_id}}" class="on">{{d[i].sort_name}}</a></li>';
	html_sort += '{{# } else { }}';
	html_sort += '<li><a href="javascript:void(0)" data-cat_id="{{d[i].sort_id}}">{{d[i].sort_name}}</a></li>';
	html_sort += '{{# } }}';
	html_sort += '{{# loopNum++ }}';
	html_sort += '{{# } }}';
	common.http('Storestaff&a=foodshop_goods',{'order_id':urlParam.order_id}, function(data){
		if (data.goods_list != null) {
			laytpl($('#cartsTpl').html()).render(data.goods_list, function(html){
				$('.cease .dishes ul').html(html);
			});
			old_total_num = data.total_num;
			$('.al_point .point_n h2').html(old_total_num);
			$('.cease .within .cease_top h2').html('已点菜品(' + old_total_num + ')');
			var footer_html = '<div class="total">订单总价<span>￥' + data.total_price + '</span> 已付定金<span>￥' + data.book_price + '</span></div>';
			if (data.unpaid_price > 0) {
				footer_html += '<div class="need">还需支付：<span>￥' + parseFloat(data.unpaid_price.toFixed(2)) + '</span></div>';
			}
			$('.cease .within .tol_price .tol_n').html(footer_html);
		} else {
			$('.al_point').hide();
		}
		//左边分类
		laytpl(html_sort).render(data.lists, function(html){
			$('.foodleft .foodnav ul').html(html);
		});
		//右边菜品
		laytpl($('#listTpl').html()).render(data.lists, function(html){
			$('.foodright').html(html);
		});

		/*左侧滚动条*/
		$(".foodright").scroll(function() {
			var top = $(".foodright").scrollTop();
			var menu = $(".foodnav");
			var item = $(".foodright dl");
			var onid = "";
			item.each(function() {
				var n = $(this);
				var itemtop = $('.foodright-' + $(this).data('cat_id')).offset().top - $('.foodright').offset().top + $('.foodright').scrollTop();
				if (top > itemtop - 100) {
					onid = n.data('cat_id');
				}
			});
			var link = menu.find(".on");
			link.removeClass("on");
			menu.find("[data-cat_id=" + onid + "]").addClass("on");
		});
		$(document).on('click', '.foodnav a', function() {
			$('.foodright').animate({scrollTop : $('.foodright-' + $(this).data('cat_id')).offset().top - $('.foodright').offset().top + $('.foodright').scrollTop()}, 500);
		});
		$(".foodright dl").last().css({"min-height" : hi - 154, "padding-bottom" : "60px"});
		
		
		nowShopCart = $.parseJSON(window.sessionStorage.getItem(cookie_index));
		if (nowShopCart == null) {
			nowShopCart = data.temp_list;
		}
		console.log(nowShopCart);
		initGoodsMenu();
		if (isShow == 1 && nowShopCart != null && nowShopCart.length > 0) {
			$(".Cart").slideDown();
			$(".Maskcat").show();
		}
	});
	
	//保存到购物车
	$('.next').click(function(){
		common.http('Storestaff&a=foodshop_save_order',{'order_id':urlParam.order_id, 'cart':goodsCart, 'noTip':true}, function(data){
			motify.log(data);
			window.sessionStorage.removeItem(cookie_index);
			location.reload();
		});
	});

	//数量加减
	$(document).on('click', '.Clist_right .Addsub a, .food_right .Addsub a', function(){
		var this_num = $(this).siblings("input").val(), name = $(this).data('name'), price = parseFloat($(this).data('price')), goods_id = parseInt($(this).data('id')), goodsCartKey = $(this).data('index')/*,goods_extra_price = parseFloat($(this).data('extra_pay_price')),goods_extra_price_name = $(this).data('extra_price_name')*/;
		var uid = parseInt($(this).data('uid')), extra_price = parseFloat($(this).data('extra_price')), unit = $(this).data('unit');
		var stock = -1;
		if ($(this).attr('class') == 'jia') {
			this_num ++;
			goodsNumber ++;
			goodsCartMoney += price;
		} else {
			this_num --;
			goodsNumber --;
			goodsCartMoney -= price;
		}
		goodsCartMoney = parseFloat(goodsCartMoney.toFixed(2));
		var this_index = null;
		for (var i in goodsCart) {
			var old_goodsCartKey = goodsCart[i].uid + '_' + goodsCart[i].productId;
			if (goodsCart[i]['productParam'] && goodsCart[i]['productParam'].length) {
				for (var pi in goodsCart[i]['productParam']) {
					if (goodsCart[i]['productParam'][pi].type == 'spec') {
						old_goodsCartKey += '_s_' + goodsCart[i]['productParam'][pi].id;
					}
					if (goodsCart[i]['productParam'][pi]['data'].length) {
						for (var di in goodsCart[i]['productParam'][pi]['data']) {
							old_goodsCartKey += '_v_' + goodsCart[i]['productParam'][pi]['data'][di].id;
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
			goodsCart[this_index].count = this_num;
		} else {
            var params = {
                    'uid':uid,
                    'productKey':goodsCartKey,
                    'productId':goods_id,
                    'productName':name,
                    'productUnit':unit,
                    'productPrice':price,
                    'productStock':stock,
                    'productLabel':'',
                    'isTmpOrder':true,
                    'tmpOrderId':0,
                    'count':this_num,
                    'extra_price':extra_price,
                    'note':'',
                    'productParam':''
            };
            goodsCart.push(params);
		}
		
		$('.food_' + goodsCartKey).find("input").val(this_num);
		
		if (this_num > 0) {
			$(this).siblings().show();
			if (!$('.Cart_list ul').find('.food_' + goodsCartKey).length) {
				var cart_goods_html = '';
				cart_goods_html += '<li class="clr food_' + goodsCartKey + '">';
				cart_goods_html += '<div class="Clist_left">';
				cart_goods_html += '<h2>' + name + '</h2>';
				cart_goods_html += '</div>';
				cart_goods_html += '<div class="Clist_right">';
				cart_goods_html += '<div class="MenuPrice"><i>￥</i>' + price + '</div>';
				cart_goods_html += '<div class="Addsub">';
				cart_goods_html += '<a href="javascript:void(0)" class="jian" data-unit="' + unit + '" data-uid="' + uid + '" data-stock="' + stock + '" data-extra_price="' + extra_price + '" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"></a>';
				cart_goods_html += '<input type="text" value="' + this_num + '" readOnly="true" class="num">';
				cart_goods_html += '<a href="javascript:void(0)" class="jia" data-unit="' + unit + '" data-uid="' + uid + '" data-stock="' + stock + '" data-extra_price="' + extra_price + '" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"></a>';
				cart_goods_html += '</div>';
				cart_goods_html += '</div>';
				cart_goods_html += '</li>';
				if (!$('.Cart_list ul').find('#food_' + uid).length) {
				    $('.Cart_list ul').append('<dl id="food_' + uid + '"><li style="color: #127b3e; padding: 5px;">店员</li>' + cart_goods_html + '</dl>');
                } else {
                    $('.Cart_list ul #food_' + uid).append(cart_goods_html);
                }
			}
		} else {
			$('.food_' + goodsCartKey).find('.jia').siblings().hide();
			$('.Cart_list ul').find('.food_' + goodsCartKey).remove();
			
            if (!$('.Cart_list ul #food_' + uid).find('li').length) {
                $('#food_' + uid).remove();
            }
            if (!$('.Cart_list ul').find('li').length) {
                $(".Cart").slideUp();
                $(".Maskcat").hide();
            }
		}
		if (goodsNumber > 0) {
			$(".floor").addClass("floorOn");
			$(".qty").show(500).text(goodsNumber);
			$('#total_price').text(goodsCartMoney);
		} else {
			goodsCart = [];
			$(".qty").hide(500);
			$('#total_price').text(0);
			clearData();
		}
		stringifyCart();
	});


	//清空购物车
	$(".Cart_top span").click(function() {
		$(".Cart_list").find("li").remove();
		$(".qty").hide(500);
		$('.prix i').text(0);
		$(".Cart").slideUp();
		$(".Maskcat").hide();
		$('.foodright .Addsub').find('input').val(0);
		$('.foodright .Addsub').find('.jia').siblings().hide();
		goodsNumber = 0;
		goodsCartMoney = 0;
		goodsCart = [];
		stringifyCart();
		clearData();
	});

	//有规格属性的菜品
	$(document).on('click', '.Speci', function() {
		this_goods_data = null;
		for (var i in datas) {
			if (datas[i].goods_id == $(this).data('id')) {
				this_goods_data = datas[i];
			}
		}
		if (this_goods_data != null) {
			laytpl($('#goodsDetalTpl').html()).render(this_goods_data, function(html){
				$('.TcancelT').html(html).slideDown();
				$(".Mask").show();
			});
		}
	});
	//添加备注
    $('body').off('click','.addBei').on('click','.addBei',function(e){
        $('#note').val($(this).data('note'));
        $('#detail_id').val($(this).data('id'));
        if ($(this).data('note').length > 0) {
            $('.remarkContent .itemTop').html('修改备注');
        } else {
            $('.remarkContent .itemTop').html('添加备注');
        }
        $('.remarkBg').show();
    });
    $('.que').click(function(e){
        var note = $('#note').val(), detail_id = $('#detail_id').val();
        if (note.length < 1) {
            motify.log('请输入备注');
            return false;
        }
        common.http('Storestaff&a=foodshop_change_order_note',{'order_id':urlParam.order_id, 'detail_id':detail_id, 'note':note, 'noTip':true}, function(data){
            $('#addRemark_' + detail_id).html('(' + note + ')');
            $('#addBei_' + detail_id).html('修改备注');
            $('#addBei_' + detail_id).data('note', note);
            $('.remarkBg').hide();
        });
    });
    $('.qu').click(function(e){
        $('.remarkBg').hide();
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
			var this_goods = this_goods_data.list;
			var price = 0;
			if (typeof(this_goods[spec_ids.join('_')]) != 'undefined') {
				price = this_goods[spec_ids.join('_')]['price'];
			}
			$(this).parents('.TcancelT').find('.TcancelT_topL span').html('<i>￥</i>' + price);
			$(this).parents('.TcancelT').find('.join').data('price', price);
		}
	});
	
	
	//提交规格选中的
	$(document).on('click', '.TcancelT .join', function(){
		var goodsCartKey = $(this).data('goods_id'), goods_id = parseInt($(this).data('goods_id')), name = $(this).data('name'), price = parseFloat($(this).data('price'));
		var uid = parseInt($(this).data('uid')), extra_price = parseFloat($(this).data('extra_price')), unit = $(this).data('unit');
        var stock = -1;
		var flag = false;
		var params = [];
		goodsCartKey = uid + '_' + goodsCartKey;
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
			if (select_num == 0) {
				flag = true;
				motify.log('必须在' + name + '下选择一项');
				return false;
			} else if (select_num > num) {
				flag = true;
				motify.log('必须在' + name + '下最多可选' + num + '项');
				return false;
			}
			params.push(temp);
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
		
		goodsNumber ++;
		goodsCartMoney += price;
		var this_index = null;
		for (var i in goodsCart) {
            var old_goodsCartKey = goodsCart[i].uid + '_' + goodsCart[i].productId;
			if (goodsCart[i]['productParam'].length) {
				for (var pi in goodsCart[i]['productParam']) {
					if (goodsCart[i]['productParam'][pi].type == 'spec') {
						old_goodsCartKey += '_s_' + goodsCart[i]['productParam'][pi].id;
					}
					if (goodsCart[i]['productParam'][pi]['data'].length) {
						for (var di in goodsCart[i]['productParam'][pi]['data']) {
							old_goodsCartKey += '_v_' + goodsCart[i]['productParam'][pi]['data'][di].id;
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
		    this_num = goodsCart[this_index].count;
		    this_num ++;
			goodsCart[this_index].count = this_num;
		} else {
		    this_num = 1;
            var params1 = {
                    'uid':0,
                    'productKey':goodsCartKey,
                    'productId':goods_id,
                    'productName':name,
                    'productUnit':unit,
                    'productPrice':price,
                    'productStock':stock,
                    'productLabel':names_str,
                    'isTmpOrder':true,
                    'tmpOrderId':0,
                    'count':this_num,
                    'extra_price':extra_price,
                    'note':'',
                    'productParam':params
            };
            goodsCart.push(params1);
		}
		
		$('.food_' + goodsCartKey).find("input").val(this_num);
		if (this_num > 0) {
			if (!$('.Cart_list ul').find('.food_' + goodsCartKey).length) {
				var cart_goods_html = '';
                cart_goods_html += '<li class="clr food_' + goodsCartKey + '">';
                cart_goods_html += '<div class="Clist_left">';
                cart_goods_html += '<h2>' + name + '</h2>';
                cart_goods_html += '<span>' + names_str + '</span>';
                cart_goods_html += '</div>';
                cart_goods_html += '<div class="Clist_right">';
                cart_goods_html += '<div class="MenuPrice"><i>￥</i>' + price + '</div>';
                cart_goods_html += '<div class="Addsub">';
                cart_goods_html += '<a href="javascript:void(0)" class="jian" data-unit="' + unit + '" data-uid="' + uid + '" data-stock="' + stock + '" data-extra_price="' + extra_price + '" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"></a>';
                cart_goods_html += '<input type="text" value="' + this_num + '" readOnly="true" class="num">';
                cart_goods_html += '<a href="javascript:void(0)" class="jia" data-unit="' + unit + '" data-uid="' + uid + '" data-stock="' + stock + '" data-extra_price="' + extra_price + '" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"></a>';
                cart_goods_html += '</div>';
                cart_goods_html += '</div>';
                cart_goods_html += '</li>';
                if (!$('.Cart_list ul').find('#food_' + uid).length) {
                    $('.Cart_list ul').append('<dl id="food_' + uid + '"><li style="color: #127b3e; padding: 5px;">店员</li>' + cart_goods_html + '</dl>');
                } else {
                    $('.Cart_list ul #food_' + uid).append(cart_goods_html);
                }
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

	//购物效果
	$(".Maskcat,.trolley").click(function() {
		if ($(".Cart").is(":hidden")) {
			$(".Cart").slideDown();
			$(".Maskcat").show();
		} else {
			$(".Cart").slideUp();
			$(".Maskcat").hide();
		}
	});
	
	//已点菜品查看
	$(".al_point").click(function() {
		$(".Mask").show();
		$(".spot,.mask").fadeIn();
		$(".dishes").height($(".spot").height() - 140);
	});
	
	//已点菜品数量加减
	var isSave = false;
	$(document).on('click', '.cart_goods a', function(){
		if (isSave) return false;
		isSave = true;
		var this_num = $(this).siblings("input").val(), detail_id = parseInt($(this).data('id')), obj = $(this);
		if ($(this).attr('class') == 'jia') {
			this_num ++;
			old_total_num ++;
		} else {
			this_num --;
			old_total_num --;
		}
		common.http('Storestaff&a=foodshop_change_order',{'order_id':urlParam.order_id, 'num':this_num, 'detail_id':detail_id, 'noTip':true}, function(data){
			isSave = false;
			var footer_html = '<div class="total">订单总价<span>￥' + data.total_price + '</span> 已付定金<span>￥' + data.book_price + '</span></div>';
			if (data.unpaid_price > 0) {
				footer_html += '<div class="need">还需支付：<span>￥' + parseFloat(data.unpaid_price.toFixed(2)) + '</span></div>';
			}
			$('.cease .within .tol_price .tol_n').html(footer_html);
			if (this_num < 1) {
				obj.parents('li').remove();
			} else {
				obj.siblings("input").val(this_num)
			}
			$('.al_point .point_n h2').html(old_total_num);
			$('.cease .within .cease_top h2').html('已点菜品(' + old_total_num + ')');
		}, function(res){
			isSave = false;
			motify.log(res.errorMsg);
		});
	});
	
	$(document).on('click', '.Mask,.TcancelT .gb,.setmenu .gb,.spot .del', function() {
		$(".TcancelT,.setmenu,setmenu_n").slideUp();
		$(".Mask").hide();
		$(".spot").fadeOut();
	});

	
	//团购套餐点击查看产品详情
	$(document).on('click', '.packageSpeci', function() {
		var id = $(this).data('id');
		common.http('Storestaff&a=foodshop_getgroup_detail',{'group_id':id, 'noTip':true}, function(data){
			laytpl($('#groupDetailTpl').html()).render(data, function(html){
				$('.setmenu').html(html).slideDown();
			});

			//团购套餐
			$(".setmenu_list dd:last-child").css("border-bottom", "#f1f1f1 1px solid");
			$(".setmenu_list .condition").each(function(){
				$(this).height($(this).siblings(".set_list").height());
			})
			$(".Mask").show();
		});
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
		var tempData = [], is_no_select = false, tnum = 0, max = 0, id = $(this).data('id');
		$(this).parents('.setmenu').find('.setmenu_list .set_list ul').each(function(){
			max = parseInt($(this).data('num'));
			tnum = 0;
			$(this).find('.on').each(function(){
				tempData.push({'uid':0, 'productKey':$(this).data('goods_id'), 'productUnit':'份', 'note':'', 'productId':$(this).data('goods_id'), 'count':1, 'productName':$(this).data('name'), 'productPrice':$(this).data('price'), 'productStock':-1, 'extra_price':0, 'params':''});
				tnum ++;
			});
			if (tnum < max) is_no_select = true;
		});
		if (is_no_select) {
			motify.log('您有菜品未选择');
			return false;
		}
		
		common.http('Storestaff&a=foodshop_save_order',{'order_id':urlParam.order_id, 'cart':tempData, 'package_id':id, 'noTip':true}, function(data){
			location.reload();
		});
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
	window.sessionStorage.setItem(cookie_index, JSON.stringify(cookieProductCart));
}

function initGoodsMenu()
{
    goodsCart = [];
    var cart_goods_html = '';
    for (var i in nowShopCart) {
        
        if (nowShopCart[i].foods.length > 0) {
            
        }
        cart_goods_html += '<dl id="food_' + nowShopCart[i].uid + '"><li style="color: #127b3e; padding: 5px;">' + nowShopCart[i].nickname + '</li>';
        
        for (var ig in nowShopCart[i].foods) {
            var goodsData = nowShopCart[i].foods[ig];
            if (goodsData.ishide == 1) {
                goodsNumber += parseInt(goodsData.num);
                goodsCartMoney += parseFloat(goodsData.price) * parseInt(goodsData.num);
                continue;
            }
            if (goodsData != null && goodsData.num > 0) {
                var detail_name = '', goodsCartKey = nowShopCart[i].uid + '_' + goodsData.goods_id;
                if (goodsData['params'].length) {
                    for (var pi in goodsData['params']) {
                        if (goodsData['params'][pi].type == 'spec') {
                            goodsCartKey += '_s_' + goodsData['params'][pi].id;
                        }
                        if (goodsData['params'][pi]['data'].length) {
                            for (var di in goodsData['params'][pi]['data']) {
                                goodsCartKey += '_v_' + goodsData['params'][pi]['data'][di].id;
                                if (detail_name.length > 0) {
                                    detail_name += ',' + goodsData['params'][pi]['data'][di].name
                                } else {
                                    detail_name += goodsData['params'][pi]['data'][di].name;
                                }
                            }
                        }
                    }
                }
                var tmp_extra_price = '';
                if(goodsData.extra_price>0&&open_extra_price==1){
                    tmp_extra_price = '+'+goodsData.extra_price+goodsData.extra_price_name
                }
                console.log(goodsCartKey)
                cart_goods_html += '<li class="clr food_' + goodsCartKey + '">';
                cart_goods_html += '<div class="Clist_left">';
                cart_goods_html += '<h2>' + goodsData.name;
                if (parseInt(goodsData.package_id) > 0) {
                    cart_goods_html += '(套餐)';
                }
                cart_goods_html += '</h2>';
                if (detail_name.length) {
                    cart_goods_html += '<span>' + detail_name + '</span>';
                }
                
                cart_goods_html += '</div>';
                cart_goods_html += '<div class="Clist_right">';
                cart_goods_html += '<div class="MenuPrice"><i>￥</i>' + goodsData.price +tmp_extra_price+ '</div>';
                cart_goods_html += '<div class="Addsub">';
                if (parseInt(goodsData.package_id) > 0) {
                    cart_goods_html += '<input type="text" value="' + goodsData.num + '" readOnly="true" class="num">';
                } else {
                    cart_goods_html += '<a href="javascript:void(0)" class="jian" data-unit="' + goodsData.unit + '" data-stock="-1" data-price="' + goodsData.price + '" data-id="' + goodsData.goods_id + '" data-uid="' + nowShopCart[i].uid + '" data-index="' + goodsCartKey + '" data-name="' + goodsData.name +'"'+ 'data-extra_pay_price="' + goodsData.extra_price +'" data-extra_price_name="' + goodsData.extra_price_name +'"></a>';
                    cart_goods_html += '<input type="text" value="' + goodsData.num + '" readOnly="true" class="num">';
                    cart_goods_html += '<a href="javascript:void(0)" class="jia" data-unit="' + goodsData.unit + '" data-stock="-1" data-price="' + goodsData.price + '" data-id="' + goodsData.goods_id + '" data-uid="' + nowShopCart[i].uid + '" data-index="' + goodsCartKey + '" data-name="' + goodsData.name+'"' + 'data-extra_pay_price="' + goodsData.extra_price +'" data-extra_price_name="' + goodsData.extra_price_name+'"></a>';
                }
                cart_goods_html += '</div>';
                cart_goods_html += '</div>';
                cart_goods_html += '</li>';
                
                $('.food_' + goodsCartKey).find("input").val(parseInt(goodsData.num)).show();
                $('.food_' + goodsCartKey).find(".jian").show();
				
				goodsNumber += parseInt(goodsData.num);
				if(goodsData.fid == '0'){
					goodsCartMoney += parseFloat(goodsData.price) * parseInt(goodsData.num);
				}
                
                var params = {
                        'uid':nowShopCart[i].uid,
                        'productKey':goodsCartKey,
                        'productId':goodsData.goods_id,
                        'productName':goodsData.name,
                        'productUnit':goodsData.unit,
                        'productPrice':goodsData.price,
                        'productStock':goodsData.stock,
                        'productLabel':goodsData.spec,
						'fid':goodsData.fid,
                        'isTmpOrder':true,
                        'tmpOrderId':goodsData.id,
                        'count':goodsData.num,
                        'extra_price':goodsData.extra_price,
                        'note':goodsData.note
                };
                      
                goodsCart.push(params);
            }
        }
        cart_goods_html += '</dl>';
    }
    $('.Cart_list ul').append(cart_goods_html);
    if (goodsNumber > 0) {
        $(".floor").addClass("floorOn");
        $(".qty").show(500).text(goodsNumber);
        $('#total_price').text(goodsCartMoney);
    }
}

function clearData()
{
    common.http('Storestaff&a=foodShopClearTempData',{'order_id':urlParam.order_id}, function(data){console.log(data);});
}
function init_goods_menu()
{
    goodsCart = [];
    var cart_goods_html = '';
    for (var i in nowShopCart) {
        if (nowShopCart[i] != null && nowShopCart[i].num > 0) {
            var detail_name = '', goodsCartKey = nowShopCart[i].goods_id;
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
            var tmp_extra_price = '';
            if(nowShopCart[i].extra_price>0&&open_extra_price==1){
                tmp_extra_price = '+'+nowShopCart[i].extra_price+nowShopCart[i].extra_price_name
            }
            console.log(goodsCartKey)
            cart_goods_html += '<li class="clr food_' + goodsCartKey + '">';
            cart_goods_html += '<div class="Clist_left">';
            cart_goods_html += '<h2>' + nowShopCart[i].name + '</h2>';
            if (detail_name.length) {
                cart_goods_html += '<span>' + detail_name + '</span>';
            }
            
            cart_goods_html += '</div>';
            cart_goods_html += '<div class="Clist_right">';
            cart_goods_html += '<div class="MenuPrice"><i>￥</i>' + nowShopCart[i].price +tmp_extra_price+ '</div>';
            cart_goods_html += '<div class="Addsub">';
            cart_goods_html += '<a href="javascript:void(0)" class="jian" data-price="' + nowShopCart[i].price + '" data-id="' + nowShopCart[i].goods_id + '" data-index="' + goodsCartKey + '" data-name="' + nowShopCart[i].name +'"'+ 'data-extra_pay_price="' + nowShopCart[i].extra_price +'" data-extra_price_name="' + nowShopCart[i].extra_price_name +'"></a>';
            cart_goods_html += '<input type="text" value="' + nowShopCart[i].num + '" readOnly="true" class="num">';
            cart_goods_html += '<a href="javascript:void(0)" class="jia" data-price="' + nowShopCart[i].price + '" data-id="' + nowShopCart[i].goods_id + '" data-index="' + goodsCartKey + '" data-name="' + nowShopCart[i].name+'"' + 'data-extra_pay_price="' + nowShopCart[i].extra_price +'" data-extra_price_name="' + nowShopCart[i].extra_price_name+'"></a>';
            cart_goods_html += '</div>';
            cart_goods_html += '</div>';
            cart_goods_html += '</li>';
            
            $('.food_' + goodsCartKey).find("input").val(parseInt(nowShopCart[i].num)).show();
            $('.food_' + goodsCartKey).find(".jian").show();
            goodsNumber += parseInt(nowShopCart[i].num);
            goodsCartMoney += parseFloat(nowShopCart[i].price) * parseInt(nowShopCart[i].num);
            goodsCart[i] = nowShopCart[i];
        }
    }
    $('.Cart_list ul').append(cart_goods_html);
    if (goodsNumber > 0) {
        $(".floor").addClass("floorOn");
        $(".qty").show(500).text(goodsNumber);
        $('#total_price').text(goodsCartMoney);
    }
}