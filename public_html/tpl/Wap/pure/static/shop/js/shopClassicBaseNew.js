var nowPage='', storeTheme = 0;
var window_width = $(window).width();
var window_height = $(window).height();
var categoryList=null,sortList=null,typeList=null,choosePage='list', isShowSpell = false,cart_cookie = '';


//购物车COOKIE标识
function randomString(len) {
	len = len || 32;
	var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678oOLl9gqVvUuI1';
	var maxPos = $chars.length;
	var pwd = '';
	for(i = 0; i < len; i++) {
　　　　pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
　　}
	return pwd;
}
cart_cookie = $.cookie('cart_cookie');
if(!cart_cookie){
	$.cookie('cart_cookie',randomString(32),{expires:1,path:'/'});
	cart_cookie = $.cookie('cart_cookie');
}


$(function(){
	FastClick.attach(document.body);
	
	$('body').width(window_width);
	$('body,.pageDiv').css({width:window_width,'min-height':window_height});
	
	$(document).on('click','.hasMore',function(){
		$(this).toggleClass('showMore');
		return false;
	});
	
	/*页面点击事件*/
	$(document).on('click','.page-link',function(){
		redirectPage(ajax_url_root+'classic_'+$(this).data('url'));
		return false;
	});
	$(document).on('click','.storelive',function(){
		$('.video').show();
		return false;
	});
	$(document).on('click','.bg_style',function(){
		$('.video').hide();
		return false;
	});

	$(window).resize(function(){
		if($(window).width() != window_width){
			location.reload();
		}
	});
	//changeTitle('快店列表');
	
	if(window.__wxjs_is_wkwebview){
		window.addEventListener("pageshow", function(){
			hash_handle();
		},false);
	}else{
		hash_handle();
	}
	
	$(document).on('click','.storelive',function(){
		$('.video').show();
		
		if(Hls.isSupported()) {
			var video_show = document.getElementById('video_url');
			hls = new Hls();
			 hls.loadSource($(video_show).attr('src'));
			hls.attachMedia(video_show);
			hls.on(Hls.Events.MANIFEST_PARSED,function() {
			  video_show.play();
		  });
		}
		return false;
	});
	
	$('#ScanStore').click(function(){
		if(motify.checkWeixin()){
			motify.log('正在调用二维码功能');
			wx.scanQRCode({
				desc:'scanQRCode desc',
				needResult:1,
				scanType:["qrCode","barCode"],
				success:function (res){
					GoodsbyScan(now_store_id,res)
				},
				error:function(res){
					motify.log('微信返回错误！请稍后重试。',5);
				},
				fail:function(res){
					motify.log('无法调用二维码功能');
				}
			});
		}else{
			motify.log('您不是微信访问，无法使用二维码功能');
		}
	});
});
function GoodsbyScan(store_id,result){
	if(result.resultStr.indexOf("http")>-1){
		location.href=result.resultStr
	}else{
		var res_arr = result.resultStr.split(',');
		
		$.post(ajax_url_root+'scanGood', {'store_id':store_id, 'good_id':res_arr[1]}, function(response){
			if(typeof(response.url)!='undefined'){
				location.href=response.url 
			}else{
				alert(response.info)
			}
		},'json');
	}
}
function redirectPage(url){
	pageLoadTips();
	window.addEventListener("pagehide", function(){
		pageLoadTipHide();
	},false);
	
	window.location.href = url;
}

function resetBodyHeight(){
	// $('body').height($('.nowPage').height());
}

function hash_handle(){
	var locationHashParam = locationClassicHash.split('-');
	var locationHashItem = locationHashParam[0];
	if(locationHashItem != 'shop' && locationHashItem != 'good'){
		changeWechatShare('plat');
	}
	switch(locationHashItem){
		case 'shop':
			if(locationHashParam.length == 1 || isNaN(parseInt(locationHashParam[1])) || parseInt(locationHashParam[1]) == 0){
				location.hash = 'list';
			}else{
				showShop(locationHashParam[1]);
			}
			break;
		case 'cat':
			showCategory(locationHashParam[1]);
			break;
		case 'address':
			showAddress();
			break;
		case 'shopsearch':
			showShopSearch();
			break;
		case 'map':
			showMap(locationHashParam[1],locationHashParam[2],locationHashParam[3],locationHashParam[4],locationHashParam[5]);
			break;
		case 'good':
			showGood(locationHashParam[1],locationHashParam[2]);
			break;
		default:
			showList();
	}
}

//显示分类
var isShowCat = false,comeInShop = false,isCatListShow=false;
function showCategory(tmpCatUrl){
	if(comeInShop == false){
		pageLoadTips();
	}
	$('#pageCat').addClass('nowPage').show();

	close_dropdown();
	
	if(comeInShop == false){
		$('#listNavBox').after('<div id="listNavBoxPlace"></div>');
		$('#pageCatNav')[0].appendChild($('#listNavBox')[0]);
	}
	choosePage = 'cat';
	
	if(isShowCat == false){
		$('#catBackBtn').click(function(){
			goBackPage();
		});
		$(document).on('click','#pageCat #storeList dd',function(){
			comeInShop = true;
		});
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
			if(nowPage == 'cat'){
				if(isShowShade == true){
					close_dropdown();
					return false;
				}
				if(isCatListShow == false && catHasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height() - 50){
					showCatShopList();
				}
			}
		}
		if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
			if(motify.checkAndroid()){
				var locations = window.lifepasslogin.getLocation(false);
				var locationArr = locations.split(',');
				user_long = $.trim(locationArr[0]);
				user_lat = $.trim(locationArr[1]);
			}else{
				$('body').append('<iframe src="pigcmso2o://getLocation/false" style="display:none;"></iframe>');
			}
		}else if($.cookie('userLocationName') && $.cookie('userLocationLong') && $.cookie('userLocationLat')){
			user_long = $.cookie('userLocationLong');
			user_lat = $.cookie('userLocationLat');
		}else{
			redirectPage(ajax_url_root+'classic_address');
		}
		
		
		if((user_long == 0 || user_lat == 0) && $.cookie('userLocationName') && $.cookie('userLocationLong') && $.cookie('userLocationLat')){
			user_long = $.cookie('userLocationLong');
			user_lat = $.cookie('userLocationLat');
			$('#locationText').html($.cookie('userLocationName'));
			pageLoadHides();
			showShopList(true);
		}
		
		isShowCat = true;
	}
	
	cat_url = tmpCatUrl ? tmpCatUrl : 'all';
	if(categoryList == null || sortList == null || typeList == null){
		$.getJSON(ajax_url_root+'ajax_category',function(result){
			/*可选分类*/
			if(result.category_list){
				categoryList = result.category_list;
			}
			/*可选排序*/
			if(result.sort_list){
				sortList = result.sort_list;
				sort_url = sortList[0].sort_url;
			}
			/*可选类别*/
			if(result.type_list){
				typeList = result.type_list;
				type_url = typeList[0].type_url;
			}
			
			laytpl($('#listCategoryListTpl').html()).render(categoryList, function(html){
				$('#dropdown_scroller .category-wrapper ul').html(html);
			});
			laytpl($('#listSortListTpl').html()).render(sortList, function(html){
				$('#dropdown_scroller .sort-wrapper ul').html(html);
			});
			laytpl($('#listTypeListTpl').html()).render(typeList, function(html){
				$('#dropdown_scroller .type-wrapper ul').html(html);
			});
			
			var tmpCatDom = $('#listNavBox .category-wrapper .listCat-'+cat_url);
			if(tmpCatDom.size() == 0 || tmpCatDom.size() > 2){
				tmpCatDom = $('#listNavBox .category-wrapper .listCat-all');
			}
			if(tmpCatDom.size() == 1){
				list_location($('#listNavBox .category-wrapper .listCat-'+cat_url));
			}else if(tmpCatDom.size() == 2){
				$('#listNavBox .category-wrapper .listCat-'+cat_url+':eq(0)').trigger('click');
				list_location($('#listNavBox .category-wrapper .listCat-'+cat_url+':eq(1)'));
			}
		});
	}else{
		if(comeInShop == false){
			$('.dropdown-toggle.sort span').html(categoryList[0].cat_name);
			laytpl($('#listCategoryListTpl').html()).render(categoryList, function(html){
				$('#dropdown_scroller .category-wrapper ul').html(html);
			});
			
			sort_url = sortList[0].sort_url;
			$('.dropdown-toggle.sort span').html(sortList[0].name);
			laytpl($('#listSortListTpl').html()).render(sortList, function(html){
				$('#dropdown_scroller .sort-wrapper ul').html(html);
			});
			
			type_url = typeList[0].type_url;
			$('.dropdown-toggle.type span').html(typeList[0].name);
			laytpl($('#listTypeListTpl').html()).render(typeList, function(html){
				$('#dropdown_scroller .type-wrapper ul').html(html);
			});
				
			var tmpCatDom = $('#listNavBox .category-wrapper .listCat-'+cat_url);
			if(tmpCatDom.size() == 0){
				tmpCatDom = $('#listNavBox .category-wrapper .listCat-all');
			}
			if(tmpCatDom.size() == 1){
				list_location($('#listNavBox .category-wrapper .listCat-'+cat_url));
			}else if(tmpCatDom.size() == 2){
				$('#listNavBox .category-wrapper .listCat-'+cat_url+':eq(0)').trigger('click');
				list_location($('#listNavBox .category-wrapper .listCat-'+cat_url+':eq(1)'));
			}
		}else{
			comeInShop = false;
		}
	}
}

//显示地图
var hasLoadMap=false;
function showMap(shopId,lng,lat,shopName,address){
	pageLoadTips();
	nowPage = 'map';
	$('#pageMap').addClass('nowPage').show().siblings('.pageDiv').removeClass('nowPage').hide();
	if(hasLoadMap == false){
		$('#shopDetailMapBiz').height(window_height-60);
		$('#shopDetailMapClose').click(function(){
			$(this).hide();
			goBackPage();
		});
		$('#shopDetailMapAddressGo').click(function(){
			if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
				window.lifepasslogin.startToNavigation(lng,lat,shopName);
			}else if(typeof(wxSdkLoad) != "undefined"){
				pageLoadTips();
				$.getJSON(baiduToGcj02Url+"&baidu_lat="+lat+"&baidu_lng="+lng,function(result){
					pageLoadHides();
					if(result['status'] == 1){
						wx.ready(function (){
							wx.openLocation({
								latitude: result['info']['lat'],
								longitude: result['info']['lng'],
								name: decodeURIComponent(shopName), // 位置名
								address: decodeURIComponent(address), // 地址详情说明
								scale: 18, // 地图缩放级别,整形值,范围从1~28。默认为最大
								infoUrl: window.location.href // 在查看位置界面底部显示的超链接,可点击跳转
							});
						});
					}else{
						window.location.href = get_route_url+'&store_id='+shopId;
					}
				})
			}else{
				window.location.href = get_route_url+'&store_id='+shopId;
			}
		});

		hasLoadMap = true;
	}
	$('#shopDetailMapClose').show();
	$('#shopDetailMapAddress').html(decodeURIComponent(address));
	
	var map = new BMap.Map("shopDetailMapBiz",{enableMapClick:false});
	map.centerAndZoom(new BMap.Point(lng,lat), 16);
	
	map.addControl(new BMap.ZoomControl());  //添加地图缩放控件
	var marker1 = new BMap.Marker(new BMap.Point(lng,lat));  //创建标注
	map.addOverlay(marker1);                 // 将标注添加到地图中
	//创建信息窗口
	var infoWindow1 = new BMap.InfoWindow(decodeURIComponent(shopName));
	marker1.openInfoWindow(infoWindow1);
	marker1.addEventListener("click", function(){this.openInfoWindow(infoWindow1);});


	pageLoadHides();
}

var listShopList=[],listNavBarTop=0,isShowShade = false,mustShowShopList=false,isListShow = false,isFirstShowList = true;
//显示列表
function showList(){
	pageLoadTips();
	
	$('#pageList').addClass('nowPage').show();
	/*滚动条事件*/
	if(isFirstShowList == true){
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
			if(nowPage == 'list' || nowPage == ''){
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
	}else{
		if(user_long == '0'){
			getListGeocoderError();
		}else if(mustShowShopList == true){
			mustShowShopList = false;
			showShopList(true);
		}
		pageLoadHides();
	}
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
/*店铺搜索*/
var isShowShopSearch = false,loadShopSearchTimer=null,isSearchListShow=true;
function showShopSearch(){
	pageLoadTips({showBg:false});
	nowPage = 'shopSearch';
	$('#pageShopSearch').addClass('nowPage').show().siblings('.pageDiv').removeClass('nowPage').hide();
	
	if(isShowShopSearch == false){
		if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
			if(motify.checkAndroid()){
				var locations = window.lifepasslogin.getLocation(false);
				var locationArr = locations.split(',');
				user_long = $.trim(locationArr[0]);
				user_lat = $.trim(locationArr[1]);
			}else{
				$('body').append('<iframe src="pigcmso2o://getLocation/false" style="display:none;"></iframe>');
			}
		}else if($.cookie('userLocationName') && $.cookie('userLocationLong') && $.cookie('userLocationLat')){
			user_long = $.cookie('userLocationLong');
			user_lat = $.cookie('userLocationLat');
		}else{
			redirectPage(ajax_url_root+'classic_address');
		}
		
		$('#pageShopSearchTxt').width(window_width-124-32);
		
		$('#pageShopSearchBackBtn').click(function(){
			goBackPage();
		});
		
		$("#pageShopSearchTxt").bind('input', function(e){
			var address = $.trim($(this).val());
			if(address.length > 0){
				$('#pageShopSearchDel').show();
				$('#pageShopSearchBtn').addClass('so');
			}else{
				$('#pageShopSearchDel').hide();
				$('#pageShopSearchBtn').removeClass('so');
			}
		});
		$('#pageShopSearchBtn').click(function(){
			var address = $.trim($("#pageShopSearchTxt").val());
			if(address == ''){
				motify.log('请您输入店铺名称');
			}else{
				isSearchListShow = false;
				if(user_long == '0'){
					getUserLocation({okFunction:'getListGeocoderbefore',okFunctionParam:[true],errorFunction:'getListGeocoderError',errorFunctionParam:[false]});
				}else{
					showShopSearchList(true);
				}
			}
		});
		
		$('#pageShopSearchDel').click(function(){
			$('#pageShopSearchTxt').val('').trigger('input');
		});
		
		/*防止重复初始化JS*/
		if(motify.checkIos()){
			$('body').on('touchmove',function(){
				if(isShowShade == false){
					scrollSearchListEvent('ios');
				}
			});
			$(window).scroll(function(){
				$('body').trigger('touchmove');
			});
		}else{
			$(window).scroll(function(){
				scrollSearchListEvent('android');
			});
		}
		function scrollSearchListEvent(phoneType){	
			if(nowPage == 'shopSearch'){				
				if(isSearchListShow == false && shopSearchListHasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height() - 50){
					showShopSearchList();
				}
			}
		}
		
		isShowShopSearch = true;
	}
	
	pageLoadHides();
}

/*收货地址*/
var hasLoadAddress=false,loadAddressTimer=null,addressGeocoder = false;
function showAddress(){
	pageLoadTips({showBg:false});

	$('#pageAddress').addClass('nowPage').show();
	
	if($.cookie('userLocationName') && $.cookie('userLocationLong') && $.cookie('userLocationLat')){
		$('#pageAddressHeader').removeClass('hideBack');
		$('#pageAddressSearchTxt').width(window_width-124-32);
		$('#pageAddressNowLocation').show().find('.content dd').data({'long':$.trim($.cookie('userLocationLong')),'lat':$.cookie('userLocationLat'),'name':$.cookie('userLocationName')}).find('.name').html($.cookie('userLocationName'));
	}else{
		$('#pageAddressNowLocation').remove();
	}
	addressGeocoder = true;
	
	if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
		if(motify.checkAndroid()){
			var locations = window.lifepasslogin.getLocation(false);
			$('#pageAddressLocationList').show().find('.content dd').data({'long':$.trim(locationArr[0]),'lat':$.trim(locationArr[1]),'name':$.trim(locationArr[2])}).find('.name').html($.trim(locationArr[2]));
		}else{
			$('body').append('<iframe src="pigcmso2o://getLocation/false" style="display:none;"></iframe>');
		}
	}else{
		getUserLocation({'useHistory':false,okFunction:'getListGeocoderbefore',okFunctionParam:[true],errorFunction:'getAddressGeocoderError',errorFunctionParam:[false]});
	}
	
	if(hasLoadAddress == false){
		$('#pageAddressBackBtn').click(function(){
			goBackPage();
		});
		
		$("#pageAddressSearchTxt").bind('input', function(e){
			var address = $.trim($(this).val());
			if(address.length > 0){
				$('#pageAddressSearchDel,#pageAddressSearchContent').show();
				$('#pageAddressContent').hide();
				
				clearTimeout(loadAddressTimer);
				loadAddressTimer = setTimeout("searchAddress('"+address+"')", 500);
				$('#pageAddressSearchBtn').addClass('so');
			}else{
				$('#pageAddressSearchDel').hide();
				$('#pageAddressSearchBtn').removeClass('so');
				
				$('#pageAddressContent').show();
				$('#pageAddressSearchContent').hide();
			}
		});
		$('#pageAddressSearchBtn').click(function(){
			var address = $.trim($("#pageAddressSearchTxt").val());
			searchAddress(address);
		});
		
		$('#pageAddressSearchDel').click(function(){
			$('#pageAddressSearchTxt').val('').trigger('input');
			/* $('#pageAddressSearchDel').hide(); */
		});
		
		$(document).on('click','.searchAddressList dd',function(){
			$('#pageAddressSearchDel').trigger('click');
			user_long = $(this).data('long');
			user_lat = $(this).data('lat');
			$('#locationText').html($(this).data('name'));
			
			$.cookie('userLocation',user_long+','+user_lat,{expires:700,path:'/'});
			$.cookie('userLocationLong',user_long,{expires:700,path:'/'});
			$.cookie('userLocationLat',user_lat,{expires:700,path:'/'});
			$.cookie('userLocationName',$(this).data('name'),{expires:700,path:'/'});
			if($(this).data('id')){
				$.cookie('userLocationId',$(this).data('id'),{expires:700,path:'/'});
			}	
			mustShowShopList = true;
			redirectPage(locationLastPage);
			return false;
		});
		
		$.getJSON(ajax_url_root+'ajax_address',function(result){
			if(result.length > 0){
				laytpl($('#listAddressListTpl').html()).render(result, function(html){
					$('#pageAddressUserList .content').html(html);
				});
			}else{
				$('#pageAddressUserList').hide();
			}
			pageLoadHides();
		});
		
		hasLoadAddress = true;
	}else{
		pageLoadHides();
	}
}

function getAddressGeocoderError(){
	$('#pageAddressLocationList dl').html('<div style="height:40px;line-height:40px;background:white;padding-left:12px;">未获取到定位</div>');
}

function searchAddress(address){
	$.get(ajax_map_url, {query:address}, function(data){
		if(data.status == 1){
			$('#pageAddressSearchContent dl').empty();
			var result = data.result;
			var addressHtml = '';
			for(var i=0;i<result.length;i++){
				if(result[i]['long']){
					addressHtml += '<dd data-long="'+result[i]['long']+'" data-lat="'+result[i]['lat']+'" data-name="'+result[i]['name']+'">';
					addressHtml += '<div class="name">'+result[i]['name']+'</div>';
					addressHtml += '<div class="desc">'+result[i]['address']+'</div>';
					addressHtml += '</dd>';
				}
			}
			$('#pageAddressSearchContent dl').html(addressHtml);
		}
	});
}

var isShowGood = false;
function showGood(shop_id,product_id){
	shopId = shop_id;
	$('#pageShop').addClass('nowPage').show();
	pageLoadTips();
	$('body').css('overflow','hidden');
	$('#shopDetailPage').height(window_height-50);
	
	$('#shopHeader,#shopBanner,#shopMenuBar,#shopMerchantBox').hide();
	
	if(isShowGood == false){
		cartEventReg();	
	}
	
	$.getJSON(ajax_url_root+'ajaxShop',{store_id:shopId},function(result){
		nowShop = result;
        if (nowShop.store.tmpl == '1' || nowShop.store.store_theme == '1') {
            $('#shopDetailPageImgbox').css({height:window_width,width:window_width});
        }  else if(nowShop.store.tmpl == '0') {
            $('#shopDetailPageImgbox').css({height:window_width*500/900,width:window_width});
        }
        if (result.store.kf_url.length > 0) {
            $('#enter_im_div').show();
			$('#enter_im').attr('data-url',result.store.kf_url);
        }
        if (result.store.is_close == 1) {
            $('.top_header').text(result.store.close_reason).css('backgroud', '#eac3a7').unbind('click');
            $('#top_fei').show();
        } else if(result.store.delivery){
            $('#checkCartEmpty').html(result.store.delivery_price.toFixed(2)+'元起送');
        }
		$.getJSON(ajax_url_root+'ajax_goods',{goods_id:product_id},function(result){
			nowProduct = result;
			productPicList = [];
			for(var i in result.pic_arr){
				productPicList.push(result.pic_arr[i].url);
			}
			
			changeWechatShare('good',{title:nowProduct.name,desc:nowProduct.des_share ? nowProduct.des_share : nowProduct.name,imgUrl:productPicList[0],link:goodShareUrl});
			
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
            if (result.sell_count > 0) {
                $('#shopDetailPageTitle .desc').html('已售'+result.sell_count+'份 好评'+result.reply_count+'');
            } else if (result.is_new) {
                $('#shopDetailPageTitle .desc').html('新品上市  好评'+result.reply_count+'');
            } else {
                $('#shopDetailPageTitle .desc').html('好评'+result.reply_count+'');
            }
            $('#shopDetailPageTitle .skill_discount').remove();
            if (true == result.is_seckill_price && result.limit_type == 0) {
                if (result.max_num > 0) {
                    $('#shopDetailPageTitle .desc').after('<div class="skill_discount" style="float:right">限时优惠<span id="showLimit">,限<b id="showMax">' + result.max_num + '</b>' + result.unit + '优惠</span></div>');
                } else {
                    $('#shopDetailPageTitle .desc').after('<div class="skill_discount" style="float:right">限时优惠</div>');
                }
            } else if (result.max_num > 0) {
                $('#shopDetailPageTitle .desc').after('<div class="skill_discount" style="float:right" id="showLimit">限购<b id="showMax">' + result.max_num + '</b>' + result.unit + '</div>');
            }
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
			$('#shopDetailPagePrice').html('￥'+result.price+'<span class="unit"><em>/ </em>'+result.unit+'</span>'+(result.stock_num != -1 ? '<span data-stock="'+result.stock_num+'">' + (result.stock_num < 10 ? '还剩' + result.stock_num+result.unit : '') + '</span>' : '<span data-stock="-1"></span'));
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
			
			var nowShopCart = '';
			if (cartid != '' && nowIndex != '') {
				nowShopCart = window.localStorage.getItem(cartid + '_' + nowIndex);
			}
			var tmpShopCart = [];
			if ((nowShopCart == '' || nowShopCart == null) && nowIndex == '') {		
				pageLoadTips();
				$.post(ajax_cart_url,{shop_id:nowShop.store.id,cart_cookie:cart_cookie},function(result){
					if(!result.status || result.status == 0){
						result.info = [];
					}
					initializeShopCart(result.info);
					if(location.href.indexOf('classic_good') != -1){
						changeProductSpec();
					}
				});
			}else{
				initializeShopCart($.parseJSON(nowShopCart));
			}
			
			if(isShowGood == false){
				$('#shopDetailPageImgbox').click(function(){
					if(motify.checkWeixin()){
						wx.previewImage({
							current:productPicList[0],
							urls:productPicList
						});
					}
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
					changeProductSpec();
				});
			
				$(document).on('click','#shopDetailPageNumber .product_btn.plus,#shopDetailPageBuy',function(event){
					if(nowShop.store.is_close == 1){
						motify.log('店铺休息中');
						return false;
					}
					var productId = nowProduct.goods_id;
					if(nowProduct.spec_list){
						var productSpecListId = [];
						$.each($('#shopDetailPageFormat .row'),function(i,item){
							productSpecListId.push($(item).find('li.active').data('spec_list_id'));
						});
						var productSpecStr = productSpecListId.join('_');

						var productKey = productId + '_' + productSpecStr;
						var nowProductSpect = nowProduct.list[productSpecStr];
						var maxNum = parseInt(nowProductSpect.max_num);
						var isSeckill = nowProduct.is_seckill_price;
						var limit_type = nowProduct.limit_type;
						var unit = nowProduct.unit;
						var minNum = parseInt(nowProduct.min_num);
					}else{
						var productKey = productId;
						var maxNum = parseInt(nowProduct.max_num);
						var minNum = parseInt(nowProduct.min_num);
						var isSeckill = nowProduct.is_seckill_price;
						var limit_type = nowProduct.limit_type;
						var unit = nowProduct.unit;
					}
					if (maxNum > 0) {
						if (productCart[productKey] && maxNum <= productCart[productKey]['count']) {
							if (isSeckill == true && limit_type == 0) {
								motify.log('每单可享受' + maxNum + unit + '限时优惠价，超出恢复原价');
							} else {
								if (limit_type == 0) {
									motify.log('每单限购' + maxNum + unit);
								} else {
									motify.log('每个用户限购' + maxNum + unit);
								}
								return false;
							}
						}
					}
					/***********/
					if(nowProduct.properties_list){
						var thisFlag = false;
						$.each($('#shopDetailPageLabelBox .row'),function(i,item){
							var tmpProductProperties = [];
							var maxNum = $(item).data('num');
							var num = 0;
							$.each($(item).find('li.active'),function(j,jtem){
								num ++;
							});
							if (num > maxNum) {
								motify.log($(item).data('label_name')+' 您最多能选择 ' + maxNum + ' 个');
								thisFlag = true;
							}
						});
						if (thisFlag) {
							return false;
						}
					}
					
					var intStock = parseInt($('#shopDetailPagePrice span').data('stock'));
					if(intStock != -1 && (intStock == 0 || intStock - parseInt($('#shopDetailPageNumber .number').html()) <= 0)){
						motify.log('没有库存了');
						return false;
					}
					tmpDomObj = $(this);

					cartFunction('plus',tmpDomObj,'productPage');

					return false;
				});
				$(document).on('click','#shopDetailPageNumber .product_btn.min',function(event){
					tmpDomObj = $(this);
					cartFunction('min',tmpDomObj,'productPage');
					return false;
				});
				
				$('#shopDetailpageClose').click(function(){
					goBackPage();
				});
			}
			changeProductSpec();
			
			// shopDetailPageIscroll = new IScroll('#shopDetailPage', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false});
			$('#shopContentBar').show();
			$('#shopDetailPage').show();
			$('.content_video').css({'width':$(window).width()-20,'height':($(window).width()-20)*9/16});
			pageLoadHides();
			isShowGood = true;
		});
	});
}

function cartEventReg(){
	$(document).on('click','#shopProductCartDel',function(){
		layer.open({
			content: '您确定要清空购物车吗？',
			btn: ['确认', '取消'],
			shadeClose: false,
			yes: function(){
				$('#shopProductRightBar2 .product_btn.min,#shopProductRightBar2 .product_btn.number,#shopProductBottomBar .product_btn.min,#shopProductBottomBar .product_btn.number').remove();
				$('#shopDetailPageBuy').show();
				$('#shopDetailPageNumber').hide();
				productCart = [];
				productCartNumber = 0;
				productCartMoney = 0;
				
				cartFunction('clear');
				layer.closeAll();
				$('#shopProductCartShade').trigger('click');
			}, no: function(){
				
			}
		});
	});
	//top_header点击
    $('.top_header').click(function(e){
        var header_data = $('.header_data').text();
        if(deliverExtraPrice == 1){
            $('#cartInfo .price').removeClass('active');
            deliverExtraPrice = 0;
            $('#checkCartEmpty').show();
            $('#checkCart').hide();
        } else {
            $('#additional').text(header_data);
            $('#cartInfo .price').addClass('active');

            $('.top_header, #checkCartEmpty').hide();
            $('#checkCart').show();

            $('#shopProductLeftBar,#shopProductRightBar,#shopProductBottomBar').css('height',window_height-152-50);
            deliverExtraPrice = 1;
        }
    });

	
	$('#cartInfo').click(function(){
		if(!$(this).hasClass('isShow')){
			$(this).addClass('isShow');
			$('#shopProductCartShade').show();
			$('#top_fei').css('max-height',(window_height-50)/3*2+'px').show();
			if (parseFloat(productCartMoney.toFixed(2)) < nowShop.store.delivery_price && nowShop.store.extra_price > 0 && nowShop.store.deliver_type != 2 && nowShop.store.deliver_type != 5) {
			    $('.header_data').html(nowShop.store.extra_price);
			    if (deliverExtraPrice == 0) $('.top_header').show();
			} else {
			    deliverExtraPrice = 0;
			    $('.top_header').hide();
			}
			laytpl($('#productCartBoxTpl').html()).render(productCart, function(html){
				$('#shopProductCartBox').html(html);
                
                if(isShowSpell && nowIndex == '') {
                    $('#spell_tip').show();
                } else {
                    $('#spell_tip').hide();
                }
				$('body').css('overflow-y','hidden');
			});
		}else{
			$('#shopProductCartShade').trigger('click');
			$('#top_fei').show();
		}
		// $('#shopPageShade').trigger('click');
	});
	
	$('#shopProductCartShade').click(function(){
		$(this).hide();
		$('#shopProductCartBox').empty();
		$('#cartInfo').removeClass('isShow');
		$('body').css('overflow-y','auto');
//		$('#top_fei').hide();
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
        var locationHashParam = locationClassicHash.split('-');
        $.get(ajax_basic_price,{store_id:locationHashParam[1]},function(basic_res){
            if(basic_res.basic_price > nowShop.store.delivery_price){
                nowShop.store.delivery_price = basic_res.basic_price;
                motify.log('起送价已更新！')
				window.location.reload();
            }
        },'json');
	    if (cartid != '' && nowIndex != '') {
            var cartData = window.localStorage.getItem(cartid + '_' + nowIndex);
            var jsonData = window.localStorage.getItem(cartid);
            var name = '', avatar = '';
            if (jsonData != '' && jsonData != null) {
                jsonData = $.parseJSON(jsonData);
                name = jsonData.name;
                avatar = jsonData.avatar;
            }
            var from = spellfrm == 'spell' ? 1 : 0;
            $.post(spell_add_cart, {'cartData':cartData, 'cartid':cartid, 'store_id':nowShop.store.id, 'index':nowIndex, 'name':name, 'avatar':avatar, 'from':from}, function(response){
                if (response.error == false) {
                    if (spellfrm == 'spell') {
                        var this_url = spell_cart_url + 'spell';
                    } else {
                        var this_url = spell_cart_url + 'sync';
                    }
                    window.location.href = this_url+'&store_id='+nowShop.store.id + '&cartid=' + response.cartid;
                } else {
                    motify.log(response.msg);
                }
            }, 'json');
        } else {
            window.location.href = check_cart_url+'&store_id='+nowShop.store.id + '&deliverExtraPrice=' + deliverExtraPrice;
        }
	});
    
    //点击分单
    $(document).on('click', '#spell_tip', function(){
		var cookieProductCart = [];
		for(var i in productCart){
			cookieProductCart.push(productCart[i]);
		}
		if(cookieProductCart.length > 0){
			nowShopCart = JSON.stringify(cookieProductCart);
		}else{
			nowShopCart = '{}';
		}
        $.post(spell_add_cart, {'cartData':nowShopCart, 'store_id':nowShop.store.id, 'index':1}, function(response){
            if (response.error == false) {
                if (spellfrm == 'spell') {
                    var this_url = spell_cart_url + 'spell';
                } else {
                    var this_url = spell_cart_url + 'sync';
                }
                //将点单数据写入下标为1
                window.localStorage.setItem(response.cartid + '_1', nowShopCart);
                window.location.href = this_url+'&store_id='+nowShop.store.id + '&cartid=' + response.cartid;
            } else {
                motify.log(response.msg);
            }
        }, 'json');
    });
}

var nowShop = {},isShowShop = false,tmpDomObj={},shopDetailPageIscroll = null,nowProduct = {},firstMenuClick = false,productSwiper = null,productPicList = [];
function showShop(shopId){
	pageLoadTips({showBg:false});

	$('#pageShop').addClass('nowPage').show();
	$('#pageShop').css('min-height','');
	$('#pageShop').css('height',$(window).height());

	if(isShowShop == false){
		$('#shopContentBar').height(window_height-152);
		$('#shopContentBar>div').css({width:window_width});
		
		$('#shopContentBar #shopProductBox').css('left','0px');
		$('#shopContentBar #shopReplyBox').css('left',window_width+'px');
		$('#shopContentBar #shopMerchantBox').css('left',window_width*2+'px');
		
		$('#shopProductLeftBar2,#shopProductBottomBar').css('height',window_height-152-50);
		$('#shopProductRightBar2').css('height',window_height-152-50);
		$('#shopMerchantBox,#shopReplyBox').css({height:window_height-152,'overflow-y':'auto'});
		$('#shopProductRightBar2').width(window_width-100);
		$('#shopProductLeftBar2').css('height',window_height-152-50);
		$('#shopProductRightBar2').css('height',window_height-152-50);
		$('#shopProductRightBar2').width(window_width-100);
		if($('.top_header').is(':hidden')){
			$('#shopProductLeftBar,#shopProductRightBar,#shopProductBottomBar').css('height',window_height-152-50-30);
		}
		if(motify.checkIos()){
			$('#shopProductLeftBar,#shopProductRightBar,#shopProductLeftBar2,#shopProductRightBar2,#shopProductBottomBar,#shopMerchantBox,#shopReplyBox').css({'-webkit-overflow-scrolling':'touch'});
		}

		$('#shopMenuBar li').click(function(){
			if(firstMenuClick == false){
				$('html,body').animate({scrollTop: $('#shopMenuBar').offset().top-50});
			}
			var tmpIndex = $(this).index();
			var tmpNav = $(this).data('nav');
			$(this).addClass('active').siblings().removeClass('active');
			pageLoadTips({showBg:false});
			$('#shopContentBar').animate({'margin-left':'-'+tmpIndex*window_width+'px'},function(){
				showShopContent(tmpNav);
			});
		});
		
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
		});
		
		$('#pageShop #backBtn').click(function(){
			goBackPage();
		});
		cartEventReg();
		
		
		$('#shopBanner').click(function(){
			$('#shopMenuBar li.merchant').trigger('click');
		});
		
//		$(document).on('click','#shopProductLeftBar2 dd',function(){
//			$(this).addClass('active').siblings().removeClass('active');
//			$('#shopProductRightBar2').scrollTop($('#shopProductRightBar2-'+$(this).data('cat_id')).offset().top-$('#shopProductRightBar2').offset().top+$('#shopProductRightBar2').scrollTop());
//		});
		
		$(document).on('click','#shopProductRightBar2 li,#shopProductBottomBar li',function(event){
			redirectPage(ajax_url_root+'classic_good&shop_id='+shopId+'&good_id='+$(this).data('product_id'));
		});

		$(document).on('click','#shopProductRightBar2 .product_btn.plus,#shopProductBottomBar .product_btn.plus',function(event){
		   
			if(nowShop.store.is_close == 1){
				motify.log('店铺休息中');
				return false;
			}
			tmpDomObj = $(this);
            if (parseInt(tmpDomObj.closest('li').data('max_num')) > 0 && parseInt(tmpDomObj.closest('li').data('max_num')) <= parseInt(tmpDomObj.siblings('.number').html())) {
                if (tmpDomObj.closest('li').data('is_seckill') && parseInt(tmpDomObj.closest('li').data('limit_type')) == 0) {
                    motify.log('每单可享受' + parseInt(tmpDomObj.closest('li').data('max_num')) + tmpDomObj.closest('li').data('unit') + '限时优惠价，超出恢复原价');
                } else {
                    if (parseInt(tmpDomObj.closest('li').data('limit_type')) == 0) {
                        motify.log('每单限购' + parseInt(tmpDomObj.closest('li').data('max_num')) + tmpDomObj.closest('li').data('unit'));
                    } else {
                        motify.log('每个用户限购' + parseInt(tmpDomObj.closest('li').data('max_num')) + tmpDomObj.closest('li').data('unit'));
                    }
                    return false;
                }
            }
			var max_num_=parseInt(tmpDomObj.closest('li').data('max_num')) ;
			var user_buyer_num=parseInt(tmpDomObj.closest('li').data('user_buy_num')) ;

			if(user_buyer_num>0 && max_num_>0 && user_buyer_num>=max_num_ && parseInt(tmpDomObj.closest('li').data('limit_type'))==1){
				motify.log('每个用户限购' + parseInt(tmpDomObj.closest('li').data('max_num')) + tmpDomObj.closest('li').data('unit'));
				return false;
			}
            
			var intStock = parseInt(tmpDomObj.closest('li').data('stock'));
			if(intStock != -1 && (intStock == 0 || intStock - parseInt(tmpDomObj.siblings('.number').html()) <= 0)){
				motify.log('没有库存了');
				return false;
			}

			cartFunction('plus',tmpDomObj,tmpDomObj.closest('li'));

			return false;
		});
		$(document).on('click','#shopProductRightBar2 .bgPlusBack',function(event){
			if(nowShop.store.is_close == 1){
				motify.log('店铺休息中');
				return false;
			}
			tmpDomObj = $(this);
            if (parseInt(tmpDomObj.closest('li').data('max_num')) > 0 && parseInt(tmpDomObj.closest('li').data('max_num')) <= parseInt(tmpDomObj.siblings('.number').html())) {
                if (tmpDomObj.closest('li').data('is_seckill') && parseInt(tmpDomObj.closest('li').data('limit_type')) == 0) {
                    motify.log('每单可享受' + parseInt(tmpDomObj.closest('li').data('max_num')) + tmpDomObj.closest('li').data('unit') + '限时优惠价，超出恢复原价');
                } else {
                    if (parseInt(tmpDomObj.closest('li').data('limit_type')) == 0) {
                        motify.log('每单限购' + parseInt(tmpDomObj.closest('li').data('max_num')) + tmpDomObj.closest('li').data('unit'));
                    } else {
                        motify.log('每个用户限购' + parseInt(tmpDomObj.closest('li').data('max_num')) + tmpDomObj.closest('li').data('unit'));
                    }
                    return false;
                }
            }
			var max_num_=parseInt(tmpDomObj.closest('li').data('max_num')) ;
			var user_buyer_num=parseInt(tmpDomObj.closest('li').data('user_buy_num')) ;

			if(user_buyer_num>0 && max_num_>0 && user_buyer_num>=max_num_ && parseInt(tmpDomObj.closest('li').data('limit_type'))==1){
				motify.log('每个用户限购' + parseInt(tmpDomObj.closest('li').data('max_num')) + tmpDomObj.closest('li').data('unit'));
				return false;
			}
            
			var intStock = parseInt(tmpDomObj.closest('li').data('stock'));
			if(intStock != -1 && (intStock == 0 || intStock - parseInt(tmpDomObj.siblings('.number').html()) <= 0)){
				motify.log('没有库存了');
				return false;
			}

			cartFunction('plus',tmpDomObj,tmpDomObj.closest('li'));

			return false;
		});
		$(document).on('click','#shopProductRightBar2 .bgMinBack',function(event){
			tmpDomObj = $(this).siblings('.product_btn.min');
			cartFunction('min',tmpDomObj,tmpDomObj.hasClass('cart') ? tmpDomObj.closest('dd') : tmpDomObj.closest('li'));
			return false;
		});
		$(document).on('click','#shopProductRightBar2 .product_btn.min',function(event){
			tmpDomObj = $(this);
			cartFunction('min',tmpDomObj,tmpDomObj.hasClass('cart') ? tmpDomObj.closest('dd') : tmpDomObj.closest('li'));
			return false;
		});
		
		$('#shopReplyBox ul li').click(function(){
			if($(this).hasClass('active')){
				return false;
			}
			$(this).addClass('active').siblings().removeClass('active');
			
			$('#shopReplyBox dl').empty();
			$('#showMoreReply').hide();
			pageLoadTips({showBg:false});
			$.post(shopReplyUrl+nowShop.store.id,{tab:$(this).data('tab')},function(result){
				result = $.parseJSON(result);	
				laytpl($('#shopReplyTpl').html()).render(result.list, function(html){
					$('#shopReplyBox dl').html(html);
				});
				$('#showMoreReply').data('page','2');
				if(result.total < result.now){
					$('#showMoreReply').show();
				}else{
					$('#showMoreReply').hide();
				}
				pageLoadHides();
			});
		});
		
		$('#showMoreReply').click(function(){
			pageLoadTips({showBg:false});
			var nowPage = parseInt($(this).data('page'));
			$.post(shopReplyUrl+nowShop.store.id,{tab:$('#shopReplyBox ul li.active').data('tab'),page:nowPage},function(result){
				result = $.parseJSON(result);	
				laytpl($('#shopReplyTpl').html()).render(result.list, function(html){
					$('#shopReplyBox dl').append(html);
				});

				$('#showMoreReply').data('page',(nowPage+1));
				
				if(result.total < result.now){
					$('#showMoreReply').show();
				}else{
					$('#showMoreReply').hide();
				}
				
				pageLoadHides();
			});
		});
		
		/*Right滚动条*/
		if(motify.checkIos()){
			$('#shopProductRightBar2,#shopProductBottomBar').on('touchmove',function(){
				scrollProductEvent('ios');
			});
			$('#shopProductRightBar2,#shopProductBottomBar').scroll(function(){
				$('#shopProductRightBar2').trigger('touchmove');
			});
		}else{
			$('#shopProductRightBar2,#shopProductBottomBar').scroll(function(){
				scrollProductEvent('android');
			});
		}
		function scrollProductEvent(phoneType){
			var scrollRightTop = $('#shopMenuBar').css('display') == 'none' ? $('#shopProductBottomBar').scrollTop() :$('#shopProductRightBar2').scrollTop();
			if(scrollRightTop > 10){
				$(window).scrollTop($('#shopMenuBar').css('display') == 'none' ? $('#shopCatBar').offset().top-50 : $('#shopMenuBar').offset().top-50);
			}
		}
		isShowShop = true;
	}
	
	productCart=[];
	productCartNumber = 0;
	productCartMoney  = 0;
	$('#shopProductCart #cartNumber').html(productCartNumber);
	$('#shopProductCart #cartMoney').html(productCartMoney.toFixed(2));
	$('#shopProductCart #cartInfo').hide();
	$('#shopProductCart #emptyCart').show();
	$('#shopProductLeftBar2 dl,#shopProductRightBar2 dl').empty();
	$('#shopProductBottomBar ul,#shopCatBar .content ul').empty();
	$.getJSON(ajax_url_root+'ajaxShop',{store_id:shopId},function(result){
		$('#shopTitle').html(result.store.name);
		$('#shopIcon').css('background-image','url('+result.store.image+')');
		if(result.store.delivery){
			if(result.store.delivery_money == 0){
                var str = ' | 免配送费';
			}else{
                var str = ' | 配送 ￥ '+result.store.delivery_money;
			}
			$('#deliveryText').html('起送 ￥ '+result.store.delivery_price + str + ' | 送达 '+result.store.delivery_time+result.store.delivery_time_type);
		}else{
			$('#deliveryText').html('本店铺仅支持门店自提');
		}
		$('#shopNoticeText').html(result.store.store_notice);

        if (result.store.kf_url.length > 0) {
            $('#enter_im_div').show();
			$('#enter_im').attr('data-url',result.store.kf_url);
        }
		// $('#shopCouponText').html(parseCoupon(result.store.coupon_list,'text')+';'+result.store.store_notice);
		$('#shopCouponText').html(parseCoupon(result.store.coupon_list,'text'));
		if(result.store.is_close == 1){
            if (result.store.close_reason.length > 0) {
                $('.top_header').text(result.store.close_reason).css({'background':'#eac3a7', 'font-size':'12px'}).unbind('click');
                $('#top_fei').show();
            }
			$('#checkCartEmpty').html('店铺休息中');
		}else if(result.store.delivery){
			$('#checkCartEmpty').html(parseFloat(result.store.delivery_price).toFixed(2)+'元起送');
		}
		
		nowShop = result;
		
		$('#shopProductBox,#shopMerchantBox,#shopReplyBox').data('isShow','0');
		$('#shopReplyBox').hide();
		// showShopContent('product');
		firstMenuClick = true;
		$('#shopMenuBar .product').trigger('click');
		
		changeWechatShare('shop',{title:nowShop.store.name,desc:nowShop.store.txt_info,imgUrl:nowShop.store.image,link:shopShareUrl+nowShop.store.id});
	});

	$('#shopContentBar,#shopBanner').show();
	
	// setTimeout(function(){
		// pageLoadHides();
	// },1500);
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
		$('#shopDetailPagePrice').html('￥'+((nowProduct.is_seckill_price && nowProductSpect.seckill_price) ? nowProductSpect.seckill_price : nowProductSpect.price)+(nowProduct.extra_pay_price>0?'+'+nowProduct.extra_pay_price+nowProduct.extra_pay_price_name:'')+'<span class="unit"><em>/ </em>'+nowProduct.unit+'</span>'+(nowProductSpect.stock_num != -1 ? '<span class="stock" data-stock="'+nowProductSpect.stock_num+'">' + (nowProductSpect.stock_num < 10 ? '剩下'+nowProductSpect.stock_num+nowProduct.unit : '') + '</span>' : '<span class="stock" data-stock="-1"></span>'));
		//$('#shopDetailPagePrice').html('￥'+nowProductSpect.price+'<span class="unit"><em>/ </em>'+nowProduct.unit+'</span>'+(nowProductSpect.stock_num != -1 ? '<span data-stock="'+nowProductSpect.stock_num+'">剩下'+nowProductSpect.stock_num+nowProduct.unit+'</span>' : '<span data-stock="-1"></span>'));
	      if (nowProductSpect.max_num > 0) {
	            $('#showMax').text(nowProductSpect.max_num);
	            $('#showLimit').show();
	        } else {
	            $('#showLimit').hide();
	        }
		if(nowProduct.properties_list){
			for(var i in nowProductSpect.properties){
				$('.productProperties_'+nowProductSpect.properties[i].id).data('num',nowProductSpect.properties[i].num);
			}
		}
		var nowProductCartLabel = nowProduct.goods_id + '_' + productSpecStr;
	}else{
		var nowProductCartLabel = nowProduct.goods_id;
	}
	if(nowProduct.properties_list){
		$.each($('#shopDetailPageLabelBox .row'),function(i,item){
			var tmpProductProperties = [];
			$.each($(item).find('li.active'),function(j,jtem){
				nowProductCartLabel = nowProductCartLabel+'_'+$(jtem).data('label_list_id')+'_'+$(jtem).data('label_id');
			});
		});
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
			var productPrice = (nowProduct.is_seckill_price && nowProductSpect.seckill_price) ? parseFloat(nowProductSpect.seckill_price) : parseFloat(nowProductSpect.price);
			var productStock = nowProductSpect.stock_num;
            var oldPrice = parseFloat(nowProductSpect.old_price);
			var maxNum = parseInt(nowProductSpect.max_num);
			var isSeckill = nowProduct.is_seckill_price;
			var limit_type = nowProduct.limit_type;
			var unit = nowProduct.unit;
			var minNum = parseInt(nowProduct.min_num);
			
			var productParam = [];
			for(var i in productSpecListId){
				productParam.push({'type':'spec','spec_id':productSpecId[i],'id':productSpecListId[i],'name':productSpecText[i]});
			}
		}else{
			var productKey = productId;
			var productPrice = nowProduct.price;
			var productParam = [];
			var productStock = parseInt(nowProduct.stock_num);
			
            var oldPrice = parseFloat(nowProduct.old_price);
            var maxNum = parseInt(nowProduct.max_num);
            var minNum = parseInt(nowProduct.min_num);
            var isSeckill = nowProduct.is_seckill_price;
            var limit_type = nowProduct.limit_type;
            var unit = nowProduct.unit;
		}
        
		if(nowProduct.properties_list){
			$.each($('#shopDetailPageLabelBox .row'),function(i,item){
				var tmpProductProperties = [];
				$.each($(item).find('li.active'),function(j,jtem){
					productKey = productKey+'_'+$(jtem).data('label_list_id')+'_'+$(jtem).data('label_id');
					tmpProductProperties.push({'id':$(jtem).data('label_id'),'list_id':$(jtem).data('label_list_id'),'name':$(jtem).html()});
				});
				productParam.push({'type':'properties','data':tmpProductProperties});
			});
		}
        var productPackCharge = nowProduct.packing_charge;
	}else if(type != 'count' && type != 'clear'){
		if(dataObj.hasClass('cartDD') && dataObj.find('.cartLeft').hasClass('hasSpec')){
			var productKey = dataObj.find('.spec').data('product_id');
			var productStock = dataObj.find('.spec').data('stock');
		}else{
			var productKey = dataObj.data('product_id');
			var productStock = dataObj.data('stock');
		}
        var productPackCharge = dataObj.data('packing_charge');
		var productId = dataObj.data('product_id');
		var productName = dataObj.data('product_name');
		var productPrice = parseFloat(dataObj.data('product_price'));
		var oldPrice = parseFloat(dataObj.data('o_price'));
		var maxNum = parseInt(dataObj.data('max_num'));
		var isSeckill = dataObj.data('is_seckill');
		var limit_type = dataObj.data('limit_type');
		var unit = dataObj.data('unit');
		var minNum = parseInt(dataObj.data('min_num'));
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
		if ((dataObj == 'productPage' || dataObj.hasClass('cartDD')) && maxNum > 0) {
    		if (productCart[productKey] && maxNum <= productCart[productKey]['count']) {
                if (isSeckill == true && limit_type == 0) {
                    motify.log('每单可享受' + maxNum + unit + '限时优惠价，超出恢复原价');
                } else {
                    if (limit_type == 0) {
                        motify.log('每单限购' + maxNum + unit);
                    } else {
                        motify.log('每个用户限购' + maxNum + unit);
                    }
                    return false;
                }
            }
		}
		
		$('#shopProductCart .cart').addClass('bound');
		setTimeout(function(){
			$('#shopProductCart .cart').removeClass('bound');
		},500);
		var thisAddNum = 1;
		if (productCart[productKey]) {
			productCart[productKey]['count']++;
			$('.productNum-'+productKey).html(productCart[productKey]['count']);
		} else {
            if (minNum > 0) {
                thisAddNum = minNum;
            }
			if (dataObj == 'productPage') {
				$('#shopDetailPageBuy').hide();
				$('#shopDetailPageNumber').show();
				$('#shopDetailPageNumber .number').html(thisAddNum);
				$('.product_'+productId+' .plus').after('<div class="product_btn number productNum-'+productId+'">' + thisAddNum + '</div>').after('<div class="product_btn min"></div>');
			} else {
				obj.after('<div class="product_btn number productNum-'+productId+'">' + thisAddNum + '</div>');
				obj.after('<div class="product_btn min"></div>');
			}
			productPackCharge = productPackCharge ? productPackCharge : 0;
			productCart[productKey] = {
				'productId':productId,
				'productName':productName,
				'productPrice':productPrice,
				'productStock':productStock,
                'productParam':productParam,
                'productPackCharge':productPackCharge,
                'maxNum':maxNum,
                'minNum':minNum,
                'isSeckill':isSeckill,
                'limit_type':limit_type,
                'unit':unit,
                'oldPrice':oldPrice,
				'count':thisAddNum,
			};
		}
		if (parseInt(productCart[productKey]['maxNum']) > 0 && parseInt(productCart[productKey]['count']) > parseInt(productCart[productKey]['maxNum'])) {
		    productPrice = parseFloat(productCart[productKey]['oldPrice']);
		}
		productCartNumber += thisAddNum;
        productCartMoney = productCartMoney + (productPrice + productPackCharge) * thisAddNum;
        if (productPackCharge > 0 && dataObj != 'productPage' && dataObj.hasClass('cartDD')) {
            $('#packChargeCount').html(parseFloat($('#packChargeCount').html())+productPackCharge);
        }
        var locationHashParam = locationClassicHash.split('-');
        $.get(ajax_basic_price,{store_id:locationHashParam[1]},function(basic_res){
            if(basic_res.basic_price != nowShop.store.delivery_price){
                nowShop.store.delivery_price = basic_res.basic_price;
                motify.log('起送价已更新！');
                if(nowShop.store.delivery == true && parseFloat(productCartMoney.toFixed(2)) < nowShop.store.delivery_price) {
                    $('#checkCart').hide();
                    $('#checkCartEmpty').addClass('noEmpty').show().html('还差￥' + (nowShop.store.delivery_price - parseFloat(productCartMoney.toFixed(2))).toFixed(2) + '起送');
                    if (nowShop.store.deliver_type != 2 && nowShop.store.deliver_type != 5) {
                        if (nowShop.store.extra_price > 0) {
                            $('.header_data').html(nowShop.store.extra_price);
                            $('#top_fei, .top_header').show();
                            if ($('.top_header').is(':hidden')) {
                                $('#shopProductLeftBar,#shopProductRightBar,#shopProductBottomBar').css('height', window_height - 166 - 50 - 30);
                            }
                        }
                    }
                }
            }
        },'json');
	}else if(type == 'min'){
		$('#shopProductCart .cart').addClass('bound');
		setTimeout(function(){
			$('#shopProductCart .cart').removeClass('bound');
		},500);
		var thisAddNum = 1;
        if (minNum > 0) {
            thisAddNum = minNum;
        }
        var reduceNum = 1;
		if(productCart[productKey].count == thisAddNum){
		    reduceNum = thisAddNum;
			if(dataObj == 'productPage'){
				$('#shopDetailPageBuy').show();
				$('#shopDetailPageNumber').hide();
				$('#shopDetailPageNumber .number').html('0');
			}else{
				obj.siblings('.number').remove();
				obj.remove();
				if(dataObj.hasClass('cartDD')){
					dataObj.remove();
					$('#shopProductRightBar2 .productNum-'+productKey).siblings('.min').remove();
					$('#shopProductRightBar2 .productNum-'+productKey).remove();
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
            if(productPackCharge > 0 && dataObj != 'productPage' && dataObj.hasClass('cartDD')){
                $('#packChargeCount').html(parseFloat($('#packChargeCount').html())-productPackCharge);
            }
		}
		
        if (productCart[productKey] && parseInt(productCart[productKey]['maxNum']) > 0 && parseInt(productCart[productKey]['count']) >= parseInt(productCart[productKey]['maxNum'])) {
            productPrice = parseFloat(productCart[productKey]['oldPrice']);
        }
		productCartNumber -= reduceNum;
        productCartMoney = productCartMoney - (productPrice + productPackCharge) * reduceNum;
//		productCartMoney = productCartMoney - productPrice * reduceNum;
        var locationHashParam = locationClassicHash.split('-');
        $.get(ajax_basic_price,{store_id:locationHashParam[1]},function(basic_res){
            if(basic_res.basic_price != nowShop.store.delivery_price){
                nowShop.store.delivery_price = basic_res.basic_price;
                motify.log('起送价已更新！');
                if(nowShop.store.delivery == true && parseFloat(productCartMoney.toFixed(2)) < nowShop.store.delivery_price) {
                    $('#checkCart').hide();
                    $('#checkCartEmpty').addClass('noEmpty').show().html('还差￥' + (nowShop.store.delivery_price - parseFloat(productCartMoney.toFixed(2))).toFixed(2) + '起送');
                    if (nowShop.store.deliver_type != 2 && nowShop.store.deliver_type != 5) {
                        if (nowShop.store.extra_price > 0) {
                            $('.header_data').html(nowShop.store.extra_price);
                            $('#top_fei, .top_header').show();
                            if ($('.top_header').is(':hidden')) {
                                $('#shopProductLeftBar,#shopProductRightBar,#shopProductBottomBar').css('height', window_height - 166 - 50 - 30);
                            }
                        }
                    }
                }
            }
        },'json');
	}
	
	
	$('#shopProductCart #cartNumber').html(productCartNumber);
	$('#shopProductCart #cartMoney').html(productCartMoney.toFixed(2));

	if(nowIndex != ''){
        $('#checkCartEmpty').hide();
        $('#checkCart').show();
        $('#shopProductLeftBar,#shopProductRightBar,#shopProductBottomBar').css('height',window_height-166-50);
    } else if(productCartNumber == 0){
        if(nowShop.store.pick == true && nowShop.store.delivery == false){
            $('#checkCartEmpty').removeClass('noEmpty').show().html('￥0起送价');
        } else {
            $('#checkCartEmpty').removeClass('noEmpty').show().html(parseFloat(nowShop.store.delivery_price).toFixed(2)+'元起送');
        }
		$('#checkCart').removeClass('noEmpty').hide();
	}else if(nowShop.store.pick == true){
		$('#checkCartEmpty').hide();
		$('#checkCart').show();
		$('#shopProductLeftBar,#shopProductRightBar,#shopProductBottomBar').css('height',window_height-166-50);
        $('#cartInfo .price').removeClass('active');
        if (nowShop.store.deliver_type != 2 && nowShop.store.deliver_type != 5 && nowShop.store.extra_price > 0 && parseFloat(productCartMoney.toFixed(2)) < nowShop.store.delivery_price) {
            $('.header_data').html(nowShop.store.extra_price);
            $('#top_fei, .top_header').show();
            if($('.top_header').is(':hidden')){
				$('#shopProductLeftBar,#shopProductRightBar,#shopProductBottomBar').css('height',window_height-166-50-30);
			}
        } else {
            $('.top_header').hide();
            deliverExtraPrice = 0;
           
        }
		isShowSpell = true;
	}else if(nowShop.store.delivery == true && parseFloat(productCartMoney.toFixed(2)) < nowShop.store.delivery_price){
		$('#checkCart').hide();
		$('#checkCartEmpty').addClass('noEmpty').show().html('还差￥'+(nowShop.store.delivery_price - parseFloat(productCartMoney.toFixed(2))).toFixed(2)+'起送');
		if (nowShop.store.deliver_type != 2 && nowShop.store.deliver_type != 5) {
		    if (nowShop.store.extra_price > 0) {
    		    $('.header_data').html(nowShop.store.extra_price);
    		    $('#top_fei, .top_header').show();
    		    if($('.top_header').is(':hidden')){
    				$('#shopProductLeftBar,#shopProductRightBar,#shopProductBottomBar').css('height',window_height-166-50-30);
    			}
		    }
		}

        isShowSpell = true;
	}else{
		$('#checkCartEmpty').hide();
		$('#checkCart').show();
		$('#shopProductLeftBar,#shopProductRightBar,#shopProductBottomBar').css('height',window_height-166-50);
        $('#cartInfo .price').removeClass('active');
        $('.top_header').hide();
        deliverExtraPrice = 0;
        isShowSpell = true;
       
	}
	if(productCartNumber > 0){
		$('#shopProductCart #emptyCart').hide();
		$('#shopProductCart #cartInfo').show();
	}else{
		if($('#cartInfo').hasClass('isShow')){
			$('#shopProductCartShade').trigger('click');
		}
		$('#top_fei').hide();
		$('#shopProductCart #cartInfo').hide();
		$('#shopProductCart #emptyCart').show();
	}
	if (parseFloat(productCartMoney.toFixed(2)) < nowShop.store.delivery_price) {
	    deliverExtraPrice = 0;
	}
	stringifyCart(type);
}

function stringifyCart(type){
    var cookieProductCart = [];
    for(var i in productCart){
        cookieProductCart.push(productCart[i]);
    }
    
    //记录拼单的每个袋的数据
    if (cartid != '' && nowIndex != '') {
        window.localStorage.setItem(cartid + '_' + nowIndex, JSON.stringify(cookieProductCart));
    }
    
    if(nowIndex == '' && type != 'count'){
		pageLoadTips({showBg:false});
		$.post(ajax_cart_save_url,{shop_id:nowShop.store.id,cart_cookie:cart_cookie,cookieProductCart:cookieProductCart},function(){
			pageLoadHides();
		});
	}
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
		}else if(i=='isDiscountGoods'){
            returnObj[i] = '店内有部分商品限时优惠';
        }else if(i=='isdiscountsort'){
            returnObj[i] = '部分商品分类参与折扣优惠';
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
		}else if(i=='invoice' || i=='discount' || i=='isDiscountGoods' || i=='isdiscountsort'){
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

function showShopContent(nav){
	if(nav == 'product'){
		if(nowShop.store.tmpl == '0'){
			$('#shopCatBar,#shopProductBottomBar').hide();
			$('#shopMenuBar').fadeIn('slow')
			$('#shopProductLeftBar2,#shopProductRightBar2').show();
		}else if(nowShop.store.tmpl == '1'){
			$('#shopMenuBar,#shopProductLeftBar2,#shopProductRightBar2').hide();
			$('#shopProductBottomBar').show();
			$('#shopCatBar').fadeIn('slow').find('.title').html('全部分类');
		}
		if($('#shopProductBox').data('isShow') != '1'){
			$('#shopProductLeftBar2 dl,#shopProductRightBar2 dl').empty();
			storeTheme = nowShop.store.store_theme;
			if (nowShop.product_list) {
				if(nowShop.store.tmpl == '0'){
					laytpl($('#shopProductLeftBarTpl').html()).render(nowShop.sort_list, function(html){
						$('#shopProductLeftBar2 dl').html(html);

						$("#shopProductLeftBar2 dd span").click(function(){
							$(this).parents("dd").addClass("active").siblings("dd").removeClass("active");
                            $(this).siblings("ul").find("li").removeClass("active").find(".p").hide().find('p').removeClass("active");
                            $(this).parents("dd").find('li:first').addClass("active").find('div').show().find('p:first').addClass("active");
							showGoodsBySortId($(this).data('sort_id'), nowShop.store.id);
						});
						$("#shopProductLeftBar2 dd li em").click(function(){
							$(this).parents("li").siblings("li").removeClass("active").find(".p").hide();
                            $(this).parents("li").addClass("active").find(".p").show().find("p").removeClass("active");
                            $(this).parents("li").find('p:first').addClass("active");
							showGoodsBySortId($(this).data('sort_id'), nowShop.store.id);
						});
						$("#shopProductLeftBar2 dd li p").click(function(){
							$(this).addClass("active").siblings("p").removeClass("active");
							showGoodsBySortId($(this).data('sort_id'), nowShop.store.id);
						});

					});
					laytpl($('#shopProductRightBarTpl').html()).render(nowShop.product_list, function(html){
						$('#shopProductRightBar2 dl').html(html);
						$(".lazy").lazyload({effect:"fadeIn",threshold:200,failurelimit:8,container:$('#shopProductRightBar2')});
					});
				} else if (nowShop.store.tmpl == '1') {
					laytpl($('#shopProductTopBarTpl').html()).render(nowShop.product_list, function(html){
						$('#shopCatBar .content ul').html(html);
					});
					laytpl($('#shopProductBottomBarTpl').html()).render(nowShop.product_list, function(html){
						$('#shopProductBottomBar ul').html(html);
						$(".lazy").lazyload({effect:"fadeIn",threshold:200,failurelimit:8,container:$('#shopProductBottomBar')});
					});
					$('#shopProductBottomBar .position_img').height($('#shopProductBottomBar .position_img:eq(0)').width());
					// $('#shopProductBottomBar li').css('margin-top',window_width*0.02);
					$('#shopProductBottomBar ul').css('margin-bottom',window_width*0.02);
				}
			}

            //初始化数据
            var nowShopCart = '';
            if (cartid != '' && nowIndex != '') {
                nowShopCart = window.localStorage.getItem(cartid + '_' + nowIndex);
            }
            var tmpShopCart = [];
            if ((nowShopCart == '' || nowShopCart == null) && nowIndex == '') {		
				pageLoadTips();
				$.post(ajax_cart_url,{shop_id:nowShop.store.id,cart_cookie:cart_cookie},function(result){
					if(!result.status || result.status == 0){
						result.info = [];
					}
					initializeShopCart(result.info);
					if(location.href.indexOf('classic_good') != -1){
						changeProductSpec();
					}
				});
			}else{
				initializeShopCart($.parseJSON(nowShopCart));
			}
            
		}
		pageLoadHides();
	}else if(nav == 'merchant'){
		$('#shopCatBar').hide();
		$('#shopMenuBar').show();
		if($('#shopMerchantBox').data('isShow') != '1'){
            if (nowShop.store.home_url != '') {
                $('#shopMerchantDescBox .merchant').attr('data-url', nowShop.store.home_url);
            } else {
                $('#shopMerchantDescBox .merchant').remove();
            }
			$('#shopMerchantDescBox .phone').attr('data-phone',nowShop.store.phone).html('店铺电话：'+nowShop.store.phone);
			$('#shopMerchantDescBox .address').attr('data-url','map&param='+nowShop.store.id+'-'+nowShop.store.long+'-'+nowShop.store.lat+'-'+encodeURIComponent(nowShop.store.name)+'-'+encodeURIComponent(nowShop.store.adress)).html('<span></span>店铺地址：'+nowShop.store.adress);
			$('#shopMerchantDescBox .openTime').html('营业时间：'+nowShop.store.time);
			$('#shopMerchantDescBox .merchantNotice').html('店铺公告：'+nowShop.store.store_notice);
			if(nowShop.store.delivery){
				$('#shopMerchantDescBox .deliveryType').html('配送服务：由 '+(nowShop.store.delivery_system ? '平台' : '店铺')+' 提供配送');
			}else{
				$('#shopMerchantDescBox .deliveryType').html('配送服务：本店铺仅支持门店自提');
			}

			if(nowShop.store.isverify==1){
				$('#shopMerchantDescBox').append('<dd class="merchantVerify">店铺认证：已认证</dd>');
			}

			if(nowShop.store.store_live_url!=''&& typeof(nowShop.store.store_live_url)!='undefined'){
				$('#shopMerchantDescBox').append('<dd class="storelive" >店铺直播</dd>');
				$('#video_url').attr('src',nowShop.store.store_live_url)
			}

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
            if(tmpCouponList['isDiscountGoods']){
                tmpCouponHtml+= '<dd><em class="system_newuser"></em>'+tmpCouponList['isDiscountGoods']+'</dd>';
            }
            if(tmpCouponList['isdiscountsort']){
                tmpCouponHtml+= '<dd><em class="merchant_discount"></em>'+tmpCouponList['isdiscountsort']+'</dd>';
            }
			$('#shopMerchantCouponBox').html(tmpCouponHtml);
            var store_image = '';
            var im_i = 0;
            $.each(nowShop.store.images, function(i, image){
                im_i ++;
                store_image += '<li><img src="' + image + '" width="100%" height="100%"></li>';
            });
            if (im_i > 3) {
                store_image += '<li><span>更多</span></li>';
            }
            if (store_image == '') {
                $('.store_image').parents('.photo').remove();
            } else {
                $('.store_image').data('pics', nowShop.store.images_str).html(store_image);
            }
            
            var auth_files_image = '';
            var au_i = 0;
            $.each(nowShop.store.auth_files, function(i, file){
                au_i ++;
                auth_files_image += '<li><img src="' + file + '" width="100%" height="100%"></li>';
            });
            if (au_i > 3) {
                auth_files_image += '<li><span>更多</span></li>';
            }
            if (auth_files_image == '') {
                $('.auth_file_image').parents('.photo').remove();
            } else {
                $('.auth_file_image').data('pics', nowShop.store.auth_files_str).html(auth_files_image);
            }
			$('#shopMerchantBox').data('isShow','1');
		}
		pageLoadHides();
	}else if(nav == 'reply'){
		$('#shopCatBar').hide();
		$('#shopMenuBar').show();
		if($('#shopReplyBox').data('isShow') != '1'){
			$('#showMoreReply').data('page','2');
			$('#shopReplyBox ul li:eq(0)').addClass('active').siblings().removeClass('active');
			$('#shopReplyBox dl').empty();
			$.post(shopReplyUrl+nowShop.store.id,{showCount:1},function(result){
				$('#shopReplyBox').data('isShow','1').show();
				if(result == '0'){
				    $('.usats').remove();
					$('#noReply').show();
					$('#showMoreReply').hide();
					$('#shopReplyBox ul').hide();
				}else{	
					result = $.parseJSON(result);
                    $('#shopReplyBox .usats .fen b').width(parseFloat(nowShop.store.star) * 25);
                    if (parseFloat(nowShop.store.star) == 0 && parseFloat(nowShop.store.reply_deliver_score) == 0) {
                        $('.usats').remove();
                    } else if (parseFloat(nowShop.store.star) > 0 && parseFloat(nowShop.store.reply_deliver_score) == 0) {
                        $('.replyScore').html(nowShop.store.star);
                        $('#replyCount').html(nowShop.store.reply_count);
                        $('#replyDeliverScore').html('暂无');
                    } else if (parseFloat(nowShop.store.star) == 0 && parseFloat(nowShop.store.reply_deliver_score) > 0) {
                        $('.replyScore').html('暂无');
                        $('#replyCount').html(nowShop.store.reply_count);
                        $('#replyDeliverScore').html(nowShop.store.reply_deliver_score);
                    } else {
                        $('.replyScore').html(nowShop.store.star);
                        $('#replyCount').html(nowShop.store.reply_count);
                        $('#replyDeliverScore').html(nowShop.store.reply_deliver_score);
                    }
//                    $('.replyScore').html(nowShop.store.star);
//                    $('#replyCount').html(nowShop.store.reply_count);
//                    $('#replyDeliverScore').html(nowShop.store.reply_deliver_score);
					$('#shopReplyDiv ul li:eq(0) em').html(result.all_count);
					$('#shopReplyDiv ul li:eq(1) em').html(result.good_count);
					$('#shopReplyDiv ul li:eq(2) em').html(result.wrong_count);
					$('#shopReplyBox ul').show();
					laytpl($('#shopReplyTpl').html()).render(result.list, function(html){
						$('#shopReplyBox dl').html(html);
					});
					
					if(result.total < result.now){
						$('#showMoreReply').show();
					}else{
						$('#showMoreReply').hide();
					}
					$('#noReply').hide();
				}
				
				pageLoadHides();
			});
		}else{
			pageLoadHides();
		}
	}
	// if(!$('#shopMenuBar li.'+nav).hasClass('active')){
		// $('#shopMenuBar li.'+nav).trigger('click');
	// }
	// setTimeout(function(){
		// pageLoadHides();
	// },1000);
}

function initializeShopCart(nowShopCartArr){
	if(nowShopCartArr && nowShopCartArr.length > 0){
		productCart=[];
		productCartNumber = productCartMoney = 0;
		for(var i in nowShopCartArr){
			var tmpSpec = [];
			var tmpObj = nowShopCartArr[i].productParam;
			if(tmpObj.length > 0){
				for(var j in tmpObj){
					if(tmpObj[j].type == 'spec'){
						tmpSpec.push(tmpObj[j].id);
					}else{
						for(var k in tmpObj[j].data){
							tmpSpec.push(tmpObj[j].data[k].list_id);
							tmpSpec.push(tmpObj[j].data[k].id);
						}
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
			if (parseInt(nowShopCartArr[i].maxNum) > 0 && parseInt(nowShopCartArr[i].count) > parseInt(nowShopCartArr[i].maxNum)) {
				productCartMoney += parseInt(nowShopCartArr[i].maxNum) * parseFloat(nowShopCartArr[i].productPrice);
				productCartMoney += parseInt(parseInt(nowShopCartArr[i].count) - parseInt(nowShopCartArr[i].maxNum)) * parseFloat(nowShopCartArr[i].oldPrice);
			} else {
				productCartMoney += parseFloat(parseInt(nowShopCartArr[i].count) * parseFloat(nowShopCartArr[i].productPrice));
			}
			if (nowShopCartArr[i].productPackCharge != '' && typeof(nowShopCartArr[i].productPackCharge) != 'undefined') {
			    productCartMoney += parseFloat(parseInt(nowShopCartArr[i].count) * parseFloat(nowShopCartArr[i].productPackCharge));
			}
		}
		//统计购物车功能
		cartFunction('count');
	}else{
		cartFunction('count');
	}
	$('#shopProductBox').data('isShow','1');
}

function showGoodsBySortId(sortId, shopId)
{
	$.getJSON(ajax_url_root+'showGoodsBySortId',{'store_id':shopId, 'sort_id':sortId},function(result){
		laytpl($('#shopProductRightBarTpl').html()).render(result.product_list, function(html){
			$('#shopProductRightBar2 dl').html(html);
			$(".lazy").lazyload({effect:"fadeIn",threshold:200,failurelimit:8,container:$('#shopProductRightBar2')});
			$('#shopProductRightBar2').scrollTop(0);
		});
		
		if(productCart.length > 0){
			nowShopCartArr = productCart; 
			productCartNumber = productCartMoney = 0;
			for(var i in nowShopCartArr){
				var tmpSpec = [];
				var tmpObj = nowShopCartArr[i].productParam;
				if(tmpObj.length > 0){
					for(var j in tmpObj){
						if(tmpObj[j].type == 'spec'){
							tmpSpec.push(tmpObj[j].id);
						}else{
							for(var k in tmpObj[j].data){
								tmpSpec.push(tmpObj[j].data[k].list_id);
								tmpSpec.push(tmpObj[j].data[k].id);
							}
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
		}else{
			cartFunction('count');
		}
	});
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






function close_swiper(){
    $('.albumContainer').remove();
}


var myScroll2=null,myScroll3=null;
$(function(){
    $('.photo ul').click(function(){
        var album_more = $(this).data('pics');
        var album_array = album_more.split(',');
        if(motify.checkWeixin()){
            wx.previewImage({
                current:album_array[0],
                urls:album_array
            });
        }else{
            var album_html = '<div class="albumContainer" style="display:block;">';
            album_html += '<div class="swiper-container">';
            album_html += '<div class="swiper-wrapper">';
            $.each(album_array,function(i,item){
                album_html += '<div class="swiper-slide">';
                album_html += '<img src="'+item+'"/>';
                album_html += '</div>';
            });
            album_html += '</div>';
            album_html += '<div class="swiper-pagination"></div><div class="swiper-close" onclick="close_swiper()">X</div>';
            album_html += '</div>';
            album_html += '</div>';
            $('body').append(album_html);
            mySwiper_big = $('.albumContainer .swiper-container').swiper({
                pagination:'.albumContainer .swiper-pagination',
                loop:true,
                grabCursor: true,
                paginationClickable: true
            });
        }
    });
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
		if(locationClassicHash == 'address'){
			getGeoconv(arguments[2],arguments[3]);
		}else{
			if(user_long != '0'){
				getListGeocoder();
			}else if($.cookie('userLocationName')){
				user_long = arguments[2];
				user_lat = arguments[3];
				getListGeocoder();
			}else{
				getGeoconv(arguments[2],arguments[3]);
			}
		}
	}else{
		// alert('2222222226666666');
		pageLoadHides();
	}
}
function getGeoconv(lng,lat){
	$.getJSON('https://api.map.baidu.com/geoconv/v1/?coords='+lng+','+lat+'&from=1&to=5&ak=4c1bb2055e24296bbaef36574877b4e2&callback=getListGeoconvBack&jsoncallback=?');
}
function getListGeoconvBack(obj){
	// alert(JSON.stringify(obj.result));
	user_long = obj.result[0].x;
	user_lat = obj.result[0].y;
	getListGeocoder();
}
function getListGeocoder(){
	$.getJSON('https://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&callback=getListGeocoderBack&location='+user_lat+','+user_long+'&output=json&pois=1&jsoncallback=?');
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
			$('#listRecommend').height(parseInt(($(window).width()/2) * 135 / 280)*2);
			$('#listRecommend .recommendLeft').height(parseInt(($(window).width()/2) * 135 / 280)*2);
			$('#listRecommend .recommendRightTop,#listRecommend .recommendRightBottom').height(parseInt(($(window).width()/2) * 135 / 280));
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

var listShopSearchNowPage=0,shopSearchListHasMorePage = true;
function showShopSearchList(newPage){
	isSearchListShow = true;
	if(newPage || listShopSearchNowPage == 0){
		$('#pageShopSearch #storeListLoadTip').show();
		$('#pageShopSearch #storeList .dealcard').empty();

		listShopSearchNowPage = 1;
		shopSearchListHasMorePage = true;
		pageLoadTips({showBg:false});
	}else{
		listShopSearchNowPage++;
	}
	$.getJSON(ajax_url_root+'ajax_list',{user_lat:user_lat,user_long:user_long,page:listShopSearchNowPage,key:$('#pageShopSearchTxt').val()},function(result){
		if(result.store_list && result.store_list.length > 0){
			laytpl($('#listShopTpl').html()).render(result.store_list, function(html){
				if(newPage){
					$('#pageShopSearch #storeList .dealcard').html(html);
					$('#pageShopSearch #storeList').show();
				}else{
					$('#pageShopSearch #storeList .dealcard').append(html);
				}
			});
			if(result.has_more == false){
				shopSearchListHasMorePage = false;
				$('#pageShopSearch #storeListLoadTip').hide();
			}
		}else{
			shopSearchListHasMorePage = false;
			$('#pageShopSearch #storeListLoadTip').hide();
		}
		isSearchListShow = false;
		pageLoadHides();
	});
}

var listShopNowPage=0,listHasMorePage = true;
function showShopList(newPage){
	isListShow = true;
	if(newPage || listShopNowPage == 0){
		$('#pageList #storeListLoadTip').show();
		$('#pageList #storeList .dealcard').empty();

		listShopNowPage = 1;
		listHasMorePage = true;
		if(isFirstShowList == false){
			pageLoadTips();
		}
	}else{
		listShopNowPage++;
	}
	$.getJSON(ajax_url_root+'ajax_list',{cat_url:cat_url,sort_url:sort_url,type_url:type_url,user_lat:user_lat,user_long:user_long,page:listShopNowPage},function(result){
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
		if(document.referrer == ""){
			window.location.href = storeUrl;
		}else{
			window.history.go(-1);
		}
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
		wx.onMenuShareQQ({
			title: param.title,
			desc: param.desc,
			link: param.link,
			imgUrl: param.imgUrl,
			success: function () { 
			   // 用户确认分享后执行的回调函数
			},
			cancel: function () { 
			   // 用户取消分享后执行的回调函数
			}
		});
	});
}