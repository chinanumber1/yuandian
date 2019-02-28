$(document).ready(function(){
	var indexData = common.getCache('indexData',true);
	
	if(!indexData){
		location.href = 'index.html';
	}
	
	if(common.checkWeixin()){
		common.fillPageBg(1,'#f4f4f4');
	}
	
	if(common.checkIosApp()){
		$('#container').height($(window).height() - 64);
		common.onlyScroll($('#container'));
	}
	
	common.http('Deliver&a=new_detail',{supply_id:urlParam.supply_id}, function(data){
		$('.openUserMap').data({baidu_lng:data.supply.aim_lnt,baidu_lat:data.supply.aim_lat,name:'[用户] ' + data.supply.name,address:data.supply.aim_site});
		$('.openStoreMap').data({baidu_lng:data.supply.from_lnt,baidu_lat:data.supply.from_lat,name:'[店铺] ' + data.supply.store_name,address:data.supply.from_site});
		if (data.supply.status < 2) {
		    $('#supply-from_text').parent('li').remove();
//		    data.supply.from_text = '待抢单';
		} else if(data.supply.get_type == 0){
			data.supply.from_text = '抢单';
		}else if(data.supply.get_type == 1){
			data.supply.from_text = '系统派单';
		}else if(data.supply.get_type == 1){
			data.supply.from_text = data.supply.change_name + '配送员';
		}
		if (data.supply.item == 3) {
			$('#goodsList').parent('div').remove();
			$('#supply-appoint_time').parent('li').remove();
			var html = '';
            data.order.real_orderid = data.supply.real_orderid;
            data.order.create_time = data.supply.create_time;
			if (data.supply.server_type == 1) {
				html += '<div class="leaflets">帮送</div>';
				html += '<div class="f16 c9">商品类型：' + data.supply.goods_name+ '</div>';
				html += '<div class="f16 c9">商品重量：' + data.supply.goods_weight+ '千克</div>';
				html += '<div class="f16 c9">商品价格：' + data.supply.goods_price+ '元</div>';
				if (data.supply.image.length > 0) {
				    html += '<div class="f16 c9 acc"><span>商品图片:</span>';
				    for (var im in data.supply.image) {
				        html += '<img class="img_click" src="' + data.supply.image[im] + '" alt="" >';
				    }
				    html += '</div>';
				}
				$('#appoint_show').html('取货时间');
				data.supply.appoint_time = data.supply.server_time;
				$('.server_type2,.server_type3').remove();
			} else {
	            html += '<div class="leaflets">帮买</div>';
	            html += '<div class="f14 c9">商品名称：' + data.supply.goods_name+ '</div>';
	            html += '<div class="f14 c9">商品估价：' + data.supply.goods_price+ '元</div>';
                if (data.supply.image.length > 0) {
                    html += '<div class="f16 c9 acc"><span>商品图片:</span>';
                    for (var im in data.supply.image) {
                        html += '<img class="img_click" src="' + data.supply.image[im] + '" alt="" >';
                    }
                    html += '</div>';
                }
	            $('#appoint_show').html('期望送达');
	            $('.server_type1').remove();
	            if (data.supply.server_type == 2) {
	            	$('.server_type3').remove();
	            } else {
	            	$('.server_type2').remove();
	            }
			}
            html += '<p class="f12 red">额外小费：￥' + data.supply.tip_price + '</p>';
            html += '<p class="f12 red">配送距离' + data.supply.distance+ '公里，配送费' + data.supply.freight_charge+ '元</p>';
			$('.details_list').html(html);
			$('#cue_field_box, #Merchant').remove();
		} else {
			$('#fromSource').remove();
			laytpl($('#goodListTpl').html()).render(data.goods, function(html){
				$('#goodsList').html(html);
			});
			
			if(data.order.cue_field && data.order.cue_field.length > 0){
				laytpl($('#cueFieldListTpl').html()).render(data.order.cue_field, function(html){
					$('#cue_field_ul').html(html);
				});
			}else{
				$('#cue_field_box').remove();
			}
		}
		if (parseInt(data.supply.fetch_number) == 0) {
		    $('#supply-fetch_number').parent('li').remove();
		}
		common.setData(data);

		var storePhoneArr = data.store.phone.split(' ');
		storePhoneArr = arrUnique(storePhoneArr);
		var storePhoneTxt = '';
		for(var i in storePhoneArr){
			storePhoneTxt += '<div style="line-height:1.5;">'+storePhoneArr[i]+'</div>';
		}
		$('#store-phone').html(storePhoneTxt);
		
		var order_status = data.supply.status;
		var statusTxt = '';
		var postUrl = '';
		switch(order_status){
			case '1':
				statusTxt = '抢单';
				postUrl = 'Deliver&a=grab';
				$('#fetch_number_box').remove();
				break;
			case '2':
				statusTxt = '取货';
				postUrl = 'Deliver&a=pick';
				if(data.order.fetch_number == '0'){
					$('#fetch_number_box').remove();
				}
				if (data.is_cancel_order == 1) {
    				$('#cancel').show().click(function(){
    		            var supply_id = urlParam.supply_id;
    	                var postParam = {'supply_id':supply_id};
    		            layer.open({
    		                content: '确认要放弃配送此单吗？'
    		                ,btn: ['确定', '取消']
    		                ,yes: function(index){
    		                    common.http('Deliver&a=cancelOrder', postParam, function(data){
    		                        layer.open({title:['操作提示：','background-color:#289FFD;color:#fff;'],content:'放弃配送成功',btn: ['确定'],end:function(){
    		                            if(common.checkApp()){
											if(common.checkAndroidApp()){
												window.pigcmspackapp.closewebview(2);
											}else{
												common.iosFunction('closewebview/2');
											}
										}else{
											location.href = 'pick.html';
										}
    		                        },success:function(){$('.layermbtn span').width('100%');}});
    		                    });
    		                    layer.close(index);
    		                }
    		            });
    		        });
				}
				break;
			case '3':
				statusTxt = '配送';
				postUrl = 'Deliver&a=send';
				if(data.order.fetch_number == '0'){
					$('#fetch_number_box').remove();
				}
				break;
			case '4':
				statusTxt = '送达';
				postUrl = 'Deliver&a=complete';
				if(data.order.fetch_number == '0'){
					$('#fetch_number_box').remove();
				}
				break;
			case '5':
				statusTxt = '删除';
				postUrl = 'Deliver&a=new_del';
				$('#fetch_number_box').remove();
				break;
		}
		$('#eventBtn').html(statusTxt).show().click(function(){
			var supply_id = urlParam.supply_id;
			if(order_status != 5){
				var postParam = {'supply_id':supply_id};
			}else{
				var postParam = {'supply_ids':supply_id};
			}
			layer.open({
				content: '确认要设置为已'+statusTxt+'吗？'
				,btn: ['确定', '取消']
				,yes: function(index){
					common.http(postUrl,postParam, function(data){
						layer.open({title:['操作提示：','background-color:#289FFD;color:#fff;'],content:'已成功设置为'+statusTxt,btn: ['确定'],end:function(){
							location.reload();
						},success:function(){$('.layermbtn span').width('100%');}});
					});
					layer.close(index);
				}
			});
		});
		if(data.supply.is_hide == '1'){
			$('#eventBtn').html('已删除').removeAttr('id').unbind('click');
		}
	});
});