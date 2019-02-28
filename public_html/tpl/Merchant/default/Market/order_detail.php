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
            <th>订单编号</th>
            <th>商品信息</th>
            <th>商家信息</th>
            <th>购买信息</th>
            <th>规格商品信息</th>
        </tr>
        <volist name="orderList" id="order">
        <tr>
            <td>{pigcms{$order['order_id']}</td>
            <td>商品名称:{pigcms{$order['name']}
            <php> if (!empty($order['number'])) {</php>
            <br/>商品条形码:{pigcms{$order['number']}
            <php>}</php>
            </td>
            <if condition="$_GET['type'] eq 'buy'">
                <td>卖家商家名称:{pigcms{$order['merchant_name']}<br/>卖家商家电话:{pigcms{$order['merchant_phone']}<br/>卖家店铺名称:{pigcms{$order['store_name']}<br/>卖家店铺电话:{pigcms{$order['store_phone']}</td>
            <elseif condition="$_GET['type'] eq 'sell'" />
                <td>买家商家名称:{pigcms{$order['merchant_name']}<br/>买家商家电话:{pigcms{$order['merchant_phone']}</td>
            </if>
            <td>批发总数:{pigcms{$order.num}({pigcms{$order.unit})
            <br/>批发价:{pigcms{$order['price']|floatval}(元)
            <br/>总金额:{pigcms{$order.total_price|floatval}(元)
            <php> if (!empty($order['discount_info'])) {</php>
            <br/>优惠详情：<span style="color:red">{pigcms{$order['discount_info']['num']}</span> {pigcms{$order['unit']}, 享受：<span style="color:red">{pigcms{$order['discount_info']['discount']}</span> 折优惠
            <br/>优惠后的总价:{pigcms{$order.money|floatval}(元)
            <php>}</php>
            
        </td>
        
         <if condition="$order['spec_list']">
        <td>
            <table>
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
        <else />
        <td></td>
        </if>
        
        </tr>
        </volist>
    	
    	<tr>
    		<th>收货人姓名</th>
    		<th colspan="4">{pigcms{$userData['username']}</th>
    	</tr>
    	<tr>
    		<th>收货人电话</th>
    		<th colspan="4">{pigcms{$userData['userphone']}</th>
    	</tr>
    	
    	<tr>
    		<th>收货人地址</th>
    		<th colspan="4">{pigcms{$userData['address']}</th>
    	</tr>
    	<tr>
    		<th>备注</th>
    		<th colspan="4"><span style="color:red">{pigcms{$userData['desc']|default="无"}</span></th>
    	</tr>
        <tr>
            <th>卖家备注</th>
            <th colspan="4"><span style="color:red">{pigcms{$userData['sell_note']|default="无"}</span></th>
        </tr>
    	<if condition="$userData['express_id']">
    	<tr>
    		<th>快递公司</th>
    		<th colspan="4">{pigcms{$userData['express_name']}</th>
    	</tr>
    	<tr>
    		<th>快递单号</th>
    		<th colspan="4">{pigcms{$userData['express_number']}</th>
    	</tr>
    	<tr>
    		<th>发货备注</th>
    		<th colspan="4">{pigcms{$userData['sell_note']|default="无"}</th>
    	</tr>
    	</if>
    	<if condition="$userData['create_time']">
    	<tr>
    		<th>下单时间</th>
    		<th colspan="4">{pigcms{$userData['create_time']|date="Y-m-d H:i:s",###}</th>
    	</tr>
    	</if>
    	<if condition="$userData['pay_time']">
    	<tr>
    		<th>支付时间</th>
    		<th colspan="4">{pigcms{$userData['pay_time']|date="Y-m-d H:i:s",###}</th>
    	</tr>
    	</if>
    	<if condition="$userData['send_time']">
    	<tr>
    		<th>发货时间</th>
    		<th colspan="4">{pigcms{$userData['send_time']|date="Y-m-d H:i:s",###}</th>
    	</tr>
    	</if>
    	<if condition="$userData['pull_time']">
    	<tr>
    		<th>收货时间</th>
    		<th colspan="4">{pigcms{$userData['pull_time']|date="Y-m-d H:i:s",###}</th>
    	</tr>
    	</if>
    </table>
</body>
</html>