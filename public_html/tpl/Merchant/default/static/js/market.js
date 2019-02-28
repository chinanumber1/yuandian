var source_goods_data = [], left_cart_data = [], myScroll1 = null, myScroll2 = null, myScroll3 = null, card_data = [], pause_cart_data = [], order_num = 1, pause_num = 0;
var clearmsg = false;
var ispoint = true;
var check_pay_time = null, order_id = 0, order_price = 0, tip_out_time = 0;
var local_total_index = 'local_total_index_' + store_id, local_left_cart_data_index = 'left_cart_data_' + store_id, local_pause_cart_data = 'pause_cart_data_' + store_id;
var shop_mall = {
	'postNow':false,
	'init_data':function(){//加载全部商品数据
		var t_data = $.parseJSON(window.localStorage.getItem(local_total_index));
		if (t_data == null) {
			var index_loading = layer.load(0, {shade: [0.1,'#000000']});
			$.post(ajax_goods_list, function(response){
				if (response.err_code) {
					layer.msg(response.data);
				} else {
					window.localStorage.setItem(local_total_index, JSON.stringify(response.data));
					shop_mall.init_data_html(response.data);
				}
				layer.close(index_loading);
			}, 'json');
		} else {
			this.init_data_html(t_data);
		}
	},
	'format_data':function(){
		var t_data = $.parseJSON(window.localStorage.getItem(local_total_index));
		var new_data = [];
		for (var i in t_data) {
			for (var ii in t_data[i].goods_list) {
				t_data[i].goods_list[ii].stock_num = source_goods_data[t_data[i].goods_list[ii].goods_id].stock_num;
			}
			new_data.push(t_data[i]);
		}
		window.localStorage.setItem(local_total_index, JSON.stringify(new_data));
	},
	'count_cart_num':function(data, goods_id, num, return_type){
		var new_data = [], is_new = true;
		for (var i in data) {
			if (data[i].goods_id == goods_id) {
				is_new = false;
				num += data[i].num;
				data[i].num = num;
			}
			new_data.push(data[i]);
		}
		if (is_new) {
			new_data.push({'goods_id':goods_id, 'num':num});
		}
		if (return_type !== undefined) {
			return num;
		} else {
			return new_data;
		}
	},
	'init_data_html':function(datas){//加载全部商品页面
		var swiper_slide = '', goods_list = '', first = 0;
		$.each(datas, function(i, data){
			if (first == 0) {
				swiper_slide += '<div class="swiper-slide on"><span>' + data.sort_name + '</span></div>';
			} else {
				swiper_slide += '<div class="swiper-slide"><span>' + data.sort_name + '</span></div>';
			}
			first ++;
			goods_list += '<ul class="clr">';
			
			//减去挂单商品的数量和购物车里面的数量
			var goods_data = $.parseJSON(window.localStorage.getItem(local_left_cart_data_index));
			if (goods_data != null) {
				left_cart_data = goods_data.goods_data;
			} else {
				left_cart_data = null;
			}
			var temp_data = [];
			for (var i in left_cart_data) {
				temp_data = shop_mall.count_cart_num(temp_data, left_cart_data[i].goods_id, left_cart_data[i].num);
			}
			
			var t_pause_cart_data = $.parseJSON(window.localStorage.getItem(local_pause_cart_data)), pause_num = 0;
			for (var t in t_pause_cart_data) {
				pause_num ++;
				var this_goods_data = t_pause_cart_data[t].goods_data;
				for (var s in this_goods_data) {
					temp_data = shop_mall.count_cart_num(temp_data, this_goods_data[s].goods_id, this_goods_data[s].num);
				}
			}
			if (pause_num > 0) {
				$(".right_1uick .gd").find("em").html(pause_num).show();
			} else {
				$(".right_1uick .gd").find("em").html(pause_num).hide();
			}
			
			$.each(data.goods_list, function(ii, goods){
				var stock_num = goods.stock_num;
				if (goods.stock_num != -1) {
					var num = shop_mall.count_cart_num(temp_data, goods.goods_id, 0, 1);
					stock_num -= num;
				}
				source_goods_data[goods.goods_id] = {'goods_id':goods.goods_id, 'name':goods.name, 'number':goods.number, 'price':goods.price, 'stock_num':stock_num, 'unit':goods.unit};
				goods_list += '<li class="source_data_' + goods.goods_id + '" data-id="' + goods.goods_id + '" data-price="' + goods.price + '" data-number="' + goods.number + '" data-unit="' + goods.unit + '" data-name="' + goods.name + '" data-stock="' + stock_num + '">';
				goods_list += '<a href="javascript:void(0);">';
				goods_list += '<h2>' + goods.name + '</h2>';
				if (goods.number != '') {
					goods_list += '<p>' + goods.number + '</p>';
				} else {
					goods_list += '<p>无条形码</p>';
				}
				goods_list += '<div class="clr bot">';
				if (-1 == goods.stock_num) {
					goods_list += '<div class="fl stock">无限</div>';
				} else {
					goods_list += '<div class="fl stock">剩余' + stock_num + goods.unit + '</div>';
				}
				goods_list += '<div class="fr" data-num="68">￥' + goods.price + '</div>';
				goods_list += '</div>';
				goods_list += '</a>';
				goods_list += '</li>';
			});
			goods_list += '</ul>';
		});
		$('.right_list').find('.swiper-wrapper').html(swiper_slide);
		$('.right_end .tab_ul').html(goods_list);
		$(".tab_ul ul").eq(0).show().siblings("ul").hide();
		$(".right_end").height($(window).height() - 245);
		
		//切换导航
		var swiper = new Swiper('.right_list .swiper-container', {
			slidesPerView: 3,
			spaceBetween: 0,
			pagination: '.right_list .swiper-pagination',
			nextButton: '.right_list .swiper-button-next',
			prevButton: '.right_list .swiper-button-prev',
			slidesPerView: 'auto'
		});
		this.loading_cart_html();
	},
	'format_left_data':function(goods_id, type, data, num){//左边购物车的操作
		number_or_name_focus();
		if (goods_id == undefined) {
			return false;
		}
		if (data == undefined) {
			data = null;
		}
		var goods_data = $.parseJSON(window.localStorage.getItem(local_left_cart_data_index));
		if (goods_data != null) {
			left_cart_data = goods_data.goods_data;
		} else {
			goods_data = {'card_data':'', 'goods_data':''};
			left_cart_data = null;
		}
		if (goods_id == '---') {//清空
			new_data = [];
			data = null;
			for (var i in left_cart_data) {
				var stock = $('.source_data_' + left_cart_data[i].goods_id).data('stock');
				if (source_goods_data[left_cart_data[i].goods_id].stock_num != -1) {
					source_goods_data[left_cart_data[i].goods_id].stock_num += left_cart_data[i].num;
					$('.source_data_' + left_cart_data[i].goods_id).data('stock', source_goods_data[left_cart_data[i].goods_id].stock_num).removeClass('on');
					$('.source_data_' + left_cart_data[i].goods_id).find('.stock').html('剩余' + source_goods_data[left_cart_data[i].goods_id].stock_num + source_goods_data[left_cart_data[i].goods_id].unit);
				} else {
					$('.source_data_' + left_cart_data[i].goods_id).removeClass('on');
				}
			}
			$('.right_end .tab_ul').find('li').removeClass('on');
			goods_data.card_data = '';
			shop_mall.tips('商品清空了');
		} else {
			var is_value = false, new_data = [];
			for (var i in left_cart_data) {
				if (left_cart_data[i].goods_id == goods_id) {
					is_value = true;
					data = left_cart_data[i];
				} else {
					new_data.push(left_cart_data[i]);
				}
			}
			
			// + - 删除一条，删除全部
			if (is_value) {
				if (type == '+') {
					data.num ++;
					if (source_goods_data[goods_id].stock_num != -1) {
						if (source_goods_data[goods_id].stock_num > 0) {
							source_goods_data[goods_id].stock_num --;
							$('.source_data_' + goods_id).data('stock', source_goods_data[goods_id].stock_num);//右边库存的显示
							$('.source_data_' + goods_id).find('.stock').html('剩余' + source_goods_data[goods_id].stock_num + source_goods_data[goods_id].unit);
						} else {
							layer.msg('库存不足');
							return false;
						}
					}
					shop_mall.tips(source_goods_data[goods_id].name + '加1');
				} else if (type == '-') {
					if (data.num > 1) {
						data.num --;
					} else {
						data = null;
					}
					if (source_goods_data[goods_id].stock_num != -1) {
						source_goods_data[goods_id].stock_num ++;
						$('.source_data_' + goods_id).data('stock', source_goods_data[goods_id].stock_num);
						$('.source_data_' + goods_id).find('.stock').html('剩余' + source_goods_data[goods_id].stock_num + source_goods_data[goods_id].unit);
					}
					shop_mall.tips(source_goods_data[goods_id].name + '减1');
					if (data == null) $('.source_data_' + goods_id).removeClass('on');
				} else if (type == '--') {
					if (source_goods_data[goods_id].stock_num != -1) {
						source_goods_data[goods_id].stock_num += data.num;
						$('.source_data_' + goods_id).data('stock', source_goods_data[goods_id].stock_num);
						$('.source_data_' + goods_id).find('.stock').html('剩余' + source_goods_data[goods_id].stock_num + source_goods_data[goods_id].unit);
					}
					
					data = null;
					shop_mall.tips(source_goods_data[goods_id].name + '删除操作');
					$('.source_data_' + goods_id).removeClass('on');
				} else if (type == null && num != undefined) {
					if (data.num > num) {
						var diff_num = data.num - num;
						data.num = num;
						if (source_goods_data[goods_id].stock_num != -1) {
							source_goods_data[goods_id].stock_num += diff_num;
							$('.source_data_' + goods_id).data('stock', source_goods_data[goods_id].stock_num);
							$('.source_data_' + goods_id).find('.stock').html('剩余' + source_goods_data[goods_id].stock_num + source_goods_data[goods_id].unit);
						}
					} else if (data.num < num) {
						var diff_num = num - data.num;
						if (source_goods_data[goods_id].stock_num != -1) {
							if (source_goods_data[goods_id].stock_num - diff_num > 0) {
								source_goods_data[goods_id].stock_num -= diff_num;
								$('.source_data_' + goods_id).data('stock', source_goods_data[goods_id].stock_num);
								$('.source_data_' + goods_id).find('.stock').html('剩余' + source_goods_data[goods_id].stock_num + source_goods_data[goods_id].unit);
							} else if (source_goods_data[goods_id].stock_num - diff_num == 0) {
								source_goods_data[goods_id].stock_num = 0;
								$('.source_data_' + goods_id).data('stock', source_goods_data[goods_id].stock_num);
								$('.source_data_' + goods_id).find('.stock').html('剩余' + source_goods_data[goods_id].stock_num + source_goods_data[goods_id].unit).removeClass('on');
							} else {
								layer.msg('库存不足');
								return false;
							}
						}
						data.num = num;
					}
					shop_mall.tips(source_goods_data[goods_id].name + '修改成' + data.num + source_goods_data[goods_id].unit);
				}
			} else if (data != null) {
				if (source_goods_data[goods_id].stock_num != -1) {
					if (source_goods_data[goods_id].stock_num > 0) {
						source_goods_data[goods_id].stock_num --;
						$('.source_data_' + goods_id).data('stock', source_goods_data[goods_id].stock_num);//右边库存的显示
						$('.source_data_' + goods_id).find('.stock').html('剩余' + source_goods_data[goods_id].stock_num + source_goods_data[goods_id].unit);
					} else {
						layer.msg('库存不足');
						return false;
					}
				}
				shop_mall.tips(data.name + '加1');
			}
		}

		if (data != null) {
			if (new_data.length > 0) {
				new_data.reverse().push(data);
				new_data.reverse();
			} else {
				new_data.push(data);
			}
		}
		goods_data.goods_data = new_data;
		window.localStorage.setItem(local_left_cart_data_index, JSON.stringify(goods_data));
		this.loading_cart_html();
	},
	'loading_cart_html':function() {//左边购物车页面的加载
		var cathtml = '', isFirst = 0, total_price = 0;
		var goods_data = $.parseJSON(window.localStorage.getItem(local_left_cart_data_index));
		$('.left_card_id').html('会员号: 无');
		$('.left_card_money').html('￥0');
		var left_cart_data = '';
		if (goods_data != null) {
			left_cart_data = goods_data.goods_data;
			card_data = goods_data.card_data;
			if (card_data != '') {
				$('.left_card_id').html('会员号: ' + card_data.card_id);
				$('.left_card_money').html('￥' + card_data.card_money);
			}
		}
		for (var i in left_cart_data) {
			if (isFirst == 0) {
				cathtml += '<tr class="on goods_' + left_cart_data[i].goods_id + '" data-id="' + left_cart_data[i].goods_id + '" data-num="' + left_cart_data[i].num + '" data-price="' + left_cart_data[i].price + '">';
			} else {
				cathtml += '<tr class="goods_' + left_cart_data[i].goods_id + '" data-id="' + left_cart_data[i].goods_id + '" data-num="' + left_cart_data[i].num + '" data-price="' + left_cart_data[i].price + '">';
			}
			isFirst ++;
			
			cathtml += '<td width="42">' + isFirst + '</td>';
			cathtml += '<td class="tl" width="235">';
			cathtml += '<h2>' + left_cart_data[i].name + '</h2>';
			cathtml += '<p>' + left_cart_data[i].number + '</p>';
			cathtml += '</td>';
			cathtml += '<td width="55">' + left_cart_data[i].num + '</td>';
			cathtml += '<td class="tl" width="115">';
			cathtml += '￥<span class="price">' + parseFloat((parseFloat(left_cart_data[i].price) * left_cart_data[i].num).toFixed(2)) + '</span>';
			cathtml += '</td>';
			cathtml += '</tr>';
			
			total_price += parseFloat(left_cart_data[i].price) * left_cart_data[i].num;
			if(!/(android)/.test(navigator.userAgent.toLowerCase())){
				$('.source_data_' + left_cart_data[i].goods_id).addClass('on');
			}
		}
		total_price = parseFloat(total_price.toFixed(2));
        if (cathtml == '') {
            $(".tabx_list table.slide").html('').hide();
        } else {
            $(".tabx_list table.slide").html(cathtml).show();
        }
		$(".number .ef2").text(total_price);
		
		number_or_name_focus();
		// div高度
		$(".roll_table").height($(window).height() - 445);
		myScroll1 = new IScroll('.roll_table',{ click: true,mouseWheel:true,bounce:false});
	},
	'array_remove':function(data, index) {
		var new_data = [];
		for (var i in data) {
			if (index != i) {
				new_data.push(data[i]);
			}
		}
		return new_data;
	},
	//挂单操作
	'pause_cart':function(opt, index){
		pause_cart_data = $.parseJSON(window.localStorage.getItem(local_pause_cart_data));
		var new_datas = []
		for (var i in pause_cart_data) {
			new_datas.push(pause_cart_data[i]);
		}
		if (opt == '+') {
			left_cart_data = $.parseJSON(window.localStorage.getItem(local_left_cart_data_index));
			
			var goods_data = '';
			if (left_cart_data != null) {
				goods_data = left_cart_data.goods_data;
			}
			if (goods_data.length > 0) {
				new_datas.push(left_cart_data);
			}
			window.localStorage.removeItem(local_left_cart_data_index)
			this.format_left_data('---');
			$('.left_card_id').html('会员号: 无');
			$('.left_card_money').html('￥0');
			shop_mall.tips('挂单成功');
			
		} else if (opt == '-') {
			window.localStorage.setItem(local_left_cart_data_index, JSON.stringify(new_datas[index]));
			this.loading_cart_html();
			new_datas = shop_mall.array_remove(new_datas, index);
			shop_mall.tips('调出挂单成功');
		} else if (opt == '--') {
			window.localStorage.setItem(local_left_cart_data_index, JSON.stringify(new_datas[index]));
			this.format_left_data('---');
			new_datas = shop_mall.array_remove(new_datas, index);
		}
		window.localStorage.setItem(local_pause_cart_data, JSON.stringify(new_datas));
		myScroll1.refresh();
		
		this.pause_cart_count();
	},
	'pause_cart_html':function(){
		var left_menu = '', right_table = '';
		pause_num = 0;
		
		pause_cart_data = $.parseJSON(window.localStorage.getItem(local_pause_cart_data));
		var this_goods_data = '';
		for (var t in pause_cart_data) {
			pause_num ++;
			left_menu += '<li data-order_num="' + t + '">' + pause_num + '</li>';
			right_table += '<table class="end">';
			this_goods_data = pause_cart_data[t].goods_data;
			for (var s in this_goods_data) {
				right_table += '<tr>';
				right_table += '<td width="48">' + parseInt(parseInt(s) + 1) + '</td>';
				right_table += '<td width="138">' + this_goods_data[s].number + '</td>';
				right_table += '<td width="187">' + this_goods_data[s].name + '</td>';
				right_table += '<td width="50">' + this_goods_data[s].num + '</td>';
				right_table += '<td width="50">' + this_goods_data[s].price + '</td>';
				right_table += '<td width="75">' + parseFloat(this_goods_data[s].num * this_goods_data[s].price).toFixed(2) + '</td>';
				right_table += '</tr>';
			}
			right_table += '</table>';
		}
		$('.guadan .leaguer ul').html(left_menu);
		$('.member_list .tab_end .tab_table').html(right_table);
		$(".guadan, .shadow").show();
		myScroll2.refresh();
		myScroll3.refresh();
		
		$('.leaguer li').eq(0).addClass("on").siblings("li").removeClass("on");
		$(".tab_table table").eq(0).show().siblings("table").hide();
		this.pause_cart_count();
	},
	'pause_cart_count':function(){
		pause_num = 0;
		pause_cart_data = $.parseJSON(window.localStorage.getItem(local_pause_cart_data));
		for (var t in pause_cart_data) {
			pause_num ++;
		}
		if (pause_num > 0) {
			$(".right_1uick .gd").find("em").html(pause_num).show();
		} else {
			$(".right_1uick .gd").find("em").html(pause_num).hide();
		}
		return pause_num;
	},
	'search':function(){
		var key = $('#number_or_name').val();
		for (var i in source_goods_data) {
			if (source_goods_data[i].name == key || source_goods_data[i].number == key) {
				// $('.source_data_' + source_goods_data[i].goods_id).addClass('on');
				this.format_left_data(source_goods_data[i].goods_id, '+', {'goods_id':source_goods_data[i].goods_id, 'name':source_goods_data[i].name, 'unit':source_goods_data[i].unit, 'num':1, 'price':source_goods_data[i].price, 'number':source_goods_data[i].number});
				$('#number_or_name').val('');
				break;
			}
		}
	},
	'pay_success_back':function(){
		window.localStorage.removeItem(local_left_cart_data_index);
		order_id = 0;
		this.format_data();
		this.init_data();
	},
	'go_pay':function(paymethod, auth_code, offline_pay){
		if(this.postNow == true){
			layer.msg('正在请求中，请稍等');
			return false;
		}
		$('.fix .chat_end .firm').html('请求中...');
		if (offline_pay == undefined) offline_pay = -1;
		this.postNow = true;
		var coupon = $('#coupon_price').data('coupon'), card_money = $('#user_card_money').data('price'), price = parseFloat($('#pay_price').data('price')), uid = 0, card_id = 0, discount = 10;
		var change_reason = $('#change_price_reason').val();
		if (left_cart_data.card_data != '') {
			discount = parseFloat(left_cart_data.card_data.discount);
			if (discount > 0) {
			} else {
				discount = 10;
			}
			uid = left_cart_data.card_data.uid;
			card_id = left_cart_data.card_data.card_id;
		}
		$.post(arrival_pay,{order_id:order_id, 'auth_code':auth_code, 'auth_type':paymethod, 'change_reason':change_reason, 'price':price, 'card_id':card_id, 'uid':uid, 'discount':discount, 'coupon':coupon, 'card_money':card_money, 'offline_pay':offline_pay},function(result){
			shop_mall.postNow = false;
			if(result.status == 1){
				layer.msg('支付成功');
				shop_mall.pay_success_back();
				window.top.location.reload();
			}else{
			    if (typeof result.errcode != undefined && result.errcode == 'USERPAYING') {
			        layer.confirm(result.info, {
                        btn: ['是(Y)','否(N)'] //按钮
                    }, function(index){
                        layer.close(index);
                        $('.fix .chat_end .firm').trigger("click");
                    }, function(){});
			        
			    } else {
			        layer.msg(result.info);
			    }
			}
			$('.fix .chat_end .firm').html('确认支付');
		});
	},
	'count_price_html':function(){
		var discount = $('#card_discount').data('discount'), reduce_money = $('#coupon_price').data('price'), card_money = $('#user_card_money').data('price');
		var payPrice = parseFloat($('#pay_price').data('price'));//parseFloat(discount * 0.1 * order_price - reduce_money).toFixed(2));
		$('#pay_price').html('￥' + payPrice);
		var go_pay_money = parseFloat(parseFloat(payPrice - card_money).toFixed(2));
		if (go_pay_money < 0) {
			$('#user_card_money').html('￥' + payPrice).data('price', payPrice);
			go_pay_money = 0;
		}
		$('#go_pay_money').html('<span class="still_zf">还需支付：</span>￥' + go_pay_money).data('price', go_pay_money);
		if (go_pay_money <= 0) {
			$('.pay .confirm').show();
			$('.pay .line,.pay .wx,.pay .alipay').hide();
		} else {
			$('.pay .confirm').hide();
			$('.pay .line,.pay .wx,.pay .alipay').show();
		}
	},
	'tips':function(msg){
//		clearTimeout(tip_out_time);
		$('.prompt_span').html(msg);
//		tip_out_time = setTimeout(function(){$('.prompt_span').html('');}, 1000);
	}
};

function number_or_name_focus(){
	if(!/(android)/.test(navigator.userAgent.toLowerCase())){
		$('#number_or_name').focus();
	}
}

var keyCodeArr = {'48':'0','49':'1','50':'2','51':'3','52':'4','53':'5','54':'6','55':'7','56':'8','57':'9','96':'0','97':'1','98':'2','99':'3','100':'4','101':'5','102':'6','103':'7','104':'8','105':'9'};
$(document).ready(function(){	
	$('body').keyup(function(e){
		if(document.activeElement.id == '' && keyCodeArr[e.keyCode]){
			$('#number_or_name').val($('#number_or_name').val()+keyCodeArr[e.keyCode]);
		}
	});
	
	$('.back').click(function(){
		location.href = $(this).data('url');
	});
	//初始化数据
//	window.localStorage.clear();
	shop_mall.init_data();
	$('#number_or_name').keyup(function(){
		var v = $(this).val();
		if (v == '/' || v == '*' || v == '+' || v == '-' || v == 'u' || v == 'h' || v == 'y' || v == 'd' || v == 'n' || v == 'z') {
			$('#number_or_name').val('');
		}
		return false;
	});
	// 右侧内容切换
	$(document).on('click', '.right_top .swiper-slide', function() {
		number_or_name_focus();
		$(this).addClass("on").siblings(".swiper-slide").removeClass("on");
		$(".tab_ul ul").eq($(this).index()).show().siblings("ul").hide();
	});
	//左侧购物车中的选择
	$(document).on("click", "table.slide tr", function() {
		$(this).addClass("on").siblings().removeClass("on");
	});
	
	myScroll3 = new IScroll('.leaguer', {click:true, mouseWheel:true, bounce:false});
	myScroll2 = new IScroll('.tab_end', {click:true, mouseWheel:true, bounce:false});
	
	//挂单页面中的挂单项切换
	$(document).on('click', '.leaguer li', function() {
		$(this).addClass("on").siblings("li").removeClass("on");
		$(".tab_table table").eq($(this).index()).show().siblings("table").hide();
		myScroll2.scrollTo(0, 0);
		myScroll2.refresh();
	});
	//挂单页的商品行选择效果
	$(document).on('click', '.tab_table tr', function(){
		$(this).addClass("on").siblings().removeClass("on");
	});
	$(document).on('click', '.reload', function(){
		var index_loading = layer.load(0, {shade: [0.1,'#000000']});
		$.post(ajax_goods_list, {refresh:0}, function(response){
			if (response.err_code) {
				layer.msg(response.data);
			} else {
				window.localStorage.setItem(local_total_index, JSON.stringify(response.data));
				shop_mall.init_data_html(response.data);
			}
			layer.close(index_loading);
		}, 'json');
	});
	
	//挂单的取消
	$(".op_cancel").click(function(){
		number_or_name_focus();
		$(".guadan, .shadow").hide();
	});
	
	//挂单删除
	$(".op_del").click(function(){
		layer.confirm('是否删除挂单号[' + (parseInt($('.leaguer').find('.on').data('order_num')) + 1) + ']？', {
						btn: ['是(Y)','否(N)'] //按钮
					}, function(index){
						layer.close(index);
						shop_mall.pause_cart('--', $('.leaguer').find('.on').data('order_num'));
						shop_mall.pause_cart_html();
						var num = shop_mall.pause_cart_count();
						if (num < 1) {
							$(".guadan, .shadow").hide();
						}
					}, function(){});
		
	});
	
	//选取挂单中的某个订单返回到购物车
	$('.op_confirm').click(function(){
		shop_mall.pause_cart('-', $('.leaguer').find('.on').data('order_num'));
		$(".guadan, .shadow").hide();
	});
	
	//右边的菜单点击
	$(document).on('click', '.tab_ul ul li', function(){
		var name = $(this).data('name'), number = $(this).data('number'), price = parseFloat($(this).data('price')), goods_id = $(this).data('id'), unit = $(this).data('unit');
		shop_mall.format_left_data(goods_id, '+', {'goods_id':goods_id, 'name':name, 'unit':unit, 'num':1, 'price':price, 'number':number});
	});
	
	$(document).on('click', '.right_1uick li', function(){
		var opt = $(this).data('opt');
		switch (opt){
		case '/'://结算 /点击
			left_cart_data = $.parseJSON(window.localStorage.getItem(local_left_cart_data_index));
			var goods_data = '';
			if (left_cart_data != null) {
				goods_data = left_cart_data.goods_data;
			}
			if (goods_data.length > 0) {
				var index_loading = layer.load(0, {shade: [0.1,'#000000']});
				$.post(shop_order_save, {data:left_cart_data}, function(response){
					shop_mall.tips('进入结算');
					if (response.error_code) {
						layer.msg(response.msg);
					} else {
						order_id = response.order_id;
						order_price = response.price;
						$('#order_id').html(response.real_orderid);
						$('#order_price span').html('￥' + response.price);
						$(".settlement, .shadow").show();
						$('#pay_qrcode_url').attr('src', response.pay_qrcode_url);
						if (response.discount_msg != '') {
							$('.disk').html(response.discount_msg);
							$('#order_price').addClass('on');
						} else {
							$('.disk').html('');
							$('#order_price').removeClass('on');
						}
						left_cart_data = $.parseJSON(window.localStorage.getItem(local_left_cart_data_index));
						var discount = 10, card_money = 0, use_card_money = 0, card_id = '无';
						if (left_cart_data.card_data != '') {
							discount = parseFloat(left_cart_data.card_data.discount);
							card_id = left_cart_data.card_data.card_id;
							card_money = left_cart_data.card_data.card_money;
						}
						if (discount > 0 && discount != 10) {
							$('#card_discount').html(discount + '折').data('discount', discount);
						} else {
							discount = 10;
							$('#card_discount').html('无折扣').data('discount', 10);
						}
						$('#user_card_number').html(card_id);
						$('#user_card_total').html('￥' + card_money).data('price', card_money);
						
						var payPrice = parseFloat(parseFloat(discount * 0.1 * response.price).toFixed(2));
						$('#pay_price').html('￥' + payPrice).data('price', payPrice);
						$('#pay_price').data('old_price', payPrice);
						if (card_money > payPrice) {
							$('#user_card_money').html('￥' + payPrice).data('price', payPrice);
						} else {
							$('#user_card_money').html('￥' + card_money).data('price', card_money);
						}
						$('#go_pay_money').html('<span class="still_zf">还需支付：</span>￥' + payPrice).data('price', payPrice);
						shop_mall.count_price_html();
					}
					layer.close(index_loading);
				}, 'json');
			} else {
				shop_mall.tips('未选择任何商品，无法操作！');
			}
			
			break;
		case '+':// + 点击
			if ($("table.slide").find('.on').data('id')) {
				shop_mall.format_left_data($("table.slide").find('.on').data('id'), '+');
			} else {
				shop_mall.tips('未选择任何商品，无法操作！');
			}
			
			break;
		case '-':// - 点击
			if ($("table.slide").find('.on').data('id')) {
				shop_mall.format_left_data($("table.slide").find('.on').data('id'), '-');
			} else {
				shop_mall.tips('未选择任何商品，无法操作！');
			}
//			shop_mall.format_left_data($("table.slide").find('.on').data('id'), '-');
			break;
		case '*'://数量
			if ($("table.slide").find('.on').size() == 1) {
				$(".counter").data('type', 0);//0 表示增加商品数量，1：支付的金额
				$(".counter").removeClass('xgcontainer').addClass('slcontainer');
				$(".counter").show().find("input")[0].focus();
				$('.shadow').show();
			} else {
				shop_mall.tips('未选择任何商品，无法操作！');
				number_or_name_focus();
			}
			break;
		case 'u'://u 删除 点击
			if ($("table.slide").find('.on').data('id')) {
				shop_mall.format_left_data($("table.slide").find('.on').data('id'), '--');
			} else {
				shop_mall.tips('未选择任何商品，无法操作！');
			}
			break;
		case 'h'://挂单
			left_cart_data = $.parseJSON(window.localStorage.getItem(local_left_cart_data_index));
			var goods_data = '';
			if (left_cart_data != null) {
				goods_data = left_cart_data.goods_data;
			}
			pause_num = shop_mall.pause_cart_count();
			if (goods_data.length > 0) {
				shop_mall.pause_cart('+');
				shop_mall.pause_cart_count();
			} else if (pause_num > 0) {
				shop_mall.pause_cart_html();
			} else {
				shop_mall.tips('未选择任何商品，无法操作！');
			}
			break;
		case 'y'://会员卡
			$('#card_name, #card_number, #card_number2, #card_sex, #card_phone, #card_money, #card_discount_p').html('');
			$('#card_value').val('');
			$('#card_score').html('可用积分：0');
			$(".card").show().find("input")[0].focus();
			$(".query_qr").css("background","#ccc");
			$('.shadow').show();
			break;
		case 'd'://清空全部
			if ($("table.slide").find('.on').data('id')) {
				shop_mall.format_left_data('---');
			} else {
				shop_mall.tips('未选择任何商品，无法操作！');
			}
			break;

		default:
			break;
		}
	});
	//修改支付的价格
	$('.mod_hand').click(function(){
//		$('.change_price').find('input[name=change_price]').val($('#pay_price').data('price'));
		$('#show_last_price').html($('#pay_price').data('price'));
		$('.change_price, .shadow_two').show();
		$('.change_price').find('input[name=change_price]').val($('#pay_price').data('price')).focus();
	});
	
	$('.change_price .hf').click(function(){
		var price = parseFloat($('#pay_price').data('old_price'));
//		if (card_money > price) {
//			$('#user_card_money').html('￥' + price).data('price', price);
//		}
		$('#isChange').val(0);
		changePrice(price, '');
		return false;
//		$('#pay_price').html('￥' + price).data('price', price);
//		$('#change_price_reason').val('');
//		$('.mod_hui').html('').hide();
//		$('.change_price, .shadow_two').hide();
//		shop_mall.count_price_html();
	});
	
	$('.change_price .qr').click(function(){
		var change_price = parseFloat($('.change_price').find('input[name=change_price]').val()), old_price = parseFloat($('#pay_price').data('old_price')), card_money = parseFloat($('#user_card_money').data('price'));
		var change_price_reason = $('.change_price').find('input[name=change_price_reason]').val();
		if (change_price <= 0 || change_price == old_price) {
			$('#change_price_reason').val('');
			$('.mod_hui').html('').hide();
			$('.change_price, .shadow_two').hide();
			return false;
		}
		if (change_price_reason.length > 9) {
			layer.msg('理由最多8个字');
			return false;
		}
		$('#isChange').val(1);
		changePrice(change_price, change_price_reason);
		
		
//		if (card_money > change_price) {
//			$('#user_card_money').html('￥' + change_price).data('price', change_price);
//		}
//		$('#pay_price').html('￥' + change_price).data('price', change_price);
//		var html = '<em>修改前：￥' + old_price + '</em>';
//		if (change_price_reason.length > 0) {
//			html += '<em>备注：' + change_price_reason + '</em>';
//		}
//		$('#change_price_reason').val(change_price_reason);
//		$('.mod_hui').html(html).show();
//		$('.change_price, .shadow_two').hide();
//		shop_mall.count_price_html();
		return false;
	});
	
	//修改金额
	$(".modify").click(function(){
		$(".counter").data('type', 1);//0 表示增加商品数量，1：支付的金额
		$(".counter").removeClass('slcontainer').addClass('xgcontainer');
		$(".counter").show().find("input")[0].focus();
		$(".shadow_two").show();
	});
	
	//整单取消 
	$(".set_fr .cancel").click(function(){
		layer.confirm('真的取消订单吗？', {
			btn: ['是(Y)','否(N)'] //按钮
		}, function(index){
			layer.close(index);
			$(".right_1uick .qk").trigger("click");
			$(".settlement, .shadow").hide();
			shop_mall.tips('取消结算成功');
		});
	});
	//计算器输入值的处理
	$('.counter input.text').keyup(function(e){
		if (e.keyCode == 13) {
			$(this).parents('.counter').find('.getResult').trigger('click');
			return false;
		}
		var type = $(this).parents('.counter').data('type')
		if (type == 0) {
			var text = $(this).val(), last_v = $(this).val().substr(-1, 1);
			if (last_v.search(/\d/) == -1) {
				$(this).val(text.substr(0, text.length - 1));
				return false;
			}
		}
			
	});
	//计算器点击确定的处理结果
	$(".getResult").click(function(){
		var type = $(this).parents('.counter').data('type');
		var num = parseFloat($.trim($(this).parents('.counter').find("input.text").val()));
		if (isNaN(num)) {
			$(".counter, .shadow_two").hide();
			return false;
		}
		
		if (type == 0) {
			if (num < 1) {
				layer.msg('数量必须是大于0的整数');
				return false;
			}
			if (num % 1 !== 0) {
				layer.msg('数量必须是大于0的整数');
				return false;
			}
			shop_mall.format_left_data($("table.slide").find('.on').data('id'), null, null, num);
			$(".counter, .shadow").hide();
		} else if (type == 1) {
			var card_total_money = parseFloat($('#user_card_total').data('price')), pay_price = $('#pay_price').data('price');
			if (num > card_total_money) {
				layer.msg('超过了可用余额');
				return false;
			}
			if (num > pay_price) {
				layer.msg('超过了待支付的金额');
				return false;
			}
			$('#user_card_money').html('￥' + num).data('price', num);
			shop_mall.count_price_html();
			$(".counter, .shadow_two").hide();
		} else if (type == 2) {
			var price = parseFloat($('#go_pay_money').data('price'));
			if (num < price) {
				layer.msg('金额不能小于应付的金额');
				return false;
			}
			$('#offline_finish_money').html(num);
			$('#offline_return_money').html(parseFloat((parseFloat(num) - price).toFixed(2)));
		}
		$(this).parents('.counter').find("input.text").val('');
	});
	
	// 点击计算器中的数字
	$(".show").click(function(){
		var data = $(this).val(), text = $(this).parents('.counter').find('.text').val();
		if (text != '0') {
			$(this).parents('.counter').find('.text').val(text + '' + data);
		} else {
			if (!(data == '0' || data == '00')) {
				$(this).parents('.counter').find('.text').val(data);
			}
		}
		$(this).parents('.counter').find('.text').focus();
	});
	//点击计算器中的点
	$(".point").click(function(){
		if ($(this).parents('.counter').data('type') == 0) return false;
		var text = $(this).parents('.counter').find('.text')[0];
		var p = $(this)[0];
		if (text.value.search(/\./) != -1) return false;
		if (text == '') text = 0;
		text.value += p.value;
		$(this).parents('.counter').find('.text').focus();
	});
	
	//点击计算器的清空
	$(".funclear").click(function(){
		$(this).parents('.counter').find('.text').val('').focus();
	});
	
	//点击计算器的退格键
	$(".funback").click(function(){
		var text = $(this).parents('.counter').find('.text').val(); 
		if (text == "0" || text == "") {
			$(this).parents('.counter').find('.text').val('');
		} else {
			var last_value = text.substr(-2, 1);
			if (last_value == '.') {
				$(this).parents('.counter').find('.text').val(text.substr(0, text.length - 2));
			} else {
				$(this).parents('.counter').find('.text').val(text.substr(0, text.length - 1));
			}
		}
		$(this).parents('.counter').find('.text').focus();
	});
	// 计算器取消
	$(".counter .cancel").click(function(){
		if (!$(".shadow_two").is(":hidden")) {
			$(".counter, .shadow_two").hide();
		} else {
			number_or_name_focus();
			$(".counter, .shadow").hide();
		}
	});
	

	//会员卡搜索输入框的情况按钮
	$('.inbox_top em').click(function(){
		$('.inbox_top input').val('').trigger('input');
	});
	$('#card_value').keyup(function(e){
		if (e.keyCode == 13) {
			$('.query_cx').trigger('click');
		}
	});
	//查询会员卡
	var temp_data = null;
	$('.query_cx').click(function(){
		var key = $('#card_value').val();
		if (key.length < 1) return false;
		var index_loading = layer.load(0, {shade: [0.1,'#000000']});
		$.post(ajax_card_url, {'key':key}, function(response){
			if (response.err_code) {
				$('.card .information').find('.clr').hide();
				$('.card .information').find('.img').show();
				$(".query_qr").css("background","#ccc");
			} else {
				$('.card .information').find('.clr').show();
				$('.card .information').find('.img').hide();
				$(".query_qr").css("background","#0099dc");
				temp_data = response.data;
				
				$('#card_name').html(temp_data.name);
				$('#card_number').html(temp_data.card_id);
				$('#card_number2').html(temp_data.physical_id);
				$('#card_sex').html(temp_data.sex);
				$('#card_discount_p').html(parseFloat(temp_data.discount) + '折');
				$('#card_phone').html(temp_data.phone);
				$('#card_money').html(temp_data.card_money);
				$('#card_score').html('可用积分：' + temp_data.card_score);
			}
			layer.close(index_loading);
		}, 'json');
	});
	//使用会员卡
	$('.query_qr').click(function(){
		if (temp_data == null) return false; 
		left_cart_data = $.parseJSON(window.localStorage.getItem(local_left_cart_data_index));
		if (left_cart_data == null) {
			left_cart_data = {'card_data':temp_data, 'goods_data':''};
		} else {
			left_cart_data.card_data = temp_data;
		}
		window.localStorage.setItem(local_left_cart_data_index, JSON.stringify(left_cart_data));
		$('.left_card_id').html('会员号: ' + temp_data.card_id);
		$('.left_card_money').html('￥' + temp_data.card_money);
		temp_data = null;
		$('.shadow, .card').hide();
	});
	//快捷键
	$(document).keydown(function(e) {
		var code = e.keyCode || e.which || e.charCode;
		switch (code)
		{
			case 38:// ↑
				if ($(".shadow_two").is(":hidden") && $(".shadow").is(":hidden")) {
					if ($(".tabx_list table.slide").find('.on').prev('tr').size() > 0) {
						$(".tabx_list table.slide").find('.on').removeClass('on').prev('tr').addClass('on');
					} else {
						$(".tabx_list table.slide").find('.on').removeClass('on')
						$(".tabx_list table.slide").find('tr').eq($(".tabx_list table.slide").find('tr').size() - 1).addClass('on');
					}
				}
				if(!$(".guadan").is(":hidden") && !$(".shadow").is(":hidden")){
					if ($('.guadan .leaguer ul').find('.on').prev('li').size() > 0) {
						$('.guadan .leaguer ul').find('.on').prev('li').trigger("click");
					} else {
						$('.guadan .leaguer ul').find('li').eq($('.guadan .leaguer ul').find('li').size() - 1).trigger("click");
					}
				}
				break;
			case 40:// ↓
				if ($(".shadow_two").is(":hidden") && $(".shadow").is(":hidden")) {
					if ($(".tabx_list table.slide").find('.on').next('tr').size() > 0) {
						$(".tabx_list table.slide").find('.on').removeClass('on').next('tr').trigger("click");;
					} else {
						$(".tabx_list table.slide").find('.on').removeClass('on');
						$(".tabx_list table.slide").find('tr').eq(0).trigger("click");;
					}
				}
				if(!$(".guadan").is(":hidden") && !$(".shadow").is(":hidden")){
					if ($('.guadan .leaguer ul').find('.on').next('li').size() > 0) {
						$('.guadan .leaguer ul').find('.on').next('li').trigger("click");
					} else {
						$('.guadan .leaguer ul').find('li').eq(0).trigger("click");
					}
				}
				break;
			case 68:// d删除全部
				if ($(".shadow_two").is(":hidden") && $(".shadow").is(":hidden")) {
					$(".right_1uick .qk").trigger("click");
				}
				break;
			case 107:// +加
				if ($(".shadow_two").is(":hidden") && $(".shadow").is(":hidden")) {
					$(".right_1uick .jia").trigger("click");
				}
				break;
			case 109:// -减
				if ($(".shadow_two").is(":hidden") && $(".shadow").is(":hidden")) {
					$(".right_1uick .jian").trigger("click");
				}
				break;
			case 85:// U删除
				if ($(".shadow_two").is(":hidden") && $(".shadow").is(":hidden")) {
					$(".right_1uick .del").trigger("click");
				}
				break;
			case 13: // 条码搜索
				if ($(".shadow_two").is(":hidden") && $(".shadow").is(":hidden")) {
					if($(".left_input input").val().length>0){
						shop_mall.search();
						return false;
					}
				}
				if (!$('.guadan').is(":hidden") && !$(".shadow").is(":hidden")) {
					$('.op_confirm').trigger("click");
					return false;
				}
				if(!$(".settlement").is(":hidden") && !$(".shadow").is(":hidden") && $(".shadow_two").is(":hidden")){
					// $(".pay .confirm").trigger("click");
					$(".pay .line").trigger("click");
					return false;
				}
				return false;
				break;
			case 106:// *数量
				if($(".slcontainer").is(":hidden") && $(".shadow").is(":hidden")){
					$(".right_1uick .sl").trigger("click");
					return false;
				}
				break;
			case 72:// H挂单
				if($(".guadan").is(":hidden") && $(".shadow").is(":hidden")){
					$(".right_1uick .gd").trigger("click");
				}
				break;
			case 115:// f4挂单删除
				if (!($('.guadan').is(":hidden") && $(".shadow").is(":hidden"))) {
					$('.op_del').trigger("click");
				}
				break;
			case 89:// y会员卡
				if($(".card").is(":hidden") && $(".shadow").is(":hidden")){
					$(".right_1uick .hyk").trigger("click");
					return false;
				}
				
				$('.layui-layer-btn0').trigger("click");
				break;
			case 78:// n
				$('.layui-layer-btn1').trigger("click");
				break;
			case 111:// / 结算
				if ($(".settlement").is(":hidden") && $(".shadow").is(":hidden")) {
					$(".right_1uick .js").trigger("click");
				}
				break;
			case 90:// z整单取消
				if (!$(".settlement").is(":hidden") && !$(".shadow").is(":hidden")) {
					$(".set_fr .cancel").trigger("click");
				}
				break;
			case 27:// ESC 返回
				//挂单返回
				if(!$(".guadan").is(":hidden") && !$(".shadow").is(":hidden") && !$(document).find('.layui-layer-shade').size()){
					number_or_name_focus();
					$(".guadan, .shadow").hide();
				}
				//结算订单返回
				if(!$(".settlement").is(":hidden") && !$(".shadow").is(":hidden") && $(".shadow_two").is(":hidden")){
					number_or_name_focus();
					$(".settlement, .shadow").hide();
				}
				//线下支付返回
				if(!$(".linepay").is(":hidden") && !$(".shadow_two").is(":hidden")){
					$(".linepay, .shadow_two").hide();
				}
				//支付宝返回
				if(!$(".alip").is(":hidden") && !$(".shadow_two").is(":hidden")){
					$(".alip, .shadow_two").hide();
				}
				//微信返回
				if(!$(".chat").is(":hidden") && !$(".shadow_two").is(":hidden")){
					$(".chat, .shadow_two").hide();
				}
				//修改价格返回
				if(!$(".change_price").is(":hidden") && !$(".shadow_two").is(":hidden")){
					$(".change_price, .shadow_two").hide();
				}
				//线上支付返回
				if(!$(".payment").is(":hidden") && !$(".shadow_two").is(":hidden")){
					clearInterval(check_pay_time);
					$(".payment, .shadow_two").hide();
				}
				//会员卡返回
				if(!$(".card").is(":hidden") && !$(".shadow").is(":hidden")){
					number_or_name_focus();
					$(".card, .shadow").hide();
				}
				
				//计算器返回
				if (!$(".only_counter").is(":hidden")) {
					if(!$(".shadow_two").is(":hidden")){
						$(".only_counter, .shadow_two").hide();
					} else if(!$(".shadow").is(":hidden")){
						number_or_name_focus();
						$(".only_counter, .shadow").hide();
					}
				}
				
				break;
		}
	});
	//点击弹出优惠券
	$(".cho_yhj").click(function(){
		left_cart_data = $.parseJSON(window.localStorage.getItem(local_left_cart_data_index));
		var html = '';
		if (left_cart_data.card_data != '') {
			var coupon_list = left_cart_data.card_data.card_new;
			var temp_price = parseFloat((order_price * left_cart_data.card_data.discount * 0.1).toFixed(2));
			for (var i in coupon_list) {
				if (parseFloat(coupon_list[i].full_money) > temp_price) {
					continue;
				}
				html += '<div class="swiper-slide" data-id="' + coupon_list[i].coupon_id + '" data-full="' + coupon_list[i].full_money + '" data-reduce="' + coupon_list[i].reduce_money + '">';
				html += '<div class="text">';
				html += '<div class="money_top clr">';
				html += '<i class="fl">￥</i><span class="fl">' + coupon_list[i].reduce_money + '</span>';
				html += '</div>';
				html += '<div class="fullcut">满' + coupon_list[i].full_money + '元可用</div>';
				html += '<div class="coupon_use">点击使用 ></div>';
				html += '<div class="effective">有效期至：' + coupon_list[i].end_time + '</div>';
				html += '</div>';
				html += '</div>';
			}
		}
		if (html == '') {
			layer.msg('暂无可用优惠券');
			return false;
		} else {
			$('.coupon').find('.swiper-wrapper').html(html);
			$('.coupon, .shadow_two').show();
			var swiper = new Swiper('.coupon_end .swiper-container', {
				pagination: '.coupon_end .swiper-pagination',
				nextButton: '.coupon_end .swiper-button-next',
				prevButton: '.coupon_end .swiper-button-prev',
				slidesPerView: 'auto'
			});
		}
	});
	//选中使用优惠券
	$(document).on('click', '.coupon_end .swiper-slide', function(){
		var full_money = $(this).data('full'), reduce_money = $(this).data('reduce'), coupon = $(this).data('id'),discount = $('#card_discount').data('discount');
		$('#coupon_price').data('price', reduce_money);
		$('#coupon_price').data('coupon', coupon);
		var isChange = $('#isChange').val();
		var payPrice = parseFloat((discount * 0.1 * order_price - reduce_money).toFixed(2));
		$('#pay_price').data('price', payPrice).data('old_price', payPrice);//
		
		if (isChange == 1) {
			changePrice(parseFloat(discount * 0.1 * order_price), '');
		}
		
		shop_mall.count_price_html();
		$('.coupon, .shadow_two').hide();
		$(".cho_yhj").text("满" + full_money + "减" + reduce_money).css("color", "#ef2e05");
	});

	//线上支付
	$(".on-line").click(function(){
		check_pay_time = setInterval(function(){
			$.post(shop_arrival_check, {order_id:order_id}, function(result){
				if(result.status == 1){
					layer.msg('支付成功！');
					clearInterval(check_pay_time);
					shop_mall.pay_success_back();
					$(".payment, .shadow_two, .shadow, .settlement").hide(); 
				}
			});
		},3000);
		$(".payment, .shadow_two").show(); 
	});

	$(".shadow").click(function(){
		$(".fix").hide();
		$(this).hide();
		number_or_name_focus();
	});

	$(".shadow_two").click(function(){
		$(".coupon,.alip,.chat,.payment,.linepay,.xgcontainer").hide();
		$(this).hide();
	});


	//删除输入框
	$(".left_input .del").click(function(){
		$(this).siblings(".input")[0].value = $(this).siblings(".input")[0].value.slice(0,-1);
	});

	//返回
	$(".return").click(function(){
		if(!$(this).parents(".settlement").is(":hidden") && $(".shadow_two").is(":hidden")){
			number_or_name_focus();
			$(".shadow").hide();
		}
		if(!$(".payment").is(":hidden") && !$(".shadow_two").is(":hidden")){
			clearInterval(check_pay_time);
		}
		$(this).parents(".fix").hide();
		$(".shadow_two").hide();
	});


	$("body").css("background","-webkit-linear-gradient(top, #010102 0%,#573651 50%,#181b39 100%)");
	
	//判断高度 小屏一屏显示
	if($(window).height() < 725){
		$(".right .right_1uick li").css({"height":"20px","line-height":"20px","padding":"10px 0px"});
	}
	// // 弹窗居中
	// $(".fix").each(function(){
	// 	$(this).css({"margin-left":-($(this).width()/2),"margin-top":-($(this).height()/2)}); 
	// });
	
	//线下支付
	$(".pay .line").click(function(){
		$('#offline_total_money').html($('#go_pay_money').data('price'));
		$('#offline_wait_money').html($('#go_pay_money').data('price'));
		$(".offline_pay").show().find("input")[0].focus();
		$(".shadow_two").show();
	});
	
	$('#offline_finish_money').keyup(function(){
		var text = $(this).val(), last_v = $(this).val().substr(-1, 1);
		if (!(parseFloat(text) > 0)) {
			$('#offline_return_money').html(0);
		}
		if (last_v.search(/\d|\./) == -1) {
			$(this).val(text.substr(0, text.length - 1));
			return false;
		}
		if (text.length == 1 && text == '.') {
			$(this).val('');
			return false;
		}
		if (text.substr(0, text.length - 1).search(/\./) != -1 && last_v == '.') {
			$(this).val(text.substr(0, text.length - 1));
			return false;
		}
		var price = parseFloat($('#go_pay_money').data('price'));
		if (parseFloat(text) > 0) {
			$('#offline_return_money').html(parseFloat((parseFloat(text) - price).toFixed(2)));
		} else {
			$('#offline_return_money').html(0);
		}
	});
	
	
	
	//支付宝支付
	$(".pay .alipay").click(function(){
		$(".alip, .shadow_two").show();
		$('#alipay_txt').focus();
		return false;
	});

	//微信支付
	$(".pay .wx").click(function(){
		$(".chat, .shadow_two").show();
		$('#weixin_txt').focus();
		return false;
	});

	//确认支付成
	$(".pay .confirm").click(function(){
		shop_mall.go_pay('', '', '');
		return false;
	});
	//点击微信与支付宝支付
	$('.fix .chat_end .firm').click(function(){
		shop_mall.go_pay($(this).data('paymethod'), $(this).siblings('input.port').val(), -1);
	});
	//回车微信与支付宝支付
	$('.fix .chat_end .port').keyup(function(e){
		if(e.keyCode == 13){
			shop_mall.go_pay($(this).siblings('.firm').data('paymethod'), $(this).val(), -1);
		}
	});
	
	//线下支付
	$('.offline_pay .offline_pay li').click(function(){
		shop_mall.go_pay('', '', $(this).data('id'));
	});
	
	$("#order_price.on").hover(function(){
		$(".disk").slideDown();
		$(this).addClass("ou");
	},function(){
		$(".disk").slideUp();
		$(this).removeClass("ou");
	})
});

function changePrice(price, reason)
{
	var card_money = parseFloat($('#user_card_money').data('price')), old_price = parseFloat($('#pay_price').data('old_price'));
	$.post(changePriceUrl,{order_id:order_id, 'change_reason':reason, 'change_price':price},function(result){
		if (result.status == 1) {
			if (card_money > price) {
				$('#user_card_money').html('￥' + price).data('price', price);
			} else {
				$('#user_card_money').html('￥' + card_money).data('price', card_money);
			}
			$('#pay_price').html('￥' + price).data('price', price);
			var html = '';
			if (old_price != price) {
				html += '<em>修改前：￥' + old_price + '</em>';
				if (reason.length > 0) {
					html += '<em>备注：' + change_price_reason + '</em>';
				}
			}
			$('.mod_hui').html(html).show();
			$('#change_price_reason').val(reason);
			shop_mall.count_price_html();
			$('.change_price, .shadow_two').hide();
		} else {
			layer.msg(result.info);
			$('.change_price, .shadow_two').hide();
		}
	});
}
