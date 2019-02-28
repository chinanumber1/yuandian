var myScroll,wx,t,s,nomore = false;
$(function(){
	/*if(!app_version){
	$(window).resize(function(){
		window.location.reload();
});}*/

//	myScroll = new IScroll('.left_scroll', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: true, scrollY:false,click:iScrollClick(),scrollbars:false});
	
//	myScroll = new IScroll('.picContent', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: true, scrollY:false,click:iScrollClick(),scrollbars:false});
	if($('.activity').size() > 0){
		var timeDDom = $('.time_d:eq(0)');
		var timeHDom = $('.time_h:eq(0)');
		var timeMDom = $('.time_m:eq(0)');
		var timeSDom = $('.time_s:eq(0)');
		var timer = setInterval(function(){
			var timeJ = parseInt(timeDDom.html());
			var timeH = parseInt(timeHDom.html());
			var timeM = parseInt(timeMDom.html());
			var timeS = parseInt(timeSDom.html());
			if(timeS == 0){
				if(timeM == 0){
					if(timeH == 0){
						if(timeJ == 0){
							clearInterval(timer);
							window.location.reload();
						}else{
							$('.time_d').html(format_time(timeJ-1));
						}
						$('.time_h').html('23');
					}else{
						$('.time_h').html(format_time(timeH-1));
					}
					$('.time_m').html('59');
				}else{
					$('.time_m').html(format_time(timeM-1));
				}
				$('.time_s').html('59');
			}else{
				$('.time_s').html(format_time(timeS-1));
			}
		},1000);
	}
	$('#container').css({top:'50px'});
	if (guess_content_type != 'mall') {
    	var upIcon = $("#up-icon"), downIcon = $("#pullDown");
    	myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false});
    	myScroll.on("scroll",function(){
    		if(this.y >= 50){
    			if(!downIcon.hasClass("reverse_icon")) downIcon.addClass("reverse_icon").find('.pullDownLabel').html('释放可以刷新');
    			return "";
    		}else if(this.y < 50 && this.y > 0){
    			if(downIcon.hasClass("reverse_icon")) downIcon.removeClass("reverse_icon").find('.pullDownLabel').html('下拉可以刷新');
    			return "";
    		}
    	});
    	myScroll.on("slideDown",function(){
    		if(this.y > 50){
    			$('#container').css({'bottom':0});
    			$('.footerMenu,#pullDown').hide();
    			$('#scroller').animate({'top':$(window).height()+'px'},function(){
    				upIcon.removeClass("reverse_icon");
    				pageLoadTip();
    				window.addEventListener("pagehide", function(){
    					$('#container').css({'bottom':'49px'});
    					$('#scroller').css({'top':'0px'});
    					$('.footerMenu,#pullDown').show();
    					pageLoadTipHide();
    				},false);
    				window.location.href =window.location.href;
    			});
    		}
    	});
    	myScroll.on("slideUp",function(){
    		if(this.maxScrollY - this.y > 50 && !nomore){
    			$("#moress").show();
    			getRecommendList('',user_long,user_lat);
    		}else if(nomore){
    			$("#moress").hide();
    			$("#enddate").show();
    		}
    	});
	}
	if($('.swiper-container1').size() > 0){
		var mySwiper = $('.swiper-container1').swiper({
			pagination:'.swiper-pagination1',
			loop:true,
			grabCursor: true,
			paginationClickable: true,
			autoplay:3000,
			autoplayDisableOnInteraction:false,
			simulateTouch:false
		});
	}
	if($('.swiper-container2').size() > 0){
		var mySwiper2 = $('.swiper-container2').swiper({
			pagination:'.swiper-pagination2',
			loop:true,
			grabCursor: true,
			paginationClickable: true,
			simulateTouch:false
		});
	}
	if($('.swiper-container3').size() > 0){
		$('.swiper-container3 .swiper-slide').width($('.swiper-container3 .swiper-slide').width());
		var mySwiper3 = $('.swiper-container3').swiper({
			freeMode:true,
			freeModeFluid:true,
			slidesPerView: 'auto',
			simulateTouch:false/*,
			centeredSlides: true*/
		});
	}
	if($('.swiper-container4').size() > 0){
		$('.swiper-container4 .swiper-slide').width($('.swiper-container4 .swiper-slide').width());
		var mySwiper4 = $('.swiper-container4').swiper({
			freeMode:true,
			freeModeFluid:true,
			slidesPerView: 'auto',
			simulateTouch:false/*,
			centeredSlides: true*/
		});
	}
	if($('.swiper-containerhouse').size() > 0){
		var mySwiper = $('.swiper-containerhouse').swiper({
			pagination:'.swiper-paginationhouse',
			loop:true,
			grabCursor: true,
			paginationClickable: true,
			autoplay:3000,
			autoplayDisableOnInteraction:false,
			simulateTouch:false
		});
	}
	

	// motify.log('正在加载内容',0,{show:true});
	if(user_long == '0'){
		getUserLocation({errorAction:1,okFunction:'getRecommendList',errorFunction:'getRecommendList'});
	}else{
		getRecommendList('',user_long,user_lat);
	}
	if($('.platformNews').size() > 0){
		$('.platformNews .list').width($(window).width()-20-73);
		var platformNewsIndex = 0;
		var platformNewsSize = $('.platformNews .list li').size();
		setInterval(function(){
			platformNewsIndex += 1;
			if((platformNewsIndex*2)+2>platformNewsSize){
				platformNewsIndex = 0;
			}
			$('.platformNews .list li').hide();
			$('.platformNews .list').find('.num-'+((platformNewsIndex*2)+1)+',.num-'+((platformNewsIndex*2)+2)).show();
		},4000);
	}

	$('.qrcodeBtn').click(function(){
		if(motify.checkWeixin()){
			motify.log('正在调用二维码功能');
			wx.scanQRCode({
				desc:'scanQRCode desc',
				needResult:0,
				scanType:["qrCode"],
				success:function (res){
					// alert(res);
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
	
	// $('#moress').click(function(){
		// getRecommendList();
	// })

	$(document).on('click','.recommend-link-url',function(){
		pageLoadTip();
		var tmpObj = $(this);
		var id = tmpObj.data('group_id');
		$.post(group_index_sort_url,{id:id},function(){
			pageLoadTipHide();
			redirect(tmpObj.data('url'),tmpObj.data('url-type'));
			return false;
		});
	});

	$(document).on('click','.hasMore',function(){
		$(this).toggleClass('showMore');
		myScroll.refresh();
		return false;
	});
	var banner_height	=	$(window).width()/320;
	banner_height	=	 Math.ceil(banner_height*119);
	$("#banner_hei").css('height',banner_height);
	
	$("#banner_house_adver").css('height',banner_height+40);
	$("#banner_house_adver img").css('height',banner_height);
	
	$('.recommendLeft').height(parseInt(($(window).width()/2) * 135 / 280)*2);
	$('.recommendRightTop,.recommendRightBottom').height(parseInt(($(window).width()/2) * 135 / 280));

	$(".More_drop").click(function(event){
		event.stopPropagation()
		$(".drop_list").toggle();
	});

	$(".drop_list li").click(function(event){
		event.stopPropagation();

	});

	$(document).click(function(){
		$(".drop_list").hide();
	});

	$(" .payment").click(function(event){
		$.ajax({
			url: payqrcode_url,
			type: 'POST',
			dataType: 'json',
			data: {param1: 'value1'},
			success:function(date){
				showpayqrcode();
				check_scan_order();
				$(".mask,.payment_code .con_img #paybarcode").attr('src','./wap.php?c=My&a=cardbarcode&type=pay')
				$(".mask,.payment_code .con_img #payqrcode").attr('src','./wap.php?c=My&a=cardqrcode&type=pay')
				$(".mask,.payment_code").show();
			},
		 	error: function(xhr, textStatus, errorThrown) {
				motify.log('您还没有登录');
	   		}
		});
		
	});
	$(".mask,.payment_code .del").click(function(event){
		clearTimeout(t)
		clearTimeout(s)
		$(".mask,.payment_code").hide();
	});
	$(".mask,.payment_code .refresh").click(function(event){
		clearTimeout(t)
	
		showpayqrcode()
	});
	$(".drop_list .nearby").click(function(event){
		window.location.href=merchant_around_url;
	});

	

});

function mall_list_ajax()
{
    $("#moress").hide();
    $('.youlike').show()
    var recommend_list_ajax_url = "/wap.php?g=Wap&c=Groupservice&a=mall";
    $.post(recommend_list_ajax_url, function(data){
        if(data.error == 0){
            laytpl($('#indexMallTpl').html()).render(data.data, function (html) {
                $('.allChild').html(html);
                
                myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false});
                $('.left_scroll').each(function(){
                    var id = $(this).attr('id');
                    $('#' + id + ' ul').width($('#' + id + ' ul li').length * 81 + 60);
                    
                    $('#' + id + ' ul li').click(function(e){
                        $(this).addClass('active').siblings('li').removeClass('active');
                        mallGoods($(this).data('id'), false);
                        $('#' + id).animate({"scrollLeft":$(this).offset().left}, 300);
                    });
                    
                    
                    new IScroll('#' + id, { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: true, scrollY:false,click:iScrollClick(),scrollbars:false});
                });
                
            });
        } else {
            $("#enddate").css('display','block');
        }
    },'json');
}


function mallGoods(cateId, isFlash)
{
    $("#moress").hide();
    $('.youlike').show()
    var recommend_list_ajax_url = "/wap.php?g=Wap&c=Groupservice&a=mallGoods";
    $.post(recommend_list_ajax_url,{'cateId':cateId}, function(data){
        if(data.error == 0){
            if (data.data.length > 0) {
                laytpl($('#indexMallGoodsTpl').html()).render(data.data, function (html) {
                    $('#mall_' + data.fid + ' ul').html(html);
                    $('#mall_' + data.fid + ' ul').width($('#mall_' + data.fid + ' ul li').length*160 + 10);
                    if (isFlash) {
                        myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false});
                    }
                    new IScroll('#mall_' + data.fid, { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: true, scrollY:false,click:iScrollClick(),scrollbars:false});
                });
            } else {
                $('#mall_' + data.fid + ' ul').html('');
                $('#mall_' + data.fid + ' ul').width($('#mall_' + data.fid + ' ul li').length*160 + 10);
            }
        }
    },'json');
}


var like_page	=	0;
var page_count	=	10;
function getRecommendList(locationStr,lng,lat){
    if(guess_content_type == 'mall'){
        mall_list_ajax();
    } else if(guess_content_type == 'yuedan'){
        recommend_list_ajax(lng,lat,like_page)
        like_page++;
    }else{
		$.post(window.location.pathname+'?c=Groupservice&a=indexRecommendList&page='+like_page,function(result){
			if(result.nomore==1){
				$("#moress").hide();
				$("#enddate").show();
			}
			if(guess_content_type == 'group' || guess_content_type == 'shop'){
				if(result.length < page_count){
					$("#moress").hide();
				}
			}else if(guess_content_type == 'meal' || guess_content_type == 'store'){
			    console.log(result.store_list.length + '-----------' + page_count)
				if(result.store_list.length < page_count){
				    nomore = true;
					$("#moress").hide();
				}
			}
			if(result!=''){
				laytpl($('#indexRecommendBoxTpl').html()).render(result, function (html) {
					$('.youlike').show().find('.likeBox').append(html);
				});
			}
			
			like_page	=	like_page+page_count;
			if(like_page >= Number(guess_num)){
				nomore = true;
				
				if(like_page> Number(guess_num)){
					$("#enddate").show();
					$("#moress").hide();
				}
			}
			myScroll.refresh();
		});
	}
	
	
	if(lng){
		user_long = lng;
		user_lat = lat;
	}
	if($('.hasManyCity').size() > 0 && !$.cookie('is_location_city_new')){
		geocoder('locationCity',user_long,user_lat);
	}
}

function recommend_list_ajax(lng,lat,page){
    $("#moress").hide();
    $('.youlike').show()
    var recommend_list_ajax_url = "/wap.php?g=Wap&c=Yuedan&a=recommend_list_ajax";
    $.post(recommend_list_ajax_url,{lng:lng,lat:lat,page:page},function(data){
        if(data.error == 1){
            $("#recommend_list").append(data.html);

            $("#enddate").css('display','none');
            }else{
            $("#enddate").css('display','block');

            // alert(data.msg);
        }
        
        myScroll.refresh();
    },'json');

}



function locationCity(result){
	if(result.result && result.result.addressComponent && result.result.addressComponent.city){	
		var city_name = result.result.addressComponent.city;
		city_name = city_name.replace('市','');
		if(city_name != $('#cityBtn').html()){
			$.post('./wap.php?c=Home&a=cityMatching',{city_name:city_name},function(res){
				if(res.status == 1){
					layer.open({
						content: '当前定位到您在'+ city_name + '，是否进行切换？'
						,btn: ['切换', '不要']
						,shadeClose:false
						,yes: function(index){
							$.cookie('now_city',res.info.area_url,{expires:120,path:'/',domain:'.'+$('#cityBtn').data('top_domain')});
							$.cookie('is_location_city_new','1',{path:'/',domain:'.'+$('#cityBtn').data('top_domain')});
							location.reload();
							layer.close(index);
						}
						,no: function(index){
							$.cookie('is_location_city_new','1',{path:'/',domain:'.'+$('#cityBtn').data('top_domain')});
							layer.close(index);
						}
					});
				}
			});
		}
	}
}

function format_time(time){
	return time < 10 ? '0'+time : time;
}

function showpayqrcode(){
	$(".mask,.payment_code .con_img #paybarcode").attr('src','./wap.php?c=My&a=cardbarcode&type=pay')
	$(".mask,.payment_code .con_img #payqrcode").attr('src','./wap.php?c=My&a=cardqrcode&type=pay')
	t= setTimeout('showpayqrcode()',60000)
}

function check_scan_order(){
	$.ajax({
		url: scan_order_url,
		type: 'POST',
		dataType: 'json',
		data: '',
		success:function(date){
			if(date.status){
				window.location.href=date.url
			}
		}
	});
	
	s= setTimeout('check_scan_order()',1000)
}