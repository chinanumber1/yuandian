<!doctype html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<title>{pigcms{$now_merchant.name}的会员余额</title>
	<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/style_bai.css"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
</head>
<body>
	<div class="myde">
		<span class="left">我的余额</span><span class="myshu" id="prepay">{pigcms{$card_info['card_money']+$card_info['card_money_give']}元</span>
	</div>
	<div class="myxq">
		<p class="myxq_title">在线充值</p>
		<ul class="price-list">
			<volist name="card_info['recharge_suggest_array']" id="vo">
				<li data-price="{pigcms{$vo}"><a class="price">{pigcms{$vo}元</a></li>
			</volist>
		</ul>
		<div class="jtxx" style="width:94%;padding:0px;border:1px solid #dddddd;height:36px;line-height:36px;">
			<input class="jtxx_r_c xb left" type="number" id="otherPrice" value="" placeholder="输入其他金额" style="    width: 100%;padding-left:8px;height:36px;"/>
		</div>
		<div id="rechargeMoneyBox" class="jtxx" style="width:94%;padding:0px;border:none;height:36px;line-height:36px;margin-top:12px;display:none;">
			充值金额：<span id="rechargeMoney"></span> 元
		</div>
		<div id="giveMoneyBox" class="jtxx" style="width:94%;padding:0px;border:none;height:36px;line-height:36px;margin-top:12px;display:none;">
			赠送金额：<span id="giveMoney"></span> 元
		</div>
		<div id="givePointBox" class="jtxx" style="width:94%;padding:0px;border:none;height:36px;line-height:36px;margin-top:12px;display:none;">
			赠送{pigcms{$config['score_name']}：<span id="givePoint"></span> 分
		</div>
		<button class="btn" id="commitBtn">确定</button>
	</div>
	<div class="myxq" style="margin-top:30px;">
		<p class="myxq_title">预存说明</p>
		<div class="myxq_mx">
		{pigcms{$card_info.recharge_des|nl2br=###}
		</div>
	</div>
	<script>
		var nowMoney = 0;
		$(function(){
			$('.price-list li').click(function(){
				$(this).addClass('active').siblings('li').removeClass('active');
				nowMoney = $(this).data('price');
				$('#otherPrice').val('');
				giveCount();
			});
			$('#otherPrice').focus(function(){
				$('.price-list li').removeClass('active');
			}).keyup(function(){
				nowMoney = parseFloat($('#otherPrice').val());
				if(nowMoney > 200000){
					nowMoney = 200000;
					$('#otherPrice').val(nowMoney);
					layer.open({
						title: ['提示'],
						content: '单次充值，最高支持20万元！请分多次充值',
						time: 2
					});
				}else if(nowMoney.toString().split(".").length > 1 && nowMoney.toString().split(".")[1].length >= 3){
					nowMoney = nowMoney.toFixed(2);
					$('#otherPrice').val(nowMoney);
					layer.open({
						title: ['提示'],
						content: '小数最多精确到分'
					});
					
					return false;
				}
				giveCount();
			});
			$('#commitBtn').click(function(){
				if(nowMoney <= 0){
					layer.open({
						title: [
							'提示',
						],
						content: '充值金额必须大于0',
						time: 2
					});
					return false;
				}
				window.location.href = "{pigcms{:U('merchant_recharge',array('mer_id'=>$now_merchant['mer_id']))}&money="+nowMoney;
			});
		});
		// var conditionMoney = {pigcms{$now_merchant_card.recharge_count},giveMoney = {pigcms{$now_merchant_card.recharge_back_money},giveScore = {pigcms{$now_merchant_card.recharge_back_score};
		var recharge_rule = $.parseJSON('{pigcms{$now_merchant_card.recharge_rule}')
		function giveCount(){
			$('#rechargeMoney').html(nowMoney);
			if(isNaN(nowMoney)){
				$('#rechargeMoneyBox').hide();
				$('#giveMoneyBox').hide();
				$('#givePointBox').hide();
				return false;
			}
			$('#rechargeMoneyBox').show();
			var back_money=false;
			
			$.each(recharge_rule,function(index,val){
				if(val.count==nowMoney){
					back_money = true;
					giveUserMoney  = val.back_money;
					giveUserScore  = val.back_score;
					return false
				}
			})
			if(!back_money){
				$('#giveMoneyBox').hide();
				$('#givePointBox').hide();
				return false;
			}
			
			if(giveUserMoney > 0){
				$('#giveMoney').html(giveUserMoney);
				$('#giveMoneyBox').show();
			}
		
			if(giveUserScore > 0){
				$('#givePoint').html(giveUserScore);
				$('#givePointBox').show();
			}
		}
	</script>
</body>
</html>