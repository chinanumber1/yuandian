<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
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
#pigcms-header-left {font-size: 30px;}
</style>
<body>
	<!--头部结束-->
	<header class="pigcms-header mm-slideout">
		<a href="/index.php?g=WapMerchant&c=Index&a=sorder" id="pigcms-header-left" class="iconfont icon-left">
		</a>
		<p id="pigcms-header-title">订单详情</p>
		<!--<a id="pigcms-header-right">操作日志</a>-->
	</header>
	<link href="{pigcms{$static_path}css/diancai.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div style="padding: 0.2rem;">
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
				<tr>
					<td>流水号：{pigcms{$order.orderid}</td>
				</tr>
				<tr>
					<td>订单号：{pigcms{$order.order_id}</td>
				</tr>
				<tr>
					<td>客户姓名：{pigcms{$order.username}</td>
				</tr>
				<tr>
					<td>客户电话：<a href="tel:{pigcms{$order.userphone}" class="totel">{pigcms{$order.userphone}</a></td>
				</tr>
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
				<if condition="$order['is_pick_in_store'] eq 3 AND $order['express_id']">
				<tr>
					<td>快递公司：{pigcms{$order['express_name']}</td>
				</tr>
				<tr>
					<td>快递单号：{pigcms{$order['express_number']}</td>
				</tr>
				</if>
				<if condition="$order['deliver_user_info']">
					<tr>
						<td>配送员姓名：{pigcms{$order['deliver_user_info']['name']}</td>
					</tr>
					<tr>
						<td>配送员电话：{pigcms{$order['deliver_user_info']['phone']}</td>
					</tr>
				</if>
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

				<if condition="$order['merchant_balance']">
				<tr>
					<td>商家余额：￥{pigcms{$order['merchant_balance']|floatval} 元</td>
				</tr>
				</if>
				<if condition="$order['balance_pay']">
				<tr>
					<td>平台余额：￥{pigcms{$order['balance_pay']|floatval} 元</td>
				</tr>
				</if>
				<if condition="$order['payment_money']">
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
				<if condition="$order['pay_type'] eq 'offline' AND empty($order['third_id'])">
				<tr>
					<td>线下需支付：￥{pigcms{$order['price']-$order['card_price']-$order['merchant_balance']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-$order['coupon_price']|floatval}元</td>
				</tr>
				</if>
				<if condition="!empty($now_order['use_time'])">
					<tr>
						<td>使用时间： {pigcms{$order.use_time|date='Y-m-d H:i:s',###}</td>
					</tr>
					<tr>
						<td>操作店员： {pigcms{$order.last_staff}</td>
					</tr>
				</if>
			</tbody>
		</table>
	</ul>
	<a href="{pigcms{:U('Index/sorder')}" class="btn" style="float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;">返 回</a>
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
					<td>{pigcms{$info['name']} </td>
					<td class="cc">{pigcms{$info['price']|floatval}</td>
					<td class="cc">{pigcms{$info['num']} <span style="color: gray; font-size:10px">({pigcms{$info['unit']})</span></td>
					<td class="rr">{pigcms{$info['spec']}</td>
				</tr>
				</volist>
				<tr>
					<td>商品总价</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">￥{pigcms{$order['goods_price']|floatval}</td>
				</tr>
				<tr>
					<td>配送费</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">￥{pigcms{$order['freight_charge']|floatval}</td>
				</tr>
				<tr>
					<td>打包费</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">￥{pigcms{$order['packing_charge']|floatval}</td>
				</tr>
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

<!--<div class="footReturn">
	<div class="clr"></div>
	<div class="window" id="windowcenter">
		<div id="title" class="wtitle">操作成功<span class="close" id="alertclose"></span></div>
		<div class="content">
			<div id="txt"></div>
		</div>
	</div>
</div>--->

</body>
	<include file="Public:footer"/>
</html>