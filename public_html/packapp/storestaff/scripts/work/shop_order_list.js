var myScroll = null;
var isSearch = false, isLoadSelect = false;
var hasMore = true;
var nowPage = 1, is_open_pick = null, deliver_type = null, is_change = null, timeOut = null;
$(document).ready(function(){
	var indexData = common.getCache('indexData',true);
    if(indexData!=null){
      $('.public .content').html(indexData.have_shop_name + '订单');
    }
    $(".mask").height($(window).height());
     var datas=new Date();
      var year = datas.getFullYear()
      var month = (datas.getMonth() + 1)>=10?(datas.getMonth() + 1):'0'+(datas.getMonth() + 1);
      var day = datas.getDate()>=10?datas.getDate():'0'+(datas.getDate());
      $(".datas").val(year+'-'+month+'-'+day)
    //修改金额
    $(document).on('click', '.modify', function(e){
    	e.stopPropagation();
    	$('#order_id').val($(this).data('id'));
    	$('#change_price, #change_price_reason').val('');
    	$('#now_price').html($(this).data('price'));
        $(".mask,.amend").show();
    });

    var changeFlag = false;
    $(document).on('click', '.button .recovery, .button .ensure', function(e){
        if (changeFlag) {
            return false;
        }
        changeFlag = true;
    	e.stopPropagation();
        var type = $(this).data('type'), order_id = $('#order_id').val(), change_price_reason = $('#change_price_reason').val(), change_price = $('#change_price').val();
        if (change_price == '') {
        	motify.log('金额不能为空');
        	return false;
        }
        common.http('Storestaff&a=shopChangePrice',{'type':type, 'order_id':order_id, 'change_price':change_price, 'change_price_reason':change_price_reason, noTip:true}, function(data){
            changeFlag = false;
        	$('.change_' + order_id).html('￥' + data + ' <em class="xgh">'+ change_price_reason + '</em>');
            $('.modify_' + order_id).data('price', data);
            $(".mask,.amend").hide();
        });
    });
    var currYear = new Date().getFullYear();
    var opt = {
        'dateYMD': {
            preset: 'date',
            dateFormat: 'yy-mm-dd',
            theme: 'android-ics light', //皮肤样式
            display: 'bottom',           //显示方式
            mode: 'scroller',           //日期选择模式
            showNow: true,
            nowText: "今天",
            onSelect: function (valueText, inst) {
            	$('.entry ul').empty();
                if($('#find_value').val() == ''){
                    isSearch = false;
                } else {
                    isSearch = true;
                }
                hasMore = true;
                nowPage = 1;
                showList();
            }
        },'select': {
            preset: 'select'
        }
    } 
    $('#stime').scroller($.extend(opt['dateYMD'],opt['default']));
    $('#etime').scroller($.extend(opt['dateYMD'],opt['default']));
    
    $('.entry ul').empty();
    showList();
    $('body,html').animate({scrollTop : 0}, 300);
    $('#searchForm').submit(function(){
    	$('.entry ul').empty();
        if($('#find_value').val() == ''){
            isSearch = false;
        } else {
            isSearch = true;
        }
        hasMore = true;
        order_id = 0;
        nowPage = 1;
        showList();
        return false;
    });
	
	$('.entry').css({"height":$(window).height()-172});
    $('.entry ul').after('<div class="jroll-infinite-tip">正在加载中...</div>');
    common.scroll($('.entry'),function(scrollIndex){
        showList(scrollIndex);
    });
    
    $(document).on('change', 'select[name=st], select[name=pay_type], select[name=order_from]', function(e){
        $('.entry ul').empty();
        if($('#find_value').val() == ''){
            isSearch = false;
        } else {
            isSearch = true;
        }
        hasMore = true;
        nowPage = 1;
        showList();
        return false;
    });
    
    // -----------stock warning------------------
    timeOut = setInterval(function(){
    	common.http('Storestaff&a=checkShopGoodsStock',{noTip:true}, function(data){
    		if (data == 1) {
    			$('.remind').show();
    		} else {
    			$('.remind').hide();
    		}
    	});
    }, 2000);

    $(document).on('click', '.remind .del', function(e){
        $(".remind").hide();
        clearInterval(timeOut);
    })

    $('.rem_see').click(function(){
    	common.http('Storestaff&a=shopGoodsStock',{noTip:true}, function(data){
    		laytpl($('#stockTpl').html()).render(data, function(html){
				$('.entry').css({"height":$(window).height()-172});
    			$('.stock').html(html);
    			$(".mask, .stock").show();
    			$('.stock').css("margin-top", -$('.stock').height()/2);
    			//new IScroll('.stock .ul',{ click: true}); 
            });
    	});
    });
	// -----------stock warning------------------
    
    //------------pick address-------------------
    $(document).on('click', '.kd_since', function(e){
    	e.stopPropagation();
    	common.http('Storestaff&a=getPickAddress', {'order_id':$(this).data('id'), noTip:true}, function(data){
    		laytpl($('#pcikTpl').html()).render(data, function(html){
    			$('.since').html(html);
    			$(".mask, .since").show();
    			$('.since').css("margin-top", -$('.since').height()/2);
    			//new IScroll('.since .ul',{ click: true});
            });
    	});
    });
    $(document).on('click', '.since li', function(e){
    	e.stopPropagation();
        $(this).addClass("on").siblings().removeClass("on");
    });
    var determineFlag = false;
    $(document).on('click', '.determine', function(e){
        if (determineFlag) {
            return false;
        }
        determineFlag = true;
    	e.stopPropagation();
    	var pick_id = $('.since').find('.on').data('id'), order_id = $(this).data('id');
    	if (pick_id == undefined) {
    		motify.log('请选择自提点');
    		return false;
    	}
    	common.http('Storestaff&a=pick', {'order_id':$(this).data('id'), 'pick_id':pick_id, noTip:true}, function(data){
    	    determineFlag = false;
    		if (data == 'SUCCESS') {
    			$('.since_' + order_id).removeClass('kd_since').addClass('kd_hair send').html('发货到自提');
    		}
    		$('.mask, .since').hide();
    	});
    });
    
    //--------------------confirm order-----------------------
    var confirmFlag = false;
    $(document).on('click', '.confirm', function(e){
        if (confirmFlag) {
            return false;
        }
        confirmFlag = true;
    	e.stopPropagation();
        var order_id = $(this).data('id'), _this = $(this);
        common.http('Storestaff&a=shopOrderEdit',{'status':1, 'order_id':order_id, noTip:true}, function(data){
            confirmFlag = false;
        	_this.removeClass('kd_rob confirm').addClass('kd_order').html('已接单');
        });
    });
    
    //--------------------send to pick-----------------------------
    var sendFlag = false;
    $(document).on('click', '.send', function(e){
        if (sendFlag) {
            return false;
        }
        sendFlag = true;
    	e.stopPropagation();
        var order_id = $(this).data('id'), _this = $(this);
        common.http('Storestaff&a=shopOrderEdit',{'status':8, 'order_id':order_id, noTip:true}, function(data){
            sendFlag = false;
            _this.removeClass('kd_rob send').addClass('kd_hair').html('已发货到自提');
        });
    });
    
    //-----------------hide popup-----------------------
    $(document).on('click', '.mask, .del', function(e){
    	e.stopPropagation();
    	$('.mask, .since, .stock, .amend, .flat').hide();
    });
    
    $(".sus_popup").click(function(e){
        event.stopPropagation();
        $(".sus_url").is(":hidden") ? $(".sus_url").show() : $(".sus_url").hide();
    });
    $(".sus_url a,html").click(function(){
        $('.sus_url').hide();
    });
	//快递配送时候
    $(document).on('click', '.express', function(e){
    	e.stopPropagation();
        common.http('Storestaff&a=getExpress',{'order_id':$(this).data('id'), noTip:true}, function(data){
        	laytpl($('#expressTpl').html()).render(data, function(html){
    			$('.flat').html(html);
    		});
        	$(".flat,.mask").show();
        	$('.flat').css({"top":($(window).height()-$(".flat").height())/2});
        });
    });
    var express_save = false;
    $(document).on('click', '.express_save', function(){
        if (express_save) {
            return false;
        }
        express_save = true;
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
            express_save = false;
        	location.reload();
        });
    });
});



function showList(scrollIndex){
    if(hasMore == false){
    	return false;
    }
    var st = $('select[name=st]').val(), pay_type = $('select[name=pay_type]').val(), order_from = $('select[name=order_from]').val(), ft = $('select[name=ft]').val();
    var stime = $('#stime').val(), etime = $('#etime').val()
    if(isSearch == false){
        if (!isLoadSelect) {
            st = -2;
        }
        common.http('Storestaff&a=shopList',{'page':nowPage, 'st':st, 'stime':stime, 'etime':etime, 'pay_type':pay_type, 'order_from':order_from, noTip:true}, function(data){
        	if (!isLoadSelect) {
        		is_open_pick = data.is_open_pick, 
        		deliver_type = data.deliver_type;
        		is_change = data.is_change;
        		var html = '<select name="order_from"><option value="-2">订单来源</option>';
        		for (var i in data.order_from) {
        			html += '<option value="' + i + '">' + data.order_from[i] + '</option>';
        		}
        		html += '</select>';
        		$('.order_from').html(html);
        		var html = '<select name="pay_type"><option value="-2">支付方式</option>';
        		for (var i in data.pay_type) {
        			html += '<option value="' + i + '">' + data.pay_type[i] + '</option>';
        		}
        		html += '</select>';
        		$('.pay_type').html(html);
        		var html = '<select name="st"><option value="-2">订单状态</option>';
        		for (var i in data.status_list) {
        			if (i == -1) continue;
        			if (i == 0) {
                        html += '<option value="' + i + '">' + data.status_list[i] + '</option>';
                    } else {
                        html += '<option value="' + i + '">' + data.status_list[i] + '</option>';
                    }
        		}
        		html += '</select>';
        		$('.status').html(html);
        		isLoadSelect = true;
				
				if($('.mask').height() == 0 || $('.entry').height() <= 0){
					$(".mask").height($(window).height());
					$('.entry').css({"height":$(window).height()-172});
				}
        	}
        	nowPage++;
            if(nowPage > data.page){
                hasMore = false;
                $('.jroll-infinite-tip').addClass('hideText');
            }
            if (data.shop_order.length != 0) {
	            laytpl($('#listTpl').html()).render(data.shop_order, function(html){
	                $('.entry ul').append(html);
	                common.scrollEnd(scrollIndex);
	            });
            } else {
            	$('.entry ul').append('<div class="jroll-infinite-tip">暂无结果</div>');
            }
            
        });
    }else{
    	var fv = $('#find_value').val();
        common.http('Storestaff&a=shopList',{'page':nowPage, 'ft':ft, 'fv':fv, 'st':st, noTip:true}, function(data){
        	nowPage++;
            if(nowPage > data.page){
                hasMore = false;
                $('.jroll-infinite-tip').addClass('hideText');
            }
            if (data.shop_order.length != 0) {
	            laytpl($('#listTpl').html()).render(data.shop_order, function(html){
	                $('.entry ul').append(html);
	                common.scrollEnd(scrollIndex);
	            });
            } else {
            	$('.entry ul').append('<div class="jroll-infinite-tip">暂无结果</div>');
            }
        });
    }
}

function pageShowFunc(){
    var order_id = common.getCache('order_id',true);
	if(order_id && order_id != '0'){
		common.http('Storestaff&a=shopDetail', {'order_id':order_id}, function(data){
			console.log(data);
			laytpl($('#detailTpl').html()).render(data.order_details, function(html){
				$('#order_' + order_id).html(html);
			});
		});
	}
}