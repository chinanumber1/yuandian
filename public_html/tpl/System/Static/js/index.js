if(!Array.prototype.map)
Array.prototype.map = function(fn,scope) {
	var result = [],ri = 0;
	for (var i = 0,n = this.length; i < n; i++){
		if(i in this){
			result[ri++]  = fn.call(scope ,this[i],i,this);
		}
	}
	return result;
};
var getWindowWH = function(){
	  return ["Height","Width"].map(function(name){
	  return window["inner"+name] ||
		document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
	});
}
window.onload = function (){
	if(!+"\v1" && !document.querySelector) { //IE6 IE7
		document.body.onresize = resize;
	} else { 
	  window.onresize = resize; 
	}
	function resize() {
		wSize();
		return false;
	}
}
function wSize(){
	var str=getWindowWH();
	var strs= new Array();
	strs=str.toString().split(","); //字符串分割
	var h = strs[0];
	/* $('#leftMenuBox,#Main,#HelpBox').height(h); */
	$('#Main_content').height(h);
	$('#main').css('min-height',h);
	
	if($('#leftMenuBox').hasClass('dedent')){
		// $('#MainBox').width($(window).width()-300);
	}else{
		// $('#MainBox').width($(window).width()-420);
	}
}
wSize();

function toggleMenu(doit){
	if(doit==1){
		$('#Main_drop a.on').hide();
		$('#Main_drop a.off').show();
		$('#MainBox .main_box').css('margin-left','24px');
		$('#leftMenu').hide();
	}else{
		$('#Main_drop a.off').hide();
		$('#Main_drop a.on').show();
		$('#leftMenu').show();
		$('#MainBox .main_box').css('margin-left','224px');
	}
}	
$(function(){
	$('#leftMenu dl').hide();
	if(selected_module != '' && selected_action != ''){
		show_other_frame(selected_module,selected_action,'',selected_url);
	}else{
		$('#nav_1').show();
		$('#nav_1 dd:first').addClass('on');
	}
	$('#leftNavBox li.nav-top').hover(function(){
		if($('#leftMenuBox').hasClass('dedent')){
			var scrollTopTmp = $('#Main_content')[0].scrollHeight;
			// alert(scrollTopTmp);
			$(this).find('ul').removeClass('hide');
			$(this).addClass('hover').siblings().removeClass('hover');
			$('#leftNavBox li.nav-top.active ul').addClass('hide');
			if($(this).hasClass('active')){
				$(this).find('ul').removeClass('hide');
			}
			
			if($(this).find('ul li').size() == 1){
				$(this).find('ul').addClass('oneSub');
			}else{
				$(this).find('ul').removeClass('oneSub');
				if($(this).find('ul li').size() > 20){
					$(this).find('ul').addClass('threeSub');
				}else if($(this).find('ul li').size() > 6){
					$(this).find('ul').addClass('twoSub');
				} 
			}
			var scrollTopTmpNew = $('#Main_content')[0].scrollHeight;
			if(scrollTopTmpNew > scrollTopTmp){
				$(this).find('ul').addClass('bottomShow');
			}
		}
	},function(){
		if($('#leftMenuBox').hasClass('dedent')){
			$(this).removeClass('hover');
			$(this).find('ul').addClass('hide');
		}
	});
	$('#leftNavBox li a.auto').click(function(){
		if($(this).closest('li').hasClass('active') && $(this).closest('li').find('.nav-sub').size() > 0){	
			if($('#leftMenuBox').hasClass('dedent')){
				$(this).closest('li').find('ul').removeClass('hide');
			}else{
				$(this).closest('li').removeClass('active');
			}
			setMainHeight({leftBarHeight:$('#leftHideBtn').height()+$('#leftProfile').height()});
			return false;
		}
		$(this).closest('li').addClass('active').siblings().removeClass('active');
		if($(this).closest('li').find('.nav-sub').size() > 0){
			setMainHeight({leftBarHeight:$('#leftHideBtn').height()+$('#leftProfile').height()});
			if($('#leftMenuBox').hasClass('dedent')){
				$(this).closest('li').find('ul').removeClass('hide');
			}
			return false;
		}else{
			$(window).scrollTop(0);
		}
	});
	$('#leftNavBox li .nav-sub a').click(function(){
		$('#leftNavBox li.nav-top').removeClass('active');
		$('#leftNavBox li .nav-sub li').removeClass('active');
		
		$(this).closest('li.nav-top').addClass('active');
		
		$(this).closest('li').addClass('active');
		
		if($('#leftMenuBox').hasClass('dedent')){
			$(this).closest('ul').addClass('hide');
		}
		
		/* $(window).scrollTop(0); */
	});
	
	$('#leftHideBtn .fa-dedent').click(function(){
		$('#leftMenuBox').addClass('dedent');
		// $('#MainBox').width($(window).width()-300);
		setMainHeight({});
	});
	$('#leftHideBtn .fa-indent').click(function(){
		$('#leftMenuBox').removeClass('dedent');
		// $('#MainBox').width($(window).width()-420);
		setMainHeight({});
	});
});

//刷新父级页面
function top_refresh(){
    window.top.location.reload();
}
//刷新框架页面
function main_refresh(){
	console.log($('#LAY_app_tabsheader .layui-this').data('tab'));
    window[$('#LAY_app_tabsheader .layui-this').data('tab')].location.reload();
}

//关闭所有框架
function closeiframe(){
	var list = window.art.dialog.list;
	for (var i in list){
		if(i.substring(0,3)!=="msg"){
			list[i].close();
		};
	};
}

//关闭msg框架
function closemsgiframe(){
	var list = window.art.dialog.list;
	for (var i in list){
		if(i.substring(0,3)=="msg"){
			list[i].close();
		};
	};
}

//关闭指定ID的框架
function closeiframebyid(id){
	var list = window.art.dialog.list;
	for (var i in list){
		if(i == id){
			list[i].close();
		};
	};
}

//将指定框架移到左边
function change_frame_position_left(name){
	var window_width = $(window).width();
	if(window_width<1200){
		var frame_left = '0%';
	}else if(window_width<1350){
		var frame_left = '10%';
	}else if(window_width<1500){
		var frame_left = '15%';
	}else{
		var frame_left = '20%';
	}
	window.top.art.dialog.list[name].position(frame_left,'38.2%');
}

//得到右边框架的左边
function get_frame_position_left(name,width){	
	var window_width = $(window).width();
	if(window_width<1200){
		var frame_left = width;
	}else if(window_width<1350){
		var frame_left = (window_width*0.1)+width;
	}else if(window_width<1500){
		var frame_left = (window_width*0.15)+width;
	}else{
		var frame_left = (window_width*0.20)+width;
	}
	return frame_left;
}



//art弹框组件
function artiframe(url, title, width, height, lock, resize, background, button, id, fixeds, closefun, left, top, padding){
	if(url.indexOf("?") != -1){
		url = url+'&frame=1';
	}else{
		url = url+'?frame=1';
	}
	if (!width) width = 'auto';
    if (!height) height = 'auto';
    if (!lock) lock = false;
    if (!resize) resize = false;
    if (!background) background = 'black';
    if (!closefun) closefun = null;
    if (!button) button = null;
    if (!left) left = '50%';
    if (!top) top = '38.2%';
    if (!id) id = null;
    if (!fixeds) fixeds = false;
    if (!padding) padding = 0;
    art.dialog.open(url, {
        init: function(){
            var iframe = this.iframe.contentWindow;
            window.top.art.dialog.data('iframe' + id, iframe);
        },
        id: id,
        title: title,
        padding: padding,
        width: width,
        height: height,
        lock: lock,
        resize: resize,
        background: background,
        button: button,
        fixed: fixeds,
        close: closefun,
        left: left,
        top: top,
		opacity:'0.4'
    });
}

//art信息提示
function msg(status,msg,lock,time){
	var list = window.art.dialog.list;
	for (var i in list){
		if(i == 'form_submit_tips'){
			list[i].close();
		}
	};
    if(lock){
        lock = true;
    }else{
        lock = false;
    }
    if (!time){
        time = 2;
    }
    if(status == 2){
        ico = 'face-smile';
    }else if(status){
        ico = 'succeed';
    }else{
        ico = 'error';
    }
	if(status == 2){
        id = 'form_submit_tips';
    }else{
		id = 'msg' + Math.random();
	}
    art.dialog({
        icon: ico,
        time: time,
        /*background: '#FFF',*/
        title: '提示信息',
        id: id,
		opacity:'0.4',
        lock: lock,
        fixed: true,
        resize: false,
        content: msg
    });
}

function updateMsg(title,content,url){
    art.dialog({
        title: title,
        id:'system_update',
		opacity:'0.4',
        lock: true,
        fixed: true,
        resize: false,
		height:'400px',
		padding:'10px',
        content: content,
		ok:function(){
			if(url == './admin.php?c=Updatesys'){
				addIframe('系统升级','leftmenu_Updatesys_index','/admin.php?g=System&c=Updatesys&a=index&no_back=1')
			}
		}
    });
}
//打开颜色框
function showTopColorPanel(frameName,ttop,thei,tleft,txt){
	txtobj = $('#'+txt,window.frames[frameName].document);

	$("#colorpanel").css({
		top:$("iframe[name^='"+frameName+"']").offset().top + ttop + thei + 5,
		left:$("iframe[name^='"+frameName+"']").offset().left + tleft,
		'z-index':'19999',
	});
	$("#colorpanel").show();
	$("#CT tr td").unbind("click").mouseover(function(){
		var aaa=$(this).css("background-color");
		$("#DisColor").css("background-color",aaa);
		$("#HexColor").val(aaa);
	}).click(function(){
		var aaa=$(this).css("background-color");
		txtobj.val(aaa);
		txtobj.css("background-color",aaa);
		$("#colorpanel").hide();
	});
	$()
	$("#_clean").click(function(){
		$("#colorpanel").hide();
		txtobj.val('');
	});
	$("#_cclose").click(function(){$("#colorpanel").hide();}).css({"font-size":"12px","padding-left":"10px"});
	$('#HexColor').keypress(function(e){if(e.keyCode==13){$("#colorpanel").hide();txtobj.val($(this).val());}}).css({'width':'90px'});
}

//展现指定的main框架
function show_other_frame(module,action,param,url){
	var now_dom = $('#leftmenu_'+module+'_'+action);
	var index = $('#leftMenu dl').index(now_dom.closest('dl'));
	$('.topmenu li span').removeClass('current').eq(index).addClass('current');
	// alert($('#leftMenu dl').eq(index).html());
	$('#leftMenu dl').eq(index).show().siblings('dl').hide();
	now_dom.closest('dd').addClass('on');
	if(url){
		window.main.location.href = url;
	}else{
		window.main.location.href = now_dom.attr('url')+'&'+param;
	}
}


function showHelpText(arr){
	// console.log(arr);
	if(arr.length == 0){
		$('#helpContentText').empty();
	}else{
		var str = '';
		for(var i in arr){
			str += '<p>';
				str += '<span class="title">'+arr[i].title+'</span>';
				str += '<br/>';
				str += '<span class="content">'+arr[i].content+'</span>';
			str += '</p>';
		}
		str += '<hr/>';
		$('#helpContentText').html(str);
	}
}

function showHelpType(type,group,module,action){
	if(type == true){
		$('#indexProfile').show();
		$('#helpBox').hide();
	}else{
		$('#indexProfile').hide();
		$('#helpBox').show();
		
		$('#loadHelpContent').show();
		$('#helpContentBox,#emptyHelpContent').hide();
		$('#helpContentBox').empty();
		$.getJSON(window.location.pathname+'?c=Index&a=ajax_help&group='+group+'&module='+module+'&action='+action,function(result){
			$('#loadHelpContent').hide();
			if(result && result.length > 0){
				for(var i in result){
					$('#helpContentBox').append('<p><a href="javascript:openwin(\''+window.location.pathname+'?c=Index&a=help&answer_id='+result[i].answer_id+'\',768,1100)">'+result[i].title+'</a></p>');
				}
				$('#helpContentBox').show();
			}else{
				$('#emptyHelpContent').show();
			}
		});
	}
}

function openwin(url,iHeight,iWidth){
	var iTop = (window.screen.availHeight-30-iHeight)/2,iLeft = (window.screen.availWidth-10-iWidth)/2;
	window.open(url, "newwindow", "height="+iHeight+", width="+iWidth+", toolbar=no, menubar=no,top="+iTop+",left="+iLeft+",scrollbars=yes, resizable=no, location=no, status=no");
}

		

var iframeRealHeight = 0;

var iframeHeight = 0;
var leftBarHeight = 0;
var rightBarHeight = 0;
var windowHeight = 0;
$(function(){
	setMainHeight({});
	
	$('#fullscreenBtn').click(function(){
		screenfull.enabled&&screenfull.toggle();
	});
	if (screenfull.enabled) {
		$(document).on(screenfull.raw.fullscreenchange, function () {
			if(screenfull.isFullscreen){
				$('#fullscreenBtn').addClass('active');
			}else{
				$('#fullscreenBtn').removeClass('active');
			}
		});
	}
});
function setMainHeight(heightArr){
	$('#leftMenuBox').css('min-height',0);
	leftBarHeight = $('#leftMenuBox').height()+40;
	
	$('#HelpBox').css('min-height',0);
	rightBarHeight = $('#HelpBox').height();
	
	iframeHeight = iframeRealHeight;
	
	windowHeight = $(window).height();

	
	var domArr = [leftBarHeight,rightBarHeight,iframeHeight,windowHeight];

	var maxInNumbers = Math.max.apply(Math,domArr);
	$('#leftMenuBox,#MainBox iframe,#HelpBox').css('min-height',maxInNumbers);
	
	if(heightArr.iframeHeight){
		$('#Main_content').scrollTop(0);
	}
}

/** 页面导航栏 **/
var navBoxArr = [];
var homeTab = '';
var nowTab = '';
$("#leftNavBox a[href!='#']").click(function(){
	var href = $(this).attr('href');
	var tab = $(this).attr('id');
	var name = $.trim($(this).text());
	
	for(var i in navBoxArr){
		if(navBoxArr[i].tab == tab){
			$("#LAY_app_tabsheader li[data-tab='"+tab+"']").trigger('click');
			window.frames[tab].location.href = navBoxArr[i].href;
			return false;
		}
	}
	navBoxArr.push({
		tab:tab,
		href:href,
		name:name
	});
	if(window.localStorage){
		localStorage.setItem('system_navBoxArr',JSON.stringify(navBoxArr));
	}
	addIframe(name,tab,href,false);
	
	console.log('navBoxArr',navBoxArr);
	console.log(href);
	console.log(tab);
	
	return false;
});
$("#LAY_app_tabsheader li").live('click',function(){
	if($(this).hasClass('layui-this')){
		return false;
	}
	var tab = $(this).data('tab');
	$('#LAY_app_tabsheader li').removeClass('layui-this');
	$(this).addClass('layui-this');
	
	$(".iframe-body .iframe-item[data-tab='"+tab+"']").removeClass('layer-hide').addClass('layer-show').siblings('.iframe-item').addClass('layer-hide').removeClass('layer-show');
	
	//左侧菜单选中
	$('#leftNavBox li').removeClass('active');
	$('#'+tab).closest('li').addClass('active').closest('.nav-top').addClass('active');
	nowTab = tab;
});
$("#LAY_app_tabsheader li .layui-tab-close").live('click',function(){
	var li = $(this).closest('li');
	var tab = li.data('tab');
	var index = li.index();
	console.log(index);
	if(li.hasClass('layui-this')){
		var next = $('#LAY_app_tabsheader li').eq(index-1);
		next.addClass('layui-this');
		var next_tab = next.data('tab');
		$(".iframe-body .iframe-item[data-tab='"+next_tab+"']").removeClass('layer-hide').addClass('layer-show');
		
		//左侧菜单选中
		$('#leftNavBox li').removeClass('active');
		$('#'+next_tab).closest('li').addClass('active').closest('.nav-top').addClass('active');
		nowTab = next_tab;
	}
	li.remove();
	$(".iframe-body .iframe-item[data-tab='"+tab+"']").remove();
	
	
	$('.layui-icon-prev').trigger('click');
	
	var newNaxBoxArr = [];
	for(var i in navBoxArr){
		if(navBoxArr[i].tab != tab){
			newNaxBoxArr.push(navBoxArr[i]);
		}
	}
	navBoxArr = newNaxBoxArr;
	if(window.localStorage){
		localStorage.setItem('system_navBoxArr',JSON.stringify(navBoxArr));
	}
	console.log('navBoxArr',navBoxArr);
	return false;
});

$("#LAY_app_tabsheader li .layui-tab-refresh").live('click',function(){
	window.frames[nowTab].location.reload();
});

$('.layui-icon-next').click(function(){
	var width = 0;
	$.each($('#LAY_app_tabsheader li'),function(i,item){
		width = width + $(item).outerWidth();
	});
	console.log(width);
	var left = $('#LAY_app_tabsheader').css('left');
	left = parseInt(left.replace('px',''));
	
	var new_left = $('#LAY_app_tabsheader').width()-width;
	console.log(left);
	console.log(new_left);
	if(width <= $('#LAY_app_tabsheader').width()){
		var left = 0;
	}else if(left <= new_left){
		$('#LAY_app_tabsheader').css('left',new_left+'px');
	}else if(left >= new_left){
		$('#LAY_app_tabsheader').css('left',(left-100)+'px');
	}else{
		$('#LAY_app_tabsheader').css('left',new_left+'px');
	}
});
$('.layui-icon-prev').click(function(){
	var width = 0;
	$.each($('#LAY_app_tabsheader li'),function(i,item){
		width = width + $(item).outerWidth();
	});
	var new_left = $('#LAY_app_tabsheader').width()-width;
	
	var left = $('#LAY_app_tabsheader').css('left');
	left = parseInt(left.replace('px',''));
	if(left >= 0 || new_left >= 0){
		$('#LAY_app_tabsheader').css('left','0px');
	}else{
		$('#LAY_app_tabsheader').css('left',(left+100)+'px');
	}
});
$('#closeThisTabs').click(function(){
	if($("#LAY_app_tabsheader li.layui-this").data('is_home') == 'yes'){
		return false;
	}
	$("#LAY_app_tabsheader li.layui-this .layui-tab-close").trigger('click');
});


$('#closeOtherTabs').click(function(){
	$.each($("#LAY_app_tabsheader li"),function(i,item){
		if($(item).data('is_home') != 'yes' && !$(item).hasClass('layui-this')){
			$(this).find('.layui-tab-close').trigger('click');
		}
	});
});

$('#closeAllTabs').click(function(){
	$.each($("#LAY_app_tabsheader li"),function(i,item){
		if($(item).data('is_home') != 'yes'){
			$(this).find('.layui-tab-close').trigger('click');
		}
	});
});


function addIframe(name,tab,href){
	$('#LAY_app_tabsheader li').removeClass('layui-this');
	$('#LAY_app_tabsheader').append('<li class="layui-this" data-tab="'+tab+'" data-is_home="'+(tab == homeTab ? 'yes' : 'no')+'">'+(tab == 'leftmenu_Index_main' ? '<i class="layui-icon fa fa-home icon"></i>' : name)+'<i class="layui-icon fa fa-refresh icon layui-tab-refresh"></i><i class="layui-icon fa fa-close icon layui-tab-close"></i></li>'); 
	
	$('.iframe-body .iframe-item').addClass('layer-hide').removeClass('layer-show');
	$('.iframe-body').append('<div class="iframe-item layer-show" data-tab="'+tab+'" data-href="'+href+'"><iframe name="'+tab+'" src="'+href+'" frameborder="false" scrolling="auto"  width="100%" height="auto" allowtransparency="true"></iframe></div>');
	
	$('.layui-icon-next').trigger('click');
	nowTab = tab;
}

function closeNowTab(){
	$('#closeThisTabs').trigger('click');
}


//判断是不是要初始化
if($('#leftmenu_Index_main').size() > 0){
	homeTab = 'leftmenu_Index_main';
}else if($("#leftNavBox a[target='main']").size() > 0){
	homeTab = $("#leftNavBox a[target='main']").eq(0).attr('id');
}
navBoxArr.push({
	tab:homeTab,
	href:$('#'+homeTab).attr('href'),
	name:$.trim($('#'+homeTab).text())
});
addIframe(navBoxArr[0].name,navBoxArr[0].tab,navBoxArr[0].href);
$('#leftNavBox li').removeClass('active');
$('#'+homeTab).closest('li').addClass('active').closest('.nav-top').addClass('active');

var newNavBoxArrTmp = [];
if(window.localStorage){
	var naxBoxArrStr = localStorage.getItem('system_navBoxArr');
	if(naxBoxArrStr){
		var navBoxArrTmp = JSON.parse(naxBoxArrStr);
		newNavBoxArrTmp = [];
		for(var i in navBoxArrTmp){
			console.log(navBoxArrTmp[i].tab);
			if($('#'+navBoxArrTmp[i].tab).size() > 0 && navBoxArrTmp[i].tab != homeTab){
				newNavBoxArrTmp.push(navBoxArrTmp[i]);
			}
		}
	}
}
if(newNavBoxArrTmp.length > 0){
	localStorage.removeItem('system_navBoxArr');
	art.dialog({   
		title: '多标签页提醒',
		content: '您上次有 '+ newNavBoxArrTmp.length +' 个浏览页面未关闭，是否要打开？',  
		opacity:'0.4',
        lock: true,
        fixed: true,
        resize: false,		
		ok: function () {   
			for(var i in navBoxArrTmp){
				console.log(navBoxArrTmp[i].tab);
				$('#'+navBoxArrTmp[i].tab).trigger('click');
			} 
		},   
		cancelVal: '关闭',   
		cancel: true //为true等价于function(){}   
	});
}