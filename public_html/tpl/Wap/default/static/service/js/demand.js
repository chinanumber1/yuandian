/*$(document).ready(function() {
    publishDemand();
});*/

function publishDemand(){
    //publish_demand_form 需求表单 验证 提交
    PublishFormCheck({
       "formId":"#publish_demand_form",
       "parentEleTagName":".li",
       "errorMethod":"validatePop",
       "validateOption":function(args){
            var OptionObj={}
            $(".js_validate",$(args.formId)).each(function(index, el) {
                var parentEle=$(this).closest(".li");
                var curTitle=$('.lab-title .validate-title:eq(0)',parentEle).text();
                    curTitle=curTitle.replace("：","");
                    curTitle=curTitle.replace("*","");
                    curTitle=curTitle.replace("？","");
                var curName=$(this).attr("name");
                if(!curName){
                   curName=$(this).attr("valiname");
                }

                if(curName.indexOf('_other')==-1){
                    OptionObj[curName]={"requiredCheck":{"msg":curTitle+' 不能为空'}};
                }else{
                    switch($(this).attr('other')){
                        case '1':
                            OptionObj[curName]={"requiredCheck":{"msg":'请填写其他选项内容'}};
                        break;
                        case '2':
                            OptionObj[curName]={"requiredCheck":{"msg":'请选择具体日期和时间'}};
                        break;
                        default:
                            OptionObj[curName]={"requiredCheck":{"msg":'请填写其他选项内容'}};
                        break;
                    }
                    $(this).removeClass("js_validate");
                }
                if($(this).attr("validate")=="email"){
                    OptionObj[curName].emailCheck={"maxlength":[1,"邮箱地址格式不正确"]}
                }
                if($(this).attr("name")=="mobile"){
                    OptionObj[curName].phoneCheck={"msg":'你输入的手机号码不正确'}
                }
                if($(this).closest('.ele-price').length>0){
                    OptionObj[curName].priceCheck={"msg":'你输入正确价格'}
                }
            });
            OptionObj['is_secured']={"requiredCheck":{"msg":'是否在线付款选项不能为空'}};
            return OptionObj;
       }
    });
    //需求表单是否通过验证
    var lock=true;
    $("#publish_demand_form").on('submit',function(){
        var errLen=$("#publish_demand_form .error").length;
        if(errLen==0&&lock){
            if($('.js_city_name').length>0){
                //console.log('c1');
                if($('.js_city_name').val().length==0){
                    //console.log('c2');
                    setTimeout(function(){$("#js_publish_demand_submit").trigger('click');},40);
                    return false;
                }else{
                    //console.log('c3');
                    lock=false;
                    $('#js_publish_demand_submit').prop('disabled',true);
                }
            }else{
                //console.log('c5');
                setTimeout(function(){$("#js_publish_demand_submit").trigger('click');},40);
                return false;
            }
            var curHref=location.href;
            if(curHref.indexOf("/need/")!=-1){
                //console.log('普通表单');
                ga('send', 'event', 'fillNeed_page', 'submit_OK'); //普通表单
                _hmt.push(['_sendEvent', 'fillNeed_page', 'submit_OK']);
            }
            if(curHref.indexOf("/seo/publish")!=-1){
                 //console.log('seo/publish页面');
                ga('send', 'event', 'landing_pageS', 'submit_OK');  //seo/publish页面
                _hmt.push(['_sendEvent', 'landing_pageS', 'submit_OK']);
            }
            delCookie_g('js_demand_cate');
        }
    });

}
//房源
function publishHouse(){
    ajaxSearchFun({
        "eventEle":".js_searchComplete",
        "promptUrl":"/service-recommend/autoCompleteAjax",
        //"promptUrl":"../ajax/result.html",
        "ajaxType":"POST",
        "postDataObj":function(){ return $('#service_recommend_form').serialize()},
        "ajaxSuccessCall":function(args){
            var data=args.data;
            var t=args.t;
            var FunArgs=args.FunArgs;
            var eventEle=args.eventEle;
            if(data.status==1){
              var searchResultDiv = $("#searchResultDiv");
              searchResultDiv.css({
                left:$(t).offset().left+"px",
                top:($(t).offset().top+$(t).outerHeight())+"px",
                width:parseInt($(t).outerWidth()-2)+"px"
              })
              searchResultDiv.show();
              searchResultDiv.html("");
              //数据
              if(data.result.data.length>0){
                $(searchResultDiv).addClass('searchResult_cate_'+data.result.cate_id);
                var curdata=data.result.data;
                var searchResultItemTpl='';
                for(var i=0;i<curdata.length;i++){
                   searchResultItemTpl=searchResultItemTpl+FunArgs.resultItemTpl({'eventEle':eventEle,'curdata':curdata[i]});
                }
                searchResultDiv.html(searchResultItemTpl);
                searchResultDiv.data("linksearch",$(t));
              }

            }
        },
        "resultItemTpl":function(args){
            var curdata=args.curdata;
            var eventEle=args.eventEle;
            var html=[];
            html.push(' <div class="searchResultItem special-resultItem">')
            var i=1;
            for( a in curdata){
                var curText= curdata[a];
                html.push('     <div class="item item-'+i+'"><span class="item-span item-span-'+i+'" fieldname="'+a+'" >'+curText+'</span></div>');
                i++;
            }
            html.push(' <div class="clear"></div>')
            html.push(' </div>')
            return html.join('');
        },
        "onconfirmFun":function(args){
            var confirmForm=$(args.eventEle).closest('form');
            var eventEle=args.eventEle;
            var curItemArr=$("#searchResultDiv .cur .item-span");
            for(var i=0; i<curItemArr.length; i++){
                var cuIrtem=curItemArr[i];
                var curFieldName=$(cuIrtem).attr('fieldname');
                var curText=$(cuIrtem).text();
                var curVal=curText;
                var itemInput=$('[name="'+curFieldName+'"]',confirmForm);
                var curOjbTagName=$(itemInput)[0].tagName;
                var curOjbType;
                switch(curOjbTagName){
                    case 'INPUT':
                        curOjbType=$(itemInput).attr('type');
                        break;
                    case 'SELECT':
                        curOjbType='select';
                        break;
                    case 'TEXTAREA':
                        curOjbType='textarea';
                        break;
                    default:;
                }
                switch(curOjbType){
                    case 'radio':
                        break;
                    case 'checkbox':
                        break;
                    case 'select':
                        var selVal='';
                        $('option',itemInput).each(function(index, el) {
                           optText=$(this).text();
                           if(curVal==optText){
                                selVal=$(this).attr('value');
                           }
                        });
                        $(itemInput).val(selVal);
                        $(itemInput).attr('selectvalue',selVal);
                        //模拟Select
                        proxySelectFun();
                        break;
                    default:/*input: text password tle email url number Date pickers (date, month, week, time, datetime, datetime-local) search color;textarea:; */
                        if($(itemInput).closest('.select_pop2').length>0){
                            //select select_pop2
                            var selectPop2=$(itemInput).closest('.select_pop2');
                            $('.js_pop_text',selectPop2).val(curVal);//text
                            var dataArr=$(itemInput).attr('name')+'_Arr';
                            dataArr=eval(dataArr);
                            for(var a=0; a<dataArr.length; a++){
                              if(curVal==dataArr[a]['name']){
                                $('.js_pop_val',selectPop2).val(dataArr[a]['id']); //val
                              }
                            }
                        }else{
                            var addTxt=$(itemInput).attr('add-txt');
                            if(addTxt&&addTxt!=''){
                                curVal=parseInt(curVal);
                            }
                            $(itemInput).val(curVal);
                        }
                }

            }

        }
    });
}
