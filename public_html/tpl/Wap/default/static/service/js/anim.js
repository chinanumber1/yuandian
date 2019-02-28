$(document).ready(function(){
    if($('#main_panes').length>0){
        var animFun1=new animFun();
        animFun1.init({
            "touchId":"main_panes",
            "swipPages":".panes-page",
            "curIndex":0,
            "direction":Hammer.DIRECTION_HORIZONTAL,
            "tabWrap":".step-tab1",
            "tabcell":".step-li",
            "panEndback":function(TfThis,args){
                $('.ico-swipe-tips').remove();
                TfThis.swipTotal=$("#main_panes .panes-page").length;
                console.log(TfThis.curIndex);
                if(TfThis.swipTotal-TfThis.curIndex<=2&&TfThis.swipTotal-TfThis.curIndex>0){
                    var ajaxUrl=$("#main_panes").attr("ajaxUrl");
                    var getId=TfThis.swipTotal+1;
                    //var sNo=TfThis.swipTotal%4;
                    //var sNo=2;
                    var lastdataId=$("#main_panes .panes-page").last().attr("id");
                    if($("#main_panes").attr("ajaxState")!="false"){
                        $("#main_panes").attr("ajaxState","false");
                        $.ajax({
                                url: ajaxUrl,
                                type: 'post',
                                dataType: 'html',
                                data:{"id":lastdataId},
                                success: function(data){
                                    $("#main_panes").attr("ajaxState",'true');
                                    if(data!=""){
                                        $('#main_panes .panes-page').last().after(data);
                                        var sNo=$('#main_panes .panes-page').last().attr("state");
                                        $(".step-tab1 .step-li").last().after('<span class="step-li s'+sNo+' hidden"><i>'+getId+'</i></span>');
                                        //样式初始化
                                        TfThis.styleInit(args);
                                        if(TfThis.swipTotal-TfThis.curIndex==1){
                                            TfThis.swipTotal=$("#main_panes .panes-page").length;
                                            stepTabFun();//.step-tab1 set
                                        }
                                    }else{
                                        $("#main_panes").attr("ajaxEnd","true");
                                    }
                                }
                            });
                    }

                }
                function stepTabFun(){
                    var stepTab1W=$(".step-tab1").width();
                    var stepLiW=$(".step-tab1 .step-li").width()+parseFloat($(".step-tab1 .step-li").css("margin-left"));
                    var stepLiLoop=parseInt(stepTab1W/stepLiW)-1;
                    var curIndex=$(".step-tab1 .step-li.cur").index();
                    $('.step-tab1 .step-li').removeClass('hidden');
                    if(curIndex>=stepLiLoop&&curIndex<(TfThis.swipTotal)){
                        if(curIndex==TfThis.swipTotal-1){
                            $('.step-tab1 .step-li:lt('+(curIndex-stepLiLoop)+')').addClass('hidden');
                        }else{
                            $('.step-tab1 .step-li:gt('+(curIndex+1)+')').addClass('hidden');
                            $('.step-tab1 .step-li:lt('+(curIndex-stepLiLoop+1)+')').addClass('hidden');
                        }
                    }else{
                        $('.step-tab1 .step-li:gt('+(stepLiLoop)+')').addClass('hidden');
                    }
                    if(curIndex==TfThis.swipTotal-1&&$("#main_panes").attr("ajaxEnd")=="true"){
                        //小提示层;
                        validatePop({
                            "popconMsg":"已经全部显示"
                        });
                    }

                }
                stepTabFun();//.step-tab1 set

            }
        });
    }

});

var $el=$("#main");
var animFun=function(){
    this.touchObj=null;
    this.touchObjJQ=null;
    this.swipD=null;
    this.group=null;
    this.curIndex=0;
    this.translateD=0;
    this.totalW=0;
    this.swipTotal=0;
}
animFun.prototype={
    init:function(args){
        var TfThis=this;
        TfThis.touchObjId=document.getElementById(args.touchId);
        TfThis.touchObjJQ=$('#'+args.touchId);
        TfThis.swipD= $(TfThis.touchObjJQ).width();
        TfThis.swipTotal=$('#'+args.touchId+' '+args.swipPages).length;


        $(".step-tab1 .step-li").each(function(index, el) {
            var  stepliIndex=$(this).index();
            var  sNum=stepliIndex%4;
            //$(this).addClass('s'+sNum);
        });
        $(".step-tab1 .step-li:eq(0)").addClass('cur');
        $("#main_panes .panes-page").each(function(index, el) {
/*          var  stepliIndex=$(this).index();
            var  sNum=stepliIndex%4;
            $('.step-box-wrap',$(this)).addClass('step-s'+sNum);*/
            var sNum=$(this).attr('state');
            var index=$(this).index();
            $('.step-tab1 .step-li:eq('+index+')').addClass('s'+sNum);
        });
        //样式初始化
        TfThis.styleInit(args);
        $(TfThis.touchObjJQ).attr("curDeltaD",TfThis.translateD);
        var hm=new Hammer(TfThis.touchObjId);
        //var pan = new Hammer.Pan({ direction:args.direction, threshold: 10 });
        var pan = new Hammer.Pan({ direction:Hammer.DIRECTION_ALL, threshold: 10 });
        var tap = new Hammer.Tap({ time:500, threshold:5});
        hm.add(pan);
        hm.add(tap);
        tap.requireFailure(pan);
        hm.on('pan',function(eve){
            TfThis.panFun(args,eve);
        })
        .on('panend pancancel',function(eve){
            TfThis.panEndFun(args,eve);
        })
        .on("tap", function(ev){
            $(ev.target).trigger("click");
        })
        if(args.animCallback!= undefined) {
            args.animCallback.call("null",TfThis,args);
        }
        if(args.tabWrap&&args.tabcell){
            $(".pagewrap").on("click",args.tabWrap+' '+args.tabcell,function(event) {
                TfThis.curIndex=$(this).index();
                TfThis.showFun(TfThis.curIndex,args);
                console.log(TfThis.curIndex);
            });
        }
    },
    styleInit:function(args){
        var TfThis=this;
        stepBoxSet();//需求状态样式
        $(args.swipPages).css({"width":TfThis.swipD+"px"});
        TfThis.totalW=TfThis.swipD*$(args.swipPages).length;
        TfThis.touchObjJQ.css({"width":TfThis.totalW+"px"});
    },
    panEndFun:function(args,eve){
        var TfThis=this;
        if(args.direction & Hammer.DIRECTION_HORIZONTAL){
            TfThis.curIndex += (eve.deltaX < 0) ? 1 : -1;
        }
        if(args.direction & Hammer.DIRECTION_VERTICAL){
            TfThis.curIndex += (eve.deltaY < 0) ? 1 : -1;
        }
        TfThis.showFun(TfThis.curIndex,args);
    },
    showFun:function(showIndex,args){
        var TfThis=this;
        if(TfThis.curIndex<0){
            TfThis.curIndex=0;
        }
        if(TfThis.curIndex>=TfThis.swipTotal){
            TfThis.curIndex=TfThis.swipTotal-1;
        }

        TfThis.translateD=TfThis.curIndex*TfThis.swipD;
        if(args.direction & Hammer.DIRECTION_HORIZONTAL) {
            TfThis.touchObjJQ.addClass("animate").css({'transform' : 'translate3d(-' + TfThis.translateD + 'px,0,0)'});
        }else{
            /*DIRECTION_VERTICAL*/
            TfThis.touchObjJQ.addClass("animate").css({'transform' : 'translate3d(0, -' + TfThis.translateD + 'px, 0)'});
        }

        $(args.tabWrap+' '+args.tabcell).removeClass("cur");
        $(args.tabWrap+' '+args.tabcell+':eq('+TfThis.curIndex+')').addClass("cur");
        if(args.panEndback!= undefined) {
            args.panEndback.call("null",TfThis,args);
        }
    },
    panFun:function(args,eve){
        var TfThis=this;
        if(args.direction & Hammer.DIRECTION_HORIZONTAL) {
            var deltaD=eve.deltaX;
        }else{
            /*DIRECTION_VERTICAL*/
            var deltaD=eve.deltaY;
        }
        if(Math.abs(deltaD)<=TfThis.swipD*0.3){
            var curTranslateD=-TfThis.translateD+deltaD;
            if(args.direction & Hammer.DIRECTION_HORIZONTAL) {
                this.touchObjJQ.addClass("animate").css({'transform' : 'translate3d(' + curTranslateD + 'px,0, 0)'});
            }else{
                /*DIRECTION_VERTICAL*/
                this.touchObjJQ.addClass("animate").css({'transform' : 'translate3d(0, ' + curTranslateD + 'px, 0)'});
            }
            $(TfThis.touchObjJQ).attr("curDeltaD",curTranslateD);
        }
    }

}
//需求状态样式
function stepBoxSet(){
    if($(".step-box-wrap").length>0){
        var ele=$(".step-box-wrap");
        var winW=$(window).width();
        var winH=$(window).height();
        var eleW=winW*0.764;
        var eleH=eleW*614/489;
        $(ele).css({"height":eleH+"px"});
        $(".main").css({"height":""});
        var playliW=eleW*0.2625*0.95;
        var playliM=eleW*0.02*0.95;
        $('.dm-cell .dm-li2 .playli').css({"width":playliW+"px","padding-left":playliM+"px","padding-right":playliM+"px"});
        var headH=playliW*0.8;
        $('.dm-cell .dm-li2 .playli .user_head').css({'width':headH+'px','height':headH+'px'});
    }
}