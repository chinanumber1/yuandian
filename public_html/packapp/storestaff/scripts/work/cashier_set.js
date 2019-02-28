var checkPayTimer = null;
$(document).ready(function(){
    $('.offDiscount').hide();
	common.http('Storestaff&a=store_arrival_add_info',{},function(data){
		if(data.has_discount == true){
			if(data.discount_type == 1){	//1.打折
				var discountTxt = data.discount_percent+'折优惠';
			}else if(data.discount_type == 2){    //2.满减
				var discountTxt = '满'+data.condition_price+'元减'+data.minus_price+'元';
			}
			$('#discountTxt').html(discountTxt);
					
			$("#switch1 div").click(function(){
				$(this).addClass("on").siblings().removeClass("on");
				if($(this).hasClass('off')){
					$('#price').html($('#total_price').val());
					$('.offDiscount').hide();
                    $('#total_price').trigger('keyup');
				}else{
					$('#total_price').trigger('keyup');
					$('.offDiscount').show();
				}
			});
	
			$('#total_price,#no_discount_money').keyup(function(){
				var no_discount_money = parseFloat($('#no_discount_money').val()), total_money = parseFloat($('#total_price').val()),price = 0,minus_price = 0,
					can_discount_table_money = parseFloat($('#can_discount_table_money').val()),mer_table_discount = parseFloat($('#mer_table_discount').text());
				if(isNaN(no_discount_money)){
					no_discount_money = 0;
				}
				if(isNaN(total_money)){
					total_money = 0;
				}
                var old_total_money = parseFloat($('#old_total_price').val());
                var coupon_discount_money = parseFloat($('#coupon_discount_money').val());

                if(isNaN(coupon_discount_money)){
                    coupon_discount_money = 0;
                }
                
                if(isNaN(old_total_money)){
                    old_total_money = 0;
                }

                if (old_total_money != total_money) {
                    coupon_discount_money = 0;
                    $('#is_use_discount').val(0);
                    $('.plat_discount').hide();
                } else {
                    $('.plat_discount').show();
                    $('#is_use_discount').val(1);
                }
                //定制桌台优惠
                if($('#switch2 .no').hasClass('on')){
                    mer_table_discount = (mer_table_discount / 10).toFixed(2);
                    var minus_table_price = (can_discount_table_money - can_discount_table_money * mer_table_discount).toFixed(2);
                    total_money = price = total_money - minus_table_price;
				}else{
					minus_table_price = 0;
					total_money = parseFloat($('#total_price').val())
				}
				if(total_money > no_discount_money){
					if($('#switch1 .no').hasClass('on')) {
                        if (data.discount_type == 1) {
                            price = eval(total_money - no_discount_money) * eval(data.discount_percent) / 10 + no_discount_money;
                            minus_price = eval(total_money - no_discount_money) * (100 - data.discount_percent * 10) / 100;
                        } else if (data.discount_type == 2) {
                            minus_price = Math.floor(eval(total_money - no_discount_money) / eval(data.condition_price)) * data.minus_price;
                            price = total_money - minus_price;
                        } else {
                            minus_price = 0;
                            price = total_money;
                        }
                   }else{
						minus_price = 0;
						price = total_money;
					}

					if(minus_price > 0){
						$('#show_minus').html('-￥<span id="discount_money">' + common.floatVal(minus_price) + '</span>');
					}else{
						$('#show_minus').html('');
					}
                    if(minus_table_price > 0){
                        $('#show_table_minus').html('-￥<span id="discount_table_money">' + common.floatVal(minus_table_price) + '</span>');
                    }else{
                        $('#show_table_minus').html('');
                    }
					$('#price').html(common.floatVal(price - coupon_discount_money));
				}else{
					$('#price').html(common.floatVal(total_money - coupon_discount_money));
					$('#show_minus').html('');
				}
			});
		}else{
			$('.hasDiscount').hide();
			$('#total_price').keyup(function(){
                var can_discount_table_money = parseFloat($('#can_discount_table_money').val()),mer_table_discount = parseFloat($('#mer_table_discount').text());
				var total_money = parseFloat($('#total_price').val());
                //定制桌台优惠
                if($('#switch2 .no').hasClass('on')){
                    mer_table_discount = (mer_table_discount / 10).toFixed(2);
                    var minus_table_price = (can_discount_table_money - can_discount_table_money * mer_table_discount).toFixed(2);
                    total_money = total_money - minus_table_price;
                }else{
                    minus_table_price = 0;
                    total_money = parseFloat($('#total_price').val())
                }
                if(minus_table_price > 0){
                    $('#show_table_minus').html('-￥<span id="discount_table_money">' + common.floatVal(minus_table_price) + '</span>');
                }else{
                    $('#show_table_minus').html('');
                }

				var old_total_money = parseFloat($('#old_total_price').val());
			    var coupon_discount_money = parseFloat($('#coupon_discount_money').val());
			    
			    if(isNaN(old_total_money)){
			        old_total_money = 0;
			    }
				if(isNaN(total_money)){
					total_money = 0;
				}
			    if(isNaN(coupon_discount_money)){
			        coupon_discount_money = 0;
			    }
			    if (old_total_money != total_money) {
			        coupon_discount_money = 0;
			        $('#is_use_discount').val(0);
			        $('.plat_discount').hide();
			    } else {
			        $('.plat_discount').show();
			        $('#is_use_discount').val(1);
			    }
				$('#price').html(parseFloat((total_money - coupon_discount_money).toFixed(2)));
			});
		}
		
		if(urlParam.business_type){
			common.http('Storestaff&a=store_arrival_get_info',{business_type:urlParam.business_type,business_id:urlParam.business_id},function(data){
				if (data.coupon != null && parseInt(data.coupon.is_discount) == 1) {
				    $('#coupon_discount_id').val(parseInt(data.coupon.id));
				    $('#coupon_discount').html(parseFloat(data.coupon.discount_value) + '折');
				    $('#coupon_discount_money').val(parseFloat(data.coupon.discount_money));
				    $('#show_coupon_discount_money').html('￥' + parseFloat(data.coupon.discount_money));
				    $('#is_use_discount').val(1);
				    $('.plat_discount').show();
				} else {
				    $('#is_use_discount').val(0);
				    $('.plat_discount').hide();
				}
                $('#old_total_price').val(data.go_pay_price);
				$('#total_price').val(data.go_pay_price).trigger('keyup');

                //定制桌位折扣
				if(data.mer_discount > 0){
                    $('.hasTableDiscount').show();
				}
                $('#can_discount_table_money').val(parseFloat(data.can_discount_table_money));
                $('#mer_tables').text(data.tables);
                $('#mer_table_discount').text(data.mer_discount);

                $("#switch2 div").click(function(){
                    $(this).addClass("on").siblings().removeClass("on");
                    if($(this).hasClass('off')){
                        $('#price').html($('#total_price').val());
                        $('#total_price').trigger('keyup');
                        $('.offTableDiscount').hide();
                    }else{
                        $('#total_price').trigger('keyup');
                        $('.offTableDiscount').show();
                    }
                });
                //end
			});
		}else{
			$('#total_price').focus();
		}

		$('#orderForm').submit(function(){
			var total_price = parseFloat($('#total_price').val());
			if(isNaN(total_price) || total_price == 0){
				motify.log('请输入订单金额');
				return false;
			}
			var postData = {};
			postData.total_price = total_price;
			if(!$('#offDiscount').hasClass('on')){
				postData.discount_money = parseFloat($('#discount_money').html());
				if(isNaN(postData.discount_money)){
					postData.discount_money = 0;
				}
			}else{
				postData.discount_money = 0;
		 	}
		 	//桌台折扣
            if(!$('#offTableDiscount').hasClass('on')){
                    postData.discount_money += parseFloat($('#discount_table_money').html());
                if(isNaN(postData.discount_money)){
                    postData.discount_money = 0;
                }
            }
			postData.coupon_discount_id = parseInt($('#coupon_discount_id').val());
			postData.is_use_discount = parseInt($('#is_use_discount').val());
			postData.coupon_discount_money = parseFloat($('#coupon_discount_money').val());
			postData.pay_money = parseFloat($('#price').html());
			postData.txt_info = $('#txt_info').val();
			
			if(urlParam.business_type){
				postData.business_type = urlParam.business_type;
				postData.business_id = urlParam.business_id;
			}
			if(urlParam.from_scan && urlParam.uid && urlParam.payid){
				postData.from_scan = urlParam.from_scan;
				postData.uid = urlParam.uid;
				postData.payid = urlParam.payid;
			}
			// console.log(postData);return false;
			common.http('Storestaff&a=store_arrival_add',postData,function(saveData){
				$('#total_price,#txt_info,#no_discount_money').val('');
				$('#show_minus,#price').html('');
				if(saveData.code=='SCAN_PAY_SUCCESS'){
					layer.open({
						title:'订单信息'
						,shadeClose:false
						,content: '订单创建成功，等待用户支付'
					});
					
					checkPayTimer = setInterval(function(){
						common.http('Storestaff&a=check_store_arrival_order',{order_id:saveData.order_id,noTip:true},function(data){
							common.removeCache('cashier_order_info',true);
							layer.open({
								content:'订单已经支付成功！'
								,shadeClose:false
								,btn: ['确定']
								,yes: function(index){
									window.history.go(-1);
								}
							});
							clearInterval(checkPayTimer);
						},function(data){
							// console.log(data);
						});	
					},2000);				
				}else{					
					saveData.total_price = postData.total_price;
					saveData.discount_money = postData.discount_money;
					saveData.pay_money = postData.pay_money;
					saveData.txt_info = postData.txt_info;
					common.setCache('cashier_order_info',saveData,true);
					location.href = 'cashier_success.html';
				}
			});
			return false;
		});
	});
	
	$('#confirmOrder').css({display:'block',width:'80%',margin:'25px auto'});
});