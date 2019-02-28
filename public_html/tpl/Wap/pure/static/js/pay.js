var total = 0,discount = 0,discount_type=0,che = 0,master = parseFloat($(".master").text()),reduce = parseFloat($(".reduce").text()),frac = parseFloat($(".frac").text());

function totalNumberClickObj(number){
	if($('#store_id').data('store_id')==''){
		motify.log('请选择店铺');
		$(".choice").slideDown();
		$(".mask").show();
		$('#totalNumber .widget_number').html('');
		return false;
	}else{
		total = number;
		$('.total_money').val(total);
		Cal();
	}
	return true;
}
function totalNumberBtnObj(number){
	if($('.submit').prop('disabled') == false){
		$('.submit').trigger('click');
	}
}
function totalNumberErrObj(tipText){
	motify.log(tipText);
	return true;
}
function totalNumberFocus(){
	if($('#store_id').data('store_id')){
		if(default_money > 0){
			$('#totalNumber .widget_number').attr('style','').html(default_money);
			totalNumberClickObj(default_money);
		}else{
			$('#totalNumber').trigger('click');
		}
	}
}

function noDiscountNumberClickObj(number){
	discount = number;
	if(total >= discount){
		$('.no_discount_money').val(discount);
		Cal();
		return true;
	}else{
		return false;
	}
}
function noDiscountNumberBtnObj(number){
	if($('.submit').prop('disabled') == false){
		$('.submit').trigger('click');
	}
}
function noDiscountNumberErrObj(tipText){
	motify.log(tipText);
	return true;
}
function widgetNumberShow(){
	if($('.submit').prop('disabled') == false){
		$('.widget_number_event_btn').css('background','#44cec1');
	}
	$('.submit').hide();
	$('.actual_pay_box').css('bottom',$('.widget_number_input_box').height()).show();	
}
function widgetNumberHide(){
	$('.submit').show();
	$('.actual_pay_box').hide();
}
$(function(){
	var input1param = {};
	input1param.obj = $('#totalNumber');
	input1param.clickObj = 'totalNumberClickObj';
	input1param.btnObj = 'totalNumberBtnObj';
	input1param.errObj = 'totalNumberErrObj';
	input1param.loadOkFun = 'totalNumberFocus';
	input1param.showFun = 'widgetNumberShow';
	input1param.hideFun = 'widgetNumberHide';
	input1param.maxNum = 200000;
	input1param.decimalLength = 2;
	input1param.defaultText = '询问店员后输入';
	input1param.defaultTextStyle = 'color:#999;font-size:14px;';
	input1param.btnHtml = '<div style="line-height:26px;margin-top:30px;">确认<br/>支付</div>';
	input1param.btnStyle = 'background:#BCBCBC;color:white;text-align:center;';
	inputNumber(input1param);
	
	var input2param = {};
	input2param.obj = $('#noDiscountNumber');
	input2param.clickObj = 'noDiscountNumberClickObj';
	input2param.btnObj = 'noDiscountNumberBtnObj';
	input2param.errObj = 'noDiscountNumberErrObj';
	input2param.showFun = 'widgetNumberShow';
	input2param.hideFun = 'widgetNumberHide';
	input2param.decimalLength = 2;
	input2param.defaultText = '询问店员后输入';
	input2param.defaultTextStyle = 'color:#999;font-size:14px;';
	input2param.btnHtml = '<div style="line-height:26px;margin-top:30px;">确认<br/>支付</div>';
	input2param.btnStyle = 'background:#BCBCBC;color:white;text-align:center;';
	inputNumber(input2param);
	
	$(".submit").removeClass("on");
	$(".submit").attr('disabled',true);  
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
	$("#bind_user").click(function(){
        window.location.href=$(this).data('href')
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
	
	$('.total_money').click(function(){
		$('#totalNumber').trigger('click');
		return false;
	})
	$('.no_discount_money').click(function(){
		$('#noDiscountNumber').trigger('click');
		return false;
	})
    $(".close,.mask").click(function(){
		if($('input[name="store_id"]').val() != ''){
			$(".choice").slideUp();
			$(".mask").hide();
		}
    });

    $(".choice ul li").click(function(){
        var text = $(this).find("h2").text();
        var store_id = $(this).find("h2").data('store_id');
		$("#store_id").data('store_id',store_id);

        master = $(this).find("h2").data('condition_price');
        reduce = $(this).find("h2").data('minus_price');
        frac   = $(this).find("h2").data('discount_percent');
        discount_type = $(this).find("h2").data('discount_type');
		$('.total').val('');
		$('.discount').val('');
		if(discount_type==0){
			$('.discount_div').hide();
			$('.che').html(0);
		}else if(discount_type==2){
			$('.discount_div').show();
			$(".zhe").hide();
			$(".man").show();
		}else{
			$('.discount_div').show();
			$(".man").hide();
			if(frac>0){
				$(".zhe").show()
			}
		}
        $(".choice").slideUp();
        $(".mask").hide();
        $(".pay_top span").text(text);
		$('input[name="store_id"]').val(store_id);
		$(".master").text(master);
		$(".reduce").text(reduce);
		$(".frac").text(frac);
		$(".surplus").text(0).parents("i").hide();
		
		setTimeout(function(){
			totalNumberFocus();
		},100);
    });
	
	can_change_store && getUserLocation({useHistory:false,okFunction:'getIframe'});
	
	if(can_change_store && ($.cookie('userLocation')==null || typeof($.cookie('userLocation'))=='undefinded')){
		var i = setInterval(function(){
			if($.cookie('userLocation') != null){
				clearInterval(i);
				window.location.reload();
			}
		},1000);
	}
});

function getIframe(userLonglat,userLong,userLat){
	geoconv('realResult',userLong,userLat);
}

function realResult(result){
	var lat_wx = result.result[0].y;
	var lng_wx = result.result[0].x;
	
}


function get_store_list(){
	$.ajax({
		url: '/wap.php?g=Wap&c=My&a=ajax_get_store_list',
		type: 'POST',
		dataType: 'json',
		data: {mer_id: mer_id},
		success:function(data){
			// var store_list = data.store_list;
			console.log(data)
			// if(store_list){
				// str = '';
				// for(var x=0;x<store_list.length;x++){
					// str	+=	' <li>';
					// str	+=	'	<h2 data-store_id="'+store_list[x].store_id+'" data-discount_type="'+store_list[x].discount_type+'" data-discount_percent="'+store_list[x].discount_percent+'" data-condition_price="'+store_list[x].condition_price+'" data-minus_price="'+store_list[x].minus_price+'">'+store_list[x].name+'</h2>';
					// str	+=	'	<p>'+store_list[x].area_ip_desc+' '+store_list[x].adress+'</p>';
					// str	+=	'<div class="distance">距离您的位置 '+store_list[x].range+'</div>';
					// str	+=	'</li>';
				// }
				// $('.choice ul').html(str)
			
			// }
		}
	});

}

function Cal(){
	var reg = /^[0-9]+([.]{1}[0-9]{1,2})?$/;
	total = $('.total_money').val();
	after_subtract  =0;
	discount_compare = false;
	if(total != 0 && total >= discount){
		$(".submit").addClass("on");
		$(".submit").attr('disabled',false);  
		
		if($(".zhe").is(":hidden") && master>0 && total>=master){
			var subtract = (Math.floor((total-discount)/master)*reduce).toFixed(2);
		}else if(frac>0) {
			var subtract = parseFloat((total-discount)*(1-(frac/10)).toFixed(2)).toFixed(2);
			if(frac==0) subtract=0;
		}else{
			subtract = 0;
		}
		
			if(vip_discount_type==1){
				if(level_discount_type==1){
					after_subtract = parseFloat((total-discount)*(1-(discount_v/10)).toFixed(2)).toFixed(2)
				}else if(level_discount_type==2){
					after_subtract = discount_v;
				}
				discount_compare = true;
				
			}else if(vip_discount_type==2 ){
				if(level_discount_type==1){
					after_subtract = parseFloat((total-discount-subtract)*(1-(discount_v/10)).toFixed(2)).toFixed(2)
				}else if(level_discount_type==2){
					after_subtract = discount_v;
				}
				total = total-after_subtract<0?0:total-after_subtract;
			}
			if(after_subtract>0){
				$(".che_1").text(after_subtract).parents(".price").show();
			}else{
				$(".che_1").text(0).parents(".price").show()
			}
		
		
		if(subtract>0){
			$(".che").text(subtract).parents(".price").show();
		}else{
			$(".che").text(subtract).parents(".price").hide()
		}
	
		if(discount_compare && (Number(after_subtract)>Number(subtract))){
			subtract = after_subtract;
			if(total-subtract<0){
				subtract = total;
			}
		}

		
		$(".surplus").text((total-subtract).toFixed(2)*100/100).parents("i").show();
		$('.actual_pay_span').html($(".surplus").text());
		$('.widget_number_event_btn').css('background','#44cec1');
	}else{
		$(".submit").removeClass("on");
		$(".submit").attr('disabled',true);  
		$(".che").text(0).parents(".price").hide();
		$(".che_1").text(0).parents(".price").hide();
		$(".surplus").text(0).parents("i").hide();
		$('.actual_pay_span').html($(".surplus").text());
		$('.widget_number_event_btn').css('background','#BCBCBC');
	}
}