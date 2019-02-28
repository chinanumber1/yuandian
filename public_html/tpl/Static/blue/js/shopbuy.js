var tab = '', order = '', tips = '', timeout = 0, temp = '';
var productCart = [],productCartNumber = 0,productCartMoney=0;
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
				window.location.href = 'http://'+document.domain+'/shop/2.html';
			}
		});
	}
	
	
	//百度地图
    var position = new Object();
    position.lng = $.cookie('userLocationLong');
    position.lat = $.cookie('userLocationLat');

    var map = new BMap.Map("StoreMap");
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

    //var projection = map.getMapType().getProjection();
    //var shopPixel = new BMap.Pixel(108.951558, 34.902957);
    //var des = projection.pointToLngLat(shopPixel);
    map.setViewport([pt1, pt2]);
    map.enableScrollWheelZoom();
    map.enableContinuousZoom();
	
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
	$('.buygoods .close').on('click', function(event){
		$(this).closest('.spec-tip').hide();
		return false;
	});
	$('.buygoods').on('click', function(event){
		var src = $(this).find('img').attr('src');
		var offset = $('#i-shopping-cart').offset(), flyer = $('<img class="temp_image" src="'+src+'" width="70" height="70" style="z-index:10000"/>');
		var tmpDomObj = $(this).find('.buycar');
		var hasFormat = tmpDomObj.hasClass('hasFormat') ? true : false;
		if(hasFormat){
			$('.spec-tip').hide();
			$(this).find('.spec-tip').show();
			// if($(this).find('.type-spec').size() > 0){
				changeProductSpec($(this).find('.spec-tip'));
			// }
			return false;
		}
		var tmpSpec = parseInt(tmpDomObj.data('stock'));
		if(tmpSpec != -1 && productCart[tmpDomObj.data('id')] && productCart[tmpDomObj.data('id')]['count'] >= tmpSpec){
			alert('没有库存了');
			return false;
		}
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
		    	cartFunction('plus',tmpDomObj);
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
	$('.type-spec .s-item').on('click', function(event){
		$(this).addClass('sec').siblings().removeClass('sec');
		changeProductSpec($(this).closest('.spec-tip'));
	});
	$('strong.addtocart').on('click', function(event){
		var tmpDomObj = $(this).siblings('.select_count');
		var tmpSpec = parseInt(tmpDomObj.data('stock'));
		if(tmpSpec != -1 && productCart[tmpDomObj.data('key')] && productCart[tmpDomObj.data('key')]['count'] >= tmpSpec){
			alert('没有库存了');
			return false;
		}
		cartFunction('plus',tmpDomObj);
		return false;
	});
	$('.type-properties .s-item').on('click', function(event){
		var proNum = parseInt($(this).closest('tr').data('num'));
		if(proNum == 1){
			$(this).addClass('sec').siblings().removeClass('sec');
		}else{
			if(!$(this).hasClass('sec')){
				var tmpProSize = $(this).closest('tr').find('.sec').size();
				if(tmpProSize < proNum){
					$(this).addClass('sec');
				}else{
					alert('您最多只能选取'+proNum+'个');
				}
			}else{
				$(this).removeClass('sec');
			}
		}
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
		productCartNumber=0;
		productCartMoney=0;
		productCart=[];
		cartFunction('count');
	});
	$(document).on('click', '.minus', function(e){
		var obj = $(this).parents('li');
		var key = obj.data('key');
		
		productCartNumber -= 1;
		productCartMoney -= productCart[key]['productPrice'];
		
		if(productCart[key]['count'] == 1){
			delete productCart[key];
			obj.remove();
		}else{
			productCart[key]['count'] -=1;
			obj.find('.txt-count').html(productCart[key]['count']);
		}
		
		cartFunction('count');
	});
	
	$(document).on('click', '.del', function(e){
		var obj = $(this).parents('li');
		var key = obj.data('key');
		productCartNumber -= productCart[key]['count'];
		productCartMoney -= productCart[key]['count']*productCart[key]['productPrice'];
		delete productCart[key];
		obj.remove();
		cartFunction('count');
	});
	
	$(document).on('click', '.plus', function(e){
		var obj = $(this).parents('li');
		var key = obj.data('key');
		if(productCart[key]['productStock'] != -1 && productCart[key]['count'] >= productCart[key]['productStock']){
			alert('没有库存了');
			return false;
		}
		productCartNumber += 1;
		productCartMoney += productCart[key]['productPrice'];
		
		productCart[key]['count'] +=1;
		$('.productNum-'+obj.find('.txt-count').data('key')).html(productCart[key]['count']);
		
		cartFunction('count');
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
var productSpec = [];
function changeProductSpec(obj){
	var productId = obj.closest('li').attr('id');
	if(!productSpec[productId] && obj.data('spec_data')){
		productSpec[productId] = [];
		var tmpSpec = obj.data('spec_data');
		var tmpRow = tmpSpec.split(';');
		for(var i in tmpRow){
			var tmpData = tmpRow[i].split('|');
			var tmpKey = tmpData[0];
			productSpec[productId][tmpKey] = [];
			productSpec[productId][tmpKey].price = tmpData[1];
			productSpec[productId][tmpKey].spec = tmpData[2];
			if(tmpData[3]){
				productSpec[productId][tmpKey].properties = [];
				var tmpPro = tmpData[3].split(',');
				for(var i in tmpPro){
					var tmpPpro = tmpPro[i].split(':');
					productSpec[productId][tmpKey].properties[tmpPpro[0]] = parseInt(tmpPpro[1]);
				}
			}
		}
	}
	if(productSpec[productId]){
		var productSpecId = [productId];
		$.each(obj.find('tr.type-spec'),function(i,item){
			productSpecId.push($(item).find('span.sec').data('spec_list_id'));
		});
		var productSpecStr = productSpecId.join('_');
		// console.log(productSpec[productId][productSpecStr]);
		var dataObj = obj.closest('li');
		obj.find('.product_price').html(productSpec[productId][productSpecStr].price);	
		obj.find('.select_count').html($('.productNum-'+productSpecStr).html() ? parseInt($('.productNum-'+productSpecStr).html()) : '1').attr('class','select_count').addClass('productNum-'+productSpecStr);
		obj.find('.select_count').data('id',productId);
		obj.find('.select_count').data('key',productSpecStr);
		obj.find('.select_count').data('name',obj.data('name'));
		obj.find('.select_count').data('price',productSpec[productId][productSpecStr].price);
		obj.find('.select_count').data('stock',productSpec[productId][productSpecStr].spec);
	}else{
		var productSpecStr = productId;
		obj.find('.select_count').html($('.productNum-'+productSpecStr).html() ? parseInt($('.productNum-'+productSpecStr).html()) : '1').attr('class','select_count').addClass('productNum-'+productSpecStr);
		obj.find('.select_count').data('id',productId);
		obj.find('.select_count').data('key',productSpecStr);
		obj.find('.select_count').data('name',obj.data('name'));
		obj.find('.select_count').data('price',obj.find('.product_price').html());
		obj.find('.select_count').data('stock',obj.data('stock'));
	}
	// alert(productSpecStr);
}
function cartFunction(type,obj){
	if(type != 'count'){
		if(obj.hasClass('select_count')){
			var productKey = obj.data('key');
			
			var productParam = [];
			if(obj.closest('table').find('tr.type-spec').size() > 0){
				var productSpecListId = [],productSpecId = [],productSpecText = [];
				$.each(obj.closest('table').find('tr.type-spec'),function(i,item){
					productSpecListId.push($(item).find('span.sec').data('spec_list_id'));
					productSpecId.push($(item).find('span.sec').data('spec_id'));
					productSpecText.push($(item).find('span.sec').html());
				});
				for(var i in productSpecListId){
					productParam.push({'type':'spec','spec_id':productSpecId[i],'id':productSpecListId[i],'name':productSpecText[i]});
				}
			}
			if(obj.closest('table').find('tr.type-properties').size() > 0){
				$.each(obj.closest('table').find('tr.type-properties'),function(i,item){
					var tmpProductProperties = [];
					$.each($(item).find('span.sec'),function(j,jtem){
						tmpProductProperties.push($(jtem).html());
					});
					productParam.push({'type':'properties','name':tmpProductProperties});
				});
			}
			
		}else{
			var productKey = obj.data('id');
			var productParam = [];
		}
		var productStock = obj.data('stock');
		var productId = obj.data('id');
		var productName = obj.data('name');
		var productPrice = parseFloat(obj.data('price'));
		
	}
	if(type == 'plus'){
		var tmpStock = parseInt(obj.data('stock'));
		if(tmpStock != -1 && productCart[productKey] && productCart[productKey]['count'] >= tmpStock){
			alert('没有库存了');
			return false;
		}
		
		if(productCart[productKey]){
			productCart[productKey]['count']++;
			$('.productNum-'+productKey).html(productCart[productKey]['count']);
		}else{
			productCart[productKey] = {
				'productId':productId,
				'productName':productName,
				'productPrice':productPrice,
				'productStock':productStock,
				'productParam':productParam,
				'count':1,
			};
			var html = '';
			html += '<li class="clearfix  food-' + productKey + '" data-key="' + productKey + '" data-fid="' + productId + '" data-price="' + productPrice + '">';
			html += '<div class="fl na" title="' + productName + '">' + productName + '</div>';
			html += '<div class="fl modify clearfix">';
			html += '<a href="javascript:;" class="fl del">×</a><a href="javascript:;" class="fl minus">-</a>';
			html += '<span class="fl txt-count productNum-'+productKey+'" data-key="'+productKey+'">1</span>';
			html += '<a href="javascript:;" class="fl plus">+</a>';
			html += '</div>';
			html += '<div class="fl pri">';   
			html += '<span>¥' + productPrice + '</span>';
			html += '</div>';
			html += '</li>';
			$('.order-list ul').append(html);
		}
		productCartNumber++;
		productCartMoney = productCartMoney+productPrice;
	}
	
	$('.totalnumber').html(productCartNumber);
	$('.total .bill').html("￥"+productCartMoney.toFixed(2));
	
	var tmpSize = $('.order-list ul li').size()-1;
	if(tmpSize > 5){
		tmpSize = 5;
		$('.order-list ul').css({'height':'300px','overflow-y':'scroll'});
	}else{
		$('.order-list ul').css({'height':'','overflow-y':''});
	}
	$('.order-list').css('top', '-'+(129+tmpSize*50)+'px');
	if(productCartNumber > 0){
		$('.ready-pay').hide();
		$('.go-pay').show();
	}else{
		$('.order-list').css('top','0px');
		$('.order-list ul').empty();
		$('.go-pay').hide();
		$('.ready-pay').show();
	}
	
	stringifyCart();
}

function stringifyCart(){
	var cookieProductCart = [];
	for(var i in productCart){
		cookieProductCart.push(productCart[i]);
	}
	$.cookie('shop_cart_'+store_id,JSON.stringify(cookieProductCart),{expires:700,path:'/'});
}

//stroe_id:meal_id,num|
function init(){
	var nowShopCart = $.cookie('shop_cart_'+store_id);
	if(nowShopCart){
		nowShopCartArr = $.parseJSON(nowShopCart);
		productCart=[];
		if(nowShopCartArr.length > 0){
			productCartNumber = productCartMoney = 0;
			for(var i in nowShopCartArr){
				var tmpSpec = [];
				var tmpObj = nowShopCartArr[i].productParam;
				if(tmpObj.length > 0){
					for(var j in tmpObj){
						if(tmpObj[j].type == 'spec'){
							tmpSpec.push(tmpObj[j].id);
						}
					}
					if(tmpSpec.length > 0){
						var tmpSpecStr = nowShopCartArr[i].productId + '_' + tmpSpec.join('_');
						productCart[tmpSpecStr] = nowShopCartArr[i];
					}else{
						productCart[nowShopCartArr[i].productId] = nowShopCartArr[i];
					}
				}else{
					productCart[nowShopCartArr[i].productId] = nowShopCartArr[i];
				}
				productCartNumber += nowShopCartArr[i].count;
				productCartMoney += nowShopCartArr[i].count * nowShopCartArr[i].productPrice;
			}
			
			var html = '';
			for(var i in productCart){
				html += '<li class="clearfix  food-' + i + '" data-key="' + i + '" data-fid="' + productCart[i].productId + '" data-price="' + productCart[i].productPrice + '">';
				html += '<div class="fl na" title="' + productCart[i].productName + '">' + productCart[i].productName + '</div>';
				html += '<div class="fl modify clearfix">';
				html += '<a href="javascript:;" class="fl del">×</a><a href="javascript:;" class="fl minus">-</a>';
				html += '<span class="fl txt-count productNum-' + i + '" data-key="'+i+'">'+productCart[i].count+'</span>';
				html += '<a href="javascript:;" class="fl plus">+</a>';
				html += '</div>';
				html += '<div class="fl pri">';   
				html += '<span>¥' + productCart[i].productPrice + '</span>';
				html += '</div>';
				html += '</li>';
			}
			$('.order-list ul').html(html);
			cartFunction('count');
		}
	}else{
		cartFunction('count');
	}
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