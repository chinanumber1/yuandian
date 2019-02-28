window['dscroll'] = null;
function D_Scroll(options){
	this.options = {
		container:'container',
		content:'content',
		ct:'indicator',
		next:'next',
		prev:'prev',
		size:document.body.clientWidth,
		intervalTime:null,
		__timer:null,
		lazyIMG:false,
		allPageHeight:false,
		iScroll5:null
	}
	for (var key in options){
		this.options[key] = options[key];
	}
	this.container = document.getElementById(this.options.container);
	this.content = document.getElementById(this.options.content);
	this.ct = document.getElementById(this.options.ct);
	this.next = document.getElementById(this.options.next);
	this.prev = document.getElementById(this.options.prev);
	this.size = this.options.size;
	this.intervalTime = this.options.intervalTime;
	this.lazyIMG = this.options.lazyIMG;
	this.allPageHeight = this.options.allPageHeight;
	
	this.cell = this.content.querySelectorAll('.cell');
	this.len = this.cell.length;
	
	if(typeof this.init !== 'undefined'){
		this.init.apply(this,arguments);
	}
}
D_Scroll.prototype = {
	imgLazyLoad:function(index,bool){
		var index2=index;
		var that = this;
		var imgs = this.cell[index2].querySelectorAll('img');
		var attr = '';
		var e;
		for(var i=0,len=imgs.length;i<len;i++){
			e = imgs[i];
			attr = e.getAttribute('lazysrc');
			if(e.getAttribute('src') === attr){return false;}
			e.setAttribute('src',attr);
			if (e.complete || e.readyState && (e.readyState == "loaded" || e.readyState == "complete")){
				//执行完成事件
			}
			e.onload = function() {
				//执行完成事件
			},
			e.onerror = function() {}
		}
	},
	init:function(){
		var that = this;
		
		that.resetWidth();
		that.iScroll5 = new IScroll('#'+that.options.container,{scrollX:true,scrollY:false,click:true,momentum:false,snap:true,snapSpeed:400,indicators:{el:that.ct,resize:false}});
		$(that.prev).click(function(e){
			e.preventDefault();
			that.iScroll5.prev();
		});
		$(that.next).click(function(e){
			e.preventDefault();
			if(that.iScroll5.currentPage.pageX === 3){
				that.iScroll5.goToPage(0,0);
			}else{
				that.iScroll5.next();
			}
		});
		$(window).resize(function(){
			that.resetWidth();
			that.refresh();
		});
		that.iScroll5.on('beforeScrollStart',function(){
			that.clearTimer();
		});
		that.iScroll5.on('scrollEnd',function(){
			that.clearTimer();
			that.setTimer();
			that.lazyIMG && that.imgLazyLoad(that.iScroll5.currentPage.pageX);
		});
		that.lazyIMG && that.imgLazyLoad(0);
		that.setTimer();
	},
	resetWidth:function(){
		var that = this;
		that.size = document.documentElement.clientWidth;
		var w_h = document.documentElement.clientHeight;
		$(that.content).css({'width':that.size*that.len+'px'});
		this.allPageHeight && $(that.content).css({'height':(parseInt(w_h)-45)+'px'});
		$(that.content).find('img').css({'max-height':(parseInt(w_h)-45)+'px'});
		$(that.cell).css('width',that.size+'px');
		var indicator_width = parseInt(11*(that.len-1)+6,10);
		$(that.ct).css({'width':indicator_width+'px','margin-left':'-'+parseInt(indicator_width/2,10)+'px'});
	},
	refresh:function(){
		var that = this;
		that.cell = that.content.querySelectorAll('.cell');
		that.len = that.cell.length;
		that.resetWidth();
		that.iScroll5.refresh();
	},
	goToPage:function(p){
		var that = this;
		that.lazyIMG && that.imgLazyLoad(p);
		that.iScroll5.goToPage(p, 0, 0);
	},
	currentPage:function(p){
		var that = this;
		return that.iScroll5.currentPage;
	},
	setTimer:function(){
		var that = this;
		if(typeof that.intervalTime !== 'number'){return false;}
		that.__timer = window.setInterval(function(){$(that.next).trigger('click');},that.intervalTime);
	},
	clearTimer:function(){
		var that = this;
		that.__timer&&window.clearInterval(that.__timer);
	}
	
}



$.fn.imagesLoaded=function(callback){var $this=$(this),$images=$this.find('img').add($this.filter('img')),len=$images.length,blank='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';function triggerCallback(){callback.call($this,$images)}function imgLoaded(event){if(--len<=0&&event.target.src!==blank){setTimeout(triggerCallback);$images.unbind('load error',imgLoaded)}}if(!len){triggerCallback()}$images.bind('load error',imgLoaded).each(function(){if(this.complete||typeof this.complete==="undefined"){var src=this.src;this.src=blank;this.src=src}});return $this};

$.fn.picConsole = function(container){
	var showBigScroll5 = $('#showBigScroll5'),
		bigPic = $('#bigPic'),
		small_width = 100,
		small_height = 100,
		w_w = $(window).width(),
		w_h = $(window).height();
	function getChicun(img){
		var naturalWidth = img.naturalWidth,naturalHeight = img.naturalHeight,bili=0,toppx=0,leftpx=0;
		if(naturalWidth > w_w){
			bili = w_w/naturalWidth;
			naturalWidth = parseInt(naturalWidth*bili);
			naturalHeight = parseInt(naturalHeight*bili);
		}
		if(naturalHeight > w_h){
			bili = w_h/naturalHeight;
			naturalWidth = parseInt(naturalWidth*bili);
			naturalHeight = parseInt(naturalHeight*bili);
		}
		leftpx = parseInt((w_w - naturalWidth)/2);
		toppx = parseInt((w_h - naturalHeight)/2);
		var obj ={'toppx':toppx,'leftpx':leftpx,'naturalWidth':naturalWidth,'naturalHeight':naturalHeight}
		return obj;
	}
	
	function hideAnimate(){
		var list = $('#'+showBigScroll5.attr('data-fromList')).find('img');
		var index = window['dscroll'].currentPage().pageX,
			offset = list.eq(index).offset(),
			toppx =  offset.top - $(window).scrollTop(),
			leftpx = offset.left;
		
		var target = list.eq(index),
			small_width = target.width(),
			small_height = target.height();
		showBigScroll5.css({transition:'opacity .3s ease',"-webkit-transition":'opacity .3s ease','opacity':'0'});
		bigPic.attr({'src':target.attr('original')}).imagesLoaded(function(){
			var obj = getChicun($(bigPic)[0]);
			bigPic.css({"-webkit-transition":"none",transition:"none",'width':obj.naturalWidth+'px','height':obj.naturalHeight+'px',"-webkit-transform":"translate3d("+obj.leftpx+"px,"+obj.toppx+"px,0)",transform:"translate3d("+obj.leftpx+"px,"+obj.toppx+"px,0)"});
			container.hide();
			setTimeout(function(){
				bigPic.css({"-webkit-transform":"translate3d("+leftpx+"px,"+toppx+"px,0)",transform:"translate3d("+leftpx+"px,"+toppx+"px,0)","-webkit-transition":"all .3s",transition:"all .3s",'width':small_width+'px','height':small_height+'px'});
			},20);
		});
		setTimeout(function(){showBigScroll5.css('display','none')},300);
	}
	function showAnimate(list,index,toppx,leftpx,width,height){
		showBigScroll5.css({transition:'opacity .8s ease','opacity':'1'})
		bigPic.css({"-webkit-transform":"translate3d("+leftpx+"px,"+toppx+"px,0)",transform:"translate3d("+leftpx+"px,"+toppx+"px,0)","-webkit-transition":"all 0.4s",transition:"all 0.4s",'width':width+'px','height':height+'px'});
		setTimeout(function(){
			container.show();
			loadDScroll(list,index);
		},400);
	}
	function loadDScroll(list,index){
		var txt = '';
		list.each(function(){
			txt+='<div class="cell"><img lazysrc="'+$(this).attr('original')+'" src="'+window['Default_tplPath']+'images/loadding2.gif" /></div>';
		});
		container.find('.scroller').html(txt);
		if(container.attr('data-isloaded') === '0'){
			container.attr('data-isloaded','1');
			window['dscroll'] = new D_Scroll({'intervalTime':3000,'lazyIMG':true,'allPageHeight':true});
			window['dscroll'].goToPage(index);
		}else{
			window['dscroll'].refresh();
			window['dscroll'].goToPage(index);
		}
	}
	showBigScroll5.click(function(e){
		e.preventDefault();
		hideAnimate();
	});
	
	return this.each(function(){
		var t = $(this),
			list = t.find('img');
		list.click(function(e){
			var offset = $(this).offset();
			var t_top = offset.top - $(window).scrollTop(),
				t_left = offset.left,
				index = list.index($(this)),
				small_width = $(this).width(),
				small_height = $(this).height();
			
			showBigScroll5.css({'display':'block',"opacity":"0","-webkit-transition":"none",transition:"none"});
			bigPic.attr({'src':$(this).attr('original')}).css({'width':small_width+'px','height':small_height+'px',"-webkit-transform":"translate3d("+t_left+"px,"+t_top+"px,0)",transform:"translate3d("+t_left+"px,"+t_top+"px,0)","-webkit-transition":"none",transition:"none"});
			bigPic.imagesLoaded(function(){
				var obj = getChicun($(bigPic)[0]);
				showAnimate(list,index,obj.toppx,obj.leftpx,obj.naturalWidth,obj.naturalHeight);
			});
			showBigScroll5.attr('data-fromList',$(this).parent().parent().attr('id'));
		});
		
	});
}