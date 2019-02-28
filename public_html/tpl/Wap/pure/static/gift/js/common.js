/**
 * Created by tanytree on 2016/07/2.
 */
document.body.addEventListener('touchstart', function () { });
window.onload=function(){
    window.setTimeout(function(){
        $(".lodingCover").remove();
    },600)
}

$(function(){
	//滚动筛选
    $(".scrollNav ul li").tap(function(){
        $(".scrollNav ul li").removeClass("on");
       $(this).addClass("on");
	   
	   var cat_fid = $(this).attr('data-cat-fid');
	   get_gift_list(cat_fid,score_name)
    });
	
	$('.showMore').click(function(){
		var cat_fid = $(".scrollNav ul li.on").attr('data-cat-fid');
		location.href = gift_list_url + '&cat_id='+cat_fid;
	});
	
	$('.plus').click(function(){
		var now_sku = parseInt($('#now_sku').val());
		
		sku_limit(now_sku , total_sku , '+')
	});
	
	$('.reduce').click(function(){
		var now_sku = parseInt($('#now_sku').val());
		
		sku_limit(now_sku , total_sku , '-')
	});
	
	$('#now_sku').keyup(function(){
		var now_sku = parseInt($(this).val());

		if(!now_sku){
			$(this).val(1);
		}
		
		if(now_sku >= total_sku){
			$(this).val(total_sku);
			now_sku = total_sku;
		}
		
		if(now_sku <= 1){
			$(this).val(1);
		}
		
		if(exchange_type == 0){
		var total_sku_price =  now_sku * payment_pure_integral;
			$('.payment_pure_integral').html(total_sku_price);
		}else if(exchange_type == 1){
			var total_payment_integral =  now_sku * payment_integral;
			var total_payment_money = now_sku * payment_money;
			$('.payment_integral').html(total_payment_integral);
			$('.payment_money').html(total_payment_money);
		}
	});
	
	
	
function sku_limit(now_sku , total_sku , operate){
	if(operate == '+'){
		if(now_sku >= total_sku){
			return;
		}
		
		now_sku++;
	}else if(operate == '-'){
		if(now_sku <= 1){
			return;
		}
		now_sku--;
	}
	$('#now_sku').val(now_sku);
	if(exchange_type == 0){
		var total_sku_price =  now_sku * payment_pure_integral;
		$('.payment_pure_integral').html(total_sku_price);
	}else if(exchange_type == 1){
		var total_payment_integral =  now_sku * payment_integral;
		var total_payment_money = now_sku * payment_money;
		$('.payment_integral').html(total_payment_integral);
		$('.payment_money').html(total_payment_money);
	}
	
}
});


function get_gift_list(cat_fid,score_name){
	console.log(score_name);
	$.post(ajax_gift_list_url,{'cat_fid':cat_fid},function(data){
	   if(data['status']){
		   var gift_list = data['gift_list']['list'];
		   var shtml = '';
		   for(var i in gift_list){
			   var gift_url = gift_detail_url+'&gift_id='+gift_list[i]['gift_id']
			   shtml += '<div class="col-50 item item1" onclick="location.href=\''+gift_url+'\'"><div class="wrap"><div class="i-pic"><img src="' + site_gift_url + gift_list[i]['wap_pic_list'][0] + '"></div><h2>'+gift_list[i]['gift_name']+'</h2>';
			   
			   if((gift_list[i]['exchange_type'] == 0) || (gift_list[i]['exchange_type'] == 2)){
				   shtml += '<p>'+ gift_list[i]['payment_pure_integral'] +' <em>'+score_name+'</em></p>';
			   }else{
				   shtml +='<p>'+ gift_list[i]['payment_integral'] +' <em>'+score_name+'</em> + '+ gift_list[i]['payment_money'] +' <em>元</em></p>';
			   }
			   shtml +='<a href="javascript:void(0)" class="aButton">马上兑换</a></div></div>';
		   }
		   
		   
		   var thisUrl = gift_list_url + "&cat_id="+cat_fid;
		   shtml +='<div class="showMore"><a href="'+thisUrl+'">查看更多</a></div>';
		   
		   $('#gift_list').empty().html(shtml);
	   }else{
		   var shtml='<p style="text-align:center">暂无礼品</p>'
		   $('#gift_list').empty().html(shtml);
	   }
   },'json')
}

