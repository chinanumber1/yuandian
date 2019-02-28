var foodData = {},nowSpecFood = {},nowGroupId = 0;
$(function(){
	var tipIndex = layer.load(0, {shade: [1,'#fff']});
	$('.food_cart').height($(window).height());
	$('.food_menu').width($(window).width()-350);
	
	$('.food_body').height($(window).height()-110-140);
	
	$('.food_cart_list,.food_buy_list').height($(window).height()-80-140-40);
	
	$.getJSON(getFoodMenuUrl,{order_id:$('#order_id').val()},function(result){
		foodData = result;	
		laytpl($('#foodSortTpl').html()).render(foodData.lists, function(html){
			$('.swiper-wrapper').html(html);
			$('.swiper-wrapper li:first').addClass('cur');
		});
		var swiper = new Swiper('.swiper-container', {
			slidesPerView: 'auto',
			spaceBetween: 10,
			prevButton:'.swiper-button-prev',
			nextButton:'.swiper-button-next',
		});  
		
		$('.foodshop_order .food_menu .food_cat li').click(function(){
			$(this).addClass('cur').siblings('li').removeClass('cur');
			$('.sort_ul_'+$(this).data('sort_id')).addClass('cur').show().siblings('ul').removeClass('cur').hide();
			changeBody();
		});
		
		laytpl($('#foodTpl').html()).render(foodData.lists, function(html){
			$('.food_body').html(html);
			$('.food_body ul:first').addClass('cur').show();
			changeBody();
		});
		
		$('.food_body li').click(function(){
			if($(this).hasClass('hasSpec')){
				var tip_index = layer.load(0, {shade: [0.5,'#fff']});
				var that = $(this);
				for(var i in foodData.lists[that.data('sort_id')].goods_list){
					if(foodData.lists[that.data('sort_id')].goods_list[i].goods_id == that.data('id')){
						nowSpecFood = foodData.lists[that.data('sort_id')].goods_list[i];
						laytpl($('#foodSpecTpl').html()).render(nowSpecFood, function(html){
							$('.food_spec_box').html(html);
						});
						changeSpec();
						layer.close(tip_index);
						layer.open({
							type: 1,
							title: false,
							shadeClose: true,
							shade: 0.6,
							area: ['400px','510px'],
							closeBtn:0,
							content: $('.food_spec_box')
						});
						return false;
						break;
					}
				}
			}else{
			    var cartParam = {
			            uid:0,
			            package_id:0,
			            productKey:$(this).data('id'),
			            productId:$(this).data('id'),
			            productName:$(this).data('name'),
			            productUnit:$(this).data('unit'),
			            productPrice:$(this).data('price'),
			            productStock:$(this).data('stock_num'),
			            productParam:[],
			            extra_price:$(this).data('extra_price'),
			            note:''
			    };
				cartFunction(cartParam,'plus');
			}
		});
		
		laytpl($('#foodGroupTpl').html()).render(foodData.package, function(html){
			$('.food_group_box ul').html(html);
		});
		
		if(foodData.tmp_order){
			for(var uid in foodData.tmp_order){
                if (typeof (foodData.userList[uid]) == 'undefined') {
                    $('.food_cart_list ul').append('<li id="tmp_0">店员</li>');
                } else {
                    $('.food_cart_list ul').append('<li id="tmp_' + uid + '">' + foodData.userList[uid] + '</li>');
                }
			    for (var i in foodData.tmp_order[uid]) {
    				if(foodData.tmp_order[uid][i].spec){
    					foodData.tmp_order[uid][i].spec = foodData.tmp_order[uid][i].spec.replace(/,/g,' ');
    					foodData.tmp_order[uid][i].spec = foodData.tmp_order[uid][i].spec.replace(/;/g,' ');
    				}
    				var cartParam = {
    				        uid:foodData.tmp_order[uid][i].uid, 
    				        package_id:foodData.tmp_order[uid][i].package_id, 
    				        productKey:foodData.tmp_order[uid][i].uid+'_'+foodData.tmp_order[uid][i].goods_id+'_'+foodData.tmp_order[uid][i].spec_id+'_tmp_order',
    				        productId:foodData.tmp_order[uid][i].goods_id,
    				        productName:foodData.tmp_order[uid][i].name,
    				        productUnit:foodData.tmp_order[uid][i].unit,
    				        productPrice:foodData.tmp_order[uid][i].price,
    				        productStock:'-1',
    				        productParam:[],
    				        productLabel:foodData.tmp_order[uid][i].spec,
    				        isTmpOrder:true,
    				        tmpOrderId:foodData.tmp_order[uid][i].id,
    				        count:parseInt(foodData.tmp_order[uid][i].num),
    				        extra_price:foodData.tmp_order[uid][i].extra_price,
    				        note:foodData.tmp_order[uid][i].note
    				};
    				cartFunction(cartParam,'plus');
			    }
			}
		}
		//console.log(foodData)
		if(foodData.order_detail){
			for(var uid in foodData.order_detail){
			    if (typeof (foodData.userList[uid]) == 'undefined') {
			        $('.food_buy_list ul').append('<li id="buy_0">店员</li>');
			    } else {
			        $('.food_buy_list ul').append('<li id="buy_' + uid + '">' + foodData.userList[uid] + '</li>');
			    }
			    for(var i in foodData.order_detail[uid]){
    				if(foodData.order_detail[uid][i].spec){
    					foodData.order_detail[uid][i].spec = foodData.order_detail[uid][i].spec.replace(/,/g,' ');
    					foodData.order_detail[uid][i].spec = foodData.order_detail[uid][i].spec.replace(/;/g,' ');
    				}
    				var buyParam = {
                            uid:foodData.order_detail[uid][i].uid,
                            package_id:foodData.order_detail[uid][i].package_id,
    				        productKey:foodData.order_detail[uid][i].uid+'_'+foodData.order_detail[uid][i].goods_id+'_'+foodData.order_detail[uid][i].spec_id+'_order_detail',
    				        productId:foodData.order_detail[uid][i].goods_id,
    				        productName:foodData.order_detail[uid][i].name,
    				        productUnit:foodData.order_detail[uid][i].unit,
    				        productPrice:foodData.order_detail[uid][i].price,
    				        productStock:'-1',
    				        productParam:[],
    				        productLabel:foodData.order_detail[uid][i].spec,
    				        detail_id:foodData.order_detail[uid][i].id,
    				        count:foodData.order_detail[uid][i].num,
    				        is_must:foodData.order_detail[uid][i].is_must,
    				        extra_price:foodData.order_detail[uid][i].extra_price,
    				        note:foodData.order_detail[uid][i].note
    				}
    				cartBuyFunction(buyParam,'show');
			    }
			}
			$('#buyBox').show();
			$('#cartBox').hide();
		}
		
		layer.close(tipIndex);
	});
	
	$('.food_buy_list li .plus').live('click',function(){
		cartBuyFunction(productBuyCart[$(this).closest('li').data('key')],'plus');
	});
	$('.food_buy_list li .min').live('click',function(){
		cartBuyFunction(productBuyCart[$(this).closest('li').data('key')],'min');
	});
	$('.food_buy_list li .number input').live('blur',function(){
		cartBuyFunction(productBuyCart[$(this).closest('li').data('key')],'change');
	});
	
	$('.food_cart_list li .plus').live('click',function(){
		cartFunction(productCart[$(this).closest('li').data('key')],'plus');
	});
	$('.food_cart_list li .min').live('click',function(){
		cartFunction(productCart[$(this).closest('li').data('key')],'min');
	});
	$('.food_cart_list li .number input').live('blur',function(){
		cartFunction(productCart[$(this).closest('li').data('key')],'change');
	});
	$('.food_spec_box .spec_content li').live('click',function(){
		$(this).addClass('active').siblings('li').removeClass('active');
		changeSpec();
	});
	$('.food_spec_box .properties_content li').live('click',function(){
		var maxSize = $(this).closest('.row').data('num');
		if(maxSize == 1){
			$(this).addClass('active').siblings('li').removeClass('active');
		}else if(!$(this).hasClass('active')){
			var tmpActiveSize = $(this).closest('ul').find('.active').size();
			if(tmpActiveSize >= maxSize){
				alert($(this).closest('.row').data('label_name')+' 您最多能选择 '+maxSize+' 个');
			}else{
				$(this).addClass('active');
			}
		}else{
			$(this).removeClass('active');
		}
	});
	
	$('.food_spec_box .spec_btn').live('click',function(){
		var tmpKey = [];
		tmpKey.push(nowSpecFood.goods_id);
		var tmpParam = [];
		if(nowSpecFood.spec_list){
			$.each($('.food_spec_box .spec_content ul'),function(i,item){
				tmpKey.push($(item).find('li.active').data('spec_list_id'));
				tmpParam.push({'type':'spec','spec_id':$(item).find('li.active').data('spec_id'),'id':$(item).find('li.active').data('spec_list_id'),'name':$(item).find('li.active').html()});
			});
		}
		if(nowSpecFood.properties_list){
			$.each($('.food_spec_box .properties_content ul'),function(i,item){
				var tmpProductProperties = [];
				$.each($(item).find('.active'),function(j,jtem){	
					tmpKey.push($(jtem).data('label_id'));
					tmpProductProperties.push({'id':$(jtem).data('label_id'),'list_id':$(jtem).data('label_list_id'),'name':$(jtem).html()});
				});
				tmpParam.push({'type':'properties','data':tmpProductProperties});
			});
		}
		var cartParam = {
                uid:0,
                package_id:0,
		        productKey:tmpKey.join('_'),
		        productId:nowSpecFood.goods_id,
		        isTmpOrder:true,
		        note:'',
		        productName:nowSpecFood.name,
		        productUnit:nowSpecFood.unit,
		        productPrice:$('#specPrice').html(),
		        productStock:$('#specStock').html(),
		        productParam:tmpParam
		};
		cartFunction(cartParam,'plus');
	});
	
	$('.food_spec_box .spec_title .close_spec').live('click',function(){
		layer.closeAll();
	});
	
	$('#use_group').click(function(){
		if($.trim($('.food_cart_list ul').html()) != ''){
			alert('请先清空购物车才能使用套餐功能');
			return false;
		}
		$('.food_group_box .checkRadio').removeClass('checked');
		$('.food_group_box .checkRadio').eq(0).addClass('checked');
		layer.open({
			type: 1,
			title: '选择套餐',
			shadeClose: true,
			shade: 0.6,
			area: ['400px','510px'],
			content: $('.food_group_box'),
			btn: ['确定'],
			yes:function(){
				//console.log 选中ID
				var group_id = $('.food_group_box .checkRadio.checked').data('group_id');
				var closestLi = $('.food_group_box .checkRadio.checked').closest('li');
				var tipGroupDetailIndex = layer.load(0, {shade: [0.6,'#fff']});
				$.getJSON(getFoodGroupDetailUrl,{group_id:group_id},function(result){
					layer.close(tipGroupDetailIndex);
					var groupDetail = {};
					groupDetail.group_id = group_id;
					groupDetail.detail = result;
					laytpl($('#foodGroupDetailTpl').html()).render(groupDetail.detail, function(html){
						closestLi.find('.group_info_box').html(html);
					});
					layer.open({
						type: 1,
						title: closestLi.find('.textInfo .name').html(),
						shadeClose: true,
						shade: 0.6,
						area: ['480px','580px'],
						content: '<div class="layer_group_info_box">'+closestLi.find('.group_info_box').html()+'</div>',
						btn: ['确定'],
						success:function(){
							$.each($('.layer_group_info_box .group_info'),function(i,item){
								if($(item).data('selectnum') == 1){
									$(item).find('.right').eq(0).addClass('checked');
								}
							});
						},
						yes:function(){
							var noSelect = false;
							$('.layer_group_info_box .left').css('color','black');
							$.each($('.layer_group_info_box .group_info'),function(i,item){
								if($(item).data('selectnum') > 1){
									if($('.group_info_row_'+$(this).data('group_id')+'.checked').size() != $(this).data('selectnum')){
										$(item).find('.left').css('color','red');
										layer.msg('您有菜品未进行选择',{icon:2});
										noSelect = true;
										return false;
									}
								}
							});
							if(noSelect == true){
								return false;
							}
							$.each($('.layer_group_info_box .right.checked'),function(i,item){
								$('.food_'+$(item).data('row_id')).trigger('click');
							});
							nowGroupId = group_id;
							$('.checkCart').trigger('click');
							// layer.closeAll();
						}
					});
				});
				
				return false;
			}
		});
	});
	$('.food_group_box li').live('click',function(){
		$('.food_group_box .checkRadio').removeClass('checked');
		$(this).find('.checkRadio').addClass('checked');
	});
	
	$('.layer_group_info_box .right').live('click',function(){
		var tmp_group_row = $('.group_info_'+($(this).data('group_row_id')));
		if(!$(this).hasClass('checked')){
			if(tmp_group_row.data('selectnum') == '1'){
				$('.group_info_row_'+$(this).data('group_row_id')+'.checked').removeClass('checked');		
			}else{
				if($('.group_info_row_'+$(this).data('group_row_id')+'.checked').size() == tmp_group_row.data('selectnum')){
					layer.msg('您只能选择'+tmp_group_row.data('selectnum')+'项',{icon:0});
					return false;
				}
			}
			$(this).addClass('checked');
		}else{
			if(tmp_group_row.data('maxnum') == '1'){
				layer.msg('此为必选，不可取消',{icon:2});
			}else if(tmp_group_row.data('selectnum') == '1'){
				return false;
			}else{
				$(this).removeClass('checked');
			}
		}
	});
	
	$('.checkCart').live('click',function(){
		var checkCartTip = layer.confirm('您确认要提交吗？', {
			title:'确认提醒：',
			btn: ['确认','取消']
		}, function(){
			var tip_index = layer.load(0, {shade: [0.5,'#fff']});
			var tmpCart = [];
			for(var i in productCart){
				tmpCart.push(productCart[i]);
			}
			$.post(foodshopSaveOrder,{cart:tmpCart,order_id:$('#order_id').val(),package_id:nowGroupId},function(result){
//			    console.log(result)
				if(result.status == 1){
					alert(result.info);
					location.reload();
				}else{
					layer.close(tip_index);
					layer.close(checkCartTip);
					alert(result.info);
				}
			});
		},function(){
			nowGroupId = 0;
		});
	});
	$('#edit_order').click(function(){
		layer.open({
			type: 2,
			title: '编辑订单',
			shadeClose: true,
			shade: 0.6,
			area: ['420px','450px'],
			content: foodshopEditOrder
		});
	});
	$('.changeCart').click(function(){
		$('#buyBox').show();
		$('#cartBox').hide();
	});
	$('.changeBuy').click(function(){
		$('#cartBox').show();
		$('#buyBox').hide();
	});
	$('#print_order').click(function(){
		layer.confirm('打印订单，只会打印订单中的已点菜品！而且只会使用主打印机进行打印，一般适用于用户买单确定。<br/><font color="red">确认打印？</span>', {
			title:'确认提醒：',
			btn: ['确认','取消']
		}, function(index){
			var tip_index = layer.load(0, {shade: [0.5,'#fff']});
			var tmpCart = [];
			for(var i in productCart){
				tmpCart.push(productCart[i]);
			}
			$.post(foodshopPrintOrder,function(result){
				layer.close(index);
				layer.close(tip_index);
				alert(result.info);
			});
		});
	});
	$('#pay_order').click(function(){
		var pay_order_tip = layer.confirm('<font color="red">确认进行结算？</span>', {
			title:'确认提醒：',
			btn: ['确认','取消']
		}, function(index){
			layer.open({
				type: 2,
				title: '创建到店付订单',
				shadeClose: true,
				shade: 0.6,
				area: ['800px','580px'],
				content: foodshopPayOrder
			});
			layer.close(pay_order_tip);
		});
	});
	
    $('body').off('click','.addBei').on('click','.addBei',function(e){
//        $('#detail_id').val($(this).data('detail_id'));
        $('#detail_key').val($(this).data('key'));
        $('#note').val($(this).data('note'));
        if ($(this).data('note').length > 0) {
            $('.remarkContent .itemTop').html('修改备注');
        } else {
            $('.remarkContent .itemTop').html('添加备注');
        }
        $('.remarkBg').show();
    });
    $('.qu').click(function(e){
        $('.remarkBg').hide();
    });
    
    $('.que').click(function(e){
        var key = $('#detail_key').val(), detail_id = $('#detail_id').val(), note = $('#note').val();
//        $.post(foodshopChangeOrderNote, {'detail_id':detail_id,'note':note}, function(result){
//            if (result.status == 1) {
                productCart[key].note = note;
//                alert(key)
                $('.productCartKey_' + key + ' .addRemark').html($('#note').val());
                $('.productCartKey_' + key + ' .addBei').data('note', note);
                $('.productCartKey_' + key + ' .addBei').html('修改备注');
                $('.remarkBg').hide();
//            } else {
//                alert(result.info);
//            }
//        });
    });
    
});

function changeSpec(){
	// console.log(nowSpecFood);
	if(nowSpecFood.spec_list){
		var specId = [];
		$.each($('.food_spec_box .spec_content ul'),function(i,item){
			specId.push($(item).find('li.active').data('spec_list_id'));
		});
		var nowProductSpect = nowSpecFood.list[specId.join('_')];
		// console.log(nowProductSpect);
		$('#specPrice').html(nowProductSpect.price);
		$('#specStock').html(nowProductSpect.stock_num);
		if(nowSpecFood.properties_list){
			$('.properties_content li').removeClass('active');
			for(var i in nowProductSpect.properties){
				$('.productProperties_'+nowProductSpect.properties[i].id).data('num',nowProductSpect.properties[i].num);
			}
		}
	}
}

var productCart = [];
function cartFunction(obj,type){
	if(type == 'plus'){
		if($('.productCartKey_'+obj.productKey).size() > 0){
			productCart[obj.productKey].count++;
			$('.productCartKey_'+obj.productKey+' .number input').val(parseFloat($('.productCartKey_'+obj.productKey+' .number input').val())+1);
			$('#cartCount').html(parseFloat($('#cartCount').html())+1);
		}else{
			if(!obj.count){
				obj.count = 1;
			}
			productCart[obj.productKey] = obj;
			if ($('#tmp_' + obj.uid).size() == 0) {
			    $('.food_cart_list ul').append('<li id="tmp_0">店员</li>');
			}
			laytpl($('#foodCartTpl').html()).render(obj, function(html){
				$('.food_cart_list ul').append(html);
			});
			$('#cartCount').html(parseFloat($('#cartCount').html())+obj.count);
		}
		
	}else if(type == 'min'){
		if($('.productCartKey_'+obj.productKey+' .number input').val().split('.').length > 1){
			$('.productCartKey_'+obj.productKey+' .number input').val(parseInt($('.productCartKey_'+obj.productKey+' .number input').val()));
			productCart[obj.productKey].count = parseInt(productCart[obj.productKey].count);
			$('#cartCount').html(parseInt($('#cartCount').html()));
		}else{
			$('.productCartKey_'+obj.productKey+' .number input').val(parseFloat($('.productCartKey_'+obj.productKey+' .number input').val())-1);
			productCart[obj.productKey].count--;
			$('#cartCount').html(parseFloat($('#cartCount').html())-1);
		}
		var uid = $('.productCartKey_'+obj.productKey).data('uid');
		if(productCart[obj.productKey].count == 0){
			$('.productCartKey_'+obj.productKey).remove();
		}
        if ($('.tmp_' + uid).size() < 1) {
            $('#tmp_' + uid).remove();
        }
        
	}else if(type == 'change'){
		var tmpNum = parseFloat($('.productCartKey_'+obj.productKey+' .number input').val());
		if(isNaN(tmpNum) || tmpNum <= 0){
			$('.productCartKey_'+obj.productKey+' .number input').val(productCart[obj.productKey].count);
			alert('请输入正确的数值');
			return false;
		}else if(tmpNum != productCart[obj.productKey].count){
			if(tmpNum > productCart[obj.productKey].count){
				$('#cartCount').html(parseFloat($('#cartCount').html()) + parseFloat(tmpNum-productCart[obj.productKey].count));
			}else{
				$('#cartCount').html(parseFloat($('#cartCount').html()) - parseFloat(productCart[obj.productKey].count-tmpNum));
			}
			productCart[obj.productKey].count = tmpNum;
		}
	}
	$('#cartBox').show();
	$('#buyBox').hide();
	//console.log(productCart);
}

var productBuyCart = [];
function cartBuyFunction(obj,type){
	//console.log(type)
	if(type == 'plus'){
		if($('.productBuyKey_'+obj.productKey).size() > 0){
			var tip_index = layer.load(0, {shade: [0.5,'#fff']});
			$.post(foodshopChangeOrder,{detail_id:obj.detail_id,number:obj.count+1},function(result){
				layer.close(tip_index);
				if(result.status == 1){
					productBuyCart[obj.productKey].count++;
					$('.productBuyKey_'+obj.productKey+' .number input').val(parseFloat($('.productBuyKey_'+obj.productKey+' .number input').val())+1);
					$('#buyCount').html(parseFloat($('#buyCount').html())+1);

					now_order_price(result.total_price, result.book_price, result.unpaid_price,result.extra_price);
				}else{
					alert(result.info);
				}
			});
		}else{
			productBuyCart[obj.productKey] = obj;
			laytpl($('#foodBuyTpl').html()).render(obj, function(html){
				$('.food_buy_list ul').append(html);
			});
			$('#buyCount').html(parseFloat($('#buyCount').html())+obj.count);
		}
	}else if(type == 'show'){
		productBuyCart[obj.productKey] = obj;
		laytpl($('#foodBuyTpl').html()).render(obj, function(html){
			$('.food_buy_list ul').append(html);
		});
		$('#buyCount').html(parseFloat($('#buyCount').html())+obj.count);
	}else if(type == 'min'){
		var tip_index = layer.load(0, {shade: [0.5,'#fff']});
		
		if($('.productBuyKey_'+obj.productKey+' .number input').val().split('.').length > 1){
			var tmpPostNum = parseInt($('.productBuyKey_'+obj.productKey+' .number input').val());
		}else{
			var tmpPostNum = parseFloat($('.productBuyKey_'+obj.productKey+' .number input').val())-1;
		}
		
		$.post(foodshopChangeOrder,{detail_id:obj.detail_id,number:tmpPostNum},function(result){
			layer.close(tip_index);
			if(result.status == 1){
				if($('.productBuyKey_'+obj.productKey+' .number input').val().split('.').length > 1){
					$('.productBuyKey_'+obj.productKey+' .number input').val(parseInt($('.productBuyKey_'+obj.productKey+' .number input').val()));
					productBuyCart[obj.productKey].count = parseInt(productBuyCart[obj.productKey].count);
					$('#buyCount').html(parseInt($('#buyCount').html()));
				}else{
					$('.productBuyKey_'+obj.productKey+' .number input').val(parseFloat($('.productBuyKey_'+obj.productKey+' .number input').val())-1);
					productBuyCart[obj.productKey].count--;
					$('#buyCount').html(parseFloat($('#buyCount').html())-1);
				}
				var uid = $('.productBuyKey_'+obj.productKey).data('uid');
				if(productBuyCart[obj.productKey].count == 0){
					$('.productBuyKey_'+obj.productKey).remove();
				}
				if ($('.buy_' + uid).size() < 1) {
				    $('#buy_' + uid).remove();
				}
				now_order_price(result.total_price, result.book_price, result.unpaid_price,result.extra_price);
			}else{
				alert(result.info);
			}
		});
	}else if(type == 'change'){
		var tmpNum = parseFloat($('.productBuyKey_'+obj.productKey+' .number input').val());
		if(isNaN(tmpNum) || tmpNum <= 0){
			$('.productBuyKey_'+obj.productKey+' .number input').val(productBuyCart[obj.productKey].count);
			alert('请输入正确的数值');
			return false;
		}else if(tmpNum != productBuyCart[obj.productKey].count){
			var tip_index = layer.load(0, {shade: [0.5,'#fff']});
			$.post(foodshopChangeOrder,{detail_id:obj.detail_id,number:tmpNum},function(result){
				layer.close(tip_index);
				if(result.status == 1){
					if(tmpNum > productBuyCart[obj.productKey].count){
						$('#buyCount').html(parseFloat($('#buyCount').html()) + parseFloat(tmpNum-productBuyCart[obj.productKey].count));
					}else{
						$('#buyCount').html(parseFloat($('#buyCount').html()) - parseFloat(productBuyCart[obj.productKey].count-tmpNum));
					}
					productBuyCart[obj.productKey].count = tmpNum;
					now_order_price(result.total_price, result.book_price, result.unpaid_price,result.extra_price);
				}else{
					alert(result.info);
				}
			});
		}
	}
	//console.log(productBuyCart);
}

function changeBody(){
	var bodyUlWidth = $('.food_body').width()-10;
	var rowNumber = parseInt(bodyUlWidth/270);

	$('.food_body ul.cur').css('padding-left',(bodyUlWidth - ((rowNumber*270) + (rowNumber-1)*15))/2);
	$('.food_body ul.cur').css('padding-right',(bodyUlWidth - ((rowNumber*270) + (rowNumber-1)*15))/2);
	
	// $('.food_body ul.cur li').css('margin-right',parseInt((bodyUlWidth - (rowNumber*270))/(rowNumber-1)));
	
	$.each($('.food_body ul.cur li'),function(i,item){		
		if((i+1)%rowNumber == 0){
			$(item).css('margin-right','0');
		}
	});
}

function now_order_price(total_price, book_price, unpaid_price,extra_price)
{
	
	if(open_extra_price==1&&extra_price>0){
		$('#unpaid_price').html('<b>还应支付：</b>' + unpaid_price+'+'+extra_price+extra_price_name);
		//$('#total_price').html('<b>订单总价：</b>' + total_price+'+'+extra_price+extra_price_name);
	}else{
		$('#unpaid_price').html('<b>还应支付：</b>' + unpaid_price);
	}
	
	$('#book_price').html('<b>已付订金：</b>' + book_price);
	if(open_extra_price==1&&extra_price>0){
		$('#total_price').html('<b>订单总价：</b>' + total_price+'+'+extra_price+extra_price_name);
	}else{
		$('#total_price').html('<b>订单总价：</b>' + total_price);
	}
}