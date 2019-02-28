<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 店铺管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('Waimai/order_edit')}" enctype="multipart/form-data">
		<input type="hidden" name="order_id" value="{pigcms{$now_order.order_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息：</b></td>
			</tr>
			<tr>
				<th width="80">ID</th>
				<td>{pigcms{$now_order.order_id}</td>
				<th width="80">用户昵称</th>
				<td>{pigcms{$now_order.nickname}</td>
			</tr>
			<tr>
				<th width="80">订单号</th>
				<td>{pigcms{$now_order.order_number}</td>
				<th width="80">收货码</th>
				<td>{pigcms{$now_order.code}</td>
			</tr>
			<tr>
				<th width="80">总价</th>
				<td>{pigcms{$now_order.price}</td>
				<th width="80">优惠后</th>
				<td>{pigcms{$now_order.discount_price}</td>
			</tr>
			<tr>
				<th width="80">支付状态</th>
				<td>
					<select name="paid">
					 	<option value="0" <if condition="$now_order['paid'] eq 0">selected</if>>未支付</option>
					 	<option value="1" <if condition="$now_order['paid'] eq 1">selected</if>>已支付</option>
					 	<option value="3" <if condition="$now_order['paid'] eq 3">selected</if>>支付超时或失败</option>
					</select>
				</td>
				<th width="80">订单状态</th>
				<td>
					<select name="order_status">
					 	<option value="0" <if condition="$now_order['order_status'] eq 0">selected</if>>订单失效</option>
					 	<option value="1" <if condition="$now_order['order_status'] eq 1">selected</if>>订单完成</option>
					 	<option value="2" <if condition="$now_order['order_status'] eq 2">selected</if>>商家未确认</option>
					 	<option value="3" <if condition="$now_order['order_status'] eq 3">selected</if>>商家已确认</option>
					 	<option value="4" <if condition="$now_order['order_status'] eq 4">selected</if>>已取货</option>
					 	<option value="5" <if condition="$now_order['order_status'] eq 5">selected</if>>正在配送</option>
					 	<option value="6" <if condition="$now_order['order_status'] eq 6">selected</if> disabled>用户退单</option>
					</select>
				</td>
			</tr>
			<tr>
				<th width="80">评论状态</th>
				<td>
					<input type="radio" name="comment_status" value="0"  <if condition="$now_order['comment_status'] eq 0">checked="checked"</if>/><span>未评论</span>
					<input type="radio" name="comment_status" value="1"  <if condition="$now_order['comment_status'] eq 1">checked="checked"</if>/><span>已评论</span>
				</td>
				<th width="80">支付类型</th>
				<td>{pigcms{$now_order.pay_type}</td>
			</tr>
			<tr>
				<th width="80">支付时间</th>
				<td><if condition="$now_order['pay_time']">{pigcms{$now_order.pay_time|date='Y-m-d H:i:s',###}</if></td>
				<th width="80">商家电话</th>
				<td>{pigcms{$now_order.phone}</td>
			</tr>
			<tr>
				<th width="80">预约配送时间</th>
				<td><if condition="$now_order['book_send_time']">{pigcms{$now_order.book_send_time|date='Y-m-d H:i:s',###}</if></td>
				<th width="80">订单送达时间</th>
				<td><if condition="$now_order['order_send_time']">{pigcms{$now_order.order_send_time|date='Y-m-d H:i:s',###}</if></td>
			</tr>
			<tr>
				<th width="80">创建时间</th>
				<td>{pigcms{$now_order.create_time|date='Y-m-d H:i:s',###}</td>
				<th width="80">最后修改时间</th>
				<td>{pigcms{$now_order.last_time|date='Y-m-d H:i:s',###}</td>
			</tr>
			<tr>
				<th width="80">备注</th>
				<td colspan="3">{pigcms{$now_order.desc}</td>
			</tr>
			<tr>
				<th width="80">送货地址</th>
				<td colspan="3">{pigcms{$now_order.address}</td>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td colspan="8" style="padding-left:5px;color:black;"><b>商品信息：</b></td>
			</tr>
			<tr>
				<th>商品ID</th>
				<th>创建时间</th>
				<th>商品名称</th>
				<th>商品图片</th>
				<th>数量</th>
				<th>单位</th>
				<th>原价</th>
				<th>现价</th>
			</tr>
			<volist name="now_order['goods_list']" id="vo">
				<tr>
					<th>{pigcms{$vo.goods_id}</th>
					<th>{pigcms{$vo.create_time|date='Y-m-d H:i:s',###}</th>
					<th>{pigcms{$vo.name}</th>
					<th>
					<volist name="vo['image']" id="voo">
						<img src="{pigcms{$voo.url}" style="width:85px;height:50px;"/>
					</volist>
					</th>
					<th>{pigcms{$vo.num}</th>
					<th>{pigcms{$vo.unit}</th>
					<th>{pigcms{$vo.old_price}</th>
					<th>{pigcms{$vo.price}</th>
				</tr>
			</volist>
		</table>
		<div style="float:right;">
			<button type="submit">提交</button>
		</div>
	</form>
	</body>
</html>