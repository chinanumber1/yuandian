$(function(){
	if(motify.checkApp()){
		$('header').hide();
		$('#container').css('top','0px');
	}
	
	$('#scroller').css({'min-height':($(window).height()+1)+'px'});
	var myScroll = new IScroll('#container', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: true,scrollX: false, scrollY:true,click:iScrollClick()});
	
	var cityKey = [];
	$.each($('.cityKey'),function(i,item){
		cityKey.push($(item).data('city_key'));
	});
	console.log(cityKey);
	
	$("#selectCharBox").css({'float':'right',top:'20px',height:($(window).height()-40),width:50,'z-index':9998}).seleChar({
		chars:cityKey,
		callback:function(ret){
			console.log(ret);
			myScroll.scrollToElement('#city_'+ret,100)
		}
	});
	
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

	$(document).on('click','.city_location,#historyCityList a',function(){
		var city_url = $(this).data('city_url');
		var city_name = $(this).html();
		var cityHistory = $.cookie('cityHistory');
		if(cityHistory){
			console.log(cityHistory);
			cityArr = cityHistory.split('~^%@$$@%^~');
			var newCityArr = [];
			for(var i in cityArr){
				var nowCityArr = cityArr[i].split('~~');
				console.log(nowCityArr);
				if(nowCityArr[0] != city_url) newCityArr.push(cityArr[i]);
			}
			newCityArr.unshift(city_url+'~~'+city_name);
			var newCityHistory = newCityArr.join('~^%@$$@%^~');
		}else{
			var newCityHistory = city_url+'~~'+city_name;
		}
		$.cookie('cityHistory',newCityHistory,{expires:730,path:'/'});
		$.cookie('now_city',city_url,{expires:730,path:'/',domain:cityTopDomain});
		if(get_wxapp == 1 && motify.checkWeixinApp()){
			console.log({data:{page:'changecity',city_url:city_url,city_name:city_name}});
			wx.miniProgram.postMessage({data:{page:'changecity',city_url:city_url,city_name:city_name}});
			wx.miniProgram.navigateBack();
		}else{
			redirect(indexUrl);
		}
	});

	var cityHistory = $.cookie('cityHistory');
	if(cityHistory){
		cityArr = cityHistory.split('~^%@$$@%^~');
		var html = '';
		for(var i in cityArr){
			var nowCityArr = cityArr[i].split('~~');
			html+= '<li><a data-city_url="'+nowCityArr[0]+'">'+nowCityArr[1]+'</a></li>';
		}
		$('#historyCityList').show().find('ul').prepend(html);
		myScroll.refresh();
	}

	$('.historyBox .clear').click(function(){
		if(!$(this).hasClass('none')){
			$.cookie('searchHistory','',{expires:-1});
			$('.historyBox ul li:not(.clear)').remove();
			$(this).addClass('none').html('暂无记录');
		}
	});
});