var staffArr = null, total_goods = null, total_goods_obj = {}, goodsCart = {}, goodsNumber = 0, goodsCartMoney = 0;
$(function(){
	//背景单窗高度  
	var hi=$(window).height();
	$(".foodnav").css("height",hi-136);
	$(".foodright").css("height",hi-136);
	
	staffArr = common.getCache('store_staff',true);
	init_goods(staffArr.store_id);
	
	// common.onlyScroll($('.Cart .Cart_list'));
	$('.Cart .Cart_list').css('overflow-y','auto');
	
	//数量加减
	
	$(document).on('click', '.Addsub a', function(){
		changeNum($(this));
	});
   
	//清空购物车
    $(".Cart_top span").click(function(){
    	$(".empty,.mask").show();

    	$(".empty .close,.empty .del,.mask").click(function(){
    		$(".empty,.mask").hide();
    	});
    	
    	$(".empty .ensure").click(function(){
    		$(".empty,.mask").hide();
    		$(".Cart_list").find("li").remove();
	        $(".floor").removeClass("floorOn");
	        $(".qty").hide(500);
	        $('.prix i').text(0);
	        $(".Cart").slideUp();
	        $(".Maskcat").hide();
	        $('.foodright .Addsub').find('input').val(0);
	        $('.foodright .Addsub').find('.jia').siblings().hide();
	        goodsNumber = 0;
	        goodsCartMoney = 0;
	        goodsCart = {};
			$('.foodnav li em').remove();
    	})
 
    });
	
	//购物效果 
	$(".Maskcat,.floor").click(function(event){
		if($(".Cart").is(":hidden")){
			if(goodsNumber == 0){
				return false;
			}
			laytpl($('#listCartTpl').html()).render(goodsCart, function(html){
				$(".Cart_list ul").html(html);
			});
			
			$(".Cart").slideDown();
			$(".Maskcat").show();   
		}else{
			$(".Cart").slideUp();
			$(".Maskcat").hide();  
		}
	});

	$(".floor .next").click(function(event){
		if(goodsNumber > 0){
			var newBuyList = [];
			for(var i in goodsCart){
				if(goodsCart[i].count > 0){
					newBuyList.push(goodsCart[i]);
				}
			}
			common.setCache('buy_list',newBuyList,true);
			redirect('back');
			//此处问题，是合并原来的订单，还是重新设置。
		}
		event.stopPropagation();
	});


	/*左侧滚动条*/
    $(".foodright").scroll(function(){
        var top=$(".foodright").scrollTop();
        var menu = $(".foodnav");
        var item = $(".foodright dl");
        var onid = "";
        item.each(function() {
        var n = $(this);
            var itemtop=$('.foodright-'+$(this).data('cat_id')).offset().top-$('.foodright').offset().top+$('.foodright').scrollTop();
			
            if(top>itemtop-100){
            onid=n.data('cat_id');
            }
        });
        var link = menu.find(".on");
        link.removeClass("on");
        menu.find("[data-cat_id="+onid+"]").addClass("on");
        //alert(top);
    })
    $(document).on('click','.foodnav a',function(){
		$('.foodright').animate({scrollTop:$('.foodright-'+$(this).data('cat_id')).offset().top-$('.foodright').offset().top+$('.foodright').scrollTop()},500) ;
    });

    $(".foodright dl").last().css({"min-height":hi-136});
	

    // 搜索
    $(".sp_sear").focus(function(){
        $(".c_input").addClass('so');
        $(".sp_foodleft,.sp_foodright,.hunt_scan").hide();
        $(".hunt_remove,.foods_list").show();
    });
  
	$(".foods_list").css({"height":$(window).height()-86-50,"border-top":"#eeeeee 1px solid",'display':'none'});
	
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
            $(".c_input .clean").hide();  
        }
    });

    $('.c_input .clean').click(function(){
        $(this).hide();
        $('.sp_sear').val('').focus();
    });

    $(".hunt_remove").click(function(){
        $('.sp_sear').val('');
        $(".c_input").removeClass('so');
        $(".sp_foodleft,.sp_foodright,.hunt_scan").show();
        $(".hunt_remove,.c_input .clean,.foods_list").hide();
        $(".foods_list dl dd").remove();
        // $(".foods_list").css({"height":"0","border-top":"none"});
    });
	
	$('.hunt_scan').click(function(){
		common.scan('scanSearchResult');
	});
	
	$(document).on('click','.foods_list dd',function(){
		var goods_id = $(this).data('goods_id');
		changeNum($('.food_'+goods_id+' .jia'));
		$(".hunt_remove").trigger('click');
	});
});

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
		motify.log('商品添加成功',3000,{},10);
		changeNum($('.food_'+nowGood.goods_id+' .jia'));
	}else{
		motify.log('暂无匹配的商品');
	}
}

function changeNum(that){
	var goods_id = that.data('goods_id');
		
	var now_goods = total_goods_obj[goods_id];
	
	if(that.hasClass("jia")){
		
		if(!goodsCart[goods_id]){
			now_goods.count = 1;
			goodsCart[goods_id] = now_goods;
		}else{
			goodsCart[goods_id].count++;
		}
		console.log(goodsCart[goods_id]);
		that.siblings().show();
		$('.food_'+goods_id).find('input').val(goodsCart[goods_id].count);
		
		goodsNumber ++;
		goodsCartMoney += goodsCart[goods_id].price;
		
		var leftdom = $('.foodleft-'+goodsCart[goods_id].sort_id);
		var emdom = leftdom.find('em');
		if(emdom.size() > 0){
			emdom.html(parseInt(emdom.html())+1);
		}else{
			leftdom.append('<em>1</em>')
		}
	}else{
		goodsCart[goods_id].count--;
		if(goodsCart[goods_id].count == 0){
			$('.food_'+goods_id).find('.jia').siblings().hide();
			if(that.hasClass('cart_jian')){
				that.closest('li').remove();
			}
		}
		$('.food_'+goods_id).find('input').val(goodsCart[goods_id].count);
		goodsNumber --;
		goodsCartMoney -= goodsCart[goods_id].price;
		
		var emdom = $('.foodleft-'+goodsCart[goods_id].sort_id).find('em');
		
		var emcount = parseInt(emdom.html());
		if(emcount > 1){
			emdom.html(parseInt(emdom.html())-1);
		}else{
			emdom.remove();
		}
	}
	
	
	if (goodsNumber > 0){
		$(".floor").addClass("floorOn");
		$(".qty").show(500).text(goodsNumber);
		$('.prix i').text(common.floatVal(goodsCartMoney));	
	}else{
		goodsCart = [];
		$(".floor").removeClass("floorOn");
		$(".qty").hide(500);
		$('.prix i').text(0);
		$(".Cart").slideUp();
		$(".Maskcat").hide();
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
			total_goods_obj[good_data[i].goods_list[ii].goods_id] = good_data[i].goods_list[ii];
		}
	}
	total_goods = new_data;
	
	
	laytpl($('#listCatTpl').html()).render(good_data, function(html){
		$(".foodnav ul").html(html);
	});
	laytpl($('#listProductTpl').html()).render(good_data, function(html){
		$(".foodright").html(html);
	});
	var buyList = common.getCache('buy_list',true);
	for(var i in buyList){
		console.log(buyList[i]);
		for(var k=0; k < buyList[i].count; k++){
			changeNum($('.food_'+buyList[i].goods_id+' .jia'));
		}
	}
}