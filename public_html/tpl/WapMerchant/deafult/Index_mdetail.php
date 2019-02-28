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
		<a href="/index.php?g=WapMerchant&c=Index&a=morder" id="pigcms-header-left" class="iconfont icon-left">
		</a>
		<p id="pigcms-header-title">订单详情</p>
		<!--<a id="pigcms-header-right">操作日志</a>-->
	</header>
	<link href="{pigcms{$static_path}css/diancai.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div style="padding: 0.2rem;">
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge" style="margin-top: 50px">
			<tbody>
				<tr>
					<td>订单编号: {pigcms{$order['real_orderid']}</td>
				</tr>
				<if condition="$order.orderid neq 0">
				<tr>
					<td>流水号: <if condition="$order['pay_type'] neq 'baidu'">foodshop_</if>{pigcms{$order['orderid']}</td>
				</tr>
				</if>
			
				<tr>
					<td>客户姓名: {pigcms{$order['name']}</td>
				</tr>
				<tr>
					<td>客户手机: {pigcms{$order['phone']}</td>
				</tr>
			
				<tr>
					<td>桌台类型: {pigcms{$order['table_type_name']} </td>
				</tr>
			
				<tr>
					<td>桌台名称: {pigcms{$order['table_name']} </td>
				</tr>
			
				<tr>
					<td>下单时间: {pigcms{$order['create_time']|date="Y-m-d H:i:s",###} </td>
				</tr>
			
				<tr>
					<td>预订金额: {pigcms{$order['book_price']|floatval} 元</td>
				</tr>
			
				<if condition="$order['status'] gt 2 AND $order['status'] lt 5">
				<tr>
					<td>订单总价: ￥{pigcms{$order['total_price']|floatval} 元</td>
				</tr>
				<tr>
					<td>实付总价: ￥{pigcms{$order['price']|floatval} 元</td>
				</tr>
				<if condition="$order['book_pay_type']">
				<tr>
					<td>预订支付方式: {pigcms{$order['book_pay_type']}</td>
				</tr>
				</if>
				<if condition="$order['pay_type']">
				<tr>
					<td>订单支付方式: {pigcms{$order['pay_type']}</td>
				</tr>
				</if>
				</if>
				<tr>
					<td>订单状态：{pigcms{$order['show_status']} </td>
				</tr>
			
				<tr>
					<td>备注: {pigcms{$order['note']|default="无"}</td>
				</tr>
			</tbody>
		</table>
	</ul>
	<a href="{pigcms{:U('Storestaff/meal_list')}" class="btn" style="float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;">返 回</a>
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
				<tr>
					<th>菜品名称</th>
					<th class="cc">单价</th>
					<th class="cc">购买份数</th>
					<th class="rr">规格属性详情</th>
				</tr>
				<volist name="order['info']" id="info">
				<tr>
					<td style="color:#9F0050">{pigcms{$info['name']}</td>
					<td class="cc" style="color:#9F0050">{pigcms{$info['price']|floatval}</td>
					<td class="cc" style="color:#9F0050">{pigcms{$info['num']|floatval}/{pigcms{$info['unit']}</td>
					<td class="rr" style="color:#9F0050">{pigcms{$vo['spec']}</td>
				</tr>
				</volist>
				<tr>
					<td>总计</td>
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