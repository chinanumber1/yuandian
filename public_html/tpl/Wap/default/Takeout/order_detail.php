<include file="header" />
<body onselectstart="return true;" ondragstart="return false;">
<div class="container">
	<section>
		<ul class="my_order">
			<li>
				<a href="{pigcms{:U('Takeout/menu', array('mer_id'=>$order['mer_id'],'store_id'=>$order['store_id']))}">
					<div>
						<div class="ico_status {pigcms{$order['css']}"><i></i>{pigcms{$order['show_status']}</div>
					</div>
					<div>
						<h3 class="highlight">{pigcms{$store['name']}</h3>
						<p>{pigcms{$order['total']}份/￥{pigcms{$order['price']}</p>
						<div>{pigcms{$order['date']}</div>
					</div>
					<div class="w14"><i class="ico_arrow"></i></div>
				</a>
			</li>
		</ul>
		<table class="my_menu_list">
			<thead>
				<tr>
					<th>商品列表</th>
					<th>{pigcms{$order['total']}份</th>
					<th><strong class="highlight">￥{pigcms{$order['price']-$order['delivery_fee']|floatval}</strong></th>
				</tr>
			</thead>
			<tbody>
				<volist name="order['info']" id="info">
				<tr>
					<td>{pigcms{$info['name']}</td>
					<td>X{pigcms{$info['num']}</td>
					<td>￥{pigcms{$info['price']|floatval}</td>
				</tr>
				</volist>
			</tbody>
		</table>

		<ul class="box">
			<li>订单号：{pigcms{$order['order_id']}</li>
			<li>下单人姓名：{pigcms{$order['name']}</li>
			<li>下单人手机：{pigcms{$order['phone']}</li>
			<li>配送地址：{pigcms{$order['address']}</li>
			<li>配送时间：{pigcms{$order['arrive_time']}</li>
			<li>配送费用：￥{pigcms{$order['delivery_fee']|floatval}</li>
			
			<li>在线支付金额:￥ {pigcms{$order['payment_money']|floatval} 元</li>
			<li>使用商家会员卡余额:￥ {pigcms{$order['merchant_balance']|floatval} 元</li>
			<li>余额支付金额:￥ {pigcms{$order['balance_pay']|floatval} 元</li>
			<if condition="$order['score_deducte'] gt 0"><li>{pigcms{$config['score_name']}抵扣费用：￥{pigcms{$order['score_deducte']|floatval} 元</li></if>
			<li>平台优惠券抵扣费用：￥{pigcms{$order.coupon_price|floatval} 元</li>
			<li>商家优惠券抵扣金额:￥ {pigcms{$order.card_price|floatval} 元</li>
			<li>线下需支付：
			<if condition="$order['total_price'] gt 0">
			￥{pigcms{$order['total_price']-$order['minus_price']-$order['balance_pay']-$order['payment_money']-$order['merchant_balance']-$order['coupon_price']-$order['card_price']-$order['score_deducte']|floatval}元
			<else />
			￥{pigcms{$order['price']-$order['balance_pay']-$order['merchant_balance']-$order['payment_money']-$order['coupon_price']-$order['card_price']-$order['score_deducte']|floatval}元
			</if>
			</li>
			<if condition="$order['paid'] AND $order['status'] eq 0">
				<li>消费二维码：<span id="see_storestaff_qrcode" style="color:#FF658E;">查看二维码</span></li>
			</if>
			<li>支付方式：{pigcms{$order['paytypestr']}</li>
			<li>支付状态：
				<if condition="empty($order['paid'])">未支付
				<elseif condition="$order['pay_type'] eq 'offline' AND empty($order['third_id'])" />线下未付款
				<elseif condition="$order['paid'] eq 2"  />
				<span style="color:green">已付￥{pigcms{$order['balance_pay']+$order['merchant_balance']+$order['coupon_price']+$order['card_price']+$order['score_deducte']+$order['payment_money']|floatval}</span>，
				<span style="color:red">未付
				<if condition="$order['total_price'] gt 0">
				￥{pigcms{$order['total_price']-$order['minus_price']-$order['payment_money']-$order['balance_pay']-$order['merchant_balance']-$order['coupon_price']-$order['card_price']-$order['score_deducte']|floatval}元
				<else />
				￥{pigcms{$order['price']-$order['balance_pay']-$order['payment_money']-$order['merchant_balance']-$order['coupon_price']-$order['card_price']-$order['score_deducte']|floatval}元
				</if>
				</span>
				<else /><span style="color:green">已支付</span>
				</if>
			</li>
			<li>订单状态：
				<if condition="empty($order['status'])"><span style="color:red">未使用</span>
				<elseif condition="$order['status'] eq 1" /><span style="color:green">已使用</span>
				<elseif condition="$order['status'] eq 2"  /><span style="color:green">已评价</span>
				<elseif condition="$order['status'] eq 3"  /><span style="color:red"><del>已退款</del></span>
				<elseif condition="$order['status'] eq 4"  /><span style="color:red"><del>已取消</del></span>
				</if>
			</li>
		</ul>
		<ul class="box">
			<li>备注</li>
			<li><if condition="$order['note']">{pigcms{$order['note']}<else />无</if></li>
		</ul>
	</section>
	<if condition="$order['status'] lt 3">
	<footer class="order_fixed">
		<div class="fixed">
			<if condition="$order['paid'] neq 1 AND $order['status'] eq 0">
				<div style="float: left">
					<a href="{pigcms{:U('Pay/check',array('order_id' => $order['order_id'], 'type'=>'takeout'))}" class="comm_btn" style="background-color: #5fb038;">支付订单</a>
				</div>
			</if>
			<div style="float: right">
				<if condition="$order['paid'] eq 0 AND $order['is_confirm'] eq 0 AND $order['status'] lt 3">
				<a class="comm_btn" href="{pigcms{:U('Takeout/orderdel', array('mer_id' => $order['mer_id'], 'store_id' => $order['store_id'], 'orderid' => $order['order_id']))}">取消订单</a> 
				<elseif condition="$order['paid'] eq 1 AND $order['status'] eq 0 AND $order['is_confirm'] eq 0" />
				<a class="comm_btn" href="{pigcms{:U('My/meal_order_refund', array('mer_id' => $order['mer_id'], 'store_id' => $order['store_id'], 'orderid' => $order['order_id']))}">取消订单</a> 
				</if>
			</div>
			<if condition="$order['status'] eq 1">
				<div style="float: right">
					<a href="{pigcms{:U('My/meal_feedback',array('order_id' => $order['order_id']))}" class="comm_btn">去评价</a>
				</div>	
			</if>
		</div>
	</footer>
	</if>
</div>
<include file="kefu" />
<script type="text/javascript">
	function drop_confirm(msg, url)
	{
		if (confirm(msg)) {
			window.location.href = url;
		}
	}
</script>
{pigcms{$hideScript}
</body>
</html>