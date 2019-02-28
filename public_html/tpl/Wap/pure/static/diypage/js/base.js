if (typeof storeId == "undefined") {
	storeId = 0;
}

//商品限购
var buyer_quota = 0;

/* 用于购物车等 fixed absolute元素拖动 , 防止挡住其他元素 */
function dragTool(moveId) {

	var mousex = 0, mousey = 0;
	var divLeft = 0, divTop = 0, left = 0, top = 0;

	document.getElementById(moveId).addEventListener('touchstart', function(e){
		//e.preventDefault();
		var offset = $(this).offset();
		divLeft = parseInt(offset.left,10);
		divTop = parseInt(offset.top,10);
		mousey = e.touches[0].pageY;
		mousex = e.touches[0].pageX;
		return false;
	});
	document.getElementById(moveId).addEventListener('touchmove', function(event){
		event.preventDefault();
		left = event.touches[0].pageX-(mousex-divLeft);
		top = event.touches[0].pageY-(mousey-divTop)-$(window).scrollTop();
		if(top < 1){
			top = 1;
		}
		if(top > $(window).height()-(50+$(this).height())){
			top = $(window).height()-(50+$(this).height());
		}
		if(left + $(this).width() > $(window).width()-5){
			left = $(window).width()-$(this).width()-5;
		}
		if(left < 1){
			left = 1;
		}
		$(this).css({'top':top + 'px', 'left':left + 'px', 'position':'fixed'});
		//return false;
	});

}

var is_loading_cart = false;
var motify = {
	timer:null,
	log:function(msg){
		$('.motify').hide();
		if(motify.timer) clearTimeout(motify.timer);
		if($('.motify').size() > 0){
			$('.motify').show().find('.motify-inner').html(msg);
		}else{
			$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
		}
		motify.timer = setTimeout(function(){
			$('.motify').hide();
		},3000);
	},
	checkMobile:function(){
		if(/(iphone|ipad|ipod|android|windows phone)/.test(navigator.userAgent.toLowerCase())){
			return true;
		}else{
			return false;
		}
	}
};

function layer_tips(msg_type,msg_content){
	layer.closeAll();
	var time = msg_type==0 ? 3 : 4;
	var type = msg_type==0 ? 1 : (msg_type != -1 ? 0 : -1);
	if(type == 0){
		msg_content = '<font color="red">'+msg_content+'</font>';
	}
	$.layer({
		title: false,
		offset: ['80px',''],
		closeBtn:false,
		shade:[0],
		time:time,
		dialog:{
			type:type,
			msg:msg_content
		}
	});
}

/*计算JSON对象长度*/
function getJsonObjLength(obj){
	var l = 0;
	for (var i in obj){
		l++;
	}
	return l;
}

$(function(){

	if(motify.checkMobile() === false && $('.headerbar').size() == 0){
		$('html').removeClass('responsive-320').addClass('responsive-540 responsive-800');
	}
	if($('.js-navmenu').size() > 0){
		$('.content').css('min-height',$(window).height()-$('.container > .header').height()-$('.js-navmenu').height()-$('.js-footer').height()+'px');
		$('.js-mainmenu').click(function(e){
			e.stopPropagation();
			var submenu = $(this).next('.js-submenu:hidden');
			$(this).closest('.nav-item').siblings('.nav-item').find('.js-submenu').hide();
			if(submenu.size() > 0){
				submenu.show();
				var subleft = $(this).offset().left+(($(this).width()-submenu.width())/2)-7;
				var arrowleft = (submenu.width()+6)/2;
				submenu.css({'opacity':1,'left':(subleft > 5 ? subleft : 5) + 'px','right':'auto'}).find('.before-arrow,.after-arrow').css({'left':arrowleft+'px'});
			} else {
				$(this).next('.js-submenu').hide();
			}
		});
		
		$('body').click(function(e){
			$('.js-navmenu .js-submenu').hide();
		});
	}else{
		$('.content').css('min-height',$(window).height()-$('.container > .header').height()-$('.js-footer').height()-($('.js-bottom-opts').size() ? '40' : '0')+'px');
	}
	if(typeof(noCart) == 'undefined'){
		$.post('./saveorder.php?action=cart_count',function(result){
			if(result.err_code == 0 && result.err_msg.count != '0'){
				var cartObj = $('<div id="right-icon" class="js-right-icon icon-hide no-border" data-count="'+result.err_msg.count+'"><div class="right-icon-container clearfix"><a id="global-cart" href="./cart.php?id='+result.err_msg.store_id+'" class="no-text new showcart"><p class="right-icon-img"></p><p class="right-icon-txt">购物车</p></a></div></div>');
				$('body').append(cartObj);
				dragTool('right-icon');
			}
		});
	}
	//检测滚动公告
	if($('.js-scroll-notice').size() > 0){
		$.each($('.js-scroll-notice'),function(i,item){
			var nowDom = $(item);
			var nowWidth = nowDom.width();
			var fWidth = $(item).closest('.custom-notice-inner').width();
			if(nowWidth > fWidth){
				nowDom.css('position','relative');
				var nowLeft = 0;
				window.setInterval(function(){
					if(nowLeft+nowWidth<0){
						nowLeft = fWidth;
					}else{
						nowLeft = nowLeft-1;
					}
					nowDom.css('left',nowLeft + 'px');
				},30);
			}
		});
	}
	//检测图片广告
	if($('.custom-image-swiper').size()){
		$.each($('.custom-image-swiper'),function(i,item){

			var self = $(item);

			if(self.data('max-height') && self.data('max-width') && self.data('max-width')>$('.content').width()){
				var img_height = self.data('max-height') * $('.content').width() / self.data('max-width');
				self.find('.swiper-container').height(img_height);
				self.find('.swiper-slide').height(img_height);
				self.find('.swiper-slide a').height(img_height);
			}

			var class_page = 'swiper_img_pg'+i;
			
			$(".swiper-pagination", self).addClass(class_page);

			if (self.find(".swiper-slide").size() == 1) {
				return;
			}
			try {
				self.find('.swiper-container').swiper({
					pagination:'.'+class_page,
					loop:true,
					grabCursor: true,
					paginationClickable: true,
					autoplay:3000,
					autoplayDisableOnInteraction:false
				});
			} catch (e) {
				
			}
		});
	}

	// 店铺动态swiper js
	if ($(".shopIndex").size()) {

		$.each($(".shopIndex"), function(i, item){
	        var self = $(item);
	        var page = $(".swiper-pagination", self);
	        var class_box = 'swiper_box'+i;
	        var class_page = 'swiper_pg'+i;
	        var _class_box = '.'+class_box
	        var _class_page = '.'+class_page
	        var swiperX = [];

	        var more_btn = self.find(".title a");
	        self.addClass(class_box);
	        page.addClass(class_page);

	        swiperX[i] = new Swiper(_class_box, {
	            pagination: _class_page,
	            paginationClickable: true,
	            spaceBetween: 10,
	            onSlideChangeEnd: function(){
		        	var aid = $(".swiper-wrapper", self).children('li[class*="swiper-slide-active"]').attr('aid');
					more_btn.attr('aid',aid);
		        }
	        });
	    });
	    
	}

	// 活动swiper js
	if ($(".scroller").size()) {
		var swiperActivity = [];
		$.each($('.scrollBox'),function(i,item){

			var self = $(item);

			if (self.find("li").length >= 1) {

				var class_box = 'swiper_activity_box'+i;
				var display_mode = parseInt(self.data('display_mode'));

				self.find(".scroller").addClass(class_box+' swiper-container swiper-container-horizontal');
				self.find("ul").addClass("swiper-wrapper clearfix");
				self.find("li").addClass("swiper-slide");

				var activityOptions = {
					spaceBetween: 10,
			        slidesPerView: 2.01,
			        slidesPerGroup : 2,
			        paginationClickable: true
				};

				if (self.find("li").length == 1) {
					self.find("li.swiper-slide:last").after("<li class='swiper-slide' style='border-color:#fff;background-color:#fff;'></li>");
				}

				if (display_mode != 1) {
					activityOptions = $.extend(activityOptions, {
						// loop:true,
						autoplay:3000,
						autoplayDisableOnInteraction:false
					});
				}

				swiperActivity[i] = new Swiper('.'+class_box, activityOptions);

			}

	    });

	}

	//检测地图
	if($('.js-map-box').size() > 0){
		var mapBox = [];
		
		$.each($('.js-map-box'),function(i,item){
			$('.js-map-box').height($('.js-map-box').width());
			if(typeof (is_google_map)!="undefined"){
                lng = parseFloat($(item).data('lng'));
                lat = parseFloat($(item).data('lat'));
                shopName = $(item).data('title');
                map = new google.maps.Map(document.getElementById($(item).attr('id')), {
                    mapTypeControl:false,
                    zoom: 16,
                    center: {lng,lat}
                });

                var marker = new google.maps.Marker({
                    position: {lng,lat},
                    map: map,
                    draggable:false
                });
                attachSecretMessage(marker, decodeURIComponent(shopName));

                function attachSecretMessage(marker, secretMessage) {
                    var infowindow = new google.maps.InfoWindow({
                        content: secretMessage
                    });
                    infowindow.open(marker.get('map'), marker);
                    marker.addListener('click', function() {
                        infowindow.open(marker.get('map'), marker);
                    });
                }
			}else{
                // 百度地图API功能
                var map = new BMap.Map($(item).attr('id'),{enableMapClick:false});
                map.centerAndZoom(new BMap.Point($(item).data('lng'),$(item).data('lat')), 16);

                var marker1 = new BMap.Marker(new BMap.Point($(item).data('lng'),$(item).data('lat')));  //创建标注
                map.addOverlay(marker1);                 // 将标注添加到地图中
                //创建信息窗口
                var infoWindow1 = new BMap.InfoWindow($(item).data('title'));
                marker1.openInfoWindow(infoWindow1);
                marker1.addEventListener("click", function(){this.openInfoWindow(infoWindow1);});
			}
		});
	}
	
	//检测图片轮播图
	if($('.js-goods-list.waterfall').size() > 0){
		if($('.content').width() >= 540){
			var li_width = ($('.content').width()-10)/3;	
		}else{
			var li_width = ($('.content').width()-10)/2;
		}
		$('.js-goods-list.waterfall').each(function(i) {
			$(this).children('.goods-card').width(li_width);
			$(this).waterfall({
				column_index:i,
				column_className:'waterfall_column-' + i,
				column_width:li_width,
				column_space:0,
				cell_selector:'.goods-card',
			});
		})

		/*$('.js-goods-list.waterfall').eq(0).waterfall({
			column_index:0,
			column_className:'waterfall_column-0',
			column_width:li_width,
			column_space:0,
			cell_selector:'.goods-card',
		});
		$('.js-goods-list.waterfall:eq(1) .goods-card').width(li_width);
		$('.js-goods-list.waterfall').eq(1).waterfall({
			column_index:1,
			column_className:'waterfall_column-1',
			column_width:li_width,
			column_space:0,
			cell_selector:'.goods-card'
		});*/
	}
	$('#quckArea #quckIco2').click(function(){
		if($('#quckArea').hasClass('more_active')){
			$('#quckArea').removeClass('more_active');
		}else{
			$('#quckArea').addClass('more_active');
		}
	});
	$('#quckArea #quckMenu a').click(function(){
		$('#quckArea').removeClass('more_active');
	});

	$('.search-input').focus(function(){
		$('#J_PopSearch').show();
		$('#ks-component').show();
	});

	$('.j_CloseSearchBox').click(function(){
		$('#J_PopSearch').hide();
		$('#ks-component').hide();
	});


	$('.s-combobox-input').keyup(function(e){
		var val = $.trim($(this).val());
		if(e.keyCode == 13){
			if(val.length > 0){
				window.location.href = './category.php?keyword='+encodeURIComponent(val);
			}else{
				motify.log('请输入搜索关键词');
			}
		}
		$('.j_PopSearchClear').show();
	});

	$('.j_PopSearchClear').click(function(){
		$('.s-combobox-input').val('');
	});


/*
	if((getOs() == 'MSIE' && ieVersion() < 9) || $('.storeContact').attr('open-url') == ''){  //联系卖家
		var tel = $('.storeContact').attr('data-tel');
		$('.storeContact').html('<a href="tel:' + tel + '">2324' + tel + '</a>');
		//$('.storeContact').html('<a href="tel:' + tel + '">' + tel + '</a><a class="item-first-shop-wx chat openWindow">1联系卖家</a>');
	}else{
		var tel = $('.storeContact').attr('data-tel');
		$('.storeContact').html('<a href="tel:' + tel + '">' + tel + '</a><a class="item-first-shop-wx chat openWindow">2联系卖家</a>');
	}

    $('.openWindow').on('click',function(){
    	var is_login 	= $('.storeContact').attr('login-status');
		if(is_login==0){
			window.location.href = '/wap/login.php';
		}else {
			var url 	= $('.openWindow').parent('.storeContact').attr('open-url');
			window.location.href = url;
		}
    });
	*/
//////////////////////
	if((getOs() == 'MSIE' && ieVersion() < 9) || $('.storeContact').attr('open-url') == ''){  //联系卖家
		var tel = $('.storeContact').attr('data-tel');
		$('.storeContact').html('<a href="tel:' + tel + '">' + tel + '</a>');
	}else{
		var tel = $('.storeContact').attr('data-tel');
		var qq = $('.storeContact').attr('open-url');
		var opencontact = '<div id="enter_im_div" style="-webkit-transition:opacity 200ms ease;transition:opacity 200ms ease;opacity:1;display:block;cursor:move;z-index:10000"><a id="enter_im" href="javascript:void(0)"><div id="to_user_list"><div id="to_user_list_icon_div" class="rel left"><em class="to_user_list_icon_em_a abs">&nbsp;</em> <em class="to_user_list_icon_em_b abs">&nbsp;</em> <em class="to_user_list_icon_em_c abs">&nbsp;</em> <em class="to_user_list_icon_em_d abs">&nbsp;</em> <em id="to_user_list_icon_em_num" class="hide abs">0</em></div><p id="to_user_list_txt" class="left openWindow" style="font-size:12px">联系客服</p></div></a></div>';
		
		if (typeof qq != "undefined" && qq.length > 0) {
			var qq_arr = qq.split(',');
			var html = '<ul>';
			for (var i in qq_arr) {
				var qq = qq_arr[i].split('|');
				html += '<li><a href="http://wpa.qq.com/msgrd?v=3&uin=' + qq[1] + '&site=qq&menu=yes"><span><i></i>' + qq[0] + '</span><span>点击咨询</span></a></li>';
			}
			html += '</ul>';
			$(".js-qq_service").html(html);
		}
		
		$('.storeContact').html('<a href="tel:' + tel + '">' + tel + '</a>'+opencontact);
		$("#enter_im_div").show();
	}

	$('.openWindow').on('click', function() {
		$(".layer,.consultationLayer").fadeToggle('300'); //显示咨询客服
		
		return;
		
		var is_login 	= $('.storeContact').attr('login-status');
		if(is_login==0){
			window.location.href = 'login.php';
		}else {
			var url 	= $('.openWindow').closest('.storeContact').attr('open-url');
			window.location.href = url;
		}
	});

	$(".js-goods-cart-btn").live("click", function () {
		location.href = $(this).closest('li').find('.js-goods').attr('href');
	});
//////////////////////	
});

function getOs()  
{  
    var OsObject = "";  
   if(navigator.userAgent.indexOf("MSIE")>0) {  
        return "MSIE";  
   }  
   if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){  
        return "Firefox";  
   }  
   if(isSafari=navigator.userAgent.indexOf("Safari")>0) {  
        return "Safari";  
   }   
   if(isCamino=navigator.userAgent.indexOf("Camino")>0){  
        return "Camino";  
   }  
   if(isMozilla=navigator.userAgent.indexOf("Gecko/")>0){  
        return "Gecko";  
   }  
    
}


function ieVersion(){
    if(navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.match(/6./i)=="6."){
        return  6;
    }
    else if(navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.match(/7./i)=="7."){
        return  7;
    }
    else if(navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.match(/8./i)=="8."){
        return  8;
    }
    else if(navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.match(/9./i)=="9."){
        return 9;
    }
}

/***以字符串行书输出一个OBJ**便于查看对象的值*****/
var obj2String = function(_obj) {
    var t = typeof(_obj);
    if (t != 'object' || _obj === null) {
        // simple data type
        if (t == 'string') {
            _obj = '"' + _obj + '"';
        }
        return String(_obj);
    } else {
        if (_obj instanceof Date) {
            return _obj.toLocaleString();
        }
        // recurse array or object
        var n, v, json = [],
        arr = (_obj && _obj.constructor == Array);
        for (n in _obj) {
            v = _obj[n];
            t = typeof(v);
            if (t == 'string') {
                v = '"' + v + '"';
            } else if (t == "object" && v !== null) {
                v = this.obj2String(v);
            }
            json.push((arr ? '': '"' + n + '":') + String(v));
        }
        return (arr ? '[': '{') + String(json) + (arr ? ']': '}');
    }
};