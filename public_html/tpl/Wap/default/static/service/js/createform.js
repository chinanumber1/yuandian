$(document).ready(function() {
    new createformFun();
/*    for(var i=0;i<demandForm_Arr.length;i++){
        if(demandForm_Arr[i].type=='address'){
            alert(demandForm_Arr[i].value);
            alert(demandForm_Arr[i].value.length);
        }
    }*/

});
//发布需求统计
function welog_need_input(formEle){
    //timeline
    var timeline=[];
        if(typeof(service_start_time)!='undefined'){
            service_start_obj={'server_time':service_start_time,'client_time':new Date().getTime(),'action':'start'}
            timeline.push(JSON.stringify(service_start_obj));
        }
    var cate_id=$('input[name="cate_id"]',formEle).val();

    $(document).off('click','#publish_demand_form :input');
    $(document).on('click','#publish_demand_form :input',function(event){
        var thisId=$(this).attr('id');
        var _this=$(this);
        var event_type='question';
        var _thisLi=$(this).closest('.li');
        var question_name=$(':input',_thisLi).attr('name');
        var step=$('.li',formEle).index(_thisLi)+1;
        //图片
        if($(_this).attr('type')=='file'){
            question_name=$('.multiple_field_name',_thisLi).attr('name');
        }
        if($(_this).attr('id')=='js_publish_demand_prev'){
            event_type='prev';
            question_name=$('.li.show :input',formEle).attr('name');
            step=$('.li',formEle).index($('.li.show'))+1;
        }
        if($(_this).attr('id')=='js_publish_demand_next'){
            event_type='next';
            question_name=$('.li.show :input',formEle).attr('name');
            step=$('.li',formEle).index($('.li.show'))+1;
        }

        //timeline
        if(thisId!='js_publish_demand_submit'){
            oper_obj={'client_time':new Date().getTime(),'action':event_type,'name':question_name,'step':step}
            timeline.push(JSON.stringify(oper_obj));
        }
        //console.log(thisId);
        //welog
        welog('need_input','click',{
            'question_name':question_name,
            'cate_id':cate_id,
            'step':step,
            'event_type':event_type
        });
        //console.log('input');
        //console.log(oper_obj);
    });
    //地址
    $(document).off('focus','#publish_demand_form .js_coordinate_address');
    $(document).on('focus','#publish_demand_form .js_coordinate_address',function(event){
        var _this=$(this);
        var event_type='question';
        var _thisLi=$(this).closest('.li');
        var question_name=$(':input',_thisLi).attr('name');
        var step=$('.li',formEle).index(_thisLi)+1;
        //timeline
        oper_obj={'client_time':new Date().getTime(),'action':event_type,'name':question_name,'step':step}
        timeline.push(JSON.stringify(oper_obj));
        //welog
        welog('need_input','click',{
            'question_name':question_name,
            'cate_id':cate_id,
            'step':step,
            'event_type':event_type
        });
        //console.log('js_coordinate_address');
        //console.log(oper_obj);
    });
    //submit
    $("#publish_demand_form").on("submit",function(){
        var errLen=$("#publish_demand_form .error").length;
        var event_type='submit';
        var question_name='';
        var step=$('.li',formEle).length;
        if(errLen==0){
            //timeline
            oper_obj={'client_time':new Date().getTime(),'action':event_type,'name':question_name,'step':step}
            timeline.push(JSON.stringify(oper_obj));
            $('#js_timeline').val('['+timeline.join(',')+']');
        }
        //welog
        welog('need_input','click',{
            'question_name':question_name,
            'cate_id':cate_id,
            'step':step,
            'event_type':event_type
        });
        //console.log('submit');
        //console.log(oper_obj);
    });
}
 /**
 * 给定百分比，获得减速的百分比
 * @param x 给定的百分比数量
 * @returns {number} 显示的百分比数量
 */
var get_display_percent = function(x) {
    var a = 10;//加速度系数
    var lg_num = 2;//根号2
    return  a * Math.exp(1/lg_num*Math.log(x));
}
//创建表单
function createformFun(){
    var TfThis=this;
    $(".js_form").each(function(index, el) {
       TfThis.init($(this));
    });
}
var address_num = 0;
createformFun.prototype={
    formEle:null,
    formInnerEle:null,
    formDataArr:[],
    formHtmlTpl:null,
    formStyle:0,
    init:function(formEle){
        var isIPHONE = navigator.userAgent.toUpperCase().indexOf("IPHONE")!= -1;
        var isAndroid = navigator.userAgent.toUpperCase().indexOf("ANDROID")!= -1;

        var TfThis=this;
        TfThis.formEle=formEle;
        TfThis.formInnerEle=$(".js_formInner",formEle);
        TfThis.formDataArr=$(formEle).attr("formDataArr");
        TfThis.formDataArr=TfThis.formDataArr ? eval(TfThis.formDataArr) :[];
        TfThis.formHtmlTpl="";
        TfThis.formStyle= $(TfThis.formEle).attr('formStyle') ? parseInt($(TfThis.formEle).attr('formStyle')) : 0;
        var curRadiusDataTpl='';
        var formvalArrTpl='';
        for(var i=0;i<TfThis.formDataArr.length;i++){
            var curEleData=TfThis.formDataArr[i];
            //console.log(curEleData.type);
            var curEleDataTpl="";
            var valArrTpl='';
            switch(curEleData.type){
                case "checkbox":
                    if(curEleData.levelStyle==1){
                        curEleDataTpl= TfThis.radioTpl(curEleData);
                    }else{
                        curEleDataTpl= TfThis.checkboxTpl(curEleData);
                    }

                    break;
                case "radio":
                    curEleDataTpl= TfThis.radioTpl(curEleData);
                    break;
                case "text":
                    curEleDataTpl= TfThis.textTpl(curEleData);
                    break;
                case "price":
                    curEleDataTpl= TfThis.textTpl(curEleData);
                    break;
                case "select":
                    //1:select=>dataArrayTpl,0:select=>radioTpl
                    switch(TfThis.formStyle){
                        case 0:
                            curEleDataTpl= TfThis.radioTpl(curEleData);
                            break;
                        case 1:
                            curEleDataTpl= TfThis.selectTpl(curEleData);
                            valArrTpl=TfThis.ValTpl(curEleData);//valArray
                            break;
                    }
                    break;
                case "duration":
                    curEleDataTpl= TfThis.durationTpl(curEleData);
                    valArrTpl=TfThis.ValLv2Tpl(curEleData);//valArray
                    break;
                case "textArea":
                    curEleDataTpl= TfThis.textAreaTpl(curEleData);
                    break;
                case "email":
                    curEleDataTpl= TfThis.emailTpl(curEleData);
                    break;
                case "date":
                    curEleDataTpl= TfThis.dateTpl(curEleData);
                    break;
                case "textTime":
                    curEleDataTpl= TfThis.textTimeTpl(curEleData);
                    break;
                case "city":
                    curEleDataTpl= TfThis.cityTpl(curEleData);
                    break;
                case "address":
                    curEleDataTpl= TfThis.addressTpl(curEleData);
                    break;
                case "image":
                    curEleDataTpl= TfThis.imageTpl(curEleData);
                    break;
                case "dataArray":
                    curEleDataTpl= TfThis.dataArrayTpl(curEleData);
                    break;
                case "coordinate":
                    curEleDataTpl= TfThis.coordinateTpl(curEleData);
                    break;
                case "radius":
                    curRadiusDataTpl= TfThis.radiusTpl(curEleData);
                    break;
            }
            TfThis.formHtmlTpl=TfThis.formHtmlTpl+curEleDataTpl;
            formvalArrTpl=formvalArrTpl+valArrTpl
        }
        //元素插入页面
        $(TfThis.formInnerEle).html(TfThis.formHtmlTpl+formvalArrTpl);
        //服务商如何与您联系？未登录
        if($(TfThis.formEle).hasClass('js_form_logout')){
            //未登录
            $('.add-form-list1 .mobile_field').append($('.add-form-list1 .contact_type'));
            $('.add-form-list1 .contact_type').removeClass('li').addClass('contact-type-wrap');
        }else{
            //已登录
            if($('.textAreaTpl',TfThis.formEle).length>0){
                $('.textAreaTpl',TfThis.formEle).before($('.contact_type',TfThis.formEle));
            }
        }
        //map操作 start
        //半径
        if($('.js_coordinate_ele',TfThis.formEle).length>0&&curRadiusDataTpl.length>0){
            var firstCoordinate=$($('.js_coordinate_ele')[0]);
            $('.ele-wrap',firstCoordinate).after(curRadiusDataTpl);
            $(firstCoordinate).addClass('coordinate-radius');
        }
        //行车
        if($('.js_coordinate_ele',TfThis.formEle).length>1){
            var firstCoordinate=$($('.js_coordinate_ele')[0],TfThis.formEle);
            var secondCoordinate=$($('.js_coordinate_ele')[1],TfThis.formEle);
            $('.ele-wrap',secondCoordinate).addClass('second-ele');
            $('.ele-wrap',firstCoordinate).after($('.ele-wrap',secondCoordinate));
            $(firstCoordinate).addClass('coordinate-two');
            $(secondCoordinate).remove();
            $('.js_coordinate_map',firstCoordinate).after('<div class="drive-distance"></div>');
        }
        //map操作 end
        //form set h
        //var formEleH=parseInt($(window).height()-$('.header-wrap').outerHeight());
        //$(TfThis.formEle).css({'min-height':formEleH+'px'});

        //select,radio,checkbox 设置默认选择值
        //selvalueFun();
        //模拟 radio checkbox  样式  注意label 的for属性 与  radio checkbox  一一对应；
        proxyInput();
        //三联动 城市
        //sel_lv3();
        //popSelect初始化
        if($('.select_pop').length>0){
            popSelectInit(".select_pop");
        }
        //popSelect初始化
        if($('.select_pop2').length>0){
            popSelectInit(".select_pop2");
        }
        //.select_pop2 text 设初值
        popselValTextInit();
/*        //日期
        calendarYearMonInit();
        //页面初始化
        pageInit();*/
        //textTime set值
        TfThis.textTimeEvent();
        //set city
        $('.js_pop_val_city').on('blur',function(){
            var cityPEle=$(this).closest('.select_pop2');
            var valCity=$(this).val().split('-');
            $('.js_val_city',cityPEle).val(valCity[1]);
        });
        //form回调函数
        var formCallbackFun=$(formEle).attr("formCallbackFun");
        if(formCallbackFun && formCallbackFun!=""){
          formCallbackFun=eval(formCallbackFun);
          formCallbackFun();
        }
        $('.form-list1-w1 .proxyinput_group .proxyinput:nth-child(2n)').css({'margin-left': '10px'});

        //请填写具体内容
        //isAndroid
        if(isAndroid){
            $(document).on('click',function(e){
                if($(e.target).parents('.other').length>0||$(e.target).hasClass('other')||$(e.target).hasClass('other_text')){

                }else{
                    $('.other_text').data('focus',false);
                }
            });
        }
        $(document).off('click','.proxyinput_group [type="radio"]');
        $(document).on('click','.proxyinput_group [type="radio"]',function(event){
            if(!$(this).closest('.proxy-radio').hasClass('other')){
                if(!$('#js_publish_demand_next').hasClass('hidden')){
                     setTimeout(function(){$('#js_publish_demand_next').trigger('click');},20);
                }
            }

        });
        //补充问题 radio事件
        $(document).off('click','.js_additional_form .proxyinput_group [type="radio"]');
        $(document).on('click','.js_additional_form .proxyinput_group [type="radio"]',function(event){
            if(!$(this).closest('.proxy-radio').hasClass('other')){
                if($(this).closest('.li').next('.li').length>0){
                    var nextAddLi=$(this).closest('.li').next('.li');
                    var formTop=$('.js_form').offset().top;
                    var headH= $('.header').length>0 ? $('.header').outerHeight() : 0;
                    var nextAddLiTop=$(nextAddLi).offset().top-headH;
                    $("html,body").stop().animate({scrollTop: nextAddLiTop+'px'}, 300);
                }
            }
        });
        //补充问题计数
        TfThis.additionalLiNum();
        if(isAndroid){
            //radio_other checkbox_other
            $(document).off('click','.proxyinput_group .other_text');
            $(document).on('click','.proxyinput_group .other_text',function(event){
                var proxyInput=$(this).closest('.proxyinput');
                $('input[type="radio"]',proxyInput).trigger('click');
                $('input[type="checkbox"]',proxyInput).trigger('click');
            });
        }
        $(document).off('click','.proxyinput_group .proxyinput input');
        $(document).on('click','.proxyinput_group .proxyinput input',function(event){
            var inputGroup1=$(this).closest('.proxyinput_group');
            var proxyInput1=$('.other',inputGroup1);
            var thisProxyInput=$(this).closest('.proxyinput');
            if($('input',proxyInput1).prop('checked')){
                console.log('f1');
                if($(thisProxyInput).hasClass('other')){
                    var otherState=$(this).attr('other');
                    switch(otherState){
                        case '2':
                            //选择时间
                            $('.js_textTimeP',$(inputGroup1).closest('.ele-wrap')).removeClass('hidden');
                            $('.bl_calendar_ym_txt',$(inputGroup1).closest('.ele-wrap')).addClass('js_validate');
                            $(this).closest('.show').addClass('secondcheck');
                        break;
                        default:
                            //其他输入框
                            $('.other_text',inputGroup1).attr('placeholder',"请填写具体内容");
                            $('.other_text',inputGroup1).addClass('js_validate');
                            $('.other_text',inputGroup1).trigger('focus');

                            $('.other_text',inputGroup1).data('focus',true);
                            $('.other_text',inputGroup1).off('keyup');
                            $('.other_text',inputGroup1).on('keyup',function(event){
                                var curVal=$(this).val();
                                if(!isIPHONE){
                                   $('.other-txt',proxyInput1).html(curVal);
                                }
                                if(event.keyCode==13){
                                    $('.other_text',inputGroup1).trigger('blur');
                                }
                                if(curVal.length==0){
                                    $('.other-txt',proxyInput1).html($('input',proxyInput1).attr('placeholder'));
                                }
                            });
                    }
                    var thisProxyInputT= parseInt($(thisProxyInput).offset().top)-5;
                    $("html,body").stop().animate({scrollTop: thisProxyInputT+'px'}, 300);
                }
            }else{
                $('.other_text',inputGroup1).data('focus',false);
                //其他输入框
                $('.other_text',inputGroup1).attr('placeholder',"其他");
                $('.other_text',inputGroup1).val('');
                $('.other_text',inputGroup1).removeClass('js_validate');
                $('.other_text',inputGroup1).blur();
                $('.other-txt',proxyInput1).html($('input',proxyInput1).attr('placeholder'));

                //选择时间
                $('.js_textTimeP',$(inputGroup1).closest('.ele-wrap')).addClass('hidden');
                $('.bl_calendar_ym_txt',$(inputGroup1).closest('.ele-wrap')).val('');
                $('.bl_calendar_ym_txt',$(inputGroup1).closest('.ele-wrap')).removeClass('js_validate');
                $(this).closest('.show').removeClass('secondcheck');
            }
            /* proxyinput_level*/
            if($(inputGroup1).hasClass('proxyinput_level')){
                $('.proxyinput',inputGroup1).attr('style','');
                $('.ico-checked',inputGroup1).attr('style','');
                var level_checked=$('input:checked',inputGroup1).closest('.proxyinput');
                var level_colors=$(level_checked).attr('colors');
                    level_border=level_colors.length>0 ? 'border-color:'+level_colors+'!important;' : 'border-color:#89c1f0!important;';
                    level_ico=level_colors.length>0 ? 'background-color:'+level_colors+'!important; border-color:'+level_colors+'!important;' : 'background-color:#89c1f0!important;border-color:#89c1f0!important;';
                $(level_checked).attr('style',level_border);
                $('.ico-checked',level_checked).attr('style',level_ico);
            }

        });
        //other text
        var setTimeoutOtherBlur=null;
        $(document).off('blur','.proxyinput_group .other_text');
        $(document).on('blur','.proxyinput_group .other_text',function(event){
            var inputGroup1=$(this).closest('.proxyinput_group');
            var proxyInput1=$('.other',inputGroup1);
            var curVal=$(this).val();
            if(curVal.length==0){
                $('.other-txt',proxyInput1).html($('input',proxyInput1).attr('placeholder'));
            }else{
                $('.other-txt',proxyInput1).html(curVal);
            }

        });

        //btn-ftp1
        var formInnerEleW=$(TfThis.formInnerEle).width();
        $('.add-imglist1 .btn-ftp1').css({'width':parseInt(formInnerEleW*0.318)+'px','height':parseInt(formInnerEleW*0.318)+'px'});
        //第一个显示
        $(".main").css({"min-height":''});
        var liEle0=$('.li:eq(0)',TfThis.formEle);
        liEle0.addClass('show');
        //TfThis.helpTextSit(liEle0);
        //地图
        if(liEle0.hasClass('js_coordinate_ele')){
            if(liEle0.attr('mapState')!='true'){
                liEle0.attr('mapState','true');
                if(isExitsFunction('setLocation')){
                    setLocation({'locationEle':'.js_coordinate_ele','CallFun':TfThis.followMapCall});
                }
            }
        }
        //图片
        if($('.moxie-shim',liEle0).length>0){
            $('.moxie-shim',liEle0).css({
                'width':$('.btn-ftp1',liEle0).outerWidth()+'px',
                'height':$('.btn-ftp1',liEle0).outerHeight()+'px'
            });
        }
        var lenLiInit=$('.li',TfThis.formEle).length;
        //console.log(lenLiInit);
        if(lenLiInit==1){
            $("#js_publish_demand_next").addClass('hidden');
            $("#js_publish_demand_submit").removeClass('hidden');
        }
        $('.pagewrap').on('click','#js_publish_demand_next',function(event){
            var curLiShow=$('.li.show',TfThis.formEle);
            var setTimeoutNext=0;
            if($(curLiShow).hasClass('js_coordinate_ele')){
                setTimeoutNext=15;
            }
            setTimeout(function(){
                //checkbox 多选大于2时其他不验证
                if($(curLiShow).hasClass('secondcheck')){
                    if($('.proxy-checkbox.checked',curLiShow).length>1){
                        $('.proxy-checkbox.other.checked [type="checkbox"]',curLiShow).trigger('click');
                    }
                }
                var checkResult = formSubmitCheck({
                    "formId":curLiShow,
                    "parentEleTagName":'.li',
                    "parentEleTagitem":1
                });
                if(!checkResult&&$(curLiShow).hasClass('secondcheck')){
                    checkResult = formSubmitCheck({
                        "formId":curLiShow,
                        "parentEleTagName":'.li',
                        "parentEleTagitem":1
                    });
                }
                if(checkResult){
                    /*下一个*/
                    var lenLi=$('.li',TfThis.formEle).length;
                    /*var lenLiAdd=$('.add-form-list1 .li').length;
                    var lenLiInit=lenLi-lenLiAdd;*/

                    var curIndex=$('.li',TfThis.formEle).index($('.li.show'));
                    /*var addFormLen=$('.li.show',TfThis.formEle).closest('.add-form-list1').length;
                    if(addFormLen>0){
                        curIndex=lenLiInit+curIndex;
                    }*/
                    var nextIndex=curIndex+1;
                    var nextLi=$('.li:eq('+nextIndex+')',TfThis.formEle);
                    //console.log("nextIndex:"+nextIndex);

                    $('.li',TfThis.formEle).removeClass('show');
                    $(nextLi).addClass('show');
                    $("#js_publish_demand_prev").removeClass('hidden');
                    if(curIndex>=lenLi-2){
                        $("#js_publish_demand_next").addClass('hidden');
                        $("#js_publish_demand_submit").removeClass('hidden');
                    }
                    var perW=get_display_percent(nextIndex/(lenLi-1))*10;
                    $('.percent .per').css({'width':perW+"%"});
                    //TfThis.helpTextSit(nextLi);
                    //地图
                    if($(nextLi).hasClass('js_coordinate_ele')){
                        if($(nextLi).attr('mapState')!='true'){
                            $(nextLi).attr('mapState','true')
                            setLocation({'locationEle':'.js_coordinate_ele','CallFun':TfThis.followMapCall});
                        }
                    }
                    //图片
                    if($('.moxie-shim',nextLi).length>0){
                        $('.moxie-shim',nextLi).css({
                            'width':$('.btn-ftp1',nextLi).outerWidth()+'px',
                            'height':$('.btn-ftp1',nextLi).outerHeight()+'px'
                        });

                    }

                    //统计
                    var curHref=location.href;
                    if(curHref.indexOf('wx.')!=-1){
                        //ga
                        ga('send', 'event', 'needFill_page', 'next_OK');
                        //baidu
                        _hmt.push(['_trackEvent', 'needFill_page', 'next_OK']);
                    }else{
                        //ga
                        ga('send', 'event', 'needFill_page', 'next_OK');
                        //baidu
                        _hmt.push(['_trackEvent', 'needFill_page', 'next_OK']);
                    }
                    animCss3Fun({
                      "animObj":$(nextLi),//动画对象 class
                      "animOption":"slideInRight"
                    });
                    $("html,body").stop().animate({scrollTop: 0}, 1);
                }
            },setTimeoutNext);
        });
        $('.pagewrap').on('click','#js_publish_demand_prev',function(event){
            var lenLi=$('.li',TfThis.formEle).length;
            /*var lenLiAdd=$('.add-form-list1 .li').length;
            var lenLiInit=lenLi-lenLiAdd;*/
            var curIndex=$('.li',TfThis.formEle).index($('.li.show'));
            /*var addFormLen=$('.li.show',TfThis.formEle).closest('.add-form-list1').length;
            if(addFormLen>0){
                curIndex=lenLiInit+curIndex;
            }*/
            var prevIndex=curIndex-1;
            //console.log("prevIndex:"+prevIndex);
            if(curIndex>0){
                var prevLi=$('.li:eq('+prevIndex+')',TfThis.formEle);
                $('.li',TfThis.formEle).removeClass('show');
                $(prevLi).addClass('show');
                //上一步恢复验证
                $(prevLi).attr('checksuccess','false');
                if(curIndex==1){
                   $("#js_publish_demand_prev").addClass('hidden');
                }
                if(curIndex<=lenLi-1){
                    $("#js_publish_demand_next").removeClass('hidden');
                    $("#js_publish_demand_submit").addClass('hidden');
                }
                var perW=get_display_percent(prevIndex/(lenLi-1))*10;
                if(perW<5){perW=5;}
                $('.percent .per').css({'width':perW+"%"});
                //TfThis.helpTextSit(prevLi);
                //统计
                var curHref=location.href;
                if(curHref.indexOf('wx.')!=-1){
                    //ga
                    ga('send', 'event', 'needFill_page', 'last_question');
                    //baidu
                    _hmt.push(['_trackEvent', 'needFill_page', 'last_question']);
                }else{
                    //ga
                    ga('send', 'event', 'needFill_page', 'last_question');
                    //baidu
                }
                animCss3Fun({
                  "animObj":$(prevLi),//动画对象 class
                  "animOption":"slideInLeft"
                });
                 $("html,body").stop().animate({scrollTop: 0}, 1);
            }
        });
        //样式配置
        switch(TfThis.formStyle){
            case 1:
                 TfThis.setFormStyleFun();
                break;
        }
        //发布需求统计
        $("#publish_demand_form").prepend('<input type="hidden" id="js_timeline" name="timeline" />');
        welog_need_input(formEle);
    },
    additionalLiNum:function(){
        //补充问题计数
        var LiLen=$('.js_additional_form .li').length;
        $('.js_additional_form .li').each(function(index, el){
            var curIndex=index+1;
            $('.validate-title',$(this)).before('<span class="li-num">'+curIndex+'<i>/</i>'+LiLen+'、</span>')
        })
    },
    setFormStyleFun:function(){
        var TfThis=this;
        var formInnerEleW=100;
        var tempAllW=0;
        var tempLiArr=[];
        $('.li',TfThis.formInnerEle).each(function(index, el) {
            var curLi=$(this).attr('liWidth');
                curLi= curLi ? parseInt(curLi) : 100;
            tempAllW=tempAllW+curLi;
            tempLiArr.push($(this));
            if(tempAllW>=formInnerEleW){
                if(tempLiArr.length>1){
                    tempLiArrLen=tempLiArr.length-1;
                    for(var i=0; i<tempLiArr.length; i++){
                     switch(i){
                        case 0:
                            tempLiArr[i].addClass('pr');
                            break;
                        case tempLiArrLen:
                            tempLiArr[i].addClass('pl');
                            break;
                        default:
                            tempLiArr[i].addClass('pl');
                            tempLiArr[i].addClass('pr');
                     }
                    }
                }
                tempAllW=0;
                tempLiArr=[];
            }
            //add-txt
            if($('.add-txt',$(this)).length>0){
               var addPEle=$('.add-txt',$(this)).closest('.ele-wrap');
               var aadEleW=$('.add-txt',$(this)).outerWidth();
               $('.form-control',addPEle).css({'padding-right':aadEleW+'px'});
            }
        });
    },
    "helpTexthtmlTpl":function(args){
        var helpTexthtml=[];
        if(args.helpText){
            helpTexthtml.push('<div class="tips-helptext"><i class="ico ico-tips-helptext"></i><div class="tips-pop"><i></i>'+args.helpText+'</div></div>');
        }
        helpTexthtml= helpTexthtml.join('');
        return helpTexthtml;
    },
    "helpTextSit":function(obj){

        var winH=$(window).height();
        var showH=$('.demand-form-list').outerHeight();
        var formEleT=$(this.formEle).offset().top;
        var helpH=0;
        if($('.tips-helptext',obj)>0){
            var helpH=$('.tips-helptext',obj).outerHeight();
        }
        var btnWrap1H=$('.demand-btn-wrap1').outerHeight();
        var limitH=winH-formEleT-btnWrap1H;
        if(showH<=winH-formEleT-btnWrap1H){
            $('.tips-helptext',obj).addClass('sitfixed');
        }
    },
    "ValTpl":function(args){
        var valueListArr=args.name+"_Arr";
        var orArr=args.valueList;

        var valArr=[];
        valArr.push('');
        valArr.push('<script type="text/javascript">');
        valArr.push('var '+valueListArr+'=[');

        for(var i=0; i<orArr.length;i++){
            if(orArr[i].is_delete!=1){
                valArr.push('{');
                valArr.push('"id":"'+orArr[i].key+'"');
                valArr.push(',"name":"'+orArr[i].text+'"');
                valArr.push(',"child":[]');
                if(i==orArr.length-1){
                    valArr.push('}');
                }else{
                    valArr.push('},');
                }
            }
        }
        valArr.push('];');
        valArr.push('</script>');
        return valArr.join('');
    },
    "ValLv2Tpl":function(args){
        var valueListArr=args.name+"_Arr";
        var lv1OrArr=[];
        for(var a=0; a<30; a++){
            lv1OrArr.push({"key": a+1,"text": a+1});
        }
        var lv2OrArr=[
            {"key": "年","text": "年"},
            {"key": "月","text": "月"},
            {"key": "天","text": "天"}
        ];
        var valArr=[];
        valArr.push('');
        valArr.push('<script type="text/javascript">');
        valArr.push('var '+valueListArr+'=[');
        for(var i=0; i<lv1OrArr.length;i++){
            if(lv1OrArr[i].is_delete!=1){
                valArr.push('{');
                valArr.push('"id":"'+lv1OrArr[i].key+'"');
                valArr.push(',"name":"'+lv1OrArr[i].text+'"');
                valArr.push(',"child":[');
                    for(var j=0; j<lv2OrArr.length;j++){
                        if(lv2OrArr[j].is_delete!=1){
                            valArr.push('{');
                            valArr.push('"id":"'+lv2OrArr[j].key+'"');
                            valArr.push(',"name":"'+lv2OrArr[j].text+'"');
                            if(j==lv2OrArr.length-1){
                                valArr.push('}');
                            }else{
                                valArr.push('},');
                            }
                        }
                    }
                valArr.push(']');
                if(i==lv1OrArr.length-1){
                    valArr.push('}');
                }else{
                    valArr.push('},');
                }
            }
        }
        valArr.push('];');
        valArr.push('</script>');
        return valArr.join('');
    },
    followMapCall:function(locationEle){
        var formEle=$(locationEle).closest('form');
        var addressPoint=$(locationEle+' .js_coordinate').val();
        var addressText=$(locationEle+' .js_coordinate_address').val();
        if(addressPoint.length>0){
            addressPoint=addressPoint.split(',');
            addressPoint=new BMap.Point(addressPoint[0],addressPoint[1]);
            var geoc = new BMap.Geocoder();
            geoc.getLocation(addressPoint, function(rs){
                var addComp = rs.addressComponents;
                var mapObj={
                    "province":addComp.province,
                    "city":addComp.city,
                    "district":addComp.district
                };
                //设置city_name start
                var city_name = rs.addressComponents.city;
                if(city_name=='澳门特别行政区'){
                    city_name= rs.addressComponents.district;
                    if(city_name=='氹仔'){
                        city_name='澳门离岛';
                    }
                }
                if(city_name=='西双版纳傣族自治州'){
                    city_name= '西双版纳';
                }

                if($('.js_city_name',locationEle).length==0){
                    $('.js_coordinate:eq(0)',locationEle).after('<input class="js_city_name" type="hidden" name="city_name" value="'+city_name+'" />');
                    $('.js_coordinate_address:eq(0)',locationEle).on('focus',function(){
                        $('.js_city_name').val('');
                    })
                }else{
                    $('.js_city_name',locationEle).val(city_name);
                }
                //设置city_name end
                //has_followMap 根据地图显示价格
                $('.has_followMap',formEle).each(function(index, el) {
                    var followmap_ele=$(this);
                    var followmap_type=$(this).attr('followmap_type');
                    var followmap_obj=$(this).attr('followmap_obj');
                        followmap_obj=eval(followmap_obj);
                    var followmap_val_Arr=[];
                    var className=$(':input',followmap_ele).attr('class');
                    var fileName,checkedVal;
                        switch (followmap_type){
                            case 'radio'  :
                                fileName=$('[type="radio"]',followmap_ele).attr('name');
                                checkedVal=$(':input[name="'+fileName+'"]:checked').val();
                            break;
                            case 'checkbox'  :
                                fileName=$('[type="checkbox"]',followmap_ele).attr('name');
                                checkedVal=$(':input[name="'+fileName+'"]:checked').val();
                            break;
                        }
                        if(checkedVal==undefined||checkedVal==''){
                            $('#price_table_district').remove();
                            $('#price_table_city').remove();
                            var key_city,key_district;
                            for(a in followmap_obj){
                                if(new RegExp(a).test(mapObj.city)||new RegExp(mapObj.city).test(a)){
                                    key_city=a;
                                    var cur_city_obj=followmap_obj[a];
                                    for( b in cur_city_obj){
                                        if(new RegExp(b).test(mapObj.district)|| new RegExp(mapObj.district).test(b)){
                                            key_district=b;
                                            followmap_val_Arr= cur_city_obj[b];
                                        }
                                    }
                                    if(followmap_val_Arr.length==0){
                                        followmap_val_Arr= cur_city_obj['默认'];
                                    }
                                }
                            }
                            if(followmap_val_Arr.length>0){
                                $(formEle).prepend('<input id="price_table_district" type="hidden" name="price_table_district" value="'+key_district+'" />');
                                $(formEle).prepend('<input id="price_table_city" type="hidden" name="price_table_city" value="'+key_city+'" />');
                                switch (followmap_type){
                                    case 'radio'  :
                                        var html =[];
                                        html.push('');
                                        for(var i=0; i<followmap_val_Arr.length; i++){
                                            html.push('            <label class="proxyinput proxy-radio">');
                                            html.push('                <span class="h0hidden"><input  class="'+className+'" name="'+fileName+'" type="radio" value="'+(i+1)+'" /></span>'+followmap_val_Arr[i]);
                                            html.push('            </label>');
                                        }
                                        $(followmap_ele).html(html.join(''));
                                        break;
                                    case 'checkbox'  :
                                        var html =[];
                                        html.push('');
                                        for(var i=0; i<followmap_val_Arr.length; i++){
                                            html.push('            <label class="proxyinput proxy-checkbox">');
                                            html.push('                <span class="h0hidden"><input  class="'+className+'" name="'+fileName+'" type="checkbox" value="'+(i+1)+'" /></span>'+followmap_val_Arr[i]);
                                            html.push('            </label>');
                                        }
                                        $(followmap_ele).html(html.join(''));
                                        break;
                                }
                                //模拟 radio checkbox  样式  注意label 的for属性 与  radio checkbox  一一对应；
                                proxyInput();
                            }
                        }
                });
                //根据地图显示 需求类型
                showNeedType({'mapObj':mapObj});
            });
        }
        //根据地图显示 需求类型
        function showNeedType(args){
            var ajaxUrl='/cate/ajaxLoadSecured';
            var dataObj={'cate_id':$('[name="cate_id"]').val(),'city_name':args.mapObj.city};
            dataObj=$(formEle).serialize();
            console.log(dataObj);
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'html',
                data:dataObj,
                success: function(data){
                    if(data.length>0){
                        if($('.is_secured_ele').length==0){
                            $('.li:last',formEle).after(data);
                            //模拟 radio checkbox  样式  注意label 的for属性 与  radio checkbox  一一对应；
                            proxyInput();
                        }
                    }else{
                        $('.is_secured_ele').remove();
                    }
                }
            })
        }
    },
    "checkboxTpl":function(args){
        var orArr=args.valueList;
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var selectvalue= args.value ? 'selectvalue="'+args.value+'"' :'';
        var placeholder= args.defaultValue!="" ? args.defaultValue :"其他";
        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);
        //html
        var html = [];
        html.push('');
        html.push('<div class="li">');
        html.push('     <label class="lab-title"><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>(多选)'+valiMark+'</label>');
        html.push('     <div class="ele-wrap">');

        if(args.priceTable!=undefined&&args.priceTable.length>0){
            //根据地图生成相应价格
            var followMapObj=args.name+"_followMap";
            html.push('      <script type="text/javascript">');
            html.push('       var '+followMapObj+'='+args.priceTable);
            html.push('      </script>');
            html.push('      <div class="proxyinput_group has_followMap" followMap_type="checkbox" followMap_obj="'+followMapObj+'">');
        }else{
            html.push('      <div class="proxyinput_group">');
        }
        //checkbox start
        var otherNum=0;
        //console.log(otherNum);
        for(var i=0; i<orArr.length;i++){
            if(orArr[i].is_delete!=1){
                selectvalue= i>0 ? '': selectvalue;
                if(orArr[i].other>0){
                    //other
                    otherNum=parseInt(orArr[i].other);
                    html.push('            <label class="proxyinput proxy-checkbox other">');
                    html.push('                <span class="h0hidden"><input other="'+orArr[i].other+'" '+selectvalue+' class="checkbox_other '+valiClass+'" name="'+args.name+'[]" type="checkbox" value="'+orArr[i].key+'"  placeholder="'+orArr[i].text+'"  /></span>');
                    if(otherNum==1){
                        //other text
                        html.push('            <input class="other_text checkbox_other_text '+valiClass+'" placeholder="'+placeholder+'"  name="'+args.name+'_other"  other="'+args.other+'" type="text" value="">');
                    }else{
                        html.push('            <span class="other-txt">'+orArr[i].text+'</span>');
                    }
                    html.push('            </label>');

                }else{
                    html.push('            <label class="proxyinput proxy-checkbox">');
                    html.push('                <span class="h0hidden"><input '+selectvalue+' class="'+valiClass+'" name="'+args.name+'[]" type="checkbox" value="'+orArr[i].key+'" /></span>'+orArr[i].text);
                    html.push('            </label>');
                }
            }
        }
        //checkbox end
        html.push('            <div class="clear"></div>');
        html.push('        </div>');
        html.push('        <div class="clear"></div>');
        html.push('     </div>'+helpTexthtml);
        html.push('</div>');

        return html.join('');
    },
    "radioTpl":function(args){
        var orArr=args.valueList;
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var selectvalue= args.value ? 'selectvalue="'+args.value+'"' :'';
        var placeholder= args.defaultValue!="" ? args.defaultValue :"其他";
        var placeholder1= "年/月/日";
        var placeholder2= "时/分";
        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);
        var levelStyleClass= args.levelStyle==1 ? 'proxyinput_level' : '';
        var icoChecked= args.levelStyle==1 ? '<i class="ico-checked"></i>' : '';
        //html
        var html = [];
        html.push('');
        html.push('<div class="li">');
        html.push('     <label class="lab-title"><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>(单选)'+valiMark+'</label>');
        html.push('     <div class="ele-wrap">');
        if(args.priceTable!=undefined&&args.priceTable.length>0){
            //根据地图生成相应价格
            var followMapObj=args.name+"_followMap";
            html.push('      <script type="text/javascript">');
            html.push('       var '+followMapObj+'='+args.priceTable);
            html.push('      </script>');
            html.push('      <div class="proxyinput_group '+levelStyleClass+' has_followMap" followMap_type="radio" followMap_obj="'+followMapObj+'">');
        }else{
            html.push('      <div class="proxyinput_group '+levelStyleClass+'">');
        }
        //radio start
        var otherNum=0;
        var colors='';
        var colorstyle='';
        var bgstyle='';
        var borderstyle='';
        var icostyle='';
        for(var i=0; i<orArr.length;i++){
            if(orArr[i].is_delete!=1){
                selectvalue= i>0 ? '': selectvalue;
                if(args.levelStyle==1 ){
                    colors=orArr[i].color.length>0 ? 'colors="'+orArr[i].color+'"' : 'colors="#89c1f0"' ;
                    colorstyle=orArr[i].color.length>0 ? 'style="color:'+orArr[i].color+'!important"' : 'style="color:#89c1f0!important"';
                    bgstyle=orArr[i].color.length>0 ? 'style="background:'+orArr[i].color+'!important"' : 'style="background:#89c1f0!important"';
                    var orArrValText=[];
                    if(orArr[i].text.length>0){
                        var  level_title= orArr[i].text;
                        switch (orArr[i].text.length){
                            case 4:
                                level_title=level_title.substr(0,2)+'<br/>'+level_title.substring(2);
                            break;
                            case 5:
                                level_title=level_title.substr(0,2)+'<br/>'+level_title.substring(2);
                            break;
                            case 6:
                                level_title=level_title.substr(0,3)+'<br/>'+level_title.substring(3);
                            break;
                            case 7:
                                level_title=level_title.substr(0,3)+'<br/>'+level_title.substring(3);
                            break;
                            case 8:
                                level_title=level_title.substr(0,4)+'<br/>'+level_title.substring(4);
                            break;
                        }
                        orArrValText.push('<div class="level-title s'+orArr[i].text.length+'" '+bgstyle+' ><span>'+level_title+'</span></div>')

                    }

                    orArrValText.push('<div class="desc"><div class="desc-con">');
                    orArr[i].need_sub_title.length>0 ? orArrValText.push('<div class="level_title" '+colorstyle+'>'+orArr[i].need_sub_title+'</div>') : '';
                    orArr[i].need_desc.length>0 ? orArrValText.push('<div class="need-desc">'+orArr[i].need_desc+'</div>') : '';
                    orArrValText.push('</div></div>');
                    orArrValText=orArrValText.join('');
                }else{
                    var orArrValText=orArr[i].text;
                }
                if(orArr[i].other>0){
                    //other
                    otherNum=parseInt(orArr[i].other);
                    html.push('            <label class="proxyinput proxy-radio other"  '+colors+'>'+icoChecked);
                    html.push('                <span class="h0hidden"><input other="'+orArr[i].other+'" '+selectvalue+' class="radio_other '+valiClass+'" name="'+args.name+'" type="radio" value="'+orArr[i].key+'" placeholder="'+orArr[i].text+'" /></span>');
                    if(otherNum==1){
                        //other text
                        html.push('            <input class="other_text radio_other_text '+valiClass+'" placeholder="'+placeholder+'"  name="'+args.name+'_other" other="'+args.other+'" type="text" value="">');
                    }else{
                        html.push('             <span class="other-txt">'+orArrValText+'</span>');
                    }
                    html.push('            </label>');
                }else{
                    html.push('            <label class="proxyinput proxy-radio" '+colors+'>'+icoChecked);
                    html.push('                <span class="h0hidden"><input '+selectvalue+' class="'+valiClass+'" name="'+args.name+'" type="radio" value="'+orArr[i].key+'" /></span>'+orArrValText);
                    html.push('            </label>');
                }
            }
        }
        html.push('            <div class="clear"></div>');
        html.push('        </div>');

        switch(otherNum){
             case 2:
                html.push('<div class="clear"></div>');
                html.push('<div class="js_textTimeP hidden">');
                html.push('         <div class="bl_calendar_ym" >');
                var todaydate=new Date();
                todaydate=todaydate.getFullYear()+'-'+(todaydate.getMonth()+1)+'-'+todaydate.getDate();
                switch(args.limitDatepicker){
                    case 1:
                    html.push('             <input type="text" readonly class="js_textTime_date bl_calendar_ym_txt form-control '+valiClass+' js_no_error" valiname="datetime_'+args.name+'_other"  other="'+args.other+'" calendarend="'+todaydate+'" placeholder="'+placeholder1+'" value=""><i></i>');
                    break;
                    case 2:
                    html.push('             <input type="text" readonly class="js_textTime_date bl_calendar_ym_txt form-control '+valiClass+' js_no_error" valiname="datetime_'+args.name+'_other"  other="'+args.other+'" calendarstart="'+todaydate+'" placeholder="'+placeholder1+'" value=""><i></i>');
                    break;
                    default:
                    html.push('             <input type="text" readonly class="js_textTime_date bl_calendar_ym_txt form-control '+valiClass+' js_no_error" valiname="datetime_'+args.name+'_other"  other="'+args.other+'" placeholder="'+placeholder1+'" value=""><i></i>');
                }

                html.push('         </div>');
                html.push('         <div class="bl_calendar_ym" >');
                html.push('             <input type="text" readonly class="js_textTime_time bl_calendar_ym_txt form-control js_no_error" valiname="hour_min_'+args.name+'_other"  other="'+args.other+'"  datekey="th-tm" placeholder="'+placeholder2+'" value=""><i></i>');
                html.push('         </div>');
                html.push('        <input type="hidden"  class="js_textTime " name="'+args.name+'_other">');
                html.push('</div>');
            break;
        }
        //radio end
        html.push('        <div class="clear"></div>');
        html.push('     </div>'+helpTexthtml);
        html.push('</div>');

        return html.join('');
    },
    "textTpl":function(args){
        var placeholder= args.defaultValue!="" ? args.defaultValue :"";
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var value= args.value ? args.value :'';
        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);
        var liWidth= args.m_width ? 'width:'+args.m_width+'%' : '';
        var liWidthAttr= args.m_width ? 'liWidth='+args.m_width : '';
        var maxLength=  args.max_length ? 'maxlength="'+args.max_length+'"' :'';
        var textTypeTpl= 'type="text"';
        if(args.type=="price"){
            args.keyboard='number';
        }
        if(args.keyboard&&args.keyboard!=''){
            switch (args.keyboard){
                case 'number':
                    textTypeTpl= 'type="tel" pattern="[0-9]*"';
                    break;
                case 'tel':
                    textTypeTpl= 'type="tel" pattern="[0-9]*"';
                    break;
                default :
                    textTypeTpl='type="'+args.keyboard+'"';
            }
        }
        var unitTpl= args.unit&& args.unit ? '<span class="add-txt">'+args.unit+'</span>' : '';
        var unitAttr= args.unit&& args.unit ? 'add-txt="'+args.unit+'"' : '';
        var searchComplete= args.is_kw==1 ? 'js_searchComplete' : '';
        var autocompleteAttr= args.is_kw==1 ? 'autocomplete="off"' : '';
        //html
        var html = [];
        html.push('');
        html.push('<div class="li" '+liWidthAttr+' style="'+liWidth+'">');
        html.push('     <label class="lab-title">');
        if(args.type=="price"){
            html.push('     <i class="ico ico-s-price"></i>');
        }
        html.push('     <span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'</label>');
        if(args.type=="price"){
        html.push('     <div class="ele-wrap ele-price">');
            html.push('<span class="ico_price"></span>');
        }else{
        html.push('     <div class="ele-wrap">');
        }
        html.push('        <input class="form-control '+valiClass+' '+searchComplete+'" '+autocompleteAttr+' '+maxLength+' placeholder="'+placeholder+'"  name="'+args.name+'" '+textTypeTpl+' value="'+value+'" '+unitAttr+'>'+unitTpl);
        html.push('     </div>'+helpTexthtml);
        html.push('</div>');
        return html.join('');
    },
    "selectTpl":function(args){
        var valueListArr=args.name+"_Arr";
        var orArr=args.valueList;
        var placeholder= args.defaultValue!="" ? args.defaultValue :"";
        var valueText= "" ;
        var value= args.value ? args.value :'';
        if(value!=""){
            for(var i=0; i<orArr.length; i++){
                if(orArr[i].key==value){
                    valueText=orArr[i].text;
                }
            }
        }
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";

        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);

        var liWidth= args.m_width ? 'width:'+args.m_width+'%' : '';
        var liWidthAttr= args.m_width ? 'liWidth='+args.m_width : '';
        //html
        var html = [];
        html.push('');
        html.push('<div class="li" '+liWidthAttr+' style="'+liWidth+'">');
        html.push('     <label class="lab-title"><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'</label>');
        html.push('     <div class="ele-wrap">');
        html.push('         <div class="select_pop2"  dataArray="'+valueListArr+'"  level="1">');
        html.push('             <input class="js_pop_text form-control" placeholder="'+placeholder+'"  type="text" value="'+valueText+'" readonly>');
        html.push('             <span  class="proxy_hide">');
        html.push('                 <input class="js_pop_val '+valiClass+'" name="'+args.name+'" type="hidden" value="'+value+'">');
        html.push('             </span>');
        html.push('         </div> ');
        html.push('     </div>'+helpTexthtml);
        html.push('</div>');
        return html.join('');
    },
    "durationTpl":function(args){
        var valueListArr=args.name+"_Arr";
        var orArr=args.valueList;
        var placeholder= args.defaultValue!="" ? args.defaultValue :"";
        var valueText= "" ;
        var value= args.value ? args.value :'';
        if(value!=""){
            for(var i=0; i<orArr.length; i++){
                if(orArr[i].key==value){
                    valueText=orArr[i].text;
                }
            }
        }
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";

        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);

        var liWidth= args.m_width ? 'width:'+args.m_width+'%' : '';
        var liWidthAttr= args.m_width ? 'liWidth='+args.m_width : '';
        //html
        var html = [];
        html.push('');
        html.push('<div class="li" '+liWidthAttr+' style="'+liWidth+'">');
        html.push('     <label class="lab-title"><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'</label>');
        html.push('     <div class="ele-wrap">');
        html.push('         <div class="select_pop2"  dataArray="'+valueListArr+'"  level="2" leveltype="1" jointype="">');
        html.push('             <input class="js_pop_text form-control" placeholder="'+placeholder+'"  type="text" value="'+valueText+'" readonly>');
        html.push('             <span  class="proxy_hide">');
        html.push('                 <input class="js_pop_val '+valiClass+'" name="'+args.name+'" type="hidden" value="'+value+'">');
        html.push('             </span>');
        html.push('         </div> ');
        html.push('     </div>'+helpTexthtml);
        html.push('</div>');
        return html.join('');
    },
    "textAreaTpl":function(args){
        var placeholder= args.defaultValue!="" ? args.defaultValue :"详细说明您的服务期望和特殊要求，商家才能针对性地提供更合适的服务方案和报价。";
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var value= args.value ? args.value :'';
        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);
        var liWidth= args.m_width ? 'width:'+args.m_width+'%' : '';
        var liWidthAttr= args.m_width ? 'liWidth='+args.m_width : '';
        var maxLength=  args.max_length ? 'maxlength="'+args.max_length+'"' :'';
        //html
        var html = [];
        html.push('');
        html.push('<div class="li textAreaTpl" '+liWidthAttr+' style="'+liWidth+'">');
        html.push('     <label class="lab-title"><i class="ico ico-s-descri"></i><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'</label>');
        html.push('     <div class="ele-wrap">');
        html.push('        <textarea class="form-control '+valiClass+'" '+maxLength+' placeholder="'+placeholder+'"  name="'+args.name+'">'+value+'</textarea>');
        html.push('     </div>'+helpTexthtml);
        html.push('</div>');
        return html.join('');
    },
    "emailTpl":function(args){
        var placeholder= args.defaultValue!="" ? args.defaultValue :"";
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var validate=args.type;
        var value= args.value ? args.value :'';
        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);
        var liWidth= args.m_width ? 'width:'+args.m_width+'%' : '';
        var liWidthAttr= args.m_width ? 'liWidth='+args.m_width : '';
        //html
        var html = [];
        html.push('');
        html.push('<div class="li" '+liWidthAttr+' style="'+liWidth+'">');
        html.push('     <label class="lab-title"><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'</label>');
        html.push('     <div class="ele-wrap">');
        html.push('        <input class="form-control '+valiClass+'" validate="'+validate+'" placeholder="'+placeholder+'"  name="'+args.name+'" type="text" value="'+value+'" />');
        html.push('     </div>'+helpTexthtml);
        html.push('</div>');
        return html.join('');
    },
    "dateTpl":function(args){
        var placeholder= args.defaultValue!="" ? args.defaultValue :"年/月/日";
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var value= args.value ? args.value :'';
        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);
        var liWidth= args.m_width ? 'width:'+args.m_width+'%' : '';
        var liWidthAttr= args.m_width ? 'liWidth='+args.m_width : '';
        //html
        var html = [];
        html.push('');
        html.push('<div class="li" '+liWidthAttr+' style="'+liWidth+'">');
        html.push('     <label class="lab-title"><i class="ico ico-s-date"></i><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'</label>');
        html.push('     <div class="ele-wrap">');
        html.push('         <div class="bl_calendar_ym" >');
        var todaydate=new Date();
        todaydate=todaydate.getFullYear()+'-'+(todaydate.getMonth()+1)+'-'+todaydate.getDate();
        switch(args.limitDatepicker){
            case 1:
            html.push('             <input type="text" readonly class="bl_calendar_ym_txt form-control '+valiClass+' js_no_error" calendarend="'+todaydate+'" placeholder="'+placeholder+'"   name="'+args.name+'"  value="'+value+'"><i></i>');
            break;
            case 2:
            html.push('             <input type="text" readonly class="bl_calendar_ym_txt form-control '+valiClass+' js_no_error" calendarstart="'+todaydate+'" placeholder="'+placeholder+'"   name="'+args.name+'"  value="'+value+'"><i></i>');
            break;
            default:
            html.push('             <input type="text" readonly class="bl_calendar_ym_txt form-control '+valiClass+' js_no_error" placeholder="'+placeholder+'"   name="'+args.name+'"  value="'+value+'"><i></i>');
        }
        html.push('         </div>');
        html.push('     </div>'+helpTexthtml);
        html.push('</div>');
        return html.join('');
    },
    "textTimeTpl":function(args){
        var placeholder1= "年/月/日";
        var placeholder2= "时/分";
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var value= args.value ? args.value :'';
        //console.log(value);
        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);

        var liWidth= args.m_width ? 'width:'+args.m_width+'%' : '';
        var liWidthAttr= args.m_width ? 'liWidth='+args.m_width : '';
        //html
        var html = [];
        html.push('');
        html.push('<div class="li js_textTimeP" '+liWidthAttr+' style="'+liWidth+'">');
        html.push('     <label class="lab-title"><i class="ico ico-s-time"></i><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'</label>');
        html.push('     <div class="ele-wrap">');
        html.push('         <div class="bl_calendar_ym">');
        var todaydate=new Date();
        todaydate=todaydate.getFullYear()+'-'+(todaydate.getMonth()+1)+'-'+todaydate.getDate();
        switch(args.limitDatepicker){
            case 1:
            html.push('             <input type="text" readonly class="js_textTime_date bl_calendar_ym_txt form-control '+valiClass+' js_no_error" valiname="datetime_'+args.name+'" calendarend="'+todaydate+'" placeholder="'+placeholder1+'" value=""><i></i>');
            break;
            case 2:
            html.push('             <input type="text" readonly class="js_textTime_date bl_calendar_ym_txt form-control '+valiClass+' js_no_error" valiname="datetime_'+args.name+'" calendarstart="'+todaydate+'" placeholder="'+placeholder1+'" value=""><i></i>');
            break;
            default:
            html.push('             <input type="text" readonly class="js_textTime_date bl_calendar_ym_txt form-control '+valiClass+' js_no_error" valiname="datetime_'+args.name+'" placeholder="'+placeholder1+'" value=""><i></i>');
        }

        html.push('         </div>');
        html.push('         <div class="bl_calendar_ym">');
        html.push('             <input type="text" readonly class="js_textTime_time bl_calendar_ym_txt form-control '+valiClass+' js_no_error" valiname="hour_min_'+args.name+'"  datekey="th-tm" placeholder="'+placeholder2+'" value=""><i></i>');
        html.push('         </div>');
        html.push('        <input type="hidden"  class="js_textTime" name="'+args.name+'"  value="'+value+'">');
        html.push('     </div>'+helpTexthtml);
        html.push('</div>');
        return html.join('');
    },
    textTimeEvent:function(){
        $("body").on("blur",".js_textTimeP .js_textTime_date,.js_textTimeP .js_textTime_time",function(event) {
            var textTimeP=$(this).closest(".js_textTimeP");
            var textTimeVal=$(".js_textTime_date",textTimeP).val()+' '+$(".js_textTime_time",textTimeP).val();
            //console.log(textTimeVal!=' ');
            if(textTimeVal!=' '){
                $(".js_textTime",textTimeP).val(textTimeVal);
                //other time
                var textTimeLi=$(this).closest('.li');
                if($('.other-txt',textTimeLi).length>0){
                    $('.other-txt',textTimeLi).html(textTimeVal);
                }
            }

        });
    },
    "city2Tpl":function(args){
        //未启用
        var valueListArr="citySelectArr";
        var orArr=args.valueList;
        var placeholder= args.defaultValue!="" ? args.defaultValue :"请选择";
        var valueText= "" ;
        if(args.valueText!=""&&args.valueText){
            valueText=args.valueText;
        }
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var value= args.value ? args.value :'';
        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);
        var liWidth= args.m_width ? 'width:'+args.m_width+'%' : '';
        var liWidthAttr= args.m_width ? 'liWidth='+args.m_width : '';

        //html
        var html = [];
        html.push('');
        html.push('<div class="li" '+liWidthAttr+' style="'+liWidth+'">');
        html.push('   <label class="lab-title"><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'</label>');
        html.push('   <div class="ele-wrap">');
        html.push('       <div class="select_pop select_pop_address"  maxSelectCount="1"  dataArray="'+valueListArr+'" SelectTxt="选择城市"  attrName="hot" level="1"  >');
        html.push('           <input class="js_pop_text form-control" placeholder="'+placeholder+'"  type="text" value="'+valueText+'" readonly>');
        html.push('           <span  class="proxy_hide">');
        html.push('               <input class="js_pop_val '+valiClass+'" name="city" type="hidden" value="'+value+'">');
        html.push('           </span>');
        html.push('       </div>');
        html.push('   </div>'+helpTexthtml);
        html.push('</div>');
        return html.join('');
    },
    "cityTpl":function(args){
        //未启用
        var valueListArr="citySelectArr";
        var orArr=args.valueList;
        var placeholder= args.defaultValue!="" ? args.defaultValue :"请选择";
        var valueText= "" ;
        if(args.valueText!=""&&args.valueText){
            valueText=args.valueText;
        }
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var cityText="",cityCode="";
        var aadrText="";
        var selectValue=args.value!=""&& args.value ? args.value.split("-") : ['',''];
        var selectValueV1=args.value!=""&& args.value ? args.value : '';
        //1级等于2级
        var initShow='';
        if(selectValue[0]==selectValue[1]){
            if(selectValue[0]!=""){
                selectValueV1=selectValue[0];
            }else{
                selectValueV1=31;
            }
            initShow='initShow="false"';
        }

        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);
        var liWidth= args.m_width ? 'width:'+args.m_width+'%' : '';
        var liWidthAttr= args.m_width ? 'liWidth='+args.m_width : '';


        //html
        var html = [];
        html.push('<div class="li js_formEle_address" '+liWidthAttr+' style="'+liWidth+'">');
        html.push('    <label class="lab-title"><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'</label>');
        html.push('    <div class="ele-wrap">');
        html.push('         <div class="select_pop2 select_pop2_address"  dataArray="citySelectArr"  level="2">');
        html.push('             <input class="js_pop_text form-control" placeholder="'+placeholder+'"  type="text" value="" readonly>');
        html.push('             <span  class="proxy_hide">');
        html.push('                 <input class="js_pop_val js_pop_val_city '+valiClass+'"  valiname="city_v1" type="hidden" '+initShow+' value="'+selectValueV1+'">');
        html.push('                 <input class="js_val_city '+valiClass+'" name="city" type="hidden" value="'+selectValue[1]+'">');
        html.push('             </span>');
        html.push('         </div> ');
        html.push('        <div class="clear"></div>');
        html.push('    </div>'+helpTexthtml);
        html.push('</div>');

        return html.join('');
    },
    "addressTpl":function(args){
        //未启用
        var valueListArr="citySelectArr";
        var orArr=args.valueList;
        var placeholder= args.defaultValue!="" ? args.defaultValue :"请选择";
        var valueText= "" ;

        if(args.valueText!=""&&args.valueText){
            valueText=args.valueText;
        }
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var cityText="",cityCode="";
        var aadrText="";
        var selectValue=args.value!="" && args.value ? args.value.split("-") : ['',''];
        var selectValueV1= args.value!="" && args.value ? args.value : '';
        //1级等于2级
        var initShow='';
        if(selectValue[0]==selectValue[1]){
            if(selectValue[0]!=""){
                selectValueV1=selectValue[0];
            }else{
                selectValueV1=31;
            }
            initShow='initShow="false"';
        }

        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);
        var liWidth= args.m_width ? 'width:'+args.m_width+'%' : '';
        var liWidthAttr= args.m_width ? 'liWidth='+args.m_width : '';

        //html
        var html = [];
        html.push('<div class="li js_formEle_address" '+liWidthAttr+' style="'+liWidth+'">');
        html.push('    <label class="lab-title"><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'</label>');
        html.push('    <div class="ele-wrap">');
        html.push('         <div class="select_pop2 select_pop2_address"  dataArray="citySelectArr"  level="2">');
        html.push('             <input class="js_pop_text form-control" placeholder="'+placeholder+'"  type="text" value="" readonly>');
        html.push('             <span  class="proxy_hide">');
        html.push('                 <input class="js_pop_val js_pop_val_city '+valiClass+'"  valiname="address_city_v1_'+args.name+'" type="hidden" '+initShow+' value="'+selectValueV1+'">');
        html.push('                 <input class="js_val_city '+valiClass+'" name="address_city_'+args.name+'" type="hidden" value="'+selectValue[1]+'">');
        html.push('             </span>');
        html.push('         </div> ');
        html.push('        <div class="clear"></div>');
        html.push('    </div>');
        html.push('    <div class="ele-wrap pt10">');
        html.push('        <textarea class="form-control" name="address_detail_'+args.name+'" placeholder="街道名或小区名等能指明具体方位的信息"></textarea>');
        html.push('    </div>'+helpTexthtml);
        html.push('</div>');

        return html.join('');
    },
    "addressEventFun":function(){
        $(".js_formEle_address .js_aadrText").on('keyup',  function(event) {
            addressValSet($(this));
        });
    },
    "imageTpl":function(args){
        var valueListArr=args.name+"_Arr";
        var orArr=args.valueList;
        var placeholder= args.defaultValue!="" ? args.defaultValue :"请选择";
        var valueText= "" ;
        if(args.valueText!=""){
            valueText=args.valueText;
        }
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);

        var valiClass= args.required==1 ? "js_validate" :"";
        //html
        var html = [];
        html.push('');

        html.push('<div class="li js_img_show_wrap">');
        html.push('   <label class="lab-title"><i class="ico ico-s-image"></i><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'<div class="clear"></div></label>');
        html.push('   <div class="ele-wrap ">');
        html.push('        <input type="hidden" id="multiple_size" value="100" />');
        html.push('        <div class="add-imglist1 img-show"><div id="result" class="img-group"></div>');
        html.push('            <input class="multiple_field_name '+valiClass+'"  type="hidden" name="'+args.name+'" value="" id="'+args.name+'"/>');
        html.push('            <div class="cell" id="container">');
        html.push('                <a class="btn-ftp1"  id="pickfiles" href="javascript:;">');
        html.push('                    <i class="ico ico-add"></i>');
        html.push('                </a>');
        html.push('            </div>');
        html.push('        </div>');
        html.push('        <div class="clear"></div>');
        html.push('    </div>'+helpTexthtml);
        html.push('</div>');
        return html.join('');
    },
    "coordinateTpl":function(args){
        var placeholder= args.defaultValue!="" ? args.defaultValue :"大概位置（如：街道地址）";
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var value= args.value ? args.value :'';
        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);
        var mapShow= args.displayMap== 1 ? '': 'hidden';
        var liWidth= args.m_width ? 'width:'+args.m_width+'%' : '';
        var liWidthAttr= args.m_width ? 'liWidth='+args.m_width : '';
        //html
        var html = [];
        html.push('');
        html.push('<div class="li coordinate-ele js_coordinate_ele" '+liWidthAttr+' style="'+liWidth+'">');
        html.push('     <label class="lab-title"><i class="ico ico-s-coordinate"></i><span class="validate-title">'+args.title+'</span><span class="long-title">'+args.longTitle+'</span>'+valiMark+'</label>');
        html.push('     <div class="ele-wrap ">');
        html.push('        <input class="form-control js_coordinate_address coordinate_address '+valiClass+' " placeholder="'+placeholder+'" id="address['+address_num+']"  name="address['+address_num+']" type="text" value="">');
        html.push('        <input class="form-control js_coordinate coordinate '+valiClass+'" placeholder="'+placeholder+'" id="coordinate['+address_num+']"  name="coordinate['+address_num+']" type="hidden" value="'+value+'">');
        html.push('     </div>');
        if(address_num==0){
            html.push('     <div class="clear"></div><div class="js_coordinate_map coordinate_map '+mapShow+'" id="coordinate_map_'+address_num+'"></div>');
        }
        html.push('</div>');
        address_num++;
        return html.join('');
    },
    "radiusTpl":function(args){
        var placeholder= args.defaultValue!="" ? args.defaultValue :"";
        var valiClass= args.required==1 ? "js_validate" :"";
        var valiMark= args.required==1 ? '<span class="vali-mark">（必填）</span>' :"";
        var value= args.value ? args.value :'';
        var selectvalue= args.value ? args.value :'';
        //helpText
        var helpTexthtml=this.helpTexthtmlTpl(args);
        //html
        var html = [];
        html.push('');
        html.push('     <div class="ele-wrap radius-slider-wrap">');
        html.push('         <span class="radius-title">区域范围:</span>');
        html.push('         <div class="radius-slider">');
        html.push('            <input mapZoom="mapZoomArr_publish"  class="js_radius_val '+valiClass+'" type="hidden" name="radius" id="radius"  value="'+value+'" >');
        html.push('            <input  class="js_radius_ele " type="hidden" id="radius_slider_input" min="0" max="5" value="" step="1" data-highlight="true">');
        html.push('         </div>');
        html.push('     </div><div class="clear"></div>');

        return html.join('');
    },
    "dataArrayTpl":function(args){
        //未启用
        var html = [];
        html.push('');
        return html.join('');
    }
}

function addressValSet(eventEle){
    var addressEle=$(eventEle).closest('.js_formEle_address');
    var addressVal=$(".js_pop_val",addressEle).val()+"-"+$(".js_aadrText",addressEle).val();
    $(".js_address_txt",addressEle).val(addressVal);
}