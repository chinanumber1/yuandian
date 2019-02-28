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
$.fn.search_sel = function(){
	var search_sel = $(this),mySle=$('#mySle2');
	var list = search_sel.find('a');
	list.click(function(e){
		mySle.val($(this).attr('data-val'));
		search_sel.find('.current').removeClass('current');
		$(this).parent().addClass('current');
		e.preventDefault();
	});
}
$.fn.managePic = function(){
	var t = $(this);
	t.find('.tab-cont').each(function(){
		$(this).find('li:lt(4)').find('.img').show();
	});
}
$.fn.loupan = function(){
	var t = $(this),loupandata = $('#loupandata'),list = loupandata.find('.item');
	if(list.length===0){
		return false;
	}
	var i=0,len=Math.ceil(list.length/8);
	for( ;i<len;i++){
		list = loupandata.find('.item');
		var lis = document.createElement('li');
		var txt = list.slice(0, 8).detach();
		$(lis).append(txt);
		t.append($(lis));
	}
	setTimeout(function(){$("#slide").slide({ mainCell:".bd ul",effect:"leftLoop",vis:1,scroll:1,titCell:'.dot li',autoPlay:true,interTime:6000});},50);
}
$.fn.sel_filter = function(){
	var t = $(this),txt = t.find('.txt'),po = t.find('.po');
	t.hover(function(){
		po.show();
	},function(){
		po.hide();
	});
	
}
$('#fc_nav').ready(function(){
	var that=$('#fc_nav'),
		url = window.location.href,
		url_L = url.toLowerCase(),
		channel = that.find('a'),
		forlink;
	channel.each(function(){
		forlink = $(this).attr("href").toLowerCase();
		if(url_L.indexOf(forlink)>=0){
			that.find('.select').removeClass();
			$(this).addClass("select");
		}
	});
	if(typeof window['istiebaNav'] !== 'undefined'){
		that.find('.select').removeClass();
		that.find('a[href*="tieba"]').addClass("select");
	}
});
$.fn.houseGallery = function(){
	var $this = $(this);
	var dialog_data = $('#dialog_data');
	var dialog_imgList = $('#dialog_imgList');
	var dialog_img = $('#dialog_img');
	var preArrow = $('#preArrow');
	var nextArrow = $('#nextArrow');
	var arr = dialog_data.find('img'),len=arr.length,txt='',fristClass='',tPrev=$('#dialog_img_prev'),tNext=$('#dialog_img_next'),cellW=90,tIndex=0,pIndex=0,kyNum = len-9,yuNum = 0;
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
			if(tIndex>0 &&(len-pIndex)>5){
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
			if((tIndex+1<len-8)&&(pIndex>4)){
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
		
		yuNum = pIndex-4;
		if(yuNum > kyNum){ yuNum = kyNum;}
		if(yuNum < 0){ yuNum = 0;}
		if((yuNum>0 || tIndex>0)){tIndex = yuNum;dialog_imgList.animate({left:-cellW*yuNum},300,function(){});}
	});
}
