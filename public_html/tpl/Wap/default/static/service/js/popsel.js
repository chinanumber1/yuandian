$(document).ready(function() {
	new popselFun1();
    //popSelect初始化
    popSelectInit(".select_pop2");
    //.select_pop2 text 设初值
    popselValTextInit();
});
//.select_pop2 text 设初值
function popselValTextInit(){
    $('.select_pop2').each(function(index, el) {
       var eventEle=$(this);
       if($('.js_pop_val',eventEle).attr('initshow')=="false"){
			return ;
       }
       var val=$('.js_pop_val',eventEle).val();
       if(val&&val!=""){
			var textArr=[];
			valArr=val.split('-');
			var dataArray= $(eventEle).attr("dataArray");
			dataArray= dataArray ? eval(dataArray) :[];
			for(var i=0; i<dataArray.length; i++){
				var objV1=dataArray[i];
				if(valArr[0]==objV1.id){
					textArr.push(objV1.name);
				}
				//二层
				if(objV1.child){
					if(objV1.child.length>0){
						for(var j=0; j<objV1.child.length; j++){
							var objV2=objV1.child[j];
							if(valArr[1]==objV2.id){
								textArr.push(objV2.name);
							}
							//三层
							if(objV2.child){
								if(objV2.child.length>0){
									for(var a=0; a<objV2.child.length; a++){
										var objV3=objV2.child[a];
										if(valArr[2]==objV3.id){
											textArr.push(objV3.name);
										}
									}
								}
							}
						}
					}
				}

			}
			$('.js_pop_text',eventEle).val(textArr.join('-'));
			//popSelect初始化
			popSelectInit(".select_pop2");
       }

    });
}

/*popselFun1 start*/
var popselFun1 =function(){
	var TfThis=this;
    TfThis.winH=$(window).height();
    TfThis.winW=$(window).width();
	TfThis.popT= $(".header").length>0 ? $(".header").height() :0;
	TfThis.popH= $(".header").length>0 ? TfThis.winH-TfThis.popT :TfThis.winH;

	$(".pagewrap").on("click",".select_pop2",function(event) {
		var eventEle=$(this);
		$('input',eventEle).blur();
		if($(".popUp_sel").length>0){
			$(".popUp_sel").remove();
		}
		TfThis.init(eventEle);
	});
}
popselFun1.prototype={
	maxSelectCount:null,
	dataArray:null,
	attrName:null,
	level:null,
	eventEle:null,
    leveltype:0,
	init:function(eventEle){
		/*initVal start*/
		var TfThis=this;
		TfThis.eventEle=eventEle;

		TfThis.selectType=$(TfThis.eventEle).attr("selectType");

		TfThis.maxSelectCount= $(TfThis.eventEle).attr("maxSelectCount");
		TfThis.maxSelectCount= TfThis.maxSelectCount ? TfThis.maxSelectCount :1000;

		TfThis.dataArray= $(TfThis.eventEle).attr("dataArray");
		TfThis.dataArray= TfThis.dataArray ? eval(TfThis.dataArray) :[];

		TfThis.attrName= $(TfThis.eventEle).attr("attrName");
		TfThis.attrName= TfThis.attrName ? TfThis.attrName :'';

		TfThis.level= $(TfThis.eventEle).attr("level");
		TfThis.level= TfThis.level ? TfThis.level :1;
        TfThis.leveltype= $(TfThis.eventEle).attr("leveltype");
        TfThis.leveltype= TfThis.leveltype ? TfThis.leveltype :0;

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
            "animObj":".popUp_sel .pop",
            "animOption":"slideInUp"
        });

        //层关闭
        popUpCloseFun(".popUp_sel");
		/*init html*/
		var initVal=$('.js_pop_val',TfThis.eventEle).val();

		if(initVal.length>0){
			initVal=initVal.split("-");
		}
		for(var i=0; i<TfThis.level; i++){
			var lvObj=$(".popUp_sel .pop_lv").eq(i);
			var selectValue=initVal[i];
			selectValue = !selectValue ? "": selectValue;

			$(lvObj).attr("selectValue",selectValue);

			TfThis.setData({"lvObj":lvObj});
		}

        //确认
		TfThis.comfirmFun();
		//liclick;
		TfThis.liEventFun();
		/*checkbox init html*/
		if(TfThis.selectType=="checkbox"){
			var initCheckbox=$('.js_pop_val',TfThis.eventEle).val();
			if(initCheckbox.length>0){
				initCheckbox=initCheckbox.split(",");
				for(var i=0; i<initCheckbox.length; i++ ){
					$('.popUp_sel .pop_lv li[val="'+initCheckbox[i]+'"]').trigger("click");
				}
			}
		}
		if($(".js_pop_other",TfThis.eventEle).length>0){
			if($(".js_pop_other",TfThis.eventEle).val().length>0){
				$('.popUp_sel .pop_lv li[val="other"]').trigger("click");
				$(".js_text_other").trigger("blur");
			}
		}

		/*otherOptionFun*/
		TfThis.otherOptionFun();
	},
	popHtml:function(args){
		var TfThis=this;
		var html = [];
		html.push('');
		html.push('<div class="pop_slideUp popUp_sel" style="bottom:0px;height:'+TfThis.popH+'px">');
		html.push('<div id="gmask"></div>');
		html.push('<div class="pop" id="pop"><span class="close"></span>');
		html.push('		<div class="pop_body">');
		html.push('			<div class="pop_action">');
        html.push('				<a class="btn btn-orange" href="javascript:;" id="pop_comfirm" actionColse="false"><i class="ico ico-sure"></i>确  定</a>');
        html.push('				<a class="btn btn-green" href="javascript:;" actioncolse="true"><i class="ico ico-cancel"></i>取  消</a>');
		html.push('			</div>');
		html.push('			<div class="pop_main"> ');
		html.push('				<div class="lvbg_cur"></div>');
		html.push('				<div class="pop_lv_wrap" >');
		for(var i=0; i<TfThis.level;i++){
			//html.push('				<div class="pop_lv"><ul><li class="s2">1月</li><li class="s1">2月</li><li class="cur">3月</li><li class="s1">4月</li><li class="s2">5月</li><li class="s3">6月</li></ul></div>');
			html.push('				<div class="pop_lv" id="pop_lv_'+i+'"><ul class="option_group" ></ul></div>');
		}
		html.push('				</div>');
		html.push('			</div>');
		html.push('			<div class="pop_checked">');
		html.push('			</div>');
		html.push('		</div>');
		html.push('	</div>');
		html.push('</div>');
		return html.join('');
	},
	setData:function(args){
		var TfThis=this;
		var lvObj=args.lvObj;
		var selectValue=$(lvObj).attr("selectValue");
		var lvObjI=$(".popUp_sel .pop_lv").index(args.lvObj);
		var DataArr=TfThis.dataArray;
		switch(lvObjI){
			case 0:
				break;
			case 1:
                var lv1ArrIndex=TfThis.getArrIndex($(".popUp_sel .pop_lv").eq(0));
                if(lv1ArrIndex!=""){
                    DataArr=DataArr[lv1ArrIndex].child;
                    DataArr = DataArr ? DataArr : [];
                }else{
                    DataArr=[];
                }
				break;
            case 2:
                var lv1ArrIndex=TfThis.getArrIndex($(".popUp_sel .pop_lv").eq(0));
                var lv2ArrIndex=TfThis.getArrIndex($(".popUp_sel .pop_lv").eq(1));
                if(lv1ArrIndex!=""&&lv2ArrIndex!=""){
                    DataArr=DataArr[lv1ArrIndex].child[lv2ArrIndex].child;
                    DataArr = DataArr ? DataArr : [];
                }else{
                    DataArr=[];
                }
                break;
		}
		var lvData=TfThis.createData({"DataArr":DataArr,"attrName":TfThis.attrName,"selectValue":selectValue});
		$(".option_group",lvObj).html(lvData.li);
		TfThis.initStyle(lvObj);
	},
    createData:function(args){
		var TfThis=this;
        var optHot="",optNormal="",liHot="",liNormal="";
        var DataArr=args.DataArr;
        var attrName=args.attrName;
		var prev_letter='true';
        for(var i=0; i<DataArr.length; i++){
            var node = DataArr[i];
            var selAttr = node.id==args.selectValue ? "cur" : "";
            //字母
            if(prev_letter!=node.letter){
               prev_letter=node.letter;
               var node_letter= node.letter ? '<span class="letter">'+node.letter+'</span>' : '';
            }else{
               var node_letter= node.letter ? '<span class="letter"></span>' : '';
            }

            if(node[attrName]==1){
                liHot=liHot+'<li val="'+node.id+'" arrindex="'+i+'" class="'+selAttr+'">'+node_letter+node.name+'</li>';
            }else{
                liNormal=liNormal+'<li val="'+node.id+'" arrindex="'+i+'" class="'+selAttr+'">'+node_letter+node.name+'</li>';
            }
        }

        if($(".js_pop_other",TfThis.eventEle).length==0){
			var liHtml='<li val="" arrindex="" class=""></li><li val="" arrindex="" class=""></li>'+liHot+liNormal+'<li val="" arrindex="" class=""></li><li val="" arrindex="" class=""></li>';
        }else{
			var otherInit=$(".js_pop_other",TfThis.eventEle).val();
			var liHtml='<li val="" arrindex="" class=""></li><li val="" arrindex="" class=""></li>'+liHot+liNormal+
						'<li val="other" arrindex="" class=""><input class="js_text_other form-control" placeholder="其他"  type="text" value="'+otherInit+'" /></li>'+
						'<li val="" arrindex="" class=""></li><li val="" arrindex="" class=""></li>';
        }
        var lvOpt={"li":liHtml};
        return lvOpt;
    },
    getArrIndex:function(lvObj){
        var lvArrIndex=$("li.cur",lvObj).attr("arrindex");
        lvArrIndex = lvArrIndex ? lvArrIndex: '';
        return  lvArrIndex;
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
		$(".pagewrap").off('click', ".popUp_sel .pop_lv li");
        $(".pagewrap").on('click', ".popUp_sel .pop_lv li", function(event) {
			var liVal=$(this).attr("val");
			if(liVal!=""){
				var liText=$(this).text();
				var lvObj=$(this).closest('.pop_lv');
				if($(lvObj).attr("selectValue")!=liVal){
					var curObjI=$(".popUp_sel .pop_lv").index(lvObj);
                    //非并列
                    if(TfThis.leveltype==0){
    					$('.popUp_sel .pop_lv:gt('+curObjI+')').attr("selectValue","");
    					$('.popUp_sel .pop_lv ul:gt('+curObjI+')').html("");
                    }
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
                    //非并列
                    if(TfThis.leveltype==0){
                        var lvObjNext=$(this).closest('.pop_lv').next(".pop_lv");
                        console.log("TfThis.leveltype");
                        TfThis.setData({"lvObj":lvObjNext});
                    }
				}
				//checkbox
				if(TfThis.selectType=="checkbox"){
					if($('.pop_checked_cell[val="'+liVal+'"]').length==0&&liVal!=""){
						$(".popUp_sel .pop_checked").append('<div class="pop_checked_cell" val="'+liVal+'"><i class="del ico ico-del2"></i><span>'+liText+'</span></div>');
					}
				}
			}

		});
		//checkbox
        $(".pagewrap").off('click', ".popUp_sel .pop_checked_cell");
        $(".pagewrap").on('click', ".popUp_sel .pop_checked_cell", function(event) {
			$(this).remove();
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
		$('.popUp_sel .pop_lv').on('scroll',scrollFun);
		//$('.popUp_sel .pop_lv').on('touchmove',scrollStartFun);
		//$('.popUp_sel .pop_lv').on('touchend',scrollEndFun);
    },
    comfirmFun:function(){
		var TfThis=this;
		$(".pagewrap").off("click","#pop_comfirm");
		$(".pagewrap").on("click","#pop_comfirm",function(){
			var txtArr=[];
			var valArr=[];
			var otherText="";
			if(TfThis.selectType=="checkbox"){
				$(".popUp_sel .pop_checked_cell").each(function(index, el) {
					if($(this).text()!=""){txtArr.push($(this).text());}
					if($(this).attr("val")!=""&&$(this).attr("val")!="other"){valArr.push($(this).attr("val"));}
				});
				$(".js_pop_text",TfThis.eventEle).val(txtArr.join(","));
				$(".js_pop_val",TfThis.eventEle).val(valArr.join(","));
				otherText=$('.pop_checked_cell[val="other"]').text();
			}else{
				$(".popUp_sel .pop_lv").each(function(index, el) {
					if($("li.cur",$(this)).length>0){
						if($("li.cur",$(this)).attr("val")!=""){
							//other 选项
							if($("li.cur",$(this)).attr("val")=="other"){
								txtArr.push($("li.cur .js_text_other",$(this)).val());
								otherText=$("li.cur .js_text_other",$(this)).val();
							}else{
								var curLetter=$("li.cur .letter",$(this)).text();
								var curtxt=$("li.cur",$(this)).text();
								curtxt=curtxt.replace(curLetter,'');
								txtArr.push(curtxt);
							}

						}
						if($("li.cur",$(this)).attr("val")!=""&&$("li.cur",$(this)).attr("val")!="other"){
							valArr.push($("li.cur",$(this)).attr("val"));
						}

					}
				});
                var jointype=$(TfThis.eventEle).attr('jointype');
                if($(TfThis.eventEle).attr('jointype')==undefined){
                    jointype='-';
                }
				$(".js_pop_text",TfThis.eventEle).val(txtArr.join(jointype));
				$(".js_pop_val",TfThis.eventEle).val(valArr.join(jointype));
				$(".js_pop_text",TfThis.eventEle).blur();
				$(".js_pop_val",TfThis.eventEle).blur();
			}
			//other 选项
			if($(".js_pop_other",TfThis.eventEle).length>0){
				$(".js_pop_other",TfThis.eventEle).val(otherText);
			}
			popSelectSpanSet(TfThis.eventEle);
            //回调函数
            var selectPopCallbackArgs={'eventEle':$(TfThis.eventEle),'text':txtArr,'val':valArr};
            var selectPopCallback = $(TfThis.eventEle).attr("selectPopCallback");
            if(selectPopCallback!=undefined&&selectPopCallback!=''){
                selectPopCallback=eval(selectPopCallback);
                selectPopCallback.call(null,selectPopCallbackArgs);
            }
			$(".popUp_sel .close").trigger("click");

		});
    },
	initStyle:function(lvObj){
		var TfThis=this;
		if($(".option_group li",lvObj).length>0){
			/*$(lvObj).removeClass("hidden");*/
		}else{
			/*$(lvObj).addClass("hidden");*/
		}
		//init style
		if($(".option_group li.cur",lvObj).length==0){
			$(".option_group li:eq(2)",lvObj).addClass("cur");
		}
		var liCurIndex=$(".option_group li.cur",lvObj).index();
		if(liCurIndex!=-1){
			//stopScrollFun 定位
			TfThis.stopScrollFun(lvObj);
			//styleAnimFun 动画样式
			TfThis.styleAnimFun(liCurIndex,lvObj);
		}

	},
	otherOptionFun:function(){
		$(".pagewrap").on('keyup blur', '.js_text_other', function(event) {
			$('.pop_checked_cell[val="other"] span').html($(this).val());
		});
	}

}
