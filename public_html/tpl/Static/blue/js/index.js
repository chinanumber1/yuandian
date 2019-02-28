function goTop(){
	//window.location.href = "#nav1";
	$("html,body").animate({scrollTop: $("#nav1").offset().top}, 500);
}
function scrollToId(targetId){
	$("html,body").animate({scrollTop: $(targetId).offset().top-160}, 500);
}
//弹窗
function popup(mudi){
	//var thistop=$(this).offset().top;//获取当前位置的top
	//var maskHeight = $(document).height();//文档的总高度
	var maskWidth = $(window).width();//窗口的宽度		
	var maskHeight = $(window).height();//窗口高度		
	var popTop =  (maskHeight/2) - ($('#pop_box').height()/2);
	var popLeft = (maskWidth/2) - ($('#pop_box').width()/2);
	$('#pop_box').css({top:popTop,left:popLeft}).slideDown(600);
	$("#mudi").val(mudi);
}

//弹窗
function popup2(){
	//var thistop=$(this).offset().top;//获取当前位置的top
	//var maskHeight = $(document).height();//文档的总高度
	var maskWidth = $(window).width();//窗口的宽度		
	var maskHeight = $(window).height();//窗口高度		
	var popTop =  (maskHeight/2) - ($('#pop_box2').height()/2);
	var popLeft = (maskWidth/2) - ($('#pop_box2').width()/2);
	$('#pop_box2').css({top:popTop,left:popLeft}).slideDown(600);
}

//初始化对象
function Roll (){
	this.initialize.apply(this, arguments)
}
Roll.prototype ={
	initialize: function (obj)
	{
		var _this = this;
		this.obj = $('#'+obj);
		this.oUp = this.obj.find('.scroll_up');
		this.oDown = this.obj.find('.scroll_down');
		this.oList = this.obj.find('.scroll_list');
		this.aItem = this.oList.children();
		this.timer = null;
		this.iHeight = this.aItem.eq(0).height();
		this.oUp.click(function(){
			_this.up()
		});
		this.oDown.click(function(){
			_this.down()
		})
	},
	up: function ()
	{
		var tmpHtml = this.oList.children().last().prop('outerHTML');
		this.oList.children().last().remove();
		this.oList.prepend(tmpHtml).css('top',-this.iHeight+"px");
		this.doMove(0)
	},
	down: function ()
	{
		this.doMove(-this.iHeight, function (){
			var tmpHtml = this.oList.children().first().prop('outerHTML');
			this.oList.children().first().remove();	
			this.oList.append(tmpHtml);
			this.oList.css('top',0);
		})
	},
	doMove: function (iTarget, callBack)
	{
		var _this = this;
		clearInterval(this.timer)
		this.timer = setInterval(function ()
		{
			var iSpeed = (iTarget - _this.oList.position().top) / 5;
			iSpeed = iSpeed > 0 ? Math.ceil(iSpeed) : Math.floor(iSpeed);
			_this.oList.position().top == iTarget ? (clearInterval(_this.timer), callBack && callBack.apply(_this)) : _this.oList.css('top',iSpeed + _this.oList.position().top + "px")
		}, 30);
	}
};

$(function(){
	var navH = $(".gd_box").offset().top;/* 获取导航条距离顶部距离 */
	$(window).scroll(function(){
		var scroH = $(this).scrollTop();/* 获取滚动条的滑动距离 */
		if(scroH >= navH){
			$(".gd_box").css({"position":"fixed","top":"100px"});
		}else if(scroH < navH){
			$(".gd_box").css({"position":"absolute","top":$('.socll').offset().top});
		}
	});
	$('#nav li').each(function(i,item){
		if($(item).find('div').html().length <= 2){
			$(item).css('line-height','44px');
		}
	});
	new Roll("scroll_box");
	$('#nav').onePageNav();
	$('.nearby_box_close').click(function(){$('.nearby_box').css('display','none') ;});
	
	if($(window).width() < 1400){
		$('.gd_box').hide();
	}
	$(window).resize(function(){
		if($(window).width() < 1400){
			$('.gd_box').hide();
		}else{
			$('.gd_box').show();
		}
	});
	
	$('.index_top_fold_box').slideDown(1500,function(){
		$('.index_top_fold_box').append("<a href='javascript:;' class='up'></a>");									  
	});
	var t = setTimeout(index_top_fold_adUp,5000);
	$('.index_top_fold_box a.up').live('click',function(){
		clearTimeout(t);
		$('.index_top_fold_box').animate({
			height:'100px'						 
		},function(){
			$(this).find('.up').addClass('down').removeClass('up');	
		});	 
	});	
	$('.index_top_fold_box a.down').live('click',function(){
		$(this).css({
			opacity:'0'	,
			filter:'alpha(opacity=0)'
		});
		$('.index_top_fold_box').animate({
			height:'250px'
		},function(){
			$(this).find('.down').addClass('up').removeClass('down').css({opacity:'1',filter:'alpha(opacity=100)'});
		});	 
	});
	
	if($('.socll .category').size() == 0){
		$('.gd_box').remove();
	}
});
//设置延时函数
function index_top_fold_adUp(){
	$('.index_top_fold_box').animate({
		height:'100px'						 
	},1000,function(){
		$(this).find('.up').addClass('down').removeClass('up');	
	});	
}