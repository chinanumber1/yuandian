var nowPage='';
var window_width = $(window).width();
var window_height = $(window).height();
var categoryList=null,sortList=null,typeList=null,choosePage='list';
var listShopList=[],listNavBarTop=0,isShowShade = false,mustShowShopList=false,isListShow = false;
$(function(){
	FastClick.attach(document.body);
	$(document).on('click','.hasMore',function(){
		$(this).toggleClass('showMore');
		return false;
	});
	
	/*页面点击事件*/
	$(document).on('click','.page-link',function(){
		redirectPage(ajax_url_root+'classic_'+$(this).data('url'));
		return false;
	});
	
	var listHeaderColor = $('#listHeader').css('background-color').match(/\(.*\)/);
	var listHeaderColor = listHeaderColor[0].replace('(','').replace(')','');
	$('#listHeader').css('background-color','rgba('+listHeaderColor+',0)');
	
	listNavBarTop = $('#listNavBox').offset().top - 50;
	/*防止重复初始化JS*/
	if(motify.checkIos()){
		$('body').on('touchmove',function(){
			if(isShowShade == false){
				scrollListEvent('ios');
			}
		});
		$(window).scroll(function(){
			$('body').trigger('touchmove');
		});
	}else{
		$(window).scroll(function(){
			scrollListEvent('android');
		});
	}
	function scrollListEvent(phoneType){
		if(nowPage == 'list'){
			if(isShowShade == true){
				close_dropdown();
				return false;
			}
			var scrollTop = $(window).scrollTop();
			if(scrollTop > 50){
				$('#listHeader').removeClass('roundBg');
			}else{
				$('#listHeader').addClass('roundBg');
			}
			if(scrollTop > 150){
				$('#listHeader').css('background-color','rgb('+listHeaderColor+')');
			}else{
				$('#listHeader').css('background-color','rgba('+listHeaderColor+','+(scrollTop/100)+')');
			}
			if(scrollTop >= listNavBarTop){
				$('#listNavBox').addClass('fixed');
				$('#listNavPlaceHolderBox').show();
			}else{
				$('#listNavBox').removeClass('fixed');
				$('#listNavPlaceHolderBox').hide();
			}
			
			if(isListShow == false && listHasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height() - 50){
				showShopList();
			}
		}
	}
	
	if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
		if(motify.checkAndroid()){
			var locations = window.lifepasslogin.getLocation(false);
			var locationArr = locations.split(',');
			user_long = $.trim(locationArr[0]);
			user_lat = $.trim(locationArr[1]);
			$('#locationText').html($.trim(locationArr[2]));
			$('#listBackBtn').removeClass('hide').click(function(){
				window.lifepasslogin.webViewGoBack();
			});
			pageLoadHides();
			showShopList(true);
			showListData();
		}else{
			$('body').append('<iframe src="pigcmso2o://getLocation/false" style="display:none;"></iframe>');
			$('#listBackBtn').removeClass('hide').click(function(){
				$('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>'); 
			});
		}
	}else if($.cookie('userLocationName') && $.cookie('userLocationLong') && $.cookie('userLocationLat')){
		user_long = $.cookie('userLocationLong');
		user_lat = $.cookie('userLocationLat');
		$('#locationText').html($.cookie('userLocationName'));
		pageLoadHides();
		showShopList(true);
		showListData();
	}else{
		redirectPage(ajax_url_root+'classic_address');
	}
	isFirstShowList = false;
});

function redirectPage(url){
	pageLoadTips();
	window.addEventListener("pagehide", function(){
		pageLoadTipHide();
	},false);
	window.location.href = url;
}

function callbackLocation(locations){
	var locationArr = locations.split(',');
	user_long = $.trim(locationArr[0]);
	user_lat = $.trim(locationArr[1]);
	if(locationClassicHash == 'address'){
		$('#pageAddressLocationList').show().find('.content dd').data({'long':$.trim(locationArr[0]),'lat':$.trim(locationArr[1]),'name':$.trim(locationArr[2])}).find('.name').html($.trim(locationArr[2]));
	}else if(locationClassicHash == 'index'){
		$('#locationText').html($.trim(locationArr[2]));
		pageLoadHides();
		showShopList(true);
		showListData();
	}
}

function getListGeocoderError(){
	pageLoadHides();
	var addressTipLayer = layer.open({
		content: '未获取到您的位置，请先确认收货地址！',
		btn: ['OK'],
		end: function(){
			$('#pageAddressHeader').addClass('mustHideBack');
			layer.close(addressTipLayer);
			location.hash = 'address';
		}
	});
}

function parseCart(){
	
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


function changeTitle(title){
	$(document).attr("title",title);
}

function pageLoadTips(options){
	this.options = {
		showBg:true,
		top:'center',
		left:'center'
	}
	for (var i in options){
		this.options[i] = options[i];
	}
	options = this.options;
	//显示背景
	if(options.showBg){
		$('#pageLoadTipShade').css('background','rgba(216,216,216,0.5)').removeClass('nobg');
	}else{
		$('#pageLoadTipShade').addClass('nobg');
	}
	//显示顶边
	if(options.top == 'center'){
		options.top = (window_height-120)/2;
	}
	//显示顶边
	if(options.left == 'center'){
		options.left = (window_width-120)/2;
	}
	$('#pageLoadTipBox').css({'top':options.top+'px','left':options.left+'px'});
	$('#pageLoadTipShade').css({'height':$(window).height(),'width':$(window).width()}).show();
}
function pageLoadHides(){
	$('#pageLoadTipShade').hide();
}









var myScroll2=null,myScroll3=null;
$(function(){
	$('.dropdown-toggle').click(function(){
		if(choosePage == 'list'){
			isListShow=true;
		}else{
			isCatListShow=true;
		}
		if($(this).hasClass('active')){
			close_dropdown();
			return false;
		}
		close_dropdown();
		
		$(this).addClass('active');
		var nav = $(this).attr('data-nav');
		
		
		$('.dropdown-wrapper').addClass(nav+' active');
		$('.'+nav+'-wrapper').addClass('active');
		
		$('#dropdown_scroller,.dropdown-module').height($('.'+nav+'-wrapper>ul>li').size()*41-1);
		
		if($('#dropdown_scroller').height() < ($(window).height() - 97)*0.5){
			// $('#dropdown_scroller,.dropdown-module').height(($(window).height() - 97)*0.5);
			$('#dropdown_scroller,.dropdown-module').height($('#dropdown_scroller div').height());
		}else if($('#dropdown_scroller').height() < ($(window).height() - 97)*0.8){
			$('#dropdown_scroller,.dropdown-module').height($('#dropdown_scroller').height());
		}else{
			$('#dropdown_scroller,.dropdown-module').height(($(window).height() - 97)*0.8);
			myScroll3 = new IScroll('#dropdown_scroller', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick()});
		}
		
		if(!$('#listNavBox').hasClass('fixed')){
			if(choosePage == 'list'){
				if($('#pageList').height() < window_height + $('#listNavBox').offset().top){
					$('#pageList .shade').css('min-height',window_height + $('#listNavBox').offset().top).show();
				}
				$(window).scrollTop(listNavBarTop+5);
				setTimeout(function(){
					$('#listNavBox').addClass('fixed');
					$('#pageList .shade').height($('#pageList').height()+'px').show();
					isShowShade = true;
				},50);
			}else{
				$('#pageCat .shade').height($('#pageCat').height()+'px').show();
			}
		}else{
			$('#pageList .shade').height($('#pageList').height()+'px').show();
			// if($('#pageList').height() < window_height + $('#listNavBox').offset().top){
				// $('#pageList .shade').css('min-height',window_height + $('#listNavBox').offset().top);
			// }
			isShowShade = true;
		}
		
		if($('.'+nav+'-wrapper').find('.active').attr('data-has-sub')){
			$('#dropdown_sub_scroller').html('<div>'+$('.'+nav+'-wrapper').find('.active').find('.sub_cat').html()+'<div>').css('left','160px');
			$('#dropdown_scroller').width('160px');
		}
		myScroll2 = new IScroll('#dropdown_sub_scroller', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick()});
	});
	$('#pageList .shade').click(function(){
		$('#listNavBox').removeClass('fixed');
		$('#listNavPlaceHolderBox').hide();
		close_dropdown();
	});
	$('#pageCat .shade').click(function(){
		close_dropdown();
	});
	
	$(document).on('click','.biz-wrapper ul>li, .category-wrapper ul>li',function(){
		$('#dropdown_sub_scroller').css({'overflow':'hide','overflow-y':''});
		$('.biz-wrapper ul>li, .category-wrapper ul>li').removeClass('active');	
		if($(this).attr('data-has-sub')){
			$(this).addClass('active');
			$('#dropdown_sub_scroller').html('<div>'+$(this).find('.sub_cat').html()+'<div>').css('left','160px');
			$('#dropdown_scroller').width('160px');
			if($('#dropdown_sub_scroller>div').height() > $('#dropdown_sub_scroller').height()){
				myScroll2 = new IScroll('#dropdown_sub_scroller', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick()});
			}
		}
	});
	// $(document).on('click','.dropdown-list li',function(){
		// if(!$(this).attr('data-has-sub')){
			// alert(222);
			// list_location($(this));
		// }
	// });
});

function close_dropdown(){
	if(choosePage == 'list'){
		isListShow=false;
	}else{
		isCatListShow=false;
	}
	isShowShade = false;
	$('#dropdown_scroller,#dropdown_sub_scroller').css('width','');
	$('.dropdown-toggle').removeClass('active');
	$('.dropdown-wrapper').prop('class','dropdown-wrapper');
	$('#dropdown_scroller,.dropdown-module').css('height','');
	$('#pageList .shade,#pageCat .shade').hide();
	$('#dropdown_sub_scroller').css('left','100%');
	$('#dropdown_scroller>div>ul>li').removeClass('active');
	if(myScroll3){
		myScroll3.destroy();
		myScroll3 = null;
		$('#dropdown_scroller>div').removeAttr('style');
	}
	if(myScroll2){
		myScroll2.destroy();
		myScroll2 = null;
		$('#dropdown_sub_scroller>div').removeAttr('style');
	}
}



function getListGeocoderbefore(type){
	if(type == true){
		if(user_long != '0'){
			getListGeocoder();
		}else if($.cookie('userLocationName')){
			user_long = arguments[2];
			user_lat = arguments[3];
			getListGeocoder();
		}else{
			getGeoconv(arguments[2],arguments[3]);
		}
	}else{
		// alert('2222222226666666');
		pageLoadHides();
	}
}
function getGeoconv(lng,lat){
	$.getJSON('http://api.map.baidu.com/geoconv/v1/?coords='+lng+','+lat+'&from=1&to=5&ak=4c1bb2055e24296bbaef36574877b4e2&callback=getListGeoconvBack&jsoncallback=?');
}
function getListGeoconvBack(obj){
	// alert(JSON.stringify(obj.result));
	user_long = obj.result[0].x;
	user_lat = obj.result[0].y;
	getListGeocoder();
}
function getListGeocoder(){
	$.getJSON('http://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&callback=getListGeocoderBack&location='+user_lat+','+user_long+'&output=json&pois=1&jsoncallback=?');
}
function getListGeocoderBack(obj){
	if(addressGeocoder == false){
		if(obj.result.pois.length > 0){
			$('#locationText').html(obj.result.pois[0].name);
			$.cookie('userLocationName',obj.result.pois[0].name,{expires:700,path:'/'});
		}else{
			$('#locationText').html(obj.result.addressComponent.street);
			$.cookie('userLocationName',obj.result.addressComponent.street,{expires:700,path:'/'});
		}
		pageLoadHides();
		if(nowPage == 'list'){
			showShopList(true);
		}else if(nowPage == 'shopSearch'){
			showShopSearchList(true);
		}
	}else{
		var tmpName = obj.result.pois.length > 0 ? obj.result.pois[0].name : obj.result.addressComponent.street;
		$('#pageAddressLocationList').show().find('.content dd').data({'long':user_long,'lat':user_lat,'name':tmpName}).find('.name').html(tmpName);
		addressGeocoder = false;
	}
}

function showListData(){
	$.getJSON(ajax_url_root+'ajax_index',function(result){
		/*顶部轮播图*/
		if(result.banner_list){
			laytpl($('#listBannerSwiperTpl').html()).render(result.banner_list, function(html){
				$('#listBanner .swiper-wrapper').html(html);
				if(result.banner_list.length > 1){
					var mySwiper1 = $('#listBanner .swiper-container1').swiper({
						pagination:'#listBanner .swiper-pagination1',
						loop:true,
						grabCursor: true,
						paginationClickable: true,
						autoplay:3000,
						autoplayDisableOnInteraction:false,
						simulateTouch:false
					});
				}
				$('#listBanner').show();
			});
		}else{
			$('#listHeader').addClass('fixedRoundBg');
			$('#pageList').css('padding-top','50px');
			$('#listBanner').hide();
		}
		
		/*九宫格*/
		if(result.slider_list){
			laytpl($('#listSliderSwiperTpl').html()).render(result.slider_list, function(html){
				$('#listSlider .swiper-wrapper').html(html);
				if(result.slider_list.length > 8){
					var mySwiper2 = $('.swiper-container2').swiper({
						pagination:'.swiper-pagination2',
						loop:true,
						grabCursor: true,
						paginationClickable: true,
						simulateTouch:false
					});
				}
				$('#listSlider').show();
			});
		}else{
			$('#listSlider').hide();
		}
		
		/*三格广告*/
		if(result.adver_list){
			laytpl($('#listRecommendTpl').html()).render(result.adver_list, function(html){
				$('#listRecommend').html(html);
				$('#listRecommend').show();
			});
		}else{
			$('#listRecommend').hide();
		}
		
		/*可选分类*/
		if(result.category_list){
			categoryList = result.category_list;
			cat_url = categoryList[0].cat_url;
			laytpl($('#listCategoryListTpl').html()).render(result.category_list, function(html){
				$('#dropdown_scroller .category-wrapper ul').html(html);
			});
		}
		
		/*可选排序*/
		if(result.sort_list){
			sortList = result.sort_list;
			sort_url = categoryList[0].sort_url;
			laytpl($('#listSortListTpl').html()).render(result.sort_list, function(html){
				$('#dropdown_scroller .sort-wrapper ul').html(html);
			});
		}
		
		/*可选类别*/
		if(result.type_list){
			typeList = result.type_list;
			type_url = categoryList[0].type_url;
			laytpl($('#listTypeListTpl').html()).render(result.type_list, function(html){
				$('#dropdown_scroller .type-wrapper ul').html(html);
			});
		}
		listNavBarTop = $('#listNavBox').offset().top - 50;
	});
}

function list_location(obj){
	close_dropdown();
	if(obj.data('cat_url')){
		obj.addClass('red');
		$('.dropdown-toggle.category .nav-head-name').html(obj.find('span').data('name'));
		if(choosePage == 'cat'){
			$('#catTitle').html(obj.find('span').data('name'));
		}
		cat_url = obj.data('cat_url');
	}else if(obj.data('type_url')){
		obj.addClass('active').siblings('li').removeClass('active');
		$('.dropdown-toggle.type .nav-head-name').html(obj.find('span').data('name'));
		type_url = obj.data('type_url');
	}else if(obj.data('sort_url')){
		obj.addClass('active').siblings('li').removeClass('active');
		$('.dropdown-toggle.sort .nav-head-name').html(obj.find('span').data('name'));
		sort_url = obj.data('sort_url');
	}
	pageLoadTips({showBg:false});
	if(choosePage == 'list'){
		showShopList(true);
	}else{
		showCatShopList(true);
	}
}

var listShopNowPage=0,listHasMorePage = true;
function showShopList(newPage){
	isListShow = true;
	if(newPage || listShopNowPage == 0){
		$('#pageList #storeListLoadTip').show();
		$('#pageList #storeList .dealcard').empty();

		listShopNowPage = 1;
		listHasMorePage = true;
		pageLoadTips();
	}else{
		listShopNowPage++;
	}
	$.getJSON(ajax_url_root+'ajax_list',{cat_url:cat_url,sort_url:sort_url,type_url:type_url,user_lat:user_lat,user_long:user_long,page:listShopNowPage},function(result){
		// console.log(result);
		if(result.store_list && result.store_list.length > 0){
			laytpl($('#listShopTpl').html()).render(result.store_list, function(html){
				if(newPage){
					$('#pageList #storeList .dealcard').html(html);
				}else{
					$('#pageList #storeList .dealcard').append(html);
				}
			});
			if(result.has_more == false){
				listHasMorePage = false;
				$('#pageList #storeListLoadTip').hide();
			}
		}else{
			listHasMorePage = false;
			$('#pageList #storeListLoadTip').hide();
		}
		isListShow = false;
		pageLoadHides();
	});
}

var catShopNowPage=0,catHasMorePage = true;
function showCatShopList(newPage){
	isCatListShow = true;
	if(newPage || catShopNowPage == 0){
		$('#pageCat #storeListLoadTip').show();
		$('#pageCat #storeList .dealcard').empty();		
		catShopNowPage = 1;
		catHasMorePage = true;
		pageLoadTips();
	}else{
		catShopNowPage++;
	}
	$.getJSON(ajax_url_root+'ajax_list',{cat_url:cat_url,sort_url:sort_url,type_url:type_url,user_lat:user_lat,user_long:user_long,page:catShopNowPage},function(result){
		// console.log(result);
		if(result.store_list && result.store_list.length > 0){
			laytpl($('#listShopTpl').html()).render(result.store_list, function(html){
				if(newPage){
					$('#pageCat #storeList .dealcard').html(html);
				}else{
					$('#pageCat #storeList .dealcard').append(html);
				}
			});
			if(result.has_more == false){
				catHasMorePage = false;
				$('#pageCat #storeListLoadTip').hide();
			}
		}else{
			catHasMorePage = false;
			$('#pageCat #storeListLoadTip').hide();
		}
		isCatListShow = false;
		pageLoadHides();
	});
}

function goBackPage(){
	if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
		if(motify.checkIos()){
			$('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>');
			window.history.go(-1);
		}else{
			window.lifepasslogin.webViewGoBack();
		}
	}else{
		window.history.go(-1);
	}
}

function changeWechatShare(type,param){
	if(typeof(wxSdkLoad) == "undefined"){
		return false;
	}
	
	if(type == 'plat'){
		param = {
			title: window.shareData.tTitle,
			desc:  window.shareData.tContent,
			link:  window.shareData.sendFriendLink + '&openid=' + userOpenid,
			imgUrl: window.shareData.imgUrl,
		};
	}
	// console.log(param);
	wx.ready(function () {
		wx.onMenuShareAppMessage({
			title: param.title,
			desc: param.desc,
			link: param.link,
			imgUrl: param.imgUrl,
			type: '', // 分享类型,music、video或link，不填默认为link
			dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			success: function () { 
				shareHandle('frined');
				//alert('分享朋友成功');
			},
			cancel: function () { 
				//alert('分享朋友失败');
			}
		});
		wx.onMenuShareTimeline({
			title: param.title,
			link: param.link,
			imgUrl: param.imgUrl,
			success: function () { 
				shareHandle('frineds');
				//alert('分享朋友圈成功');
			},
			cancel: function () { 
				//alert('分享朋友圈失败');
			}
		});
	});
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