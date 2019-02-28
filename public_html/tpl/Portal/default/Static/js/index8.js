var ary = location.href.split("&");
jQuery(".focus").slide({mainCell:".bd ul",autoPlay:true,trigger:"click",easing:"easeOutBounce",delayTime:1000});
$(document).ready(function(){
	/*鼠标移过，左右按钮显示*/
	$(".focus").hover(function(){
		$(this).find(".prev,.next").fadeTo("show",1);
	},function(){
		$(this).find(".prev,.next").hide();
	})
});
jQuery(".wonde_bottom").slide({mainCell:".bd ul",autoPage:true,effect:"topLoop",autoPlay:true,vis:2});

jQuery("#tabadcontent_10").slide({mainCell:".picList",autoPage:true,effect:"topLoop",autoPlay:true,vis:4});
jQuery("#tabadcontent_11").slide({mainCell:".picList",autoPage:true,effect:"topLoop",autoPlay:true,vis:4});
jQuery(".rencaiScroll").slide({mainCell:"ul",autoPage:true,effect:"topLoop",autoPlay:true,scroll:1,vis:4,delayTime:1000});
$('#fc_zushou').format_zushou();
$('#fc_zushou2').format_zushou();
$('#tab1').TabADS('.item');
$('#tab_2').TabADS();
$('#tab_3').TabADS();
$('#tab_4').TabADS();
$('#tab_5').TabADS();
$('#tab1').get_TG_num('.tg_chrnum');
$("#tab_ajax_02").TabADS4(window['siteUrl'],22,'live',false);
$('#fixed_menu').rmenuShow2016();

$.fn.fixedMore = function(selector){
	var t = $(this),node = $('#'+selector);
	t.hover(function(){
		node.show().animate({'right':'70px'},500);
	},function(){
		node.animate({'right':'0px'},500,function(){node.hide();});
	});
}
$('#telBaoming').fixedMore('telBaomingInner');
$('#weixinFixed').fixedMore('weixinFixedInner');
var adlinkList = $('.mmver').find('img');
adlinkList.each(function(){
	$(this).parent().append('<s class="mm"></s>');
});

/***
* 回到顶部插件 
* hujing
*20150819
***/
jQuery(function (){
var kefu_type=['0','0'];
manhuatoTop = function(option) {
	var options = {};
	options.showHeight = option.showHeight || 150;
	options.speed = option.speed || 1000;
	var gotoplocal;
	
	var top = jQuery("#iGo2Top");
	jQuery(window).scroll(function(){
		var scrolltop=jQuery(this).scrollTop();
		if(scrolltop>=options.showHeight){
			top.css({"display":"block"});
		}else{
			top.hide();
		}
	});
	jQuery(".tbtnclass").hover(function(){
		$(this).css({"textIndent":"2px"});
	},function(){
		$(this).css({"textIndent":"-999px"});
	})
	top.click(function(){
		jQuery("html,body").animate({scrollTop: 0}, options.speed);
	});
    jQuery(".tbtnclass").hover(function(){
		jQuery(this).addClass("hover");
	},function(){
		jQuery(this).removeClass("hover");
	});
	jQuery("#wx_down").hover(function(){
      jQuery("#wx_app").css({"top":jQuery(this).position().top-250,"left":jQuery(this).position().left-jQuery("#wx_app").width()}).show();},function(){jQuery("#wx_app").hide();});

  jQuery("#app_down").hover(function(){
      jQuery("#app_app").css({"top":jQuery(this).position().top-250,"left":jQuery(this).position().left-jQuery("#app_app").width()}).show();
	},function(){jQuery("#app_app").hide();});
};
manhuatoTop({
showHeight : 100,//设置滚动高度时显示
speed : 500 //返回顶部的速度以毫秒为单位
});
});