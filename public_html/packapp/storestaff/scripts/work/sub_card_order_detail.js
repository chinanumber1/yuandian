$(document).ready(function(){
	if(urlParam.pass){
		var pass = urlParam.pass;
	}else{
		redirect('index.html','openLeftWindow');
		return false;
	}
	
	common.http('Storestaff&a=sub_card_find',{'pass':pass},function(data){
		common.setData(data);
		if(data.row_count==0){
			motify.log('没有查到免单消费码');
			redirect('index.html','openLeftWindow');
		}
		
		if(data.use_time==0){
			$('#use_time').parent('.clr').hide()
			$('#staff').parent('.clr').hide()
		}
		
		
		
		//$('#order-merchantCardDiscount').html(merchantDiscount);
	});
});