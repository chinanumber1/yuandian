var merchant_card_use = 0,merchant_coupon_id = 0,merchant_coupon_discount = 0,order_info=null,payPrice=0,card_data=null,user_pay_timer = null;
$(function(){
	$(".settlement").height($(window).height()-94);
	
	var staffArr = common.getCache('store_staff',true);
	console.log(staffArr);
	
	if(staffArr.is_change == '0'){
		$('#changeOrderMoney').hide();
	}
	
	common.onlyScroll($(".settlement"));
	
	order_info = common.getCache('order_info',true);
	if(!order_info){
		history.go(-1);
	}
	common.setData({order_info:order_info});
	console.log(order_info);
	
	$('#scan_code_img').attr('src',order_info.pay_qrcode_url);
	
	getOfflinePayMethod();
	
	
	card_data = common.getCache('card_data',true);
	var discount = 10,card_money = 0;
	if(card_data){
		discount = card_data.discount;
		card_money = card_data.card_money;
		console.log(card_data);
		common.setData({card_data:card_data});
	}else{
		$('#card_data,#merchant_coupon_li,#merchant_discount_li').hide();
	}
	
	if (discount > 0 && discount != 10) {
		$('#merchant_discount').html(discount + '折');	
	}else{
		$('#merchant_discount').html('无折扣');
	}
	
	payPrice = parseFloat(parseFloat(discount * 0.1 * order_info.price).toFixed(2));
	$('#pay_price').html('￥' + payPrice);
	
	if(card_money > payPrice){
		merchant_card_use = payPrice;
		$('#user_card_money').html('￥' + payPrice);
	} else {
		merchant_card_use = card_money;
		$('#user_card_money').html('￥' + card_money);
	}
	$('#otherPayMoney').html('￥' + common.floatVal(payPrice-merchant_card_use));
	if($('#otherPayMoney').html() == '￥0'){
		$('.xxzf,.wxzf,.zfb').hide();
		$(".set_end li").removeClass("on");
	}else{
		$('.xxzf,.wxzf,.zfb').show();
		$(".set_end li.xxzf").addClass("on").siblings().removeClass('on');
	}
	
	$("#changeOrderMoney").click(function(){
		$('#change_price').val('');
		$('#change_price_reason').val('');
		$('#order_money_now').html(payPrice);
		$("#change_order_price,.mask").show();
	});
	$('#change_order_price .recovery').click(function(){
		payPrice = parseFloat(parseFloat(discount * 0.1 * order_info.price - merchant_coupon_discount).toFixed(2));
		$('#pay_price').html('￥' + payPrice);
		$('#otherPayMoney').html('￥' + common.floatVal(payPrice-merchant_card_use));
		if($('#otherPayMoney').html() == '￥0'){
			$('.xxzf,.wxzf,.zfb').hide();
			$(".set_end li").removeClass("on");
		}else{
			$('.xxzf,.wxzf,.zfb').show();
			$(".set_end li.xxzf").addClass("on").siblings().removeClass('on');
		}
		$(".amend,.mask").hide();
	});
	$('#change_order_price .ensure').click(function(){
		if($('#change_price').val() == ''){
			motify.log('金额不能为空');
			return false;
		}
		var tmp_money = parseFloat($('#change_price').val());
		if(isNaN(tmp_money)){
			motify.log('请输入正确的价格');
			return false;
		}
		common.http('Storestaff&a=shop_change_price',{'order_id':order_info.order_id,'change_price':$('#change_price').val(),'change_price_reason':$('#change_price_reason').val()}, function(data){
			payPrice = tmp_money;
			$('#pay_price').html('￥' + tmp_money);
			
			if(merchant_card_use > payPrice){
				merchant_card_use = payPrice;
				$('#user_card_money').html('￥' + payPrice);
			}
			$('#otherPayMoney').html('￥' + common.floatVal(payPrice-merchant_card_use));
			if($('#otherPayMoney').html() == '￥0'){
				$('.xxzf,.wxzf,.zfb').hide();
				$(".set_end li").removeClass("on");
			}else{
				$('.xxzf,.wxzf,.zfb').show();
				$(".set_end li.xxzf").addClass("on").siblings().removeClass('on');
			}
			$(".amend,.mask").hide();
		});
	});
	
	
	//修改价格
	$("#change_card_money").click(function(){
		$('#merchant_card_money').val('');
		$('#money_now').html(merchant_card_use);
		$("#merchant_card_change,.mask").show();
	});
	$(".mask,.amend .del").click(function(){
		$(".amend,.mask").hide();
	})
	
	$('#merchant_card_change .recovery').click(function(){
		if(card_money > payPrice){
			merchant_card_use = payPrice;
			$('#user_card_money').html('￥' + payPrice);
		} else {
			merchant_card_use = card_money;
			$('#user_card_money').html('￥' + card_money);
		}
		$('#otherPayMoney').html('￥' + common.floatVal(payPrice-merchant_card_use));
		if($('#otherPayMoney').html() == '￥0'){
			$('.xxzf,.wxzf,.zfb').hide();
			$(".set_end li").removeClass("on");
		}else{
			$('.xxzf,.wxzf,.zfb').show();
			$(".set_end li.xxzf").addClass("on").siblings().removeClass('on');
		}
		$(".amend,.mask").hide();
	});
	
	$('#merchant_card_change .ensure').click(function(){
		var tmp_money = parseFloat($('#merchant_card_money').val());
		if(isNaN(tmp_money)){
			motify.log('请输入正确的价格');
			return false;
		}
		if(tmp_money > card_data.card_money){
			motify.log('使用会员卡余额不能超过当前余额');
			return false;
		}
		if(tmp_money > payPrice){
			motify.log('使用会员卡余额不能超过实付金额');
			return false;
		}
		merchant_card_use = tmp_money;
		$('#user_card_money').html('￥' + tmp_money);
		$('#otherPayMoney').html('￥' + common.floatVal(payPrice-merchant_card_use));
		if($('#otherPayMoney').html() == '￥0'){
			$('.xxzf,.wxzf,.zfb').hide();
			$(".set_end li").removeClass("on");
		}else{
			$('.xxzf,.wxzf,.zfb').show();
			$(".set_end li.xxzf").addClass("on").siblings().removeClass('on');
		}
		$(".amend,.mask").hide();
	});
	
	// 支付
	$(".set_end li").click(function(){
		if($(this).hasClass("on")){
			$(this).removeClass("on");
		}else{
			$(this).addClass("on").siblings().removeClass("on");
		}
	})

	//优惠劵
	$(".choice").click(function(){
		if(card_data){
			var coupon_list = card_data.card_new;
			var temp_price = parseFloat((order_info.price * card_data.discount * 0.1).toFixed(2));
			if(coupon_list.length > 0){
				var can_use_coupon_list = [];
				for(var i in coupon_list){
					if (parseFloat(coupon_list[i].full_money) <= temp_price) {
						can_use_coupon_list.push(coupon_list[i]);
					}
				}
				if(can_use_coupon_list.length > 0){
					laytpl($('#listCouponTpl').html()).render(can_use_coupon_list, function(html){
						$(".coupon ul").html(html);
					});
					$('.coupon .h2 span').html('选择优惠券');
					$(".coupon,.mask").show();
				}else{
					motify.log('无可用优惠券');
				}
			}else if(card_data.card_can_had > 0){
				$('.coupon .h2 span').html('用户扫码领优惠券');
				$('#coupon_can_had').html(card_data.card_can_had);
				$(".coupon ul").hide();
				$(".coupon .no_coupon").show();
				var qrCon = requestUrl.replace('appapi.php','wap.php')+'My_card&a=merchant_card&mer_id='+staffArr.mer_id;
				var qrSrc = requestUrl.replace('appapi.php','index.php')+'Recognition&a=get_own_qrcode&qrCon='+encodeURIComponent(qrCon);
				if($('#merchant_card_qrcode').prop('src') != qrSrc){
					$('#merchant_card_qrcode').prop('src',qrSrc);
				}
				$(".coupon,.mask").show();
			}else{
				motify.log('无可用优惠券');
			}
		}else{
			motify.log('未输入会员卡信息');
		}
	});
	$(".mask,.coupon .del").click(function(){
		$(".coupon,.mask").hide();
	});

	$(document).on('click','.coupon li',function(){
		var price=$(this).data("reduce");
		var total=$(this).data("full");
		$(".coupon,.mask").hide();
		$(".choice").addClass('ce9').html('满'+total+'减'+price+'元');
		merchant_coupon_id = $(this).data('id');
		merchant_coupon_discount = price;
	
		payPrice = parseFloat(parseFloat(discount * 0.1 * order_info.price - merchant_coupon_discount).toFixed(2));
		$('#pay_price').html('￥' + payPrice);
		
		if(merchant_card_use > payPrice){
			merchant_card_use = payPrice;
			$('#user_card_money').html('￥' + payPrice);
		}
		$('#otherPayMoney').html('￥' + common.floatVal(payPrice-merchant_card_use));
		if($('#otherPayMoney').html() == '￥0'){
			$('.xxzf,.wxzf,.zfb').hide();
			$(".set_end li").removeClass("on");
		}else{
			$('.xxzf,.wxzf,.zfb').show();
			$(".set_end li.xxzf").addClass("on").siblings().removeClass('on');
		}
	});

	//支付
	$(".affirm").click(function(){
		if($(".set_end li.xxzf").hasClass("on")){
			$('#offline_money').html(common.floatVal(payPrice-merchant_card_use));
			$(".js_line,.mask").show(); 
			$(".js_line").css("margin-top",-$(".js_line").height()/2);
		}else if($(".set_end li.wxzf").hasClass("on")){
			$(".wechat,.mask").show();
		}else if($(".set_end li.zfb").hasClass("on")){
			$(".alipay,.mask").show();
		}else if($(".set_end li.wxsm").hasClass("on")){
			$(".scan_code,.mask").show();
			user_pay_timer = setInterval(function(){
				common.http('Storestaff&a=shop_arrival_check',{order_id:order_info.order_id,noTip:true}, function(data){
					clearInterval(user_pay_timer);
					common.removeCache('card_data',true);
					common.removeCache('buy_list',true);
					$(".back_index,.pay_ok_mask").show();
				},function(){
					
				});
			},2000);
		}else{
			if($('#otherPayMoney').html() == '￥0'){
				go_pay('', '', '');
			}else{
				motify.log('请选择支付方式');
				return false;
			}
		}
	});
	
	$(document).on('click','#offline_method dd',function(){
		go_pay('', '', $(this).data('id'));
	});
	
	$('#offline_cash').keyup(function(){
		var tmpCash = parseFloat($('#offline_cash').val());
		if(isNaN(tmpCash)){
			$('#offline_zhaoling').html('0.00');
		}else{
			$('#offline_zhaoling').html(common.floatVal(tmpCash-(payPrice-merchant_card_use)));
		}
	});
	
	$(".mask,.js_line .del,.popup .del,.back_index .del,.seek .del,.empty .close").click(function(){
		$(".js_line,.popup,.back_index,.seek,.mask").hide();
	});

	$(".cancel").click(function(){
		$(".seek,.mask").show(); 
	});
	
	$('.empty .ensure').click(function(){
		common.removeCache('card_data',true);
		common.removeCache('buy_list',true);
		common.removeCache('total_goods',true);
		history.go(-1);
	});
	
	$('#weixin_input_dom em').click(function(){
		common.scan('scanWeixinPayResult');
	});
	$('#weixin_btn').click(function(){
		if($('#weixin_input').val() == '' || !/^[0-9]*$/.test($('#weixin_input').val())){
			motify.log("微信付款码是数字",3000,{},20);
			return false;
		}
		go_pay('weixin',$('#weixin_input').val(), -1);
	});
	
	$('#alipay_input_dom em').click(function(){
		common.scan('scanAliPayResult');
	});
	$('#alipay_btn').click(function(){
		if($('#alipay_input').val() == '' || !/^[0-9]*$/.test($('#alipay_input').val())){
			motify.log("支付宝付款码是数字",3000,{},20);
			return false;
		}
		go_pay('alipay',$('#alipay_input').val(), -1);
	});
	
	$('.refresh').click(function(){
		common.http('Storestaff&a=ajax_card', {'key':card_data.card_id}, function(data){
			data.discount = common.floatVal(data.discount);
			data.discount = common.floatVal(data.discount);
			common.setCache('card_data',data,true);
			location.reload();
		});
	});
});

function scanAliPayResult(str){
	if(!/^[0-9]*$/.test(str)){
        motify.log("支付宝付款码是数字",3000,{},20);
		return false;
    }
	$('#alipay_input').val(str);
	$('#alipay_btn').trigger('click');
}
function scanWeixinPayResult(str){
	if(!/^[0-9]*$/.test(str)){
        motify.log("微信付款码是数字",3000,{},20);
		return false;
    }
	$('#weixin_input').val(str);
	$('#weixin_btn').trigger('click');
}

function go_pay(paymethod, auth_code, offline_pay){
	var postData = {};
	postData.order_id = order_info.order_id;
	postData.auth_code = auth_code;
	postData.auth_type = paymethod;
	postData.price = payPrice;
	postData.card_id = card_data ? card_data.card_id : 0;
	postData.uid = card_data ? card_data.uid : 0;
	postData.discount = card_data ? (card_data.discount ? card_data.discount : 10) : 10;
	postData.coupon = merchant_coupon_id;
	postData.card_money = merchant_card_use;
	postData.offline_pay = offline_pay;
	common.http('Storestaff&a=arrival_pay',postData, function(data){
		common.removeCache('card_data',true);
		common.removeCache('buy_list',true);
		common.removeCache('total_goods',true);
		$(".back_index,.pay_ok_mask").show();
	});
}

function getOfflinePayMethod(){
	var offline_pay_method = common.getCache('offline_pay_method',true);
	if(offline_pay_method){
		setOfflineMethod(offline_pay_method);
	}else{
		common.http('Storestaff&a=get_pay_method',{}, function(data){
			common.setCache('offline_pay_method',data,true);
			setOfflineMethod(data);
		});
	}
}
function setOfflineMethod(data){
	if(data.open_alipay == 0){
		$('#open_alipay').remove();
	}
	if(data.cash_pay_qrcode == '0'){
		$('.wxsm .fl').html('用户扫码');
		$('.scan_code .h2 span').html('用户扫码');
		$('.scan_code .cot .inb').html('用户扫码支付');
	}
	if(data.offline_pay_list && data.offline_pay_list.length > 0){
		var html = '';
		for(var i in data.offline_pay_list){
			html+= '<dd data-id="'+data.offline_pay_list[i].id+'">'+data.offline_pay_list[i].name+'</dd>';
		}
		$('#offline_method').html(html);
	}
}