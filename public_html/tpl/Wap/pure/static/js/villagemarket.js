var myScroll;
var isApp = motify.checkApp();
// alert(document.referrer);
var nowShop = {},flyer = $('<div class="shopCartFly"></div>'),nowProduct={},productSwiper = null,productPicList = [];
var window_width = $(window).width();
var window_height = $(window).height();
$(function(){
	$('#scroller').css({'min-height':($(window).height()-36-57-41+1)+'px'});
    if(isApp){
        $('#container').css({'top':'0px'});
        $('#container,#scroller').css({'position':'static'});
        $('body').append('<style>::-webkit-scrollbar{width:0px;}</style>');
    }else{
		$('#container').css({'bottom':'57px'});
        myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
    }
	
	
	$('#shopCatBar .title,#shopPageCatShade').click(function(){
		if($('#shopCatBar .title').hasClass('show')){
			$('#shopCatBar .title').removeClass('show');
			$('#shopCatBar .content').hide();
			$('#shopPageCatShade').hide();
		}else{
			$('#shopCatBar .title').addClass('show');
			$('#shopCatBar .content').show();
			$('#shopPageCatShade').show();
		}
	});
	$(document).on('click','#shopCatBar .content li',function(){
		$(this).addClass('active').siblings().removeClass('active');
		$('#shopCatBar .title').removeClass('show');
		$('#shopCatBar .content').hide();
		$('#shopPageCatShade').hide();
		// alert('#shopProductBottomBar li.product_cat_'+$(this).data('cat_id'));
		$('#shopCatBar .title').html($(this).html());
		if($(this).data('cat_id') == '0'){
			$('#shopProductBottomBar li').show();
		}else{
			$('#shopProductBottomBar li').hide();
			$('#shopProductBottomBar li.product_cat_'+$(this).data('cat_id')).show();
		}
		if(!isApp){
			myScroll.refresh();
		}
	});
	
	$('#cartBar').click(function(){
		if($('#cartBarNumber').html() == '0'){
			motify.log('购物车是空的');
			return false;
		}
		$('#shopProductCart').show();
		$('#shopProductCartShade').show();
		$('#shopProductCartBox').css('max-height',(window_height-50)/3*2+'px');
		laytpl($('#productCartBoxTpl').html()).render(productCart, function(html){
			$('#shopProductCartBox').html(html);
		});
	});
	$('#shopProductCartShade').click(function(){
		$(this).hide();
		$('#shopProductCart').hide();
		$('#shopProductCartBox').empty();
	});
	$(document).on('click','#shopProductCartDel',function(){
		layer.open({
			content: '您确定要清空购物车吗？',
			btn: ['确认', '取消'],
			shadeClose: false,
			yes: function(){
				$('#shopProductBottomBar .product_btn.min,#shopProductBottomBar .product_btn.number').remove();
				$('#shopDetailPageBuy').show();
				$('#shopDetailPageNumber').hide();
				productCart = [];
				productCartNumber = 0;
				productCartMoney = 0;
				cartFunction('count');
				layer.closeAll();
				$('#shopProductCartShade').trigger('click');
			}, no: function(){
				
			}
		});
	});
	$(document).on('click','#shopProductBottomBar .product_btn.plus',function(event){
		if(nowShop.store.is_close == 1){
			motify.log('店铺休息中');
			return false;
		}
		tmpDomObj = $(this);

		var intStock = parseInt(tmpDomObj.closest('li').data('stock'));
		if(intStock != -1 && (intStock == 0 || intStock - parseInt(tmpDomObj.siblings('.number').html()) <= 0)){
			motify.log('没有库存了');
			return false;
		}
		
		if(motify.checkApp()){
			flyer.fly({
				start: {
					left: event.pageX-10,
					top: event.pageY-120
				},
				end: {
					left: 20,
					top: window_height-120,
					width: 20,
					height: 20
				},
				onEnd:function(){
					cartFunction('plus',tmpDomObj,tmpDomObj.closest('li'));
					flyer.remove();
				}
			});
		}else{
			cartFunction('plus',tmpDomObj,tmpDomObj.closest('li'));
		}
		return false;
	});
	$(document).on('click','#shopProductCartBox .product_btn.plus',function(event){
		if(nowShop.store.is_close == 1){
			motify.log('店铺休息中');
			return false;
		}
		tmpDomObj = $(this);
		cartFunction('plus',tmpDomObj,tmpDomObj.closest('dd'));
	});	
	$(document).on('click','#shopProductCartBox .product_btn.min',function(event){
		tmpDomObj = $(this);
		cartFunction('min',tmpDomObj,tmpDomObj.hasClass('cart') ? tmpDomObj.closest('dd') : tmpDomObj.closest('li'));
		return false;
	});
	$('#checkCart').click(function(){
		window.location.href = check_cart_url;
	});
	$('#shopBanner').click(function(){
		$('#shopZoomInfoBox').show();
	});	
	$('#shopZoomInfoBoxClose').click(function(){
		$('#shopZoomInfoBox').hide();
	});
	$.getJSON(ajax_url_root+'ajax_shop',{store_id:shopId},function(result){
		if(!result.store){
			motify.log('店铺不存在或未开启',0);
			return false;
		}
		if(!result.product_list){
			motify.log('店铺未添加商品',0);
			return false;
		}
		$('#shopNoticeText').html(result.store.store_notice);
		// $('#shopCouponText').html(parseCoupon(result.store.coupon_list,'text')+';'+result.store.store_notice);
		$('#shopCouponText').html(parseCoupon(result.store.coupon_list,'text'));
		textScroll($('#shopCouponText'),$('#shopCouponBox'));
		
		nowShop = result;
		
		if(nowShop.product_list){
			laytpl($('#shopProductTopBarTpl').html()).render(nowShop.product_list, function(html){
				$('#shopCatBar .content ul').html(html);
			});
			laytpl($('#shopProductBottomBarTpl').html()).render(nowShop.product_list, function(html){
				$('#shopProductBottomBar ul').html(html);
			});
			$('#shopProductBottomBar .position_img').height($('#shopProductBottomBar .position_img:eq(0)').width());
			// $('#shopProductBottomBar li').css('margin-top',window_width*0.02);
			// $('#shopProductBottomBar ul').css('margin-bottom',window_width*0.02);
			
			if(!isApp){
				myScroll.refresh();
			}
		}
		
		$('#shopZoomInfo').css({'margin-top':window_height*0.1+'px','height':window_height*0.7+'px'});
		var tmpCouponList = parseCoupon(nowShop.store.coupon_list,'array');
		var tmpCouponHtml = '';
		if(tmpCouponList['invoice']){
			tmpCouponHtml+= '<dd><em class="merchant_invoice"></em>'+tmpCouponList['invoice']+'</dd>';
		}
		if(tmpCouponList['discount']){
			tmpCouponHtml+= '<dd><em class="merchant_discount"></em>'+tmpCouponList['discount']+'</dd>';
		}
		if(tmpCouponList['minus']){
			tmpCouponHtml+= '<dd><em class="merchant_minus"></em>'+tmpCouponList['minus']+'</dd>';
		}
		if(tmpCouponList['newuser']){
			tmpCouponHtml+= '<dd><em class="newuser"></em>'+tmpCouponList['newuser']+'</dd>';
		}
		if(tmpCouponList['delivery']){
			tmpCouponHtml+= '<dd><em class="delivery"></em>'+tmpCouponList['delivery']+'</dd>';
		}
		if(tmpCouponList['system_minus']){
			tmpCouponHtml+= '<dd><em class="system_minus"></em>'+tmpCouponList['system_minus']+'</dd>';
		}
		if(tmpCouponList['system_newuser']){
			tmpCouponHtml+= '<dd><em class="system_newuser"></em>'+tmpCouponList['system_newuser']+'</dd>';
		}
		$('#shopZoomInfoCoupon dl').append(tmpCouponHtml);
		$('#shopZoomInfoNotice dl').append('<dd>'+nowShop.store.store_notice+'</dd>');
		
		
		
		var nowShopCart = $.cookie('shop_cart_'+nowShop.store.id);
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
						$('.product_'+nowShopCartArr[i].productId+' .plus').after('<div class="product_btn number productNum-'+nowShopCartArr[i].productId+'">'+nowShopCartArr[i].count+'</div>').after('<div class="product_btn min"></div>');
					}
					productCartNumber += nowShopCartArr[i].count;
					productCartMoney += nowShopCartArr[i].count * nowShopCartArr[i].productPrice;
				}
				
				//统计购物车功能
				cartFunction('count');
			}
			// console.log(productCart);
		}else{
			cartFunction('count');
		}
	});
	$(document).on('click','#shopDetailPageNumber .product_btn.plus,#shopDetailPageBuy',function(event){
		if(nowShop.store.is_close == 1){
			motify.log('店铺休息中');
			return false;
		}
		var intStock = parseInt($('#shopDetailPagePrice span').data('stock'));
		if(intStock != -1 && (intStock == 0 || intStock - parseInt($('#shopDetailPageNumber .number').html()) <= 0)){
			motify.log('没有库存了');
			return false;
		}
		tmpDomObj = $(this);
		flyer.fly({
			start: {
				left: event.pageX-10,
				top: event.pageY-120
			},
			end: {
				left: 20,
				top: window_height-120,
				width: 20,
				height: 20
			},
			onEnd:function(){
				cartFunction('plus',tmpDomObj,'productPage');
				flyer.remove();
			}
		});
		return false;
	});
	$(document).on('click','#shopDetailPageNumber .product_btn.min',function(event){
		tmpDomObj = $(this);
		cartFunction('min',tmpDomObj,'productPage');
		return false;
	});
	$('#shopDetailpageClose').click(function(){
		$('#shopDetailPage').removeClass('sliderLeft');
		$('body').css('overflow-y','auto');
	});
	$(document).on('click','#shopDetailPageFormat li',function(event){
		$(this).addClass('active').siblings('li').removeClass('active');
		changeProductSpec();
	});
	
	$(document).on('click','#shopDetailPageLabel li',function(event){
		var maxSize = $(this).closest('.row').data('num');
		if(maxSize == 1){
			$(this).addClass('active').siblings('li').removeClass('active');
		}else if(!$(this).hasClass('active')){
			var tmpActiveSize = $(this).closest('ul').find('.active').size();
			if(tmpActiveSize >= maxSize){
				motify.log($(this).closest('.row').data('label_name')+' 您最多能选择 '+maxSize+' 个');
			}else{
				/* if(tmpActiveSize == maxSize-1){
					motify.log('您最多能选择 '+maxSize+' 个，现在已经选择满了');
				} */
				$(this).addClass('active');
			}
		}else{
			$(this).removeClass('active');
		}
	});
	$(document).on('click','#shopProductRightBar li,#shopProductBottomBar li',function(event){
		pageLoadTip();
		$('body').css('overflow','hidden');
		/* $('#shopDetailPage').height(window_height-50); */
		
		if(nowShop.store.store_theme == '0'){
			$('#shopDetailPageImgbox').css({height:window_width*500/900,width:window_width});
		}else if(nowShop.store.store_theme == '1'){
			$('#shopDetailPageImgbox').css({height:window_width,width:window_width});
		}
		if($(this).data('product_id') == nowProduct.goods_id){
			// shopDetailPageIscroll = new IScroll('#shopDetailPage', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false});
			$('#shopDetailPage').scrollTop(0);
			$('#shopDetailPage').addClass('sliderLeft');	
			pageLoadTipHide();
		}else{
			$.getJSON(ajax_url_root+'ajax_goods',{goods_id:$(this).data('product_id')},function(result){
				nowProduct = result;
				productPicList = [];
				for(var i in result.pic_arr){
					productPicList.push(result.pic_arr[i].url);
				}
				// console.log(productPicList);
				laytpl($('#productSwiperTpl').html()).render(result.pic_arr, function(html){
					$('#shopDetailPageImgbox .swiper-wrapper').removeAttr('style').html(html);
					if(productSwiper != null){
						productSwiper.reInit();
					}
					if(result.pic_arr.length > 1){
						productSwiper = $('#shopDetailPageImgbox').swiper({
							pagination:'#shopDetailPageImgbox .swiper-pagination-productImg',
							loop:true,
							grabCursor: true,
							paginationClickable: true,
							autoplay:3000,
							autoplayDisableOnInteraction:false,
							simulateTouch:false
						});
					}
				});
				$('#shopDetailPageTitle .title').html(result.name);
				// $('#shopDetailPageTitle .desc').html('月售'+result.sell_count+'份 好评'+result.reply_count+'');
				// $('#shopDetailPageTitle .desc').html('月售'+result.sell_count+'份');
				$('#shopDetailPageTitle .desc').hide();
				$('#shopDetailPageFormat').empty();
				if(result.des != ''){
					$('#shopDetailPageContent .content').html(result.des).show();
					$('#shopDetailPageContent').show();
				}else if(nowShop.store.delivery){
					$('#shopDetailPageContent .content').html('温馨提示：图片仅供参考，请以实物为准；高峰时段及恶劣天气，请提前下单。').show();
					$('#shopDetailPageContent').show();
				}else{
					$('#shopDetailPageContent').hide();
				}
				$('#shopDetailPagePrice').html('￥'+result.price+'<span class="unit"><em>/ </em>'+result.unit+'</span>'+(result.stock_num != -1 ? '<span data-stock="'+result.stock_num+'">还剩'+result.stock_num+result.unit+'</span>' : '<span data-stock="-1"></span'));
				if(result.properties_list){
					laytpl($('#productPropertiesTpl').html()).render(result.properties_list, function(html){
						$('#shopDetailPageLabelBox').html(html);
					});
					$('#shopDetailPageLabel').show();
				}else{
					$('#shopDetailPageLabel').hide();
				}		
				if(result.spec_list){
					laytpl($('#productFormatTpl').html()).render(result.spec_list, function(html){
						$('#shopDetailPageFormat').html(html);
					});
				}
				$('#shopDetailPageNumber .number').addClass('productNum-'+result.goods_id);
				
				changeProductSpec();
				
				// shopDetailPageIscroll = new IScroll('#shopDetailPage', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false});
				
				$('#shopDetailPage').scrollTop(0);
				$('#shopDetailPage').addClass('sliderLeft');
				pageLoadTipHide();
			});
		}
	});
});

var ScrollTime;
function textScroll(obj,parentObj){
	var textwidth = obj.width();
	ScrollTime = setInterval(function(){
		var currPos = parseInt(obj.css('margin-left'));
		if(currPos<0 && Math.abs(currPos)>textwidth){
			var showWidth = parentObj.width();
			obj.css('margin-left',showWidth);
		}else{
			obj.css('margin-left',currPos-1);
		}
	},20);
}

function parseCoupon(obj,type){
	var returnObj = {};
	for(var i in obj){
		if(typeof(obj[i]) == 'object'){
			returnObj[i] = [];
			for(var j in obj[i]){
				returnObj[i].push('满'+obj[i][j].money+'元减'+obj[i][j].minus+'元');
			}
		}else if(i=='invoice'){
			returnObj[i] = '满'+obj[i]+'元支持开发票，请在下单时填写发票抬头';
		}else if(i=='discount'){
			returnObj[i] = '店内全场'+obj[i]+'折';
		}
	}
	var textObj = [];
	for(var i in returnObj){
		if(typeof(returnObj[i]) == 'object'){
			switch(i){
				case 'system_newuser':
					textObj[i] = '平台首单'+returnObj[i].join(',');
					break;
				case 'system_minus':
					textObj[i] = '平台优惠'+returnObj[i].join(',');
					break;
				case 'newuser':
					textObj[i] = '店铺首单'+returnObj[i].join(',');
					break;
				case 'minus':
					textObj[i] = '店铺优惠'+returnObj[i].join(',');
					break;
				case 'system_minus':
					textObj[i] = '平台优惠'+returnObj[i].join(',');
					break;
				case 'delivery':
					textObj[i] = '配送费'+returnObj[i].join(',');
					break;
			}
		}else if(i=='invoice' || i=='discount'){
			textObj[i] = returnObj[i];
		}
	}
	if(type == 'text'){
		var tmpObj = [];
		for(var i in textObj){
			tmpObj.push(textObj[i]);
		}
		return tmpObj.join(';');
	}else{
		return textObj;
	}
}

function changeProductSpec(){
	$('#shopDetailPageNumber .number').html('0');
	if(nowProduct.spec_list){
		var productSpecId = [];
		$.each($('#shopDetailPageFormat .row'),function(i,item){
			productSpecId.push($(item).find('li.active').data('spec_list_id'));
		});
		var productSpecStr = productSpecId.join('_');
		var nowProductSpect = nowProduct.list[productSpecStr];
		$('#shopDetailPagePrice').html('￥'+nowProductSpect.price+'<span class="unit"><em>/ </em>'+nowProduct.unit+'</span>'+(nowProductSpect.stock_num != -1 ? '<span data-stock="'+nowProductSpect.stock_num+'">剩下'+nowProductSpect.stock_num+nowProduct.unit+'</span>' : '<span data-stock="-1"></span>'));
		
		if(nowProduct.properties_list){
			for(var i in nowProductSpect.properties){
				$('.productProperties_'+nowProductSpect.properties[i].id).data('num',nowProductSpect.properties[i].num);
			}
		}
		var nowProductCartLabel = nowProduct.goods_id + '_' + productSpecStr;
	}else{
		var nowProductCartLabel = nowProduct.goods_id;
	}
	$('#shopDetailPageNumber .number').attr('class','product_btn number');
	$('#shopDetailPageNumber .number').addClass('productNum-'+nowProductCartLabel);
	if(productCart[nowProductCartLabel]){
		$('#shopDetailPageNumber').show();
		$('#shopDetailPageNumber .number').html(productCart[nowProductCartLabel].count);
		$('#shopDetailPageBuy').hide();
	}else{
		$('#shopDetailPageNumber').hide();
		$('#shopDetailPageBuy').show();
	}
}

var productCart = [],productCartNumber = 0,productCartMoney=0;
function cartFunction(type,obj,dataObj){
	if(dataObj == 'productPage'){
		var productId = nowProduct.goods_id;
		var productName = nowProduct.name;
		if(nowProduct.spec_list){
			var productSpecListId = [],productSpecId = [],productSpecText = [];
			$.each($('#shopDetailPageFormat .row'),function(i,item){
				productSpecListId.push($(item).find('li.active').data('spec_list_id'));
				productSpecId.push($(item).find('li.active').data('spec_id'));
				productSpecText.push($(item).find('li.active').html());
			});
			var productSpecStr = productSpecListId.join('_');

			var productKey = productId + '_' + productSpecStr;
			var nowProductSpect = nowProduct.list[productSpecStr];
			var productPrice = parseFloat(nowProductSpect.price);
			var productStock = nowProductSpect.stock_num;
			
			var productParam = [];
			for(var i in productSpecListId){
				productParam.push({'type':'spec','spec_id':productSpecId[i],'id':productSpecListId[i],'name':productSpecText[i]});
			}
		}else{
			var productKey = productId;
			var productPrice = nowProduct.price;
			var productParam = [];
			var productStock = nowProduct.stock_num;
		}
		if(nowProduct.properties_list){
			$.each($('#shopDetailPageLabelBox .row'),function(i,item){
				var tmpProductProperties = [];
				$.each($(item).find('li.active'),function(j,jtem){
					tmpProductProperties.push($(jtem).html());
				});
				productParam.push({'type':'properties','name':tmpProductProperties});
			});
		}
	}else if(type != 'count'){
		if(dataObj.hasClass('cartDD') && dataObj.find('.cartLeft').hasClass('hasSpec')){
			var productKey = dataObj.find('.spec').data('product_id');
			var productStock = dataObj.find('.spec').data('stock');
		}else{
			var productKey = dataObj.data('product_id');
			var productStock = dataObj.data('stock');
		}
		var productId = dataObj.data('product_id');
		var productName = dataObj.data('product_name');
		var productPrice = parseFloat(dataObj.data('product_price'));
		var productParam = [];
	}

	if(type == 'plus'){
		if(dataObj != 'productPage' && dataObj.hasClass('cartDD')){
			var tmpStock = parseInt(dataObj.data('stock'));
			if(tmpStock != -1 && productCart[productKey] && productCart[productKey]['count'] >= tmpStock){
				motify.log('没有库存了');
				return false;
			}
		}
		$('#cartBar').addClass('bound');
		setTimeout(function(){
			$('#cartBar').removeClass('bound');
		},500);
		if(productCart[productKey]){
			productCart[productKey]['count']++;
			$('.productNum-'+productKey).html(productCart[productKey]['count']);
		}else{
			if(dataObj == 'productPage'){
				$('#shopDetailPageBuy').hide();
				$('#shopDetailPageNumber').show();
				$('#shopDetailPageNumber .number').html('1');
				
				$('.product_'+productId+' .plus').after('<div class="product_btn number productNum-'+productId+'">1</div>').after('<div class="product_btn min"></div>');
				
			}else{
				obj.after('<div class="product_btn number productNum-'+productId+'">1</div>');
				obj.after('<div class="product_btn min"></div>');
			}
			productCart[productKey] = {
				'productId':productId,
				'productName':productName,
				'productPrice':productPrice,
				'productStock':productStock,
				'productParam':productParam,
				'count':1,
			};
		}
		productCartNumber++;
		productCartMoney = productCartMoney+productPrice;
	}else if(type == 'min'){
		$('#shopProductCart .cart').addClass('bound');
		setTimeout(function(){
			$('#shopProductCart .cart').removeClass('bound');
		},500);
		if(productCart[productKey].count == 1){
			if(dataObj == 'productPage'){
				$('#shopDetailPageBuy').show();
				$('#shopDetailPageNumber').hide();
				$('#shopDetailPageNumber .number').html('0');
			}else{
				obj.siblings('.number').remove();
				obj.remove();
				if(dataObj.hasClass('cartDD')){
					dataObj.remove();
					$('#shopProductRightBar .productNum-'+productKey).siblings('.min').remove();
					$('#shopProductRightBar .productNum-'+productKey).remove();
					$('#shopProductBottomBar .productNum-'+productKey).siblings('.min').remove();
					$('#shopProductBottomBar .productNum-'+productKey).remove();
					$('#shopDetailPageBuy').show();
					$('#shopDetailPageNumber').hide();
					$('#shopDetailPageNumber .number').html('0');
				}
			}
			delete productCart[productKey];
		}else{
			productCart[productKey]['count']--;
			$('.productNum-'+productKey).html(productCart[productKey]['count']);
		}
		productCartNumber--;
		productCartMoney = productCartMoney - productPrice;
	}
	
	
	$('#shopProductCart #cartNumber,#cartBarNumber').html(productCartNumber);
	$('#shopProductCart #cartMoney').html(productCartMoney.toFixed(2));
	
	if(productCartNumber == 0){
		$('#checkCartEmpty').removeClass('noEmpty').show().html((nowShop.store.delivery_price).toFixed(2)+'元起送');
		$('#checkCart').removeClass('noEmpty').hide();	
		
	}else if(nowShop.store.delivery == true && parseFloat(productCartMoney.toFixed(2)) < nowShop.store.delivery_price){
		$('#checkCart').hide();
		$('#checkCartEmpty').addClass('noEmpty').show().html('还差￥'+(nowShop.store.delivery_price - parseFloat(productCartMoney.toFixed(2))).toFixed(2)+'起送');
	}else{
		$('#checkCartEmpty').hide();
		$('#checkCart').show();	
	}
	
	if(productCartNumber > 0){
		$('#shopProductCart #emptyCart').hide();
		$('#shopProductCart #cartInfo').show();
	}else{
		if($('#cartInfo').hasClass('isShow')){
			$('#shopProductCartShade').trigger('click');
		}
		$('#shopProductCart #cartInfo').hide();
		$('#shopProductCart #emptyCart').show();
	}
	// console.log(productCart);
	stringifyCart();
	// console.log($.cookie('shop_cart_'+nowShop.store.id));
}
function stringifyCart(){
	var cookieProductCart = [];
	for(var i in productCart){
		cookieProductCart.push(productCart[i]);
	}
	$.cookie('shop_cart_'+nowShop.store.id,JSON.stringify(cookieProductCart),{expires:700,path:'/'});
}

/*! fly - v1.0.0 - 2014-12-22
 * https://github.com/amibug/fly
 * Copyright (c) 2014 wuyuedong; Licensed MIT */
!function (a) {
    a.fly = function (b, c) {
        var d = {
            version: "1.0.0",
            autoPlay: !0,
            vertex_Rtop: 20,
            speed: 1.2,
            start: {},
            end: {},
            onEnd: a.noop
        }, e = this, f = a(b);
        e.init = function (a) {
            this.setOptions(a), !!this.settings.autoPlay && this.play()
        }, e.setOptions = function (b) {
            this.settings = a.extend(!0, {}, d, b);
            var c = this.settings, e = c.start, g = c.end;
            f.css({
                marginTop: "0px",
                marginLeft: "0px",
                position: "fixed"
            }).appendTo("body"), null != g.width && null != g.height && a.extend(!0, e, {
                width: f.width(),
                height: f.height()
            });
            var h = Math.min(e.top, g.top) - Math.abs(e.left - g.left) / 3;
            h < c.vertex_Rtop && (h = Math.min(c.vertex_Rtop, Math.min(e.top, g.top)));
            var i = Math.sqrt(Math.pow(e.top - g.top, 2) + Math.pow(e.left - g.left, 2)), j = Math.ceil(Math.min(Math.max(Math.log(i) / .05 - 75, 30), 100) / c.speed), k = e.top == h ? 0 : -Math.sqrt((g.top - h) / (e.top - h)), l = (k * e.left - g.left) / (k - 1), m = g.left == l ? 0 : (g.top - h) / Math.pow(g.left - l, 2);
            a.extend(!0, c, {count: -1, steps: j, vertex_left: l, vertex_top: h, curvature: m})
        }, e.play = function () {
            this.move()
        }, e.move = function () {
            var b = this.settings, c = b.start, d = b.count, e = b.steps, g = b.end, h = c.left + (g.left - c.left) * d / e, i = 0 == b.curvature ? c.top + (g.top - c.top) * d / e : b.curvature * Math.pow(h - b.vertex_left, 2) + b.vertex_top;
            if (null != g.width && null != g.height) {
                var j = e / 2, k = g.width - (g.width - c.width) * Math.cos(j > d ? 0 : (d - j) / (e - j) * Math.PI / 2), l = g.height - (g.height - c.height) * Math.cos(j > d ? 0 : (d - j) / (e - j) * Math.PI / 2);
                f.css({width: k + "px", height: l + "px", "font-size": Math.min(k, l) + "px"})
            }
            f.css({left: h + "px", top: i + "px"}), b.count++;
            var m = window.requestAnimationFrame(a.proxy(this.move, this));
            d == e && (window.cancelAnimationFrame(m), b.onEnd.apply(this))
        }, e.destory = function () {
            f.remove()
        }, e.init(c)
    }, a.fn.fly = function (b) {
        return this.each(function () {
            void 0 == a(this).data("fly") && a(this).data("fly", new a.fly(this, b))
        })
    }
}(jQuery);