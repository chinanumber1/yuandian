$(function () {
    var package;
    $(".scUl li").click(function () {
        package = $(this).attr('id').replace("p", "");
		
        $('#cursel').text($(this).html().replace('<p class="dh"></p>', '')); //显示当前选择产品套餐
        $(this).addClass('current').siblings().removeClass('current');
		if(package == 0){
			return false;
		}
		$('#diy_propertyt_month_num').val('');
		layer.open({
			type: 2,
			content: '加载中',
			shadeClose:false
		}); 
		$.post(ajax_get_presented_property_month_url,{'id':package},function(result){
			layer.closeAll();
			//alert(result['status']);
			//alert(result['property_info']['abcd']);
			if (result['status'] > 0) {
				result = result['property_info'];
				if(result && (result['presented_property_month_num'] != '0') && (result['diy_type'] == 0)){
					$('#addmonth').text(result['presented_property_month_num'] + '个月'); 
					$('#gift').show();
				}else if(result['diy_type'] == 1){
					layer.open({
						title:'提示',
						content: result.diy_content,
						btn: ['确定'],
						shadeClose: false,
					});
					$('#gift').hide();
				}else{
					$('#gift').hide();
				}
				
				if(result['use_total_money']>0 && result['use_total_score_count']>0){
					$(".use-integral").show(300);
					$(".pigcms_integral_num").html(result['use_total_score_count']);	
					$(".pigcms_integraltomoney_num").html(result['use_total_money']);
				}else{
					$(".use-integral").hide(200);
					$(".pigcms_integral_num").html(0);	
					$(".pigcms_integraltomoney_num").html(0);
				}
				
				result['total_price'] && $('#totalmoney').text(parseFloat(result['total_price']).toFixed(2)) && $('#totalmoney').attr('data-money',parseFloat(result['total_price']).toFixed(2));
			}
		},'json')
    });
	
	$('#diy_propertyt_month_num').click(function(){
		$('#totalmoney').text('0.00');
		$(this).val('');
	});
	
	$('#diy_propertyt_month_num').keyup(function(){
		if($(this).val() < 0){
			$(this).val(0);
		}else if($(this).val() > 36){
			$(this).val(36);
		}
		
		$('#gift').hide();
		layer.open({
			type: 2,
			content: '加载中',
			shadeClose:false
		});
		$.post(ajax_diy_get_presented_property_month_url,{'diy_propertyt_month_num':$(this).val(),'village_id':village_id},function(result){
			layer.closeAll();
			if(result['status']){
				if(result['diy_type'] == 0){
					if(result['max_presented_property_month'] > '0'){
						$('#addmonth').text(result['max_presented_property_month'] + '个月');
						$('#gift').show();
					}
					$('#diy_propertyt_month_num').attr('property_id',0);
				}else{
					layer.open({
						title:'提示',
						content: result.diy_content,
						btn: ['确定'],
						shadeClose: false,
					});
					$('#diy_propertyt_month_num').attr('property_id',result['property_id']);
				}
				if(result['use_total_money']>0 && result['use_total_score_count']>0){
					$(".use-integral").show(300);
					$(".pigcms_integral_num").html(result['use_total_score_count']);	
					$(".pigcms_integraltomoney_num").html(result['use_total_money']);
				}else{
					$(".use-integral").hide(200);
					$(".pigcms_integral_num").html(0);	
					$(".pigcms_integraltomoney_num").html(0);
				}
				
				//result['total_price'] && $('#totalmoney').text(result['total_price']) && $('#totalmoney').attr('data-money',result['total_price']);
				$('#totalmoney').text(parseFloat(result['total_price']).toFixed(2)) && $('#totalmoney').attr('data-money',parseFloat(result['total_price']).toFixed(2));
			}
		},'json')
	});
	
	$('#diy_propertyt_month_num').bind('input propertychange', function() {
			$('#gift').hide(); 
			$.post(ajax_diy_get_presented_property_month_url,{'diy_propertyt_month_num':$(this).val(),'village_id':village_id},function(result){
				if(result['max_presented_property_month'] != '0'){
					$('#addmonth').text(result['max_presented_property_month'] + '个月'); $('#gift').show(); 
				}
			},'json')
		});


    $('.scUl li:first').click(); 
    $('#confirm').click(function () {
		
		if($('#checkbox_c1').is(':checked')) {
			var is_use_integral = 1;
			var money_info =  parseFloat($('#totalmoney').attr('data-money')-$('.pigcms_integraltomoney_num').text()).toFixed(2);
		}else{
			var is_use_integral = 0;
			var money_info =  parseFloat($('#totalmoney').attr('data-money')).toFixed(2);

		}
		layer.open({title:['是否确认提交？','background-color:#06c1ae;color:#fff;'],content:'金额：' +  money_info,shadeClose:false,btn: ['确定','取消'],yes:function(){
		layer.closeAll();
		layer.open({type: 2,content: '提交中，请稍等',shadeClose:false});
		
		var money = $('#totalmoney').attr('data-money');
		if($('#diy_propertyt_month_num').val()){
			var property_month_num = parseInt($('#diy_propertyt_month_num').val());
		}else{
			var property_month_num = parseInt($('#package .current').text());
		}

		if(!property_month_num){
			layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'月份不能为空！',btn: ['确定'],end:function(){}});
		}
		
		if(property_month_num >36 || property_month_num <= 0){
			layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'月份请填写1-36之间',btn: ['确定'],end:function(){}});
		}
		
		var house_village_property_id = $('#package .current').attr('id').replace("p", "");
		if(house_village_property_id <= 0){
			house_village_property_id = $('#diy_propertyt_month_num').attr('property_id');
		}
		var display_val = $('#gift').css('display');
		
		if(display_val != 'none'){
			var presented_property_month_num = parseInt($('#addmonth').text());
			if(!presented_property_month_num){
				presented_property_month_num = 0;
			}
		}else{
			presented_property_month_num = 0;
		}
		$.post(window.location.href,{'txt':'','money':money,'property_month_num':property_month_num,'presented_property_month_num':presented_property_month_num,'house_village_property_id':house_village_property_id,'is_use_integral':is_use_integral},function(result){
			
			layer.closeAll();
			if(result.err_code == 1){
				pageLoadTip('跳转支付中..');
				window.location.href = result.order_url;
			}else{
				layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:result.err_msg,btn: ['确定'],end:function(){}});
			}
		},'json');
		}});
    });
	
	
	
	$('button.plus').click(function(){
		var diy_propertyt_month_num = $('#diy_propertyt_month_num').val();
		diy_propertyt_month_num++;
		if(diy_propertyt_month_num > 36 ){
			diy_propertyt_month_num = 36;
		}
		
		$('#diy_propertyt_month_num').val(diy_propertyt_month_num);
		$('#diy_propertyt_month_num').trigger('keyup');
	});
	$('button.minus').click(function(){
		var diy_propertyt_month_num = $('#diy_propertyt_month_num').val();
		diy_propertyt_month_num--;
		if(diy_propertyt_month_num < 0 ){
			diy_propertyt_month_num = 0;
		}
		
		$('#diy_propertyt_month_num').val(diy_propertyt_month_num);
		$('#diy_propertyt_month_num').trigger('keyup');
	});

})

