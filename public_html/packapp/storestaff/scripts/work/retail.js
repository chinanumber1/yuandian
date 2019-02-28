var tmpCardInfo = {};
var total_goods = null;
var staffArr = null;
$(document).ready(function(){
	
	$(".tit_list").height($(window).height()-(common.checkIosApp() ? 245 : 225));
	
	$(".membership").css({"top":($(window).height()-$(".membership").height())/2,"margin-top":"0px"});
	
	$(".foods_list").css({"height":$(window).height()-86,"border-top":"#eeeeee 1px solid"});
	
	//店员名字
	staffArr = common.getCache('store_staff',true);
	if(!staffArr){
		location.href = 'index.html';
	}
	$('#staffinfo-name').html(staffArr.name);
	
	//当前日期
	$('#staffinfo-nowday').html(common.formartdate('yyyy-mm-dd'));
	
	//会员卡信息
	getCardInfo();
	
	//展开收起
	$(".jump_list .more").click(function(){
		if($(".jump_ul").is(":hidden")){
			$(".jump_ul").slideDown(),$(this).find("span").text("收起"),$(this).addClass("on");
		}else{
			$(".jump_ul").slideUp(),$(this).find("span").text("展开"),$(this).removeClass("on"); 
		}
	});

	//收索清除
	$(".search .cancel").click(function(){
		$(this).siblings("input").val("");
	});

	//搜索栏
	init_goods(staffArr.store_id);
	render_buy();
	guadan_count();
	
	$('.scanning .sweep').click(function(){
		common.scan('scanSearchResult');
	});
	
	
	//会员卡
	$(".introduce").bind('input', function(e){
		var cartNo = $.trim($(this).val());
		if(cartNo.length > 0){
			$('.inquiry').addClass('so');
		}else{
			$('.inquiry').removeClass('so');
		}
	});
	
	var qrCon = requestUrl.replace('appapi.php','wap.php')+'My_card&a=merchant_card&mer_id='+staffArr.mer_id;
	var qrImg = requestUrl.replace('appapi.php','index.php')+'Recognition&a=get_own_qrcode&qrCon='+encodeURIComponent(qrCon);
	$('#scan_code_img').prop('src',qrImg);
	$(".img-none .receive").click(function(){
        $(".pop_wx,.wx_mask").show();
    });
    $(".pop_wx .del,.wx_mask").click(function(){
        $(".pop_wx,.wx_mask").hide();
    })


	$('.inquiry').click(function(){
		if(!$(this).hasClass('so')){
			return false;
		}else{
			common.http('Storestaff&a=ajax_card', {'key':$(".introduce").val()}, function(data){
				motify.log('已成功使用会员卡',3000,{},10);
				data.discount = common.floatVal(data.discount);
				data.discount = common.floatVal(data.discount);
				common.setCache('card_data',data,true);
				getCardInfo();
			},function(){
				common.removeCache('card_data',true);
				getCardInfo();
			});
		}
	});
	
	$('.membership .query .input .search').click(function(){
		common.scan('scanCardResult');
	});
	
	$(".hyk").click(function(){
		$(".mask,.membership").show();
		$(".introduce").val('').focus();
		$(".img-none .not_found").hide().siblings(".not_input").show();
	});
	$(".mask,.membership .del").click(function(){
		$(".mask,.membership").hide();
	});

	//清空
	$(".qk").click(function(){
		$(".mask,.seek").show();
	});
	$(".mask,.seek .del,.seek .close").click(function(){
		$(".mask,.seek").hide();
	});
	$(".seek .ensure").click(function(){
		//会员卡
		common.removeCache('card_data',true);
		getCardInfo();
		
		//列表商品
		common.removeCache('buy_list',true);
		$('.tit_list ul').empty();
		
		$('#totalNum,#totalPrice').html('0');
		
		$(".mask,.seek").hide();
	});
	

	//弹层价格加减
	$(document).on('click', '.tit_list li', function(){
		var goods_id = $(this).data('goods_id');
		var now_goods = null;
		var buyList = common.getCache('buy_list',true);
		for(var i in buyList){
			if(buyList[i].goods_id == goods_id){
				now_goods = buyList[i];
			}
		}
		if(now_goods == null){
			motify.log('当前商品不存在');
			return false;
		}
		laytpl($('#listGoodTpl').html()).render(now_goods, function(html){
			$("body").append(html);
		});
		
		$(".mask").show();

		$('.rev_ul a').click(function(){
			if($(this).hasClass("jia")){
				var number = parseInt($('.rev_input').val())+1;
			}else{
				var number = parseInt($('.rev_input').val())-1;
			};
			buy_save_number(now_goods.goods_id,number);
			if(number > 0){
				now_goods.count = number;
				$('.rev_input').val(number);
				$('.rev_ul .total').html('￥'+(now_goods.count*now_goods.price));
				motify.log('已成功修改数量',1500,{},10);
			}else{
				$(".mask").hide();
				$(".revise").remove();
			}
		});
	 
		$('.mask,.keep,.revise .dels').click(function(){
			$(".mask").hide();
			$(".revise").remove();
		});
		
		$('.revise .del').click(function(){
			buy_save_number(now_goods.goods_id,0);
			motify.log('已成功删除',1500,{},10);
			$(".mask").hide();
			$(".revise").remove();
		});
		
		$('.rev_input').keyup(function(){
			var addval = $.trim($(this).val());
			if(addval.length > 0){
				var addval = parseInt(addval);
				if(isNaN(addval)){
					motify.log('请输入数字');
				}else{
					now_goods.count = addval;
					buy_save_number(now_goods.goods_id,addval);
					$('.rev_input').val(addval);
					$('.rev_ul .total').html('￥'+(now_goods.count*now_goods.price));
				}
			}
		});
	});

    // 搜索
    $(".scan_input").click(function(e){
		if(total_goods == null){
			motify.log('暂无商品');
			return false;
		}
        $(".homepage").hide();
        $(".cable_show,.hunt_remove").show();
        $(".sp_sear").focus();
    });

    $(".sp_sear").bind('input', function(e){
        var key = $.trim($(this).val());
        if(key.length > 0){
			var search_goods = [];
			for (var i in total_goods) {
				if(total_goods[i].name.indexOf(key) >= 0 || total_goods[i].number.indexOf(key) == 0){
					search_goods.push(total_goods[i]);
				}
			}
			laytpl($('#listSearchTpl').html()).render(search_goods, function(html){
				$(".foods_list dl").html(html);
			});
            $(".c_input .clean").show();
        }else{
			$(".foods_list dl").empty();
            $(".c_input .clean").hide();  
        }
    });
	
	$(document).on('click','.foods_list dd',function(){
		var goods_id = $(this).data('goods_id');
		add_buy_good(goods_id,staffArr.store_id);
		$(".hunt_remove").trigger('click');
	});

    $('.c_input .clean').click(function(){
        $(this).hide();
        $('.sp_sear').val('').focus();
    });

    $(".hunt_remove").click(function(){
		$(".foods_list dl").empty();
		$(".c_input .clean").hide();
		$(".sp_sear").val('');
		
        $(".cable_show").hide();
        $(".homepage").show();
    });
	
	//挂单
	$('.gd a').click(function(){
		var buyList = common.getCache('buy_list',true);
		if(buyList){
			guadan_add(buyList);
			common.removeCache('buy_list',true);
			render_buy();
			motify.log('挂单成功');
			return false;
		}
		if($('.gd em').html() == '0'){
			motify.log('没有挂单');
			return false;
		}
	});
	
	$('.jiesuan').click(function(){
		var postObj = {};
		//订单数据
		var buyList = common.getCache('buy_list',true);
		if(buyList && buyList.length > 0){
			postObj.goods_data = [];
			for(var i in buyList){
				var tmpData = {};
				tmpData.goods_id = buyList[i].goods_id;
				tmpData.name = buyList[i].name;
				tmpData.unit = buyList[i].unit;
				tmpData.num = buyList[i].count;
				tmpData.price = buyList[i].price;
				tmpData.number = buyList[i].number;
				postObj.goods_data.push(tmpData);
			}
			postObj.store_id = staffArr.store_id;
			
			//会员卡数据
			var card_data = common.getCache('card_data',true);
			if(card_data){
				postObj.card_data = card_data;
			}else{
				postObj.card_data = '';
			}
			
			console.log(postObj);
			
			common.http('Storestaff&a=shop_order_save',{data:postObj}, function(data){
				common.setCache('order_info',data,true);
				location.href = 'settlement.html';
			});
		}else{
			motify.log('没有选择商品');
			return false;
		}
	});
	$('.scan_again').click(function(){
		common.scan('scanSearchResult');
	});
	$(window).on("pageshow",function(){
		render_buy();
		guadan_count();
		$('.jump_list').css('left','');
	});
});
function guadan_add(order){
	var guadan_list = common.getCache('guadan_list');
	if(!guadan_list){
		guadan_list = [];
	}
	guadan_list.unshift(order);
	common.setCache('guadan_list',guadan_list);
	guadan_count();
}
function guadan_count(){
	var guadan_list = common.getCache('guadan_list');
	var guadan_count = guadan_list ? guadan_list.length : 0;
	$('.gd em').html(guadan_count);
}
function add_buy_good(goods_id,store_id){
	for(var i in total_goods){
		if(total_goods[i].goods_id == goods_id){
			var buyGood = total_goods[i];
			break;
		}
	}
	var buyList = common.getCache('buy_list',true);
	var newBuyList = [];
	buyGood.count = 1;
	if(buyList){
		for(var i in buyList){
			if(buyList[i].goods_id == goods_id){
				buyGood.count = buyList[i].count+1;
			}else{
				newBuyList.push(buyList[i]);
			}
		}
		newBuyList.unshift(buyGood);
	}else{
		newBuyList.push(buyGood);
	}
	common.setCache('buy_list',newBuyList,true);
	render_buy();
}
function buy_save_number(goods_id,number){
	var buyList = common.getCache('buy_list',true);
	var newBuyList = [];
	for(var i in buyList){
		if(buyList[i].goods_id == goods_id){
			if(number > 0){
				buyList[i].count = number;
				newBuyList.push(buyList[i]);
			}
		}else{
			newBuyList.push(buyList[i]);
		}
	}
	common.setCache('buy_list',newBuyList,true);
	render_buy();
}
function render_buy(){
	var buyList = common.getCache('buy_list',true);
	if(buyList){
		var totalNum = 0;
		var totalPrice = 0;
		for(var i in buyList){
			totalNum = buyList[i].count + totalNum;
			totalPrice = (buyList[i].price * buyList[i].count) + totalPrice;
		}
		$('#totalNum').html(totalNum);
		$('#totalPrice').html(common.floatVal(totalPrice));
		laytpl($('#listBuyTpl').html()).render(buyList, function(html){
			$(".tit_list ul").html(html);
		});
	}else{
		$(".tit_list ul").empty();
		$('#totalNum,#totalPrice').html('0');
	}
}

function init_goods(store_id){
	var good_data = common.getCache('total_goods',true);
	if(!good_data){
		common.http('Storestaff&a=ajax_shop_goods',{'store_id':store_id,'refresh':0}, function(data){
			common.setCache('total_goods',data,true);
			format_goods(data);
		});
	}else{
		format_goods(good_data);
	}
}
function format_goods(good_data){
	var new_data = [];
	for (var i in good_data) {
		for (var ii in good_data[i].goods_list) {
			new_data.push(good_data[i].goods_list[ii]);
		}
	}
	total_goods = new_data;
	
	var barcode = common.getCache('shopBarCode',true);
	if(barcode){
		scanSearchResult(barcode);
		common.removeCache('shopBarCode',true);
	}
}

var showAgainTimer = null;
function scanSearchResult(str){
	var strArr = str.split(',');
	if(strArr.length == 2){
		str = strArr[1];
	}
	var nowGood = null;
	for(var i in total_goods){
		if(total_goods[i].number == str){
			nowGood = total_goods[i];
		}
	}
	if(nowGood != null){
		add_buy_good(nowGood.goods_id,staffArr.store_id);
		motify.log('商品添加成功',3000,{},10);
		clearTimeout(showAgainTimer);
		$('.scan_again').show();
		showAgainTimer = setTimeout(function(){
			$('.scan_again').hide();
		},3000);
	}else{
		$('.scan_again').hide();
		motify.log('暂无匹配的商品',3000,{},10);
	}
}
function scanCardResult(str){
	$('.introduce').val(str);
	$('.inquiry').addClass('so').trigger('click');
}
function getCardInfo(){
	$('.introduce').val('');
	$('.inquiry').removeClass('so');
		
	var card_data = common.getCache('card_data',true);
	if(card_data){
		$('#cartinfo-card_id').html(card_data.card_id);
		$('#cartinfo-card_money').html(card_data.card_money);
		$('.nocard').addClass('hide');
		$('.card_info').removeClass('hide');
		
		common.setData({card_info:card_data});
		if(card_data.discount == 10){
			$('#discountBox').html('无折扣');
		}
	}else{
		$('#cartinfo-card_id').html('无');
		$('#cartinfo-card_money').html('0');
		$('.card_info').addClass('hide');
		$('.nocard').removeClass('hide');
	}
}