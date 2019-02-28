$(document).ready(function() {
    //锚点定位动画
     scrollToFun();
    /*only mobile start*/
    //a代理
    jsAagentfun();
    //页面初始化
    pageInit();
    //模拟 radio checkbox  样式  注意label 的for属性 与  radio checkbox  一一对应；
    proxyInput();
    /*only mobile end*/
    if($("#pageloading").length>0){
        setTimeout(function(){
            $("#pageloading").remove();
        }, 200);
    }
    starGroupFun();//星级选择
    starShowFun();//星级显示
    proxySelectFun();//js 调用 模拟Select
    priceTypeFun();//需求详情 报价

    //省略符
    ellipsisText();
    //js_form_edit_toggle 编辑交互
    formEditToggle();
    //微信专用
    if(/wx./.test(location.href)){
        if($('#sitBottom').length>0||$('.js_bottomfixed').length>0||$('.js_topfixed').length>0){
            sitBottomFun();
        }
    }
});

//是否存在指定函数
function isExitsFunction(funcName) {
    try {
        if (typeof(eval(funcName)) == "function") {
            return true;
        }
    } catch(e) {}
    return false;
}

/*pageLoadingTpl({'text':'正在加载中','loadingPopid':'page-loading'})*/
function pageLoadingTpl(agrs){
    var html=[];
        html.push('<div class="page-loading-popUp" id="'+agrs.loadingPopid+'">');
        html.push(' <div class="gmask"></div>');
        html.push(' <div class="anim_loading2 page-loading">');
        html.push('    <div class="loading-con">'+agrs.text);
        html.push('         <span class="loading-anim"><span>...</span></span>');
        html.push('    </div>');
        html.push(' </div>');
        html.push('</div>');
    return html.join('');
}
//js_form_edit_toggle 编辑交互
function formEditToggle(){
    $('.js_form_edit_toggle .js_validate').each(function(index, el) {
        var valiP=$(this).closest('.li');
        $('.form-control',valiP).removeClass('js_validate');
        $('span.form-control',valiP).addClass('js_validate');
    });
    $('.pagewrap').off('focus','.js_form_edit_toggle .form-control');
    $('.pagewrap').on('focus','.js_form_edit_toggle .form-control',function(event){
        var pEle=$(this).closest('.li');
        $(pEle).addClass('lifocus')
    });
    $('.pagewrap').off('blur','.js_form_edit_toggle .form-control');
    $('.pagewrap').on('blur','.js_form_edit_toggle .form-control',function(event){
        var pEle=$(this).closest('.li');
        $(pEle).removeClass('lifocus')
    });
    $('.pagewrap').off('click','.js_form_edit_toggle .ico-edit');
    $('.pagewrap').on('click','.js_form_edit_toggle .ico-edit',function(event){
        var pEle=$(this).closest('.li');
        var pForm=$(this).closest('.js_form_edit_toggle');
        $('.preview',pEle).addClass('hidden');
        $('.edit',pEle).removeClass('hidden');
        $('.form-control',pEle).focus();
        $('.btn-wrap2',pForm).removeClass('hidden');

        //mobile
        if($(this).closest('.mobile_phone').length>0){
            $('.li',$(this).closest('.mobile_phone')).removeClass('hidden');
            $('.li .form-control',$(this).closest('.mobile_phone')).addClass('js_validate');
        }else{
            if($('.form-control.js_validate',pEle).length>0){
                $('.form-control',pEle).addClass('js_validate');
            }
        }
    });
    //$('.pagewrap').on('click','.js_form_edit_toggle .js_form_submit',function(event){
    //  var btnWrapEle=$(this).closest('.btn-wrap2');
    //  var pForm=$(this).closest('.js_form_edit_toggle');
    //  $('.preview',pForm).removeClass('hidden');
    //  $('.edit',pForm).addClass('hidden');
    //});
    $('.pagewrap').off('blur','.js_form_edit_toggle .edit .form-control');
    $('.pagewrap').on('blur','.js_form_edit_toggle .edit .form-control',function(event){
        var pEle=$(this).closest('.li');
        $('.preview .form-control',pEle).html($(this).val());
    });
}
//省略符
function ellipsisText(){
    $(".js_ellipsis").each(function(index, el) {
        var row=parseInt($(this).attr('ellipsis_row'));
        var eleH=$(this).height();
        var lineH=parseInt($(this).css("line-height"));
        var limtH=lineH*(row+0.1);
        if(eleH>limtH){
            $(this).addClass('text-ellipsis');
            $(this).css({'height':limtH+'px','overflow':'hidden'});
        }
    });
}
/*only mobile start*/
$(window).resize(function(){
    pageInit();
});

//页面初始化
function pageInit(){
    var win = {
        W :$(window).width(),
        H :$(window).height()
    }
    $(".main").css({"margin-top": "","margin-bottom":"","min-height":""});
    $(".footer").css({"margin-bottom":""});
    /*header bottom-bar start*/
    var headerH=  $(".header").length>0 ? $(".header").outerHeight() : 0;
    /*headerH= $('#wrapper').length==0 ? headerH : 0;*/
    var bottomBarH=  $(".bottom-bar").length>0 ? $(".bottom-bar").outerHeight(): 0;
    var footerH=$(".footer").length>0 ? $(".footer").outerHeight() : 0;
    var mainH=$(".main").height();
    var allMainH=mainH+headerH+bottomBarH+footerH;
    var fullMainH="";
    if(allMainH<win.H){
        fullMainH=win.H-headerH+bottomBarH-footerH;
    }
    if($(".footer").length>0){
        $(".main").css({"margin-top": headerH+"px","margin-bottom":""});
        $(".footer").css({"margin-bottom":bottomBarH+"px"});
        if($('#scroller').length>0){
            $(".main").css({"min-height":fullMainH+"px"});
        }
    }else{
        if($('#scroller').length>0){
            $(".main").css({"min-height":fullMainH+"px"});
        }
        $(".main").css({"margin-top": headerH+"px","margin-bottom":bottomBarH+"px"});
    }
    /*header bottom-bar end*/
    //iframeAdv();//广告自适应
    //btn-ftp1 样式设定
    btnFtp1Fun();

    if($(".pagewrap .main.bg-gray").length==1){
        $("body").addClass('bg-gray');
    }
    if($(".pagewrap .main.bg-gray2").length==1){
        $("body").addClass('bg-gray2');
    }
    if($(".pagewrap .main").attr('pagebg')){
         $("body").addClass($(".pagewrap .main").attr('pagebg'));
    }
    //导航动画
    navBarFun();
    if($('.status-list').length>0){
        if($('.header').length>0){
            var headerH=parseInt($('.header').outerHeight());
/*            if($('#wrapper').length==0){
                $('.status-list').css({'top':headerH+'px'});
            }*/
        }else{
            $('.status-list').css({'top':'0px'});
        }
    }
    //isIPHONE 控制 ios android 样式
    var isIPHONE = navigator.userAgent.toUpperCase().indexOf("IPHONE")!= -1;
    if(isIPHONE){
        $("body").addClass('ios');
    }else{
        $("body").addClass('android');
    }
}
//sitBottom 活动
function sitBottomFun(){
    //sitBottom 活动

    if($('#sitBottom').length>0){
        var sitBottomH=parseInt($('#sitBottom').outerHeight());
        if($('.sitBottomH_h').length>0){
          $('.sitBottomH_h').remove();
        }
        $('.pagewrap').after('<div class="sitBottomH_h" style="height:'+sitBottomH+'px;"></div>');
    }
    if($('.js_bottomfixed').length>0){
        var js_bottomfixedH=parseInt($('.js_bottomfixed').outerHeight());
        if($('.bottomfixed_h').length>0){
            $('.bottomfixed_h').remove();
        }
        $('.pagewrap').append('<div class="bottomfixed_h" style="height:'+js_bottomfixedH+'px;"></div>');
    }
    if($('.js_topfixed').length>0){
        if($('.header').length>0){
            var headerH=$('.header').outerHeight();
            $('.js_topfixed').css({'top':headerH+"px"});
        }
    }
    //report-prompt
    if($('.report-prompt').length>0){
        if($('.footer-m').length>0){
            $('.footer-m').after($('.report-prompt'));
        }
    }
}
//导航动画
function navBarFun(){
    var winW=$(window).width();
    var winH=$(window).height();

    //nav-bar
    $(".btn-nav-down").off('click');
    $(".btn-nav-down").on('click',function(event){
        var headerH=  $(".header").length>0 ? $(".header").outerHeight() : 0;
        var footerH=$(".footer").length>0 ? $(".footer").outerHeight() : 0;
        $('.nav-bar-wrap').removeClass('hidden')
        var navbarW=$('.nav-bar-wrap').outerWidth();
        $(".pagewrap").addClass("animate").css({'transform' : 'translate3d(-' + navbarW + 'px,0,0)'});
        $('.pagewrap').css({'width':winW+'px','height':winH+'px','overflow':'hidden'});
        // 绑定事件
        $('body').on('touchmove',function(event) {
            event.preventDefault();
        });
        $(".nav-bar-wrap").addClass("animate").css({'transform' : 'translate3d(-' + navbarW + 'px,0,0)'});
        $('.btn-nav-down').addClass('hidden');
        $('.btn-nav-up').removeClass('hidden');
        if($('#nav-bar-mask').length==0){
            $('body').append('<div id="nav-bar-mask"></div>');
            $('#nav-bar-mask').on('click',function(event){
                $(".btn-nav-up").trigger('click');
            });
        }
    })
    $(".btn-nav-up").off('click');
    $(".btn-nav-up").on('click',function(event){
        var navbarW=0;
        $('#nav-bar-mask').remove();
        $(".pagewrap").removeClass("animate");
        $(".pagewrap").addClass("animate").css({'transform' : ''});
        $(".nav-bar-wrap").addClass("animate").css({'transform' : ''});
        $(".pagewrap").css({'width':'','height':'','overflow':''});
        $('body').off('touchmove');
        $('.btn-nav-up').addClass('hidden');
        $('.btn-nav-down').removeClass('hidden');
        $('.nav-bar-wrap').addClass('hidden')
    })
    //生意机会列表定位
    $('.nav-bar-wrap .nav-bar a').on('click',function(){
        delCookiePath_g('tradeListSitInfo');
    });

    //回到顶部
    if($('.btn-go-top').length>0){
        $('.pagewrap').append($('.btn-go-top'));
    }
    $('.pagewrap').on('click','.btn-go-top',function(event) {
        console.log('f');
        if($('#scroller').length>0){
            $('#scroller').css({'transform': 'translate(0px, -'+$('#pullDown').outerHeight()+'px) scale(1) translateZ(0px)'});
            $(this).addClass('hidden');
        }else{
            $("html,body").stop().animate({scrollTop: 0}, 300);
        }
    });
    $(window).on('scroll',function(event) {
        var winH=$(window).height();
        var curT=$(this).scrollTop();
        var goTopLmit=parseInt(winH/2);
        if(curT>goTopLmit){
            $('.btn-go-top').removeClass('hidden');
        }else{
            $('.btn-go-top').addClass('hidden');
        }
    });

}
//广告自适应
function iframeAdv(){
    setTimeout(function(){

        /*外部广告*/
        $(".js-page-adv").each(function(index, el) {
            var oriframeId=$("iframe",$(this)).attr("id");
            var orImg =$("#"+oriframeId).contents().find("img") ;
            var oriframeW=$("#"+oriframeId).width();
            var oriframeH=$("#"+oriframeId).height();
            var advW=$(this).width();
            var advH=advW*oriframeH/oriframeW;
            $("iframe",$(this)).width(advW);
            $(orImg).width(advW);
            $("iframe",$(this)).height(advH);
            $(orImg).height(advH);

        });
    },200);
}
//a代理
var jsAagentfun=function(){
    $(".js_agent").each(function(index, el) {
        var agenttag=$(this).attr("agenttag");
         $(".pagewrap").on('click', ".js_agent "+agenttag, function(event) {
            if($(event.target)[0].tagName!="A"&&$(event.target).parent()[0].tagName!="A"&&$(event.target)[0].tagName!="INPUT"&&$(event.target)[0].tagName!="LABEL"){
                var agentUrl=$(this).attr("agenturl");
                var  agentTarget=$(this).attr("agenttarget");
                if(agentUrl!=""&&!agentUrl!=undefined){
                    if(agentTarget=="_blank"){
                        window.open(agentUrl);
                    }else{
                        location.href=agentUrl;
                    }
                }
            }
         });
    });

}
//animFun animated css3动画调用
/*
    animCss3Fun({
      "animObj":".animationsbox",//动画对象 class
      "animOption":"bounceInLeft",//动画方式
        "CallbackFun":function(animArgs){
            if(args.animCallbackFun){
                args.animCallbackFun(args);
            }
        }
    });
*/
function animCss3Fun(args) {
    $(args.animObj).removeClass("hidden").addClass(args.animOption+" animated").one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',function(){
        $(this).removeClass(args.animOption+" animated block");
        if(args.CallbackFun){
            args.CallbackFun(args);
        }
    });
}

function proxyInput(){
    $('.proxyinput [type="radio"]').each(function(index, el) {
        if($(this).prop("checked")){
            var proxyinput=$(this).closest('.proxyinput');
            var radioArr=$(this).closest('.proxyinput_group');
            $('.proxyinput',radioArr).removeClass('checked');
            $(proxyinput).addClass('checked');
        }

    });
    $('.proxyinput [type="radio"]').off('click');
    $('.proxyinput [type="radio"]').on('click', function(event) {
        var proxyinput=$(this).closest('.proxyinput');
        var radioArr=$(this).closest('.proxyinput_group');
        var checkedcancel=$(this).hasClass('checkedcancel');
        if(!checkedcancel){
            if($(radioArr).hasClass('radiocancel')){
                //可取消radio radiocancel
                if($(proxyinput).hasClass('checked')){
                    $('.proxyinput',radioArr).removeClass('checked');
                    $(this).prop('checked',false);
                }else{
                    $('.proxyinput',radioArr).removeClass('checked');
                    $(proxyinput).addClass('checked');
                }
            }else{
                //普通radio
                $('.proxyinput',radioArr).removeClass('checked');
                $(proxyinput).addClass('checked');
				
				if(!proxyinput.hasClass('other')){
                    if(bfb<100){
                        publish_demand_next();
                    }
					
				}
            }
        }

    });
    $('.proxyinput [type="checkbox"]').each(function(index, el) {
        if($(this).prop("checked")){
            var proxyinput=$(this).closest('.proxyinput');
            $(proxyinput).addClass('checked');
        }

    });
    /*.proxyinput_group 可以设置 maxlength="3"*/
    $('.proxyinput [type="checkbox"]').off('click');
    $('.proxyinput [type="checkbox"]').on('click', function(event) {
        var checkboxArr=$(this).closest('.proxyinput_group');
        var proxyinput=$(this).closest('.proxyinput');
        var maxselectcount=$(checkboxArr).attr('maxselectcount');

        if($(this).prop("checked")){
            if(maxselectcount){
                maxselectcount=parseInt(maxselectcount);
                var checkCount=$('.proxyinput [type="checkbox"]:checked',checkboxArr).length;
                if(checkCount>maxselectcount){
                    $(this).prop("checked",false);
                    //小提示层;
                    validatePop({
                        "popconMsg":'最多选择'+maxselectcount+'个'
                    });
                }else{
                    $(proxyinput).addClass('checked');
                }
            }else{
                $(proxyinput).addClass('checked');
            }
        }else{
            $(proxyinput).removeClass('checked');
        }
    });

}
/*模拟 radio checkbox  样式 end*/
/*proxySelect 模拟Select start*/
/*
//html 代码
<div class="proxy_select"><i></i>
    <input class="proxy-txt form-control control-sm bor-c-green w-100" placeholder="职位"  type="text" readonly value="">
    <input class="proxy-val" placeholder=""  type="hidden"  value="">
    <ul class="option_group">
        <li val="1">公司1</li>
        <li val="2">公司2</li>
        <li val="3">公司3</li>
        <li val="4">公司4</li>
        <li val="5">公司5</li>
        <li val="6">公司6</li>
        <li val="7">公司7</li>
        <li val="8">公司8</li>
        <li val="9">公司9</li>
        <li val="10">公司10</li>
        <li val="11">公司11</li>
        <li val="12">公司12</li>
    </ul>
</div>
proxySelectFun();//js 调用 模拟Select
*/

function proxySelectFun(){
    function Selhtml(selOjb){
        var options=$('select option',selOjb);
        var placeholder= $(options[0]).attr("value")=="" ? $(options[0]).text() : "";
        var selectedTxt=$('select option:selected',selOjb).text();
        if(placeholder==selectedTxt){
            selectedTxt="";
        }
        var selectedVal=$('select option:selected',selOjb).val();
        var html = [];
        html.push('');
        html.push('<input class="proxy-txt form-control" placeholder="'+placeholder+'"  type="text" readonly value="'+selectedTxt+'">');
        html.push('<input class="proxy-val" type="hidden"  value="'+selectedVal+'">');
        html.push('<ul class="option_group">');
        for(var i=0; i<options.length; i++){
            html.push('    <li val="'+$(options[i]).val()+'">'+$(options[i]).text()+'</li>');
        }
        html.push('</ul>');
        return html.join('');
    }
    var zIndex=1000;
    $('.proxy_select').each(function(index, el) {
        var proxySelect=$(this);
        $(proxySelect).css({"z-index":zIndex});
        zIndex--;
        if($(".proxy-txt",proxySelect).length==0){
            $(".proxy_sel_hide",proxySelect).before(Selhtml(proxySelect));
        }
        var proxyTxtObj=$(".proxy-txt",proxySelect);
        var proxyValObj=$(".proxy-val",proxySelect);
        //样式
        $(".option_group",proxySelect).css({
            "border-color":$(proxyTxtObj).css("border-color"),
            "width":parseFloat(proxyTxtObj.css("width"))
        })
    });
    $("body").off('click', '.proxy_select .proxy-txt');
    $("body").on('click', '.proxy_select .proxy-txt', function(event) {
        var proxySelect=$(this).closest('.proxy_select');
        $(proxySelect).removeClass("proxy-un");
        $(".proxy-un .option_group").css({"display":"none"});
        var proxyTxtObj=$(".proxy-txt",proxySelect);
        var proxyValObj=$(".proxy-val",proxySelect);
        var aniTime=1;
        //默认值
        var curProxyVal =$(proxyValObj).val();
        if(curProxyVal!=""){
            $(".option_group li",proxySelect).removeClass("cur");
            $('.option_group li[val='+curProxyVal+']',proxySelect).addClass("cur");
        }
        //slideToggle
        $(".option_group",proxySelect).slideToggle(aniTime);
        $("body").off("click",".proxy_select .option_group li");
        $("body").on("click",".proxy_select .option_group li",function(event){
            var proxyTxt=$(this).text();
            var proxyVal=$(this).attr("val");
            $(proxyTxtObj).val(proxyTxt);
            $(proxyValObj).val(proxyVal);
            var curselOjb=$(this).closest('.proxy_select');
            $('select',curselOjb).val(proxyVal);
            $(".option_group",curselOjb).slideUp(aniTime);

            //onchange 回调
            var onchangeCall=$('select',curselOjb).attr('onchange');
            if(onchangeCall){
                onchangeCall=eval(onchangeCall);
            }
        });
    });
    $("body").off('blur','.proxy_select .proxy-txt');
    $("body").on('blur','.proxy_select .proxy-txt', function(event) {
        var proxySelect=$(this).closest('.proxy_select');
        $(proxySelect).addClass("proxy-un");
    });
    $("body").click(function(event){
        var proxy_select=$(event.target).closest('.proxy_select').length;
        if(proxy_select==0){
           $(".proxy-un .option_group").css({"display":"none"});
        }
    })
}

/*小提示层 validatePop start*/
/*
    //小提示层;
    validatePop({
        "eventEle":"#a1",//点击事件元素（不定义：立即弹出）
        "popconMsg":"成功",//Msg
        "popCallbackFun":function(args){
            //自动关闭时的操作
            console.log(args);
        }
    });
*/
var validatePop=function(args){
    var popup=function(args){
        if($(".validatePop").length>0){
            $(".validatePop").remove();
        }
        var validatePop='<div class="validatePop">'+args.popconMsg+'</div>';
        $("body").append(validatePop);
        var validatePopW=$(".validatePop").width()+parseFloat($('.validatePop').css('padding-top'))+parseFloat($('.validatePop').css('padding-bottom'));
        var validatePopH=$(".validatePop").height();
        $(".validatePop").css({"opacity": 0,"left":"50%","top":"50%","margin": "-"+(validatePopH/2)+"px 0 0 -"+(validatePopW/2)+"px"});
        $(".validatePop").animate({"opacity": 1},350,function() {
            setTimeout(function(){
                if($(".validatePop").length>0){
                    $(".validatePop").fadeOut(100, function() {
                        if(args.popCallbackFun){
                            args.popCallbackFun(args);
                        }
                        $(this).remove();

                    });
                }
            },1000);
        });
    }
    if(args.eventEle){
        $(args.eventEle).on("click",function(){
            args.eventEle=$(this);
            popup(args);
        });
    }else{
        popup(args);
    }
}
/*小提示层 validatePop end*/

/*popsel start*/
var popSelectInit=function(args){
    $(args).each(function(index, el) {
        popSelectSpanSet($(this));
    });
}
//set span text
var popSelectSpanSet=function(args){
    var selectPop=$(args);
    var popText=$("input.js_pop_text",selectPop);
    var popTextVal=$(popText).val();
    var focusClass=$(selectPop).attr("focusClass");
    if(popTextVal!=""){
        $(popText).attr("type","hidden");
        if($("span.js_pop_text",selectPop).length>0){
            $("span.js_pop_text",selectPop).remove();
        }
        if(focusClass){
            $(selectPop).addClass(focusClass);
        }
        $(selectPop).append(' <span class="form-control js_pop_text">'+popTextVal+'</span>');
    }else{
        $(popText).attr("type","text");
        if($("span.js_pop_text",selectPop).length>0){
            $("span.js_pop_text",selectPop).remove();
            if(focusClass){
                $(selectPop).removeClass(focusClass);
            }
        }
    }
}
//层关闭
var popUpCloseFun=function(args){
    //关闭
    $(".pagewrap").off("click",args+' .close,'+args+' #gmask');
    $(".pagewrap").on("click",args+' .close,'+args+' #gmask',function(event) {
        var popUpEle=$(this).closest(args);
        $(popUpEle).remove();
        $(".pagewrap").css({"width":"","height":"","overflow":""});//mobile 页面100% cancel
    });
    $(".pagewrap").off("click",args+" .pop_action .btn");
    $(".pagewrap").on("click",args+" .pop_action .btn",function(event){
        var popUpEle=$(this).closest(args);
        var actionColse=$(this).attr("actionColse");
        switch(actionColse){
            case "false":
            break;
            case "true":
                $(".close",popUpEle).trigger("click");
            break;
            default:
                $(".close",popUpEle).trigger("click");
            ;
        }
    });
}
/*popsel end*/
function starGroupFun(){
    $(".pagewrap").on('click', '.js_star_group .ico', function(event) {
        var starIndex=$(this).index()+1;
        var groupEle=$(this).closest(".star-group");
        $('.ico',groupEle).removeClass('star');
        $('.ico:lt('+starIndex+')',groupEle).addClass("star");
        var groupVal=$(".star",groupEle).length;
        $(".js_group_val",groupEle).val(groupVal);
        //是否好评
        if(starIndex>2){
            $(groupEle).addClass('good');
        }else{
            $(groupEle).removeClass('good');
        }
    });
}
//星级显示
function starShowFun(){
    $('.js_star_show').each(function(){
        var starVal=$(this).attr('star_val');
        if(starVal!=undefined&&starVal.length!=0){
            starVal=parseFloat(starVal);
            var starInt=parseInt(starVal/1);
            var starFloat=starVal%1*100;
            $('.ico',$(this)).removeClass('star');
            $('.ico:lt('+starInt+')',$(this)).addClass('star');
            if(starFloat>1){
                if(starFloat<20){
                    starFloat=20;
                }
                if(starFloat>80){
                    starFloat=80;
                }
                $('.ico:eq('+starInt+')',$(this)).html('<span style="width:'+starFloat+'%;"></span>');
            }
            //是否好评
            if(starVal>2){
                $(this).addClass('good');
            }else{
                $(this).removeClass('good');
            }
        }
    });
}

/*only mobile end*/

//锚点定位动画
function scrollToFun(agrs) {
    $("body").on('click','.js_goto',function(event) {
        var gotodata=$(this).attr("gotodata");
        if($('.header').length>0){
            var headerH=$('.header').outerHeight();
            $("html,body").stop().animate({scrollTop: $(gotodata).offset().top-headerH}, 300);
        }else{
            $("html,body").stop().animate({scrollTop: $(gotodata).offset().top}, 300);
        }
    });
}

// 弹出层
var popFun= function(){
    this.animateTime=350;
    this.popDataId=null;
}
popFun.prototype={
    init:function(args){
        var TfThis=this;
        if(args.popDataId!=undefined){
            this.popDataId=args.popDataId;
        }else{
            this.popDataId=args.popId;
        }

        if(args.eventEle!=undefined){
            $(".pagewrap").on('click',args.eventEle,((function(TfThis) {
                return function(event) {
                    event.preventDefault();
                    //关闭 所有层
                    if($('.popUp').length>0){
                        $('.popUp').each(function(){
                           $(this).remove();
                        })
                    }
                    args.eventEle=$(this);
                    var curPop=null;
                    if(args.creatType!=undefined){
                        if(args.creatType[0]==1){
                            $("body").append(TfThis.pops1(args));
                            curPop=$("#"+TfThis.popDataId);
                            //兼容  单数据源 对 1个模板1个位置
                            if(args.popTplId!=undefined&&args.data!=undefined){
                                //兼容  单数据源 对 1个模板1个位置
                                multipleTpl({
                                    "data": args.data,
                                    "sourcetpl":args.popTplId,//1个模板
                                    "insertsit": "#"+TfThis.popDataId+" .pops1_con",//1个位置
                                    "insertmethod": "html"
                                });
                            }
                            TfThis.popStyleFun(args,curPop);
                            if(args.popCallbackFun){
                                args.popCallbackFun(args,TfThis,$(this));
                            }

                        }
                    }else{
                        var curPop=null;
                        if(TfThis.popDataId!=undefined){
                            curPop=$("#"+TfThis.popDataId);
                        }else{
                            curPop=$($(this).attr("pop-data"));
                        }
                        if(curPop.length>0){
                            TfThis.popStyleFun(args,curPop);
                            if(args.popCallbackFun){
                                args.popCallbackFun(args,TfThis,$(this));
                            }
                        }
                    }
                }
            })(this)));
        }
        if(args.showPop!=undefined){
            //关闭 所有层
            if($('.popUp').length>0){
                $('.popUp').each(function(){
                    $(this).remove();
                })
            }
            var curPop=null;
            switch(args.showPop){
                case true:
                    curPop=$("#"+this.popDataId);

                    break;
                default:

                    curPop=$(args.showPop);
            }

            if(args.creatType!=undefined){
                if(args.creatType[0]==1){
                    $("body").append(TfThis.pops1(args));
                    curPop=$("#"+this.popDataId);
                    //兼容  单数据源 对 1个模板1个位置
                    if(args.popTplId!=undefined&&args.data!=undefined){
                        //兼容  单数据源 对 1个模板1个位置
                        multipleTpl({
                            "data": args.data,
                            "sourcetpl":args.popTplId,//1个模板
                            "insertsit": "#"+this.popDataId+" .pops1_con",//1个位置
                            "insertmethod": "html"
                        });
                    }
                    TfThis.popStyleFun(args,curPop);
                    if(args.popCallbackFun){
                        args.popCallbackFun(args,TfThis);
                    }

                }
            }else{
                if(curPop.length>0){
                    this.popStyleFun(args,curPop);
                    if(args.popCallbackFun){
                        args.popCallbackFun(args,TfThis,$(this));
                    }
                }

            }
        }
    },
    popStyleFun:function(args,curPop){
        var animateTime=this.animateTime;
            var curPop=curPop;
            curPop.css({"display":"block"});
            var popWH={
                W:$(".pop",curPop).width(),
                H:$(".pop",curPop).height()
            }
            var popStyleArr=args.popStyle.split(' ');
            switch (popStyleArr[0]){
                case "slideInUp":
                    $(".pop",curPop).css({"opacity": 1,"left":"0","bottom":"0"});
                    animCss3Fun({
                        "animObj":".slideInUp_pop",//动画对象 class
                        "animOption":"slideInUp",
                        "CallbackFun":function(animArgs){
                            animateEndFun();
                        }
                    });
                  break;
                case "fade":
                    $(".pop",curPop).css({"opacity": 0,"left":"50%","top":"50%","margin": "-"+(popWH.H/2)+"px 0 0 -"+(popWH.W/2)+"px"});
                    $(".pop",curPop).animate({
                        "opacity": 1
                    },animateTime, function() {
                         animateEndFun();
                    });
                  break;
                default:
                    $(".pop",curPop).css({"opacity": 1,"left":"50%","top":"50%","margin": "-"+(popWH.H/2)+"px 0 0 -"+(popWH.W/2)+"px"});
                    $(".pop",curPop).animate({
                        "opacity": 1
                    },animateTime, function() {
                         animateEndFun();
                    });
            }
            function animateEndFun(){
                $("body").addClass("popbody");
                // 绑定事件
/*                $('body').on('touchmove',function(event) {
                    event.preventDefault();
                });*/
                /*close*/

                if(args.gmaskClose){
                    $(".close,#gmask",curPop).on('click',function(event) {
                        $(".pop",curPop).animate({
                            "opacity": 0
                            },
                            0, function() {
                            $("body").removeClass("popbody");
                            /*$('body').off('touchmove');*/
                            if(args.creatType!=undefined){
                                if(args.creatType[0]==1){
                                    curPop.remove();
                                }
                            }else{
                                curPop.css({"display":"none"});
                            }
                        });
                    });
                }else{
                    $(".close",curPop).on('click',function(event) {
                        $(".pop",curPop).animate({
                            "opacity": 0
                            },
                            0, function() {
                            $("body").removeClass("popbody");
                            /*$('body').off('touchmove');*/
                            if(args.creatType!=undefined){
                                if(args.creatType[0]==1){
                                    curPop.remove();
                                }
                            }else{
                                curPop.css({"display":"none"});
                            }
                        });
                    });

                }

                //自动关闭
                if(args.closeAuto){
                    setTimeout(function(){
                        if(curPop.length>0){
                            $(".close",curPop).trigger("click");
                        }
                    },2000);
                }
                //成功自动关闭
                if(args.creatType!=undefined){
                    if(args.creatType[0]==1){
                        if(args.creatType[1].conMsg[0]==1){
                            setTimeout(function(){
                                if(curPop.length>0){
                                    $(".close",curPop).trigger("click");
                                }
                            },2000);
                        }
                    }
                }
                $(".pop_action a,.pop_action input",curPop).on('click', function(event) {
                    var actionColse=$(this).attr("actionColse");

                    switch(actionColse){
                        case "false":

                        break;
                        case "true":

                            $(".close",curPop).trigger("click");
                        break;
                        default:

                            $(".close",curPop).trigger("click");
                        ;
                    }
                });
            }
    },
    pops1:function(args){
        var curDomArgs=args.creatType[1];

        var pops1html = [];
        pops1html.push('');
        pops1html.push('<div id="'+this.popDataId+'" class="popUp"  >');
        pops1html.push('    <div id="gmask"></div>');
    var popStyleClass='';
    if(args.popStyle&&args.popStyle!='fade'){
        var popStyleArr=args.popStyle.split(' ');
        for(var i=0; i<popStyleArr.length; i++){
            if(i==0){
                popStyleClass=popStyleArr[i]+'_pop ';
            }else{
                popStyleClass=popStyleClass+popStyleArr[i];
            }
        }

    }
    if(curDomArgs.popSize!=undefined){
        pops1html.push('    <div class="pop '+curDomArgs.popSize+' '+popStyleClass+' pops1">');
    }else{
        if(args.popStyle&&args.popStyle!='fade'){
            pops1html.push('    <div class="pop '+popStyleClass+' pops1">');
        }else{
            pops1html.push('    <div class="pop mid pops1">');
        }
    }

    if(curDomArgs.headTitle!=undefined){
        pops1html.push('        <span class="close"></span>');
    }else{
        pops1html.push('        <span class="close close2"></span>');
    }

    if(curDomArgs.headTitle!=undefined){
        pops1html.push('        <div class="pop_head">');
    if(curDomArgs.headRight!=undefined){
        pops1html.push('            <div class="poph_right">'+curDomArgs.headRight+'</div>');
    }
        pops1html.push('            <h3>'+curDomArgs.headTitle+'</h3>');
    if(curDomArgs.headRight!=undefined){
        pops1html.push('            <div class="sub">'+curDomArgs.headTitleSub+'</div>');
    }
        pops1html.push('        </div>');
    }
        pops1html.push('        <div class="pop_body">');
    if(curDomArgs.headTitle==undefined){
        pops1html.push('        <div class="pops1_con no-head">');
    }else{
        pops1html.push('            <div class="pops1_con">');
    }
    if(curDomArgs.conMsg!=undefined){
        switch(curDomArgs.conMsg[0]){
            case 0:
            pops1html.push(curDomArgs.conMsg[1]);
            break;
            case 1:
            pops1html.push('                <div class="pop_tips_wrap"><div class="pop_tips_info"><i class="ico ico-tips-succ"></i>'+curDomArgs.conMsg[1]+'</div></div>');
            break;
            case 2:
            pops1html.push('                <div class="pop_tips_wrap"><div class="pop_tips_info" ><i class="ico ico-tips-error"></i>'+curDomArgs.conMsg[1]+'</div></div>');
            break;
            case 3:
            pops1html.push('                <div class="pop_tips_wrap"><div class="pop_tips_info"><i class="ico ico-tips-warning"></i>'+curDomArgs.conMsg[1]+'</div></div>');
            break;
        }
    }
        pops1html.push('            </div>');
    if(curDomArgs.popAction!=undefined){
        pops1html.push('            <div class="pop_action">');
        for(var i=0;i<curDomArgs.popAction.length;i++){
            var curAction=curDomArgs.popAction[i];
        pops1html.push('                <a  ');
        if(curAction.actionClass){
        pops1html.push('class="'+curAction.actionClass+'"');
        }else{
            switch(i){
                case 0:
        pops1html.push('class="btn btn-orange"');
                break;
                case 1:
        pops1html.push('class="btn btn-green"');
                break;
                default:
        pops1html.push('class="btn btn-orange"');

            }
        }
        if(curAction.actionHref){
        pops1html.push('href="'+curAction.actionHref+'"');
        }else{
        pops1html.push('href="javascript:;"');
        }
        if(curAction.actionId!=undefined){
        pops1html.push('id="'+curAction.actionId+'"');
        }
        if(curAction.actionColse!=undefined){
        pops1html.push('actionColse="'+curAction.actionColse+'"');
        }
        if(curAction.onclick!=undefined){
            pops1html.push('onclick="'+curAction.onclick+'"');
        }
        pops1html.push('>'+curAction.actionTxt+'</a>');
        }
        pops1html.push('            </div>');
    }
        pops1html.push('        </div>');
        pops1html.push('    </div> ');
        pops1html.push('</div>');
        return pops1html.join('');
    }

}
//普通弹出层
/*

<!-- 普通模板 start -->
<div id="normalbox" class="js_hidetpl_box">
    普通内容Tpl{{title}}

    <!-- 按钮区 a,input 标签 默认自带关闭该层功能 如果设置 actionColse="false" 则不关闭该层 start-->
    <div class="pop_action">
        <a href="javascript:;" class="btn btn-green2" actionColse="false" >提交</a>
        <a href="javascript:;" class="btn btn-gray"  >取消</a>
        <a class="link" href="javascript:;">修改信息</a>
    </div>
    <!-- 按钮区 end-->
</div>
<!-- 普通模板 end -->
<a href="javascript:;"   class="js_popnormal">普通</a>
<script type="text/javascript">
var data1 = {title: "My New Posffsst", body: "This is my first post!"};
popnormal({
    "data":data1,//使用 handlebars 带数据的内容模板id  的 数据  //可选项
    "popTplId":"#normalbox",//内容模板id
    //"popconMsg":"popconMsg普通内容",//可选项
    "eventEle":".js_popnormal",//点击事件元素（不定义：立即弹出）
    "popId":"popnormal",//弹出层id 默认为popnormal,（可自定义）
    "headTitle":"普通标题",//普通标题
    "popSize":"",//控制大小样式 //可选项
    //"popAction":[{"actionTxt":"提  交","actionId":"js_report_submit","actionClass":"btn btn-info","actionColse":false},{"actionTxt":"取  消","actionColse":true},{"actionTxt":"修改信息"}],//按钮区
    "popCallbackFun":function(args,TfThis){//popCallbackFun 可选项
        //console.log(args);//层的相关参数
        //var curpopid = "#" + args.popId;
        //$(curpopid+" .close").trigger('click');//关闭该层
        //$(curpopid+" .close").on('click', function(event) {
            //关闭层做的操作
        //});
    }

});
//$("#popnormal .close").trigger("click");//关闭层
//$("#popnormal .close").on('click', function(event) {
//    //关闭层做的操作
//});
</script>
*/
var popnormal=function(args){
    var popTpl;

    if(args.popconMsg!=undefined){
        if(args.popAction){
            popTpl='<div class="pop_tips_msg">'+args.popconMsg+'</div>';
        }else{
            popTpl='<div class="pop_tips_msg_noaction">'+args.popconMsg+'</div>';
        }
    }
    if(args.popTplId!=undefined){
        popTpl=$(args.popTplId).html();
        var data=args.data;
    }
    if(args.popconTpl!=undefined){
        popTpl=args.popconTpl;
    }
    var popDataId;
    if(args.popId==undefined){
        popDataId="popnormal";
    }else{
        popDataId=args.popId;
    }
    var popCallbackFun;
    if(args.popCallbackFun){
        popCallbackFun=args.popCallbackFun;
    }
    var popAction;
    if(args.popAction){
        popAction=args.popAction;
    }
    var popSize;
    if(args.popSize){
        popSize=args.popSize;
    }
    var closeAuto;
    if(args.closeAuto){
        closeAuto=args.closeAuto;
    }
    var popStyle="fade";
    if(args.popStyle){
        popStyle=args.popStyle;
    }

    var gmaskClose=true;
    if(args.gmaskClose!==undefined){
        gmaskClose=args.gmaskClose;
    }
    if(args.eventEle==undefined){
        new popFun().init({
            "popTplId":args.popTplId,
            "data":data,
            "popStyle":popStyle,
            "popDataId":popDataId,
            "popId":popDataId,
            "creatType":[1,{
                "popSize":popSize,
                "headTitle":args.headTitle,
                "conMsg":[0,popTpl],
                "popAction":popAction
            }],
            "showPop":true,
            "closeAuto":closeAuto,
            "gmaskClose":gmaskClose,
            "popCallbackFun":popCallbackFun
        });
    }else{
        new popFun().init({
            "popTplId":args.popTplId,
            "data":data,
            "eventEle":args.eventEle,
            "popStyle":popStyle,
            "popDataId":popDataId,
            "popId":popDataId,
            "creatType":[1,{
                "popSize":popSize,
                "headTitle":args.headTitle,
                "conMsg":[0,popTpl],
                "popAction":popAction
            }],
            "closeAuto":closeAuto,
            "gmaskClose":gmaskClose,
            "popCallbackFun":popCallbackFun
        });
    }

}
//弹出层成功
/*
<!-- 几秒后自动关闭该层 -->
<a href="javascript:;"   class="js_popsucc">成功</a>
<script type="text/javascript">
popsucc({
    "popconMsg":"成功",//成功Msg
    "eventEle":".js_popsucc",//点击事件元素（不定义：立即弹出）
    "popId":"popsucc",//弹出层id 默认为ppopsucc,（可自定义）
    "headTitle":"成功",//成功标题
    "popCallbackFun":function(args,TfThis){//popCallbackFun 可选项
        //console.log(args);//层的相关参数
        //var curpopid = "#" + args.popId;
        //$(curpopid+" .close").trigger('click');//关闭该层
        //$(curpopid+" .close").on('click', function(event) {
            //关闭层做的操作
        //});
    }
});
//$("#popsucc .close").trigger("click");//关闭层
//$("#popsucc .close").on('click', function(event) {
//    //关闭层做的操作
//});
</script>
*/
var popsucc=function(args){
    var popTpl=args.popconMsg;
    var popDataId;
    if(args.popId==undefined){
        popDataId="popsucc";
    }else{
        popDataId=args.popId;
    }
    var popCallbackFun;
    if(args.popCallbackFun){
        popCallbackFun=args.popCallbackFun;
    }
    var closeAuto;
    if(args.closeAuto){
        closeAuto=args.closeAuto;
    }
    var popSize='';
    if(args.popSize){
        popSize=args.popSize;
    }
    var gmaskClose=true;
    if(args.gmaskClose!==undefined){
        gmaskClose=args.gmaskClose;
    }
    if(args.eventEle==undefined){
        new popFun().init({
            "popStyle":"fade",
            "popDataId":popDataId,
            "popId":popDataId,
            "creatType":[1,{
                "popSize":"mid popsucc "+popSize,
                "headTitle":args.headTitle,
                "conMsg":[1,popTpl]
            }],
            "showPop":true,
            "closeAuto":closeAuto,
            "gmaskClose":gmaskClose,
            "popCallbackFun":popCallbackFun
        });
    }else{
        new popFun().init({
            "eventEle":args.eventEle,
            "popStyle":"fade",
            "popDataId":popDataId,
            "popId":popDataId,
            "creatType":[1,{
                "popSize":"mid popsucc "+popSize,
                "headTitle":args.headTitle,
                "conMsg":[1,popTpl]
            }],
            "closeAuto":closeAuto,
            "gmaskClose":gmaskClose,
            "popCallbackFun":popCallbackFun
        });
    }

}
//弹出层失败
/*
<!-- 点击确认按钮关闭该层 -->
<a href="javascript:;"   class="js_poperror">失败</a>
<script type="text/javascript">
poperror({
    "popconMsg":"失败",
    "eventEle":".js_poperror",//点击事件元素（不定义：立即弹出）
    "popId":"poperror",//弹出层id 默认为poperror,（可自定义）
    "headTitle":"失败",//失败标题
    "popCallbackFun":function(args,TfThis){//popCallbackFun 可选项
        //console.log(args);//层的相关参数
        //var curpopid = "#" + args.popId;
        //$(curpopid+" .close").trigger('click');//关闭该层
        //$(curpopid+" .close").on('click', function(event) {
            //关闭层做的操作
        //});
    }
});
//$("#poperror .close").trigger("click");//关闭层
//$("#poperror .close").on('click', function(event) {
//    //关闭层做的操作
//});
</script>
*/
var poperror=function(args){
    var popTpl=args.popconMsg;
    var popDataId;
    if(args.popId==undefined){
        popDataId="poperror";
    }else{
        popDataId=args.popId;
    }
    var popCallbackFun;
    if(args.popCallbackFun){
        popCallbackFun=args.popCallbackFun;
    }
    var closeAuto;
    if(args.closeAuto){
        closeAuto=args.closeAuto;
    }
    var popSize='';
    if(args.popSize){
        popSize=args.popSize;
    }
    var gmaskClose=true;
    if(args.gmaskClose!==undefined){
        gmaskClose=args.gmaskClose;
    }
    if(args.eventEle==undefined){
        new popFun().init({
            "popStyle":"fade",
            "popDataId":popDataId,
            "popId":popDataId,
            "creatType":[1,{
                "popSize":"mid-s2 "+popSize,
                "headTitle":args.headTitle,
                "conMsg":[2,popTpl]/*,
                "popAction":[{"actionTxt":"确  认"}]        */
            }],
            "showPop":true,
            "closeAuto":closeAuto,
            "gmaskClose":gmaskClose,
            "popCallbackFun":popCallbackFun
        });
    }else{
        new popFun().init({
            "eventEle":args.eventEle,
            "popStyle":"fade",
            "popDataId":popDataId,
            "popId":popDataId,
            "creatType":[1,{
                "popSize":"mid-s2 "+popSize,
                "headTitle":args.headTitle,
                "conMsg":[2,popTpl]/*,
                "popAction":[{"actionTxt":"确  认"}]*/
            }],
            "closeAuto":closeAuto,
            "gmaskClose":gmaskClose,
            "popCallbackFun":popCallbackFun
        });
    }
}

// 弹出层 end


//倒数
function discount(i,fun){
    var dis = i;
    function _discount(){
        fun(dis);
        if(dis>0){
            setTimeout(_discount,1000);
        }
        dis--;
    }
    _discount();
}
//serialize字符串转json
var strToObj=function (str){
    str = str.replace(/&/g,"','");
    str = str.replace(/=/g,"':'");
    str = "({'"+str +"'})";
    obj = eval(str);
    return obj;
}
//判断obj是否为json对象
function isJson(obj){
    var isjson = typeof(obj) == "object" && Object.prototype.toString.call(obj).toLowerCase() == "[object object]" && !obj.length;
    return isjson;
}
//倒数按钮
/* btnDiscount("#send_mobile_code",3);*/
function btnDiscount(btnObj,time,eventType,txt_start,txt_change){
        if(isJson(time)){
            var i=time.i;
        }else{
            var i=time;
        }
        if(txt_start==undefined){
            var txt_start='获取短信验证码';
        }
        if(txt_change==undefined){
            var txt_change='秒后重发';
        }
        if(!eventType){
            var curObj=btnObj;
            $(curObj).prop("disabled",true);
             discount(i,function(i){
                if(i==0){
                    $(curObj).prop("disabled",false);
                    $(curObj).val(txt_start);
                    if(isJson(time)){
                        var timeCallback=time.timeCallback;
                        timeCallback.call(null)
                    }
                }else{
                    $(curObj).val(i+txt_change);
                }
             })
        }else{
            $("body").on("click",btnObj,function(){
                var curObj=$(this);
                $(curObj).prop("disabled",true);
                 discount(i,function(i){
                    if(i==0){
                        $(curObj).prop("disabled",false);
                        $(curObj).val(txt_start);
                        if(isJson(time)){
                            var timeCallback=time.timeCallback;
                            timeCallback.call(null)
                        }
                    }else{
                        $(curObj).val(i+txt_change);
                    }
                 })
            })
        }

}
/*handlebars生成模板 start*/
/*
var data1 = {title: "My New Posffsst", body: "This is my first post!"};
var data2=[
{"title":"ccc1","body":"1","center":"center1","bottom":"bottom1"},
{"title":"ccc2","body":"21","center":"center2","bottom":"bottom2"},
{"title":"ccc3","body":"31","center":"center3","bottom":"bottom3"}
]
// 单数据源 对 1个模板1个位置
creathtmlTpl({
    "data":data1,//数据
    "sourcetpl":"#entry-template1",//模板
    "insertsit":"#temp1",//插入位置的元素
    "insertmethod":"append"//插入方式
})
//insertmethod：append[不定义默认append],before,after;
*/
var creathtmlTpl=function(args){
    var source   = $(args.sourcetpl).html();
    var template = Handlebars.compile(source);
    var data =args.data;

    if(data instanceof Array){
        var html = "";
        for (var i = 0; i < data.length; i++) {
            var curhtml= template(data[i]);
            html=html+curhtml;
        }
    }else{
        var html = template(data);
    }
    var curInsertMethod;
    if(args.insertmethod!=undefined){
        curInsertMethod=args.insertmethod;
    }else{
        curInsertMethod="append";
    }
    $(args.insertsit)[curInsertMethod](html);
}

/*
//单数据源 对多个模板，多个位置
multipleTpl({
    "data":data2,
    "sourcetpl":["#entry-template1","#entry-template2"],//多个模板
    "insertsit":["#temp2","#temp3"],//多个位置 与模板一样对应
    "insertmethod":["before","after"],//多个插入方式与模板一样对应
    //"insertmethod":"append",//都是一个插入方式
    "CallbackFun":function(args){
        console.log("CallbackFun 1条数据对多个模板，多个位置");//回调函数
    }
});
//兼容  单数据源 对 1个模板1个位置
multipleTpl({
    "data":data1,
    "sourcetpl":"#entry-template1",//1个模板
    "insertsit":"#temp1",//1个位置
    "insertmethod":"append",//1个插入方式
    "CallbackFun":function(args){
        console.log("CallbackFun:兼容  1条数据对 1个模板1个位置");//回调函数
    }
});
//insertmethod：append[不定义默认append],before,after;
*/
var multipleTpl=function(args){

    if(args.sourcetpl instanceof Array){
        for (var i = 0; i < args.sourcetpl.length; i++) {
            var curInsertMethod="";
            if(args.insertmethod!=undefined){
                if(args.insertmethod instanceof Array){
                    curInsertMethod=args.insertmethod[i];
                }else{
                    curInsertMethod=args.insertmethod;
                }
            }else{
                curInsertMethod="append";
            }
            creathtmlTpl({
                "data":args.data,
                "sourcetpl":args.sourcetpl[i],
                "insertsit":args.insertsit[i],
                "insertmethod":curInsertMethod
            })

        }
    }else{
        var curInsertMethod="";
        if(args.insertmethod!=undefined){
            curInsertMethod=args.insertmethod;
        }else{
            curInsertMethod="append";
        }
        creathtmlTpl({
            "data":args.data,
            "sourcetpl":args.sourcetpl,
            "insertsit":args.insertsit,
            "insertmethod":curInsertMethod
        })
    }
    if(args.CallbackFun){
        args.CallbackFun(args);
    }
}
//form 控件选中
//SELECT,radio:selectValue="2";
//checkbox: selectValue="2,3"
/*selvalueFun({"parentEle":});*/
var selvalueFun=function(args){
    var curparentEle="";
    if(args!=undefined){
        curparentEle=args.parentEle;
    }
    $(curparentEle+" :input").each(function(index, el) {
        if($(this).attr("selectValue")!=undefined&&$(this).attr("selectValue")!=""){
            var selectValue=$(this).attr("selectValue");
            var curOjbTagName=$(this)[0].tagName;
                if($(this).closest('form').length==1){
                    var curformEle =$(this).closest('form');
                }
            switch(curOjbTagName){
                case "INPUT":
                    var curOjbType=$(this).attr("type");
                    switch(curOjbType){
                        case "checkbox":
                            var checkboxName=$(this).attr("name");
                            var selectedValueArray = selectValue.split(",");
                            for(var i=0;i<selectedValueArray.length;i++){
                                if(curformEle!=undefined){
                                    $('[name="'+checkboxName+'"][value="'+selectedValueArray[i]+'"]',$(curformEle)).prop('checked',true);
                                }else{
                                    $(curparentEle+' [name="'+checkboxName+'"][value="'+selectedValueArray[i]+'"]').prop('checked',true);
                                }
                            }
                        break;
                        case "radio":
                            var radioName=$(this).attr("name");
                            if(curformEle!=undefined){
                                $(curparentEle+' [name="'+radioName+'"][value="'+selectValue+'"]',$(curformEle)).prop('checked',true);
                            }else{
                                $(curparentEle+' [name="'+radioName+'"][value="'+selectValue+'"]').prop('checked',true);
                            }
                        break;
                    }
                break;
                case "SELECT":
                    $('option[value="'+selectValue+'"]',$(this)).prop('selected',true);
                    /*if($(this).attr("selectxt")!=undefined){
                        $('option[value="'+selectValue+'"]',$(this)).attr('selected', true); //设置Select的Text值为jQuery的项选中
                    }*/
                break;

            }
        }
    });
    $("body").off('change', ":input");
    $("body").on('change', ":input", function(event) {
            var curOjbTagName=$(this)[0].tagName;
                if($(this).closest('form').length==1){
                    var curformEle =$(this).closest('form');
                }
            switch(curOjbTagName){
                case "INPUTss":
                    var curOjbType=$(this).attr("type");
                    switch(curOjbType){
                        //selectValue="2,3"
                        case "checkbox":
                            var checkboxName=$(this).attr("name");
                            var cursleval=[];
                            if(curformEle!=undefined){
                                var checkboxArray=$('[name="'+checkboxName+'"]',$(curformEle));
                            }else{
                                var checkboxArray=$(curparentEle+' [name="'+checkboxName+'"]');
                            }
                            if(typeof($(checkboxArray[0]).attr("selectValue"))!="undefined"){
                                if(curformEle!=undefined){
                                    var checkedArray=$('[name="'+checkboxName+'"]:checked',$(curformEle));
                                }else{
                                    var checkedArray=$(curparentEle+' [name="'+checkboxName+'"]:checked');
                                }
                                //selectValue set;
                                for(var i=0; i<checkedArray.length;i++){
                                    cursleval.push($(checkedArray[i]).val());
                                }
                                cursleval=cursleval.join(",");
                                $(checkboxArray[0]).attr("selectValue",cursleval);
                            }
                        break;
                        case "radio":
                            var radioName=$(this).attr("name");
                            var cursleval=[];
                            if(curformEle!=undefined){
                                var radioArray=$('[name="'+radioName+'"]',$(curformEle));
                            }else{
                                var radioArray=$(curparentEle+' [name="'+radioName+'"]');

                            }
                            if(typeof($(radioArray[0]).attr("selectValue"))!="undefined"){
                                if(curformEle!=undefined){
                                    var checkedArray=$('[name="'+radioName+'"]:checked',$(curformEle));
                                }else{
                                    var checkedArray=$(curparentEle+' [name="'+radioName+'"]:checked');
                                }
                                //selectValue set;
                                cursleval=$(checkedArray).val();
                                $(radioArray[0]).attr("selectValue",cursleval);
                            }
                        break;
                    }
                break;
                case "SELECT":
                    if(typeof($(this).attr("selectValue"))!="undefined"){
                        $(this).attr("selectValue",$(this).val());

                    }
                break;

            }
    });
}
/*selectValue
selectxt*/
//需求详情 报价
function priceTypeFun(){
    $("#price_type").each(function(index, el) {
        var proxySelect=$(this);
        var priceType=$("select",proxySelect).val();
        priceTypeChange(priceType);

    });
    $(".pagewrap").off("click","#price_type");
    $(".pagewrap").on("click","#price_type",function(event) {
        $('.bottom-bar').toggleClass('quote_price_open');
    });
    $(".pagewrap").off("click","#price_type li");
    $(".pagewrap").on("click","#price_type li",function(event) {
        var proxySelect=$(this).closest('.proxy_select');
        var priceType=$("select",proxySelect).val();
        priceTypeChange(priceType);
    });
    function priceTypeChange(priceType){
        var PEle=$(".quote-price");
        switch (priceType){
            case "1":
                $(".li-left .li-1",PEle).addClass("hidden");
                $(".li-left",PEle).addClass("v-center");
                break;
            case "2":
                $(".li-left .li-1",PEle).removeClass("hidden");
                $(".li-left",PEle).removeClass("v-center");
                break;
        }
    }
}

//btn-ftp1 样式设定
function btnFtp1Fun(){
    $(".add-imglist1 .btn-ftp1").each(function(index, el) {
        var imglist=$(this).closest('.add-imglist1');
        if($(this).closest('.add-imglist-col4').length>0){
            var winW=$(this).closest('.add-imglist-col4').width();
            var btnFtpW=winW*0.235;
        }else{
            var winW=$(this).closest('.add-imglist1').width();
            var btnFtpW=winW*0.317;
        }
        $('.cell-img,.cell-img a',imglist).css({"width":btnFtpW+"px","height":btnFtpW+"px"});
        $(this).css({"width":btnFtpW+"px","height":btnFtpW+"px"});
    });
}

/*cookie start*/
function setCookie_g(name,value,expiredays){
    var exdate=new Date()
    exdate.setDate(exdate.getDate()+expiredays)
    document.cookie=name+ "=" +escape(value)+
    ((expiredays==null||expiredays=='') ? "" : ";expires="+exdate.toGMTString());
}
function setCookiePath_g(name,value,expiredays){
    var exdate=new Date()
    exdate.setDate(exdate.getDate()+expiredays)
    document.cookie=name+ "=" +escape(value)+
    ((expiredays==null||expiredays=='') ? "" : ";expires="+exdate.toGMTString())+';path=/';
}
function getCookie_g(name){
    if (document.cookie.length>0){
      start=document.cookie.indexOf(name + "=")
      if (start!=-1){
        start=start + name.length+1
        end=document.cookie.indexOf(";",start)
        if (end==-1) end=document.cookie.length
        return unescape(document.cookie.substring(start,end))
        }
    }
    return ""
}
//删除cookies
function delCookie_g(name){
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie_g(name);
    if(cval!=null&&cval!='') document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}
//删除Path cookies
function delCookiePath_g(name){
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie_g(name);
    if(cval!=null&&cval!='') document.cookie= name + "="+cval+";expires="+exp.toGMTString()+';path=/';
}
//列子
/*function checkCookie(){
  username=getCookie_g('username')
  if (username!=null && username!="")
    {alert('Welcome again '+username+'!')}
  else
    {
    username=prompt('Please enter your name:',"")
    if (username!=null && username!="")
      {
      setCookie_g('username',username,365)
      }
    }
}*/

/*cookie end*/


/*ajax 搜索提示*/
function ajaxSearchFun(args){
    var curEventEle=null;
    $(args.eventEle).on("keyup",function(event){
      curEventEle=$(this);
      if(!$(this).data("oldval")){
        $(this).data("oldval","");
      }
      if($(this).val()!=$(this).data("oldval")){
        $(this).data("oldval",$(this).val());
        clearTimeout($(this).data("timeoutkey"));
        var timeoutKey = setTimeout(function(t){
          return function(){
            var ajaxType= args.ajaxType ? args.ajaxType :'GET';
            var ajaxDataType= args.ajaxDataType ? args.ajaxDataType :"json";
            var postDataObj={"kw":$(t).val()};
            if(args.postDataObj){
                postDataObj= args.postDataObj.call(null)
            }
            $.ajax({
              url:args.promptUrl,
              type:ajaxType,
              dataType:ajaxDataType,
              data:postDataObj,
              success:function(data){
                if(args.ajaxSuccessCall!=undefined){
                    var SuccessCall=eval(args.ajaxSuccessCall);
                    SuccessCall.call(null,{"eventEle":curEventEle,"data":data,'t':t,'FunArgs':args});//当前函数名
                }else{
                    if(data.data!=undefined && data.data.length>0){
                      var searchResultDiv = $("#searchResultDiv");
                      searchResultDiv.css({
                        left:$(t).offset().left+"px",
                        top:($(t).offset().top+$(t).outerHeight())+"px",
                        width:parseInt($(t).outerWidth()+2)+"px"
                      })
                      searchResultDiv.show();
                      searchResultDiv.html("");
                      //数据
                      var curdata=data.data;
                      if  (curdata.length != false) {
                          ga('send','event','index','ajax_complete');
                      }
                      for(var i=0;i<curdata.length;i++){
                        searchResultDiv.append($("<div class='searchResultItem' onclick=\"ga('send','event','index','search_result')\">"+curdata[i]+"</div>"));
                      }
                      searchResultDiv.data("linksearch",$(t));
                    }
                }
              }
            })
          }
        }(this),10);
        $(this).data("timeoutkey",timeoutKey);
      }else{
        var d = 0;
        switch(event.which){
          case 38:
            d--;
            var currentIdx = 0;
            break;
          case 40:
            d++;
            var currentIdx = -1;
            break;
          case 13:
            clearTimeout($(this).data("timeoutkey"));
            $("#searchResultDiv").data("linksearch",$(this));
                searchSelect();
                return true;
            }

            var all = 0;
            $("#searchResultDiv").find(".searchResultItem").each(function(idx,ele){
              if($(ele).hasClass("cur")){
                currentIdx = idx;
                $(ele).removeClass("cur");
              }
              all++;
            });
            if(all!=0){
              currentIdx+=d;
              currentIdx%=all;
            }
            if(d!=0){
                $("#searchResultDiv .searchResultItem:eq("+currentIdx+")").addClass('cur');
            }


      }
    })
    $(args.eventEle).on("blur",function(event){
      clearTimeout($(this).data("timeoutkey"));
      if($(this).data("cancelblur")!="true"){
        $("#searchResultDiv").hide();
        $("#searchResultDiv").data("linksearch",null);
      }
    })
    $("<div id='searchResultDiv' style='position:absolute;border:1px solid #f0f1f6;display:none'></div>").appendTo("body");
    $("#searchResultDiv").on("mouseover",".searchResultItem",function(){
      //$(this).closest("#searchResultDiv").find(".searchResultItem").removeClass('cur');
      //$(this).addClass('cur');
      $("#searchResultDiv").data("linksearch").data("cancelblur","true");
    });
    $("#searchResultDiv").on("mouseout",".searchResultItem",function(){
      var linkSearch = $("#searchResultDiv").data("linksearch");
      if(linkSearch!=null){
        $("#searchResultDiv").data("linksearch").data("cancelblur","false");
      }
    })
    $("#searchResultDiv").on("click",".searchResultItem",function(){
        $(this).closest("#searchResultDiv").find(".searchResultItem").removeClass('cur');
        $(this).addClass('cur');
        searchSelect();
    });
    function searchSelect(){
      var linkSearch = $("#searchResultDiv").data("linksearch");
      if(linkSearch!=null){
          linkSearch.data("cancelblur","false");
          linkSearch.trigger('blur');
          if($("#searchResultDiv .cur").length!=0){
              if(args.onconfirmFun!=undefined){
                var confirmCall=eval(args.onconfirmFun);
                confirmCall.call(null,{"eventEle":curEventEle});//当前函数名
              }else{
                linkSearch.val($("#searchResultDiv .cur").html());
              }
          }
      }
      if(args.searchCallback!=undefined){
        var searchCall=eval(args.searchCallback);
        searchCall.call(null,{"eventEle":curEventEle,"postData":linkSearch.val()});//当前函数名
      }
    }
  }

//ajax 搜索提示 data
//data： {"data":["php\u4e2d\u7ea7\u7a0b\u5e8f\u5458","php\u540e\u53f0\u5de5\u7a0b\u5e08","php\u5f00\u53d1\u5de5\u7a0b\u5e08","php\u7a0b\u5e8f\u5458","php\u9ad8\u7ea7\u7a0b\u5e8f\u5458"],"status":1}

// 调用
//ajaxSearchFun({
//  "eventEle":".ajaxSearch",
//  "promptUrl":"select_ajax_test2.json",
//  "searchCallback":function(args){
//      //{"eventEle":"eventEle","postData":"postData"}
//      console.log(args);
//      $.ajax({
//          url:"select_ajax_test2-2.json",
//          type:"POST",
//          data:args.postData,
//          success:function(data){
//            console.log("搜索结果:"+args.postData);
//            console.log(args.eventEle);
//          }
//      });
//  }
//});
//moreInitOperFun 展开更多
function moreInitOperFun(){
    $('.js_moreOper').each(function(index, el) {
        var initOperLt=parseInt($(this).attr('initOper'));
        var initOperGt=initOperLt-1;
        var moreOper=$(this);
        var operCell=$(this).attr('operCell');
        var operList=$(this).attr('operList');
        if($(operList+' '+operCell,moreOper).length>initOperLt){
            $('.js_btn_down',moreOper).removeClass('hidden');
        }
        $(operList+' '+operCell+':lt('+initOperLt+')',moreOper).removeClass('hidden');
        //展开
        $('.js_btn_down',moreOper).off('click');
        $('.js_btn_down',moreOper).on('click', function(event) {
            $(this).addClass('hidden');
            $(operList+' '+operCell+'',moreOper).removeClass('hidden');
            $('.js_btn_up',moreOper).removeClass('hidden');
        });
        //收起
        $('.js_btn_up',moreOper).off('click');
        $('.js_btn_up',moreOper).on('click', function(event) {
            $(this).addClass('hidden');
            $(operList+' '+operCell+':gt('+initOperGt+')',moreOper).addClass('hidden');
            $('.js_btn_down',moreOper).removeClass('hidden');
        });
    });

}
/*
//textarea验证
textareaValidateFun({
    'valiEle':'#evaluate',
    'valiPele':'.li',
    'minlength':[10,'内容小于10个字'],
    'maxlength':[200,'已超过字数限制']
});
*/
//textarea验证
function textareaValidateFun(args){
    $(args.valiEle).on('keyup',function(){
        var valLen=$(this).val().length;
        var eleP=$(this).closest(args.valiPele);
        if(valLen<args.minlength[0]){
            $('.textarea-error',eleP).html('<i class="ico ico-err"></i>'+args.minlength[1]);
            $('.textarea-error',eleP).addClass('err');
        }else{
            if(valLen>args.maxlength[0]){
                $('.textarea-error',eleP).html('<i class="ico ico-err"></i>'+args.maxlength[1]);
                $('.textarea-error',eleP).addClass('err');
            }else{
                $('.textarea-error',eleP).html($('.textarea-error',eleP).attr('default_txt'));
                $('.textarea-error',eleP).removeClass('err');
            }
        }
    })
}