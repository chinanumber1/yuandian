<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>店员中心</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="{pigcms{$static_path}css/diancai.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/datePicker.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll_min.css" media="all">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min1.8.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll_min.js"></script>
<style>
.green{color:green;}
.btn{
margin: 0;
text-align: center;
height: 2.2rem;
line-height: 2.2rem;
padding: 0 .32rem;
border-radius: .3rem;
color: #fff;
border: 0;
background-color: #FF658E;
font-size: .28rem;
vertical-align: middle;
box-sizing: border-box;
cursor: pointer;
-webkit-user-select: none;}
.totel{color: green;}
.cpbiaoge td{font-size:1rem;}
.dropdown_select {
    -webkit-appearance: button;
    -webkit-user-select: none;
    font-size: 13px;
    overflow: visible;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #999999;
    display: inline;
    position: relative;
    margin: 0px 1px 0px 1px;
    font-size: 16px;
    height: auto;
    padding: 10px;
    outline: none;
    border: 0;
    background-color: transparent;
}
.px {
    position: relative;
    background-color: transparent;
    color: #999999;
    padding: 10px;
    font-size: 16px;
    margin: 0 auto;
    font-family: Arial, Helvetica, sans-serif;
    border: 0;
    -webkit-appearance: none;
}
</style>
</head>
<body>

<div style="padding: 0.2rem;"> 
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
				<tr>
					<td>客户姓名：{pigcms{$order.username}</td>
				</tr>
				<tr>
					<td>客户电话：<a href="tel:{pigcms{$order.userphone}" class="totel">{pigcms{$order.userphone}</a></td>
				</tr>
				<tr>
					<td>订单编号：{pigcms{$order.real_orderid}</td>
				</tr>
				<if condition="$order.orderid neq 0">
				<tr>
					<td>订单流水号：{pigcms{$order.orderid}</td>
				</tr>
				</if>
				<tr>
					<td>下单时间： {pigcms{$order.create_time|date="Y-m-d H:i:s",###}</td>
				</tr>
				<tr>
					<td>支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </td>
				</tr>
				<if condition="$order['expect_use_time']">
				<tr>
					<td>到货时间：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</td>
				</tr>
				</if>
				<if condition="$order['is_pick_in_store'] eq 2">
				<tr>
					<td>自提地址：{pigcms{$order['address']}</td>
				</tr>
				<else />
				<tr>
					<td>客户地址：{pigcms{$order['address']}</td>
				</tr>
				</if>
				<tr>
					<td>配送方式：{pigcms{$order['deliver_str']}</td>
				</tr>
				<tr>
					<td>配送状态：{pigcms{$order['deliver_status_str']}</td>
				</tr>
				<tr>
					<td>客户留言： {pigcms{$order.desc|default='无'}</td>
				</tr>
				
				<if condition="$order['invoice_head']">
				<tr>
					<td>发票抬头:{pigcms{$order['invoice_head']}</td>
				</tr>
				</if>
				<tr>
				  <td>支付状态：{pigcms{$order['pay_status']}</td>
				</tr>
				<tr>
				  <td>支付方式： {pigcms{$order.pay_type_str}</td>
				</tr>
				<tr>
					<td>订单状态：{pigcms{$order['status_str']}</td>
				</tr>
				<if condition="$order['score_used_count']">
				<tr>
					<td>使用{pigcms{$config['score_name']}：{pigcms{$order['score_used_count']} </td>
				</tr>
				<tr>
					<td>{pigcms{$config['score_name']}抵现：￥{pigcms{$order['score_deducte']|floatval} 元</td>
				</tr>
				</if>
				
				<if condition="$order['merchant_balance'] gt 0">
				<tr>
					<td>商家余额：￥{pigcms{$order['merchant_balance']|floatval} 元</td>
				</tr>
				</if>
				<if condition="$order['balance_pay'] gt 0">
				<tr>
					<td>平台余额：￥{pigcms{$order['balance_pay']|floatval} 元</td>
				</tr>
				</if>
				<if condition="$order['payment_money'] gt 0">
				<tr>
					<td>在线支付：￥{pigcms{$order['payment_money']|floatval} 元</td>
				</tr>
				</if>
				<if condition="$order['card_id']">
				<tr>
					<td>店铺优惠券金额：￥{pigcms{$order['card_price']} 元</td>
				</tr>
				</if>
				<if condition="$order['coupon_id']">
				<tr>
					<td>平台优惠券金额：￥{pigcms{$order['coupon_price']} 元</td>
				</tr>
				</if>
				<if condition="$order['card_give_money'] gt 0">
				<tr>
					<td>会员卡余额：￥{pigcms{$order['card_give_money']|floatval} 元</td>
				</tr>
				</if>
				<if condition="$order['card_discount'] neq 0 AND $order['card_discount'] neq 10">
				<tr>
					<td>会员卡：{pigcms{$order['card_discount']|floatval} 折优惠</td>
				</tr>
				</if>
				<tr>
					<td>应收现金：￥{pigcms{$order['offline_price']|floatval}元</td>
				</tr>
				<if condition="!empty($order['use_time'])">		
					<tr>
						<td>操作店员：<span class="totel">{pigcms{$order.last_staff}</span> </td>
					</tr>
					<tr>
						<td>操作时间： {pigcms{$order.use_time|date='Y-m-d H:i:s',###}</td>
					</tr>
				</if>
				<form enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/check_deliver')}">
				<input name="order_id" value="{pigcms{$order['order_id']}" type="hidden">
				<tr>
					<th ><strong>修改配送方式</strong> <b style="color:red">（修改后的配送费超出部分由商家自出）</b></th>
				</tr>
				<tr>
					<td>快速配送收取的配送费:<b style="color: red">￥{pigcms{$order['freight_charge']|floatval}</b></td>
				</tr>
				<tr>
					<td>配送方式更改为:
					<if condition="$store['deliver_type'] eq 0 OR $store['deliver_type'] eq 3">
					<b style="color: red">{pigcms{$config['deliver_name']}</b>
					<else />
					<b style="color: red">商家配送</b>
					</if>
					</td>
				</tr>
				<tr>
					<td>配送距离:<b style="color: red">{pigcms{$distance|floatval}km</b></td>
				</tr>
				<tr>
					<td>【{pigcms{$time_select_1}】的配送费用:<b style="color: red">￥{pigcms{$delivery_fee|floatval}</b></td>
				</tr>
				<if condition="$have_two_time">
				<tr>
					<td>【{pigcms{$time_select_2}】的配送费用:<b style="color: red">￥{pigcms{$delivery_fee2|floatval}</b></td>
				</tr>
				</if>
				<if condition="$order['status'] eq 0">
				<tr>
					<td>预计送达时间:<input type="text" name="expect_use_time" value="{pigcms{$arrive_datetime}" id="expect_use_time" style="height: 24px;" readonly/></td>
				</tr>
				<tr>
					<td >
						 <button type="submit" class="submit" style="padding: 5px;margin: 12px auto;margin-top: 25px;background-color:#FF658E;border:1px solid #FF658E">更改配送方式</button>
					</td>
				</tr>
				<else />
				<tr>
					<td>预计送达时间:{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</td>
				</tr>
				</if>
				</form>
			</tbody>
		</table>
		<if condition="$order['cue_field']">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
			<tr>
				<th colspan="2"><strong>分类填写字段</strong></th>
			</tr>
			<volist name="order['cue_field']" id="vo">
				<tr>
					<td>{pigcms{$vo.title}</td>
					<td>{pigcms{$vo.txt}</td>
				</tr>
			</volist>
			</tbody>
		</table>
		</if>
	</ul>
	<a href="{pigcms{:U('Storestaff/shop_list')}" class="btn" style="float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;">返 回</a>
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
				<tr>
					<th>商品名称</th>
					<th class="cc">单价</th>
					<th class="cc">数量</th>
					<th class="rr">规格属性</th>
				</tr>
				<volist name="order['info']" id="info">
				<tr>
					<td style="color: blue">{pigcms{$info['name']} </td>
					<td class="cc">{pigcms{$info['price']|floatval}</td>
					<td class="cc" style="color: blue">{pigcms{$info['num']} <span style="color: gray; font-size:10px">({pigcms{$info['unit']})</span></td>
					<td class="rr">{pigcms{$info['spec']}</td>
				</tr>
				</volist>
				<tr>
					<td>商品总价</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">￥{pigcms{$order['goods_price']|floatval}</td>
				</tr>
				<if condition="$order['freight_charge'] gt 0">
				<tr>
					<td>{pigcms{$store['freight_alias']|default='配送费'}</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">￥{pigcms{$order['freight_charge']|floatval}</td>
				</tr>
				</if>
				<if condition="$order['packing_charge'] gt 0">
				<tr>
					<td>{pigcms{$store['pack_alias']|default='打包费'}</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">￥{pigcms{$order['packing_charge']|floatval}</td>
				</tr>
				</if>
				<tr>
					<td>总计</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">￥{pigcms{$order['total_price']|floatval}</span></td>
				</tr>
				<tr>
					<td>商家优惠</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">￥{pigcms{$order['merchant_reduce']|floatval}</span></td>
				</tr>
				<tr>
					<td>平台优惠</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">￥{pigcms{$order['balance_reduce']|floatval}</span></td>
				</tr>
				<tr>
					<td>优惠后总额</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">￥{pigcms{$order['price']|floatval}</span></td>
				</tr>
			</tbody>
		</table>
	</ul>
</div>
<div class="footReturn">
	<div class="clr"></div>
	<div class="window" id="windowcenter">
		<div id="title" class="wtitle">操作成功<span class="close" id="alertclose"></span></div>
		<div class="content">
			<div id="txt"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	var opt = {};
	opt.date = {preset:'datetime'};
	opt.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'bottom', //显示方式
		mode: 'scroller', //日期选择模式
		lang:'zh',
		minWidth: 64,
		setText: '确定', //确认按钮名称
		cancelText: '取消',//取消按钮
		dateFormat: 'yy-mm-dd'
	};
	$("#expect_use_time").scroller('destroy').scroller($.extend(opt['date'], opt['default']));
});
</script>