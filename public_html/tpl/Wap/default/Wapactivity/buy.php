<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>清单</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<style>
	    #buy dd {
	        font-size: .3rem;
	    }
	    #change_address .more:after {
	        top: .2rem;
	    }
	    #change_address h6 {
	        width: 3em;
	    }
	    h4 small {
	        color: #999;
	        display: inline-block;
	        padding-left: .2rem;
	    }
	    .good-name {
	        color: #666;
	    }
	
	    .good-left-count {
	        color: #2bb2a3;
	    }
	
	    .good-left-out {
	        color: #999;
	    }
	    .quantity.kv-line {
	        -webkit-box-align: center;
	    }
	
	    .campaign_tag {
	        position: static;
	        background: #ff8c00;
	        color: #fff;
	        line-height: 1.5;
	        display: inline-block;
	        padding: 0 .06rem;
	        text-align: center;
	        font-size: .24rem;
	        border-radius: .06rem;
	        vertical-align: text-bottom;
	    }
	
	    .amount>span {
	        display: block;
	    }
	
	    .J_campaign-value {
	        font-size: .24rem;
	        color: #999;
	    }
	
	    .J_total-price {
	        font-weight: bold;
	        color: #FF9712;
	    }
	
	    .kv-line-r .btn, .kv-line-r .mt, .kv-line-r .input-weak {
	        margin-top: -.15rem;
	        margin-bottom: -.15rem;
	    }
	    .kv-line-r .kv-k {
	        display: block;
	    }
	    .kv-line .btn, .kv-line .mt, .kv-line .input-weak {
	        margin: -.15rem 0;
	    }
	
	    /*agreement*/
	    .agreement {
	        padding: .2rem;
	    }
	
	    .agreement li {
	        display: inline-block;
	        text-align: center;
	        width: 50%;
	        box-sizing: border-box;
	        color: #666;
	    }
	
	    .agreement li:nth-child(2n) {
	        padding-left: .14rem;
	    }
	
	    .agreement li:nth-child(1n) {
	        padding-right: .14rem;
	    }
	
	    .agreement li.active {
	        color: #6bbd00;
	    }
	
	    .agreement ul.btn-line li {
	        vertical-align: middle;
	        margin-top: .06rem;
	        margin-bottom: 0;
	    }
	
	    .agreement .text-icon {
	        margin-right: .14rem;
	        vertical-align: top;
	        height: 100%;
	    }
	
	    .agreement .agree .text-icon {
	        font-size: .4rem;
	        margin-right: .2rem;
	    }
	
	    label.disabled {
	        color: #ccc;
	    }
	    #birthday_wrap label.select {
	        width: 28%;
	        display: inline-block;
	        margin-right: .16rem;
	    }
	    #birthday_wrap .select select {
	        border: 1px solid #ccc;
	    }
	    #sms-captcha {
	        width: 100%;
	    }
	</style>
</head>
<body>
	<div id="tips" class="tips"></div>
	<form id="buy-form" method="POST" class="wrapper-list" autocomplete="off">
		<h4 style="margin-top:.4rem">{pigcms{$now_activity.name}</h4>
		<dl class="list">
			<dd>
				<dl>
					<dd class="dd-padding kv-line-r">
						<h6>^剩余：</h6>
						<p>{pigcms{$now_activity['all_count'] - $now_activity['part_count']}次</p>
					</dd>
					<dd class="dd-padding kv-line-r quantity">
						<h6>数量：</h6>
						<div class="kv-v">
							<div class="stepper" data-com="stepper">
								<button type="button" class="btn btn-weak minus" disabled="disabled">-</button>&nbsp;<input class="mt number" type="tel" name="quantity" min="1" max="{pigcms{$now_activity['all_count'] - $now_activity['part_count']}" value="1"/>&nbsp;<button type="button" class="btn btn-weak plus">+</button>
							</div>
						</div>
					</dd>
					<dd class="dd-padding kv-line-r">
						<h6>总价：</h6>
						<span class="kv-v" id="amount">
							<span class="J_total-price">1元</span>
							<span class="J_campaign-value"></span>
						</span>
					</dd>
				</dl>
			</dd>
		</dl>
		<div class="btn-wrapper">
			<button type="submit" class="btn btn-block btn-strong btn-larger mj-submit" style="display:none;">提交</button>
		</div>
	</form>
	<form id="recharge-form" method="POST" action="{pigcms{:U('My/recharge')}" style="display:none;">
		<input id="recharge-money" name="money"/>
		<input id="label" name="label"/>
		<input type="submit" value="提交">
	</form>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_path}js/common_wap.js"></script>	
	<script src="{pigcms{$static_public}js/fastclick.js"></script>
	<script>
		$(function(){
			FastClick.attach(document.body);
			var price = 100;
			var quantity = $("input[name='quantity']");
			$('button.plus').click(function(){
				$('#tips').removeClass('tips-err').empty();
				var pigcms_now_quantity = parseInt(quantity.val());
				var max = parseInt(quantity.attr('max'));
				if(max > 200){max = 200;}
				if(!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(pigcms_now_quantity)){
					$('#tips').addClass('tips-err').html('请输入正确的购买数量');
				}else if(pigcms_now_quantity + 1 > max && max != '0'){
					$('#tips').addClass('tips-err').html('您最多能购买'+max+'单');
					quantity.val(max);
					$(this).prop('disabled',true);
				}else{
					quantity.val(pigcms_now_quantity+1);
					if(pigcms_now_quantity == max -1){
						$(this).prop('disabled',true);
					}
					$('.J_total-price').html(price*(pigcms_now_quantity+1)/100+'元');
					$('button.minus').prop('disabled',false);
				}
			});
			$('button.minus').click(function(){
				$('#tips').removeClass('tips-err').empty();
				var pigcms_now_quantity = parseInt(quantity.val());
				if(!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(pigcms_now_quantity)){
					$('#tips').addClass('tips-err').html('请输入正确的购买数量');
				}else if(pigcms_now_quantity - 1 < quantity.attr('min')){
					$('#tips').addClass('tips-err').html('您最少能购买'+quantity.attr('min')+'单');
				}else{
					if(pigcms_now_quantity-1 <= quantity.attr('min')){
						$(this).prop('disabled',true);
					}
					quantity.val(pigcms_now_quantity-1);
					$('.J_total-price').html(price*(pigcms_now_quantity-1)/100+'元');
					$('button.plus').prop('disabled',false);
				}
			});
			quantity.blur(function(){
				$('#tips').removeClass('tips-err').empty();
				var pigcms_now_quantity = parseInt(quantity.val());
				var max = parseInt(quantity.attr('max'));
				if(max > 200){max = 200;}
				if(!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(pigcms_now_quantity)){
					$('#tips').addClass('tips-err').html('请输入正确的购买数量');
				}else{
					if(max != 0 && pigcms_now_quantity == max){
						$('button.plus').prop('disabled',true);
					}else if(max != 0 && pigcms_now_quantity > max){
						$('#tips').addClass('tips-err').html('您最多能购买'+max+'单');
						$('button.plus').prop('disabled',true);
						quantity.val(max);
					}else{
					
						$('button.plus').prop('disabled',false);
					}
					if(pigcms_now_quantity == quantity.attr('min')){
						$('button.minus').prop('disabled',true);
					}else if(pigcms_now_quantity < quantity.attr('min')){
						$('#tips').addClass('tips-err').html('您最少能购买'+quantity.attr('min')+'单');
						$('button.minus').prop('disabled',true);
						quantity.val(quantity.attr('min'));
					}else{
						$('button.minus').prop('disabled',false);
					}

					$('.J_total-price').html(price*(parseInt(quantity.val()))/100+'元');
				}
			});
			var submit_now = false;
			$('#buy-form').submit(function(){
				//检测购买的值
				var now = parseInt(quantity.val());
				quantity.val(now);
				var max = parseInt(quantity.attr('max'));
				if(now < 1){
					quantity.val('1');
					$('#error_num_tips').html('最少需要参与1次');
					return false;
				}else if(now > 200){
					quantity.val(200);
					$('#error_num_tips').html('一次性最多参加200次，请分批次参与');
					return false;
				}else if(now > max){
					quantity.val(max);
					$('#error_num_tips').html('最多只能参与 '+max+' 次');
					return false;
				}else{
					$('#error_num_tips').empty();
				}
				if(submit_now == false){
					submit_now = true;
					$('.mj-submit').html('提交中...').prop('disabled',true);
					$.post("{pigcms{$config.site_url}/index.php?c=Activity&a=submit&id={pigcms{$now_activity.pigcms_id}",{q:quantity.val()},function(result){
						submit_now = false;
						$('.mj-submit').html('提交').prop('disabled',false);
						if(result.status == -3){
							alert(result.info);
							window.location.href = "{pigcms{:U('Redirect/need_login',array('referer'=>urlencode(U('Wapactivity/buy',array('id'=>$now_activity['pigcms_id'])))))}";
						}else if(result.status == -4){
							layer.open({
								content: result.info,
								btn: ['充值', '取消'],
								shadeClose: false,
								yes: function(){
									$('#recharge-money').val(result.recharge);
									$('#label').val('wap_activity_'+{pigcms{$now_activity.pigcms_id}+'_'+quantity.val());
									$('#recharge-form').trigger('submit');
								}
							});
						}else if(result.status == -5){
							layer.open({
								content: result.info,
								btn: ['确定'],
								shadeClose: false,
								yes: function(){
									window.location.href = "{pigcms{:U('My/edit_adress',array('referer'=>urlencode(U('Wapactivity/buy',array('id'=>$now_activity['pigcms_id'])))))}";
								}
							});
						}else{
							layer.open({
								content: result.info,
								btn: ['确定'],
								shadeClose: false
							});
						}
						if(result.status == 1){
							window.location.href = "{pigcms{:U('Wapactivity/detail',array('id'=>$now_activity['pigcms_id']))}";
						}else{
							if(result.status == -2){
								window.location.reload();
							}else if(result.status == -1){
								$('#J-quantity').attr('max',result.count);
							}
						}
					});
				}
				return false;
			});
		});
	</script>	
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
	<script>var showBuyBtn = true;</script>
	<script>if(showBuyBtn){$('button.mj-submit').show();}</script>
	<include file="Public:footer"/>
	{pigcms{$hideScript}
</body>
</html>