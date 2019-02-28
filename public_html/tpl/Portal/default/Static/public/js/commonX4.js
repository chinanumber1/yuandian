function changeTwoDecimal(x){//保留两位小数，四舍五入
	var f_x = parseFloat(x);
	if (isNaN(f_x)){
		//console.info('function:changeTwoDecimal->parameter error');
		return false;
	}
	if(f_x.toString().lastIndexOf('.')!==-1){
		f_x=f_x.toFixed(2);
	}
	return f_x;
}
function changeTwoDecimal2(x){//保留两位小数，不四舍五入
	var f_x = parseFloat(x);
	if (isNaN(f_x)){
		//console.info('function:changeTwoDecimal->parameter error');
		return false;
	}
	f_x=f_x.toFixed(3);
	f_x = f_x.substring(0,f_x.lastIndexOf('.')+3);
	return f_x;
}
function MSGwindowShow(action,showid,str,url,formcode){
	var sys_tips = '<div class="sys_tips" id="sys_tips" style="display:none;"><div class="hd" id="sys_tips_title"></div><div class="bd"><p id="sys_tips_info"></p><div class="btn"><a href="#" class="btn2" id="sys_tips_submit">确定</a></div></div></div>';
	if(!$('#sys_tips')[0]){
		$('body').append(sys_tips);
	}
	var pay_tips = $('#pay_tips'),sys_tips = $('#sys_tips'),sys_tips_title = $('#sys_tips_title'),sys_tips_info = $('#sys_tips_info'),sys_tips_submit = $('#sys_tips_submit');
	if(action === "pay"){
		$('#have_login').hide();
		if(showid=="2"){//正常提交
			if(document.getElementById('formcode')){
				document.getElementById('formcode').value=formcode;//赋值code
				document.forms['submitpay'].submit();//提交支付
				//这里添加支付中信息提示窗口
				pay_tips.show();
				var w_h = $(window).height(),d_h = pay_tips.height(),s_h = $(document).scrollTop(),top_val = (w_h-d_h)/2+s_h;
				pay_tips.css({'top':top_val+'px'});
			}
		}else if(showid=="1"){//成功提示加跳转
			if(!!win){win.close();}
			showConsole('恭喜您！',!0);
		}else if(showid=="0"){//提示不跳转
			if(!!win){win.close();}
			showConsole('温馨提示',!1);
		}else if(showid=="4"){//错误提示不跳转
			if(!!win){win.close();}
			showConsole('出错了',!1);
		}else{//提示加跳转
			if(!!win){win.close();}
			showConsole('温馨提示',!0);
		}
		if(document.getElementById('formcode')){
			document.getElementById('formcode').value="payok";//设置默认值防止二次提交
		}
	}else{
		if(showid=="0"){ //只提示不跳转
			showConsole('提示',false);
		}else if(showid=="1"){ //提示加跳转
			showConsole('提示',true);
		}else if(showid=="2"){ //直接跳转
			windowlocationhref(url);
		}
		else if(showid=="3"){ //错误信息加跳转
			showConsole('出错了',true);
		}else if(showid=="4"){ //错误信息加只提示不跳转
			showConsole('出错了',false);
		}else{
			return false;
		}
	}
	
	function showConsole(tit,isredirect){
		sys_tips_info.html(str);
		sys_tips_title.html(tit);
		sys_tips_submit.bind('click',function(e){
			e.preventDefault();
			sys_tips.hide();
			isredirect&&windowlocationhref(url);
		});
		sys_tips.show();
		var w_h = $(window).height(),d_h = sys_tips.height(),s_h = $(document).scrollTop(),top_val = (w_h-d_h)/2+s_h;
		sys_tips.css({'top':top_val+'px'});
	}
}
function windowlocationhref(url){
	if(url.length > 5){window.location.href=url;}
}
function weixinX4(){
	var weixinImg = $('#weixinImg');
	if(weixinImg.attr('src')===''){return;}
	var pnode = $('#weixinX4'),node = pnode.find('.node');
	pnode.hover(function(){node.toggle()});
}
function showMap(mapdomid){
	var wrap_node = document.createElement('div');
	wrap_node.className = 'map_iframe';
	wrap_node.id='map_iframe';
	wrap_node.style.display='none';
	
	var myiframe = '<a href="javascript:close_map();" class="close_map">关闭</a><iframe src="'+nowdomain+'ezmarker/map.aspx?action=shop&id='+mapdomid+'" scrolling="no" frameBorder="0" width="700" height="500"></iframe>';
	if(document.getElementById('map_iframe')){
		document.getElementById('map_iframe').style.display='block';
		return false;
	}
	wrap_node.innerHTML=myiframe;
	document.getElementsByTagName('body')[0].appendChild(wrap_node);
	document.getElementById('map_iframe').style.display='block';
	return false;
}
function close_map(){
	document.getElementById('map_iframe').style.display='none';
}
function loginout(siteUrl){
	var url = siteUrl+"request.ashx?action=loginout&json=1&jsoncallback=?&date=" + new Date();
	$.getJSON(url,function(data){
		
		if(data[0].islogin === '0'){
			if(data[0].bbsopen === "open"){
				var   f=document.createElement("IFRAME")   
				f.height=0;   
				f.width=0;   
				f.src=data[0].bbsloginurl;
				if (f.attachEvent){
					f.attachEvent("onload", function(){
						window.location.reload();
					});
				} else {
					f.onload = function(){
						window.location.reload();
					};
				}
				document.body.appendChild(f);
			}else{
				window.location.reload();
			}
		}else{
			alert("对不起，操作失败！");
		}
	}).error(function(){alert("对不起，操作失败！");});
}
function is_login(siteUrl,tplPath){
	var url = siteUrl+"request.ashx?action=islogin&json=1&jsoncallback=?&date=" + new Date(),
		node = $("#login_info"),txt='',txt_cm='';
	var hash = window.location.href.indexOf("?from=")<0?'?from='+encodeURIComponent(window.location.href):'';
	
	var sj_btn = "";
	
	if(typeof siteUrl !== 'undefined'){window['siteUrl'] = siteUrl;}
	if(typeof tplPath !== 'undefined'){window['tplPath'] = tplPath;}
	
	$.getJSON(url,function(data){
		if(data[0].islogin==="1"){
			if(data[0].jibie === '1'||data[0].jibie === '2'||parseInt(data[0].manageshopid)>0){
				sj_btn = " <a href=\""+siteUrl+"member/userindex_s.aspx\" class=\"shangjia\" target=\"_blank\">商家平台</a>";
			}
			txt=txt_cm="<span class=\"login_success\"><span class=\"username\">"+data[0].name+"</span>，您好！<a href=\""+siteUrl+"member/index.html\">管理</a>"+sj_btn+" <a href=\"javascript:loginout('"+siteUrl+"');\">退出</a></span>";
			txt+="<input value=\"1\" id=\"isLogin\" type=\"hidden\" /><input value=\""+data[0].jibie+"\" id=\"user_jibie\" type=\"hidden\" />";
			loadWEBmessage();//消息系统
		}else{
			txt="<a href=\""+siteUrl+"member/login.html"+hash+"\" class=\"sys_btn\">登录</a><a href=\""+siteUrl+"member/register.html"+hash+"\" class=\"sys_btn\">注册</a><input value=\"0\" id=\"isLogin\" type=\"hidden\" />";
			txt_cm="<li class=\"bor\">您好，先登录再发言！<a a href=\""+siteUrl+"member/login.html"+hash+"\">登录</a></li><li>还没有账号？<a href=\""+siteUrl+"member/register.html\">免费注册</a></li><li class=\"yellow\" id=\"youke\" style=\"display:none;\">网友：</li><li class=\"youke_li\" style=\"float:right;\"><input value=\"1\" id=\"commentyouke\" name=\"commentyouke\" type=\"checkbox\" style=\"vertical-align:middle;\" /> 游客直接发表 </li>";
		}
		node.prepend(txt);
		$(document).ready(function(){
			var cm_node = $("#login_info_cm");
			cm_node[0]&&cm_node.html(txt_cm);
			var node2 = $("#login_info2");
			node2[0]&&node2.prepend(txt);
		});
	}).error(function(err){
		//alert(err);
	});
}
var message_pid="-1";
var message_isstop = false;//页面是否丢失服务权
var message_isforced = false;//是否被强制拉回服务权页面,被丢失时又强制拉回权时,完全停止弱探测
function loadWEBmessage(){
	var url = window['siteUrl']+'api/request.ashx?pid=' +message_pid + '&jsoncallback=?';
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){WebMessageShow(data);}
		if(data[0].islogin === '1' || data[0].islogin === '0'){
			/*if( message_pid != '-1' &&  message_pid != data[0].pid){
		  		$('#message_show').html('活动页面丢失,被重新找回连接权');
		    }*/
			message_pid=data[0].pid;
			window.setTimeout(function(){loadWEBmessage()},200);//高速探测:间隔时间短100-200毫秒,弱探测:间隔1-2分钟以上
		}else{
			/*$('#message_show').html('信息获取被另一页面取代，本页面抓取信息进入弱探测');*/
			message_isstop = true;
			if(message_isforced){
				message_isforced=false;
			}else{
				if( message_pid === '-1' )message_pid='0';
			    window.setTimeout(function(){loadWEBmessage()},1*60000);////被取代后每2分钟尝试一次连接,检测活动页面是否丢失
			}
		}
	}).error(function(err){//失败2分钟后尝试一次
		window.setTimeout(function(){loadWEBmessage()},2*60000);
	});
	/* 
	data[0].islogin:0无信息,1:有信息MSG,2:停止高速探测,改为弱探测区别是间隔时间.
	*/
	/*$(window).blur(function(){
		RunOnunload();
	});
	$(window).focus(function(){
		newloadWEBmessage();
	});*/
}
function newloadWEBmessage(){
	//当页面发生任何刷新或鼠标动作或任意操作时,表示前活动页面已经不是焦点页面,当前页面重新初始参数强行抓回信息获取权
	//问题:如何防止本页面并行执行loadWEBmessage(),自动执行一次,强制执行一次.
	if(message_isstop){
	  	message_isstop = false;
		message_isforced =true;
    	message_pid="-1";
	    loadWEBmessage();
    }
}
function RunOnunload(){//当前页面关闭时执行,将程序里当前链接关闭,无需返回任何数据
	var url = window['siteUrl']+'api/request.ashx?action=close&pid=' +message_pid + '&jsoncallback=?';
	$.getJSON(url,function(data){});
}
function WebMessageShow(data){
	var idata = data[0]['MSG'];
	var newOrderId='webMessage';
	function countItem(){
		var len = $('#'+newOrderId).find('.item').length;
		$('#WebMessageNum').html(len);
		if(len === 0){
			$('#'+newOrderId).hide();	
		}
	}
	if(typeof idata['mp3'] !== 'undefined' && idata['mp3'] !==''){
		WebMessageMusic(idata['mp3']);
	}
	if(!$('#'+newOrderId)[0]){
		var divs = document.createElement('div');
		divs.id = newOrderId;
		$('body').append(divs);
		divs.innerHTML = '<div class="hd">您有<span id="WebMessageNum">0</span>条新信息</div><div class="bd" id="WebMessageInner"></div><a href="#" class="close">收起</a><a href="#" class="remove">移除</a>';
		$('#'+newOrderId).find('.close').click(function(e){
			e.preventDefault();
			$('#WebMessageInner').slideToggle();
			$(this).toggleClass('open');
		}).end().find('.remove').click(function(e){
			e.preventDefault();
			$('#'+newOrderId).hide();
		}).end().on( "click", ".view", function(e){
			if(typeof idata['notViewCloseALL'] !=='undefined' && idata['notViewCloseALL'] === '1'){//点击查看移除全部同类型消息
				$(this).parent().parent().remove();
			}else{
				$('#'+newOrderId).find('.tplid_'+$(this).attr('data-tplid')).remove();
			}
			countItem();
		}).on( "click", ".del", function(e){
			e.preventDefault();
			$(this).parent().parent().remove();
			countItem();
		});
	}else{
		$('#'+newOrderId).show();
		$('#WebMessageInner').slideDown();
	}
	var txt = $('<div class="item tplid_'+idata.tplid+'">'+idata.title+'<p class="date">'+idata.dtappenddate+'</p><span class="panel"><a href="'+idata.smsurl+'" class="view" data-tplid="'+idata.tplid+'" target="_blank">查看详细</a> <a href="#" class="del">忽略</a></span><s class="s"></s></div>');
	var WebMessageInner = $('#WebMessageInner');
	setTimeout(function(){WebMessageInner.append(txt);WebMessageInner[0].scrollTop = WebMessageInner[0].scrollHeight;},50);
	$('#WebMessageNum').html(parseInt($('#WebMessageNum').html())+1);
}
function WebMessageMusic(file){
	if(typeof window['my_jPlayer'] === 'undefined'){
		$.ajax({url:window['tplPath']+"js/jquery.jplayer.min.js",dataType:'script'}).done(function(){
			setTimeout(function(){
				$('body').append('<div id="jquery_jplayer"></div>');
				window['my_jPlayer'] = $("#jquery_jplayer");
				my_jPlayer.jPlayer({
					ready: function (event) {
						$(this).jPlayer("setMedia",{mp3: file});
						if(typeof notplay === 'undefined'){window['my_jPlayer'].jPlayer('play');}
					},
					swfPath: window['tplPath']+"js", // jquery.jplayer.swf 文件存放的位置
					supplied: "mp3",
					wmode: "window"
				});
			},200);
		});
	}else{
		window['my_jPlayer'].jPlayer("setMedia",{mp3: file});
		window['my_jPlayer'].jPlayer('play');
	}
	return false;
}
function isIE6(){return getIEVersion() === '6'}
function getIEVersion(){
	var a=document;
	if(a.body.style.scrollbar3dLightColor!=undefined){
		if(a.body.style.opacity!=undefined){
			return "9"
		}else if(a.body.style.msBlockProgression!=undefined){
			return "8"
		}else if(a.body.style.msInterpolationMode!=undefined){
			return "7"
		}else if(a.body.style.textOverflow!=undefined){
			return "6"
		}else{
			return "IE5.5"
		}
	}
	return false;
}
$.fn.teseLoad = function(){
	var tese = $(this),tese_arr=[],tese_txt='';
	tese_arr = tese.html().split(',');
	
	if(tese_arr.length > 0){
		for(var k = 0; k<tese_arr.length;k++){
			tese_txt+='<span class="sp">'+tese_arr[k]+'</span>';
		}
		tese.html(tese_txt);
	}
}
$.returnTop=function(node){
	var node = $('<a href="#" alt="返回顶端" id="returnTop">返回顶端</a>');
	$(document).ready(function(){$('body').append(node)});
	var b = node.click(function(event){
		event.preventDefault();
		$("html,body").animate({scrollTop: 0},300);
	}),
	c = null;
	$(window).bind("scroll",function(){
	   var d = $(document).scrollTop(),
	   e = $(window).height();
	   0 < d ? b.css("bottom", "10px") : b.css("bottom", "-200px");
	   isIE6() && (b.hide(),clearTimeout(c),c = setTimeout(function(){
			0 < d ? b.show() : b.hide();
			clearTimeout(c);
		},
		300), b.css("top", d + e - 51))
	});
}
$.fn.rmenuShow2016 = function(){
	var rtop = $("#top");
	var $t = $(this),w_w = $(window).width(),r_css = 0;
	r_css = parseInt((w_w-1200)/2 - $t.width() - 12);
	r_css = r_css<0?0:r_css;
	$t.css({'right':r_css+'px'})
	rtop.click(function(e){
		e.preventDefault();
		$("html,body").animate({scrollTop: 0},300);
	});
	$(window).bind("scroll",function(){
		var d = $(document).scrollTop();
		0 < d ? rtop.show() : rtop.hide();
	}).bind('resize',function(){
		if($(window).width()<1406){
			$t.css({'right':'10px'});
		}else{
			$t.css({'right':r_css+'px'});
		}
	});
}