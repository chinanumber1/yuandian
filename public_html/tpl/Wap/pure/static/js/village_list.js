var myScroll,now_page = 0,hasMorePage = true,isLoading = true;
var isApp = motify.checkApp();
$(function(){
	if(isApp){
		$('header').hide();
	}
	$('#backBtn').click(function(){
		redirect(backUrl,'openLeftWindow');
	});
	$('#scroller').css({'min-height':($(window).height()-57+1)+'px'});
	// myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
    myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
    var upIcon = $("#pullUp"),
		downIcon = $("#pullDown");
    
	myScroll.on("scroll",function(){
		var maxY = this.maxScrollY - this.y;
		if(this.y >= 50){
			if(!downIcon.hasClass("reverse_icon")) downIcon.addClass("reverse_icon").find('.pullDownLabel').html('松开可以刷新');
			return "";
		}else if(this.y < 50 && this.y > 0){
			if(downIcon.hasClass("reverse_icon")) downIcon.removeClass("reverse_icon").find('.pullDownLabel').html('下拉可以刷新');
			return "";
		}
		if(maxY >= 50){
			if(!upIcon.hasClass("reverse_icon")) upIcon.addClass("reverse_icon").find('.pullUpLabel').html('松开加载更多');
			return "";
		}else if(maxY < 50 && maxY >=0){
			if(upIcon.hasClass("reverse_icon")) upIcon.removeClass("reverse_icon").find('.pullUpLabel').html('上拉加载更多');
			return "";
		}
	});
	myScroll.on("slideDown",function(){
		if(this.y > 50){
			$('#bindVillageBox,#villageBox,#bindFamilyBox').hide();
			now_page = 0;
			hasMorePage = true;
			upIcon.removeClass('noMore loading').show();
			pageLoadTip(57);
			getUserLocation({okFunction:'pageGetList',okFunctionParam:[true],errorFunction:'pageGetList',errorFunctionParam:[false]});
		}
	});
	myScroll.on("slideUp",function(){
		if(hasMorePage){
			$('.villageBox dl').append('<dd class="loadMoreList">正在加载</dd>');
			// upIcon.addClass('loading');
			// setTimeout(function(){
				myScroll.refresh();
				myScroll.scrollTo(0,this.maxScrollY);
				getList(true);
			// },200);
		}
		/* if(this.maxScrollY - this.y > 50 && !upIcon.hasClass('noMore')){
			upIcon.addClass('noMore').hide();
		} */
	});
	/* myScroll.on("scrollEnd",function(){
		if(hasMorePage && upIcon.hasClass('noMore') && !upIcon.hasClass('loading')){
			$('.listBox dl').append('<dd class="loadMoreList">正在加载</dd>');
			upIcon.addClass('loading');
			// setTimeout(function(){
				myScroll.refresh();
				myScroll.scrollTo(0,this.maxScrollY);
				getList(true);
			// },200);
		}
	}); */
	/* $(window).resize(function(){
		window.location.reload();
	}); */
	
    if(isApp){
		$('header').hide();
        $('#container').css({'top':'60px'});
    }
    
	$('#search-form').submit(function(){
		var keyword = $.trim($('#keyword').val());
		$('#keyword').val(keyword);
		$('#bindVillageBox,#villageBox,#bindFamilyBox').hide();
		now_page = 0;
		hasMorePage = true;
		upIcon.removeClass('noMore loading').show();
		pageLoadTip(57);
		getUserLocation({okFunction:'pageGetList',okFunctionParam:[true],errorFunction:'pageGetList',errorFunctionParam:[false]});
		return false;
	});
	$(document).on('click','.listBox li.more',function(){
		$(this).hide().siblings('li').show();
		$(this).prev().css({'border-bottom':'none'});
		// setTimeout(function(){
		myScroll.refresh();
		// },200);
	});
	
	// $('.listBox').css('min-height',$(window).height()-95);
	pageLoadTip(57);
	if(user_long == '0'){
		getUserLocation({okFunction:'pageGetList',okFunctionParam:[true],errorFunction:'pageGetList',errorFunctionParam:[false]});
	}else{
		pageGetList(user_long,user_lat);
	}
});
function pageGetList(){
	getList(false);
}

function getList(more){
	isLoading = true;
	var go_url = location_url;
	now_page += 1;
	go_url += "&page="+now_page;
	$('.noMoreDiv').hide();
	$.post(go_url,{keyword:$('#keyword').val(),account:account,user_long:user_long,user_lat:user_lat},function(result){
		$('.loadMoreList').remove();
		if(now_page == 1 && result.bind_village_list){
			laytpl($('#villageBoxTpl').html()).render(result.bind_village_list, function(html){
				$('#bindVillageBox').show().find('dl').html(html);
			});
		}
		
		if(now_page == 1 && result.bind_village_family_list){
			laytpl($('#village_bind_familyBoxTpl').html()).render(result.bind_village_family_list, function(html){
				$('#bindFamilyBox').show().find('dl').html(html);
			});
		}
		
		if(result.login_test && (document.domain.indexOf('.pigcms.com') != -1 || document.domain.indexOf('.group.com') != -1)){
			layer.open({
				content:'为了方便您测试 社区O2O 更多功能，建议您先进行登录。我们然后为您默认绑定一个测试小区。',
				btn: ['去登录','先看看'],
				yes:function(){
                    if(isApp){
                        if(motify.checkAndroid()){
                            window.lifepasslogin.startJsToJavaFunction();
                            function ReturnLastPage(){
                                history.back();
                            }
                        }
                        if(motify.checkIos()){
                            $('body').append('<iframe src="pigcmso2o://login" style="display:none"></iframe>');
                            function refresh(){
								alert(222222);
                                window.location.reload();
                            }
                        }
                    }else{
					    window.location.href = window.location.pathname+'?c=Login&a=index';
                    }
				}
			});
		}
		if(result.first_test && (document.domain.indexOf('.pigcms.com') != -1 || document.domain.indexOf('.group.com') != -1)){
			layer.open({
				content:'为了方便您测试 社区O2O 更多功能，我们为您默认绑定了一个测试小区。建议您进入此小区测试完整的功能。',
				btn: ['好的'],
			});
		}
		if(result.village_list && result.village_list.village_count > 0){
			hasMorePage = now_page < result.village_list.totalPage ? true : false;
			laytpl($('#villageBoxTpl').html()).render(result.village_list.village_list, function(html){
				if(now_page == 1){
					$('#villageBox').show().find('dl').html(html);
				}else{
					$('#villageBox').show().find('dl').append(html);
				}
			});
			if(!hasMorePage){
				$("#pullUp").addClass('noMore').hide();
				$('#villageBox dl').append('<dd class="noMore">更多小区正在入驻，敬请期待!</dd>');
			}
		}else{
			$('.noMoreDiv').show();
			$("#pullUp").addClass('noMore').hide();
		}
		pageLoadTipHide();
		setTimeout(function(){
			// console.log(more);
			myScroll.refresh();
			if(!more){
				myScroll.scrollTo(0,0);
			}
		},200);
		isLoading = false;
	});
}
function androidRefresh(ticket,device){
	if(ticket && device){
		$.post("./wap.php?c=Home&a=ajaxLogin", { ticket: ticket, 'Device-Id': device },function(){
			location.reload();
		});
	}
}