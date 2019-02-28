$(document).ready(function(){
    $(".mask").height($(window).height());


    common.http('Storestaff&a=shopDetail', {'order_id':urlParam.order_id}, function(data){
    	console.log(data);
    	laytpl($('#allTpl').html()).render(data, function(html){
			$('.g_details').html(html);
			$(".kd_dl .hx").height($(".kd_dl").height()-65);
		});
	});
    
    $.post(url, params, function(res){
		if (res.errorCode == '0') {
			sucFun(res.result);
		} else if (errFun) {
			errFun(res);
		} else {
			motify.log(res.errorMsg);
		}
		layer.close(index);
	}, 'json');
    
    
});