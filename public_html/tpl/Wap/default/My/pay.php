<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{$now_store['name']}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/pay.css" rel="stylesheet"/>
</head>
<body onselectstart="return true;" ondragstart="return false;">
        <div id="tips" class="tips"></div>
        <form id="form" method="post" action="{pigcms{:U('My/store_order', array('store_id' => $now_store['store_id']))}">
        
		    <dl class="list list-in">
		    	<dd>
		    		<dl>
		        		<dd class="dd-padding kv-line">
		        			<h6>消费总额:</h6>
		        			<input name="total_money" type="text" class="kv-v input-weak" placeholder="询问服务员后输入" value="">
		        		</dd>
		        		<if condition="$discount_type">
							<span style="margin-left: 10px;color: gray;font-size: 12px;">输入不参与优惠金额(酒水、特价菜等)</span>
							<dd class="dd-padding kv-line">
								<h6>不可优惠金额:</h6>
								<input name="no_discount_money" type="number" class="kv-v input-weak" placeholder="询问服务员后输入" value=""/>
							</dd>
						</if>
			    	</dl>
		   		</dd>
			</dl>
			<if condition="$discount_type eq 1 OR $discount_type eq 2">
		    <dl class="list list-in">
		    	<dd>
		    		<dl>
		        		<dd class="dd-padding1 kv-line">
		        			<h6 style="margin-left: 10px;color:#FF658E"><if condition="$discount_type eq 1"><php>if($config['open_extra_price']==1){ echo "最高优惠".((10-$discount_percent)*10)."%";}else{</php>{pigcms{$discount_percent}折<php>}</php><elseif condition="$discount_type eq 2" />每满{pigcms{$condition_price}减{pigcms{$minus_price}元</if></h6>
							<div id="show_minus" class="kv-v input-weak" style="margin-right: 16px"></div>
		        		</dd>
						
			    	</dl>
		   		</dd>
			</dl>
			</if>
		    <div class="btn-wrapper">
		    	<input name="total_price" id="total_price" type="hidden" value="0" />
		    	<input name="extra_price" id="extra_price" type="hidden" value="0" />
		    	<input name="minus_price" id="minus_price" type="hidden" value="0" />
		    	<input name="price" id="price" type="hidden" value="0" />
				<button type="submit" class="btn btn-block btn-larger"><if condition="$config.open_extra_price eq 1">下一步<else />确认支付</if></button>
		    </div>
		</form>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script>
			$(function(){
				var total_price = 0, minus_price = 0, price = 0;
				var open_extra_price  = Number('{pigcms{$config.open_extra_price}');
				var extra_price_name  = '{pigcms{$config.extra_price_alias_name}';
				var extra_percent  = Number('{pigcms{$config.user_score_use_percent}');
				var condition_price = '{pigcms{$condition_price}', minus_money = '{pigcms{$minus_price}', discount_percent = '{pigcms{$discount_percent}', discount_type = '{pigcms{$discount_type}';
				$('input[name=no_discount_money]').keyup(function(){
					var no_discount_money = eval($(this).val()), total_money = eval($('input[name=total_money]').val());
					if (total_money > no_discount_money) {
						if (discount_type == 1) {
							price = eval(total_money - no_discount_money) * eval(discount_percent) * 10 / 100 + no_discount_money;
							minus_price = eval(total_money - no_discount_money) * (100 - discount_percent * 10) / 100;
						} else if (discount_type == 2) {
							minus_price = Math.floor(eval(total_money - no_discount_money) / eval(condition_price)) * minus_money;
							price = total_money - minus_price;
						} else {
							minus_price = 0;
							price = total_money;
						}
						if(open_extra_price==1){							
							$('#extra_price').val((minus_price*extra_percent).toFixed(2)+extra_price_name);
							$('#after_discount_money').html(price+'+'+(minus_price*extra_percent).toFixed(2)+extra_price_name);
						}
						$('#total_price').val(total_money);
						$('#show_minus').text('-￥' + minus_price.toFixed(2));
						$('#price').val(price);
						$('#minus_price').val(minus_price);
					} else {
						$('input[name=no_discount_money]').val('');
					}
				});
				$('input[name=total_money]').keyup(function(){
					var total_money = eval($(this).val()), no_discount_money = eval($('input[name=no_discount_money]').val()) == undefined ? 0 : eval($('input[name=no_discount_money]').val());
					if (total_money > no_discount_money) {
						if (discount_type == 1) {
							price = eval(total_money - no_discount_money) * eval(discount_percent) * 10 / 100 + no_discount_money;
							minus_price = eval(total_money - no_discount_money) * (100 - discount_percent * 10) / 100;
						} else if (discount_type == 2) {
							minus_price = Math.floor(eval(total_money - no_discount_money) / eval(condition_price)) * minus_money;
							price = total_money - minus_price;
						} else {
							minus_price = 0;
							price = total_money;
						}
						if(open_extra_price==1){							
							$('#extra_price').val((minus_price*extra_percent).toFixed(2));
							$('#after_discount_money').html(price+'+'+(minus_price*extra_percent).toFixed(2)+extra_price_name);
						}
						$('#show_minus').text('-￥' + minus_price.toFixed(2));
						$('#total_price').val(total_money);
						$('#price').val(price);
						$('#minus_price').val(minus_price);
					} else {
						$('input[name=no_discount_money]').val('');
					}
				});
				
				$('#form').submit(function(){
					layer.open({type:2,content:'提交中，请稍候'});
					$.post($('#form').attr('action'),$('#form').serialize(),function(result){
						layer.closeAll();
						if(result.status == 1){
							window.location.href = result.url;
						}else{
							$('#tips').addClass('tips-err').html(result.info);
						}
					});
					return false;
				});
			});
		</script>
<include file="Public:footer"/>
</body>
</html>