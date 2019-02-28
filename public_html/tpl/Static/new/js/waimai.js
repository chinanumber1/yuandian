/**
 * Created by tanytree on 2015/8/1.
 */
var t = n = 0, count;
$(document).ready(function(){
    count=$(".flashBox ul li").length;
    $(".flashBox ul li:not(:first-child)").hide();
    $(".flashBox ol li").click(function() {
        var i = $(this).text() - 1;
        n = i;
        if (i >= count) return;
        $(".flashBox ul li").filter(":visible").fadeOut(600).parent().children().eq(i).fadeIn(1000);
        document.getElementById("flashBox").style.background="";
        $(this).addClass("active");
        $(this).siblings().removeClass("active");
    });
    t = setInterval("show()", 4000);
    $(".flashBox").hover(function(){clearInterval(t);$(" .bnr_btn_wrap .bnr_btn").show()}, function(){t = setInterval("show()", 4000);$(".bnr_btn_wrap .bnr_btn").hide()});
    $(".bnr_btn_left").click(function() {
        shows()
    });
    $(".bnr_btn_right").click(function() {
        show()
    });
});
function show()
{
    n = n >=(count - 1) ? 0 : ++n;
    $(".flashBox ol li").eq(n).trigger('click');
}
function shows()
{
    n = n >=(count + 1) ? 0 : --n;
    $(".flashBox ol li").eq(n).trigger('click');
}


function rowlastLi(a,b){
    $(a).each(function(){
        var li=$(this).find("ul>li");
        var len=$(this).find("ul>li").length;
        var y=len/b;
        for(var i=1;i<=y;i++){
            li.eq(i*b-1).css({'margin-right':'0'}).find(".someDesc").addClass("lastRightBox");
        }
    })
}

/*弹窗操作*/
function windowClosed(){
    $(".fullBg").fadeOut();
    $(".window").slideUp();
}
$(function(){
$(".fullBg").click(function(){
    windowClosed();
});
    $(".phonefield button").click(function(){
        var par=$(this).parents(".addressformfield ");
       if( par.hasClass("phonefield")){
           $(this).hide();
           par.removeClass("phonefield");
           $(".addressformfield.phonebkfield").show();
       }
    });
    $(".phonebkfield button").click(function(){
        $(this).parents(".addressformfield").hide().prev().addClass("phonefield");
        $(".phonefield button").show();
    });

    /*$(".addressfield>input").click(function(){
        $(".address-suggestlist").show();
        return false;
    });*/
    $(".address-suggestlist li").live('click',function(){
        var aText=$(this).find(".name").text();
        $(".addressfield>input").val(aText);
        $(this).parents(".address-suggestlist").hide();
        return false;
    });

    //更多优惠券弹窗
    clickaShowWindow('.moreCoupons','.item-coupon .chooseMoreBtn');
    tab('.moreCoupons .hd li','.moreCoupons .bd .row','select');
    $(".moreCoupons .coupon-list li").live("click",function(){
       $(this).addClass("item-select").siblings().removeClass("item-select");
    });

    //评分弹窗
    clickaShowWindow('.ratingWindow','a.showRating');
    var starIcon1=$(".ratingWindow .ratingBox.mass i");
    starIcon1.bind('click',function(){
        starIcon1.removeClass('on');
        var iCount=starIcon1.index(this)+1;
        for(var i=0;i<iCount;i++){
            starIcon1.eq(i).addClass('on');
        }
    });
	var starIcon2=$(".ratingWindow .ratingBox.send i");
    starIcon2.bind('click',function(){
        starIcon2.removeClass('on');
        var iCount=starIcon2.index(this)+1;
        for(var i=0;i<iCount;i++){
            starIcon2.eq(i).addClass('on');
        }
    });
	var starIcon3=$(".ratingWindow .ratingBox.whole i");
    starIcon3.bind('click',function(){
        starIcon3.removeClass('on');
        var iCount=starIcon3.index(this)+1;
        for(var i=0;i<iCount;i++){
            starIcon3.eq(i).addClass('on');
        }
    });
});



$(function(){
    rowlastLi(".goodsList",4);
    rowlastLi(".userCenter .address",3);
    rowlastLi(".productDisplay .bd",3);
    lastLi(".hotSale");
    tab('.tabNav>li','.userWrap .bd>.row','on');
    $(".goodsList>ul>li").hover(function(){
       $(this).find(".bmbox").show();
    },function(){
        $(this).find(".bmbox").hide();
    });

    $(".needShow").hover(function(){
        $(this).find(".slideNavList").show();
    },function(){
        $(this).find(".slideNavList").hide()
    });
    $(".slideNavList ul>li").hover(function(){
        $(this).addClass("on").siblings().removeClass('on');
    },function(){
        $(this).removeClass("on")
    });

    $(".sort li").click(function(){
        if($(this).hasClass("on")){
            $(this).removeClass("on");
        }else{
            $(this).addClass("on").siblings().removeClass("on");
        }
    });

    $(".productDisplay .hd i").click(function(){
       $(this).toggleClass("on");
        $(this).parent().next().toggle();
    });

    $(".selectBox>li").hover(function(){
        $(this).find(".subSelect").stop().slideDown();
    },function(){
        $(this).find(".subSelect").stop().slideUp();
    });

    $(".storeHd .rightIcon .ic0").click(function(){
        $(this).toggleClass('on');
    });
});



function windowStepBtn(){
    var docHeight = $(document).height();
    $(".fullBg").height(docHeight).show();
    $(".windowStepBtn img").show();
    var btnX=$(".productDisplay .goodsInfo .btn").offset().left;
    var btnY=$(".productDisplay .goodsInfo .btn").offset().top;
    $(".windowStepBtn img").css({
        left:btnX,top:btnY
    });
    $("html,body").stop().animate({scrollTop:$(".productDisplay").offset().top},1000);
    $(".windowStepBtn img").on('click',function(){
        windowStepCart();
        $(this).hide();
        if ((navigator.userAgent.indexOf('MSIE') >= 0)){
            $(".windowStepCart .cartgirl").animate({
                left:"1000px"
            },8000)
        }
    });
}

function windowStepCart(){
    var t=null;
    clearTimeout(t);
    var docHeight = $(document).height();
    var windowHeight = $(window).height();
    var storeX=$(".store").offset().left;
    $(".fullBg").height(docHeight).show();
    $(".cartBg").css({'left':storeX,'height':windowHeight,'display':'block'});

    $(window).bind('scroll resize',
        function() {
            var windowHeight = $(window).height();
            $(".cartBg").css({'left':storeX,'height':windowHeight});
        });
    centerWindow(".windowStepCart");
    $(".windowStepCart").show();
    t=setTimeout(function(){
        $(".fullBg").hide();
        $(".cartBg").hide();
        $(".windowStepCart").hide();
        $("html,body").stop().animate({scrollTop:0},1000);
    },10000)

}

function listWindow(){
    var docHeight = $(document).height();
    $(".fullBg").height(docHeight).show();
    var listIconX=$(".listIcon ul").offset().left;
    var listIconY=$(".listIcon ul").offset().top-27;
    var checkBoxPartX=$(".checkBoxPart ul").offset().left;
    var checkBoxPartY=$(".checkBoxPart ul").offset().top-15;
    var tagsChoosX=$(".tagsChoose .w1210").offset().left;
    var tagsChooseY=$(".tagsChoose .w1210").offset().top-7;
    var goodsX=$(".goodsList>ul>li").eq(0).offset().left;
    var goodsY=$(".goodsList>ul>li").eq(0).offset().top;

    $(".step0").css({
        left:listIconX,top:listIconY
    });
    $(".step1").css({
        left:checkBoxPartX,top:checkBoxPartY
    });
    $(".step2").css({
        left:tagsChoosX,top:tagsChooseY
    });
    $(".step3").css({
        left:goodsX,top:goodsY
    });

    $(".step0").show();
    function showHide(a,b){
        $(a).hide();
        $(b).show();
        $("html,body").stop().animate({scrollTop:$(b).offset().top},1000);
    }
    $(".step0 .nextbtn").bind("click",function(){
        showHide('.step0','.step1');
    });
    $(".step1 .nextbtn").bind("click",function(){
        showHide('.step1','.step2');
    });
    $(".step2 .nextbtn").bind("click",function(){
        showHide('.step2','.step3');
    });
    $(".step3 .endBtn").bind("click",function(){
        $(".step3").hide();
        $(".fullBg").fadeOut();
        $("html,body").stop().animate({scrollTop:0},1000);
    });

}

function indexWindow(){
    var indexPositionX=$(".goodsList>ul>li").eq(0).offset().left;
    var indexPositionY=$(".goodsList>ul>li").eq(0).offset().top;
    var docHeight = $(document).height();
    $(".fullBg").height(docHeight).show();
    $(".windowStepIndex").show();
    $(".windowStepIndex").css({
        left:indexPositionX,top:indexPositionY
    });
    $("html,body").stop().animate({scrollTop:$(".windowStepIndex").offset().top-150},1000);

}

function xClosed(){
    $(".fullBg").fadeOut();
    $(".windowStepIndex").hide();
    $("html,body").stop().animate({scrollTop:0},1000);
}

function lastLi(a){
    $(a).find("li").last().css('borderBottom','0');
}

function tab(a,b,c){
    var len=$(a);
    len.bind("click",function(){
        var index = 0;
        $(this).addClass(c).siblings().removeClass(c);
        index = len.index(this);
        $(b).eq(index).addClass("animate").show().siblings().removeClass("animate").hide();
        return false;
    }).eq(0).trigger("click");
}


//3.点击弹窗方法
function clickaShowWindow(a, b) {
    $(b).click(function() {
        centerWindow(a);
        $(".fullBg").show();
        $(a).slideDown(300);
        return false;
    });
}
//2.将盒子方法放入这个方，方便法统一调用
function centerWindow(a) {
    center(a);
    //自适应窗口
    $(window).bind('scroll resize',
        function() {
            center(a);
        });
}
//1.居中方法，传入需要剧中的标签
function center(a) {
    var wWidth = $(window).width();
    var wHeight = $(window).height();
    var boxWidth = $(a).width();
    var boxHeight = $(a).height();
    var scrollTop = $(window).scrollTop();
    var scrollLeft = $(window).scrollLeft();
    var top = scrollTop + (wHeight - boxHeight) / 2;
    var left = scrollLeft + (wWidth - boxWidth) / 2;
    $(a).css({
        "top": top,
        "left": left
    });
}