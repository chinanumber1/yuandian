<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>选择优惠券</title>
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name='apple-touch-fullscreen' content='yes'>
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
		
		<style>
			 dl.list dd.dealcard {
				overflow: visible;
				-webkit-transition: -webkit-transform .2s;
				position: relative;
			}
			.dealcard.orders-del {
				-webkit-transform: translateX(1.05rem);
			}
			.dealcard-block-right {
				height: 1.68rem;
				position: relative;
			}
			.dealcard .dealcard-brand {
				margin-bottom: .18rem;
			}
			.dealcard small {
				font-size: .24rem;
				color: #666;
			}
			.dealcard weak {
				font-size: .24rem;
				color: #999;
				position: absolute;
				bottom: 0;
				left: 0;
				display: block;
				width: 100%;
			}
			.dealcard weak b {
				color: #FDB338;
			}
			.dealcard weak a.btn{
				margin: -.15rem 0;
			}
			.dealcard weak b.dark {
				color: #fa7251;
			}
			.hotel-price {
				color: #ff8c00;
				font-size: .24rem;
				display: block;
			}
			.del-btn {
				display: block;
				width: .45rem;
				height: .45rem;
				text-align: center;
				line-height: .45rem;
				position: absolute;
				left: -.85rem;
				top: 50%;
				background-color: #EC5330;
				color: #fff;
				-webkit-transform: translateY(-50%);
				border-radius: 50%;
				font-size: .4rem;
			}
			.no-order {
				color: #D4D4D4;
				text-align: center;
				margin-top: 1rem;
				margin-bottom: 2.5rem;
			}
			.icon-line {
				font-size: 2rem;
				margin-bottom: .2rem;
			}
			.orderindex li {
				display: inline-block;
				width:50%;
				text-align:center;
				position: relative;
			}
			.orderindex li .react {
				padding: .28rem 0;
			}
			.orderindex .text-icon {
				display: block;
				font-size: .4rem;
				margin-bottom: .18rem;
			}
			.orderindex .amount-icon {
				position: absolute;
				left: 50%;
				top: .16rem;
				color: white;
				background: #EC5330;
				border-radius: 50%;
				padding: .08rem .06rem;
				min-width: .28rem;
				font-size: .24rem;
				margin-left: .1rem;
				display: none;
			}
			.order-icon {
				display: inline-block;
				width: .5rem;
				height: .5rem;
				text-align: center;
				line-height: .5rem;
				border-radius: .06rem;
				color: white;
				margin-right: .25rem;
				margin-top: -.06rem;
				margin-bottom: -.06rem;
				background-color: #F5716E;
				vertical-align: initial;
				font-size: .3rem;
			}
			.order-all {
				background-color: #2bb2a3;
			}
			.order-zuo,.order-jiudian {
				background-color: #F5716E;
			}
			.order-fav {
				background-color: #0092DE;
			}
			.order-card {
				background-color: #EB2C00;
			}
			.order-lottery {
				background-color: #F5B345;
			}
			.color-gray{
				color:gray;
				border-color:gray;
			}
			.color-gray:active{
				background-color:gray;
			}
			.orderindex li .react.hover{
				color:#FF658E;
			}
		</style>
		<style>
			.address-container {
				font-size: .3rem;
				-webkit-box-flex: 1;
			}
			.kv-line h6 {
				width:auto;
			}
			.btn-wrapper {
				margin: .2rem .2rem;
				padding: 0;
			}
		
			.address-wrapper a {
				display: -webkit-box;
				display: -moz-box;
				display: -ms-flex-box;
			}
		
			.address-select {
				display: -webkit-box;
				display: -moz-box;
				display: -ms-flex-box;
				padding-right: .2rem;
				-webkit-box-align: center;
				-webkit-box-pack: center;
				-moz-box-align: center;
				-moz-box-pack: center;
				-ms-box-align: center;
				-ms-flex-pack: justify;
			}
		
			.list.active dd {
				background-color: #fff5e3;
			}
		
			.confirmlist {
				display: -webkit-box;
				display: -moz-box;
				display: -ms-flex-box;
			}
		
			.confirmlist li {
				-ms-flex: 1;
				-moz-box-flex: 1;
				-webkit-box-flex: 1;
				height: .88rem;
				line-height: .88rem;
				border-right: 1px solid #C9C3B7;
				text-align: center;
			}
		
			.confirmlist li a {
				color: #2bb2a3;
			}
		
			.confirmlist li:last-child {
				border-right: none;
			}
		</style>
	</head>
	<body id="index" data-com="pagecommon">

		<div id="tips" class="tips"></div>
		<dl class="list" style="margin-top:0rem;display:none">
		    <dd>
				<ul class="orderindex">
					<li><a href="{pigcms{:U('My/select_card',array('coupon_type'=>'system','order_id'=>$_GET['order_id'],'type'=>$_GET['type']))}" class="react <if condition="(empty($_GET['coupon_type'])) OR ($_GET['coupon_type'] eq 'system')">hover</if>">
						<i class="text-icon">⌺</i>
						<span>平台优惠券</span>
					</a>
					</li><li><a href="{pigcms{:U('My/select_card',array('coupon_type'=>'mer','order_id'=>$_GET['order_id'],'type'=>$_GET['type']))}" class="react <if condition="$_GET['coupon_type'] == 'mer'">hover</if>">
						<i class="text-icon">⌸</i>
						<span>商家优惠券</span>
					</a>
					</li>
				</ul>
			</dd>
		</dl>
		<if condition="$coupon_list AND $_GET['coupon_type'] eq 'mer'">
			<div id="tips" class="tips"></div>
				<dl class="list ">
					<dd class="address-wrapper">
						<a class="react" href="{pigcms{$unselect}">
							<div class="address-select"><input class="mt" type="radio" name="addr" ></div>
							<div class="address-container">
								<div class="kv-line">
									<h6>不使用优惠券</h6>
								</div>
							
							</div>
						</a>
					</dd>
				</dl>
			<volist name="coupon_list" id="vo">
				<dl class="list <if condition="$vo['id'] eq $_GET['merc_id']">active</if>">
					<dd class="address-wrapper">
						<a class="react" href="{pigcms{$vo.select_url}">
							<div class="address-select"><input class="mt" type="radio" name="addr" <if condition="$vo['id'] eq $_GET['merc_id']">checked="checked"</if>/></div>
							<div class="address-container">
								<div class="kv-line">
									<h6>条件：</h6><p>满{pigcms{$vo.order_money}元可用</p>
								</div>
								<div class="kv-line">
									<h6>金额：</h6><p>￥{pigcms{$vo.discount}</p>
								</div>
								<div class="kv-line">
									<h6>有效期：</h6><p>{pigcms{$vo.end_time|date='Y年m月d日',###}</p>
								</div>
							</div>
						</a>
					</dd>
				</dl>
			</volist>
		<elseif condition="coupon_list AND $_GET['coupon_type'] eq 'system' " />
			<div id="tips" class="tips"></div>
			
			<dl class="list ">
					<dd class="address-wrapper">
						<a class="react" href="{pigcms{$unselect}">
							<div class="address-select"><input class="mt" type="radio" name="addr"></div>
							<div class="address-container">
								<div class="kv-line">
									<h6>不使用优惠券</h6>
								</div>
							
							</div>
						</a>
					</dd>
				</dl>
			<volist name="coupon_list" id="vo">
				<dl class="list <if condition="$vo['id'] eq $_GET['sysc_id']">active</if>">
					<dd class="address-wrapper">
						<a class="react" href="{pigcms{$vo.select_url}">
							<div class="address-select"><input class="mt" type="radio" name="addr" <if condition="$vo['id'] eq $_GET['sysc_id']">checked="checked"</if>/></div>
							<div class="address-container">
								<div class="kv-line">
									<h6>条件：</h6><p>满{pigcms{$vo.order_money}元可用</p>
								</div>
								<div class="kv-line">
									<if condition="$vo.is_discount eq 1"><h6>折扣：</h6><p>{pigcms{$vo.discount_value|floatval}折</p><else /><h6>金额：</h6><p>￥{pigcms{$vo.discount}</p></if>
								</div>
								<div class="kv-line">
									<h6>有效期：</h6><p>{pigcms{$vo.end_time|date='Y年m月d日',###}</p>
								</div>
							</div>
						</a>
					</dd>
				</dl>
			</volist>
		<else/>
			<div id="tips" class="tips" style="display:block;">您没有可用的优惠券</div>
		</if>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			$(function(){
				$('.mj-del').click(function(){
					var now_dom = $(this);
					if(confirm('您确定要删除此地址吗？')){
						$.post(now_dom.attr('href'),function(result){
							if(result.status == '1'){
								now_dom.closest('dl').remove();
							}else{
								alert(result.info);
							}
						});
					}
					return false;
				});
				$('.address-wrapper input.mt').click(function(){
					window.location.href = $(this).closest('a').attr('href');
				});
			});
		</script>
		<include file="Public:footer"/>
{pigcms{$hideScript}
	</body>
</html>