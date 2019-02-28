var goodsCart = [], goodsNumber = 0, goodsCartMoney = 0, goods_price_list = [], goods_index_list = [], goodsCartPackCharge = 0, goods_list = [];
$(document).ready(function(){
	if(typeof(is_google_map)!="undefined"){
        //谷歌地图
        var position = new Object();

        position.lng = $.cookie('shop_select_lng');
        position.lat = $.cookie('shop_select_lat');

        if (position.lng != null && position.lat != null) {
            var map = new google.maps.Map(document.getElementById('biz-map'), {
                disableDefaultUI:true,
                mapTypeControl:false,
                zoom: 10,
                center: {lng:parseFloat(position.lng),lat:parseFloat(position.lat)}
            });
            var polygonArr = [];
            polygonArr.push({lng:parseFloat(position.lng), lat:parseFloat(position.lat)});
            polygonArr.push({lng:parseFloat(store_long), lat:parseFloat(store_lat)});
            var flightPath = new google.maps.Polyline({
                path: polygonArr,
                geodesic: true,
                strokeColor: 'red',
                strokeOpacity: 0.8,
                strokeWeight: 5
            });
            flightPath.setMap(map);

            //我的图标
            var userIcon =  new google.maps.Marker({
                position: {lng:parseFloat(position.lng),lat: parseFloat(position.lat)},
                map:map,
                icon: {url:static_path+"images/mysite.png", anchor: new google.maps.Point(16, 16)}
            });
            //店铺图标
            var storeIcon =  new google.maps.Marker({
                position: {lng:parseFloat(store_long),lat:parseFloat(store_lat)},
                map:map,
                icon: {
                    url: static_path+"images/storesite.png",
                    anchor: new google.maps.Point(16, 16)
				}
            });
            var bounds = new google.maps.LatLngBounds();
            for (var i = 0; i < polygonArr.length; i++) {
                bounds.extend(polygonArr[i]);
            }
            map.fitBounds(bounds);

			var s = GetDistance(position.lng,position.lat,store_long,store_lat);

            var infowindow = new google.maps.InfoWindow({
                content:"距离 " + s + " 米"
			});
            infowindow.open(map, storeIcon);
            storeIcon.addListener('click', function() {
                infowindow.open(map, storeIcon);
            });

            /* 得到经纬度之间的距离 */
            function Rad(d){
                return d * Math.PI / 180.0;//经纬度转换成三角函数中度分表形式。
            }
			//输出为米
            function GetDistance(lng1,lat1,lng2,lat2){
                var radLat1 = Rad(lat1);
                var radLat2 = Rad(lat2);
                var a = radLat1 - radLat2;
                var  b = Rad(lng1) - Rad(lng2);
                var s = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a/2),2) +
                    Math.cos(radLat1)*Math.cos(radLat2)*Math.pow(Math.sin(b/2),2)));
                s = s *6378.137 ;// EARTH_RADIUS;
                s = Math.round(s * 10000) / 10; //输出为公里
                return s;
            }
        } else {
            var map = new google.maps.Map(document.getElementById('biz-map'), {
                mapTypeControl:false,
                zoom: 10,
                center: {lng:parseFloat(store_long),lat:parseFloat(store_lat)}
            });
            var storeIcon =  new google.maps.Marker({
                position: {lng:parseFloat(store_long),lat:parseFloat(store_lat)},
                map:map,
                icon:  {url:static_path+"images/storesite.png", anchor: new google.maps.Point(16, 16)}
            });
        }
	}else{
        //百度地图
        var position = new Object();

        position.lng = $.cookie('shop_select_lng');
        position.lat = $.cookie('shop_select_lat');

        var map = new BMap.Map("biz-map");
        if (position.lng != null && position.lat != null) {
            map.centerAndZoom(new BMap.Point(position.lng, position.lat), 15);
            map.enableScrollWheelZoom();
            var polyline = new BMap.Polyline([
                new BMap.Point(position.lng, position.lat),
                new BMap.Point(store_long, store_lat)
            ], {strokeColor:"red", strokeWeight:5, strokeOpacity:0.8});   //创建折线
            map.addOverlay(polyline);   //增加折线
            //我的图标
            var pt1 = new BMap.Point(position.lng, position.lat);
            var myIcon = new BMap.Icon(static_path+"images/mysite.png", new BMap.Size(32,32));
            var marker1 = new BMap.Marker(pt1,{icon:myIcon});  // 创建标注
            map.addOverlay(marker1);

            //店铺图标
            var pt2 = new BMap.Point(store_long, store_lat);
            var storeIcon = new BMap.Icon(static_path+"images/storesite.png", new BMap.Size(32,32));
            var marker2 = new BMap.Marker(pt2,{icon:storeIcon});  // 创建标注
            map.addOverlay(marker2);

            function _e() {
                this.defaultAnchor = BMAP_ANCHOR_TOP_RIGHT,
                    this.defaultOffset = new BMap.Size(10, 10)
            }

            var n = map.getDistance(pt1, pt2).toFixed(0);
            "NaN" == n && (n = 0),
                _e.prototype = new BMap.Control,
                _e.prototype.initialize = function(e) {
                    var obj = document.createElement("div");
                    return obj.appendChild(document.createTextNode("距离 " + n + " 米")),
                        obj.className = "mapTopCtrl",
                        e.getContainer().appendChild(obj),
                        obj
                };
            var o = new _e;
            map.addControl(o);
            map.setViewport([pt1, pt2]);
            map.enableScrollWheelZoom();
            map.enableContinuousZoom();
        } else {
            map.centerAndZoom(new BMap.Point(store_long, store_lat), 15);
            map.enableScrollWheelZoom();
            //店铺图标
            var pt2 = new BMap.Point(store_long, store_lat);
            var storeIcon = new BMap.Icon(static_path+"images/storesite.png", new BMap.Size(32,32));
            var marker2 = new BMap.Marker(pt2,{icon:storeIcon});  // 创建标注
            map.addOverlay(marker2);
        }
	}



    

	$('#search').click(function(){
		var keyword = $('#keyword').val();
		if (keyword.length < 1) return false;
		location.href = '?keyword=' + keyword;
	});
	// 显示分数
	$(".score").each(function() {
		$(this).find("p").css("width", $(this).find("i").text() * 15);
	});
	
	/*底部返回顶部*/  
	$(window).scroll(function(){  
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
	
	//清除边框
	$(".give li:last-child").css("border-right",0);  
	$(".vlefttop li:last-child").css("background","none");
	$(".Shoplist_top a:last-child").css("background","none");
	$(".Sell_list li:nth-child(4n) a").css("margin-right","0px");
	
	
	//弹窗遮罩高度
	$(".mask").height($(window).height())
	
	   
	// 定位滚动
	$(".vleftend a").click(function(){
		$(this).addClass("on").siblings().removeClass("on")
	});
	
	$(document).on('click','.vleftend a',function(){
		$('html,body').animate({scrollTop:$('.varietylist-'+$(this).data('cat_id')).offset().top-$('.varietylist').offset().top+$('.varietylist').scrollTop()+400},500) ;
	});

    //点击图片查看商品详情
	$(".Sell_list .img").click(function(){
		var tt_index = $(this).data('index');
		if ($(this).data('has_format')) { 
			tt_index = '';
			if (goods_index_list[$(this).data('goods_id')] != undefined) {
				tt_index = goods_index_list[$(this).data('goods_id')];
			}
		}
		popup($(this).data('goods_id'), $(this).data('has_format'), tt_index);
	});
	//关闭商品详情
	$(".gb").click(function(){
		$(this).parents(".Popup").hide();
		$(".mask").hide();
	});
	$('.mask').click( function(e) {
		$('.Popup, .mask').hide();
	});

	//购物车的加减
	$(document).on('click', '.carmiddle ul a', function(){
		var name = $(this).data('name'), price = parseFloat($(this).data('price')), packing_charge = parseFloat($(this).data('packing_charge')), stock = parseInt($(this).data('stock')), goods_id = parseInt($(this).data('goods_id')), goodsCartKey = $(this).data('index'),extra_price =$(this).data('extra_pay_price');
		var maxNum = parseInt($(this).data('max_num')), minNum = parseInt($(this).data('min_num')), oldPrice = parseFloat($(this).data('o_price')), isSeckill = parseInt($(this).data('is_seckill')), limit_type = parseInt($(this).data('limit_type')), unit = $(this).data('unit');
		cartFunction(name, price, goods_id, goodsCartKey, $(this).attr('class'), '', packing_charge, stock,extra_price, maxNum, oldPrice, isSeckill, minNum, unit, limit_type);
	});
	
	
	//弹窗的立即购买
	$(document).on('click', '.purchase', function(){
		var name = $(this).data('name'), price = parseFloat($(this).data('price')), packing_charge = parseFloat($(this).data('packing_charge')), stock = parseInt($(this).data('stock')), goods_id = parseInt($(this).data('goods_id')), has_format = $(this).data('has_format'),extra_price =$(this).data('extra_pay_price');
		var maxNum = parseInt($(this).data('max_num')), minNum = parseInt($(this).data('min_num')), oldPrice = parseFloat($(this).data('o_price')), isSeckill = parseInt($(this).data('is_seckill')), limit_type = parseInt($(this).data('limit_type')), unit = $(this).data('unit');
		if (has_format == 1) {
			var result = check_select(true);
			if (result == false) return false;
			var goodsCartKey = result.index_key;
			var productParam = result.data;
		} else {
			var goodsCartKey = $(this).data('index');
			var productParam = '';
		}
		if (!cartFunction(name, price, goods_id, goodsCartKey, 'jia', productParam, packing_charge, stock,extra_price, maxNum, oldPrice, isSeckill, minNum, unit, limit_type)) {
			return false;
		}
		
		var src = $(this).parents(".text").siblings(".img").find('img').attr('src');
	    var offset = $('.mark').offset(), flyer = $('<img class="temp_image" src="'+src+'" width="70" height="70" style="z-index:10000; border-radius:100%;"/>');
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
	        onEnd:function(){
	            flyer.remove();
	        }
	    });
	});
	
	//弹框中的加减
	$(document).on('click', '.Popup .plus a', function(){
		var name = $(this).data('name'), price = parseFloat($(this).data('price')), packing_charge = parseFloat($(this).data('packing_charge')), stock = parseInt($(this).data('stock')), goods_id = parseInt($(this).data('goods_id')), goodsCartKey = $(this).data('index'),extra_price =$(this).data('extra_pay_price');
		var maxNum = parseInt($(this).data('max_num')), minNum = parseInt($(this).data('min_num')), oldPrice = parseFloat($(this).data('o_price')), isSeckill = parseInt($(this).data('is_seckill')), limit_type = parseInt($(this).data('limit_type')), unit = $(this).data('unit');
		if ($(this).attr('class') == 'jia') {

			if (!cartFunction(name, price, goods_id, goodsCartKey, 'jia', '', packing_charge, stock,extra_price, maxNum, oldPrice, isSeckill, minNum, unit, limit_type)) {
				return false;
			}
			
			var src = $(this).parents(".text").siblings(".img").find('img').attr('src');
		    var offset = $('.mark').offset(), flyer = $('<img class="temp_image" src="'+src+'" width="70" height="70" style="z-index:10000; border-radius:100%;"/>');
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
		        onEnd:function(){
		            flyer.remove();
		        }
		    });
		} else {
			cartFunction(name, price, goods_id, goodsCartKey, 'jian', '', packing_charge, stock,extra_price, maxNum, oldPrice, isSeckill, minNum, unit, limit_type);
		}
	});
	
	//列表中加号加菜
	$(document).on('click', '.click', function(event){
		if ($(this).data('has_format')) {
			var tt_index = '';
			if (goods_index_list[$(this).data('goods_id')] != undefined) {
				tt_index = goods_index_list[$(this).data('goods_id')];
			}
			popup($(this).data('goods_id'), 1, tt_index);
			return false;
		} else {
			var name = $(this).data('name'), packing_charge = parseFloat($(this).data('packing_charge')), stock = parseInt($(this).data('stock')), price = parseFloat($(this).data('price')), goods_id = parseInt($(this).data('goods_id')), goodsCartKey = $(this).data('index'),extra_price =$(this).data('extra_pay_price');
			var maxNum = parseInt($(this).data('max_num')), minNum = parseInt($(this).data('min_num')), oldPrice = parseFloat($(this).data('o_price')), isSeckill = parseInt($(this).data('is_seckill')), limit_type = parseInt($(this).data('limit_type')), unit = $(this).data('unit');
			if (!cartFunction(name, price, goods_id, goodsCartKey, 'jia', '', packing_charge, stock,extra_price, maxNum, oldPrice, isSeckill, minNum, unit, limit_type)) {
				return false;
			}
			
			var src = $(this).parents(".text").siblings(".img").find('img').attr('src');
		    var offset = $('.mark').offset(), flyer = $('<img class="temp_image" src="'+src+'" width="70" height="70" style="z-index:10000; border-radius:100%;"/>');
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
		        onEnd:function(){
		            flyer.remove();
		        }
		    });
		}
	});
    
	$(".empty").click(function(){
	    $(".carmiddle ul").html('');
	    $(this).parents(".cartop").hide();
	    $(".carleft").removeClass("carlefton");
	    $(".common, .carmiddle, .amount").hide();
	    $(".tencer").removeClass("tenceron");
	    $(".tencer").text("购物车是空的").css("cursor", "default");

		goodsNumber = 0;
		goodsCartMoney = 0;
		goodsCartPackCharge = 0;
		goodsCart = [];
		stringifyCart();
	});
	
	init_goods_menu();
	

	//规格选择
	$(document).on('click', '.spec li', function(){
		$(this).parents('ul').find('li').removeClass("on");
		$(this).addClass("on");
		check_select(false);
	});
	
	//属性选择
	$(document).on('click', '.properties li', function(){
		var num = parseInt($(this).parents('ul').data('num')), goods_id = parseInt($(this).parents('.Specifications').data('goods_id')), fid = parseInt($(this).parents('ul').data('id'));
		if ($(this).hasClass('on')) {
			$(this).removeClass("on");
		} else {
			var spec_ids = [];
			$('.Specifications ul.spec_ul').each(function(){
				$(this).find('li').each(function(){
					if ($(this).hasClass('on')) {
						spec_ids.push($(this).data('id'));
					}
				});
			});
			if (spec_ids.length > 0) {
				if (spec_ids.length > 1) {
					var str = spec_ids.join('_');
				} else {
					var str = spec_ids[0];
				}
				if (goods_price_list[goods_id][str] != undefined) {
					var t_properties = goods_price_list[goods_id][str].properties;
					for (var i in t_properties) {
						if (t_properties[i].id == fid) {
							num = t_properties[i].num;
						}
					}
				}
			}
			
			if (parseInt($(this).parents('ul').find('.on').size()) >= num) {
				layer.msg('该属性最多能选' + num + '个');
			} else {
				$(this).addClass("on");
			}
		}
		check_select(false);
	});
	
	$(document).on('click', '.tencer', function(){
		if (goodsNumber < 1) {
			layer.msg('您的购物车还是空！');
			return false;
		} else {
			$('#post_cart').submit();
//			location.href = cart_url;
		}
	});
	
});

function check_select(is_check)
{
	var productParam = [], goods_id = $('.Specifications').data('goods_id'), index_key = $('.Specifications').data('goods_id'), spec_ids = [], is_false = false;
	$('.Specifications ul.spec_ul').each(function(){
		var type = $(this).data('type'), fid = $(this).data('id'), fname = $(this).data('name'), datas = null, select_num = $(this).data('num');
		if (type == 'spec') {
			var num = 0;
			$(this).find('li').each(function(){
				if ($(this).hasClass('on')) {
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
				}
			});
			if (num < 1 && is_check) {
				layer.msg(fname + '规格必须选择一个');
				is_false = true;
			}
		} else {
			var temp_data = [], num = 0;
			$(this).find('li').each(function(){
				if ($(this).hasClass('on')) {
					num ++;
					temp_data.push({'id':$(this).data('id'), 'list_id':fid, 'name':$(this).data('name')});
					index_key += '_v_' + $(this).data('id');
				}
			});
			if (num < 1 && is_check) {
				layer.msg(fname + '属性至少选择一个');
				is_false = true;
			}
			if (num > select_num && is_check) {
				layer.msg(fname + '属性最多选择' + select_num + '个');
				is_false = true;
			}
			if (temp_data.length > 0) {
				datas = {type:'properties', data:temp_data};
				productParam.push(datas);
			}
		}
	});
	
	
	$('.Specifications ul.properties_ul').each(function(){
		var type = $(this).data('type'), fid = $(this).data('id'), fname = $(this).data('name'), datas = null, select_num = $(this).data('num');
		var temp_data = [], num = 0;
		$(this).find('li').each(function(){
			if ($(this).hasClass('on')) {
				num ++;
				temp_data.push({'id':$(this).data('id'), 'list_id':fid, 'name':$(this).data('name')});
				index_key += '_v_' + $(this).data('id');
			}
		});
		if (num < 1 && is_check) {
			layer.msg(fname + '属性至少选择一个');
			is_false = true;
		}
		
		if (spec_ids.length > 0) {
			if (spec_ids.length > 1) {
				var str = spec_ids.join('_');
			} else {
				var str = spec_ids[0];
			}
			if (goods_price_list[goods_id][str] != undefined) {
				var t_properties = goods_price_list[goods_id][str].properties;
				for (var i in t_properties) {
					if (t_properties[i].id == fid) {
						select_num = t_properties[i].num;
					}
				}
			}
		}
		
		if (num > select_num && is_check) {
			layer.msg(fname + '属性最多选择' + select_num + '个');
			is_false = true;
		}
		if (temp_data.length > 0) {
			datas = {type:'properties', data:temp_data};
			productParam.push(datas);
		}
	});
	var t_price = 0, maxNum = 0, isSeckill = 0, oldPrice = 0 ;
	if (spec_ids.length > 0) {
		if (spec_ids.length > 1) {
			var str = spec_ids.join('_');
		} else {
			var str = spec_ids[0];
		}
		
		if (goods_price_list[goods_id][str] != undefined) {
			t_price = goods_price_list[goods_id][str].price;
			maxNum = goods_price_list[goods_id][str].max_num;
			oldPrice = goods_price_list[goods_id][str].old_price;
		}
	} else if (goods_price_list[goods_id] == '') {
		t_price = goods_list[goods_id].price;
	}
	$('#show_format_price').text(t_price);
	$('.Popup .purchase, .Popup .jia, .Popup .jian').data('price', t_price);
	$('.Popup .purchase, .Popup .jia, .Popup .jian').data('index', index_key);
	$('.Popup .purchase, .Popup .jia, .Popup .jian').data('max_num', maxNum);
	$('.Popup .purchase, .Popup .jia, .Popup .jian').data('o_price', oldPrice);
	
	var this_index = format_cart_data(index_key);
	if (this_index == null) {
		$('.Popup .purchase').show();
		$('.Popup .plus').hide();
	} else {
		$('.Popup .plus input').val(goodsCart[this_index].count);
		$('.Popup .purchase').hide();
		$('.Popup .plus').show();
	}
	if (is_false) {
		return false;
	} else {
		return {index_key:index_key, data:productParam};
	}
}


//商品详情的html代码
function popup_html(data, has_format, goodsCartKey)
{
	var this_index = format_cart_data(goodsCartKey);
	
	console.log(data)
	var html = '', spec_ids = [], properties_ids = [];
	html += '<div class="img fl">';
	if (store_theme == 1) {
		html += '<img src="' + data.pic_arr[0]['url'] + '" width="290" height="290">';
	} else {
		html += '<img src="' + data.pic_arr[0]['url'] + '" width="390" height="216">';
	}
	html += '</div>';
	html += '<div class="p415 text fr">';
	html += '<h2>' + data.name + '</h2>';
	html += '<div class="Price clr">';

	if (this_index != null) {
		
		var detail_name = '', goodsCartKey = goodsCart[this_index].productId;
		if (goodsCart[this_index]['productParam'].length) {
			for (var pi in goodsCart[this_index]['productParam']) {
				if (goodsCart[this_index]['productParam'][pi].type == 'spec') {
					goodsCartKey += '_s_' + goodsCart[this_index]['productParam'][pi].id;
					spec_ids[goodsCart[this_index]['productParam'][pi].id] = 1;
				} else {
					if (goodsCart[this_index]['productParam'][pi]['data'].length) {
						var t_ids = [], list_id = 0;
						for (var di in goodsCart[this_index]['productParam'][pi]['data']) {
							goodsCartKey += '_v_' + goodsCart[this_index]['productParam'][pi]['data'][di].id;
							t_ids[goodsCart[this_index]['productParam'][pi]['data'][di].id] = 1;
							list_id = goodsCart[this_index]['productParam'][pi]['data'][di].list_id;
							if (detail_name.length > 0) {
								detail_name += ',' + goodsCart[this_index]['productParam'][pi]['data'][di].name
							} else {
								detail_name += goodsCart[this_index]['productParam'][pi]['data'][di].name;
							}
						}
						if (t_ids.length > 0) {
							properties_ids[list_id] = t_ids;
						}
					}
				}
			}
		}

		var tmp_extra_price = data.extra_pay_price>0&&open_extra_price==1&&data.spec_value==''?('+'+data.extra_pay_price+ExtraPirceName):'';

		html += '<div class="fl Pricesl">￥<i id="show_format_price">' + goodsCart[this_index].productPrice +tmp_extra_price+ '</i>';
		if (data.min_num > 1) {
		    html += '<span style="font-size:12px;color:#999;margin-left:10px">' + data.min_num + data.unit + '起购</span>';
		}
		html += '</div>';
		html += '<div class="fl Pricesl" style="display: none;">￥<i>18</i></div>';
		html += '<div class="fr purchase" style="display:none" data-has_format="' + has_format + '" data-unit="' + data.unit + '" data-limit_type="' + data.limit_type + '" data-stock="' + data.stock_num + '" data-price="' + goodsCart[this_index].productPrice + '" data-packing_charge="' + data.packing_charge + '" data-goods_id="' + data.goods_id + '" data-index="' + goodsCartKey + '" data-name="' + data.name + '" data-extra_pay_price="' + data.extra_pay_price + '" data-min_num="' + data.min_num + '" data-max_num="' + data.max_num + '" data-o_price="' + data.old_price + '" data-is_seckill="' + data.is_seckill_price + '">立即购买</div>';
		html += '<div class="fr plus clr" style="display:block">';
		html += '<a href="javascript:void(0)" class="jian" data-price="' + goodsCart[this_index].productPrice + '" data-unit="' + data.unit + '" data-limit_type="' + data.limit_type + '" data-stock="' + data.stock_num + '" data-packing_charge="' + data.packing_charge + '" data-goods_id="' + data.goods_id + '" data-index="' + goodsCartKey + '" data-name="' + data.name + '" data-extra_pay_price="' + data.extra_pay_price + '" data-min_num="' + data.min_num + '" data-max_num="' + data.max_num + '" data-o_price="' + data.old_price + '" data-is_seckill="' + data.is_seckill_price + '">-</a>';
		html += '<input type="text" value="' + goodsCart[this_index].count + '"  readonly="readonly">';
		html += '<a href="javascript:void(0)" class="jia" data-price="' + goodsCart[this_index].productPrice + '" data-unit="' + data.unit + '" data-limit_type="' + data.limit_type + '" data-stock="' + data.stock_num + '" data-packing_charge="' + data.packing_charge + '" data-goods_id="' + data.goods_id + '" data-index="' + goodsCartKey + '" data-name="' + data.name + '" data-extra_pay_price="' + data.extra_pay_price + '" data-min_num="' + data.min_num + '" data-max_num="' + data.max_num + '" data-o_price="' + data.old_price + '" data-is_seckill="' + data.is_seckill_price + '">+</a>';
		html += '</div>';
	} else {
		var tmp_extra_price = data.extra_pay_price >0&&open_extra_price==1&&data.spec_value==''?('+'+data.extra_pay_price+ExtraPirceName):'';
		html += '<div class="fl Pricesl">￥<i id="show_format_price">' + data.price +tmp_extra_price+ '</i>';
		if (data.min_num > 1) {
            html += '<span style="font-size:12px;color:#999;margin-left:10px">' + data.min_num + data.unit + '起购</span>';
        }
		html += '</div>';
		html += '<div class="fl Pricesl" style="display: none;">￥<i>18</i></div>';
		html += '<div class="fr purchase" data-price="' + data.price + '" data-unit="' + data.unit + '" data-limit_type="' + data.limit_type + '"  data-stock="' + data.stock_num + '" data-packing_charge="' + data.packing_charge + '" data-has_format="' + has_format + '" data-goods_id="' + data.goods_id + '" data-index="' + data.goods_id + '" data-name="' + data.name + '" data-extra_pay_price="' + data.extra_pay_price + '" data-max_num="' + data.max_num + '" data-min_num="' + data.min_num + '" data-o_price="' + data.old_price + '" data-is_seckill="' + data.is_seckill_price + '">立即购买</div>';
		html += '<div class="fr plus clr">';
		html += '<a href="javascript:void(0)" class="jian" data-price="' + data.price + '" data-unit="' + data.unit + '" data-limit_type="' + data.limit_type + '"  data-stock="' + data.stock_num + '" data-packing_charge="' + data.packing_charge + '" data-goods_id="' + data.goods_id + '" data-index="' + data.goods_id + '" data-name="' + data.name + '" data-extra_pay_price="' + data.extra_pay_price + '" data-max_num="' + data.max_num + '" data-min_num="' + data.min_num + '" data-o_price="' + data.old_price + '" data-is_seckill="' + data.is_seckill_price + '">-</a>';
		html += '<input type="text" value="1"  readonly="readonly">';
		html += '<a href="javascript:void(0)" class="jia" data-price="' + data.price + '" data-unit="' + data.unit + '" data-limit_type="' + data.limit_type + '"  data-stock="' + data.stock_num + '" data-packing_charge="' + data.packing_charge + '" data-goods_id="' + data.goods_id + '" data-index="' + data.goods_id + '" data-name="' + data.name + '" data-extra_pay_price="' + data.extra_pay_price + '" data-max_num="' + data.max_num + '" data-min_num="' + data.min_num + '" data-o_price="' + data.old_price + '" data-is_seckill="' + data.is_seckill_price + '">+</a>';
		html += '</div>';
	}
	
	
	html += '</div>';
	if (data.spec_list || data.properties_list) {
		html += '<div class="Specifications clr" data-goods_id="' + data.goods_id + '">';
		if (data.spec_list) {
			for (var i in data.spec_list) {
				html += '<div class="Speclist spec">';
				html += '<span class="fl">' + data.spec_list[i].name + '：</span>';
				html += '<div class="p65">';
				html += '<ul class="clr spec_ul" data-id="' + data.spec_list[i].id + '" data-name="' + data.spec_list[i].name + '" data-num="1" data-type="spec">';
//				console.log(spec_ids);
				for (var ii in data.spec_list[i].list) {
					if (spec_ids[data.spec_list[i].list[ii].id] != undefined) {
						html += '<li class="on" data-id="' + data.spec_list[i].list[ii].id + '" data-name="' + data.spec_list[i].list[ii].name + '">' + data.spec_list[i].list[ii].name + '</li>';
					} else {
						html += '<li data-id="' + data.spec_list[i].list[ii].id + '" data-name="' + data.spec_list[i].list[ii].name + '">' + data.spec_list[i].list[ii].name + '</li>';
					}
				}
					
				html += '</ul>';
				html += '</div>';
				html += '</div>';
			}
		}
		if (data.properties_list) {
			for (var i in data.properties_list) {
				html += '<div class="Speclist properties">';
				html += '<span class="fl">' + data.properties_list[i].name + '：</span>';
				html += '<div class="p65">';
				
				html += '<ul class="clr properties_ul" data-id="' + data.properties_list[i].id + '" data-name="' + data.properties_list[i].name + '" data-num="' + data.properties_list[i].num + '" data-type="properties">';
				for (var ii in data.properties_list[i].val) {
					if (properties_ids.length > 0 && properties_ids[data.properties_list[i].id] != undefined && properties_ids[data.properties_list[i].id][ii] != undefined) {
						html += '<li class="on" data-id="' + ii + '" data-name="' + data.properties_list[i].val[ii] + '">' + data.properties_list[i].val[ii] + '</li>';
					} else {
						html += '<li data-id="' + ii + '" data-name="' + data.properties_list[i].val[ii] + '">' + data.properties_list[i].val[ii] + '</li>';
					}
				}
				html += '</ul>';
				
				html += '</div>';
				html += '</div>';
			}
		}
		html += '</div>';
	}
	html += '<div class="describe">';
	html += '<h2>商品描述</h2>';
	html += '<div class="describe_n">' + data.des + '</div>';
    html += '</div>';
    html += '</div>';
    return html;
}


//查找指定的下标是否在购买车里
function format_cart_data(index)
{
	var this_index = null;
	for (var i in goodsCart) {
		if (goodsCart[i].count > 0) {
			var old_goodsCartKey = goodsCart[i].productId;
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




//弹窗查看商品详情
function popup(goods_id, has_format, goodsCartKey)
{
	if (true || goods_list[goods_id] == undefined) {
		$.get(ajax_goods, {goods_id:goods_id}, function(response){
			var html = '';
			if (response.status) {
				html = popup_html(response.data, has_format, goodsCartKey);
				goods_list[goods_id] = response.data;
				goods_price_list[goods_id] = response.data.list;
			}
			$('.Popup_n').html(html);
			$(".Popup").show();
			$(".mask").show();
//			console.log('total==>' + $('.p415').height())
//			console.log('h2==>' + $(".p415>h2").height())
//			console.log('price==>' + $(".p415 .Price").height())
//			console.log('Specifications==>' + $(".p415 .Specifications").height())
//			console.log('describe h2==>' + $(".p415 .describe h2").height())
			$(".describe_n").css("max-height", $('.p415').height() - $(".p415>h2").height() - $(".p415 .Specifications").height() - $(".p415 .Price").height() - $(".p415 .describe h2").height() - 40);
			$('.content_video').css({width:420,height:236});
		}, 'json');
	} else {
		$('.Popup_n').html(popup_html(goods_list[goods_id], has_format, goodsCartKey));
		$(".Popup").show();
		$(".mask").show();
		$(".describe_n").css("max-height", $('.p415').height() - $(".p415>h2").height() - $(".p415 .Specifications").height() - $(".p415 .Price").height() - $(".p415 .describe h2").height() - 40);
		$('.content_video').css({width:420,height:236});
	}
}


//格式化购物车的html
function format_cart_html(num, name, price, goods_id, index, packing_charge, stock,extra_price, maxNum, oldPrice, isSeckill, minNum, unit, limit_type)
{
	console.log(extra_price)
	var div = '';
	div += '<li class="clr goods_' + index +'">';
	div += '<div class="fl cartitle">' + name +'</div>';
	div += '<div class="plus clr">';
	div += '<a href="javascript:void(0)" class="jian" data-price="' + price + '" data-unit="' + unit + '"  data-limit_type="' + limit_type + '" data-stock="' + stock + '" data-packing_charge="' + packing_charge + '" data-goods_id="' + goods_id + '" data-index="' + index + '" data-name="' + name + '" data-extra_pay_price="' + extra_price + '" data-max_num="' + maxNum + '" data-min_num="' + minNum + '" data-o_price="' + oldPrice + '" data-is_seckill="' + isSeckill + '">-</a>';
	div += '<input type="text" value="' + num + '" readonly="readonly">';
	div += '<a href="javascript:void(0)" class="jia" data-price="' + price + '" data-unit="' + unit + '"  data-limit_type="' + limit_type + '" data-stock="' + stock + '" data-packing_charge="' + packing_charge + '" data-goods_id="' + goods_id + '" data-index="' + index + '" data-name="' + name + '" data-extra_pay_price="' + extra_price + '" data-max_num="' + maxNum + '" data-min_num="' + minNum + '" data-o_price="' + oldPrice + '" data-is_seckill="' + isSeckill + '">+</a>';
	div += '</div>';
	div += '<div class="fr grid">￥' + price +(extra_price>0&&open_extra_price==1?'+'+extra_price+ExtraPirceName:'')+ '</div>';
	div += '</li>';
	return div;
}

function cartFunction(name, price, goods_id, index, type, productParam, productPackCharge, stock,extra_price, maxNum, oldPrice, isSeckill, minNum, unit, limit_type)
{
    if (isClose == 1) {
        layer.msg('店铺休息中');
        return false;
    }
	var this_index = format_cart_data(index);
	console.log(extra_price)
	var this_num = 0;
	
	
//	if (type == 'jia') {
//		if (this_index != null) {
//			this_num = goodsCart[this_index].count + 1;
//		} else {
//			this_num = 1;
//		}
//	} else {
//		this_num = goodsCart[this_index].count - 1;
//	}
//	if (stock != -1 && this_num > stock) {
//		alert('库存不足，不能购买！');
//		return false;
//	}
	
	if (this_index != null) {
		if (type == 'jia') {
		    var addNum = 1;
		    if (goodsCart[this_index].count >= minNum) {
		        this_num = goodsCart[this_index].count + 1;
		    } else {
		        this_num = minNum;
		        addNum = minNum
		    }
			if (stock != -1 && this_num > stock) {
				layer.msg('库存不足，不能购买！');
				return false;
			}
			
			if (maxNum > 0 && maxNum < this_num) {
	            if (isSeckill == 1 && limit_type == 0) {
	                layer.msg('每单可享受' + maxNum + unit + '限时优惠价，超出恢复原价');
	            } else {
	                if (limit_type == 0) {
	                    layer.msg('每单限购' + maxNum + unit);
	                } else {
	                    layer.msg('每个用户限购' + maxNum + unit);
	                }
	                return false;
	            }
                price = parseFloat(oldPrice);
            }
	        
			goodsCart[this_index].count = this_num;
			goodsNumber += addNum;
			goodsCartMoney += price * addNum;
			goodsCartPackCharge += productPackCharge * addNum;
		} else {
		    var reduceNum = 1;
		    if (goodsCart[this_index].count == minNum) {
		        this_num = 0;
		        reduceNum = minNum;
		    } else {
		        this_num = goodsCart[this_index].count - 1;
		    }
			
			if (stock != -1 && this_num > stock) {
				layer.msg('库存不足，不能购买！');
				return false;
			}
			if (maxNum > 0 && maxNum <= this_num) {
                price = parseFloat(oldPrice);
            }
			
			goodsCart[this_index].count = this_num;
			goodsNumber -= reduceNum;
			goodsCartMoney -= price * reduceNum;
			goodsCartPackCharge -= productPackCharge * reduceNum;
		}
	} else if (type == 'jia') {
		this_num = 1;
		if (minNum > 0) {
		    this_num = minNum;
		}
		if (stock != -1 && this_num > stock) {
			layer.msg('库存不足，不能购买！');
			return false;
		}
        
		
		
        if (maxNum > 0 && maxNum < this_num) {
            if (isSeckill == 1 && limit_type == 0) {
                layer.msg('每单可享受' + maxNum + unit + '限时优惠价，超出恢复原价');
            } else {
                if (limit_type == 0) {
                    layer.msg('每单限购' + maxNum + unit);
                } else {
                    layer.msg('每个用户限购' + maxNum + unit);
                }
                return false;
            }
        }
        
		goodsCart.push({
			'productId':goods_id,
			'count':this_num,
			'productName':name,
			'productStock':stock,
			'productPrice':price,
			'productExtraPrice':extra_price,
			'productPackCharge':productPackCharge,
            'maxNum':maxNum,
            'minNum':minNum,
            'unit':unit,
            'limit_type':limit_type,
            'isSeckill':isSeckill,
            'oldPrice':oldPrice,
			'productParam':productParam
			});
		goodsNumber += this_num;
		if (maxNum > 0 && maxNum < this_num) {
            price = parseFloat(oldPrice);
        }
		goodsCartMoney += price * this_num;
		goodsCartPackCharge += productPackCharge * this_num;
	}
	goodsCartMoney = parseFloat(goodsCartMoney.toFixed(2));
	goodsCartPackCharge = parseFloat(goodsCartPackCharge.toFixed(2));
	
	
	//记录上一次的商品唯一值
	goods_index_list[goods_id] = index;
//	console.log(productPackCharge);
//	console.log('total===============' + goodsCartPackCharge);
	var div = format_cart_html(this_num, name, price, goods_id, index, productPackCharge, stock,extra_price, maxNum, oldPrice, isSeckill, minNum);
	
	if (parseInt($(".carmiddle ul").find('.goods_' + index).size()) == 0) {
		if ($(".carmiddle ul").find('.goods_packing_charge').size() > 0) {
			$('.goods_packing_charge').before(div);
		} else {
			$(".carmiddle ul").append(div);
		}
	} else if (this_num > 0) {
		$('.goods_' + index).find('input').val(this_num);
	} else {
		$('.goods_' + index).remove();
	}
	
	if (goodsCartPackCharge > 0) {
		if ($(".carmiddle ul").find('.goods_packing_charge').size() > 0) {
			$('.goods_packing_charge').find('.grid').text('￥' + goodsCartPackCharge);
		} else {
			var div = '';
			div += '<li class="clr goods_packing_charge">';
			div += '<div class="fl cartitle">' + pack_alias + '</div>';
			div += '<div class="fr grid">￥' + goodsCartPackCharge + '</div>';
			div += '</li>';
			$(".carmiddle ul").append(div);
		}
	}
	
	if (this_num > 0) {
		$('.Popup .plus input').val(this_num);
		$('.Popup .purchase').hide();
		$('.Popup .plus').show();
	} else {
		$('.Popup .purchase').show();
		$('.Popup .plus').hide();
	}
	var tmp_extra_price=0;
	for (var i = 0; i < goodsCart.length; i++) {
		if(goodsCart[i].productExtraPrice>0){
			tmp_extra_price +=goodsCart[i].productExtraPrice*goodsCart[i].count;
		}
	};
	
	stringifyCart();
	if (goodsNumber > 0) {
		$(".carleft").addClass("carlefton");
		$(".common").show();
		
		if (is_pick == 1 || delivery_price <= goodsCartMoney) {
			$(".tencer").addClass("tenceron");
			$(".tencer").text("选好了").css("cursor", "pointer");
		} else {
			$(".tencer").removeClass("tenceron");
			var diff_price = parseFloat((parseFloat(delivery_price) - parseFloat(goodsCartMoney)).toFixed(2));
			$(".tencer").text('还差' + diff_price + '元起送').css("cursor", "default");
		}
		
		$(".car .cartop").show();
		$(".car .amount").html(goodsNumber).show();
		$(".car .carmiddle").show();
		if(tmp_extra_price>0){
			$('#total_price').html((goodsCartMoney + goodsCartPackCharge)+'+'+tmp_extra_price+ExtraPirceName);
			$('#total_price').css('font-size','10px');
		}else{
			$('#total_price').text(goodsCartMoney + goodsCartPackCharge);
		}
	} else {
		$(".carmiddle ul").html('');
		$(".carleft").removeClass("carlefton");
		$(".common, .cartop, .carmiddle, .amount").hide();
		$(".tencer").removeClass("tenceron");
		if (is_pick == 1) {
			$(".tencer").text("购物车是空的").css("cursor", "default");
		} else {
			$(".tencer").text(delivery_price + '元起送').css("cursor", "default");
		}
		
	}
	return true;
}

function stringifyCart()
{
	var cookieProductCart = [];
	for(var i in goodsCart){
		if (goodsCart[i].count > 0) {
			cookieProductCart.push(goodsCart[i]);
		}
	}
	$('#foodshop_cart').val(JSON.stringify(cookieProductCart));
	window.sessionStorage.setItem(cookie_index, JSON.stringify(cookieProductCart));
//	$.cookie(cookie_index, JSON.stringify(cookieProductCart), {expires:700,path:'/'});
}

function init_goods_menu()
{
//	var nowShopCart = $.parseJSON($.cookie(cookie_index));
	var nowShopCart = $.parseJSON(window.sessionStorage.getItem(cookie_index));
	$('#foodshop_cart').val(window.sessionStorage.getItem(cookie_index));
	console.log(nowShopCart)
	goodsCart = [];
	var cart_goods_html = '';
	for (var i in nowShopCart) {
		if (nowShopCart[i] != null && nowShopCart[i].count > 0) {
			var detail_name = '', goodsCartKey = nowShopCart[i].productId;
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
			if(nowShopCart[i].productParam!=''){
				nowShopCart[i].productExtraPrice=0;
			}
			cart_goods_html += format_cart_html(nowShopCart[i].count, nowShopCart[i].productName, nowShopCart[i].productPrice, nowShopCart[i].productId, goodsCartKey, nowShopCart[i].productPackCharge, nowShopCart[i].productStock,nowShopCart[i].productExtraPrice, nowShopCart[i].maxNum, nowShopCart[i].oldPrice, nowShopCart[i].isSeckill, nowShopCart[i].minNum, nowShopCart[i].unit, nowShopCart[i].limit_type);
			goodsNumber += parseInt(nowShopCart[i].count);
			if (nowShopCart[i].maxNum > 0 && parseInt(nowShopCart[i].count) > nowShopCart[i].maxNum) {
			    goodsCartMoney += parseFloat(nowShopCart[i].productPrice) * parseInt(nowShopCart[i].maxNum);
			    goodsCartMoney += parseFloat(nowShopCart[i].oldPrice) * (parseInt(nowShopCart[i].count) - parseInt(nowShopCart[i].maxNum));
			} else {
			    goodsCartMoney += parseFloat(nowShopCart[i].productPrice) * parseInt(nowShopCart[i].count);
			}
			
			goodsCartPackCharge += parseFloat(nowShopCart[i].productPackCharge) * parseInt(nowShopCart[i].count);
			goodsCart[i] = nowShopCart[i];
			goods_index_list[nowShopCart[i].productId] = goodsCartKey;
		}
	}
	$(".carmiddle ul").append(cart_goods_html);
	if (goodsCartPackCharge > 0) {
		var div = '';
		div += '<li class="clr goods_packing_charge">';
		div += '<div class="fl cartitle">' + pack_alias + '</div>';
		div += '<div class="fr grid">￥' + goodsCartPackCharge + '</div>';
		div += '</li>';
		$(".carmiddle ul").append(div);
	}
	if (goodsNumber > 0) {
		$(".carleft").addClass("carlefton");
		$(".common").show();
		if (is_pick == 1 || delivery_price <= goodsCartMoney) {
			$(".tencer").addClass("tenceron");
			$(".tencer").text("选好了").css("cursor", "pointer");
		} else {
			$(".tencer").removeClass("tenceron");
			var diff_price = parseFloat((parseFloat(delivery_price) - parseFloat(goodsCartMoney)).toFixed(2));
			$(".tencer").text('还差' + diff_price + '元起送').css("cursor", "default");
		}
		
//		$(".tencer").addClass("tenceron");
//		$(".tencer").text("选好了").css("cursor", "pointer");
		$(".car .cartop").show();
		$(".car .amount").html(goodsNumber).show();
		$(".car .carmiddle").show();
		$(".common").show();
		var tmp_extra_price=0;
		for (var i = 0; i < goodsCart.length; i++) {
			if(goodsCart[i].productExtraPrice>0){
				tmp_extra_price +=goodsCart[i].productExtraPrice*goodsCart[i].count;
			}
		};
		if(tmp_extra_price>0){
			$('#total_price').html((goodsCartMoney + goodsCartPackCharge)+'+'+tmp_extra_price+ExtraPirceName);
			$('#total_price').css('font-size','10px');
		}else{
			$('#total_price').text(goodsCartMoney + goodsCartPackCharge);
		}
	}
}