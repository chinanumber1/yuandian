/**
 * Created by tanytree on 2016/1/15.
 */

//单选框 .form_2radio
$(document).ready(function () {
    $(".cssRadio").each(function(){
        $(this).cssRadio()
    });
});
jQuery.fn.cssRadio = function () {
    var context = this;
    jQuery("input[type='radio'] + label", this)
        .each(function () {
            if (jQuery(this).prev()[0].checked)
                jQuery(this).addClass("checkOn");
        })
        .click(function () {
            jQuery("input[type='radio'] + label", context)
                .each(function () {
                    jQuery(this)
                        .removeClass('checkOn')
                        .prev()[0].checked = false;
                });
            jQuery(this)
                .addClass("checkOn")
                .prev()[0].checked = true;
        }).prev().hide();
};



$(function(){
    $(".rightForm .bd .putWrap input[type=text],.rightForm .bd .putWrap textarea,.rightForm .bd .putWrap select").focus(function(){
        $(this).addClass('focusOn');
    }).blur(function(){
        $(this).removeClass('focusOn');
    });
	
	$(".windowBg").height($(window).height());
	$(window).resize(function(){
	$(".windowBg").height($(window).height());
		
	});
});
//时间
$(function() {
    $( ".actData").each(function(){
        $(this).datetimepicker({
            timeFormat: "HH:mm",
            dateFormat: "yy-mm-dd"
        });
    });

    //myFun.tabs(".invitationSet label",".rightForm .bd .shareType","on");
	
	    $(".invitationSet label").click(function(){
       if($(this).attr('for')=='cash'){
           $(".rightForm .bd .shareType").eq(0).show().siblings().hide();
       } if($(this).attr('for')=='score'){
           $(".rightForm .bd .shareType").eq(1).show().siblings().hide();
       }
    });

    if($("#cash").attr("checked")=='checked'){
        $(".rightForm .bd .shareType").eq(0).show().siblings().hide();
    }else{
        $(".rightForm .bd .shareType").eq(0).hide().siblings().show();
    }
    if($("#score").attr("checked")=='checked'){
        $(".rightForm .bd .shareType").eq(1).show().siblings().hide();
    }else{
        $(".rightForm .bd .shareType").eq(1).hide().siblings().show();
    }

	
	
	
});

$(function(){
    var rightFormLeft=$(".rightForm").offset().left;
    $("#uploadImgBox").css('left',rightFormLeft);
});
//固定底部
function fixFooter(){
    var h = $(".gameConfig").outerHeight();
    var left = $(".gameConfig").offset().left;
	var itemCtrlBoxW=$("#itemCtrlBox").width();
    $(".saveBottomBar").width(itemCtrlBoxW);
    if( window.innerHeight < h){
        $(".saveBottomBar").addClass("fixBottombar");
        $(".saveBottomBar").css('left',left);
    }else{
        $(".saveBottomBar").removeClass("fixBottombar");
    }
}
$(function(){
    fixFooter();
});
window.onresize = function(){
    fixFooter();
};

//主页面内操作表单和弹窗
$(function(){
    $('.formRow').mouseleave(function(){
        $(this).removeClass('checkBorder');
        $(this).parent().removeClass('checkBorder');
    });


});

//操作iframe


$(function(){
    var start,end;
    var d = new Date();
    var vYear = d.getFullYear();
    var vMon = d.getMonth() + 1;
    var vDay = d.getDate();
    var h = d.getHours();
    var m = d.getMinutes();
    var se = d.getSeconds();
    start=vYear+'-'+(vMon<10 ? "0" + vMon : vMon)+'-'+(vDay<10 ? "0"+ vDay : vDay)+' '+(h<10 ? "0"+ h : h)+':'+(m<10 ? "0" + m : m);
    Date.prototype.format =function(format)
    {
        var o = {
            "M+" : this.getMonth()+1, //month
            "d+" : this.getDate(), //day
            "h+" : this.getHours(), //hour
            "m+" : this.getMinutes() //minute
            //"s+" : this.getSeconds(), //second
//                    "q+" : <a href="https://www.baidu.com/s?wd=Math.floor&tn=44039180_cpr&fenlei=mv6quAkxTZn0IZRqIHckPjm4nH00T1d-nHTvmhn3PAuhuA7bPhmv0ZwV5Hcvrjm3rH6sPfKWUMw85HfYnjn4nH6sgvPsT6KdThsqpZwYTjCEQLGCpyw9Uz4Bmy-bIi4WUvYETgN-TLwGUv3EnHmvrjDYn1f3njTvrjTLnjDsPs" target="_blank" class="baidu-highlight">Math.floor</a>((this.getMonth()+3)/3), //quarter
//            "S" : this.getMilliseconds() //millisecond
        };
        if(/(y+)/.test(format)){
            format=format.replace(RegExp.$1,(this.getFullYear()+"").substr(4- RegExp.$1.length));
        }
        for(var k in o){
            if(new RegExp("("+ k +")").test(format)){
                format = format.replace(RegExp.$1,RegExp.$1.length==1? o[k] :("00"+ o[k]).substr((""+o[k]).length));
            }
        }
        return format;
    }

    var addNDays=function(date,n){
        var d = new Date(Date.parse(date.replace(/-/g,"/")));
        var time=d.getTime();
        var newTime=time+n*24*60*60*1000;
        return new Date(newTime);
    };
    var nd = addNDays(start,29);
    end = nd.format('yyyy-MM-dd hh:mm');

    //$("#itemContent .start").each(function(){
    //    $(this).val(start);
    //});
    //$("#itemContent .end").each(function(){
    //    $(this).val(end);
    //});

});







$(function(){
    var x= $(".rightForm").offset().left;
    var y= $(".rightForm").offset().top;
    $(".uploadBox").css({left:x});

    $(".uploadBox").each(function(){
        var obj=$(this);
        centerWindow(obj);
    });
});
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
    var boxHeight = $(a).outerHeight(true);
    var scrollTop = $(window).scrollTop();
    var scrollLeft = $(window).scrollLeft();
    var top = (wHeight - boxHeight) / 2;
    //var left = scrollLeft + (wWidth - boxWidth) / 2;
    var x= $(".rightForm").offset().left;

    $(a).css({
        "top": top,'left':x
    });
}

var styleMod1={
    width:400,
    height:256,
    bg:{
        color:'#ffffff',
        head:{height:110,alpha:0.2,color:'#ffffff'},
        image:{src:(typeof _staticPath=='undefined'?'':_staticPath)+'hbimage/placeholder/style1.png'}
    },
    element:[
        {type:'avatar',x:15,y:15},
        {type:'textarea',x:100,y:30,color:'#fffff',size:18,content:[
            {text:'我是',color:'#ffffff',size:18},
            {text:'{$username}',color:'#fff600',size:18}
        ]},
        {type:'textarea',x:210,y:33,color:'#ffffff',size:14,content:[
            {text:'我为',color:'#ffffff',size:14},
            {text:'{$company}',color:'#fff600',size:14},
            {text:'代言',color:'#ffffff',size:14}
        ]},
        {type:'textarea',x:95,y:55,color:'#ffffff',size:14,content:[
            {text:'推广时间：{$start_time}至{$end_time}',color:'#ffffff',size:14}
        ]},
        {type:'qrcode',width:122,height:122,x:265,y:122},
        {type:'textarea',width:240,height:72,x:10,y:115,color:'#fff',size:12,content:[{text:'描述：阿尔法围棋（AlphaGo）是一款围棋人工智能程序，由位于英国伦敦的谷歌旗下DeepMind公司的戴维·西尔弗、艾佳·黄和戴密斯·哈萨比斯与他们的团队开发。'}]},
        {type:'textarea',x:10,y:210,color:'#fff600',size:14,content:[
			{text:'长按此图，识别图中的二维码'}
		]}
    ]
};
var styleMod2={
    width:320,
    height:500,
    bg:{
        color:'#ffffff',
        head:{height:110,alpha:0.2,color:'#ffffff'},
        image:{src:(typeof _staticPath=='undefined'?'':_staticPath)+'hbimage/placeholder/style2.png'}
    },
    element:[
        {type:'avatar',x:50,y:15},
        {type:'textarea',x:140,y:20,color:'#fffff',size:18,content:[
            {text:'我是',color:'#ffffff',size:18},
            {text:'{$username}',color:'#fff600',size:18}
        ]},
        {type:'textarea',x:140,y:60,color:'#ffffff',size:14,content:[
            {text:'我为',color:'#ffffff',size:14},
            {text:'{$company}',color:'#fff600',size:14},
            {text:'代言',color:'#ffffff',size:14}
        ]},
        {type:'textarea',x:40,y:457,color:'#ffffff',size:14,content:[
            {text:'推广时间：{$start_time}至{$end_time}',color:'#ffffff',size:14}
        ]},
        {type:'qrcode',width:180,height:180,x:65,y:215},
        {type:'textarea',width:240,height:72,x:18,y:130,color:'#fff',size:12,content:[{text:'描述：阿尔法围棋（AlphaGo）是一款围棋人工智能程序，由位于英国伦敦的谷歌旗下DeepMind公司的戴维·西尔弗、艾佳·黄和戴密斯·哈萨比斯与他们的团队开发。'}]},
        {type:'textarea',x:65,y:430,color:'#fff600',size:14,content:[
			{text:'长按此图，识别图中的二维码'}
		]}
    ]
};

var styleMod3={
    width:320,
    height:320,
    bg:{
        color:'#ffffff',
        head:{height:110,alpha:0.2,color:'#ffffff'},
        image:{src:(typeof _staticPath=='undefined'?'':_staticPath)+'hbimage/placeholder/style3.png'}
    },
    element:[
        {type:'avatar',x:50,y:15},
        {type:'textarea',x:140,y:20,color:'#fffff',size:18,content:[
            {text:'我是',color:'#ffffff',size:18},
            {text:'{$username}',color:'#fff600',size:18}
        ]},
        {type:'textarea',x:140,y:60,color:'#ffffff',size:14,content:[
            {text:'我为',color:'#fff600',size:14},
            {text:'{$company}',color:'#fff600',size:14},
            {text:'代言',color:'#fff600',size:14}
        ]},
        {type:'textarea',x:40,y:275,color:'#ffffff',size:14,content:[
            {text:'推广时间：{$start_time}至{$end_time}',color:'#ffffff',size:14}
        ]},
        {type:'qrcode',width:122,height:122,x:185,y:125},
        {type:'textarea',width:160,height:110,x:10,y:130,color:'#fff',size:12,content:[{text:'描述：阿尔法围棋（AlphaGo）是一款围棋人工智能程序，由位于英国伦敦的谷歌旗下DeepMind公司的戴维·西尔弗、艾佳·黄和戴密斯·哈萨比斯与他们的团队开发。'}]},
        {type:'textarea',x:65,y:250,color:'#fff600',size:14,content:[
			{text:'长按此图，识别图中的二维码'}
		]}
    ]
};


$(function(){
    var ogetHbStyle=$("#hbModStyle").val();
    if(ogetHbStyle==''){
        return false;
    }
    var onewStyle=JSON.parse(ogetHbStyle);


    if(onewStyle.height==256){
        styleMod1=onewStyle
    }else if(onewStyle.height==500){
        styleMod2=onewStyle
    }else{
        styleMod3=onewStyle
    }

});













var ctrlJson=styleMod1;

$(function(){
    $(".modStyle label").on("click",function(){
        dataValue=$(this).attr('data');
        if(dataValue=='style1'){
            ctrlJson=styleMod1
        }else if(dataValue=='style2'){
            ctrlJson=styleMod2
        }else{
            ctrlJson=styleMod3

        }
        var i=$(".modStyle label").index(this);
        $(".itemList .normalStyle").eq(i).attr('id','currentMod').siblings().attr('id','');

    }).eq(0).trigger('click');

    ctrlJson['element'][3]['display']=ctrlJson['element'][3]['display']?ctrlJson['element'][3]['display']:'1';
    changeTimeDisplay(ctrlJson['element'][3]['display']);
    $('[name=start_time_display]').click(function()
    {
        var timeDisplay=$('[name=start_time_display]:checked').val();
        changeTimeDisplay(timeDisplay);
    });

    ctrlJson['element'][6]['display']=ctrlJson['element'][6]['display']?ctrlJson['element'][6]['display']:'1';
    changeTipDisplay(ctrlJson['element'][6]['display']);
    $('[name=tip_display]').click(function()
    {
        var tipDisplay=$('[name=tip_display]:checked').val();
        changeTipDisplay(tipDisplay);
    });

});

function changeTimeDisplay(timeDisplay)
{
    if(timeDisplay=='1')
    {
        $('.descDate').show();
        $("[name=start_time_display][value='1']").prop('checked',true);
        var forId=$("[name=start_time_display][value='1']").prop('id');
        $("[for="+forId+"]").addClass('checkOn');
        ctrlJson['element'][3]['display']=1;
    }
    else
    {
        $('.descDate').hide();
        $("[name=start_time_display][value='0']").prop('checked',true);
        var forId=$("[name=start_time_display][value='0']").prop('id');
        $("[for="+forId+"]").addClass('checkOn');
        ctrlJson['element'][3]['display']=0;
    }
}

function changeTipDisplay(tipDisplay)
{
    if(tipDisplay=='1')
    {
        $('.descTip').show();
        $("[name=tip_display][value='1']").prop('checked',true);
        var forId=$("[name=tip_display][value='1']").prop('id');
        $("[for="+forId+"]").addClass('checkOn');
        ctrlJson['element'][6]['display']=1;
    }
    else
    {
        $('.descTip').hide();
        $("[name=tip_display][value='0']").prop('checked',true);
        var forId=$("[name=tip_display][value='0']").prop('id');
        $("[for="+forId+"]").addClass('checkOn');
        ctrlJson['element'][6]['display']=0;
    }
}


$(function(){

    //拖拽缩放设置
    $(".itemList .row").each(function() {
        var avatar = $(this).find(".avatar");
        var spanFor = $(this).find("p.for");
        var spanMe = $(this).find("p.me");
        var hdDescDate = $(this).find(".hd p.descDate");
        var bdDescDate = $(this).find(".bd p.descDate")
        var descText = $(this).find(".descText");
        var descTip = $(this).find(".descTip");
        var descCode = $(this).find(".descCode");

        var hd = $(this).find(".hd");
        var bd = $(this).find(".normalStyle");


        //按钮
        var styleAndCrtlBtn = $('<span class="styleAndCrtl"> <a class="ctrlFromBtn" href="javascript:;">编辑</a> <a  class="styleBtn" href="javascript:;">样式</a> </span>');
        var styleBtn = $('<span class="styleAndCrtl"> <a  class="styleBtn" href="javascript:;">样式</a> </span>');
        var recover=$("<a class='recover' href='javascript:;'>恢复</a>");


        //查询按钮和可点击按钮
        var moduleLayer = $(this).find(".moduleLayer");




        function styleAndCrtls(obj,btn) {
            obj.hover(function () {
                $(this).append(btn);
            }, function () {
                $(this).find(".styleAndCrtl").remove();
            });
        }



        styleAndCrtls(spanFor,styleAndCrtlBtn);
        styleAndCrtls(spanMe,styleAndCrtlBtn);
        styleAndCrtls(hdDescDate,styleAndCrtlBtn);
        styleAndCrtls(bdDescDate,styleAndCrtlBtn);
        styleAndCrtls(descText,styleAndCrtlBtn);
        styleAndCrtls(descTip,styleBtn);
        //styleAndCrtls(descCode,styleAndCrtlBtn);

        //spanFor.hover(function(){
        //   $(this).append(styleAndCrtl);
        //},function(){
        //    $(this).find(".styleAndCrtl").remove();
        //}
        //);
        //


        avatar.draggable({ containment: hd, scroll: false,
                stop:function(){
					var left=$(this).css('left');
					var top=$(this).css('top');
                    ctrlJson.element[0].x=parseInt(left);
                    ctrlJson.element[0].y=parseInt(top);
                }
            });


        spanFor.draggable({ containment: hd, scroll: false,
            stop:function(){
                ctrlJson.element[2].x=parseInt($(this).css('left'));
                ctrlJson.element[2].y=parseInt($(this).css('top'));
            }
        });
        spanMe.draggable({ containment: hd, scroll: false,
            stop:function(){
                ctrlJson.element[1].x=parseInt($(this).css('left'));
                ctrlJson.element[1].y=parseInt($(this).css('top'));

            }
        });
        hdDescDate.draggable({ containment: hd, scroll: false,
            stop:function(){
                ctrlJson.element[3].x=parseInt($(this).css('left'));
                ctrlJson.element[3].y=parseInt($(this).css('top'));

            }
        });
        bdDescDate.draggable({ containment: bd, scroll: false,
            stop:function(){
                ctrlJson.element[3].x=parseInt($(this).css('left'));
                ctrlJson.element[3].y=parseInt($(this).css('top'));

            }
        });

        descText.resizable({
            stop:function(){
                ctrlJson.element[5].width=parseInt($(this).css('width'));
                ctrlJson.element[5].height=parseInt($(this).css('height'));

                $(this).append(recover);

            }
        });


        //恢复原来二维码大小
        var descTextw= descText.css('width');
        var descTexth= descText.css('height');

        descText.parent().on('click','.recover',function(){
            $(this).parent().css({'width':descTextw,'height':descTexth});
            $(this).parent().find('.descText').css({'width':descTextw,'height':descTexth});
            ctrlJson.element[5].width=parseInt(descTextw);
            ctrlJson.element[5].height=parseInt(descTexth);
            $(this).remove();
        });


        descText.draggable({ containment: bd, scroll: false,
            stop:function(){
                ctrlJson.element[5].x=parseInt($(this).css('left'));
                ctrlJson.element[5].y=parseInt($(this).css('top'));

            }
        });
        descTip.draggable({ containment: bd, scroll: false,
            stop:function(){
                ctrlJson.element[6].x=parseInt($(this).css('left'));
                ctrlJson.element[6].y=parseInt($(this).css('top'));

            }
        });



        descCode.resizable({
            stop:function(){
                ctrlJson.element[4].width=parseInt($(this).css('width'));
                ctrlJson.element[4].height=parseInt($(this).css('height'));

                $(this).append(recover);
            }
        });

        //恢复原来二维码大小
        var descCodew= descCode.css('width');
        var descCodeh= descCode.css('height');

        descCode.parent().on('click','.recover',function(){
            $(this).parent().css({'width':descCodew,'height':descCodeh});
            $(this).parent().find('.descCode').css({'width':descCodew,'height':descCodeh});
            ctrlJson.element[4].width=parseInt(descCodew);
            ctrlJson.element[4].height=parseInt(descCodeh);

            $(this).remove();
        });

        descCode.parent().draggable({ containment: bd, scroll: false,
            stop:function(){
                ctrlJson.element[4].x=parseInt($(this).css('left'));
                ctrlJson.element[4].y=parseInt($(this).css('top'));
            }
        });

    });



});


//窗口处理
$(function(){

    var itemContent=$("#itemContent");
	var sy1=itemContent.find(".style1")
	var sy2=itemContent.find(".style2")
	var sy3=itemContent.find(".style3")

    var moduleLayer=itemContent.find(".moduleLayerBox");

    var spanFor = itemContent.find("p.for");
    var spanMe = itemContent.find("p.me");
    var descDate = itemContent.find(".descDate");
    var descText = itemContent.find(".descText");
    var descTip = itemContent.find(".descTip");
    var descCode =itemContent.find(".descCode");
    var opacityHd = itemContent.find(".opacityHd");



    //窗口显示
    function showWindow(target){
        $(target).css('visibility','visible');
        $("body").css("overflow","hidden");
    }
    //关闭窗口
    $('.editPicClose').click(function(e){
        $('.setWindow').css('visibility','hidden');
        $("body").css("overflow","auto");
    });


//
    moduleLayer.on("click", '.bgTool',function(){
        showWindow("#uploadImg");
    });
    moduleLayer.on("click", '.hdTool',function(){
        showWindow("#headerStyle");
    });
    itemContent.on("click",'.styleBtn',function(){
        showWindow("#TextStyle");
    });


    //默认user样式
    var userColor=spanMe.css('color');
    var userNameColor=spanMe.find('em').css('color');
    var userSize=spanMe.css('font-size');
    //默认corp样式
    var corpColor=spanFor.css('color');
    var corpNameColor=spanFor.find('em').css('color');
    var corpSize=spanFor.css('font-size');
    //默认desc样式
    var descColor=descText.css('color');
    var descSize=descText.css('font-size');
    //默认desc样式
    var descTipColor=descTip.css('color');
    var descTipSize=descTip.css('font-size');
    //默认desc样式
    var descDateColor=descDate.css('color');
    var descDateSize=descDate.css('font-size');
    //默认desc样式
    var hdColor=opacityHd.css('background-color');
    var hdOpacity=opacityHd.css('opacity');




    //字号大小拉条
    $( ".slider-range-0" ).slider({
        range: "min",
        value: 18,
        min: 10,
        max: 48,
        slide: function( event, ui ) {
            $(this).next().text( ui.value+"px" );
            spanMe.css('font-size', ui.value+"px");
            styleMod1.element[1].size=ui.value;
            styleMod2.element[1].size=ui.value;
            styleMod3.element[1].size=ui.value;
        }
    });
    $( ".slider-range-1" ).slider({
        range: "min",
        value: 14,
        min: 10,
        max: 48,
        slide: function( event, ui ) {
            $(this).next().text( ui.value+"px" );
            spanFor.css('font-size', ui.value+"px");
            styleMod1.element[2].size=ui.value;
            styleMod2.element[2].size=ui.value;
            styleMod3.element[2].size=ui.value;

        }
    });
    $( ".slider-range-2" ).slider({
        range: "min",
        value: 12,
        min: 10,
        max: 48,
        slide: function( event, ui ) {
            $(this).next().text( ui.value+"px" );
            descText.css('font-size', ui.value+"px");
            styleMod1.element[5].size=ui.value;
            styleMod2.element[5].size=ui.value;
            styleMod3.element[5].size=ui.value;
        }
    });
    $( ".slider-range-3" ).slider({
        range: "min",
        value: 14,
        min: 10,
        max: 48,
        slide: function( event, ui ) {
            $(this).next().text( ui.value+"px" );
            descTip.css('font-size', ui.value+"px");
            styleMod1.element[6].size=ui.value;
            styleMod2.element[6].size=ui.value;
            styleMod3.element[6].size=ui.value;
        }
    });
    $( ".slider-range-4" ).slider({
        range: "min",
        value: 14,
        min: 10,
        max: 48,
        slide: function( event, ui ) {
            $(this).next().text( ui.value+"px" );
            descDate.css('font-size', ui.value+"px");
            styleMod1.element[3].size=ui.value;
            styleMod2.element[3].size=ui.value;
            styleMod3.element[3].size=ui.value;

        }
    });
    $( ".slider-range-5" ).slider({
        range: "min",
        value: 50,
        min: 0,
        max: 100,
        slide: function( event, ui ) {
            $(this).next().text( ui.value+"%" );
            opacityHd.css('opacity', ui.value/100);
            styleMod1.bg.head.alpha=ui.value/100;
            styleMod2.bg.head.alpha=ui.value/100;
            styleMod3.bg.head.alpha=ui.value/100;
        }
    });
    $( "#userStyle .userSizeTip" ).text( $( ".slider-range-0" ).slider( "value" )+"px" );
    $( "#corpStyle .userSizeTip" ).text( $( ".slider-range-1" ).slider( "value" )+"px" );
    $( "#descStyle .userSizeTip" ).text( $( ".slider-range-2" ).slider( "value" )+"px" );
    $( "#descTipStyle .userSizeTip" ).text( $( ".slider-range-3" ).slider( "value" )+"px" );
    $( "#descDateStyle .userSizeTip" ).text( $( ".slider-range-4" ).slider( "value" )+"px" );
    $( "#opacityStyle .userSizeTip" ).text( $( ".slider-range-5" ).slider( "value" )+"%" );





    //用户名称颜色
    $("#userColor").bigColorpicker(function(el,color){
        $(el).css("background-color",color);
        spanMe.css({'color':color});
        styleMod1.element[1].color=color;
        styleMod2.element[1].color=color;
        styleMod3.element[1].color=color;
    });
    $("#userNameColor").bigColorpicker(function(el,color){
        $(el).css("background-color",color);
        spanMe.find("em").css({'color':color});
        styleMod1.element[1].content[1].color=color;
        styleMod2.element[1].content[1].color=color;
        styleMod3.element[1].content[1].color=color;
    });
    $("#corpColor").bigColorpicker(function(el,color){
        $(el).css("background-color",color);
        spanFor.css({'color':color});
        styleMod1.element[2].color=color;
        styleMod2.element[2].color=color;
        styleMod3.element[2].color=color;
    });
    $("#corpNameColor").bigColorpicker(function(el,color){
        $(el).css("background-color",color);
        spanFor.find("em").css({'color':color});
        styleMod1.element[2].content[1].color=color;
        styleMod2.element[2].content[1].color=color;
        styleMod3.element[2].content[1].color=color;
    });

    $("#descColor").bigColorpicker(function(el,color){
        $(el).css("background-color",color);
        descText.css({'color':color});
        styleMod1.element[5].color=color;
        styleMod2.element[5].color=color;
        styleMod3.element[5].color=color;
    });
    $("#descTipColor").bigColorpicker(function(el,color){
        $(el).css("background-color",color);
        descTip.css({'color':color});
        styleMod1.element[6].color=color;
        styleMod2.element[6].color=color;
        styleMod3.element[6].color=color;
    });
    $("#descDateColor").bigColorpicker(function(el,color){
        $(el).css("background-color",color);
        descDate.css({'color':color});
        styleMod1.element[3].color=color;
        styleMod2.element[3].color=color;
        styleMod3.element[3].color=color;
    });
    $("#hdColor").bigColorpicker(function(el,color){
        $(el).css("background-color",color);
        opacityHd.css({'background-color':color});
        styleMod1.bg.head.color=color;
        styleMod2.bg.head.color=color;
        styleMod3.bg.head.color=color;
    });



    //显示颜色控件
    $("#userColorCustom,#userNameColorCustom,#corpColorCustom,#corpNameColorCustom,#descColorCustom,#descTipColorCustom,#descDateColorCustom,#hdColorCustom").click(function(){
        $(this).parent().find(".colorPicker").css('display','block');
    });
    //关闭颜色控件
    $("#userColorDefault,#userNameColorDefault,#corpColorDefault,#corpNameColorDefault,#descColorDefault,#descTipColorDefault,#descDateColorDefault,#hdColorDefault").click(function(){
        $(this).parent().find(".colorPicker").css('display','none');
    });


    //默认用户名字颜色
    $("#userColorDefault").click(function(){
        spanMe.css({'color':userColor});
        styleMod1.element[1].color=userColor;
        styleMod2.element[1].color=userColor;
        styleMod3.element[1].color=userColor;
    });
    $("#corpColorDefault").click(function(){
        spanFor.css({'color':corpColor});
        styleMod1.element[2].color=corpColor;
        styleMod2.element[2].color=corpColor;
        styleMod3.element[2].color=corpColor;
    });
    //默认用户名字高亮
    $("#userNameColorDefault").click(function(){
        spanMe.find("em").css({'color':userNameColor});
        styleMod1.element[1].content[1].color=userNameColor;
        styleMod2.element[1].content[1].color=userNameColor;
        styleMod3.element[1].content[1].color=userNameColor;

    });
    $("#corpNameColorDefault").click(function(){
        spanFor.find("em").css({'color':corpNameColor});
        styleMod1.element[2].content[1].color=corpNameColor;
        styleMod2.element[2].content[1].color=corpNameColor;
        styleMod3.element[2].content[1].color=corpNameColor;

    });
    //描述内容颜色
    $("#descColorDefault").click(function(){
        descText.css({'color':descColor});
        styleMod1.element[5].color=descColor;
        styleMod2.element[5].color=descColor;
        styleMod3.element[5].color=descColor;

    });
    //描述提示颜色
    $("#descTipColorDefault").click(function(){
        descTip.css({'color':descTipColor});
        styleMod1.element[6].color=descTipColor;
        styleMod2.element[6].color=descTipColor;
        styleMod3.element[6].color=descTipColor;

    });
    //描述时间颜色
    $("#descDateColorDefault").click(function(){
        descDate.css({'color':descDateColor});
        styleMod1.element[3].color=descDateColor;
        styleMod2.element[3].color=descDateColor;
        styleMod3.element[3].color=descDateColor;

    });
    //头部背景色
    $("#hdColorDefault").click(function(){
        opacityHd.css({'background-color':hdColor});
        styleMod1.bg.head.color=hdColor;
        styleMod2.bg.head.color=hdColor;
        styleMod3.bg.head.color=hdColor;
    });


    //显示编辑user名字
    $("#userTextCustom").on('click',function(){
        $(this).parent().next().show();
    });
    $("#corpTextCustom").on('click',function(){
        $(this).parent().next().show();
    });
    //显示编辑desc名字
    $("#descTextCustom").on('click',function(){
        $(this).parent().next().show();
    });
    //显示编辑descTipr名字
    $("#descTipTextCustom").on('click',function(){
        $(this).parent().next().show();
    });
    //显示编辑descDate名字
    $("#descDateTextCustom").on('click',function(){
        $(this).parent().next().show();
    });
    //显示编辑descDate名字
    $("#hdCustom").on('click',function(){
        $(this).parent().next().show();
    });

    //关闭user名字编辑回到默认
    $("#userTextDefault").on('click',function(){
        spanMe.css({'font-size':userSize,'color':userColor});
        spanMe.find("em").css({'color':userNameColor});
        styleMod1.element[1].color=userColor;
        styleMod1.element[1].size=userSize;
        styleMod1.element[1].content[1].color=userNameColor;
        styleMod2.element[1].color=userColor;
        styleMod2.element[1].size=userSize;
        styleMod2.element[1].content[1].color=userNameColor;
        styleMod3.element[1].color=userColor;
        styleMod3.element[1].size=userSize;
        styleMod3.element[1].content[1].color=userNameColor;
        $(this).parent().next().hide();
    });
    $("#corpTextDefault").on('click',function(){
        spanFor.css({'font-size':corpSize,'color':corpColor});
        spanFor.find("em").css({'color':corpNameColor});
        styleMod1.element[1].color=corpColor;
        styleMod1.element[1].size=corpSize;
        styleMod1.element[1].content[1].color=corpNameColor;
        styleMod2.element[1].color=corpColor;
        styleMod2.element[1].size=corpSize;
        styleMod2.element[1].content[1].color=corpNameColor;
        styleMod3.element[1].color=corpColor;
        styleMod3.element[1].size=corpSize;
        styleMod3.element[1].content[1].color=corpNameColor;
        $(this).parent().next().hide();
    });
    //关闭desc编辑回到默认
    $("#descTextDefault").on('click',function(){
        descText.css({'font-size':descSize,'color':descColor});
        styleMod1.element[5].color=descColor;
        styleMod1.element[5].size=descSize;
        styleMod2.element[5].color=descColor;
        styleMod2.element[5].size=descSize;
        styleMod3.element[5].color=descColor;
        styleMod3.element[5].size=descSize;
        $(this).parent().next().hide();
    });
    //关闭descTip编辑回到默认
    $("#descTipTextDefault").on('click',function(){
        descTip.css({'font-size':descTipSize,'color':descTipColor});
        styleMod1.element[6].color=descTipColor;
        styleMod1.element[6].size=descTipSize;
        styleMod2.element[6].color=descTipColor;
        styleMod2.element[6].size=descTipSize;
        styleMod3.element[6].color=descTipColor;
        styleMod3.element[6].size=descTipSize;
        $(this).parent().next().hide();
    });
    //关闭descDate编辑回到默认
    $("#descDateTextDefault").on('click',function(){
        descDate.css({'font-size':descDateSize,'color':descDateColor});
        styleMod1.element[3].color=descDateColor;
        styleMod1.element[3].size=descDateSize;
        styleMod2.element[3].color=descDateColor;
        styleMod2.element[3].size=descDateSize;
        styleMod3.element[3].color=descDateColor;
        styleMod3.element[3].size=descDateSize;
        $(this).parent().next().hide();
    });
    //关闭头部编辑回到默认
    $("#hdDefault").on('click',function(){
        opacityHd.css({'opacity':hdOpacity,'background-color':hdColor});
        styleMod1.bg.head.color=hdColor;
        styleMod1.bg.head.alpha=hdOpacity;
        styleMod2.bg.head.color=hdColor;
        styleMod2.bg.head.alpha=hdOpacity;
        styleMod3.bg.head.color=hdColor;
        styleMod3.bg.head.alpha=hdOpacity;
        $(this).parent().next().hide();
    });





//    $("#actNamePut input").bind("input propertychange",function(){
 //       spanMe.find("em").text($(this).val());
 //       styleMod1.element[1].content.text='{$username}';
 //       styleMod2.element[1].content.text='{$username}';
 //       styleMod3.element[1].content.text='{$username}';

//    });
    $("#corpNamePut input").bind("input propertychange",function(){
        spanFor.find("em").text($(this).val());
        styleMod1.element[2].content.text='{$company}';
        styleMod2.element[2].content.text='{$company}';
        styleMod3.element[2].content.text='{$company}';

    });
    $("#descTextPut textarea").bind("input propertychange",function(){
        descText.find('p').text($(this).val());
        styleMod1.element[5].content[0].text='{$desc}';
        styleMod2.element[5].content[0].text='{$desc}';
        styleMod3.element[5].content[0].text='{$desc}';
    });


    //兑换说明，兑换期限
    $("#promotion .start").bind('change',function(){
        var timeText=$(this).val().substring(0,10);
        descDate.find(".start").text(timeText);
        styleMod1.element[3].content[0].text="推广时间：{$start_time}至{$end_time}";
        styleMod2.element[3].content[0].text="推广时间：{$start_time}至{$end_time}";
        styleMod3.element[3].content[0].text="推广时间：{$start_time}至{$end_time}";
    });
    $("#promotion .end").bind('change',function(){
        var timeText=$(this).val().substring(0,10);
        descDate.find(".end").text(timeText);
        styleMod1.element[3].content[0].text="推广时间：{$start_time}至{$end_time}";
        styleMod2.element[3].content[0].text="推广时间：{$start_time}至{$end_time}";
        styleMod3.element[3].content[0].text="推广时间：{$start_time}至{$end_time}";
    });

	$('.bgimg_view').bind("upload_complete",function(e,data){
		styleMod1.bg.image.src=data["400_256"];
		styleMod2.bg.image.src=data["320_500"];
		styleMod3.bg.image.src=data["320_320"];
		
		sy1.css("background-image",'url('+data["400_256"]+')');
		sy2.css("background-image",'url('+data["320_500"]+')');
		sy3.css("background-image",'url('+data["320_320"]+')');
	});
	


    spanMe.on('click','.ctrlFromBtn',function(){
        $("#actNamePut").addClass("checkBorder");
    });
    spanFor.on('click','.ctrlFromBtn',function(){
        $("#actNamePut").addClass("checkBorder");
    });
    descText.on('click','.ctrlFromBtn',function(){
        $("#descTextPut").addClass("checkBorder");
    });
    descDate.on('click','.ctrlFromBtn',function(){
        $("#promotion").addClass("checkBorder");
    });
});


var myFun = {
    //计算每行最后一个，清除每行最后一个的margin
    rowlastLi: function(a, b) {
        $(a).each(function() {
            var li = $(this).find("ul>li");
            var len = $(this).find("ul>li").length;
            var y = len / b;
            for (var i = 1; i <= y; i++) {
                li.eq(i * b - 1).css({
                    'margin-right': '0'
                });
            }
        })
    },
    //tab切换一个参数
    tab: function(obj) {
        var tabObj = $(obj);
        tabObj.each(function() {
            var len = $(this).find('.hd ul li');
            var row = $(this).find('.row');
            len.bind("click", function() {
                var index = 0;
                $(this).addClass('on').siblings().removeClass('on');
                index = len.index(this);
                row.eq(index).show().siblings(".row").hide();
                return false;
            }).eq(0).trigger("click");
        });
    },
    //tab切换一个参数
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
                return false;
            }).eq(0).trigger("click");
        });
    },
    //tab切换三个参数
    tabs: function(a, b, c) {
        var len = $(a);
        len.on("click", function() {
            var index = 0;
            $(this).addClass(c).siblings().removeClass(c);
            index = len.index(this);
            $(b).eq(index).show().siblings().hide();
            return false;
        }).eq(0).trigger("click");
    },
    //清楚最后一个li的border
    lastLi: function(a) {
        $(a).find("li").last().css('borderBottom', '0');
    },
    //清楚最后一个li的margin-right
    lastLimr: function(a) {
        $(a).find("li").last().css('marginRight', '0');
    },

    //设置相对屏幕（不是整个文档的）的top值
    marginTop: function(a) {
        var wHeight = $(window).height();
        var boxHeight = $(a).height();
        //var scrollTop = $(window).scrollTop();
        var top = (wHeight - boxHeight) / 2;
        $(a).css('marginTop', top);
    },
    animate: function (sum){
        var t = $(window).scrollTop();
        var h = $(window).height();
        for(var i = 1; i < sum + 1; i ++){
            var off = $('.floor' + i).offset().top + 100;
            if(t + h > off){
                $('.floor' + i).addClass('animate');
            };
        };
    }
};
