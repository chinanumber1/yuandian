<include file="Public:header"/>
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">

	<if condition="$order.orderid neq 0">
	<tr>
		<th colspan="1">流水号</th>
		<th colspan="2"><if condition="$order['pay_type'] neq 'baidu'"><if condition="$order['meal_type']" >takeout_<else />food_</if> </if>{pigcms{$order['orderid']}</th>
	</tr>
	</if>
	<if condition="$order['info']">
	<tr>
		<th width="180">商品名称</th>
		<th>单价</th>
		<th>数量</th>
		<th>规格属性详情</th>
	</tr>
	<volist name="order['info']" id="vo">
	<tr>
		<th width="180" style="color:#9F0050">{pigcms{$vo['name']}<if condition="$vo['package_id']"> <span style="color:green;">　(套餐)</span></if></th>
		<th style="color:#9F0050">{pigcms{$vo['price']|floatval}</th>
		<th style="color:#9F0050"><strong>{pigcms{$vo['num']|floatval}</strong> / {pigcms{$vo['unit']}</th>
		<th style="color:#9F0050">{pigcms{$vo['spec']}</th>
	</tr>
	</volist>
	</if>
	
	<tr>
		<th colspan="4">客户姓名：{pigcms{$order['name']}</th>
	</tr>
	<tr>
		<th colspan="4">客户手机：{pigcms{$order['phone']}</th>
	</tr>
	<if condition="$order['register_phone']">
	<tr>
		<th colspan="4" style="color:red">客户注册手机：{pigcms{$order['register_phone']}</th>
	</tr>
	</if>

	<tr>
		<th colspan="4">桌台类型：{pigcms{$order['table_type_name']} </th>
	</tr>

	<tr>
		<th colspan="4">桌台名称：{pigcms{$order['table_name']} </th>
	</tr>

	<tr>
		<th colspan="4">下单时间：{pigcms{$order['create_time']|date="Y-m-d H:i:s",###} </th>
	</tr>

	<tr>
		<th colspan="4">预订金额：{pigcms{$order['book_price']|floatval} 元</th>
	</tr>

	<if condition="$order['status'] gt 2 AND $order['status'] lt 5">
	<tr>
		<th colspan="4">订单总价：￥{pigcms{$order['total_price']|floatval} 元<if condition="$config.open_extra_price eq 1 AND $order.extra_price gt 0">+{pigcms{$order.extra_price}{pigcms{$config.extra_price_alias_name}</if></th>
	</tr>
	
	<if condition="$order['merchant_reduce'] gt 0">
	<tr>
		<th colspan="4">店铺优惠：￥{pigcms{$order['merchant_reduce']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['balance_reduce'] gt 0">
	<tr>
		<th colspan="4">平台优惠：￥{pigcms{$order['balance_reduce']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['score_used_count']">
	<tr>
		<th colspan="4">使用{pigcms{$config.score_name}：{pigcms{$order['score_used_count']} </th>
	</tr>
	<tr>
		<th colspan="4">{pigcms{$config.score_name}抵现：￥{pigcms{$order['score_deducte']|floatval} 元</th>
	</tr>
	</if>
	
	<if condition="$order['merchant_balance']">
	<tr>
		<th colspan="4">商家余额：￥{pigcms{$order['merchant_balance']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['balance_pay']">
	<tr>
		<th colspan="4">平台余额：￥{pigcms{$order['balance_pay']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['payment_money']">
	<tr>
		<th colspan="4">在线支付：￥{pigcms{$order['payment_money']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['card_id']">
	<tr>
		<th colspan="4">店铺优惠券金额：￥{pigcms{$order['card_price']} 元</th>
	</tr>
	</if>
	<if condition="$order['coupon_id']">
	<tr>
		<th colspan="4">平台优惠券金额：￥{pigcms{$order['coupon_price']} 元</th>
	</tr>
	</if>
	<tr>
		<th colspan="4">实付总价：￥{pigcms{$order['price']|floatval} 元</th>
	</tr>
	<if condition="$order['book_pay_type']">
	<tr>
		<th colspan="4">预订支付方式：{pigcms{$order['book_pay_type']}</th>
	</tr>
	</if>
	<if condition="$order['pay_type']">
	<tr>
		<th colspan="4">订单支付方式：{pigcms{$order['pay_type']}</th>
	</tr>
	</if>
	</if>
	<tr>
		<th colspan="4">订单状态：{pigcms{$order['show_status']} </th>
	</tr>
	<tr>
		<th colspan="4">备注:{pigcms{$order['note']|default="无"}</th>
	</tr>
</table>
<include file="Public:footer"/>