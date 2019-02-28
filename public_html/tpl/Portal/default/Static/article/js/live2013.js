//页面滚动锚点效果
!function(a){"use strict";a.fn.scrolld=function(b){function c(){i=f.height()}var d,e=this,f=a(window),g=a("html"),h=a("body"),i=f.height(),j=a.extend({position:"top",speed:1100,offset:0,easing:"scrolldEasing",callback:null},b);return function(){var a;f.on("resize",function(){a?clearTimeout(a):0,a=setTimeout(c,150)})}(),function(){var a="undefined"!=typeof InstallTrigger;d=a?g:h}(),function(){for(var b=e.length,f=0;b>f;f++)a(e[f]).on("click",function(){c();var b=a(this).data("scrolld"),e=a("#"+b),f=e.offset().top,g=e.outerHeight(),h=~~(f+j.offset);"center"===j.position&&i>g&&(h=~~(f-(i/2-g/2))),d.stop(!0).animate({scrollTop:h},j.speed,j.easing,function(){"function"==typeof j.callback&&j.callback()})})}(),jQuery.extend(jQuery.easing,{scrolldEasing:function(a,b,c,d,e){var f=(b/=e)*b,g=f*b;return c+d*(-.749999*g*f+2.5*f*f+-2*g+-1.5*f+2.75*b)}}),this}}(jQuery);
//imagesLoaded
$.fn.imagesLoaded=function(callback){var $this=$(this),$images=$this.find('img').add($this.filter('img')),len=$images.length,blank='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';function triggerCallback(){callback.call($this,$images)}function imgLoaded(event){if(--len<=0&&event.target.src!==blank){setTimeout(triggerCallback);$images.unbind('load error',imgLoaded)}}if(!len){triggerCallback()}$images.bind('load error',imgLoaded).each(function(){if(this.complete||typeof this.complete==="undefined"){var src=this.src;this.src=blank;this.src=src}});return $this};
$.fn.doMaodian = function(){
	var d_arr = [],m_list = $(this).find('a');
	m_list.each(function(){
		var t_id = $(this).attr('data-scrolld');
		var t = $('#'+t_id).offset().top;
		d_arr[t] = t_id;
	});
	$(window).bind("scroll",function(){
		var d = $(document).scrollTop();
		todoMaodian(d);
	});
	function todoMaodian(t){
		var n=99999999;
		var r;
		for(i in d_arr){
			if(Math.abs(t-i)<n){n=Math.abs(t-i);r=d_arr[i];}
		}
		m_list.removeClass("current");
		$('a[data-scrolld="'+r+'"]').addClass("current");	
	}
}
$.companyUrl = function(){
	var current_host = window.location;
	var url_obj = $.url(current_host).param();
	if(url_obj['colname'] !== '' && url_obj['colname'] !== '0' && (url_obj['key'] === '' || url_obj['key'] === '0')){
		$('#s_'+url_obj['colname']).addClass('cur').parent().parent().prev().find('.rights').removeClass().addClass('xias');
		$('#s_'+url_obj['colname']).addClass('cur').parent().parent().parent().addClass('open');
	}
	if(url_obj['key'] !== '' && url_obj['key'] !== '0'){
		$('#s_'+url_obj['key']).addClass('cur').parent().parent().prev().find('.rights').removeClass().addClass('xias');
		$('#s_'+url_obj['key']).addClass('cur').parent().parent().parent().addClass('open');
	}
	for(var i in url_obj){
		$('#'+i).val(url_obj[i]);
	}
}
function showmobileortel(){
	var c_chrtel = $('#c_chrtel');
	var c_chrmobile = $('#c_chrmobile');
	if(c_chrtel.val() === ''){
		$('#n_chrtel').hide();
	}
	if(c_chrmobile.val() === ''){
		$('#n_chrmobile').hide();
	}
}
showmobileortel();
function showquyu(str,urls,forID,nowcc){ 
	if (str.length > 0){ 
		var url=urls+str;
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		$.get(url,function(data){
			var sel=document.getElementById(forID);
			var val="选择地段";
			var str =data;
			sel.options.length=0;
			var arrstr = new Array();
			arrstr = str.split(",");
			//开始构建新的Select.
			sel.options.add(new Option( val,"")); 
			if(str.length>0)   {
				for(var i=0;i<arrstr.length-1;i++){
					//分割字符串
					var subarrstr=new Array
					subarrstr=arrstr[i].split("|")
					//生成下级菜单
					sel.options.add(new Option(subarrstr[1],subarrstr[0])); 
					if(nowcc==subarrstr[0]){sel.options[i+1].selected=true;}
				}
			}
		});
	} 
}
function showcategory(str,urls,forID,nowcc){ 
	if (str.length > 0){ 
		var url=urls+str+'&jsoncallback=?';
		
		$.getJSON(url,function(data){
			var arr = [];
			arr = data[0]["MSG"];
			var val="选择二级分类";
			var sel=document.getElementById(forID);
			sel.options.length=0;
			sel.options.add(new Option( val,"")); 
			for(var i=0;i<arr.length;i++){
				sel.options.add(new Option(arr[i]['name'],arr[i]['id'])); 
				if(nowcc==arr[i]['id']){sel.options[i+1].selected=true;}
			}
		});
	} 
}
$.fn.fastfabu = function(){
	$('#areaid').change(function(){
		showquyu($(this).val(),"../request.aspx?action=quyu&id=","qu_classid");
	});
	$('#bigid').change(function(){
		showcategory($(this).val(),siteUrl+"request.ashx?action=category_live&id=","categoryid");
	});
	
	var cmt_btn = $('#cmt_btn'),po_captcha = $('#po_captcha');
	if(po_captcha.length<1){
		cmt_btn.attr('type','submit');
		return false;
	}
	var close_btn = po_captcha.find('.close_captcha');
	cmt_btn.bind('click',function(e){
		po_captcha.show();
		
		cmt_btn.attr("disabled", true);
		cmt_btn.addClass("disabled");
	});
	close_btn.bind('click',function(e){
		cmt_btn.attr("disabled", false);
		cmt_btn.removeClass("disabled");
		po_captcha.hide();
		e.preventDefault();
	});
	
}

$.fn.jisu = function(){
	var t = $(this),mask = $('#mask'),btns = $('.jisu_btn'),close_btn = $(this).find('.close_all'),d,top,h=Math.ceil(($(window).height()/2) - (t.height()/2));
	mask.css({'height':$(window).height()+'px'});
	btns.bind('click',function(e){
		d = $(document).scrollTop();
		t.css('top',d+h);
		t.show();
		var myiframe = '<iframe src="fabu.html?action=fastfabu" scrolling="no" frameBorder="0" width="777" height="520"></iframe>';
		$('#inner_iframe')[0].innerHTML=myiframe;
		mask.show();
		e.preventDefault();
	});
	close_btn.bind('click',function(e){
		t.hide();
		mask.hide();
		e.preventDefault();
	});
	$(window).bind("scroll",function(){
		var d = $(document).scrollTop();
		t.css('top',d+h);
	});
	$(window).bind("resize",function(){
		var d = $(document).scrollTop();
		h=Math.ceil(($(window).height()/2) - (t.height()/2));
		t.css('top',d+h);
	});
}
$.fn.liveList = function(){
	var listodd = $(this).find('li:odd'),list = $(this).find('li');
	listodd.addClass('odd');
	list.hover(function(){
		$(this).toggleClass('hover');
	});
}
$.fn.houseGallery = function(){
	var $this = $(this);
	var dialog_data = $('#dialog_data');
	var dialog_imgList = $('#dialog_imgList');
	var dialog_img = $('#dialog_img');
	var preArrow = $('#preArrow');
	var nextArrow = $('#nextArrow');
	var arr = dialog_data.find('img'),len=arr.length,txt='',fristClass='',tPrev=$('#dialog_img_prev'),tNext=$('#dialog_img_next'),cellW=72,tIndex=0,pIndex=0,kyNum = len-5,yuNum = 0;
	
	if(len<1){nextArrow.hide();tPrev.hide();tNext.hide();return;}
	if(len<2){
		tNext.addClass('btn_disabled');
		nextArrow.hide();
	}
	for(var i=0;i<len;i++){
		if(i === 0){fristClass="cur";}else{fristClass='';}
		txt += '<li class="'+fristClass+'"><a href="'+arr.eq(i).attr('data-bigsrc')+'" data-index="'+i+'" class="item"><img src="'+arr.eq(i).attr('src')+'" alt="" /></a><s class="arrow"></s></li>';
	}
	dialog_imgList.html(txt);
	
	dialog_img.attr('src',dialog_data.find('img').eq(0).attr('data-bigsrc'));
	dialog_imgList.css({'width':cellW*len+'px'});
	nextArrow.click(function(e){
		e.preventDefault();
		tNext.trigger('click');
	});
	preArrow.click(function(e){
		e.preventDefault();
		tPrev.trigger('click');
	});
	tPrev.click(function(e){
		
		if(pIndex>0){
			pIndex--;
			showIMG(pIndex);
			if(tIndex>0 &&(len-pIndex)>3){
				tIndex--;
				dialog_imgList.animate({left:'+='+cellW},300,function(){});
			}
		}
		e.preventDefault();
	});
	tNext.click(function(e){
		if(pIndex<len-1){
			pIndex++;
			showIMG(pIndex);
			if((tIndex+1<len-4)&&(pIndex>2)){
				tIndex++;
				dialog_imgList.animate({left:'-='+cellW},300,function(){});
			}
		}
		e.preventDefault();
	});
	function showIMG(index){
		
		
		if(!$('#loddingGallery')[0]){
			var div = document.createElement('div');
			div.setAttribute('id','loddingGallery');
			$this.append(div);
		}else{
			$('#loddingGallery').show();
		}
		
		var target = dialog_imgList.find('.item').eq(index);
		dialog_imgList.find('.item').parent().removeClass('cur');
		target.parent().addClass('cur');
		dialog_img.attr('src',target.attr('href')).imagesLoaded(function(){
			$('#loddingGallery').hide();														 
		});
		if(index>0){tPrev.removeClass('btn_disabled');preArrow.show();}else{tPrev.addClass('btn_disabled');preArrow.hide();}
		if(index<len-1){tNext.removeClass('btn_disabled');nextArrow.show();}else{tNext.addClass('btn_disabled');nextArrow.hide();}
	}
	dialog_imgList.on('click','.item',function(e){
		e.preventDefault();
		pIndex = parseInt($(this).attr('data-index'));
		showIMG(pIndex);
		
		yuNum = pIndex-2;
		if(yuNum > kyNum){ yuNum = kyNum;}
		if(yuNum < 0){ yuNum = 0;}
		if((yuNum>0 || tIndex>0)){tIndex = yuNum;dialog_imgList.animate({left:-cellW*yuNum},300,function(){});}
	});
}
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
		$(objImg).css({'width':w+'px','height':h+'px'});
	}
}
$.fn.detailPicList = function(){
	var t = $(this),arr = $('#dialog_data').find('img'),txt='';
	if(arr.length > 0){
		for(var i=0;i<arr.length;i++){
			txt += '<div class="cell"><img src="'+arr.eq(i).attr('data-bigsrc')+'" alt="" /></div>';
		}
		t.html(txt);
	}
}