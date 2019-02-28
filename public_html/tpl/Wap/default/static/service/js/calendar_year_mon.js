$(document).ready(function() {
    new calendarYearMon();
});
/*
<div class="bl_calendar_ym_group">
    <div class="bl_calendar_ym" calendargroup="start">
        <input  type="text" readonly  class="bl_calendar_ym_txt"  placeholder="起始时间"  value="2013-01" ><i></i>
    </div>
    <div class="bl_calendar_ym" calendargroup="end">
        <input type="text" readonly  class="bl_calendar_ym_txt" today="true" placeholder="结束时间" value=""><i></i>
    </div>
</div>
<div class="bl_calendar_ym" >
    <input type="text" readonly class="bl_calendar_ym_txt" today="true" calendarstart="2002/05" calendarend="2014/08" calendarformat="/" limitmsg="结束时间不能晚于起始时间" placeholder="结束时间"  placeholder=""><i></i>
</div>
*/
function calendarYearMonInit(){
    $(".bl_calendar_ym .bl_calendar_ym_txt").each(function(index, el) {
        var eventEle=$(this);
        $(eventEle).blur();
        demandFromCalendar(eventEle);
    });

}
function demandFromCalendar(eventEle){
    if($(eventEle).val()!=""){
        $(eventEle).css({"text-align":"left"});
    }else{
        $(eventEle).css({"text-align":"right"});
    }
}
function calendarYearMon(){
    var TfThis=this;
    TfThis.winH=$(window).height();
    TfThis.winW=$(window).width();
    TfThis.popT= $(".header").length>0 ? $(".header").height() :0;
    TfThis.popH= $(".header").length>0 ? TfThis.winH-TfThis.popT :TfThis.winH;
    $(".pagewrap").on("click",".bl_calendar_ym",function(event) {
        var eventEle=$(this);
        if($(".bl_calendar_ym_wrap").length>0){
            $(".bl_calendar_ym_wrap").remove();
        }
        TfThis.init(eventEle);
        $('.bl_calendar_ym_txt',$(this)).blur();
    });
}
calendarYearMon.prototype={
    eventEle:null,
    yearCur:null,
    monthCur:null,
    dayCur:null,
    datekey:null,
    inputTxt:null,
    today:null,
    limitMsg:null,
    format:null,
    firstDay:null,
    lastDay:null,
    firstMonth:null,
    lastMonth:null,
    calendarCur:null,
    calendarStart:null,
    calendarEnd:null,
    yearEnd:null,
    yearStart:null,
    hourEnd:0,
    hourStart:23,
    minEnd:0,
    minStart:59,
    secEnd:0,
    secStart:59,
    TimeCur:null,
    init:function(eventEle){
        /*initVal start*/
        var TfThis=this;
        TfThis.eventEle=eventEle;

        TfThis.yearCur=new Date().getFullYear();
        TfThis.monthCur=new Date().getMonth()+1;
        TfThis.dayCur=new Date().getDate();
        TfThis.datekey=$(".bl_calendar_ym_txt",TfThis.eventEle).attr("datekey");
        if(!TfThis.datekey||TfThis.datekey==""){
            TfThis.datekey="y-m-d";
        }
        TfThis.datekey=TfThis.datekey.split("-");
        //console.log(TfThis.datekey);
        /*initVal end*/
        var htmltpl=TfThis.popHtml({"eventEle":eventEle});
        $(".pagewrap").append(htmltpl);
        $(".pagewrap").css({"width":TfThis.winW+"px","height":TfThis.winH+"px","overflow":"hidden"});//mobile 页面100% set
        //init .pop_lv 宽度
        var popLvLen=$('.pop_slideUp .pop_lv').length;
        var popLvW=parseInt(TfThis.winW/popLvLen);
        $('.pop_slideUp .pop_lv').css({'width':popLvW+'px'});
        //弹出动画
        animCss3Fun({
            "animObj":".bl_calendar_ym_wrap .pop",
            "animOption":"slideInUp"
        });
        //层关闭
        popUpCloseFun(".bl_calendar_ym_wrap");
        /*init html*/
        TfThis.calendarYMFun();
        //确认
        TfThis.comfirmFun();
    },
    calendarYMFun:function(){
        var TfThis=this;
        //定义
        TfThis.inputTxt=$(".bl_calendar_ym_txt",$(TfThis.eventEle));
        TfThis.today=$(TfThis.inputTxt).attr("today");
        TfThis.limitMsg=$(TfThis.inputTxt).attr("limitmsg");
        if(TfThis.limitMsg==undefined||TfThis.limitMsg==""){
            TfThis.limitMsg="该月份不可选！"
        }
        TfThis.format=$(TfThis.inputTxt).attr("calendarformat");
        if(TfThis.format==undefined||TfThis.format==""){
            TfThis.format="-";
        }
        //设置 start end
        TfThis.calendarStart=$(TfThis.inputTxt).attr("calendarstart");
        TfThis.calendarEnd=$(TfThis.inputTxt).attr("calendarend");
        //默认
        //calendarCur 今天日期
        TfThis.calendarCur=[TfThis.yearCur,TfThis.monthCur,TfThis.dayCur];

        //TimeCur 当前时间
        TfThis.TimeCur=[new Date().getHours(),new Date().getMinutes(),new Date().getSeconds()];
        //disYears 间距多少年
        var disYears=$(TfThis.inputTxt).attr('disYears');
        disYears= disYears ? parseInt(disYears) : 70 ;
        //年
        TfThis.yearStart=parseInt(TfThis.calendarCur[0])-disYears;
        TfThis.yearEnd=TfThis.calendarCur[0];
        //增量
        var years_add=$(TfThis.inputTxt).attr('add_date');
            years_add=30;
        //年
        if(TfThis.calendarStart!=undefined&&TfThis.calendarStart!=""){
            TfThis.calendarStart= TfThis.calendarStart.split(TfThis.format);
            TfThis.yearStart=parseInt(TfThis.calendarStart[0]);

        }
        if(TfThis.calendarEnd!=undefined&&TfThis.calendarEnd!=""){
            TfThis.calendarEnd= TfThis.calendarEnd.split(TfThis.format);
            TfThis.yearEnd=parseInt(TfThis.calendarEnd[0]);
            years_add=0;
        }
        //增量
        TfThis.yearEnd = years_add ? (TfThis.yearCur+parseInt(years_add)).toString() : TfThis.yearEnd=(TfThis.yearCur).toString();

        //月
        TfThis.monthStart=1;
        TfThis.monthEnd=12;
        if(TfThis.calendarStart!=null){
            TfThis.monthStart=TfThis.calendarStart[1];
        }
        if(TfThis.calendarEnd!=null){
            TfThis.monthEnd=TfThis.calendarEnd[1];
        }
        //日
        TfThis.dayStart=1;
        TfThis.dayEnd=31;
        //调用
        TfThis.calendarTplCreate();
    },
    calendarTplCreate:function(){
        var TfThis=this;
        //liclick;
        TfThis.liEventFun();
        var seldate=TfThis.calendarCur;
        var seltime=TfThis.TimeCur;
        //set初始日期
        if($(TfThis.inputTxt).val()!=""&&$(TfThis.inputTxt).val()!=$(TfThis.inputTxt).attr("placeholder")){
           var selDateTime=$(TfThis.inputTxt).val().split(" ");
           //日期
           seldate=selDateTime[0].split(TfThis.format);
           seldate[0]= seldate[0] ? parseInt(seldate[0]) : "";
           seldate[1]= seldate[1] ? parseInt(seldate[1]) : "";
           seldate[2]= seldate[2] ? parseInt(seldate[2]) : "";
           //时间
           if(selDateTime.length>1){
               seltime=selDateTime[1].split(":");
               seltime[0]= seltime[0] ? parseInt(seltime[0]) : "";
               seltime[1]= seltime[1] ? parseInt(seltime[1]) : "";
               seltime[2]= seltime[2] ? parseInt(seltime[2]) : "";
               //时间
               seldate[3]=seltime[0];
               seldate[4]=seltime[1];
               seldate[5]=seltime[2];
           }
        }else{
           //时间
           seldate[3]=seltime[0];
           seldate[4]=seltime[1];
           seldate[5]=seltime[2];
        }
        if(TfThis.datekey[0]=='th'){
            $('.bl_calendar_ym_wrap .bl_calendar_th').attr("selectValue",seldate[3]);
            //$('.bl_calendar_ym_wrap .bl_calendar_tm').attr("selectValue",seldate[4]);
            $('.bl_calendar_ym_wrap .bl_calendar_tm').attr("selectValue",0);
            $('.bl_calendar_ym_wrap .bl_calendar_ts').attr("selectValue",seldate[5]);
        }else{
            for(var i=0; i<$(".bl_calendar_ym_wrap .pop_lv").length; i++){
                $('.bl_calendar_ym_wrap .pop_lv:eq('+i+')').attr("selectValue",seldate[i]);
            }
        }

        TfThis.createDate(TfThis.yearEnd,TfThis.yearStart,$('.bl_calendar_y'),"年");
        TfThis.createMonthFun();//月
        TfThis.createDayFun();//日
        //时分秒
        TfThis.createDate(TfThis.hourEnd,TfThis.hourStart,$('.bl_calendar_th'),"点");
        TfThis.createDate(TfThis.minEnd,TfThis.minStart,$('.bl_calendar_tm'),"分");
        TfThis.createDate(TfThis.secEnd,TfThis.secStart,$('.bl_calendar_ts'),"秒");

    },
    createDate:function (valueStar,valueEnd,innerId,defaultTxt){
        var valueStar=parseInt(valueStar);
        var valueEnd=parseInt(valueEnd);
        var d=1;
        if($(innerId).hasClass('bl_calendar_tm')){
            d=15;
        }
        var TfThis=this;
        defaultTxt= defaultTxt ? defaultTxt : "";
        var valHtml=[];
        valHtml.push('<li val="" ></li><li val="" ></li>');
        if(TfThis.today=="true"&&$(innerId).hasClass('bl_calendar_y')){
            valHtml.push('<li val="0" ><span>至今</span></li>');
        }
        if(valueStar<valueEnd){
            for(i=valueStar;i<=valueEnd;i=i+d){
                valHtml.push('<li val="'+i+'" ><span>'+i+defaultTxt+'</span></li>');
            }
        }else{
            for(i=valueStar;i>=valueEnd;i=i-d){
                valHtml.push('<li val="'+i+'" ><span>'+i+defaultTxt+'</span></li>');
            }
        }
        valHtml.push('<li val="" ></li><li val="" ></li>');
        valHtml=valHtml.join("");
        $(".option_group",innerId).html(valHtml);
        // 选中
        var selectValue=$(innerId).attr("selectValue");
        //console.log(valueEnd+':'+selectValue);
        $('.option_group li[val="'+selectValue+'"]',innerId).addClass("cur");
        var liCurIndex=$(".option_group li.cur",innerId).index();
        if(liCurIndex!=-1){
            //stopScrollFun 定位
            TfThis.stopScrollFun(innerId);
            //styleAnimFun 动画样式
            TfThis.styleAnimFun(liCurIndex,innerId);
        }
    },
    createMonthFun:function(){
        var TfThis=this;
        //月
        var y=$('.bl_calendar_y li.cur').attr("val");
        TfThis.monthStart=1;
        TfThis.monthEnd=12;
        if(TfThis.calendarStart!=null){
            if(y==TfThis.calendarStart[0]){
                TfThis.monthStart=TfThis.calendarStart[1];
            }
        }
        if(TfThis.calendarEnd!=null){
            if(y==TfThis.calendarEnd[0]){
                TfThis.monthEnd=TfThis.calendarEnd[1];
            }
        }
        var curSelectM=$('.bl_calendar_m').attr("selectvalue");
        if(curSelectM>=TfThis.monthStart&&curSelectM<=TfThis.monthEnd){
        }else{
            if(TfThis.calendarStart!=null){
                $('.bl_calendar_m').attr("selectvalue",TfThis.monthStart);
            }
            if(TfThis.calendarEnd!=null){
                $('.bl_calendar_m').attr("selectvalue",TfThis.monthEnd);
            }
        }
        TfThis.createDate(TfThis.monthStart,TfThis.monthEnd,$('.bl_calendar_m'),"月");
    },
    createDayFun:function(){
        var TfThis=this;
        //日
        var y=$('.bl_calendar_y li.cur').attr("val");
        var m=$('.bl_calendar_m li.cur').attr("val");
        TfThis.dayStart=1;
        if(((y%4 == 0&&y%100 != 0)||y%400 == 0)&& m==2){
            //闰年
            TfThis.dayEnd=29;
        }else{
            //平年
            if(m==2){
                TfThis.dayEnd=28;
            }else{
                if((m<8&&m%2==0)||(m>7&& m%2==1)){
                   TfThis.dayEnd=30;
                }else{
                    TfThis.dayEnd=31;
                }
            };
        }

        if(TfThis.calendarStart!=null){
            if(y==TfThis.calendarStart[0]&&m==TfThis.calendarStart[1]){
                TfThis.dayStart=TfThis.calendarStart[2];
            }
        }
        if(TfThis.calendarEnd!=null){
            if(y==TfThis.calendarEnd[0]&&m==TfThis.calendarEnd[1]){
                TfThis.dayEnd=TfThis.calendarEnd[2];
            }
        }
        var curSelectD=$('.bl_calendar_d').attr("selectvalue");
        if(curSelectD>=TfThis.dayStart&&curSelectD<=TfThis.dayEnd){
        }else{
            if(TfThis.calendarStart!=null){
                 $('.bl_calendar_d').attr("selectvalue",TfThis.dayStart);
            }
            if(TfThis.calendarEnd!=null){
                 $('.bl_calendar_d').attr("selectvalue",TfThis.dayEnd);
            }


        }
        TfThis.createDate(TfThis.dayStart,TfThis.dayEnd,$('.bl_calendar_d'),"日");
    },
    popHtml:function(args){
        var TfThis=this;
        var html = [];
        html.push('');
        html.push('<div class="pop_slideUp bl_calendar_ym_wrap" style="bottom:0px;height:'+TfThis.popH+'px">');
        html.push('<div id="gmask"></div>');
        html.push('<div class="pop" id="pop"><span class="close"></span>');
        html.push('     <div class="pop_body">');
        html.push('         <div class="pop_action">');
        html.push('             <a class="btn btn-orange" href="javascript:;" id="pop_comfirm" actionColse="false"><i class="ico ico-sure"></i>确定</a>');
        html.push('             <a class="btn btn-green" href="javascript:;" actioncolse="true"><i class="ico ico-cancel"></i>取消</a>');
        html.push('         </div>');
        html.push('         <div class="pop_main"> ');
        html.push('             <div class="lvbg_cur"></div>');
        html.push('             <div class="pop_lv_wrap" >');
        for(var i=0; i<TfThis.datekey.length;i++){
            //html.push('               <div class="pop_lv"><ul><li class="s2">1月</li><li class="s1">2月</li><li class="cur">3月</li><li class="s1">4月</li><li class="s2">5月</li><li class="s3">6月</li></ul></div>');
            html.push('             <div class="pop_lv bl_calendar_'+TfThis.datekey[i]+'" id="pop_lv_'+TfThis.datekey[i]+'"><ul class="option_group" ></ul></div>');
        }
        html.push('             </div>');
        html.push('         </div>');
        html.push('         <div class="pop_checked">');
        html.push('         </div>');

        html.push('     </div>');
        html.push(' </div>');
        html.push('</div>');
        return html.join('');
    },
    styleAnimFun:function(liCurIndex,lvObj){
        var liEle=$(".option_group li",lvObj);
        $(liEle).removeClass("s1");
        $(liEle).removeClass("s2");
        $(liEle).removeClass("cur");
        $(liEle).removeClass("s1");
        $(liEle).removeClass("s2");
        $(liEle).removeClass("s3");
        $(liEle).eq(liCurIndex-1).addClass("s1");
        $(liEle).eq(liCurIndex-2).addClass("s2");
        $(liEle).eq(liCurIndex).addClass("cur");
        $(liEle).eq(liCurIndex+1).addClass("s1");
        $(liEle).eq(liCurIndex+2).addClass("s2");
        $(liEle).eq(liCurIndex+3).addClass("s3");
    },
    stopScrollFun:function(lvObj){
        var lvbg_curH=parseInt($('.lvbg_cur').outerHeight());
        $('.pop_slideUp .pop_main .lvbg_cur').css({'top':lvbg_curH*2+'px'});
        $('.pop_slideUp .pop_lv li,.pop_slideUp .pop_main .lvbg_cur').css({'height':lvbg_curH+'px'});
        $('.pop_lv_wrap').css({'height':(lvbg_curH*5)+'px'});
        var stopSit=($("li.cur",lvObj).index()-2)*lvbg_curH;
        $(lvObj).animate({scrollTop:stopSit}, 100);
    },
    liEventFun:function (){
        var TfThis=this;
        $(".pagewrap").off('click', ".bl_calendar_ym_wrap .pop_lv li");
        $(".pagewrap").on('click', ".bl_calendar_ym_wrap .pop_lv li", function(event){
            var liVal=$(this).attr("val");
            if(liVal!=""){
                var liText=$(this).text();
                var lvObj=$(this).closest('.pop_lv');
                if($(lvObj).attr("selectValue")!=liVal){
                    $(lvObj).attr("selectValue",$(this).attr("val"));
                    $("li",lvObj).removeClass("cur");
                    $(this).addClass("cur");
                    var liCurIndex=$(".option_group li.cur",lvObj).index();
                    if(liCurIndex!=-1){
                        //stopScrollFun 定位
                        TfThis.stopScrollFun(lvObj);
                        //styleAnimFun 动画样式
                        TfThis.styleAnimFun(liCurIndex,lvObj);
                    }
                    if($(lvObj).hasClass('bl_calendar_y')){
                        //month
                        TfThis.createMonthFun();
                    }
                    if($(lvObj).hasClass('bl_calendar_y')||$(lvObj).hasClass('bl_calendar_m')){
                        //data
                        TfThis.createDayFun();
                    }
                }
            }
        });
        var scrollFun = function(event) {
            var curScrollTop=$(this).scrollTop();
            var lvbg_curH=parseInt($('.lvbg_cur').outerHeight());
            curScrollTop=curScrollTop+lvbg_curH/2;
            var curObjIndex=parseInt(curScrollTop/lvbg_curH)+2;
            //styleAnimFun 动画样式
            TfThis.styleAnimFun(curObjIndex,$(this));
            //scrollEndingFun
            if($(this).data('scrolling')!='true'){
               scrollEndingFun(this);
            }
        }
        var scrollEndingFun = function(thiz){
                clearTimeout($(thiz).data('scrollTimeout'));
                $(thiz).data('scrollTimeout', setTimeout(function(popLv){
                    return function(){
                        //stopScrollFun 定位
                        TfThis.stopScrollFun(popLv);
                        setTimeout(function(){clearTimeout($(popLv).data('scrollTimeout'));},2);
                        if(TfThis.selectType!="checkbox"){
                            $("li.cur",popLv).trigger('click');
                        }
                    }
                }(thiz), 100))
            };

        var scrollStartFun = function(){
            $(this).data('scrolling','true');
        }
        var scrollEndFun = function(){
            $(this).data('scrolling','false');
            scrollEndingFun(this);
        }
        $('.bl_calendar_ym_wrap .pop_lv').on('scroll',scrollFun);
        //$('.popUp_sel .pop_lv').on('touchmove',scrollStartFun);
        //$('.popUp_sel .pop_lv').on('touchend',scrollEndFun);
    },
    comfirmFun:function(){
        var TfThis=this;
        $(".pagewrap").off("click","#pop_comfirm");
        $(".pagewrap").on("click","#pop_comfirm",function(){
            var valArr=[];
            var timeArr=[];
            $(".bl_calendar_ym_wrap .pop_lv").each(function(index, el) {
                if($("li.cur",$(this)).length>0){
                    if($("li.cur",$(this)).attr("val")!=""){
                        var curVal=$("li.cur",$(this)).attr("val");
                        curVal= curVal<10 ? "0"+curVal : curVal;
                        if($(this).hasClass("bl_calendar_th")||$(this).hasClass("bl_calendar_tm")||$(this).hasClass("bl_calendar_ts")){
                            //时分秒
                            timeArr.push(curVal);
                        }else{
                            valArr.push(curVal);
                        }
                    }
                }
            });
            //set值
            var valTxt=valArr.join(TfThis.format);
            //时分秒
            if(timeArr.length>0){
                valTxt=valTxt+" "+timeArr.join(":");
            }
            $(".bl_calendar_ym_txt",TfThis.eventEle).val(valTxt);
            demandFromCalendar($(".bl_calendar_ym_txt",TfThis.eventEle));
            $(".bl_calendar_ym_txt",TfThis.eventEle).trigger('blur');
            //calendargroup
            var calendarGroup=$(TfThis.eventEle).attr("calendargroup");
            if(calendarGroup=="start"){
                var groupEventEle=$(TfThis.eventEle).closest(".bl_calendar_ym_group");
                switch(valArr.length){
                    case 3:
                        var dayNum =1;
                        break;
                    case 2:
                        var dayNum =31;
                        break;
                    case 1:
                        var dayNum =365;
                        break;
                }
                var tomorrow=TfThis.getOneDay(valArr.join(","),dayNum);
                $('.bl_calendar_ym[calendargroup="end"] .bl_calendar_ym_txt',groupEventEle).attr("calendarstart",tomorrow.join(TfThis.format));
            }
            if(calendarGroup=="end"){
                var groupEventEle=$(TfThis.eventEle).closest(".bl_calendar_ym_group");
                switch(valArr.length){
                    case 3:
                        var dayNum =-1;
                        break;
                    case 2:
                        var dayNum =-31;
                        break;
                    case 1:
                        var dayNum =-365;
                        break;
                }
                var yesterday=TfThis.getOneDay(valArr.join(","),dayNum);
                $('.bl_calendar_ym[calendargroup="start"] .bl_calendar_ym_txt',groupEventEle).attr("calendarend",yesterday.join(TfThis.format));
            }
            //关闭层
            $(".bl_calendar_ym_wrap .close").trigger("click");
            $("html,body").stop().animate({scrollTop: $(TfThis.eventEle).offset().top+'px'}, 1);
        });
    },
    getOneDay:function(date,num){
        var dateLen=date.split(",").length;
        var DayMs=1000*60*60*24;
        var dateMs=Date.parse(date);
        var otherDayMs=dateMs+(num*DayMs);
        var otherDay=new Date();
            otherDay.setTime(otherDayMs);
        var yearOther=otherDay.getFullYear();
        var monthOther=otherDay.getMonth()+1;
        var dayOther=otherDay.getDate();

        var otherDate=[yearOther,monthOther,dayOther];
        otherDate.length=dateLen;
        return otherDate;
        //console.log(otherDate.join("-"));
    }
}
