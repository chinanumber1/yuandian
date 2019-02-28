$(document).ready(function(){
    
    common.setCache('order_id',urlParam.order_id, true);
  //修改金额
    $(document).on('click', '.amount', function(){
    	$('#order_id').val($(this).data('id'));
    	$('#change_price, #change_price_reason').val('');
    	$('#now_price').html($(this).data('price'));
        $(".mask,.amend").show();
    });
    $(document).on('click', '.button .section_recovery, .button .section_ensure', function(e){
    	e.stopPropagation();
        var type = $(this).data('type'), order_id = $('#order_id').val(), change_price_reason = $('#change_price_reason').val(), change_price = $('#change_price').val();
        if (change_price == '') {
        	motify.log('金额不能为空');
        	return false;
        }
        common.http('Storestaff&a=shopChangePrice',{'type':type, 'order_id':order_id, 'change_price':change_price, 'change_price_reason':change_price_reason, noTip:true}, function(data){
        	$('.change_' + order_id).html('￥' + data + ' <em class="xgh">'+ change_price_reason + '</em>');
            $('.modify_' + order_id).data('price', data);
            $(".mask,.amend").hide();
            location.reload();
        });
    });

    
    common.http('Storestaff&a=shopDetail', {'order_id':urlParam.order_id}, function(data){
        console.log(data)
        var headerHtml = '<a class="link-url" data-nobtn="true" data-url="shop_order_log.html?order_id=' + urlParam.order_id + '" data-webview="true">';
        if (data.order_details.orderStatus.status == 0) {
            headerHtml += '<h2>订单生成成功</h2> <p>订单编号：' + data.order_details.real_orderid + '</p>';
        } else if (data.order_details.orderStatus.status == 1) {
            headerHtml += '<h2>订单支付成功</h2> <p>订单编号：' + data.order_details.real_orderid + '</p>';
        } else if (data.order_details.orderStatus.status == 2) {
            headerHtml += '<h2>店员接单</h2> <p>正在为您准备商品</p>';
        } else if (data.order_details.orderStatus.status == 3) {
            headerHtml += '<h2>配送员接单</h2> <p>配送员正在赶往店铺取货</p>';
        } else if (data.order_details.orderStatus.status == 4) {
            headerHtml += '<h2>配送员取货</h2> <p>已取货，准备配送，请耐心等待</p>';
        } else if (data.order_details.orderStatus.status == 5) {
            headerHtml += '<h2>配送员配送中</h2> <p>配送员正快速向您靠拢，请耐心等待！</p>';
        } else if (data.order_details.orderStatus.status == 6) {
            headerHtml += '<h2>订单已完成</h2> <p>';
            if (data.order_details.is_pick_in_store < 2) {
                headerHtml += '配送员已完成配送，欢迎下次光临！';
            } else {
                headerHtml += '订单编号：' + data.order_details.real_orderid;
            }
            headerHtml += '</p>';
        } else if (data.order_details.orderStatus.status == 7) {
            if (data.order_details.is_pick_in_store == 3) { 
                headerHtml += '<h2>店员已发货</h2> <p>已发货给快递公司<strong style="color:red">【' + data.order_details.express_name + '】</strong>，快递单号:<strong style="color:green">' + data.order_details.express_number + '</strong></p>';
            } else {
                headerHtml += '<h2>店员验证消费</h2> <p>订单改成已消费</p>';
            }
        } else if (data.order_details.orderStatus.status == 8) {
            headerHtml += '<h2>完成评论</h2> <p>您已完成评论，谢谢您提出宝贵意见！</p>';
        } else if (data.order_details.orderStatus.status == 9) {
            headerHtml += '<h2>已完成退款</h2> <p>';
            if (data.order_details.orderStatus.note.length < 1) {
                headerHtml += '您已完成退款'; 
            } else {
                headerHtml += data.order_details.orderStatus.note;
            }
            headerHtml += '</p>';
        } else if (data.order_details.orderStatus.status == 10) {
            headerHtml += '<h2>已取消订单</h2> <p>';
            if (data.order_details.orderStatus.note.length < 1) {
                headerHtml += '您已经取消订单'; 
            } else {
                headerHtml += data.order_details.orderStatus.note;
            }
            headerHtml += '</p>';
        } else if (data.order_details.orderStatus.status == 11) {
            headerHtml += '<h2>商家分配自提点</h2> <p>店员给您分配了自提点</p>';
        } else if (data.order_details.orderStatus.status == 12) {
            headerHtml += '<h2>商家发货到自提点</h2> <p>店员已经给您发货到配送点</p>';
        } else if (data.order_details.orderStatus.status == 13) {
            headerHtml += '<h2>自提点已接货</h2> <p>自提点已经接到您的货物了</p>';
        } else if (data.order_details.orderStatus.status == 14) {
            headerHtml += '<h2>自提点已发货</h2> <p>自提点已经给您发货了</p>';
        } else if (data.order_details.orderStatus.status == 15) {
            headerHtml += '<h2>您在自提点取货</h2> <p>您在自提点已经把您的货提走了！</p>';
        } else if (data.order_details.orderStatus.status == 30) {
            headerHtml += '<h2>店员为您修改了价格</h2> <p>店员已将订单的总价修改成' + data.order_details.orderStatus.note + '</p>';
        } else if (data.order_details.orderStatus.status == 31) {
            headerHtml += '<h2>配送员放弃配送</h2> <p>' + data.order_details.orderStatus.note + '</p>';
        }
        headerHtml += '<div class="time">' + data.order_details.orderStatus.dateline + '</div>';
        headerHtml += '<em>更多状态</em>';
        headerHtml += '</a>';
        $('.defrayal .defrayal_n').html(headerHtml);
    	laytpl($('#allTpl').html()).render(data, function(html){
			$('.g_details').html(html);
			$(".kd_dl .hx").height($(".kd_dl").height()-65);
		});
	});
    
    
    //确认消费
    $(document).on('click', '.mask, .seek .close, .seek .del', function(){
        $(".mask,.seek").hide();
    });

    //顶部按钮
    $(window).scroll(function(){
        if($(this).scrollTop() > 200){
           $(".coping").show(); 
        }else{
           $(".coping").hide();  
        }
    });
    $(".coping .top").click(function(){
        $("body,html").animate({
            scrollTop: 0
        },500);
    });

	
	// 配送弹窗
	$(".flat").css({"top":($(window).height()-$(".flat").height())/2});
	
	
	//接单
	var confirmFlag = false;
    $(document).on('click', '.kd_rob', function(){
        if (confirmFlag) {
            return false;
        }
        confirmFlag = true;
        var order_id = $(this).data('id');
        common.http('Storestaff&a=shopOrderEdit',{'status':1, 'order_id':order_id, noTip:true}, function(data){
            confirmFlag = false;
        	location.reload();
        });
    });
	
	//发货到自提点
    $(document).on('click', '.send', function(){
        var order_id = $(this).data('id');
        common.http('Storestaff&a=shopOrderEdit', {'status':8, 'order_id':order_id, noTip:true}, function(data){
        	location.reload();
        });
    });
	
	//确认消费弹窗提示
    $(document).on('click', '.sure', function(){
    	$('.div_ensure').data('id', $(this).data('id'))
    	$(".seek,.mask").show();
    });

    
	//快递配送时候
    $(document).on('click', '.express', function(){
        common.http('Storestaff&a=getExpress',{'order_id':$(this).data('id'), noTip:true}, function(data){
        	laytpl($('#expressTpl').html()).render(data, function(html){
    			$('.flat').html(html);
    		});
        	$(".flat,.mask").show();
        	$('.flat').css({"top":($(window).height()-$(".flat").height())/2});
        });
    });
    
    //确认消费
    $(document).on('click', '.div_ensure', function(){
        var order_id = $(this).data('id');
        common.http('Storestaff&a=shopOrderEdit',{'status':2, 'order_id':order_id, noTip:true}, function(data){
        	location.reload();
        });
    });
    $(document).on('click', '.express_save', function(){
        var order_id = $(this).data('id'), express_id = $('#express_id').val(), express_number = $('#express_number').val();
        if (express_id.length < 1) {
        	motify.log('请选择快递公司');
        	return false;
        }
        if (express_number.length < 1) {
        	motify.log('请填写快递单号');
        	return false;
        }
        common.http('Storestaff&a=shopOrderEdit',{'status':1, 'order_id':order_id, 'express_number':express_number, 'express_id':express_id, noTip:true}, function(data){
        	location.reload();
        });
    });
	
	//取消订单
    $(document).on('click', '.cancel', function(){
        var order_id = $(this).data('id');
        common.http('Storestaff&a=shopOrderEdit',{'status':5, 'order_id':order_id, noTip:true}, function(data){
        	location.reload();
        });
    });
    
	//更换配送方式
    $(document).on('click', '.change_deliver', function(){
        common.http('Storestaff&a=mallOrderDetail',{'order_id':$(this).data('id'), noTip:true}, function(data){
        	laytpl($('#deliverTpl').html()).render(data, function(html){
    			$('.flat').html(html);
    		});
        	$(".flat,.mask").show();
        	$('.flat').css("margin-top",-$('.flat').height()/2);
        	$('#demo').scroller('destroy').scroller($.extend(opt['dateYMD'],opt['default']));
        });
    });
    $(document).on('click', '.con .sub', function(){
        common.http('Storestaff&a=checkDeliver',{'order_id':$(this).data('id'), 'expect_use_time':$('#demo').val(), noTip:true}, function(data){
        	location.reload();
        });
    });
    
    //------------pick address-------------------
    $(document).on('click', '.kd_since', function(e){
    	e.stopPropagation();
    	common.http('Storestaff&a=getPickAddress', {'order_id':$(this).data('id'), noTip:true}, function(data){
    		laytpl($('#pcikTpl').html()).render(data, function(html){
    			$('.since').html(html);
    			$(".mask, .since").show();
    			$('.since').css("margin-top", -$('.since').height()/2);
    			new IScroll('.since .ul',{ click: true});
            });
    	});
    });
    $(document).on('click', '.since li', function(e){
    	e.stopPropagation();
        $(this).addClass("on").siblings().removeClass("on");
    });
    $(document).on('click', '.determine', function(e){
    	e.stopPropagation();
    	var pick_id = $('.since').find('.on').data('id'), order_id = $(this).data('id');
    	if (pick_id == undefined) {
    		motify.log('请选择自提点');
    		return false;
    	}
    	common.http('Storestaff&a=pick', {'order_id':$(this).data('id'), 'pick_id':pick_id, noTip:true}, function(data){
    		if (data == 'SUCCESS') {
    			location.reload();
    		}
    		$('.mask, .since').hide();
    	});
    });
    //退款
    $(document).on('click', '.refund', function(e){
        var refund_id = $(this).data('refund_id'), type = $(this).data('type'), order_id = $(this).data('id');
        var reply_content = $(this).parent('dd').find('input[name="reply_content"]').val();
        common.http('Storestaff&a=replyRefund', {'order_id':order_id, 'refund_id':refund_id, 'reply_content':reply_content, 'type':type}, function(response){
            if (response.code == 1) {
                layer.msg(response.msg);
            } else {
                location.reload();
            }
        });
    });
    
	$(document).on('click', '.mask, .del', function(){
        $('.mask, .flat, .since, .amend').hide();
    });

//插件日历
    var currYear = new Date().getFullYear();
    var opt = {  
        'dateYMD': {
            preset: 'datetime',
            dateFormat: 'yyyy-mm-dd',
//            theme: 'android-ics light', //皮肤样式
            display: 'modal',           //显示方式
            mode: 'scroller',           //日期选择模式
//            showNow: true,
//            nowText: "现在",
            startYear: currYear,    //开始年份
//            endYear: currYear + 1, //结束年份
            minDate: new Date()    //只能选择
        },'select': {
            preset: 'select'
        }
    };
    $(document).on('click', '.refund_img', function(){
        $(this).toggleClass('imgmax');
        $(this).toggleClass('imgmin');
    });
});