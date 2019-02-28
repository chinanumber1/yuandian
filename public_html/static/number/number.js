/*加载样式库*/
function baseUrl(){
    var scriptSrc =document.getElementsByTagName('script')[document.getElementsByTagName('script').length-1].src;
    var jsName =scriptSrc.split('/')[scriptSrc.split('/').length-1];
    return scriptSrc.replace(jsName,'');
}
// var numberBaseUrl = baseUrl();
var numberBaseUrl = '/static/number/';

var widgetNumberLoadOkFun = null;
var widgetNumberHideFun = null;
$("<link>").attr({rel:"stylesheet",type:"text/css",href:numberBaseUrl+"number.css"}).appendTo("head");
$(function(){
	$.get(numberBaseUrl+"number.html",function(result){
		$('body').append(result);
		if(widgetNumberLoadOkFun){
			window[widgetNumberLoadOkFun]();
		}
	});
	
	$("#widget_container").on("click", function(e){
		e = e || window.event;
		if(!$(e.target).closest('.widget_number_input_box').length && $(".widget_number_input_box").is(':visible')){
			$(".widget_number_input_box").hide();
			$(".wiget_number_cursor").removeClass('wiget_number_show');
			$('#widget_container').css({'height':'','overflow':'','padding-bottom':''});
			if(widgetNumberHideFun){
				window[widgetNumberHideFun]();
			}
		}
	});
});
var nowObj = null;
var nowClickObj = null;
var nowBtnObj = null;
var nowErrObj = null;
var nowMaxNum = null;
var nowDecimalLength = null;
var nowBtnHtml = null;
var nowDefaultText = null;
var nowDefaultTextStyle = null;
/*
 * objParam object
 * obj dom
 * clickObj 正确点击数字的事件 obj null
 * btnObj   按钮点击数字的事件 obj null
 * errObj   按钮点击数字的事件 obj null(alert) emptystring(不提醒)
 * maxNum   最大值 int null
 * decimalLength   最多的小数位  int null
 * isSimple   是否是简约 boolean null
 * btnHtml   按钮的HTML html null
 * btnStyle   按钮的样式 style null
 * defaultText   未输入金额时的提示文字 text null
 * defaultTextStyle   未输入金额时的提示样式 style null
 */
function inputNumber(objParam){
	console.log(objParam);
	if(objParam.loadOkFun){
		widgetNumberLoadOkFun = objParam.loadOkFun;
	}
	if(objParam.hideFun){
		widgetNumberHideFun = objParam.hideFun;
	}
	var obj = objParam.obj;
	obj.click(function(event){
		if(objParam.isSimple){
			$('.widget_number_full').hide();
			$('.widget_number_simple').show();
		}else{
			if(objParam.btnHtml){
				$('.widget_number_event_btn').html(objParam.btnHtml);
				if(objParam.btnStyle){
					$('.widget_number_event_btn').attr('style',objParam.btnStyle);
				}
			}else{
				$('.widget_number_event_btn').html('<div style="background:url('+numberBaseUrl+'weixin_pay.png);width:30px;height:30px;background-size: 100%;display:inline-block;text-align:center;margin-top:24px;margin-bottom:4px;"></div><div style="font-size:16px;line-height:20px;">付款</div>').attr('style','background:#1AAD19;height:112px;color:white;text-align:center;');
			}
			$('.widget_number_simple').hide();
			$('.widget_number_full').show();
		}
		$('.widget_number_input_box').show();
		$('#widget_container').css({height:$(window).height()-$('.widget_number_input_box').height()-50-20,overflow:'scroll','padding-bottom':'20px'});
		if(objParam.showFun){
			window[objParam.showFun]();
		}
		obj[0].scrollIntoViewIfNeeded(true);
		$(".wiget_number_cursor").removeClass('wiget_number_show');
		obj.find('.wiget_number_cursor').addClass('wiget_number_show');
		nowObj = obj;
		nowClickObj = objParam.clickObj;
		nowBtnObj = objParam.btnObj;
		nowErrObj = objParam.errObj != null ? objParam.errObj : 'alert';
		nowMaxNum = objParam.maxNum;
		nowDecimalLength = objParam.decimalLength;
		nowBtnHtml = objParam.btnHtml;
		nowDefaultText = objParam.defaultText;
		nowDefaultTextStyle = objParam.defaultTextStyle;
		
		$('.widget_number_col,.widget_number_event_back,.widget_number_event_btn').off('click').on('click',function(){
			var that = $(this);
			that.addClass('widget_number_gray');
			setTimeout(function(){
				that.removeClass('widget_number_gray');
			},100);
			var oldHtml = nowObj.find('.widget_number').html();
			var clickHtml = that.html();
			if(that.hasClass('widget_number_col')){
				if(nowDefaultText &&　oldHtml == nowDefaultText){
					oldHtml = '';
				}
				if(clickHtml == '.'){
					if(oldHtml.indexOf('.') > 0){
						return false;
					}
				}
				if((clickHtml == '.' || clickHtml == '0') && oldHtml == ''){
					oldHtml = '0';
					clickHtml = '.';
				}
				var newHtml = oldHtml+clickHtml;
				if(nowMaxNum && parseFloat(newHtml) > nowMaxNum){
					if(nowErrObj != ''){
						window[nowErrObj]('金额不能超过'+nowMaxNum);
					}
					return false;
				}
				if(nowDecimalLength){
					var decimalArr = newHtml.split('.');
					if(decimalArr.length == 2 && decimalArr[1].length > nowDecimalLength){
						if(nowErrObj != ''){
							window[nowErrObj]('仅支持两位小数');
						}
						return false;
					}
				}
				if(nowClickObj){
					if(window[nowClickObj](parseFloat(newHtml))){
						nowObj.find('.widget_number').attr('style','');
						nowObj.find('.widget_number').html(oldHtml+clickHtml);
					}
				}else{
					nowObj.find('.widget_number').attr('style','');
					nowObj.find('.widget_number').html(oldHtml+clickHtml);
				}
			}else if(that.hasClass('widget_number_event_back')){
				if(nowDefaultText &&　oldHtml == nowDefaultText){
					oldHtml = '';
					nowObj.find('.widget_number').attr('style','');
				}
				var newHtml = oldHtml.substring(0,oldHtml.length-1);
				if(newHtml == '0'){
					newHtml = '';
				}
				if(nowClickObj){
					if(window[nowClickObj](newHtml == '' ? 0 : newHtml)){
						if(newHtml == '' && nowDefaultText){
							newHtml = nowDefaultText;
							if(nowDefaultTextStyle){
								nowObj.find('.widget_number').attr('style',nowDefaultTextStyle);
							}
						}
						nowObj.find('.widget_number').html(newHtml);
					}
				}else{
					nowObj.find('.widget_number').html(newHtml);
				}
			}
		});
		
		$('.widget_number_event_btn').off('click').on('click',function(){
			var oldHtml = nowObj.find('.widget_number').html();
			if(nowDefaultText &&　oldHtml == nowDefaultText){
				oldHtml = '';
			}
			window[nowBtnObj](oldHtml);
		});
	
		event.stopPropagation();
	});
	
	var obj_align = obj.css('text-align');
	if(obj_align == 'start' || obj_align == 'left'){
		obj.append('<div class="widget_number" style="'+(objParam.defaultTextStyle ? objParam.defaultTextStyle : '')+'">'+(objParam.defaultText ? objParam.defaultText : '')+'</div><div class="wiget_number_cursor right" style="margin-top:'+(obj.height()*0.15)+'px;"></div>');
	}else{
		obj.prepend('<div class="widget_number" style="'+(objParam.defaultTextStyle ? objParam.defaultTextStyle : '')+'">'+(objParam.defaultText ? objParam.defaultText : '')+'</div><div class="wiget_number_cursor left" style="margin-top:'+(obj.height()*0.15)+'px;"></div>');
	}
}