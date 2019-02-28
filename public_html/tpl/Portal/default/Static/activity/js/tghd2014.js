$.fn.imagesLoaded=function(callback){var $this=$(this),$images=$this.find('img').add($this.filter('img')),len=$images.length,blank='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';function triggerCallback(){callback.call($this,$images)}function imgLoaded(event){if(--len<=0&&event.target.src!==blank){setTimeout(triggerCallback);$images.unbind('load error',imgLoaded)}}if(!len){triggerCallback()}$images.bind('load error',imgLoaded).each(function(){if(this.complete||typeof this.complete==="undefined"){var src=this.src;this.src=blank;this.src=src}});return $this};

$.fn.resizeIMG = function(width,height){
	var that = $(this);
	var imgList = that.find('img');
	var len = imgList.length;
	if(len>0){
		imgList.each(function(i,item){
			$(item).imagesLoaded(function(){
				AutoResizeImage(width,height,item);
			});
		});
	}
	function AutoResizeImage(maxWidth,maxHeight,objImg){
		var img = new Image();
		img.src = objImg.src;
		var hRatio;
		var wRatio;
		var Ratio = 1;
		var w = img.width;
		var h = img.height;
		wRatio = maxWidth / w;
		hRatio = maxHeight / h;
		if (maxWidth ==0 && maxHeight==0){
		Ratio = 1;
		}else if (maxWidth==0){//
		if (hRatio<1) Ratio = hRatio;
		}else if (maxHeight==0){
		if (wRatio<1) Ratio = wRatio;
		}else if (wRatio<1 || hRatio<1){
		Ratio = (wRatio<=hRatio?wRatio:hRatio);
		}
		if (Ratio<1){
		w = w * Ratio;
		h = h * Ratio;
		}
		objImg.height = h;
		objImg.width = w;
	}
}
var list = $('#output').find('li');
var txt = '';
if(list.length > 0){
	list.each(function(i){
		if(i === 0){
			txt += '<li class="on">1</li>';
		}else{
			txt += '<li>'+(i+1)+'</li>';
		}
	});
	$('#tabs').html(txt);
	setTimeout(function(){$("#slide").slide({ mainCell:".bd ul",effect:"leftLoop",vis:1,scroll:1,autoPlay:true});},20);
	showSlide($('#slide'));
}
function showSlide(node){
	var prev = node.find('.prev'),next = node.find('.next');
	node.hover(function(){
		prev.toggleClass('show');
		next.toggleClass('show');
	});
}

function showwybaoming(val,tt){
	if(tt=="1"){
		Demo_login('<div align=center><iframe src="/portal.php?c=activity&a=baoming&activeid='+val+'" scrolling="no" frameBorder=0 width=600 height=445></iframe></div>',600,445,600,445);
	}
	else{
		if(document.getElementById('isLogin').value !== '1'){
			window.location.href=nowdomain+"member/login.html?from="+(encodeURIComponent(window.location.href));
		}
		else{
			Demo_login('<div align=center><iframe src="/portal.php?c=activity&a=baoming&activeid='+val+'" scrolling="no" frameBorder=0 width=600 height=445></iframe></div>',600,445,600,445);
		}
	}
}
function Demo_login(string,ow,oh,w,h){
	 ShowDiv=string;
	 DialogShow(ShowDiv,ow,oh,w,h);	
	 var objDialog = document.getElementById("DialogMove");
	  var lstd = document.getElementById("lstd");
	 
}
function DialogShow(showdata,ow,oh,w,h){
	 var objDialog = document.getElementById("DialogMove");
	 if (!objDialog) 
	 objDialog = document.createElement("div");
	 t_DiglogW = ow;
	 t_DiglogH = oh;
	 DialogLoc();
	 objDialog.id = "DialogMove";
	 var oS = objDialog.style;
	 oS.display = "block";
	 oS.top = t_DiglogY + "px";	
	 oS.left = t_DiglogX + "px"; 
	 oS.margin = "0px";
	 oS.padding = "0px";
	 oS.width = w + "px";
	 oS.height = h + "px";
	 oS.position = "absolute";
	 oS.zIndex = "999";
	 oS.background = "#FFF";
	 oS.border = "solid #ddd 1px";
	 objDialog.innerHTML = showdata;
	 document.body.appendChild(objDialog);
	 delselect();
}
function DialogLoc(){
	 var dde = document.documentElement;
	 if (window.innerWidth){
	 	var ww = window.innerWidth;
		var wh = window.innerHeight;
		var bgX = window.pageXOffset;
		var bgY = window.pageYOffset;	
	 }else{	 	
		var ww = dde.offsetWidth;
		var wh = dde.offsetHeight;
		var bgX = dde.scrollLeft;
		var bgY = dde.scrollTop;	  
	 }
	 t_DiglogX = (bgX + ((ww - t_DiglogW)/2));
	 t_DiglogY = (bgY + ((wh - t_DiglogH)/2));
}
function LoginHide(){
	ScreenClean();
	var objDialog = document.getElementById("DialogMove");
	 if (objDialog){
		 objDialog.style.display = "none";
	 }
}
function ScreenClean(){
	 var objScreen = document.getElementById("ScreenOver");
	 if (objScreen)
	 objScreen.style.display = "none";
	 var allselect = document.getElementsByTagName("select");
	 for (var i=0; i<allselect.length; i++) 
	 allselect[i].style.visibility = "visible";
}
function delselect(){
	var allselect = document.getElementsByTagName("select");
	 for (var i=0; i<allselect.length; i++) 
	 allselect[i].style.visibility = "hidden";	
}
function showselect(){
	var allselect = document.getElementsByTagName("select");
	 for (var i=0; i<allselect.length; i++) 
	 allselect[i].style.visibility = "visible";	
}
function getnumOne(sid){
	var url = nowdomain+'request.ashx?action=chrnum&key=active&id='+sid+'&jsoncallback=?';
	$.getJSON(url,function(data){
		var obj = data[0]['MSG'][0];
		for(var k in obj){
			$('#suc_num').html(obj[k][0]['intnum']);
		}
	});
}