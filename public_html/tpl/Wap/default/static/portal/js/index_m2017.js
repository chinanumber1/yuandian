window['historyKeyArr'] = [];
var searchHtml = '<div class="searchbar2 searchbar3">'+
	'<div class="tab_all" id="wrapper2">'+
		'<ul id="scroller2" class="clearfix">'+
			'<li class="current"><a href="javascript:void(0);" class="item" data-val="1">生活信息</a></li>'+
			'<li><a href="javascript:void(0);" class="item" data-val="2">店铺商家</a></li>'+
			'<li><a href="javascript:void(0);" class="item" data-val="3">新闻资讯</a></li>'+
			'<li><a href="javascript:void(0);" class="item" data-val="4">省啦项目</a></li>'+
			'<li><a href="javascript:void(0);" class="item" data-val="5">招聘信息</a></li>'+
			'<li><a href="javascript:void(0);" class="item" data-val="6">快店商品</a></li>'+
		'</ul>'+
		'<div class="more" id="iscrollto"><span></span></div>'+
	'</div>'+
	'<form id="myform" action="search.aspx" method="get">'+
		'<input type="hidden" value="1" name="c" id="keyword_c" />'+
		'<input type="text" name="keyword" id="keyword" class="s_ipt" value="" placeholder="输入搜索关键词" />'+
		'<input type="submit" class="s_btn po_ab" value="搜索">'+
	'</form></div>'+
	'<div class="hotKey clearfix"><span class="tit">热搜</span>'+site_remen+'</div>'+
	'<div class="hotKeyline"></div>'+
	'<div class="historyKey">'+
	'<div class="hd"><a href="javascript:void(0);" onClick="return removeHistoryKey();" class="del">删除</a><div class="tit">历史记录</div></div>'+
	'<div class="bd" id="historyKey">暂无搜索历史</div>'+
	'</div>';
function newPageSearch(){
	var windowIframe = $('#windowIframe');
	windowIframe.find('.back').show()
	if(windowIframe.attr('data-loaded') === '0'){
		windowIframe.attr('data-loaded','1');
		$('#scroller2').css('width',(90*$('#scroller2').find('li').length)+40+'px'); 
		window['myScroll2'] = new IScroll('#wrapper2', {
			scrollX: true,
			scrollY: false,
			click:true,
			keyBindings: true
		});
		$('#iscrollto').click(function(){
			window['myScroll2'].scrollBy(-100,0,500)
		});
		$('#myform').submit(function(){
			if($('#keyword').val() === ''){
				MSGwindowShow('index','0','请输入搜索关键字','','');
				return false;
			}else{
				var obj = {'c':$('#keyword_c').val(),'keyword':encodeURIComponent($('#keyword').val())};
				window['historyKeyArr'].push(obj);
				$.cookie('historyKey',JSON.stringify(window['historyKeyArr']));
			}
		});
		windowIframe.on('click','.item',function(e){
			e.preventDefault();
			$('#keyword_c').val($(this).attr('data-val'));
			$(this).parent().parent().find('li').removeClass('current');
			$(this).parent().addClass('current');
		});
		//初始化搜索历史cookie
		if($.cookie('historyKey') !== undefined){
			window['historyKeyArr'] = JSON.parse($.cookie('historyKey'));
			if(window['historyKeyArr'].length === 0){return false;}
			var txt='';
			for(var i=0;i<window['historyKeyArr'].length;i++){
				txt+='<a href="search.aspx?c='+window['historyKeyArr'][i]['c']+'&keyword='+window['historyKeyArr'][i]['keyword']+'">'+decodeURIComponent(window['historyKeyArr'][i]['keyword'])+'</a>';
			}
			$('#historyKey').html(txt);
		}
	}
	setTimeout(function(){window['myScroll2'].refresh();},10);
}
function removeHistoryKey(){
	$.removeCookie('historyKey');
	$('#historyKey').html('暂无搜索历史');
	
}
var myScroll;
function loaded() {
	var nav_APP_data = $('#nav_APP_data');
	nav_APP_data.find('li').eq(0).remove();
	nav_APP_data.find('li').slice(5,10).clone().insertAfter(nav_APP_data.find('li').eq(9));
	var w_w = $(window).width();
	var len = Math.ceil(nav_APP_data.find('li').length/10);
	var txt = '';
	var scroller = $('#scroller');
	for(var i=0; i<len;i++){
		var el = document.createElement('div');
		el.className = 'slide';
		var ul = document.createElement('ul');
		ul.className = 'clearfix';
		txt = nav_APP_data.find('li').slice(0,10).detach();
		$(ul).append(txt);
		el.appendChild(ul);
		scroller[0].appendChild(el);
	}
	scroller.css({'width':w_w*len+'px'}); 
	scroller.find('.slide').css('width',w_w+'px'); 
	$('#indicator2').css({'width':(11*(len-1)+6)+'px'});
	setTimeout(function(){
		myScroll = new IScroll('#nav_Node', {
			scrollX: true,
			scrollY: false,
			momentum: false,
			click:true,
			snap: true,
			snapSpeed: 400,
			keyBindings: true,
			eventPassthrough:true,
			indicators: {
				el: document.getElementById('indicator2'),
				resize: false
			}
		});
	},200);
}
window.requestAnimFrame = (function(){
	return function(callback,timer){
		window.setTimeout( callback, timer);
	}
})();
$.fn.loopScrollTxt = function(){
	var t = $(this),list = t.find('li'),inner = t.find('.inner2');
	var len = list.length,h = list.eq(0).height(),index = 0;
	inner.css({'height':len*h+'px','position':'absolute'});
	function loop() {
		requestAnimFrame(loop,4000);
		//轮询处
		index++;
		if(index === len){
			index=0;
			inner.animate({'top':0});
		}else{
			inner.animate({'top':'-='+h});
		}
	}
	window.setTimeout( loop, 4000);
}
$.fn.img1bi1 = function(){
	var list = $(this).find('.img');
	var $divWidth = list.eq(0).width();
	list.css({'height':$divWidth});
}