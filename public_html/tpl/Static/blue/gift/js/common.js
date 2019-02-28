/**
 * Created by tanytree on 2016/05/17.
 */


$(function(){
    //首页第一个模块每行最后一个li里头a的右边框
    $(".indexRow1 .rowCell").each(function() {
        var li = $(this).find("ul>li");
        var len = $(this).find("ul>li").length;
        var y = len / 4;
        for (var i = 1; i <= y; i++) {
            li.eq(i * 4 - 1).find("a").css({
                'border-right': '1px solid #F9F9F9'
            });
        }
    });

//小标签添加

        $(".tagBowl .link-list .more").click(function(){
           if($(this).hasClass('on')){
               $(this).removeClass('on');
               $(".js_poolTagLink").css('height','30px');
           }else{
               $(this).addClass('on');
               $(".js_poolTagLink").css('height','auto');
           }
        });
    //排序按钮
    $(".ranking-nav ul li").on('click',function(){
        if($(this).hasClass("on")){
            $(this).removeClass("on");
        } else{
            $(this).addClass("on");
        }
    });


    $(window).scroll(function() {
        if ($(window).scrollTop() > 200) {
            $(".back").fadeIn(0);
        }
        else {
            $(".back").fadeOut(500);
        }
    });
    $(".back a").click(function() {
        $('body,html').animate({
                scrollTop: 0
            },
            500);
        return false;
    });
});


$(function(){
    tab(".indexRow1 .tabHd ul li",'.indexRow1 .special','on');
    myFun.tabFade(".pmTab");
    bigScroll();//滚动图
});
var switchClass=function(obj){
   var swObj=$(obj);
    swObj.each(function(){
        var len = $(this).find('.hd ul li');
        len.bind("click", function() {
            $(this).addClass('on').siblings().removeClass('on');
            return false
        }).eq(0).trigger("click");
    });
};
var tab=function(a,b,c){
    if(arguments.length<2){
        var tabObj = $(a);
        tabObj.each(function() {
            var prefix;
            if(typeof($(this).attr("data"))=="undefined"){
                 prefix=$(this).attr('class').toString().substring(0,4);
            }else{
                prefix=$(this).attr('data').toString();
            }
            console.log(prefix);
            var len = $(this).find('.hd ul li');
            var row = $(this).find('.row');
            var index = 0;
            var cookieName=prefix+'indexThis';
            len.bind("click", function() {
                $(this).addClass('on').siblings().removeClass('on');
                index = len.index(this);

                row.eq(index).fadeIn(500).siblings(".row").hide();

                $.cookie( cookieName ,index , { path: '/'});
                return false
            });
            var oo = $.cookie( cookieName);
            if(oo){
            len.eq(oo).trigger("click");
            }else{
                len.eq(0).trigger("click");
            }
        });
    }else{
        var len = $(a);
        len.bind("click", function() {
            var index = 0;
            $(this).addClass(c).siblings().removeClass(c);
            index = len.index(this);
            $(b).eq(index).show().siblings().hide();
            return false
        }).eq(0).trigger("click");
    }
};






//详情页面小图切换大图
$(function(){
    $(".jf-thumb li img").on("click",function(){
        /*var imgSrc = $(this).attr("src");
         var i = imgSrc.lastIndexOf("/");
        var imageName = imgSrc.slice(i+1);
        imgSrc = imgSrc.substring(0,i);
		var imgSrc_small = 'images/img400/' + imageName;
		var imgSrc_big = 'images/img800/' + imageName; */
		
		var imgSrc = $(this).attr("src");
		var imgSrc_big = $(this).data("big-src");
        $(this).parent().addClass("on").siblings().removeClass("on");
        $("#bigImg").attr({"src": imgSrc_big ,"jqimg": imgSrc_big });
    }).eq(0).trigger('click');
});
//详情页面图片放大预览效果
$(function(){
    $(".ks-imagezoom-wrap").jqueryzoom({
        xzoom: 420, //放大图的宽度(默认是 200)
        yzoom: 420, //放大图的高度(默认是 200)
        offset: 10, //离原图的距离(默认是 10)
        position: "right", //放大图的定位(默认是 "right")
        preload:1
    });
});
//详情一般点击处理
$(function(){
    $(".JSsizes .option a:not('.disable')").click(function(){
        var va=$(this).text().trim();
        $(this).addClass("on").siblings().removeClass("on");
        $("#chosenTip").show();
        $(".chosenTip").find("em").text(va);

    });
    $(".JSpay .option a:not('.disable')").click(function(){
        $(this).addClass("on").siblings().removeClass("on");
    });

    //订单页面点击处理
    $(".adressOption .adrrList li.JSaddress").click(function(){
        $(this).addClass("on").siblings().removeClass("on");
    });
    $(".payOption .time .option em").click(function(){
        $(this).addClass("on").siblings().removeClass("on");
    });
});

$(function(){
   $(".JSitem").find(".item").each(function(){
       $(this).find(".min-i-pic li img").on("click",function(){
           var imgSrc = $(this).attr("src");
           //var i = imgSrc.lastIndexOf("/");
           //var imageName = imgSrc.slice(i+1);
           //var imgSrc_small = 'images/img400/' + imageName;
           $(this).parent().addClass("on").siblings().removeClass("on");
           $(this).parent().parent().parent().find(".i-pic img").attr({"src": imgSrc});
       }).eq(0).trigger('click');
   });
   
   
   $('.JSjfBtn').click(function(){
	   
   });
});

$(function(){
	$('.plus').click(function(){
		var now_sku = parseInt($('#now_sku').val());
		var total_sku = parseInt($('.total_sku').html());
		
		if(!total_sku){
			total_sku = parseInt($('.new_total_sku').html());
		}
		sku_limit(now_sku , total_sku , '+')
	});
	
	$('.reduce').click(function(){
		var now_sku = parseInt($('#now_sku').val());
		var total_sku = parseInt($('.total_sku').html());
		
		if(!total_sku){
			total_sku = parseInt($('.new_total_sku').html());
		}
		sku_limit(now_sku , total_sku , '-');
	});
	
	$('#now_sku').keyup(function(){
		var now_sku = parseInt($(this).val());
		var total_sku = parseInt($('.total_sku').html());
		if(!total_sku){
			total_sku = parseInt($('.new_total_sku').html());
		}

		if(!now_sku){
			$(this).val(1);
		}
		
		if(now_sku >= total_sku){
			$(this).val(total_sku);
		}
		
		if(now_sku <= 1){
			$(this).val(1);
		}
	});
	
	
	$('.plus_buy').click(function(){
		var now_sku = parseInt($('#now_sku_buy').val());
		sku_limit_buy(now_sku , total_sku , '+')
	});
	
	$('.reduce_buy').click(function(){
		var now_sku = parseInt($('#now_sku_buy').val());
		sku_limit_buy(now_sku , total_sku , '-');
	});
	
	$('#now_sku_buy').keyup(function(){
		var now_sku = parseInt($(this).val());
		if(!now_sku){
			$(this).val(1);
		}
		
		if(now_sku >= total_sku){
			$(this).val(total_sku);
			now_sku = total_sku;
		}
		
		if(now_sku <= 1){
			$(this).val(1);
		}
		
		if(exchange_type == 0){
			var total_sku_price =  now_sku * payment_pure_integral;
			$('.payment_pure_integral').html(total_sku_price);
		}else if(exchange_type == 1){
			var total_payment_integral =  now_sku * payment_integral;
			var total_payment_money = now_sku * payment_money;
			$('.payment_integral').html(total_payment_integral);
			$('.payment_money').html(total_payment_money);
		}
	});
});
function sku_limit(now_sku , total_sku , operate){
		if(operate == '+'){
			if(now_sku >= total_sku){
				return;
			}
			
			now_sku++;
		}else if(operate == '-'){
			if(now_sku <= 1){
				return;
			}
			now_sku--;
		}
		 $('#now_sku').val(now_sku);
		
}


function sku_limit_buy(now_sku , total_sku , operate){
		if(operate == '+'){
			if(now_sku >= total_sku){
				return;
			}
			
			now_sku++;
		}else if(operate == '-'){
			if(now_sku <= 1){
				return;
			}
			now_sku--;
		}
		$('#now_sku_buy').val(now_sku);
		if(exchange_type == 0){
			var total_sku_price =  now_sku * payment_pure_integral;
			$('.payment_pure_integral').html(total_sku_price);
		}else if(exchange_type == 1){
			var total_payment_integral =  now_sku * payment_integral;
			var total_payment_money = now_sku * payment_money;
			$('.payment_integral').html(total_payment_integral);
			$('.payment_money').html(total_payment_money);
		}
		
}

function gift_menu(){
	var str = $.cookie('gift_menu');
	if (str == '' || str == null) return false;
	var arr = str.split(":");
	if (arr[0] != store_id) {
		$.cookie("gift_menu", '', {expires:365, path:"/"});
		return false;
	}
}

function showWindow(windowObj){
    $(".fullBg").show();
    $(windowObj).show();
    $(document.body).css("overflow","hidden");
}
//窗口关闭方法
function windowXclosed(){
    $(".fullBg").hide();
    $(".window").hide();
    $(document.body).css("overflow","auto");
}
//点击灰色背景关闭窗口表达式
(function fullBgXclosed(){
    $(".fullBg").click(function(){
        windowXclosed();
    })
})();



//搜索列表自动填充

function oSearchSuggest(searchFuc)
{
    var input = $('.topSearch .inputWrap input');
    var suggestWrap = $('#gov_search_suggest');
    var key = "";
    var init = function(){
        input.bind('keyup',sendKeyWord);
        input.bind('blur',function(){setTimeout(hideSuggest,100);})
    }
    var hideSuggest = function(){
        suggestWrap.hide();
    }
    //发送请求，根据关键字到后台查询
    var sendKeyWord = function(event){
        //键盘选择下拉项
        if(suggestWrap.css('display')=='block'&&event.keyCode == 38||event.keyCode == 40)
        {
            var current = suggestWrap.find('li.hover');
            if(event.keyCode == 38)
            {
                if(current.length>0)
                {
                    var prevLi = current.removeClass('hover').prev();
                    if(prevLi.length>0)
                    {
                        prevLi.addClass('hover');
                        input.val(prevLi.html());
                    }
                }
                else
                {
                    var last = suggestWrap.find('li:last');
                    last.addClass('hover');
                    input.val(last.html());
                }
            }
            else if(event.keyCode == 40)
            {
                if(current.length>0)
                {
                    var nextLi = current.removeClass('hover').next();
                    if(nextLi.length>0)
                    {
                        nextLi.addClass('hover');
                        input.val(nextLi.html());
                    }
                }
                else
                {
                    var first = suggestWrap.find('li:first');
                    first.addClass('hover');
                    input.val(first.html());
                }
            }
            //输入字符
        }
        else
        {
            var valText = $.trim(input.val());
            if(valText ==''||valText==key)
            {
                return;
            }
            searchFuc(valText);
            key = valText;
        }
    }
    //请求返回后，执行数据展示
    this.dataDisplay = function(data){
        if(data.length<=0)
        {
            suggestWrap.hide();
            return;
        }
        //往搜索框下拉建议显示栏中添加条目并显示
        var li;
        var tmpFrag = document.createDocumentFragment();
        suggestWrap.find('ul').html('');
        for(var i=0; i<data.length; i++)
        {
            li = document.createElement('LI');
            li.innerHTML = data[i];
            tmpFrag.appendChild(li);
        }
        suggestWrap.find('ul').append(tmpFrag);
        suggestWrap.show();
        //为下拉选项绑定鼠标事件
        suggestWrap.find('li').hover(function(){
            suggestWrap.find('li').removeClass('hover');
            $(this).addClass('hover');
        },function(){
            $(this).removeClass('hover');
        }).bind('click',function(){
            $(this).find("span").remove();
            input.val(this.innerHTML);
            suggestWrap.hide();
        });
    }
    init();
};
//实例化输入提示的JS,参数为进行查询操作时要调用的函数名
var searchSuggest = new oSearchSuggest(sendKeyWordToBack);
//这是一个模似函数，实现向后台发送ajax查询请求，并返回一个查询结果数据，传递给前台的JS,再由前台JS来展示数据。本函数由程序员进行修改实现查询的请求
//参数为一个字符串，是搜索输入框中当前的内容
function sendKeyWordToBack(keyword){
    var aData = [];
    aData.push('<span class="num_right">约100个</span>'+keyword+'返回数据1');
    aData.push('<span class="num_right">约200个</span>'+keyword+'返回数据2');
    aData.push('<span class="num_right">约100个</span>'+keyword+'返回数据3');
    aData.push('<span class="num_right">约50000个</span>'+keyword+'返回数据4');
    aData.push('<span class="num_right">约1044个</span>'+keyword+'2012是真的');
    aData.push('<span class="num_right">约100个</span>'+keyword+'2012是假的');
    aData.push('<span class="num_right">约100个</span>'+keyword+'2012是真的');
    aData.push('<span class="num_right">约100个</span>'+keyword+'2012是假的');
    //将返回的数据传递给实现搜索输入框的输入提示js类
    searchSuggest.dataDisplay(aData);
}



//图片放大查看插件
;(function($){

    $.fn.jqueryzoom = function(options){

        var settings = {
            xzoom: 200,		//zoomed width default width
            yzoom: 200,		//zoomed div default width
            offset: 10,		//zoomed div default offset
            position: "right" ,//zoomed div default position,offset position is to the right of the image
            lens:1, //zooming lens over the image,by default is 1;
            preload: 1

        };

        if(options) {
            $.extend(settings, options);
        }

        var noalt='';

        $(this).hover(function(){

            var imageLeft = $(this).offset().left;
            var imageTop = $(this).offset().top;


            var imageWidth = $(this).children('img').get(0).offsetWidth;
            var imageHeight = $(this).children('img').get(0).offsetHeight;


            noalt= $(this).children("img").attr("alt");

            var bigimage = $(this).children("img").attr("jqimg");

            $(this).children("img").attr("alt",'');

            if($("div.zoomdiv").get().length == 0){

                $(this).after("<div class='zoomdiv'><img class='bigimg' src='"+bigimage+"'/></div>");


                $(this).append("<div class='jqZoomPup'>&nbsp;</div>");

            }


            if(settings.position == "right"){

                if(imageLeft + imageWidth + settings.offset + settings.xzoom > screen.width){

                    leftpos = imageLeft  - settings.offset - settings.xzoom;

                }else{

                    leftpos = imageLeft + imageWidth + settings.offset;
                }
            }else{
                leftpos = imageLeft - settings.xzoom - settings.offset;
                if(leftpos < 0){

                    leftpos = imageLeft + imageWidth  + settings.offset;

                }

            }

            $("div.zoomdiv").css({ top: imageTop,left: leftpos });

            $("div.zoomdiv").width(settings.xzoom);

            $("div.zoomdiv").height(settings.yzoom);

            $("div.zoomdiv").show();

            if(!settings.lens){
                $(this).css('cursor','crosshair');
            }




            $(document.body).mousemove(function(e){



                mouse = new MouseEvent(e);

                /*$("div.jqZoomPup").hide();*/


                var bigwidth = $(".bigimg").get(0).offsetWidth;

                var bigheight = $(".bigimg").get(0).offsetHeight;

                var scaley ='x';

                var scalex= 'y';


                if(isNaN(scalex)|isNaN(scaley)){

                    var scalex = (bigwidth/imageWidth);

                    var scaley = (bigheight/imageHeight);




                    $("div.jqZoomPup").width(200);

                    $("div.jqZoomPup").height(200);

                    if(settings.lens){
                        $("div.jqZoomPup").css('visibility','visible');
                    }

                }



                xpos = mouse.x - $("div.jqZoomPup").width()/2 - imageLeft;

                ypos = mouse.y - $("div.jqZoomPup").height()/2 - imageTop ;

                if(settings.lens){

                    xpos = (mouse.x - $("div.jqZoomPup").width()/2 < imageLeft ) ? 0 : (mouse.x + $("div.jqZoomPup").width()/2 > imageWidth + imageLeft ) ?  (imageWidth -$("div.jqZoomPup").width() -2)  : xpos;

                    ypos = (mouse.y - $("div.jqZoomPup").height()/2 < imageTop ) ? 0 : (mouse.y + $("div.jqZoomPup").height()/2  > imageHeight + imageTop ) ?  (imageHeight - $("div.jqZoomPup").height() -2 ) : ypos;

                }


                if(settings.lens){

                    $("div.jqZoomPup").css({ top: ypos,left: xpos });

                }



                scrolly = ypos;

                $("div.zoomdiv").get(0).scrollTop = scrolly * scaley;

                scrollx = xpos;

                $("div.zoomdiv").get(0).scrollLeft = (scrollx) * scalex ;


            });
        },function(){

            $(this).children("img").attr("alt",noalt);
            $(document.body).unbind("mousemove");
            if(settings.lens){
                $("div.jqZoomPup").remove();
            }
            $("div.zoomdiv").remove();

        });

        count = 0;

        if(settings.preload){

            $('body').append("<div style='display:none;' class='jqPreload"+count+"'>搞么子……</div>");

            $(this).each(function(){

                var imagetopreload= $(this).children("img").attr("jqimg");

                var content = jQuery('div.jqPreload'+count+'').html();

                jQuery('div.jqPreload'+count+'').html(content+'<img src=\"'+imagetopreload+'\">');

            });

        }

    }

})(jQuery);

function MouseEvent(e) {
    this.x = e.pageX;
    this.y = e.pageY;
}


/* $(function(){
	function getcustomScroll(obj){
		var obj=$(".customScroll");
		var left=obj.find(".left");
		var right=obj.find(".right");
		var i=0;
		var t=null;
		var n=4;

		var li=obj.find(".scrollList ul li");
		var len=li.length;
		var w=li.outerWidth(true);
		var scrollUl=obj.find(".scrollList ul");
		scrollUl.width(w*len);
		var page_count = Math.ceil(len / n);
		var pageW=w*n;


		left.bind('click',function(){
			prevBtn();
			Scroll();
		});
		right.bind('click',function(){
			nextBtn();
			Scroll();
		});
		function nextBtn() {
			i++;
			if (i == page_count) {
				i = 0
			}
		}
		function prevBtn() {
			i--;
			if (i < 0) {
				i = page_count - 1
			}
		}
		function Scroll(){
			scrollUl.stop().animate({
					'margin-left': -pageW * i + 'px'
				},
				1000);
		}
	}
}); */




function bigScroll() {
    $(".flashBox").each(function() {
        var i = 0;
        var timer = 0;
        var prev = $(this).find(".bannerBtn a.prev");
        var next = $(this).find(".bannerBtn a.next");
        var pageI = $(this).find("ol li");
        var imgLi = $(this).find("ul li");

        function right() {
            i++;
            if (i == imgLi.length) {
                i = 0
            }
        }
        function left() {
            i--;
            if (i < 0) {
                i = imgLi.length - 1
            }
        }
        function run() {
            pageI.eq(i).addClass("active").siblings().removeClass("active");
            imgLi.eq(i).fadeIn(600).siblings().fadeOut(600).hide()
        }
        pageI.each(function(index) {
            $(this).click(function() {
                i = index;
                run()
            })
        }).eq(0).trigger("click");

        function runn() {
            right();
            run()
        }
        timer = setInterval(runn, 8000);
        $(this).hover(function() {
            clearInterval(timer);
            $(".bannerBtn a").fadeIn(600)
        }, function() {
            timer = setInterval(runn, 8000);
            $(".bannerBtn a").fadeOut(600)
        });
        prev.click(function() {
            left();
            run()
        });
        next.click(function() {
            right();
            run()
        })
    })
}


$(function(){
   var thisScroll=$(".arrowScroll ");

    thisScroll.each(function(){
        var r=0;
        var singleLi=$(this).find(".srollEachMod");
        var liLen=singleLi.length;
        var liW=singleLi.outerWidth(true);
        var scrollUl=$(this).find('.scrollSpecial');
       scrollUl.width(liW*liLen);
        var prev=$(this).find('.prev');
        var next=$(this).find('.next');
        //var li4=Math.ceil(liLen/4);
        //var li4W=liW*4;

        //if(liLen<5){
        //    next.hide();
        //    prev.hide();
        //}
        prev.bind('click',function(){
            prevBtn();
            Scroll();
        });
        next.bind('click',function(){
            nextBtn();
            Scroll();
        });
        function nextBtn() {
            r++;
            if (r == liLen) {
                r = 0
            }
        }
        function prevBtn() {
            r--;
            if (r < 0) {
                r = liLen - 1
            }
        }
        function Scroll(){
            scrollUl.stop().animate({
                    'margin-left': -liW * r + 'px'
                },
                1000);
        }


    })
});





//常用方法
var myFun = {
    rowlastLi: function(a, b) {
        $(a).each(function() {
            var li = $(this).find("ul>li");
            var len = $(this).find("ul>li").length;
            var y = len / b;
            for (var i = 1; i <= y; i++) {
                li.eq(i * b - 1).css({
                    'margin-right': '0'
                })
            }
        })
    },
    tab: function(obj) {
        var tabObj = $(obj);
        tabObj.each(function() {
            var len = $(this).find('.hd ol li');
            var row = $(this).find('.row');
            len.bind("click", function() {
                var index = 0;
                $(this).addClass('on').siblings().removeClass('on');
                index = len.index(this);
                row.eq(index).show().siblings(".row").hide();
                return false
            }).eq(0).trigger("click")
        })
    },
    tabFade: function(obj) {
        var tabObj = $(obj);
        tabObj.each(function() {
            var len = $(this).find('.hd ul li');
            var row = $(this).find('.row');
            len.bind("click", function() {
                var index = 0;
                $(this).addClass('on').siblings().removeClass('on');
                index = len.index(this);
                row.eq(index).fadeIn(1000).siblings(".row").hide();
                return false
            }).eq(0).trigger("click")
        })
    },
    tabs: function(a, b, c) {
        var len = $(a);
        len.bind("click", function() {
            var index = 0;
            $(this).addClass(c).siblings().removeClass(c);
            index = len.index(this);
            $(b).eq(index).addClass("animate").show().siblings().removeClass("animate").hide();
            return false
        }).eq(0).trigger("click")
    },
    lastLi: function(a) {
        $(a).find("li").last().css('borderBottom', '0')
    },
    lastLimr: function(a) {
        $(a).find("li").last().css('marginRight', '0')
    },
    marginTop: function(a) {
        var wHeight = $(window).height();
        var boxHeight = $(a).height();
        var top = (wHeight - boxHeight) / 2;
        $(a).css('marginTop', top)
    },
    animate: function(sum) {
        var t = $(window).scrollTop();
        var h = $(window).height();
        for (var i = 1; i < sum + 1; i++) {
            var off = $('.floor' + i).offset().top + 100;
            if (t + h > off) {
                $('.floor' + i).addClass('animate')
            }
        }
    }
};




jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};