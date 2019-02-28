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
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	<tr>
		<th colspan="1">订单编号</th>
		<th colspan="3">{pigcms{$order['real_orderid']}</th>
	</tr>
	<if condition="$order.orderid neq 0">
	<tr>
		<th colspan="1">流水号</th>
		<th colspan="3"><if condition="$order['pay_type'] neq 'baidu'">shop_</if>{pigcms{$order['orderid']}</th>
	</tr>
	</if>
	<if condition="$order['shop_pass']">
	<tr>
		<th colspan="1">消费码</th>
		<th colspan="3">{pigcms{$order['shop_pass']}</th>
	</tr>
	</if>
	<tr>
		<th width="180">商品名称</th>
		<th>单价</th>
		<th>数量</th>
		<th>规格属性详情</th>
	</tr>
	<volist name="order['info']" id="vo">
	<tr>
		<th width="180">{pigcms{$vo['name']}</th>
		<th>{pigcms{$vo['price']|floatval}</th>
		<th>{pigcms{$vo['num']}</th>
		<th>{pigcms{$vo['spec']}</th>
	</tr>
	</volist>
	
	<tr>
		<th colspan="4">客户姓名：{pigcms{$order['username']}</th>
	</tr>
	<tr>
		<th colspan="4">客户手机：{pigcms{$order['userphone']}</th>
	</tr>
	<if condition="$order['is_pick_in_store'] eq 2">
	<tr>
		<th colspan="4">自提地址：{pigcms{$order['address']}</th>
	</tr>
	<else />
	<tr>
		<th colspan="4">客户地址：{pigcms{$order['address']}</th>
	</tr>
	</if>
	<tr>
		<th colspan="4">配送方式：{pigcms{$order['deliver_str']}</th>
	</tr>
	<tr>
		<th colspan="4">配送状态：{pigcms{$order['deliver_status_str']}</th>
	</tr>
	<if condition="$order['deliver_user_info']">
	<tr>
		<th colspan="4">配送员姓名：{pigcms{$order['deliver_user_info']['name']}</th>
	</tr>
	<tr>
		<th colspan="4">配送员电话：{pigcms{$order['deliver_user_info']['phone']}</th>
	</tr>
	</if>
	<tr>
		<th colspan="4">下单时间：{pigcms{$order['create_time']|date="Y-m-d H:i:s",###} </th>
	</tr>
	<if condition="$order['pay_time']">
	<tr>
		<th colspan="4">支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </th>
	</tr>
	</if>
	<if condition="$order['expect_use_time']">
	<tr>
		<th colspan="4">到货时间：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</th>
	</tr>
	</if>
	<tr>
		<th colspan="4">商品总价：￥{pigcms{$order['goods_price']|floatval} 元</th>
	</tr>
	<tr>
		<th colspan="4">配送费用：￥{pigcms{$order['freight_charge']|floatval} 元</th>
	</tr>
	<tr>
		<th colspan="4">订单总价：￥{pigcms{$order['total_price']|floatval} 元</th>
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
	<tr>
		<th colspan="4">实付金额：￥{pigcms{$order['price']|floatval} 元</th>
	</tr>
	<if condition="$order['score_used_count']">
	<tr>
		<th colspan="4">使用{pigcms{$config['score_name']}：{pigcms{$order['score_used_count']} </th>
	</tr>
	<tr>
		<th colspan="4">{pigcms{$config['score_name']}抵现：￥{pigcms{$order['score_deducte']|floatval} 元</th>
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
	<if condition="$order['pay_type'] eq 'offline' AND empty($order['third_id'])">
	<tr>
		<th colspan="4">线下需支付：￥{pigcms{$order['price']-$order['card_price']-$order['merchant_balance']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-$order['coupon_price']|floatval}元</th>
	</tr>
	</if>
	<tr>
		<th colspan="4">支付状态：{pigcms{$order['pay_status']}</th>
	</tr>
	<tr>
		<th colspan="4">支付方式：{pigcms{$order['pay_type_str']}</th>
	</tr>
	<tr>
		<th colspan="4">订单状态：{pigcms{$order['status_str']}</th>
	</tr>
	<tr>
		<th colspan="4">备注:{pigcms{$order['desc']|default="无"}</th>
	</tr>
	<if condition="$order['invoice_head']">
	<tr>
		<th colspan="4">发票抬头:{pigcms{$order['invoice_head']}</th>
	</tr>
	</if>
</table>
<script type="text/javascript">
			$(function(){
				$('#merchant_remark_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Group/group_remark',array('order_id'=>$now_order['order_id']))}",{merchant_remark:$('#merchant_remark').val()},function(result){
						$('#merchant_remark_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
				$('#store_id_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Group/order_store_id',array('order_id'=>$now_order['order_id']))}",{store_id:$('#order_store_id').val()},function(result){
						$('#store_id_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
			});
		</script>
	</body>
</html>