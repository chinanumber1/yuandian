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
		<th colspan="3">{pigcms{$order['order_id']}</th>
	</tr>
	<tr>
		<th colspan="1">流水号</th>
		<th colspan="3"><if condition="$order['pay_type'] neq 'baidu'"><if condition="$order['meal_type']" >takeout_<else />food_</if> </if>{pigcms{$order['orderid']}</th>
	</tr>
	<tr>
		<th width="180">菜品名称</th>
		<th>单价</th>
		<th>数量</th>
		<th>菜品备注</th>
	</tr>
	<volist name="order['info']" id="vo">
	<tr>
		<th width="180">{pigcms{$vo['name']}</th>
		<th>{pigcms{$vo['price']}</th>
		<th>{pigcms{$vo['num']}</th>
		<th>{pigcms{$vo['omark']}</th>
	</tr>
	</volist>
	<tr>
		<th width="180">总价：￥<if condition="$order['total_price'] gt 0">{pigcms{$order['total_price']}<else />{pigcms{$order['price']}</if></th>
		<th colspan="3">优惠：￥{pigcms{$order['minus_price']}</th>
		
	</tr>
	<tr>
		<th>支付状态:　
		<if condition="empty($order['paid'])">未支付
		<elseif condition="$order['pay_type'] eq 'offline' AND empty($order['third_id'])" />线下未支付
		<elseif condition="$order['paid'] eq 2"  /><span style="color:green">已付￥{pigcms{$order['pay_money']}</span>，<span style="color:red">未付￥{pigcms{$order['price'] - $order['pay_money']}</span>
		<else /><span style="color:green">全额支付</span>
		</if>
		</th>
		<th colspan="3">支付方式：
		<if condition="$order['pay_type'] eq 'alipay'">
		<span style="color:green">支付宝</span>
		<elseif condition="$order['pay_type'] eq 'weixin'"/>
		<span style="color:green">微信支付</span>
		<elseif condition="$order['pay_type'] eq 'tenpay'"/>
		<span style="color:green">财付通[wap手机]</span>
		<elseif condition="$order['pay_type'] eq 'tenpaycomputer'"/>
		<span style="color:green">财付通[即时到帐]</span>
		<elseif condition="$order['pay_type'] eq 'yeepay'"/>
		<span style="color:green">易宝支付</span>
		<elseif condition="$order['pay_type'] eq 'allinpay'"/>
		<span style="color:green">通联支付</span>
		<elseif condition="$order['pay_type'] eq 'daofu'"/>
		<span style="color:green">货到付款</span>
		<elseif condition="$order['pay_type'] eq 'dianfu'"/>
		<span style="color:green">到店付款</span>
		<elseif condition="$order['pay_type'] eq 'chinabank'"/>
		<span style="color:green">网银在线</span>
		<elseif condition="$order['pay_type'] eq 'offline'"/>
		<span style="color:green">线下支付</span>
		<elseif condition="empty($order['pay_type']) AND $order['paid'] eq 1 AND $order['balance_pay'] gt 0" />
		<span style="color:green">平台余额支付</span>
		<elseif condition="empty($order['pay_type']) AND $order['paid'] eq 1 AND $order['merchant_balance'] gt 0" />
		<span style="color:green">平台商户支付</span>
		<else />
		<span style="color:green">暂未选择</span>
		</if>
		</th>
	
	</tr>
	<tr>
		<th colspan="4">余额支付金额:￥ {pigcms{$order['balance_pay']}</th>
	</tr>
	<tr>
		<th colspan="4">积分抵扣金额:￥ {pigcms{$order.score_deducte} 积分使用数量: {pigcms{$order.score_used_count}</th>
	</tr>
	<tr>
		<th colspan="4">在线支付金额:￥ {pigcms{$order['payment_money']}</th>
	</tr>
	<tr>
		<th colspan="4">使用商户余额:￥ {pigcms{$order['merchant_balance']}</th>
	</tr>
	<if condition="$system_coupon">
	<tr>
		<th colspan="4">平台优惠券抵扣金额:￥ {pigcms{$system_coupon.price}</th>
	</tr>
	<elseif condition="$card" />
	<tr>
		<th colspan="4">商家优惠券抵扣金额:￥ {pigcms{$card.price}</th>
	</tr>
	</if>
	<if condition="$order['pay_type'] eq 'offline' AND empty($order['third_id'])" >
	<tr>
		<th colspan="3">线下需向商家付金额：<font color="red">￥{pigcms{$order['total_price']-$order['wx_cheap']-$order['merchant_balance']-$order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price']}元</font></th>
	</tr>
	</if>
	<tr>
		<td colspan="4" style="line-height:22px;padding-top:15px;">
		姓名：{pigcms{$order['name']}<br/>
		电话：{pigcms{$order['phone']}<br/>
		使用人数：{pigcms{$order['num']}<br/>
		地址：{pigcms{$order['address']}
		</td>
	</tr>
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