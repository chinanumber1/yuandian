<include file="Public:header"/>
<script src="{pigcms{$static_public}js/layer/layer.js"></script> 
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	<tr>
		<th>订单编号</th>
		<th>{pigcms{$order['order_id']}</th>
	</tr>
	<tr>
		<th>商品名称</th>
		<th>{pigcms{$order['name']}</th>
	</tr>
	<tr>
		<th>卖家商家名称</th>
		<th>{pigcms{$merchant['name']}</th>
	</tr>
	<tr>
		<th>卖家商家电话</th>
		<th>{pigcms{$merchant['phone']}</th>
	</tr>
	<tr>
		<th>卖家店铺名称</th>
		<th>{pigcms{$merchant_store['name']}</th>
	</tr>
	<tr>
		<th>卖家店铺电话</th>
		<th>{pigcms{$merchant_store['phone']}</th>
	</tr>
	<tr>
		<th>买家商家名称</th>
		<th>{pigcms{$buy_merchant['name']}</th>
	</tr>
	<tr>
		<th>买家商家电话</th>
		<th>{pigcms{$buy_merchant['phone']}</th>
	</tr>
	
    <if condition="empty($order['list'])">
    	<tr>
    		<th>商品条形码</th>
    		<th>{pigcms{$order['number']}</th>
    	</tr>
    	<tr>
    		<th >批发价</th>
    		<th>{pigcms{$order['price']|floatval}</th>
    	</tr>
    </if>
    
    <if condition="$order['discount_info']">
        <tr>
    		<th colspan="2">批发满：<span style="color:red">{pigcms{$order['discount_info']['num']}</span> {pigcms{$order['unit']}, 享受：<span style="color:red">{pigcms{$order['discount_info']['discount']}</span> 折优惠</th>
    	</tr>
    </if>
    <if condition="$order['spec_list']">
        <tr>
        <td colspan="2">
    	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
    	<tbody>
    		<tr>
    			<th>商品条形码</th>
    			<volist name="order['spec_list']" id="gs">
    			<th>{pigcms{$gs['name']}</th>
    			</volist>
    			<th>批发价(元)</th>
    			<th>本次批发数({pigcms{$order['unit']})</th>
    			<th>总价(元)</th>
    		</tr>
    		
    		<volist name="order['list']" id="gl">
    			<tr>
    				<td>{pigcms{$gl['number']}</td>
    				<volist name="gl['spec']" id="g">
    				<td>{pigcms{$g['spec_val_name']}</td>
    				</volist>
    				<td>{pigcms{$gl['price']|floatval}</td>
    				<td>{pigcms{$gl['stock_num']}</td>
    				<td>{pigcms{$gl['stock_num'] * $gl['price']}</td>
    			</tr>
    		</volist>
    	</tbody>
    	</table>
    	</td>
    	</tr>
    </if>
	<tr>
		<th>批发总数</th>
		<th>{pigcms{$order.num}({pigcms{$order.unit})</th>
	</tr>
	<tr>
		<th>总金额</th>
		<th>{pigcms{$order.total_price|floatval}(元)</th>
	</tr>
	<if condition="$order['discount_info']">
	<tr>
		<th>优惠后的总价</th>
		<th>{pigcms{$order.money|floatval}(元)</th>
	</tr>
	</if>

	<tr>
		<th>备注</th>
		<th><span style="color:red">{pigcms{$order['desc']|default="无"}</span></th>
	</tr>
	
	<tr>
		<th>收货人姓名</th>
		<th>{pigcms{$order['username']}</th>
	</tr>
	<tr>
		<th>收货人电话</th>
		<th>{pigcms{$order['userphone']}</th>
	</tr>
	
	<tr>
		<th>收货人地址</th>
		<th>{pigcms{$order['address']}</th>
	</tr>
	<if condition="$order['express_id']">
	<tr>
		<th>快递公司</th>
		<th>{pigcms{$order['express_name']}</th>
	</tr>
	<tr>
		<th>快递单号</th>
		<th>{pigcms{$order['express_number']}</th>
	</tr>
	<tr>
		<th>发货备注</th>
		<th>{pigcms{$order['sell_note']|default="无"}</th>
	</tr>
	</if>
	<if condition="$order['create_time']">
	<tr>
		<th>下单时间</th>
		<th>{pigcms{$order['create_time']|date="Y-m-d H:i:s",###}</th>
	</tr>
	</if>
	<if condition="$order['pay_time']">
	<tr>
		<th>支付时间</th>
		<th>{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###}</th>
	</tr>
	</if>
	<if condition="$order['send_time']">
	<tr>
		<th>发货时间</th>
		<th>{pigcms{$order['send_time']|date="Y-m-d H:i:s",###}</th>
	</tr>
	</if>
	<if condition="$order['pull_time']">
	<tr>
		<th>收货时间</th>
		<th>{pigcms{$order['pull_time']|date="Y-m-d H:i:s",###}</th>
	</tr>
	</if>
</table>

<script>
	function refund_confirm(){
		layer.confirm('确认后订单状态改为已退款，金额请通过其他渠道手动退款给客户！', {
			btn: ['确定','取消'] //按钮
		}, function(){
			window.location.href='{pigcms{:U('Shop/refund_update',array('order_id'=>$order['order_id']))}';
		});
		//
	}
</script>
<include file="Public:footer"/>