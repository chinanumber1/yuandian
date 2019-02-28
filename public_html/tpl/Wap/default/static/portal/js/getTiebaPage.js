var myScroll;
var ifReload = false;

function setTimer(){
	var myDate = new Date();
	var hours = myDate.getHours();
	var minutes = myDate.getMinutes() > 10?myDate.getMinutes():'0'+myDate.getMinutes()
	$('#reload').find('.time').html('今天 '+myDate.getHours() + ':' + minutes);
}

function loaded_page(){
	$('body').css({'min-height':$(window).height()+'px'});
	$(window).bind('resize',function(){
		$('body').css({'min-height':$(window).height()+'px'});
	});
	var pullDownHeight = 50;
	var addheight = false;
	myScroll = new IScroll('#wrapper', {
		probeType: 2,
		mouseWheel: true,
		click: true,
		preventDefaultException:{tagName: /^(DIV|INPUT|TEXTAREA|BUTTON|SELECT)$/ },
		scrollX: false,
		scrollY: true,
		scrollbars: false,
		interactiveScrollbars: true,
		shrinkScrollbars: 'scale',
		fadeScrollbars: true
	});
	//lazyImg('#pagingList .n_img',true);
	myScroll.on('scroll',function(){
		if(typeof showCatState !== 'undefined'){
			showCatState(true);
		}
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
		lazyImg('#pagingList',window['isIscroll5']);
		if ((this.y - this.maxScrollY) < 10) {             
			!window['ifNoMore']&&pullUpAction();
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
		//隐藏顶部
		if(!!window['isIscroll5_hideHead']){
			var mainPage = $('#wrapper'),hideHead = $('#hideHead'),mh = mainPage.height(),hasHeight=hideHead.outerHeight()+10;
			if (this.y < -100 && !addheight) {             
				addheight = true;
				$('#hideHead').hide();
				
				mainPage.css({"-webkit-transform":"translate3d(0,-"+hasHeight+"px,0)",transform:"translate3d(0,-"+hasHeight+"px,0)","-webkit-transition":"-webkit-transform 0.6s",transition:"transform 0.6s",height:(mh+hasHeight)+"px"});
				
				
				setTimeout(function(){
					myScroll.refresh();
				},620);
				
			}
			if(this.y == 0 && !!addheight){
				addheight = false;
				$('#hideHead').show();
				
				mainPage.css({"-webkit-transform":"translate3d(0,0,0)",transform:"translate3d(0,0,0)","-webkit-transition":"-webkit-transform 0.6s",transition:"transform 0.6s",height:"auto"});
				
				myScroll.refresh();
			}
		}
	});
	function pullDownAction(){
		ifReload = false;
		$('#reload').hide();
		$('#pullDown').find('.loader').show();
		getPagingGlobal({p:'1'},null,'','2');
	}
	function pullUpAction(){
		setTimeout(function(){
			$('#pullUp').show();
			myScroll.refresh();
			myScroll.scrollBy(0, -pullDownHeight);
		}, 0);
		getPagingGlobal({},null,'','0');
	}
}