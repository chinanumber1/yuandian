var myScroll;
var ifReload = false;
var ifNoMore = false;
window['pageLoadedSuccess'] = true;
function setTimer(){
	var myDate = new Date();
	var hours = myDate.getHours();
	var minutes = myDate.getMinutes() > 10?myDate.getMinutes():'0'+myDate.getMinutes()
	$('#reload').find('.time').html('今天 '+myDate.getHours() + ':' + minutes);
}
function getPageData(isFirstPage){
	if(!window['pageLoadedSuccess']){ return false;}
	window['pageLoadedSuccess'] = false;
	var current_host = window.location;
	var url_obj = $.url(current_host).param();
	var iPage = 1;
	if(url_obj['page'] !== '' && typeof url_obj['page'] !== 'undefined'){
		iPage = parseInt(url_obj['page']);
	}
	if(isFirstPage === '1'){
		iPage--;
	}else if(isFirstPage === '2'){//真正的第一页
		iPage = 0;
	}else{
		
	}
	
	
	var url = siteUrl + 'request_paging.ashx?jsoncallback=?&table_id=11&tplpath=list&tplname=news_list.json&a='+$('#bigcatid').val()+'&b='+$('#smallcatid').val()+'&p='+(iPage+1)+'&PageSize=20&isjson=1';
	$.getJSON(url,function(data){
		setTimeout(function(){
			$('#pullUp').hide();
			$('#pageLoader').hide();
			if(typeof isFirstPage !== 'undefined'){
				ifNoMore = false;
				$('#innerrow').empty();
				$('#pullDown').find('.loader').hide();
				$('#pullDown').hide();
				$('#reload').find('.txt').html('下拉可以刷新');
				$('#reload').find('.s').removeClass('s_ok');
				setTimer();
				myScroll.scrollTo(0, 0, 500)
			}
			
			if(iPage == data[0].PageCount || data[0].PageCount == '0'){
				
				ifNoMore = true;
				lis = document.createElement('li');
				lis.innerText = '没有更多了';
				lis.className = 'noMore';
				$('#innerrow').append(lis);
				setTimeout(function () {
					myScroll.refresh();
				}, 0);
				return false;
			}
			for(var i=0;i<data[0].MSG.length;i++){
				var TPL=$('#tp').html().replace(/[\n\t\r]/g, '');
				if(data[0].MSG[i].filepath === ''){
					data[0].MSG[i].hasImg = '0';
				}else{
					data[0].MSG[i].hasImg = '1';
				}
				$('#innerrow').append(Mustache.to_html(TPL, data[0].MSG[i]));
			}
			setTimeout(function () {
				myScroll.refresh();
				setTimeout(function(){lazyImg('#innerrow');},50);
				window['pageLoadedSuccess'] = true;
				if($.cookie('myZXsid') !== undefined){
					$('#item'+$.cookie('myZXsid'))[0] && myScroll.scrollToElement( $('#item'+$.cookie('myZXsid'))[0], 500,0,-150,'');
				}
				$.removeCookie('myZXsid',{ path:'/'});
			}, 0);
			history.pushState(null, '', '?page='+(iPage+1));
			
		},500);
	});
}

function loaded_page(){
	$('body').css({'min-height':$(window).height()+'px'});
	$(window).bind('resize',function(){
		$('body').css({'min-height':$(window).height()+'px'});
	});
	getPageData('1');
	var pullDownHeight = 50;
	myScroll = new IScroll('#wrapper', {
		probeType: 2,
		mouseWheel: true,
		click: true,
		scrollX: false,
		scrollY: true,
		scrollbars: false,
		interactiveScrollbars: true,
		shrinkScrollbars: 'scale',
		fadeScrollbars: true
	});
	myScroll.on('scroll',function(){
		if(this.y < 0){
			if(ifReload === true){
				ifReload = false;
				$('#reload').find('.txt').html('下拉可以刷新');
				$('#reload').find('.s').removeClass('s_ok');
			}
			return;
		}
		$('#reload').show();
		if(this.y > pullDownHeight && !ifReload){
			ifReload = true;
			$('#reload').find('.txt').html('释放马上刷新');
			$('#reload').find('.s').addClass('s_ok');
			$('#pullDown').show();
			myScroll.refresh();
			
		}
	});
	myScroll.on('scrollEnd', function () {  
		setTimeout(function(){lazyImg('#innerrow');},50);
		if ((this.y - this.maxScrollY) < 10) {             
			!ifNoMore&&pullUpAction();
		}
		if(!!ifReload){
			pullDownAction();
		}else{
			if(!!$('#pullDown:visible')[0]){
				$('#reload').hide();
				$('#pullDown').hide();
				myScroll.refresh();
				myScroll.scrollTo(0, 0);
			}
			
		}
	});
	function pullDownAction(){
		ifReload = false;
		$('#reload').hide();
		$('#pullDown').find('.loader').show();
		getPageData('2');
	}
	function pullUpAction(){
		setTimeout(function(){
			$('#pullUp').show();
			myScroll.refresh();
			myScroll.scrollBy(0, -pullDownHeight);
		}, 0);
		getPageData();
	}
}
function lazyImg(selector){
	var w_h = $(window).height();
	$(selector).find('img').each(function(){
		if($(this).attr("data-ifshow") === '0' && ($(this).offset().top - w_h)<0 && $(this).attr('data-src') !=='' ){
			$(this).attr({'src':$(this).attr('data-src'),"data-ifshow":'1'})
		}
	});
}