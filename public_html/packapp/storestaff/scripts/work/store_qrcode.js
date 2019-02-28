$(document).ready(function(){
	var staffArr = common.getCache('store_staff',true);
	
	$('#store_qrcode').attr('src',requestUrl+'Storestaff&a=store_qrcode&Device-Id='+common.getDeviceId()+'&ticket='+common.getCache('ticket',true));
});