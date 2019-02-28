$.fn.imagesLoaded=function(callback){var $this=$(this),$images=$this.find('img').add($this.filter('img')),len=$images.length,blank='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';function triggerCallback(){callback.call($this,$images)}function imgLoaded(event){if(--len<=0&&event.target.src!==blank){setTimeout(triggerCallback);$images.unbind('load error',imgLoaded)}}if(!len){triggerCallback()}$images.bind('load error',imgLoaded).each(function(){if(this.complete||typeof this.complete==="undefined"){var src=this.src;this.src=blank;this.src=src}});return $this};

$.fn.tap = function(fn){ 
	var collection = this, 
	isTouch = "ontouchend" in document.createElement("div"), 
	tstart = isTouch ? "touchstart" : "mousedown",
	tmove = isTouch ? "touchmove" : "mousemove",
	tend = isTouch ? "touchend" : "mouseup",
	tcancel = isTouch ? "touchcancel" : "mouseout";
	collection.each(function(){
		var i = {};
		i.target = this;
		$(i.target).on('click',function(e){e.preventDefault();});
		$(i.target).on(tstart,function(e){
			var p = "touches" in e ? e.touches[0] : (isTouch ? window.event.touches[0] : window.event);
			i.startX = p.clientX;
			i.startY = p.clientY;
			i.endX = p.clientX;
			i.endY = p.clientY;
			i.startTime = + new Date;
		});
		$(i.target).on(tmove,function(e){
			var p = "touches" in e ? e.touches[0] : (isTouch ? window.event.touches[0] : window.event);
			i.endX = p.clientX;
			i.endY = p.clientY;
		});
		$(i.target).on(tend,function(e){
			if((+ new Date)-i.startTime<300){
				if(Math.abs(i.endX-i.startX)+Math.abs(i.endY-i.startY)<20){
					var e = e || window.event;
					e.preventDefault();
					fn.call(i.target);
				}
				i.startTime = undefined;
				i.startX = undefined;
				i.startY = undefined;
				i.endX = undefined;
				i.endY = undefined;
			}
		});
	});
	return collection;
}
function changeTwoDecimal(x){//保留两位小数，四舍五入
	var f_x = parseFloat(x);
	if (isNaN(f_x)){
		console.info('function:changeTwoDecimal->parameter error');
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
		console.info('function:changeTwoDecimal->parameter error');
		return false;
	}
	f_x=f_x.toFixed(3);
	f_x = f_x.substring(0,f_x.lastIndexOf('.')+3);
	return f_x;
}
function is_weixn(){//是否在微信客户端浏览器中打开
    var ua = navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i)=="micromessenger") {
        return true;
    } else {
        return false;
    }
}
function windowToShow(action_name,to_url,ok_url){//从微信客户端中跳出//微信客户端中的支付宝问题
	var html = '<s class="s s1"></s><s class="s s2"></s>'+
	'<div class="txt">请在菜单中选择在浏览器中打开，<br>以完成'+action_name+'。</div>'+
	'<a href="'+ok_url+'" class="btn">已完成'+action_name+'</a>　<a href="'+ok_url+'" class="btn">取消并返回</a>';
	var divs = document.createElement('div');
	divs.id = 'alipayForSafari';
	divs.className = 'alipay_for_safari';
	divs.innerHTML = html;
	$('body').append(divs);
	$('#alipayForSafari').show();
	history.pushState(null, '', to_url);
}
function MSGwindowShow_JSON(data){
	if(data.action === "pay" && data.classid === "2" ){
		payAppsubmitGo(data);
	}else{
		if(data.islogin === '1'){
			$('#isrep').val('');
			$('#parentid').val('');
			$('#cmt_txt').html('');
			$('#chrcontent').html('');
			$('#chrcontent2').val('');
			$('#closeReply').trigger('click');
			if(data.isopen === '0'){
				MSGwindowShow('revert','0','恭喜你，回复成功！请耐心等待系统审核！','','');
			}else{
				successPostRevert(data);
			}
		}else{
			MSGwindowShow('revert','0',data.error,'','');
		}
	}
}
function payAppsubmitGo(MSGkeyval){
	if(isapp ==="1"){
		var	YDB = new YDBOBJ();
		if(MSGkeyval.Payid === "7" ){//拉起微信app支付
			YDB.SetWxpayInfo(MSGkeyval.ProductName, MSGkeyval.Desicript, MSGkeyval.Price, MSGkeyval.OuttradeNo,MSGkeyval.attach);
		}
		else if(MSGkeyval.Payid === "1" ){//拉起阿里app支付
			YDB.SetAlipayInfo(MSGkeyval.ProductName, MSGkeyval.Desicript, MSGkeyval.Price, MSGkeyval.OuttradeNo);
		}
	}
}
function windowlocationhref(url){
	if(url.length > 5){window.location.href=url;}
}
function MSGwindowShow(action,showid,str,url,formcode){
	if(!!$('#form_submit_disabled')[0]){
		$('#form_submit_disabled').removeClass('disabled').prop('disabled',false);
	}
	var sys_tips = '<div class="sys_tips" id="sys_tips" style="display:none;"><div class="hd" id="sys_tips_title"></div><div class="bd"><p id="sys_tips_info"></p><div class="btn"><a href="#" class="btn2" id="sys_tips_submit">确定</a></div></div></div>';
	if(!$('#sys_tips')[0]){
		$('body').append(sys_tips);
	}
	var sys_tips = $('#sys_tips'),sys_tips_title = $('#sys_tips_title'),sys_tips_info = $('#sys_tips_info'),sys_tips_submit = $('#sys_tips_submit');
	if(action === "pay"){
		$('#have_login').hide();
		if(showid=="2"){
			document.getElementById('formcode').value=formcode;//赋值code
			document.forms['submitpay'].submit();//提交支付
			//这里添加支付中信息提示窗口
			//$('#pay_tips').show();
		}else if(showid=="1"){
			showConsole('恭喜您！',!0);
			//if(url.length > 5){window.location.href=url;}
		}else if(showid=="0"){
			alert(str);
			if(url.length > 5){window.location.href=url;}
		}else{
			alert(str);
		}
		document.getElementById('formcode').value="payok";//设置默认值防止二次提交
	}else if(action === "jifen"){
		if(typeof formcode !== 'undefined' && formcode!=='0' && formcode!==0){
			str = str + '，增加'+formcode +window['jifenneme']+'！';
		}
		showConsole('提示',true);
	}else if(action === "jiaoyou_buy"){
		if(typeof getPageInfo !== 'undefined'){
			getPageInfo();
		}
	}else if(action === "jiaoyou_keep"){
		$('#keeptxt').html('已收藏');
		$('#keepnum').html(formcode);
	}else if(action === "jiaoyou_flowers"){
		$('#flowernum').html(formcode);
		$('#flower').find('.closes').trigger('click');
		showConsole('提示',false);
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
		}else if(showid=="5"){ //成功并由页面刷上层页面
			showConsole('提示',false);
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
			if(showid === '5'){
				window.parent.location.href=window.parent.location.href;
			}
		});
		sys_tips.show();
		var w_h = $(window).height(),d_h = sys_tips.height(),s_h = $(document).scrollTop(),top_val = (w_h-d_h)/2;
		sys_tips.css({'top':top_val+'px'});
	}
}
//地图测距
window['GPSpoint'] = null;
function reloadLocation(callback){//切换地址 恢复当前位置
	var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
			//alert('您的位置：'+r.point.lng+','+r.point.lat);
			callback&&callback.call(this,r.point);
		}
		else {
			if(typeof keyvalues !== 'undefined'){getPagingGlobal();}
			MSGwindowShow('location','0','抱歉，我们没有获取到您的位置信息','','');
		}        
	},{enableHighAccuracy: true});
}
function getLocation(callback){
	var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
			callback&&callback.call(this,r.point);
		}
		else {
			if(typeof keyvalues !== 'undefined'){getPagingGlobal();}
			MSGwindowShow('location','0','抱歉，我们没有获取到您的位置信息','','');
		}        
	},{enableHighAccuracy: true});
}
function showMapGPSre(point){
	if(typeof showGPSLocation !== 'undefined'){
		showGPSLocation(point);
		window['GPSpoint'] = point;
	}
	var geoc = new BMap.Geocoder();
	geoc.getLocation(point, function(rs){
		var addComp = rs.addressComponents;
		$('#curLocation2').html(addComp.district + addComp.street + addComp.streetNumber);
	});
}
function showMapGPS(point){
	var map = window['Lmap'] || new BMap.Map();
	window['GPSpoint'] = point;
	setMap(map,point);
	if(typeof keyvalues !== 'undefined'){
		getPagingGlobal({"p":"1",'x':point.lng,'y':point.lat},true);
	}
}
function showMapBD(longitude, latitude){
	if(longitude===''){return;}
	var map = window['Lmap'] || new BMap.Map();
	var myPoint = new BMap.Point(longitude, latitude); //GPS坐标
	setMap(map,myPoint);
}
function setMap(map,point){
	
	var mapPointList = $('#mapPoint').find('.item');
	//逆向地址解析
	var geoc = new BMap.Geocoder();
	geoc.getLocation(point, function(rs){
		var addComp = rs.addressComponents;
		$('#curLocation').html(addComp.district + addComp.street + addComp.streetNumber);
	});
	//列表距离
	mapPointList.each(function(){
		var mapPoint = $(this);
		var dataX = mapPoint.attr('data-x'),dataY = mapPoint.attr('data-y');
		if(dataX === '' || dataX ==='0'){return;}
		var pointB = new BMap.Point(dataX,dataY);  // 商家坐标
		var txt = (map.getDistance(point,pointB)/1000).toFixed(2)+'公里';
		$(this).find('.juli').html(txt);
	});
}

//filter
function showFilter(option){
	var node = $('#'+option.ibox),
		fullbg = $('#'+option.fullbg),
		ct1 = $('#'+option.content1),
		ct2 = $('#'+option.content2),
		ctp1 = ct1.find('.innercontent'),
		ctp2 = ct2.find('.innercontent'),
		currentClass = 'current';
	var tabs = node.find('.tab .item'),
		conts = node.find('.inner');
	//fullbg.css({'height':$(document).height()+'px'});
	
	var timelist = node.find('.inner > ul > li').filter(function(index) {
			return $('ul', this).length > 0;
		}),
		childUL = null;
	timelist.each(function(){
		var that = $(this);
		that.addClass('hasUL');
		that.children('a').addClass('hasUlLink');
	});
	ct1.on("click",".hasUlLink",function(e){
		e.preventDefault();
		var that = $(this).parent();
		if(!window['myScroll_inner']){
			window['myScroll_inner'] = new IScroll('#'+option.content2, {
				click: true,
				scrollX: false,
				scrollY: true,
				scrollbars: true,
				interactiveScrollbars: true,
				shrinkScrollbars: 'scale',
				fadeScrollbars: true
			});
		}
		setTimeout(function(){
			ctp1.find('.hasUL_current').removeClass('hasUL_current');
			that.addClass('hasUL_current');
			ctp2.html('<ul>'+that.find('ul').html()+'</ul>').show();
			ct1.css({'width':'50%'});
			ct2.show();
			window['myScroll_inner'].refresh();
		},100);
	});
	tabs.each(function(i){
		$(this).bind("click",function(e){
			e.preventDefault();
			if($(this).attr('data-isopen')==='1'){
				hide_nav();
				return false;
			}
			tabs.attr('data-isopen','0');
			$(this).attr('data-isopen','1');
			if(!window['myScroll_parent']){
				window['myScroll_parent'] = new IScroll('#'+option.content1, {
					click: true,
					scrollX: false,
					scrollY: true,
					scrollbars: true,
					interactiveScrollbars: true,
					shrinkScrollbars: 'scale',
					fadeScrollbars: true
				});
			}
			node.addClass('filter-fixed');
			ctp1[0].innerHTML = conts.eq(i).html();
			fullbg.fadeIn('fast');
			tabs.removeClass(currentClass);
			tabs.eq(i).addClass(currentClass);
			if($(this).attr('data-hasbigid') !== undefined){
				var triggerEle = ct1.find('.hasUL[categoryid="'+$(this).attr('data-hasbigid')+'"]');
				ct1.css({'width':'50%'}).show();
				ct2.show();
				triggerEle.find('.hasUlLink').trigger('click');
			}else{
				ct2.hide();
				ct1.css('width','100%').show();
			}
			setTimeout(function(){
				window['myScroll_parent'].refresh();
			},100);
			if($(this).attr('data-more') === '1'){
				node.addClass('filter-fixed-btn');
			}else{
				node.removeClass('filter-fixed-btn');
			}
		});
	});
	fullbg.bind('click',function(e){
		e.preventDefault();
		hide_nav();
	});
	function hide_nav(){
		node.removeClass('filter-fixed').removeClass('filter-fixed-btn');
		fullbg.fadeOut('fast');
		timelist.removeClass('hasUL_current');
		tabs.removeClass(currentClass).attr('data-isopen','0');
		ct1.css('width','100%').hide();
		ct2.hide();
	}
	
	option.callback && option.callback.call(this);
}
//遮罩页
function showNewPage(tit,html,callback){
	var windowIframe = $('#windowIframe'),windowIframeTitle = $('#windowIframeTitle'),windowIframeBody = $('#windowIframeBody');
	function showBox(){
		windowIframe.show();
		//$('body').css({'height':$(window).height()+'px','overflow':'hidden'});
		$('.wrapper').hide();
	}
	function hideBox(){
		windowIframe.hide();
		//$('body').css({'height':'auto','overflow':'visible'});
		$('.wrapper').show();
	}
	var addEditAddressInit = function(){
		if(windowIframe.attr('data-loaded') === '0'){
			var w_h = $(window).height();
			windowIframeTitle.html(tit);
			windowIframeBody.html(html);
			windowIframe.css({'min-height':w_h+'px'});
		}
		showBox();
		callback&&callback.call(this);
		
	};
	
	addEditAddressInit();
	windowIframe.on('click','.close',function(e){
		e.preventDefault();
		hideBox();
	});
}
function getCategory(node,sid,callback){
	var url = window['siteUrl']+'request.ashx?jsoncallback=?&action=category&id='+sid;
	$.getJSON(url,function(data){
		var d = data[0].MSG;
		window['loadCat']++;
		callback&&callback.call(this,node,d);
		
	});
}
var IDC2 = (function(){
	jQuery.extend(jQuery.easing,{easeOutCubic:function(t,e,i,n,o){return n*((e=e/o-1)*e*e+1)+i}});
	var closeGG = function(node){
		var node = $('#'+node),btn = node.find('.close');
		if(!!node.find('a')[0]){node.show();}
		btn.click(function(){
			node.slideUp('easeOutCubic');
		});
	}
	var loginout = function(siteUrl){
		if(isapp === '1'){ YDB.CloseGPS();}
		var url = siteUrl+"request.ashx?action=loginout&json=1&jsoncallback=?&date=" + Math.random();
		$.getJSON(url,function(data){
			if(data[0].islogin === '0'){
				if(data[0].bbsopen === "open"){
					var   f=document.createElement("IFRAME")   
					f.height=0;
					f.width=0;
					f.src=data[0].bbsloginurl;
					if (f.attachEvent){
						f.attachEvent("onload", function(){
							setTimeout(function(){window.location.href=siteUrl;},50);
						});
					} else {
						f.onload = function(){
							setTimeout(function(){window.location.href=siteUrl;},50);
						};
					}
					document.body.appendChild(f);
				}else{
					setTimeout(function(){window.location.href=siteUrl;},50);
				}
			}else{
				alert("对不起，操作失败！");
			}
		}).error(function(){alert("对不起，操作失败！");});
	}
	var showLogin = function(){
		var loginIco = $('#login_ico'),
			login_inner = $('#login_inner'),
			login_ico = $('#login_ico');
		loginIco.click(function(){
			login_inner.slideToggle('easeOutCubic');
		});
	}
	var isLogin = function(siteUrl,siteName,source){
		var sourceS = source || '';
		var url = siteUrl+"request.ashx?action=islogin&tempid="+sourceS+"&json=1&jsoncallback=?",
			node = $("#login_inner"),login_ico = $('#login_ico'),txt='';
		var hash = '?from='+encodeURIComponent(window.location.href);
		
		$.getJSON(url,function(data){
			if(data[0].islogin==="1"){
				txt="<p><span class=\"username\">"+data[0].name+"</span>，您好！欢迎登录"+siteName+"！<br><a href=\""+siteUrl+"member\">[管理中心]</a>　　<a href=\"javascript:IDC2.loginout('"+siteUrl+"');\">[退出]</a></p><input value=\"1\" id=\"isLogin\" type=\"hidden\" /><input value=\""+data[0].jibie+"\" id=\"user_jibie\" type=\"hidden\" />";
				login_ico.addClass('ico_ok');
				//loadWEBmessage();//消息系统
				if(typeof getUserState !== 'undefined'){
					window['userDate'] = data[0];
					getUserState();
				}
			}else{
				$('#login_ico').attr({'href':siteUrl+'member/login.html'+hash});
				txt='<p>您好，欢迎来到'+siteName+'！<br><a href="'+siteUrl+'member/login.html'+hash+'">[登录]</a>　　　<a href="'+siteUrl+'member/register.html">[注册]</a><input value="0" id="isLogin" type="hidden" /><input value="" id="user_jibie" type="hidden" /></p>';
				if(typeof getUserState !== 'undefined'){
					window['userDate'] = {};
					getUserState();
				}
			}
			node.html(txt);
		});
	}
	
	var tabADS = function(node){
		var obj = node;
		var currentClass = "current";
		var tabs = obj.find(".tab-hd").find(".item");
		var conts = obj.find(".tab-cont");
		var t;
		tabs.eq(0).addClass(currentClass);
		conts.eq(0).nextAll().hide();
		tabs.each(function(i){
			$(this).tap(function(){
				conts.hide().eq(i).show();
				tabs.removeClass(currentClass).eq(i).addClass(currentClass);
			});
		});
	}
	var textMarquee = function(e){
		var n=$(e),r=n.width(),w=$(window).width(),i=n.html(),s=0,speed=Math.round(r/w*30);
		if(r<w){return;}
		n.html(i+i),s=r;
		var o=s/speed,
			u="marque"+(new Date).valueOf(),
			a="@-webkit-keyframes "+u+" { 0% {-webkit-transform:translate3d(0,0,0)} 100% {-webkit-transform:translate3d(-"+s+"px,0,0)}}\n";
		a+=a.replace(/\-webkit\-/g,"");
		$("head").append("<style>"+a+"</style>");
		var f=u+" "+o+"s linear infinite";
		n.css({"-webkit-animation":f,animation:f});
	}
	var footWorker = function(){
		var t = $('#shangjiaSelect'),node = $('#shangjiaSelectPo');
		t.click(function(e){
			e.preventDefault();
			if(t.attr('data-isShow')==='0'){
				node.show();
				t.attr('data-isShow','1');
			}else{
				node.hide();
				t.attr('data-isShow','0');
			}
		})
	}
	return {
		loginout:loginout,
		isLogin:isLogin,
		showLogin:showLogin,
		closeGG:closeGG,
		tabADS:tabADS,
		textMarquee:textMarquee,
		footWorker:footWorker
	}
})();
$.fn.radioForm = function(){
	this.each(function(){
		var list = $(this).find('.gx_radio');
		var forname = $(this).attr('data-name');
		var sid=$('input[name="'+forname+'"]:checked').attr('value');
		if(sid !=='' && !!sid){
			$(this).find('.gx_radio').removeClass('current');
			$(this).find('.gx_radio[data-val="'+sid+'"]').addClass('current');
		}
		list.click(function(e){
			e.preventDefault();
			$('input[name="'+forname+'"][value="'+$(this).attr('data-val')+'"]').prop('checked',true);
			list.removeClass('current');
			$(this).addClass('current');
		});
	});
}
$.fn.radioForm2 = function(){
	this.each(function(){
		var list = $(this).find('.gx_radio');
		list.click(function(e){
			e.preventDefault();
			$('#'+$(this).attr('data-id')+$(this).attr('data-val')).prop('checked',true);
			list.removeClass('checked');
			$(this).addClass('checked');
		});
	});
}
function setStatenum(selector){
	var statenum = Math.round(Math.random()*1E15);
	$(selector).val(statenum);
}
$.fn.get_TG_num = function(selector){
	var _t = $(this),list = _t.find(selector),arr_id = [],txt_id='';
	list.each(function(index,item){
		arr_id.push($(item).attr('data-tgid'));
	});
	txt_id = arr_id.join(',');
	var url = window['siteUrl']+'request.ashx?action=chrnum&key=tg&jsoncallback=?&id='+txt_id;
	$.getJSON(url, function(data){
		if(data[0]['islogin'] === '1'){
			for(var i=0;i<data[0]['MSG'].length;i++){
				for(var k in data[0]['MSG'][i]){
					_t.find('.tg_chrnum_'+k).html(data[0]['MSG'][i][k][0]['chrnum'])
				}
			}
		}
	});
}
$.fn.mUploadFile = function(){
	var t = $(this),btn = t.find('.a-upload'),showFileName = t.find('.showFileName');
	btn.on("change","input[type='file']",function(){
		var filePath=$(this).val();
		if(filePath.indexOf("jpg")!=-1 ||filePath.indexOf("jpeg")!=-1 || filePath.indexOf("png")!=-1 || filePath.indexOf("gif")!=-1){
			var arr=filePath.split('\\');
			var fileName=arr[arr.length-1];
			showFileName.html(fileName);
		}else{
			showFileName.html("您上传文件类型有误！");
			return false 
		}
	});
}
$.fn.zan_total = function(styleid,sid,val){
	var t = $(this);
	var urls = nowdomain+'request.ashx?action=dianzan&styleid='+styleid+'&id='+sid+'&jsoncallback=?';
	t.click(function(e){
		e.preventDefault();
		$.getJSON(urls,function(data){
			if(data[0].islogin === '1'){
				t.html(data[0].MSG[0][val]);
			}else{
				MSGwindowShow('shopping','0',data[0].error,'','');
			}
		});
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
	$(window).blur(function(){
		RunOnunload();
	});
	$(window).focus(function(){
		newloadWEBmessage();
	});
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
	var txt = $('<div class="item tplid_'+idata.tplid+'">'+idata.title+'<p class="date">'+idata.dtappenddate+'</p><span class="panel"><a href="'+idata.smsurl+'" class="view" data-tplid="'+idata.tplid+'">查看详细</a> <a href="#" class="del">忽略</a></span><s class="s"></s></div>');
	$('#WebMessageInner').prepend(txt);
	$('#WebMessageNum').html(parseInt($('#WebMessageNum').html())+1);
}
window['if_played_mp3'] = false;
function WebMessageMusic(file){
	if(!$('#html5_jplayer')[0]){
		$('body').append('<audio id="html5_jplayer" controls="false" hidden="true"></audio>');
		$(window).one('click',function(){
			$('#html5_jplayer').attr('src',file);
			$('#html5_jplayer')[0].play();
		});
		
	}else{
		$('#html5_jplayer').attr('src',file);
		$('#html5_jplayer')[0].play();
	}
	return false;
}
function showloupanAddTG(Loupan_loupanid){
	window['heightV'] = 318;
	var mask = $('#mask');
	var inner_iframe = $('#inner_iframe');
	inner_iframe.css({'top':'auto','bottom':'0','height':'0px'});
	if(document.getElementById('isLogin').value !== '1'){	
		window.location.href=nowdomain+"member/login.html?from="+(encodeURIComponent(window.location.href));
	}
	else{
		mask.show();
		inner_iframe.show().animate({'height':window['heightV']+'px'},500,function(){});
		var myiframe = '<iframe src="../request.aspx?action=addtg&id='+Loupan_loupanid+'" scrolling="no" frameBorder="0" width="100%" height="'+window['heightV']+'"></iframe>';
		inner_iframe[0].innerHTML=myiframe;
	}
	return false;
}
function LoginHide(){
	$('#inner_iframe').animate({'height':'0px'},500,function(){$('#mask').hide();});
}
function getQuan(shopid){
	var o_quan = $('#o_quan');
	var url = siteUrl+'request.ashx?action=getshopcard&shopid='+shopid+'&jsoncallback=?';
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			var arr = data[0].MSG;
			for(var i=0;i<arr.length;i++){
				if(parseInt(arr[i].nousercardcount) > 0){
					arr[i].disable = '';
				}else{
					if(arr[i].ismycard === "0"){
						arr[i].disable = 'disable';
					}else{
						arr[i].disable = '';
					}
				}
				var TPL=$('#tp_quan').html().replace(/[\n\t\r]/g, '');
				$('#o_quan').append(Mustache.to_html(TPL, data[0].MSG[i]));
			}
			$('#tab_item_quan')[0]&&$('#tab_item_quan').show();
			o_quan.on('click','li',function(e){
				e.preventDefault();
				if(!!$(this).hasClass('success1') || !!$(this).hasClass('disable')){return false;}
				lingQuan($(this),$(this).attr('data-picinum'),$(this).attr('data-styleid'));
			});
			if(typeof pageOneQuan !=='undefined'){
				pageOneQuan.call(this,data[0].MSG[0]);
			}
		}else{
			//MSGwindowShow('lingQuan','0',data[0].error,'','');
		}
	});
}
function lingQuan(o,picinum,styleid){
	if($('#isLogin').val()!=='1'){
		MSGwindowShow('lingQuan','1','您还没有登录哦！',siteUrl+'member/login.html?from='+encodeURIComponent(window.location.href),'');
		return false;
	}
	var url = siteUrl+'request.ashx?action=setusercard&picinum='+picinum+'&styleid='+styleid+'&jsoncallback=?';
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			o.addClass('success1');
		}else{
			MSGwindowShow('lingQuan','0',data[0].error,'','');
		}
	});
}
function getHongbao(){
	if($.cookie("wap_hongbao") === '1'){
		return false;
	}
	var url = window['siteUrl']+'request.ashx?action=ismyhongbao&ishtml=1&jsoncallback=?';
	//moban:pc/main/default/member/IsMyHongbao.html
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			$.cookie("wap_hongbao",'1',{domain:"."+window['SiteYuming'],path:'/',expiress:1})
			$('body').append(data[0].MSG).on('click','#hongbaoNode .close',function(e){
				e.preventDefault();
				$('#hongbaoNode').hide();
				$('#mask').hide();
			});
			$('#mask').show();
			var node = $('#hongbaoNode');
			node.css({'margin-top':'-'+parseInt(node.height()/2)+'px'});
			node.find('li').each(function(){
				if(parseInt($(this).attr('data-nousercardcount'))<1){
					$(this).addClass('disable');
				}
				if($(this).attr('data-moneymin') === '0'){
					$(this).find('.moneymin').html('无门槛券');
				}
			});
			node.on('click','li',function(e){
				e.preventDefault();
				if(!!$(this).hasClass('success1') || !!$(this).hasClass('disable')){return false;}
				lingQuan($(this),$(this).attr('data-picinum'),$(this).attr('data-styleid'));
			});
		}
	});
	
}