var myScroll,myScroll2=null,myScroll3=null,now_page = 0,hasMorePage = true;
$(function(){
	$('#container').css({top:'103px'});
	$('#scroller').css({'min-height':($(window).height()-103+1)+'px'});
	// myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
	myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick()});
	var upIcon = $("#pullUp"),
		downIcon = $("#pullDown");
	myScroll.on("scroll",function(){
		var y = this.y,
			maxY = this.maxScrollY - y,
			downHasClass = downIcon.hasClass("reverse_icon"),
			upHasClass = upIcon.hasClass("reverse_icon");
		if(y >= 50){
			if(!downHasClass) downIcon.addClass("reverse_icon").find('.pullDownLabel').html('松开可以刷新');
			return "";
		}else if(y < 50 && y > 0){
			if(downHasClass) downIcon.removeClass("reverse_icon").find('.pullDownLabel').html('下拉可以刷新');
			return "";
		}
		if(maxY >= 50){
			if(!upHasClass) upIcon.addClass("reverse_icon").find('.pullUpLabel').html('松开加载更多');
			return "";
		}else if(maxY < 50 && maxY >=0){
			if(upHasClass) upIcon.removeClass("reverse_icon").find('.pullUpLabel').html('上拉加载更多');
			return "";
		}
	});
	myScroll.on("slideDown",function(){
		if(this.y > 50){
			now_page = 0;
			hasMorePage = true;
			upIcon.removeClass('noMore loading').show();
			pageLoadTip(103);
			getList(false);
		}
	});
	myScroll.on("slideUp",function(){
		if(this.maxScrollY - this.y > 50 && !upIcon.hasClass('noMore')){
			upIcon.addClass('noMore').hide();
		}
	});
	myScroll.on("scrollEnd",function(){
		if(hasMorePage && upIcon.hasClass('noMore') && !upIcon.hasClass('loading')){
			$('.listBox dl').append('<dd class="loadMoreList">正在加载</dd>');
			myScroll.refresh();
			myScroll.scrollTo(0,this.maxScrollY);
			upIcon.addClass('loading');
			getList(true);
		}
	});
	/* $(window).resize(function(){
		window.location.reload();
	}); */
	
	$('#search-form').submit(function(){
		var keyword = $.trim($('#keyword').val());
		$('#keyword').val(keyword);
		if(keyword.length == 0){
			layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'请输入搜索词！',btn: ['确定']});
			return false;
		}
		var searchHistory = $.cookie('searchHistory');
		if(searchHistory){
			searchArr = searchHistory.split('~^%@$$@%^~');
			var newSearchArr = [];
			for(var i in searchArr){
				if(searchArr[i] != keyword) newSearchArr.push(searchArr[i]);
			}
			newSearchArr.unshift(keyword);
			newSearchHistory = newSearchArr.join('~^%@$$@%^~');
		}else{
			newSearchHistory = keyword;
		}
		$.cookie('searchHistory',newSearchHistory,{expires:730,path:'/'});
		window.addEventListener("pagehide", function(){
			$('#keyword').val('');
		},false);
	});

	$('.navBox li').click(function(){
		$(this).addClass('active').siblings('li').removeClass('active');
		now_sort = $(this).data('sort');
		
		$('.listBox dl').empty().hide();
		$('.listBox .no-deals,.noMoreList').addClass('hide');	
		$("#pullUp").removeClass('noMore loading').show();
		pageLoadTip(103);
		now_page = 0;
		getList(false);
	});
});

function getList(more){
	var go_url = location_url;
	if(now_sort != 'defaults'){
		go_url += "&sort="+now_sort;
	}
	now_page += 1;
	go_url += "&page="+now_page;
	$.post(go_url,function(result){
		if(result.meal_count > 0){
			hasMorePage = now_page < result.totalPage ? true : false;
			laytpl($('#groupListBoxTpl').html()).render(result, function(html){
				if(more){
					if(hasMorePage){
						$("#pullUp").removeClass('noMore loading').show();
					}
					$('.loadMoreList').remove();
					$('.listBox dl').append(html);
				}else{
					$('.listBox dl').html(html).addClass('dealcard').show();
				}
			});
			if(!hasMorePage){
				$("#pullUp").addClass('noMore').hide();
				$('.noMoreList').removeClass('hide');
			}
		}else{
			$("#pullUp").addClass('noMore').hide();
			$('.listBox dl').hide();
			$('.listBox .no-deals').removeClass('hide');
		}
		pageLoadTipHide();
		myScroll.refresh();
		if(!more){
			myScroll.scrollTo(0,0);
		}
	});
}