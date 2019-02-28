$(function($){
	$('#Config_shop_start_time').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('#Config_shop_stop_time').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('#Config_shop_start_time_2').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('#Config_shop_stop_time_2').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('#Config_shop_start_time_3').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('#Config_shop_stop_time_3').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
});



	function deleteImg(path,obj){
		$.post("{pigcms{:U('Appoint/ajax_del_pic')}",{path:path});
		$(obj).closest('.upload_pic_li').remove();
	}

	function paymentHide(){
		$('#payment_money').hide();
	}
	function paymentShow(){
		$('#payment_money').show();
	}

	get_worker_list();
	$('select[name="appoint_type"]').change(function(){
		get_worker_list();
	});
	
	
	function get_worker_list(){
		var appoint_type=$('select[name="appoint_type"]').val();
		$.post(ajax_worker_list_url,{'appoint_type':appoint_type},function(data){
			$('.worker-list').each(function(){
				var store_id=$(this).data('store-id');
				var shtml='';
				for(var i in data){
					//alert(obj2String(data[i]['merchant_store_id']))
					if(data[i]['merchant_store_id']==store_id){
						shtml+='<label style="margin-right:10px"><input type="checkbox" id="worker'+data[i]["merchant_worker_id"]+'" value="'+data[i]["merchant_store_id"]+','+data[i]["merchant_worker_id"]+'" name="worker_memus[]" class="paycheck ace"><span class="lbl"><label for="worker'+data[i]["merchant_worker_id"]+'">&nbsp;&nbsp;'+data[i]["name"]+'</label></span></label>';
					}
				}
				$(this).html('').append(shtml);
			});
		},'json')
	}


$('.store-list').each(function(){
	var flag=$(this).is(':checked');
	
	if(flag){
			$(this).parents('.radio').next('.worker-list').show();
			flag=false;
		}else{
			var obj=$(this).parents('.radio').next('.worker-list');
			obj.hide();
			obj.children('label').each(function(){
				$(this).children('input').removeAttr('checked');
			});
			flag=true;
		}
	$(this).click(function(){
		if(flag){
			$(this).parents('.radio').next('.worker-list').show();
			flag=false;
		}else{
			var obj=$(this).parents('.radio').next('.worker-list');
			obj.hide();
			obj.children('label').each(function(){
				$(this).children('input').removeAttr('checked');
			});
			flag=true;
		}
	});
});


$('select[name="is_appoint_price"]').change(function(){
	if($(this).val()=='1'){
		$('#appoint_price').show();
	}else{
		$('#appoint_price').hide();
	}
});