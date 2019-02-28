<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('Store/shop_change_price')}" enctype="multipart/form-data">
		<input type="hidden" name="order_id" value="{pigcms{$order.order_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th colspan="1">订单编号</th>
				<th colspan="3">{pigcms{$order['real_orderid']}</th>
			</tr>
			<if condition="$order.orderid neq 0">
			<tr>
				<th colspan="1">订单流水号</th>
				<th colspan="3"><if condition="$order['pay_type'] neq 'baidu'">shop_</if>{pigcms{$order['orderid']}</th>
			</tr>
			</if>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th><strong>商品名称</strong></th>
				<th><strong>单价</strong></th>
				<th><strong>数量</strong></th>
				<th><strong>规格属性详情</strong></th>
			</tr>
			<volist name="order['info']" id="vo">
			<tr>
				<th style="color:#9F0050">{pigcms{$vo['name']}</th>
				<th style="color:#9F0050">{pigcms{$vo['price']|floatval}</th>
				<th style="color:#9F0050"><strong>{pigcms{$vo['num']}</strong> / {pigcms{$vo['unit']}</th>
				<th style="color:#9F0050">{pigcms{$vo['spec']}</th>
			</tr>
			</volist>
			<tr >
				<th><strong>总价</strong></th>
				<th>{pigcms{$order['goods_price']|floatval}</th>
				<th colspan="2">{pigcms{$order['num']}</th>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th><strong>支付状态</strong></th>
				<th style="color: green">{pigcms{$order['pay_status']}</th>
				
			</tr>
			
			<tr>
				<th><strong>应付总价</strong></th>
				<th style="color: green">{pigcms{$order['price']|floatval}</th>
			</tr>
			<tr>
			<th><strong>修改价格</strong></th>
			<th><input type="text" name="change_price" value="{pigcms{$order['price']|floatval}" style="height: 24px;width:80px"/><button type="submit">提交</button></th>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th><strong>客户信息</strong></th>
			</tr>
			<tr>
				<th>客户姓名：{pigcms{$order['username']}</th>
			</tr>
			<tr>
				<th>客户手机：{pigcms{$order['userphone']}</th>
			</tr>
			<if condition="$order['register_phone']">
			<tr>
				<th style="color:red">客户注册手机：{pigcms{$order['register_phone']}</th>
			</tr>
			</if>
			<if condition="$order['is_pick_in_store'] eq 2">
				<tr>
					<th >自提地址：{pigcms{$order['address']}</th>
				</tr>
			<else />
				<tr>
					<th >客户地址：{pigcms{$order['address']}</th>
				</tr>
			</if>
			<tr>
				<th>下单时间：{pigcms{$order['create_time']|date="Y-m-d H:i:s",###} </th>
			</tr>
			<if condition="$order['pay_time']">
				<tr>
					<th>支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </th>
				</tr>
			</if>
			<if condition="$order['expect_use_time']">
				<tr>
					<th>到货时间：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</th>
				</tr>
			</if>
		</table>
		<if condition="$order['cue_field']">
			<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
				<tr>
					<th colspan="2"><strong>分类填写字段</strong></th>
				</tr>
				<volist name="order['cue_field']" id="vo">
					<tr>
						<th>{pigcms{$vo.title}</th>
						<th>{pigcms{$vo.txt}</th>
					</tr>
				</volist>
			</table>
		</if>
	</form>
</body>
</html>