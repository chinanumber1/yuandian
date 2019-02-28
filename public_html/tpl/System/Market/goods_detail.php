<include file="Public:header"/>
<script src="{pigcms{$static_public}js/layer/layer.js"></script> 
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	<tr>
		<th>商品名称</th>
		<th>{pigcms{$marketGoods['name']}</th>
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
	
    <if condition="empty($marketGoods['list'])">
    	<tr>
    		<th>商品条形码</th>
    		<th>{pigcms{$marketGoods['number']}</th>
    	</tr>
    	<tr>
    		<th >批发价</th>
    		<th>{pigcms{$marketGoods['price']}</th>
    	</tr>
        <tr>
            <th >库存</th>
            <th>{pigcms{$marketGoods['stock_num']}</th>
        </tr>
        <tr>
            <th >最低批发数</th>
            <th>{pigcms{$marketGoods['min_num']}</th>
        </tr>
    </if>
    <if condition="$marketGoods['discount_info']">
        <volist name="marketGoods['discount_info']" id="drow">
        <tr>
    		<th colspan="2">批发满：<span style="color:red">{pigcms{$drow['num']}</span> {pigcms{$marketGoods['unit']}, 享受：<span style="color:red">{pigcms{$drow['discount']}</span> 折优惠</th>
    	</tr>
    	</volist>
    </if>
    <if condition="$marketGoods['spec_list']">
        <tr>
        <td colspan="2">
    	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
    	<tbody>
			<tr>
				<th>商品条形码</th>
				<volist name="marketGoods['spec_list']" id="gs">
				<th>{pigcms{$gs['name']}</th>
				</volist>
				<th>批发价</th>
				<th>库存</th>
				<th>最低批发数</th>
			</tr>
			
			<volist name="marketGoods['list']" id="gl" key="id_index" >
				<tr id="{pigcms{$gl['index']}">
					<td>{pigcms{$gl['number']}</td>
					<volist name="gl['spec']" id="g">
					<td>{pigcms{$g['spec_val_name']}</td>
					</volist>
					
					<td>{pigcms{$gl['price']}</td>
					<td>{pigcms{$gl['stock_num']}</td>
					<td>{pigcms{$gl['min_num']}</td>
				</tr>
			</volist>
    	</tbody>
    	</table>
    	</td>
    	</tr>
    </if>
</table>
<include file="Public:footer"/>