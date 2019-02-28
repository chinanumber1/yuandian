<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$store['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/css_whir.css"/>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body>
	<section class="Success"><span><if condition="$order['status'] eq 5">已取消！<elseif condition="$order['status'] gt 0"/>订座成功！<else />提交成功！</if></span></section>
	<section class="Sudetails">
		<ul>
			<li class="Su_top">
      	    	<a href="{pigcms{:U('Foodshop/shop', array('store_id' => $order['store_id']))}">{pigcms{$store['name']}</a>
			</li>
			<li class="Su_zh">
				<dl>
					<dd>{pigcms{$order['book_time_show']}</dd>
					<dd>{pigcms{$order['book_num']}人 | {pigcms{$order['table_type_name']}  <span class="Su_sit"><if condition="$order['status'] eq 5">已取消<elseif condition="$order['status'] gt 0"/>已付定金:￥{pigcms{$order['book_price']|floatval}<else />未付定金</if></span></dd>
					<dd>{pigcms{$order['name']} <if condition="$order['sex'] eq 1">先生<else />女士</if> {pigcms{$order['phone']}</dd>
					<dd>【{pigcms{$now_merchant.name}】</dd>
				</dl>
			</li>
			<if condition="$order['note']">
			<li class="Su_bots">{pigcms{$order['note']}</li>
			</if>
			<if condition="$order['cancel_reason']">
			<li class="Su_bots">取消理由：{pigcms{$order['cancel_reason']}</li>
			</if>
		</ul>
	</section>
	<if condition="$order['status'] lt 3">
	<section class="SuOrde">
		<div class="SuOrde_top">如您不能准时到达餐厅，请在您预约时间的<b>{pigcms{$store['cancel_time']}</b>分钟前取消并可退定金，否则不得取消。</div>
		<div class="Sufrom">
			<if condition="$config.open_sub_mchid AND $now_merchant.open_sub_mchid AND $now_merchant.sub_mch_id gt 0 AND $now_merchant.sub_mch_refund eq 0 AND $order.is_own eq 2  AND $order['pay_type'] eq 'weixin' ">
			<a href="tel:{pigcms{$now_merchant.phone}"  style="font-size:12px;margin-right: 3%;border: #e4e4e4 1px solid;">该订单不能退款，请联系商家</a><else /><a href="javascript:void(0);" class="cancel">取消预约</a></if>
			<if condition="$order['status'] gt 0">
			<a href="{pigcms{:U('Foodshop/menu', array('store_id' => $order['store_id'], 'order_id' => $order['real_orderid']))}" class="complete" style="float:right">去订餐</a>
			<else />
			<a href="{pigcms{:U('Foodshop/pay', array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))}" class="complete">去付定金</a>
			</if>
		</div>
	</section>
	</if>
	<section class="Tcancel">
		<div class="Tcancel_top">请选择取消预订原因</div>
		<div class="Reason">
			<ul>
				<li><i></i><span>行程取消</span></li>
				<li><i></i><span>改去其他餐厅</span></li>
				<li><i></i><span>预订信息填写错误</span></li>
				<li><i></i><span>行程有变</span></li>
			</ul>
		</div>
		<div class="Sufrom_n">
			<a href="javascript:void(0);" class="cancel">放弃取消</a>
			<a href="javascript:void(0);" class="complete">确认取消</a>
		</div>
</section>
<div class="Mask"></div>
</body>
<script type="text/javascript">
var motify = {
	timer:null,
	/*shade 为 object调用 show为true显示 opcity 透明度*/
	log:function(msg,time,shade){
		$('.motifyShade,.motify').hide();
		if(motify.timer) clearTimeout(motify.timer);
		if($('.motify').size() > 0){
			$('.motify').show().find('.motify-inner').html(msg);
		}else{
			$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
		}
		if(shade && shade.show){
			if($('.motifyShade').size() > 0){
				$('.motifyShade').css({'background-color':'rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+')'}).show();
			}else{
				$('body').append('<div class="motifyShade" style="display:block;background-color:rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+');"></div>');
			}
		}
		if(typeof(time) == 'undefined'){
			time = 3000;
		}
		if(time != 0){
			motify.timer = setTimeout(function(){
				$('.motify').hide();
			},time);
		}
	},
	clearLog:function(){
		$('.motifyShade,.motify').hide();
	}
};
//背景单窗高度  
$(".Mask").css("height", $(document).height());
//弹窗
$(".Sufrom .cancel").click(function(){
	$(".Tcancel").show();
	$(".Mask").show();
});
$(".Sufrom_n .cancel").click(function(){
	$(".Tcancel").hide();
	$(".Mask").hide();
});

var cancel_order = false;
$(".Sufrom_n .complete").click(function(){
	if (cancel_order) return false;
	cancel_order = true;

	var cancel_reason = '', pre = '';
	$('.Reason .on').each(function(){
		cancel_reason += pre + $(this).find('span').text();
		pre = ',';
	});

	if (cancel_reason.length < 1) {
		cancel_order = false;
		motify.log('请选择您的取消理由');
		return false;
	}
	$.post('{pigcms{:U("Foodshop/cancel_book", array("order_id" => $order["order_id"]))}', {'cancel_reason':cancel_reason}, function(response){
		cancel_order = false;
		if (response.err_code) {
			motify.log(response.msg);
		} else {
			location.href=response.url;
		}
	}, 'json');
	
	//alert("已取消")
});
$(".Reason li").click(function(){
	if (!$(this).hasClass("on")) {
		$(".Reason li").removeClass("on");
		$(this).addClass("on");
	}else{
		$(this).removeClass("on");
	}
	$(".Reason li").each(function(){
		if ($(this).hasClass("on") || $(this).siblings().hasClass("on")) {
			$(".Sufrom_n .complete").css("background", "#f03c3c");        
		} else if (!$(this).hasClass("on") && !$(this).siblings().hasClass("on")) { 
			$(".Sufrom_n .complete").css("background", "#c8c8c8");
		}
	});
});
</script>
</html>