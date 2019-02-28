$(function(){
	$('#scroller').css({'min-height':($(window).height()+1)+'px'});
	var myScroll = new IScroll('#container', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: true,scrollX: false, scrollY:true,click:iScrollClick()});
	
	var windowWidth = $(window).width();
	$('.hotKeyUl li').css({width:(windowWidth*0.312)+'px'});
	
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
		$('.historyBox .clear').removeClass('none');
		window.addEventListener("pagehide", function(){
			$('#keyword').val('');
		},false);
	});
	
	
	var searchHistory = $.cookie('searchHistory');
	if(searchHistory){
		searchArr = searchHistory.split('~^%@$$@%^~');
		var html = '';
		for(var i in searchArr){
			html+= '<li class="link-url" data-url="'+(searchUrl + '&w='+encodeURIComponent(searchArr[i]))+'">'+searchArr[i]+'</li>';
		}
		$('.historyBox').show().find('ul').prepend(html);
	}else{
		$('.historyBox').show().find('.clear').addClass('none').html('暂无记录');
	}
	
	$('.historyBox .clear').click(function(){
		if(!$(this).hasClass('none')){
			$.cookie('searchHistory','',{expires:-1});
			$('.historyBox ul li:not(.clear)').remove();
			$(this).addClass('none').html('暂无记录');
		}
	});
});