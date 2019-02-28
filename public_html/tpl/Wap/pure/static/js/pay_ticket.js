var ticketPrice = 0;

function totalNumberClickObj(number){
	ticketPrice = parseFloat(number);
	Cal();
	return true;
}
function totalNumberBtnObj(number){
	if($('.pay_submit').prop('disabled') == false){
		$('.pay_submit').trigger('click');
	}
}
function totalNumberErrObj(tipText){
	// motify.log('单张票的'+tipText);
	return true;
}
function totalNumberFocus(){
	if(default_money > 0){
		$('#money .widget_number').attr('style','').html(default_money);
		totalNumberClickObj(default_money);
	}else{
		$('#g_fy').html('￥0');
		$('#money').trigger('click');
	}
}


function widgetNumberShow(){
	if($('.pay_submit').prop('disabled') == false){
		$('.widget_number_event_btn').css('background','#44cec1');
	}
	$('.pay_submit').hide();
}
function widgetNumberHide(){
	$('.pay_submit').show();
}

function changefy(){
	choose_cinsure = $('#like0').prop('checked');
	console.log(choose_cinsure);
	Cal();
}

function opendiv(){
	$('.popup').show();
}

$(function(){
	var input1param = {};
	input1param.obj = $('#money');
	input1param.clickObj = 'totalNumberClickObj';
	input1param.btnObj = 'totalNumberBtnObj';
	input1param.errObj = 'totalNumberErrObj';
	input1param.loadOkFun = 'totalNumberFocus';
	input1param.showFun = 'widgetNumberShow';
	input1param.hideFun = 'widgetNumberHide';
	input1param.maxNum = 100000;
	input1param.decimalLength = 2;
	input1param.defaultText = '';
	input1param.defaultTextStyle = 'color:#999;font-size:14px;';
	input1param.btnHtml = '<div style="line-height:26px;margin-top:30px;">确认<br/>支付</div>';
	input1param.btnStyle = 'background:#BCBCBC;color:white;text-align:center;';
	inputNumber(input1param);
	
	$('#moneyBox,#moneyTip').click(function(e){
		$('#money').trigger('click');
		return false;
	});
	
	$(".pay_submit").removeClass("on").attr('disabled',true);
	
    $(".cik").click(function(){
        if($(this).hasClass("on")){
            $(this).removeClass("on");
            $(".li_s").hide();
        }else{
            $(this).addClass("on");
            $(".li_s").show();
			$('#noDiscountNumber').trigger('click');
			return false;
        }
    });
    $(".pay_top").click(function(){
		if(can_change_store){
			if($(".choice").is(":hidden")){
				$('.choice .close').show();
				$(".choice").slideDown();
				$(".mask").show();
			}else{
				$(".choice").slideUp();
				$(".mask").hide();
			}
		} 
    });
	if($('#store_id').data('store_id')==''){
		$('.choice .close').hide();
		$('.choice').slideDown();
		$('.mask').show();
	}
	
	$('.decrease').click(function(){
		var pay_more = parseInt($('#pay_more').val());
		if(pay_more - 1 >= limit_num){
			$('#pay_more').val(pay_more-1);
			$('.decrease').prop('disabled',false);		
			if(pay_more - 1 == limit_num){
				$('.decrease').prop('disabled',true);
			}
			Cal();
		}else{
			$('.decrease').prop('disabled',true);
		}
	});
	$('.increase').click(function(){
		var pay_more = parseInt($('#pay_more').val());
		$('#pay_more').val(pay_more+1);
		$('.decrease').prop('disabled',false);
		Cal();
	});
	
	// var pay_more = parseInt($('#pay_more').val());
	
	$('#pay_more').val(limit_num);
	
	$('.popup').click(function(){
		$('.popup').hide();
	});
	$('.code').click(function(){
		return false;
	});
	
	$('.pay_submit').click(function(){
		$('#ticketPrice').val(ticketPrice);
		$('#pay_num').val(parseInt($('#pay_more').val()));
		$('#choose_cinsure').val($('#like0').prop('checked') ? '1' : '0');

		$('.submit').trigger('click');
	});
});

function Cal(){
	console.log(ticketPrice);
	
	var pay_more = parseInt($('#pay_more').val());
	
	var insure = 0;
	var charge = 0;
	if(ticketPrice > 0){
		//支付手续费
		if(have_charge && charge_tikcet_3 != 0 && ticketPrice >= charge_tikcet_3){
			$('#g_fy').html('￥'+charge_3);
			charge = charge_3;
		}else if(have_charge && charge_tikcet_2 != 0 && ticketPrice >= charge_tikcet_2){
			$('#g_fy').html('￥'+charge_2);
			charge = charge_2;
		}else if(have_charge && ticketPrice >= charge_tikcet_1){
			$('#g_fy').html('￥'+charge_1);
			charge = charge_1;
		}
		
		//乘意险
		if(choose_cinsure){
			if(insure_tikcet_3 != 0 && ticketPrice >= insure_tikcet_3){
				$('#k_fy').html('￥'+insure_3);
				insure = insure_3;
			}else if(insure_tikcet_2 != 0 && ticketPrice >= insure_tikcet_2){
				$('#k_fy').html('￥'+insure_2);
				insure = insure_2;
			}else if(have_insure && ticketPrice >= insure_tikcet_1){
				$('#k_fy').html('￥'+insure_1);
				insure = insure_1;
			}
		}else{
			$('#k_fy').html('￥0');
		}
	}else{
		$('#g_fy').html('￥0');
		$('#k_fy').html('￥0');
	}
	
	var total_money = parseFloat(((ticketPrice + charge + insure) * pay_more).toFixed(2));
	
	$('#smoney').text(total_money);
	
	if(total_money > 0){
		$(".pay_submit").addClass("on").attr('disabled',false);
		$('.widget_number_event_btn').css('background','#44cec1');
	}else{
		$(".pay_submit").removeClass("on").attr('disabled',true);  
		$('.widget_number_event_btn').css('background','#BCBCBC');
	}
}

changefy();