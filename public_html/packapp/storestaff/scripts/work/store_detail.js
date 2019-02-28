$(document).ready(function(){
	if(urlParam.order_id){
		var order_id = urlParam.order_id;
	}else{
		redirect('index.html','openLeftWindow');
		return false;
	}
	
	common.http('Storestaff&a=store_order_detail',{'order_id':order_id},function(data){
		common.setData(data);
		if(!data.user){
			$('#userInfo').hide();
		}
		if(data.order.noDiscountBox == '0.00'){
			$('#noDiscountBox').hide();
		}
		
		if(data.order.ticketNum != 0){
			$('.ticketBox').show();
		}
		if(data.order.ticketInsure == 0){
			$('#ticketInsureLi').hide();
		}
		
		var merchantDiscount = data.order.price - (data.order.price - data.order.no_discount_money)*data.order.card_discount/10-data.order.no_discount_money;
		if(merchantDiscount){
			merchantDiscount = merchantDiscount.toFixed(2) + '（' + data.order.card_discount + '折）';
			$('#order-price').html(data.order.price-data.order.card_discount_money)
			$('#order-merchantCardDiscount').html(merchantDiscount);
		}else{
			$('#order-price').html(data.order.price)
		}
		
		if(data.order.pay_total){
			$('#order-price').html(data.order.pay_total);
		}
		
		if(data.order.score_deducte){
			$('#order-score').html(data.order.score_deducte);
		}
		
		if(data.order.card_price){
			$('#order-cardPrice').html(data.order.card_price);
		}
		
		if(data.order.coupon_price){
			$('#order-couponPrice').html(data.order.coupon_price);
		}
		
		if(data.order.payment_money){
			$('#order-onlinePay').html(data.order.payment_money);
		}
	});
});