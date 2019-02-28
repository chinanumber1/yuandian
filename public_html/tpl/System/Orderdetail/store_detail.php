<include file="Public:header"/>
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	<tr>
		<th colspan="1">订单编号</th>
		<th colspan="3">{pigcms{$order['order_id']}</th>
	</tr>
	<if condition="$order.orderid neq 0">
	<tr>
		<th colspan="1">流水号</th>
		<th colspan="3">store_{pigcms{$order['orderid']}</th>
	</tr>
	</if>
	<tr>
		<th colspan="1">名称</th>
		<th colspan="3">{pigcms{$order['name']}</th>
	</tr>
	
	
	<tr>
		<th colspan="4">客户姓名：{pigcms{$order['nickname']}</th>
	</tr>
	<tr>
		<th colspan="4">客户手机：{pigcms{$order['phone']}</th>
	</tr>
	
	<tr>
		<th colspan="4">下单时间：{pigcms{$order['dateline']|date="Y-m-d H:i:s",###} </th>
	</tr>
	<if condition="$order['pay_time']">
	<tr>
		<th colspan="4">支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </th>
	</tr>
	</if>
	
	<tr>
		<th colspan="4">商品总价：￥{pigcms{$order['total_price']|floatval} 元</th>
	</tr>
	<tr>
		<th colspan="4">优惠费用：￥{pigcms{$order['discount_price']|floatval} 元</th>
	</tr>
	<tr>
		<th colspan="4">实际支付：￥{pigcms{$order['balance_pay']+$order['merchant_balance']+$order['card_give_money']+$order['payment_money']|floatval} 元</th>
	</tr>
	
	<if condition="$order['balance_pay']">
	<tr>
		<th colspan="4">平台余额支付：￥{pigcms{$order['balance_pay']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['merchant_balance']">
	<tr>
		<th colspan="4">商家余额支付：￥{pigcms{$order['merchant_balance']+$order['card_give_money']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['payment_money']">
	<tr>
		<th colspan="4">在线支付：￥{pigcms{$order['payment_money']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['score_deducte']">
	<tr>
		<th colspan="4">平台{pigcms{$config['score_name']}抵扣：￥{pigcms{$order['score_deducte']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['coupon_price']">
	<tr>
		<th colspan="4">使用平台优惠券：￥{pigcms{$order['coupon_price']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['card_id']">
	<tr>
		<th colspan="4">店铺优惠券金额：￥{pigcms{$order['card_price']} 元</th>
	</tr>
	</if>
	
	<tr>
		<th colspan="4">支付方式：{pigcms{$pay_method}</th>
	</tr>
	
</table>
<include file="Public:footer"/>