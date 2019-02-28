<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('Store/check_deliver')}" enctype="multipart/form-data">
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
				<th><strong>支付方式</strong></th>
				<th>{pigcms{$order['pay_type_str']}</th>
			</tr>
			<tr>
				<th>线下需支付</th>
				<th style="color: red">￥{pigcms{$order['offline_price']|floatval}元</th>
				<th>发票信息</th>
				<th>{pigcms{$order.invoice_head}</th>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>客户信息</strong></th>
			</tr>
			<tr>
				<th>客户姓名：{pigcms{$order['username']}</th>
				<th>客户手机：{pigcms{$order['userphone']}</th>
			</tr>
			<if condition="$order['register_phone']">
			<tr>
				<th colspan="2" style="color:red">客户注册手机：{pigcms{$order['register_phone']}</th>
			</tr>
			</if>
			<if condition="$order['is_pick_in_store'] eq 2">
				<tr>
					<th colspan="2">自提地址：{pigcms{$order['address']}</th>
				</tr>
			<else />
				<tr>
					<th colspan="2">客户地址：{pigcms{$order['address']}</th>
				</tr>
			</if>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>修改配送方式</strong> <b style="color:red">（修改后的配送费超出部分由商家自出）</b></th>
			</tr>
			<tr>
				<th>快速配送收取的配送费</th>
				<th style="color: red">￥{pigcms{$order['freight_charge']|floatval}</th>
			</tr>
			<tr>
				<th>配送方式更改为</th>
				<if condition="$store['deliver_type'] eq 0 OR $store['deliver_type'] eq 3">
				<th style="color: red">{pigcms{$config['deliver_name']}</th>
				<else />
				<th style="color: red">商家配送</th>
				</if>
			</tr>
			<tr>
				<th>配送距离</th>
				<th style="color: red">{pigcms{$distance|floatval}km</th>
			</tr>
			<tr>
				<th>【{pigcms{$time_select_1}】的配送费用</th>
				<th style="color: red">￥{pigcms{$delivery_fee|floatval}</th>
			</tr>
			<if condition="isset($time_select_2)">
			<tr>
				<th>【{pigcms{$time_select_2}】的配送费用</th>
				<th style="color: red">￥{pigcms{$delivery_fee2|floatval}</th>
			</tr>
			</if>
            <if condition="isset($time_select_3)">
            <tr>
                <th>【{pigcms{$time_select_3}】的配送费用</th>
                <th style="color: red">￥{pigcms{$delivery_fee3|floatval}</th>
            </tr>
            </if>
			<if condition="$order['status'] eq 0">
			<tr>
				<th>预计送达时间</th>
				<th><input type="text" name="expect_use_time" value="{pigcms{$arrive_datetime}" id="expect_use_time" style="height: 24px;" readonly/></th>
			</tr>
			<tr>
				<th colspan="2">
					 <button type="submit">提交</button>
				</th>
			</tr>
			<else />
			<tr>
				<th>预计送达时间</th>
				<th>{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</th>
			</tr>
			</if>
		</table>
		
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>时间信息</strong></th>
			</tr>
			<tr>
				<th colspan="4">下单时间：{pigcms{$order['create_time']|date="Y-m-d H:i:s",###} </th>
			</tr>
			<if condition="$order['pay_time']">
				<tr>
					<th colspan="4">支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </th>
				</tr>
			</if>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>费用信息</strong></th>
			</tr>
			<tr>
				<th colspan="4">商品总价：￥{pigcms{$order['goods_price']|floatval} 元</th>
			</tr>
			<if condition="$order['packing_charge'] gt 0">
			<tr>
				<th colspan="4">{pigcms{$store['pack_alias']|default='打包费'}：￥{pigcms{$order['packing_charge']|floatval} 元</th>
			</tr>
			</if>
			<if condition="$order['freight_charge'] gt 0">
			<tr>
				<th colspan="4">配送费用：￥{pigcms{$order['freight_charge']|floatval} 元</th>
			</tr>
			</if>
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
			<if condition="$order['card_discount'] neq 0 AND $order['card_discount'] neq 10">
			<tr>
				<th colspan="4">会员卡：{pigcms{$order['card_discount']|floatval} 折优惠</th>
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
			
			<if condition="$order['card_give_money'] gt 0">
			<tr>
				<th colspan="4">会员卡余额：￥{pigcms{$order['card_give_money']|floatval} 元</th>
			</tr>
			</if>
			
			<if condition="$order['merchant_balance'] gt 0">
			<tr>
				<th colspan="4">商家余额：￥{pigcms{$order['merchant_balance']|floatval} 元</th>
			</tr>
			</if>
			<if condition="$order['balance_pay'] gt 0">
			<tr>
				<th colspan="4">平台余额：￥{pigcms{$order['balance_pay']|floatval} 元</th>
			</tr>
			</if>
			<if condition="$order['payment_money'] gt 0">
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
				<th colspan="4">备注:{pigcms{$order['desc']|default="无"}</th>
			</tr>
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
<script type="text/javascript">
			$(function(){
				$('#expect_use_time').click(function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})});
			});
		</script>
	</body>
</html>